<?php
	//Integrity: 52b996615f0dd957ba4bfcf829f2a3e87afb406e
	//namespace Quark;
	//use Quark\Db;

	class DbField
	{
		protected $field;
		protected $db;

		public function __construct($field)
		{
			$this->field=$field;
		}
		public function init($db)
		{
			$this->db=$db;
		}
		public function convert($value,$k)
		{
			return array($this->field=>$value);
		}
	}
	class DbDate extends DbField
	{
		public function convert($value,$k)
		{
			return array($this->field=>$this->db->s2t($value));
		}
	}
	class DbFunction extends DbField
	{
		public function convert($value,$k)
		{
			$func=$this->field;
			return $func($value,$k);
		}
	}
	//foreach(glob("sys/class/api/sqldrivers/*.php") as $filename)
	//require_once('sqldrivers/mysql.php');
	//require_once('sqldrivers/mysqli.php');
	//require_once('sqldrivers/odbc.php');
	//require_once('sqldrivers/excel.php');
	//require_once('sqldrivers/sqlserver.php');
	//require_once('sqldrivers/oracle.php');

	/**
	 * Class to access DB
	 *
	 * Use: prepare() to prepare a request and use query() to execute
	 * Ex: $db->prepare("SELECT col1,col2 FROM table")->query() : return true or false, then use next() or row() to fetch
	 * Ex: $db->prepare("SELECT col1,col2 FROM table")->queryFetchAll() : return an array of the table. Use it if you need a one shot fetch
	 * Use: assign() to change result
	 * Ex: $db->prepare("SELECT col1,col2 FROM table")->assign('c1','c2')->query() : columns col1 and col2 will be rename as c1 and c2 at result
	 */
	class QDb
	{
		private $prepare;
		private $db;
		private $as;
		private $op;
		protected $destruct,$logSlowQueries=false,$useCache;
		public $showRequest=false;
		public $bufferizeRequest=false;
		public $buffer;

		public function __construct($driver='mysqli',$options=array())
		{
			$this->destruct=array_key_exists('autodestruct',$options)?$options['autodestruct']:false;
			$this->op=null;
			$this->buffer=array();
			$this->useCache=false;
			switch($driver)
			{
				case 'mysql': require_once(QUARK_PATH.'/db/mysql.php'); $this->db=new MySql($options); return;
				case 'odbc': require_once(QUARK_PATH.'/db/odbc.php'); $this->db=new Odbc($options); return;
				case 'excel': require_once(QUARK_PATH.'/db/excel.php'); $this->db=new Excel($options); return;
				case 'sqlserver': require_once(QUARK_PATH.'/db/sqlserver.php'); $this->db=new SqlServer($options); return;
				case 'oracle': require_once(QUARK_PATH.'/db/oracle.php'); $this->db=new Oracle($options); return;
				case 'postgresql': require_once(QUARK_PATH.'/db/postgresql.php'); $this->db=new PostgreSql($options); return;
			}
			require_once(QUARK_PATH.'/db/mysqli.php');
			if(array_key_exists('logslowqueries',$options)) $this->logSlowQueries=$options['logslowqueries'];
			$this->db=new DbMysqli($options);
		}
		public function __destruct()
		{
			$this->close();
		}
		public function open($ip,$login='',$pass='',$db=null)
		{
			$this->op=$this->db->open($ip,$login,$pass,$db);
			return $this->op;
		}
		public function close()
		{
			$ret=true;
			if($this->destruct && $this->op)
				if($ret=$this->db->close())
					$this->op=null;
			return $ret;
		}
		public function useCache()
		{
			$this->useCache=true;
		}
		public function bufferizeRequest($val=true)
		{
			$this->bufferizeRequest=true;
			$this->buffer=array();
		}
		public function getBufferRequest()
		{
			$totalDuration=0;
			foreach($this->buffer as $line)
				$totalDuration+=$line['duration'];
			return array('total-duration'=>$totalDuration,'buffer'=>$this->buffer);
		}
		public function query(&$id=null)
		{
			$t=$t2=microtime(true);
			$res=$this->db->query($this->prepare);
			$t3=microtime(true)-$t;
			if($this->bufferizeRequest) $this->buffer[]=array('duration'=>$t3,'request'=>$this->prepare);
			if($this->logSlowQueries!==false)
			{
				
				$t=$t3*1000;
				$logSlowQueries=is_array($this->logSlowQueries)?$this->logSlowQueries:array(array('log'=>'sql_slow.log','min'=>$this->logSlowQueries*1000));
				foreach($logSlowQueries as $slow)
				{
					$min=array_key_exists('min',$slow)?$slow['min']:0;
					$max=array_key_exists('max',$slow)?$slow['max']:1000*3600;
					if($t>=$min && $t<$max)
						_QUARKLOG($slow['log'],sprintf("\n########################################\nData: %s - Duration:%fs\n%s\n",date('d/M/Y H:i:s'),$t3,$this->prepare));
				}
			}
			if(ENV_DEV)
			{
				$trans=$this->showRequest===2?array():array("\n"=>"<br>");
				if(is_bool($this->showRequest) || is_integer($this->showRequest))
					if($this->showRequest)
						echo strtr(sprintf("### %fs\n%s\n\n",microtime(true)-$t2,$this->prepare),$trans);
			}
			if($res)
			{
				if(func_num_args()>0)
					if(!($id=$this->db->getId()))
						return false;
				return true;
			} else file_put_contents(LOG_PATH.'/sql_error.txt',
			                         sprintf("[%s] %s\r\n%s%s\r\n\r\n",date('d/M/Y H:i:s'),
			                                 $this->getError(),
			                                 array_key_exists('REQUEST_URI',$_SERVER)?'URI: '.$_SERVER['REQUEST_URI']."\r\n":'',
			                                 $this->prepare),
			                         FILE_APPEND);
			return false;
		}
		public function logSlowQueries($slowOptions)
		{
			$old=$this->logSlowQueries;
			$this->logSlowQueries=$slowOptions;
			return $old;
		}
		public function db($dataBase)
		{
			return $this->db->db($dataBase);
		}
		public function next()
		{
			if($this->db->next())
				return $this->row();
			return false;
		}
		private function row()
		{
			$sql=$this->db;
			if(!isset($this->as) && func_num_args()>0) $this->as=func_get_args();
			if(!isset($this->as)) return $sql->row();
			$as=$this->as;
			$row=array();
			$i=0; foreach($sql->row() as $k=>$v) $row+=$as[$i++]->convert($v,$k);
			return $row;
		}
		public function queryFetchAll()
		{
			if(!$this->query()) return false;
			if(!isset($this->as) && func_num_args()>0) $this->as=func_get_args();
			$ret=array();
			for($i=0;($row=$this->next())!==false;$i++) $ret[]=$row;
			return $ret;
		}
		public function queryFetchFirst()
		{
			if(!$this->query()) return false;
			if(!isset($this->as) && func_num_args()>0) $this->as=func_get_args();
			$ret=array();
			for($i=0;($row=$this->next())!==false && $i<1;$i++) $ret[]=$row;
			if(count($ret)) $ret=$ret[0];
			return $ret;
		}
		public function openTransaction()
		{
			return $this->db->openTransaction();
		}
		public function closeTransaction($commit=true)
		{
			return $this->db->closeTransaction($commit);
		}
		public function commit()
		{
			return $this->db->commit();
		}
		public function rollback()
		{
			return $this->db->rollback();
		}

		/* Fonctions ajoutees */
		public function assign()
		{
			//$this->as=func_get_args(); return $this;
			$this->as=array();
			foreach(func_get_args() as $field)
			{
				if(is_string($field))
				{
					if($field[0]=='{')
						$as=new DbDate(substr($field,1,-1));
					else
						$as=new DbField($field);
				} elseif(is_callable($field))
				{
					$as=new DbFunction($field);
				} else $as=$field;
				$as->init($this->db);
				$this->as[]=$as;
			}
			return $this;
		}
		public function getPrepare()
		{
			return $this->prepare;
		}
		public function prepare($request)
		{
			unset($this->as);
			$args=func_get_args();
			array_shift($args);
			$this->prepare=$this->vrequest($request,$args);
			return $this;
		}
		public function request($request)
		{
			$args=func_get_args();
			array_shift($args);
			return $this->vrequest($request,$args);
		}
		public function s2t($date)
		{
			return $this->db->s2t($date);
		}
		public function t2s($date)
		{
			return $this->db->t2s($date);
		}
		public function a2i($arrayIn,$f='%')
		{
			if(!is_array($arrayIn)) $arrayIn=array($arrayIn);
			if(!empty($arrayIn))
			{
				foreach($arrayIn as $idx=>$val)
				{
					switch(strtolower(gettype($val)))
					{
						case 'string':
							$arrayIn[$idx]=$this->db->s(sprintf($f.'s',$val));
							break;
						case 'integer':
							$arrayIn[$idx]=sprintf($f.'ld',$val);
							break;
						case 'double':
							$arrayIn[$idx]=sprintf($f.'f',$val);
							break;
						case 'null':
							$arrayIn[$idx]='null';
							break;
					}
				}
				return "(".implode(',',$arrayIn).")";
			}
			return 'null';
		}
		public function escape($str)
		{
			return $this->db->s2($str);
		}
		public function getError()
		{
			return $this->db->getError();
		}

		/* Fonctions privees */
		public function format($f,$s,&$offset,&$args) /* public pour PHP 5.3 */
		{
			switch($s)
			{
				case 'rs':
					$args[$offset]=$this->db->s(is_null($args[$offset])?null:sprintf($f.'s',$args[$offset]));
					$offset++;
					return '%s';
				case 'rt':
					$args[$offset]=$this->db->t2s($args[$offset]);
					$offset++;
					return '%s';
				case 'rb':
					$args[$offset]=$this->db->b(is_null($args[$offset])?null:sprintf($f.'s',$args[$offset]));
					$offset++;
					return '%s';
				case 'rd':
					$ret=$f.'ld';
					if(is_null($args[$offset])) {$args[$offset]='null'; $ret='%s';}
					$offset++;
					return $ret;
				case 'rf':
					$ret=$f.'F';
					if(is_null($args[$offset])) {$args[$offset]='null'; $ret='%s';}
					$offset++;
					return $ret;
				case 'ri':
					$args[$offset]=$this->a2i($args[$offset],$f);
					$offset++;
					return '%s';
				case '%':
					return '%%';
			}
			$offset++;
			return $f.$s;
		}
		private function vrequest($request,&$args)
		{
			$a=0;
			$t=$this;
			//$request=preg_replace('/(%[^%]*?)(rs|rt|rb|rd|rf|ri|%|[a-zA-Z])/es','$this->format("$1","$2",$a,$args)',$request);
			$request=preg_replace_callback('/(%[^%]*?)(rs|rt|rb|rd|rf|ri|%|[a-zA-Z])/s',
				function($arg) use(&$a,&$args,&$t)
				{
					return $t->format($arg[1],$arg[2],$a,$args);
				},$request);
			return vsprintf($request,$args);
		}
		private function vrequest2($request,&$args)
		{
			$a=0;
			$r='';
			//$request=preg_replace('/(%[^%]*?)(rs|rt|rb|rd|rf|ri|%|[a-zA-Z])/es','$this->format("$1","$2",$a,$args)',$request);
			preg_match_all('/(%[^%]*?)(rs|rt|rb|rd|rf|ri|%(s|t|b|d|f)|[a-z])/s',$request,$m,PREG_OFFSET_CAPTURE);
			$o=0;
			print_r($m);
			foreach($m[0] as $k=>$v)
			{
				$len=$v[1]-$a;
				$r.=substr($request,$a,$len);
				$f=$m[1][$k][0];
				switch($m[2][$k][0])
				{
					case 'rs':
						$r.=$this->db->s(is_null($args[$o])?null:sprintf($f.'s',$args[$o]));
						$o++;
						break;
					case 'rt':
						$r.=$this->db->t2s($args[$o]);
						$o++;
						break;
					case 'rb':
						$r.=$this->db->b(is_null($args[$o])?null:sprintf($f.'s',$args[$o]));
						$o++;
						break;
					case 'rd':
						$r.=sprintf($f.'d',$args[$o++]);
						break;
					case 'rf':
						$r.=sprintf($f.'f',$args[$o++]);
						break;
					case 'ri':
						$r.=$this->db->a2i($args[$o],$f);
						$offset++;
						break;
					case '%':
						if(strlen($f)>1)
						{
							$r.=$this->db->s(is_null($args[$o][substr($f,1)])?null:sprintf('%s',$args[$o][substr($f,1)]));
						} else $r.='%';
						break;
				}
				$a=$v[1]+strlen($v[0]);
			}
			print_r($r);
			die;
		}

		//public function col($field,$default=null)
		//{
		//	$db=$this->db;
		//	if($field[0]=='{') return $db->s2t($db->col(strtr($field,array('{'=>'','}'=>''))));
		//	return $db->col($field,$default);
		//}
		//function col($field,$default=null)
		//{
		//	$row=$this->row();
		//	return array_key_exists($field,row)?row[$field]:$default;
		//}
		private function row2()
		{
			$sql=$this->db;
			if(!isset($this->as) && func_num_args()>0) $this->as=func_get_args();
			if(!isset($this->as)) return $sql->row();
			$new=array();
			foreach(array_combine($this->as,$sql->row()) as $k=>$v)
			{
				if($k[0]=='{')
					$new[substr($k,1,-1)]=$sql->s2t($v);
				else
					$new[$k]=$v;
			}
			return $new;
		}
		private function row1()
		{
			$sql=$this->db;
			if(!isset($this->as) && func_num_args()>0) $this->as=func_get_args();
			if(!$this->as) return $sql->row();
			$i=0;
			$new=array();
			$row=array_values($sql->row());
			foreach($this->as as $field)
			{
				if($field[0]=='{')
					$new[substr($field,1,-1)]=$sql->s2t($row[$i]);
				else
					$new[$field]=$row[$i];
				$i++;
			}
			return $new;
		}
		private function getAll1()
		{
			$sql=$this->db;
			if(!isset($this->as) && func_num_args()>0) $this->as=func_get_args();
			$ret=array();
			while($this->next()) $ret[]=$sql->row();
			$keys=$as=array();
			foreach($this->as as $v)
				if($v[0]=='{')
				{
					$keys[]=$as[]=substr($v,1,-1);
				} else $keys[]=$v;
			$ret=array_map(function($t) use (&$keys,&$as,&$sql)
			{
				$t=array_combine($keys,$t);
				foreach($as as $k) $t[$k]=$sql->s2t($t[$k]);
				return $t;
			},$ret);

			return $ret;
		}
	}
?>
