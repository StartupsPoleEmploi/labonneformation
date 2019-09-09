<?php
	/* Règles Guadeloupe **************************************************************************************************/
	function reglesGuadeloupe($quark,$var,&$droits,&$display)
	{
		extract($var);

		/* Ligne 4 (Guadeloupe) */
		/* Caractéristiques DE: */
		//Tout DE inscrit en région Guadeloupe.

		/* Caractéristiques formation: */
		//F° France entière
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Region"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250, si certifinfo 84385
		//si durée F°>300h
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//'15081','13250','84385'

		/* Rémunération: */
		//AREF ou RFPE. RFF )

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Aide individuelle à la Formation (AIF)";
			$array['step']="Contactez votre conseiller référent Pôle emploi.<br/>Attention : votre projet de formation et son financement (dossier complet) doivent être présentés au plus tard 15 jours avant le début de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre.<br/>Vous devez indiquez dans le formulaire AIF votre souhait ou non de mobiliser votre CPF<br/>L’AIF est réservée à des projets de formation dont la pertinence est validée par votre conseiller référent Pôle emploi.<br/>Cette pertinence est appréciée au regard de votre projet professionnel et du marché du travail.<br/>Les formations financées doivent permettre de faciliter votre retour rapide à l'emploi.";
			$array['info']="L'AIF permet une prise en charge des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen …).<br/>L'AIF peut être attribuée quelle que soit la modalité pédagogique de la formation, y compris pour une formation à distance (FOAD) ou pour une formation en cours du soir. Sous certaines conditions , ces formations peuvent ouvrir droit à des aides à la mobilité (déplacement et/ou hébergement)";
			$array['cost']="Formation financée jusqu'à 1500 € maximum";
			$array['cost-plafond']="1500";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath))
				{
					unset($droits['aif']);
					if($training_duration<300)
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
							if(!in_array($training_formacode,array('15064','44591')))
								if(!in_array($training_formacode,array('15081','13250')) && !in_array($training_codecertifinfo,array('84385')))
									if(!hasStrings('AFC',$ar['intitule']))
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										{
											arrayInsertAfterKey($droits,'aif',$display,array('aif_guadeloupe'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'aif_guadeloupe',$droits);
											else
												remuRFPE2($var,'aif_guadeloupe',$droits);
										}
				}
		}

		/* Ligne 5 (Guadeloupe) */
		/* Caractéristiques DE: */
		//Tout DE inscrit en région Guadeloupe.

		/* Caractéristiques formation: */
		//F° code financeur PE, localisée en Guadeloupe

		/* Rémunération: */
		//AREF ou RFPE. RFF

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Action de Formation Conventionnée Pôle emploi (AFC PE)";
			$array['step']="Contactez votre conseiller référent Pôle emploi pour valider avec lui votre projet de formation.";
			$array['descinfo']="Les actions de formation conventionnées (AFC) sont des actions de formation proposées par Pôle emploi pour des demandeurs d'emploi ayant besoin de certification ou de renforcement de leurs capacités professionnelles pour répondre à des besoins identifiés d'entreprises au niveau territorial.";
			$array['info']="Sous certaines conditions , ces formations peuvent ouvrir droit à des aides à la mobilité (déplacement et/ou hébergement)";
			$array['cost']="Formation entièrement financée par Pôle emploi";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_guadeloupe'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'poleemploicollectif_guadeloupe',$droits);
						else
							remuRFPE2($var,'poleemploicollectif_guadeloupe',$droits);
					}
		}

		/* Ligne 6 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrit en région Guadeloupe, inscrit depuis > ou = 12 mois
		//ou niveau inférieur à V

		/* Caractéristiques formation: */
		//F° en Guadeloupe, avec code certinfo, durée < ou = 600h, tag RFF
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Region"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr
		//si Formacode 15081, 13250, si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si tag "contrat de professionnalisation"
		//'15081','13250','84385'

		/* Rémunération: */
		//AREF - RFPE + aides à la mobilité.

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Action de formation préalable au recrutement<br/>(AFPR) expérimentale jusqu'au 25/07/17";
		//	$array['step']="Une entreprise veut vous embaucher en CDD et vous avez besoin d'une formation, contactez votre conseiller référent Pôle emploi pour bénéficier d'une AFPR.";
		//	$array['descinfo']="L'action de formation préalable au recrutement (AFPR) vise à fomer des demandeurs d'emploi avant un recrutement .<br/>Elle est réalisée par l’entreprise dans le cadre du tutorat ou par un organisme interne ou par un organisme externe<br/>afin d'acquérir les compétences nécessaires pour occuper l'emploi proposé.<br/>Un dispositif expérimental est mis en oeuvre pour les embauches réalisées dans le département de la Guadeloupe jusqu'au 25/07/17.";
		//	$array['info']="L'Action de Formation Préalable au Recrutement (AFPR) est une formation destinée à combler l'écart entre les compétences que vous détenez et celles que requiert l'emploi que vous allez occuper. L'AFPR est donc une action de formation avant une embauche. Embauche qui peut se faire en CDD de 4 à 12 mois<br/>ou en contrat de professionnalisation en CDD de 6 à moins de 12 mois<br/>ou en contrat de travail temporaire de 6 mois dans les 9 mois suivant la fin de formation. La formation peut se faire en interne, au sein de l'entreprise qui vous recrutera.";
		//	$array['cost']="Formation financée par Pôle emploi jusqu'à 600H pour une embauche en CDD de 6 à 12 mois dans un secteur en difficulté de recrutement. Formationfinancée par Pôle emploi dans la limte de 200H en cas d'embauche en CDD de 4 à 6 mois.";
		//	if($situation_inscrit)
		//		if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
		//		{
		//			$display['afprpoei']=$array;
		//			if($training_duration<=600)
		//				if(!hasKeywords('AFC',$ar['intitule']))
		//					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
		//					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
		//					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
		//					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
		//					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
		//					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
		//					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces))
		//						if(!in_array($training_formacode,array('15081','13250')) && !in_array($training_codecertifinfo,array('84385')))
		//							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
		//								if($allocation_type=='are')
		//									remuAREF($var,'afprpoei',$droits);
		//								else
		//									remuRFPE2($var,'afprpoei',$droits);
		//		}
		//}

		/* Ligne 7 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrit en région Guadeloupe, inscrit depuis > ou = 12 mois
		//ou niveau inférieur à V

		/* Caractéristiques formation: */
		//F° en Guadeloupe,, durée < ou = 400h, si tag RFF
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Region"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250,
		//si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//'15081','13250','84385'

		/* Rémunération: */
		//AREF - RFPE + aides à la mobilité.

		if(0)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Action de formation préalable au recrutement<br/>(AFPR) expérimentale jusqu'au 25/07/17";
			$array['step']="Une entreprise veut vous embaucher en CDD et vous avez besoin d'une formation, contactez votre conseiller référent Pôle emploi pour bénéficier d'une AFPR.";
			$array['descinfo']="L'action de formation préalable au recrutement (AFPR) vise à fomer des demandeurs d'emploi avant un recrutement .<br/>Elle est réalisée par l’entreprise dans le cadre du tutorat ou par un organisme interne ou par un organisme externe<br/>afin d'acquérir les compétences nécessaires pour occuper l'emploi proposé.<br/>Un dispositif expérimental est mis en oeuvre pour les embauches réalisées dans le département de la Guadeloupe jusqu'au 25/07/17.";
			$array['info']="L'Action de Formation Préalable au Recrutement (AFPR) est une formation destinée à combler l'écart entre les compétences que vous détenez et celles que requiert l'emploi que vous allez occuper. L'AFPR est donc une action de formation avant une embauche. Embauche qui peut se faire en CDD de 4 à 12 mois<br/>ou en contrat de professionnalisation en CDD de 6 à moins de 12 mois<br/>ou en contrat de travail temporaire de 6 mois dans les 9 mois suivant la fin de formation. La formation peut se faire en interne, au sein de l'entreprise qui vous recrutera.";
			$array['cost']="Formation financée par Pôle emploi dans la limte de 200H en cas d'embauche en CDD de 4 à 6 mois ou de 400h pour un CDD de 6 à 12 mois.";
			if($situation_inscrit)
				if($niveauscolaire<=CODENIVEAUSCOLAIRE_CAPBEPCFPA)
					if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
						if($training_duration<=400)
							if(!hasKeywords('AFC',$ar['intitule']))
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
									if(!in_array($training_formacode,array('15064','44591'))) //VAE
										if(!in_array($training_formacode,array('15081','13250')))
											if(!in_array($training_codecertifinfo,array('84385')))
												if(!$training_contratprofessionalisation && !$training_contratapprentissage)
												{
													arrayInsertAfterKey($droits,'afprpoei',$display,array('afprpoei_guadeloupe2'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'afprpoei_guadeloupe2',$droits);
													else
														remuRFPE2($var,'afprpoei_guadeloupe2',$droits);
												}
		}

		/* Ligne 8 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrit en région Guadeloupe, ou salariés en Guadeloupe en en CUI-CAE, CAE-DOM ou de CDDI_IAE.

		/* Caractéristiques formation: */
		//F° en Guadeloupe,, durée < ou = 400h,
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Region"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250, si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//'15081','13250','84385'

		/* Rémunération: */
		//AREF RFPE RFF

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations avant embauche : POEI";
			$array['step']="Une entreprise veut vous embaucher en CDD de pls + de 12 mois ou CDI et vous avez besoin d'une formation,demandez à l'entreprise de contacter son OPCA pour bénéficier de la formation. Pendant la formation, vous serez stagiaire de la formation professionnelle";
			$array['descinfo']="La préparation opérationnelle à l'emploi vise à former des demandeurs d'emploi avant un recrutement .<br/>Elle est réalisée par un organisme de formation interne ou externe à l'entreprise,<br/>afin d'acquérir les compétences nécessaires pour occuper l'emploi proposé.";
			$array['info']="La POEI est une formation avant embauche.<br/>L'embauche peut se faire en CDI ou CDD de 12 mois et plus, y compris en contrat d'apprentissage. Un dispositif expérimental est mis en œuvre pour les embauches réalisées en Guadeloupe en contrat de professionnalisation suivant une POEI : la durée minimale du contrat peut être inférieure à 12 mois sans être inférieure à 6 mois.";
			$array['cost']="Formation financée par Pôle emploi dans la limite de 8€ / heure et de 400H : (5€ / heure stagiaire si la formation est réalisée par un organisme de formation interne à l'entreprise) Cofinancement possible par l'OPCA de l'employeur.";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
					if($training_duration<=400)
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
							if(!in_array($training_formacode,array('15064','44591'))) //VAE
								if(!in_array($training_formacode,array('15081','13250')) && !in_array($training_codecertifinfo,array('83899','84385','54664')))
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
									{
										unset($droits['afprpoei']); // on ne peut pas avoir d'afprpoei si on a un poei
										if($training_duration>0) $array['cost-plafond']=8*$training_duration;
										arrayInsertAfterKey($droits,'poei',$display,array('poei_guadeloupe3'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'poei_guadeloupe3',$droits);
										else
											remuRFPE2($var,'poei_guadeloupe3',$droits);
									}
		}

		/* Ligne 9 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrit en région Guadeloupe, ou salariés en Guadeloupe en CUI-CAE, CAE-DOM ou de CDDI_IAE.

		/* Caractéristiques formation: */
		//Formation° en Guadeloupe avec intitulé incluant POEC ou dispositif financement = POEC. La durée maximale de la formation est fixée à 400 heures.

		/* Rémunération: */
		//AREF ou RFPE. RFF

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Préparation Opérationnelle à l'Emploi_collective";
			$array['step']="Contactez votre conseiller référent Pôle emploi pour valider avec lui votre projet de formation.";
			$array['descinfo']="Il s'agit d'une formation professionnalisante, qui répond à un besoin de main-d'oeuvre qualifié exprimé par une branche, un secteur propfessionnel ou une profession. D'une durée maximale de 400h , elle inclut une période d'application en entreprise. Elle est gratuite et réservée aux demandeurs d'emploi.";
			$array['info']="Les frais pédagogiques sont pris en charge par un OPCA (organisme paritaire collecteur agréé).<br/>La rémunération est prise en charge par Pôle emploi. Sous certaines conditions, elles peuvent ouvrir droit à des aides à la mobilité";
			$array['cost']="Formation totalement financée par l'OPCA dans la limite de 400H.";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
					if($training_duration<=400)
						if(hasStrings(array('POEC'),$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'poecollective',$display,array('poecollective_guadeloupe'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'poecollective_guadeloupe',$droits);
							else
								remuRFPE2($var,'poecollective_guadeloupe',$droits);
						}
		}

		/* Ligne 10 (Guadeloupe) */
		/* Caractéristiques DE: */
		//idem national

		/* Caractéristiques formation: */
		//idem national

		/* Rémunération: */
		//idem national

		if(1)
		{
			$array=$display['cifcdd'];
			$array['step']="Une commission paritaire d'agrément examine la prise en charge de votre dossier.La décision de prise en charge peut également porter sur les conditions de rémunérations<br/>Possibilité de prise en charge partielle ou totale des frais de déplacement et/ou d'hébergement. Contactez le Fongecif Guadeloupe (0590 32 10 33)";
			if(isset($droits['cifcdd']))
				if(isInGuadeloupe($domicileDepartementPath))
				{
					arrayInsertAfterKey($droits,'cifcdd',$display,array('cifcdd_guadeloupe'=>$array));
				}
		}

		/* Ligne 11 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrits à Pôle emploi Guadeloupe depuis au moins 6 mois sans interruption.
		//Pas de condition de durée d'inscription pour les publics sous main de justice. (à ajouter au formulaire)

		/* Caractéristiques formation: */
		//F° durée < ou = 1200h (centre + entreprise) et < ou = 12 mois maximum. + code certifinfo
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Région"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr,si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250,31802
		//si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//si intitulé avec BTS, licence, Master, Mastère, ingénieur, diplôme d'ingénieur, capacité, permis (hors parmis B - certifinfo 54664)
		//'15081','13250','31802','84385','54664'

		/* Rémunération: */
		//AREF si ARE -RFF. si durée formation> ou = 350H, RPS. si durée formation < 350h, indemniité de défraiement (se renseinger auprès de votre conseiller)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Le chèque qualification";
			$array['step']="Contactez votre conseiller référent Pôle emploi pour valider avec lui votre projet de formation.<br/>A noter : les demandes d'aide doivent être instruites 45 jours avant le démarrage de l'action de formation.";
			$array['descinfo']="Le Chèque qualification est un dispositif mis en place par le Conseil régional de la Guadeloupe pour répondre aux besoins individuels ne trouvant pas leur réponse dans les programmes collectifs.<br/>Il s’agit de permettre au bénéficiaire de l’aide d’accéder totalement ou partiellement à une formation qualifiante (ex: CCP, CQP, TP,...) en lien direct avec son projet professionnel.";
			$array['info']="";
			$array['cost']="Formation financée dans la limite de 4000 €.";
			$array['cost-plafond']="4000";
			if($situation_inscrit)
				if($niveauscolaire<=CODENIVEAUSCOLAIRE_LICENCEMAITRISE)
					if(!$situation_projetcreationentreprise)
						if(isInGuadeloupe($domicileDepartementPath))
							if($training_duration>=100 && $training_duration<=1200)
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
									if(!in_array($training_formacode,array('15064','44591'))) //VAE
										if(!in_array($training_formacode,array('15081','13250','31802')))
											if(!in_array($training_codecertifinfo,array('84385','54664')))
												if(!$training_contratprofessionalisation && !$training_contratapprentissage)
													if(!hasStrings(array('AFC','BTS','LICENCE','MASTER','MASTERE','INGENIEUR','CAPACITÉ','PERMIS'),$ar['intitule']))
													{
														arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_guadeloupe2'=>$array));
														if($allocation_type=='are')
															remuAREF($var,'finindividuel_guadeloupe2',$droits);
														elseif($training_duration>=350)
															remuRPS($var,'finindividuel_guadeloupe2',$droits);
														else
															remuTEXT($var,'finindividuel_guadeloupe2',$droits);
													}
		}

		/* Ligne 12 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrits à Pôle emploi Guadeloupe pendant 6 mois sans interruption + en création d'entreprise
		//Pas de condition de durée d'inscription pour les publics sous main de justice. (à ajouter au formulaire)

		/* Caractéristiques formation: */
		//F° durée < ou = 1200h (centre + entreprise) et < ou = 12 mois maximum. + code certifinfo
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Région"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250,31802
		//si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//si intitulé avec BTS, licence, Master, Mastère, ingénieur, diplôme d'ingénieur, capacité, permis (hors parmis B - certifinfo 54664)
		//'15081','13250','31802','84385','54664'

		/* Rémunération: */
		//AREF si ARE -RFF. si durée formation> ou = 350H, RPS. si durée formation < 350h, indemniité de défraiement (se renseinger auprès de votre conseiller)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Le chèque qualification &laquo;&nbsp;création d'entreprise&nbsp;&raquo;";
			$array['step']="Si vous êtes bien dans une démarche de création d'entreprise, contactez votre conseiller référent Pôle emploi pour valider avec lui votre projet de formation.<br/>Important : les demandes d'aide doivent être instruites 45 jours avant le démarrage de l'action de formation.";
			$array['descinfo']="Le Chèque qualification &laquo;&nbsp;Création ou reprise d'entreprise&nbsp;&raquo; est mis en oeuvre à titre expérimental. C'est un dispositif du Conseil régional de la Guadeloupe pour répondre aux besoins individuels ne trouvant pas leur réponse dans les programmes collectifs.<br/>Il s’agit de permettre au bénéficiaire de l’aide d’accéder totalement ou partiellement à une formation qualifiante (ex: CCP, CQP, TP,...) en lien direct avec son projet de création ou de reprise d'entreprise.";
			$array['info']="";
			$array['cost']="Formation totalement financée par le Conseil régional";
			if($situation_inscrit)
				if($situation_projetcreationentreprise)
					if(isInGuadeloupe($domicileDepartementPath))
					{
						arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_guadeloupe5'=>$array));
						if($training_duration>=100 && $training_duration<=1200)
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
								if(!in_array($training_formacode,array('15064','44591'))) //VAE
									if(!in_array($training_formacode,array('15081','13250','31802')))
										if(!in_array($training_codecertifinfo,array('84385','54664')))
											if(!$training_contratprofessionalisation && !$training_contratapprentissage)
												if(!hasStrings(array('AFC','BTS','LICENCE','MASTER','MASTERE','INGENIEUR','CAPACITÉ','PERMIS'),$ar['intitule']))
													if($allocation_type=='are')
														remuAREF($var,'finindividuel_guadeloupe5',$droits);
													elseif($training_duration>=350)
														remuRPS($var,'finindividuel_guadeloupe5',$droits);
													else
														remuTEXT($var,'finindividuel_guadeloupe5',$droits);
					}
		}

		/* Ligne 13 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE inscrits à Pôle emploi Guadeloupe pendant 6 mois sans interruption + niveau bac + 5 ou statut cadre
		//Si De sous main de justiice, pas de durée d'inscription exigée

		/* Caractéristiques formation: */
		//F° durée < ou = 1200h (centre + entreprise) et < ou = 12 mois maximum. + code certifinfo
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Région"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terrsi code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250, 31802 si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//si intiulé comprend "permis" hors certifinfo 54664,
		//si intitulé avec BTS, licence, Master, Mastère, ingénieur, diplôme d'ingénieur, capacité,
		//Formations exclues :
		//▪ les formations inscrites au programme collectif du Conseil Régional ou de Pôle emploi ou du Conseil Départemental Guadeloupe ou de l'Etablissement public administratif "Guadeloupe Formation".
		//▪ les formations et diplômes universitaires et d'enseignement supérieur sous statut étudiant.
		//▪ le BEPECASER et tout type de permis (sauf permis B dans le cadre du CPF).
		//▪ les remises à niveau
		//▪ les baccalauréats
		//'15081','13250','31802','84385','54664'

		/* Rémunération: */
		//AREF si ARE -RFF. si durée formation> ou = 350H, RPS. si durée formation < 350h, indemniité de défraiement (se renseinger auprès de votre conseiller)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Le chèque qualification &laquo;&nbsp;cadres et jeunes diplômés&nbsp;&raquo;";
			$array['step']="Contactez votre conseiller référent Pôle emploi pour valider avec lui votre projet de formation.<br/>A noter : les demandes d'aide doivent être instruites 45 jours avant le démarrage de l'action de formation.";
			$array['descinfo']="Le Chèque qualification &laquo;&nbsp;cadres et jeunes diplômés&nbsp;&raquo; est mis en oeuvre à titre expérimental. C'est un dispositif du Conseil régional pour répondre aux besoins individuels ne trouvant pas leur réponse dans les programmes collectifs.<br/>Il s’agit de permettre au bénéficiaire de l’aide d’accéder totalement ou partiellement à une formation qualifiante (ex: CCP, CQP, TP,...) en lien direct avec son projet professionnel.";
			$array['info']="";
			$array['cost']="Formation totalement financée par le Conseil régional";
			if($situation_inscrit)
				if($niveauscolaire>CODENIVEAUSCOLAIRE_LICENCEMAITRISE)
						if(isInGuadeloupe($domicileDepartementPath))
							if($training_duration>=100 && $training_duration<=1200)
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
									if(!in_array($training_formacode,array('15064','44591'))) //VAE
										if(!in_array($training_formacode,array('15081','13250','31802')))
											if(!in_array($training_codecertifinfo,array('84385')))
												if(!$training_contratprofessionalisation && !$training_contratapprentissage)
													if(!hasStrings(array('AFC','BTS','LICENCE','MASTER','MASTERE','INGENIEUR','CAPACITÉ'),$ar['intitule']))
														if(!hasStrings('PERMIS',$ar['intitule']) || in_array($training_codecertifinfo,array('54664')))
														{
															arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_guadeloupe4'=>$array));
															if($allocation_type=='are')
																remuAREF($var,'finindividuel_guadeloupe4',$droits);
															elseif($training_duration>=350)
																remuRPS($var,'finindividuel_guadeloupe4',$droits);
															else
																remuTEXT($var,'finindividuel_guadeloupe4',$droits);
														}
		}

		/* Ligne 14 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE de la région Guadeloupe.

		/* Caractéristiques formation: */
		//Code financeur région+ localisation Guadeloupe

		/* Rémunération: */
		//AREF si ARE. Possibilité de RFF si durée formation> ou = 350H, RPS. si durée formation < 350h, indemniité de défraiement (se renseinger auprès de votre conseiller)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Régional de Formation (PRF)";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.";
			$array['descinfo']="Le Programme Régional de Formation propose des actions de formation financées par le Conseil Régional pour des demandeurs d'emploi ayant besoin d'une certification ou qualification.";
			$array['info']="";
			$array['cost']="Formation totalement financée par le Conseil régional";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_guadeloupe1'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_guadeloupe1',$droits);
						elseif($training_duration>=350)
							remuRPS($var,'actioncollectiveregion_guadeloupe1',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_guadeloupe1',$droits);
					}
		}

		/* Ligne 15 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE et salariés > ou = 16 ans, résident en Guadeloupe

		/* Caractéristiques formation: */
		//F° en Guadeloupe avec code certifinfo, durée > 100H
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Région"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250, si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//si intitulé comprenant "permis" (hors permis B, certifinfo 54664)
		//hors formation avec intitulé comprenant licence, master, mastère, DUT, BTS si DE < 26 ans
		//'15081','13250','84385','54664'

		/* Rémunération: */
		//AREF si ARE. Possibilité de RFF dans certains cas. Sinon pas de rémunération.

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="AIF Région";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.<br/>Les dossiers doivent être déposés au moins 3 mois avant le début de la formation.";
			$array['descinfo']="C'est une aide individuelle réservée aux formations qualifiantes attribuée par le Conseil Régional de la Guadeloupe. L'aide est exclusivement réservée à des demandeurs d'emploi ou des salariés n'ayant bénéficié d'aucune autre aide de la Région.<br/>Cette aide peut venir en complément d'un montant accordé par un autre financeur tel que l'AIF (Pôle emploi), l'ADI pour les publics BRSA, le FONGECIF ou un OPCA pour les salariés, le CPF pour un salarié ou un demandeur d'emploi ou en complément d'un apport personnel.";
			$array['info']="Les formations concernées doivent être qualifiantes.<br/>Les dossiers doivent être déposés au moins 3 mois avant le début de la formation.<br/>Pour les formations effectuées hors de la Guadeloupe, résider hors de Guadeloupe mais pas depuis plus de 6 mois.";
			$array['cost']="Prise en charge partielle par le Conseil régional des coûts pédagogiques, en complément d'un financement accordé par un autre financeur, du CPF ou d'un apport personnel";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
					if($age>=16)
						if($training_duration>=100)
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces))
								if(!in_array($training_formacode,array('15081','13250')))
									if(!in_array($training_codecertifinfo,array('84385')))
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
											if(!in_array($training_codecertifinfo,array('54664')))
												if(!hasStrings(array('PERMIS','LICENCE','MASTER','MASTERE','DUT','BTS'),$ar['intitule']))
												{
													arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_guadeloupe3'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'finindividuel_guadeloupe3',$droits);
													else
														remuTEXT($var,'finindividuel_guadeloupe3',$droits);
												}
		}

		/* Ligne 16 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE et salariés > ou = 16 ans, ayant résidé en Guadeloupe dans les 6 mois qui précèdent l'entrée en formation

		/* Caractéristiques formation: */
		//F° France entière avec code certifinfo, durée > 100H
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Région"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250, si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//si intitulé comprenant "permis" (hors permis B, certifinfo 54664)
		//si formation avec intitulé comprenant licence, master, mastère, DUT, BTS si DE < 26 ans
		//'15081','13250','84385','54664'

		/* Rémunération: */
		//AREF si ARE. Possibilité de RFF . Sinon pas de rémunération.

		if(0)
		{
			$array=array();
			$array['pri']="";
			$array['title']="AIF Région";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.<br/>Les dossiers doivent être déposés au moins 3 mois avant le début de la formation.";
			$array['descinfo']="C'est une aide individuelle réservée aux formations qualifiantes attribuée par le Conseil Régional de la Guadeloupe. L'aide est exclusivement réservée à des demandeurs d'emploi ou des salariés n'ayant bénéficié d'aucune autre aide de la Région.<br/>Cette aide peut venir en complément d'un montant accordé par un autre financeur tel que l'AIF (Pôle emploi), l'ADI pour les publics BRSA, le FONGECIF ou un OPCA pour les salariés, le CPF pour un salarié ou un demandeur d'emploi ou en complément d'un apport personnel.";
			$array['info']="Les formations concernées doivent être qualifiantes.<br/>Les dossiers doivent être déposés au moins 3 mois avant le début de la formation.<br/>Pour les formations effectuées hors de la Guadeloupe, résider hors de Guadeloupe mais pas depuis plus de 6 mois.";
			$array['cost']="Prise en charge partielle par le Conseil régional, en complément d'un financement d'un autre financeur, du CPF ou d'un apport personnel";
			if($situation_inscrit)
				if(!isInGuadeloupe($domicileDepartementPath))
					if($age>=16)
						if($training_duration>=100)
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces))
								if(!in_array($training_formacode,array('15081','13250')))
									if(!in_array($training_codecertifinfo,array('84385')))
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
											if(!in_array($training_codecertifinfo,array('54664')))
												if(!hasStrings(array('PERMIS','LICENCE','MASTER','MASTERE','DUT','BTS'),$ar['intitule']))
												{
													arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_guadeloupe7'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'finindividuel_guadeloupe7',$droits);
													else
														remuTEXT($var,'finindividuel_guadeloupe7',$droits);
												}
		}

		/* Ligne 17 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE Guadeloupe

		/* Caractéristiques formation: */
		//Formations + tag CPF COPANEF - COPAREF
		//hors tag "contrat d'apprentissage" hors Tag "contrat de professionnalisation"; hors intitulé comprend "POEC" ou Préparation Opérationnelle à l'Emploi Collective

		/* Rémunération: */
		//AREF sinon RFPE* (*si votre projet est validé par un conseiller Pôle emploi\")

		if(0) //Desactivée pour l'instant. On laisse les règles nationales cpf s'appliquer
		{
			$array=array();
			$array['pri']="";
			$array['title']="Le Compte Personnel Formation (CPF)";
			$array['step']="Contactez votre conseiller référent Pôle emploi pour valider avec lui votre projet de formation.<br/>Créez votre compte CPF sur le site internet www.moncompteformation.gouv.fr";
			$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle.<br/>SI vousavez des heures sur votre compte,vous pouvez les mobiliser pour financer toute ou partie d'une formation qualifiante.<br/>Vous pouvez utiliser votre CPF même si vos heures créditées sont inférieures à la durée totale de la formation. Vérifiez auprès de l'organisme de formation que vous pouvez valider partiellement la formation sous forme de blocs de compétence. Vous aurez ensuite cinq ans pour valider l'intégralité de la formation qualifiante.<br/>Si vous avez donné votre accord, votre CPF pourra être mobilisé pour une formation financée par le Conseil régional ou par Pôle emploi.";
			$array['info']="";
			$array['cost']="Formation totalement ou partiellement financée sur la base de 9 € multipliée par vos heures CPF acquises.<br/>Dans le cas d'un financement partiel, vous pouvez prendre en charge vous-même le reste à charge ou faire appel à des co-financements.";
			if($situation_inscrit)
				if(isInGuadeloupe($domicileDepartementPath))
					if($situation_creditheurescpfconnu>0)
						if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!hasStrings(array('POEC','PRÉPARATION OPÉRATIONNELLE À L\'EMPLOI COLLECTIVE'),$ar['intitule']))
								{
									arrayInsertAfterKey($droits,'cpf',$display,array('cpf_guadeloupe1'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'cpf_guadeloupe1',$droits);
									else
										remuRFPE2($var,'cpf_guadeloupe1',$droits);
									$droits['cpf_guadeloupe1']['remu'].="<br/>* si votre projet est validé par un conseiller Pôle emploi.";
								}
		}

		/* Ligne 18 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE et RSA en Guadeloupe.

		/* Caractéristiques formation: */
		//F° France entière avec code certifinfo, durée > 100H
		//hors F° :
		//si Intitulé comportant "AFC"
		//si code financeur "PE" collectif
		//si code financeur "'Région"
		//si code financeur "OPCA"
		//si code financeur Etat
		//si code financeur : coll terr, si code financeur : coll terr, coll terr autres, communes etc.
		//si Formacode 15081, 13250, si certifinfo 84385
		//si tag "contrat d'apprentissage"
		//si Tag "contrat de professionnalisation"
		//'15081','13250','84385'

		/* Rémunération: */
		//Pas de rémunération,

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="AIF département";
			$array['step']="Contactez votre référent ADI pour valider avec lui votre projet de formation.";
			$array['descinfo']="Le Conseil Départemental de la Guadeloupe accorde des aides individuelles pour les formations des allocataires du RSA.<br/>Cette aide vient généralement en complément d'un montant accordé par un autre financeur tel que le Conseil Régional ou Pôle emploi.";
			$array['info']="Attribution de l'aide suite au passage du dossier devant une Commission.";
			$array['cost']="Prise en charge partielle des côuts pédagogiques.";
			if($situation_inscrit && $allocation_type=='rsa')
				if(isInGuadeloupe($domicileDepartementPath))
					if($training_duration>100)
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
							if(!in_array($training_formacode,array('15064','44591')))
								if(!in_array($training_formacode,array('15081','13250')))
									if(!in_array($training_codecertifinfo,array('84385')))
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										{
											arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_guadeloupe6'=>$array));
											remuFORM2($var,'finindividuel_guadeloupe6',$droits);
										}
		}

		/* Ligne 19 (Guadeloupe) */
		/* Caractéristiques DE: */
		//DE RSA en Guadeloupe

		/* Caractéristiques formation: */
		//F) en Guadeloupe + code financeur 8 : coll terr Conseil général

		/* Rémunération: */
		//maintient du RSA

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Actions de formation collectives département";
			$array['step']="Contactez votre référent ADI pour valider avec lui votre projet de formation.";
			$array['descinfo']="Les actions de formation collectives du Conseil départemental sont proposées pour les bénéficiares du RSA.<br/>Elles sont visibles sur le site internet du Conseil départemental de la Guadeloupe, dans le Programme Départemental d'Insertion.";
			$array['info']="";
			$array['cost']="Formation entièrement prise en charge";
			if($situation_inscrit && $allocation_type=='rsa')
				if(isInGuadeloupe($domicileDepartementPath) && isInGuadeloupe($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'conseildepartementalcollectif',$display,array('conseildepartementalcollectif_guadeloupe1'=>$array));
						remuFORM2($var,'conseildepartementalcollectif_guadeloupe1',$droits);
					}
		}
	}
?>
