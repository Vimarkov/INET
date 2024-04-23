function Ajouter(){
	if(document.getElementById('visite').value!="" && document.getElementById('dateVisite').value!=""){
		var tabVisite = document.getElementById('visite').value.split(";");
		if(document.getElementById('visites').value.indexOf(tabVisite[0]+"_")==-1){
			var idVisites = tabVisite[0]+"_"+document.querySelector('input[name="question1"]:checked').value+"_"+document.querySelector('input[name="question2"]:checked').value+"_"+document.querySelector('input[name="question3"]:checked').value+"_"+document.querySelector('input[name="question4"]:checked').value+"_"+document.getElementById('commentaire1').value+"_"+document.getElementById('commentaire2').value+"_"+document.getElementById('commentaire3').value+"_"+document.getElementById('commentaire4').value+"_"+document.getElementById('dateVisite').value+";";
			var idVisites2 = tabVisite[0]+"_"+document.querySelector('input[name="question1"]:checked').value+"_"+document.querySelector('input[name="question2"]:checked').value+"_"+document.querySelector('input[name="question3"]:checked').value+"_"+document.querySelector('input[name="question4"]:checked').value+"_"+document.getElementById('commentaire1').value.replace("'","\\'")+"_"+document.getElementById('commentaire2').value.replace("'","\\'")+"_"+document.getElementById('commentaire3').value.replace("'","\\'")+"_"+document.getElementById('commentaire4').value.replace("'","\\'")+"_"+document.getElementById('dateVisite').value+";";
			document.getElementById('visites').value = document.getElementById('visites').value+idVisites;
			var table = document.getElementById("tab_Visite");
			//Ligne 1 visite
			var row = table.insertRow();
			row.id = idVisites+"_1";
			var cell = row.insertCell(0);
			cell.col
			cell.innerHTML = tabVisite[1]+"&nbsp;&nbsp;("+document.getElementById('dateVisite').value+")";
			cell.style.fontWeight = "bold";
			cell.setAttribute('colspan','6');
			cell.setAttribute('bgcolor','#a0d8d4');
			
			btn="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('"+idVisites2+"')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
			cell.setAttribute('bgcolor','#a0d8d4');
			
			//Ligne 2 résultats
			var row = table.insertRow();
			row.id = idVisites+"_2";
			var cell = row.insertCell(0);
			cell.setAttribute('valign','center');
			cell.style.borderBottom = "dotted 1px #000000";
			cell.innerHTML = "1. Presentation planning<br>(communication,notification in due time...)";
			var cell = row.insertCell(1);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question1"]:checked').value=="Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(2);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question1"]:checked').value=="Somehow Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(3);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question1"]:checked').value=="Satisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(4);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question1"]:checked').value=="Totally Satisfied"){
				cell.innerHTML = "X";
			}
			
			var cell = row.insertCell(5);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			cell.innerHTML = document.getElementById('commentaire1').value;
			
			var cell = row.insertCell(6);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";

			//Ligne 3 résultats
			var row = table.insertRow();
			row.id = idVisites+"_3";
			var cell = row.insertCell(0);
			cell.setAttribute('valign','center');
			cell.style.borderBottom = "dotted 1px #000000";
			cell.innerHTML = "2. Zone readliness at the start of presentation ?(punctuality,cleanliness...)";
			var cell = row.insertCell(1);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question2"]:checked').value=="Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(2);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question2"]:checked').value=="Somehow Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(3);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question2"]:checked').value=="Satisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(4);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question2"]:checked').value=="Totally Satisfied"){
				cell.innerHTML = "X";
			}
			
			var cell = row.insertCell(5);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			cell.innerHTML = document.getElementById('commentaire2').value;
			
			var cell = row.insertCell(6);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			
			//Ligne 4 résultats
			var row = table.insertRow();
			row.id = idVisites+"_4";
			var cell = row.insertCell(0);
			cell.setAttribute('valign','center');
			cell.style.borderBottom = "dotted 1px #000000";
			cell.innerHTML = "3. Support provided by ATR team during your presentation?";
			var cell = row.insertCell(1);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question3"]:checked').value=="Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(2);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question3"]:checked').value=="Somehow Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(3);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question3"]:checked').value=="Satisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(4);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			if(document.querySelector('input[name="question3"]:checked').value=="Totally Satisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(5);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";
			cell.innerHTML = document.getElementById('commentaire3').value;
			
			var cell = row.insertCell(6);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "dotted 1px #000000";

			//Ligne 5 résultats
			var row = table.insertRow();
			row.id = idVisites+"_5";
			var cell = row.insertCell(0);
			cell.setAttribute('valign','center');
			cell.style.borderBottom = "solid 1px #000000";
			cell.innerHTML = "4. Quality/Conformity of the area presented?";
			var cell = row.insertCell(1);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "solid 1px #000000";
			if(document.querySelector('input[name="question4"]:checked').value=="Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(2);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "solid 1px #000000";
			if(document.querySelector('input[name="question4"]:checked').value=="Somehow Dissatisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(3);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "solid 1px #000000";
			if(document.querySelector('input[name="question4"]:checked').value=="Satisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(4);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "solid 1px #000000";
			if(document.querySelector('input[name="question4"]:checked').value=="Totally Satisfied"){
				cell.innerHTML = "X";
			}
			var cell = row.insertCell(5);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "solid 1px #000000";
			cell.innerHTML = document.getElementById('commentaire4').value;
			
			var cell = row.insertCell(6);
			cell.setAttribute('valign','center');
			cell.setAttribute('align','center');
			cell.style.borderBottom = "solid 1px #000000";
		}
	}
}
function Supprimer(visite) {
	var row = document.getElementById(visite+'_1');
	row.parentNode.removeChild(row);
	var row = document.getElementById(visite+'_2');
	row.parentNode.removeChild(row);
	var row = document.getElementById(visite+'_3');
	row.parentNode.removeChild(row);
	var row = document.getElementById(visite+'_4');
	row.parentNode.removeChild(row);
	var row = document.getElementById(visite+'_5');
	row.parentNode.removeChild(row);
	document.getElementById('visites').value = document.getElementById('visites').value.replace(visite,"");
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
ListeMSN= new Array();
function VerifChamps(){
	if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
	//Verifier existance du msn
	bExiste=false;
	for(i=0;i<ListeMSN.length;i++){
		if (ListeMSN[i]==formulaire.msn.value){
			bExiste = true;
		}
	}
	if(bExiste==true){alert('Ce numéro de MSN existe déjà.');return false;}
	
	if(formulaire.dateMoulage.value==''){alert('Vous n\'avez pas renseigné la date de moulage.');return false;}
	if(formulaire.heureMoulage.value==''){alert('Vous n\'avez pas renseigné l\'heure de moulage.');return false;}
	return true;
}