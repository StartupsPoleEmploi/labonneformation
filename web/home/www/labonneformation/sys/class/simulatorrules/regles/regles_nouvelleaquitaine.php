<?php
	/* Règles Nouvelle Aquitaine ******************************************************************************************/
	function reglesNouvelleAquitaine($quark,$var,&$droits,&$display)
	{
		extract($var);

		$hors_codefinanceur_pe_opca_etat_colter=false;
		if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
			!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
			{
				$hors_codefinanceur_pe_opca_etat_colter=true;
			}

		/* Ligne 3 */
		/* Caractéristiques formation: */
		//Si Bilan de compétences Formacode 15081
		//'15081'

		/* Caractéristiques DE: */
		//
		/* Rémunération: */
		//SI ARE, maintien de l'ARE. Pas de rémunération RFPE ni RPS

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="AIF Bilan de Compétences";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.";
		//	$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre.<br/>L’AIF blian de coméptences finance des préparations de bilan ou d'évaluation des acquis professionnels";
		//	$array['info']="L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence d'établir un bilan de compétences.";
		//	$array['cost']="Formation totalement ou partiellement financée";
		//	$display['AIF Bilan de Compétences']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 4 */
		/* Caractéristiques formation: */
		//Toute F° france entiere , hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//hors formacode :
		//'- préparation concours paramédical 43409
		//- préparation concours social 44002
		//- préparation examen concours 15073
		//- préparation examen concours fonction publique 13030
		//hors durée > 3 ans
		//hors formacodes sanitaire et social France entière ; 43448, 43457, 43456, 43439,43449, 43490, 43491, 43493, 43497,43092, 43436, 43441, 31815, 43006, 44092, 44008, 44084, 44083, 44050, 44092
		//thérapies alternatives (43425), acupuncture (43428), chiropractie (43430), homéopathie (43433), massage bien être et kinésiologie (43445), narcothérapie (43442), phytothérapie (43438), ostéopathie (43443), sophrologie (43444), kinésithérapie (43490), naturothérapie (43442), hypnose (14447), musicothérapie (14407), art thérapie (14426), réflexothérapie (14456)
		//hors certifinfo 65960 (auxiliaire ambulancier), 87805 (permis d'exploitation), 49616 (BAFA) , 23710 (BAFD), 54660 (permis C), 81306 (permis CE ), 78281 (permis A); 54664] (permis B); 54662 (permis D); 81136 (permis DE),
		//hors domaine 150 excepté domaine 150 + code CPF 201
		//'43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456'
		//'65960','87805','49616','23710','54660','81306','78281','54664','54662','81136'

		/* Caractéristiques DE: */
		//Tout DE inscrit, domicilié en Nouvelle-Aquitaine
		//SAUF :
		//- DE en contrat aidé
		//-DE bénéficiaires de l’ARCE (création d'entreprise).
		//- sauf DE sortis de formation initiale depuis moins de 12 mois.
		//Hors TH
		/* Rémunération: */
		//AREF / RFPE

		if(1)
		{
			$array=array();
			$array['pri']="7";
			$array['title']="Aide Individuelle à la Formation (AIF)";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) pour valider votre projet de formation.<br/>Le projet doit être validé et le dossier complet, au plus tard 15 jours avant l'entrée en formation.<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises afin de financer tout ou partie de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels. L'objectif de l'AIF est de permettre un retour rapide à l'emploi aux demandeurs d'emploi DE inscrits.<br/>L’AIF est réservée à des projets de formation permettant un retour à l'emploi rapide et dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il évaluera avec vous cette pertinence par rapport au marché du travail.<br/>Il vérifiera aussi que les conditions du financement sont réunies (dont l'enregistrement de la formation par l'organisme dans les bases CARIF et la validation des critères Qualité définis par le décret n°2015-790 du 30 juin 2015).";
			$array['info']="L'AIF permet uniquement la prise en charge du coût de la formation, hors frais d'inscription, frais annexes, frais de dossier, etc...<br/>Attention : vous ne pouvez pas bénficier d'une AIF pour<br/>- les formations bénéficiant d'une subvention de la Région<br/>- les cursus universitaires<br/>- les formations mises en place par les franchises<br/>- les formations dispensées exclusivement le week-end et/ou en cours du soir<br/>- les formations par correspondance sans regroupements pédagogiques et sans suivis de travaux (ex : travaux rendus et corrigés)<br/>- les modalités pédagogiques à temps très partiel avec une intensité hebdomadaire de moins de 21h,, en raison de leur impact sur la rémunération du DE, sauf pour les formations du Socle de compétences et de connaissances CléA).<br/>- les formations délivrées hors du territoire national par des organismes de formation étrangers non déclarés en France.";
			$array['cost']="Formation totalement ou partiellement financée dans la limite de 3000&nbsp;€<br/>et du montant correspondant à vos droits CPF si la formation est éligible.";
			$array['cost-plafond']=3000;
			if(isInRegionNouvelleAquitaine($domicileRegionPath))
			{
				unset($droits['aif']);
				if(!$situation_personneencourscontrataide)
					if($situation_inscrit && !$situation_th)
						if(!$situation_projetcreationentreprise)
							if($hors_codefinanceur_pe_opca_etat_colter)
								if(!$training_contratprofessionalisation && !$training_contratapprentissage)
									if(!in_array($training_formacode,array('43412','43437','43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456','31826','31795','31768','31747','31709','31715','31717','31708','31828','24069','24066')))
										if($training_dureeenmois && !($training_dureeenmois>3*12))
											if($training_duration<=400 || $training_dureeenmois>12)
												if($training_racineformacode!=150)
													if(!(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar)))
														if(!in_array($training_codecertifinfo,array('65960','87805','49616','23710','54660','81306','78281','54664','54662','81136')))
														{
															arrayInsertAfterKey($droits,'aif',$display,array('aif_nouvelleaquitaine3'=>$array));
															if($allocation_type!='acre')
															{
																if($allocation_type=='are')
																	remuAREF($var,'aif_nouvelleaquitaine3',$droits);
																else
																	remuRFPE2($var,'aif_nouvelleaquitaine3',$droits);
															}
														}
			}
		}

		/* Ligne 5 */
		/* Caractéristiques formation: */
		//Toute F° france entiere avec tag CPF
		//hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//hors formacode :
		//'- préparation concours paramédical 43409
		//- préparation concours social 44002
		//- préparation examen concours 15073
		//- préparation examen concours fonction publique 13030
		//hors durée > 3 ans
		//hors formacodes sanitaire et social France entière ; 43448, 43457, 43456, 43439,43449, 43490, 43491, 43493, 43497,43092, 43436, 43441, 31815, 43006, 44092, 44008, 44084, 44083, 44050, 44092
		//thérapies alternatives (43425), acupuncture (43428), chiropractie (43430), homéopathie (43433), massage bien être et kinésiologie (43445), narcothérapie (43442), phytothérapie (43438), ostéopathie (43443), sophrologie (43444), kinésithérapie (43490), naturothérapie (43442), hypnose (14447), musicothérapie (14407), art thérapie (14426), réflexothérapie (14456)
		//hors certifinfo 65960 (auxiliaire ambulancier), 87805 (permis d'exploitation), 49616 (BAFA) , 23710 (BAFD), 54660 (permis C), 81306 (permis CE ), 78281 (permis A); 54664] (permis B); 54662 (permis D); 81136 (permis DE),
		//hors domaine 150 excepté domaine 150 + code CPF 201
		//'43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456'
		//'65960','87805','49616','23710','54660','81306','78281','54664','54662','81136'

		/* Caractéristiques DE: */
		//DE inscrit, domicilié en Nouvelle-Aquitaine + TH
		//SAUF :
		//- DE en contrat aidé
		//-DE bénéficiaires de l’ARCE (création d'entreprise).
		//- sauf DE sortis de formation initiale depuis moins de 12 mois.
		/* Rémunération: */
		//AREF / RFPE
		if(1)
		{
			$array=array();
			$array['pri']="7";
			$array['title']="Aide Individuelle à la Formation (AIF)";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) pour valider votre projet de formation.<br/>Le projet doit être validé et le dossier complet, au plus tard 15 jours avant l'entrée en formation.<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises afin de financer tout ou partie de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels. L'objectif de l'AIF est de permettre un retour rapide à l'emploi aux demandeurs d'emploi DE inscrits.<br/>L’AIF est réservée à des projets de formation permettant un retour à l'emploi rapide et dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il évaluera avec vous cette pertinence par rapport au marché du travail.<br/>Il vérifiera aussi que les conditions du financement sont réunies (dont l'enregistrement de la formation par l'organisme dans les bases CARIF et la validation des critères Qualité définis par le décret n°2015-790 du 30 juin 2015).";
			$array['info']="L'AIF permet uniquement la prise en charge du coût de la formation, hors frais d'inscription, frais annexes, frais de dossier, etc...<br/>Attention : vous ne pouvez pas bénficier d'une AIF pour<br/>- les formations bénéficiant d'une subvention de la Région<br/>- les cursus universitaires<br/>- les formations mises en place par les franchises<br/>- les formations dispensées exclusivement le week-end et/ou en cours du soir<br/>- les formations par correspondance sans regroupements pédagogiques et sans suivis de travaux (ex : travaux rendus et corrigés)<br/>- les modalités pédagogiques à temps très partiel avec une intensité hebdomadaire de moins de 21h,, en raison de leur impact sur la rémunération du DE (sauf pour les formations du Socle de compétences et de connaissances CléA).<br/>- les formations délivrées hors du territoire national par des organismes de formation étrangers non déclarés en France.";
			$array['cost']="Formation partiellement ou totalement financée dans la limite de 6000&nbsp;€, financés pour moitié par Pôle emploi et pour moitié par l'Agefiph,<br/>et du montant correspondant à vos droits CPF si la formation est éligible.";
			$array['cost-plafond']=6000;
			if(isInRegionNouvelleAquitaine($domicileRegionPath))
			{
				unset($droits['aif']);
				if($situation_inscrit && $situation_th)
					if(!$situation_personneencourscontrataide)
						if($hors_codefinanceur_pe_opca_etat_colter)
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if($training_dureeenmois && !($training_dureeenmois>3*12))
									if($training_duration<=400 || $training_dureeenmois>12)
										if(!in_array($training_formacode,array('43412','43437','43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456','31826','31795','31768','31747','31709','31715','31717','31708','31828','24069','24066')))
											if($training_racineformacode!=150 || (hasCpf($ad,$ar,'TOUTPUBLIC|DE',$domicilePath."|/1/1",array(201))))
												if(!in_array($training_codecertifinfo,array('65960','87805','49616','23710','54660','81306','78281','54664','54662','81136')))
													if($allocation_type!='acre')
													{
														arrayInsertAfterKey($droits,'aif',$display,array('aif_nouvelleaquitaine'=>$array));
														if($allocation_type=='are')
															remuAREF($var,'aif_nouvelleaquitaine',$droits);
														else
															remuRFPE2($var,'aif_nouvelleaquitaine',$droits);
													}
			}
		}

		/* Ligne 6 */
		/* Caractéristiques formation: */
		//Toute F° france entiere avec tag CPF
		//hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//hors formacode :
		//- préparation concours paramédical 43409
		//- préparation concours social 44002
		//- préparation examen concours 15073
		//- préparation examen concours fonction publique 13030
		//hors durée > 3 ans
		//hors formacodes sanitaire et social France entière ; 43448, 43457, 43456, 43439,43449, 43490, 43491, 43493, 43497,43092, 43436, 43441, 31815, 43006, 44092, 44008, 44084, 44083, 44050, 44092
		//thérapies alternatives (43425), acupuncture (43428), chiropractie (43430), homéopathie (43433), massage bien être et kinésiologie (43445), narcothérapie (43442), phytothérapie (43438), ostéopathie (43443), sophrologie (43444), kinésithérapie (43490), naturothérapie (43442), hypnose (14447), musicothérapie (14407), art thérapie (14426), réflexothérapie (14456)
		//hors certifinfo 65960 (auxiliaire ambulancier), 87805 (permis d'exploitation), 49616 (BAFA) , 23710 (BAFD), 54660 (permis C), 81306 (permis CE ), 78281 (permis A); 54664] (permis B); 54662 (permis D); 81136 (permis DE),
		//hors domaine 150 excepté domaine 150 + code CPF 201
		//'43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456'
		//'65960','87805','49616','23710','54660','81306','78281','54664','54662','81136'

		/* Caractéristiques DE: */
		//Tout DE inscrit, domicilié en Nouvelle-Aquitaine avec CPF > 0
		//SAUF :
		//- DE en contrat aidé
		//-DE bénéficiaires de l’ARCE (création d'entreprise).
		//- sauf DE sortis de formation initiale depuis moins de 12 mois.
		//- TH
		//- De avec niveau de qualification < ou = niveau V ,
		//- DE inscrit depuis 12 mois et plus o
		//- DE de 50 ans et +
		/* Rémunération: */
		//AREF / RFPE

		//if(0)
		//{
		//	$array=array();
		//	$array['pri']="7";
		//	$array['title']="AIF cumulée avec votre CPF";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) pour valider votre projet de formation.<br/>Le projet doit être validé et le dossier complet, au plus tard 15 jours avant l'entrée en formation.<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises afin de financer tout ou partie de la formation.";
		//	$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels. L'objectif de l'AIF est de permettre un retour rapide à l'emploi aux demandeurs d'emploi DE inscrits.<br/>L’AIF est réservée à des projets de formation permettant un retour à l'emploi rapide et dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il évaluera avec vous cette pertinence par rapport au marché du travail.<br/>Il vérifiera aussi que les conditions du financement sont réunies (dont l'enregistrement de la formation par l'organisme dans les bases CARIF et la validation des critères Qualité définis par le décret n°2015-790 du 30 juin 2015).";
		//	$array['info']="L'AIF permet uniquement la prise en charge du coût de la formation, hors frais d'inscription, frais annexes, frais de dossier, etc...<br/>Attention : vous ne pouvez pas bénficier d'une AIF pour<br/>- les formations bénéficiant d'une subvention de la Région<br/>- les cursus universitaires<br/>- les formations mises en place par les franchises<br/>- les formations dispensées exclusivement le week-end et/ou en cours du soir<br/>- les formations par correspondance sans regroupements pédagogiques et sans suivis de travaux (ex : travaux rendus et corrigés)<br/>- les modalités pédagogiques à temps très partiel avec une intensité hebdomadaire de moins de 21h,, en raison de leur impact sur la rémunération du DE (sauf pour les formations du Socle de compétences et de connaissances CléA).<br/>- les formations délivrées hors du territoire national par des organismes de formation étrangers non déclarés en France.";
		//	$array['cost']="Formation totalement ou partiellement financée dans la limite de 3000&nbsp;€<br/>et du montant correspondant à vos droits CPF si la formation est éligible.";
		//	if($situation_inscrit && isInRegionNouvelleAquitaine($domicileRegionPath) && $situation_creditheurescpf>0)
		//	{
		//		if(!$situation_projetcreationentreprise && !$situation_th && $age<50)
		//			if($niveauscolaire>=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
		//				if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
		//					if($hors_codefinanceur_pe_opca_etat_colter)
		//						if($training_dureeenmois && !($training_dureeenmois>3*12))
		//							if($training_duration<=400 || $training_dureeenmois>12)
		//								if(!$training_contratprofessionalisation && !$training_contratapprentissage)
		//									if(!in_array($training_formacode,array('43412','43437','43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456')))
		//										if($training_dureeenmois && !($training_dureeenmois>3*12))
		//											if($training_racineformacode!=150 || hasCpf($ad,$ar,'TOUTPUBLIC|DE',$domicilePath."|/1/1/",array(201)))
		//												if(!in_array($training_codecertifinfo,array('65960','87805','49616','23710','54660','81306','78281','54664','54662','81136')))
		//													if($allocation_type!='acre')
		//													{
		//														arrayInsertAfterKey($droits,'aif',$display,array('aif_nouvelleaquitaine2'=>$array));
		//														if($allocation_type=='are')
		//															remuAREF($var,'aif_nouvelleaquitaine2',$droits);
		//														else
		//															remuRFPE2($var,'aif_nouvelleaquitaine2',$droits);
		//													}
		//	}
		//}

		/* Ligne 7 */
		/* Caractéristiques formation: */
		//F° avec code financeur Région et lieux = Dordogne, Gironde (33),Landes (40),Lot-et-Garonne (47),Pyrénées-Atlantiques (64)
		//hors formacodes '43448','43457','43456','43439','43449','43490','43491','43493','43092','43497','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','44092'

		/* Caractéristiques DE: */
		//Salariés ou demandeurs d'emploi de la région ex-Aquitaine (dépt 24, 33, 40, 47, 64) ayant adhéré à un dispositif d'accompagnement suite à un licenciement économique.
		/* Rémunération: */
		//AREF / RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional de Formation";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) qui étudiera avec vous votre projet de formation. Si votre projet est validé, il procèdera à une pré-inscription auprès de l'organisme de formation.<br/>Ensuite l'organisme de formation auprès duquel vous candidatez vous recevra pour passer un entretien et/ou des tests de sélection. Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises.";
			$array['descinfo']="Le Programme Régional de Formation regroupe toutes les formations financées par la Région et Pôle emploi en Dordogne, Gironde (33), Landes (40), Lot-et-Garonne (47) et<br/>Pyrénées-Atlantiques (64). Elles répondent aux besoins des entreprises du territoire.";
			$array['info']="Le Programme Régional de Formation est prescrit à tout demandeur d'emploi qui fait état d'un besoin de formation validée par son conseiller.<br/>La demande de formation doit s'inscrire dans le cadre d'un projet professionnel de retour à l'emploi, validé par votre conseiller à l'occasion d'un Conseil en Evolution Professionnelle. Il partagera avec vous la pertinence du projet au regard du marché du travail et de votre profil.";
			$array['cost']="Formation totalement financée";
			if(isInRegionExAquitaine($trainingRegionPath))
			{
				if($situation_inscrit)
					if(!in_array($training_formacode,array('43448','43457','43456','43439','43449','43490','43491','43493','43092','43497','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','44092')))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_nouvelleaquitaine',$droits);
							elseif($training_duration>=150)
								remuRPS($var,'actioncollectiveregion_nouvelleaquitaine',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine',$droits);
						}
			}
		}

		/* Ligne 8 */
		/* Caractéristiques formation: */
		//F° code financeur Région localisée en Charente (16), Charente-Maritime (17), Deux-Sèvres(79) et Vienne (86),
		//hors formacodes : 44083; 44050; 44008 et SIRET 77571615200019
		//hors certifinfo 20120, 23715; 53277, 87185; 87187; 87189; 53865 et SIRET 77571615200019
		//hors certifinfo 54912 et SIRET 20005535800150
		//hors certifinfo 54912 et SIRET 261 600 340 00010
		//• formacode 43092 et SIRET 19860856400706
		//• formacode 43436 et SIRET Diplôme d’État d'Aide Soignant délivré par les Instituts de Formation d'Aide-Soignant de La Rochelle (siret 20004783500018), Saintes (26170002500230), Rochefort (SIRET 26170033000176), Poitiers (20005535800150), Angoulême (26160034000000) , Niort (SIRET 526790001700059) et Thouars (siret 267 901 213 00046) et autres établissements agrées (86 : lycée le Dolmen (SIRET 198 600 397 00014) et Saint Jacques de Compostelle (SIRET 781 564 398 00035)- 17 : Lycée Val de Charente Greta Aunis( SIRET 19860037100043)
		//• formacode 43441 + SIRET 26790001700000 Diplôme d’État d'Auxiliaire de Puériculture délivré par l’Institut de Formation des Auxiliaires de Puériculture (IFAP) de Niort -
		//• formacode 43448 + SIRET 20004783500018 (La Rochelle)
		//formacode 43448 + SIRET 77567227229743 (La Couronne)
		//formacode 43448 + SIRET 26170002500230 (Saintes)
		//formacode 43448 + SIRET 26170033000176 (Rochefort)
		//formacode 43448 + SIRET 26790001700059 (Niort)
		//formacode 43448 + SIRET 26790121300012 (Thouars)
		//formacode 43448 + SIRET 20005535800150 (Poitiers) Diplôme d'Etat d'Infirmier délivré par les Instituts de Formation en Soins Infirmiers (IFSI) de , Thouars et Poitiers -
		//• formacode 43490 + siret 20005535800150 Diplôme d'Etat de Masseur- Kinésithérapeute délivré par l'Ecole de Masseur Kinésithérapeute (IFMK) de Poitiers -
		//• formacode 43497+ siret 20005535800150 Diplôme d'Etat de Manipulateur d'Electroradiologie Médicale (IFMEM) délivré par l'Institut de Formation de Manipulateur en Electroradiologie médicale de Poitiers -
		//• 43491 + siret 20005535800150 Diplôme d’Etat d’Ergothérapeute (IFERGO) délivré par l’Institut de Formation d’Ergothérapie de Poitiers -
		//'44083','44050','44008','77571','61520','20120','23715','53277','87185','87187','87189','53865','77571','61520','54912','20005','53580','54912','00010','43092','19860','85640','43436','20004','78350','26170','00250','26170','03300','20005','53580','26160','03400','52679','00017','00059','00046','00014','00035','19860','03710','43441','26790','00170','43448','20004','78350','43448','77567','22722','43448','26170','00250','43448','26170','03300','43448','26790','00170','43448','26790','12130','43448','20005','53580','43490','20005','53580','43497','20005','53580','43491','20005','53580'

		/* Caractéristiques DE: */
		//DE France entière :
		//< niveau V bis CAP/BEP non validé
		//ou < IV général (bac général),
		//SI niveau > IV général ou niveau V professionel --> message spécifique : "Si votre formation initiale est considérée comme obsolète, du fait de l’évolution du marché du travail ou si vous n'avez pas exercé une activité en rapport aveccette qualification depuis au moins 2 ans, vous pouvez vous positionner sur une formation financée par la Région"
		/* Rémunération: */
		//AREF- RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional de Formation (PRF)";
			$array['step']="Contacter votre conseiller Référent (Pôle emploi, Mission Locale, Opérateur Placement Spécialisé, ex Cap Emploi, CIDFF, conseiller en insertion du Conseil Départemental, chargés de mission VAE du Conseil Régional) afin que votre projet de formation soit validé,<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises. Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises.";
			$array['descinfo']="Le Conseil régional finance en totalité les coûts pédagogiques des formations collectives du Programme Régional de Formation (PRF).<br/>La mobilisation de ces financements sera prioritaire par rapport à un financement individuel (Aide Individuelle à la Formation).";
			$array['info']="Le Programme Régional de Formation est prescrit à tout demandeur d'emploi, qui fait état d'un besoin de formation validée par son conseiller.<br/>La demande de formation doit s'inscrire dans le cadre d'un projet professionnel de retour à l'emploi, validé par votre conseiller à l'occasion d'un Conseil en Evolution Professionnelle. Il partagera avec vous la pertinence du projet au regard du marché du travail et de votre profil.<br/>Il vous informera sur les conditions de rémunération propres à votre situation au jour d'entrée en formation.Les stagiaires bénéfiiant de la Rémunération Publique de Stage peuvent bénficier de bonifications<br/>►si la formation concernent les domaines du bâtiment gros œuvre, travail des métaux, mécanique et travail du bois<br/>►pour les pères ou mères élevant seuls 1 ou plusieurs enfants de moins de 21 ans, y compris en cas de garde alternée (sur justificatifs) (+ 100€:mois)<br/>►pour une formation préparant l'accès à une certification professionnelle d'une durée de plus de 70h, au bout du 6ème jour de présence.(prime forfaitaire de 150€)<br/>A noter : une contribution partielle aux frais de restauration et d'hébergement peut être demandée au stagiaire dans la limite de 1,50€ par repas et de 3€ par nuitée.";
			$array['cost']="Formation totalement financée.";
			if($situation_inscrit)
				if(isInRegionExPoitouChartente($training_locationpath))
				{
					//if($niveauscolaire<CODENIVEAUSCOLAIRE_BAC)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!(in_array($training_formacode,array(44083,44050,44008)) && $training_siret=='77571615200019') &&
						   !(in_array($training_codecertifinfo,array(20120,23715,53277,87185,87187,87189,53865)) && $training_siret=='77571615200019') &&
						   !(in_array($training_codecertifinfo,array(54912)) && $training_siret=='20005535800150') &&
						   !(in_array($training_codecertifinfo,array(54912)) && $training_siret=='26160034000010') &&
						   !(in_array($training_formacode,array(43092)) && $training_siret=='19860856400706') &&
						   !(in_array($training_formacode,array(43436)) && in_array($training_siret,array('20004783500018','26170002500230','26170033000176','20005535800150','26160034000000','526790001700059','26790121300046','19860039700014','78156439800035','19860037100043'))) &&
						   !(in_array($training_formacode,array(43441)) && $training_siret=='26790001700000') &&
						   !(in_array($training_formacode,array(43448)) && in_array($training_siret,array('20004783500018','77567227229743','26170002500230','26170033000176','26790001700059','26790121300012','20005535800150'))) &&
						   !(in_array($training_formacode,array(43490,43497,43491)) && in_array($training_siret,array('20005535800150'))))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine2'=>$array));
								//if($niveauscolaire>=CODENIVEAUSCOLAIRE_BAC) $display['actioncollectiveregion_nouvelleaquitaine2']['cost']="Si votre formation initiale est considérée comme obsolète, du fait de l’évolution du marché du travail ou si vous n'avez pas exercé une activité en rapport avec cette qualification depuis au moins 2 ans, la formation est totalement financée par la région.";
								if($niveauscolaire>=CODENIVEAUSCOLAIRE_BAC) $array['descinfo'].="<br/>Si votre formation initiale est considérée comme obsolète, du fait de l’évolution du marché du travail ou si vous n'avez pas exercé une activité en rapport avec cette qualification depuis au moins 2 ans, la formation est totalement financée par la région.";
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_nouvelleaquitaine2',$droits);
								elseif($training_duration>=150)
									remuRPS($var,'actioncollectiveregion_nouvelleaquitaine2',$droits);
								else
									remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine2',$droits);
							}
				}
		}

		/* Ligne 9 */
		/* Caractéristiques formation: */
		//formacodes : 44083; 44050; 44008 et SIRET 77571615200019
		//certifinfo 20120, 23715; 53277, 87185; 87187; 87189; 53865 et SIRET 77571615200019
		//certifinfo 54912 et SIRET 20005535800150
		//certifinfo 54912 et SIRET 261 600 340 00010
		//• formacode 43092 et SIRET 19860856400706
		//• formacode 43436 et SIRET Diplôme d’État d'Aide Soignant délivré par les Instituts de Formation d'Aide-Soignant de La Rochelle (siret 20004783500018), Saintes (26170002500230), Rochefort (SIRET 26170033000176), Poitiers (20005535800150), Angoulême (26160034000000) , Niort (SIRET 526790001700059) et Thouars (siret 267 901 213 00046) et autres établissements agrées (86 : lycée le Dolmen (SIRET 198 600 397 00014) et Saint Jacques de Compostelle (SIRET 781 564 398 00035)- 17 : Lycée Val de Charente Greta Aunis( SIRET 19860037100043)
		//• formacode 43441 + SIRET 26790001700000 Diplôme d’État d'Auxiliaire de Puériculture délivré par l’Institut de Formation des Auxiliaires de Puériculture (IFAP) de Niort -
		//• formacode 43448 + SIRET 20004783500018 (La Rochelle)
		//formacode 43448 + SIRET 77567227229743 (La Couronne)
		//formacode 43448 + SIRET 26170002500230 (Saintes)
		//formacode 43448 + SIRET 26170033000176 (Rochefort)
		//formacode 43448 + SIRET 26790001700059 (Niort)
		//formacode 43448 + SIRET 26790121300012 (Thouars)
		//formacode 43448 + SIRET 20005535800150 (Poitiers) Diplôme d'Etat d'Infirmier délivré par les Instituts de Formation en Soins Infirmiers (IFSI) de , Thouars et Poitiers -
		//• formacode 43490 + siret 20005535800150 Diplôme d'Etat de Masseur- Kinésithérapeute délivré par l'Ecole de Masseur Kinésithérapeute (IFMK) de Poitiers -
		//• formacode 43497+ siret 20005535800150 Diplôme d'Etat de Manipulateur d'Electroradiologie Médicale (IFMEM) délivré par l'Institut de Formation de Manipulateur en Electroradiologie médicale de Poitiers -
		//• 43491 + siret 20005535800150 Diplôme d’Etat d’Ergothérapeute (IFERGO) délivré par l’Institut de Formation d’Ergothérapie de Poitiers -
		//'44083','44050','44008','77571','61520','20120','23715','53277','87185','87187','87189','53865','77571','61520','54912','20005','53580','54912','00010','43092','19860','85640','43436','20004','78350','26170','00250','26170','03300','20005','53580','26160','03400','52679','00017','00059','00046','00014','00035','19860','03710','43441','26790','00170','43448','20004','78350','43448','77567','22722','43448','26170','00250','43448','26170','03300','43448','26790','00170','43448','26790','12130','43448','20005','53580','43490','20005','53580','43497','20005','53580','43491','20005','53580'

		/* Caractéristiques DE: */
		//DE non démissionnaires France entière
		/* Rémunération: */
		//AREF sinon RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme régional sanitaire et social";
			$array['step']="Contacter votre conseiller emploi (Pôle emploi, Mission Locale, Opérateur Placement Spécialisé, ex Cap Emploi, CIDFF, conseiller en insertion du Conseil Départemental, chargés de mission VAE du Conseil Régional) afin que votre projet de formation soit validé,<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises.";
			$array['descinfo']="Le Conseil régional prend en charge les coûts pédagogiques (hors frais de scolarité) des formations relevant du domaine sanitaire et social délivrées dans un institut ou une école de formation du secteur paramédical et de santé agréée ou dans un établissement de formation sociale agréé.";
			$array['info']="";
			$array['cost']="Prise en charge des coûts pédagogiques par la Région";
			if($situation_inscrit)
				if(isInRegionExPoitouChartente($training_locationpath))
				{
					//if(!$situation_nondemissionaire)
					{
						if((in_array($training_formacode,array(44083,44050,44008)) && $training_siret=='77571615200019') ||
						   (in_array($training_codecertifinfo,array(20120,23715,53277,87185,87187,87189,53865)) && $training_siret=='77571615200019') ||
						   (in_array($training_codecertifinfo,array(54912)) && $training_siret=='20005535800150') ||
						   (in_array($training_codecertifinfo,array(54912)) && $training_siret=='26160034000010') ||
						   (in_array($training_formacode,array(43092)) && $training_siret=='19860856400706') ||
						   (in_array($training_formacode,array(43436)) && in_array($training_siret,array('20004783500018','26170002500230','26170033000176','20005535800150','26160034000000','526790001700059','26790121300046','19860039700014','78156439800035','19860037100043'))) ||
						   (in_array($training_formacode,array(43441)) && $training_siret=='26790001700000') ||
						   (in_array($training_formacode,array(43448)) && in_array($training_siret,array('20004783500018','77567227229743','26170002500230','26170033000176','26790001700059','26790121300012','20005535800150'))) ||
						   (in_array($training_formacode,array(43490,43497,43491)) && in_array($training_siret,array('20005535800150'))) ||
						   (in_array($training_formacode,array(43497)) && in_array($training_siret,array('20005535800150'))))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine3'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_nouvelleaquitaine3',$droits);
							elseif($training_duration>=150)
								remuRPS($var,'actioncollectiveregion_nouvelleaquitaine3',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine3',$droits);
						}
					}
				}
		}

		/* Ligne 10 */
		/* Caractéristiques formation: */
		//code financeur Région + localisation Corrèze (19)· Creuse (23) · Haute-Vienne (87)
		//hors formations sanitaires et sociales listées ligne 11

		/* Caractéristiques DE: */
		//tout DE France entière
		/* Rémunération: */
		//AREF RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional de Formation (PRF)";
			$array['step']="Contacter votre conseiller Référent (Pôle emploi, Mission Locale, Opérateur Placement Spécialisé, ex Cap Emploi) afin que votre projet de formation soit validé.<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises.";
			$array['descinfo']="Le Conseil régional finance en totalité les coûts pédagogiques des formations collectives du programme régional de Formation.<br/>La mobilisation de ces financements sera prioritaire par rapport à un financement individuel (Aide Individuelle à la Formation).<br/>Le Programme Régional de Formation est composé de plusieurs dispositifs, dont certains présentent des spécificités :<br/>► L'Offre de Formation Qualifiante (OFQ) pour lequel la prescription se fait après validation du projet par le conseiller référent.<br/>► L'Habilitation de Service public, formations non qualifiantes visant l'acquisition de compétences clés. La prescription se fait après validation du projet par le conseiller référent.";
			$array['info']="Les formations issues du Programme de formation régional sont prescrites à tout demandeur d'emploi qui fait état d'un besoin de formation validée par son conseiller.<br/>La pertinence de votre demande de formation doit s'inscrire dans le cadre d'un projet professionnel de retour à l'emploi. Ce projet doit être validé par votre conseiller dans le cadre de votre accompagnement. Votre conseiller partagera avec vous dans le cadre d'un Conseil en Evolution Professionnelle, la pertinence de celui ci au regard du marché du travail mais également de votre dossier.<br/>Il vous informera sur les conditions de rémunération propres à votre situation au jour d'entrée en formation.";
			$array['cost']="Le coût pédagogique de la formation est totalement pris en charge par le Conseil régional.";
			if($situation_inscrit)
				if(isInRegionExLimousin($training_locationpath))
				{
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!((in_array($training_siret,array('77567227230717')) && in_array($training_formacode,array(43441,43480))) ||
						   (in_array($training_siret,array('26190310800015')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26192720600027')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26192750300019')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26871540600040','26871870700014')) && in_array($training_formacode,array(43436))) ||
						   (in_array($training_siret,array('26870851800272')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26230960200072')) && in_array($training_formacode,array(43436))) ||
						   (in_array($training_siret,array('26870851800272')) && in_array($training_codecertifinfo,array(49332))) ||
						   (in_array($training_siret,array('26870851800306')) && in_array($training_formacode,array(43448))) ||
						   (in_array($training_siret,array('26870851800280')) && in_array($training_formacode,array(31815,43449)) && in_array($training_codecertifinfo,array(78851))) ||
						   (in_array($training_siret,array('77567227230717')) && in_array($training_formacode,array(43448,43480))) ||
						   (in_array($training_siret,array('19870669900420')) && in_array($training_formacode,array(43092,43491,43480))) ||
						   (in_array($training_siret,array('77807079700049')) && in_array($training_formacode,array(44050)) && in_array($training_codecertifinfo,array(87185,87187,87189,20120,53277,53865))) ||
						   (in_array($training_siret,array('77567227230717')) && in_array($training_formacode,array(44083))) ||
						   (in_array($training_siret,array('77807079700015')) && in_array($training_codecertifinfo,array(73378))) ||
						   (in_array($training_siret,array('77807079700015')) && in_array($training_formacode,array(44008)))
						   ))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine4'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_nouvelleaquitaine4',$droits);
							elseif($training_duration>=150)
								remuRPS($var,'actioncollectiveregion_nouvelleaquitaine4',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine4',$droits);
						}
				}
		}

		/* Ligne 11 */
		/* Caractéristiques formation: */
		//Corrèze (19)· Creuse (23) · Haute-Vienne (87)
		//siret 77567227230717 et formacode 43441 (Auxiliaire de puériculture), formacode 43480 (Masseur-kinésithérapeute)
		//ou
		//SIRET 26190310800015 (IFSI Brive) et formacode 43436 (Aide-soignant), 43448 (Infirmier),
		//ou
		//SIRET 26192720600027 (IFSI Tulle) et formacode 43436 (Aide-soignant), 43448 (Infirmier)
		//ou
		//SIRET 26192750300019 (IFSI Ussel) et formacode 43436 (Aide-soignant), 43448 (Infirmier)
		//ou
		//SIRET 26230960200072 (IFSI Guéret) et formacode 43436 (Aide-soignant), 43448 (Infirmier)
		//ou
		//SIRET 26871540600040 (IFAS Saint-Junien) et formacode 43436 (Aide-soignant)
		//ou
		//SIRET 26871870700014 (IFAS St Yriex La Perche) et formacode 43436 (Aide-soignant)
		//ou
		//siret 26870851800272 (CHU Limoges) et formacode 43436 (Aide-soignant), certifinfo 49332 Cadre de santé
		//ou
		//siret 26870851800306 CHU Limoges et formacode 43448 (Infirmier),
		//ou
		//siret 26870851800280 CHU Limoges et formacode 31815 (Ambulancier), certifinfo 78851 (Infirmier anesthésiste), formacode 43449 (Infirmier de bloc opératoire)
		//ou
		//SIRET 77567227230717 (IRFSS Limoges) et formacode 43448 (Infirmier), formacode 43480 (Masseur-kinésithérapeute)
		//ou
		//SIRET 19870669900420 (Limoges) et formacode 43092 (Sage-femme); 43491 (Ergothérapeute); formacode 43480 (Masseur-kinésithérapeute)
		//Secteur social :
		//siret 77807079700049 (Polaris Isle - Brive) et certinfo 87185, 87187; 87189, 20120, certifinfo 53277, certifinfo 53865, formacode 44050
		//ou
		//siret77567227230717 et formacode 44083 Assistant de service social
		//SIRET 77807079700015 et certifinfo 73378 Conseiller en économie sociale et familiale
		//ou
		//SIRET 77807079700015 et formacode 44008 Technicien de l’intervention sociale et familiale
		//'77567','22723','43441','43480','26190','31080','43436','43448','26192','72060','43436','43448','26192','75030','43436','43448','26230','96020','43436','43448','26871','54060','43436','26871','87070','43436','26870','85180','43436','49332','26870','85180','43448','26870','85180','31815','78851','43449','77567','22723','43448','43480','19870','66990','43092','43491','43480','77807','07970','87185','87187','87189','20120','53277','53865','44050','77567','22723','44083','77807','07970','73378','77807','07970','44008'

		/* Caractéristiques DE: */
		//DE non démissionnaires France entière
		/* Rémunération: */
		//AREF RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme régional sanitaire et social";
			$array['step']="Contacter votre conseiller Référent (Pôle emploi, Mission Locale, Opérateur Placement Spécialisé, ex Cap Emploi) afin que votre projet de formation soit validé.<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises.";
			$array['descinfo']="Les coûts pédagogiques et la rémunération des formations du domaine &laquo;&nbsp;Sanitaire et social&nbsp;&raquo; dont sont pris en charge par la Région lorsqu'elles ont lieu dans l'un des instituts et/ou écoles agréés par la Région.";
			$array['info']="";
			$array['cost']="Le coût pédagogique de la formation est totalement pris en charge par le Conseil régional.";
			if($situation_inscrit)
				//if($situation_nondemissionaire)
					if(isInRegionExLimousin($training_locationpath))
					{
						if((in_array($training_siret,array('77567227230717')) && in_array($training_formacode,array(43441,43480))) ||
						   (in_array($training_siret,array('26190310800015')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26192720600027')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26192750300019')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26871540600040','26871870700014')) && in_array($training_formacode,array(43436))) ||
						   (in_array($training_siret,array('26870851800272')) && in_array($training_formacode,array(43436,43448))) ||
						   (in_array($training_siret,array('26230960200072')) && in_array($training_formacode,array(43436))) ||
						   (in_array($training_siret,array('26870851800272')) && in_array($training_codecertifinfo,array(49332))) ||
						   (in_array($training_siret,array('26870851800306')) && in_array($training_formacode,array(43448))) ||
						   (in_array($training_siret,array('26870851800280')) && in_array($training_formacode,array(31815,43449)) && in_array($training_codecertifinfo,array(78851))) ||
						   (in_array($training_siret,array('77567227230717')) && in_array($training_formacode,array(43448,43480))) ||
						   (in_array($training_siret,array('19870669900420')) && in_array($training_formacode,array(43092,43491,43480))) ||
						   (in_array($training_siret,array('77807079700049')) && in_array($training_formacode,array(44050)) && in_array($training_codecertifinfo,array(87185,87187,87189,20120,53277,53865))) ||
						   (in_array($training_siret,array('77567227230717')) && in_array($training_formacode,array(44083))) ||
						   (in_array($training_siret,array('77807079700015')) && in_array($training_codecertifinfo,array(73378))) ||
						   (in_array($training_siret,array('77807079700015')) && in_array($training_formacode,array(44008)))
						   )
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine5'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_nouvelleaquitaine5',$droits);
							elseif($training_duration>=150)
								remuRPS($var,'actioncollectiveregion_nouvelleaquitaine5',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine5',$droits);
						}
					}
		}

		/* Ligne 12 */
		/* Caractéristiques formation: */
		//F° avec code financeur Région et lieux = Dordogne, Gironde (33), Landes (40),Lot-et-Garonne (47), Pyrénées-Atlantiques (64)
		//hors balise (grpt d'achat PRF AS-FM) - PRF AS-FM (grpt d'achat)
		//hors formacodes sanitaire et social
		//INFIRMIER (ère) 43448
		//Infirmier (ère) anesthésiste 43457
		//Infirmière puéricultrice 43456
		//Puéricultrice 43439
		//Infirmier (ère) bloc opératoire 43449
		//MASSEUR KINESITHERAPEUTE 43490
		//Ergothérapeute 43491
		//Pédicure-podologue 43493
		//SAGE - FEMME 43092
		//MANIPULATEUR D’ELECTRORADIOLOGIE MEDICALE 43497
		//AIDE SOIGNANT 43436
		//AUXILIAIRE DE PUERICULTURE 43441
		//AMBULANCIER 31815
		//PRÉPARATEUR EN PHARMACIE HOSPITALIÈRE 43006
		//MONITEUR EDUCATEUR 44092
		//TECHNICIEN EN EDUCATION SOCIALE ET FAMILIALE 44008
		//CONSEILLER EN ECONOMIE SOCIALE ET FAMILIALE 44084
		//Assistant du service social 44083
		//Educateur de Jeunes Enfants 44050
		//Educateur Spécialisé 44092
		//Educateur Technique Spécialisé 44092
		//'43448','43457','43456','43439','43449','43490','43491','43493','43092','43497','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','44092'

		/* Caractéristiques DE: */
		//DE inscrit France entiere
		/* Rémunération: */
		//AREF RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional de Formation (PRF)";
			$array['step']="Contacter votre conseiller Référent (Pôle emploi, Mission Locale, Opérateur Placement Spécialisé, ex Cap Emploi) afin que votre projet de formation soit validé.<br/>Si la formation est éligible au Compte Personnnel de Formation (CPF), votre conseiller recueillera votre consentement à mobiliser vos heures acquises.";
			$array['descinfo']="Le Conseil régional Nouvelle-Aquitaine finance en totalité les coûts pédagogiques des formations collectives du programme régional de Formation.<br/>La mobilisation de ces financements sera prioritaire par rapport à un financement individuel (Aide Individuelle à la Formation).";
			$array['info']="La pertinence de votre demande de formation doit s'inscrire dans le cadre d'un projet professionnel de retour à l'emploi. Ce projet doit être validé par votre conseiller dans le cadre de votre accompagnement. Votre conseiller partagera avec vous dans le cadre d'un Conseil en Evolution Professionnelle, la pertinence de celui ci au regard du marché du travail mais également de votre dossier.<br/>Il vous informera sur les conditions de rémunération propres à votre situation au jour d'entrée en formation.";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionExAquitaine($training_locationpath))
				{
					if(hasKeywords(array('PRF'),$ar['intitule']))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(!in_array($training_formacode,array('43448','43457','43456','43439','43449','43490','43491','43493','43092','43497','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','44092')))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine6'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_nouvelleaquitaine6',$droits);
								elseif($training_duration>=150)
									remuRPS($var,'actioncollectiveregion_nouvelleaquitaine6',$droits);
								else
									remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine6',$droits);
							}
				}
		}

		/* Ligne 13 */
		/* Caractéristiques formation: */
		//F° avec code financeurRégion et localisation Dordogne (24)
		//Gironde (33)
		//Landes (40)
		//Lot-et-Garonne (47)
		//Pyrénées-Atlantiques (64)
		//ou F° mention "Programme Régional de Formation (grpt d'achat PRF AS-FM) - PRF AS-FM (grpt d'achat)" et localisation Dordogne (24)
		//Gironde (33)
		//Landes (40)
		//Lot-et-Garonne (47)
		//Pyrénées-Atlantiques (64)

		/* Caractéristiques DE: */
		//DE inscrit France entiere
		/* Rémunération: */
		//AREF - RPS

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Programme Régional de Formation";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) qui étudiera avec vous votre projet de formation. Si votre projet est validé, il procèdera à une pré-inscription auprès de l'organisme de formation.<br/>Ensuite l'organisme de formation auprès duquel vous candidatez vous recevra pour passer un entretien et/ou des tests de sélection.";
		//	$array['descinfo']="Le Programme Régional de Formation regroupe toutes les formations financées par la Région et Pôle emploi en Dordogne, Gironde (33),Landes (40),Lot-et-Garonne (47) et<br/>Pyrénées-Atlantiques (64). Elles répondent aux besoins des entreprises du territoire.";
		//	$array['info']="La pertinence de votre demande de formation doit s'inscrire dans le cadre d'un projet professionnel de retour à l'emploi. Ce projet doit être validé par votre conseiller dans le cadre de votre accompagnement. Votre conseiller partagera avec vous dans le cadre d'un Conseil en Evolution Professionnelle, la pertinence de celui ci au regard du marché du travail mais également de votre dossier.<br/>Il vous informera sur les conditions de rémunération propres à votre situation au jour d'entrée en formation.";
		//	$array['cost']="Formation totalement financée";
		//	arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('nouvelleaquitaine77'=>$array));
		//	if($situation_inscrit)
		//		if(isInRegionExAquitaine($training_locationpath))
		//			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
		//				if(hasStrings(array('PRF AS-FM'),$ar['intitule']))
		//				{
		//					if($allocation_type=='are')
		//						remuAREF($var,'nouvelleaquitaine77',$droits);
		//					else
		//						remuRPS($var,'nouvelleaquitaine77',$droits);
		//				}
		//}

		/* Ligne 14 */
		/* Caractéristiques formation: */
		//F° avec code financeur PE et localisation Dordogne (24)
		//Gironde (33)
		//Landes (40)
		//Lot-et-Garonne (47)
		//Pyrénées-Atlantiques (64)

		/* Caractéristiques DE: */
		//DE inscrit France entiere
		/* Rémunération: */
		//AREF RFPE

		//if(0)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Programme Régional de Formation";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) qui étudiera avec vous votre projet de formation. Si votre projet est validé, il procèdera à une pré-inscription auprès de l'organisme de formation.<br/>Ensuite l'organisme de formation auprès duquel vous candidatez vous recevra pour passer un entretien et/ou des tests de sélection.";
		//	$array['descinfo']="Le Programme Régional de Formation regroupe toutes les formations financées par la Région et Pôle emploi en Dordogne, Gironde (33),Landes (40),Lot-et-Garonne (47) et<br/>Pyrénées-Atlantiques (64).<br/>Elles répondent aux besoins des entreprises du territoire.";
		//	$array['info']="La pertinence de votre demande de formation doit s'inscrire dans le cadre d'un projet professionnel de retour à l'emploi. Ce projet doit être validé par votre conseiller dans le cadre de votre accompagnement. Votre conseiller partagera avec vous dans le cadre d'un Conseil en Evolution Professionnelle, la pertinence de celui ci au regard du marché du travail mais également de votre dossier.<br/>Il vous informera sur les conditions de rémunération propres à votre situation au jour d'entrée en formation.";
		//	$array['cost']="Formation totalement financée";
		//	if($situation_inscrit)
		//		if(isInRegionExAquitaine($training_locationpath))
		//		{
		//			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
		//			{
		//				arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine7'=>$array));
		//				if($allocation_type=='are')
		//					remuAREF($var,'actioncollectiveregion_nouvelleaquitaine7',$droits);
		//				else
		//					remuRFPE2($var,'actioncollectiveregion_nouvelleaquitaine7',$droits);
		//			}
		//		}
		//}

		/* Ligne 15 */
		/* Caractéristiques formation: */
		//Toute F° france entiere , hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//+ début avant le 30/06/2017

		/* Caractéristiques DE: */
		//Tout demandeur d'emploi , non DE et créateur d'entp, non suivi par la Mission locale + dépt 24, 33, 40, 47, 64
		/* Rémunération: */
		//AREF RPS

		//if(0)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Aide individuelle Région - Chèque qualification demandeur d'emploi";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>Le projet doit être validé et le dossier complet pour être transmis - au plus tard 4 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
		//	$array['descinfo']="L'aide individuelle de la Région permet, en priorité à des demandeurs d'emploi peu qualifiés, d'accéder à une qualification de niveau supérieur, dans un objectif de retour à l'emploi.<br/>L'aide individuelle de la Région permet également à des demandeurs d'emploi déjà qualifiés dans un domaine d'accéder à une action de spécialisation en lien avec leur qualification .";
		//	$array['info']="L’aide individuelle de la Région vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels.<br/>http://les-aides.laregion-alpc.fr/fiche/cheque-regional-qualification-demandeurs-demploi/";
		//	$array['cost']="Formation totalement ou partiellement financée par la Région<br/>dans la limite de 2000 €.";
		//	$array['cost-plafond']="2000";
		//	if($situation_inscrit || (!$situation_inscrit && $situation_projetcreationentreprise))
		//		if($training_datebegin && Tools::calcDiffDate($training_datebegin,'2017-12-31')>0)
		//		//if($situation_nonsuivienmissionlocale)
		//			if(isInRegionExAquitaine($domicileRegionPath))
		//				if($hors_codefinanceur_pe_opca_etat_colter)
		//					if(!$training_contratprofessionalisation && !$training_contratapprentissage)
		//					{
		//						arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_nouvelleaquitaine'=>$array));
		//						if($allocation_type=='are')
		//							remuAREF($var,'finindividuel_nouvelleaquitaine',$droits);
		//						elseif($training_duration>150)
		//							remuRPS($var,'finindividuel_nouvelleaquitaine',$droits);
		//					}
		//}

		/* Ligne 16 */
		/* Caractéristiques formation: */
		//Toute F° france entiere , hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//et de niveau V ou IV
		//pour formaton niveau > à IV : afficher le wording suivant
		//+ début avant le 30/06/2017

		/* Caractéristiques DE: */
		//contrat d'avenir en cours (cf règles salariés)+ et localisation Dordogne (24)
		//Gironde (33)
		//Landes (40)
		//Lot-et-Garonne (47)
		//Pyrénées-Atlantiques (64)
		/* Rémunération: */
		//salaire en cours

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Aide individuelle Région -<br/>Chèque qualification emploi d'avenir";
		//	$array['step']="Contacter votre conseiller référent emploi (Mission Locale ou Cap emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>Le projet doit être validé et le dossier complet pour être transmis - au plus tard 4 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
		//	$array['descinfo']="L'aide individuelle &laquo;&nbsp;chèque emploi d'avenir&nbsp;&raquo; de la Région a pour objectif de professionnaliser les jeunes en emploi d'avenir.ayant besoin d'acquérir une qualification professionnelle reconnue de niveau V ou IV ou une spécialisation en lien avec son parcours initial.<br/>L’aide individuelle vient obligatoirement en complément d’un cofinancement. Elle doit donc correspondre au coût résiduel non pris en charge par les OPCA, les employeurs et éventuellement le salarié.";
		//	$array['info']="L’aide individuelle de la Région vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels.<br/>Le taux maximum de prise en charge des coûts pédagogiques (= hors frais d'inscription et frais annexes) par la région est de 30% si votre employeur relève du secteur marchandde et de 50% s'il relève du secteur non marchand et dans la limite de 2500€.<br/>Consultez le site de la Région<br/>http://les-aides.laregion-alpc.fr/fiche/cheque-regional-qualification-emploi-davenir/";
		//	$array['cost']="Formation partiellement financée dans la limite de 2500€., au titre d'un co-financement apporté par la Région";
		//	$display['Aide individuelle Région -<br/>Chèque qualification emploi d\'avenir']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 17 */
		/* Caractéristiques formation: */
		//Toute F° france entiere , hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//et de niveau = III + + début avant le 30/06/2017

		/* Caractéristiques DE: */
		//contrat d'avenir en cours (cf règles salariés)+ et localisation Dordogne (24)
		//Gironde (33)
		//Landes (40)
		//Lot-et-Garonne (47)
		/* Rémunération: */
		//salaire en cours

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Aide individuelle Région -<br/>Chèque qualification emploi d'avenir";
		//	$array['step']="Contacter votre conseiller référent emploi (Mission Locale ou Cap emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>Le projet doit être validé et le dossier complet pour être transmis - au plus tard 4 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
		//	$array['descinfo']="L'aide individuelle &laquo;&nbsp;chèque emploi d'avenir&nbsp;&raquo; de la Région a pour objectif de professionnaliser les jeunes en emploi d'avenir ayant besoin d'acquérir une qualification professionnelle reconnue de niveau V ou IV ou une spécialisation en lien avec son parcours initial. A noter : les qualifications de niveau III sont éligibles pour les jeunes issus des ZUS (zones urbaines sensibles) ou ZRR (zones de revitalisation rurale).<br/>L’aide individuelle vient obligatoirement en complément d’un cofinancement. Elle doit donc correspondre au coût résiduel non pris en charge par les OPCA, les employeurs et éventuellement le salarié.";
		//	$array['info']="L’aide individuelle de la Région vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels.<br/>Le taux maximum de prise en charge des coûts pédagogiques (= hors frais d'inscription et frais annexes) par la région est de 30% si votre employeur relève du secteur marchandde et de 50% s'il relève du secteur non marchand et dans la limite de 2500€.<br/>Consultez le site de la Région<br/>http://les-aides.laregion-alpc.fr/fiche/cheque-regional-qualification-emploi-davenir/";
		//	$array['cost']="Formation totalement ou partiellement financée dans la limite de 2500€, au titre d'un co-financement apporté par la Région<br/>.";
		//	$display['Aide individuelle Région -<br/>Chèque qualification emploi d\'avenir']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 18 */
		/* Caractéristiques formation: */
		//Toute F° france entiere avec n° certifinfo , hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage
		//+ date début formation avant le 30/06/2017

		/* Caractéristiques DE: */
		//DE ou non, dépt 24, 33, 40, 47, 64 ; <26 ans suivis par les Missions Locales .
		/* Rémunération: */
		//AREF RPS si formation>150h sinon pas de rémunération spécifique

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Aide individuelle Région - Chèque jeune";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>Le projet doit être validé et le dossier complet pour être transmis - au plus tard 4 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
		//	$array['descinfo']="L'aide individuelle &laquo;&nbsp;chèque jeune&nbsp;&raquo; permet aux jeunes aquitains âgés de 16 à 25 ans inscrits dans une Mission Locale d’Aquitaine, d’accéder par l’acquisition d’une qualification (qualification inscrite au Répertoire National de la Certification Professionnelle ou validation reconnue par la branche professionnelle) ou d’une spécialisation professionnelle (en complément d’une formation de base en rapport avec la spécialisation), à une insertion rapide et durable dans l’emploi.";
		//	$array['info']="Cette aide s’adresse en priorité aux jeunes sortis du système scolaire sans qualification professionnelle et dont l’objectif est l’accès à un premier niveau de qualification professionnelle.<br/>Une attention particulière sera portée aux jeunes les plus éloignés de l’emploi et, notamment, ceux issus des zones urbaines et des zones rurales en difficulté.<br/>Cette aide dans le cadre de la convention signée entre le Conseil régional d’Aquitaine et l’AGEFIPH, contribue à favoriser l’accès aux formations professionnelles pour les personnes handicapées suivies par la Mission Locale.<br/>Consultez le site de la Région<br/>http://les-aides.nouvelle-aquitaine.fr/fiche/cheque-regional-qualification-jeunes/";
		//	$array['cost']="Formation totalement ou partiellement financée<br/>dans la limite de 2000 €.";
		//	$display['finindividuel']=$array;
		//	if($situation_inscrit)
		//		if(isInRegionExAquitaine($domicileRegionPath))
		//		{
		//		}
		//}

		/* Ligne 19 */
		/* Caractéristiques formation: */
		//Toute F° france entiere , hors tag code financeur Région, PE, OPCA, Etat, Col ter, tag contrat de pro et contrat en apprentissage + + début avant le 30/06/2017

		/* Caractéristiques DE: */
		//Salariés ou demandeurs d'emploi de la région ex-Aquitaine (dépt 24, 33, 40, 47, 64) en CSP
		/* Rémunération: */
		//rémunération basée sur formulaire

		//if(0)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Aide individuelle Région - Chèque reclassement";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi CSP ou Cellule de reclassement) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>Le projet doit être validé et le dossier complet pour être transmis - au plus tard 4 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
		//	$array['descinfo']="L'aide individuelle &laquo;&nbsp;Chèque reclassement&nbsp;&raquo; de la Région permet aux personnes licenciées économiques suivies dans le cadre d'un accompagnement (Cellule de reclassement ou Contrat de sécurisation professionnelle) d'accéder à une qualification de niveau supérieur, dans un objectif de retour à l'emploi.<br/>L'aide individuelle de la Région permet également aux personnes licenciées économiques suivies dans le cadre d'un accompagnement (Cellule de reclassement ou Contrat de sécurisation professionnelle) déjà qualifiées dans un domaine, d'accéder à une action de spécialisation en lien avec leur qualification .";
		//	$array['info']="Lorsque les aides de l'OPCA ou de l'employeur sont insuffisantes, le chèque reclassement peut couvrir le coût résiduel, avec un taux maximum de prise en charge de la région de 75% des coûts pédagogiques (= hors frais d'inscription et frais annexes) et dans la limite de 4000€.<br/>Consultez le site de la Région<br/>http://les-aides.laregion-alpc.fr/fiche/cheque-regional-reclassement/";
		//	$array['cost']="Formation totalement ou partiellement financée<br/>dans la limite de 4000 € et de 75% des coûts pédagogiques<br/>Lorsque les aides de l'OPCA ou de l'employeur sont insuffisantes, le chèque reclassement peut couvrir le coût résiduel, avec un taux maximum de prise en charge de la région de 75% des coûts pédagogiques (= hors frais d'inscription et frais annexes) et dans la limite de 4000&nbsp;€.";
		//	$array['cost-plafond']="4000";
		//	if($situation_inscrit || $situation_salarie)
		//		if($situation_liccsp)
		//			if(isInRegionExAquitaine($domicilePath))
		//				if($hors_codefinanceur_pe_opca_etat_colter)
		//					if($training_datebegin && Tools::calcDiffDate($training_datebegin,'2017-06-30')>0)
		//						if(!$training_contratprofessionalisation && !$training_contratapprentissage)
		//						{
		//							arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_nouvelleaquitaine2'=>$array));
		//							remuFORM2($var,'finindividuel_nouvelleaquitaine2',$droits);
		//						}
		//}

		/* Ligne 20 */
		/* Caractéristiques formation: */
		//toute F° avec formacode 15064 et hors code financeur Région ou PE
		//Entrée en formation avant le 30/04/2017
		//'15064'

		/* Caractéristiques DE: */
		//Tout demandeur d'emploi + dépt 24, 33, 40, 47, 64, hors créateutr entreprise.
		/* Rémunération: */
		//Pas de rémunération spécifique

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Aide individuelle Région -<br/>Chèque accompagnement VAE (Validation des Acquis de l'Expérience)";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>Le projet doit être validé et le dossier complet pour être transmis - au plus tard 4 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
		//	$array['descinfo']="La prestation d’accompagnement à la VAE a pour objectif d’aider les candidats dans la constitution du dossier présenté au jury, afin d'obtenir une qualification.<br/>Pour cela, le candidat doit justifier d’au moins trois ans d’expérience en rapport direct avec la certification visée.";
		//	$array['info']="http://les-aides.laregion-alpc.fr/fiche/cheque-regional-accompagnement-vae/<br/>http://www.vae.gouv.fr/";
		//	$array['cost']="Formation totalement ou partiellement financée, avec 2 niveaux d'intervention :<br/>- pour les personnes indemnisées par Pôle emploi, l'aide régionale est de 400€ maximum. Pôle emploi peut compléter par une Aide à la VAE d'un montant maximum de 900€.<br/>- pour les personnes non indemnisées par Pôle emploi, l'aide régionale est de 900€ maximum. Pôle emploi peut compléter par une Aide à la VAE d'un montant maximum de 400€.";
		//	$display['finindividuel3']=$array;
		//	arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel3'=>$array));
		//	if($situation_inscrit && !$situation_projetcreationentreprise)
		//		if(isInRegionExAquitaine($domicilePath))
		//			if($training_formacode!='15064')
		//				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
		//				   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
		//				{
		//					remuTEXT($var,'finindividuel3',$droits);
		//				}
		//}

		/* Ligne 21 */
		/* Caractéristiques formation: */
		//INFIRMIER (ère) 43448
		//Infirmier (ère) anesthésiste 43457
		//Infirmière puéricultrice 43456
		//puéricultrice 43439
		//Infirmier (ère) bloc opératoire 43449
		//MASSEUR KINESITHERAPEUTE 43490
		//Ergothérapeute 43491
		//Pédicure-podologue 43493
		//SAGE - FEMME 43092
		//MANIPULATEUR D’ELECTRORADIOLOGIE MEDICALE 43497
		//AIDE SOIGNANT 43436
		//AUXILIAIRE DE PUERICULTURE 43441
		//AMBULANCIER 31815
		//PRÉPARATEUR EN PHARMACIE HOSPITALIÈRE 43006
		//MONITEUR EDUCATEUR 44092
		//TECHNICIEN EN EDUCATION SOCIALE ET FAMILIALE 44008
		//CONSEILLER EN ECONOMIE SOCIALE ET FAMILIALE 44084
		//Assistant du service social 44083
		//Educateur de Jeunes Enfants 44050
		//Educateur Spécialisé 44092
		//Educateur Technique Spécialisé 44092
		//+ lieux : (dépt 24, 33, 40, 47, 64)
		//'43448','43457','43456','43439','43449','43490','43491','43493','43092','43497','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','44092'

		/* Caractéristiques DE: */
		//Tout public habitant dépt 24, 33, 40, 47, 64
		/* Rémunération: */
		//AREF ... SI pas d'AREF, wording ci-dessous. La Région peut accorder des bourses. Les condtions d'optention de ces bourses sont en cours de révision.

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations sanitaires et sociales";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi) pour valider votre projet professionnel et vérifier votre éligibilité.<br/>Ensuite vous prendrez directement contact avec les organismes de formation agréés par la Région.";
			$array['descinfo']="Ces formations sont financées par le Conseil régional et co-financées par Pôle emploi pour les aides-soignant(e)s.";
			$array['info']="21 métiers sont représentés dans l'offre de formations du secteur sanitaire et social. Le nombre de places disponibles est fonction des besoins en personnel du secteur (professions règlementées).";
			$array['cost']="Prise en charge totale ou partielle par la Région";
			if(isInRegionNouvelleAquitaine($domicilePath))
			{
				if(isInRegionExAquitaine($training_locationpath))
					if(in_array($training_formacode,array('43437','43448','43457','43456','43439','43449','43490','43491','43493','43092','43497','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','44092')))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine9'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_nouvelleaquitaine9',$droits);
						elseif($training_duration>=150)
							remuRPS($var,'actioncollectiveregion_nouvelleaquitaine9',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine9',$droits);
					}
			}
		}

		/* Ligne 22 */
		/* Caractéristiques formation: */
		//- Formations courtes <210 heures
		//- Formations professionnalisantes jusqu'à 600 heures
		//- Participation au coût d'un financement individuel Région ou Pôle emploi
		//- existence du dispositif RECAP (Rencontres d'Expertises Croisées pour l'Accessibilité Pédagogique).

		/* Caractéristiques DE: */
		//DE + TH
		/* Rémunération: */
		//Pas de rémunération spécifique

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="AGEFIPH";
		//	$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider votre projet professionnel.";
		//	$array['descinfo']="L'Agefiph propose, en complément des aides de droit commun, des aides destinées à la construction du projet professionnel et la formation.<br/>- La formation des demandeurs d'emploi<br/>- La formation des salariés dans le cadre du maintien dans l’emploi<br/>- L'Aide à la formation des jeunes handicapés en emploi d’avenir<br/>- L'aide à la formation des salariés en contrats de génération";
		//	$array['info']="https://www.agefiph.fr/Professionnel/Projet-professionnel-et-formation/Toutes-les-aides-Agefiph-pour-la-construction-d-un-projet-professionnel-et-la-formation#ss_article_f";
		//	$array['cost']="Formation totalement ou partiellement financée.<br/>Intervention en complémentarité des dispositifs existants.";
		//	$display['AGEFIPH']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 23 */
		/* Caractéristiques formation: */
		//

		/* Caractéristiques DE: */
		//Bénéficiaire du RSA
		/* Rémunération: */
		//Pas de rémunération spécifique

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Conseil Départemental";
		//	$array['step']="Contacter votre référent RSA ou votre conseiller référent emploi (Pôle emploi ou Cap emploi) pour valider votre projet professionnel.";
		//	$array['descinfo']="";
		//	$array['info']="Se rapprocher des services sociaux de votre département en charge du RSA";
		//	$array['cost']="";
		//	$display['Conseil Départemental']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 24 */
		/* Caractéristiques formation: */
		//

		/* Caractéristiques DE: */
		//
		/* Rémunération: */
		//Pas de rémunération spécifique

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="AFPR, POE I, POE C : pas de spécificités régionales impactant le demandeur d'emploi tant au niveau de sa rémunération que de la prise en charge financière de sa formation";
		//	$array['step']="";
		//	$array['descinfo']="";
		//	$array['info']="";
		//	$array['cost']="";
		//	$display['AFPR, POE I, POE C : pas de spécificités régionales impactant le demandeur d\'emploi tant au niveau de sa rémunération que de la prise en charge financière de sa formation']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 25 */
		/* Caractéristiques formation: */
		//F° avec intitulé commençant par : "PREPA Métiers"

		/* Caractéristiques DE: */
		//jeunes - 26 ans, inscrits à la MIssion Locale
		//TH, résidants en Grande Aquitaine
		/* Rémunération: */
		//A vérifier

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Mesures préparatoires à l'alternance - PREPA - Parcours Régional de Préparation à l'Apprentissage";
			$array['step']="Contacter votre Mission Locale ou Opérateur Placement Spécialisé, ex Cap Emploi pour valider votre projet";
			$array['descinfo']="Dispositif financé par la Conseil régional pour permettre un accompagnement individualisé pour préparer un projet de formation en apprentissage et aider à la recherche d'un employeur.";
			$array['info']="Réservé aux jeunes de moins de 26 ans ou aux personnes reconnues travailleur handicapé.";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
			{
				if($age<26 || $situation_th)
					if(isInRegionNouvelleAquitaine($domicilePath))
						if(isInRegionNouvelleAquitaine($training_locationpath))
							if(hasStrings(array('PREPA Métiers'),$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_nouvelleaquitaine10'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_nouvelleaquitaine10',$droits);
								else
									remuTEXT($var,'actioncollectiveregion_nouvelleaquitaine10',$droits);
							}
			}
		}

		/* Ligne 26 */
		/* Caractéristiques de la formation: */
		//F° 
		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Aide Individuelle Conseil Régional";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Operateur de Placement Spécialisés, ex-CAP Emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>
