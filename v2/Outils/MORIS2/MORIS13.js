function round(value, decimals) {
  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
function calculeProductivite(){
	ratio=0;
	if(document.getElementById('tempsAlloue').value!="" && document.getElementById('tempsPasse').value!="" && document.getElementById('tempsPasse').value!=0){
		ratio=Number(document.getElementById('tempsAlloue').value)/Number(document.getElementById('tempsPasse').value);
		ratio=round(ratio,2);
	}
	document.getElementById('prodCorrigee').innerHTML=ratio;
	
	ratio=0;
	if(document.getElementById('tempsObjectif').value!="" && document.getElementById('tempsPasse').value!="" && document.getElementById('tempsPasse').value!=0){
		ratio=Number(document.getElementById('tempsObjectif').value)/Number(document.getElementById('tempsPasse').value);
		ratio=round(ratio,2);
	}
	document.getElementById('prodBrut').innerHTML=ratio;
}
function calculOTD(){
	ratio="";
	nbLivrableConforme=0;
	nbRetour=0;
	nbTolerance=0;
	if((document.getElementById('nbLivrableConformeOTD').value!="" && document.getElementById('nbLivrableConformeOTD').value!=0)
	||	(document.getElementById('nbRetourClientOTD').value!="" && document.getElementById('nbRetourClientOTD').value!=0)
	){
		nbLivrableConforme=Number(document.getElementById('nbLivrableConformeOTD').value);
		if(document.getElementById('nbRetourClientOTD').value!=""){
			nbRetour=Number(document.getElementById('nbRetourClientOTD').value);
		}
		if(document.getElementById('nbLivrableToleranceOTD').value!=""){
			nbTolerance=Number(document.getElementById('nbLivrableToleranceOTD').value);
		}
		ratio=nbLivrableConforme/(nbLivrableConforme+nbRetour+nbTolerance);
		ratio=round(ratio*100,2);
	}
	document.getElementById('OTDRealise').value=ratio;
	if(document.getElementById('objectifClientOTD').value!="" && ratio!=""){
		if(Number(document.getElementById('objectifClientOTD').value)<=ratio){
			document.getElementById('OTDRealise').style.backgroundColor="#3bff4b";
		}
		else{
			if(formulaire.ToleranceOTDOQD.value=='1' && document.getElementById('objectifToleranceOTD').value!="" && Number(document.getElementById('objectifToleranceOTD').value)<=ratio){
				document.getElementById('OTDRealise').style.backgroundColor="#ffd757";
			}
			else{
				document.getElementById('OTDRealise').style.backgroundColor="#ff2929";
			}
		}
	}
	else{
		document.getElementById('OTDRealise').style.backgroundColor="#ffffff";
	}
}
function calculOTD2(){
	ratioTotal=0;
	nbLivrableConformeTotal=0;
	nbRetourTotal=0;
	nbToleranceTotal=0;
	nbLigne=0;
	
	for(i=0;i<100;i++){
		if(document.getElementById('LibelleOTD'+i).value!=""){
			ratio="";
			nbLivrableConforme=0;
			nbRetour=0;
			nbTolerance=0;
			if((document.getElementById('nbLivrableConformeOTD'+i).value!="" && document.getElementById('nbLivrableConformeOTD'+i).value!=0)
				||
				(document.getElementById('nbRetourClientOTD'+i).value!="" && document.getElementById('nbRetourClientOTD'+i).value!=0)
			){
				nbLigne++;
				nbLivrableConforme=Number(document.getElementById('nbLivrableConformeOTD'+i).value);
				if(document.getElementById('nbRetourClientOTD'+i).value!=""){
					nbRetour=Number(document.getElementById('nbRetourClientOTD'+i).value);
				}
				if(document.getElementById('nbLivrableToleranceOTD'+i).value!=""){
					nbTolerance=Number(document.getElementById('nbLivrableToleranceOTD'+i).value);
				}
				ratio=nbLivrableConforme/(nbLivrableConforme+nbRetour+nbTolerance);
				ratio=round(ratio*100,2);
			}
			document.getElementById('OTDRealise'+i).value=ratio;
			if(document.getElementById('objectifClientOTD'+i).value!="" && ratio!=""){
				if(Number(document.getElementById('objectifClientOTD'+i).value)<=ratio){
					document.getElementById('OTDRealise'+i).style.backgroundColor="#3bff4b";
				}
				else{
					if(formulaire.ToleranceOTDOQD.value=='1' && document.getElementById('objectifToleranceOTD'+i).value!="" && Number(document.getElementById('objectifToleranceOTD'+i).value)<=ratio){
						document.getElementById('OTDRealise'+i).style.backgroundColor="#ffd757";
					}
					else{
						document.getElementById('OTDRealise'+i).style.backgroundColor="#ff2929";
					}
				}
			}
			else{
				document.getElementById('OTDRealise'+i).style.backgroundColor="#ffffff";
			}
			if(document.getElementById('PasOTD'+i).checked==false){
			nbLivrableConformeTotal+=nbLivrableConforme;
			nbRetourTotal+=nbRetour;
			nbToleranceTotal+=nbTolerance;
			}
		}
	}
	if(nbLigne>0){
		document.getElementById('nbLivrableConformeOTD').value=nbLivrableConformeTotal;
		document.getElementById('nbRetourClientOTD').value=nbRetourTotal;
		document.getElementById('nbLivrableToleranceOTD').value=nbToleranceTotal;
	}
	calculOTD();
}
function calculOQD(){
	ratio="";
	nbLivrableConforme=0;
	nbRetour=0;
	nbTolerance=0;
	if((document.getElementById('nbLivrableConformeOQD').value!="" && document.getElementById('nbLivrableConformeOQD').value!=0)
		|| (document.getElementById('nbRetourClientOQD').value!="" && document.getElementById('nbRetourClientOQD').value!=0)
	){
		nbLivrableConforme=Number(document.getElementById('nbLivrableConformeOQD').value);
		if(document.getElementById('nbRetourClientOQD').value!=""){
			nbRetour=Number(document.getElementById('nbRetourClientOQD').value);
		}
		if(document.getElementById('nbLivrableToleranceOQD').value!=""){
			nbTolerance=Number(document.getElementById('nbLivrableToleranceOQD').value);
		}
		ratio=nbLivrableConforme/(nbLivrableConforme+nbRetour+nbTolerance);
		ratio=round(ratio*100,2);
	}
	document.getElementById('OQDRealise').value=ratio;
	
	if(document.getElementById('objectifClientOQD').value!="" && ratio!=""){
		if(Number(document.getElementById('objectifClientOQD').value)<=ratio){
			document.getElementById('OQDRealise').style.backgroundColor="#3bff4b";
		}
		else{
			if(formulaire.ToleranceOTDOQD.value=='1' && document.getElementById('objectifToleranceOQD').value!="" && Number(document.getElementById('objectifToleranceOQD').value)<=ratio){
				document.getElementById('OQDRealise').style.backgroundColor="#ffd757";
			}
			else{
				document.getElementById('OQDRealise').style.backgroundColor="#ff2929";
			}
		}
	}
	else{
		document.getElementById('OQDRealise').style.backgroundColor="#ffffff";
	}
}
function calculOQD2(){
	ratioTotal=0;
	nbLivrableConformeTotal=0;
	nbRetourTotal=0;
	nbToleranceTotal=0;
	nbLigne=0;
	
	for(i=0;i<100;i++){
		if(document.getElementById('LibelleOQD'+i).value!=""){
			ratio="";
			nbLivrableConforme=0;
			nbRetour=0;
			nbTolerance=0;
			if((document.getElementById('nbLivrableConformeOQD'+i).value!="" && document.getElementById('nbLivrableConformeOQD'+i).value!=0)
			|| (document.getElementById('nbRetourClientOQD'+i).value!="" && document.getElementById('nbRetourClientOQD'+i).value!=0)
			){
				nbLigne++;
				nbLivrableConforme=Number(document.getElementById('nbLivrableConformeOQD'+i).value);
				if(document.getElementById('nbRetourClientOQD'+i).value!=""){
					nbRetour=Number(document.getElementById('nbRetourClientOQD'+i).value);
				}
				if(document.getElementById('nbLivrableToleranceOQD'+i).value!=""){
					nbTolerance=Number(document.getElementById('nbLivrableToleranceOQD'+i).value);
				}
				ratio=nbLivrableConforme/(nbLivrableConforme+nbRetour+nbTolerance);
				ratio=round(ratio*100,2);
			}
			document.getElementById('OQDRealise'+i).value=ratio;
			
			if(document.getElementById('objectifClientOQD'+i).value!="" && ratio!=""){
				if(Number(document.getElementById('objectifClientOQD'+i).value)<=ratio){
					document.getElementById('OQDRealise'+i).style.backgroundColor="#3bff4b";
				}
				else{
					if(formulaire.ToleranceOTDOQD.value=='1' && document.getElementById('objectifToleranceOQD'+i).value!="" && Number(document.getElementById('objectifToleranceOQD'+i).value)<=ratio){
						document.getElementById('OQDRealise'+i).style.backgroundColor="#ffd757";
					}
					else{
						document.getElementById('OQDRealise'+i).style.backgroundColor="#ff2929";
					}
				}
			}
			else{
				document.getElementById('OQDRealise'+i).style.backgroundColor="#ffffff";
			}
			if(document.getElementById('PasOQD'+i).checked==false){
				nbLivrableConformeTotal+=nbLivrableConforme;
				nbRetourTotal+=nbRetour;
				nbToleranceTotal+=nbTolerance;
			}
		}
	}
	if(nbLigne>0){
		document.getElementById('nbLivrableConformeOQD').value=nbLivrableConformeTotal;
		document.getElementById('nbRetourClientOQD').value=nbRetourTotal;
		document.getElementById('nbLivrableToleranceOQD').value=nbToleranceTotal;
	}
	calculOQD();
}
function calculCompetences(){
	ratio=0;
	if(document.getElementById('nbXTableauPolyvalence').value!="" && document.getElementById('nbLTableauPolyvalence').value!="" && (document.getElementById('nbXTableauPolyvalence').value!=0 || document.getElementById('nbLTableauPolyvalence').value!=0)){
		ratio=Number(document.getElementById('nbXTableauPolyvalence').value)/(Number(document.getElementById('nbXTableauPolyvalence').value)+Number(document.getElementById('nbLTableauPolyvalence').value));
		ratio=round(ratio*100,2);
	}
	document.getElementById('pourcentageCompetences').innerHTML=ratio;
}
function VerifChampsEnregistrement(langue){
	checkedPasActivite=false;
	var Elements_PasActivite = document.getElementsByClassName('PasActivite');
	for(var k=0, l=Elements_PasActivite.length; k<l; k++){
		if(Elements_PasActivite[k].checked){
			checkedPasActivite=true;
		}
	}
	
	if(checkedPasActivite==false && formulaire.OTDOQDADesactive.value=='0'){
		if(document.getElementById('PasOTD').checked==false){
			if(formulaire.objectifClientOTD.value=='' || formulaire.objectifClientOTD.value==0){
				if(langue=="EN"){alert('Please fill in the OTD customer objective.');}
				else{alert('Veuillez renseigner l\'objectif client OTD.');}
				return false;
			}
		}

		if(document.getElementById('PasOQD').checked==false){
			if(formulaire.objectifClientOQD.value=='' || formulaire.objectifClientOQD.value==0){
				if(langue=="EN"){alert('OQD : Please fill in the customer objective.');}
				else{alert('OQD : Veuillez renseigner l\'objectif client.');}
				return false;
			}
		}
	}

	return true;

}
function VerifChamps(langue){
	ratio=Number(document.getElementById('tempsAlloue').value)/Number(document.getElementById('tempsPasse').value);
	ratio=round(ratio,2);
	
	checkedManagement=false;
	if(formulaire.ManagementADesactive.value=='1'){
		checkedManagement=true;
	}
	else{
		var Elements_Management = document.getElementsByClassName('tendanceManagement');
		for(var k=0, l=Elements_Management.length; k<l; k++){
			if(Elements_Management[k].checked){
				checkedManagement=true;
			}
		}
	}
	
	checkedPasActivite=false;
	var Elements_PasActivite = document.getElementsByClassName('PasActivite');
	for(var k=0, l=Elements_PasActivite.length; k<l; k++){
		if(Elements_PasActivite[k].checked){
			checkedPasActivite=true;
		}
	}
	
	checkedPasAT=false;
	if(formulaire.SecuriteADesactive.value=='1' || (formulaire.SecuriteADesactive.value=='0' && formulaire.AnneeMois.value>'2023_06') ){
		checkedPasAT=true;
	}
	else{
		var Elements_PasAT = document.getElementsByClassName('PasAT');
		for(var k=0, l=Elements_PasAT.length; k<l; k++){
			if(Elements_PasAT[k].checked){
				checkedPasAT=true;
			}
		}
	}
	
	if(checkedPasActivite==false){
		if(ratio<1 && formulaire.commentaireProductivite.value==''){
			if(langue=="EN"){alert('PRODUCTIVITY : Please enter a comment for productivity.');}
			else{alert('PRODUCTIVITE : Veuillez renseigner un commentaire pour la productivité.');}
			return false;
		}
		if(checkedManagement==false){
			if(langue=="EN"){alert('MANAGEMENT : Please fill in the trend.');}
			else{alert('MANAGEMENT : Veuillez renseigner la tendance.');}
			return false;
		}
		if(formulaire.OTDOQDADesactive.value=='0'){
			if(document.getElementById('PasOTD').checked==false){
				if(formulaire.objectifClientOTD.value=='' || formulaire.objectifClientOTD.value==0){
					if(langue=="EN"){alert('Please fill in the OTD customer objective.');}
					else{alert('Veuillez renseigner l\'objectif client OTD.');}
					return false;
				}
				if(formulaire.ToleranceOTDOQD.value=='1'){
					if(formulaire.objectifToleranceOTD.value=='' || formulaire.objectifToleranceOTD.value==0){
						if(langue=="EN"){alert('OTD : Please fill in the tolerance objective.');}
						else{alert('OTD : Veuillez renseigner l\'objectif tolérance.');}
						return false;
					}
				}
				if(formulaire.objectifClientOTD.value!="" && formulaire.OTDRealise.value!=""){
					if(Number(formulaire.OTDRealise.value) < Number(formulaire.objectifClientOTD.value)){
						if(formulaire.causeOTD.value==''){
							if(langue=="EN"){alert('OTD : Please fill in the cause of the deliverables in default.');}
							else{alert('OTD : Veuillez renseigner la cause des livrables en défaut.');}
							return false;
						}
						if(formulaire.actionOTD.value==''){
							if(langue=="EN"){alert('OTD : Please fill in the actions.');}
							else{alert('OTD : Veuillez renseigner les actions.');}
							return false;
						}
					}
				}
				else{
					if(langue=="EN"){alert('OTD: Please fill in the OTD.');}
					else{alert('OTD : Veuillez renseigner l\'OTD.');}
					return false;
				}
			}
			else{
				if((document.getElementById('nbLivrableConformeOTD').value!="" && Number(document.getElementById('nbLivrableConformeOTD').value)>0) ||
					(document.getElementById('nbRetourClientOTD').value!="" && Number(document.getElementById('nbRetourClientOTD').value)>0) || 
					(document.getElementById('nbLivrableToleranceOTD').value!="" && Number(document.getElementById('nbLivrableToleranceOTD').value)>0)){
					if(langue=="EN"){alert("OTD: Please choose between the 'No deliverable' checkbox and the 'number of deliverables' fields.");}
					else{alert("OTD : Veuillez choisir entre la case à cocher 'Pas de livrable' et les champs 'nombres de livrables'.");}
					return false;
				}
			}
		
			calculOTD2();
			checkedAlertOTD=0;
			for(i=0;i<100;i++){
				if(document.getElementById('LibelleOTD'+i).value!=""){
					if(document.getElementById('PasOTD'+i).checked==true){
						if((document.getElementById('nbLivrableConformeOTD'+i).value!="" && Number(document.getElementById('nbLivrableConformeOTD'+i).value)>0) || 
							(document.getElementById('nbRetourClientOTD'+i).value!="" && Number(document.getElementById('nbRetourClientOTD'+i).value)>0) || 
							(document.getElementById('nbLivrableToleranceOTD'+i).value!="" && Number(document.getElementById('nbLivrableToleranceOTD'+i).value)>0)){
							checkedAlertOTD=1;
						}
					}
					else{
						if(document.getElementById('objectifClientOTD'+i).value!="" && document.getElementById('OTDRealise'+i).value!=""){
							if(Number(document.getElementById('OTDRealise'+i).value) < Number(document.getElementById('objectifClientOTD'+i).value)){
								if(formulaire.causeOTD.value==''){
									if(langue=="EN"){alert('OTD : Please fill in the cause of the deliverables in default.');}
									else{alert('OTD : Veuillez renseigner la cause des livrables en défaut.');}
									return false;
								}
								if(formulaire.actionOTD.value==''){
									if(langue=="EN"){alert('OTD : Please fill in the actions.');}
									else{alert('OTD : Veuillez renseigner les actions.');}
									return false;
								}
							}
						}
					}
				}
			}
			if(checkedAlertOTD==1){
				if(langue=="EN"){alert("OTD: Please choose between the 'No deliverable' checkbox and the 'number of deliverables' fields.");}
				else{alert("OTD : Veuillez choisir entre la case à cocher 'Pas de livrable' et les champs 'nombres de livrables'.");}
				return false;
			}
			
			if(document.getElementById('PasOQD').checked==false){
				if(formulaire.objectifClientOQD.value=='' || formulaire.objectifClientOQD.value==0){
					if(langue=="EN"){alert('OQD : Please fill in the customer objective.');}
					else{alert('OQD : Veuillez renseigner l\'objectif client.');}
					return false;
				}
				if(formulaire.ToleranceOTDOQD.value=='1'){
					if(formulaire.objectifToleranceOQD.value=='' || formulaire.objectifToleranceOQD.value==0){
						if(langue=="EN"){alert('OQD : Please fill in the tolerance objective.');}
						else{alert('OQD : Veuillez renseigner l\'objectif tolérance.');}
						return false;
					}
				}
				if(formulaire.objectifClientOQD.value!="" && formulaire.OQDRealise.value!=""){
					if(Number(formulaire.OQDRealise.value) < Number(formulaire.objectifClientOQD.value)){
						if(formulaire.causeOQD.value==''){
							if(langue=="EN"){alert('OQD : Please fill in the cause of the deliverables in default.');}
							else{alert('OQD : Veuillez renseigner la cause des livrables en défaut.');}
							return false;
						}
						if(formulaire.actionOQD.value==''){
							if(langue=="EN"){alert('OQD : Please fill in the actions.');}
							else{alert('OQD : Veuillez renseigner les actions.');}
							return false;
						}
					}
				}
				else{
					if(langue=="EN"){alert('OQD: Please fill in the OTD.');}
					else{alert('OQD : Veuillez renseigner l\'OQD.');}
					return false;
				}
			}
			else{
				if((document.getElementById('nbLivrableConformeOQD').value!="" && document.getElementById('nbLivrableConformeOQD').value!="0") ||
					(document.getElementById('nbRetourClientOQD').value!="" && document.getElementById('nbRetourClientOQD').value!="0") || 
					(document.getElementById('nbLivrableToleranceOQD').value!="" && document.getElementById('nbLivrableToleranceOQD').value!="0")){
						if(langue=="EN"){alert("OQD: Please choose between the 'No deliverable' checkbox and the 'number of deliverables' fields.");}
						else{alert("OQD : Veuillez choisir entre la case à cocher 'Pas de livrable' et les champs 'nombres de livrables'.");}
						return false;
				}
			}

			calculOQD2();
			checkedAlertOQD=0;
			for(i=0;i<100;i++){
				if(document.getElementById('LibelleOQD'+i).value!=""){
					if(document.getElementById('PasOQD'+i).checked==true){
						if((document.getElementById('nbLivrableConformeOQD'+i).value!="" && Number(document.getElementById('nbLivrableConformeOQD'+i).value)>0) ||
							(document.getElementById('nbRetourClientOQD'+i).value!="" && Number(document.getElementById('nbRetourClientOQD'+i).value)>0) || 
							(document.getElementById('nbLivrableToleranceOQD'+i).value!="" && Number(document.getElementById('nbLivrableToleranceOQD'+i).value)>0)){
							checkedAlertOQD=1;
						}
					}
					else{
						if(document.getElementById('objectifClientOQD'+i).value!="" && document.getElementById('OQDRealise'+i).value!=""){
							if(Number(document.getElementById('OQDRealise'+i).value) < Number(document.getElementById('objectifClientOQD'+i).value)){
								if(formulaire.causeOQD.value==''){
									if(langue=="EN"){alert('OQD : Please fill in the cause of the deliverables in default.');}
									else{alert('OQD : Veuillez renseigner la cause des livrables en défaut.');}
									return false;
								}
								if(formulaire.actionOQD.value==''){
									if(langue=="EN"){alert('OQD : Please fill in the actions.');}
									else{alert('OQD : Veuillez renseigner les actions.');}
									return false;
								}
							}
						}
					}
				}
			}
			if(checkedAlertOQD==1){
				if(langue=="EN"){alert("OQD: Please choose between the 'No deliverable' checkbox and the 'number of deliverables' fields.");}
				else{alert("OQD : Veuillez choisir entre la case à cocher 'Pas de livrable' et les champs 'nombres de livrables'.");}
				return false;
			}
		}
		
		if(formulaire.evaluationQualite.value > 0 || formulaire.evaluationDelais.value > 0 || formulaire.evaluationCompetencePersonnel.value > 0 || formulaire.evaluationAutonomie.value > 0 || formulaire.evaluationAnticipation.value > 0 || formulaire.evaluationCommunication.value > 0){
			if(formulaire.derniereDateEvaluation.value==''){
				if(langue=="EN"){alert('PRM & CUSTOMER SATISFACTION : Please fill in the last valuation date.');}
				else{alert('PRM & SATISFACTION CLIENTS : Veuillez renseigner la dernière date d\'évaluation.');}
				return false;
			}
		}
		
		if(checkedPasAT==false){
			existeAT=0;
			for(k=1; k<4; k++){
				if(document.getElementById('dateAT'+k).value!=""){existeAT=1;}
			}
			if(existeAT==0){
				if(langue=="EN"){alert('SECURITY : Please fill in the accidents at work.');}
				else{alert('SECURITE : Veuillez renseigner les AT.');}
				return false;
			}
		}
		
	}

	return true;

}

function AfficheAide(Paragraphe)
{
	var SourceImage = document.getElementById(Paragraphe).src;
	var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
	var elements = document.getElementsByClassName(Paragraphe);
	
	if(result == "e2.png")
	{
		document.getElementById(Paragraphe).src="../../Images/LivreOuvert.png";
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
	}
	else
	{
		document.getElementById(Paragraphe).src="../../Images/Aide2.png";
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
}
function calculMoyennePRM(){
	ratio="";
	nbeval=0;
	total=0;
	if(document.getElementById('evaluationQualite').value!="NA" && document.getElementById('evaluationQualite').value!="N" && document.getElementById('evaluationQualite').value!="A"){
		nbeval++;
	}
	if(document.getElementById('evaluationDelais').value!="NA" && document.getElementById('evaluationDelais').value!="N" && document.getElementById('evaluationDelais').value!="A"){
		nbeval++;
	}
	if(document.getElementById('evaluationCompetencePersonnel').value!="NA" && document.getElementById('evaluationCompetencePersonnel').value!="N" && document.getElementById('evaluationCompetencePersonnel').value!="A"){
		nbeval++;
	}
	if(document.getElementById('evaluationAutonomie').value!="NA" && document.getElementById('evaluationAutonomie').value!="N" && document.getElementById('evaluationAutonomie').value!="A"){
		nbeval++;
	}
	if(document.getElementById('evaluationAnticipation').value!="NA" && document.getElementById('evaluationAnticipation').value!="N" && document.getElementById('evaluationAnticipation').value!="A"){
		nbeval++;
	}
	if(document.getElementById('evaluationCommunication').value!="NA" && document.getElementById('evaluationCommunication').value!="N" && document.getElementById('evaluationCommunication').value!="A"){
		nbeval++;
	}
	if(nbeval>0){
		if(document.getElementById('evaluationQualite').value!="" && document.getElementById('evaluationQualite').value!="NA" && document.getElementById('evaluationQualite').value!="N" && document.getElementById('evaluationQualite').value!="A"){
			total+=Number(document.getElementById('evaluationQualite').value);
		}
		if(document.getElementById('evaluationDelais').value!="" && document.getElementById('evaluationDelais').value!="NA" && document.getElementById('evaluationDelais').value!="N" && document.getElementById('evaluationDelais').value!="A"){
			total+=Number(document.getElementById('evaluationDelais').value);
		}
		if(document.getElementById('evaluationCompetencePersonnel').value!="" && document.getElementById('evaluationCompetencePersonnel').value!="NA" && document.getElementById('evaluationCompetencePersonnel').value!="N" && document.getElementById('evaluationCompetencePersonnel').value!="A"){
			total+=Number(document.getElementById('evaluationCompetencePersonnel').value);
		}
		if(document.getElementById('evaluationAutonomie').value!="" && document.getElementById('evaluationAutonomie').value!="NA" && document.getElementById('evaluationAutonomie').value!="N" && document.getElementById('evaluationAutonomie').value!="A"){
			total+=Number(document.getElementById('evaluationAutonomie').value);
		}
		if(document.getElementById('evaluationAnticipation').value!="" && document.getElementById('evaluationAnticipation').value!="NA" && document.getElementById('evaluationAnticipation').value!="N" && document.getElementById('evaluationAnticipation').value!="A"){
			total+=Number(document.getElementById('evaluationAnticipation').value);
		}
		if(document.getElementById('evaluationCommunication').value!="" && document.getElementById('evaluationCommunication').value!="NA" && document.getElementById('evaluationCommunication').value!="N" && document.getElementById('evaluationCommunication').value!="A"){
			total+=Number(document.getElementById('evaluationCommunication').value);
		}
		
		ratio=total/nbeval;
		ratio=round(ratio,2);
		
		if(total==0){
			ratio="";
		}
	}
	else{
		ratio="";
	}
	document.getElementById('moyennePRM').innerHTML=ratio;
}
function Reporter(partie){
	if(partie=='Competences'){
		document.getElementById('nbXTableauPolyvalence').value=document.getElementById('nbXTableauPolyvalenceM1').value;
		document.getElementById('nbLTableauPolyvalence').value=document.getElementById('nbLTableauPolyvalenceM1').value;
		document.getElementById('nbMonoCompetence').value=document.getElementById('nbMonoCompetenceM1').value;
		calculCompetences();
	}
	else if(partie=='TauxQualif'){
		document.getElementById('tauxQualif').value=document.getElementById('tauxQualifRecup').value;
		document.getElementById('tauxQualif2').value=document.getElementById('tauxQualifRecup').value;
	}
	else if(partie=='Charge'){
		document.getElementById('M6').value=document.getElementById('M6M1').value;
		document.getElementById('M5').value=document.getElementById('M6M1').value;
		document.getElementById('M4').value=document.getElementById('M5M1').value;
		document.getElementById('M3').value=document.getElementById('M4M1').value;
		document.getElementById('M2').value=document.getElementById('M3M1').value;
		document.getElementById('M1').value=document.getElementById('M2M1').value;
		
		document.getElementById('besoinEffectif').value=document.getElementById('besoinEffectifM1').value;
		if(document.getElementById('M1M1').value==document.getElementById('sommeCurrent').value){
			document.getElementById('interneCurrent').value=document.getElementById('interneCurrentM1').value;
			document.getElementById('subContractorCurrent').value=document.getElementById('subContractorCurrentM1').value;
			document.getElementById('attentionCharge1').style.display='';
		}
		else{
			if(document.getElementById('Langue').value=="FR"){
				document.getElementById('valeurM1').innerHTML="Valeur M+1 du mois précédent<br>"+document.getElementById('M1M1').value;
			}
			else 
			{
				document.getElementById('valeurM1').innerHTML="M+1 value of the previous month<br>"+document.getElementById('M1M1').value;
			}
			
			document.getElementById('attentionCharge2').style.display='';
		}
	}
	else if(partie=='Charge2'){
		document.getElementById('interneIndefiniM').value=document.getElementById('interneIndefiniM1old').value;
		document.getElementById('interneIndefiniM1').value=document.getElementById('interneIndefiniM2old').value;
		document.getElementById('interneIndefiniM2').value=document.getElementById('interneIndefiniM3old').value;
		document.getElementById('interneIndefiniM3').value=document.getElementById('interneIndefiniM4old').value;
		document.getElementById('interneIndefiniM4').value=document.getElementById('interneIndefiniM5old').value;
		document.getElementById('interneIndefiniM5').value=document.getElementById('interneIndefiniM6old').value;
		
		document.getElementById('interneIndefiniCapaM').value=document.getElementById('interneIndefiniCapaM1old').value;
		document.getElementById('interneIndefiniCapaM1').value=document.getElementById('interneIndefiniCapaM2old').value;
		document.getElementById('interneIndefiniCapaM2').value=document.getElementById('interneIndefiniCapaM3old').value;
		document.getElementById('interneIndefiniCapaM3').value=document.getElementById('interneIndefiniCapaM4old').value;
		document.getElementById('interneIndefiniCapaM4').value=document.getElementById('interneIndefiniCapaM5old').value;
		document.getElementById('interneIndefiniCapaM5').value=document.getElementById('interneIndefiniCapaM6old').value;
		
		document.getElementById('externeIndefiniM').value=document.getElementById('externeIndefiniM1old').value;
		document.getElementById('externeIndefiniM1').value=document.getElementById('externeIndefiniM2old').value;
		document.getElementById('externeIndefiniM2').value=document.getElementById('externeIndefiniM3old').value;
		document.getElementById('externeIndefiniM3').value=document.getElementById('externeIndefiniM4old').value;
		document.getElementById('externeIndefiniM4').value=document.getElementById('externeIndefiniM5old').value;
		document.getElementById('externeIndefiniM5').value=document.getElementById('externeIndefiniM6old').value;
		
		document.getElementById('externeIndefiniCapaM').value=document.getElementById('externeIndefiniCapaM1old').value;
		document.getElementById('externeIndefiniCapaM1').value=document.getElementById('externeIndefiniCapaM2old').value;
		document.getElementById('externeIndefiniCapaM2').value=document.getElementById('externeIndefiniCapaM3old').value;
		document.getElementById('externeIndefiniCapaM3').value=document.getElementById('externeIndefiniCapaM4old').value;
		document.getElementById('externeIndefiniCapaM4').value=document.getElementById('externeIndefiniCapaM5old').value;
		document.getElementById('externeIndefiniCapaM5').value=document.getElementById('externeIndefiniCapaM6old').value;
		
		var Elements = document.getElementsByClassName('interneExterne');
		for(var k=0, l=Elements.length; k<l; k++){
			document.getElementById(Elements[k].id+'M').value=document.getElementById(Elements[k].id+'M1old').value;
			document.getElementById(Elements[k].id+'M1').value=document.getElementById(Elements[k].id+'M2old').value;
			document.getElementById(Elements[k].id+'M2').value=document.getElementById(Elements[k].id+'M3old').value;
			document.getElementById(Elements[k].id+'M3').value=document.getElementById(Elements[k].id+'M4old').value;
			document.getElementById(Elements[k].id+'M4').value=document.getElementById(Elements[k].id+'M5old').value;
			document.getElementById(Elements[k].id+'M5').value=document.getElementById(Elements[k].id+'M6old').value;
		}
		CalculerTotalCharge();
	}
	else if(partie=='Charge3'){
		document.getElementById('interneIndefiniM1').value=document.getElementById('interneIndefiniM').value;
		document.getElementById('interneIndefiniM2').value=document.getElementById('interneIndefiniM').value;
		document.getElementById('interneIndefiniM3').value=document.getElementById('interneIndefiniM').value;
		document.getElementById('interneIndefiniM4').value=document.getElementById('interneIndefiniM').value;
		document.getElementById('interneIndefiniM5').value=document.getElementById('interneIndefiniM').value;
		document.getElementById('interneIndefiniM6').value=document.getElementById('interneIndefiniM').value;
		
		document.getElementById('interneIndefiniCapaM1').value=document.getElementById('interneIndefiniCapaM').value;
		document.getElementById('interneIndefiniCapaM2').value=document.getElementById('interneIndefiniCapaM').value;
		document.getElementById('interneIndefiniCapaM3').value=document.getElementById('interneIndefiniCapaM').value;
		document.getElementById('interneIndefiniCapaM4').value=document.getElementById('interneIndefiniCapaM').value;
		document.getElementById('interneIndefiniCapaM5').value=document.getElementById('interneIndefiniCapaM').value;
		document.getElementById('interneIndefiniCapaM6').value=document.getElementById('interneIndefiniCapaM').value;

		document.getElementById('externeIndefiniM1').value=document.getElementById('externeIndefiniM').value;
		document.getElementById('externeIndefiniM2').value=document.getElementById('externeIndefiniM').value;
		document.getElementById('externeIndefiniM3').value=document.getElementById('externeIndefiniM').value;
		document.getElementById('externeIndefiniM4').value=document.getElementById('externeIndefiniM').value;
		document.getElementById('externeIndefiniM5').value=document.getElementById('externeIndefiniM').value;
		document.getElementById('externeIndefiniM6').value=document.getElementById('externeIndefiniM').value;
		
		document.getElementById('externeIndefiniCapaM1').value=document.getElementById('externeIndefiniCapaM').value;
		document.getElementById('externeIndefiniCapaM2').value=document.getElementById('externeIndefiniCapaM').value;
		document.getElementById('externeIndefiniCapaM3').value=document.getElementById('externeIndefiniCapaM').value;
		document.getElementById('externeIndefiniCapaM4').value=document.getElementById('externeIndefiniCapaM').value;
		document.getElementById('externeIndefiniCapaM5').value=document.getElementById('externeIndefiniCapaM').value;
		document.getElementById('externeIndefiniCapaM6').value=document.getElementById('externeIndefiniCapaM').value;
		
		var Elements = document.getElementsByClassName('interneExterne');
		for(var k=0, l=Elements.length; k<l; k++){
			document.getElementById(Elements[k].id+'M1').value=document.getElementById(Elements[k].id+'M').value;
			document.getElementById(Elements[k].id+'M2').value=document.getElementById(Elements[k].id+'M').value;
			document.getElementById(Elements[k].id+'M3').value=document.getElementById(Elements[k].id+'M').value;
			document.getElementById(Elements[k].id+'M4').value=document.getElementById(Elements[k].id+'M').value;
			document.getElementById(Elements[k].id+'M5').value=document.getElementById(Elements[k].id+'M').value;
			document.getElementById(Elements[k].id+'M6').value=document.getElementById(Elements[k].id+'M').value;
		}
		CalculerTotalCharge();
	}
	else if(partie=='CommentaireCharge'){
		document.getElementById('besoinEffectif').value=document.getElementById('besoinEffectifM1').value;
	}
}

function changerFormat(AT){
	if(AT==1){
		if(document.getElementById('Langue').value=="FR"){
			document.getElementById('Q1').innerHTML="Connaissances & Compétences des ressources vs Besoin client";
			document.getElementById('Q2').innerHTML="Délai de mise en place des ressources / Ramp-up & Ramp-down";
			document.getElementById('Q3').innerHTML="Attitude des ressources (envers le client / l'equipe sur site)";
			document.getElementById('Q4').innerHTML="Autonomie / Flexibilité des ressources";
			document.getElementById('Q5').innerHTML="Proactivité / Anticipation du management";
			document.getElementById('Q6').innerHTML="Interface / Communication client avec le management";
		}
		else{
			document.getElementById('Q1').innerHTML="Resource Knowledge & Skills vs Customer Need";
			document.getElementById('Q2').innerHTML="Time to set up resources / Ramp-up & Ramp-down";
			document.getElementById('Q3').innerHTML="Resource attitude (towards client / on-site team)";
			document.getElementById('Q4').innerHTML="Autonomy / flexibility of resources";
			document.getElementById('Q5').innerHTML="Proactivity / Anticipation of management";
			document.getElementById('Q6').innerHTML="Customer interface / communication with management";
		}
	}
	else{
		if(document.getElementById('Langue').value=="FR"){
			document.getElementById('Q1').innerHTML="Qualité du produit / prestation (Respect des exigences du client)";
			document.getElementById('Q2').innerHTML="Respect des délais";
			document.getElementById('Q3').innerHTML="Compétences du personnel";
			document.getElementById('Q4').innerHTML="Autonomie / Flexibilité";
			document.getElementById('Q5').innerHTML="Proactivité / Anticipation";
			document.getElementById('Q6').innerHTML="Interface / Communication avec le client";
		}
		else{
			document.getElementById('Q1').innerHTML="Product / service quality (Compliance with customer requirements) ";
			document.getElementById('Q2').innerHTML="Respect of deadlines";
			document.getElementById('Q3').innerHTML="Staff skills";
			document.getElementById('Q4').innerHTML="Autonomy / Flexibility";
			document.getElementById('Q5').innerHTML="Proactivity / Anticipation";
			document.getElementById('Q6').innerHTML="Interface / Communication with the customer";
		}
	}
}
function AfficherSatisfaction(){
	document.getElementById('idPlusSatisfaction2').style.display="";
	document.getElementById('idPlusSatisfaction').style.display="none";
}
function AfficherOTD(ligne,ligne1){
	if(ligne==-1){
		document.getElementById(ligne1).style.display="";
		document.getElementById("ligne0").style.display="";
		document.getElementById("BtnPlusOTD").style.display="none";
		document.getElementById("BtnPlusOTD0").style.display="";
	}
	else{
		if(ligne<99){
			document.getElementById("BtnPlusOTD"+ligne).style.display="none";
			document.getElementById("ligne"+ligne1).style.display="";
			ligne2=parseInt(ligne1)+1;
			if(ligne2<99){
				if(document.getElementById("ligne"+ligne2).style.display=="none"){
					
					document.getElementById("BtnPlusOTD"+ligne1).style.display="";
				}
			}
		}
		else{
			document.getElementById("BtnPlusOTD"+ligne).style.display="none";
			document.getElementById("ligne"+ligne1).style.display="";
		}
	}
}
function MasquerOTD(ligne,ligne1){
	document.getElementById("ligne"+ligne).style.display="none";
	
	document.getElementById("LibelleOTD"+ligne).value="";
	document.getElementById("objectifClientOTD"+ligne).value="";
	document.getElementById("objectifToleranceOTD"+ligne).value="";
	document.getElementById("nbLivrableConformeOTD"+ligne).value="";
	document.getElementById("nbLivrableToleranceOTD"+ligne).value="";
	document.getElementById("nbRetourClientOTD"+ligne).value="";
	document.getElementById("OTDRealise"+ligne).value="";
	document.getElementById("PasOTD"+ligne).checked=false;
	
	bAffiche=0;
	for(i=0;i<100;i++){
		if(document.getElementById("ligne"+i).style.display==""){
			bAffiche=1;
		}
	}
	if(bAffiche==0){
		document.getElementById("BtnPlusOTD").style.display="";
		document.getElementById("blocOTD").style.display="none";
	}
	if(ligne1!=-1){
		document.getElementById("BtnPlusOTD"+ligne1).style.display="";
	}
	calculOTD2();
}

function AfficherOQD(ligne,ligne1){
	if(ligne==-1){
		document.getElementById(ligne1).style.display="";
		document.getElementById("ligneOQD0").style.display="";
		document.getElementById("BtnPlusOQD").style.display="none";
		document.getElementById("BtnPlusOQD0").style.display="";
	}
	else{
		if(ligne<99){
			document.getElementById("BtnPlusOQD"+ligne).style.display="none";
			document.getElementById("ligneOQD"+ligne1).style.display="";
			ligne2=parseInt(ligne1)+1;
			if(ligne2<99){
				if(document.getElementById("ligneOQD"+ligne2).style.display=="none"){
					
					document.getElementById("BtnPlusOQD"+ligne1).style.display="";
				}
			}
		}
		else{
			document.getElementById("BtnPlusOQD"+ligne).style.display="none";
			document.getElementById("ligneOQD"+ligne1).style.display="";
		}
	}
}
function MasquerOQD(ligne,ligne1){
	document.getElementById("ligneOQD"+ligne).style.display="none";
	
	document.getElementById("LibelleOQD"+ligne).value="";
	document.getElementById("objectifClientOQD"+ligne).value="";
	document.getElementById("objectifToleranceOQD"+ligne).value="";
	document.getElementById("nbLivrableConformeOQD"+ligne).value="";
	document.getElementById("nbLivrableToleranceOQD"+ligne).value="";
	document.getElementById("nbRetourClientOQD"+ligne).value="";
	document.getElementById("OQDRealise"+ligne).value="";
	document.getElementById("PasOQD"+ligne).checked=false;
	
	bAffiche=0;
	for(i=0;i<100;i++){
		if(document.getElementById("ligneOQD"+i).style.display==""){
			bAffiche=1;
		}
	}
	if(bAffiche==0){
		document.getElementById("BtnPlusOQD").style.display="";
		document.getElementById("blocOQD").style.display="none";
	}
	if(ligne1!=-1){
		document.getElementById("BtnPlusOQD"+ligne1).style.display="";
	}
	calculOQD2();
}
function EditFamille(Id){
	window.open("Edit_Famille.php?Id="+Id,"NewPage","status=no,menubar=no,width=500,height=500");
}
function CalculerTotalCharge(){
	var tabCol = new Array("M_1","M","M1","M2","M3","M4","M5","M6");
	var Elements = document.getElementsByClassName('interneExterne');
	visibleInterne=0;
	visibleExterne=0;
	if(document.getElementById('interneIndefini').style.display==""){
		visibleInterne++;
	}
	if(document.getElementById('externeIndefini').style.display==""){
		visibleExterne++;
	}
	for(var k=0, l=Elements.length; k<l; k++){
		if(document.getElementById(Elements[k].id).style.display==""){
			if(Elements[k].id.substr(0,7)=="interne"){
				visibleInterne++;
			}
			else{
				visibleExterne++;
			}
		}
	}
	for(var i= 0; i < tabCol.length; i++)
	{
		sommeInterne=0;
		sommeExterne=0;
		
		sommeInterneCapa=0;
		sommeExterneCapa=0;
		if(document.getElementById('interneIndefini'+tabCol[i]).value!=""){sommeInterne=Number(document.getElementById('interneIndefini'+tabCol[i]).value);}
		if(document.getElementById('externeIndefini'+tabCol[i]).value!=""){sommeExterne=Number(document.getElementById('externeIndefini'+tabCol[i]).value);}
		
		if(document.getElementById('interneIndefiniCapa'+tabCol[i]).value!=""){sommeInterneCapa=Number(document.getElementById('interneIndefiniCapa'+tabCol[i]).value);}
		if(document.getElementById('externeIndefiniCapa'+tabCol[i]).value!=""){sommeExterneCapa=Number(document.getElementById('externeIndefiniCapa'+tabCol[i]).value);}

		for(var k=0, l=Elements.length; k<l; k++){
			if(document.getElementById(Elements[k].id+tabCol[i]).value!=""){
				if(Elements[k].id.substr(0,7)=="interne" && Elements[k].id.includes('Capa')){
					sommeInterneCapa+=Number(document.getElementById(Elements[k].id+tabCol[i]).value);
				}
				else if(Elements[k].id.substr(0,7)=="interne"){
					sommeInterne+=Number(document.getElementById(Elements[k].id+tabCol[i]).value);
				}
				else if(Elements[k].id.substr(0,7)=="externe" && Elements[k].id.includes('Capa')){
					sommeExterneCapa+=Number(document.getElementById(Elements[k].id+tabCol[i]).value);
				}
				else if(Elements[k].id.substr(0,7)=="externe"){
					sommeExterne+=Number(document.getElementById(Elements[k].id+tabCol[i]).value);
				}
			}
		}
		
		document.getElementById('STinterne'+tabCol[i]).value=sommeInterne;
		document.getElementById('STexterne'+tabCol[i]).value=sommeExterne;
		document.getElementById('Total'+tabCol[i]).value=sommeInterne+sommeExterne;
		
		document.getElementById('STinterneCapa'+tabCol[i]).value=sommeInterneCapa;
		document.getElementById('STexterneCapa'+tabCol[i]).value=sommeExterneCapa;
		document.getElementById('TotalCapa'+tabCol[i]).value=sommeInterneCapa+sommeExterneCapa;
	}
	if(visibleInterne>1){document.getElementById('STinterne').style.display="";document.getElementById('STinterneCapa').style.display="";}
	else{document.getElementById('STinterne').style.display="none";document.getElementById('STinterneCapa').style.display="none";}
	if(visibleExterne>1){document.getElementById('STexterne').style.display="";document.getElementById('STexterneCapa').style.display="";}
	else{document.getElementById('STexterne').style.display="none";document.getElementById('STexterneCapa').style.display="none";}
		
		
}

function afficherToutesPersonnes(){
	if(document.getElementById('afficherLesPersonnes').checked==false){
		for(i=1;i<=4;i++){
			for(var k=0, l=document.getElementById('personne'+i).options.length; k<l; k++){
				if(document.getElementById('personne'+i).options[k].id.substr(0,1)=="0"){
					document.getElementById('personne'+i).options[k].style.display="none";
				}
				else{
					document.getElementById('personne'+i).options[k].style.display="";
				}
			}
		}
	}
	else{
		for(i=1;i<=4;i++){
			for(var k=0, l=document.getElementById('personne'+i).options.length; k<l; k++){
				document.getElementById('personne'+i).options[k].style.display="";
			}
		}
	}
}

function AfficherChargeCapa(){
	var tabCol = new Array("M","M1","M2","M3","M4","M5","M6");
	var Elements = document.getElementsByClassName('interne');
	visibleInterne=0;
	visibleExterne=0;
	if(opener.document.getElementById('interneIndefini').style.display==""){
		visibleInterne++;
	}
	if(opener.document.getElementById('externeIndefini').style.display==""){
		visibleExterne++;
	}
	for(var k=0, l=Elements.length; k<l; k++){
		if(Elements[k].checked){
			opener.document.getElementById(Elements[k].name).style.display="";
			opener.document.getElementById(Elements[k].name+"Capa").style.display="";
		}
		else{
			//Vérifier si des données sont remplis pour cette famille, dans ce cas on ne peut pas masquer la ligne
			somme=0;
			for(var i= 0; i < tabCol.length; i++){
				if(opener.document.getElementById(Elements[k].id+tabCol[i]).value!="" || opener.document.getElementById(Elements[k].id+"Capa"+tabCol[i]).value!=""){
					if(Elements[k].id.substr(0,7)=="interne"){
						somme+=Number(opener.document.getElementById(Elements[k].id+tabCol[i]).value)+Number(opener.document.getElementById(Elements[k].id+"Capa"+tabCol[i]).value);
					}
				}
			}
			if(somme==0){
				opener.document.getElementById(Elements[k].name).style.display="none";
				opener.document.getElementById(Elements[k].name+"Capa").style.display="none";
			}
			else{
				Elements[k].checked=true;
			}
		}
	}
	var Elements = document.getElementsByClassName('externe');
	for(var k=0, l=Elements.length; k<l; k++){
		if(Elements[k].checked){
			opener.document.getElementById(Elements[k].name).style.display="";
			opener.document.getElementById(Elements[k].name+"Capa").style.display="";
		}
		else{
			//Vérifier si des données sont remplis pour cette famille, dans ce cas on ne peut pas masquer la ligne
			somme=0;
			for(var i= 0; i < tabCol.length; i++){
				if(opener.document.getElementById(Elements[k].id+tabCol[i]).value!="" || opener.document.getElementById(Elements[k].id+"Capa"+tabCol[i]).value!=""){
					if(Elements[k].id.substr(0,7)=="externe"){
						somme+=Number(opener.document.getElementById(Elements[k].id+tabCol[i]).value)+Number(opener.document.getElementById(Elements[k].id+"Capa"+tabCol[i]).value);
					}
				}
			}
			if(somme==0){
				opener.document.getElementById(Elements[k].name).style.display="none";
				opener.document.getElementById(Elements[k].name+"Capa").style.display="none";
			}
			else{
				Elements[k].checked=true;
			}
		}
	}
	
	var Elements = opener.document.getElementsByClassName('interneExterne');
	for(var k=0, l=Elements.length; k<l; k++){
		if(opener.document.getElementById(Elements[k].id).style.display==""){
			if(Elements[k].id.substr(0,7)=="interne"){
				visibleInterne++;
			}
			else{
				visibleExterne++;
			}
		}
	}
	if(visibleInterne>1){opener.document.getElementById('STinterne').style.display="";opener.document.getElementById('STinterneCapa').style.display="";}
	else{opener.document.getElementById('STinterne').style.display="none";opener.document.getElementById('STinterneCapa').style.display="none";}
	if(visibleExterne>1){opener.document.getElementById('STexterne').style.display="";opener.document.getElementById('STexterneCapa').style.display="";}
	else{opener.document.getElementById('STexterne').style.display="none";opener.document.getElementById('STexterneCapa').style.display="none";}
}
function CocherFamilles(){
	var Elements = document.getElementsByClassName('interne');
	for(var k=0, l=Elements.length; k<l; k++){
		if(opener.document.getElementById(Elements[k].name).style.display==""){
			Elements[k].checked=true;
		}
		else{
			Elements[k].checked=false;
		}
	}
	var Elements = document.getElementsByClassName('externe');
	for(var k=0, l=Elements.length; k<l; k++){
		if(opener.document.getElementById(Elements[k].name).style.display==""){
			Elements[k].checked=true;
		}
		else{
			Elements[k].checked=false;
		}
	}
}
function AfficherGraphCompetence(Num){
	if(Num==1){
		document.getElementById('chart_Competences').style.display="";
		document.getElementById('chart_Competences2').style.display="none";
	}
	else{
		document.getElementById('chart_Competences').style.display="none";
		document.getElementById('chart_Competences2').style.display="";
	}
	
}

function AfficherGraphOTDOQD(OtdOqd,Num){
	if(Num==1){
		document.getElementById('chart_'+OtdOqd).style.display="";
		document.getElementById('chart_'+OtdOqd+'V2').style.display="none";
	}
	else{
		document.getElementById('chart_'+OtdOqd).style.display="none";
		document.getElementById('chart_'+OtdOqd+'V2').style.display="";
	}
	
}