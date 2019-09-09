<?php
	$page='simulator';

	function calcDate($monthToAdd)
	{
		$date=new DateTime();
		if($monthToAdd<0)
			$calc=$date->sub(new DateInterval(sprintf('P%dM',abs($monthToAdd))));
		else
			$calc=$date->add(new DateInterval(sprintf('P%dM',abs($monthToAdd))));
		return date_format($calc,'d/m/Y');
	}

	/* Chargement des données de l'annonce et si pas d'annonces, passage en 404 */
	$db=$this->getStore('read');
	$adSearch=new AdSearch($db);
	if($adId=$this->get('id'))
		if(!($a=$adSearch->getByIdNew($adId)))
		{
			$this->controller('/404.php');
			return;
		}
	$ar=new ArrayExplorer($a);

    // test pour savoir si une formation est accessible à un salarié
	$financeurs=$ar['sessions[0]/financeurs,array()']->toArray();
	$isFormationSalarie=empty($financeurs);
	if(!$isFormationSalarie && array_walk($financeurs,function(&$v) {$v=(in_array($v['code'],array(0,5,10)))?$v:'';}))
		$isFormationSalarie=!empty(array_filter($financeurs));


	$session=$this->getSession();

	$step=intval($this->get('step',1));
	$etatForm=$this->getPOST('etat-form',false);

	/* Fusionne les données de session pour les rendre accessibles au post comme si elles provenaient d'un formulaire sur une seule page */
	$post=array();
	$sessionData=array();
	foreach($session->get() as $k=>$v)
		if(preg_match('#^step-(\d)+$#',$k,$m))
			if(intval($m[1])!=$step || !$etatForm) //les données de sessions de l'etape ne sont pas reprises sauf si ce n'est pas un post.
				$sessionData=$v+$sessionData;

	$this->setPOST($this->getPOST()+$sessionData); //Fusion avec le post actuel de la page.

	/* Inclusion de l'instance $form=new Form() alimentée: $step doit etre alimenté */
	require_once('inc/simulator_inc.php');
	$form=new EnhancedQForm();
	setForm($form,$step,$this);


	$errors=array();
	if($etatForm) //Pour ne pas tester les champs s'il n'y a pas eu de post
	{
		$namesVisibles=preg_split('# +#',trim($this->get('visible','')));
		$errors=$form->control($namesVisibles);

		/* Controle supplémentaire */
		if($step==1 && $this->get('salarie'))
		{
			if(!$isFormationSalarie) // ce n'est pas une formation salarié on propose de refaire une recherche
                		$errors=array('page'=>"noaccess-salarie");

			// on ne prend pas en compte les eventuels erreur formulaire DE
			if(!empty($errors))
				if(in_array('situation_inscritcumuldureeinscriptionsur12mois',array_keys($errors)) ||
				   in_array('allocation_type',array_keys($errors)))
					$errors=array();
		}

		if($step==2 && $this->get('inscritDE'))
			if(!$this->get('domicilepath',false))
			{
				$errors['domicilepath']='mandatory';
				$errors['location']='mandatory';
			}

		if($step==3)
			if(!$this->get('situation_cpfconnu',false))
				$errors['situation_cpfconnu']='mandatory';

		if($step==4)
			if($this->get('situation_contrataide',false)=='on' && !$this->get('situation_personneencourscontrataide',false))
				$errors['situation_personneencourscontrataide']='mandatory';

		if($step==5 && $this->get('salarie'))
			if(!$this->get('entrepriselocationinsee',false))
			{
				$errors['entrepriselocationinsee']='mandatory';
				$errors['commune-entreprise']='mandatory';
			}

		if(empty($errors))
		{
			/* Si tout est ok, on va injecter le résultat du post en session */
			$post=$this->getPOST();

			/* Pour avoir le bon type de formulaire DE / Salarie */
			if($step==1)
			{
				if($post['situation_inscrit'] && $sessionData['salarie'] || !$post['situation_inscrit'] && $sessionData['inscritDE'])
					$session->clear();
				$formInscritType=array('inscritDE'=>($post['situation_inscrit']?'on':''),'salarie'=>$post['situation_inscrit']?'':'on');
			}
			else
				$formInscritType=array('inscritDE'=>$post['inscritDE'],'salarie'=>$post['salarie']);

			/* D'abord, on ne garde que les visibles et le type de formulaire */
			$postVisible=array();
			foreach($namesVisibles as $name)
				if(array_key_exists($name,$post))
					$postVisible[$name]=$post[$name];
			$post=$postVisible+$formInscritType;

			unset($post['id'],$post['visible'],$post['etat-form'],$post['step']);

			/* Et ensuite on sauve le post de l'étape en session */
			$session->set("step-$step",$post);
			//error_log(print_r($namesVisibles,true));
			//error_log(print_r($post,true));
			//print_r($session->get()); die;

			if($step<5)
			{
				$this->forward($this->rewrite('/simulatorform.php',array_merge(array('ar'=>$ar,'step'=>$step+1))),301,true);
			} else
			{
				$params=array();
				$sess=$this->getSession();
				/* Assemblage des données des étapes */
				for($i=1;$i<=5;$i++)
				{
					$data=$sess->get("step-$i",array());
					//$params=array_merge($params,$data);
					$params+=$data;
				}
				/* Si une anomalie dans les données en session est détectée, on revient en step 0 */
				if(empty($params))
				{
					$this->forward($this->rewrite('/simulatorform.php',
						array(
							'ar'=>$ar,
							'step'=>0
						)),301);
					return;
				}

				$this->forward($this->rewrite('/simulatorresult.php',
					array_merge(
						array(
							'id'=>$ar['id'],
							'idformintercarif'=>$ar['uid'],
							'cmd'=>'engage'
						),
						$params)
					),301,true);
			}
		} else
			_QUARKLOG('champs_rouge.log',print_r($this->get(),true).print_r($errors,true)."\n");
	}

	$backLink=$this->rewrite('/detail.php',array('ar'=>$ar));
	$this->view('/simulatorform_view.php',
		array(
			'ar'=>$ar,
			//'content'=>$content,
			//'orgaContent'=>$orgaContent,
			'backLink'=>$backLink,
			'errors'=>$errors,
			'step'=>$step,
			'form'=>$form,
			'hist'=>serialize($hist),
			'page'=>'simulatorform',
			'logoBeta'=>$this->get('salarie',false)
		));
?>
