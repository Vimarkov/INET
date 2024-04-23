<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<!-- Feuille de style -->
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script>
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_InformationsPersonnel.php?Menu="+Menu;
			window.close();
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->	
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
require("../Connexioni.php");
require("../Fonctions.php");

if(isset($_POST['submitValider'])){
	//Mise à jour de l'enregistrement new_planning_personne_vacationabsence
	$requete="UPDATE new_rh_etatcivil SET ";
	$requete.="TelephoneProMobil='".$_POST['TelephoneProMobil']."', ";
	$requete.="TelephoneProFixe='".$_POST['TelephoneProFixe']."', ";
	$requete.="EmailPro='".$_POST['EmailPro']."', ";
	$requete.="NumBadge='".$_POST['NumBadge']."', ";
	$requete.="Matricule='".$_POST['Matricule']."', ";
	$requete.="TelephoneMobil='".$_POST['TelephoneMobil']."' ";
	$requete.=" WHERE Id='".$_POST['Id_Personne']."'";
	$result=mysqli_query($bdd,$requete);

	$resultUpdate=mysqli_query($bdd,$requeteUpdate);
	//Fermeture de la fenêtre et rechargement
	echo "<script>FermerEtRecharger('".$_POST['Menu']."');</script>";
 }
 
$Menu=$_GET['Menu']; 

//Personnes  présentent sur cette prestation à ces dates
$req = "SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne, ";
$req .= "(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = new_competences_personne_metier.Id_Metier) AS Metier, ";
$req .= "new_rh_etatcivil.TelephoneProFixe, ";
$req .= "new_rh_etatcivil.TelephoneProMobil, ";
$req .= "new_rh_etatcivil.EmailPro, ";
$req .= "new_rh_etatcivil.NumBadge, ";
$req .= "new_rh_etatcivil.Matricule, ";
$req .= "new_rh_etatcivil.Date_Naissance ";
$req .= "FROM (new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne) ";
$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
$req .= "WHERE new_rh_etatcivil.Id=".$_GET['Id_Personne'].";";

$resultPersonne=mysqli_query($bdd,$req);
$nbPersonne=mysqli_num_rows($resultPersonne);
?>

</script>
	<table class="TableCompetences" width="100%">
		 <form id="formulaire" method="post" action="Modif_InfosPersonnel.php">
			<tr style="display:none;">
				<td><input type="text" name="Menu" size="11" value="<?php echo $Menu; ?>"></td>
				<td><input type="text" name="Id_Personne" size="11" value="<?php echo $_GET['Id_Personne']; ?>"></td>
			</tr>
			<?php
			if ($nbPersonne > 0){
				$row=mysqli_fetch_array($resultPersonne);
			?>
			<tr>
				<td><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?> :</td>
				<td>
					<?php echo $row['Personne']; ?>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td><?php if($_SESSION["Langue"]=="FR"){echo "Tel. pro fixe";}else{echo "Fixed business phone";} ?> : </td>
				<td><input name="TelephoneProFixe" size="20" value="<?php echo $row['TelephoneProFixe'];?>"></td>
				<td><?php if($_SESSION["Langue"]=="FR"){echo "Tel. pro mobile";}else{echo "Mobile business phone";} ?> : </td>
				<td><input name="TelephoneProMobil" size="20" value="<?php echo $row['TelephoneProMobil'];?>"></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td><?php if($_SESSION["Langue"]=="FR"){echo "Email pro";}else{echo "Email";} ?> : </td>
				<td><input name="EmailPro" size="20" value="<?php echo $row['EmailPro'];?>"></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td><?php if($_SESSION["Langue"]=="FR"){echo "N° badge";}else{echo "Badge number";} ?> : </td>
				<td><input name="NumBadge" size="20" value="<?php echo $row['NumBadge'];?>"></td>
				<td><?php if($_SESSION["Langue"]=="FR"){echo "NG/ST";}else{echo "NG/ST";} ?> : </td>
				<td><input name="Matricule" size="20" value="<?php echo $row['Matricule'];?>"></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" name="submitValider" type="submit" value='Valider'>
				</td>
			</tr>
			<?php
			}
			?>
		</form>
	</table>

</body>
</html>