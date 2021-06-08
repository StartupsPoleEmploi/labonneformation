<?php
	ini_set('memory_limit','256M');

	global $users_api;
	$users=$users_api;

	$db=$this->getStore('read');

	$doc=$this->get('doc',false);
	$mode=$this->get('mode');
	$this->del('mode');

	$test=false;
	if($user=$this->get('user'))
		if(array_key_exists($user,$users))
			if(Tools::queryControl($this->get(),$users[$user],600)!==false)
				$test=true;
	if(ENV_DEV===true && $user=='TEST') $test=true;
	//$test=true;
	if($test)
	{
		$jsonList=array();
		switch($mode)
		{
			case 'rank':
				$rank=new Rank($db);
				$formacode=trim($this->get('formacode',false));
				$codeInseeVille=trim($this->get('codeinseeville',$this->get('codeinsee',false)));

				if($doc)
				{
					$formacode=13021;
					$codeInseeVille=35001;
				}
				if($formacode && $codeInseeVille)
				{
					$list=$rank->getList($formacode,$codeInseeVille);
					if($list!==false)
					{
						foreach($list as $line)
							$jsonList[]=array(
								'formacode'=>$line['formacode'],
								//'label'=>$line['label'],
								//'location-slug'=>$line['locationslug'],
								'codeinsee-bassin'=>$line['codeinsee'],
								'taux-bassin'=>is_null($line['bassinrate'])?'':$line['bassinrate'],
								'taux-departemental'=>is_null($line['departementalrate'])?'':$line['departementalrate'],
								'taux-regional'=>is_null($line['regionalrate'])?'':$line['regionalrate'],
								'taux-national'=>is_null($line['nationalrate'])?'':$line['nationalrate'],
							);
					} elseif($rank->error)
					{
						$code=$rank->error['code'];
						unset($rank->error['code']);
						switch($code)
						{
							case 'ERROR_MISSING':
							case 'ERROR_NOTSTATS':
							case 'ERROR_NOTFOUND':
								$jsonList=$rank->error;
								break;
						}
						http_response_code(400);
					}
				}
				break;
			case 'location':
				$ref=new Reference($db);
				foreach($ref->get('LOCATION','/1/1/*') as $line)
				{
					$l=array(
						'path'=>$line['path'],
						'label'=>$line['label'],
						'location-slug'=>$line['labelslug']
					);
					if($zipcode=Reference::extraDataAll('zc',$line['extradata'],array()))
						$l['zipcode']=$zipcode;
					if($lat=Reference::extraData('lt',$line['extradata'],''))
						$l['lat']=$lat;
					if($lng=Reference::extraData('lg',$line['extradata'],''))
						$l['lng']=$lng;
					$jsonList[]=$l;
				}
				break;
			case 'detail':
				$jsonList=false;
				$adSearch=new AdSearch($db);

				$uid=trim($this->get('uid',''));
				$id=trim($this->get('id',''));

				if($uid)
					$ad=$adSearch->getByIdNew($uid,'findbyuid');
				elseif($id)
					$ad=$adSearch->getByIdNew($id);

				$properties=array();
				if(!empty($ad))
				{
					// on met les flags publiques sont forme de booleen
					$properties=array(
						'contrat-professionnalisation'=>'CONTRATPROFESSIONNALISATION',
						'contrat-apprentissage'=>'CONTRATAPPRENTISSAGE',
						'entrees-sorties-permanentes'=>'ENTREESSORTIESPERMANENTES',
						'certifiante'=>'CERTIFIANTE',
						'rncp'=>'RNCP',
						'pic'=>'PIC',
					);
					$flags=$ad['caracteristiques']?:array();

					if(array_walk($properties,function(&$v) use($flags){return $v=in_array($v,$flags);}))
					{
						unset($ad['caracteristiques']);
						$jsonList=$ad+array_filter($properties);
					}
					else
						error_log('API detail - ligne '.__LINE__.': échec du filtrage des flags');
				}

				break;
			case 'catalogue':
				$doc='';
				$jsonList=array();
				$rome=trim($this->get('rome',false));
				$codeInsee=trim($this->get('codeinsee',false));
				$limit=trim($this->get('limit',1000));
				$adSearch=new AdSearch($db);
				$content=new QContentParser();
				$list=$adSearch->getList(array('rome'=>$rome,'codeinsee'=>$codeInsee));
				$i=0;
				foreach($list as $ad)
				{
					if($i++>=$limit) break;
					$item=array();
					$item['formacode']=explode(' ',$ad['formacode']);
					$item['intitule']=utf8_encode($ad['title']);
					$item['lieu-de-formation']=utf8_encode(sprintf('%s > %s',$ad['locationparentlabel'],$ad['locationlabel']));
					$item['organisme']=utf8_encode($ad['organame']);
					$item['distance']=utf8_encode($ad['dist']); //En metre
					$tx=$ad['nationalrate'];
					$txType='national';
					if($ad['bassinrate']) {$tx=$ad['bassinrate']; $txType='bassin';}
					elseif($ad['departementrate']) {$tx=$ad['departementrate']; $txType='departement';}
					elseif($ad['regionalrate']) {$tx=$ad['regionalrate']; $txType='region';}
					else {$tx=''; $txType='national';}
					$item['tx']=$tx;
					$item['tx-type']=$txType;
					$item['lat']=$ad['lat'];
					$item['lng']=$ad['lng'];
					$item['url']=URL_BASE.$this->rewrite('/detail.php',array('ad'=>$ad));
					$content->parse($ad['content']);
					$desc=(string)$content->get('objective',$content->get('description'));
					$item['description']=utf8_encode(substr($desc,0,300).(strlen($desc)>300?'...':''));
					$jsonList[]=$item;
				}

				$jsonList=array(
						'resultat-total'=>count($list),
						'resultat-url'=>URL_BASE.$this->rewrite('/result.php',array('criteria'=>array('rome'=>$rome,'codeinsee'=>$codeInsee))),
						'annonces'=>$jsonList
					);
				//$jsonList=$list;
				break;
		}
		if(!$doc)
		{
			$this->header('Content-Type: application/json');
			if($jsonList!==false)
			{
				$json=json_encode($jsonList);
				if($json!==false)
				{
					echo $json;
					return;
				}
			}
			//utf8_encode(echo json_last_error());
			http_response_code(400);
			return;
		}
	} else $doc='';
		//$this->forward('/404.php');
	$asset=$this->getGeneral('asset');
