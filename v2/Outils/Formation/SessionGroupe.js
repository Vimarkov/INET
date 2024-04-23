Liste_Formation= new Array();
Liste_GroupeFormation= new Array();
Liste_Lieu= new Array();
Liste_Formateur= new Array();

function nombre(champ){
	var chiffres = new RegExp("[0-9\.]");
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

function FermerEtRecharger(infos){
	window.opener.location="Planning_v2.php?"+infos;
	window.close();
}
function SelectionnerTout(){
	var elements = document.getElementsByClassName("check");
	if (formulaire.selectAll.checked == true){
		for(var i=0, l=elements.length; i<l; i++){
			elements[i].checked = true;
		}
	}
	else{
		for(var i=0, l=elements.length; i<l; i++){
			elements[i].checked = false;
		}
	}
}
function SelectionnerTout2(){
	var elements = document.getElementsByClassName("check");
	var checked=true;
	for(var i=0, l=elements.length; i<l; i++){
		if(elements[i].checked==false){
			checked=false;
		}
	}
	if(checked==true){
		formulaire.selectAll.checked = true;
	}
	else{
		formulaire.selectAll.checked = false;
	}
}

function AfficherFormationNonLiees(){
	var elements = document.getElementsByClassName("formsNonLiees");
	var display="none";
	var displayliee="";
	if(document.getElementById('formationsLiees').value==0){
		display="";
		displayliee="none";
	}
	document.getElementById('displayLiee').style.display=displayliee;
	for(var i=0, l=elements.length; i<l; i++){
		elements[i].style.display=display;
	}
}

function minToHour(minutes){
	//On test que minutes est bien un nombre entier
	var Myexp = new RegExp("^[0-9]+$","g");
	if(Myexp.test(minutes)){
		var nbHour = parseInt(minutes / 60);
		var nbminuteRestante = (minutes % 60);
		if(nbminuteRestante == 0){return nbHour + ":00";}
		else{return nbHour + ":" + nbminuteRestante;}
	} 
}

function MajCompteur(id_Formation){
	var heurePlus = document.getElementById('heurePlus_'+id_Formation).value;
	var minPlus = document.getElementById('minPlus_'+id_Formation).value;
	var heure=0;
	var minute=0;
	var heureD=0;
	var minD=0;
	var heureF=0;
	var minF=0;
	var heureFinal=0;
	var minFinal=0;
	
	for(i=1;i<=document.getElementById('nbJours_'+id_Formation).value;i++){
		if(document.getElementById('heureDebut_'+id_Formation+'_'+i).value!="0"){
			var tabHeure = document.getElementById('heureDebut_'+id_Formation+"_"+i).value.split(":");
			heureD=tabHeure[0];
			if(tabHeure[1]!=""){minD=tabHeure[1];}
			
			var tabHeure = document.getElementById('heureFin_'+id_Formation+"_"+i).value.split(":");
			heureF=tabHeure[0];
			if(tabHeure[1]!=""){minF=tabHeure[1];}
			
			heure = parseInt(heureF)-parseInt(heureD);
			minute = parseInt(minF)-parseInt(minD);
			if(minute>=0){
				minute=minToHour(minute);
				tabMinutes = minute.split(":");
				heure=parseInt(heure)+parseInt(tabMinutes[0]);
				minute=("0"+parseInt(tabMinutes[1])).slice(-2);
			}
			else{
				heure = heure - 1;
				minute = 60 + minute;
				minute=minToHour(minute);
				tabMinutes = minute.split(":");
				minute=("0"+parseInt(tabMinutes[1])).slice(-2);
			}
			if(document.getElementById('pauseRepas_'+id_Formation+"_"+i).value==1){
				//Ajout de la pause
				if(document.getElementById('heureFin_'+id_Formation+"_"+i).value > document.getElementById('heureDebutPause_'+id_Formation+"_"+i).value && document.getElementById('heureDebut_'+id_Formation+"_"+i).value < document.getElementById('heureFinPause_'+id_Formation+"_"+i).value){
					var tabHeure = document.getElementById('heureDebutPause_'+id_Formation+"_"+i).value.split(":");
					heureDPause=tabHeure[0];
					if(tabHeure[1]!=""){minDPause=tabHeure[1];}
					
					var tabHeure = document.getElementById('heureFinPause_'+id_Formation+"_"+i).value.split(":");
					heureFPause=tabHeure[0];
					if(tabHeure[1]!=""){minFPause=tabHeure[1];}
					
					heurePause = parseInt(heureFPause)-parseInt(heureDPause);
					minutePause = parseInt(minFPause)-parseInt(minDPause);
					if(minutePause>=0){
						minutePause=minToHour(minutePause);
						tabMinutes = minutePause.split(":");
						heurePause=heurePause+parseInt(tabMinutes[0]);
						minutePause=("0"+parseInt(tabMinutes[1])).slice(-2);
					}
					else{
						heurePause = heurePause - 1;
						minutePause = 60 + minutePause;
					}
					heure=parseInt(heure)-parseInt(heurePause);
					minute=parseInt(minute)-parseInt(minutePause);
					if(minute>=0){
						minute=minToHour(minute);
						tabMinutes = minute.split(":");
						heure=parseInt(heure)+parseInt(tabMinutes[0]);
						minute=("0"+parseInt(tabMinutes[1])).slice(-2);
					}
					else{
						heure = heure - 1;
						minute = 60 + minute;
					}
				}
			}
			heureFinal = parseInt(heureFinal)+parseInt(heure);
			minFinal = minToHour(parseInt(minFinal)+parseInt(minute));
			tabMinutes = minFinal.split(":");
			heureFinal=parseInt(heureFinal)+parseInt(tabMinutes[0]);
			minFinal=("0"+parseInt(tabMinutes[1])).slice(-2);
		}
	
	}
	heureFinal = parseInt(heurePlus)-parseInt(heureFinal);
	minFinal = parseInt(minPlus)-parseInt(minFinal);
	if(minFinal>=0){
		minFinal=minToHour(minFinal);
		tabMinutes = minFinal.split(":");
		heureFinal=heureFinal+parseInt(tabMinutes[0]);
		minFinal=("0"+parseInt(tabMinutes[1])).slice(-2);
	}
	else{
		if(heureFinal>0){
			heureFinal = heureFinal - 1;
			minFinal = 60 + minFinal;
			minFinal=minToHour(minFinal);
			tabMinutes = minFinal.split(":");
			minFinal=("0"+parseInt(tabMinutes[1])).slice(-2);
		}
		else{
			heureFinal="-"+heureFinal;
			minFinal=("0"+parseInt(minFinal)).slice(-2);
		}
	}
	if(document.getElementById('Langue').value=="FR"){
		document.getElementById('compteur_'+id_Formation).innerHTML="Nbr d'heures : "+heurePlus+":"+minPlus+"<br>Nbr d'heures restantes : "+heureFinal+":"+minFinal;
	}
	else{
		document.getElementById('compteur_'+id_Formation).innerHTML="Number of hours : "+heurePlus+":"+minPlus+"<br>Number of hours remaining : "+heureFinal+":"+minFinal;
	}
	document.getElementById('heuresRestantes_'+id_Formation).value=heureFinal;
	document.getElementById('minRestantes_'+id_Formation).value=minFinal;
}

function VerifHeuresPause(nbJour,Typeheure,id,id_Formation,Recyclage,Modif){
	Modif= Modif || 0;
	if(document.getElementById('pauseRepas_'+id_Formation+'_'+id).value==0){
	}
	else{
		if(document.getElementById('heureFinPause_'+id_Formation+'_'+id).value < document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value){
			if(Typeheure=="D"){document.getElementById('heureFinPause_'+id_Formation+'_'+id).value=document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value;}
			else{document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value=document.getElementById('heureFinPause_'+id_Formation+'_'+id).value;}
		}
	}
	if(nbJour>1){calculNbHeuresRestantes(Typeheure,id,id_Formation);}
	else{
		if(Modif==0){ModifierHeureFin(id,id_Formation,Recyclage);}
		else{ModifierHeureFinModeModif(id,id_Formation,Recyclage);}
	}
	
}

function calculNbHeuresRestantes(Typeheure,id,id_Formation){
	if(document.getElementById('heureFin_'+id_Formation+'_'+id).value < document.getElementById('heureDebut_'+id_Formation+'_'+id).value){
		if(Typeheure=="D"){document.getElementById('heureFin_'+id_Formation+'_'+id).value=document.getElementById('heureDebut_'+id_Formation+'_'+id).value;}
		else{document.getElementById('heureDebut_'+id_Formation+'_'+id).value=document.getElementById('heureFin_'+id_Formation+'_'+id).value;}
	}
	
	if((document.getElementById('heureDebut_'+id_Formation+'_'+id).value>=document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value && document.getElementById('heureDebut_'+id_Formation+'_'+id).value<document.getElementById('heureFinPause_'+id_Formation+'_'+id).value) ||
		(document.getElementById('heureFin_'+id_Formation+'_'+id).value>document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value && document.getElementById('heureFin_'+id_Formation+'_'+id).value<=document.getElementById('heureFinPause_'+id_Formation+'_'+id).value)){
		document.getElementById('pauseRepas_'+id_Formation+'_'+id).value=0;
	}
	MajCompteur(id_Formation);
}

function VerifChamps(){
	Liste_Dates = new Array();
	nbDates=0;
	if(formulaire.groupeFormation.value=='0'){
		if(document.getElementById('Langue').value=="FR"){
			alert('Vous n\'avez pas renseigné le groupe de formation.');return false;
		}
		else{
			alert('You did not fill in the training group.');return false;
		}
	}
	if(formulaire.formationsLiees.value==1){
		if(formulaire.diffuser.value=='1'){
			if(formulaire.stagiaireMax.value=='' || formulaire.stagiaireMax.value=='0'){
				if(document.getElementById('Langue').value=="FR"){
					alert('Vous n\'avez pas renseigné le nombre de stagiaire maxi.');return false;
				}
				else{
					alert('You have not entered the maximum number of trainees.');return false;
				}
			}
		}
	}
	for(i=0;i<Liste_GroupeFormation.length;i++){
		if (Liste_GroupeFormation[i][1]==document.getElementById('groupeFormation').value){
			for(j=0;j<Liste_Formation.length;j++){
				if (Liste_GroupeFormation[i][2]==Liste_Formation[j][0]){
					for(nbJ=1;nbJ<=document.getElementById('nbJours_'+Liste_Formation[j][0]).value;nbJ++){
						if(document.getElementById('dateDebut_'+Liste_Formation[j][0]+"_"+nbJ).value==''){
							if(document.getElementById('Langue').value=="FR"){
								alert('Vous n\'avez pas renseigné toutes les dates de formation.');return false;
							}
							else{
								alert('You did not fill in all training dates.');return false;
							}
						}
						Liste_Dates[nbDates] = document.getElementById('dateDebut_'+Liste_Formation[j][0]+"_"+nbJ).value;
						nbDates++;
					}
					if(document.getElementById('nbJours_'+Liste_Formation[j][0]).value>0){
						//Verifier que les jours sont différents
						bOK=0;
						var liste = new Array();
						ligne=0;
						for(nbJ=1;nbJ<=document.getElementById('nbJours_'+Liste_Formation[j][0]).value;nbJ++){
							for(k=0;k<liste.length;k++){
								if(document.getElementById('dateDebut_'+Liste_Formation[j][0]+"_"+nbJ).value==liste[k]){
									if(document.getElementById('Langue').value=="FR"){
										alert('Vous ne pouvez pas renseigner 2 dates identiques pour une même formation.');return false;
									}
									else{
										alert('You can not enter 2 identical dates for the same training.');return false;
									}
								}
							}
							liste[ligne]=document.getElementById('dateDebut_'+Liste_Formation[j][0]+"_"+nbJ).value;
							ligne++;
						}
						//Verifier heures restantes
						if(document.getElementById('heuresRestantes_'+Liste_Formation[j][0]).value>0 || document.getElementById('minRestantes_'+Liste_Formation[j][0]).value>0){alert('Les heures de sessions ne sont pas corrects.');return false;}
					}
					//Si diffusion du créneau alors le nombre de stagiaire maxi doit être renseigné
					if(formulaire.formationsLiees.value==0){
						if(document.getElementById('diffuser_'+Liste_Formation[j][0]).value=='1'){
							if(document.getElementById('stagiaireMax_'+Liste_Formation[j][0]).value=='' || document.getElementById('stagiaireMax_'+Liste_Formation[j][0]).value=='0'){
								if(document.getElementById('Langue').value=="FR"){
									alert('Vous n\'avez pas renseigné le nombre de stagiaire maxi.');return false;
								}
								else{
									alert('You have not entered the maximum number of trainees.');return false;
								}
							}
						}
					}
				}
			}
		}
	}
	return true;
}

function ModifierDates(Langue,Id_Formation,Recyclage){
	var heurePlus=0;
	var minPlus=0;
	var compteur="";
	var tabDates="";
	var nbJours=1;
	var onchange="";
	
	for(k=0;k<Liste_Formation.length;k++){
		if (Liste_Formation[k][0]==Id_Formation){
			if(Recyclage=="0"){
				tabHeurePlus = Liste_Formation[k][2].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if(document.getElementById('Langue').value=="FR"){
					if (Liste_Formation[k][7]>=0){nbJours=Liste_Formation[k][7];compteur="Nbr d'heures : "+heurePlus+":"+minPlus;}
				}
				else{
					if (Liste_Formation[k][7]>=0){nbJours=Liste_Formation[k][7];compteur="Number of hour : "+heurePlus+":"+minPlus;}
				}
			}
			else{
				tabHeurePlus = Liste_Formation[k][3].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if(document.getElementById('Langue').value=="FR"){
					if (Liste_Formation[k][8]>=0){nbJours=Liste_Formation[k][8];compteur="Nbr d'heures : "+heurePlus+":"+minPlus;}
				}
				else{
					if (Liste_Formation[k][8]>=0){nbJours=Liste_Formation[k][8];compteur="Number of hour : "+heurePlus+":"+minPlus;}
				}
			}
			
			document.getElementById('compteur_'+Id_Formation).innerHTML=compteur;
		}
	}
	document.getElementById('heurePlus_'+Id_Formation).value = heurePlus;
	document.getElementById('minPlus_'+Id_Formation).value = minPlus;
	document.getElementById('nbJours_'+Id_Formation).value = nbJours;
}

function ModifierHeureFin(id,id_Formation,Recyclage){
	var heureD=0;
	var minD=0;
	var heurePlus=0;
	var minPlus=0;
	var compteur="";
	var tabDates="";
	var nbJours=1;
	for(i=0;i<Liste_Formation.length;i++){
		if (Liste_Formation[i][0]==id_Formation){
			if(Recyclage=="0"){
				tabHeurePlus = Liste_Formation[i][2].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if (Liste_Formation[i][7]>1){nbJours=Liste_Formation[i][7];}
			}
			else{
				tabHeurePlus = Liste_Formation[i][3].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if (Liste_Formation[i][8]>1){nbJours=Liste_Formation[i][8];}
			}
		}
	}
	document.getElementById('heureFin_'+id_Formation+'_'+id).value="";
	if(document.getElementById('heureDebut_'+id_Formation+'_'+id).value!="0"){
		if(document.getElementById('heureDebut_'+id_Formation+'_'+id).value>=document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value && document.getElementById('heureDebut_'+id_Formation+'_'+id).value<document.getElementById('heureFinPause_'+id_Formation+'_'+id).value){
			document.getElementById('pauseRepas_'+id_Formation+'_'+id).value=0;
		}
		var tabHeure = document.getElementById('heureDebut_'+id_Formation+'_'+id).value.split(":");
		heureD=tabHeure[0];
		if(tabHeure[1]!=""){minD=tabHeure[1];}
		for(i=0;i<Liste_Formation.length;i++){
			if (Liste_Formation[i][0]==id_Formation){
				heure = parseInt(heureD)+parseInt(heurePlus);
				minute = minToHour(parseInt(minD)+parseInt(minPlus));
				tabMinutes = minute.split(":");
				heure=heure+parseInt(tabMinutes[0]);
				heure=("0"+parseInt(heure)).slice(-2);
				minute=("0"+parseInt(tabMinutes[1])).slice(-2);
				if(document.getElementById('pauseRepas_'+id_Formation+'_'+id).value==1){
					//Ajout de la pause si nécessaire
					if(heure+":"+minute > document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value && heureD+":"+minD < document.getElementById('heureFinPause_'+id_Formation+'_'+id).value){
						var tabHeure = document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value.split(":");
						heureD=tabHeure[0];
						if(tabHeure[1]!=""){minD=tabHeure[1];}
						
						var tabHeure = document.getElementById('heureFinPause_'+id_Formation+'_'+id).value.split(":");
						heureF=tabHeure[0];
						if(tabHeure[1]!=""){minF=tabHeure[1];}
						
						heurePause = parseInt(heureF)-parseInt(heureD);
						minutePause = parseInt(minF)-parseInt(minD);
						if(minutePause>=0){
							minutePause=minToHour(minutePause);
							tabMinutes = minutePause.split(":");
							heurePause=heurePause+parseInt(tabMinutes[0]);
							minutePause=("0"+parseInt(tabMinutes[1])).slice(-2);
						}
						else{
							heurePause = heurePause - 1;
							minutePause = 60 + minutePause;
						}
						heure=parseInt(heure)+parseInt(heurePause);
						minute=minToHour(parseInt(minute)+parseInt(minutePause));
						tabMinutes = minute.split(":");
						heure=parseInt(heure)+parseInt(tabMinutes[0]);
						minute=("0"+parseInt(tabMinutes[1])).slice(-2);
					}
				}
				document.getElementById('heureFin_'+id_Formation+'_'+id).value = heure+":"+minute;
			}
		}
	}
}

function ModifierHeureFinModeModif(id,id_Formation,Recyclage){
	var heureD=0;
	var minD=0;
	var heurePlus=0;
	var minPlus=0;
	var compteur="";
	var tabDates="";
	var nbJours=1;

	heurePlus=document.getElementById('heurePlus_'+id_Formation).value;
	minPlus=document.getElementById('minPlus_'+id_Formation).value;
	nbJours=document.getElementById('nbJours_'+id_Formation).value;
	
	document.getElementById('heureFin_'+id_Formation+'_'+id).value="";
	if(document.getElementById('heureDebut_'+id_Formation+'_'+id).value!="0"){
		if(document.getElementById('heureDebut_'+id_Formation+'_'+id).value>=document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value && document.getElementById('heureDebut_'+id_Formation+'_'+id).value<document.getElementById('heureFinPause_'+id_Formation+'_'+id).value){
			document.getElementById('pauseRepas_'+id_Formation+'_'+id).value=0;
		}
		var tabHeure = document.getElementById('heureDebut_'+id_Formation+'_'+id).value.split(":");
		heureD=tabHeure[0];
		if(tabHeure[1]!=""){minD=tabHeure[1];}

		heure = parseInt(heureD)+parseInt(heurePlus);
		minute = minToHour(parseInt(minD)+parseInt(minPlus));
		tabMinutes = minute.split(":");
		heure=heure+parseInt(tabMinutes[0]);
		heure=("0"+parseInt(heure)).slice(-2);
		minute=("0"+parseInt(tabMinutes[1])).slice(-2);
		if(document.getElementById('pauseRepas_'+id_Formation+'_'+id).value==1){
			//Ajout de la pause si nécessaire
			if(heure+":"+minute > document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value && heureD+":"+minD < document.getElementById('heureFinPause_'+id_Formation+'_'+id).value){
				var tabHeure = document.getElementById('heureDebutPause_'+id_Formation+'_'+id).value.split(":");
				heureD=tabHeure[0];
				if(tabHeure[1]!=""){minD=tabHeure[1];}
				
				var tabHeure = document.getElementById('heureFinPause_'+id_Formation+'_'+id).value.split(":");
				heureF=tabHeure[0];
				if(tabHeure[1]!=""){minF=tabHeure[1];}
				
				heurePause = parseInt(heureF)-parseInt(heureD);
				minutePause = parseInt(minF)-parseInt(minD);
				if(minutePause>=0){
					minutePause=minToHour(minutePause);
					tabMinutes = minutePause.split(":");
					heurePause=heurePause+parseInt(tabMinutes[0]);
					minutePause=("0"+parseInt(tabMinutes[1])).slice(-2);
				}
				else{
					heurePause = heurePause - 1;
					minutePause = 60 + minutePause;
				}
				heure=parseInt(heure)+parseInt(heurePause);
				minute=minToHour(parseInt(minute)+parseInt(minutePause));
				tabMinutes = minute.split(":");
				heure=parseInt(heure)+parseInt(tabMinutes[0]);
				minute=("0"+parseInt(tabMinutes[1])).slice(-2);
			}
		}
		document.getElementById('heureFin_'+id_Formation+'_'+id).value = heure+":"+minute;
	}
}

function VerifChampsModeModif(){
	Liste_Dates = new Array();
	nbDates=0;
	var tabSession = document.getElementById('IdChampsSessions').value.split(";");
	if(formulaire.formationsLiees.value==1){
		if(formulaire.diffuser.value=='1'){
			if(formulaire.stagiaireMax.value=='' || formulaire.stagiaireMax.value=='0'){
				if(document.getElementById('Langue').value=="FR"){
					alert('Vous n\'avez pas renseigné le nombre de stagiaire maxi.');return false;
				}
				else{
					alert('You have not entered the maximum number of trainees.');return false;
				}
			}
		}
	}
	if(formulaire.formationsLiees.value==1){
		if(formulaire.diffuser.value=='1'){
			if(formulaire.stagiaireMax.value=='' || formulaire.stagiaireMax.value=='0'){
				if(document.getElementById('Langue').value=="FR"){
					alert('Vous n\'avez pas renseigné le nombre de stagiaire maxi.');return false;
				}
				else{
					alert('You have not entered the minimum number of trainees.');return false;
				}
				 
			}
		}
	}
	for(i=0;i<tabSession.length;i++){
		if(tabSession[i]!=""){
			for(nbJ=1;nbJ<=document.getElementById('nbJours_'+tabSession[i]).value;nbJ++){
				if(document.getElementById('dateDebut_'+tabSession[i]+"_"+nbJ).value==''){
					if(document.getElementById('Langue').value=="FR"){
						alert('Vous n\'avez pas renseigné toutes les dates de formation.');return false;
					}
					else{
						alert('You did not fill in all training dates.');return false;
					}
				}
				Liste_Dates[nbDates] = document.getElementById('dateDebut_'+tabSession[i]+"_"+nbJ).value;
				nbDates++;
			}
			if(document.getElementById('nbJours_'+tabSession[i]).value>0){
				//Verifier que les jours sont différents
				bOK=0;
				var liste = new Array();
				ligne=0;
				for(nbJ=1;nbJ<=document.getElementById('nbJours_'+tabSession[i]).value;nbJ++){
					for(k=0;k<liste.length;k++){
						if(document.getElementById('dateDebut_'+tabSession[i]+"_"+nbJ).value==liste[k]){alert('Vous ne pouvez-vous pas renseigner 2 dates identiques pour une même formation.');return false;}
					}
					liste[ligne]=document.getElementById('dateDebut_'+tabSession[i]+"_"+nbJ).value;
					ligne++;
				}
				//Verifier heures restantes
				if(document.getElementById('heuresRestantes_'+tabSession[i]).value>0 || document.getElementById('minRestantes_'+tabSession[i]).value>0){alert('Les heures de sessions ne sont pas corrects.');return false;}
			}
			//Si diffusion du créneau alors le nombre de stagiaire maxi doit être renseigné
			if(formulaire.formationsLiees.value==0){
				if(document.getElementById('diffuser_'+tabSession[i]).value=='1'){
					if(document.getElementById('stagiaireMax_'+tabSession[i]).value=='' || document.getElementById('stagiaireMax_'+tabSession[i]).value=='0'){
						if(document.getElementById('Langue').value=="FR"){
							alert('Vous n\'avez pas renseigné le nombre de stagiaire maxi.');return false;
						}
						else{
							alert('You have not entered the maximum number of trainees.');return false;
						}
					}
				}
			}
		}
	}
	return true;
}
function AfficherFormation(Langue){
	var table="";
	table="";
	table+="<table width='100%'>";
	document.getElementById('Id_Formations').value="";
	if(document.getElementById('formationsLiees').value==1){
		if(document.getElementById('Langue').value=="FR"){
			table+="<tr><td style='font-weight:bold;'>Diffuser : </td><td align='left'>";
		}
		else{
			table+="<tr><td style='font-weight:bold;'>Spread : </td><td align='left'>";
		}
		table+="<select name=\"diffuser\" id=\"diffuser\">";
		table+="<option value='0'>Non</option>";
		table+="<option value='1'>Oui</option>";
		table+="</select>";
		table+="</td>";
		
		//Nb stagiaires si formations liées
		if(document.getElementById('Langue').value=="FR"){
			table+="<td style='font-weight:bold;'>Nb stagiaires mini : </td><td align='left'>";
		}
		else{
			table+="<td style='font-weight:bold;'>Number of trainees minimum : </td><td align='left'>";
		}
		table+="<input onKeyUp='nombre(this)' name='stagiaireMin' id='stagiaireMin' style='width:40px;' type='text' value=''>";
		table+="</td>";
		if(document.getElementById('Langue').value=="FR"){
			table+="<td style='font-weight:bold;'>Nb stagiaires maxi : </td><td align='left'>";
		}
		else{
			table+="<td style='font-weight:bold;'>Number of trainees maximum : </td><td align='left'>";
		}
		table+="<input onKeyUp='nombre(this)' name='stagiaireMax' id='stagiaireMax' style='width:40px;' type='text' value=''>";
		table+="</td>";
		table+="</tr>";
	}
	
	table+="<tr><td colspan='6' style='border-bottom:1px dotted #1a23f0'></td></tr>";
	couleur='#d2fbcf';
	for(i=0;i<Liste_GroupeFormation.length;i++){
		if (Liste_GroupeFormation[i][1]==document.getElementById('groupeFormation').value){
			//Pour chaque formation du groupe de formation, recherche des informations : Type de la formation|Nom de la formation
			for(j=0;j<Liste_Formation.length;j++){
				if (Liste_GroupeFormation[i][2]==Liste_Formation[j][0]){
					document.getElementById('Id_Formations').value=document.getElementById('Id_Formations').value+";"+Liste_Formation[j][0];
					if(document.getElementById('formationsLiees').value==0){
						if(document.getElementById('Langue').value=="FR"){
							table+="<tr bgcolor='"+couleur+"'><td style='font-weight:bold;' width='10%'>Diffuser : </td><td width='15%' align='left' colspan='5'>";
						}
						else{
							table+="<tr bgcolor='"+couleur+"'><td style='font-weight:bold;' width='10%'>Spread : </td><td width='15%' align='left' colspan='5'>";
						}
						table+="<select name=\"diffuser_"+Liste_Formation[j][0]+"\" id=\"diffuser_"+Liste_Formation[j][0]+"\">";
						if(document.getElementById('Langue').value=="FR"){
							table+="<option value='0'>Non</option>";
							table+="<option value='1'>Oui</option>";
						}
						else{
							table+="<option value='0'>No</option>";
							table+="<option value='1'>Yes</option>";
						}
						table+="</select>";
						table+="</td>";
					}
					table+="<tr  bgcolor='"+couleur+"' style='display:none;'><td colspan='6'>";
					table+="<input type='hidden' id='heurePlus_"+Liste_Formation[j][0]+"' name='heurePlus_"+Liste_Formation[j][0]+"' value='0'>";
					table+="<input type='hidden' id='minPlus_"+Liste_Formation[j][0]+"' name='minPlus_"+Liste_Formation[j][0]+"' value='0'>";
					table+="<input type='hidden' id='nbJours_"+Liste_Formation[j][0]+"' name='nbJours_"+Liste_Formation[j][0]+"' value='1'>";
					table+="<input type='hidden' id='heuresRestantes_"+Liste_Formation[j][0]+"' name='heuresRestantes_"+Liste_Formation[j][0]+"' value='0'>";
					table+="<input type='hidden' id='minRestantes_"+Liste_Formation[j][0]+"' name='minRestantes_"+Liste_Formation[j][0]+"' value='0'>";
					table+="<input type='hidden' id='recyclage_"+Liste_Formation[j][0]+"' name='recyclage_"+Liste_Formation[j][0]+"' value='"+Liste_GroupeFormation[i][3]+"'>";
					table+="<tr bgcolor='"+couleur+"'><td style='font-weight:bold;' width='10%'>Type : </td><td width='15%' align='left' colspan='5'>"+Liste_Formation[j][9]+"</td>";
					table+="</td></tr>";
					if(Liste_GroupeFormation[i][3]==0){
						if(document.getElementById('Langue').value=="FR"){
							table+="<tr  bgcolor='"+couleur+"'><td width='15%' style='font-weight:bold;' valign='top'>Initiale / Recyclage : </td><td valign='top' width='10%' align='left'>Initiale</td>";
							table+="<td width='10%' style='font-weight:bold;' valign='top'>Formation : </td><td width='40%' valign='top' align='left' colspan='4'>"+Liste_Formation[j][4]+"<br/><div id='compteur_"+Liste_Formation[j][0]+"' style='display: inline'></div></td></tr>";
						}
						else{
							table+="<tr  bgcolor='"+couleur+"'><td width='15%' style='font-weight:bold;' valign='top'>Initial / Recycling : </td><td valign='top' width='10%' align='left'>Initial</td>";
							table+="<td width='10%' style='font-weight:bold;' valign='top'>Training : </td><td width='40%' valign='top' align='left' colspan='4'>"+Liste_Formation[j][4]+"<br/><div id='compteur_"+Liste_Formation[j][0]+"' style='display: inline'></div></td></tr>";
						}
						nbJours=Liste_Formation[j][7];
					}
					else{
						if(document.getElementById('Langue').value=="FR"){
							table+="<tr  bgcolor='"+couleur+"'><td width='15%' style='font-weight:bold;' valign='top'>Initiale / Recyclage : </td><td valign='top' width='10%' align='left'>Recyclage</td>";
							table+="<td width='10%' style='font-weight:bold;' valign='top'>Formation : </td><td width='40%' valign='top' align='left' colspan='4'>"+Liste_Formation[j][5]+"<br/><div id='compteur_"+Liste_Formation[j][0]+"' style='display: inline'></div></td></tr>";
						}
						else{
							table+="<tr  bgcolor='"+couleur+"'><td width='15%' style='font-weight:bold;' valign='top'>Initial / Recycling : </td><td valign='top' width='10%' align='left'>Recycling</td>";
							table+="<td width='10%' style='font-weight:bold;' valign='top'>Training : </td><td width='40%' valign='top' align='left' colspan='4'>"+Liste_Formation[j][5]+"<br/><div id='compteur_"+Liste_Formation[j][0]+"' style='display: inline'></div></td></tr>";
						}
						nbJours=Liste_Formation[j][8];
					}
					for(k=1;k<=nbJours;k++){
						
						table+="<tr  bgcolor='"+couleur+"'>";
						table+="<td class=\"Libelle\" width=\"15%\">Date : </td>";
						
						table+="<td width=\"20%\"><input type=\"date\" onmousedown=\"datepick();\" name=\"dateDebut_"+Liste_Formation[j][0]+"_"+k+"\" id=\"dateDebut_"+Liste_Formation[j][0]+"_"+k+"\" size=\"10\" value=\"\"></td>";
						if(Langue=="FR"){table+="<td class=\"Libelle\" width=\"15%\">Heure de début</td>";}
						else{table+="<td class=\"Libelle\" width=\"15%\">Start time</td>";}
						table+="<td width=\"15%\">";
						onchange2="";
						if(nbJours==1){onchange2="onchange=\"ModifierHeureFin("+k+","+Liste_Formation[j][0]+","+Liste_GroupeFormation[i][3]+")\"";}
						else{onchange2="onchange=\"calculNbHeuresRestantes('D',"+k+","+Liste_Formation[j][0]+","+Liste_GroupeFormation[i][3]+")\"";}
						table+="<select name=\"heureDebut_"+Liste_Formation[j][0]+"_"+k+"\" id=\"heureDebut_"+Liste_Formation[j][0]+"_"+k+"\" "+onchange2+">";
						table+="<option value=\"0\"></option>";
						heure=5;
						min=0;
						for(h=1;h<=61;h++){
							if(min==0){minAffiche="0";}
							else{minAffiche=min;}
							selected="";
							table+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2);
							table+="\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2);
							table+="</option>";
							if(min==0){min=15;}
							else if(min==15){min=30;}
							else if(min==30){min=45;}
							else{min=0;heure++;}
						}
						table+="</select>";
						table+="</td>";
						if(Langue=="FR"){table+="<td class=\"Libelle\" width=\"10%\">Heure de fin</td>";}
						else{table+="<td class=\"Libelle\" width=\"10%\">End time</td>";}
						table+="<td width=\"15%\">";
						if(nbJours==1){
							table+="<input name=\"heureFin_"+Liste_Formation[j][0]+"_"+k+"\" id=\"heureFin_"+Liste_Formation[j][0]+"_"+k+"\" size=\"10\" type=\"text\" value=\"\" readonly=\"readonly\">";
						}
						else{
							table+="<select name=\"heureFin_"+Liste_Formation[j][0]+"_"+k+"\" id=\"heureFin_"+Liste_Formation[j][0]+"_"+k+"\" onchange=\"calculNbHeuresRestantes('F',"+k+","+Liste_Formation[j][0]+")\" >";
							table+="<option value=\"0\"></option>";
							heure=5;
							min=0;
							for(h=1;h<=61;h++){
								if(min==0){minAffiche="0";}
								else{minAffiche=min;}
								selected="";
								table+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)+"</option>";
								if(min==0){min=15;}
								else if(min==15){min=30;}
								else if(min==30){min=45;}
								else{min=0;heure++;}
							}
							table+="</select>";
						}
						
						table+="</td>";
						table+="</tr>";
						table+="<tr class=\"TitreColsUsers\"  bgcolor='"+couleur+"'>";
						table+="<td></td>";
						table+="<td></td>";
						if(Langue=="FR"){table+="<td  class=\"Libelle\">Pause repas : </td>";}
						else{table+="<td  class=\"Libelle\">Lunch break : </td>";}
						table+="<td>";
						table+="<select id=\"pauseRepas_"+Liste_Formation[j][0]+"_"+k+"\" name=\"pauseRepas_"+Liste_Formation[j][0]+"_"+k+"\" onchange=\"VerifHeuresPause("+nbJours+",'D',"+k+","+Liste_Formation[j][0]+","+Liste_GroupeFormation[i][3]+")\">";
						if(Langue=="FR"){Tableau= new Array('Oui|1','Non|0');}
						else{Tableau= new Array('Yes|1','No|0');}
						for(var m = 0; m < Tableau.length; m++){
							valeur=Tableau[m].split("|");
							table+="<option value='"+valeur[1]+"' ";
							table+=">"+valeur[0]+"</option>\n";
						}
						
						table+="</select>";
						table+="</td>";
						if(Langue=="FR"){table+="<td id=\"td_heurepause"+k+"\" class=\"Libelle\" width=\"10%\">De&nbsp;</td>";}
						else{table+="<td id=\"td_heurepause"+k+"\" class=\"Libelle\" width=\"10%\">From&nbsp;</td>";}
						table+="<td id=\"td_heurepause2"+k+"\" class=\"Libelle\" width=\"15%\">";
						table+="<select name=\"heureDebutPause_"+Liste_Formation[j][0]+"_"+k+"\" id=\"heureDebutPause_"+Liste_Formation[j][0]+"_"+k+"\" onchange=\"VerifHeuresPause("+nbJours+",'D',"+k+","+Liste_Formation[j][0]+","+Liste_GroupeFormation[i][3]+")\" >";
						heure=5;
						min=0;
						for(h=1;h<=61;h++){
							if(min==0){minAffiche="0";}
							else{minAffiche=min;}
							selected="";
							if(heure==12 && min==0){selected="selected";}
							table+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">";
							table+=("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)
							table+="</option>";
							if(min==0){min=15;}
							else if(min==15){min=30;}
							else if(min==30){min=45;}
							else{min=0;heure++;}
						}
						table+="</select>";
						if(Langue=="FR"){table+="&nbsp;à&nbsp;";}
						else{table+="&nbsp;to&nbsp;";}
						table+="<select name=\"heureFinPause_"+Liste_Formation[j][0]+"_"+k+"\" id=\"heureFinPause_"+Liste_Formation[j][0]+"_"+k+"\" onchange=\"VerifHeuresPause("+nbJours+",'D',"+k+","+Liste_Formation[j][0]+","+Liste_GroupeFormation[i][3]+")\" >";
						heure=5;
						min=0;
						for(h=1;h<=61;h++){
							if(min==0){minAffiche="0";}
							else{minAffiche=min;}
							selected="";
							if(heure==13 && min==0){selected="selected";}
							table+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)+"</option>";
							if(min==0){min=15;}
							else if(min==15){min=30;}
							else if(min==30){min=45;}
							else{min=0;heure++;}
						}
						table+="</select>";
						table+="</td>";
						table+="</tr>";
					}
					//Lieux et formateurs
					table+="<tr  bgcolor='"+couleur+"'>";
					if(Langue=="FR"){table+="<td  class='Libelle'>Lieu : </td>";}
					else{table+="<td  class='Libelle'>Place : </td>";}
					table+="<td>";
					table+="<select name='lieu_"+Liste_Formation[j][0]+"' id='lieu_"+Liste_Formation[j][0]+"'>";
					table+="<option value='0'></option>";
					for(l=0;l<Liste_Lieu.length;l++){
						table+="<option value='"+Liste_Lieu[l][0]+"'>"+Liste_Lieu[l][1]+"</option>\n";
					}
					table+="</select>";
					table+="</td>";
					if(Langue=="FR"){table+="<td  class='Libelle'>Formateur : </td>";}
					else{table+="<td  class='Libelle'>Former : </td>";}
					table+="<td>";
					table+="<select name='formateur_"+Liste_Formation[j][0]+"' id='formateur_"+Liste_Formation[j][0]+"'>";
					table+="<option value='0'></option>";
					for(l=0;l<Liste_Formateur.length;l++){
						table+="<option value='"+Liste_Formateur[l][0]+"'>"+Liste_Formateur[l][1]+"</option>\n";
					}
					table+="</select>";
					table+="</td>";
					table+="</tr>";
					
					table+="<tr  bgcolor='"+couleur+"'>";
					if(Langue=="FR"){table+="<td  class='Libelle' colspan='6'>Message à l'attention des stagiaires (convocation) : </td>";}
					else{table+="<td  class='Libelle' colspan='6'>Message for trainees (convocation) : </td>";}
					table+="</tr>";
					table+="<tr  bgcolor='"+couleur+"'>";
					table+="<td colspan='6'> ";
					table+="<textarea name='message_"+Liste_Formation[j][0]+"' rows='3' cols='140' style='resize:none'></textarea>";
					table+="</td>";
					table+="</tr>";
					
					table+="<tr  bgcolor='"+couleur+"'>";
					if(Langue=="FR"){table+="<td  class='Libelle' colspan='6'>Message lors des inscriptions : </td>";}
					else{table+="<td  class='Libelle' colspan='6'>Registration message : </td>";}
					table+="</tr>";
					table+="<tr  bgcolor='"+couleur+"'>";
					table+="<td colspan='6'> ";
					table+="<textarea name='messageInscription_"+Liste_Formation[j][0]+"' rows='3' cols='140' style='resize:none'></textarea>";
					table+="</td>";
					table+="</tr>";
					
					if(document.getElementById('formationsLiees').value==0){
						table+="<tr bgcolor='"+couleur+"'>";
						//Si les formations ne sont pas liées
						//Nb stagiaires
						if(Langue=="FR"){table+="<td style='font-weight:bold;'>Nb stagiaires mini : </td><td align='left'>";}
						else{table+="<td style='font-weight:bold;'>Number of trainees minimum : </td><td align='left'>";}
						table+="<input onKeyUp='nombre(this)' name='stagiaireMin_"+Liste_Formation[j][0]+"' id='stagiaireMin_"+Liste_Formation[j][0]+"' style='width:40px;' type='text' value=''>";
						table+="</td>";
						if(Langue=="FR"){table+="<td style='font-weight:bold;'>Nb stagiaires maxi : </td><td align='left' colspan='3'>";}
						else{table+="<td style='font-weight:bold;'>Number of trainees maximum : </td><td align='left' colspan='3'>";}
						table+="<input onKeyUp='nombre(this)' name='stagiaireMax_"+Liste_Formation[j][0]+"' id='stagiaireMax_"+Liste_Formation[j][0]+"' style='width:40px;' type='text' value=''>";
						table+="</td>";
						table+="</tr>";
						
					}
					table+="<tr  bgcolor='"+couleur+"'><td colspan='6' style='border-bottom:1px dotted #1a23f0'></td></tr>";
				}
			}
		}
		if(couleur=='#d2fbcf'){couleur='#ffffff'}
		else{couleur='#d2fbcf'}
	}
	table+="</table>";
	document.getElementById('div_Formations').innerHTML=table;
	for(i=0;i<Liste_GroupeFormation.length;i++){
		if (Liste_GroupeFormation[i][1]==document.getElementById('groupeFormation').value){
			//Pour chaque formation du groupe de formation, recherche des informations : Type de la formation|Nom de la formation
			for(j=0;j<Liste_Formation.length;j++){
				if (Liste_GroupeFormation[i][2]==Liste_Formation[j][0]){
					if(Liste_GroupeFormation[i][3]==0){nbJours=Liste_Formation[j][7];}
					else{nbJours=Liste_Formation[j][8];}
					if(nbJours>=1){
						ModifierDates(Langue,Liste_Formation[j][0],Liste_GroupeFormation[i][3]);
					}
				 }
			}
		}
	}
}