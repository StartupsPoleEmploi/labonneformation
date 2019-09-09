<?php _BEGINBLOCK('description'); ?>
	Accédez à toutes les formations de votre ville et de votre région triées par efficacité sur le marché du travail
<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('css'); ?>
	<!--<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>-->
	<?php $asset->add('css',array('/css/index.less')); ?> 
<?php _ENDBLOCK('css');?>

<?php _BEGINBLOCK('script'); ?>
<?php _ENDBLOCK('script');?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="row hidden-sm hidden-xs">
				<div class="col-md-12">
					<h1 class="titre-page">Tout pour réussir votre projet de formation</h1>
				</div>
			</div>
			<div class="row block-home">
				<div class="col-md-4 informations">
					<h2>Les financements et la rémunération</h2>
					<p class="block-info">
						Puis-je me faire financer ma formation&nbsp;?<br/>
						Combien vais-je toucher pendant cette période&nbsp;?
					</p>
				</div>
				<div class="col-md-4 avis">
					<h2>Les avis et commentaires des anciens stagiaires</h2>
					<p class="block-info">
						Qu'ont pensé les anciens stagiaires de la formation&nbsp;?
					</p>
				</div>
				<div class="col-md-4 visibilites">
					<h2>L’emploi après votre formation</h2>
					<p class="block-info">
						Quelles sont mes chances de retrouver un emploi après cette formation&nbsp;?<br/>
						Quelles sont les offres d’emploi en lien avec la formation&nbsp;?
					</p>
				</div>
			</div>


			<?php if(0): ?>
				<div class="row">
					<div class="col-lg-3 col-sm-6 block-explicatif">
						<div class="picto">
							<img src="/img/pictos/picto-valider.png" alt="Valider"/>
						</div>
						<div class="background">
							<img src="/img/backgrounds/image1home.jpg" alt="Profil"/>
						</div>
						<div class="block">
							<h2>Une formation adaptée à votre profil&nbsp;!</h2>
							<p class="block-info">
								Repérez en quelques clics les formations qui répondent à votre projet.<br/>
								Affinez votre recherche selon votre profil, votre mobilité, votre expérience...
							</p>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 block-explicatif">
						<div class="picto">
							<img src="/img/pictos/picto-contrat.png" alt="Contrat"/>
						</div>
						<div class="background">
							<img src="/img/backgrounds/image2home.jpg" alt="Marché du travail"/>
						</div>
						<div class="block">
							<h2>Une formation en phase avec le marché du travail&nbsp;!</h2>
							<p class="block-info">
								Accédez pour chaque formation aux taux de retour à l’emploi, aux offres d’emploi et aux entreprises qui recrutent.<br/>
								Contactez des employeurs éventuels avant de finaliser votre projet.
							</p>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 block-explicatif">
						<div class="picto">
							<img src="/img/pictos/picto-portefeuille.png" alt="Portefeuille"/>
						</div>
						<div class="background">
							<img src="/img/backgrounds/image3home.jpg" alt="Risques financiers"/>
						</div>
						<div class="block">
							<h2>Une formation sans risques financiers&nbsp;!</h2>
							<p class="block-info">
								Découvrez les financements possibles et votre éventuelle rémunération en fonction des caractéristiques de chaque formation et de votre profil.<br/>
								Informez-vous des premières démarches à entreprendre.
							</p>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 block-explicatif">
						<div class="picto">
							<img src="/img/pictos/picto-smiley.png" alt="Sourire"/>
						</div>
						<div class="background">
							<img src="/img/backgrounds/image4home-beta.jpg?1" alt="Avis des anciens stagiaires"/>
						</div>
						<div class="block">
							<h2>Un choix éclairé par l'avis des anciens stagiaires&nbsp;!</h2>
							<p class="block-info">
								Consultez les notes des anciens stagiaires sur les conditions de réalisation des formations qui vous intéressent.<br/>
								Repérez les avis sur les formations et organismes qui vous intéressent (expérimenté avec la Région Île-de-France).
							</p>
						</div>
					</div>
				</div>
			<?php endif ?>
		</div>
	</div>
	<div class="row" id="immersion-intro">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 text-center">
					<h2 class="immersion-title text-center">L'immersion professionnelle</h2>
					<h3 class="immersion-subtitle titre-page">Pour tester un métier avant<br/>de vous former</h3>
					<p class="immersion-description">Avant de vous lancer dans une formation, profitez d'une immersion en entreprise pour tester un métier quelques jours. Vérifiez que le métier vous convient et faites évaluer vos compétences par un professionnel.</p>
				</div>
			</div>

			<div class="row" id="immersion-etapes">
				<div class="col-lg-3 col-sm-6 text-center">
					<div class="block immersion">
						<h4>ETAPE 1</h4>
						<h4 class="sous-titre">TROUVEZ UNE ENTREPRISE</h4>

						<img src="/img/pictos/immersion-etape-1.png" alt="Etape 1"/>
						<p class="block-info immersion">
							Dans le métier que vous souhaitez près de chez vous.
						</p>
					</div>
				</div>

				<div class="col-lg-3 col-sm-6 text-center">
					<div class="block immersion">
						<h4>ETAPE 2</h4>
						<h4 class="sous-titre">CONTACTEZ L'ENTREPRISE</h4>
						<img src="/img/pictos/immersion-etape-2.png" alt="Etape 2"/>
						<p class="block-info immersion">
							Utilisez si besoin les conseils proposés.
						</p>
					</div>
				</div>

				<div class="col-lg-3 col-sm-6 text-center">
					<div class="block immersion">
						<h4>ETAPE 3</h4>
						<h4 class="sous-titre">TELECHARGEZ LA CONVENTION</h4>
						<img src="/img/pictos/immersion-etape-3.png" alt="Etape 3"/>
						<p class="block-info immersion">
							Complétez-la avec l'entreprise prête à vous accueillir.
						</p>
					</div>
				</div>

				<div class="col-lg-3 col-sm-6 text-center">
					<div class="block immersion">
						<h4>ETAPE 4</h4>
						<h4 class="sous-titre">CONFIRMEZ L'IMMERSION</h4>
						<img src="/img/pictos/immersion-etape-4.png" alt="Etape 4"/>
						<p class="block-info immersion">
							Transmettez la convention à votre conseiller pour validation.
						</p>
					</div>
				</div>
			</div>

			<div class="row" id="immersion-bouton-tester">
				<div class="col-md-12 text-center">
					<a href="<?php $this->rw('/immersion.php');?>" class="btn" onclick="track('IMMERSION HOME CTA');">Tester un métier</a>
				</div>
			</div>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>

<?php _BEGINBLOCK('sponsor'); ?>
<?php if(0): ?>
	<div class="row">
		<div class="col-md-6 powered-by">
			<div class="logo-pe">
				<p>
					Pôle Emploi innove et propose ce service pour permettre de trouver plus facilement des formations. La Bonne Formation est une startup interne de Pôle Emploi créée et développée par des conseillers. 
				</p>
			</div>
		</div>
		<div class="col-md-6 powered-by">
			<div class="logo-intercarif">
				<p>
					L’ensemble des formations sont référencées par notre partenaire le réseau des CARIF-OREF
				</p>
			</div>
		</div>
	</div>
<?php endif ?>
<?php _ENDBLOCK('sponsor'); ?>
<?php require_once('base_view.php'); ?>
