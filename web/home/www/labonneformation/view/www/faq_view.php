<?php _BEGINBLOCK('title'); ?>Foire aux questions (FAQ) | <?php _ENDBLOCK('title'); ?>

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
					<h1>Foire aux questions (FAQ)</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h2>A quoi sert ce site&nbsp;?</h2>
					<p>&laquo;&nbsp;La Bonne Formation&nbsp;&raquo; vous permet de trouver une formation, de repérer son efficacité en terme de retour à l’emploi et d’identifier les solutions possibles de financement de cette formation en fonction de votre profil. Le site vous guide ensuite vers les formalités à remplir.</p>

					<h2>Quelles sont les formations disponibles sur &laquo;&nbsp;La Bonne Formation&nbsp;&raquo;&nbsp;?</h2>
					<p>Les informations sont collectées auprès des organismes de formations, des financeurs, et mises à jour, indexées , quotidiennement par chacun des Carif-Oref. En effet, informer sur les formations professionnelles continues dispensées en région est une des missions des Carif-Oref. Le site présente donc les organismes et les actions de formations référencés par les CARIF-OREF et accessibles sur le site <a href="http://www.intercariforef.org/reseau/" target="_blank">www.intercariforef.org/reseau/</a>.</p>
					<?php if(CONTACT_MAIL): ?><p>Si vous constatez que votre organisme et/ou votre action de formation ne figure pas sur le site, vous pouvez nous en faire part par <a href="mailto:<?php _M(EMAIL_CONTACT,array('subject'=>'Mon organisme ou ma formation ne figure pas sur votre site'));?>">courriel</a> et directement mettre à jour les informations auprès du CARIF concerné.</p><?php endif ?>

					<h2>Quels sont les dispositifs de financement qui sont disponibles et étudiés sur &laquo;&nbsp;La Bonne Formation&nbsp;&raquo;&nbsp;?</h2>
					<p>&laquo;&nbsp;la Bonne Formation&nbsp;&raquo; intègre dans son moteur de recherche la très grande majorité des règles nationales et régionales de financement de formation pour les demandeurs d’emploi.</p>

					<h2>Comment est mesurée l’efficacité des formations&nbsp;?</h2>
					<p>Les formations sont triées par efficacité sur le marché du travail. L'outil analyse les données relatives aux stagiaires ayant déjà suivi les formations visées.</p>
					<p>Précisément, il <?php _T(PARAM_DEFRETOURALEMPLOI);?></p>
					<?php if(CONTACT_MAIL): ?><p>Si vous êtes organisme de formation et que vous trouvez ce résultat surprenant, vous pouvez nous contacter par <a href="mailto:<?php _M(EMAIL_CONTACT,array('subject'=>'L\'efficacité que vous présentez pour mon organisme me semble surprenante'));?>">courriel</a>.</p><?php endif ?>

					<h2>Peut-on réaliser les démarches en ligne jusqu’au bout&nbsp;?</h2>
					<p>Non. &laquo;&nbsp;La Bonne Formation&nbsp;&raquo; vous propose d’abord d’avoir accès à des sessions de formation correspondant à vos recherches. Mais le site ne propose pas de vous inscrire en ligne, cette démarche se faisant auprès de l'organisme de formation.</p>
					<p>D’où l'intérêt de faire les démarches pour valider votre projet de formation car celles-ci vous serviront lors de la sélection de l’organisme de formation.</p>

					<h2>Comment obtenir les financements&nbsp;?</h2>
					<p>&laquo;&nbsp;La Bonne Formation&nbsp;&raquo; vous propose également une simulation très précise des financements que vous pouvez solliciter, ce en fonction à la fois, des caractéristiques de votre profil que vous avez saisies et de la formation que vous avez sélectionnée.</p>
					<p>Pour autant, remplir les conditions ne signifie pas nécessairement obtenir ce financement. Car il faut être retenu par l'organisme de formation et le financeur doit donner son accord pour le financement (en fonction de ses priorités, de son bugdet...).</p>
					<p>Le site vous aide ensuite à entamer les démarches en vous indiquant précisément auprès de quel organisme et comment constituer une demande de financement.</p>

					<h2>Pour rechercher les financement possibles, on me demande des informations très personnelles. Que faites-vous de ces informations ?</h2>
					<p>&laquo;&nbsp;La Bonne Formation&nbsp;&raquo; ne stocke aucune information personnelle. Lorsque le site vous demande vos date de naissance, type d’allocations et montants, cela lui permet de mesurer si vous remplissez les conditions pour chacun des financements possibles.</p>
					<p>Cela permet aussi de vous indiquer précisément si la formation visée est totalement ou partiellement finançable par type de financement ainsi que le montant de votre rémunération pendant la formation.</p>
					<p>Aucune de ces informations n’est conservée.</p>

					<h2>Je suis un salarié, ai-je intérêt à utiliser &laquo;&nbsp;La Bonne Formation&nbsp;&raquo;&nbsp;?</h2>
					<p>Actuellement &laquo;&nbsp;La Bonne Formation&nbsp;&raquo; met à disposition  la base de données Carif-Oref sur l'offre de formation professionnelle continue en France, qui recense les actions de formation mises en place pour les demandeurs d'emploi et en fonction des régions, l'offre de formation en direction des salariés.</p>
					<p>Pour tout savoir sur les offres de formation pour les salariés et comment les financer, rapprochez-vous de votre employeur et de votre OPCA.</p>

					<h2>Est-ce que La Bonne Formation peut m’aider dans toutes mes démarches&nbsp;?</h2>
					<p>Le site propose une simplification majeure pour accéder à une formation correspondant à votre profil. Néanmoins, il ne se substitue pas à l’appui que vous pouvez obtenir auprès d’un Conseiller en Évolution Professionnelle (CEP).</p>
					<p>Celui-ci vous permettra d’analyser votre situation professionnelle, de décider de la poursuite ou non de vos démarches. Et également de formaliser et mettre en œuvre votre projet d’évolution professionnelle.</p>
					<p>Les opérateurs du CEP sont Pôle emploi, Cap emploi, l’Association pour l'emploi des cadres (APEC), les Missions locales, les Organismes Paritaires Agréés au titre du Congé Individuel de Formation (OPACIF dont les FONGECIF).</p>

					<!--<h2>A quoi sert le bouton &laquo;&nbsp;nous contacter&nbsp;&raquo;, situé en bas de page ?</h2>
					Ce bouton sert à contacter directement l'équipe de La Bonne Formation pour signaler une erreur, un dysfonctionnement ou poser une question sur le mode de fonctionnement de La Bonne Formation. Ce bouton n'est pas un moyen de communiquer avec les organismes de formation.

					<h2>Comment signaler une erreur ?</h2>
					Vous pouvez nous signaler une erreur en nous envoyant un mail au moyen du bouton &laquo;&nbsp;nous contacter&nbsp;&raquo;. Merci de copier l'URL de la page qui comporte l'erreur que vous avez repérée.-->

					<h2>En quoi La Bonne Formation est utile si on doit de toute façon faire valider son projet de formation par Pôle emploi pour obtenir une aide financière ?</h2>

					La Bonne Formation permet de repérer des actions de formation qui peuvent vous intéresser et de savoir également si cette formation peut être efficace pour trouver ensuite un emploi et si elle peut être financée. Vous pourrez ainsi ensuite échanger avec votre conseiller sur la base d'un projet déjà solide.
					Pour cela :
					<ol>
						<li>Consulter les offres de formation sur La Bonne Formation et repérer les organismes qui dispensent les formations ciblées en précisant le lieu de formation souhaitée </li>
						<li>Consulter les performances de retour à l'emploi affichées pour la ou les formations que vous avez sélectionnées. Contacter des employeurs pour vérifier si la formation que vous voulez suivre est reconnue. Vous avez accès, pour chaque formation, à des offres d'emploi ainsi qu'à des employeurs susceptibles d'embaucher.</li>
						<li>Vérifier le dispositif de financement possible en cliquant sur &laquo;&nbsp;connaître mes possibilités de financement&nbsp;&raquo;</li>
						<li>Contacter votre conseiller emploi et présenter lui les résultats suggérés par La Bonne Formation.</li>
					</ol>

					<h2>Comment adresser sa candidature à un organisme de formation ?</h2>
					Vous devez directement contacter l'organisme de formation, dont les coordonnées sont accessibles sur la page de détail de la formation que vous avez repérée.
					Ne pas utiliser le bouton &laquo;&nbsp;nous contacter&nbsp;&raquo; en bas de page.

					<h2>Pourquoi proposer des formations déjà débutées ?</h2>
					Nous avons fait le choix de donner accès aux formations en cours pour permettre de contacter ces organismes de formation qui peuvent avoir encore des places disponibles ou peuvent proposer une nouvelle session.

					<h2>Pourquoi certaines formations n'apparaissent pas sur votre site ?</h2>
					<ol>
						<li>La Bonne Formation peut ne pas trouver d'actions de formation parce que les organismes de formation n'ont pas encore diffusé ces offres de formation à leur base de données régionales qui recense toute l'offre de formation et qui alimente La Bonne Formation.Essayer d'élargir géographiquement votre recherche.</li>
						<li>Il se peut que le moteur de recherche de La Bonne Formation n'arrive pas à interpréter votre requête. Dans ce cas, essayer avec une autre orthographe ou en utilisant un autre terme. Par exemple, si aucun résultat ne remonte pour &laquo;&nbsp;expertcomptable&nbsp;&raquo;, essayer de saisir &laquo;&nbsp;expert comptable&nbsp;&raquo; ou expert-comptable".</li>
					</ol>

					<h2>J'ai trouvé une formation qui correspond à mes attentes : la prochaine session n'est pas indiquée. Comment faire ?</h2>
					Si la prochaine session de cette formation n'est pas indiquée, contactez directement l'organisme de formation qui vous indiquera si une prochaine session est programmée (et donc bientôt publiée sur La Bonne Formation) ou non.
					Cela vous permettra d'organiser votre recherche d'emploi et/ou de stage, voire de repérer d'autres formations équivalentes, si l'organisme de formation ne dispense plus cette formation

					<h2>Pourquoi les formations d'un organisme n'apparaissent pas sur LBF alors qu'elles le sont bien sur l'intercarif ?</h2>
					Effectivement un organisme peut être bien référencé sur l'interactif sans que pour autant, les sessions de formation qu'il propose apparaissent sur La Bonne Formation
					En effet, notre règle de gestion des offres de formation sélectionne les sessions à venir ou les sessions ayant débuté il y a moins d'un mois. Nous n'affichons pas ni les formations antérieures ni les formations sans dates de sessions.

					<h2>Plusieurs formations différentes peuvent être référencées par La Bonne Formation dans des domaines similaires. Laquelle choisir ?</h2>
					Il faut d'abord bien définir votre projet de reconversion.
					Sollicitez un rendez-vous auprès de votre conseiller emploi pour construire avec lui ce projet.
					Les formations que vous avez repérées ne forment pas aux mêmes métiers et ne ciblent pas les mêmes filières. Vous pouvez affiner votre projet en demandant à faire une ou plusieurs immersions professionnelles pour vous rendre compte de la réalité du travail dans le secteur que vous ciblez.
					Vérifiez également votre capacité financière à pouvoir suivre une formation loin de votre domicile. Des formations peuvent être financées par la Région et donc gratuites pour un demandeur d'emploi mais il vous faudrait néanmoins financer votre logement et votre transport jusqu'au lieu de formation.

					<h2>Comment sélectionner une action de formation parmi toutes celles qui sont proposées sur La Bonne Formation dans un même domaine ?</h2>
					
					<p>Il convient d'effectuer les démarches suivantes :</p>
					<ol>
						<li>Consulter les offres de formation sur La Bonne Formation, bien lire le descriptif, tenir compte de pré-requis éventuels et vérifier le type de validation obtenu à l'issue des formations. Le lieu de la formation peut également être un critère de choix.</li>
						<li>Prendre connaissance des performances de la formation en termes de retour à l'emploi</li>
						<li>Contacter des employeurs (cf. les offres d'emploi publiées et et les employeurs ciblés sur La Bonne Formation) pour valider la pertinence d'une éventuelle formation</li>
						<li>Contacter ensuite votre conseiller emploi pour étudier votre projet de formation et les possibilités de financement</li>
					</ol>

					<h2>Est-ce que Pôle emploi peut aider les personnes en reconversion en aidant financièrement à passer des formations si ils n'ont pas cotisé suffisamment ?</h2>
					L'aide de Pôle emploi n'est pas subordonnée à la cotisation,ni au fait donc de percevoir une allocation chômage. Elle est fonction et de la pertinence du projet professionnel et du besoin validé par un conseiller emploi de formation.
					Un certain nombre de formations sont financées par Pôle emploi ou les Conseils Régionaux pour les demandeurs d'emploi inscrits à Pôle emploi. D'autres peuvent être prises en charge individuellement par Pole emploi. Autrement dit,la prise en charge financière est à la fois en fonction de la situation individuelle et de la formation visée.

					<h2>Comment est calculé le niveau de retour à l'emploi ?</h2>
					C'est l'efficacité de se former dans tel ou tel domaine que nous évaluons et non l'efficacité de la formation délivrée par tel ou tel organisme.
					Le niveau de retour à l'emploi mesure la part des stagiaires inscrits à Pôle emploi qui, dans les 6 mois suivant la fin de chaque formation identifiée par le même formacode, ont retrouvé un emploi salarié de 1 mois et plus.
					Il s'agit donc bien du domaine de formation qui est évalué , et ce à l'aune de la reprise d'emploi des stagiaires inscrits à Pole emploi tout d'abord, et ensuite de la reprise d'un emploi salarié ( puisque que nous utilisons les données DPAE). Pour les secteurs spécifiques qui recrutent hors contrat de travail, nous cherchons actuellement un moyen d'intégrer les reprises d'emploi non salarié dans nos calculs.

					Un système de jauge a été privilégié par rapport à des données chiffrées qui, en fonction du nombre de formations effectuées par domaine, pourraient donner lieu à des interprétations biaisées.
					Nous travaillons actuellement à une évolution pour afficher également des données par organisme de formation.


					<h2>Comment faire pour que des actions de formations apparaissent sur La Bonne Formation ou pour modifier des informations ?</h2>
					Les informations concernant les actions de formation publiées sur notre site sont issues du catalogue des formations référencés par les CARIF-OREF et accessibles sur le site <a href="http://www.intercariforef.org" target="_blank">www.intercariforef.org/reseau/</a>.
					Pour intégrer des actions à celles déjà présentes sur La Bonne Formation ou modifier des informations, nous vous invitons à prendre contact avec l'équipe du CARIF de votre région pour connaître leurs modalités de référencement. La base enrichie du CARIF régional alimentera la base nationale InterCariforef ainsi que La Bonne Formation

					<h2> En tant qu'organisme de formation, comment s'assurer que Pôle emploi financera bien la formation suivie par un demandeur d'emploi ?</h2>
					L’aide au financement d’une formation d’un demandeur d’emploi n’est pas accordée automatiquement par Pôle emploi.
					Il faut en premier lieu qu’un projet de formation ait été clairement formulé par un demandeur d’emploi en cohérence avec son projet professionnel. Dans un 2e temps, dès lors que le projet de formation est validé, le demandeur d’emploi peut demander à Pôle emploi une aide individuelle financière à la formation ou participer à une action de formation collective achetée soit par une Région (dont c’est la compétence), soit par Pôle emploi.
					Dans le cas d’une aide individuelle à la formation (on parle d’AIF), l'organisme de formation doit remplir le formulaire "AIF" dématérialisé et le transmettre par l'intermédiaire du service Kairos à Pôle emploi. Le refus ou l'accord sera notifié à l'organisme de formation par Kairos.
					L’accord au financement est soumis à plusieurs conditions, dont la capacité de cette formation à permettre un retour rapide à l’emploi, le référencement de l’organisme de formation dans une des bases de données « qualité » et le référencement de l'action de formation dans la base CARIF de votre région.
					Le crédit CPF du demandeur d'emploi peut être mobilisé pour contribuer partiellement ou totalement à la couverture de frais de formation.

					<h2>La Bonne Formation permet-elle à un salarié de trouver une formation et de savoir comment la financer ?</h2>
					Le site La Bonne Formation recense les dispositifs de financement pour les demandeurs d’emploi et pour les salariés de droit privé.
					La Bonne Formation permet, quel que soit le statut, de consulter les offres de formation sur La Bonne Formation et de filtrer des offres de formation en fonction du statut de l'utilisateur (demandeurs d'emploi ou salariés).
				
				</div>
			</div>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
