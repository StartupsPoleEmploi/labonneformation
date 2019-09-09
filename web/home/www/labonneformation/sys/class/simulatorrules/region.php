<?php
	function isInGuadeloupe($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		return $depPath==LOCATIONPATH_GUADELOUPE?true:false;
	}
	function isInRegionNouvelleAquitaine($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_NOUVELLEAQUITAINE?true:false;
	}
	function isInRegionOccitanie($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_OCCITANIE?true:false;
	}
	function isInRegionExAquitaine($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/5/1/') return true; //Dordogne 24
		if($depPath=='/1/1/5/2/') return true; //Gironde 33
		if($depPath=='/1/1/5/3/') return true; //Landes 40
		if($depPath=='/1/1/5/4/') return true; //Lot et Garonne 47
		if($depPath=='/1/1/5/5/') return true; //Pyrénées Atlantique 64
		return false;
	}
	function isInRegionExPoitouChartente($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/5/6/') return true; //Charente 16
		if($depPath=='/1/1/5/7/') return true; //Charente Maritime 17
		if($depPath=='/1/1/5/8/') return true; //Deux Sèvres 79
		if($depPath=='/1/1/5/9/') return true; //Vienne 86
		return false;
	}
	function isInRegionExMidiPyrenees($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/6/6/') return true; //Ariege 09
		if($depPath=='/1/1/8/7/') return true; //Aveyron 12
		if($depPath=='/1/1/8/8/') return true; //Haute Garonne 31
		if($depPath=='/1/1/8/9/') return true; //Gers 32
		if($depPath=='/1/1/8/10/') return true; //Lot 46
		if($depPath=='/1/1/8/11/') return true; //Hautes Pyrénées 65
		if($depPath=='/1/1/8/12/') return true; //Tarn 81
		if($depPath=='/1/1/8/13/') return true; //Tarn et Garonne 82
		return false;
	}
	function isInRegionHautsDeFrance($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_HAUTSDEFRANCE?true:false;
	}
	function isInRegionBretagne($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_BRETAGNE?true:false;
	}
	function isInRegionDomTom($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_DOMTOM?true:false;
	}
	function isInRegionPaysDeLaLoire($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_PAYSDELALOIRE?true:false;
	}
	function isInRegionCentreValDeLoire($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_CENTREVALDELOIRE?true:false;
	}
	function isInRegionPACA($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_PACA?true:false;
	}
	function isInRegionCorse($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_CORSE?true:false;
	}
	function isInRegionBourgogne($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/14/4/') return true; //Yonne
		if($depPath=='/1/1/14/1/') return true; //Cote d'or
		if($depPath=='/1/1/14/2/') return true; //Nièvre
		if($depPath=='/1/1/14/3/') return true; //Saone et Loire
		return false;
	}
	function isInRegionFrancheComte($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/14/5/') return true; //Doubs
		if($depPath=='/1/1/14/7/') return true; //Haute Saone
		if($depPath=='/1/1/14/6/') return true; //Jura
		if($depPath=='/1/1/14/8/') return true; //Territoire de Belfort
		return false;
	}
	function isInRegionBourgogneFrancheComte($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/14/4/') return true; //Yonne
		if($depPath=='/1/1/14/1/') return true; //Cote d'or
		if($depPath=='/1/1/14/2/') return true; //Nièvre
		if($depPath=='/1/1/14/3/') return true; //Saone et Loire
		if($depPath=='/1/1/14/5/') return true; //Doubs
		if($depPath=='/1/1/14/7/') return true; //Haute Saone
		if($depPath=='/1/1/14/6/') return true; //Jura
		if($depPath=='/1/1/14/8/') return true; //Territoire de Belfort
		return false;
	}
	function isInRegionIDF($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_ILEDEFRANCE?true:false;
	}
	function isInRegionNormandie($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_NORMANDIE?true:false;
	}
	function isInRegionBasseNormandie($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/13/1/') return true; //Calvados
		if($depPath=='/1/1/13/2/') return true; //Manche
		if($depPath=='/1/1/13/3/') return true; //Orne
		return false;
	}
	function isInRegionHauteNormandie($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/13/4/') return true; //Eure
		if($depPath=='/1/1/13/5/') return true; //Seine Maritime
		return false;
	}
	function isInRegionMidiPyrenees($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/8/6/') return true; //Ariège
		if($depPath=='/1/1/8/7/') return true; //Aveyron
		if($depPath=='/1/1/8/9/') return true; //Gers
		if($depPath=='/1/1/8/8/') return true; //Haute Garonne
		if($depPath=='/1/1/8/11/') return true; //Hautes Pyrénées
		if($depPath=='/1/1/8/10/') return true; //Lot
		if($depPath=='/1/1/8/12/') return true; //Tarn
		if($depPath=='/1/1/8/13/') return true; //Tarn et Garonne
		return false;
	}
	function isInRegionLanguedocRoussillon($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/8/1/') return true; //Aude
		if($depPath=='/1/1/8/2/') return true; //Gard
		if($depPath=='/1/1/8/3/') return true; //Herault
		if($depPath=='/1/1/8/4/') return true; //Lozere
		if($depPath=='/1/1/8/5/') return true; //Pyrenees Orientales
		return false;
	}
	function isInRegionGrandEst($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/2/1/') return true; //Bas Rhin
		if($depPath=='/1/1/2/2/') return true; //Haut Rhin
		if($depPath=='/1/1/2/3/') return true; //Meurthe et Moselle
		if($depPath=='/1/1/2/4/') return true; //Meuse
		if($depPath=='/1/1/2/5/') return true; //Moselle
		if($depPath=='/1/1/2/6/') return true; //Vosges
		if($depPath=='/1/1/2/7/') return true; //Ardennes
		if($depPath=='/1/1/2/8/') return true; //Aube
		if($depPath=='/1/1/2/9/') return true; //Marne
		if($depPath=='/1/1/2/10/') return true; //Haute Marne
		return false;
	}
	function isInRegionExAuvergne($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/7/1/') return true; //Allier (03)
		if($depPath=='/1/1/7/2/') return true; //Cantal (15)
		if($depPath=='/1/1/7/3/') return true; //Haute Loire (43)
		if($depPath=='/1/1/7/4/') return true; //Puy de Dome (63)
		return false;
	}
	function isInRegionExRhoneAlpes($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/7/5/') return true; //Ain
		if($depPath=='/1/1/7/6/') return true; //Ardeche
		if($depPath=='/1/1/7/7/') return true; //Drome
		if($depPath=='/1/1/7/8/') return true; //Isere
		if($depPath=='/1/1/7/9/') return true; //Loire
		if($depPath=='/1/1/7/10/') return true; //Rhone
		if($depPath=='/1/1/7/11/') return true; //Savoie
		if($depPath=='/1/1/7/12/') return true; //Haute Savoie
		return false;
	}
	function isInRegionAuvergneRhoneAlpes($locationPath)
	{
		$regionPath=Reference::subPath($locationPath,3);
		return $regionPath==LOCATIONPATH_AUVERGNERHONEALPES?true:false;
	}
	function isInDepartementParis75($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		return $depPath==LOCATIONPATH_PARIS?true:false;
	}
	function isInDepartementHautsDeSeine92($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		return $depPath==LOCATIONPATH_HAUTSDESEINE?true:false;
	}
	function isInDepartementSeineStDenis93($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		return $depPath==LOCATIONPATH_SEINESAINTDENIS?true:false;
	}
	function isInRegionReunionMayotte($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/11/5/') return true; //Reunion
		if($depPath=='/1/1/11/7/') return true; //Mayotte
		return false;
	}
	function isInDepartements_54_55_57_88($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/2/3/') return true; //54
		if($depPath=='/1/1/2/4/') return true; //55
		if($depPath=='/1/1/2/5/') return true; //57
		if($depPath=='/1/1/2/6/') return true; //88
		return false;
	}
	function isInDepartements_08_10_51_52($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/2/7/') return true; //08
		if($depPath=='/1/1/2/8/') return true; //10
		if($depPath=='/1/1/2/9/') return true; //51
		if($depPath=='/1/1/2/10/') return true; //52
		return false;
	}
	function isInRegionExLimousin($locationPath)
	{
		$depPath=Reference::subPath($locationPath,4);
		if($depPath=='/1/1/5/10/') return true; //Correze 19
		if($depPath=='/1/1/5/11/') return true; //Creuse 23
		if($depPath=='/1/1/5/12/') return true; //Haute Vienne 87
		return false;
	}
?>
