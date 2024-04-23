<!DOCTYPE html>
<?php
	
	session_start();
	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
	|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"){
		$HTTPServeur="http://".$_SERVER['SERVER_NAME']."/v2/";
	}
	elseif($_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$HTTPServeur="http://".$_SERVER['SERVER_NAME'].":443/v2/";
	}
	else{
		$HTTPServeur="https://".$_SERVER['SERVER_NAME']."/v2/";
	}
?>
<html lang="en">
<head>
	<title>Extranet | Daher</title>
	<link rel="icon" type="image/x-icon" href="<?php echo $HTTPServeur;?>Images/Logos/Logo DaherMonogramme_Posi.png">
	<meta http-equiv="Pragma" CONTENT="no-cache"><meta name="robots" content="noindex">
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate" />
	<meta http-equiv="Cache" content="no store" />
	<meta http-equiv="Expires" CONTENT="0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/Organigramme.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/jquery.jOrgChart.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/custom.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/prettify.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>Outils/JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/Perfos.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/Menu2.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/TDB.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/bootstrap.minV2.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/Switches.css" >
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/PlanningPersonnel.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/Planning.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/ChartMORIS.css">
	
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/Feuille.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTPServeur;?>CSS/New_Menu2.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" href="<?php echo $HTTPServeur;?>CSS/styleMenuHamburger.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" href="<?php echo $HTTPServeur;?>CSS/RECORDTuto.css?t=<?php echo time(); ?>">
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/jquery.min351.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/bootstrap.minV2.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/mask.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/modernizr.js"></script>
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/prettify.js"></script>
    <script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/jquery.jOrgChart.js"></script>
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/Formation/Fonctions.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/Formation/Besoin.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/Formation/QCM.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/Formation/Planning.js?t=<?php echo time(); ?>"></script>	
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/MORIS2/MORIS13.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/Fonctions_Outils.js"></script>
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/jquery.stickytableheaders.min.js"></script>	
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>amcharts/core.js"></script>	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>amcharts/charts.js"></script>	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>amcharts/themes/animated.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>amcharts/themes/dataviz.js"></script>
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/scriptMenuHamburger.js?t=<?php echo time(); ?>"></script>
	
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/jquery-1.12.4.min.js"></script>
	<!--<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/JS/jquery.flurry.js"></script>-->
</head>
<style>
	.blink_me {
		color:red;
		font-weight:bold;
		-webkit-animation-name: blinker;
		-webkit-animation-duration: 1s;
		-webkit-animation-timing-function: linear;
		-webkit-animation-iteration-count: infinite;

		-moz-animation-name: blinker;
		-moz-animation-duration: 1s;
		-moz-animation-timing-function: linear;
		-moz-animation-iteration-count: infinite;
		
		animation-name: blinker;
		animation-duration: 1s;
		animation-timing-function: linear;
		animation-iteration-count: infinite;
	}
	@-moz-keyframes blinker {  
		0% { opacity: 1.0; }
		50% { opacity: 0.0; }
		100% { opacity: 1.0; }
	}

	@-webkit-keyframes blinker {  
		0% { opacity: 1.0; }
		50% { opacity: 0.0; }
		100% { opacity: 1.0; }
	}

	@keyframes blinker {  
		0% { opacity: 1.0; }
		50% { opacity: 0.0; }
		100% { opacity: 1.0; }
	}
</style>
<body>

<script>
	function OuvreFenetreDroits(chemin){window.open(chemin+"/Outils/GestionDroits.php","PageUtilisateurs","status=no,menubar=no,scrollbars=yes,width=850,height=600");}
	function OuvreFenetreUtilisateur(chemin,Id){window.open(chemin+"/Outils/Utilisateur_Change_Profil.php?Id="+Id,"ChangeProfil","status=no,menubar=no,width=850,height=300");}
	function OuvreFenetreLeProfil(chemin,Id){window.open(chemin+"/Outils/Competences/Profil.php?Mode=Lecture&Id_Personne="+Id,"Profil","status=no,menubar=no,width=1100,height=800");}
	function OuvreFenetreCompetences(chemin,Id){
		window.open(chemin+"/Outils/Competences/Individual_Competency_List.php?Id_Personne="+Id,"CompetencyList","status=no,menubar=no,scrollbars=yes,rezisable=yes,width=1010,height=600");
	}
	function OuvreFenetreCompetences2(chemin,Id){
		window.open(chemin+"/Outils/Competences/Individual_Competency_List.php?Id_Personne="+Id,"CompetencyList","status=no,menubar=no,scrollbars=yes,rezisable=yes,width=1010,height=600");
	}
	function Deconnexion()
	{
		location="index.php";
		window.close();
	}
	function ChangeLangue(Langue,Page)
	{
		$.ajax({
			url : 'ajax_Langue.php',
			type : 'GET',
			data : 'Langue='+Langue,
			async: false,
		});
		location=Page+'?langue='+Langue;
	}
	function Rechercher(Page)
	{
		if(document.getElementById('mots').value!=''){
			location=Page+'?mots='+document.getElementById('mots').value;
		}
	}
	$(document).ready(function(){
	  $("#btnRecherche2").click(function(){
		if(document.getElementById('laRecherche').style.display==''){
			$("#laRecherche").fadeOut("slow");
		}
		else{
			$("#laRecherche").fadeIn("slow");
		}
	  });
	});
</script>
	
<?php
if(isset($_GET['langue'])){$_SESSION['Langue'] = $_GET['langue'];}
	
require_once("Outils/Connexioni.php");
require_once("Outils/Formation/Globales_Fonctions.php");
require_once("Outils/PlanningV2/Fonctions_Planning.php");
require_once("Outils/Formation/Graphiques_Fonctions.php");
require_once("Outils/Fonctions.php");
require_once("Outils/Formation/QCM_Fonctions.php");
require_once("Outils/Tools/Fonctions.php");

include 'Excel/PHPExcel.php';
include 'Excel/PHPExcel/Writer/Excel2007.php';
Ecrire_Code_JS_Init_Date();

ReaffecterDemandeConge();


global $num;
global $HTTPServeur;

$num=0;
$numHamburger=0;

function TitrePereMenu($Libelle)
{
	global $num;
	echo "<li
		onclick=\"
			var lesElements = document.getElementsByClassName('meta');
			for (i=0; i<lesElements.length; i++){
				if(lesElements[i].id!='meta".$num."'){
					lesElements[i].style.display='none';
				}
			}
			var lesElements = document.getElementsByClassName('lesA');
			for (i=0; i<lesElements.length; i++){
				if(lesElements[i].id!='a_".$num."'){
					lesElements[i].style.background='';
				}
			}
			if(document.getElementById('meta".$num."').style.display=='block'){
				document.getElementById('meta".$num."').style.display='none';
				document.getElementById('a_".$num."').style.background='';
			}else{
				document.getElementById('meta".$num."').style.display='block';
				document.getElementById('a_".$num."').style.background='#00325f';
			}

			\"><a class='lesA' id='a_".$num."' href='#'>".$Libelle."\n</a><div class='meta' id='meta".$num."'>\n";
	$num++;
}

//--------NOUVEAU MENU---------//

//--------BANDEAU--------//
function TitrePereMenuV2($Libelle,$LibelleEN,$langue)
{
	global $num;
	$valeur= "<li
		onclick=\"
			var lesElements = document.getElementsByClassName('mega');
			for (i=0; i<lesElements.length; i++){
				if(lesElements[i].id!='mega".$num."'){
					lesElements[i].style.display='none';
				}
			}
			var lesElements = document.getElementsByClassName('lesA');
			for (i=0; i<lesElements.length; i++){
				if(lesElements[i].id!='a_".$num."'){
					lesElements[i].style.background='';
				}
			}
			if(document.getElementById('mega".$num."').style.display=='block'){
				document.getElementById('mega".$num."').style.display='none';
				document.getElementById('a_".$num."').style.background='';
			}else{
				document.getElementById('mega".$num."').style.display='block';
			}

			\"><a class='lesA' id='a_".$num."' href='#'>";
		if($langue=="FR"){
			$valeur.=$Libelle;
		}
		else{
			$valeur.=$LibelleEN;
		}
		$valeur.="\n</a><div class='mega' id='mega".$num."'>\n";
	$num++;
	return $valeur;
}

function TitrePereMenuCliquableV2($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	global $num;
	$valeur= "<li><a class='lesA' id='a_".$num."' href='".$HTTPServeur.$Lien."'>";
		if($langue=="FR"){
			$valeur.=$Libelle;
		}
		else{
			$valeur.=$LibelleEN;
		}
		$valeur.="\n</a><div class='mega' id='mega".$num."'>\n";
	$num++;
	return $valeur;
}

function TitreFilsSlV2($Libelle,$LibelleEN,$langue)
{
	$valeur= "<li><a href='#' style='cursor: default;color:#E1E1D7;' onmouseover='this.style.color=\"white\"'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</a>\n";
	return $valeur;
}

function TitreFilsSlCliquableHorsPageV2($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	$valeur= '<ul>';
	$valeur.= "<li><a href='".$HTTPServeur.$Lien."' target='_blank'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</a></li>\n";
	$valeur.= '</ul>';
	
	return $valeur;	
}

function TitreFilsSlCliquableHorsPageHorsSiteV2($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	$valeur= '<ul>';
	$valeur.= "<li><a href='".$Lien."' target='_blank'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</a></li>\n";
	$valeur.= '</ul>';
	
	return $valeur;	
}

function TitreFilsV2($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	$valeur= "<li><a href='".$HTTPServeur.$Lien."'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</a></li>\n";
	return $valeur;
}

//--------HAMBURGER--------//
function TitrePereHamburger($Libelle,$LibelleEN,$langue)
{
	global $numHamburger;
	
	$valeur="<li id='nav".$numHamburger."' class='toggle accordion-toggle'> 
			 <span class='icon-plus'></span>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</li>";

	$numHamburger++;
	return $valeur;
}

function TitrePereCliquableHamburger($Libelle,$LibelleEN,$langue,$Lien)
{
	global $numHamburger;
	global $HTTPServeur;
	
	$valeur="<li id='nav".$numHamburger."' class='toggle accordion-toggle'> 
			 <a href='".$HTTPServeur.$Lien."'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</a></li>";

	$numHamburger++;
	return $valeur;
}

function TitreFilsHamburgerS1($Libelle,$LibelleEN,$langue)
{
	$valeur= "<li class='sousTitre' style='border-bottom:1px dotted ##B2AE9F;'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}			
	$valeur.= "</li>";
	return $valeur;
}

function TitreFilsHamburger($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	
	$valeur= "<li style='border-bottom:1px dotted ##B2AE9F;'>
				<a class='head' style='color:##B2AE9F;' href='".$HTTPServeur.$Lien."'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}			
	$valeur.= "</a>
			</li>";
	return $valeur;
}

function TitreFilsHorsPageHamburger($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	
	$valeur= "<li style='border-bottom:1px dotted #B2AE9F;'>
				<a class='head' style='color:#B2AE9F;' href='".$HTTPServeur.$Lien."' target='_blank'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}			
	$valeur.= "</a>
			</li>";
	return $valeur;
}

function TitreFilsHorsPageHorsSiteHamburger($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	
	$valeur= "<li style='border-bottom:1px dotted #B2AE9F;'>
				<a class='head' style='color:#B2AE9F;' href='".$Lien."' target='_blank'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}			
	$valeur.= "</a>
			</li>";
	return $valeur;
}

