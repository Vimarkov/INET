function OuvreFenetreAideperfos(IdPrestation,IdPole){
	var w=window.open("AidePERFOSNew.php?Id_Prestation="+IdPrestation+"&Id_Pole="+IdPole,"Aide","status=no,menubar=no,width=950,height=350,scrollbars=1");
	w.focus();
}

function FermerEtRecharger(Id_Prestation,Id_Pole,dateEnvoi)
		{
			opener.location.href="Liste_NewPERFOS.php?IdPrestationSelect="+Id_Prestation+"&IdPoleSelect="+Id_Pole+"&DateSelect="+dateEnvoi;
			window.close();
		}

	function ModifCouleur(lettre,couleur){
		if(couleur=='R'){
			document.getElementById('note'+lettre+'_J_1').value = '2';
			if(document.getElementById(lettre).style.backgroundColor != "rgb(255, 175, 174)" && document.getElementById("Idnew_perfos").value == '0'){
				CreerAction(lettre);
			}
			document.getElementById(lettre).style.backgroundColor = '#ffafae';
		}
		else{
			document.getElementById('note'+lettre+'_J_1').value = '1';
			if(document.getElementById(lettre).style.backgroundColor == "rgb(255, 175, 174)" && document.getElementById("Idnew_perfos").value == '0'){
				SupprimerAction(lettre);
			}
			document.getElementById(lettre).style.backgroundColor = '#e2fdbd';
		}
	}
	
	ListeCoordinateurProjet = new Array();
	Liste_Pole_Prestation = new Array();
	var resp_Action = "";
	var k = 0;
	Liste_Action = new Array();
	function CreerAction(laLettre) {
		var table = document.getElementById("tab_Actions");
		var row = table.insertRow();
		var laValeur="";
//Pour chaque lettre on calcule la variable k pour le bouton supprimer
		if(laLettre=="") {
			Liste_Action[k] = k+1;
			k = k + 1;
			row.id = 'ligne' + k;
			laValeur = k;
		} else {
			row.id = 'ligne' + laLettre;
			laValeur = laLettre;
		}

		var cellNiveau = row.insertCell(0);
		cellNiveau.align="center";
		var cellLettre = row.insertCell(1);
		cellLettre.align="center";
		var cellPointChaud = row.insertCell(2);
		cellPointChaud.align="center";
		var cellProbleme = row.insertCell(3);
		cellProbleme.align="center";
		var cellCommentaire = row.insertCell(4);
		cellCommentaire.align="center";
		var cellAction = row.insertCell(5);
		cellAction.align="center";
		var cellResponsable = row.insertCell(6);
		cellResponsable.align="center";
		var cellDelai = row.insertCell(7);
		cellDelai.align="center";
		var cellSuppr = row.insertCell(8);
		cellSuppr.align="center";
		
		//NIVEAU
		var elementNiveau = document.createElement("select");             
		elementNiveau.type = "text"; 
		var optionNiveau = document.createElement("option");
		optionNiveau.text = "1";
		optionNiveau.name = "1";
		elementNiveau.add(optionNiveau);
		var optionNiveau = document.createElement("option");
		optionNiveau.text = "2";
		optionNiveau.name = "2";
		elementNiveau.add(optionNiveau);
		elementNiveau.id="niveau"+laValeur;
		elementNiveau.name="niveau"+laValeur;
		elementNiveau.onchange = function(){AfficherPartAction(""+laValeur+"");};
		cellNiveau.appendChild(elementNiveau);
		
		
		//LETTRE
		var elementLettre = document.createElement("select");     
		elementLettre.id="lettre"+laValeur;
		elementLettre.name="lettre"+laValeur; 
		elementLettre.type = "text"; 
		var optionLettre = document.createElement("option");
		optionLettre.text = "S";
		optionLettre.name = "S";
		if(laLettre=="" || laLettre == "S"){elementLettre.add(optionLettre);}
		var optionLettre = document.createElement("option");
		optionLettre.text = "Q";
		optionLettre.name = "Q";
		if(laLettre=="" || laLettre == "Q"){elementLettre.add(optionLettre);}
		var optionLettre = document.createElement("option");
		optionLettre.text = "C";
		optionLettre.name = "C";
		if(laLettre=="" || laLettre == "C"){elementLettre.add(optionLettre);}
		var optionLettre = document.createElement("option");
		optionLettre.text = "D";
		optionLettre.name = "D";
		if(laLettre=="" || laLettre == "D"){elementLettre.add(optionLettre);}
		var optionLettre = document.createElement("option");
		optionLettre.text = "P";
		optionLettre.name = "P";
		if(laLettre=="" || laLettre == "P"){elementLettre.add(optionLettre);}
		var optionLettre = document.createElement("option");
		optionLettre.text = "F";
		optionLettre.name = "F";
		if(laLettre=="" || laLettre == "F"){elementLettre.add(optionLettre);}
		cellLettre.appendChild(elementLettre);
		
		//POINT CHAUD
		 
		var elementPointChaud = document.createElement("label");
		elementPointChaud.id="pc"+laValeur;
		elementPointChaud.name="pc";
		if (laLettre == "")
			elementPointChaud.innerHTML = "*";			
		cellPointChaud.appendChild(elementPointChaud);
		
		var elementProbleme = document.createElement("input");             
		elementProbleme.type = "text";
		elementProbleme.id="pb"+laValeur;
		elementProbleme.name="pb"+laValeur;
		elementProbleme.size = 40;
		cellProbleme.appendChild(elementProbleme);
		
		var elementCommentaire = document.createElement("input");             
		elementCommentaire.type = "text";
		elementCommentaire.id="commentaire"+laValeur;
		elementCommentaire.name="commentaire"+laValeur;
		elementCommentaire.size = 40;
		cellCommentaire.appendChild(elementCommentaire);
		
		var elementAction = document.createElement("input");             
		elementAction.type = "text"; 
		elementAction.id="action"+laValeur;
		elementAction.name="action"+laValeur;
		elementAction.size = 40;
		cellAction.appendChild(elementAction);
		
		var elementResp = document.createElement("label");             
		elementResp.id="resp"+laValeur;
		elementResp.innerHTML = resp_Action;
		cellResponsable.appendChild(elementResp);
		
		var elementDelai = document.createElement("input");   
		elementDelai.className = "pickDate";
		elementDelai.size = 9;
		elementDelai.id="delai"+laValeur;
		elementDelai.name="delai"+laValeur;
		cellDelai.appendChild(elementDelai);
		$('.pickDate').datepicker({dateFormat: 'yy-mm-dd' });
		
// Bouton de suppression de l'action crée		
		var elementSuppr = document.createElement("img");             
		elementSuppr.src = "../../Images/Suppression2.gif";
		
		var elementASuppr = document.createElement("a");
		elementASuppr.setAttribute('style', 'text-decoration:none');
		if(laLettre == "") {
			elementASuppr.setAttribute('href', "javascript:SupprimerAction('"+k+"')");
		}else{
			elementASuppr.setAttribute('href', "javascript:SupprimerAction('"+laLettre+"')");
		}
		elementASuppr.appendChild(elementSuppr);
		cellSuppr.appendChild(elementASuppr);
	
	}

