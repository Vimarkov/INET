issetfocus="";
ListeTypeContrat = new Array();
ListeAgenceInterim = new Array();
Liste_PrestaPole = new Array();
Liste_TAG = new Array();
Liste_SMH = new Array();
Liste_FicheEmploi = new Array();
ListeMetier = new Array();
Liste_TempsTravail = new Array();
function Afficher_Agence(){
	var estInterim=0;
	formulaire.agenceInterim.value="0";
	formulaire.typeCoeff.value="Gestion";
	formulaire.coeffFacturation.value="";
	formulaire.salaireMensuel.value="";
	formulaire.tauxHoraire.value="";
	
	var elements = document.getElementsByClassName('agence');
	for (i=0; i<elements.length; i++){
	  elements[i].style.display='none';
	}
	
	var elements = document.getElementsByClassName('salarie');
	for (i=0; i<elements.length; i++){
	  elements[i].style.display='';
	}
	
	for(i=0;i<ListeTypeContrat.length;i++){
		if (ListeTypeContrat[i][0]==document.getElementById('typeContrat').value){
			if (ListeTypeContrat[i][1] == "1"){
				var elements = document.getElementsByClassName('agence');
				for (k=0; k<elements.length; k++){
				  elements[k].style.display='';
				}
				
				var elements = document.getElementsByClassName('salarie');
				for (k=0; k<elements.length; k++){
				  elements[k].style.display='none';
				}
			}
		}
	}
}
function selectionClassif(){
	for(i=0;i<ListeMetier.length;i++){
		if (ListeMetier[i][0]==document.getElementById('metier').value){
			document.getElementById('classficiationMetier').value=ListeMetier[i][1];
		}
	}
	RechargerListeTAG();
}
function selectionClassif2(){
	for(i=0;i<ListeMetier.length;i++){
		if (ListeMetier[i][0]==document.getElementById('metier').value){
			document.getElementById('classficiationMetier').value=ListeMetier[i][1];
		}
	}
	ModifierCouleurChamps('id_Metier','metier','LibelleMetier');
	RechargerListeTAG3();
}
function RechargerListeTAG(){
	bTrouve=false;
	select="<select name='niveauCoeffEchlon' id='niveauCoeffEchlon' onchange='RechercherSalaire()'>";
	for(i=0;i<Liste_TAG.length;i++){
		if (Liste_TAG[i][3]==document.getElementById('classficiationMetier').value && Liste_TAG[i][4]>0){
			select+="<option value='"+Liste_TAG[i][0]+";"+Liste_TAG[i][1]+";"+Liste_TAG[i][2]+"'>"+Liste_TAG[i][0]+" - "+Liste_TAG[i][1]+" - "+Liste_TAG[i][2]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){
		select+="<option value='0;0;0'></option>";
	}
	select+="</select>";
	document.getElementById('Div_niveauCoeffEchlon').innerHTML=select;
	RechercherSalaire();
}

function RechargerListeTAG3(){
	bTrouve=false;
	select="<select name='niveauCoeffEchlon' id='niveauCoeffEchlon' onchange='RechercherSalaire()'>";
	for(i=0;i<Liste_TAG.length;i++){
		if (Liste_TAG[i][3]==document.getElementById('classficiationMetier').value && Liste_TAG[i][4]>0){
			select+="<option value='"+Liste_TAG[i][0]+";"+Liste_TAG[i][1]+";"+Liste_TAG[i][2]+"'>"+Liste_TAG[i][0]+" - "+Liste_TAG[i][1]+" - "+Liste_TAG[i][2]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){
		select+="<option value='0;0;0'></option>";
	}
	select+="</select>";
	document.getElementById('Div_niveauCoeffEchlon').innerHTML=select;
	
	ModifierCouleurChamps('id_classficiationMetier','classficiationMetier','LibelleclassficiationMetier');
	
	RechercherSalaire2();
}

function RechargerListeTAG2(){
	bTrouve=false;
	select="<select name='niveauCoeffEchlon' id='niveauCoeffEchlon' onchange='RechercherSalaire2()'>";
	for(i=0;i<Liste_TAG.length;i++){
		if (Liste_TAG[i][3]==document.getElementById('classficiationMetier').value && Liste_TAG[i][4]>0){
			selected="";
			if(document.getElementById('Old_NiveauCoeffEchelon').value==Liste_TAG[i][0]+";"+Liste_TAG[i][1]+";"+Liste_TAG[i][2]){selected="selected";}
			select+="<option value='"+Liste_TAG[i][0]+";"+Liste_TAG[i][1]+";"+Liste_TAG[i][2]+"' "+selected+">"+Liste_TAG[i][0]+" - "+Liste_TAG[i][1]+" - "+Liste_TAG[i][2]+"</option>";
			bTrouve=true;
		}
	}
	if(bTrouve==false){
		select+="<option value='0;0;0'></option>";
	}
	select+="</select>";
	document.getElementById('Div_niveauCoeffEchlon').innerHTML=select;
	document.getElementById('niveauCoeffEchlon').value=document.getElementById('Old_NiveauCoeffEchelon').value;
	
}

function RechercherSalaire2(){
	for(i=0;i<Liste_TAG.length;i++){
		if (Liste_TAG[i][0]+";"+Liste_TAG[i][1]+";"+Liste_TAG[i][2]+";"+Liste_TAG[i][3]==document.getElementById('niveauCoeffEchlon').value+";"+document.getElementById('classficiationMetier').value){
			document.getElementById('salaireRef').value=Liste_TAG[i][4];
		}
	}
	ModifierCouleurChamps('id_niveauCoeffEchlon','niveauCoeffEchlon','LibelleniveauCoeffEchlon');
	ModifierCouleurChamps('id_salaireRef','salaireRef','LibelleSalaireRef');
	
	CompareSalaire();
}

function RechercherSalaire(){
	for(i=0;i<Liste_TAG.length;i++){
		if (Liste_TAG[i][0]+";"+Liste_TAG[i][1]+";"+Liste_TAG[i][2]+";"+Liste_TAG[i][3]==document.getElementById('niveauCoeffEchlon').value+";"+document.getElementById('classficiationMetier').value){
			document.getElementById('salaireRef').value=Liste_TAG[i][4];
		}
	}
	
	CompareSalaire();
}
function Afficher_CoeffFacturation(){
	formulaire.coeffFacturation.value="";

	for(i=0;i<ListeAgenceInterim.length;i++){
		if (ListeAgenceInterim[i][0]==document.getElementById('agenceInterim').value){
			if(formulaire.typeCoeff.value=="Gestion"){
				formulaire.coeffFacturation.value=ListeAgenceInterim[i][1];
			}
			else{
				formulaire.coeffFacturation.value=ListeAgenceInterim[i][2];
			}
		}
	}
	
}
function FiltrerPrestationPole(){
	var bTrouve = false;
	var selPresta="<select name='prestationPole' id='prestationPole' style='width:400px'>";
	selPresta= selPresta + "<option value='0_0'></option>";
	for(i=0;i<Liste_PrestaPole.length;i++){
		if (Liste_PrestaPole[i][4]==document.getElementById('plateforme').value || document.getElementById('plateforme').value==0){
			selPresta= selPresta + "<option value='"+Liste_PrestaPole[i][0]+"_"+Liste_PrestaPole[i][1]+"_"+document.getElementById('plateforme').value;
			pole="";
			if(Liste_PrestaPole[i][1]!="0"){
				pole=" - "+Liste_PrestaPole[i][3];
			}
			selectedPresta="";
			if(document.getElementById('id_prestationPole').value==Liste_PrestaPole[i][0]+"_"+Liste_PrestaPole[i][1]+"_"+document.getElementById('plateforme').value){selectedPresta= "selected";}
			selPresta= selPresta + "' "+selectedPresta+" >"+Liste_PrestaPole[i][2]+pole+"</option>";
			bTrouve=true;
		}
	}
	selPresta =selPresta + "</select>";
	document.getElementById('prestationPole').innerHTML=selPresta;
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

function Enregistrer(){
	var valide = true;
	var estInterim = false;
	for(i=0;i<ListeTypeContrat.length;i++){
		if (ListeTypeContrat[i][0]==document.getElementById('typeContrat').value){
			if (ListeTypeContrat[i][1] == "1"){
				estInterim= true;
			}
		}
	}
	
	if(document.getElementById('Langue').value=="FR"){
		if(formulaire.metier.value=="0"){valide=false;alert("Veuillez renseigner le métier");return false;}
		if(formulaire.typeContrat.value=="0"){valide=false;alert("Veuillez renseigner le type de contrat");return false;}
		if(estInterim==true){
			if(formulaire.agenceInterim.value=="0"){valide=false;alert("Veuillez renseigner l\'agence d\'intérim");return false;}
			if(formulaire.coeffFacturation.value==""){valide=false;alert("Veuillez renseigner le coeff");return false;}
			if(formulaire.tauxHoraire.value==""){valide=false;alert("Veuillez renseigner le taux horaire");return false;}
		}
		if(formulaire.niveauCoeffEchlon.value==""){valide=false;alert("Veuillez renseigner le niveau - coeff - échelon");return false;}
		if(formulaire.ficheEmploi.value=="0"){valide=false;alert("Veuillez renseigner la fiche emploi");return false;}
		if(formulaire.cotation.value==""){valide=false;alert("Veuillez renseigner la cotation");return false;}
		if(formulaire.salaireRef.value==""){valide=false;alert("Veuillez renseigner le salaire de référence");return false;}
		if(formulaire.smh.value==""){valide=false;alert("Veuillez renseigner le salaire de référence 2024");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez renseigner la date de début");return false;}
		if(formulaire.tempsTravail.value=="0"){valide=false;alert("Veuillez renseigner le temps de travail");return false;}
		if(formulaire.lieuTravail.value=="0"){valide=false;alert("Veuillez renseigner le lieu de travail");return false;}
		if(formulaire.prestationPole.value=="0"){valide=false;alert("Veuillez renseigner la prestation d\'affectation");return false;}
	}
	else{
		if(formulaire.metier.value=="0"){valide=false;alert("Please fill in the job");return false;}
		if(formulaire.typeContrat.value=="0"){valide=false;alert("Please enter the type of contract");return false;}
		if(estInterim==true){
			if(formulaire.agenceInterim.value=="0"){valide=false;alert("Please inform the interim agency");return false;}
			if(formulaire.coeffFacturation.value==""){valide=false;alert("Please inform the coeff");return false;}
			if(formulaire.tauxHoraire.value==""){valide=false;alert("Please enter the hourly rate");return false;}
		}
		if(formulaire.niveauCoeffEchlon.value==""){valide=false;alert("Please enter the level - coeff - echelon");return false;}
		if(formulaire.ficheEmploi.value=="0"){valide=false;alert("Please fill in the job form");return false;}
		if(formulaire.cotation.value==""){valide=false;alert("Please fill in the quotation");return false;}
		if(formulaire.salaireRef.value==""){valide=false;alert("Please enter the reference salary");return false;}
		if(formulaire.smh.value==""){valide=false;alert("Please enter the reference salary 2024");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Please enter the start date");return false;}
		if(formulaire.tempsTravail.value=="0"){valide=false;alert("Please fill in the working time");return false;}
		if(formulaire.lieuTravail.value=="0"){valide=false;alert("Please fill in the place of work");return false;}
		if(formulaire.prestationPole.value=="0"){valide=false;alert("Please fill in the assignment service");return false;}
	}
	
	if(formulaire.Mode.value=="M"){
		if(formulaire.id_dateFin.value==""){
			if(formulaire.dateFin.value!=""){
				if(document.getElementById('Langue').value=="FR"){
					question="Voulez-vous appliquer cette date de fin à tous les contrats en cours ?";
				}
				else{
					question="Do you want to apply this end date to all current contracts ?";
				}
				if(window.confirm(question)){
					formulaire.AppliquerAuxAutresContrats.value="1";
				}
				else{
					formulaire.AppliquerAuxAutresContrats.value="0";
				}
			}
		}
	}
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function FermerEtRecharger(Menu,Id_Personne,Page)
{
	if(Page=="Liste_ContratHistorique"){
		window.opener.location="Liste_ContratHistorique.php?Menu="+Menu+"&Id_Personne="+Id_Personne;
		window.close();
	}
	else{
		window.opener.location="Liste_ContratEC.php?Menu="+Menu;
		window.close();
		
	}
}

function Recharger(Menu,Id_Personne,Page)
{
	if(Page=="Liste_ContratHistorique"){
		window.opener.location="Liste_ContratHistorique.php?Menu="+Menu+"&Id_Personne="+Id_Personne;
	}
	else{
		window.opener.location="Liste_ContratEC.php?Menu="+Menu;
	}
}

function FermerEtRechargerODM(Menu,Id_Personne,Page)
{
	if(Page=="Liste_ContratHistorique"){
		window.opener.location="Liste_ContratHistorique.php?Menu="+Menu+"&Id_Personne="+Id_Personne;
		window.close();

	}
	else if(Page=="Liste_DODM"){
		window.opener.location="Liste_DODM.php?Menu="+Menu;
		window.close();
		
	}
	else{
		window.opener.location="Liste_ODMEC.php?Menu="+Menu;
		window.close();
		
	}
}

function Afficher_AgenceV2(ChampsHidden,Champs,LibelleChamps){
	Afficher_Agence();
	ModifierCouleurChamps(ChampsHidden,Champs,LibelleChamps);
	
}
function Afficher_CoeffFacturationV2(ChampsHidden,Champs,LibelleChamps){
	Afficher_CoeffFacturation();
	ModifierCouleurChamps(ChampsHidden,Champs,LibelleChamps);
	ModifierCouleurChamps('id_typeCoeff','typeCoeff','LibelleTypeCoeff');
	ModifierCouleurChamps('id_coeffFacturation','coeffFacturation','LibelleCoeffFacturation');
	
}
function FiltrerPrestationPoleV2(ChampsHidden,Champs,LibelleChamps){
	FiltrerPrestationPole();
	ModifierCouleurChamps(ChampsHidden,Champs,LibelleChamps);
	ModifierCouleurChamps('id_prestationPole','prestationPole','LibellePrestationPole');
	
}
function ModifierCouleurChamps(ChampsHidden,Champs,LibelleChamps)
{
	valeur="";
	if(Champs=="metier"){valeur="Id_Metier";}
	else if(Champs=="typeContrat"){valeur="Id_TypeContrat";}
	else if(Champs=="agenceInterim"){valeur="Id_AgenceInterim";}
	else if(Champs=="typeCoeff"){valeur="TypeCoeff";}
	else if(Champs=="coeffFacturation"){valeur="CoeffFacturationAgence";}
	else if(Champs=="salaireRef"){valeur="SalaireReference";}
	else if(Champs=="SMHReference"){valeur="SMHReference";}
	else if(Champs=="salaireMensuel"){valeur="SalaireBrut";}
	else if(Champs=="tauxHoraire"){valeur="TauxHoraire";}
	else if(Champs=="dateDebut"){valeur="DateDebut";}
	else if(Champs=="dateFin"){valeur="DateFin";}
	else if(Champs=="dateFinPeriodeEssai"){valeur="DateFinPeriodeEssai";}
	else if(Champs=="dateSouplesseNegative"){valeur="DateSouplesseNegative";}
	else if(Champs=="dateSouplessePositive"){valeur="DateSouplessePositive";}
	else if(Champs=="prestationPole"){valeur="Id_Prestation";}
	else if(Champs=="client"){valeur="Id_Client";}
	else if(Champs=="remarque"){valeur="Remarque";}
	else if(Champs=="client"){valeur="Id_Client";}
	else if(Champs=="responsableAAA"){valeur="Id_Responsable";}
	else if(Champs=="descriptionMission"){valeur="Motif";}
	else if(Champs=="indemniteDeplacement"){valeur="MontantIPD";}
	else if(Champs=="indemniteRepas"){valeur="MontantRepas";}
	else if(Champs=="indemniteIGD"){valeur="MontantIGD";}
	else if(Champs=="indemniteRepasGD"){valeur="MontantRepasGD";}
	else if(Champs=="fraisReel"){valeur="FraisReel";}
	else if(Champs=="indemniteOutillage"){valeur="IndemniteOutillage";}
	else if(Champs=="panierGD"){valeur="PanierGrandeNuit";}
	else if(Champs=="majorationVSD"){valeur="MajorationVSD";}
	else if(Champs=="panierVSD"){valeur="PanierVSD";}
	
	if(document.getElementById(ChampsHidden).value==document.getElementById(Champs).value){
		document.getElementById(LibelleChamps).style.backgroundColor="#ffffff";
		if(document.getElementById('ChampsModifies').value.indexOf(";"+valeur+"_")!=-1){
			document.getElementById('ChampsModifies').value = document.getElementById('ChampsModifies').value.replace(";"+valeur+"_","");
		}
	}
	else{
		document.getElementById(LibelleChamps).style.backgroundColor="#baf37f";
		
		if(document.getElementById('ChampsModifies').value.indexOf(";"+valeur+"_")==-1){
			document.getElementById('ChampsModifies').value+=";"+valeur+"_";
		}
	}
	if(Champs=="salaireMensuel" || Champs=="salaireRef" || Champs=="tempsTravail"){
		CompareSalaire();
		ComparerSalaireSMH();
	}
}

function EnregistrerODM(){
	var valide = true;

	if(document.getElementById('Langue').value=="FR"){
		if(formulaire.metier.value=="0"){valide=false;alert("Veuillez renseigner le métier");return false;}
		if(formulaire.prestationPole.value=="0"){valide=false;alert("Veuillez renseigner la prestation d\'affectation");return false;}
		if(formulaire.responsableAAA.value=="0"){valide=false;alert("Veuillez renseigner le nom du responsable AAA");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez renseigner la date de début");return false;}
		if(formulaire.indemniteDeplacement.value==""){valide=false;alert("Veuillez renseigner le montant d\'indemnité déplacement");return false;}
		if(formulaire.indemniteRepas.value==""){valide=false;alert("Veuillez renseigner le montant d\'indemnité repas");return false;}
	}
	else{
		if(formulaire.metier.value=="0"){valide=false;alert("Please fill in the job");return false;}
		if(formulaire.prestationPole.value=="0"){valide=false;alert("Please fill in the assignment service");return false;}
		if(formulaire.responsableAAA.value=="0"){valide=false;alert("Please fill in the name of the AAA manager");return false;}
		if(formulaire.dateDebut.value==""){valide=false;alert("Please enter the start date");return false;}
		if(formulaire.indemniteDeplacement.value==""){valide=false;alert("Please enter the amount of the travel allowance");return false;}
		if(formulaire.indemniteRepas.value==""){valide=false;alert("Please enter the amount of meal allowance");return false;}
		
	}
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function EnregistrerODMCommun(){
	var valide = true;

	if(document.getElementById('Langue').value=="FR"){
		if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez renseigner la date de début");return false;}
		if(formulaire.dateFin.value==""){valide=false;alert("Veuillez renseigner la date de fin");return false;}
		if(formulaire.indemniteRepas.value==""){valide=false;alert("Veuillez renseigner le montant d\'indemnité repas");return false;}
		if(formulaire.primeEquipe.value==""){valide=false;alert("Veuillez renseigner la prime d\'équipe");return false;}
	}
	else{
		if(formulaire.dateDebut.value==""){valide=false;alert("Please enter the start date");return false;}
		if(formulaire.dateFin.value==""){valide=false;alert("Please enter the end date");return false;}
		if(formulaire.indemniteRepas.value==""){valide=false;alert("Please enter the amount of meal allowance");return false;}
		if(formulaire.primeEquipe.value==""){valide=false;alert("Please enter the team bonus");return false;}
		
	}
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function CompareSalaire(){
	var SalaireReference=document.getElementById('salaireRef').value;
	var SalaireMensuel=document.getElementById('salaireMensuel').value;
	var TempsTravail=0;

	document.getElementById('ErreurSalaireRef').style.display="none";
	if(SalaireReference!="" && SalaireMensuel!=""){
		
		
		for(i=0;i<Liste_TempsTravail.length;i++){
			if (Liste_TempsTravail[i][0]==document.getElementById('tempsTravail').value){
				TempsTravail=Liste_TempsTravail[i][1];
			}
		}
		if(TempsTravail!=151.67 && TempsTravail!=0 && TempsTravail!=""){
			SalaireReference=((SalaireReference/12)/151.67)*TempsTravail;
		}
		else{
			SalaireReference=(SalaireReference/12);
		}
		if(SalaireMensuel<SalaireReference){
			document.getElementById('ErreurSalaireRef').style.display="";
		}
	}
}
function ContratExcel(Id)
	{window.open("Export_Contrat.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
function ODMExcel(Id)
{window.open("Export_ODM.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}

function ajouter(){
	for(y=0;y<document.getElementById('Id_Personne').length;y++)
	{
		if(document.getElementById('Id_Personne').options[y].selected == true)
		{
			nouvel_element = new Option(document.getElementById('Id_Personne').options[y].text,document.getElementById('Id_Personne').options[y].value,false,false);
			document.getElementById('PersonneSelect').options[document.getElementById('PersonneSelect').length] = nouvel_element;
			document.getElementById('Id_Personne').options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById('PersonneSelect')
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array()
		Liste[i][0]=Obj.options[i].text
		Liste[i][1]=Obj.options[i].value
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0]
		Obj.options[i].value=Liste[i][1]
	}
}

function effacer(){
	for(y=0;y<document.getElementById('PersonneSelect').length;y++)
	{
		if(document.getElementById('PersonneSelect').options[y].selected == true)
		{
			nouvel_element = new Option(document.getElementById('PersonneSelect').options[y].text,document.getElementById('PersonneSelect').options[y].value,false,false);
			document.getElementById('Id_Personne').options[document.getElementById('Id_Personne').length] = nouvel_element;
			document.getElementById('PersonneSelect').options[y] = null;
		}
	}
	
	Liste= new Array();
	Obj= document.getElementById('Id_Personne')
	 
	for(i=0;i<Obj.options.length;i++){
		Liste[i]=new Array()
		Liste[i][0]=Obj.options[i].text
		Liste[i][1]=Obj.options[i].value
	}
	Liste=Liste.sort()
	 
	for(i=0;i<Obj.options.length;i++){
		Obj.options[i].text=Liste[i][0]
		Obj.options[i].value=Liste[i][1]
	}
}

function selectall(){
	if(document.getElementById('Langue').value=="FR"){
		if(document.getElementById('PersonneSelect').length==0){alert("Veuillez sélectionner au moins une personne.");return false;}
		if(document.getElementById('dateDebut').value==""){alert("Veuillez remplir une date de début.");return false;}
		if(formulaire.tempsTravail.value=="0"){valide=false;alert("Veuillez renseigner le temps de travail");return false;}
	}
	else{
		if(document.getElementById('PersonneSelect').length==0){alert("Please select at least one person.");return false;}
		if(document.getElementById('dateDebut').value==""){alert("Please fill in the start date.");return false;}
		if(formulaire.tempsTravail.value=="0"){valide=false;alert("Please fill in the working time");return false;}

	}
	for(y=0;y<document.getElementById('PersonneSelect').length;y++){
		document.getElementById('PersonneSelect').options[y].selected = true;
	}
	
	var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
	document.getElementById('Ajouter').innerHTML=bouton;
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
	document.getElementById("btnEnregistrer2").dispatchEvent(evt);
	document.getElementById('Ajouter').innerHTML="";
}

function RechargerCotation(){
	cotation="";
	for(i=0;i<Liste_FicheEmploi.length;i++){
		if (Liste_FicheEmploi[i][0]==document.getElementById('ficheEmploi').value){
			cotation=Liste_FicheEmploi[i][1];
		}
	}
	document.getElementById("cotation").value = cotation;
	RechargerSMH();
}

function RechargerSMH(){
	salaire="";
	for(i=0;i<Liste_SMH.length;i++){
		if (Liste_SMH[i][0]==document.getElementById('cotation').value){
			salaire=Liste_SMH[i][1];
		}
	}
	document.getElementById("smh").value = salaire;
	ComparerSalaireSMH();
}

function ComparerSalaireSMH(){
	var SalaireReference=document.getElementById('smh').value;
	var SalaireMensuel=document.getElementById('salaireMensuel').value;
	var TempsTravail=0;

	document.getElementById('ErreurSMHRef').style.display="none";
	if(SalaireReference!="" && SalaireMensuel!=""){
		
		
		for(i=0;i<Liste_TempsTravail.length;i++){
			if (Liste_TempsTravail[i][0]==document.getElementById('tempsTravail').value){
				TempsTravail=Liste_TempsTravail[i][1];
			}
		}
		if(TempsTravail!=151.67 && TempsTravail!=0 && TempsTravail!=""){
			SalaireReference=((SalaireReference/12)/151.67)*TempsTravail;
		}
		else{
			SalaireReference=(SalaireReference/12);
		}
		if(SalaireMensuel<SalaireReference){
			document.getElementById('ErreurSMHRef').style.display="";
		}
	}
}