function TitrePereMenuCliquableV2HorsSiteV2($Libelle,$LibelleEN,$langue,$Lien)
{
	global $HTTPServeur;
	global $num;
	$valeur= "<li><a href='".$Lien."' target='_blank'>";
		if($langue=="FR"){
			$valeur.=$Libelle;
		}
		else{
			$valeur.=$LibelleEN;
		}
		$valeur.="\n</a><div class='mega' id='mega".$num."'>\n";
	$num++;
	return $valeur;
}

function TitrePereCliquableHamburgerHorsSiteV2($Libelle,$LibelleEN,$langue,$Lien)
{
	global $numHamburger;
	global $HTTPServeur;
	
	$valeur="<li id='nav".$numHamburger."' class='toggle accordion-toggle'> 
			 <a href='".$Lien."' target='_blank'>";
	if($langue=="FR"){
		$valeur.=$Libelle;
	}
	else{
		$valeur.=$LibelleEN;
	}
	$valeur.="</a></li>";

	$numHamburger++;
	return $valeur;
}


//------FIN NOUVEAU MENU----------------//
	
//Réinitialiser à la page d'accueil si pas de login enregistré (plus de connexion)
if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"){
	$chemin="http://".$_SERVER['SERVER_NAME']."/v2";
}
elseif($_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
	$chemin="http://".$_SERVER['SERVER_NAME'].":443/v2";
}
else{$chemin="https://extranet.aaa-aero.com/v2";}

if(!isset($_SESSION['Log'])){echo "<body onload='window.top.location.href=\"".$chemin."/index.php\";'>";}
elseif($_SESSION['Log']==""){echo "<body onload='window.top.location.href=\"".$chemin."/index.php\";'>";}

if(!isset($_SESSION['Langue'])){$_SESSION['Langue']="FR";}

//Affichage des drapeaux en fonction de la langue choisie
if($_SESSION['Langue'] == "EN"){$LangueInverse = "FR";}
else{$LangueInverse = "EN";}

$req = "SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme = 1 AND Id_Personne =".$_SESSION['Id_Personne']."";
$result=mysqli_query($bdd,$req);
$nbResult=mysqli_num_rows($result);

//Prestations de toutes les plateformes
$dateDuJour = date("Y-m-d");
$reqPresta = "SELECT DISTINCT(new_competences_personne_prestation.Id_Prestation) 
	FROM new_competences_personne_prestation LEFT JOIN new_competences_prestation ON new_competences_personne_prestation.Id_Prestation = new_competences_prestation.Id 
	WHERE new_competences_personne_prestation.Id_Personne =".$IdPersonneConnectee." 
	AND new_competences_personne_prestation.Date_Debut <='".$dateDuJour ."' 
	AND new_competences_personne_prestation.Date_Fin >='".$dateDuJour ."'";

$reqPrestaLimite = "SELECT DISTINCT(new_competences_personne_prestation.Id_Prestation) 
		FROM new_competences_personne_prestation LEFT JOIN new_competences_prestation 
		ON new_competences_personne_prestation.Id_Prestation = new_competences_prestation.Id 
		WHERE new_competences_personne_prestation.Id_Personne =".$IdPersonneConnectee." 
		AND new_competences_personne_prestation.Date_Debut <='".$dateDuJour ."' 
		AND new_competences_personne_prestation.Date_Fin >='".$dateDuJour ."'
		AND Id_Plateforme IN (19) ";

$reqPresta2 = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation 
				FROM rh_personne_mouvement 
				WHERE rh_personne_mouvement.Id_Personne =".$IdPersonneConnectee." 
				AND Suppr=0
				AND rh_personne_mouvement.DateDebut <='".$dateDuJour ."' AND (rh_personne_mouvement.DateFin >='".$dateDuJour ."' OR rh_personne_mouvement.DateFin <='0001-01-01') 
				AND (((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,23,24,27,32,9,10,13,17,19,28)) OR Id_Prestation IN (1205,1206,1483,1478))
				";
