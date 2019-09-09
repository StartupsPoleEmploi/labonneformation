/* Assure les liens de retour. JS pour continuer à fonctionner malgré le cache NginX */
function initFollowLink() {
	var list=[];
	$('h2 > a').each(function() {
		var link=$(this).attr("href");
		list.push({id:link.replace(/^.*-(\d+)$/,"$1"),link:link});
	});

	list=JSON.stringify(list);
	sessionStorage.setItem("list",list);
	sessionStorage.setItem("location",$("#criteria_location").val());
	sessionStorage.setItem("backlink",window.location.href);
	sessionStorage.setItem("listresultlink",window.location.href);
	sessionStorage.setItem("backtitle",$("#backtitle").val());
}
/* Permet de trier à la volée les résultats selon le taux, la date ou la distance: déplace les blocs HTML. *
 * Evite ainsi de décliner le cache NginX via un paramètre d'URL, mais se cantonne au tri de la liste      *
 * fournie via le serveur.                                                                                 */
function sortAds(mode){
	if (mode != '') {
		var tab = [];
		$('.block-annonce').each(function() {
			var o=$(this);
			tab.push({"tx":o.data("tx"),"date":o.data("date"),"dist":o.data("dist"),"html":o.html()});
		});
		switch(mode) {
			case 'tx':
				tab.sort(function(a,b) {
					return (a.tx?a.tx:0)>(b.tx?b.tx:0)?-1:1;
				});
				break;
			case 'date':
				tab.sort(function(a,b) {
					return (a.date?a.date:0)<(b.date?b.date:0)?-1:1;
				});
				break;
			case 'dist':
				tab.sort(function(a,b) {
					return (a.dist?a.dist:0)<(b.dist?b.dist:0)?-1:1;
				});
				break;
		}
		var i=0;
		$('.block-annonce').each(function() {
			$(this).data("tx",tab[i].tx);
			$(this).data("date",tab[i].date);
			$(this).data("dist",tab[i].dist);
			$(this).html(tab[i].html);
			i++;
		});
	}
}

function initResult(param,sessions,isDomaine) {
	//$(".taux-reembauche").gauge({type:"cam",'fontSizeFact':1.6,'borderFact':1.5,'effect':'shadow','backColor':'#C4D3E7','startColor':'#003173','stopColor':'#336DA1','lag':1000,'animate':'slow',section:{nb:5,color:'001153'}});
	initFollowLink();

	$("#formulaire-filtre input:checkbox, #formulaire-filtre input:radio").click(function() {
		if($("#button-submit").is(":hidden")) //Autosubmit seulement si pas mode mobile
			$("#formulaire-filtre").submit();
	});
	$("#formulaire-filtre select").change(function() {
		if($("#button-submit").is(":hidden")) //Autosubmit seulement si pas mode mobile
			$("#formulaire-filtre").submit();
	});

	$("#formulaire-filtre button[type=button]").click(function() {
		$("#formulaire-filtre input:checkbox").prop("checked",false);
	});

	/* Assure qu'un clic sur la ligne d'annonce provoque un équivalent du clic sur le lien du H2 */
	//$('.row .line, .row .ad-result-line').click(function() {
	//	window.location.href=$(this).find("h2 > a").attr("href");
	//});

	//$(".btn-tri-mobile").click(function() {
	//	$(".block-avance .close").show();
	//	$(".block-criteres").slideDown();
	//});

	$(".btn-tri-mobile").click(function() {
		var target=$(this).data("target");
		$(target).slideDown();
		$(".close").click(function() {
			$(target).slideUp();
		});
	});

	/* Détecte une demande de tri dans la select list et stocke en sessionsStorage le mode de tri */
	$(".filtre input[name=trier]").change(function() {
		var sort=$(this).val();
		sortAds(sort);
		sessionStorage.setItem("sort",sort);
	});

	/* Provoque le tri des annonces en fonction de ce qui a été indiqué et stocké dans le sessionStorage */
	var sort=sessionStorage.getItem("sort");
	if(sort=="") sort="date";
	if(sort!=="date") {
		sortAds(sort);
	}
	$(".filtre input[value="+sort+"]").prop('checked',true);

	//initCarousselResult();
	
	/* tooltip pour affichage de conseils de recherche */
	if(param!=null && param.showadvice){
		adviceToolTip();
	}

	initAvisResult(sessions,isDomaine);
}

