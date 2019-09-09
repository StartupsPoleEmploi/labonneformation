<?php
	function getList()
	{
		$list=array();
		$list['contratapprentissage']=array(
		      'pri'=>3,
		      'title'=>"Formation en alternance : contrat d'apprentissage",
		       'step'=>array("
		         1. Si vous êtes inscrit à Pôle emploi ou suivi par une Mission Locale ou Cap Emploi, informez votre conseiller de votre projet.<br/>
		         <br/>2. Contactez le centre de formation pour connaître leurs conditions précises d'inscription.<br/>
		         <br/>3. Recherchez aussitôt que possible une entreprise qui vous recrutera en alternance, en répondant aux offres d'emploi et en contactant les entreprises que La Bonne Formation vous recommande ou que vous pouvez trouver sur La Bonne Alternance (".Tools::urlize('https://labonnealternance.pole-emploi.fr').")<br/>
		       "),
		                    //"Contactez les employeurs susceptibles de vous embaucher en contrat d'apprentissage :<br/>
		                    // <ul><li>x offres d'emploi de &laquo;&nbsp;... &nbsp;&raquo;<br/>-&gt; dont x en contrat d'apprentissage</li>
		                    // <li>x employeurs sont susceptibles de recruter un &laquo;&nbsp; ... &nbsp;&raquo;.</li></ul>"),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>"http://travail-emploi.gouv.fr/formation-professionnelle/formation-en-alternance/contrat-apprentissage",
		      'descinfo'=>"Le contrat d'apprentissage permet de se former pour obtenir une qualification professionnelle tout en ayant une expérience en entreprise.<br/>
		      Les avantages :<br/>
		      - vous êtes rémunéré par l'entreprise ;<br/>
		      - la formation en centre est complétée par des périodes en entreprise",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>false,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Formation / emploi'
		      );

		$list['contratdepro']=array(
		      'pri'=>3,
		      'title'=>"Formation en alternance : Contrat de professionnalisation",
		      'step'=>array("
		         1. Si vous êtes inscrit à Pôle emploi ou suivi par une Mission Locale ou Cap Emploi, informez votre conseiller de votre projet.<br/>
		         <br/>2. Contactez le centre de formation pour connaître leurs conditions précises d'inscription.<br/>
		         <br/>3. Recherchez aussitôt que possible une entreprise qui vous recrutera en alternance, en répondant aux offres d'emploi et en contactant les entreprises que La Bonne Formation vous recommande ou que vous pouvez trouver sur La Bonne Alternance (".Tools::urlize('https://labonnealternance.pole-emploi.fr').")<br/>
		      "),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>"http://travail-emploi.gouv.fr/formation-professionnelle/formation-en-alternance/article/le-contrat-de-professionnalisation",
		      'descinfo'=>"Le contrat d'apprentissage permet de se former pour obtenir une qualification professionnelle tout en ayant une expérience en entreprise.<br/>
		      Les avantages :<br/>
		      - vous êtes rémunéré par l'entreprise ;<br/>
		      - la formation en centre est complétée par des périodes en entreprise",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>false,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Formation / emploi'
		      );

		$list['afprpoei']=array(
		      'pri'=>4,
		      'title'=>"Formations avant embauche : AFPR/POEI",
		      'step'=>array("
		        1. Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDI ou un CDD d'au moins 6 mois. Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 400h.<br/>
		        <br/>2. En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier votre plan de formation.
[OE cont=\"CDD\"]<br/>"),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>"dans la limte de 400h",
		      'info'=>"http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321",
		      'descinfo'=>"Ce dispositif vous permet de vous former en 400h maximum afin d'obtenir les compétences qui vous manquent, avant une embauche (en CDD d'au moins 6 mois ou en CDI 
ou en contrat en alternance d'au moins 12 mois).",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>false,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Formation / emploi'
		      );
		
		$list['poei']=array(
		      'pri'=>4,
		      'title'=>"Formations avant embauche : POEI",
		      'step'=>array("
		        1. Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDI ou un CDD d'au moins 12 mois. Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 400h.<br/>
		        <br/>2. En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier votre plan de formation.<br/>
[OE cont=\"CDI\"]"),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>"dans la limte de 400h",
		      'info'=>"http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321",
		      'descinfo'=>"Ce dispositif vous permet de vous former en 400h maximum afin d'obtenir les compétences qui vous manquent, avant une embauche (en CDD d'au moins 12 mois ou en CDI ou en contrat en alternance d'au moins 12 mois).",
		      'cost-plafond'=>3200,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>false,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Formation / emploi'
		      );

		$list['cifcdd']=array(
		      'pri'=>5,
		      'title'=>"CIF - CDD",
		      'step'=>array("
		        1. Retirez auprès de votre OPACIF (Fongecif, Uniformation, AFDAS, FAFSEA, UNIFAF)  un dossier de demande de prise en charge.<br/>
		        <br/>2. Vérifiez les délais pour faire votre demande.<br/>
		        <br/>3. Indiquez votre accord pour compléter, si besoin, le financement de la formation avec votre Compte Personnel de Formation.<br/>"),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"http://travail-emploi.gouv.fr/informations-pratiques,89/les-fiches-pratiques-du-droit-du,91/formation-professionnelle,118/le-conge-individuel-de-formation,1070.html",
		      'descinfo'=>"Le Congé Individuel de Formation (CIF) permet à une personne en CDD ou ayant achevé un CDD de suivre une formation, en bénéficiant d'une prise en charge totale des frais de formation et d'une rémunération par un OPACIF (Fongecif, Uniformation ...). La formation doit débuter au plus tard dans les 12 mois qui suivent la fin de votre dernier CDD.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>true,
		      'famille'=>'CIF'
		      );

		$list['cifcddjeune']=array(
		      'pri'=>5,
		      'title'=>"CIF - CDD jeunes",
		      'step'=>$list['cifcdd']['step'],
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"http://travail-emploi.gouv.fr/informations-pratiques,89/les-fiches-pratiques-du-droit-du,91/formation-professionnelle,118/le-conge-individuel-de-formation,1070.html",
		      'descinfo'=>$list['cifcdd']['descinfo'],
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>true,
		      'famille'=>'CIF'
		      );

		$list['cifinterim']=array(
		      'pri'=>5,
		      'title'=>"CIF - Interim",
		      'step'=>array("
		       1. Contactez le Fonds d'Assurance Formation du Travail Temporaire  (Faf-TT)  au 01 73 78 13 30 (du lundi au vendredi, de 9h à 18h) pour retirer un dossier de demande de CIF.<br/>
		       Votre agence Interim peut vous aider à effectuer la demande de CIF.<br/>
		       <br/>2. Vérifiez les délais pour faire votre demande.<br/>
		       <br/>3. Indiquez votre accord pour compléter, si besoin, le financement de la formation avec votre Compte Personnel de Formation."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      //'cost'=>"Formation totalement ou partiellement financée<br/>
		      //         coût pris en charge par le FAF.T sur une durée maximale de 1 an pour les formations à temps complet<br/>
		      //         et de 1 200 heures pour les formations à temps partiel (sur une période maximale de 24 mois) et dans la limite de 18 000 € HT et à 27,45 €/h HT et une partie du coût pédagogique reste à charge :<br/>
		      //         - si le salaire horaire brut total pris en charge est compris entre 2 et 3 fois le SMIC:l'équivalent de 5% de la rémunération totale prise en charge pendant le congé reste à charge<br/>
		      //         - si le salaire horaire brut total pris en charge est supérieur ou égal à 3 fois le SMIC:l'équivalent de 10% de la rémunération totale prise en charge pendant le congé reste à charge",
		      'info'=>"http://travail-emploi.gouv.fr/informations-pratiques,89/les-fiches-pratiques-du-droit-du,91/formation-professionnelle,118/le-conge-individuel-de-formation,1070.html",
		      'descinfo'=>"Le Congé Individuel de Formation (CIF) permet à tout salarié intérimaire ou ayant achevé une mission en intérim de suivre une formation, en bénéficiant d'une prise en charge totale des frais de formation et d'une rémunération par le FAFTT.<br/>La formation doit débuter au plus tard dans les 12 mois qui suivent la fin de votre dernière mission.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>true,
		      'famille'=>'CIF'
		      );

		$list['poleemploicollectif']=array(
		      'pri'=>2,
		      'title'=>"Action collective financée par Pôle emploi",
		      'step'=>array("Contactez votre conseiller emploi pour être retenu sur une des places financées par Pôle emploi"),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>"",
		      'descinfo'=>"Cette action de formation est financée par Pôle emploi, donc totalement prise en charge pour les demandeurs d'emploi.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Actions collectives'
		      );

		$list['agefiphcollectif']=array(
		      'pri'=>1,
		      'title'=>"Action collective financée par l'Agefiph",
		      'step'=>array("Contactez votre conseiller emploi ou le centre de formation pour être retenu sur une des places financées par l'Agefiph."),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>"https://www.agefiph.fr/",
		      'descinfo'=>"Cette action de formation est financée par l'Agefiph pour permettre aux travailleurs reconnus handicapés d'accéder à l'emploi.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Actions collectives'
		      );

		$list['actioncollectiveregion']=array(
		      'pri'=>1,
		      'title'=>"Action collective financée par la Région",
		      'step'=>array("Vérifiez auprès de votre conseiller et/ou de l'organisme de formation<br/>que vous remplissez les conditions pour effectuer cette formation."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"",
		      'descinfo'=>"Cette action de formation est financée, totalement ou partiellement, par votre Région.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>true,
		      'famille'=>'Actions collectives'
		      );

		$list['conseildepartementalcollectif']=array(
		      'pri'=>1,
		      'title'=>"Action collective financée par le Conseil départemental",
		      'step'=>array("Contactez votre conseiller emploi ou le centre de formation pour être retenu sur une des places financées par le Conseil Départemental."),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>"Même si Pôle emploi ne finance pas cette formation, votre conseiller peut valider votre projet de formation. Si vous êtes bénéficiaire d\'une allocation de recherche d\'emploi elle sera maintenue",
		      'descinfo'=>"Cette action de formation est financée par le Conseil Départemental.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Actions collectives'
		      );

		$list['etat']=array(
		      'pri'=>1,
		      'title'=>"Action financée par l'Etat",
		      'step'=>array("Contactez votre conseiller emploi ou le centre de formation pour être retenu sur une des places financées par l'Etat"),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>'',
		      'descinfo'=>"Cette action de formation est financée par l'Etat.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Actions collectives'
		      );

		$list['poecollective']=array(
		      'pri'=>1,
		      'title'=>"Préparation Opérationnelle à l'Emploi Collectif (POEC)",
		      'step'=>array("Pour effectuer cette formation, contactez votre conseiller emploi ou le centre de formation."),
		      'cost'=>"Formation totalement financée",
		      'cost-complement'=>null,
		      'info'=>"http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321 ",
		      'descinfo'=>"Dans le cadre de la Préparation opérationnelle à l'emploi collective, une branche professionnelle finance des formations adaptées aux besoins des entreprises. Ces formations d'une durée maximum de 400h sont totalement prises en charge.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>false,
		      'cumulable'=>false,
		      'reste-a-charge'=>false,
		      'famille'=>'Actions collectives'
		      );

		$list['finindividuel']=array(
		      'pri'=>'6.3',
		      'title'=>"Financement individuel par la Région",
		      'step'=>array("<span class=\"highlight\">Contactez un conseiller emploi</span> pour connaître les conditions de la mobilisation éventuelle d'une aide individuelle de la Région."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"http://www.conseil-general.com/regions/conseils-regionaux.htm",
		      'descinfo'=>"Chaque Région finance sous des conditions spécifiques certains types de formations individuelles pour certains demandeurs d'emplois.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['finindividuelpermisb']=array(
		      'pri'=>7,
		      'title'=>"Prise en charge Permis de conduire B",
		      'step'=>"Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.",
		      'descinfo'=>"Vous pouvez utiliser votre CPF pour financer tout ou partie de votre permis de conduire catégorie B.<br/>Pôle emploi peut éventuellement vous apporter un complément de financement.<br/>Votre auto-école doit impérativement vous présenter à l'examen de conduite au plus tard six mois après votre inscription.",
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['agefiph']=array(
		      'pri'=>'6.1',
		      'title'=>"Financement individuel de l'Agefiph",
		      'step'=>array("Contactez un conseiller emploi pour connaitre les conditions de la mobilisation éventuelle d'une aide individuelle de l'Agefiph."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"https://www.agefiph.fr",
		      'descinfo'=>"L'Agefiph peut financer partiellement ou totalement une formation individuelle permettant un accès à l'emploi.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['faj']=array(
		      'pri'=>'6.2',
		      'title'=>"Fonds d'Aide aux Jeunes (FAJ)",
		      'step'=>array("Contactez la Mission Locale pour connaitre les conditions de mobilisation de cette aide individuelle."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"Le Fonds d'Aide aux Jeunes (FAJ) est un dispositif départemental exceptionnel qui peut être mobilisé pour contribuer à financer une formation. Si aucune autre solution n'a été trouvée.",
		      'descinfo'=>"Le Fonds d'Aide aux Jeunes (FAJ) est un dispositif départemental de dernier recours destiné aux jeunes adultes en grande difficulté sociale. Il vise à favoriser leur insertion sociale et professionnelle. Dans ce cas, il peut être mobilisé pour participer au financement d'une formation.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['cpf']=array(
		      'pri'=>0,
		      'title'=>"Compte Personnel de Formation (CPF)",
		      'subtitle'=>Tools::urlize('http://www.moncompteformation.gouv.fr/mon-compte-personnel-de-formation/mes-droits/mon-compte-dheures/mes-heures-cpf','Inscrivez-vous ICI'),
		      'step'=>array("
		      1. Contactez votre conseiller référent emploi pour valider avec lui votre projet de formation.<br/>
		      <br/>2. Créez votre compte CPF sur le site ".Tools::urlize('http://moncompteformation.gouv.fr')."<br/>"),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"Si le montant couvert par le CPF est insuffisant, il peut être complété par vous-même ou, sous conditions, par d'autres dispositifs de financement disponibles. http://www.moncompteformation.gouv.fr",
		      'descinfo'=>"Le compte personnel de formation (CPF) permet à toute personne active d’acquérir des droits pour financer totalement ou partiellement une formation qualifiante.<br/>
		                   <br/>NB : dans le cas d'une formation financée par Pôle emploi ou la Région, il pourra vous être demandé de contribuer à son financement avec votre CPF.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'CPF'
		      );
		
		$list['aif']=array(
		      'pri'=>7,
		      'title'=>"Aide Individuelle à la Formation (AIF)",
		      'step'=>array("
		        1. Contactez un conseiller Pôle emploi pour connaitre les conditions de la mobilisation éventuelle d'une aide individuelle de Pole emploi dans votre région.<br/>
		        <br/>2. Votre projet de formation et son financement doivent être présentés bien en amont du début de la formation. (au plus tard 15 jours avant).<br/>
		        <br/>3. Ne démarrez pas votre formation tant que vous n'avez pas eu la confirmation de l'accord de la part de votre conseiller.<br/>"),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"L'AIF permet une prise en charge totale des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen …).<br/>L'AIF peut compléter des co-financements mais jamais une prise en charge personnelle. ".Tools::urlize('http://www.pole-emploi.fr/candidat/l-aide-individuelle-a-la-formation-aif--@/article.jspz?id=60857'),
		      'descinfo'=>"L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre.<br/>L’AIF est réservée à des projets de formation dont la pertinence est validée par votre conseiller référent Pôle emploi.<br/>Cette pertinence est appréciée au regard de votre projet professionnel et du marché du travail.<br/>Les formations financées doivent permettre un retour rapide à l'emploi.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['aifbilancompetence']=array(
		      'pri'=>7,
		      'title'=>"Aide individuel à la Formation (AIF)",
		      'step'=>array("Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi). <br />Votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>"L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br />Il partagera avec vous la pertinence d'établir un bilan de compétences",
		      'descinfo'=>"L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’actions de formations collectives ne peuvent répondre. <br />L’AIF bilan de compétences finance des préparations de bilan ou d'évaluation des acquis professionnels",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['aifvaepartielle']=array(
		      'pri'=>7,
		      'title'=>"Aide individuel à la Formation (AIF)",
		      'step'=>array("<span class=\"highlight\">Contactez un conseiller Pôle emploi</span> pour connaître les conditions dans votre Région de la mobilisation d'une 'Aide Individuelle à la Formation de Pôle emploi à la Validation partielle des Acquis de l'Expérience Munissez vous de la notification de la décision du jury VAE, car, pour sollciter ce financement, la formation post jury VAE doit se dérouler dans le délai imparti de 5 années maximum à compter de la date de notification de la décision du jury."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>"<br/>
		               Le montant de l'AIF correspond au montant restant à la charge du demandeur d'emploi après<br/>
		               intervention éventuelle d'autres financeurs.<br/>
		               Des dispositions régionales fixent les limites quant au montant total des frais pédagogiques de la formation pouvant être pris en charge et quant à la durée maximale de la formation.",
		      'info'=>"http://www.pole-emploi.fr/candidat/l-aide-individuelle-a-la-formation-aif--@/article.jspz?id=60856",
		      'descinfo'=>"L'AIF « VAE partielle » permet au demandeur d'emploi de suivre une formation post jury VAE, dans le délai imparti des cinq années maximum à compter de la date de notification de la décision du jury, lorsque la VAE n'a été que partiellement validée.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['aifartisan']=array(
		      'pri'=>7,
		      'title'=>"Aide individuel à la Formation Artisan (AIF Artisan)",
		      'step'=>array("Assurez vous auprès de la Chambre des métiers et de l'Artisanat (".Tools::urlize('http://www.artisanat.fr/portals/0/annuaire/annuaire.html','www.artisanat.fr').") que la formation est bien obligatoire et préalable à l'installation comme artisan.<br/>Le financement par le biais de cette Aide Inidividuelle à la Formation - Artisan (AIF Artisan) n'est possible que si le devis présenté à Pôle emploi du Stage préparatoire à l'installation d'une entreprise artisanale correspond au montant exact de l'aide (195,18 € pour 2018)."),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>"pour 195,18€",
		      'info'=>"L'AIF permet une prise en charge totale des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen …).<br/>L'AIF peut compléter des co-financements mais jamais une prise en charge personnelle. ".Tools::urlize('http://www.pole-emploi.fr/candidat/l-aide-individuelle-a-la-formation-aif--@/article.jspz?id=60857'),
		      'descinfo'=>"L'aide individuelle à la formation &laquo;&nbsp;artisan&nbsp;&raquo; permet de couvrir le coût du stage de préparation à l'installation (le SPI)<br/>Le SPI est obligatoire pour toute personne sollicitant une immatriculation auprès d'une Chambre de métiers et de l'artisanat dans le cadre d'un projet de création ou de reprise d'entreprise.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>true,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );

		$list['autres']=array(
		      'pri'=>8,
		      'title'=>"Autres financements possibles",
		      'step'=>array("
		        1. Contactez le centre de formation pour obtenir un devis de formation.<br/>
		        <br/>2. Vous pouvez solliciter un éventuel financement individuel auprès d’organismes suivants :<br/>
		         Caisses de retraites<br/>
		         Centre communal d'action sociale (CCAS)<br/>
		         Collectivités locales (Mairie, Communautés de communes...)<br/>
		         CAF<br/>
		         L'organisme de formation peut aussi vous conseiller.<br/>
		        <br/> 3. Même si Pôle emploi ne finance pas cette formation, votre conseiller peut valider votre projet de formation. Si vous êtes bénéficiaire d'une allocation de recherche d'emplo; elle sera maintenue.
			  "),
		      'cost'=>"Formation totalement ou partiellement financée",
		      'cost-complement'=>null,
		      'info'=>'',
		      'descinfo'=>"Nous n'avons pas identifié de dispositif de financement mobilisable pour ce type de formation. Il vous faudra sans doute la financer par vous-même.<br />Certains organismes peuvent, sous certaines conditions, participer à la prise en charge de frais liés à une formation. N’hésitez pas à les solliciter.",
		      'cost-plafond'=>null,
		      'montant-financement'=>null,
		      'financee-pe'=>false,
		      'financable-cpf'=>true,
		      'cumulable'=>true,
		      'reste-a-charge'=>true,
		      'famille'=>'Financement individuel'
		      );
		return $list;
	}

?>
