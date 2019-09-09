<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Widget</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>
		<style>
			a {
				color:black;
			}
			.rating {
				display:inline-block;
				overflow:hidden;
				text-decoration:none!important;
				background-image:url("/img/rating.png");
				background-repeat:no-repeat;
				width:90px;
				height:16px;
				vertical-align: middle;
				line-height:19.5px;
				-border:1px solid red;
			}
			.rating.rate-00 {
				background-position:-90px -42px;
			}
			.rating.rate-05 {
				background-position:-72px -25px;
			}
			.rating.rate-10 {
				background-position:-72px -42px;
			}
			.rating.rate-15 {
				background-position:-54px -25px;
			}
			.rating.rate-20 {
				background-position:-54px -42px;
			}
			.rating.rate-25 {
				background-position:-36px -25px;
			}
			.rating.rate-30 {
				background-position:-36px -42px;
			}
			.rating.rate-35 {
				background-position:-18px -25px;
			}
			.rating.rate-40 {
				background-position:-18px -42px;
			}
			.rating.rate-45 {
				background-position:0 -25px;
			}
			.rating.rate-50 {
				background-position:0 -42px;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<?php $orgaContent=new QContentParser();?>
				<?php if($orient!=2): ?>
					<div class="col-md-12">
						<?php foreach($adList as $line): $orgaContent->parse($line['orgacontent']);?>
							<p>
								<a href="<?php _T(URL_BASE.$this->rw('/detail.php',array('ad'=>$line),true));?>" target="_blank"><?php _H($line['title']);?></a><br/>
								<span style="font-size:.8em;">
									<span class="fa fa-building-o"></span>&nbsp;<a href="<?php $this->rw('/result.php',array('criteria'=>array('orgaid'=>$line['orgaid'])));?>" target="_blank"><strong class="ad-text-info"><?php _H(Tools::cut($orgaContent->get('organame','')->inner(),300));?></strong></a>
									<span class="fa fa-map-marker"></span>&nbsp;<span class="ad-text-info"><strong><?php _H($line['locationlabel']);?></strong> (<?php _H($line['locationparentlabel']);?>)<?php if($line['dist']>0.1):?> - <strong><?php _H(sprintf('%.1f',$line['dist']));?> km</strong><?php endif ?></span>
									<span class="rating rate-<?php $tx=$line['tx']; _T(sprintf('%02ld',intval($tx/100*50)));?>" data-tx="<?php _T($line['tx']>0?Tools::rateTx($line['tx']):'');?>"></span>
								</span>
							</p>
						<?php endforeach ?>
					</div>
				<?php else: ?>
					<?php foreach($adList as $line): $orgaContent->parse($line['orgacontent']);?>
						<div class="col-xs-4">
							<p>
								<a href="<?php _T(URL_BASE.$this->rw('/detail.php',array('ad'=>$line),true));?>" target="_blank"><?php _H($line['title']);?></a><br/>
								<span style="font-size:.8em;">
									<p><span class="fa fa-building-o"></span>&nbsp;<a href="<?php $this->rw('/result.php',array('criteria'=>array('orgaid'=>$line['orgaid'])));?>" target="_blank"><strong class="ad-text-info"><?php _H(Tools::cut($orgaContent->get('organame','')->inner(),300));?></strong></a></p>
									<p><span class="fa fa-map-marker"></span>&nbsp;<span class="ad-text-info"><strong><?php _H($line['locationlabel']);?></strong> (<?php _H($line['locationparentlabel']);?>)<?php if($line['dist']>0.1):?> - <strong><?php _H(sprintf('%.1f',$line['dist']));?> km</strong><?php endif ?></span></p>
									<p><span class="rating rate-<?php $tx=$line['tx']; _T(sprintf('%02ld',intval($tx/100*50)));?>" data-tx="<?php _T($line['tx']>0?Tools::rateTx($line['tx']):'');?>"></span></p>
								</span>
							</p>
						</div>
					<?php endforeach ?>
				<?php endif ?>
			</div>
		</div>
	</body>
</html>