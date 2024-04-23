<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
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


//Mode réinitialisation
$requete="UPDATE new_rh_etatcivil SET ";
$requete.="Motdepasse='".$_SESSION['MdpDefaut']."' ";
$requete.=" WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);

//Verifier si la personne à un login 
$select = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Login<>'' AND Id=".$_GET['Id']." ";
$result=mysqli_query($bdd,$select);
$nbResulta=mysqli_num_rows($result);
if($nbResulta==0){
	$select = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id']." ";
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
	$requete.=" WHERE Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$requete);
}

echo "<script>FermerEtRecharger();</script>";
	
mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>