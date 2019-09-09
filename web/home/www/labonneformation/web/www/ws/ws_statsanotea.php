<?php
	$this->header('Content-Type: application/json');
	$db=$this->getStore('read');
	$anotea=new Anotea($db);

	if($stats=$anotea->getStats())
	{
		$json=json_encode($stats);
	} else
	{
		$returnCode=400;
		http_response_code($returnCode);
		$json=json_encode(array(
			'100'=>'Erreur interne, veuillez reessayer ulterieurement'
		));
	}
	echo $json;
?>