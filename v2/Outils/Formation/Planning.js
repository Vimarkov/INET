function OuvreFenetreAjoutFormation()
{
	var w=window.open("Ajout_Session.php?Mode=A&Id=0&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&formateur="+document.getElementById('formateur').value+"&lieu="+document.getElementById('lieu').value+"&horaires="+document.getElementById('horaires').value+"&formation="+document.getElementById('formation').value+"&typeAffichage="+document.getElementById('typeAffichage').value+"&etatAffichage="+document.getElementById('etatAffichage').value,"PageSession","status=no,menubar=no,scrollbars=yes,width=1000,height=800");
	w.focus();
}

function OuvreFenetreAjoutGroupeFormation()
{
	var w=window.open("Ajout_SessionGroupe.php?Mode=A&Id=0&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&formateur="+document.getElementById('formateur').value+"&lieu="+document.getElementById('lieu').value+"&horaires="+document.getElementById('horaires').value+"&formation="+document.getElementById('formation').value+"&typeAffichage="+document.getElementById('typeAffichage').value+"&etatAffichage="+document.getElementById('etatAffichage').value,"PageSession","status=no,menubar=no,scrollbars=yes,width=1100,height=800");
	w.focus();
}

function OuvreFenetreIndispoFormateur()
{
	var w=window.open("Ajout_Indisponibilite_Formateur.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&formateur="+document.getElementById('formateur').value+"&lieu="+document.getElementById('lieu').value+"&horaires="+document.getElementById('horaires').value+"&formation="+document.getElementById('formation').value+"&typeAffichage="+document.getElementById('typeAffichage').value+"&etatAffichage="+document.getElementById('etatAffichage').value,"PageSession","status=no,menubar=no,scrollbars=yes,width=600,height=200");
	w.focus();
}

function ModifierSession(Id)
{
	var w=window.open("Ajout_Session.php?Mode=M&Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&formateur="+document.getElementById('formateur').value+"&lieu="+document.getElementById('lieu').value+"&horaires="+document.getElementById('horaires').value+"&formation="+document.getElementById('formation').value+"&typeAffichage="+document.getElementById('typeAffichage').value+"&etatAffichage="+document.getElementById('etatAffichage').value,"PageSession","status=no,menubar=no,scrollbars=yes,width=900,height=600");
	w.focus();
}

function ModifierSessionGroupe(Id)
{
	var w=window.open("Modif_SessionGroupe.php?Mode=M&Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&formateur="+document.getElementById('formateur').value+"&lieu="+document.getElementById('lieu').value+"&horaires="+document.getElementById('horaires').value+"&formation="+document.getElementById('formation').value+"&typeAffichage="+document.getElementById('typeAffichage').value+"&etatAffichage="+document.getElementById('etatAffichage').value,"PageSession","status=no,menubar=no,scrollbars=yes,width=1100,height=800");
	w.focus();
}

function OuvreFenetrePlanningExport()
{
	var w=window.open("Planning_Extract.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&typeAffichage="+document.getElementById('typeAffichage').value+"&formateur="+document.getElementById('formateur').value+"&lieu="+document.getElementById('lieu').value+"&horaires="+document.getElementById('horaires').value+"&formation="+document.getElementById('formation').value+"&etat="+document.getElementById('etatAffichage').value+"&dateFin="+document.getElementById('DateDeFin').value,"PagePlanning","status=no,menubar=no,scrollbars=yes,width=90,height=60");
	w.focus();
}

function OuvreFenetrePlanningSiteExport()
{
	var w=window.open("PlanningSite_Extract.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&date="+document.getElementById('DateDeDebut').value+"&formation="+document.getElementById('formation').value+"&etat="+document.getElementById('etatAffichage').value+"&dateFin="+document.getElementById('DateDeFin').value,"PagePlanning","status=no,menubar=no,scrollbars=yes,width=90,height=60");
	w.focus();
}

