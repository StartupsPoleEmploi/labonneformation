<?php
	//$nbAvis: ex "35 avis"
	//$noteMoyenne: "35 avis <note moyenne affichée par étoiles>"
	//$pseudo: ex "Josephine"
	//$avis: ex "Super formation ! Riche en informations avec beaucoup de mises en situation nous permettant d’appréhender à minima les différentes situations"
	//$reponseOrganisme: ex: "Riche en informations avec beaucoup de mises en situation nous permettant d’appréhender à minima les différentes situations"
	//$reponsePoleEmploi: ex: "Riche en informations avec beaucoup de mises en situation nous permettant d’appréhender à minima les différentes situations"
	//$noteGenerale: ex "Avis général <note générale affichée par étoiles>"

	if(!isset($caroussel_id)) $caroussel_id='tous-les-block-avis';
?>
<?php _BEGINBLOCK('css'); ?>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>
	<?php $asset->add('css',array('/css/inc/anotea.less')); ?> 
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
	<?php $asset->add('js','/js/inc/anotea.js'); ?>
	<script>
		$(document).ready(function() {
			window.location.hash="#anotea"; // hotjar
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php function listeAvis($landscape) { ?>
	<div class="avis-general">
		<h4 class="titre" data-target=".avis-liste" data-parent=".avis-general"><!--Avis général -->Notes<span class="fa fa-chevron-down"></span></h4>
		<?php
			$notes=array(
				"Accueil"=>"champ-accueil",
				"Contenu"=>"champ-lesuivipedagogique",
				"Formateurs"=>"champ-equipedeformateurs",
				"Matériels"=>"champ-lesmoyensmateriels",
				"Accompagnement"=>"champ-recommandations"
				);
		?>
		<div class="avis-liste<?php _H($landscape?'':/*' collapse'*/'');?>">
			<?php foreach($notes as $label=>$target): ?>
				<div class="row">
					<div class="col-md-7 col-xs-7 label-avis"><?php _H($label);?></div>
					<div class="col-md-5 col-xs-5 note-avis text-right <?php _H($target);?>"><span>&nbsp;</span><span class="etoile">&nbsp;</span></div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
	<div class="avis-details">
		<div>
			<div class="champ-titre">{titre}</div>
			Avis émis le <span class="champ-date-publication">{date publication}</span><br/>
			Formation du <span class="champ-date-session">{date session}</span><br/>
		</div>
	</div>
<?php } ?>

<div id="template-avis" style="display:none;">
	<span class="titre" data-target=".block-avis">
		<span class="champ-notemoyenne note">&nbsp;</span> <span class="champ-nbavis">{nbAvis}</span> avis
		<?php if($collapse!==false):?><span class="fa fa-chevron-down visible-xs"></span><?php endif ?>
	</span>

	<div id="<?php _H($caroussel_id); ?>">
	</div>
	<?php if($domaine): ?>
		<?php $link=$this->rewrite('/result.php',array('criteria'=>array('domaine'=>$domaine['data'], 'orgaid'=> $orgaid)));?>

		<div class="bouton-plus-d-avis" style="display: none;">
			<a class="btn secondaire plus-d-avis" href="<?php _T($link);?>" onclick="track('CLIC PLUS AVIS ANOTEA');">Plus d'avis</a>
		</div>
	<?php endif ?>
	<div id="block-avis" style="display: none;">
		<div class="padding-caroussel">
			<div class="block-avis<?php _T($collapse!==false?' collapse-xs':'');?>">
				<div class="row">
					<div class="col-md-<?php _H($landscape?'8':'12');?>">
						<div class="avis">
							<h3><span class="champ-global note">&nbsp;</span> par <span class="champ-pseudo">{pseudo}</span></h3>
							<p>
								<span class="champ-commentaire">{commentaire}</span>
								<button class="plus" data-toggle="collapse" data-target=".reponses">... Plus</button>
							</p>
						</div>
						<div class="reponses collapse">
							<div class="reponse organisme">
								<h3>Organisme de formation</h3>
								<p class="champ-commentaireorganisme">
									{commentaireOrganisme}
								</p>
							</div>
							<div class="reponse pole-emploi">
								<h3>Pôle emploi</h3>
								<p class="champ-commentairepoleemploi">
									{commentairePoleEmploi}
								</p>
							</div>
							<div class="contact" style="display: none;">
								<p>Signaler</p>
							</div>
						</div>
					</div>
					<?php if($landscape):?>
						<div class="row">
							<div class="col-md-4">
								<?php listeAvis($landscape); ?>
							</div>
						</div>
					<?php endif ?>
				</div>
				<?php if(!$landscape):?>
					<div class="row">
						<div class="col-md-12">
							<?php listeAvis($landscape); ?>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>
