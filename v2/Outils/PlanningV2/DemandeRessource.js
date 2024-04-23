function selectall(){
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('lieu').value==""){alert("Veuillez remplir le lieu.");return false;}
		if(document.getElementById('Id_Metier').value=="0"){alert("Veuillez sélectionner un métier.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Veuillez remplir la date de démarrage souhaitée.");return false;}
		if(document.getElementById('duree').value==""){alert("Veuillez remplir la durée.");return false;}
		if(document.getElementById('nombr').value==""){alert("Veuillez remplir le nombre.");return false;}
		if(document.getElementById('motifDemande').value==""){alert("Veuillez remplir le motif de la demande.");return false;}
		if(document.getElementById('horaire').value==""){alert("Veuillez remplir l'horaire équipe.");return false;}
		if(document.getElementById('motifContrat').value==""){alert("Veuillez remplir le motif du contrat.");return false;}
		if(document.getElementById('motifContratSuite').value==""){alert("Veuillez remplir le motif du contrat.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Veuillez remplir le descriptif du poste.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Veuillez remplir les prérequis particuliers.");return false;}
		if(document.getElementById('Logiciel').value==""){alert("Veuillez remplir les logiciels souhaitées.");return false;}
		if(document.getElementById('Langue').value==""){alert("Veuillez remplir les langues souhaitées.");return false;}
		if(document.getElementById('ExperienceDesPostes').value==""){alert("Veuillez remplir l'expérience souhaitée.");return false;}
	}
	else{
		if(document.getElementById('lieu').value==""){alert("Please fill in the place.");return false;}
		if(document.getElementById('Id_Metier').value=="0"){alert("Please select a job.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Please fill in the desired start date.");return false;}
		if(document.getElementById('duree').value==""){alert("Please fill in the duration.");return false;}
		if(document.getElementById('nombr').value==""){alert("Please fill in the number.");return false;}
		if(document.getElementById('motifDemande').value==""){alert("Please fill in the reason for the request.");return false;}
		if(document.getElementById('horaire').value==""){alert("Please fill in the team schedule.");return false;}
		if(document.getElementById('motifContrat').value==""){alert("Please fill in the reason for the contract.");return false;}
		if(document.getElementById('motifContratSuite').value==""){alert("Please fill in the reason for the contract.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Please fill in the job description.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Please fill in the special prerequisites.");return false;}
		if(document.getElementById('Logiciel').value==""){alert("Please fill in the desired software.");return false;}
		if(document.getElementById('Langue').value==""){alert("Please fill in the desired languages.");return false;}
		if(document.getElementById('ExperienceDesPostes').value==""){alert("Please complete the desired experience.");return false;}
	}
}

Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
Liste_Personne = new Array(); //Id, Personne, Id_Prestation, Pole

function Recharge_Responsables(){
	var bTrouve = false;

	if(document.getElementById('Langue').value=="FR"){resp="Responsables ";}
	else{resp="Responsibles ";}
	
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
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + resp + " :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
}

function EnregistrerModif(){
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('lieu').value==""){alert("Veuillez remplir le lieu.");return false;}
		if(document.getElementById('Id_Metier').value=="0"){alert("Veuillez sélectionner un métier.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Veuillez remplir la date de démarrage souhaitée.");return false;}
		if(document.getElementById('duree').value==""){alert("Veuillez remplir la durée.");return false;}
		if(document.getElementById('nombr').value==""){alert("Veuillez remplir le nombre.");return false;}
		if(document.getElementById('motifDemande').value==""){alert("Veuillez remplir le motif de la demande.");return false;}
		if(document.getElementById('horaire').value==""){alert("Veuillez remplir l'horaire équipe.");return false;}
		if(document.getElementById('motifContrat').value==""){alert("Veuillez remplir le motif du contrat.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Veuillez remplir le descriptif du poste.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Veuillez remplir les prérequis particuliers.");return false;}
	}
	else{
		if(document.getElementById('lieu').value==""){alert("Please fill in the place.");return false;}
		if(document.getElementById('Id_Metier').value=="0"){alert("Please select a job.");return false;}
		if(document.getElementById('dateSouhaitee').value==""){alert("Please fill in the desired start date.");return false;}
		if(document.getElementById('duree').value==""){alert("Please fill in the duration.");return false;}
		if(document.getElementById('nombr').value==""){alert("Please fill in the number.");return false;}
		if(document.getElementById('motifDemande').value==""){alert("Please fill in the reason for the request.");return false;}
		if(document.getElementById('horaire').value==""){alert("Please fill in the team schedule.");return false;}
		if(document.getElementById('motifContrat').value==""){alert("Please fill in the reason for the contract.");return false;}
		if(document.getElementById('DescriptifPoste').value==""){alert("Please fill in the job description.");return false;}
		if(document.getElementById('Prerequis').value==""){alert("Please fill in the special prerequisites.");return false;}
	}
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function AfficherRefus(){
	if(document.getElementById('statut').value==0){
		document.getElementById('trRaison').style.display="";
		document.getElementById('trCommentaire').style.display="";
	}
	else{
		document.getElementById('trRaison').style.display="none";
		document.getElementById('trCommentaire').style.display="none";
	}
}

function EnregistrerModifRecrut(){
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrerRecrut2' name='btnEnregistrerRecrut2' value='Enregistrer'>";
	document.getElementById('Ajouter2').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrerRecrut2").dispatchEvent(evt);
	document.getElementById('Ajouter2').innerHTML="";
}