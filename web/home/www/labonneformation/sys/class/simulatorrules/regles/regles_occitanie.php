<?php
	/* Règles Occitanie ***************************************************************************************************/
	function reglesOccitanie($quark,$var,&$droits,&$display)
	{
		extract($var);
		/* Ligne 3 (Occitanie) */
		/* Caractéristiques DE: */
		//Tout DE domicilié en LRMP - (pr F° tout territoire)

		/* Caractéristiques formation: */
		//F° France entière
		//pas d'AIF :
		//-si code financeur "PE" collectif
		//- si code financeur 'Region" collectif
		//- si formacode = 150 sauf si code CPF 201
		//- si formacode = 434 sauf si formacode = 43421, 43429, 43485, 43401, 43424, 43415, 43407, 43426, 43417, 43486
		//- si formacode = 31802 , 31811, 31812, 44028
		//- si Certif Info 49616, 56306, 87185, 87187, 87189, 52411, 63669, 63670, 88309
		//- si intitulé comprend licence, master, ingénieur
		//si certifinfo Ambulancier (DEA) 59412 + siret 77567227207806
		//'43429','43485','43401','43424','43415','43407','43426','43417','43486','31802','31811','31812','44028','49616','56306','87185','87187','87189','52411','63669','63670','88309','59412'

		/* Rémunération: */
		//AREF sinon RFPE
		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="AIF : Aide Individuelle à la Formation";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour qu'il valide votre projet.<br/>Lui apporter le formulaire devis AIF que le centre de formation vous aura donné, complété pour sa part (téléchargeable sur <a href=\"http://www.pole-emploi.org\" target=\"_blank\">www.pole-emploi.org</a>)<br/>Votre projet de formation et son financement doivent être présentés avant le début de la formation.";
			$array['descinfo']="";
			$array['info']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d'action de formation collectives ne peuvent répondre.<br/>Cette aide financière porte sur le coût pédagogique, elle ne prend pas en compte les frais d'équipement, de dossier ou d'inscription à des examens.<br/>L'AIF est réservée à des projets de formation dont la pertinence est évaluée par votre conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail, notamment concernant les demandes relatives aux secteurs sanitaire et social. Il vérifiera aussi si les conditions du financement sont réunies,<br/>Les formations se déroulant en cours du soir, le week-end, ou proposant une période en entreprise dont la durée est supérieure au tiers de la durée totale de la formation sont exclues.";
			$array['cost']="Formation totalement ou partiellement financée dans la limite de 1000&nbsp;€ (possibilité de cofinancement à étudier avec votre conseiller référent mais sans autofinancement)";
			if($situation_inscrit && isInRegionOccitanie($domicilePath))
			{
				if($niveauscolaire<CODENIVEAUSCOLAIRE_BAC)
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces)&&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces)&&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
						if($training_racineformacode!=150 || hasCOPANEF($ad,$ar,array(201)))
							if($training_racineformacode!=434 || in_array($training_formacode,array('43421','43429','43485','43401','43424','43415','43407','43426','43417','43486')))
								if(!in_array($training_formacode,array('31802','31811','31812','44028','44591')))
									if(!in_array($training_codecertifinfo,array('49616','56306','87185','87187','87189','52411','63669','63670','88309')))
										if(!hasKeywords(array('LICENCE','MASTER','INGENIEUR'),$ar['intitule']))
											if(!(in_array($training_codecertifinfo,array('59412')) && in_array($training_siret,array('77567227207806'))))
												if(!$training_contratprofessionalisation && !$training_contratapprentissage)
												{
													arrayInsertAfterKey($droits,'aif',$display,array('aif_occitanie'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'aif_occitanie',$droits);
													else
														remuRFPE2($var,'aif_occitanie',$droits);
												}
			}
		}

		/* Ligne 4 (Occitanie) */
		/* Caractéristiques DE: */
		//tout public : DE, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+ criteres à préciser

		/* Caractéristiques formation: */
		//- F° topée 'code financeur Région' + Formation sur les départements Midi Pyrenées (i-e dpt : 09-12-31-32-46-65-81-82)
		//+ F° formacode 15061
		//'15061'

		/* Rémunération: */
		//AREF. ou sinon RPS , (indiquer &laquo;&nbsp;pour formation à temps partiel : rémunération au prorata de la durée hebdomadaire de la formation&nbsp;&raquo;). Pour TH : droit d'option possible

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formation Conseil Régional : Parcours Orientation Insertion";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="";
			$array['info']="Cette formation est financée par le Conseil Régional Languedoc-Roussillon-Midi-Pyrénées<br/>Parcours Orientation Insertion : ces formations d'orientation permettent de choisir un métier ; de confirmer un projet et d'effectuer une remise à niveau avec un objectif emploi ou formation qualifiante.<br/>Plus d'infos sur le site du conseil régional (<a href=\"http://www.laregion.fr)\" target=\"_blank\">www.laregion.fr)</a>";
			$array['cost']="Formation totalement financée par la Région";
			if(isInRegionExMidiPyrenees($training_locationpath))
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					if(in_array($training_formacode,array('15061')))
						if(hasStrings('PARCOURS ORIENTATION INSERTION',$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_occitanie',$droits);
							else
								remuRPS($var,'actioncollectiveregion_occitanie',$droits);
						}
		}

		/* Ligne 5 (Occitanie) */
		/* Caractéristiques DE: */
		//DE France entière, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+ niveau de formation < ou = niveau v

		/* Caractéristiques formation: */
		//- F° topée 'code financeur Région' sur les départements Midi Pyrenées (i-e dpt : 09-12-31-32-46-65-81-82)
		//hors F° code financeur Région + formacode 15061
		//et F°>200h et < ou = niveau V
		//'15061'

		/* Rémunération: */
		//AREF ou RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formation Conseil Régional : Parcours Diplômants et Actions Préparatoires";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="";
			$array['info']="Cette formation est financée par le Conseil Régional Languedoc-Roussillon-Midi-Pyrénées<br/>Actions de Qualification : ces formations permettent l'acquisition d'une qualification sanctionnée ou non par un diplôme.<br/>Actions Préparatoires : ces formations ont pour objectif de donner aux demandeurs d'emploi sans qualification les connaissances théoriques et techniques nécessaires à l'accès à la qualification ou à l'emploi direct.<br/>Cette formation est accessible pour tout personne titulaire d'un diplôme du système scolaire ou universitaire initial depuis 1 à 2 ans selon la formation envisagée (dérogations possibles).";
			$array['cost']="Formation totalement financée par la région LRMP";
			if(isInRegionExMidiPyrenees($training_locationpath))
				if($niveauscolaire<=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && !in_array($training_formacode,array('15061')))
						if((!$training_duration || $training_duration>200) && $training_niveausortie>=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie2'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_occitanie2',$droits);
							else
								remuRPS($var,'actioncollectiveregion_occitanie2',$droits);
						}
		}

		/* Ligne 6 (Occitanie) */
		/* Caractéristiques DE: */
		//DE France entière, TH, travailleurs non salariés inscrits ou non à Pôle emploi, détenus,
		//+niveau de formation > niveau V

		/* Caractéristiques formation: */
		//- F° topée 'code financeur Région' + sur les départements Midi Pyrenées (i-e dpt : 09-12-31-32-46-65-81-82)
		// F° code financeur Région et pas formacode 15061, F° > 200h et > niveau V
		//'15061'

		/* Rémunération: */
		//AREF ou. rémunération publique de stage selon conditions (se renseigner auprès de votre conseiller)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formation Conseil Régional :<br/>Parcours Diplômants et Actions Préparatoires";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="";
			$array['info']="Cette formation est financée par le Conseil Régional Languedoc-Roussillon-Midi-Pyrénées<br/>Actions de Qualification : ces formations permettent l'acquisition d'une qualification sanctionnée ou non par un diplôme.<br/>.<br/>Cette formation est accessible pour tout personne titulaire d'un diplôme du système scolaire ou universitaire initial depuis 1 à 2 ans selon la formation envisagée (dérogations possibles).";
			$array['cost']="Formation totalement financée par la région LRMP";
			if(isInRegionExMidiPyrenees($training_locationpath))
				if($niveauscolaire>CODENIVEAUSCOLAIRE_CAPBEPCFPA)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_formacode,array('15061')))
							if($training_niveausortie>CODENIVEAUSCOLAIRE_CAPBEPCFPA)
								if(!$training_duration || $training_duration>200)
								{
									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie3'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_occitanie3',$droits);
									else
										remuTEXT($var,'actioncollectiveregion_occitanie3',$droits,'Rémunération publique de stage selon conditions (se renseigner auprès de votre conseiller).');
								}
		}

		/* Ligne 7 (Occitanie) */
		/* Caractéristiques DE: */
		//DE localisé en Occitanie (LRMP)

		/* Caractéristiques formation: */
		//F° topée Code financeur Région + intitulé comprend "chèques" + Formacode 70332
		//ou Formacode: 15234, ou Formacode: 32663 ou Formacode: 46052
		//'70332','15234','32663','46052'

		/* Rémunération: */
		//AREF pas de RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formation conseil régional : dispositif &laquo;&nbsp;chèque&nbsp;&raquo;";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Pour entrer en formation, votre projet doit être validé avant le début de la formation.";
			$array['descinfo']="";
			$array['info']="Ces formations ont pour objectif l'initiation ou l'actualisation des connaissances sur de courtes durées. Elles peuvent se réaliser à distance.";
			$array['cost']="Formation totalement financée par la région LRMP";
			if(isInRegionOccitanie($domicilePath))
				if($situation_inscrit)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && hasKeywords('CHEQUES',$ar['intitule']) && in_array($training_formacode,array('70332','15234','32663','46052')))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie4'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_occitanie4',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_occitanie4',$droits);
					}
		}

		/* Ligne 8 (Occitanie) */
		/* Caractéristiques DE: */
		//être inscrit à POLE EMPLOI en tant que demandeur d'emploi

		/* Caractéristiques formation: */
		//F° topée Code financeur Région + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 ) + formacode 43436, 43441, 44028 et certifinfo 87185, 87187, 87189, 63669, 63670, 88309, 65960
		//ou
		//Aide-soignant : formacode 43436 + siret 26120014100040, 77567227207806, 26310012500636, 13001913600022, 26310018200058, 77567227207806, 26650009900020, 18320222500011, 26810005400066, 26120011700131, 19650029200012, 26820007800064, 26810008800015, 19310057500011, 43908850100028
		//(auxiliaire de puériculture) formacode 43441 + siret 43908850100028, 19310057500011,
		//(Aide Médico-Psychologique) : certifinfo 87185, 87187, 87189, 52411 + siret 32434542000040, 77558121800218, 19310044300020
		//(Auxiliaire de vie sociale) formacode 44028
		//:certifinfo 63669 (Employé Familial)- certifinfo 63670 (Assistant de vie Dépendance) – certifinfo 88309 (Assistant de Vie aux Familles)
		//Ambulancier (DEA) certifinof 54912 + siret 77567227207806
		//'43436','43441','44028','87185','87187','87189','63669','63670','88309','65960','43436','43441','87185','87187','87189','52411','44028','63669','63670','88309','54912'

		/* Rémunération: */
		//Pour formations niveau V:. Si ARE: AREF. RPS possible. AREF - RPS (qqsoit régime ant) si formation de niv V. si niv > à V, se renseigner auprès de son conseiller car soumis à condition. sinon possibilté de demande de Bourses régionales (<a href=\"http://www.midipyrenees.fr/Bourses-sanitaires-et-sociales-3949)\" target=\"_blank\">www.midipyrenees.fr</a>

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations sanitaires et sociales";
			$array['step']="Vous devez au préalable prendre contact avec l'école et avoir réussi les épreuves de sélection pour intégrer une formation du programme régional sanitaire et social.";
			$array['descinfo']="";
			$array['info']="Le domaine sanitaire et social se caractérise par la mise en oeuvre de savoirs professionnels<br/>autour de la personne.<br/>Les professionnels interviennent dans les métiers de:<br/>- l'enfance<br/>- la famille<br/>- les personnes handicapées<br/>- les personnes âgées en structure ou à domicile<br/>- la lutte contre l'exclusion<br/>- la santé<br/>Ces emplois nécessitent des savoirs et des aptitudes spécifiques d'où la nécessité de détenir un<br/>Diplôme d'Etat pour accéder à la plupart de ces professions règlementées.<br/>La Région Languedoc -Roussillon Midi Pyrénées finance les formations des secteurs sanitaire et social<br/>Sont exclues du dispositif :<br/>- Les personnes non salariées en congé parental qui perçoivent l'allocation de libre choix<br/>d'activité,<br/>- Les personnes inscrites sur des parcours passerelles ou de revalidation.<br/>- Les personnes sorties de formation initiale ou de formation qualifiante financée sur fonds publics depuis un an";
			$array['cost']="Formation totalement financée par la région LRMP";
			if($situation_inscrit)
				if(isInRegionExMidiPyrenees($training_locationpath))
					if($training_niveausortie>=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && in_array($training_formacode,array('43436','43441','44028')) && in_array($training_codecertifinfo,array('87185','87187','87189','63669','63670','88309','65960')) ||
						   (in_array($training_formacode,array('43436')) && in_array($training_siret,array('26120014100040','77567227207806','26310012500636','13001913600022','26310018200058','77567227207806','26650009900020','18320222500011','26810005400066','26120011700131','19650029200012','26820007800064','26810008800015','19310057500011','43908850100028'))) ||
						   (in_array($training_formacode,array('43441')) && in_array($training_siret,array('43908850100028','19310057500011'))) ||
						   (in_array($training_codecertifinfo,array('87185','87187','87189','52411')) && in_array($training_siret,array('32434542000040','77558121800218','19310044300020'))) ||
						   (in_array($training_formacode,array('44028')) && in_array($training_siret,array('77567227207806'))) ||
						   (in_array($training_codecertifinfo,array('63669','63670','88309','54912')) && in_array($training_siret,array('77567227207806'))))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie5'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_occitanie5',$droits);
								else
								if($training_niveausortie==CODENIVEAUSCOLAIRE_CAPBEPCFPA)
								{
									remuRPS($var,'actioncollectiveregion_occitanie5',$droits);
								} else
									remuTEXT($var,'actioncollectiveregion_occitanie5',$droits,'Possibilité de demande de bourse régionale');
							}
		}

		/* Ligne 9 (Occitanie) */
		/* Caractéristiques DE: */
		//être inscrit à POLE EMPLOI en tant que demandeur d'emploi

		/* Caractéristiques formation: */
		//F° topée Code financeur Région + Formation sur les départements Midi Pyrenées ( i-e dpt : 09-12-31-32-46-65-81-82 ) + formacodee 43491, 43448, 43457, 43449, 43497, 43490, 43006, 43439, 43092 ou certifinfo 82976
		//ou
		//(Cadre de santé) certifinfo 82976 + siret 26310012500636
		//'Ergothérapeute ) formacode 43491 + siret 26310012500636
		//(Infirmier) formacode 43448 + SIRET 13001913600022, 77567227207806, 18320222500011, 26810005400066, 26120011700131, 26820007800064, 26310012500636, 26810001300062, 26090023800197
		//(Infirmier anesthésiste) formacode 43457 + siret 26310012500636
		//(Infirmier de bloc opératoire) formacode 43449 + siret 26310012500636
		//(Manipulateur en électroradiologie médicale) formacode 43497 + siret 26310012500636
		//(Masseur-kinésithérapeute) formacode 43490 + siret 26310012500636
		//(Préparateur en pharmacie hospitalière) formacode 43006 + siret 26310012500636
		//(Puéricultrice) formacode 43439 + siret 19310057500011
		//(Sage-femme) formacode 43092 + siret 26310012500636
		//'43491','43448','43457','43449','43497','43490','43006','43439','43092','82976','82976','43491','43448','43457','43449','43497','43490','43006','43439','43092'

		/* Rémunération: */
		//Pour formation niveau IV et III et II. si ARE: AREF. Si pas d'ARE: bourses du Conseil régional possibles selon ressources (effectuer demande : <a href=\"http://www.midipyrenees.fr/Demande-de-bourse)\" target=\"_blank\">www.midipyrenees.fr</a>. Si pas d'ARE noter. \"Bourse possible du Conseil Régional selon ressources (effectuer demande : <a href=\"http://www.midipyrenees.fr/Demande-de-bourse)\" target=\"_blank\">www.midipyrenees.fr</a>. AREF - RPS (qqsoit régime ant) si formation de niv V. si niv > à V, se renseigner auprès de son conseiller car soumis à condition. sinon possibilté de demande de Bourses régionales (<a href=\"http://www.midipyrenees.fr/Bourses-sanitaires-et-sociales-3949)\" target=\"_blank\">www.midipyrenees.fr</a>

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations sanitaires et sociales";
			$array['step']="Vous devez au préalable prendre contact avec l'école et avoir réussi les épreuves de sélection pour intégrer une formation du programme régional sanitaire et social.";
			$array['descinfo']="";
			$array['info']="Le domaine sanitaire et social se caractérise par la mise en oeuvre de savoirs professionnels autour de la personne.<br/>Les professionnels interviennent dans les métiers de:<br/>- l'enfance<br/>- la famille<br/>- les personnes handicapées<br/>- les personnes âgées en structure ou à domicile<br/>- la lutte contre l'exclusion<br/>- la santé<br/>Ces emplois nécessitent des savoirs et des aptitudes spécifiques d'où la nécessité de détenir un<br/>Diplôme d'Etat pour accéder à la plupart de ces professions règlementées.<br/>La Région Languedoc -Roussillon Midi Pyrenées finance les Formations des secteurs sanitaire et social<br/>Exclues du dispositif:<br/>***Les personnes non salariées en congé parental qui perçoivent l'allocation de libre choix<br/>d'activité,<br/>***Les personnes inscrites sur des parcours passerelles ou de revalidation.<br/>• sorti de formation initiale depuis plus d'un an<br/>• ne pas avoir suivi de formation qualifiante financée sur fonds publics dans les 12 derniers<br/>mois";
			$array['cost']="Formation totalement financée par la région LRMP";
			if($situation_inscrit)
				if(isInRegionExMidiPyrenees($training_locationpath))
					if($training_niveausortie>=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
						if((hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && in_array($training_formacode,array('43491','43448','43457','43449','43497','43490','43006','43439','43092')) && in_array($training_codecertifinfo,array('82976'))) ||
						   (in_array($training_codecertifinfo,array('82976')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43491')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43448')) && in_array($training_siret,array('13001913600022','77567227207806','18320222500011','26810005400066','26120011700131','26820007800064','26310012500636','26810001300062','26090023800197'))) ||
						   (in_array($training_formacode,array('43457')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43449')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43497')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43490')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43006')) && in_array($training_siret,array('26310012500636'))) ||
						   (in_array($training_formacode,array('43439')) && in_array($training_siret,array('19310057500011'))) ||
						   (in_array($training_formacode,array('43092')) && in_array($training_siret,array('26310012500636'))))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie6'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_occitanie6',$droits);
							else
							if($training_niveausortie==CODENIVEAUSCOLAIRE_CAPBEPCFPA)
							{
								remuRPS($var,'actioncollectiveregion_occitanie6',$droits);
							} else
								remuTEXT($var,'actioncollectiveregion_occitanie6',$droits,'Possibilité de demande de bourse régionale');
						}
		}

		/* Ligne 10 (Occitanie) */
		/* Caractéristiques DE: */
		//Jeunes de mois de 26 ans inscrits ou non inscrits comme DE
		//ou DE inscrit à Pôle Emploi de niveau VI, V ou IV + résidents départements 09,11, 12, 30, 31, 32, 34, 46, 48, 65, 66,
		//-81-82
		//+ sortis de formation intiale depuis 6 mois ou plus

		/* Caractéristiques formation: */
		//F° avec code Financeur Région + localisation sur les départements LR ( dpt 11, 30, 34, 48 et 66)+ intitulé comprend " Action Cap métiers"

		/* Rémunération: */
		//AREF + RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Action Cap Métiers";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d'insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="Le dispositif &laquo;&nbsp;Cap métiers&nbsp;&raquo; a pour finalité de valider un projet professionnel (identifier au moins un secteur d’activité ou un métier) en cohérence avec votre potentiel et en lien avec les réalités du marché du travail.<br/>Ce dispositif s'adresse aux demandeurs d’emploi inscrits à Pôle Emploi (de niveau VI, V ou IV,) et aux jeunes de moins de 26 ans ayant quitté leur formation scolaire depuis plus de 6 mois,<br/>Différents modules seront proposés : module pour découvrir l’entreprise, module pour découvrir les métiers, module pour construire et valider un projet professionnel, ...";
			$array['cost']="Formation totalement financée par la région LRMP";
			if(isInRegionOccitanie($domicilePath))
			{
				if($age<26 || ($situation_inscrit && $niveauscolaire<=CODENIVEAUSCOLAIRE_BAC))
					if(isInRegionLanguedocRoussillon($training_locationpath))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(hasStrings('ACTION CAP METIERS',$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie7'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_occitanie7',$droits);
								else
									remuRPS($var,'actioncollectiveregion_occitanie7',$droits);
							}
			}
		}

		/* Ligne 11 (Occitanie) */
		/* Caractéristiques DE: */
		//Jeunes de mois de 26 ans inscrits ou non inscrits comme DE
		//ou DE inscrit à Pôle Emploi de niveau VI, V ou IV + résidents départements 09,11, 12, 30, 31, 32, 34, 46, 48, 65, 66,
		//-81-82
		//+ sortis de formation intiale depuis 6 mois ou plus

		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//et sur les départements LR (dpt 11, 30, 34, 48 et 66)
		//+ intitulé comprend "ACTION CAP AVENIR"

		/* Rémunération: */
		//AREF + RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="ACTION CAP AVENIR";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="Le dispositif &laquo;&nbsp;Cap Avenir&nbsp;&raquo; a pour finalité de permettre aux stagiaires d'acquérir les premiers gestes professionnels du métier visé à travers un parcours individualisé, afin de poursuivre son parcours en formation qualifiante ou directement en emploi.<br/>L'objectif est de confirmer un projet professionnel en cohérence avec les réalités économiques et le marché du travail régional.<br/>Ce dispositif s'adresse aux demandeurs d’emploi inscrits à Pôle Emploi (de niveau VI, V ou IV,) et aux jeunes de moins de 26 ans ayant quitté leur formation scolaire depuis plus de 6 mois,";
			$array['cost']="Formation totalement financée par la région LRMP";
			if(isInRegionOccitanie($domicilePath))
				if(isInRegionLanguedocRoussillon($training_locationpath))
					if($age<26 || ($situation_inscrit && $niveauscolaire<=CODENIVEAUSCOLAIRE_BAC))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(hasStrings('ACTION CAP AVENIR',$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie8'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_occitanie8',$droits);
								else
									remuRPS($var,'actioncollectiveregion_occitanie8',$droits);
							}
		}

		/* Ligne 12 (Occitanie) */
		/* Caractéristiques DE: */
		//DE + résidents départements 09,11, 12, 30, 31, 32, 34, 46, 48, 65, 66,
		//-81-82
		//+ sortis de formation intiale depuis 6 mois ou plus

		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//sur les départements LR (dpt 11, 30, 34, 48 et 66)
		//et intitulé comprend ' CAP COMPTENCES CLES '

		/* Rémunération: */
		//AREF. Pas de RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Cap Compétences Clés";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="Ce programme vise a lutter efficacement pour la maîtrise des savoirs de base en Région. Des modules de Français langue étrangère (FLE) y sont également disponible. Il s’adresse donc aussi bien aux personnes en situation d’illettrisme qu’aux personnes en difficultés avec la langue française.<br/>Il doit permettre aux stagiaires d’acquérir une aisance certaine dans les savoirs fondamentaux (écrit et oral) afin de poursuivre ensuite leur parcours en formation préqualifiante, qualifiante ou d’accéder à un emploi.";
			$array['cost']="Formation totalement financée par la région LRMP";
			if($situation_inscrit)
				if(isInRegionOccitanie($domicilePath))
					if(isInRegionLanguedocRoussillon($training_locationpath))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(hasStrings('CAP COMPTENCES CLES',$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie9'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_occitanie9',$droits);
								else
									remuTEXT($var,'actioncollectiveregion_occitanie9',$droits);
							}
		}

		/* Ligne 13 (Occitanie) */
		/* Caractéristiques DE: */
		//Demandeur d’emploi inscrit à Pôle Emploi

		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//et sur les départements LR (dpt 11, 30, 34, 48 et 66)
		//+ hors F° avec code Financeur Région et intitulé 'Action cap avenir' ou 'Action cap metier' ou' competences cles' ou 'er2c'

		/* Rémunération: */
		//AREF + RPS

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Formations ERI (Expérimentation, Recherche et Innovation)";
		//	$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
		//	$array['descinfo']="";
		//	$array['info']="Le programme ERI permet de suivre une action de formation présentant un caractère innovant et assurant une insertion durable dans l’emploi.<br/>Les objectifs sont de :<br/>- favoriser la promotion des projets innovants et de leurs résultats en phase expérimentale<br/>- accompagner une dynamique de coopération entre porteurs de projet et, au-delà, en direction de l’ensemble des professionnels régionaux<br/>- faciliter le transfert d’innovation à l’échelle régionale.";
		//	$array['cost']="Formation totalement financée par la région LRMP";
		//	if($situation_inscrit)
		//		if(isInRegionLanguedocRoussillon($training_locationpath))
		//		{
		//			arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie10'=>$array));
		//			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
		//				if(!hasStrings(array('ACTION CAP AVENIR','ACTION CAP METIER','COMPETENCES CLES','ER2C'),$ar['intitule']))
		//					if($allocation_type=='are')
		//						remuAREF($var,'actioncollectiveregion_occitanie10',$droits);
		//					else
		//						remuRPS($var,'actioncollectiveregion_occitanie10',$droits);
		//		}
		//}

		/* Ligne 14 (Occitanie) */
		/* Caractéristiques DE: */
		//Demandeur d’emploi inscrit à Pôle Emploi
		//+ sortis de formation intiale depuis 6 mois ou plus

		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//et sur les départements LR (dpt 11, 30, 34, 48 et 66)
		//+ hors intitulé comprenant "Action Cap Métiers" ou "ACTION CAP AVENIR" ou "CAP COMPTENCES CLES" ou formations sanitaires et sociales (ligne 22)

		/* Rémunération: */
		//AREF + RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional Qualifiant";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="Le Programme Régional Qualifiant propose d'acquérir des compétences reconnues grâce à une certification professionnelle, une qualification, un diplôme, un titre homologué ou un certificat de qualification délivré par une branche professionnelle, ou un perfectionnement en vue d'obtenir un emploi stable.";
			$array['cost']="Formation totalement financée par la région LRMP";
			if($situation_inscrit)
				if(isInRegionLanguedocRoussillon($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_formacode,array('43436','31815','43441','43458','43491','43448','43457','43497','43449','43497','43490','43006','43439','43092','44083','44028','44047','44084','44050','44092','44092','44092','44008')))
							if(!hasStrings(array('ACTION CAP AVENIR','ACTION CAP METIER','COMPETENCES CLES'),$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie11'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_occitanie11',$droits);
								else
									remuRPS($var,'actioncollectiveregion_occitanie11',$droits);
							}
		}

		/* Ligne 15 (Occitanie) */
		/* Caractéristiques DE: */
		//demandeurs d'emploi + - 26 ans + niveau VI, V bis, V ou IV + sortis de formation intiale depuis 6 mois ou plus
		//+ résidents départements 09,11, 12, 30, 31, 32, 34, 46, 48, 65, 66,
		//-81-82

		/* Caractéristiques formation: */
		//F° avec code Financeur Région
		//et sur les départements LR (dpt 11, 30, 34, 48 et 66)
		//+ intitulé 'er2c'

		/* Rémunération: */
		//AREF + RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Les Ecoles Régionales de la deuxième Chance (ER2C)";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="Il s’agit de permettre aux jeunes de 18 à 25 ans sortis du système scolaire depuis plus de 6 mois ou relevant de mesures d’insertion socio-professionnelles, de parvenir à la maîtrise des savoirs de base : lire, écrire, compter, notions d’informatique, notions d’une langue étrangère.<br/>Pendant cette période, les jeunes sont amenés à faire deux ou trois stages dans des entreprises de la région pour découvrir le monde du travail, ses contraintes, ses possibilités.<br/>La formation est très personnalisée, c’est-à-dire que chaque jeune est suivi à l’intérieur de l’école par un « référent » avec qui il peut s’entretenir de ses problèmes tant pédagogiques que personnels. Dans l’entreprise, il est suivi par un tuteur.";
			$array['cost']="Formation totalement financée par la région LRMP";
			
			if($situation_inscrit)
				if(isInRegionOccitanie($domicilePath))
					if(isInRegionLanguedocRoussillon($training_locationpath))
						if($age<26 && $niveauscolaire<=CODENIVEAUSCOLAIRE_BAC)
							if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
								if(hasKeywords(array('ER2C'),$ar['intitule']))
								{
									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie12'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_occitanie12',$droits);
									else
										remuRPS($var,'actioncollectiveregion_occitanie12',$droits);
								}
		}

		/* Ligne 16 (Occitanie) */
		/* Caractéristiques DE: */
		//DE
		//.

		/* Caractéristiques formation: */
		//Formation code financeur Région + localisation départements 11, 30, 34, 48, 66
		//+ formacode:
		//Aide soignant 43436
		//Ambulancier 31815
		//Auxiliaire de puériculture 43441
		//Cadre de santé 43458
		//Ergothérapeute 43491
		//Infirmier 43448
		//Infirmier anesthésiste 43457
		//DTS Imagerie médicale 43497
		//Infirmier de bloc opératoire 43449
		//Manipulateur en électroradiologie médicale 43497
		//Masseur-kinésithérapeute 43490
		//Préparateur en pharmacie hospitalière 43006
		//Puéricultrice 43439
		//Sage-femme 43092
		//Assistant de service social 44083
		//Auxiliaire de vie sociale 44028
		//CAFERUIS 44047
		//Conseiller en économie sociale et familiale 44084
		//Educateur de jeunes enfants 44050
		//educateur spécialisé 44092
		//Educateur technique spécialisé 44092
		//Moniteur educateur 44092
		//Technicien de l'intervention sociale et familiale 44008
		//Ok, vérifié le 19-05
		//'43436','31815','43441','43458','43491','43448','43457','43497','43449','43497','43490','43006','43439','43092','44083','44028','44047','44084','44050','44092','44092','44092','44008'

		/* Rémunération: */
		//AREF - RPS (qqsoit régime ant) si formation de niv V. si niv > à V, se renseigner auprès de son conseiller car soumis à condition. sinon possibilté de demande de Bourses régionales (<a href=\"http://www.midipyrenees.fr/Bourses-sanitaires-et-sociales-3949)\" target=\"_blank\">www.midipyrenees.fr</a>

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Les formations sanitaires et sociales";
			$array['step']="Vous devez au préalable prendre contact avec l'école et avoir réussi les épreuves de sélection pour intégrer une formation du programme régional sanitaire et social.";
			$array['descinfo']="";
			$array['info']="La Région LRMP assure le fonctionnement des centres de formation du paramédical et du travail social et accorde des bourses aux étudiants de ces filières.";
			$array['cost']="Formation totalement financée ou partiellement (erogthérapeute) par la région LRMP";
			if($situation_inscrit)
				if(isInRegionLanguedocRoussillon($training_locationpath))
					if(in_array($training_formacode,array('43436','31815','43441','43458','43491','43448','43457','43497','43449','43497','43490','43006','43439','43092','44083','44028','44047','44084','44050','44092','44092','44092','44008')))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie13'=>$array));
							if($allocation_type=='are')
							{
								remuAREF($var,'actioncollectiveregion_occitanie13',$droits);
							} else
							{
								if($training_niveausortie<=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
									remuRPS($var,'actioncollectiveregion_occitanie13',$droits);
								else
									remuTEXT($var,'actioncollectiveregion_occitanie13',$droits,'Possibilité de demande de bourse régionale');
							}

						}
		}

		/* Ligne 17 (Occitanie) */
		/* Caractéristiques DE: */
		//DE TH

		/* Caractéristiques formation: */
		//F° avec code financeur Agefiph

		/* Rémunération: */
		//AREF. pas de rému , si pas d'aref

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Formations collectives des demandeurs d'emploi handicapés";
		//	$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement qui devra transmettre une fiche de prescription à l'Agefiph (Association de gestion du fonds pour l'insertion des personnes handicapées) au plus tard 3 semaines avant le début de la formation.";
		//	$array['descinfo']="";
		//	$array['info']="L'objectif de ce financement de l'Agefiph est de permettre à une personne handicapée d’acquérir les compétences nécessaires à un accès durable à l’emploi.";
		//	$array['cost']="Formation totalement financée par l'Agefiph";
		//	if($situation_inscrit && $situation_th)
		//		if(isInRegionOccitanie($domicilePath))
		//		{
		//			$display['agefiphcollectif']=$array;
		//			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces))
		//				if($allocation_type=='are')
		//					remuAREF($var,'agefiphcollectif',$droits);
		//				else
		//					remuTEXT($var,'agefiphcollectif',$droits);
		//		}
		//}

		/* Ligne 18 (Occitanie) */
		/* Caractéristiques DE: */
		//DE + >Etre sortis du système scolaire depuis plus de 6 mois,
		//>n'ayant pas bénéficié, dans le délai d'un an, d'une formation financée dans le cadre des programmes pris en charge par la Région Languedoc-Roussillon,

		/* Caractéristiques formation: */
		//F° avec code financeur région + siret suivants:
		//- 19341089100280 (univ P valery montp)
		//- 19660437500317 (univ perpignan)
		//- 49189213900016 (cnam)

		/* Rémunération: */
		//AREF sinon rien

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations des établissements d'enseignement supérieur";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="La région langeudoc -Roussillon Midi Pyrennées finance pour des demandeurs d'emploi (possédant les prérequis définis par l’établissement d'enseignement supérieur) des formations assurées par les centres de formation continue des universitées et vous permet ainsi l'accès à des diplômes nationaux et dans certains cas à des diplômes d'université.<br/>Si vous n'avez pas le Baccalauréat, la région Languedoc -Roussillon Midi Pyrennées finance également le diplôme d'accès aux études universitaires (DAEU) pour pouvoir accéder aux diplômes de l'enseignement supérieur (après le Bac)";
			$array['cost']="Formation totalement financée par la région LRMP<br/>Pour les formations d'enseignement supérieur financées par la Région, des frais ou des droits d'inscription sont à la charge des demandeurs d'emploi : 160 € pour une année.<br/>>Pour le DAEU et la capacité en droit, les frais demandés sont de 80 €.<br/>>Pour les formations du CNAM, les demandeurs d'emploi doivent payer 75 € de droits d'inscription + 1 € par heure d'enseignement.";
			if($situation_inscrit)
				if(isInRegionOccitanie($domicilePath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(in_array($training_siret,array('19341089100280','19660437500317','49189213900016')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie14'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_occitanie14',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_occitanie14',$droits);
						}
		}

		/* Ligne 19 (Occitanie) */
		/* Caractéristiques DE: */
		//Jeunes de 16 à 25 ans,
		//TH de 16 à 29 ans

		/* Caractéristiques formation: */
		//F° avec code financeur Région +siret :
		//btp cfa 31009419800028
		//cci sud form : 18340001900471
		//IRFMA de l'Aude : 18110003300024
		//mfr st hypol : 41953572900026
		//cfa sport mediterranée : 50819727400014
		//IRFMA des PO : 18660001100032
		//Chambre de Métiers et de l'Artisanat du Gard - Antenne de Nîmes 18300001700016
		//CFA Agricole de l'Aude : 20000746600077
		//IRFMA de la Lozère : 18480003500042

		/* Rémunération: */
		//ARE ou ass-f. RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Ecole de l'apprentissage";
			$array['step']="Contacter votre référent emploi (Pôle Emploi, Mission locale, Cap emploi, Services d’insertion du Conseil Départemental, CIDFF, ou APEC) pour valider votre projet de formation et son financement et obtenir la fiche de prescription nécessaire pour participer aux sélections de l'organisme de formation.";
			$array['descinfo']="";
			$array['info']="Ce dispositif permet de mieux connaitre l’univers de l’apprentissage et de vous aider à déterminer un métier et trouver une oriention. il s'agit d'actions pré-qualifiantes, de découvertes des métiers accessibles par la voie de l’apprentissage, de consolidation de projet professionnel ou de recherche d'entreprise pour la signature de votre contrat d’apprentissage";
			$array['cost']="Formation totalement financée par la région LRMP";
			if($age<26 || ($situation_th && $age<30))
				if(isInRegionOccitanie($domicilePath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(in_array($training_siret,array('31009419800028','18340001900471','18110003300024','41953572900026','50819727400014','18660001100032','18300001700016','20000746600077','18480003500042')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_occitanie15'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_occitanie15',$droits);
							else
								remuRPS($var,'actioncollectiveregion_occitanie15',$droits);
						}
		}

		/* Ligne 20 */
		/* Caractéristiques formation: */
		//F) France entière et  certifinfo 54664

		/* Caractéristiques DE: */
		//F) Occitanie et  certifinfo 54664
		// Niveauw scolaire < BAC
		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Prise en charge Permis de conduire B";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.";
			$array['descinfo']="Vous pouvez utiliser votre CPF pour financer tout ou partie de votre permis de conduire catégorie B.<br/>Pôle emploi peut éventuellement vous apporter un complément de financement.<br/>Votre auto-école doit impérativement vous présenter à l'examen de conduite au plus tard six mois après votre inscription.";
			$array['cost']="Formation partiellement ou totalement financée ";
			$costComp="Pôle emploi peut vous apporter un complémenet de financement jusqu'à 1000€.";
			if($situation_inscrit)
				if($niveauscolaire<CODENIVEAUSCOLAIRE_BAC)
					if(in_array($training_codecertifinfo,array(54664)))
					{
						arrayInsertAfterKey($droits,'finindividuelpermisb',$display,array('finindividuelpermisb_occitanie20'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'finindividuelpermisb_occitanie20',$droits);
						else
							remuTEXT($var,'finindividuelpermisb_occitanie20',$droits);
					}
		}

	}

?>
