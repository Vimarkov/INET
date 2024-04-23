Liste_PrestaPole = new Array();

function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}

function nombre(champ){
	var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
	var verif;
	var points = 0; /* Supprimer cette ligne */

	for(x = 0; x < champ.value.length; x++)
	{
	verif = chiffres.test(champ.value.charAt(x));
	if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
	if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
	if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
	}
}
function Enregistrer(){
	var valide = true;
	var Elements_Objets = document.getElementsByClassName("objets");
	var Elements_Sieges = document.getElementsByClassName("sieges");
	var Elements_SiegesAutres = document.getElementsByClassName("siegesAutres");
	var Elements_Natures = document.getElementsByClassName("natures");
	var Elements_NaturesAutres = document.getElementsByClassName("naturesAutres");
	var checkedObjet=false;
	var checkedSiege=false;
	var checkedSiegeAutres=false;
	var checkedNature=false;
	var checkedNatureAutres=false;
	
	for(var k=0, l=Elements_Objets.length; k<l; k++){
		if(Elements_Objets[k].value!=""){
			checkedObjet=true;
		}
	}
	for(var k=0, l=Elements_Sieges.length; k<l; k++){
		if(Elements_Sieges[k].checked==true){
			checkedSiege=true;
		}
	}
	for(var k=0, l=Elements_SiegesAutres.length; k<l; k++){
		if(Elements_SiegesAutres[k].value!=""){
			checkedSiegeAutres=true;
		}
	}
	for(var k=0, l=Elements_Natures.length; k<l; k++){
		if(Elements_Natures[k].checked==true){
			checkedNature=true;
		}
	}
	for(var k=0, l=Elements_NaturesAutres.length; k<l; k++){
		if(Elements_NaturesAutres[k].value!=""){
			checkedNatureAutres=true;
		}
	}
	
	if(document.getElementById('RH').value=="0"){
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.dateAT.value==""){valide=false;alert("Veuillez compléter la date de l\'AT");return false;}
			if(formulaire.heureAT.value==""){valide=false;alert("Veuillez compléter l\'heure de l\'AT");return false;}
			if(formulaire.heureDebut1.value=="" || formulaire.heureFin1.value==""){valide=false;alert("Veuillez compléter les horaires de travail le jour de l\'AT");return false;}
			if(formulaire.lieu.value==""){valide=false;alert("Veuillez compléter le lieu exact de l\'accident");return false;}
			if(formulaire.Id_PrestationPole.value=="0_0"){valide=false;alert("Veuillez renseigner la prestation");return false;}
			if(formulaire.activiteVictime.value==""){valide=false;alert("Veuillez compléter l\'activité de la victime");return false;}
			if(formulaire.natureAccident.value==""){valide=false;alert("Veuillez compléter la nature de l\'accident");return false;}
			if(checkedObjet==false){valide=false;alert("Veuillez compléter l\'objet dont le contact a blessé");return false;}
			if(checkedSiege==false && checkedSiegeAutres==false){valide=false;alert("Veuillez cocher le(s) siège(s) des lésions");return false;}
			if(formulaire.temoin.value==""){valide=false;alert("Veuillez renseigner le témoin");return false;}
			if(formulaire.autresInfos.value==""){valide=false;alert("Veuillez renseigner les actions de sécurisation immédiates");return false;}
		}
		else{
			if(formulaire.dateAT.value==""){valide=false;alert("Please complete the date of the accident");return false;}
			if(formulaire.heureAT.value==""){valide=false;alert("Please complete the time of the accident");return false;}
			if(formulaire.heureDebut1.value=="" || formulaire.heureFin1.value==""){valide=false;alert("Please complete the working hours on the day of the accident");return false;}
			if(formulaire.lieu.value==""){valide=false;alert("Please complete the exact location of the accident");return false;}
			if(formulaire.Id_PrestationPole.value=="0_0"){valide=false;alert("Please complte the site");return false;}
			if(formulaire.activiteVictime.value==""){valide=false;alert("Please complete the activity of the victim");return false;}
			if(formulaire.natureAccident.value==""){valide=false;alert("Please complete the nature of the accident");return false;}
			if(checkedObjet==false){valide=false;alert("Please complete the object whose contact hurt");return false;}
			if(checkedSiege==false && checkedSiegeAutres==false){valide=false;alert("Please complete the seat(s) of the injuries");return false;}
			if(formulaire.temoin.value==""){valide=false;alert("Please inform the witness");return false;}	
			if(formulaire.autresInfos.value==""){valide=false;alert("Please fill in the immediate security actions");return false;}
		}
	}
	else{
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.dateAT.value==""){valide=false;alert("Veuillez compléter la date de l\'AT");return false;}
			if(formulaire.heureAT.value==""){valide=false;alert("Veuillez compléter l\'heure de l\'AT");return false;}
			if(formulaire.adresse.value==""){valide=false;alert("Veuillez compléter l\'adresse de la personne");return false;}
			if(formulaire.cp.value==""){valide=false;alert("Veuillez compléter le code postal de la personne");return false;}
			if(formulaire.ville.value==""){valide=false;alert("Veuillez compléter la ville de la personne");return false;}
			if(formulaire.numSecu.value==""){valide=false;alert("Veuillez compléter le numéro de sécurité social de la personne");return false;}
			if(formulaire.dateNaissance.value==""){valide=false;alert("Veuillez compléter la date de naissance de la personne");return false;}
			if(formulaire.anciennete.value==""){valide=false;alert("Veuillez compléter l\'ancienneté de la personne");return false;}
			if(formulaire.heureDebut1.value=="" || formulaire.heureFin1.value==""){valide=false;alert("Veuillez compléter les horaires de travail le jour de l\'AT");return false;}
			if(formulaire.lieu.value==""){valide=false;alert("Veuillez compléter le lieu exact de l\'accident");return false;}
			if(formulaire.siretClient.value==""){valide=false;alert("Veuillez compléter le SIRET du client");return false;}
			if(formulaire.activiteVictime.value==""){valide=false;alert("Veuillez compléter l\'activité de la victime");return false;}
			if(formulaire.natureAccident.value==""){valide=false;alert("Veuillez compléter la nature de l\'accident");return false;}
			if(checkedObjet==false){valide=false;alert("Veuillez compléter l\'objet dont le contact a blessé");return false;}
			if(checkedSiege==false && checkedSiegeAutres==false){valide=false;alert("Veuillez cocher le(s) siège(s) des lésions");return false;}
			if(checkedNature==false && checkedNatureAutres==false){valide=false;alert("Veuillez cocher la nature des lésions");return false;}
			if(formulaire.evacuationVers.value==""){valide=false;alert("Veuillez compléter le champs Evacuation vers");return false;}
			if(formulaire.autreVictime.value==""){valide=false;alert("Veuillez compléter le champs Autre victime");return false;}
			if(formulaire.tiersResponsable.value==""){valide=false;alert("Veuillez compléter le champs Tiers responsable");return false;}
			if(formulaire.temoin.value==""){valide=false;alert("Veuillez renseigner le témoin");return false;}
			if(formulaire.coordonnees.value==""){valide=false;alert("Veuillez compléter les coordonnées du témoin");return false;}
			if(formulaire.personne1.value==""){valide=false;alert("Veuillez renseigner le nom de la première personne avertie");return false;}
			if(formulaire.dateConnaissanceAT.value==""){valide=false;alert("Veuillez compléter la date de connaissance de l'accident");return false;}
			if(formulaire.heureConnaisanceAT.value==""){valide=false;alert("Veuillez compléter l'heure de connaissance de l'accident");return false;}
			if(formulaire.doutes.value==""){valide=false;alert("Veuillez compléter le champs Doutes");return false;}
			if(formulaire.autresInfos.value==""){valide=false;alert("Veuillez renseigner les actions de sécurisation immédiates");return false;}
			if(formulaire.Id_PrestationPole.value=="0_0"){valide=false;alert("Veuillez renseigner la prestation");return false;}
		}
		else{
			if(formulaire.dateAT.value==""){valide=false;alert("Please complete the date of the accident");return false;}
			if(formulaire.heureAT.value==""){valide=false;alert("Please complete the time of the accident");return false;}
			if(formulaire.adresse.value==""){valide=false;alert("Please complete the address of the person");return false;}
			if(formulaire.cp.value==""){valide=false;alert("Please complete the postal code of the person");return false;}
			if(formulaire.ville.value==""){valide=false;alert("Please complete the city of the person");return false;}
			if(formulaire.numSecu.value==""){valide=false;alert("Please complete the social security number of the person");return false;}
			if(formulaire.dateNaissance.value==""){valide=false;alert("Please complete the date of birth of the person");return false;}
			if(formulaire.anciennete.value==""){valide=false;alert("Please complete the seniority of the person");return false;}
			if(formulaire.heureDebut1.value=="" || formulaire.heureFin1.value==""){valide=false;alert("Please complete the working hours on the day of the accident");return false;}
			if(formulaire.lieu.value==""){valide=false;alert("Please complete the exact location of the accident");return false;}
			if(formulaire.siretClient.value==""){valide=false;alert("Please complete the customer's SIRET");return false;}
			if(formulaire.activiteVictime.value==""){valide=false;alert("Please complete the activity of the victim");return false;}
			if(formulaire.natureAccident.value==""){valide=false;alert("Please complete the nature of the accident");return false;}
			if(checkedObjet==false){valide=false;alert("Please complete the object whose contact hurt");return false;}
			if(checkedSiege==false && checkedSiegeAutres==false){valide=false;alert("Please complete the seat(s) of the injuries");return false;}
			if(checkedNature==false && checkedNatureAutres==false){valide=false;alert("Please check the nature of the lesions");return false;}
			if(formulaire.evacuationVers.value==""){valide=false;alert("Please fill in the field Evacuation to");return false;}
			if(formulaire.autreVictime.value==""){valide=false;alert("Please fill in the Other victim field");return false;}
			if(formulaire.tiersResponsable.value==""){valide=false;alert("Please fill in the responsible third party field");return false;}
			if(formulaire.temoin.value==""){valide=false;alert("Please inform the witness");return false;}
			if(formulaire.coordonnees.value==""){valide=false;alert("Please fill in the details of the witness");return false;}
			if(formulaire.personne1.value==""){valide=false;alert("Please fill in the name of the first informed person");return false;}
			if(formulaire.dateConnaissanceAT.value==""){valide=false;alert("Please fill in the date of the accident");return false;}
			if(formulaire.heureConnaisanceAT.value==""){valide=false;alert("Please complete the time of the accident");return false;}
			if(formulaire.doutes.value==""){valide=false;alert("Please fill in the doubts field");return false;}
			if(formulaire.autresInfos.value==""){valide=false;alert("Please fill in the immediate security actions");return false;}	
			if(formulaire.Id_PrestationPole.value=="0_0"){valide=false;alert("Please complte the site");return false;}
		}
	}
	
	if(valide==true){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrer2").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
}

