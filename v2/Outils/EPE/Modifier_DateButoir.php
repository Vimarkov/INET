<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Production.js"></script>
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
		function FermerEtRecharger()
		{
			window.opener.document.getElementById('formulaire').submit();
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	$tab=explode(";",$_POST['Id']);
	foreach($tab as $Id){
		//Vérifier si existe
		$req="SELECT Id FROM epe_personne_datebutoir WHERE Id_Personne=".$Id." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."'";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		
		$Req="DELETE FROM epe_personne_na WHERE Id_Personne=".$Id." AND Annee = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."'";
		$Result=mysqli_query($bdd,$Req);
				
		if($nbResulta>0){
			if(TrsfDate_($_POST['dateButoir'])>"0001-01-01"){
				$Req="UPDATE epe_personne_datebutoir SET DateReport='".TrsfDate_($_POST['dateButoir'])."' WHERE Id_Personne=".$Id." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."'";
				$Result=mysqli_query($bdd,$Req);
			}
			else{
				$Req="DELETE FROM epe_personne_datebutoir WHERE Id_Personne=".$Id." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."'";
				$Result=mysqli_query($bdd,$Req);
				
				if(isset($_POST['NA'])){
					$Req="INSERT INTO epe_personne_na (Id_Personne,DateCreation,Id_Createur,TypeEntretien,Annee,Id_MotifNonRealisation) VALUES (".$Id.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".$_SESSION['FiltreEPEDateButoir_TypeEPE']."',".$_SESSION['FiltreEPEDateButoir_Annee'].",".$_POST['Motif'].")";
					$Result=mysqli_query($bdd,$Req);
				}
			}
		}
		else{
			if(TrsfDate_($_POST['dateButoir'])>"0001-01-01"){
				$Req="INSERT INTO epe_personne_datebutoir (Id_Personne,DateCreation,Id_Createur,TypeEntretien,DateButoir) VALUES (".$Id.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".$_SESSION['FiltreEPEDateButoir_TypeEPE']."','".TrsfDate_($_POST['dateButoir'])."')";
				$Result=mysqli_query($bdd,$Req);
			}
			else{
				if(isset($_POST['NA'])){
					$Req="INSERT INTO epe_personne_na (Id_Personne,DateCreation,Id_Createur,TypeEntretien,Annee,Id_MotifNonRealisation) VALUES (".$Id.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".$_SESSION['FiltreEPEDateButoir_TypeEPE']."',".$_SESSION['FiltreEPEDateButoir_Annee'].",".$_POST['Motif'].")";
					$Result=mysqli_query($bdd,$Req);
				}
			}
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
?>
<form id="formulaire" method="POST" action="Modifier_DateButoir.php" onSubmit="return VerifChamps('<?php echo $LangueAffichage ?>');">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td><input type="hidden" id="Id" name="Id" value="<?php echo $_SESSION['EPE_DateButoir']; ?>" /></td>
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($LangueAffichage=="FR"){echo "Date butoir";}else{echo "Deadline";}?> :
				<input type="date" style="text-align:center;width:130px;" id="dateButoir" name="dateButoir" value="">
			</td>
				
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($_SESSION["Langue"]=="FR"){echo "N/A";}else{echo "N/A";} ?> : <input type="checkbox" id="NA" name="NA" value="NA"> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($_SESSION["Langue"]=="FR"){echo "Motif de non réalisation";}else{echo "Reason for non-achievement ";} ?> : 
				<?php 
				$req="SELECT Id, Libelle FROM epe_motifnonrealisation WHERE Suppr=0 ORDER BY Libelle";
				$resultM=mysqli_query($bdd,$req);
				$nb=mysqli_num_rows($resultM);
				
				echo "<select style='width:100px;' id='Motif' name='Motif' >";
				echo "<option name='0' value='0' selected></option>";
				if ($nb > 0)
				{
					$resultM=mysqli_query($bdd,$req);
					while($rowM=mysqli_fetch_array($resultM))
					{
						echo "<option value='".$rowM['Id']."'>".stripslashes($rowM['Libelle'])."</option>\n";
					}
				 }
				 echo "</select>";
				?>
				&nbsp;&nbsp;
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Modifier'";}else{echo "value='Edit'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php

mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
