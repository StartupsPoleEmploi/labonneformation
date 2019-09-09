<?php
	/* Règles Languedoc Roussillon - Midi Pyrenees ************************************************************************/
	function reglesLRMP($quark,$var,&$droits,&$display)
	{
		extract($var);
		$db=$quark->getStore('read');
		/* Ligne 6 */
		/* Caractéristiques formation: */
		//AIF
		//uniquement si F°< ou = à 400h
		//
		//pas d'AIF :
		//-si code financeur "PE" collectif
		//- si code financeur 'Region" collectif
		//
		//Les domaines exclus sans possibilité de dérogation sont :
		// Développement personnel et coaching (analyse transactionnelle, PNL, re-motivation, gestion du stress, relaxation, coaching….) 15066, 15091,
		//- sauf si éligibles CPF national :CLEA. (i-e code CPF 201)
		//- toute préparation aux concours 15073,15094,15093
		//- et modules de pré-admissibilité à des formations (ex : pré-admissibilité BEPECASER 31802,).
		//- pas d'aif si diplome : Licence, Master ou Ingenieur (sauf pour les cursus universitaires professionnalisant de courte durée tels que les DU). :
		//- Le BAFA : formacode : 44067
		//- permis de catégorie A et B : 31811 et 31812
		//- Prévention et Secours Civique 1 : formacode 42829
		//
		//pas d'AIF si :
		//- si Formation en cours du soir
		//- si formation exclusivement dispensée le week-end
		//- si : Nombre heures en entreprise supérieur à 30% du nbre heures total du plan de formation
		/* Caractéristiques DE: */
		//Tout DE domicilié en LRMP - (pr F° tout territoire)
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="AIF: aide individuelle à la formation";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Vous lui présenterez 2 devis minimum de 2 organismes différents.<br/><br/>Votre projet de formation et son financement doivent être présentés avant le début de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre. <br/>Cette aide financière porte sur le coût pédagogique, elle ne prend pas en compte les frais d’équipement, de dossier ou d’inscription à des examens.<br/><br/>L’AIF est réservée à des projets de formation dont la pertinence est évaluée par votre conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail, notamment concernant les demandes relatives aux secteurs sanitaire et social. Il vérifiera aussi si les conditions du financement sont réunies,";
			$array['cost']="Formation totalement ou partiellement financée dans la limite de 3200 € <br/>(possibilité de cofinancement à étudier avec votre conseiller référent)";
			$array['cost-plafond']="3200";
			if($training_duration && $training_duration<=400)
				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
				   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(!in_array($training_racineformacode,array(150)) || hasCpf($ad,$ar,'DE|TOUTPUBLIC','/1/1/',array(201)))
						if(!in_array($training_formacode,array(15073,15094,15093,31802,44067,31811,31812,42829)))
							if(!(in_array($training_niveausortie,array(7,8)) && $training_diplome))
								if(!$training_coursdusoir && !$training_coursweekend)
									if($training_nbheurestotales && $training_nbheuresentreprise>($training_nbheurestotales*30/100))
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										{
											unset($droits['aif']); //On annule les règles nationales
											arrayInsertAfterKey($droits,'aif',$display,array('aif_lrmp1'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'aif_lrmp1',$droits);
											else
												remuRFPE2($var,'aif_lrmp1',$droits);
										}
		}
		/* Ligne 7 */
		/* Caractéristiques formation: */
		//Uniquement si bilan de compétence est d'un coût inférieur ou égal à 800€
		/* Caractéristiques DE: */
		//Tout DE domicilié en LRMP et tout public dans la liste ci-dessous:
		//Tous les publics bénéficiaires prioritaires ci-dessous :
		//- détenus en longue peine ne pouvant avoir accès a une prestation de bilan de compétences
		//- bénévoles non demandeurs d’emploi et non-salariés (ou autre statut économique) anticipant une reprise d’emploi salarié ou non salarié dans le délai d’un an,
		//- personnes en arrêt longue maladie,
		//- hommes ou femmes au foyer anticipant une reprise d’emploi salarié ou non dans un délai d’un an,
		//- travailleurs handicapés : non demandeurs d’emploi, salariés non éligibles et non pris en charge par l’AGEFIPH,
		//- personnes en congé parental total anticipant une reprise d’emploi salarié ou non salarié dans un délai d’un an,
		//- salariés travaillant à l’étranger et demeurant en France,
		//- salariés du secteur privé ou public ne comptabilisant pas 24 mois d’OPACIF,
		//- non-salariés (professions libérales, commerçants, artisans, exploitants agricoles).
		/*
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="AIF Bilan de Compétences";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Vous lui présenterez 2 devis minimum de 2 organismes différents.<br/>votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.";
			$array['descinfo']="L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiiera aussi si les conditions du financement sont réunies.";
			$array['cost']="Formation totalement financée<br/>hors frais d’équipement, de dossier ou d’inscription à des examens.";
			$display['aifbilancompetences']=$array;
			if(!$situation_inscrit && $situation_th)
			{

			}
		}
		*/
		/* Ligne 8 */
		/* Caractéristiques formation: */
		//frais pédagogiques du stage préparatoire à l’installation, dans la limite du montant déterminé en application de l’article 97 de la loi 86-1317 du 30 décembre 1986 portant loi de finances pour 1987 et de l'article 1601 du Code Général des impôts,
		/* Caractéristiques DE: */
		//vérifier votre éligibilité auprés d'un conseiller
		/*
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="AIF Artisan";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.";
			$array['descinfo']="L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiiera aussi si les conditions du financement sont réunies.";
			$array['cost']="AIF si cout de la formation est égal à 189,70 € (pour 2016)";
			$display['aifartisan']=$array;
			//A terminer
		}
		*/
		/* Ligne 9 */
		/* Caractéristiques formation: */
		//F financée région si
		//- F° topée 'code financeur Région' + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		//
		//F° formacode 15051
		/* Caractéristiques DE: */
		//tout public : DE, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+ criteres à préciser
		//
		//POI (parcours orientation insertion):
		//- places rémunérées au prorata de la durée hebdomadaire de la formation,).
		//Parcours diplomants et action préparatoires. Places rémunérées.
		//Actions de qualifications jusqu'au niveau V: rémunération si plus de 200h et pour tout DE de Niveau V
		//Actions de qulaifications niveau IV à 1: moitié des places rémunérées;
		//délai de carence formations de IV à 3: 1 an
		//Formation de 3 à 1: 2 ans.
		//délai de carence= date de sortie du système scolaire ou universitaire initial et date d'entr"e en formation et ce sans interruption.
		/*
		if(isInRegionLanguedocRoussillon($training_locationpath) || isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Formation Conseil Régional";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/><br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="Cette formation est financée par le Conseil Régional Languedoc Roussillon - Midi Pyrénées<br/>Parcours Orientation Insertion : ces formations d’orientation permettent de choisir un métier, de confirmer un projet et d’effectuer une remise à niveau avec un objectif d'emploi ou de formation qualifiante.<br/><br/>Parcours Diplômants : ces formations proposent aux demandeurs d’emploi sans qualification un parcours sécurisé vers l’acquisition d’un diplôme ou d’un titre professionnel.<br/><br/>Actions Préparatoires : ces formations ont pour objectif de donner aux demandeurs d’emploi sans qualification les connaissances théoriques et techniques nécessaires à l’accès à la qualification ou à l’emploi direct.<br/><br/>Actions de Qualification : ces formations permettent l’acquisition d’une qualification sanctionnée ou non par un diplôme.<br/><br/>+ d'infos sur le site du conseil régional ( http : ? )";
			$array['cost']="Formations gratuites";
			$display['actioncollectiveregion']=$array;
			if(1) // Tout le monde
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(in_array($training_formacode,array(15051)))
					{

					}
		}
		*/
		/* Ligne 10 */
		/* Caractéristiques formation: */
		//- F° topée 'code financeur Région' + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		//
		//+ F° formacode 15051
		/* Caractéristiques DE: */
		//tout public : DE, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+ criteres à préciser
		//
		//POI (parcours orientation insertion):
		//- places rémunérées au prorata de la durée hebdomadaire de la formation,).
		//Parcours diplomants et action préparatoires. Places rémunérées.
		//Actions de qualifications jusqu'au niveau V: rémunération si plus de 200h et pour tout DE de Niveau V
		//Actions de qulaifications niveau IV à 1: moitié des places rémunérées;
		//délai de carence formations de IV à 3: 1 an
		//Formation de 3 à 1: 2 ans.
		//délai de carence= date de sortie du système scolaire ou universitaire initial et date d'entr"e en formation et ce sans interruption.
		//if(0)
		//if(isInRegionMidiPyrenees($training_locationpath))
		//{
		//	$array=array();
		//	$array['title']="Formation Conseil Régional : Parcours Orientation Insertion";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/><br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
		//	$array['descinfo']="Cette formation est financée par le Conseil Régional Languedoc Roussillon - Midi Pyrénées <br/><br/>Parcours Orientation Insertion : ces formations d’orientation permettent de choisir un métier ; de confirmer un projet et d’effectuer une remise à niveau avec un objectif emploi ou formation qualifiante.";
		//	$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
		//	arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp2'=>$array));
		//	if(1) // Tout le monde
		//		if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
		//			if(in_array($training_formacode,array(15051)))
		//			{
		//				if($allocation_type=='are')
		//					remuAREF($var,'actioncollectiveregion_lrmp2',$droits);
		//				else
		//					remuRPS($var,'actioncollectiveregion_lrmp2',$droits);
		//				if($training_tempspartiel) $display['actioncollectiveregions2'].="<br/>Rémunération au prorata de la durée hebdomadaire de la formation.";
		//			}
		//}
		/* Ligne 11 */
		/* Caractéristiques formation: */
		//- F° topée 'code financeur Région' + intitulé 'Parcours Diplomants' Parcours Diplômants et 'Actions Préparatoires'' Actions Préparatoires' sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		/* Caractéristiques DE: */
		//tout public : DE, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+ criteres à préciser
		//
		//POI (parcours orientation insertion):
		//- places rémunérées au prorata de la durée hebdomadaire de la formation,).
		//Parcours diplomants et action préparatoires. Places rémunérées.
		//Actions de qualifications jusqu'au niveau V: rémunération si plus de 200h et pour tout DE de Niveau V
		//Actions de qulaifications niveau IV à 1: moitié des places rémunérées;
		//délai de carence formations de IV à 3: 1 an
		//Formation de 3 à 1: 2 ans.
		//délai de carence= date de sortie du système scolaire ou universitaire initial et date d'entr"e en formation et ce sans interruption.
		if(isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Formation Conseil Régional : Parcours Diplômants et Actions Préparatoires";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/><br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="Cette formation est financée par le Conseil Régional Languedoc Roussillon - Midi Pyrénées<br/>Parcours Diplômants : ces formations proposent aux demandeurs d’emploi sans qualification un parcours sécurisé vers l’acquisition d’un diplôme ou d’un titre professionnel.<br/><br/>Actions Préparatoires : ces formations ont pour objectif de donner aux demandeurs d’emploi sans qualification les connaissances théoriques et techniques nécessaires à l’accès à la qualification ou à l’emploi direct.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				if(hasStrings(array('PARCOURS DIPLOMANTS','ACTION PREPARATOIRE'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
				{
					arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp1'=>$array));
					if($allocation_type=='are')
						remuAREF($var,'actioncollectiveregion_lrmp1',$droits);
					else
						remuRPS($var,'actioncollectiveregion_lrmp1',$droits);
				}
		}
		/* Ligne 12 */
		/* Caractéristiques formation: */
		//- F° topée 'code financeur Région' + intitulé ' Actions de Qualification Préparatoires' sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		/* Caractéristiques DE: */
		//tout public : DE, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+ criteres à préciser
		if(isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Formation Conseil Régional : Parcours Diplômants et Actions Préparatoires";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/><br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="Cette formation est financée par le Conseil Régional Languedoc-Roussillon-Midi-Pyrénées <br/><br/><br/>Actions de Qualification : ces formations permettent l’acquisition d’une qualification sanctionnée ou non par un diplôme.<br/><br/>Cette Formation est accessible pour tout personne sortie du système scolaire ou universitaire initial depuis 1 à 2 ans selon la formation envisagée (dérogations possibles) .";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				if(hasStrings(array('ACTIONS DE QUALIFICATION PRÉPARATOIRES'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
				{
					if(in_array($training_niveausortie,array(5,6,7,8)))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp2'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp2',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_lrmp2',$droits,'Rémunération selon conditions');
					} else
					if(in_array($training_niveausortie,array(4,3,2,1)))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp2'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp2',$droits);
						else
							remuRPS($var,'actioncollectiveregion_lrmp2',$droits);
					}
				}
		}
		/* Ligne 13 */
		/* Caractéristiques formation: */
		//F° topée Code financeur Région + Formacode Chèques bureautiques: Formacode: 70332
		//Chèques langues: Formacode: 15234
		//Chèques comptabilité Formacode: 32663
		//CHEQUES MULTIMEDIA PAO Formacode: 46052
		/* Caractéristiques DE: */
		//Type de formations disponibles pour tout DE LRMP
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="Formation conseil régional: <br/>Chèques création d'entreprise";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/><br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="Ces formations ont pour objectif l’initiation ou l’actualisation des connaissances sur de courtes durées. Peuvent se réaliser à distance.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			$array['remu']="Pas de rémunération spécifique";
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				if(in_array($training_formacode,array(71332,15234,32663,46052)))
				{
					arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp3'=>$array));
					if($allocation_type=='are')
						remuAREF($var,'actioncollectiveregion_lrmp3',$droits);
					else
						remuTEXT($var,'actioncollectiveregion_lrmp3',$droits);
				}
		}
		/* Ligne 14 */
		/* Caractéristiques formation: */
		//F° topée Code financeur Région + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		//Formations niveau V:
		//Aide-soignant 43436 et auxiliaire de puériculture 43441 (DEAS et DEAP)
		//Aide Médico-Psychologique 44004 (DEAMP)
		//Auxiliaire de vie sociale 44028(DEAVS)
		//Formations de l’aide à domicile : Employé Familial - Assistant de vie Dépendance – Assistant de Vie aux Familles
		//Ambulancier (DEA) - Auxiliaire-ambulancier 31815
		/* Caractéristiques DE: */
		//être inscrit à POLE EMPLOI en tant que demandeur d’emploi
		if(isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Formations sanitaires et sociales";
			$array['step']="Vous devez au préalable prendre contact avec l'école et avoir réussi les épreuves de sélection pour intégrer une formation du programme régional sanitaire et social.";
			$array['descinfo']="Le domaine sanitaire et social se caractérise par la mise en oeuvre de savoirs professionnels<br/>autour de la personne.<br/>Les professionnels interviennent dans les métiers de:<br/> - l’enfance<br/> - la famille<br/> - les personnes handicapées<br/> - les personnes âgées en structure ou à domicile<br/> - la lutte contre l’exclusion<br/> - la santé<br/>Ces emplois nécessitent des savoirs et des aptitudes spécifiques d’où la nécessité de détenir un<br/>Diplôme d’Etat pour accéder à la plupart de ces professions règlementées. <br/>La Région Languedoc -Roussillon Midi Pyrenées finance les Formations des secteurs sanitaire et social<br/><br/>Exclues du dispositif: <br/>***Les personnes non salariées en congé parental qui perçoivent l’allocation de libre choix<br/>d’activité,<br/>***Les personnes inscrites sur des parcours passerelles ou de revalidation.<br/>• sorti de formation initiale depuis plus d’un an<br/>• ne pas avoir suivi de formation qualifiante financée sur fonds publics dans les 12 derniers<br/>mois";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(in_array($training_formacode,array(43436,43441,44004,44028,31815)))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp4'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp4',$droits);
						else
						{
							remuRPS($var,'actioncollectiveregion_lrmp4',$droits);
							$droits['actioncollectiveregion_lrmp4']['remu'].="<br/>Si vous n’êtes pas éligible à une rémunération, vous pouvez faire une demande de bourse, octroyée sous conditions de ressources";
						}
					}
		}
		/* Ligne 15 */
		/* Caractéristiques formation: */
		//F° topée Code financeur Région + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		//Formations niveau IV et III et II :
		//Cadre de santé 43458
		//Ergothérapeute 43491
		//Infirmier 43448
		//Infirmier anesthésiste 43457
		//Infirmier de bloc opératoire 43449
		//Manipulateur en électroradiologie médicale 43497
		//Masseur-kinésithérapeute 43490
		//Préparateur en pharmacie hospitalière 43006
		//Puéricultrice 43439
		//Sage-femme 43092
		/* Caractéristiques DE: */
		//être inscrit à POLE EMPLOI en tant que demandeur d’emploi
		if(isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Formations sanitaires et sociales";
			$array['step']="Vous devez au préalable prendre contact avec l'école et avoir réussi les épreuves de sélection pour intégrer une formation du programme régional sanitaire et social.";
			$array['descinfo']="Le domaine sanitaire et social se caractérise par la mise en oeuvre de savoirs professionnels<br/>autour de la personne.<br/>Les professionnels interviennent dans les métiers de:<br/> - l’enfance<br/> - la famille<br/> - les personnes handicapées<br/> - les personnes âgées en structure ou à domicile<br/> - la lutte contre l’exclusion<br/> - la santé<br/>Ces emplois nécessitent des savoirs et des aptitudes spécifiques d’où la nécessité de détenir un<br/>Diplôme d’Etat pour accéder à la plupart de ces professions règlementées. <br/>La Région Languedoc -Roussillon Midi Pyrenées finance les Formations des secteurs sanitaire et social<br/>Exclues du dispositif: <br/>***Les personnes non salariées en congé parental qui perçoivent l’allocation de libre choix<br/>d’activité,<br/>***Les personnes inscrites sur des parcours passerelles ou de revalidation.<br/>• sorti de formation initiale depuis plus d’un an<br/>• ne pas avoir suivi de formation qualifiante financée sur fonds publics dans les 12 derniers<br/>mois";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit)
				if(in_array($training_niveausortie,array(5,6,7)))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(in_array($training_formacode,array(43458,43491,43448,43457,43449,43497,43490,43006,43439,43092)))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp5'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_lrmp5',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_lrmp5',$droits,'Bourse possible du Conseil Régional selon ressources (<a href=\"http://www.midipyrenees.fr/Demande-de-bourse\" target=\"_blank\">effectuer la demande</a>)');
						}
		}
		/* Ligne 16 */
		/* Caractéristiques formation: */
		//F° topée Code financeur Région + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 )
		//Formations niveau V:
		//Aide-soignant 43436 et auxiliaire de puériculture 43441 (DEAS et DEAP)
		//Aide Médico-Psychologique 44004 (DEAMP)
		//Auxiliaire de vie sociale 44028(DEAVS)
		//Formations de l’aide à domicile : Employé Familial - Assistant de vie Dépendance – Assistant de Vie aux Familles
		//Ambulancier (DEA) - Auxiliaire-ambulancier 31815
		/* Caractéristiques DE: */
		//être inscrit à POLE EMPLOI en tant que demandeur d’emploi
		//if(isInRegionMidiPyrenees($training_locationpath))
		//{
		//	$array=array();
		//	$array['title']="AIF Projet individuel sur modules de formations sanitaires et sociales";
		//	$array['step']="Vous devez au préalable prendre contact avec l'organisme de formation pour vérifeir si vous accéder à cette formation en n'y effectuant que certains modules.<br/> <br/>Contacter ensuite votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/><br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
		//	$array['descinfo']="Ce dispositif permet aux DE ayant au moins deux ans d'expérience professionnelle et titulaires d'un dipôme du secteur sanitaire et social de financer certains modules de la formation visée .<br/>Ces Formations dites \"passerelles\"<br/> permettent donc de tenir compte d’une formation professionnelle<br/>diplômante déjà acquise pour aménager ou alléger un cursus vers une autre formation<br/>professionnelle diplômante. <br/>Ce dispositif permet la prise en charge totale par Pôle emploi de la formation dès lors que les modules visée sont d'une durée inférieure à 400 heures (possibilité de dérogation)";
		//	$array['cost']="Formation totalement financée par Pôle emploi";
		//	if($situation_inscrit)
		//		if(in_array($training_niveausortie,array(4)))
		//			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
		//				if(in_array($training_formacode,array(43441,44004,44028,31815)))
		//				{
		//					arrayInsertAfterKey($droits,'aif',$display,array('aif_lrmp2'=>$array));
		//					if($allocation_type=='are')
		//						remuAREF($var,'aif_lrmp2',$droits);
		//					else
		//						remuRFPE2($var,'aif_lrmp2',$droits);
		//				}
		//}
		/* Ligne 17 */
		/* Caractéristiques formation: */
		//formations courtes (inférieures à 210 heures)
		//formations professionnalisantes (jusqu’à 600 heures)
		//participation au financement du coût d’une formation individuelle s'inscrivant dans un parcours d'insertion
		/* Caractéristiques DE: */
		//s'adresse aux demandeurs d'emploi ayant une reconnaissance de travailleurs handicapés
		/*
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="Financement individuel AGEFIPH";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour effectuer une demande individuelle aupres de l'Agefiph";
			$array['descinfo']="L'Agefiph …";
			$array['cost']="Formation totallement ou partiellement fiancée par l'Agefiph";
			$display['agefiph']=$array;
		}
		*/
		/* Ligne 18 */
		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//+ intitulé 'Action cap avenir'
		/* Caractéristiques DE: */
		//Jeunes de mois de 26 ans non inscrits
		// et Demandeur d’emploi inscrit à Pôle Emploi de niveau VI, V ou IV,
		if(isInRegionLanguedocRoussillon($training_locationpath))
		{
			$array=array();
			$array['title']="Cap Avenir";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
			$array['descinfo']="Cap Avenir a pour finalité de valider un projet professionnel (identifier au moins un secteur d’activité ou un métier) en cohérence avec votre potentiel et en lien avec les réalités du marché du travail.<br/><br/>Ce dispositif s'adresse aux demandeurs d’emploi inscrits à Pôle Emploi (de niveau VI, V ou IV,) et aux jeunes de moins de 26 ans ayant quitté leur formation scolaire depuis plus de 6 mois,<br/><br/><br/>Différents types de stage seront proposés : stage pour découvrir l’entreprise, stage pour découvrir les métiers, stage pour construire et valider un projet professionnel.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if((!$situation_inscrit && $age<26) || ($situation_inscrit && in_array($niveauscolaire,array(5,4,3,2))))
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(hasStrings(array('ACTION CAP AVENIR'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp6'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp6',$droits);
						else
							remuRPS($var,'actioncollectiveregion_lrmp6',$droits);
					}
		}
		/* Ligne 19 */
		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//+ intitulé 'Action cap metier'
		/* Caractéristiques DE: */
		//Jeunes de mois de 26 ans non inscrits
		// et Demandeur d’emploi inscrit à Pôle Emploi de niveau VI, V ou IV,
		if(isInRegionLanguedocRoussillon($training_locationpath))
		{
			$array=array();
			$array['title']="Cap Métier";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
			$array['descinfo']="Cap Métiers a pour finalité de permettre aux stagiaires d'acquérir les premiers gestes professionnels du métier visé à travers un parcours individualisé, afin de poursuivre son parcours en formation qualifiante ou directement en emploi.<br/>L'objectif est de confirmer un projet professionnel en cohérence avec les réalités économiques et le marché du travail régional.<br/><br/>Ce dispositif s'adresse aux demandeurs d’emploi inscrits à Pôle Emploi (de niveau VI, V ou IV,) et aux jeunes de moins de 26 ans ayant quitté leur formation scolaire depuis plus de 6 mois,";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if((!$situation_inscrit && $age<26) || ($situation_inscrit && in_array($niveauscolaire,array(5,4,3,2))))
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(hasStrings(array('Action Cap Metier'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp7'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp7',$droits);
						else
							remuRPS($var,'actioncollectiveregion_lrmp7',$droits);
					}
		}
		/* Ligne 20 */
		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//+ intitulé ' competences cles '
		/* Caractéristiques DE: */
		//inscrit comme DE
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="Cap Compétences Clés";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
			$array['descinfo']="Ce programme vise a lutter efficacement pour la maîtrise des savoirs de base en Région. Des modules de Français langue étrangère (FLE) y sont également disponible. Il s’adresse donc aussi bien aux personnes en situation d’illettrisme qu’aux personnes en difficultés avec la langue française.<br/>Il doit permettre aux stagiaires d’acquérir une aisance certaine dans les savoirs fondamentaux (écrit et oral) afin de poursuivre ensuite leur parcours en formation préqualifiante, qualifiante ou d’accéder à un emploi.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(hasStrings(array('COMPETENCES CLES'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp8'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp8',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_lrmp8',$droits);
					}
		}
		/* Ligne 21 */
		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//+ hors F° avec code Financeur Région et intitulé 'Action cap avenir' ou 'Action cap metier' ou' competences cles'' ou 'er2c'
		//
		//à valider en recette ?????
		/* Caractéristiques DE: */
		//Demandeur d’emploi inscrit à Pôle Emploi
		if(isInRegionLanguedocRoussillon($training_locationpath) || isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Formations ERI (Expérimentation, Recherche et Innovation) expérimentales";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
			$array['descinfo']="Le programme ERI permet de suivre une action de formation présentant un caractère innovant et assurant une insertion durable dans l’emploi.<br/><br/>Les objectifs sont de :<br/>- favoriser la promotion des projets innovants et de leurs résultats en phase expérimentale<br/>- accompagner une dynamique de coopération entre porteurs de projet et, au-delà, en direction de l’ensemble des professionnels régionaux<br/>- faciliter le transfert d’innovation à l’échelle régionale.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit)
				if(!(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
				     hasStrings(array('ACTION CAP AVENIR','ACTION CAP METIER','COMPETENCES CLES','ER2C'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule'])))))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp9'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp9',$droits);
						else
							remuRPS($var,'actioncollectiveregion_lrmp9',$droits);
					}
		}
		/* Ligne 22 */
		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//+ intitulé 'parcours professionnalisant' 'parcours certifiant' ou 'parcours qualifiant'
		/* Caractéristiques DE: */
		//Demandeur d’emploi inscrit à Pôle Emploi
		if(isInRegionLanguedocRoussillon($training_locationpath) || isInRegionMidiPyrenees($training_locationpath))
		{
			$array=array();
			$array['title']="Programme Régional Qualifiant (PRQ)";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
			$array['descinfo']="Le Programme Régional Qualifiant implique une formation \"à la carte\" permettant d'acquérir des compétences reconnues par l'accés à une certification professionnelle, une qualification, un diplôme, un titre homologué ou un certificat de qualification délivré par une branche professionnelle, ou un perfectionnement en vue d'obtenir un emploi stable.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(hasStrings(array('PARCOURS PROFESSIONNALISANT','PARCOURS CERTIFIANT','PARCOURS QUALIFIANT'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp10'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp10',$droits);
						else
							remuRPS($var,'actioncollectiveregion_lrmp10',$droits);
					}
		}
		/* Ligne 23 */
		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//+ intitulé 'er2c'
		/* Caractéristiques DE: */
		//Etre demandeurs d'emploi jeunes (d’au moins 18 ans et jusqu’à 25 ans), obligatoirement inscrits à Pôle Emploi, de niveau VI, V bis, V ou IV
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="Les Ecoles Régionales de la deuxième Chance (ER2C)";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
			$array['descinfo']="Il s’agit de permettre aux jeunes de 18 à 25 ans sortis du système scolaire depuis plus de 6 mois ou relevant de mesures d’insertion socio-professionnelles, de parvenir à la maîtrise des savoirs de base : lire, écrire, compter, notions d’informatique, notions d’une langue étrangère.<br/><br/>Pendant cette période, les jeunes sont amenés à faire deux ou trois stages dans des entreprises de la région pour découvrir le monde du travail, ses contraintes, ses possibilités.<br/>La formation est très personnalisée, c’est-à-dire que chaque jeune est suivi à l’intérieur de l’école par un « référent » avec qui il peut s’entretenir de ses problèmes tant pédagogiques que personnels. Dans l’entreprise, il est suivi par un tuteur.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit && $age>=18 && $age<=25 && in_array($niveauscolaire,array(2,3,4,5)))
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(hasStrings(array('ER2C'),strtoupper(preg_replace("#[- ,()]+#i"," ",$ar['intitule']))))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp11'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp11',$droits);
						else
							remuRPS($var,'actioncollectiveregion_lrmp11',$droits);
					}
		}
		/* Ligne 24 */
		/* Caractéristiques formation: */
		//Formation code financeur Région
		//+ formacode:
		//Aide soignant	43436
		//Ambulancier	31815
		//Auxiliaire de puériculture 	43441
		//Cadre de santé	43458
		//Ergothérapeute	43491
		//Infirmier	43448
		//Infirmier anesthésiste	43457
		//DTS Imagerie médicale	43497
		//Infirmier de bloc opératoire	43449
		//Manipulateur en électroradiologie médicale	43497
		//Masseur-kinésithérapeute	43490
		//Préparateur en pharmacie hospitalière	43006
		//Puéricultrice	43439
		//Sage-femme	43092
		//Assistant de service social	44083
		//Auxiliaire de vie sociale	44028
		//CAFERUIS	44047
		//Conseiller en économie sociale et familiale	44084
		//Educateur de jeunes enfants	44050
		//educateur spécialisé	44092
		//Educateur technique spécialisé	44092
		//Moniteur educateur	44092
		//Technicien de l'intervention sociale et familiale	44008
		//( à integrer également pour les domaines d'exclusion de l'AIF )
		/* Caractéristiques DE: */
		//>Etre demandeur d'emploi ou étudiant
		//
		//.
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="Les formations sanitaires et sociales";
			$array['step']="Vous devez au préalable prendre contact avec l'école et avoir réussi les épreuves de sélection pour intégrer une formation du programme régional sanitaire et social.";
			$array['descinfo']="La Région Languedoc Roussillon - Midi Pyrénées assure le fonctionnement des centres de formation du paramédical et du travail social et accorde des bourses aux étudiants de ces filières.";
			$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(in_array($training_formacode,array(43436,31815,43441,43458,43491,43448,43457,43497,43449,43497,43490,43006,43439,43092,44083,44028,44047,44084,44050,44092,44092,44092,44008)))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp12'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_lrmp12',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_lrmp12',$droits,'Bourse possible du Conseil Régional selon ressources (<a href=\"http://www.midipyrenees.fr/Bourses-sanitaires-et-sociales-3949\" target=\"_blank\">effectuer la demande</a>)');
					}
		}
		/* Ligne 25 */
		/* Caractéristiques formation: */
		//F° avec code financeur Agefiph
		/* Caractéristiques DE: */
		//demandeurs d’emploi bénéficiaires de l'obligation d'emploi de LR
		// (- départements: 011-30- 34-48-66)
		if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		{
			$array=array();
			$array['title']="Formations collectives des demandeurs d'emploi handicapés";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement qui devra transmettre une fiche de prescription à l'Agefiph (Association de gestion du fonds pour l'insertion des personnes handicapées) au plus tard 3 semaines avant le début de la formation.";
			$array['descinfo']="L'objectif de ce financement de l'Agefiph est de permettre à une personne handicapée d’acquérir les compétences nécessaires à un accès durable à l’emploi.";
			$array['cost']="Formation totalement financée par l'Agefiph";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces))
					if(in_array($training_formacode,array(43436,31815,43441,43458,43491,43448,43457,43497,43449,43497,43490,43006,43439,43092,44083,44028,44047,44084,44050,44092,44092,44092,44008)))
					{
						arrayInsertAfterKey($droits,'agefiphcollectif',$display,array('agefiphcollectif_lrmp1'=>$array));
						if($allocation_type=='are' && $training_nbheurestotales>210)
							remuAREF($var,'agefiphcollectif_lrmp1',$droits);
						else
							remuRPS($var,'agefiphcollectif_lrmp1',$droits);
					}
		}
		/* Ligne 27 */
		/* Caractéristiques formation: */
		//F° avec code financeur région + siret suivants:
		//- 19341089100280 (univ P valery montp)
		//- 19660437500317 (univ perpignan )
		//- 49189213900016 (cnam)
		/* Caractéristiques DE: */
		//>Etre sortis du système scolaire depuis plus de 6 mois,
		//>n'ayant pas bénéficié, dans le délai d'un an, d'une formation financée dans le cadre des programmes pris en charge par la Région Languedoc-Roussillon,
		//if(0)
		//if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		//{
		//	$array=array();
		//	$array['title']="Formations des établissements d'enseignement supérieur";
		//	$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
		//	$array['descinfo']="La région langeudoc -Roussillon Midi Pyrennées finance pour des demandeurs d'emploi (possédant les prérequis définis par l’établissement d'enseignement supérieur) des formations assurées par les centres de formation continue des universitées et vous permet ainsi l'accès à des diplômes nationaux et dans certains cas à des diplômes d'université.<br/><br/>Si vous n'avez pas le Baccalauréat, la région Languedoc -Roussillon Midi Pyrennées finance également le diplôme d'accès aux études universitaires (DAEU) pour pouvoir accéder aux diplômes de l'enseignement supérieur (après le Bac)";
		//	$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées<br/>>Pour les formations d'enseignement supérieur financées par la Région, des frais ou des droits d'inscription sont à la charge des demandeurs d'emploi : 160 € pour une année.<br/><br/>>Pour le DAEU et la capacité en droit, les frais demandés sont de 80 €.<br/><br/>>Pour les formations du CNAM, les demandeurs d'emploi doivent payer 75 € de droits d'inscription + 1 € par heure d'enseignement.";
		//	arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp15'=>$array));
		//	if($situation_inscrit)
		//		if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
		//			if(in_array($training_siret,array('19341089100280','19660437500317','49189213900016')))
		//				if($allocation_type=='are')
		//					remuAREF($var,'actioncollectiveregion_lrmp14',$droits);
		//				else
		//					remuAREF($var,'actioncollectiveregion_lrmp14',$droits);
		//}
		/* Ligne 28 */
		/* Caractéristiques formation: */
		//F° avec code financeur Région +siret :
		//btp cfa 31 009 419 800 028
		//cci sud form : 18 340 001 900 471
		//IRFMA de l'Aude : 18 110 003 300 024
		//mfr st hypol : 41 953 572 900 026
		//cfa sport mediterranée : 50 819 727 400 014
		//IRFMA des PO : 18 660 001 100 032
		//Chambre de Métiers et de l'Artisanat du Gard - Antenne de Nîmes 18 300 001 700 016
		//CFA Agricole de l'Aude : 20 000 746 600 077
		//IRFMA de la Lozère : 18 480 003 500 042
		/* Caractéristiques DE: */
		//Jeunes de 16 à 26 ans, (30 ans pour les TH)

		//if(0)
		//if(isInRegionLanguedocRoussillon($domicilePath) || isInRegionMidiPyrenees($domicilePath))
		//{
		//	$array=array();
		//	$array['title']="Ecole de l'apprentissage";
		//	$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l''organisme de formation.";
		//	$array['descinfo']="Ce dispositif permet de mieux connaitre l’univers de l’apprentissage et de vous aider à déterminer un métier et trouver une oriention. il s'agit d'actions pré-qualifiantes, de découvertes des métiers accessibles par la voie de l’apprentissage, de consolidation de projet professionnel ou de recherche d'entreprise pour la signature de votre contrat d’apprentissage";
		//	$array['cost']="Formation totalement financée par la région Languedoc Roussillon - Midi Pyrénées";
		//	if($situation_inscrit)
		//		if($age>=16 && (($age<=26 && !$situation_th) || ($age<=30 && $situation_th)))
		//			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
		//				if(in_array($training_siret,array('31009419800028','18340001900471','18110003300024','41953572900026','50819727400014','18660001100032','18300001700016','20000746600077','18480003500042')))
		//				{
		//					arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_lrmp16'=>$array));
		//					/* Règles grisée sur le tableau de règles. En stand by */
		//				}
		//}
	}
?>