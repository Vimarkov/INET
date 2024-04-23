function FermerEtRecharger(){
	opener.location.reload();
	window.close();
}
function Change_Note(){
	var i;
	var total = 0;
	var C = 0;
	var NA = 0;
	var lnote = 0;
	var idQuestion = "";
	table = document.getElementsByTagName('input');
	for (l=0;l<table.length;l++){
		if (table[l].type == 'radio'){
			idQuestion = table[l].name.substr(6);
			if(table[l].value == 'C' && table[l].checked == true){
				C++;
				total++;
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "";
			}
			else if(table[l].value == 'NA' && table[l].checked == true){
				NA++;
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "none";
			}
			else if(table[l].value == 'NC' && table[l].checked == true){
				total++;
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "";
			}
		}
	}
	if (total > 0){
		lnote = Math.round((C / total)*100);
		document.getElementById('note').value = lnote+"%" ;
		document.getElementById('score').value = lnote ;
	}
	else{
		document.getElementById('note').value = "100%";
		document.getElementById('score').value = 100 ;
	}
}
Liste_Plateforme_Prestation = new Array();
Liste_Plateforme_Personne = new Array();
function Recharge_Liste_Prestation_Personne(){
	var i;
	var sel="";
	var sel1="";
	var sel2="";
	sel ="<select size='1' name='Id_Prestation' style='width:300'>";
	sel1 ="<select size='1' name='Id_Surveillant'>";
	sel2 ="<select size='1' name='Id_Surveille'>";
	for(i=0;i<Liste_Plateforme_Prestation.length;i++){
		if (Liste_Plateforme_Prestation[i][1]==document.getElementById('Id_Plateforme').value){
			sel= sel + "<option value="+Liste_Plateforme_Prestation[i][0];
			sel= sel + ">"+Liste_Plateforme_Prestation[i][2]+"</option>";}
	}
	for(i=0;i<Liste_Plateforme_Personne.length;i++){
		if (Liste_Plateforme_Personne[i][1]==document.getElementById('Id_Plateforme').value || (Liste_Plateforme_Personne[i][0]==8033 && Liste_Plateforme_Personne[i][1]==17) || (Liste_Plateforme_Personne[i][0]==1762 && Liste_Plateforme_Personne[i][1]==17) || (Liste_Plateforme_Personne[i][0]==2526 && Liste_Plateforme_Personne[i][1]==17) || (Liste_Plateforme_Personne[i][0]==12529 && Liste_Plateforme_Personne[i][1]==17) || (Liste_Plateforme_Personne[i][0]==5618 && Liste_Plateforme_Personne[i][1]==17)){
			sel1= sel1 + "<option value="+Liste_Plateforme_Personne[i][0];
			sel2= sel2 + "<option value="+Liste_Plateforme_Personne[i][0];
			sel1= sel1 + ">"+Liste_Plateforme_Personne[i][2]+"</option>";
			sel2= sel2 + ">"+Liste_Plateforme_Personne[i][2]+"</option>";}
	}
	sel =sel + "</select>";
	sel1 =sel1 + "</select>";
	sel2 =sel2 + "</select>";
	document.getElementById('Prestation').innerHTML=sel;
	document.getElementById('Surveillant').innerHTML=sel1;
	document.getElementById('Surveille').innerHTML=sel2;
}
function nombre(champ)
{
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