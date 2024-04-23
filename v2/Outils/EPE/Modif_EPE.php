<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
	function filter() {
        var keyword = document.getElementById("BFRecherche").value;
        var select = document.getElementById("QualifRecherche");
		var BFSelectBesoin="<select name='BFSelectBesoin' id='BFSelectBesoin' style='width:300px;'>";
		trouve=0;
        for (var i = 0; i < select.length; i++) {
            var txt = select.options[i].text.toUpperCase();
            if (txt.match(keyword.toUpperCase())) {
				BFSelectBesoin = BFSelectBesoin + "<option value='" + select.options[i].value + "'>" + select.options[i].text + "</option>";
				trouve=1;
            }
        }
		if(trouve==0 || select==""){
			BFSelectBesoin= BFSelectBesoin + "<option value=''></option>";
		}
		BFSelectBesoin = BFSelectBesoin + "</select>";
		document.getElementById('BFSelectBesoin').innerHTML=BFSelectBesoin;
    }
	function filterSF() {
        var keyword = document.getElementById("SFRecherche").value;
        var select = document.getElementById("QualifRecherche");
		var SFSelectBesoin="<select name='SFSelectBesoin' id='SFSelectBesoin' style='width:300px;'>";
		trouve=0;
        for (var i = 0; i < select.length; i++) {
            var txt = select.options[i].text.toUpperCase();
            if (txt.match(keyword.toUpperCase())) {
               SFSelectBesoin = SFSelectBesoin + "<option value='" + select.options[i].value + "'>" + select.options[i].text + "</option>";
			   trouve=1;
            }
        }
		if(trouve==0 || select==""){
			SFSelectBesoin= SFSelectBesoin + "<option value=''></option>";
		}
		SFSelectBesoin = SFSelectBesoin + "</select>";
		document.getElementById('SFSelectBesoin').innerHTML=SFSelectBesoin;
    }
	function VerifQualif(i){
		if(document.getElementById("BFQuestionPose").value=="0"){
			if(document.getElementById("BFQualifAjoute").value=="0"){
				alert("Avez-vous pensé à faire une recherche avant de saisir une nouvelle formation ?");
			}
			document.getElementById("BFQuestionPose").value="1";
		}
	}
	function VerifFormation(i){
		if(document.getElementById("SFQuestionPose").value=="0"){
			if(document.getElementById("SFQualifAjoute").value=="0"){
				alert("Avez-vous pensé à faire une recherche avant de saisir une nouvelle formation ?");
			}
			document.getElementById("SFQuestionPose").value="1";
		}
	}
	function AjouterBFBesoin(){
		if(document.getElementById("BFSelectBesoin").value!=""){
			trouvePlace=false;
			for(var i=0; i<10; i++){
				if(document.getElementById("BFBesoin"+i).value=='' && trouvePlace==false){
					trouvePlace=true;
					document.getElementById("BFBesoin"+i).value=document.getElementById("BFSelectBesoin").value;
					if(i>1){
						AfficherTR2(i);
					}
				}
			}
		}
		document.getElementById("BFQualifAjoute").value="1";
	}
	function AjouterSFBesoin(){
		if(document.getElementById("SFSelectBesoin").value!=""){
			trouvePlace=false;
			for(var i=0; i<10; i++){
				if(document.getElementById("SFFormation"+i).value=='' && trouvePlace==false){
					trouvePlace=true;
					document.getElementById("SFFormation"+i).value=document.getElementById("SFSelectBesoin").value;
					if(i>1){
						AfficherTR3(i);
					}
				}
			}
		}
		document.getElementById("SFQualifAjoute").value="1";
	}
	function EPE_PDF(Id,Cadre)
			{window.open("EPE_PDF.php?Id="+Id+"&Cadre="+Cadre,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPE_Excel(Id,Cadre)
			{window.open("EPE_Excel.php?Id="+Id+"&Cadre="+Cadre,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function VerifChamps(){
		//Objectifs années précédentes
		
		if(document.getElementById("Etat").value=="Brouillon"){
			if(document.getElementById("modeBrouillon").value=="0"){
				nbObj=0;
				for(var i=0; i<10; i++){
					if(document.getElementById("ObjEvaluation"+i).value!=''){
						necessiteCom=0;
						checkedObjet=false;
						var Elements_Obj = document.getElementsByClassName("ObjNote"+i);
						for(var k=0, l=Elements_Obj.length; k<l; k++){
							if(Elements_Obj[k].checked){
								checkedObjet=true;
								if((k==1 || k==2 || k ==4) && document.getElementById("ObjCommentaire"+i).value==''){necessiteCom=1;}
							}
						}
						if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 1 - Bilan annuel Global");return false;}
						if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 1 - Bilan annuel Global (commentaire obligatoire si note= 1, 2 ou 4)");return false;}
						nbObj++;
					}
				}
				
				tab=['ConnaissanceMetier','UtilisationDoc','Productivite','Organisation','CapaciteManager','RespectObjectif','AnglaisTech','CapaciteTuteur','Reporting','PlanAction','RespectBudget']; 
				for(var i= 0; i < tab.length; i++)
				{
					necessiteCom=0;
					checkedObjet=false;
					var Elements_Obj = document.getElementsByClassName(tab[i]);
					for(var k=0, l=Elements_Obj.length; k<l; k++){
						if(Elements_Obj[k].checked){
							checkedObjet=true;
							if((k==1 || k==2 || k ==4) && document.getElementById("Com"+tab[i]).value==''){necessiteCom=1;}
						}
					}
					if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 2 - Focus Métier");return false;}
					if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 2 - Focus Métier (commentaire obligatoire si note= 1, 2 ou 4)");return false;}
				}
				tab=['RepresentationEntreprise','SouciSatisfaction','Ecoute','TraitementInsatisfaction','ExplicationSolution','ComprehensionInsatisfaction'];
				for(var i= 0; i < tab.length; i++)
				{
					necessiteCom=0;
					checkedObjet=false;
					var Elements_Obj = document.getElementsByClassName(tab[i]);
					for(var k=0, l=Elements_Obj.length; k<l; k++){
						if(Elements_Obj[k].checked){
							checkedObjet=true;
							if((k==1 || k==2 || k ==4) && document.getElementById("Com"+tab[i]).value==''){necessiteCom=1;}
						}
					}
					if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 2 - Focus Relation Client");return false;}
					if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 2 - Focus Relation Client (commentaire obligatoire si note= 1, 2 ou 4)");return false;}
				}
				tab=['ConnaissanceManagement','ConnaissanceMetierEquipe','CapaciteFixerObjectif','Delegation','AnimationEquipe']; 
				for(var i= 0; i < tab.length; i++)
				{
					necessiteCom=0;
					checkedObjet=false;
					var Elements_Obj = document.getElementsByClassName(tab[i]);
					for(var k=0, l=Elements_Obj.length; k<l; k++){
						if(Elements_Obj[k].checked){
							checkedObjet=true;
							if((k==1 || k==2 || k ==4) && document.getElementById("Com"+tab[i]).value==''){necessiteCom=1;}
						}
					}
					if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 2 - Focus Management");return false;}
					if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 2 - Focus Management (commentaire obligatoire si note= 1, 2 ou 4)");return false;}
				}
				tab=['RespectQSE','ContributionNC','RespectRegles','PortTenues','PortEPI','RespectOutils'];
				for(var i= 0; i < tab.length; i++)
				{
					necessiteCom=0;
					checkedObjet=false;
					var Elements_Obj = document.getElementsByClassName(tab[i]);
					for(var k=0, l=Elements_Obj.length; k<l; k++){
						if(Elements_Obj[k].checked){
							checkedObjet=true;
							if((k==1 || k==2 || k ==4) && document.getElementById("Com"+tab[i]).value==''){necessiteCom=1;}
						}
					}
					if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 2 - Focus Qualité - Sécurité- Environnement");return false;}
					if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 2 - Focus Qualité - Sécurité- Environnement (commentaire obligatoire si note= 1, 2 ou 4)");return false;}
				}
				tab=['Assiduite','EspritEntreprise','TravailEquipe','Dispo','Autonomie','Initiative','Communication'];
				for(var i= 0; i < tab.length; i++)
				{
					necessiteCom=0;
					checkedObjet=false;
					var Elements_Obj = document.getElementsByClassName(tab[i]);
					for(var k=0, l=Elements_Obj.length; k<l; k++){
						if(Elements_Obj[k].checked){
							checkedObjet=true;
							if((k==1 || k==2 || k ==4) && document.getElementById("Com"+tab[i]).value==''){necessiteCom=1;}
						}
					}
					if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 2 - Savoir être Général");return false;}
					if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 2 - Savoir être Général (commentaire obligatoire si note= 1, 2 ou 4)");return false;}
				}
				
				nbObj=0;
				for(var i=0; i<10; i++){
					if(document.getElementById("OAObjectif"+i).value!=''){
						nbObj++;
					}
				}
				if(nbObj==0){alert("Veuillez compléter la partie 3 - Définition des objectifs annuels à venir");return false;}
				
				if(document.getElementById("FAFormationFor").value>0){
					for(var i=0; i<document.getElementById("FAFormationFor").value; i++){
						if(document.getElementById("FAFormationFor"+i).value!=''){
							checkedObjet=false;
							var Elements_Obj = document.getElementsByClassName("FANoteFor"+i);
							for(var k=0, l=Elements_Obj.length; k<l; k++){
								if(Elements_Obj[k].checked){
									checkedObjet=true;
								}
							}
							if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 4 - Formations (Bilan des formations annuelles réalisées)");return false;}
						}
					}
				}
				
				if(document.getElementById("FAFormationQua").value>0){
					for(var i=0; i<document.getElementById("FAFormationQua").value; i++){
						if(document.getElementById("FAFormationQua"+i).value!=''){
							checkedObjet=false;
							var Elements_Obj = document.getElementsByClassName("FANoteQua"+i);
							for(var k=0, l=Elements_Obj.length; k<l; k++){
								if(Elements_Obj[k].checked){
									checkedObjet=true;
								}
							}
							if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 4 - Formations (Bilan des formations annuelles réalisées)");return false;}
						}
					}
				}
				
				for(var i=0; i<5; i++){
					if(document.getElementById("FAFormation"+i).value!=''){
						checkedObjet=false;
						var Elements_Obj = document.getElementsByClassName("FANote"+i);
						for(var k=0, l=Elements_Obj.length; k<l; k++){
							if(Elements_Obj[k].checked){
								checkedObjet=true;
							}
						}
						if(checkedObjet==false || document.getElementById("FADateDebut"+i).value=='' || document.getElementById("FADateFin"+i).value==''){alert("Veuillez terminer de compléter la partie 4 - Formations (Bilan des formations annuelles réalisées)");return false;}
					}
				}
				
				for(var i=0; i<10; i++){
					if(document.getElementById("BFBesoin"+i).value!=''){
						if(document.getElementById("BFDateDebut"+i).value=='' || document.getElementById("BFDateFin"+i).value==''){alert("Veuillez terminer de compléter la partie 4 - Formations (Besoins en formation identifié par le manager) - Dates à ajouter");return false;}
					}
				}
				
				for(var i=0; i<10; i++){
					if(document.getElementById("SFFormation"+i).value!=''){
						checkedObjet=false;
						necessiteCom=0;
						var Elements_Obj = document.getElementsByClassName("SFAvis"+i);
						for(var k=0, l=Elements_Obj.length; k<l; k++){
							if(Elements_Obj[k].checked){
								checkedObjet=true;
								if((k==0) && document.getElementById("SFOrdre"+i).value=='0'){alert("Veuillez terminer de compléter la partie 4 - Formations (souhait de formation exprimé par le salarié) - Priorité à ajouter");return false;}
								if((k==1) && document.getElementById("SFCommentaire"+i).value==''){alert("Veuillez terminer de compléter la partie 4 - Formations (souhait de formation exprimé par le salarié) - Commentaire à ajouter");return false;}
							}
						}
						if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 4 - Formations (souhait de formation exprimé par le salarié) - Avis évaluateur à ajouter");return false;}
					}
				}
						
				if(document.getElementById("Cadre").value==0){
					tab=['OrganisationCharge','ArticulationActiviteProPerso'];
					for(var i= 0; i < tab.length; i++)
					{
						if(document.getElementById("ComS"+tab[i]).value==''){alert("Veuillez terminer de compléter la partie 5 - Suivi de la charge de travail (commentaire salarié obligatoire)");return false;}
					}
					
					tab=['Stress'];
					for(var i= 0; i < tab.length; i++)
					{
						necessiteCom=0;
						checkedObjet=false;
						var Elements_Obj = document.getElementsByClassName(tab[i]);
						for(var k=0, l=Elements_Obj.length; k<l; k++){
							if(Elements_Obj[k].checked){
								checkedObjet=true;
								if((k==0) && document.getElementById("ComS"+tab[i]).value==''){necessiteCom=1;}
							}
						}
						if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 6 - Temps d'écoute");return false;}
						if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 6 - Temps d'écoute (commentaire obligatoire si note= 1)");return false;}
					}
					if(document.getElementById("PointFort").value==""){alert("Veuillez compléter la partie 7 - Synthèse");return false;}
					if(document.getElementById("PointFaible").value==""){alert("Veuillez compléter la partie 7 - Synthèse");return false;}
					if(document.getElementById("ObjectifProgression").value==""){alert("Veuillez compléter la partie 7 - Synthèse");return false;}
				}
				else{
					tab=['OrganisationCharge','AmplitudeJournee','OrganisationTravail','ArticulationActiviteProPerso','Remuneration'];
					for(var i= 0; i < tab.length; i++)
					{
						if(document.getElementById("ComS"+tab[i]).value==''){alert("Veuillez terminer de compléter la partie 5 - Suivi de la charge de travail (commentaire salarié obligatoire)");return false;}
					}
					
					tab=['Stress'];
					for(var i= 0; i < tab.length; i++)
					{
						necessiteCom=0;
						checkedObjet=false;
						var Elements_Obj = document.getElementsByClassName(tab[i]);
						for(var k=0, l=Elements_Obj.length; k<l; k++){
							if(Elements_Obj[k].checked){
								checkedObjet=true;
								if((k==0) && document.getElementById("ComS"+tab[i]).value==''){necessiteCom=1;}
							}
						}
						if(checkedObjet==false){alert("Veuillez terminer de compléter la partie 6 - Temps d'écoute");return false;}
						if(necessiteCom==1){alert("Veuillez terminer de compléter la partie 6 - Temps d'écoute (commentaire obligatoire si note= 1)");return false;}
					}
					
					if(document.getElementById("PointFort").value==""){alert("Veuillez compléter la partie 7 - Synthèse");return false;}
					if(document.getElementById("PointFaible").value==""){alert("Veuillez compléter la partie 7 - Synthèse");return false;}
					if(document.getElementById("ObjectifProgression").value==""){alert("Veuillez compléter la partie 7 - Synthèse");return false;}
				}
				if(document.getElementById("ComEvaluateur").value==""){alert("Veuillez compléter le commentaire évaluateur");return false;}
				
				if(document.getElementById("modeBrouillon").value=="0" && document.getElementById("Etat").value=="Brouillon"){
					var Confirm=false;
					Confirm=window.confirm('Attention, aucune modification ne sera possible. Etes-vous sur de vouloir valider ? ');
					if(Confirm==false){
						return false;
					}
				}
			}
		}
		else if(document.getElementById("Etat").value=="Signature salarié"){
			if(document.getElementById("ComSalarie").value==""){
				var Confirm=false;
				Confirm=window.confirm('Attention, la signature sera définitive. Etes-vous sur de ne pas vouloir ajouter un commentaire à votre EPE ? ');
				if(Confirm==false){
					return false;
				}
			}
		}
	}
	function AfficherTR(nb){
		var elements = document.getElementsByClassName('OA'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('OOA'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTR2(nb){
		var elements = document.getElementsByClassName('BF'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('BBF'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTR3(nb){
		var elements = document.getElementsByClassName('SF'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('SSF'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTRFA(nb){
		var elements = document.getElementsByClassName('FA'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('FFA'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTRObj(nb){
		var elements = document.getElementsByClassName('Obj'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('OObj'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherRPS(){
		checkedObjet=false;
		var Elements_Obj = document.getElementsByClassName('Stress');
		for(var k=0, l=Elements_Obj.length; k<l; k++){
			if(Elements_Obj[k].checked){
				
				if((k==0)){checkedObjet=true;}
			}
		}
		if(checkedObjet==true){
			var elements = document.getElementsByClassName('RPS');
			for (i=0; i<elements.length; i++){
			  elements[i].style.display='';
			}
		}
		else{
			var elements = document.getElementsByClassName('RPS');
			for (i=0; i<elements.length; i++){
			  elements[i].style.display='none';
			}
		}
	}
	function FermerEtRecharger()
	{
		window.opener.location="Liste_EPE.php";
		window.close();
	}
	</script>
</head>


<?php
require_once("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

function existeSinon_2($leChamps){
	$valeur=-2;
	if(isset($_POST[$leChamps])){$valeur=$_POST[$leChamps];}
	return $valeur;
}

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		$rowEPE=mysqli_fetch_array($result);

		$Id_Prestation=0;
		$Id_Pole=0;

		$TableauPrestationPole=explode("_",PrestationPoleCompetence_Personne(date('Y-m-d'),$rowEPE['Id']));
		$Id_Prestation=$TableauPrestationPole[0];
		$Id_Pole=$TableauPrestationPole[1];


		$Id_Plateforme=0;
		$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Id_Plateforme=$RowPresta['Id_Plateforme'];
		}

		$MetierManager="";
		$req="SELECT MetierPaie AS Metier
				FROM new_rh_etatcivil
				WHERE Id=".$_SESSION['Id_Personne'];
		$ResultManager=mysqli_query($bdd,$req);
		$NbManager=mysqli_num_rows($ResultManager);
		if($NbManager>0){
			$RowManager=mysqli_fetch_array($ResultManager);
			$MetierManager=$RowManager['Metier'];
		}
		
		$ComSAmplitudeJournee="";
		$ComEAmplitudeJournee="";
		$ComSOrganisationTravail="";
		$ComEOrganisationTravail="";
		$ComSRemuneration="";
		$ComERemuneration="";
		if($_POST['Cadre']==1){
			$ComSAmplitudeJournee=addslashes($_POST['ComSAmplitudeJournee']);
			$ComEAmplitudeJournee=addslashes($_POST['ComEAmplitudeJournee']);
			$ComSOrganisationTravail=addslashes($_POST['ComSOrganisationTravail']);
			$ComEOrganisationTravail=addslashes($_POST['ComEOrganisationTravail']);
			$ComSRemuneration=addslashes($_POST['ComSRemuneration']);
			$ComERemuneration=addslashes($_POST['ComERemuneration']);
		}
		
		$EntretienRH=0;
		if(isset($_POST['EntretienRH'])){if($_POST['EntretienRH']==1){$EntretienRH=1;}}
		$EntretienMedecienTravail=0;
		if(isset($_POST['EntretienMedecienTravail'])){if($_POST['EntretienMedecienTravail']==1){$EntretienMedecienTravail=1;}}
		$EntretienLumanisy=0;
		if(isset($_POST['EntretienLumanisy'])){if($_POST['EntretienLumanisy']==1){$EntretienLumanisy=1;}}
		$EntretienSoutienPsycho=0;
		if(isset($_POST['EntretienSoutienPsycho'])){if($_POST['EntretienSoutienPsycho']==1){$EntretienSoutienPsycho=1;}}
		$EntretienHSE=0;
		if(isset($_POST['EntretienHSE'])){if($_POST['EntretienHSE']==1){$EntretienHSE=1;}}
		$EntretienAutre=0;
		if(isset($_POST['EntretienAutre'])){if($_POST['EntretienAutre']==1){$EntretienAutre=1;}}
		$FormationOrganisationTravail=0;
		if(isset($_POST['FormationOrganisationTravail'])){if($_POST['FormationOrganisationTravail']==1){$FormationOrganisationTravail=1;}}
		$FormationStress=0;
		if(isset($_POST['FormationStress'])){if($_POST['FormationStress']==1){$FormationStress=1;}}
		$FormationSophrologie=0;
		if(isset($_POST['FormationSophrologie'])){if($_POST['FormationSophrologie']==1){$FormationSophrologie=1;}}
		$FormationAutre=0;
		if(isset($_POST['FormationAutre'])){if($_POST['FormationAutre']==1){$FormationAutre=1;}}
		
		//Modif d'un EPE
		$req="UPDATE epe_personne SET ModeBrouillon=".$_POST['modeBrouillon'].",DateCreation='".date('Y-m-d')."',Id_Createur=".$_SESSION['Id_Personne'].",
		Metier='".addslashes($rowEPE['Metier'])."',DateAnciennete='".$rowEPE['DateAncienneteCDI']."',DateEntretien='".date('Y-m-d')."',DateButoir='".$rowEPE['DateButoir']."',
		Id_Evaluateur=".$_SESSION['Id_Personne'].",MetierManager='".addslashes($MetierManager)."',
		ConnaissanceMetier=".existeSinon_2('ConnaissanceMetier').",ComConnaissanceMetier='".addslashes($_POST['ComConnaissanceMetier'])."',UtilisationDoc=".existeSinon_2('UtilisationDoc').",
		ComUtilisationDoc='".addslashes($_POST['ComUtilisationDoc'])."',Productivite=".existeSinon_2('Productivite').",ComProductivite='".addslashes($_POST['ComProductivite'])."',
		Organisation=".existeSinon_2('Organisation').",ComOrganisation='".addslashes($_POST['ComOrganisation'])."',CapaciteManager=".existeSinon_2('CapaciteManager').",
		ComCapaciteManager='".addslashes($_POST['ComCapaciteManager'])."',RespectObjectif=".existeSinon_2('RespectObjectif').",ComRespectObjectif='".addslashes($_POST['ComRespectObjectif'])."',
		AnglaisTech=".existeSinon_2('AnglaisTech').",ComAnglaisTech='".addslashes($_POST['ComAnglaisTech'])."',CapaciteTuteur=".existeSinon_2('CapaciteTuteur').",
		ComCapaciteTuteur='".addslashes($_POST['ComCapaciteTuteur'])."',Reporting=".existeSinon_2('Reporting').",ComReporting='".addslashes($_POST['ComReporting'])."',
		PlanAction=".existeSinon_2('PlanAction').",ComPlanAction='".addslashes($_POST['ComPlanAction'])."',RespectBudget=".existeSinon_2('RespectBudget').",ComRespectBudget='".addslashes($_POST['ComRespectBudget'])."',
		RepresentationEntreprise=".existeSinon_2('RepresentationEntreprise').",ComRepresentationEntreprise='".addslashes($_POST['ComRepresentationEntreprise'])."',SouciSatisfaction=".existeSinon_2('SouciSatisfaction').",
		ComSouciSatisfaction='".addslashes($_POST['ComSouciSatisfaction'])."',Ecoute=".existeSinon_2('Ecoute').",ComEcoute='".addslashes($_POST['ComEcoute'])."',TraitementInsatisfaction=".existeSinon_2('TraitementInsatisfaction').",
		ComTraitementInsatisfaction='".addslashes($_POST['ComTraitementInsatisfaction'])."',ExplicationSolution=".existeSinon_2('ExplicationSolution').",ComExplicationSolution='".addslashes($_POST['ComExplicationSolution'])."',
		ComprehensionInsatisfaction=".existeSinon_2('ComprehensionInsatisfaction').",ComComprehensionInsatisfaction='".addslashes($_POST['ComComprehensionInsatisfaction'])."',ConnaissanceManagement=".existeSinon_2('ConnaissanceManagement').",
		ComConnaissanceManagement='".addslashes($_POST['ComConnaissanceManagement'])."',ConnaissanceMetierEquipe=".existeSinon_2('ConnaissanceMetierEquipe').",ComConnaissanceMetierEquipe='".addslashes($_POST['ComConnaissanceMetierEquipe'])."',
		CapaciteFixerObjectif=".existeSinon_2('CapaciteFixerObjectif').",ComCapaciteFixerObjectif='".addslashes($_POST['ComCapaciteFixerObjectif'])."',Delegation=".existeSinon_2('Delegation').",ComDelegation='".addslashes($_POST['ComDelegation'])."',
		AnimationEquipe=".existeSinon_2('AnimationEquipe').",ComAnimationEquipe='".addslashes($_POST['ComAnimationEquipe'])."',RespectQSE=".existeSinon_2('RespectQSE').",ComRespectQSE='".addslashes($_POST['ComRespectQSE'])."',
		ContributionNC=".existeSinon_2('ContributionNC').",ComContributionNC='".addslashes($_POST['ComContributionNC'])."',RespectRegles=".existeSinon_2('RespectRegles').",ComRespectRegles='".addslashes($_POST['ComRespectRegles'])."',
		PortTenues=".existeSinon_2('PortTenues').",ComPortTenues='".addslashes($_POST['ComPortTenues'])."',PortEPI=".existeSinon_2('PortEPI').",ComPortEPI='".addslashes($_POST['ComPortEPI'])."',RespectOutils=".existeSinon_2('RespectOutils').",
		ComRespectOutils='".addslashes($_POST['ComRespectOutils'])."',Assiduite=".existeSinon_2('Assiduite').",ComAssiduite='".addslashes($_POST['ComAssiduite'])."',EspritEntreprise=".existeSinon_2('EspritEntreprise').",
		ComEspritEntreprise='".addslashes($_POST['ComEspritEntreprise'])."',TravailEquipe=".existeSinon_2('TravailEquipe').",ComTravailEquipe='".addslashes($_POST['ComTravailEquipe'])."',Dispo=".existeSinon_2('Dispo').",
		ComDispo='".addslashes($_POST['ComDispo'])."',Autonomie=".existeSinon_2('Autonomie').",ComAutonomie='".addslashes($_POST['ComAutonomie'])."',Initiative=".existeSinon_2('Initiative').",ComInitiative='".addslashes($_POST['ComInitiative'])."',
		Communication=".existeSinon_2('Communication').",ComCommunication='".addslashes($_POST['ComCommunication'])."',ComSOrganisationCharge='".addslashes($_POST['ComSOrganisationCharge'])."',
		ComEOrganisationCharge='".addslashes($_POST['ComEOrganisationCharge'])."',ComSAmplitudeJournee='".$ComSAmplitudeJournee."',ComEAmplitudeJournee='".$ComEAmplitudeJournee."',
		ComSOrganisationTravail='".$ComSOrganisationTravail."',ComEOrganisationTravail='".$ComEOrganisationTravail."',
		ComSArticulationActiviteProPerso='".addslashes($_POST['ComSArticulationActiviteProPerso'])."',ComEArticulationActiviteProPerso='".addslashes($_POST['ComEArticulationActiviteProPerso'])."',
		ComSRemuneration='".$ComSRemuneration."',ComERemuneration='".$ComERemuneration."',Stress=".existeSinon_2('Stress').",ComSStress='".addslashes($_POST['ComSStress'])."',ComEStress='".addslashes($_POST['ComEStress'])."',EntretienRH=".$EntretienRH.",
		EntretienMedecienTravail=".$EntretienMedecienTravail.",EntretienLumanisy=".$EntretienLumanisy.",EntretienSoutienPsycho=".$EntretienSoutienPsycho.",EntretienHSE=".$EntretienHSE.",EntretienAutre=".$EntretienAutre.",
		FormationOrganisationTravail=".$FormationOrganisationTravail.",FormationStress=".$FormationStress.",FormationSophrologie=".$FormationSophrologie.",FormationAutre=".$FormationAutre.",ComEntretienRH='".addslashes($_POST['ComEntretienRH'])."',
		ComEntretienMedecienTravail='".addslashes($_POST['ComEntretienMedecienTravail'])."',ComEntretienLumanisy='".addslashes($_POST['ComEntretienLumanisy'])."',ComEntretienSoutienPsycho='".addslashes($_POST['ComEntretienSoutienPsycho'])."',
		ComEntretienHSE='".addslashes($_POST['ComEntretienHSE'])."',ComEntretienAutre='".addslashes($_POST['ComEntretienAutre'])."',ComEEntretienAutre='".addslashes($_POST['ComEEntretienAutre'])."',
		ComFormationOrganisationTravail='".addslashes($_POST['ComFormationOrganisationTravail'])."',ComFormationStress='".addslashes($_POST['ComFormationStress'])."',
		ComFormationAutre='".addslashes($_POST['ComFormationAutre'])."',ComEFormationAutre='".addslashes($_POST['ComEFormationAutre'])."',CommentaireLibreS='".addslashes($_POST['CommentaireLibreS'])."',CommentaireLibreE='".addslashes($_POST['CommentaireLibreE'])."',
		PointFort='".addslashes($_POST['PointFort'])."',PointFaible='".addslashes($_POST['PointFaible'])."',ObjectifProgression='".addslashes($_POST['ObjectifProgression'])."',ComSalarie='".addslashes($_POST['ComSalarie'])."',ComEvaluateur='".addslashes($_POST['ComEvaluateur'])."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);
		
		
		
		//Objectif de l'année précédentes
		
		$req="UPDATE epe_personne_objectifanneeprecedente SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);
		for($i=0;$i<10;$i++){
			if($_POST['ObjEvaluation'.$i]<>""){
				$req="INSERT INTO epe_personne_objectifanneeprecedente (Id_epepersonne,Evaluation,Note,Commentaire) 
					VALUES (".$_POST['Id_EPE'].",'".addslashes($_POST['ObjEvaluation'.$i])."',".existeSinon_2('ObjNote'.$i).",'".addslashes($_POST['ObjCommentaire'.$i])."') ";
				$resultAjout=mysqli_query($bdd,$req);
			}
		}

		//Objectif de l'année à venir
		
		$req="UPDATE epe_personne_objectifannee SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);
		for($i=0;$i<10;$i++){
			if($_POST['OAObjectif'.$i]<>""){
				$req="INSERT INTO epe_personne_objectifannee (Id_epepersonne,Objectif,Indicateur,MoyensAssocies,Commentaire) 
					VALUES (".$_POST['Id_EPE'].",'".addslashes($_POST['OAObjectif'.$i])."','".addslashes($_POST['OAIndicateurs'.$i])."','".addslashes($_POST['OAMoyensAssocies'.$i])."','".addslashes($_POST['OACommentaires'.$i])."') ";
				$resultAjout=mysqli_query($bdd,$req);
			}
		}
		
		//Bilan des formations annuelles réalisées
		
		$req="UPDATE epe_personne_bilanformation SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Type='For' AND Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		for($i=0;$i<$_POST['FAFormationFor'];$i++){
			$req="INSERT INTO epe_personne_bilanformation (Id_epepersonne,Type,Formation,DateDebut,DateFin,EvaluationAFroid,Commentaire) 
				VALUES (".$_POST['Id_EPE'].",'For','".addslashes($_POST['FAFormationFor2'.$i])."','".TrsfDate_($_POST['FADateDebutFor2'.$i])."','".TrsfDate_($_POST['FADateFinFor2'.$i])."',".existeSinon_2('FANoteFor'.$i).",'".addslashes($_POST['FACommentaireFor'.$i])."') ";
			$resultAjout=mysqli_query($bdd,$req);
		}
		
		$req="UPDATE epe_personne_bilanformation SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Type='Qua' AND Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);
		
		for($i=0;$i<$_POST['FAFormationQua'];$i++){
			$req="INSERT INTO epe_personne_bilanformation (Id_epepersonne,Type,Formation,DateDebut,DateFin,EvaluationAFroid,Commentaire) 
				VALUES (".$_POST['Id_EPE'].",'Qua','".addslashes($_POST['FAFormationQua2'.$i])."','".TrsfDate_($_POST['FADateDebutQua2'.$i])."','".TrsfDate_($_POST['FADateFinQua2'.$i])."',".existeSinon_2('FANoteQua'.$i).",'".addslashes($_POST['FACommentaireQua'.$i])."') ";
			$resultAjout=mysqli_query($bdd,$req);
		}
							
		$req="UPDATE epe_personne_bilanformation SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Type='' AND Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);			
		for($i=0;$i<5;$i++){
			if($_POST['FAFormation'.$i]<>""){
				$req="INSERT INTO epe_personne_bilanformation (Id_epepersonne,Formation,DateDebut,DateFin,EvaluationAFroid,Commentaire) 
					VALUES (".$_POST['Id_EPE'].",'".addslashes($_POST['FAFormation'.$i])."','".TrsfDate_($_POST['FADateDebut'.$i])."','".TrsfDate_($_POST['FADateFin'.$i])."',".existeSinon_2('FANote'.$i).",'".addslashes($_POST['FACommentaire'.$i])."') ";
				$resultAjout=mysqli_query($bdd,$req);
			}
		}
		
		
		//Besoins en formation (manager)
		$req="UPDATE epe_personne_besoinformation SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);
		for($i=0;$i<10;$i++){
			if($_POST['BFBesoin'.$i]<>""){
				$req="INSERT INTO epe_personne_besoinformation (Id_epepersonne,Formation,DateDebut, DateFin,Commentaire) 
					VALUES (".$_POST['Id_EPE'].",'".addslashes($_POST['BFBesoin'.$i])."','".TrsfDate_($_POST['BFDateDebut'.$i])."','".TrsfDate_($_POST['BFDateFin'.$i])."','".addslashes($_POST['BFCommentaire'.$i])."') ";
				$resultAjout=mysqli_query($bdd,$req);
			}
		}
		
		//Besoins en formation (salarié)
		$req="UPDATE epe_personne_souhaitformation SET Suppr=1, DateSuppr='".date('Y-m-d')."' WHERE Id_epepersonne=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);
		for($i=0;$i<10;$i++){
			if($_POST['SFFormation'.$i]<>""){
				$req="INSERT INTO epe_personne_souhaitformation (Id_epepersonne,Formation,Favorable,Priorite,Commentaire) 
					VALUES (".$_POST['Id_EPE'].",'".addslashes($_POST['SFFormation'.$i])."',".existeSinon_2('SFAvis'.$i).",'".addslashes($_POST['SFOrdre'.$i])."','".addslashes($_POST['SFCommentaire'.$i])."') ";
				$resultAjout=mysqli_query($bdd,$req);
			}
		}

		echo "<script>FermerEtRecharger();</script>";
		
	}
	elseif(isset($_POST['btnSupprimer'])){
		$req="UPDATE epe_personne SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr='".$_SESSION['Id_Personne']."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif(isset($_POST['btnSignerS'])){
		$req="UPDATE epe_personne SET DateSalarie='".date('Y-m-d')."', ComSalarie='".addslashes($_POST['ComSalarie'])."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif(isset($_POST['btnRefuserS'])){
		$req="UPDATE epe_personne SET SalarieRefuseSignature=1, DateSalarie='".date('Y-m-d')."', ComSalarie='".addslashes($_POST['ComSalarie'])."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif(isset($_POST['btnSignerE'])){
		$req="UPDATE epe_personne SET DateEvaluateur='".date('Y-m-d')."', ComEvaluateur='".addslashes($_POST['ComEvaluateur'])."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
}

$modeBrouillon=0;
if($_GET['Mode']=="B"){$modeBrouillon=1;}


	
$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,Id_Plateforme,
		(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
		(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
		ConnaissanceMetier,ComConnaissanceMetier,UtilisationDoc,ComUtilisationDoc,Productivite,ComProductivite,Organisation,ComOrganisation,CapaciteManager,ComCapaciteManager,
		RespectObjectif,ComRespectObjectif,AnglaisTech,ComAnglaisTech,CapaciteTuteur,ComCapaciteTuteur,Reporting,ComReporting,PlanAction,ComPlanAction,RespectBudget,ComRespectBudget,
		RepresentationEntreprise,ComRepresentationEntreprise,SouciSatisfaction,ComSouciSatisfaction,Ecoute,ComEcoute,TraitementInsatisfaction,ComTraitementInsatisfaction,ExplicationSolution,ComExplicationSolution,
		ComprehensionInsatisfaction,ComComprehensionInsatisfaction,ConnaissanceManagement,ComConnaissanceManagement,ConnaissanceMetierEquipe,ComConnaissanceMetierEquipe,CapaciteFixerObjectif,ComCapaciteFixerObjectif,
		Delegation,ComDelegation,AnimationEquipe,ComAnimationEquipe,RespectQSE,ComRespectQSE,ContributionNC,ComContributionNC,RespectRegles,ComRespectRegles,PortTenues,ComPortTenues,
		PortEPI,ComPortEPI,RespectOutils,ComRespectOutils,Assiduite,ComAssiduite,EspritEntreprise,ComEspritEntreprise,TravailEquipe,ComTravailEquipe,Dispo,ComDispo,Autonomie,ComAutonomie,Initiative,ComInitiative,
		Communication,ComCommunication,OrganisationCharge,ComSOrganisationCharge,ComEOrganisationCharge,AmplitudeJournee,ComSAmplitudeJournee,ComEAmplitudeJournee,OrganisationTravail,
		ComSOrganisationTravail,ComEOrganisationTravail,ArticulationActiviteProPerso,ComSArticulationActiviteProPerso,ComEArticulationActiviteProPerso,Remuneration,ComSRemuneration,
		ComERemuneration,Stress,ComSStress,ComEStress,EntretienRH,EntretienMedecienTravail,EntretienLumanisy,EntretienSoutienPsycho,EntretienHSE,EntretienAutre,FormationOrganisationTravail,FormationStress,
		FormationSophrologie,FormationAutre,ComEntretienRH,ComEntretienMedecienTravail,ComEntretienLumanisy,ComEntretienSoutienPsycho,ComEntretienHSE,ComEntretienAutre,ComEEntretienAutre,
		ComFormationOrganisationTravail,ComFormationStress,ComFormationSophrologie,ComFormationAutre,ComEFormationAutre,CommentaireLibreS,CommentaireLibreE,
		PointFort,PointFaible,ObjectifProgression,ComSalarie,ComEvaluateur,SalarieRefuseSignature,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND ModeBrouillon=".$modeBrouillon."
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPE'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);

$Plateforme="";
$Id_Plateforme=$rowEPERempli['Id_Plateforme'];
$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Plateforme=$RowPresta['Libelle'];
}

$Manager=stripslashes($rowEPERempli['Manager']);
$MatriculeAAAManager=$rowEPERempli['MatriculeAAAManager'];
$MetierManager=stripslashes($rowEPERempli['MetierManager']);

$requete="SELECT Id,IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".($rowEPE['Annee']-1).")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".($rowEPE['Annee']-1)."),
			'A faire')
			AS Etat,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".($rowEPE['Annee']-1).")>0,
			(SELECT IF(TypeCadre=0,".$_GET['Cadre'].",IF(TypeCadre=1,0,1))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".($rowEPE['Annee']-1)." LIMIT 1),
			".$_GET['Cadre'].") AS Cadre
			FROM epe_personne_datebutoir
			WHERE Id_Personne=".$rowEPE['Id']."
			AND TypeEntretien='EPE'
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir))=".($rowEPE['Annee']-1);
$result_1=mysqli_query($bdd,$requete);
$Nb_1=mysqli_num_rows($result_1);
?>

<form id="formulaire" class="test" action="Modif_EPE.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Id_EPE" id="Id_EPE" value="<?php echo $rowEPERempli['Id']; ?>" />
	<input type="hidden" name="Cadre" id="Cadre" value="<?php echo $_GET['Cadre']; ?>" />
	<input type="hidden" name="Etat" id="Etat" value="<?php echo $rowEPERempli['Etat']; ?>" />
	<?php 
		if($Nb_1>0){
			$rowEPE_1=mysqli_fetch_array($result_1);
			if($rowEPE_1['Etat']=="Réalisé"){
				echo "<tr><td class='Libelle' align='right'>EPE ".($rowEPE['Annee']-1)." :";
	?>
		<a class="Modif" href="javascript:EPE_PDF(<?php echo $rowEPE_1['Id']; ?>,<?php echo $rowEPE_1['Cadre']; ?>);">
			<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
		</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php
				echo "</td></tr>";
			}
		}
		if($rowEPERempli['Etat']=="Brouillon"){
		?>
	<tr>
		<td class="Libelle2" align="right">
			<a class="Modif" style="color:#000000;" href="javascript:EPE_Excel(<?php echo $rowEPERempli['Id']; ?>,<?php echo $_GET['Cadre']; ?>);">
				D-0705-012-EPE
			</a>&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<?php	
			
		}
	?>
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#1a0078;">
				<tr>
					<td class="TitrePage" align="center" style="color:#ffffff;">
					<?php
						if($_GET['Cadre']==0){
							if($_SESSION["Langue"]=="FR"){echo "ENTRETIEN PROFESSIONNEL D'EVALUATION - E.P.E<br>NON CADRES";}else{echo "PROFESSIONAL EVALUATION INTERVIEW - P.E.I<br>NON-EXECUTIVE";}
						}
						else{
							if($_SESSION["Langue"]=="FR"){echo "ENTRETIEN PROFESSIONNEL D'EVALUATION - E.P.E<br>CADRES";}else{echo "PROFESSIONAL EVALUATION INTERVIEW - P.E.I<br>EXECUTIVE";}
						}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php if($rowEPERempli['Etat']=="Brouillon"){ ?>
	<tr>
		<td class="Libelle2" colspan="6" align="right">Brouillon
			<select name="modeBrouillon" id="modeBrouillon">
				<option value="0" <?php if($rowEPERempli['ModeBrouillon']==0){echo "selected";} ?>>Non</option>
				<option value="1" <?php if($rowEPERempli['ModeBrouillon']==1){echo "selected";} ?>>Oui</option>
			</select>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $rowEPERempli['MatriculeAAA']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'entretien";}else{echo "Interview date";} ?></td>
							<td width="30%"><?php if($_GET['Mode']=="B"){echo date('d/m/Y');}else{echo AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']);} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Name";} ?></td>
							<td width="30%"><?php echo $rowEPERempli['Nom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
							<td width="30%"><?php echo $Plateforme; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="30%"><?php echo $rowEPERempli['Prenom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Evaluateur";}else{echo "Evaluator";} ?></td>
							<td width="30%"><?php echo $Manager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction/métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo stripslashes($rowEPERempli['Metier']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $MatriculeAAAManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
							<td width="30%"><?php echo AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction /métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo stripslashes($MetierManager); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "1. EPE - Bilan annuel Global - Performance individuelle annuelle";}else{echo "1. EPE - Global annual report - Annual individual performance";} ?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="2" style="color:#ffffff;">RAPPEL DES OBJECTIFS DE L'ANNEE ECOULEE</td>
							<td colspan="5" style="color:#ffffff;"></td>
							<td class="Libelle2" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<tr bgcolor="8f8b8f">
							<td width="3%" class="Libelle2" align="center">
							<td width="25%" class="Libelle2">Evaluation*</td>
							<td width="5%" class="Libelle2" align="center">NA</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="60%" class="Libelle2">
							*NA  = non applicable, ex : absence longue durée sur la période de réalisation , ou arrivé  en  fin de période d'évaluation;<br>
							1 = Résultats non atteints;<br>
							2 = Résultats partiellement atteints;<br>
							3 = Résultats atteints;<br>
							4 = Résultats dépassés
							</td>
						</tr>
						<?php 
						
						$req="SELECT Id, Evaluation, Note, Commentaire
						FROM epe_personne_objectifanneeprecedente 
						WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";

						$resultAnneePrec=mysqli_query($bdd,$req);
						$resultAnneePrec2=mysqli_query($bdd,$req);
						$NbAnneePrec=mysqli_num_rows($resultAnneePrec);
						
						$rowAnneePrec2=mysqli_fetch_array($resultAnneePrec2);
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							$rowAnneePrec=mysqli_fetch_array($resultAnneePrec);
							$rowAnneePrec2=mysqli_fetch_array($resultAnneePrec2);
							
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="Obj<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2 && $rowAnneePrec['Id']==0){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if(($i>1 && $i<>9) && $rowAnneePrec2['Id']==0 && $rowEPERempli['ModeBrouillon']==1){ ?>
								<input class="Bouton OObj<?php echo $i;?>" type="button" name="newObj" id="newObj" onclick="AfficherTRObj(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="25%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="60" name="ObjEvaluation<?php echo $i;?>" id="ObjEvaluation<?php echo $i;?>" value="<?php echo stripslashes($rowAnneePrec['Evaluation']);?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="-1" <?php if($rowAnneePrec['Note']==-1){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="1" <?php if($rowAnneePrec['Note']==1){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="2" <?php if($rowAnneePrec['Note']==2){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="3" <?php if($rowAnneePrec['Note']==3){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="4" <?php if($rowAnneePrec['Note']==4){echo "checked";} ?>></td>
								<td width="60%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="90" name="ObjCommentaire<?php echo $i;?>" id="ObjCommentaire<?php echo $i;?>" value="<?php echo stripslashes($rowAnneePrec['Commentaire']);?>">
								</td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "2. EPE - Grille d'évaluation des compétences";}else{echo "2. EPE - Skills assessment grid";} ?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2"  style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "TECHNIQUE";}else{echo "TECHNICAL ";} ?></td>
							<td colspan="5" style="color:#ffffff;"></td>
							<td class="Libelle2" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<tr bgcolor="8f8b8f">
							<td width="25%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Focus métier";}else{echo "Business focus";} ?></td>
							<td width="5%" class="Libelle2" align="center">NA</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="60%" class="Libelle2">
							* NA = Le salarié n'est pas concerné par le critère d'évaluation (ex : pas de management, pas d'anglais requis…);<br>
							1 = Compétence insuffisante par rapport aux attentes;<br>
							2 = Compétence partielle par rapport aux attentes;<br>
							3 = Compétences maîtrisées;<br>
							4 = Au-delà des compétences attendues
							</td>
						</tr>
						<?php 
						$tab=array('Connaissance et maitrise technique poste et métier (règles de l’art, lecture de plans…)','Utilisation des documents de travail (Qualité, …)','Productivité – rapidité d’exécution','Organisation dans le travail','Capacité à manager un projet','Respect des objectifs (délais fixés, …)','Anglais technique ','Capacité à tutorer (aptitude pédagogique à transmettre son savoir)','Reporting','Mise en place et suivi des plans d’actions','Respect des lignes budgétaires, des coûts / délais');
						$tab2=array('ConnaissanceMetier','UtilisationDoc','Productivite','Organisation','CapaciteManager','RespectObjectif','AnglaisTech','CapaciteTuteur','Reporting','PlanAction','RespectBudget'); 
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="25%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1" <?php if($rowEPERempli[$tab2[$i]]==-1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1" <?php if($rowEPERempli[$tab2[$i]]==1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2" <?php if($rowEPERempli[$tab2[$i]]==2){echo "checked";} ?>></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3" <?php if($rowEPERempli[$tab2[$i]]==3){echo "checked";} ?>></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4" <?php if($rowEPERempli[$tab2[$i]]==4){echo "checked";} ?>></td>
								<td width="60%" class="Libelle2"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['Com'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
						?>
						<tr bgcolor="8f8b8f" height="35px">
							<td width="25%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Focus Relation Client (interne comme externe)";}else{echo "Customer Relationship Focus (internal and external)";} ?></td>
							<td width="5%" class="Libelle2" align="center">NA</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="60%" class="Libelle2"></td>
						</tr>
						<?php 
						$tab=array('Représentation de l’entreprise auprès du client','Souci de satisfaction client / sens du service','Ecoute et empathie','Qualité de traitement des insatisfactions','Explication des solutions, valorisation','Compréhension des raisons de l’insatisfaction');
						$tab2=array('RepresentationEntreprise','SouciSatisfaction','Ecoute','TraitementInsatisfaction','ExplicationSolution','ComprehensionInsatisfaction'); 
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="25%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1" <?php if($rowEPERempli[$tab2[$i]]==-1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1" <?php if($rowEPERempli[$tab2[$i]]==1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2" <?php if($rowEPERempli[$tab2[$i]]==2){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3" <?php if($rowEPERempli[$tab2[$i]]==3){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4" <?php if($rowEPERempli[$tab2[$i]]==4){echo "checked";} ?>></td>
								<td width="60%" class="Libelle2"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['Com'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
						?>
						<tr bgcolor="8f8b8f" height="35px">
							<td width="25%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Focus Management";}else{echo "Focus Management";} ?></td>
							<td width="5%" class="Libelle2" align="center">NA</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="60%" class="Libelle2"></td>
						</tr>
						<?php 
						$tab=array('Connaissance des techniques de management','Connaissance des métiers de ses équipes','Capacité à fixer des objectifs','Aptitude à la délégation','Animation et gestion d’équipe');
						$tab2=array('ConnaissanceManagement','ConnaissanceMetierEquipe','CapaciteFixerObjectif','Delegation','AnimationEquipe'); 
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="25%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1" <?php if($rowEPERempli[$tab2[$i]]==-1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1" <?php if($rowEPERempli[$tab2[$i]]==1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2" <?php if($rowEPERempli[$tab2[$i]]==2){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3" <?php if($rowEPERempli[$tab2[$i]]==3){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4" <?php if($rowEPERempli[$tab2[$i]]==4){echo "checked";} ?>></td>
								<td width="60%" class="Libelle2"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['Com'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
						?>
						<tr bgcolor="8f8b8f" height="35px">
							<td width="25%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Focus Qualité - Sécurité - Environnement";}else{echo "Focus Quality - Safety - Environment";} ?></td>
							<td width="5%" class="Libelle2" align="center">NA</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="60%" class="Libelle2"></td>
						</tr>
						<?php 
						$tab=array('Respect des normes QSE en vigueur','Contribution aux NC et actions correctives associées','Respect des consignes, règles et procédures','Port des tenues identifiées AAA','Port des EPI','Respect des outils et / ou matériels mis à disposition');
						$tab2=array('RespectQSE','ContributionNC','RespectRegles','PortTenues','PortEPI','RespectOutils');
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="25%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1" <?php if($rowEPERempli[$tab2[$i]]==-1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1" <?php if($rowEPERempli[$tab2[$i]]==1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2" <?php if($rowEPERempli[$tab2[$i]]==2){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3" <?php if($rowEPERempli[$tab2[$i]]==3){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4" <?php if($rowEPERempli[$tab2[$i]]==4){echo "checked";} ?>></td>
								<td width="60%" class="Libelle2"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['Com'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2"  style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "COMPORTEMENT";}else{echo "BEHAVIOUR";} ?></td>
							<td colspan="5" style="color:#ffffff;"></td>
							<td class="Libelle2" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<tr bgcolor="8f8b8f" height="35px">
							<td width="25%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Savoir être Général";}else{echo "Knowing how to be General";} ?></td>
							<td width="5%" class="Libelle2" align="center">NA</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="60%" class="Libelle2">
							* NA = Le salarié n'est pas concerné par le critère d'évaluation (ex : pas de management, pas d'anglais requis…);<br>
							1 = Compétence insuffisante par rapport aux attentes;<br>
							2 = Compétence partielle par rapport aux attentes;<br>
							3 = Compétences maîtrisées;<br>
							4 = Au-delà des compétences attendues
							</td>
						</tr>
						<?php 
						$tab=array('Assiduité','Esprit d’entreprise / engagement','Capacité à travailler en équipe','Disponibilité / implication','Autonomie','Initiative','Communication / relationnel');
						$tab2=array('Assiduite','EspritEntreprise','TravailEquipe','Dispo','Autonomie','Initiative','Communication');
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="25%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1" <?php if($rowEPERempli[$tab2[$i]]==-1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1" <?php if($rowEPERempli[$tab2[$i]]==1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2" <?php if($rowEPERempli[$tab2[$i]]==2){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3" <?php if($rowEPERempli[$tab2[$i]]==3){echo "checked";} ?>></td>
								<td width="5%" align="center"><input <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4" <?php if($rowEPERempli[$tab2[$i]]==4){echo "checked";} ?>></td>
								<td width="60%" class="Libelle2"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['Com'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "3. EPE - Définition des objectifs annuels à venir";}else{echo "3. EPE - Definition of future annual objectives";} ?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" ></td>
							<td width="30%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Objectifs";}else{echo "Target";} ?></td>
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Indicateurs";}else{echo "Indicators";} ?></td>
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Moyens associés";}else{echo "Associated means";} ?></td>
							<td width="27%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<?php 
						
						$req="SELECT Id, Objectif, Indicateur, MoyensAssocies, Commentaire
						FROM epe_personne_objectifannee 
						WHERE Suppr=0 AND  Id_epepersonne=".$rowEPERempli['Id']." ";

						$resultAnnee=mysqli_query($bdd,$req);
						$resultAnnee2=mysqli_query($bdd,$req);
						$NbAnnee=mysqli_num_rows($resultAnnee);
						
						$rowAnnee2=mysqli_fetch_array($resultAnnee2);
						
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							$rowAnnee=mysqli_fetch_array($resultAnnee);
							$rowAnnee2=mysqli_fetch_array($resultAnnee2);
							
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="OA<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2 && $rowAnnee['Id']==0){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if(($i>1 && $i<>9)  && $rowAnnee2['Id']==0 && $rowEPERempli['ModeBrouillon']==1){ ?>
								<input class="Bouton OOA<?php echo $i;?>" type="button" name="newOA" id="newOA" onclick="AfficherTR(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="60" name="OAObjectif<?php echo $i;?>" id="OAObjectif<?php echo $i;?>" value="<?php echo stripslashes($rowAnnee['Objectif']);?>"></td>
								<td width="20%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="30" name="OAIndicateurs<?php echo $i;?>" id="OAIndicateurs<?php echo $i;?>" value="<?php echo stripslashes($rowAnnee['Indicateur']);?>"></td>
								<td width="20%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="30" name="OAMoyensAssocies<?php echo $i;?>" id="OAMoyensAssocies<?php echo $i;?>" value="<?php echo stripslashes($rowAnnee['MoyensAssocies']);?>"></td>
								<td width="27%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="60" name="OACommentaires<?php echo $i;?>" id="OACommentaires<?php echo $i;?>" value="<?php echo stripslashes($rowAnnee['Commentaire']);?>">
								</td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "4. EPE - Formations";}else{echo "4. EPE - Training";} ?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" ></td>
							<td width="30%" class="Libelle2" align="center" style="color:#ffffff;" >Bilan des formations annuelles réalisées</td>
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" colspan="2">Période</td>
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" colspan="4">CO-EVALUATION à froid Manager/Salarié</td>
							<td width="47%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<tr bgcolor="#d9d9d9">
							<td width="3%" class="Libelle2" align="center" ></td>
							<td width="30%" class="Libelle2" align="center">Intitulé de la formation</td>
							<td width="10%" class="Libelle2" align="center">date début</td>
							<td width="10%" class="Libelle2" align="center">date fin</td>
							<td width="5%" class="Libelle2" align="center">1</td>
							<td width="5%" class="Libelle2" align="center">2</td>
							<td width="5%" class="Libelle2" align="center">3</td>
							<td width="5%" class="Libelle2" align="center">4</td>
							<td width="47%" class="Libelle2" align="center">1= Insuffisant ; 2 = Moyen ; 3 = Efficace ; 4 = Très efficace </td>
						</tr>
						<?php 
						$couleur="#d4d2d4";
						
						//Liste des formations et qualifications de l'année précédente 
						$req="SELECT Formation, DateDebut, DateFin, EvaluationAFroid, Commentaire 
								FROM epe_personne_bilanformation 
								WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." 
								AND Type='For' ";
						$result2=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result2);
						$i=0;
						if($nbenreg>0){
							while($row2=mysqli_fetch_array($result2)){
								if($couleur=="#ffffff"){$couleur="#d4d2d4";}
								else{$couleur="#ffffff";}
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='hidden' size="60" name="FAFormationFor2<?php echo $i;?>" id="FAFormationFor2<?php echo $i;?>" value="<?php echo stripslashes($row2['Formation']);?>"><input type='text' size="60" disabled name="FAFormationFor<?php echo $i;?>" id="FAFormationFor<?php echo $i;?>" value="<?php echo stripslashes($row2['Formation']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='hidden' size="10" name="FADateDebutFor2<?php echo $i;?>" id="FADateDebutFor2<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateDebut']);?>"><input type='date' size="10" disabled name="FADateDebutFor<?php echo $i;?>" id="FADateDebutFor<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateDebut']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='hidden' size="10" name="FADateFinFor2<?php echo $i;?>" id="FADateFinFor2<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateFin']);?>"><input type='date' size="10" disabled name="FADateFinFor<?php echo $i;?>" id="FADateFinFor<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateFin']);?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="1" <?php if($row2['EvaluationAFroid']==1){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="2" <?php if($row2['EvaluationAFroid']==2){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="3" <?php if($row2['EvaluationAFroid']==3){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="4" <?php if($row2['EvaluationAFroid']==4){echo "checked";} ?>></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="90" name="FACommentaireFor<?php echo $i;?>"  id="FACommentaireFor<?php echo $i;?>" value="<?php echo stripslashes($row2['Commentaire']);?>">
								</td>
							</tr>
						<?php	
								$i++;
							}
						}
						echo "<tr><td><input type='hidden' name='FAFormationFor' id='FAFormationFor' value='".$i."'></td></tr>";
						
						$req="SELECT Formation, DateDebut, DateFin, EvaluationAFroid, Commentaire 
								FROM epe_personne_bilanformation 
								WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." 
								AND Type='Qua' ";
						$ListeQualification=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($ListeQualification);
						$i=0;
						if($nbenreg>0){
							
							while($row2=mysqli_fetch_array($ListeQualification)){
								if($couleur=="#ffffff"){$couleur="#d4d2d4";}
								else{$couleur="#ffffff";}
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='hidden' size="60" name="FAFormationQua2<?php echo $i;?>" id="FAFormationQua2<?php echo $i;?>" value="<?php echo stripslashes($row2['Formation']);?>"><input type='text' size="60" disabled name="FAFormationQua<?php echo $i;?>" id="FAFormationQua<?php echo $i;?>" value="<?php echo stripslashes($row2['Formation']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='hidden' size="10" name="FADateDebutQua2<?php echo $i;?>" id="FADateDebutQua2<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateDebut']);?>"><input type='date' size="10" disabled name="FADateDebutQua<?php echo $i;?>" id="FADateDebutQua<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateDebut']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='hidden' size="10" name="FADateFinQua2<?php echo $i;?>" id="FADateFinQua2<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateFin']);?>"><input type='date' size="10" disabled name="FADateFinQua<?php echo $i;?>" id="FADateFinQua<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateFin']);?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="1" <?php if($row2['EvaluationAFroid']==1){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="2" <?php if($row2['EvaluationAFroid']==2){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="3" <?php if($row2['EvaluationAFroid']==3){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="4" <?php if($row2['EvaluationAFroid']==4){echo "checked";} ?>></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="90" name="FACommentaireQua<?php echo $i;?>"  id="FACommentaireQua<?php echo $i;?>" value="<?php echo stripslashes($row2['Commentaire']);?>">
								</td>
							</tr>
						<?php
								$i++;
							}
						}
						echo "<tr><td><input type='hidden' name='FAFormationQua' id='FAFormationQua' value='".$i."'></td></tr>";
						
						$req="SELECT Id, Formation, DateDebut, DateFin, EvaluationAFroid, Commentaire 
								FROM epe_personne_bilanformation 
								WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." 
								AND Type='' ";
						$ListeFor=mysqli_query($bdd,$req);
						$ListeFor2=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($ListeFor);
						
						$rowFor2=mysqli_fetch_array($ListeFor2);
						
						for($i=0;$i<5;$i++){
							$rowFor=mysqli_fetch_array($ListeFor);
							$rowFor2=mysqli_fetch_array($ListeFor2);
							
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="FA<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>1 && $rowFor['Id']==0){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if(($i>0 && $i<>4) && $rowFor2['Id']==0 && $rowEPERempli['ModeBrouillon']==1){ ?>
								<input class="Bouton FFA<?php echo $i;?>" type="button" name="newFA" id="newFA" onclick="AfficherTRFA(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="60" name="FAFormation<?php echo $i;?>" id="FAFormation<?php echo $i;?>" value="<?php echo stripslashes($rowFor['Formation']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="10" name="FADateDebut<?php echo $i;?>" id="FADateDebut<?php echo $i;?>" value="<?php echo AfficheDateFR($rowFor['DateDebut']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="10" name="FADateFin<?php echo $i;?>" id="FADateFin<?php echo $i;?>" value="<?php echo AfficheDateFR($rowFor['DateFin']);?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="1" <?php if($rowFor['EvaluationAFroid']==1){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="2" <?php if($rowFor['EvaluationAFroid']==2){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="3" <?php if($rowFor['EvaluationAFroid']==3){echo "checked";} ?>></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="4" <?php if($rowFor['EvaluationAFroid']==4){echo "checked";} ?>></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="90" name="FACommentaire<?php echo $i;?>"  id="FACommentaire<?php echo $i;?>" value="<?php echo stripslashes($rowFor['Commentaire']);?>">
								</td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" ></td>
							<td width="30%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Besoins en formation identifié par le manager";}else{echo "Training needs identified by the manager ";} ?></td>
							<td width="10%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";} ?></td>
							<td width="10%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Date fin";}else{echo "End date";} ?></td>
							<td width="47%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<tr bgcolor="#d2d4d4">
							<td height="5" colspan="5" align="center" class="Libelle2">RECHERCHE BESOIN</td>
						</tr>
						<tr bgcolor="#d2d4d4">
							<td>
								<td colspan="2" align="center" class="Libelle2">Recherche : <input type='text' size="60" name="BFRecherche" id="BFRecherche" value="">
								<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filter();"/>
								</td>
								<td colspan="2" align="center" class="Libelle2">Besoin : 
									<?php 
										$Req="
											SELECT Libelle AS Ref,
											'' AS Categorie
											FROM new_competences_formation 
											
											UNION 
											
											SELECT
												new_competences_qualification.Libelle AS Ref,
												new_competences_categorie_qualification.Libelle AS Categorie
											FROM
												new_competences_qualification,
												new_competences_categorie_qualification
											WHERE
												new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
											ORDER BY
												Ref ASC,
												Categorie ASC ";
										$Liste=mysqli_query($bdd,$Req);
										$nbenreg=mysqli_num_rows($Liste);
									?>
									<select name="QualifRecherche" id="QualifRecherche" style="display:none;">
										<option value="0"></option>
										<?php 
											if($nbenreg>0){
												while($rowListe=mysqli_fetch_array($Liste)){
													$categorie="";
													if($rowListe['Categorie']<>""){$categorie.=" (".$rowListe['Categorie'].")";}
												?>
													<option value="<?php echo stripslashes($rowListe['Ref'].$categorie); ?>"><?php echo stripslashes($rowListe['Ref'].$categorie); ?></option>
												<?php
												}
											}
										?>
									</select>
									<input name="BFQualifAjoute" id="BFQualifAjoute" value="0" style="display:none;" />
									<input name="BFQuestionPose" id="BFQuestionPose" value="0" style="display:none;" />
									<select name="BFSelectBesoin" id="BFSelectBesoin" style="width:300px;">
										<option value="0"></option>
										<?php 
											if($nbenreg>0){
												$Liste=mysqli_query($bdd,$Req);
												while($rowListe=mysqli_fetch_array($Liste)){
													$categorie="";
													if($rowListe['Categorie']<>""){$categorie.=" (".$rowListe['Categorie'].")";}
												?>
													<option value="<?php echo stripslashes($rowListe['Ref'].$categorie); ?>"><?php echo stripslashes($rowListe['Ref'].$categorie); ?></option>
												<?php
												}
											}
										?>
									</select>
									<input class="Bouton" id="BtnBFAjouter" name="BtnBFAjouter" type="button" value="Ajouter" onclick="AjouterBFBesoin()"/>
								</td>
							</td>
						</tr>
						<?php 
						$requete="SELECT Id, Formation,DateDebut, DateFin, Commentaire 
								FROM epe_personne_besoinformation 
								WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
						$ListeFor=mysqli_query($bdd,$requete);
						$ListeFor2=mysqli_query($bdd,$requete);
						$nbenreg=mysqli_num_rows($ListeFor);
						
						$rowFor2=mysqli_fetch_array($ListeFor2);
						
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							$rowFor=mysqli_fetch_array($ListeFor);
							$rowFor2=mysqli_fetch_array($ListeFor2);
							
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="BF<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2 && $rowFor['Id']==0){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if(($i>1 && $i<>9) && $rowFor2['Id']==0 && $rowEPERempli['ModeBrouillon']==1){ ?>
								<input class="Bouton BBF<?php echo $i;?>" type="button" name="newBF" id="newBF" onclick="AfficherTR2(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="60" name="BFBesoin<?php echo $i;?>" id="BFBesoin<?php echo $i;?>" value="<?php echo stripslashes($rowFor['Formation']);?>"  onKeyUp="VerifQualif(<?php echo $i;?>)"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="30" name="BFDateDebut<?php echo $i;?>" id="BFDateDebut<?php echo $i;?>" value="<?php echo AfficheDateFR($rowFor['DateDebut']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="30" name="BFDateFin<?php echo $i;?>" id="BFDateFin<?php echo $i;?>" value="<?php echo AfficheDateFR($rowFor['DateFin']);?>"></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="90" name="BFCommentaire<?php echo $i;?>"  id="BFCommentaire<?php echo $i;?>" value="<?php echo stripslashes($rowFor['Commentaire']);?>">
								</td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" ></td>
							<td width="30%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Souhait de formation exprimé par le salarié";}else{echo "Training wish expressed by the employee";} ?></td>
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Avis évaluateur";}else{echo "Evaluator opinion";} ?></td>
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" >Ordre de priorité :<br>1 : Prioritaire pour l'activité,<br>2 : nécessaire,<br>3 : non urgent</td>
							<td width="27%" class="Libelle2" align="center" style="color:#ffffff;" ><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires";}else{echo "Comments";} ?></td>
						</tr>
						<tr bgcolor="#d2d4d4">
							<td height="5" colspan="5" align="center" class="Libelle2">RECHERCHE BESOIN</td>
						</tr>
						<tr bgcolor="#d2d4d4">
							<td>
								<td colspan="2" align="center" class="Libelle2">Recherche : <input type='text' size="60" name="SFRecherche" id="SFRecherche" value="">
								<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filterSF();"/>
								</td>
								<td colspan="3" align="center" class="Libelle2">Besoin : 
									<select name="SFSelectBesoin" id="SFSelectBesoin" style="width:300px;">
										<option value="0"></option>
										<?php 
											if($nbenreg>0){
												$Liste=mysqli_query($bdd,$Req);
												while($rowListe=mysqli_fetch_array($Liste)){
													$categorie="";
													if($rowListe['Categorie']<>""){$categorie.=" (".$rowListe['Categorie'].")";}
												?>
													<option value="<?php echo stripslashes($rowListe['Ref'].$categorie); ?>"><?php echo stripslashes($rowListe['Ref'].$categorie); ?></option>
												<?php
												}
											}
										?>
									</select>
									<input name="SFQualifAjoute" id="SFQualifAjoute" value="0" style="display:none;" />
									<input name="SFQuestionPose" id="SFQuestionPose" value="0" style="display:none;" />
									<input class="Bouton" id="BtnSFAjouter" name="BtnSFAjouter" type="button" value="Ajouter" onclick="AjouterSFBesoin()"/>
								</td>
							</td>
						</tr>
						<tr bgcolor="#d2d4d4">
							<td height="5" colspan="5"></td>
						</tr>
						<?php 
						$req="SELECT Id, Formation, Favorable,Priorite, Commentaire 
								FROM epe_personne_souhaitformation 
								WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
						$ListeFor=mysqli_query($bdd,$req);
						$ListeFor2=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($ListeFor);
						
						$rowFor2=mysqli_fetch_array($ListeFor2);
						
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							$rowFor=mysqli_fetch_array($ListeFor);
							$rowFor2=mysqli_fetch_array($ListeFor2);
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="SF<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2 && $rowFor['Id']==0){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if(($i>1 && $i<>9) && $rowFor2['Id']==0 && $rowEPERempli['ModeBrouillon']==1){ ?>
								<input class="Bouton SSF<?php echo $i;?>" type="button" name="newSF" id="newSF" onclick="AfficherTR3(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="60" name="SFFormation<?php echo $i;?>" id="SFFormation<?php echo $i;?>" value="<?php echo stripslashes($rowFor['Formation']);?>"  onKeyUp="VerifFormation(<?php echo $i;?>)"></td>
								<td width="20%" class="Libelle2"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="SFAvis<?php echo $i;?>" name="SFAvis<?php echo $i;?>" value="1" <?php if($rowFor['Favorable']==1){echo "checked";} ?>>Favorable<br><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="SFAvis<?php echo $i;?>" name="SFAvis<?php echo $i;?>" value="0" <?php if($rowFor['Favorable']==0 && $rowFor['Id']>0){echo "checked";} ?>>Défavorable</td>
								<td width="20%" class="Libelle2" align="center">
									<select <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="SFOrdre<?php echo $i;?>" id="SFOrdre<?php echo $i;?>">
										<option value="0" <?php if($rowFor['Priorite']==0){echo "selected";} ?>></option>
										<option value="1" <?php if($rowFor['Priorite']==1){echo "selected";} ?>>1</option>
										<option value="2" <?php if($rowFor['Priorite']==2){echo "selected";} ?>>2</option>
										<option value="3" <?php if($rowFor['Priorite']==3){echo "selected";} ?>>3</option>
									</select>
								</td>
								<td width="47%" class="Libelle2" align="center"><input type='text' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> size="90" id="SFCommentaire<?php echo $i;?>" name="SFCommentaire<?php echo $i;?>" value="<?php echo stripslashes($rowFor['Commentaire']);?>">
								</td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php 
			if($_GET['Cadre']==0){
				if($_SESSION["Langue"]=="FR"){echo "5. EPE - Suivi de la charge de travail";}else{echo "5. EPE - Workload monitoring";} 
			}
			else{
				if($_SESSION["Langue"]=="FR"){echo "5. EPE - Suivi du forfait en jour";}else{echo "5. EPE - Package follow-up in days";}
			}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2" align="center" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "item";}else{echo "item";} ?></td>
							<td align="center" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires du salarié";}else{echo "Employee comments ";} ?></td>
							<td class="Libelle2"  align="center" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires de l'évaluateur";}else{echo "Evaluator's Comments";} ?></td>
						</tr>
						<?php 
						if($_GET['Cadre']==0){
							$tab=array('Organisation et charge de travail','Articulation entre activité professionnelle et vie personnelle et familiale');
							$tab2=array('OrganisationCharge','ArticulationActiviteProPerso');
						}
						else{
							$tab=array('Organisation et charge de travail','Amplitude des journées d’activité','Organisation du travail dans l’entreprise ','Articulation entre activité professionnelle et vie personnelle et familiale','Conformité par rapport à la grille de rémunération conventionnelle');
							$tab2=array('OrganisationCharge','AmplitudeJournee','OrganisationTravail','ArticulationActiviteProPerso','Remuneration');
						}
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="20%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComS<?php echo $tab2[$i];?>" id="ComS<?php echo $tab2[$i];?>" cols="60" rows="2" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComS'.$tab2[$i]]);?></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComE<?php echo $tab2[$i];?>" id="ComE<?php echo $tab2[$i];?>" cols="60" rows="2" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComE'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "6. EPE - Temps d'écoute";}else{echo "6. EPE - Listening time";}?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2" align="center" style="color:#ffffff;" rowspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "item";}else{echo "item";} ?></td>
							<td colspan="4"  align="center" style="color:#ffffff;"><?php if($_SESSION["Langue"]=="FR"){echo "Evaluation et commentaires du salarié";}else{echo "Employee evaluation and comments ";} ?></td>
							<td class="Libelle2"  align="center" style="color:#ffffff;" rowspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaires de l'évaluateur";}else{echo "Evaluator's Comments";} ?></td>
						</tr>
						<tr bgcolor="8f8b8f" height="35px">
							<td class="Libelle2" align="center">1</td>
							<td class="Libelle2" align="center">2</td>
							<td class="Libelle2" align="center">3</td>
							<td class="Libelle2" align="center">1= Je ne me sens pas bien ; 2 = Je me sens bien ; 3 = Je me sens très bien</td>
						</tr>
						<?php 
						$tab=array('Comment évalueriez vous votre niveau stress ?');
						$tab2=array('Stress');
						$couleur="#d4d2d4";
						for($i=0;$i<sizeof($tab);$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="20%" class="Libelle2"><?php echo $tab[$i]; ?></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" value="1" <?php if($tab2[$i]=="Stress"){echo "onclick='AfficherRPS()'";}?> <?php if($rowEPERempli[$tab2[$i]]==1){echo "checked";} ?>></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" value="2" <?php if($tab2[$i]=="Stress"){echo "onclick='AfficherRPS()'";}?> <?php if($rowEPERempli[$tab2[$i]]==2){echo "checked";} ?>></td>
								<td width="5%" align="center"><input type='radio' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" value="3" <?php if($tab2[$i]=="Stress"){echo "onclick='AfficherRPS()'";}?> <?php if($rowEPERempli[$tab2[$i]]==3){echo "checked";} ?>></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComS<?php echo $tab2[$i];?>" id="ComS<?php echo $tab2[$i];?>" cols="45" rows="2" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComS'.$tab2[$i]]);?></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComE<?php echo $tab2[$i];?>" id="ComE<?php echo $tab2[$i];?>" cols="45" rows="2" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComE'.$tab2[$i]]);?></textarea></td>
							</tr>
						<?php
						}
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>	
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="20%" class="Libelle2" rowspan="12" >Si votre niveau de stress est = 1, de quel dispositif d'accompagnement auriez-vous besoin ?</td>
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienRH" <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="EntretienRH" id="EntretienRH" value="1" <?php if($rowEPERempli['EntretienRH']==1){echo "checked";} ?>>Entretien RH</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienRH" <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> id="ComEntretienRH" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEntretienRH']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="EntretienMedecienTravail" name="EntretienMedecienTravail" id="EntretienMedecienTravail" value="1" <?php if($rowEPERempli['EntretienMedecienTravail']==1){echo "checked";} ?>>Entretien avec la médecine du travail</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEntretienMedecienTravail" id="ComEntretienMedecienTravail" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEntretienMedecienTravail']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="EntretienLumanisy" name="EntretienLumanisy" id="EntretienLumanisy" value="1" <?php if($rowEPERempli['EntretienLumanisy']==1){echo "checked";} ?>>Entretien avec LUMANISY</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEntretienLumanisy" id="ComEntretienLumanisy" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEntretienLumanisy']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="EntretienSoutienPsycho" name="EntretienSoutienPsycho" id="EntretienSoutienPsycho" value="1" <?php if($rowEPERempli['EntretienSoutienPsycho']==1){echo "checked";} ?>>Soutien psychologique</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEntretienSoutienPsycho" id="ComEntretienSoutienPsycho" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEntretienSoutienPsycho']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="EntretienHSE" name="EntretienHSE" id="EntretienHSE" value="1" <?php if($rowEPERempli['EntretienHSE']==1){echo "checked";} ?>>Entretien avec service HSE</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEntretienHSE" id="ComEntretienHSE" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEntretienHSE']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="EntretienAutre" name="EntretienAutre" id="EntretienAutre" value="1" <?php if($rowEPERempli['EntretienAutre']==1){echo "checked";} ?>>Entretien Autres</td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEntretienAutre" id="ComEntretienAutre" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEntretienAutre']);?></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEEntretienAutre" id="ComEEntretienAutre" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEEntretienAutre']);?></textarea></td>
							</tr>
						<?php
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="4">Formation</td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FormationOrganisationTravail" name="FormationOrganisationTravail" id="FormationOrganisationTravail" value="1" <?php if($rowEPERempli['FormationOrganisationTravail']==1){echo "checked";} ?>>Organisation du travail, gestion du temps et des priorités</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComFormationOrganisationTravail" id="ComFormationOrganisationTravail" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComFormationOrganisationTravail']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FormationStress" name="FormationStress" id="FormationStress" value="1" <?php if($rowEPERempli['FormationStress']==1){echo "checked";} ?>>Gestion du stress</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComFormationStress" id="ComFormationStress" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComFormationStress']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FormationSophrologie" name="FormationSophrologie" id="FormationSophrologie" value="1" <?php if($rowEPERempli['FormationSophrologie']==1){echo "checked";} ?>>La sophrologie au travail</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComFormationSophrologie" id="ComFormationSophrologie" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComFormationSophrologie']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="3"><input type='checkbox' <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> class="FormationAutre" name="FormationAutre" id="FormationAutre" value="1" <?php if($rowEPERempli['FormationAutre']==1){echo "checked";} ?>>Autres</td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComFormationAutre" id="ComFormationAutre" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComFormationAutre']);?></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ComEFormationAutre" id="ComEFormationAutre" cols="45" rows="1" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEFormationAutre']);?></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="<?php if($rowEPERempli['Stress']>2){echo "display:none;";}?>">
								<td width="5%" colspan="5" class="Libelle2" align="center" style="color:red;">Nous vous rappelons également que les membres du CSE et du CSSCT se tiennent à votre disposition si nécessaire.</td>
							</tr>
						<tr>
							<td width="20%" class="Libelle2">Commentaires libres</td>
							<td width="30%" class="Libelle2" align="center" colspan="4"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="CommentaireLibreS" id="CommentaireLibreS" cols="90" rows="2" noresize="noresize"><?php echo stripslashes($rowEPERempli['CommentaireLibreS']);?></textarea></td>
							<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="CommentaireLibreE" id="CommentaireLibreE" cols="45" rows="2" noresize="noresize"><?php echo stripslashes($rowEPERempli['CommentaireLibreE']);?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php 
			if($_SESSION["Langue"]=="FR"){echo "7. EPE - Synthèse";}else{echo "7. EPE - Summary";}
			
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="Libelle2">Synthèse des points forts</td>
							<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="PointFort" id="PointFort" cols="80" rows="2" style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['PointFort']);?></textarea></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">Synthèse des axes d'amélioration</td>
							<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="PointFaible" id="PointFaible" cols="80" rows="2" style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['PointFaible']);?></textarea></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">Objectifs de progression / plan d'action correctif</td>
							<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['ModeBrouillon']==0){echo "disabled";} ?> name="ObjectifProgression" id="ObjectifProgression" cols="80" rows="2" style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['ObjectifProgression']);?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "8. EPE - Conclusion";}else{echo "8. EPE - Conclusion";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="Libelle2">COMMENTAIRES DU COLLABORATEUR</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="ComSalarie" <?php if($rowEPERempli['ModeBrouillon']==0 && ($rowEPERempli['Id_Personne']<>$_SESSION['Id_Personne'] || $rowEPERempli['Etat']<>"Signature salarié")){echo "disabled";} ?> id="ComSalarie" cols="80" rows="3" style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComSalarie']);?></textarea></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">COMMENTAIRES DE L'EVALUATEUR</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="ComEvaluateur" <?php if($rowEPERempli['ModeBrouillon']==0  && ($rowEPERempli['Id_Evaluateur']<>$_SESSION['Id_Personne'] || $rowEPERempli['Etat']<>"Signature manager")){echo "disabled";} ?> id="ComEvaluateur" cols="80" rows="3" style="font-size:16px;" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEvaluateur']);?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
	<?php 
	if($rowEPERempli['Etat']=="Signature manager"){
		if($rowEPERempli['SalarieRefuseSignature']==1){
	?>
						<tr>
							<td width="20%" class="Libelle2" align="center" colspan="2">Entretien réalisé le <?php echo AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']); ?> mais <?php echo $rowEPERempli['Nom']." ".$rowEPERempli['Prenom']; ?> refuse de signer son entretien</td>
						</tr>
						<tr><td height="4"></td></tr>
	<?php
		}
	}
	?>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="6" align="center">
			<?php 
			if($rowEPERempli['Etat']=="Brouillon"){ ?>
			<table width="100%">
			<tr>
			<td width="50%" align="right">
				<input class="Bouton" name="btnEnregistrer" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
			</td>
			<td width="50%"  align="center">
			<input class="Bouton" name="btnSupprimer" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce brouillon ?')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Supprimer le brouillon";}else{echo "Delete the draft";} ?>"/>
			</td>
			</tr>
			</table>
			<?php }
				elseif($rowEPERempli['ModeBrouillon']==0 && ($rowEPERempli['Id_Personne']==$_SESSION['Id_Personne'] || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))) && $rowEPERempli['Etat']=="Signature salarié"){?>
			<input class="Bouton" name="btnSignerS" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Signer";}else{echo "Sign";} ?>"/>
			<?php	
				}
				elseif($rowEPERempli['ModeBrouillon']==0 && ($rowEPERempli['Id_Evaluateur']==$_SESSION['Id_Personne'] || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH.",".$IdPosteResponsableRH))) && $rowEPERempli['Etat']=="Signature manager"){?>
				<input class="Bouton" name="btnSignerE" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Signer";}else{echo "Sign";} ?>"/>
			<?php	
				}
			?>
		</td>
	</tr>
	</tr>
</table>
</form>
</body>
</html>