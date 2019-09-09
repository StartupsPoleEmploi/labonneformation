<?php
	class Anotea
	{
		protected $db;

		public function __construct($db)
		{
			$this->db=$db;
		}

		protected function prepareRequest($options)
		{
			$db=$this->db;

			$request=array(1);
			if(array_key_exists('ad_id',$options)) $request[]=$db->request('an.ad_id=%rd',$options['ad_id']);
			if(array_key_exists('orga_id',$options)) $request[]=$db->request('an.orga_id=%rd',$options['orga_id']);
			if(array_key_exists('idformintercarif',$options)) $request[]=$db->request('an.idformintercarif=%rs',$options['idformintercarif']);
			if(array_key_exists('idorgaintercarif',$options)) $request[]=$db->request('an.idorgaintercarif=%rs',$options['idorgaintercarif']);
			if(array_key_exists('domaine',$options)) $request[]=$db->request('SUBSTRING(an.formacode,1,3)=%rs',$options['domaine']);

			$db->prepare("SELECT an.createdat,an.orga_id,an.ad_id,an.formacode,r.path,r.label,an.avisjson 
			              FROM anotea an 
			              INNER JOIN reference r ON r.type=3 AND r.status=1 AND r.extradata=SUBSTRING(an.formacode,1,3)
			              WHERE an.status='ACTIVE' AND %s
			              ORDER BY an.createdat DESC",
			             implode(' AND ',$request));
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);
			return $db->assign('{createdat}','orga_id','ad_id','formacode','formapath','domaine','avisjson');
		}

		public function get($options)
		{
			return $this->prepareRequest($options)->queryFetchAll();
		}

		public function getByAnnonceId($id)
		{
			return $this->prepareRequest(array('ad_id'=>$id))->queryFetchAll();
		}

		public function getByDomaine($formacode,$orga_id)
		{
			return $this->prepareRequest(array('domaine'=>substr($formacode,0,3),'orga_id'=>$orga_id))->queryFetchAll();
		}

		public function getByIdFormIntercarif($idformintercarif)
		{
			return $this->prepareRequest(array('idformintercarif'=>$idformintercarif))->queryFetchAll();
		}

		public function getByOrgaId($id)
		{
			return $this->prepareRequest(array('orga_id'=>$id))->queryFetchAll();
		}

		public function getByIdOrgaIntercarif($idorgaintercarif)
		{
			return $this->prepareRequest(array('idorgaintercarif'=>$idorgaintercarif))->queryFetchAll();
		}

		public function getNbAvisDomaineOrga($orga_id)
		{
			$db=$this->db;

			//$db->prepare("SELECT r.label,an.formacode,ROUND(AVG(an.noteglobale)) AS notemoyenne,COUNT(an.orga_id) AS nbavis
			//              FROM anotea an
			//              INNER JOIN reference r ON r.type=4 AND r.status=1 AND r.level=0 AND FUNC_EXTRADATA('fm',r.extradata,'')=SUBSTRING(an.formacode,1,3)
			//              WHERE an.status='ACTIVE' AND an.orga_id=%rd
			//              GROUP BY FUNC_EXTRADATA('fm',r.extradata,'')",
			//              $orga_id);
			if(0)
				$db->prepare("
					SELECT r.label,r.labelslug,FUNC_EXTRADATA('fm',r.extradata,'') AS formacode,r.path AS formapath,ROUND(AVG(an.noteglobale)) AS notemoyenne,COUNT(DISTINCT an.id) AS nbavis,COUNT(DISTINCT s.ad_id,s.location) AS nbannonces
					FROM reference r
					INNER JOIN sphad s ON s.query=CONCAT('@formacode ',FUNC_EXTRADATA('fm',r.extradata,''),'*;mode=extended2;maxmatches=200000;limit=10000;filter=orgaid,%rd;')
					LEFT OUTER JOIN anotea an ON SUBSTRING(an.formacode,1,3)=FUNC_EXTRADATA('fm',r.extradata,'') AND an.status='ACTIVE' AND an.orga_id=%rd
					WHERE r.type=4 AND r.status=1 AND r.level=0
					GROUP BY FUNC_EXTRADATA('fm',r.extradata,'')",
					$orga_id,$orga_id);

			if(0)
				$db->prepare("
					SELECT sql_no_cache r.label,r.labelslug,FUNC_EXTRADATA('fm',r.extradata,'') AS formacode,r.path AS formapath,ROUND(AVG(an.noteglobale)) AS notemoyenne,COUNT(DISTINCT an.id) AS nbavis,s._sph_count AS nbannonces
					FROM reference r
					INNER JOIN sphad s ON s.query=CONCAT('@formacode ',FUNC_EXTRADATA('fm',r.extradata,''),'*;mode=extended2;maxmatches=1;groupby=attr:groupbyanotea;limit=10000;filter=orgaid,2345;')
					LEFT OUTER JOIN anotea an ON SUBSTRING(an.formacode,1,3)=FUNC_EXTRADATA('fm',r.extradata,'') AND an.status='ACTIVE' AND an.orga_id=2345
					WHERE r.type=4 AND r.status=1 AND r.level=0
					GROUP BY  r.label,r.labelslug,FUNC_EXTRADATA('fm',r.extradata,'') 
					ORDER BY formacode
				",$orga_id,$orga_id);

			if(1)
				$db->prepare("
					SELECT SQL_NO_CACHE
					       r.label,
					       r.labelslug,
					       s.racineformacodeprincipal AS formacode,
					       r.path AS formapath,
					       ROUND(AVG(an.noteglobale)) AS notemoyenne,
					       COUNT(an.id) AS nbavis,
					       s.nbannonces
					FROM
					(
						SELECT s.orgaid,s.racineformacodeprincipal,s._sph_count AS nbannonces
						FROM sphad s
						WHERE s.query=';mode=ext2;groupby=attr:racineformacodeprincipal;limit=10000;filter=orgaid,%rd'
					) AS s
					INNER JOIN reference r ON r.type=4 AND r.status=1 AND r.level=0 AND FUNC_EXTRADATA('fm',r.extradata,'')=s.racineformacodeprincipal
					LEFT OUTER JOIN anotea an ON SUBSTRING(an.formacode,1,3)=s.racineformacodeprincipal AND an.status='ACTIVE' AND an.orga_id=s.orgaid
					GROUP BY s.racineformacodeprincipal
					ORDER BY r.label;",
					$orga_id
				);

			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare(),false);

			return $db->queryFetchAll();
		}

		public function getStats()
		{
			$db=$this->db;

			$cache=new QCache(3600*24,array('mode'=>'FILE'));
			$key='ANOTEA_GETSTATS';

			if(($result=$cache->get($key))===false)
			{
				/* Nb de sessions actives FRANCE Entière  */
				$db->prepare("
					SELECT COUNT(s.id) AS cnt
					FROM ad a
					INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
					WHERE a.status='ACTIVE'"
				);
				$sessionsActives=$db->queryFetchAll();
				if($sessionsActives===false)
					error_log('Pb de stats classe Anotea sessionsActives');

				/* Nb de sessions actives idf  */
				$db->prepare("
					SELECT COUNT(s.id) AS cnt
					FROM ad a
					INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
					WHERE a.status='ACTIVE'
					  AND a.locationpath LIKE '/1/1/6/%%'"
				);
				$sessionsActivesIdf=$db->queryFetchAll();
				if($sessionsActivesIdf===false)
					error_log('Pb de stats classe Anotea sessionsActivesIdf');

				/* Nb de sessions actives à distance france entiere */
				$db->prepare("
					SELECT COUNT(DISTINCT s.id) AS cnt
					FROM ad a
					INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
					WHERE a.status='ACTIVE'
					  AND a.flags&4"
				);
				$sessionsActivesADistanceFranceEntiere=$db->queryFetchAll();
				if($sessionsActivesADistanceFranceEntiere===false)
					error_log('Pb de stats classe Anotea sessionsActivesADistanceFranceEntiere');

				/* Nb de formations actives avec code financeur collectif france entiere */
				$db->prepare("
					SELECT COUNT(DISTINCT s.id) AS cnt
				    FROM sphad sp
				    INNER JOIN ad a ON a.id=sp.ad_id AND a.status='ACTIVE'
				    INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
				    WHERE sp.query='@codefinanceur (2_* | 4_* | 6_* | 8_* | 9_* | 11_* | 12_* | 13_* | 15_* | 16_*);mode=extended2;maxmatches=200000;limit=200000'"
				);
				$sessionsActivesCodeFinanceurCollectif=$db->queryFetchAll();
				if($sessionsActivesCodeFinanceurCollectif===false)
					error_log('Pb de stats classe Anotea sessionsActivesCodeFinanceurCollectif');

				/* Nb de sessions actives un seul avis */
				$db->prepare("
					SELECT COUNT(*) AS cnt FROM
					(
						SELECT s.id,COUNT(DISTINCT an.id) AS aviscnt
						FROM ad a
						INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
						INNER JOIN anotea an ON an.ad_id=a.id AND an.status='ACTIVE'
						WHERE a.status='ACTIVE'
						GROUP BY s.id
						HAVING aviscnt=1
					) a"
				);
				$sessionsUnSeulAvis=$db->queryFetchAll();
				if($sessionsUnSeulAvis===false)
					error_log('Pb de stats classe Anotea sessionsUnSeulAvis');

				/* Nb de sessions actives au moins 1 avis */
				$db->prepare("
					SELECT COUNT(DISTINCT s.id) AS cnt
					FROM ad a
					INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
					INNER JOIN anotea an ON an.ad_id=a.id AND an.status='ACTIVE'
					WHERE a.status='ACTIVE'"
				);
				$sessionsActivesAuMoins1Avis=$db->queryFetchAll();
				if($sessionsActivesAuMoins1Avis===false)
					error_log('Pb de stats classe Anotea sessionsActivesAuMoins1Avis');

				/* Nb de sessions actives idf au moins 1 avis */
				$db->prepare("
					SELECT COUNT(DISTINCT s.id) AS cnt
					FROM ad a
					INNER JOIN session s ON s.ad_id=a.id AND s.status='ACTIVE' AND (s.endedat IS NULL OR s.endedat>NOW())
					INNER JOIN anotea an ON an.ad_id=a.id AND an.status='ACTIVE'
					WHERE a.status='ACTIVE'
					  AND a.locationpath LIKE '/1/1/6/%%'"
				);
				$sessionsActivesAuMoins1AvisIdf=$db->queryFetchAll();
				if($sessionsActivesAuMoins1AvisIdf===false)
					error_log('Pb de stats classe Anotea sessionsActivesAuMoins1AvisIdf');

				/* Nombre d'organismes de formation */
				$db->prepare("
					SELECT COUNT(DISTINCT o.id) AS cnt
					FROM orga o
					WHERE o.status='ACTIVE'"
				);
				$organismes=$db->queryFetchAll();
				if($organismes===false)
					error_log('Pb de stats classe Anotea organismes');

				/* Nombre d'organismes qui ont au moins un commentaire */
				$db->prepare("
					SELECT COUNT(DISTINCT o.id) AS cnt
					FROM orga o
					INNER JOIN anotea an ON an.orga_id=o.id AND an.status='ACTIVE'
					WHERE o.status='ACTIVE'"
				);
				$organismesAuMoins1Avis=$db->queryFetchAll();
				if($organismesAuMoins1Avis===false)
					error_log('Pb de stats classe Anotea organismesAuMoins1Avis');

				/* Nb de session avec au moins un avis dans le meme domaine et meme organisme */
				$db->prepare("
					SELECT COUNT(DISTINCT s.id) AS cnt
				    FROM ad a
				    INNER JOIN anotea an ON (SUBSTRING(a.formacode,1,3) = SUBSTRING(an.formacode,1,3)
				                      -- OR SUBSTRING(a.formacode,7,3) = SUBSTRING(an.formacode,1,3)
				                      -- OR SUBSTRING(a.formacode,13,3) = SUBSTRING(an.formacode,1,3)
				                      -- OR SUBSTRING(a.formacode,19,3) = SUBSTRING(an.formacode,1,3)
				                      -- OR SUBSTRING(a.formacode,25,3) = SUBSTRING(an.formacode,1,3) -- on pourrait stopper ici
				                      -- OR SUBSTRING(a.formacode,31,3) = SUBSTRING(an.formacode,1,3)
				                      -- OR SUBSTRING(a.formacode,37,3) = SUBSTRING(an.formacode,1,3)
				                      -- OR SUBSTRING(a.formacode,43,3) = SUBSTRING(an.formacode,1,3)
				                     ) AND a.orga_id=an.orga_id AND an.status='ACTIVE'
				    INNER JOIN session s ON s.ad_id=a.id AND (s.endedat IS NULL OR s.endedat>NOW()) AND s.status='ACTIVE'
				    WHERE a.status='ACTIVE' AND (a.endedat IS NULL OR a.endedat>NOW())"
				);
				$sessionsAuMoinsUnAvisMemeDomaineMemeOrganisme=$db->queryFetchAll();
				if($sessionsAuMoinsUnAvisMemeDomaineMemeOrganisme===false)
					error_log('Pb de stats classe Anotea 3');

				$result=array(
					'sessions'=>array_values($sessionsActives)[0]['cnt'],
					'sessions-idf'=>array_values($sessionsActivesIdf)[0]['cnt'],
					'sessions-a-distance'=>array_values($sessionsActivesADistanceFranceEntiere)[0]['cnt'],
					'sessions-avec-codefinanceur-collectif'=>array_values($sessionsActivesCodeFinanceurCollectif)[0]['cnt'],
					'sessions-un-seul-avis'=>array_values($sessionsUnSeulAvis)[0]['cnt'],
					'sessions-au-moins-un-avis'=>array_values($sessionsActivesAuMoins1Avis)[0]['cnt'],
					'sessions-au-moins-un-avis-idf'=>array_values($sessionsActivesAuMoins1AvisIdf)[0]['cnt'],
					'sessions-au-moins-un-avis-meme-domaine-meme-organisme'=>array_values($sessionsAuMoinsUnAvisMemeDomaineMemeOrganisme)[0]['cnt'],
					'organismes-au-moins-un-avis-idf'=>array_values($organismesAuMoins1Avis)[0]['cnt'],
					'organismes'=>array_values($organismes)[0]['cnt']
				);
				$cache->set($key,$result);
			}

			return $result;
		}
	}
?>
