<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Immersion professionnelle, entreprises à contacter</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="description" content="Widget immersion professionnelle"/>
		<?php _T($asset->combine('css',array('/css/bootstrap/css/bootstrap.css','/css/bootstrap/css/font-awesome.min.css','https://fonts.googleapis.com/css?family=Dosis:400,600,700|Open+Sans:300,400,600,700|Lato:300,400,600,700','/css/base.less','/css/inc/widget_immersion_vertical.less'))); ?>
		<?php _T($asset->combine('css',$asset->get('css'))); ?>
	</head>
	<body>
		<div id="widget-immersion">
			<?php if(!$lieu || !$label): ?>		
				<div id="recherche-container-widget">
					<p class="grand-titre-widget">
						Testez un métier avant de vous former
					</p>
					<div class="immersion-formulaire">
						<form class="formulaire-recherche-immersion" name="immersion" action="/widget-immersion.php" method="get">
							<div class="form-group">
								<label for="search-immersion" id="label-search-immersion">Saisissez un métier</label>
								<input name="criteria[search_immersion]" id="search-immersion" type="text" placeholder="Exemples : graphiste, vendeur, infirmier ..." class="form-control champ-recherche-widget" value="<?php _H($criteria['label']);?>"/>
							</div>
							<input type="hidden" name="criteria[code]" id="code-immersion" value="<?php _H($criteria['code']);?>">
							<input type="hidden" name="format" id="format" value="<?php _H($format); ?>">
							<input type="hidden" name="etape" id="etape" value="<?php _H($etape); ?>">
						
							<div class="form-group">
								<label for="location-immersion" id="label-location-immersion">Saisissez un lieu (ville, département ou région)</label>
								<input name="criteria[location-immersion]" id="location-immersion" type="text" placeholder="Exemples : Lyon, Gironde, Bretagne ..." class="form-control champ-recherche-widget" required="required" value="<?php _H($criteria['location']);?>"/>
								<input name="criteria[locationpath]" id="locationpath-immersion" type="hidden" value="<?php _H($criteria['locationpath']);?>"/>
							</div>
							<div class="form-group" style="max-width:700px;">
								<button type="submit" id="validation" class="rechercher-widget">RECHERCHER</button>
							</div>
						</form>
					</div>
				</div>
			<?php else: ?>
				<div id="resultat-container-widget" class="row">
					<div class="immersion-result col-md-12">	
						<div class="col-md-12 immersion-formulaire">
							<p class="titre-widget">
								Votre recherche
							</p>
							<form class="formulaire-recherche-immersion" name="immersion" action="/widget-immersion.php" method="get">
								<input type="hidden" name="format" id="format-widget" value="<?php _T($format);?>">
								<div class="form-group">
									<input name="criteria[search_immersion]" id="search-immersion" type="text" placeholder="Coiffure, restauration, soudage, ..." class="form-control champ-recherche-widget" value="<?php _H($criteria['label']);?>"/>
									<input type="hidden" name="criteria[code]" id="code-immersion" value="<?php _H($criteria['code']);?>">
								</div>
								<div class="form-group">
									<input name="criteria[location-immersion]" id="location-immersion" type="text" placeholder="Dijon, Marseille, Maubeuge, ..." class="form-control champ-recherche-widget" required="required" value="<?php _H($criteria['location']);?>"/>
									<input name="criteria[locationpath]" id="locationpath-immersion" type="hidden" value="<?php _H($criteria['locationpath']);?>"/>
								</div>
								<div class="form-group" style="max-width:700px;">
									<button type="submit" id="validation" class="rechercher-widget">RECHERCHER</button>											
								</div>
							</form>				
						</div>
						<hr class="separateur-horizontal">
						<?php if(!$c=count($entreprises)): ?>
							<p class="titre-widget">0 entreprise à vous proposer pour :</p>
							<p><b>- <?php _H($criteria['label']);?></b></p>
							<p><b>- <?php _H($criteria['location']);?></b></p>
							<p>Pour plus de résultats, essayez de modifier votre recherche en changeant votre métier ou le lieu (ville, département ou région).</p>
						<?php else: ?>
							<div id="nb_resultats">
								<p class="titre-widget" style="margin-left:15px;margin-bottom:20px;">
									<?php if($c<2): ?><?php _T($c);?> entreprise à contacter :
									<?php else: ?><?php _T($c);?> entreprises à contacter :
									<?php endif ?>
								</p>
							</div>
							<?php $i=0; ?>
							<?php foreach($entreprises as $entreprise): ?>
								<?php $i++; ?>
								<div class="row row-entreprise">
									<p class="enseigne-entreprise-widget row" style="position:relative;">
										<span class="col-xs-1" style="max-width:80px;"><?php _T($i."."); ?></span>
										<span class="col-xs-11"><?php _T($entreprise['enseigne']);?><br/><span class="secteur-entreprise-widget"><?php _T(strtoupper($entreprise['secteurlarge']));?></span></span>
									</p>
									<?php if ($entreprise['adresse']): ?>
										<p class="adresse-entreprise-widget row">
											<span class="col-xs-1 text-right" style="max-width:80px;">
												<img src="/img/pictos/widget_immersion_location.png" class="picto-widget" alt="adresse"/>
											</span>
											<span class="col-xs-11">
												<?php _T($entreprise['adresse']);?>
											</span>
										</p>
									<?php endif; ?>
									<?php if ($entreprise['email']): ?>
										<p class="mail-entreprise-widget row">
											<span class="col-xs-1 text-right" style="max-width:80px;">
												<img src="/img/pictos/widget_immersion_email.png" class="picto-widget" alt="email"/>
											</span>
											<span class="col-xs-11">
												<a href="mailto:<?php _T($entreprise['email']);?>">
													<?php _T($entreprise['email']);?>
												</a>
											</span>
										</p>
									<?php endif; ?>
									<?php if ($entreprise['telephonecorrespondant']): ?>
										<p class="telephone-entreprise-widget row">
											<span class="col-xs-1 text-right" style="max-width:80px;">
												<img src="/img/pictos/widget_immersion_phone.png" class="picto-widget" alt="téléphone"/>
											</span>
											<span class="col-xs-11">
												<a href="tel:<?php _T(preg_replace('/\s+/', '',$entreprise['telephonecorrespondant']));?>">
													<?php _T($entreprise['telephonecorrespondant']);?>
												</a>
											</span>
										</p>
									<?php endif; ?>
									<hr class="separateur-horizontal">
								</div>
							<?php endforeach ?>
						<?php endif ?>
					</div>				
					<hr class="separateur-horizontal">
					<div class="immersion-documents">
						<div class="row">
							<div class="col-md-12">
								<a class="titre-widget liens-documents-widget" data-toggle="modal" data-target="#modal-documents">
									<img src="/img/pictos/widget_immersion_download.png" class="picto-docs-widget dl-picto" alt="télécharger"/>
									&nbsp;Les documents à télécharger (2)
								</a>
							</div>
							<div id="modal-documents" class="modal">
								<div class="modal-dialog">
									<div class="modal-content">									
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<p class="modal-title">Vos documents :</p>
										</div>
										<div class="modal-body">				
											<div class="liens-documents-widget">
												<a href="/pdf/cerfa_13912-04.pdf" target="_blank">
													<img src="/img/pictos/widget_immersion_download.png" class="picto-docs-widget dl-picto" alt="télécharger"/>
													&nbsp;Convention de stage (.pdf)
												</a>
											</div>
											<div class="liens-documents-widget">
												<a href="/pdf/conseils-immersion-contact-entreprise.pdf" target="_blank">
													<img src="/img/pictos/widget_immersion_download.png" class="picto-docs-widget dl-picto" alt="télécharger "/>
													&nbsp;Aide à la prise de contact (.pdf)
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif ?>
		</div>
		<?php _T($asset->combine('js',array('/js/jquery/jquery.min.js','/js/bootstrap/bootstrap.min.js','/js/jquery/jquery.plugin.complete.js','/js/inc/widget_immersion.js'))); ?>
		<?php _T($asset->combine('js',$asset->get('js'))); ?>
		<script>
			$(document).ready(function() {
				$("#location-immersion").complete({
					call: "/ws/ws_locationcompletion.php?v=1.50&adistance=0&showdepartment=1&locationpath=<?php _T(is_array($restrictionCompletion)?implode('|',$restrictionCompletion):$restrictionCompletion);?>&q=%s",
					onvalidate: "auto",
					onselect:function(result) {
						$("#location-immersion").val(result.value["label"]);
						$("#locationpath-immersion").val(result.value["path"]);
					},
					onchange:function(key,result) {
						//if(result.length) $("#locationpath-immersion").val(result[0].value["path"]);
					},
					classover: "over",
					charmin:2,
					classlist: "locationcompletion",
					width: 'default',
					lag: 500
				});
			});
		</script>	
	</body>
</html>
