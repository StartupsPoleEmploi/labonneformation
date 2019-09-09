<?php _BEGINBLOCK('script'); ?>
	<script>
		$(document).ready(function() {
			initEngine();
		});
	</script>
<?php _ENDBLOCK('script'); ?>
<form id="block-recherche" class="block-recherche clearfix" action="/result.php" method="get" style="<?php _T($engine?'':'display: none;');?>">
	
	<div class="row">
		<div class="col-md-12">
			<div class="mots-cles">
				<p><strong>Je cherche une formation de</strong></p>
				<input type="text" id="criteria_search" name="criteria[search]" value="<?php _H($criteria['search']);?>" autofocus class="input search" placeholder="Coiffeur, cariste, AFPA..."/>
			</div>
			<div class="lieu">
				<p><strong>Où ?</strong></p>
				<input type="text" id="criteria_location" name="criteria[location]" value="<?php _H($criteria['location']);?>" placeholder="44300, Auxerre, Bordeaux, à distance, ..." class="input location"/>
			</div>
			<input type="hidden" id="criteria_code" name="criteria[code]" value="<?php _H($criteria['code']);?>"/>
			<input type="hidden" id="criteria_locationpath" name="criteria[locationpath]" value="<?php _H($criteria['locationpath']);?>"/>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 text-xs-right">
			<button type="submit" class="btn trouver">Trouver</button>
		</div>
	</div>
</form>