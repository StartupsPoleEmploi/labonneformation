<?php
	function addLocation($domtree, $urlset, $urlLink, $lastMod=null, $changeFreq=null)
	{
		$url = $domtree->createElement('url');
		
		$loc = $domtree->createElement('loc', $urlLink);
		$url->appendChild($loc);
		if(!is_null($lastMod)) {
			$node = $domtree->createElement('lastmod', date('Y-m-d',$lastMod));
			$url->appendChild($node);
		}
		if(!is_null($changeFreq)) {
			$node = $domtree->createElement('changefreq', $changeFreq);
			$url->appendChild($node);
		}

		$urlset->appendChild($url);
	}

	//$this->header('Content-Type','application/octet-stream');
	$this->header('Content-Type','application/xml');
	$db=$this->getStore('read');

	$cache=new QCache(3600*6);

	$key='sitemap.php';
	if(($xml=$cache->get($key))===false)
	{
		$orga=new Orga($db);
		$adSearch=new AdSearch($db);

		$domtree = new DOMDocument('1.0', 'UTF-8');
		$domtree->formatOutput=true;

		$urlset = $domtree->createElement('urlset');
		$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		$domtree->appendChild($urlset);

		addLocation($domtree, $urlset, URL_BASE.$this->rw('/index.php', array(), true));
		addLocation($domtree, $urlset, URL_BASE.$this->rw('/faq.php', array(), true));
		addLocation($domtree, $urlset, URL_BASE.$this->rw('/conditions.php', array(), true));
		addLocation($domtree, $urlset, URL_BASE.$this->rw('/orga.php', array(), true));

		for($i=0;$i<26;$i++)
			addLocation($domtree, $urlset, URL_BASE.$this->rw('/orga.php', array('firstletter'=>chr(65+$i)), true), null, 'daily');

		// WARNING: if URL rewritting make some SQL request in the future, this will crash
		//$db->prepare("SELECT id FROM orga o WHERE o.status='ACTIVE' LIMIT 15000");
		if($rows=$orga->getList('*',array('limit'=>15000)))
			foreach($rows as $row)
			{
				$url=URL_BASE.$this->rw('/result.php',array('criteria'=>array('orgaid'=>$row['id'])), true);
				addLocation($domtree, $urlset, $url);
			}

		// WARNING: if URL rewritting make some SQL request in the future, this will crash
		if(0) //Desactivé suite à la nouvelle stratégie SEO on n'indexe plus les pages detail.php
			if($rows=$adSearch->getSiteMapList(30000))
				foreach($rows as $row)
				{
					$url=URL_BASE.$this->rw('/detail.php',array('ad'=>array('firsttitle'=>$row['title'],'id'=>$row['id'])), true);
					$lastMod=$row['lastmod'];

					addLocation($domtree, $urlset, $url, $lastMod, 'daily');
				}

		//$domtree->save('compress.zlib://'.$this->controllerRoot.'/sitemap.xml.gz');
		$xml=$domtree->saveXML();
		$cache->set($key,$xml);
	}
	echo $xml;
?>