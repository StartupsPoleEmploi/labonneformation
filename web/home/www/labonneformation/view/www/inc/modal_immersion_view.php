<!-- Modal Immersion -->
<div class="modal fade" id="info-immersion" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Comment contacter cette entreprise pour une immersion ?</h4>
			</div>
			<div class="modal-body">
				<p><b>Privilégiez le contact direct</b> par téléphone ou en vous rendant sur place pour un commerçant ou artisan. L'échange sera plus simple.</p>
				<p><b>Présentez-vous et présentez votre projet</b>. Expliquez vos souhaits de vous former et de vérifier avant, auprès d'un professionnel, que le métier vous convient.</p>
				<p><b>Expliquez en quelques mots simples ce qu'est une immersion en entreprise</b> : c'est un stage de découverte de quelques jours avec une convention signée par Pôle emploi. Vous conservez votre statut de demandeur d’emploi et vous êtes couvert(e) par Pôle emploi.</p>
				<p class="text-center"><b>Prêt(e) à les contacter ?</b></p>

				<p class="text-center"><i class="fa fa-phone"></i>&nbsp;&nbsp;<span id="nophone">Non disponible</span><a href="tel:<?php _T(preg_replace('/\s+/', '',$entreprise['telephonecorrespondant']));?>" id="phone" onclick="track('IMMERSION CLIC PHONE');"></a><br/>
				<i class="fa fa-envelope"></i>&nbsp;&nbsp;<span id="noemail">Non disponible</span><a href="mailto:<?php _T($entreprise['email']);?>" id="email" style="overflow:hidden;text-overflow:ellipsis;overflow-wrap:break-word;max-width: 100%;" onclick="track('IMMERSION CLIC EMAIL');"></a><br/></p>

				<p></p>
				<p class="text-center"><b>Vous avez besoin de plus d'informations ?<br/><a href="/pdf/conseils-immersion-contact-entreprise.pdf" target="_blank" onclick="track('IMMERSION CLIC GOOGLE FORM);">Téléchargez nos conseils</a></b></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>
<!-- Fin modal Immersion -->