function initCarousselBlocksDomaines(zoneId) {
	$(".avis-par-domaine").fadeIn();
	$(zoneId).fadeIn();
	$(window).resize(function () {
		$(zoneId).not(".slick-initialized").slick('resize');
		//$("#tous-les-block-avis").not(".slick-initialized").slick('resize');
	});

	$(zoneId).slick({
		//prevArrow:"<img class='' src='/img/pictos/picto-check.png'>",
		//nextArrow:"<img class='' src='/img/pictos/picto-check.png'>"
		centerPadding: "2em",
		//prevArrow: '<div class="slick-arrow"><span class="fa fa-angle-left"></span><span class="sr-only">Prev</span></div>',
		//nextArrow: '<div class="slick-arrow"><span class="fa fa-angle-right"></span><span class="sr-only">Next</span></div>'
		slidesToShow: 4,
		slidesToScroll: 4,
		mobileFirst: true,
		responsive: [
			{
				breakpoint: 1800,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
				}
			},
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2
				}
			},
			{
				breakpoint: 0,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: true
				}
			}
		]
	});
}

function initAvisResult(sessions,isDomaine) {
	$.get('https://anotea.pole-emploi.fr/api/v1/sessions?id='+sessions.map(function(e){return e.join('|');}).join(',')+'&fields=avis,score,id&items_par_page=100', function(response) {
			var sessions=response.sessions;

			var tousLesAvis=[];
			var avisParDomaine={};
			var noteParDomaine={};
			var nbAvisTotal=0;

			for (var i=0;i<sessions.length;i++) {
				session=sessions[i];
				nbAvis=session.score.nb_avis;

				// Nombre d'avis pour chaque session
				$(".nombre-avis-formation-"+session.id.split('|').join('-')).text(nbAvis+" avis");

				// Moyenne du score pour chaque session
				if (session.score && session.score.notes) $(".note-formation-"+session.id.split('|').join('-')).addClass("note-"+session.score.notes.global);

				// Tri par domaine pour la liste des formations d'un OF
				if (session.avis) {
					tousLesAvis=tousLesAvis.concat(session.avis);

					for (var j=0;j<session.avis.length;j++) {
						avis=session.avis[j];

						if (avis.formation.domaine_formation.formacodes) {
							for (var k=0;k<avis.formation.domaine_formation.formacodes.length;k++) {
								// Pour chaque formacode, on extrait le domaine
								formacode=avis.formation.domaine_formation.formacodes[k];
								domaine=formacode.substring(0,3);

								if (avisParDomaine.hasOwnProperty(domaine)) {
									avisParDomaine[domaine]=avisParDomaine[domaine].concat(avis);
								} else {
									avisParDomaine[domaine]=[];
								}
							}
						}
					}
				}
			}

			tousLesAvis=dedupAvis(tousLesAvis);

			// Affichage des domaines de cet OF

			if (!isDomaine) initCarousselBlocksDomaines("#carous");

			// Affichage des stats des avis par domaine, en haut de page
			for (var key in avisParDomaine) {
				// Nombre d'avis par domaine
				var avisDedup=dedupAvis(avisParDomaine[key]);
				var nombreAvisDansDomaine=avisDedup.length;
				if (avisParDomaine.hasOwnProperty(key)) {
					$("#domaine-nb-avis-"+key).text("Avis ("+nombreAvisDansDomaine+") ");
				}

				// Note moyenne des avis par domaine

				var noteMoyenne=0;
				for (var i=0;i<avisDedup.length;i++) {
					a=avisDedup[i];
					noteMoyenne=noteMoyenne+a.notes.global;
				}

				$("#domaine-note-moyenne-"+key).addClass("note-"+Math.floor(noteMoyenne/nombreAvisDansDomaine));
			}

//			if (tousLesAvis.length>0) {
//				$(".titre-avis-domaine").text(" sur ").show();
//				$(".titre-nb-avis").text(tousLesAvis.length+" avis de stagiaire ").show();
//			} else {
//				$(".titre-nb-avis").text("0"+" avis de stagiaire ").show();
//			}
			$(".titre-avis").show();
	}, 'json');
}

function dedupAvis(lesAvis) {
	var obj = {};

	for (var i=0,len=lesAvis.length;i<len;i++) {
		obj[lesAvis[i]['id']] = lesAvis[i];
	}

	var lesAvisDedup = new Array();
	for ( var key in obj ) {
		lesAvisDedup.push(obj[key]);
	}
	return lesAvisDedup;
}
