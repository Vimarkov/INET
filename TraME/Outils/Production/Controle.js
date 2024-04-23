function FermerEtRecharger(){
	window.opener.location = "Production.php";
	window.close();
}
function datepick() {
	if (!Modernizr.inputtypes['date']) {
		$('input[type=date]').datepicker({
			dateFormat: 'dd/mm/yy'
		});
	}
}
function afficherIMG(img){
	var w=open("",'image','weigth=toolbar=no,scrollbars=no,resizable=yes, width=810, height=310');	
	w.document.write("<HTML><BODY onblur=\"window.close();\"><IMG src='ImagesChecklist/"+img+"'>");
	w.document.write("</BODY></HTML>");
	w.focus();
	w.document.close();
}
function Excel(Id_TE,Id,Version){
	var w=window.open("Extract_CLRemplie.php?Id_TE="+Id_TE+"&Id="+Id+"&Version="+Version,"PageExcel","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function ToutOK(){
	var inputs = document.getElementsByTagName('INPUT');
	for(l=0;l<inputs.length;l++){
		if(inputs[l].type == "radio") {
			if(inputs[l].value=="OK"){
				inputs[l].checked = true;
			}
		}
	}
}
function VerifChamps(langue){
	//Vérifier que tous les radios bouton sont cochés (la moitié doit être coché) et si KO coché alors commentaire obligatoire
	if(document.getElementById('boutonClick').value==''){
		var inputs = document.getElementsByTagName('INPUT');
		var nbTotal=0;
		var nbCheck=0;
		bComment = true;
		for(l=0;l<inputs.length;l++){
			if(inputs[l].type == "radio"){
				nbTotal=nbTotal+1;
				if(inputs[l].checked==true){
					nbCheck=nbCheck+1;
					if(inputs[l].value=="KO"){
						if(document.getElementById(inputs[l].id+"_Commentaire").value==""){bComment=false;}
					}
				}
			}
		}
		if(nbCheck==(nbTotal/3)){
			if(bComment==false){
				if(langue=="EN"){
					alert('You have not filled in all the comments for the KO statuses.');
				}
				else{
					alert('Vous n\'avez pas renseigné tous les commentaires pour les statuts KO.');
				}
				return false;
			}
			return true;
		}
		else{
			if(langue=="EN"){
				alert('You have not checked all controls.');
			}
			else{
				alert('Vous n\'avez pas coché tous les contrôles.');
			}
			return false;
		}
	}
	else{
		return true;
	}
}

function VerifierLaPriseEnCompte(){
	var inputs = document.getElementsByTagName('INPUT');
	var nbTotal=0;
	var nbCheck=0;
	comment = "";
	document.getElementById('lesCommentaires').innerHTML="";
	for(l=0;l<inputs.length;l++){
		if(inputs[l].type == "radio"){
			if(inputs[l].value=="KO"){
				if(document.getElementById(inputs[l].id+"_Commentaire").value!=""){comment=comment+" "+document.getElementById(inputs[l].id+"_Commentaire").value+"<br>";}
			}
		}
	}
	
	if(comment!=""){
		document.getElementById('lesCommentaires').innerHTML=comment;
		document.getElementById('id_confrmdiv').style.display="block"; //this is the replace of this line
		document.getElementById('id_truebtn').onclick = function(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btn_recontrole' name='btn_recontrole' value='Recontrôle effectué'>";
			document.getElementById('div_recontrole').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btn_recontrole").dispatchEvent(evt);
			document.getElementById('div_recontrole').innerHTML="";
		   return true;
		};

		document.getElementById('id_falsebtn').onclick = function(){
			document.getElementById('id_confrmdiv').style.display="none";
		   return false;
		};
	}
}