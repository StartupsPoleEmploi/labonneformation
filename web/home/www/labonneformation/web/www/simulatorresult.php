<?php
	//require_once(CLASS_PATH.'/simulatorrules.php');

	function stepFormat($step)
	{
		if(!is_array($step)) return $step;
		$col='';
		foreach($step as $s) $col.=sprintf('<li>%s</li>',$s);
		return "<ul>$col</ul>";
	}
	function displayField($lineParser,$convBullet=false)
	{
		return Tools::displayField($lineParser,$convBullet);
	}
	function displayRaw($lines)
	{
		foreach($lines as $line)
			echo str_replace("\n",'\n',trim($line)).'\n';
	}

	/******************************************************************************************************************************/

	$t=microtime(true);
	$db=$this->getStore('read');
	$adSearch=new adSearch($db);
	$success=false;
	$locationParentLabel='';

	if($id=$this->get('id'))
	{
		//$ad=$adSearch->getById($id);
		$a=$adSearch->getByIdNew($id);
		if(!empty($a))
			$success=true;
	}
	// cas lorsque les paramètres proviennent de l'explorer trèfle
	elseif($id=$this->get('formation')['numero'])
	{
		$a=$adSearch->getByIdFormIntercarifNew($id);
		if(!empty($a))
			$success=true;
	}


	if(!$success)
	{
		$this->controller('/404.php');
		return;
	}

	$ar=new ArrayExplorer($a);
	$ref=new Reference($db);
	if($parentLocation=$ref->get('LOCATION',Reference::subPath($ar['sessions[0]/localisation/formation/path'],-1)))
		$locationParentLabel=array_values($parentLocation)[0]['label'];
	

	$simulatorRules=new SimulatorRules();
	//print_r($this->get());

	if($this->get('all',false))
	{
		$display=array(
			'droits'=>getList($db),
			'infocontact'=>array(),
			'pasdroits'=>array()
		);
		foreach($display['droits'] as $k=>$v)
		{
			$display['droits'][$k]['cost']='gratuit';
			$display['droits'][$k]['remu']='travail';
		}
	}
	else
		$display=$simulatorRules->getListeDispositifsFinancementDE($this,$ad,$ar);

	// Lien vers l'explorer trèfle
	$explainLink=array();
	if(is_array($display['trefleparams']))
		$explainLink=array('explainLink'=>array(
			'trefleLink'=>URL_API_TREFLE.'/explorer/simulateur#simulate/',
			'simulLink'=>$this->rewrite(
				'/simulatorresult.php',
				array_merge(array( 'cmd'=>'explain'),$display['trefleparams'])
				)
			)
		);

	$params=array(
		'ad'=>$ad,
		'ar'=>$ar,
		//'content'=>$ad['content'],
		//'orgaContent'=>$ad['orgacontent'],
		'locationParentLabel'=>$locationParentLabel,
		'locationLabel'=>$ar['sessions[0]/localisation/formation/ville'],
		'backLink'=>$this->rewrite('/simulatorform.php',array('ar'=>$ar,'step'=>0)),
		'detailLink'=>$this->rewrite('/detail.php',array('ar'=>$ar)),
		'criteria'=>$criteria,
		'droits'=>$display['droits'],
		'pasDroits'=>$display['pasdroits'],
		'infoContact'=>$display['infocontact'],
		'type'=>$display['type'],
		'page'=>'simulatorresult',
		'logoBeta'=>$this->get('salarie',false)
	)+$explainLink;
	

	_LOG('time_moteur.log',(microtime(true)-$t)."\n".print_r($_SERVER,true)."\n");
	
	// si erreur via api financement
	if(isset($display['erreur'])) $params+=array('erreur'=>$display['erreur']);

	switch($this->get('cmd','simul'))
	{
		default:
			$this->view('/simulatorresult_view.php',$params+array('engage'=>false,'display'=>'site'));
			break;
		case 'mail':
			$to=$this->get('mail','');
			if(filter_var($to,FILTER_VALIDATE_EMAIL))
			{
				$smtp=new QSmtp();
				$error=1;
				if($smtp->open())
				{
					$pdf_link = URL_BASE . $this->rw('/simulatorresult.php',array('cmd'=>'print')+$this->get(),true);
					$from=EMAIL_FROM; //'no-reply@'.DOMAIN;
					$pdf=$this->controller('/simulatorpdf.php',array('engage'=>true,'display'=>'print','print'=>false),true);
					$data=$this->view('/simulatormail_view.php',$params+array('from'=>$from,'to'=>$to,'display'=>'mail','pdf'=>$pdf,'pdf_link'=>$pdf_link),true);
					if($smtp->send($from,$to,$data))
						$error=0;
					$smtp->close();
				} else $error=2;
			} else $error=3;
			if(!$error) echo "Le message vous a été envoyé par email à l'adresse "._H($to,true);
			elseif($error==3) echo sprintf("Veuillez entrer une adresse email valide %s!",$to?sprintf("(%s) ",_H($to,true)):'');
			elseif($error==2) echo "Une erreur est survenue. Veuillez réessayer ultérieurement.";
			else echo sprintf("Impossible de vous envoyer un email à l'adresse %s !",_H($to,true));
			break;
		case 'print':
			$this->controller('/simulatorpdf.php',$params+array('engage'=>true,'display'=>'print','print'=>true));
			break;
		case 'engage':
			//$params['backLinkResult']=$this->rewrite('/simulatorresult.php',array('cmd'=>'simul','id'=>$ad['id'],'title'=>$ad['title'])+$this->get());
			$this->view('/simulatorresult_view.php',$params+array('engage'=>true,'display'=>'site'));
			break;
	}
?>
