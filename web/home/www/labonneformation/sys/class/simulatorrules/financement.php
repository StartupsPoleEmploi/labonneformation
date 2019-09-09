<?php
	/* Etape 1 - Régles de financement : Selectionne le type de financement */
	function financementMultiples($var,$financement,&$droits,$alloctype=null,$calcRff=true,$percent=100,$except=array())
	{
		//$rfpe1=array('remu'=>'Vous percevrez 652,02 € brut / mois','type'=>'rfpe');
		//$rfpe2=array('remu'=>'Vous percevrez 100% du salaire antérieur (Plancher : 644,17 € brut / mois, Plafond : 1 932,52 € brut / mois)','type'=>'rfpe');
		//$rfpe3=array('remu'=>'Vous percevrez 708,59 € brut / mois','type'=>'rfpe');
		//$rfpe4=array('remu'=>'Vous percevrez 130,34 € brut / mois','type'=>'rfpe');
		//$rfpe5=array('remu'=>'Vous percevrez 310,39 € brut / mois','type'=>'rfpe');
		//$rfpe6=array('remu'=>'Vous percevrez 339,35 € brut / mois','type'=>'rfpe');
		//$rfpe7=array('remu'=>'Vous percevrez 401,09 € brut / mois','type'=>'rfpe');
		extract($var);
		$remu=array();
		if(is_null($alloctype)) $alloctype=array('1'=>array('are','aex','aah'),'2'=>array('ass','ata','rsa'),'3'=>array('ass','ata','rsa','non'),'4'=>array('asr','atp','asp'));
		//echo $financement; print_r($alloctype);

		/* Cas spécial pour la région corse: si on empeche le rfpe, on met deja une remu par défaut basée sur le formulaire */
		if(in_array('rfpe',$except)) $remu=remuForm($var,$calcRff);

		if(!in_array('form',$except) && in_array($allocation_type,$alloctype['1'])) $remu=remuForm($var,$calcRff,$percent); //Règles N+0
		if(!in_array('rfpe',$except) && in_array($allocation_type,$alloctype['2']) && $situation_6moissur12) $remu=remuRfpe($var,'rfpe1',$calcRff,$percent); //Règles N+1
		if(!in_array('rfpe',$except) && in_array($allocation_type,$alloctype['2']) && $situation_th && $situation_6moissur12) $remu=remuRfpe($var,'rfpe2',$calcRff,$percent); //Règles N+2
		if(!in_array('rfpe',$except) && in_array($allocation_type,$alloctype['3']))
		{
			if($situation_th && !$situation_6moissur12) $remu=remuRfpe($var,'rfpe1',$calcRff,$percent); //Règles N+3
			if($situation_travailleurnonsal12dont6dans3ans) $remu=remuRfpe($var,'rfpe3',$calcRff,$percent); //Règles N+4
			if($situation_parentisole) $remu=remuRfpe($var,'rfpe1',$calcRff,$percent); //Règles N+5
			if($situation_mere3enfants) $remu=remuRfpe($var,'rfpe1',$calcRff,$percent); //Règles N+6
			if($situation_divorceeveuve) $remu=remuRfpe($var,'rfpe1',$calcRff,$percent); //Règles N+7
			if($age<18 && !$caseVousEtes) $remu=remuRfpe($var,'rfpe4',$calcRff,$percent); //Règles N+8
			if($age>=18 && $age<21 && !$caseVousEtes) $remu=remuRfpe($var,'rfpe5',$calcRff,$percent); //Règles N+9
			if($age>=21 && $age<26 && !$caseVousEtes) $remu=remuRfpe($var,'rfpe6',$calcRff,$percent); //Règles N+10
			if($age>=26 && !$caseVousEtes) $remu=remuRfpe($var,'rfpe7',$calcRff,$percent); //Règles N+11
		}
		if(!in_array('form',$except) && in_array($allocation_type,$alloctype['4'])) $remu=remuForm($var,$calcRff,$percent); //Règles N+12
		if($remu) $droits[$financement]=$remu;
		return $remu;
	}
	function remuCodeFinanceur($var,$financement,&$droits,$percent=100)
	{
		financementMultiples($var,$financement,$droits,null,true,$percent);
	}
	function codeFinanceur($cas,$codeFinanceur,$financement,&$droits,$var)
	{
		extract($var);
		$alloctype=array();
		$alloctype['CAS_1']=array('1'=>array('are','aex','aah'),'2'=>array('ass','ata','rsa'),'3'=>array('ass','ata','rsa','non'),'4'=>array('asr','atp','asp'));
		$alloctype['CAS_2']=array('1'=>array(),'2'=>array('rsa'),'3'=>array('rsa'),'4'=>array());

		//echo "$training_codefinanceur : $codeFinanceur";
		if(hasCodeFinanceur($training_codefinanceur,$codeFinanceur,$nbPlaces) || is_null($codeFinanceur))
		{//87=21 à ...
			financementMultiples($var,$financement,$droits,$alloctype[$cas],true,100);
		}
	}
	function financementCpf(&$droits,$var)
	{
		extract($var);
		$financement='cpf';
		//$alloctype=array('1'=>array('are','asp'),'2'=>array('ass','ata','rsa','non'),'3'=>array('ass','ata','rsa','non'),'4'=>array('ass','ata','rsa'));

		if(in_array($allocation_type,array('are','aex','asp','aah'))) $droits[$financement]=remuForm($var); //Règle N°+0
		if(in_array($allocation_type,array('ass','ata','rsa','non')) && $situation_6moissur12) $droits[$financement]=remuRfpe($var,'rfpe1'); //Règle N°+1
		if(in_array($allocation_type,array('ass','ata','rsa','non')) && $situation_6moissur12 && $situation_th) $droits[$financement]=remuRfpe($var,'rfpe2'); //Règle N°+2
		if(in_array($allocation_type,array('ass','ata','rsa','non')))
		{
			if($situation_th) $droits[$financement]=remuRfpe($var,'rfpe1'); //Règle N°+3
			if($situation_travailleurnonsal12dont6dans3ans) $droits[$financement]=remuRfpe($var,'rfpe3'); //Règle N°+4
			if($situation_parentisole) $droits[$financement]=remuRfpe($var,'rfpe1'); //Règle N°+5
			if($situation_mere3enfants) $droits[$financement]=remuRfpe($var,'rfpe1'); //Règle N°+6
			if($situation_divorceeveuve) $droits[$financement]=remuRfpe($var,'rfpe1'); //Règle N°+7
			if($age<18 && !$caseVousEtes) $droits[$financement]=remuRfpe($var,'rfpe4'); //Règle N°+8
			if($age>=18 && $age<21 && !$caseVousEtes) $droits[$financement]=remuRfpe($var,'rfpe5'); //Règle N°+9
			if($age>=21 && $age<26 && !$caseVousEtes) $droits[$financement]=remuRfpe($var,'rfpe6'); //Règle N°+10
			if($age>=26 && !$caseVousEtes) $droits[$financement]=remuRfpe($var,'rfpe7'); //Règle N°+11
		}
		if(in_array($allocation_type,array('asr','atp','asp'))) $droits[$financement]=remuForm($var);//Règle N°+12
	}
	function financementAfprPoei($var,&$droits)
	{
		extract($var);
		if(!$situation_personnecontrataide || $situation_personnesortantcontrataide)
			$typePoei='afprpoei';
		elseif($situation_personneencourscontrataide)
			$typePoei='poei';
		
		if(!$training_adistance) financementMultiples($var,$typePoei,$droits,null,true,100);
	}
	function financementAif($var,&$droits,$except=array())
	{
		extract($var);
		if(!$training_adistance) financementMultiples($var,'aif',$droits,null,true,100,$except);
	}
	function financementAifBilanDeCompetences($var,&$droits,$except=array())
	{
		extract($var);
		if($allocation_type=='are') $var['dont_check_minaref']=true; /* Supprimer le test dans remuForm() du min aref dans le cas de l'are */
		if(!$training_adistance) financementMultiples($var,'aifbilancompetences',$droits,null,true,100,array('rfpe'));
	}
	function financementAif200h($var,&$droits,$except=array())
	{
		extract($var);
		if($allocation_type=='are') $var['dont_check_minaref']=true; /* Supprimer le test dans remuForm() du min aref dans le cas de l'are */
		//if(!$training_adistance) financementMultiples($var,'aif200h',$droits,null,true,array('rfpe')); //8/6/2016: Comme c'est le carif qui top lorsque c'est plus a distance, on ne teste plus
		financementMultiples($var,'aif200h',$droits,null,true,100,array('rfpe'));
	}
	function financementAifArtisan($var,&$droits)
	{
		extract($var);
		if(!$training_adistance)
		{
			if($situation_inscritdepuisaumoins6moissur12mois) financementMultiples($var,'aifartisan',$droits,null,true,100); //Toutes les règles de N°84 à N°84-12
			if($situation_personnesortantcontrataide) financementMultiples($var,'aifartisan',$droits,null,true,100); //Toutes les règles de N°85 à N°85-12
			if($situation_liccsp) financementMultiples($var,'aifartisan',$droits,null,true,100); //Toutes les règles de N°86 à N°86-12
		}
	}
	function financementAifVaePartielle($var,&$droits)
	{
		extract($var);
		if(!$training_adistance)
		{
			if($situation_vaepartiellemoins5ans) financementMultiples($var,'aifvaepartielle',$droits,null,true,100);
		}
	}
	function financementAutres($var,&$droits)
	{
		$droits['autres']=remuNop($var);
	}
?>
