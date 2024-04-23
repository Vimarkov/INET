<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_SMH.php?Menu="+Menu;
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

	$salaire="";
	$req="SELECT Salaire 
		FROM rh_smh 
		WHERE Suppr=0 
		AND Cotation='".$_POST['Cotation']."'  ";
	$resultTAG=mysqli_query($bdd,$req);
	$nbenregTAG=mysqli_num_rows($resultTAG);
	
	$salaire=0;
	if($_POST['Salaire']<>0){$salaire=$_POST['Salaire'];}
	if($nbenregTAG>0)
	{
		$req="UPDATE rh_smh
			SET Salaire=".$salaire." 
			WHERE Suppr=0 
			AND Cotation='".$_POST['Cotation']."' ";
	}
	else{
		$req="INSERT INTO rh_smh (Cotation,Salaire)
		VALUES ('".$_POST['Cotation']."',".$salaire.")";	
	}
	$resultInsertUpdate=mysqli_query($bdd,$req);

	
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif_SMH.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Cotation" value="<?php echo $_GET['Cotation']; ?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table style="width:95%; height:95%; align:center;" class="TableCompetences">
		<?php
			$salaire="";
			$req="SELECT Salaire 
				FROM rh_smh 
				WHERE Suppr=0 
				AND Cotation='".$_GET['Cotation']."' ";
			$resultTAG=mysqli_query($bdd,$req);
			$nbenregTAG=mysqli_num_rows($resultTAG);
			if($nbenregTAG>0)
			{
				$rowTAG=mysqli_fetch_array($resultTAG);
				if($rowTAG['Salaire']>0){$salaire=$rowTAG['Salaire'];}
			}
		?>
		<tr>
			<td class="Libelle" width="25%"><?php if($_SESSION["Langue"]=="FR"){echo "Cotation";}else{echo "Quotation";}?> : </td>
			<td><?php echo $_GET['Cotation']; ?></td>
			<td>
				<input onKeyUp="nombre(this)" name="Salaire" id="Salaire" size="9" value="<?php echo $salaire; ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
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