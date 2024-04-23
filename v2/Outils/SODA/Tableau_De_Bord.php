<?php
require("../../Menu.php");
?>
<script src="Corriger_Questionnaire6.js?t=<?php echo time(); ?>"></script>
<script>
	function reset2(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnReset2' name='btnReset2' value='Reset'>";
		document.getElementById('reset').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnReset2").dispatchEvent(evt);
		document.getElementById('reset').innerHTML="";
	}
	function OuvreFenetreExcel(Page){
		window.open("Export_"+Page+".php","PageExcel","status=no,menubar=no,width=50,height=50");
	}
	function SelectionnerTout(Champ)
	{	
		if(Champ=="ThemeQuestionnaire"){
			var elements = document.getElementsByClassName("checkTheme");
			if (document.getElementById('selectAll'+Champ).checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){
					elements[i].checked = true;
					laValeur=elements[i].value.substr(6);
					CocheQuestionnaire(laValeur);
				}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){
					elements[i].checked = false;
					laValeur=elements[i].value.substr(6);
					CocheQuestionnaire(laValeur);
				}
			}
		}
		else{
			var elements = document.getElementsByClassName("check"+Champ);
			if (document.getElementById('selectAll'+Champ).checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
			}
			if(Champ=="UER" || Champ=="RP"){
				Selectionner("Prestation");
			}
		}
	}
	function OuvreFenetreAjout(Page){
		if(Page=="Ajout_Surveillant.php"){
			var w=window.open(Page+"?Mode=A&Id=0","Page","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
		}
		else{
			var w=window.open(Page+"?Mode=A&Id=0","Page","status=no,menubar=no,scrollbars=yes,width=600,height=150");
			w.focus();
		}
	}
	function OuvreFenetreSuppr(Page,Id){
		if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open(Page+"?Mode=S&Id="+Id,"Page","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
	}
	function OuvreFenetreSupprSurveillancePlanifiee(Page,Id,Volume){
		if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open(Page+"?Mode=S&Id="+Id+"&Volume="+Volume,"Page","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
	}
	function OuvreFenetreModif(Page,Mode,Id)
	{
		var Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr" || Mode=="S"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr" || Mode=="S"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if(((Mode=="Suppr" || Mode=="S") && Confirm==true) || Mode=="Ajout" || Mode=="Modif" || Mode=="A" || Mode=="M" || Mode=="D" || Mode=="V")
		{
			if(Page=='LancerSurveillance.php'){
				var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=1200,height=550");
			}
			else if(Page=='Ajout_Question.php'){
				var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=1400,height=700");
			}
			else if(Page=='Modif_Surveillance.php'){
				var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=1200,height=700");
			}
			else if(Page=='Questions_Additionnelles.php'){
				var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=1400,height=700");
			}
			else if(Page=='ChoisirSurveillant.php'){
				var elements = document.getElementsByClassName("checkSurveillant");
				Id_Surveillants="";
				for(var i=0, l=elements.length; i<l; i++){
					if(elements[i].checked ==true){
						if(Id_Surveillants!=""){Id_Surveillants=Id_Surveillants+",";}
						Id_Surveillants=Id_Surveillants+elements[i].value;
					}
				}
				if(Id_Surveillants!=""){
					var w= window.open(Page+"?Mode="+Mode+"&Id="+Id_Surveillants,"PageTheme","status=no,menubar=no,width=400,height=300");
				}
			}
			else{
				var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=900,height=550");
			}
			w.focus();
		}
	}
	function OuvreFenetreModifQuestion(Page,Mode,Id)
	{
		var Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr" || Mode=="S"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr" || Mode=="S"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if(((Mode=="Suppr" || Mode=="S") && Confirm==true) || Mode=="Ajout" || Mode=="Modif" || Mode=="A" || Mode=="M" || Mode=="D")
		{
			var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=750,height=550");
			w.focus();
		}
	}
	function OuvreFenetrePlanningExport(Id_Plateforme,Id_Prestation,lDate,lDateFin)
	{
		window.open("PlanningSurveillance_Export.php?Id_Prestation="+Id_Prestation+"&lDate="+lDate+"&lDateFin="+lDateFin+"&Id_Plateforme="+Id_Plateforme,"PagePlanningExport","status=no,menubar=no,scrollbars=1,width=90,height=40");
	}
	function OuvreFenetrePlannifManuelle(Annee,Semaine,Id_Theme,Id_Questionnaire)
	{
		var w=window.open("PlannifManuelle.php?Annee="+Annee+"&Semaine="+Semaine+"&Id_Theme="+Id_Theme+"&Id_Questionnaire="+Id_Questionnaire,"PagePlannif","status=no,menubar=no,width=1200,height=350");
		w.focus();
	}
	function OuvreFenetrePlannifManuelleP(Annee,Semaine,Id_Theme,Id_Questionnaire)
	{
		var w=window.open("PlannifManuelleProcessus.php?Annee="+Annee+"&Semaine="+Semaine+"&Id_Theme="+Id_Theme+"&Id_Questionnaire="+Id_Questionnaire,"PagePlannif","status=no,menubar=no,width=800,height=350");
		w.focus();
	}
	function OuvreFenetrePlannifAuto(Id_Theme,Id_Questionnaire)
	{
		var w=window.open("PlannifAuto.php?Id_Theme="+Id_Theme+"&Id_Questionnaire="+Id_Questionnaire,"PagePlannif","status=no,menubar=no,width=1300,height=350");
		w.focus();
	}
	function VerifChampsSurveillance()
	{
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.Id_Prestation.value=='0'){alert('Vous n\'avez pas renseigné la prestation.');return false;}
			if(formulaire.Id_Surveille.value=='0'){alert('Vous n\'avez pas renseigné le surveillé.');return false;}
			if(formulaire.Id_Metier.value=='0'){alert('Vous n\'avez pas renseigné le métier.');return false;}
			if(formulaire.nbQuestions2.value=='' || formulaire.nbQuestions2.value=='0'){alert('Vous n\'avez pas renseigné de sous-thématique.');return false;}
			return true;
		}
		else{
			if(formulaire.Id_Prestation.value=='0'){alert('You did not fill in the site.');return false;}
			if(formulaire.Id_Surveille.value=='0'){alert('You have not informed the monitored.');return false;}
			if(formulaire.Id_Metier.value=='0'){alert('You did not fill in the profession.');return false;}
			if(formulaire.nbQuestions2.value=='' || formulaire.nbQuestions2.value=='0'){alert('You have not entered a sub-theme.');return false;}
			return true;
		}
	}
	function VerifChampsSurveillanceP()
	{
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.nbQuestions2.value=='' || formulaire.nbQuestions2.value=='0'){alert('Vous n\'avez pas renseigné de sous-thématique.');return false;}
			return true;
		}
		else{
			if(formulaire.nbQuestions2.value=='' || formulaire.nbQuestions2.value=='0'){alert('You have not entered a sub-theme.');return false;}
			return true;
		}
	}
	function valider(id,valider)
	{
		var w=window.open("QuestioNonApplicable_Valider.php?Id="+id+"&Valider="+valider, "PageQCMValider", "width=500,height=350");
		w.focus();
	}
	function SurveillancePDF(Id)
	{
		var w=window.open("SurveillancePDF.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=900,height=600");
		w.focus();
	}
	function SurveillanceExcel(Id)
	{
		var w=window.open("SurveillanceExcel.php?Id="+Id,"PageExcel"+Id,"status=no,menubar=no,scrollbars=1,width=90,height=60");
		w.focus();
	}
	function QuestionnaireExcel(Id)
	{
		var w=window.open("QuestionnaireExcel.php?Id="+Id,"PageExcel"+Id,"status=no,menubar=no,scrollbars=1,width=90,height=60");
		w.focus();
	}
	function QuestionnaireExcel2(Id)
	{
		var w=window.open("QuestionnaireExcel2.php?Id="+Id,"PageExcel"+Id,"status=no,menubar=no,scrollbars=1,width=90,height=60");
		w.focus();
	}
	function Up(Id){
		var tr=document.getElementById('tr_'+Id);
		tbody=document.getElementById('test');
		trs=tbody.getElementsByTagName('tr');
		count=trs.length;
		i=0;
		found=false;
		while(i<count && !found){
			if(trs[i]==tr){
				found=true;
			}else{
				i++;
			}
		}
		
		//Echanger les couleurs
		j=0;
		found=false;
		while(j<count && !found){
			if(j==(i-1)){
				var color=tr.style.backgroundColor;
				tr.style.backgroundColor=trs[j].style.backgroundColor;
				trs[j].style.backgroundColor=color;
				$.ajax({
					url : 'EchangerOrdre.php',
					data : 'Id1='+tr.id.substr(3)+'&Id2='+trs[j].id.substr(3),
				});
				found=true;
			}else{
				j++;
			}
		}
		
		tr2=tbody.insertRow(i-1);
		tbody.replaceChild(tr,tr2);
	}
	function Down(Id){
		var tr=document.getElementById('tr_'+Id);
		tbody=document.getElementById('test');
		trs=tbody.getElementsByTagName('tr');
		count=trs.length;
		i=0;
		found=false;
		while(i<count && !found){
			if(trs[i]==tr){
				found=true;
			}else{
				i++;
			}
		}
		//Echanger les couleurs
		j=0;
		found=false;
		while(j<count && !found){
			if(j==(i+1)){
				var color=tr.style.backgroundColor;
				tr.style.backgroundColor=trs[j].style.backgroundColor;
				trs[j].style.backgroundColor=color;
				$.ajax({
					url : 'EchangerOrdre.php',
					data : 'Id1='+tr.id.substr(3)+'&Id2='+trs[j].id.substr(3),
				});
				found=true;
			}else{
				j++;
			}
		}
		
		tr2=tbody.insertRow(i+2);
		tbody.replaceChild(tr,tr2);
	}
	tabPresta = new Array();
	function Selectionner(Champ){
		if(Champ=="Prestation"){
			var elements = document.getElementsByClassName("check"+Champ);
			for(var i=0, l=elements.length; i<l; i++){
				for(var k=0, m=tabPresta.length; k<m; k++){
					if(tabPresta[k][0]==elements[i].value){
						if(
							(document.getElementById("plateforme"+tabPresta[k][1])!= null && tabPresta[k][1]==document.getElementById("plateforme"+tabPresta[k][1]).value && document.getElementById("plateforme"+tabPresta[k][1]).checked == true)
						&&
							(document.getElementById("RP"+tabPresta[k][2])!= null && tabPresta[k][2]==document.getElementById("RP"+tabPresta[k][2]).value && document.getElementById("RP"+tabPresta[k][2]).checked == true)
						){
							elements[i].checked =true;
						}
						else{
							elements[i].checked =false;
						}
					}
				}
			}
		}
	}
	function AjouterQualifSurv(Id){
		var w=window.open("Ajout_QualifSurv.php?Id="+Id,"Page","status=no,menubar=no,scrollbars=yes,width=600,height=300");
		w.focus();
	}
	function ValiderQualifSurv(Id){
		var w=window.open("Valider_QualifSurv.php?Id="+Id,"Page","status=no,menubar=no,scrollbars=yes,width=60,height=30");
		w.focus();
	}
