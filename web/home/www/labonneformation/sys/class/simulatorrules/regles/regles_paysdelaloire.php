<?php
		/* Règles Pays de la loire  *******************************************************************************************/
	function reglesPaysDeLaLoire($quark,$var,&$droits,&$display)
	{
		//if(ENV_DEV===true) echo 'Pays de la loire';
		extract($var);
		
		/* Ligne 2 (pdl) */
		/* Caractéristiques DE: */
		//tous DE

		/* Caractéristiques formation: */
		//localisation PdL + Balise <code-financeur> Collectivité territoriale - Conseil régional
		//+ niveau de formation < ou égal 4
		//+ hors formacodes 43436 Aide-soignant
		//, 43441 Auxiliaire puériculture
		//et 31815 : Transport sanitaire
		//'43436','43441','31815'

		/* Rémunération: */
		//AREF = montant indiqué par le DE dans le formulaire - RPS

		if(1)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Action collective financée par la Région";
			$array['step']="Vérifiez auprès de votre conseiller et/ou de l'organisme de formation<br/>que vous remplissez les conditions pour effectuer cette formation.";
			$array['descinfo']="Cette action de formation est financée par votre Région - gratuite pour les demandeurs d'emploi";
			$array['info']="<a href=\"http://www.paysdelaloire.fr/politiques-regionales/formation-professionnelle/\" target=\"_blank\">www.paysdelaloire.fr</a>";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionPaysDeLaLoire($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_formacode,array('43436','43441','31815')))
							if($training_niveausortie<=CODENIVEAUSCOLAIRE_BAC)
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_pdl'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_pdl',$droits);
								else
									remuRPS($var,'actioncollectiveregion_pdl',$droits);
							}
		}

		/* Ligne 3 (pdl) */
		/* Caractéristiques DE: */
		//tous DE

		/* Caractéristiques formation: */
		//localisation PdL + Balise <code-financeur> Collectivité territoriale - Conseil régional
		//+ niveau de formation > à 4
		//+ hors formacodes 43436 Aide-soignant
		//, 43441 Auxiliaire puériculture
		//et 31815 : Transport sanitaire
		//'43436','43441','31815'

		/* Rémunération: */
		//AREF = montant indiqué par le DE dans le formulaire- RPS

		if(1)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Action collective financée par la Région";
			$array['step']="Pour effectuer cette formation, contactez tout d'abord l'organisme de formation pour être sélectionné sur une des places financées par la Région";
			$array['descinfo']="Cette action de formation est financée par votre Région - gratuite pour les demandeurs d'emploi";
			$array['info']="<a href=\"http://www.paysdelaloire.fr/politiques-regionales/formation-professionnelle/\" target=\"_blank\">www.paysdelaloire.fr</a>";
			$array['cost']="Formation partiellement financée";
			if($situation_inscrit)
				if(isInRegionPaysDeLaLoire($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(!in_array($training_formacode,array('43436','43441','31815')))
							if($training_niveausortie>CODENIVEAUSCOLAIRE_BAC)
							{
								arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_pdl2'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'actioncollectiveregion_pdl2',$droits);
								else
									remuRPS($var,'actioncollectiveregion_pdl2',$droits);
							}
		}

		/* Ligne 4 (pdl) */
		/* Caractéristiques DE: */
		//tous DE

		/* Caractéristiques formation: */
		//localisation PdL + Balise <code-financeur> Collectivité territoriale - Conseil régional
		//+ formacode 43436 : Aide-soignant
		//+ formacode 43441 : Auxiliaire puériculture
		//+ formacode 31815 : Transport sanitaire
		//'43436','43441','31815'

		/* Rémunération: */
		//AREF ou demande de bourse régionale à effectuer (mettre lien <a href=\"http://www.paysdelaloire.fr/services-en-ligne/aides-regionales/aides-regionales-themes/formation/actu-detaillee/n/formations-sanitaires-et-sociales-demande-de-bourses-en-ligne/)\" target=\"_blank\">www.paysdelaloire.fr</a>

		if(1)
		{
			$array=array();
			$array['pri']="1";
			$array['title']="Action collective Sanitaire et Social financée par la Région et Pôle Emploi";
			$array['step']="Pour effectuer cette formation, il vous faut tout d'abord réussir le concours d'entrée. Contactez l'organisme de formation.";
			$array['descinfo']="Cette action de formation est financée par la Région et Pôle emploi";
			$array['info']="<a href=\"http://www.paysdelaloire.fr/politiques-regionales/formation-professionnelle/\" target=\"_blank\">www.paysdelaloire.fr</a>";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(isInRegionPaysDeLaLoire($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
						if(in_array($training_formacode,array('43436','43441','31815')))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_pdl3'=>$array));
							
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_pdl3',$droits);
							else
								remuTEXT($var,'actioncollectiveregion_pdl3',$droits,"Demande de bourse régionale à effectuer sur <a href=\"http://www.paysdelaloire.fr/services-en-ligne/aides-regionales/aides-regionales-themes/formation/actu-detaillee/n/formations-sanitaires-et-sociales-demande-de-bourses-en-ligne/)\" target=\"_blank\">www.paysdelaloire.fr</a>");
						}
		}
	}
?>
