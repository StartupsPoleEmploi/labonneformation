<?php
	/* Règles Réunion et Mayotte  *****************************************************************************************/
	function reglesReunionMayotte($quark,$var,&$droits,&$display)
	{
		extract($var);
		$db=$quark->getStore('read');

		/* Ligne 6 : Aide individuelle à la Formation AIF */
		if(isInRegionReunionMayotte($domicilePath))
		{
			unset($droits['aif']);
			$array=array();
			$array['title']="L'Aide Individuelle à la Formation (AIF)";
			$array['step']=array("Contactez votre conseiller référent emploi  Pôle emploi. Pour les formations dont le coût est supérieur à 2600 €, contactez le CCAS de votre commune ou l'antenne de la Région pour vérifier que vous pouvez disposer d'un co-financement.", "Si vous êtes reconnu handicapé, contactez l'Agéfiph.", "Votre projet de formation et son financement doivent être présentés au plus tard 15 jours avant le début de la formation.");
			$array['descinfo']="L'aide individuelle à la formation professionnelle (AIF) est une aide de Pôle emploi qui vise à financer certains besoins individuels de formation auxquels les achats d'action de formation collectives ne peuvent répondre.<br/>L'AIF est réservée à des projets de formation dont la pertinence est validée par votre conseiller référent Pôle emploi.<br/>Cette pertinence est appréciée au regard de votre projet professionnel et du marché du travail.<br/>Les formations financées doivent permettre de faciliter votre retour rapide (dans les 6 mois qui suivent la formation) à l'emploi.";
			$array['info']="L'AIF permet une prise en charge totale des frais pédagogiques mais ne couvre pas les frais annexes (droits d'inscriptions, acquisition de matériels, frais de passage d'examen...).<br />Certaines formations sont exclues : financement de permis B, C, CE, D<br />Formation universitaire initiale<br />Formations obligatoires dans le cadre de la création d'entreprise (SPI, Hygiène et sécurité, permis d'exploitation)<br />Formation préparation aux concours<br />Formation se déroulant à l'étranger<br />Formation à distance sauf si durée inférieure à un an et avec retour rapide à l'emploi à l'issue de la formation<br />Formations déjà prévues dans les formations collectives Pôle Emploi";
			$array['cost']="formation gratuite si coût &lt; ou = 2600 € - Formations exclues &gt; 2600 € si pas d'autres financeurs --&gt; pas de reste à charge possible pour le DE.<br />Possiblité de chercher un co-financement : CCAS (se renseigner auprès du CCAS de votre commune), Agefiph pour DEBOE, CPF, Région";
			$array['cost-plafond']=2600;
			if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces) &&
			   !hasStrings(array('PERMIS B','PERMIS C','PERMIS CE','PERMIS D','PERMIS D\'EXPLOITATION'),$ar['intitule']) &&
			   !in_array($training_formacode,array(13030, 15073, 43409, 44002, 84385, 42793)) &&
			   !in_array($training_siret,array(SIRET_UNIVERSITE_REUNION)) &&
			   ($training_dureeenmois<12 || !$training_adistance))
				if($situation_inscrit)
					if(!$training_contratprofessionalisation && !$training_contratapprentissage)
					{
						arrayInsertAfterKey($droits,'aif',$display,array('aif_mayotte'=>$array));
						$array['loc']=$locationName;
						if($allocation_type=='are')
							remuAREF($var,'aif_mayotte',$droits);
						else
							remuRFPE2($var,'aif_mayotte',$droits);
					}
		}

		/* Ligne 7 : Compte Personnel de Formation CPF */
		if(0) //Désactivation. Pour le moment on gère en national
		if(isInRegionReunionMayotte($domicilePath))
		{
			$array=array();
			$array['title']='Compte Personnel de Formation (CPF)';
			$array['step']=array("Activer votre compte personnel sur <a href=\"http://www.moncompteformation.gouv.fr/\" target=\"_blank\">www.moncompteformation.gouv.fr</a><br/>vous munir de votre numéro de sécurité social et d'une adresse mail.<br />Une fois le compte activé, les heures créditées à votre compte s'affiche : 24h/an pour un emploi à temps plein jusqu'à 120h puis 12H/an jusqu'à 150h. <br/>Il est aussi possible de sauvegarder ses heures DIF non utilisées pour une utilisation au plus tard jusqu'au 31 décembre 2020.","Vérifier que la formation souhaitée est bien éligible au CPF : la formation doit permettre d'accéder à une certification éligible (Ex : CAP cuisinier, BPJEPS spécialité loisirs tous publics, etc.).","Vérifier l'éligibilité sur <a href=\"http://www.moncompteformation.gouv.fr/espace-professionnels/professionnels-de-lemploi-et-de-la-formation-professionnelle-0\" target=\"_blank\">www.moncompteformation.gouv.fr</a> (liste COPANEF et liste COPAREF Bourgogne-Franche-Comté &laquo;en recherche d'emploi&raquo;)","Prendre contact avec votre conseiller référent emploi.");
			$array['descinfo']="Le compte personnel de formation (CPF) permet à toute personne active, dès son entrée sur le marché du travail et jusqu’à sa retraite, d’acquérir des droits à la formation mobilisables tout au long de sa vie professionnelle. Le CPF permet  de favoriser l'accès de son titulaire à la formation professionnelle, indépendamment de son statut pour acquériri un meilleur niveau de qualification.";
			$array['info']="il est possible de financer avec son CPF une formation permettant de valider partiellement une certification éligible. On parle alors de &laquo;bloc de compétence&raquo;. Il convient de vérifier auprès de l'organisme de formation que la formation est bien découpée en bloc de compétence. Une fois un premier bloc de compétence validé, son titulaire dispose de 5 ans pour valider totalement la certification recherchée.";
			$array['cost']="La durée de la prise en charge correspond au nombre d'heures créditées sur le CPF (ex si CPF = 120h et si la formation = 200h, 120h seront pris en charge au titre du CPF).SI le projet de formation est validé par Pôle emploi, une AIF (cf. AIF) peut permettre de couvrir le reste à charge.<br />SI le projet n'est pas validé par Pôle emploi, le reste à charge doit être financé par un apport personnel ou par un co-financeur autre que Pôle emploi<br />il est possible de financer avec son CPF une formation permettant de valider totalement ou partiellement une certification éligible. On parle alors de \"bloc de compétence\". Il convient de vérifier auprès de l'organisme de formation que la formation est bien découpée en bloc de compétence. Une fois un premier bloc de compétence validé, son titulaire dispose de 5 ans pour valider totalement la certification recherchée.";
			$array['loc']=$locationName;
			$display['cpf']=$array;
			if($situation_inscrit)
				if(hasCOPAREF($ad,$ar,$domicilePath) || hasCOPANEF($ad,$ar))
					if($situation_creditheurescpfconnu && $situation_creditheurescpf>=1)
					{
						arrayInsertAfterKey($droits,'cpf',$display,array('cpf_mayotte'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'cpf_mayotte',$droits);
						else
							remuRFPE2($var,'cpf_mayotte',$droits);
					}
		}

		/* Ligne 9 : Chèque Formation Réussite - formation Région Réunion */
		if(isInRegionReunionMayotte($domicilePath))
		{
			$array=array();
			$array['title']='Chèque Formation Réussite';
			$array['step']="Rapprochez-vous de l'antenne de la Région pour connaître les modalités de mise en œuvre ou contactez votre conseiller référent Pôle Emploi";
			$array['descinfo']="Le dispositif intitulé « Chèque Formation Réussite » a pour objectif de répondre aux différentes demandes individuelles de formation des demandeurs d’emploi qui ont un projet professionnel clairement défini. En effet, cette aide individuelle doit participer à l’aboutissement d’un projet d’insertion professionnelle et de création d’activité à court terme. Aussi, le critère essentiel d’appréciation de l’opportunité demeure la faisabilité du projet professionnel.";
			$array['info']="Le bénéficiaire du Chèque Formation Réussite dispose du statut de stagiaire de la formation professionnelle mais ne perçoit aucune rémunération de la part du Conseil régional.<br />Le dispositif « Chèque Formation Réussite » comprend trois types de chèque :<br />Le chèque Formation pour vous permettre d’accéder à une formation ;<br />Le chèque VAE, pour financer votre accompagnement VAE ;<br />Le chèque Langues Étrangères pour suivre une formation en langue étrangère.";
			$array['cost']="Formation gratuite si côut &lt; ou = 3000 €, reste à charge possible.<br/>Possibilité de monter un co-financement avec un autre dispositif.";
			$array['cost-plafond']=3000;
			$display['finindividuel'] = $array;
			if($situation_inscrit && !in_array($allocation_type,array('rsa')))
				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
				{
					$array['loc']=$locationName;
					arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_mayotte'=>$array));
					if($allocation_type=='are')
						remuAREF($var,'finindividuel_mayotte',$droits);
					else
						remuTEXT($var,'finindividuel_mayotte',$droits);
				}
		}

		/* Ligne 10 : Chèque Formation Réussite - VAE Région Réunion */
		if(isInRegionReunionMayotte($domicilePath))
		{
			$array=array();
			$array['title']='Chèque Formation Réussite - VAE';
			$array['step']="Rapprochez-vous de l'antenne de la Région pour connaître les modalités de mise en œuvre ou contactez votre conseiller référent Pôle Emploi";
			$array['descinfo']="Le dispositif intitulé « Chèque Formation Réussite » a pour objectif de répondre aux différentes demandes individuelles de formation des demandeurs d’emploi qui ont un projet professionnel clairement défini. En effet, cette aide individuelle doit participer à l’aboutissement d’un projet d’insertion professionnelle et de création d’activité à court terme. Aussi, le critère essentiel d’appréciation de l’opportunité demeure la faisabilité du projet professionnel.";
			$array['info']="Le bénéficiaire du Chèque Formation Réussite dispose du statut de stagiaire de la formation professionnelle mais ne perçoit aucune rémunération de la part du Conseil régional.<br />Le dispositif « Chèque Formation Réussite » comprend trois types de chèque :<br />Le chèque Formation pour vous permettre d’accéder à une formation ;<br />Le chèque VAE, pour financer votre accompagnement VAE ;<br />Le chèque Langues Étrangères pour suivre une formation en langue étrangère.";
			$array['cost']="Formation gratuite si côut &lt; ou = 3000 €, reste à charge possible. Possibilité de faire un co-financement avec un autre dispositif. Contacter les CCAS de la commune du DE pour un éventuel financement, Agefiph pour DEBOE ";
			$array['cost-plafond']=3000;
			if($situation_inscrit && !in_array($allocation_type,array('rsa')))
				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					if(in_array($training_formacode,array(15064,44591)))
					{
						$array['loc']=$locationName;
						arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_mayotte2'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'finindividuel_mayotte2',$droits);
						else
							remuTEXT($var,'finindividuel_mayotte2',$droits);
					}
		}

		/* Ligne 11 : Chèque Formation Réussite - langue étrangère Région Réunion */
		if(isInRegionReunionMayotte($domicilePath))
		{
			$array=array();
			$array['title']='Chèque Formation Réussite - langue étrangère';
			$array['step']="Rapprochez-vous de l'antenne de la Région pour connaître les modalités de mise en œuvre ou contactez votre conseiller référent Pôle Emploi";
			$array['descinfo']="Le dispositif intitulé « Chèque Formation Réussite » a pour objectif de répondre aux différentes demandes individuelles de formation des demandeurs d’emploi qui ont un projet professionnel clairement défini. En effet, cette aide individuelle doit participer à l’aboutissement d’un projet d’insertion professionnelle et de création d’activité à court terme. Aussi, le critère essentiel d’appréciation de l’opportunité demeure la faisabilité du projet professionnel.";
			$array['info']="Le bénéficiaire du Chèque Formation Réussite dispose du statut de stagiaire de la formation professionnelle mais ne perçoit aucune rémunération de la part du Conseil régional.<br />Le dispositif « Chèque Formation Réussite » comprend trois types de chèque :<br />Le chèque Formation pour vous permettre d’accéder à une formation ;<br />Le chèque VAE, pour financer votre accompagnement VAE ;<br />Le chèque Langues Étrangères pour suivre une formation en langue étrangère.";
			$array['cost']="Formation gratuite si côut &lt; ou = 3000 €, reste à charge possible.  Possibilité de faire un co-financement avec un autre dispositif. Contacter les CCAS de la commune du DE pour un éventuel financement, Agefiph pour DEBOE ";
			$array['cost-plafond']=3000;
			if($situation_inscrit && !in_array($allocation_type,array('rsa')))
				if(!hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
					if(in_array($training_formacode,array(15254, 15287, 15288, 15299, 15298, 15289, 15297, 15234, 15213, 15214, 15205, 15206, 15204, 15203, 15259, 15228, 15229, 15219, 15209, 15207, 15227, 15274, 15260, 15290, 15286, 15247, 15238, 15239, 15236, 15237, 15248, 15233, 15240, 15278, 15221, 15212, 15215, 15220, 15210, 15202, 15222, 15201, 15282, 15291, 15293, 15292, 15285, 15284, 15294, 15283, 15296, 15295, 15276, 15277, 15218, 15208, 15249)))
					{
						$array['loc']=$locationName;
						arrayInsertAfterKey($droits,'finindividuel',$display,array('finindividuel_mayotte3'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'finindividuel_mayotte3',$droits);
						else
							remuTEXT($var,'finindividuel_mayotte3',$droits);
					}
		}

		/* Ligne 12 : POEC */
		if(isInRegionReunionMayotte($training_locationpath))
		{
			$array=array();
			$array=$display['poecollective'];
			$array['step']=array("Contactez votre conseiller référent emploi (Pôle emploi, Mission Locale ou Cap emploi) pour valider avec lui votre projet de formation.","Votre projet de formation et son financement doivent être présentés au plus tard 2 semaines avant le début de la formation");
			$array['descinfo']="Il s'agit d'une formation professionnalisante, qui répond à un besoin de main-d'oeuvre qualifiée exprimé par une branche, un secteur professionnel ou une profession... D'une durée maximale de 400h, elle inclut une période d'application en entreprise. Elle est gratuite et réservée aux demandeurs d'emploi";
			$array['info']="Les frais pédagogiques sont pris en charge par un OPCA (organisme paritaire collecteur agréé).<br/>La rémunération est prise en charge par Pôle emploi. Se renseigner auprès de Pôle emploi pour les conditions d'attribution d'aides à la mobilité et de frais de garde d'enfants pour les parents isolés (soumis à conditions)";
			$array['cost']="Gratuité";
			$display['poecollective']=$array;
			if($situation_inscrit)
				if($training_nbheurestotales && $training_nbheurestotales<=400)
					if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_OPCA,$nbPlaces))
					{
						$array['loc']=$locationName;
						arrayInsertAfterKey($droits,'poecollective',$display,array('poecollective_mayotte'=>$array));
						if($allocation_type=='are')
							remuAREF($var,'poecollective_mayotte',$droits);
						else
							remuRFPE2($var,'poecollective_mayotte',$droits);
					}
		}

		/* Ligne 13 : Action Collective Pôle emploi AFC PE */
		if(isInRegionReunionMayotte($training_locationpath))
		{
			$array=array();
			$array=$display['poleemploicollectif'];
			$array['step']="Contacter votre conseiller référent emploi";
			$array['descinfo']="Il s'agit d'un formation collective, délivrée en centre de formation avec une période de stage en entreprise, gratuite et réservée aux demandeurs d'emploi";
			$array['info']="Se renseigner auprès de Pôle emploi pour les conditions d'attribution d'aides à la mobilité et de frais de garde d'enfants pour les parents isolés (soumis à des conditions de revenus), si vous êtes retenu sur une des places financées par Pôle emploi.";
			$array['cost']="Formation Totalement financée";
			if($situation_inscrit)
				if(hasCodeFinanceur($training_codefinanceur,CODEFINANCEUR_POLE_EMPLOI,$nbPlaces))
				{
					$array['loc']=$locationName;
					arrayInsertAfterKey($droits,'poleemploicollectif',$display,array('poleemploicollectif_mayotte'=>$array));
					if($allocation_type=='are')
						remuAREF($var,'poleemploicollectif_mayotte',$droits);
					else
						remuRFPE2($var,'poleemploicollectif_mayotte',$droits);
				}
		}
	}
?>
