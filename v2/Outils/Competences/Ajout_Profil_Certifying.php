<html>
<head>
	<title>Compétences - Profil personne - Fonction</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
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
require_once("../Fonctions.php");

if($_POST)
{
	$req="UPDATE new_rh_etatcivil 
		SET CertifyingStaffNumber='".addslashes($_POST['certifyingStaffNumber'])."',
		CertifyingStaffPrecision='".addslashes($_POST['precision'])."'
		WHERE Id=".$_POST['Id_Personne'];
	$result=mysqli_query($bdd,$req);
	
	$result2=mysqli_query($bdd,"DELETE FROM new_competences_personne_certifying WHERE Id_Personne=".$_POST['Id_Personne']);
	$Tableau=array
			(
				"Intervention_Card",
				"QSR",
				"TLB",
				"ARC",
				"CoC",
				"Other"
			);
	foreach($Tableau as $indice => $valeur)
	{
		if(isset($_POST['autorisation_'.$valeur])){
			$req="INSERT INTO new_competences_personne_certifying (Id_Personne,AutorisationSign) VALUES (".$_POST['Id_Personne'].",'".$_POST['autorisation_'.$valeur]."')";
			$result2=mysqli_query($bdd,$req);
		}
	}
	
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	$result=mysqli_query($bdd,"SELECT CertifyingStaffNumber,CertifyingStaffPrecision FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_GET['Id_Personne']);
	$row=mysqli_fetch_array($result);
	$CertifyingStaffNumber=$row['CertifyingStaffNumber'];
	$CertifyingStaffPrecision=$row['CertifyingStaffPrecision'];

	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout")
	{
?>
	<form id="formulaire" method="POST" action="Ajout_Profil_Certifying.php" class="None">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences" width="100%">
		<tr class="TitreColsUsers">
			<td class="Libelle">Certifying Staff number : 
			</td>
			<td>
				<input name="certifyingStaffNumber" value="<?php echo stripslashes($CertifyingStaffNumber);?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<i>enter the number if  intervener is an authorized signatory granted the EASA privilege(s) to sign (any of below):<br>
			- EASA Form 1 (Authorized Release Certificate) / <br>
			- EASA Form 52 (Aircraft Statement of Conformity) / <br>
			- EASA Form 53 (Certificate of Release into Service) and/or <br>
			- EASA Form 20b (Permit to Fly) or Statement of Ability of Safe Flight at Tianjin </i>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">Authorization to sign : </td>
			<td>
			<?php 
				$Tableau=array
						(
							"Intervention_Card",
							"QSR",
							"TLB",
							"ARC",
							"CoC",
							"Other"
						);
				foreach($Tableau as $indice => $valeur)
				{
					$checked="";
					$req="SELECT Id FROM new_competences_personne_certifying 
							WHERE AutorisationSign='".$valeur."'
							AND Id_Personne=".$_GET['Id_Personne']." ";
					$resultParam=mysqli_query($bdd,$req);
					$nbParam=mysqli_num_rows($resultParam);
					if($nbParam>0)
					{
						$checked="checked";
					}
					echo "<input type='checkbox' ".$checked." name='autorisation_".str_replace("_"," ",$valeur)."' value='".$valeur."' />".$valeur."";
				}
			?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">If other - precision : </td>
			<td>
				<input name="precision" style="width:500px;" value="<?php echo stripslashes($CertifyingStaffPrecision);?>" />
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center"><input class="Bouton" type="submit"
				<?php
					if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
				?>
			></td>
		</tr>
	</table>
	</form>
<?php
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>