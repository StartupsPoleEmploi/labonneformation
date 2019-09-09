<?php
	//Integrity: 51744727fcc3c0c8aefb16645acc77f4e596cca9
	//namespace Quark\Db;

	class PostgreSql
	{
		private $sql;
		private $result;
		private $row;

		public function __construct($options=null)
		{
		}
		public function open($ip,$login,$pass,$db=null)
		{
			$dsn=sprintf("host=%s port=%d user=%s password=%s %s",$ip,5432,$login,$pass,$db?"dbname=$db":'');
			//echo $dsn;
			if($this->sql=pg_connect($dsn))
				return true;
			return false;
		}
		public function close()
		{
			return pg_close($this->sql);
		}
		public function query($request)
		{
			if($this->result=pg_query($this->sql,$request))
				return true;
			return false;
		}
		public function db($dataBase)
		{
			return true; //mysql_select_db($dataBase,$this->sql);
		}
		public function next()
		{
			if($this->row=pg_fetch_assoc($this->result))
				return true;
			return false;
		}
        public function row()
        {
            return $this->row;
        }
		public function getId()
		{
			return false;
		}
		public function openTransaction()
		{
			return $this->query('BEGIN');
		}
		public function closeTransaction($commit=true)
		{
			return $commit?$this->commit():$this->rollback();
		}
		public function commit()
		{
			return $this->query('COMMIT');
		}
		public function rollback()
		{
			return $this->query('ROLLBACK');
		}
		public function s($str)
		{
			if(is_null($str)) return 'null';
			return "'".pg_escape_string($this->sql,$str)."'";
		}
		public function s2($str)
		{
			if(is_null($str)) return 'null';
			return pg_escape_string($this->sql,$str);
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
			return strtotime($date);
		}
		public function getError()
		{
			return pg_last_error($this->sql);
		}
	}
?>