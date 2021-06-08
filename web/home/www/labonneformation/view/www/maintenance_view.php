<?php _BEGINBLOCK('title'); ?>Site en maintenance<?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('css'); ?>
	<style>
		.block {
			color:#94C7F3;
			min-height:100%;
			height:100%;
			display:inline-block;
		}
		.evol {
			color:#003173;
		}
	</style>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="block">
				<h1>
					Le site est en maintenance et devrait réouvrire d'ici 10 min.
				</h1>
				<span class="evol">
					Evolution en cours de déploiement :<br/>
					- ajout de la liste des dates de sessions sur les pages de détail des annonces.<br/>
				</span>
				<br/>
			</div>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>