<html>
<head>
	<title>Modification du mot de passe</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(Langue)
		{
			var New1MDP=formulaire.New1MDP.value;
			var New2MDP=formulaire.New2MDP.value;
			if(New1MDP==''){
				if(Langue=="EN"){alert('You have not entered the 1st new password.');}
				else{alert('Vous n\'avez pas renseigné le 1er nouveau mot de passe.');}
				return false;
			}
			else{
				if(New2MDP==''){
					if(Langue=="EN"){alert('You have not entered the 2nd new password.');}
					else{alert('Vous n\'avez pas renseigné 2ème nouveau mot de passe.');}
					return false;
				}
				else{
					if(New1MDP != New2MDP){
						if(Langue=="EN"){alert('The new password is different from the confirmation.');}
						else{alert('Le nouveau mot de passe est différent de la confirmation.');}
						return false;
					}
					else{
						if(New1MDP.length < 8){
							if(Langue=="EN"){alert('The password must be more than 7 characters long.');}
							else{alert('Le mot de passe doit avoir plus de 7 caractères.');}
							return false;
						}
						else{return true;}
						}
					}
				}
		}
			
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
	if($_SESSION['LogTR']<>"")
	{
		$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET MdpTrame='".$_POST['New1MDP']."' WHERE LoginTrame='".$_SESSION['LogTR']."'");
		mysqli_free_result($result);	// Libération des résultats
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" method="POST" action="MDP_Modif.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<table width="95%" height="95%" align="center">
		<tr class="TitreColsUsers">
			<td><?php if($_SESSION['Langue']=="EN"){ echo "New Password";}else{echo "Nouveau mot de passe";} ?> : </td>
			<td><input name="New1MDP" size="15" type="password" value=""></td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($_SESSION['Langue']=="EN"){ echo "New Password (To re-enter for verification)";}else{echo "Nouveau mot de passe (à ressaisir pour vérif.)";} ?> : </td>
			<td><input name="New2MDP" size="15" type="password" value=""></td>
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