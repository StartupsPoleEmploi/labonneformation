<?php
	if(!defined('DOMAIN')) define('DOMAIN','labonneformation.pole-emploi.fr');
	define('URL_LBB','https://labonneboite.pole-emploi.fr');
	define('LBF_TITLE','La Bonne Formation');
	define('RATING','STAR');
	define('EMAIL_FROM','');
	define('ESDLBB_CLIENTID','');
	define('ESD_CLIENTID','');
	define('ESDLBB_CLIENTSECRET','');
	define('ESD_CLIENTSECRET','');

	define('TOKEN_LBB','');
	define('LBB_HMACKEY','');

	define('URL_IMPORTANOTEA','');
	define('URL_WSINTERCARIF','');
	define('URL_TREFLE','http://trefle.pole-emploi.fr');
	define('TREFLE_API_VERSION','0.7');
	define('URL_API_TREFLE',URL_TREFLE.(TREFLE_API_VERSION?"/".TREFLE_API_VERSION:""));
	define('CRYPT_PASS','');

	define('TAG_SEO_PROD','');
	define('TAG_SEO_RECETTE','');
	define('TAG_ANALYTICS_PROD','');
	define('TAG_ANALYTICS_RECETTE','');
	define('TAG_OPTIMIZE_PROD','');
	define('TAG_OPTIMIZE_RECETTE','');
	define('TAG_HOTJAR_PROD','');
	define('TAG_HOTJAR_RECETTE','');
	define('TAG_CRISP_PROD','');
	define('TAG_CRISP_RECETTE','');

	define('LOCATION_TYPE',6);
	/* Constantes locationpath regions */
	define('LOCATIONPATH_GRANDEST','/1/1/2/');
	define('LOCATIONPATH_NOUVELLEAQUITAINE','/1/1/5/');
	define('LOCATIONPATH_HAUTSDEFRANCE','/1/1/1/');
	define('LOCATIONPATH_BRETAGNE','/1/1/4/');
	define('LOCATIONPATH_CENTREVALDELOIRE','/1/1/12/');
	define('LOCATIONPATH_PACA','/1/1/9/');
	define('LOCATIONPATH_PAYSDELALOIRE','/1/1/3/');
	define('LOCATIONPATH_CORSE','/1/1/10/');
	define('LOCATIONPATH_BOURGOGNEFRANCHECOMTE','/1/1/14/');
	//define('LOCATIONPATH_FRANCHECOMTE','/1/1/15/');
	define('LOCATIONPATH_DOMTOM','/1/1/11/');
	define('LOCATIONPATH_ILEDEFRANCE','/1/1/6/');
	define('LOCATIONPATH_PARIS','/1/1/6/1/');
	define('LOCATIONPATH_HAUTSDESEINE','/1/1/6/5/');
	define('LOCATIONPATH_SEINESAINTDENIS','/1/1/6/6/');
	define('LOCATIONPATH_NORMANDIE','/1/1/13/');
	//define('LOCATIONPATH_HAUTENORMANDIE','/1/1/6/');
	define('LOCATIONPATH_AUVERGNERHONEALPES','/1/1/7/');
	define('LOCATIONPATH_OCCITANIE','/1/1/8/');
	//define('LOCATIONPATH_MIDIPYRENEES','/1/1/20/');

	define('LOCATIONPATH_AISNE','/1/1/1/3/');
	define('LOCATIONPATH_ALPESMARITIMES','/1/1/9/3/');
	define('LOCATIONPATH_ARDENNES','/1/1/2/7/');
	define('LOCATIONPATH_CORSEDUSUD','/1/1/10/1/');
	define('LOCATIONPATH_DROME','/1/1/7/7/');
	define('LOCATIONPATH_ESSONNE','/1/1/6/4/');
	define('LOCATIONPATH_EURE','/1/1/13/4/');
	define('LOCATIONPATH_EUREETLOIR','/1/1/12/2/');
	define('LOCATIONPATH_FINISTÈRE','/1/1/4/2/');
	define('LOCATIONPATH_GIRONDE','/1/1/5/2/');
	define('LOCATIONPATH_GUADELOUPE','/1/1/11/1/');
	define('LOCATIONPATH_GUYANE','/1/1/11/4/');
	define('LOCATIONPATH_HAUTECORSE','/1/1/10/2/');
	define('LOCATIONPATH_HAUTESAVOIE','/1/1/7/12/');
	define('LOCATIONPATH_LAREUNION','/1/1/11/5/');
	define('LOCATIONPATH_LOIREATLANTIQUE','/1/1/3/1/');
	define('LOCATIONPATH_MAINEETLOIRE','/1/1/3/2/');
	define('LOCATIONPATH_MARTINIQUE','/1/1/11/6/');
	define('LOCATIONPATH_MAYOTTE','/1/1/11/7/');
	define('LOCATIONPATH_SAONEETLOIRE','/1/1/14/3/');
	define('LOCATIONPATH_SARTHE','/1/1/3/4/');
	define('LOCATIONPATH_SEINESTDENIS','/1/1/6/6/');
	define('LOCATIONPATH_TARN','/1/1/8/12/');
	define('LOCATIONPATH_VAR','/1/1/9/5/');

	define('LOCATIONPATH_VENDEE','/1/1/3/5/');

	define('LOCATIONPATH_COTEDOR','/1/1/14/1/');
	define('LOCATIONPATH_NIEVRE','/1/1/14/2/');
	define('LOCATIONPATH_YONNE','/1/1/14/4/');
	define('LOCATIONPATH_DOUBS','/1/1/14/5/');
	define('LOCATIONPATH_JURA','/1/1/14/6/');
	define('LOCATIONPATH_HAUTESAONE','/1/1/14/7/');
	define('LOCATIONPATH_TERRITOIREDEBELFORT','/1/1/14/8/');

	define('TRE_FORMACODEEXCEPTION','32047,13115,13263,94016,15235,98821,98808,21546,44590,44591,44595,44569,44002,43409');
	define('TRE_DOMAINEXCEPTION','150,152');

	define('USE_APILBB',true);

	define('PARAM_DEFRETOURALEMPLOI',"mesure la part des stagiaires inscrits à Pôle emploi qui, dans les 6 mois suivant la fin de chaque formation, ont retrouvé un emploi salarié de 1 mois et plus (hors particuliers employeurs, employeurs publics, employeurs à l’étranger et missions d’intérim à durée non renseignée), ou ont bénéficié d’un contrat aidé ou ont créé leur entreprise (Source : données Pôle emploi).");

	global $users_api;
	$users_api=array('USER'=>'pass');

	global $users_apifinancement;
	$users_apifinancement=array('USER'=>'pass');
?>
