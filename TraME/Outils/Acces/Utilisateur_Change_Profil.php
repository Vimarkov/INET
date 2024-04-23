<html>
<head>
	<title>Modification des informations personnelles</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function OuvreFenetreMDP(){window.open("MDP_Modif.php?Id=0","MDP","status=no,menubar=no,width=400,height=150");}
		function FermerEtRecharger(){
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
	if(isset($_SESSION['Id_PersonneTR']))
	{
		if($_SESSION['Id_PersonneTR']!=''){
			$requete="UPDATE new_rh_etatcivil SET ";
			$requete.="TelephoneProMobil='".$_POST['TelephoneProMobil']."', ";
			$requete.="TelephoneProFixe='".$_POST['TelephoneProFixe']."', ";
			$requete.="Matricule='".$_POST['Matricule']."', ";
			$requete.="EmailPro='".$_POST['Email']."' ";
			$requete.=" WHERE Id=".$_SESSION['Id_PersonneTR']."";
			echo $requete;
			$result=mysqli_query($bdd,$requete);
		}
		else{
			if($_SESSION['Langue']=="EN"){
				echo "Your session has expired. Please log out and try again";
			}
			else{
				echo "Votre session a expiré. Veuillez vous déconnecter et recommencer l'operation.";
			}
		}
	}
	else{
		if($_SESSION['Langue']=="EN"){
			echo "Your session has expired. Please log out and try again";
		}
		else{
			echo "Votre session a expiré. Veuillez vous déconnecter et recommencer l'operation.";
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$result=mysqli_query($bdd,"SELECT TelephoneProFixe,TelephoneProMobil,Matricule, EmailPro FROM new_rh_etatcivil WHERE Id='".$_SESSION['Id_PersonneTR']."'");
	$row=mysqli_fetch_array($result);
?>
	<form id="formulaire" method="POST" action="Utilisateur_Change_Profil.php">
		<table class="TableCompetences" width="95%" height="95%" align="center">
			<tr>
				<td colspan="2">
					<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreMDP();" align="right">&nbsp;<?php if($_SESSION['Langue']=="EN"){ echo "Change my password";}else{echo "Modifier mon mot de passe";} ?>&nbsp;</a>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){ echo "Business phone";}else{echo "Tél pro fixe";} ?> :</td>
				<td><input name="TelephoneProFixe" size="20" value="<?php echo $row['TelephoneProFixe'];?>"></td>
				<td><?php if($_SESSION['Langue']=="EN"){ echo "Business mobile phone";}else{echo "Tél pro mobile";} ?> : </td>
				<td><input name="TelephoneProMobil" size="20" value="<?php echo $row['TelephoneProMobil'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){ echo "ST/NG";}else{echo "Matricule (ST/NG)";} ?> : </td>
				<td><input name="Matricule" size="20" value="<?php echo $row['Matricule'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){ echo "Email professional";}else{echo "Email Pro";} ?> : </td>
				<td><input name="Email" size="30" value="<?php echo $row['EmailPro'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){ echo "Validate";}else{echo "Valider";} ?>">
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