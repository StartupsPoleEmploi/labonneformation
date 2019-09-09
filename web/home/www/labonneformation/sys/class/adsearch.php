<?php
	//Classe type API.

	class AdSearch
	{
		protected $codesFinanceurs=array(
			'0'=>'Autre',
			'1'=>'Code Obsolete(s)',
			'2'=>'Collectivite Territoriale Conseil Regional', //collectif
			'3'=>'Fonds Europeens Fse',
			'4'=>'Pole Emploi', //collectif
			'5'=>'Entreprise',
			'6'=>'Acse Anciennement Fasild', //collectif
			'7'=>'Agefiph',
			'8'=>'Collectivite Territoriale Conseil General', //collectif
			'9'=>'Collectivite Territoriale Commune', //collectif
			'10'=>'Beneficiaire De L\'Action',
			'11'=>'Etat Ministere Charge De L\'Emploi', //collectif
			'12'=>'Etat Ministere De L\'Education Nationale', //collectif
			'13'=>'Etat Autre', //collectif
			'14'=>'Fonds Europeens Autre',
			'15'=>'Collectivite Territoriale Autre', //collectif
			'16'=>'Opca', //collectif
			'17'=>'Opacif'
		);
		protected $db;

		public function __construct($db)
		{
			$this->db=$db;
		}
		public function getAdIdList($search=array(),$offset=0,$limit=100)
		{
			$db=$this->db;

			$db->prepare("
				SELECT SQL_NO_CACHE @ordre:=@ordre+1 AS ordre,
				       sa.ad_id,sa.id 
				FROM (SELECT @ordre:=0) AS ordre,sphad sa
				WHERE sa.query='%s'
				GROUP BY sa.ad_id
				ORDER BY ordre
				",$this->_makeSphinxClause($search,$limit));

			if($db->query())
				return $db->queryFetchAll();
			return false;
		}
		public function getListNew($search=array(),$offset=0,$limit=100)
		{
			$db=$this->db;

			$t=microtime(true);
			//if($idList=$this->getAdIdList($search,$offset,$limit))
			{
				$t2=microtime(true);
				
				$sphinxClause=$this->_makeSphinxClause($search,$limit);
				$lat=array_key_exists('geoanchor',$search)?$search['geoanchor']['lat']:0;
				$lng=array_key_exists('geoanchor',$search)?$search['geoanchor']['lng']:0;
				$tri_cpf=array_key_exists('tri_cpf',$search)?$search['tri_cpf']:false;

				$db->prepare("
					SELECT SQL_NO_CACHE a.*,
					c.name AS catalogue_name,
					s.idsessionintercarif AS session_uid,
					s.idactionintercarif AS session_uida,
					s.beganat AS datedebut,s.endedat AS datefin,
					s.lat AS session_lat,s.lng AS session_lng,r1.label AS ville,FUNC_EXTRADATA('zc',r1.extradata,'') AS zipcode,
					s.locationpath AS session_locationpath,
					o.id AS orgaid,o.content AS orgacontent,o.name AS organame,
					sa.distance AS dist,
					/*(6371*ACOS(COS(sa.lat)*COS(RADIANS(s.lat))*COS(RADIANS(s.lng)-sa.lng)+SIN(sa.lat)*SIN(RADIANS(s.lat)))) AS distance,*/
					(6371*ACOS(COS(sa.lat)*COS(RADIANS(FUNC_EXTRADATA('lt',r1.extradata,'')))*COS(RADIANS(FUNC_EXTRADATA('lg',r1.extradata,''))-sa.lng)+SIN(sa.lat)*SIN(RADIANS(FUNC_EXTRADATA('lt',r1.extradata,''))))) AS distance,
					FLOOR(IF(arb.bassinrate IS NULL,IF(arb.departementrate IS NULL,IF(arb.regionalrate IS NULL,arb.nationalrate,arb.regionalrate),arb.departementrate),arb.bassinrate)*5) AS tx
					FROM
					(
						SELECT @ordre:=@ordre+1 AS ordre,sa.ad_id,sa.id,sa._sph_geodist/1000 AS distance,RADIANS(%.5rf) AS lat,RADIANS(%.5rf) AS lng
						FROM (SELECT @ordre:=0) AS ordre,sphad sa
						WHERE sa.query='%s'
						GROUP BY sa.ad_id
						ORDER BY ordre
						LIMIT %rd
					) AS sa
					INNER JOIN session s ON s.ad_id=sa.ad_id
					INNER JOIN ad a ON a.id=sa.ad_id
					INNER JOIN catalogue c ON c.id=a.catalogue_id
					INNER JOIN orga o ON o.id=a.orga_id
					LEFT OUTER JOIN reference r1 ON r1.path=s.locationpath AND r1.type=6 AND r1.status=1
					LEFT OUTER JOIN adrankbassin arb ON arb.status='ACTIVE' AND arb.codeinsee=FUNC_EXTRADATA('ba',r1.extradata,'') AND arb.formacode=TRIM(SUBSTRING_INDEX(a.formacode,' ',1))
					ORDER BY sa.ordre,distance",
					//Reference::getLevelFromPath($locationPath),$locationPath,
					$lat,$lng,
					$sphinxClause,
					$limit
				);

				// Gérer l'affichage du lieu en fonction du lieu de recherche.
				//L'idée ici est d'itérer sur une liste de sessions d'annonces consécutives et dédoublonner sur un tableau $res en cumulant
				//les sessions dans une champ 'sessions' de chaque annonce
				if(($ads=$db->queryFetchAll())!==false)
				{
					$t3=microtime(true);
					$res=array();
					$annonce=array();
					$sessions=array();
					$content=new ContentParser();
					$orga=new ContentParser();
					$ad_id=0;
					foreach($ads as $ad)
					{
						//tri_cpf = variable qui détermine si l'on effectue un tri ou non
						//eligible_cpf = variable qui détermine si la formation est éligible au CPF quand on a décidé de trier les formations en fonction du CPF, sinon toujours à vrai
						$eligible_cpf=true;
						if($tri_cpf) {
							if($cpf=$db->prepare("
										SELECT codecpf
										FROM adcpf ac
										WHERE ac.ad_id=%rd AND ac.beganat<=NOW() AND ac.endedat>=NOW() AND ac.status='ACTIVE'",
										$ad['id'])
									->assign('codecpf')
									->queryFetchAll()) $eligible_cpf=true;
							else $eligible_cpf=false;
						}
						if ($eligible_cpf) {

							$content->parse($ad['content']);
							if($ad['id']!=$ad_id) //Si changement d'annonce, ou première ligne d'annonce, alors on initialise une nouvelle annonce
							{
								if($ad_id!=0) //Si ce n'est pas la toute premiere ligne, alors c'est un changement d'annonce et on l'ajoute dans $res
								{
									$res[]=$annonce;
									$annonce=array();
									$ad_id=$ad['id'];
								}
								$orga->parse($ad['orgacontent']);
								$sessions=array();

								$annonce=array(
									'catalogue'=>$ad['catalogue_name'],
									'id'=>$ad['id'],
									'uid'=>$ad['idformintercarif'],
									//'link'=>URL_BASE.$this->rewrite('/detail.php',array('ad'=>$ad)),
									'intitule'=>$ad['title'],
									'premier-intitule'=>$ad['firsttitle'],
									'objectif'=>substr(trim($content->get('objective','')),0,255),
									'description'=>substr(trim($content->get('description','')),0,255),
									'validation'=>trim($content->get('sanction','')),
									'codes-formacode'=>explode(' ',$ad['formacode']),
									'sessions'=>$sessions,
									//'sessions'=>array_map(function($session) {return array('debut'=>date('Y-m-d',$session['beganat']),'fin'=>date('Y-m-d',$session['endedat']));},$ad['session']),
									//'sessions'=>array(array('debut'=>(string)$content->get('begindate'),'fin'=>(string)$content->get('enddate'))),
									'contact'=>array(
										'email'=>(string)$content->get('email'),
										'tel'=>(string)$content->get('tel'),
										'fax'=>(string)$content->get('fax'),
										'url'=>(string)$content->get('url')
									),
									'organisme'=>array(
										'id'=>$ad['orgaid'],
										'nom'=>$ad['organame'],
										'siret'=>(string)$orga->get('siret'),
										'contact'=>array(
											'email'=>(string)$orga->get('email'),
											'tel'=>(string)$orga->get('tel'),
											'fax'=>(string)$orga->get('fax'),
											'url'=>(string)$orga->get('url')
										)
									),
									'niveau-retour-embauche'=>intval($ad['tx']),
									'metiers-vises'=>explode(' ',trim($ad['romecode'])),
									'distance-recherche'=>$ad['distance'],
									'entrees-sorties-permanentes'=>(bool)$this->isFlag($ad['flags'],'ENTREESSORTIESPERMANENTES'),
									'certifiante'=>(bool)$this->isFlag($ad['flags'],'CERTIFIANTE'),
									'rncp'=>(bool)$this->isFlag($ad['flags'],'RNCP'),
									'pic'=>(bool)$this->isFlag($ad['flags'],'PIC'),
									'a-distance'=>(bool)$this->isFlag($ad['flags'],'ADISTANCE'),
									'contratapprentissage'=>(bool)$this->isFlag($ad['flags'],'CONTRATAPPRENTISSAGE'),
									'contratprofessionnalisation'=>(bool)$this->isFlag($ad['flags'],'CONTRATPROFESSIONALISATION')
								);

								$ad_id=$ad['id'];
							}

							$annonce['sessions'][]=array(
								'id'=>$ad['id'],
								'uid'=>$ad['session_uid'],
								'uida'=>$ad['session_uida'],
								'debut'=>$ad['datedebut'],
								'fin'=>$ad['datefin'],
								'nombre-heures-total'=>(int)$ad['duration'],
								'nombre-heures-entreprise'=>((int)(string)$content->get('nbheuresent')),
								'nombre-heures-centre'=>((int)(string)$content->get('nbheurescen')),
								'localisation'=>array(
									'formation'=>array(
										//'location-slug'=>$ad['locationslug'],
										//'label'=>sprintf('%s > %s',$ad['locationparentlabel'],$ad['locationlabel']),
										'path'=>$ad['session_locationpath'],
										'lng'=>$ad['session_lng'],
										'lat'=>$ad['session_lat'],
										'ville'=>$ad['ville'],
										'code-postal'=>$ad['zipcode'],
										'distance-recherche'=>array_key_exists('locationsave',$search)?round($ad['distance'],3):0
									)
								)
							);
						}
					}
					//En sortant de l'itération, on check si y'avait pas une annonce en cours à mettre dans le tableau $res
					if(!empty($annonce)) $res[]=$annonce;

					$res=$this->cleanArray($res);
					$t4=microtime(true);

					return $res;
				}
			}
			return false;
		}
		protected function cleanArray($array)
		{
			return Tools::array_filter_recursive($array,function($var) {return !(empty($var) || is_null($var)) || $var=='0';});
		}

		/*
		 * Exploitation du tableau associatif de critères de recherche
		 * - keywords (optionnel)
		 * - codeinsee (optionnel)
		 * - geoanchor (array optionnel)-> lat, lng, distmax (en km, defaut 30 km)
		 * Note: si codeinsee fournit, la recherche se fait en geocode sur la résolution du codeinsee et écrase un éventuel géocoding fournit
		*/
		protected function _makeSphinxClause(&$param,$limit=100)
		{
			$db=$this->db;

			$ref=new Reference($db);
			$arrayClauseText=array();
			$arrayClauseGeo=array();
			$arrayClauseFilter=array();
			$dist=isset($param['distance'])?$param['distance']:30;

			/* Mots-clés: pour compatibilité, 2 noms de parametres possible keywords et search */
			if(isset($param['search']) || isset($param['keywords']))
				if($search=trim($param['keywords'].' '.$param['search']))
					if($cleanSearch=$this->cleanSearchWords($search))
						$arrayClauseText[]=sprintf('@title %s',$this->_makeSphinxFieldText($cleanSearch,true));

			/* Formacode et romes (code): le tout est combiné pour ne donner qu'un tableau de formacodes à rechercher */
			$formaList=array();
			if(isset($param['formacode']))
				if(is_array($param['formacode']))
					$formaList=array_merge($formaList,$param['formacode']);
				else
					$formaList[]=$param['formacode'];
			
			if(isset($param['code']))
			{
				$s=$param['code'];
				if(!is_array($s)) $s=preg_split('/ +/',trim($s));
				/* Transformation des path en formacode */
				foreach($s as $k=>$path)
					if($fc=$ref->get('ROMEAPPELLATION',$path))
						$formaList=array_merge($formaList,Reference::extraDataAll('fm',array_values($fc)[0]['extradata']));
			}
			if(isset($param['domaine']))
			{
				$domaineFormacode=$param['domaine'];
				if(!is_array($domaineFormacode)) $domaineFormacode=preg_split('/ +/',trim($domaineFormacode));
				foreach($domaineFormacode as $fm)
					$formaList[]=Tools::sphinxEscape($fm).'*';
			}
			if(!empty($formaList))
				$arrayClauseText[]=sprintf('@formacode %s',$this->_makeSphinxFieldText($formaList));
			/////////////////////////
			
			if(isset($param['orgaid']))
				$arrayClauseFilter[]=sprintf('filter=orgaid,%d',$param['orgaid']);
			
			/* Si codeinsee présent, on le remappe en lat et lng et on alimente geoanchor */
			if(isset($param['codeinsee']))
			{
				if($location=$ref->getByExtraData('LOCATION','in',$param['codeinsee']))
				{
					$location=array_values($location)[0];
					$param['geoanchor']=array(
						'lat'=>Reference::getExtraData('lt',$location['extradata']),
						'lng'=>Reference::getExtraData('lg',$location['extradata']),
						'distmax'=>$dist
					);
				}
			} else
			/* enfin le locationpath s'il ne reste que ça */
			if(isset($param['locationpath']))
			{
				$locationPath=$param['locationpath'];
				if($dist && !is_array($locationPath))
					if($ref->getLevelFromPath($locationPath)>=5)
						if(!empty($loc=$ref->get('LOCATION',$locationPath)))
						{
							$loc=array_values($loc)[0];
							$param['geoanchor']=array(
								'lat'=>$loc['lat'],
								'lng'=>$loc['lng'],
								'distmax'=>$dist
							);
							$param['locationsave']=$param['locationpath'];
							unset($param['locationpath']);
						}

				if(isset($param['locationpath']))
					if($locationPath=$param['locationpath'])
					{
						if(!is_array($locationPath)) $locationPath=preg_split('/ +/',trim($locationPath));
						$s=array_map(function($word) {return Tools::sphinxEscape(str_replace('/','_',$word)).'*';},$locationPath);
						$arrayClauseText[]=sprintf('@locationpath %s',implode(' | ',$s));
					}
			}
			/* Un geoanchor fourni est prioritaire sur le locationpath */
			if(isset($param['geoanchor']))
			{
				$geo=$param['geoanchor'];
				$geodist=isset($geo['distmax'])?$geo['distmax']:30;
				$geodist*=1000;
				$arrayClauseGeo[]=sprintf('geoanchor=lat,lng,%.5f,%.5f',deg2rad($geo['lat']),deg2rad($geo['lng']));
				$arrayClauseGeo[]=sprintf('floatrange=@geodist,1,%ld',$geodist);
				//$arrayClauseGeo[]="groupsort=@geodist asc";
			}

			if(isset($param['niveausortie']))
			{
				$niveauSortie=$param['niveausortie'];
				$range=array();
				foreach(is_array($niveauSortie)?$niveauSortie:array($niveauSortie) as $choix)
					switch($choix)
					{
						case 'bepcap':
							$range=array_unique(array_merge(array(4),$range));
							break;
						case 'bac':
							$range=array_unique(array_merge(array(5),$range));
							break;
						case 'bacplus2':
							$range=array_unique(array_merge(array(6),$range));
							break;
						case 'bacplus3etplus':
							$range=array_unique(array_merge(array(7,8,9),$range));
							break;
					}
					if($range) $arrayClauseFilter[]=sprintf('filter=niveausortie,%s',implode(',',$range));
			}

			if(isset($param['certifiante']))
				if($param['certifiante']==1)
					$arrayClauseFilter[]='filter=certifiante,1';

			if(isset($param['financementdeentier']))
				if($param['financementdeentier'])
					$arrayClauseFilter[]='filter=financementde,1';

			if(isset($param['financementsalarie']))
				if($param['financementsalarie'])
					$arrayClauseFilter[]='filter=financementsalarie,1';

			if(isset($param['financementpic']))
				if($param['financementpic'])
					$arrayClauseFilter[]='filter=financementpic,1';

			if(isset($param['contratapprentissage']))
				if($param['contratapprentissage'])
					$arrayClauseFilter[]='filter=contratapprentissage,1';

			if(isset($param['contratprofessionnalisation']))
				if($param['contratprofessionnalisation'])
					$arrayClauseFilter[]='filter=contratprofessionalisation,1';

			if(isset($param['modalite']))
			{
				$modalite=$param['modalite'];
				$range=array();
				foreach(is_array($modalite)?$modalite:array($modalite) as $choix)
					switch($choix)
					{
						case 'adistance':
							//$sphinx[]='filter=adistance,1';
							$range=array_unique(array_merge(array(1),$range));;
							break;
						case 'enorganisme':
							//$sphinx[]='filter=adistance,0';
							$range=array_unique(array_merge(array(0),$range));
							break;
					}
				if($range) $arrayClauseFilter[]=sprintf('filter=adistance,%s',implode(',',$range));
			}

			$arrayClauseFinal=array();
			if(!empty($arrayClauseText)) $arrayClauseFinal[]=implode(' ',$arrayClauseText);
			if(!empty($arrayClauseGeo)) $arrayClauseFinal[]=implode(';',$arrayClauseGeo);
			if(!empty($arrayClauseFilter)) $arrayClauseFinal[]=implode(';',$arrayClauseFilter);
			if(isset($param['geoanchor']))
				$arrayClauseFinal[]="sort=extended:proximitydate desc,@geodist asc,convention desc,rate desc";
			else
				$arrayClauseFinal[]="sort=extended:proximitydate desc,convention desc,rate desc";

			//$arrayClauseFinal[]='groupby=attr:ad_id';
			$arrayClauseFinal[]='mode=extended2';
			$arrayClauseFinal[]='maxmatches=1000';
			$arrayClauseFinal[]='limit=400';//sprintf('limit=%d',$limit);
			
			return implode(';',$arrayClauseFinal);
		}
		protected function _makeSphinxFieldText($keywords,$stem=false)
		{
			if(!is_array($keywords)) $keywords=array($keywords);
			$s=array_map(function($keywords) use($stem)
				{
					if($stem) return implode(' ',array_map(function($word) {return Tools::sphinxEscape(self::stem($word));},preg_split('/ +/',trim($keywords))));
					return Tools::sphinxEscape(trim($keywords));
				},$keywords);
			return implode(' | ',$s);
		}
		public function getByIdNew($id,$findBy='findbyid')
		{
			assert($id!=null);

			$db=$this->db;
			switch($findBy)
			{
				default:
				case 'findbyid':
					$toFind=$db->request('a.id=%rd',$id);
					$toFindAC=$db->request('ac.ad_id=%rd',$id);
					break;
				case 'findbyidintercarif':
				case 'findbyuid':
					$toFind=$db->request('a.idformintercarif=%rs',$id);
					$toFindAC=$db->request('ac.idformintercarif=%rs',$id);
					break;
			}

			$db->prepare("
				SELECT DATE_FORMAT(ac.beganat,'%%Y-%%m-%%d') AS beganat,
				       DATE_FORMAT(ac.endedat,'%%Y-%%m-%%d') AS endedat,
				       ac.source,ac.type,ac.codecpf,ac.branche,ac.interbranche,ac.locationpath,
				       IF(NOT ac.branche AND ac.locationpath='/1/1/',1,'') AS copanef
				FROM adcpf ac
				WHERE %s AND ac.beganat<=NOW() AND ac.endedat>=NOW() AND ac.status='ACTIVE'",
				$toFindAC)
				->assign(
					'debut','fin','source','type',
					function($a){return array('code'=>(int)$a);},
					function($a){return array('branches'=>explode(' ',$a));},
					function($a){return ($a=='1')?array('inter-branche'=>true):array('');},
					function($a){return ($a!='/1/1/')?array('region-insee'=>$a):array('');},
					function($a){return ($a=='1')?array('copanef'=>true):array('');}
				);

			if(($cpf=$db->queryFetchAll())!==false)
			{
				$db->prepare("
					SELECT c.name AS catalogue_name,
					       a.*,
					       SUBSTRING(a.formacode,1,5) AS formacodeprincipal,
					       r1.label AS locationlabel,FUNC_EXTRADATA('zc',r1.extradata,'') AS zipcode,
					       r2.label AS parentlocationlabel,
					       s.id AS session_id,s.idsessionintercarif AS session_uid,
					       s.idactionintercarif AS action_uid,
					       s.content AS sessioncontent,
					       DATE_FORMAT(s.beganat,'%%Y-%%m-%%d') AS datedebut,
					       DATE_FORMAT(s.endedat,'%%Y-%%m-%%d') AS datefin,
					       FUNC_EXTRADATA('lt',r1.extradata,'') AS session_lat,
					       FUNC_EXTRADATA('lg',r1.extradata,'') AS session_lng,
					       o.id AS orga_id,o.content AS orgacontent,o.name AS organame,
					       (
					           SELECT r.extradata
					           FROM sphreference s
					           INNER JOIN reference r ON r.id=s.id AND r.type=4
					           WHERE s.query=CONCAT('@formacode ',formacodeprincipal,';mode=extended2;maxmatches=200;filter=type,4')
					           ORDER BY r.level
					           LIMIT 1
					       ) AS extradataromesvises,
					       FLOOR(IF(arb.bassinrate IS NULL,IF(arb.departementrate IS NULL,IF(arb.regionalrate IS NULL,arb.nationalrate,arb.regionalrate),arb.departementrate),arb.bassinrate)*5) AS tx
					FROM ad a
					INNER JOIN catalogue c ON c.id=a.catalogue_id
					INNER JOIN session s ON s.ad_id=a.id AND (s.endedat IS NULL OR s.endedat>NOW()) AND s.status='ACTIVE'
					INNER JOIN orga o ON o.id=a.orga_id
					INNER JOIN reference r1 ON r1.path=s.locationpath AND r1.type=6
					LEFT OUTER JOIN reference r2 ON r2.path=FUNC_SUBPATH(a.locationpath,r1.level-1) AND r2.type=6 AND r2.status=1
					LEFT OUTER JOIN adrankbassin arb ON arb.status='ACTIVE' AND arb.codeinsee=FUNC_EXTRADATA('ba',r1.extradata,'') AND arb.formacode=TRIM(SUBSTRING_INDEX(a.formacode,' ',1))
					WHERE a.status='ACTIVE' AND %s",
					$toFind
				);

				$content=new ContentParser();
				$orga=new ContentParser();
				$sessionContent=$orga=new ContentParser();
				if($db->query())
				{
					$codesFinanceurs=$this->codesFinanceurs;
					$res=array();
					$flagsList=AdSearch::getFlagList();

					for($i=0;($ad=$db->next())!==false;$i++)
					{
						if($ad['content']) $content->parse($ad['content']);
						if($ad['sessioncontent']) $sessionContent->parse($ad['sessioncontent']);
						$prix=(string)$sessionContent->get('prix');
						$session=array(
							'id'=>(int)$ad['session_id'],
							'uid'=>$ad['session_uid'],
							'uida'=>$ad['action_uid'],
							'debut'=>$ad['datedebut'], //gmdate(DATE_ISO8601,strtotime($ad['datedebut'],time())),
							'fin'=>$ad['datefin'],
							'nombre-heures-total'=>(int)$ad['duration'],
							'nombre-heures-entreprise'=>((int)(string)$content->get('nbheuresent')),
							'nombre-heures-centre'=>((int)(string)$content->get('nbheurescen')),
							'prix-total-ttc'=>$prix!=''?(float)$prix:'',
							'localisation'=>array(
								'formation'=>array(
									//'location-slug'=>$ad['locationslug'],
									//'label'=>sprintf('%s > %s',$ad['locationparentlabel'],$ad['locationlabel']),
									'longitude'=>$ad['session_lng'],
									'latitude'=>$ad['session_lat'],
									'path'=>$ad['locationpath'],
									'ville'=>$ad['locationlabel'],
									'code-postal'=>$ad['zipcode'],
									'commune-insee'=>(string)$content->get('codeinsee'),
									'adresse'=>(string)$content->get('line'), //Attention patch: il faudra prendre celui du content de la session que l'on a pas encore !
								),
								'inscription'=>array(
								)
							),
							'financeurs'=>array_map(function($elem) use($codesFinanceurs) //Décomposition du champ de base, ex: 0[0] 10[0] 17[0] 5[0]
								{
									if(!empty($elem))
										if(preg_match('#^(\d+)\[(\d*)\]$#',$elem,$m)!==false)
											return array(
												'code'=>(int)$m[1],
												'places'=>(int)$m[2],
												'libelle'=>$codesFinanceurs[$m[1]]
											);
									return '';
								},explode(' ',(string)$ad['codefinanceur'])),
							'contact'=>array(
								'email'=>(string)$content->get('email'),
								'tel'=>(string)$content->get('tel'),
								'mobile'=>(string)$content->get('mobile'),
								'fax'=>(string)$content->get('fax'),
								'url'=>(string)$content->get('url')
							),
							'eligibilite-cpf'=>$cpf
						);
						if($i>0)
						{
							$res['sessions'][]=$session;
							continue;
						}
						$formacode=array_filter(explode(' ',$ad['formacode']));
						$flags=$ad['flags'];
						$orga->parse($ad['orgacontent']);
						$res=array(
							'catalogue'=>$ad['catalogue_name'],
							'id'=>(int)$ad['id'],
							'uid'=>$ad['idformintercarif'],
							//'link'=>URL_BASE.$this->rewrite('/detail.php',array('ad'=>$ad)),
							//'formation'=>array(
							'intitule'=>$ad['title'],
							'premier-intitule'=>$ad['firsttitle'],
							'objectif'=>trim($content->get('objective','')),
							'objectif-general-formation'=>trim($content->get('objgene','')),
							'description'=>trim($content->get('description','')),
							'codes-formacode'=>$formacode,
							'domaines-formacode'=>array_unique(array_map(function($code){ return substr($code,0,3);},$formacode)),
							'codes-rome'=>$ad['romecode']?explode(' ',$ad['romecode']):Reference::extraDataAll('rm',$ad['extradataromesvises'],null),
							'caracteristiques'=>array_keys(array_filter($flagsList,function($e) use($flags) {return $flags&$e?true:false;})),
							'sessions'=>array($session),
							
							'organisme'=>array(
								'id'=>$ad['orga_id'],
								'nom'=>$ad['organame'],
								'siret'=>(string)$orga->get('siret'),
								'localisation'=>!(string)$orga->get('zipcode')?null:array(
									'longitude'=>(string)$orga->get('lng'),
									'latitude'=>(string)$orga->get('lat'),
									'adresse'=>(string)$orga->get('line'),
									'ville'=>preg_replace('#^.*?, (.*)$#','$1',(string)$orga->get('city')),
									'code-postal'=>(string)$orga->get('zipcode')
								),
								'contact'=>array(
									'email'=>(string)$orga->get('email'),
									'tel'=>(string)$orga->get('tel'),
									'mobile'=>(string)$orga->get('mobile'),
									'fax'=>(string)$orga->get('fax'),
									'url'=>(string)$orga->get('url')
								)
							),
							'niveau-retour-embauche'=>intval($ad['tx']),
							'code-certifinfo'=>(int)(string)$content->get('certifinfo'),
							'code-rncp'=>(int)(string)$content->get('rncp'),
							'code-niveau-sortie'=>(int)(string)Reference::getExtraData('niv',$ad['extradata']),
							'resultats-attendus'=>(string)$content->get('sanction'),
							'validation'=>(int)(string)$content->get('validation'),
							'modalites-alternance'=>(string)$content->get('modalt'),
							'modalites-pedagogiques'=>(string)$content->get('modped'),
							'code-modalite-pedagogique'=>explode(' ',(string)$content->get('codemod')),
							'modalites-enseignement'=>(int)(string)$content->get('modens'),
							'duree-indicative'=>(string)$content->get('moddurind'),
							'conditions-specifiques'=>(string)$content->get('condspec'),
							'detail-conditions-prise-en-charge'=>(string)$content->get('condprise'),
							'info-public-vise'=>(string)$content->get('infpubvis'),
							'niveau-entree-obligatoire'=>(bool)$content->get('nivent'),
							'duree-hebdomadaire'=>(string)$content->get('dureehebdo'),
						);
					}
					return $this->cleanArray($res);
				}
			}
			return false;
		}

		/* Debut d'utilisation de l'API de l'intercarif */
		public function getByIdNewOld($id)
		{
			assert($id!=null);
			$db=$this->db;

			$tt=microtime(true);
			if($ad=$db->prepare('SELECT idformintercarif FROM ad a WHERE a.id=%rd',$id)->queryFetchFirst())
			{
				if($xml=file_get_contents(sprintf(URL_WSINTERCARIF,$ad['idformintercarif'])))
				{
					if($formation=simplexml_load_string($xml)->offres->formation)
					{
						$ad=array();
						foreach($formation->action as $action)
						{
							$ad['session']=array();
							foreach($action->session as $session)
							{
								$ad['session'][]=array(
									'beganat'=>$session->periode->debut!='0000000'?strtotime($session->periode->debut):null,
									'endedat'=>$session->periode->fin!='0000000'?strtotime($session->periode->fin):null,
								);
							}
						}
						//$ad['intitule']=
					}
				}
			}
			$tt=microtime(true)-$tt;
			return array();
		}
		public function getbyIdFormIntercarif($idformintercarif)
		{
			assert($idformintercarif!=null);
			return $this->get($idformintercarif,'findbyidintercarif');
		}
		public function getbyIdFormIntercarifNew($idformintercarif)
		{
			assert($idformintercarif!=null);
			return $this->getByIdNew($idformintercarif,'findbyidintercarif');
		}
		public function getById($id)
		{
			assert($id!=null);
			return $this->get($id,'findbyid');
		}
		protected function get($id,$findBy='findbyid')
		{
			assert($id!=null);

			$db=$this->db;

			$t=microtime(true);
			switch($findBy)
			{
				default:
				case 'findbyid':
					$toFind1=$db->request('filter=ad_id,%rd',$id);
					break;
				case 'findbyidintercarif':
					$toFind2=$db->request('@idformintercarif %s',Tools::sphinxEscape($id));
					break;
			}
			$db->prepare("
				SELECT a.orga_id AS orgaid,a.beganat,a.endedat,a.flags,a.idformintercarif,a.id,a.formacode,a.title,a.romecode,o.content AS orgacontent,a.content,
				       r1.label AS locationlabel,r2.label AS locationparentlabel,r1.path AS locationpath,r2.path AS parentlocationpath,
				       FLOOR(IF(arb.bassinrate IS NULL,IF(arb.departementrate IS NULL,IF(arb.regionalrate IS NULL,arb.nationalrate,arb.regionalrate),arb.departementrate),arb.bassinrate)*100) AS tx,
				       orr.followed/orr.cdi as orga_tx,arb.bassinrate,arb.departementrate,arb.regionalrate,arb.nationalrate,a.zipcode,
				       IF(a.firsttitle IS NULL,a.title,a.firsttitle) AS firsttitle,codefinanceur,
				       a.niveausortie,a.catalogue_id
				FROM sphad s
				INNER JOIN ad a ON a.id=s.ad_id AND a.status='ACTIVE'
				INNER JOIN orga o ON o.id=a.orga_id AND o.status='ACTIVE'
				LEFT OUTER JOIN reference r1 ON r1.path=a.locationpath AND r1.type=6 AND r1.status=1
				LEFT OUTER JOIN reference r2 ON r2.path=FUNC_SUBPATH(a.locationpath,r1.level-1) AND r2.type=6 AND r2.status=1
				LEFT OUTER JOIN orgarank orr ON orr.orga_id=a.orga_id AND orr.formacode=TRIM(SUBSTRING_INDEX(a.formacode,' ',1))
				LEFT OUTER JOIN adrankbassin arb ON arb.id=s.adrankid AND arb.status='ACTIVE'
				WHERE s.query='%s;mode=extended2;%s;groupby=attr:ad_id'",
			    $toFind2,
			    $toFind1
			);
			if($ad=$db->queryFetchFirst())
			{
				//$ad=$ad[0];
				$content=new QContentParser($ad['content']);

				$locationList=array();
				//$ad['session']=($ad['beganat']||$ad['endedat'])?array(array('beganat'=>$ad['beganat']?strtotime($ad['beganat']):null,'endedat'=>$ad['endedat']?strtotime($ad['endedat']):null)):array(); /* Pour le moment on bidouille la récupération des sessions: 23/5/2016 */
				$ad['session']=$content->get('session',array())->inner();
				
				$sessions=array();
				foreach($ad['session'] as $k=>$sess)
				{
					// Incomplete dates ("2019-00-00") : on les convertit en dates complètes

					// BeganAt
					if(!is_null($sess['beganat'])){
						$beganAtParsed=date_parse($sess['beganat']);

						if($beganAtParsed['warning_count']>0) // Warning when incomplete date
						{
							// Incomplete date
							if ($beganAtParsed['month']==0) $beganAtParsed['month']=1;
							if ($beganAtParsed['day']==0) $beganAtParsed['day']=1;
							$sess['beganat'] = sprintf("%04d-%02d-%02d", $beganAtParsed["year"], $beganAtParsed["month"], $beganAtParsed["day"]);
						}
					}

					// endedAt
					if(!is_null($sess['endedat'])){
						$endedAtParsed=date_parse($sess['endedat']);

						if($endedAtParsed['warning_count']>0) // Warning when incomplete date
						{
							// Incomplete date
							if ($endedAtParsed['month']==0) $endedAtParsed['month']=12;
							if ($endedAtParsed['day']==0) $endedAtParsed['day']=31;
							$sess['endedat'] = sprintf("%04d-%02d-%02d", $endedAtParsed["year"], $endedAtParsed["month"], $endedAtParsed["day"]);
						}
					}
					// Done pour les incomplete dates

					$beginAt=$sess['beganat']?strtotime($sess['beganat']):null;
					$endedAt=$sess['endedat']?strtotime($sess['endedat'].' 23:59:59'):null;
					if(!is_null($endedAt) && $endedAt<=time()) continue;
					$session=$sess;

					$session['beganat']=$beginAt;
					$session['endedat']=$endedAt;

					$sessions[]=$session;
					$locationList[]=$db->request('%rs',$sess['locationpath']);
				}

				if(empty($sessions)) return false;
				$ad['session']=$sessions;

				$locationLabels=array();
				$db->prepare("SELECT CONCAT(r1.label,' (',SUBSTRING(FUNC_EXTRADATA('zc',r1.extradata,''),1,2),')') as label,r1.path FROM reference r1
				              WHERE r1.type=6 AND r1.status=1
				                AND r1.path IN (%s)",
				              implode(',',$locationList));

				if($loc=$db->query())
					while(($row=$db->next())!==false)
					{
						$locationLabels[$row['path']]=$row['label'];
					}
				foreach($ad['session'] as $k=>$sess)
					$ad['session'][$k]['locationlabel']=$locationLabels[$sess['locationpath']];

				if($cpf=$db->prepare("SELECT source,beganat,endedat,codecpf,type,branche,locationpath
				                      FROM adcpf ac
				                      WHERE ac.ad_id=%rd AND ac.beganat<=NOW() AND ac.endedat>=NOW() AND ac.status='ACTIVE'",
				                      $ad['id'])
				           ->assign('source','{beganat}','{endedat}','codecpf','type',function($a){return array('branche'=>explode(' ',$a));},'locationpath')
				           ->queryFetchAll())
				{
					$ad['cpf']=$cpf;
				} else $ad['cpf']=array();

				/* Enrichissement du $content avec la validation: Principe à revoir */
				//$content=new ContentParser($ad['content']);
				$type='';
				switch($content->get('validation'))
				{
					case '1': $type='Diplôme Education Nationale'; break;
					case '2': $type='Diplôme travail'; break;
					case '3': $type='Diplôme Agriculture'; break;
					case '4': $type='Diplôme Jeunesse et Sports'; break;
					case '5': $type='Diplôme autres'; break;
					case '6': $type='Titre ou diplôme homologué'; break;
				}
				if($type) $content->set('typevalidation',$type);
				$ad['content']=$content;
				$ad['orgacontent']=new ContentParser($ad['orgacontent']);
				$ad['locationlabelbis']=sprintf('%s (%s)',$ad['locationlabel'],substr($ad['zipcode'],0,2));
				$ad['codes-formacode']=explode(' ',$ad['formacode']);
				if($romecodes=trim($ad['romecode']))
					$ad['romecode']=explode(' ',$ad['romecode']);
				else
					$ad['romecode']=array();

				/* Récupère les métiers visés puis les implante dans le content parser pour faciliter l'affichage dans la vue */
				$romeVises=$ad['romecode'];

				/* Enrichissement du $content avec romecode */
				$ref=new Reference($db);

				/* Enrichissement du $content avec targetformacode */
				$theRomes=array();
				if(!empty($ad['codes-formacode']))
					if($targetFormacode=$ref->getByExtraData('FORMACODEMULTI','fm',$ad['codes-formacode'][0])) //Recherche du libellé du formacode principal
					{
						usort($targetFormacode,function($a,$b) {return $a['level']<$b['level'];});
						$targetFormacode=array_values($targetFormacode)[0];
						$content->set('targetformacode',$targetFormacode['label']);
						$ad['formapath']=$targetFormacode['path'];
						$theRomes=Reference::extraDataAll('rm',$targetFormacode['extradata']);
						$content->set('romesrff',implode(' ',$theRomes));

						/* Si on a pas de rome codes, on prend ceux mappés par les formacodes */
						if(empty($ad['romecode'])) $ad['romecode']=$theRomes;
					}

				/* Si romeVises est vide on utilise les romesrff */
				if(empty($romeVises))
				{
					$romesRff=trim($content->get('romesrff',''));
					$romeVises=$romesRff?explode(' ',$romesRff):array();
				}

				//if(empty($romeVises)) $romeVises=Reference::extraDataAll('rm',$targetFormacode['extradata']);
				if(!empty($romeVises))
				{
					$content->set('romes',implode(' ',$romeVises));
					if($targetRomes=$ref->getByExtraData('ROME','rm',$romeVises))
					{
						$doc=array();
						$structuredDoc=array();
						foreach($targetRomes as $targetRome)
						{
							/* Partie à dégager partiellement du code et placer dans le template HTML */
							$doc[]=sprintf('%s (<a href="%s" target="_blank">voir la fiche métier</a>)',Tools::Text2Html($targetRome['label']),Tools::getPERomeLink($targetRome['data']));
							$structuredDoc[]=array(
								'rome'=>$targetRome['data'],
								'label'=>$targetRome['label']
							);
						}
						$content->set('targetromesstructured',$structuredDoc);
						$content->set('targetromes',array('type'=>'html'),implode("<br>\n",$doc));
					}
				}
				$ad['romepath']=array();
				if(!empty($ad['romecode']))
					if($romes=$ref->getByExtraData('ROMEAPPELLATION','rm',$ad['romecode'][0]))
						$ad['romepath']=array_keys($romes);
				$t=microtime(true)-$t;

				return $ad;
			}
			return false;
		}
		public function getList($s,$dist=30,$limit=100)
		{
			assert(is_array($s));

			$db=$this->db;
			
			$params=$this->prepareSearchEngineParams($db,$s,$dist,$limit);
			$sphinx=$this->prepareSphinxParams($db,$params);
			$this->prepareRequest($db,$sphinx,array('limit'=>$limit));

			if(($list=$db->queryFetchAll())!==false) // peut renvoyer false
			{
				$content=new QContentParser();
				foreach($list as $k=>$ad)
				{
					$content->parse($ad['content']);
					$sessions=$content->get('session',array())->inner();
					foreach($sessions as $i=>$sess)
					{
						$sessions[$i]['beganat']=$sess['beganat']?strtotime($sess['beganat']):null;
						$sessions[$i]['endedat']=$sess['endedat']?strtotime($sess['endedat']):null;
						if (!is_null($sessions[$i]['endedat']) && $sessions[$i]['endedat']<=time()) unset($sessions[$i]);
					}
					$list[$k]['session']=array_values($sessions);
				}
				if(empty($list)) _QUARKLOG('emptysearch.log',sprintf("%s\n",$_SERVER['REQUEST_URI']));
			}
			return $list;
		}
		/*
		 * Renvoie un tableau permettant ensuite l'extension de recherche
		 * Exemple de retour, renvoyant notamment le nombre d'annonces : (recherche dans le département, puis la région, puis national)
		 * array(
		 *    array('locationpath'=>'/1/1/25/5/',
		 *          'label'=>'Var (83)',
		 *          'extradata'=>'[dn:83]',
		 *          'cnt'=>1,
		 *          'keywords'=>'Communication externe',
		 *          'code'=>'/63/22/'),
		 *    array('locationpath'=>'/1/1/25/',
		 *          'label'=>'Provence Alpes Côte d'Azur',
		 *          'extradata'=>'[in:93]',
		 *          'cnt'=>13,
		 *          'keywords'=>'Communication externe',
		 *          'code'=>'/63/22/'),
		 *    array('locationpath'=>'/1/1/',
		 *          'label'=>'France',
		 *          'extradata'=>'',
		 *          'cnt'=>18,
		 *          'keywords'=>'Communication externe',
		 *          'code'=>'/63/22/')
		 *      )
		 */
		public function getCount($s)
		{
			$db=$this->db;
			$ref=new Reference($db);
			$params=array();

			/* Ou l'un ou l'autre mais pas les deux: choix entre le code Formacode et les mots-cles */
			if(trim($s['code'])) $params['code']=trim($s['code']);
			if($s['search']) $params['search']=$s['search'];
			if($s['orgaid']) $params['orgaid']=$s['orgaid'];

			$params['limit']=$limit;
			$curLevel=Reference::getLevelFromPath($s['locationpath']);
			$result=array();

			$limiteBasse=1;
			$totalCnt=0;
			if($curLevel>2)
				for($l=$curLevel-1;$l>$limiteBasse;$l--)
				{
					$params['locationpath']=Reference::subPath($s['locationpath'],$l);
					$sphinx=$this->prepareSphinxParams($db,$params);
					if($list=$this->prepareRequestCount($db,$sphinx,$l)->queryFetchAll())
					{
						$result[]=array_merge($list[0],array('keywords'=>$s['search'],'code'=>trim($s['code'])));
						$totalCnt+=$list[0]['cnt'];
					}
					else
					{
						if($location=$ref->get('LOCATION',$params['locationpath']))
						{
							$location=array_values($location)[0];
							$result[]=array('locationpath'=>$params['locationpath'],'label'=>$location['label'],'extradata'=>$location['extradata'],'cnt'=>0);
						}
					}
				}
			foreach($result as $k=>$line)
				if($dn=Reference::getExtraData('dn',$line['extradata']))
					$result[$k]['label'].=" ($dn)";
			return $totalCnt?$result:array();
		}
		/* A définir */
		public function getSiteMapList($limit)
		{
			$db=$this->db;
			$db->prepare("
				SELECT ad.id, IF(ad.firsttitle IS NULL, ad.title, ad.firsttitle) AS title, IF(ad.updatedat IS NULL,ad.createdat,ad.updatedat) AS lastmod
				FROM ad
				INNER JOIN `session` se ON se.ad_id=ad.id AND se.status='ACTIVE' AND (se.endedat IS NULL OR se.endedat>NOW())
				WHERE ad.status='active'
				GROUP BY ad.id
				ORDER BY ad.id DESC
				LIMIT %rd",
				$limit
			)->assign('id','title','cdi','{lastmod}');
			if($res=$db->queryFetchAll())
				return $res;
			return array();
		}
		private function prepareSearchEngineParams($db,$s,$dist,$limit)
		{
			$ref=new Reference($db);

			/* Ou l'un ou l'autre mais pas les deux: choix entre le code Formacode et les mots-cles */
			//if($s['code']) $params['code']=$s['code'];
			//if($s['search']) $params['search']=$s['search'];

			/* Si présent et pas de code, il devient prioritaire et est transformé en coderome path */
			if(array_key_exists('rome',$s))
				if($rome=$ref->getByExtraData('ROMEAPPELLATION','rm',$s['rome']))
					$s['code']=array_values($rome)[0]['path'];

			/* Si présent et pas de locationpath, il devient prioritaire et est transformé en locationpath */
			if(array_key_exists('codeinsee',$s))
				if($lieu=$ref->getByExtraData('LOCATION','in',$s['codeinsee']))
					$s['locationpath']=array_values($lieu)[0]['path'];

			/* Utilisation du geocode s'il est dispo sinon le locationpath est utilise */
			if(array_key_exists('locationpath',$s))
				if($dist && $ref->getLevelFromPath($s['locationpath'])>=5 && !empty($loc=$ref->get('LOCATION',$s['locationpath'])))
				{
					$loc=array_values($loc)[0];
					$s['lat']=$loc['lat'];
					$s['lng']=$loc['lng'];
					$s['dist']=$dist;
					unset($s['locationpath']);
				}
			$s['limit']=$limit;
			return $s;
		}
		private function prepareSphinxParams($db,$params=array())
		{
			$ref=new Reference($db);

			$searchWords=array();
			$lat=array_key_exists('lat',$params)?$params['lat']:null;
			$lng=array_key_exists('lng',$params)?$params['lng']:null;
			$dist=array_key_exists('dist',$params)?$params['dist']:'';
			$locationPath=array_key_exists('locationpath',$params)?$params['locationpath']:'';
			$code=(trim(array_key_exists('code',$params)?$params['code']:''));
			$domaineFormacode=(trim(array_key_exists('domaine',$params)?$params['domaine']:''));
			$search=array_key_exists('search',$params)?$params['search']:'';
			$orgaId=array_key_exists('orgaid',$params)?$params['orgaid']:'';
			$limit=array_key_exists('limit',$params)?$params['limit']:100;
			$sortType=array_key_exists('sort',$params)?$params['sort']:'DATE';
			$modalite=array_key_exists('modalite',$params)?$params['modalite']:false;
			$certifiante=array_key_exists('certifiante',$params)?$params['certifiante']:false;
			$diplomante=array_key_exists('diplomante',$params)?$params['diplomante']:false;
			$dureeFormation=array_key_exists('duree',$params)?$params['duree']:false;
			$debutEnMois=array_key_exists('datedebut',$params)?$params['datedebut']:false;
			$niveauSortie=array_key_exists('niveausortie',$params)?$params['niveausortie']:false;
			$financementDeEntier=array_key_exists('financementdeentier',$params)?$params['financementdeentier']:false;
			$financementSalarie=array_key_exists('financementsalarie',$params)?$params['financementsalarie']:false;
			$financementPic=array_key_exists('financementpic',$params)?$params['financementpic']:false;

			$niveau=array();
			if(array_key_exists('niveau_5',$params)) $niveau[]=5;
			if(array_key_exists('niveau_6',$params)) $niveau[]=6;
			if(array_key_exists('niveau_7',$params)) $niveau[]=7;
			if(array_key_exists('niveau_8',$params)) $niveau[]=8;
			if(array_key_exists('niveau_9',$params)) $niveau[]=9;

			$sphinx=array();
			$sort=array();

			if(!is_null($lat) && !is_null($lng))
			{
				$sphinx[]=sprintf('geoanchor=lat,lng,%f,%f;floatrange=@geodist,0,%ld',deg2rad($lat),deg2rad($lng),intval($dist*1000));
			}

			$sortByDist='';
			switch($sortType)
			{
				default:
				case 'DATE':
					if(!is_null($lat) && !is_null($lng)) $sortByDist=',@geodist asc';
					$sort[]="proximitydate desc${sortByDist},convention desc,rate desc";
					break;
				case 'RATE':
					if(!is_null($lat) && !is_null($lng)) $sortByDist=',@geodist asc';
					$sort[]='rate desc,convention desc,proximitydate desc${sortByDist}';
					break;
				case 'DIST':
					if(!is_null($lat) && !is_null($lng)) $sort['dist']='@geodist asc';
					break;
			}

			if($debutEnMois)
			{
				$range=array();
				foreach(is_array($debutEnMois)?$debutEnMois:array($debutEnMois) as $choix)
					switch($choix)
					{
						case 'dansmoins3mois':
							//$sphinx[]='range=debutenmois,0,3';
							$range=array_unique(array_merge(array(0,1,2,3),$range));
							break;
						case 'entre3et6mois':
							//$sphinx[]='filter=debutenmois,0,3,4,5,6';
							$range=array_unique(array_merge(array(0,3,4,5,6),$range));
							break;
						case 'dansplus6mois':
							//$sphinx[]='!range=debutenmois,1,5';
							$range=array_unique(array_merge(array(0,6,7,8,9,10,11,12),$range));
							break;
					}
				if($range) $sphinx[]=sprintf('filter=debutenmois,%s',implode(',',$range));
			}
			if($niveauSortie)
			{
				$range=array();
				foreach(is_array($niveauSortie)?$niveauSortie:array($niveauSortie) as $choix)
					switch($choix)
					{
						case 'bepcap':
							//$sphinx[]='filter=niveausortie,4';
							$range=array_unique(array_merge(array(4),$range));
							break;
						case 'bac':
							//$sphinx[]='filter=niveausortie,5';
							$range=array_unique(array_merge(array(5),$range));
							break;
						case 'bacplus2':
							//$sphinx[]='filter=niveausortie,6';
							$range=array_unique(array_merge(array(6),$range));
							break;
						case 'bacplus3etplus':
							//$sphinx[]='!range=niveausortie,0,6';
							$range=array_unique(array_merge(array(7,8,9),$range));
							break;
					}
					if($range) $sphinx[]=sprintf('filter=niveausortie,%s',implode(',',$range));
			}

			if($certifiante) $sphinx[]='filter=certifiante,1';

			if($financementDeEntier) $sphinx[]='filter=financementde,1';

			if($financementSalarie) {
				$sphinx[]='filter=financementsalarie,1';
			}

			if($financementPic) $sphinx[]='filter=financementpic,1';

			if($modalite)
			{
				$range=array();
				foreach(is_array($modalite)?$modalite:array($modalite) as $choix)
					switch($choix)
					{
						case 'adistance':
							//$sphinx[]='filter=adistance,1';
							$range=array_unique(array_merge(array(1),$range));;
							break;
						case 'enorganisme':
							//$sphinx[]='filter=adistance,0';
							$range=array_unique(array_merge(array(0),$range));
							break;
					}
				if($range) $sphinx[]=sprintf('filter=adistance,%s',implode(',',$range));
			}

			/* Utilisation du geocode s'il est dispo sinon le locationpath est utilise */
			if(!is_null($lat) && !is_null($lng))
			{
				$sphinx[]=sprintf('geoanchor=lat,lng,%f,%f;floatrange=@geodist,0,%ld',deg2rad($lat),deg2rad($lng),intval($dist*1000));
			}
			if($locationPath)
			{
				if(!is_array($locationPath)) $locationPath=preg_split('/ +/',trim($locationPath));
				$s=array_map(function($word) {return Tools::sphinxEscape(str_replace('/','_',$word)).'*';},$locationPath);
				$searchWords[]=sprintf('@locationpath %s',implode(' | ',$s));
			}

			/* Reception des code d'appellations du rome et transformation en formacodes */
			$formaList=array();
			if($code)
			{
				$s=$code;
				if(!is_array($s)) $s=preg_split('/ +/',trim($s));
				/* Transformation des path en formacode */
				foreach($s as $k=>$path)
					if($fc=$ref->get('ROMEAPPELLATION',$path))
						$formaList=array_merge($formaList,Reference::extraDataAll('fm',array_values($fc)[0]['extradata']));
			}
			if(!empty($formaList)) $searchWords[]=sprintf('@formacode (%s)',implode(' | ',$formaList));
			$formaList=array();
			if($domaineFormacode)
			{
				if(!is_array($domaineFormacode)) $domaineFormacode=preg_split('/ +/',trim($domaineFormacode));
				foreach($domaineFormacode as $fm)
					$formaList[]=Tools::sphinxEscape($fm).'*';
			}
			if(!empty($formaList)) $searchWords[]=sprintf('@formacode (%s)',implode(' | ',$formaList));


			if($cleanSearch=$this->cleanSearchWords($search))
			{
				$s=array_map(function($word) {return Tools::sphinxEscape(self::stem($word));},preg_split('/ +/',trim($cleanSearch)));
				$searchWords[]='@title '.implode(' ',$s);
			}

			$sphinx=array_merge(array(implode(' ',$searchWords)),$sphinx);
			$sphinx[]='mode=extended2;maxmatches=500';
			$sphinx[]='groupsort=attr:groupby';
			if(!empty($sort)) $sphinx[]='sort=extended:'.implode(',',$sort);
			$sphinx[]=sprintf('limit=%d',1000);
			if($orgaId)
			{
				$sphinx[]=sprintf('filter=orgaid,%ld',$orgaId);
			}

			return implode(';',$sphinx);
		}
		public function simplifieur($cleanSearch)
		{
			//Corrige les initiales type B.E.P. ou B E P, en BEP
			$cleanSearch=preg_replace("#(\w) (\w) #","$1$2",preg_replace("#([a-zA-Z])[.]#s","$1",$cleanSearch));

			$cleanSearch=preg_replace("#(ecole|formation) d.#siu",' ',$cleanSearch);
			$cleanSearch=preg_replace("#bp jeps#siu",'bpjeps',$cleanSearch);
			return $cleanSearch;
		}
		protected function noAccents($texte)
		{
			$texte=mb_strtolower($texte,'UTF-8');
			$texte=str_replace(array('à','â','ä','á','ã','å','î','ï','ì','í','ô','ö','ò','ó','õ','ø','ù','û','ü','ú','é','è','ê','ë','ç','ÿ','ñ'),array('a','a','a','a','a','a','i','i','i','i','o','o','o','o','o','o','u','u','u','u','e','e','e','e','c','y','n'),$texte);
			return $texte;
		}
		public function corrector($str)
		{
			$db=$this->db;

			$str=self::noAccents($str);
			$str=strtolower(iconv('UTF-8','ASCII//TRANSLIT',$str));
			$str=preg_replace('#[^a-zA-Z]#',' ',$str);
			$words=preg_split('# +#',$str);

			/* Chargement du dico avec les mots dont la distance levenshtein est <3 */
			$req=array();
			$dico=array();
			foreach($words as $k=>$word)
			{
				$len=strlen($word);
				$req[]=$db->request('(c.word LIKE %rs AND c.length>=%rd AND c.length<=%rd AND FUNC_LEVENSHTEIN(c.word,%rs)<3)',
				                    $word{0}.'%',$len-3,$len+3,$word);
				$words[$k]=array('word'=>$word,'correct'=>$word,'levensthein'=>100000);
			}
			$db->prepare("
				SELECT * FROM corrector c
				WHERE c.status='ACTIVE' AND (%s)
				ORDER BY count DESC
				",implode(' OR ',$req));
			if($db->query())
				while(($row=$db->next())!==false)
					$dico[$row['word']]=array('soundex'=>$row['soundex'],'metaphone'=>$row['metaphone'],'levensthein'=>10000);

			foreach($words as $k=>$word)
			{
				$w=$word['word'];
				foreach($dico as $correctWord=>$info)
				{
					$levensthein=levenshtein($w,$correctWord);
					if($levensthein<$words[$k]['levensthein'])
					{
						$words[$k]=array('word'=>$w,'correct'=>$correctWord,'levensthein'=>$levensthein);
					}
				}
			}
			foreach($words as $k=>$word)
			{
				$len=strlen($word['word']);
				if($len>2)
				{
					if($word['levensthein']>3)
						$words[$k]['correct']=$word['word'];
				} else
				{
					if($word['levensthein']>1)
						$words[$k]['correct']=$word['word'];
				}
			}

			$correct='';
			foreach($words as $k=>$word)
			{
				$correct.=$word['correct'].' ';
			}

			return trim($correct);
		}

		private function prepareRequest($db,$sphinx,$options=array('limit'=>100))
		{
			$limit=array_key_exists('limit',$options)?$options['limit']:100;

			if(1)
				$db->prepare("
					SELECT SQL_NO_CACHE s.ordre,
					       a.orga_id AS orgaid,a.idorgaintercarif,se.beganat,se.endedat,a.idformintercarif,
					       a.id,a.formacode,r0.path AS formapath,a.title,
					       a.content,o.content AS orgacontent,
					       o.name AS organame,r1.label AS locationlabel,
					       CONCAT(r1.label,' (',SUBSTRING(FUNC_EXTRADATA('zc',r1.extradata,''),1,2),')') AS locationlabeldisplay,
					       r2.label AS locationparentlabel,se.locationpath,r2.labelslug AS locationslug,
					       s.ratio,
					       FLOOR(IF(arb.bassinrate IS NULL,IF(arb.departementrate IS NULL,IF(arb.regionalrate IS NULL,
					       arb.nationalrate,arb.regionalrate),arb.departementrate),arb.bassinrate)*100) AS tx,
					       arb.nationalrate,arb.regionalrate,arb.departementrate,arb.bassinrate,
					       a.flags,a.lat,a.lng,a.romecode,IF(a.firsttitle IS NULL,a.title,a.firsttitle) AS firsttitle,
					       s._sph_geodist/1000 AS dist,s.nbavis AS nbavis,s.noteglobale AS notemoyenne,
					       IF(se.beganat IS NOT NULL,IF(IF(month(a.beganat)=0,CONCAT(DATE_FORMAT(a.beganat, '%%Y'), '-01-01'),a.beganat)>NOW(),10000000,DATEDIFF(IF(month(a.beganat)=0,CONCAT(DATE_FORMAT(a.beganat, '%%Y'), '-01-01'),a.beganat),NOW())),-9999999) AS proximitydate,
					       IF(a.flags&1,1,0) AS convention,
					       a.niveausortie,a.catalogue_id
					FROM
					(
						SELECT s.ordre,s.ad_id,s.ratio,s._sph_geodist,s.id,s.location,s.adrankid,
						       COUNT(an.id) AS nbavis,
						       ROUND(AVG(an.noteglobale)) AS noteglobale
						FROM
						(
							SELECT @ordre:=@ordre+1 AS ordre,s.id,s.ad_id,s.location,s.adrankid,s.ratio,s._sph_geodist,SUBSTRING(s.formacodeprincipal,1,3) AS formacode,s.orgaid
							FROM (SELECT @ordre:=0) AS ordre,sphad s
							WHERE s.query=%rs
							GROUP BY s.ad_id
						) AS s
						LEFT OUTER JOIN anotea an ON an.status='ACTIVE' AND an.orga_id=s.orgaid AND SUBSTRING(an.formacode,1,3)=s.formacode
						GROUP BY s.ad_id
					) AS s
					INNER JOIN session se ON se.ad_id=s.ad_id AND se.status='ACTIVE' AND (se.endedat IS NULL OR se.endedat>NOW())
					INNER JOIN ad a ON a.id=s.ad_id AND a.status='ACTIVE'
					INNER JOIN orga o on o.id=a.orga_id AND o.status='ACTIVE'
					INNER JOIN catalogue c ON c.id=a.catalogue_id AND c.status='ACTIVE'
					LEFT OUTER JOIN reference r0 ON r0.extradata=SUBSTRING_INDEX(a.formacode,' ',1) AND r0.type=3 AND r0.status=1
					LEFT OUTER JOIN reference r1 ON r1.path=REPLACE(se.locationpath,'_','/') AND r1.type=6 AND r1.status=1
					LEFT OUTER JOIN reference r2 ON r2.path=FUNC_SUBPATH(REPLACE(se.locationpath,'_','/'),LENGTH(se.locationpath)-LENGTH(REPLACE(se.locationpath,'_',''))-2) AND r2.type=6 AND r2.status=1
					LEFT OUTER JOIN adrankbassin arb ON arb.id=s.adrankid AND arb.status='ACTIVE'
					GROUP BY se.locationpath,se.ad_id
					ORDER BY s.ordre
					LIMIT 100",
					$sphinx
				); //'@title afpa marne;mode=extended2;maxmatches=100;groupsort=attr:groupby;sort=extended:proximitydate desc,convention desc,rate desc;limit=100'

			return $db;
		}

		public function getNbAdByDomainByOrga($orgaId)
		{
			$db=$this->db;

			$db->prepare("
				SELECT r.label,
				       r.labelslug,
				       FUNC_EXTRADATA('fm',r.extradata,'') AS formacode,
				       r.path AS formapath,
				       COUNT(DISTINCT se.ad_id,se.locationpath) as nbannonces
				FROM reference r
				INNER JOIN sphad s ON s.query=CONCAT('@formacode ',FUNC_EXTRADATA('fm',r.extradata,''),'*;mode=ext2;groupsort=attr:groupby;limit=1000;filter=orgaid,%rd')
				INNER JOIN session se ON se.id=s.id AND se.status='ACTIVE' AND (se.endedat IS NULL OR se.endedat>NOW())
				WHERE r.type=4 AND r.status=1 AND r.level=0
				GROUP BY r.label,r.labelslug,r.path",
				$orgaId
			);

			return $db->queryFetchAll();
		}
		private function prepareRequestCount($db,$sphinx,$pathLevel)
		{
			$db->prepare("
				SELECT FUNC_SUBPATH(se.locationpath,%rd) AS locationpath,r.label,r.extradata,
				       COUNT(DISTINCT se.ad_id,se.locationpath) AS cnt
			    FROM sphad s
			    INNER JOIN session se ON se.id=s.id AND (se.endedat IS NULL OR se.endedat>NOW()) AND se.status='ACTIVE'
			    INNER JOIN ad a ON a.id=s.ad_id AND a.status='ACTIVE'
			    INNER JOIN orga o on o.id=a.orga_id AND o.status='ACTIVE'
			    INNER JOIN reference r ON r.path=REPLACE(FUNC_SUBPATH(se.locationpath,%rd),'_','/') AND r.type=6 AND r.status=1
			    WHERE s.query=%rs
			    GROUP BY FUNC_SUBPATH(se.locationpath,%rd),r.label",
			    $pathLevel,$pathLevel,$sphinx,$pathLevel);

			return $db;
		}
		/* Utilisé pour épurer avant recherche tous les caractères pouvant parasiter la recherche */
		private function cleanSearchWords($str)
		{
			/* Stop words traité dans ce code plutot que dans la conf sphinx */
			$stopWords=array('le','la','les','de','d','l','n');

			$str=strtolower(preg_replace('#[^a-zA-Z0-9eéèêëaàäâoôöòuùüûiîïç ]#sui',' ',trim($str)));
			$str2=array();
			foreach(preg_split('/ +/',trim($str)) as $word)
				if(!in_array($word,$stopWords))
					$str2[]=$word;
			if(!empty($str2)) $str=implode(' ',$str2);
			return trim($str);
		}
		static function stem($word)
		{
			$stemList=array(
				'animat','profession','coiff','vendeu','animal','patiss','educat','sport','soud','conseil',
				'boulange','plomb','boucher','infirm','agricult','dieteti','mecanicien',
				'reparat','puericult','conduct','dessinat','coutur','cuisin','sommel','charcut'
			);
			$word=strtolower($word);
			foreach($stemList as $stem)
				if(substr($word,0,strlen($stem))==$stem)
					return "$stem*";
			return $word;
		}
		static function getFlagList()
		{
			$list=array(
				'CONVENTIONNE'=>1,
				'ENTREESSORTIESPERMANENTES'=>2,
				'ADISTANCE'=>4,
				'DIPLOMANTE'=>8,
				'CERTIFIANTE'=>16,
				'CONTRATAPPRENTISSAGE'=>32,
				'CONTRATPROFESSIONALISATION'=>64,
				'RNCP'=>128,
				'FINANCEMENTCOLLECTIFELARGIS'=>256,
				'FINANCEMENTNONRENSEIGNE'=>512,
				'PIC'=>1024
			);
			return $list;
		}
		static function isFlag($flag,$toCheck)
		{
			$flagList=self::getFlagList();

			if(array_key_exists($toCheck,$flagList))
				return $flag&$flagList[$toCheck]?true:false;
			return false;
		}
	}
?>