</script>
<style>
#leHover2{
	position: relative;
	color : black;
	text-decoration: none;
}
#leHover2 span {
   display: none; /*  On masque l'infobulle. */
}

#leHover2:hover  {
   z-index: 500; /* On définit une valeur pour l'ordre d'affichage. */
	/* cursor: help; On change le curseur par défaut par un curseur d'aide. */
}
#leHover2:hover  span {
   display: inline; /* On affiche l'infobulle. */
   position: absolute;
   top: 30px; /* On positionne notre infobulle. */
   left: 20px;
   background: white;
   color: black;
   padding: 3px;
   border: 1px solid #75a3ff;
   border-left: 4px solid #75a3ff;
   font-size:12px;
   text-align:left;
   width:500px;
}

#leHover2:hover  span table,#leHover2:hover  span table td {
   border: 1px solid black;
   text-align : center;
   border-collapse : collapse;
}
</style>
<?php
if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
	$MoisLettre3 = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
	$MoisLettre3 = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
}

if($_GET){
	if(isset($_GET['Menu'])){
		$Menu=$_GET['Menu'];
	}
	else{
		$Menu=1;
	}
}
else{
	if(isset($_POST['Menu'])){
		$Menu=$_POST['Menu'];
	}
	else{
		$Menu=1;
	}
}

