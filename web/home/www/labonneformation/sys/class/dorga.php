<?php
class DOrga
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
	 * @var String id organisation intercarif
	 *
	 */
	protected $idOrganisationIntercarif;

	/**
	 * @var String Nom
	 */
	protected $name;

	/**
	 * @var String
	 */
	protected $content;
	
	/**
	 * @var String
	 */
	protected $raison;

	/**
	 * @var String
	 */
	protected $siret;

	/**
	 * @var String
	 */
	protected $numacti;

	/**
	 * @var String
	 */
	protected $rue;

	/**
	 * @var String
	 */
	protected $ville;

	/**
	 * @var String
	 */
	protected $zipcode;

	/**
	 * @var String
	 */
	protected $codeInsee;

	/**
	 * @var Float
	 */
	protected $lat;

	/**
	 * @var Float
	 */
	protected $lng;

	/**
	 * @var String
	 */
	protected $tel;

	/**
	 * @var String
	 */
	protected $fax;

	/**
	 * @var String
	 */
	protected $mobile;

	/**
	 * @var String
	 */
	protected $email;

	/**
	 * @var String
	 */
	protected $url;

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

	/**
	 * status Getter 
	 *
	 * @return status
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * idOrganisationIntercarif Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param String $idOrganisationIntercarif
	 *
	 * @return $this
	 */
	public function setIdOrganisationIntercarif($idOrganisationIntercarif)
	{
		$this->idOrganisationIntercarif=$idOrganisationIntercarif;
		return $this;
	}

	/**
	 * idOrganisationIntercarif Getter 
	 *
	 * @return String
	 */
	public function getIdOrganisationIntercarif()
	{
		return $this->idOrganisationIntercarif;
	}

	/**
	 * name Setter 
	 *
	 * @param String $name Nom de l'organisme.
	 *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name=$name;
		return $this;
	}

	/**
	 * name Getter 
	 *
	 * @return String
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * name Setter 
	 *
	 * @param String $name Nom de l'organisme.
	 *
	 * @return $this
	 */
	public function setURL($url)
	{
		$this->url=$url;
		return $this;
	}

	/**
	 * name Getter 
	 *
	 * @return String
	 */
	public function getURL()
	{
		return $this->url;
	}


	/**
	 * Champ `content` dans la table orga 
	 *
	 * @return String
	 */
	public function getContent()
	{
		$orgaContent=new ContentParser();
		$addressContent=new ContentParser();
		$contactContent=new ContentParser();

		$addressContent->clear();
		$contactContent->clear();
		$orgaContent->clear();

		$contactContent->set('orgaid',array('type'=>'text'),$this->getIdOrganisationIntercarif());

		$contactContent->set('organame',array('type'=>'text'),$this->getName());
		$contactContent->set('raison',array('type'=>'text','display'=>'law'),$this->getRaison());
		$contactContent->set('siret',array('type'=>'text','display'=>'law'),$this->getSiret());
		$contactContent->set('numacti',array('type'=>'text','display'=>'law'),$this->getNumacti());

		/* Adresse */

		//$addressContent=getAddress($adresse,$addressContent,'address');

		$display='address';

		$addressContent->set('line',array('type'=>'text','display'=>$display),$this->getRue()."\n");
		$addressContent->set('city',array('type'=>'text','display'=>$display),$this->getZipcode().', '.$this->getVille());
		$addressContent->set('zipcode',array('type'=>'text'),$this->getZipcode());
		$addressContent->set('codeinsee',array('type'=>'text'),$this->getCodeInsee());
		$addressContent->set('lat',array('type'=>'float'),(float)$this->getLatitude());
		$addressContent->set('lng',array('type'=>'float'),(float)$this->getLongitude());

		/* Contact */

		//$content=getContact($coordonnees,$contactContent,'contact');

		$display='contact';

		$contactContent->set('tel',array('type'=>'tel','display'=>$display),$this->getTel());
		$contactContent->set('fax',array('type'=>'tel','display'=>$display),$this->getFax());
		$contactContent->set('mobile',array('type'=>'tel','display'=>$display),$this->getMobile());
		$contactContent->set('email',array('type'=>'email','display'=>$display),$this->getEmail());
		$contactContent->set('url',array('type'=>'url','display'=>$display),$this->getURL());

		$orgaContent->merge($addressContent)->merge($contactContent);

		return $orgaContent->serialize();
	}



	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setRaison($raison)
	{
		$this->raison=$raison;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getRaison()
	{
		return $this->raison;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setSiret($siret)
	{
		$this->siret=$siret;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getSiret()
	{
		return $this->siret;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setNumActi($numacti)
	{
		$this->numacti=$numacti;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getNumActi()
	{
		return $this->numacti;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setRue($rue)
	{
		$this->rue=$rue;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getRue()
	{
		return $this->rue;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setVille($ville)
	{
		$this->ville=$ville;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getVille()
	{
		return $this->ville;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setZipcode($zipcode)
	{
		$this->zipcode=$zipcode;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getZipcode()
	{
		return $this->zipcode;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setCodeInsee($codeInsee)
	{
		$this->codeInsee=$codeInsee;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getCodeInsee()
	{
		return $this->codeInsee;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setLatitude($lat)
	{
		$this->lat=$lat;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getLatitude()
	{
		return $this->lat;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setLongitude($lng)
	{
		$this->lng=$lng;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getLongitude()
	{
		return $this->lng;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setTel($tel)
	{
		$this->tel=$tel;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getTel()
	{
		return $this->tel;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setFax($fax)
	{
		$this->fax=$fax;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getFax()
	{
		return $this->fax;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setMobile($mobile)
	{
		$this->mobile=$mobile;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getMobile()
	{
		return $this->mobile;
	}

	/**
	 * id Setter 
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setEmail($email)
	{
		$this->email=$email;
		return $this;
	}

	/**
	 * id Getter 
	 *
	 * @return int
	 */
	public function getEmail()
	{
		return $this->email;
	}

	public function save()
	{
		// xxx verifier que tous les champs sont ok
		// xxx $vars à virer quand remplacé par $this partout...
		$vars = array();

		$fields='(createdat,status,idorgaintercarif,name,content)';

		$values=array();

		$values[]=$this->db->request("(now(),%rs,%rs,%rs,%rs)",
				$this->getStatus() ? $this->getStatus() : 'INACTIVE',
				$this->getIdOrganisationIntercarif(),
				$this->getName(),
				$this->getContent()
		);

		$this->db->prepare("INSERT INTO orga $fields VALUES %s",implode($values,",\n"));

		echo $this->db->getprepare()."\n";

		$id;

		if(!$this->db->query($id))
		{
			echo "Insert Orga Fail\n";
			printf("### %s\n%s\n",$this->db->geterror(),$this->db->getprepare());
		} else {
			$this->setId($id);
			return $this->getId();
		}
	}
}
?>
