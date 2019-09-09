<?php
	$db=$this->getStore('read');

	$firstLetter=$this->get('firstletter');
	$page=(int)$this->get('page', 1);

	$firstLetterIsNumeric=preg_match('#^[0-9]#',$firstLetter) || is_null($firstLetter)?true:false;

	if(!is_numeric($page) || ($firstLetter!=null && $firstLetterIsNumeric))
	{
		$this->controller('/404.php');
		return;
	} else
	{
		$page--;
		$orga=new Orga($db);
		$pageSize=Orga::PAGE_SIZE;

		$toFind="$firstLetter*";
		$page=$page<0?0:$page;
		$options=array('offset'=>$page*$pageSize,'limit'=>$pageSize);
		if($firstLetterIsNumeric)
		{
			$toFind='^[0-9]';
			$options+=array('regexp'=>true);
		}

		$list=$orga->getList($toFind,$options);
		$pageCount=ceil($orga->getCount($toFind,$options)/$pageSize);
		//echo $pageSize.';'.$orga->getCount($toFind,$options);

		/* Si débordement de page forcé en url */
		if($page<0 || $page>=$pageCount)
		{
			$this->controller('/404.php');
			return;
		}


		//$list=$orga->getList($firstLetter, $page);
		//$pageCount=$orga->getPageCount($firstLetter);
		/* Si quelqu'un force un lien de pagination incoherent, on le dirige en 404 */
		if($page<0 || $page>=$pageCount)
		{
			$this->controller('/404.php');
			return;
		}
		$page++;

		$startingPage=1;
		$endingPage=$pageCount;
		$previousPage=false;
		$nextPage=false;
		if($pageCount>10)
		{
			if($page <= 5)
			{
				$endingPage=10;
				$nextPage=true;
			} else if($page > 5)
			{
				$previousPage=true;
				$startingPage=$page-4;
				$endingPage=$page+5>$pageCount?$pageCount:$page+5;
				$nextPage=$endingPage<$pageCount-1;
			}
		}

		$this->view('/orga_view.php',
			array(
				'currentLetter'=>$firstLetter,
				'currentPage'=>$page,
				'list'=>$list,
				'startingPage'=>$startingPage,
				'endingPage'=>$endingPage,
				'previousPage'=>$previousPage,
				'nextPage'=>$nextPage,
				'page'=>$page,
				'firstLetter'=>$firstLetter)
			);
	}
?>