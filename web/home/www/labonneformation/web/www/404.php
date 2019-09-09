<?php
	header('HTTP/1.1 404 Not Found',false);

	//$smtp=new QSmtp();
	//if($smtp->open())
	{
		$from='no-reply@'.DOMAIN;
		$to=MAILTO_404;
		$data="From: $from\nTo: $to\nSubject: LBF 404\n";
		$data.=sprintf("Date: %s\n\n",date('d/m/Y h:i:s'));
		$data.=sprintf("IP:\n%s\n\n",$this->getIP());
		$data.=sprintf("Url:\n%s\n\n",$this->getUrl());
		$data.=sprintf("_SERVER:\n%s\n\n",print_r($_SERVER,true));
		//_HR($data);
		_QUARKLOG("404.log","\n################################################\n$data\n");
		//$smtp->send($from,$to,$data);
		//$smtp->close();
	}
	$this->view('/404_view.php',
		array(
			'engine'=>true,
			'noRobots'=>true
		));
?>