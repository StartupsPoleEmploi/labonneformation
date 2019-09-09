<?php
	/* Règle New Normandie ************************************************************************************************/
	function reglesNormandie($quark,$var,&$droits,&$display)
	{
		extract($var);

		/* Ligne 2 (Normandie) */
		/* Caractéristiques DE: */
		//DE TH + Normandie
		//hors DE créateurs d'entreprise inscrits en catégorie5 CEN

		/* Caractéristiques formation: */
		//F° france entiere + COPAREF -COPANEF
		//durée totale F° : inf ou égal à 400heures
		//durée en entreprise = 0h ou < un 1/3 de la durée totale
		//hors formacodes :
		//'43448',43436','31815','43441','44041','31815','43454','44054','43409','44041','31802','31854','31805','31827','31833','31815','43454','44054','43409','31734','32050','43438','43438','43438','42003','42030','42050','42060','46061','43445','43425','14449','42020','42093','15009','15015','49616','23710','55676','83419','96173'
		//hors code Financeur Région, coll terr (tout type), Etat (tout type), PE , OPCA,
		//hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"
		//'43436','31815','43441','44041','31815','43454','44054','43409','44041','31802','31854','31805','31827','31833','31815','43454','44054','43409','31734','32050','43438','43438','43438','42003','42030','42050','42060','46061','43445','43425','14449','42020','42093','15009','15015','49616','23710','55676','83419','96173'

		/* Rémunération: */
		//AREF + RFPE (ou droit d'option TH)

		if($situation_inscrit)
			if(isInRegionNormandie($domicilePath))
			{
				unset($droits['aif']);
				$array=array();
				$array['title']="Aide individuelle à la Formation (AIF)";
				$array['step']="Effectuez les démarches de validation de votre projet de formation et  assurez-vous qu'elle corresponde aux emplois offerts sur votre secteur géographique. Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi). Vous lui présenterez 2 devis minimum de 2 organismes différents. Votre projet de formation et son financement doivent être présentés au plus tard 3 semaines avant le début de la formation, pour pouvoir être validé au préalable par votre conseiller emploi.";
				$array['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels  les achats d'action de formation collectives  ne peuvent répondre. L'AIF est réservée à des projets de formation dont la pertinence et la faisabilité sont partagées avecvotre conseiller Pôle emploi, par rapport au marché du travail. Il vérifiera aussi si les conditions du financement sont réunies. Si vous possédez des heures CPF, un complément de financement pourra être possible grâce à l'AIF.";
				$array['info']="L'AIF est réservée à des projets de formation dont la pertinence et la faisabilité sont partagées avec le conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiiera aussi si les conditions du financement sont réunies.";
				$array['cost']="Formation totalement ou partiellement financée<br/>(avec mobilisation possible de vos heures CPF)";
				if(!$situation_th)
					if($training_nbheurestotales)
						if(!$training_nbheuresentreprise || ($training_nbheurestotales && $training_nbheuresentreprise<($training_nbheurestotales/3)))
							if(!in_array($training_formacode,array(43448,43436,31815,43441,44041,31815,43454,44054,43409,44041,31802,31854,31805,31827,31833,31815,43454,44054,43409,31734,32050,43438,43438,43438,42003,42030,42050,42060,46061,43445,43425,14449,42020,42093)))
								if($training_racineformacode!=150 || in_array($training_formacode,array(15009,15015)))
									if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
										if(!$training_contratapprentissage && !$training_contratprofessionalisation)
											if(!in_array($training_codecertifinfo,array(49616,23710,55676,83419,96173)))
												if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
												   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces) &&
												   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
												   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
												   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
												   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
												   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
												{
													arrayInsertAfterKey($droits,'aif',$display,array('aif_normandie1'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'aif_normandie1',$droits);
													else
														remuRFPE2($var,'aif_normandie1',$droits);
												}
			}

		/* Ligne 3 (Normandie) */
		/* Caractéristiques DE: */
		//DE TH + Normandie
		//hors DE créateurs d'entreprise inscrits en catégorie5 CEN

		/* Caractéristiques formation: */
		//F° france entiere + COPAREF -COPANEF
		//durée totale F° : inf ou égal à 400heures
		//durée en entreprise = 0h ou < un 1/3 de la durée totale
		//hors formacodes :
		//'43448',43436','31815','43441','44041','31815','43454','44054','43409','44041','31802','31854','31805','31827','31833','31815','43454','44054','43409','31734','32050','43438','43438','43438','42003','42030','42050','42060','46061','43445','43425','14449','42020','42093','15009','15015','49616','23710','55676','83419','96173'
		//hors code Financeur Région, coll terr (tout type), Etat (tout type), PE , OPCA,
		//hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"
		//'43436','31815','43441','44041','31815','43454','44054','43409','44041','31802','31854','31805','31827','31833','31815','43454','44054','43409','31734','32050','43438','43438','43438','42003','42030','42050','42060','46061','43445','43425','14449','42020','42093','15009','15015','49616','23710','55676','83419','96173'

		/* Rémunération: */
		//AREF + RFPE (ou droit d'option TH)

		/* Ligne 3 */
		if(isInRegionBasseNormandie($domicilePath) || isInRegionHauteNormandie($domicilePath))
		{
			$array=array();
			$array ['title']="Aide individuelle à la Formation financée par Pôle emploi et l'Agefiph";
			$array['step']="Effectuez les démarches de validation de votre projet de formation et  assurez-vous qu'elle corresponde aux emplois offerts sur votre secteur géographique. Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi). Vous lui présenterez 2 devis minimum de 2 organismes différents. Votre projet de formation et son financement doivent être présentés au plus tard 3 semaines avant le début de la formation, pour pouvoir être validé au préalable par votre conseiller emploi.";
			$array['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi et de l'Agefiph qui vise à financer certains besoins individuels de formation auxquels  les achats d'action de formation collectives  ne peuvent répondre. L'AIF est réservée à des projets de formation dont la pertinence et la faisabilité sont partagées avecvotre conseiller Pôle emploi, par rapport au marché du travail. Il vérifiera aussi si les conditions du financement sont réunies. Si vous possédez des heures CPF, un complément de financement pourra être possible grâce à l'AIF.";
			$array['info']="L'AIF est réservée à des projets de formation dont la pertinence et la faisabilité sont partagées avec le conseiller référent Pôle emploi ou votre conseiller Cap Emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiiera aussi si les conditions du financement sont réunies.";
			$array['cost']="Formation totalement ou partiellement financée<br/>(avec mobilisation possible de vos heures CPF)";
			if($situation_th)
				if($training_nbheurestotales)
					if(!$training_nbheuresentreprise || ($training_nbheurestotales && $training_nbheuresentreprise<($training_nbheurestotales/3)))
						if(!in_array($training_formacode,array(43448,43436,31815,43441,44041,31815,43454,44054,43409,44041,31802,31854,31805,31827,31833,31815,43454,44054,43409,31734,32050,43438,43438,43438,42003,42030,42050,42060,46061,43445,43425,14449,42020,42093)))
							if($training_racineformacode!=150 || in_array($training_formacode,array(15009,15015)))
								if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
									if(!$training_contratapprentissage && !$training_contratprofessionalisation)
										if(!in_array($training_codecertifinfo,array(49616,23710,55676,83419,96173)))
											if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
											   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
											   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
											   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
											   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
											   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
											{
												arrayInsertAfterKey($droits,'agefiph',$display,array('agefiph_normandie1'=>$array));
												if($allocation_type=='are')
													remuAREF($var,'agefiph_normandie1',$droits);
												else
													remuRFPE2($var,'agefiph_normandie1',$droits);
											}
		}

		/* Ligne 4 (Normandie) */
		/* Caractéristiques DE: */
		//idem nat

		/* Caractéristiques formation: */
		//formacode 15081 hors code financeur Région, Etat Régin, colk terr, coll terr autres ... (idem nat)
		//'15081'

		/* Rémunération: */
		//idem nat

		if(1)
		{
			$array=$display['aifbilancompetence'];
			$array['cost']="35€/h sur une durée maximale de 24 h";
			if(isInRegionNormandie($domicilePath))
			{
				arrayInsertAfterKey($droits,'aifbilancompetence',$display,array('aifbilancompetence_normandie1'=>$array));
			}
		}

		/* Ligne 5 (Normandie) */
		/* Caractéristiques DE: */
		//Inscrits comme DE en Normandie,
		//Sortis de formation initiale depuis au moins 12 9 mois
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//F° avec code financeur Région et hors formacode sanitaires et social:
		//Infirmier 43 448
		//Aide soignant 43436
		//Ambulancier : 31815
		//Aux de puériculture 43441
		//( CAP Petite enfance : 44041
		//Auxiliaire Ambulancier 31815
		//Santé secteur sanitaire 43454
		//Action sociale 44054
		//Préparation concours para médical
		//(secteur sanitaire et social) 43409
		//Hors intitulé 'Une formation un emploi"
		//Hors intitulé 'Programme de formation de base'
		//'43436','31815','43441','44041','31815','43454','44054','43409'

		/* Rémunération: */
		//AREF ou RSP (noter 'sauf pour les Formations du Programme de formation générale')

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme de formations collectives financées par le Conseil Régional de Normandie";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)<br/>Effectuez les démarches de validation de votre projet de formation et assurez-vous qu'elle corresponde aux emplois offerts sur votre secteur géographique.<br/>Vous pouvez aussi contacter directement l'organisme de formation.";
			$array['descinfo']="La région Normandie propose des formations pour les jeunes et adultes demandeurs d’emploi et accompagne ceux qui souhaitent obtenir un diplôme, améliorer leurs compétences, changer de métier ou concrétiser leur projet d’entreprise.<br/>Ces actions permettent aux demandeurs d'emploi d’obtenir une certification professionnelle pour optimiser leur accès à l’emploi.<br/>Les formations proposées sont de tous niveaux (du Titre Professionnel de niveau V ou du CAP au diplôme d’ingénieur) et dans tous les domaines d’activité.<br/>Ce dispositif s'adresse en priorité aux formations visant à l'accés à la premiere qualification, la reconversion des beneficiaires de l'obligation d'emploi,<br/>l'élévation du niveau de formation Elle ne peut bénéficier aux demadeurs d'emploi ayant déja suivi une formation qualifiante au cours des 12 derniers mois, pour laquelle un financement institutionnel (Région,Pole emploi, Agefiph, Fongecif ,,) avait été accordé.<br/>=LIEN_HYPERTEXTE(&laquo;&nbsp;<a href=\"https://www.normandie.fr/programmes-de-formation&nbsp;&raquo;,&laquo;&nbsp;https://www\" target=\"_blank\">www.normandie.fr</a>.normandie.fr/programmes-de-formation&nbsp;&raquo;)";
			$array['info']="";
			$array['cost']="Formation totalement financée par le Conseil régional de Normandie";
			if($situation_inscrit)
				if(isInRegionNormandie($domicilePath) && isInRegionNormandie($training_locationpath))
					if(!$situation_liccsp)
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(!in_array($training_formacode,array('43448','43436','31815','43441','44041','31815','43454','44054','43409')))
								if(!hasStrings(array('UNE FORMATION UN EMPLOI','PROGRAMME DE FORMATION DE BASE'),$ar['intitule']))
								{
									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_normandie1'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_normandie1',$droits);
									else
									{
										remuRPS($var,'actioncollectiveregion_normandie1',$droits);
										$display['actioncollectiveregion_normandie1']['remu'].="<br/>* sauf pour les Formations du Programme de formation générale";
									}
								}
		}

		/* Ligne 6 (Normandie) */
		/* Caractéristiques DE: */
		//DE hors
		//DE créateurs d'entreprise inscrits en catégorie5 CEN
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//F° avec code Financeur Région et formacodes suivants :
		//Infirmier 43448
		//Aide soignant 43436
		//Ambulancier : 31815
		//Aux de puériculture 43441
		//'43436','31815','43441'

		/* Rémunération: */
		//Aref , ASSF ou demande de bourses selon critères à effectuer auprès du CROUS pour le Conseil régionale (<a href=\"http://www.crous-caen.fr/bourses/paramedicales/)\" target=\"_blank\">www.crous-caen.fr</a>

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme sanitaire et social du Conseil régional";
			$array['step']="Contactez tout d'abord l'organisme de formation pour connaitre les modalités de sélection.";
			$array['descinfo']="La Région prend en charge la presque intégralité du coût pédagogique des formations sanitaires et sociales  pour les élèves en poursuite de scolarité. Des conditions particulières s’appliquent aux demandeurs d’emploi et aux salariés.";
			$array['info']="<a href=\"http://formations-sante.normandie.fr/\" target=\"_blank\">formations-sante.normandie.fr</a>";
			$array['cost']="Formation totalement ou partiellement financée par le Conseil régional de Normandie";
			if($situation_inscrit)
				if(!$situation_projetcreationentreprise)
					if(!$situation_liccsp)
						if(isInRegionNormandie($domicilePath) && isInRegionNormandie($training_locationpath))
							if(in_array($training_formacode,array('43448','43436','31815','43441')))
								if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
								{
									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_normandie2'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_normandie2',$droits);
									elseif($allocation_type=='ass')
										remuFORM2($var,'actioncollectiveregion_normandie2',$droits);
									else
										remuTEXT($var,'actioncollectiveregion_normandie2',$droits,'Possibilité de demander une bourse.');
								}
		}

		/* Ligne 7 (Normandie) */
		/* Caractéristiques DE: */
		//DE france entiere inscrits à Pôle-emploi.
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//Code financeur Région + intitulé 'Une formation un emploi"

		/* Rémunération: */
		//AREF ou Remuneration RSP

		if(1)
		{
			$array=array();
			$array['title']="1 Formation 1 emploi - collectif";
			$array['step']="Vous contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)<br/>Effectuez les démarches de validation de votre projet de formation et d'emploi et assurez-vous qu'elle corresponde aux emplois offerts sur votre secteur géographique.<br/>Vous pouvez aussi contacter directement l'organisme de formation.";
			$array['descinfo']="La formation professionnelle répond à une double ambition : participer à l’évolution et la montée en compétence des Normands et contribuer au développement des entreprises et des associations.<br/>Dans cet esprit, la Région accompagne les employeurs normands dans la qualification de leurs futurs salariés,<br/>Le dispositif &laquo;&nbsp;Une formation un emploi&nbsp;&raquo; du Conseil Régional permet de financer aux entreprises qui recrutent un demandeur d'emploi pour un contrat d'au moins 6 mois, une formation de plus de 400 heures .";
			$array['info']="<a href=\"https://www.normandie.fr/recruter-et-former\" target=\"_blank\">www.normandie.fr</a>";
			$array['cost']="Formation totalement financée par le Conseil régional de Normandie";
			if($situation_inscrit)
				if(!$situation_liccsp)
					if(isInRegionNormandie($training_locationpath))
						if(hasStrings(array('UNE FORMATION UN EMPLOI'),$ar['intitule']))
							if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_normandie3'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_normandie3',$droits);
								else
									remuRPS($var,'actioncollectiveregion_normandie3',$droits);
							}
		}

		/* Ligne 8 (Normandie) */
		/* Caractéristiques DE: */
		//DE France entiere inscrits à Pôle-emploi.
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//F° hors code financeur Région, coll terr; coll terr autre, PE ou OPCA , hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"
		//+ de niveau V, IV et III,
		//+ durée > 400h et < à 1400 h
		//hors formacode
		//hors formacode des domaines 430 ; 434; 440 (sauf 44067); 150, 154 (sauf 15447, 15448;15457;15458)
		//Auxiliaire Ambulancier 31815
		//hors formacode 13030; 13250, 31802
		//50245;50445;50145;50349;50249
		//hors ROME G1204 et L 1401
		//hors tag "contrat d'apprentissage" et hors Tag "contrat de professionnalisation"
		//hors intitulé comprenant BTS, Bachelor, Capacité, DAEU, DEUST, Diplôme d'accès aux études universitaires, ingénieur, Licence, Master, Mastère
		//'44067','15447','15448','15457','15458','31815','13030','13250','31802','50245','50445','50145','50349','50249'

		/* Rémunération: */
		//AREF ou Remuneration RSP

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="1 Formation 1 emploi";
			$array['step']="Le financement de cette formation est lié à une embauche d'au moins 6 mois par une entreprise basée en Normandie.<br/>L'entreprise qui souhaite vous former avant de vous recruter au moins 6 mois doit déposer une demande auprès du Conseil régional au moins 1 mois avant le début de la formation.<br/>(dossier à completer par l'entreprise sur cette page : <a href=\"http://bn-aides.normandie.fr/index\" target=\"_blank\">bn-aides.normandie.fr</a>.php/5-formation-tout-au-long-de-la-vie-developpement-economique-recherche-et-innovation-tourisme/20-partenariat-avec-les-employeurs/14-q-une-formation-un-emploi-q)";
			$array['descinfo']="La formation professionnelle répond à une double ambition : participer à l’évolution et la montée en compétence des Normands et contribuer au développement des entreprises et des associations. Dans cet esprit, la Région accompagne les employeurs normands dans la qualification de leurs futurs salariés,<br/>Le dispositif &laquo;&nbsp;Une formation un emploi&nbsp;&raquo; du Conseil Régional permet de financer aux entreprises et aux demandeurs d'emploi une formation de plus de 400 heures avant un recrutement d'au moins 6 mois.<br/>Attention : pas de financement de formations sanitaires et social. ATTENTION : la formation au titre professionnel enseignant de la conduite et la sécurité routière ne pourra être financée que par ce dispositif !";
			$array['info']="<a href=\"https://www.normandie.fr/recruter-et-former\" target=\"_blank\">www.normandie.fr</a>";
			$array['cost']="Formation totalement financée par le Conseil régional de Normandie";
			if($situation_inscrit)
				if(!$situation_liccsp)
					if(isInRegionNormandie($training_locationpath))
						if(in_array($training_niveausortie,array(CODENIVEAUSCOLAIRE_CAPBEPCFPA,CODENIVEAUSCOLAIRE_BAC,CODENIVEAUSCOLAIRE_BTSDUT)))
							if($training_duration>400 && $training_duration<1400)
								if(!in_array($training_romesvises,array('G1204','L1401')))
									if(!in_array($training_racineformacode,array('430','434','440','150','154')) ||
										in_array($training_formacode,array(44067,15447,15448,15457,15458)))
										if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
										   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces) &&
										   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
										   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
										   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
										   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) && 
										   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
											if(!$training_contratprofessionalisation && !$training_contratapprentissage)
												if(!in_array($training_formacode,array('13030','13250','31802','31815','50245','50445','50145','50349','50249')))
													if(!$training_contratprofessionalisation && !$training_contratapprentissage)
														if(!hasStrings(array('BTS','BACHELOR','CAPACITÉ','DAEU','DEUST','DIPLÔME D\'ACCÈS AUX ÉTUDES UNIVERSITAIRES','INGÉNIEUR','LICENCE','MASTER','MASTÈRE'),$ar['intitule']))
														{
															arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_normandie'=>$array));
															if($allocation_type=='are')
																remuAREF($var,'finindividuel_normandie',$droits);
															else
																remuRPS($var,'finindividuel_normandie',$droits);
														}
		}

		/* Ligne 9 (Normandie) */
		/* Caractéristiques DE: */
		//Toute personne de plus de 16 ans
		//de niveau Vbis et VI et sans niveau
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//F° avec code financeur Région, + intitulé 'Programme de formation de base ou PFB

		/* Rémunération: */
		//AREF ou ASSF sinon pas de rémunération

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme régional de formation de base";
			$array['step']="Contactez l'organisme de formation et en échanger avec votre Conseiller emploi qui validera avec vous ce projet de formation";
			$array['descinfo']="Vous souhaitez acquérir la maîtrise des compétences de base (communication écrite et orale, connaissances mathématiques, utilisation de l’ordinateur, méthodologie d’apprentissage) et des compétences éco-citoyennes nécessaires pour accéder à la qualification et/ou à l’emploi durable.";
			$array['info']="<a href=\"http://bn-aides.normandie.fr/index.php/5-formation-tout-au-long-de-la-vie-developpement-economique-recherche-et-innovation-tourisme/19-dispositif-qformation-tout-au-long-de-la-vieq/12-formation-de-base\" target=\"_blank\">bn-aides.normandie.fr</a>";
			$array['cost']="Formation totalement financée par le Conseil régional de Normandie";
			if($situation_inscrit)
				if(isInRegionNormandie($training_locationpath))
					if($age>16)
						if(!$situation_liccsp)
							if($training_niveausortie<=CODENIVEAUSCOLAIRE_BAC)
								if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
									if(hasStrings(array('PROGRAMME DE FORMATION DE BASE','PFB'),$ar['intitule']))
									{
										arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_normandie4'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'actioncollectiveregion_normandie4',$droits);
										else
										if($allocation_type=='ass')
											remuFORM2($var,'actioncollectiveregion_normandie4',$droits);
										else
											remuTEXT($var,'actioncollectiveregion_normandie4',$droits);
									}
		}

		/* Ligne 10 (Normandie) */
		/* Caractéristiques DE: */
		//Demandeurs d'emploi inscrits en Normandie
		//sortis de formation initiale (scolaire, universitaire et apprentissage) depuis au moins 9 mois
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//F° hors financeur Région PE et OPCA, hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"
		//et de + 300 H
		//et hors formacodes sanitaires et sociales (liste :Infirmier 43 448
		//Aide soignant 43436; Ambulancier : 31815; Aux de puériculture 43441 )
		//et de 12 mois maximum
		//et dont période en entreprise : 50% maximum de la durée totale de la F°
		//et de niveau IV VI et V
		//'43436','31815','43441'

		/* Rémunération: */
		//AREF ou rémuneration RSP

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Qualif individuel niveau IV, V et VI";
			$array['step']="Effectuez les démarches de validation de votre projet de formation et assurez-vous qu'elle corresponde aux emplois offerts sur votre secteur géographique.<br/>Et vous contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)<br/>Completer le dossier de demande finanacemt,<br/>Faire completer par l'Organisme de formation la seconde partie de la demande,<br/>Votre conseiller fera parvenir à la région votre demande,";
			$array['descinfo']="La région compléte l'offre de formation collective en finançant des formations individuelles répondant aux besoins des Normands.<br/>Elle ne peut béneficier aux demandeurs d'emploi ayant déja suivi une formation qualifiante au cours des 12 derniers mois, pour laquelle un financement institutionnel (Région,Pôle emploi, Agefiph, Fongecif) avait été accordé.";
			$array['info']="<a href=\"http://bn-aides.normandie.fr/index.php/5-formation-tout-au-long-de-la-vie-developpement-economique-recherche-et-innovation-tourisme/18-formation-qualifiante-pour-les-demandeurs-demploi/11-qualif-programme-individuel\" target=\"_blank\">bn-aides.normandie.fr</a>";
			$array['cost']="Formation totalement financée par le Conseil régional de Normandie.";
			if($situation_inscrit)
				if(isInRegionNormandie($domicilePath) && isInRegionNormandie($training_locationpath))
					if(!$situation_liccsp)
						if($training_duration>300 && $training_dureeenmois<=12)
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
								if(!in_array($training_formacode,array('43436','31815','43441')))
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										if($training_niveausortie<=CODENIVEAUSCOLAIRE_BAC)
											if(!$training_nbheuresentreprise || ($training_nbheurestotales && $training_nbheuresentreprise<($training_nbheurestotales/2)))
											{
												arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_normandie2'=>$array));
												if($allocation_type=='are')
													remuAREF($var,'finindividuel_normandie2',$droits);
												else
													remuRPS($var,'finindividuel_normandie2',$droits);
											}
		}

		/* Ligne 11 (Normandie) */
		/* Caractéristiques DE: */
		//Demandeurs d'emploi inscrits en Normandie
		//sortis de formation initiale (scolaire, universitaire et apprentissage) depuis au moins 9 mois,
		//n'ayant pas béneficié d'une formation qualifiante au cours des douze derniers mois (Région, Pôle emploi, Agefiph, Fongécif,,)
		//hors DE licencies éco en CSP (rému ASP)

		/* Caractéristiques formation: */
		//F° en Normandie
		//F° hors financeur Région PE et OPCA, hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"
		//et de + 300 H à 12 mois maximum pour formation de niveau II et III
		//et de + 300 H à 24 mois maximum pour formation de niveau I
		//(ne pas CODER) et de 12 mois maximum
		//et dont période en entreprise : 50% maximum
		//et de niveau VI et V
		//et hors formacodes sanitaires et sociales (liste :Infirmier 43448
		//Aide soignant 43436; Ambulancier : 31815; Aux de puériculture 43441 )
		//'43436','31815','43441'

		/* Rémunération: */
		//AREF ou rémuneration RSP

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Qualif individuel niveau I, II et III";
			$array['step']="Effectuez les démarches de validation de votre projet de formation et assurez-vous qu'elle corresponde aux emplois offerts sur votre secteur géographique.<br/>Et vous contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)<br/>Completer le dossier de demande finanacemt,<br/>Faire completer par l'Organisme de formation la seconde partie de la demande,<br/>Votre conseiller fera parvenir à la région votre demande,";
			$array['descinfo']="La région compléte l'offre de formation collective en finançant des formations individuelles répondant aux besoins des Normands,";
			$array['info']="<a href=\"http://bn-aides.normandie.fr/index.php/5-formation-tout-au-long-de-la-vie-developpement-economique-recherche-et-innovation-tourisme/18-formation-qualifiante-pour-les-demandeurs-demploi/11-qualif-programme-individuel\" target=\"_blank\">bn-aides.normandie.fr</a>";
			$array['cost']="Formation partiellement financée par le Conseil régional de Normandie.";
			if($situation_inscrit)
				if(isInRegionNormandie($domicilePath) && isInRegionNormandie($training_locationpath))
					if(!$situation_liccsp)
						if(($training_duration>=300 && $training_dureeenmois<=12 && in_array($training_niveausortie,array(CODENIVEAUSCOLAIRE_BTSDUT,CODENIVEAUSCOLAIRE_LICENCEMAITRISE))) ||
						   ($training_duration>=300 && $training_dureeenmois<=24 && $training_niveausortie>=CODENIVEAUSCOLAIRE_SUPMAITRISE))
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
								if(!$training_contratprofessionalisation && !$training_contratapprentissage)
									if(!in_array($training_formacode,array('43448','43436','31815','43441')))
										if(!$training_nbheuresentreprise || ($training_nbheurestotales && $training_nbheuresentreprise<($training_nbheurestotales/2)))
										{
											arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_normandie3'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'finindividuel_normandie3',$droits);
											else
												remuRPS($var,'finindividuel_normandie3',$droits);
										}
		}

		/* Ligne 12 (Normandie) */
		/* Caractéristiques DE: */
		//

		/* Caractéristiques formation: */
		//F° en Normandie + F° code financeur OPCA + intitulé 'POEC'
		//attention !!
		//ne pas tenir compte du code financeur OPCA en région Normandie hors intitulé POEC ou ADEMA : ne pas afficher POEC )

		/* Rémunération: */
		//AREF + RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Préparation Opérationnelle à l'Emploi Collective (POEC)";
			$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par l'OPCA";
			$array['descinfo']="Dans le cadre de la Préparation opérationnelle à l'Emploi, une branche professionnelle identifie des besoins de formation dans les entreprises relevant de son secteur. L’OPCA met en place, en partenariat avec Pôle emploi, des actions de formation collectives pour former des demandeurs d’emploi en réponse aux compétences recherchées par les entreprises";
			$array['info']="<a href=\"http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321\" target=\"_blank\">www.pole-emploi.fr</a>";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionNormandie($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
						if(hasStrings(array('POEC'),$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'poecollective',$display,array('poecollective_normandie'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'poecollective_normandie',$droits);
							else
								remuRFPE2($var,'poecollective_normandie',$droits);
						}
		}

		/* Ligne 13 (Normandie) */
		/* Caractéristiques DE: */
		//tout DE

		/* Caractéristiques formation: */
		//F° en Normandie + F° code financeur OPCA + intitulé 'ADEMA'

		/* Rémunération: */
		//AREF sinon saisir '660 € allocation FAFSEA'

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Accès des Demandeurs d’Emploi<br/>aux Métiers Agricoles (ADEMA)";
			$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par l'OPCA du secteur agricole , le FAFSEA";
			$array['descinfo']="Ce dispositif ADEMA s'adresse aux Demandeurs d'emploi souhaitant découvrir les métiers de l’agriculture Il permet d'alterner 7 jours en centre de formation et 15 jours d'immersion en entreprise agricole.<br/>Ce dispositif ne s'adresse donc pas aux Demandeurs d'emploi qui connaisent déja les métiers de l'agriculture (ceux ayant déjà bénéficié au cours des 36 mois précédents d’une prise en charge financière par le FAFSEA parmi les dispositifs suivants : CIF CDD, CIF CDI ou Congé de formation professionnalisant, ou ayant suivi une formation agricole ou du paysage de plus de 3 mois, ou ayant déjà une expérience de travail en agriculture ou dans le paysage depuis plus de 3 mois.)";
			$array['info']="<a href=\"http://www.fafsea.com/adema/index.html\" target=\"_blank\">www.fafsea.com</a>";
			$array['cost']="Formation totalement financée par le FASEA";
			if($situation_inscrit)
				if(isInRegionNormandie($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
						if(hasStrings(array('ADEMA'),$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_normandie4'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'finindividuel_normandie4',$droits);
							else
								remuTEXT($var,'finindividuel_normandie4',$droits,'660 € allocation FAFSEA');
						}
		}

		/* Ligne 14 (Normandie) */
		/* Caractéristiques DE: */
		//DE en Normandie + ARE + inscrits depuis 3,5 mois et moins, hors CDD, hors intérim, hors contrat aidé

		/* Caractéristiques formation: */
		//tte formation avec code certifinfo + durée > ou = 400h et < ou = 1200h + date de début < 31-12-2017, et date de fin < 31-12-2018
		//hors code financeur Région, coll terr, PE ...hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"

		/* Rémunération: */
		//AREF

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Congé Individuel de Formation - CIF CDI portable";
			$array['step']="Contacter votre conseiller Pôle emploi ou le Fongécif Normandie 4 mois au plus tard après votre licenciement.<br/>Antenne CAEN 15 Avenue de Cambridge, 14200 Hérouville-Saint-Clair Téléphone: 02 31 46 26 46<br/>Antenne ROUEN 95 Allée Alfred Nobel, 76230 Bois-Guillaume Téléphone: 02 35 07 95 59<br/>Antenne EVREUX Route de Louviers, 27930 CaerNormanvilleTéléphone: 02 32 39 49 43<br/>Antenne LE HAVRE59 Quai de Southampton, 76600 Le Havre Téléphone: 02 35 41 76 69";
			$array['descinfo']="Expérimentation conduite en 2017 : permettre à des personnes licenciées alors qu'elles étaient en CDI de bénéficier du CIF pour financer une formation en lien avec leur nouveau projet professionnel. Pour en bénéficier, il faut avoir travaillé au moins 24 mois dont 12 dans l'entreprise du licenciement , avoir été licencié d'un CDI, hors licenciement économique ou plan de départ volontaire, hors entreprises relevant des OPACIF AFDAS (culture-spectacle), Uniformation (ESS), FAFTT (intérim) ou du service public";
			$array['info']="";
			$array['cost']="formation totalement financée jusqu'à 12 000 €";
			$array['cost-plafond']="12000";
			if($situation_inscrit)
				if(isInRegionNormandie($domicilePath) && isInRegionNormandie($training_locationpath))
					if($allocation_type=='are')
						if(!$situation_interim && !$situation_interim1600h)
							if(!$situation_personnesortantcontrataide)
								if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										if(Tools::inDateInterval($enddate,'2017-01-01','2017-12-31'))
											if(Tools::inDateInterval($enddate,'2017-01-01','2018-12-31'))
												if($training_codecertifinfo)
													if($training_duration>=400 && $training_duration<=1200)
													{
														arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_normandie5'=>$array));
														remuAREF($var,'finindividuel_normandie5',$droits);
													}
		}

		/* Ligne 15 (Normandie) */
		/* Caractéristiques DE: */
		//DE : -> checkbox " vous avez terminé un contrat à durée déterminée depuis moins d’un an"
		//"vous totalisez 24 mois de travail dans le secteur privé, depuis le (5 ans)"
		//..." dont 4 mois, consécutifs ou non, sous CDD, depuis le (12 derniers mois)". oui ou non
		//"date de fin de votre dernier CDD: xx/xx/xxxx"
		//- d'un an avant date début formation

		/* Caractéristiques formation: */
		//tag : toute formation hors code financeur PE, Région, coll terr, OPCA etc
		//hors tag "contrat d'apprentissage" + hors Tag "contrat de professionnalisation"
		//durée max : 1 an ou 1200H si intensité hebdomadaire <30h

		/* Rémunération: */
		//salaire moyen perçu en CDD

		if(0)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Congé individuel de formation CDD dérogatoire (CIF CDD dérogatoire)";
			$array['step']="Contacter votre conseiller Pôle emploi ou le Fongécif Normandie.<br/>Antenne CAEN 15 Avenue de Cambridge, 14200 Hérouville-Saint-Clair Téléphone: 02 31 46 26 46<br/>Antenne ROUEN 95 Allée Alfred Nobel, 76230 Bois-Guillaume Téléphone: 02 35 07 95 59<br/>Antenne EVREUX Route de Louviers, 27930 CaerNormanvilleTéléphone: 02 32 39 49 43<br/>Antenne LE HAVRE59 Quai de Southampton, 76600 Le Havre Téléphone: 02 35 41 76 69";
			$array['descinfo']="Le Congé Individuel de Formation (CIF) permet à tout salarié en contrat ou ancien titulaire de contrat à durée déterminée (CDD) de suivre, à son initiative et à titre individuel, une formation,<br/>En 2017, en Normandie, le Fongecif assouplit les critères d'accès au CIF CDD. SI vous avez entre 5 et 10 ans d'expérience, vous pouvez bénéficier d'un CIF dès lors que vous avez eu deux mois de CDD dans les 12 derniers mois.<br/>SI vous avez plus de 10 ans d'expérience professionnelle, le CIF est possible dès lors que vous avez eu un CDD d'un mois dans les 12 derniers mois.<br/>L'OPACIF de l'entreprise qui vous employait en CDD doit être le Fongécif.";
			$array['info']="";
			$array['cost']="Formation totalement ou partiellement financée";
			if($situation_inscrit)
				if($situation_cdd12moisdepuisle)
					if(Tools::calcDiffDate($training_dateend,$situation_cdddatedefin)<=12)
						if($training_dureeenmois<=12 || ($training_nbheurestotales<=1200 && $training_intensitehebdomadaire<30))
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
								if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								{
									arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_normandie6'=>$array));
									remuTEXT($var,'finindividuel_normandie6',$droits,sprintf('%f € / mois',$situation_salairebrutecdd));
								}
		}
	}
?>
