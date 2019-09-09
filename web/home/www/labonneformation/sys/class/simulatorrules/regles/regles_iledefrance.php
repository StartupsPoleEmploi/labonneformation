<?php
	/* Règles ile de France ***********************************************************************************************/
	function reglesIleDeFrance($quark,$var,&$droits,&$display)
	{
		extract($var);
		/* Ligne 2 (IDF) */
		/* Caractéristiques DE: */
		//tout DE Ile de France

		/* Caractéristiques formation: */
		//Pas d'AIF si code financeur RE, Région, Etat, Coll terr autres, OPCA
		//hors formacodes 44004l, 44083, 44084, 44050, 44092,44072,44008, 43491, 43448, 43497, 43490,43439,43493,43470, 43092, 43476, 44004,4404, 44026.43436, 43441 ou certifinfo 54912, 54913, 54917, 87189, 87185, 87187
		//'44083','44084','44050','44092','44072','44008','43491','43448','43497','43490','43439','43493','43470','43092','43476','44004','44026','43436','43441','54912','54913','54917','87189','87185','87187'

		/* Rémunération: */
		//AREF - RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Aide Individuelle à la Formation (AIF)";
			$array['step']="Contacter votre conseiller référent (Pôle emploi, Mission Locale ou Cap emploi) au moins 15 jours avant le début de la formation.";
			$array['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise la mise en place de toute formation :<br/>- nécessaire pour réaliser votre projet de retour à l’emploi,<br/>- qui ne peut être financée dans le cadre d’autres dispositifs (Action de Formation Conventionnée-AFC, Programme Régional Qualifiant Compétences du Conseil Régional…).<br/>L’AIF est réservée à des projets de formation dont la pertinence est partagée et validée par votre conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail.";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge, sous réserve d'acceptation par Pôle emploi";
			if(isInRegionIDF($domicilePath))
			{
				unset($droits['aif']);
				if($situation_inscrit)
					if(!$training_contratprofessionalisation && !$training_contratapprentissage)
						if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
							if(!in_array($training_formacode,array('15081','43441','44041','44083','44084','44050','44092','44072','44008','43491','43448','43497','43490','43439','43493','43470','43092','43476','44004','44026','43436','43441')) &&
							   !in_array($training_codecertifinfo,array('54912','54913','54917','87189','87185','87187','84385')))
							{
								arrayInsertAfterKey($droits,'aif',$display,array('aif_idf'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'aif_idf',$droits);
								else
									remuRFPE2($var,'aif_idf',$droits);
							}
			}
		}

		/* Ligne 3 (IDF) */
		/* Caractéristiques DE: */
		//être inscrits comme demandeurs d'emploi :
		//- domcilié en IDF,
		//- depuis au moins 3 mois : cette condition s'apprécie sur une période continue ou discontinue de 3 mois au cours des 12 mois précédant la date d'entrée en formation,
		//- ou être inscrit en Contrat de Sécurisation Professionnelle (CSP) à la date d'entrée en formation;
		//- ou être sorti d'un contrat aidé depuis moins de 12 mois,

		/* Caractéristiques formation: */
		//Financement des parcours complet du Diplôme d'Etat d'Aide Soignant (DEAS) ou d'Auxliaire de Puériculture (DEAP).
		//F° conventionnée Région, en IDF
		//+ certifiante
		//+ formacode d'Aide Soignant (43436 ) d'Auxiliaire de puériculture (43441)
		//'43436','43441'

		/* Rémunération: */
		//AREF pour les demandeurs d'emploi indemnisé en ARE, (+ RFF le cas écheant). Régime Public des Stagiaires (RPS) pour les autres publics (bénéficiaires de l'ASS, ATA, RSA ou DE non indemnisé en ARE)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations sanitaires et sociales d'Aide Soignant ou d'Auxiliaire de puériculture Région Ile-de France";
			$array['step']="Vous devez avoir réussi le concours d'entrée pour le diplôme d'Etat d'Aide Soignant ou d'Auxiliaire de puériculture dans l'un des centres financés par le Conseil Régional";
			$array['descinfo']="La prise en charge des frais pédagogiques d'Aide Soignant ou d'Auxiliaire de puériculture est assurée par le Conseil Régional Ile-de-France dans la limite du nombre de places allouées au centre de formation.<br/>Pour tout renseignement sur les Formations sanitaires et sociales du conseil régional Ile-de-France : 01.53.85.73.84";
			$array['info']="";
			$array['cost']="Formation totalement financée *<br/>* dans la limite du nombre de places attribuées au Centre de Formation";
			if($situation_inscrit)
				if(isInRegionIDF($domicilePath))
					if($situation_liccsp)
						if(isInRegionIDF($training_locationpath))
							if($training_certifiante)
								if($situation_personnesortantcontrataide)
									if(in_array($training_formacode,array('43436','43441')))
										if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
										{
											arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_idf'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'actioncollectiveregion_idf',$droits);
											else
												remuRPS($var,'actioncollectiveregion_idf',$droits);
										}
		}

		/* Ligne 4 (IDF) */
		/* Caractéristiques DE: */
		//être inscrits comme demandeurs d'emploi :- domcilié en IDF,
		//- depuis au moins 3 mois : cette condition s'apprécie sur une période continue ou discontinue de 3 mois au cours des 12 mois précédant la date d'entrée en formation

		/* Caractéristiques formation: */
		//F° conventionnée Région en IDF
		//+ certifiante
		//+ Formations du secteur et sociale longues :
		//- Aide médico psychologique, 44004
		//- Assistante de service social, 44083
		//- Conseiller en économie sociale et familiale, 44084
		//- Educateur de jeune enfants, 44050
		//- Educateur spécialisé, 44092
		//- Moniteur éducateur,44072
		//- Technicien de l'intervention sociale et familiale,44008
		//- Ambulancier, 31815
		//- Ergothérapeute, 43491
		//- Infirmier, 43448
		//- Manipulateur d'électroradiologie médicale, 43497
		//- Masseur-kinésithérapeute , 43490 ;
		//- Puéricultrice,43439
		//- Pédicure Podologue, 43493
		//- Psychomotricien,43470
		//- Sage femme - Maïeuticien, 43092
		//- Tecnicien de laboratoire médical, 43476
		//- Accompagnant Educatif et Social : 44004; 44041; 44026.
		//'44004','44083','44084','44050','44092','44072','44008','31815','43491','43448','43497','43490','43439','43493','43470','43092','43476','44004','44041','44026'

		/* Rémunération: */
		//AREF, (+ RFF le ca échéant). Régime Public des Stagiaires (RPS) en relais de l'AREF à la date d'expiration complète des droits à indemnisation au plus tôt à l'entrée en 2ème année pour certaines formations :. - Assistant de service social,. - Conseiller en économie sociale et familiale,. - Educateur de jeune enfants,. - Educateur spécialisé,. - Moniteur éducateur,. - Technicien de l'intervention sociale et familiale,. - Infirmier,. - Manipulateur d'électroradiologie médicale,. - Masseur-kinésithérapeute ,. - Puéricultrice,. - Pédicure Podologue,. - Sage femme - Maïeuticien,. - Tecnicien de laboratoire médical,

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations sanitaires et sociales &laquo;&nbsp;post-bac&nbsp;&raquo; longues Région Ile-de France";
			$array['step']="Vous devez avoir réussi les tests d'admission pour intéger cette formation.<br/>Vous vous rapprocherez du centre de formation pour obtenir des informations sur la prise en charge des frais";
			$array['descinfo']="La prise en charge des frais pédagogiques est assuré par le Conseil Régional dans la limite du nombre de places allouées au centre de formation,<br/>Pour tout renseignement sur les Formations sanitaires et sociales du conseil régional Ile-de-France : 01.53.85.73.84";
			$array['info']="";
			$array['cost']="Formation totalement ou partiellement financée*<br/>* dans la limite du nombre de places attribuées au Centre de Formation";
			if($situation_inscrit)
				if(isInRegionIDF($domicilePath))
					if(isInRegionIDF($training_locationpath))
						if(in_array($training_formacode,array('44004','44083','44084','44050','44092','44072','44008','31815','43491','43448','43497','43490','43439','43493','43470','43092','43476','44004','44041','44026')))
							if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_idf2'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_idf2',$droits);
								else
									remuRPS($var,'actioncollectiveregion_idf2',$droits);
							}
		}

		/* Ligne 5 (IDF) */
		/* Caractéristiques DE: */
		//tout public (dont salarié)

		/* Caractéristiques formation: */
		//Code financeur Région en IDF
		//+ hors formacodes sanitaire et social :
		//- Aide médico psychologique, 44004
		//- Assistante de service social, 44083
		//- Conseiller en économie sociale et familiale, 44084
		//- Educateur de jeune enfants, 44050
		//- Educateur spécialisé, 44092
		//- Moniteur éducateur,44072
		//- Technicien de l'intervention sociale et familiale,44008
		//- Ambulancier, 31815
		//- Ergothérapeute, 43491
		//- Infirmier, 43448
		//- Manipulateur d'électroradiologie médicale, 43497
		//- Masseur-kinésithérapeute , 43490 ;
		//- Puéricultrice,43439
		//- Pédicure Podologue, 43493
		//- Psychomotricien,43470
		//- Sage femme - Maïeuticien, 43092
		//- Tecnicien de laboratoire médical, 43476
		//- Accompagnant Educatif et Social : 44004; 44041; 44026.
		//-Aide Soignant (43436 ) -
		//'Auxiliaire de puériculture (43441)
		//'44004','44083','44084','44050','44092','44072','44008','31815','43491','43448','43497','43490','43439','43493','43470','43092','43476','44004','44041','44026','43436','43441'

		/* Rémunération: */
		//AREF. RPS. - pas de rému RPS si formation d'une durée totale inférieure à 300 heures (à l'exception des Formations parcours d'accès à la qualification= préqualfi code niv de sortie :1, 2 ou 3)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional Qualifiant Compétences";
			$array['step']="Contacter votre conseiller référent (Pôle emploi, Mission Locale ou Cap emploi) avant le début de la formation.<br/>Ou contacter l'organisme de formation.";
			$array['descinfo']="La région Ile-de-France finance un Programme de formations visant à qualifier les actifs (demandeurs d'emploi, jeunes, salariés) sur le territoire francilien.";
			$array['info']="";
			$array['cost']="Formation totalement financée *<br/>* Il peut être demandé un maximum de 150 euros au titre des frais d'inscription,";
			if(isInRegionIDF($training_locationpath))
				if(!in_array($training_formacode,array('44004','44083','44084','44050','44092','44072','44008','31815','43491','43448','43497','43490','43439','43493','43470','43092','43476','44004','44041','44026','43436','43441')))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_idf3'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_idf3',$droits);
						else
						if($training_duration<300)
							remuTEXT($var,'actioncollectiveregion_idf3',$droits);
						else
							remuRPS($var,'actioncollectiveregion_idf3',$droits);
					}
		}

		/* Ligne 6 (IDF) */
		/* Caractéristiques DE: */
		//DE IDF
		//VAE

		/* Caractéristiques formation: */
		//F° IdF - hors code financeur financeur RE, Région, Etat, Coll terr autres, OPCA
		// avec formacode VAE 15064,44591

		/* Rémunération: */
		//Si la fomation est financée en totalité par le Conseil Régional :. - AREF si bénéficiaire de l'ARE Formation,. - les adhérents CSP continuent à bénéficier de l'allocaction de sécurisation professionnelle (ASP). - l'aide à la formation accordée après-jury par la région n'ouvre pas droit à la rémunération publique de stage (RPS) pour les demandeurs d'emploi non indemnisés ou bénéficiaires d'un minima social 5ASS, ATA, AAH ou RSA),. - les demandeurs d'emploi en ASS bénéficient de l'ASS Formation... Si la formation est cofinancée par la Région et par Pöle emploi :. Ce sont les règles de rémunération liées à l'AIF VAE qui s'appliquent : AREF et RFPe

		if(1) //A reactiver prochainement (modif du formulaire)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Chéquier unique VAE Région Ile-de-France";
			$array['step']="Le besoin de formation avant jury est examiné par le certificateur.<br/>Le besoin de formation post jury est indiqué dans la décision du jury de validation.<br/>Contacter le conseiller référent pour le montage du dossier.";
			$array['descinfo']="La Région Ile-de-France finance des modules de formation pour :<br/>- faciliter l'accès à la certification avant le jury : le bloc de compétence nécessaire est identifié par le certificateur,<br/>- vous permettre d'obtenir la certification après le passage devant le jury en cas de validation partielle : la décision du jury mentionne les modules non acquis pour lesquels un formation est nécessaire.<br/>La Région finance les modules de formation avant ou après jury, dans la limite d'un plafond de 1 600 € TTC.<br/>Pôle emploi complétera le financement des actions de formation post-jury si la Région ne couvre pas la totalité du coût de la formation.";
			$array['info']="";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(in_array($training_formacode,array('15064','44591')))
					if(isInRegionIDF($domicilePath))
						if(isInRegionIDF($training_locationpath))
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
								{
									arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_idf'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'finindividuel_idf',$droits);
									else
										remuRPS($var,'finindividuel_idf',$droits);
								}
		}

		// Ligne 11
		//F° : code financeur conseil départemental + lieu de formation  = 75
		//DE + lieu de résidence = 75
		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Paris formation pour l'emploi";
			$array['step']="Contacter votre  conseiller référent  (Pôle emploi, Mission Locale ou Cap emploi) au moins 15 jours avant le début de la formation. Ou contacter l'organisme de formation.";
			$array['descinfo']="La ville de Paris finance un programme de formations pour les demandeurs d'emploi résidant à Paris.";
			$array['info']="";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
					if(isInDepartementParis75($domicilePath) && isInDepartementParis75($training_locationpath))
					{
						arrayInsertAfterKey($droits,'conseildepartementalcollectif',$display,array('conseildepartementalcollectif_idf'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'conseildepartementalcollectif_idf',$droits);
						else
							remuTEXT($var,'conseildepartementalcollectif_idf',$droits);
					}
		}
	}
?>
