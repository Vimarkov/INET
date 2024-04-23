function FermerEtRecharger(){
	opener.location.reload();
	window.close();
}
function Recharger(){
	window.location="Tableau_De_Bord.php";
}
function Recharger2(){
	window.location="Liste_Surveillance.php";
}
function VerifChamps(){
	if(formulaire.Id_Prestation.value=='0' || formulaire.Id_Prestation.value==''){alert('Vous n\'avez pas renseigné la prestation.');return false;}
	if(formulaire.Id_Surveille.value=='0' || formulaire.Id_Surveille.value==''){alert('Vous n\'avez pas renseigné le surveillé.');return false;}
	if(formulaire.Id_Surveillant.value=='0' || formulaire.Id_Surveillant.value==''){alert('Vous n\'avez pas renseigné le surveillant.');return false;}
	if(formulaire.DatePlanif.value==''){alert('Vous n\'avez pas renseigné la date de surveillance.');return false;}
	if(formulaire.Id_Questionnaire.value=='0' || formulaire.Id_Questionnaire.value==''){alert('Vous n\'avez pas renseigné le questionnaire.');return false;}
	
	var idsQuestion = document.getElementById('idsQuestion').value;
	tab = idsQuestion.split(";");
	for(var i= 0; i < tab.length; i++)
	{
		checkedObjet=false;
		var Elements_Obj = document.getElementsByName('radio_'+tab[i]);
		for(var k=0, l=Elements_Obj.length; k<l; k++){
			if(Elements_Obj[k].checked){
				checkedObjet=true;
			}
		}
		if(checkedObjet==false){alert("Veuillez renseigner les conformités");return false;}
	}
	
	var inputSignatures = document.getElementsByClassName('signatures');
	nSignatures=0;
	for(var i=0; inputSignatures[i]; i++){
		  if(inputSignatures[i].checked){
			   nSignatures++;
		  }
	}
	
	if(nSignatures!=2){
		if(document.getElementById('Langue').value=="FR"){
			Confirm=window.confirm('Surveillance non signée par le surveillant et/ou le surveillé. Voulez-vous quitter ? Si OK, les données seront enregistrées mais le questionnaire restera en statut planifié');
		}else{
			Confirm=window.confirm('Surveillance not signed by the supervisor and/or by the supervised person. Do you want to quit ? If OK, data will be saved but the questionnaire will stay on planned status');
		}
		if(Confirm==false){return false;}
	}
	
	var inputRadio = document.getElementsByClassName('radioNote');
	for(var k=0, l=inputRadio.length; k<l; k++){
		if(inputRadio[k].checked){
			if(inputRadio[k].value=="NC"){
				valeur=inputRadio[k].name.substr(6);
				if(document.getElementById('observation_'+valeur).value=='' || document.getElementById('action_'+valeur).value==''){
					alert('Veuillez renseigner les descriptions et les actions des réponses NC');return false;
				}
			}
		}
	}
	
	var inputActions = document.getElementsByClassName('actions');
	nbActionsTrackers=0;
	for(var i=0; inputActions[i]; i++){
		  if(inputActions[i].value=="Action immédiate + Action Tracker" || inputActions[i].value=="Immediate action + Action Tracker"){
			   nbActionsTrackers++;
		  }
	}
	
	if(document.getElementById('score').value<80){
		if(nbActionsTrackers==0){
			if(document.getElementById('Langue').value=="FR"){
				alert('Veuillez renseigner au moins une action "Action immédiate + Action Tracker"');return false;
			}
			else{
				alert('Please fill in at least one action "Immediate action + Action Tracker"');return false;
			}
		}
	}
	
	if(nbActionsTrackers>0 && (document.getElementById('numActionTraker').value=="" || document.getElementById('numActionTraker').value=="0")){
		if(document.getElementById('Langue').value=="FR"){
			Confirm=window.confirm('N° fiche Action Tracker requis et non renseigné. Voulez-vous quitter ? Si OK, les données seront enregistrées mais le questionnaire restera en statut planifié.');
		}else{
			Confirm=window.confirm('Action Tracker form # requested and not filled-in. Do you want to quit ? If OK, data will be saved but the questionnaire will stay on planned status.');
		}
		if(Confirm==false){return false;}
	}
	
	return true;
}
function Change_Note(langue){
	var i;
	var total = 0;
	var C = 0;
	var NA = 0;
	var lnote = 0;
	var idQuestion = "";
	table = document.getElementsByTagName('input');
	for (l=0;l<table.length;l++){
		if (table[l].type == 'radio'){
			idQuestion = table[l].name.substr(6);
			if(table[l].value == 'C' && table[l].checked == true){
				C++;
				total++;
				document.getElementById('tr_'+idQuestion).style.display = "none";
				document.getElementById('tr2_'+idQuestion).style.display = "none";
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "";
			}
			else if(table[l].value == 'NA' && table[l].checked == true){
				NA++;
				document.getElementById('tr_'+idQuestion).style.display = "";
				document.getElementById('tr2_'+idQuestion).style.display = "";
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "none";
				
				if(langue=="FR"){
					document.getElementById('observation_'+idQuestion).placeholder = "Cause de la non applicabilité";
				}
				else{
					document.getElementById('observation_'+idQuestion).placeholder = "Cause of non-applicability";
				}
			}
			else if(table[l].value == 'NC' && table[l].checked == true){
				total++;
				document.getElementById('tr_'+idQuestion).style.display = "";
				document.getElementById('tr2_'+idQuestion).style.display = "";
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "";
				
				if(langue=="FR"){
					document.getElementById('observation_'+idQuestion).placeholder = "Description de la NC";
				}
				else{
					document.getElementById('observation_'+idQuestion).placeholder = "NC Description";
				}
			}
		}
	}
	if (total > 0){
		lnote = Math.round((C / total)*100);
		document.getElementById('note').value = lnote+"%" ;
		document.getElementById('score').value = lnote ;
	}
	else{
		document.getElementById('note').value = "100%";
		document.getElementById('score').value = 100 ;
	}
}

