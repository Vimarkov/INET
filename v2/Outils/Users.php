<html>
<head>
	<title>Utilisateur</title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Nom.value==''){alert('Vous n\'avez pas renseigné le nom.');return false;}
			else{
				if(formulaire.Prenom.value==''){alert('Vous n\'avez pas renseigné le prénom.');return false;}
				else{
					if(formulaire.Login.value==''){alert('Vous n\'avez pas renseigné le login.');return false;}
					else{
						if(formulaire.Motdepasse.value==''){alert('Vous n\'avez pas renseigné le mot de passe.');return false;}
						else{return true;}
						}
					}
				}
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();	//require("VerifPage.php");
require("Connexioni.php");

function ucname($string)
{
    $string =ucwords(strtolower($string));
    if (strpos($string, '-')!==false) {$string =implode('-', array_map('ucfirst', explode('-', $string)));}
    return $string;
}

if($_POST)
{
	if($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_rh_etatcivil WHERE Id='".$_POST['Id']."'");
		if(mysqli_num_rows($result)>0)
		{
			$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET Nom='".addslashes(trim(strtoupper($_POST['Nom'])))."', Prenom='".addslashes(trim(ucname($_POST['Prenom'])))."', Login='".$_POST['Login']."', Motdepasse='".$_POST['Motdepasse']."', EmailPro='".$_POST['Email']."', TelephoneProMobil='".$_POST['Telephone']."' WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_rh_etatcivil WHERE Id='".$_GET['Id']."'");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Users.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="OldLogin" value="<?php if($_GET['Mode']=="Modif"){echo $row['Login'];}?>">
		<table style="width:95%; height:95%; align:center;">
			<tr>
				<td>Nom : </td>
				<td><input name="Nom" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Nom'];}?>"></td>
				<td>Prénom : </td>
				<td><input name="Prenom" size="15" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Prenom'];}?>"></td>
				<td>Email Pro : </td>
				<td><input name="Email" size="25" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['EmailPro'];}?>"></td>
			</tr>
			<tr>
				<td>Téléphone Pro : </td>
				<td><input name="Telephone" size="15" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['TelephoneProMobil'];}?>"></td>
				<td>Login : </td>
				<td><input name="Login" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Login'];}?>"></td>
				<td>Mot de passe : </td>
				<td><input name="Motdepasse" size="15" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Motdepasse'];}?>"></td>
			</tr>
			<tr>
				<td><input name="Id" type="hidden" value="<?php echo $_GET['Id'] ?>"></td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="Modif"){echo "value='Valider'";}
						else{echo "value='Ajouter'";}
					?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET Motdepasse='', Login='',MdpSP='', LoginSP='' WHERE Id='".$_GET['Id']."'");
		echo "<script>FermerEtRecharger();</script>";
	}
	if(isset($result)){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>