<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function FermerEtRecharger(Menu,TDB,OngletTDB)
		{
			window.opener.location="Liste_Contrat18Mois.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
	</script>
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
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){

	$req="UPDATE new_rh_etatcivil 
		SET DateDebut18Mois='".TrsfDate_($_POST['dateDebutContrat18Mois'])."'
		WHERE Id=".$_POST['personne']."
		";

	$resultModif=mysqli_query($bdd,$req);
	echo "<script>FermerEtRecharger(".$_POST['Menu'].",".$_POST['TDB'].",'".$_POST['OngletTDB']."');</script>";
}
$Menu=$_GET['Menu'];

$requete="SELECT Id,DateDebut18Mois, CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil
	WHERE Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";

?>

<form id="formulaire" class="test" action="Modif_Date18Mois.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $_GET['TDB']; ?>" />
	<input type="hidden" name="personne" id="personne" value="<?php echo $row['Id']; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $_GET['OngletTDB']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
							<td width="15%">
								<?php echo $row['Personne']; ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début des 18 mois :";}else{echo "Start date of 18 months :";} ?></td>
							<td width="15%">
								<input type="date" style="text-align:center;" id="dateDebutContrat18Mois" name="dateDebutContrat18Mois" size="10" value="<?php echo AfficheDateFR($row['DateDebut18Mois']); ?>">
							</td>
						</tr>
						<tr>
							<td colspan='6' align='center'>
								<input class='Bouton' type='submit' name='ModifierType' value="<?php if($_SESSION["Langue"]=="FR"){echo "Modifier";}else{echo "Modify";} ?>" />
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>