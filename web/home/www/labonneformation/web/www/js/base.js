function applyCollapse(target) {
	$(target).find(".titre").click(function() {
		var titre=this;
		var target=$(this).parent().find($(this).data("target"));
		var parent=$(this).data("parent");

		if($(titre).find(".fa.fa-chevron-up, .fa.fa-chevron-down").is(":visible")) {// || target.is(":hidden")) {
			var visible=$(".collapse-xs:visible, collapse:visible").not(target);
			if(parent) visible=$(this).closest(parent).find(".collapse-xs:visible, collapse:visible").not(target);

			visible.slideUp("fast",function() {
				$(this).parent().find(".fa.fa-chevron-up, .fa.fa-chevron-down").removeClass("fa-chevron-up").addClass("fa-chevron-down");
			})

			target.slideToggle("fast",function() {
				if($(this).is(":visible"))
					$(titre).find(".fa.fa-chevron-up, .fa.fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-up");
				else
					$(titre).find(".fa.fa-chevron-up, .fa.fa-chevron-down").removeClass("fa-chevron-up").addClass("fa-chevron-down");
			});
		}
	});

	$(target).find("#apis_esd").click(function() {
		var apis_esd=this;
		var target=$(this).parent().find($(this).data("target"));
		var parent=$(this).data("parent");

		if($(apis_esd).find(".fa.fa-chevron-up, .fa.fa-chevron-down").is(":visible")) {// || target.is(":hidden")) {
			var visible=$(".collapse-xs:visible, collapse:visible").not(target);
			if(parent) visible=$(this).closest(parent).find(".collapse-xs:visible, collapse:visible").not(target);

			visible.slideUp("fast",function() {
				$(this).parent().find(".fa.fa-chevron-up, .fa.fa-chevron-down").removeClass("fa-chevron-up").addClass("fa-chevron-down");
			})

			target.slideToggle("fast",function() {
				if($(this).is(":visible"))
					$(apis_esd).find(".fa.fa-chevron-up, .fa.fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-up");
				else
					$(apis_esd).find(".fa.fa-chevron-up, .fa.fa-chevron-down").removeClass("fa-chevron-up").addClass("fa-chevron-down");
			});
		}
	});
}

$(document).ready(function() {
	tarteaucitron.init({
			  "privacyUrl": "/conditions-generales-d-utilisation#cookies", /* Privacy policy url */

			  "hashtag": "#tarteaucitron", /* Open the panel with this hashtag */
			  "cookieName": "tarteaucitron", /* Cookie name */
			  "orientation": "top", /* Banner position (top - bottom) */
			  "showAlertSmall": false, /* Show the small banner on bottom right */
			  "cookieslist": false, /* Show the cookie list */

			  "adblocker": false, /* Show a Warning if an adblocker is detected */
			  "AcceptAllCta" : true, /* Show the accept all button when highPrivacy on */
			  "highPrivacy": true, /* Disable auto consent */
			  "handleBrowserDNTRequest": false, /* If Do Not Track == 1, disallow all */

			  "removeCredit": true, /* Remove credit link */
			  "moreInfoLink": true, /* Show more info link */
			  "useExternalCss": false, /* If false, the tarteaucitron.css file will be loaded */

			  //"cookieDomain": ".my-multisite-domaine.fr", /* Shared cookie for multisite */
			  "readmoreLink": "/conditions-generales-d-utilisation#cookies", /* Change the default readmore link */
			});


	//$.infoCookie('Les cookies assurent le bon fonctionnement de nos services. En utilisant ces derniers, vous acceptez l\'utilisation des cookies. <a href="/conditions-generales-d-utilisation#cookies" target="_blank" title="nouvelle fenetre">En savoir plus</a>.',{"class":"bandeau-cookie","position":"top","fixed":false});
	//$.infoCookie('Information: le service sera interrompu de 17h10 à 17h20 pour maintenance. Nous vous prions de bien vouloir nous en excuser.',{"class":"bandeau-cookie","position":"bottom","cookieDuration":0,"delay":0,"cookieName":"maintenance"});

	/* Fait heriter les div class="button" du lien click a href. */
	$(".btn a").each(function(index,a) {
		$(a).parent('.btn').click(function(o) {
			window.location.href=a.href;
		});
	});
	$(".block-expand > .button-expand").click(function() {
		var o=this;
		$(this).parent().find("div.block-expand-hidden").slideToggle("fast",function() {
			if($(this).is(":hidden"))
				$(o).find("span").attr("class","fa fa-caret-down");
			else
				$(o).find("span").attr("class","fa fa-caret-up");
		});
	});
	applyCollapse("body");
});
function initEngine() {
	$('#criteria_location').focus(function() {
		$(this).val("");
		$("#criteria_locationpath").val("");
	});
	/* Assure la complétion des libellés ROME */
	$("#criteria_search").complete(
		{
			call:"/ws/ws_formacodecompletion.php?v=3.1&q=%s",
			onvalidate:"close",
			onselect:function(result){
				$("#criteria_code").val(result.value["code"]);
				$("#criteria_search").val(result.value["label"]);
				//$("input[name=locationpath]").val(result.value[2]);
			},
			charmin:3,
			classover:"over",
			classlist:"codecompletion",
			width: 'default',
			lag: 300
		});
	/* Si touche entrée appuyée sur le champ de recherche, récupère le premier code rome de al liste de complétion */
	$("#criteria_search").keyup(function(key) {
		if(key.which!=13) $("#criteria_code").val("");
		//key.preventDefault();
	});
	/* Assure la complétion des libellé de lieux */
	$("#criteria_location").complete({
		call: "/ws/ws_locationcompletion.php?v=1.91&q=%s",
		onvalidate: "auto",
		onselect:function(result) {
			$("#criteria_location").val(result.value["label"]);
			//$("[name='criteria[locationslug]']").val(result.value["slug"]);
			$("#criteria_locationpath").val(result.value["path"]);
		},
		onchange:function(key,result) {
			if(result.length) $("#criteria_locationpath").val(result[0].value["path"]);
		},
		classover: "over",
		charmin:2,
		classlist: "locationcompletion",
		width: 'default',
		lag: 200
	});

	$(".btn-recherche").click(function() {
		$(this).fadeOut(function() {
			$("#block-recherche").slideDown(function(){
				$(".btn-reduire").fadeIn();
				$(".section-header").animate({
					'background-position-y':'18%',
					'background-position-y':'top'
				},'fast','linear');
			});
		});
	});
	$(".btn-reduire").click(function() {
		$(".btn-reduire").fadeOut();
		$("#block-recherche").slideUp(function() {
			$(".btn-recherche").fadeIn();
			$(".section-header").animate({
				'background-position-y':'top',
				'background-position-y':'18%'
			},'fast','linear');
		});
	});
}

