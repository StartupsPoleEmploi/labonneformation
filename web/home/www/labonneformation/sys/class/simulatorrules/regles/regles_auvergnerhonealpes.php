<?php
	function reglesAuvergneRhoneAlpes($quark, $var, &$droits, &$display)
	{
		extract($var);
		
		/* Ligne 2 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//Tout DE domicilié en ARA
		//hors salarié IAE ou contrat aidé, créateur d'entreprise

		/* Caractéristiques formation: */
		//Toute Formation en ARA, hors tag code financeur Région, PE, OPCA, Etat, Col ter
		//hors tag "contrat d'apprentissage" et hors Tag "contrat de professionnalisation"
		//hors ROME G1204 et L 1401
		//hors intitulé comprenant BP, Bac, Bac pro, bac techno, brevet professionnel, BPJEPS, BTS, BTSA, Bachelor, Capacité, CAP, DAEU, DEUST, DE, diplôme d'Etat, diplôme national, Diplôme d'accès aux études universitaires, ingénieur, Licence, Master, Mastère
		//hors formacode 15064,44591, certifinfo 83899
		//'15064','44591','83899'

		/* Rémunération: */
		//AREF/ RFF sinon RFPE

		if(1)
		{
			$array=array();
			$array['pri']="2";
			$array['title']="Action de formation préalable au recrutement (AFPR) et préparation opérationnelle à l'emploi individuel (POEI)";
			$array['step']="1. Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un  CDI ou un CDD d'au moins 6 mois. Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 400h.<br/>2. En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier votre plan de formation.";
			$array['descinfo']="Ce dispositif vous permet de vous former en 400h maximum afin d'obtenir les compétences qui vous manquent avant une embauche (en CDD d'au moins 6 mois ou en CDI ou en contrat en alternance d'au moins 12 mois).";
			$array['info']="";
			$array['cost']="Formation totalement financée dans la limite de 400 heures";
			if($situation_inscrit)
				if(!$situation_personneencourscontrataide)
					if(isInRegionAuvergneRhoneAlpes($domicileRegionPath))
						if(isInRegionAuvergneRhoneAlpes($training_locationpath))
						{
							unset($droits['afprpoei'],$droits['poei']);
							if(!$situation_salarie && !$situation_projetcreationentreprise)
							{
								if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										if(!in_array($training_formacode,array('15064','44591')) && !in_array($training_codecertifinfo,array('83899')))
											if(!in_array($training_rome,array('G1204','L1401')))
												if(!hasStrings(explode(', ',"BP, BAC, BAC PRO, BAC TECHNO, BREVET PROFESSIONNEL, BPJEPS, BTS, BTSA, BACHELOR, CAPACITÉ, CAP, DAEU, DEUST, DE, DIPLÔME D'ETAT, DIPLÔME NATIONAL, DIPLÔME D'ACCÈS AUX ÉTUDES UNIVERSITAIRES, INGÉNIEUR, LICENCE, MASTER, MASTÈRE"),$ar['intitule']))
												{
													arrayInsertAfterKey($droits,'afprpoei',$display,array('afprpoei_ara2'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'afprpoei_ara2',$droits);
													else
														remuRFPE2($var,'afprpoei_ara2',$droits);
												}
							}
						}
		}

		/* Ligne 3 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//Tout salarié IAE ou contrat aidé en ARA

		/* Caractéristiques formation: */
		//Toute Formation en ARA, hors tag code financeur Région, PE, OPCA, Etat, Col ter
		//hors tag "contrat d'apprentissage" et hors Tag "contrat de professionnalisation"
		//hors ROME G1204 et L 1401
		//hors intitulé comprenant BP, Bac, Bac pro, bac techno, brevet professionnel, BPJEPS, BTS, BTSA, Bachelor, Capacité, CAP, DAEU, DEUST, DE, diplôme d'Etat, diplôme national, Diplôme d'accès aux études universitaires, ingénieur, Licence, Master, Mastère
		//hors formacode 15064,44591, certifinfo 83899
		//'15064','44591','83899'

		/* Rémunération: */
		//maintien du salaire : rému basée sur le formulaire

		if(1)
		{
			$array=array();
			$array['pri']="2";
			$array['title']="Préparation Opérationnelle à l’Emploi Individuelle (POEI)";
			$array['step']="1. Postulez sur une offre d'emploi déposée auprès de Pôle emploi pour un CDI ou un CDD d'au moins 12 mois. Si vous n'avez pas l'ensemble des compétences demandées, vous pouvez proposer à l'employeur de suivre une formation adaptée d'une durée maximale de 400h.<br/>2. En cas de réponse positive de l'employeur, contactez votre conseiller Pôle emploi qui finalisera avec ce dernier votre plan de formation.";
			$array['descinfo']="Ce dispositif vous permet de vous former en 400h maximum afin d'obtenir les compétences qui vous manquent avant une embauche (en CDD d'au moins 6 mois ou en CDI ou en contrat en alternance d'au moins 12 mois).";
			$array['info']="";
			$array['cost']="Formation totalement financée dans la limite de 400 heures";
			if($situation_inscrit)
				if($situation_personneencourscontrataide)
					if(!$situation_projetcreationentreprise)
						if(isInRegionAuvergneRhoneAlpes($domicileRegionPath))
							if(isInRegionAuvergneRhoneAlpes($training_locationpath))
							{
								if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) && 
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										if(!in_array($training_formacode,array('15064','44591')) && !in_array($training_codecertifinfo,array('83899','84385','54664')))
											if(!in_array($training_rome,array('G1204','L1401')))
												if(!hasStrings(explode(', ',"BP, BAC, BAC PRO, BAC TECHNO, BREVET PROFESSIONNEL, BPJEPS, BTS, BTSA, BACHELOR, CAPACITÉ, CAP, DAEU, DEUST, DE, DIPLÔME D'ETAT, DIPLÔME NATIONAL, DIPLÔME D'ACCÈS AUX ÉTUDES UNIVERSITAIRES, INGÉNIEUR, LICENCE, MASTER, MASTÈRE"),$ar['intitule']))
												{
													arrayInsertAfterKey($droits,'afprpoei',$display,array('afprpoei_ara'=>$array));
													if($allocation_type=='are')
														remuAREF($var,'afprpoei_ara',$droits);
													else
														remuRFPE2($var,'afprpoei_ara',$droits);
												}
							}
		}

		/* Ligne 4 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE ARA avec durée CPF < durée de formation

		/* Caractéristiques formation: */
		//F° France entière tag CPF COPANEF - COPAREF Auvergne, Coparef Rhône Alpes, COPAREF ARA
		//hors tag "contrat d'apprentissage" hors Tag "contrat de professionnalisation"
		//hors code financeur OPCA
		//hors F° avec code CPF COPANEF 200

		/* Rémunération: */
		//AREF - RFPE si votre projet est validé par un conseiller Pôle emploi

		if(0)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Compte Personnel de Formation";
			$array['step']="Contactez un conseiller emploi pour être accompagné dans la mobilisation de votre compte CPF.<br/>Activez votre compte CPF et inscrivez vos éventuelles heures de DIF, non utilisées au titre de votre dernière rupture de contrat de travail,<br/>Vous pouvez utiliser votre CPF même si votre nombre d'heures crédité est inférieur à la durée totale de la formation. : vérifier auprès de l'organisme de formation que vous pouvez valider partiellement cette formation qualifiante sous forme de blocs de compétence. Vous aurez ensuite cinq ans pour valider l'intégralité de la formation qualifiante.<br/>Si le nombre d’heures ou le montant couvert par le CPF est insuffisant, il peut être complété par vous-même ou, sous conditions, par d'autres financements : co-financement par Pôle emploi avec une Aide Individuelle à la Formation (AIF) si le projet est validé par Pôle emploi, ou par un autre co-financeur (Agefiph, ...). <a href=\"http://www.moncompteformation.gouv.fr\" target=\"_blank\">www.moncompteformation.gouv.fr</a>";
			$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle.<br/>Lorsqu’un demandeur d’emploi bénéficie d’heures sur son compte, il peut les mobiliser pour financer toute ou partie d'une formation qualifiante.<br/>Si le montant couvert par le CPF est insuffisant, il peut être complété par vous-même ou, sous conditions, par d'autres financements : co-financement par Pôle emploi avec une Aide Individuelle à la Formation (AIF) si le projet est validé par Pôle emploi, ou par un autre co-financeur (Agefiph, ...). <a href=\"http://www.moncompteformation.gouv.fr\" target=\"_blank\">www.moncompteformation.gouv.fr</a>";
			$array['info']="";
			$array['cost']="Formation partiellement financée sur la base de ";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicileRegionPath))
				{
					if($situation_creditheurescpf)
						if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
									if(!hasCOPANEF($ad,$ar,array(200)))
									{
										$array['cost-plafond'].=($situation_creditheurescpf*9);
										$array['cost'].=$array['cost-plafond'].' €';
										arrayInsertAfterKey($droits,'cpf',$display,array('cpf_ara'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'cpf_ara',$droits);
										else
											remuRFPE2($var,'cpf_ara',$droits);
									}
				}
		}

		/* Ligne 5 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE ARA
		//-avec nb heures CPF = ou > durée de la formation

		/* Caractéristiques formation: */
		//F° France entière tag CPF COPANEF - COPAREF Auvergne, Coparef Rhône Alpes, COPAREF ARA
		//hors tag "contrat d'apprentissage" hors Tag "contrat de professionnalisation"
		//hors code financeur OPCA hors F° avec code CPF COPANEF 200

		/* Rémunération: */
		//AREF sinon RFPE * *si votre projet est validé par un conseiller Pôle emploi

		if(0)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Compte Personnel de Formation";
			$array['step']="Vous pouvez directement faire la demande de financement sur <a href=\"http://www.moncompteformation.gouv.fr/qui-etes-vous#qui-etes-vous\" target=\"_blank\">www.moncompteformation.gouv.fr</a> et prendre contact avec l'organisme de formation pour vous inscrire à la formation";
			$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle.<br/>Lorsqu’un demandeur d’emploi bénéficie d’heures sur son compte, il peut les mobiliser pour financer toute ou partie d'une formation qualifiante.";
			$array['info']="";
			$array['cost']="Formation totalement ou partiellement financée sur la base de 9 € multipliée par vos heures CPF acquises";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicileRegionPath))
				{
					if($situation_creditheurescpf && $situation_creditheurescpf>=$training_duration)
						if(hasCOPANEF($ad,$ar) || hasCOPAREF($ad,$ar,$domicilePath))
							if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
									if(!hasCOPANEF($ad,$ar,array(200)))
									{
										arrayInsertAfterKey($droits,'cpf',$display,array('cpf_ara2'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'cpf_ara2',$droits);
										else
											remuRFPE2($var,'cpf_ara2',$droits);
									}
				}
		}

		/* Ligne 6 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE ARA
		//-avec nb heures CPF = ou > durée de la formation
		//Formation totalement ou partiellement financée sur la base de 9 € multipliée par vos 'heures CPF acquises

		/* Caractéristiques formation: */
		//F° France entière tag CPF COPANEF - COPAREF Auvergne, Coparef Rhône Alpes, COPAREF ARA
		//hors tag "contrat d'apprentissage" hors Tag "contrat de professionnalisation"
		//hors code financeur OPCA hors F° avec code CPF COPANEF 200

		/* Rémunération: */
		//AREF sinon RFPE* *si votre projet est validé par un conseiller Pôle emploi\".

		if(0)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Compte Personnel de Formation";
			$array['step']="Vous pouvez directement faire la demande de financement sur <a href=\"http://www.moncompteformation.gouv.fr/qui-etes-vous\" target=\"_blank\">www.moncompteformation.gouv.fr</a> et prendre contact avec l'organisme de formation pour vous inscrire à la formation.";
			$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle.<br/>Lorsqu’un demandeur d’emploi bénéficie d’heures sur son compte, il peut les mobiliser pour financer toute ou partie d'une formation qualifiante.<br/>Si le montant couvert par le CPF est insuffisant, il peut être complété par vous-même ou, sous conditions, par d'autres financements : co-financement par Pôle emploi avec une Aide Individuelle à la Formation (AIF) si le projet est validé par Pôle emploi, ou par un autre co-financeur (Agefiph, ...). <a href=\"http://www.moncompteformation.gouv.fr\" target=\"_blank\">www.moncompteformation.gouv.fr</a>";
			$array['info']="";
			$array['cost']="";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicileRegionPath))
					if(!hasStrings('',$ar['intitule']))
						if(hasCOPANEF($ad,$ar) && hasCOPAREF($ad,$ar,$domicilePath))
							if($training_contratprofessionalisation && $training_contratapprentissage)
								if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) && hasCOPANEF($ad,$ar))
								{
									arrayInsertAfterKey($droits,'cpf',$display,array('cpf_ara3'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'cpf_ara3',$droits);
									else
										remuRFPE2($var,'cpf_ara3',$droits);
								}
		}

		/* Ligne 7 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE ARA

		/* Caractéristiques formation: */
		//F° France entière
		//hors F° avec :
		//Intitulé comportant "AFC"
		//code financeur "PE" collectif
		//code financeur "'Region"
		//code financeur "OPCA"
		//code financeur Etat
		//code financeur : coll terr, coll terr autres, communes etc
		//Formacode 15081, 13250, 31802, 31805, 31847,31827,31833, 43409, 43436, 43339,43441, 43448, 43454, 44004, 44028, 44054, 15064, 44591
		//domaine = 150
		//certifinfo 54912, 84385, 87185,87187,87189, 83899
		//tag CPF COPANEF = code 201
		//durée > 12 mois
		//durée en entreprise supérieure à 30% du nbre heures total de la formation
		//tag "contrat d'apprentissage" horsTag "contrat de professionnalisation"
		//'15081','13250','31802','31805','31847','31827','31833','43409','43436','43339','43441','43448','43454','44004','44028','44054','15064','44591','54912','84385','87185','87187','87189','83899'

		/* Rémunération: */
		//AREF/ RFPE

		if(1)
		{
			$array=$display['aif'];
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicileRegionPath))
				{
					unset($droits['aif']);
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces))
						if(!in_array($training_formacode,array('13250','31802','31805','31847','31827','31833','43409','43436','43339','43441','43448','43454','44004','44028','44054','15064','44591')))
							if($training_racineformacode!=150)
								if(!in_array($training_codecertifinfo,array('54912','84385','87185','87187','87189','83899')))
									if(!hasCOPANEF($ad,$ar,array(201)))
										if(!$training_contratprofessionalisation && !$training_contratapprentissage)
											if($training_duration<=12)
											{
												arrayInsertAfterKey($droits,'aif',$display,array('aif_ara'=>$array));
												if($allocation_type=='are')
													remuAREF($var,'aif_ara',$droits);
												else
													remuRFPE2($var,'aif_ara',$droits);
											}
				}
		}

		/* Ligne 8 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//subention sur certains diplômes dans certains organismes de formation par la Région, concernant les métiers de Mécanique d’usinage
		//Automobile
		//Bâtiment
		//Chaudronnerie-serrurerie
		//1ere transformation du bois
		//Services.

		/* Caractéristiques formation: */
		//

		/* Rémunération: */
		//Les écoles de production permettent aux jeunes en formation initiale de se former sur cerains métiers en se mettant en situation réelle de production au sein d'une &laquo;&nbsp;école entreprise&nbsp;&raquo;. Ces établissements sont déclarés au rectorat de l'académie et préparent aux diplômes d'état CAP et Bac Professionnel... Certains diplômes de certaines écoles sont subventionnées par le Conseil régional.

		//if(0)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Ecole de production";
		//	$array['step']="en attente précisions région";
		//	$array['descinfo']="jeunes de 14 ans (15 si machines dangereuses) à 18 ans en formation initiale";
		//	$array['info']="pas de formulaire";
		//	$array['cost']="le jeune peut se positionner de lui-même auprès des organismes de formation concernés, sur les formations concernées.";
		//	if($situation_inscrit)
		//	{
		//		$display['fincollectif région']=$array;
		//	}
		//}

		/* Ligne 9 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE < 26 ans, ARA,< niveau V

		/* Caractéristiques formation: */
		//F° en ARA + intitulé de la formation comprend "E2C" ou "Ecole de la 2e chance"

		/* Rémunération: */
		//AREF - RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Ecole de la 2eme chance (E2C)";
			$array['step']="SI vous avez abandonné votre scolarité depuis 6 mois ou plus, présentez-vous directement auprès du centre de formation ou renseignez-vous auprès de votre conseiller emploi.";
			$array['descinfo']="Ce programme est conçu pour permettre à des jeunes (18-25 ans) en difficulté de préparer un projet professionnel et intégrer un parcours de formation ou d'insertion).<br/>- Maîtriser les compétences de base (français, mathématiques, informatique) et la détermination d’un projet professionnel.<br/>- Donner un place importante à l’alternance : 40 à 50% du parcours est consacré aux stages en entreprise pour découvrir le monde du travail, ses contraintes et ses possibilités.<br/>- Parcours de 7 mois en moyenne.";
			$array['info']="";
			$array['cost']="Formation totalement financée par la Région";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionAuvergneRhoneAlpes($training_locationpath))
						if($age<26)
							if($niveauscolaire<CODENIVEAUSCOLAIRE_CAPBEPCFPA)
								if(hasStrings(array('E2C','ECOLE DE LA 2E CHANCE'),$ar['intitule']))
								{
									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_ara',$droits);
									else
										remuRPS($var,'actioncollectiveregion_ara',$droits);
								}
		}

		/* Ligne 10 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//tout DE, ARA

		/* Caractéristiques formation: */
		//F° en ARA + intitulé comprend DAEU, diplôme d'accès aux études universitaires
		//ou formacode 15093 + siret 19691775100014, 19691774400019, 19692437700019, 19692437700191, 19421095100423,13002139700018, 19730858800015,13002277500170
		//ou
		//siret 34040220500033 + formacode 32036, 12520, 13017
		//'15093','32036','12520','13017'

		/* Rémunération: */
		//Si ARE -> AREF. sinon, pas de rému région

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Enseignement supérieur (ESUP)";
			$array['step']="Prenez contact avec votre conseiller emploi (Mission locale, Cap emploi, Pôle emploi, CIDFF…) pour faire valider votre projet. Vous devrez ensuite effectuer votre inscription administrative auprès de l'Université.";
			$array['descinfo']="Les actions concernées sont subventionnées par la Région pour une durée de 1 an.<br/>Le coût pédagogique est gratuit pour le stagiaire, hors frais annexes à sa charge (droit d'inscription, frais de validation, transport, hébergement, restauration et matériels)<br/>Un accompagnement du stagiaire est assuré par l'école.";
			$array['info']="";
			$array['cost']="Formation totalement financée par la Région, hors frais annexes";
			if($situation_inscrit)
			{
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionAuvergneRhoneAlpes($training_locationpath))
						if(((hasStrings(array('DAEU','diplôme d\'accès aux études universitaires'),$ar['intitule']) || in_array($training_formacode,array('15093'))) && in_array($training_siret,array('19691775100014','19691774400019','19692437700019','19692437700191','19421095100423', '13002139700018','19730858800015','13002277500170')))
							||
							(in_array($training_formacode,array('32036','12520','13017')) && in_array($training_siret,array('34040220500033')))
						)
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara2'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_ara2',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_ara2',$droits);
						}
			}
		}
		

		/* Ligne 11 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE TH ou DE avec RSA

		/* Caractéristiques formation: */
		//F° en Rhône Alpes, durée < 12 mois ou 800h en centre (si indiquée), avec code certif info + éligible RFF ARA
		//hors F° avec Intitulé comportant "AFC"
		//hors code financeur "PE" collectif
		//code financeur "'Region"
		//code financeur "OPCA"
		//code financeur Etat
		//code financeur : coll terr, coll terr autre ...
		//code financeur "Agefiph"
		//hors niveau > ou = II
		//hors formacode Infirmier 43448
		//Aide soignant 43436
		//Aux de puériculture 43441
		//Auxiliaire Ambulancier et ambulancier : 31815
		//Santé secteur sanitaire 43454
		//DEAES : 44004
		//44028 - AUXILIAIRE VIE SOCIALE
		//Action sociale 44054,
		//vae 15064,44591
		//42050,42060,42061,42032 (coiffure-esthéstisme)
		//racine formacode 345 (commerce), 342 (commerce internat), 218 (cuir), 220,222,223,224 (BTP), 318 (transport), 154 (sport), 241,150 (dev persp),
		//certifinfo 54912 (ambulancier)
		//certifinfo 83899 (VAE)
		//certinfo 56072 (CAP petite enfance)
		//49616 (BAFA), 23710 (BAFD)
		//'43448','43436','43441','31815','43454','44004','44028','44054','15064','44591','42050','42060','42061','42032','54912','83899','56072','49616','23710'

		/* Rémunération: */
		//AREF sinon RPS

		if(1)
		{
			$array=array();
			$array['pri']="2";
			$array['title']="Actions projets individuels (API)";
			$array['step']="Prenez contact avec votre conseiller Pôle emploi pour vérifier la validité de votre projet et la possibilité de financement de la formation.<br/>Attention : le dossier complet doit être envoyé au plus tard 3 semaines avant le démarrage de la formation";
			$array['descinfo']="La Région apporte un financement total ou partiel d'une action de formation si celle-ci permet un retour immédiat à l'emploi.<br/>Possibilité d'avoir une aide aux frais d'hébergement ou de repas avec l'AFPA, dans le cadre d'une convention avec le Conseil Régional.<br/>Attention, certaines formations ne sont pas éligibles en API : vérifiez auprès de votre conseiller emploi... Si la Région ne couvre pas la totalité de la formation, il est possible de rechercher un cofinancement autre.";
			$array['info']="";
			$array['cost']="Formation partiellement ou totalement prise en charge par la Région. Des co-financements sont possibles";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionAuvergneRhoneAlpes($training_locationpath))
					{
						if(!hasStrings(array('AFC'),$ar['intitule']))
							if($training_niveausortie<CODENIVEAUSCOLAIRE_LICENCEMAITRISE)
								if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
								   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces)
								)
									if($training_dureeenmois<12)
										if($training_certifiante)
											if(in_array($training_racineformacode,array(215,223,224)))
												if(in_array($training_formacode,array(31754,31734,32663,32682,32667,31054,31034,31082,31088,31854,31011,31058,34554,34575,34056,34584,34592,34561,34588,34038,34593,34027,34507,34568,42752,21599,21589,21538,21539,21528,42776,42754,42757,42786,42797,42778,42788,42793,42756,42746,42766,43418,44026,44028,44079,42056,42093,42086,42083,42084,21854,21867,21869,21801,21871,21815,21888,21882,21860,21884,21893,21895,21838,21842,21850,21831,21823,21803,21812,21802,21814,21804,21886,21654,21627,21625,21617,21605,21607,21606,21674,21680,21692,21696,21651,21650,21634,21622,21611,21676,21631,21620,21754,21742,21736,22435,22415,22404,22408,22416,22406,22405,22313,22396,23637,23617,23607,23658,23649,23606,23605,23629,23609,23608,44067,44079,44089,21028,21030,21027,21046,21053,21041,21043,21040,21001,21056,21078,21086,21088,21085,21075,21097,21022,21025,22274,22294,22293,22657,00110,24354,24054,24066,24086,24095,24097,24096,24089,24099,24069,24047,24049,24039,24024,24016,23662,23661,23670,23650,23681,23680,23673,23652,23054,23048,23059,23058,23049,23070,23029,23039,23080,23062,23090,23081,23060,23071,23091,23074,23035,23028,23023,23002,23076,22867,22839,22849,22879,22885,22895,22898,22899,22889,22828,22818,22819,22827,22869,22896,22859,22865,24130,23021,23092,23012,23031,23011,23010,23001,23007)))
													if(in_array($training_codecertifinfo,array(80757,80758,85189,49806,31426,21740,77503, 77528,94681, 21338,82627,21154,21155,45554,21156,20646,58445,86083,98967,98037,83182,84261)))
														//if(condRff($var)) TODO : code ROME à revoir -> pb avec calcul remu RFF
														//{
															arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_ara'=>$array));
															if($allocation_type=='are')
																remuAREF($var,'finindividuel_ara',$droits);
															else
																remuRPS($var,'finindividuel_ara',$droits);
														//}
				}
		}

		/* Ligne 12 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//localisation ARA
		//Tout DE
		//ou salarié en contrat aidé ou emplois d’avenir

		/* Caractéristiques formation: */
		//F° en ARA, durée < 12 mois ou 800h en centre (si indiquée), avec code certif info
		//hors F° avec Intitulé comportant "AFC"
		//hors code financeur "PE" collectif
		//code financeur "'Region"
		//code financeur "OPCA"
		//code financeur Etat
		//code financeur : coll terr, coll terr autre ...
		//code financeur "Agefiph"
		//hors tag "contrat d'apprentissage" et hors Tag "contrat de professionnalisation"
		//hors ROME G1204 et L 1401
		//hors intitulé comprenant BP, Bac, Bac pro, bac techno, brevet professionnel, BPJEPS, BTS, BTSA, Bachelor, Capacité, CAP, DAEU, DEUST, DE, diplôme d'Etat, diplôme national, Diplôme d'accès aux études universitaires, ingénieur, Licence, Master, Mastère
		//hors formacode Infirmier 43448
		//Aide soignant 43436
		//Aux de puériculture 43441
		//Auxiliaire Ambulancier et ambulancier : 31815
		//Santé secteur sanitaire 43454
		//DEAES : 44004
		//44028 - AUXILIAIRE VIE SOCIALE
		//Action sociale 44054,
		//vae 15064,44591
		//certifinfo 83899, VAE
		//49616 (BAFA), 23710 (BAFD)
		//54912 (ambulancier)
		//'43448','43436','43441','31815','43454','44004','44028','44054','15064','44591','83899','49616','23710','54912'

		/* Rémunération: */
		//AREF. RPS

		if(1)
		{
			$array=array();
			$array['pri']="2";
			$array['title']="Contrat d'Aide et Retour à l'Emploi Durable (CARED)";
			$array['step']="Vous devez trouver un employeur, hors collectivités locales, administration ou association à but non lucratif, prêt à vous embaucher pour un contrat d'au moins 6 mois, à la suite de votre formation. Prenez contact avec votre conseiller Pôle emploi pour vérifier la validité de votre projet, la possibilité de financement de la formation et le montage du dossier.<br/>Attention : le dossier complet doit être envoyé au plus tard 3 semaines avant le démarrage de la formation.";
			$array['descinfo']="Financement total ou partiel du conseil régional concernant certaines formations à condition que la formation débouche sur un recrutement en CDI ou en CDD ou mission d'au moins 6 mois (interim 6 mois suivi de CDI, saisonnier).<br/>Possibilité de bénéficier d'une aide aux frais d'hébergement ou de repas par la Région.";
			$array['info']="";
			$array['cost']="Formation partiellement ou totalement prise en charge par la Région. Des co-financements sont possibles.";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionAuvergneRhoneAlpes($training_locationpath))
						//if($situation_personnesortantcontrataide)
						if(!hasStrings(explode(', ',"AFC, BP, BAC, BAC PRO, BAC TECHNO, BREVET PROFESSIONNEL, BPJEPS, BTS, BTSA, BACHELOR, CAPACITÉ, CAP, DAEU, DEUST, DE, DIPLÔME D'ETAT, DIPLÔME NATIONAL, DIPLÔME D'ACCÈS AUX ÉTUDES UNIVERSITAIRES, INGÉNIEUR, LICENCE, MASTER, MASTÈRE"),$ar['intitule']))
						{
							if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
							   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces)
							)
								if($training_dureeenmois<12)
									if(!$training_contratprofessionalisation && !$training_contratapprentissage)
										if(!in_array($training_racineformacode,array(150)))
											if(!in_array($training_formacode,array(13250,31802,31805,31847,31827,31833,43409,43436,43339,43441,43448,43454,44004,44028,44054,44591,44591,43448,43436,43441,31815,43454,44004,44028,44054)))
												if(!in_array($training_codecertifinfo,array(54912,84385,87185,87187,87189,83899)))
													if($training_codecertifinfo)
													{
														arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_ara'=>$array));
														if($allocation_type=='are')
															remuAREF($var,'finindividuel_ara',$droits);
														else
															remuRPS($var,'finindividuel_ara',$droits);
													}
						}
		}

		/* Ligne 13 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//Tout DE + localisation ARA
		//ou salarié en contrat aidé ou emplois d’avenir

		/* Caractéristiques formation: */
		//F° avec intitulé CARED + localisation ARA

		/* Rémunération: */
		//AREF RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Contrat d’Aide et Retour à l’Emploi Durable collectifs";
			$array['step']="Contactez votre conseiller Pôle emploi pour valider votre projet. Il transmettra votre demande d'inscription à la Région";
			$array['descinfo']="Financement par le Conseil régional d'une formation proposée pour répondre à un projet de recrutement d'au moins 6 mois. Le besoin de recrutement et de formation a été préalablement exprimé par un employeur spécifique ou une branche professionnelle dans la région. L'organisme de formation vous accompagne dans le cadre de ce projet de recrutement.<br/>Possibilité de bénéficier d'une aide aux frais d'hébergement ou de repas par la Région.";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionAuvergneRhoneAlpes($training_locationpath))
						if(hasStrings(array('CARED'),$ar['intitule']))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_ara',$droits);
							else
								remuRPS($var,'actioncollectiveregion_ara',$droits);
						}
		}

		/* Ligne 14 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//Tout DE + résident en Rhône Alpes

		/* Caractéristiques formation: */
		//F°code financeur Région + localisation Rhône Alpes +intitulé comprend PCP ou segment ou étape ou Exploration du projet professionnel, Elaboration du projet professionnel, Stabilisation du projet professionnel
		//ou formacode 15061, 15062,15084,15235
		//'15061','15062','15084','15235'

		/* Rémunération: */
		//AREF. si pas d'ARE : &laquo;&nbsp;se renseigner auprès de votre conseiller sur les conditions d'obtention d'une rémunération par la Région&nbsp;&raquo;

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programmation Compétences Premières (PCP)";
			$array['step']="Contactez votre conseiller Pôle emploi pour faire valider votre projet et faire la prescription auprès de la Région";
			$array['descinfo']="Programme du Conseil régional proposant trois cursus de formation<br/>1) Compétences premières et insertion socio-professionnelle - lutte contre l'illétrisme<br/>2) Compétences premières et construction de projet professionnel<br/>3) Compétences premières et préparation à la qualification et à l’emploi<br/>Certains parcours peuvent intégrer du français langue étrangère";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région";
			if($situation_inscrit)
				if(isInRegionExRhoneAlpes($domicilePath))
					if(isInRegionExRhoneAlpes($training_locationpath))
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(in_array($training_formacode,array('15061','15062','15084','15235')) ||
							   hasStrings(array('PCP','SEGMENT','ETAPE','EXPLORATION DU PROJET PROFESSIONNEL','ELABORATION DU PROJET PROFESSIONNEL','STABILISATION DU PROJET PROFESSIONNEL'),$ar['intitule']))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara2'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_ara2',$droits);
								else
									remuTEXT($var,'actioncollectiveregion_ara2',$droits,"Se renseigner auprès de votre conseiller sur les conditions de rémunération par la Région.");
							}
		}

		/* Ligne 15 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//Tout demandeur d'emploi

		/* Caractéristiques formation: */
		//F° code financeur Région + localisation Auvergne-Rhône Alpes hors formacode 12520,13017, 15031,15041, 15061,15062, 15093,31815, 32036, 43436, 43441, 43448, 43454, 44004, 44028, 44054, 15061, 15062,15084,15235, 15064,44591
		//VAE certifinfo 83899
		//'12520','13017','15031','15041','15061','15062','15093','31815','32036','43436','43441','43448','43454','44004','44028','44054','15061','15062','15084','15235','15064','44591','83899'

		/* Rémunération: */
		//... AREF ou RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme régional qualifiant";
			$array['step']="Contactez votre conseiller Emploi (Pôle emploi, Mission locale, Cap emploi, CIDFF) pour valider votre projet et transmettre votre candidature à l'organisme de formation. Celui-ci reprendra contact avec vous pour que vous assistiez à une séance d’information collective et/ou à un entretien. Si besoin, vous passerez des tests de sélection.";
			$array['descinfo']="Il s'agit de formations financées par la Région, parfois en cofinancement avec d'autres acteurs de la formation (Pôle emploi, Agefiph, PLIE, Etat, Conseil général, OPCA…)";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_formacode,array('12520','13017','15031','15041','15061','15062','15093','31815','32036','43436','43441','43448','43454','44004','44028','44054','15061','15062','15084','15235','15064','44591')))
							if(!in_array($training_codecertifinfo,array('83899')))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara3'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_ara3',$droits);
								else
									remuRPS($var,'actioncollectiveregion_ara3',$droits);
							}
		}

		/* Ligne 16 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//tout DE résidant en Auvergne

		/* Caractéristiques formation: */
		//F° en Auvergne, hors tt code financeur Région, coll terr, hors code financeur Pôle emploi, hors code financeur Etat,OPCA hors intitulé comprenant BTS, Bachelor, Capacité, DAEU, DEUST, Diplôme d'accès aux études universitaires, ingénieur, Licence, Master, Mastère; hors ntitulé comprend "atelier sectoriel ou " atelier multisectoriel"
		//hors formacode 15061; 15062, 15041, 15064,44591
		//ou certifinfo 83899
		//hos Infirmier 43448
		//Aide soignant 43436
		//Aux de puériculture 43441
		//Auxiliaire Ambulancier et ambulancier : 31815
		//Santé secteur sanitaire 43454
		//DEAES : 44004
		//44028 - AUXILIAIRE VIE SOCIALE
		//Action sociale 44054,
		//hors certifinfo ambulancier : 54912
		//'15061','15062','15041','15064','44591','83899','43448','43436','43441','31815','43454','44004','44028','44054','54912'

		/* Rémunération: */
		//AREF ou RPS

		if(0)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Aides individuelles à la formation professionnelle (AIFP)";
			$array['step']="Prenez contact avec votre conseiller Pôle emploi,votre conseiller emploi mission locale, Cap emploi ou CIDFF pour valider votre projet et vérifier la possibilité de monter le dossier.";
			$array['descinfo']="Dispositif d'aide individuelle financée par le Conseil régional.<br/>La formation doit permettre une insertion rapide à l’emploi en réelle adéquation avec les besoins de l’économie et du territoire. Il peut s’agir :<br/>- d’une formation qui amène à une qualification reconnue sur le marché du travail et pour laquelle les flux de personnes formées ne sont pas déjà supérieurs à la capacité d’absorption de l’économie<br/>- d’une formation liée à un projet de création d’entreprise, formation technique spécifique au projet<br/>Cela peut être des formations d’adaptation à l’emploi, des formations conduisant à une certification professionnelle ou des parcours Validation des Acquis de l’Expérience.";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région";
			if($situation_inscrit)
				if(isInRegionExAuvergne($domicilePath))
					if(isInRegionExAuvergne($training_locationpath))
					{
						if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_AGEFIPH,$nbPlaces)
						)
							if(!in_array($training_formacode,array('15061','15062','15041','15064','44591')))
								if(!in_array($training_codecertifinfo,array('83899','43448','43436','43441','31815','43454','44004','44028','44054','54912')))
									if(!hasStrings(explode(', ',"BP, BAC, BREVET PROFESSIONNEL, BPJEPS, BTS, BTSA, BACHELOR, CAPACITÉ, CAP, DAEU, DEUST, DE, DIPLÔME D'ETAT, DIPLÔME NATIONAL, DIPLÔME D'ACCÈS AUX ÉTUDES UNIVERSITAIRES, INGÉNIEUR, LICENCE, MASTER, MASTÈRE, ATELIER SECTORIEL, ATELIER MULTISECTORIEL"),$ar['intitule']))
									{
										arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_ara2'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'finindividuel_ara2',$droits);
										else
											remuRPS($var,'finindividuel_ara2',$droits);
									}
					}
		}

		/* Ligne 17 (9 Dispositifs de la Région) */
		/* Caractéristiques DE: */
		//Tout DE en Auvergne, hors créateur d'entreprise

		/* Caractéristiques formation: */
		//F° en Auvergne hors tt code financeur coll
		//pour un contrat de travail d'au minimum 6 mois. s'il s'agit d'un contrat saisonnier de minimum 2 mois et à la condition que le contrat soit 2 fois supérieur à la durée de la formation.

		/* Rémunération: */
		//Dispositif financé par le Conseil Régional, sur le territoire ex-Auvergne... La formation doit permettre une insertion rapide à l’emploi en réelle adéquation avec les besoins de l’économie et du territoire. Il peut s’agir :. - d’une formation liée à l’obtention d’une promesse d’embauche (CARED Individuel) pour un contrat de travail d'au minimum 6 mois.ou pour un contrat saisonnier d'au moins 2 mois et à la condition que le contrat soit 2 fois supérieur à la durée de la formation... Cela peut être des formations d’adaptation à l’emploi, des formations conduisant à une certification professionnelle ou des parcours de Validation des Acquis de l’Expérience.

		if(0)
		{
			$array=array();
			$array['pri']="";
			$array['title']="CARED individuel (ex-Auvergne)";
			$array['step']="Promesse d'embauche obligatoire<br/>Une fois que vous avez trouvé un employeur susceptible de vous embaucher après la formation, si vous etes demandeur d'emploi, prenez contact avec votre conseiller Pôle emploi pour valider votre projet et vérifier la possibilité de monter le dossier.<br/>Sinon, prenez contact avec votre conseiller emploi mission locale, Cap emploi ou CIDFF";
			$array['descinfo']="dispositif financé par le Conseil Régional, sur le territoire ex-Auvergne.<br/>La formation doit permettre une insertion rapide à l’emploi en réelle adéquation avec les besoins de l’économie et du territoire. Il peut s’agir :<br/>- d’une formation liée à l’obtention d’une promesse d’embauche (CARED Individuel) pour un contrat de travail d'au minimum 6 mois. s'il s'agit d'un contrat saisonnier de minimum 2 mois et à la condition que le contrat soit 2 fois suérieur à la durée de la formation.<br/>Cela peut être des formations d’adaptation à l’emploi, des formations conduisant à une certification professionnelle ou des parcours Validation des Acquis de l’Expérience.";
			$array['info']="";
			$array['cost']="Promesse d'embauche obligatoire<br/>Une fois que vous avez trouvé un employeur susceptible de vous embaucher après la formation, si vous etes demandeur d'emploi, prenez contact avec votre conseiller Pôle emploi pour valider votre projet et vérifier la possibilité de monter le dossier.<br/>Sinon, prenez contact avec votre conseiller emploi mission locale, Cap emploi ou CIDFF";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
				{
					if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
					   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_ara3'=>$array));
						remuTEXT($var,'finindividuel_ara3',$droits,"Dispositif financé par le Conseil Régional, sur le territoire ex-Auvergne... La formation doit permettre une insertion rapide à l’emploi en réelle adéquation avec les besoins de l’économie et du territoire. Il peut s’agir :. - d’une formation liée à l’obtention d’une promesse d’embauche (CARED Individuel) pour un contrat de travail d'au minimum 6 mois.ou pour un contrat saisonnier d'au moins 2 mois et à la condition que le contrat soit 2 fois supérieur à la durée de la formation... Cela peut être des formations d’adaptation à l’emploi, des formations conduisant à une certification professionnelle ou des parcours de Validation des Acquis de l’Expérience.");
					}
				}
		}

		/* Ligne 18 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE, < niveau IV, localisé en ARA,

		/* Caractéristiques formation: */
		//F° code financeur région + Auvergne + intitulé comprend "atelier sectoriel ou " atelier multisectoriel", ou formacode 15031, 15061; 15062, 15041
		//'15031','15061','15062','15041'

		/* Rémunération: */
		//AREF ou RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Projets collectifs régionaux";
			$array['step']="Prenez contact avec votre conseiller Pôle emploi ou mission locale, Cap emploi ou CIDFF pour valider votre projet et monter le dossier.";
			$array['descinfo']="Formations financées par la Région et concernant plusieurs dispositifs possibles<br/>1) remise à niveau (préparer un examen ou un concours, accéder à une formation professionnalisante et/ou certifiante, préparer une reconversion professionnelle, réussir la validation des acquis de l’expérience.)<br/>2) parcours préparatoire multi sectoriel Découverte des métiers : Orientation Construction Validation du projet professionnel; préqualification avant formation; préparation à l'emploi avant embauche";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionExAuvergne($training_locationpath))
						if($niveauscolaire<CODENIVEAUSCOLAIRE_BAC)
						{
							if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
								if(hasStrings(explode(', ',"ATELIER SECTORIEL, ATELIER MULTISECTORIEL"),$ar['intitule']) ||
								   in_array($training_formacode,array('15031','15061','15062','15041')))
								{
									arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara4'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'actioncollectiveregion_ara4',$droits);
									else
										remuRPS($var,'actioncollectiveregion_ara4',$droits);
								}
						}
		}

		/* Ligne 19 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE France entière

		/* Caractéristiques formation: */
		//F° en ex-Auvergne  + code financeur région + formacode 43092, 43436, 43441, 43448, 44004, 44028, 31815 (que en Auvergne)
		//ou
		//code financeur région
		//certifinfo 54913 (Aide soignant) + Siret 50264547600016 (MFR Annecy le Vieux) + SIRET 26011019200085 (Hauteville) + SIRET 26420396900094 -St Chamond) + SIRET 38392563300059 (Pôle formation santé Lyon) +77567227232200 ( Croix Rouge) + lieux : Valence, Lyon, Saint Etienne, Grenoble) + SIRET 39388611400023 (lycée les 3 vallées - Thonon) + SIRET 26740009100059 (Rumilly) + SIRET 30293883200045 (ESSE Lyon) +SIRET 20003493200018 (MtBrisson) + SIRET 26420030400808 (CH St ETienne) + SIRET 26690027300183 (HCL Esquirol) + SIRET 26010004500012 (CH Fleyriat) + SIRET 77992578300010 (Rockfeller) + SIRET 26741108000018 (Sallanches) + SIRET 43997564001218 (aveize) + SIRET 26261109800068 (St Vallier) + SIRET 20001138500057 (Aubenas) + SIRET 26420027000215 (ROannes) + SIRET 26011021800146 (oyonnax) + SIRET 26690023200064 (Tarare) + SIRET 26690025700020 (Villefranche) + SIRET 42462022700159 (Francheville) + SIRET 26380032800035 (Vienne) + SIRET 26731109000109 (moutiers) + Numéro SIRET 19730016300049 (GRETA Savoie) + lieu Bassens et Saint Jean de Maurienne + SIRET 26260006700256 (Montélimar) + SIRET 77563330800199 (Privas) + SIRET 26070001800021 (Annonay) + SIRET 26740002600071 (Annecy Genevois) + SIRET 26730007700018 (Chambéry) + SIRET 26380030200014 (Grenoble) + SIRET 26740084400093 (Ambilly) + SIRET 26741103100045 (Thonon les bains) +siret 26380021100017 (St Egrève) + siret 26380006200238 (Bourgoin Jallieu) + siret 26380026000014 (St Marcellin) + siret 77639526100014 (Lycée La Salésienne) + 77988345300010 (Don Bosco)+ siret 26690018200095 (Neuville Fontaine) + siret 39055507600012 (ST jo St Luc) + siret SIRET 26690027300449 (HCL Clémenceau) + SIRET 26630783400058 (Ambert) + SIRET 26630785900139 (Thiers) + SIRET 26030026400066 (Vichy) + SIRET 26430284500070 (le Puy en Velay) + SIRET 26150013600013 + siret SIRET 26150013600104 (st Flour) + SIRET 26150005200012 + SIRET 26150005200061 (Mauriac) + SIRET 26630746100225 (CH Clermont Fer) + SIRET 26150284300038 (Aurillac) + SIRET 26030017300051(Montluçon) + SIRET 77567227221088 (moulins) + 77909358200012 (maurs)
		//certifinfo 54917 o+ SIRET 26630746100324 (clermont ferrand) + SIRET 77639533700020 (St Etienne) + SIRET 30293883200045 (ESSE) + SIRET 77937827200016 ( Saint Sorlin) + SIRET 77567227232200 + lieu Grenoble (Croix rouge) + SIRET 32642100500017 (thône) + SIRET 19730016300049 (GRETA Savoie) + lieu Bassens + SIRET 42462022700159 (Francheville) + SIRET 77992578300010 (Rockfeller) + SIRET 26380030200014 (CHU Grenoble) + SIRET 30296050500030 (lycée Jeanne Antide) + SIRET 52182439100036 (IFAPtitude)
		//certifinfo 54912 + siret 26630746100019 (CHU Clermont Ferrand) + SIRET 26630746100290 (IFA clermont)
		//certifinfo 87187 ou formacode 44004 + siret 48121631500046 (ARFRIPS Valence) + siret 77567227232200 + lieux St Etienne( Croix Rouge) +siret 77937827200016 (LEA St Sorlin) + siret 77563330800199 (Privas) + SIRET 19380081000031 (GRETA bourgoin) + SIRET 19260765300032 (Nyons) + SIRET 38881118400026 (Tournon) + SIRET 19070004700052 (GRETA Aubenas) + SIRET 39269401400086 (Annecy) + SIRET 19730016300049 (GRETA Savoie) et lieu Bassens + SIRET 30293883200045 (ESSE) et lieux Lyon, SIRET 30293883200052 (Esse Valence) + SIRET 19382274900019 (lycée Dolto -Fontanil) + SIRET 19740009600024 et lieu Annemasse (GRETA Lac) + SIRET 19740013800032 (GRETA Arve ) et lieu Bonneville + SIRET 44138635600022 (Echirolle) + SIRET 30488998300017 (MFR La Catie) + SIRET 30870164800047 (st Chamond) + SIRET 19380033100020 (Greta Grenoble) et lieu Voiron + SIRET 39269401400060 (la Ravoire) + SIRET 77931160400028 (ADEA Bourg) + SIRET 77988347900023 (Ecully) + SIRET 77630753000027(Feurs) + SIRET 19382274900019 (Fontanil)
		//DEAES formacode 44028 ou certifinfo 87185 - domicileSIRET 20004632400022 (GRETA Viva 5 - Crest) + SIRET 77992578300010 (Rockfeller)+ SIRET 19380081000031 (GRETA bourgoin) + SIRET 30293883200045 (ESSE) + lieu Lyon ou lieu Villefranche/Saône, SIRET 30293883200052 (Valence) + SIRET 38881118400026 (tournon) + SIRET 19070004700052 (greta Aubenas) et lieu Aubenas, Montélimar + siret 77563330800199 (Privas) + +siret 77937827200016 (LEA St Sorlin) + SIRET 39269401400086 (Annecy) + SIRET 19730016300049 (GRETA Savoie) et lieu Bassens + SIRET 19740009600024 et lieux Thonon les Bains et Annemasse(Greta Lac) + SIRET 19740013800032 (GRETA Arve ) et lieu Bonneville + SIRET 44138635600022 (Echirolle) + SIRET 30488998300017 (MFR La Catie) + SIRET 19380033100020 (Greta Grenoble) et lieu Voiron + SIRET 77566657100085 (ADMR Adyfor) + SIRET 39269401400060 (La Ravoire) + SIRET 77931160400028 (ADEA Bourg) + SIRET 19010016400028 et lieu Nantua (GRETA Ain) + SIRET 77630753000027 (Feurs) + SIRET 75079623700022 (CARREL) + siret 77937827200016 (St Sorlin)
		//'43092','43436','43441','43448','44004','44028','31815','54913','54917','54912','87187','44004','44028','87185'

		/* Rémunération: */
		//AREF sinon &laquo;&nbsp;Possibilité de faire une demande de bourse auprès de la Région&nbsp;&raquo;

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme régional sanitaire et social";
			$array['step']="Si vous êtes suivi par un conseiller emploi, prenez contact avec lui (Pôle emploi, Mission Locale, Cap emploi) pour valider votre projet.<br/>La demande de financement de la formation (et de la subvention si vous y êtes éligible) se fait sur le site régional www.aidesfss.auvergnerhonealpes.fr";
			$array['descinfo']="Le coût pédagogique des formations du domaine &laquo;&nbsp;Sanitaire et social&nbsp;&raquo; est pris en charge par la Région lorsqu'elles ont lieu dans l'un des instituts et/ou écoles agréés par la Région et situés sur le territoire Auvergne Rhône Alpes pour les formations d'aide-soignant, d'auxiliaire de puériculture ou, en Auvergne, d'ambulancier. Certaines autres formations sanitaires et sociales peuvent être financées par la Région. Renseignez-vous.";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région";
			if($situation_inscrit)
			{
				$ok=false;
				if(isInRegionExAuvergne($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(in_array($training_formacode,array('43092','43436','43441','43448','44004','44028','31815')))
							$ok=true;
				if(!$ok)
					if((in_array($training_codecertifinfo,array('54913')) && in_array($training_siret,array('50264547600016','26011019200085','26420396900094','38392563300059','77567227232200','39388611400023','26740009100059','30293883200045','20003493200018','26420030400808','26690027300183','26010004500012','77992578300010','26741108000018','43997564001218','26261109800068','20001138500057','26420027000215','26011021800146','26690023200064','26690025700020','42462022700159','26380032800035','26731109000109','19730016300049','26260006700256','77563330800199','26070001800021','26740002600071','26730007700018','26380030200014','26740084400093','26741103100045','26380021100017','26380006200238','26380026000014','77639526100014','77988345300010','26690018200095','39055507600012','26690027300449','26630783400058','26630785900139','26030026400066','26430284500070','26150013600013','26150013600104','26150005200012','26150005200061','26630746100225','26150284300038','26030017300051','77567227221088','77909358200012'))) ||
					   (in_array($training_codecertifinfo,array('54917')) && in_array($training_siret,array('26630746100324','77639533700020','30293883200045','77937827200016','77567227232200','32642100500017','19730016300049','42462022700159','77992578300010','26380030200014','30296050500030','52182439100036'))) ||
					   (in_array($training_codecertifinfo,array('54912')) && in_array($training_siret,array('26630746100019','26630746100290'))) ||
					   ((in_array($training_formacode,array('44004')) || in_array($training_codecertifinfo,array('87187'))) && in_array($training_siret,array('48121631500046','77567227232200','77937827200016','77563330800199','19380081000031','19260765300032','38881118400026','19070004700052','39269401400086','19730016300049','30293883200045','30293883200052','19382274900019','19740009600024','19740013800032','44138635600022','30488998300017','30870164800047','19380033100020','39269401400060','77931160400028','77988347900023','77630753000027','19382274900019'))) ||
					   ((in_array($training_formacode,array('44028')) || in_array($training_codecertifinfo,array('87185'))) && in_array($training_siret,array('20004632400022','77992578300010','19380081000031','30293883200045','30293883200052','38881118400026','19070004700052','77563330800199','77937827200016','39269401400086','19730016300049','19740009600024','19740013800032','44138635600022','30488998300017','19380033100020','77566657100085','39269401400060','77931160400028','19010016400028','77630753000027','75079623700022','77937827200016')))
					)
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara5'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_ara5',$droits);
						else
							remuTEXT($var,'actioncollectiveregion_ara5',$droits,'Possibilité de faire une demande de bourse auprès de la Région.');
					}
			}
		}

		/* Ligne 20 (AUVERGNE RHONE ALPES) */
		/* Caractéristiques DE: */
		//DE ARA

		/* Caractéristiques formation: */
		//formacode 31815 + localisation Rhône Alpes
		//'31815'

		/* Rémunération: */
		//AREF - RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme régional sanitaire et social - ambulancier";
			$array['step']="Si vous êtes suivi par un conseiller emploi, prenez contact avec lui (Pôle emploi, Mission Locale, Cap emploi) pour valider votre projet.";
			$array['descinfo']="En ex-Rhône-Alpes, la formation d'ambulancier est finançable via des Contrat d’Aide et Retour à l’Emploi Durable collectifs (CARED) et/ou des Préparations Opérationnelles à l'emploi collectives (POEC).";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge par la Région et par l'OPCA, dans le cadre d'un CARED ou d'une POEC";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(isInRegionExRhoneAlpes($training_locationpath))
					{
						if(in_array($training_formacode,array('31815')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_ara6'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_ara6',$droits);
							else
								remuRPS($var,'actioncollectiveregion_ara6',$droits);
						}
					}
		}

		/* Ligne 21 (ARA) */
		/* Caractéristiques DE: */
		//DE Région

		/* Caractéristiques formation: */
		//formacode 15064,44591 ou certifinfo 83899
		//'15064','44591','83899'

		/* Rémunération: */
		//AREF - pas de RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Aide à la VAE";
			$array['step']="Prendre contact avec votre conseiller Pôle emploi";
			$array['descinfo']="Financement par Pôle emploi d'un accompagnement pour constituer un dossier VAE et préparer l'entretien de validation";
			$array['info']="";
			$array['cost']="Formation totalement ou partiellement financés dans la limite de 1400€";
			$array['cost-plafond']="1400";
			if($situation_inscrit)
				if(isInRegionAuvergneRhoneAlpes($domicilePath))
					if(in_array($training_formacode,array('15064','44591')) || in_array($training_codecertifinfo,array('83899')))
					{
						arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_ara4'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'finindividuel_ara4',$droits);
						else
							remuTEXT($var,'finindividuel_ara4',$droits);
					}
		}
	}
?>
