
function OuvreFenetreSuppr(Menu,Id)
	{var w=window.open("Modif_Conges.php?Mode=S&Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value+"&Page=Liste_DemandeConges","PageConges","status=no,menubar=no,width=1000,height=550");
	w.focus();
	}
function OuvreFenetreRefus(Menu,Id,Step){
	if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to refuse?';}
	else{texte='Etes-vous sûr de vouloir refuser ?';}
	if(window.confirm(texte)){
		var w=window.open("Refuser_Conges.php?Id="+Id+"&Menu="+Menu+"&Step="+Step+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageConges","status=no,menubar=no,scrollbars=yes,width=800,height=300");
	}			
}
function OuvreFenetreValidation(Menu,Id,Step){
	var w=window.open("Valider_Conges.php?Id="+Id+"&Menu="+Menu+"&Step="+Step+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageConges","status=no,menubar=no,scrollbars=yes,width=800,height=300");			
}
function OuvreFenetreExcel(Menu)
	{window.open("Export_Conges.php?Menu="+document.getElementById('Menu').value,"PageExcel","status=no,menubar=no,width=900,height=450");}
function CocherPriseEnCompte(){
	if(document.getElementById('check_ValidePriseEnCompte').checked==true){
		var elements = document.getElementsByClassName('checkRH');
		for (i=0; i<elements.length; i++){
		  elements[i].checked=true;
		}
	}
	else{
		var elements = document.getElementsByClassName('checkRH');
		for (i=0; i<elements.length; i++){
		  elements[i].checked=false;
		}
	}
}
function CocherValide(){
	if(document.getElementById('check_Valide').checked==true){
		var elements = document.getElementsByClassName('check');
		for (i=0; i<elements.length; i++){
		  elements[i].checked=true;
		}
	}
	else{
		var elements = document.getElementsByClassName('check');
		for (i=0; i<elements.length; i++){
		  elements[i].checked=false;
		}
	}
}