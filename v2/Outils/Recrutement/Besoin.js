function selectall(){
	var inputSavoirEtre = document.getElementsByClassName('savoiretres');
	nbChecked=0;
	for(var i=0; inputSavoirEtre[i]; ++i){
		  if(inputSavoirEtre[i].checked){
			   nbChecked++;
		  }
	}
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('lieu').value==""){alert("Veuillez remplir le lieu.");return false;}
		if(document.getElementById('metier').value=="0"){alert("Veuillez sélectionner un métier.");return false;}
		if(document.getElementById('nombr').value==""){alert("Veuillez remplir le nombre.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Veuillez remplir la date de début souhaitée.");return false;}
		if(document.getElementById('Id_TypeHoraire').value=="0"){alert("Veuillez remplir l'horaire équipe.");return false;}
		if(document.getElementById('etatPoste').value==""){alert("Veuillez remplir l'état du poste.");return false;}
		if(document.getElementById('categorieProfessionnelle').value==""){alert("Veuillez remplir le statut.");return false;}
		if(document.getElementById('salaire').value==""){alert("Veuillez remplir le salaire.");return false;}
		if(document.getElementById('IGD').value==""){alert("Veuillez remplir l'IGD.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Veuillez remplir le descriptif du poste.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Veuillez remplir les diplômes.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Veuillez remplir les savoir-faire.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Veuillez remplir les savoirs-être.");return false;}
		if(document.getElementById('Langues').value==""){alert("Veuillez remplir les langues souhaitées.");return false;}
	}
	else{
		if(document.getElementById('lieu').value==""){alert("Please fill in the place.");return false;}
		if(document.getElementById('metier').value=="0"){alert("Please select a job.");return false;}
		if(document.getElementById('nombr').value==""){alert("Please fill in the number.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Please fill in the desired start date.");return false;}
		if(document.getElementById('Id_TypeHoraire').value=="0"){alert("Please fill in the team schedule.");return false;}
		if(document.getElementById('etatPoste').value==""){alert("Please fill in the post status.");return false;}
		if(document.getElementById('categorieProfessionnelle').value==""){alert("Please fill in the status.");return false;}
		if(document.getElementById('salaire').value==""){alert("Please fill in the salary.");return false;}
		if(document.getElementById('IGD').value==""){alert("Please complete the IGD.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Please fill in the job description.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Please complete the diplomas.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Please fill in the know-how.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Please complete the social skills.");return false;}
		if(document.getElementById('Langues').value==""){alert("Please fill in the desired languages.");return false;}
	}
}

function afficherDuree(){
	/*
	if(document.getElementById("posteDefinitif").value==1){
		document.getElementById("duree1").style.display="none";
		document.getElementById("duree2").style.display="none";
	}
	else{
		document.getElementById("duree1").style.display="";
		document.getElementById("duree2").style.display="";
	}*/
}
function afficherDureeV2(){
	/*
	if(document.getElementById("posteDefinitif").value==1){
		document.getElementById("duree1").style.display="none";
		document.getElementById("duree2").style.display="none";
		document.getElementById("trDeploiement").style.display="none";
	}
	else{
		document.getElementById("duree1").style.display="";
		document.getElementById("duree2").style.display="";
		if(document.getElementById("RHParis").value==1){
			document.getElementById("trDeploiement").style.display="";
		}
		else{
			document.getElementById("trDeploiement").style.display="none";
		}
		
	}*/
}

Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
Liste_Personne = new Array(); //Id, Personne, Id_Prestation, Pole

