<?php
	if(count($argv)>1)
	{
		$dir=getcwd();
		chdir(pathinfo(__FILE__)['dirname']);
		require_once('../../sys/quark/quark.php');
		require_once('../../config/config.php');

		$quark=new Quark();
		chdir($dir);

		$quark->executeScript($argv[1],$argv);
	}
?>