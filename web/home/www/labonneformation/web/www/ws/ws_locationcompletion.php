<?php
	/*
	 * 23/02/2017: Complètement à refaire
	 * -> Extraire notamment la requete de complétion pour la mettre dans le modele
	 * -> Utiliser le champ label search de la table reference pour les alias
	 */
	require_once(CLASS_PATH.'/tools.php');

	function parseExprLocation($keywords)
	{
		$keywords=strtolower(strtr($keywords,array("Œ"=>"oe","œ"=>"oe","Æ"=>"ae,","æ"=>"ae","À"=>"a","Á"=>"a","Â"=>"a","Ã"=>"a","Ä"=>"a","Å"=>"a","à"=>"a","á"=>"a","â"=>"a","ã"=>"a","ä"=>"a","å"=>"a","Ò"=>"o","Ó"=>"o","Ô"=>"o","Õ"=>"o","Ö"=>"o","Ø"=>"o","ò"=>"o","ó"=>"o","ô"=>"o","õ"=>"o","ö"=>"o","ø"=>"o","È"=>"e","É"=>"e","Ê"=>"e","Ë"=>"e","è"=>"e","é"=>"e","ê"=>"e","ë"=>"e","Ç"=>"c","ç"=>"c","Ì"=>"i","Í"=>"i","Î"=>"i","Ï"=>"i","ì"=>"i","í"=>"i","î"=>"i","ï"=>"i","Ù"=>"u","Ú"=>"u","Û"=>"u","Ü"=>"u","ù"=>"u","ú"=>"u","û"=>"u","ü"=>"u","ÿ"=>"y","Ñ"=>"n","ñ"=>"n")));
		$keywords=trim(preg_replace('#[^a-zA-Z0-9eéèêëaàäâoôöòuùüûiîï ]#sui',' ',trim($keywords)));
		$keywords=preg_replace('#\s+#Si',' ',$keywords);
		if($keywords!='')
		{
			$cor=array('sain(tes|te|ts|t)'=>'s$1',
			           'idf'=>'ile de france',
			           'paca'=>'provence alpes cote d\'azur',
			           'lrmp'=>'languedoc roussillon midi pyrenees',
			           'acal'=>'grand est',
			           'bfc'=>'bourgogne franche comte',
			           'ara'=>'auvergne rhone alpes',
			           'pdl'=>'pays de la loire',
			           'cvl'=>'centre val de loire');
			//$keywordsAlt=preg_replace('#\bsain(tes|te|ts|t)\b#Si','s$1 ',$keywords);
			$keywordsAlt=$keywords;
			foreach($cor as $exp=>$rep)
			{
				$exp="#\b$exp\b#Si";
				$keywordsAlt=preg_replace($exp,"$rep ",$keywordsAlt);
			}
			$keywordsAlt=preg_replace('#\s+#Si',' ',$keywordsAlt);
			if($keywords!=$keywordsAlt) $keywords=sprintf('"^%s*"|"^%s*"',$keywords,$keywordsAlt);
		}
		if($keywords!='') return $keywords;
		return null;
	}
	function highlight($words,$keywords)
	{
		return Tools::text2Html($words,true,array('highlight'=>$keywords,'highlightstyle'=>array('font-weight:bold;')));
	}

	$sql=$db=$this->getStore('read');
	$result=array();

	$keywords=$this->get('q',''); //iconv("UTF-8","CP1252//TRANSLIT",$this->get('q',''));
	$mode=$this->get('mode');
	$restrictionLocation=explode('|',$this->get('locationpath'));
	$showDepartment=$this->get('showdepartment',1);
	$aDistance=$this->get('adistance',$mode=='zipcode'?false:true);

	$keywords2=parseExprLocation($keywords);
	if($keywords2!==null)
	{
		$maxLines=10;
		$arrayClauseText[]=sprintf('@label %s*',$keywords2);

		$arrayClauseFinal=array();
		$arrayClauseFinal[]=implode(' ',$arrayClauseText);
		$arrayClauseFinal[]='mode=extended2';
		$arrayClauseFinal[]='sort=attr_asc:level';
		$arrayClauseFinal[]='range=level,2,6';
		$arrayClauseFinal[]='maxmatches=50000';
		$arrayClauseFinal[]='limit=100';
		$arrayClauseFinal[]=sprintf('filter=type,%d',LOCATION_TYPE);
		
		$level='';
		if($mode=='zipcode' || $mode=='commune') $level=' AND r.level>=5';

		$restriction='';
		if(!empty($restrictionLocation))
			if($restrictionTab=implode(' OR ',array_map(function($e) use($db) {return $db->request('r.path LIKE %rs',"$e%");},$restrictionLocation)))
				$restriction="AND ($restrictionTab)";

		if(!$showDepartment)
			$restriction.=' AND r.level!=4 ';

		/* A deplacer dans le modele */
		$sql->prepare("
			SELECT IF(r.labeldisplay IS NOT NULL,r.labeldisplay,r.label) AS label,
			       IF(r.level>=4,FUNC_EXTRADATA('zc',r.extradata,''),'') AS codeprincipal,
			       IF(r.level>=4,FUNC_EXTRADATAALL('zc',r.extradata,'',null),'') AS code,
			       IF(r.level>=4,FUNC_EXTRADATA('in',r.extradata,''),'') AS insee,
			       IF(r.level>=4,CONCAT('(',SUBSTRING(FUNC_EXTRADATA('zc',r.extradata,FUNC_EXTRADATA('dn',r.extradata,'')) FROM 1 FOR 2),')'),'') AS depnum,
			       r.labelslug,r.path,r.extradata,level
			FROM sphreference s
			INNER JOIN reference r ON r.id=s.locationid AND r.type=%rd AND r.path LIKE '/1/1/%%' AND r.path!='/1/1/6/1/' AND r.level>1 %s %s
			WHERE s.query=%rs
			GROUP BY r.id,r.label,r.labelslug
			ORDER BY r.level,LENGTH(r.label)
			LIMIT %rd",
			LOCATION_TYPE,$level,$restriction,implode(';',$arrayClauseFinal),
			$maxLines);

		if($sql->query())
		{
			$result=array('key'=>$keywords,'list'=>array());
			while(($row=$sql->next())!==false)
			{
				$lb=$row['label'];
				$depnum=$row['depnum'];
				$insee=$row['insee'];
				$code=$row['codeprincipal'];
				$label=sprintf('<div style="float:right;">%s</div>&nbsp;<span>%s</span>',highlight($code,$keywords),highlight($lb,$keywords));
				$line=array(
					'label'=>$label,
					'value'=>array(
						'slug'=>$row['labelslug'],
						'label'=>"$lb $depnum",
						'path'=>$row['path'],
						'zipcode'=>Reference::extraData('zc',$row['extradata'],''),
						'insee'=>$insee
					)
				);
				if($mode=='zipcode')
				{
					foreach(Reference::extraDataAll('zc',$row['extradata'],array()) as $zc)
					{
						$line['label']=sprintf('<div style="float:right;">%s</div>&nbsp;<span>%s</span>',highlight($zc,$keywords),highlight($lb,$keywords));
						$line['value']['zipcode']=$zc;
						$result['list'][]=$line;
					}
				} else
					$result['list'][]=$line;
			}

			$result['list']=array_slice($result['list'],0,$maxLines);
			
			if($aDistance)
				$result['list'][]=array(
					'label'=>'<div class="distance"><img src="/img/pictos/picto-laptop.png" style="margin-right:.5em;" alt="">À distance</div>',
					'value'=>array(
						'slug'=>'',
						'label'=>'A distance',
						'path'=>'/0/1/',
						'zipcode'=>'',
						'insee'=>''
					)
				);
		}
	}

	$this->header("Content-Type:","text/html; charset=utf-8");
	$this->header('Expires: '.date('r',time()+3600));
	$this->header('Pragma: Public');
	$this->header('Cache-Control: public, must-revalidate, proxy-revalidate');
	if(count($result)>0) echo json_encode($result);
	die;
?>
