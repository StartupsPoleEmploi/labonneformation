<?php
	/*
	 * 23/02/2017: Complètement à refaire
	 * -> Extraire notamment la requete de complétion pour la mettre dans le modele
	 * -> Utiliser le champ label search de la table reference pour les alias
	 */
	require_once(CLASS_PATH.'/tools.php');

	function highlight($words,$keywords)
	{
		return Tools::text2Html($words,true,array('highlight'=>$keywords,'highlightstyle'=>array('font-weight:bold;')));
	}

	$result=array();

	$keywords=$this->get('q',''); //iconv("UTF-8","CP1252//TRANSLIT",$this->get('q',''));

	if($keywords!==null && (strlen($keywords)>3 || is_numeric($keywords)))
	{
		$listeRaw=array();
		$json=Tools::getUrlContent(URL_API_TREFLE.'/naf?'.http_build_query(array('q'=>$keywords)),null,2);
		$listeRaw=json_decode($json,true);

		if(!empty($listeRaw))
		{
			$result=array('key'=>$keywords,'list'=>array());
			foreach($listeRaw as $nafCode=>$data)
			{
				$label=sprintf('<div style="float:right;">%s</div>&nbsp;<span>%s</span>',highlight($nafCode,$keywords),highlight($data['name'],$keywords));
				$result['list'][]=array('label'=>$label,'value'=>$nafCode);
			}
		}
	}

	$this->header("Content-Type:","text/html; charset=utf-8");
	$this->header('Expires: '.date('r',time()+3600));
	$this->header('Pragma: Public');
	$this->header('Cache-Control: public, must-revalidate, proxy-revalidate');
	if(count($result)>0) echo json_encode($result);
	return;
?>
