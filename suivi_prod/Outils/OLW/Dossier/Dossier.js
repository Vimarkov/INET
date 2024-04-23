Liste_SousATA = new Array();
Liste_ECME = new Array();
Liste_Module = new Array();
function Recharge_PS(){
	var i;
	var sel="";
	var isElement = false;
	sel ="<select id=\"RefAIPI\" name=\"RefAIPI\" style=\"width:130px;\" onkeypress=\"if(event.keyCode == 13)AjouterAIPIS()\">";
	sel= sel + "<option value=\"0\"></option>";
	for(i=0;i<Liste_AIPI.length;i++){
		if(document.getElementById('module').value=="0"){
			sel= sel + "<option value=\""+Liste_AIPI[i][0]+"\">"+Liste_AIPI[i][1]+"</option>";
		}
		else{
			for(j=0;j<Liste_Module.length;j++){
				if(Liste_Module[j][0]==document.getElementById('module').value){
					if(Liste_Module[j][1]==Liste_AIPI[i][0]){
						sel= sel + "<option value=\""+Liste_AIPI[i][0]+"\">"+Liste_AIPI[i][1]+"</option>";
					}
				}
			}
		}
	}
	sel =sel + "</select>";
	document.getElementById('listeRefAIPI').innerHTML=sel;
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
function AfficherSN(){
	//SABCA = 9
	if(document.getElementById('client').value=="9"){
		document.getElementById('SNLibelle').style.display='';
		document.getElementById('SNInput').style.display='';
	}
	else{
		document.getElementById('numSN').value="";
		document.getElementById('SNLibelle').style.display='none';
		document.getElementById('SNInput').style.display='none';
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
	if(document.getElementById('compagnon').value!="0" && document.getElementById('tempsPasse').value!="" && document.getElementById('tempsTravail').value!=""){
		if(document.getElementById('travailEffectue').value.indexOf(document.getElementById('compagnon').value+"C_")==-1){
			document.getElementById('travailEffectue').value = document.getElementById('travailEffectue').value+document.getElementById('compagnon').value+"C_"+document.getElementById('tempsPasse').value+"C_"+document.getElementById('tempsTravail').value+';';
			var table = document.getElementById("tab_TravailEffectue");
			var row = table.insertRow();
			row.id = document.getElementById('compagnon').value+"C_"+document.getElementById('tempsPasse').value+"C_"+document.getElementById('tempsTravail').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('"+document.getElementById('compagnon').value+"C_"+document.getElementById('tempsPasse').value+"C_"+document.getElementById('tempsTravail').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
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
			cell.innerHTML = document.getElementById('tempsTravail').value;
			var cell = row.insertCell(3);
			cell.innerHTML = btn;
			
			//Ajout du temps dans le champs tps FI
			document.getElementById('tpsFI').value=parseFloat(document.getElementById('tpsFI').value)+parseFloat(document.getElementById('tempsPasse').value);
			document.getElementById('tpsTravailFI').value=parseFloat(document.getElementById('tpsTravailFI').value)+parseFloat(document.getElementById('tempsTravail').value);
			document.getElementById('tpsDossier').value=parseFloat(document.getElementById('tpsDossier').value)+parseFloat(document.getElementById('tempsPasse').value);
		}
	}
}
function SupprimerTE(compagnon){
	var row = document.getElementById(compagnon);
	row.parentNode.removeChild(row);
	document.getElementById('travailEffectue').value = document.getElementById('travailEffectue').value.replace(compagnon+";","");
	tempsTravail=compagnon.substr(compagnon.indexOf("_")+1);
	document.getElementById('tpsFI').value=parseFloat(document.getElementById('tpsFI').value)-parseFloat(tempsTravail.substr(0,tempsTravail.indexOf("_")));
	document.getElementById('tpsTravailFI').value=parseFloat(document.getElementById('tpsTravailFI').value)-parseFloat(tempsTravail.substr(tempsTravail.indexOf("_")+1));
	document.getElementById('tpsDossier').value=parseFloat(document.getElementById('tpsDossier').value)-parseFloat(tempsTravail.substr(tempsTravail.indexOf("_")-2));
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
	if(formulaire.zone.value==''){alert('Vous n\'avez pas renseigné la zone.');return false;}
	if(formulaire.titre.value==''){alert('Vous n\'avez pas renseigné le titre.');return false;}
	if(formulaire.tai.value==''){formulaire.tai.value=0;}
	if(formulaire.ligneContrat.value=='0'){alert('Vous n\'avez pas renseigné la ligne contrat.');return false;}
	//if(formulaire.zone.value=='0'){alert('Vous n\'avez pas renseigné la zone de travail.');return false;}
	/*if(formulaire.Mastic.checked==false && formulaire.Peinture.checked==false && formulaire.Metal.checked==false && formulaire.Structure.checked==false && formulaire.Systeme.checked==false){
		alert('Vous n\'avez pas renseigné les compétences.');
		return false;
	}
	if(formulaire.ata_sousata.value==''){alert('Vous n\'avez pas renseigné les ATA/Sous-ATA.');return false;}*/
	if(formulaire.travailRealise.value==''){alert('Vous n\'avez pas renseigné le travail réalisé.');return false;}
	if(formulaire.statutProd.value!=''){
		if(formulaire.vacation.value==''){alert('Vous n\'avez pas renseigné la vacation.');return false;}
		if(formulaire.dateIntervention.value==''){alert('Vous n\'avez pas renseigné la date d\'intervention.');return false;}
	}
	if(formulaire.statutQualite.value!=''){
		if(formulaire.vacationQ.value==''){alert('Vous n\'avez pas renseigné la vacation qualité.');return false;}
		if(formulaire.dateInterventionQ.value==''){alert('Vous n\'avez pas renseigné la date d\'intervention qualité.');return false;}
	}
	if(document.getElementById('statutProd').value=="TERA"){
		if(document.getElementById('travailEffectue').value==""){
			alert('Vous n\'avez pas renseigné le temps passé en PROD.');return false;
		}
	}
	if(document.getElementById('statutQualite').value=="TERC"){
		if(document.getElementById('tempsControle').value==""){
			alert('Vous n\'avez pas renseigné le temps de contrôle.');return false;
		}
	}
	if(document.getElementById('statutProd').value=="TERA"){
		if(document.getElementById('Produit').value=="" && document.getElementById('PasDeIngredient').checked==false){
			alert("Veuillez renseigner les procédés spéciaux");
			return false
		}
		if(document.getElementById('lesAIPI').value=="" && document.getElementById('PasDePS').checked==false){
			alert("Veuillez renseigner les procédés spéciaux");
			return false
		}
		if(document.getElementById('statutProd').value=="TERA" && document.getElementById('ValidationPSCE').checked==false){
			alert("Les procédés spéciaux doivent être validés par le chef d'équipe pour pouvoir mettre le statut TERA");
			return false
		}
		if(document.getElementById('ECMEPROD').value=='' && document.getElementById('PasDeECMEPROD').checked==false){
			alert("Veuillez renseigner les ECME PROD pour pouvoir mettre le statut TERA");
			return false
		}
	}
	if(document.getElementById('statutQualite').value=="TERC"){
		if(document.getElementById('ValidationPSIQ').checked==false){
			alert("Les procédés spéciaux doivent être validés par la qualité pour pouvoir mettre le statut TERC");
			return false
		}
		if(document.getElementById('ECMEQUALITE').value=='' && document.getElementById('PasDeECMEQUALITE').checked==false){
			alert("Veuillez renseigner les ECME QUALITE pour pouvoir mettre le statut TERC");
			return false
		}
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
	if(isElement == false || document.getElementById('statutQualite').value=="TERC"){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('retourQ').innerHTML=sel;
}
function AjouterECMEPROD(){
	if(document.getElementById('referencePROD').value!="0" && document.getElementById('referencePROD').value!=""){
		if(document.getElementById('ECMEPROD').value.indexOf(document.getElementById('referencePROD').value+'_'+document.getElementById('typeECMEPROD').value+'ECME_ECME;')==-1){
			document.getElementById('ECMEPROD').value = document.getElementById('ECMEPROD').value+document.getElementById('referencePROD').value+'_'+document.getElementById('typeECMEPROD').value+'ECME_ECME'+';';
			var table = document.getElementById("tab_ECMEPROD");
			var row = table.insertRow();
			row.id = "ECMEPROD"+document.getElementById('referencePROD').value+'_'+document.getElementById('typeECMEPROD').value+'ECME_ECME';
			var cell = row.insertCell(0);
			var Type = "";
			for(i=0;i<Liste_ECME.length;i++){
				if (Liste_ECME[i][0]==document.getElementById('typeECMEPROD').value){
					Type = Liste_ECME[i][1];
				}
			}
			var ECME = "";
			for(i=0;i<Liste_RefECMEPROD.length;i++){
				if (Liste_RefECMEPROD[i][0]==document.getElementById('referencePROD').value){
					ECME = Liste_RefECMEPROD[i][2];
				}
			}
			cell.innerHTML = Type;
			var cell = row.insertCell(1);
			cell.innerHTML = ECME;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEPROD('"+document.getElementById('referencePROD').value+"_"+document.getElementById('typeECMEPROD').value+'ECME_ECME'+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
			document.getElementById('referencePROD').value="";
			document.getElementById('typeECMEPROD').value="";
			document.getElementById('PasDeECMEPROD').checked=false;
		}
	}
	else if(document.getElementById('nomECMEPROD').value!="" && document.getElementById('typeECMEPROD').value!='' && document.getElementById('typeECMEPROD').value!='0'){
		if(document.getElementById('ECMEPROD').value.indexOf(document.getElementById('nomECMEPROD').value)==-1){
			document.getElementById('ECMEPROD').value = document.getElementById('ECMEPROD').value+'0ECME_ECME'+document.getElementById('nomECMEPROD').value+'_'+document.getElementById('typeECMEPROD').value+';';
			var table = document.getElementById("tab_ECMEPROD");
			var row = table.insertRow();
			row.id = "ECMEPROD"+'0ECME_ECME'+document.getElementById('nomECMEPROD').value+'_'+document.getElementById('typeECMEPROD').value;
			var cell = row.insertCell(0);
			var Type = "";
			for(i=0;i<Liste_ECME.length;i++){
				if (Liste_ECME[i][0]==document.getElementById('typeECMEPROD').value){
					Type = Liste_ECME[i][1];
				}
			}
			cell.innerHTML = Type;
			var cell = row.insertCell(1);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEPROD('0"+"ECME_ECME"+document.getElementById('nomECMEPROD').value+'_'+document.getElementById('typeECMEPROD').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = "&nbsp;"+document.getElementById('nomECMEPROD').value;
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
			document.getElementById('nomECMEPROD').value="";
			document.getElementById('PasDeECMEPROD').checked=false;
		}
	}
}
function AjouterECMEQUALITE(){
	if(document.getElementById('referenceQUALITE').value!="0" && document.getElementById('referenceQUALITE').value!=""){
		if(document.getElementById('ECMEQUALITE').value.indexOf(document.getElementById('referenceQUALITE').value+'_'+document.getElementById('typeECMEQUALITE').value+'ECME_ECME;')==-1){
			document.getElementById('ECMEQUALITE').value = document.getElementById('ECMEQUALITE').value+document.getElementById('referenceQUALITE').value+'_'+document.getElementById('typeECMEQUALITE').value+'ECME_ECME'+';';
			var table = document.getElementById("tab_ECMEQUALITE");
			var row = table.insertRow();
			row.id = "ECMEQUALITE"+document.getElementById('referenceQUALITE').value+'_'+document.getElementById('typeECMEQUALITE').value+'ECME_ECME';
			var cell = row.insertCell(0);
			var Type = "";
			for(i=0;i<Liste_ECME.length;i++){
				if (Liste_ECME[i][0]==document.getElementById('typeECMEQUALITE').value){
					Type = Liste_ECME[i][1];
				}
			}
			var ECME = "";
			for(i=0;i<Liste_RefECMEQUALITE.length;i++){
				if (Liste_RefECMEQUALITE[i][0]==document.getElementById('referenceQUALITE').value){
					ECME = Liste_RefECMEQUALITE[i][2];
				}
			}
			cell.innerHTML = Type;
			var cell = row.insertCell(1);
			cell.innerHTML = ECME;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEQUALITE('"+document.getElementById('referenceQUALITE').value+"_"+document.getElementById('typeECMEQUALITE').value+'ECME_ECME'+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
			document.getElementById('referenceQUALITE').value="";
			document.getElementById('typeECMEQUALITE').value="";
			document.getElementById('PasDeECMEQUALITE').checked=false;
		}
	}
	else if(document.getElementById('nomECMEQUALITE').value!="" && document.getElementById('typeECMEQUALITE').value!='' && document.getElementById('typeECMEQUALITE').value!='0'){
		if(document.getElementById('ECMEQUALITE').value.indexOf(document.getElementById('nomECMEQUALITE').value)==-1){
			document.getElementById('ECMEQUALITE').value = document.getElementById('ECMEQUALITE').value+'0ECME_ECME'+document.getElementById('nomECMEQUALITE').value+'_'+document.getElementById('typeECMEQUALITE').value+';';
			var table = document.getElementById("tab_ECMEQUALITE");
			var row = table.insertRow();
			row.id = "ECMEQUALITE"+'0ECME_ECME'+document.getElementById('nomECMEQUALITE').value+'_'+document.getElementById('typeECMEQUALITE').value;
			var cell = row.insertCell(0);
			var Type = "";
			for(i=0;i<Liste_ECME.length;i++){
				if (Liste_ECME[i][0]==document.getElementById('typeECMEQUALITE').value){
					Type = Liste_ECME[i][1];
				}
			}
			cell.innerHTML = Type;
			var cell = row.insertCell(1);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEQUALITE('0"+"ECME_ECME"+document.getElementById('nomECMEQUALITE').value+'_'+document.getElementById('typeECMEQUALITE').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = "&nbsp;"+document.getElementById('nomECMEQUALITE').value;
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
			document.getElementById('nomECMEQUALITE').value="";
			document.getElementById('PasDeECMEQUALITE').checked=false;
		}
	}
}
function SupprimerECMEPROD(ECME){
	var row = document.getElementById("ECMEPROD"+ECME);
	row.parentNode.removeChild(row);
	document.getElementById('ECMEPROD').value = document.getElementById('ECMEPROD').value.replace(ECME+";","");
}


function SupprimerECMEQUALITE(ECME){
	var row = document.getElementById("ECMEQUALITE"+ECME);
	row.parentNode.removeChild(row);
	document.getElementById('ECMEQUALITE').value = document.getElementById('ECMEQUALITE').value.replace(ECME+";","");
}

Liste_RefECMEPROD = new Array();
Liste_RefECMEQUALITE = new Array();
function Recharge_RefECMEPROD(){
	var i;
	var sel="";
	var isElement = false;
	sel ="<select id=\"referencePROD\" name=\"referencePROD\" style=\"width:100px;\" onkeypress=\"if(event.keyCode == 13)AjouterECMEPROD()\">";
	sel= sel + "<option value=\"0\"></option>";
	for(i=0;i<Liste_RefECMEPROD.length;i++){
		if(Liste_RefECMEPROD[i][1]==document.getElementById('typeECMEPROD').value){
			sel= sel + "<option value=\""+Liste_RefECMEPROD[i][0]+"\">"+Liste_RefECMEPROD[i][2]+"</option>";
		}
	}
	sel =sel + "</select>";
	document.getElementById('listeECMEPROD').innerHTML=sel;
}
function Recharge_RefECMEQUALITE(){
	var i;
	var sel="";
	var isElement = false;
	sel ="<select id=\"referenceQUALITE\" name=\"referenceQUALITE\" style=\"width:100px;\" onkeypress=\"if(event.keyCode == 13)AjouterECMEQUALITE()\">";
	sel= sel + "<option value=\"0\"></option>";
	for(i=0;i<Liste_RefECMEQUALITE.length;i++){
		if(Liste_RefECMEQUALITE[i][1]==document.getElementById('typeECMEQUALITE').value){
			sel= sel + "<option value=\""+Liste_RefECMEQUALITE[i][0]+"\">"+Liste_RefECMEQUALITE[i][2]+"</option>";
		}
	}
	sel =sel + "</select>";
	document.getElementById('listeECMEQUALITE').innerHTML=sel;
}

Liste_Produit = new Array();
function AjouterProduit(){
	 if(document.getElementById('RefProduit').value!="0" && document.getElementById('RefProduit').value!="" && document.getElementById('numLot').value!="" && document.getElementById('datePeremption').value!="" && document.getElementById('coeffH').value!="" && document.getElementById('temperature').value!=""){
		if(document.getElementById('Produit').value.indexOf(document.getElementById('RefProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value+"ING_ING")==-1){
			document.getElementById('Produit').value = document.getElementById('Produit').value+document.getElementById('RefProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value+"ING_ING"+';';
			var table = document.getElementById("tab_Produit");
			var row = table.insertRow();
			row.id = document.getElementById('RefProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value+"ING_ING";
			var Produit = "";
			for(i=0;i<Liste_Produit.length;i++){
				if (Liste_Produit[i][0]==document.getElementById('RefProduit').value){
					Produit = Liste_Produit[i][1];
				}
			}
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerProduit('"+document.getElementById('RefProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value+"ING_ING"+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(0);
			cell.innerHTML = Produit;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('numLot').value;
			var cell = row.insertCell(2);
			cell.innerHTML = document.getElementById('datePeremption').value;
			var cell = row.insertCell(3);
			cell.innerHTML = document.getElementById('coeffH').value;
			var cell = row.insertCell(4);
			cell.innerHTML = document.getElementById('temperature').value;
			var cell = row.insertCell(5);
			cell.innerHTML = btn;
			
			document.getElementById('PasDeIngredient').checked=false;
		}
	}
	else if(document.getElementById('nomProduit').value!="" && document.getElementById('numLot').value!="" && document.getElementById('datePeremption').value!="" && document.getElementById('coeffH').value!="" && document.getElementById('temperature').value!=""){
		if(document.getElementById('Produit').value.indexOf("0ING_ING"+document.getElementById('nomProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value)==-1){
			document.getElementById('Produit').value = document.getElementById('Produit').value+"0ING_ING"+document.getElementById('nomProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value+';';
			var table = document.getElementById("tab_Produit");
			var row = table.insertRow();
			row.id = "0ING_ING"+document.getElementById('nomProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerProduit('"+"0ING_ING"+document.getElementById('nomProduit').value+"_"+document.getElementById('numLot').value+"_"+document.getElementById('datePeremption').value+"_"+document.getElementById('coeffH').value+"_"+document.getElementById('temperature').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(0);
			cell.innerHTML = document.getElementById('nomProduit').value;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('numLot').value;
			var cell = row.insertCell(2);
			cell.innerHTML = document.getElementById('datePeremption').value;
			var cell = row.insertCell(3);
			cell.innerHTML = document.getElementById('coeffH').value;
			var cell = row.insertCell(4);
			cell.innerHTML = document.getElementById('temperature').value;
			var cell = row.insertCell(5);
			cell.innerHTML = btn;
			
			document.getElementById('PasDeIngredient').checked=false;
		}
	}
}
function SupprimerProduit(Produit){
	var row = document.getElementById(Produit);
	row.parentNode.removeChild(row);
	document.getElementById('Produit').value = document.getElementById('Produit').value.replace(Produit+';',"");
}


function AjouterAIPIS(){
	if(document.getElementById('RefAIPI').value!="0" && document.getElementById('RefAIPI').value!=""){
		if(document.getElementById('lesAIPI').value.indexOf(document.getElementById('RefAIPI').value)==-1){
			document.getElementById('lesAIPI').value = document.getElementById('lesAIPI').value+document.getElementById('RefAIPI').value+'PS_PS;';
			var AIPI = "";
			for(i=0;i<Liste_AIPI.length;i++){
				if (Liste_AIPI[i][0]==document.getElementById('RefAIPI').value){
					AIPI = Liste_AIPI[i][1];
				}
			}
			var table = document.getElementById("tab_AIPI");
			var row = table.insertRow();
			row.id = document.getElementById('RefAIPI').value+'PS_PS';
			var cell = row.insertCell(0);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('"+document.getElementById('RefAIPI').value+"PS_PS"+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = "&nbsp;"+AIPI;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
			document.getElementById('RefAIPI').value="";
			document.getElementById('PasDePS').checked=false;
			if(document.getElementById('droit').value.substr(1,1)=="1"){
				document.getElementById('ValidationPSCE').checked=true;
			}
			else{
				document.getElementById('ValidationPSCE').checked=false;
			}
			if(document.getElementById('droit').value.substr(4,1)=="1"){
				document.getElementById('ValidationPSIQ').checked=true;
			}
			else{
				document.getElementById('ValidationPSIQ').checked=false;
			}
		}
	}
	else if(document.getElementById('nomPS').value!=""){
		if(document.getElementById('lesAIPI').value.indexOf(document.getElementById('nomPS').value)==-1){
			document.getElementById('lesAIPI').value = document.getElementById('lesAIPI').value+'0PS_PS'+document.getElementById('nomPS').value+';';
			var table = document.getElementById("tab_AIPI");
			var row = table.insertRow();
			row.id = '0PS_PS'+document.getElementById('nomPS').value;
			var cell = row.insertCell(0);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('0"+"PS_PS"+document.getElementById('nomPS').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = "&nbsp;"+document.getElementById('nomPS').value;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
			document.getElementById('nomPS').value="";
			document.getElementById('PasDePS').checked=false;
			
			if(document.getElementById('droit').value.substr(1,1)=="1"){
				document.getElementById('ValidationPSCE').checked=true;
			}
			else{
				document.getElementById('ValidationPSCE').checked=false;
			}
			
			if(document.getElementById('droit').value.substr(4,1)=="1"){
				document.getElementById('ValidationPSIQ').checked=true;
			}
			else{
				document.getElementById('ValidationPSIQ').checked=false;
			}
		}
	}
}
function ValidationAutoPS(){
	if(document.getElementById('PasDePS').checked==true){
		if(document.getElementById('droit').value.substr(1,1)=="1"){
			document.getElementById('ValidationPSCE').checked=true;
		}
		else{
			document.getElementById('ValidationPSCE').checked=false;
		}
		
		if(document.getElementById('droit').value.substr(4,1)=="1"){
			document.getElementById('ValidationPSIQ').checked=true;
		}
		else{
			document.getElementById('ValidationPSIQ').checked=false;
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

function AjouterECMECLIENT(){
	if(document.getElementById('numClient').value!="" && document.getElementById('dateEtalonnageECMEClient').value!=""){
		if(document.getElementById('ECMECLIENT').value.indexOf(document.getElementById('numClient').value+"_"+document.getElementById('dateEtalonnageECMEClient').value)==-1){
			document.getElementById('ECMECLIENT').value = document.getElementById('ECMECLIENT').value+document.getElementById('numClient').value+"_"+document.getElementById('dateEtalonnageECMEClient').value+';';
			var table = document.getElementById("tab_ECMECLIENT");
			var row = table.insertRow();
			row.id = "ECMECLIENT"+document.getElementById('numClient').value+"_"+document.getElementById('dateEtalonnageECMEClient').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMECLIENT('"+document.getElementById('numClient').value+"_"+document.getElementById('dateEtalonnageECMEClient').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(0);
			cell.innerHTML = document.getElementById('numClient').value;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('dateEtalonnageECMEClient').value;
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerECMECLIENT(Ecme){
	var row = document.getElementById('ECMECLIENT'+Ecme);
	row.parentNode.removeChild(row);
	document.getElementById('ECMECLIENT').value = document.getElementById('ECMECLIENT').value.replace(Ecme+';',"");
}