<?php
	/*
	=> Home
	Renseignez votre situation pour connaître les possibilités de financement de la formation XXX

	Commencer

	=> Votre situation professionnelle :
	Vous êtes inscris…
	Vous êtes salarié…
	Type d'allocation

	=> Vous :
	Date de naissance
	Domicile
	Niveau d’étude (si présent)



	=> Votre CPF :

	=> Votre expérience professionnelle :

	=> Vos situations particulières :

	Transverses aux étapes :
	- Barre de progression à 5 étapes
	- Pas de bouton retour pour l'instant
	- Bouton « Valider » pour passer à l’étape suivante
	- Le bouton « Valider » est grisé si il y a des questions obligatoires
	*/

	//$step=$this->get('step',1);
	//$hist=$this->getPOST('hist',array());
	//$post=$this->getPOST();
	//unset($post['hist']);
	//$hist=Tools::compressArgs($hist);
	function setForm($form,$step,$quark)
	{
		if($step==1)
		{
			$step1=$form->group('step-1')->getForm();
				$group1=$step1->group('group1','Situation')->getForm();
				$group1->add('hidden','inscritDE')->value($quark->get('situation_inscrit')?'on':'');
				$group1->add('hidden','salarie')->value($quark->get('situation_inscrit')?'':'on');
					$group1->add('radio','situation_inscrit','situation_inscrit1')->value(1)->checked($quark->get('inscritDE',''))->attr(array('required'=>'required','label-after'=>'Vous êtes inscrit(e) comme demandeur d\'emploi','onclick'=>'track(\'FORM FINANCEMENT DEMANDEUR EMPLOI - ETAPE 1\')'));
						$group1->add('select','situation_inscritcumuldureeinscriptionsur12mois')->selected($quark->get('situation_inscritcumuldureeinscriptionsur12mois','-'))->attr('label-before',"Depuis combien de temps, êtes-vous inscrit à Pôle Emploi ?")->attr('indent','2')
						       ->condition(array('showif'=>'situation_inscrit1.checked','dontmatch'=>'#^-$#'))
							   ->option('-','-')
						       ->option('1','de 1 à 3 mois')
						       ->option('3','de 3 à 6 mois')
						       ->option('6','de 6 à 12 mois')
						       ->option('12','12 mois et plus');
					//$group1->add('checkbox','situation_inscritdepuisaumoins6moissur12mois')->checked($quark->get('situation_inscritdepuisaumoins6moissur12mois',''))->attr('label-after',"Vous êtes inscrit(e) comme demandeur d'emploi depuis au moins 6 mois au cours des 12 derniers mois")->attr('indent','2')
					       //->condition(array('showif'=>'situation_inscrit.checked'));
					$group2=$group1->group('group2')->condition(array('showif'=>'situation_inscrit1.checked'))->attr('indent','2')->getForm();
						$group2->add('select','allocation_type')->selected($quark->get('allocation_type','-'))->attr('label-before',"Type d'allocation")->condition(array('dontmatch'=>'#^-$#'))->attr('style','font-weight:normal;')
					           ->option('-','-')
					           ->option('non','Non indemnisé')
					           ->option('are',"Allocation d'aide au Retour à l'Emploi (ARE)")
					           ->option('ass',"Allocation de Solidarité Spécifique (ASS)")
					           ->option('rsa',"Revenu de Solidarité Active (RSA)")
					           //->option('ata',"Allocation Temporaire d'Attente (ATA)")
					           //->option('aah',"Allocation Adulte Handicapé (AAH)")
					           ->option('asr',"Allocation Spécifique de Reclassement (ASR)")
					           ->option('atp',"Allocation de Transition Professionnelle (ATP)")
					           ->option('asp',"Allocation de Sécurisation Professionnelle (ASP)")
					           ->option('aex','Allocation ex-employeur secteur public');
						$group22=$group2->group('group22')->condition(array('showif'=>'allocation_type.val!="-" && allocation_type.val!="non"'))->getForm();
							$group22->add('date','allocation_dateend')->value($quark->get('allocation_dateend',''))->attr(array('placeholder'=>'JJ/MM/AAAA','class'=>'date'))->attr('label-before',"Date de fin estimée de votre indemnisation")->attr('indent','1')
							        ->condition(array('showif'=>'allocation_type.val!="rsa"','min'=>date('d/m/Y'),'match'=>'#^\d{1,2}/\d{1,2}/\d{4}$#'));
							$group22->add('number','allocation_cost')->value($quark->get('allocation_cost',''))->attr('label-before',"Montant mensuel de votre allocation")->attr('placeholder','arrondi à l\'€ net / mois')->attr('indent','1')->attr('min','0')
									->condition(array('match'=>'#^[0-9.]+$#'));					 

						$group1->add('label','situation_salarie_label')->content("<br/><span class=\"new\">Nouveau :</span>&nbsp;version bêta pour les salariés: service en cours de construction",true);
						$group1->add('radio','situation_inscrit','situation_inscrit2')->value(0)->checked($quark->get('salarie',''))->attr(array('required'=>'required','label-after'=>'Vous êtes salarié(e) de droit privé','onclick'=>'track(\'FORM FINANCEMENT SALARIE - ETAPE 1\')'));


				//$group2=$step1->group('group2',"Type d'allocation")->condition(array('showif'=>'situation_inscrit.checked'))->getForm();
				//	$group2->add('select','allocation_type')->selected($quark->get('allocation_type','-'))->condition(array('dontmatch'=>'#^-$#'))
				//           ->option('-','-')
				//           ->option('non','Non indemnisé')
				//           ->option('are',"Allocation d'aide au Retour à l'Emploi (ARE)")
				//           ->option('ass',"Allocation de Solidarité Spécifique (ASS)")
				//           ->option('rsa',"Revenu de Solidarité Active (RSA)")
				//           ->option('ata',"Allocation Temporaire d'Attente (ATA)")
				//           //->option('aah',"Allocation Adulte Handicapé (AAH)")
				//           ->option('asr',"Allocation Spécifique de Reclassement (ASR)")
				//           ->option('atp',"Allocation de Transition Professionnelle (ATP)")
				//           ->option('asp',"Allocation de Sécurisation Professionnelle (ASP)")
				//           ->option('aex','Allocation ex-employeur secteur public');
				//	$group22=$group2->group('group22')->condition(array('showif'=>'allocation_type.val!="-" && allocation_type.val!="non"'))->getForm();
				//		$group22->add('date','allocation_dateend')->value($quark->get('allocation_dateend',''))->attr(array('placeholder'=>'JJ/MM/AAAA','class'=>'date'))->attr('label-before',"Date de fin estimée de votre indemnisation")->attr('indent','1')
				//		        ->condition(array('showif'=>'allocation_type.val!="rsa"','min'=>date('d/m/Y'),'match'=>'#^\d{1,2}/\d{1,2}/\d{4}$#'));
				//		$group22->add('number','allocation_cost')->value($quark->get('allocation_cost',''))->attr('label-before',"Montant mensuel de votre allocation")->attr('placeholder','€ net / mois')->attr('indent','1')
				//		        ->condition(array('match'=>'#^[0-9.]+$#'));
		}
		if($step==2)
		{
			$step2=$form->group('step-2')->getForm();
				$step2->add('hidden','inscritDE')->value($quark->get('inscritDE'));
				$step2->add('hidden','salarie')->value($quark->get('salarie'));
				$step2->add('hidden','domicilepath')->value($quark->get('domicilepath'));
				$step2->add('hidden','allocation_type')->value($quark->get('allocation_type'));
				//$step2->add('hidden','hist')->value($hist);
				$group3=$step2->group('group3','Date de naissance')->getForm();
					$date_naissance_max = date('d/m/Y',strtotime('- 16 years',time()));
					$group3->add('date','birthdate')->value($quark->get('birthdate'))->attr(array('title'=>'Format : JJ/MM/AAAA','required'=>'required','pattern'=>'^\d{1,2}/\d{1,2}/\d{4}$','placeholder'=>'JJ/MM/AAAA','class'=>'date'))
					       ->condition(array('min'=>'1/1/1900','max'=>$date_naissance_max,'match'=>'#^\d{1,2}/\d{1,2}/\d{4}$#'));

				if($quark->get('inscritDE'))
				{
					$group4=$step2->group('group4','Domicile')->getForm();
					$group4->add('text','location')->value(trim($quark->get('location')))->attr('placeholder','Votre code postal')->attr('required','required')->attr('onfocus',"$('#locationpath').val(''); this.value='';")
						   ;//->condition(array('match'=>'#^[0-9a-zA-Z ]+$#'));
					//$group4->add('label','location_label')->content("<span class=\"highlight\">Attention</span>&nbsp;: L'outil intègre, actuellement, l’ensemble des règles nationales de financement de formation et les règles spécifiques de la région Pays-de-Loire.<br/>Dans tous les cas, consulter un conseiller emploi pour connaître les spécificités éventuelles des financements dans votre région",true)->attr('class','situationlaius')
					//       ->condition(array('showif'=>'domicilepath.val!="" && $.inArray(domicilepath.val.subPath(3),['.preg_replace('#[0-9/]+#','"$0"',$regionsImplementees).'])==-1'));
					$group5=$step2->group('group5',"Votre niveau d'étude")
								  //->condition(array('showif'=>'birthdate.age>=15 && birthdate.age<26 && birthdate.val!=""'))
								  ->getForm();
						$group5->add('select','niveauscolaire')->selected($quark->get('niveauscolaire','-'))->condition(array('dontmatch'=>'#^-$#'))
							   ->option('-','-')
							   ->option('2','Sans diplôme (VI)')
							   ->option('3','Préqualification (V bis)')
							   ->option('4','CAP, BEP, CFPA du premier degré (V)')
							   ->option('5',"BP, BT, baccalauréat professionnel ou technologique (IV)")
							   ->option('5.1',"Baccalauréat général (IV)")
							   ->option('6',"BTS, DUT (III)")
							   ->option('7',"Licence ou master 1 (II)")
							   ->option('8',"Supérieur ou égal à master 2 (I)")
							   ->option('1','Sans niveau spécifique');
				}
				if($quark->get('salarie')=='on')
				{
						$sGroup6=$step2->group('sGroup6',"Votre rémunération mensuelle actuelle")
					              ->getForm();
							$sGroup6->add('number','salaire')->value(trim($quark->get('salaire')))->condition(array('min'=>1))->attr('placeholder','salaire arrondi à l\'€ net / mois')->attr('min','0');
							$sGroup6->add('hidden','droitprive')->value('1');
				}
			}
		if($step==3)
		{
			$step3=$form->group('step-3')->getForm();
				$step3->add('hidden','birthdate')->value($quark->get('birthdate'));
				$step3->add('hidden','allocation_type')->value($quark->get('allocation_type'));
				$step3->add('hidden','inscritDE')->value($quark->get('inscritDE'));
				$step3->add('hidden','salarie')->value($quark->get('salarie'));
				//$step3->add('hidden','hist')->value($hist);
				$group6=$step3->group('group6',"Compte personnel de formation (CPF)")->getForm();
					$group6->add('radio','situation_cpfconnu','cpf1')->value('cpfconnu')->checked($quark->get('situation_cpfconnu')=='cpfconnu'?true:false)->attr('label-after',"Vous connaissez vos droits à formation CPF en euros")
					       ;//->condition(array('mandatory'=>true));
						$group6->add('number','situation_creditheurescpf')->value($quark->get('situation_creditheurescpf'))->attr('placeholder','Votre crédit en euro')->attr('indent','1')->attr('min','0')
						       ->condition(array('showif'=>'cpf1.checked && !cpf2.checked && !cpf3.checked','match'=>'#^\d+$#','min'=>0));
						$group6->add('label','situation_cpfconnu_label')
						       ->content("Attention : assurez-vous bien d'avoir <a href=\"https://www.moncompteformation.gouv.fr/espace-prive/html/#/compte-utilisateur/connexion\" target=\"_blank\">activé votre Compte Personnel Formation</a> pour pouvoir mobiliser vos heures CPF",true)->attr('indent','1')
						       ->condition(array('showif'=>'cpf1.checked && !cpf2.checked && !cpf3.checked && situation_creditheurescpf.val!=""','match'=>'#^\d+$#'));
					$group6->add('radio','situation_cpfconnu','cpf2')->value('cpfinconnu')->checked($quark->get('situation_cpfconnu')=='cpfinconnu'?true:false)->attr('label-after',"Vous ne connaissez pas vos droits à formation CPF en euros")
					       ;//->condition(array('mandatory'=>true));
						$group6->add('label','situation_creditheurescpfconnu_label')
						       ->content("Activez votre <a href=\"https://www.moncompteformation.gouv.fr/espace-prive/html/#/compte-utilisateur/connexion\" target=\"_blank\">Compte Personnel Formation (CPF)</a>.",true)->attr('indent','1')
						       ->condition(array('showif'=>'cpf2.checked && !cpf1.checked && !cpf3.checked'));
					$group6->add('radio','situation_cpfconnu','cpf3')->value('cpfempty')->checked($quark->get('situation_cpfconnu')=='cpfempty'?true:false)->attr('label-after',"Vous n'avez pas de crédit de formation CPF");
		}
		if($step==4)
		{
			$step4=$form->group('step-4')->getForm();
				$step4->add('hidden','inscritDE')->value($quark->get('inscritDE'));
				$step4->add('hidden','salarie')->value($quark->get('salarie'));
				$step4->add('hidden','birthdate')->value($quark->get('birthdate'));
				$step4->add('hidden','allocation_type')->value($quark->get('allocation_type'));
				if($quark->get('inscritDE')=='on')
				{
					$group7=$step4->group('group7','Expérience professionnelle')->getForm();
						$group7->add('checkbox','situation_contratapprentissage')->checked($quark->get('situation_contratapprentissage'))->attr('label-after',"Vous avez déjà réalisé un contrat d'apprentissage terminé après le ".calcDate(-12))
								   ->condition(array('showif'=>'birthdate.age>=16 && birthdate.age<=30'));
							$group7->add('select','situation_contratapprentissagetype')->selected($quark->get('situation_contratapprentissagetype','-'))->attr('label-before',"...de niveau")->attr('indent','1')
								   ->condition(array('showif'=>'situation_contratapprentissage.checked','dontmatch'=>'#^-$#'))
								   ->option('-','-')
								   ->option('2','Sans diplôme (VI)')
								   ->option('3','Préqualification (V bis)')
								   ->option('4','CAP, BEP, CFPA du premier degré (V)')
								   ->option('5',"BP, BT, baccalauréat professionnel ou technologique (IV)")
								   ->option('5.1',"Baccalauréat général (IV)")
								   ->option('6',"BTS, DUT (III)")
								   ->option('7',"Licence ou master 1 (II)")
								   ->option('8',"Supérieur ou égal à master 2 (I)")
								   ->option('1','Sans niveau spécifique');
							$group7->add('checkbox','situation_rupturecontratapprentissage')->checked($quark->get('situation_rupturecontratapprentissage'))->attr('indent','1')->attr('label-after',"Ce contrat d'apprentissage a été rompu indépendamment de votre volonté")
								   ->condition(array('showif'=>'situation_contratapprentissage.checked'));

						$group7->add('checkbox','situation_cdd')->checked($quark->get('situation_cdd'))->attr('label-after',"Vous avez terminé un contrat CDD depuis moins d'un an");
							$group71=$group7->group('group71')->condition(array('showif'=>'situation_cdd.checked'))->getForm();

							$group71->add('checkbox','situation_cdd5years')->checked($quark->get('situation_cdd5years'))->attr('label-after',"...et vous totalisez au moins 24 mois de travail&nbsp;<span class=\"mini-info\"></span> depuis le ".calcDate(-5*12))->attr('indent','1');
								$group71->add('checkbox','situation_cdd12months')->checked($quark->get('situation_cdd12months'))->attr('label-after',"...dont au moins 4 mois de travail de CDD depuis le ".calcDate(-12))->attr('indent','2')
										->condition(array('showif'=>'situation_cdd5years.checked'));
									//$group71->add('date','situation_cdddatedefin')->value($quark->get('situation_cdddatedefin'))->attr('label-before','date de fin de votre dernier CDD :')->attr(array('placeholder'=>'JJ/MM/AAAA','class'=>'date'))->attr('indent','3')
											//->condition(array('showif'=>'situation_cdd12months.checked && situation_cdd5years.checked && birthdate.age>=26','min'=>'1/1/1900','max'=>date('d/m/Y'),'match'=>'#^\d{1,2}/\d{1,2}/\d{4}$#'));
									$group71->add('number','situation_salairebrutecdd')->value($quark->get('situation_salairebrutecdd'))->attr(array('label-before'=>"salaire moyen perçu au cours des 4 derniers mois du CDD :",'placeholder'=>"€ brut / mois"))->attr('indent','3')->attr('min','0')
											->condition(array('showif'=>'situation_cdd12months.checked && situation_cdd5years.checked','match'=>'#^\d+$#'));
								$group71->add('label','situation_cdd5years_label')->content("<span class=\"asterisk\"><span class=\"mini-info middle\"></span></span> hors contrats d'apprentissage, de professionnalisation, d'accompagnement dans l'emploi (CAE),<br>contrats d'avenir, et hors contrats conclus au cours des cursus scolaires",true)->attr('class','asterisk-label')->attr('indent',2)
									;//->condition(array('showif'=>'birthdate.age>=26'));

						#$group7->add('checkbox','situation_interim')->checked($quark->get('situation_interim'))->attr('label-after','Vous avez une expérience comme intérimaire');
						#$group7->add('checkbox','situation_interim1600h')->checked($quark->get('situation_interim1600h'))->attr('indent','1')->attr('label-after',"...et vous avez travaillé au moins 1600 heures comme intérimaire depuis le ".calcDate(-18))
						#	   ->condition(array('showif'=>'situation_interim.checked'));
						#	$group7->add('checkbox','situation_interim600h')->checked($quark->get('situation_interim600h'))->attr('indent','2')->attr('label-after','...dont au moins 600 heures dans la même entreprise de travail temporaire')
						#		   ->condition(array('showif'=>'situation_interim.checked && situation_interim1600h.checked'));
						#		$group7->add('date','situation_interimdateend')->value($quark->get('situation_interimdateend'))->attr('class','date')->attr('label-before','date de fin de votre dernière mission ou mission en cours')->attr(array('placeholder'=>'JJ/MM/AAAA','class'=>'date'))->condition(array('min'=>'1/1/1900','match'=>'#^\d{1,2}/\d{1,2}/\d{4}$#'))->attr('indent','4')
						#			   ->condition(array('showif'=>'situation_interim.checked && situation_interim1600h.checked && situation_interim600h.checked','min'=>'1/1/1900','match'=>'#^\d{1,2}/\d{1,2}/\d{4}$#'));
						#		$group7->add('number','situation_salairebruteinterim')->value($quark->get('situation_salairebruteinterim'))->attr(array('label-before'=>"salaire moyen brut mensuel perçu au cours des 4 derniers mois en interim :",'placeholder'=>"€ brut / mois"))->attr('indent','4')
						#			   ->condition(array('showif'=>'situation_interim.checked && situation_interim1600h.checked && situation_interim600h.checked','match'=>'#^\d+$#'));

						$group7->add('checkbox','situation_contrataide')->checked($quark->get('situation_contrataide'))->attr('label-after',"Vous bénéficiez ou avez déjà bénéficié d'un contrat aidé <span class=\"mini-info\"></span>");
						$group72=$group7->group('group72')->condition(array('showif'=>'situation_contrataide.checked'))->attr('indent','1')->getForm();
							$group72->add('radio','situation_personneencourscontrataide','contrataide1')->value('oui')->checked($quark->get('situation_personneencourscontrataide')=='oui')->attr('label-after',"Votre contrat aidé est en cours");
							$group72->add('radio','situation_personneencourscontrataide','contrataide2')->value('non')->checked($quark->get('situation_personneencourscontrataide')=='non')->attr('label-after',"Votre contrat aidé est achevé");
							$group72->add('label','situation_personneencourscontrataide_label')->content("<span class=\"asterisk\"><span class=\"mini-info middle\"></span></span> Contrat d’accompagnement dans l’emploi (CUI-CAE) ou contrat initiative emploi (CUI-CIE) ou emploi d'avenir",true)->attr('class','asterisk-label');

						$group7->add('checkbox','situation_6moissur12')->checked($quark->get('situation_6moissur12'))->attr('label-after',"Vous avez déjà travaillé pendant 6 mois sur une période de 12 mois ou pendant 12 mois sur 24 mois")
							   ->condition(array('showif'=>'allocation_type.val=="ass" || allocation_type.val=="ata" || allocation_type.val=="rsa" || allocation_type.val=="non"'));
				}
				if($quark->get('salarie')=='on')
				{
					$sGroup7=$step4->group('sGroup7',"Type de contrat de travail")
								  //->condition(array('showif'=>'birthdate.age>=15 && birthdate.age<26 && birthdate.val!=""'))
								  ->getForm();
						$sGroup7->add('select','contrat')->selected($quark->get('contrat','-'))
							   ->option('','-')
							   ->option('cdd','Contrat à durée déterminée (CDD)')
							   ->option('cdi','Contrat à durée indéterminée (CDI)');
							 #  ->option('intermittent','Intermittent')
							 #  ->option('interim',"Intérim");
					$sGroup8=$step4->group('sGroup8',"Nombre de mois travaillés sur les 5 dernières années")->condition(array('showif'=>'contrat.val=="cdd"'))
					              ->getForm();
					$sGroup8->add('number','experience')->value(trim($quark->get('experience')))->attr('placeholder','Nombre de mois')->condition(/*array('match'=>'#^$#'),*/array('showif'=>'contrat.val'=="cdd",'min'=>0,'max'=>60))->attr('min','0');
					$sGroup8b=$step4->group('sGroup8b',"Nombre d'années d'ancienneté dans votre vie professionnelle")->condition(array('showif'=>'contrat.val=="cdi"'))
					              ->getForm();
					$sGroup8b->add('number','experienceannee')->value(trim($quark->get('experienceannee')))->attr('placeholder','Nombre d\'années')->condition(/*array('match'=>'#^$#'),*/array('showif'=>'contrat.val'=="cdi",'min'=>0))->attr('min','0');
					//$sGroup7->add('checkbox','experience')->checked($quark->get('experience'))->attr('label-after',"...et vous totalisez au moins 12 mois de travail depuis le ".calcDate(-5*12))->attr('indent','1')
					//				->condition(array('showif'=>"birthdate.age<26 && birthdate.age>=15 && contrat.val=='cdd'"));
					//$sGroup7->add('checkbox','experience2')->checked($quark->get('experience'))->attr('label-after',"...et vous totalisez au moins 24 mois de travail")->attr('indent','1')->condition(array('showif'=>'contrat.val=="cdi"'));
					//$sGroup7->add('checkbox','experience2')->checked($quark->get('experience2'))->attr('label-after',"...et vous totalisez au moins 24 mois de travail depuis le ".calcDate(-5*12))->attr('indent','1')
					//				->condition(array('showif'=>"birthdate.age>=26 && contrat.val=='cdd'"));
					//$sGroup7->add('checkbox','moistravailleencdd')->checked($quark->get('moisentravailleencdd'))->attr('label-after',"...dont au moins 4 mois de travail de CDD depuis le ".calcDate(-12))->attr('indent','2')
					//				->condition(array('showif'=>'(experience.checked || experience2.checked) && contrat.val=="cdd"'));
					$sGroup9=$step4->group('sGroup9',"Nombre de mois travaillés sur un contrat CDD sur les 12 derniers mois")->condition(array('showif'=>'contrat.val=="cdd"'))
					              ->getForm();
					$sGroup9->add('number','moistravailleencdd')->value(trim($quark->get('moistravailleencdd')))->attr('placeholder','Nombre de mois')->condition(array('showif'=>'contrat.val=="cdd"','min'=>0,'max'=>12))->attr('min','0');
					$sGroup10=$step4->group('sGroup9b',"Nombre d'années d'ancienneté dans l'entreprise actuelle")->condition(array('showif'=>'contrat.val=="cdi"'))
					              ->getForm();
					$sGroup10->add('number','ancienneteentrepriseactuelle')->value(trim($quark->get('ancienneteentrepriseactuelle')))->attr('placeholder','Nombre d\'années')->condition(array('showif'=>'contrat.val=="cdi"','min'=>0))->attr('min','0');
					$sGroup11=$step4->group('sGroup11',"Nombre d'heures travaillées dans votre entreprise d'intérim")->condition(array('showif'=>'contrat.val=="interim"'))
					              ->getForm();
					$sGroup11->add('number','heuresentreprise')->value(trim($quark->get('heuresentreprise')))->attr('placeholder','Nombre d\'heures')->condition(array('showif'=>'contrat.val=="interim"','max'=>3000))->attr('min','0');
					$sGroup12=$step4->group('sGroup12',"Nombre d'heures travaillées au cours des 18 derniers mois")->condition(array('showif'=>'contrat.val=="interim"'))
					              ->getForm();
					$sGroup12->add('number','heurestravaillees')->value(trim($quark->get('heurestravaillees')))->attr('placeholder','Nombre d\'heures')->condition(array('showif'=>'contrat.val=="interim"','max'=>3000))->attr('min','0');
				}
		}
		if($step==5)
		{
			$step5=$form->group('step-5')->getForm();
				$step5->add('hidden','inscritDE')->value($quark->get('inscritDE'));
				$step5->add('hidden','salarie')->value($quark->get('salarie'));
				$step5->add('hidden','birthdate')->value($quark->get('birthdate'));
				$step5->add('hidden','allocation_type')->value($quark->getPOST('allocation_type'));
				if($quark->get('inscritDE')=='on')
				{
					$group8=$step5->group('group8','Situation particulière')->getForm();
						$group8->add('checkbox','situation_th')->checked($quark->get('situation_th'))->attr('label-after','Vous êtes reconnu travailleur handicapé');
						$group8->add('checkbox','situation_travailleurnonsal12dont6dans3ans')->checked($quark->get('situation_travailleurnonsal12dont6dans3ans'))->attr('label-after',"Vous êtes un travailleur non salarié et vous avez travaillé durant 12 mois, dont 6 mois consécutifs, dans les 3 ans précédant l’entrée en stage")
							   ->condition(array('showif'=>'allocation_type.val=="ass" || allocation_type.val=="ata" || allocation_type.val=="rsa" || allocation_type.val=="non"'));
						$group8->add('checkbox','situation_parentisole')->checked($quark->get('situation_parentisole'))->attr('label-after',"Vous êtes parent isolé <span class=\"mini-info\"></span>")
							   ->condition(array('showif'=>'allocation_type.val=="ass" || allocation_type.val=="ata" || allocation_type.val=="rsa" || allocation_type.val=="non"'));
						$group8->add('label','situation_parentisole_label')->content("<span class=\"asterisk\"><span class=\"mini-info middle\"></span></span> Personnes veuves, divorcées, séparées, abandonnées ou célibataires assumant seules la charge effective et permanente d’un ou de plusieurs enfants résidant en France et Femmes seules enceintes ayant effectué la déclaration de grossesse et les examens prénataux prévus par la loi",true)->attr('class','asterisk-label');
						$group8->add('checkbox','situation_mere3enfants')->checked($quark->get('situation_mere3enfants'))->attr('label-after',"Vous êtes mère de famille ayant eu au moins 3 enfants")
							   ->condition(array('showif'=>'allocation_type.val=="ass" || allocation_type.val=="ata" || allocation_type.val=="rsa" || allocation_type.val=="non"'));
						$group8->add('checkbox','situation_divorceeveuve')->checked($quark->get('situation_divorceeveuve'))->attr('label-after',"Vous êtes une femme divorcée, veuve, ou séparée judiciairement depuis moins de 3 ans")
							   ->condition(array('showif'=>'allocation_type.val=="ass" || allocation_type.val=="ata" || allocation_type.val=="rsa" || allocation_type.val=="non"'));
						$group8->add('checkbox','situation_projetcreationentreprise')->checked($quark->get('situation_projetcreationentreprise'))->attr('label-after',"Vous avez un projet de création d'entreprise qui nécessite cette formation");
						$group8->add('checkbox','situation_vaepartiellemoins5ans')->checked($quark->get('situation_vaepartiellemoins5ans'))->attr('label-after',"Vous souhaitez terminer cette formation pour laquelle vous avez obtenu, il y a moins de 5 ans, une certification partielle par un jury VAE");
				}
				if($quark->get('salarie')=='on')
				{
					$sGroup11=$step5->group('sGroup11','Votre entreprise')->getForm();
						$sGroup11->add('label','info')->content("<br/>&nbsp;Munissez-vous de votre&nbsp;<span class=\"highlight\">bulletin de paie</span>&nbsp;pour renseigner les informations qui suivent :<br/>&nbsp;Le code <span class=\"highlight\">ape/naf</span> ou <span class=\"highlight\">idcc</span> doit être composé de 4 chiffres et être éventuellement terminé par une lettre<br/>",true);
						$sGroup11->add('text','naf')->value(trim($quark->get('naf')))->attr(array('label-after'=>'<span class="fa fa-info-circle" id="naf-tooltip" data-toggle="tooltip"></span>','label-before'=>'code ape/naf','required'=>'required'))->condition(array('match'=>'#^\d{4}[A-Za-z]?$#'));
						$sGroup11->add('text','idcc')->value(trim($quark->get('idcc')))->attr(array('required'=>'required','label-after'=>'<span class="fa fa-info-circle" id="idcc-tooltip" data-toggle="tooltip"></span>','label-before'=>'secteur d\'activité ou code idcc'))->condition(array('match'=>'#^\d{3,4}[A-Za-z]?$#'));
						$sGroup11->add('text','commune-entreprise')->value(trim($quark->get('commune-entreprise')))->attr(array('required'=>'required','label-before'=>'la commune de votre entreprise'))->attr('onfocus',"$('#commune').val(''); this.value='';")/*->condition(array('match'=>'#^\d{3,4}[A-Za-z]?$#'))*/;
						$sGroup11->add('hidden','entrepriselocationinsee')->value($quark->get('entrepriselocationinsee'));
						//$sGroup11->add('select','region')->selected($quark->get('region','-'))
						//         ->condition(array('dontmatch'=>'#^$#'))
						//         ->option('','Région')
						//         ->option('11','Île-de-France')
						//         ->option('24','Centre-Val de Loire')
						//         ->option('27','Bourgogne-Franche-Comté')
						//         ->option('28','Normandie')
						//         ->option('32','Hauts-de-France')
						//         ->option('44','Grand Est')
						//         ->option('52','Pays de la Loire')
						//         ->option('53','Bretagne')
						//         ->option('75','Nouvelle-Aquitaine')
						//         ->option('76','Occitanie')
						//         ->option('84','Auvergne-Rhône-Alpes')
						//         ->option('93','Provence-Alpes-Côte d\'Azur')
						//         ->option('94','Corse')
						//         ->option('01','Guadeloupe')
						//         ->option('02','Martinique')
						//         ->option('03','Guyane')
						//         ->option('04','La Réunion')
						//         ->option('06','Mayotte');

				}
		}
		return $form;
	}
?>
