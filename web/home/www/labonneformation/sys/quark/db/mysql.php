<?php
	//Integrity: a6fc64754eae1c5e2fefc199b19bfa741c1d0b50
	//namespace Quark\Db;

	class MySQL
	{
		private $sql;
		private $result;
		private $row;

		public function __construct($options=null)
		{
		}
		public function open($ip,$login,$pass,$db=null)
		{
			if($this->sql=mysql_connect($ip,$login,$pass,true))
			{
				if($this->db($db))
					return true;
				$this->close();
			}
			return false;
		}
		public function close()
		{
			return mysql_close($this->sql);
		}
		public function query($request)
		{
			if($this->result=mysql_query($request,$this->sql))
				return true;
			return false;
		}
		public function db($dataBase)
		{
			return mysql_select_db($dataBase,$this->sql);
		}
		public function next()
		{
			if($this->row=mysql_fetch_assoc($this->result))
				return true;
			return false;
		}
		public function row()
		{
		    return $this->row;
		}
		public function getId()
		{
			$id=mysql_insert_id($this->sql);
			return $id?$id:0;
		}
		public function openTransaction()
		{
			return $this->query('SET autocommit=0');
		}
		public function closeTransaction($commit=true)
		{
			$retCode=$commit?$this->commit():$this->rollback();
			if($retCode) return $this->query('SET autocommit=1');
			return false;
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
			return "'".mysql_real_escape_string($str,$this->sql)."'";
		}
		public function s2($str)
		{
			if(is_null($str)) return 'null';
			return mysql_real_escape_string($str,$this->sql);
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