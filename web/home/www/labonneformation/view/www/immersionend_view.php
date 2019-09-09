<?php _BEGINBLOCK('title'); ?>Immersion professionnelle, entreprises à contacter<?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('description'); ?>Immersion professionnelle<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('script'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
	<?php $asset->add('js',array('/js/result.js')); ?>

	<script>
		$(document).ready(function() {
			track(<?php _JS('IMMERSION MERCI');?>);
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
	<style>
		.table tbody tr td {
			border-color: #3F7AC5;
			border-width:2px;
		}
		.immersion ul {
			list-style-type:disc;
		}
		.immersion ul li {
			padding:0 0;
		}
		.panel-default > .panel-heading {
			background-color: #e7eff9;
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
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-12">
				<div class="row" style="margin:auto;">
					<!-- Immersion -->
					<h2 style="margin:auto;text-align:center;margin-top:20px;margin-bottom:50px;">Merci !</h2>

					<p class="text-center">Nous allons rechercher des entreprises succeptibles de vous accueillir en immersion.<br/>Nous vous transmettrons au plus vite par email les résultats de notre recherche.</p>

					<p class="text-center" style="margin-top:30px;"><a href="<?php $this->rw('/detail.php',array('ad'=>$ad)); ?>" class="">Retour à la formation</a></p>

					<!-- Immersion -->
				</div>
			</div>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
