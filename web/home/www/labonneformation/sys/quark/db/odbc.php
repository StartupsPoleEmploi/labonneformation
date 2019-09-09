<?php
	//Integrity: f9aca431a1ec6b2bb152c8a7e6affe02b71b2191
	//namespace Quark\Db;

	class Odbc
	{
		protected $sql;
		protected $result;
		protected $row;
		protected $driver;

		public function __construct($driver=null)
		{
			if(is_null($driver)) $driver='MySQL ODBC 5.1 Driver';
			$this->driver=$driver;
		}
		public function open($ip,$login,$pass,$db=null)
		{
			//$dsn="Driver={SQL Server};Server=$ip;database=$db";
			$db=is_null($db)?'':"database=$db;";
			$dsn=strchr($ip,';')?$ip:"driver={{$this->driver}};server=$ip;$db";
			if($this->sql=odbc_connect($dsn,$login,$pass))
				return true;
			return false;
		}
		public function close()
		{
			return odbc_close($this->sql);
		}
		public function query($request)
		{
			if($this->result=odbc_exec($this->sql,$request))
				return true;
			return false;
		}
		public function db($dataBase)
		{
			return self::query("USE $dataBase");
		}
		public function next()
		{
			if($this->row=odbc_fetch_array($this->result))
				return true;
			return false;
		}
		public function row()
		{
		    return $this->row;
		}
		public function getId()
		{
			if(self::query('SELECT @@IDENTITY AS id'))
				if(self::next())
					return self::col('id',0);
			return 0;
		}
		public function openTransaction()
		{
			return odbc_autocommit($this->sql,false);
		}
		public function closeTransaction($commit=true)
		{
			$retCode=$commit?self::commit():self::rollback();
			if($retCode) return odbc_autocommit($this->sql,true);
			return false;
		}
		public function commit()
		{
			return odbc_commit($this->sql);
		}
		public function rollback()
		{
			return odbc_rollback($this->sql);
		}
		public function s($str)
		{
			if(is_null($str)) return 'null';
			return "'".addslashes($str)."'";
		}
		public function b($data)
		{
			if(is_null($data) || empty($data)) return 'null';
			$bin=@unpack("H*hex",$data);
			return '0x'.$bin['hex'];
		}
		public function t2s($time)
		{
			if(!$time) return 'null';
			return "'".date("Y-m-d H:i:s",$time)."'";
		}
		public function s2t($date)
		{
			$hour=$min=$sec=0;
			list($date,$time)=explode(' ',$date);
			list($hour,$min,$sec)=explode(":",$time);
			list($year,$month,$day)=explode('-',$date);
			return mktime(intval($hour),intval($min),intval($sec),intval($month),intval($day),intval($year));
		}
	}
?>