function AfficherMasquerlesNC(langue){
	table = document.getElementsByTagName('input');
	
	if(document.getElementById('CB_NC').checked == true){
		for (l=0;l<table.length;l++){
			if (table[l].type == 'radio'){
				idQuestion = table[l].name.substr(6);
				if(table[l].value == 'C' && table[l].checked == true){
					document.getElementById('tr1_'+idQuestion).style.display = "none";
					document.getElementById('tr3_'+idQuestion).style.display = "none";
					document.getElementById('tr_'+idQuestion).style.display = "none";
					document.getElementById('tr2_'+idQuestion).style.display = "none";
				}
				else if(table[l].value == 'NA' && table[l].checked == true){
					document.getElementById('tr1_'+idQuestion).style.display = "none";
					document.getElementById('tr3_'+idQuestion).style.display = "none";
					document.getElementById('tr_'+idQuestion).style.display = "none";
					document.getElementById('tr2_'+idQuestion).style.display = "none";
				}
				else if(table[l].value == 'NC' && table[l].checked == true){
					document.getElementById('tr1_'+idQuestion).style.display = "";
					document.getElementById('tr3_'+idQuestion).style.display = "";
					document.getElementById('tr_'+idQuestion).style.display = "";
					document.getElementById('tr2_'+idQuestion).style.display = "";
				}
			}
		}
	}
	else{
		for (l=0;l<table.length;l++){
			if (table[l].type == 'radio'){
				idQuestion = table[l].name.substr(6);
				document.getElementById('tr1_'+idQuestion).style.display = "";
				document.getElementById('tr1_'+idQuestion).style.display = "";
				document.getElementById('tr1_'+idQuestion).style.display = "";
				
				document.getElementById('tr3_'+idQuestion).style.display = "";
				document.getElementById('tr3_'+idQuestion).style.display = "";
				document.getElementById('tr3_'+idQuestion).style.display = "";
			}
		}
		Change_Note(langue);
	}
}

Liste_Plateforme_Prestation = new Array();
Liste_Plateforme_Personne = new Array();
function Recharge_Liste_Prestation_Personne(){
	var i;
	var sel="";
	var sel1="";
	var sel2="";
	sel ="<select size='1' name='Id_Prestation' style='width:300'>";
	sel1 ="<select size='1' name='Id_Surveillant'>";
	sel2 ="<select size='1' name='Id_Surveille'>";
	for(i=0;i<Liste_Plateforme_Prestation.length;i++){
		if (Liste_Plateforme_Prestation[i][1]==document.getElementById('Id_Plateforme').value){
			sel= sel + "<option value="+Liste_Plateforme_Prestation[i][0];
			sel= sel + ">"+Liste_Plateforme_Prestation[i][2]+"</option>";}
	}
	for(i=0;i<Liste_Plateforme_Personne.length;i++){
		if (Liste_Plateforme_Personne[i][1]==document.getElementById('Id_Plateforme').value){
			sel1= sel1 + "<option value="+Liste_Plateforme_Personne[i][0];
			sel2= sel2 + "<option value="+Liste_Plateforme_Personne[i][0];
			sel1= sel1 + ">"+Liste_Plateforme_Personne[i][2]+"</option>";
			sel2= sel2 + ">"+Liste_Plateforme_Personne[i][2]+"</option>";}
	}
	sel =sel + "</select>";
	sel1 =sel1 + "</select>";
	sel2 =sel2 + "</select>";
	document.getElementById('Prestation').innerHTML=sel;
	document.getElementById('Surveillant').innerHTML=sel1;
	document.getElementById('Surveille').innerHTML=sel2;
}
function nombre(champ)
{
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