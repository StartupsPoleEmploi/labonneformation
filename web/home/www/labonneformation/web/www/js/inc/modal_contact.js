
/* fonction qui génère le formulaire de mail, ce formulaire peut-être généré depuis différentes sources modifiant son comportement */
function afficherFormulaireMail(adresse_expediteur, obj, mes, copie='', origine="home",type) 
{
	
	switch (origine){
		case "home":
			$("#confirmationhead").hide();
			$("#confirmationbody").hide();
			$("#envoiok").hide();
			$("#envoiko").hide();
			$("#envoibody").hide();
			$("#alerteadresse").hide();
			$("#alerteobjet").hide();
			$("#alertemessage").hide();
			$("#contact-orga-copie-form").removeAttr('checked');
			var message_rand = Math.floor(Math.random()*2);
			if (message_rand == 1) {
				var placeholder = "Exemple :\nBonjour, je vous contacte pour savoir comment trouver une formation...";
			} else {
				var placeholder = "Exemple :\nBonjour, je vous contacte pour savoir comment faire référencer les formations de notre organisme sur votre site...";
			}
			$("#contact-orga-message-form").attr("placeholder", placeholder);
			var adresse_expediteur="";
			var objet=obj;
			var message="";
			break;

		case "orga":
			$("#confirmationhead").hide();
			$("#confirmationbody").hide();
			$("#envoiok").hide();
			$("#envoiko").hide();
			$("#envoibody").hide();
			$("#alerteadresse").hide();
			$("#alerteobjet").hide();
			$("#alertemessage").hide();
			$("#contact-orga-copie-form").removeAttr('checked');
			var adresse_expediteur="";
			var objet=obj;
			var message=mes;
			break;

		case "retour":
			$("#confirmationhead").hide();
			$("#confirmationbody").hide();
			$("#alerteadresse").hide();
			$("#alerteobjet").hide();
			$("#alertemessage").hide();
			var objet=obj;
			var message=mes;
			break;

		case "adressenonvalide":
			$("#alerteadresse").show();
			$("#alerteobjet").hide();
			$("#alertemessage").hide();
			var objet=obj;
			var message=mes;
			break;
		
		case "objetnonvalide":
			$("#alerteadresse").hide();
			$("#alertemessage").hide();
			$("#alerteobjet").show();
			var objet=obj;
			var message=mes;
			break;

		case "messagenonvalide":
			$("#alerteadresse").hide();
			$("#alerteobjet").hide();
			$("#alertemessage").show();
			var objet=obj;
			var message=mes;
			break;
	}

	$("#contact-orga-adresse-exp-form").val(adresse_expediteur);
	$("#contact-orga-objet-form").val(objet);
	$("#contact-orga-message-form").text(message);
	if (type == "orga") {
		$("#formulaireheadlbf").hide();
		$("#formulaireheadorga").show();
	}
	else if (type == "lbf") {
		$("#formulaireheadorga").hide();
		$("#formulaireheadlbf").show();
	}
	$("#formulairebody").show();

	$("#boutonconfirmer").click(function () {
		checkEmail(type);
	});
}

/* fonction qui vérifie que l'adresse mail entrée est valide et que les champs ne sont pas vides et qui redirige en fonction */
function checkEmail(type)
{
	var adresse_expediteur=$("#contact-orga-adresse-exp-form").val();
	var objet=$("#contact-orga-objet-form").val();
	var message=$("#contact-orga-message-form").val();
	var copie=$("#contact-orga-copie-form").is(":checked");

	if(copie) {
		var checked='checked';
	} else {
		var checked='';
	}

	var regexp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	var emailcorrect=regexp.test(String(adresse_expediteur).toLowerCase());

	if (!emailcorrect) {
		afficherFormulaireMail(adresse_expediteur,objet,message,checked,'adressenonvalide',type);
	}
	else if (objet=="") {
		afficherFormulaireMail(adresse_expediteur,objet,message,checked,'objetnonvalide',type);
	}
	else if (message=="") {
		afficherFormulaireMail(adresse_expediteur,objet,message,checked,'messagenonvalide',type);
	}
	else {
		var confirmation=true;
		if(confirmation) {
			confirmerMail(type);
		} else {
			track('CLIC MAIL CONTACT ORGANISME');
			envoyerMail(type);
		}
	}
}

/* fonction qui génère une confirmation du mail en fonction des inputs de l'utilisateur */
function confirmerMail(type)
{		
	var adresse_expediteur=$("#contact-orga-adresse-exp-form").val();
	var objet=$("#contact-orga-objet-form").val();
	var message=$("#contact-orga-message-form").val();
	var copie=$("#contact-orga-copie-form").is(":checked");

	if(copie) {
		var checked='checked';
		$("#contact-orga-copie-confirm").val("true");
	} else {
		var checked='';
		$("#contact-orga-copie-confirm").val("false");
	}

	$("#contact-orga-objet-confirm").text(objet);
	$("#contact-orga-message-confirm").text(message);
	$("#contact-orga-adresse-exp-confirm").val(adresse_expediteur);
	$("#contact-orga-copie-confirm").val(copie);
	
	$("#formulairebody").hide();
	$("#confirmationbody").show();
	$("#formulaireheadorga").hide();
	$("#formulaireheadlbf").hide();
	$("#confirmationhead").show();

	$("#boutonretour").click(function() {
		afficherFormulaireMail(adresse_expediteur,objet,message,checked,'retour',type);
	});
	$("#boutonenvoyer").unbind("click");
	$("#boutonenvoyer").click(function () {
		envoyerMail(type);
	});
}

