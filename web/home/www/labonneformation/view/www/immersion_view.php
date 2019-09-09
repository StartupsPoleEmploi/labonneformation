<?php _BEGINBLOCK('title'); ?>Immersion professionnelle | <?php _H($ad['title']);?><?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('description'); ?>
		Immersion professionnelle
<?php _ENDBLOCK('description'); ?>

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

			/* Assure la complétion des libellé de lieux */
			$("#location-immersion").focus(function() {
				$("#location-immersion").val("");
				$("#locationpath-immersion").val("");
			});
			$("#location-immersion").complete({
				call: "/ws/ws_locationcompletion.php?v=1.50&adistance=0&showdepartment=0&locationpath=<?php _T(is_array($restrictionCompletion)?implode('|',$restrictionCompletion):$restrictionCompletion);?>&q=%s",
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

			// Identifiant PE
//			$("#statut-de").click(function() {
//				$("#identifiant-block").slideToggle();
//				$("#identifiant").prop("required",$("#statut-de").prop("checked"));
//			});

			track(<?php _JS(($errors?'IMMERSION ERREURS':'IMMERSION'). ' '.$departementZipcode);?>);
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

<style>
	.la-bonne-formation .page-immersion .titre-padding {
		margin-top: 20px;
		margin-bottom: 20px;
	}
	.la-bonne-formation .page-immersion .block-avancee {
		margin-top: 1em;
		margin-bottom: 1em;
		//position: relative;
	}
	.la-bonne-formation #form-immersion .form-group {
		margin-top: 1.5em;
	}
	.la-bonne-formation #form-immersion label {font-weight:450;}
	.la-bonne-formation #form-immersion input {border: 1px solid;}
	.la-bonne-formation #form-immersion select {
	    //margin-top: .5em;
	    //margin-bottom: .5em;
	    width: 100%;
	    max-width: 35em;
	    -webkit-appearance: menulist;
	}
	.la-bonne-formation  #form-immersion .form-control {
		border-color: #1BD2A4;
		padding: 20px 10px;
		width: 100%;
	}
	.la-bonne-formation  #form-immersion .form-control-50 {
		border-color: #1BD2A4;
		padding: 20px 10px;
		width: 50%;
	}
	.la-bonne-formation  #form-immersion .form-control-select {
		width:auto;
		border-color:#1BD2A4;
		padding:0px 10px;
	}


	ul.public {
		list-style-type: disc;
		margin-left: 0;
		padding-left: 0;
		line-height:1em;
	}
	ul.public>li {
		margin-left: 1em;
	}
	.pastilles {
		margin: 0;
		padding: 0;
		list-style-type: none;
	}

	.pastilles li {
		counter-increment: step-counter;
		margin-bottom: 10px;
		display:inline-block;
	}

	.pastilles li::before {
		content: counter(step-counter);
		margin-right: 5px;
		font-size: 100%;
		font-family:roboto,serif;
		background-color: #4192EF;
		color: white;
		font-weight: bold;
		padding: 3px 8px;
		border-radius: 50%;
		width: 2em;
		height: 2em;
		line-height: 2em;
		text-align: center;
	}
</style>

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
	<div class="row page-immersion">
		<input type="hidden" id="backtitle" value="<?php _H($backTitle); ?>"/>

		<!-- Colonne principale -->
		<div class="col-md-8 col-sm-7 col-xs-12">
			<div class="col-md-12">
				<div class="row" style="margin:auto;">
					<!-- Immersion -->

					<h2 style="margin:auto;text-align:center;margin-top:20px;">Je souhaite découvrir un métier auprès d'un professionnel.</h2>

					<h3 style="color:white;background-color:#3F7AC5;padding:20px;margin-bottom:20px;text-align:center;">Remplir ce formulaire pour obtenir des coordonnées d'entreprises.</h3>

					<form id="form-immersion" name="immersion" action="<?php $this->rw('/immersion.php',array('criteria'=>$criteria));?>" method="post" style="margin-bottom:20px;">
						<?php _T($form->getTag('formacode')); ?>

						<div class="form-group<?php _T((isset($errors['rome'])?' error':''));?>">
							<label for="metier">Quel métier voulez-vous découvrir ?</label>
							<?php _T($form->getTag('rome')->attr(['class'=>'form-control form-control-select input-lg']));?>
						</div>

						<div class="form-group<?php _T((isset($errors['locationpath'])?' error':''));?>">
							<label for="location-immersion" >Où voulez-vous faire votre essai (<?php _T($departementLabel); ?> uniquement) ?</label>
							<div>
								<?php _T($form->getTag('location-immersion','location-immersion')->attr(['placeholder'=> $placeholder,'class'=>'form-control'.(isset($errors['locationpath'])?' error':''),'required'=>'required']));?>
								<?php _T($form->getTag('locationpath-immersion'));?>
							</div>
						</div>

						<div><?php _T($form->getTag('etape'));?></div>