function FermerEtRecharger(Menu)
{
	window.opener.location="Liste_AT.php?Menu="+Menu;
	window.close();
}

function RechargerPrestation(Id_PrestationPole){
	var bTrouve = false;
	var selPresta="<select name='Id_PrestationPole' id='Id_PrestationPole' class='Id_PrestationPole' onchange='AffecterSIRET()' style='width:400px'>";
	selPresta= selPresta + "<option value='0_0'></option>";
	for(i=0;i<Liste_PrestaPole.length;i++){
		if (Liste_PrestaPole[i][4]==document.getElementById('Id_Plateforme').value || document.getElementById('Id_Plateforme').value==0){
			selPresta= selPresta + "<option value='"+Liste_PrestaPole[i][0]+"_"+Liste_PrestaPole[i][1];
			pole="";
			if(Liste_PrestaPole[i][1]!="0"){
				pole=" - "+Liste_PrestaPole[i][3];
			}
			selectedPresta="";
			if(Id_PrestationPole==Liste_PrestaPole[i][0]+"_"+Liste_PrestaPole[i][1]){selectedPresta= "selected";}
			selPresta= selPresta + "' "+selectedPresta+" >"+Liste_PrestaPole[i][2]+pole+"</option>";
			bTrouve=true;
		}
	}
	selPresta =selPresta + "</select>";
	document.getElementById('Id_PrestationPole').innerHTML=selPresta;
	
	AffecterSIRET();
}

function AffecterSIRET(){
	document.getElementById('siretClient').value="";
	if(document.getElementById("Est_Interim").value==0){
		if(document.getElementById('Id_Plateforme').value==4){document.getElementById('siretClient').value = "353522204 00067";}
		else if(document.getElementById('Id_PrestationPole').value=='355_0'){document.getElementById('siretClient').value = "353522204 00075";}
		else{document.getElementById('siretClient').value = "353522204 00083";}
	}
}