function Recharge_Responsables(){
	var bTrouve = false;
	
	var sel="<table>";
	var ValidateurN1="<tr><td>";
	var ValidateurN2="<tr><td>";
	var ValidateurN3="<tr><td>";
	for(var i=0;i<Liste_Poste_Prestation.length;i++)
	{
		if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value)
		{
			switch (Liste_Poste_Prestation[i][1])	//Id_Poste
			{
				case 7:
					ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][2] + "; ";
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
}

function EnregistrerModif(type){
	var inputSavoirEtre = document.getElementsByClassName('savoiretres');
	nbChecked=0;
	for(var i=0; inputSavoirEtre[i]; ++i){
		  if(inputSavoirEtre[i].checked){
			   nbChecked++;
		  }
	}
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('lieu').value==""){alert("Veuillez remplir le lieu.");return false;}
		if(document.getElementById('metier').value=="0"){alert("Veuillez sélectionner un métier.");return false;}
		if(document.getElementById('nombr').value==""){alert("Veuillez remplir le nombre.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Veuillez remplir la date de début souhaitée.");return false;}
		if(document.getElementById('Id_TypeHoraire').value=="0"){alert("Veuillez remplir l'horaire équipe.");return false;}
		if(document.getElementById('etatPoste').value==""){alert("Veuillez remplir l'état du poste.");return false;}
		if(document.getElementById('categorieProfessionnelle').value==""){alert("Veuillez remplir le statut.");return false;}
		if(document.getElementById('salaire').value==""){alert("Veuillez remplir le salaire.");return false;}
		if(document.getElementById('IGD').value==""){alert("Veuillez remplir l'IGD.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Veuillez remplir le descriptif du poste.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Veuillez remplir les prérequis particuliers.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Veuillez remplir les savoir-faire.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Veuillez remplir les savoirs-être.");return false;}
		if(document.getElementById('Langues').value==""){alert("Veuillez remplir les langues souhaitées.");return false;}

	}
	else{
		if(document.getElementById('lieu').value==""){alert("Please fill in the place.");return false;}
		if(document.getElementById('metier').value=="0"){alert("Please select a job.");return false;}
		if(document.getElementById('nombr').value==""){alert("Please fill in the number.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Please fill in the desired start date.");return false;}
		if(document.getElementById('Id_TypeHoraire').value=="0"){alert("Please fill in the team schedule.");return false;}
		if(document.getElementById('etatPoste').value==""){alert("Please fill in the post status.");return false;}
		if(document.getElementById('categorieProfessionnelle').value==""){alert("Please fill in the status.");return false;}
		if(document.getElementById('salaire').value==""){alert("Please fill in the salary.");return false;}
		if(document.getElementById('IGD').value==""){alert("Please complete the IGD.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Please fill in the job description.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Please fill in the special prerequisites.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Please fill in the know-how.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Please complete the social skills.");return false;}
		if(document.getElementById('Langues').value==""){alert("Please fill in the desired languages.");return false;}

	}
	
	if(type=="Responsable"){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrer2").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
	else if(type=="Operation"){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrerOperation' name='btnEnregistrerOperation' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrerOperation").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
	else if(type=="Plateforme"){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrerPlateforme' name='btnEnregistrerPlateforme' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrerPlateforme").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
	else if(type=="Recrutement"){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrerRecrutement' name='btnEnregistrerRecrutement' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrerRecrutement").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
	else if(type=="RecrutementMAJ"){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrerRecrutementMAJ' name='btnEnregistrerRecrutementMAJ' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrerRecrutementMAJ").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
	else if(type=="PlateformeMAJ"){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrerPlateformeMAJ' name='btnEnregistrerPlateformeMAJ' value='Enregistrer'>";
		document.getElementById('Ajouter').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnEnregistrerPlateformeMAJ").dispatchEvent(evt);
		document.getElementById('Ajouter').innerHTML="";
	}
}

function AfficherRefus(){
	if(document.getElementById('etatValidation').value==-1){
		document.getElementById('tdRaison1').style.display="";
		document.getElementById('tdRaison2').style.display="";
	}
	else{
		document.getElementById('tdRaison1').style.display="none";
		document.getElementById('tdRaison2').style.display="none";
	}
}

function AfficherRefusApprobation(){
	if(document.getElementById('etatApprobation').value==-1){
		document.getElementById('tdRaisonA1').style.display="";
		document.getElementById('tdRaisonA2').style.display="";
	}
	else{
		document.getElementById('tdRaisonA1').style.display="none";
		document.getElementById('tdRaisonA2').style.display="none";
	}
	if(document.getElementById('etatApprobation').value==1){
		document.getElementById('trDeploiement').style.display="";
	}
	else{
		document.getElementById('trDeploiement').style.display="none";
	}
}

function AfficherRefusRecrutement(){
	if(document.getElementById('etatRecrutement').value==-1){
		document.getElementById('tdRaisonR1').style.display="";
		document.getElementById('tdRaisonR2').style.display="";
	}
	else{
		document.getElementById('tdRaisonR1').style.display="none";
		document.getElementById('tdRaisonR2').style.display="none";
	}
}

Liste_PrestaPolePostule = new Array();
function RechargerPrestationPostule(){
	var bTrouve = false;
	var selPresta="<select name='Id_PrestationPole' id='Id_PrestationPole' class='Id_PrestationPole' style='width:100px'>";
	selPresta= selPresta + "<option value='0'></option>";
	for(i=0;i<Liste_PrestaPolePostule.length;i++){
		if (Liste_PrestaPolePostule[i][2]==document.getElementById('plateforme').value){
			selPresta= selPresta + "<option value='"+Liste_PrestaPolePostule[i][0];
			selPresta= selPresta + "' >"+Liste_PrestaPolePostule[i][1]+"</option>";
			bTrouve=true;
		}
	}
	selPresta =selPresta + "</select>";
	document.getElementById('Id_PrestationPole').innerHTML=selPresta;
}
function VerifChampsPostuler(){
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('Email').value==""){alert("Veuillez remplir l'adresse email.");return false;}
		if(document.getElementById('Tel').value==""){alert("Veuillez remplir le numéro de téléphone.");return false;}
		if(document.getElementById('PosteOccupe').value==""){alert("Veuillez remplir le poste occupé actuellement.");return false;}
		if(document.getElementById('plateforme').value=="0"){alert("Veuillez remplir la plateforme.");return false;}
		if(document.getElementById('Id_PrestationPole').value=="0"){alert("Veuillez remplir la prestation.");return false;}
		if(document.getElementById('responsable').value==""){alert("Veuillez remplir le responsable actuel.");return false;}
	}
	else{
		if(document.getElementById('Email').value==""){alert("Please fill in the email address.");return false;}
		if(document.getElementById('Tel').value==""){alert("Please fill the phone number.");return false;}
		if(document.getElementById('PosteOccupe').value==""){alert("Please fill the position currently occupied.");return false;}
		if(document.getElementById('plateforme').value=="0"){alert("Please fill the platform.");return false;}
		if(document.getElementById('Id_PrestationPole').value=="0"){alert("Please fill the site.");return false;}
		if(document.getElementById('responsable').value==""){alert("Please fill the responsible.");return false;}
	}
}