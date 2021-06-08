<?php _BEGINBLOCK('title'); ?>
	<?php if(count($adList)): ?>Formation<?php _T(count($adList)>1?'s':'');?><?php else: ?>Pas de résultats pour la formation<?php endif ?><?php if($domaineLabel):?> "<?php _H($domaineLabel);?>"<?php endif ?><?php if($criteria['location']): ?> sur <?php _H($criteria['location']); ?><?php endif ?><?php if($criteria['orgaid'] && count($adList)): ?>, chez "<?php _H($orga['nom']); ?>"<?php endif ?>
<?php _ENDBLOCK('title'); ?>
<?php _BEGINBLOCK('description'); ?>
	<?php if($criteria['orgaid']): ?>
		<?php if(($c=count($adList))==0): ?>
			Pas de formation référencée	pour cet organisme
		<?php elseif($c>1): ?>
			Découvrez les <?php _H($c); ?> formations et toutes les infos pratiques sur l'organisme <?php _H($adList[0]['organisme']['nom']); ?>
		<?php else: ?>
			Découvrez toutes les formations et les infos pratiques sur l'organisme <?php _H($adList[0]['organisme']['nom']); ?>
		<?php endif ?>
	<?php else: ?>
		<?php if(($c=count($adList))==0): ?>
			Pas de formation référencée	
		<?php elseif($c>1): ?>	
			Les <?php _H($c); ?> formations 
		<?php else: ?> 
			La formation 
		<?php endif ?>

		<?php if($criteria['search']): ?>
			de &laquo;&nbsp;<?php _H($criteria['search']); ?>&nbsp;&raquo;
		<?php endif ?>
		<?php if($criteria['location']): ?>
			<?php if(Reference::getLevelFromPath($criteria['locationpath'])==5): ?> autour de <?php else: ?> en <?php endif ?>
			&laquo;&nbsp;<?php _H($criteria['location']); ?>&nbsp;&raquo;
		<?php endif ?>
		<?php if($c>1): ?>
			triées par efficacité sur le marché du travail
		<?php endif ?>
	<?php endif ?>		
