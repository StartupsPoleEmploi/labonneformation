<?php
	/* Règles Bourgogne Franche Comté ***************************************************************************************************/
	function reglesBourgogneFrancheComte($quark,$var,&$droits,&$display)
	{
		extract($var);

		if(isInRegionBourgogneFrancheComte($training_locationpath))
			unset($droits['poecollective'],$droits['actioncollectiveregion'],$droits['poleemploicollectif']);

		/* Ligne 3 : CPF*/
		if(0) //Desactivée pour l'instant. On laisse les règles nationales cpf s'appliquer
			if(isInRegionBourgogneFrancheComte($domicilePath))
			{
				$array=array();
				$array['title']='Compte Personnel de Formation (CPF)';
				$array['step']=array("Activer votre compte personnel sur <a href=\"http://www.moncompteformation.gouv.fr/\" target=\"_blank\">www.moncompteformation.gouv.fr</a><br/>vous munir de votre numéro de sécurité social et d'une adresse mail.<br />Une fois le compte activé, les heures créditées à votre compte s'affiche : 24h/an pour un emploi à temps plein jusqu'à 120h puis 12H/an jusqu'à 150h. <br/>Il est aussi possible de sauvegarder ses heures DIF non utilisées pour une utilisation au plus tard jusqu'au 31 décembre 2020.","Vérifier que la formation souhaitée est bien éligible au CPF : la formation doit permettre d'accéder à une certification éligible (Ex : CAP cuisinier, BPJEPS spécialité loisirs tous publics, etc.).","Vérifier l'éligibilité sur <a href=\"http://www.moncompteformation.gouv.fr/espace-professionnels/professionnels-de-lemploi-et-de-la-formation-professionnelle-0\" target=\"_blank\">www.moncompteformation.gouv.fr</a> (liste COPANEF et liste COPAREF Bourgogne-Franche-Comté &laquo;en recherche d'emploi&raquo;)","Prendre contact avec votre conseiller référent emploi.");
				$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle. Le CPF permet  de favoriser l'accès de son titulaire à la formation professionnelle, indépendamment de son statut pour acquériri un meilleur niveau de qualification.";
				$array['info']="Il est possible de financer avec son CPF une formation permettant de valider partiellement une certification éligible. On parle alors de &laquo;bloc de compétence&raquo;. Il convient de vérifier auprès de l'organisme de formation que la formation est bien découpée en bloc de compétence. Une fois un premier bloc de compétence validé, son titulaire dispose de 5 ans pour valider totalement la certification recherchée.";
				$array['cost']="Le taux horaire maximal de prise en charge au titre du CPF pour un demandeur d'emploi est de 9€/heure (financé par le Fonds Paritaire de Sécurisation des Parcours Professionnels). Si le projet de formation est validé par Pôle emploi, une AIF (cf. AIF) peut permettre de couvrir le reste à charge. Si le projet n'est pas validé par Pôle emploi, le reste à charge doit être financé par un apport personnel ou par un co-financeur autre que Pôle emploi";
				$array['loc']=$locationName;
				if($situation_inscrit)
					if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
						if($situation_creditheurescpfconnu && $situation_creditheurescpf>=1)
						{
							arrayInsertAfterKey($droits,'cpf',$display,array('cpf_bfc'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'cpf_bfc',$droits);
							else
								remuTEXT($var,'cpf_bfc',$droits,"Si vous ne bénéficiez pas de l'AREF, vous toucherez une rémunération formation Pôle emploi (RFPE) si la formation est validée par Pôle emploi. Si votre projet n'est pas validé, vous ne toucherez pas de rémunération.");
							$display['cpf_bfc']['cost']=sprintf("La prise en charge correspond à %d&nbsp;€.<br/>%s",9*$situation_creditheurescpf,$display['cpf']['cost']);
						}
			}

		/* Ligne 4 : AIF */
		if(isInRegionBourgogneFrancheComte($domicilePath))
		{
			unset($droits['aif']);

			$array=array();
			$array['title']="L'Aide Individuelle à la Formation (AIF)";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi).<br/>Vous lui présenterez si possible plusieurs devis d'organismes différents ainsi qu'une lettre de motivation.<br/>Votre projet de formation et son financement doivent être présentés au plus tard un mois avant le début de la formation.";
			$array['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d'action de formation collectives ne peuvent répondre.<br/>L'AIF est réservée à des projets de formation dont la pertinence est validée par votre conseiller référent Pôle emploi.<br/>Cette pertinence est appréciée au regard de votre projet professionnel et du marché du travail.<br/>Les formations financées doivent permettre de faciliter votre retour à l'emploi.";
			$array['info']="L'AIF permet une prise en charge totale des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen...).<br/>Certaines formations sont exclues : financement de permis, quel que soit le permis (B, C, CE, D...).<br/>SI une formation existe dans le programme collectif, elle ne sera pas prise en charge par l'AIF.<br/>Les formations du dispositif Région sanitaire et social sont exclues : aide-soignant, ambulancier et auxiliaire de puériculture";
			$array['cost']="Formation totalement financée par Pôle emploi (hors frais d'inscription et frais éventuels de passage d'examen)";
			if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
			   !hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces) &&
			   !hasKeywords(array('PERMIS'),strtoupper($ar['intitule'])))
				if(!in_array($training_formacode,array(43448,43436,43441,43454,44004,44028,44054)))
					if(!in_array($training_codecertifinfo,array(54912,54913,54917,87185,87187,25467)))
						if(!$training_contratprofessionalisation && !$training_contratapprentissage)
							if($situation_inscrit)
							{
								$array['loc']=$locationName;
								arrayInsertAfterKey($droits,'aif',$display,array('aif_bfc'=>$array));
								if($allocation_type=='are')
									remuAREF($var,'aif_bfc',$droits);
								else
									remuRFPE2($var,'aif_bfc',$droits);
							}
		}

		/* Ligne 5 : POEC */
		if(isInRegionBourgogneFrancheComte($training_locationpath))
		{
			$array=array();
			$array=$display['poecollective'];
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)";
			$array['descinfo']="Il s'agit d'une formation professionnalisante, qui répond à un besoin de main-d'oeuvre qualifiée exprimé par une branche, un secteur professionnel ou une profession... D'une durée maximale de 400h, elle inclut une période d'application en entreprise. Elle est gratuite et réservée aux demandeurs d'emploi";
			$array['info']="Les frais pédagogiques sont pris en charge par un OPCA (organisme paritaire collecteur agréé).<br/>La rémunération est prise en charge par Pôle emploi.";
			$array['cost']="Formation totalement financée";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
				{
					$array['loc']=$locationName;
					arrayInsertAfterKey($droits,'poecollective',$display,array('poecollective_bfc'=>$array));
					if($allocation_type=='are')
						remuAREF($var,'poecollective_bfc',$droits);
					else
						remuRFPE2($var,'poecollective_bfc',$droits);
				}
		}

		/* Ligne 6 : Action Collective Pôle emploi AFC PE */
		if(isInRegionBourgogneFrancheComte($training_locationpath))
		{
			$array=array();
			$array=$display['poleemploicollectif'];
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)";
			$array['descinfo']="Il s'agit d'un formation collective, délivrée en centre de formation avec une période de stage en entreprise, gratuite et réservée aux demandeurs d'emploi";
			$array['info']="Se renseigner auprès de Pôle emploi pour les conditions d'attribution d'aides à la mobilité et de frais de garde d'enfants pour les parents isolés (soumis à des conditions de revenus), si vous êtes retenu sur une des places financées par Pôle emploi.";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					if(!in_array($training_codecertifinfo,array(54912,54913,54917,87185,87187)))
					{
						$array['loc']=$locationName;
						arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_bfc'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'poleemploicollectif_bfc',$droits);
						else
							remuRFPE2($var,'poleemploicollectif_bfc',$droits);
					}
		}

		/* Ligne 7 : Action Collective Région */
		if(isInRegionBourgogneFrancheComte($training_locationpath))
		{
			$array=array();
			$array=$display['actioncollectiveregion'];
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)";
			$array['descinfo']="Il s'agit d'une formation collective qualifiante (qualification professionnelle reconnue, diplômante ou certifiante pour accéder à l’emploi : diplôme, CQP, titres homologués/certifiés inscrits au Répertoire National des Certifications Professionnelles (RNCP), délivrée en centre de formation avec une période de stage en entreprise, gratuite pour le demandeur d'emploi";
			$array['info']="L’entrée en formation sur une action collective est ouverte aux demandeurs d’emploi inscrits à Pôle emploi en catégorie 1, 2 ou 3  et dont le projet professionnel a fait l’objet d’une validation par un conseiller Pôle emploi, Cap emploi ou Mission Locale.";
			$array['cost']="Formation totalement financée";
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				if(!in_array($training_codecertifinfo,array(54912,54913,54917,87185,87187)))
				{
					if($situation_inscrit)
					{
						// TODO: ajouter inscrit en catégorie 1,2,3
						$array['loc']=$locationName;
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_bfc'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_bfc',$droits);
						else
							remuRPS($var,'actioncollectiveregion_bfc',$droits);
					}
				}
		}

		/* Ligne 8 (Bourgogne franche comté) */
		/* Caractéristiques DE: */
		//DE France entière
		//inscrit depuis 2 mois ou +

		/* Caractéristiques formation: */
		//F° en Bourgogne Franche Comté
		//Certifinfo 54913 + SIRET 26890005700061, 26710079000059 , 26890025500012, 26890023000072 , 26390003700080, 26210007600286 , 26580008600018, 20001120300037, 26710033700034 , 26710076600117 , 26580007800015, 20004782700056, 26710028700130 , 26390004500109 , 26250462400012 , 13001406100019 , 26250176000082 , 26900129300118 26900129300167, 26890015600103, 77567227210495 , 77567227233851, 19580050300037
		//Certifinfo 54917 + SIRET 26250462400012, 26250176000082, 77567227233851 , 19250030400022
		//Certifinfo 54912 + siret 26250176000082, 26210007600310; 26890015600103
		//'54913','54917','54912'

		/* Rémunération: */
		//AREF ou RPS. Un stagiaire a la possibilité de cumuler la rémunération de stagiaire de la formation professionnelle avec une rémunération perçue au titre d’une activité salariée dans la limite de 10 heures par jour et de 48 heures par semaine (formation + emploi)... Pour autant, l’activité salariée doit rester compatible avec l’assiduité nécessaire à la formation.

		if(1)
		{
			$array=array();
			$array['pri']="";
			$array['title']="FORMATION SANITAIRE ET SOCIAL";
			$array['step']="Admission après la réussite du concours d'entrée pour les formations débouchant sur un diplôme d'Etat d'aide-soignant, d'ambulancier ou d'auxiliaire de puériculture et d'accompagnant éducatif et social (option structure et domicile).<br/>1) Retirer un dossier auprèsde l'école concernée pour s'inscrire au concours.<br/>2) Le projet doit être validé par le conseiller référent emploi.<br/>3) Attention aux conditions d'éligibilité.<br/>Ne sont pas éligibles : 1) les demandeurs d’emploi déjà détenteurs d’un diplôme paramédical (L.4383-3 du Code de la Santé) ou relevant du secteur social (L.451-1 du Code de l’action sociale et familiale), 2) les salariés du secteur privé ou publc démissionnaires, 3) les personnes percevant une allocation d’étude versée par un centre hospitalier ou un employeur, 4) les démissionnaires d’un contrat à durée indéterminée dans les 4 mois avant leur entrée en formation, 5) Les dermandeurs d'emploi inscrits depuis moins de deux mois à Pôle emploi au moment du démarrage de la formation 6) les personnes en congé parental, 6) Les redoublants.<br/>Un délai de carence de 2 ans entre deux qualifications professionnelles obtenues est exigé, quel que soit le financeur et le domaine de qualification.";
			$array['descinfo']="Formation débouchant sur un diplôme d'Etat de niveau V, prise en charge et rémunérée pour les demandeurs d'emploi par la Région et par Pôle emploi";
			$array['info']="";
			$array['cost']="Formation totalement prise en charge<br/>en dehors de frais d'inscription (180 €)";
			if($situation_inscrit)
				if(isInRegionBourgogneFrancheComte($training_locationpath))
				{
					if((in_array($training_codecertifinfo,array('54913')) && in_array($training_siret,array('26890005700061','26710079000059','26890025500012','26890023000072','26390003700080','26210007600286','26580008600018','20001120300037','26710033700034','26710076600117','26580007800015','20004782700056','26710028700130','26390004500109','26250462400012','13001406100019','26250176000082','26900129300118','26900129300167','26890015600103','77567227210495','77567227233851','19580050300037'))) ||
					   (in_array($training_codecertifinfo,array('54917')) && in_array($training_siret,array('26250462400012','26250176000082','77567227233851','19250030400022'))) ||
					   (in_array($training_codecertifinfo,array('54912')) && in_array($training_siret,array('26250176000082','26210007600310','26890015600103'))))
						{
							arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_bfc3'=>$array));
							if($allocation_type=='are')
								remuAREF($var,'actioncollectiveregion_bfc3',$droits);
							else
								remuRPS($var,'actioncollectiveregion_bfc3',$droits);
						}
				}
		}

		/* Ligne 9 : DAQ dispositif "accès à la  qualification" (SIEG) */
		if(isInRegionBourgogneFrancheComte($training_locationpath))
		{
			$array=array();
			$array['title']="DAQ dispositif &laquo;accès à la qualification&raquo; (SIEG)";
			$array['step']="Contacter votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi)";
			$array['descinfo']="Parcours de 650h maximum permettant à une personne peu ou pas qualifiée d'accéder à des formations qualifiantes en validant des certifcations comme  le Certificat de formation générale, le Diplôme d’études en langue française option professionnelle), ou le diplôme de compétence en langue, le brevet informatique et internet adultes ou le passeport de compétences informatique européen), l' attestation de sécurité routière...";
			$array['info']="L’entrée en formation sur cette action collective est ouverte aux demandeurs d’emploi inscrits à Pôle emploi  en catégorie 1, 2 ou 3 et dont le projet a fait l’objet d’une validation par un conseiller Pôle emploi, Mission Locale ou Cap emploi.";
			$array['cost']="Formation totalement financée";
			$array['loc']=$locationName;
			if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_COLLECTIVITE_TERRITORIALE_CONSEIL_REGIONAL,$nbPlaces))
				if(hasKeywords(array('DAQ'),strtoupper($ar['intitule']) || hasKeywords(array('dispositif accès à la qualification'),strtoupper($ar['intitule']))))
					if($situation_inscrit)
					{
						arrayInsertAfterKey($droits,'actioncollectiveregion',$display,array('actioncollectiveregion_bfc2'=>$array));
						// TODO: ajouter inscrit en catégorie 1,2,3
						if($allocation_type=='are')
							remuAREF($var,'actioncollectiveregion_bfc2',$droits);
						else
							remuRPS($var,'actioncollectiveregion_bfc2',$droits);
					}
		}
	}
?>