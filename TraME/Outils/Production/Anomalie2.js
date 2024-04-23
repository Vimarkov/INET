function VerifChamps(langue){
	if(langue=="EN"){
		if(formulaire.reference.value==''){alert('You didn\'t enter the output data number.');return false;}
		if(formulaire.wp.value=='0'){alert('You didn\'t enter the workpackage.');return false;}
		if(formulaire.dateCreation.value==''){alert('You didn\'t enter the date of the anomaly.');return false;}
		if(formulaire.probleme.value==''){alert('You didn\'t enter the problem.');return false;}
		if(formulaire.actionCurative.value==''){alert('You didn\'t enter the solution.');return false;}
		if(formulaire.analyseCause.value==''){alert('You have not entered the root cause analysis.');return false;}
		if(formulaire.origine.value=='0'){alert('You didn\'t enter the origin.');return false;}
		if(formulaire.responsable.value=='0'){alert('You didn\'t enter the responsible.');return false;}
		if(formulaire.familleErreur1.value=='0'){alert('You didn\'t enter the error family 1.');return false;}
	}
	else{
		if(formulaire.reference.value==''){alert('Vous n\'avez pas renseigné le n° donnée de sortie.');return false;}
		if(formulaire.wp.value=='0'){alert('Vous n\'avez pas renseigné le workpackage.');return false;}
		if(formulaire.dateCreation.value==''){alert('Vous n\'avez pas renseigné la date de l\'anomalie.');return false;}
		if(formulaire.probleme.value==''){alert('Vous n\'avez pas renseigné le problème.');return false;}
		if(formulaire.actionCurative.value==''){alert('Vous n\'avez pas renseigné la solution.');return false;}
		if(formulaire.analyseCause.value==''){alert('Vous n\'avez pas renseigné l\'analyse des causes.');return false;}
		if(formulaire.origine.value=='0'){alert('Vous n\'avez pas renseigné l\'origine.');return false;}
		if(formulaire.responsable.value=='0'){alert('Vous n\'avez pas renseigné le responsable.');return false;}
		if(formulaire.familleErreur1.value=='0'){alert('Vous n\'avez pas renseigné la famille d\'erreur.');return false;}
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
		$.ajax({
			url : 'Ajax_AnomalieExistante.php',
			data : 'reference='+formulaire.reference.value,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				if(data=="<body>"){
					document.getElementById('existanceLivrable').style.color="#ff0000";
					document.getElementById('existanceLivrable').innerHTML=LivrableInexistant;
				}
				else{
					document.getElementById('existanceLivrable').style.color="#38d529";
					document.getElementById('existanceLivrable').innerHTML=LivrableExistant;
					Id_WP=data.substring(6);
					document.getElementById('wp').value=Id_WP;
				}
			}
		});
		
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
		$.ajax({
			url : 'Ajax_AnomalieExistante.php',
			data : 'reference='+formulaire.reference.value,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				
				},
			success:function(data){
				if(data=="<body>"){
					document.getElementById('existanceLivrable').style.color="#ff0000";
					document.getElementById('existanceLivrable').innerHTML=LivrableInexistant;
				}
				else{
					document.getElementById('existanceLivrable').style.color="#38d529";
					document.getElementById('existanceLivrable').innerHTML=LivrableExistant;
				}
			}
		});
		
	}
	else{
		document.getElementById('existanceLivrable').innerHTML="";
	}
}