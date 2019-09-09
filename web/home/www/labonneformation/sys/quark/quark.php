<?php
	//Integrity: 79eebc3904439517b120badbdeb271c19cb40513
	/*
	 * This file is part of the Quark package. (2008-2016)
	 *
	 * (c) FreeCoders
	 *
	 * Quark Framework is distribute as Free
	 * Quark is an easy PHP modular Framework allowing :
	 * - Mutli-databases type access
	 * - MVC
	 * - Full PHP Inheritance pattern view (Don't needs any template layer)
	 * - Simple files organization
	 * - Almost all in one file (some linked class are in the same file) : very fast on opcodes cache
	 *
	 * How to use quark:
	 * ### First create a file names like "site/web/route.php" for instance with:
	 * <?php
	 * require_once('../sys/class/quark.php');
	 * require_once('../config/config.php'); //use this file to put somes specifics locales constantes or BD access
	 *
	 * $quark=new Quark();
	 * $db=new QDb('mysqli');
	 * if($db->open($database['host'],$database['user'],$database['password'],$database['db']))
	 * {
	 *    $quark->store('cache',new QCache(3600*24));
	 *    $quark->store('write',$db)->store('read',$db);
	 *    $quark->asset('asset',array('combine'=>true));
	 *
	 *    $quark->route('#^/$#','/index.php')    //Means all urls like "/" will be manage by www/index.php controller
	 *          ->rewriteRule('/index.php','/'); //Means in view: "$this->rw('/index.php');" will echo "/"
	 *
	 *    $quark->execute();
	 *
	 *    $db->close();
	 * }
	 * ?>
	 *
	 * ### Second create a controller file like "site/web/www/index.php", for instance with:
	 * <?php
	 * $this->view('www/index_view.php',array('param'=>'anything')); // The array represents all parameters will be exported in the view as variables accessibles by $...
	 * ?>
	 *
	 * ### Third create the view file like "site/view/www/index_view.php" with an html content
	 * <html><?php _H($param);?></html>
	 *
	 * ### Finaly, use the "site/sys/class" folder to put your model classes
	 * An autoload() exists in quark framework to load automatically your classes in yours controllers
	 */

	/**
	 * PSR0 framework, initialisation
	 */
	spl_autoload_register(function($className)
	{
		//$t=microtime(true);
		$className=strtr(strtolower($className),array('\\'=>DIRECTORY_SEPARATOR,'_'=>DIRECTORY_SEPARATOR)).'.php';
		$fileName=__DIR__."/../class/$className";
		if($className{0}=='q')
			if(file_exists(__DIR__."/$className"))
				$fileName=__DIR__."/$className";
		if(file_exists($fileName))
			require_once $fileName;
		//echo (microtime(true)-$t)." $className<br>";
	});

	/************************************************************************************
	 * Some Template function (/view/)
	 ************************************************************************************/
	/**
	 * Allow to display a string by htmlentities: <?php _H("string");?>
	 * @param string $str the string to display
	 * @param bool $isEncodeLF specify if "\n" will be convert to "<br>" or not
	 * @return string $encoding the enconding: by default "utf-8"
	 * function _H($str,$return=false): htmlentites
	 * function _Q($args,$return=false) //formate la partie query d'une url juste apres le ?
	 * function _U($url,$args,$return=false) //formate une url
	 * function _M($url,$args,$return=false) //formate un mailto
	 * function _T($string,$return=false) //ne fait rien sauf echo
	 * function _BEGINBLOCK($tag) //Défini un bloc
	 * function _ENDBLOCK($tag,$erase=false) //termine un bloc
	 * function _GETBLOCK($tag,$default='') //Retourne un bloc s'il existe
	 * function _PRINTBLOCK($tag) //Ecrit le contenu d'un bloc s'il existe, retourne false sinon
	 * function _DISPLAYBLOCK($tag) //idem que _PRINTBLOCK
	 * function _EXISTSBLOCK($tag) //Retourne true si le bloc existe sinon false
	 */
	global $_VIEW;
	$_VIEW=array();
	global $_CHARSET;
	$_CHARSET='utf-8';
	global $_ONENDBLOCK;
	$_ONENDBLOCK=array();
	function _QUARKLOG($fileName,$str)
	{
		file_put_contents(LOG_PATH.'/'.$fileName,sprintf("#### Date: %s\n%s\n",date('d/m/Y H:i:s'),$str),FILE_APPEND);
	}
	function _QUARKDEBUG($str)
	{
		//file_put_contents(LOG_PATH.'/debug.log',$str,FILE_APPEND);
		_QUARKLOG('debug.log',$str);
	}
	function _LOG($fileName,$str)
	{
		_QUARKLOG($fileName,$str);
	}
	function _HR($str,$return=false)
	{
		$str=htmlentities($str,ENT_COMPAT);
		$str=str_replace('€',"&euro;",$str);
		$str=str_replace("\n","<br/>",$str);
		$str=str_replace("\r",'',$str);
		if($return) return $str;
		echo $str;
	}
	function _HRF($str)
	{
		if(func_num_args()>1)
		{
			_HR(vsprintf($str,array_slice(func_get_args(),1)));
			return;
		}
		_HR($str);
	}
	function _H($str,$return=false)
	{
		//$str=htmlentities($str,ENT_COMPAT/*|ENT_HTML401*/,$encoding);
		//$str=str_replace('€',"&euro;",$str);
		$str=htmlentities($str);
		if($return) return $str;
		echo $str;
	}
	function _HF($str)
	{
		if(func_num_args()>1)
		{
			_H(vsprintf($str,array_slice(func_get_args(),1)));
			return;
		}
		_H($str);
	}
	/* Template function: Convert an array key=>val to a query string. */
	function _Q($args,$return=false)
	{
		$str=http_build_query($args);
		if($return) return $str;
		echo $str;
	}
	function _U($url,$args=array(),$return=false)
	{
		$str=http_build_query($args);
		if($return) return "$url?$str";
		echo "$url?$str";
	}
	function _M($url,$args=array(),$return=false)
	{
		$str=http_build_query($args,null,'&',PHP_QUERY_RFC3986);
		if($return) return "$url?$str";
		echo "$url?$str";
	}
	/* Template function: to print text */
	function _T($string,$return=false)
	{
		if($return) return $string;
		echo $string;
	}
	function _TF($str)
	{
		if(func_num_args()>1)
		{
			_T(vsprintf($str,array_slice(func_get_args(),1)));
			return;
		}
		_T($str);
	}
	function _JS($str,$type='DOUBLE_QUOTE',$return=false)
	{
		if($type=='DOUBLE_QUOTE')
			$str='"'.str_replace('"','\x22',$str).'"';
		elseif($type=='SIMPLE_QUOTE')
			$str="'".str_replace("'",'\x27',$str)."'";
		if($return) return $str;
		echo $str;
	}
	function _BEGINBLOCK($tag)
	{
		global $_VIEW;
		ob_start();
	}
	function _ENDBLOCK($tag,$erase=false)
	{
		global $_VIEW,$_ONENDBLOCK;
		$html=ob_get_contents();
		ob_end_clean();
		/* Si evenement de fin de bloc, execution d'une fonction dessus */
		if(array_key_exists($tag,$_ONENDBLOCK)) $html=$_ONENDBLOCK[$tag]($html);
		if($erase)
			$_VIEW[$tag]=$html;
		else
			$_VIEW[$tag]=$html._GETBLOCK($tag);
	}
	function _ONENDBLOCK($tag,$func)
	{
		 global $_ONENDBLOCK;
		 $_ONENDBLOCK[$tag]=$func;
	}
	function _GETBLOCK($tag,$default='')
	{
		global $_VIEW;
		return array_key_exists($tag,$_VIEW)?$_VIEW[$tag]:$default;
	}
	function _PRINTBLOCK($tag)
	{
		if(_EXISTSBLOCK($tag))
		{
			echo _GETBLOCK($tag);
			return true;
		}
		return false;
	}
	function _DISPLAYBLOCK($tag)
	{
		return _PRINTBLOCK($tag);
	}
	function _EXISTSBLOCK($tag)
	{
		global $_VIEW;
		return array_key_exists($tag,$_VIEW);
	}
	class Session
	{
		public function __construct()
		{
			@session_start();
		}
		/*
		function __destruct()
		{
			@session_destroy();
		}*/
		/**
		 * Get a session value by is key
		 * @param string $key the session key
		 * @param mixed $default
		 * @return mixed $default or the value saved
		*/
		public function get($key=null,$default=null)
		{
			if(is_null($key)) return array_map(function($e) {return unserialize($e);},$_SESSION);
			if(array_key_exists($key,$_SESSION)) return unserialize($_SESSION[$key]);
			return $default;
		}
		/**
		 * Set a session value by key
		 * @param string $key the session key
		 * @param mixed $value the value to save
		*/
		public function set($key,$value=null)
		{
			if(is_array($key)) $_SESSION=array_map(function($e) {return serialize($e);},$key)+$_SESSION;
			elseif($value==null) unset($_SESSION[$key]);
			else $_SESSION[$key]=serialize($value);
		}
		/**
		 * Delete a session key
		 * @param string $key the session key to delete
		*/
		public function del($key)
		{
			if(array_key_exists($key,$_SESSION)) unset($_SESSION[$key]);
		}
		/**
		 * Clear all the session
		*/
		public function clear()
		{
			session_destroy();
			@session_start();
		}
	}
	class FileObject
	{
		var $tmpFileName='';
		var $name='';
		var $typeMime='';
		var $data=null;
		var $size=0;

		public function __construct($file=null)
		{
			if($file) $this->set($file);
		}
		public function set($file)
		{
			$this->tmpFileName=$file["tmp_name"];
			$this->name=$file['name'];
			$this->typeMime=$file['type'];
			$this->size=$file['size'];
		}
		public function getName()
		{
			return $this->name;
		}
		public function getData()
		{
			if(!$this->data)
				$this->data=file_get_contents($this->tmpFileName);
			return $this->data;
		}
		public function getHexData()
		{
			$bin=@unpack("H*hex",$this->getData());
			return "0x".$bin["hex"];
		}
		public function getTypeMime()
		{
			return $this->typeMime;
		}
		public function getTmpFileName()
		{
			return $this->tmpFileName;
		}
		public function getSize()
		{
			return $this->size;
		}
	}
	class Http
	{
		public function getURI()
		{
			return $_SERVER['REQUEST_URI'];
		}
		public function getURL($isFull=true)
		{
			$port=$_SERVER['SERVER_PORT'];
			$url="http".($port==443?'s://':'://').$_SERVER['HTTP_HOST'];
			if($port!=80 && $port!=443) $url.=':'.$port;
			$uri=$_SERVER['REQUEST_URI'];
			if(!$isFull && ($pos=strpos($uri,'?'))!==false) $uri=substr($uri,0,$pos);
			return $url.$uri;
		}
		public function getIP()
		{
			return isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
		}
		public function get($key=null,$default=null)
		{
			if(is_null($key)) return array_merge($this->getGET(),$this->getPOST());
			//return array_key_exists($key,$_REQUEST)?$_REQUEST[$key]:$default;
			return $this->getGET($key,$this->getPOST($key,$default));
		}
		public function getGET($key=null,$default=null)
		{
			if(is_null($key)) return $_GET;
			return array_key_exists($key,$_GET)?$_GET[$key]:$default;
		}
		public function getPOST($key=null,$default=null)
		{
			if(is_null($key)) return $_POST;
			return array_key_exists($key,$_POST)?$_POST[$key]:$default;
		}
		public function getCOOKIE($key=null,$default=null)
		{
			if(is_null($key)) return $_COOKIE;
			return array_key_exists($key,$_COOKIE)?$_COOKIE[$key]:$default;
		}
		public function getFILE($key=null,$default=null,$retObj=true)
		{
			if(is_null($key)) return $_FILES;
			if(array_key_exists($key,$_FILES))
				if($retObj) return new FileObject($_FILES[$key]);
				else return $_FILES[$key];
			return $default;
		}
		public function set($key,$value=null)
		{
			$this->setGET($key,$value);
		}
		public function setGET($key,$value=null)
		{
			if(is_array($key))
				$_GET=$key+$_GET;
			else
				$_GET[$key]=$value;
		}
		public function setPOST($key,$value=null)
		{
			if(is_array($key))
				$_POST=$key+$_POST;
			else
				$_POST[$key]=$value;
		}
		public function setCOOKIE($key,$value,$options=array())
		{
			if(is_null($value))
			{
				setcookie($key,null,-1);
				unset($_COOKIE[$key]);
			} else
			{
				$expires=0;
				if(array_key_exists('time',$options)) $expires=$options['time']===0?0:time()+$options['time'];
				if(array_key_exists('expires',$options)) $expires=$options['expires'];
				$path=array_key_exists('path',$options)?$options['path']:'/';
				$domain=array_key_exists('domain',$options)?$options['domain']:'';
				$secure=array_key_exists('secure',$options)?$options['secure']:false;
				$httpOnly=array_key_exists('httponly',$options)?$options['httponly']:false;
				setcookie($key,$value,$expires,$path,$domain,$secure,$httpOnly);
				$_COOKIE[$key]=$value;
			}
		}
		public function del($key=null)
		{
			if(is_null($key)) $_REQUEST=$_GET=$_POST=$_FILES=$_COOKIE=array();
			else
				unset($_REQUEST[$key],$_GET[$key],$_POST[$key],$_FILES[$key]);
		}
		public function delGET($key=null)
		{
			if(is_null($key))
			{
				$_REQUEST=array_diff($_REQUEST,$_GET);
				$_GET=array();
			}
			else
				unset($_GET[$key],$_REQUEST[$key]);
		}
		public function delPOST($key=null)
		{
			if(is_null($key))
			{
				$_REQUEST=array_diff($_REQUEST,$_POST);
				$_POST=array();
			} else
				unset($_POST[$key],$_REQUEST[$key]);
		}
		public function delCOOKIE($key)
		{
			$this->setCOOKIE($key,null);
			unset($_REQUEST[$key]);
		}
		public function forward($url,$code=null,$die=true)
		{
			if($code!=null) header("Location: $url",true,$code);
			else header("Location: $url",true);
			if($die) die;
		}
		public function getHeader($key=null,$default=null)
		{
			$headers=array();
			foreach($_SERVER as $name => $value)
				if(substr($name,0,5)=='HTTP_')
					$headers[str_replace(' ', '-',ucwords(strtolower(str_replace('_',' ',substr($name,5)))))]=$value;
			if(isset($key))
				if(array_key_exists($key,$headers))
					return $headers[$key];
				else
					return $default;
			return $headers;
		}
		public function setHeader($key,$data=null,$replace=true)
		{
			if(!isset($data)) header($key,$replace);
			else header("$key: $data",$replace);
		}
		public function header($key,$data=null,$replace=true)
		{
			$this->setHeader($key,$data,$replace);
		}
	}
	class Route extends Http
	{
		private $route,$rewriteRules;

		public function __construct()
		{
			$this->route=array();
			$this->rewriteRules=array();
		}
		public function rewriteRule($key,$param)
		{
			$this->rewriteRules[$key]=$param;
			return $this;
		}
		public function route($pattern,$destination,$action=200)
		{
			$this->route[]=array($pattern,$destination,$action);
			return $this;
		}
		public function rewrite($query,$args=array())
		{
			if(array_key_exists($query,$this->rewriteRules))
				$query=is_string($this->rewriteRules[$query])?$this->rewriteRules[$query]:$this->rewriteRules[$query]($query,$args);
			if(array_key_exists('*',$this->rewriteRules))
				$query=is_string($this->rewriteRules['*'])?$this->rewriteRules['*']:$this->rewriteRules['*']($query,$args);
			return $query.(!empty($args)?'?'.http_build_query($args):'');
		}
		public function rw($query,$args=array(),$return=false)
		{
			if($return) return $this->rewrite($query,$args);
			echo $this->rewrite($query,$args);
		}
		protected function unRewrite($path,&$args=array())
		{
			foreach($this->route as $route)
			{
				list($pattern,$destination,$action)=$route;
				if(preg_match($pattern,$path,$m))
				{
					if(is_string($destination))
					{
						$request=$this->_getURI(preg_replace($pattern,$destination,$path),$a);
						$args=array_merge($args,$a);
					} else $request=$destination($m,$args,$action);
					if($action==301 || $action==302) $this->forward($request,$action);
					if($action=='continue') continue;
					return $request;
				}
			}
			return $path;
		}
	}
	class Quark extends Route
	{
		private $index,$base,$cache,$session,$store,$global,$viewHook;
		public $root,$contollerRoot,$viewRoot;

		public function __construct($root='/www')
		{
			parent::__construct();
			$this->index='/index.php';
			$this->base=realpath(__DIR__.'/../..');
			unset($this->cache);
			define('APP_BASE',$this->base);
			define('SYS_PATH',APP_BASE.'/sys');
			define('CLASS_PATH',SYS_PATH.'/class');
			define('MODEL_PATH',CLASS_PATH);
			define('QUARK_PATH',SYS_PATH.'/quark');
			define('FUNC_PATH',SYS_PATH.'/func');
			define('VIEW_PATH',APP_BASE.'/view');
			define('CONFIG_PATH',APP_BASE.'/config');
			define('LOG_PATH',APP_BASE.'/logs');
			define('CACHE_PATH',APP_BASE.'/cache');
			define('CONTROLLER_PATH',APP_BASE.'/web');
			ini_set('include_path','.'.PATH_SEPARATOR.APP_BASE.PATH_SEPARATOR.CONTROLLER_PATH.$root);
			ini_set('error_log',LOG_PATH.'/php_error.log');
			$this->root=$root;
			$this->controllerRoot=CONTROLLER_PATH.$root;
			$this->viewRoot=VIEW_PATH.$root;
			$this->charset('UTF-8');
			if(function_exists('mb_internal_encoding')) mb_internal_encoding("UTF-8");
			$this->store=array();
			$this->global=array();
			$this->session=null;
			$this->viewHook=null;
			//$this->session=new Session();
		}
		public function executeScript($file,$argv=array())
		{
			$this->controller($file,array('argn'=>count($argv)-1,'argv'=>array_slice($argv,1)),false,array('root'=>false));
		}
		public function execute($uri=null)
		{
			$path=$this->_getURI($uri,$_GET);
			$file=$this->unRewrite($path,$_GET);
			if(!is_null($file))
			{
				$_REQUEST=array_merge($_GET,$_POST);
				if($file && $file=='/') $file=$this->index;
				$this->controller($file);
			}
		}
		public function compile($file,$param=array())
		{
			extract($param);
			ob_start();require_once($file);return ob_get_clean();
		}
		public function controller($file,$param=array(),$__return=false,$options=array())
		{
			$controllerRoot='';
			if(array_key_exists('root',$options))
			{
				if($options['root']!==false)
					$controllerRoot=CONTROLLER_PATH.$options['root'].'/';
			} else $controllerRoot=$this->controllerRoot.'/';

			if($__return) ob_start();
			if(is_array($param))
			{
				$this->setGET(array_merge($this->getGET(),$param));
				extract($param);
			}
			if(!is_file("$controllerRoot$file"))
			{
				$file='404.php';
				header('HTTP/1.1 404 Not Found',false,404);
			}
			require("$controllerRoot$file");
			if($__return) return ob_get_clean();
			return $this;
		}
		public function incClass($file)
		{
			require_once(CLASS_PATH.$file);
		}
		public function view($file,$param=array(),$return=false,$options=array())
		{
			/*
			global $_THIS;

			$_THIS=$this;
			//if(!function_exists('_RW'))
				function _RW($path,$args=array(),$return=false)
				{
					global $_THIS;

					return $_THIS->rw($path,$args,$return);
				}
			*/
			global $_VIEW;
			$hook=$this->viewHook;

			$viewRoot='';
			if(array_key_exists('root',$options))
			{
				if($options['root']!==false)
					$viewRoot=VIEW_PATH.$options['root'].'/';
			} else $viewRoot=$this->viewRoot.'/';

			extract($this->global);
			if(is_array($param)) extract($param);
			if($return || !is_null($hook)) ob_start();
			require("$viewRoot$file");
			if(!is_null($hook))
			{
				$html=$hook(ob_get_clean());
				if($return) return $html;
				else echo $html;
			} else
			if($return) return ob_get_clean();
		}
		public function viewHook($func)
		{
			$this->viewHook=$func;
			return $this;
		}
		public function getStore($key,$default=false)
		{
			if(isset($this->store[$key])) return $this->store[$key];
			return $default;
		}
		public function store($key,$obj=null)
		{
			if(is_null($obj)) unset($this->store[$key]);
			else $this->store[$key]=$obj;
			return $this;
		}
		public function getGeneral($key,$default=false)
		{
			if(isset($this->global[$key])) return $this->global[$key];
			return $default;
		}
		public function general($key,$obj=null)
		{
			if(is_null($obj)) unset($this->global[$key]);
			else $this->global[$key]=$obj;
			return $this;
		}
		public function initSession()
		{
			if(!isset($this->session)) $this->session=new Session();
			return $this;
		}
		public function getSession()
		{
			if(!isset($this->session)) $this->session=new Session();
			return $this->session;
		}
		public function asset($cacheName='asset',$options=array())
		{
			$this->general($cacheName,new QAsset($this,3600*24*365,array('combine'=>array_key_exists('combine',$options)?$options['combine']:true,'group'=>$cacheName,'cssfile'=>"/css/$cacheName.php",'jsfile'=>"/js/$cacheName.php")));
			$this->route('#^/(css|js)/([0-9a-z]+)$#',function($m,&$args) use($cacheName)
				{
					echo $this->getGeneral($cacheName)->display($m[1],$m[2]);
				});
			$this->rewriteRule("/css/$cacheName.php",function($path,&$args){$etag=$args['etag']; unset($args['etag']); return "/css/$etag";});
			$this->rewriteRule("/js/$cacheName.php",function($path,&$args){$etag=$args['etag']; unset($args['etag']); return "/js/$etag";});
		}
		public function charset($charset='UTF-8')
		{
			global $_CHARSET;
			$_CHARSET=$charset;
			ini_set('default_charset',$charset);
			return $this;
		}
		static function locale($type,$data=array())
		{
			return setlocale($type,$data);
		}
		protected function _getURI($uri,&$get)
		{
			if(!isset($uri)) $uri=$_SERVER['REQUEST_URI'];
			//@list($path,$query)=array_values(parse_url($uri));
			preg_match('#^(.*?)(\?(.*))?$#si',$uri,$m);
			@list($dummy,$path,$dummy,$query)=$m;
			parse_str(isset($query)?$query:'',$get);
			if(0)
			{
				echo "uri: $uri<br>";
				echo "path: $path<br>";
				printf("get: %s<br>",print_r($_GET,true));
				echo __DIR__;
			}
			return $path;
		}

		/* Obsolets méthodes */
		private function inc($file,$param=array(),$return=false)
		{
			if($return) ob_start();
			extract($param);
			require_once($this->controllerRoot."/$file");
			if($return) return ob_get_clean();
		}
		private function _getCache($name=null)
		{
			$this->cache=isset($this->cache)?$this->cache:new QCache();
			//$this->cache->config($name);
			return $this->cache;
		}
	}
?>
