<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Production.js?t=<?php echo time();?>"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Production.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();

$req="SELECT Id, Id_Tache,Statut,Id_Preparateur,Id_WP, ";
$req.="(SELECT Delais FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Delais, ";
$req.="(SELECT CritereOTD FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS CritereOTD, Attestation ";
$req.="FROM trame_travaileffectue WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$req);
$Ligne=mysqli_fetch_array($result);

$Id_CL=0;
$Niveau=0;
$Delais=0;

//Récupérer la CL de la tâche + niveau
$req="SELECT Id_CL, NiveauControle,Delais FROM trame_tache WHERE Id=".$Ligne['Id_Tache'];
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if ($nbResulta>0){
	$row=mysqli_fetch_array($result);
	$Id_CL=$row['Id_CL'];
	$Niveau=$row['NiveauControle'];
}

$Id_CLVersion=0;
//Recherche de la version du CL
$req="SELECT Id FROM trame_cl_version WHERE Id_CL=".$Id_CL." AND Valide=1 AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if ($nbResulta>0){
	$row=mysqli_fetch_array($result);
	$Id_CLVersion=$row['Id'];
}

//Recherche le contenu de la version
$req="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Id_CLVersion;
$resultContenuVersion=mysqli_query($bdd,$req);
$nbResultaContenuVersion=mysqli_num_rows($resultContenuVersion);
if($Id_CL>0 && $Id_CLVersion>0 && $nbResultaContenuVersion>0){
	//Remplacer le statut pour AUTO-CONTOLE
	$req="UPDATE trame_travaileffectue SET Statut='AC' WHERE Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);

	//Ajouter une ligne dans trame_controlecroise
	$req="INSERT INTO trame_controlecroise (Id_TravailEffectue,Id_CLVersion,Id_Prestation,Id_Preparateur,NiveauControle,DateCreation) ";
	$req.="VALUES(".$_GET['Id'].",".$Id_CLVersion.",".$_SESSION['Id_PrestationTR'].",".$Ligne['Id_Preparateur'].",".$Niveau.",'".date("Y-m-d")."') ";
	$result=mysqli_query($bdd,$req);
}
echo "<script>FermerEtRecharger();</script>";
?>
	
</body>
</html>