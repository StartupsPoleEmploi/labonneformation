<?php
	/* Règles Hauts de France *********************************************************************************************/
	function reglesHautsDeFrance($quark,$var,&$droits,&$display)
	{
		extract($var);

		/* Ligne 3 */
		/* Caractéristiques DE: */
		//Tous les DE inscrits en région NPDC
		//qui des DE hors région?

		/* Caractéristiques formation: */
		//F° CPF COPANEF ou CPF COPAREFrégion HDF

		/* Rémunération: */
		//

		if(1)
		{ //Commentée car règles nationale
			//$array=array();
			//$array['pri']="";
			//$array['title']="";
			//$array['step']="1 Activer votre compte personnel sur http://www.moncompteformation.gouv.fr/<br/>vous munir de votre numéro de sécurité social et d'une adresse mail.<br/>Une fois le compte activé, les heures créditées à votre compte s'affiche : 24h/an pour un emploi à temps plein jusqu'à 120h puis 12H/an jusqu'à 150h.<br/>Il est aussi possible de sauvegarder ses heures DIF non utilisées pour une utilisation au plus tard jusqu'au 31 décembre 2020.<br/>2) Vérifier que la formation souhaitée est bien éligible au CPF : la formation doit permettre d'accéder à une certification éligible (Ex : CAP cuisinier, BPJEPS spécialité loisirs tous publics, etc.) Vérifier l'éligibilité sur http://www.moncompteformation.gouv.fr/espace-professionnels/professionnels-de-lemploi-et-de-la-formation-professionnelle-0 (liste COPANEF et liste COPAREF Bourgogne-Franche-Comté &laquo;&nbsp;en recherche d'emploi&nbsp;&raquo;)<br/>3) Prendre contact avec votre conseiller référent emploi.";
			//$array['descinfo']="le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle. Le CPF permet de favoriser l'accès de son titulaire à la formation professionnelle, indépendamment de son statut pour acquérir un meilleur niveau de qualification.";
			//$array['info']="il est possible de financer avec son CPF une formation permettant de valider partiellement une certification éligible. On parle alors de &laquo;&nbsp;bloc de compétence&nbsp;&raquo;. Il convient de vérifier auprès de l'organisme de formation que la formation est bien découpée en bloc de compétence. Une fois un premier bloc de compétence validé, son titulaire dispose de 5 ans pour valider totalement la certification recherchée.";
			//$array['cost']="La durée de la prise en charge correspond au nombre d'heures créditées sur le CPF (ex si CPF = 120h et si la formation = 200h, 120h seront pris en charge au titre du CPF)<br/>Le taux horaire maximal de prise en charge au titre du CPF pour un demandeur d'emploi est de 9€/heure (financé par le Fonds Paritaire de Sécurisation des Parcours Professionnels).<br/>SI le projet de formation est validé par Pôle emploi, un autre dispositif de financement peut être mobilisé peut permettre de couvrir le reste à charge.<br/>SI le projet n'est pas validé par Pôle emploi, le reste à charge doit être financé par un apport personnel ou par un co-financeur autre que Pôle emploi";
			//$display['cpf']=$array;
			//if($situation_inscrit)
			//{
			//	if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
			//	{
			//		if($situation_creditheurescpfconnu && $situation_creditheurescpf>=1)
			//		{
			//			if($allocation_type=='are')
			//				remuAREF($var,'cpf',$droits);
			//			else
			//				remuTEXT($var,'cpf',$droits,"Si vous ne bénéficiez pas de l'AREF, vous toucherez une rémunération formation Pôle emploi (RFPE) si la formation est validée par Pôle emploi. Si votre projet n'est pas validé, vous ne toucherez pas de rémunération.");
			//			$display['cpf']['cost']=sprintf("La prise en charge correspond à %d&nbsp;€.<br/>%s",9*$situation_creditheurescpf,$display['cpf']['cost']);
			//		}
			//	}
			//}
		}

		/* Ligne 4 */
		/* Caractéristiques DE: */
		//Tout DE inscrit en région HDF

		/* Caractéristiques formation: */
		//formation éligible au CPF - hors F° avec code financeur Région, Etat, PE …ou hors Formation certifinfo 54913 (aide-soignant),54912 (ambulancier ) et formacode 43441
		//(auxiliaire de puériculture)
		//'54913','54912','43441'

		/* Rémunération: */
		//voir RFF (doc pdf). AREF - RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Aide Individuelle à la Formation (AIF)";
			$array['step']="Contactez votre conseiller référent Pôle emploi.<br/>Si vous êtes reconnu travailleur handicapé, contactez l'Agéfiph.<br/>Attention : votre projet de formation et son financement (dossier complet) doivent être présentés au plus tard 15 jours avant le début de la formation.";
			$array['descinfo']="L’aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d’action de formation collectives ne peuvent répondre.<br/>Vous devez indiquez dans le formulaire AIF votre souhait ou non de mobiliser votre CPF<br/>L’AIF est réservée à des projets de formation dont la pertinence est validée par votre conseiller référent Pôle emploi.<br/>Cette pertinence est appréciée au regard de votre projet professionnel et du marché du travail.<br/>Les formations financées doivent permettre de faciliter votre retour rapide (dans les 6 mois qui suivent la formation) à l'emploi.";
			$array['info']="L'AIF permet une prise en charge des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen …).<br/>l’AIF peut être attribuée quelle que soit la modalité pédagogique de la formation, y compris pour une formation à distance (FOAD) ou pour une formation en cours du soir.";
			$array['cost']=""; //Calculé plus bas
			if(isInRegionHautsDeFrance($domicileRegionPath))
			{
				unset($droits['aif']);
				if($situation_inscrit)
					if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
						if(!(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces)))
							if(!in_array($training_codecertifinfo,array(54913,54912)))
								if(!in_array($training_formacode,array(43441)))
								{
									//$array['cost-plafond']=($situation_creditheurescpfconnu && $situation_creditheurescpf>0)?2500+9*$situation_creditheurescpf:2500;
									$array['cost-plafond']=2500;
									$array['cost']=sprintf("Prise en charge jusqu'à %s € maximum",Tools::fmt($array['cost-plafond']));

									arrayInsertAfterKey($droits,'aif',$display,array('aif_hdf'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'aif_hdf',$droits);
									else
										remuRFPE2($var,'aif_hdf',$droits);
								}
			}
		}

		/* Ligne 5 */
		/* Caractéristiques DE: */
		//tout DE

		/* Caractéristiques formation: */
		//F° avec intitulé incluant POEC ou dispositif financement = POEC en hauts de france

		/* Rémunération: */
		//AREF RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Préparation Opérationnelle à l'Emploi Collective (POEC)";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.";
			$array['descinfo']="Il s'agit d'une formation professionnalisante, qui répond à un besoin de main-d'oeuvre qualifié exprimé par une branche, un secteur propfessionnel ou une profession. D'une durée maximale de 400h , elle inclut une période d'application en entreprise. Elle est gratuite et réservée aux demandeurs d'emploi";
			$array['info']="Les frais pédagogiques sont pris en charge par un OPCA (organisme paritaire collecteur agréé).<br/>La rémunération est prise en charge par Pôle emploi. Sous certaines conditions, elles peuvent ouvrir droit à des aides à la mobilité";
			$array['cost']="Formation Totalement financée";
			if($situation_inscrit)
				if(isInRegionHautsDeFrance($training_locationpath))
					if(hasKeywords(array('POEC'),$ar['intitule']))
					{
						arrayInsertAfterKey($droits,'poecollective',$display,array('poecollective_hdf'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'poecollective_hdf',$droits);
						else
							remuRFPE2($var,'poecollective_hdf',$droits);
					}
		}

		/* Ligne 6 */
		/* Caractéristiques DE: */
		//Tout DE inscrit indemnisé ou non

		/* Caractéristiques formation: */
		//F collective, code financeur PE en hauts de france

		/* Rémunération: */
		//AREF RFPE

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Action Collective Pôle emploi (AFC)";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.";
			$array['descinfo']="Les actions de formation conventionnées (AFC) sont des actions de formation proposées par Pôle emploi pour des demandeurs d'emploi ayant besoin d'un renforcement de ses capacités professionnelles pour répondre à des besoins identifiés d'entreprises au niveau territorial ou professionnel.<br/>Elles sont entièrement financées par Pole Emploi";
			$array['info']="Sous certaines conditions, ces formations peuvent ouvrir droit à des aides à la mobilité (déplacement et/ou hébergement)";
			$array['cost']="Formation Totalement financée";
			if($situation_inscrit)
				if(isInRegionHautsDeFrance($training_locationpath))
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_hdf'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'poleemploicollectif_hdf',$droits);
						else
							remuRFPE2($var,'poleemploicollectif_hdf',$droits);
					}
		}

		/* Ligne 7 */
		/* Caractéristiques DE: */
		//idem nat (DE ayant effectué un CDD)

		/* Caractéristiques formation: */
		//idem nat

		/* Rémunération: */
		//idem nat

		if(0)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Congé Individuel de Formation CDD - idem nat sauf colonne en savoir plus";
			$array['step']="Une commission paritaire d'agrément examine la prise en charge de votre dossier.La décision de pris en charge peut également porter sur les conditions de rémunérations<br/>Sous certaines conditions les frais de déplacement et/ou d'hébergement peuvent être pris en charge<br/>La prise en charge peut être totale ou partielle Contactez le Fongecif Hauts de France<br/>(http://www.fongecif5962.fr)";
			//$array['descinfo']="idem nat";
			//$array['info']="idem nat";
			//$array['cost']="idem nat";
			if(isInRegionHautsDeFrance($training_locationpath))
			{
				$display['finindividuel']['step']=$array['step']; //Les règles nationales s'appliquent, on change juste 'step';
			}
		}

		/* Ligne 8 */
		/* Caractéristiques DE: */
		//
		/* Caractéristiques formation: */
		//La durée maximale de l'AFPR est fixée à 150 heures en tutorat
		//Elle vise une embauche en CDD de 6 à moins de 12 mois
		/* Rémunération: */
		//AREF - RFPE
		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Supprimer Action de formation préalable au recrutement<br/>AFPR";
		//	$array['step']="Une entreprise veut vous embaucher en CDD, vous avez besoin d'une formation en tutorat, contactez votre conseiller référent Pôle emploi pour bénéficier d'une AFPR";
		//	$array['descinfo']="L'action de formation préalble au recrutement (AFPR) vise à fomer des demandeurs d'emploi avant un recrutement .<br/>Elle est réalisée par l’entreprise dans le cadre du tutorat (150 heures maxi)<br/>afin d'acquérir les compétences nécessaires pour occuper l'emploi proposé.<br/>Un dispositif expémrimental est mis en oeuvre pour les embauches réalisées dans le département de l'Aisne jusqu'en juillet 2017(lieu d'activité)";
		//	$array['info']="L'Action de Formation Préalable au Recrutement (AFPR) et la Préparation Opérationnelle à l'Emploi Individuelle (POEI) sont des formations avant embauche. Elles sont destinées à combler l'écart entre les compétences que vous détenez et celles que requiert l'emploi que vous allez occuper. L'AFPR et la POEI sont donc des actions de formation avant une embauche. Embauche qui peut se faire pour l'AFPR en CDD ou en contrat de professionnalisation à durée déterminée de plus de 6 mois ou sur des missions en contrat de travail temporaire d'au moins 6 mois dans les 9 prochains mois. Embauche qui peut se faire pour la POEI en CDD d'au moins 12 mois ou en contrat de travail à durée indéterminée (CDI).";
		//	$array['cost']="Formation Totalement financée";
		//	$display['individuel - à supprimer']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}

		/* Ligne 9 */
		/* Caractéristiques DE: */
		//Tout DE inscrit indemnisé ou non
		/* Caractéristiques formation: */
		//La durée maximale de la POEI est fixée à 200 heures
		//Elle vise une embauche en CDI ou en CDD de 12 mois ou plus
		/* Rémunération: */
		//AREF RFPE
		//if(1)
		//{
		//	$array=array();
		//	$array['pri']="";
		//	$array['title']="Supprimer Préparation opérationnelle à l’emploi individuelle<br/>POEI";
		//	$array['step']="Une entreprise veut vous embaucher en CDD + de 12 mois ou CDI , vous avez besoin d'une formation,demandez à l'entreprise de contacter son OPCA pour bénéficier de la formation. Pendant la formation vous serez stagiaire de la formation professionnelle";
		//	$array['descinfo']="La préparation opérationnelle à l'emploi vise à fomer des demandeurs d'emploi avant un recrutement .<br/>Elle est réalisée par un organisme de formation interne ou externe<br/>afin d'acquérir les compétences nécessaires pour occuper l'emploi proposé. 400 heures maxi à l'initiative de l'OPCA";
		//	$array['info']="La POEI est accessible à tout demandeur d'emploi inscrit, indemnisé ou non.<br/>Elle vise à répondre à un besoin de recrutement qui nécessite une adaption des compétences acquises.<br/>Avant le début de formation, un plan de formation individuel est établi avec l'entreprise et le demandeur d'emploi afin de répondre précisémement au besoin de l'entreprise.";
		//	$array['cost']="Formation Totalement financée";
		//	$display['individuel à supprimer']=$array;
		//	if($situation_inscrit)
		//	{
		//	}
		//}
		/* Ligne 8 et 9, on supprime afprpoei si calculé en national */
		if(isInRegionHautsDeFrance($training_locationpath))
		{
			unset($droits['afprpoei'],$droits['afprpoei2'],$droits['afpr'],$droits['poei2'],$droits['poei']);
		}

		/* Ligne 10 */
		/* Caractéristiques DE: */
		//Les demandeurs d’emploi Hauts de France + CSP

		/* Caractéristiques formation: */
		//F° exclues : code financeur Région, PE, Etat … ou hors Formation certifinfo 54913 (aide-soignant),54912 (ambulancier ) et formacode 43441
		//(auxiliaire de puériculture) ou
		//durée F°> 12 mois
		//'54913','54912','43441'

		/* Rémunération: */
		//AREF si ARE. RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Le Chèque Pass Formation";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.<br/>Vous devez obligatoirement mobiliser votre Compte Personnel de Formation lors de la demande<br/>Les demandes d'aide doivent être instruites au moins 3 semaines avant le démarrage de l'action de formation";
			$array['descinfo']="Le Chèque Pass Formation est un dispositif mis en place par le Conseil Régional Hauts de France pour répondre aux besoins individuels ne trouvant pas leur réponse dans les programmes collectifs.<br/>Il s’agit de permettre au bénéficiaire de l’aide d’accéder à une formation qualifiante ou menant à une certification (CAP, Bac, Titre Professionnel, …) en lien direct avec leur projet professionnel.<br/>La durée des actions de formation ne peut dépasser 12 mois<br/>Cette aide est versée par la Région Hauts de France";
			$array['info']="La Région Hauts de France accompagne les projets individuels de formation des demandeurs d’emploi visant :<br/>- soit les métiers en tension et prioritaires,<br/>- soit les projets de création ou de reprise d’entreprise,<br/>- soit les projets de reprise d’activité suite à un licenciement économique<br/>consultez le lien suivant : http://www.c2rp.fr/dispositifs/cheque-pass-formation";
			$array['cost']="formation gratuite dans la limite de 15€/h et plafonnée à 6000 € consultez le lien suivant : <a href=\"http://www.c2rp.fr/dispositifs/cheque-pass-formation\" target=\"_blank\">cheque-pass-formation</a>";
			$array['cost-plafond']="6000";
			if(isInRegionHautsDeFrance($domicileRegionPath))
				if($situation_inscrit)
					if($situation_liccsp)
						if(!(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces)))
							if(!in_array($training_codecertifinfo,array(54913,54912)))
								if(!in_array($training_formacode,array(43441)))
									if($training_dureeenmois<=12)
									{
										arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_hdf'=>$array));
										if($allocation_type=='are')
											remuAREF($var,'finindividuel_hdf',$droits);
										else
											remuRPS($var,'finindividuel_hdf',$droits);
									}
		}

		/* Ligne 11 */
		/* Caractéristiques DE: */
		//Les demandeurs d’emploi quel que soit leur âge, indemnisés ou non par l’assurance chômage et aux salariés licenciés économiques dans la cadre d'un CSP

		/* Caractéristiques formation: */
		//si tag CPF + taux retour emploi = ou > 3* , hors code financeur Région, PE, Etat et durée f° > 12 MOIS ou hors Formation certifinfo 54913 (aide-soignant),54912 (ambulancier ) et formacode 43441
		//(auxiliaire de puériculture)
		//'54913','54912','43441'

		/* Rémunération: */
		//AREF si ARE. RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Le Chèque Pass Formation Automatique";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.<br/>Vous devez obligatoirement mobiliser votre Compte Personnel de Formation lors de la demande<br/>Les demandes d'aide doivent être instruites au moins 3 semaines avant le démarrage de l'action de formation";
			$array['descinfo']="Le Chèque Pass Formation est un dispositif mis en place par le Conseil Régional Hauts de France pour répondre aux besoins individuels ne trouvant pas leur réponse dans les programmes collectifs.<br/>Il s’agit de permettre au bénéficiaire de l’aide d’accéder à une formation qualifiante ou menant à une certification (CAP, Bac, Titre Professionnel, …) en lien direct avec leur projet professionnel.<br/>La durée des actions de formation ne peut dépasser 12 mois<br/>Cette aide est versée par la Région Hauts de France";
			$array['info']="La Région Hauts de France accompagne les projets individuels de formation des demandeurs d’emploi visant :<br/>- soit les métiers en tension et prioritaires,<br/>- soit les projets de création ou de reprise d’entreprise,<br/>- soit les projets de reprise d’activité suite à un licenciement économique<br/>consultez le lien suivant : http://www.c2rp.fr/dispositifs/cheque-pass-formation";
			$array['cost']="Formation gratuite dans la limite de 15€/h et plafonnée à 6000€";
			$array['cost-plafond']="6000";
			if(isInRegionHautsDeFrance($domicileRegionPath))
				if($situation_inscrit)
					if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
						if(!(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces)))
							if(!in_array($training_codecertifinfo,array(54913,54912)))
								if(!in_array($training_formacode,array(43441)))
									if($training_dureeenmois<=12)
										if((bestRate($ad,$ar))>=3)
										{
											arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_hdf2'=>$array));
											if($allocation_type=='are')
												remuAREF($var,'finindividuel_hdf2',$droits);
											else
												remuRPS($var,'finindividuel_hdf2',$droits);
										}
		}

		/* Ligne 12 */
		/* Caractéristiques DE: */
		//Tout DE inscrit en région HDF

		/* Caractéristiques formation: */
		//Formation certifinfo 54913 (aide-soignant),54912 (ambulancier ) et formacode 43441
		//(auxiliaire de puériculture),
		//+ localisation Hts de France
		//'54913','54912','43441'

		/* Rémunération: */
		//AREF/RFF. RPS + rému complémentaire

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Sanitaire et Social";
			$array['step']="Contactez votre conseiller<br/>référent emploi (Pôle emploi, Mission Locale ou Cap emploi)";
			$array['descinfo']="Certaines formations relevant du secteur sanitaire et social font l'objet d'une subvention et peuvent ainsi, sous certaines conditions, être financées par Pole Emploi";
			$array['info']="Contactez votre conseiller<br/>référent emploi (Pôle emploi, Mission Locale ou Cap emploi)";
			$array['cost']="Formation totalement financée";
			if(isInRegionHautsDeFrance($domicileRegionPath))
				if(isInRegionHautsDeFrance($training_locationpath))
					if(in_array($training_codecertifinfo,array(54913,54912)) || in_array($training_formacode,array(43441)))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_hdf'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_hdf',$droits);
						else
							remuRPS($var,'actioncollectiveregion_hdf',$droits);
					}
		}

		/* Ligne 13 */
		/* Caractéristiques DE: */
		//tout DE de la région hors CSP

		/* Caractéristiques formation: */
		//Code financeur région+ localisation Hts de France

		/* Rémunération: */
		//AREF si ARE. RPS si non indemnisé

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Programme Regional de Formation (PRF)";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.";
			$array['descinfo']="Le Programme Régional de Formation propose des actions de formation financées par le Conseil Régional pour des demandeurs d'emploi ayant besoin d'une certification ou qualification.";
			$array['info']="Consultez le lien : <a href=\"https://formation.hautsdefrance.fr/\" target=\"_blank\">formation.hautsdefrance.fr</a>";
			$array['cost']="Formation totalement financée";
			if(isInRegionHautsDeFrance($domicileRegionPath) && isInRegionHautsDeFrance($training_locationpath))
				if(!$situation_liccsp)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_hdf2'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_hdf2',$droits);
						else
							remuRPS($var,'actioncollectiveregion_hdf2',$droits);
					}
		}

		/* Ligne 14 */
		/* Caractéristiques DE: */
		//tout DE Hauts de France

		/* Caractéristiques formation: */
		//F° en Hauts de France, durée < ou = 400h hors F° avec code financeur Région, Etat, coll terr, PE ou hors Formation certifinfo 54913 (aide-soignant),54912 (ambulancier ) et formacode 43441
		//(auxiliaire de puériculture)
		//'54913','54912','43441'

		/* Rémunération: */
		//AREF si ARE. RPS

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="Pass Emploi";
			$array['step']="Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation et identifier une entreprise ayant des besoins de recrutements avec une adaptation au poste de travail. L'entreprise s’engage à recruter les candidats formés selon l’un des contrats suivants :<br/>CDI, y compris CDI intérimaire,<br/>CDD de six mois minimum,<br/>Contrat de professionnalisation de six mois minimum,<br/>Contrat d’apprentissage,<br/>Contrat en intérim de 6 mois sur une période de 12 mois.<br/>L’entreprise s’engage par écrit à recruter les candidats formés. L'entreprise souhaitant recruter doit envoyer la demande de subvention à la Région. Elle doit indiquer le nombre prévisionnel de création de postes.";
			$array['descinfo']="Le Pass Emploi est une aide mise en place par la Région HDF. Elle a pour objectif de permettre à des demandeurs d'emploi d'acquérir des connaissances et compétences nécessaires aux postes de travail proposés par une entreprise, contribuant ainsi à une adaptation &laquo;&nbsp;juste à temps et sur mesure&nbsp;&raquo;.";
			$array['info']="Consultez <a href=\"http://www.c2rp.fr/dispositifs/pass-emploi\" target=\"_blank\">www.c2rp.fr</a> et <a href=\"https://formation.hautsdefrance.fr/\" target=\"_blank\">formation.hautsdefrance.fr</a>";
			$array['cost']="formation gratuite dans la limite de 15€/heure pour une durée maximale de 400h.<br/>Le demandeur d’emploi est amené à mobiliser les heures disponibles sur son Compte Personnel de Formation (CPF) dès lors que la formation visée y est éligible";
			if(isInRegionHautsDeFrance($domicileRegionPath) && isInRegionHautsDeFrance($training_locationpath))
				if($situation_inscrit)
					if($training_duration && $training_duration<=400)
						if(!(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_COMMUNE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_GENERAL,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_AUTRE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_DE_L_EDUCATION_NATIONALE,$nbPlaces) ||
						   hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_ETAT_MINISTERE_CHARGE_DE_L_EMPLOI,$nbPlaces)))
							if(!in_array($training_codecertifinfo,array(54913,54912)))
								if(!in_array($training_formacode,array(43441)))
								{
									if($training_duration>0) $array['cost-plafond']=15*$training_duration;
									arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_hdf3'=>$array));
									if($allocation_type=='are')
										remuAREF($var,'finindividuel_hdf3',$droits);
									else
										remuRPS($var,'finindividuel_hdf3',$droits);
								}
		}
	}
?>
