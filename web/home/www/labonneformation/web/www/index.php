<?php
	$criteria=array('search'=>'','locationpath'=>'','location'=>'','romecode'=>'','code'=>'');

	$db=$this->getStore('read');
	$isSuccess=true;

	if($isSuccess)
		$this->view('/index_view.php',
			array(
				'criteria'=>$criteria,
				'home'=>true,
				'engine'=>true,
				'page'=>'index'
			));

	if(!$isSuccess)
	{
		$this->view('/error_view.php');
	}
?>
