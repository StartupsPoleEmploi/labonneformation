<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title><?php if(!_DISPLAYBLOCK('title')):?><?php _H($page=='index'?LBF_TITLE.' | ':'');?>Trouvez votre formation professionnelle près de chez vous<?php endif ?><?php _H($page!='index'?' | '.LBF_TITLE:'');?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="description" content="<?php _H(Tools::cut(trim(preg_replace('# +#',' ',preg_replace('#( |\r|\n|\t)#si',' ',_GETBLOCK('description')))),300));?>"/>
		<?php if(isset($noRobots) && $noRobots):?><meta name="robots" content="noindex, follow"><?php endif ?>
		<link rel="icon" type="image/png" href="/img/favicon.png"/>
		<?php if(!_DISPLAYBLOCK('meta')): ?><?php endif ?>
		<?php _T($asset->combine('css',array('/css/bootstrap/css/bootstrap.css','/css/bootstrap/css/font-awesome.min.css','https://fonts.googleapis.com/css?family=Dosis:400,600,700|Open+Sans:300,400,600,700','/css/mapbox-gl.css','/css/base.less'))); ?>
		<?php _T($asset->combine('css',$asset->get('css'))); ?>
		<?php if(!_DISPLAYBLOCK('css')): ?><?php endif ?>
	</head>
	<body data-spy="scroll" data-target="#avis" data-offset="50">
		<div class="container-fluid full-height">
			<div class="section-all la-bonne-formation full-height">
				<div class="row full-height">
					<div class="col-md-12 full-height">
						<?php require_once('inc/header_view.php'); ?>
						<div class="row section-body">
							<div class="col-md-10 col-md-offset-1">
								<?php _T(_GETBLOCK('followlink')); ?> 
								<?php _T(_GETBLOCK('content')); ?> 
							</div>
						</div>
						<div class="row section-powered-by">
							<div class="col-md-10 col-md-offset-1">
								<?php if(!_DISPLAYBLOCK('sponsor')): ?><?php endif ?>
							</div>
						</div>
						<?php require_once('inc/footer_view.php'); ?>
					</div>
				</div>
			</div>
		</div>
		<script>
			var tagSEO=<?php _JS(ENV_NAME=='prod'?TAG_SEO_PROD:TAG_SEO_RECETTE); ?>;
			var idAnalytics=<?php _JS(ENV_NAME=='prod'?TAG_ANALYTICS_PROD:TAG_ANALYTICS_RECETTE); ?>;
			var idOptimize=<?php _JS(ENV_NAME=='prod'?TAG_OPTIMIZE_PROD:TAG_OPTIMIZE_RECETTE); ?>;
			var idHotjar=<?php _H(ENV_NAME=='prod'?TAG_HOTJAR_PROD:TAG_HOTJAR_RECETTE);?>;
			var idCrisp=<?php _JS(ENV_NAME=='prod'?TAG_CRISP_PROD:TAG_CRISP_RECETTE);?>;
			var tarteaucitronForceCDN='/js/tarteaucitron/';
			var tarteaucitronCustomText={"alertBigPrivacy": 'Les cookies assurent le bon fonctionnement de nos services. En utilisant ces derniers, vous acceptez l\'utilisation des cookies. <a href="/conditions-generales-d-utilisation#cookies" target="_blank" title="nouvelle fenetre">En savoir plus</a>.'};
			var _nocrisp=true;
		</script>
		<?php _T($asset->combine('js',array('/js/tarteaucitron/tarteaucitron.js','/js/jquery/jquery.min.js','/js/bootstrap/bootstrap.min.js','/js/jquery/jquery.plugin.complete.js','/js/jquery/jquery.plugin.infocookie.js','/js/mapbox-gl.js','/js/base.js','/js/tags.js'))); ?>
		<?php _T($asset->combine('js',$asset->get('js'))); ?>
		<?php if(ENV_NAME=='prod'): ?><script>initConnect({uri:<?php _JS($this->rewrite('/peconnect',array('uri'=>$this->getURI())));?>});</script><?php endif ?>
		<?php if(!_DISPLAYBLOCK('script')):?><?php endif ?>
	</body>
</html>
