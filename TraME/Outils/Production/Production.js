Liste_Tache_WP = new Array();
Liste_Tache_Info = new Array();
Liste_Tache_uo = new Array();
Liste_Tache = new Array();
ListePlanning = new Array();
function RechargerTache(Langue){
	var i;
	var sel="";
	var isElement = false;
	var bValide = true;
	
	sel ="<select id='tache' name='tache' onchange=\"RechargerInfos('"+Langue+"')\">";
	for(i=0;i<Liste_Tache_WP.length;i++){
		if (Liste_Tache_WP[i][1]==document.getElementById('wp').value && Liste_Tache_WP[i][2]=="0" && Liste_Tache_WP[i][7]=="0"){
			bValide=true;
			if(document.getElementById('famille').value!="0"){
				if (Liste_Tache_WP[i][4]!=document.getElementById('famille').value){bValide=false;}
			}
			if(bValide==true){
				sel= sel + "<option value='"+Liste_Tache_WP[i][0]+"'>"+Liste_Tache_WP[i][3]+"</option>";
				isElement = true;
			}
		}
	}
	if(isElement == false){sel= sel + "<option value='0' selected></option>";}
	sel =sel + "</select>";
	document.getElementById('divTache').innerHTML=sel;
	RechargerInfos(Langue);
	AfficherTDControle();
}
function RechargerInfos(Langue){
	document.getElementById('ModificationTache').value = "1";
	//INFOS COMPLEMENTAIRES
	var i;
	var sel="<table>";
	var temps;
	var critereOTD="";
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
			else if(Liste_Tache_Info[i][3]=="Oui/Non"){
				sel= sel + "<td><input type='checkbox' class='InfoComplementaire' size='10' id='Info_"+Liste_Tache_Info[i][0]+"' name='Info_"+Liste_Tache_Info[i][0]+"' /></td></tr>";
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
			sel= sel + "<tr><td><input type='checkbox' onchange='TempsAlloue("+Liste_Tache_uo[i][5]+")' id='"+Liste_Tache_uo[i][5]+"' name='"+Liste_Tache_uo[i][5]+"' /></td><td>" + Liste_Tache_uo[i][2] + "("+ Liste_Tache_uo[i][6] + "|" + Liste_Tache_uo[i][7] +")" + "</td></tr>";
		}
	}
	sel= sel + "</table>";
	document.getElementById('divOptional').innerHTML=sel;
	//Statut du délais
	sel ="<select id='statutDelais' name='statutDelais'>";
	for(i=0;i<Liste_Tache_WP.length;i++){
		if (Liste_Tache_WP[i][0]==document.getElementById('tache').value){
			critereOTD=Liste_Tache_WP[i][6];
			if (Liste_Tache_WP[i][5]==0){
				sel= sel + "<option value='N/A'>N/A</option>";
				break;
			}
			else{
				sel= sel + "<option value='OK'>OK</option>";
				sel= sel + "<option value='KO'>KO</option>";
				break;
			}
		}
	}
	sel= sel + "</select>";
	document.getElementById('leDelais').innerHTML=sel;
	if(Langue=="EN"){
		document.getElementById('hoverCritereOTD').innerHTML="\n<span> Criteria OTD : "+critereOTD+"</span>Deadline\n";
	}
	else{
		document.getElementById('hoverCritereOTD').innerHTML="\n<span> Critere OTD : "+critereOTD+"</span>Statut du delais\n";
	}
	AfficherTDControle();
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
	if(formulaire.statutTravail.value != "EN COURS" && formulaire.statutTravail.value != "STAND BY"){
		for (i = 0; i < list.length; i++) {
		  if(list[i].value==""){
			bRempli=false;
		  }
		}
	}
	if(langue=="EN"){
		if(formulaire.tache.value=='0'){alert('You didn\'t enter the task.');return false;}
		if(formulaire.reference.value==''){alert('You didn\'t enter the reference.');return false;}
		if(strs.length>50){alert('You must list less than 51 references.');return false;}
		if(formulaire.statutTravail.value==''){alert('You didn\'t enter the work status.');return false;}
		if(formulaire.dateTravail.value==''){alert('You didn\'t enter the date of work.');return false;}
		if(bRempli==false){alert('You didn\'t enter the further information.');return false;}
		if(formulaire.statutTravail.value=='A VALIDER'){
			if(document.getElementById('attestation').checked==false){alert('You have not certified the complies of the deliverable.');return false;}
		}
	}
	else{
		if(formulaire.tache.value=='0'){alert('Vous n\'avez pas renseigné la tâche.');return false;}
		if(formulaire.reference.value==''){alert('Vous n\'avez pas renseigné la référence.');return false;}
		if(strs.length>50){alert('Vous devez renseigner 50 references maximum.');return false;}
		if(formulaire.statutTravail.value==''){alert('Vous n\'avez pas renseigné le statut du travail.');return false;}
		if(formulaire.dateTravail.value==''){alert('Vous n\'avez pas renseigné la date du travail.');return false;}
		if(bRempli==false){alert('Vous n\'avez pas renseigné les informations complémentaires.');return false;}
		if(formulaire.statutTravail.value=='A VALIDER'){
			if(document.getElementById('attestation').checked==false){alert('Vous n\'avez pas certifié la conformité du livrable.');return false;}
		}
	}
	
	if(formulaire.Mode.value=="M"){
		var laDate = formulaire.dateTravail.value;
		if(laDate.indexOf("/")>0){
			var res = laDate.split("/");
			laDate = res[2]+"-"+res[1]+"-"+res[0];
		}
		else{
			var res = laDate.split("-");
			laDate = res[0]+"-"+res[1]+"-"+res[2];
		}
		if(formulaire.OldStatut.value == "A VALIDER"){
			if(formulaire.Droit.value.substr(1,1)=="1"){
				if(formulaire.DateFacturation.value!=""){
					if(laDate<=formulaire.DateFacturation.value){
						if(window.confirm('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+'). Êtes-vous sûre de vouloir garder cette date ?')==false){
							formulaire.dateTravail.value=formulaire.OldDateTravail.value;
							return false;
						}
					}
				}
			}
			else if(formulaire.Droit.value.substr(0,1)=="1"){
				if(formulaire.DateFacturation.value!=""){
					if(laDate<=formulaire.DateFacturation.value){
						alert('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+')');
						formulaire.dateTravail.value=formulaire.OldDateTravail.value;
						return false;	
					}
				}
			}
		}
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
	var laDate = formulaire.dateTravail.value;
	if(laDate.indexOf("/")>0){
		var res = laDate.split("/");
		laDate = res[2]+"-"+res[1]+"-"+res[0];
	}
	else{
		var res = laDate.split("-");
		laDate = res[0]+"-"+res[1]+"-"+res[2];
	}
	if(formulaire.Mode.value=="A"){
		if(formulaire.Droit.value.substr(1,1)=="1"){
			if(formulaire.DateFacturation.value!=""){
				if(laDate<=formulaire.DateFacturation.value){
					if(window.confirm('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+'). Êtes-vous sûre de vouloir garder cette date ?')==false){
						formulaire.dateTravail.value=formulaire.OldDateTravail.value;
						
					}
				}
			}
		}
		else if(formulaire.Droit.value.substr(0,1)=="1"){
			if(formulaire.DateFacturation.value!=""){
				if(laDate<=formulaire.DateFacturation.value){
					alert('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+')');
					formulaire.dateTravail.value=formulaire.OldDateTravail.value;
						
				}
			}
		}
	}
	else{
		if(formulaire.OldStatut.value == "A VALIDER"){
			if(formulaire.Droit.value.substr(1,1)=="1"){
				if(formulaire.DateFacturation.value!=""){
					if(laDate<=formulaire.DateFacturation.value){
						if(window.confirm('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+'). Êtes-vous sûre de vouloir garder cette date ?')==false){
							formulaire.dateTravail.value=formulaire.OldDateTravail.value;
						}
					}
				}
			}
			else if(formulaire.Droit.value.substr(0,1)=="1"){
				if(formulaire.DateFacturation.value!=""){
					if(laDate<=formulaire.DateFacturation.value){
						alert('La date du travail est inférieure à la dernière date de facturation ('+formulaire.DateFacturation.value+')');
						formulaire.dateTravail.value=formulaire.OldDateTravail.value;
							
					}
				}
			}
		}
	}
	$.ajax({
		url : 'Ajout_ProductionPlanning.php',
		data : 'dateTravail='+document.getElementById('dateTravail').value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			alert( "Error !: " + string );
			},
		success:function(data){
			//alert(data);
			//on met à jour le div calendrier2 avec les données reçus
			//on vide la div et on le cache
			$("#calendrier2").empty().hide();
			//on affecte les resultats au div
			$("#calendrier2").append(data);
			//on affiche les resultats avec la transition
			$('#calendrier2').fadeIn(2000);
			}
	});
	
	$.ajax({
		url : 'Ajout_ProductionBloc.php',
		data : 'dateTravail='+document.getElementById('dateTravail').value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			alert( "Error !: " + string );
			},
		success:function(data){
			//alert(data);
			//on met à jour le div blocCalendar avec les données reçus
			//on vide la div et on le cache
			$("#blocCalendar").empty().hide();
			//on affecte les resultats au div
			$("#blocCalendar").append(data);
			//on affiche les resultats avec la transition
			$('#blocCalendar').fadeIn(2000);
			}
	});
}
function AfficherTDControle(){
	if(document.getElementById('NbControle').value=="0"){
		if(document.getElementById('statutTravail').value=="A VALIDER"){
			for(i=0;i<Liste_Tache.length;i++){
				if (Liste_Tache[i][0]==document.getElementById('tache').value){
					if(Liste_Tache[i][1]=="-1"){
						document.getElementById('tdControle').style.display = '';
					}
					else{
						document.getElementById('tdControle').style.display = 'none';
						document.getElementById('controle').checked=false;
					}
				}
			}
		}
		else{
			document.getElementById('tdControle').style.display = 'none';
			document.getElementById('controle').checked=false;
		}
	}
	else{
		document.getElementById('tdControle').style.display = 'none';
		document.getElementById('controle').checked=false;
	}
}