<html>
<head>
	<title>Compétences - Catégorie Diplôme</title><meta name="robots" content="noindex">
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
		$result=mysqli_query($bdd,"SELECT Id FROM moduleformation_categorie WHERE Suppr=0 AND Libelle='".$_POST['Libelle']."'");
		if(mysqli_num_rows($result)==0)
		{
			$requete="SELECT Id FROM moduleformation_categorie WHERE Suppr=0";
			$resultOrdre=mysqli_query($bdd,$requete);
			$nbResulta=mysqli_num_rows($resultOrdre);
			$ordre=$nbResulta+1;
			
			$result=mysqli_query($bdd,"INSERT INTO moduleformation_categorie (Libelle,Ordre) VALUES ('".$_POST['Libelle']."',".$ordre.")");
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT Id FROM moduleformation_categorie WHERE Suppr=0 AND Libelle='".$_POST['Libelle']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE moduleformation_categorie SET Libelle='".$_POST['Libelle']."' WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id,Libelle FROM moduleformation_categorie WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_categorie_Formation.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle">Libellé : </td>
				<td><input name="Libelle" size="70" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
				<td><input class="Bouton" type="submit"
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
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT Id FROM moduleformation_formation WHERE Id_Categorie=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE moduleformation_categorie SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer cette catégorie car une ou plusieurs formations y sont attachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>