if($_GET){
	if(isset($_GET['Id_SurveillanceMere'])){
		$Id_SurveillanceMere=$_GET['Id_SurveillanceMere'];
	}
	else{
		$Id_SurveillanceMere=0;
	}
}
else{
	if(isset($_POST['Id_SurveillanceMere'])){
		$Id_SurveillanceMere=$_POST['Id_SurveillanceMere'];
	}
	else{
		$Id_SurveillanceMere=0;
	}
}

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=$leNombre;}
	return $nb;
}
function Titre($Libelle,$Lien){
	echo "<tr>
			<td style='font-size:14px;' colspan='8' >&nbsp;&bull;&nbsp;
				<a style=\"color:black;text-decoration: none;font-weight:bold;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
					".$Libelle."
				</a>
			</td>
		</tr>\n
		<tr>
			<td height=\"5px\">
			
			</td>
		</tr>
		";
}

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:3px solid #ffffff;font-style:italic;font-size:14px;";$couleurTexte="#ffffff";}
	echo "<td style=\"width:10%;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle;
		
	echo "</a></td>\n";
}

function SousTitre($Libelle,$Lien,$Selected,$nbQuestionNA=""){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:3px solid #ffffff;font-style:italic;font-size:14px;";$couleurTexte="#ffffff";}
	echo "<td style=\"width:10%;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle;
	if($nbQuestionNA>0){
		echo " <span style='color:red;font-size:18px;'>[".$nbQuestionNA."]</span>";
	}		
	echo "</a></td>\n";
}

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='X'
	AND Suppr=0
	AND Date_Debut<='".date('Y-m-d')."'
	AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantQualifie=mysqli_num_rows($resultSurQualifie);

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='L'
	AND Suppr=0
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantECQualif=mysqli_num_rows($resultSurQualifie);

