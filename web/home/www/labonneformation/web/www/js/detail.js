/* Utilise sessionStorage pour assurer le principe de pages précédente et suivante */
function initFollowLink() {
	var predLink="",nextLink="",list=sessionStorage.getItem("list");
	if(typeof list!=="undefined" && list!==null && list!="") {
		var curId=window.location.href.replace(/^.*-(\d+).*/,"$1");
		list=JSON.parse(list);
		for(var i=0;i<list.length;i++)
			if(list[i].id==curId) {
				if(i>0) predLink=list[i-1].link;
				if(i<(list.length-1)) nextLink=list[i+1].link;
				break;
			}
		if(predLink!="") $("#pred-link").attr("href",predLink).show();
		if(nextLink!="") $("#next-link").attr("href",nextLink).show();
		$(document).on("keyup",function(e) {
			if(e.which==37 && $("#pred-link").is(":not(:hidden)")) window.location.href=$("#pred-link").attr("href");
			if(e.which==39 && $("#next-link").is(":not(:hidden)")) window.location.href=$("#next-link").attr("href");
		});
		if(i>=list.length) sessionStorage.removeItem("list");
	}
	var backTitle=sessionStorage.getItem("backtitle");
	if(typeof backTitle!=="undefined" && backTitle!==null && backTitle!="") {
		$("#backlink").attr('href',sessionStorage.getItem("backlink"))
		              .html('<span class="fa fa-chevron-left"></span>&nbsp;'+backTitle)
		              .css("display","block");
	}
}

function ajaxStats(romes,locationPath,displayJobs,alternance) {
	if(displayJobs)
		$.ajax({
			url:"/ws/ws_getpeoffers.php?rome="+encodeURI(romes)+"&locationpath="+locationPath+"&alternance="+alternance,
			//dataType:'text/html',
			success:function(result) {
				$("#ajaxstats").html("")
				               .css("display","none")
				               .html(result)
				               .fadeIn("slow");
				$("#ajaxoffers a").click(function() {
					var type=$(this).data('type');
					if(type) track('ONGLET STATS - CLICK '+type);
				});
			}
		});
	else
	{
		$("#ajaxstats").html("");
	}
}

/* Requete de dialogue si clic sur pas de stats de retour à l'embauche */
function blockPerspective() {
	$(".evaluation").click(function() {
		track('POPUP RETOUR A L\'EMPLOI');
	});
}

function initDetail(params) {
	var romes=params.romes;
	var locationPath=params.location.replace(/_/g,'/');
	var displayJobs=params.displayjobs;
	var alternance=params.alternance;

	//$("#onglet-taux").gauge({type:"cam",'fontSizeFact':1.6,'borderFact':1.5,'effect':'shadow','backColor':'#C4D3E7','startColor':'#003173','stopColor':'#336DA1','animate':false});

	ajaxStats(romes,locationPath,displayJobs,alternance);
	$(".block-perspective").click(function(){
		$(this).find(".info-retour-emploi").slideToggle();
	});

	initFollowLink();
	blockPerspective();
}