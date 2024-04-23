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
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('dateDebut').value==""){alert("Veuillez remplir une date de début.");return false;}
		if(document.getElementById('transfertPermanent').checked==false){
			if(document.getElementById('dateFin').value==""){alert("Veuillez remplir la date de fin.");return false;}
		}
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
	}
	else{
		if(document.getElementById('dateDebut').value==""){alert("Please fill in the start date.");return false;}
		if(document.getElementById('transfertPermanent').checked==false){
			if(document.getElementById('dateFin').value==""){alert("Please fill in the end date.");return false;}
		}
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}

	}
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
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
Liste_PrestaPoleAccueil = new Array();
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
	Recharge_PrestationAccueil();
}

function Recharge_PrestationAccueil(){
	var bTrouve = false;
	//Recharge les poles de la prestation selectionnée
	var selPresta="<select name='Id_PrestationPoleAccueil' id='Id_PrestationPoleAccueil' style='width:400px'>";
	selPresta= selPresta + "<option value='0_0'></option>";
	for(i=0;i<Liste_PrestaPoleAccueil.length;i++){
		if (Liste_PrestaPoleAccueil[i][4]==document.getElementById('Id_Plateforme').value || document.getElementById('Id_Plateforme').value==0){
			if (Liste_PrestaPoleAccueil[i][0]+"_"+Liste_PrestaPoleAccueil[i][1] !=document.getElementById('Id_Prestation').value+"_"+document.getElementById('Id_Pole').value){
				selPresta= selPresta + "<option value="+Liste_PrestaPoleAccueil[i][0]+"_"+Liste_PrestaPoleAccueil[i][1];
				pole="";
				if(Liste_PrestaPoleAccueil[i][1]!="0"){
					pole=" - "+Liste_PrestaPoleAccueil[i][3];
				}
				selPresta= selPresta + ">"+Liste_PrestaPoleAccueil[i][2]+pole+"</option>";
				bTrouve=true;
			}
		}
	}
	selPresta =selPresta + "</select>";
	document.getElementById('Id_PrestationPoleAccueil').innerHTML=selPresta;
	document.getElementById('Id_PrestationPoleAccueil').value="0_0";
}

function AfficheDateFin(){
	if(document.getElementById('transfertPermanent').checked==false){
		document.getElementById('trDateFin').style.display="";
	}
	else{
		document.getElementById('trDateFin').style.display="none";
	}
}

function Recharge_Personnel(){
	var bTrouve = false;
	//Recharge les personnes de la prestation et le pole selectionnée
	var selPers="<select name='Id_Personne' id='Id_Personne' onDblclick='ajouter();'>";
	for(i=0;i<Liste_Personne.length;i++){
		if (Liste_Personne[i][2]==document.getElementById('Id_Prestation').value && Liste_Personne[i][3]==document.getElementById('Id_Pole').value){
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