<?php _BEGINBLOCK('title'); ?>Accessibilité | <?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('description'); ?>
	A quoi sert ce site ? &laquo;&nbsp;La Bonne Formation&nbsp;&raquo; vous permet de trouver une formation, de repérer son efficacité en terme de retour à l’emploi et d’identifier les solutions possibles de financement de cette formation en fonction de votre profil. Le site vous guide ensuite vers les formalités à remplir.
<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/conditions.less')); ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<h1>Accessibilité </h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h2>Introduction :</h2>
					<p> L’article 47 de la loi n° 2005-102 du 11 février 2005 pour l’égalité des droits et des chances, la participation et la citoyenneté des personnes handicapées fait de l’accessibilité une exigence pour tous les services de communication publique en ligne de l’État, les collectivités territoriales et les établissements publics qui en dépendent. Il stipule que les informations diffusées par ces services doivent être accessibles à tous. </p>
					<p> Le référentiel général d’accessibilité pour les administrations (RGAA) rendra progressivement accessible l’ensemble des informations fournies par ces services.</p>
					<p> Le site <a href="/"><?php _H(DOMAIN)?></a> est en cours d’optimisation afin de le rendre conforme au <a href="https://references.modernisation.gouv.fr/referentiel/" target="_blank">RGAA v3</a>. La déclaration de conformité sera publiée ultérieurement. </p>
					
					<h2>Nos engagements :</h2>
					<ul>
						<li>Audit de mise en conformité (en cours) pour nous aider à détecter les potentiels oublis d'accessibilité</li>
						<li>Déclaration d'accessibilité (en cours) pour expliquer en toute transparence notre démarche</li>
						<li>Mise à jour de cette page pour vous tenir informé de notre progression</li>
					</ul>
					<p>Nos équipes ont ainsi travaillé sur les contrastes de couleur, la présentation et la structure de l’information ou la clarté des formulaires.</p>
					<p><b>Des améliorations vont être apportées régulièrement.</b></p>

					<h2>Améliorations et contact :</h2>
					<p>L'équipe &laquo;&nbsp;La Bonne Formation&nbsp;&raquo;&nbsp; reste à votre écoute et entière disposition, si vous souhaitez nous signaler le moindre défaut de conception.</p>
					<p>Vous pouvez nous aider à améliorer l’accessibilité du site en nous signalant les problèmes éventuels que vous rencontrez.</p>
					<br>
				</div>
				<?php if(CONTACT_MAIL):?>
				<div class="col-md-12">
					<a class="btn secondaire contacter" href="mailto:<?php _M(EMAIL_CONTACT,array('subject'=>'Améliorations accessibilité numérique'));?>">Contacter l'équipe la bonne formation</a>
				</div>
				<?php endif ?>
			</div>
		</div>
	</div> 
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
