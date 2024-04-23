<html>
<head>
	<title>Compétences - Qualification - Moyen</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger()
		{
			opener.location.reload();
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
	if($_POST['Mode']=="Ajout")
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification_moyen WHERE Id_Qualification='".$_POST['Id_Qualification']."' AND Id_Moyen_Categorie=".$_POST['Id_Moyen_Categorie']." AND Suppr=0";
		$requeteInsertUpdate="INSERT INTO new_competences_qualification_moyen (Id_Qualification, Id_Moyen_Categorie)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.=$_POST['Id_Qualification'];
		$requeteInsertUpdate.=",".$_POST['Id_Moyen_Categorie'];
		$requeteInsertUpdate.=")";
	}
	elseif($_POST['Mode']=="Modif")
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification_moyen WHERE Id_Qualification='".$_POST['Id_Qualification']."' AND Id_Moyen_Categorie=".$_POST['Id_Moyen_Categorie']." AND Suppr=0 AND Id!=".$_POST['Id'];
		$requeteInsertUpdate="UPDATE new_competences_qualification_moyen";
		$requeteInsertUpdate.=" WHERE ";
		$requeteInsertUpdate.=" Id_Qualification=".$_POST['Id_Qualification'];
		$requeteInsertUpdate.=",Id_Moyen_Categorie=".$_POST['Id_Moyen_Categorie'];
	}
	$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
	if(mysqli_num_rows($resultVerificationExiste)==0)
	{
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Ce moyen est déjà spécifié pour cette qualification.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		$Modif=false;
		if($_GET['Mode']=="Modif"){$Modif=true;}
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Id_Qualification, Id_Moyen_Categorie FROM new_competences_qualification_moyen WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Qualification_Moyen.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" name="Id_Qualification" value="<?php echo $_GET['Id_Qualification'];?>">
		<table style="width:95%; height:95%; align:center;">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Moyen et catégorie d'autorisation";}else{echo "Mean and authorization category";}?> : </td>
				<td>
					<select name="Id_Moyen_Categorie">
					<?php
					$resultMoyen=mysqli_query($bdd,"SELECT Id, Id_Moyen, Libelle, (SELECT Libelle FROM new_competences_moyen WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) AS Moyen FROM new_competences_moyen_categorie WHERE Suppr=0 ORDER BY Moyen ASC, Libelle ASC");
					while($rowMoyen=mysqli_fetch_array($resultMoyen))
					{
						echo "<option value='".$rowMoyen['Id']."'";
						if($Modif){if($row['Id_Moyen_Categorie']==$rowMoyen['Id']){echo " selected";}}
						echo ">".$rowMoyen['Moyen']." - ".$rowMoyen['Libelle']."</option>";							 
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<input class="Bouton" type="submit"
						<?php
							if($_GET['Mode']=="Modif"){
								if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
							}
							else{
								if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
							}
						?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE new_competences_qualification_moyen SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";

	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>