Liste_SousATA = new Array();
function SaisieQUALITE(){
	document.getElementById('saisiePROD').style.color="#888888";
	document.getElementById('saisieQUALITE').style.color="#0066CC";
	document.getElementById('typeSaisie').value="QUALITE";

	document.getElementById('LibellePieceauposte').style.display="none";
	document.getElementById('pieceauposte').style.display="none";
	document.getElementById('LibelleTravailRealise').style.display="none";
	document.getElementById('travailRealise').style.display="none";
	document.getElementById('tableProd').style.display="none";
	document.getElementById('tableIngredient').style.display="none";
	
	if(formulaire.droit.value.substr(4,1)=="1"){
		sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
	}
	else{
		sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
	}
	sel= sel + "<option name=\"\" value=\"\"></option>";
	for(i=0;i<Liste_Statut.length;i++){
		sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
	}
	sel =sel + "</select>";
	document.getElementById('statutsQualite').innerHTML=sel;
	
	sel ="<select id='retourQualite' name='retourQualite'>";
	sel= sel + "<option value='0' selected></option>";
	sel =sel + "</select>";
	document.getElementById('retourQ').innerHTML=sel;
	
	AfficherDepose();
}

function SaisiePROD(){
	document.getElementById('saisiePROD').style.color="#0066CC";
	document.getElementById('saisieQUALITE').style.color="#888888";
	document.getElementById('typeSaisie').value="PROD";
	
	document.getElementById('LibellePieceauposte').style.display="";
	document.getElementById('pieceauposte').style.display="";
	document.getElementById('LibelleTravailRealise').style.display="";
	document.getElementById('travailRealise').style.display="";
	document.getElementById('tableProd').style.display="";
	document.getElementById('tableIngredient').style.display="";
	
	if(formulaire.droit.value.substr(4,1)=="1"){
		sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
	}
	else{
		sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
	}
	sel= sel + "<option name=\"\" value=\"\"></option>";
	if(document.getElementById('statutProd').value=='TERA' && document.getElementById('statutProd').value=='REWORK'){
		for(i=0;i<Liste_Statut.length;i++){
			sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
		}
	}
	else if(document.getElementById('statutProd').value=='TFS'){
		for(i=0;i<Liste_Statut.length;i++){
			if(Liste_Statut[i][0]=='TVS'){
				sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
			}
		}
	}
	sel =sel + "</select>";
	document.getElementById('statutsQualite').innerHTML=sel;
	
	sel ="<select id='retourQualite' name='retourQualite'>";
	sel= sel + "<option value='0' selected></option>";
	sel =sel + "</select>";
	document.getElementById('retourQ').innerHTML=sel;

	AfficherDepose();
}
function Recharge_SousATA(){
	var i;
	var sel="";
	var isElement = false;
	sel ="<select id='sousata' size='1' name='sousata' onkeypress='if(event.keyCode == 13)Ajouter()'>";
	for(i=0;i<Liste_SousATA.length;i++){
		if (Liste_SousATA[i][0]==document.getElementById('ata').value){
			sel= sel + "<option value='"+Liste_SousATA[i][1];
			sel= sel + "'>"+Liste_SousATA[i][1]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('sousatas').innerHTML=sel;
}
function AfficherDepose(){
	if(document.getElementById('origine').value=="DA"){
		var elements = document.getElementsByClassName('depose');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	else{
		var elements = document.getElementsByClassName('depose');
		for (i=0; i<elements.length; i++){
			elements[i].style.display='none';
		}
	}

}
function Ajouter(){
	if(document.getElementById('ata').value!=""){
		if(document.getElementById('ata_sousata').value.indexOf(document.getElementById('ata').value+"_"+document.getElementById('sousata').value)==-1){
			document.getElementById('ata_sousata').value = document.getElementById('ata_sousata').value+document.getElementById('ata').value+"_"+document.getElementById('sousata').value+";";
			var table = document.getElementById("tab_ATA");
			var row = table.insertRow();
			row.id = document.getElementById('ata').value+"_"+document.getElementById('sousata').value+";";
			var cell = row.insertCell(0);
			cell.innerHTML = document.getElementById('ata').value;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('sousata').value;
			var cell = row.insertCell(2);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('"+document.getElementById('ata').value+"_"+document.getElementById('sousata').value+";')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = btn;
		}
	}
}
function Supprimer(ataSousAta) {
	var row = document.getElementById(ataSousAta);
	row.parentNode.removeChild(row);
	document.getElementById('ata_sousata').value = document.getElementById('ata_sousata').value.replace(ataSousAta,"");
}
function FicheSuiveuse(Id)
	{window.open("FicheSuiveuse.php?Id_Dossier="+Id,"PageFS","status=no,menubar=no,scrollbars=1,width=90,height=40");}	
Liste_AIPI = new Array();
Liste_Reference = new Array();
Liste_ReferenceAM = new Array();
Liste_ReferenceNC = new Array();
Liste_ReferencePF = new Array();
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
Liste_Personne = new Array();
function AjouterTE(){
	if(document.getElementById('compagnon').value!="0" && document.getElementById('tempsPasse').value!=""){
		if(document.getElementById('travailEffectue').value.indexOf(document.getElementById('compagnon').value+"C_")==-1){
			document.getElementById('travailEffectue').value = document.getElementById('travailEffectue').value+document.getElementById('compagnon').value+"C_"+document.getElementById('tempsPasse').value+';';
			var table = document.getElementById("tab_TravailEffectue");
			var row = table.insertRow();
			row.id = document.getElementById('compagnon').value+"C_"+document.getElementById('tempsPasse').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('"+document.getElementById('compagnon').value+"C_"+document.getElementById('tempsPasse').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var Personne = "";
			for(i=0;i<Liste_Personne.length;i++){
				if (Liste_Personne[i][0]==document.getElementById('compagnon').value){
					Personne = Liste_Personne[i][1]+" "+Liste_Personne[i][2];
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = Personne;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('tempsPasse').value;
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
			
			//Ajout du temps dans le champs tps FI
			document.getElementById('tpsFI').value=parseFloat(document.getElementById('tpsFI').value)+parseFloat(document.getElementById('tempsPasse').value);
			document.getElementById('tpsDossier').value=parseFloat(document.getElementById('tpsDossier').value)+parseFloat(document.getElementById('tempsPasse').value);
		}
	}
}
function SupprimerTE(compagnon){
	var row = document.getElementById(compagnon);
	row.parentNode.removeChild(row);
	document.getElementById('travailEffectue').value = document.getElementById('travailEffectue').value.replace(compagnon+";","");
	document.getElementById('tpsFI').value=parseFloat(document.getElementById('tpsFI').value)-parseFloat(compagnon.substr(compagnon.indexOf("_")+1));
	document.getElementById('tpsDossier').value=parseFloat(document.getElementById('tpsDossier').value)-parseFloat(compagnon.substr(compagnon.indexOf("_")+1));
}
Liste_Ingredient = new Array();
function AjouterIngredient(){
	if(document.getElementById('RefIngredient').value!="" && document.getElementById('numLot').value!="" && document.getElementById('datePeremption').value!=""){
		if(document.getElementById('Ingredient').value.indexOf(document.getElementById('RefIngredient').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value)==-1){
			document.getElementById('Ingredient').value = document.getElementById('Ingredient').value+document.getElementById('RefIngredient').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+';';
			var table = document.getElementById("tab_Ingredient");
			var row = table.insertRow();
			row.id = document.getElementById('RefIngredient').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value;
			
			var Ingredient = "";
			for(i=0;i<Liste_Ingredient.length;i++){
				if (Liste_Ingredient[i][0]==document.getElementById('RefIngredient').value){
					Ingredient = Liste_Ingredient[i][1];
				}
			}
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerIngredient('"+document.getElementById('RefIngredient').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(0);
			cell.innerHTML = Ingredient;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('numLot').value;
			var cell = row.insertCell(2);
			cell.innerHTML = document.getElementById('datePeremption').value;
			var cell = row.insertCell(3);
			cell.innerHTML = document.getElementById('coeffH').value;
			var cell = row.insertCell(4);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerIngredient(Ingredient){
	var row = document.getElementById(Ingredient);
	row.parentNode.removeChild(row);
	document.getElementById('Ingredient').value = document.getElementById('Ingredient').value.replace(Ingredient+';',"");
}

function VerifChamps(){
	if(formulaire.numDossier.value==''){alert('Vous n\'avez pas renseigné le numéro de dossier.');return false;}
	//Verifier existance du dossier
	bExiste=false;
	for(i=0;i<Liste_Reference.length;i++){
		if (Liste_Reference[i][0]!=formulaire.idDossier.value && Liste_Reference[i][1]==formulaire.numDossier.value){
			bExiste = true;
		}
	}
	if(bExiste==true){alert('Ce numéro de dossier existe déjà.');return false;}
	
	if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
	if(formulaire.titre.value==''){alert('Vous n\'avez pas renseigné le titre.');return false;}
	if(formulaire.type.value==''){alert('Vous n\'avez pas renseigné le type.');return false;}
	if(formulaire.tai.value==''){formulaire.tai.value=0;}
	if(formulaire.zone.value=='0'){alert('Vous n\'avez pas renseigné la zone de travail.');return false;}
	if(formulaire.Fuel.checked==false && formulaire.Elec.checked==false && formulaire.Meca.checked==false && formulaire.Hydraulique.checked==false && formulaire.Metal.checked==false && formulaire.Structure.checked==false && formulaire.Systeme.checked==false && formulaire.Oxygene.checked==false){
		alert('Vous n\'avez pas renseigné les compétences.');
		return false;
	}
	if(formulaire.pole.value=='0'){alert('Vous n\'avez pas renseigné le pôle.');return false;}
	if(formulaire.statutProd.value!=''){
		if(formulaire.vacation.value==''){alert('Vous n\'avez pas renseigné la vacation.');return false;}
		if(formulaire.dateIntervention.value==''){alert('Vous n\'avez pas renseigné la date d\'intervention.');return false;}
	}
	if(formulaire.statutQualite.value!=''){
		if(formulaire.vacationQ.value==''){alert('Vous n\'avez pas renseigné la vacation qualité.');return false;}
		if(formulaire.dateInterventionQ.value==''){alert('Vous n\'avez pas renseigné la date d\'intervention qualité.');return false;}
	}
	return true;
}

Liste_Statut = new Array();
Liste_Retour = new Array();
function Recharge_StatutProd(){
	var i;
	var sel="";
	var isElement = false;
	if(formulaire.droit.value.substr(4,1)=="1"){
		sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
	}
	else{
		sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
	}
	sel= sel + "<option name=\"\" value=\"\"></option>";
	if(document.getElementById('statutProd').value=='TERA' || document.getElementById('statutProd').value=='REWORK'){
		for(i=0;i<Liste_Statut.length;i++){
			sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
		}
	}
	else if(document.getElementById('statutProd').value=='TFS'){
		for(i=0;i<Liste_Statut.length;i++){
			if(Liste_Statut[i][0]=='TVS'){
				sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
			}
		}
	}
	sel =sel + "</select>";
	document.getElementById('statutsQualite').innerHTML=sel;
	
	sel ="<select id='retourProd' name='retourProd'>";
	for(i=0;i<Liste_Retour.length;i++){
		if (Liste_Retour[i][2]==document.getElementById('statutProd').value && Liste_Retour[i][3]==0){
			sel= sel + "<option value='"+Liste_Retour[i][0]+"'>"+Liste_Retour[i][1]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('retourP').innerHTML=sel;
	
	sel ="<select id='retourQualite' name='retourQualite'>";
	sel= sel + "<option name='0' value='0' selected></option>";
	sel =sel + "</select>";
	document.getElementById('retourQ').innerHTML=sel;
	
	isElement = false;
	sel ="<select id='avancementProd' name='avancementProd'>";
	if(document.getElementById('statutProd').value=="TFS"){
		isElement = true;
		for(i=0;i<=100;i++){
			sel= sel + "<option value='"+i+"'>"+i+"</option>";
			i=i+4;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('avancementP').innerHTML=sel;
}

function Recharge_StatutQualite(){
	var i;
	var sel="";
	var isElement = false;
	sel ="<select id='retourQualite' name='retourQualite'>";
	for(i=0;i<Liste_Retour.length;i++){
		if (Liste_Retour[i][2]==document.getElementById('statutQualite').value && Liste_Retour[i][3]==0){
			sel= sel + "<option name='0' value='"+Liste_Retour[i][0]+"'>"+Liste_Retour[i][1]+"</option>";
			isElement = true;
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('retourQ').innerHTML=sel;
}
function AjouterECME(){
	if(document.getElementById('reference').value!=""){
		if(document.getElementById('ECME').value.indexOf(document.getElementById('reference').value+'_')==-1){
			document.getElementById('ECME').value = document.getElementById('ECME').value+document.getElementById('reference').value+';';
			
			var table = document.getElementById("tab_ECME");
			var row = table.insertRow();
			row.id = document.getElementById('reference').value;
			var cell = row.insertCell(0);
			cell.innerHTML = document.getElementById('reference').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECME('"+document.getElementById('reference').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
			document.getElementById('reference').value="";
		}
	}
}
function SupprimerECME(ECME){
	var row = document.getElementById(ECME);
	row.parentNode.removeChild(row);
	document.getElementById('ECME').value = document.getElementById('ECME').value.replace(ECME+";","");
}
function AjouterAIPIS(){
	if(document.getElementById('RefAIPI').value!=""){
		if(document.getElementById('lesAIPI').value.indexOf(document.getElementById('RefAIPI').value)==-1){
			document.getElementById('lesAIPI').value = document.getElementById('lesAIPI').value+document.getElementById('RefAIPI').value+';';
			var AIPI = "";
			for(i=0;i<Liste_AIPI.length;i++){
				if (Liste_AIPI[i][0]==document.getElementById('RefAIPI').value){
					AIPI = Liste_AIPI[i][1];
				}
			}
			var table = document.getElementById("tab_AIPI");
			var row = table.insertRow();
			row.id = document.getElementById('RefAIPI').value;
			var cell = row.insertCell(0);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('"+document.getElementById('RefAIPI').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = "&nbsp;"+AIPI;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
			document.getElementById('RefAIPI').value="";
		}
	}
}
function SupprimerAIPIS(AIPI){
	var row = document.getElementById(AIPI);
	row.parentNode.removeChild(row);
	document.getElementById('lesAIPI').value = document.getElementById('lesAIPI').value.replace(AIPI+";","");
}

function rechercher(){
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("rechercher").dispatchEvent(evt);
}

function GenererFicheSuiveuse(Id){
	question="Voulez-vous générer la fiche suiveuse ?";
	if(window.confirm(question)){
		window.open("FicheSuiveuse.php?Id_Dossier="+Id,"PageFS","status=no,menubar=no,scrollbars=1,width=90,height=40");
	}
}	

function ModifQUALITE(){
	if(document.getElementById('numDossier').value!=""){
		document.getElementById('saisiePROD').style.color="#888888";
		document.getElementById('saisieQUALITE').style.color="#0066CC";
		document.getElementById('typeSaisie').value="QUALITE";
		
		document.getElementById('LibellePieceauposte').style.display="none";
		document.getElementById('pieceauposte').style.display="none";
		document.getElementById('LibelleTravailRealise').style.display="none";
		document.getElementById('travailRealise').style.display="none";
		document.getElementById('tableProd').style.display="none";
		document.getElementById('tableIngredient').style.display="none";
		
		if(formulaire.droit.value.substr(4,1)=="1"){
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
		}
		else{
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
		}
		sel= sel + "<option name=\"\" value=\"\"></option>";
		for(i=0;i<Liste_Statut.length;i++){
			selected="";
			if(document.getElementById('statutQualite').value==Liste_Statut[i][0]){selected="selected";}
			sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" "+selected+" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
		}
		sel =sel + "</select>";
		document.getElementById('statutsQualite').innerHTML=sel;
		
		sel ="<select id='retourQualite' name='retourQualite'>";
		sel= sel + "<option value='0' selected></option>";
		sel =sel + "</select>";
		document.getElementById('retourQ').innerHTML=sel;
		
		AfficherOrigine();
	}
}

function ModifPROD(){
	if(document.getElementById('numDossier').value!=""){
		document.getElementById('saisiePROD').style.color="#0066CC";
		document.getElementById('saisieQUALITE').style.color="#888888";
		document.getElementById('typeSaisie').value="PROD";
		
		document.getElementById('LibellePieceauposte').style.display="";
		document.getElementById('pieceauposte').style.display="";
		document.getElementById('LibelleTravailRealise').style.display="";
		document.getElementById('travailRealise').style.display="";
		document.getElementById('tableProd').style.display="";
		document.getElementById('tableIngredient').style.display="";
		
		if(formulaire.droit.value.substr(4,1)=="1"){
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
		}
		else{
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
		}
		sel= sel + "<option name=\"\" value=\"\"></option>";
		if(document.getElementById('statutProd').value=='TERA' && document.getElementById('statutProd').value=='REWORK'){
			for(i=0;i<Liste_Statut.length;i++){
				sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
			}
		}
		else if(document.getElementById('statutProd').value=='TFS'){
			for(i=0;i<Liste_Statut.length;i++){
				if(Liste_Statut[i][0]=='TVS'){
					sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
				}
			}
		}
		sel =sel + "</select>";
		document.getElementById('statutsQualite').innerHTML=sel;
		
		sel ="<select id='retourQualite' name='retourQualite'>";
		sel= sel + "<option value='0' selected></option>";
		sel =sel + "</select>";
		document.getElementById('retourQ').innerHTML=sel;
		AfficherOrigine();
		AfficherDepose();
	}
}

function DupliqueQUALITE(){
	if(document.getElementById('numDossier').value!=""){
		document.getElementById('saisiePROD').style.color="#888888";
		document.getElementById('saisieQUALITE').style.color="#0066CC";
		document.getElementById('typeSaisie').value="QUALITE";
		
		document.getElementById('LibellePieceauposte').style.display="none";
		document.getElementById('pieceauposte').style.display="none";
		document.getElementById('LibelleTravailRealise').style.display="none";
		document.getElementById('travailRealise').style.display="none";
		document.getElementById('tableProd').style.display="none";
		document.getElementById('tableIngredient').style.display="none";
		
		
		if(formulaire.droit.value.substr(4,1)=="1"){
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
		}
		else{
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
		}
		sel= sel + "<option name=\"\" value=\"\"></option>";
		for(i=0;i<Liste_Statut.length;i++){
			sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
		}
		sel =sel + "</select>";
		document.getElementById('statutsQualite').innerHTML=sel;
		
		sel ="<select id='retourQualite' name='retourQualite'>";
		sel= sel + "<option value='0' selected></option>";
		sel =sel + "</select>";
		document.getElementById('retourQ').innerHTML=sel;
		
		AfficherOrigine();
	}
}

function DupliquePROD(){
	if(document.getElementById('numDossier').value!=""){
		document.getElementById('saisiePROD').style.color="#0066CC";
		document.getElementById('saisieQUALITE').style.color="#888888";
		document.getElementById('typeSaisie').value="PROD";
		
		document.getElementById('LibellePieceauposte').style.display="";
		document.getElementById('pieceauposte').style.display="";
		document.getElementById('LibelleTravailRealise').style.display="";
		document.getElementById('travailRealise').style.display="";
		document.getElementById('tableProd').style.display="";
		document.getElementById('tableIngredient').style.display="";
		
		if(formulaire.droit.value.substr(4,1)=="1"){
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\">";
		}
		else{
			sel ="<select id=\"statutQualite\" name=\"statutQualite\" onchange=\"Recharge_StatutQualite();\" disabled=\"disabled\">";
		}
		sel= sel + "<option name=\"\" value=\"\"></option>";
		if(document.getElementById('statutProd').value=='TERA' && document.getElementById('statutProd').value=='REWORK'){
			for(i=0;i<Liste_Statut.length;i++){
				sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
			}
		}
		else if(document.getElementById('statutProd').value=='TFS'){
			for(i=0;i<Liste_Statut.length;i++){
				if(Liste_Statut[i][0]=='TVS'){
					sel= sel + "<option name=\""+Liste_Statut[i][0]+"\" value=\""+Liste_Statut[i][0]+"\">"+Liste_Statut[i][0]+"</option>";
				}
			}
		}
		sel =sel + "</select>";
		document.getElementById('statutsQualite').innerHTML=sel;
		
		sel ="<select id='retourQualite' name='retourQualite'>";
		sel= sel + "<option value='0' selected></option>";
		sel =sel + "</select>";
		document.getElementById('retourQ').innerHTML=sel;
		AfficherOrigine();
		AfficherDepose();
	}
}