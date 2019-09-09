<?php _BEGINBLOCK('title'); ?>Immersion professionnelle | <?php _H($ad['title']);?><?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('description'); ?>
		Immersion professionnelle
<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('script'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
	<?php $asset->add('js',array('https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.js','/js/result.js')); ?>
	<?php $asset->add('js',array('/js/jquery/jquery.plugin.datepicker.js')); ?>

	<script>
		$(document).ready(function() {
			pageview('/immersion/_landing');
			track(<?php _JS('IMMERSION LANDING'. ' '.$departementZipcode);?>);

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
			/*$("#search_immersion").keyup(function(key) {
				if(key.which!=13) $("#code_immersion").val("");
				//key.preventDefault();
			});
			 */
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
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('css'); ?>
	<link href="https://api.mapbox.com/mapbox.js/v2.2.3/mapbox.css" rel="stylesheet"/>
	<?php $asset->add('css',array('/css/immersion.less')); ?>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
	<?php if($searchCorrected):?><link rel="canonical" href="<?php $this->rw('/result.php',array('criteria'=>$criteria));?>"/><?php endif ?>

<style>
	.la-bonne-formation .page-immersion .titre-padding {
		margin-top: 20px;
		margin-bottom: 20px;
		text-align:center;
	}
	.video-container {
		float: none;
		clear: both;
		width: 100%;
		position: relative;
		padding-bottom: 56.25%;
		padding-top: 25px;
		height: 0;
	}
	.video-container iframe,
	.video-container object,
	.video-container embed {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
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

		<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<h1 class="titre-padding text-center">L'immersion professionnelle</h1>
				</div>
		</div> <!-- <div class="col-md-12 col-sm-12 col-xs-12"> -->
		<!-- Ligne du haut -->
	</div>

<?php if (!$criteria['location']): ?>
	<div class="row">
		<div class="col-md-12 immersion-formulaire">
			<form id="form-immersion" name="immersion" action="/immersion.php" method="get" style="margin-bottom:20px;">
			<?php _T($form->getTag('etape'));?>

			<div class="row">
			<div class="col-md-4 cold-sm-4 col-xs-12">
				<div class="form-group">
					<label for="search_immersion">Métier recherché</label>
					<?php //_T($form->getTag('search_immersion')->attr(['class'=>'form-control']));?>
<input name="criteria[search_immersion]" id="search_immersion" type="text" class="form-control" placeholder="Coiffeur, cariste, ..." value="<?php _H($criteria['label']);?>"/>
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
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="form-group text-left">
				<button type="submit" id="validation" class="btn search"><i class="fa fa-search"></i>&nbsp;&nbsp;Rechercher</button>
				</div>
			</div>
			</div>

			</form>
		</div>
	</div>
<?php endif;?>

	<div class="row page-immersion">
		<div class="col-md-12 col-sm-12 col-xs-12 block-video">
				<h3 class="text-center">Avant de commencer une formation, testez ce métier quelques jours dans une entreprise.</h3>
<?php if ($criteria['location']): ?>
				<h3 class="text-center">Votre recherche : «&nbsp;<?php _H($label);?>&nbsp;» pour «&nbsp;<?php _H($lieu);?>&nbsp;».</h3>

				<div class="titre-padding"><a href="<?php $this->rw('/immersion.php',array('criteria'=>$criteria));?>?etape=2" class="btn bleu">Voir la liste des entreprises</a></div>
<?php endif;?>
				<div class="video-container" style="margin-top:20px;margin-bottom:20px;">
					<iframe width="1188" height="668" src="https://www.youtube.com/embed/2Jy_K6o7lQA?rel=0&showinfo=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>

		</div> <!-- <div class="col-md-12">  -->
	</div> <!-- <div class="row resultat-formations"> -->
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
