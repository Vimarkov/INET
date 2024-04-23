<html>
<head>
	<title>Modification des informations personnelles</title><meta name="robots" content="noindex">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function OuvreFenetreMDP(){window.open("MDP_Modif.php?Id=0","MDP","status=no,menubar=no,width=400,height=55");}
		function FermerEtRecharger(){
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../../Connexioni.php");

if($_POST)
{
	if(isset($_SESSION['LogSP']))
	{
		if($_SESSION['LogSP']!=''){
			$requete="UPDATE new_rh_etatcivil SET ";
			$requete.="TelephoneProMobil='".$_POST['TelephoneProMobil']."', ";
			$requete.="TelephoneProFixe='".$_POST['TelephoneProFixe']."', ";
			$requete.="Matricule='".$_POST['Matricule']."', ";
			$requete.="EmailPro='".$_POST['Email']."' ";
			$requete.=" WHERE Id=".$_SESSION['Id_PersonneSP']."";
			echo $requete;
			$result=mysqli_query($bdd,$requete);
		}
		else{
			echo "Votre session a expiré. Veuillez vous déconnecter et recommencer l'operation.";
		}
	}
	else{
		echo "Votre session a expiré. Veuillez vous déconnecter et recommencer l'operation.";
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$result=mysqli_query($bdd,"SELECT TelephoneProFixe,TelephoneProMobil,Matricule, EmailPro FROM new_rh_etatcivil WHERE LoginSP='".$_SESSION['LogSP']."'");
	$row=mysqli_fetch_array($result);
?>
	<form id="formulaire" method="POST" action="Utilisateur_Change_Profil.php">
		<table class="TableCompetences" width="95%" height="95%" align="center">
			<tr>
				<td colspan="2">
					<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreMDP();" align="right">&nbsp;Modifier le mot de passe&nbsp;</a>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Tél pro fixe : </td>
				<td><input name="TelephoneProFixe" size="20" value="<?php echo $row['TelephoneProFixe'];?>"></td>
				<td>Tél pro mobile : </td>
				<td><input name="TelephoneProMobil" size="20" value="<?php echo $row['TelephoneProMobil'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Matricule (ST/NG) : </td>
				<td><input name="Matricule" size="20" value="<?php echo $row['Matricule'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Email Pro : </td>
				<td><input name="Email" size="30" value="<?php echo $row['EmailPro'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="Valider">
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