<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/inc/contact.less')); ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
	<?php $asset->add('js',array('/js/inc/modal_contact.js')); ?>

<script>
	/* fonction qui génère puis envoie le mail à partir des informations du formulaire et qui génère un message de retour (OK/KO) */
	function envoyerMail(type) 
	{

		var adresse_expediteur=$("#contact-orga-adresse-exp-confirm").val();
		var objet=$("#contact-orga-objet-confirm").text();
		var message=$("#contact-orga-message-confirm").text();
		var copie=$("#contact-orga-copie-confirm").val();
		var url=$("#urlformation").val();

		var response=function(resp)
		{
			if (resp.substring(0,28)=="Le message a bien été envoyé") {
				$("#envoiok").show();
			}
			else {
				$("#envoiko").show();
			}
			$("#envoibody").text(resp);
			$("#confirmationhead").hide();
			$("#confirmationbody").hide();
			$("#envoibody").show();
		}

		track('CLIC MAIL CONTACT '+type);

		$.ajax({url:"<?php $this->rw('/inc/modal_contact.php',array('cmd'=>'mail')+$this->get());?>"+
			"&exp="+encodeURIComponent(adresse_expediteur)+
			"&obj="+encodeURIComponent(objet)+
			"&mes="+encodeURIComponent(message)+
			"&cop="+encodeURIComponent(copie)+
			"&type="+encodeURIComponent(type)+
			"&url="+encodeURIComponent(url),success:response});
			return false;
	}
	</script>
<?php _ENDBLOCK('script'); ?>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header nobr" id="contactmodalhead">

				<button type="button" data-dismiss="modal" class="close" aria-label="Close">
					<img src="/img/pictos/picto-croix.png" alt="fermer"/>
				</button><br>

				<div id="formulaireheadorga" hidden>
					<h2 class="modal-title">Contacter l'organisme de formation</h2>
				</div>
				<div id="formulaireheadlbf" hidden>
					<h2 class="modal-title">Contacter La Bonne Formation</h2>
				</div>

				<div id="confirmationhead" hidden>
					<h2 class="modal-title">Confirmation de l'envoi de mail</h2>
				</div>

				<div id="envoiok" hidden>
					<h2 class="modal-title">
						<span class="fa fa-check"></span>
						&nbsp;Envoi effectué
					</h2>
				</div>

				<div id="envoiko" hidden>
					<h2 class="modal-title">
						<span class="fa fa-exclamation-triangle"></span>
						&nbsp;Echec de l'envoi
					</h2>
				</div> 
			</div>
			<div class="modal-body form-group" id="contactmodalbody">

				<div id="formulairebody" hidden>
					<div class=" mail">
						<label for="contact-orga-adresse-exp-form"><strong>Votre email</strong></label><br>
						<input type="email" required class="form-control input" id="contact-orga-adresse-exp-form" name="contact-orga-adresse-exp-form" value=""/>
						<span id="alerteadresse" class="errormessage">Veuillez rentrer une adresse email valide.</span>
					</div>
					<div class="objet">
						<label for="contact-orga-objet-form"><strong>Objet</strong></label><br>
						<input id="contact-orga-objet-form" class="form-control input" name="contact-orga-objet" value=""/>
						<span id="alerteobjet" class="errormessage"  hidden>L'objet de votre email ne peut pas être vide</span>
					</div>
					<div class="message">
						<label for="contact-orga-message-form"><strong>Message au centre de formation</strong></label><br>
						<textarea id="contact-orga-message-form" class="form-control input" name="contact-orga-message" rows="10"></textarea>
						<span id="alertemessage" class="errormessage" hidden>Le message de votre email ne peut pas être vide.</span>
					</div>
					<div class="copie">
						<input type="checkbox" id="contact-orga-copie-form" name="contact-orga-copie-form" value="Oui" class="sr-only"/>
						<label for="contact-orga-copie-form" id="label-copie"><strong>Je souhaite recevoir une copie de cet email sur ma boîte email</strong></label>
					</div>
					<div class="modal-footer">
						<button id="boutonconfirmer" type="submit" class="btnoblig btn center-block">J'envoie</button>
					</div>
				</div>

				<div id="confirmationbody" hidden>
					<p>
						<span>À : </span><?php _H($email) ?><span id="contact-orga-adresse-confirm"></span>
					</p>
					<p>
						<span>Objet : </span><span id="contact-orga-objet-confirm"></span>
					</p>
					<p>
						<span id="contact-orga-message-confirm"></span>
					</p>
					<input type="hidden" id="contact-orga-adresse-exp-confirm" value=""/>
					<input type="hidden" id="contact-orga-copie-confirm" value=""/>
					<input type="hidden" id="urlformation" value="<?php _T($this->getURL()); ?>"/>
					<div class="modal-footer">
						<div class="row block-retour-ou-envoi">
							<div class="col-xs-12 col-sm-6 retour">
								<button type="button" class="btn secondaire" id="boutonretour">
									<span class="fa fa-chevron-left pull-left black"></span>
									<span id="libelleretour">Retour</span>
								</button>
							</div>
							<div class="col-xs-12 col-sm-6 envoi">
								<button type="button" class="btn" id="boutonenvoyer">Je confirme</button>
							</div>
						</div>
					</div>
				</div>

				<div id="envoibody" hidden>

				</div>
			</div>
		</div>
	</div>
</div>
