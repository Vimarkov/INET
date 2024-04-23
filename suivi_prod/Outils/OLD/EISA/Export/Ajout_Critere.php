<!DOCTYPE html>
<html>
<head>
	<title>TraME AAA</title><meta name="robots" content="noindex">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
    <script src="../../JS/jquery-1.11.1.min.js"></script>
    <script src="../../JS/mask.js"></script>
	<script>
		function Recharger(){
			opener.location="Liste_Reporting.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Reporting.php";
			window.close();
		}
	</script>
</head>

<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if(strlen($_POST['mois'])==7  && strpos($_SESSION['EXTRACT_Mois'],$_POST['mois'].";")===false){
		$_SESSION['EXTRACT_Mois']=$_POST['mois'];
	}
	echo "<script>Recharger();</script>";
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_Critere.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Ajouter des critères</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">&nbsp;<?php echo "Mois mm/aaaa"; ?></td>
			<td>
				<input id="mois" name="mois">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php echo "Ajouter"; ?>">
			</td>
			
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
</form>
</table>
<script>
	var options;
	var masks = [];
	var mask;
	var setErrorFunction = function(isValid, input){
		if(isValid == false){
			input.parent().css("border", "1px solid red");
		}else{
			input.parent().css("border", "1px solid green");
		}
	} 
	mask = Mask.newMask(
	{
		$el: $("#mois"),
		mask: "MM/YYYY",
		errorFunction: setErrorFunction,
		isUtc: true
	}
	);
	masks.push(mask); 
</script>
</body>
</html>