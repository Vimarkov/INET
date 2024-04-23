Liste_Pole = new Array();
Liste_PrestationPole = new Array();

Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
Liste_Personne = new Array(); //Id, Personne, Id_Prestation, Pole
function Recharge_Responsables(){
	var bTrouve = false;
	//Recharge les poles de la prestation selectionnée
	var selPole="<select name='Id_Pole' id='Id_Pole' onchange='Recharge_Personnel();'>";
	for(i=0;i<Liste_Pole_Prestation.length;i++){
		if (Liste_Pole_Prestation[i][1]==document.getElementById('Id_Prestation').value){
			selPole= selPole + "<option value="+Liste_Pole_Prestation[i][0];
			selPole= selPole + ">"+Liste_Pole_Prestation[i][2]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selPole= selPole + "<option value=0></option>";}
	selPole =selPole + "</select>";
	document.getElementById('Id_Pole').innerHTML=selPole;
	
	Recharge_Personnel();
}

function Recharge_Personnel(){
	var bTrouve = false;
	//Recharge les personnes de la prestation et le pole selectionnée
	var selPers="<select name='Id_Personne' id='Id_Personne' onDblclick='ajouter();'>";
	for(i=0;i<Liste_Personne.length;i++){
		if ((Liste_Personne[i][2]==document.getElementById('Id_Prestation').value && Liste_Personne[i][3]==document.getElementById('Id_Pole').value) || document.getElementById('Id_Prestation').value==0){
			selPers= selPers + "<option value="+Liste_Personne[i][0];
			selPers= selPers + ">"+Liste_Personne[i][1]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selPers= selPers + "<option value=0></option>";}
	selPers =selPers + "</select>";
	document.getElementById('Id_Personne').innerHTML=selPers;
	
	document.getElementById('PersonneSelect').options.length=0;
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
function Enregistrer(){
	var valide = true;
	
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez compléter la date de début");return false;}
		if (document.getElementById('absenceJustifiee').checked==true){
			if(formulaire.typeAbsence.value=="0"){valide=false;alert("Veuillez sélectionner le type d'\absence");return false;}
		}
		if (document.getElementById('journeeEntiere').checked==false){
			if(formulaire.heureDebut.value=="" || formulaire.heureFin.value==""){valide=false;alert("Veuillez compléter l'\heure de début et l'\heure de fin");return false;}
			//if(nbMinutes(formulaire.heureDebut.value)>=nbMinutes(formulaire.heureFin.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
			if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Veuillez compléter le nombre d'\heures d'\absence");return false;}
		}
		if(formulaire.dateFin.value==""){valide=false;alert("Veuillez compléter la date de fin");return false;}
	}
	else{
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Please fill in the start date");return false;}
		if (document.getElementById('absenceJustifiee').checked==true){
			if(formulaire.typeAbsence.value=="0"){valide=false;alert("Please select the type of absence");return false;}
		}
		if (document.getElementById('journeeEntiere').checked==false){
			if(formulaire.heureDebut.value=="" || formulaire.heureFin.value==""){valide=false;alert("Please complete the start time and the end time");return false;}
			//if(nbMinutes(formulaire.heureDebut.value)>=nbMinutes(formulaire.heureFin.value)){valide=false;alert("The start time must be less than the end time");return false;}
			if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Please complete the number of hours of absence");return false;}
		}
		if(formulaire.dateFin.value==""){valide=false;alert("Please complete the end date");return false;}
	}
	
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
	}
	
	Id_Personnes="";
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
		Id_Personnes=Id_Personnes+"_"+document.getElementById('PersonneSelect').options[y].value;
	}
	document.getElementById('CongesAbsences').innerHTML="";
	$.ajax({
		url : 'Ajax_AbsenceCeJour.php',
		data : 'DateDebut='+formulaire.dateDebut.value+'&DateFin='+formulaire.dateFin.value+'&Id_Personnes='+Id_Personnes,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('CongesAbsences').innerHTML=data;
			}
	});
	if(document.getElementById('CongesAbsences').innerHTML.indexOf("attention.png")==-1){
		document.getElementById('HS').innerHTML="";
		$.ajax({
			url : 'Ajax_HSCeJour.php',
			data : 'DateDebut='+formulaire.dateDebut.value+'&DateFin='+formulaire.dateFin.value+'&Id_Personnes='+Id_Personnes,
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
		HS=document.getElementById('HS').innerHTML.substring(document.getElementById('HS').innerHTML.indexOf("lesHSDEBUT")+10,document.getElementById('HS').innerHTML.indexOf("lesHSFIN"));
		if(HS!=""){
			if(document.getElementById('Langue').value=="FR"){
				question="Les personnes suivantes ont des heures supplémentaires déclarées à ces dates : \n"+HS+" Si vous continuez ces heures supplémentaires seront supprimées\nVoulez vous continuez ?";
			}
			else{
				question="The following people have declared overtime on these dates : \n"+HS+" If you continue these extra hours will be deleted\nDo you want to continue?";
			}
			if(window.confirm(question)){
				var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
				document.getElementById('Ajouter').innerHTML=bouton;
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("btnEnregistrer2").dispatchEvent(evt);
				document.getElementById('Ajouter').innerHTML="";
			}
		}
		else{
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Ajouter').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Ajouter').innerHTML="";
		}
	}
}

