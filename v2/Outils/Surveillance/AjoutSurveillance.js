function VerifChamps()
{
	if(formulaire.DatePlanif.value=='' || formulaire.DatePlanif.value <= '0001-01-01'){alert('Vous n\'avez pas sélectionné de date.');return false;}
	return true;
}

function FermerEtRecharger()
{
	opener.location.reload();
	window.close();
}

function Change_Type()
{
	Recharge_Liste_Questionnaire();
}

Liste_Plateforme_Prestation = new Array();
Liste_Plateforme_Personne = new Array();
function Recharge_Liste_Prestation_Personne()
{
	var i;
	var sel="";
	var sel1="";
	var sel2="";
	sel ="<select size='1' name='Id_Prestation'>";
	sel1 ="<select size='1' name='Id_Surveillant'>";
	sel2 ="<select size='1' name='Id_Surveille'>";
	
	for(i=0;i<Liste_Plateforme_Prestation.length;i++)
	{
		if (Liste_Plateforme_Prestation[i][1]==document.getElementById('Id_Plateforme').value)
		{
			sel= sel + "<option value="+Liste_Plateforme_Prestation[i][0];
			if(Liste_Plateforme_Prestation[i][0]==document.getElementById('Id_Prestation_Initiale').value){sel = sel + " selected";}
			sel= sel + ">"+Liste_Plateforme_Prestation[i][2]+"</option>";
		}
	}
	
	for(i=0;i<Liste_Plateforme_Personne.length;i++)
	{
		if (Liste_Plateforme_Personne[i][1]==document.getElementById('Id_Plateforme').value || Liste_Plateforme_Personne[i][0]==document.getElementById('Id_Surveillant_Initial').value || Liste_Plateforme_Personne[i][0]==8033 || Liste_Plateforme_Personne[i][0]==1762 || Liste_Plateforme_Personne[i][0]==2526)
		{
			sel1= sel1 + "<option value="+Liste_Plateforme_Personne[i][0];
			if(Liste_Plateforme_Personne[i][0]==document.getElementById('Id_Surveillant_Initial').value){sel1 = sel1 + " selected";}
			sel1= sel1 + ">"+Liste_Plateforme_Personne[i][2]+"</option>";
		}
	}
	for(i=0;i<Liste_Plateforme_Personne.length;i++)
	{
		if (Liste_Plateforme_Personne[i][1]==document.getElementById('Id_Plateforme').value || Liste_Plateforme_Personne[i][0]==document.getElementById('Id_Surveille_Initial').value)
		{
			sel2= sel2 + "<option value="+Liste_Plateforme_Personne[i][0];
			if(Liste_Plateforme_Personne[i][0]==document.getElementById('Id_Surveille_Initial').value){sel2 = sel2 + " selected";}
			sel2= sel2 + ">"+Liste_Plateforme_Personne[i][2]+"</option>";
		}
	}
	sel =sel + "</select>";
	sel1 =sel1 + "</select>";
	sel2 =sel2 + "</select>";
	document.getElementById('Prestation').innerHTML=sel;
	document.getElementById('Surveillant').innerHTML=
	;
	document.getElementById('Surveille').innerHTML=sel2;
}

Liste_Questionnaire_Theme_Plateforme = new Array();
function Recharge_Liste_Questionnaire()
{
	Recharge_Liste_Prestation_Personne();
	var sel="";
	sel ="<select size='1' name='Id_Questionnaire'>";
	if(document.getElementById('Type').value == "Générique")
	{
		for(var i=0;i<Liste_Questionnaire_Theme_Plateforme.length;i++)
		{
			if (Liste_Questionnaire_Theme_Plateforme[i][1]=='0' && Liste_Questionnaire_Theme_Plateforme[i][2]==document.getElementById('Id_Theme_Questionnaire').value)
			{
				sel= sel + "<option value="+Liste_Questionnaire_Theme_Plateforme[i][0];
				if(Liste_Questionnaire_Theme_Plateforme[i][0]==document.getElementById('Id_Questionnaire_Initial').value){sel = sel + " selected";}
				sel= sel + ">"+Liste_Questionnaire_Theme_Plateforme[i][3]+"</option>";
			}
		}
	}
	else
	{
		for(var i=0;i<Liste_Questionnaire_Theme_Plateforme.length;i++)
		{
			if (Liste_Questionnaire_Theme_Plateforme[i][1]!='0' && Liste_Questionnaire_Theme_Plateforme[i][2]==document.getElementById('Id_Theme_Questionnaire').value)
			{
				sel= sel + "<option value="+Liste_Questionnaire_Theme_Plateforme[i][0];
				if(Liste_Questionnaire_Theme_Plateforme[i][0]==document.getElementById('Id_Questionnaire_Initial').value){sel = sel + " selected";}
				sel= sel + ">"+Liste_Questionnaire_Theme_Plateforme[i][3]+"</option>";
			}
		}			
	}
	sel =sel + "</select>";
	document.getElementById('Questionnaire').innerHTML=sel;
}