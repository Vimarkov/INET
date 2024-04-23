Liste_Tache_WP = new Array();
Liste_Tache_Info = new Array();
Liste_Tache_uo = new Array();
function RechargerTache(Langue){
	var i;
	var sel="";
	var isElement = false;
	var bValide = true;
	sel ="<select id='tache' name='tache' onchange=\"RechargerInfos('"+Langue+"')\">";
	for(i=0;i<Liste_Tache_WP.length;i++){
		if (Liste_Tache_WP[i][1]==document.getElementById('wp').value && Liste_Tache_WP[i][2]=="0"){
			bValide=true;
			if(document.getElementById('famille').value!="0"){
				if (Liste_Tache_WP[i][4]!=document.getElementById('famille').value){bValide=false;}
			}
			if(bValide==true){
				sel= sel + "<option value='"+Liste_Tache_WP[i][0];
				sel= sel + "'>"+Liste_Tache_WP[i][3]+"</option>";
				isElement = true;
			}
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('divTache').innerHTML=sel;
	RechargerInfos(Langue);
}
function RechargerInfos(Langue){
	//INFOS COMPLEMENTAIRES
	var i;
	var sel="<table>";
	var temps;

	for(i=0;i<Liste_Tache_Info.length;i++){
		if (Liste_Tache_Info[i][1]==document.getElementById('tache').value){
			sel= sel + "<tr><td style='font-weight:bold;'>"+Liste_Tache_Info[i][2]+"</td>";
			if(Liste_Tache_Info[i][3]=="Numerique"){
				sel= sel + "<td><input onKeyUp='nombre(this)' class='InfoComplementaire' type='text' size='8' id='Info_"+Liste_Tache_Info[i][0]+"' name='Info_"+Liste_Tache_Info[i][0]+"' value='' /></td></tr>";
			}
			else if(Liste_Tache_Info[i][3]=="Texte"){
				sel= sel + "<td><input type='text' class='InfoComplementaire' id='Info_"+Liste_Tache_Info[i][0]+"' size='10' name='Info_"+Liste_Tache_Info[i][0]+"' value='' /></td></tr>";
			}
			else if(Liste_Tache_Info[i][3]=="Date"){
				sel= sel + "<td><input type='date' class='InfoComplementaire' onmousedown='datepick();' size='10' id='Info_"+Liste_Tache_Info[i][0]+"' name='Info_"+Liste_Tache_Info[i][0]+"' value='' /></td></tr>";
			}
		}
	}
	sel= sel + "</table>";
	document.getElementById('divInfos').innerHTML=sel;
	//UO MANDATORY
	sel="<table width='100%' cellpadding='0' cellspacing='0'>"
	if(Langue=="EN"){
		sel= sel + "<tr><td style='font-weight:bold;'>Work unit mandatory</td></tr>";
	}
	else{
		sel= sel + "<tr><td style='font-weight:bold;'>Unit&#233; d'oeuvre mandatory</td></tr>";
	}
	document.getElementById('tempsAlloue').value=0;
	for(i=0;i<Liste_Tache_uo.length;i++){
		if (Liste_Tache_uo[i][0]==document.getElementById('tache').value && Liste_Tache_uo[i][3]=="Mandatory"){
			sel= sel + "<tr><td>" + Liste_Tache_uo[i][2] + "</td></tr>";
			if(Liste_Tache_uo[i][4]!=""){
				document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)+(Math.round((parseFloat(Liste_Tache_uo[i][4])) * 100) / 100);
			}
		}
	}
	sel= sel + "</table>";
	document.getElementById('tempsAlloue').value=Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100;
	document.getElementById('divMandatory').innerHTML=sel;
	
	//UO OPTIONAL
	sel="<table width='100%' cellpadding='0' cellspacing='0'>"
	if(Langue=="EN"){
		sel= sel + "<tr><td style='font-weight:bold;' colspan='2'>Work unit optional</td></tr>";
		sel= sel + "<tr><td bgcolor='#25981e' color=#ffffff; style='font-weight:bold;'>Yes/No</td>";
		sel= sel + "<td bgcolor='#25981e' color=#ffffff; style='font-weight:bold;'>Work unit done</td></tr>";
	}
	else{
		sel= sel + "<tr><td style='font-weight:bold;' colspan='2'>Unit&#233; d'oeuvre optional</td></tr>";
		sel= sel + "<tr><td bgcolor='#25981e' color=#ffffff; style='font-weight:bold;'>Oui/Non</td>";
		sel= sel + "<td bgcolor='#25981e' color=#ffffff; style='font-weight:bold;'>Unit&#233; d'oeuvre r&#233;alis&#233;e</td></tr>";
	}
	
	for(i=0;i<Liste_Tache_uo.length;i++){
		if (Liste_Tache_uo[i][0]==document.getElementById('tache').value && Liste_Tache_uo[i][3]=="Optional"){
			sel= sel + "<tr><td><input type='checkbox' onchange='TempsAlloue("+Liste_Tache_uo[i][5]+")' id='"+Liste_Tache_uo[i][5]+"' name='"+Liste_Tache_uo[i][5]+"' /></td><td>" + Liste_Tache_uo[i][2] + "</td></tr>";
		}
	}
	sel= sel + "</table>";
	document.getElementById('divOptional').innerHTML=sel;
}
function TempsAlloue(Id){
	for(i=0;i<Liste_Tache_uo.length;i++){
		if (Liste_Tache_uo[i][5]==Id){
			if(Liste_Tache_uo[i][4]!=""){
				if(document.getElementById(Id).checked==true){
					document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)+(Math.round((parseFloat(Liste_Tache_uo[i][4])) * 100) / 100);
				}
				else{
					document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)-(Math.round((parseFloat(Liste_Tache_uo[i][4])) * 100) / 100);
				}
			}
		}
	}
	document.getElementById('tempsAlloue').value=Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100;
}
function TempsAlloue2(Id,Temps){
	if(document.getElementById(Id).checked==true){
		document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)+(Math.round((parseFloat(Temps)) * 100) / 100);
	}
	else{
		document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)-(Math.round((parseFloat(Temps)) * 100) / 100);
	}
	document.getElementById('tempsAlloue').value=Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100;
}
function VerifChamps(langue){
	var strs = formulaire.reference.value.split("\n");
	var bRempli = true;
	var list = document.getElementsByClassName('InfoComplementaire');
	var i;
	for (i = 0; i < list.length; i++) {
	  if(list[i].value==""){
		bRempli=false;
	  }
	}
	if(langue=="EN"){
		if(formulaire.tache.value=='0'){alert('You didn\'t enter the task.');return false;}
		if(formulaire.reference.value==''){alert('You didn\'t enter the reference.');return false;}
		if(strs.length>=200){alert('You must list less than 200 references.');return false;}
		if(formulaire.statutTravail.value==''){alert('You didn\'t enter the work status.');return false;}
		if(formulaire.dateTravail.value==''){alert('You didn\'t enter the date of work.');return false;}
		if(bRempli==false){alert('You didn\'t enter the further information.');return false;}
	}
	else{
		if(formulaire.tache.value=='0'){alert('Vous n\'avez pas renseigné la tâche.');return false;}
		if(formulaire.reference.value==''){alert('Vous n\'avez pas renseigné la référence.');return false;}
		if(strs.length>=200){alert('Vous devez renseigner moins 200 references.');return false;}
		if(formulaire.statutTravail.value==''){alert('Vous n\'avez pas renseigné le statut du travail.');return false;}
		if(formulaire.dateTravail.value==''){alert('Vous n\'avez pas renseigné la date du travail.');return false;}
		if(bRempli==false){alert('Vous n\'avez pas renseigné les informations complémentaires.');return false;}
	}
	return true;
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

function VerifValidite(Langue){
	if(formulaire.Mode.value="A"){
		if(formulaire.Droit.value.substr(1,1)=="1"){
			if(formulaire.DateFacturation.value!=""){
				str=Date.parse(formulaire.dateTravail.value).toString("yyyy-MM-dd");
				if(str<=formulaire.DateFacturation.value){
					if(window.confirm('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+'). Êtes-vous sûre de vouloir garder cette date ?')==false){
						formulaire.dateTravail.value=formulaire.OldDateTravail.value;
						
					}
				}
			}
		}
		else if(formulaire.Droit.value.substr(0,1)=="1"){
		
		}
	}
	else{
	
	}
}