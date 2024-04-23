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
				if(formulaire.personne.value=='0'){alert('Vous n\'avez pas renseigné la personne.');return false;}
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
		$req="INSERT INTO soda_surveillant (Id_Personne) VALUES (".$_POST['personne'].")";
		$result=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		
		foreach($_POST['Theme'] as $value){
			$req="INSERT INTO soda_surveillant_theme (Id_Surveillant,Id_Theme) VALUES (".$_POST['personne'].",".$value.")";
			$result=mysqli_query($bdd,$req);
		}
		
	}
	elseif($_POST['Mode']=="Modif"){
		$req="DELETE FROM soda_surveillant_theme WHERE Id_Surveillant=".$_POST['Id_Personne']." ";
		$result=mysqli_query($bdd,$req);
		
		foreach($_POST['Theme'] as $value){
			$req="INSERT INTO soda_surveillant_theme (Id_Surveillant,Id_Theme) VALUES (".$_POST['Id_Personne'].",".$value.")";
			$result=mysqli_query($bdd,$req);
		}
		
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="Modif"){
?>

		<form id="formulaire" method="POST" action="Ajout_Surveillant.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Personne" value="<?php if($_GET['Mode']=="Modif"){echo $_GET['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "User";}else{echo "Personne";} ?></td>
				<td>
					<?php
					if($_GET['Mode']=="A"){
						echo '<select id="personne" name="personne">';
					
						echo"<option name='0' value='0'></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE Id NOT IN (SELECT Id_Personne FROM soda_surveillant) ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Nom']." ".$row['Prenom']."</option>";
							}
						}
					
						echo "</select>";
					}
					elseif($_GET['Mode']=="Modif"){
						$req="SELECT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id']." ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							$row=mysqli_fetch_array($result);
							echo $row['Nom']." ".$row['Prenom'];
						}
					}
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Themes";}else{echo "Thèmes";} ?></td>
			</tr>
			<tr>
				<td colspan="2">
					<div id='Div_Theme' style='height:300px;width:300px;overflow:auto;'>
					<table>
						<?php
							$req = "SELECT Id, Libelle
									FROM soda_theme
									WHERE Suppr=0
									ORDER BY soda_theme.Libelle;";
							$resultTheme=mysqli_query($bdd,$req);
							$nbTheme=mysqli_num_rows($resultTheme);
							
							if ($nbTheme > 0){
								while($row=mysqli_fetch_array($resultTheme)){
									$cadenas="";
									$checked="";
									$disabled="";
									if($_GET['Mode']=="Modif"){
										$req="SELECT Id 
											FROM soda_surveillant_theme 
											WHERE Id_Surveillant=".$_GET['Id']."
											AND Id_Theme=".$row['Id']." ";
										$resultSurveillable=mysqli_query($bdd,$req);
										$nbSurveillable=mysqli_num_rows($resultSurveillable);
										if ($nbSurveillable > 0)
										{
											$checked="checked";
										}
									}
									echo "<tr><td><input class='checkTheme' type='checkbox' ".$checked." ".$disabled." name='Theme[]' value='".$row['Id']."' >".$row['Libelle']." ".$cadenas."</td></tr>". "\n";
								}
							}
						?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="Modif"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression
	{
		$req="DELETE FROM soda_surveillant WHERE Id_Personne=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		
		$req="DELETE FROM soda_surveillant_theme WHERE Id_Surveillant=".$_GET['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>