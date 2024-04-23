<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_PeriodiciteVM.php?Menu="+Menu;
			window.close();
		}
		
		function nombreEntier(champ){
			var chiffres = new RegExp("[0-9]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
			verif = chiffres.test(champ.value.charAt(x));
			if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
			if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
			if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");

if($_POST)
{
	$Periodicite=0;
	$periodiciteAvecSMR=0;
	if($_POST['periodicite']<>""){$Periodicite=$_POST['periodicite'];}
	if($_POST['periodiciteAvecSMR']<>""){$periodiciteAvecSMR=$_POST['periodiciteAvecSMR'];}
	
	$req="UPDATE new_competences_metier 
		SET Periodicite_VM=".$Periodicite." ,
		Periodicite_VM_AvecSMR=".$periodiciteAvecSMR." 
		WHERE Id=".$_POST['Id']." ";

	$resultUpdate=mysqli_query($bdd,$req);
	
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" method="POST" action="Modif_PeriodiciteVM.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table style="width:95%; height:95%; align:center;" class="TableCompetences">
		
		<?php
			if($_SESSION['Langue']=="FR"){
				$req="SELECT Id, 
				Libelle,Periodicite_VM,Periodicite_VM_AvecSMR
				FROM new_competences_metier
				WHERE Id=".$_GET['Id'];
			}
			else{
				$req="SELECT Id, 
				LibelleEN AS Libelle,Periodicite_VM,Periodicite_VM_AvecSMR
				FROM new_competences_metier
				WHERE Id=".$_GET['Id'];
			}
			$result=mysqli_query($bdd,$req);
			$nbenreg=mysqli_num_rows($result);
			$row=mysqli_fetch_array($result);

		?>
		<tr>
			<td class="Libelle" width="25%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";}?> : </td>
			<td><?php echo $row['Libelle']; ?></td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Périodicité sans SMR (en mois)";}else{echo "Frequency without SMR (in months)";}?> : </td>
			<td>
				<input onKeyUp="nombreEntier(this)" name="periodicite" id="periodicite" size="5" value="<?php echo $row['Periodicite_VM']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Périodicité avec SMR (en mois)";}else{echo "Frequency with SMR (in months)";}?> : </td>
			<td>
				<input onKeyUp="nombreEntier(this)" name="periodiciteAvecSMR" id="periodiciteAvecSMR" size="5" value="<?php echo $row['Periodicite_VM_AvecSMR']; ?>" />
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