<html>
<head>
	<title>SQCDPF - Secteurs</title><meta name="robots" content="noindex">
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
session_start();	//require("../VerifPage.php");
require("../Connexioni.php");

if($_POST)
{
	$result=mysqli_query($bdd,"UPDATE new_competences_prestation SET Id_Secteur='".$_POST['secteur']."' WHERE Id=".$_POST['Id']);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$req = "SELECT Libelle, Id_Secteur FROM new_competences_prestation WHERE new_competences_prestation.Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($result);
?>
	<form id="formulaire" method="POST" action="Modif_SecteurPresta.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
	<table width="95%" height="95%" align="center">
		<tr class="TitreColsUsers">
			<td>Prestation : </td>
			<td><?php echo $row['Libelle'];?></td>
		</tr>
		<tr class="TitreColsUsers">
			<td>Secteur : </td>
			<td>
				<select name="secteur">
				<?php
					$req= "SELECT Id, Libelle FROM new_secteur WHERE Id_Plateforme=1 ORDER BY Libelle";
					$result=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result);
					if($nbenreg>0){
						echo "<option selected></option>";
						while($rowSecteur=mysqli_fetch_array($result)){
							$selected = "";
							if($row['Id_Secteur'] == $rowSecteur['Id']){
								$selected = "selected";
							}
							echo "<option value='".$rowSecteur['Id']."' name='".$rowSecteur['Id']."' ".$selected.">".$rowSecteur['Libelle']."</option>";
						}
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input class="Bouton" type="submit" value="Valider"></td>
		</tr>
	</table>
	</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>