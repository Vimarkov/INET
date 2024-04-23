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

function Valider(type){
	var now = new Date();
	var annee = now.getFullYear();
	var mois = now.getMonth() + 1;
	if(mois<10){mois='0'+mois}
	var jour = now.getDate();
	if(jour<10){jour='0'+jour}
	var heure = now.getHours();
	if(heure<10){heure='0'+heure}
	var minute = now.getMinutes();
	if(minute<10){minute='0'+minute}
	var seconde = now.getSeconds();
	var label = "<label id='Enquete'>"+jour+"/"+mois+"/"+annee+"<br>"+heure+":"+minute+"</label>";
	btn="<a style=\"text-decoration:none;\" href=\"javascript:AnnulerDebut('"+type+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
	document.getElementById('Debut'+type).innerHTML=label+btn;
	document.getElementById('dateDebut'+type).value = annee+"-"+mois+"-"+jour;
	document.getElementById('heureDebut'+type).value = heure+":"+minute+":00";
	var checkFin = "<a style=\"text-decoration:none;\" href=\"javascript:Terminer('"+type+"');\"><img id=\"img\" src=\"../../../Images/Avion2.png\"  height=\"35\" alt=\"Terminer\" title=\"Terminer\"></a>";
	document.getElementById('Fin'+type).innerHTML=checkFin;
}
function AnnulerDebut(type){
	btn="<a style=\"text-decoration:none;\" href=\"javascript:Valider('"+type+"')\">&nbsp;<img src=\"../../../Images/Avion1.png\" height=\"35\" alt=\"Démarrer\" title=\"Démarrer\">&nbsp;&nbsp;</a>";
	document.getElementById('Debut'+type).innerHTML=btn;
	document.getElementById('dateDebut'+type).value = "";
	document.getElementById('heureDebut'+type).value = "";
	document.getElementById('dateFin'+type).value = "";
	document.getElementById('heureFin'+type).value = "";
	document.getElementById('Fin'+type).innerHTML="";
}
function AnnulerFin(type){
	btn="<a style=\"text-decoration:none;\" href=\"javascript:Terminer('"+type+"')\">&nbsp;<img src=\"../../../Images/Avion2.png\" height=\"35\" alt=\"Terminer\" title=\"Terminer\">&nbsp;&nbsp;</a>";
	document.getElementById('Fin'+type).innerHTML=btn;
	document.getElementById('dateFin'+type).value = "";
	document.getElementById('heureFin'+type).value = "";
}
function Terminer(type){
	var now = new Date();
	var annee = now.getFullYear();
	var mois = now.getMonth() + 1;
	if(mois<10){mois='0'+mois}
	var jour = now.getDate();
	if(jour<10){jour='0'+jour}
	var heure = now.getHours();
	if(heure<10){heure='0'+heure}
	var minute = now.getMinutes();
	if(minute<10){minute='0'+minute}
	var seconde = now.getSeconds();
	var label = "<label id='Enquete'>"+jour+"/"+mois+"/"+annee+"<br>"+heure+":"+minute+"</label>";
	btn="<a style=\"text-decoration:none;\" href=\"javascript:AnnulerFin('"+type+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
	document.getElementById('Fin'+type).innerHTML=label+btn;
	document.getElementById('dateFin'+type).value = annee+"-"+mois+"-"+jour;
	document.getElementById('heureFin'+type).value = heure+":"+minute+":00";
}
function OuvreDef(){window.open("pdf.php?Doc=PDF/Definition des catégories","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
function AfficherSubmit(){
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Enregistrer').innerHTML=bouton;

	document.getElementById('btnEnregistrer2').click();
	document.getElementById('Enregistrer').innerHTML="";
}


function mouseEvent(type, sx, sy, cx, cy) {
  var evt;
  var e = {
    bubbles: true,
    cancelable: (type != "mousemove"),
    view: window,
    detail: 0,
    screenX: sx, 
    screenY: sy,
    clientX: cx, 
    clientY: cy,
    ctrlKey: false,
    altKey: false,
    shiftKey: false,
    metaKey: false,
    button: 0,
    relatedTarget: undefined
  };
  if (typeof( document.createEvent ) == "function") {
    evt = document.createEvent("MouseEvents");
    evt.initMouseEvent(type, 
      e.bubbles, e.cancelable, e.view, e.detail,
      e.screenX, e.screenY, e.clientX, e.clientY,
      e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
      e.button, document.body.parentNode);
  } else if (document.createEventObject) {
    evt = document.createEventObject();
    for (prop in e) {
    evt[prop] = e[prop];
  }
    evt.button = { 0:1, 1:4, 2:2 }[evt.button] || evt.button;
  }
  return evt;
}
function dispatchEvent (el, evt,type) {
  if (el.dispatchEvent) {
    el.dispatchEvent(evt);
  } else if (el.fireEvent) {
    el.fireEvent('on' + type, evt);
  }
  return evt;
}

function FicheSuiveuse(Id,Id_FI)
	{window.open("FicheSuiveuse.php?Id_Dossier="+Id+"&Id_FI="+Id_FI,"PageFS","status=no,menubar=no,scrollbars=1,width=90,height=40");}	
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
	var elements = document.getElementsByClassName("check");
	var Elements_Compagnons = document.getElementsByClassName("compagnons");
	var checked=true;
	
	for(var k=0, l=Elements_Compagnons.length; k<l; k++)
	{
		if(Elements_Compagnons[k].checked==true)
		{
			if(Elements_Compagnons[k].value!="0" && document.getElementById('tempsPasse').value!=""){
				if(document.getElementById('travailEffectue').value.indexOf(Elements_Compagnons[k].value+"C_")==-1){
					document.getElementById('travailEffectue').value = document.getElementById('travailEffectue').value+Elements_Compagnons[k].value+"C_"+document.getElementById('tempsPasse').value+';';
					var table = document.getElementById("tab_TravailEffectue");
					var row = table.insertRow();
					row.id = Elements_Compagnons[k].value+"C_"+document.getElementById('tempsPasse').value;
					btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('"+Elements_Compagnons[k].value+"C_"+document.getElementById('tempsPasse').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					var Personne = "";
					for(i=0;i<Liste_Personne.length;i++){
						if (Liste_Personne[i][0]==Elements_Compagnons[k].value){
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
	}
}
function SupprimerTE(compagnon){
	var row = document.getElementById(compagnon);
	row.parentNode.removeChild(row);
	document.getElementById('travailEffectue').value = document.getElementById('travailEffectue').value.replace(compagnon+";","");
	document.getElementById('tpsFI').value=parseFloat(document.getElementById('tpsFI').value)-parseFloat(compagnon.substr(compagnon.indexOf("_")+1));
	document.getElementById('tpsDossier').value=parseFloat(document.getElementById('tpsDossier').value)-parseFloat(compagnon.substr(compagnon.indexOf("_")+1));
}

function VerifChamps(droitPrepa){
	if(formulaire.numNC.value=='' && formulaire.numAM.value=='' && formulaire.numDossier.value==''){
		alert('Vous n\'avez pas renseigné le numéro NC ou AM ou dossier.');
		return false;
	}
	if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
	if(formulaire.client.value=='0'){alert('Vous n\'avez pas renseigné le client.');return false;}
	//Verifier existance du dossier
	if(formulaire.numNC.value!=''){
		bExiste=false;
		for(i=0;i<Liste_ReferenceNC.length;i++){
			if (Liste_ReferenceNC[i][0]!=formulaire.idDossier.value && Liste_ReferenceNC[i][1]==formulaire.numNC.value){
				bExiste = true;
			}
		}
		if(bExiste==true){alert('Ce numéro de NC existe déjà.');return false;}
	}
	if(formulaire.numAM.value!=''){
		bExiste=false;
		for(i=0;i<Liste_ReferenceAM.length;i++){
			if (Liste_ReferenceAM[i][0]!=formulaire.idDossier.value && Liste_ReferenceAM[i][1]==formulaire.numAM.value){
				bExiste = true;
			}
		}
		if(bExiste==true){alert('Ce numéro d\'AM existe déjà.');return false;}
	}
	if(formulaire.numDossier.value!=''){
		bExiste=false;
		for(i=0;i<Liste_Reference.length;i++){
			if (Liste_Reference[i][0]!=formulaire.idDossier.value && Liste_Reference[i][1]==formulaire.numDossier.value){
				bExiste = true;
			}
		}
		if(bExiste==true){alert('Ce numéro de dossier existe déjà.');return false;}
	}
	if(formulaire.numPF.value!=''){
		bExiste=false;
		for(i=0;i<Liste_ReferencePF.length;i++){
			if (Liste_ReferencePF[i][0]!=formulaire.idDossier.value && Liste_ReferencePF[i][1]==formulaire.numPF.value && Liste_ReferencePF[i][2]==formulaire.msn.value){
				bExiste = true;
			}
		}
		if(bExiste==true){alert('Ce numéro de point folio existe déjà.');return false;}
	}
	if(formulaire.caec.value==''){alert('Vous n\'avez pas renseigné le CA/EC.');return false;}
	if(formulaire.section.value==''){alert('Vous n\'avez pas renseigné la section.');return false;}
	if(formulaire.titre.value==''){alert('Vous n\'avez pas renseigné le titre.');return false;}
	if(formulaire.zone.value=='0'){alert('Vous n\'avez pas renseigné la zone de travail.');return false;}
	if(formulaire.commentaireZI.value==''){alert('Vous n\'avez pas renseigné la localisation.');return false;}
	if(formulaire.travailRealise.value==''){alert('Vous n\'avez pas renseigné le travail réalisé.');return false;}
	if(droitPrepa==1){
		if(document.getElementById('dateDebutAppro').value != ''){
			if(document.getElementById('dateFinAppro').value != ''){
				if(formulaire.dateAppro.value==''){alert('Vous n\'avez pas renseigné le date de réception prévu.');return false;}
			}
		}
		if(document.getElementById('dateDebutDA').value != ''){
			if(document.getElementById('dateFinDA').value != ''){
				if(formulaire.dateDA.value==''){alert('Vous n\'avez pas renseigné la date prévu de DA.');return false;}
				if(formulaire.numDA.value==''){alert('Vous n\'avez pas renseigné le numéro de DA.');return false;}
			}
		}
		if(document.getElementById('dateDebutDERO').value != ''){
			if(document.getElementById('dateFinDERO').value != ''){
				if(formulaire.numDERO.value==''){alert('Vous n\'avez pas renseigné le numéro de dérogation.');return false;}
			}
		}
	}
	
	if(document.getElementById('statutProd').value=="TERA"){
		if(document.getElementById('Produit').value=="" && document.getElementById('PasDeIngredient').checked==false){
			alert("Veuillez renseigner les produits");
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
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('retourQ').innerHTML=sel;
}

function rechercher(){
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("rechercher").dispatchEvent(evt);
}

function GenererFicheSuiveuse(Id,Id_FI){
	question="Voulez-vous générer la fiche suiveuse ?";
	if(window.confirm(question)){
		window.open("FicheSuiveuse.php?Id_Dossier="+Id+"&Id_FI="+Id_FI,"PageFS","status=no,menubar=no,scrollbars=1,width=90,height=40");
	}
}
function AfficherBesoin(besoin){
	if(besoin=="1"){
		var elements = document.getElementsByClassName('outillage');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	else{
		var elements = document.getElementsByClassName('outillage');
		for (i=0; i<elements.length; i++){
			elements[i].style.display='none';
		}
	}

}
function AfficherMB21(){
	if(document.getElementById("typeDA").value=="MB21"){
		var elements = document.getElementsByClassName('mb21');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	else{
		var elements = document.getElementsByClassName('mb21');
		for (i=0; i<elements.length; i++){
			elements[i].style.display='none';
		}
	}
	
	if(document.getElementById("typeDA").value=="Client"){
		var elements = document.getElementsByClassName('client');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	else{
		var elements = document.getElementsByClassName('client');
		for (i=0; i<elements.length; i++){
			elements[i].style.display='none';
		}
	}
}

function AjouterMB21(){
	if(document.getElementById('numResa').value!="" && document.getElementById('numOT').value!=""){
		if(document.getElementById('mb21').value.indexOf(document.getElementById('numResa').value+"_"+document.getElementById('numOT').value)==-1){
			document.getElementById('mb21').value = document.getElementById('mb21').value+document.getElementById('numResa').value+"_"+document.getElementById('numOT').value+';';
			var table = document.getElementById("tab_MB21");
			var row = table.insertRow();
			row.id = document.getElementById('numResa').value+"_"+document.getElementById('numOT').value;
			var cell = row.insertCell(0);
			cell.innerHTML = "&nbsp;"+document.getElementById('numResa').value;
			var cell = row.insertCell(1);
			cell.innerHTML = document.getElementById('numOT').value;
			var cell = row.insertCell(2);
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMB21('"+document.getElementById('numResa').value+"_"+document.getElementById('numOT').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			cell.innerHTML = btn;
			document.getElementById('numResa').value="";
			document.getElementById('numOT').value="";
		}
	}
}
function SupprimerMB21(MB21){
	var row = document.getElementById(MB21);
	row.parentNode.removeChild(row);
	document.getElementById('mb21').value = document.getElementById('mb21').value.replace(MB21+";","");
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
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('0PS_PS"+document.getElementById('nomPS').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
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