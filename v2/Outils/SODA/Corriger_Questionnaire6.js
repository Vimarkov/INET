function AfficherQA(Id_Questionnaire,Num){
	document.getElementById('BtnPlus_'+Id_Questionnaire+'_'+Num).style.display='none';
	Num=Num+1;
	document.getElementById('Question_'+Id_Questionnaire+'_'+Num).style.display='';
}
function Afficher(Sens,IdQ,FuturIdQ){
	if(Sens=="G" || Sens=="D"){
		if(Sens=="D"){
			document.getElementById('QuestionnaireEnTete_'+IdQ).style.display='none';
			document.getElementById('Questionnaire_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireAdditionnel_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireNote_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireFin_'+IdQ).style.display='none';
			if(FuturIdQ>0){
				document.getElementById('QuestionnaireEnTete_'+FuturIdQ).style.display='';
				document.getElementById('Questionnaire_'+FuturIdQ).style.display='';
				document.getElementById('QuestionnaireFin_'+FuturIdQ).style.display='';
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
			document.getElementById('Questionnaire_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireAdditionnel_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireNote_'+IdQ).style.display='none';
			document.getElementById('QuestionnaireFin_'+IdQ).style.display='none';
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
				if(checkedObjet==false){
					if(document.getElementById('Langue').value=="FR"){
						alert(nomQ+" : \nVeuillez renseigner toutes les conformités");
					}
					else{
						alert(nomQ+" : \nPlease answer to all questions");
					}
					return false;
				}
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
						if(document.getElementById('Langue').value=="FR"){
							alert(nomQ+' : \nVeuillez renseigner le type et la cause de la non applicabilité');
						}
						else{
							alert(nomQ+' : \nPlease fill in the type and cause of the non-applicability');
						}
						return false;
					}
				}
				else if(table[l].value == 'NC' && table[l].checked == true){
					if(document.getElementById('observation_'+idQuestion).value=='' || document.getElementById('action_'+idQuestion).value==''){
						nomQ=document.getElementById('NomQuestionnaire_'+idQuestion).value;
						
						if(document.getElementById('Langue').value=="FR"){
							alert(nomQ+' : \nVeuillez renseigner les descriptions et les actions des réponses NC');
						}
						else{
							alert(nomQ+' : \nPlease fill in the descriptions and actions linked to Non-Conform answers');
						}
						return false;
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
					document.getElementById('label_'+idQuestion).innerHTML = "Description NC / preuve / proposition d'actions long-terme (si nécessaire)";
				}
				else{
					document.getElementById('label_'+idQuestion).innerHTML = "NC description / Evidence / proposed long-terme Actions (if necessary)";
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
	
	if(formulaire.dateSurveillance.value==''){alert('Vous n\'avez pas renseigné la date de surveillance.');return false;}
	
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
				if(checkedObjet==false){
					
					if(document.getElementById('Langue').value=="FR"){
						alert("Veuillez renseigner la conformité");
					}
					else{
						alert("Please answer to the question");
					}
					return false;
				}
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
						if(document.getElementById('Langue').value=="FR"){
							alert('Veuillez renseigner le type et la cause de la non applicabilité');
						}
						else{
							alert('Please fill in the type and cause of the non-applicability');
						}
						return false;
					}
				}
				else if(table[l].value == 'NC' && table[l].checked == true){
					if(document.getElementById('observation_'+idQuestion).value=='' || document.getElementById('action_'+idQuestion).value==''){
						if(document.getElementById('Langue').value=="FR"){
							alert('Veuillez renseigner les descriptions et les actions des réponses NC');
						}
						else{
							alert('Please fill in the description and action linked to Non-Conform answer');
						}
						return false;
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
Liste_Questionnaire = new Array();
function CalculerQuestionnaire(){
	nbQuestions=0;
	var Elements_Obj = document.getElementsByClassName("theme");
	for(var k=0, l=Elements_Obj.length; k<l; k++){
		if(Elements_Obj[k].checked){
			for(m=0; m<3;m++){
				if(document.getElementById("Id_Questionnaire_"+Elements_Obj[k].value+"_"+m) != null){
					if(document.getElementById("Id_Questionnaire_"+Elements_Obj[k].value+"_"+m).value!=0){
						for(i=0;i<Liste_Questionnaire.length;i++){
							if(Liste_Questionnaire[i][0]==document.getElementById("Id_Questionnaire_"+Elements_Obj[k].value+"_"+m).value){
								if(Liste_Questionnaire[i][1]==0){nbQuestions=nbQuestions+Liste_Questionnaire[i][2];}
								else{
									if(Liste_Questionnaire[i][1]<Liste_Questionnaire[i][2]){nbQuestions=nbQuestions+Liste_Questionnaire[i][1];}
									else{nbQuestions=nbQuestions+Liste_Questionnaire[i][2];}
								}
							}
						}
					}
				}
			}
		}
	}
	document.getElementById("nbQuestions").value=nbQuestions;
	document.getElementById("nbQuestions2").value=nbQuestions;
	duree=nbQuestions*2;
	laDuree="";
	if(duree>0 && duree<=5){laDuree="0-5 minutes";}
	else if(duree<=10){laDuree="5-10 minutes";}
	else if(duree<=20){laDuree="10-20 minutes";}
	else if(duree<=30){laDuree="20-30 minutes";}
	else if(duree<=45){laDuree="30-45 minutes";}
	else if(duree<=60){laDuree="45-60 minutes";}
	else{laDuree="60+ minutes";}
	document.getElementById("nbQuestions").innerHTML=nbQuestions;
	document.getElementById("dureeApproximative").innerHTML=laDuree;
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