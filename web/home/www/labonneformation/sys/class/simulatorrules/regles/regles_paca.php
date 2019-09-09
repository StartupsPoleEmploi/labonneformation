<?php
	/* Règles PACA ********************************************************************************************************/
	function reglesPaca($var,&$droits,&$display)
	{
		extract($var);

		/* Ligne 3 (PACA) */
		/* Caractéristiques DE: */
		//Tout DE

		/* Caractéristiques formation: */
		//code financeur "PE" + localisée en région PACA

		/* Rémunération: */
		//AREF + RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Actions de Formation Conventionnées Pôle Emploi (AFC)";
			$array['step']="Pour pouvoir être sélectionné sur une des places financées par Pôle emploi, faites valider tout d’abord votre projet de formation par un conseiller emploi (Pôle emploi, Mission Locale ou Cap Emploi).";
			$array['descinfo']="Les Actions de Formation Conventionnées (AFC) permettent de développer les compétences des demandeurs d’emploi qui en ont le plus besoin et de répondre aux besoins identifiés des entreprises.Le stagiaire bénéficie d'une rémunération et d’une couverture en matière d’accident du travail et de maladie professionnelle.";
			$array['info']="";
			$array['cost']="Formation totalement financée par Pôle emploi comprenant la mobilisation éventuelle de votre CPF";
			if($situation_inscrit)
				if(isInRegionPACA($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_paca'=>$array));
						unset($droits['poleemploicollectif']);
						if($allocation_type=='are')
							remuAREF($var,'poleemploicollectif_paca',$droits);
						else
							remuRFPE2($var,'poleemploicollectif_paca',$droits);
					}
		}

		/* Ligne 4 (PACA) */
		/* Caractéristiques DE: */
		//DE en région PACA
		//A partir de 16 ans

		/* Caractéristiques formation: */
		//F° en région PACA
		//et code financeur Région
		//et formacode 15041
		//'15041'

		/* Rémunération: */
		//Si indemnisé PE : AREF si non RPS,

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Accès aux premiers savoirs - ETAPS";
			$array['step']="Pour pouvoir être sélectionné sur une des places financées par la Région PACA, faites valider tout d’abord votre projet de formation par un conseiller emploi (Pôle emploi, Mission Locale, Cap Emploi).";
			$array['descinfo']="Les ETAPS visent l’acquisition et la reconnaissance de savoirs, de connaissances et de compétences permettant aux personnes d’accéder à une formation qualifiante et/ou un emploi.<br/>Ils permettent le développement de compétences mobilisables dans les différents contextes de la vie de tout individu : vie quotidienne, vie professionnelle, vie sociale et citoyenne, vie familiale …<br/>La durée et le rythme du parcours de formation sont définis en fonction des besoins de chaque personne.<br/>Le stagiaire bénéficie d’une couverture en matière d’accident du travail et de maladie professionnelle.";
			$array['info']="";
			$array['cost']="Formation totalement financée par la Région, comprenant la mobilisation éventuelle de votre CPF";
			if($situation_inscrit)
				if(isInRegionPACA($domicilePath) && isInRegionPACA($training_locationpath))
					if($age>=16)
						if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
							if(in_array($training_formacode,array('15041')))
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_paca2'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_paca2',$droits);
								else
									remuRPS($var,'actioncollectiveregion_paca2',$droits);
							}
		}

		/* Ligne 5 (PACA) */
		/* Caractéristiques DE: */
		//tout DE ou jeune - 26 ans non DE mais inscrit Mission Locale

		/* Caractéristiques formation: */
		//F° en région PACA
		//et code financeur "Region"
		//et certifinfo 54912, 54913, 54917, 87189, 87185, 87187 ou formacode 43448, 43457
		//'54912','54913','54917','87189','87185','87187','43448','43457'

		/* Rémunération: */
		//Remu PE droit commun : AREF ou. Indemnité régionale d'études (téléprocédure sur www.regionpaca.fr)... Attention : si vous avez démissionné depuis moins d'un an d'un contrat de plus de 110h/mois, vous ne toucherez pas de rémunération.

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="ETAQ (Espaces territoriaux à la qualification)";
			$array['step']="Pour pouvoir être sélectionné sur une des places financées par la Région PACA, faites valider tout d’abord votre projet de formation par un conseiller emploi (Pôle emploi, Mission Locale, Cap emploi).";
			$array['descinfo']="Les ETAQ visent la préparation à une certification reconnue, l'acquisition d'un premier niveau de qualification, l'acquisition d'une nouvelle qualification ou le développement de compétences professionnelles complémentaires en vue d'une intégration professionnelle durable.<br/>Le stagiaire bénéficie d’une couverture en matière d’accident du travail et de maladie professionnelle.";
			$array['info']="";
			$array['cost']="Formation totalement financée par la Région, comprenant la mobilisation éventuelle de votre CPF";
			if($situation_inscrit)
				if(isInRegionPACA($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(in_array($training_formacode,array('43448','43457')) || in_array($training_codecertifinfo,array('54912','54913','54917','87189','87185','87187')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_paca3'=>$array));
							unset($droits['actioncollectiveregion']);
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_paca3',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_paca3',$droits,"Indemnité régionale d'études (téléprocédure sur <a href=\"http://www.regionpaca.fr\" target=\"_blank\">www.regionpaca.fr</a>)... Attention : si vous avez démissionné depuis moins d'un an d'un contrat de plus de 110h/mois, vous ne toucherez pas de rémunération.");
						}
		}

		/* Ligne 6 (PACA) */
		/* Caractéristiques DE: */
		//tout DE ou jeune - 26 ans non DE mais inscrit Mission Locale

		/* Caractéristiques formation: */
		//F° en région PACA
		//et code financeur "Region"
		//hors formacode 15041, 43448, 43457 ou certifinfo 54912, 54913, 54917, 87189, 87185, 87187
		//'43448','43457','54912','54913','54917','87189','87185','87187'

		/* Rémunération: */
		//AREF... Si pas d'ARE, rapprochez-vous de votre conseiller emploi pour connaître les possibilités de rémunération

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="ETAQ (Espaces territoriaux à la qualification)";
			$array['step']="Pour pouvoir être sélectionné sur une des places financées par la Région PACA, faites valider tout d’abord votre projet de formation par un conseiller emploi (Pôle emploi, Mission Locale, Cap emploi).";
			$array['descinfo']="Les ETAQ visent la préparation à une certification reconnue, l'acquisition d'un premier niveau de qualification, l'acquisition d'une nouvelle qualification ou le développement de compétences professionnelles complémentaires en vue d'une intégration professionnelle durable.<br/>Le stagiaire bénéficie d’une couverture en matière d’accident du travail et de maladie professionnelle.";
			$array['info']="";
			$array['cost']="Formation totalement financée par la Région, comprenant la<br/>mobilisation éventuelle de votre compte personnel de formation";
			if($situation_inscrit)
				if(isInRegionPACA($training_locationpath))
				{
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_formacode,array('15041','43448','43457')) && 
						   !in_array($training_codecertifinfo,array('54912','54913','54917','87189','87185','87187')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_paca4'=>$array));
							unset($droits['actioncollectiveregion']);
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_paca4',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_paca4',$droits,"Rapprochez-vous de votre conseiller emploi pour connaître les possibilités de rémunération.");
						}

				}
		}

		/* Ligne 7 (PACA) */
		/* Caractéristiques DE: */
		//Tout DE, localisé en PACA

		/* Caractéristiques formation: */
		//F° France entière
		//et durée totale (centre + entreprise) < ou = à 12 mois maximum
		//et tag CPF Copanef et Coparef DE PACA
		//Hors code financeur PE, Région, Etat, Coll Terr autres, Etat
		//hors formacode 15041, 43448, 43457 ou certifinfo 54912, 54913, 54917, 87189, 87185, 87187
		//'43448','43457','54912','54913','54917','87189','87185','87187'

		/* Rémunération: */
		//AREF ou RFPE

		if(1)
		{
			$array=array();
			$array['pri']="aif";
			$array['title']="Aide Individuelle à la Formation (AIF)";
			$array['step']="Contacter votre conseiller emploi (Pôle emploi, Mission Locale ou Cap emploi) pour lui présenter votre projet de formation. Il partagera avec vous la pertinence de ce projet de formation par rapport au marché du travail.<br/>Il vérifiera aussi si les conditions du financement et de rémunération sont réunies.<br/>Votre projet de formation et son financement doivent être validés au plus tard 15 jours avant le début de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre.<br/>Le stagiaire bénéficie d’une couverture en matière d’accident du travail et de maladie professionnelle.<br/>L’AIF est réservée à des projets de formation dont la pertinence est évaluée par votre conseiller emploi (Pôle emploi, Mission Locale ou Cap emploi).";
			$array['info']="";
			$array['cost']="Formation financée par Pôle emploi, comprenant la mobilisation éventuelle de votre CPF";
			
			if(isInRegionPACA($domicilePath))
			{
				unset($droits['aif']);
				if($situation_inscrit)
					if($training_dureeenmois<=12)
						if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) && 
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) &&
						   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces))
							if(!in_array($training_formacode,array('15041','43448','43457')) && 
							   !in_array($training_codecertifinfo,array('54912','54913','54917','87189','87185','87187','84385')))
								if(!$training_contratprofessionalisation && !$training_contratapprentissage)
								{
									arrayInsertAfterKey($droits,'aif',$display,array('aif_paca'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'aif_paca',$droits);
									else
										remuRFPE2($var,'aif_paca',$droits);
								}
			}
		}
	}
?>
