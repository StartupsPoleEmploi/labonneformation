<?php
	class Reference
	{
		protected $db;

		public function __construct($db)
		{
			$this->db=$db;
		}
		public function __destruct()
		{
		}
		public function get($type,$path,$maxLevel=10,$minLevel=0)
		{
			$type=$this->_getLabelType($type);
			$db=$this->db;
			$db->prepare("SELECT r.path,r.label,r.labelslug,r.level,r.extradata,FUNC_EXTRADATA('lt',r.extradata,NULL) as lat,FUNC_EXTRADATA('lg',r.extradata,NULL) as lng,FUNC_EXTRADATA('zc',r.extradata,FUNC_EXTRADATA('dn',r.extradata,'')) as zipcode ".
			             "FROM reference r ".
			             "WHERE r.type=%rd AND r.path LIKE %rs AND r.level<=%rd AND r.level>=%rd ".
			             "ORDER BY r.priority",
			             $type,strtr($path,array('*'=>'%')),$maxLevel,$minLevel);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['path']]=$row;
			return $ret;
		}
		public function getByLabel($type,$label,$maxLevel=10,$minLevel=0,$options=array())
		{
			$type=$this->_getLabelType($type);
			$db=$this->db;
			$cleanLabel='r.label';
			if(array_key_exists('cleanlabel',$options))
			{
				$label=str_replace('-',' ',$label);
				$cleanLabel="REPLACE(r.label,'-',' ')";
			}
			$db->prepare("SELECT r.path,r.label,r.level,r.extradata,FUNC_EXTRADATA('lt',r.extradata,NULL) as lat,FUNC_EXTRADATA('lg',r.extradata,NULL) as lng,FUNC_EXTRADATA('zc',r.extradata,FUNC_EXTRADATA('dn',r.extradata,'')) as zipcode ".
			             "FROM reference r ".
			             "WHERE r.type=%rd AND $cleanLabel LIKE %rs AND r.level<=%rd AND r.level>=%rd ".
			             "ORDER BY r.priority",
			             $type,strtr($label,array('*'=>'%')),$maxLevel,$minLevel);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['path']]=$row;
			return $ret;
		}
		public function getByLabelFullText($type,$label,$opt=array())
		{
			$type=$this->_getLabelType($type);
			$db=$this->db;
			$request=array();
			$minLevel=array_key_exists('minlevel',$opt)?$opt['minlevel']:0;
			$maxLevel=array_key_exists('maxlevel',$opt)?$opt['maxlevel']:10;
			$limit=array_key_exists('limit',$opt)?sprintf('LIMIT %d',$opt['limit']):'';
			switch(array_key_exists('method',$opt)?$opt['method']:'')
			{
				case 'COMPLETION':
					$order='r.level,LENGTH(r.label)';
					break;
				default:
					$order='r.priority';
			}
			if($label) $request=array_map(function($word) use ($db) {return $db->request('r.label LIKE %rs',"%$word%");},preg_split('/ +/',strtr(trim($label),array('*'=>'%'))));
			$db->prepare("SELECT r.path,r.label,r.extradata,r.level ".
			             "FROM reference r ".
			             "WHERE r.type=%rd AND %s AND r.level<=%rd AND r.level>=%rd ".
			             "ORDER BY $order ".
			             "$limit",
			             $type,implode($request,' AND '),$maxLevel,$minLevel);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['path']]=$row;
			return $ret;
		}
		/**
		 * Select entries by extradata, ex: 'rm', 'zipcode', ...
		 * If $data is null, the db field is considered as RAW and $extraTag becomes the data to find
		 * $data can be an array of values to find
		 * @param string $type the type as defined id _getLabelType() method
		 * @param string $extraTag the left extraTag saved in the table, ex: [rm:D1401] -> $extraTag is 'rm'
		 * @param mixed $data the string or an array of strings that must matches in the table (right part), ex: [rm:D1401] -> 'D1401' is the right part to find. Wildcards are authorised
		 * @return array the list of table items that matches
		 */
		public function getByExtraData($type,$extraTag,$data=null)
		{
			$type=$this->_getLabelType($type);
			$db=$this->db;
			if(is_null($data))
			{
				$data=$extraTag;
				$extraTag=null;
			}
			if(!is_array($data)) $data=array($data);
			$extraCond=is_null($extraTag)?'r.extradata':$db->request("FUNC_EXTRADATA(%rs,r.extradata,'')",$extraTag);
			$cond=array();

			$prepared=false;
			/* Optimisation sphinx si recherche de codeinsee ou rome */
			if(!is_null($extraTag))
			{
				$mappingTag=array('dn'=>'@departementinsee','in'=>'@codeinsee','rm'=>'@rome','fm'=>'@formacode');
				if(array_key_exists($extraTag,$mappingTag))
				{
					foreach($data as $d) $cond[]=Tools::sphinxEscape($d);
					$query=array();
					$query[]=sprintf('%s %s',$mappingTag[$extraTag],implode(' | ',$cond));
					$query[]=sprintf('mode=extended2;maxmatches=200;filter=type,%d',$type);
					$db->prepare("SELECT r.id,r.path,r.label,r.labelslug,r.extradata,FUNC_EXTRADATA(%rs,r.extradata,'') AS data,r.level 
					              FROM sphreference s
					              INNER JOIN reference r ON r.id=s.id AND r.type=%rd
					              WHERE s.query=%rs 
					              ORDER BY r.priority",
					             $extraTag?$extraTag:'',$type,implode(';',$query));
					$prepared=true;
				}
			}
			if(!$prepared)
			{
				foreach($data as $d) $cond[]=$db->request('%s LIKE %rs',$extraCond,strtr($d,array('*'=>'%')));
				$db->prepare("SELECT r.id,r.path,r.label,r.labelslug,r.extradata,FUNC_EXTRADATA(%rs,r.extradata,'') AS data,r.level 
				              FROM reference r 
				              WHERE r.type=%rd AND (%s) 
				              ORDER BY r.priority",
				             $extraTag?$extraTag:'',$type,implode(' OR ',$cond));
			}
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['path']]=$row;
			return $ret;
		}
		public function getBySlug($type,$slug,$opt=array())
		{
			$type=$this->_getLabelType($type);
			$db=$this->db;
			$minLevel=array_key_exists('minlevel',$opt)?$opt['minlevel']:0;
			$maxLevel=array_key_exists('maxlevel',$opt)?$opt['maxlevel']:10;
			$db->prepare("SELECT r.id,r.level,r.path,r.label,r.labelslug,r.extradata,FUNC_EXTRADATA('lt',r.extradata,NULL) as lat,FUNC_EXTRADATA('lg',r.extradata,NULL) as lng,FUNC_EXTRADATA('zc',r.extradata,FUNC_EXTRADATA('dn',r.extradata,'')) as zipcode 
			              FROM reference r
			              WHERE r.type=%rd AND r.status=1 AND r.labelslug=%rs AND r.level<=%rd AND r.level>=%rd 
			              ORDER BY r.priority",
			              $type,strtr($slug,array('*'=>'%')),$maxLevel,$minLevel);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['path']]=$row;
			return $ret;
		}
		public function getRomeAppellationCompletion($keywords,$options=array())
		{
			$db=$this->db;
			$limit=array_key_exists('limit',$options)?$options['limit']:10;

			$t=microtime(true);
			$sphinx=array();
			if($keywords)
			{
				$keywords=$this->cleanSearchWords($keywords);
				$keywords=implode(' ',array_map(function($word) {return "$word*";},preg_split('/ +/',$keywords)));
				$sphinx[]='@label '.Tools::sphinxEscape($keywords);
			}
			$sphinx[]='mode=extended2;maxmatches=15000;limit=15000;sort=extended:reference_id asc';
			$db->prepare('SELECT SQL_NO_CACHE r2.id,r2.label AS romelabel,r2.path AS romepath,r.label AS appellationlabel,r.path AS appellationpath
			              FROM sphreferenceformacode s
			              INNER JOIN reference r ON r.status=1 AND r.type=5 AND r.id=s.reference_id AND r.level>=3
			              INNER JOIN reference r2 ON r2.status=1 AND r2.type=5 AND r2.path=FUNC_SUBPATH(r.path,3) AND r2.level=3
			              WHERE s.query=%rs
			              GROUP BY romepath
			              ORDER BY LENGTH(CONCAT(romelabel))
			              LIMIT %rd',
			              implode(';',$sphinx),$limit);

			//$db->prepare('SELECT SQL_NO_CACHE r.id,r.path,r.extradata,r.label,r2.label AS parentlabel,_sph_count AS cnt 
			//              FROM sphreferenceformacode sfc 
			//              INNER JOIN reference r ON r.type=5 AND r.status=1 AND r.id=sfc.reference_id AND level=3
			//              INNER JOIN reference r2 ON r2.type=5 AND r2.status=1 AND r2.path=FUNC_SUBPATH(r.path,2)
			//              WHERE sfc.query=%rs
			//              GROUP BY r.label
			//              ORDER BY r.level,LENGTH(r.label) 
			//              LIMIT %rd',
			//             implode(';',$sphinx),$limit);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			//_QUARKDEBUG($db->getPrepare()."\n");
			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['romepath']]=$row;
			$t=microtime(true)-$t;
			//_QUARKDEBUG(sprintf("### %.4f\n%s\n\n",$t,$db->getPrepare()));
			return $ret;
		}
		public function getFormaCodeCompletion($keywords,$options=array())
		{
			$db=$this->db;
			$limit=array_key_exists('limit',$options)?$options['limit']:10;

			$sphinx=array();
			if($keywords)
			{
				$keywords=$this->cleanSearchWords($keywords);
				$keywords=implode(' ',array_map(function($word) {return "$word*";},preg_split('/ +/',$keywords)));
				$sphinx[]='@label '.Tools::sphinxEscape($keywords);
			}
			$sphinx[]='mode=extended2;maxmatches=3000;groupby=attr:reference_id';
			$db->prepare('SELECT r.id,r.path,r.extradata,r.label,_sph_count AS cnt 
			              FROM sphreferenceformacode sfc 
			              INNER JOIN reference r ON r.type=4 AND r.status=1 AND r.id=sfc.reference_id 
			              WHERE sfc.query=%rs
			              GROUP BY r.label
			              ORDER BY r.level,LENGTH(r.label) 
			              LIMIT %rd',
			             implode(';',$sphinx),$limit);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			//_QUARKDEBUG($db->getPrepare()."\n");

			$ret=array();
			if($db->query())
				while(($row=$db->next())!==false)
					$ret[$row['path']]=$row;
			return $ret;
		}
		static function subPath($path,$level,$strict=false)
		{
			if($level<0) $level=self::getLevelFromPath($path)+$level;
			if(preg_match('#(?:/\d+){'.intval($level).'}#iS',$path,$match)) return $match[0].'/';
			if(!$strict) return $path;
			return false;
		}
		static function getLevelFromPath($path)
		{
			if(preg_match_all('#/([^/]+?)#',$path,$match)) return count($match[1]);
			return 0;
		}
		/* Ex: Reference::extradata('rm',$string); */
		static function extraData($tag,$data,$default='')
		{
			if(preg_match(sprintf('#\[%s:(.*?)\]#',preg_quote($tag,'#')),$data,$m))
				return $m[1];
			return $default;
		}
		static function getExtraData($tag,$data,$default='')
		{
			return self::extraData($tag,$data,$default);
		}
		static function extraDataAll($tag,$data,$default=array())
		{
			if(preg_match_all(sprintf('#\[%s:(.*?)\]#',preg_quote($tag,'#')),$data,$m))
				return $m[1];
			return $default;
		}
		static function addExtraData($tag,$data,$value)
		{
			if(self::existsExtraData($tag,$data))
			{
				$replace=preg_replace_callback(sprintf('#(\[%s:)(.*?)(\])#',preg_quote($tag,'#')),function($m) use($value)
					{
						return $m[1].$value.$m[3];
					},$data);
			} else
			{
				$replace=sprintf('[%s:%s]%s',$tag,$value,$data);
			}

			return $replace;
		}
		static function existsExtraData($tag,$data)
		{
			if(preg_match_all(sprintf('#\[%s:(.*?)\]#',preg_quote($tag,'#')),$data,$m))
				return true;
			return false;
		}
		private function _getLabelType($type)
		{
			switch($type)
			{
				case 'LOCATION':
					return 6;
				case 'ZIPCODE':
					return 10;
				case 'ROMEAPPELLATION':
					return 5;
				case 'FORMACODE':
					return 3;
				case 'FORMACODEMULTI':
					return 4;
				case 'ROMECODE':
				case 'ROME':
					return 2;
			}
			return LOCATION_TYPE;
		}
		private function cleanSearchWords($str)
		{
			$str=preg_replace('#[^a-zA-Z0-9eéèêëaàäâoôöòuùüûiîï ]#sui',' ',$str);
			return trim($str);
		}
	}
?>
