issetfocus="";
ListeTypeAbsence = new Array();
Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
function Modif_TypeAbsence(){
	bTrouve=false;
	heure=" jour(s)";
	if(document.getElementById('Langue').value!="FR"){heure=" hour(s)";}
	for(i=0;i<ListeTypeAbsence.length;i++){
		if (ListeTypeAbsence[i][0]==document.getElementById('typeAbsence').value){
			if(ListeTypeAbsence[i][1]>0){
				document.getElementById('nbJourTypeAbs').innerHTML="&nbsp;&nbsp;"+ListeTypeAbsence[i][1]+heure;
				document.getElementById('NbJoursMax').value=ListeTypeAbsence[i][1];
				document.getElementById('jourCalendaire').value=ListeTypeAbsence[i][2];
				document.getElementById('infosSalarie'+ListeTypeAbsence[i][0]).style.display="";
				bTrouve=true;
			}
		}
		else{
			document.getElementById('infosSalarie'+ListeTypeAbsence[i][0]).style.display="none";
		}
	}
	if(bTrouve==false){
		document.getElementById('nbJourTypeAbs').innerHTML="";
		document.getElementById('NbJoursMax').value=0;
		document.getElementById('jourCalendaire').value=0;
	}
	//Affectation date de fin en fonction de la date de début
	myDateDebut = formulaire.dateDebut.value;
	myDateDebut2 = myDateDebut.split("-");
	if (myDateDebut2.length == 1){
		myDateDebut = myDateDebut.split("/");
		newDateDebut = myDateDebut[2]+"-"+myDateDebut[1]+"-"+myDateDebut[0];
	}
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
	
	myDateDebut = formulaire.dateDebut.value;
	myDateDebut2 = myDateDebut.split("-");
	dateJJJJMM="";
	if (myDateDebut2.length == 1){
		myDateDebut = myDateDebut.split("/");
		dateJJJJMM=myDateDebut[2]+"/"+myDateDebut[1];
	}
	else{
		myDateDebut = myDateDebut.split("-");
		dateJJJJMM=myDateDebut[0]+"/"+myDateDebut[1];
	}
	
	//Si date de début < Mois E/C -2 OU date de début < Mois E/C -1 ET Date E/C >=10 du mois alors IMPOSSIBLE = Efface les infos 
	var ladate=new Date();
	ladate.setMonth(ladate.getMonth()-2);
	mois=ladate.getMonth()+1;
	if(mois<10){mois="0"+mois;}
	date_2Mois=ladate.getFullYear()+"/"+(mois);
	
	var ladate=new Date();
	ladate.setMonth(ladate.getMonth()-1);
	mois=ladate.getMonth()+1;
	if(mois<10){mois="0"+mois;}
	date_1Mois=ladate.getFullYear()+"/"+(mois);
	
	var ladate=new Date();
	mois=ladate.getMonth()+1;
	if(mois<10){mois="0"+mois;}
	date_10=ladate.getFullYear()+"-"+mois+"-10";
	
	var ladate=new Date();
	mois=ladate.getMonth()+1;
	if(mois<10){mois="0"+mois;}
	jour=ladate.getDate();
	if(jour<10){jour="0"+jour;}
	date_Jour=ladate.getFullYear()+"-"+mois+"-"+jour;
	
	if(formulaire.Menu.value!=4){
		if(dateJJJJMM<=date_2Mois || (dateJJJJMM<date_1Mois && date_Jour>=date_10)){
			formulaire.dateDebut.value="";
			formulaire.dateFin.value="";
		}
	}
	
	if(formulaire.Menu.value==2){
		if(newDateDebut<date_Jour){
			formulaire.dateDebut.value="";
			formulaire.dateFin.value="";
		}
	}

	formulaire.heureDebut.value="";
	formulaire.heureFin.value="";
	formulaire.nbHeureJour.value="";
	formulaire.nbHeureNuit.value="";
	formulaire.nbHeureRC.value="";
	formulaire.nbHeuresBDD.value="";
	formulaire.fonctionRepresentative.value="0";
	document.getElementById('journeeComplete').checked=true;
	
	var lesElements = document.getElementsByClassName('journee');
	for (i=0; i<lesElements.length; i++){
	  lesElements[i].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeure');
	for (i=0; i<lesElements.length; i++){
	  lesElements[i].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeuresRC');
	for (i=0; i<lesElements.length; i++){
	  lesElements[i].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeuresBDD');
	for (i=0; i<lesElements.length; i++){
	  lesElements[i].style.display='none';
	}
	var lesElements = document.getElementsByClassName('delegation');
	for (i=0; i<lesElements.length; i++){
	  lesElements[i].style.display='none';
	}
	
	//CSS ou EM (100%) ou EM (50%) ou AM 
	if (document.getElementById('typeAbsence').value == 8 || document.getElementById('typeAbsence').value == 26 || document.getElementById('typeAbsence').value == 30 || document.getElementById('typeAbsence').value == 1){
		document.getElementById('journeeComplete').checked=true;
		var lesElements = document.getElementsByClassName('journee');
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	// RC
	else if (document.getElementById('typeAbsence').value == 11){
		var lesElements = document.getElementsByClassName('nbHeuresRC');
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	//BDD
	else if (document.getElementById('typeAbsence').value == 9){
		var lesElements = document.getElementsByClassName('delegation');
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	if(document.getElementById('journeeComplete').checked==false){
		formulaire.dateFin.value=formulaire.dateDebut.value;
	}
}

function Modif_TypeAbsenceRH(i){
	
	document.getElementById('heureDebut'+i).value="";
	document.getElementById('nbHeureJour'+i).value="";
	document.getElementById('nbHeureNuit'+i).value="";
	document.getElementById('nbHeureRC'+i).value="";
	document.getElementById('nbHeuresBDD'+i).value="";
	document.getElementById('fonctionRepresentative'+i).value="0";
	
	var lesElements = document.getElementsByClassName('journee'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeure'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeuresRC'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeuresBDD'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('delegation'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}

	//CSS ou EM (100%) ou EM (50%) ou AM
	if (document.getElementById('typeAbsence'+i).value == 8 || document.getElementById('typeAbsence'+i).value == 26 || document.getElementById('typeAbsence'+i).value == 30 || document.getElementById('typeAbsence'+i).value == 1){
		document.getElementById('journeeComplete'+i).checked=true;
		var lesElements = document.getElementsByClassName('journee'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	// RC
	else if (document.getElementById('typeAbsence'+i).value == 11){
		var lesElements = document.getElementsByClassName('nbHeuresRC'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	//BDD
	else if (document.getElementById('typeAbsence'+i).value == 9){
		var lesElements = document.getElementsByClassName('delegation'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
}

function Modif_TypeAbsenceRHABS(i){
	
	document.getElementById('heureDebut'+i).value="";
	document.getElementById('heureFin'+i).value="";
	document.getElementById('nbHeureJour'+i).value="";
	document.getElementById('nbHeureNuit'+i).value="";
	document.getElementById('nbHeureRC'+i).value="";
	document.getElementById('nbHeuresBDD'+i).value="";
	document.getElementById('fonctionRepresentative'+i).value="0";
	
	var lesElements = document.getElementsByClassName('journee'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeure'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeuresRC'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('nbHeuresBDD'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}
	var lesElements = document.getElementsByClassName('delegation'+i);
	for (k=0; k<lesElements.length; k++){
	  lesElements[k].style.display='none';
	}

	//CSS ou EM (100%) ou EM (50%) ou AM
	if (document.getElementById('typeAbsence'+i).value == 8 || document.getElementById('typeAbsence'+i).value == 26 || document.getElementById('typeAbsence'+i).value == 30 || document.getElementById('typeAbsence'+i).value == 1){
		document.getElementById('journeeComplete'+i).checked=true;
		var lesElements = document.getElementsByClassName('journee'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	// RC
	else if (document.getElementById('typeAbsence'+i).value == 11){
		var lesElements = document.getElementsByClassName('nbHeuresRC'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	//BDD
	else if (document.getElementById('typeAbsence'+i).value == 9){
		var lesElements = document.getElementsByClassName('delegation'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	
	//ABS ou ABS JUSTIF NON REM
	else if (document.getElementById('typeAbsence'+i).value == 0 || document.getElementById('typeAbsence'+i).value == 79){
		document.getElementById('journeeComplete'+i).checked=true;
		var lesElements = document.getElementsByClassName('journee'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
}

function Modif_TypeAbsence2RH(i){
	document.getElementById('nbHeureJour'+i).value="";
	document.getElementById('nbHeureNuit'+i).value="";
	if (document.getElementById('journeeComplete'+i).checked==false){
		var lesElements = document.getElementsByClassName('nbHeure'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	else{
		var lesElements = document.getElementsByClassName('nbHeure'+i);
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='none';
		}
	}
}
function Modif_TypeAbsence2(){
	formulaire.nbHeureJour.value="";
	formulaire.nbHeureNuit.value="";
	if (document.getElementById('journeeComplete').checked==false){
		var lesElements = document.getElementsByClassName('nbHeure');
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='';
		}
	}
	else{
		var lesElements = document.getElementsByClassName('nbHeure');
		for (i=0; i<lesElements.length; i++){
		  lesElements[i].style.display='none';
		}
	}
	
	if(document.getElementById('journeeComplete').checked==false){
		formulaire.dateFin.value=formulaire.dateDebut.value;
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
function pointVirgule(champ){
	for(x = 0; x < champ.value.length; x++)
	{
		if(champ.value.charAt(x) == ";" || champ.value.charAt(x) == "|"){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
	}
}
function AfficherAjouter(){
	var valide = true;
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
	if(document.getElementById('Langue').value=="FR"){
		if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez compléter la date de début");return false;}
		if(formulaire.dateFin.value==""){valide=false;alert("Veuillez compléter la date de fin");return false;}
		if(newDateDebut>newDateFin){valide=false;alert("Veuillez renseigner des dates valides");return false;}
		
		if(formulaire.typeAbsence.value=="0"){valide=false;alert("Veuillez compléter le type d\'absence");return false;}
		
		//BDD
		if(formulaire.typeAbsence.value==9){
			if(formulaire.nbHeuresBDD.value==""){valide=false;alert("Veuillez compléter le nombre d\'heures");return false;}
			if(formulaire.fonctionRepresentative.value=="0"){valide=false;alert("Veuillez sélectionner la fonction représentative");return false;}
		}
		
		//RC
		if(formulaire.typeAbsence.value==11){
			if(formulaire.nbHeureRC.value==""){valide=false;alert("Veuillez compléter le nombre d\'heures");return false;}
		}
		
		//CSS ou EM (100%) ou EM (50%) ou AM
		if (document.getElementById('typeAbsence').value == 8 || document.getElementById('typeAbsence').value == 26 || document.getElementById('typeAbsence').value == 30 || document.getElementById('typeAbsence').value == 1){
			if(document.getElementById("journeeComplete").checked==false){
				if(formulaire.heureDebut.value=="" || formulaire.heureFin.value==""){valide=false;alert("Veuillez compléter l'\heure de début et l'\heure de fin");return false;}
				//if(nbMinutes(formulaire.heureDebut.value)>=nbMinutes(formulaire.heureFin.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
				if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Veuillez compléter le nombre d\'heures");return false;}
			}
		}
		
	}
	else{
		if(formulaire.dateDebut.value==""){valide=false;alert("Please fill in the start date");return false;}
		if(formulaire.dateFin.value==""){valide=false;alert("Please complete the end date");return false;}
		if(newDateDebut>newDateFin){valide=false;alert("Please enter valid dates");return false;}
		
		if(formulaire.typeAbsence.value=="0"){valide=false;alert("Please complete the type of absence");return false;}
		
		//BDD
		if(formulaire.typeAbsence.value==9){
			if(formulaire.nbHeuresBDD.value==""){valide=false;alert("Veuillez compléter le nombre d\'heures");return false;}
			if(formulaire.fonctionRepresentative.value=="0"){valide=false;alert("Please select the representative function");return false;}
		}
		
		//RC
		if(formulaire.typeAbsence.value==11){
			if(formulaire.nbHeureRC.value==""){valide=false;alert("Please complete the number of hours");return false;}
		}
		
		//CSS ou EM (100%) ou EM (50%) ou AM 
		if (document.getElementById('typeAbsence').value == 8 || document.getElementById('typeAbsence').value == 26 || document.getElementById('typeAbsence').value == 30 || document.getElementById('typeAbsence').value == 1){
			if(document.getElementById("journeeComplete").checked==false){
				if(formulaire.heureDebut.value=="" || formulaire.heureFin.value==""){valide=false;alert("Please complete the start time and the end time");return false;}
				//if(nbMinutes(formulaire.heureDebut.value)>=nbMinutes(formulaire.heureFin.value)){valide=false;alert("The start time must be less than the end time");return false;}
				if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Please complete the number of hours");return false;}
			}
		}
	}
	
	var tomorrow = new Date();
	
	lanewDateDebut=newDateDebut;
	lanewDateFin=newDateFin;
	
	//Vérifier si le nombre de jours max = nombre de jours posé
	if(document.getElementById("NbJoursMax").value!=0){
		nb=0;
		vsd=0;
		sd=0;
		while(newDateDebut<=newDateFin){
			myDate = newDateDebut.split("-");
			$.ajax({
				url : 'Ajax_TravailCejour.php',
				data : 'DateJour='+newDateDebut+'&Id_Personne='+document.getElementById('Id_Personne').value,
				dataType : "html",
				async : false,
				//affichage de l'erreur en cas de problème
				error:function(msg, string){
					},
				success:function(data){
					document.getElementById('travailCeJour').value=data;
					}
			});
			
			$.ajax({
				url : 'Ajax_VSD.php',
				data : 'DateJour='+newDateDebut+'&Id_Personne='+document.getElementById('Id_Personne').value,
				dataType : "html",
				async : false,
				//affichage de l'erreur en cas de problème
				error:function(msg, string){
					},
				success:function(data){
					document.getElementById('VSD').value=data;
					}
			});
			if(document.getElementById('VSD').value.indexOf("VSD")!=-1){
				vsd=1;
			}
			else if(document.getElementById('VSD').value.indexOf("SD")!=-1){
				sd=1;
			}
			
			//Vérifier si jour calendaire
			if(document.getElementById("jourCalendaire").value==1){
				nb++;
			}
			else if(document.getElementById('travailCeJour').value.indexOf("TRAVAIL")!=-1){
				//Vérifier si n'est pas un jour férié 
				if(document.getElementById("ListeJoursFerie").value.search(newDateDebut)==-1 || vsd==1 || sd==1){
					nb++;
				}
			}

			$.ajax({
				url : 'Ajax_JourSuivant.php',
				data : 'laDate='+newDateDebut,
				dataType : "html",
				async : false,
				//affichage de l'erreur en cas de problème
				error:function(msg, string){
					
					},
				success:function(data){
					document.getElementById('leJour').value=data;
					}
			});
			
			newDateDebut=document.getElementById('leJour').value.substring(document.getElementById('leJour').value.indexOf("LEJOUR")+6,document.getElementById('leJour').value.indexOf("FIN"));
		}
		if(nb!=document.getElementById("NbJoursMax").value && vsd==0){
			if(document.getElementById('Langue').value=="FR"){alert("Le nombre de jours saisies est différent du nombre de jours autorisés. "+nb+" jour(s) au lieu de "+document.getElementById("NbJoursMax").value);}
			else{alert("The number of days entered is different from the number of days allowed. "+nb+" day(s) instead of "+document.getElementById("NbJoursMax").value);}
			return false;
		}
	}
	
	listeDate="";
	
	while(newDateDebut<=newDateFin){
		myDate = newDateDebut.split("-");
		//document.getElementById('AS').value="";
		
		//Verifier si la personne a des astreintes de prévues ce jour là
		$.ajax({
			url : 'Ajax_AstreinteCeJour.php',
			data : 'DateJour='+newDateDebut+'&Id_Personne='+document.getElementById('Id_Personne').value,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('AS').value=data;
				}
		});
		
		if(document.getElementById('AS').value.indexOf("ASTREINTE")!=-1){
			//Format JJ/MM/AAAA
			laDate=newDateDebut.substr(8,2)+"/"+newDateDebut.substr(5,2)+"/"+newDateDebut.substr(0,4)
			listeDate+=" - "+laDate+"\n";
		}
		
		$.ajax({
			url : 'Ajax_JourSuivant.php',
			data : 'laDate='+newDateDebut,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('leJour').value=data;
				}
		});
		
		newDateDebut=document.getElementById('leJour').value.substring(document.getElementById('leJour').value.indexOf("LEJOUR")+6,document.getElementById('leJour').value.indexOf("FIN"));
	}
	
	if(listeDate!=""){
		if(document.getElementById('Langue').value=="FR"){
			question="Des astreintes sont prévues aux dates suivantes : \n"+listeDate+" Voulez vous continuez ?";
		}
		else{
			question="The penalty is scheduled for the following dates : \n"+listeDate+" Do you want to continue?";
		}
		if(window.confirm(question)){
			
		}
		else{
			return false;
		}
	}
	
	//Vérifier si dates déjà rajoutées 
	document.getElementById('CongesExistants').innerHTML="";
	$.ajax({
		url : 'Ajax_CongesExistants.php',
		data : 'DateDebut='+lanewDateDebut+'&DateFin='+lanewDateFin+'&CongesEC='+formulaire.absences.value+'&Id_Personne='+document.getElementById('Id_Personne').value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('CongesExistants').innerHTML=data;
			}
	});
	
	document.getElementById('travailCeJour').value="";
	document.getElementById('HorsContrat').innerHTML="";
	if(document.getElementById('CongesExistants').innerHTML.indexOf("attention.png")==-1){
			//Vérifie si HS dans le créneau 
			document.getElementById('HS').innerHTML="";
			$.ajax({
				url : 'Ajax_HSCeJour4.php',
				data : 'DateDebut='+lanewDateDebut+'&DateFin='+lanewDateFin+'&Id_Personne='+document.getElementById('Id_Personne').value,
				dataType : "html",
				async : false,
				//affichage de l'erreur en cas de problème
				error:function(msg, string){
					
					},
				success:function(data){
					document.getElementById('HS').innerHTML=data;
					}
			});
			//Récupérer la partie entre les balises <lesHS></lesHS>
			if(document.getElementById('HS').innerHTML.indexOf("attention.png")==-1){
				//Vérifie si Formation dans le créneau 
				document.getElementById('Formation').innerHTML="";
				$.ajax({
					url : 'Ajax_FormationCeJour.php',
					data : 'DateDebut='+lanewDateDebut+'&DateFin='+lanewDateFin+'&Id_Personne='+document.getElementById('Id_Personne').value,
					dataType : "html",
					async : false,
					//affichage de l'erreur en cas de problème
					error:function(msg, string){
						
						},
					success:function(data){
						document.getElementById('Formation').innerHTML=data;
						}
				});
				if(document.getElementById('Formation').innerHTML.indexOf("attention.png")==-1){
					var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnAjouter2' name='btnAjouter2' value='Enregistrer'>";
					document.getElementById('Ajouter').innerHTML=bouton;
					var evt = document.createEvent("MouseEvents");
					evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
					document.getElementById("btnAjouter2").dispatchEvent(evt);
					document.getElementById('Ajouter').innerHTML="";
				}
			}
	}
}
function Supprimer(valeur){
	document.getElementById('absences').value = document.getElementById('absences').value.replace(valeur,"");
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnAjouter2' name='btnAjouter2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnAjouter2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function Enregistrer(){
	var valide = true;
	
	if(document.getElementById('Langue').value=="FR"){
		if(formulaire.absences.value==""){valide=false;alert("Veuillez ajouter une absence");return false;}
	}
	else{
		if(formulaire.absences.value==""){valide=false;alert("Please add an absence");return false;}
	}
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function Enregistrer2(){
	var valide = true;
	
	if(formulaire.personne.value==""){valide=false;alert("Veuillez compléter la personne concernée");return false;}
	if(formulaire.prestation.value==""){valide=false;alert("Veuillez compléter le site");return false;}
	if(formulaire.absences.value==""){valide=false;alert("Veuillez ajouter une absence");return false;}
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function Modif_Date(){
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

function AfficherAjouterAI(){
	var valide = true;
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
	if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez compléter la date de début");return false;}
	if(formulaire.dateFin.value==""){valide=false;alert("Veuillez compléter la date de fin");return false;}
	if(newDateDebut>newDateFin){valide=false;alert("Veuillez renseigner des dates valides");return false;}
	if(formulaire.Lundi.checked==false && formulaire.Mardi.checked==false && formulaire.Mercredi.checked==false && formulaire.Jeudi.checked==false && formulaire.Vendredi.checked==false && formulaire.Samedi.checked==false && formulaire.Dimanche.checked==false){
		valide=false;alert("Veuillez compléter les jours d\'absences");return false;
	}
	if(document.getElementById("journeeEntiere").checked==false){
		if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Veuillez compléter le nombre d\'heures");return false;}
	}
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnAjouter2' name='btnAjouter2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnAjouter2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function Recharge_Responsables(){
	var bTrouve = false;

	if(document.getElementById('Langue').value=="FR"){resp="Responsables ";}
	else{resp="Responsibles ";}
	
	var sel="<table>";
	var ValidateurN1="<tr><td>";
	var ValidateurN2="<tr><td>";
	for(var i=0;i<Liste_Poste_Prestation.length;i++)
	{
		if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value && Liste_Poste_Prestation[i][4]==document.getElementById('Id_Pole').value)
		{
			switch (Liste_Poste_Prestation[i][1])	//Id_Poste
			{
				case 1:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + resp + "N+1 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				case 2:
					if(document.getElementById('Niveau').value==2){
						if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + resp + "N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
						if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
						if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					}
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
}

function nombre2(champ)
{
	var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
	var verif;
	var points = 0; /* Supprimer cette ligne */
	for(x = 0; x < champ.value.length; x++){
		verif = chiffres.test(champ.value.charAt(x));
		if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
		if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
		if(verif == false){
			champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;
		}
	}
	if(champ.value>8){
		champ.value='';
		x=0;
	}
}

function nbMinutes(heure){
	nbMinute=0;
	if(heure!=""){
		time = heure.split(':');
		nbMinute=time[0]*60 + time[1];
	}
	return parseFloat(nbMinute);
}