function OuvreFenetreModif(Mode,Id)
{
	Confirm=false;
	if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous s\373r de vouloir supprimer ? Are you sure you want to delete ?');}
	if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
	{
		window.open("Ajout_Formation.php?Mode="+Mode+"&Id="+Id,"PageFormation","status=no,menubar=no,width=450,height=180");
	}
}

function OuvreFenetreModifAutre(Page,Mode,Id_Formation,Id,NomChampsBase,Largeur,Hauteur)
{
	Confirm=false;
	if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous s\373r de vouloir supprimer ? Are you sure you want to delete ?');}
	if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
	{
		window.open(Page+".php?Mode="+Mode+"&"+NomChampsBase+"="+Id_Formation+"&Id="+Id,Page,"status=no,menubar=no,width="+Largeur+",height="+Hauteur);
	}
}

function Lister_Dependances(TableGenerale,ValeursATrierDansInput,NomChamps,TailleChamps,DivAffichage,PageModif,Largeur,Hauteur,LibelleTableResultats)
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
						MilieuCode+="<td class='PetitCompetence'>"+nl2br(htmlEntities(Valeurs[k+1]))+"</td>";	//k+1 car les 2 premiers champs dans l'input des données sont les ID
						Largeur_Tableau_Total=Largeur_Tableau_Total+Tableau_TailleChamps[k+1];
					}
					MilieuCode+="<td>";
					MilieuCode+="<a class='Modif' href='javascript:OuvreFenetreModifAutre(\""+PageModif+"\",\"Modif\",\""+Valeurs[1]+"\",\""+Valeurs[0]+"\",\""+Tableau_NomChamps[0]+"\",\""+Largeur+"\",\""+Hauteur+"\");'>";
					MilieuCode+="<img src='../../Images/Modif.gif' style='border:0;' alt='Modification'>";
					MilieuCode+="</a>";
					MilieuCode+="</td>";
					MilieuCode+="<td>";
					MilieuCode+="<a class='Modif' href='javascript:OuvreFenetreModifAutre(\""+PageModif+"\",\"Suppr\",\""+Valeurs[1]+"\",\""+Valeurs[0]+"\",\""+Tableau_NomChamps[0]+"\",\""+Largeur+"\",\""+Hauteur+"\");'>";
					MilieuCode+="<img src='../../Images/Suppression.gif' style='border:0;' alt='Suppression'>";
					MilieuCode+="</a>";
					MilieuCode+="</td>";
					MilieuCode+="</tr>";
				}
 			}
		}
	}
	if(AfficherTableau)
	{
		DebutCode+="<table class='ProfilCompetence' style='width:"+Largeur_Tableau_Total+"px;'>";
		DebutCode+="<tr class='TitreSousPageCompetences'><td colspan="+(Tableau_NomChamps.length+1)+">"+LibelleTableResultats+"</td></tr>";
		DebutCode+="<tr>";
		for(var k=1;k<Tableau_NomChamps.length;k++)
		{
			DebutCode+="<td class='PetiteCategorieCompetence' style='width:"+Tableau_TailleChamps[k]+"px;'>"+Tableau_NomChamps[k]+"</td>";
		}
		DebutCode+="<td class='PetiteCategorieCompetence' colspan=2 align='right' width='20px'>";
		DebutCode+="<a class='Modif' href='javascript:OuvreFenetreModifAutre(\""+PageModif+"\",\"Ajout\",\""+Id+"\",\"0\",\""+Tableau_NomChamps[0]+"\",\""+Largeur+"\",\""+Hauteur+"\");'>";
		DebutCode+="<img src='../../Images/Ajout.gif' style='border:0;'>";
		DebutCode+="</a>";
		DebutCode+="</td>";
		DebutCode+="</tr>";
		FinCode="</table>";
		document.getElementById(DivAffichage).innerHTML=DebutCode+MilieuCode+FinCode;
		
	}
}

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function br2nl (str, replaceMode) {   
	
  var replaceStr = (replaceMode) ? "\n" : '';
  // Includes <br>, <BR>, <br />, </br>
  return str.replace(/<\s*\/?br\s*[\/]?>/gi, replaceStr);
}

function RecliqueRadioPageMere(TablePageMere)
{
	var Table = window.opener.document.getElementById(TablePageMere);
	var Input = Table.getElementsByTagName('input');
	
	for(var i=0;i<Input.length;i++)
	{
		if(Input[i].checked == true)
		{
			Input[i].click();
		}
	}
}

function Transferer_Liste(ListeChoix,ListeSelection){
	for(y=0;y<document.getElementById(ListeChoix).length;y++){
		if(document.getElementById(ListeChoix).options[y].selected == true){
			nouvel_element = new Option(document.getElementById(ListeChoix).options[y].text,document.getElementById(ListeChoix).options[y].value,false,false);
			document.getElementById(ListeSelection).options[document.getElementById(ListeSelection).length] = nouvel_element;
			document.getElementById(ListeChoix).options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById(ListeSelection);
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array();
		Liste[i][0]=Obj.options[i].text;
		Liste[i][1]=Obj.options[i].value;
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0];
		Obj.options[i].value=Liste[i][1];
	}
}
function heure(champ){
	var chiffres = new RegExp("[0-9\:]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
	var verif;
	var points = 0; /* Supprimer cette ligne */

	for(x = 0; x < champ.value.length; x++)
	{
	verif = chiffres.test(champ.value.charAt(x));
	if(champ.value.charAt(x) == ":"){points++;} /* Supprimer cette ligne */
	if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
	if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
	}
}