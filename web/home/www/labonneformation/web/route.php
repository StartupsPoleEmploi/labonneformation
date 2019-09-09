<?php
	require_once('../sys/quark/quark.php');
	require_once('../config/config.php');
	require_once('../sys/param.php');

	$errorReporting=E_ALL & ~E_DEPRECATED & ~E_NOTICE;
	error_reporting($errorReporting);
	if(ENV_DEV===true)
	{
		error_reporting($errorReporting);
		ini_set('display_errors','On');
	}

	$quark=new Quark();
	/* Post traitement sur la balise title, pour effacer les eventuels retours chariots */
	_ONENDBLOCK('title',function($txt)
		{
			return trim(preg_replace('#[\r\n\t ]+#',' ',$txt));
		});

	$db=new QDb('mysqli',array('logslowqueries'=>1));
	//if($db->open('openvpn','lbfrecette',base64_decode('YUJjREFmS21FdHhD'),'labonneformation'))
	if($db->open($database['host'],$database['user'],$database['password'],$database['db']))
	{
		$debugDb=false;
		if($debugDb) $db->bufferizeRequest(true);

		$quark->store('cache',new QCache(3600*24));
		$quark->store('write',$db)->store('read',$db);
		$quark->asset('asset',array('combine'=>COMBINE_SCRIPT)); // configure les assets et leur réécriture d'url

		require_once(SYS_PATH.'/rewriterule.php');

		$quark->execute();

		if($debugDb)
		{
			$request=$db->getBufferRequest();
			if(!empty($request['buffer']))
			{
				_QUARKLOG('requetes.log',
					$quark->getURI()."\n".
					print_r($request,true)
				);
				print_r($request);
			}
		}
		$db->close();
	} else echo 'DB connexion error !';
?>