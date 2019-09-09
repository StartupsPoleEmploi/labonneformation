$(document).ready(function() {
	/* Assure la complétion des libellés ROME */
	$("#search-immersion").complete(
		{
			call:"/ws/ws_formacodecompletion.php?v=3.1&q=%s",
			onvalidate:"close",
			onselect:function(result){
				$("#code-immersion").val(result.value["code"]);
				$("#search-immersion").val(result.value["label"]);
				//$("input[name=locationpath]").val(result.value[2]);
			},
			charmin:3,
			classover:"over",
			classlist:"codecompletion",
			width: 'default',
			lag: 500
		});

	/* Si touche entrée appuyée sur le champ de recherche, récupère le premier code rome de al liste de complétion */
	$("#search-immersion").focus(function() {
		$("#search-immersion").val("");
		$("#code-immersion").val("");
		$("#search-immersion").removeClass("error");
	});

	/* Assure la complétion des libellé de lieux */
	$("#location-immersion").focus(function() {
		$("#location-immersion").val("");
		$("#locationpath-immersion").val("");
		$("#location-immersion").removeClass("error");
	});

	/* Assure que les champs de recherche ne sont pas vides sinon -> erreur */
	$("#validation").click(function(e) {
		if ($("#code-immersion").val()=="") {
			$("#search-immersion").addClass("error");
			e.preventDefault();
			return false;
		}
		if ($("#locationpath-immersion").val()=="") {
			$("#location-immersion").addClass("error");
			e.preventDefault();
		}
	});

	$("#search-immersion").keyup(function(key) {
		$("#search-immersion").removeClass("error");
		if(key.which==13) {
			if ($("#code-immersion").val()=="") {
				$("#search-immersion").addClass("error");
				key.preventDefault();
			}
		}
	});

	$("#location-immersion").keyup(function(key) {
		$("#location-immersion").removeClass("error");
		if(key.which==13) {
			if ($("#locationpath-immersion").val()=="") {
				$("#location-immersion").addClass("error");
				key.preventDefault();
			}
		}
	});

	var backTitle=sessionStorage.getItem("backtitle");
	if(typeof backTitle!=="undefined" && backTitle!==null && backTitle!="") {
		$("#backlink").attr('href',sessionStorage.getItem("backlink"))
			.html('<span class="fa fa-chevron-left"></span>&nbsp;'+backTitle)
			.css("display","block");
	}

	// Modale de contact entreprise
	$(".immersion-modal-link").click(function () {
		$("#noemail").text("Non disponible");
		$("#email").text("");
		$("#email").prop("href","");
		$("#nophone").text("Non disponible");
		$("#phone").text("");
		$("#phone").prop("href","");

		var email=$(this).data('email');
		var phone=$(this).data('phone');

		if (email!='') {
			$("#noemail").text("");
			$("#email").text(email);
			$("#email").prop("href","mailto:"+email);
		}

		if (phone!='') {
			$("#nophone").text("");
			$("#phone").text(phone);
			$("#phone").prop("href","tel:"+phone);
		}
	})
});
