<?php
	//Integrity: 912db185dfb7099b8133b58bd84b2125000c20d2
	//namespace Quark\Db;

	require_once('odbc.php');

	class SqlServer extends Odbc
	{
		public function __construct($options=null)
		{
			parent::__construct('SQL Server');
		}
	}
?>