<?php
	class Tools
	{
		static function removeAccents($chaine)
		{
			return strtr($chaine,array("À"=>"A","Á"=>"A","Â"=>"A","Ã"=>"A","Ä"=>"A","Å"=>"A","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","å"=>"a","Ò"=>"O","Ó"=>"O","Ô"=>"O","Õ"=>"O","Ö"=>"O","Ø"=>"o","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o","ø"=>"o","È"=>"E","É"=>"E","Ê"=>"E","Ë"=>"E","è"=>"e","é"=>"e","ê"=>"e","ë"=>"e","Ç"=>"C","ç"=>"c","Ì"=>"I","Í"=>"I","Î"=>"I","Ï"=>"I","ì"=>"i","í"=>"i","î"=>"i","ï"=>"i","Ù"=>"U","Ú"=>"U","Û"=>"U","Ü"=>"U","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u","ÿ"=>"y","Ñ"=>"N","ñ"=>"n"));
		}
		static function cut($str,$len)
		{
			$str=mb_substr(trim($str),0,$len);
			echo $str;
			if(strlen($str)>=$len) echo '...';
		}
		static function text2Html($text,$nobr=true,$options=array())
		{
			$high=array();
			if(!empty($options['highlight']))
			{
				$stop=array_key_exists('stopwords',$options)?$options['stopwords']:array();
				$minLen=array_key_exists('minlength',$options)?$options['minlength']:2;
				preg_replace_callback("/(\\w|[0-9])+/ui",function($m) use(&$high,$stop,$minLen)
					{
						if(!in_array($m[0],$stop) && strlen($m[0])>$minLen) $high[]='('.strtr(preg_quote($m[0],'/'),array('e'=>'[eéèêë]','a'=>'[aàäâ]','o'=>'[oôöò]','u'=>'[uùüû]','i'=>'[iîï]')).')';
					},$options['highlight']);
				$high=implode($high,'|');
				$styles=array_key_exists('highlightstyle',$options)?$options['highlightstyle']:array('background-color:#c2e368','background-color:#FF6600','background-color:#aaaaaa','background-color:#74A408');//'cyan','chartreuse','fuchsia','lime','red','teal');
				$classes=isset($options['highlightclass'])?$options['highlightclass']:array();
				if($high)
					$text=preg_replace_callback("/$high/sui",function($m) use($styles,$classes)
						{
							$cstyles=count($styles);
							$cclasses=count($classes);
							return sprintf('<span style="%s" class="%s">%s</span>',$styles[(count($m)-1)%($cstyles?$cstyles:1)],$classes[(count($m)-1)%($classes?$classes:1)],$m[0]);
						},$text,-1,$cnt);
				return $text;
			}
			if(!empty($options['urlize']) && $text)
			{
				$text=self::urlize($text);
				if(!$text) error_log('pb lors de la conversion text / urlize');
			}
			return $nobr?$text:nl2br($text);
		}
		/**
		 * Remplace des liens text en lien html => avec target blank et nofollow
		 * inspiré de https://stackoverflow.com/questions/1925455/how-to-mimic-stack-overflow-auto-link-behavior
		 *
		 * @param  string $text
		 * @return string
		 */
		static function urlize($text,$linkTitle=null)
		{
		   // a more readably-formatted version of the pattern is on http://daringfireball.net/2010/07/improved_regex_for_matching_urls
		   $pattern  = '#(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))#';

		   return preg_replace_callback($pattern, function($matches) use ($linkTitle){
			   $url=array_shift($matches);
			   $url_parts=parse_url($url);

			   if(is_null($linkTitle))
			   {
				   $text=parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
				   $text=preg_replace("/^www./", "", $text);

				   $las=-(strlen(strrchr($text, "/")))+1;
				   if($last<0)
				   {
					   $text=substr($text, 0, $last) . "&hellip;";
				   }
			   }
			   else
				   $text=$linkTitle;

			   return sprintf('<a rel="nofollow" target="_blank" href="%s">%s</a>', $url, $text);
		   }, $text);

		}
		static function sphinxEscape($string)
		{
			//$from = array('\\',';','|','-','!','@','~','"','&','/','^','$','=',"'",'(',')');
			//$to   = array('\\\\','\;','\|','\-','\!','\@','\~','\"','\&','\/','\^','\$','\=',"\\'",'\(','\)');

			$from = array ( '\\', '(',')','|','-','!','@','~','"','&', '/', '^', '$', '=', "'", "\x00", "\n", "\r", "\x1a" ,';');
			$to   = array ( '\\\\', '\\\(','\\\)','\\\|','\\\-','\\\!','\\\@','\\\~','\\\"', '\\\&', '\\\/', '\\\^', '\\\$', '\\\=', "\\'", "\\x00", "\\n", "\\r", "\\x1a", '\\\;');
			return str_replace($from,$to,$string);
		}
		static function slug($string)
		{
			$string=strtolower($string);
			$string=preg_replace('#(^| )\w\'#i',' ',Tools::removeAccents($string));
			$string=preg_replace('#\(.+?\)#',' ',$string);
			$string=preg_replace('#[^a-z0-9 ]#',' ',$string);
			$string=preg_replace('# +#','-',trim($string));
			return $string;
		}
		static function date($date)
		{
			if(empty($date)) return '"non précisé"';
			if(is_string($date)) $date=strtotime($date);
			setlocale(LC_TIME,"fr_FR.utf8");
			return strftime('%d %b %Y',$date);
		}
		static function cleanInvisibleChar($text)
		{
			return filter_var(preg_replace('/[[:space:]]+/',' ',$text), FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW);
		}

		static function parseHeaders($response)
		{
			$parse=array();
			foreach($response as $field)
				if(preg_match('#^(.*?): (.*)$#',$field,$m))
					if(!array_key_exists($m[1],$parse))
						$parse[$m[1]]=$m[2];
					else
					if(is_array($parse[$m[1]]))
						$parse[$m[1]][]=$m[2];
					else
						$parse[$m[1]]=array($parse[$m[1]],$m[2]);
			return $parse;
		}
		static function getUrlContent($url,$data=null,$timeout=5)
		{
			$html=false;
			$cookiesSaveds=array();
			$t=microtime(true);
			for($i=0;$i<10;$i++)
			{
				$host=false;
				$html=false;
				$cookies=array();

				if(preg_match('#^https?://(.*?)(/|\?|$)#',$url,$m))
					$host=$m[1];

				if($host && array_key_exists($host,$cookiesSaveds))
					foreach($cookiesSaveds[$host] as $k=>$cookie)
						$cookies[]=sprintf('%s=%s',$k,$cookie);

				/* Test le timeout avant itération et le recalcule pour la suivante */
				$to=$timeout-(microtime(true)-$t);
				if($to<=0) break;

				$options=array(
						"ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false),
						'http'=>array(
							'timeout'=>$to,
							'follow_location'=>0,
							'user_agent'=>'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36'
						));
				if(!empty($cookies)) $options['http']['header']='Cookie: '.implode("; ",$cookies).";\n";

				if($ctx=stream_context_create($options))
					if(($html=@file_get_contents($url,false,$ctx))!==false)
					{
						//echo $html;
						$headers=self::parseHeaders($http_response_header);
						if(!array_key_exists('Location',$headers)) break;

						$url=$headers['Location'];
						if(array_key_exists('Set-Cookie',$headers))
							foreach(is_array($headers['Set-Cookie'])?$headers['Set-Cookie']:array($headers['Set-Cookie']) as $cookie)
							{
								//$c=array_map(function($e) {$e=explode('=',$e); return array($e[0]=>$e[1]);},preg_split('#; *#',$cookie));
								//print_r($c);
								if(preg_match('#^(.*?)=(.*?);#',$cookie,$m))
									$cookiesSaveds[$host][$m[1]]=$m[2];
							}
					}
			}
			return $html;

			if(0)
			{
				$response=false;
				if($res=curl_init())
				{
					$_curl = curl_init();
					curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, 1);
					curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($_curl, CURLOPT_COOKIEFILE, './cookiePath.txt');
					curl_setopt($_curl, CURLOPT_COOKIEJAR, './cookiePath.txt');
					curl_setopt($_curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; InfoPath.1)');
					curl_setopt($_curl, CURLOPT_FOLLOWLOCATION, true); //new added
					curl_setopt($_curl, CURLOPT_URL, $url);
					$response = curl_exec( $_curl );
					var_dump(curl_error( $_curl));
					curl_close($res);
					return $response;
				}
				return $response;
			}
		}
		static function getMetierLink($rome,$dep)
		{
			return sprintf('http://candidat.pole-emploi.fr/marche-du-travail/informationssurunmetier?codeRome=%s&codeZoneGeographique=%02d&t:lb=t&typeZoneGeographique=DEPARTEMENT',$rome,$dep);
		}
		static function getPERomeLink($rome)
		{
			return "http://candidat.pole-emploi.fr/marche-du-travail/fichemetierrome?codeRome=".$rome;
		}
		static function getPeSearchLink($db,$rome,$locationPath='',$contract='')
		{
			//lieux=44109&motsCles=K2501&offresPartenaires=true&rayon=20&tri=0
			//https://candidat.pole-emploi.fr/offres/recherche?lieux=44109&motsCles=K2501&offresPartenaires=true&rayon=20&tri=0
			$ref=new Reference($db);

			$c1=$c2=$p1=$locType=$dep='';
			$params=array();

			if($contract=='CDD' || $contract=='CDI') $params['typeContrat']=$contract;
			if($contract=='APPRENTISSAGE') $params['natureOffre']='E2';
			if($contract=='PROFESSIONNALISATION') $params['natureOffre']='FS';

			if($locationPath)
				if(($level=Reference::getLevelFromPath($locationPath))>=3)
					if($loc=$ref->get('LOCATION',$locationPath))
					{
						$loc=array_values($loc)[0];
						// departement
						if($level==4)
						{
							$loc=Reference::extraData('dn',$loc['extradata'],'').'D';
							$params['lieux']=$loc;
						} else
						// region
						if($level==3)
						{
							$loc=Reference::extraData('in',$loc['extradata'],'').'R';
							$params['lieux']=$loc;
						} else
						if($level>=5)
						{
							$loc=Reference::extraData('in',$loc['extradata'],'');
							$params['lieux']=$loc;
						}
					}
			if($rome) $params['motsCles']=$rome;
			$params['offresPartenaires']='true';
			$params['rayon']=20;
			$params['tri']='0';

			//$url=sprintf('https://candidat.pole-emploi.fr/offres/recherche?lieux=%s&motsCles=%s&offresPartenaires=true&rayon=10&tri=0',$loc,$rome,$contract,$nature);
			$url=sprintf('https://candidat.pole-emploi.fr/offres/recherche?%s',http_build_query($params));
			return $url;
		}
		static function getNbPEOffers($db,$rome,$locationPath='',$contract='')
		{
			//$cache=new QCache(3600*24,array('mode'=>'FILE','group'));
			$nb=0;
			$key=sprintf('TOOLS_GETNBPEOFFERS:%s_%s_%s',$rome,$locationPath,$contract);

			//if(($return=$cache->get($key,false))!==false) return $return;
			$page=self::getUrlContent(self::getPeSearchLink($db,$rome,$locationPath,$contract));
			if(preg_match("#<h1.*?>(.*?)</h1>#sim",$page,$m))
				if(preg_match("#(\d+) offre#i",$m[1],$m))
					$nb=$m[1];

			//$cache->set($key,$nb);
			return $nb;
		}
		static function getPeOffers($db,$romes,$locationPath='',$contract='')
		{
			$cache=new QCache(3600*24,array('mode'=>'FILE','group'=>'nboffers'));
			if(!is_array($romes)) $romes=array($romes);
			sort($romes); //Pour la clef de cache, les codes ROME seront toujours dans le meme ordre
			$key=sprintf('TOOLS_GETPEOFFERS:%s_%s_%s',implode('|',$romes),$locationPath,$contract);
			if(($return=$cache->get($key,false))!==false) return $return;
			$tab=$dep=array();
			$totalNbOffers=0;

			if(!$romes && !$locationpath) _QUARKLOG('pe_crawl.log',"$romes ; $locationPath");

			/* Récupération des données du lieu en fonction du locationpath */
			$dep='';
			$ref=new Reference($db);
			if($locationPath)
				if(Reference::getLevelFromPath($locationPath)>=5)
					if($loc=$ref->get('LOCATION',$locationPath))
					{
						$loc=array_values($loc)[0];
						//$dep=Reference::extraData('dn',$loc['extradata'],'');
						$locLabel=sprintf('%s (%s)',$loc['label'],substr(Reference::extraData('zc',$loc['extradata']),0,2));
					}
			if($romes)
				foreach($romes as $rome)
					if($rm=$ref->getbyExtraData('ROME','rm',$rome))
					{
						$rm=array_values($rm);
						$nb=Tools::getNbPEOffers($db,$rome,$locationPath,$contract);
						$tab[$rome]=array('nb'=>$nb,'label'=>$rm[0]['label'],'dep'=>$dep,'link'=>$link=Tools::getPeSearchLink($db,$rome,$locationPath));
						$totalNbOffers+=$nb;
					}
			$return=array('total'=>$totalNbOffers,'loclabel'=>$locLabel,'dep'=>$dep,'offers'=>$tab);
			$cache->set($key,$return);
			return $return;
		}
		static function compressArgs($args)
		{
			return self::basicCrypt(bzcompress(serialize($args)));
		}
		static function unCompressArgs($args)
		{
			$args=self::basicUncrypt($args);
			if($args===false) return false;
			return unserialize(bzdecompress($args));
		}
		static function basicCrypt($str)
		{
			$str=base64_encode($str);
			$checksum=substr(md5($str.CRYPT_PASS),-8);

			$array=array(
				'A'=>'s','B'=>'4','C'=>'R','D'=>'c','E'=>'o','F'=>'g','G'=>'y','H'=>'w',
				'I'=>'m','J'=>'H','K'=>'7','L'=>'E','M'=>'a','N'=>'Y','O'=>'X','P'=>'8',
				'Q'=>'G','R'=>'B','S'=>'k','T'=>'i','U'=>'I','V'=>'A','W'=>'T','X'=>'D',
				'Y'=>'p','Z'=>'J','a'=>'2','b'=>'L','c'=>'M','d'=>'=','e'=>'d','f'=>'C',
				'g'=>'Q','h'=>'f','i'=>'r','j'=>'P','k'=>'N','l'=>'V','m'=>'v','n'=>'0',
				'o'=>'K','p'=>'e','q'=>'j','r'=>'Z','s'=>'9','t'=>'h','u'=>'F','v'=>'t',
				'w'=>'-','x'=>'U','y'=>'1','z'=>'x','0'=>'3','1'=>'u','2'=>'5','3'=>'q',
				'4'=>'W','5'=>'S','6'=>'6','7'=>'n','8'=>'b','9'=>'O','+'=>'_','/'=>'l',
				'='=>'z');

			$str=$checksum.$str;
			for($idx=0;$idx<strlen($str);$idx++) $str{$idx}=$array[$str{$idx}];

			return $str;
		}
		static function basicUncrypt($str)
		{
			$array=array(
				's'=>'A','4'=>'B','R'=>'C','c'=>'D','o'=>'E','g'=>'F','y'=>'G','w'=>'H',
				'm'=>'I','H'=>'J','7'=>'K','E'=>'L','a'=>'M','Y'=>'N','X'=>'O','8'=>'P',
				'G'=>'Q','B'=>'R','k'=>'S','i'=>'T','I'=>'U','A'=>'V','T'=>'W','D'=>'X',
				'p'=>'Y','J'=>'Z','2'=>'a','L'=>'b','M'=>'c','='=>'d','d'=>'e','C'=>'f',
				'Q'=>'g','f'=>'h','r'=>'i','P'=>'j','N'=>'k','V'=>'l','v'=>'m','0'=>'n',
				'K'=>'o','e'=>'p','j'=>'q','Z'=>'r','9'=>'s','h'=>'t','F'=>'u','t'=>'v',
				'-'=>'w','U'=>'x','1'=>'y','x'=>'z','3'=>'0','u'=>'1','5'=>'2','q'=>'3',
				'W'=>'4','S'=>'5','6'=>'6','n'=>'7','b'=>'8','O'=>'9','_'=>'+','l'=>'/',
				'z'=>'=');

			$len=strlen($str);
			for($idx=0;$idx<$len;$idx++)
				if(isset($array[$str{$idx}]))
					$str{$idx}=$array[$str{$idx}];

			$checksum=substr($str,0,8);
			$str=substr($str,8);
			if(substr(md5($str.CRYPT_PASS),-8)==$checksum) return base64_decode($str);
			return false;
		}
		static function encodeArgs(&$args,$arg)
		{
			if(!empty($args)) $args=array($arg=>self::compressArgs($args));
		}
		static function decodeArgs(&$args,$arg)
		{
			if(array_key_exists($arg,$args))
			{
				$a=self::unCompressArgs($args[$arg]);
				if($a!==false)
				{
					$args=array_merge($args,$a);
					unset($args[$arg]);
					return true;
				}
			}
			return false;
		}
		static function displayField($lineParser,$convBullet=false)
		{
			$inner=$lineParser->inner();
			switch($lineParser->getAttr('type'))
			{
				case 'html':
					return $inner;
				case 'email':
					$line=sprintf('<a href="mailto:%s">%s</a>',$inner,$inner);
					break;
				case 'url':
					if(strtolower(substr($inner,0,4))!='http') $inner="http://$inner";
					$line=sprintf('<a target="_blank" href="%s" rel="nofollow">%s</a>',$inner,str_replace(array('http://','https://'),'',$inner));
					break;
				default:
					$line=trim($inner);//preg_replace('#[\xc2]#sui',' ',$inner));
					if(1)
					$line=trim(preg_replace_callback('#&?(eacute|[aei]grave|[aeuoi]circ);#sui',function($m)
						{
							return Tools::text2Html('&'.$m[1].';');
						},$line));
					$line=trim(preg_replace('#([cdjlmnst])\?([a-z])#sui',"$1'$2",$line));
					$line=trim(preg_replace_callback('#([^-+&A-Za-z0-9 \n\r_àäâéèëêôçïîüûù\[\].:;,\'=<>"\(\)\|/?!%*\#])#sui',
						function($m)
						{
							if(ord($m[1])==0xc2) return ' ';
							if(ord($m[1])==0xe2) return "'";
							return sprintf('#0x%02lx#',ord($m[1]));
						},$line));
					if($convBullet)
					{
						$line=preg_replace_callback('#^(- .*?)^([A-Za-z])#smi',function($m)
							{
								$a=preg_replace_callback('#^- +(.*)$#mi',function($m)
									{
										return '<li>'.Tools::text2Html($m[1]).'</li>';
									},$m[1]);
								return "<ul>$a</ul>\n".$m[2];
							},$line);
						return $line;
					}
					break;
			}
			return /*$lineParser->getAttr('id','')."=".*/Tools::text2Html(trim($line),false);
		}
		static function calcDiffDate($b,$e,$mode='month')
		{
			$b=new DateTime($b);
			$e=new DateTime($e);
			$d=$b->diff($e);
			if($mode=='month') return $d->format("%y")*12+$d->format("%m");
			return $d->days;
		}
		static function calcDiffTime($b,$e,$mode='month')
		{
			return self::calcDiffDate(date('Y-m-d',$b),date('Y-m-d',$e),$mode);
		}
		static function apiGetLbb($db,$rome_codes,$locationPath,$lba=false,$distance=10,$pageSize=3,$departements="")
		{
			if(empty($rome_codes) || empty($locationPath)) return array();
			$cache=new QCache(3600*1,array('mode'=>'FILE','group'=>'apilbb'));
			if(!is_array($rome_codes)) $rome_codes=array($rome_codes);
			sort($rome_codes); //Pour la clef de cache, les codes ROME seront toujours dans le meme ordre
			$pmsmp=1;
			$key=sprintf('TOOLS_APIGETLBB:%s_%s_%s_%d_%s_*d',implode('|',$rome_codes),$lba?'lba':'lbb',$locationPath,$distance,str_replace(',','',$departements),$pmsmp);
			$romeCode=$rome_codes[0];//implode(',',$rome_codes),
			if(empty($romeCode)) return array();
			if(($return=$cache->get($key,false))!==false) return $return;

			$ref=new Reference($db);
			$locationPath=Reference::subPath($locationPath,5); //Si supérieur à 6 (arrondissements) alors on revient à la ville (LBB ne supporte pas les arrond)
			//print $ref->getLevelFromPath($locationPath);
			if($locationPath)
				if($distance && $ref->getLevelFromPath($locationPath)>=4)
				{
					if(!empty($loc=$ref->get('LOCATION',$locationPath)))
					{
						$loc=array_values($loc)[0];
						$codeInseeCommune=Reference::extraData('in',$loc['extradata']);
						if(empty($codeInseeCommune)) return array();

						if(0)
						{
							/* récupération du tocken ESD LBB */
							$clientId=ESDLBB_CLIENTID;
							$clientSecret=ESDLBB_CLIENTSECRET;

							$post=array(
								"realm"=>"/partenaire",
								"grant_type"=>"client_credentials",
								"client_id"=>$clientId,
								"client_secret"=>$clientSecret,
								"scope"=>"application_$clientId api_labonneboitev1"
							);
							$opts=array(
								"http"=>array(
									"method"=>"post",
									"header"=>"Content-Type: application/x-www-form-urlencoded",
									"content"=>http_build_query($post)
								)
							);
							$access_token='';
							if($context=stream_context_create($opts))
								if($json=file_get_contents("https://entreprise.pole-emploi.fr/connexion/oauth2/access_token",false,$context))
								{
									$json=json_decode($json,true);
									$access_token=$json['access_token'];
								}
							/* Récupération des données de l'API */
							if($access_token)
							{
								$opts=array(
									"http"=>array(
										"method"=>"post",
										"header"=>"Authorization: Bearer $access_token"
									)
								);

								$get=array(
									//'latitude'=>$loc['lat'],
									//'longitude'=>$loc['lng'],
									'commune_id'=>$codeInseeCommune,
									'rome_codes'=>$romeCode,
									'page_size'=>$pageSize,
									'flag_pmsmp'=>0
								);
								if($context=stream_context_create($opts))
								{
									$url=sprintf("https://api.emploi-store.fr/partenaire/labonneboite/v1/company/?%s",http_build_query($get));

									if($json=file_get_contents($url,false,$context))
									{
										$json=json_decode($json,true);
										$json['companies_url']=sprintf('%s/entreprises/commune/%s/rome/%s',URL_LBB,$ref->getExtraData('in',$loc['extradata']),$rome_codes[0]);
										$json['search_location']=sprintf('%s (%2s)',$loc['label'],substr($loc['zipcode'],0,2));

										$cache->set($key,$json);
										return $json;
									}
								}
							}
						} else
						{
							$params=array(
								'commune_id'=>$codeInseeCommune,
								'contract'=>$lba?'alternance':'',
								'departments'=>$departements,
								'distance'=>$distance,
								'flag_pmsmp'=>1,
								'page_size'=>$pageSize,
								//'naf_codes'=>implode(',',is_array($naf_codes)?$naf_codes:array($naf_codes)),
								'rome_codes'=>implode(',',is_array($rome_codes)?$rome_codes:array($rome_codes)),
								'timestamp'=>gmdate("Y-m-d\TH:i:s"),
								'user'=>'labonneformation',
							);
							if (!$lba) {unset($params['contract']);} // Il ne doit pas y avoir le paramètre du tout pour lbb

							$hmac_key=LBB_HMACKEY;
							$params+=array('signature'=>hash_hmac('md5',http_build_query($params),$hmac_key));

							$api_host=URL_LBB;
							$url=sprintf('%s/api/v1/company/?%s',$api_host,http_build_query($params));
							$urlList=sprintf('%s/entreprises/commune/%s/rome/%s',$api_host,$ref->getExtraData('in',$loc['extradata']),$rome_codes[0]);

							if($data=Tools::getUrlContent($url,null,5))
								if($json=json_decode($data,true))
									if(isset($json['companies']))
									{
										$json['companies_url']=sprintf('%s/entreprises/commune/%s/rome/%s?d=%ld',URL_LBB,$ref->getExtraData('in',$loc['extradata']),$rome_codes[0],$distance); // xxx remplacer par le champ 'url' renvoyé par l'API
										$json['search_location']=sprintf('%s (%2s)',$loc['label'],substr($loc['zipcode'],0,2));
										$cache->set($key,$json);
										return $json;
									}
						}
					}
				}
			return array();
		}
		static function peConnectGetForwardUrl($quark,$uri)
		{
			$session=$quark->getSession();
			$state=sha1(rand(0,1000000000));
			$nonce=sha1(rand(0,1000000000));

			$session->set('peconnect_connection',
				array(
					'STATE'=>$state,
					'NONCE'=>$nonce,
					'uri'=>$uri
				));

			$params=array(
				'realm'=>'/individu',
				'response_type'=>'code',
				'client_id'=>ESD_CLIENTID,
				'scope'=>'application_'.ESD_CLIENTID.' email api_peconnect-individuv1 openid profile',
				'redirect_uri'=>URL_BASE.'/peconnect_callback',
				'state'=>$state,
				'nonce'=>$nonce,
				//'prompt'=>'none'
			);
			$url=sprintf('https://authentification-candidat.pole-emploi.fr/connexion/oauth2/authorize?%s',http_build_query($params));
			return $url;
		}
		static function peConnectGetAccessToken($quark,$args)
		{
			$session=$quark->getSession();

			if($peConnect=$session->get('peconnect_connection'))
				if($args['state']==$peConnect['STATE'])
					if(array_key_exists('code',$args))
					{
						$post=array(
							//'realm'=>'/individu',
							"grant_type"=>"authorization_code",
							"code"=>$args['code'],
							"client_id"=>ESD_CLIENTID,
							"client_secret"=>ESD_CLIENTSECRET,
							'redirect_uri'=>URL_BASE.'/peconnect_callback',
						);
						$opts=array(
							"http"=>array(
								'method'=>'POST',
								'header'=>'Content-Type: application/x-www-form-urlencoded',
								'content'=>http_build_query($post),
								'ignore_errors'=>true
							),
							"ssl"=>array(
								'verify_peer'=>false,
								'verify_peer_name'=>false
							)
						);
						$url='https://authentification-candidat.pole-emploi.fr/connexion/oauth2/access_token?realm=%2findividu';
						if($context=stream_context_create($opts))
							if(($json=@file_get_contents($url,false,$context))!==false)
								if(($json=json_decode($json,true))!==false)
									if(array_key_exists('nonce',$json) && array_key_exists('access_token',$json))
									{
										$nonce=$json['nonce'];
										$access_token=$json['access_token'];
										if($nonce==$peConnect['NONCE'])
											return $access_token;
									}
					}
			return false;
		}
		static function peConnectGetIndividu($access_token)
		{
			$opts=array(
				"http"=>array(
					'method'=>'GET',
					'max-redirects'=>10,
					'header'=>"Authorization: Bearer $access_token\r\n",
					'ignore_errors'=>true
				),
				"ssl"=>array(
					'verify_peer'=>false,
					'verify_peer_name'=>false
				)
			);
			if($context=stream_context_create($opts))
			{
				$individu=@file_get_contents('https://api.emploi-store.fr/partenaire/peconnect-individu/v1/userinfo',false,$context);
				return $individu;
			}
			return false;
		}

		static function apiGetLbbExpanse($db,$rome_codes,$locationPath)
		{
			for($i=1;$i<=3;$i++)
			{
				$dist=$i*10;
				$lbb=self::apiGetLbb($db,$rome_codes,$locationPath,false,$dist);
				if(!empty($lbb))
					return $lbb;
			}
			return array();
		}
		static function calcColorSpread($c1,$c2,$tx)
		{
			$c1=sscanf($c1,"%02x%02x%02x");
			$c2=sscanf($c2,"%02x%02x%02x");
			$dr=ceil(($c2[0]-$c1[0])*$tx/100);
			$dg=ceil(($c2[1]-$c1[1])*$tx/100);
			$db=ceil(($c2[2]-$c1[2])*$tx/100);

			return sprintf('%02x%02x%02x',$c1[0]+$dr,$c1[1]+$dg,$c1[2]+$db);
		}
		static function rateTx($tx)
		{
			return $tx;
			/* Ne sert plus car le tx est fourni sur 5 echelons et traduit en taux de 0 à 1 dans le BD */
			//if($tx>=50) return 5;
			//if($tx>=40) return 4.5;
			//if($tx>=30) return 4;
			//if($tx>=25) return 3.5;
			//if($tx>=20) return 3;
			//if($tx>=15) return 2.5;
			//if($tx>=10) return 2;
			//return 1;
		}
		static function splitSessions($sessionList)
		{
			$now=time();
			$encours=$avenir=array();
			foreach($sessionList as $k=>$session)
			{
				$debut=strtotime($session['debut']);
				$fin=strtotime($session['fin']);
				if((is_null($debut) || $debut<=$now) &&
				   (is_null($fin) || $fin)>=$now)
					$encours[]=$session;
				else
					$avenir[]=$session;
			}
			return array('encours'=>$encours,'avenir'=>$avenir);
		}
		/* Fonctions privees */
		static function format($f,$s,&$offset,&$args) /* public pour PHP 5.3 */
		{
			switch($s)
			{
				case 'nf':
					$dec=2;
					$number=$args[$offset];
					$args[$offset]=is_null($args[$offset])?null:number_format($number,is_float($number)?$dec:0,',',' ');
					$offset++;
					return $f.'s';
				case '%':
					return '%%';
			}
			$offset++;
			return $f.$s;
		}
		static function vsprintf($string,&$args)
		{
			$a=0;
			$string=preg_replace_callback('/(%[^%]*?)(%|nf|[a-zA-Z])/s',
				function($arg) use(&$a,&$args)
				{
					return Tools::format($arg[1],$arg[2],$a,$args);
				},$string);
			return vsprintf($string,$args);
		}
		static function sprintf($request)
		{
			$args=func_get_args();
			array_shift($args);
			return Tools::vsprintf($request,$args);
		}
		static function inDateInterval($date,$begin,$end)
		{
			if(strcmp($date,$begin)>=0 && strcmp($date,$end)<=0)
				return true;
			return false;
		}
		static function queryControl($query,$password,$timeControl=600,$hashMethod='md5')
		{
			assert(!empty($query));
			assert(!is_null($password));
			assert(!empty($hashMethod));
			$signature=$query['signature'];
			if(!$signature) return false;
			unset($query['signature']);
			$calcSignature=hash_hmac($hashMethod,http_build_query($query),$password);
			if($calcSignature!==$signature) return false;
			if($timeControl!==false)
			{
				if(!array_key_exists('timestamp',$query)) return false;
				$time=gmdate("Y-m-d\TH:i:s",time()-$timeControl);
				if($time>$query['timestamp']) return false;
			}
			return true;
		}
		static function querySign($query,$password,$insertTimestamp=true,$hashMethod='md5')
		{
			assert(!empty($query));
			assert(!empty($password));
			assert(!empty($hashMethod));
			if($insertTimestamp) $query['timestamp']=gmdate("Y-m-d\TH:i:s");
			$query['signature']=hash_hmac($hashMethod,http_build_query($query),$password);
			return $query;
		}
		static function fmt($num)
		{
			return number_format($num,0,',',' ');
		}
		static function htmlToTxt($html)
		{
			$html=self::cleanInvisibleChar($html);
			return strip_tags(preg_replace('/\<br(\s*)?\/?\>/i', "\n", html_entity_decode($html)));
		}
		static function array_filter_recursive($input,$callback=null) 
		{ 
			foreach($input as &$value) 
				if(is_array($value)) 
					$value=self::array_filter_recursive($value,$callback); 
			return array_filter($input,$callback); 
		} 
	}
?>
