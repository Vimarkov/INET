<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.libelle.value==''){alert('You didn\'t enter the wording.');return false;}
				if(formulaire.dateDebut.value==''){alert('You didn\'t enter the start date.');return false;}
				if(formulaire.dateFin.value==''){alert('You didn\'t enter the end date.');return false;}
			}
			else{
				if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				if(formulaire.dateDebut.value==''){alert('Vous n\'avez pas renseigné la date de début.');return false;}
				if(formulaire.dateFin.value==''){alert('Vous n\'avez pas renseigné la date de fin.');return false;}
			}
			return true;
		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Fonctions.php");
require("../Connexioni.php");

Ecrire_Code_JS_Init_Date();

if($_POST){
	if($_POST['Mode']=="A"){
		$droit="";
		$requete="INSERT INTO trame_wp (Libelle,DateDebut,DateFin,Id_Prestation,Actif) VALUES ('".addslashes($_POST['libelle'])."','".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',".$_SESSION['Id_PrestationTR'].",".$_POST['actif'].") ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_wp SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Actif=".$_POST['actif'].",";
		$requete.="DateDebut='".TrsfDate_($_POST['dateDebut'])."',";
		$requete.="DateFin='".TrsfDate_($_POST['dateFin'])."'";
		$requete.=" WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Libelle, DateDebut, DateFin, Actif FROM trame_wp WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_WP.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libellé";} ?> </td>
				<td colspan="4">
					<input type="texte" name="libelle" id="libelle" size="80" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date début";} ?> </td>
				<td>
					<input type="date" name="dateDebut" id="dateDebut" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['DateDebut'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date fin";} ?> </td>
				<td>
					<input type="date" name="dateFin" id="dateFin" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['DateFin'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Active";}else{echo "Actif";} ?> </td>
				<td colspan="4">
					<select name="actif" id="actif">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['Actif']=="0"){echo "selected";}}else{echo "selected";} ?>><?php if($_SESSION['Langue']=="EN"){echo "Yes";}else{echo "Oui";} ?></option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['Actif']=="1"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "No";}else{echo "Non";} ?></option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE trame_wp SET ";
		$requete.="Supprime=true ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>