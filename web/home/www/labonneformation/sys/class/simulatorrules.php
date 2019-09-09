<?php

	include 'simulatorrules/constantes.php';
	include 'simulatorrules/financement.php';
	include 'simulatorrules/list.php';
	include 'simulatorrules/region.php';
	include 'simulatorrules/remu.php';

	include 'simulatorrules/regles/regles_auvergnerhonealpes.php';
	include 'simulatorrules/regles/regles_bourgognefranchecomte.php';
	include 'simulatorrules/regles/regles_bretagne.php';
	include 'simulatorrules/regles/regles_centre.php';
	include 'simulatorrules/regles/regles_corse.php';
	include 'simulatorrules/regles/regles_grandest.php';
	include 'simulatorrules/regles/regles_guadeloupe.php';
	include 'simulatorrules/regles/regles_hautsdefrance.php';
	include 'simulatorrules/regles/regles_iledefrance.php';
	include 'simulatorrules/regles/regles_lrmp.php';
	include 'simulatorrules/regles/regles_nationalesnew.php';
	include 'simulatorrules/regles/regles_normandie.php';
	include 'simulatorrules/regles/regles_nouvelleaquitaine.php';
	include 'simulatorrules/regles/regles_occitanie.php';
	include 'simulatorrules/regles/regles_paca.php';
	include 'simulatorrules/regles/regles_paysdelaloire.php';
	include 'simulatorrules/regles/regles_reunionmayotte.php';

	include 'simulatorrules/regles/regles_generiques.php';

	class DefaultParams
	{
		protected $array;

		public function __construct($array)
		{
			$this->array=$array;
		}
		public function get($key,$default='')
		{
			if(is_null($key)) return $this->array;
			return array_key_exists($key,$this->array)?$this->array[$key]:$default;
		}
	}

	/* Appel des règles (API) *********************************************************************************************/
	/*
	 * Dans l'ordre:
	 * Récupère la formation en BD à partir de l'id intercarif
	 * à Faire: remouliner les donnée $individu en donnée pour $quark->setGET()
	 * Compute les Rules avec l'ancienne fonction
	 * Remouline le résultat en tableau avec clefs francaises (Swagger)
	 *
	 */
	class SimulatorRules
	{
		public function computeRulesAPI($quark,$data)
		{
			$db=$quark->getStore('read');
			$adSearch=new AdSearch($db);
			$financementsRemanies=false;

			$formation=$data['formationVisee'];
			$individu=$this->getMappedIndividuData($quark,$data['individu']);

			// la formation est soit transmise dans data (API) soit il faut la chercher

			if($idFormIntercarif=$formation['codeFormation'])
				$a=(isset($data['ad'])?$data['ad']:$adSearch->getByIdFormIntercarifNew($idFormIntercarif));
			//$idFormIntercarif='23_54S1703192-0';
			$ar=new ArrayExplorer($a);

			if($a)
			{
				//$quark->setGet($individu);
				$displayGenerique=getList();
				if($financements=$this->computeRules($quark,$individu,$ad,$ar))
				{
					$financementsRemanies=array();
					if(!empty($financements))
						foreach($financements['droits'] as $type=>$financement)
						{
							/* les données pour l'API */
							$fin=array(
								'libelle'=>null,
								'donneeConsolidees'=>array(
									'description'=>null,
									'infoComplementaires'=>null,
									'cout'=>null,
									'remuneration'=>null,
								),
								'donneeStructurees'=>array(
									'familleDispositif'=>null,
									'typeDispositif'=>null,
									'priorite'=>null,
									'codesFinanceur'=>null,
									'cout'=>array(
										'montant'=>null,
										'plafond'=>null,
										'resteACharge'=>null,
										'financeePE'=>null,
										'financableCpf'=>null,
										'cumulable'=>null
									),
									'remunerations'=>array(
										array(
											'montant'=>null,
											'type'=>null,
											'dateFin'=>null,
											'nature'=>null
										)
									)
								)
							);

							/* type dispositif commun */
							$typeDispositif=preg_replace("#^([^_]*)_?.*$#","$1",$type);

							/* reformatage des demarches */
							if(is_array($displayGenerique[$typeDispositif]['step']))
								$displayGenerique[$typeDispositif]['step']=implode("\n",$displayGenerique[$typeDispositif]['step']);
							$demarches=Tools::htmlToTxt($displayGenerique[$typeDispositif]['step']);

							if(array_key_exists($typeDispositif,$displayGenerique))
							{
								// champ generique / commun
								$fin['libelle']=$financement['title'];
								$fin['donneeConsolidees']['description']=$demarches;
								$fin['donneeConsolidees']['infoComplementaires']=Tools::htmlToTxt($financement['descinfo']);
								$fin['donneeConsolidees']['cout']=$this->getCostConsolide($financement,$displayGenerique[$typeDispositif]);

								/* Remuneration */
								unset($fin['donneeConsolidees']['remuneration']);
								if(array_key_exists('indemnisation',$financement) && !empty($financement['indemnisation']))
									$fin['donneeConsolidees']['remuneration']=Tools::htmlToTxt($financement['indemnisation']);

								$fin['donneeStructurees']['familleDispositif']=$displayGenerique[$typeDispositif]['famille'];
								$fin['donneeStructurees']['typeDispositif']=$typeDispositif;
								$fin['donneeStructurees']['priorite']=$displayGenerique[$typeDispositif]['pri'];

								/* codes financeurs */
								unset($fin['donneeStructurees']['codesFinanceur']);
								//if(!empty($ad['codefinanceur'])) $fin['donneeStructurees']['codesFinanceur']=explode(' ',preg_replace('#\[.*?\]#','',$ad['codefinanceur']));
								if(!empty($ar['sessions[0]/financeurs,array()'])) $fin['donneeStructurees']['codesFinanceur']=array_map(function($e) {return $e['code'];},$ar['sessions[0]/financeurs,array()']->toArray());

								/* Cout */
								unset($fin['donneeStructurees']['cout']);
								$coutStructure=$this->getCoutStructure($financement,$displayGenerique[$typeDispositif]);
								if(!empty($coutStructure)) $fin['donneeStructurees']['cout']=$coutStructure;

								/* Rénumération structurée */
								unset($fin['donneeStructurees']['remunerations']);
								$remuStructure=$this->getRemuStructure($financement);
								if(!empty($remuStructure)) $fin['donneeStructurees']['remunerations']=$remuStructure;

							}
							else error_log('Dispositif inconnu: '.print_r($typeDispositif,true));

							$financementsRemanies[]=$fin;
						}
				}
			}
			return array('financements'=>$financementsRemanies);
		}

		public function getListeDispositifsFinancementDE($quark,$annonce,$ar)
		{
			$dispositifs=array('droits'=>array());
			$individu=$quark->getGET();

			//$legacy=filter_var($individu['legacy'],FILTER_VALIDATE_BOOLEAN);
			$legacy=false;
			if($legacy && $individu['situation_inscrit'])
			{
				$dispositifs=$this->computeRules($quark,$individu,$annonce,$ar);
				$dispositifs['type']='demandeuremploi';
				$this->ajoutDesOffresEmplois($quark,$individu,$annonce,$ar,$dispositifs['droits']);
			}
			else if($individu['situation_inscrit'])
			{
				$dispositifs['type']='demandeuremploi';
				$dataFinancementDE=$this->getDataFinancementDE($quark,$individu,$annonce,$ar);
				@_LOG('api-de.log',print_r($dataFinancementDE+$_SERVER,true));
				$financementDE=$this->getRulesTrefle($dataFinancementDE);
				if($financementDE!==false && !$financementDE['error'])
					$this->ajoutDesDispositifsTrefle($dispositifs,$financementDE,$annonce,$ar);
				else
				{
					$dispositifs['erreur']=true;
					@_LOG('api_error.log',print_r(array('Erreur retour API financement',$dataFinancementDE),true));
				}
				$dispositifs['trefleparams']=$dataFinancementDE;
			}

			if($individu['salarie'])
			{
				$dispositifs['type']='salarie';
				$dataFinancementSalarie=$this->getDataFinancementSalarie($quark,$individu,$annonce,$ar);
				@_LOG('api-salarie.log',print_r($dataFinancementSalarie+$_SERVER,true));
				$financementSalarie=$this->getRulesTrefle($dataFinancementSalarie);
				if($financementDE!==false && !$financementDE['error'])
					$this->ajoutDesDispositifsTrefle($dispositifs,$financementSalarie,$annonce,$ar);
				else
				{
					$dispositifs['erreur']=true;
					@_LOG('api_error.log',print_r(array('Erreur retour API financement',$dataFinancementSalarie),true));
				}
				$dispositifs['trefleparams']=$dataFinancementSalarie;
			}

			// cas pour afficher les financements depuis le lien de l'explorer trèfle
			if($individu['beneficiaire'] && $individu['cmd']='engage')
			{
				$financement=$this->getRulesTrefle($individu);
				if($financement!==false && !$financement['error'])
					$this->ajoutDesDispositifsTrefle($dispositifs,$financement,$annonce,$ar);
				else
				{
					$dispositifs['erreur']=true;
					@_LOG('api_error.log',print_r(array('Erreur retour API financement',$individu),true));
				}
				$dispositifs['trefleparams']=$individu;
			}

			return $dispositifs;
		}

		/**
		 * checkApiData vérifications des données entrantes de l'API
		 *
		 * @param object $quark
		 * @param array $data
		 * @access public
		 * @return array
		 */
		public function checkApiData($quark,&$data)
		{
			$db=$quark->getStore('read');
			$adSearch=new AdSearch($db);

			$erreurs=array();

			$rawData=$data;
			$formation=$data['formationVisee'];
			$individu=$data['individu'];
			$typeAllocation=isset($individu['typeAllocation'])?$individu['typeAllocation']:false;
			$contratAide=isset($individu['contratAide'])?$individu['contratAide']:false;
			if(is_array($contratAide)) unset($individu['contratAide']); // pour compatibilité 0.6.0
			$cddTermine=isset($individu['cddTermine'])?$individu['cddTermine']:false;
			$interimExperience=isset($individu['interimExperience'])?$individu['interimExperience']:false;

			$champsObligatoiresIndividu=array('type','dateNaissance','departementHabitation','niveauEtude');
			//$champsObligatoiresTypeAllocation=array('type');

			$contratType=array('non','are','ass','rsa','ata','asr','atp','asp','aex');
			$valeursAttenduesIndividu=array('type'=>array('de','salarie'));
			$valeursAttenduesTypeAllocation=array('contratType'=>$contratType);
			$valeursAttenduesContratAide=array('enCours','termine');
			$valeursAttenduesApprentissage=array('contratType'=>$contratType);

			function checkChampsObligatoires($checkData,$champsObligatoires)
			{
				assert('is_array($champsObligatoires)');
				$erreurs=array();
				foreach($champsObligatoires as $k=>$cleObligatoire)
				{
					if(!isset($checkData[$cleObligatoire]))
					{
						$erreurs[]=getErreurChampRequis($cleObligatoire);
					}
				}
				return $erreurs;
			}
			function getErreurChampRequis($nomDuChamp)
			{
				return array('code'=>200,'message'=>'requis : champ '.$nomDuChamp);
			}
			function getErreurValeurInattendue($nomDuChamp="")
			{
				if($nomDuChamp)
					return array('code'=>100,'message'=>'valeur inattendue pour le champ '.$nomDuChamp);
				else
					return array('code'=>100,'message'=>'valeur inattendue');
			}
			function getErreurValeurDateMalFormee($nomDuChamp)
			{
				return array('code'=>110,'message'=>'date mal formée pour le champ '.$nomDuChamp);
			}
			function getErreurValeurDateInferieurOuEgal($nomDuChamp,$dateRequis)
			{
				return array('code'=>111,'message'=>'la date doit être inférieure ou égale au '.$dateRequis->format('d/m/Y'). ' pour le champ '.$nomDuChamp);
			}
			function getErreurValeurDateInferieur($nomDuChamp,$dateRequis)
			{
				return array('code'=>112,'message'=>'la date doit être inférieure au '.$dateRequis->format('d/m/Y'). ' pour le champ '.$nomDuChamp);
			}
			function getErreurValeurDateSuperieur($nomDuChamp,$dateRequis)
			{
				return array('code'=>112,'message'=>'la date doit être supérieure au '.$dateRequis->format('d/m/Y'). ' pour le champ '.$nomDuChamp);
			}
			function getErreurValeurBooleen($nomDuChamp)
			{
				return array('code'=>120,'message'=>'erreur : valeur non booleene pour le champ '.$nomDuChamp);
			}
			function getErreurValeurNumerique($nomDuChamp)
			{
				return array('code'=>130,'message'=>'erreur : valeur non numérique pour le champ '.$nomDuChamp);
			}
			function getErreurValeurString($nomDuChamp)
			{
				return array('code'=>140,'message'=>'erreur : valeur n\'est pas un \'string\' pour le champ '.$nomDuChamp);
			}
			/**
			 * checkErreur400 on check si les clés/valeurs obligatoires existent et si elles sont conformes
			 *
			 * @param array $checkData
			 * @param array $dataType
			 * @param array $champsObligatoires
			 * @access public
			 * @return array
			 */
			function checkErreur400($checkData,$valeursAttendues=array(),$champsObligatoires=array())
			{
				$erreurs=array();
				$dataToCheck=array('individu','typeAllocation','contratAide','interimExperience','apprentissateSituation','cddTermine');
				if(!in_array(key($checkData),$dataToCheck) || !is_array(current($checkData)))
					$erreurs[]=getErreurValeurInattendue(key($checkData));
				elseif(empty($erreurs=checkChampsObligatoires(current($checkData),$champsObligatoires)))
				{
					foreach(current($checkData) as $clef=>$val)
						switch($clef)
						{
							case 'type':
							case 'contratType':
								if(!is_string($val))
								{
									$erreurs[]=getErreurValeurString($clef);
									break;
								}
								if(isset($valeursAttendues['type']))
								{
									if(!in_array(strtolower($val),$valeursAttendues['type']))
										$erreurs[]=getErreurValeurInattendue($clef);

								}
								if(isset($valeursAttendues['contratType']))
								{
									if(!in_array(strtolower($val),$valeursAttendues['contratType']))
										$erreurs[]=getErreurValeurInattendue($clef);
								}
							break;
							case 'dateNaissance':
							case 'dateFinIndemnisation':
							case 'cddDateDeFin':
							case 'derniereMissionDateFin':
								if(!is_string($val))
								{
									$erreurs[]=getErreurValeurString($clef);
									break;
								}

								$dateValeur=DateTime::createFromFormat('Y-m-d',$val);
								$dateNow=new DateTime();
								$dateNaissanceMinimuLegalTravail=new DateTime('-15 year'); //année naissance age légal

								if($val!='' && $dateValeur===False)
									$erreurs[]=getErreurValeurDateMalFormee($clef);
								elseif($clef=='dateNaissance' && $dateValeur>$dateNaissanceMinimuLegalTravail) //pour une date minimum pour la date de naissance
									$erreurs[]=getErreurValeurDateInferieurOuEgal($clef,$dateNaissanceMinimuLegalTravail);
								elseif($clef=='cddDateDeFin' && ($dateValeur>$dateNow))
									$erreurs[]=getErreurValeurDateInferieur($clef,$dateNow);
								elseif(substr($val,0,4)<1900)
									$erreurs[]=getErreurValeurDateSuperieur($clef,DateTime::createFromFormat('Y-m-d','1900-01-01'));
								elseif(substr($val,0,4)>date('Y')+10)
									$erreurs[]=getErreurValeurDateInferieur($clef,new DateTime('+10 year'));
							break;
							case 'heuresCPF':
							case 'montantCPF':
								if(!is_numeric($val))
								{
									$erreurs[]=getErreurValeurNumerique($clef);
									break;
								}
								if($val<0) //TODO modifier la doc en conséquence
									$erreurs[]=getErreurValeurInattendue($clef);
							break;
							case 'departementHabitation':
								if(!is_string($val))
								{
									$erreurs[]=getErreurValeurString($clef);
									break;
								}
								if(0===preg_match('/^((0[1-9])|([1-8][0-9])|(9[0-5])|(2A)|(2B)|(97[1-4])|(976))$/i',$val))
									$erreurs[]=getErreurValeurInattendue($clef);
							break;
							case 'montantMensuelAllocation':
							case 'salaireMoyen4DerniersMois':
							case 'salaireBrutMensuel4DerniersMois':
							case 'cumulDureeInscriptionSur12Mois':
								if(!is_numeric($val))
									$erreurs[]=getErreurValeurNumerique($clef);
								if($val<0 || ($clef=='cumulDureeInscriptionSur12Mois' && $val>12))
									$erreurs[]=getErreurValeurInattendue($clef);
							break;
							case 'chomageDePlusDeSixMois':
							case 'contratAide': //compatibilité API 0.6.0
							case 'travailleurHandicape':
							case 'travNonSalariePrisEnCharge':
							case 'parentIsole':
							case 'femmeSeule':
							case 'mere3enfants':
							case 'projetCreationEntreprise':
							case 'formationEntammeeEnVAE':
							case 'depuisMoinsUnAn':
							case '12MoisSur5Ans':
							case '24MoisSur5Ans':
							case '4MoisSur1An':
							case '1600HeuresDepuis6mois':
							case '600HeuresDepuis6mois':
							case 'ruptureContratApprentissage':
							case 'termine':
							case 'enCours':
								if(!is_bool($val) && $val && !in_array(strtolower($val),array('true','false')))
									$erreurs[]=getErreurValeurBooleen($clef);
							break;
							case 'codeFormation':
							case 'codeAction':
							case 'codeSession':
								if(!is_string($val))
									$erreurs[]=getErreurValeurString($clef);
							break;
						}
				}
				return $erreurs;
			} // End function checkErreur400


			if(empty($data))
			{
				//check si on a bien des données
				$erreurs[400][]=array('code'=>10,'message'=>'Aucune donnée ou données mal formatées');
			}
			elseif(!$formation['codeFormation'] || !is_string($formation['codeFormation']) || $data['api-error']==400) # API détail et API trèfle ne renvoi pas si la formaiton est trouvée ou non
			{
				if(!is_string($formation['codeFormation']))
					$erreurs[404][]=getErreurValeurString('codeFormation');
				// check si on a bien un formation
				$erreurs[404][]=array('code'=>800,'message'=>'formation non trouvée');
			}
			else
			{
				// check tous les champs suivi la spec swagger
				$erreursTypeAllocation=array();
				$erreursContratAide=array();
				$erreursCDD=array();
				$erreursInterim=array();
				$erreursApprentissage=array();

				//check data individu
				$erreursIndividu=checkErreur400(array('individu'=>$individu),$valeursAttenduesIndividu,$champsObligatoiresIndividu);
				//check data typeAllocation
				if($typeAllocation)
					$erreursTypeAllocation=checkErreur400(array('typeAllocation'=>$typeAllocation),$valeursAttenduesTypeAllocation);
				else
					$data['individu']['typeAllocation']['type']='non'; // doit avoir cette valeur par défaut si rien est envoyé en amont 'non indemnisé'

				// compatibilite 0.6.0
				if(is_array($contratAide)) $erreursContratAide=checkErreur400(array('contratAide'=>$contratAide));
				//if($contratAide) $erreursContratAide=checkErreur400(array('contratAide'=>$contratAide));

				//check data CDD
				if($cddTermine) $erreursCDD=checkErreur400(array('cddTermine'=>$cddTermine));
				//check data interim
				if($interimExperience) $erreursInterim=checkErreur400(array('interimExperience'=>$interimExperience));
				//check data apprentissage
				if($apprentissageSituation) $erreursApprentissage=checkErreur400(array('apprentissateSituation'=>$apprentissageSituation),$valeursAttenduesApprentissage);

				$erreurs400=array_merge($erreursIndividu,$erreursTypeAllocation,$erreursContratAide,$erreursCDD,$erreursInterim,$erreursApprentissage);
				if(!empty($erreurs400)) $erreurs[400]=$erreurs400;
			}
			return $erreurs;
		} // End function checkApiData


		/* Appel des règles ***************************************************************************************************/
		protected function computeRules($quark,$individu,$ad,$ar)
		{
			$individu=new DefaultParams($individu);

			$db=$quark->getStore('read');
			$ref=new Reference($db);

			//$orgaContent=$ad['orgacontent'];
			//$content=$ad['content'];

			$age=calcAge($individu->get('birthdate'));
			$locationPath=$domicilePath=$individu->get('domicilepath');
			$training_enfrancemetropolitaine=!isInRegionDomTom($training_locationpath)?true:false;

			$validation=$ar['validation:0']; //$content->get('validation','0')->inner(); // Récupère pour la formation le type de validation
			$codeModalitesPedagogiques=$ar['code-modalite-pedagogique,array()']->toArray(); //explode(' ',$content->get('codemod','')->inner());

			$training_duration=(int)$training_nbheurestotales=(int)$ar['sessions[0]/nombre-heures-total:0']; //(int)$content->get('duration','0')->inner();
			$training_nbheuresentreprise=(int)$ar['sessions[0]/nombre-heures-entreprise:0']; //(int)$content->get('nbheuresent','0')->inner();
			$training_nbheurescentre=(int)$ar['sessions[0]/nombre-heures-centre:0']; //(int)$content->get('nbheurescen','0')->inner();
			if(!$training_nbheurescentre) $training_nbheurescentre=$training_duration; //Si pas de nb d'heures en centre renseigné, la règle est de prendre la duree totale

			$training_rncp=$ar['code-rncp:0']; //$training_certifiante=$content->get('rncp','0')->inner();

			$training_diplome=($validation>=1 && $validation<=6)?true:false;
			$training_cpf=$ar['eligibilite-cpf']; //$content->get('cpf','0')->inner(); //utiliser $ar
			$training_niveausortie=$ar['nveau-sortie:0']; //$content->get('niveausortie','0')->inner();
			$training_datebegin=$ar['sessions[0]/debut']; //$ad['session'][0]['beganat']?date('Y-m-d',$ad['session'][0]['beganat']):null;//($content->get('begindate','')->inner());
			$training_dateend=$ar['sessions[0]/fin']; //$ad['session'][0]['endedat']?date('Y-m-d',$ad['session'][0]['endedat']):null;//($content->get('enddate','')->inner());
			$training_dureeenmois=!is_null($training_datebegin) && !is_null($training_dateend)?Tools::calcDiffDate($training_datebegin,$training_dateend):null;
			/* Tout ce qui est < au mois doit etre approximé à 1 mois */
			if(!$training_dureeenmois) $training_dureeenmois=1;

			$training_professionnalisante=($validation==0||$training_niveausortie==0||$training_niveausortie==1)?true:false;
			/* Pour a distance, 2 écoles donc on fait un ou logique */
			$training_adistance=(int)$ar['modalites-enseignement:0']==FORMATION_ADISTANCE?true:false; //$content->get('modens',0)->inner()==FORMATION_ADISTANCE?true:false;
			$training_adistance|=in_array('96129',$codeModalitesPedagogiques) || in_array('96130',$codeModalitesPedagogiques) || in_array('96131',$codeModalitesPedagogiques) || in_array('96133',$codeModalitesPedagogiques);

			$training_infpubvise=$ar['info-public-vise']; //$content->get('infpubvis','')->inner();
			$training_conventionne=in_array('CONVENTIONNE',$ar['caracteristiques,array()']->toArray()); //AdSearch::isFlag($ad['flags'],'CONVENTIONNE');

			$codeModalitesPedagogiques=$ar['code-modalite-pedagogique,array()']->toArray();
			$training_coursdusoir=in_array('96411',$codeModalitesPedagogiques); //$content->get('coursdusoir',false)->inner();
			$training_coursweekend=in_array('96412',$codeModalitesPedagogiques); //$content->get('coursweekend',false)->inner();
			$training_alternance=in_array('96142',$codeModalitesPedagogiques); //$content->get('coursalternance',false)->inner();

			$training_preparationconcours=in_multiarray(array('15073','15093','15094'),$codeModalitesPedagogiques); //in_array('15073',$codeModalitesPedagogiques) || in_array('15093',$codeModalitesPedagogiques) || in_array('15094',$codeModalitesPedagogiques);
			$training_contratapprentissage=in_array('CONTRATAPPRENTISSAGE',$ar['caracteristiques,array()']->toArray()); //AdSearch::isFlag($ad['flags'],'CONTRATAPPRENTISSAGE');
			$training_contratprofessionalisation=in_array('CONTRATPROFESSIONALISATION',$ar['caracteristiques,array()']->toArray()); //AdSearch::isFlag($ad['flags'],'CONTRATPROFESSIONALISATION');

			//Compliqué car on transforme le nouveau format en l'ancien format pour la fonction hasCodeFinanceur()
			$training_codefinanceur=implode(' ',array_map(function($e) {return sprintf('%d[%d]',$e['code'],$e['places']);},$ar->get('sessions[0]/financeurs',$ar['financeurs,array()'])->toArray())); //$ad['codefinanceur'];//$content->get('codefinanceur',0)->inner(); //"session->organisme-financeur->code-financeur"
			$training_formacode=$ar['codes-formacode[0]:0']; //$ad['codes-formacode'][0]; //Formacode principal

			$training_racineformacode=$ar['domaines-formacode[0]:0'];
			$training_romesvises=$ar['codes-rome,array()']->toArray(); //explode(' ',$content->get('romes',''));
			$training_romesrff=$ar['codes-rome,array()']->toArray(); //explode(' ',$content->get('romesrff','')); //Utiliser $ar

			$training_intensitehebdomadaire=$ar['duree-hebdomadaire,false']; //$content->get('dureehebdo',false)->inner();
			$training_dureehebdomadaire=$training_intensitehebdomadaire; //Alias de $training_intensitehebdomadaire
			$training_tempspartiel=$training_dureehebdomadaire && $training_dureehebdomadaire<32?true:false;

			$training_codecertifinfo=$ar['code-certifinfo,false']; //$content->get('certifinfo',false)->inner();
			$training_certifiante=$training_codecertifinfo?true:false;

			$training_concours=false;
			$training_locationpath=$ar['sessions[0]/localisation/formation/path']; //$ad['locationpath'];

			$training_siret=$ar['organisme/siret']; //$orgaContent->get('siret','')->inner();
			$training_codensf=''; //Bouchonné. Récupérer la balise "code-NSF"

			/* L'alimentation de $adParams me permet de creer des tests de validation quasi fonctionnels */
			$adParams=array();
			//print_r(get_defined_vars());
			foreach(get_defined_vars() as $k=>$val)
				if(preg_match('#^training_.*$#',$k))
					$adParams[$k]=$val;

			$allocation_type=$individu->get('allocation_type','');
			$allocation_cost=$individu->get('allocation_cost','');
			$allocation_costtype=$individu->get('allocation_costtype','mois');
			if($allocation_dateend=$individu->get('allocation_dateend',''))
				$allocation_dateend=convDate($allocation_dateend);

			$situation_inscrit=$individu->get('situation_inscrit');
			$situation_salarie=$individu->get('situation_salarie');
			$situation_th=$individu->get('situation_th',false);

			$situation_cdd=$individu->get('situation_cdd');
			$situation_cdd12moisdepuisle=$situation_cdd && $individu->get('situation_cdd12moisdepuisle');
			$situation_cdd5years=$situation_cdd && $individu->get('situation_cdd5years');

			/* Attention, 2 cas zone de formulaire cdd */
			$situation_cdd12months=$situation_cdd && $individu->get($situation_cdd12moisdepuisle?'situation_cdd12months2':'situation_cdd12months');
			$situation_cdddatedefin=preg_replace('#^(\d+)/(\d+)/(\d+)$#','$3-$2-$1',$individu->get($situation_cdd12moisdepuisle?'situation_cdddatedefin2':'situation_cdddatedefin'));
			$situation_salairebrutecdd=$situation_cdd?$individu->get($situation_cdd12moisdepuisle?'situation_salairebrutecdd2':'situation_salairebrutecdd',0):0;

			$situation_interim=$individu->get('situation_interim');
			$situation_interim1600h=$individu->get('situation_interim1600h');
			$situation_interim600h=$individu->get('situation_interim600h');
			$situation_salairebruteinterim=$individu->get('situation_salairebruteinterim');
			$situation_lic=($individu->get('situation_lic') || $allocation_type=='asp')?true:false;
			$situation_liccsp=($situation_lic && ($individu->get('situation_liccsp') || $allocation_type=='asp'))?true:false;
			$situation_contratapprentissage=$individu->get('situation_contratapprentissage');
			$situation_contratapprentissagetype=$individu->get('situation_contratapprentissagetype');
			$situation_rupturecontratapprentissage=$individu->get('situation_rupturecontratapprentissage');
			$situation_projetcreationentreprise=$individu->get('situation_projetcreationentreprise');
			$situation_personnecontrataide=$individu->get('situation_contrataide')=="on";
			$situation_personnesortantcontrataide=($situation_personnecontrataide)?$individu->get('situation_personneencourscontrataide',"non")=="non":false;
			$situation_personneencourscontrataide=($situation_personnecontrataide)?$individu->get('situation_personneencourscontrataide',"non")=="oui":false;
			$niveauscolaire=$individu->get('niveauscolaire');
			$situation_travailleurnonsal12dont6dans3ans=$individu->get('situation_travailleurnonsal12dont6dans3ans',false); /* ajouter checkbox 'vs etes un tTravailleur non salariés et vous avez travaillé durant 12 mois, dont 6 mois consécutifs, dans les 3 ans précédant l'entrée en stage" */
			$situation_parentisole=$individu->get('situation_parentisole',false); //ajouter checkbox 'vs etes 'parent isolé' ( + def°)
			$situation_mere3enfants=$individu->get('situation_mere3enfants',false); //ajouter checkbox 'vs etes Mère de famille ayant eu au moins 3 enfants'
			$situation_divorceeveuve=$individu->get('situation_divorceeveuve',false); //ajouter checkbox 'vs etes Femmes divorcées, veuves, séparées judiciairement depuis moins de 3 ans'
			$situation_creditheurescpf=$individu->get('situation_creditheurescpf',false); //Ajouter champ de saisie crédit d'heures CPF
			$situation_creditheurescpfconnu=$individu->get('situation_cpfconnu','cpfinconnu')=='cpfconnu'?true:false;
			$situation_creditheurescpfinconnu=!$situation_creditheurescpfconnu;
			$situation_inscritcumuldureeinscriptionsur12mois=($situation_inscrit)?$individu->get('situation_inscritcumuldureeinscriptionsur12mois',$individu->get('situation_6moissur12')?6:0):0; //textfield nombre de mois
			$situation_6moissur12=($situation_inscritcumuldureeinscriptionsur12mois>=6);
			//TODO : voir si une durée inferieur à 6 mois pose pb pour setter la valeur à true de situation_inscritcumuldureeinscriptionsur12mois
			$situation_vaepartiellemoins5ans=$individu->get('situation_vaepartiellemoins5ans',false); //checkbox "vous avez obtenu pour cette formation, il y a moins de 5 ans, une certification partielle par un jury VAE"
			$situation_nondemissionaire=true; //Ajouter champ de case à cocher: Salariés démissionnaires pour les cas de démissions "légitimes"
			$situation_nonsuivienmissionlocale=true; //Ajouter case à cocher: Vous etes suivi en mission locale

			$domicileRegionPath=Reference::subPath($domicilePath,3);
			$domicileDepartementPath=Reference::subPath($domicilePath,4);
			$trainingRegionPath=Reference::subPath($training_locationpath,3);
			$trainingDepartementPath=Reference::subPath($training_locationpath,4);

			$caseVousEtes=($situation_th || $situation_6moissur12 || $situation_divorceeveuve || $situation_mere3enfants || $situation_parentisole || $situation_travailleurnonsal12dont6dans3ans)?true:false;
			/* On met en boite toutes les variables récupérées du formulaire pour les passer au moteur de calcul des financements */
			$var=get_defined_vars();
			unset($var['quark'],$var['content'],$var['orgaContent'],$var['adParams'],$var['formParams']);
			//_LOG('api.log',print_r($var,true));
			//unset($var['ref'],$var['ad'],$var['db'],$var['quark'],$var['content'],$var['orgacontent'],$var['orgaContent'],$var['adParams'],$var['formParams']);
			$display=getList();

			$droits=array();
			/* Etape 1 - Application des règles */
				reglesNationalesNew($var,$droits,$display);
				if(isInRegionPaysDeLaLoire($training_locationpath) || isInRegionPaysDeLaLoire($domicilePath))
					reglesPaysDeLaLoire($quark,$var,$droits,$display);

				if(isInRegionCentreValDeLoire($training_locationpath) || isInRegionCentreValDeLoire($domicilePath))
					reglesCentre($var,$droits,$display);

				if(isInRegionCorse($training_locationpath) || isInRegionCorse($domicilePath))
					reglesCorse($var,$droits,$display);

				if(isInRegionPACA($training_locationpath) || isInRegionPACA($domicilePath))
					reglesPaca($var,$droits,$display);

				if(isInRegionBourgogneFrancheComte($training_locationpath) || isInRegionBourgogneFrancheComte($domicilePath))
					reglesBourgogneFrancheComte($quark,$var,$droits,$display);

				if(isInRegionNormandie($training_locationpath) || isInRegionNormandie($domicilePath))
					reglesNormandie($quark,$var,$droits,$display);

				if(isInRegionIDF($training_locationpath) || isInRegionIDF($domicilePath))
					reglesIleDeFrance($quark,$var,$droits,$display);

				if(isInRegionReunionMayotte($training_locationpath) || isInRegionReunionMayotte($domicilePath))
					reglesReunionMayotte($quark,$var,$droits,$display);

				if(isInRegionNouvelleAquitaine($training_locationpath) || isInRegionNouvelleAquitaine($domicilePath))
					reglesNouvelleAquitaine($quark,$var,$droits,$display);

				if(isInRegionOccitanie($training_locationpath) || isInRegionOccitanie($domicilePath))
					reglesOccitanie($quark,$var,$droits,$display);

				if(isInRegionBretagne($training_locationpath) || isInRegionBretagne($domicilePath))
					reglesBretagne($quark, $var, $droits, $display);

				if(isInGuadeloupe($training_locationpath) || isInGuadeloupe($domicilePath))
					reglesGuadeloupe($quark, $var, $droits, $display);

				if(isInRegionHautsDeFrance($training_locationpath) || isInRegionHautsDeFrance($domicilePath))
					reglesHautsDeFrance($quark,$var,$droits,$display);

				if(isInRegionAuvergneRhoneAlpes($training_locationpath) || isInRegionAuvergneRhoneAlpes($domicilePath))
					reglesAuvergneRhoneAlpes($quark,$var,$droits,$display);

				if(isInRegionGrandEst($training_locationpath) || isInRegionGrandEst($domicilePath))
					reglesGrandEst($quark,$var,$droits,$display);

				// régles génériques - prioritaires sur toutes les autre règles
				// NOTE: temporarie pour corriger des pbs sur toutes les règles en un seul endroit
				// a voir si on garde ou pas
				// A terme il peut etre bon d'en faire une fonction de vérif pour remonter les regles qui ne respectent pas les régles génériques sans que cela modifie la liste des dispo
				reglesGeneriques($quark,$var,$droits,$display);

			/* Etape 2 - Respect des priorités d'affichage et types des financements */
				$droitsTrie=array();
				foreach($display as $fin=>$v)
					if(array_key_exists($fin,$droits))
					{
						$droitsTrie[$fin]=$droits[$fin];
					}
				$droits=$droitsTrie;

			/* Etape 3 - Déduction des financements auquels il n'a pas le droit */
				$pasDroits=array();

			/* Etape 4 - Application de la rémunération selon le type de financement */
				foreach($droits as $key=>$v)
				{
					if(array_key_exists($key,$display)) $droits[$key]=$display[$key];
					if($v['remu']) $droits[$key]['indemnisation']=$v['remu'];
					if($v['type']) $droits[$key]['remu-type']=$v['type'];
				}

			/* Quelques données de contact */
			$infoContact=$this->getInfoContact($ad,$ar);

			/* ajout d'une clé offre-emploi */
			foreach($droits as $fin=>$droit)
			{
				if(!is_array($droit['step'])) $droits[$fin]['step']=$droit['step']=array($droit['step']);

				// suppression de la balise OE et alimentation d'une clé 'offre-emploi' pour pouvoir ajouté les offres dans la function ajoutDesOffresEmplois
				foreach($droit['step'] as $k=>$step)
				{
					$contrat='';
					$step=preg_replace_callback('#\[OE(.*?)\]#mui',function($m) use(&$contrat)
					{
						$params=array();
						if($p=trim($m[1]))
							if(preg_match_all('#([a-z]+)=\"(.*?)\"#',$p,$m))
								foreach($m[1] as $k=>$v) $params[$v]=$m[2][$k];
						$contrat=array_key_exists('cont',$params)?$params['cont']:'';
						return '';
					},$step);
					$droits[$fin]['step'][$k]=$step;
					//if($contrat && !empty($ad['romecode'])) $droits[$fin]['offre-emploi']=$contrat;
					if($contrat && !empty($ar['codes-rome'])) $droits[$fin]['offre-emploi']=$contrat;
				}
			}

			$result=array('droits'=>$droits,'pasdroits'=>$pasDroits,'infocontact'=>$infoContact);
			//print_r($result);

			//$fileContent = Array(
			//	'input'=>$input,
			//	'output'=>$output,
			//);
			//file_put_contents('temp',json_encode($fileContent,true));
			return $result;
		}

		protected function getInfoContact($ad,$ar)
		{
			//$orgaContent=$ad['orgacontent'];
			//$content=$ad['content'];
			$infoContact=array();
			//if($tel=(string)$orgaContent->get('tel',$orgaContent->get('mobile'))) $infoContact['fa-phone']=$tel;
			//if($email=(string)$orgaContent->get('email')) $infoContact["fa-envelope"]=sprintf('<a href="mailto:%s">%s</a>',$email,$email);
			//if($address=(array)$content->select('[display=address]')) $infoContact["fa-map-marker"]=implode(' - ',$address);
			if($tel=$ar['organisme/contact/tel:'.$ar['organisme/contact/mobile']]) $infoContact['fa-phone']=$tel;
			if($email=$ar['organisme/contact/email']) $infoContact["fa-envelope"]=sprintf('<a href="mailto:%s">%s</a>',$email,$email);
			if($address=($ar['organisme/localisation/adresse'].' - '.$ar['organisme/localisation/ville'])) $infoContact["fa-map-marker"]=$address;
			return $infoContact;
		}

		protected function ajoutDesOffresEmplois($quark,$individu,$annonce,$ar,&$listeDispositifFinancement)
		{
			$db=$quark->getStore('read');
			$individu=new DefaultParams($individu);

			/* Appel aux API LBB/PE pour enrichir d'offres d'emploi */
			foreach($listeDispositifFinancement as $fin=>$droit)
			{
				$romeCode=$ar['codes-rome,array()']->toArray(); //$annonce['romecode'];
				$domicilePath=$individu->get('domicilepath');
				if(array_key_exists('offre-emploi',$droit) && $romeCode && $domicilePath)
					$listeDispositifFinancement[$fin]['offre-emploi']='<br/>'.getOffers($db,$romeCode,$droit['offre-emploi'],$domicilePath);
			}
		}


		/**
		 * getMappedIndividuData retourne les données de l'api au format anglais
		 *
		 * @param Quark $quark
		 * @param Array $individu
		 * @param string $fromType json ou form
		 * @access public
		 * @return Array
		 */
		public function getMappedIndividuData($quark,$individu)
		{
			$db=$quark->getStore('read');
			$ref=new Reference($db);
			if(!isset($individu['typeAllocation'])) $individu['typeAllocation']=array();
			if(!isset($individu['cddTermine'])) $individu['cddTermine']=array();
			if(!isset($individu['contratAide'])) $individu['contratAide']=array();
			if(!isset($individu['interimExperience'])) $individu['interimExperience']=array();
			if(!isset($individu['apprentissateSituation'])) $individu['apprentissateSituation']=array();
			$typeAllocationInit=array('type'=>null,'dateFinIndemnisation'=>null,'montantMensuelAllocation'=>null);
			$contratAideInit=array('enCours'=>null,'termine'=>null);
			$cddTermineInit=array('depuisMoinsUnAn'=>null,'24MoisSur5Ans'=>null,'12MoisSur5Ans'=>null,'4MoisSur1An'=>null,'cddTermine'=>null,'salaireMoyen4DerniersMois'=>null);
			$interimExperienceInit=array('1600HeuresDepuis6mois'=>null,'600HeuresDepuis6mois'=>null,'derniereMissionDateFin'=>null,'salaireBrutMensuel4DerniersMois'=>null);
			$apprentissageSituationInit=array('contratType'=>null,'ruptureContratApprentissage'=>null);
			$typeAllocation=$individu['typeAllocation']+$typeAllocationInit;
			if(!is_array($individu['contratAide'])) $individu['contratAide']=array('termine'=>true,'enCours'=>false);
			$contratAide=$individu['contratAide']+$contratAideInit;
			$cddTermine=isset($individu['cddTermine'])?$individu['cddTermine']+$cddTermineInit:$cddTermineInit;
			$interimExperience=isset($individu['interimExperience'])?$individu['interimExperience']+$interimExperienceInit:$interimExperienceInit;
			$apprentissageSituation=isset($individu['apprentissageSituation'])?$individu['apprentissageSituation']+$apprentissageSituationInit:$apprentissageSituationInit;

			$domicilePath='';
			if($zipCode=$ref->getByExtraData('LOCATION','dn',$individu['departementHabitation']))
				$domicilePath=array_keys($zipCode)[0];

			// conversion des dates
			$dateFormatInput='Y-m-d';
			$dateFormatOutput='d/m/Y';
			$individu['dateNaissance']=DateTime::createFromFormat($dateFormatInput,$individu['dateNaissance'])->format($dateFormatOutput);
			if(!empty($typeAllocation['dateFinIndemnisation']))
				$typeAllocation['dateFinIndemnisation']=DateTime::createFromFormat($dateFormatInput,$typeAllocation['dateFinIndemnisation'])->format($dateFormatOutput);
			if(!empty($cddTermine['cddDateDeFin']))
				$cddTermine['cddDateDeFin']=DateTime::createFromFormat($dateFormatInput,$cddTermine['cddDateDeFin'])->format($dateFormatOutput);
			if(!empty($interimExperience['derniereMissionDateFin']))
				$interimExperience['derniereMissionDateFin']=DateTime::createFromFormat($dateFormatInput,$interimExperience['derniereMissionDateFin'])->format($dateFormatOutput);

			/* on map les valeurs de l'API sur les champs du formulaire */
			$mapping=array(
				'situation_inscrit'=>(strtolower($individu['type'])=="de"),
				'situation_inscritdepuisaumoins6moissur12mois'=>filter_var($individu['chomageDePlusDeSixMois'],FILTER_VALIDATE_BOOLEAN),
				'situation_inscritcumuldureeinscriptionsur12mois'=>$individu['cumulDureeInscriptionSur12Mois'],
				'birthdate'=>$individu['dateNaissance'],
				'domicilepath'=>$domicilePath,
				'niveauscolaire'=>$individu['niveauEtude'],
				'situation_salarie'=>false, //TODO: à implémenter

				/* type Allocation */
				'allocation_type'=>strtolower($typeAllocation['type']),
				'allocation_dateend'=>$typeAllocation['dateFinIndemnisation'],
				'allocation_cost'=>$typeAllocation['montantMensuelAllocation'],

				/* cdd Termine //TODO: valider ces règles suivantes : */
				'situation_cdd'=>filter_var($cddTermine['depuisMoinsUnAn'],FILTER_VALIDATE_BOOLEAN),
				'situation_cdd5years'=>filter_var($cddTermine['24MoisSur5Ans'],FILTER_VALIDATE_BOOLEAN),
				'situation_cdd12months'=>filter_var($cddTermine['4MoisSur1An'],FILTER_VALIDATE_BOOLEAN),
				'situation_cdd12months2'=>filter_var($cddTermine['4MoisSur1An'],FILTER_VALIDATE_BOOLEAN),
				'situation_cdddatedefin'=>$cddTermine['cddDateDeFin'],
				'situation_salairebrutecdd'=>$cddTermine['salaireMoyen4DerniersMois'],

				'situation_cpfconnu'=>($individu['heuresCPF'] || $individu['montantCPF'])?'cpfconnu':'cpfinconnu',
				'situation_creditheurescpf'=>(!$individu['montantCPF'] && $individu['heuresCPF'])?($individu['heuresCPF']*15):$individu['montantCPF'],

				/* apprentissage */
				'situation_contratapprentissagetype'=>$apprentissageSituation['contratType'],
				'situation_rupturecontratapprentissage'=>filter_var($apprentissageSituation['ruptureContratApprentissage'],FILTER_VALIDATE_BOOLEAN),

				'situation_contratapprentissage'=>$individu['contratApprentissage'],
				'situation_contratapprentissagetype'=>$individu['situation_contratapprentissage'],

				/* interim */
				'situation_interim'=> (filter_var($interimExperience['1600HeuresDepuis6mois'],FILTER_VALIDATE_BOOLEAN) ||
				                       filter_var($interimExperience['600HeuresDepuis6mois'],FILTER_VALIDATE_BOOLEAN)),
				'situation_interim1600h'=>filter_var($interimExperience['1600HeuresDepuis6mois'],FILTER_VALIDATE_BOOLEAN),
				'situation_interim600h'=>filter_var($interimExperience['600HeuresAvecMemeEntreprise'],FILTER_VALIDATE_BOOLEAN),
				'situation_interimdateend'=>$interimExperience['derniereMissionDateFin'],
				'situation_salairebruteinterim'=>$interimExperience['salaireBrutMensuel4DerniersMois'],

				/* autre */
				'situation_contrataide'=>$contratAide?'on':'',
				'situation_personnesortantcontrataide'=>(filter_var($contratAide['termine'],FILTER_VALIDATE_BOOLEAN))?'oui':'non',
				'situation_personneencourscontrataide'=>(filter_var($contratAide['enCours'],FILTER_VALIDATE_BOOLEAN))?'oui':'non',
				/*'situation_6moissur12'=>filter_var($individu['chomageDePlusDeSixMois'],FILTER_VALIDATE_BOOLEAN),*/
				'situation_th'=>filter_var($individu['travailleurHandicape'],FILTER_VALIDATE_BOOLEAN),
				'situation_travailleurnonsal12dont6dans3ans'=>filter_var($individu['travNonSalariePrisEnCharge'],FILTER_VALIDATE_BOOLEAN),
				'situation_parentisole'=>filter_var($individu['parentIsole'],FILTER_VALIDATE_BOOLEAN),
				'situation_mere3enfants'=>filter_var($individu['mere3enfants'],FILTER_VALIDATE_BOOLEAN),
				'situation_divorceeveuve'=>filter_var($individu['femmeSeule'],FILTER_VALIDATE_BOOLEAN),
				'situation_projetcreationentreprise'=>filter_var($individu['projetCreationEntreprise'],FILTER_VALIDATE_BOOLEAN),
				'situation_vaepartiellemoins5ans'=>filter_var($individu['formationEntammeeEnVAE'],FILTER_VALIDATE_BOOLEAN)
			);

			return array_filter($mapping);
		} // End function getMappedIndividuData // End function getMappedIndividuData

		protected function getRemuStructure($financement)
		{
			/* Structure la partie rémunération (indemnisation) */
			$indem=preg_replace('#(<.*?>|&nbsp;)#',' ',$financement['indemnisation']);
			$indem=trim(preg_replace('# +#',' ',html_entity_decode($indem)));
			$remu=array();
			if(preg_match("#Vous percevrez jusqu'au (\d+/\d+/\d+) ([0-9., ]+) ?€ (net|brut) / mois Puis jusqu'au (\d+/\d+/\d+) ([0-9., ]+) ?€ (net|brut) / mois#sui",$indem,$m))
			{
				$remu=array(
					array(
						'montant'=>number_format(floatval(str_replace(' ','',$m[2])),2,'.',''),
						//'type'=>'?',
						'dateFin'=>preg_replace('#(\d+)/(\d+)/(\d+)#','$3-$2-$1',$m[1]),
						'nature'=>$m[3]
					)+($financement['remu-type']?array('type'=>$financement['remu-type']):array()),
					array(
						'montant'=>number_format(floatval(str_replace(' ','',$m[5])),2,'.',''),
						//'type'=>'?',
						'dateFin'=>preg_replace('#(\d+)/(\d+)/(\d+)#','$3-$2-$1',$m[4]),
						'nature'=>$m[6],
						'type'=>'rff' /* par défaut on met un type rému de fin de formation */
					)
				);
			}
			elseif(preg_match("#Vous percevrez jusqu'au (\d+/\d+/\d+) ([0-9., ]+) ?€ (net|brut) / mois#sui",$indem,$m))
			{
				$remu=array(
					array(
						'montant'=>number_format(floatval(str_replace(' ','',$m[2])),2,'.',''),
						//'type'=>'?',
						'dateFin'=>preg_replace('#(\d+)/(\d+)/(\d+)#','$3-$2-$1',$m[1]),
						'nature'=>$m[3]
					)+($financement['remu-type']?array('type'=>$financement['remu-type']):array()));
			}
			elseif(preg_match("#Vous percevrez ([0-9., ]+) ?€ (net|brut)#sui",$indem,$m))
			{
				$remu=array(
					array(
						'montant'=>number_format(floatval(str_replace(' ','',$m[1])),2,'.',''),
						//'type'=>'?',
						'nature'=>$m[2]
					)+($financement['remu-type']?array('type'=>$financement['remu-type']):array())
				);
			}
			return $remu;
		}

		/**
		 * getCostMontant
		 *
		 * @param array $financement
		 * @access public
		 * @return float
		 */
		protected function getCostMontant($financement)
		{
			$montant=null;
			$cost='';

			if(array_key_exists('cost',$financement))
				$cost=$financement['cost'];
			$costComp='';
			if(array_key_exists('cost-complement',$financement) && $financement['cost-complement'])
				$costComp=strip_tags($financement['cost-complement']);
			if(preg_match("#pour ([0-9,. ]+?) ?€#",htmlspecialchars_decode($cost.$costComp),$m))
				$montant=number_format(floatval(strtr($m[1],array(','=>'.',' '=>''))),2,'.','');
			return $montant;
		} // End function getMontantFinancement

		/**
		 * getCoutStructure retourne le cout structure en fonction des données spécifiques et nationales
		 *
		 * @param array $financement
		 * @param array $financementGenerique
		 * @access public
		 * @return array
		 */
		protected function getCoutStructure($financement,$financementGenerique)
		{
			$cout=array();

			/* plafond */
			if(array_key_exists('cost-plafond',$financement) && !is_null($financement['cost-plafond']))
				$cout['plafond']=$financement['cost-plafond'];

			/* montant */
			$montant=$this->getCostMontant($financement);
			if($montant) $cout['montant']=$montant;

			/* reste a charge en fonction de ce que donne la regle */
			if(array_key_exists('cost',$financement) && !is_null($financement['cost']))
			{
				$testCost=preg_replace('#(<.*?>|&nbsp;)#',' ',$financement['cost']);
				$testCost=trim(preg_replace('# +#',' ',html_entity_decode($testCost)));
				$totalement=preg_match('#totalement#sui',$testCost,$m)?true:false;
				$partiellement=preg_match('#partiel#sui',$testCost,$m)?true:false;
				if($totalement && !$partiellement)
					$cout['resteACharge']=false;
				elseif(array_key_exists('reste-a-charge',$financement))
					$cout['resteACharge']=$financement['reste-a-charge'];
			}

			// champs génériques (nationales)
			if(array_key_exists('reste-a-charge',$financementGenerique) && !array_key_exists('resteACharge',$cout))
				$cout['resteACharge']=$financementGenerique['reste-a-charge'];
			if(array_key_exists('financee-pe',$financementGenerique))
				$cout['financeePE']=$financementGenerique['financee-pe'];
			if(array_key_exists('financable-cpf',$financementGenerique))
				$cout['financableCpf']=$financementGenerique['financable-cpf'];
			if(array_key_exists('cumulable',$financementGenerique))
				$cout['cumulable']=$financementGenerique['cumulable'];

			return $cout;
		} // End function getCoutStructure

		/**
		 * getCostConsolide retourne le cout spécifique ou générique si pas de cout spécifique donné
		 *
		 * @param array $financement
		 * @param array $financementGenerique
		 * @access public
		 * @return string
		 */
		protected function getCostConsolide($financement,$financementGenerique)
		{
			$costConsolidee=null;
			// description spécifique
			if(array_key_exists('cost',$financement) && !empty($financement['cost']))
			{
				$costConsolidee=$financement['cost'];
				if(array_key_exists('cost-complement',$financement) && !empty($financement['cost-complement']))
					$costConsolidee=trim($costConsolidee).' '.$financement['cost-complement'];
			}
			// description générique (national)
			elseif(array_key_exists('cost',$financementGenerique) && !empty($financementGenerique['cost']))
			{
				$costConsolidee=$financementGenerique['cost'];
				if(array_key_exists('cost-complement',$financementGenerique) && !empty($financementGenerique['cost-complement']))
					$costConsolidee=trim($costConsolidee).' '.$financementGenerique['cost-complement'];
			}

			return Tools::htmlToTxt($costConsolidee);
		} // End function getCostConsolide

		/**
		 * getDataFinancementSalarie
		 *
		 * @param mixed $quark
		 * @param mixed $individu
		 * @param mixed $ad
		 * @access public
		 * @return void
		 */
		protected function getDataFinancementSalarie($quark,$individu,$ad,$ar)
		{
			$entreprise=array('naf'=>null,'idcc'=>null,'region'=>null);
			$beneficiaire=array('droit_prive'=>null,'solde_cpf'=>null,'remuneration'=>null,'age'=>null,'experience_professionnelle'=>null,'mois_travailles_en_cdd'=>null,'contrat'=>null,'anciennete_entreprise_actuelle'=>null);
			$formation=array('numero'=>null);

			$beneficiaire['droit_prive']=true;
			$beneficiaire['solde_cpf']=$this->_getSoldeCpf($individu);
			$beneficiaire['remuneration']=$individu['salaire'];
			$beneficiaire['age']=calcAge($individu['birthdate']);
			$beneficiaire['experience_professionnelle']=$individu['experienceannee']?($individu['experienceannee']*12):null; //en mois pour trèfle
			$beneficiaire['experience_professionnelle_5_dernieres_annees']=$individu['experience'];
			$beneficiaire['mois_travailles_en_cdd']=$individu['moistravailleencdd'];
			$beneficiaire['contrat']=$individu['contrat'];
			$beneficiaire['anciennete_entreprise_actuelle']=$individu['ancienneteentrepriseactuelle']?($individu['ancienneteentrepriseactuelle']*12):null; //en mois pour trèfle
			$beneficiaire['heures_entreprise']=$individu['heuresentreprise'];
			$beneficiaire['heures_travaillees']=$individu['heurestravaillees'];

			$entreprise['naf']=$individu['naf'];
			$entreprise['idcc']=$individu['idcc'];
			$entreprise['commune']=$individu['entrepriselocationinsee'];
			$beneficiaire['entreprise']=$entreprise;

			$formation['numero']=$ar['uid']; //$ad['idformintercarif'];

			return compact('formation','beneficiaire');
		} // End function getDataFinancementSalarie

		/**
		 * getDataFinancementDemandeurEmploi
		 *
		 * @param mixed $quark
		 * @param mixed $individu
		 * @param mixed $ad
		 * @access public
		 * @return void
		 */
		protected function getDataFinancementDE($quark,$individu,$ad,$ar)
		{
			$entreprise=array('naf'=>null,'idcc'=>null,'region'=>null);
			$beneficiaire=array('droit_prive'=>null,'solde_cpf'=>null,'remuneration'=>null,'age'=>null,'experience_professionnelle'=>null,'mois_travailles_en_cdd'=>null,'contrat'=>null,'anciennete_entreprise_actuelle'=>null,'heures_entreprise'=>null,'heures_travaillees'=>null,'commune'=>null);
			$formation=array('numero'=>null);

			$beneficiaire['inscrit_pe']=true;
			$beneficiaire['droit_prive']=false;
			$beneficiaire['solde_cpf']=$this->_getSoldeCpf($individu);
			$beneficiaire['remuneration']=$individu['salaire'];
			$beneficiaire['fin_allocation']=$individu['allocation_dateend'];
			$beneficiaire['age']=calcAge($individu['birthdate']);
			$beneficiaire['experience_professionnelle']=$individu['situation_cdd']?12:null;
			$beneficiaire['experience_professionnelle_5_dernieres_annees']=$individu['situation_cdd5years']?24:null;
			$beneficiaire['mois_travailles_en_cdd']=($individu['situation_cdd12months'] || $individu['situation_cdd12months2'])?4:null;
			$beneficiaire['contrat']=($individu['situation_cdd']?'cdd':($individu['situation_interim']?'interim':''));
			$beneficiaire['fin_contrat']=($individu['situation_cdddatedefin']?:$individu['situation_cdddatedefin2']);
			$beneficiaire['anciennete_entreprise_actuelle']=$individu['ancienneteentrepriseactuelle'];
			$beneficiaire['heures_entreprise']=$individu['heuresentreprise'];
			$beneficiaire['heures_travaillees']=$individu['heurestravaillees'];
			$beneficiaire['commune']=($this->_getCodeInseeByLocationPath($quark,$individu['domicilepath'],$individu['communeinsee']))?:$individu['beneficiaire_commune'];
			$beneficiaire+=$individu;
			//$entreprise['naf']=$individu['naf'];
			//$entreprise['idcc']=$individu['idcc'];
			//$entreprise['commune']=$individu['entrepriselocationinsee'];
			//$beneficiaire['entreprise']=$entreprise;

			$formation['numero']=$ar['uid'];

			return compact('formation','beneficiaire');
		} // End function getDataFinancementDE

		/**
		 * _getCodeInseeByLocationPath on essaie de recupérer le code insee si le paremetre par default est vide
		 *
		 * @param mixed $quark
		 * @param mixed $path
		 * @param mixed $default
		 * @access private
		 * @return string
		 */
		private function _getCodeInseeByLocationPath($quark,$path,$default=null)
		{
			$codeinsee=$default;
			// parfois le code insee est absent (cas d'un copier/coller d'url entre version beta et trefle)
			if(!$default && $path)
			{
				$db=$quark->getStore('read');
				$ref=new Reference($db);
				$refData=$ref->get('LOCATION',$path);
				$codeinsee=$ref::extraData('in',$refData[$path]['extradata']);
			}
			return $codeinsee;
		} // End function _getCodeInseeByLocationPath

		/**
		 * _getSoldeCpf
		 *
		 * @param mixed $individu
		 * @access private
		 * @return void
		 */
		private function _getSoldeCpf($individu)
		{
			$solde_cpf='';
			if($individu['situation_cpfconnu']=='cpfconnu')
				$solde_cpf=$individu['situation_creditheurescpf'];
			elseif($individu['situation_cpfconnu']=='cpfempty')
				$solde_cpf=0;
			return $solde_cpf;
		} // End function _getSoldeCpf

		/**
		 * getRulesTrefle
		 *
		 * @param mixed $dataFinancementTrefle
		 * @access public
		 * @return void
		 */
		public function getRulesTrefle($dataFinancement,$apiCall=false)
		{
			$t=microtime(true);
			$financements=array();
			if($apiCall)
			{
				if($dataFinancement['individu']['heuresCPF'] && !$dataFinancement['individu']['montantCPF'])
					$dataFinancement['individu']['montantCPF']=$dataFinancement['individu']['heuresCPF']*15;
			}
			$dataFinancementJson=json_encode($dataFinancement);
			//$dataFinancementSalarieJson='{"formation":{"numero":"04_A815482"},"beneficiaire":{"droit_prive":true,"solde_cpf":"12","remuneration":"1000","age":17,"experience_professionnelle":"12","mois_travailles_en_cdd":"12","contrat":"cdd","anciennete_entreprise_actuelle":"12","entreprise":{"naf":"","idcc":"2706","region":"01"}}}';
			$opts=array(
				"http"=>array(
					"method"=>"POST",
					"header"=>"Content-Type: application/x-www-form-urlencoded",
					"content"=>$dataFinancementJson
				)
			);
			if($context=stream_context_create($opts))
			{
				#TODO utiliser URL_API_TREFLE
				$apiUrl=URL_TREFLE.(TREFLE_API_VERSION?"/".TREFLE_API_VERSION:"");
				$url=$apiUrl."/financement?eligible=true";
				if($apiCall) $url=$apiUrl."/legacy";
				$json=@file_get_contents($url,false,$context);
				if(ENV_DEV) _LOG('time_apimoteur.log',(microtime(true)-$t)."\n".print_r($_SERVER,true)."\n");
				if($http_response_header)
				{
					if(preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$http_response_header[0],$match))
					{
						$retCode=$match[1];
						if(intval($retCode)==200)
						{
							if($apiCall)
							{
								$financements=$json;
							}
							else
							{
								$financements=json_decode($json,true);
								if(isset($financements['financements']))
									$financements=$financements['financements'];
							}
						}
						else
						{
							$financements=array('error'=>$retCode); //erreur de retour API
							if(ENV_DEV) _LOG('api_error.log',print_r(array($url,$http_response_header),true)."\n");
						}
					}
					else
						if(ENV_DEV) _LOG('api_error.log',print_r(array('No http code return detected',$url,$http_response_header),true)."\n");

				}
				else
				{
					if(ENV_DEV) _LOG('api_error.log',print_r(array('No http response',$url,$http_response_header),true)."\n");
					$financements=false; //pas d'appel
				}
			}
			if(ENV_DEV) _LOG('time_apimoteur.log',(microtime(true)-$t)."\n".print_r($_SERVER,true)."\n");
			return $financements;
		} // End function getRulesTrefle

		/**
		 * ajoutDesDispositifsTrefle
		 *
		 * @param mixed $dispositifs
		 * @param mixed $financementTrefle
		 * @access public
		 * @return void
		 */
		protected function ajoutDesDispositifsTrefle(&$dispositifs,$financementTrefle,$annonce,$ar)
		{
			$financementTrefleRemanies=array();
			$financementTrefleRemaniesItemInit=array(
					'pri'=>null,
					'title'=>null,
					'step'=>null,
					'cost'=>null,
					'cost-complement'=>null,
					'info'=>null,
					'descinfo'=>null,
					'cost-plafond'=>null,
					'montant-financement'=>null,
					'financee-pe'=>null,
					'financable-cpf'=>null,
					'cumulable'=>null,
					'reste-a-charge'=>null,
					'famille'=>null,
					'indemnisation'=>null,
					'remu-type'=>null
			);

			$genre=array();
			if(is_array($financementTrefle) && !empty($financementTrefle))
				foreach($financementTrefle as $order=>$itemFinancement)
				{
					if(is_array($itemFinancement) && $itemFinancement['eligible'])
					{
						$financementTrefleRemaniesItem=array('pri'=>$order);
						foreach($itemFinancement as $propKey=>$propVal)
						{
							$propVal=str_replace("\xe2\x8f\x8e","\n",$propVal);
							switch($propKey)
							{
							case 'tags':
								$genre[$propVal[0]][]=$propKey; // on peut avoir plusieurs même genre de dispositif
								$num=(count($genre[$propVal[0]])>1)?'-'.count($genre[$propVal[0]])-1:''; //TODO : n'est pas bon doit être refais
								$financementTrefleRemaniesKey=$propVal[0].$num;
								break;
							case 'description':
								$financementTrefleRemaniesItem['descinfo']=Tools::text2Html($propVal,false,array('urlize'=>true));
								break;
							case 'demarches':
								$financementTrefleRemaniesItem['step']=Tools::text2Html($propVal,false,array('urlize'=>true));
								break;
							case 'nom': # Trefle 0.6
							case 'intitule':
								$financementTrefleRemaniesItem['title']=$propVal;
								break;
							case 'prise_en_charge':
								if($propVal) //TODO : voir quels sont les champs qui doivent être obligatoirement renseignés
									$financementTrefleRemaniesItem['priseencharge']=$propVal.' €';
								break;
							case 'prise_en_charge_texte':
								if($propVal) //TODO : voir quels sont les champs qui doivent être obligatoirement renseignés
									$financementTrefleRemaniesItem['priseencharge']=$propVal;
								break;
							case 'rff':
								if($propVal) //TODO : voir quels sont les champs qui doivent être obligatoirement renseignés
									$financementTrefleRemaniesItem['rff']=$propVal.' €';
								break;
							case 'debut_rff':
								if($propVal) //TODO : voir quels sont les champs qui doivent être obligatoirement renseignés
									$financementTrefleRemaniesItem['debutrff']=$propVal;
								break;
							case 'fin_rff':
								if($propVal) //TODO : voir quels sont les champs qui doivent être obligatoirement renseignés
									$financementTrefleRemaniesItem['finrff']=$propVal;
								break;
							case 'fin_remuneration':
								if($propVal && !$itemFinancement['remuneration_annee_2']) //TODO : voir quels sont les champs qui doivent être obligatoirement renseignés
									$financementTrefleRemaniesItem['finremuneration']=$propVal;
								break;
							case 'plafond_prise_en_charge':
								if($propVal)
									$financementTrefleRemaniesItem['plafondpriseencharge']=$propVal.' €';
								break;
							case 'remuneration':
								if($propVal)
								{
									$financementTrefleRemaniesItem['indemnisation']='Vous percevrez '.$propVal.' € brut / mois';
									if($itemFinancement['remuneration_annee_2'])
										$financementTrefleRemaniesItem['indemnisation'].=' la première année';
								} else
									$financementTrefleRemaniesItem['indemnisation']='Vous ne percevez pas de rémunération pendant la formation';
								break;
							case 'remuneration_annee_2':
								if($propVal)
									$financementTrefleRemaniesItem['indemnisation'].='<br/> et '.$propVal.' € la deuxième année';
								break;
							case 'remuneration_annee_3':
								if($propVal)
									$financementTrefleRemaniesItem['indemnisation'].='<br/> et '.$propVal.' € la troisième année';
							case 'organisme':
								if(is_array($propVal))
									$financementTrefleRemaniesItem['organisme']=$propVal;
								break;
							}
						}
						$financementTrefleRemaniesItem=array_merge($financementTrefleRemaniesItemInit,$financementTrefleRemaniesItem);
						$financementTrefleRemanies[$financementTrefleRemaniesKey]=$financementTrefleRemaniesItem;
					}
				}

			if(is_array($dispositifs) && !empty($dispositifs))
			{
				if(!array_key_exists('infocontact',$dispositifs))
				{
					$infoContact=$this->getInfoContact($annonce,$ar);
					$dispositifs['infocontact']=$infoContact;
				}
				if(is_array($dispositifs['droits']))
					$dispositifs['droits']=array_merge($dispositifs['droits'],$financementTrefleRemanies);
			}
		} // End function ajoutDesDispositifsTrefle
	}
?>
