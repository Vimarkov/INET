function OuvreFenetreModif(Mode,Id,BesoinRaison)
{
	Confirm=false;
	if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
	if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
	{
		if(Mode=="Suppr")
		{
			if(BesoinRaison==0){var w= window.open("Ajout_Besoin_Formation.php?Mode="+Mode+"&Id="+Id,"PageBesoinFormation","status=no,menubar=no,width=620,height=450");}
			else{var w= window.open("Supprimer_Besoin_Raison.php?Id="+Id,"PageBesoinFormation","status=no,menubar=no,width=620,height=200");}
		}
		else{var w= window.open("Ajout_Besoin_Formation.php?Mode="+Mode+"&Id="+Id,"PageBesoinFormation","status=no,menubar=no,width=620,height=450");}
		w.focus();
	}
}

function SelectionnerTout()
{
	var elements = document.getElementsByClassName("check");
	if (formulaire.selectAll.checked == true)
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
	}
	else
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
	}
}
tabPresta = new Array();
function SelectionnerToutPresta()
{
	var elements = document.getElementsByClassName("checkPresta");
	if (formulaire.selectAllPresta.checked == true)
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
	}
	else
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
	}
}
function SelectionnerToutUER()
{
	var elements = document.getElementsByClassName("checkUER");
	if (formulaire.selectAllUER.checked == true)
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
	}
	else
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
	}
	
	Selectionner("Presta",-1);
}
function Selectionner(Champ,Valeur){
	var elements = document.getElementsByClassName("check"+Champ);
	for(var i=0, l=elements.length; i<l; i++){
		for(var k=0, m=tabPresta.length; k<m; k++){
			if(tabPresta[k][0]==elements[i].value){
				if(
					document.getElementById("plateforme"+tabPresta[k][1])!= null 
					&& tabPresta[k][1]==document.getElementById("plateforme"+tabPresta[k][1]).value
					&& document.getElementById("plateforme"+tabPresta[k][1]).checked == true
				){
					elements[i].checked =true;
				}
				else{
					elements[i].checked =false;
				}
			}
		}
	}
}

function ValiderCheck()
{
	var elements = document.getElementsByClassName("check");
	Id="";
	ref="";
	for(var i=0, l=elements.length; i<l; i++)
	{
		if(elements[i].checked == true){Id+=elements[i].name+";";}
	}				
}

function SelectionnerToutPriseEC()
{
	var elements = document.getElementsByClassName("checkEC");
	if (formulaire.selectAllPriseEC.checked == true)
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
	}
	else
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
	}
}

function ValiderCheckEC()
{
	var elements = document.getElementsByClassName("checkEC");
	Id="";
	ref="";
	for(var i=0, l=elements.length; i<l; i++)
	{
		if(elements[i].checked == true){Id+=elements[i].name+";";}
	}				
}

function OuvreFenetreProfil(Mode,Id)
{
	var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
	w.focus();
}

function OuvreFenetreQCM(Id)
{
	var w= window.open("QCM_SansSession.php?&Id_Besoin="+Id,"PageQCM","status=no,menubar=no,scrollbars=yes,width=800,height=400");
	w.focus();
}
function InscrireSession(Id)
{
	var w = window.open("Liste_DateInscription.php?Id_Besoin="+Id,"PageInscription","status=no,menubar=no,width=700,height=500");
	w.focus();
}

function SelectionnerToutSuppr()
{
	var elements = document.getElementsByClassName("checkSuppr");
	if (formulaire.selectAllSuppr.checked == true)
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
	}
	else
	{
		for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
	}
}

function ValiderCheckSuppr()
{
	var elements = document.getElementsByClassName("checkSuppr");
	Id="";
	ref="";
	for(var i=0, l=elements.length; i<l; i++)
	{
		if(elements[i].checked == true){Id+=elements[i].name+";";}
	}				
}