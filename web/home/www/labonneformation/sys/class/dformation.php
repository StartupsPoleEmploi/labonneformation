<?php
require_once('dsessionformation.php');

class DFormation
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
	 * @var int Flags
	 *
	 * Le but est de stocker dans flags si la formation est finançable par une action collective dans le but de la mettre en prio de page result.php
	 * $flags =
	 * ($conventionnement?1:0) |
	 * ($modalitesEntreesSortie?2:0) |
	 * ($training_adistance?4:0) |
	 * ($training_diplomante?8:0) |
	 * ($training_certifiante?16:0) |
	 * ($contratApprentissage?32:0) |
	 * ($contratProfessionnalisation?64:0)|
         * ($rncp?128:0);
	 */
	protected $flags;

	/**
	 * @var int id catalogueId
	 *
	 */
	protected $catalogueId;

	/**
	 * @var int id organisation
	 *
	 */
	protected $organisationId;

	/**
	 * @var String id organisation intercarif
	 *
	 */
	protected $idOrganisationIntercarif;

	/**
	 * @var String id formation intercarif
	 *
	 */
	protected $idFormationIntercarif;

	/**
	 * @var array (String) formacodes
	 *
	 */
	protected $formacodes;

	/**
	 * @var array (String) codes ROMEs
	 *
	 */
	protected $romeCodes;

	/**
	 * @var String
	 */
	protected $intitule;

	/**
	 * @var String
	 */
	protected $description;

	/**
	 * @var DateTime
	 */
	protected $dateDebut;

	/**
	 * @var DateTime
	 */
	protected $dateFin;

	/**
	 * @var DateInterval
	 */
	protected $duration;

	/**
	 * @var String objective 
	 *
	 */
	protected $objective;

	/**
	 * @var String sanction
	 *
	 */
	protected $sanction;

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

	/**
	 * @var String
	 */
	protected $codeFinanceur;

	/**
	 * @var String
	 */
	protected $locationPath;

	/**
	 * @var String
	 */
	protected $locationSearch;

	/**
	 * @var String
	 */
	protected $zipCode;

	/**
	 * @var int niveau de sortie
	 *
	 */
	protected $niveauSortie;

	/**
	 * @var String RNCP
	 *
	 */
	protected $rncp;

	/**
	 * @var String
	 */
	protected $extraData;

	/**
	 * @var array objets SessionFormation
	 *
	 */
	protected $sessionsFormation; // Array

	/**
	 * @var array (array('type'=> $type, 'string' => SString))
	 *
	 */
	protected $conditions; // Array

	/**
	 * @var String URL de la formation sur le site de l'Organisme de Formation
	 */
	protected $urlFormation;

	protected $content_line;
	protected $content_city;
	protected $content_zipcode;
	protected $content_lat;
	protected $content_lng;

	protected $content_tel;
	protected $content_fax;
	protected $content_mobile;
	protected $content_email;
	protected $content_url;
	
	public function __construct($db)
	{
		$this->db=$db;
		$this->sessionsFormation = array(new DSessionFormation($db));
	}

	/**
	 * sessionsFormation Setter
	 * @param Array(DSessionformation)
	 *
	 * @return $this
	 */
	public function setSessionsFormation($sessionsFormation) {
		$this->sessionsFormation = $sessionsFormation;
	}

	/**
	 * sessionsFormation Getter 
	 *
	 * @return sessionsFormation
	 */
	public function getSessionsFormation()
	{
		return $this->sessionsFormation;
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
	 * flags Setter 
	 *
	 * @param int $flags
	 *
	 * @return $this
	 */
	public function setFlags($flags)
	{
		$this->flags=$flags;
		return $this;
	}

	/**
	 * flags Setter plus pratique 
	 *
	 * Fournir un array contenant uniquement
	 * les valeurs vrais pour cette formation
	 *
	 * @param array $flags ('CONVENTIONNEMENT','MODALITESENTREESSORTIES','DISTANCE','DIPLOMANTE','CERTIFIANTE','APPRENTISSAGE','PROFESSIONNALISATION','RNCP')
	 *
	 * @return $this
	 */
	public function setFinancementFlags($flags)
	{ $this->flags = (in_array('CONVENTIONNEMENT', $flags)?1:0) |
			(in_array('ENTREESSORTIESPERMANENTES', $flags)?2:0) |
			(in_array('DISTANCE', $flags)?4:0) |
			(in_array('DIPLOMANTE', $flags)?8:0) |
			(in_array('CERTIFIANTE', $flags)?16:0) |
			(in_array('APPRENTISSAGE', $flags)?32:0) |
			(in_array('PROFESSIONNALISATION', $flags)?64:0) |
			(in_array('RNCP', $flags)?128:0) |
			(in_array('FINANCEMENTCOLLECTIF', $flags)?256:0) |
			(in_array('FINANCEMENTNONRENSEIGNE', $flags)?512:0) |
			(in_array('PIC', $flags)?1024:0);

		return $this;
	}

	/**
	 * flags Getter 
	 *
	 * @return flags
	 */
	public function getFlags()
	{
		return $this->flags;
	}

	/**
	 * catalogueId Setter
	 *
	 * @param int $catalogueId
	 *
	 * @return $this
	 */
	public function setCatalogueId($catalogueId)
	{
		$this->catalogueId=$catalogueId;
		return $this;
	}

	/**
	 * catalogueId Getter
	 *
	 * @return int
	 */
	public function getCatalogueId()
	{
		return $this->catalogueId;
	}

	/**
	 * organisationId Setter 
	 *
	 * @param int $organisationId
	 *
	 * @return $this
	 */
	public function setOrganisationId($organisationId)
	{
		$this->organisationId=$organisationId;
		return $this;
	}

	/**
	 * organisationId Getter 
	 *
	 * @return int
	 */
	public function getOrganisationId()
	{
		return $this->organisationId;
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
	 * idFormationIntercarif Setter 
	 *
	 * Ne peut pas être null
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
	 * formacodes Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param array (String) $formacodes
	 *
	 * @return $this
	 */
	public function setFormacodes($formacodes)
	{
		$this->formacodes=$formacodes;
		return $this;
	}

	/**
	 * formacodes Getter 
	 *
	 * @return array (String)
	 */

	public function getFormacodes()
	{
		return $this->formacodes;
	}

	/**
	 * romeCodes Setter 
	 *
	 * @param array (String) $romeCodes
	 *
	 * @return $this
	 */
	public function setRomeCodes($romeCodes)
	{
		$this->romeCodes=$romeCodes;
		return $this;
	}

	/**
	 * romeCodes Getter 
	 *
	 * @return array (String)
	 */

	public function getRomeCodes()
	{
		return $this->romeCodes;
	}

	/**
	 * intitule Setter 
	 *
	 * @param String $intitule Intitulé de la formation.
	 *
	 * @return $this
	 */
	public function setIntitule($intitule)
	{
		$this->intitule=$intitule;
		return $this;
	}

	/**
	 * intitule Getter 
	 *
	 * @return String
	 */
	public function getIntitule()
	{
		return $this->intitule;
	}

	/**
	 * description Setter 
	 *
	 * @param String $description Description de la formation.
	 *
	 * @return $this
	 */
	public function setDescription($description)
	{
		$this->description=$description;
		return $this;
	}

	/**
	 * description Getter 
	 *
	 * @return String
	 */
	public function getDescription()
	{
		return $this->description;
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
	 * duration Setter 
	 *
	 * @param DateInterval $duration Durée de la formation.
	 *
	 * @return $this
	 */
	public function setDuration($duration)
	{
		$this->duration=$duration;
		return $this;
	}

	public function getDuration()
	{
		return $this->duration;
	}

	/**
	 * objective Setter 
	 *
	 * @param String $objective Objectif de la formation.
	 *
	 * @return $this
	 */
	public function setObjective($objective)
	{
		$this->objective=$objective;
		return $this;
	}

	/**
	 * objective Getter 
	 *
	 * @return String
	 */
	public function getObjective()
	{
		return $this->objective;
	}

	/**
	 * sanction Setter 
	 *
	 * @param String $sanction Sanction de la formation.
	 *
	 * @return $this
	 */
	public function setSanction($sanction)
	{
		$this->sanction=$sanction;
		return $this;
	}

	/**
	 * sanction Getter 
	 *
	 * @return String
	 */
	public function getSanction()
	{
		return $this->sanction;
	}

	/**
	 * latitude Setter 
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

	/**
	 * codeFinanceur Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param String $codeFinanceur
	 *
	 * @return $this
	 */
	public function setCodeFinanceur($codeFinanceur)
	{
		$this->codeFinanceur=$codeFinanceur;
		return $this;
	}

	/**
	 * codeFinanceur Getter 
	 *
	 * @return String
	 */
	public function getCodeFinanceur()
	{
		return $this->codeFinanceur;
	}

	/**
	 * locationPath Setter 
	 *
	 * Ne peut pas être null
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
	 * locationSearch Setter 
	 *
	 * @param String $locationSearch
	 *
	 * @return $this
	 */
	public function setLocationSearch($locationSearch)
	{
		$this->locationSearch=$locationSearch;
		return $this;
	}

	/**
	 * locationSearch Getter 
	 *
	 * @return String
	 */
	public function getLocationSearch()
	{
		// xxx code de interParse:
		//implode(' ',$vars['locationpathlist']),

		return $this->locationSearch;
	}

	/**
	 * zipCode Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param String $zipCode
	 *
	 * @return $this
	 */
	public function setZipCode($zipCode)
	{
		$this->zipCode=$zipCode;
		return $this;
	}

	/**
	 * zipCode Getter 
	 *
	 * @return String
	 */
	public function getZipCode()
	{
		return $this->zipCode;
	}

	/**
	 * niveauSortie Setter 
	 *
	 * @param int $niveauSortie
	 *
	 * @return $this
	 */
	public function setNiveauSortie($niveauSortie)
	{
		$this->niveauSortie=$niveauSortie;
		return $this;
	}

	/**
	 * niveauSortie Getter 
	 *
	 * @return int
	 */
	public function getNiveauSortie()
	{
		return $this->niveauSortie;
	}

	/**
	 * rncp Setter
	 *
	 * @param String $rncp
	 *
	 * @return $this
	 */
	public function setRNCP($rncp)
	{
		$this->rncp=$rncp;
		return $this;
	}

	/**
	 * rncp Getter
	 *
	 * @return String
	 */
	public function getRNCP()
	{
		return $this->rncp;
	}

	/**
	 * ExtraData Setter 
	 *
	 * Ne peut pas être null
	 *
	 * @param String $extraData
	 *
	 * @return $this
	 */
	public function setExtraData($extraData)
	{
		$this->extraData=$extraData;
		return $this;
	}

	/**
	 * extraData Getter 
	 *
	 * @return String
	 */
	public function getExtraData()
	{
		// xxx code de interParse :
		//implode('',$vars['extradata'])

		return $this->extraData;
	}

	/**
	 * conditions Setter
	 *
	 * Une condition a la structure :
	 * array( 'type' => $type,
	 *        'string' => $string)
	 *
	 * avec $type un string parmi 'condspec', 'condprise', 'infpubvis'.
	 *
	 * et $string une SString qui décrit la condition
	 *
	 * @param Array
	 *
	 * @return $this
	 */
	public function setConditions($conditions) {
		$this->conditions = $conditions;
	}

	/**
	 * conditions Getter
	 *
	 * @return conditions
	 */
	public function getConditions()
	{
		return $this->conditions;
	}

	/**
	 * urlFormation Setter
	 *
	 * @param String $urlFormation
	 *
	 * @return $this
	 */
	public function setUrlFormation($urlFormation)
	{
		$this->urlFormation=$urlFormation;
		return $this;
	}

	/**
	 * urlFormation Getter
	 *
	 * @return String
	 */

	public function getUrlFormation()
	{
		return $this->urlFormation;
	}

	/**
	 * Champ `content` dans la table ad 
	 *
	 * @return String
	 */
	public function getContent()
	{
/*
		$contactContent=new ContentParser();
		$addressContent=new ContentParser();
		$adContent=new ContentParser();

		$adContent->merge($addressContent)->merge($contactContent);
*/
		$content=new ContentParser();
		$sessionList = array();

		foreach($this->getSessionsFormation() as $session) {
			$sessionList[]=array('numero'=>(string)$session->getIdSessionIntercarif(),
				'beganat'=>$session->getDateDebut()->format('Y-m-d'),
				'endedat'=>$session->getDateFin()->format('Y-m-d'),
				'locationpath'=>$session->getLocationPath(),
				'lat'=>$session->getLatitude(),
				'lng'=>$session->getLongitude(),
				'session'=>$session);
		}

		$content->set('session',array('display'=>'none','type'=>'date'),$sessionList);

		if ($this->getDuration()) {
			$intervalInSeconds = (new DateTime())->setTimeStamp(0)->add($this->getDuration())->getTimeStamp();
			$intervalInHours=ceil($intervalInSeconds/3600);
			$content->set('duration',array('display'=>'header','type'=>'duration','title'=>'Durée'),$intervalInHours);
		}

/*
		if($nbHeuresEntreprise) $content->set('nbheuresent',array('display'=>'header','type'=>'duration'),$nbHeuresEntreprise);
		if($nbHeuresCentre) $content->set('nbheurescen',array('display'=>'header','type'=>'duration'),$nbHeuresCentre);
*/
		if($this->getRNCP()) $content->set('rncp',array('display'=>'none','type'=>'text','title'=>'RNCP'),$this->getRNCP());
/*
		//if((string)$cpf) $content->set('cpf',array('display'=>'none','type'=>'text','title'=>'CPF'),$cpf);
*/
		if($this->getObjective()) $content->set('objective',array('display'=>'body','type'=>'text'),(string)$this->getObjective());

		if($this->getDescription()) $content->set('description',array('display'=>'body','type'=>'text'),(string)$this->getDescription());

/*
		if((string)$sanction) $content->set('sanction',array('display'=>'body','type'=>'text'),(string)$sanction);
		if((string)$validation) $content->set('validation',array('display'=>'body','type'=>'text'),(string)$validation);
		if((string)$modaliteAlternance) $content->set('modalt',array('display'=>'modality','type'=>'text'),(string)$modaliteAlternance);
		if(1) $content->set('modens',array('display'=>'none','type'=>'text'),(string)$modaliteEnseignement);
		if((string)$coursDuSoir) $content->set('coursdusoir',array('display'=>'none'),$coursDuSoir);
		if((string)$coursWeekEnd) $content->set('coursweekend',array('display'=>'none'),$coursWeekEnd);
		if((string)$coursAlternance) $content->set('coursalternance',array('display'=>'none'),$coursAlternance);
		if($codeModalitesPedagogiques) $content->set('codemod',array('display'=>'none'),$codeModalitesPedagogiques);
		if((string)$codeFinanceur) $content->set('codefinanceur',array('display'=>'none'),$codeFinanceur);
		if((string)$modalitePedagogiques) $content->set('modped',array('display'=>'modality','type'=>'text'),(string)$modalitePedagogiques);
		if((string)$modaliteDureeIndicative) $content->set('moddurind',array('display'=>'modality','type'=>'text'),(string)$modaliteDureeIndicative);
*/

/*
		if((string)$conditionSpecifiques) $content->set('condspec',array('display'=>'condition','type'=>'text'),(string)$conditionSpecifiques);
		if((string)$conditionPriseEnCharge) $content->set('condprise',array('display'=>'condition','type'=>'text'),(string)$conditionPriseEnCharge);
		if((string)$conditionInfoPublicVise) $content->set('infpubvis',array('display'=>'condition','type'=>'text'),(string)$conditionInfoPublicVise);
*/

		if ($this->getConditions() && count($this->getConditions())>0) {
			foreach($this->getConditions() as $condition) {
				$content->set($condition['type'],array('display'=>'condition','type'=>'text'),(string)$condition['string']);
			}
		}
/*
		if((string)$niveauEntreeObligatoire) $content->set('nivent',array('type'=>'text'),(string)$niveauEntreeObligatoire);
		if((string)$dureeHebdo) $content->set('dureehebdo',array('display'=>'none'),$dureeHebdo);
		if((string)$codeCertifInfo) $content->set('certifinfo',array('display'=>'none'),$codeCertifInfo);
*/

		if($this->getUrlFormation()) $content->set('urlformation',array('display'=>'body','type'=>'html'),sprintf('<a href="%s" target="_blank" rel="nofollow">Consultez les informations complètes sur le site de l\'organisme</a>',(string)$this->getUrlFormation()));

/*
		$adContent->merge($content);
		return $adContent->serialize();
*/
		return $content->serialize();
	}

	public function save()
	{
		// xxx verifier que tous les champs sont ok
		// xxx $vars à virer quand remplacé par $this partout...
		$vars = array();

		//if(empty($vars)) return;
		//printf("insertformation %s\n",$vars['idformintercarif']);
		$fields='(createdat,status,flags,catalogue_id,orga_id,beganat,endedat,duration,idorgaintercarif,idformintercarif,codefinanceur,formacode,romecode,title,content,lat,lng,locationpath,locationsearch,zipcode,niveausortie,extradata)';

		$values=array();
		//print_r($vars['contentser']);

		$values[]=$this->db->request("(now(),%rs,%rd,%rd,%rd,%rs,%rs,%rd,%rs,%rs,%rs,%rs,%rs,%rs,%rs,%rf,%rf,%rs,%rs,%rs,%rd,%rs)",
				$this->getStatus() ? $this->getStatus() : 'INACTIVE',
				$this->getFlags(),
				$this->getCatalogueId(),
				$this->getOrganisationId(),
				$this->getDateDebut() ? $this->getdateDebut()->format('Y-m-d H:i:s') : null,
				$this->getDateFin() ? $this->getDateFin()->format('Y-m-d H:i:s') : null,
				$this->getDuration() ? ceil((new DateTime())->setTimeStamp(0)->add($this->getDuration())->getTimeStamp()/3600) : 0,
				$this->getIdOrganisationIntercarif(),
				$this->getIdFormationIntercarif(),
				$this->getCodeFinanceur() ? $this->getCodeFinanceur() : '',
				implode(' ',$this->getFormacodes()),
				implode(' ',$this->getRomeCodes()),
				$this->getIntitule(),
				$this->getContent(),
				$this->getLatitude(),
				$this->getLongitude(),
				$this->getLocationPath() ? $this->getLocationPath() : '',
				$this->getLocationSearch(),
				$this->getZipCode() ? $this->getZipCode() : '',
				$this->getNiveauSortie() ? $this->getNiveauSortie() : 0,
				$this->getExtraData() ? $this->getExtraData() : ''
		);

		$this->db->prepare("INSERT INTO ad $fields VALUES %s",implode($values,",\n"));

		//echo $this->db->getprepare()."\n";

		$id;

		if(!$this->db->query($id))
		{
			echo "Insert Formation Fail\n";
			printf("### %s\n%s\n",$this->db->geterror(),$this->db->getprepare());
		} else {
			$this->setId($id);

			foreach($this->getSessionsFormation() as $session) {
				$session->setAdId($this->getId());
				$session->save();
			}
		}

	}
}
?>
