<div class="row section-footer">
	<div class="col-md-10 col-md-offset-1">
		<div class="row justify-content-start">
			<div class="col-md-3">
				<ul>
					<li><a href="<?php $this->rw('/conditions.php');?>" target="_blank">Conditions générales d'utilisation</a></li>
					<li><a href="<?php $this->rw('/accessibilite.php');?>" target="_blank">Accessibilité</a></li>
					<li><a href="<?php $this->rw('/stats.php');?>" target="_blank">Statistiques</a></li>
				</ul>
			</div>
			<div class="col-md-3">
				<ul>
					<li><a href="<?php $this->rw('/faq.php');?>" target="_blank">FAQ</a></li>
					<!--<li><button data-toggle="modal" data-target="#contactModal" onclick="javascript:afficherFormulaireMail('',<?php //_JS(EMAIL_FROM,'SIMPLE_QUOTE') ?>,'Je souhaite vous contacter','','','home','lbf');">Contactez notre équipe</button></li>-->
					<?php if(CONTACT_MAIL): ?><li><a href="mailto:<?php _M(EMAIL_CONTACT,array('subject'=>'Je souhaite vous contacter', 'body'=>"\nCe message sera transmis sur la boîte de La Bonne Formation, vous pouvez rédiger votre question ou nous signaler une anomalie.\n\nEnvoyé depuis la page ".$this->getUrl()));?>" target="_blank">Contactez notre équipe</a></li><?php endif ?>
					<li><a href="<?php $this->rw('/orga.php');?>">Annuaire des organismes</a></li>
				</ul>
			</div>
			<div class="col-md-3">
				<ul>
					<li>
						<a id="apis_esd" data-toggle="collapse" data-target="#apis-target"><span id="label_apis_esd">Découvrez nos API</span><span id="indent_apis_esd">&nbsp;&nbsp;</span><span class="fa fa-chevron-down"></span></a>
						<ul id="apis-target" class="collapse">
							<li class="liens_apis_esd"><a href="https://pole-emploi.io/data/api/retour-emploi-suite-formation" target="_blank">Taux de retour à l'emploi</a></li>
							<li class="liens_apis_esd"><a href="https://pole-emploi.io/data/api/anotea" target="_blank">Anotea</a></li>
							<li class="liens_apis_esd"><a href="https://pole-emploi.io/data/api/simulateur-financement" target="_blank">Simulateur de financement</a></li>
						</ul>
					</li>
					<li><a href="https://git.beta.pole-emploi.fr/open-source/labonneformation">Code source ouvert</a></li>
				</ul>
			</div>
			<div class="col-md-3 pull-right">
				<ul>
					<li><img src="/img/logoPE-mono.png" class="logo" alt="logo pole-emploi"/></li>
				</ul>				
			</div>
		</div>
	</div>
</div>
