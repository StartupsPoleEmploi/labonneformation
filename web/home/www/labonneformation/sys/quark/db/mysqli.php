<?php
	//Integrity: ebf7df99c83930cbf4780407cd201fd49b44c4c5
	//namespace Quark\Db;

	class DbMySqli
	{
		private $sql;
		private $result;
		private $row;

		public function __construct($options=null)
		{
			//mysqli_prepare($this->db,'');
		}
		public function open($ip,$login,$pass,$db=null)
		{
			if(preg_match('#^(.*):(\d+)$#',trim($ip),$m))
			{
				$ip=$m[1];
				$port=$m[2];
			}
			$this->sql=mysqli_init();
			if($c=@$this->sql->real_connect($ip,$login,$pass,$db,$port,null,MYSQLI_CLIENT_COMPRESS))
				$this->sql->set_charset('utf8');
			return $c;
		}
		public function close()
		{
			return $this->sql->close();
		}
		public function query($request)
		{
			if($this->result=$this->sql->query($request))
				return true;
			return false;
		}
		public function db($dataBase)
		{
			return $this->sql->select_db($dataBase);
		}
		public function next()
		{
			if($this->result===true) return false;
			if(($this->row=mysqli_fetch_assoc($this->result))!==null)
				return true;
			return false;
		}
        public function row()
        {
            return $this->row;
        }
		public function getId()
		{
			return $this->sql->insert_id;
		}
		public function openTransaction()
		{
			return $this->sql->autocommit(false);
		}
		public function closeTransaction($commit=true)
		{
			$retCode=$commit?self::commit():self::rollback();
			if($retCode) return $this->sql->autocommit(true);
			return $retCode;
		}
		public function commit()
		{
			return $this->sql->commit();
		}
		public function rollback()
		{
			return $this->sql->rollback();
		}
		public function s($str)
		{
			if(is_null($str)) return 'null';
			return "'".mysqli_real_escape_string($this->sql,$str)."'";
		}
		public function s2($str)
		{
			if(is_null($str)) return 'null';
			return mysqli_real_escape_string($this->sql,$str);
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
			/*
			if($date!='')
			{
				$hour=$min=$sec=0;
				list($date,$time)=explode(' ',$date);
				list($hour,$min,$sec)=explode(":",$time);
				list($year,$month,$day)=explode('-',$date);
				return mktime(intval($hour),intval($min),intval($sec),intval($month),intval($day),intval($year));
			}
			*/
			return -1;
		}
		public function getError()
		{
			return $this->sql->error;
		}
	}
?>