<?php
	/* Règles Bretagne ****************************************************************************************************/
	function reglesBretagne($quark,$var,&$droits,&$display)
	{
		extract($var);
		/* Ligne 3 (BRETAGNE) */
		/* Caractéristiques DE: */
		//Tout DE domicilié en Bretagne

		/* Caractéristiques formation: */
		//code Financeur PE + localisation Bretagne

		/* Rémunération: */
		//AREF RFF RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Action de formation conventionnée Pôle emploi";
			$array['step']="Un besoin de formation doit avoir été préalablement identifié dans le plan de sécurisation en cohérence avec votre projet professionnel.<br/>Vous devez être inscrit sur la liste des Demandeurs d'emploi et conctacter votre conseiller référent Pôle emploi car c'est lui qui validera votre projet.<br/>Il consultera avec vous les offres de formation conventionnées disponibles. Votre conseiller peut aussi vous inviter à assister à une information collective. Vous pouvez également contacter directement l'organisme de formation.";
			$array['descinfo']="Il s'agit d'une formation dont les frais pédagogiques sont intégralement financés par Pôle emploi. Les rémunérations sont prises en charge par Pôle emploi.<br/>Le contenu des formations conventionnées est connu de votre conseiller. Elles correspondent pour la plupart d'entre elles à des formations professionnalisantes ou d'adaptation à l'emploi. N'hésitez pas à le solliciter ou interroger le centre de formation pour obtenir plus de précisions. Des aides à la mobilité peuvent être octroyées sous certaines conditions.";
			$array['info']="";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionBretagne($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
						if(!hasStrings(array("POEC","PREPARATION OPERATIONNELLE A L'EMPLOI"),$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_bretagne'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'poleemploicollectif_bretagne',$droits);
							else
								remuRFPE2($var,'poleemploicollectif_bretagne',$droits);
						}
		}

		/* Ligne 4 (BRETAGNE) */
		/* Caractéristiques DE: */
		//Tout DE domicilié en Bretagne

		/* Caractéristiques formation: */
		//code financeur Région + niveau V à III + localisation Bretagne
		//hors formacode 31815, 43448, 43092, 43439, 43436, 43441, 43497, 44038, 44092, 44008, 44028, 44084, 44050 ou certifinfo 87185, 87187, 87189
		//'31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050','87185','87187','87189'

		/* Rémunération: */
		//Aref et RFF (si sur la liste préfectorale)... Si pas d'ARE ou si pas de RFF : RSP

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Bretagne Formation";
			$array['step']="Vous devez être inscrit à Pôle emploi et conctacter votre conseiller référent Pôle emploi car c'est lui qui validera votre projet. En effet, votre besoin de formation doit avoir été identifié dans un plan de sécurisation et être en cohérence avec votre projet professionnel. Votre conseiller peut vous inviter à assister à une information collective. Vous pouvez également contacter directement les organismes de formation.";
			$array['descinfo']="la Région finance des formations certifiantes (diplômantes) correspondant à des qualifications professionnelles de niveau V à III, recherchées sur l'ensemble du territoire breton (BP, BPJEPS, Titre Professionnel, CAP, BAC Pro, Bepecaser, Brevet, Brevet Professionnel, BTS, Diplôme Universitaire, CQP) et couvrant 14 secteurs d'activité : Agriculture- Animation- Arts artisanant- BTP- Commerce/Vente- Fonctions transversales de l'entreprise- Hôtellerie/restauration/tourisme- Industries/IAA - Mécanique - Métallurgie/plasturgie - Pêche mer nautisme / Service aux entreprises et aux collectivités- Services d'aides à la personne- Transport/Logistique. Pour en savoir plus : se rendre sur <a href=\"http://www.seformerenbretagne.fr\" target=\"_blank\">www.seformerenbretagne.fr</a> <a href=\"http://www.seformerenbretagne.fr/\" target=\"_blank\">www.seformerenbretagne.fr</a>";
			$array['info']="";
			$array['cost']="Formation totalement financée (pour les formations de niveau III une participation maximum de 200 € peut être demandée).";
			if($situation_inscrit)
				if(isInRegionBretagne($domicilePath) && isInRegionBretagne($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if($training_niveausortie<=CODENIVEAUSCOLAIRE_BTSDUT)
							if(!in_array($training_formacode,array('31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050')) && !
							   !in_array($training_codecertifinfo,array('87185','87187','87189')))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_bretagne'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_bretagne',$droits);
								else
									remuRPS($var,'actioncollectiveregion_bretagne',$droits);
							}
		}

		/* Ligne 5 (BRETAGNE) */
		/* Caractéristiques DE: */
		//Jeunes de 18 à 26 ans, inscrits à Pôle Emploi et suivi par une Mission locale ou jeune titulaire d'un contrat d'avenir 
		//hos diplômés de formation initiale depuis moins d'un an
		//DE + 26 ans inscrit à Pôle emploi;
		//Jeunes en emploi d'avenir
		//+ (pour tous)
		//domicilé en Bretagne
		//hors TH

		/* Caractéristiques formation: */
		//F° niveau < à III + localisée en Bretagne + code RNCP # vide ou tag CPF COPANEF-COPAREF si RNCP = vide.
		//hors code financeur Région, PE, Coll terr autres, Etat, Etat autres (codes 8,9,11, 12, 13, 14, 15) , hors tag contrat de pro ou contrat d'apprentissage
		//hors formation avec intitulé comprenant FIMO, FCOS, CACES, TOEIC, TOEFL, BULATS, TOSA, habilitations électriques, ou "certification" comprenant TOEIC, TOEFL, BULATS, TOSA, FIMO, FCOS, CACES
		//hors formacode 13250,31815, 43448, 43092, 43439, 43436, 43441, 43497, 44038, 44092, 44008, 44028, 44084, 44050 ou certifinfo 87185, 87187, 87189
		//hors "modalité : formation à distance"
		//'13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050','87185','87187','87189'

		/* Rémunération: */
		//pour les DE en Are : versement de l'AREF. Si non, rémunération assurée par la Région. (RSP)

		if(1)
		{
			$array=array();
			$array['pri']="prioritaire/AIF";
			$array['title']="Chèque formation";
			$array['step']="Contacter votre conseille emploi. Votre projet de formation doit être validé par une prestation de bilan ou de validation de projet. Si votre conseiller estime que le projet est cohérent, cette prestation n'est pas nécessaire.<br/>Pour les formations éligibles au compte personnel de formation (CPF), son utilisation sera demandée en déduction ou en complément du Chèque Formation<br/>Vous devez justifier d'une résidence en Bretagne depuis au moins 6 mois à la date d'entrée en formation.";
			$array['descinfo']="Cette aide vous permet d'accéder à des formations dès lors qu'elle est sanctionnée par un diplôme ou un titre inscrit au RNCP et/ou éligible au CPF).<br/>Les formations longues, jusqu'à trois ans peuvent être financées. L'engagement de la Région est alors subordonné à la production d'une attestation de passage en année supérieure, accompagnée d'un dossier de demande et d'un devis actualisé.<br/>Les  formations peuvent se dérouler à distance, avec des périodes de regroupement auprès de l'établissement.<br/>Les formations doivent se dérouler en Bretagne, sauf si la formation envisagée n'y existe pas.<br/>. <a href=\"http://www.seformerenbretagne.fr/\" target=\"_blank\">www.seformerenbretagne.fr</a>";
			$array['info']="";
			$array['cost']="Formation totalement financée dans la limite de 3050 €.<br/>Pour les formations éligibles au CPF dont le coût est inférieur à 3050 €, le montant de votre CPF est inclus dans l'aide du Conseil Régional. Si le coüt est supérieur à 3050 €, le montant de votre CPF s'ajoute à l'aide du Conseil régional.";
			$array['cost-plafond']="3050";
			if($situation_inscrit && !$situation_th)
				if(isInRegionBretagne($domicilePath) && isInRegionBretagne($training_locationpath))
					if($training_niveausortie<CODENIVEAUSCOLAIRE_BTSDUT)
						if($training_rncp || hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
								if(!$training_contratprofessionalisation && !$training_contratapprentissage)
									if(!in_array($training_formacode,array('13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050')))
										if(!in_array($training_codecertifinfo,array('87185','87187','87189')))
											if(!$training_adistance)
												if(!hasStrings(array('FIMO','FCOS','CACES','TOEIC','TOEFL','BULATS','TOSA','HABILITATIONS ÉLECTRIQUES'),$ar['intitule']))
												{
													arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_bretagne'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'finindividuel_bretagne',$droits);
													else
														remuRPS($var,'finindividuel_bretagne',$droits);
												}
		}

		/* Ligne 6 (BRETAGNE) */
		/* Caractéristiques DE: */
		//Tout DE TH
		//domicilé en Bretagne

		/* Caractéristiques formation: */
		//F° niveau < à III + localisée en Bretagne + code RNCP # vide ou tag CPF COPANEF-COPAREF si RNCP = vide.
		//hors code financeur Région, PE, Coll terr autres, Etat, Etat autres (codes 8,9,11, 12, 13, 14, 15) , hors tag contrat de pro ou contrat d'apprentissage
		//hors formation avec intitulé comprenant FIMO, FCOS, CACES, TOEIC, TOEFL, BULATS, TOSA, habilitations électriques, ou "certification" comprenant TOEIC, TOEFL, BULATS, TOSA, FIMO, FCOS, CACES
		//hors formacode 13250,31815, 43448, 43092, 43439, 43436, 43441, 43497, 44038, 44092, 44008, 44028, 44084, 44050 ou certifinfo 87185, 87187, 87189
		//hors "modalité : formation à distance"
		//'13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050','87185','87187','87189'

		/* Rémunération: */
		//pour les DE en Are : versement de l'AREF. Si non, rémunération assurée par la Région. (RSP)

		if(1)
		{
			$array=array();
			$array['pri']="prioritaire/AIF";
			$array['title']="Chèque formation";
			$array['step']="Contacter votre conseille emploi. Votre projet de formation doit être validé par une prestation de bilan ou de validation de projet. Si votre conseiller estime que le projet est cohérent, cette prestation n'est pas nécessaire.<br/>Pour les formations éligibles au compte personnel de formation (CPF), son utilisation sera demandée en déduction ou en complément du Chèque Formation<br/>Vous devez justifier d'une résidence en Bretagne depuis au moins 6 mois à la date d'entrée en formation.";
			$array['descinfo']="Cette aide vous permet d'accéder à des formations dès lors qu'elle est sanctionnée par un diplôme ou un titre inscrit au RNCP et/ou éligible au CPF).<br/>Les formations longues, jusqu'à trois ans peuvent être financées. L'engagement de la Région est alors subordonné à la production d'une attestation de passage en année supérieure, accompagnée d'un dossier de demande et d'un devis actualisé.<br/>Les  formations peuvent se dérouler à distance, avec des périodes de regroupement auprès de l'établissement.<br/>Les formations doivent se dérouler en Bretagne, sauf si la formation envisagée n'y existe pas.<br/>. <a href=\"http://www.seformerenbretagne.fr/\" target=\"_blank\">www.seformerenbretagne.fr</a>";
			$array['info']="";
			$array['cost']="Formation totalement financée dans la limite de 9150 €";
			$array['cost-plafond']="9150";
			if($situation_inscrit && $situation_th)
				if(isInRegionBretagne($domicilePath) && isInRegionBretagne($training_locationpath))
					if($training_niveausortie<CODENIVEAUSCOLAIRE_BTSDUT)
						if($training_rncp || hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) && 
							   !$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!in_array($training_formacode,array('13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050')) && 
								   !in_array($training_codecertifinfo,array('87185','87187','87189')))
									if(!hasStrings(array('FIMO','FCOS','CACES','TOEIC','TOEFL','BULATS','TOSA','HABILITATIONS ÉLECTRIQUES'),$ar['intitule']))
										if(!$training_adistance)
										{
											arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_2'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'finindividuel_2',$droits);
											else
												remuRPS($var,'finindividuel_2',$droits);
										}
			}

		/* Ligne 7 (BRETAGNE) */
		/* Caractéristiques DE: */
		//Jeunes de 18 à 26 ans, inscrits à Pôle Emploi et suivi par une Mission locale ou jeune titulaire d'un contrat d'avenir 
		//hos diplômés de formation initiale depuis moins d'un an
		//DE + 26 ans inscrit à Pôle emploi;
		//Jeunes en emploi d'avenir
		//+ (pour tous)
		//domicilé en Bretagne
		//hors TH

		/* Caractéristiques formation: */
		//F° niveau > ou = à III, II et I + localisée en Bretagne + code RNCP # vide, ou tag CPF COPANEF-COPAREF si RNCP = vide.
		//hors code financeur Région, PE, Coll terr autres, Etat, Etat autres (codes 8,9,11, 12, 13, 14, 15) , hors tag contrat de pro ou contrat d'apprentissage
		//hors formation avec intitulé comprenant FIMO, FCOS, CACES, TOEIC, TOEFL, BULATS, TOSA, habilitations électriques, ou "certification" comprenant TOEIC, TOEFL, BULATS, TOSA, FIMO, FCOS, CACES
		//hors formacode 13250,31815, 43448, 43092, 43439, 43436, 43441, 43497, 44038, 44092, 44008, 44028, 44084, 44050 ou certifinfo 87185, 87187, 87189
		//hors hors "modalité : formation à distance"
		//'13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050','87185','87187','87189'

		/* Rémunération: */
		//AREF... Si non, rémunération assurée par la Région. (RSP)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Chèque formation (pour les formations de niveau bac + 2 et plus)";
			$array['step']="Attention, pour obtenir une aide pour les formations de niveau supérieur à bac + 2, vous devrez justifier de 2 ans d’activité professionnelle.<br/>Contacter votre conseille emploi. Votre projet de formation doit être validé par une prestation de bilan ou de validation de projet. Si votre conseiller estime que le projet est cohérent, cette prestation n'est pas nécessaire.<br/>Pour les formations éligibles au compte personnel de formation (CPF), son utilisation sera demandée en déduction ou en complément du Chèque Formation<br/>Vous devez justifier d'une résidence en Bretagne depuis au moins 6 mois à la date d'entrée en formation.";
			$array['descinfo']="Cette aide vous permet d'accéder à des formations dès lors qu'elle est sanctionnée par un diplôme ou un titre inscrit au RNCP et/ou éligible au CPF).<br/>Les formations longues, jusqu'à trois ans peuvent être financées. L'engagement de la Région est alors subordonné à la production d'une attestation de passage en année supérieure, accompagnée d'un dossier de demande et d'un devis actualisé.<br/>Les  formations peuvent se dérouler à distance, avec des périodes de regroupement auprès de l'établissement.<br/>Les formations doivent se dérouler en Bretagne, sauf si la formation envisagée n'y existe pas.<br/>. <a href=\"http://www.seformerenbretagne.fr/\" target=\"_blank\">www.seformerenbretagne.fr</a>";
			$array['info']="";
			$array['cost']="Formation totalement financée, dans la limite de 3050 €.<br/>Pour les formations éligibles au CPF dont le coût est inférieur à 3050 €, le montant de votre CPF est inclus dans l'aide du Conseil Régional. Si le coüt est supérieur à 3050 €, le montant de votre CPF s'ajoute à l'aide du Conseil régional.";
			$array['cost-plafond']="3050";
			if($situation_inscrit && !$situation_th)
				if(isInRegionBretagne($domicilePath) && isInRegionBretagne($training_locationpath))
					if($training_niveausortie>=CODENIVEAUSCOLAIRE_BTSDUT)
						if($training_rncp || hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
							   !$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!in_array($training_formacode,array('13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050')) && 
								   !in_array($training_codecertifinfo,array('87185','87187','87189')))
									if(!hasStrings(array('FIMO','FCOS','CACES','TOEIC','TOEFL','BULATS','TOSA','HABILITATIONS ÉLECTRIQUES',$ar['intitule'])))
										if(!$training_adistance)
										{
											arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_3'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'finindividuel_3',$droits);
											else
												remuRPS($var,'finindividuel_3',$droits);
										}
		}

		/* Ligne 8 () */
		/* Caractéristiques DE: */
		//Salarié issu d'une entreprises de moins de 250 salariés, en situation de chômage partiel cumulé d'au moins 4 semaines, sans qualification ou titulaire d'une qualification ne permettant pas de retrouver un emploi

		/* Caractéristiques formation: */
		//Ne sont pas éligibles :formations réglementaires obligatoires à l’exercice d’une profession du secteur de la logistique et du transport (FIMO, FCOS, CACES 1-3-5 …),La formation doit se dérouler en Bretagne, sauf si la formation envisagée n'y existe pas.
		//La formation peut se dérouler en présentiel ou à distance avec des sessions de regroupement
		//La formation doit s’intégrer dans un projet professionnel, validé par la cellule de reclassement ou Pôle emploi

		/* Rémunération: */
		//Votre salaire ainsi que votre allocation perçue lors de votre contrat de sécurisaton professionnelle (CSP) ou de votre congé de reclassement, de la convention de reclassement personnalisée ou du contrat de transition professionnelle sont maintenus.

		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="";
		//	$array['step']="● Etape préalable Cette aide est prescrite et mise en œuvre exclusivement par une cellule de reclassement ou par une agence spécialisée de Pôle Emploi qui construit et valide le projet de formation avec le bénéficiaire. ● Saisie en ligne de la candidature par l'organisme de formation Une fois le projet validé, le candidat s'adresse à l'organisme de formation choisi pour la saisie de sa demande sur l'extranet de la Région dédié aux aides individuelles à la formation. ● Instruction et décision La demande fait l'objet d'une instruction par les services de la Région, tant sur la formati";
		//	$array['descinfo']="Le Chèque Reconversion est une aide individuelle qui permet aux salariés concernés par une procédure de licenciement économique de suivre une formation diplômante ou d'adaptation à l'emploi afin de faciliter leur retour à l'emploi. La formation s'inscrit dans un projet professionnel construit avec le soutien de la cellule de reclassement ou du Pôle Emploi.";
		//	$array['info']="<a href=\"http://www.seformerenbretagne.fr/\" target=\"_blank\">www.seformerenbretagne.fr</a>";
		//	$array['cost']="L'aide de la Région est de 15 € maximum par heure de formation, avec un montant plafonné à 3 050 € par projet de formation, portant sur les seuls frais pédagogiques. Pour les personnes handicapées, en application d'une convention spécifique conclue avec l’Agefiph, le plafond est porté à 9 150 €, pour un coût de 15 € maximum par heure de formation. L'aide de la Région intervient après le financement de l'OPCA et/ou de l'employeur pour les formations éligibles au Compte personnel de formation(CPF). Pour les formations RNCP mais non éligibles au CPF, la Région intervient directement ou en complément de l'employeur. MODALITÉS D E PAIEMENT La participation de la Région est versée directement à l'organisme de formation selon des modalités fixées par un arrêté. Deux modalités sont à distinguer : Pour les formations inférieures à 150 heures, un seul versement au vu de la saisie du bilan de formation qui doit être transmis à la Région au plus tard dans un délai de six mois suivant la date de fin de formation. Pour les formations supérieures à 150 heures : ● un acompte égal à 50 % du montant de la participation prévisionnelle après saisie de l'attestation d'entrée en formation";
		//	if($situation_inscrit)
		//	{
		//		$display['chéque reconversion']=$array;
		//		if($training_adistance)
		//		{
		//		}
		//	}
		//}

		/* Ligne 9 (BRETAGNE) */
		/* Caractéristiques DE: */
		//Tout DE TH
		//domicilé en Bretagne

		/* Caractéristiques formation: */
		//F° niveau > ou = à III, II et I + localisée en Bretagne + code RNCP # vide, ou tag CPF COPANEF-COPAREF si RNCP = vide.
		//hors code financeur Région, PE, Coll terr autres, Etat, Etat autres (codes 8,9,11, 12, 13, 14, 15) , hors tag contrat de pro ou contrat d'apprentissage
		//hors formation avec intitulé comprenant FIMO, FCOS, CACES, TOEIC, TOEFL, BULATS, TOSA, habilitations électriques, ou "certification" comprenant TOEIC, TOEFL, BULATS, TOSA, FIMO, FCOS, CACES
		//hors formacode 13250,31815, 43448, 43092, 43439, 43436, 43441, 43497, 44038, 44092, 44008, 44028, 44084, 44050 ou certifinfo 87185, 87187, 87189
		//hors hors "modalité : formation à distance"
		//'13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050','87185','87187','87189'

		/* Rémunération: */
		//AREF... Si non, rémunération assurée par la Région. (RSP)

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Chèque formation (pour les formations de niveau bac + 2 et plus)";
			$array['step']="Attention, pour obtenir une aide pour les formations de niveau supérieur à bac + 2, vous devrez justifier de 2 ans d’activité professionnelle.<br/>Contacter votre conseille emploi. Votre projet de formation doit être validé par une prestation de bilan ou de validation de projet. Si votre conseiller estime que le projet est cohérent, cette prestation n'est pas nécessaire.<br/>Pour les formations éligibles au compte personnel de formation (CPF), son utilisation sera demandée en déduction ou en complément du Chèque Formation<br/>Vous devez justifier d'une résidence en Bretagne depuis au moins 6 mois à la date d'entrée en formation.";
			$array['descinfo']="Cette aide vous permet d'accéder à des formations dès lors qu'elle est sanctionnée par un diplôme ou un titre inscrit au RNCP et/ou éligible au CPF).<br/>Les formations longues, jusqu'à trois ans peuvent être financées. L'engagement de la Région est alors subordonné à la production d'une attestation de passage en année supérieure, accompagnée d'un dossier de demande et d'un devis actualisé.<br/>Les  formations peuvent se dérouler à distance, avec des périodes de regroupement auprès de l'établissement.<br/>Les formations doivent se dérouler en Bretagne, sauf si la formation envisagée n'y existe pas.<br/>. <a href=\"http://www.seformerenbretagne.fr/\" target=\"_blank\">www.seformerenbretagne.fr</a>";
			$array['info']="";
			$array['cost']="Formation totalement financée, dans la limite de 9150 €.";
			$array['cost-plafond']="9150";
			if($situation_inscrit && $situation_th)
				if(isInRegionBretagne($domicilePath) && isInRegionBretagne($training_locationpath))
					if($training_rncp || hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
						if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces)	&&
						   !$training_contratprofessionalisation && !$training_contratapprentissage)
							if(!in_array($training_formacode,array('13250','31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050')) && 
							   !in_array($training_codecertifinfo,array('87185','87187','87189')))
								if(!hasStrings(array('FIMO','FCOS','CACES','TOEIC','TOEFL','BULATS','TOSA','HABILITATIONS ÉLECTRIQUES'),$ar['intitule']))
									if(!$training_adistance)
									{
										arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_4'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'finindividuel_4',$droits);
										else
											remuRPS($var,'finindividuel_4',$droits);
									}
		}

		/* Ligne 10 (BRETAGNE) */
		/* Caractéristiques DE: */
		//tout DE France entière

		/* Caractéristiques formation: */
		//F° localisées en Bretagne. + formacode 31815, 43448, 43092, 43439, 43436, 43441, 43497, 44038, 44092, 44008, 44028, 44084, 44050 ou certifinfo 87185, 87187, 87189
		//formations visées : 43448 - INFIRMIER , sage femme (43092)- puéricultrice (43439)- aide soignant( 43436) auxiliaire de puéricultrice(43441)- manipulateur en électro radiologie médicale (43497)- ambulancier(31815)-
		//Assistant de service Social (44038)
		//Educateur Spécialisé (44092)
		//Educateur technique Spécialisé (44092)
		//Moniteur éducateur (44092)
		//Technicien de l’Intervention Sociale et Familiale (44008)
		//Conseiller en Economie Sociale et Familiale (44084)
		//Educateur de Jeunes Enfants(44050)
		//certifinfo 87185, 87187, 87189 (AES)
		//Code financeur Région ou liste établissements à trouver. Vérifier si toutes les écoles sont possibles ou si sélection d'écoles.
		//'31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050','87185','87187','87189','43448','43092','43439','43436','43441','43497','31815','44038','44092','44092','44092','44008','44084','44050','87185','87187','87189'

		/* Rémunération: */
		//Aref. si pas d'ARE : possibilité de faire une demande de bourses d'étude auprès de la Région

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Formations du domaine sanitaire et social";
			$array['step']="Contacter l'organisme de formation qui vous fournira les codes d'accès au site de demande de bourse ainsi que les dates d'ouverture des inscriptions selon la formation choisie.";
			$array['descinfo']="Ces formations sont du ressort exclusif du Conseil Régional de Bretagne. Celui-ci détermine les quotas de place, délivre les agréments au centre de formation.<br/>L'entrée dans ces formations est conditionnée par la réussite à des concours spécifiques ou à des examents de sélection<br/><a href=\"http://www.bretagne.bzh/jcms/c_15456/fr/les-formations-sanitaires-et-sociales\" target=\"_blank\">www.bretagne.bzh</a>";
			$array['info']="";
			$array['cost']="Prise en charge des frais pédagogiques par la Région dans la limite des places agréées par la Région.";
			if($situation_inscrit)
				if(isInRegionBretagne($domicilePath) && isInRegionBretagne($training_locationpath))
					if(in_array($training_formacode,array('31815','43448','43092','43439','43436','43441','43497','44038','44092','44008','44028','44084','44050')) ||
					   in_array($training_codecertifinfo,array('87185','87187','87189')))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						{
							arrayInsertAfterKey($droits,'actionecollectiveregion',$display,array('actionecollectiveregion_2'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actionecollectiveregion_2',$droits);
							else
								remuTEXT($var,'actionecollectiveregion_2',$droits,"Possibilité de faire une demande de bourses d'étude auprès de la Région");
						}
		}
	}
?>