Le projet doit être validé et le dossier complet pour être transmis - au plus tard 6 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
			$array['descinfo']="L’aide individuelle de la Région vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels.";
			$array['info']="Sont prioritairement éligibles les actions de qualification de niveaux V et IV figurant au RNCP (Répertoire national des certifications professionnelles) ou professionnalisantes (diplôme d’Etat, certificat de qualification professionnelle (CQP), validation de Branches Professionnelles). Dans certains cas, il peut s’agir aussi d’actions de spécialisation, de formations techniques nécessaires à la création/reprise d’entreprise ou de formations supérieures de niveau III à I.";
			$array['cost']="Formation totalement ou partiellement financée dans la limite de 3000&nbsp;€";
			$array['cost-plafond']=3000;
			if($situation_inscrit)
				if(!$situation_th)
				{
					if(isInRegionNouvelleAquitaine($domicilePath))
						if($hors_codefinanceur_pe_opca_etat_colter)
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!in_array($training_formacode,array('43412','43437','43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456','31826','31795','31768','31747','31709','31715','31717','31708','31828','24069','24066')))
									if($training_duration>400 && $training_dureeenmois<12)
										if($training_racineformacode!=150)
											if(!in_array($training_codecertifinfo,array('65960','87805','49616','23710','54660','81306','78281','54664','54662','81136')))
											{
												arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_nouvelleaquitaine'=>$array));
												if($allocation_type=='are')
													remuAREF($var,'finindividuel_nouvelleaquitaine',$droits);
												elseif($training_duration>=150)
													remuRPS($var,'finindividuel_nouvelleaquitaine',$droits);
												else
													remuTEXT($var,'finindividuel_nouvelleaquitaine',$droits);
											}
				}
		}

		/*Ligne 27*/

		if(1)
		{
			$array=array();
			$array['pri']="4";
			$array['title']="Action de formation collective financée par Pôle emploi";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Operateur de Placement Spécialisés, ex-CAP Emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>
Le projet doit être validé et le dossier complet pour être transmis - au plus tard 6 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
			$array['descinfo']="Il s'agit d'une formation collective, délivrée en centre de formation pouvant comprendre une période de stage en entreprise, gratuite et réservée aux demandeurs d'emploi";
			$array['cost']="Formation totalement financée";
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AUTRE,$nbPlaces) &&
					!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ENTREPRISE,$nbPlaces) &&
					!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_BENEFICIAIRE_DE_L_ACTION,$nbPlaces) &&
					!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPACIF,$nbPlaces))
						if(!hasStrings(array("POEC","PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI'),$ar['intitule']))
						{
							//$display['poleemploicollectif']=$array;
							arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_nouvelleaquitaine'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'poleemploicollectif_nouvelleaquitaine',$droits);
							else
								remuRFPE2($var,'poleemploicollectif_nouvelleaquitaine',$droits);
						}
		}

		/* Ligne 28 */
		// Si DE inscrit et en nouvelle aquitaine et TH et à le droit à de l'agefiph nationale
		if(isInRegionNouvelleAquitaine($domicileRegionPath))
			if($situation_inscrit && $situation_th && isset($droits['agefiph']))
			{
				unset($droits['agefiph']);
			}

		/* Ligne 29 */
		/* Caractéristiques de la formation: */
		//F° 
		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Aide Individuelle Conseil Régional";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Operateur de Placement Spécialisés, ex-CAP Emploi) pour valider votre projet de formation. Vous pouvez pour cela lui présenter 2 à 3 devis d'organismes de formation différents ainsi qu'une lettre de motivation.<br/>
Le projet doit être validé et le dossier complet pour être transmis - au plus tard 6 semaines avant l'entrée en formation - au Conseil régional qui décide ou non de l'attribution de l'aide.";
			$array['descinfo']="L’aide individuelle de la Région vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectifs ne peuvent répondre, ni les autres dispositifs de formation individuels.";
			$array['info']="Sont prioritairement éligibles les actions de qualification de niveaux V et IV figurant au RNCP (Répertoire national des certifications professionnelles) ou professionnalisantes (diplôme d’Etat, certificat de qualification professionnelle (CQP), validation de Branches Professionnelles). Dans certains cas, il peut s’agir aussi d’actions de spécialisation, de formations techniques nécessaires à la création/reprise d’entreprise ou de formations supérieures de niveau III à I.";
			$array['cost']="Formation partiellement ou totalement financée dans la limite de 6000&nbsp;€, financés pour moitié par la région et pour moitié par l'Agefiph,<br/>et du montant correspondant à vos droits CPF si la formation est éligible.";
			$array['cost-plafond']=6000;
			if($situation_inscrit)
				if($situation_th)
				{
					if(isInRegionNouvelleAquitaine($domicilePath))
						if($hors_codefinanceur_pe_opca_etat_colter)
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!in_array($training_formacode,array('43412','43437','43409','44002','15073','13030','43448','43457','43456','43439','43449','43490','43491','43493','43497','43092','43436','43441','31815','43006','44092','44008','44084','44083','44050','44092','43425','43428','43430','43433','43445','43442','43438','43443','43444','43490','43442','14447','14407','14426','14456','31826','31795','31768','31747','31709','31715','31717','31708','31828','24069','24066')))
									if($training_duration>400 && $training_dureeenmois<12)
										if($training_racineformacode!=150)
											if(!in_array($training_codecertifinfo,array('65960','87805','49616','23710','54660','81306','78281','54664','54662','81136')))
											{
												arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_nouvelleaquitaine'=>$array));
												if($allocation_type=='are')
													remuAREF($var,'finindividuel_nouvelleaquitaine',$droits);
												elseif($training_duration>=150)
													remuRPS($var,'finindividuel_nouvelleaquitaine',$droits);
												else
													remuTEXT($var,'finindividuel_nouvelleaquitaine',$droits);
											}
				}
		}

		if(isInRegionNouvelleAquitaine($training_locationpath))
			if(hasKeywords(array('POEC'),$ar['intitule']))
				unset($droits['finindividuel_nouvelleaquitaine'],$droits['aif_nouvelleaquitaine'],$droits['aif_nouvelleaquitaine3'],
				      $droits['afprpoei'],$droits['afpr'],$droits['poei'],$droits['autres']);

	}
?>
