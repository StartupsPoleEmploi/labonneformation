<?php if($nomOrganisme): ?>
	<h2><?php _H($nomOrganisme);?></h2>
<?php endif ?>
<div class="row">
	<div class="col-md-<?php _H($landscape?'4':'12');?>">
		<div class="info-contact">
			<table>
				<?php if($telephone || $mobile): ?>
					<tr>
						<td class="first"><span class="fa fa-phone">&nbsp;</span></td>
						<td>
							<?php if($telephone):?><div><a href="tel:<?php _H(strtr($telephone,[' '=>'','-'=>'','.'=>'']));?>"><?php _H($telephone);?></a></div><?php endif ?> 
							<?php if($mobile):?><div><a href="tel:<?php _H(strtr($mobile,[' '=>'','-'=>'','.'=>'']));?>"><?php _H($mobile);?></a></div><?php endif ?> 
						</td>
					</tr>
				<?php endif ?>
				<?php if($fax): ?>
					<tr>
						<td class="first"><span class="fa fa-fax">&nbsp;</span></td>
						<td>
							<?php if($fax):?><div><?php _H($fax);?></div><?php endif ?> 
						</td>
					</tr>
				<?php endif ?>
				<?php if($email || $url): ?>
					<tr>
						<td class="first"><span class="fa fa-at">&nbsp;</span></td>
						<td>
							<?php if($email):?><div><a href="mailto:<?php _M($email,array('subject'=>$sujetMail));?>" onclick="track('CLIC MAIL CONTACT ORGANISME');"><?php _H($email);?></a></div><?php endif ?> 
							<?php if($url):?><div><a href="<?php _H($url);?>" target="_blank"><?php _H($url);?></a></div><?php endif ?> 
							<?php //displayItem($orgaContent,'Contact','[display=contact]',null,array('tel'=>':fa fa-phone: ','fax'=>':fa fa-fax: ','mobile'=>':fa fa-phone: ','email'=>':fa fa-envelope: ','url'=>':fa fa-internet-explorer: ')); ?>
						</td>
					</tr>
				<?php endif ?>
			</table>
		</div>

		<?php if($email): ?>
			<a class="btn secondaire contacter" data-toggle="modal" data-target="#contactModal" onclick="javascript:afficherFormulaireMail('',<?php _JS($sujetMail,'SIMPLE_QUOTE') ?>,<?php _JS($messageDefault,'SIMPLE_QUOTE') ?>,'','orga','orga');">Contacter l'organisme</a>
		<?php endif ?>

		<?php if($adresseFormation):?>
			<div class="panel-primary map">
				<h3>Lieu de formation</h3>
				<p><?php _HR($adresseFormation); ?></p>

				<?php if(!$aDistance):?>
					<?php _BEGINBLOCK('script'); ?>
						<script>
							$(document).ready(function() {
								var info=<?php _JS(str_replace("\n",'\n',trim($adresseFormation)).'\n'); ?>;
								showMap("carte",<?php _H($lat);?>,<?php _H($lng);?>,info);
							});
						</script>
					<?php _ENDBLOCK('script'); ?>

					<p class="google-itineraire">
						<script>var info=<?php _JS(str_replace("\n",'\n',trim($adresseFormation)).'\n'); ?>;</script>
						<a href="#" onclick="window.open('https://www.google.fr/maps/dir//'+info.replace(/\bBP \d+/i,'').replace(/\n/g,' '));">Cliquez sur ce lien pour calculer votre itinéraire</a>
					</p>
					<div id="carte" class="panel panel-primary"></div>
				<?php endif ?>
			</div>
		<?php endif ?>

		<?php if($adresseOrganisme): ?>
			<h3>Centre de formation</h3>
			<p><?php _HR($adresseOrganisme); ?></p>

		<?php endif ?>
			<p class="toutes-les-formations">
				<strong><a href="<?php $this->rw('/result.php',array('criteria'=>array('orgaid'=>$orgaId)));?>">Voir toutes les formations de <?php _H($nomOrganisme);?></a></strong>
			</p>

		<?php if($adresseCentre): ?>
			<h3>Centre de formation</h3>
			<p><?php _HR($adresseCentre); ?></p>

			<?php _BEGINBLOCK('script'); ?>
				<script>
					$(document).ready(function() {
						var info=<?php _JS(str_replace("\n",'\n',trim($adresseCentre)).'\n'); ?>;
						showMap("carte-centre",<?php _H($lat);?>,<?php _H($lng);?>,info);
					});
				</script>
			<?php _ENDBLOCK('script'); ?>

			<?php if(!$landscape):?>
				<p class="google-itineraire">
					<script>var info=<?php _JS(str_replace("\n",'\n',trim($adresseCentre)).'\n'); ?>;</script>
					<a target="_blank" href="javascript:window.open('https://www.google.fr/maps/dir//'+info.replace(/\bBP \d+/i,'').replace(/\n/g,' '));">Cliquez sur ce lien pour calculer votre itinéraire</a>
				</p>
				<div id="carte-centre" class="panel panel-primary"></div>
			<?php endif ?>
		<?php endif ?>
	</div>
	<?php if($landscape && $adresseCentre): ?>
		<div class="col-md-8">
			<p class="google-itineraire">
				<script>var info=<?php _JS(str_replace("\n",'\n',trim($adresseCentre)).'\n'); ?>;</script>
				<a target="_blank" href="javascript:window.open('https://www.google.fr/maps/dir//'+info.replace(/\bBP \d+/i,'').replace(/\n/g,' '));">Cliquez sur ce lien pour calculer votre itinéraire</a>
			</p>
			<div id="carte-centre" class="panel panel-primary" style="height:24em;"></div>
		</div>
	<?php endif ?>
</div>