$reqPresta3 = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation 
				FROM rh_personne_mouvement 
				WHERE rh_personne_mouvement.Id_Personne =".$IdPersonneConnectee." 
				AND Suppr=0
				AND rh_personne_mouvement.DateDebut <='".$dateDuJour ."' AND (rh_personne_mouvement.DateFin >='".$dateDuJour ."' OR rh_personne_mouvement.DateFin <='0001-01-01') 
				AND (((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (23,24,27,32)) OR Id_Prestation IN (1205,1206,1483,1478))
				";

//Vérifier si la personne est interimaire dans Optea
$modeInterim=false;
if(estInterimPourMenu(date('Y-m-d'),$IdPersonneConnectee)){
	$modeInterim=true;
}

$modeSousTraitant=false;
if(estSousTraitantPourMenu($IdPersonneConnectee)){
	$modeSousTraitant=true;
}

$menuHamburger="";
$menuBandeau="";

//##################
//NEWS//
//##################
if($modeInterim==false && $modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuCliquableV2HorsSiteV2("Workday","Workday",$_SESSION['Langue'],"https://wd3.myworkday.com/daher/d/home.htmld");
	$menuHamburger.=TitrePereCliquableHamburgerHorsSiteV2("Workday","Workday",$_SESSION['Langue'],"https://wd3.myworkday.com/daher/d/home.htmld");
	
	$menuBandeau.= '</div>';
	
	$menuBandeau.=TitrePereMenuCliquableV2HorsSiteV2("Intranet DAHER (MyDaher)","Intranet DAHER (MyDaher)",$_SESSION['Langue'],"https://daher.sharepoint.com/sites/mydaher/fr");
	$menuHamburger.=TitrePereCliquableHamburgerHorsSiteV2("Intranet DAHER (MyDaher)","Intranet DAHER (MyDaher)",$_SESSION['Langue'],"https://daher.sharepoint.com/sites/mydaher/fr");
	
	$menuBandeau.= '</div>';
}

//##################
//QUALITE//
//##################
$TabMetier=Get_Metier($IdPersonneConnectee);	//Pour vérifier si une personne est contrôleur ou assistant qualité ou RH

//Vérifier si une personne à un S sur une qualif à jour
$req = "SELECT Id 
	FROM new_competences_relation 
	WHERE Type = 'Qualification' 
	AND Evaluation='S'
	AND Id_Personne =".$_SESSION['Id_Personne']."
	AND Date_Debut<='".date('Y-m-d')."' 
	AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') ";
$resultQualifS=mysqli_query($bdd,$req);
$nbResultQualifS=mysqli_num_rows($resultQualifS);

if($modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuV2("Système Qualité","Quality System",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Système Qualité","Quality System",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	if($modeInterim==false || $TabMetier[0]==85 || $TabMetier[0]==38 || $TabMetier[0]==158 || $TabMetier[0]==173 || $nbResultQualifS>0 
	|| DroitsPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteReferentQualiteProcedesSpeciaux,$IdPosteFormateur))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)))
	{
		$menuBandeau.=TitreFilsSlCliquableHorsPageV2("SMQ (Système Management Qualité)","QMS (Quality Management System)",$_SESSION['Langue'],"Qualite/SMQ_Files/Accueil.php");
		$menuHamburger.=TitreFilsHorsPageHamburger("SMQ (Système Management Qualité)","QMS (Quality Management System)",$_SESSION['Langue'],"Qualite/SMQ_Files/Accueil.php");
	}
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Système Qualité","Quality System",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Système Qualité","Quality System",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	$menuBandeau.=TitreFilsV2("Certificats","Certificates",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=Certificats");
	$menuHamburger.=TitreFilsHamburger("Certificats","Certificates",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=Certificats");

	if(DroitsPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteReferentQualiteProcedesSpeciaux,$IdPosteFormateur,$IdPosteResponsablePlateforme,$IdPosteResponsableRH))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
	)
	{
		$menuBandeau.=TitreFilsV2("ARF OSW","ARF OSW",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=ARF-OSW");
		$menuHamburger.=TitreFilsHamburger("ARF OSW","ARF OSW",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=ARF-OSW");

		$menuBandeau.=TitreFilsV2("ARF OLW","ARF OLW",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=ARF-OLW");
		$menuHamburger.=TitreFilsHamburger("ARF OLW","ARF OLW",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=ARF-OLW");

		$menuBandeau.=TitreFilsV2("ARF SERIE","ARF SERIE",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=ARF-SERIE");
		$menuHamburger.=TitreFilsHamburger("ARF SERIE","ARF SERIE",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=ARF-SERIE");

		$menuBandeau.=TitreFilsV2("CNF","CNF",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=CNF");
		$menuHamburger.=TitreFilsHamburger("CNF","CNF",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=CNF");

		$menuBandeau.=TitreFilsV2("Docs A1130","Docs A1130",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=Docs_A1130");
		$menuHamburger.=TitreFilsHamburger("Docs A1130","Docs A1130",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=Docs_A1130");

		$menuBandeau.=TitreFilsV2("Docs multi-plateformes","Multi-platforms Docs",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=multiplateforme");
		$menuHamburger.=TitreFilsHamburger("Docs multi-plateformes","Multi-platforms Docs",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=multiplateforme");

		$menuBandeau.=TitreFilsV2("Matrices AAA","AAA Matrix",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=MatricesAAA");
		$menuHamburger.=TitreFilsHamburger("Matrices AAA","AAA Matrix",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=MatricesAAA");

		$menuBandeau.=TitreFilsV2("Normes","Standards",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=Normes");
		$menuHamburger.=TitreFilsHamburger("Normes","Standards",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=Normes");
	}
	$menuHamburger.= "</ul>";
	$menuBandeau.= '</ul>';

	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Retour d'expérience","Lessons learnt",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Retour d'expérience","Lessons learnt",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	
	$menuBandeau.=TitreFilsV2("Liste des flash qualité","Quality Flash list",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=REX&Dossier2=ListeFQ");
	$menuHamburger.=TitreFilsHamburger("Liste des flash qualité","Quality Flash list",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=REX&Dossier2=ListeFQ");
	
	if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur))
	|| DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteReferentQualiteProcedesSpeciaux))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
	)
	{
		$menuBandeau.=TitreFilsV2("FQ","FQ",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=REX&Dossier2=FQ");
		$menuHamburger.=TitreFilsHamburger("FQ","FQ",$_SESSION['Langue'],"ListeDocs.php?Page=qualite&Dossier1=REX&Dossier2=FQ");
	}
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
	$menuHamburger.= "</ul>";
}

//#####################
//RESSOURCES HUMAINES//
//#####################
if($modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuV2("Ressources humaines","Human ressources",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Ressources humaines","Human ressources",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Communications","Communications",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Communications","Communications",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	$menuBandeau.=TitreFilsV2("Notes RH","HR Notes",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=NotesRH");
	$menuHamburger.=TitreFilsHamburger("Notes RH","HR Notes",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=NotesRH");
	$menuBandeau.=TitreFilsV2("Foire aux questions","Frequently asked questions",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=FAQ");
	$menuHamburger.=TitreFilsHamburger("Foire aux questions","Frequently asked questions",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=FAQ");
	$menuBandeau.=TitreFilsV2("Elections professionnelles","Professional elections",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=ElectionsProfessionnelles");
	$menuHamburger.=TitreFilsHamburger("Elections professionnelles","Professional elections",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=ElectionsProfessionnelles");
	$menuBandeau.=TitreFilsV2("Divers","Various",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=Divers");
	$menuHamburger.=TitreFilsHamburger("Divers","Various",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Communications&Dossier2=Divers");
	$menuBandeau.= '</ul>';
	$menuHamburger.= "</ul>";
	
	if($modeInterim==false)
	{
		$menuBandeau.= "<ul>";
		$menuBandeau.=TitreFilsSlV2("Conventions collectives","Collective agreements",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Conventions collectives","Collective agreements",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		$menuBandeau.=TitreFilsV2("Cadres","Managerial staff",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ConventionsCollectives&Dossier2=Cadres");
		$menuHamburger.=TitreFilsHamburger("Cadres","Managerial staff",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ConventionsCollectives&Dossier2=Cadres");
		$menuBandeau.=TitreFilsV2("Non-cadres","Non-management",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ConventionsCollectives&Dossier2=Non-Cadres");
		$menuHamburger.=TitreFilsHamburger("Non-cadres","Non-management",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ConventionsCollectives&Dossier2=Non-Cadres");
		$menuBandeau.= "</ul>";
		$menuHamburger.= "</ul>";
	}
	
	$menuBandeau.= "<ul>";
	$menuBandeau.=TitreFilsSlV2("Autres","Others",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Autres","Others",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	if($modeInterim==false)
	{
		$menuBandeau.=TitreFilsV2("Accords entreprise","Company agreements",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=AccordEntreprise");
		$menuHamburger.=TitreFilsHamburger("Accords entreprise","Company agreements",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=AccordEntreprise");
		$menuBandeau.=TitreFilsV2("Convention collective / Notes RH","Collective agreement / HR notes",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ConventionCollective");
		$menuHamburger.=TitreFilsHamburger("Convention collective / Notes RH","Collective agreement / HR notes",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ConventionCollective");
		$menuBandeau.=TitreFilsV2("Plan Epargne Entreprise","Company saving plan",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=PEE");
		$menuHamburger.=TitreFilsHamburger("Plan Epargne Entreprise","Company saving plan",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=PEE");
	}
	$menuBandeau.=TitreFilsV2("Réglement intérieur","Rules of procedure",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ReglementInterieur");
	$menuHamburger.=TitreFilsHamburger("Réglement intérieur","Rules of procedure",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=ReglementInterieur");
	$menuBandeau.=TitreFilsV2("Formulaires","Forms",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Formulaires");
	$menuHamburger.=TitreFilsHamburger("Formulaires","Forms",$_SESSION['Langue'],"ListeDocs.php?Page=rh&Dossier1=Formulaires");
	$menuBandeau.= "</ul>";
	$menuHamburger.= "</ul>";
	
	
	//##################
	//MISSION HANDICAP //
	//##################
	if($modeInterim==false 
	&& $modeSousTraitant==false 
	&& mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("Mission Handicap","Handicap Mission",$_SESSION['Langue'],"ListeDocs.php?Page=missionhandicap&Dossier1=missionhandicap");
		$menuHamburger.=TitreFilsHamburger("Mission Handicap","Handicap Mission",$_SESSION['Langue'],"ListeDocs.php?Page=missionhandicap&Dossier1=missionhandicap");
		$menuBandeau.= '</ul>';	
	}
	
		

	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}

//##################
//IN-SITU//
//##################
if(mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$IdPersonneConnectee."' AND Id_Plateforme=19"))>0 
|| mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee))>0
|| DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteProduit,$IdPosteAssistantQualite))
|| DroitsFormation1Plateforme(10,array($IdPosteResponsableQualite,$IdPosteFormateur,$IdPosteResponsablePlateforme))
|| DroitsFormationPrestations(array(10),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteReferentQualiteProduit))
|| DroitsFormation1Plateforme(9,array($IdPosteResponsableQualite))
|| DroitsFormation1Plateforme(19,array($IdPosteResponsableRH,$IdPosteResponsablePlateforme,$IdPosteResponsableQualite,$IdPosteResponsableHSE))
|| DroitsFormationPrestations(array(19),array($IdPosteResponsableProjet))
|| DroitsFormation1Plateforme(1,array($IdPosteResponsableRH,$IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteFormateur))
|| DroitsFormationPrestations(array(1),array($IdPosteReferentQualiteProduit))
)
{
	$menuBandeau.=TitrePereMenuV2("Prestations in-situ","In-Situ activities",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Prestations in-situ","In-Situ activities",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	
	if(mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=1 AND Id_Personne=".$IdPersonneConnectee))>0 
	|| mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Plateforme=1 AND Id_Personne=".$IdPersonneConnectee))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("AAA-MGX TLS","AAA-MGX TLS",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=MGX");
		$menuHamburger.=TitreFilsHamburger("AAA-MGX TLS","AAA-MGX TLS",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=MGX");
		$menuBandeau.= '</ul>';
	}
	// 17 = Siège social et 10 = Region Ouest
	if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteAssistantQualite))
		|| DroitsFormation1Plateforme(10,array($IdPosteResponsableQualite,$IdPosteFormateur,$IdPosteResponsablePlateforme))
		|| DroitsFormationPrestations(array(10),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteReferentQualiteProduit))
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("AAA Ouest","AAA Ouest",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("AAA Ouest","AAA Ouest",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	
		$menuBandeau.=TitreFilsV2("Formation","Formation",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Ouest&Dossier2=Formation");
		$menuHamburger.=TitreFilsHamburger("Formation","Formation",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Ouest&Dossier2=Formation");
	
		$menuBandeau.= '</ul>';
		$menuHamburger.= "</ul>";
	}
	// 17 = Siège social et 9 = Region Nord
	if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteAssistantQualite))
		|| DroitsFormation1Plateforme(9,array($IdPosteResponsableQualite))
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("AAA Nord","AAA Nord",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("AAA Nord","AAA Nord",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

		$menuBandeau.=TitreFilsV2("Formation","Formation",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Nord&Dossier2=Formation");
		$menuHamburger.=TitreFilsHamburger("Formation","Formation",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Nord&Dossier2=Formation");

		$menuBandeau.=TitreFilsV2("QCM","QCM",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Nord&Dossier2=QCM");
		$menuHamburger.=TitreFilsHamburger("QCM","QCM",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Nord&Dossier2=QCM");

		$menuBandeau.=TitreFilsV2("Fiche de présence","Fiche de présence",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Nord&Dossier2=FichePresence");
		$menuHamburger.=TitreFilsHamburger("Fiche de présence","Fiche de présence",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Nord&Dossier2=FichePresence");

		$menuHamburger.= "</ul>";
		$menuBandeau.= '</ul>';
		
	}
	
	if(mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$IdPersonneConnectee."' AND Id_Plateforme=19"))>0
	|| DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteAssistantQualite))
	|| DroitsFormation1Plateforme(19,array($IdPosteResponsableRH,$IdPosteResponsablePlateforme,$IdPosteResponsableQualite,$IdPosteResponsableHSE))
	|| DroitsFormationPrestations(array(19),array($IdPosteResponsableProjet))
	)		
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("AAA Sud Est","AAA Sud Est",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("AAA Sud Est","AAA Sud Est",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		$menuBandeau.=TitreFilsV2("Classeur RH","Classeur RH",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=RH");
		$menuHamburger.=TitreFilsHamburger("Classeur RH","Classeur RH",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=RH");
		$menuBandeau.=TitreFilsV2("Classeur HSE","Classeur HSE",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=HSE");
		$menuHamburger.=TitreFilsHamburger("Classeur HSE","Classeur HSE",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=HSE");
		$menuBandeau.=TitreFilsV2("Classeur Qualité","Classeur Qualité",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=Qualite");
		$menuHamburger.=TitreFilsHamburger("Classeur Qualité","Classeur Qualité",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=Qualite");
		$menuBandeau.=TitreFilsV2("Plan de prévention","Plan de prévention",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=Pdp");
		$menuHamburger.=TitreFilsHamburger("Plan de prévention","Plan de prévention",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=Pdp");
		$menuBandeau.=TitreFilsV2("Planning Formation","Planning Formation",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=PlanningFormation");
		$menuHamburger.=TitreFilsHamburger("Planning Formation","Planning Formation",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=SudEst&Dossier2=PlanningFormation");

		$menuHamburger.= "</ul>";
		$menuBandeau.= '</ul>';
	}

	if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteAssistantQualite))
		|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
		|| DroitsFormationPrestations(array(1),array($IdPosteReferentQualiteProduit))
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("AAA-TLS - Qualité","AAA-TLS - Qualité",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("AAA-TLS - Qualité","AAA-TLS - Qualité",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	
		$menuBandeau.=TitreFilsV2("DOCAAA","DOCAAA",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=DOCAAA");
		$menuHamburger.=TitreFilsHamburger("DOCAAA","DOCAAA",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=DOCAAA");;


		$menuBandeau.=TitreFilsV2("DQ813","DQ813",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=DQ813");
		$menuHamburger.=TitreFilsHamburger("DQ813","DQ813",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=DQ813");

		$menuBandeau.=TitreFilsV2("Formation SMQ","Formation SMQ",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=FormationSMQ");
		$menuHamburger.=TitreFilsHamburger("Formation SMQ","Formation SMQ",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=FormationSMQ");

		$menuBandeau.=TitreFilsV2("FQ","FQ",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=FQ");
		$menuHamburger.=TitreFilsHamburger("FQ","FQ",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=FQ");
		
		$menuBandeau.=TitreFilsV2("FM","FM",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=FM");
		$menuHamburger.=TitreFilsHamburger("FM","FM",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=FM");
		
		$menuBandeau.=TitreFilsV2("Gestion documentaire","Gestion documentaire",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=GestionDocumentaire");
		$menuHamburger.=TitreFilsHamburger("Gestion documentaire","Gestion documentaire",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=GestionDocumentaire");
		
		$menuBandeau.=TitreFilsV2("IQTO","IQTO",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=IQTO");
		$menuHamburger.=TitreFilsHamburger("IQTO","IQTO",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=IQTO");
		
		$menuBandeau.=TitreFilsV2("IDS","IDS",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=IDS");
		$menuHamburger.=TitreFilsHamburger("IDS","IDS",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=IDS");
		
		$menuBandeau.=TitreFilsV2("MO","MO",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=MO");
		$menuHamburger.=TitreFilsHamburger("MO","MO",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=MO");
		
		$menuBandeau.=TitreFilsV2("NDS","NDS",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=NDS");
		$menuHamburger.=TitreFilsHamburger("NDS","NDS",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=NDS");
		
		$menuBandeau.=TitreFilsV2("ORGA","ORGA",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=ORGA");
		$menuHamburger.=TitreFilsHamburger("ORGA","ORGA",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=ORGA");
		
		$menuBandeau.=TitreFilsV2("PQ","PQ",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=PQ");
		$menuHamburger.=TitreFilsHamburger("PQ","PQ",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=PQ");
		
		$menuBandeau.=TitreFilsV2("PR03","PR03",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=PR03");
		$menuHamburger.=TitreFilsHamburger("PR03","PR03",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=PR03");
		
		$menuBandeau.=TitreFilsV2("SURV","SURV",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=SURV");
		$menuHamburger.=TitreFilsHamburger("SURV","SURV",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=Qualite&Dossier2=SURV");

		$menuHamburger.= "</ul>";
		$menuBandeau.= '</ul>';
	}
	if(mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=1 AND Id_Personne=".$IdPersonneConnectee))>0 
	|| mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Plateforme=1 AND Id_Personne=".$IdPersonneConnectee))>0
	|| DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteAssistantQualite))
	|| DroitsFormation1Plateforme(1,array($IdPosteResponsableRH,$IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme))
	|| DroitsFormationPrestations(array(1),array($IdPosteReferentQualiteProduit))
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("AAA-TLS - Communication Bureau vers Prestations","AAA-TLS - Communication from office to activity",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=CommBureauSites");
		$menuHamburger.=TitreFilsHamburger("AAA-TLS - Communication Bureau vers Prestations","AAA-TLS - Communication from office to activity",$_SESSION['Langue'],"ListeDocs.php?Page=insitu&Dossier1=CommBureauSites");
		$menuBandeau.= '</ul>';
		
	}
	
	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}

//##################
// C A N A D A //
//##################
if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier1 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada'"))>0)
{
	$menuBandeau.=TitrePereMenuV2("AAA Canada","AAA Canada",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("AAA Canada","AAA Canada",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";

	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='ECMECalibration'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("ECME – Calibration","ECME – Calibration",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("ECME – Calibration","ECME – Calibration",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Contrat"){
				$menuBandeau.=TitreFilsV2("Contrat","Contrat",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=Contrat");
				$menuHamburger.=TitreFilsHamburger("Contrat","Contrat",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=Contrat");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="FicheVie"){
				$menuBandeau.=TitreFilsV2("Fiche de vie","Fiche de vie",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=FicheVie");
				$menuHamburger.=TitreFilsHamburger("Fiche de vie","Fiche de vie",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=FicheVie");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="NormeCalibration"){
				$menuBandeau.=TitreFilsV2("Norme de calibration","Norme de calibration",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=NormeCalibration");
				$menuHamburger.=TitreFilsHamburger("Norme de calibration","Norme de calibration",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=NormeCalibration");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="SuiviExpiration"){
				$menuBandeau.=TitreFilsV2("Suivi expiration - Assignation","Suivi expiration - Assignation",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=SuiviExpiration");
				$menuHamburger.=TitreFilsHamburger("Suivi expiration - Assignation","Suivi expiration - Assignation",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=SuiviExpiration");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="RapportEtalonnage"){
				$menuBandeau.=TitreFilsV2("Rapport d’étalonnage - Certificat","Rapport d’étalonnage - Certificat",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=RapportEtalonnage");
				$menuHamburger.=TitreFilsHamburger("Rapport d’étalonnage - Certificat","Rapport d’étalonnage - Certificat",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=RapportEtalonnage");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="RapportNonConformites"){
				$menuBandeau.=TitreFilsV2("Rapport des Non-Conformités","Rapport des Non-Conformités",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=RapportNonConformites");
				$menuHamburger.=TitreFilsHamburger("Rapport des Non-Conformités","Rapport des Non-Conformités",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=ECMECalibration&Dossier2=RapportNonConformites");
				break;}}
		
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='Training'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Formations","Formations",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Formations","Formations",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Planning"){
			$menuBandeau.=TitreFilsV2("Planning","Planning",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=Planning");
			$menuHamburger.=TitreFilsHamburger("Planning","Planning",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=Planning");
		break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="EBTT"){
				$menuBandeau.=TitreFilsV2("EBTT","EBTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=EBTT");
				$menuHamburger.=TitreFilsHamburger("EBTT","EBTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=EBTT");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="SBTT"){
				$menuBandeau.=TitreFilsV2("SBTT","SBTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=SBTT");
				$menuHamburger.=TitreFilsHamburger("SBTT","SBTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=SBTT");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="EFTT"){
				$menuBandeau.=TitreFilsV2("EFTT","EFTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=EFTT");
				$menuHamburger.=TitreFilsHamburger("EFTT","EFTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=EFTT");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="SFTT"){
				$menuBandeau.=TitreFilsV2("SFTT","SFTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=SFTT");
				$menuHamburger.=TitreFilsHamburger("SFTT","SFTT",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=SFTT");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Training"){
				$menuBandeau.=TitreFilsV2("Training","Training",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=Training");
				$menuHamburger.=TitreFilsHamburger("Training","Training",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Training&Dossier2=Training");
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='Operations'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Opérations et analyses de risques","Opérations et analyses de risques",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Opérations et analyses de risques","Opérations et analyses de risques",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-MIR_STL_G7_OSW"){
				$menuBandeau.=TitreFilsV2("QUEBEC-MIR_STL_G7_OSW","QUEBEC-MIR_STL_G7_OSW",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-MIR_STL_G7_OSW");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-MIR_STL_G7_OSW","QUEBEC-MIR_STL_G7_OSW",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-MIR_STL_G7_OSW");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PIT_BA_EWIS"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PIT_BA_EWIS","QUEBEC-PIT_BA_EWIS",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PIT_BA_EWIS");;
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PIT_BA_EWIS","QUEBEC-PIT_BA_EWIS",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PIT_BA_EWIS");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL3_BAMX_CHL"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL3_BAMX_CHL","QUEBEC-PL3_BAMX_CHL",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL3_BAMX_CHL");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL3_BAMX_CHL","QUEBEC-PL3_BAMX_CHL",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL3_BAMX_CHL");;
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL3_MHI_CHL_STR"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL3_MHI_CHL_STR","QUEBEC-PL3_MHI_CHL_STR",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL3_MHI_CHL_STR");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL3_MHI_CHL_STR","QUEBEC-PL3_MHI_CHL_STR",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL3_MHI_CHL_STR");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_AER_A220"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_AER_A220","QUEBEC-PL8_AER_A220",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_AER_A220");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_AER_A220","QUEBEC-PL8_AER_A220",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_AER_A220");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_AIR_A220_MET"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_AIR_A220_MET","QUEBEC-PL8_AIR_A220_MET",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_AIR_A220_MET");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_AIR_A220_MET","QUEBEC-PL8_AIR_A220_MET",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_AIR_A220_MET");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_BAMX_CRJ"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_BAMX_CRJ","QUEBEC-PL8_BAMX_CRJ",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_BAMX_CRJ");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_BAMX_CRJ","QUEBEC-PL8_BAMX_CRJ",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_BAMX_CRJ");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_CSALP_A220_AVR"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_CSALP_A220_AVR","QUEBEC-PL8_CSALP_A220_AVR",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_CSALP_A220_AVR");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_CSALP_A220_AVR","QUEBEC-PL8_CSALP_A220_AVR",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_CSALP_A220_AVR");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_ITT_A220"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_ITT_A220","QUEBEC-PL8_ITT_A220",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_ITT_A220");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_ITT_A220","QUEBEC-PL8_ITT_A220",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_ITT_A220");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_LAT_CRJ"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_LAT_CRJ","QUEBEC-PL8_LAT_CRJ",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_LAT_CRJ");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_LAT_CRJ","QUEBEC-PL8_LAT_CRJ",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_LAT_CRJ");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QUEBEC-PL8_MIRABEL"){
				$menuBandeau.=TitreFilsV2("QUEBEC-PL8_MIRABEL","QUEBEC-PL8_MIRABEL",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_MIRABEL");
				$menuHamburger.=TitreFilsHamburger("QUEBEC-PL8_MIRABEL","QUEBEC-PL8_MIRABEL",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=QUEBEC-PL8_MIRABEL");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="AnalyseRisque"){
				$menuBandeau.=TitreFilsV2("Liste des analyses de risques","Liste des analyses de risques",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=AnalyseRisque");
				$menuHamburger.=TitreFilsHamburger("Liste des analyses de risques","Liste des analyses de risques",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Operations&Dossier2=AnalyseRisque");
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}

	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='QUALITE_OPEX'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Qualité-OPEX","Qualité-OPEX",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Qualité-OPEX","Qualité-OPEX",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="DQ506"){
				$menuBandeau.=TitreFilsV2("D-0601-Plan d'actions","D-0601-Plan d'actions",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=DQ506");
				$menuHamburger.=TitreFilsHamburger("D-0601-Plan d'actions","D-0601-Plan d'actions",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=DQ506");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Ecme"){
				$menuBandeau.=TitreFilsV2("ECME","ECME",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=Ecme");
				$menuHamburger.=TitreFilsHamburger("ECME","ECME",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=Ecme");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="SpecificDocumentation"){
				$menuBandeau.=TitreFilsV2("Specific Documentation","Specific Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=SpecificDocumentation");
				$menuHamburger.=TitreFilsHamburger("Specific Documentation","Specific Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=SpecificDocumentation");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Audit"){
				$menuBandeau.=TitreFilsV2("Audit","Audit",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=Audit");
				$menuHamburger.=TitreFilsHamburger("Audit","Audit",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=Audit");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="FormationSMQ"){
				$menuBandeau.=TitreFilsV2("Formation SMQ","Formation SMQ",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=FormationSMQ");
				$menuHamburger.=TitreFilsHamburger("Formation SMQ","Formation SMQ",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=QUALITE_OPEX&Dossier2=FormationSMQ");
				break;}}
	  $menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='Reporting'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Reporting","Reporting",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Reporting","Reporting",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="M01"){
				$menuBandeau.=TitreFilsV2("M01 indicator (Direction)","M01 indicator (Direction)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=M01");
				$menuHamburger.=TitreFilsHamburger("M01 indicator (Direction)","M01 indicator (Direction)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=M01");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="M02"){
				$menuBandeau.=TitreFilsV2("M02 indicator (Quality)","M02 indicator (Quality)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=M02");
				$menuHamburger.=TitreFilsHamburger("M02 indicator (Quality)","M02 indicator (Quality)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=M02");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="R01"){
				$menuBandeau.=TitreFilsV2("R01 indicator (Commercial)","R01 indicator (Commercial)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=R01");
				$menuHamburger.=TitreFilsHamburger("R01 indicator (Commercial)","R01 indicator (Commercial)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=R01");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="R03"){
				$menuBandeau.=TitreFilsV2("R03 indicator (Operation)","R03 indicator (Operation)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=R03");
				$menuHamburger.=TitreFilsHamburger("R03 indicator (Operation)","R03 indicator (Operation)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=R03");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="R04"){
				$menuBandeau.=TitreFilsV2("R04 indicator (Financial)","R04 indicator (Financial)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=R04");
				$menuHamburger.=TitreFilsHamburger("R04 indicator (Financial)","R04 indicator (Financial)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=R04");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="S02"){
				$menuBandeau.=TitreFilsV2("S02 Indicator (HR)","S02 Indicator (HR)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=S02");
				$menuHamburger.=TitreFilsHamburger("S02 Indicator (HR)","S02 Indicator (HR)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=S02");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="S03"){
				$menuBandeau.=TitreFilsV2("S03 indicator (Procurement)","S03 indicator (Procurement)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=S03");
				$menuHamburger.=TitreFilsHamburger("S03 indicator (Procurement)","S03 indicator (Procurement)",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=Reporting&Dossier2=S03");
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='BU_Ontario'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("BU-Ontario (Quality)","BU-Ontario (Quality)",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("BU-Ontario (Quality)","BU-Ontario (Quality)",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="PolyvalenceTables"){
				$menuBandeau.=TitreFilsV2("Polyvalence Tables","Polyvalence Tables",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=PolyvalenceTables");
				$menuHamburger.=TitreFilsHamburger("Polyvalence Tables","Polyvalence Tables",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=PolyvalenceTables");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="OTD_OQD"){
				$menuBandeau.=TitreFilsV2("OTD/OQD","OTD/OQD",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=OTD_OQD");
				$menuHamburger.=TitreFilsHamburger("OTD/OQD","OTD/OQD",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=OTD_OQD");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="CustomerSatisfaction"){
				$menuBandeau.=TitreFilsV2("Customer Satisfaction","Customer Satisfaction",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=CustomerSatisfaction");
				$menuHamburger.=TitreFilsHamburger("Customer Satisfaction","Customer Satisfaction",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=CustomerSatisfaction");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="QualityPCS"){
				$menuBandeau.=TitreFilsV2("Quality PCS","Quality PCS",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=QualityPCS");
				$menuHamburger.=TitreFilsHamburger("Quality PCS","Quality PCS",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=QualityPCS");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Other"){
				$menuBandeau.=TitreFilsV2("Other","Other",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=Other");
				$menuHamburger.=TitreFilsHamburger("Other","Other",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=BU_Ontario&Dossier2=Other");
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='RECRUTEMENT'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("RECRUTEMENT","RECRUTEMENT",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("RECRUTEMENT","RECRUTEMENT",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		$menuBandeau.=TitreFilsV2("Recrutement","Recrutement",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=RECRUTEMENT");
		$menuHamburger.=TitreFilsHamburger("Recrutement","Recrutement",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=RECRUTEMENT");
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='GestionDocumentaire'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("*GESTION DOCUMENTAIRE AAA CANADA*","*GESTION DOCUMENTAIRE AAA CANADA*",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("*GESTION DOCUMENTAIRE AAA CANADA*","*GESTION DOCUMENTAIRE AAA CANADA*",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="DocApp"){
				$menuBandeau.=TitreFilsV2("Documents applicables","Documents applicables",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=GestionDocumentaire&Dossier2=DocApp");
				$menuHamburger.=TitreFilsHamburger("Documents applicables","Documents applicables",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=GestionDocumentaire&Dossier2=DocApp");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="GestionDocumentaire"){
				$menuBandeau.=TitreFilsV2("Gestion Documentaire","Gestion Documentaire",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=GestionDocumentaire&Dossier2=GestionDocumentaire");
				$menuHamburger.=TitreFilsHamburger("Gestion Documentaire","Gestion Documentaire",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=GestionDocumentaire&Dossier2=GestionDocumentaire");
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='EXPERIENCE_EMPLOYE'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Expérience Employé","Expérience Employé",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Expérience Employé","Expérience Employé",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="HRDocumentation"){
				$menuBandeau.=TitreFilsV2("HR Documentation","HR Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=EXPERIENCE_EMPLOYE&Dossier2=HRDocumentation");
				$menuHamburger.=TitreFilsHamburger("HR Documentation","HR Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=EXPERIENCE_EMPLOYE&Dossier2=HRDocumentation");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="KitNouveauGestionnaire"){
				$menuBandeau.=TitreFilsV2("Kit Nouveau Gestionnaire","Kit Nouveau Gestionnaire",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=EXPERIENCE_EMPLOYE&Dossier2=KitNouveauGestionnaire");
				$menuHamburger.=TitreFilsHamburger("Kit Nouveau Gestionnaire","Kit Nouveau Gestionnaire",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=EXPERIENCE_EMPLOYE&Dossier2=KitNouveauGestionnaire");;
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Dossier2 FROM new_acces WHERE Login='".$LoginPersonneConnectee."' AND Page='canada' AND Dossier1='HSE'"))>0)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("HSE","HSE",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("HSE","HSE",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Comite"){
				$menuBandeau.=TitreFilsV2("Comite","Comite",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=HSE&Dossier2=Comite");
				$menuHamburger.=TitreFilsHamburger("Comite","Comite",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=HSE&Dossier2=Comite");
				break;}}
		mysqli_data_seek($resAcc,0);
		while($LigAcc=mysqli_fetch_array($resAcc)){
			if($LigAcc[0]=="Accident"){
				$menuBandeau.=TitreFilsV2("Accident","Accident",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=HSE&Dossier2=Accident");
				$menuHamburger.=TitreFilsHamburger("Accident","Accident",$_SESSION['Langue'],"ListeDocs.php?Page=canada&Dossier1=HSE&Dossier2=Accident");
				break;}}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}

	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}

//##################
//GESTION PRESTATION
//##################
if(mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee))>0 
|| mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))>0
|| mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta2))>0
|| $modeSousTraitant==true
|| mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Personne =".$IdPersonneConnectee))>0 
|| DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteInnovation))
|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteFormateur))
|| DroitsFormationPlateforme(array($IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteDirection,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteCoordinateurSecurite,$IdPosteReferentSurveillance,$IdPosteReferentQualiteProcedesSpeciaux,$IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableFormation,$IdPosteFormateur,$IdPosteResponsableHSE,$IdPosteProcedeSpecial,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteAssistantRH,$IdPosteResponsableRH))
|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteAssistantePrestation))
)
{
	$menuBandeau.=TitrePereMenuV2("Gestion des prestations","Activities management",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Gestion des prestations","Activities management",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";

	//Gestion des compétences
	//-----------------------
	$DroitCompetences=false;
	if(
	DroitsFormationPlateforme(array($IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteDirection,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteCoordinateurSecurite,$IdPosteReferentSurveillance,$IdPosteReferentQualiteProcedesSpeciaux,$IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableFormation,$IdPosteFormateur,$IdPosteResponsableHSE,$IdPosteProcedeSpecial,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteAssistantRH,$IdPosteResponsableRH,$IdPosteInnovation))
	|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteAssistantePrestation))
	|| mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Personne =".$IdPersonneConnectee))>0
	){
		$DroitCompetences=true;
	}
	
	if($DroitCompetences ||  $TabMetier[0]==85 || $nbResultQualifS>0 || mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste <> 22"))>0)
	{
		if($DroitCompetences || $TabMetier[0]==85 || $nbResultQualifS>0 || mysqli_num_rows($resAcc2=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee))>0)
		{
			$menuBandeau.= '<ul>';
			$menuBandeau.=TitreFilsV2("Gestion des compétences","Competencies Management",$_SESSION['Langue'],"Outils/Competences/Tableau_De_Bord.php");
			$menuHamburger.=TitreFilsHamburger("Gestion des compétences","Competencies Management",$_SESSION['Langue'],"Outils/Competences/Tableau_De_Bord.php");
			$menuBandeau.= '</ul>';
		}
	}
	
	//Gestion des formations
	//----------------------
	//Ouverture des accès uniquement aux prestations suivantes :
	if(mysqli_num_rows($resTest=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,9,10,13,19,22,23,32) AND Id_Personne =".$IdPersonneConnectee))>0
	|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,9,10,13,19,22,23,32) AND Active=0) AND Id_Poste IN (1,2,3,4) AND Id_Personne=".$IdPersonneConnectee))>0
	|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Plateforme IN (1,3,4,9,10,13,19,20,22,23,32) AND Id_Poste IN (13,15,17,18,19,21,23,31) AND Id_Personne =".$IdPersonneConnectee))>0
	|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_prestation WHERE Id_Poste IN (5,6) AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,9,10,13,19,22,23,32) ) AND Id_Personne =".$IdPersonneConnectee))>0)
	{
		
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("QUALIPSO","QUALIPSO",$_SESSION['Langue'],"Outils/Formation/Tableau_De_Bord.php");
		$menuHamburger.=TitreFilsHamburger("QUALIPSO","QUALIPSO",$_SESSION['Langue'],"Outils/Formation/Tableau_De_Bord.php");
		
		//Réserver à l'informatique Id_Personne=1351|406|3556
		if($IdPersonneConnectee==1351 || $IdPersonneConnectee==406 || $IdPersonneConnectee==3556){
			$menuBandeau.=TitreFilsV2("Documents QUALIPSO","QUALIPSO Documents",$_SESSION['Langue'],"Outils/Formation/Liste_Document.php");
			$menuHamburger.=TitreFilsHamburger("Documents QUALIPSO","QUALIPSO Documents",$_SESSION['Langue'],"Outils/Formation/Liste_Document.php");
		}
		$menuBandeau.= '</ul>';
	}
	
	
	//##################
	//EPE / EPP       //
	//##################
	if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".$IdPosteDirection.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29,32) AND Id_Personne =".$IdPersonneConnectee))>0
	|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_prestation WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29,32)) AND Id_Personne =".$IdPersonneConnectee))>0
	|| ((mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29,32) AND Id_Personne =".$IdPersonneConnectee))>0 && estInterimPourMenu(date('Y-m-d'),$_SESSION['Id_Personne'])==0 && estSousTraitantPourMenu($IdPersonneConnectee)==0)))
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("EPE / EPP","EPE / EPP",$_SESSION['Langue'],"Outils/EPE/Tableau_De_Bord.php");
		$menuHamburger.=TitreFilsHamburger("EPE / EPP","EPE / EPP",$_SESSION['Langue'],"Outils/EPE/Tableau_De_Bord.php");
		$menuBandeau.= '</ul>';
	}
	
	if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") AND Id_Plateforme IN (1,3,4,5,9,10,17,19,23,24,32) AND Id_Personne =".$IdPersonneConnectee))>0
	|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_prestation WHERE Id_Poste IN (".implode(',',$TableauIdPostesResponsablesPrestation).",".$IdPosteMagasinier.") AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,5,9,10,17,19,23,24,32)) AND Id_Personne =".$IdPersonneConnectee))>0
	|| (mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,17,19,23,24,32) AND Id_Personne =".$IdPersonneConnectee))>0 && estSousTraitantPourMenu($IdPersonneConnectee)==0))
	{	
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("Suivi du matériel","Monitoring of equipment",$_SESSION['Langue'],"Outils/Tools/Tableau_De_Bord.php");
		$menuHamburger.=TitreFilsHamburger("Suivi du matériel","Monitoring of equipment",$_SESSION['Langue'],"Outils/Tools/Tableau_De_Bord.php");
		$menuBandeau.= '</ul>';
	}

	
	
	//Hiérarchie du personnel
	//-----------------------
	if(estSousTraitantPourMenu($IdPersonneConnectee)==0
	&& (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste <> 1 AND Id_Poste <> 22"))>0 
	|| mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN(11,14,17,18,19,13,23,31) "))>0)
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Hiérarchie du personnel","Staff hierarchy",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Hiérarchie du personnel","Staff hierarchy",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		
		$menuBandeau.=TitreFilsV2("Poste","Workplace",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Poste.php");
		$menuHamburger.=TitreFilsHamburger("Poste","Workplace",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Poste.php");
		$menuBandeau.=TitreFilsV2("Responsables par prestation","Responsible per service",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Prestation_Poste.php");
		$menuHamburger.=TitreFilsHamburger("Responsables par prestation","Responsible per service",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Prestation_Poste.php");
		if( mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN(14,17,18,19,13,38) "))>0)
		{
			$menuBandeau.=TitreFilsV2("Responsables par unité d'exploitation","Responsible per operating unit",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Plateforme_Poste.php");
			$menuHamburger.=TitreFilsHamburger("Responsables par unité d'exploitation","Responsible per operating unit",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Plateforme_Poste.php");
		}
		if($IdPersonneConnectee==1351 || $IdPersonneConnectee==406){
			$menuBandeau.=TitreFilsV2("Responsables - Informations","Responsables - Informations",$_SESSION['Langue'],"Outils/Hierarchie/Liste_InformationsResponsables.php");
			$menuHamburger.=TitreFilsHamburger("Responsables - Informations","Responsables - Informations",$_SESSION['Langue'],"Outils/Hierarchie/Liste_InformationsResponsables.php");
		}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	elseif(estSousTraitantPourMenu($IdPersonneConnectee)==0 
	&& (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_personne_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Plateforme=1"))>0 || mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))>0)
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsSlV2("Hiérarchie du personnel","Staff hierarchy",$_SESSION['Langue']);
		$menuHamburger.=TitreFilsHamburgerS1("Hiérarchie du personnel","Staff hierarchy",$_SESSION['Langue']);
		$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
		
		$menuBandeau.=TitreFilsV2("Poste","Workplace",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Poste.php");
		$menuHamburger.=TitreFilsHamburger("Poste","Workplace",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Poste.php");
		$menuBandeau.=TitreFilsV2("Responsables par prestation","Responsible per service",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Prestation_Poste.php");
		$menuHamburger.=TitreFilsHamburger("Responsables par prestation","Responsible per service",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Prestation_Poste.php");
		
		if( mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN(17,18,19,13,38) "))>0)
		{
			$menuBandeau.=TitreFilsV2("Responsables par unité d'exploitation","Responsible per operating unit",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Plateforme_Poste.php");
			$menuHamburger.=TitreFilsHamburger("Responsables par unité d'exploitation","Responsible per operating unit",$_SESSION['Langue'],"Outils/Hierarchie/Liste_Plateforme_Poste.php");
		}
		if($IdPersonneConnectee==1351 || $IdPersonneConnectee==406){
			$menuBandeau.=TitreFilsV2("Responsables - Informations","Responsables - Informations",$_SESSION['Langue'],"Outils/Hierarchie/Liste_InformationsResponsables.php");
			$menuHamburger.=TitreFilsHamburger("Responsables - Informations","Responsables - Informations",$_SESSION['Langue'],"Outils/Hierarchie/Liste_InformationsResponsables.php");
		}
		$menuHamburger.= '</ul>';
		$menuBandeau.= '</ul>';
	}
	
	//GESTION DU PLANNING
	//-------------------
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_personne_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Plateforme IN (1,19,23) "))>0 
		|| mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta2))>0
		|| DroitsFormation1Plateforme("1,3,4,5,9,10,13,17,19,23,24,27,28,29,32",array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteOperateurSaisieRH ,$IdPosteResponsablePlateforme,$IdPosteOperateurSaisieRH,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))
		|| DroitsFormationPrestations(array(1,3,4,5,9,10,13,17,19,23,24,27,28,29,32),$TableauIdPostesResponsablesPrestation))
	{
		if(mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaLimite))>0){
			$menuBandeau.= '<ul>';
			$menuBandeau.=TitreFilsSlV2("Gestion du planning","Planning management",$_SESSION['Langue']);
			$menuHamburger.=TitreFilsHamburgerS1("Gestion du planning","Planning management",$_SESSION['Langue']);
			$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
			
			if(DroitsFormationPrestations(array(19),$TableauIdPostesResponsablesPrestation) 
				|| DroitsFormation1Plateforme("19",array($IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH)))
			{
				$menuBandeau.=TitreFilsV2("Informations du personnel","Staff information",$_SESSION['Langue'],"Outils/Planning/InformationsPersonnel.php");
				$menuHamburger.=TitreFilsHamburger("Informations du personnel","Staff information",$_SESSION['Langue'],"Outils/Planning/InformationsPersonnel.php");
			}
			$menuBandeau.=TitreFilsV2("Planning","Planning",$_SESSION['Langue'],"Outils/Planning/Planning.php");
			$menuHamburger.=TitreFilsHamburger("Planning","Planning",$_SESSION['Langue'],"Outils/Planning/Planning.php");
			
			$menuBandeau.=TitreFilsV2("Pointage","Timesheet",$_SESSION['Langue'],"Outils/Planning/Pointage.php");
			$menuHamburger.=TitreFilsHamburger("Pointage","Timesheet",$_SESSION['Langue'],"Outils/Planning/Pointage.php");
			
			if(DroitsFormationPrestations(array(19),$TableauIdPostesResponsablesPrestation) 
				|| DroitsFormation1Plateforme("19",array($IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH)))
			{
				$menuBandeau.=TitreFilsV2("Heure supplémentaire","Additional hours",$_SESSION['Langue'],"Outils/Planning/Liste_Heures_Supp.php");;
				$menuHamburger.=TitreFilsHamburger("Heure supplémentaire","Additional hours",$_SESSION['Langue'],"Outils/Planning/Liste_Heures_Supp.php");
			}
			
			//Gestion des vacations
			//---------------------
			if(DroitsFormationPrestations(array(19),array($IdPosteResponsableProjet))) {
				$menuBandeau.=TitreFilsV2("Gestion des vacations : Vacations par prestation","Management of vacations : Vacations par activity",$_SESSION['Langue'],"Outils/Planning/VacationPrestation.php");
				$menuHamburger.=TitreFilsHamburger("Gestion des vacations : Vacations par prestation","Management of vacations : Vacations par activity",$_SESSION['Langue'],"Outils/Planning/VacationPrestation.php");
			}
			
			$menuHamburger.= '</ul>';
			$menuBandeau.= '</ul>';
		}
		
		if(
			DroitsFormation1Plateforme("1,3,4,5,9,10,13,17,19,23,24,27,28,29,32",array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteResponsablePlateforme,$IdPosteOperateurSaisieRH,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))
			|| DroitsFormationPrestations(array(1,3,4,5,9,10,13,17,19,23,24,27,28,29,32),$TableauIdPostesResponsablesPrestation)
			|| DroitsFormation1Plateforme("17",array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation))
		)
		{	
			$menuBandeau.= '<ul>';
			$menuBandeau.=TitreFilsV2("Accidents de travail","Workplace accidents",$_SESSION['Langue'],"Outils/PlanningV2/Tableau_De_BordAT.php?Menu=1");
			$menuHamburger.=TitreFilsHamburger("Accidents de travail","Workplace accidents",$_SESSION['Langue'],"Outils/PlanningV2/Tableau_De_BordAT.php?Menu=1");
			$menuBandeau.= '</ul>';
		}
		
		$reqPrestaPoste = "SELECT Id_Prestation 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne =".$IdPersonneConnectee."  
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
				AND (
						((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,23,24,27,32,9,10,13,17,19,28)) 
						OR Id_Prestation IN (1205,1206,1483,1478)
					)
				";
		if(
			DroitsFormation1Plateforme("1,3,23,24,27,32,9,10,13,17,19,28",array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteOperateurSaisieRH,$IdPosteResponsablePlateforme,$IdPosteGestionnaireMGX,$IdPosteResponsableMGX))
			|| mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta2))>0
			|| mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste))>0
		)
		{	
			if(DroitsFormation1Plateforme("1,3,23,24,27,32,9,10,13,17,19,28",array($IdPosteResponsablePlateforme)) 
				|| DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) 
				|| DroitsFormation1Plateforme("1,3,23,24,27,9,10,13,17,19,28",$TableauIdPostesRH)
			){
				$menuBandeau.= '<ul>';
				$menuBandeau.=TitreFilsV2("OPTEA","OPTEA",$_SESSION['Langue'],"Outils/PlanningV2/Tableau_De_Bord.php?Menu=1");
				$menuHamburger.=TitreFilsHamburger("OPTEA","OPTEA",$_SESSION['Langue'],"Outils/PlanningV2/Tableau_De_Bord.php?Menu=1");
				$menuBandeau.= '</ul>';
			}
			else{
				$menuBandeau.= '<ul>';
				$menuBandeau.=TitreFilsV2("OPTEA","OPTEA",$_SESSION['Langue'],"Outils/PlanningV2/Tableau_De_Bord.php?Menu=2");
				$menuHamburger.=TitreFilsHamburger("OPTEA","OPTEA",$_SESSION['Langue'],"Outils/PlanningV2/Tableau_De_Bord.php?Menu=2");
				$menuBandeau.= '</ul>';
			}
		}
		
		if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme=1) AND  Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") "))>0)
		{
			$menuBandeau.= '<ul>';
			$menuBandeau.=TitreFilsV2("SQCDPF","SQCDPF",$_SESSION['Langue'],"Outils/PERFOS/Tableau_De_Bord.php");
			$menuHamburger.=TitreFilsHamburger("SQCDPF","SQCDPF",$_SESSION['Langue'],"Outils/PERFOS/Tableau_De_Bord.php");
			$menuBandeau.= '</ul>';
		}
	}
	
	//RECORD
	//--------------------------
	if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) 
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee."  AND (Id_Poste=2 OR Id_Poste=3 OR Id_Poste=4 OR Id_Poste=5 OR Id_Poste=46) AND (SELECT UtiliseMORIS FROM new_competences_prestation WHERE Id=Id_Prestation)=1 "))>0) 
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee."  AND Id_Poste IN (9,15,6,27)) AND UtiliseMORIS=1 "))>0)
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee."  AND Id_Poste IN (41,44) AND Id_Plateforme=17 "))>0)
		)
	{	
		$leMenu=1;
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND Active=0
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$IdPersonneConnectee."
				AND Id_Prestation=new_competences_prestation.Id 
				AND Id_Poste IN (4,5)
				)>0";
		$resultPrestation=mysqli_query($bdd,$req);
		$nbPrestation=mysqli_num_rows($resultPrestation);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND Active=0
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$IdPersonneConnectee."
				AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Id_Poste IN (6)
				)>0";
		$resultCQS=mysqli_query($bdd,$req);
		$nbCQS=mysqli_num_rows($resultCQS);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND Active=0
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$IdPersonneConnectee."
				AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Id_Poste IN (27)
				)>0";
		$resultCG=mysqli_query($bdd,$req);
		$nbCG=mysqli_num_rows($resultCG);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE Active=0
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$IdPersonneConnectee."
				AND Id_Plateforme=17
				AND Id_Poste IN (9,15,27,41,44)
				)>0";
		$resultPlat=mysqli_query($bdd,$req);
		$nbPlat=mysqli_num_rows($resultPlat);
		
		if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbPrestation>0 || $nbPlat>0 || $nbCQS>0 || $nbCG>0){
			$leMenu=7;
		}
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("RECORD","RECORD",$_SESSION['Langue'],"Outils/MORIS2/TableauDeBord.php?Menu=".$leMenu."");
		$menuHamburger.=TitreFilsHamburger("RECORD","RECORD",$_SESSION['Langue'],"Outils/MORIS2/TableauDeBord.php?Menu=".$leMenu."");
		$menuBandeau.= '</ul>';
	}
	
	//SODA
	//--------------------------
	if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) 
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee))>0)
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_surveillant WHERE Id_Personne=".$IdPersonneConnectee))>0)
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_relation WHERE (Evaluation='L' OR (Evaluation='X' AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))) AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777) AND Id_Personne=".$IdPersonneConnectee))>0)
		|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.") "))>0)
		|| DroitsFormationPlateformes(array(1,3,4,5,7,9,10,12,13,16,17,18,19,20,22,23,24,27,28,29,30,32),array($IdPosteResponsableQualite,$IdPosteCoordinateurSecurite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation,$IdPosteReferentSurveillance))
		|| DroitsFormationPrestations(array(1,3,4,5,7,9,10,12,13,16,17,18,19,20,22,23,24,27,28,29,30,32),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
	)
	{	
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("SODA","SODA",$_SESSION['Langue'],"Outils/SODA/Tableau_De_Bord.php");
		$menuHamburger.=TitreFilsHamburger("SODA","SODA",$_SESSION['Langue'],"Outils/SODA/Tableau_De_Bord.php");
		$menuBandeau.= '</ul>';
	}
		
	//Gestion des surveilllances
	//--------------------------
	if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_metier WHERE Id_Metier=85 AND Futur=0 AND Id_Personne=".$IdPersonneConnectee))>0
	|| (DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteCoordinateurSecurite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme)) 
	&& estSousTraitantPourMenu($IdPersonneConnectee)==0)
	|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
	)
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("Gestion des surveillances","Monitoring Management",$_SESSION['Langue'],"Outils/Surveillance/Tableau_De_Bord.php");
		$menuHamburger.=TitreFilsHamburger("Gestion des surveillances","Monitoring Management",$_SESSION['Langue'],"Outils/Surveillance/Tableau_De_Bord.php");
		$menuBandeau.= '</ul>';
	}
	
	//GPAO
	//--------------------------
	if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) 
	)
	{	
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("GPAO","GPAO",$_SESSION['Langue'],"Outils/GPAO/TableauDeBord.php?Menu=1");
		$menuHamburger.=TitreFilsHamburger("GPAO","GPAO",$_SESSION['Langue'],"Outils/GPAO/TableauDeBord.php?Menu=1");
		$menuBandeau.= '</ul>';
	}

	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}

//##################
//IRP//
//##################
if($modeInterim==false && $modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuV2("IRP","IRP",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("IRP","IRP",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("CSE","CSE",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("CSE","CSE",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	
	$menuBandeau.=TitreFilsV2("Comptes rendus des réunions","Lists of elected",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ComptesRendus");
	$menuHamburger.=TitreFilsHamburger("Comptes rendus des réunions","Lists of elected",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ComptesRendus");
	$menuBandeau.=TitreFilsV2("Liste des élus","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ListeElus");
	$menuHamburger.=TitreFilsHamburger("Liste des élus","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ListeElus");
	$menuBandeau.=TitreFilsV2("Règlement intérieur","Rules of procedure",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ReglementInterieur");
	$menuHamburger.=TitreFilsHamburger("Règlement intérieur","Rules of procedure",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ReglementInterieur");
	$menuBandeau.=TitreFilsV2("Activités sociales & culturelles","Social & cultural activities",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ActivitesSocialesCulturelles");
	$menuHamburger.=TitreFilsHamburger("Activités sociales & culturelles","Social & cultural activities",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=ActivitesSocialesCulturelles");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Commissions","Commissions",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Commissions","Commissions",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";
	
	$menuBandeau.=TitreFilsV2("Commission Economique","Economic Commission",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionEconomique");
	$menuHamburger.=TitreFilsHamburger("Commission Economique","Economic Commission",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionEconomique");
	$menuBandeau.=TitreFilsV2("Commission Formation-Egalité-Logement","Training-Equality-Housing Commission",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionFEL");
	$menuHamburger.=TitreFilsHamburger("Commission Formation-Egalité-Logement","Training-Equality-Housing Commission",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionFEL");
	$menuBandeau.=TitreFilsV2("Commission Handicap","Handicap Commission",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionHandicap");
	$menuHamburger.=TitreFilsHamburger("Commission Handicap","Handicap Commission",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionHandicap");
	$menuBandeau.=TitreFilsV2("Commission Hygiène et Sécurité (CSSCT)","Health and Safety Commission (CSSCT)",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionCSSCT");
	$menuHamburger.=TitreFilsHamburger("Commission Hygiène et Sécurité (CSSCT)","Health and Safety Commission (CSSCT)",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Commissions&Dossier2=CommissionCSSCT");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Expression syndicale","Union expression",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Expression syndicale","Union expression",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

	$menuBandeau.=TitreFilsV2("CFDT","CFDT",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=CFDT");
	$menuHamburger.=TitreFilsHamburger("CFDT","CFDT",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=CFDT");
	$menuBandeau.=TitreFilsV2("CFE-CGC","CFE-CGC",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=CFE-CGC");
	$menuHamburger.=TitreFilsHamburger("CFE-CGC","CFE-CGC",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=CFE-CGC");
	$menuBandeau.=TitreFilsV2("CGT","CGT",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=CGT");
	$menuHamburger.=TitreFilsHamburger("CGT","CGT",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=CGT");
	$menuBandeau.=TitreFilsV2("FO","FO",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=FO");
	$menuHamburger.=TitreFilsHamburger("FO","FO",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=ExpressionSyndicale&Dossier2=FO");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteEluCFDT,$IdPosteEluCFE_CGC,$IdPosteEluCGT,$IdPosteEluFO)))
	{
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("BDESE","BDESE",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=BDESE");
		$menuHamburger.=TitreFilsHamburger("BDESE","BDESE",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=BDESE");
		$menuBandeau.= '</ul>';
	}
	
	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
	
	/*
	$menuBandeau.=TitrePereMenuV2("IRP (Archives)","IRP (Archives)",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("IRP (Archives)","IRP (Archives)",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Comité d'entreprise","Works council",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Comité d'entreprise","Works council",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

	$menuBandeau.=TitreFilsV2("Comptes rendus des réunions","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CE&Dossier2=ComptesRendus");
	$menuHamburger.=TitreFilsHamburger("Comptes rendus des réunions","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CE&Dossier2=ComptesRendus");
	$menuBandeau.=TitreFilsV2("Liste des élus","Lists of elected",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CE&Dossier2=ListeElus");
	$menuHamburger.=TitreFilsHamburger("Liste des élus","Lists of elected",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CE&Dossier2=ListeElus");
	$menuBandeau.=TitreFilsV2("Activités soc. & cult.","Entertainment activities",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Activites");
	$menuHamburger.=TitreFilsHamburger("Activités soc. & cult.","Entertainment activities",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=Activites");
	$menuBandeau.=TitreFilsV2("CESU","CESU",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=CESU");
	$menuHamburger.=TitreFilsHamburger("CESU","CESU",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CSE&Dossier2=CESU");
	
	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Délégués du personnel","Staff representatives",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Délégués du personnel","Staff representatives",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

	$menuBandeau.=TitreFilsV2("Comptes rendus","Reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=DP&Dossier2=ComptesRendus");
	$menuHamburger.=TitreFilsHamburger("Comptes rendus","Reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=DP&Dossier2=ComptesRendus");
	$menuBandeau.=TitreFilsV2("Liste des élus","Lists of elected",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=DP&Dossier2=ListeElus");
	$menuHamburger.=TitreFilsHamburger("Liste des élus","Lists of elected",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=DP&Dossier2=ListeElus");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("CHSCT Paris","CHSCT Paris",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("CHSCT Paris","CHSCT Paris",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

	$menuBandeau.=TitreFilsV2("Comptes rendus des réunions","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=ComptesRendus");
	$menuHamburger.=TitreFilsHamburger("Comptes rendus des réunions","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=ComptesRendus");
	$menuBandeau.=TitreFilsV2("Liste des élus et réglement intérieur","Lists of elected and internal rules",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=ListeElus");
	$menuHamburger.=TitreFilsHamburger("Liste des élus et réglement intérieur","Lists of elected and internal rules",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=ListeElus");
	$menuBandeau.=TitreFilsV2("Synthéses des visites","Summary of visits",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=Syntheses");
	$menuHamburger.=TitreFilsHamburger("Synthéses des visites","Summary of visits",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=Syntheses");
	$menuBandeau.=TitreFilsV2("Documentation","Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=Documentation");
	$menuHamburger.=TitreFilsHamburger("Documentation","Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCT&Dossier2=Documentation");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("CHSCT Tarbes","CHSCT Tarbes",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("CHSCT Tarbes","CHSCT Tarbes",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

	$menuBandeau.=TitreFilsV2("Comptes rendus des réunions","Lists of elected and internal rules",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCTTarbes&Dossier2=ComptesRendus");
	$menuHamburger.=TitreFilsHamburger("Comptes rendus des réunions","Lists of elected and internal rules",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCTTarbes&Dossier2=ComptesRendus");
	$menuBandeau.=TitreFilsV2("Liste des élus et réglement intérieur","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCTTarbes&Dossier2=ListeElus");
	$menuHamburger.=TitreFilsHamburger("Liste des élus et réglement intérieur","Meeting reports",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCTTarbes&Dossier2=ListeElus");
	$menuBandeau.=TitreFilsV2("Synthéses des visites","Summary of visits",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCTTarbes&Dossier2=Syntheses");
	$menuHamburger.=TitreFilsHamburger("Synthéses des visites","Summary of visits",$_SESSION['Langue'],"ListeDocs.php?Page=cedpchsct&Dossier1=CHSCTTarbes&Dossier2=Syntheses");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';
	
	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
	*/
	
}


//##################
//PERFORMANCE INDUSTRIELLE//
//##################
if($modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuV2("Performance Industrielle","Performance Industrielle",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Performance Industrielle","Performance Industrielle",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsV2("Documentation","Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=performanceindustrielle&Dossier1=Documentation&Dossier2=");
	$menuHamburger.=TitreFilsHamburger("Documentation","Documentation",$_SESSION['Langue'],"ListeDocs.php?Page=performanceindustrielle&Dossier1=Documentation&Dossier2=");
	$menuBandeau.= '</ul>';

	
	if(DroitsFormation1Plateforme(17,array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation))){
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("Gestion","Gestion",$_SESSION['Langue'],"ListeDocs.php?Page=performanceindustrielle&Dossier1=Gestion&Dossier2=");
		$menuHamburger.=TitreFilsHamburger("Gestion","Gestion",$_SESSION['Langue'],"ListeDocs.php?Page=performanceindustrielle&Dossier1=Gestion&Dossier2=");
		$menuBandeau.= '</ul>';
	}
	
	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}

//##################
// ACCES EXTRANET //
//##################
if($modeSousTraitant==false)
{
	$Requete_DroitsAdministrateurDossiers="SELECT Id FROM dossiers_admin WHERE Id_Personne='".$IdPersonneConnectee."';";
	$Result_DroitsAdministrateurDossiers=mysqli_query($bdd,$Requete_DroitsAdministrateurDossiers);

	$menuBandeau.=TitrePereMenuV2("Accès extranet","Accès extranet",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Accès extranet","Accès extranet",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	if($LoginPersonneConnectee=="AdminExtranetAAA"){
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("Gestion des administrateurs des dossiers","Gestion des administrateurs des dossiers",$_SESSION['Langue'],"Outils/AdminDossiers.php");
		$menuHamburger.=TitreFilsHamburger("Gestion des administrateurs des dossiers","Gestion des administrateurs des dossiers",$_SESSION['Langue'],"Outils/AdminDossiers.php");
		$menuBandeau.= '</ul>';
		
	}
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsV2("Liste administrateurs des dossiers","Liste administrateurs des dossiers",$_SESSION['Langue'],"Outils/AdminDossiers_Liste.php");
	$menuHamburger.=TitreFilsHamburger("Liste administrateurs des dossiers","Liste administrateurs des dossiers",$_SESSION['Langue'],"Outils/AdminDossiers_Liste.php");
	$menuBandeau.= '</ul>';
	
	if(mysqli_num_rows($Result_DroitsAdministrateurDossiers)>0){
		$menuBandeau.= '<ul>';
		$menuBandeau.=TitreFilsV2("Administration des droits par dossiers","Administration des droits par dossiers",$_SESSION['Langue'],"Outils/DroitsV2.php");
		$menuHamburger.=TitreFilsHamburger("Administration des droits par dossiers","Administration des droits par dossiers",$_SESSION['Langue'],"Outils/DroitsV2.php");
		$menuBandeau.= '</ul>';
	}
	
	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}

//##################
//BOITE A IDEE//
//##################
/*
if($modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuCliquableV2("Boite à idées","Ideas box",$_SESSION['Langue'],"Outils/BoiteIdees.php");
	$menuHamburger.=TitrePereCliquableHamburger("Boite à idées","Ideas box",$_SESSION['Langue'],"Outils/BoiteIdees.php");
	
	$menuBandeau.= '</div>';
}
*/



//##################
//BOURSE EMPLOI   //
//##################
/*
if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.") AND Id_Personne =".$IdPersonneConnectee))>0
|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".$IdPosteResponsableRecrutement.") AND Id_Plateforme=17 AND Id_Personne =".$IdPersonneConnectee))>0)
{
	$menuBandeau.=TitrePereMenuCliquableV2("Bourse emploi","Stock exchange job",$_SESSION['Langue'],"Outils/Recrutement/Tableau_De_Bord.php");
	$menuHamburger.=TitrePereCliquableHamburger("Bourse emploi","Stock exchange job",$_SESSION['Langue'],"Outils/Recrutement/Tableau_De_Bord.php");
	
	$menuBandeau.= '</div>';
}*/

//##################
//TalentBoost   //
//##################
/*
if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".$IdPosteResponsableRecrutement.",".$IdPosteRecrutement.",".$IdPosteDirection.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0
|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_prestation WHERE Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)) AND Id_Personne =".$IdPersonneConnectee))>0
|| ($modeInterim==false && $modeSousTraitant==false && mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) AND Id_Personne =".$IdPersonneConnectee))>0))
{
	$menuBandeau.=TitrePereMenuCliquableV2("TalentBoost","TalentBoost",$_SESSION['Langue'],"Outils/TalentBoost/Tableau_De_Bord.php");
	$menuHamburger.=TitrePereCliquableHamburger("TalentBoost","TalentBoost",$_SESSION['Langue'],"Outils/TalentBoost/Tableau_De_Bord.php");
	
	$menuBandeau.= '</div>';
}*/

//##################
//COMMUNICATION//
//##################
if($modeSousTraitant==false)
{
	$menuBandeau.=TitrePereMenuV2("Communication","Communication",$_SESSION['Langue']);
	$menuHamburger.=TitrePereHamburger("Communication","Communication",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordion-content'>";
	
	$menuBandeau.= '<ul>';
	$menuBandeau.=TitreFilsSlV2("Direction Technique","Direction Technique",$_SESSION['Langue']);
	$menuHamburger.=TitreFilsHamburgerS1("Direction Technique","Direction Technique",$_SESSION['Langue']);
	$menuHamburger.="<ul class='menu-submenu accordionsubmenu-content'>";

	$menuBandeau.=TitreFilsV2("InnoLab-Marketplace Impression 3D","InnoLab-Marketplace Impression 3D",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=InnoLab-MarketplaceImpression3D");
	$menuHamburger.=TitreFilsHamburger("InnoLab-Marketplace Impression 3D","InnoLab-Marketplace Impression 3D",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=InnoLab-MarketplaceImpression3D");
	$menuBandeau.=TitreFilsV2("Innovation","Innovation",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=Innovation");
	$menuHamburger.=TitreFilsHamburger("Innovation","Innovation",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=Innovation");
	$menuBandeau.=TitreFilsV2("Formation interne","Formation interne",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=FormationInterne");
	$menuHamburger.=TitreFilsHamburger("Formation interne","Formation interne",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=FormationInterne");
	$menuBandeau.=TitreFilsV2("Training modules DRAFTS exchange","Training modules DRAFTS exchange",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=TrainingModulesDRAFTSexchange");
	$menuHamburger.=TitreFilsHamburger("Training modules DRAFTS exchange","Training modules DRAFTS exchange",$_SESSION['Langue'],"ListeDocs.php?Page=communication&Dossier1=TrainingModulesDRAFTSexchange");

	$menuHamburger.= '</ul>';
	$menuBandeau.= '</ul>';

	$menuBandeau.= '</div>';
	$menuHamburger.= "</ul>";
}



//##################
//ONBOARDING//
//##################
if(mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne =".$IdPersonneConnectee))>0
|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id FROM onboarding_administrateur WHERE Id_Personne =".$IdPersonneConnectee))>0){
	$menuBandeau.=TitrePereMenuCliquableV2("Espace accueil","Reception area",$_SESSION['Langue'],"Outils/Onboarding/TableauDeBord.php?Menu=1");
	$menuHamburger.=TitrePereCliquableHamburger("Espace accueil","Reception area",$_SESSION['Langue'],"Outils/Onboarding/TableauDeBord.php?Menu=1");
	
	$menuBandeau.= '</div>';
}

?>
<div id="navigation" class="bandeau">
	<!-- hamburger-menu -->
	<div id="menu-container">
	   <div id="menu-wrapper">
		  <div id="hamburger-menu"><span></span><span></span><span></span></div>
	   </div>
	   <!-- menu-wrapper -->
	   <ul class="menu-list accordion">
			<?php
				//Si la personne n'est pas affectée à une prestation alors pas de menu
				//Si sous-traitant alors laisser l'accès pour l'instant
				if(mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))>0 || $modeSousTraitant==true){
					echo $menuHamburger;
				}
			?>
	   </ul>
	</div>
	<!-- fin hamburger-menu -->
	<div class="elementBandeau Titre">
		<a class="a_Accueil" onmouseover="this.style.color='#black';" onmouseout="this.style.color='#black';" href="<?php echo $chemin;?>/<?php if(estInterimPourMenu(date('Y-m-d'),$_SESSION['Id_Personne']) || estSousTraitantPourMenu($IdPersonneConnectee)){echo "AccueilInt.php";}else{echo "Accueil.php";}?>" target="_top">
			<img id="imgAccueil" src="<?php echo $CheminImage;?>/Logos/Logo DaherMonogramme_Neg.png" width="100px" alt="Accueil" title="Accueil">
		</a>
	</div>
	<div class="elementBandeau Menu" id="wrapper">
		<!-- begin nav -->
		<nav>
			<ul id="menu">
			<?php
			//Si la personne n'est pas affectée à une prestation alors pas de menu
			//Si sous-traitant alors laisser l'accès pour l'instant
			if(mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))>0 || $modeSousTraitant==true){
				echo $menuBandeau;
			}
			?>
			</ul>
		</nav><!-- /nav -->
	</div><!-- /wrapper -->
	<div class="elementBandeau Drapeau">
		<?php
			if(estInterimPourMenu(date('Y-m-d'),$_SESSION['Id_Personne']) || estSousTraitantPourMenu($IdPersonneConnectee)){
				echo "<input type='image' id='imageDrapeau' src='".$CheminImage."/".$_SESSION['Langue'].".jpg' onclick='javascript:ChangeLangue(\"".$LangueInverse."\",\"".$chemin."/AccueilInt.php\");'>";
			}
			else{
				echo "<input type='image' id='imageDrapeau' src='".$CheminImage."/".$_SESSION['Langue'].".jpg' onclick='javascript:ChangeLangue(\"".$LangueInverse."\",\"".$chemin."/Accueil.php\");'>";
			}
		?>
	</div>
	<div class="elementBandeau Recherche" id='btnRecherche2' >
		<?php
			if(mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPresta))>0){
		?>
		<img id="imgRecherche" src="<?php echo $CheminImage;?>/loupe2.png" width="23px" class="unBouton" alt="Rechercher" title="Rechercher"><br>
		<?php if($_SESSION["Langue"]=="FR"){echo "Recherche";}else{echo "Search";}
			}
		?>
	</div>
	<div class="elementBandeau Bonjour">
		<table style="width:100%;">
			<tr>
				<td class="Identification" style="color:white;font-weight:bold;" colspan="2">
					<div id="wrapper2">
						<!-- begin nav -->
						<nav>
							<ul id="menuProfil">
								<?php 
									TitrePereMenu($_SESSION['Prenom']." ".$_SESSION['Nom']."&nbsp; &#x2B9F;");
									echo "<ul>";
									
									if($_SESSION['Mdp']<>"aaa01")
									{
										if($_SESSION["Langue"]=="FR"){$intitule="Mes coordonnées";}else{$intitule="My contact details";}
										echo "<li><a href=\"javascript:OuvreFenetreUtilisateur('".$chemin."',".$_SESSION['Id_Personne'].");\">";
										echo "<img src='".$HTTPServeur."Images/Lettre.png' border='0' width='25px'>&nbsp;&nbsp;";
										echo $intitule."</a></li>\n";
										
										if($_SESSION["Langue"]=="FR"){$intitule="Mes compétences";}else{$intitule="My skills";}
										echo "<li><a href=\"javascript:OuvreFenetreLeProfil('".$chemin."',".$_SESSION['Id_Personne'].");\">";
										echo "<img src='".$HTTPServeur."Images/etoile.png' border='0' width='25px'>&nbsp;&nbsp;";
										echo $intitule."</a></li>\n";
									}
									
									//Administration
									if(DroitsPlateforme(array($IdPosteAdministrateur)))
									{
										if($_SESSION["Langue"]=="FR"){$intitule="Gestion des droits et des utilisateurs";}else{$intitule="User and rights management";}
										echo "<li><a href=\"javascript:OuvreFenetreDroits('".$chemin."');\">";
										echo "<img src='".$HTTPServeur."Images/Cadenas.png' border='0' width='25px'>&nbsp;&nbsp;";
										echo $intitule."</a></li>\n";
									}
									
									if($_SESSION["Langue"]=="FR"){$intitule="Se déconnecter";}else{$intitule="Sign out";}
									echo "<li><a style='text-decoration:none;font:12px Calibri;' target='_top' href='".$chemin."/index.php'>";
									echo "<img src='".$HTTPServeur."Images/deconnexion.png' border='0' width='25px'>&nbsp;&nbsp;";
									echo $intitule."</a></li>\n";
										
									echo "</ul>";
								?>
								</li>
							</ul>
						</nav>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="elementBandeau laRecherche" id="laRecherche">
		<label id="LibelleRecherche"><?php if($_SESSION["Langue"]=="FR"){echo "Rechercher";}else{echo "Search";}?></label>
		<input type="text" style="color:black;" id="mots" name="mots" size="40" value="<?php if(isset($_GET['mots'])){echo $_GET['mots'];}?>">
		<a style="text-decoration:none;font:12px Calibri;" id="btnRecherche" class="unBouton" target="_top" onclick="javascript:Rechercher('<?php echo $chemin;?>/Outils/Recherche.php');" ><img src="<?php echo $CheminImage;?>/loupe2.png" width="23px" alt="Rechercher" title="Rechercher"></a>
	</div>
</div><!-- navigation -->

<script>
	if(document.getElementById('mots').value!=''){
		document.getElementById('laRecherche').style.display="";
	}
	else{
		document.getElementById('laRecherche').style.display="none";
	}
</script>