$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

$req="SELECT Id FROM soda_theme 
	WHERE Suppr=0 
	AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.") ";
$resAcc=mysqli_query($bdd,$req);
$nbGestionnaire=mysqli_num_rows($resAcc);

$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$IdPersonneConnectee." ";	
$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

//Liste des question non-applicable à valider par le CQP
$req="SELECT soda_surveillance_question.Id 
FROM soda_surveillance_question 
LEFT JOIN soda_surveillance 
ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
WHERE soda_surveillance.Suppr=0
AND soda_surveillance_question.Etat='NA'
AND soda_surveillance.Etat='Clôturé'
AND AutoSurveillance=0 
AND TypeNA=2 
AND TraitementNA=0 ";
if($nbAccess>0 || $nbSuperAdmin>0){}
else{
	$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
				IN (SELECT Id 
					FROM soda_theme 
					WHERE Suppr=0 
					AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
					) 
				OR 
				IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
				(
					SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
					AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
				)
				)";
}
$nbQuestionsNA=mysqli_num_rows($resAcc=mysqli_query($bdd,$req));
?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="Tableau_De_Bord.php" method="post">
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td style="display:none;"><input name="Langue" id="Langue" value="<?php echo $LangueAffichage;?>"></td>
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>
		<td colspan="6" height="20px" valign="center" align="right" style="font-weight:bold;font-size:15px;">
			<?php
			if($LangueAffichage=="FR"){echo "Vous avez des questions, un problème ? Contactez-nous : ";}
			else{echo "Do you have questions or a problem? Contact us : ";}
			?>
			<span style="color:#00577c;">help-soda.aaa@daher.com </span>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr bgcolor="#6EB4CD">
	<?php
		$select=false;
		if(isset($Menu)){
			if($Menu==1){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("ACCUEIL","Outils/SODA/Tableau_De_Bord.php?Menu=1",$select);}
		else{Titre1("WELCOME","Outils/SODA/Tableau_De_Bord.php?Menu=1",$select);}
		
		
		$select=false;
		if(isset($Menu)){
			if($Menu==2 || $Menu==7 || $Menu==8 || $Menu==9 || $Menu==25 || $Menu==26 || $Menu==28){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("SURVEILLANCE","Outils/SODA/Tableau_De_Bord.php?Menu=2",$select);}
		else{Titre1("SURVEILLANCE","Outils/SODA/Tableau_De_Bord.php?Menu=2",$select);}
		
		if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation))
			|| DroitsFormationPrestation(array($IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8)))
		{
			$select=false;
			if(isset($Menu)){
				if($Menu==3 || $Menu==10 || $Menu==11 || $Menu==12){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("TABLEAU DE BORD","Outils/SODA/Tableau_De_Bord.php?Menu=3",$select);}
			else{Titre1("DASHBOARD","Outils/SODA/Tableau_De_Bord.php?Menu=3",$select);}
		}

		$select=false;
		if(isset($Menu)){
			if($Menu==5 || $Menu==15 || $Menu==16|| $Menu==17 || $Menu==29){$select=true;}
		}
		if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation,$IdPosteReferentSurveillance))
			|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8)))
		{
			if($_SESSION["Langue"]=="FR"){Titre1("PARAMETRAGE","Outils/SODA/Tableau_De_Bord.php?Menu=5",$select);}
			else{Titre1("SETTINGS","Outils/SODA/Tableau_De_Bord.php?Menu=5",$select);}
		}
		$select=false;
		if(isset($Menu)){
			if($Menu==6 || $Menu==18 || $Menu==19 || $Menu==20 || $Menu==21 || $Menu==22 || $Menu==23 || $Menu==24 || $Menu==27 || $Menu==30){$select=true;}
		}
		if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0){
			if($_SESSION["Langue"]=="FR"){Titre1("ADMINISTRATION","Outils/SODA/Tableau_De_Bord.php?Menu=6",$select);}
			else{Titre1("ADMINISTRATION","Outils/SODA/Tableau_De_Bord.php?Menu=6",$select);}
		}
	?>
	</tr>
