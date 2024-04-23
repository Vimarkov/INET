<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_TAG.php?Menu="+Menu;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");

if($_POST)
{
		$req="SELECT Id 
		FROM rh_classificationmetier
		WHERE Suppr=0";

		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($row=mysqli_fetch_array($result))
			{
				$salaire="";
				$req="SELECT Salaire 
					FROM rh_tag 
					WHERE Suppr=0 
					AND Niveau='".$_POST['Niveau']."' 
					AND Echelon='".$_POST['Echelon']."' 
					AND Coeff='".$_POST['Coeff']."' 
					AND Id_ClassificationMetier=".$row['Id']." ";
				$resultTAG=mysqli_query($bdd,$req);
				$nbenregTAG=mysqli_num_rows($resultTAG);
				
				$salaire=0;
				if($_POST['Salaire_'.$row['Id']]<>0){$salaire=$_POST['Salaire_'.$row['Id']];}
				if($nbenregTAG>0)
				{
					$req="UPDATE rh_tag 
						SET Salaire=".$salaire." 
						WHERE Suppr=0 
						AND Niveau='".$_POST['Niveau']."' 
						AND Echelon='".$_POST['Echelon']."' 
						AND Coeff='".$_POST['Coeff']."' 
						AND Id_ClassificationMetier=".$row['Id']." ";
				}
				else{
					$req="INSERT INTO rh_tag (Niveau,Echelon,Coeff,Id_ClassificationMetier,Salaire)
					VALUES ('".$_POST['Niveau']."','".$_POST['Echelon']."','".$_POST['Coeff']."',".$row['Id'].",".$salaire.")";	
				}
				$resultInsertUpdate=mysqli_query($bdd,$req);
			}
		}
	
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif_TAG.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Niveau" value="<?php echo $_GET['Niveau']; ?>">
	<input type="hidden" name="Echelon" value="<?php echo $_GET['Echelon']; ?>">
	<input type="hidden" name="Coeff" value="<?php echo $_GET['Coeff']; ?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table style="width:95%; height:95%; align:center;" class="TableCompetences">
		<tr>
			<td class="Libelle" width="25%"><?php if($_SESSION["Langue"]=="FR"){echo "Niveau / Position";}else{echo "Level / Position";}?> : </td>
			<td><?php echo $_GET['Niveau']; ?></td>
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Coeff";}else{echo "Coefficient";}?> : </td>
			<td><?php echo $_GET['Coeff']; ?></td>
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Echelon";}else{echo "Echelon";}?> : </td>
			<td><?php echo $_GET['Echelon']; ?></td>
		</tr>
		<?php
			if($_SESSION['Langue']=="FR"){
				$req="SELECT Id, 
				Libelle
				FROM rh_classificationmetier
				WHERE Suppr=0
				ORDER BY Libelle ";
			}
			else{
				$req="SELECT Id, 
				LibelleEN AS Libelle
				FROM rh_classificationmetier
				WHERE Suppr=0
				ORDER BY LibelleEN ";
			}
			$result=mysqli_query($bdd,$req);
			$nbenreg=mysqli_num_rows($result);
			if($nbenreg>0)
			{
				while($row=mysqli_fetch_array($result))
				{
					$salaire="";
					$req="SELECT Salaire 
						FROM rh_tag 
						WHERE Suppr=0 
						AND Niveau='".$_GET['Niveau']."' 
						AND Echelon='".$_GET['Echelon']."' 
						AND Coeff='".$_GET['Coeff']."' 
						AND Id_ClassificationMetier=".$row['Id']." ";
					$resultTAG=mysqli_query($bdd,$req);
					$nbenregTAG=mysqli_num_rows($resultTAG);
					if($nbenregTAG>0)
					{
						$rowTAG=mysqli_fetch_array($resultTAG);
						if($rowTAG['Salaire']>0){$salaire=$rowTAG['Salaire'];}
					}
		?>
		<tr>
			<td class="Libelle"><?php echo $row['Libelle']; ?> : </td>
			<td colspan="3">
				<input onKeyUp="nombre(this)" name="Salaire_<?php echo $row['Id'];?>" id="Salaire_<?php echo $row['Id'];?>" size="9" value="<?php echo $salaire; ?>" />
			</td>
		</tr>
		<?php
				}
			}
		?>
		<tr>
			<td colspan="6" align="center">
				<input class="Bouton" type="submit" 
				<?php
					if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
				?>
				/>
			</td>
		</tr>
	</table>
	</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>