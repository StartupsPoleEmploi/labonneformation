<?php
	/* Règles nationales **************************************************************************************************/
	function reglesNationalesNew($var,&$droits,&$display)
	{
		extract($var);
		$locationName='National';
		/* Ligne 1 : contrat d'apprentissage */
		//if(0) //Desactivée pour l'instant. On laisse les règles nationales cpf s'appliquer
		//	if(isInRegionBourgogneFrancheComte($domicilePath))
		//	{
		//		$array=array();
		//		$array['title']="Contrat d'apprentissage";
		//		$array['step']=array("Activer votre compte personnel sur <a href=\"http://www.moncompteformation.gouv.fr/\" target=\"_blank\">www.moncompteformation.gouv.fr</a><br/>vous munir de votre numéro de sécurité social et d'une adresse mail.<br />Une fois le compte activé, les heures créditées à votre compte s'affiche : 24h/an pour un emploi à temps plein jusqu'à 120h puis 12H/an jusqu'à 150h. <br/>Il est aussi possible de sauvegarder ses heures DIF non utilisées pour une utilisation au plus tard jusqu'au 31 décembre 2020.","Vérifier que la formation souhaitée est bien éligible au CPF : la formation doit permettre d'accéder à une certification éligible (Ex : CAP cuisinier, BPJEPS spécialité loisirs tous publics, etc.).","Vérifier l'éligibilité sur <a href=\"http://www.moncompteformation.gouv.fr/espace-professionnels/professionnels-de-lemploi-et-de-la-formation-professionnelle-0\" target=\"_blank\">www.moncompteformation.gouv.fr</a> (liste COPANEF et liste COPAREF Bourgogne-Franche-Comté &laquo;en recherche d'emploi&raquo;)","Prendre contact avec votre conseiller référent emploi.");
		//		$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle. Le CPF permet  de favoriser l'accès de son titulaire à la formation professionnelle, indépendamment de son statut pour acquériri un meilleur niveau de qualification.";
		//		$array['info']="Il est possible de financer avec son CPF une formation permettant de valider partiellement une certification éligible. On parle alors de &laquo;bloc de compétence&raquo;. Il convient de vérifier auprès de l'organisme de formation que la formation est bien découpée en bloc de compétence. Une fois un premier bloc de compétence validé, son titulaire dispose de 5 ans pour valider totalement la certification recherchée.";
		//		$array['cost']="La durée de la prise en charge correspond au nombre d'heures créditées sur le CPF (ex si CPF = 120h et si la formation = 200h, 120h seront pris en charge au titre du CPF)<br />Le taux horaire maximal de prise en charge au titre du CPF pour un demandeur d'emploi est de 9€/heure (financé par le Fonds Paritaire de Sécurisation des Parcours Professionnels).<br />Si le projet de formation est validé par Pôle emploi, une AIF (cf. AIF) peut permettre de couvrir le reste à charge.<br />Si le projet n'est pas validé par Pôle emploi, le reste à charge doit être financé par un apport personnel ou par un co-financeur autre que Pôle emploi";
		//		$array['loc']=$locationName;
		//		$display['contratapprentissage']=$array;
		//		
		//		if($situation_inscrit)
		//		{
		//			if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
		//			{
		//				if($situation_creditheurescpfconnu && $situation_creditheurescpf>=1)
		//				{
		//					if($allocation_type=='are')
		//						remuAREF($var,'contratapprentissage',$droits);
		//					else
		//						remuTEXT($var,'contratapprentissage',$droits,"Si vous ne bénéficiez pas de l'AREF, vous toucherez une rémunération formation Pôle emploi (RFPE) si la formation est validée par Pôle emploi. Si votre projet n'est pas validé, vous ne toucherez pas de rémunération.");
		//				}
		//			}
		//		}
		//	}

		/* Ligne 2 */
		/* Caractéristiques formation: */
		//tag contrat d'apprentissage

		/* Caractéristiques DE: */
		//tout public de 16 à 25 ans inclus
		//et jusqu'à 30 ans des postulant(e)s domiciliés en Bretagne ;Bourgogne-Franche-Comté ;Centre-Val de Loire ;Grand Est ;Hauts-de-France ;Nouvelle-Aquitaine ;Pays de la Loire.
		//(http://travail-emploi.gouv.fr/formation-professionnelle/formation-en-alternance/contrat-apprentissage)
		//25 à 30 ans inclus si nv contrat d'’apprentissage de niveau sup & si le contrat d’apprentissage est souscrit dans un délai maximum d’un an après l’expiration du précédent contrat
		//jusqu'à 30 ans inclus & rupture du contrat pour des causes indépendantes de leur volonté ou suite à une inaptitude physique et temporaire & nouveau contrat d’apprentissage souscrit dans un délai maximum d’un an après l’expiration du précédent contrat
		//pas de limite d'äge pour :
		//personne reconnue travailleur handicapé
		//personne qui a un projet de création ou de reprise d’entreprise dont la réalisation est subordonnée à l’obtention du diplôme ou titre sanctionnant la formation poursuivie.
		//sportif de haut niveau sur la liste arrêtée par le ministre chargé des sports
		if(1)
		{
			//$array=array();
			//$array['pri']="1";
			//$array['title']="Contrat d'apprentissage";
			//$array['step']="Il vous faut trouver un employeur pour pouvoir effectuer cette formation en alternance avec un emploi.<br/>Prenez contact avec l'organisme de formation et<br/>contactez les employeurs susceptibles de vous embaucher en contrat d’apprentissage<br/>[OE cont=\"ALTERNANCE\"]";
			//$array['descinfo']="Le contrat d'apprentissage est un contrat de travail qui permet de se former en vue d'acquérir un diplôme d'Etat ou un titre professionnel tout en étant salarié d’une entreprise.<br/>L’avantage : la formation repose sur le principe de l’alternance entre enseignement théorique en centre de formation et mise en pratique chez l’employeur avec lequel l’apprenti a signé son contrat.";
			//$array['cost']="Formation totalement financée";
			//$display['contratapprentissage']=$array;
			if($training_contratapprentissage)
			{
				$bonDomicile=isInRegionBretagne($domicilePath) || isInRegionBourgogneFrancheComte($domicilePath) ||
				             isInRegionCentreValDeLoire($domicilePath) || isInRegionGrandEst($domicilePath) ||
				             isInRegionHautsDeFrance($domicilePath) || isInRegionNouvelleAquitaine($domicilePath) ||
				             isInRegionPaysDeLaLoire($domicilePath)?true:false;
				$ok=false;
				if($age>=16 && ($age<=25 || ($age<=30 && $bonDomicile)))
					$ok=true;
				if(!$ok && ($situation_th || $situation_projetcreationentreprise))
					$ok=true;
				if($ok)
				{
					remuContratApprentissage2($var,'contratapprentissage',$droits);
				}
			}
		}

		/* Ligne 3 */
		/* Caractéristiques formation: */
		//Tag contrat de professionnalisation

		/* Caractéristiques DE: */
		//-Jeunes de moins de 26 ans.
		//-Demandeurs d'emploi de 26 ans et plus, inscrits à Pôle Emploi.
		//-personnes bénéficiaires de minima sociaux : RSA, ASS, AAH.
		//- Personnes sortant d'un contrat unique d'insertion : contrat d'accompagnement dans l'emploi (CUI-CAE) ou contrat initiative emploi (CUI-CIE).
		//http://travail-emploi.gouv.fr/formation-professionnelle/formation-en-alternance/article/le-contrat-de-professionnalisation
		if(1)
		{
			//$array=array();
			//$array['pri']="2";
			//$array['title']="Contrat de professionnalisation";
			//$array['step']="Il vous faut trouver un employeur pour pouvoir effectuer cette formation en alternance avec un emploi.<br/>Prenez contact avec l'organisme de formation et<br/>contactez les employeurs susceptibles de vous embaucher en contrat de professionnalisation :<br/>[OE cont=\"PROFESSIONALISATION\"]";
			//$array['descinfo']="Le contrat de professionnalisation en un contrat qui permet de se former pour d'acquérir une qualification professionnelle reconnue par l’État et/ou la branche professionnelle dans un objectif de retour à l’emploi.<br/>Il repose sur le principe de l’alternance entre enseignement théorique en centre de formation et mise en pratique chez l’employeur.";
			//$array['cost']="Formation totalement financée";
			//$display['contratdepro']=$array;
			if($training_contratprofessionalisation)
				if($age<26 || ($age>=26 && $situation_inscrit) || in_array($allocation_type,array('rsa','ass','aah')) ||
				    $situation_personnesortantcontrataide)
				{
					remuContratDePro2($var,'contratdepro',$droits);
				}
		}

		/* Ligne 4 */
		/* Caractéristiques formation: */
		//Tag contrat pacte

		/* Caractéristiques DE: */
		//- 16 à 25 ans inclus
		//- &laquo;&nbsp;les jeunes âgés de 16 à 25 ans révolus sortis du système scolaire sans diplôme et sans qualification professionnelle reconnue.
		//- les jeunes de 16 à 25 ans révolus qui ont quitté l’école sans obtenir leur bac général, technologique ou professionnel (soit les niveaux VI, Vbis ou V).&nbsp;&raquo;
		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="1 (en rouge : ne pas implémenter cette règle)";
		//	$array['title']="PACTE (parcours d’accès aux carrières territoriales, hospitalières et de l’Etat)";
		//	$array['step']="";
		//	$array['descinfo']="";
		//	$array['cost']="Formation totalement financée";
		//	$display['PACTE (parcours d’accès aux carrières territoriales, hospitalières et de l’Etat)']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 5 */
		/* Caractéristiques formation: */
		//Toute Formation, hors tag code financeur Région, PE, OPCA, Etat, Col ter
		//hors tag contrat d'apprentissage et hors Tag contrat de professionnalisation

		/* Caractéristiques DE: */
		//Tout DE
		//(hors salarié IAE ou contrat aidé)
		if(1) //A finir: la personne doit-^etre en contrat aidé. Rien avoir avec sortant d'un contrat aidé => à corriger
		{
			//$array=array();
			//$array['pri']="2";
			//$array['title']="Formations avant embauche :<br/>Action de Formation Préalable au Recrutement (AFPR) et Préparation Opérationnelle à l’Emploi Individuelle (POEI)";
			//$array['step']="Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDI ou un CDD d'au moins 6 mois. Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 400h.<br/>[OE cont=\"CDD\"]<br/>En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier le plan de formation.";
			//$array['descinfo']="L'Action de Formation Préalable au Recrutement (AFPR) et la Préparation Opérationnelle à l’Emploi Individuelle (POEI) sont des aides au financement de formations (de 400 heures maximum) préalable à un recrutement.<br/>L'objectif est d'acquérir des compétences professionnelles requises pour occuper l'emploi correspondant à une offre déposée auprès de Pôle emploi, pour un CDD d'au moins 6 mois ou un CDI.<br/>Cette aide est également mobilisable en préalable à un contrat en alternance d'au moins 12 mois (contrat de professionnaliisation ou contrat d'apprentissage)";
			//$display['afprpoei']=$array;
			$ok=false;
			if(!in_array($training_codecertifinfo,array('83899','84385','54664')))
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
					if(!$training_contratapprentissage && !$training_contratprofessionalisation)
						if(!$situation_projetcreationentreprise)
							if(!$situation_salarie)
								if(!hasStrings(array('VAE','BP','BAC','BAC PRO','BAC TECHNO','BAC TECHNOLOGIQUE','BREVET PROFESSIONEL','BPJEPS','BTS','BTFA','BACHELOR','CAPACITE','CAP','DAEU','DEUST','DIPLOME D\'ETAT','DIPLOME NATIONAL','DIPLOME D\'ACCES AUX ETUDES UNIVERSITAIRES','INGENIEUR','LICENCE','MASTER','MASTERE'),$ar['intitule']))
									if(!in_array($training_racineformacode,array('430','434','440','150')) || $training_formacode=='44067')
										if(!in_array($training_formacode,array('13030','50245','50445','50145','50349','50249','13250','31802','44591')))
											$ok=true;
			if($ok || hasStrings(array('POEI'),$ar['intitule']))
			{
				//TODO faire des tests avant commit
				if($situation_personneencourscontrataide || hasStrings(array('POEI'),$ar['intitule']))
				{
					if($allocation_type=='are')
						remuAREF($var,'poei',$droits);
					else
						remuRFPE2($var,'poei',$droits);
				}elseif(!$situation_personnecontrataide || $situation_personnesortantcontrataide)
				{
					if($allocation_type=='are')
						remuAREF($var,'afprpoei',$droits);
					else
						remuRFPE2($var,'afprpoei',$droits);
				}
			}
		}

		/* Ligne 6 */
		/* Caractéristiques formation: */
		/*
		Toute Formation + formacode 44067, hors tag code financeur Région, PE, OPCA, Etat, Col ter hors tag "contrat d'apprentissage"
		+ hors Tag "contrat de professionnalisation"
		 hors formacode des domaines 430 ; 434; 440; 150
		hors formacode 13030; 43409;43436;15084;15073;15094;15093, 15061, 15081
		hors ROME G1204 et L 1401  ou formacodes du domaine 154 (sauf 15447, 15448;15457;15458) et formacodes 50245;50445;50145;50349;50249
		*/

		/* Caractéristiques DE: */
		//Tout salarié IAE ou contrat aidé
		if(1) 
		{
			//$array=array();
			//$array['pri']="2";
			//$array['title']="Formations avant embauche : POEI<br/>Préparation Opérationnelle à l’Emploi Individuelle (POEI)";
			//$array['step']="Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDI ou un CDD d'au moins 12 mois. Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 400h.<br/>[OE]<br/>En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier le plan de formation.";
			//$array['descinfo']="La Préparation Opérationnelle à l’Emploi Individuelle (POEI) est une aide au financement de formations (d'une durée de 400 heures maximum) préalable à un recrutement. L'objectif est d'acquérir des compétences professionnelles requises pour occuper l'emploi correspondant à une offre déposée auprès de Pôle emploi, pour un CDD d'au moins 12 mois ou un CDI.<br/>Cette aide est également mobilisable en préalable à un contrat en alternance d'au moins 12 mois (contrat de professionnaliisation ou contrat d'apprentissage)";
			//$array['cost']="Formation totalement financée dans la limite de 400h.";
			//$display['poei']=$array;
			$ok=false;
			if(!in_array($training_codecertifinfo,array(68950)))
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
					if(!$training_contratapprentissage && !$training_contratprofessionalisation)
						if($situation_salarie && $situation_personneencourscontrataide)
							if(!hasStrings(array('VAE','BP','BAC','BAC PRO','BAC TECHNO','BAC TECHNOLOGIQUE','BREVET PROFESSIONEL','BPJEPS','BTS','BTFA','BACHELOR','CAPACITE','CAP','DAEU','DEUST','DIPLOME D\'ETAT','DIPLOME NATIONAL','DIPLOME D\'ACCES AUX ETUDES UNIVERSITAIRES','INGENIEUR','LICENCE','MASTER','MASTERE'),$ar['intitule']))
								if(!in_array($training_racineformacode, array('430','434','440','150')) || $training_formacode=='44067')
									if(!in_array($training_formacode,array('13030','50245','50445','50145','50349','50249','13250','31802','44591')))
										$ok=true;
										
			if($ok || hasStrings(array('POEI'),$ar['intitule']))
			{
				if($allocation_type=='are')
					remuAREF($var,'poei',$droits);
				else
					remuRFPE2($var,'poei',$droits);
			}
		}

		/* Ligne 7 */
		/* Caractéristiques formation: */
		/*
		Toute Formation + formacode 44067, hors tag code financeur Région, PE, OPCA, Etat, Col ter hors tag "contrat d'apprentissage"
		+ hors Tag "contrat de professionnalisation"
		 hors formacode des domaines 430 ; 434; 440; 150
		hors formacode 13030; 43409;43436;15084;15073;15094;15093, 15061, 15081
		hors ROME G1204 et L 1401  ou formacodes du domaine 154 (sauf 15447, 15448;15457;15458) et formacodes 50245;50445;50145;50349;50249
		*/

		/* Caractéristiques DE: */
		//- DE longue durée : DE ayant 12 mois d’inscription en catégorie A3 dans
		//les 15 derniers mois qui précèdent l’entrée en formation.
		//- DE non Qualifiés : DE sans qualification
		//if(0) //A finir:
		//{
		//	$array=array();
		//	$array['pri']="2";
		//	$array['title']="Action de Formation Préalable au Recrutement (AFPR expérimentale 200h) pour embauche de 4 à 6 mois";
		//	$array['step']="Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDD de 4 à 6 mois. L'emploi proposé doit se situer dans l’un des départements ou territoires expérimentaux suivants : Alpes-Maritimes (06); Var (83); Drôme (26); Haute-Savoie (74); Loire Atlantique (44); Maine et Loire (49); Sarthe (72); Vendée (85); Gironde (33); Ardennes (08); Vosges (88); Corse (2A - 2B); Saône et Loire (71); Essonne (91); Seine Saint Denis (93); Tarn (81); Finistère (29); Eure (27); Aisne (02); Eure et Loire (28) Guyane; Guadeloupe; Réunion-Mayotte; Martinique.<br/>Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 200h.<br/>Pour trouver cet employeur [OE cont=\"CDD\"]<br/>En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier le plan de formation.";
		//	$array['descinfo']="L'Action de Formation Préalable au Recrutement expérimentale (AFPR expérimentale) est une aide au financement de formations d'une durée de 200 heures maximum préalable à un recrutement en CDD de 4 à 6 mois. L'objectif est d'acquérir des compétences professionnelles requises pour occuper l'emploi correspondant à une offre déposée auprès de Pôle emploi, pour un CDD de 4 à 6 mois.<br/>L'embauche doit se situer dans l’un des départements ou territoires expérimentaux suivants : Alpes-Maritimes (06); Var (83); Drôme (26); Haute-Savoie (74); Loire Atlantique (44); Maine et Loire (49); Sarthe (72); Vendée (85); Gironde (33); Ardennes (08); Vosges (88); Corse (2A - 2B); Saône et Loire (71); Essonne (91); Seine Saint Denis (93); Tarn (81); Finistère (29); Eure (27); Aisne (02); Eure et Loire (28) : Guyane; Guadeloupe; Réunion-Mayotte; Martinique.";
		//	$array['cost']="Formation totalement financée dans la limite de 200h.";
		//	arrayInsertAfterKey($droits,'afprpoei',$display,array('afpr'=>$array));
		//	if(!in_array($training_codecertifinfo,array(68950)))
		//		if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
		//			if(!$training_contratprofessionalisation)
		//				if($niveauscolaire==CODENIVEAUSCOLAIRE_SANSDIPLOME)
		//					if(in_array($domicileDepartementPath,array(LOCATIONPATH_AISNE,LOCATIONPATH_ALPESMARITIMES,LOCATIONPATH_ARDENNES,LOCATIONPATH_CORSEDUSUD,LOCATIONPATH_DROME,LOCATIONPATH_ESSONNE,LOCATIONPATH_EURE,LOCATIONPATH_EUREETLOIR,LOCATIONPATH_FINISTÈRE,LOCATIONPATH_GIRONDE,LOCATIONPATH_GUADELOUPE,LOCATIONPATH_GUYANE,LOCATIONPATH_HAUTECORSE,LOCATIONPATH_HAUTESAVOIE,LOCATIONPATH_LAREUNION,LOCATIONPATH_LOIREATLANTIQUE,LOCATIONPATH_MAINEETLOIRE,LOCATIONPATH_MARTINIQUE,LOCATIONPATH_MAYOTTE,LOCATIONPATH_SAONEETLOIRE,LOCATIONPATH_SARTHE,LOCATIONPATH_SEINESTDENIS,LOCATIONPATH_TARN,LOCATIONPATH_VAR)))
		//						if(condRff($var))
		//							if(!hasStrings(array('VAE','BP','BAC','BAC PRO','BAC TECHNO','BAC TECHNOLOGIQUE','BREVET PROFESSIONEL','BPJEPS','BTS','BTFA','BACHELOR','CAPACITE','CAP','DAEU','DEUST','DIPLOME D\'ETAT','DIPLOME NATIONAL','DIPLOME D\'ACCES AUX ETUDES UNIVERSITAIRES','INGENIEUR','LICENCE','MASTER','MASTERE'),$ar['intitule']))
		//								if(!in_array($training_racineformacode, array('430','434','440','150')) || $training_formacode=='44067')
		//									if(!in_array($training_formacode,array('13030','50245','50445','50145','50349','50249','13250','31802')))
		//									{
		//										if($allocation_type=='are')
		//											remuAREF($var,'afpr',$droits);
		//										else
		//											remuRFPE2($var,'afpr',$droits);
		//									}
		//}

		/* Ligne 8 */
		/* Caractéristiques formation: */
		/*
		Toute Formation + formacode 44067, hors tag code financeur Région, PE, OPCA, Etat, Col ter hors tag "contrat d'apprentissage"
		+ hors Tag "contrat de professionnalisation"
		 hors formacode des domaines 430 ; 434; 440; 150
		hors formacode 13030; 43409;43436;15084;15073;15094;15093, 15061, 15081
		hors ROME G1204 et L 1401  ou formacodes du domaine 154 (sauf 15447, 15448;15457;15458) et formacodes 50245;50445;50145;50349;50249
		*/

		/* Caractéristiques DE: */
		//- DE longue durée : DE ayant 12 mois d’inscription en catégorie A3 dans
		//les 15 derniers mois qui précèdent l’entrée en formation.
		//- DE non Qualifiés : DE ayant les niveaux de formation suivants : aucune formation scolaire
		//if(0)
		//{
		//	$array=array();
		//	$array['pri']="2";
		//	$array['title']="Action de Formation Préalable au Recrutement (AFPR expérimentale 600h) pour embauche de 6 mois à 12 mois";
		//	$array['step']="Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDD de 6 à 12 mois.<br/>Vérifiez avec un conseiller Pôle emploi qu'il s'agit bien d'un métier pour lequel il est difficile de recruter et que l'emploi proposése situe dans l’un des départements expérimentaux suivants : Alpes-Maritimes (06); Var (83); Drôme (26); Haute-Savoie (74); Loire Atlantique (44); Maine et Loire (49); Sarthe (72); Vendée (85); Gironde (33); Ardennes (08); Vosges (88); Corse (2A - 2B); Saône et Loire (71); Essonne (91); Seine Saint Denis (93); Tarn (81); Finistère (29); Eure (27); Aisne (02); Eure et Loire (28) et des territoires d'Outre-Mer : Guyane; Guadeloupe; Réunion-Mayotte; Martinique.<br/>Pour trouver cet employeur:<br/>[OE cont=\"CDD\"]<br/>Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 600h.<br/>En cas de réponse positive de l'employeur, votre conseiller Pôle emploi finalisera avec ce dernier le plan de formation.";
		//	$array['descinfo']="A titre expérimental, l'Action de Formation Préalable au Recrutement (AFPR) est une aide au financement de formations d'une durée maximale de 600 h préalable à un recrutement en CDD de 6 mois à 12 mois pour un métier pour lequel il est difficile de recruter.<br/>L'embauche doit se situer dans l’un des départements ou territoires expérimentaux suivants : Alpes-Maritimes (06); Var (83); Drôme (26); Haute-Savoie (74); Loire Atlantique (44); Maine et Loire (49); Sarthe (72); Vendée (85); Gironde (33); Ardennes (08); Vosges (88); Corse (2A - 2B); Saône et Loire (71); Essonne (91); Seine Saint Denis (93); Tarn (81); Finistère (29); Eure (27); Aisne (02); Eure et Loire (28) Guyane; Guadeloupe; Réunion-Mayotte; Martinique.";
		//	$array['cost']="Formation totalement financée dans la limite de 600h";
		//	arrayInsertAfterKey($droits,'afprpoei',$display,array('afpr2'=>$array));
		//	if(!in_array($training_codecertifinfo,array(68950)))
		//		if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
		//		   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
		//			if(!$training_contratprofessionalisation)
		//				if($niveauscolaire==CODENIVEAUSCOLAIRE_SANSDIPLOME)
		//					if(!hasStrings(array('VAE','BP','BAC','BAC PRO','BAC TECHNO','BAC TECHNOLOGIQUE','BREVET PROFESSIONEL','BPJEPS','BTS','BTFA','BACHELOR','CAPACITE','CAP','DAEU','DEUST','DIPLOME D\'ETAT','DIPLOME NATIONAL','DIPLOME D\'ACCES AUX ETUDES UNIVERSITAIRES','INGENIEUR','LICENCE','MASTER','MASTERE'),$ar['intitule']))
		//						if(in_array($domicileDepartementPath,array(LOCATIONPATH_AISNE,LOCATIONPATH_ALPESMARITIMES,LOCATIONPATH_ARDENNES,LOCATIONPATH_CORSEDUSUD,LOCATIONPATH_DROME,LOCATIONPATH_ESSONNE,LOCATIONPATH_EURE,LOCATIONPATH_EUREETLOIR,LOCATIONPATH_FINISTÈRE,LOCATIONPATH_GIRONDE,LOCATIONPATH_GUADELOUPE,LOCATIONPATH_GUYANE,LOCATIONPATH_HAUTECORSE,LOCATIONPATH_HAUTESAVOIE,LOCATIONPATH_LAREUNION,LOCATIONPATH_LOIREATLANTIQUE,LOCATIONPATH_MAINEETLOIRE,LOCATIONPATH_MARTINIQUE,LOCATIONPATH_MAYOTTE,LOCATIONPATH_SAONEETLOIRE,LOCATIONPATH_SARTHE,LOCATIONPATH_SEINESTDENIS,LOCATIONPATH_TARN,LOCATIONPATH_VAR)))
		//							if(condRff($var))
		//							{
		//								if(!in_array($training_racineformacode, array('430','434','440','150')) || $training_formacode=='44067')
		//								{
		//									if(!in_array($training_formacode,array('13030','50245','50445','50145','50349','50249','13250','31802')))
		//									{
		//										if($allocation_type=='are')
		//											remuAREF($var,'afpr2',$droits);
		//										else
		//											remuRFPE2($var,'afpr2',$droits);
		//									}
		//								}
		//							}
		//}

		/* Ligne 9 */
		/* Caractéristiques formation: */
		//tag : toute formation hors code financeur PE, OPCA, Région
		//hors tag contrat d'apprentissag + hors Tag contrat de professionnalisation
		//durée max : 1 an ou 1200H si intensité hebdomadaire <30h

		/* Caractéristiques DE: */
		//- anciens titulaires de CDD ayant 24 mois, consécutifs ou non, en qualité de salarié,
		//  quelle que soit la nature des contrats successifs, au cours des 5 dernières années ;
		//  dont 4 mois, consécutifs ou non, sous CDD, au cours des 12 derniers mois.
		//  (sauf contrat d'apprentissage et de professionnalisation, contrat unique d'insertion-contrat d'accompagnement dans l'emploi (CUI-CAE), CDD se poursuivant par un CDI, CDD conclu avec des jeunes au cours de leur cursus scolaire ou universitaire)
		if(1)
		{
			//$array=array();
			//$array['pri']="3";
			//$array['title']="CIF - CDD";
			//$array['step']="Retirer auprès de l'OPACIF un dossier de demande de prise en charge Vérifier les délais piour faire cette demande.<br/>Indiquer votre accord pour compléter, si besoin, le financement de la formation avec votre Compte Personnel de Formation.";
			//$array['descinfo']="Le Congé Individuel de Formation (CIF) permet à tout salarié en contrat ou ancien titulaire de contrat à durée déterminée (CDD) de suivre, à son initiative et à titre individuel, une formation, dans le but de :<br/>se reconvertir<br/>ou acquérir un niveau supérieur de qualification ou une 1ère qualification<br/>ou se perfectionner<br/>ou s'ouvrir plus largement à la culture et à la vie sociale.";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['cifcdd']=$array;
			if($age>=26 && $situation_cdd5years && $situation_cdd12months)
				if($training_datebegin && $situation_cdddatedefin && Tools::calcDiffDate($training_datebegin,$situation_cdddatedefin)<=12)
					if(!in_array($training_formacode,array('15064','44591')))
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
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if($training_dureeenmois<=12 || ($training_nbheurestotales<=1200 && $training_intensitehebdomadaire<30))
									if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
										if($situation_cdd12months)
										{
											remuFORMCDD($var,'cifcdd',$droits);
										}
		}

		/* Ligne 10 */
		/* Caractéristiques formation: */
		//tag : toute formation hors code financeur PE, OPCA, Région, hors tag contrat d'apprentissage
		//hors Tag contrat de professionnalisation
		//durée max : 1 an ou 1200H
		//début au plus tard 12 mois après le terme du contrat C10

		/* Caractéristiques DE: */
		//- moins de 26 ans
		//- avoir travaillé 12 mois (et non 24 mois, comme pour tous les autres salariés) consécutifs ou non en qualité de salarié, quelle que soit la nature du contrat de travail au cours des 5 dernières années ;- dont 4 mois, consécutifs ou non, en CDD au cours des 12 derniers mois, et ce, y compris en contrat de professionnalisation ou d'apprentissage.
		if(1)
		{
			//$array=array();
			//$array['pri']="3";
			//$array['title']="CIF - CDD Jeune";
			//$array['step']="Retirer auprès de l'OPACIF un dossier de demande de prise en charge Vérifier les délais piour faire cette demande.<br/>Indiquer votre accord pour compléter, si besoin, le financement de la formation avec votre Compte Personnel de Formation.";
			//$array['descinfo']="Le Congé Individuel de Formation (CIF) permet à tout salarié en contrat ou ancien titulaire de contrat à durée déterminée (CDD) de suivre, à son initiative et à titre individuel, une formation, dans le but de :<br/>se reconvertir<br/>ou acquérir un niveau supérieur de qualification ou une 1ère qualification<br/>ou se perfectionner<br/>ou s'ouvrir plus largement à la culture et à la vie sociale.";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['cifcddjeune']=$array;
			if($age<26 && $situation_cdd12months)
				if($training_datebegin && $situation_cdddatedefin && Tools::calcDiffDate($training_datebegin,$situation_cdddatedefin)<=12)
					if(!in_array($training_formacode,array('15064','44591')))
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
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if($training_dureeenmois<=12 || $training_nbheurestotales<=1200)
										if($situation_cdd12months)
											if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
											{
												remuFORMCDD($var,'cifcddjeune',$droits);
											}
		}

		/* Ligne 11 */
		/* Caractéristiques formation: */
		//idem cif CDD + Formation d’une durée supérieure à 70h.
		//+ hors formations t à distance, en cours du soir le week-end,
		//+ hors formaCode 15064

		/* Caractéristiques DE: */
		//- avoir travaillé 1 600 heures en intérim au cours des 18 derniers mois, dont 600 heures dans la même entreprise de travail temporaire (celle qui signera votre autorisation d’absence). Demande d’autorisation d’absence pour cif-Interim en cours de mission ou dans un délai maximum de 3 mois après votre dernier jour de mission.
		//- La demande de « CIF reconversion » qui peut être effectuée par les travailleurs temporaires suite à un accident de travail ou de trajet ou à une maladie professionnelle les rendant inaptes à occuper un emploi correspondant à leur qualification doit être effectuée au plus tard dans les 6 mois suivant la visite de reprise, au lieu des 3 mois initialement prévus.
		if(1)
		{
			//$array=array();
			//$array['pri']="3";
			//$array['title']="CIF - Interim";
			//$array['step']="Contacter le Fonds d'Assurance Formation du Travail Temporaire (Faf-TT) dont vous dépendez. Votre agence Interim vous indiquera l'adresse du Faf-TT à contacter et vous aidera à effectuer la demande de CIF<br/>Plus d'infos pour retirer un dossier CIF, appelez le Faf-TT au 01 73 78 13 30 (du lundi au vendredi de 9h à 18h).";
			//$array['descinfo']="Le Congé Individuel de Formation (CIF) permet à tout salarié intérimaire ou faisant suite à un contrat de travail temporaire, à son initiative, de s'absenter de son poste de travail pour suivre une formation de son choix.<br/>Le Congé Individuel de Formation (CIF) est un droit ouvert à tout salarié intérimaire quelle que soit la taille de l'entreprise.<br/>Cette formation vous permet d'accéder à un niveau supérieur de qualification, de vous perfectionner dans votre domaine de compétence ou de changer d'orientation professionnelle";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['cifinterim']=$array;
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
				if(!$training_contratprofessionalisation && !$training_contratapprentissage)
					if($training_nbheurestotales>70)
						if(!$training_adistance && !$training_coursdusoir && !$training_coursweekend)
							if($situation_interim && $situation_interim1600h)
								if(!in_array($training_formacode,array('15064','44591')))
									if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
									{
										remuFORMINTERIM($var,'cifinterim',$droits);
									}
		}

		/* Ligne 12 */

		/* Caractéristiques formation: */
		/* "code financeur OPCA et durée < ou = 400h, hors intitulé POEI, hors formacode 15064
		/*  ou intitulé de la formation comprend ""préparation opérationnelle à l'emploi collective "" et/ou ""POEC"" + durée max < ou = 400h;"

		/* Caractéristiques DE: */
		// tout DE - hors créateur d'entreprise

		if(1)
		{
			//$array=array();
			//$array['pri']="1";
			//$array['title']="Préparation Opérationnelle à l'Emploi Collective";
			//$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par l'OPCA et Pole emploi";
			//$array['descinfo']="Dans le cadre de la Préparation opérationnelle à l'Emploi, une branche professionnelle identifie des besoins de formation dans les entreprises relevant de son secteur.<br/>L’OPCA met en place, en partenariat avec Pôle emploi, des actions de formation collectives pour former des demandeurs d’emploi en réponse aux compétences recherchées par les entreprises";
			//$array['info']="http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321";
			//$array['cost']="Formation totalement financée";
			//$display['poecollective']=$array;
			if($training_nbheurestotales<=400)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) || hasStrings(array('PREPARATION OPERATIONNELLE À L\'EMPLOI COLLECTIVE','POEC'),$ar['intitule']))
					if(!$situation_projetcreationentreprise)
						if(!in_array($training_formacode,array('15064','44591')))
							if(!hasStrings(array('POEI'),$ar['intitule']))
							{
								if($allocation_type=='are')
									remuAREF($var,'poecollective',$droits);
								else
									remuRFPE2($var,'poecollective',$droits);
							}
		}

		/* Ligne 13 */
		/* Caractéristiques formation: */
		//cf F° France entière tag CPF COPANEF (Tout public) ou CPF COPAREF DE
		//+ Code INSEE de la région (dans le cas où france-entiere=0) = code INSEE région du DE
		//hors tag contrat d'apprentissage hors Tag contrat de professionnalisation

		/* Caractéristiques DE: */
		//Tous Demandeurs d'emploi avec durée CPF < durée de formation
		// EN DOUBLON
		if(1)
		{
			//$array=array();
			//$array['pri']="5";
			//$array['title']="Compte Personnel de Formation (CPF)";
			//$array['step']="Contactez un conseiller emploi pour être accompagné dans la mobilisation de votre compte CPF .<br/>Activez votre compte CPF et inscrivez vos éventuelles heures de DIF, non utilisées au titre de votre dernière rupture de contrat de travail,";
			//$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle.<br/>Lorsqu’un demandeur d’emploi bénéficie d’heures sur son compte, il peut les mobiliser pour financer toute ou partie d'une formation qualifiante.";
			//$array['cost']="Formation totalement ou partiellement financée";
			if($situation_creditheurescpf)
				$display['cpf']['cost-complement']='pour '.($situation_creditheurescpf*9).' €';
			else
				$display['cpf']['cost-complement']='sur la base de 9&nbsp;€ multipliée par vos heures CPF acquises';
			if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
				if(!$training_contratprofessionalisation && !$training_contratapprentissage)
					if($situation_creditheurescpfinconnu || ($situation_creditheurescpfconnu && $situation_creditheurescpf<$training_nbheurestotales))
						if(!in_array($training_formacode,array('15064','44591'))) 
							if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
							{
								//if(!in_array($training_codecertifinfo,array('54664')))
								if($allocation_type=='are')
									remuAREF($var,'cpf',$droits);
								else
								{
									if(in_array($training_codecertifinfo,array('54664')))
										remuTEXT($var,'cpf',$droits);
									else
										remuTEXT($var,'cpf',$droits,"Si vous ne bénéficiez pas de l'AREF, vous toucherez une rémunération formation Pôle emploi (RFPE) si la formation est validée par Pôle emploi. Si votre projet n'est pas validé, vous ne toucherez pas de rémunération.");
								}
							}
		}

		/* Ligne 14 */
		/* Caractéristiques formation: */
		//cf F° France entière tag CPF COPANEF (Tout public) ou CPF COPAREF DE
		//+ Code INSEE de la région (dans le cas où &laquo;&nbsp;france-entiere&nbsp;&raquo;=0) = code INSEE région du DE
		//hors tag &laquo;&nbsp;contrat d'apprentissage&nbsp;&raquo; + hors Tag &laquo;&nbsp;contrat de professionnalisation&nbsp;&raquo;

		/* Caractéristiques DE: */
		//Tous Demandeurs d'emploi
		//-avec nb heures CPF = ou > durée de la formation
		if(1)
		{
			//$array=array();
			//$array['pri']="6";
			//$array['title']="Compte Personnel de Formation (CPF)";
			//$array['step']="Vous pouvez directement faire la demande de financement <a href=\"http://www.moncompteformation.gouv.fr/qui-etes-vous#qui-etes-vous\">ici</a> et prendre contact avec l'organisme de formation pour vous inscrire à la formation.";
			//$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle.<br/>Lorsqu’un demandeur d’emploi bénéficie d’heures sur son compte, il peut les mobiliser pour financer toute ou partie d'une formation qualifiante.";
			//$array['info']="Si le montant couvert par le CPF est insuffisant, il peut être complété par vous-même ou, sous conditions, par d'autres dispositifs de financement disponibles. http://www.moncompteformation.gouv.fr/#";
			//$array['cost']="Formation totalement ou partiellement financée sur la base de ";
			
				if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
					if(!hasKeywords('POEC',$ar['intitule']))
						if(!$training_contratprofessionalisation && !$training_contratapprentissage)
							if($situation_creditheurescpfconnu && $situation_creditheurescpf>=$training_nbheurestotales)
								if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
								{
									$array=$display['cpf'];
									arrayInsertAfterKey($droits,'cpf',$display,array('cpf_2'=>$array));
															
									if($situation_creditheurescpf)
										$display['cpf_2']['cost-complement']='pour '.($situation_creditheurescpf*9).' €';
									else
										$display['cpf_2']['cost-complement']='sur la base de 9&nbsp;€ multipliée par vos heures CPF acquises';

									if($allocation_type=='are')
										remuAREF($var,'cpf_2',$droits);
									else
									{
										if(in_array($training_codecertifinfo,array('54664')))
											remuTEXT($var,'cpf_2',$droits);
										else
											remuTEXT($var,'cpf_2',$droits,"Si vous ne bénéficiez pas de l'AREF, vous toucherez une rémunération formation Pôle emploi (RFPE) si la formation est validée par Pôle emploi. Si votre projet n'est pas validé, vous ne toucherez pas de rémunération.");
									}
								}
		}

		/* Ligne 15 */
		/* Caractéristiques formation: */
		//toutes
		/*
		"hors F°
		hors intulé POEC ""PREPARATION OPERATIONNELLE A L'EMPLOI"",'POEI') code financeur ""PE"" collectif, code financeur  ""'Region"" 
		hors code financeur OPCA si il n'y a aucun code financeur individuel associé (code 10 ""bénéficiaire de l'action, code 5 ""entreprise"", code 17 ""OPACIF"", code O ""autres financeurs"") 
		si code financeur Etat 
		si  code financeur : coll terr
		si code financeur ""Agefiph""
		si hors tag contrat pro et hors tag contrat d'apprentissage
			"
		*/

		/* Caractéristiques DE: */
		//personnes reconnues travailleur handicapé
		if(1)
		{
			//$array=array();
			//$array['pri']="6";
			//$array['title']="Financement individuel Agefiph";
			//$array['step']="Contactez un conseiller emploi pour connaitre les conditions de la mobilisation éventuelle d'une aide individuelle de l'Agefiph .";
			//$array['descinfo']="L'Agefiph propose une participation au financement du coût d’une formation individuelle offrant des perspectives réelles et sérieuses d'accès à l'emploi.";
			//$array['info']="<a href=\"https://www.agefiph.fr/\" target=\"_blank\">www.agefiph.fr</a>";
			//$array['cost']="Formation totalement ou partiellement financée";
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
					if(!$training_contratprofessionalisation && !$training_contratapprentissage)
						if($situation_th)
							if(!hasStrings(array('POEC'),$ar['intitule']))
							{
								//$display['agefiph']=$array;
								if($allocation_type=='are')
									remuAREF($var,'agefiph',$droits);
								else
									remuFORM2($var,'agefiph',$droits);
							}
		}

		/* Ligne 16 */
		/* Caractéristiques formation: */
		//toutes

		/* Caractéristiques DE: */
		//DE moins de 26 ans en ARE (ou alloca° ex-employeur secteur public ou non indemnisé)
		if(1)
		{
			//$array=array();
			//$array['pri']="6";
			//$array['title']="Le Fonds d'Aide aux Jeunes (FAJ)";
			//$array['step']="Contactez la Mission Locale pour connaitre les conditions de mobilisation de cette aide individuelle.";
			//$array['descinfo']="Le Fonds d'Aide aux Jeunes (FAJ) est un dispositif départemental de dernier recours destiné aux jeunes adultes en grande difficulté sociale. Il vise à favoriser leur insertion sociale et professionnelle. Dans ce cas, il peut être mobilisé pour participer au financement d'une formation.";
			//$array['info']="Accordée ponctuellement, l’aide financière peut être renouvelée sous certaines conditions. La durée des actions d’accompagnement varie selon les départements.";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['faj']=$array;
			if($age<26)
				if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces))
						if(!in_array($training_formacode,array('15064','44591')))
						{
							if($allocation_type=='are')
								remuAREF($var,'faj',$droits);
							else
								remuFORM2($var,'faj',$droits);
						}
		}

		/* Ligne 17 */
		/* Caractéristiques formation: */
		//tag éligibilité CPF Copanef; Coparef DE Région, et branche du salarié hors tag contrat d'apprentissage
		//hors Tag contrat de professionnalisation

		/* Caractéristiques DE: */
		//DE licenciés économiques adhérents CSP
		if(1) //A finir
		{
			$array=array();
			$array['pri']="6";
			$array['title']="Le Financement individuel OPCA";
			$array['step']="Pour effectuer cette formation, présentez le devis à la structure qui vous accompagne dans le cadre de votre CSP";
			$array['descinfo']="Les Organismes Paritaires Collecteurs Agréés (OPCA) peuvent, sous conditions, intervenir dans le financement total ou partiel (en complément d'autres financements) de certaines formations pour les licenciés économiques adhérents CSP";
			$array['cost']="Formation totalement ou partiellement financée";
			$display['opca']=$array;
			if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
				if(!$training_contratprofessionalisation && !$training_contratapprentissage)
					//if(branche_salarié) utiliser le travail de Loic pour récupérer la branche du salarié
					if($situation_liccsp)
						if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
							if($allocation_type=='are')
								remuAREF($var,'opca',$droits);
							else
								remuFORM2($var,'opca',$droits);
		}

		/* Ligne 18 */
		/* Caractéristiques formation: */
		//F° France entière
		//pas d'AIF :
		//pr Intitulé comportant AFC;
		//si code financeur PE; collectif
		//si code financeur 'Region;
		//si code financeur OPCA;
		//si code financeur Etat
		//si code financeur : coll terr
		//pas d'AIF si : :
		//Nombre heures en entreprise supérieur à 30% du nbre heures total de la formation
		//hors tag contrat d'apprentissage hors Tag contrat de professionnalisation
		//hors tag F° n° certifinfo 84385 stage de préparation à l'installation préalable

		/* Caractéristiques DE: */
		//Tous DE
		if(1)
		{
			//$array=array();
			//$array['pri']="7";
			//$array['title']="Aide Individuelle à la formation";
			//$array['step']="Contactez un conseiller Pôle emploi pour connaitre les conditions de la mobilisation éventuelle d'une aide individuelle de Pole emploi dans votre région.<br/>Votre projet de formation et son financement doivent être présentés bien en amont du début de la formation. (au plus tard 15 jours avant)";
			//$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre.<br/>L’AIF est réservée à des projets de formation dont la pertinence est validée par votre conseiller référent Pôle emploi.<br/>Cette pertinence est appréciée au regard de votre projet professionnel et du marché du travail.<br/>Les formations financées doivent permettre un retour rapide à l'emploi.";
			//$array['info']="L'AIF permet une prise en charge totale des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen …).<br/>L'AIF peut compléter des co-financements mais jamais une prise en charge personnelle. http://www.pole-emploi.fr/candidat/l-aide-individuelle-a-la-formation-aif--@/article.jspz?id=60857";
			//$array['cost']="Formation totalement financée";
			//$display['aif']=$array;
			if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
				if(!hasKeywords(array('AFC'),$ar['intitule']))
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces))
						if(!($training_nbheuresentreprise>(($training_nbheurestotales*30)/100)))
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!in_array($training_formacode,array('15081','13250','15064','44591')))
									if(!in_array($training_codecertifinfo,array(84385,54664)))
									{
										if($allocation_type=='are')
											remuAREF($var,'aif',$droits);
										else
											remuRFPE2($var,'aif',$droits);
									}
		}


		/* Ligne 19 */
		/* AIF Bilan de Compétences */
		/* Caractéristiques formation: */
		//F° France entière
		// Formacode : 44067

		/* Caractéristiques DE: */
		//Tous DE
		if(1)
		{
			//$array=array();
			//$array['pri']="7";
			//$array['title']="Aide Individuelle à la formation Bilan de compétences";
			//$array['step']="Contacter votre  conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi). <br />Votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.";
			//$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels  les achats d’action de formation collectives  ne peuvent répondre. <br />L’AIF blian de coméptences finance des préparations de bilan ou d'évaluation des acquis professionnels";
			//$array['info']="L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br />Il partagera avec vous la pertinence d'établir un bilan de compétences";
			//$array['cost']="Formation totalement financée";
			//$display['aifbilancompetence']=$array;
			if(in_array($training_formacode,array('15081')))
			{
				if(!hasStrings(array('POEI'),$ar['intitule']))
				{
					if($allocation_type=='are')
						remuAREF($var,'aifbilancompetence',$droits);
					else
						remuTEXT($var,'aifbilancompetence',$droits);
				}
			}
		}

		/* Ligne 20 */
		/* Caractéristiques formation: */
		//tag F° n° certifinfo 84385 stage de préparation à l'installation préalable à l'inscription au répertoire des métiers
		//au titre de la création ou reprise d'entreprise artisanale

		/* Caractéristiques DE: */
		//tout DE
		if(1)
		{
			//$array=array();
			//$array['pri']="7";
			//$array['title']="Aide Individuelle à la formation Artisan";
			//$array['step']="Assurez vous auprès de la Chambre des métiers et de l'Artisanat (<a href=\"http://www.artisanat.fr/portals/0/annuaire/annuaire.html\">www.artisanat.fr</a>) que la formation est bien obligatoire et préalable à l'installation comme artisan.<br/>Le financement par le biais de cette Aide Inidividuelle à la Formation - Artisan (AIF Artisan) n'est possible que si le devis présenté à Pôle emploi du Stage préparatoire à l'installation d'une entreprise artisanale correspond au montant exact de l'aide (192,71 € pour 2017).";
			//$array['descinfo']="L'aide individuelle à la formation &laquo;&nbsp;artisan&nbsp;&raquo; permet de couvrir le coût du stage de préparation à l'installation (le SPI)<br/>Le SPI est obligatoire pour toute personne sollicitant une immatriculation auprès d'une Chambre de métiers et de l'artisanat dans le cadre d'un projet de création ou de reprise d'entreprise.<br/>II a pour objectif de favoriser la pérennité des entreprise en création/reprise, en accompagnant leurs créateurs afin de :<br/>Préparer le projet de création/reprise d’entreprise artisanale,<br/>Appréhender de façon concrète les missions et responsabilités du dirigeant d’entreprise artisanale";
			//$array['info']="L'AIF permet une prise en charge totale des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen …).<br/>L'AIF peut compléter des co-financements mais jamais une prise en charge personnelle. <a href=\"http://www.pole-emploi.fr/candidat/l-aide-individuelle-a-la-formation-aif--@/article.jspz?id=60857\">www.pole-emploi.fr</a>";
			//$array['cost']="Formation financée en totalité pour 195,10 €";
			//$display['aifartisan']=$array;
			if(in_array($training_codecertifinfo,array(84385)))
			{
				if(!hasStrings(array('POEI'),$ar['intitule']))
					if($allocation_type=='are')
						remuAREF($var,'aifartisan',$droits);
					else
						remuRFPE2($var,'aifartisan',$droits);
			}
		}

		/* Ligne 21 */
		/* Caractéristiques formation: */
		//Balise <code-financeur> Collectivité territoriale - Conseil régional
		//+Code INSEE de la région du lieu de formation < ou = niveau IV
		//hors tag contrat d'apprentissage

		/* Caractéristiques DE: */
		//DE
		// EN DOUBLON
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action de formation collective financée par la Région";
			//$array['step']="Vérifiez auprès de votre conseiller et/ou de l'organisme de formation<br/>que vous remplissez les conditions pour effectuer cette formation.";
			//$array['descinfo']="Cette action de formation est financée par votre Région - gratuite pour les demandeurs d'emploi";
			//$array['cost']="Formation totalement financée";
			//$display['actioncollectiveregion']=$array;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				//if($domicileRegionPath==$trainingRegionPath)
				if(!$training_contratapprentissage)
					if($training_niveausortie<=CODENIVEAUSCOLAIRE_BAC)
					{
						if(!hasStrings(array('POEI'),$ar['intitule']))
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion',$droits);
							else
								remuRPS($var,'actioncollectiveregion',$droits);
					}
		}

		/* Ligne 22 */
		/* Caractéristiques formation: */
		//Balise <code-financeur> Collectivité territoriale - Conseil régional
		//+Code INSEE de la région du lieu de formation
		//> niveau IV
		//hors tag contrat d'apprentissage hors Tag contrat de professionnalisation

		/* Caractéristiques DE: */
		//DE
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action de formation collective financée par la Région";
			//$array['step']="Vérifiez auprès de votre conseiller et/ou de l'organisme de formation<br/>que vous remplissez les conditions pour effectuer cette formation.";
			//$array['descinfo']="Cette action de formation est financée, totalement ou partiellement, par votre Région<br/>Vérifiez les critères d'admissibilité auprès de votre conseiller ou de l'organisme de formation";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['actioncollectiveregion']=$array;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				//if($domicileRegionPath==$trainingRegionPath)
					if(!$training_contratapprentissage && !$training_contratprofessionalisation)
						if($training_niveausortie>CODENIVEAUSCOLAIRE_BAC)
							if(!hasStrings(array('POEI'),$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_national'=>$display));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_national',$droits);
								else
									remuRPS($var,'actioncollectiveregion_national',$droits);
							}
		}

		/* Ligne 23 */
		/* Caractéristiques formation: */
		//Balise <code-financeur> Pôle emploi

		/* Caractéristiques DE: */
		//tout DE
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action de formation collective financée par Pôle emploi";
			//$array['step']="Contactez votre conseiller emploi pour être sélectionné sur une des places financées par Pôle emploi";
			//$array['descinfo']="Il s'agit d'une formation collective, délivrée en centre de formation pouvant comprendre une période de stage en entreprise, gratuite et réservée aux demandeurs d'emploi";
			//$array['cost']="Formation totalement financée";
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AUTRE,$nbPlaces) &&
					!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ENTREPRISE,$nbPlaces) &&
					!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_BENEFICIAIRE_DE_L_ACTION,$nbPlaces) &&
					!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPACIF,$nbPlaces))
						if(!hasStrings(array("POEC","PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI'),$ar['intitule']))
						{
							//$display['poleemploicollectif']=$array;
							if($allocation_type=='are')
								remuAREF($var,'poleemploicollectif',$droits);
							else
								remuRFPE2($var,'poleemploicollectif',$droits);
						}
		}

		/* Ligne 24 */
		/* Caractéristiques formation: */
		//Balise <code-financeur> AGEFIPH

		/* Caractéristiques DE: */
		//personnes reconnues travailleur handicapé
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action de formation collective financée par l'Agefiph";
			//$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par l'Agefiph";
			//$array['descinfo']="Cette action de formation est financée par l'Agefiph. Cet organisme finance des actions de formation pour des personnes reconnues travailleurs handicapées pour accéder ou conserver leur emploi";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$array['info']="https://www.agefiph.fr/";
			//$display['agefiphcollectif']=$array;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces))
				if($situation_th)
				{
					if(!hasStrings(array('POEI'),$ar['intitule']))
						if($allocation_type=='are')
							remuAREF($var,'agefiphcollectif',$droits);
						else
							remuRFPE2($var,'agefiphcollectif',$droits);
				}
		}

		/* Ligne 25 */
		/* Caractéristiques formation: */
		//Balise <code-financeur> Collectivité territoriale - Conseil général
		//+Code INSEE du dpt du lieu de formation = code INSEE dpt du DE

		/* Caractéristiques DE: */
		//- Personnes bénéficiaires du RSA
		// EN DOUBLON
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action collective financée Conseil Départemental";
			//$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par le Conseil Départemental";
			//$array['descinfo']="Cette action de formation est financée par le Conseil Départemental pour des bénéficiaires du RSA.";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['conseildepartementalcollectif']=$array;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
				if($domicileDepartementPath==$trainingDepartementPath)
					if(!in_array($training_formacode,array('44030')))
						if($allocation_type=='rsa')
							remuRPS($var,'conseildepartementalcollectif',$droits);
						//else sinon rien
							
		}

		/* Ligne 26 */
		/* Caractéristiques formation: */
		//Balise <code-financeur> Etat

		/* Caractéristiques DE: */
		//tout DE
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action collective financée par l'Etat";
			//$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par l'Etat";
			//$array['descinfo']="Cette action de formation est financée par l'Etat qui finance des actions de formation pour permettre à des demandeurs d'emploi d'accéder à un emploi";
			//$array['info']='Même si Pôle emploi ne finance pas cette formation, votre conseiller peut valider votre projet de formation. Si vous êtes bénéficiaire d\'une allocation de recherche d\'emploi elle sera maintenue';
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['etat']=$array;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) ||
			   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) ||
			   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces))
			{
				if($allocation_type=='are')
					remuAREF($var,'etat',$droits);
				else
					remuRPS($var,'etat',$droits);
			}
		}

		/* Ligne 27 */
		/* Caractéristiques formation: */
		//hors tag contrat d'apprentissage
		//hors Tag contrat de professionnalisation

		/* Caractéristiques DE: */
		//tous DE
		if(1)
		{
			//$array=array();
			//$array['pri']="8";
			//$array['title']="Autres financement possibles : autofinancement / CAF / MSA / caisse maritime / caisses de retraites / caisse congés payé btp / Associations / Mairies et CCAS / Fondations / etc.";
			//$array['step']="Contactez l'organisme de formation pour obtenir un devis de formation.<br />Vous pouvez solliciter un éventuel financement individuel auprès d’organismes suivants:<br />Caisses de retraites.<br />Centre communal d'action sociale (CCAS)<br />Collectivités locales (Mairie,  Communautés de communes...)<br />CAF<br />L'organisme de formation peut aussi vous conseiller.";
			//$array['descinfo']="Nous n'avons pas identifié de dispositif de financement mobilisable pour ce type de formation. Il vous faudra sans doute la financer par vous-même.<br />Certains organismes peuvent, sous certaines conditions, participer à la prise en charge de frais liés à une formation. N’hésitez pas à les solliciter. ";
			//$array['cost']="Financement partiel possible";
			//$display['autres']=$array;
			//if(!$training_contratapprentissage && !$training_contratprofessionalisation)
			if(!trim($training_codefinanceur) || 
			   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) ||
			   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AUTRE,$nbPlaces) ||
			   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_BENEFICIAIRE_DE_L_ACTION,$nbPlaces) ||
			   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ENTREPRISE,$nbPlaces))
			{
				if(!$training_contratapprentissage && !$training_contratprofessionalisation)
					if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
						if($allocation_type=='are')
							remuAREF($var,'autres',$droits);
						else
							remuTEXT($var,'autres',$droits);
			}
		}

		/* Ligne 28 */
		/* Caractéristiques formation: */
		//F) France entière et  certifinfo 54664

		/* Caractéristiques DE: */
		//F) France entière et  certifinfo 54664
		if(1)
		{
			//$array=array();
			//$array['pri']="8";
			//$array['title']="Prise en charge Permis de conduire B";
			//$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation.";
			//$array['descinfo']="Vous pouvez utiliser votre CPF pour financer tout ou partie de votre permis de conduire catégorie B.<br/>Pôle emploi peut éventuellement vous apporter un complément de financement.<br/>Votre auto-école doit impérativement vous présenter à l'examen de conduite au plus tard six mois après votre inscription.";
			//$array['cost']="Formation partiellement ou totalement financée ";
			//$display['finindividuelpermisb']=$array;
			if($situation_inscrit)
				if(in_array($training_codecertifinfo,array(54664)))
				{
					if($allocation_type=='are')
						remuAREF($var,'finindividuelpermisb',$droits);
					else
						remuTEXT($var,'finindividuelpermisb',$droits);
				}
		}

		/* Ligne 29 */
		/* Caractéristiques formation: */
		//"Balise <code-financeur> Collectivité territoriale - Conseil général
		//	 +Code INSEE du dpt du lieu de formation = code INSEE dpt du DE
		//	 et  formacode 44030 - ASSISTANCE MATERNELLE"

		/* Caractéristiques DE: */
		//"DE habitant département du lieu de formation
		if(1)
		{
			//$array=array();
			//$array['pri']="4";
			//$array['title']="Action collective financée Conseil Départemental";
			//$array['step']="Pour effectuer cette formation, contactez l'organisme de formation ou votre conseiller référent emploipour être sélectionné sur une des places financées par le Conseil Départemental";
			//$array['descinfo']="Cette action de formation qui vous permet de devenir assistante maternelle est financée par le Conseil Départemental.";
			//$array['cost']="Formation totalement ou partiellement financée";
			//$display['conseildepartementalcollectif']=$array;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
			{
				if($domicileDepartementPath==$trainingDepartementPath)
					if(in_array($training_formacode,array('44030')))
						remuRPS($var,'conseildepartementalcollectif',$droits);
			}
						//else sinon rien
		}

		/* Ligne 30 */
		//"F° France entière  + formacode 15064 ou 44591
		//pas d'AIF : 
		//si code financeur ""PE"" collectif
		//si code financeur  ""'Region"" 
		//si code financeur ""OPCA""
		//si code financeur Etat 
		//si code financeur : coll terr
		
		if(1)
		{
			if(!hasStrings(array('POEC',"PREPARATION OPERATIONNELLE A L'EMPLOI",'POEI','POE'),$ar['intitule']))
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
					if(in_array($training_formacode,array('15064','44591')))
					{
						if($allocation_type=='are')
							remuAREF($var,'aifvaepartielle',$droits);
						else
							remuTEXT($var,'aifvaepartielle',$droits,"Formation totalement financée, si validée par Pôle emploi");
					}
		}
	}
?>