<!--
						<div class="form-group<?php _T((isset($errors['debut'])?' error':''));?>">
							<label>Quand voulez-vous commencer ?</label>
							<?php //_T($form->getTag('debut')->attr('placeholder','JJ/MM/AAAA')->attr(['pattern'=>'\d{1,2}/\d{1,2}/\d{4}','required'=>'required','class'=>'date form-control']));?>
						</div>
-->
<!--
						<div class="form-group<?php _T((isset($errors['duree'])?' error':''));?>" style="width:100%;">
							<div>Durée souhaitée de l'essai ?</div>
							
							<div class="row">
								<div class="col-md-4 pull-left">
									<?php //_T($form->getTag('duree-1')->attr('class','sr-only'));?>
									<label class="radio-inline" for="duree-1">1 à 5 jours</label>
								</div>
								<div class="col-md-4 text-center">
									<?php //_T($form->getTag('duree-2')->attr('class','sr-only'));?>
									<label class="radio-inline" for="duree-2">6 à 10 jours</label>
								</div>
								<div class="col-md-4 pull-right">
									<?php //_T($form->getTag('duree-3')->attr('class','sr-only'));?>
									<label class="radio-inline pull-right" for="duree-3">plus de 10 jours</label>
								</div>
							</div>

						</div>
-->

						<div class="form-group">
							<label for="statut">Votre statut (obligatoire)</label>
							<fieldset id="statut" class="<?php _T((isset($errors['statut'])?'error':''));?>">
								<?php _T($form->getTag('statut-de')->attr('class','sr-only'));?>
								<label for="statut-de" style="margin-right:20px;">Demandeur d'emploi</label>
								<?php _T($form->getTag('statut-ml')->attr('class','sr-only'));?>
								<label for="statut-ml" style="margin-right:20px;">Mission locale</label>
								<?php _T($form->getTag('statut-cap')->attr('class','sr-only'));?>
								<label for="statut-cap" style="margin-right:20px;">CAP EMPLOI</label>
								<?php _T($form->getTag('statut-rsa')->attr('class','sr-only'));?>
								<label for="statut-rsa">Allocataire RSA</label>
							</fieldset>
						</div>

						<div id="identifiant-block" class="form-group" style="display:none;">
								<label for="identifiant">Identifiant Pôle emploi</label>
								<?php //_T($form->getTag('identifiant')->attr(['placeholder'=> '7 chiffres et 1 lettre, ou 8 chiffres','pattern'=>'[a-zA-Z0-9]+','class'=>'form-control form-control-50'.(isset($errors['identifiant'])?' error':'')]));?>
						</div>

						<div class="form-group<?php _T((isset($errors['nom'])?' error':''));?>">
							<label>Votre nom</label>
								<?php _T($form->getTag('nom')->attr(['class'=>'form-control']));?>
						</div>
						<div class="form-group<?php _T((isset($errors['prenom'])?' error':''));?>">
							<label>Votre prénom</label>
								<?php _T($form->getTag('prenom')->attr(['class'=>'form-control']));?>
						</div>
<!--
						<div class="form-group">
							<label>E-mail</label>
								<?php //_T($form->getTag('email')->attr(['class'=>'form-control'.(isset($errors['email'])?' error':'')]));?>
						</div>
-->
						<div class="text-center" style="margin-top:1.5em;">
							<button type="submit" class="button">Envoyer</button>
						</div>
					</form>
				</div> <!-- <div class="row" style="margin:auto;"> -->
			</div> <!-- <div class="col-md-12">  -->
			<!-- Immersion -->
		</div> <!-- <div class="col-md-9 col-sm-8 col-xs-12"> -->

		<!-- Colonne gauche-->
		<div class="col-md-4 col-sm-5 col-xs-12 block-video titre-padding">
			<h3 style="color:black;padding:20px;margin-bottom:20px;text-align:center;">L'immersion, c'est quoi ?</h3>
			<div class="embed-responsive embed-responsive-16by9" style="margin-bottom:20px;">
			<iframe class="embed-responsive-item" width="480" height="270" src="https://www.powtoon.com/embed/eDM4zMye6j7/" frameborder="0"></iframe>
			</div>
			<p>L'immersion est possible <strong>uniquement</strong> si vous êtes :</p>
			<ul class="public">
			<li>demandeur d'emploi (y compris salarié en contrat aidé ou en insertion économique)</li>
			<li>suivi par une Mission Locale</li>
			<li>suivi par Cap Emploi</li>
			<li>allocataire RSA</li>
			</ul>
		</div>
	</div> <!-- <div class="row resultat-formations"> -->
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
