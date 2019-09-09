<?php
class DSessionFormation
{
	protected $db;

	/**
	 * @var int id généré par MySQL
	 */
	protected $id;

	/**
	 * @var String Doit être 'ACTIVE', 'INACTIVE' ou 'DELETED'
	 */
	protected $status;

	/**
	 * @var int Action à laquelle est rattachée cette session de formation
	 */
	protected $action_id;

	/**
	 * @var int id Formation  à laquelle est rattachée cette session de formation
	 */
	protected $ad_id;

	/**
	 * @var String id formation intercarif
	 *
	 */
	protected $idFormationIntercarif;

	/**
	 * @var String id session intercarif
	 *
	 */
	protected $idSessionIntercarif;

	/**
	 * @var DateTime
	 */
	protected $dateDebut;

	/**
	 * @var DateTime
	 */
	protected $dateFin;

	/**
	 * @var String
	 */
	protected $locationPath;

	/**
	 * @var float latitude 
	 *
	 */
	protected $latitude;

	/**
	 * @var float longitude 
	 *
	 */
	protected $longitude;

	public function __construct($db)
	{
		$this->db=$db;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id=$id;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * status Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param String $status enum('ACTIVE','INACTIVE','DELETED')
	 *
	 * @return $this
	 */
	public function setStatus($status)
	{
		if (in_array($status, array('ACTIVE','INACTIVE','DELETED'))) {
			$this->status=$status;
		} else {
			$this->status='INACTIVE';
		}
		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * action_id Setter 
	 *
	 * @param int $action_id
	 *
	 * @return $this
	 */
	public function setActionId($ad_id)
	{
		$this->action_id=$action_id;
		return $this;
	}

	/**
	 * action_id Getter 
	 *
	 * @return int
	 */
	public function getActionId()
	{
		return $this->action_id;
	}

	/**
	 * ad_id Setter 
	 *
	 * @param int $ad_id
	 *
	 * @return $this
	 */
	public function setAdId($ad_id)
	{
		$this->ad_id=$ad_id;
		return $this;
	}

	/**
	 * ad_id Getter 
	 *
	 * @return int
	 */
	public function getAdId()
	{
		return $this->ad_id;
	}

	/**
	 * idFormationIntercarif Setter 
	 *
	 * @param String $idFormationIntercarif
	 *
	 * @return $this
	 */
	public function setIdFormationIntercarif($idFormationIntercarif)
	{
		$this->idFormationIntercarif=$idFormationIntercarif;
		return $this;
	}

	/**
	 * idFormationIntercarif Getter 
	 *
	 * @return String
	 */
	public function getIdFormationIntercarif()
	{
		return $this->idFormationIntercarif;
	}

	/**
	 * idSessionIntercarif Setter 
	 *
	 * @param String $idSessionIntercarif
	 *
	 * @return $this
	 */
	public function setIdSessionIntercarif($idSessionIntercarif)
	{
		$this->idSessionIntercarif=$idSessionIntercarif;
		return $this;
	}

	/**
	 * idSessionIntercarif Getter 
	 *
	 * @return String
	 */
	public function getIdSessionIntercarif()
	{
		return $this->idSessionIntercarif;
	}

	/**
	 * dateDebut Setter 
	 *
	 * @param DateString $dateDebut Date debut formation.
	 *
	 * @return $this
	 */
	public function setDateDebut($dateDebut)
	{
		$this->dateDebut=$dateDebut;
		return $this;
	}

	public function getDateDebut()
	{
		return $this->dateDebut;
	}

	/**
	 * dateFin Setter 
	 *
	 * @param DateString $dateFin Date fin formation.
	 *
	 * @return $this
	 */
	public function setDateFin($dateFin)
	{
		$this->dateFin=$dateFin;
		return $this;
	}

	public function getDateFin()
	{
		return $this->dateFin;
	}

	/**
	 * locationPath Setter 
	 *
	 * @param String $locationPath
	 *
	 * @return $this
	 */
	public function setLocationPath($locationPath)
	{
		$this->locationPath=$locationPath;
		return $this;
	}

	/**
	 * locationPath Getter 
	 *
	 * @return String
	 */
	public function getLocationPath()
	{
		return $this->locationPath;
	}

	/**
	 * latitude Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param float $latitude
	 *
	 * @return $this
	 */
	public function setLatitude($latitude)
	{
		$this->latitude=$latitude;
		return $this;
	}

	/**
	 * latitude Getter 
	 *
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * longitude Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param float $longitude
	 *
	 * @return $this
	 */
	public function setLongitude($longitude)
	{
		$this->longitude=$longitude;
		return $this;
	}

	/**
	 * longitude Getter 
	 *
	 * @return float
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	public function save()
	{
		// xxx verifier que tous les champs sont ok
		// xxx $vars à virer quand remplacé par $this partout...
		$vars = array();

		$fields='(createdat,status,action_id,ad_id,idformintercarif,idsessionintercarif,beganat,endedat,locationpath,lat,lng)';

		$values=array();

		$values[]=$this->db->request("(NOW(),'ACTIVE',%rd,%rd,%rs,%rs,%rs,%rs,%rs,%rf,%rf)",
                $this->getActionId(),
                $this->getAdId(),
                $this->getIdFormationIntercarif(),
                $this->getIdSessionIntercarif(),
		$this->getDateDebut() ? $this->getdateDebut()->format('Y-m-d H:i:s') : null,
		$this->getDateFin() ? $this->getDateFin()->format('Y-m-d H:i:s') : null,
		$this->getLocationPath() ? $this->getLocationPath() : '',
		$this->getLatitude() ? $this->getLatitude() : 0,
		$this->getLongitude() ? $this->getLongitude() : 0);

		$this->db->prepare("INSERT INTO session $fields VALUES %s",implode($values,",\n"));

		//echo $this->db->getprepare()."\n";

		$id;

		if(!$this->db->query($id))
		{
			echo "Insert Formation Fail\n";
			printf("### %s\n%s\n",$this->db->geterror(),$this->db->getprepare());
		} else {
			$this->setId($id);
		}
	}
}
?>
