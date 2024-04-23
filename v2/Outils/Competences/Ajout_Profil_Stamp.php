<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Profil personne - Stamp</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST)
{
	if($_POST['Num_Stamp']!="")
	{
		if($_POST['Mode']=="Ajout")
		{
			$requete="INSERT INTO new_competences_personne_stamp (Id_Personne, Num_Stamp, Scope, Lettre,WA_QA, Client, Date_Debut, Date_Fin) VALUES (";
			$requete.=$_POST['Id_Personne'].",";
			$requete.="'".$_POST['Num_Stamp']."',";
			$requete.="'".addslashes($_POST['Scope'])."',";
			$requete.="'".addslashes($_POST['Lettre'])."',";
			$requete.="'".addslashes($_POST['WA_QA'])."',";
			$requete.="'".addslashes($_POST['Client'])."',";
			$requete.="'".TrsfDate($_POST['Date_Debut'])."',";
			$requete.="'".TrsfDate($_POST['Date_Fin'])."'";
			$requete.=")";
		}
		else
		{
			$requete="UPDATE new_competences_personne_stamp SET ";
			$requete.="Num_Stamp='".$_POST['Num_Stamp']."', ";
			$requete.="Scope='".addslashes($_POST['Scope'])."', ";
			$requete.="Lettre='".addslashes($_POST['Lettre'])."', ";
			$requete.="WA_QA='".addslashes($_POST['WA_QA'])."', ";
			$requete.="Client='".addslashes($_POST['Client'])."', ";
			$requete.="Date_Debut='".TrsfDate($_POST['Date_Debut'])."', ";
			$requete.="Date_Fin='".TrsfDate($_POST['Date_Fin'])."' ";
			$requete.="WHERE Id=".$_POST['Id'];
		}
		$result=mysqli_query($bdd,$requete);
	}
	//echo $requete;
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Mode']=="Modif")
		{
			$result_Stamp=mysqli_query($bdd,"SELECT * FROM new_competences_personne_stamp WHERE Id=".$_GET['Id']);
			$Ligne_Stamp=mysqli_fetch_array($result_Stamp);
		}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Ajout_Profil_Stamp.php" class="None">
	<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $_GET['Id'];} ?>">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td>Mark N° : </td>
			<td>
				<input name="Num_Stamp" size="20" value="<?php if($_GET['Mode']=="Modif"){echo $Ligne_Stamp['Num_Stamp'];} ?>">
			</td>
			<td>Scope of application :</td>
			<td>
				<input name="Scope" size="50" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($Ligne_Stamp['Scope']);} ?>">
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Client";}else{echo "Customer";}?> : </td>
			<td>
				<input name="Client" size="20" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($Ligne_Stamp['Client']);} ?>">
			</td>
			<td>Work specification N° :</td>
			<td>
				<input name="Lettre" size="50" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($Ligne_Stamp['Lettre']);} ?>">
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>WA/QA iaw commitment letter :</td>
			<td colspan="3">
				<input name="WA_QA" size="50" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($Ligne_Stamp['WA_QA']);} ?>">
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> :</td>
			<td>
				<input type="date" name="Date_Debut" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($Ligne_Stamp['Date_Debut']);} ?>">
			</td>
			<td><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> :</td>
			<td>
				<input type="date" name="Date_Fin" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($Ligne_Stamp['Date_Fin']);} ?>">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input class="Bouton" type="submit"
				<?php
					if($_GET['Mode']=="Modif"){
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					}
					else{
						if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
					}
				?>
			></td>
		</tr>
	</table>
	</form>
<?php
	}
	if($_GET['Mode']=="Modif"){mysqli_free_result($result_Stamp);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>