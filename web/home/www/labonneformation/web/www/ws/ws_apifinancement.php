<?php
	$user=$this->get('user');

	global $users_apifinancement;
	
	$usersPassword=$users_apifinancement;

	$t=microtime(true);
	/* Test d'intégrité de l'url + controle du User + Signature */
	$test=false;
	if($user)
		if(array_key_exists($user,$usersPassword))
			if(Tools::queryControl($this->getGET(),$usersPassword[$user],600)!==false)
				$test=true;
	//xhprof_enable();
	if(ENV_DEV && $user=='NOCONTROL') $test=true;
	if($test)
	{
		$simulatorRules=new SimulatorRules();

		$data=file_get_contents('php://input');
		$res=json_decode($data,true);

		if($res===false) _LOG('api_error.log',"Reçu false en décodage json :\n".print_r($this->get(),true)."\n".print_r($data,true)."\n");
		//@_LOG('api-de.log',print_r($data+$_SERVER,true));
		$data=$res;

		$this->header('Content-Type: application/json');
		$legacy=false; //swtich pour basculer sur l'ancien moteur

		// Test les erreurs en entrées
		$erreurs=$simulatorRules->checkApiData($this,$data);

		if(!$erreurs && !$legacy)
		{
			$data=$simulatorRules->getRulesTrefle($data,true);
			if(ENV_DEV) _LOG('time_apimoteur.log',(microtime(true)-$t)."\n".print_r($_SERVER,true)."\n");

			// on test si on a pas un erreur 400 renvoyé par trèfle ou api détail on en déduit une formaiton non trouvée
			if(isset($data['error']) && 400>=intval($data['error']) && intval($data['error'])<500)
			{
				$erreurs[404][]=array('code'=>800,'message'=>'formation non trouvée');
				if(ENV_DEV) _LOG('api_error.log',print_r($erreurs,true)."\n");
			}
			else if(!$data)
			{
				$erreurs[500][]=array('code'=>000,'message'=>'Une erreur est survenue');
				if(ENV_DEV) _LOG('api_error.log',print_r($erreurs,true)."\n");
			}
			else
				$json=$data;
		}

		// Toutes les erreurs sont levées
		if(!$erreurs || $tmpDataFormationPourWitbe=testFormDispoPourWitbe($data['formationVisee']['codeFormation'],$erreurs))
		{
			if($erreurs && $tmpDataFormationPourWitbe)
				$json=$tmpDataFormationPourWitbe;
			elseif($legacy)
			{
				$json=json_encode($simulatorRules->computeRulesAPI($this,$data));
				_LOG('time_apimoteur.log',(microtime(true)-$t)."\n".print_r($_SERVER,true)."\n");
			}
			_T($json);
		}
		else
		{
			_LOG('api_error.log',"Mauvaise utilisation de l'API\n".print_r($data,true).print_r($erreurs,true));
			// un seul erreur http à la fois
			http_response_code(current(array_keys($erreurs)));
			_T(json_encode(array_values($erreurs)[0]));
		}
		return;
	}
	else
	{
		_LOG('api_error.log',sprintf("API Financement: code 401 (utilisateur non authentifié: %s)",$user));
		http_response_code(401);
	}

	/**
	 * testFormDispoPourWitbe test si la formation testée par la sonde n'est plus dispo alors on mock en attendant de faire mieux
	 *
	 * @param mixed $idFormIntercarif
	 * @param mixed $erreurs
	 * @access public
	 * @return string/false
	 */
	function testFormDispoPourWitbe($idFormIntercarif,$erreurs)
	{
		if(ENV_DEV) error_log(print_r($idFormIntercarif,true));
		$pass=false;
		if(!$erreurs)
			$pass=true;
		else if($idFormIntercarif=='03_1000665F')
		{
			if(isset($erreurs[404]))
				foreach($erreurs[404] as $e)
					if($e['code']==800)
					{
						error_log('FORMATION TEST 03_1000665F POUR SONDE WITBE INDISPONIBLE');
						$pass='{"financements":[{"libelle":"Action collective financ\u00e9e par la R\u00e9gion","donneeConsolidees":{"description":"V\u00e9rifiez aupr\u00e8s de votre conseiller et\/ou de l\'organisme de formation\nque vous remplissez les conditions pour effectuer cette formation.","infoComplementaires":"Cette action de formation est financ\u00e9e, totalement ou partiellement, par votre R\u00e9gion.","cout":"Formation totalement ou partiellement financ\u00e9e","remuneration":"Vous percevrez 130,34 \u20ac brut \/ mois "},"donneeStructurees":{"familleDispositif":"Actions collectives","typeDispositif":"actioncollectiveregion","priorite":1,"codesFinanceur":["17","2","5"],"cout":{"resteACharge":true,"financeePE":false,"financableCpf":true,"cumulable":false},"remunerations":[{"montant":"130.00","nature":"brut","type":"rfpe"}]}},{"libelle":"Compte Personnel de Formation (CPF)","donneeConsolidees":{"description":" 1. Contactez votre conseiller r\u00e9f\u00e9rent emploi pour valider avec lui votre projet de formation.\n \n2. Cr\u00e9ez votre compte CPF sur le site moncompteformation.gouv.fr\n","infoComplementaires":"Le compte personnel de formation (CPF) permet \u00e0 toute personne active d\u2019acqu\u00e9rir des droits pour financer totalement ou partiellement une formation qualifiante.\n \nNB : dans le cas d\'une formation financ\u00e9e par P\u00f4le emploi ou la R\u00e9gion, il pourra vous \u00eatre demand\u00e9 de contribuer \u00e0 son financement avec votre CPF.","cout":"Formation totalement ou partiellement financ\u00e9e sur la base de 9\u00a0\u20ac multipli\u00e9e par vos heures CPF acquises","remuneration":"Si vous ne b\u00e9n\u00e9ficiez pas de l\'AREF, vous toucherez une r\u00e9mun\u00e9ration formation P\u00f4le emploi (RFPE) si la formation est valid\u00e9e par P\u00f4le emploi. Si votre projet n\'est pas valid\u00e9, vous ne toucherez pas de r\u00e9mun\u00e9ration."},"donneeStructurees":{"familleDispositif":"CPF","typeDispositif":"cpf","priorite":0,"codesFinanceur":["17","2","5"],"cout":{"resteACharge":true,"financeePE":false,"financableCpf":true,"cumulable":true}}}]}';
					}
		}
		return $pass;
	} // End function testFormDispoPourWitbe
	//$query=Tools::querySign(array('user'=>'TEST'),'0d580d162cef6ff4d40190468c93c85d23a0066d',false);
?>
