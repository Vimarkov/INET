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
	function VerifChamps(){
		
		if(document.getElementById("modeBrouillon").value=="0"){
			//Objectifs années précédentes
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
			
			if(document.getElementById("modeBrouillon").value=="0"){
				var Confirm=false;
				Confirm=window.confirm('Attention, aucune modification ne sera possible. Etes-vous sur de vouloir valider ? ');
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

//Date du dernier EPE 
$DateDernierEPE=date('Y-01-01',strtotime(date('Y-m-d')." -1 year"));

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,IF(DateReport>'0001-01-01' ,DateReport,DateButoir) AS DateButoir,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		$rowEPE=mysqli_fetch_array($result);
		
		//Vérifier si EPE n'existe pas déjà
		$req="SELECT Id 
			FROM epe_personne 
			WHERE Suppr=0 AND Type='EPE' AND epe_personne.Id_Personne=".$rowEPE['Id']." AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']."";
		$resultRecherche=mysqli_query($bdd,$req);
		$nbRecherche=mysqli_num_rows($resultRecherche);
		
		if($nbRecherche==0){
			$DateDernierEPE=$_POST['DateDernierEPE'];
			
			$Id_Prestation=0;
			$Id_Pole=0;
			
			$req="SELECT Id_Prestation,Id_Pole 
				FROM new_competences_personne_prestation
				WHERE Id_Personne=".$rowEPE['Id']." 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
			$resultch=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($resultch);
			$Id_PrestationPole="0_0";
			if($nb>0){
				$rowMouv=mysqli_fetch_array($resultch);
				$Id_Prestation=$rowMouv['Id_Prestation'];
				$Id_Pole=$rowMouv['Id_Pole'];
			}

			$req="SELECT Id_Prestation, Id_Pole FROM epe_personne_prestation WHERE Id_Personne=".$rowEPE['Id']." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
			$ResultlaPresta=mysqli_query($bdd,$req);
			$NblaPresta=mysqli_num_rows($ResultlaPresta);
			if($NblaPresta>0){
				$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
				$Id_Prestation=$RowlaPresta['Id_Prestation'];
				$Id_Pole=$RowlaPresta['Id_Pole'];
			}

			$Id_Plateforme=0;
			$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
			$ResultPresta=mysqli_query($bdd,$req);
			$NbPrest=mysqli_num_rows($ResultPresta);
			if($NbPrest>0){
				$RowPresta=mysqli_fetch_array($ResultPresta);
				$Id_Plateforme=$RowPresta['Id_Plateforme'];
			}

			$MetierManager="";
			$req="SELECT MetierPaie
					FROM new_rh_etatcivil
					WHERE Id=".$_SESSION['Id_Personne'];
			$ResultManager=mysqli_query($bdd,$req);
			$NbManager=mysqli_num_rows($ResultManager);
			if($NbManager>0){
				$RowManager=mysqli_fetch_array($ResultManager);
				$MetierManager=$RowManager['MetierPaie'];
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
			$FormationAutre=0;
			if(isset($_POST['FormationAutre'])){if($_POST['FormationAutre']==1){$FormationAutre=1;}}
			
			$typeCadre=0;
			if($_POST['Cadre']==1){
				$typeCadre=2;
			}
			else{
				$typeCadre=1;
			}
			//Création d'un EPE
			$req="INSERT INTO epe_personne (Type,ModeBrouillon,Id_Prestation,Id_Pole,Id_Personne,TypeCadre,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,Id_Plateforme,
			ConnaissanceMetier,ComConnaissanceMetier,UtilisationDoc,ComUtilisationDoc,Productivite,ComProductivite,Organisation,ComOrganisation,CapaciteManager,ComCapaciteManager,
			RespectObjectif,ComRespectObjectif,AnglaisTech,ComAnglaisTech,CapaciteTuteur,ComCapaciteTuteur,Reporting,ComReporting,PlanAction,ComPlanAction,RespectBudget,ComRespectBudget,
			RepresentationEntreprise,ComRepresentationEntreprise,SouciSatisfaction,ComSouciSatisfaction,Ecoute,ComEcoute,TraitementInsatisfaction,ComTraitementInsatisfaction,ExplicationSolution,ComExplicationSolution,
			ComprehensionInsatisfaction,ComComprehensionInsatisfaction,ConnaissanceManagement,ComConnaissanceManagement,ConnaissanceMetierEquipe,ComConnaissanceMetierEquipe,CapaciteFixerObjectif,ComCapaciteFixerObjectif,
			Delegation,ComDelegation,AnimationEquipe,ComAnimationEquipe,RespectQSE,ComRespectQSE,ContributionNC,ComContributionNC,RespectRegles,ComRespectRegles,PortTenues,ComPortTenues,
			PortEPI,ComPortEPI,RespectOutils,ComRespectOutils,Assiduite,ComAssiduite,EspritEntreprise,ComEspritEntreprise,TravailEquipe,ComTravailEquipe,Dispo,ComDispo,Autonomie,ComAutonomie,Initiative,ComInitiative,
			Communication,ComCommunication,ComSOrganisationCharge,ComEOrganisationCharge,ComSAmplitudeJournee,ComEAmplitudeJournee,
			ComSOrganisationTravail,ComEOrganisationTravail,ComSArticulationActiviteProPerso,ComEArticulationActiviteProPerso,ComSRemuneration,
			ComERemuneration,Stress,ComSStress,ComEStress,EntretienRH,EntretienMedecienTravail,EntretienLumanisy,EntretienSoutienPsycho,EntretienHSE,EntretienAutre,FormationOrganisationTravail,FormationStress,
			FormationAutre,ComEntretienRH,ComEntretienMedecienTravail,ComEntretienLumanisy,ComEntretienSoutienPsycho,ComEntretienHSE,ComEntretienAutre,ComEEntretienAutre,
			ComFormationOrganisationTravail,ComFormationStress,ComFormationAutre,ComEFormationAutre,CommentaireLibreS,CommentaireLibreE,
			PointFort,PointFaible,ObjectifProgression,ComSalarie,ComEvaluateur) 
				VALUES 
					('EPE',".$_POST['modeBrouillon'].",".$Id_Prestation.",".$Id_Pole.",".$rowEPE['Id'].",".$typeCadre.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".addslashes($rowEPE['Metier'])."','".$rowEPE['DateAncienneteCDI']."','".date('Y-m-d')."','".$rowEPE['DateButoir']."',
					".$_SESSION['Id_Personne'].",'".addslashes($MetierManager)."',".$Id_Plateforme.",".existeSinon_2('ConnaissanceMetier').",
					'".addslashes($_POST['ComConnaissanceMetier'])."',".existeSinon_2('UtilisationDoc').",'".addslashes($_POST['ComUtilisationDoc'])."',".existeSinon_2('Productivite').",'".addslashes($_POST['ComProductivite'])."',
					".existeSinon_2('Organisation').",'".addslashes($_POST['ComOrganisation'])."',".existeSinon_2('CapaciteManager').",'".addslashes($_POST['ComCapaciteManager'])."',".existeSinon_2('RespectObjectif').",'".addslashes($_POST['ComRespectObjectif'])."',
					".existeSinon_2('AnglaisTech').",'".addslashes($_POST['ComAnglaisTech'])."',".existeSinon_2('CapaciteTuteur').",'".addslashes($_POST['ComCapaciteTuteur'])."',".existeSinon_2('Reporting').",'".addslashes($_POST['ComReporting'])."',
					".existeSinon_2('PlanAction').",'".addslashes($_POST['ComPlanAction'])."',".existeSinon_2('RespectBudget').",'".addslashes($_POST['ComRespectBudget'])."',".existeSinon_2('RepresentationEntreprise').",'".addslashes($_POST['ComRepresentationEntreprise'])."',
					".existeSinon_2('SouciSatisfaction').",'".addslashes($_POST['ComSouciSatisfaction'])."',".existeSinon_2('Ecoute').",'".addslashes($_POST['ComEcoute'])."',".existeSinon_2('TraitementInsatisfaction').",'".addslashes($_POST['ComTraitementInsatisfaction'])."',
					".existeSinon_2('ExplicationSolution').",'".addslashes($_POST['ComExplicationSolution'])."',".existeSinon_2('ComprehensionInsatisfaction').",'".addslashes($_POST['ComComprehensionInsatisfaction'])."',".existeSinon_2('ConnaissanceManagement').",'".addslashes($_POST['ComConnaissanceManagement'])."',
					".existeSinon_2('ConnaissanceMetierEquipe').",'".addslashes($_POST['ComConnaissanceMetierEquipe'])."',".existeSinon_2('CapaciteFixerObjectif').",'".addslashes($_POST['ComCapaciteFixerObjectif'])."',".existeSinon_2('Delegation').",'".addslashes($_POST['ComDelegation'])."',
					".existeSinon_2('AnimationEquipe').",'".addslashes($_POST['ComAnimationEquipe'])."',".existeSinon_2('RespectQSE').",'".addslashes($_POST['ComRespectQSE'])."',".existeSinon_2('ContributionNC').",'".addslashes($_POST['ComContributionNC'])."',
					".existeSinon_2('RespectRegles').",'".addslashes($_POST['ComRespectRegles'])."',".existeSinon_2('PortTenues').",'".addslashes($_POST['ComPortTenues'])."',".existeSinon_2('PortEPI').",'".addslashes($_POST['ComPortEPI'])."',
					".existeSinon_2('RespectOutils').",'".addslashes($_POST['ComRespectOutils'])."',".existeSinon_2('Assiduite').",'".addslashes($_POST['ComAssiduite'])."',".existeSinon_2('EspritEntreprise').",'".addslashes($_POST['ComEspritEntreprise'])."',
					".existeSinon_2('TravailEquipe').",'".addslashes($_POST['ComTravailEquipe'])."',".existeSinon_2('Dispo').",'".addslashes($_POST['ComDispo'])."',".existeSinon_2('Autonomie').",'".addslashes($_POST['ComAutonomie'])."',
					".existeSinon_2('Initiative').",'".addslashes($_POST['ComInitiative'])."',".existeSinon_2('Communication').",'".addslashes($_POST['ComCommunication'])."','".addslashes($_POST['ComSOrganisationCharge'])."','".addslashes($_POST['ComEOrganisationCharge'])."',
					'".$ComSAmplitudeJournee."','".$ComEAmplitudeJournee."','".$ComSOrganisationTravail."','".$ComEOrganisationTravail."',
					'".addslashes($_POST['ComSArticulationActiviteProPerso'])."','".addslashes($_POST['ComEArticulationActiviteProPerso'])."','".$ComSRemuneration."','".$ComERemuneration."',
					".existeSinon_2('Stress').",'".addslashes($_POST['ComSStress'])."','".addslashes($_POST['ComEStress'])."',".$EntretienRH.",".$EntretienMedecienTravail.",".$EntretienLumanisy.",".$EntretienSoutienPsycho.",".$EntretienHSE.",
					".$EntretienAutre.",".$FormationOrganisationTravail.",".$FormationStress.",".$FormationAutre.",'".addslashes($_POST['ComEntretienRH'])."','".addslashes($_POST['ComEntretienMedecienTravail'])."','".addslashes($_POST['ComEntretienLumanisy'])."',
					'".addslashes($_POST['ComEntretienSoutienPsycho'])."','".addslashes($_POST['ComEntretienHSE'])."','".addslashes($_POST['ComEntretienAutre'])."','".addslashes($_POST['ComEEntretienAutre'])."','".addslashes($_POST['ComFormationOrganisationTravail'])."',
					'".addslashes($_POST['ComFormationStress'])."','".addslashes($_POST['ComFormationAutre'])."','".addslashes($_POST['ComEFormationAutre'])."','".addslashes($_POST['CommentaireLibreS'])."',
					'".addslashes($_POST['CommentaireLibreE'])."','".addslashes($_POST['PointFort'])."','".addslashes($_POST['PointFaible'])."','".addslashes($_POST['ObjectifProgression'])."',
					'".addslashes($_POST['ComSalarie'])."','".addslashes($_POST['ComEvaluateur'])."')";
			$resultAjout=mysqli_query($bdd,$req);
			$IdCree = mysqli_insert_id($bdd);

			if($IdCree>0){
				//Objectif de l'année précédentes
				for($i=0;$i<10;$i++){
					if($_POST['ObjEvaluation'.$i]<>""){
						$req="INSERT INTO epe_personne_objectifanneeprecedente (Id_epepersonne,Evaluation,Note,Commentaire) 
							VALUES (".$IdCree.",'".addslashes($_POST['ObjEvaluation'.$i])."',".existeSinon_2('ObjNote'.$i).",'".addslashes($_POST['ObjCommentaire'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
					}
				}
				
				//Objectif de l'année à venir
				for($i=0;$i<10;$i++){
					if($_POST['OAObjectif'.$i]<>""){
						$req="INSERT INTO epe_personne_objectifannee (Id_epepersonne,Objectif,Indicateur,MoyensAssocies,Commentaire) 
							VALUES (".$IdCree.",'".addslashes($_POST['OAObjectif'.$i])."','".addslashes($_POST['OAIndicateurs'.$i])."','".addslashes($_POST['OAMoyensAssocies'.$i])."','".addslashes($_POST['OACommentaires'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
					}
				}
				
				//Bilan des formations annuelles réalisées
				$req="SELECT DateSession,Libelle,Organisme,Type
					FROM
					(
					SELECT
					form_besoin.Id AS Id_Besoin,
					0 AS Id_PersonneFormation,
					(
						SELECT
							form_session_date.DateSession
						FROM
							form_session_personne
						LEFT JOIN 
							form_session_date 
						ON 
							form_session_personne.Id_Session=form_session_date.Id_Session
						WHERE
							form_session_personne.Id_Besoin=form_besoin.Id
							AND form_session_personne.Id NOT IN 
								(
								SELECT
									Id_Session_Personne
								FROM
									form_session_personne_qualification
								WHERE
									Suppr=0	
								)
							AND form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Presence=1
							AND form_session_date.Suppr=0
						ORDER BY DateSession DESC
						LIMIT 1
					) AS DateSession,
					(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Organisme,
					(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_besoin.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
							AND Id_Formation=form_besoin.Id_Formation
							AND Suppr=0 
							LIMIT 1)
						AND Suppr=0) AS Libelle,
				'Professionnelle' AS Type
				FROM
					form_besoin,
					new_competences_prestation
				WHERE
					form_besoin.Id_Personne=".$rowEPE['Id']."
					AND form_besoin.Id_Prestation=new_competences_prestation.Id
					AND form_besoin.Suppr=0
					AND form_besoin.Valide=1
					AND form_besoin.Traite=4
					AND form_besoin.Id IN
					(
					SELECT
						Id_Besoin
					FROM
						form_session_personne
					WHERE
						form_session_personne.Id NOT IN 
							(
							SELECT
								Id_Session_Personne
							FROM
								form_session_personne_qualification
							WHERE
								Suppr=0	
							)
						AND Suppr=0
						AND form_session_personne.Validation_Inscription=1
						AND form_session_personne.Presence=1
					)
					AND (
						SELECT
							form_session_date.DateSession
						FROM
							form_session_personne
						LEFT JOIN 
							form_session_date 
						ON 
							form_session_personne.Id_Session=form_session_date.Id_Session
						WHERE
							form_session_personne.Id_Besoin=form_besoin.Id
							AND form_session_personne.Id NOT IN 
								(
								SELECT
									Id_Session_Personne
								FROM
									form_session_personne_qualification
								WHERE
									Suppr=0	
								)
							AND form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Presence=1
							AND form_session_date.Suppr=0
						ORDER BY DateSession DESC
						LIMIT 1
					)>='".$DateDernierEPE."'
					AND (
						SELECT
							form_session_date.DateSession
						FROM
							form_session_personne
						LEFT JOIN 
							form_session_date 
						ON 
							form_session_personne.Id_Session=form_session_date.Id_Session
						WHERE
							form_session_personne.Id_Besoin=form_besoin.Id
							AND form_session_personne.Id NOT IN 
								(
								SELECT
									Id_Session_Personne
								FROM
									form_session_personne_qualification
								WHERE
									Suppr=0	
								)
							AND form_session_personne.Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Presence=1
							AND form_session_date.Suppr=0
						ORDER BY DateSession DESC
						LIMIT 1
					)>='".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
					
					UNION 
					
					SELECT 
					0 AS Id_Besoin,
					new_competences_personne_formation.Id AS Id_PersonneFormation, 
					new_competences_personne_formation.Date AS DateSession,
					'' AS Organisme,
					(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
					new_competences_personne_formation.Type 
					FROM new_competences_personne_formation
					WHERE new_competences_personne_formation.Id_Personne=".$rowEPE['Id'].") AS TAB 
					WHERE DateSession>='".$DateDernierEPE."'
					AND DateSession<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
					ORDER BY Type ASC, Libelle ASC, DateSession DESC ";
				$result2=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result2);
				if($nbenreg>0){
					$i=0;
					while($row2=mysqli_fetch_array($result2)){
						$req="INSERT INTO epe_personne_bilanformation (Id_epepersonne,Type,Formation,DateDebut,DateFin,EvaluationAFroid,Commentaire) 
							VALUES (".$IdCree.",'For','".addslashes($row2['Libelle'])."','".$row2['DateSession']."','".$row2['DateSession']."',".existeSinon_2('FANoteFor'.$i).",'".addslashes($_POST['FACommentaireFor'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
						$i++;
					}
				}
				
				$Requete_Qualif="
					SELECT
						new_competences_qualification.Id,
						new_competences_qualification.Id_Categorie_Qualification,
						new_competences_qualification.Libelle AS Qualif,
						new_competences_qualification.Periodicite_Surveillance,
						new_competences_categorie_qualification.Libelle,
						new_competences_relation.Sans_Fin,
						new_competences_relation.Evaluation,
						new_competences_relation.Date_QCM,
						new_competences_relation.QCM_Surveillance,
						new_competences_relation.Date_Surveillance,
						new_competences_relation.Id AS Id_Relation,
						new_competences_relation.Visible,
						new_competences_relation.Date_Debut,
						new_competences_relation.Date_Fin,
						new_competences_relation.Resultat_QCM,
						new_competences_relation.Id_Besoin,
						new_competences_relation.Id_Session_Personne_Qualification
					FROM
						new_competences_relation,
						new_competences_qualification,
						new_competences_categorie_qualification
					WHERE
						new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
						AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
						AND new_competences_relation.Id_Personne=".$rowEPE['Id']."
						AND new_competences_relation.Type='Qualification'
						AND (new_competences_relation.Date_QCM >= '".$DateDernierEPE."' OR new_competences_relation.Date_Debut>='".$DateDernierEPE."')
						AND new_competences_relation.Date_QCM<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
						AND new_competences_relation.Suppr=0
						AND Evaluation NOT IN ('','B','Bi')
						AND new_competences_qualification.Id NOT IN (1643,1644)
						AND (
							Evaluation = 'X'
							OR (Evaluation IN ('Q','S') AND new_competences_categorie_qualification.Id_Categorie_Maitre=2 )
						)
						AND new_competences_categorie_qualification.Id_Categorie_Maitre<>1
					ORDER BY
						new_competences_categorie_qualification.Libelle ASC,
						new_competences_qualification.Libelle ASC,
						new_competences_relation.Date_Debut DESC,
						new_competences_relation.Date_QCM DESC";
				$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
				$nbenreg=mysqli_num_rows($ListeQualification);
				$i=0;
				if($nbenreg>0){
					$i=0;
					$dateDebut=$row2['Date_Debut'];
					if($dateDebut<="0001-01-01"){$dateDebut=$row2['Date_QCM'];}
					while($row2=mysqli_fetch_array($ListeQualification)){
						$req="INSERT INTO epe_personne_bilanformation (Id_epepersonne,Type,Formation,DateDebut,DateFin,EvaluationAFroid,Commentaire) 
							VALUES (".$IdCree.",'Qua','".addslashes($row2['Qualif']." (".$row2['Libelle'].")")."','".$row2['Date_Debut']."','".$row2['Date_Debut']."',".existeSinon_2('FANoteQua'.$i).",'".addslashes($_POST['FACommentaireQua'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
						$i++;
					}
				}
									
									
				for($i=0;$i<5;$i++){
					if($_POST['FAFormation'.$i]<>""){
						$req="INSERT INTO epe_personne_bilanformation (Id_epepersonne,Formation,DateDebut,DateFin,EvaluationAFroid,Commentaire) 
							VALUES (".$IdCree.",'".addslashes($_POST['FAFormation'.$i])."','".TrsfDate_($_POST['FADateDebut'.$i])."','".TrsfDate_($_POST['FADateFin'.$i])."',".existeSinon_2('FANote'.$i).",'".addslashes($_POST['FACommentaire'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
					}
				}
				
				//Besoins en formation (manager)
				for($i=0;$i<10;$i++){
					if($_POST['BFBesoin'.$i]<>""){
						$req="INSERT INTO epe_personne_besoinformation (Id_epepersonne,Formation,DateDebut,DateFin,Commentaire) 
							VALUES (".$IdCree.",'".addslashes($_POST['BFBesoin'.$i])."','".TrsfDate_($_POST['BFDateDebut'.$i])."','".TrsfDate_($_POST['BFDateFin'.$i])."','".addslashes($_POST['BFCommentaire'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
					}
				}
				
				//Besoins en formation (salarié)
				for($i=0;$i<10;$i++){
					if($_POST['SFFormation'.$i]<>""){
						$req="INSERT INTO epe_personne_souhaitformation (Id_epepersonne,Formation,Favorable,Priorite,Commentaire) 
							VALUES (".$IdCree.",'".addslashes($_POST['SFFormation'.$i])."',".existeSinon_2('SFAvis'.$i).",'".addslashes($_POST['SFOrdre'.$i])."','".addslashes($_POST['SFCommentaire'.$i])."') ";
						$resultAjout=mysqli_query($bdd,$req);
					}
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
		
	}
}

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
			
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$Id_Prestation=0;
$Id_Pole=0;

$req="SELECT Id_Prestation,Id_Pole 
	FROM new_competences_personne_prestation
	WHERE Id_Personne=".$rowEPE['Id']." 
	AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
$resultch=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultch);
$Id_PrestationPole="0_0";
if($nb>0){
	$rowMouv=mysqli_fetch_array($resultch);
	$Id_Prestation=$rowMouv['Id_Prestation'];
	$Id_Pole=$rowMouv['Id_Pole'];
}


$Presta="";
$Plateforme="";
$Id_Plateforme=0;
$req="SELECT LEFT(Libelle,7) AS Prestation,(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Presta=$RowPresta['Prestation'];
	$Plateforme=$RowPresta['Plateforme'];
	$Id_Plateforme=$RowPresta['Id_Plateforme'];
}

$Pole="";
$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
$ResultPole=mysqli_query($bdd,$req);
$NbPole=mysqli_num_rows($ResultPole);
if($NbPole>0){
	$RowPole=mysqli_fetch_array($ResultPole);
	$Pole=$RowPole['Libelle'];
}

if($Pole<>""){$Presta.=" - ".$Pole;}

$Manager="";
$MatriculeAAAManager="";
$MetierManager="";
$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, MatriculeAAA,MetierPaie AS Metier
		FROM new_rh_etatcivil
		WHERE Id=".$_GET['Id_Manager'];
$ResultManager=mysqli_query($bdd,$req);
$NbManager=mysqli_num_rows($ResultManager);
if($NbManager>0){
	$RowManager=mysqli_fetch_array($ResultManager);
	$Manager=$RowManager['Personne'];
	$MatriculeAAAManager=$RowManager['MatriculeAAA'];
	$MetierManager=$RowManager['Metier'];
}

$requete="SELECT Id,IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".($rowEPE['Annee']-1).")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".($rowEPE['Annee']-1)."),
			'A faire')
			AS Etat
			FROM epe_personne_datebutoir
			WHERE Id_Personne=".$rowEPE['Id']."
			AND TypeEntretien='EPE'
			AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir))=".($rowEPE['Annee']-1);
$result_1=mysqli_query($bdd,$requete);
$Nb_1=mysqli_num_rows($result_1);

$req="SELECT Date_Reel FROM new_competences_personne_rh_eia WHERE Type='EPE' AND Id_Personne=".$rowEPE['Id']." 
UNION
SELECT IF((SELECT COUNT(Id)
FROM epe_personne 
WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir)= YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)))>0,
(SELECT DateEntretien
FROM epe_personne 
WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir)= YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) LIMIT 1)
,'0001-01-01')
AS Date_Reel
FROM epe_personne_datebutoir
WHERE Id_Personne=".$rowEPE['Id']."
AND TypeEntretien='EPE'
ORDER BY Date_Reel DESC";
$resultEIA=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($resultEIA);
if($nbenreg>0){
	$RowEIA=mysqli_fetch_array($resultEIA);
	$DateDernierEPE=$RowEIA['Date_Reel'];
	
	//Remonter 2 mois avant
	$DateDernierEPE=date('Y-m-d',strtotime($DateDernierEPE." -2 month"));
}
?>

