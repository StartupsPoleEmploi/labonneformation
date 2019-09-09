<?php _BEGINBLOCK('title'); ?>Financement de la formation "<?php _H($ar['intitule']);?>"<?php _ENDBLOCK('title'); ?>
<?php _BEGINBLOCK('description'); ?><?php _ENDBLOCK('description'); ?>
<?php _BEGINBLOCK('meta'); ?><meta name="robots" content="noindex"/><?php _ENDBLOCK('meta'); ?>

<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/simulatorform.less','/css/simulatorresult.less')); ?>
	<style>
		#msgmail {
			display:none;
			line-height:38px;
			vertical-align:middle;
		}
		<?php if($engage): ?>
			.droits .line .explication {
				display:block;
			}
		<?php endif ?>
	</style>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
	<script>
		function demarchePrint()
		{
			track('ENGAGEZ - CLICK IMPRESSION');
			window.open("<?php $this->rw('/simulatorresult.php',array('cmd'=>'print')+$this->get());?>","_blank");
		}
		function stateMail()
		{
			$('.send-mail').fadeIn();
			$('#state-mail').hide();
		}
		function sendMail()
		{
			var response=function(resp)
			{
				$(".send-mail").hide();
				$("#mail").val("");
				$("#msgmail").html(resp).fadeIn();
				setTimeout(function() {
					$("#msgmail").fadeOut(400,function() {
						$(".send-mail").fadeIn();
					});
				},2000);
			}
			track('ENGAGEZ - CLICK ENVOI MAIL');
			var mail=$("#mail").val();
			$.ajax({url:"<?php $this->rw('/simulatorresult.php',array('cmd'=>'mail')+$this->get());?>&mail="+encodeURIComponent(mail),success:response});
			return false;
		}
		$(document).ready(function()
		{
			var listResultLink=sessionStorage.getItem("listresultlink");
			if(typeof listResultLink!=="undefined" && listResultLink!==null && listResultLink!="") {
				$("#recherche-elargir-txt").addClass('hide');
				$("#retour-liste-resultat").parent().removeClass('hide');
				$("#retour-liste-resultat").attr('href',listResultLink);
			}
			<?php if(!$engage): /* Pas de Slideup/slideDown si on est sur la page engage */ ?>
				$(".line").click(function()
					{
						var desc=$(this).find(".explication");
						var type=$(this).data("type");

						$(".more-explication").slideUp();
						/* Ouvre si c'est fermé */
						if(desc.is(':hidden'))
						{
							track('DEMARCHES - CLICK '+type);
							desc.slideDown('slow');
							$(".explication").not(desc).slideUp('slow');
						} else
						/* Ferme si c'est ouvert */
						{
							desc.slideUp('slow');
						}
					});
			<?php endif ?>
			//$("a").click(function() {
			//	var t=this;
			//	$('html, body').animate({"scrollTop":$($.attr(t,"href")).offset().top},1000);
			//	return false;
			//});
			$(".pasdroits .line").click(function()
				{
					var desc=$(this).find(".explication-inactif");
					var type=$(this).data("type");

					/* Ouvre si c'est fermé */
					if(desc.is(':hidden'))
					{
						track('DEMARCHES ROUGE - CLICK '+type);
						desc.slideDown('slow');
						$(".explication-inactif").not(desc).slideUp('slow');
					} else
					/* Ferme si c'est ouvert */
					{
						desc.slideUp('slow');
					}
				});
			$(".explication span").click(function()
				{
					track('ENGAGEZ EN SAVOIR PLUS - CLICK '+$(this).data("type"));
					$(this).parent().parent().next(".more-explication:first").slideToggle();
					return false;
				});
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('followlink'); ?>
	<?php if($backLink || $listLink): ?>
		<div class="row section-ariane">
			<?php if($backLinkResult) :?>
				<div class="col-md-6 text-left"><?php if($backLinkResult):?><a href="<?php _T($backLinkResult);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> Retour à la liste des financements</a><?php endif ?></div>
			<?php else: ?>
				<div class="col-md-6 text-left"><?php if($backLink):?><a href="<?php _T($backLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> Retour au formulaire de financement de la formation</a><?php endif ?></div>
			<?php endif ?>
			<?php if($detailLink) :?>
				<div class="col-md-6 text-right"><?php if($detailLink):?><a href="<?php _T($detailLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> Retour au détail de la formation</a><?php endif ?></div>
			<?php else: ?>
				<div class="col-md-6 text-right"><?php if($listLink):?><a href="<?php _T($listLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> Retour à la liste des formations</a><?php endif ?></div>
			<?php endif ?>
		</div>
	<?php endif ?>
<?php _ENDBLOCK('followlink'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row title">
		<?php if(isset($explainLink) && (ENV_DEV || $this->getCookie('mode-expert'))): ?>
			<div class="col-md-12 text-right">
				<a class="btn" rel="nofollow" target='_blank' href="<?php _T($explainLink['trefleLink'].URL_LBF.$explainLink['simulLink']) ?>">Simuler le financement sur trèfle</a>
			</div>
		<?php endif ?>
		<div class="col-md-12">
			<br/>
			<div class="block-expand">
				<div class="block-expand-visible">
					<a href="<?php _T($detailLink);?>"><h1><?php _H($ar['intitule']);?></h1></a>
				</div>
				<div class="block-expand-hidden">
					<div class="orga">ORGANISME <strong><?php _H($ar['organisme/nom']);?></strong></div>
					<?php if(is_array($infoContact) && !empty($infoContact)): ?>
						<?php foreach($infoContact as $class=>$contact): ?>
							<div><span class="fa <?php _T($class);?>"></span> <?php _T($contact);?></div>
						<?php endforeach ?>
					<?php endif ?>
				</div>
				<div class="button-expand">
					<span class="fa fa-caret-down"></span>
				</div>
			</div>
		</div>
	</div>
	<?php if(false): /* Selon que l'on soit sur la page de récapitulatif ou la page d'engagement des démarches */ ?>
		<div class="row">
			<div class="col-md-8 col-md-offset-2 content-devis">
				<span class="highlight">Les démarches à engager&nbsp;:</span><br/>
				<ol>
					<li class="text-info">
						Je demande d’abord un devis nominatif à l’organisme de formation&nbsp;: <?php _H($ar['organisme/nom']);?><br/>
						<ul>
							<?php if(is_array($infoContact) && !empty($infoContact)): ?>
								<?php foreach($infoContact as $class=>$contact): ?>
									<li><span class="fa <?php _T($class);?>"></span> <?php _T($contact);?></span></li>
								<?php endforeach ?>
							<?php endif ?>
						</ul>
					</li>
					<li class="text-info">
						J’effectue ensuite les démarches pour <a href="#financements">chaque financement</a>&nbsp;:
						<ul>
							<li>selon l’ordre de priorité indiqué ci-dessous</li>
						</ul>
					</li>
					<li class="text-info">
						Je sollicite enfin mon conseiller emploi pour valider ce projet de formation et son financement&nbsp;:
						<ul>
							<li>en lui présentant le devis</li>
							<li>en lui présentant la liste des démarches effectuées</li>
						</ul>
					</li>
				</ol>
			</div>
		</div>
	<?php endif ?>
	<div class="row">
		<div class="col-md-12 text-center">
			<?php if($c=count($droits)): ?>
				<h2>Nous avons trouvé <?php _H($c);?> financement<?php _H($c>1?'s':'');?> pour réaliser votre formation</h2>
			<?php elseif(!isset($erreur)): ?>
				<h2>Nous n'avons pas trouvé de financement</h2>
			<?php else: ?>
			<h2>Une erreur est survenue</h2>
			<?php endif ?>
		</div>
	</div>
	<div id="financements" class="row">
		<div class="col-md-9 col-sm-8 droits">
			<?php if(!$c): ?>
				<?php if($type=="salarie" && !$erreur): ?>
						<div class="text-left">D'après les informations que vous nous avez apportées et les caractéristiques de la formation, celle-ci n'est financée ni par:
							<br/>- le compte personnel de formation (CPF)
							<br/>- le projet de transition professionnelle
							<br/>- ni par les financements de votre entreprise
						 </div>
					<br/>
				<?php elseif($type=="salarie" && $erreur): ?>
					<div class="text-left">Nous n'avons pas pu calculer la prise en charge de votre formation en raison d'un problème technique.</div>
					<br/>
				<?php endif ?>
				<?php if($listLink): ?>
					<div class="col-md-6 text-left">Nous vous invitons à <a href="<?php _T($listLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span>élargir</a> votre recherche de formation.</div>
				<?php elseif(!$erreur): ?>
					<div class="col-md-6 text-left hide">Nous vous invitons à <a href="" id="retour-liste-resultat" class="lien-navigation">élargir votre recherche de formation</a>.</div>
					<p id="recherche-elargir-txt">Nous vous invitons à élargir votre recherche de formation.</p>
				<?php endif ?>
			<?php else: ?>
				<?php foreach($droits as $key=>$v): ?>
					<div data-type="FORMATION FINANCEE" class="row line">
						<div class="block-highlight">
							<div class="col-md-8 block-description col-height">
								<div class="row">
									<div class="col-md-12">
										<h3 class="financement"><?php _T($v['title']);?></h3>
									</div>
								</div>
								<div class="row explication">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-12">
												<strong>Je souhaite bénéficier de ce financement :</strong><br/>
												<?php _T(stepFormat($engage?$v['step']:$v['descinfo']));?><br/>
												<?php _T($v['offre-emploi']);?>
											</div>
										</div>
										<?php if($engage && $v['descinfo']): ?>
											<div class="row explication">
												<div class="col-md-12">
													<span class="savoir-plus" data-type="<?php _H($key);?>">En savoir plus &gt;</span>
												</div>
											</div>
											<div class="row more-explication">
												<div class="col-md-12">
													<br/>
													<?php _T($v['descinfo']);?>
												</div>
											</div>
										<?php endif ?>
									</div>
								</div>
							</div>

							<div class="col-md-4 block-cout-remu col-height">
								<div class="picto text-center"><img src="/img/pictos/picto-inform.png" alt="information"/></div>
								<div class="cout"><?php _T($v['cost']?'<strong>Coût</strong><br/>'.$v['cost']:''); if(isset($v['cost']) && isset($v['cost-complement'])) _T(' '.$v['cost-complement']); ?></div>
								<div class="priseencharge remu"><?php _T($v['priseencharge']?'<strong>Prise en charge</strong><br/>'.$v['priseencharge']:'');?></div>
								<div class="plafondpriseencharge remu"><?php _T($v['plafondpriseencharge']?'<strong>Plafond de prise en charge</strong><br/>'.$v['plafondpriseencharge']:'');?></div>
								<div class="remu"><?php _T($v['indemnisation']?'<strong>Rémunération mensuelle</strong><br/>'.$v['indemnisation']:'');?>
								<?php _T($v['finremuneration']?'<br/>jusqu\'au '.date('d/m/Y',$v['finremuneration']):'');?></div>
								<div class="remu"><?php _T($v['rff']?'<strong>Rémunération de fin de formation</strong><br/>'.$v['rff']:'');?>
								<?php _T($v['debutrff']?'<br/>du '.date('d/m/Y',$v['debutrff']):'');?>
								<?php _T($v['finrff']?'<br/>jusqu\'au '.date('d/m/Y',$v['finrff']):'');?></div>

								<div class="organisme remu">
									<?php if(is_array($v['organisme'])): ?>
										<strong>Organisme</strong><br/>
										<?php _T($v['organisme']['nom']?$v['organisme']['nom']."<br/>":''); ?>
										<?php _T($v['organisme']['adresse']?$v['organisme']['adresse']."<br/>":''); ?>
										<?php _T($v['organisme']['telephone']?$v['organisme']['telephone']."<br/>":''); ?>
										<?php _T($v['organisme']['web']?"<a rel=\"nofollow\" target=\"_blank\" href=\"{$v['organisme']['web']}\">{$v['organisme']['web']}</a><br/>":''); ?>
									<?php endif ?>
								</div>
								<div class="aides-a-la-formation"><a href="https://clara.pole-emploi.fr/aides/detail/aides-a-la-formation" onclick="track('CLIC AIDES A LA FORMATION - CLARA');" target="_blank">Aides possibles à la formation</a></div>
							</div>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif ?>
		</div>
		<div class="col-md-3 col-sm-4 contacter">
			<div class="row">
				<?php if($engage): ?>
					<div class="block-actions">
						<div class="col-md-12 text-center">
							<?php if($email=$ar['organisme/contact/email']): ?>
								<h5>Contacter le centre de formation</h5>
								<a class="btn" onclick="track('CLIC MAIL CONTACT ORGANISME');" href="mailto:<?php _M($email,array('subject'=>'A propos de votre formation de: '.$ar['intitule']));?>"><span class="fa fa-paper-plane fa-picto"></span></a>
							<?php endif ?>

							<?php if($c): ?>
								<h5>M'envoyer cette page de démarches par email</h5>
								<a id="state-mail" href="#" onclick="javascript:stateMail();" class="btn mail" title="M'envoyer cette page de démarches par email"><span class="fa fa-envelope fa-picto"></span></a>
								<span class="send-mail">
									<input type="email" id="mail" name="mail" value="" placeholder="Votre adresse email"/>
									<a href="javascript:sendMail();" class="button">Envoyer</a>
								</span>
								<span id="msgmail"></span>

								<div class="hidden-xs">
									<h5>Imprimer votre simulation</h5>
									<a href="javascript:demarchePrint();" class="btn print" title="Imprimer votre simulation"><span class="fa fa-print fa-picto"></span></a>
								</div>
							<?php endif ?>
						</div>
					</div>
					<?php if(CONTACT_MAIL): ?>
					<div class="block-actions anomalie">
							<div class="col-md-12 text-center">
								<h5>Signaler une anomalie<br/>sur votre simulation</h5>
								<a href="mailto:<?php _M(EMAIL_CONTACT,array('subject'=>'Anomalie financement','body'=>"Explication de l'anomalie sur les dispositifs de financement :\n\n\n\nURL de la page concernée : ".URL_BASE.$this->rewrite('/simulatorresult.php',array('cmd'=>'engage')+$this->get())));?>" class="btn"><span class="fa fa-exclamation-triangle fa-picto"></span></a>
							</div>
					</div>
					<?php endif ?>
				<?php else: ?>
					<div class="block-actions">
						<div class="col-md-12 text-center">
							<a href="<?php $this->rw('/simulatorresult.php',array('cmd'=>'engage')+$this->get());?>" class="button call-inv">Voir les démarches</a>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
	<?php if(0 /*in_array('PIC',$ar['caracteristiques,array()']->toArray())*/): ?>
		<div class="row line">
			<div class="block-highlight">
				<div class="col-md-8 block-description col-height">
					<div class="row explication">
						Cette formation bénéficie d'un financement du <strong>Plan d’Investissement Compétences</strong> par l’État.<br>
						Il est destiné à former des <strong>demandeurs d’emploi peu ou pas qualifiés et des jeunes éloignés du marché du travail.</strong>
					</div>
				</div>
				<div class="col-md-8 block-description col-height"><a data-toggle="modal" data-target="#info-pic" style="cursor:pointer;"><span class="pic">PIC</span></a></div>
			</div>
		</div>
		<?php $this->view('/inc/modal_pic_view.php') ?>
	<?php endif ?>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
