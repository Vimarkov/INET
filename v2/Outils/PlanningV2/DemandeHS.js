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

function selectall(){
	document.getElementById('Contrat').innerHTML="";
	document.getElementById('CongesAbsences').innerHTML="";
	
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('Date').value==""){alert("Veuillez remplir la date.");return false;}
		if(document.getElementById('Nb_Heures_Jour').value==0 && document.getElementById('Nb_Heures_Nuit').value==0){alert("Veuillez remplir un nombre d'heures.");return false;}
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
	}
	else{
		if(document.getElementById('Date').value==""){alert("Please fill in the date.");return false;}
		if(document.getElementById('Nb_Heures_Jour').value==0 && document.getElementById('Nb_Heures_Nuit').value==0){alert("Please fill in a number of hours.");return false;}
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}

	}
	Id_Personnes="";
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
		Id_Personnes=Id_Personnes+"_"+document.getElementById('PersonneSelect').options[y].value;
	}
	$.ajax({
		url : 'Ajax_HorsContratCeJour3.php',
		data : 'DateHS='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('Contrat').innerHTML=data;
			}
	});
	if(document.getElementById('Contrat').innerHTML.indexOf("attention.png")!=-1){return false;}
	else{
		document.getElementById('CongesAbsences').innerHTML="";
		$.ajax({
			url : 'Ajax_AbsenceCeJour.php',
			data : 'DateDebut='+formulaire.Date.value+'&DateFin='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('CongesAbsences').innerHTML=data;
				}
		});
		if(document.getElementById('CongesAbsences').innerHTML.indexOf("attention.png")!=-1){return false;}
	}
	
	document.getElementById('HS').innerHTML="";
	$.ajax({
		url : 'Ajax_HSCeJour3.php',
		data : 'DateHS='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes,
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
	if(document.getElementById('HS').innerHTML.indexOf("attention.png")!=-1){return false;}	
	
	document.getElementById('ASJourNonT').innerHTML="";
	$.ajax({
		url : 'Ajax_AstreinteHorsContratCeJour.php',
		data : 'DateHS='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('ASJourNonT').innerHTML=data;
			}
	});
	if(document.getElementById('ASJourNonT').innerHTML.indexOf("attention.png")!=-1){return false;}	
	
	document.getElementById('10HEURES').innerHTML="";
	$.ajax({
		url : 'Ajax_Plus10HeuresCeJour.php',
		data : 'DateHS='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes+'&NbHeuresJ='+document.getElementById('Nb_Heures_Jour').value+'&NbHeuresN='+document.getElementById('Nb_Heures_Nuit').value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('10HEURES').innerHTML=data;
			}
	});
	
	//Récupérer la partie entre les balises <lesHS></lesHS>
	if(document.getElementById('10HEURES').innerHTML.indexOf("attention.png")!=-1){return false;}	
	
	document.getElementById('48HEURES').innerHTML="";
	$.ajax({
		url : 'Ajax_Plus48HeuresCetteSemaine.php',
		data : 'DateHS='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes+'&NbHeuresJ='+document.getElementById('Nb_Heures_Jour').value+'&NbHeuresN='+document.getElementById('Nb_Heures_Nuit').value,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('48HEURES').innerHTML=data;
			}
	});
	
	//Récupérer la partie entre les balises <lesHS></lesHS>
	if(document.getElementById('48HEURES').innerHTML.indexOf("attention.png")!=-1){return false;}	
	
	//ASTREINTE CE JOUR LA 
	document.getElementById('AS').innerHTML="";
	$.ajax({
		url : 'Ajax_AstreinteCeJour2.php',
		data : 'DateJour='+formulaire.Date.value+'&Id_Personnes='+Id_Personnes,
		dataType : "html",
		async : false,
		//affichage de l'erreur en cas de problème
		error:function(msg, string){
			
			},
		success:function(data){
			document.getElementById('AS').innerHTML=data;
			}
	});
	//Récupérer la partie entre les balises <lesHS></lesHS>
	AS=document.getElementById('AS').innerHTML.substring(document.getElementById('AS').innerHTML.indexOf("lesASDEBUT")+10,document.getElementById('AS').innerHTML.indexOf("lesASFIN"));
	if(AS!=""){
		if(document.getElementById('Langue').value=="FR"){
			question="Les personnes suivantes ont des astreintes déclarées ce jour là : \n"+AS+" \nVoulez vous continuez ?";
		}
		else{
			question="The following people have reported penalties on this days : \n"+AS+" \nDo you want to continue?";
		}
		if(window.confirm(question)){
			return true;
		}
		else{
			return false;
		}
	}
}
function TransfererListePersonne(ListePersonne){
	var chaine=ListePersonne;
	var reg=new RegExp("[;]+", "g");
	var tableau=chaine.split(reg);
	for (var i=0; i<tableau.length; i++)
	{
		for(y=0;y<document.getElementById('Id_Personne').length;y++)
		{
			if(document.getElementById('Id_Personne').options[y].value == tableau[i]){document.getElementById('Id_Personne').options[y].selected = true;}
		}
	}
	ajouter();
}

Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
Liste_Personne = new Array(); //Id, Personne, Id_Prestation, Pole
function Recharge_Responsables(){
	var bTrouve = false;
	//Recharge les poles de la prestation selectionnée
	var selPole="<select name='Id_Pole' id='Id_Pole' onchange='Recharge_ResponsablesP();'>";
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
	
	if(document.getElementById('Langue').value=="FR"){resp="Responsables ";}
	else{resp="Responsibles ";}
	
	var sel="<table>";
	var ValidateurN1="<tr><td>";
	var ValidateurN2="<tr><td>";
	var ValidateurN3="<tr><td>";
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
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + resp + "N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				case 3:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN3=ValidateurN3 + resp + "N+3 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr>" + ValidateurN3 + "</td></tr></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
	
	Recharge_Personnel();
}
function Recharge_ResponsablesP(){
	var sel="<table>";
	var ValidateurN1="<tr><td>";
	var ValidateurN2="<tr><td>";
	var ValidateurN3="<tr><td>";
	
	if(document.getElementById('Langue').value=="FR"){resp="Responsables ";}
	else{resp="Responsibles ";}
	for(var i=0;i<Liste_Poste_Prestation.length;i++)
	{
		if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value && Liste_Poste_Prestation[i][4]==document.getElementById('Id_Pole').value)
		{
			switch (Liste_Poste_Prestation[i][1])	//Id_Poste
			{
				case 2:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + resp + "N+1 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				case 3:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + resp + "N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				case 4:
					if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN3=ValidateurN3 + resp + "N+3 :" + Liste_Poste_Prestation[i][3] + "; ";}
					if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
					break;
				default:
			}
		}
	}
	sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr>" + ValidateurN3 + "</td></tr></table>";
	document.getElementById('PostesValidateurs').innerHTML=sel;
	Recharge_Personnel();
}

function Recharge_Personnel(){
	var bTrouve = false;
	//Recharge les personnes de la prestation et le pole selectionnée
	var selPers="<select name='Id_Personne' id='Id_Personne' onDblclick='ajouter();'>";
	for(i=0;i<Liste_Personne.length;i++){
		if ((Liste_Personne[i][2]==document.getElementById('Id_Prestation').value && Liste_Personne[i][3]==document.getElementById('Id_Pole').value) || document.getElementById('Id_Prestation').value==-1){
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