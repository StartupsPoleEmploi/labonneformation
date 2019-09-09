<?php _BEGINBLOCK('title'); ?>Page non trouvée<?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/404.less')); ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="block">
				<h1>
					<span class="e404">404</span>
					<span class="error">error</span>
				</h1>
				<h2>Page non trouvée</h2>
				<br/>
			</div>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