<form id="formulaire" class="test" action="Ajout_EPE.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Cadre" id="Cadre" value="<?php echo $_GET['Cadre']; ?>" />
	<input type="hidden" name="DateDernierEPE" id="DateDernierEPE" value="<?php echo $DateDernierEPE; ?>" />
	<?php 
		if($Nb_1>0){
			$rowEPE_1=mysqli_fetch_array($result_1);
			if($rowEPE_1['Etat']=="Réalisé"){
				echo "<tr><td class='Libelle' align='right'>EPE ".($rowEPE['Annee']-1)." :";
	?>
		<a class="Modif" href="javascript:EPE_PDF(<?php echo $rowEPE_1['Id']; ?>,<?php echo $_GET['Cadre']; ?>);">
			<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
		</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php
				echo "</td></tr>";
			}
		}
	?>
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#1a0078;">
				<tr>
					<td class="TitrePage" align="center" style="color:#ffffff;font-size: 18px;">
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
	<tr>
		<td class="Libelle2" colspan="6" align="right">Brouillon
			<select name="modeBrouillon" id="modeBrouillon">
				<option value="0">Non</option>
				<option value="1" selected>Oui</option>
			</select>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $rowEPE['MatriculeAAA']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'entretien";}else{echo "Interview date";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo date('d/m/Y'); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Name";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $rowEPE['Nom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $Plateforme; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $rowEPE['Prenom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Evaluateur";}else{echo "Evaluator";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $Manager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction/métier";}else{echo "Function / profession";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $rowEPE['Metier']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $MatriculeAAAManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo AfficheDateJJ_MM_AAAA($rowEPE['DateAncienneteCDI']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction /métier";}else{echo "Function / profession";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $MetierManager; ?></td>
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
						
						$req="SELECT epe_personne_objectifannee.Id, Objectif
						FROM epe_personne_objectifannee 
						LEFT JOIN epe_personne 
						ON epe_personne_objectifannee.Id_epepersonne=epe_personne.Id 
						WHERE epe_personne.Type='EPE' 
						AND YEAR(DateButoir)='".($_SESSION['FiltreEPE_Annee']-1)."' 
						AND epe_personne.Suppr=0 
						AND epe_personne_objectifannee.Suppr=0
						AND epe_personne.Id_Personne=".$rowEPE['Id']." ";
						
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
							<tr class="Obj<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2 && ($rowAnneePrec==null || $rowAnneePrec['Id']==0)){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if(($i>1 && $i<>9) && ($rowAnneePrec2==null ||$rowAnneePrec2['Id']==0)){ ?>
								<input class="Bouton OObj<?php echo $i;?>" type="button" name="newObj" id="newObj" onclick="AfficherTRObj(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="25%" class="Libelle2" align="center"><input type='text' size="60" name="ObjEvaluation<?php echo $i;?>" id="ObjEvaluation<?php echo $i;?>" value="<?php if($rowAnneePrec<>null){echo stripslashes($rowAnneePrec['Objectif']);}?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="-1"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="1"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="2"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="3"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="ObjNote<?php echo $i;?>" name="ObjNote<?php echo $i;?>" id="ObjNote<?php echo $i;?>" value="4"></td>
								<td width="60%" class="Libelle2" align="center"><input type='text' size="90" name="ObjCommentaire<?php echo $i;?>" id="ObjCommentaire<?php echo $i;?>" value="">
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
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4"></td>
								<td width="60%" class="Libelle2"><textarea name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"></textarea></td>
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
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4"></td>
								<td width="60%" class="Libelle2"><textarea name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"></textarea></td>
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
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4"></td>
								<td width="60%" class="Libelle2"><textarea name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"></textarea></td>
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
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4"></td>
								<td width="60%" class="Libelle2"><textarea name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"></textarea></td>
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
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="-1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="1"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="2"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="3"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" id="<?php echo $tab2[$i];?>" value="4"></td>
								<td width="60%" class="Libelle2"><textarea name="Com<?php echo $tab2[$i];?>" id="Com<?php echo $tab2[$i];?>" cols="60" rows="2"  style="font-size:16px;" noresize="noresize"></textarea></td>
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
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="OA<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if($i>1 && $i<>9){ ?>
								<input class="Bouton OOA<?php echo $i;?>" type="button" name="newOA" id="newOA" onclick="AfficherTR(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' size="60" name="OAObjectif<?php echo $i;?>" id="OAObjectif<?php echo $i;?>" value=""></td>
								<td width="20%" class="Libelle2" align="center"><input type='text' size="30" name="OAIndicateurs<?php echo $i;?>" id="OAIndicateurs<?php echo $i;?>" value=""></td>
								<td width="20%" class="Libelle2" align="center"><input type='text' size="30" name="OAMoyensAssocies<?php echo $i;?>" id="OAMoyensAssocies<?php echo $i;?>" value=""></td>
								<td width="27%" class="Libelle2" align="center"><input type='text' size="60" name="OACommentaires<?php echo $i;?>" id="OACommentaires<?php echo $i;?>" value="">
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
						
						//Liste des formations et qualifications jusqu'au dernier EPE
						$req="SELECT DateSession,Libelle,Organisme,Type
							FROM
							(
							SELECT
							form_besoin.Id AS Id_Besoin,
							0 AS Id_PersonneFormation,
							(
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							) AS DateSession,
							(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
								WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
								AND Suppr=0 LIMIT 1) AS Organisme,
							(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0) AS Libelle,
						'Professionnelle' AS Type
						FROM
							form_besoin,
							new_competences_prestation
						WHERE
							form_besoin.Id_Personne=".$rowEPE['Id']."
							AND form_besoin.Id_Prestation=new_competences_prestation.Id
							AND form_besoin.Suppr=0
							AND form_besoin.Valide=1
							AND form_besoin.Traite=4
							AND form_besoin.Id IN
							(
							SELECT
								Id_Besoin
							FROM
								form_session_personne
							WHERE
								form_session_personne.Id NOT IN 
									(
									SELECT
										Id_Session_Personne
									FROM
										form_session_personne_qualification
									WHERE
										Suppr=0	
									)
								AND Suppr=0
								AND form_session_personne.Validation_Inscription=1
								AND form_session_personne.Presence=1
							)
							AND (
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							)>='".$DateDernierEPE."'
							
							AND (
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							)<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
							
							UNION 
							
							SELECT 
							0 AS Id_Besoin,
							new_competences_personne_formation.Id AS Id_PersonneFormation, 
							new_competences_personne_formation.Date AS DateSession,
							'' AS Organisme,
							(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
							new_competences_personne_formation.Type 
							FROM new_competences_personne_formation
							WHERE new_competences_personne_formation.Id_Personne=".$rowEPE['Id'].") AS TAB 
							WHERE DateSession>='".$DateDernierEPE."'
							AND DateSession<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
							ORDER BY Type ASC, Libelle ASC, DateSession DESC ";
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
								<td width="30%" class="Libelle2" align="center"><input type='text' size="60" disabled name="FAFormationFor<?php echo $i;?>" id="FAFormationFor<?php echo $i;?>" value="<?php echo $row2['Libelle'];?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="10" disabled name="FADateDebutFor<?php echo $i;?>" id="FADateDebutFor<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateSession']);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="10" disabled name="FADateFinFor<?php echo $i;?>" id="FADateFinFor<?php echo $i;?>" value="<?php echo AfficheDateFR($row2['DateSession']);?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="1"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="2"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="3"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteFor<?php echo $i;?>" name="FANoteFor<?php echo $i;?>" id="FANoteFor<?php echo $i;?>" value="4"></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' size="80" name="FACommentaireFor<?php echo $i;?>"  id="FACommentaireFor<?php echo $i;?>" value="">
								</td>
							</tr>
						<?php	
								$i++;
							}
						}
						echo "<tr><td><input type='hidden' name='FAFormationFor' id='FAFormationFor' value='".$i."'></td></tr>";
						$Requete_Qualif="
							SELECT
								new_competences_qualification.Id,
								new_competences_qualification.Id_Categorie_Qualification,
								new_competences_qualification.Libelle AS Qualif,
								new_competences_qualification.Periodicite_Surveillance,
								new_competences_categorie_qualification.Libelle,
								new_competences_relation.Sans_Fin,
								new_competences_relation.Evaluation,
								new_competences_relation.Date_QCM,
								new_competences_relation.QCM_Surveillance,
								new_competences_relation.Date_Surveillance,
								new_competences_relation.Id AS Id_Relation,
								new_competences_relation.Visible,
								new_competences_relation.Date_Debut,
								new_competences_relation.Date_Fin,
								new_competences_relation.Resultat_QCM,
								new_competences_relation.Id_Besoin,
								new_competences_relation.Id_Session_Personne_Qualification
							FROM
								new_competences_relation,
								new_competences_qualification,
								new_competences_categorie_qualification
							WHERE
								new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
								AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
								AND new_competences_relation.Id_Personne=".$rowEPE['Id']."
								AND new_competences_relation.Type='Qualification'
								AND (new_competences_relation.Date_QCM >= '".$DateDernierEPE."' OR new_competences_relation.Date_Debut>='".$DateDernierEPE."')
								AND new_competences_relation.Date_QCM<'".date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))."'
								AND new_competences_relation.Suppr=0
								AND Evaluation NOT IN ('','B','Bi')
								AND new_competences_qualification.Id NOT IN (1643,1644)
								AND (
									Evaluation = 'X'
									OR (Evaluation IN ('Q','S') AND new_competences_categorie_qualification.Id_Categorie_Maitre=2 )
								)
								AND new_competences_categorie_qualification.Id_Categorie_Maitre<>1
							ORDER BY
								new_competences_categorie_qualification.Libelle ASC,
								new_competences_qualification.Libelle ASC,
								new_competences_relation.Date_Debut DESC,
								new_competences_relation.Date_QCM DESC";
						$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
						$nbenreg=mysqli_num_rows($ListeQualification);
						$i=0;
						if($nbenreg>0){
							
							while($row2=mysqli_fetch_array($ListeQualification)){
								if($couleur=="#ffffff"){$couleur="#d4d2d4";}
								else{$couleur="#ffffff";}
								
								$dateDebut=$row2['Date_Debut'];
								if($dateDebut<="0001-01-01"){$dateDebut=$row2['Date_QCM'];}
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' size="60" disabled name="FAFormationQua<?php echo $i;?>" id="FAFormationQua<?php echo $i;?>" value="<?php echo $row2['Qualif']." (".$row2['Libelle'].")";?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="13" disabled name="FADateDebutQua<?php echo $i;?>" id="FADateDebutQua<?php echo $i;?>" value="<?php echo AfficheDateFR($dateDebut);?>"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="13" disabled name="FADateFinQua<?php echo $i;?>" id="FADateFinQua<?php echo $i;?>" value="<?php echo AfficheDateFR($dateDebut);?>"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="1"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="2"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="3"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANoteQua<?php echo $i;?>" name="FANoteQua<?php echo $i;?>" id="FANoteQua<?php echo $i;?>" value="4"></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' size="80" name="FACommentaireQua<?php echo $i;?>"  id="FACommentaireQua<?php echo $i;?>" value="">
								</td>
							</tr>
						<?php
								$i++;
							}
						}
						echo "<tr><td><input type='hidden' name='FAFormationQua' id='FAFormationQua' value='".$i."'></td></tr>";
						
						for($i=0;$i<5;$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="FA<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>1){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if($i>0 && $i<>4){ ?>
								<input class="Bouton FFA<?php echo $i;?>" type="button" name="newFA" id="newFA" onclick="AfficherTRFA(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' size="60" name="FAFormation<?php echo $i;?>" id="FAFormation<?php echo $i;?>" value=""></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="10" name="FADateDebut<?php echo $i;?>" id="FADateDebut<?php echo $i;?>" value=""></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="10" name="FADateFin<?php echo $i;?>" id="FADateFin<?php echo $i;?>" value=""></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="1"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="2"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="3"></td>
								<td width="5%" class="Libelle2" align="center"><input type='radio' class="FANote<?php echo $i;?>" name="FANote<?php echo $i;?>" id="FANote<?php echo $i;?>" value="4"></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' size="80" name="FACommentaire<?php echo $i;?>"  id="FACommentaire<?php echo $i;?>" value="">
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
							<td height="5" colspan="4" align="center" class="Libelle2">RECHERCHE BESOIN</td>
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
						<tr bgcolor="#d2d4d4">
							<td height="5" colspan="4"></td>
						</tr>
						<?php 
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="BF<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if($i>1 && $i<>9){ ?>
								<input class="Bouton BBF<?php echo $i;?>" type="button" name="newBF" id="newBF" onclick="AfficherTR2(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' size="60" name="BFBesoin<?php echo $i;?>" id="BFBesoin<?php echo $i;?>" value="" onKeyUp="VerifQualif(<?php echo $i;?>)"></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="30" name="BFDateDebut<?php echo $i;?>" id="BFDateDebut<?php echo $i;?>" value=""></td>
								<td width="10%" class="Libelle2" align="center"><input type='date' size="30" name="BFDateFin<?php echo $i;?>" id="BFDateFin<?php echo $i;?>" value=""></td>
								<td width="47%" class="Libelle2" align="center"><input type='text' size="90" name="BFCommentaire<?php echo $i;?>"  id="BFCommentaire<?php echo $i;?>" value="">
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
							<td width="20%" class="Libelle2" align="center" style="color:#ffffff;" >Ordre de priorité :<br>1 : Prioritaire pour l'activité,<br>2 : nécessaire,<br>3 : non urgent </td>
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
						$couleur="#d4d2d4";
						for($i=0;$i<10;$i++){
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr class="SF<?php echo $i;?>" bgcolor="<?php echo $couleur;?>" <?php if($i>2){echo "style='display:none;'";} ?>>
								<td width="3%" class="Libelle2" align="center" style="color:#ffffff;" >
								<?php if($i>1 && $i<>9){ ?>
								<input class="Bouton SSF<?php echo $i;?>" type="button" name="newSF" id="newSF" onclick="AfficherTR3(<?php echo ($i+1)?>)" value="+" />
								<?php } ?>
								</td>
								<td width="30%" class="Libelle2" align="center"><input type='text' size="60" name="SFFormation<?php echo $i;?>" id="SFFormation<?php echo $i;?>" value=""  onKeyUp="VerifFormation(<?php echo $i;?>)"></td>
								<td width="20%" class="Libelle2"><input type='radio' class="SFAvis<?php echo $i;?>" name="SFAvis<?php echo $i;?>" value="1">Favorable<br><input type='radio' class="SFAvis<?php echo $i;?>" name="SFAvis<?php echo $i;?>" value="0">Défavorable</td>
								<td width="20%" class="Libelle2" align="center">
								<select name="SFOrdre<?php echo $i;?>" id="SFOrdre<?php echo $i;?>">
									<option value="0"></option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select>
								</td>
								<td width="47%" class="Libelle2" align="center"><input type='text' size="90" id="SFCommentaire<?php echo $i;?>" name="SFCommentaire<?php echo $i;?>" value="">
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
								<td width="30%" class="Libelle2" align="center"><textarea name="ComS<?php echo $tab2[$i];?>" id="ComS<?php echo $tab2[$i];?>" cols="60" rows="2" noresize="noresize"></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComE<?php echo $tab2[$i];?>" id="ComE<?php echo $tab2[$i];?>" cols="60" rows="2" noresize="noresize"></textarea></td>
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
							<td class="Libelle2" align="center">1= Je ne me sens pas bien ; 2 = Je me sens bien ; 3 = Je me sens très bien </td>
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
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" value="1" onclick="AfficherRPS()"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" value="2" onclick="AfficherRPS()"></td>
								<td width="5%" align="center"><input type='radio' class="<?php echo $tab2[$i];?>" name="<?php echo $tab2[$i];?>" value="3" onclick="AfficherRPS()"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComS<?php echo $tab2[$i];?>" id="ComS<?php echo $tab2[$i];?>" cols="45" rows="2" noresize="noresize"></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComE<?php echo $tab2[$i];?>" id="ComE<?php echo $tab2[$i];?>" cols="45" rows="2" noresize="noresize"></textarea></td>
							</tr>
						<?php
						}
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>	
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="20%" class="Libelle2" rowspan="11" >Si votre niveau de stress = 1, de quel dispositif d'accompagnement auriez-vous besoin ?</td>
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienRH" name="EntretienRH" id="EntretienRH" value="1">Entretien RH</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienRH" id="ComEntretienRH" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienMedecienTravail" name="EntretienMedecienTravail" id="EntretienMedecienTravail" value="1">Entretien avec la médecine du travail</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienMedecienTravail" id="ComEntretienMedecienTravail" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienLumanisy" name="EntretienLumanisy" id="EntretienLumanisy" value="1">Entretien avec le service social du travail</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienLumanisy" id="ComEntretienLumanisy" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienSoutienPsycho" name="EntretienSoutienPsycho" id="EntretienSoutienPsycho" value="1">Soutien psychologique</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienSoutienPsycho" id="ComEntretienSoutienPsycho" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienHSE" name="EntretienHSE" id="EntretienHSE" value="1">Entretien avec service HSE</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienHSE" id="ComEntretienHSE" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="EntretienAutre" name="EntretienAutre" id="EntretienAutre" value="1">Entretien Autres</td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEntretienAutre" id="ComEntretienAutre" cols="45" rows="1" noresize="noresize"></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEEntretienAutre" id="ComEntretienAutre" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
						<?php
							if($couleur=="#ffffff"){$couleur="#d4d2d4";}
							else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="4">Formation</td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="FormationOrganisationTravail" name="FormationOrganisationTravail" id="FormationOrganisationTravail" value="1">Organisation du travail, gestion du temps et des priorités</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComFormationOrganisationTravail" id="ComFormationOrganisationTravail" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="FormationStress" name="FormationStress" id="FormationStress" value="1">Gestion du stress</td>
								<td width="30%" class="Libelle2" align="center"></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComFormationStress" id="ComFormationStress" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="3"><input type='checkbox' class="FormationAutre" name="FormationAutre" id="FormationAutre" value="1">Autres</td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComFormationAutre" id="ComFormationAutre" cols="45" rows="1" noresize="noresize"></textarea></td>
								<td width="30%" class="Libelle2" align="center"><textarea name="ComEFormationAutre" id="ComFormationAutre" cols="45" rows="1" noresize="noresize"></textarea></td>
							</tr>
							<tr bgcolor="<?php echo $couleur;?>" class="RPS" style="display:none;">
								<td width="5%" colspan="5" class="Libelle2" align="center" style="color:red;">Nous vous rappelons également que les membres du CSE et du CSSCT se tiennent à votre disposition si nécessaire.</td>
							</tr>
						<tr>
							<td width="20%" class="Libelle2">Commentaires libres</td>
							<td width="30%" class="Libelle2" align="center" colspan="4"><textarea name="CommentaireLibreS" id="CommentaireLibreS" cols="90" rows="2" noresize="noresize"></textarea></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="CommentaireLibreE" id="CommentaireLibreE" cols="45" rows="2" noresize="noresize"></textarea></td>
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
							<td width="30%" class="Libelle2" align="center"><textarea name="PointFort" id="PointFort" cols="80" rows="2" style="font-size:16px;" noresize="noresize"></textarea></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">Synthèse des axes d'amélioration</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="PointFaible" id="PointFaible" cols="80" rows="2" style="font-size:16px;" noresize="noresize"></textarea></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">Objectifs de progression / plan d'action correctif</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="ObjectifProgression" id="ObjectifProgression" cols="80" rows="2" style="font-size:16px;" noresize="noresize"></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			<?php if($_SESSION["Langue"]=="FR"){echo "8. EPE - Conclusion";}else{echo "8. EPE - Conclusion";} ?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="Libelle2">COMMENTAIRES DU COLLABORATEUR</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="ComSalarie" id="ComSalarie" cols="80" rows="3" style="font-size:16px;" noresize="noresize"></textarea></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">COMMENTAIRES DE L'EVALUATEUR</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="ComEvaluateur" id="ComEvaluateur" cols="80" rows="3" style="font-size:16px;" noresize="noresize"></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
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
			<?php if($_GET['Id_Manager']==$_SESSION['Id_Personne'] || $_SESSION['FiltreEPE_AffichageBackup']<>"" || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){ ?>
			<input class="Bouton" name="btnEnregistrer" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
			<?php } ?>
		</td>
	</tr>
	</tr>
</table>
</form>
</body>
</html>