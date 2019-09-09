<?php
	/* Règles Centre  *****************************************************************************************************/
	function reglesCentre($var,&$droits,&$display)
	{
		extract($var);

		$logo="/img/logo-centre-mini.png";

		/* Ligne 4 */
		if(1)
		{
			if(isInRegionCentreValDeLoire($domicilePath))
			{
				unset($droits['aif']);
				$array=array();
				$array['title']='Aide Individuelle à la Formation (AIF) Pôle emploi';
				$array['info']='';
				$array['descinfo']="L'aide individuelle à la Formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d'action de formation collectives ne peuvent répondre.<br/>L'AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiera aussi si les conditions du financement sont réunies.";
				$array['step']=array("1) Contacter votre  conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour lui transmettre votre promesse d'embauche et faire valider votre besoin de formation.<br/>2) Télécharger le formulaire de l'AIF <a href=\"http://www.pole-emploi.org/files/live/sites/peorg/files/documents/Formation/formulaire%20devis%20aif%20p%C3%B4le%20emploi%2033206.pdf\">sur pole-emploi.org</a> et transmettez le au centre de formation.<br/>3) Votre projet de formation et la demande de financement doivent être présentés au plus tard 2 semaines avant le début de la formation.");
				$array['cost']='Formation totalement ou partiellement financée';
				/* NOTE ATTENTIONS codes certinfo mis dans formacode => corrigé dans trèfle */
				if(!in_array($training_formacode,array(
				   14406,14407,14426,14428,14447,14449,14477,15450,15452,43425,43428,43438,43442,43444,43445,
				   14403,14405,14406,14407,14411,14414,14420,14423,14426,14428,14435,14441,14447,14450,14454,
				   14456,14475,14477,14484,14489,14490,14496,14497,14498,42001,42020,42030,42032,42034,42052,
				   43418,42101,31812,31816,31804,31805,31811,31795,31826,42811,24049,24130,23603,50545,43454,
				   44054,31801,31812,43409,44002,15094,15073,13030,
				   31795,31768,31706,31715,31747,31826,42811,24049,24130,22603,42101,42102,42103,42105,42106,
				   42108,42108,42109,42110,44067,23014,23015,23016,31828,21546,72412,71954,71909,71905,71906,
				   71904,71908,71907,71901,71903,71902,71910,70154,70254,70201,70202,70203,70204,72554,72501,
				   72503,72502,72754,72740,72742,72741,72710,72713,72712,72714,72711,72730,72720,72721,71854,
				   71803,71802,71804,70354,70310,70311,70312,70313,70320,70321,70322,70323,70330,70332,70333,
				   70454,70401,70402,71454,71401,71402,71408,71404,71410,71403,71409,71405,71407,71406,70554,
				   70501,70502,70503,70504,72854,70654,70601,70602,70604,70603,72254,72201,72202,70754,70701,
				   70702,70710,70712,70711,70703,70704,70705,70706,70707,70854,70801,70954,70901,71054,71001,
				   71002,71154,71110,71111,71115,71113,71114,71140,71141,71142,71143,71144,71120,71121,71123,
				   71125,71126,71127,71130,71131,71135,71132,71134,71133,71754,71701,71254,71201,71202,71203,
				   71204,71205,71354,71301,71302,71303,71304,71305,71554,71501,71502,71503,71504,71505,71506,
				   71507,72054,72001,72002,72154,72101,72102,71654,71630,71632,71631,71633,71670,71620,71660,
				   71661,71640,71610,71613,71614,71611,71612,71616,71615,72654,72601,72603,72602,72354,72310,
				   72311,72313,72312,72320,72321,72330,72331,49616,65960,55673,55674,55675,55676,55677,55803,
				   55804,55805,55806,55807,55808,55809,55810,55811,55812,55813,55814,55815,55816,55817,55818,
				   55819,55820,55822,55821,55823,55824,55825,55826,55827,84700,84449,84700,84715,85312,85338,
				   31801,31812,43409,44002,15094,15073,13030)))
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces))
						if(!in_array($training_codecertifinfo,array('49616','65960')))
							if(!$situation_projetcreationentreprise)
								if(!($training_duration<=20 && $training_adistance))
									if($situation_inscrit && !$situation_salarie)
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										{
											arrayInsertAfterKey($droits,'aif',$display,array('aif_centre'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'aif_centre',$droits);
											else
												remuRFPE2($var,'aif_centre',$droits);
										}
			}
		}

		/* Ligne 5 */
		if(1)
		{
			if(isInRegionCentreValDeLoire($domicilePath))
				if($training_rncp || in_array($training_formacode,array('15064','44591')))
				{
					$array=array();
					$array['title']="Chèque Formation Conseil Régional Centre Val de Loire";
					$array['descinfo']="Ce dispositif individuel permet la prise en charge par le Conseil régional Centre - Val de Loire des coûts de formation, lorsque aucune action collective (financement Conseil régional ou Pôle emploi) qualifiante ne peut être mobilisée.<br/>Cette formation doit être inscrite au Répertoire National des Certifications Professionnelles (RNCP). Elle se traduit par la remise d'un diplôme, d'un certificat de qualification professionnelle, d'un titre professionnel du ministère du travail ou d'un titre homologué.";
					$array['step']=array("Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Vous lui présenterez 2 devis minimum de 2 organismes différents ainsi qu'une lettre de motivation.<br/>Votre projet de formation et la demande de financement par un Chèque Formation doivent être présentés au plus tard 2 semaines avant le début de la formation.");
					$array['cost']="Formation totalement ou partiellement financée";
					$array['logo']=$logo;
					/* NOTE : mauvais code financeur => corrigé dans trefle */
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
						if($training_duration<=1600 || Tools::calcDiffDate($training_dateend,$training_datebegin)<18)
							if(!in_array($training_formacode,
								array(
									42001,42020,42030,42032,42034,42052,43418,14406,14407,14426,14428,14447,14449,14477,15450,15452,43425,
									43428,43438,43442,43444,43445,43438,14403,14405,14406,14407,14411,14414,14420,14423,14426,14428,14435,
									14441,14447,14450,14454,14456,14475,14477,14484,14489,14490,14496,14497,14498,42003,43024,42056,43441,
									44030,44041,44042,15009,42056,42075,42076,42078,42080,42081,43437,44028,15094,15093,15073,13030,43409,
									44002,42837,42814,42880,13275,13254,42022,43054,43042,50150,42025,31801,44575,91502,96129,43070,43440,
									43486,31826,31795,42811,42854,43409,44002,15084,15094,15093,15073,13030,15073,42814,42837,42880,31812,
									31816))
								)
								if($training_rncp || in_array($training_formacode,array(15064,44591)))
									if($situation_inscrit && !$situation_salarie)
									{
										arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_centre'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'finindividuel_centre',$droits);
										else
											remuRPS($var,'finindividuel_centre',$droits);
									}
				}
		}

		/* Ligne 6 */
		if(0)
		{
			if($situation_inscrit)
				if(!$situation_salarie)
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					{
						$array=$display['poleemploicollectif'];
						$array['step']=array("<span class=\"highlight\">Contacter votre conseiller</span> référent emploi (Pôle emploi, Mission Locale ou Cap emploi) ou l'organisme de formation).");
						$array['cost']="Formation totalement financée";

						arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_centre'=>$array));
						unset($droits['poleemploicollectif']); /* Pour la région centre, on doit recalculer spécifiquement poleemploicollectif */
						if($allocation_type=='are')
							remuAREF($var,'poleemploicollectif_centre',$droits);
						else
							remuRFPE2($var,'poleemploicollectif_centre',$droits);
					}
		}

		/* Lignes 7 à 9 */
		if(1)
		{
			/* Ligne 7 */
			if($situation_inscrit)
				if(isInRegionCentreValDeLoire($training_locationpath))
					if(isInRegionCentreValDeLoire($domicilePath))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							#NOTE : code formacode (sanitaire et social) différent sur trefle
							if(!in_array($training_formacode,array(43436,43448,43441,43409,44002,44004,44092,44050,43497,44083,44008,43491,43470,43476,43092,43490,31815,43006,43439,43456)))
								if(!hasStrings(array("VISA"),$ar['intitule']))
								{
									$array=array();
									$array['logo']=$logo;
									$array['title']="Le Programme Régional de Formation (PRF)";
									#NOTE : mauvais descriptif
									$array['descinfo']="La région Centre Val de Loire finance des actions de formation qualifiante, permettant d'obtenir un titre ou un diplôme, des découvertes de métiers, ou des préparations aux concours des métiers sanitaires et sociaux. Le Conseil Régional finance également des actions d'élaboration de projets professionnels...";
									$array['step']=array("<span class=\"highlight\">Contacter votre conseiller</span> référent emploi (Pôle emploi, Mission Locale ou Cap emploi) ou l'organisme de formation");
									$array['cost']="Formation totalement financée";

									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_centre'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_centre',$droits);
									else
										remuRPS($var,'actioncollectiveregion_centre',$droits);
								}
		}
		/* Ligne 8 */
		if(1)
		{
			if(isInRegionCentreValDeLoire($training_locationpath))
				if(isInRegionCentreValDeLoire($domicilePath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(hasStrings(array("VISA"),$ar['intitule']))
						{
							$array=array();
							$array['title']="Programme Régional de Formation Visa (PRF Visa)";
							$array['descinfo']="Les Visas sont des formations personnalisées, gratuites et ouvertes à tous les habitants de la région Centre Val de Loire, en français, maths, langues vivantes, Internet, bureautique, accès à l’emploi... Ces actions, financées par la Région Centre Val de Loire, sont de courte durée et  permettent de mettre à jour vos compétences essentielles et savoirs fondamentaux pour favoriser l’autonomie et l’accès à l’emploi. Elles sont adaptées au niveau et au besoin de chacun.";
							$array['step']=array("Contactez directement l'oganisme de formation pour évaluer votre besoin et accéder à la formation");
							$array['cost']="Formation totalement financée";

							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_centre2'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_centre2',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_centre2',$droits);
						}
		}
		/* Ligne 9 */
		if(1)
		{
			if(isInRegionCentreValDeLoire($training_locationpath))
				if(in_array($training_formacode,array(
					43436,43448,43441,43409,44002,44004,44092,44050,43497,44083,44008,43491,
					43470,43476,43092,43490,31815,43006,43439,43456)))
				{
					$array=array();
					$array['title']="Formations sanitaires et sociales subventionnées par le Conseil régional Centre Val de Loire";
					$array['info']="<a href=\"http://www.etoile.regioncentre.fr/GIP/accueiletoile/seformer/formation/articles-formation/Financer-une-formation-sanitaire-et-sociale#A125823\" target=\"_blank\">Principales conditions</a> pour bénéficier de ce financement : avoir réussi le concours d'entrée dans l'un des 22 instituts de formation agréés par la Région Centre, être étudiant issu du cursus scolaire, demandeur d'emploi, salarié en CDD ou en reconversion professionnelle (hors bénéficiaires d'un CIF ou d'un CFP). L'aide est versée directement à l'école par le Conseil régional, sans avance de frais de la part de l'élève.";
					$array['descinfo']="La région Centre Val de Loire finance quasiment toutes les formations sanitaires et sociales. Ainsi la Région peut prendre en charge leur coût pédagogique.<br/>Seuls les droits d'inscription et les frais de sécurité sociale fixés par arrêté ministériel restent à la charge des inscrits.";
					$array['step']=array("<span class=\"highlight\">Renseignez-vous</span> directement auprès de l'institut de formation choisi.<br/>Si l'institut n'a pas encore été choisi, vous pouvez contacter le N° Vert 0800 222 100 (appel gratuit depuis un poste fixe)");
					$array['cost']="Formation totalement financée";

					arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_centre3'=>$array));
					if($allocation_type=='are')
						remuAREF($var,'actioncollectiveregion_centre3',$droits);
					else
						remuTEXT($var,'actioncollectiveregion_centre3',$droits,"Bourses d'études<br/>Demande à faire <a href=\"https://www.aress.regioncentre.fr/basscep/\" target=\"_blank\">ici</a>");
				}
		}
		/* Ligne 10 */
		if(1)
		{
			if(isInRegionCentreValDeLoire($domicilePath))
			{
				$array=array();
				$array['title']="L'Aide Individuelle à la Formation (AIF) en cas de promesse d'embauche";
				$array['info']='';
				$array['descinfo']="Pour cette formation, l'aide individuelle de Pôle emploi n'est possible que si vous avez une promesse d'embauche ferme. Votre conseiller emploi vérifiera  si les conditions du financement sont réunies. L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.";
				$array['step']=array("1) Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi). Transmettez lui votre promesse d'embauche.<br/>2) Télécharger le formulaire de l'AIF sur <a href=\"http://www.pole-emploi.org/files/live/sites/peorg/files/documents/Formation/formulaire%20devis%20aif%20p%C3%B4le%20emploi%2033206.pdf\">pole-emploi.org</a> et donnez-le au centre de formation.<br/>3) Votre projet de formation et la demande de financement doivent être présentés au plus tard 2 semaines avant le début de la formation.");
				$array['cost']='Formation totalement ou partiellement financée';
				/* NOTE ATTENTIONS codes certinfo mis dans formacode => corrigé dans trèfle */
				if(in_array($training_formacode,array(
				   14406,14407,14426,14428,14447,14449,14477,15450,15452,43425,43428,43438,43442,43444,43445,
				   14403,14405,14406,14407,14411,14414,14420,14423,14426,14428,14435,14441,14447,14450,14454,
				   14456,14475,14477,14484,14489,14490,14496,14497,14498,42001,42020,42030,42032,42034,42052,
				   43418,42101,31812,31816,31804,31805,31811,31795,31826,42811,24049,24130,23603,50545,43454,
				   44054,31801,31812,43409,44002,15094,15073,13030,
				   31795,31768,31706,31715,31747,31826,42811,24049,24130,22603,42101,42102,42103,42105,42106,
				   42108,42108,42109,42110,44067,23014,23015,23016,31828,21546,72412,71954,71909,71905,71906,
				   71904,71908,71907,71901,71903,71902,71910,70154,70254,70201,70202,70203,70204,72554,72501,
				   72503,72502,72754,72740,72742,72741,72710,72713,72712,72714,72711,72730,72720,72721,71854,
				   71803,71802,71804,70354,70310,70311,70312,70313,70320,70321,70322,70323,70330,70332,70333,
				   70454,70401,70402,71454,71401,71402,71408,71404,71410,71403,71409,71405,71407,71406,70554,
				   70501,70502,70503,70504,72854,70654,70601,70602,70604,70603,72254,72201,72202,70754,70701,
				   70702,70710,70712,70711,70703,70704,70705,70706,70707,70854,70801,70954,70901,71054,71001,
				   71002,71154,71110,71111,71115,71113,71114,71140,71141,71142,71143,71144,71120,71121,71123,
				   71125,71126,71127,71130,71131,71135,71132,71134,71133,71754,71701,71254,71201,71202,71203,
				   71204,71205,71354,71301,71302,71303,71304,71305,71554,71501,71502,71503,71504,71505,71506,
				   71507,72054,72001,72002,72154,72101,72102,71654,71630,71632,71631,71633,71670,71620,71660,
				   71661,71640,71610,71613,71614,71611,71612,71616,71615,72654,72601,72603,72602,72354,72310,
				   72311,72313,72312,72320,72321,72330,72331,49616,65960,55673,55674,55675,55676,55677,55803,
				   55804,55805,55806,55807,55808,55809,55810,55811,55812,55813,55814,55815,55816,55817,55818,
				   55819,55820,55822,55821,55823,55824,55825,55826,55827,84700,84449,84700,84715,85312,85338,
				   31801,31812,43409,44002,15094,15073,13030)))
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces))
						if(!in_array($training_codecertifinfo,array('49616','65960')))
							if(!$situation_projetcreationentreprise)
								if($situation_inscrit && !$situation_salarie)
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
									{
										arrayInsertAfterKey($droits,'aif',$display,array('aif_centre2'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'aif_centre2',$droits);
										else
											remuRFPE2($var,'aif_centre2',$droits);
									}
			}
		}
	}
?>
