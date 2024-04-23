<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.personne.value=='0'){alert('You didn\'t enter the user.');return false;}
			}
			else{
				if(formulaire.personne.value=='0'){alert('Vous n\'avez pas renseign� la personne.');return false;}
			}
			return true;

		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php

session_start();
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$req="INSERT INTO onboarding_administrateur (Id_Personne,Rubrique) VALUES (".$_POST['personne'].",'".$_POST['rubrique']."')";
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A"){
?>

		<form id="formulaire" method="POST" action="Ajout_Administrateur.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Personne" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "User";}else{echo "Personne";} ?></td>
				<td>
					<select id="personne" name="personne">
					<?php
					if($_GET['Mode']=="A"){
						echo"<option name='0' value='0'></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Nom']." ".$row['Prenom']."</option>";
							}
						}
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Th�me";} ?></td>
				<td>
					<select id="rubrique" name="rubrique">
						<option value="Achats">Achats</option>
						<option value="Bienvenue chez AAA">Bienvenue chez AAA</option>
						<option value="Excellence op�rationnelle">Excellence op�rationnelle</option>
						<option value="Formation interne">Formation interne</option>
						<option value="Informatique">Informatique</option>
						<option value="Innovation">Innovation</option>
						<option value="Qualit�">Qualit�</option>
						<option value="Ressources humaines">Ressources humaines</option>
						<option value="S�curit� et environnement">S�curit� et environnement</option>
						<option value="Vie quotidienne">Vie quotidienne</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression
	{
		$req="DELETE FROM onboarding_administrateur WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>