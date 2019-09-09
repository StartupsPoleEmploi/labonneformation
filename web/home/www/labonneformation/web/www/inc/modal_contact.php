<?php
	$db=$this->getStore('read');
	$adSearch=new adSearch($db);
	$a=$adSearch->getByIdNew($this->get('id',''));
	$ar=new ArrayExplorer($a);
	$dest=$ar['sessions[0]/contact/email:'.$ar['organisme/contact/email']];

	$from_lbf=EMAIL_FROM;
	$to=$dest;
	$expediteur=$this->get('exp','');
	if(filter_var($to,FILTER_VALIDATE_EMAIL) && filter_var($expediteur,FILTER_VALIDATE_EMAIL))
	{
			$smtp=new QSmtp();
			$error=1;
			if($smtp->open())
			{
				$copie=$this->get('cop','');
				$objet=$this->get('obj','');
				$message=$this->get('mes','');
				$url=$this->get('url','');
				$type=$this->get('type','');
				switch ($type){
					case "orga":
						$data=$this->view('inc/mail_orga_view.php',array('from'=>$expediteur,'to'=>$to,'objet'=>$objet,'message'=>$message,'url'=>$url),true);
						break;
					case "lbf":
						$data=$this->view('inc/mail_lbf_view.php',array('from'=>$expediteur,'to'=>$to,'objet'=>$objet,'message'=>$message,'url'=>$url),true);
						break;
				}
				if($copie=="true") {
					if($smtp->send($from_lbf,$to,$data) && $smtp->send($from_lbf,$expediteur,$data))
						$error=0;
				}
				else {
					if($smtp->send($from_lbf,$to,$data))
						$error=0;
				}
				$smtp->close();
			} else $error=2;
	} else $error=3;

	if(!$error) echo sprintf("Le message a bien été envoyé par email à l'adresse %s",_H($to,true));
	elseif($error==3) echo sprintf("Veuillez entrer une adresse email valide %s!",$to?sprintf("(%s) ",_H($to,true)):'');
	elseif($error==2) echo "Une erreur est survenue. Veuillez réessayer ultérieurement.";
	else echo sprintf("Impossible d'envoyer un email à l'adresse %s !",_H($to,true));
