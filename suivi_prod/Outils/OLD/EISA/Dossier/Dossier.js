Liste_CE = new Array();
Liste_Personne = new Array();
Liste_IQ = new Array();
Liste_ECME = new Array();
Liste_AIPI = new Array();
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
function AjouterTE(){
	var elements = document.getElementsByClassName("check");
	var Elements_Compagnons = document.getElementsByClassName("compagnons");
	var checked=true;
	
	for(var k=0, l=Elements_Compagnons.length; k<l; k++)
	{
		if(Elements_Compagnons[k].checked==true)
		{
			if(document.getElementById('lescompagnons').value.indexOf(Elements_Compagnons[k].value+";")==-1){
				document.getElementById('lescompagnons').value = document.getElementById('lescompagnons').value+Elements_Compagnons[k].value+';';
				var table = document.getElementById("tab_TravailEffectue");
				var row = table.insertRow();
				row.id = Elements_Compagnons[k].value;
				btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('"+Elements_Compagnons[k].value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				var Personne = "";
				for(i=0;i<Liste_Personne.length;i++){
					if (Liste_Personne[i][0]==Elements_Compagnons[k].value){
						Personne = Liste_Personne[i][1]+" "+Liste_Personne[i][2];
					}
				}
				var cell = row.insertCell(0);
				cell.innerHTML = Personne;
				var cell = row.insertCell(1);
				cell.innerHTML = btn;
			}
			Elements_Compagnons[k].checked=false;
		}
	}
}
function SupprimerTE(compagnon){
	var row = document.getElementById(compagnon);
	row.parentNode.removeChild(row);
	document.getElementById('lescompagnons').value = document.getElementById('lescompagnons').value.replace(compagnon+";","");
}
function AjouterCE(){
	if(document.getElementById('leader').value!="0"){
		if(document.getElementById('lesces').value.indexOf(document.getElementById('leader').value+";")==-1){
			document.getElementById('lesces').value = document.getElementById('lesces').value+document.getElementById('leader').value+';';
			var table = document.getElementById("tab_CE");
			var row = table.insertRow();
			row.id = document.getElementById('leader').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerCE('"+document.getElementById('leader').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var Personne = "";
			for(i=0;i<Liste_CE.length;i++){
				if (Liste_CE[i][0]==document.getElementById('leader').value){
					Personne = Liste_CE[i][1]+" "+Liste_CE[i][2];
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = Personne;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerCE(leader){
	var row = document.getElementById(leader);
	row.parentNode.removeChild(row);
	document.getElementById('lesces').value = document.getElementById('lesces').value.replace(leader+";","");
}
function AjouterControleur(){
	if(document.getElementById('controleur').value!="0"){
		if(document.getElementById('IQ').value.indexOf(document.getElementById('controleur').value+";")==-1){
			document.getElementById('IQ').value = document.getElementById('IQ').value+document.getElementById('controleur').value+';';
			var table = document.getElementById("tab_IQ");
			var row = table.insertRow();
			row.id = document.getElementById('controleur').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('"+document.getElementById('controleur').value+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var Personne = "";
			for(i=0;i<Liste_IQ.length;i++){
				if (Liste_IQ[i][0]==document.getElementById('controleur').value){
					Personne = Liste_IQ[i][1]+" "+Liste_IQ[i][2];
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = Personne;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerControleur(controleur){
	var row = document.getElementById(controleur);
	row.parentNode.removeChild(row);
	document.getElementById('IQ').value = document.getElementById('IQ').value.replace(controleur+";","");
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

function VerifChamps(){
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
	return true
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