var Tableau_InputACompleter_ValeurSelection = new Array();
var Tableau_InputACompleter_QuantiteSelection = new Array();
Liste_Caisse = new Array();
function FermerEtRecharger(Page)
{
	window.opener.location="Liste_"+Page+".php";
	window.close();
}

function Recharger(Page)
{
	window.opener.location="Liste_"+Page+".php";
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
function Lister_Dependances(TableGenerale,ValeursATrierDansInput,NomChamps,TailleChamps,DivAffichage,LibelleTableResultats)
{
	var DebutCode="";
	var MilieuCode="";
	var FinCode="";
	var Id=0;
	var Couleur="#EEEEEE";
	var AfficherTableau=false;
	var Table = document.getElementById(TableGenerale);
	var Input = Table.getElementsByTagName('input');
	var Valeurs_Tableau_Liste=document.getElementById(ValeursATrierDansInput).value;
	Valeurs_Tableau_Liste=Valeurs_Tableau_Liste.substr(0,Valeurs_Tableau_Liste.length-1);	//pour enlever le dernier caractère µ
	var Tableau_Liste=Valeurs_Tableau_Liste.split('\265');
	var Tableau_NomChamps=NomChamps.split("|");
	var Tableau_TailleChamps=TailleChamps.split("|");
	var Largeur_Tableau_Total=0;
	for(var i=0;i<Input.length;i++)
	{
		if(Input[i].checked == true)
		{
			Id=Input[i].value;
			AfficherTableau=true;
 			for(var j=0;j<Tableau_Liste.length;j++)
 			{
 				var Valeurs=Tableau_Liste[j].split("|");
				if (Valeurs[1]==Input[i].value)
				{
					if(Couleur=="#EEEEEE"){Couleur="#FFFFFF";}
					else{Couleur="#EEEEEE";}
					MilieuCode+="<tr bgcolor='"+Couleur+"'>";
					for(var k=1;k<Tableau_NomChamps.length;k++)
					{
						MilieuCode+="<td class='PetitCompetence'>"+Valeurs[k+1]+"</td>";	//k+1 car les 2 premiers champs dans l'input des données sont les ID
						Largeur_Tableau_Total=Largeur_Tableau_Total+Tableau_TailleChamps[k+1];
					}
					MilieuCode+="</tr>";
				}
 			}
		}
	}
	if(AfficherTableau)
	{
		if(Largeur_Tableau_Total==0){Largeur_Tableau_Total=Tableau_TailleChamps[1];}
		DebutCode+="<table class='ProfilCompetence' style='width:"+Largeur_Tableau_Total+"px;'>";
		DebutCode+="<tr class='TitreSousPageCompetences'><td colspan="+(Tableau_NomChamps.length)+">"+LibelleTableResultats+"</td></tr>";
		DebutCode+="<tr>";
		for(var k=1;k<Tableau_NomChamps.length;k++)
		{
			DebutCode+="<td class='PetiteCategorieCompetence' style='width:"+Tableau_TailleChamps[k]+"px;'>"+Tableau_NomChamps[k]+"</td>";
		}
		DebutCode+="</tr>";
		FinCode="</table>";
		document.getElementById(DivAffichage).innerHTML=DebutCode+MilieuCode+FinCode;
	}
}

function AjouterAListe(ListeDeChoix,ListeACompleter,InputACompleter)
{
	var Liste= new Array();
	var Obj= document.getElementById(ListeACompleter);
	var InputACompleter_ValeurSelection=InputACompleter+"_ValeurSelection";
	var InputACompleter_QuantiteSelection=InputACompleter+"_QuantiteSelection";
	var LigneModif;
	var QteModif;
	
	for(y=0;y<document.getElementById(ListeDeChoix).length;y++)
	{
		if(document.getElementById(ListeDeChoix).options[y].selected == true)
		{
			//On vérifie si l'élément existe déjà dans la liste à compléter (dans ce cas on lui ajoute 1 en quantité)
			TrouveElement=-1;
			for(val=0;val<document.getElementById(ListeACompleter).length;val++)
			{
				if(document.getElementById(ListeACompleter).options[val].value==document.getElementById(ListeDeChoix).options[y].value){
					TrouveElement=val;
				}
				
			}
			if(TrouveElement >- 1)
			{
				for(k=0;k<document.getElementById(ListeACompleter).length;k++)
				{
					if(document.getElementById(ListeACompleter).options[k].value==document.getElementById(ListeDeChoix).options[y].value){
						LigneModif=k;
					}
					
				}
				emplacementTab=Tableau_InputACompleter_ValeurSelection.indexOf(document.getElementById(ListeDeChoix).options[y].value);
				Tableau_InputACompleter_QuantiteSelection[emplacementTab]=Tableau_InputACompleter_QuantiteSelection[emplacementTab]+1;
				QteModif=Tableau_InputACompleter_QuantiteSelection[emplacementTab];
			}
			else
			{
				LigneModif=document.getElementById(ListeACompleter).length;
				QteModif=1;
				Tableau_InputACompleter_ValeurSelection.push(document.getElementById(ListeDeChoix).options[y].value);
				Tableau_InputACompleter_QuantiteSelection.push(1);
			}
			nouvel_element = new Option(document.getElementById(ListeDeChoix).options[y].text+" _________ Qty:"+QteModif,document.getElementById(ListeDeChoix).options[y].value,false,false);
			document.getElementById(ListeACompleter).options[LigneModif] = nouvel_element;
		}
	}

	for(i=0;i<Obj.options.length;i++)
	{
		Liste[i]=new Array();
		Liste[i][0]=Obj.options[i].text;
		Liste[i][1]=Obj.options[i].value;
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++)
	{
		Obj.options[i].text=Liste[i][0];
		Obj.options[i].value=Liste[i][1];
	}
	
	//On remet toutes les valeurs dans les inputs qui vont servir à écrire la request POST et compléter la BDD
	document.getElementById(InputACompleter_ValeurSelection).value=Tableau_InputACompleter_ValeurSelection.join('|');
	document.getElementById(InputACompleter_QuantiteSelection).value=Tableau_InputACompleter_QuantiteSelection.join('|');
}

function AjouterAListe2(ListeDeChoix,ListeACompleter,InputACompleter)
{
	var Liste= new Array();
	var Obj= document.getElementById(ListeACompleter);
	var InputACompleter_ValeurSelection=InputACompleter+"_ValeurSelection";
	var InputACompleter_QuantiteSelection=InputACompleter+"_QuantiteSelection";
	var LigneModif;
	var QteModif;
	
	if(formulaire.nbMateriel.value!="" && formulaire.nbMateriel.value>0){
		for(y=0;y<document.getElementById(ListeDeChoix).length;y++)
		{
			if(document.getElementById(ListeDeChoix).options[y].selected == true)
			{
				//On vérifie si l'élément existe déjà dans la liste à compléter (dans ce cas on lui ajoute 1 en quantité)
				TrouveElement=-1;
				for(val=0;val<document.getElementById(ListeACompleter).length;val++)
				{
					if(document.getElementById(ListeACompleter).options[val].value==document.getElementById(ListeDeChoix).options[y].value){
						TrouveElement=val;
					}
					
				}
				if(TrouveElement >- 1)
				{
					for(k=0;k<document.getElementById(ListeACompleter).length;k++)
					{
						if(document.getElementById(ListeACompleter).options[k].value==document.getElementById(ListeDeChoix).options[y].value){
							LigneModif=k;
						}
						
					}
					emplacementTab=Tableau_InputACompleter_ValeurSelection.indexOf(document.getElementById(ListeDeChoix).options[y].value);
					Tableau_InputACompleter_QuantiteSelection[emplacementTab]=Tableau_InputACompleter_QuantiteSelection[emplacementTab]+parseInt(formulaire.nbMateriel.value);
					QteModif=Tableau_InputACompleter_QuantiteSelection[emplacementTab];
				}
				else
				{
					LigneModif=document.getElementById(ListeACompleter).length;
					QteModif=parseInt(formulaire.nbMateriel.value);
					Tableau_InputACompleter_ValeurSelection.push(document.getElementById(ListeDeChoix).options[y].value);
					Tableau_InputACompleter_QuantiteSelection.push(parseInt(formulaire.nbMateriel.value));
				}
				nouvel_element = new Option(document.getElementById(ListeDeChoix).options[y].text+" _________ Qty:"+QteModif,document.getElementById(ListeDeChoix).options[y].value,false,false);
				document.getElementById(ListeACompleter).options[LigneModif] = nouvel_element;
			}
		}

		for(i=0;i<Obj.options.length;i++)
		{
			Liste[i]=new Array();
			Liste[i][0]=Obj.options[i].text;
			Liste[i][1]=Obj.options[i].value;
		}
		Liste=Liste.sort()
		 
		for(i=0;i<Obj.options.length;i++)
		{
			Obj.options[i].text=Liste[i][0];
			Obj.options[i].value=Liste[i][1];
		}
		
		//On remet toutes les valeurs dans les inputs qui vont servir à écrire la request POST et compléter la BDD
		document.getElementById(InputACompleter_ValeurSelection).value=Tableau_InputACompleter_ValeurSelection.join('|');
		document.getElementById(InputACompleter_QuantiteSelection).value=Tableau_InputACompleter_QuantiteSelection.join('|');
	}
}

function RetirerDeListe(ListeACompleter,InputACompleter)
{
	var InputACompleter_ValeurSelection=InputACompleter+"_ValeurSelection";
	var InputACompleter_QuantiteSelection=InputACompleter+"_QuantiteSelection";
	var QteModif;
	for(y=0;y<document.getElementById(ListeACompleter).length;y++)
	{
		if(document.getElementById(ListeACompleter).options[y].selected == true)
		{
			TrouveElement=-1;
			for(val=0;val<Tableau_InputACompleter_ValeurSelection.length;val++)
			{
				if(Tableau_InputACompleter_ValeurSelection[val]==document.getElementById(ListeACompleter).options[y].value){
					TrouveElement=val;
				}
				
			}
			if(Tableau_InputACompleter_QuantiteSelection[TrouveElement] == 1)
			{
				nouvel_element = new Option(document.getElementById(ListeACompleter).options[y].text,document.getElementById(ListeACompleter).options[y].value,false,false);
				document.getElementById(ListeACompleter).options[y] = null;
				Tableau_InputACompleter_ValeurSelection.splice(TrouveElement,1);
				Tableau_InputACompleter_QuantiteSelection.splice(TrouveElement,1);
			}
			else
			{
				Tableau_InputACompleter_QuantiteSelection[TrouveElement]=Tableau_InputACompleter_QuantiteSelection[TrouveElement]-1;
				QteModif=Tableau_InputACompleter_QuantiteSelection[TrouveElement];
				nouvel_element = new Option(document.getElementById(ListeACompleter).options[y].text.replace("Qty:"+(QteModif+1),"Qty:"+QteModif),document.getElementById(ListeACompleter).options[y].value,false,false);
				document.getElementById(ListeACompleter).options[y] = nouvel_element;
			}
		}
	}
	
	//On remet toutes les valeurs dans les inputs qui vont servir à écrire la request POST et compléter la BDD
	document.getElementById(InputACompleter_ValeurSelection).value=Tableau_InputACompleter_ValeurSelection.join('|');
	document.getElementById(InputACompleter_QuantiteSelection).value=Tableau_InputACompleter_QuantiteSelection.join('|');
}

Liste_PrestaPole = new Array();
Liste_Lieu= new Array();
Liste_Personne= new Array();
function RechargerPrestation(){
	var bTrouve = false;
	var selPresta="<select name='Id_PrestationPole' id='Id_PrestationPole' class='Id_PrestationPole' style='width:300px' onchange='RechargerLieu()'>";
	for(i=0;i<Liste_PrestaPole.length;i++){
		if (Liste_PrestaPole[i][4]==document.getElementById('Id_Plateforme').value){
			selPresta= selPresta + "<option value='"+Liste_PrestaPole[i][0]+"_"+Liste_PrestaPole[i][1];
			pole="";
			if(Liste_PrestaPole[i][1]!="0"){
				pole=" - "+Liste_PrestaPole[i][3];
			}
			selectedPresta="";
			selPresta= selPresta + "' "+selectedPresta+" >"+Liste_PrestaPole[i][2]+pole+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selPresta= selPresta + "<option value='0_0'></option>";}
	selPresta =selPresta + "</select>";
	document.getElementById('Id_PrestationPole').innerHTML=selPresta;
	RechargerLieu();
}
function RechargerLieu(){
	var bTrouve = false;
	var selPresta="<select name='Id_Lieu' id='Id_Lieu' class='Id_Lieu' style='width:200px'><option value='0'></option>";
	for(i=0;i<Liste_Lieu.length;i++){
		if (Liste_Lieu[i][1]+"_"+Liste_Lieu[i][2]==document.getElementById('Id_PrestationPole').value){
			selPresta= selPresta + "<option value='"+Liste_Lieu[i][0]+"' >"+Liste_Lieu[i][3]+pole+"</option>";
			bTrouve=true;
		}
	}
	selPresta =selPresta + "</select>";
	document.getElementById('Id_Lieu').innerHTML=selPresta;
}

function AfficherAffectation(affectation){
	if(affectation=="site"){
		var elements = document.getElementsByClassName('trPrestation');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="";          
		}
		var elements = document.getElementsByClassName('trPersonne');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="none";          
		}
		var elements = document.getElementsByClassName('trCaisse');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="none";          
		}
	}
	else if(affectation=="personne"){
		var elements = document.getElementsByClassName('trPrestation');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="none";          
		}
		var elements = document.getElementsByClassName('trPersonne');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="";          
		}
		var elements = document.getElementsByClassName('trCaisse');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="none";          
		}
	}
	else if(affectation=="caisse"){
		var elements = document.getElementsByClassName('trPrestation');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="none";          
		}
		var elements = document.getElementsByClassName('trPersonne');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="none";          
		}
		var elements = document.getElementsByClassName('trCaisse');
		for (var i = 0 ; i < elements.length ; i++) {
		  elements[i].style.display="";          
		}
	}
}
function RechargerCaisse(){
	var bTrouve = false;
	var selCaisse="<select name='Id_Caisse' id='Id_Caisse' class='Id_Caisse'>";
	for(i=0;i<Liste_Caisse.length;i++){
		if (Liste_Caisse[i][3]==document.getElementById('Id_PlateformeCaisse').value){
			selCaisse= selCaisse + "<option value='"+Liste_Caisse[i][0]+"'>"+Liste_Caisse[i][2]+" "+Liste_Caisse[i][1]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selCaisse= selCaisse + "<option value='0'></option>";}
	selCaisse =selCaisse + "</select>";
	document.getElementById('Id_Caisse').innerHTML=selCaisse;
}
function RechargerPersonne(){
	var bTrouve = false;
	var selPers="<select name='Id_Personne' id='Id_Personne' class='Id_Personne'>";
	for(i=0;i<Liste_Personne.length;i++){
		if (Liste_Personne[i][2]==document.getElementById('Id_PlateformePersonne').value || document.getElementById('Id_PlateformePersonne').value==0){
			selPers= selPers + "<option value='"+Liste_Personne[i][0]+"'>"+" "+Liste_Personne[i][1]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){selPers= selPers + "<option value='0'></option>";}
	selPers =selPers + "</select>";
	document.getElementById('Id_Personne').innerHTML=selPers;
}
function couleurLigne(id){
	var arrayCellules = document.getElementById(id).cells;
	var longueur = arrayCellules.length;
	var i=0; //on définit un incrémenteur qui représentera la clé
	while(i<longueur)
	{
		if(document.getElementById('checkOutils_'+id).checked==true){
			arrayCellules[i].style.backgroundColor = "#f1f82e";
			
		}
		else{
			arrayCellules[i].style.backgroundColor = document.getElementById(id).style.backgroundColor;
		}
		
		i++;
	}
}
function couleurLigneInventaire(id){
	var arrayCellules = document.getElementById(id).cells;
	var longueur = arrayCellules.length;
	var i=0; //on définit un incrémenteur qui représentera la clé
	while(i<longueur)
	{
		if(document.getElementById('checkOutils_'+id).checked==true){
			arrayCellules[i].style.backgroundColor = "#f1f82e";
			document.getElementById('checkMemeAffectation_'+id).checked=false;
			
		}
		else{
			arrayCellules[i].style.backgroundColor = document.getElementById(id).style.backgroundColor;
		}
		
		i++;
	}
}
function couleurLigneChangementMateriel(id){
	var arrayCellules = document.getElementById(id).cells;
	var longueur = arrayCellules.length;
	var i=0; //on définit un incrémenteur qui représentera la clé
	while(i<longueur)
	{
		if(document.getElementById('checkOutils_'+id).checked==true){
			arrayCellules[i].style.backgroundColor = "#f1f82e";
			if(document.getElementById('checkPersonne_'+id)){document.getElementById('checkPersonne_'+id).checked=false;}
			
		}
		else{
			arrayCellules[i].style.backgroundColor = document.getElementById(id).style.backgroundColor;
		}
		
		i++;
	}
}
function couleurLigne2(id){
	var arrayCellules = document.getElementById(id).cells;
	var longueur = arrayCellules.length;
	var i=0; //on définit un incrémenteur qui représentera la clé
	while(i<longueur)
	{
		if(document.getElementById('checkMemeAffectation_'+id).checked==true){
			arrayCellules[i].style.backgroundColor = "#2eadf8";
			if(document.getElementById('checkPersonne_'+id)){document.getElementById('checkOutils_'+id).checked=false;}
		}
		else{
			arrayCellules[i].style.backgroundColor = document.getElementById(id).style.backgroundColor;
		}
		
		i++;
	}
}

function couleurLigne3(id){
	var arrayCellules = document.getElementById(id).cells;
	var longueur = arrayCellules.length;
	var i=0; //on définit un incrémenteur qui représentera la clé
	while(i<longueur)
	{
		if(document.getElementById('checkPersonne_'+id).checked==true){
			arrayCellules[i].style.backgroundColor = "#2eadf8";
			if(document.getElementById('checkOutils_'+id)){document.getElementById('checkOutils_'+id).checked=false;}
		}
		else{
			arrayCellules[i].style.backgroundColor = document.getElementById(id).style.backgroundColor;
		}
		
		i++;
	}
}