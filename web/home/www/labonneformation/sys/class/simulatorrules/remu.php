<?php
	/* Sert à rien, mais à voir si ce ne serait pas interessant */
	class DefaultGet
	{
		protected $array;

		public function __construct($array)
		{
			$this->array=$array;
		}
		public function get($key=null,$default=null)
		{
			if(is_null($key)) return $this->array;
			return array_key_exists($key,$this->array)?$this->array[$key]:$default;
		}
	}
	function hasKeywords($keyArray,$str)
	{
		if(!is_array($keyArray)) $keyArray=array($keyArray);
		$str=strtoupper(preg_replace("#[- ,()]+#i"," ",$str));
		return in_multiarray($keyArray,explodeTitle($str));
	}
	function hasStrings($stringArray,$str)
	{
		if(!is_array($stringArray)) $stringArray=array($stringArray);
		$str=strtoupper(preg_replace("#[-. ,()]+#i"," ",$str));
		foreach($stringArray as $string)
		{
			if(strpos($str,$string)!==false)
				return true;
		}
		return false;
	}
	function arrayInsertAfterKey(&$droits,$key,&$array,$item)
	{
		$ret=array();
		$i=array_keys($item);
		foreach($array as $k=>$v)
		{
			$ret[$k]=$v;
			if($k==$key) $ret[$i[0]]=$item[$i[0]];
		}
		$array=$ret;
		unset($droits[$key]);
	}
	function getOffers($db,$romeCode,$contract='',$locationPath='')
	{
		if(empty($romeCode)) return '';
		if($contract=='PROFESSIONALISATION') $cont="contrat de professionnalisation";
		if($contract=='ALTERNANCE') $cont="contrat d'apprentissage";
		if($contract=='CDD') $cont="contrat à durée déterminée";
		$doc=array();
		$offers=array();

		if($romeCode && $locationPath)
			if($offers=Tools::getPeOffers($db,$romeCode,$locationPath,$contract))
			{
				foreach($offers['offers'] as $rome=>$info)
				{
					$d=array();
					$expandText=$link=$antiLink='';
					if($info['nb'])
					{
						$text=sprintf("%ld offre%s",$info['nb'],$info['nb']>1?'s':'');
						$link=sprintf("<a href=\"%s\" target=\"_blank\">",Tools::getPeSearchLink($db,$rome,$locationPath));
						$antiLink='</a>';
					} else
					{
						$text="Il n'y a actuellement pas d'offre";
						$expandText=sprintf("<br/><a href=\"%s\" target=\"_blank\">Elargissez votre recherche d'offres</a><br/><br/>",Tools::getPeSearchLink($db,$rome));
					}
					//echo $link;
					$d[]=sprintf("%s%s d'emploi pour le métier de &laquo;&nbsp;%s&nbsp;&raquo; sur &laquo;&nbsp;%s&nbsp;&raquo; %s%s",$link,$text,$info['label'],$offers['loclabel'],$expandText,$antiLink);
					if($contract)
						if($offersAlt=Tools::getPeOffers($db,$rome,$locationPath,$contract))
						{
							if($offersAlt['total']>0)
								$d[]=sprintf("<span style=\"margin-left:20px; margin-bottom:20px;\"></span>... dont <a href=\"%s\" target=\"_blank\">%ld en %s</a>",
								             Tools::getPeSearchLink($db,$rome,$locationPath,$contract),
								             $offersAlt['total'],$cont);
						}
					$doc[]=implode("<br/>\n",$d);
				}
			}
		if(!is_array($romeCode)) $romeCode=explode(' ',$romeCode);
		if(!empty($romeCode))
			if($companies=Tools::apiGetLbb($db,$romeCode[0],$locationPath))
				if(!empty($companies['compagnies']))
				{
					$ref=new Reference($db);
					if($loc=$ref->get('LOCATION',$locationPath))
					{
						$nbCompagnies = count($companies);
						$conjugaison = ($nbCompagnies <= 1) ? "employeur est susceptible" : "employeurs sont susceptibles";
						
						$text=sprintf('<br/>%d '. $conjugaison .' de recruter après cette formation autour de &laquo;&nbsp;%s&nbsp;(%d)&nbsp;&raquo; dont :<br/>',$nbCompagnies,$loc[$locationPath]['label'],substr($loc[$locationPath]['zipcode'],0,2));
						$i=1;
						foreach($companies['compagnies'] as $comp)
						{
							if(!array_key_exists('url',$comp)) _QUARKLOG('companies.log',print_r($companies,true));
							$text.=sprintf('- <a href="%s" target="_blank">%s</a><br/>',$comp['url'],$comp['name']);
							if($i++>=3) break;
						}

						$doc[]=$text;
					}
				}
		//var_dump($companies);
		//print_r($doc);
		return implode('<br/>',$doc);
	}
	function convDate($date)
	{
		if(preg_match('#^(\d+)/(\d+)/(\d+)$#',$date,$m))
			return sprintf('%04d-%02d-%02d',$m[3],$m[2],$m[1]);
		return false;
	}
	/*function cleanTxt($text)
	{
		$text=preg_replace_callback('#^.*$#mi',function($m)
			{
				return trim($m[0]);
			},$text);
		$text=preg_replace("#\n+#smi","\n",$text);
		return trim($text);
	}*/
	function calcAge($date)
	{
		$date=trim($date);
		//$date=preg_replace('#(\d+)/(\d+)/(\d+)#','$2/$1/$3',$date);
		$date=date_create_from_format('d/m/Y',$date);
		if(!$date) return 0;
		return (int)((time()-$date->getTimestamp())/3600/24/365);
	}
	function calcDate($monthToAdd)
	{
		$date=new DateTime();
		if($monthToAdd<0)
			$calc=$date->sub(new DateInterval(sprintf('P%dM',abs($monthToAdd))));
		else
			$calc=$date->add(new DateInterval(sprintf('P%dM',abs($monthToAdd))));
		return date_format($calc,'d/m/Y');
	}
	function display($pri,$str,$option=array())
	{
		if(!is_callable($str)) return Tools::Text2Html($str,false);
		return $str($pri,$option);
	}
	function in_multiarray($keys,$array)
	{
		if(!is_array($keys)) $keys=array($keys);
		foreach($keys as $key)
			if(in_array($key,$array))
				return true;
		return false;
	}
	function explodeTitle($title)
	{
		$title=preg_replace('#[^\w]#',' ',$title);
		return preg_split('#[\t ]+#',trim($title));
	}
	function bestRate($ad,$ar)
	{
		return (int)$ar['niveau-retour-embauche:0'];
		//if($ad['bassinrate']) return $ad['bassinrate'];
		//if($ad['departementrate']) return $ad['departementrate'];
		//if($ad['regionalrate']) return $ad['regionalrate'];
		//return $ad['nationalrate'];
	}
	/******************************************************************************************************************************/
	function condRff($var)
	{
		extract($var);
		/* Calcul de la rémunération de fin de formation pour
		   - les Pays de la Loire
		   - Centre Val de Loire
		   - PACA
		   - Corse
		   prend en compte les rome visés par la formation */
		$rff_auvergnerhonealpes=array('A1204','A1412','A1413','B1201','B1602','B1604','B1801','B1802','B1803','B1804','C1102','C1109','C1501','C1502','C1503','C1504','D1101','D1101','D1102','D1103','D1104','D1105','D1206','D1207','D1301','D1402','D1403','D1404','D1405','D1406','D1407','D1501','D1504','D1506','D1509','F1101','F1102','F1103','F1104','F1105','F1106','F1107','F1108','F1201','F1202','F1203','F1204','F1601','F1602','F1603','F1604','F1605','F1606','F1607','F1609','F1706','G1203','G1501','G1502','G1503','G1602','G1701','G1702','G1703','G1801','G1802','G1803','G1804','H1101','H1102','H1201','H1203','H1206','H1208','H1209','H1301','H1303','H1404','H1503','H2101','H2102','H2203','H2206','H2301','H2401','H2402','H2403','H2404','H2405','H2406','H2407','H2408','H2409','H2410','H2411','H2412','H2413','H2414','H2415','H2502','H2503','H2504','H2601','H2602','H2604','H2701','H2801','H2802','H2803','H2804','H2805','H2901','H2902','H2903','H2904','H2905','H2906','H2907','H2908','H2909','H2910','H2911','H2912','H2913','H2914','H3101','H3102','H3201','H3202','H3203','H3301','H3302','H3303','H3401','H3402','H3403','H3404','I1101','I1103','I1203','I1301','I1302','I1304','I1305','I1306','I1307','I1308','I1309','I1310','I1401','I1503','I1601','I1602','I1603','I1604','I1605','I1606','I1607','J1104','J1304','J1403','J1404','J1406','J1412','J1501','J1502','J1503','J1504','J1505','J1506','J1507','K1202','K1302','K1305','K2110','K2201','K2301','K2306','K2501','K2502','K2503','M1101','M1102','M1701','M1702','M1703','M1704','M1705','M1706','M1707','M1801','M1802','M1803','M1804','M1805','M1805','M1806','M1807','M1810','N4402');
		$rff_paysdelaloire=array('A1101','A1201','A1202','A1302','A1401','A1402','A1405','A1406','A1409','A1411','A1415','A1416','A1417','B1803','C1102','C1206','D1101','D1102','D1103','D1104','D1105','D1106','D1107','D1206','D1209','D1212','D1214','D1301','D1401','D1402','D1403','D1404','D1406','D1407','D1408','D1501','D1502','D1506','D1507','E1301','F1103','F1104','F1106','F1107','F1108','F1201','F1202','F1301','F1302','F1501','F1502','F1503','F1601','F1602','F1603','F1604','F1605','F1606','F1607','F1608','F1609','F1610','F1611','F1612','F1613','F1701','F1702','F1703','F1705','G1202','G1203','G1204','G1401','G1403','G1501','G1502','G1601','G1602','G1603','G1604','G1605','G1703','G1802','G1803','H1101','H1203','H1208','H1303','H1402','H1403','H1404','H1502','H1504','H1506','H2101','H2102','H2202','H2206','H2401','H2402','H2411','H2502','H2503','H2504','H2602','H2603','H2604','H2605','H2901','H2902','H2903','H2904','H2905','H2906','H2909','H2911','H2912','H2913','H2914','H3201','H3202','H3203','H3301','H3302','H3401','H3404','I1102','I1203','I1301','I1302','I1303','I1304','I1305','I1306','I1307','I1308','I1309','I1310','I1401','I1501','I1601','I1602','I1603','I1604','I1606','I1607','J1304','J1305','J1306','J1403','J1404','J1406','J1407','J1501','J1503','J1504','J1506','J1507','K1102','K1201','K1202','K1203','K1206','K1207','K1301','K1302','K1303','K1304','K1305','K1403','K2110','K2111','K2204','K2303','K2304','K2501','K2503','L1509','M1203','M1501','M1605','M1801','M1802','M1803','M1804','M1805','M1806','M1810','N1101','N1103','N1104','N1302','N1303','N2203','N3101','N3102','N4101','N4102','N4103','N4203','N4204');
		$rff_centrevaldeloire=array('A1101','A1203','A1205','A1405','A1414','A1416','D1101','D1102','D1103','D1104','D1106','D1209','D1211','D1212','D1402','D1407','D1408','D1502','D1503','E1205','F1104','F1106','F1108','F1201','F1202','F1301','F1302','F1501','F1502','F1602','F1603','F1604','F1605','F1606','F1607','F1608','F1610','F1611','F1613','F1702','F1703','G1202','G1203','G1204','G1501','G1502','G1601','G1602','G1603','G1703','G1801','G1802','G1803','H1202','H1203','H1303','H1404','H1503','H1506','H2102','H2206','H2301','H2401','H2402','H2415','H2602','H2801','H2901','H2902','H2903','H2906','H2909','H2912','H2913','H2914','H3203','H3301','H3404','I1203','I1301','I1302','I1304','I1306','I1309','I1310','I1501','I1503','I1603','I1604','I1606','J1102','J1304','J1305','J1307','J1403','J1412','J1501','J1502','J1505','J1506','J1507','K1201','K1206','K1207','K1301','K1302','K2110','K2111','K2202','K2204','K2503','K2602','M1101','M1203','M1805','M1810','N1101','N1103','N1201','N1303','N4101','N4103');
		$rff_corse=array('A1201','A1202','A1203','A1204','A1402','A1405','A1407','A1410','A1414','A1416','C1102','D1101','D1102','D1104','D1106','D1202','D1208','D1209','D1301','D1403','D1407','D1505','D1507','F1201','F1202','F1302','F1501','F1502','F1601','F1602','F1603','F1604','F1605','F1606','F1607','F1608','F1609','F1610','F1611','F1612','F1613','F1701','F1702','F1703','F1704','G1501','G1503','G1601','G1602','G1603','G1604','G1605','G1703','G1801','G1802','G1803','H2102','H2206','H2901','H2902','I1203','I1304','I1306','I1309','I1401','I1604','I1606','I1607','J1301','J1304','J1305','J1307','J1501','J1506','K1207','K1302','K1303','K2204','K2503','M1203','M1608','N1103','N1105','N4101','N4103');
		$rff_paca=array('A1101','A1202','A1402','A1405','A1414','C1102','C1401','C1501','C1502','D1101','D1102','D1103','D1105','D1212','D1213','D1401','D1402','D1403','D1406','D1407','D1408','D1502','D1504','D1506','E1101','E1304','F1103','F1104','F1106','F1108','F1605','F1607','F1609','F1610','F1613','F1702','F1705','G1202','G1203','G1205','G1404','G1501','G1502','G1601','G1602','G1603','G1604','G1703','G1803','G1804','H1101','H1202','H1203','H1209','H1303','H1401','H1402','H1403','H1404','H1502','H1504','H1506','H2102','H2301','H2503','H2504','H2604','H2902','H2903','H2905','H2909','H2911','H2914','H3201','H3303','H3404','I1102','I1203','I1302','I1303','I1304','I1307','I1308','I1310','I1503','I1603','I1604','I1606','J1301','J1302','J1303','J1304','J1305','J1307','J1403','J1404','J1406','J1412','J1501','J1502','J1504','J1505','J1506','J1507','K1201','K1202','K1203','K1204','K1301','K1302','K1303','K1305','K1801','K2110','K2107','K2109','K2111','K2201','K2202','K2203','K2301','K2303','K2304','K2305','K2503','M1202','M1203','M1605','M1801','M1802','M1804','M1805','M1806','M1810','N1103','N1104','N4103','N4204');
		$rff_bourgogne=array('A1101','A1201','A1202','A1203','A1204','A1303','A1401','A1402','A1405','A1410','A1412','A1414','A1416','B1604 ','B1802','C1102','C1109','C1201','C1206','C1504','D1101 ','D1102','D1103','D1104','D1105','D1106','D1211','D1212','D1213','D1301','D1401','D1402','D1403','D1406','D1407 ','D1408','D1502','D1503','D1505','D1506','D1508','D1509','E1301','E1302','E1308','F1104','F1106','F1107','F1108','F1201 ','F1202 ','F1203','F1204','F1301','F1302 ','F1401','F1501','F1502','F1602','F1603 ','F1604','F1605 ','F1606','F1607','F1608','F1609','F1610 ','F1611','F1613','F1701 ','F1702','F1703','F1704','F1705 ','F1706','G1401','G1501','G1502 ','G1503','G1601','G1602','G1603','G1605','G1702','G1703','G1801 ','G1802','G1803 ','G1804','H1101','H1102','H1202','H1203 ','H1205','H1206 ','H1207','H1208','H1209 ','H1210','H1301','H1303','H1401','H1402','H1403','H1404','H1501','H1502','H1503','H1504','H1506','H1506 ','H2101','H2102','H2201','H2202','H2203 ','H2206','H2209','H2301','H2401 ','H2501','H2502','H2503','H2504 ','H2601','H2602','H2603 ','H2604','H2605 ','H2801','H2802','H2901','H2902 ','H2903','H2904','H2905','H2906 ','H2907','H2908','H2909','H2910','H2911','H2912','H2913','H2914','H3101','H3201','H3202 ','H3203','H3401','H3402 ','H3403','H3404','I1101','I1102','I1103','I1203','I1301','I1302','I1303','I1304','I1305','I1306 ','I1307','I1308','I1309 ','I1310','I1401','I1402','I1601','I1602','I1603','I1604','I1605','I1606 ','I1607 ','J1305 ','J1404','J1406','J1412','J1501','J1505','J1506','K1101','K1102','K1201','K1207','K1302','K1303 ','K1304 ','K1305','K1705','K1706','K2202','K2204','K2301','K2303','K2304 ','K2503','M1202','M1203','M1204','M1206','M1705','M1801','M1802','M1804','M1805','M1807','N1101 ','N1103','N1105','N4101 ','N4103','N4105','N4203','N4204');
		$rff_franchecomte=$rff_bourgogne;
		$rff_iledefrance=array('A1101','A1203','C1102','C1109','C1401','C1502','C1504','D1101','D1102','D1104','D1106','D1202','D1204','D1205','D1208','D1209','D1212','D1401','D1402','D1403','D1407','D1408','D1501','D1502','D1503','D1506','D1507','D1509','E1302','F1103','F1104','F1106','F1107','F1108','F1201','F1202','F1605','F1607','F1610','F1613','F1705','G1204','G1205','G1401','G1404','G1502','G1601','G1602','G1603','G1604','G1703','G1803','H1101','H1102','H1202','H1203','H1208','H1403','H1404','H1502','H1503','H1504','H1506','H2301','H2502','H2503','H2504','H2604','H2605','H2901','H2902','H2903','H2913','H2914','I1101','I1201','I1302','I1303','I1304','I1305','I1306','I1307','I1308','I1309','I1310','I1401','I1602','I1603','I1604','J1302','J1303','J1412','J1502','J1506','J1507','K1201','K1202','K1203','K1204','K1205','K1206','K1207','K1301','K1303','K1304','K1305','K1706','K1801','K2104','K2105','K2107','K2109','K2110','K2111','K2503','K2601','M1202','M1203','M1401','M1603','M1605','M1608','M1801','M1802','M1805','M1810','N1202','N4101','N4104','N4203','N4301');
		$rff_normandie=array('A1204','A1302','A1403','A1404','A1407','A1408','A1409','A1410','A1411','A1415','A1501','B1601','B1604','C1102','C1109','C1201','C1206','C1401','C1501','C1502','C1503','C1504','D1101','D1102','D1103','D1104','D1202','D1203','D1208','D1301','D1402','D1403','D1404','D1405','D1406','D1407','D1501','D1502','D1503','D1504','D1506','D1508','D1509','F1103','F1105','F1106','F1108','F1201','F1202','F1204','F1302','F1502','F1604','F1605','F1607','F1610','F1613','F1701','F1702','F1704','G1202','G1203','G1204','G1401','G1402','G1403','G1404','G1602','G1801','G1803','H1101','H1202','H1203','H1206','H1208','H1210','H1303','H1401','H1402','H1404','H1501','H1502','H1503','H1505','H2101','H2206','H2503','H2504','H2602','H2902','H2904','H2911','H2912','H3202','I1301','I1302','I1304','I1304','I1305','I1306','I1307','I1308','I1309','I1503','I1601','I1602','I1603','I1604','I1605','I1607','J1305','J1501','K1206','K1301','K1302','K1305','K1402','K1801','K2104','K2107','K2109','K2303','K2501','K2503','M1101','M1102','M1201','M1202','M1203','M1204','M1205','M1206','M1207','M1402','M1403','M1701','M1702','M1703','M1704','M1707','M1801','M1805','M1808','M1809','M1810','N1202','N1303','N4102');
		//$rff_bassenormandie=array('M1804','J1501','J1506','N4103','N1303','N1103','K1301','K1302','K1303','K2501','K1207','A1404','A1302','A1407','A1203','A1415','A1414','A1416','G1701','G1801','G1601','G1602','G1501','G1603','G1803','I1302','I1304','I1306','I1307','I1603','I1203','I1309','I1310','I1604','I1606','D1101','D1102','D1103','D1407','D1502','D1503','D1104','D1105','D1406','D1106','F1106','F1302','F1605','F1613','F1702','F1701','F1202','F1602','F1603','F1703','F1604','F1501','F1502','F1606','F1607','F1608','F1609','F1610','F1704','H1202','H1203','H1206','H1208','H1209','H1210','H1404','H1502','H1503','H2101','H2102','H2502','H2602','H2604','H2901','H2902','H2903','H2907','H2908','H2912','H2913','H2914','H3201','H3202','H3203','H3404');
		//$rff_hautenormandie=array('B1601','C1102','C1104','D1102','D1103','D1104','D1106','D1211','D1212','D1301','D1401','D1402','D1406','D1407','D1408','D1502','D1503','D1507','F1104','F1106','F1107','F1201','F1202','F1301','F1302','F1501','F1502','F1602','F1603','F1604','F1606','F1607','F1608','F1609','F1610','F1613','F1701','F1702','F1703','G1204','G1501','G1602','G1603','G1803','H1102','H11202','H11203','H11206','H1301','H1402','H1404','H1502','H1503','H1506','H2102','H2301','H2502','H2503','H2504','H2602','H2701','H2901','H2902','H2903','H2909','H2912','H2913','H2914','H3201','H3203','H3403','H3404','I1102','I1202','I1203','I1302','I1304','I1306','I1307','I1309','I1310','I1401','I1402','I1503','I1603','I1604','I1606','I1607','J1304','J1306','J1404','J1501','J1506','K1104','K1201','K1206','K1207','K1301','K1302','K1801','K2110','K2111','K2202','K2204','K2303','K2503','M1202','M1203','M1204','M1205','M1604','M1607','M1803','M1805','N1103','N1105','N1202','N4101','N4103','N4105');
		$rff_midipyrenees=array('A1101','A1405','A1407','A1409 ','A1410 ','D1101','D1102','D1104','D1106','D1214','D1507','F1201','F1301','F1302','F1502','F1607','F1610','F1703','G1602','G1603','G1803','H1203','H1302 ','H1402 ','H2101','H2102','H2602','H2901','H2902','H2903','H2904','H2905','H2906','H2907','H2908','H2909','H2911','H2913','H3204','H3404','I1302','I1303','I1304','I1305','I1310','I1602','I1603','I1604','J1301','J1501','J1506','K1302','K2204','M1203','M1805','N4101','N4103');
		$rff_languedocroussillon=array('A1101','A1203','A1401','A1404','A1405','A1407','A1413','A1414','A1416','A1416','C1102','C1503','D1101','D1102','D1103','D1104','D1105','D1106','D1205','D1211','D1212','D1402','D1402','D1402','D1402','D1403','D1406','D1408','D1502','D1503','D1506','E1205','E1301','F1104','F1106','F1201','F1202','F1301','F1302','F1501','F1502','F1602','F1603','F1604','F1606','F1607','F1608','F1609','F1610','F1612','F1613','F1701','F1702','F1703','G1202','G1204','G1301','G1303','G1402','G1501','G1601','G1602','G1603','G1605','G1703','G1801','G1802','G1803','H1202','H1202','H1203','H1203','H1302','H1303','H1404','H1503','H1504','H1504','H1506','H2102','H2102','H2206','H2301','H2503','H2602','H2603','H2605','H2901','H2902','H2903','H2903','H2905','H2908','H2909','H2913','H2914','H3201','H3203','H3301','H3302','H3402','I1203','I1302','I1304','I1305','I1305','I1306','I1309','I1310','I1401','I1402','I1503','I1603','I1604','I1605','I1606','I1607','J1302','J1303','J1305','J1306','J1307','J1404','J1405','J1410','J1501','J1502','J1503','J1504','J1506','J1507','K1201','K1203','K1207','K1207','K1301','K1302','K1401','K1802','K2101','K2110','K2202','K2303','K2503','M1203','M1205','M1302','M1402','M1605','M1801','M1802','M1805','N1101','N1103','N1303','N4101','N4103');
		$rff_grandest=array('A1101','A1201','A1403','A1405','A1407','A1416','B1802','C1102','C1103','C1109','C1502','C1504','D1101','D1102','D1103','D1104','D1105','D1106','D1202','D1212','D1214','D1301','D1401','D1402','D1403','D1407','D1408','D1501','D1502','D1503','D1505','D1506','D1507','E1101','E1301','F1103','F1104','F1106','F1107','F1108','F1201','F1202','F1302','F1501','F1502','F1503','F1602','F1603','F1604','F1605','F1606','F1607','F1608','F1609','F1610','F1611','F1612','F1613','F1701','F1702','F1703','F1704','F1705','G1202','G1203','G1204','G1205','G1401','G1502','G1601','G1602','G1603','G1604','G1703','G1803','H1202','H1203','H1206','H1208','H1209','H1210','H1303','H1403','H1404','H1502','H1503','H1506','H2101','H2102','H2201','H2202','H2203','H2206','H2207','H2209','H2301','H2402','H2503','H2504','H2602','H2901','H2902','H2903','H2904','H2905','H2906','H2907','H2912','H2913','H2914','H3102','H3301','H3302','H3401','H3404','I1203','I1301','I1302','I1304','I1305','I1306','I1307','I1308','I1309','I1310','I1401','I1402','I1501','I1503','I1603','I1604','I1606','I1607','J1102','J1301','J1304','J1305','J1403','J1404','J1405','J1406','J1412','J1501','J1504','J1505','J1506','J1507','K1101','K1201','K1202','K1203','K1207','K1301','K1302','K1304','K1305','K1801','K2110','K2202','K2204','K2301','K2304','K2503','K2602','M1202','M1203','M1204','M1401','M1501','M1605','M1707','M1801','M1805','M1806','M1810','N1101','N1102','N1103','N1104','N1105','N3102','N4101','N4102','N4103','N4301');
		$rff_nouvelleaquitaine=array('A1101','A1201','A1202','A1203','A1205','A1301','A1302','A1401','A1402','A1403','A1405','A1407','A1408','A1409','A1410','A1411','A1412','A1413','A1414','A1416','A1501','A1502','B1301','B1601','B1604','B1802','C1102','C1504','D1101','D1102','D1103','D1104','D1105','D1106','D1107','D1203','D1205','D1209','D1211','D1212','D1213','D1214','D1301','D1401','D1402','D1403','D1404','D1405','D1406','D1407','D1408','D1501','D1502','D1503','D1504','D1505','D1506','D1507','D1508','D1509','E1101','E1302','E1304','E1307','F1102','F1104','F1202','F1301','F1302','F1501','F1502','F1503','F1601','F1602','F1603','F1604','F1605','F1606','F1607','F1608','F1609','F1610','F1611','F1612','F1613','F1701','F1702','F1703','F1704','F1705','F1706','G1101','G1203','G1204','G1205','G1401','G1402','G1403','G1404','G1501','G1502','G1503','G1601','G1602','G1603','G1604','G1605','G1701','G1702','G1703','G1801','G1802','G1803','G1804','H1101','H1102','H1202','H1203','H1204','H1206','H1208','H1209','H1210','H1303','H1401','H1402','H1403','H1404','H1501','H1503','H1505','H1506','H2101','H2102','H2201','H2202','H2203','H2204','H2205','H2206','H2209','H2301','H2401','H2501','H2502','H2503','H2504','H2505','H2602','H2603','H2604','H2605','H2802','H2803','H2805','H2901','H2902','H2903','H2904','H2905','H2906','H2907','H2909','H2910','H2911','H2912','H2913','H2914','H3101','H3102','H3201','H3202','H3203','H3301','H3302','H3303','H3401','H3402','H3403','H3404','I1101','I1102','I1103','I1201','I1203','I1301','I1304','I1305','I1306','I1308','I1309','I1310','I1401','I1402','I1501','I1503','I1601','I1603','I1604','I1605','I1606','I1607','J1305','J1306','J1501','J1502','J1503','J1504','J1505','J1506','J1507','K1201','K1202','k1203','K1204','K1207','K1301','K1302','K1304','K2104','K2110','K2111','K2201','K2202','K2203','K2204','K2305','K2502','K2503','K2601','M1101','M1202','M1203','M1204','M1206','M1401','M1404','M1501','M1601','M1602','M1603','M1604','M1605','M1606','M1607','M1608','M1706','M1707','M1801','M1802','M1803','M1805','M1806','M1810','N1101','N1102','N1103','N1104','N1105','N1201','N1202','N1301','N1302','N1303','N4101','N4102','N4103','N4104','N4105','N4201','N4202','N4203','N4204','N4301','N4401','N4402','N4403');
		$rff_hautsdefrance=array('A1101','A1404','A1405','A1407','A1416','C1102','C1504','D1101','D1102','D1104','D1106','D1202','D1208','D1211','D1212','D1214','D1402','D1403','D1404','D1407','D1408','D1501','D1502','D1503','D1505','D1507','F1104','F1106','F1201','F1202','F1302','F1501','F1502','F1602','F1603','F1604','F1606','F1607','F1608','F1609','F1610','F1611','F1701','F1702','F1703','F1704','G1203','G1204','G1205','G1601','G1602','G1603','G1703','G1801','G1803','H1202','H1203','H1206','H1301','H1404','H1503','H1504','H1506','H2101','H2102','H2206','H2301','H2602','H2604','H2701','H2901','H2902','H2903','H2905','H2906','H2907','H2909','H2912','H2913','H2914','H3101','H3201','H3301','H3302','H3401','H3402','I1102','I1203','I1301','I1302','I1304','I1305','I1306','I1307','I1309','I1310','I1401','I1603','I1604','I1606','J1304','J1305','J1404','J1406','J1501','J1502','J1506','J1507','K1201','K1203','K1206','K1207','K1301','K1302','K1303','K1304','K2104','K2110','K2111','K 2204','K2304','K2501','K2503','K2601','M1202','M1203','M1605','M1609','M1801','M1802','M1803','M1805','N1101','N1303','N4101','N4102','N4103');
		$rff_bretagne=array('K2204','K2503','M1608','D1106','D1407','D1408','D1502','D1503','D1507','M1805','A1406','A1407','A1410','A1411','A1414','A1415','H2101','H2102','H3301','H3302','F1501','F1502','F1602','F1603','F1607','F1610','F1613','F1701','F1703','F1705','N1104','N1105','N4101','N4103','N4105','H2902','H2903','H2913','H2914','I1203','I1306','I1602','I1604','I1605','D1101','D1102','D1103','D1105','G1601','G1602','G1603','G1604','G1803','G1501','G1703','G1202','G1203','G1204','J1501','J1506','K1201','K1206','K1207','K1302','K1303');
		$rff_guadeloupe=array('A1203','D1102','D1106','D1212','D1402','D1403','D1501','D1506','F1101','F1104','F1106','F1201','F1202','F1302','F1402','F1501','F1602','F1603','F1604','F1607','F1608','F1610','F1613','F1701','F1703','F1704','F1705','G1101','G1201','G1202','G1203','G1204','G1205','G1602','G1603','G1603','G1603','G1604','G1803','H1209','H1504','H2402','H2403','H2404','H2405','H2406','H2410','H2413','H2502','H2504','H2601','H2602','H2603','H2604','H2605','H2909','I1203','I1401','I1402','J1101','J1102','J1103','J1104','J1306','J1307','J1403','J1406','J1410','J1412','J1501','J1502','J1503','J1504','J1505','J1506','J1507','K1201','K1202','K1203','K1204','K1205','K1206','K1207','K1301','K1302','K1303','K1304','K1305','K1801','K1802','K2204','K2501','K2503','M1202','M1203','M1204','M1501','M1502','M1503','M1604','M1605','M1701','M1704','M1406','M1805','N1101','N1103','N1104','N1105','N1202','N3102','N4101',"N4102");
		$regionDomicilePath=Reference::subPath($domicilePath,3);
		if((isInRegionAuvergneRhoneAlpes($domicilePath) && in_multiarray($training_romesrff,$rff_auvergnerhonealpes)) ||
		   (isInRegionPaysDeLaLoire($domicilePath) && in_multiarray($training_romesrff,$rff_paysdelaloire)) ||
		   (isInRegionCentreValDeLoire($domicilePath) && in_multiarray($training_romesrff,$rff_centrevaldeloire)) ||
		   (isInRegionPACA($domicilePath) && in_multiarray($training_romesrff,$rff_paca)) ||
		   (isInRegionCorse($domicilePath) && in_multiarray($training_romesrff,$rff_corse)) ||
		   (isInRegionBourgogne($domicilePath) && in_multiarray($training_romesrff,$rff_bourgogne)) ||
		   (isInRegionFrancheComte($domicilePath) && in_multiarray($training_romesrff,$rff_franchecomte)) ||
		   (isInRegionIDF($domicilePath) && in_multiarray($training_romesrff,$rff_iledefrance)) ||
		   (isInRegionNormandie($domicilePath) && in_multiarray($training_romesrff,$rff_normandie)) ||
		   //(isInRegionBasseNormandie($domicilePath) && in_multiarray($training_romesrff,$rff_bassenormandie)) ||
		   //(isInRegionHauteNormandie($domicilePath) && in_multiarray($training_romesrff,$rff_hautenormandie)) ||
		   (isInRegionMidiPyrenees($domicilePath) && in_multiarray($training_romesrff,$rff_midipyrenees)) ||
		   (isInRegionLanguedocRoussillon($domicilePath) && in_multiarray($training_romesrff,$rff_languedocroussillon)) ||
		   (isInRegionGrandEst($domicilePath) && in_multiarray($training_romesrff,$rff_grandest)) ||
		   (isInRegionNouvelleAquitaine($domicilePath) && in_multiarray($training_romesrff,$rff_nouvelleaquitaine)) ||
		   (isInRegionHautsDeFrance($domicilePath) && in_multiarray($training_romesrff,$rff_hautsdefrance)) ||
		   (isInRegionBretagne($domicilePath) && in_multiarray($training_romesrff,$rff_bretagne)) ||
		   (isInGuadeloupe($domicilePath) && in_multiarray($training_romesrff,$rff_guadeloupe))
		   )
		{
			return true;
		}
		return false;
	}
	function getRemuLimit($var,$remu,$options=array())//$calcRff=true,$checkTh=true,$prefixe='Vous percevrez')
	{
		$calcRff=array_key_exists('calcrff',$options)?$options['calcrff']:true;
		$checkTh=array_key_exists('checkth',$options)?$options['checkth']:true;
		$prefixe=array_key_exists('prefixe',$options)?$options['prefixe']:'Vous percevrez';
		$prorata=array_key_exists('prorata',$options)?$options['prorata']:1;

		//print_r($var);
		extract($var);
		if($checkTh) /* Pas de test pour cifcdd, cifinterim et contrat de pro */
			if($situation_th)
			{
				$doc='<span onclick="$(this).next(\'span\').fadeToggle();">Vous bénéficiez d\'un droit d\'option pour votre rémunération&nbsp;<span class="mini-info"></span></span>';
				$doc.='<span class="asterisk"><br/><span class="mini-info"></span> Le demandeur d\'emploi reconnu travailleur handicapé et qui bénéficie de l\'Allocation de Retour à l\'Emploi peut, à l\'entrée en formation, opter, si elle s\'avère plus favorable, pour la rémunération publique de stage (RPS). Renseignez-vous auprès d\'un conseiller</span>';
				return $doc;
			}

		$until=$next='';
		if($calcRff && $training_datebegin && $training_dateend)
		{
			/* Prise en compte de la date de fin de rémunération */
			if($allocation_dateend)
				if($allocation_dateend>=$training_datebegin && $allocation_dateend<$training_dateend)
				{
					$until=sprintf("jusqu'au %s<br/>",preg_replace('#^(\d+)-(\d+)-(\d+)$#','$3/$2/$1',$allocation_dateend));

					/* Calcul de la rémunération de fin de formation pour
					   - les Pays de la Loire
					   - Centre Val de Loire
					   - PACA
					   - Corse
					   prend en compte les rome visés par la formation */
					if(condRff($var))
					{
						$next=Tools::sprintf('<br/>Puis jusqu\'au %s<br/>%nf&nbsp;€ net / mois',
						                     preg_replace('#^(\d+)-(\d+)-(\d+)$#','$3/$2/$1',
						                     $training_dateend),
						                     RFF_NET);
					}
				}
		}
		return sprintf('%s %s %s %s',$prefixe,$until,$remu,$next);
	}
	function remuForm($var,$calcRff=true,$percent=100)
	{
		extract($var);
		if(!$dont_check_minaref && $allocation_cost<AREF_MINIMAL_NET) $allocation_cost=AREF_MINIMAL_NET;
		if($allocation_cost) return array('remu'=>getRemuLimit($var,Tools::sprintf('%nf %s',($allocation_cost*$percent/100),' € net '.($allocation_costtype=='jour'?'/ jour':'/ mois')),array('calcrff'=>$calcRff)),'type'=>'aref');
		return array('remu'=>"Non rémunéré",'type'=>'aref');
	}
	function remuSalaireCdd($var,$percent)
	{
		$salaire="";
		extract($var);
		if($situation_salairebrutecdd) $salaire=Tools::sprintf('%nf&nbsp;€ brut&nbsp;/&nbsp;mois<br/>',round($situation_salairebrutecdd*$percent/100,2,PHP_ROUND_HALF_UP));
		$remu=getRemuLimit($var,Tools::sprintf('%s<span class="fa fa-arrow-right"></span>&nbsp;soit %d %% du salaire brut déclaré',
		                                $salaire,$percent),
		                                array('calcrff'=>false,'checkth'=>false));
		return array('remu'=>$remu);
	}
	function remuSalaireInterim($var,$percent)
	{
		$salaire="";
		extract($var);
		if($situation_salairebruteinterim) $salaire=Tools::sprintf('%nf&nbsp;€ brut&nbsp;/&nbsp;mois<br/>',round($situation_salairebruteinterim*$percent/100,2,PHP_ROUND_HALF_UP));
		$remu=getRemuLimit($var,Tools::sprintf('%s<span class="fa fa-arrow-right"></span>&nbsp;soit %d %% du salaire brut déclaré',
		                                $salaire,$percent),
		                                array('calcrff'=>false,'checkth'=>false));
		return array('remu'=>$remu);
	}
	function calc($annee1,$annee2,$annee3)
	{
		$a1=round(SMIC_BRUT*$annee1/100,2,PHP_ROUND_HALF_UP);
		$a2=round(SMIC_BRUT*$annee2/100,2,PHP_ROUND_HALF_UP);
		$a3=round(SMIC_BRUT*$annee3/100,2,PHP_ROUND_HALF_UP);
		$doc='';
		$doc.="<tr><td style='margin:0;padding:0;'>vous percevrez un salaire de $a1&nbsp;€ brut&nbsp;/&nbsp;mois<br/><span class='fa fa-arrow-right'></span>&nbsp;soit $annee1&nbsp;% du Smic la 1<sup>ère</sup> année</td></tr>";
		$doc.="<tr><td style='margin:0;padding:0;'>puis $a2&nbsp;€ brut&nbsp;/&nbsp;mois<br/><span class='fa fa-arrow-right'></span>&nbsp;soit $annee2&nbsp;% du Smic la 2<sup>ème</sup> année</td></tr>";
		$doc.="<tr><td style='margin:0;padding:0;'>puis $a3&nbsp;€ brut&nbsp;/&nbsp;mois<br/><span class='fa fa-arrow-right'></span>&nbsp;soit $annee3&nbsp;% du Smic la 3<sup>ème</sup> année</td></tr>";
		return $doc;
	}
	function remuContratApprentissage($var)
	{
		extract($var);
		$tab=array();
		if($age<18)
		{
			$tab=calc(25,37,53);
		} elseif($age>=18 && $age<21)
		{
			$tab=calc(41,49,65);
		} elseif($age>=21)
		{
			$tab=calc(53,61,78);
		}
		//return getRemuLimit($var,"<table style='padding:0;margin:0;'>$tab</table>",false);
		return array('remu'=>"<table style='padding:0;margin:0;'>$tab</table>");
	}
	function remuNop($var,$text='Pas de rémunération spécifique')
	{
		return array('remu'=>$text,'type'=>'');
	}
	function hasCOPANEF($ad,$ar,$codesCpf=array()) {
		return hasCpf($ad,$ar,'DE|TOUTPUBLIC','/1/1/',$codesCpf);
	}
	function hasCOPAREF($ad,$ar,$domicilePath) {
		$domicilePath=Reference::subPath($domicilePath,4);
		return hasCpf($ad,$ar,'DE|TOUTPUBLIC',$domicilePath);
	}
	function hasCpf($ad,$ar,$type,$domicile,$codesCpf=array())
	{
		/* Requete de mapping département => code insee anciennes régions
		//SELECT CONCAT('''',r1.path,'''','=>','''',FUNC_EXTRADATA('in',r3.extradata,''),''',',' //',r1.label,' (',r3.label,')')
		//FROM reference r1
		//INNER JOIN reference r2 ON r2.type=1 AND r2.labelslug=r1.labelslug 
		//INNER JOIN reference r3 ON r3.type=1 AND r3.path=FUNC_SUBPATH(r2.path,3)
		//WHERE r1.type=6 AND r1.level=4 AND r1.path like '/1/1/%'

		/* 4/5/2016: La base a tjrs les codeinsee au lieu des path, je dois changer ca, mais en attendant on converti le path en code insee */
		//$domicile=strtr(Reference::subPath($location,3),array('/1/1/14/'=>'42','/1/1/19/'=>'72','/1/1/23/'=>'83','/1/1/8/'=>'25','/1/1/9/'=>'26','/1/1/17/'=>'53',LOCATIONPATH_CENTREVALDELOIRE=>'24','/1/1/4/'=>'21',LOCATIONPATH_CORSE=>'94','/1/1/27/'=>'','/1/1/15/'=>'43','/1/1/6/'=>'23','/1/1/2/'=>'11','/1/1/24/'=>'91','/1/1/21/'=>'74','/1/1/13/'=>'41','/1/1/20/'=>'73','/1/1/11/'=>'31',LOCATIONPATH_PAYSDELALOIRE=>'52','/1/1/5/'=>'22','/1/1/18/'=>'54',LOCATIONPATH_PACA=>'93','/1/1/22/'=>'82'));
		//echo "\n$type; $domicile\n";
		
		//$mappingLocation=array('/1/1/7/'=>'84','/1/1/14/'=>'27','/1/1/4/'=>'53','/1/1/12/'=>'24','/1/1/10/'=>'94','/1/1/11/'=>'','/1/1/2/'=>'44','/1/1/1/'=>'32','/1/1/6/'=>'11','/1/1/13/'=>'28','/1/1/5/'=>'75','/1/1/8/'=>'76','/1/1/3/'=>'52','/1/1/9/'=>'93');
		//14/6/2017: l'intercarif fournit les anciens code insee region. Donc mapping du département vers le code insee des anciennes régions
		$mappingLocation=array(
			'/1/1/'=>'00', //France entière
			'/1/1/1/1/'=>'31|22|32', //Nord (Nord Pas de Calais)
			'/1/1/1/2/'=>'31|22|32', //Pas de Calais (Nord Pas de Calais)
			'/1/1/1/3/'=>'31|22|32', //Aisne (Picardie)
			'/1/1/1/4/'=>'31|22|32', //Oise (Picardie)
			'/1/1/1/5/'=>'31|22|32', //Somme (Picardie)
			'/1/1/2/1/'=>'42|41|21|44', //Bas Rhin (Alsace)
			'/1/1/2/2/'=>'42|41|21|44', //Haut Rhin (Alsace)
			'/1/1/2/3/'=>'42|41|21|44', //Meurthe et Moselle (Lorraine)
			'/1/1/2/4/'=>'42|41|21|44', //Meuse (Lorraine)
			'/1/1/2/5/'=>'42|41|21|44', //Moselle (Lorraine)
			'/1/1/2/6/'=>'42|41|21|44', //Vosges (Lorraine)
			'/1/1/2/7/'=>'42|41|21|44', //Ardennes (Champagne Ardenne)
			'/1/1/2/8/'=>'42|41|21|44', //Aube (Champagne Ardenne)
			'/1/1/2/9/'=>'42|41|21|44', //Marne (Champagne Ardenne)
			'/1/1/2/10/'=>'42|41|21|44', //Haute Marne (Champagne Ardenne)
			'/1/1/3/1/'=>'52', //Loire Atlantique (Pays de la Loire)
			'/1/1/3/2/'=>'52', //Maine et Loire (Pays de la Loire)
			'/1/1/3/3/'=>'52', //Mayenne (Pays de la Loire)
			'/1/1/3/4/'=>'52', //Sarthe (Pays de la Loire)
			'/1/1/3/5/'=>'52', //Vendée (Pays de la Loire)
			'/1/1/4/1/'=>'53', //Côtes d'Armor (Bretagne)
			'/1/1/4/2/'=>'53', //Finistère (Bretagne)
			'/1/1/4/3/'=>'53', //Ille et Vilaine (Bretagne)
			'/1/1/4/4/'=>'53', //Morbihan (Bretagne)
			'/1/1/5/1/'=>'54|72|74|75', //Dordogne (Aquitaine)
			'/1/1/5/2/'=>'54|72|74|75', //Gironde (Aquitaine)
			'/1/1/5/3/'=>'54|72|74|75', //Landes (Aquitaine)
			'/1/1/5/4/'=>'54|72|74|75', //Lot et Garonne (Aquitaine)
			'/1/1/5/5/'=>'54|72|74|75', //Pyrénées Atlantiques (Aquitaine)
			'/1/1/5/6/'=>'54|72|74|75', //Charente (Poitou Charentes)
			'/1/1/5/7/'=>'54|72|74|75', //Charente Maritime (Poitou Charentes)
			'/1/1/5/8/'=>'54|72|74|75', //Deux Sèvres (Poitou Charentes)
			'/1/1/5/9/'=>'54|72|74|75', //Vienne (Poitou Charentes)
			'/1/1/5/10/'=>'54|72|74|75', //Corrèze (Limousin)
			'/1/1/5/11/'=>'54|72|74|75', //Creuse (Limousin)
			'/1/1/5/12/'=>'54|72|74|75', //Haute Vienne (Limousin)
			'/1/1/6/1/'=>'11', //Paris (Île de France)
			'/1/1/6/2/'=>'11', //Seine et Marne (Île de France)
			'/1/1/6/3/'=>'11', //Yvelines (Île de France)
			'/1/1/6/4/'=>'11', //Essonne (Île de France)
			'/1/1/6/5/'=>'11', //Hauts de Seine (Île de France)
			'/1/1/6/6/'=>'11', //Seine St Denis (Île de France)
			'/1/1/6/7/'=>'11', //Val de Marne (Île de France)
			'/1/1/6/8/'=>'11', //Val d'Oise (Île de France)
			'/1/1/7/1/'=>'83|82|84', //Allier (Auvergne)
			'/1/1/7/2/'=>'83|82|84', //Cantal (Auvergne)
			'/1/1/7/3/'=>'83|82|84', //Haute Loire (Auvergne)
			'/1/1/7/4/'=>'83|82|84', //Puy de Dôme (Auvergne)
			'/1/1/7/5/'=>'83|82|84', //Ain (Rhône Alpes)
			'/1/1/7/6/'=>'83|82|84', //Ardèche (Rhône Alpes)
			'/1/1/7/7/'=>'83|82|84', //Drôme (Rhône Alpes)
			'/1/1/7/8/'=>'83|82|84', //Isère (Rhône Alpes)
			'/1/1/7/9/'=>'83|82|84', //Loire (Rhône Alpes)
			'/1/1/7/10/'=>'83|82|84', //Rhône (Rhône Alpes)
			'/1/1/7/11/'=>'83|82|84', //Savoie (Rhône Alpes)
			'/1/1/7/12/'=>'83|82|84', //Haute Savoie (Rhône Alpes)
			'/1/1/8/1/'=>'91|73|76', //Aude (Languedoc Roussillon)
			'/1/1/8/2/'=>'91|73|76', //Gard (Languedoc Roussillon)
			'/1/1/8/3/'=>'91|73|76', //Hérault (Languedoc Roussillon)
			'/1/1/8/4/'=>'91|73|76', //Lozère (Languedoc Roussillon)
			'/1/1/8/5/'=>'91|73|76', //Pyrénées Orientales (Languedoc Roussillon)
			'/1/1/8/6/'=>'91|73|76', //Ariège (Midi Pyrénées)
			'/1/1/8/7/'=>'91|73|76', //Aveyron (Midi Pyrénées)
			'/1/1/8/8/'=>'91|73|76', //Haute Garonne (Midi Pyrénées)
			'/1/1/8/9/'=>'91|73|76', //Gers (Midi Pyrénées)
			'/1/1/8/10/'=>'91|73|76', //Lot (Midi Pyrénées)
			'/1/1/8/11/'=>'91|73|76', //Hautes Pyrénées (Midi Pyrénées)
			'/1/1/8/12/'=>'91|73|76', //Tarn (Midi Pyrénées)
			'/1/1/8/13/'=>'91|73|76', //Tarn et Garonne (Midi Pyrénées)
			'/1/1/9/1/'=>'93', //Alpes de Haute Provence (Provence Alpes Côte d'Azur)
			'/1/1/9/2/'=>'93', //Hautes Alpes (Provence Alpes Côte d'Azur)
			'/1/1/9/3/'=>'93', //Alpes Maritimes (Provence Alpes Côte d'Azur)
			'/1/1/9/4/'=>'93', //Bouches du Rhône (Provence Alpes Côte d'Azur)
			'/1/1/9/5/'=>'93', //Var (Provence Alpes Côte d'Azur)
			'/1/1/9/6/'=>'93', //Vaucluse (Provence Alpes Côte d'Azur)
			'/1/1/10/1/'=>'94', //Corse du Sud (Corse)
			'/1/1/10/2/'=>'94', //Haute Corse (Corse)
			'/1/1/11/1/'=>'01', //Guadeloupe (Dom Tom)
			'/1/1/11/2/'=>'', //St Pierre et Miquelon (Dom Tom)
			'/1/1/11/3/'=>'', //Wallis et Futuna (Dom Tom)
			'/1/1/11/4/'=>'03', //Guyane (Dom Tom)
			'/1/1/11/5/'=>'04', //La Réunion (Dom Tom)
			'/1/1/11/6/'=>'02', //Martinique (Dom Tom)
			'/1/1/11/7/'=>'06', //Mayotte (Dom Tom)
			'/1/1/11/8/'=>'', //Nouvelle Calédonie (Dom Tom)
			'/1/1/11/9/'=>'', //Polynésie Française (Dom Tom)
			'/1/1/11/10/'=>'01', //St Barthélemy (Dom Tom)
			'/1/1/11/11/'=>'01', //St Martin (Dom Tom)
			'/1/1/12/1/'=>'24', //Cher (Centre Val de Loire)
			'/1/1/12/2/'=>'24', //Eure et Loir (Centre Val de Loire)
			'/1/1/12/3/'=>'24', //Indre (Centre Val de Loire)
			'/1/1/12/4/'=>'24', //Indre et Loire (Centre Val de Loire)
			'/1/1/12/5/'=>'24', //Loir et Cher (Centre Val de Loire)
			'/1/1/12/6/'=>'24', //Loiret (Centre Val de Loire)
			'/1/1/13/1/'=>'25|23|28', //Calvados (Basse Normandie)
			'/1/1/13/2/'=>'25|23|28', //Manche (Basse Normandie)
			'/1/1/13/3/'=>'25|23|28', //Orne (Basse Normandie)
			'/1/1/13/4/'=>'25|23|28', //Eure (Haute Normandie)
			'/1/1/13/5/'=>'25|23|28', //Seine Maritime (Haute Normandie)
			'/1/1/14/1/'=>'26|43|27', //Côte d'Or (Bourgogne)
			'/1/1/14/2/'=>'26|43|27', //Nièvre (Bourgogne)
			'/1/1/14/3/'=>'26|43|27', //Saône et Loire (Bourgogne)
			'/1/1/14/4/'=>'26|43|27', //Yonne (Bourgogne)
			'/1/1/14/5/'=>'26|43|27', //Doubs (Franche Comté)
			'/1/1/14/6/'=>'26|43|27', //Jura (Franche Comté)
			'/1/1/14/7/'=>'26|43|27', //Haute Saône (Franche Comté)
			'/1/1/14/8/'=>'26|43|27' //Territoire de Belfort (Franche Comté)
		);
		//$mappingLocation=array('/1/1/7/'=>'84','/1/1/14/'=>'27','/1/1/4/'=>'53','/1/1/12/'=>'24','/1/1/10/'=>'94','/1/1/11/'=>'','/1/1/2/'=>'44','/1/1/1/'=>'32','/1/1/6/'=>'11','/1/1/13/'=>'28','/1/1/5/'=>'75','/1/1/8/'=>'76','/1/1/3/'=>'52','/1/1/9/'=>'93');
		$locationList=array();
		foreach(explode('|',$domicile) as $path)
		{
			$path=Reference::subPath($path,4);
			if(array_key_exists($path,$mappingLocation))
				$locationList=array_merge($locationList,explode('|',$mappingLocation[$path]));
		}
		$type=explode('|',$type);

		if($ar)
			foreach($ar['sessions[0]/eligibilite-cpf,array()'] as $cpf)
				if(in_array($cpf['type'],$type) && (!$cpf['region-insee'] || in_array($cpf['region-insee'],$locationList)))
				{
					if(!empty($codesCpf))
					{
						if(in_array($cpf['code'],$codesCpf))
							return $cpf;
						return false;
					}
					return $cpf;
				}
		if(0)
			if(is_array($ad) && !empty($ad))
				foreach($ad['cpf'] as $cpf)
					if(in_array($cpf['type'],$type) && in_array($cpf['locationpath'],$locationList))
					{
						if(!empty($codesCpf))
						{
							if(in_array($cpf['codecpf'],$codesCpf))
								return $cpf;
							return false;
						}
						print_r($cpf);
						return $cpf;
					}
		return false;
	}
	function hasCodeFinanceur($training_codefinanceur,$codeFinanceur,&$nbPlaces)
	{
		$nbPlaces=false;
		if(!is_array($training_codefinanceur)) $training_codefinanceur=explode(' ',$training_codefinanceur);
		foreach($training_codefinanceur as $cf)
			if(preg_match("#^(\d+)\[(\d*?)\]$#",$cf,$m))
			{
				$code=$m[1];
				if($code==$codeFinanceur)
				{
					if(isset($m[2]) && !empty($m[2])) $nbPlaces=$m[2];
					return true;
				}
			}
		return false;
	}
	/* Fonction de rémunération - calcul de la rémunération */
	function remuRfpe($var,$numType,$calcRff=true,$percent=100)
	{
		//echo "ok".Locale::getDefault();
		$rfpe=array();
		$rfpe['rfpe1']=Tools::sprintf('%nf € brut / mois',652.02*$percent/100);
		$rfpe['rfpe2']=Tools::sprintf('%d%% du salaire antérieur (Plancher : %nf € brut / mois, Plafond : %nf € brut / mois)',$percent,644.17*$percent/100,1932.52*$percent/100);
		$rfpe['rfpe3']=Tools::sprintf('%nf € brut / mois',708.59*$percent/100);
		$rfpe['rfpe4']=Tools::sprintf('%nf € brut / mois',130.34*$percent/100);
		$rfpe['rfpe5']=Tools::sprintf('%nf € brut / mois',310.39*$percent/100);
		$rfpe['rfpe6']=Tools::sprintf('%nf € brut / mois',339.35*$percent/100);
		$rfpe['rfpe7']=Tools::sprintf('%nf € brut / mois',401.09*$percent/100);
		return array('remu'=>getRemuLimit($var,$rfpe[$numType],array('calcrff'=>$calcRff)),'type'=>'rfpe');
	}
	/* Pas de calcul rff pour RPS */
	function remuRPS($var,$financement,&$droits)
	{
		extract($var);
		$remu=array();

		if(in_array($allocation_type,array('ass','ata','rsa','non')))
		{
			if($situation_inscrit && $situation_6moissur12) $remu=remuRfpe($var,'rfpe1',false);
			if($situation_th && $situation_6moissur12) $remu=remuRfpe($var,'rfpe2',false);
			if($situation_th && !$situation_travailleurnonsal12dont6dans3ans) $remu=remuRfpe($var,'rfpe1',false);
			if($situation_inscrit && $situation_travailleurnonsal12dont6dans3ans) $remu=remuRfpe($var,'rfpe3',false); //Règles N+4
			if($situation_parentisole) $remu=remuRfpe($var,'rfpe1',false); //Règles N+5
			if($situation_mere3enfants) $remu=remuRfpe($var,'rfpe1',false); //Règles N+6
			if($situation_divorceeveuve) $remu=remuRfpe($var,'rfpe1',false); //Règles N+7
			if(!$caseVousEtes)
			{
				if($age<18) $remu=remuRfpe($var,'rfpe4',false); //Règles N+8
				if($age>=18 && $age<21) $remu=remuRfpe($var,'rfpe5',false); //Règles N+9
				if($age>=21 && $age<26) $remu=remuRfpe($var,'rfpe6',false); //Règles N+10
				if($age>=26) $remu=remuRfpe($var,'rfpe7',false); //Règles N+11
			}
		} elseif(in_array($allocation_type,array('asr','atp','asp')))
		{
			 $remu=remuForm($var,false);
		}
		if($remu)
			$droits[$financement]=$remu;
		else
			remuTEXT($var,$financement,$droits);
		return $remu;
	}
	function remuTEXT($var,$financement,&$droits,$text='Pas de rémunération spécifique')
	{
		extract($var);
		$remu=array('remu'=>$text,'type'=>'text');
		if($remu) $droits[$financement]=$remu;
		return $remu;
	}
	function remuFORM2($var,$financement,&$droits)
	{
		extract($var);
		$remu=remuForm($var);
		if($remu) $droits[$financement]=$remu;
		return $remu;
	}
	function remuFORMCDD($var,$financement,&$droits)
	{
		extract($var);
		$percent=100;
		if($situation_salairebrutecdd>(SMIC_BRUT*2)) $percent=90;
		$remu=remuSalaireCdd($var,$percent);
		if($remu) $droits[$financement]=$remu;
		return $remu;
	}
	function remuFORMINTERIM($var,$financement,&$droits)
	{
		extract($var);
		$percent=100;
		if($situation_salairebrutecdd>(SMIC_BRUT*2)) $percent=90;
		$remu=remuSalaireInterim($var,$percent);
		if($remu) $droits[$financement]=$remu;
		return $remu;
	}
	function remuAREF($var,$financement,&$droits)
	{
		extract($var);
		$remu=array();

		if($allocation_type=='are')
		{
			$remu=remuForm($var);
		}
		if($remu) $droits[$financement]=$remu;
		return $remu;
	}
	/* Pas de calcul rff pour RFPE */
	function remuRFPE2($var,$financement,&$droits)
	{
		extract($var);
		$remu=array();

		if(in_array($allocation_type,array('ass','ata','rsa','non')))
		{
			if($situation_inscrit && $situation_6moissur12) $remu=remuRfpe($var,'rfpe1',false);
			if($situation_th && $situation_6moissur12) $remu=remuRfpe($var,'rfpe2',false);
			if($situation_th && !$situation_travailleurnonsal12dont6dans3ans) $remu=remuRfpe($var,'rfpe1',false);
			if($situation_inscrit && $situation_travailleurnonsal12dont6dans3ans) $remu=remuRfpe($var,'rfpe3',false); //Règles N+4
			if($situation_parentisole) $remu=remuRfpe($var,'rfpe1',false); //Règles N+5
			if($situation_mere3enfants) $remu=remuRfpe($var,'rfpe1',false); //Règles N+6
			if($situation_divorceeveuve) $remu=remuRfpe($var,'rfpe1',false); //Règles N+7
			if(!$caseVousEtes)
			{
				if($age<18) $remu=remuRfpe($var,'rfpe4',false); //Règles N+8
				if($age>=18 && $age<21) $remu=remuRfpe($var,'rfpe5',false); //Règles N+9
				if($age>=21 && $age<26) $remu=remuRfpe($var,'rfpe6',false); //Règles N+10
				if($age>=26) $remu=remuRfpe($var,'rfpe7',false); //Règles N+11
			}
		} elseif(in_array($allocation_type,array('asr','atp','asp')))
		{
			 $remu=remuForm($var,false);
		}
		if($remu)
			$droits[$financement]=$remu;
		else
			remuTEXT($var,$financement,$droits);

		return $remu;
	}
	function remuContratApprentissage2($var,$financement,&$droits)
	{
		extract($var);
		$tab=array();
		if($age<18)
		{
			$tab=calc(25,37,53);
		} elseif($age>=18 && $age<21)
		{
			$tab=calc(41,49,65);
		} elseif($age>=21)
		{
			$tab=calc(53,61,78);
		}
		$remu=array('remu'=>"<table style='padding:0;margin:0;'>$tab</table>");
		//return getRemuLimit($var,"<table style='padding:0;margin:0;'>$tab</table>",false);
		$droits[$financement]=$remu;
		return $remu;
	}
	function remuContratDePro2($var,$financement,&$droits)
	{
		extract($var);
		$percent=100;
		if($age>=15 && $age<21 && $niveauscolaire && $niveauscolaire<CODENIVEAUSCOLAIRE_BAC) $percent=55;
		if($age>=15 && $age<21 && $niveauscolaire && $niveauscolaire>=CODENIVEAUSCOLAIRE_BAC) $percent=65;
		if($age>=21 && $age<26 && $niveauscolaire && $niveauscolaire<CODENIVEAUSCOLAIRE_BAC) $percent=70;
		if($age>=21 && $age<26 && $niveauscolaire && $niveauscolaire>=CODENIVEAUSCOLAIRE_BAC) $percent=80;

		$remu=getRemuLimit($var,Tools::sprintf('%nf&nbsp;€ brut&nbsp;/&nbsp;mois<br/><span class="fa fa-arrow-right"></span>&nbsp;soit %d %% du Smic',
		                                 round(SMIC_BRUT*$percent/100,2,PHP_ROUND_HALF_UP),$percent),
		                                 array('calcrff'=>false,'checkth'=>false,'prefixe'=>'Vous percevrez un salaire de'));
		$droits[$financement]=array('remu'=>$remu);
		return array('remu'=>$remu);
	}
?>