function EnregistrerManager(){
	var valide = true;
	
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez compléter la date de début");return false;}
		if (document.getElementById('journeeEntiere').checked==false){
			if(formulaire.heureDebut.value=="" || formulaire.heureFin.value==""){valide=false;alert("Veuillez compléter l'\heure de début et de fin");return false;}
			//if(nbMinutes(formulaire.heureDebut.value)>=nbMinutes(formulaire.heureFin.value)){valide=false;alert("L\'heure de début doit être inférieure à l\'heure de fin");return false;}
			if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Veuillez compléter le nombre d'\heures d'\absence");return false;}
		}
		if(formulaire.dateFin.value==""){valide=false;alert("Veuillez compléter la date de fin");return false;}
	}
	else{
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Please fill in the start date");return false;}
		if (document.getElementById('journeeEntiere').checked==false){
			if(formulaire.heureDebut.value=="" || formulaire.heureFin.value==""){valide=false;alert("Please complete the start time and the end time");return false;}
			//if(nbMinutes(formulaire.heureDebut.value)>=nbMinutes(formulaire.heureFin.value)){valide=false;alert("The start time must be less than the end time");return false;}
			if(formulaire.nbHeureJour.value=="" && formulaire.nbHeureNuit.value==""){valide=false;alert("Please complete the number of hours of absence");return false;}
		}
		if(formulaire.dateFin.value==""){valide=false;alert("Please complete the end date");return false;}
	}
	
	Id_Personnes="";
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
		Id_Personnes=Id_Personnes+"_"+document.getElementById('PersonneSelect').options[y].value;
	}
	document.getElementById('CongesAbsences').innerHTML="";
	$.ajax({
		url : 'Ajax_AbsenceCeJour.php',
		data : 'DateDebut='+formulaire.dateDebut.value+'&DateFin='+formulaire.dateFin.value+'&Id_Personnes='+Id_Personnes,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('CongesAbsences').innerHTML=data;
			}
	});
	if(document.getElementById('CongesAbsences').innerHTML.indexOf("attention.png")==-1){
		document.getElementById('HS').innerHTML="";
		$.ajax({
			url : 'Ajax_HSCeJour.php',
			data : 'DateDebut='+formulaire.dateDebut.value+'&DateFin='+formulaire.dateFin.value+'&Id_Personnes='+Id_Personnes,
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
		HS=document.getElementById('HS').innerHTML.substring(document.getElementById('HS').innerHTML.indexOf("lesHSDEBUT")+10,document.getElementById('HS').innerHTML.indexOf("lesHSFIN"));
		if(HS!=""){
			if(document.getElementById('Langue').value=="FR"){
				question="Les personnes suivantes ont des heures supplémentaires déclarées à ces dates : \n"+HS+" Si vous continuez ces heures supplémentaires seront supprimées\nVoulez vous continuez ?";
			}
			else{
				question="The following people have declared overtime on these dates : \n"+HS+" If you continue these extra hours will be deleted\nDo you want to continue?";
			}
			if(window.confirm(question)){
				var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
				document.getElementById('Ajouter').innerHTML=bouton;
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("btnEnregistrer2").dispatchEvent(evt);
				document.getElementById('Ajouter').innerHTML="";
			}
		}
		else{
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Ajouter').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Ajouter').innerHTML="";
		}
	}
}
function Affiche_TypeAbsence(check){
	if(check==0){
		var elements = document.getElementsByClassName('types');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	else{
		var elements = document.getElementsByClassName('types');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
}

function Affiche_Heure(check){
	if(check==1){
		var elements = document.getElementsByClassName('heures');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
		document.getElementById('heureDebut').value="";
		document.getElementById('heureFin').value="";
		document.getElementById('nbHeureJour').value="";
		document.getElementById('nbHeureNuit').value="";
	}
	else{
		var elements = document.getElementsByClassName('heures');
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	if(document.getElementById('journeeEntiere').checked==false){
		formulaire.dateFin.value=formulaire.dateDebut.value;
	}
}

function ajouter(){
	for(y=0;y<document.getElementById('Id_Personne').length;y++)
	{
		if(document.getElementById('Id_Personne').options[y].selected == true)
		{
			nouvel_element = new Option(document.getElementById('Id_Personne').options[y].text,document.getElementById('Id_Personne').options[y].value,false,false);
			document.getElementById('PersonneSelect').options[document.getElementById('PersonneSelect').length] = nouvel_element;
			document.getElementById('Id_Personne').options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById('PersonneSelect')
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array()
		Liste[i][0]=Obj.options[i].text
		Liste[i][1]=Obj.options[i].value
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0]
		Obj.options[i].value=Liste[i][1]
	}
}

function effacer(){
	for(y=0;y<document.getElementById('PersonneSelect').length;y++)
	{
		if(document.getElementById('PersonneSelect').options[y].selected == true)
		{
			nouvel_element = new Option(document.getElementById('PersonneSelect').options[y].text,document.getElementById('PersonneSelect').options[y].value,false,false);
			document.getElementById('Id_Personne').options[document.getElementById('Id_Personne').length] = nouvel_element;
			document.getElementById('PersonneSelect').options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById('Id_Personne')
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array()
		Liste[i][0]=Obj.options[i].text
		Liste[i][1]=Obj.options[i].value
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0]
		Obj.options[i].value=Liste[i][1]
	}
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
	
	if(document.getElementById('journeeEntiere').checked==false){
		formulaire.dateFin.value=formulaire.dateDebut.value;
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
}
function nbMinutes(heure){
	nbMinute=0;
	if(heure!=""){
		time = heure.split(':');
		nbMinute=time[0]*60 + time[1];
	}
	return parseFloat(nbMinute);
}