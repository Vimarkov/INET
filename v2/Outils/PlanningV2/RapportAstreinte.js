Liste_Pole = new Array();
Liste_PrestationPole = new Array();

Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
Liste_Personne = new Array(); //Id, Personne, Id_Prestation, Pole
function Recharge_Responsables(){
	var bTrouve = false;
	//Recharge les poles de la prestation selectionnée
	var selPole="<select name='Id_Pole' id='Id_Pole' onchange='Recharge_ResponsablesP();'>";
	for(i=0;i<Liste_Pole_Prestation.length;i++){
		if (Liste_Pole_Prestation[i][1]==document.getElementById('Id_Prestation').value){
			selPole= selPole + "<option value="+Liste_Pole_Prestation[i][0];
			selPole= selPole + ">"+Liste_Pole_Prestation[i][2]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selPole= selPole + "<option value=0></option>";}
	selPole =selPole + "</select>";
	document.getElementById('Id_Pole').innerHTML=selPole;
	
	if(document.getElementById('Langue').value=="FR"){resp="Responsables ";}
	else{resp="Responsibles ";}
	
	var sel="<table>";
	var ValidateurN1="<tr><td>";
	var ValidateurN2="<tr><td>";
	for(var i=0;i<Liste_Poste_Prestation.length;i++)
	{
		if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value && Liste_Poste_Prestation[i][4]==document.getElementById('Id_Pole').value)
		{
			switch (Liste_Poste_Prestation[i][1])	//Id_Poste
			{
				case 2:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + resp + "N+1 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				case 3:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + resp + "N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
	if(document.getElementById('Menu').value!=2){
		Recharge_Personnel();
	}
}
function Recharge_ResponsablesP(){
	var sel="<table>";
	var ValidateurN1="<tr><td>";
	var ValidateurN2="<tr><td>";
	
	if(document.getElementById('Langue').value=="FR"){resp="Responsables ";}
	else{resp="Responsibles ";}
	for(var i=0;i<Liste_Poste_Prestation.length;i++)
	{
		if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value && Liste_Poste_Prestation[i][4]==document.getElementById('Id_Pole').value)
		{
			switch (Liste_Poste_Prestation[i][1])	//Id_Poste
			{
				case 2:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + resp + "N+1 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				case 3:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + resp + "N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
	Recharge_Personnel();
}

function Recharge_Personnel(){
	var bTrouve = false;
	//Recharge les personnes de la prestation et le pole selectionnée
	var selPers="<select name='Id_Personne' id='Id_Personne' onDblclick='ajouter();'>";
	for(i=0;i<Liste_Personne.length;i++){
		if ((Liste_Personne[i][2]==document.getElementById('Id_Prestation').value && Liste_Personne[i][3]==document.getElementById('Id_Pole').value) || document.getElementById('Id_Prestation').value==-1){
			selPers= selPers + "<option value="+Liste_Personne[i][0];
			selPers= selPers + ">"+Liste_Personne[i][1]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selPers= selPers + "<option value=0></option>";}
	selPers =selPers + "</select>";
	document.getElementById('Id_Personne').innerHTML=selPers;
	
	document.getElementById('PersonneSelect').options.length=0;
}

function Recharge_PolePresta(){
	var i;
	var sel="";
	var isElement = false;
	//Prestation
	sel ="<select id='pole' size='1' name='pole' style='width: 30%;'>";
	for(i=0;i<Liste_PrestationPole.length;i++){
		if (Liste_PrestationPole[i][0]==document.getElementById('personne').value && Liste_PrestationPole[i][1]==document.getElementById('prestation').value){
			sel= sel + "<option value='"+Liste_PrestationPole[i][2];
			sel= sel + "'>"+Liste_PrestationPole[i][4]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('poles').innerHTML=sel;
}

function Recharge_Pole(){
	var i;
	var sel="";
	var isElement = false;
	sel ="<select id='pole' size='1' name='pole' style='width: 30%;'>";
	for(i=0;i<Liste_Pole.length;i++){
		if (Liste_Pole[i][2]==document.getElementById('prestation').value){
			sel= sel + "<option value='"+Liste_Pole[i][0];
			sel= sel + "'>"+Liste_Pole[i][1]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('poles').innerHTML=sel;
}
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
function pointVirgule(champ){
	for(x = 0; x < champ.value.length; x++)
	{
		if(champ.value.charAt(x) == ";" || champ.value.charAt(x) == "|"){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
	}
}
function Enregistrer(){
	var valide = true;
	
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
		if(formulaire.dateDebut1.value!=""){
			if(formulaire.dateDebut1.value!=""){
				if (document.getElementById('intervention1').checked==true){
					if(formulaire.heureDebut11.value=="" && formulaire.heureFin11.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut11.value!="" || formulaire.heureFin11.value!=""){
						if(nbMinutes(formulaire.heureDebut11.value)>=nbMinutes(formulaire.heureFin11.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut21.value!="" || formulaire.heureFin21.value!=""){
						if(nbMinutes(formulaire.heureDebut21.value)>=nbMinutes(formulaire.heureFin21.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut31.value!="" || formulaire.heureFin31.value!=""){
						if(nbMinutes(formulaire.heureDebut31.value)>=nbMinutes(formulaire.heureFin31.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
			if(formulaire.dateDebut2.value!=""){
				if (document.getElementById('intervention2').checked==true){
					if(formulaire.heureDebut12.value=="" && formulaire.heureFin12.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut12.value!="" || formulaire.heureFin12.value!=""){
						if(nbMinutes(formulaire.heureDebut12.value)>=nbMinutes(formulaire.heureFin12.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut22.value!="" || formulaire.heureFin22.value!=""){
						if(nbMinutes(formulaire.heureDebut22.value)>=nbMinutes(formulaire.heureFin22.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut32.value!="" || formulaire.heureFin32.value!=""){
						if(nbMinutes(formulaire.heureDebut32.value)>=nbMinutes(formulaire.heureFin32.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
			if(formulaire.dateDebut3.value!=""){
				if (document.getElementById('intervention3').checked==true){
					if(formulaire.heureDebut13.value=="" && formulaire.heureFin13.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut13.value!="" || formulaire.heureFin13.value!=""){
						if(nbMinutes(formulaire.heureDebut13.value)>=nbMinutes(formulaire.heureFin13.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut23.value!="" || formulaire.heureFin23.value!=""){
						if(nbMinutes(formulaire.heureDebut23.value)>=nbMinutes(formulaire.heureFin23.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut33.value!="" || formulaire.heureFin33.value!=""){
						if(nbMinutes(formulaire.heureDebut33.value)>=nbMinutes(formulaire.heureFin33.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
			if(formulaire.dateDebut4.value!=""){
				if (document.getElementById('intervention4').checked==true){
					if(formulaire.heureDebut14.value=="" && formulaire.heureFin14.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut14.value!="" || formulaire.heureFin14.value!=""){
						if(nbMinutes(formulaire.heureDebut14.value)>=nbMinutes(formulaire.heureFin14.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut24.value!="" || formulaire.heureFin24.value!=""){
						if(nbMinutes(formulaire.heureDebut24.value)>=nbMinutes(formulaire.heureFin24.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut34.value!="" || formulaire.heureFin34.value!=""){
						if(nbMinutes(formulaire.heureDebut34.value)>=nbMinutes(formulaire.heureFin34.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
			if(formulaire.dateDebut5.value!=""){
				if (document.getElementById('intervention5').checked==true){
					if(formulaire.heureDebut15.value=="" && formulaire.heureFin15.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut15.value!="" || formulaire.heureFin15.value!=""){
						if(nbMinutes(formulaire.heureDebut15.value)>=nbMinutes(formulaire.heureFin15.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut25.value!="" || formulaire.heureFin25.value!=""){
						if(nbMinutes(formulaire.heureDebut25.value)>=nbMinutes(formulaire.heureFin25.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut35.value!="" || formulaire.heureFin35.value!=""){
						if(nbMinutes(formulaire.heureDebut35.value)>=nbMinutes(formulaire.heureFin35.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
			if(formulaire.dateDebut6.value!=""){
				if (document.getElementById('intervention6').checked==true){
					if(formulaire.heureDebut16.value=="" && formulaire.heureFin16.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut16.value!="" || formulaire.heureFin16.value!=""){
						if(nbMinutes(formulaire.heureDebut16.value)>=nbMinutes(formulaire.heureFin16.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut26.value!="" || formulaire.heureFin26.value!=""){
						if(nbMinutes(formulaire.heureDebut26.value)>=nbMinutes(formulaire.heureFin26.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut36.value!="" || formulaire.heureFin36.value!=""){
						if(nbMinutes(formulaire.heureDebut36.value)>=nbMinutes(formulaire.heureFin36.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
			if(formulaire.dateDebut7.value!=""){
				if (document.getElementById('intervention7').checked==true){
					if(formulaire.heureDebut17.value=="" && formulaire.heureFin17.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
					if(formulaire.heureDebut17.value!="" || formulaire.heureFin17.value!=""){
						if(nbMinutes(formulaire.heureDebut17.value)>=nbMinutes(formulaire.heureFin17.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut27.value!="" || formulaire.heureFin27.value!=""){
						if(nbMinutes(formulaire.heureDebut27.value)>=nbMinutes(formulaire.heureFin27.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
					if(formulaire.heureDebut37.value!="" || formulaire.heureFin37.value!=""){
						if(nbMinutes(formulaire.heureDebut37.value)>=nbMinutes(formulaire.heureFin37.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
					}
				}
			}
		}
		else{
			valide=false;alert("Veuillez compléter les dates d\'astreintes");return false;
		}
	}
	else{
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}
		if(formulaire.dateDebut1.value!=""){
			if(formulaire.dateDebut1.value!=""){
				if (document.getElementById('intervention1').checked==true){
					if(formulaire.heureDebut11.value=="" && formulaire.heureFin11.value==""){valide=false;alert("Please complete the hours");return false;}
				}
				if(formulaire.heureDebut11.value!="" || formulaire.heureFin11.value!=""){
					if(nbMinutes(formulaire.heureDebut11.value)>=nbMinutes(formulaire.heureFin11.value)){valide=false;alert("The start time must be less than the end time");return false;}
				}
				if(formulaire.heureDebut21.value!="" || formulaire.heureFin21.value!=""){
					if(nbMinutes(formulaire.heureDebut21.value)>=nbMinutes(formulaire.heureFin21.value)){valide=false;alert("The start time must be less than the end time");return false;}
				}
				if(formulaire.heureDebut31.value!="" || formulaire.heureFin31.value!=""){
					if(nbMinutes(formulaire.heureDebut31.value)>=nbMinutes(formulaire.heureFin31.value)){valide=false;alert("The start time must be less than the end time");return false;}
				}
			}
			if(formulaire.dateDebut2.value!=""){
				if (document.getElementById('intervention2').checked==true){
					if(formulaire.heureDebut12.value=="" && formulaire.heureFin12.value==""){valide=false;alert("Please complete the hours");return false;}
					if(formulaire.heureDebut12.value!="" || formulaire.heureFin12.value!=""){
						if(nbMinutes(formulaire.heureDebut12.value)>=nbMinutes(formulaire.heureFin12.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut22.value!="" || formulaire.heureFin22.value!=""){
						if(nbMinutes(formulaire.heureDebut22.value)>=nbMinutes(formulaire.heureFin22.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut32.value!="" || formulaire.heureFin32.value!=""){
						if(nbMinutes(formulaire.heureDebut32.value)>=nbMinutes(formulaire.heureFin32.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
				}
			}
			if(formulaire.dateDebut3.value!=""){
				if (document.getElementById('intervention3').checked==true){
					if(formulaire.heureDebut13.value=="" && formulaire.heureFin13.value==""){valide=false;alert("Please complete the hours");return false;}
					if(formulaire.heureDebut13.value!="" || formulaire.heureFin13.value!=""){
						if(nbMinutes(formulaire.heureDebut13.value)>=nbMinutes(formulaire.heureFin13.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut23.value!="" || formulaire.heureFin23.value!=""){
						if(nbMinutes(formulaire.heureDebut23.value)>=nbMinutes(formulaire.heureFin23.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut33.value!="" || formulaire.heureFin33.value!=""){
						if(nbMinutes(formulaire.heureDebut33.value)>=nbMinutes(formulaire.heureFin33.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
				}
			}
			if(formulaire.dateDebut4.value!=""){
				if (document.getElementById('intervention4').checked==true){
					if(formulaire.heureDebut14.value=="" && formulaire.heureFin14.value==""){valide=false;alert("Please complete the hours");return false;}
					if(formulaire.heureDebut14.value!="" || formulaire.heureFin14.value!=""){
						if(nbMinutes(formulaire.heureDebut14.value)>=nbMinutes(formulaire.heureFin14.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut24.value!="" || formulaire.heureFin24.value!=""){
						if(nbMinutes(formulaire.heureDebut24.value)>=nbMinutes(formulaire.heureFin24.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut34.value!="" || formulaire.heureFin34.value!=""){
						if(nbMinutes(formulaire.heureDebut34.value)>=nbMinutes(formulaire.heureFin34.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
				}
			}
			if(formulaire.dateDebut5.value!=""){
				if (document.getElementById('intervention5').checked==true){
					if(formulaire.heureDebut15.value=="" && formulaire.heureFin15.value==""){valide=false;alert("Please complete the hours");return false;}
					if(formulaire.heureDebut15.value!="" || formulaire.heureFin15.value!=""){
						if(nbMinutes(formulaire.heureDebut15.value)>=nbMinutes(formulaire.heureFin15.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut25.value!="" || formulaire.heureFin25.value!=""){
						if(nbMinutes(formulaire.heureDebut25.value)>=nbMinutes(formulaire.heureFin25.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut35.value!="" || formulaire.heureFin35.value!=""){
						if(nbMinutes(formulaire.heureDebut35.value)>=nbMinutes(formulaire.heureFin35.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
				}
			}
			if(formulaire.dateDebut6.value!=""){
				if (document.getElementById('intervention6').checked==true){
					if(formulaire.heureDebut16.value=="" && formulaire.heureFin16.value==""){valide=false;alert("Please complete the hours");return false;}
					if(formulaire.heureDebut16.value!="" || formulaire.heureFin16.value!=""){
						if(nbMinutes(formulaire.heureDebut16.value)>=nbMinutes(formulaire.heureFin16.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut26.value!="" || formulaire.heureFin26.value!=""){
						if(nbMinutes(formulaire.heureDebut26.value)>=nbMinutes(formulaire.heureFin26.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut36.value!="" || formulaire.heureFin36.value!=""){
						if(nbMinutes(formulaire.heureDebut36.value)>=nbMinutes(formulaire.heureFin36.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
				}
			}
			if(formulaire.dateDebut7.value!=""){
				if (document.getElementById('intervention7').checked==true){
					if(formulaire.heureDebut17.value=="" && formulaire.heureFin17.value==""){valide=false;alert("Please complete the hours");return false;}
					if(formulaire.heureDebut17.value!="" || formulaire.heureFin17.value!=""){
						if(nbMinutes(formulaire.heureDebut17.value)>=nbMinutes(formulaire.heureFin17.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut27.value!="" || formulaire.heureFin27.value!=""){
						if(nbMinutes(formulaire.heureDebut27.value)>=nbMinutes(formulaire.heureFin27.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
					if(formulaire.heureDebut37.value!="" || formulaire.heureFin37.value!=""){
						if(nbMinutes(formulaire.heureDebut37.value)>=nbMinutes(formulaire.heureFin37.value)){valide=false;alert("The start time must be less than the end time");return false;}
					}
				}
			}
		}
		else{
			valide=false;alert("Please complete the penalty dates");return false;
		}
	}
	
	Id_Personnes="";
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
		Id_Personnes=Id_Personnes+"_"+document.getElementById('PersonneSelect').options[y].value;
	}
	
	document.getElementById('HorsContrat').innerHTML="";
	$.ajax({
		url : 'Ajax_HorsContratCeJour2.php',
		data : 'DateDebut1='+formulaire.dateDebut1.value+'&DateDebut2='+formulaire.dateDebut2.value+'&DateDebut3='+formulaire.dateDebut3.value+'&DateDebut4='+formulaire.dateDebut4.value+'&DateDebut5='+formulaire.dateDebut5.value+'&DateDebut6='+formulaire.dateDebut6.value+'&DateDebut7='+formulaire.dateDebut7.value+'&Id_Personnes='+Id_Personnes,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('HorsContrat').innerHTML=data;
			}
	});
	
	document.getElementById('HS').innerHTML="";
	document.getElementById('AS').innerHTML="";
	if(document.getElementById('HorsContrat').innerHTML.indexOf("attention.png")==-1){
		
		document.getElementById('HSJourNonT').innerHTML="";
		$.ajax({
			url : 'Ajax_HSHorsContratCeJour.php',
			data : 'DateDebut1='+formulaire.dateDebut1.value+'&DateDebut2='+formulaire.dateDebut2.value+'&DateDebut3='+formulaire.dateDebut3.value+'&DateDebut4='+formulaire.dateDebut4.value+'&DateDebut5='+formulaire.dateDebut5.value+'&DateDebut6='+formulaire.dateDebut6.value+'&DateDebut7='+formulaire.dateDebut7.value+'&Id_Personnes='+Id_Personnes,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('HSJourNonT').innerHTML=data;
				}
		});
		if(document.getElementById('HSJourNonT').innerHTML.indexOf("attention.png")!=-1){return false;}	
		
		$.ajax({
			url : 'Ajax_AstreinteCeJour3.php',
			data : 'DateDebut1='+formulaire.dateDebut1.value+'&DateDebut2='+formulaire.dateDebut2.value+'&DateDebut3='+formulaire.dateDebut3.value+'&DateDebut4='+formulaire.dateDebut4.value+'&DateDebut5='+formulaire.dateDebut5.value+'&DateDebut6='+formulaire.dateDebut6.value+'&DateDebut7='+formulaire.dateDebut7.value+'&Id_Personnes='+Id_Personnes,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('AS').innerHTML=data;
				}
		});
		
		document.getElementById('HS').innerHTML="";
		if(document.getElementById('AS').innerHTML.indexOf("attention.png")==-1){
			$.ajax({
				url : 'Ajax_HSCeJour5.php',
				data : 'DateDebut1='+formulaire.dateDebut1.value+'&DateDebut2='+formulaire.dateDebut2.value+'&DateDebut3='+formulaire.dateDebut3.value+'&DateDebut4='+formulaire.dateDebut4.value+'&DateDebut5='+formulaire.dateDebut5.value+'&DateDebut6='+formulaire.dateDebut6.value+'&DateDebut7='+formulaire.dateDebut7.value+'&Id_Personnes='+Id_Personnes,
				dataType : "html",
				async : false,
				//affichage de l'erreur en cas de problème
				error:function(msg, string){
					
					},
				success:function(data){
					document.getElementById('HS').innerHTML=data;
					}
			});
			//Récupérer la partie entre les balises <lesHS></lesHS>
			HS=document.getElementById('HS').innerHTML.substring(document.getElementById('HS').innerHTML.indexOf("lesHSDEBUT")+10,document.getElementById('HS').innerHTML.indexOf("lesHSFIN"));
			continu=1;
			if(HS!=""){
			if(document.getElementById('Langue').value=="FR"){
					question="Les personnes suivantes ont des heures supplémentaires déclarées ces jours là : \n"+HS+" \nVoulez vous continuez ?";
				}
				else{
					question="The following people have overtime declared on these days : \n"+HS+" \nDo you want to continue?";
				}
				if(window.confirm(question)){
					continu=1;
				}
				else{
					continu=0;
				}
			}
			if(continu==1){
				//Verifier si la personne a des absences injustifiées ce jour là
				document.getElementById('ABS_INJ').value="";
				$.ajax({
					url : 'Ajax_AbsenceCeJour3.php',
					data : 'DateDebut1='+formulaire.dateDebut1.value+'&DateDebut2='+formulaire.dateDebut2.value+'&DateDebut3='+formulaire.dateDebut3.value+'&DateDebut4='+formulaire.dateDebut4.value+'&DateDebut5='+formulaire.dateDebut5.value+'&DateDebut6='+formulaire.dateDebut6.value+'&DateDebut7='+formulaire.dateDebut7.value+'&Id_Personnes='+Id_Personnes,
					dataType : "html",
					async : false,
					//affichage de l'erreur en cas de problème
					error:function(msg, string){
						
						},
					success:function(data){
						document.getElementById('ABS_INJ').innerHTML=data;
						}
				});
				if(document.getElementById('ABS_INJ').innerHTML.indexOf("attention")==-1){
					document.getElementById('ABS').innerHTML="";
					$.ajax({
						url : 'Ajax_AbsenceCeJour2.php',
						data : 'DateDebut1='+formulaire.dateDebut1.value+'&DateDebut2='+formulaire.dateDebut2.value+'&DateDebut3='+formulaire.dateDebut3.value+'&DateDebut4='+formulaire.dateDebut4.value+'&DateDebut5='+formulaire.dateDebut5.value+'&DateDebut6='+formulaire.dateDebut6.value+'&DateDebut7='+formulaire.dateDebut7.value+'&Id_Personnes='+Id_Personnes,
						dataType : "html",
						async : false,
						//affichage de l'erreur en cas de problème
						error:function(msg, string){
							
							},
						success:function(data){
							document.getElementById('ABS').innerHTML=data;
							}
					});
					//Récupérer la partie entre les balises <lesHS></lesHS>
					ABS=document.getElementById('ABS').innerHTML.substring(document.getElementById('ABS').innerHTML.indexOf("lesABSDEBUT")+11,document.getElementById('ABS').innerHTML.indexOf("lesABSFIN"));
					if(ABS!=""){
						if(document.getElementById('Langue').value=="FR"){
							alert("Les personnes suivantes ont des congés déclarés ces jours là : \n"+ABS+" ");
							return false;
						}
						else{
							alert("The following people have holidays declared on these days : \n"+ABS+" ");
							return false;
						}

						var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
						document.getElementById('Ajouter').innerHTML=bouton;
						var evt = document.createEvent("MouseEvents");
						evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
						document.getElementById("btnEnregistrer2").dispatchEvent(evt);
						document.getElementById('Ajouter').innerHTML="";
					}
					else{
						var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
						document.getElementById('Ajouter').innerHTML=bouton;
						var evt = document.createEvent("MouseEvents");
						evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
						document.getElementById("btnEnregistrer2").dispatchEvent(evt);
						document.getElementById('Ajouter').innerHTML="";
					}
				}
			}
		}
	}
}


function EnregistrerModif(){
	var valide = true;
	
	if(document.getElementById('Langue').value=="FR"){
		if(formulaire.dateDebut1.value!=""){
			if(formulaire.dateDebut1.value!=""){
				if (document.getElementById('intervention1').checked==true){
					if(formulaire.heureDebut11.value=="" && formulaire.heureFin11.value==""){valide=false;alert("Veuillez compléter les heures");return false;}
				}
			}
		}
		else{
			valide=false;alert("Veuillez compléter les dates d\'astreintes");return false;
		}
	}
	else{
		if(formulaire.dateDebut1.value!=""){
			if(formulaire.dateDebut1.value!=""){
				if (document.getElementById('intervention1').checked==true){
					if(formulaire.heureDebut11.value=="" && formulaire.heureFin11.value==""){valide=false;alert("Please complete the hours");return false;}
				}
			}
		}
		else{
			valide=false;alert("Please complete the penalty dates");return false;
		}
	}
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function Affiche_Heure(nb){
	document.getElementById('heureDebut1'+nb).value="";
	document.getElementById('heureFin1'+nb).value="";
	document.getElementById('heureDebut2'+nb).value="";
	document.getElementById('heureFin2'+nb).value="";
	document.getElementById('heureDebut3'+nb).value="";
	document.getElementById('heureFin3'+nb).value="";
	if (document.getElementById('intervention'+nb).checked==true){
		var elements = document.getElementsByClassName('nbHeure'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	else{
		var elements = document.getElementsByClassName('nbHeure'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
}

function AfficherTR(nb){
	var elements = document.getElementsByClassName('RA'+nb);
	for (i=0; i<elements.length; i++){
	  elements[i].style.display='';
	}
	
	var elements = document.getElementsByClassName('R'+nb);
	for (i=0; i<elements.length; i++){
	  elements[i].style.display='none';
	}
}

function ajouter(){
	for(y=0;y<document.getElementById('Id_Personne').length;y++)
	{
		if(document.getElementById('Id_Personne').options[y].selected == true)
		{
			nouvel_element = new Option(document.getElementById('Id_Personne').options[y].text,document.getElementById('Id_Personne').options[y].value,false,false);
			document.getElementById('PersonneSelect').options[document.getElementById('PersonneSelect').length] = nouvel_element;
			document.getElementById('Id_Personne').options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById('PersonneSelect')
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array()
		Liste[i][0]=Obj.options[i].text
		Liste[i][1]=Obj.options[i].value
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0]
		Obj.options[i].value=Liste[i][1]
	}
}

function effacer(){
	for(y=0;y<document.getElementById('PersonneSelect').length;y++)
	{
		if(document.getElementById('PersonneSelect').options[y].selected == true)
		{
			nouvel_element = new Option(document.getElementById('PersonneSelect').options[y].text,document.getElementById('PersonneSelect').options[y].value,false,false);
			document.getElementById('Id_Personne').options[document.getElementById('Id_Personne').length] = nouvel_element;
			document.getElementById('PersonneSelect').options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById('Id_Personne')
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array()
		Liste[i][0]=Obj.options[i].text
		Liste[i][1]=Obj.options[i].value
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0]
		Obj.options[i].value=Liste[i][1]
	}
}

function selectall(){
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('Date').value==""){alert("Veuillez remplir la date.");return false;}
		if(document.getElementById('Nb_Heures_Jour').value==0 && document.getElementById('Nb_Heures_Nuit').value==0){alert("Veuillez remplir un nombre d'heures.");return false;}
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
	}
	else{
		if(document.getElementById('Date').value==""){alert("Please fill in the date.");return false;}
		if(document.getElementById('Nb_Heures_Jour').value==0 && document.getElementById('Nb_Heures_Nuit').value==0){alert("Please fill in a number of hours.");return false;}
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}

	}
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
	}
}

function nbMinutes(heure){
	nbMinute=0;
	if(heure!=""){
		time = heure.split(':');
		nbMinute=time[0]*60 + time[1];
	}
	return parseFloat(nbMinute);
}

function estFerie(nomChamps){
	//Si date < Mois E/C -2 OU date < Mois E/C -1 ET Date E/C >=10 du mois alors IMPOSSIBLE = Efface les infos 
		myDateDebut = document.getElementById(nomChamps).value;
		myDateDebut2 = myDateDebut.split("-");
		dateJJJJMM="";
		if (myDateDebut2.length == 1){
			myDateDebut = myDateDebut.split("/");
			dateJJJJMM=myDateDebut[2]+"/"+myDateDebut[1];
		}
		else{
			myDateDebut = myDateDebut.split("-");
			dateJJJJMM=myDateDebut[0]+"/"+myDateDebut[1];
		}

		var ladate=new Date();
		ladate.setMonth(ladate.getMonth()-2);
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_2Mois=ladate.getFullYear()+"/"+(mois);
		
		var ladate=new Date();
		ladate.setMonth(ladate.getMonth()-1);
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_1Mois=ladate.getFullYear()+"/"+(mois);
		
		var ladate=new Date();
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_10=ladate.getFullYear()+"-"+mois+"-10";
		
		var ladate=new Date();
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		jour=ladate.getDate();
		if(jour<10){jour="0"+jour;}
		date_Jour=ladate.getFullYear()+"-"+mois+"-"+jour;
		
		if(formulaire.Menu.value!=4){
			if(dateJJJJMM<=date_2Mois || (dateJJJJMM<date_1Mois && date_Jour>=date_10)){
				document.getElementById(nomChamps).value="";
			}
		}
		
	document.getElementById('div_'+nomChamps).innerHTML="";
	$.ajax({
		url : 'Ajax_EstJourFerie.php',
		data : 'DateDebut='+document.getElementById(nomChamps).value+'&Id_Prestation='+formulaire.Id_Prestation.value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('div_'+nomChamps).innerHTML=data;
			}
	});
}
