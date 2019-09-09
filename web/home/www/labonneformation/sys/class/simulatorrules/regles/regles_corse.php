<?php

	function reglesCorse($var,&$droits,&$display)
	{
		extract($var);
		/* Ligne 2 (Corse) */
		/* Caractéristiques DE: */
		//DE domicilié en Corse

		/* Caractéristiques formation: */
		//"pas d'AIF :
		//pr Intitulé comportant ""AFC""
		//pr Intitulé comportant ""AFPA"" + localisation en Corse
		//pr Intitulé comportant ""PRF"" + localisation Corse
		//si code financeur ""PE"" collectif
		//si code financeur 'Region"" collectif,
		//si code financeur Etat, coll terr, Agefiph, hors code financeur OPCA si il n'y a aucun code financeur individuel associé (code 10 "bénéficiaire de l'action, code 5 "entreprise", code 17 "OPACIF", code O "autres financeurs") ...
		//pas d'aif si :
		//Formacode 15081
		//Formacodes 42030 + 42034
		//Formacodes 31812 + 31811
		//Formacodes 43445 + 43448 + 43433 + 43438 +14456
		//Formacodes 43409 + 44002+15084 +15093
		//Certifinfo 87185;87187; 87189
		//pas d'AIF si :
		//Nombre heures en entreprise supérieur à 30% du nbre heures total de la formation
		//pas AIF avec intensité hebdomadaire < 30h
		//'15081','42030','42034','31812','31811','43445','43448','43433','43438','14456','43409','44002','15084','15093','87185','87187','87189'

		/* Rémunération: */
		//AREF-RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="AIF";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Votre projet de formation et son financement doivent être validés au plus tard 2 semaines avant le début de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation lorsque les autres dispositifs individuels ou collectifs ne peuvent répondre.<br/>L’AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi.<br/>Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiiera aussi si les conditions du financement sont réunies.\"";
			$array['info']="";
			$array['cost']="Formation totalement ou partiellement financée";
			if($situation_inscrit)
				if(isInRegionCorse($domicileRegionPath))
				{
					unset($droits['aif']);
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
						if(in_array($training_formacode,array('15081','42030','42034','31812','31811','43445','43448','43433','43438','14456','43409','44002','15084','15093')))
							if(in_array($training_codecertifinfo,array('87185','87187','87189')))
								if(!(hasKeywords(array('AFC'),strtoupper($ar['intitule'])))) /* S'il y a au moins AFC ou AFPA ou PRF dans le titre */
									if(!(hasKeywords(array('AFPA','PRF'),strtoupper($ar['intitule'])) && isInRegionCorse($trainingRegionPath))) /* S'il y a au moins AFC ou AFPA ou PRF dans le titre */
										if(!($training_nbheuresentreprise>(30*$training_nbheurestotales/100)
									       || ($training_intensitehebdomadaire!==false && $training_intensitehebdomadaire<30)))
										{
											$display['aif']=$array;
											if($allocation_type=='are')
												remuAREF($var,'aif',$droits);
											else
												remuRFPE2($var,'aif',$droits);
										}
				}
		}

		/* Ligne 3 (Corse) */
		/* Caractéristiques DE: */
		//DE

		/* Caractéristiques formation: */
		//si code financeur 'Region" collectif et localisation Corse

		/* Rémunération: */
		//AREF - RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Action Collective financée par la Collectivité territoriale de Corse";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour partager avec vous la pertinence de ce projet de formation en lien avec le marché du travail .";
			$array['descinfo']="La Collectivité territoriale de Corse finance des actions de formation qualifiantes, permettant d'obtenir un titre ou un diplôme et des actions de pré-qualification et d'insertion.";
			$array['info']="";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionCorse($trainingRegionPath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_corse'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_corse',$droits);
						else
							remuRPS($var,'actioncollectiveregion_corse',$droits);
					}
		}

		/* Ligne 4 (Corse) */
		/* Caractéristiques DE: */
		//DE, Corse

		/* Caractéristiques formation: */
		//certifinfo 87185,87187,87189 + localisation en Corse
		//'87185','87187','87189'

		/* Rémunération: */
		//Se renseigner auprès de votre conseiller

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme sanitaire et social";
			$array['step']="Prenez contact avec votre conseiller emploi (Pôle emploi, Mission Locale, Cap emploi)";
			$array['descinfo']="La Collectivité territoriale de Corse finance des actions de formation qualifiantes dans le domaine sanitaire et social. Les formations menant au DEAES sont progressivement intégrées au programme régional. Se renseigner auprès de votre conseiller.";
			$array['info']="";
			$array['cost']="Se renseigner auprès de votre conseiller";
			if($situation_inscrit)
				if(isInRegionCorse($domicileRegionPath))
					if(isInRegionCorse($trainingRegionPath))
						if(in_array($training_codecertifinfo,array('87185','87187','87189')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_corse2'=>$array));
							remuTEXT($var,'actioncollectiveregion_corse2',$droits,"Se renseigner auprès de votre conseiller");
						}
		}
	}

	//A corriger au niveau des tableaux $display et pacer les inserAfter...
	
	/* Règles Corse  ******************************************************************************************************/
	//function reglesCorse($var,&$droits,&$display)
	//{
	//	extract($var);

	//	if(isInRegionCorse($domicilePath))
	//	{
	//		/* Modification des différent descriptifs par défaut, spécifiques à la corse */
	//		$display['aif']['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation lorsque les autres dispositifs individuels ou collectifs ne peuvent répondre.<br/>L'AIF est réservée à des projets de formation dont la pertinence est partagée par le conseiller référent Pôle emploi. Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail. Il vérifiera aussi si les conditions du financement sont réunies.";
	//		$display['aif']['step']=array("<span class=\"highlight\">Contacter votre conseiller</span> référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>
	//		                               Votre projet de formation et son financement doivent être validés au plus tard 2 semaines avant le début de la formation.");
	//		$display['aif']['cost']='Formation totalement ou partiellement financée';

	//		//$display['poleemploicollectif']['title']="Action collective financée par Pôle emploi",
	//		$display['poleemploicollectif']['step']=array("Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) ou l'organisme de formation.");
	//		//$display['poleemploicollectif']['cost']="Formation totalement financée",
	//		$display['poleemploicollectif']['descinfo']="Cette action de formation est financée par <a href=\"http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321\" target=\"_blank\">Pôle emploi</a>.";

	//		/* Pour la corse, ajout dans la liste le financement bilan de compétences */
	//		$display['aifbilancompetences']=$display['aif'];
	//		$display['aifbilancompetences']['title']="AIF Bilan de compétences";

	//		unset($droits['aif']); /* Pour la région centre, on doit recalculer spécifiquement l'AIF */
	//		/* Ligne 4 : pas d'AIF si... */
	//		if(!(hasKeywords(array('AFC','AFPA','PRF'),strtoupper($ar['intitule'])) /* S'il y a au moins AFC ou AFPA ou PRF dans le titre */
	//		   || hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces)
	//		   || hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces)
	//		   || in_array($training_formacode,array('42030','42034','31812','31811','43445','43433','43438','14456','43409','44002','15084','15093'))
	//		   || $training_nbheuresentreprise>(30*$training_nbheurestotales/100)
	//		   || ($training_intensitehebdomadaire!==false && $training_intensitehebdomadaire<30)
	//		   || $training_nbheurestotales>400
	//		   || ($training_datebegin && $training_dateend && Tools::calcDiffDate($training_dateend,$training_datebegin)<12)
	//		   )
	//		)
	//			if(!$training_contratprofessionalisation && !$training_contratapprentissage)
	//			{
	//				financementAif($var,$droits);
	//			}
	//		/* Ligne 5 : Ajout du financement AIF bilan de compétences */
	//		if(in_array($training_formacode,array('15081')))
	//		{
	//			financementAifBilanDeCompetences($var,$droits); /* Dégager rfpe et supprimer le test aref miniman si are */
	//		}
	//		/* Ligne 6 */
	//		if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
	//			if($situation_creditheurescpfconnu && $situation_creditheurescpf>=$training_nbheurestotales)
	//				if(!$training_contratprofessionalisation && !$training_contratapprentissage)
	//				{
	//					$display['aif']['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui peut permettre le financement d'actions de formation éligibles au CPF. Le conseiller vérifiera si les conditions de mobilisation sont réunies.";
	//					$display['aif']['step']=array("Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>
	//					                               Votre dossier doit être validé au plus tard 2 semaines avant le début de la formation.");
	//					$display['aif']['cost']='Formation totalement financée';

	//					financementAif($var,$droits,array('rfpe')); /* Dégager le rfpe */
	//				}
	//	}
	//	/* Ligne 7 : Juste le wording qui change : le reste a été calculé dans les calculs nationaux */
	//	//unset($droits['poleemploicollectif']);
	//	if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
	//	{
	//		$display['poleemploicollectif']['title']="Action Collective financée par Pôle emploi.";
	//		$display['poleemploicollectif']['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) ou l'organisme de formation.";
	//		$display['poleemploicollectif']['descinfo']="Cette action de formation est financée par <a href=\"http://www.pole-emploi.fr/candidat/mes-aides-financieres-@/index.jspz?id=77321\" target=\"_blank\">Pôle emploi</a>.";
	//		//codeFinanceur('CAS_1',CODEFINANCEUR_POLE_EMPLOI,'poleemploicollectif',$droits,$var);
	//		remuCodeFinanceur($var,'poleemploicollectif',$droits);
	//	}
	//	/* Ligne 8 : Juste le wording qui change : le reste a été calculé dans les calculs nationaux */
	//	if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
	//	{
	//		$display['actioncollectiveregion']['title']="Action Collective financée par la Collectivité territoriale de Corse";
	//		$display['actioncollectiveregion']['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour partager avec vous la pertinence de ce projet de formation en lien avec le marché du travail.";
	//		$display['actioncollectiveregion']['descinfo']="La Collectivité territoriale de Corse finance des actions de formation qualifiante, permettant d'obtenir un titre ou un diplome, des actions de pré-qualification et d'insertion.";
	//		$display['actioncollectiveregion']['cost']="Formation totalement financée";
	//		remuCodeFinanceur($var,'actioncollectiveregion',$droits); //100%
	//	}
	//}
?>
