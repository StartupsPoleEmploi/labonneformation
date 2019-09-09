<?php
	class Orga
	{
		protected $db;

		const PAGE_SIZE = 30;

		public function __construct($db)
		{
			$this->db=$db;
		}

		public function getById($id)
		{
			$db=$this->db;

			$db->prepare("
				SELECT o.id,o.name,o.idorgaintercarif,o.content,
				       a.content AS ad_content
				FROM orga o
				LEFT OUTER JOIN ad a ON a.orga_id=o.id
				WHERE o.status='ACTIVE' AND o.id=%rd
				LIMIT 1
				",$id);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			if($row=$db->queryFetchFirst())
			{
				$orgaContent=new ContentParser($row['content']);
				$content=$row['ad_content']?new ContentParser($row['ad_content']):new ContentParser();
				$res=array(
					'idorgaintercarif'=>$row['idorgaintercarif'],
					'nom'=>$row['name'],
					'siret'=>(string)$orgaContent->get('siret'),
					'lat'=>(string)$orgaContent->get('lat'),
					'lng'=>(string)$orgaContent->get('lng'),
					'contact'=>array(
						'telephone'=>(string)$orgaContent->get('tel',(string)$content->get('tel')),
						'fax'=>(string)$orgaContent->get('fax',(string)$content->get('fax')),
						'mobile'=>(string)$orgaContent->get('mobile',$content->get('mobile')),
						'adresse'=>(string)$orgaContent->get('line').(string)$orgaContent->get('city'),
						'email'=>(string)$orgaContent->get('email',(string)$content->get('email')),
						'url'=>(string)$orgaContent->get('url',(string)$content->get('url'))
					)
				);
				return $res;
			}
			return array();
		}

		protected function prepareRequest($mode,$name,$options)
		{
			$db=$this->db;

			$fields='COUNT(DISTINCT o.id) AS cnt';
			$limit=$order='';

			if(array_key_exists('regexp',$options) && $options['regexp'])
				$name=$db->request('REGEXP %rs',$name);
			else
				$name=$db->request('LIKE %rs',strtr($name,array('*'=>'%')));

			if($mode=='LIST')
			{
				$fields='o.id,o.name,COUNT(DISTINCT s.ad_id,s.locationpath) AS cnt';

				$offset=array_key_exists('offset',$options)?(int)$options['offset']:null;
				$limit=array_key_exists('limit',$options)?(int)$options['limit']:null;
				if(!is_null($offset) && !is_null($limit))
					$limit=$db->request('LIMIT %rd,%rd',$offset,$limit);
				elseif(!is_null($limit))
					$limit=$db->request('LIMIT %rd',$limit);
				$order="GROUP BY o.id 
			            ORDER BY o.name ASC";
			}

			$db->prepare("SELECT %s
			              FROM orga o
			              INNER JOIN ad a ON a.orga_id=o.id AND a.status='ACTIVE' AND (a.endedat IS NULL OR a.endedat>=NOW())
			              INNER JOIN session s ON s.ad_id=a.id AND a.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
			              WHERE o.status='ACTIVE' AND o.name %s
			              %s
			              %s",$fields,$name,$order,$limit);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			return $db;
		}

		public function getList($name,$options=array())
		{
			$db=$this->prepareRequest('LIST',$name,$options);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			if($res=$db->queryFetchAll())
				return $res;
			return array();
		}
		public function getCount($name,$options=array())
		{
			$db=$this->prepareRequest('COUNT',$name,$options);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			if($res=$db->queryFetchFirst())
				return $res['cnt'];
			return 0;
		}

		public function getListOld($firstLetter, $page)
		{
			$db=$this->db;
			
			if(!is_null($firstLetter))
			{
				$criteria=$db->request("LIKE %rs","$firstLetter%");
			} else
			{
				$criteria="NOT REGEXP '^[a-zÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ]'";
			}

			$db->prepare("SELECT o.id,o.name,COUNT(DISTINCT s.ad_id,s.locationpath) AS cnt
			              FROM orga o
			              INNER JOIN ad a ON a.orga_id=o.id AND a.status='ACTIVE' AND (a.endedat IS NULL OR a.endedat>=NOW())
			              INNER JOIN session s ON s.ad_id=a.id AND a.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
			              WHERE o.status='ACTIVE'  AND o.name %s
			              GROUP BY o.name
			              ORDER BY o.name ASC
			              LIMIT %rd, %rd", $criteria, self::PAGE_SIZE*$page, self::PAGE_SIZE);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			if($res=$db->queryFetchAll())
				return $res;
			return array();
		}

		public function getPageCountOld($firstLetter)
		{
			$db=$this->db;
			
			if(!is_null($firstLetter))
			{
				$criteria=$db->request("LIKE %rs","$firstLetter%");
			} else
			{
				$criteria="NOT REGEXP '^[a-zÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ]'";
			}

			$db->prepare("SELECT COUNT(DISTINCT o.id) AS cnt
			              FROM orga o
			              INNER JOIN ad a USE INDEX(annuaire) ON a.orga_id=o.id AND a.status='ACTIVE' AND (a.endedat IS NULL OR a.endedat>=NOW())
			              INNER JOIN session s ON s.ad_id=a.id AND a.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
			              WHERE o.status='ACTIVE' AND name %s",
			              $criteria);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			if($res=$db->queryFetchFirst())
				return ceil($res['cnt']/self::PAGE_SIZE); 
			return 0;
		}
	}
?>