<?php _ENDBLOCK('description'); ?>
<?php _BEGINBLOCK('script'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
	<script src="https://anotea.pole-emploi.fr/static/js/widget/anotea-widget-loader.min.js"></script>
	<?php $asset->add('js',array('/js/result.js')); ?>

	<script>
		$(document).ready(function() {
			var params=<?php if(!isset($criteria['orgaid'])): ?>{showadvice:'<?php _T(count($adList)>50) ?>'}<?php else:?>null<?php endif ?>;
			var sessions=<?php _T(json_encode($anoteaList));?>;

			<?php if($domaine): ?>
				var paramsAnotea={
					template:$("#template-avis"),
					zoneId:'#tous-les-block-avis-large',
					largeur:'large'
				};

				// Dans anotea.js
				initAvis(paramsAnotea,sessions);
			<?php endif; ?>

			initResult(params,sessions,<?php _T($domaine?'true':'false');?>);

			<?php if($immersion): ?>
				pageview('/immersion/_results');
			<?php endif; ?>
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('css'); ?>
	<!--<link href="https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.css" rel="stylesheet"/>-->
	<?php $asset->add('css',array('/css/result.less')); ?>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
	<?php if($searchCorrected):?><link rel="canonical" href="<?php $this->rw('/result.php',array('criteria'=>$criteria));?>"/><?php endif ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('followlink'); ?>
	<?php if($backLink && $backTitle): ?>
		<div class="row section-ariane">
			<div class="col-xs-12">
				<a id="backlink" href="<?php _T($backLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> <?php _H($backTitle);?></a>
			</div>
		</div>
	<?php endif ?>
<?php _ENDBLOCK('followlink'); ?>

<?php _BEGINBLOCK('content'); ?>
	<?php function displayShowMoreList($quark,$showMoreList) { ?>
		<?php if(array_key_exists('lieux',$showMoreList) && !empty($showMoreList['lieux'])): ?>
			<div class="row">
				<div class="col-md-12">
					<span class="titre mobile-geographiquement">Vous êtes mobile ! Élargissez votre recherche géographique.</span>
				</div>
			</div>
			<div class="row">
				<div class="block-elargissez">
					<?php foreach($showMoreList['lieux'] as $line): ?>
						<?php if(!$line['cnt']) continue; ?>
						<?php $link=$quark->rewrite('/result.php',array('criteria'=>array('locationpath'=>$line['locationpath'],'code'=>$line['code'],'search'=>$line['keywords']))); ?>
						<div class="block">
							<?php $level=Reference::getLevelFromPath($line['locationpath']); ?>

							<div>
								<?php if($level==4): ?>
									<p class="libelle">Dans votre département :</p>
									<span class="titre"><?php _H($line['label']); ?></span>
								<?php elseif($level==3): ?>
									<p class="libelle">Dans votre région :</p>
									<span class="titre"><?php _H($line['label']); ?></span>
								<?php else: ?>
									<span class="titre">France entière</span>
								<?php endif ?>
							</div>
							<div>
								<p class="text-center">
									<a href="<?php _T($link);?>" class="btn secondaire">Voir <?php _H($line['cnt']>1?"les ${line['cnt']} formations":'la formation');?></a>
								</p>
							</div>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		<?php endif ?>
		<?php if(array_key_exists('domaines',$showMoreList) && !empty($showMoreList['domaines'])): ?>
			<div class="row">
				<div class="col-md-12">
					<span class="titre mobile-professionnellement">
						Vous êtes mobile professionnellement !<br/>
						Élargissez votre recherche à des domaines de formation proches.
					</span>
				</div>
			</div>
			<div class="row">
				<div class="block-elargissez">
					<?php foreach($showMoreList['domaines'] as $line): ?>
						<?php if(!$line['cnt']) continue; ?>
						<?php $link=$quark->rewrite('/result.php',array('criteria'=>array('locationpath'=>$line['locationpath'],'code'=>$line['code'],'search'=>$line['keywords']))); ?>
						<div class="block">
							<table>
								<tr>
									<td>
										<span class="libelle">Formation de :</span>
										<span class="titre"><?php _H($line['label']); ?></span>
									</td>
								</tr>
								<tr>
									<td valign="bottom">
										<p class="text-center">
											<a href="<?php _T($link);?>" class="btn secondaire">Voir <?php _H($line['cnt']>1?"les ${line['cnt']} formations":'la formation');?></a>
										</p>
									</td>
								</tr>
							</table>
						</div>
					<?php endforeach ?>
				</div>
			</div>
		<?php endif ?>
	<?php } ?>
	<?php function displaySessions($line) { ?>
		<?php if($line['entrees-sorties-permanentes,false'] /* && empty($sessions['avenir'])*/): ?>
			<p>
				avec entrées/sorties permanentes
			</p>
		<?php else:?>
			<?php $sessions=new ArrayExplorer(Tools::splitSessions($line['sessions,array()']->toArray())); ?>
			<?php if(count($sessions)): ?>
				<p>
					<?php if(!count($sessions['avenir'])): ?>
						en cours
					<?php else: ?>
						<?php if($sessions['avenir[0]/fin']): ?>
							du
						<?php else: ?>
							à partir du
						<?php endif ?>
						<?php _T(strtr(Tools::date($sessions['avenir[0]/debut']),array(' '=>'&nbsp;'))); ?>
						<?php if($sessions['avenir[0]/fin']): ?>
							au&nbsp;<?php _T(strtr(Tools::date($sessions['avenir[0]/fin']),array(' '=>'&nbsp;'))); ?>
						<?php endif ?>
						<?php if(count($sessions['avenir'])>1): ?>
							<br/>+ d'autres sessions à venir
						<?php endif ?>
					<?php endif ?>
				</p>
			<?php else: ?>
				<p>Non communiquée par l'organisme</p>
			<?php endif ?>
		<?php endif ?>
	<?php } ?>
	<?php if($criteria['orgaid'] && !$domaine):?>
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<h2 class="titre-principal"><?php _H($orga['nom']);?></h2>
					</div>
				</div>
				<div class="row block contact">
					<div class="col-md-12">
						<span class="titre hidden-md hidden-sm hidden-lg" data-target="#infos-pratiques">Informations pratiques<span class="fa fa-chevron-down visible-xs"></span></span>
						<div id="infos-pratiques" class="collapse-xs">
							<?php
								$this->view('/inc/contact_view.php',
									array(
										//'nomOrganisme'=>"(string)$orgaContent->get('organame','')",
										'email'=>$orga['contact']['email'],
										'url'=>$orga['contact']['url'],
										'sujetMail'=>'Demande d\'information',
										'telephone'=>$orga['contact']['telephone'],
										'fax'=>$orga['contact']['fax'],
										'mobile'=>$orga['contact']['mobile'],
										'url'=>$orga['contact']['url'],
										'adresseCentre'=>$orga['contact']['adresse'],
										'lat'=>$orga['lat'],
										'lng'=>$orga['lng'],
										'orgaId'=>$ad['orga_id'],
										'landscape'=>true
									));
							?>
						</div>
					</div>
				</div>
				<?php if(true): ?>
					<div class="row">
						<div class="col-md-12">
							<div class="anotea-widget" data-type="organisme" data-env="prod" data-format="liste" data-identifiant="<?php _T($orga['siret']) ?>" data-options="json-ld"></div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
		<?php if(!$domaine): ?>
			<div class="row avis-par-domaine" style="display:none;">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<h2 class="titre-avis" style="display:none;">
								<span class="titre-nb-avis" style="display:none;">
									<!--Plusieurs avis de stagiaires-->
								</span>
								<?php if($nbDomaines):?>
									<span class="titre-avis-domaine" style="display:none;">
										<!--<?php _H($nbAvis?' sur ':'')?>-->
									</span>
									<?php _H($nbDomaines);?> domaine<?php _H($nbDomaines>1?'s':'');?> de formation
								<?php endif ?>
							</h2>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div id="carous" class="caroussel">
								<?php foreach($domaineList as $dom): ?>
									<div class="block-domaine-avis" id="domaine-<?php _T($dom['domaine']) ?>">
										<div class="background">
											<img src="/img/backgrounds/domaines/<?php _T(preg_replace('#(.*)-1#','$1.jpg',$dom['labelslug']));?>" alt=""/>
										</div>
										<div class="avis">
											<?php $link=$this->rewrite('/result.php',array('criteria'=>array_merge($criteria,array('domaine'=>$dom['domaine']))));?>
											<h2><a href="<?php _T($link);?>"><?php _H($dom['label']);?></a></h2>
											<p>
												<?php if(true||$avis['nbavis']):?><a href="<?php _T($link);?>"><span id="domaine-nb-avis-<?php _T($dom['domaine']) ?>">Avis (0) </span><span id="domaine-note-moyenne-<?php _T($dom['domaine']) ?>" class="note">&nbsp;</span></a><br/><?php endif ?>
												<a href="<?php _T($link);?>"><?php _H($dom['nbannonces']);?> formation<?php _H($dom['nbannonces']>1?'s':'');?></a>
											</p>
										</div>
									</div>
								<?php endforeach ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		<?php endif ?>
	<?php endif ?>


	<?php if($domaine): ?>
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<h2>Domaine <?php _H($domaineLabel);?></h2>
					</div>
				</div>
				<div class="avis-anotea">
					<div class="row">
						<div class="col-md-12 block block-avis-anotea">
							<div id="caroussel-liste-avis" class="caroussel">
								<?php
									$this->view('/inc/anotea_view.php',
										array(
											'callback_avis_js'=>'avisParDomaine',
											//'domaine'=>$domaine,
											'landscape'=>false,
											'collapse'=>false,
											'caroussel_id'=>'tous-les-block-avis-large',
										));
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>

	<div class="row resultat-formations">
		<input type="hidden" id="backtitle" value="<?php _H($backTitle); ?>"/>

		<div class="col-md-3 col-sm-4 block-avancee">
			<div class="row btn-tri-mobile visible-xs" data-target="#close,#block-criteres">
				<div class="col-md-12">
					Trier / Filtrer
				</div>
			</div>
			<div id="block-criteres" class="row block-criteres collapse-xs">
				<div class="col-md-12">

					<div class="h1 visible-xs">
						<?php _H($c=count($adList)); ?> formation<?php _H($c>1?'s':''); ?> trouvée<?php _H($c>1?'s':''); ?>
						<button id="close" type="button" class="close collapse-xs" aria-label="Close"></button>
					</div>

					<div class="titre-encart">
						<span>Trier les résultats par</span>
					</div>

					<div class="filtre">
						<div>
							<input type="radio" id="tx" name="trier" class="sr-only" value="tx"/>
							<label for="tx">Perspective d'embauche</label>
						</div>
						<div>
							<input type="radio" id="date" name="trier" class="sr-only" value="date"/>
							<label for="date">Date de début</label>
						</div>
						<?php if($criteria['locationpath'] && Reference::getLevelFromPath($criteria['locationpath'])>=5):?>
							<div>
								<input type="radio" id="dist" name="trier" class="sr-only" value="dist"/>
								<label for="dist">Proximité</label>
							</div>
						<?php endif ?>
					</div>

					<div class="titre-encart advicetip" title="" data-title="Trop de résultats&nbsp;?<br/>Essayez les filtres" data-toggle="tooltip" data-placement="top">
						<span>Affiner votre recherche</span>
					</div>

					<form id="formulaire-filtre" action="/result.php" method="get">
						<?php foreach($criteria as $name=>$value): ?>
							<input type="hidden" name="criteria[<?php _H($name);?>]" value="<?php _H($value); ?>"/>
						<?php endforeach ?>

						<?php function input($type,$id,$name,$value,$isChecked,$label) { ?>
							<div>
							<input type="<?php _H($type);?>" id="<?php _H($id);?>" name="<?php _H($name);?>" class="sr-only" value="<?php _H($value);?>"<?php _T($isChecked?' checked':'');?> <?php if (in_array($id,array('contratapprentissage','contratprofessionnalisation'))): ?>onclick="track('<?php _T('CLIC FILTRE '.strtoupper($id))?>');" <?php endif; ?> />
								<label for="<?php _H($id);?>"><?php _H($label);?></label>
							</div>
						<?php } ?>

						<?php if($domaineList): ?>
							<div class="filtre separatif">
								<span class="titre" data-target="#domaine-liste" data-parent="#formulaire-filtre">Domaine de formation <span class="fa fa-chevron-up visible-xs"></span></span>
								<div id="domaine-liste" class="collapse-xs">
									<select name="criteria[domaine]" class="domaine-liste">
										<option value="">-</option>
										<?php foreach($domaineList as $dom):?>
											<option value="<?php _H($dom['domaine']);?>"<?php _T($domaine==$dom['domaine']?' selected':'');?>><?php _H($dom['label']);?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						<?php endif ?>


						<div class="filtre separatif">
							<span class="titre" data-target="#financement-public-target" data-parent="#formulaire-filtre">Public <span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="financement-public-target" class="collapse-xs">
								<?php input('radio','financementdetout','financement','financementdetout',$this->get('financement','financementdetout')=='financementdetout','Demandeur d\'emploi'); ?>
								<?php input('radio','financementsalarie','financement','financementsalarie',$this->get('financement')=='financementsalarie','Salarié'); ?>
							</div>
						</div>

						<div class="filtre separatif">
							<span class="titre" data-target="#financement-entier-target" data-parent="#formulaire-filtre">Financées à 100%<span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="financement-entier-target" class="collapse-xs">
								<?php input('radio','financementdeentier','financement','financementdeentier',$this->get('financement')=='financementdeentier','Demandeur d\'emploi'); ?>
								<!--<?php input('radio','financementdepic','financement','financementdepic',$this->get('financement')=='financementdepic','Au titre du PIC'); ?>-->
								<!-- <div>
									<input type="radio" id="financementdepic" name="financement" class="sr-only" value="financementdepic"<?php _T($this->get('financement')=='financementdepic'?' checked':'');?>/>
									<label for="financementdepic">Au titre du <a href="https://travail-emploi.gouv.fr/grands-dossiers/plan-d-investissement-competences/" target="_blank">PIC</a></label> <a data-toggle="modal" data-target="#info-pic" style="position:relative;top:-5px;cursor:help;"><img src="/img/pictos/picto-information.png" width="12" height="12" alt="?" /></a>
								</div> -->
							</div>
						</div>

						<div class="filtre separatif">
							<span class="titre" data-target="#contrat-target" data-parent="#formulaire-filtre">Contrat en alternance <span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="contrat-target" class="collapse-xs">
								<?php input('checkbox','contratapprentissage','contratapprentissage[]','contratapprentissage',in_array('contratapprentissage',$this->get('contratapprentissage',array())),'Apprentissage'); ?>
								<?php input('checkbox','contratprofessionnalisation','contratprofessionnalisation[]','contratprofessionnalisation',in_array('contratprofessionnalisation',$this->get('contratprofessionnalisation',array())),'Professionnalisation'); ?>
							</div>
						</div>


						<?php if(Reference::getLevelFromPath($criteria['locationpath'])>=5): ?>
							<div class="filtre separatif">
								<span class="titre" data-target="#distance-target" data-parent="#formulaire-filtre">Votre zone de recherche <span class="fa fa-chevron-down visible-xs"></span></span>
								<div id="distance-target" class="collapse-xs">
									<?php input('radio','rayon-15','distance','15',$this->get('distance',30)==15,'15 Km'); ?>
									<?php input('radio','rayon-30','distance','30',$this->get('distance',30)==30,'30 Km'); ?>
									<?php input('radio','rayon-60','distance','60',$this->get('distance',30)==60,'60 Km'); ?>
								</div>
							</div>
						<?php endif ?>

						<?php if($regionList): ?>
							<div class="filtre separatif">
								<span class="titre" data-target="#region-target" data-parent="#formulaire-filtre">Région <span class="fa fa-chevron-down visible-xs"></span></span>
								<div id="region-target" class="collapse-xs">
										<select name="criteria[locationpath]" class="region-liste">
											<option value="">-</option>
											<?php foreach($regionList as $region):?>
												<option value="<?php _H($region['path']);?>"><?php _H($region['label']);?></option>
											<?php endforeach ?>
										</select>
								</div>
							</div>
						<?php endif ?>

						<div class="filtre separatif">
							<span class="titre" data-target="#certification-target" data-parent="#formulaire-filtre">Diplôme ou certification <span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="certification-target" class="collapse-xs">
								<?php input('checkbox','certification','certification','oui',$this->get('certification')=='oui','Oui'); ?>
							</div>
						</div>

						<div class="filtre separatif">
							<span class="titre" data-target="#niveausortie-target" data-parent="#formulaire-filtre">Niveau souhaité <span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="niveausortie-target" class="collapse-xs">
								<?php input('checkbox','bepcap','niveausortie[]','bepcap',in_array('bepcap',$this->get('niveausortie',array())),'BEP/CAP'); ?>
								<?php input('checkbox','bac','niveausortie[]','bac',in_array('bac',$this->get('niveausortie',array())),'BAC'); ?>
								<?php input('checkbox','bacplus2','niveausortie[]','bacplus2',in_array('bacplus2',$this->get('niveausortie',array())),'BAC +2'); ?>
								<?php input('checkbox','bacplus3etplus','niveausortie[]','bacplus3etplus',in_array('bacplus3etplus',$this->get('niveausortie',array())),'BAC +3 +4'); ?>
							</div>
						</div>

						<div class="filtre separatif">
							<span class="titre" data-target="#modalite-target" data-parent="#formulaire-filtre">Type d'enseignement <span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="modalite-target" class="collapse-xs">
								<?php input('checkbox','enorganisme','modalite[]','enorganisme',in_array('enorganisme',$this->get('modalite',array())),'En organisme'); ?>
								<?php input('checkbox','adistance','modalite[]','adistance',in_array('adistance',$this->get('modalite',array())),'À distance'); ?>
							</div>
						</div>

						<div class="filtre separatif">
							<span class="titre" data-target="#cpf-target" data-parent="#formulaire-filtre">Formations éligibles CPF<span class="fa fa-chevron-down visible-xs"></span></span>
							<div id="cpf-target" class="collapse-xs">
								<?php input('checkbox','cpf','cpf','oui',$this->get('cpf')=='oui','Oui'); ?>
							</div>
						</div>

						<div id="button-submit" class="row visible-xs">
							<div class="col-xs-6 text-center">
								<button type="button" class="btn secondaire">Réinitialiser</button>
							</div>
							<div class="col-xs-6 text-center">
								<button type="submit" class="btn">Ok</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php $this->view('/inc/modal_pic_view.php') ?>
		<div class="col-md-9 col-sm-8 col-xs-12">
			<?php if(!count($adList)): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="block-pasderesultats">
							<div class="total-resultats">
								<p>Il n’existe pas de formation référencée<?php if($criteria['search']):?> de "<?php _H($criteria['search']); ?>"<?php endif ?>
								<?php if($criteria['location']): ?> sur <?php _H($criteria['location']); ?><?php endif ?></p>
							</div>
							<?php if(!empty($showMoreList)): ?>
									<?php displayShowMoreList($this,$showMoreList); ?>
								<div class="plus-de-resultats">
								</div>
							<?php else: ?>
								<div class="plus-de-resultats">
									<strong>Suggestion :</strong>
									<ol>
										<li>Vérifiez l’orthographe des termes de recherche.</li>
										<li>Essayez d'autres mots.</li>
									</ol>
								</div>
							<?php endif ?>
						</div>
					</div>
				</div>
			<?php else: ?>
				<div class="row">
					<div class="col-md-12">

						<div class="total-resultats">

							<?php if($immersion): ?><div class="pull-right-sm text-sm-right text-md-right text-lg-right text-xs-center"><a href="<?php $this->rw('/immersion.php',array('criteria'=>$criteria));?>" class="btn bleu">Tester ce métier quelques jours</a></div><?php endif ?>

							<h1>
								<?php _H($c=count($adList)); ?> formation<?php _H($c>1?'s':''); ?><?php _H($criteria['search']?' de '.$criteria['search']:'');?><?php _H($domaineLabel?" dans le domaine \"$domaineLabel\"":'');?><?php _H($criteria['location']?' autour de '.$criteria['location']:''); ?>
								<?php if($criteria['orgaid']): ?>
										<?php _H('pour l\'organisme '.$adList[0]['organisme']['nom']); ?>
								<?php endif ?>
							</h1>
							
						</div>
							<?php if(defined('SHOW_COVID19') && SHOW_COVID19===true): ?>
							<div class="row block-annonce covid19" data-tx="4" data-date="1" data-dist="0" style="border-top: 2px solid #ff8481; background-color: #ff848126;" >
								<div class="col-md-12">
									<div class="row">
										<div class="block-info-formation">
											<div class="col-md-12 titre-formation">
											<h3>
												Plus de 100 formations à distance gratuites et rémunérées pour développer vos compétences sans perdre de temps ! En cliquant <a target="_blank" href="https://candidat.pole-emploi.fr/formations/recherche?financementPossibleOrganismeFinanceur=4&formationCPFPublicConcerne=3&modaliteEnseignement=1&quoi=COVID-19&range=0-9&tri=0">ici</a>.<br/>
												Vous pouvez préciser votre recherche en complétant par un mot clef supplémentaire, exemple : infographie
											</h3>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php endif ?>

						<?php $i=0; foreach($adList as $line): $i++; ?>
							<?php
								$tx=$line['niveau-retour-embauche'];
								$distance=$line['sessions[0]/localisation/formation/distance-recherche'];
								$duration=$line['sessions[0]/nombre-heures-total'];
								$beginDate=$line['sessions[0]/debut'];
								$endDate=$line['sessions[0]/fin'];
							?>
							<div class="row block-annonce" data-tx="<?php _T($tx>0?Tools::rateTx($tx):''); ?>" data-date="<?php _T($i); ?>" data-dist="<?php _T($distance); ?>">
								<div class="col-md-12">
									<div class="row">
										<div class="block-info-formation">
											<div class="col-md-10 titre-formation">
												<h2><a href="<?php $this->rw('/detail.php',array('ar'=>$line));?>"><?php _T(highlight($line['intitule'],$criteria['search'])); ?></a></h2>
												<?php if (0 /*$line['pic']*/): ?><a data-toggle="modal" data-target="#info-pic" style="cursor:pointer;"><span class="pic">PIC</span></a><?php endif?>
												<?php if (defined('SHOW_COVID19') && SHOW_COVID19===true && $line['codefinanceur[0]']==4 && $line['a-distance']): ?><span class="financeepe">Financée par Pôle emploi</span><span class="covidfoad">100% à distance</span><?php endif?>
												<?php if ($line['contratapprentissage']): ?><span class="btn tags">Apprentissage</span><?php endif?>
												<?php if ($line['contratprofessionnalisation']): ?><span class="btn tags">Professionnalisation</span><?php endif?>
											</div>
											<div class="col-md-2 avis-formation">
												<a href="<?php $this->rw('/detail.php',array('ar'=>$line)); ?>#avis">
													<span class="nombre-avis nombre-avis-formation-<?php _H($line['uid']); ?>-<?php _H($line['sessions[0]/uida']); ?>-<?php _H($line['sessions[0]/uid']); ?>"><!--0 avis--></span>
													<span class="note note-formation-<?php _H($line['uid']); ?>-<?php _H($line['sessions[0]/uida']); ?>-<?php _H($line['sessions[0]/uid']); ?>">&nbsp;</span>
												</a>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-5 etablissement-formation">
													<span>
														<img src="/img/pictos/picto-organisme-mini.png" style="margin-right:.2em;" alt=""/>
														<a href="<?php $this->rw('/result.php',array('criteria'=>array('orgaid'=>$line['organisme/id'])));?>"><?php _H(Tools::cut($line['organisme/nom'],300)); ?></a>
													</span>
												</div>
												<div class="col-md-4 lieu-formation">
													<img src="/img/pictos/<?php _T($line['a-distance,false']?'picto-laptop.png':'picto-position-mini.png');?>" style="margin-right:.2em;" alt=""/>
													<span>
														<?php if($line['a-distance,false']): ?>
															À distance
														<?php else: ?>
															<?php _H(sprintf('%s (%.2s)',$line['sessions[0]/localisation/formation/ville'],$line['sessions[0]/localisation/formation/code-postal'])); ?>
															<?php if($distance>0.5): ?>
																- <?php _H(sprintf('%.1f',$distance)); ?>&nbsp;km
															<?php endif ?>
															<?php if(count($sessionList=$line['sessions,array()'])>1): ?>
																<?php $lp=$sessionList['0/localisation/formation/path']; ?>
																<?php foreach($sessionList as $session): ?>
																	<?php if($lp==$session['localisation/formation/path']) continue; ?>
																	, <?php _H(sprintf('%s (%.2s)',$session['localisation/formation/ville'],$session['localisation/formation/code-postal'])); ?>, ...
																	<?php break; ?>
																<?php endforeach ?>
															<?php endif ?>
														<?php endif ?>
													</span>
												</div>
												<div class="col-md-3">
												</div>
											</div>
											<div class="row">
												<div class="col-md-5 info-session">
													<h4>Session</h4>
													<div class="info">
														<?php displaySessions($line); ?>
													</div>
												</div>
												<div class="col-md-4 duree-session">
													<?php if($duration): ?>
														<h4>Durée</h4>
														<div class="info">
															<div>
															<?php _H($duration);?> heure<?php _T($duration>1?'s':''); ?>
															</div>
														</div>
													<?php endif ?>
												</div>
												<div class="col-md-3 col-xs-12 block-info-formation en-savoir-plus text-sm-right text-md-right text-lg-right text-xs-center">
													<a class="btn en-savoir-plus" href="<?php $this->rw('/detail.php',array('ar'=>$line)); ?>">En savoir plus</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach ?>

						<?php if(!empty($showMoreList)): ?>
							<br/><br/><br/>
							<?php displayShowMoreList($this,$showMoreList); ?>
						<?php endif ?>
					</div>
				</div>
			<?php endif ?>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
