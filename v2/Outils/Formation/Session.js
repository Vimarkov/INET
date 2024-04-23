function VerifChamps(){
	if(formulaire.formation.value=='0'){
		if(document.getElementById('Langue').value=="FR"){
			alert('Vous n\'avez pas renseigné la formation.');return false;
		}
		else{
			alert('You did not complete the training.');return false;
		}
	}
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
	for(i=1;i<=document.getElementById('nbJours').value;i++){
		if(document.getElementById('dateDebut'+i).value==''){
			if(document.getElementById('Langue').value=="FR"){
				alert('Vous n\'avez pas renseigné la date de formation.');
				return false;
			}
			else{
				alert('You did not fill in the training date.');
				return false;
			}
		}
	}
	if(document.getElementById('nbJours').value>0){
		//Verifier que les jours sont différents
		bOK=0;
		var liste = new Array();
		ligne=0;
		for(i=1;i<=document.getElementById('nbJours').value;i++){
			for(k=0;k<liste.length;k++){
				if(document.getElementById('dateDebut'+i).value==liste[k]){
					if(document.getElementById('Langue').value=="FR"){
						alert('Vous ne pouvez-vous pas mettre 2 dates identiques.');return false;
					}
					else{
						alert('You can not put 2 identical dates.');return false;
					}	
				}
			}
			liste[ligne]=document.getElementById('dateDebut'+i).value;
		}
		//Verifier heures restantes
		if(formulaire.heuresRestantes.value>0 || formulaire.minRestantes.value>0){
			if(document.getElementById('Langue').value=="FR"){
				alert('Les heures de sessions ne sont pas corrects.');return false;
			}
			else{
				alert('Session times are not correct.');return false;
			}
		}
	}
	return true;
}

