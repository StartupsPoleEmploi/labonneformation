<?php
	//Integrity: 3c021969017fbcaca608d31996b858efc82ebe37
	//namespace Quark\Db;
	require_once('odbc.php');

	class Excel extends Odbc
	{
		public function open($file,$login,$pass,$db=null)
		{
			$file=realpath($file);
			$dsn="driver={Microsoft Excel Driver (*.xls)};readonly=0;dbq=$file;";
			return parent::open($dsn,$login,$pass);
		}
	}
?>