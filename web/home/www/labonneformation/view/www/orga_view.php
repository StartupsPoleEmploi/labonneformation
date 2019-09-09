<?php _BEGINBLOCK('title'); ?>Liste des centres de formation professionnelle commençant par <?php _T(is_null($firstLetter)?'un chiffre':"la lettre &laquo;&nbsp;$firstLetter&nbsp;&raquo;");?><?php _H($page?" - page $page":'');?><?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('description'); ?>
	A quoi sert ce site ? &laquo;&nbsp;La Bonne Formation&nbsp;&raquo; vous permet de trouver une formation, de repérer son efficacité en terme de retour à l’emploi et d’identifier les solutions possibles de financement de cette formation en fonction de votre profil. Le site vous guide ensuite vers les formalités à remplir.
<?php _ENDBLOCK('description'); ?>

<?php _BEGINBLOCK('css'); ?>
	<style>
		.la-bonne-formation .section-body ul { list-style-type: none; }
	</style>
	<?php $asset->add('css',array('/css/conditions.less')); ?>
	<?php if($currentPage>$startingPage): ?>
		<link rel="prev" href="<?php $this->rw('/orga.php',array('firstletter'=>$firstLetter,'page'=>$currentPage-1));?>"/>
	<?php endif ?>
	<?php if($currentPage<$endingPage): ?>
		<link rel="next" href="<?php $this->rw('/orga.php',array('firstletter'=>$firstLetter,'page'=>$currentPage+1));?>"/>
	<?php endif ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('content'); ?>
	<div class="row">
		<div class="col-md-12">
			<h1>Annuaire des organismes commençant par <?php _T(is_null($firstLetter)?'un chiffre':"la lettre &laquo;&nbsp;$firstLetter&nbsp;&raquo;");?></h1>

			<ul class="pagination">
				<li class="<?php $firstLetter == null ? _H('active') : '' ?>"><a href="<?php $this->rw('/orga.php'); ?>">#</a></li>
				<?php foreach(range('A','Z') as $char): ?>
					<li class="<?php _H(strtoupper($firstLetter)==$char?'active':'');?>"><a href="<?php $this->rw('/orga.php', array('firstletter'=>$char)); ?>"><?php _H($char) ?></a></li>
				<?php endforeach; ?>
			</ul>

			<div>
				<ul>
					<?php foreach($list as $orga):?>
						<li><a href="<?php $this->rw('/result.php', array('criteria'=>array('orgaid'=>$orga['id'])));?>"><?php _H($orga['name']);?></a><small> (<?php _H($orga['cnt']);?> annonce<?php _H($orga['cnt']>1?'s':'');?>)</small></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<ul class="pagination">
				<?php if($previousPage): ?>
					<li>
						<a href="<?php $this->rw('/orga.php', array('firstletter'=>$firstLetter,'page'=>$currentPage-1)); ?>"><i class="glyphicon glyphicon-menu-left"></i></a>
					</li>
				<?php endif; ?>
				<?php for($i=$startingPage;$i<=$endingPage;$i++): ?>
					<li class="<?php $i == $currentPage ? _H('active') : '' ?>">
						<a href="<?php $this->rw('/orga.php', array('firstletter'=>$firstLetter,'page'=>$i)); ?>"><?php _H($i); ?></a>
					</li>
				<?php endfor; ?>
				<?php if($nextPage): ?>
					<li>
						<a href="<?php $this->rw('/orga.php', array('firstletter'=>$firstLetter,'page'=>$currentPage+1)); ?>"><i class="glyphicon glyphicon-menu-right"></i></a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>