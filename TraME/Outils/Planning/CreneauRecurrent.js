function FermerEtRecharger(laDate){
	window.opener.parent.location="Planning.php?laDate="+laDate;
	window.close();
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
Liste_Presta = new Array();
Liste_Tache_WP = new Array();
Liste_WP = new Array();
function RechargerWP(Langue){
	var i;
	var sel="";
	var isElement = false;
	var bValide = true;
	sel ="<select name=\"wp\" id=\"wp\" onchange=\"RechargerTache()\">";
	for(i=0;i<Liste_WP.length;i++){
		if (Liste_WP[i][1]==document.getElementById('new_event_presta').value){
			sel= sel + "<option value='"+Liste_WP[i][0];
			sel= sel + "'>"+Liste_WP[i][2]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('divWP').innerHTML=sel;
	RechargerTache();
}
function RechargerTache(){
	var i;
	var sel="";
	var isElement = false;
	var bValide = true;
	sel ="<select name='new_event_tache' id='new_event_tache'>";
	for(i=0;i<Liste_Tache_WP.length;i++){
		if (Liste_Tache_WP[i][1]==document.getElementById('wp').value && Liste_Tache_WP[i][2]=="0"){
			sel= sel + "<option value='"+Liste_Tache_WP[i][0];
			sel= sel + "'>"+Liste_Tache_WP[i][3]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('divTache').innerHTML=sel;
}
function VerifChamps(langue){
	myDateDebut = document.getElementById('dateDebut').value;
	myDateDebut2 = myDateDebut.split("-");
	if (myDateDebut2.length == 1){
		myDateDebut = myDateDebut.split("/");
		newDateDebut = myDateDebut[2]+"-"+myDateDebut[1]+"-"+myDateDebut[0];}
	else{
		myDateDebut = myDateDebut.split("-");
		newDateDebut = myDateDebut[0]+"-"+myDateDebut[1]+"-"+myDateDebut[2];
	}
	myDateFin = document.getElementById('dateFin').value;
	myDateFin2 = myDateFin.split("-");
	if (myDateFin2.length == 1){
		myDateFin = myDateFin.split("/");
		newDateFin = myDateFin[2]+"-"+myDateFin[1]+"-"+myDateFin[0];}
	else{
		myDateFin = myDateFin.split("-");
		newDateFin = myDateFin[0]+"-"+myDateFin[1]+"-"+myDateFin[2];
	}
	
	if(langue=="EN"){
		if(document.getElementById('new_event_tache').value=='0' || document.getElementById('new_event_tache').value==''){alert('You didn\'t enter the task.');return false;}
		if(document.getElementById('dateDebut').value==''){alert('You didn\'t enter the start date.');return false;}
		if(document.getElementById('dateFin').value==''){alert('You didn\'t enter the end date.');return false;}
		if(document.getElementById('heureDebut').value=='' || document.getElementById('heureDebut').value=='0:00:00'){alert('You didn\'t enter the start hour.');return false;}
		if(document.getElementById('heureFin').value=='' || document.getElementById('heureFin').value=='0:00:00'){alert('You didn\'t enter the end hour.');return false;}
		if(newDateDebut>newDateFin){alert('The start date must be less than the end date.');return false;}
		$heureDebut=document.getElementById('heureDebut').value;
		if($heureDebut.length<8){$heureDebut="0"+$heureDebut;}
		$heureFin=document.getElementById('heureFin').value;
		if($heureFin.length<8){$heureFin="0"+$heureFin;}
		if($heureDebut>$heureFin){alert('The start time must be less than the end time.');return false;}
		
	}
	else{
		if(document.getElementById('new_event_tache').value=='0'  || document.getElementById('new_event_tache').value==''){alert('Vous n\'avez pas renseigné la tâche.');return false;}
		if(document.getElementById('dateDebut').value==''){alert('Vous n\'avez pas renseigné la date de début.');return false;}
		if(document.getElementById('dateFin').value==''){alert('Vous n\'avez pas renseigné la date de fin.');return false;}
		if(document.getElementById('heureDebut').value=='' || document.getElementById('heureDebut').value=='0:00:00'){alert('Vous n\'avez pas renseigné l\'heure de début.');return false;}
		if(document.getElementById('heureFin').value=='' || document.getElementById('heureFin').value=='0:00:00'){alert('Vous n\'avez pas renseigné l\'heure de fin.');return false;}
		if(newDateDebut>newDateFin){alert('La date de début doit être inférieure à la date de fin.');return false;}
		$heureDebut=document.getElementById('heureDebut').value;
		if($heureDebut.length<8){$heureDebut="0"+$heureDebut;}
		$heureFin=document.getElementById('heureFin').value;
		if($heureFin.length<8){$heureFin="0"+$heureFin;}
		if($heureDebut>$heureFin){alert('L\'heure de début doit être inférieure à l\'heure de fin.');return false;}
	}
	return true;
}

function ModifDate(){
	//Affectation date de fin en fonction de la date de début
	myDateDebut = formulaire.dateDebut.value;
	myDateDebut2 = myDateDebut.split("-");
	if (myDateDebut2.length == 1){
		myDateDebut = myDateDebut.split("/");
		newDateDebut = myDateDebut[2]+"-"+myDateDebut[1]+"-"+myDateDebut[0];}
	else{
		myDateDebut = myDateDebut.split("-");
		newDateDebut = myDateDebut[0]+"-"+myDateDebut[1]+"-"+myDateDebut[2];
	}
	myDateFin = formulaire.dateFin.value;
	myDateFin2 = myDateFin.split("-");
	if (myDateFin2.length == 1){
		myDateFin = myDateFin.split("/");
		newDateFin = myDateFin[2]+"-"+myDateFin[1]+"-"+myDateFin[0];}
	else{
		myDateFin = myDateFin.split("-");
		newDateFin = myDateFin[0]+"-"+myDateFin[1]+"-"+myDateFin[2];
	}
	if(issetfocus=="dateDebut"){
		if(formulaire.dateDebut.value!=""){
			if(formulaire.dateFin.value==""){
				formulaire.dateFin.value=formulaire.dateDebut.value;
			}
			else{
				if(newDateFin<newDateDebut){
					formulaire.dateFin.value=formulaire.dateDebut.value;
				}
			}
		}
	}
	else if(issetfocus=="dateFin"){
		if(formulaire.dateFin.value!=""){
			if(formulaire.dateDebut.value==""){
				formulaire.dateDebut.value=formulaire.dateFin.value;
			}
			else{
				if(newDateFin<newDateDebut){
					formulaire.dateDebut.value=formulaire.dateFin.value;
				}
			}
		}
	}
}