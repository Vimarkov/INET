<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_CentreCoutPersonne.php?Menu="+Menu;
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
		$req="UPDATE new_rh_etatcivil 
		SET centreDeCout='".$_POST['centreDeCout']."',
		MatriculeDaher='".$_POST['matriculeDaher']."',
		MatriculeDSK='".$_POST['matriculeDSK']."'
		WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
	
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
	$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne,
	CentreDeCout,MatriculeDaher, MatriculeDSK
	FROM new_rh_etatcivil 
	WHERE Id=".$_GET['Id']." ";
	$result=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($result);
?>
	<form id="formulaire" method="POST" action="Modif_CentreCoutPersonne.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
	<table style="width:95%; height:95%; align:center;" class="TableCompetences">
		<tr><td height="4"></td></tr>
		<tr>
			<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
			<td width="10%" class="Libelle">
				<?php echo $row['Personne']; ?>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Centre de coût :";}else{echo "Cost center :";} ?></td>
			<td width="10%">
				<input name="centreDeCout" id="centreDeCout" size="20" value="<?php echo $row['CentreDeCout']; ?>">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule Daher :";}else{echo "Matricule Daher :";} ?></td>
			<td width="10%">
				<input name="matriculeDaher" id="matriculeDaher" size="20" value="<?php echo $row['MatriculeDaher']; ?>">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule DSK :";}else{echo "Matricule DSK :";} ?></td>
			<td width="10%">
				<input name="matriculeDSK" id="matriculeDSK" size="20" value="<?php echo $row['MatriculeDSK']; ?>">
			</td>
		</tr>
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