Liste_Formation= new Array();
function ModifierListeFormation(Langues){
	var sel="";
	var isElement = false;
	selected="";
	bSelect=0;
	sel ="<select name='formation' id='formation' style='width:200px;' onchange=\"ModifierDates('"+Langues+"')\">";
	for(i=0;i<Liste_Formation.length;i++){
		if (Liste_Formation[i][1]==document.getElementById('Id_TypeFormation').value && (document.getElementById('formationR').value=="0" ||Liste_Formation[i][6]==document.getElementById('formationR').value)){
			selected="";
			if(bSelect==0){
				selected="selected";
				bSelect=1;
			}
			if(document.getElementById('formationR').value=="0"){
				sel= sel + "<option value=\""+Liste_Formation[i][0]+"\" "+selected+">"+Liste_Formation[i][4]+"</option>";
			}
			else{
				sel= sel + "<option value=\""+Liste_Formation[i][0]+"\" "+selected+">"+Liste_Formation[i][5]+"</option>";
			}
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	
	document.getElementById('divFormation').innerHTML=sel;
	ModifierDates(Langues);
}
function ModifierDates(Langue){
	var heurePlus=0;
	var minPlus=0;
	var compteur="";
	var tabDates="";
	var nbJours=1;
	var onchange="";
	for(i=0;i<Liste_Formation.length;i++){
		if (Liste_Formation[i][0]==document.getElementById('formation').value){
			if(document.getElementById('formationR').value=="0"){
				tabHeurePlus = Liste_Formation[i][2].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if(document.getElementById('Langue').value=="FR"){
					if (Liste_Formation[i][7]>=0){nbJours=Liste_Formation[i][7];compteur="Nbr d'heures : "+heurePlus+":"+minPlus;}
				}
				else{
					if (Liste_Formation[i][7]>=0){nbJours=Liste_Formation[i][7];compteur="Number of hours : "+heurePlus+":"+minPlus;}
				}
			}
			else{
				tabHeurePlus = Liste_Formation[i][3].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if(document.getElementById('Langue').value=="FR"){
					if (Liste_Formation[i][8]>=0){nbJours=Liste_Formation[i][8];compteur="Nbr d'heures : "+heurePlus+":"+minPlus;}
				}
				else{
					if (Liste_Formation[i][8]>=0){nbJours=Liste_Formation[i][8];compteur="Number of hours : "+heurePlus+":"+minPlus;}
				}
			}
			
			document.getElementById('compteur').innerHTML=compteur;
		}
	}
	document.getElementById('heurePlus').value = heurePlus;
	document.getElementById('minPlus').value = minPlus;
	document.getElementById('nbJours').value = nbJours;
	tabDates="<table width=\"100%\" id=\"tab_session\" style=\"border:1px  dotted black;\">";
	for(k=1;k<=nbJours;k++){
		tabDates+="<tr>";
		tabDates+="<td class=\"Libelle\" width=\"15%\">Date : </td>";
		
		tabDates+="<td width=\"20%\"><input type=\"date\" onmousedown=\"datepick();\" name=\"dateDebut"+k+"\" id=\"dateDebut"+k+"\" size=\"10\" value=\"\"></td>";
		if(Langue=="FR"){tabDates+="<td class=\"Libelle\" width=\"15%\">Heure de début</td>";}
		else{tabDates+="<td class=\"Libelle\" width=\"15%\">Start time</td>";}
		tabDates+="<td width=\"15%\">";
		if(nbJours==1){onchange="onchange=\"ModifierHeureFin("+k+")\"";}
		else{onchange="onchange=\"calculNbHeuresRestantes('D',"+k+")\"";}
		tabDates+="<select name=\"heureDebut"+k+"\" id=\"heureDebut"+k+"\" "+onchange+">";
		tabDates+="<option value=\"0\"></option>";
		heure=5;
		min=0;
		for(i=1;i<=61;i++){
			if(min==0){minAffiche="0";}
			else{minAffiche=min;}
			selected="";
			tabDates+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)+"</option>";
			if(min==0){min=15;}
			else if(min==15){min=30;}
			else if(min==30){min=45;}
			else{min=0;heure++;}
		}
		tabDates+="</select>";
		tabDates+="</td>";
		if(Langue=="FR"){tabDates+="<td class=\"Libelle\" width=\"10%\">Heure de fin</td>";}
		else{tabDates+="<td class=\"Libelle\" width=\"10%\">End time</td>";}
		tabDates+="<td width=\"15%\">";
		if(nbJours==1){
			tabDates+="<input name=\"heureFin"+k+"\" id=\"heureFin"+k+"\" size=\"10\" type=\"text\" value=\"\" readonly=\"readonly\">";
		}
		else{
			tabDates+="<select name=\"heureFin"+k+"\" id=\"heureFin"+k+"\" onchange=\"calculNbHeuresRestantes('F',"+k+")\" >";
			tabDates+="<option value=\"0\"></option>";
			heure=5;
			min=0;
			for(i=1;i<=68;i++){
				if(min==0){minAffiche="0";}
				else{minAffiche=min;}
				selected="";
				tabDates+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)+"</option>";
				if(min==0){min=15;}
				else if(min==15){min=30;}
				else if(min==30){min=45;}
				else{min=0;heure++;}
			}
			tabDates+="</select>";
		}
		tabDates+="</td>";
		tabDates+="</tr>";
		tabDates+="<tr class=\"TitreColsUsers\">";
		tabDates+="<td></td>";
		tabDates+="<td></td>";
		if(Langue=="FR"){tabDates+="<td  class=\"Libelle\">Pause repas : </td>";}
		else{tabDates+="<td  class=\"Libelle\">Lunch break : </td>";}
		tabDates+="<td>";
		tabDates+="<select id=\"pauseRepas"+k+"\" name=\"pauseRepas"+k+"\" onchange=\"VerifHeuresPause("+nbJours+",'D',"+k+")\">";
		if(Langue=="FR"){Tableau= new Array('Oui|1','Non|0');}
		else{Tableau= new Array('Yes|1','No|0');}
		for(var i = 0; i < Tableau.length; i++){
			valeur=Tableau[i].split("|");
			tabDates+="<option value='"+valeur[1]+"' ";
			tabDates+=">"+valeur[0]+"</option>\n";
		}
		tabDates+="</select>";
		tabDates+="</td>";
		if(Langue=="FR"){tabDates+="<td id=\"td_heurepause"+k+"\" class=\"Libelle\" width=\"10%\">De&nbsp;</td>";}
		else{tabDates+="<td id=\"td_heurepause"+k+"\" class=\"Libelle\" width=\"10%\">From&nbsp;</td>";}
		tabDates+="<td id=\"td_heurepause2"+k+"\" class=\"Libelle\" width=\"15%\">";
		tabDates+="<select name=\"heureDebutPause"+k+"\" id=\"heureDebutPause"+k+"\" onchange=\"VerifHeuresPause("+nbJours+",'D',"+k+")\" >";
		heure=5;
		min=0;
		for(i=1;i<=61;i++){
			if(min==0){minAffiche="0";}
			else{minAffiche=min;}
			selected="";
			if(heure==12 && min==0){selected="selected";}
			tabDates+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)+"</option>";
			if(min==0){min=15;}
			else if(min==15){min=30;}
			else if(min==30){min=45;}
			else{min=0;heure++;}
		}
		tabDates+="</select>";
		if(Langue=="FR"){tabDates+="&nbsp;à&nbsp;";}
		else{tabDates+="&nbsp;to&nbsp;";}
		tabDates+="<select name=\"heureFinPause"+k+"\" id=\"heureFinPause"+k+"\" onchange=\"VerifHeuresPause("+nbJours+",'D',"+k+")\" >";
		heure=5;
		min=0;
		for(i=1;i<=61;i++){
			if(min==0){minAffiche="0";}
			else{minAffiche=min;}
			selected="";
			if(heure==13 && min==0){selected="selected";}
			tabDates+="<option value=\""+("0"+heure).slice(-2)+":"+("0"+min).slice(-2)+"\" "+selected+">"+("0"+heure).slice(-2)+"h"+("0"+minAffiche).slice(-2)+"</option>";
			if(min==0){min=15;}
			else if(min==15){min=30;}
			else if(min==30){min=45;}
			else{min=0;heure++;}
		}
		tabDates+="</select>";
		tabDates+="</td>";
		tabDates+="</tr>";
	}
	tabDates+="</table>";
	document.getElementById('divSession').innerHTML=tabDates;
}
function ModifierHeureFin(id){
	var heureD=0;
	var minD=0;
	var heurePlus=0;
	var minPlus=0;
	var compteur="";
	var tabDates="";
	var nbJours=1;
	for(i=0;i<Liste_Formation.length;i++){
		if (Liste_Formation[i][0]==document.getElementById('formation').value){
			if(document.getElementById('formationR').value=="0"){
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

	document.getElementById('heureFin'+id).value="";
	if(document.getElementById('heureDebut'+id).value!="0"){
		if(document.getElementById('heureDebut'+id).value>=document.getElementById('heureDebutPause'+id).value && document.getElementById('heureDebut'+id).value<document.getElementById('heureFinPause'+id).value){
			document.getElementById('pauseRepas'+id).value=0;
		}
		var tabHeure = document.getElementById('heureDebut'+id).value.split(":");
		heureD=tabHeure[0];
		if(tabHeure[1]!=""){minD=tabHeure[1];}
		for(i=0;i<Liste_Formation.length;i++){
			if (Liste_Formation[i][0]==document.getElementById('formation').value){
				heure = parseInt(heureD)+parseInt(heurePlus);
				minute = minToHour(parseInt(minD)+parseInt(minPlus));
				tabMinutes = minute.split(":");
				heure=heure+parseInt(tabMinutes[0]);
				heure=("0"+parseInt(heure)).slice(-2);
				minute=("0"+parseInt(tabMinutes[1])).slice(-2);
				if(document.getElementById('pauseRepas'+id).value==1){
					//Ajout de la pause si nécessaire
					if(heure+":"+minute > document.getElementById('heureDebutPause'+id).value && heureD+":"+minD < document.getElementById('heureFinPause'+id).value){
						var tabHeure = document.getElementById('heureDebutPause'+id).value.split(":");
						heureD=tabHeure[0];
						if(tabHeure[1]!=""){minD=tabHeure[1];}
						
						var tabHeure = document.getElementById('heureFinPause'+id).value.split(":");
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
				document.getElementById('heureFin'+id).value = heure+":"+minute;
			}
		}
	}
}

function VerifHeuresPause(nbJour,Typeheure,id){
	if(document.getElementById('pauseRepas'+id).value==0){
	}
	else{
		if(document.getElementById('heureFinPause'+id).value < document.getElementById('heureDebutPause'+id).value){
			if(Typeheure=="D"){document.getElementById('heureFinPause'+id).value=document.getElementById('heureDebutPause'+id).value;}
			else{document.getElementById('heureDebutPause'+id).value=document.getElementById('heureFinPause'+id).value;}
		}
	}
	if(nbJour>1){calculNbHeuresRestantes(Typeheure,id);}
	else{ModifierHeureFin(id);}
}
function calculNbHeuresRestantes(Typeheure,id){
	if(document.getElementById('heureFin'+id).value < document.getElementById('heureDebut'+id).value){
		if(Typeheure=="D"){document.getElementById('heureFin'+id).value=document.getElementById('heureDebut'+id).value;}
		else{document.getElementById('heureDebut'+id).value=document.getElementById('heureFin'+id).value;}
	}
	
	if((document.getElementById('heureDebut'+id).value>=document.getElementById('heureDebutPause'+id).value && document.getElementById('heureDebut'+id).value<document.getElementById('heureFinPause'+id).value) ||
		(document.getElementById('heureFin'+id).value>document.getElementById('heureDebutPause'+id).value && document.getElementById('heureFin'+id).value<=document.getElementById('heureFinPause'+id).value)){
		document.getElementById('pauseRepas'+id).value=0;
	}
	MajCompteur();
}
function MajCompteur(){
	var heurePlus = document.getElementById('heurePlus').value;
	var minPlus = document.getElementById('minPlus').value;
	var heure=0;
	var minute=0;
	var heureD=0;
	var minD=0;
	var heureF=0;
	var minF=0;
	var heureFinal=0;
	var minFinal=0;
	
	for(i=1;i<=document.getElementById('nbJours').value;i++){
		if(document.getElementById('heureDebut'+i).value!="0"){
			var tabHeure = document.getElementById('heureDebut'+i).value.split(":");
			heureD=tabHeure[0];
			if(tabHeure[1]!=""){minD=tabHeure[1];}
			
			var tabHeure = document.getElementById('heureFin'+i).value.split(":");
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
			if(document.getElementById('pauseRepas'+i).value==1){
				//Ajout de la pause
				if(document.getElementById('heureFin'+i).value > document.getElementById('heureDebutPause'+i).value && document.getElementById('heureDebut'+i).value < document.getElementById('heureFinPause'+i).value){
					var tabHeure = document.getElementById('heureDebutPause'+i).value.split(":");
					heureDPause=tabHeure[0];
					if(tabHeure[1]!=""){minDPause=tabHeure[1];}
					
					var tabHeure = document.getElementById('heureFinPause'+i).value.split(":");
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
		document.getElementById('compteur').innerHTML="Nbr d'heures : "+heurePlus+":"+minPlus+"<br>Nbr d'heures restantes : "+heureFinal+":"+minFinal;
	}
	else{
		document.getElementById('compteur').innerHTML="Number of hours : "+heurePlus+":"+minPlus+"<br>Number of hours remaining : "+heureFinal+":"+minFinal;
	}
	document.getElementById('heuresRestantes').value=heureFinal;
	document.getElementById('minRestantes').value=minFinal;
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

function MiseAJourCompteur(Langue){
	var heurePlus=0;
	var minPlus=0;
	var compteur="";
	var nbJours=1;
	var onchange="";
	for(i=0;i<Liste_Formation.length;i++){
		if (Liste_Formation[i][0]==document.getElementById('formation').value){
			if(document.getElementById('formationR').value=="0"){
				tabHeurePlus = Liste_Formation[i][2].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if(document.getElementById('Langue').value=="FR"){
					if (Liste_Formation[i][7]>=0){nbJours=Liste_Formation[i][7];compteur="Nbr d'heures : "+heurePlus+":"+minPlus;}
				}
				else{
					if (Liste_Formation[i][7]>=0){nbJours=Liste_Formation[i][7];compteur="Number of hours : "+heurePlus+":"+minPlus;}
				}
			}
			else{
				tabHeurePlus = Liste_Formation[i][3].split(".");
				heurePlus=tabHeurePlus[0];
				if(tabHeurePlus[1]!=""){minPlus=tabHeurePlus[1];}
				if(document.getElementById('Langue').value=="FR"){
					if (Liste_Formation[i][8]>=0){nbJours=Liste_Formation[i][8];compteur="Nbr d'heures : "+heurePlus+":"+minPlus;}
				}
				else{
					if (Liste_Formation[i][8]>=0){nbJours=Liste_Formation[i][8];compteur="Number of hours : "+heurePlus+":"+minPlus;}
				}
			}
			
			document.getElementById('compteur').innerHTML=compteur;
		}
	}
	document.getElementById('heurePlus').value = heurePlus;
	document.getElementById('minPlus').value = minPlus;
	document.getElementById('nbJours').value = nbJours;
}