function FeuVert(Id_Prestation,Id_Pole,dateEnvoi) {
	ModifCouleur('S','V');
	ModifCouleur('Q','V');
	ModifCouleur('C','V');
	ModifCouleur('D','V');
	ModifCouleur('P','V');
	ModifCouleur('F','V');

// Pour valider le SQCDPF	
//	if(VerifChamps())		
//		document.formSQCDPF.submitValider.click();
}	
	
	function SupprimerAction(laLettre) {
		try {
				var row = document.getElementById('ligne'+laLettre);
				delete Liste_Action[laLettre-1];
				row.parentNode.removeChild(row);
			} catch(err) {}		
	}

	function Recharge_Liste_Pole(){
		var i;
		var sel="";
		var isElement = false;
		sel ="&nbsp; Pôle : <select id='poles' size='1' name='pole'  onchange='Rechercher_PERFOS();'>";
		for(i=0;i<Liste_Pole_Prestation.length;i++){
			if (Liste_Pole_Prestation[i][1]==document.getElementById('prestation').value){
				sel= sel + "<option value="+Liste_Pole_Prestation[i][0];
				sel= sel + ">"+Liste_Pole_Prestation[i][2]+"</option>";
				isElement = true;
			}
		}
		if(isElement == false){sel= sel + "<option value='0' selected></option>";}
		sel =sel + "</select>";
		document.getElementById('pole').innerHTML=sel;
		
		var resp = "";
		for(i=0;i<ListeCoordinateurProjet.length;i++){
			if (ListeCoordinateurProjet[i][2]==document.getElementById('prestation').value && ListeCoordinateurProjet[i][3]==document.getElementById('poles').value){
				resp = resp + ListeCoordinateurProjet[i][0] + ' ' + ListeCoordinateurProjet[i][1] + ' <br> ';
			}
		}
		if(resp.length > 0){resp = resp.substr(0,resp.length-2);}
		
		Rechercher_PERFOS();
	}
	Liste_PERFOS = new Array();
	function Rechercher_PERFOS(){
		var i;
		var bTrouve;
		bTrouve=false;
		for(i=0;i<Liste_PERFOS.length;i++){
			var val="";
			if (Liste_PERFOS[i][1]==document.getElementById('prestation').value && Liste_PERFOS[i][2]==document.getElementById('poles').value && Liste_PERFOS[i][0]==document.getElementById('datePERFOS').value && Liste_PERFOS[i][3]==document.getElementById('vacation').value){
				bTrouve=true;
			}
		}
		if(bTrouve==false){
			val="<input class='Bouton' name='submitValider' type='submit' value='Valider'>";
		}
		else{
			val="<label>Ce SQCDPF existe déjà</label>";
		}
		document.getElementById('btnValider').innerHTML=val;
		aide="";
		if(document.getElementById('prestation').value!=0){
			aide="<a style='text-decoration:none;' href='javascript:OuvreFenetreAideperfos("+document.getElementById('prestation').value+","+document.getElementById('poles').value+")'>";
			aide+="<img src='../../Images/aide.gif' border='0' alt='Aide' title='Aide'></a>";
		}
		document.getElementById('aide').innerHTML=aide;
	}
	function AfficherPartAction(lettre){
		var i = 0;
		var niveau = document.getElementById('niveau'+lettre)
		if(niveau.value == "1"){
			document.getElementById('resp'+lettre).innerHTML = resp_Action;
		}
		else{
			var resp = "";
			for(i=0;i<ListeCoordinateurProjet.length;i++){
				if (ListeCoordinateurProjet[i][2]==document.getElementById('prestation').value && ListeCoordinateurProjet[i][3]==document.getElementById('poles').value){
					resp = resp + ListeCoordinateurProjet[i][0] + ' ' + ListeCoordinateurProjet[i][1] + ' <br> ';
				}
			}
			if(resp.length > 0){resp = resp.substr(0,resp.length-2);}
			document.getElementById('resp'+lettre).innerHTML = resp;
		}
	}
	
	function VerifChamps(){		
		if(document.getElementById('prestation').value=='0'){
			alert('Vous n\'avez pas renseigné la prestation.');
			return false;
		}
		
		if(document.getElementById('datePERFOS').value==''){
			alert('Vous n\'avez pas renseigné la date du SQCDPF.');
			return false;
		}
		
		if(document.getElementById('S').style.backgroundColor == ''){
			alert('Vous n\'avez pas renseigné la couleur du S.');
			return false;
		}				
		else if(document.getElementById('S').style.backgroundColor == 'rgb(255, 175, 174)' && document.getElementById("Idnew_perfos").value == '0'){
			if(document.getElementById('pbS').value == ''){
				alert('Vous n\'avez pas renseigné la description du problème de S.');
				return false;
			}
			if(document.getElementById('niveauS').value == '1'){
				if(document.getElementById('actionS').value == ''){
					alert('Vous n\'avez pas renseigné la description de l\'action de S.');
					return false;
				}
				if(document.getElementById('delaiS').value == ''){
					alert('Vous n\'avez pas renseigné le délai de S.');
					return false;
				}
			}
		}
		if(document.getElementById('Q').style.backgroundColor == ''){
			alert('Vous n\'avez pas renseigné la couleur du Q.');
			return false;
		}
		else if(document.getElementById('Q').style.backgroundColor == 'rgb(255, 175, 174)' && document.getElementById("Idnew_perfos").value == '0'){
			if(document.getElementById('pbQ').value == ''){
				alert('Vous n\'avez pas renseigné la description du problème de Q.');
				return false;
			}
			if(document.getElementById('niveauQ').value == '1'){
				if(document.getElementById('actionQ').value == ''){
					alert('Vous n\'avez pas renseigné la description de l\'action de Q.');
					return false;
				}
				if(document.getElementById('delaiQ').value == ''){
					alert('Vous n\'avez pas renseigné le délai de Q.');
					return false;
				}
			}
		}
		if(document.getElementById('C').style.backgroundColor == ''){
			alert('Vous n\'avez pas renseigné la couleur du C.');
			return false;
		}
		else if(document.getElementById('C').style.backgroundColor == 'rgb(255, 175, 174)' && document.getElementById("Idnew_perfos").value == '0'){
			if(document.getElementById('pbC').value == ''){
				alert('Vous n\'avez pas renseigné la description du problème de C.');
				return false;
			}
			if(document.getElementById('niveauC').value == '1'){
				if(document.getElementById('actionC').value == ''){
					alert('Vous n\'avez pas renseigné la description de l\'action de C.');
					return false;
				}
				if(document.getElementById('delaiC').value == ''){
					alert('Vous n\'avez pas renseigné le délai de C.');
					return false;
				}
			}
		}
		if(document.getElementById('D').style.backgroundColor == ''){
			alert('Vous n\'avez pas renseigné la couleur du D.');
			return false;
		}
		else if(document.getElementById('D').style.backgroundColor == 'rgb(255, 175, 174)' && document.getElementById("Idnew_perfos").value == '0'){
			if(document.getElementById('pbD').value == ''){
				alert('Vous n\'avez pas renseigné la description du problème de D.');
				return false;
			}
			if(document.getElementById('niveauD').value == '1'){
				if(document.getElementById('actionD').value == ''){
					alert('Vous n\'avez pas renseigné la description de l\'action de D.');
					return false;
				}
				if(document.getElementById('delaiD').value == ''){
					alert('Vous n\'avez pas renseigné le délai de D.');
					return false;
				}
			}
		}
		if(document.getElementById('P').style.backgroundColor == ''){
			alert('Vous n\'avez pas renseigné la couleur du P.');
			return false;
		}
		else if(document.getElementById('P').style.backgroundColor == 'rgb(255, 175, 174)' && document.getElementById("Idnew_perfos").value == '0'){
			if(document.getElementById('pbP').value == ''){
				alert('Vous n\'avez pas renseigné la description du problème de P.');
				return false;
			}
			if(document.getElementById('niveauP').value == '1'){
				if(document.getElementById('actionP').value == ''){
					alert('Vous n\'avez pas renseigné la description de l\'action de P.');
					return false;
				}
				if(document.getElementById('delaiP').value == ''){
					alert('Vous n\'avez pas renseigné le délai de P.');
					return false;
				}
			}
		}
		if(document.getElementById('F').style.backgroundColor == ''){
			alert('Vous n\'avez pas renseigné la couleur du F.');
			return false;
		}
		else if(document.getElementById('F').style.backgroundColor == 'rgb(255, 175, 174)' && document.getElementById("Idnew_perfos").value == '0'){
			if(document.getElementById('pbF').value == ''){
				alert('Vous n\'avez pas renseigné la description du problème de F.');
				return false;
			}
			if(document.getElementById('niveauF').value == '1'){
				if(document.getElementById('actionF').value == ''){
					alert('Vous n\'avez pas renseigné la description de l\'action de F.');
					return false;
				}
				if(document.getElementById('delaiF').value == ''){
					alert('Vous n\'avez pas renseigné le délai de F.');
					return false;
				}
			}
		}
		document.getElementById('actionSupp').value ="";
		if(document.getElementById("Idnew_perfos").value == '0'){
			for (var l in Liste_Action){
				if(document.getElementById('pb'+Liste_Action[l]).value == ''){
					alert('Vous n\'avez pas renseigné la description du problème.');
					return false;
				}
				if(document.getElementById('niveau'+Liste_Action[l]).value == '1'){
					if(document.getElementById('action'+Liste_Action[l]).value == ''){
						alert('Vous n\'avez pas renseigné la description de l\'action.');
						return false;
					}
					if(document.getElementById('delai'+Liste_Action[l]).value == ''){
						alert('Vous n\'avez pas renseigné le délai.');
						return false;
					}
				}
				document.getElementById('actionSupp').value=document.getElementById('actionSupp').value + Liste_Action[l] +';';
			}
		}
		return true;
	}