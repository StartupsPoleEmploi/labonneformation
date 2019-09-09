<?php _BEGINBLOCK('title'); ?>Les stats<?php _ENDBLOCK('title'); ?>

<?php _BEGINBLOCK('css'); ?>
	<?php $asset->add('css',array('/css/conditions.less','/js/chartist/chartist.min.css','/css/stats.less')); ?>
<?php _ENDBLOCK('css'); ?>

<?php _BEGINBLOCK('script'); ?>
	<?php $asset->add('js',array('/js/chartist/chartist.min.js','/js/chartist/plugins/chartist-plugin-legend.js','/js/chartist/plugins/chartist-plugin-tooltip.js')); ?> 
	
	<script>
		function calcHigh(serie,scale) {
			var max=0;
			serie.forEach(function(a) {
				if(a>max) max=a;
			});
			console.log(scale);
			return max*scale;
		}
		function calcLabels(serie) {
			var labels=[];
			var month=['J','F','M','A','M','J','J','A','S','O','N','D'];
			var i=0;
			var year=2016;
			serie.forEach(function(a) {
				labels[i]=month[i%12];
				if(!(i%12)) labels[i]+=' '+(year++);
				i++;
			});
			return labels;
		}
		function graph(target,type,series,addOptions,scale) {
			var options = {
				width: '100%',
				height: 300,
				high:calcHigh(series[0],scale),
				showPoint: false,
				plugins: [ ] //legendNames: ['Blue pill', 'Red pill', 'Purple pill']}) ]
			};
			var cLabels=calcLabels(series[0]);
			if(addOptions.legend) {
				options.plugins.push(Chartist.plugins.legend({}));
				for(i in series) {
					series[i]={"name":addOptions.legend[i],"data":series[i]};
				}
			}
			options.plugins.push(Chartist.plugins.tooltip({}));
			var data = {
				labels: cLabels,
				series: series
			};
			options=$.extend(options,addOptions);
			if(type=='line')
				new Chartist.Line(target,data,options);
			else
				new Chartist.Bar(target,data,options).on('draw',function(data) {
					if(data.type === 'bar') {	
						//data.element.attr({
						//	// Colore la barre de vert pr 10 à rouge pr 0
						//	style: 'stroke: hsl(' + Math.floor(Chartist.getMultiValue(data.value) / 10 * 100) + ', 50%, 50%);'
						//});
					}
				});
		}
		$(document).ready(function()
		{
			var options;
			<?php foreach($stats as $name=>$stat): ?>
				<?php
					$options=['showArea'=>true];
					if(isset($stat['legend'])) $options['legend']=$stat['legend'];
				?>
				<?php if(!is_string($stat['series'])): ?>
					graph('.<?php _H($name);?>','line',<?php _T(json_encode($stat['series'],true));?>,<?php _T(json_encode($options,true));?>,<?php _T($stat['scale']?$stat['scale']:1.5);?>);
				<?php endif ?>
			<?php endforeach ?>
		});
	</script>
<?php _ENDBLOCK('script'); ?>

<?php _BEGINBLOCK('content'); ?>
	<?php function stats($class,$title,$desc,$content=null) { ?>
		<div style="background-color:#white; margin:2em; border:1px dotted #ddd;">
			<div class="<?php _T($class); ?> stats"><?php _H(is_null($content)?'':$content);?></div>
			<div style="background-color:#f5f5f5; padding:1em; box-shadow: 1px 1px 12px #aaa;">
				<span style="font-weight:bold; color:#00226A"><span class="fa fa-bar-chart"></span> <?php _H($title); ?></span>
				<p style="margin:1em 0;"><?php _T(displayDesc($desc));?></p>
			</div>
		</div>
	<?php } ?>
	<div class="row">
		<div class="col-md-12">
			<h1>Statistiques La Bonne Formation</h1>
		</div>
	</div>
	<?php foreach(array_chunk($stats,3,true) as $s): ?>
		<div class="row">
			<?php foreach($s as $name=>$stat): ?>
				<div class="col-lg-4">
					<?php stats($name,$stat['title'],$stat['desc'],is_string($stat['series'])?$stat['series']:null); ?>
				</div>
			<?php endforeach ?>
		</div>
	<?php endforeach ?>
<?php _ENDBLOCK('content'); ?>
<?php require_once('base_view.php'); ?>
