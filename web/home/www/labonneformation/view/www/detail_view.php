<?php _BEGINBLOCK('title'); ?>Formation de "<?php _H($ar['intitule']);?>"<?php _ENDBLOCK('title'); ?>
<?php _BEGINBLOCK('description'); ?><?php _H($ar['objectif:'.$ar['description']]);?><?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/detail.less')); ?>
	<!--<link href="https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.css" rel="stylesheet"/>-->
	<link rel="canonical" href="<?php _T($canonical);?>"/>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
	<?php $asset->add('js',array('/js/detail.js')); ?>
	<script src="https://anotea.pole-emploi.fr/static/js/widget/anotea-widget-loader.min.js"></script>
	<script>
		$(document).ready(function() {
			initDetail({
				"romes": <?php _JS(implode(' ',$ar['codes-rome,array()']->toArray()));?>,
				"location": <?php _JS(str_replace('/','_',$ar['sessions[0]/localisation/formation/path']));?>,
				"displayjobs": <?php _T(in_array($ar['codes-formacode[0]'],explode(',',TRE_FORMACODEEXCEPTION)) || in_array(substr($ar['codes-formacode[0]'],0,3),explode(',',TRE_DOMAINEXCEPTION))?'false':'true');?>,
				"alternance": <?php _JS(in_array('CONTRATPROFESSIONNALISATION',$ar['caracteristiques,array()']->toArray())?'professionnalisation':(in_array('CONTRATAPPRENTISSAGE',$ar['caracteristiques,array()']->toArray())?'apprentissage':''));?>,
			});

			sessions=[["<?php _T($ar['uid'].'","'.$ar['sessions[0]/uida'].'","'.$ar['sessions[0]/uid']) ?>"]];
			var paramsCb={
				template:$("#template-avis"),
				zoneId:'#tous-les-block-avis'
			};

			// Dans anotea.js
			//initAvis(paramsCb,sessions);
			track(<?php _JS($aDistance?'A DISTANCE':'PRESENTIEL');?>);
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('followlink'); ?>
	<div class="row section-ariane">
		<div class="col-xs-9">
			<a id="backlink" href="<?php _T($backLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> Retour aux offres de formations</a>
		</div>
		<div class="col-xs-3 text-right">
			<a id="pred-link" href="" class="lien-navigation"><span class="fa fa-chevron-left"></span></a>
			&nbsp;&nbsp;|&nbsp;&nbsp;
			<a id="next-link" href="" class="lien-navigation"><span class="fa fa-chevron-right"></span></a>
		</div>
	</div>
<?php _ENDBLOCK('followlink'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<div class="col-md-8 section-annonce">
			<h1><?php _H($ar['intitule']);?></h1>
			<?php if (defined('SHOW_COVID19') && SHOW_COVID19===true && $ar['sessions[0]/financeurs[0]/code']==4 && $ar['caracteristiques[0]']=='ADISTANCE'): ?>
				<div class="tags text-right">
					<span class="financeepe">Financée par Pôle emploi</span><span class="covidfoad">100% à distance</span>
				</div>
			<?php endif?>
		</div>
	</div>

	<div class="row">
		<!-- Colonne de gauche (Annonce, conseils, ...) -->
		<div class="col-md-8">
			<div class="row section-annonce">
				<div class="col-md-12 block annonce">
					<div class="annonce">
						<span class="titre" data-target="#descriptif">Descriptif <span class="fa fa-chevron-down visible-xs"></span></span>
						<div id="descriptif" class="collapse-xs">
							<?php if(count($ar['sessions'])): ?>
								<div class="item">
									<?php displayDurationDetail($ar['sessions[0]/nombre-heures-total:0'],$ar['sessions[0]/nombre-heures-entreprise:0'],$ar['sessions[0]/nombre-heures-centre:0']	); ?>									
								</div>
							<?php endif ?>
							<div class="item">
								<h3>Session</h3>
								<div class="item">
									<?php if(in_array('ENTREESSORTIESPERMANENTES',$ar['caracteristiques,array()']->toArray())): //!AdSearch::isFlag($ad['flags'],'ENTREESSORTIESPERMANENTES')): ?>
										<p>entrées/sorties permanentes</p>
									<?php endif ?>
									<div data-toggle="collapse" data-target="#autres-sessions">
										<?php displaySession($ar['sessions[0]'],count($ar['sessions'])); ?>
										<?php if(count($ar['sessions'])>1):?><a href="#">+ autres sessions</a><?php endif ?>
									</div>
									<div id="autres-sessions" class="collapse">
										<?php $i=0; foreach($ar['sessions'] as $session): $i++; /* Affiche la liste des sessions */ ?>
											<?php
												if(!$i) continue;
												displaySession($session,count($ar['sessions']));
											?>
										<?php endforeach ?>
									</div>

									<?php if(count($ar['sessions'])==1): ?>
										<div class="prochaines-sessions">Pour connaître les dates des prochaines sessions, veuillez contacter l'organisme de formation</div>
									<?php endif ?>

									<?php if(SHOW_INTERCARIF===true && $ar['catalogue']=='Intercarif'): ?>
										<p>
											<strong>Inter-Carif</strong> <i><?php _H($ar['uid']);?></i>
										</p>
										<?php if($rncp=$ar['code-rncp']):?>
											<p>
												<strong>RNCP</strong> <i><?php _H($rncp);?></i>
											</p>
										<?php endif ?>
									<?php endif ?>
								</div>
							</div>
							<?php displayItem2($ar,'objectif','Objectif de la formation','Non précisé par l\'organisme de formation') ;?>
							<?php displayItem2($ar,'description','Description de la formation','Non précisé par l\'organisme de formation'); ?>
							<?php displayItem2($ar,[
									'conditions-specifiques'=>'Conditions spécifiques',
									'conditions-prise-en-charge'=>'Conditions de prise en charge',
									'info-public-vise'=>'Public visé'
								],'Conditions d\'accès','Non précisé par l\'organisme de formation'); ?>
							<?php displayItem2($ar,'resultats-attendus','Validation'); ?>
							<?php displayItem2($ar,'libelles-codes-rome-vises','Donne accès au(x) métier(s) suivant(s)'); ?>
							<?php displayItem2($ar,[
									'modalites-pedagogiques'=>'Modalités pédagogiques',
									'modalites-enseignement'=>'Modalités d\'enseignement',
									'modalites-alternance'=>'Modalités de l\'alternance',
									'duree-indicative'=>'Durée indicative'
								],'Informations complémentaires'); ?>
							<?php //displayItem2($ar,'','Pour en savoir plus','urlformation',null); ?>


							<?php //displayItem($content,'Objectif de la formation','objective','Non précisé par l\'organisme de formation'); ?>
							<?php //displayItem($content,'Description de la formation','description','Non précisé par l\'organisme de formation'); ?>
							<?php //displayItem($content,'Conditions d\'accès','[display=condition]','Non précisé par l\'organisme de formation',array('condspec'=>'Conditions spécifiques : ','condprise'=>'Conditions de prise en charge : ','infpubvis'=>'Public visé : ')); ?>
							<?php //displayItem($content,'Validation','sanction'); ?>
							<?php //displayItem($content,'Donne accès au(x) métier(s) suivant(s)','targetromes'); ?>
							<?php //displayItem($content,'Informations complémentaires','[display=modality]',null,array('modped'=>'Modalités pédagogiques : ','modens'=>'Modalités d\'enseignement : ','modalt'=>'Modalités de l\'alternance : ','moddurind'=>'Durée indicative : ')); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row visible-xs visible-sm">
				<div id="copie-colonne-droite" class="col-md-12"></div>
			</div>
			<div class="row">
				<div class="col-md-12 block mis-en-valeur">
					<span class="titre perspectives" data-target="#perspectives">Et après la formation ? <span class="fa fa-chevron-down visible-xs"></span></span>
					<div id="perspectives" class="row collapse-xs">
						<?php $tx=$ar['niveau-retour-embauche:0'];?>
						<div class="col-md-6" data-tx="<?php _H($tx);?>">

							<p class="evaluation">
								<?php if($tx): ?>Retour à l'emploi des anciens stagiaires <?php else:?>NON DÉTERMINÉ<?php endif ?>
							</p>

							<p class="evaluation">
								<?php if($tx>0): ?>
									<?php
										if($tx<=1) _H('FAIBLE');
										elseif($tx<=2) _H('CORRECT');
										elseif($tx<=3) _H('SATISFAISANT');
										elseif($tx<=4) _H('ÉLEVÉ');
										else _H('EXCELLENT');
									?>
								<?php endif ?>
							</p>
							<p class="evaluation">
								<span class="rating rate-<?php _T(sprintf('%ld',intval($tx)));?>" ></span>
							</p>
							<!-- Modal -->
							<div class="modal fade" id="info-retour-embauche" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">Comment est calculé le &laquo;&nbsp;Retour à l’emploi&nbsp;&raquo; ?</h4>
										</div>
										<div class="modal-body">
											<?php if($tx): ?>
												<p>Les formations sont triées par efficacité sur le marché du travail dans votre département.</p>
												<p>
													L'outil analyse les données relatives aux stagiaires ayant suivi des formations et calcule les taux de retour à l'emploi pour chaque domaine et par bassin d'emploi (au niveau départemental sinon régional voire national si peu de stagiaires formés).
												</p>
												<p>
													Sont pris en compte, l’ensemble des stagiaires qui, dans les 6 mois suivant la fin de chaque formation, soit ont retrouvé un emploi salarié de 1 mois et plus (hors particuliers employeurs, employeurs publics, employeurs à l’étranger et missions d’intérim à durée non renseignée), soit ont bénéficié d’un contrat aidé ou soit sont créateurs d’entreprise.
												</p>
											<?php else: ?>
												<p>Les formations sont triées par efficacité sur le marché du travail dans votre département.</p>
												<p>
													L'outil analyse les données relatives aux stagiaires ayant suivi des formations et calcule les taux de retour à l'emploi pour chaque domaine et par bassin d'emploi (au niveau départemental sinon régional voire national si peu de stagiaires formés).
												</p>
												<p>
													Attention, pour ce domaine de formation, les résultats relèvent du secret statistique car moins de 20 demandeurs d'emploi ont suivi une formation dans le domaine indiqué, depuis 18 mois, au niveau national.
												</p>
												<p>Nous ne pouvons donc pas présenter l'efficacité des formations dans ce domaine.</p>
											<?php endif ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
										</div>
									</div>
								</div>
							</div>
							<div class="text-center">
								<button type="button" class="btn btn-info btn-lg btn-retour-embauche" data-toggle="modal" data-target="#info-retour-embauche">?</button>
							</div>
						</div>
						<div class="col-md-6">
							<div id="ajaxstats" class="ajax-stats">
								<div class="text-center"><img src="/img/wait2.gif" width="80" alt=""/></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 block conseils">
					<div class="conseils">
						<span class="titre" data-target="#conseils">Conseils <span class="fa fa-chevron-down visible-xs"></span></span>
						<div id="conseils" class="collapse-xs">
							<?php if(!$aDistance):?>
								<strong>Les questions à poser avant de choisir un centre de formation</strong><br/>
								<ul>
									<li>Quels sont les profils des anciens stagiaires (niveau de formation, expérience professionnelle) ? </li>
									<?php if(!$aDistance):?><li>Est-il possible de visiter le centre ?</li><?php endif ?>
									<li>Quel type de public accueillez-vous en formation (salariés, demandeurs d’emploi, particuliers) ?</li>
									<li>Peut-on obtenir une liste de ces anciens stagiaires pour les interroger sur cette formation ?</li>
									<li>Comment aidez-vous les stagiaires à trouver un emploi ?</li>
								</ul>
							<?php else: ?>
								<strong>Les questions à se poser avant de choisir une formation à distance</strong><br/>
								<ul>
									<li>Les modalités de formation proposées par cet organisme sont-elles adaptées à mes besoins et répondent-elles à mes attentes ?</li>
									<li>Y-a-t'il un système de coaching, de contrôle de l’assiduité, de vérification régulière des connaissances acquises ?</li>
									<li>Est-ce que je dispose du matériel adéquat et d'un lieu approprié pour suivre cette formation ? Bonne connexion, webcam, casque, bureau fermé ou accès un espace numérique public ou de co-working ?</li>
									<li>Suis-je suffisamment motivé, auto-discipliné et organisé pour me connecter de façon assidue ?</li>
									<li>Suis-je en capacité de travailler seul ? Si non, puis-je m’appuyer sur une communauté ?</li>
								</ul>
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Colonne de droite (Contact, lieu, ...) -->
		<div class="col-md-4">
			<div id="colonne-droite">
				<div class="info-complementaire">
					<?php if($avril!==false): ?>
						<div class="row">
							<div class="col-md-12 block avril">
								<?php $url=_U('https://avril.pole-emploi.fr/certifications',$avril,true);?>
								Vous avez au moins un an d'expérience en &laquo;&nbsp;<?php _H($avril['romelabel']);?>&nbsp;&raquo; !<br/>
								Vous pouvez obtenir ce diplôme sans suivre la formation !<br/>
								<a id="avril" href="<?php _T($url);?>" target="_blank"><b>Validez vos acquis</b></a>
								<!--
								<script type="text/javascript">
									var loc=sessionStorage.getItem("location");
									var url=<?php _JS($url);?>;
									if(loc) url=url.replace(/city=.*?&/,"city="+(encodeURI(loc).replace("("," ").replace(")",""))+"&");
									document.getElementById("avril").setAttribute("href",url);
								</script>
								-->
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 avril-arrow">
							</div>
						</div>
					<?php endif ?>

					<div class="row">
						<?php if($financement!==false): ?>
							<div class="hidden-sm hidden-xs">
								<a class="btn simulation" onclick="track('FORM FINANCEMENT - BOUTON');" href="<?php $this->rw('/simulatorform.php',array('ar'=>$ar,'step'=>0));?>">Combien ça coûte ?</a>
							</div>
						<?php else: ?>
							<div class="block hidden-sm hidden-xs">
								<p>Cette formation ne peut pas bénéficier d'aide au financement car elle est proposée par un organisme qui ne réside pas en Union Européenne.</p>
							</div>
						<?php endif ?>
					</div>
					<div class="row">
						<div class="col-md-12 block contact">
							<span class="titre hidden-md hidden-sm hidden-lg" data-target="#contact">Informations pratiques<span class="fa fa-chevron-down visible-xs"></span></span>

							<div id="contact" class="contact collapse-xs">
								<?php
									$this->view('/inc/contact_view.php',
										array(
											'nomOrganisme'=>$ar['organisme/nom'],//(string)$orgaContent->get('organame',''),
											'email'=>$ar['sessions[0]/contact/email:'.$ar['organisme/contact/email']], //(string)$orgaContent->get('email',$content->get('email')),
											'telephone'=>$ar['sessions[0]/contact/tel:'.$ar['organisme/contact/tel']], //(string)$orgaContent->get('tel',$content->get('tel')),
											//'fax'=>$ar['formation/contact/fax:'.$ar['organisme/contact/fax']],//(string)$orgaContent->get('fax',$content->get('fax')),
											'mobile'=>$ar['formation/contact/mobile:'.$ar['organisme/contact/mobile']], //(string)$orgaContent->get('mobile',$content->get('mobile')),
											'url'=>$ar['formation/contact/url:'.$ar['organisme/contact/url']], //(string)$orgaContent->get('url',$content->get('url')),
											'adresseOrganisme'=>$ar['organisme/localisation'] ? $ar['organisme/localisation/adresse'].$ar['organisme/localisation/code-postal'].', '.$ar['organisme/localisation/ville'] : null,
											'adresseFormation'=>$aDistance?'À distance':$ar['sessions[0]/localisation/formation/adresse'].$ar['sessions[0]/localisation/formation/ville'],//(string)$content->get('line').(string)$content->get('city'),
											'aDistance'=>$aDistance,
											'orgaId'=>$ar['organisme/id'], //$ad['orgaid'],
											'lat'=>$ar['sessions[0]/localisation/formation/latitude'], //$content->get('lat','0'),
											'lng'=>$ar['sessions[0]/localisation/formation/longitude'], //$content->get('lng','0')
											'sujetMail'=>$sujetMail,
											'messageDefault'=>$messageDefault
										));
								?>
								<div id="avis"></div>
							</div>
						</div>
					</div>
				</div>
				<?php if(true): ?>
					<div class="row">
						<div class="col-md-12">
							<div class="anotea-widget" data-type="session" data-env="recette" data-format="carrousel" data-identifiant="<?php _T($sessions) ?>" data-options="json-ld,contact-stagiaire,avis-details"></div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-5 col-xs-12 block-simulation-ou-impression simulation">
					<?php if($financement!==false): ?>
						<a class="btn simulation" onclick="track('FORM FINANCEMENT - BOUTON');" href="<?php $this->rw('/simulatorform.php',array('ar'=>$ar,'step'=>0));?>">Combien ça coûte ?</a>
					<?php else: ?>
						Cette formation ne peut pas bénéficier d'aide au financement car elle est proposée par un organisme qui ne réside pas en Union Européenne.
					<?php endif ?>
				</div>
				<div class="col-md-3 col-xs-12 block-simulation-ou-impression simulation">
					<?php if($email=$ar['organisme/contact/email']): //if($email=(string)$orgaContent->get('email')): ?>
						<a class="btn secondaire contacter" data-toggle="modal" data-target="#contactModal" onclick="javascript:afficherFormulaireMail('',<?php _JS($sujetMail,'SIMPLE_QUOTE') ?>,<?php _JS($messageDefault,'SIMPLE_QUOTE') ?>,'','orga','orga');">Contacter l'organisme</a>
					<?php endif ?>
				</div>
				<div class="col-md-4 hidden-xs block-simulation-ou-impression impression">
					<a class="btn secondaire impression" href="javascript:window.print();"><span class="fa fa-print fa-picto"></span></a>
				</div>
			</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>

	<?php function displaySession($session,$countAd) { ?>
		<?php
			//$session=new ArrayExplorer($session);
			$expired=$session['debut'] && strtotime($session['debut'])<time()?true:false; ?>
		<div>
			<?php if($countAd>1):?><?php _T(!$expired?'<span class="fa fa-caret-right"></span>':'');?> <?php endif ?>
			<?php if($endDate=$session['fin']):?>
				<i>du <?php _H(Tools::date($session['debut']));?> au <?php _H(Tools::date($endDate));?></i>
			<?php else: ?>
				<i>à partir du <?php _H(Tools::date($session['debut']));?></i>
			<?php endif ?>
			<strong>&nbsp;-&nbsp;<?php _H($session['localisation/formation/ville'].' ('.substr($session['localisation/formation/code-postal'],0,2).')');?></strong>
		</div>
	<?php } ?>

	<?php function displayItem2($ar,$fields,$title,$default=null) { ?>
		<?php
			/* Ici on élimine les champs demandés quand y a rien en BD */
			$f2=array();
			foreach(is_array($fields)?$fields:array($fields) as $k=>$f)
			{
				$f=is_string($k)?$k:$f;
				$o=$ar["$f,array()"];
				if(is_string($o) || is_numeric($o) || count($o))
					$f2[]=$f;
			}
			$fields=$f2;
		?>
		<?php if(!empty($fields) && ($ar[$fields[0]] || !is_null($default))): ?>
			<div class="item">
				<?php if($title):?><h3><?php _H($title);?></h3><?php endif ?> 
				<?php foreach($fields as $key=>$field): ?>
					<div>
						<?php if(is_numeric($key)): ?>
							<?php if($field=='libelles-codes-rome-vises'):?>
								<?php foreach($ar["$field,array()"] as $romeVise): ?>
									<p>
										<?php _H($romeVise['libelle']);?> (<a href="<?php _T(Tools::getPERomeLink($romeVise['rome']));?>" target="_blank">voir la fiche métier</a>)
									</p>
								<?php endforeach ?>
							<?php else: ?>
								<?php _T(displayField2($ar["$field:$default"])); ?> 
							<?php endif ?>
						<?php else: ?>
							<?php if($desc=$ar[$key]):?>
								<p>
									<span class="info-titre"><?php _H($field);?> : </span>
									<span class="info-valeur"><?php _T(displayField2($desc));?></span>
								</p>
							<?php endif ?>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	<?php } ?>

	<?php function displayItem($content,$title,$target,$default=null,$prefix=array()){ ?>
		<?php if(($lines=$content->select($target)) || $default): ?>
			<div class="item">
				<?php if($first=array_values($lines)[0]) $first=$first->inner(); ?>
				<?php if($title && (!empty($first) || $default)):?>
					<h3><?php _H($title);?></h3>
				<?php endif ?>
				<?php if(empty($lines) || empty($first)):?>
					<div><?php _H($default);?></div>
				<?php else: ?>
					<?php foreach($lines as $id=>$line): /* Affiche toutes les zones */ ?>
						<div>
							<?php if(array_key_exists($id,$prefix)):?>
								<?php if($prefix[$id][0]!=':'):?>
									<span class="info-titre"><?php _H($prefix[$id]);?></span>
								<?php else: ?>
									<span class="<?php _T(preg_replace('#^:(.+?):.*$#','$1',$prefix[$id]));?>"><?php _T(preg_replace('#^:(.+?):(.*)$#','$2',$prefix[$id]));?></span>
								<?php endif ?>
							<?php endif ?><span class="info-valeur"><?php _T(displayField($line));?></span><br/>
						</div>
					<?php endforeach ?>
				<?php endif ?>
			</div>
		<?php endif ?>
	<?php } ?>

	
	<?php function displayDurationDetail($duree_totale,$duree_entreprise,$duree_centre) { ?>
		<?php if($duree_totale || $duree_entreprise || $duree_centre): ?>
		<h3>Durée de la formation</h3>
		<?php endif ?>
		<?php if($duree_totale): ?>
			<div>
				Durée <i><?php _H($duree_totale);?> heure<?php _T($duree_totale>1?'s':''); ?></i>
			</div>
		<?php endif ?>
		<?php if($duree_entreprise): ?>
			<div>
				En entreprise <i><?php _H($duree_entreprise);?> heure<?php _T($duree_entreprise>1?'s':''); ?></i>
			</div>
		<?php endif ?>
		<?php if($duree_centre): ?>
			<div>
				En centre <i><?php _H($duree_centre);?> heure<?php _T($duree_centre>1?'s':''); ?></i>
			</div>
		<?php endif ?>
	<?php } ?>

	<?php $this->view('/inc/modal_contact_view.php',
				array(
					'email'=>$ar['sessions[0]/contact/email:'.$ar['organisme/contact/email']])
				); ?>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>

