<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
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
$mdp="aaa01";
if($_POST){
	if($_POST['Mode']=="A"){
		$req="SELECT Nom,Prenom, LoginTrame, MdpTrame FROM new_rh_etatcivil WHERE Id=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
		
		//Verifier si cette personne n'a pas dejà accès
		if($row['LoginTrame'] == ""){
			$login=str_replace("'","",strtolower(substr($row['Prenom'],0,1).$row['Nom']));
			$login=str_replace(" ","",$login);
			
			//Vérifier existance Login dans la base
			$select = "SELECT Id FROM new_rh_etatcivil WHERE LoginTrame LIKE '".$login."%'";
			$result=mysqli_query($bdd,$select);
			$nbResulta=mysqli_num_rows($result);
			
			if($nbResulta>0){$login=$login.$nbResulta;}
			$requete="UPDATE new_rh_etatcivil SET ";
			$requete.="LoginTrame='".$login."', ";
			$requete.="MdpTrame='".$mdp."' ";
			$requete.=" WHERE Id=".$_POST['personne'];
			$result=mysqli_query($bdd,$requete);
		}
		
		$req="INSERT INTO trame_admin (Id_Personne) VALUES (".$_POST['personne'].")";
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A"){
?>

		<form id="formulaire" method="POST" action="Ajout_Utilisateur.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Personne" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "User";}else{echo "Personne";} ?></td>
				<td>
					<select id="personne" name="personne">
					<?php
					if($_GET['Mode']=="A"){
						echo"<option name='0' value='0'></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE Id NOT IN (SELECT Id_Personne FROM trame_admin) ORDER BY Nom, Prenom;";
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
		$req="DELETE FROM trame_admin WHERE Id_Personne=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>