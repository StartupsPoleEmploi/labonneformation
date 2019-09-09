<?php
	/* Règles Grand Est ***************************************************************************************************/
	function reglesGrandEst($quark,$var,&$droits,&$display)
	{
		extract($var);

		/* Ligne 3 */
		/* Caractéristiques formation: */
		//"   F° France entière
		//F certifiantes + tag CPF COPANEF ou COPAREF DE Grand Est + Durée F°: 140 heures --> 1600 heures, + période en entreprise <1/3 durée totale
		//OU F° professionnalisantes durée totale : 140 heures --> 600 heures. et Intensité hebdomadaire > 20 heures minimum
		// Pas d'ARIF
		//si F°  collectives avec financement Région ou PE ou OPCA ou Etat ou coll terr
		//ou si F° niveau II && I
		//ou si intitulé = recyclage CACES,  recyclage habilitation électrique,
		//ou si formacode 31826
		//ou si intitulé = Permis C et D
		//ou si durée > un an
		//ou si intitulé =  ""par correspondance"";""à distance""; ""FOAD""
		//ou si formacode 13030, 15073, 43409, 44002,15084,15094,15093,  42032, 42052,  42040  32047,15054,15066,1507, 15034,15067,15098, 15096,15020, 15097, 15068,15078, 15036, 15069, 15089, 15099,15079, 15091
		//ou si domaine formacode 430 ou domaine formacode 434 ou domaine formacode 440
		//ou si certifinfo 56072,  49616,  49616, 31056, 31062
		/* Caractéristiques DE: */
		//demandeurs d’emploi Grand Est
		//Les jeunes - 26 ans  Grand Est
		if(isInRegionGrandEst($domicilePath))
		{
			unset($droits['aif']);
			$array=array();
			$array['title']="ARIF : Aide Individuelle régionale à la formation";
			$array['step']="Contactez un prescripteur habilité par la Région (Pôle emploi, Mission locale, Cap Emploi) pour valider votre projet et transmettre la demande et à la Région avec les documents nécessaires ((CV, lettre de motivation, 2 devis nominatif, descriptif de la formation.) et  activer votre CPF si la formation est éligible et si vous êtes d'accord.<br/>La demande doit être déposée dans les <b>3 semaines préalablement</b> à l’entrée en formation.<br/>La prise de décision (accord ou refus) sera émise par la Commission Permanente de la Région.";
			$array['descinfo']="Dispositif de la région Grand Est destiné à favoriser l’insertion professionnelle des demandeurs d'emploi par un accès individuel à une formation visant l’acquisition d’une certification ou diplôme, en complément du programme régional et des mesures Pôle emploi.";
			$array['info']="Certains critéres d'égibilité s'appliquent aux formations.Les formations dont la durée de formation est supérieure à un an pour l’obtention du diplôme (quelle  que soit l’année de formation pour laquelle la demande de prise en charge est établie (1ère, 2ème ou 3ème année), par correspondance ou à distance (y compris le CNED) sont exclues, de même que les permis, les formations de niveau I et II,  certaines formations du sanitaire et social... Les demandes de formation successives ne sont pas recevables à l’exception des suites de parcours (inférieure à 12 mois).";
			$array['cost']="Formation financée jusqu'à 6000&nbsp;€ (coûts pédagogiques exclusivement)";
			$array['cost-plafond']=6000;
			if($situation_inscrit || $age<26)
				if(($training_certifiante && (hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath)) && $training_nbheurestotales>=140 && $training_nbheurestotales<=1600 && $training_nbheuresentreprise<=($training_nbheurestotales/3)) ||
				   ($training_professionnalisante && $training_nbheurestotales>=140 && $training_nbheurestotales<=600 && $training_intensitehebdomadaire>=20))
				{
					$test=false;
					/* sreg: Tellement de tests que l'indentation peut etre longue. J'opte pour une variable boolenne $test pour le meme résultat */
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) ||
					   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces))
						$test=true;
					if(!$test && ($training_niveausortie==CODENIVEAUSCOLAIRE_LICENCEMAITRISE || $training_niveausortie==CODENIVEAUSCOLAIRE_SUPMAITRISE))
						$test=true;
					if(!$test && hasStrings(array('RECYCLAGE CACES','RECYCLAGE HABILITATION ELECTRIQUE','PERMIS C','PERMIS D'),$ar['intitule']))
						$test=true;
					if(!$test && in_array($training_formacode,array(31826,13030,15073,43409,44002,15084,15094,15093,42032,42052,42040,32047,15054,15066,15075,15034,15067,15098,15096,15020,15097,15068,15078,15036,15069,15089,15099,15079,15091)))
						$test=true;
					if(!$test && in_array($training_racineformacode,array(430,434,440)))
						$test=true;
					if(!$test && in_array($training_codecertifinfo,array(56072,49616,31056,31062)))
						$test=true;
					/* Si aucun des cas ci-dessus n'est validé, alors le financement ARIF est possible */
					if(!$training_contratprofessionalisation && !$training_contratapprentissage)
						if(!$test)
						{
							arrayInsertAfterKey($droits,'aif',$display,array('aif_acal'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'aif_acal',$droits);
							else
								remuRPS($var,'aif_acal',$droits);
						}
				}
		}
		/* Ligne 4 */
		/* Caractéristiques formation: */
		//F° = certifinfo 48735, 64144,48172, 78851, 20138, 49332, 18730, 71516, 18733, 78459, 53335, 20132,54913, 54917, 54912, 20103, 20120, 23715, 53277, 53865, 73378, 21097.
		//et code financeur = Région
		//et lieu = Grand Est
		/* Caractéristiques DE: */
		//Demandeurs d’emploi non démissionnaires France entière
		if(isInRegionGrandEst($training_locationpath))
		{
			$array=array();
			$array['title']="Formations Sanitaire et Social";
			$array['step']="Retirer un dossier pour s'inscrire au concours auprès des écoles concernées. Le projet doit être validé par le conseiller référent emploi.";
			$array['descinfo']="La Région Grand Est ne finance pas les formations partielles ou par voie de passerelles, les places supplémentaires sur les formations déjà financées.";
			$array['info']="Renseignez-vous auprès de l'école visée ou de votre conseiller emploi pour connaitre les critères d'éligibilité appliquées.<br/>Seuls les frais de formation (frais pédagogiques) peuvent être pris en charge par la Région Grand Est.<br/>Les frais de concours, d’inscription, de dossier, d’hébergement, de restauration et autres frais de scolarité restent à la charge de l’apprenant.<br/>Une partie des frais d’inscription peuvent être remboursés aux étudiants boursiers.<br/>Se renseigner auprès de Pôle emploi pour les conditions d'attribution d'aides à la mobilité et de frais de garde d'enfants pour les parents isolés ";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if($situation_nondemissionaire) //Bouchon, a ajouter dans le formulaire
					if(in_array($training_codecertifinfo,array(48735,64144,48172,78851,20138,49332,18730,71516,18733,78459,53335,20132,54913,54917,54912,20103,20120,23715,53277,53865,73378,21097)))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_acal'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_acal',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_acal',$droits,"Si vous ne bénéficiez pas de l'AREF, de nouvelles régles de rémunération seront mises en oeuvres à compter de novembre 2017");
						}
		}
		/* Ligne 5 */
		/* Caractéristiques formation: */
		//intitulé formation contient "Atout  clé".  date début : 15-02-2017 date fin : 15-02-2018
		//territoire Lorraine : 54 , 55, 57, 88
		/* Caractéristiques DE: */
		//départements : 54, 55 ,57, 88
		//DE, jeune - 26 ans sans emploi, sans diplôme
		//Salariés en contrat aidé
		//Salariès qui souhaitent développer leurs compétences clés sans que l'employeur en soit informé
		if(isInRegionGrandEst($domicilePath))
		{
			$array=array();
			$array['title']="&laquo;&nbsp;Atout Clés&nbsp;&raquo;&nbsp;:&nbsp;Dispositif Lorrain";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).";
			$array['descinfo']="La Région Grand Est finance un dispositif visant la lutte contre l'illétrisme et l'acquisition de compétences essentielles pour favoriser l'accés à la formation et à l'insertion professionnelle.<br/>Cette formation  permet de développer une ou plusiers des compétences fondamentales suivantes : compréhension et expression écrites - mathématiques, sciences et technologies - bureautique et internet - aptitude à développer ses connaissances et compétences - initiation à une langue étrangére.";
			$array['info']="Les dates, durée, rythme et contenus sont personnalisés en fonction du projet professionnelle de chacun.<br/>Le rythme hebdomadaire de la formation est au maximum de 18 heures par semaine.<br/>Cette formation est donc compatible avec une recherche d'emploi concomitante.";
			$array['cost']="Formation totalement financée";
			//if(Tools::inDateInterval(date('Ymd'),'20170215','20180215'))
			if($situation_inscrit || ($age<26 && $niveauscolaire==CODENIVEAUSCOLAIRE_SANSDIPLOME))
				if(isInDepartements_54_55_57_88($domicilePath))
					if(isInDepartements_54_55_57_88($training_locationpath))
						if(hasStrings(array('ATOUT CLE'),$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_acal2'=>$array));
							
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_acal2',$droits);
							elseif($situation_personnesortantcontrataide)
								remuFORM2($var,'actioncollectiveregion_acal2',$droits);
							else
								remuRPS($var,'actioncollectiveregion_acal2',$droits);
						}
		}
		/* Ligne 6 */
		/* Caractéristiques formation: */
		//Intitulé formation  contient "Tonic" et "PM ..." et départements  : Ardennes (08) Aube (10) Marne (51) Haute-Marne (52).
		/* Caractéristiques DE: */
		//Ardennes (08) Aube (10) Marne (51) Haute-Marne (52)
		//DE de + 26 ans
		//ou Jeunes moins de 26 ans , sans emploi, sans diplôme
		//Salariés en contrat aidé ou en mission intérimaire
		if(isInRegionGrandEst($domicilePath))
		{
			$array=array();
			$array['title']="&laquo;&nbsp;Tonic&nbsp;&raquo; : dispositif champardennais";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).";
			$array['descinfo']="La Région Grand Est finance un dispositif visant la lutte contre l'illétrisme et l'acquisition de compétences essentielles pour  favoriser l'accés à la formation et à l'insertion professionnelle.<br/>Le dispositif « TONIC » vous propose une démarche séquencée, des temps d’accompagnement ciblés sur des contenus et des objectifs spécifiques pour vous amener à terme à une insertion professionnelle durable.<br/>Il s’agit de vous proposer des choix de parcours, de limiter le sentiment d’échec, de construire un parcours de formation cohérent et un projet réalisable, au vu du diagostic réalisé.<br/>Les temps d’accompagnement et de formation sont progressifs et alternent entretiens individuels et séances collectives pour répondre à des degrés d’engagement et de disponibilité variables.";
			$array['info']="Ce dispositif comporte 5 étapes, mobilisables selon vos besoins :<br/>A : Diagnostic<br/>B : Défi illetrisme<br/>C : Préparation à la Qualification<br/>D : Activation Professionnelle / valorisation des capacités et des ressources.<br/>E : Validation de projet / Objectif emploi-formation";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit || ($age<26 && $niveauscolaire==CODENIVEAUSCOLAIRE_SANSDIPLOME))
				if($situation_personnesortantcontrataide || $situation_interim)
					if(isInDepartements_08_10_51_52($domicilePath))
						if(isInDepartements_08_10_51_52($training_locationpath))
							if(hasKeywords(array('TONIC','PM'),$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_acal3'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_acal3',$droits);
								else
									remuRPS($var,'actioncollectiveregion_acal3',$droits);
							}
		}
		/* Ligne 7 */
		/* Caractéristiques formation: */
		//F° niveau V et IV
		//et code financeur Région,
		//et localisation : Grand Est
		//hors (certifinfo n°54913, certifinfo n°54917, certifinfo n°54912, certifinfo n°53865,  certifinfo n°21097

		/* Caractéristiques DE: */
		//Demandeurs d'emploi France entière
		if(isInRegionGrandEst($training_locationpath))
		{
			$array=array();
			$array['title']="PRF : Plan Régional de Formation";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).";
			$array['descinfo']="Le plan régional de formation est construit par les Conseils Régionaux conformément à la Loi de mars 2014, relative à la formation professionnelle.<br/>Ce sont des actions de formations dipômante pour les demandeurs d'emploi de Niv V et IV.";
			$array['info']="Des défraiements complémentaires sont possibles sous conditions, seul l'organisme de formation peut renseigner sur ce sujet.";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if($training_niveausortie==CODENIVEAUSCOLAIRE_BAC || $training_niveausortie==CODENIVEAUSCOLAIRE_CAPBEPCFPA)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_codecertifinfo,array(54913,54917,54912,53865,21097)))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_acal4'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_acal4',$droits);
							else
								remuRPS($var,'actioncollectiveregion_acal4',$droits);
						}
		}
	}
?>
