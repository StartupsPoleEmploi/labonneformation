<?php
	$db=$this->getStore('read');

	$codeRome=$this->get('rome');
	$codeInsee=$this->get('codeinsee');
	$orient=$this->get('orient',1);
	if($codeRome && $codeInsee)
	{
		$adSearch=new adSearch($db);
		$ref=new Reference($db);

		$criteria=array();
		$criteria['rome']=$codeRome;
		$criteria['codeinsee']=$codeInsee;

		$list=$adSearch->getList($criteria,30,3);
	}
	
	$this->view('/ws/ws_widget_view.php',
		array(
			'adList'=>$list,
			'orient'=>$orient
			)
		);
?>