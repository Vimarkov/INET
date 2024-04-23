$(document).ready(function()
{
	$(".collapser").click(function()
	{
		var src = jQuery(this).find("img").attr('src');
		var endsrc = src.substring(src.length -6, src.length);
		if(endsrc == "us.gif")
		{
			jQuery(this).find("img").attr('src', "../../Images/Moins.gif");
			for( var i = 0; i < $(".autresCompetences").length; i++)
				if ($(".autresCompetences")[i].id == jQuery(this).find("img").attr('id'))
					$(".autresCompetences")[i].style.display='';
		}
		else
		{
			jQuery(this).find("img").attr('src', "../../Images/Plus.gif");
			for( var i = 0; i < $(".autresCompetences").length; i++)
				if ($(".autresCompetences")[i].id == jQuery(this).find("img").attr('id'))
					$(".autresCompetences")[i].style.display='none';
		}
	});
});

function OuvreFenetre(Page,Haut,Long)
	{window.open(Page,"PageModifProfilCompetences","status=no,menubar=no,width="+Long+",height="+Haut);}

function FermerEtRecharger()
{
	opener.location.reload();
	window.close();
}

function collapse()
{
	var elt = document.getElementById('collapser');
	var endsrc = elt.src.substring(elt.src.length -6, elt.src.length);
	if(endsrc == "us.gif") 
		elt.src = "../../Images/Moins.gif";
	else
		elt.src = "../../Images/Plus.gif";
}

function GetId(id)
	{return document.getElementById(id);}
	
function OuvrirFichier(Fic)
	{window.open("../../../Qualite/D/5/"+Fic+"-GRP-fr.pdf","PageFicheMetier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}

function Affiche_Masque(Table)
{
	var SourceImage = document.getElementById('Image_PlusMoins_'+Table).src;
	var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
	
	Table_TR = document.getElementById('Table_'+Table).getElementsByTagName('TR');
	if(result == "us.gif")
	{
		document.getElementById('Image_PlusMoins_'+Table).src="../../Images/Moins.gif";
		for(l=5;l<Table_TR.length+1;l++){
			if(Table_TR[l].id == "")
				Table_TR[l].style.display = 'table-row';
			}
	}
	else
	{
		document.getElementById('Image_PlusMoins_'+Table).src="../../Images/Plus.gif";
		for(l=5;l<Table_TR.length+1;l++){Table_TR[l].style.display = 'none';}
	}
}

function DemandeSuppression(question1,question2,question3,question4,debut1,debut2,fin1,fin2,Id_Prestation,Id_Personne,ModeProfil,Id_Pole)
{
	question="Voulez-vous également supprimer le planning \n"+question1+"\n"+question2+"\n"+question3+"\n"+question4+" ?";
	if(window.confirm(question))
	{
		window.location = "Supprime_Planning.php?Debut1="+debut1+"&Debut2="+debut2+"&Fin1="+fin1+"&Fin2="+fin2+"&Id_Prestation="+Id_Prestation+"&Id_Personne="+Id_Personne+"&ModeProfil="+ModeProfil+"&Id_Pole="+Id_Pole;
	}
}

function CocheCase(valeur)
{
	if(document.getElementById('check_'+valeur).checked==true)
	{
		var elements = document.getElementsByClassName('lesvaleurscochees');
		for (i=0; i<elements.length; i++){ elements[i].value+=valeur+";";}
	}
	else
	{
		var elements = document.getElementsByClassName('lesvaleurscochees');
		for (i=0; i<elements.length; i++){elements[i].value=elements[i].value.replace(valeur+";","");}
		document.getElementById('valeurCochee').value = document.getElementById('valeurCochee').value.replace(valeur+";","");
	}
}
function genererAttestation(Id){
	var w=window.open("../Formation/Generer_Attestation.php?Id="+Id,"PageAttestation","status=no,menubar=no,scrollbars=yes,width=90,height=90");
	w.focus();
}

function OuvrirDossier(Id_Relation){
	var w=window.open("../Formation/Consulter_DocumentsQualification.php?Id_Relation="+Id_Relation,"PageDossier","status=no,menubar=no,scrollbars=yes,width=800,height=500");
	w.focus();
}