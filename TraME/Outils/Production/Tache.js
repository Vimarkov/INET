Liste_Tache= new Array();
function VerifChamps(langue){
	//Verifier existance du dossier
	bExiste=false;
	for(i=0;i<Liste_Tache.length;i++){
		if (Liste_Tache[i]==formulaire.libelle.value){
			bExiste = true;
		}
	}
	if(langue=="EN"){
		if(formulaire.libelle.value==''){alert('You didn\'t enter the task.');return false;}
		if(bExiste==true){alert('This task already exists.');return false;}
	}
	else{
		if(formulaire.uo.value==''){alert('Vous n\'avez pas renseigné la tâche.');return false;}
		if(bExiste==true){alert('Cette tâche existe déjà.');return false;}
	}
	return true;
}

Liste_WP = new Array();
function AjouterWP(){
	var elements = document.getElementsByClassName("check");
	var Elements_WP = document.getElementsByClassName("wps");
	var checked=true;
	
	for(var k=0, l=Elements_WP.length; k<l; k++)
	{
		if(Elements_WP[k].checked==true)
		{
			if(Elements_WP[k].value!="0"){
				if(document.getElementById('lesWP').value.indexOf(";"+Elements_WP[k].value+"WP")==-1){
					document.getElementById('lesWP').value = document.getElementById('lesWP').value+";"+Elements_WP[k].value+"WP";
					var table = document.getElementById("tab_WP");
					var row = table.insertRow();
					row.id = Elements_WP[k].value;
					btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerWP('"+Elements_WP[k].value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					var WP = "";
					for(i=0;i<Liste_WP.length;i++){
						if (Liste_WP[i][0]==Elements_WP[k].value){
							WP = Liste_WP[i][1]
						}
					}
					var cell = row.insertCell(0);
					cell.innerHTML = WP;
					var cell = row.insertCell(1);
					cell.innerHTML = btn;
				}
			}
		}
	}
}
function SupprimerWP(WP){
	var row = document.getElementById(WP);
	row.parentNode.removeChild(row);
	document.getElementById('lesWP').value = document.getElementById('lesWP').value.replace(";"+WP+"WP","");
}

function AjouterInfo(Langue){
	if(document.getElementById('information').value!="" 
	&& document.getElementById('typeInfo').value!=""
	&& (
			(document.getElementById('typeInfo').value=="Menu deroulant" && document.getElementById('choix1').value!="") 
			|| document.getElementById('typeInfo').value!="Menu deroulant"
		)
	){
		if(document.getElementById('lesInfos').value.indexOf(";"+document.getElementById('information').value+"_")==-1){
			document.getElementById('lesInfos').value = document.getElementById('lesInfos').value+";"+document.getElementById('information').value
														+"_"+document.getElementById('typeInfo').value+"_"
														+document.getElementById('choix1').value+"<>"+document.getElementById('choix2').value+"<>"+document.getElementById('choix3').value
														+"<>"+document.getElementById('choix4').value+"<>"+document.getElementById('choix5').value;
			var table = document.getElementById("tab_Info");
			var row = table.insertRow();
			row.id = document.getElementById('information').value+"_"+document.getElementById('typeInfo').value+"_"
														+document.getElementById('choix1').value+"<>"+document.getElementById('choix2').value+"<>"+document.getElementById('choix3').value
														+"<>"+document.getElementById('choix4').value+"<>"+document.getElementById('choix5').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerInfo('"+document.getElementById('information').value+"_"+document.getElementById('typeInfo').value+"_"
														+document.getElementById('choix1').value+"<>"+document.getElementById('choix2').value+"<>"+document.getElementById('choix3').value
														+"<>"+document.getElementById('choix4').value+"<>"+document.getElementById('choix5').value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(0);
			cell.innerHTML = document.getElementById('information').value;
			var cell = row.insertCell(1);
			type=document.getElementById('typeInfo').value;
			if(Langue=="EN"){
				if(type=="Texte"){type="text";}
				else if(type=="Numerique"){type="Digital";}
				else if(type=="Date"){type="Date";}
				else if(type=="Menu deroulant"){type="Drop-down menu";}
			}
			if(document.getElementById('typeInfo').value=="Menu deroulant"){
				type+=" ["
				if(document.getElementById('choix1').value!=""){
					if(type!="["){type+="|";}
					type+=document.getElementById('choix1').value;
				}
				if(document.getElementById('choix2').value!=""){
					if(type!="["){type+="|";}
					type+=document.getElementById('choix2').value;
				}
				if(document.getElementById('choix3').value!=""){
					if(type!="["){type+="|";}
					type+=document.getElementById('choix3').value;
				}
				if(document.getElementById('choix4').value!=""){
					if(type!="["){type+="|";}
					type+=document.getElementById('choix4').value;
				}
				if(document.getElementById('choix5').value!=""){
					if(type!="["){type+="|";}
					type+=document.getElementById('choix5').value;
				}
				type+="]";
			}
			cell.innerHTML = type;
			var cell = row.insertCell(2);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerInfo(Info){
	var row = document.getElementById(Info);
	row.parentNode.removeChild(row);
	document.getElementById('lesInfos').value = document.getElementById('lesInfos').value.replace(";"+Info,"");
}
Liste_UO = new Array();
Liste_DT = new Array();
function AjouterUO(){
	if(document.getElementById('uo').value!="0" && document.getElementById('dt').value!="0" && document.getElementById('tt').value!="" && document.getElementById('complexite').value!="" && document.getElementById('relation').value!=""){
		if(document.getElementById('lesUO').value.indexOf(";"+document.getElementById('uo').value+"UO_")==-1){
			document.getElementById('lesUO').value = document.getElementById('lesUO').value+";"+document.getElementById('uo').value+"UO_"+document.getElementById('dt').value+"_"+document.getElementById('tt').value+"_"+document.getElementById('complexite').value+"_"+document.getElementById('relation').value;
			var table = document.getElementById("tab_UO");
			var row = table.insertRow();
			row.id = document.getElementById('uo').value+"UO_"+document.getElementById('dt').value+"_"+document.getElementById('tt').value+"_"+document.getElementById('complexite').value+"_"+document.getElementById('relation').value;
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerUO('"+document.getElementById('uo').value+"UO_"+document.getElementById('dt').value+"_"+document.getElementById('tt').value+"_"+document.getElementById('complexite').value+"_"+document.getElementById('relation').value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var UO = "";
			for(i=0;i<Liste_UO.length;i++){
				if (Liste_UO[i][0]==document.getElementById('uo').value){
					UO = Liste_UO[i][1]
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = UO;
			cell.style.borderBottom = "1px dotted #000000";
			var DT = "";
			for(i=0;i<Liste_DT.length;i++){
				if (Liste_DT[i][0]==document.getElementById('dt').value){
					DT = Liste_DT[i][1]
				}
			}
			var cell = row.insertCell(1);
			cell.innerHTML = DT;
			cell.style.borderBottom = "1px dotted #000000";
			var cell = row.insertCell(2);
			cell.innerHTML = document.getElementById('tt').value;
			cell.style.borderBottom = "1px dotted #000000";
			var cell = row.insertCell(3);
			cell.innerHTML = document.getElementById('complexite').value;
			cell.style.borderBottom = "1px dotted #000000";
			var cell = row.insertCell(4);
			cell.innerHTML = document.getElementById('relation').value;
			cell.style.borderBottom = "1px dotted #000000";
			var cell = row.insertCell(5);
			cell.innerHTML = btn;
			cell.style.borderBottom = "1px dotted #000000";
		}
	}
}
function SupprimerUO(uo){
	var row = document.getElementById(uo);
	row.parentNode.removeChild(row);
	document.getElementById('lesUO').value = document.getElementById('lesUO').value.replace(";"+uo,"");
}