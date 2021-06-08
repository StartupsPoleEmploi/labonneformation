<?php
	require_once(CLASS_PATH.'/tools.php');

	//echo calcColorSpread('ff0000','00ff00',50);
	function highlight($title,$search)
	{
		return Tools::text2Html($title,true,array('highlight'=>$search,'highlightstyle'=>array(),'highlightclass'=>array('highlight')));
	}

	//Rome Test: E1401
	$db=$this->getStore('read');
	$isSuccess=true;

	$orga=array();
	$avisDomaine=array();
	$domaineList=array();
	$anoteaList=array();
	$domaineLabel='';
	$criteria=$this->get('criteria',array());
	if(!is_array($criteria)) $criteria=array();

	/* On renvoie vers la home si les deux champs ne sont pas remplie, sauf les cas de recherche par organisme */
	if(!$criteria['code'] && !$criteria['search'] && !$criteria['locationpath'] && !$criteria['orgaid'])
		$this->forward($this->rewrite('/index.php'));

	/* Analyse du retour de formulaire de recherche */
	if(array_key_exists('search',$criteria)) $criteria['search']=trim($criteria['search']);
	if(isset($criteria))
		if(isset($criteria['domaine']) || isset($criteria['code']) || isset($criteria['search']) || isset($criteria['locationpath']) || isset($criteria['orgaid']))
		{
			$ref=new Reference($db);
			$adSearch=new AdSearch($db);

			/* récuperation liste région si pas de lieu donné */
			$regionList=false;
			if(!isset($criteria['locationpath']))
				$regionList=$ref->get('LOCATION','*',3,3);

			/* On recupe le libelle du lieu a partir de son path pour l'inserer dans l'input afin que l'utilisateur voit bien le lieu entré */
			if(isset($criteria['code']))
				if($code=array_values($ref->get('ROMEAPPELLATION',$criteria['code'])))
				{
					$code=$code[0];
					$criteria['search']=$code['label'];
				}

			/* Récupération de la liste des résultats */
			$s=$criteria;
			if(isset($s['code'])) unset($s['search']); //Si le formacode est fourni on ne fait pas de recherche mot clef

			/* Récupération des critères du formulaire de recherche avancee et injection dans le tableau de critères pour $adSearch->getList() */
			if($duree=$this->get('duree')) $s['duree']=$duree;
			if($dateDebut=$this->get('datedebut')) $s['datedebut']=$dateDebut;
			if($niveauSortie=$this->get('niveausortie')) $s['niveausortie']=$niveauSortie;
			if($certification=$this->get('certification')) $s['certifiante']=true;
			if($modalite=$this->get('modalite')) $s['modalite']=$modalite;
			if($contratapprentissage=$this->get('contratapprentissage')) $s['contratapprentissage']=$contratapprentissage;
			if($contratprofessionnalisation=$this->get('contratprofessionnalisation')) $s['contratprofessionnalisation']=$contratprofessionnalisation;
			if($codeFinanceur=$this->get('codefinanceur')) $s['codefinanceur']=$codeFinanceur;
			if($cpf=$this->get('cpf')) $s['tri_cpf']=true; else $s['tri_cpf']=false;			

			$limit=$this->get('limit',array(100));
			$limit=array_pop($limit);
			if($limit>400) $limit=400; //limite maximum à 400 annonces par liste, 100 par défaut

			$financement=$this->get('financement');
			if ($financement) {
				if($financement=='financementsalarie') $s['financementsalarie']='financementsalarie';
				if($financement=='financementdeentier') $s['financementdeentier']='financementdeentier';
				if($financement=='financementdepic') $s['financementpic']='financementpic';
			}
			$distance=30;
			if(($dist=$this->get('distance')) && in_array($dist,array(15,30,45,60))) $distance=$dist;

			//Si la recherche donne zéro résultats, on tente une correction orthographique et on relance la recherche.
			//on check quand meme si le correcteur en renvoie pas les memes mots-cles, ce qui rend inutile une nouvelle recherche.

			$searchCorrected=false;
			$s['search']=$adSearch->simplifieur($s['search']);

			$isSuccess=false;
			//$adList=$adSearch->getList($s,$distance);
			$adList=$adSearch->getListNew($s+array('distance'=>$distance),0,$limit);
			if($adList!==false)
			{
				$isSuccess=true;

				if(empty($adList))
				{
					$search=$adSearch->corrector($s['search']);
					if($search!=$s['search'])
					{
						$s['search']=$search;
						$isSuccess=false;
						//$adList=$adSearch->getList($s,$distance);
						$adList=$adSearch->getListNew($s+array('distance'=>$distance),0,$limit);
						if($adList!==false) $isSuccess=true;
						$searchCorrected=true;
					}
				}

				if($isSuccess!==false)
				{
					$adList=new QArray($adList);
					$locationPath='';
					$showMoreList=array();
					$extendSearch=false;
					$dontSortByDist=true;

					/* Si la liste est vide, on récupe la liste d'extension de recherche */
					if(!count($adList)) $extendSearch=true;
					if($locationPath=$s['locationpath'])
					{
						$pathLevel=Reference::getLevelFromPath($s['locationpath']);
						/* Et si la liste de résultats comporte moins de 10 lignes et que le champ lieu est renseigné on propose une extension de recherche */
						if(count($adList)<=10)
							if($pathLevel>2 && $pathLevel<=5)
								$extendSearch=true;
						if($pathLevel>=5) $dontSortByDist=false;
					}
					if($extendSearch)
					{
						$cntAd=$adSearch->getCount($s);
						if(!empty($cntAd)) $showMoreList['lieux']=$cntAd;
					}

					$search=$loc='';
					if(array_key_exists('search',$criteria)) $search="de « ".strtolower($criteria['search'])." »";
					if(array_key_exists('location',$criteria)) $loc="pour « ".strtolower($criteria['location'])." »";
					$backTitle=sprintf('Retour aux <span class="hidden-xs">offres de</span> formations <span class="hidden-xs">%s %s</span>',$search,$loc);

					if($criteria['domaine'])
					{
						$backTitle='Retour';
						$c=$criteria;
						unset($c['domaine']);
						$backLink=$this->rewrite('/result.php',array('criteria'=>$c));

						if($domaineLabel=$ref->getByExtraData('FORMACODE',$criteria['domaine']))
							$domaineLabel=array_values($ref->getByExtraData('FORMACODE',$criteria['domaine']))[0]['label'];
					}

					$nbDomaines=0;
					if($criteria['orgaid'])
					{
						$organisme=new Orga($db);
						if($orga=$organisme->getById($criteria['orgaid']))
							if(!$criteria['domaine'])
							{
								if($domaines=$adSearch->getNbAdByDomainByOrga($criteria['orgaid']))
									foreach($domaines as $dom)
										$domaineList[$nbDomaines++]=array(
												'label'=>$dom['label'],
												'labelslug'=>$dom['labelslug'],
												'domaine'=>$dom['formacode'],
												'nbannonces'=>$dom['nbannonces']
											);
							}
					}

					if(count($adList))
						foreach($adList as $ar)
						{
							$anoteaList[]=array(
								$ar['uid'],
								$ar['sessions[0]/uida'],
								$ar['sessions[0]/uid']
							);
						}
				}
			}
		}

	/* Immersion */

	$immersion=false;

	if ($lp=$criteria['locationpath'] && $criteria['code']) $immersion=true;

	/* Appel de la vue template */
	if($isSuccess)
		$this->view('/result_view.php',
			array(
				'adList'=>$adList,
				'criteria'=>$criteria,
				'regionList'=>$regionList,
				'showMoreList'=>$showMoreList,
				'backLink'=>$backLink,
				'backTitle'=>$backTitle,
				'dontSortByDist'=>$dontSortByDist,
				'noRobots'=>($s['search'] || !count($adList)?true:false), //place un meta noindex si recherche par mots clefs ou vide
				'searchCorrected'=>$searchCorrected,
				'engine'=>false,
				'orga'=>$orga,
				'nbDomaines'=>$nbDomaines,
				'domaine'=>$criteria['domaine'],
				'domaineLabel'=>$domaineLabel,
				'domaineList'=>$domaineList,
				'page'=>'result',
				'anoteaList'=>$anoteaList,
				'immersion'=>$immersion
			));

	if(!$isSuccess)
	{
		$this->view('/error_view.php');
	}
?>
