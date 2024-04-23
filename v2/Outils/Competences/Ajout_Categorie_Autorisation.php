<html>
<head>
	<title>Compétences - Catégorie autorisation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
			
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
		$requeteVerificationExiste="SELECT Id FROM new_competences_moyen_categorie WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Moyen=".$_POST['Id_Moyen']." AND Suppr=0";
		$requeteInsertUpdate="INSERT INTO new_competences_moyen_categorie (Id_Moyen, Libelle)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.=$_POST['Id_Moyen'];
		$requeteInsertUpdate.=",'".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=")";
	}
	else
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_moyen_categorie WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Moyen=".$_POST['Id_Moyen']." AND Suppr=0 AND Id!=".$_POST['Id'];
		$requeteInsertUpdate="UPDATE new_competences_moyen_categorie SET";
		$requeteInsertUpdate.=" Id_Moyen=".$_POST['Id_Moyen'];
		$requeteInsertUpdate.=", Libelle='".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	
	$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
	if(mysqli_num_rows($resultVerificationExiste)==0)
	{
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Cette catégorie d'autorisation existe déjà pour ce moyen.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$Modif=true;
			$result=mysqli_query($bdd,"SELECT Id, Id_Moyen, Libelle FROM new_competences_moyen_categorie WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Categorie_Autorisation.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Moyen";}else{echo "Mean";}?> : </td>
				<td>
					<select name="Id_Moyen">
						<?php
						$resultMoyen=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_moyen WHERE Suppr=0 ORDER BY Libelle ASC");
						while($rowMoyen=mysqli_fetch_array($resultMoyen))
						{
							echo "<option value='".$rowMoyen['Id']."'";
							if($Modif){if($rowMoyen['Id']==$row['Id_Moyen']){echo " selected";}}
							echo ">".stripslashes($rowMoyen['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="20" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif){
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
		$result=mysqli_query($bdd,"UPDATE new_competences_moyen_categorie SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>