function getCookie(name) {
	var value = "; " + document.cookie;
	var parts = value.split("; " + name + "=");
	if (parts.length == 2) return parts.pop().split(";").shift();
}


function initConnect(obj) {
	if(parseInt(getCookie("notconnected"))!==1) {
		var ifr=document.createElement("iframe");
		ifr.setAttribute("src",obj.uri);
		ifr.setAttribute("style","display:none;");
		document.body.appendChild(ifr);
	}
}

/* Install une Map dans le HTML */
function showMap(target,lat,lng,info) {
	var latitude=lat;
	var longitude=lng;
	var adresse="//api-adresse.data.gouv.fr/search/?q="+encodeURI(info.split("\n").join(', '));
	var zoom=13;
	if(info.substring(0,1)=="-") {
		info=info.substring(1,500);
		zoom=10;
	}

	if(1) {
		$.getJSON(adresse,
			function(res) {
				var coords=[longitude,latitude];
				var map=new mapboxgl.Map({
						container: target,
						style: "https://maps.pole-emploi.fr/styles/klokantech-basic/style.json",
						zoom: zoom,
						center: coords,
						offset: 200
					})
					.addControl(new mapboxgl.NavigationControl(),"top-right");

				
				map.on("load",function() {
					var popup=new mapboxgl.Popup({
					                 closeOnClick: true,
					                 offset: 45
					             })
					            .setLngLat(coords)
					            .setHTML(info.replace(/\n/,"<br>"))
					            .addTo(map);
					new mapboxgl.Marker()
					            .setLngLat(coords)
					            .addTo(map)
					            .setPopup(popup);
				});
			});
	}
}

/* Tooltip pour des conseils d'utilisations */
function adviceToolTip() {
	$('.advicetip').tooltip({'html':'true','animation':'true','trigger':'manual'});
	$('.advicetip').on('shown.bs.tooltip', function () {
		var that=$(this);

		var element=that[0];
		if(element.myShowTooltipEventNum==null){
			element.myShowTooltipEventNum=0;
		}else{
			element.myShowTooltipEventNum++;
		}
		var eventNum=element.myShowTooltipEventNum;

		setTimeout(function(){
			if(element.myShowTooltipEventNum==eventNum){
				that.tooltip('destroy');
			}
			// else skip timeout event
		}, 4000);
	});
	$('.advicetip').tooltip('show');
}
