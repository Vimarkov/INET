<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(){
			window.opener.document.getElementById('formulaire').submit();
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	//Mode réinitialisation
	$requete="UPDATE new_rh_etatcivil SET ";
	$requete.="Motdepasse='".$_SESSION['MdpDefaut']."' ";
	$requete.=" WHERE Id=".$_POST['Id'];
	$result=mysqli_query($bdd,$requete);

	//Verifier si la personne à un login 
	$select = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Login<>'' AND Id=".$_POST['Id']." ";
	$result=mysqli_query($bdd,$select);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta==0){
		$select = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$select);
		$row=mysqli_fetch_array($result);
		
		//Verifier si cette personne n'a pas dejà accès
		$login=str_replace("'","",strtolower(substr(trim($row['Prenom']),0,1).trim($row['Nom'])));
		$login=str_replace(" ","",$login);

		//Vérifier existance Login dans la base
		$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$login."%'";
		$result=mysqli_query($bdd,$select);
		$nbResulta=mysqli_num_rows($result);
		if($nbResulta>0){
			$bOK=0;
			$compteur=1;
			
			//Tant que le login n'est pas uniquement 
			while($bOK==0){
				$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$login.$compteur."%'";
				$resultLogin=mysqli_query($bdd,$select);
				$nbLogin=mysqli_num_rows($resultLogin);
				if($nbLogin==0){$bOK=1;}
				else{
					$compteur++;
				}
			}
			$login=$login.$compteur;
		}
		
		$requete="UPDATE new_rh_etatcivil SET ";
		$requete.="Login='".$login."' ";
		$requete.=" WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
	}

	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$result=mysqli_query($bdd,"SELECT Login, Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id']." ");
	$row=mysqli_fetch_array($result);
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="IdentifiantPersonne.php">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle" colspan="2"><span style="text-decoration:underline;"><?php echo $row['Nom']." ".$row['Prenom']; ?></span></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Login";}else{echo "Login";}?> :
				<?php echo $row['Login']; ?></td>
			</tr>
			<tr>
				<td align="left" colspan="2">
					<input class="Bouton" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Réinitialiser le mot de passe'";}else{echo "value='Reset password'";} ?>/>
				</td>
			</tr>
		</table>
		</form>
<?php
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}	
?>
	
</body>
</html>