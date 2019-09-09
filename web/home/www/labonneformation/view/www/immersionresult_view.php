<?php _BEGINBLOCK('title'); ?>Immersion professionnelle, entreprises à contacter<?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('description'); ?>Immersion professionnelle<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('script'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
	<?php $asset->add('js',array('/js/result.js')); ?>
	<?php $asset->add('js',array('/js/jquery/jquery.plugin.datepicker.js')); ?>

	<script>
		$(document).ready(function() {
			/* Remplace le datepicker */
			$(".date").datePicker().on('blur', function(e) {
				var chunks = $(e.currentTarget).val().split('/');
				if(chunks.length === 3) {
					var year = parseInt(chunks[2]);
					if(!isNaN(year) && year<100) {
						year = year>=50 ? year+1900 : year+2000;
						$(e.currentTarget).val(chunks[0] + "/" + chunks[1] + "/" + year);
					}
				}
			}).bind('paste', function() {
				setTimeout(function() {
					$("#birthdate").change();
				},100);
			});
			/* Assure la complétion des libellés ROME */
			$("#search_immersion").complete(
				{
					call:"/ws/ws_formacodecompletion.php?v=3.1&q=%s",
					onvalidate:"close",
					onselect:function(result){
						$("#code_immersion").val(result.value["code"]);
						$("#search_immersion").val(result.value["label"]);
						//$("input[name=locationpath]").val(result.value[2]);
					},
					charmin:3,
					classover:"over",
					classlist:"codecompletion",
					width: 'default',
					lag: 500
				});

			/* Si touche entrée appuyée sur le champ de recherche, récupère le premier code rome de al liste de complétion */
			$("#search_immersion").focus(function() {
				$("#search_immersion").val("");
				$("#code_immersion").val("");
				$("#search_immersion").removeClass("error");
			});

			/* Assure la complétion des libellé de lieux */
			$("#location-immersion").focus(function() {
				$("#location-immersion").val("");
				$("#locationpath-immersion").val("");
				$("#location-immersion").removeClass("error");
			});

			$("#validation").click(function(e) {
				if ($("#code_immersion").val()=="") {
					$("#search_immersion").addClass("error");
					e.preventDefault();
					return false;
				}
				if ($("#locationpath-immersion").val()=="") {
					$("#location-immersion").addClass("error");
					e.preventDefault();
				}
			});

			$("#search_immersion").keyup(function(key) {
				$("#search_immersion").removeClass("error");
				if(key.which==13) {
					if ($("#code_immersion").val()=="") {
						$("#search_immersion").addClass("error");
						key.preventDefault();
					}
				}
			});

			$("#location-immersion").keyup(function(key) {
				$("#location-immersion").removeClass("error");
				if(key.which==13) {
					if ($("#locationpath-immersion").val()=="") {
						$("#location-immersion").addClass("error");
						key.preventDefault();
					}
				}
			});

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

			var backTitle=sessionStorage.getItem("backtitle");
			if(typeof backTitle!=="undefined" && backTitle!==null && backTitle!="") {
				$("#backlink").attr('href',sessionStorage.getItem("backlink"))
					.html('<span class="fa fa-chevron-left"></span>&nbsp;'+backTitle)
					.css("display","block");
			}

			// Modale de contact entreprise
			$(".immersion-modal-link").click(function () {
				$("#noemail").text("Non disponible");
				$("#email").text("");
				$("#email").prop("href","");
				$("#nophone").text("Non disponible");
				$("#phone").text("");
				$("#phone").prop("href","");

				var email=$(this).data('email');
				var phone=$(this).data('phone');

				if (email!='') {
					$("#noemail").text("");
					$("#email").text(email);
					$("#email").prop("href","mailto:"+email);
				}

				if (phone!='') {
					$("#nophone").text("");
					$("#phone").text(phone);
					$("#phone").prop("href","tel:"+phone);
				}

				pageview('/immersion/_contact');
			})

			$('[data-toggle="popover"]').popover({'container':'#popover-container'});

			track(<?php _JS(($errors?'IMMERSION ERREURS':'IMMERSION'). ' '.$departementZipcode);?>);
			pageview('/immersion/_list');
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('css'); ?>
	<!--<link href="https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.css" rel="stylesheet"/>-->
	<?php $asset->add('css',array('/css/immersion.less')); ?>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
	<?php if($searchCorrected):?><link rel="canonical" href="<?php $this->rw('/result.php',array('criteria'=>$criteria));?>"/><?php endif ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('followlink'); ?>
	<div class="row section-ariane">
		<div class="col-xs-12">
			<a id="backlink" href="<?php _T($backLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> <?php _H($backTitle);?></a>
		</div>
	</div>
<?php _ENDBLOCK('followlink'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row page-immersion">
		<input type="hidden" id="backtitle" value="<?php _H($backTitle); ?>"/>
		<h1 class="main-header">Entreprises à contacter pour une demande d'immersion<!-- en <?php _H($label);?> autour de <?php _H($lieu);?>--></h1>

		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-md-12 immersion-formulaire">
					<form id="form-immersion" name="immersion" action="/immersion.php" method="get" style="margin-bottom:20px;">
						<?php _T($form->getTag('etape'));?>

						<div class="row">
							<div class="col-md-4 cold-sm-4 col-xs-12">
								<div class="form-group">
									<label for="search_immersion">Métier recherché</label>
									<?php //_T($form->getTag('search_immersion')->attr(['class'=>'form-control']));?>
									<input name="criteria[search_immersion]" id="search_immersion" type="text" class="form-control" value="<?php _H($criteria['label']);?>"/>
								</div>
								<input type="hidden" name="criteria[code]" id="code_immersion" value="<?php _H($criteria['code']);?>">
							</div>
							<div class="col-md-4 cold-sm-4 col-xs-12">
								<div class="form-group">
									<label for="location-immersion" >Lieu (ville, département ou région)</label>
									<input name="criteria[location-immersion]" id="location-immersion" type="text" placeholder="Dijon, Marseille, Maubeuge, ..." class="form-control" required="required" value="<?php _H($criteria['location']);?>"/>
									<?php //_T($form->getTag('location-immersion','location-immersion')->attr(['placeholder'=> $placeholder,'class'=>'form-control'.(isset($errors['locationpath'])?' error':''),'required'=>'required']));?>
									<input name="criteria[locationpath]" id="locationpath-immersion" type="hidden" value="<?php _H($criteria['locationpath']);?>"/>
									<?php //_T($form->getTag('locationpath-immersion'));?>
								</div>
							</div>
							<div class="col-md-3 col-md-offset-1 col-sm-4 col-xs-12">
								<div class="form-group">
									<button type="submit" id="validation" class="btn search"><i class="fa fa-search"></i>&nbsp;&nbsp;Rechercher</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Colonne principale -->
		<div class="col-md-8 col-sm-8 col-xs-12">
			<div class="col-md-12">
				<div class="row" style="margin:auto;">
					<!-- Immersion -->
					<?php if (is_array($entreprises) && count($entreprises)>0): ?>
						<?php $count=0 ?>
						<?php foreach($entreprises as $entreprise): ?>
							<?php $count++; ?>
							<div class="row">
								<div class="panel panel-default">
									<div class="panel-heading">
										<p class="panel-title pull-right"><?php _T($entreprise['secteurlarge']);?></p>
										<h3 class="panel-title"><?php _T($entreprise['enseigne']);?></h3>
									</div>
									<div class="panel-body"<?php if ($count==1): ?>id="popover-container"<?php endif; ?>>
										<?php if ($count==1): ?>
											<div class="pull-right">
												<a data-toggle="popover" tabindex="0" role="button" data-trigger="focus" data-placement="bottom" data-content="<i class='fa fa-list-ul'></i>&nbsp;Cette liste d'entreprises est issue des données de <a href='https://labonneboite.pole-emploi.fr\' target='_blank'>La Bonne Boîte</a> de Pôle emploi qui recense des entreprises proposant le métier auquel vous  voulez vous former." data-html=true>Pourquoi ces entreprises ?</a>
											</div>
										<?php endif; ?>
										<?php if ($entreprise['adresse']): ?>
											<i class="fa fa-map-marker"></i>&nbsp;&nbsp;<?php _T($entreprise['adresse']);?><br/>
										<?php endif; ?>
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
											<!--<?php if ($entreprise['email']): ?><i class="fa fa-envelope"></i>&nbsp;&nbsp;<a href="mailto:<?php _T($entreprise['email']);?>" style="overflow:hidden;text-overflow:ellipsis;overflow-wrap:break-word;display:inline-block;max-width: 100%;" onclick="track('IMMERSION CLIC EMAIL');"><?php _T($entreprise['email']);?></a><br/><?php endif; ?>
											<?php if ($entreprise['telephonecorrespondant']): ?><i class="fa fa-phone"></i>&nbsp;&nbsp;<a href="tel:<?php _T(preg_replace('/\s+/', '',$entreprise['telephonecorrespondant']));?>" onclick="track('IMMERSION CLIC PHONE');"><?php _T($entreprise['telephonecorrespondant']);?></a><?php endif; ?>-->
												<div style="margin-top:15px;">
													<a data-toggle="modal" data-target="#info-immersion" data-email="<?php _T($entreprise['email']);?>" data-phone="<?php _T($entreprise['telephonecorrespondant']);?>" style="cursor:pointer;" class="immersion-modal-link"><span class="conseils-immersion"><i class="fa fa-info"></i>Contacter l'entreprise</span></a>
										</div>
											</div>
										<!--<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="text-center" style="margin-top:15px;"><a data-toggle="modal" data-target="#info-immersion" data-email="<?php _T($entreprise['email']);?>" data-phone="<?php _T($entreprise['telephonecorrespondant']);?>" style="cursor:pointer;" class="immersion-modal-link"><span class="conseils-immersion"><i class="fa fa-info"></i>Contacter l'entreprise</span></a></div>
										</div>-->
										</div>
									</div>
								</div>
							</div>
						<?php endforeach ?>
					<?php else: ?>
						<p style="margin-left:-15px;margin-top:50px;margin-bottom:10px;"><b>0 entreprise à vous proposer pour :</b></p>
						<p style="margin-left:-15px;margin-top:0px;color:#002F75;"><b>- <?php _H($criteria['label']);?></b></p>
						<p style="margin-left:-15px;margin-top:0px;margin-bottom:30px;color:#002F75;"><b>- <?php _H($criteria['location']);?></b></p>
						<p style="margin-left:-15px;margin-top:0px;margin-bottom:30px;">Pour plus de résultats, essayez de modifier votre recherche en changeant votre métier ou le lieu (ville, département ou région).</p>
					<?php endif ?>
					<!-- Immersion -->
				</div>
			</div>

			<?php if(CONTACT_MAIL): ?><div><b>Vous êtes une entreprise ? Vous pouvez <a href="mailto:<?php _M(EMAIL_CONTACT,array('subject'=>'Je souhaite vous contacter', 'body'=>"\nCe message sera transmis sur la boîte de La Bonne Formation, vous pouvez rédiger votre question ou nous signaler une anomalie.\n\nMerci de préciser votre SIRET : \n\nEnvoyé depuis la page ".$this->getUrl()));?>" target="_blank"">nous contacter</a>.</b></div><?php endif ?>
		</div><!-- Fin première colonne -->
		<div class="col-md-3 col-md-offset-1 col-sm-4 col-xs-12"><!-- Seconde colonne-->
			<!-- PDF à télécharger -->
			<div class="row">
				<div class="col-md-12 immersion">
					<h2><i class="fa fa-exclamation-circle"></i> Important</h2>
					<p>La demande d'immersion est possible <b>uniquement</b> si vous êtes :<br/>
					<b>- demandeur d'emploi</b> (y compris salarié en contrat aidé ou en insertion économique)<br/>
					<b>- Allocataire RSA</b><br/>
					<b>- Suivi par une Mission Locale</b><br/>
					<b>- Suivi par Cap Emploi</b></p>
					<p>Complétez avec l'entreprise qui aura donné son accord la convention ci-dessous et transmettez-la par courriel à votre conseiller <b>au moins une semaine avant la date souhaitée de démarrage.</b></p>
					<p><b>À noter : ne commencez pas l'immersion avant d'avoir l'accord de Pôle emploi.</b></p>

					<p><b>À télécharger :</b></p>
				</div>
			</div>
			<div class="row" style="margin-top:20px;margin-bottom:20px;">
				<div>
					<form id="telecharger" class="text-center" method="post" action="<?php $this->rw('/immersion.php',array('criteria'=>$criteria));?>" target="_blank" style="margin-top:20px;margin-bottom:20px;" onclick="track('IMMERSION CLIC PDF ENTREPRISES');">
						<input type="hidden" name="etape" value="3"/>
						<input type="hidden" name="pdf" value="1">
						<input type="hidden" name="criteria[locationpath]" value="<?php _T($locationpath);?>">
						<input type="hidden" name="criteria[rome]" value="<?php _T($rome);?>">
						<button type="submit" class="btn bleu" style="width:90%"><i class="fa fa-download"></i>&nbsp;&nbsp;Liste des entreprises</button>
					</form>
				</div>
				<div>
					<div class="text-center" style="margin-bottom:20px;">
						<a href="/pdf/conseils-immersion-contact-entreprise.pdf" class="btn bleu" target="_blank" style="width:90%" onclick="track('IMMERSION CLIC PDF CONSEILS');"><i class="fa fa-download"></i>&nbsp;&nbsp;Conseils</a>
					</div>
				</div>
				<div>
					<div class="text-center">
						<a href="/pdf/cerfa_13912-04.pdf" class="btn bleu" target="_blank" style="width:90%" onclick="track('IMMERSION CLIC PDF CONVENTION');"><i class="fa fa-download"></i>&nbsp;&nbsp;La convention</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $this->view('/inc/modal_immersion_view.php') ?>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
