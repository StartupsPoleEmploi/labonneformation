<?php
	//Integrity: b5d3d532884f7ecaa62cb987466c4fe6d6af034f
	//namespace Quark\Db;

	class Oracle
	{
		private $sql;
		private $bind;
		private $result;
		private $row;

		public function __construct($options=null)
		{
		}
		public function open($ip,$login,$pass,$db=null)
		{
			if($this->sql=oci_connect($login,$pass,"//$ip",'AL32UTF8'))
			{
				$this->bind=null;
				return true;
			}
			return false;
		}
		public function close()
		{
			if($this->bind) oci_free_statement($this->bind);
			$this->bind=null;
			return oci_close($this->sql);
		}
		public function query($request)
		{
			if($this->bind) oci_free_statement($this->bind);
			if($this->bind=oci_parse($this->sql,$request))
				if(oci_execute($this->bind))
					return true;
			return false;
		}
		public function db($dataBase)
		{
			return true;
		}
		public function next()
		{
			if($this->row=oci_fetch_array($this->bind,OCI_ASSOC+OCI_RETURN_NULLS))
				return true;
			return false;
		}
		public function row()
		{
			return $this->row;
		}
		public function getId()
		{
			$id=0;//mysql_insert_id($this->sql);
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
			return "'".strtr($str,array("'"=>"''"))."'";
		}
		public function s2($str)
		{
			if(is_null($str)) return 'null';
			returnstrtr($str,array("'"=>"''"));
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
		public function getError()
		{
			$e=oci_error($this->bind);
			return $e['message'];
		}
	}
?>