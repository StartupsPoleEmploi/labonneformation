<div class="row section-header<?php _T($engine?'':' minify');?>">
	<?php if(isset($logoBeta) && $logoBeta):?><div class="beta">Bêta</div><?php endif ?>
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="col-md-4">
				<div class="logo"><a href="/" title="Retour à l'accueil"><img class="logo" src="/img/labonneformation-pole-emploi.png" alt="logo"></a></div>
			</div>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<div class="btn-reduire pull-right">
							<img src="/img/pictos/picto-reduire-header.png" class="reduire" alt="Ferme la recherche"/>
						</div>
						<div class="btn-recherche pull-right" style="<?php _T($engine?'display: none;':'');?>">
							<span class="fa fa-search icon-recherche"></span><br/>
							<span class="text-recherche hidden-xs">Modifier&nbsp;ma&nbsp;recherche</span>
						</div>
					</div>
				</div>
				<div class="row section-engine">
					<div class="col-md-12">
						<?php require_once('engine_view.php'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
