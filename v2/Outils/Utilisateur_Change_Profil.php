<html>
<head>
	<title>Modification des informations personnelles</title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger()
		{
			//opener.location.reload();
			window.close();
		}
		function OuvreFenetreMDP(){window.open("MDP_Modif.php?Id=0","MDP","status=no,menubar=no,width=400,height=25");}
	</script>
</head>
<body>

<?php
session_start();	//require("VerifPage.php");
require("Connexioni.php");

if($_POST)
{
	if(isset($_SESSION['Log']))
	{
		if($_SESSION['Log']!='')
		{
			$requete="
                UPDATE
                    new_rh_etatcivil
                SET
                    TelephoneProMobil='".$_POST['TelephoneProMobil']."',
                    TelephoneProFixe='".$_POST['TelephoneProFixe']."',
                    EmailPro='".$_POST['EmailPro']."',
                    Email='".$_POST['Email']."',
                    NumBadge='".$_POST['NumBadge']."',
                    Matricule='".$_POST['Matricule']."',
                    TelephoneMobil='".$_POST['TelephoneMobil']."'
                WHERE
                    Login='".$_SESSION['Log']."'";
			$result=mysqli_query($bdd,$requete);
		}
		else
		{
			echo "Votre session a expiré. Veuillez vous déconnecter et recommencer l'operation.";
		}
	}
	else
	{
		echo "Votre session a expiré. Veuillez vous déconnecter et recommencer l'operation.";
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$result=mysqli_query($bdd,"SELECT * FROM new_rh_etatcivil WHERE Login='".$_SESSION['Log']."'");
	$row=mysqli_fetch_array($result);
?>
	<form id="formulaire" method="POST" action="Utilisateur_Change_Profil.php">
		<table class="TableCompetences" style="width:100%; align:center;">
			<tr>
				<td colspan="2">
					<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreMDP();" style="align:right;">&nbsp;Modifier le mot de passe&nbsp;</a>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Tél pro fixe : </td>
				<td><input name="TelephoneProFixe" size="20" value="<?php echo $row['TelephoneProFixe'];?>"></td>
				<td>Tél pro mobile : </td>
				<td><input name="TelephoneProMobil" size="20" value="<?php echo $row['TelephoneProMobil'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Tél mobile perso : </td>
				<td><input name="TelephoneMobil" size="20" value="<?php echo $row['TelephoneMobil'];?>"></td>
			</tr>
			<tr>
				<td>Email pro : </td>
				<td><input name="EmailPro" size="45" value="<?php echo $row['EmailPro'];?>"></td>
				<td>Email perso : </td>
				<td><input name="Email" size="45" value="<?php echo $row['Email'];?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td>N° badge : </td>
				<td><input name="NumBadge" size="20" value="<?php echo $row['NumBadge'];?>"></td>
				<td>Matricule (ST/NG) : </td>
				<td><input name="Matricule" size="20" value="<?php echo $row['Matricule'];?>"></td>
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