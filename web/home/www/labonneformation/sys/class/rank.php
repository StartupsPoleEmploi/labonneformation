<?php
	class Rank
	{
		protected $db;
		var $error=array();

		public function __construct($db)
		{
			$this->db=$db;
		}
		public function getRank($formacodes)
		{
			$db=$this->db;
			if(!is_array($formacodes)) $formacodes=array($formacodes);
			foreach($formacodes as $k=>$formacode)
				$formacodes[$k]=$db->request('%rd',$formacode);

			$db->prepare("SELECT SUM(s.followed) AS followed,SUM(s.cdi) AS cdi,SUM(s.cdi)/SUM(s.followed) AS ratio,SUM(s.cdi)*100/SUM(s.followed) AS tx
			              FROM adrank s 
			              WHERE s.formacode IN (%s) 
			              AND s.status='ACTIVE'",$formacodes[0]);
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare());
			if($return=$db->queryFetchAll())
			{
				$return=$return[0];
				if($return['tx']>100) $return['tx']=83;
				return $return;
			}
			return array();
		}
		/* Comme adrankbassin possède des codes insee bassin, on va voir dans référence
		 * pour voir s'il n'y a pas un code insee bassin pour la ville donnée */
		public function getList2($formacode=null,$codeInsee=null,$type='ville')
		{
			$db=$this->db;
			$ref=new Reference($db);

		}
		public function getList($formacode=null,$codeInsee=null,$type='ville')
		{
			$db=$this->db;

			$this->error=array();
			$ref=new Reference($db);
			$formacodeReq=array();
			if(!is_null($formacode))
				$formacodeReq[]=$db->request("AND a.formacode=%rd",$formacode);
			else
			{
				$this->error=array('code'=>'ERROR_MISSING','source'=>'formacode','message'=>'Champ formacode manquant');
				return false;
			}

			if(!is_null($codeInsee))
			{
				if($line=$ref->getByExtraData('LOCATION','in',$codeInsee))
				{
					/* A partir du code insee ville on prend le code insee bassin, car la table adrankbassin de gère que ceux là */
					if($bassinInsee=Reference::getExtraData('ba',array_values($line)[0]['extradata'],false))
						$formacodeReq[]=$db->request("AND a.codeinsee=%rd",$bassinInsee);
					else
					{
						$this->error=array('code'=>'ERROR_NOSTATS','source'=>'codeinseeville','message'=>'Pas de statistiques sur ce lieu');
						return false;
					}
				} else
				{
					$this->error=array('code'=>'ERROR_NOTFOUND','source'=>'codeinseeville','message'=>'Code insee inconnu');
					return false;
				}
			} else
			{
				$this->error=array('code'=>'ERROR_MISSING','source'=>'codeinseeville','message'=>'Champ codeinseeville manquant');
				return false;
			}

			$db->prepare("SELECT a.formacode,a.codeinsee,a.bassinrate,a.regionalrate,a.nationalrate
			              FROM adrankbassin a
			              WHERE a.status='ACTIVE' %s",implode(' ',$formacodeReq));
			//if(ENV_DEV) echo Tools::Text2Html($db->getPrepare());
			return $db->queryFetchAll();
		}
		public function getOrgaTopRank()
		{
			$db=$this->db;

			$db->prepare("SELECT orga.id, orga.name, orgarank.cdi
			              FROM orga
			              INNER JOIN orgarank ON orgarank.idorgaintercarif = orga.idorgaintercarif
			              ORDER BY cdi DESC
			              LIMIT 5");

			return $db->queryFetchAll();
		}
	}
?>