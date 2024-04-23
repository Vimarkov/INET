<html>
<head>
	<title>Modification du mot de passe</title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			var New1MDP=formulaire.New1MDP.value;
			var New2MDP=formulaire.New2MDP.value;
			if(New1MDP==''){alert('Vous n\'avez pas renseigné le 1er nouveau mot de passe.');return false;}
			else{
				if(New2MDP==''){alert('Vous n\'avez pas renseigné 2ème nouveau mot de passe.');return false;}
				else{
					if(New1MDP != New2MDP){alert('Les deux nouveaux mots de passe sont différents.');return false;}
					else{
						if(New1MDP.length < 8){alert('Vous devez entrer un mot de passe de plus de 7 caractères.');return false;}
						else{return true;}
						}
					}
				}
		}
			
		function FermerEtRecharger()
		{
			//opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();	//require("VerifPage.php");
require("Connexioni.php");

if($_POST)
{
	if($_SESSION['Log']<>"")
	{
		$result=mysqli_query($bdd,'UPDATE new_rh_etatcivil SET Motdepasse="'.$_POST['New1MDP'].'" WHERE Login="'.$_SESSION['Log'].'"');
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" method="POST" action="MDP_Modif.php" onSubmit="return VerifChamps();">
	<table style="width:95%; height:95%; align:center;">
		<tr class="TitreColsUsers">
			<td>Nouveau mot de passe : </td>
			<td><input name="New1MDP" size="15" type="password" value=""></td>
		</tr>
		<tr class="TitreColsUsers">
			<td>Nouveau mot de passe (à ressaisir pour vérif.) : </td>
			<td><input name="New2MDP" size="15" type="password" value=""></td>
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