function Surbrillance(Id,etat,Couleur)
{
	if(etat=='Over')
	{
		elements = document.getElementsByClassName("td_"+Id);
		for(var i=0; i<elements.length;i++)
		{
			if(Id.search("GR")>=0){elements[i].style.backgroundColor ="#59bbbd";}
			else{elements[i].style.backgroundColor ="#59bbbd";}
			elements[i].style.cursor="pointer";
		}
	}
	else
	{
		elements = document.getElementsByClassName("td_"+Id);
		for(var i=0; i<elements.length;i++)
		{
			elements[i].style.backgroundColor =Couleur;
		}
	}
}

function ContenuSession(Id)
{
	var w=window.open("Contenu_Session.php?Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value,"PageSession","status=no,menubar=no,scrollbars=yes,width=1400,height=700");
	w.focus();
}

function ContenuSessionAllege(Id)
{
	var w=window.open("Contenu_Session_Leger.php?Id="+Id,"PageSession","status=no,menubar=no,scrollbars=yes,width=1200,height=700");
	w.focus();
}
	
function OuvreFenetreBesoin(Id_TypeFormation,Id_Formation)
{
	var w= window.open("Ajout_Besoin_Formation.php?Mode=A&Id=0&Id_Formation="+Id_Formation+"&Id_TypeFormation="+Id_TypeFormation+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value,"PageBesoinFormation","status=no,menubar=no,width=620,height=450");
	w.focus();
}

function EmailConvocation(Id,champs)
{
	if(document.getElementById('Langue').value=="FR")
	{
		Confirm=window.confirm('Etes-vous sûr de vouloir envoyer la convocation sans les informations suivantes de renseignées : '+champs+' ?');
		
	}
	else
	{
		Confirm=window.confirm('Are you sure you want to send the invitation without the following information : '+champs+' ?');
	}
	if(Confirm==true)
	{
		window.open("Convocation_Formation.php?Page=Planning&Id="+Id+"&Id_Plateforme"+document.getElementById('Id_Plateforme').value,"Convocation_Formation","status=no,menubar=no,width=420,height=250");
	}
}

function InscrireSession(Id)
{
	var w = window.open("InscrireSessionAF_CE_CQP.php?Page=Planning&Id_Session="+Id+"&Id_Plateforme="+document.getElementById("Id_Plateforme").value,"PageSession","status=no,menubar=no,width=900,height=800");
	w.focus();
}

function InscrireSessionSite(Id)
{
	var w = window.open("InscrireSessionAF_CE_CQP.php?Page=PlanningSite&Id_Session="+Id+"&Id_Plateforme="+document.getElementById("Id_Plateforme").value,"PageSession","status=no,menubar=no,width=900,height=800");
	w.focus();
}

function RediffuserSession(Id)
{
	Confirm=false;
	if(document.getElementById('Langue').value=="FR")
	{
		Confirm=window.confirm('Etes-vous sûr de vouloir rediffuser cette session aux prestations ?');
	}
	else
	{
		Confirm=window.confirm('Are you sure you want to repost this session to activities?');
	}
	if(Confirm==true)
	{
		window.open("RediffuserSession.php?Id_Plateforme="+document.getElementById("Id_Plateforme").value+"&Id="+Id,"Rediffuser_Session","status=no,menubar=no,width=420,height=250");
	}
}

function EnvoyerMailRappel()
{
	if(document.getElementById('DateDebutRappel').value!="" && document.getElementById('DateFinRappel').value!="" && document.getElementById('typeFormationRappel').value!="0")
	{
		window.open("Rappel_PersonnelInscrit.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&DateDebutRappel="+document.getElementById('DateDebutRappel').value+"&DateFinRappel="+document.getElementById('DateFinRappel').value+"&TypeFormationRappel="+document.getElementById('typeFormationRappel').value,"RappelMail","status=no,menubar=no,width=420,height=250");
	}
}