?>
<?php _BEGINBLOCK('title'); ?>Documentation API<?php _ENDBLOCK('title'); ?>
<?php _BEGINBLOCK('description'); ?>Documentation API<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('css'); ?>
	<style>
		.doc {
			padding:4em;
		}
		.json-sample {
			background-color:black;
			padding:1em;
			border:1px solid black;
		}
		.id {
			color:#F92672;
			-font-weight:bold;
		}
		.data {
			color:#E6DB74;
			-font-weight:bold;
		}
		.ponc {
			color:white;
			-font-weight:bold;
		}
	</style>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<?php if($doc): ?>
			<div class="col-md-12 doc">
				<div class="row">
					<div class="col-md-12">
						<h2>API Documentation v1</h2>
					</div>
				</div>
				<?php if($mode=='rank'): ?>
					<div class="row">
						<div class="col-md-12">
							<h3>La Bonne Formation affiche les données relatives au retour à l’emploi des demandeurs d’emploi à l’issue de leurs formations.</h3>

							<p>Plus précisément, l’API restitue le niveau de retour à l'emploi de l’ensemble des demandeurs d’emploi sortants de formation depuis janvier 2013.</p>
							<p>Le niveau de retour à l'emploi mesure la part des stagiaires inscrits à Pôle emploi qui, dans les 6 mois suivant la fin de chaque formation, ont retrouvé un emploi salarié de 1 mois et plus (hors particuliers employeurs, employeurs publics, employeurs à l’étranger et missions d’intérim à durée non renseignée), ou ont bénéficié d’un contrat aidé ou ont créé leur entreprise (Source : données Pôle emploi).</p>
							 
							<ul>
								Les règles de diffusion sont les suivantes :
								<li>Champ : ensemble des sortants de formation depuis janvier 2013, soit plus d’1 million de sortants</li>
								<li>Seuil de diffusion minimale : 60 sortants de formation par formacode au niveau national (sinon le formacode ne figure pas dans le fichier)</li>
								<li>
									Indicateur diffusé : note allant de 1 à 5 correspondant à la répartition des taux d’accès à l’emploi pour l’ensemble de la population (5 = taux les plus élevés) . Les classes sont présentées en quintiles avec les valeurs suivantes : 43% 53 % 60%  et 68%.
									<ul>
										<li>La note au niveau bassin est masquée si moins de  60 répondants pour le croisement formacode/bassin.</li>
										<li>La note au niveau département est masquée si moins de  60 répondants pour le croisement formacode/département.</li>
										<li>La note au niveau région est masquée si moins de  60 répondants pour le croisement formacode/région.</li>
										<li>La note au niveau national n’est jamais masquée (puisqu’au moins 60 répondants par construction).</li>
									</ul>
								</li>
							</ul>
							<h3>Paramètrage</h3>
							<ul>
								<li><strong>Url :</strong> <?php _T(URL_BASE);?>/api/v1/rank</li>
								<li><strong>Params :</strong> formacode=xxx, codeinseeville=xxx, key=your_partner_key</li>
							</ul>
							<ul>
								<li><?php _T(URL_BASE);?>/api/v1/rank?<strong>key</strong>=your_partner_key&<strong>formacode</strong>=<?php _H($formacode);?>&<strong>codeinseeville</strong>=<?php _H($codeInseeVille);?></li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php ob_start(); ?>
								<?php _T(json_encode($jsonList,JSON_PRETTY_PRINT));?>
							<?php $code=ob_get_clean(); ?>
							<?php
								$json=_H(rtrim($code),true);
								$json=preg_replace('#\t#','&nbsp;&nbsp;&nbsp;&nbsp;',$json);
								$json=preg_replace('#([a-z]+): #','<span class="id">$1</span>: ',$json);
								$json=preg_replace('#(&quot;)?(.*)(&quot;)?#','<span class="data">$1$2$3</span>',$json);
								$json=preg_replace('#[\[\],{}]#','<span class="ponc">$0</span>',$json);
								$json=preg_replace('#^(.*)$#mui',"$1<br/>",$json);
							?>
							<div class="json-sample">
								<?php _T($json); ?>
							</div>
						</div>
					</div>
				<?php elseif($mode): ?>
					<div class="row">
						<div class="col-md-12">
							<h3>1- Accès au catalogue des formations</h3>
							<ul>
								<li><strong>Url :</strong> <?php _T(URL_BASE);?>/api/v1/offers</li>
								<li><strong>Params :</strong> formacode=xxx, search=xxx, locationslug=xxx, key=your_partner_key</li>
							</ul>
							<strong>Exemples :</strong><br/>
							<ul>
								<li><?php _T(URL_BASE);?>/api/v1/offers?<strong>key</strong>=your_partner_key&<strong>search</strong>=anglais&<strong>locationslug</strong>=nantes-44</li>
								<li><?php _T(URL_BASE);?>/api/v1/offers?<strong>key</strong>=your_partner_key&<strong>formacode</strong>=15234&<strong>locationslug</strong>=nantes-44</li>
								<li><?php _T(URL_BASE);?>/api/v1/offers?<strong>key</strong>=your_partner_key&<strong>formacode</strong>=15234&search=<strong>plombier</strong>&<strong>locationslug</strong>=nantes-44</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php ob_start(); ?>
							[{
								id: "54433",
								link: "http://labonneformation.pole-emploi.fr/annonce-formation/les-regles-de-mise-en-oeuvre-dans-le-domaine-de-la-plomberie-sanitaire-54433",
								formation: {
									intitule: "Les règles de mise en oeuvre dans le domaine de la plomberie/sanitaire",
									objectif: "Maîtriser les règles de mise en oeuvre conformes aux règlementations.",
									description: "Savoir réaliser un dossier de plan de réservation pour communiquer avec les autres corps d'état du chantier. - Maîtriser sa préparation de chantier. - Identifier et savoir exploiter les DTU et normes de son activité.",
									validation: "Attestation de formation",
									formacode: [
										"22697"
									],
									eligibilitecpf: true,
									location: {
										locationslug: "loire-atlantique-44",
										locationlabel: "Loire Atlantique > Couëron",
										lng: "-1.733333",
										lat: "47.216667"
									},
									niveau-retour-embauche: 40,
									metiers-vises: [
										"F1603"
									],
									sessions: [{
										begin: "2016-04-11",
										end: "2016-04-13"
									}],
									contact: {
										email: "",
										tel: "",
										fax: "",
										url: ""
									}
								},
								organisme: {
									nom: "",
									siret: "",
									contact: {
										email: "",
										tel: "",
										fax: "",
										url: ""
									}
								}
							}]
							<?php
								$json=_H(rtrim(ob_get_clean()),true);
								$json=preg_replace('#\t#','&nbsp;&nbsp;&nbsp;&nbsp;',$json);
								$json=preg_replace('#([a-z]+): #','<span class="id">$1</span>: ',$json);
								$json=preg_replace('#(&quot;)?(.*)(&quot;)?#','<span class="data">$1$2$3</span>',$json);
								$json=preg_replace('#[\[\],{}]#','<span class="ponc">$0</span>',$json);
								$json=preg_replace('#^(.*)$#mui',"$1<br/>",$json);
							?>
							<div class="json-sample">
								<?php _T($json); ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h3>2- Accès à la base des lieux de France (Source INSEE)</h3>
							<ul>
								<li><strong>Url :</strong> <?php _T(URL_BASE);?>/api/v1/location</li>
								<li><strong>Params :</strong> key=your_partner_key</li>
							</ul>
							<strong>Exemple :</strong><br/>
							<ul>
								<li><?php _T(URL_BASE);?>/api/v1/location?<strong>key</strong>=your_partner_key</li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php ob_start(); ?>
							[{
								path: "/1/1/14/1/1/",
								label: "Achenheim",
								locationslug: "achenheim-67",
								zipcode: [
									"67204"
								],
								lat: "48.576980",
								lng: "7.627030"
							}]
							<?php
								$json=_H(rtrim(ob_get_clean()),true);
								$json=preg_replace('#\t#','&nbsp;&nbsp;&nbsp;&nbsp;',$json);
								$json=preg_replace('#([a-z]+): #','<span class="id">$1</span>: ',$json);
								$json=preg_replace('#(&quot;)?(.*)(&quot;)?#','<span class="data">$1$2$3</span>',$json);
								$json=preg_replace('#[\[\],{}]#','<span class="ponc">$0</span>',$json);
								$json=preg_replace('#^(.*)$#mui',"$1<br/>",$json);
							?>
							<div class="json-sample">
								<?php _T($json); ?>
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		<?php else: ?>
			<div class="col-md-12 doc">
				Vous vous êtes perdu ?<br>
				<a href="/">Rendez-vous ici</a> !
			</div>
		<?php endif ?>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once(VIEW_PATH.'/'.$this->root.'/base_view.php'); ?>
