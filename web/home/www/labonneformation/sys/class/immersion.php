<?php
class Immersion
{
	protected $db;
	const VUES_MAX=5;

	public function __construct($db)
	{
		$this->db=$db;
	}

	protected function prepareRequest($options)
	{
		$db=$this->db;

		$request=array(1);

		if(array_key_exists('ad_id',$options)) $request[]=$db->request('an.ad_id=%rd',$options['ad_id']);

		// Si pas de coordonnées GPS, utilise
		// La Chaize le Vicomte, épicentre de la Vendée
		// [lt:46.671570][lg:-1.295120]
		// comme point de départ de la recherche
		if ($options['lat']=='') $options['lat']= '46.671570';
		if ($options['lng']=='') $options['lng']= '-1.295120';

		$db->prepare("
				SELECT 
					id,
					lat,
					lng,
					adresse,
					codepostal,
					commune,
					enseigne,
					nomprenomcorrespondant,
					email,
					telephonecorrespondant,
					siretetablissement,
					rome,
					secteurlarge,
					(6371*ACOS(COS(RADIANS(%rf))*COS(RADIANS(lat))
						*COS(RADIANS(lng)-RADIANS(%rf)) + SIN(RADIANS(%rf))*SIN(RADIANS(lat)))) AS distance
				FROM immersions
				GROUP BY siretetablissement
				HAVING distance < 500
					AND rome=%rs
				ORDER BY distance 
				LIMIT 0 , 20",$options['lat'],$options['lng'],$options['lat'],$options['rome']);

				//LIMIT 0 , 60",46.447393,-0.791975,46.447393);

		return $db->assign('id','lat','lng','adresse','codepostal','commune','enseigne','nomprenomcorrespondant','email','telephonecorrespondant','siretetablissement','rome','secteurlarge','distance');
	}

	public function get($options)
	{
		return $this->prepareRequest($options)->queryFetchAll();
	}

	function storeDemandeImmersion($demande,$entreprises) {
		$d=$demande;
		$this->db->prepare("INSERT INTO immersion (createdat,status,locationpath,locationlabel,rome,romelabel,datedebut,duree,nom,prenom,statut,identifiant,email,entreprises,comment)
			VALUES (NOW(),'ACTIVE',%rs,%rs,%rs,%rs,%rt,%rs,%rs,%rs,%rs,%rs,%rs,%rs,NULL)",
				$d['locationpath'],$d['locationLabel'],$d['rome'],$d['romeLabel'],$d['debut'],$d['duree'],$d['nom'],$d['prenom'],$d['statut'],$d['identifiant'],$d['email'],json_encode($entreprises));

		if(!$this->db->query()) {
			return $this->db->getPrepare();
		}
		return true;
	}

	function cleanNAF($entreprises) {
		$blacklist_naf = array(
			'A1408'=>'9609',
			'A1503'=>'XXXXX',
			'D1102'=>'5610C',
			'D1202'=>'84',
			'D1202'=>'8411',
			'G1404'=>'8411',
			'G1501'=>'8411',
			'G1502'=>'8411',
			'G1503'=>'8411',
			'G1601'=>'8411',
			'G1602'=>'8411',
			'G1603'=>'8411',
			'G1605'=>'8411',
			'G1802'=>'8411',
			'G1803'=>'8411',
		);

		$conservees=array();
		foreach($entreprises as $entreprise) {
			if (array_key_exists($entreprise['rome'],$blacklist_naf) && preg_match('#^'.$blacklist_naf[$entreprise['rome']].'#i',$entreprise['naf'])) continue;
			array_push($conservees,$entreprise);
		}
		return $conservees;
	}

	function moderation($entreprises,$nombre) {
		$moderees=array();
		$count=0;

		foreach($entreprises as $entreprise) {
			$vues=0;
			$status='ACTIVE';

			$this->db->prepare("SELECT status,vues FROM immersionsirets WHERE siretetablissement=%rs;",
				$entreprise['siretetablissement']);

			if($match=$this->db->queryFetchFirst()) {
				print_r($match);
				$vues=$match['vues'];
				$status=$match['status'];
			}

			if ($status=='ACTIVE' && $vues < self::VUES_MAX) {
				// On la garde
				array_push($moderees,$entreprise);

				// Incrémente les vues
				$this->db->prepare("INSERT INTO immersionsirets (siretetablissement,createdat,updatedat,deletedat,status,vues,comment) VALUES (%rs,NOW(),NULL,NULL,'ACTIVE',1,NULL)
					ON DUPLICATE KEY UPDATE vues=vues+1,updatedat=NOW();
",$entreprise['siretetablissement'])->query();

				$count++;
				if ($count==$nombre) break;
			}
		}
		return $moderees;
	}
}
?>
