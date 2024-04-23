function VerifChamps(langue){
	if(langue=="EN"){
		if(formulaire.reference.value==''){alert('You didn\'t enter the output data number.');return false;}
		if(formulaire.wp.value=='0'){alert('You didn\'t enter the workpackage.');return false;}
		if(formulaire.dateCreation.value==''){alert('You didn\'t enter the date of the anomaly.');return false;}
		if(formulaire.probleme.value==''){alert('You didn\'t enter the problem.');return false;}
		if(formulaire.actionCurative.value==''){alert('You didn\'t enter the solution.');return false;}
		if(formulaire.origine.value=='0'){alert('You didn\'t enter the origin.');return false;}
		if(formulaire.responsable.value=='0'){alert('You didn\'t enter the responsible.');return false;}
		if(formulaire.familleErreur1.value=='0'){alert('You didn\'t enter the error family 1.');return false;}
	}
	else{
		if(formulaire.reference.value==''){alert('Vous n\'avez pas renseign� le n� donn�e de sortie.');return false;}
		if(formulaire.wp.value=='0'){alert('Vous n\'avez pas renseign� le workpackage.');return false;}
		if(formulaire.dateCreation.value==''){alert('Vous n\'avez pas renseign� la date de l\'anomalie.');return false;}
		if(formulaire.probleme.value==''){alert('Vous n\'avez pas renseign� le probl�me.');return false;}
		if(formulaire.actionCurative.value==''){alert('Vous n\'avez pas renseign� la solution.');return false;}
		if(formulaire.origine.value=='0'){alert('Vous n\'avez pas renseign� l\'origine.');return false;}
		if(formulaire.responsable.value=='0'){alert('Vous n\'avez pas renseign� le responsable.');return false;}
		if(formulaire.familleErreur1.value=='0'){alert('Vous n\'avez pas renseign� la famille d\'erreur.');return false;}
	}
	return true;
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
Liste_TE= new Array();
function VerifExistance(langue){
	LivrableExistant="Livrable existant";
	LivrableInexistant="Livrable inexistant";
	if(langue=="EN"){
		LivrableExistant="Deliverable available";
		LivrableInexistant="Deliverable nonexistent";
	}
	if (document.getElementById('reference').value!=""){
		j=0;
		for(i=0;i<Liste_TE.length;i++){
			if (Liste_TE[i][1]==document.getElementById('reference').value){
				j++;
				document.getElementById('existanceLivrable').style.color="#38d529";
				document.getElementById('existanceLivrable').innerHTML=LivrableExistant;
				document.getElementById('wp').value=Liste_TE[i][2];
			}
		}
		if(j==0){
			document.getElementById('existanceLivrable').style.color="#ff0000";
			document.getElementById('existanceLivrable').innerHTML=LivrableInexistant;
		}
	}
	else{
		document.getElementById('existanceLivrable').innerHTML="";
	}
}
function VerifExistanceM(langue){
	LivrableExistant="Livrable existant";
	LivrableInexistant="Livrable inexistant";
	if(langue=="EN"){
		LivrableExistant="Deliverable available";
		LivrableInexistant="Deliverable nonexistent";
	}
	if (document.getElementById('reference').value!=""){
		j=0;
		for(i=0;i<Liste_TE.length;i++){
			if (Liste_TE[i][1]==document.getElementById('reference').value){
				j++;
				document.getElementById('existanceLivrable').style.color="#38d529";
				document.getElementById('existanceLivrable').innerHTML=LivrableExistant;
			}
		}
		if(j==0){
			document.getElementById('existanceLivrable').style.color="#ff0000";
			document.getElementById('existanceLivrable').innerHTML=LivrableInexistant;
		}
	}
	else{
		document.getElementById('existanceLivrable').innerHTML="";
	}
}