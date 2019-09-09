<?php _BEGINBLOCK('title'); ?>Financement de la formation "<?php _H($ar['intitule']);?>"<?php _ENDBLOCK('title'); ?>
<?php _BEGINBLOCK('meta'); ?><meta name="robots" content="noindex"><?php _ENDBLOCK('meta'); ?>

<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/simulatorform.less')); ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
	<?php $asset->add('js',array('/js/jquery/jquery.plugin.datepicker.js')); ?>
	<script>
		function calcAge(dateString) {
			if(!dateString.match(/^\d{1,2}\/\d{1,2}\/\d{4}$/g)) return 0;
			var today=new Date();
			var birthDate=new Date(dateString.replace(/^(\d+?)\/(\d+?)\/(\d+?)$/,"$3"),dateString.replace(/^(\d+?)\/(\d+?)\/(\d+?)$/,"$2"),dateString.replace(/^(\d+?)\/(\d+?)\/(\d+?)$/,"$1"));
			var age=today.getFullYear()-birthDate.getFullYear();
			var m=today.getMonth()-birthDate.getMonth();
			if(m<0 || (m===0 && today.getDate()<birthDate.getDate())) age--;
			return age;
		}
		String.prototype.subPath=function(level) {
			var a=this.replace(new RegExp("^((\/\\d+){"+level+"}).*$","gi"),"$1/");
			return a;
		}
		$(document).ready(function() {
			$("[for='situation_salarie']").addClass('advicetip').attr('data-title','Avant de poursuivre, munissez vous de votre fiche de paye').attr('data-toggle','tooltip').attr('data-placement','right').on('click',function(){adviceToolTip();});
			<?php _T($form->getJs());?>
			/* Remplace le datepicker */
			$(".date").datePicker().on('blur', function(e) {
				var chunks = $(e.currentTarget).val().split('/');
				if(chunks.length === 3) {
					var year = parseInt(chunks[2]);
					if(!isNaN(year) && year<100) {
						year = year>=50 ? year+1900 : year+2000;
						$(e.currentTarget).val(chunks[0] + "/" + chunks[1] + "/" + year);
					}
				}
			}).bind('paste', function() {
				setTimeout(function() {
					$("#birthdate").change();
				},100);
			});

			$("input, select").focus(function() {
				$(this).removeClass("error");
			});

			/* Selectionne tous les "input" visibles et met leur id dans le input id="visible" afin d'indiquer par la suite les input visibles à valider */
			$("#form-simulator").submit(function()
				{
					var visible=[];
					<?php if($step==1): ?>
						track('FORM FINANCEMENT - ETAPE <?php echo $step ?>');
					<?php endif ?>
					<?php if($this->get('inscritDE') && $step>1):?>
						track('FORM FINANCEMENT DEMANDEUR EMPLOI - ETAPE <?php echo $step ?>');
					<?php endif ?>
					<?php if($this->get('salarie') && $step>1):?>
						track('FORM FINANCEMENT SALARIE - ETAPE <?php echo $step ?>');
					<?php endif ?>


					$("#form-simulator input:visible, #form-simulator select:visible, #form-simulator textarea:visible, #form-simulator #domicilepath, #form-simulator #droitprive, #form-simulator #entrepriselocationinsee").each(function(e)
						{
							visible.push($(this).attr("name"));
						});
					//$("#form-simulator select:visible").each(function(e)
					//	{
					//		visible.push($(this).attr("name"));
					//	});
					$("#visible").val(visible.join(" "));
					//alert($("#visible").val());
					return true;
				});

			/* Rend inactif le bouton de validation du formulaire si la checkbox "Inscrit" n'est pas selectionnée */
			if($("#situation_inscrit1").length) {
				$("#situation_inscrit1, #situation_inscrit2").change(function() {
					$("#inscritDE").val('');
					$("#salarie").val('');
					if($(this)[0].id=='situation_inscrit2')
						$("#salarie").val($(this).prop("checked")?'on':'');
					if($(this)[0].id=='situation_inscrit1')
						$("#inscritDE").val($(this).prop("checked")?'on':'');
					if(!$("#situation_inscrit1").prop("checked") && !$("#situation_inscrit2").prop("checked"))
						$("#submit").prop('disabled',true).addClass('disabled');
					else
						$("#submit").prop('disabled',false).removeClass('disabled');
				});
			}

			/* Ici on gère les cliques d'informations avec symbole "(?)"" */
			$("#situation_parentisole_label").parent().hide();
			$("#situation_parentisole + .label-after").click(function()
			{
				$("#situation_parentisole_label").parent().fadeToggle();
			}).css("cursor","pointer");
			$("#situation_personnesortantcontrataide_label").parent().hide();
			$("#situation_personnesortantcontrataide + .label-after").click(function()
			{
				$("#situation_personnesortantcontrataide_label").parent().fadeToggle();
			}).css("cursor","pointer");
			$("#situation_cdd5years_label").parent().hide();
			$("#situation_cdd5years + .label-after").click(function()
			{
				$("#situation_cdd5years_label").parent().fadeToggle();
			}).css("cursor","pointer");

			<?php if($step==2): ?>
				/* Completion du champ domicile */
				if($("#location").length)
				{
					$("#location").complete({
						call:"/ws/ws_locationcompletion.php?v=1.9&q=%s&mode=zipcode",
						onvalidate:"auto",
						lag:15,
						onselect:function(result) {
							$("#location").val(result.value["zipcode"]);
							//$("[name='criteria[locationslug]']").val(result.value["slug"]);
							$("#domicilepath").val(result.value["path"]);
						},
						onchange:function(key,result) {
							var path="";
							if(result[0]) path=result[0].value["path"];
							$("#domicilepath").val(path).trigger("change");
						},
						classover:"over",
						classlist:"locationcompletion"
					});
				}
			<?php endif ?>
			<?php if($step==5): ?>
				/* Completion du champ idcc */

				if($("#idcc").length)
				{
					$("#idcc-tooltip").tooltip({placement:'right',html:true,title:'<img src="/img/codeIDCC.png"/>'});
					$("#idcc").complete({
						call:"/ws/ws_idcccompletion.php?v=1.0&q=%s",
						onvalidate:"auto",
						lag:15,
						onselect:function(result) {
							$("#idcc").val(result.value);
						},
						//onchange:function(key,result) {
						//	$("#idcc").val(result.value).trigger("change");
						//},
						classover:"over",
						classlist:"locationcompletion"
					});
				}
				if($("#naf").length)
				{
					$("#naf-tooltip").tooltip({placement:'right',html:true,title:'<img src="/img/codeNAF.png"/>'});
					$("#naf").complete({
						call:"/ws/ws_nafcompletion.php?v=1.0&q=%s",
						onvalidate:"auto",
						lag:15,
						onselect:function(result) {
							$("#naf").val(result.value);
						},
						//onchange:function(key,result) {
						//	$("#naf").val(result.value).trigger("change");
						//},
						classover:"over",
						classlist:"locationcompletion"
					});
				}
				if($("#commune-entreprise").length)
				{
					$("#commune-entreprise").complete({
						call:"/ws/ws_locationcompletion.php?v=1.5&q=%s&mode=commune",
						onvalidate:"auto",
						lag:15,
						onselect:function(result) {
							$("#commune-entreprise").val(result.value["label"]);
							//$("[name='criteria[locationslug]']").val(result.value["slug"]);
							$("#entrepriselocationinsee").val(result.value["insee"]);
						},
						onchange:function(key,result) {
							var insee="";
							if(result[0]) insee=result[0].value["insee"];
							$("#entrepriselocationinsee").val(insee).trigger("change");
						},
						classover:"over",
						classlist:"locationcompletion"
					});
				}
			<?php endif ?>
			//$("#progress").animate({"width":"<?php _T($step*20);?>%"},1000);
			$("#prog-<?php _H($step-1);?>").animate({"width":"100%"},1000);
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('followlink'); ?>
	<?php if($backLink): ?>
		<div class="row section-ariane">
			<div class="col-md-6 text-left"><?php if($backLink):?><a href="<?php _T($backLink);?>" class="lien-navigation"><span class="fa fa-chevron-left"></span> Retour au détail de la formation</a><?php endif ?></div>
			<div class="col-md-6 text-right"></div>
		</div>
	<?php endif ?>
<?php _ENDBLOCK('followlink'); ?>

<?php _BEGINBLOCK('content'); ?>
	<?php if($errors['page']=="noaccess-salarie"): ?>
		<div class="row">
			<div class="col-md-12 text-center"><i class="fa fa-exclamation-circle"></i></div>
		</div>
		<div class="row">
			<div class="col-md-6 text-justify">
				<h2>Seules les personnes inscrites comme « demandeur d'emploi » peuvent bénéficier d'une aide financière pour cette formation.</h2>
				<h2>Assurez-vous de bien sélectionner « salarié » dans les filtres de recherche pour obtenir une liste spécifique de formations pouvant bénéficier d'une aide au financement</h2>
			</div>
			<div class="col-md-6 text-center"><img alt="cochez &quot;salarie&quot; dans les filtres de liste" src="/img/aide-filtre-resultat.png"/></div>
		</div>
		<div class="row">
			<div class="col-md-12 text-center"><span class="button" onclick="window.location=(sessionStorage.getItem('backlink'))?sessionStorage.getItem('backlink'):'/'">Retour aux offres de formation</a/></div>
		</div>
	<?php else: ?>
		<div class="row">
			<div class="col-md-12 text-xs-center">
				<h2>
					<?php if($step>0): ?>
						Renseigner ma situation pour<br/>
						<span class="highlight">connaître mes possibilités de financement</span>
					<?php else: ?>
						Renseignez maintenant vos informations pour déterminer les financements<br/>
						<span class="highlight">adaptés à votre situation et à cette formation</span>
					<?php endif ?>
				</h2>
			</div>
		</div>
		<?php if(!empty($errors)):?>
			<div class="row message">
				<div class="col-md-12">
					<div class="error text-center">Veuillez bien vérifier et renseigner les champs en rouge</div>
				</div>
			</div>
		<?php endif ?>
		<div class="row">
			<div class="col-md-12">
				<form id="form-simulator" action="<?php $this->rw('/simulatorform.php',array('ar'=>$ar,'step'=>($step==0?1:$step)));?>" method="post">
					<input type="hidden" name="etat-form" value="<?php _T($step);?>"/>
					<input type="hidden" id="visible" name="visible" value=""/>
					<div class="row">
						<div class="col-md-offset-1 col-md-10">
							<?php if($step):?>
								<div class="row">
									<div class="col-md-12">
										<table class="prog">
											<tr>
												<?php for($i=0;$i<5;$i++):?>
													<td class="hidden-xs">
														<div class="item">
															<div id="prog-<?php _H($i);?>" class="prog-bar" style="width:<?php _T($i<($step-1)?'100':'0');?>%;"></div>
														</div>
													</td>
												<?php endfor ?>
												<td class="bulle text-center-xs">
													<div class="prog-bulle">
														<span class="step"><?php _H($step);?></span><span class="total-step">/5</span>
													</div>
												</td>
											</tr>
										</table>
										<!--
										<div class="progress">
											<div id="progress" style="height:100%; background-color:#2272BA; width:<?php _T(($step-1)*20);?>%;"></div>
										</div>
										-->
									</div>
								</div>
							<?php endif ?>
							<div class="financement">
								<?php if($step>0 && $step<=5): ?>
									<?php //_T($form->display($errors,array('group'))); ?>
									<?php _T($form->display($errors,array('step-'.$step))); ?>
								<?php endif ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<?php if($step>1):?>
								<a href="<?php $this->rw('/simulatorform.php',array('ar'=>$ar,'step'=>$step-1));?>" class="button precedent">Précédent</a>
							<?php endif ?>
							<!--<a id="submit" class="button call" href="#" onclick="$('form').submit(); return false;">-->
								<input type="submit" id="submit" class="button" value="<?php if($step==0): ?>Renseigner mes informations<?php elseif($step>0 && $step<5): ?>Suivant<?php else: ?>Résultat<?php endif ?>"/>
							<!--</a>-->
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php endif ?>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
