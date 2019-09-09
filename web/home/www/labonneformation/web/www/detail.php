<?php
	//Codes régions: http://www.insee.fr/fr/methodes/nomenclatures/cog/
	//Liste de résultats: https://candidat.pole-emploi.fr/candidat/rechercheoffres/resultats/A__REGION_42___P__________INDIFFERENT_________________F1603
	require_once(CLASS_PATH.'/tools.php');

	function displayField2($inner)
	{
		$line=trim($inner);//preg_replace('#[\xc2]#sui',' ',$inner));
		$line=trim(preg_replace_callback('#&?(eacute|[aei]grave|[aeuoi]circ);#sui',function($m)
			{
				return Tools::text2Html('&'.$m[1].';');
			},$line));
		$line=trim(preg_replace('#([cdjlmnst])\?([a-z])#sui',"$1'$2",$line));
		$line=trim(preg_replace_callback('#([^-+&A-Za-z0-9 \n\r_àäâéèëêôçïîüûù\[\].:;,\'=<>"\(\)\|/?!%*\#])#sui',
			function($m)
			{
				if(ord($m[1])==0xc2) return ' ';
				if(ord($m[1])==0xe2) return "'";
				return sprintf('#0x%02lx#',ord($m[1]));
			},$line));
		return Tools::text2Html(trim($line),false);
	}
	function displayField($lineParser,$convBullet=false)
	{
		return Tools::displayField($lineParser,$convBullet);
	}
	function displayRaw($lines)
	{
		foreach($lines as $line)
			echo str_replace('"','\x22',str_replace("\n",'\n',trim($line))).'\n';
	}

	$db=$this->getStore('read');
	$ref=new Reference($db);
	$adSearch=new adSearch($db);
	$anotea=new Anotea($db);

	//$session=$this->getSession();
	if($id=$this->get('id',''))
	{
		$success=false;
		if(($a=$adSearch->getByIdNew($id))!==false)
		{
			if(empty($a))
			{
				$this->controller('/404.php');
				return;
			}

			$ar=new ArrayExplorer($a);

			/* Empeche les modifications d'url */
			$canonical=$this->rw('/detail.php',array('ar'=>$ar),true);

			/* Idem */
			if(strcmp($this->getURI(),$canonical))
			{
				$this->controller('/404.php');
				return;
			};

			$romes=$ar['codes-rome,array()']->toArray();
			if(!empty($romes))
				if($romes=$ref->getByExtraData('ROMECODE','rm',$romes))
				{
					$romesVises=array();
					foreach($romes as $value)
					{
						$romesVises[]=array(
							'libelle'=>$value['label'],
							'rome'=>$value['data'],
							'path'=>$value['path']
						);
					}
					$ar['libelles-codes-rome-vises']=$romesVises;
				}

		/* Interaction startup avril) */
		$avril=false;
		if(!in_array(Reference::subPath($ar['sessions[0]/localisation/formation/path'],3),
			array(
				LOCATIONPATH_NORMANDIE,
				LOCATIONPATH_GRANDEST,
				LOCATIONPATH_DOMTOM
			)) && !in_array(Reference::subPath($ar['sessions[0]/localisation/formation/path'],4),
			array(
				LOCATIONPATH_VENDEE,
				LOCATIONPATH_NIEVRE
			)))
			if(count($libelleRomeVises=$ar['libelles-codes-rome-vises,array()'])>0)
				if(in_array('RNCP',$ar['caracteristiques,array()']->toArray()))
					if(in_array($ar['code-niveau-sortie:0'],array(4,5,6))) //De BEP à bac a bac
					{
						$targetRomesStructured=$libelleRomeVises[0];
						$rome=(string)$targetRomesStructured['rome'];
						$label=(string)$targetRomesStructured['libelle'];
						//$city=(string)$ar['sessions[0]/localisation/formation/ville:'];
						$avril=array(
							'rncp_id'=>$ar['code-rncp'],
							'rome_code'=>$rome,
							'romelabel'=>$label,
							'lat'=>$ar['sessions[0]/localisation/formation/latitude:0'],
							'lng'=>$ar['sessions[0]/localisation/formation/longitude:0'],
						);
					}

			$success=true;
		}
	}

	if($success)
		//concaténation des id de sessions pour widget anotea
		$sessions=implode("|",[$ar['uid'],$ar['sessions[0]/uida'],$ar['sessions[0]/uid']]);
		$formation=$ar['uid'];
		if($endDate==$ar['sessions[0]']['fin']) {
			$dates_session = "du ".Tools::date($ar['sessions[0]']['debut'])." au ".Tools::date($endDate);
		} else {
			$dates_session = "à partir du ".Tools::date($ar['sessions[0]']['debut']);
		}
		$messageDefault = _T("Bonjour, je souhaite recevoir des informations complémentaires sur la formation de «".$ar['intitule']."» pour la session ".$dates_session.".",true);
		$this->view('/detail_view.php',
			array(
				'ar'=>$ar,
				'orgaid'=>$ar['organisme/id'],
				'aDistance'=>in_array('ADISTANCE',$ar['caracteristiques,array()']->toArray()), //AdSearch::isFlag($ad['flags'],'ADISTANCE'),
				'backLink'=>$this->rewrite('/result.php',array(
					'criteria'=>array(
						//'code'=>$ad['romepath'][0],
						'rome'=>$ar['codes-rome[0]'],
						'locationpath'=>$ar['sessions[0]/localisation/formation/path']
					)
				)), /* Implanté dans un champ hidden et récupéré par JS pour profiter des sessionStorage */
				'criteria'=>$criteria,
				'canonical'=>$canonical,
				'domaine'=>$domaine,
				'nombreAvisMax'=>3,
				'page'=>'detail',
				'avril'=>$avril,
				'financement'=>$ar['catalogue']!='Udemy',
				'messageDefault'=>$messageDefault,
				'sujetMail'=>'A propos de votre formation de : '.$ar['intitule'],
				'sessions'=>$sessions,
				'formation'=>$formation,
				'noRobots'=>true
			));

	if(!$isSuccess)
		$this->view('/error_view.php');
?>
