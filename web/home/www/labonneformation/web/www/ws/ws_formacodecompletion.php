<?php
	require_once(CLASS_PATH.'/tools.php');

	$db=$this->getStore('read');
	$result=array();

	$keywords=$this->get('q','');
	if(!empty($keywords))
	{
		$ref=new Reference($db);
		$list=$ref->getRomeAppellationCompletion($keywords,array('limit'=>10));

		$result=array('key'=>$keywords,'list'=>array());
		foreach($list as $line)
		{
			$lb=ucfirst(strtolower($line['romelabel']));
			if($line['romepath']!=$line['appellationpath']) $lb.=sprintf(" (%s, ...)",$line['appellationlabel']);
			$highlight=Tools::text2Html($lb,true,array('highlight'=>$keywords,'highlightstyle'=>array('font-weight:bold;')));
			$label=sprintf('<span>%s</span>',$highlight);
			$result['list'][]=array(
				'label'=>$label,
				'value'=>array(
					'label'=>$lb,
					'code'=>$line['romepath']/*Reference::extraData('rm',$row['extradata'])*/,
					'rome'=>$line['romecode'],
					'cnt'=>$line['cnt']
				)
			);
		}
	}
	$this->header("Content-Type:","text/html; charset=utf-8");
	$this->header('Expires: '.date('r',time()+3600));
	$this->header('Pragma: Public');
	$this->header('Cache-Control: public, must-revalidate, proxy-revalidate');
	if(count($result)>0) echo json_encode($result);
?>