Liste_Form = new Array();
function VerifChamps(langue){
	//Verifier existance du dossier
	bExiste=false;
	for(i=0;i<Liste_Form.length;i++){
		if (Liste_Form[i]==formulaire.libelle.value){
			bExiste = true;
		}
	}
	if(langue=="FR"){
		if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné la référence.');return false;}
		if(bExiste==true){alert('Cette formation existe déjà.');return false;}
	}
	else{
		if(formulaire.libelle.value==''){alert('You did not fill in the reference.');return false;}
		if(bExiste==true){alert('This training already exists.');return false;}
	}
	return true;
}

Liste_Docs = new Array();
function AjouterDocs(){
	if(document.getElementById('document').value!="0"){
		if(document.getElementById('lesDocs').value.indexOf(";"+document.getElementById('document').value+"Docs")==-1){
			document.getElementById('lesDocs').value = document.getElementById('lesDocs').value+";"+document.getElementById('document').value+"Docs";
			var table = document.getElementById("tab_Doc");
			var row = table.insertRow();
			row.id = document.getElementById('document').value+"_Doc";
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerDocs('"+document.getElementById('document').value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var Doc = "";
			for(i=0;i<Liste_Docs.length;i++){
				if (Liste_Docs[i][0]==document.getElementById('document').value){
					Doc = Liste_Docs[i][1]
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = Doc;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerDocs(Doc){
	var row = document.getElementById(Doc+"_Doc");
	row.parentNode.removeChild(row);
	document.getElementById('lesDocs').value = document.getElementById('lesDocs').value.replace(";"+Doc+"Docs","");
}

Liste_Categorie = new Array();
Liste_Qualif = new Array();
Liste_Langue = new Array();
Liste_QCM = new Array();
function Lister_Categories_Qualification(){
	var Div_Categorie_Qualification="";
	Div_Categorie_Qualification="<select id='categorie' name='categorie' style='width:200px;' onchange='Lister_Qualification();' onkeypress='if(event.keyCode == 13)AjouterQualif()'>";
	for(var i=0;i<Liste_Categorie.length;i++){
		if(Liste_Categorie[i][1]==document.getElementById('categorie_Maitre').value){
			Div_Categorie_Qualification=Div_Categorie_Qualification+"<option value="+Liste_Categorie[i][0]+">"+Liste_Categorie[i][2]+"</option>";
		}
	}
	Div_Categorie_Qualification=Div_Categorie_Qualification+"</select>";
	document.getElementById('Categories_Qualification').innerHTML=Div_Categorie_Qualification;
	Lister_Qualification();
}
function Lister_Qualification(){
	var Div_Qualification="";
	var bTrouve=0;
	Div_Qualification="<select id='qualification' name='qualification' style='width:300px;' onkeypress='if(event.keyCode == 13)AjouterQualif()'>";
	for(var i=0;i<Liste_Qualif.length;i++)
	{
		if(Liste_Qualif[i][4]==document.getElementById('categorie').value){
			var bTrouve=1;
			Div_Qualification=Div_Qualification+"<option value="+Liste_Qualif[i][0]+">"+Liste_Qualif[i][1]+"</option>";
		}
	}
	if(bTrouve==0){Div_Qualification= Div_Qualification + "<option value='0' selected></option>";}
	Div_Qualification=Div_Qualification+"</select>";
	document.getElementById('Qualifications').innerHTML=Div_Qualification;
}

function AjouterQualif(){
	if(document.getElementById('qualification').value!="0"){
		if(document.getElementById('lesFormsCompetences').value==""){
			var elements = document.getElementsByClassName("checkQCM");
			var nbQCM=0;
			for(var i=0, l=elements.length; i<l; i++){
				if(elements[i].checked == true){		
					nbQCM++;
					QCM=elements[i].value+"*"+document.getElementById('qcmlangue_'+elements[i].value).value+"!";
					if(document.getElementById('lesQualifs').value.indexOf(";"+document.getElementById('qualification').value+"_0_"+QCM)==-1 && document.getElementById('lesQualifs').value.indexOf(";"+document.getElementById('qualification').value+"_1_"+QCM)==-1){
						QCMAffichage="";
						NomQCM="";
						LangueQCM="";
						for(k=0;k<Liste_QCM.length;k++){
							if (Liste_QCM[k][0]==elements[i].value){
								NomQCM = Liste_QCM[k][1];
							}
						}
						for(k=0;k<Liste_Langue.length;k++){
							if (Liste_Langue[k][0]==document.getElementById('qcmlangue_'+elements[i].value).value){
								LangueQCM = Liste_Langue[k][1];
							}
						}
						QCMAffichage=NomQCM+" ("+LangueQCM+")<br>";
						document.getElementById('lesQualifs').value = document.getElementById('lesQualifs').value+";"+document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_"+QCM;
						var table = document.getElementById("tab_Qualif");
						var row = table.insertRow();
						row.id = document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_"+QCM+"_Qualif";
						btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerQualif('"+document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_"+QCM+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
						var CategorieMaitre = "";
						var Categorie = "";
						var Qualif = "";
						var Langue = "";
						var Acquise= "";
						for(k=0;k<Liste_Qualif.length;k++){
							if (Liste_Qualif[k][0]==document.getElementById('qualification').value){
								Qualif = Liste_Qualif[k][1];
								Categorie = Liste_Qualif[k][2];
								CategorieMaitre = Liste_Qualif[k][3];
							}
						}
						if(document.getElementById('Langue').value=="FR"){Acquise="Oui";}
						else{Acquise="Yes";}
						if(document.getElementById('acquise').value==1){
							if(document.getElementById('Langue').value=="FR"){Acquise="Non";}
							else{Acquise="No";}
						}
						var cell = row.insertCell(0);
						cell.innerHTML = Acquise;
						cell.style.borderBottom = "1px dotted #000000";
						var cell = row.insertCell(1);
						cell.innerHTML = CategorieMaitre;
						cell.style.borderBottom = "1px dotted #000000";
						var cell = row.insertCell(2);
						cell.innerHTML = Categorie;
						cell.style.borderBottom = "1px dotted #000000";
						var cell = row.insertCell(3);
						cell.innerHTML = Qualif;
						cell.style.borderBottom = "1px dotted #000000";
						var cell = row.insertCell(4);
						cell.innerHTML = QCMAffichage;
						cell.style.borderBottom = "1px dotted #000000";
						var cell = row.insertCell(5);
						cell.innerHTML = btn;
						cell.style.borderBottom = "1px dotted #000000";
						
						//Supprimer si Qualif existe sans QCM 
					}
				}
			}
			for(var j=0, l=elements.length; j<l; j++){
				if(elements[j].checked == true){
					elements[j].checked = false;
				}
			}
			if(nbQCM==0){
				if(document.getElementById('lesQualifs').value.indexOf(";"+document.getElementById('qualification').value+"_0_")==-1 && document.getElementById('lesQualifs').value.indexOf(";"+document.getElementById('qualification').value+"_1_")==-1){
					document.getElementById('lesQualifs').value = document.getElementById('lesQualifs').value+";"+document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_";
					var table = document.getElementById("tab_Qualif");
					var row = table.insertRow();
					row.id = document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_"+"_Qualif";
					btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerQualif('"+document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_"+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					var CategorieMaitre = "";
					var Categorie = "";
					var Qualif = "";
					var Langue = "";
					var Acquise = "";
					for(k=0;k<Liste_Qualif.length;k++){
						if (Liste_Qualif[k][0]==document.getElementById('qualification').value){
							Qualif = Liste_Qualif[k][1];
							Categorie = Liste_Qualif[k][2];
							CategorieMaitre = Liste_Qualif[k][3];
						}
					}
					if(document.getElementById('Langue').value=="FR"){Acquise="Oui";}
					else{Acquise="Yes";}
					if(document.getElementById('acquise').value==1){
						if(document.getElementById('Langue').value=="FR"){Acquise="Non";}
						else{Acquise="No";}
					}
					var cell = row.insertCell(0);
					cell.innerHTML = Acquise;
					cell.style.borderBottom = "1px dotted #000000";
					var cell = row.insertCell(1);
					cell.innerHTML = CategorieMaitre;
					cell.style.borderBottom = "1px dotted #000000";
					var cell = row.insertCell(2);
					cell.innerHTML = Categorie;
					cell.style.borderBottom = "1px dotted #000000";
					var cell = row.insertCell(3);
					cell.innerHTML = Qualif;
					cell.style.borderBottom = "1px dotted #000000";
					var cell = row.insertCell(4);
					cell.innerHTML = "";
					cell.style.borderBottom = "1px dotted #000000";
					var cell = row.insertCell(5);
					cell.innerHTML = btn;
					cell.style.borderBottom = "1px dotted #000000";
				}
			}
			else{
				SupprimerQualif(document.getElementById('qualification').value+"_"+document.getElementById('acquise').value+"_");
			}

		}
		else{
			if(document.getElementById('Langue').value=="FR"){
				alert("Impossible ! Des formations sans qualifications sont encore liées.");
			}
			else{
				alert("Impossible ! Training without qualifications is still linked.");
			}
		}		
	}
}
function SupprimerQualif(Qualif){
	var row = document.getElementById(Qualif+"_Qualif");
	row.parentNode.removeChild(row);
	document.getElementById('lesQualifs').value = document.getElementById('lesQualifs').value.replace(";"+Qualif,"");
}

function AfficherRecyclage(){
	var arrayLignes = document.getElementById("tab_Infos").rows;
	var longueur = arrayLignes.length;
	var i=0;
	var j=0;
	if(document.getElementById("Recyclage").value==1){
		while(i<longueur){
			cells = arrayLignes[i].cells;
			longueurCell = cells.length;
			j=0;
			while(j<longueurCell){
				if(cells[j].id.substr(0,4) == "td_L"){
					document.getElementById(cells[j].id).rowSpan = "2"; 
				}
				j++;
			}
			if(arrayLignes[i].id.substr(0,4) == "tr_R"){
				arrayLignes[i].style.display = ""; 
			}
			i++;
		}
	}
	else{
		while(i<longueur){
			cells = arrayLignes[i].cells;
			longueurCell = cells.length;
			j=0;
			while(j<longueurCell){
				if(cells[j].id.substr(0,4) == "td_L"){
					document.getElementById(cells[j].id).rowSpan = "1"; 
				}
				j++;
			}
			if(arrayLignes[i].id.substr(0,4) == "tr_R"){
				arrayLignes[i].style.display = "none"; 
			}
			i++;
		}
	}
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

/**
 * heure(champ)
 * 
 * Permet de formatter en champ (objet html) en heure.
 * 
 * @param champ objet html
 * @returns void
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */

function heure(champ) {
	var expression = new RegExp("[0-9]{2}:[0-9]{2}"); //Expression régulière à vérifier

	var cptChiffresTrouvés = 0; 
	var posSeparateurTrouvé = -1; //sous forme de position 
	var solution ="";				
	
	 // Renvoie vrai ou faux pour savoir si le schéma est correct
	if(expression.test(champ.value) && champ.value.length == 4) {
		//Le cas où la valeur saisie est déjà correcte
	}else {
		//Recupérer les 4 premiers chiffres trouvés et les mettre sous forme horraire		
		for(x = 0; x < champ.value.length; x++)
		{		
			//Trouve la position du séparateur si il existe
			if(/[:]/.test(champ.value.charAt(x)))
				posSeparateurTrouvé = x;
		}
		
		//Si on a trouvé un séparateur
		if (posSeparateurTrouvé >= 0) {
			var gauche = champ.value.substr(0,posSeparateurTrouvé);
			var droite = champ.value.substr(posSeparateurTrouvé+1,champ.value.length-(posSeparateurTrouvé+1));
			
			//Chercher la solution gauche si la longeur est < 2
			if(gauche.length < 2) {
				len = 2 - gauche.length; //Calcul de la longeur à écrire
				gauche = new Array(len + 1).join("0") + gauche; //Remplissage
			}
			
			//Chercher les 2 premiers chiffres de la partie droite
			var solutionDroite = "";
			
			for(y = 0; y < droite.length; y++) {
				if(/[0-9]/.test(droite.charAt(y)) && solutionDroite.length < 2) {
					solutionDroite += droite.charAt(y);
				}
			}
			solution = gauche + ":" + solutionDroite;
		} else {
			
			//Il n'y a pas de séparateur
			for(y = 0; y < champ.value.length; y++) {
				
				//Seulement si on trouve un chiffre et qu'il fait parti des 4 premiers
				if(/[0-9]/.test(champ.value.charAt(y)) && cptChiffresTrouvés < 4) {
					cptChiffresTrouvés++;
					//Ajout du caractère chiffré
					solution += champ.value.charAt(y);					
				}			
			}
			
			if (cptChiffresTrouvés == 1 ) 
				solution = "0" + solution + ":00";
			
			if (cptChiffresTrouvés >= 2 )
				solution = solution + ":00";

		}
		//Affectation de la solution
		champ.value = solution;
	}	
}

function chiffre(champ){
	var chiffres = new RegExp("[0-9]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
	var verif;
	var points = 0; /* Supprimer cette ligne */

	for(x = 0; x < champ.value.length; x++)
	{
	verif = chiffres.test(champ.value.charAt(x));
	if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
	if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
	}
}

function AfficherRecyclagePla(){
	var arrayLignes = document.getElementById("tab_Infos").rows;
	var longueur = arrayLignes.length;
	var i=0;
	var j=0;
	if(document.getElementById("Recyclage").value==1){
		document.getElementById("RecyclageCoutDuree1").style.display = "";
		document.getElementById("RecyclageCoutDuree2").style.display = "";
		document.getElementById("RecyclageCoutDuree3").style.display = "";
		document.getElementById("RecyclageCoutDuree4").style.display = "";
		document.getElementById("RecyclageCoutDuree5").style.display = "";
		document.getElementById("RecyclageCoutDuree6").style.display = "";
		document.getElementById("RecyclageCoutDuree7").style.display = "";
		document.getElementById("RecyclageCoutDuree8").style.display = "";
		document.getElementById("RecyclageCoutDuree9").style.display = "";
		document.getElementById("RecyclageCoutDuree10").style.display = "";
		while(i<longueur){
			cells = arrayLignes[i].cells;
			longueurCell = cells.length;
			j=0;
			while(j<longueurCell){
				if(cells[j].id.substr(0,4) == "td_L"){
					document.getElementById(cells[j].id).rowSpan = "2"; 
				}
				j++;
			}
			if(arrayLignes[i].id.substr(0,4) == "tr_R"){
				arrayLignes[i].style.display = ""; 
			}
			i++;
		}
	}
	else{
		document.getElementById("RecyclageCoutDuree1").style.display = "none";
		document.getElementById("RecyclageCoutDuree2").style.display = "none";
		document.getElementById("RecyclageCoutDuree3").style.display = "none";
		document.getElementById("RecyclageCoutDuree4").style.display = "none";
		document.getElementById("RecyclageCoutDuree5").style.display = "none";
		document.getElementById("RecyclageCoutDuree6").style.display = "none";
		document.getElementById("RecyclageCoutDuree7").style.display = "none";
		document.getElementById("RecyclageCoutDuree8").style.display = "none";
		document.getElementById("RecyclageCoutDuree9").style.display = "none";
		document.getElementById("RecyclageCoutDuree10").style.display = "none";
		while(i<longueur){
			cells = arrayLignes[i].cells;
			longueurCell = cells.length;
			j=0;
			while(j<longueurCell){
				if(cells[j].id.substr(0,4) == "td_L"){
					document.getElementById(cells[j].id).rowSpan = "1"; 
				}
				j++;
			}
			if(arrayLignes[i].id.substr(0,4) == "tr_R"){
				arrayLignes[i].style.display = "none"; 
			}
			i++;
		}
	}
	AfficherLanguePla();
}

function AfficherLanguePla(){
	var arrayLignes = document.getElementById("tab_Infos").rows;
	var longueur = arrayLignes.length;
	var i=0;
	var j=0;

	while(i<longueur){
		if(document.getElementById("Recyclage").value==1){
			if(arrayLignes[i].id.substr(0,4) == "tr_L" || arrayLignes[i].id.substr(0,4) == "tr_R"){
				if(arrayLignes[i].id == "tr_L_"+document.getElementById("LangueAffichage").value || arrayLignes[i].id == "tr_R_"+document.getElementById("LangueAffichage").value){
					arrayLignes[i].style.display = ""; 
				}
				else{
					arrayLignes[i].style.display = "none"; 
				}
			}
		}
		else{
			if(arrayLignes[i].id.substr(0,4) == "tr_L"){
				if(arrayLignes[i].id == "tr_L_"+document.getElementById("LangueAffichage").value){
					arrayLignes[i].style.display = ""; 
				}
				else{
					arrayLignes[i].style.display = "none"; 
				}
			}

		}
		i++;
	}
}

Liste_FormCompetence = new Array();
function AjouterFormation(){
	if(document.getElementById('formationProfil').value!="0"){
		if(document.getElementById('lesFormsCompetences').value.indexOf(";"+document.getElementById('formationProfil').value+"FormCompetence")==-1){
			document.getElementById('lesFormsCompetences').value = document.getElementById('lesFormsCompetences').value+";"+document.getElementById('formationProfil').value+"FormCompetence";
			var table = document.getElementById("tab_FormCompetences");
			var row = table.insertRow();
			row.id = document.getElementById('formationProfil').value+"_FormCompetence";
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerFormation('"+document.getElementById('formationProfil').value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var Form = "";
			for(i=0;i<Liste_FormCompetence.length;i++){
				if (Liste_FormCompetence[i][0]==document.getElementById('formationProfil').value){
					Form = Liste_FormCompetence[i][1]
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = Form;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerFormation(Form){
	var row = document.getElementById(Form+"_FormCompetence");
	row.parentNode.removeChild(row);
	document.getElementById('lesFormsCompetences').value = document.getElementById('lesFormsCompetences').value.replace(";"+Form+"FormCompetence","");
}

Liste_FormQualipso = new Array();
function AjouterFormationQUALIPSO(){
	if(document.getElementById('formationQUALIPSOProfil').value!="0"){
		if(document.getElementById('lesFormsQUALIPSO').value.indexOf(";"+document.getElementById('formationQUALIPSOProfil').value+"FormQualipso")==-1){
			document.getElementById('lesFormsQUALIPSO').value = document.getElementById('lesFormsQUALIPSO').value+";"+document.getElementById('formationQUALIPSOProfil').value+"FormQualipso";
			var table = document.getElementById("tab_FormQualipso");
			var row = table.insertRow();
			row.id = document.getElementById('formationQUALIPSOProfil').value+"_FormQualipso";
			btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerFormationQualipso('"+document.getElementById('formationQUALIPSOProfil').value+"')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			var Form = "";
			for(i=0;i<Liste_FormQualipso.length;i++){
				if (Liste_FormQualipso[i][0]==document.getElementById('formationQUALIPSOProfil').value){
					Form = Liste_FormQualipso[i][1]
				}
			}
			var cell = row.insertCell(0);
			cell.innerHTML = Form;
			var cell = row.insertCell(1);
			cell.innerHTML = btn;
		}
	}
}
function SupprimerFormationQualipso(Form){
	var row = document.getElementById(Form+"_FormQualipso");
	row.parentNode.removeChild(row);
	document.getElementById('lesFormsQUALIPSO').value = document.getElementById('lesFormsQUALIPSO').value.replace(";"+Form+"FormQualipso","");
}
