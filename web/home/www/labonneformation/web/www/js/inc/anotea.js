function setAvis(template,json) {
	if(!json) {
		template.hide();
		return null;
	}
	var pseudo=(json.hasOwnProperty('pseudo') && json.pseudo!=null && json.pseudo!=='')?json.pseudo:"(anonyme)";
	var commentaire=json.commentaire;
	var commentaireOrganisme=(commentaire!==null && commentaire!==undefined && commentaire.reponse!==null && commentaire.reponse!==undefined)?commentaire.reponse:'';
	var commentairePoleEmploi='';
	var noteAccueil=json.notes.accueil;
	var noteSuiviPedagogique=json.notes.contenu_formation;
	var noteEquipeDeFormateurs=json.notes.equipe_formateurs;
	var noteMoyensMateriels=json.notes.moyen_materiel;
	var noteRecommandations=json.notes.accompagnement;
	var noteGlobale=json.notes.global;

	var datePublication=json.hasOwnProperty('date')? new Date(json.date).toLocaleDateString():'';
	var dateSession=(json.hasOwnProperty('formation')&&json.formation.hasOwnProperty('action')&&json.formation.action.hasOwnProperty('session')&&json.formation.action.session.hasOwnProperty('periode')&&json.formation.action.session.periode.hasOwnProperty('debut'))? new Date(json.formation.action.session.periode.debut).toLocaleDateString() : '';

	var titre="";
	if(json.hasOwnProperty('formation')&&json.formation.hasOwnProperty('action')&&json.formation.action.hasOwnProperty('session')&&json.formation.action.session.hasOwnProperty('periode')&&json.formation.action.session.periode.hasOwnProperty('fin'))
		dateSession+=" au "+new Date(json.formation.action.session.periode.fin).toLocaleDateString();

	if(commentaire && commentaire!=undefined) {
		var comment=[];
		var titre=null;
		if(commentaire.titre && commentaire.titre!=='' && /[a-zA-Z]/.test(commentaire.titre)) titre=commentaire.titre;
		if(commentaire.texte) comment.push(commentaire.texte);
		commentaire=(titre!==null?(titre+"<br/><br/>"):'')+"« "+comment.join("<br/>")+" »";
	} else {
		template.find(".champ-commentaire").hide();
	}

	template.find(".champ-pseudo").html(pseudo);
	template.find(".champ-commentaire").html(commentaire);
	template.find(".champ-commentaireorganisme").html(commentaireOrganisme);
	template.find(".champ-commentairepoleemploi").html(commentairePoleEmploi);
	if(commentaireOrganisme=="" && commentairePoleEmploi=="")
		template.find(".plus").hide();
	if(commentaireOrganisme=="")
		template.find(".organisme").hide();
	if(commentairePoleEmploi=="")
		template.find(".pole-emploi").hide();
	//template.find(".champ-accueil span").attr("class","champ-accueil note note-"+noteAccueil);
	template.find(".champ-accueil span:first").html(noteAccueil+"/5 ");
	//template.find(".champ-equipedeformateurs span").attr("class","champ-equipedeformateurs note note-"+noteEquipeDeFormateurs);
	template.find(".champ-equipedeformateurs span:first").html(noteEquipeDeFormateurs+"/5 ");
	//template.find(".champ-lesuivipedagogique span").attr("class","champ-lesuivipedagogique note note-"+noteSuiviPedagogique);
	template.find(".champ-lesuivipedagogique span:first").html(noteSuiviPedagogique+"/5 ");
	//template.find(".champ-lesmoyensmateriels span").attr("class","champ-lesmoyensmateriels note note-"+noteMoyensMateriels);
	template.find(".champ-lesmoyensmateriels span:first").html(noteMoyensMateriels+"/5 ");
	//template.find(".champ-recommandations span").attr("class","champ-recommandations note note-"+noteRecommandations);
	template.find(".champ-recommandations span:first").html(noteRecommandations+"/5 ");
	template.find(".champ-global").attr("class","champ-global note note-"+noteGlobale);
	//template.find(".champ-global").html(noteGlobale+"/5");

	template.find(".champ-date-publication").html(datePublication);
	template.find(".champ-date-session").html(dateSession);
	if (json.hasOwnProperty('formation') && json.formation.intitule !== null && json.intitule !== "") {
		template.find(".champ-titre").html(json.formation.intitule);
	} else {
		template.find(".champ-titre").hide();
	}
	return template;
}

function initAvis(params,sessions) {
	var template=params.template || $("#template-avis");
	var templateZoneId=params.zoneId || "#tous-les-block-avis";
	var largeur=params.largeur || 'etroit';
	var noteMoyenne=0;
	var count=0;

	var apiAnotea='https://anotea.pole-emploi.fr/api/v1/sessions?id='+sessions.map(function(e){return e.join('|');}).join(',')+'&fields=avis,score,id&items_par_page=100';
	$.getJSON(apiAnotea,function(json) {
		var avis=json.sessions[0].avis || [];

		var nbAvis=avis.length;

		avis.forEach(function(avisJson) {
			count++;
			if(avisJson.notes.global != null) { // la note est obligatoirement renseigné
				noteMoyenne+=avisJson.notes.global;

				//if (nombreAvisMax!==null && count>nombreAvisMax) return;

				var blockAvis=$("#block-avis > div").clone();

				blockAvis=setAvis(blockAvis,avisJson).show();
				applyCollapse(blockAvis);

				$(templateZoneId).append(blockAvis);

			} else {
				nbAvis--;
			}

		});

		if (nbAvis>0) {$(".block-avis-anotea").show();} // on a des avis, affiche le block Anotea

		noteMoyenne=Math.round(noteMoyenne/(nbAvis?nbAvis:1));

		template.find(".champ-nbavis").html(nbAvis);
		template.find(".champ-notemoyenne").attr("class","champ-notemoyenne note note-"+noteMoyenne);
		template.find(".avis-navigation").hide();
		template.fadeIn();

		if (largeur=='etroit') {
			initCarousselAnotea(templateZoneId);
		} else {
			initCarousselResult(templateZoneId);
		}

		//if (nombreAvisMax && nbAvis>nombreAvisMax) {
		//	template.find(".bouton-plus-d-avis").show();
		//}
	});
}

function initCarousselAnotea(templateZoneId) {
	$(window).resize(function () {
		$(templateZoneId).not(".slick-initialized").slick('resize');
	});
	$(templateZoneId).slick({
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
				slidesToShow: 1,
				slidesToScroll: 1,
				//infinite: true,
			}
		},
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				//infinite: true,
			}
		},
		{
			breakpoint: 768,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: false
			}
		},
		{
			breakpoint: 0,
			settings: {
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
			}
		}
	  ]
	});
}

function initCarousselResult(zoneId) {
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
