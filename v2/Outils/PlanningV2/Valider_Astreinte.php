<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeHS.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function FermerEtRecharger(Menu,TDB,OngletTDB)
		{
			window.opener.location="Liste_DemandeAstreinte.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require_once("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;

$requete="SELECT Id_Prestation,Id_Pole
	FROM rh_personne_rapportastreinte
	WHERE rh_personne_rapportastreinte.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

for($j=$_GET['Step'];$j<=2;$j++){
	if($j==1){
		if(DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
			$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
					Id_ValidateurN1=".$_SESSION['Id_Personne'].",
					DateValidationN1='".date('Y-m-d')."',
					EtatN1=1
					WHERE Id=".$_GET['Id']." ";
			$resultat=mysqli_query($bdd,$requeteUpdate);
		}
		else{$j=5;}
	}
	if($j==2){
		if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
			$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
					Id_ValidateurN2=".$_SESSION['Id_Personne'].",
					DateValidationN2='".date('Y-m-d')."',
					EtatN2=1
					WHERE Id=".$_GET['Id']." ";
			$resultat=mysqli_query($bdd,$requeteUpdate);
		}
		else{$j=3;}
	}
}
echo "<script>FermerEtRecharger('".$_GET['Menu']."','".$_GET['TDB']."','".$_GET['OngletTDB']."');</script>";

?>
</body>
</html>