Liste_Temps0 = new Array();
function OuvreFenetreAjout_Critere(){
	var w=window.open("Ajout_CritereVALID.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=250");
	w.focus();
}
function Suppr_Critere(critere,valeur){
	var w=window.open("Ajout_CritereVALID.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
	w.focus();
}
function OuvreFenetreLecture(Id){			
	var w=window.open("Ajout_Validation.php?Mode=M&Mode2=L&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=700");
	w.focus();
	}
function OuvreFenetreModif(Id){
	var elements = document.getElementsByClassName("check");
	Id="";
	for(var i=0, l=elements.length; i<l; i++){
		//Tu fais ce que tu veux avec l'élément parcouru
		if(elements[i].checked ==true){
			Id+=elements[i].name+";";
		}
	}
	if(Id!=""){
		var w=window.open("Valider_Tache.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=200");
		w.focus();
	}	
}
function Excel(){
	var w=window.open("Extract_Validation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}		
function OuvreFenetreValidation(Type,Langue){
	var elements = document.getElementsByClassName("check");
	Id="";
	ref="";
	for(var i=0, l=elements.length; i<l; i++){
		//Tu fais ce que tu veux avec l'élément parcouru
		if(elements[i].checked == true){
			Id+=elements[i].name+";";
			for(var j=0, m=Liste_Temps0.length; j<m; j++){
				if(Liste_Temps0[j][0]==elements[i].name){
					ref+="- "+Liste_Temps0[j][1]+"\n";
				}
			}
		}
	}
	if(Id!=""){
		if(Type=="V"){
			if(Liste_Temps0.length>0){
				if(Langue=="EN"){texte='Some deliverables have a time spent = 0.\nAre you sure you want to validate?\n'+ref;}
				else{texte='Certains livrables ont un temps passé=0.\nEtes-vous sûr de vouloir valider ?\n'+ref;}
			}
			else{
			if(Langue=="EN"){texte='Are you sure you want to validate?';}
			else{texte='Etes-vous sûr de vouloir valider ?';}
			}
			if(window.confirm(texte)){
				var w=window.open("Valider_Tache.php?Type=V&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=200");
				w.focus();
			}
		}
		else{
			var w=window.open("Valider_Tache.php?Type=R&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=200");
			w.focus();
		}
	}				
}
function SelectionnerTout(){
	var elements = document.getElementsByClassName("check");
	if (formulaire.selectAll.checked == true){
		for(var i=0, l=elements.length; i<l; i++){
			elements[i].checked = true;
		}
	}
	else{
		for(var i=0, l=elements.length; i<l; i++){
			elements[i].checked = false;
		}
	}
}
function OuvreFenetreC(Id,langue){
	if(langue=="EN"){texte='Are you sure you want to ask for control of this work at the manufacturing engineer ?';}
	else{texte='Etes-vous sûr de vouloir demander le contrôle de ce travail par le préparateur ?';}
	if(window.confirm(texte)){
		var w=window.open("ControleTravailAV.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
		w.focus();
	}
}
function OuvreFenetreAffichage(){
	var w=window.open("Modif_AffichageVAL.php","PageAffichage","status=no,menubar=no,scrollbars=yes,width=400,height=600");w.focus();}