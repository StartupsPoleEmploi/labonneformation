<?php
	function displayCondLink($label,$link=false)
	{
		if($link) echo sprintf('<a data-type="OFFRE" href="%s" target="_blank" rel="nofollow">%s</a>',$link,_H($label,true));
		else _H($label);
	}
	$romes=$this->get('rome',array());
	if($romes && !is_array($romes)) $romes=explode(' ',$romes);
	$locationPath=$this->get('locationpath','');
	$alternance=$this->get('alternance','');
	$alternances=array('apprentissage'=>'APPRENTISSAGE',
		'professionnalisation'=>'PROFESSIONNALISATION');
	if (array_key_exists($alternance,$alternances)) {
		$alternance=$alternances[$alternance];
	} else {
		$alternance='';
	}
	$tab=array();
	$totalNbOffers=0;

	$db=$this->getStore('read');
	/* Récupe un tableau du nb d'annonce PE par romes dans le lieu donné */
	if(!empty($romes) && !empty($locationPath))
	{
		if($offers=Tools::getPeOffers($db,$romes,$locationPath,$alternance))
		{
			$totalNbOffers=$offers['total'];
			$tab=$offers['offers'];
			if(!$totalNbOffers) $tab=array(); /* On affiche pas la liste si aucune offre */
		}
		if($offersHorsAlternance=Tools::getPeOffers($db,$romes,$locationPath,''))
		{
			$totalNbOffersHorsAlternance=$offersHorsAlternance['total'];
		}
	}

	$depNum=0;
	if($offers['loclabel'])
	{
		$dep=$offers['loclabel'];
		$depNum=$offers['dep'];
	}

	$apiLbb=$companies=array();
	$searchLocation='France';
	if(USE_APILBB!==false) /* Dans params.php: supprime ou non l'utilisation de l'API LBB */
		if(!empty($romes) && $romes[0])
			if($apiLbb=Tools::apiGetLbb($db,$romes[0],$locationPath,($alternance!=''?true:false)))
			{
				$lbbUrlList=$apiLbb['companies_url'];
				$companies=$apiLbb['companies'];
				if(!$dep) //Optimisation: retrouve le département si il n'a pas été trouvé avant
				{
					$ref=new Reference($db);
					if($loc=$ref->get('LOCATION',$locationPath))
					{
						$depNum=substr($loc[$locationPath]['zipcode'],0,2);
						$dep=sprintf('%s (%s)',$loc[$locationPath]['label'],$depNum);
					}
				}
				$searchLocation=$apiLbb['search_location'];
			}
?>
<div id="ajaxoffers">
	<div class="pole-emploi">
		<?php if(!$totalNbOffers): ?>
			Attention aucun employeur ne propose aujourd’hui d’offres d’emploi <?php _H($alternance!='' ? 'en alternance ' : '') ?>sur pole-emploi.fr en lien avec cette formation<?php if($dep):?> autour de &laquo;&nbsp;<?php _H($dep);?>&nbsp;&raquo;<?php endif ?>

			<?php if($alternance!=''): ?>
			<h3 style="margin-top:20px;">
				<?php if($totalNbOffersHorsAlternance==1): ?>
					<?php displayCondLink($totalNbOffersHorsAlternance.' offre d\'emploi hors alternance');?>
				<?php else: ?>
					<?php displayCondLink($totalNbOffersHorsAlternance.' offres d\'emploi hors alternance');?>
				<?php endif ?>
				<span class="minus">en lien avec cette formation autour de &laquo;&nbsp;<?php _H($dep);?>&nbsp;&raquo;</span>
			</h3>
			<?php endif ?>
		<?php else: ?>
			<h3>
				<?php if($totalNbOffers==1): ?>
					<?php displayCondLink($totalNbOffers.' offre d\'emploi');?>
				<?php else: ?>
					<?php displayCondLink($totalNbOffers.' offres d\'emploi');?>
				<?php endif ?>
				<span class="minus">en lien avec cette formation autour de &laquo;&nbsp;<?php _H($dep);?>&nbsp;&raquo;</span>
			</h3>
		<?php endif ?>

		<?php if(count($tab)>=1): ?>
			<?php foreach($tab as $rm=>$stats): ?>
				<div class="metier-voir">
					<a class="metier" data-type="METIER" href="<?php _T(Tools::getMetierLink($rm,$depNum));?>" target="_blank" rel="nofollow"><?php _H($stats['label']);?></a>
					<a class="btn secondaire voir-les" data-type="OFFRE" href="<?php _H($stats['link']);?>" target="_blank" rel="nofollow"><?php _H($stats['nb']);?> offre<?php _H($stats['nb']>1?'s':'');?></a>
				</div>
			<?php endforeach ?>
		<?php endif ?>
	</div>
	
	<div class="la-bonne-boite">
		<?php if(!empty($companies)): ?>
			<h3>
				<a href="<?php _T($lbbUrlList);?>" target="_blank"><strong><?php _T($apiLbb['companies_count']);?> employeur<?php _H((int)$apiLbb['companies_count'] > 1 ? 's' : '') ?></strong></a>
				<span class="minus">
				<?php _H((int)$apiLbb['companies_count'] > 1 ? 'sont susceptibles' : 'est susceptible'); ?> de recruter <?php _H($alternance!='' ? 'en alternance ' : '') ?>après cette formation, dont :
				</span>
			</h3>
			<div>
				<?php $i=1; foreach($companies as $comp): if($i++>3) break; ?>
					<?php _T($i>2?', ':'');?><a data-type="EMPLOYEUR" href="<?php _T($comp['url']);?>" target="_blank"><?php _H($comp['name']);?></a>
				<?php endforeach ?>
			</div>
			<div class="voir">
				<a class="btn secondaire" data-type="EMPLOYEUR" href="<?php _T($lbbUrlList);?>" target="_blank">Voir</a>
			</div>
		<?php else: ?>
			<h3>
				Il n'y a à priori aucune entreprise susceptible d'embaucher dans un rayon de 10 km.<br/>
				Elargir votre recherche avec <a href="https://labonneboite.pole-emploi.fr" target="_blank">La Bonne Boite</a>
			</h3>
		<?php endif ?>
	</div>
</div>
