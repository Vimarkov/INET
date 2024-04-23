function AfficherQA(Id_Questionnaire,Num){
	document.getElementById('BtnPlus_'+Id_Questionnaire+'_'+Num).style.display='none';
	Num=Num+1;
	document.getElementById('Question_'+Id_Questionnaire+'_'+Num).style.display='';
}
function Afficher(Sens,IdQ,FuturIdQ){
	if(Sens=="G" || Sens=="D"){
		if(Sens=="D"){
			document.getElementById('QuestionnaireEnTete_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireFin_'+IdQ).style.display='none';
			document.getElementById('Questionnaire_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireAdditionnel_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireNote_'+IdQ).style.display='none';
			if(FuturIdQ>0){
				document.getElementById('QuestionnaireEnTete_'+FuturIdQ).style.display='';
				document.getElementById('QuestionnaireFin_'+FuturIdQ).style.display='';
				document.getElementById('Questionnaire_'+FuturIdQ).style.display='';
				if(document.getElementById('AutoriserQuestionsAdditionnelles_'+FuturIdQ).value==1){
					document.getElementById('QuestionnaireAdditionnel_'+FuturIdQ).style.display='';
				}
				document.getElementById('QuestionnaireNote_'+FuturIdQ).style.display='';
			}
			else{
				document.getElementById('resultat').style.display='';
			}
		}
		else{
			document.getElementById('QuestionnaireEnTete_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireFin_'+IdQ).style.display='none';
			document.getElementById('Questionnaire_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireAdditionnel_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireNote_'+IdQ).style.display='none';
			if(FuturIdQ>0){
				document.getElementById('QuestionnaireEnTete_'+FuturIdQ).style.display='';
				document.getElementById('QuestionnaireFin_'+FuturIdQ).style.display='';
				document.getElementById('Questionnaire_'+FuturIdQ).style.display='';
				if(document.getElementById('AutoriserQuestionsAdditionnelles_'+FuturIdQ).value==1){
					document.getElementById('QuestionnaireAdditionnel_'+FuturIdQ).style.display='';
				}
				document.getElementById('QuestionnaireNote_'+FuturIdQ).style.display='';
			}
		}
	}
	else if(Sens=="resultat"){
		document.getElementById('resultat').style.display='none';
		document.getElementById('QuestionnaireEnTete_'+FuturIdQ).style.display='';
		document.getElementById('QuestionnaireFin_'+FuturIdQ).style.display='';
		document.getElementById('Questionnaire_'+FuturIdQ).style.display='';
		if(document.getElementById('AutoriserQuestionsAdditionnelles_'+FuturIdQ).value==1){
			document.getElementById('QuestionnaireAdditionnel_'+FuturIdQ).style.display='';
		}
		document.getElementById('QuestionnaireNote_'+FuturIdQ).style.display='';
	}
}
function VerifChamps(){
	var idQuestion = "";
	var inputSignatures = document.getElementsByClassName('signatures');
	table = document.getElementsByTagName('input');
	intitule=1;
	for (l=0;l<table.length;l++){
		if (table[l].type == 'radio'){
			idQuestion = table[l].name.substr(6);
			var Elements_Obj = document.getElementsByName('radio_'+idQuestion);
			checkedObjet=false;
			for(var k=0; k<Elements_Obj.length; k++){
				if(Elements_Obj[k].checked){
					checkedObjet=true;
				}
			}
			if(document.getElementById('ValideQA_'+idQuestion).value=='0'){
				nomQ=document.getElementById('NomQuestionnaire_'+idQuestion).value;
				if(checkedObjet==false){alert(nomQ+" : \nVeuillez renseigner toutes les conformités");return false;}
			}
			else{
				if(checkedObjet==true){
					if(document.getElementById('QuestionAQ_'+idQuestion).value==''){
						intitule=0;
					}
				}
			}
			if(table[l].checked == true){
				if(table[l].value == 'NA' && table[l].checked == true){
					if(document.getElementById('typeNA_'+idQuestion).value=='0'|| document.getElementById('observation_'+idQuestion).value==''){
						nomQ=document.getElementById('NomQuestionnaire_'+idQuestion).value;
						alert(nomQ+' : \nVeuillez renseigner le type et la cause de la non applicabilité');return false;
					}
				}
				else if(table[l].value == 'NC' && table[l].checked == true){
					if(document.getElementById('observation_'+idQuestion).value=='' || document.getElementById('action_'+idQuestion).value==''){
						nomQ=document.getElementById('NomQuestionnaire_'+idQuestion).value;
						alert(nomQ+' : \nVeuillez renseigner les descriptions et les actions des réponses NC');return false;
					}
				}
			}
		}
	}
	if(intitule==0){
		if(document.getElementById('Langue').value=="FR"){
			Confirm=window.confirm("L'intitulé de certaines questions ne sont pas renseignées. Si vous continuez, ces questions ne seront pas enregistrés. Voulez vous continuez ?");
		}else{
			Confirm=window.confirm('The titles of some questions are not provided. If you continue, these questions will not be saved. Do you want to continue?');
		}
		if(Confirm==false){return false;}
	}
	
	var inputAT = document.getElementsByClassName('AT');
	for(var j=0; inputAT[j]; j++){
		Id_Questionnaire=inputAT[j].name.substr(16);
		  
		var inputActions = document.getElementsByClassName('actions_'+Id_Questionnaire);
		nbActionsTrackers=0;
		for(var i=0; inputActions[i]; i++){
			  if(inputActions[i].value=="Action immédiate + Action Tracker" || inputActions[i].value=="Immediate action + Action Tracker"){
				   nbActionsTrackers++;
			  }
		}

		if(parseFloat(document.getElementById('score_'+Id_Questionnaire).value)<parseFloat(document.getElementById('seuil_'+Id_Questionnaire).value)){
			nomQ=document.getElementById('NomQuestionnaire2_'+Id_Questionnaire).value;
			if(nbActionsTrackers==0){
				if(document.getElementById('Langue').value=="FR"){
					alert(nomQ+' : \nNote<'+document.getElementById('seuil_'+Id_Questionnaire).value+'%.Veuillez renseigner au moins une action "Action immédiate + Action Tracker"');
				}
				else{
					alert(nomQ+' : \nScore<'+document.getElementById('seuil_'+Id_Questionnaire).value+'%.Please fill in at least one action "Immediate action + Action Tracker"');
				}
				return false;
			}
		}
		
		if(nbActionsTrackers>0 && (document.getElementById('numActionTraker_'+Id_Questionnaire).value=="" || document.getElementById('numActionTraker_'+Id_Questionnaire).value=="0")){
			nomQ=document.getElementById('NomQuestionnaire2_'+Id_Questionnaire).value;
			if(document.getElementById('Langue').value=="FR"){
				Confirm=window.confirm(nomQ+' : \nN° fiche Action Tracker requis et non renseigné. Voulez-vous continuer ?');
			}else{
				Confirm=window.confirm(nomQ+' : \nAction Tracker form # requested and not filled-in. Do you want to continue ?');
			}
			if(Confirm==false){return false;}
		}
	}
	
	nSignatures=0;
	for(var i=0; inputSignatures[i]; i++){
		  if(inputSignatures[i].checked){
			   nSignatures++;
		  }
	}
	
	if(nSignatures!=2){
		if(document.getElementById('Langue').value=="FR"){
			alert('Surveillance non signée par le surveillant et/ou le surveillé.');return false;
		}else{
			alert('Surveillance not signed by the supervisor and/or by the supervised person.');return false;
		}
	}
}
function Change_Note(Id_Questionnaire,langue){
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
				compte=1;
				if(document.getElementById('QuestionAQ_'+idQuestion) != null){
					if(document.getElementById('QuestionAQ_'+idQuestion).value == ""){
						compte=0;
					}
				}
				if(compte==1){
					C=C+parseInt(document.getElementById('ponderation_'+idQuestion).value);
					total=total+parseInt(document.getElementById('ponderation_'+idQuestion).value);
				}
				document.getElementById('tr_'+idQuestion).style.display = "";
				document.getElementById('tr2_'+idQuestion).style.display = "";
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('labelAction_'+idQuestion).style.display = "none";
				document.getElementById('action_'+idQuestion).style.display = "none";
				document.getElementById('labelNA_'+idQuestion).style.display = "none";
				document.getElementById('typeNA_'+idQuestion).style.display = "none";
				
				if(langue=="FR"){
					document.getElementById('label_'+idQuestion).innerHTML = "Eléments de preuve, commentaires & observations";
				}
				else{
					document.getElementById('label_'+idQuestion).innerHTML = "Evidence, Comments & Observations";
				}
			}
			else if(table[l].value == 'NA' && table[l].checked == true){
				NA++;
				document.getElementById('tr_'+idQuestion).style.display = "";
				document.getElementById('tr2_'+idQuestion).style.display = "";
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('labelAction_'+idQuestion).style.display = "none";
				document.getElementById('action_'+idQuestion).style.display = "none";
				document.getElementById('labelNA_'+idQuestion).style.display = "";
				document.getElementById('typeNA_'+idQuestion).style.display = "";
				
				if(langue=="FR"){
					document.getElementById('label_'+idQuestion).innerHTML = "Cause de la non applicabilité";
				}
				else{
					document.getElementById('label_'+idQuestion).innerHTML = "Cause of non-applicability";
				}
			}
			else if(table[l].value == 'NC' && table[l].checked == true){
				compte=1;
				if(document.getElementById('QuestionAQ_'+idQuestion) != null){
					if(document.getElementById('QuestionAQ_'+idQuestion).value == ""){
						compte=0;
					}
				}
				if(compte==1){
					total=total+parseInt(document.getElementById('ponderation_'+idQuestion).value);
				}
				document.getElementById('tr_'+idQuestion).style.display = "";
				document.getElementById('tr2_'+idQuestion).style.display = "";
				document.getElementById('observation_'+idQuestion).style.display = "";
				document.getElementById('labelAction_'+idQuestion).style.display = "";
				document.getElementById('action_'+idQuestion).style.display = "";
				document.getElementById('labelNA_'+idQuestion).style.display = "none";
				document.getElementById('typeNA_'+idQuestion).style.display = "none";
				
				if(langue=="FR"){
					document.getElementById('label_'+idQuestion).innerHTML = "Description de la NC";
				}
				else{
					document.getElementById('label_'+idQuestion).innerHTML = "NC Description";
				}
			}
		}
	}
	if (total > 0){
		lnote = Math.round((C / total)*100);
		document.getElementById('note_'+Id_Questionnaire).value = lnote+"%" ;
		document.getElementById('score_'+Id_Questionnaire).value = lnote ;
	}
	else{
		document.getElementById('note_'+Id_Questionnaire).value = "100%";
		document.getElementById('score_'+Id_Questionnaire).value = 100 ;
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

function VerifChamps2(){
	var idQuestion = "";
	var inputSignatures = document.getElementsByClassName('signatures');
	
	if(document.getElementById('dateSurveillance').value==''){alert('Vous n\'avez pas renseigné la date de surveillance.');return false;}
	
	table = document.getElementsByTagName('input');
	intitule=1;
	for (l=0;l<table.length;l++){
		if (table[l].type == 'radio'){
			idQuestion = table[l].name.substr(6);
			var Elements_Obj = document.getElementsByName('radio_'+idQuestion);
			checkedObjet=false;
			for(var k=0; k<Elements_Obj.length; k++){
				if(Elements_Obj[k].checked){
					checkedObjet=true;
				}
			}
			if(document.getElementById('ValideQA_'+idQuestion).value=='0'){
				if(checkedObjet==false){alert("Veuillez renseigner la conformité");return false;}
			}
			else{
				if(checkedObjet==true){
					if(document.getElementById('QuestionAQ_'+idQuestion).value==''){
						intitule=0;
					}
				}
			}
			if(table[l].checked == true){
				if(table[l].value == 'NA' && table[l].checked == true){
					if(document.getElementById('typeNA_'+idQuestion).value=='0'|| document.getElementById('observation_'+idQuestion).value==''){
						alert('Veuillez renseigner le type et la cause de la non applicabilité');return false;
					}
				}
				else if(table[l].value == 'NC' && table[l].checked == true){
					if(document.getElementById('observation_'+idQuestion).value=='' || document.getElementById('action_'+idQuestion).value==''){
						alert('Veuillez renseigner les descriptions et les actions des réponses NC');return false;
					}
				}
			}
		}
	}
	if(intitule==0){
		if(document.getElementById('Langue').value=="FR"){
			Confirm=window.confirm("L'intitulé de certaines questions ne sont pas renseignées. Si vous continuez, ces questions ne seront pas enregistrés. Voulez vous continuez ?");
		}else{
			Confirm=window.confirm('The titles of some questions are not provided. If you continue, these questions will not be saved. Do you want to continue?');
		}
		if(Confirm==false){return false;}
	}
	
	nSignatures=0;
	for(var i=0; inputSignatures[i]; i++){
		  if(inputSignatures[i].checked){
			   nSignatures++;
		  }
	}
	
	if(nSignatures!=2){
		if(document.getElementById('Langue').value=="FR"){
			alert('Surveillance non signée par le surveillant et/ou le surveillé.');return false;
		}else{
			alert('Surveillance not signed by the supervisor and/or by the supervised person.');return false;
		}
	}
	
	var inputAT = document.getElementsByClassName('AT');
	for(var j=0; inputAT[j]; j++){
		Id_Questionnaire=inputAT[j].name.substr(16);
		  
		var inputActions = document.getElementsByClassName('actions_'+Id_Questionnaire);
		nbActionsTrackers=0;
		for(var i=0; inputActions[i]; i++){
			  if(inputActions[i].value=="Action immédiate + Action Tracker" || inputActions[i].value=="Immediate action + Action Tracker"){
				   nbActionsTrackers++;
			  }
		}

		if(parseFloat(document.getElementById('score_'+Id_Questionnaire).value)<parseFloat(document.getElementById('seuil_'+Id_Questionnaire).value)){
			if(nbActionsTrackers==0){
				if(document.getElementById('Langue').value=="FR"){
					alert('Note<'+document.getElementById('seuil_'+Id_Questionnaire).value+'%.Veuillez renseigner au moins une action "Action immédiate + Action Tracker"');
				}
				else{
					alert('Score<'+document.getElementById('seuil_'+Id_Questionnaire).value+'%.Please fill in at least one action "Immediate action + Action Tracker"');
				}
				return false;
			}
		}
		
		if(nbActionsTrackers>0 && (document.getElementById('numActionTraker_'+Id_Questionnaire).value=="" || document.getElementById('numActionTraker_'+Id_Questionnaire).value=="0")){
			if(document.getElementById('Langue').value=="FR"){
				Confirm=window.confirm('N° fiche Action Tracker requis et non renseigné. Voulez-vous continuer ?');
			}else{
				Confirm=window.confirm('Action Tracker form # requested and not filled-in. Do you want to continue ?');
			}
			if(Confirm==false){return false;}
		}
	}
}
function AfficherMasquerlesNC(){
	table = document.getElementsByTagName('input');
	
	if(document.getElementById('CB_NC').checked == true){
		for (l=0;l<table.length;l++){
			if (table[l].type == 'radio'){
				idQuestion = table[l].name.substr(6);
				if(table[l].value == 'C' && table[l].checked == true){
					document.getElementById('tr1_'+idQuestion).style.display = "none";
					document.getElementById('tr3_'+idQuestion).style.display = "none";
					document.getElementById('tr_'+idQuestion).style.display = "none";
					document.getElementById('tr2_'+idQuestion).style.display = "none";
					document.getElementById('tr4_'+idQuestion).style.display = "none";
				}
				else if(table[l].value == 'NA' && table[l].checked == true){
					document.getElementById('tr1_'+idQuestion).style.display = "none";
					document.getElementById('tr3_'+idQuestion).style.display = "none";
					document.getElementById('tr_'+idQuestion).style.display = "none";
					document.getElementById('tr2_'+idQuestion).style.display = "none";
					document.getElementById('tr4_'+idQuestion).style.display = "none";
				}
				else if(table[l].value == 'NC' && table[l].checked == true){
					document.getElementById('tr1_'+idQuestion).style.display = "";
					document.getElementById('tr3_'+idQuestion).style.display = "";
					document.getElementById('tr_'+idQuestion).style.display = "";
					document.getElementById('tr2_'+idQuestion).style.display = "";
					document.getElementById('tr4_'+idQuestion).style.display = "";
				}
			}
		}
	}
	else{
		for (l=0;l<table.length;l++){
			if (table[l].type == 'radio'){
				idQuestion = table[l].name.substr(6);
				document.getElementById('tr_'+idQuestion).style.display = "";
				document.getElementById('tr1_'+idQuestion).style.display = "";
				document.getElementById('tr2_'+idQuestion).style.display = "";
				document.getElementById('tr3_'+idQuestion).style.display = "";
				document.getElementById('tr4_'+idQuestion).style.display = "";
			}
		}
	}
}
function AfficherTout(){
	table = document.getElementsByTagName('input');
	
	for (l=0;l<table.length;l++){
		if (table[l].type == 'radio'){
			idQuestion = table[l].name.substr(6);
			document.getElementById('tr_'+idQuestion).style.display = "";
			document.getElementById('tr1_'+idQuestion).style.display = "";
			document.getElementById('tr2_'+idQuestion).style.display = "";
			document.getElementById('tr3_'+idQuestion).style.display = "";
			document.getElementById('tr4_'+idQuestion).style.display = "";
		}
	}
}

function VerifAttestation(){
	table = document.getElementsByTagName('input');
	intitule=1;
	for (l=0;l<table.length;l++){
		if (table[l].type == 'radio'){
			idQuestion = table[l].name.substr(6);
			var Elements_Obj = document.getElementsByName('autonomieSurveillant');
			checkedObjet=false;
			for(var k=0; k<Elements_Obj.length; k++){
				if(Elements_Obj[k].checked){
					checkedObjet=true;
				}
			}
			if(checkedObjet==false){
				alert("Veuillez confirmer si le surveillant en formation peut réaliser les prochaines surveillances en toute autonomie");
				return false;
			}
		}
	}
}