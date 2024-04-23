function selectall(){
	var inputSavoirEtre = document.getElementsByClassName('savoiretres');
	nbChecked=0;
	for(var i=0; inputSavoirEtre[i]; ++i){
		  if(inputSavoirEtre[i].checked){
			   nbChecked++;
		  }
	}
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('Id_Plateforme').value=="0"){alert("Veuillez renseigner l'UER.");return false;}
		if(document.getElementById('lieu').value==""){alert("Veuillez remplir le lieu.");return false;}
		if(document.getElementById('nombr').value==""){alert("Veuillez remplir le nombre.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Veuillez remplir la date de d�but souhait�e.");return false;}
		if(document.getElementById('metier').value==""){alert("Veuillez s�lectionner un m�tier.");return false;}
		if(document.getElementById('MotifDemande').value==""){alert("Veuillez remplir le motif de la demande.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Veuillez remplir le descriptif du poste.");return false;}
		if(document.getElementById('Diplome').value==""){alert("Veuillez renseigner les dipl�mes.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Veuillez remplir les savoir-faire.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Veuillez remplir les savoirs-�tre.");return false;}
		if(document.getElementById('Langues').value==""){alert("Veuillez remplir les langues souhait�es.");return false;}
		if(document.getElementById('personneAContacter').value=="0"){alert("Veuillez renseigner la personne � contacter.");return false;}
	}
	else{
		if(document.getElementById('Id_Plateforme').value=="0"){alert("Please fill in the platform.");return false;}
		if(document.getElementById('lieu').value==""){alert("Please fill in the place.");return false;}
		if(document.getElementById('nombr').value==""){alert("Please fill in the number.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Please fill in the desired start date.");return false;}
		if(document.getElementById('metier').value==""){alert("Please select a job.");return false;}
		if(document.getElementById('MotifDemande').value==""){alert("Please fill in the reason for the request.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Please fill in the job description.");return false;}
		if(document.getElementById('Diplome').value==""){alert("Please fill in the diplomas.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Please fill in the know-how.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Please complete the social skills.");return false;}
		if(document.getElementById('Langues').value==""){alert("Please fill in the desired languages.");return false;}
		if(document.getElementById('personneAContacter').value=="0"){alert("Please fill in the contact person.");return false;}
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
Liste_Presta = new Array(); // Id_Prestation, Prestation, Id_Plateforme

function RechargerPrestation(){
	var bTrouve = false;
	var selPresta="<select name='Id_Prestation' id='Id_Prestation' class='Id_Prestation' style='width:300px'><option value='0'></option>";
	for(i=0;i<Liste_Presta.length;i++){
		if (Liste_Presta[i][2]==document.getElementById('Id_Plateforme').value){
			selPresta= selPresta + "<option value='"+Liste_Presta[i][0]+"' >"+Liste_Presta[i][1]+"</option>";
			bTrouve=true;
		}
	}
	selPresta =selPresta + "</select>";
	document.getElementById('Id_Prestation').innerHTML=selPresta;
	//Recharge_Responsables();
}
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
		if(document.getElementById('metier').value=="0"){alert("Veuillez s�lectionner un m�tier.");return false;}
		if(document.getElementById('nombr').value==""){alert("Veuillez remplir le nombre.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Veuillez remplir la date de d�but souhait�e.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Veuillez remplir le descriptif du poste.");return false;}
		if(document.getElementById('savoirfaire').value==""){alert("Veuillez remplir les savoir-faire.");return false;}
		if(document.getElementById('savoiretre').value=="" && nbChecked==0){alert("Veuillez remplir les savoirs-�tre.");return false;}
		if(document.getElementById('Langues').value==""){alert("Veuillez remplir les langues souhait�es.");return false;}

	}
	else{
		if(document.getElementById('lieu').value==""){alert("Please fill in the place.");return false;}
		if(document.getElementById('metier').value=="0"){alert("Please select a job.");return false;}
		if(document.getElementById('nombr').value==""){alert("Please fill in the number.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Please fill in the desired start date.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Please fill in the job description.");return false;}
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
		if(document.getElementById('Tel').value==""){alert("Veuillez remplir le num�ro de t�l�phone.");return false;}
		if(document.getElementById('PosteOccupe').value==""){alert("Veuillez remplir le poste occup� actuellement.");return false;}
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