</table>
<table style="width:100%; border-spacing:0px;">
	<tr bgcolor="#6EB4CD">
		<?php
			if($Menu==2 || $Menu==7 || $Menu==8 || $Menu==9 || $Menu==25 || $Menu==26 || $Menu==28){
				$select2=false;
				if(isset($Menu)){
					if($Menu==7){$select2=true;}
				}
				$select2=false;
				if(isset($Menu)){
					if($Menu==8){$select2=true;}
				}
				if($_SESSION["Langue"]=="FR"){SousTitre("Consulter les surveillances","Outils/SODA/Tableau_De_Bord.php?Menu=8",$select2);}
				else{SousTitre("Consult the surveillances","Outils/SODA/Tableau_De_Bord.php?Menu=8",$select2);}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==9 || $Menu==25){$select2=true;}
				}
				
				if($nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Surveillance non planifiée","Outils/SODA/Tableau_De_Bord.php?Menu=9",$select2);}
					else{SousTitre("Unscheduled monitoring","Outils/SODA/Tableau_De_Bord.php?Menu=9",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==28){$select2=true;}
				}
				
				if($nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0){
					//Vérifier Si la personne peut faire les surveillances de cette thématique
					$req="SELECT Id FROM soda_surveillant_theme WHERE Id_Surveillant=".$_SESSION['Id_Personne']." AND Id_Theme=8 ";
					$resultSurvTheme=mysqli_query($bdd,$req);
					$nbSurvTheme=mysqli_num_rows($resultSurvTheme);
					
					$req="SELECT Id FROM soda_theme WHERE Id=8 AND Id_Qualification IN (
						SELECT DISTINCT Id_Qualification_Parrainage 
						FROM new_competences_relation 
						WHERE (Evaluation IN ('L','X')
						AND Date_Debut<='".date('Y-m-d')."'
						AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
						)
						AND Suppr=0
						AND Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM soda_theme WHERE Id=8)
						AND Id_Personne=".$_SESSION['Id_Personne']."
					) ";
					$resultSurTheme= mysqli_query($bdd,$req);	
					$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
					
					if($nbSurvTheme>0 || $nbSurvThemeQualifie>0){
						if($_SESSION["Langue"]=="FR"){SousTitre("Surveillance PROCESSUS non planifiée","Outils/SODA/Tableau_De_Bord.php?Menu=28",$select2);}
						else{SousTitre("Unscheduled monitoring PROCESSUS","Outils/SODA/Tableau_De_Bord.php?Menu=28",$select2);}
					}
				}
				
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteReferentQualiteSysteme))){
					$select2=false;
					if(isset($Menu)){
						if($Menu==26){$select2=true;}
					}
					if($_SESSION["Langue"]=="FR"){SousTitre("Questions non applicables à valider","Outils/SODA/Tableau_De_Bord.php?Menu=26",$select2,$nbQuestionsNA);}
					else{SousTitre("Questions not applicable to validate","Outils/SODA/Tableau_De_Bord.php?Menu=26",$select2,$nbQuestionsNA);}
				}
			}
			elseif($Menu==3 || $Menu==10 || $Menu==11 || $Menu==12){
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteChargeMissionOperation,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)))
				{
					$select2=false;
					if(isset($Menu)){
						if($Menu==12){$select2=true;}
					}
					if($_SESSION["Langue"]=="FR"){SousTitre("Thématique","Outils/SODA/Tableau_De_Bord.php?Menu=12",$select2);}
					else{SousTitre("Thematic","Outils/SODA/Tableau_De_Bord.php?Menu=12",$select2);}
				}
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteChargeMissionOperation))
				|| DroitsFormationPrestation(array($IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8)))
				{
						$select2=false;
						if(isset($Menu)){
							if($Menu==10){$select2=true;}
						}
						if($_SESSION["Langue"]=="FR"){SousTitre("Opérations","Outils/SODA/Tableau_De_Bord.php?Menu=10",$select2);}
						else{SousTitre("Operations","Outils/SODA/Tableau_De_Bord.php?Menu=10",$select2);}
				}
				
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
					|| DroitsFormationPrestation(array($IdPosteReferentQualiteSysteme,8)))
				{
					$select2=false;
					if(isset($Menu)){
						if($Menu==11){$select2=true;}
					}
					if($_SESSION["Langue"]=="FR"){SousTitre("Processus","Outils/SODA/Tableau_De_Bord.php?Menu=11",$select2);}
					else{SousTitre("Processus","Outils/SODA/Tableau_De_Bord.php?Menu=11",$select2);}
				}
			}
			elseif($Menu==4 || $Menu==13 || $Menu==14){
				$select2=false;
				if(isset($Menu)){
					if($Menu==13){$select2=true;}
				}
				if($_SESSION["Langue"]=="FR"){SousTitre("Extract","Outils/SODA/Tableau_De_Bord.php?Menu=13",$select2);}
				else{SousTitre("Extract","Outils/SODA/Tableau_De_Bord.php?Menu=14",$select2);}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==14){$select2=true;}
				}
				if($_SESSION["Langue"]=="FR"){SousTitre("Rapport","Outils/SODA/Tableau_De_Bord.php?Menu=14",$select2);}
				else{SousTitre("Report","Outils/SODA/Tableau_De_Bord.php?Menu=13",$select2);}
				
			}
			elseif($Menu==5 || $Menu==15 || $Menu==16 || $Menu==17 || $Menu==29){
				$select2=false;
				if(isset($Menu)){
					if($Menu==15){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
					if($_SESSION["Langue"]=="FR"){SousTitre("BDD Questions","Outils/SODA/Tableau_De_Bord.php?Menu=15",$select2);}
					else{SousTitre("DB Questions","Outils/SODA/Tableau_De_Bord.php?Menu=15",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==16){$select2=true;}
				}
				if($nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Objectifs","Outils/SODA/Tableau_De_Bord.php?Menu=16",$select2);}
					else{SousTitre("Objective","Outils/SODA/Tableau_De_Bord.php?Menu=16",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==17){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation,$IdPosteReferentSurveillance))
					|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8)))
				{
					if($_SESSION["Langue"]=="FR"){SousTitre("Planification","Outils/SODA/Tableau_De_Bord.php?Menu=17",$select2);}
					else{SousTitre("Planning","Outils/SODA/Tableau_De_Bord.php?Menu=17",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==29){$select2=true;}
				}
				
			
				if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillantQualifie>0
					|| DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
					|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)))
				{
					if($_SESSION["Langue"]=="FR"){SousTitre("Qualification surveillants","Outils/SODA/Tableau_De_Bord.php?Menu=29",$select2);}
					else{SousTitre("Supervisor qualifications","Outils/SODA/Tableau_De_Bord.php?Menu=29",$select2);}
				}
			}
			elseif($Menu==6 || $Menu==18 || $Menu==19 || $Menu==20 || $Menu==21 || $Menu==22 || $Menu==23 || $Menu==24 || $Menu==27 || $Menu==30){
				$select2=false;
				if(isset($Menu)){
					if($Menu==18){$select2=true;}
				}
				if($nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Accès administrateur","Outils/SODA/Tableau_De_Bord.php?Menu=18",$select2);}
					else{SousTitre("Administrator access","Outils/SODA/Tableau_De_Bord.php?Menu=18",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==27){$select2=true;}
				}
				if($nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Accès super administrateur","Outils/SODA/Tableau_De_Bord.php?Menu=27",$select2);}
					else{SousTitre("Super admin access","Outils/SODA/Tableau_De_Bord.php?Menu=27",$select2);}
				}

				$select2=false;
				if(isset($Menu)){
					if($Menu==20){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Liste des surveillants","Outils/SODA/Tableau_De_Bord.php?Menu=20",$select2);}
					else{SousTitre("List of supervisors","Outils/SODA/Tableau_De_Bord.php?Menu=20",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==30){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Liste des surveillants qualifiés","Outils/SODA/Tableau_De_Bord.php?Menu=30",$select2);}
					else{SousTitre("List of qualified supervisors","Outils/SODA/Tableau_De_Bord.php?Menu=30",$select2);}
				}

				$select2=false;
				if(isset($Menu)){
					if($Menu==21){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Prestations non surveillables ou non renseignés","Outils/SODA/Tableau_De_Bord.php?Menu=21",$select2);}
					else{SousTitre("Site not monitorable or not filled in","Outils/SODA/Tableau_De_Bord.php?Menu=21",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==22){$select2=true;}
				}
				if($_SESSION["Langue"]=="FR"){SousTitre("Questionnaires","Outils/SODA/Tableau_De_Bord.php?Menu=22",$select2);}
				else{SousTitre("Questionnaires","Outils/SODA/Tableau_De_Bord.php?Menu=22",$select2);}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==23){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Thèmes","Outils/SODA/Tableau_De_Bord.php?Menu=23",$select2);}
					else{SousTitre("Themes","Outils/SODA/Tableau_De_Bord.php?Menu=23",$select2);}
				}
				
				$select2=false;
				if(isset($Menu)){
					if($Menu==24){$select2=true;}
				}
				if($nbAccess>0 || $nbSuperAdmin>0){
					if($_SESSION["Langue"]=="FR"){SousTitre("Groupes métiers","Outils/SODA/Tableau_De_Bord.php?Menu=24",$select2);}
					else{SousTitre("Business groups","Outils/SODA/Tableau_De_Bord.php?Menu=24",$select2);}
				}
				
			}
		?>
	</tr>
</table>
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td colspan="14" align="center" style="width:100%">
		<?php	
			if($Menu==1){
				require "Accueil.php";
			}
			elseif($Menu==7){
				
			}
			elseif($Menu==8){
				require "ConsulterSurveillances.php";
			}
			elseif($Menu==9){
				require "SurveillanceNonPlanifiee.php";
			}
			elseif($Menu==10){
				require "TDB_Operation.php";
			}
			elseif($Menu==11){
				require "TDB_Processus.php";
			}
			elseif($Menu==12){
				require "TDB_Thematique.php";
			}
			elseif($Menu==13){
				
			}
			elseif($Menu==14){
				
			}
			elseif($Menu==15){
				require "Liste_Questions.php";
			}
			elseif($Menu==16){
				require "Liste_Objectif.php";
			}
			elseif($Menu==17){
				require "PlanningSurveillance.php";
			}
			elseif($Menu==18){
				require "Liste_Administrateur.php";
			}
			elseif($Menu==19){
				
			}
			elseif($Menu==20){
				require "Liste_Surveillant.php";
			}
			elseif($Menu==21){
				require "Liste_Prestation.php";
			}
			elseif($Menu==22){
				require "Liste_Questionnaire.php";
			}
			elseif($Menu==23){
				require "Liste_Theme.php";
			}
			elseif($Menu==24){
				require "Liste_GroupeMetier.php";
			}
			elseif($Menu==25){
				require "RealiserSurveillance2.php";
			}
			elseif($Menu==26){
				require "QuestionsNonApplicablesAValider.php";
			}
			elseif($Menu==27){
				require "Liste_SuperAdministrateur.php";
			}
			elseif($Menu==28){
				require "SurveillanceNonPlanifieeProcessus.php";
			}
			elseif($Menu==29){
				require "FormationPratique.php";
			}
			elseif($Menu==30){
				require "Liste_SurveillantQualifie.php";
			}
		?>
		</td>
	</tr>
</table>
</form>
</body>
</html>