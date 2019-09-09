<?php
	function displayErrors($errors)
	{
		$cnt=count($errors);
		printf("failed - %ld error%s\n",$cnt,$cnt>1?'s':'');
		foreach($errors as $error)
			echo "$error\n";
	}
	function checkingName($dir,$authorized,$path='',$mode='EXACT')
	{
		$errors=array();
		if($mode=='EXACT')
			foreach($dir as $name=>$value)
				if(!in_array($name,$authorized))
					$errors[]=sprintf("    WTF: %s%s ???",$path,$name);
		if($mode=='EXACT' || $mode=='EXISTS')
			foreach($authorized as $k=>$name)
				if(!array_key_exists($name,$dir))
					$errors[]=sprintf("    Where is: %s%s ???",$path,$name);
		return $errors;
	}
	function checkingPattern(&$array,$pattern,$recurse='')
	{
		$errors=array();
		foreach($array as $name=>$value)
		{
			$isRep=preg_match('#^.*/$#',$name);
			if($recurse!==false && $isRep)
				$errors=array_merge($errors,checkingPattern($array[$name],$pattern,"$recurse$name"));
			if(!$isRep && !preg_match($pattern,$name))
				$errors[]=sprintf("    %s%s ???",$recurse,$name);
		}
		return $errors;
	}
	function checkingContent(&$array,$patternFiles,$callback,$recurse='')
	{
		$errors=array();
		foreach($array as $name=>$value)
		{
			$isRep=preg_match('#^.*/$#',$name);
			if($recurse!==false && $isRep)
				$errors=array_merge($errors,checkingContent($array[$name],$patternFiles,$callback,"$recurse$name"));
			if(!$isRep && preg_match($patternFiles,$name))
				if($error=$callback($recurse,$name)) //sprintf("    %s%s ???",$recurse,$name);
					$errors[]=$error;
		}
		return $errors;
	}
	function recalcIntegrity()
	{
		$array=array();
		showDir('../',true,0,$array);
		//print_r($array); die;
		checkingContent($array['sys/']['quark/'],'#^.*\.php$#',
			function($path,$name)
			{
				$fileName=__DIR__."/../$path$name";
				if($content=file_get_contents($fileName))
					if($content=preg_replace('#//Integrity:.*$#mi','//Integrity:',$content))
					{
						$content=preg_replace('#//Integrity:.*$#mi','//Integrity: '.sha1($content),$content);
						file_put_contents($fileName,$content);
					}
			},'sys/quark/');
	}
	//print_r(scandir('../../')); die;
	function showDir($path,$recurs=false,$level=0,&$array)
	{
		if($dir=@opendir($path))
		{
			if(($oldPath=getcwd())!==false)
				if(chdir($path))
				{
					while(($f=@readdir($dir))!==false)
					{
						if(preg_match('#^\..*$#',$f)) continue;
						$p=is_dir("$f")?'/':'';
						//echo str_repeat(' ',$level*4)."$f$p\n";
						if($p=='/') $array["$f$p"]=array();
						else $array[$f]=$f;
						if($recurs && $p) showDir($f,$recurs,$level+1,$array["$f$p"]);
					}
					chdir($oldPath);
				}
			closedir($dir);
		}
	}
	function checkQuarky()
	{
		$array=array();
		showDir('../',true,0,$array);
		
		printf("Checking base directory structure: ");
		if($errors=checkingName($array,array('batch/','cache/','config/','logs/','sys/','view/','web/'),'./'))
		{
			displayErrors($errors);
		} else echo "ok\n";

		printf("Checking sys/ directory structure: ");
		if($errors=checkingName($array['sys/'],array('class/','quark/','param.php'),'sys/','EXISTS'))
		{
			displayErrors($errors);
		} else echo "ok\n";

		if(empty($errors))
		{
			printf("Checking framework integrity : ");
			//excel.php  mysqli.php  mysql.php  odbc.php  oracle.php  postgresql.php  sqlserver.php
			$errors=checkingName($array['sys/']['quark/'],array('db/','qasset.php','qcache.php','qcontentparser.php','qdb.php','qform.php','qmail.php','qsmtp.php','qstring.php','quark.php','quarky.php'),'sys/quark/');
			if(empty($errors))
			{
				if($errors=checkingName($array['sys/']['quark/']['db/'],array('excel.php','mysqli.php','mysql.php','odbc.php','oracle.php','postgresql.php','sqlserver.php'),'sys/quark/db/'))
				{
					displayErrors($errors);
				}
				if(empty($errors))
					if($errors=checkingContent($array['sys/']['quark/'],'#^.*\.php$#',
						function($path,$name)
						{
							$fileName=__DIR__."/../$path$name";
							if(!($content=file_get_contents($fileName)))
								return sprintf("    %s%s: empty file ???",$path,$name);
							if(!preg_match('#//Integrity: (.*)$#mi',$content,$m))
								return sprintf("    %s%s: unable to find integrity check ???",$path,$name);
							if($content=preg_replace('#//Integrity:.*$#mi','//Integrity:',$content))
							{
								if($m[1]!=sha1($content))
									return sprintf("    %s%s: integrity failure ???",$path,$name);
							} else
								return sprintf("    %s%s: integrity failure ???",$path,$name);
							return false;
						},'sys/quark/'))
					{
						displayErrors($errors);
					} else echo "ok\n";
			} else displayErrors($errors);
			if($errors) printf("    -> Don't touch my framework !\n");
		}

		printf("Checking view tree (all files names should be ended by _view.php): ");
		if($errors=checkingPattern($array['view/'],'#[a-z0-9_]+_view\.php#','view/'))
		{
			displayErrors($errors);
		} else echo "ok\n";

		printf("Checking view contents (shoudn't contain echo): ");
		if($errors=checkingContent($array['view/'],'#[a-z0-9_]+_view\.php#',
			function($path,$name)
			{
				$content=file_get_contents(__DIR__."/../$path$name");
				if(preg_match('#<\?php echo #si',$content,$m))
					return sprintf("    -> echo found in $path$name. Use <?php _T(...);?> or <?php _H();?> instead !");
				return false;
			},'view/'))
		{
			displayErrors($errors);
		} else echo "ok\n";
	}
	function qWeb()
	{
		error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
		ini_set('display_errors',1);
		chdir(__DIR__.'/../web');

		$php=true;
		$type='text/html';
		if(preg_match('#^(.*?)(\?(.*))?$#si',$_SERVER['REQUEST_URI'],$m))
		{
			@list($dummy,$path,$dummy,$query)=$m;
			if(preg_match('#.*\.(jpg|jpeg|png|gif|ico)$#i',$path,$m))
			{
				$type=sprintf('image/%s',strtolower($m[1]));
				$php=false;
			} else
			if(preg_match('#.*\.(css|js)$#',$path,$m))
			{
				$m[1]=strtolower($m[1]);
				if($m[1]=='js') $m[1]='javascript';
				$type=sprintf('text/%s',$m[1]);
			}
			header("Content-Type: $type; charset=UTF-8");
			if($php)
				require_once('../web/route.php');
			else
				@readfile(__DIR__.'/../web/www'.$path);//readfile($_SERVER['REQUEST_URI']);
		}

		/* Serveur Web PHP */
		if(0)
		{
			$address='0.0.0.0';
			$serverPort=8080;

			if(($serverSock=socket_create(AF_INET,SOCK_STREAM,SOL_TCP))!==false)
			{
				if(socket_bind($serverSock,$address,$serverPort)!==false)
				{
					if(socket_listen($serverSock,64)!==false)
					{
						//print_r($serverSock); die;
						$clients=array();
						do {
							$read=array_merge(array($serverSock),$clients);
							if(socket_select($read,$write=NULL,$except=NULL,5)!==false)
							{
								foreach($read as $sock)
								{
									switch($serverSock==$sock?'SERVER':'CLIENT')
									{
										case 'SERVER':
											if(($clientSock=socket_accept($sock))!==false)
											{
												echo "connexion\n";
												$clients[]=$clientSock;
											} else echo "socket_accept() a échoué : raison : " . socket_strerror(socket_last_error($serverSock)) . "\n";
											break;
										case 'CLIENT':
											$len=socket_recv($sock,$data,2048,0);
											if($len!==false && $len>0)
											{
												echo "un truc client ".strlen($data)."\n";
												echo "$data";
											} else
											{
												echo "Deconnexion\n";
												unset($clients[array_search($sock,$clients)]);
												socket_close($sock);
											}
											break;
									}
								}
								
								//$buf = socket_read($msgsock, 2048, PHP_NORMAL_READ);
								//socket_write($msgsock, $talkback, strlen($talkback));
							}
						} while(true);

					} else echo "socket_listen() a échoué : raison : " . socket_strerror(socket_last_error($serverSock)) . "\n";
				} else echo "socket_bind() a échoué : raison : " . socket_strerror(socket_last_error($serverSock)) . "\n";
				socket_close($serverSock);
			} else echo "socket_create() a échoué : raison : " . socket_strerror(socket_last_error()) . "\n";
		}
	}
	if(!isset($argv)) qWeb();
	else
	{
		$dir=getcwd();
		chdir(pathinfo(__FILE__)['dirname']);
		if(count($argv)>1)
		{
			$cmd=$argv[1];
			if($cmd=='check')
				checkQuarky();
		} else
		{
			printf("Usage:\n".
			       "- php -Slocalhost:8080 %s -> launch quarky as web server on port 8080.\n".
			       "- php %s check -> lauch a full check of quarky structure. use it has much has possible.\n"
			       ,$argv[0],$argv[0]);
		}
		chdir($dir);
	}
?>
