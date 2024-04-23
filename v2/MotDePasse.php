<?php
	require("Menu.php");
?>
<script>
	function VerifChamps(Langue){
		if(formulaire.nouveauMDP.value==''){
			if(Langue=="EN"){alert('You have not entered the new password.');}
			else{alert('Vous n\'avez pas renseigné le nouveau mot de passe.');}
			return false;
		}
		else{
			if(formulaire.nouveauMDP.value.length<8){
				if(Langue=="EN"){alert('The password must be more than 7 characters long.');}
				else{alert('Le mot de passe doit avoir plus de 7 caractères.');}
				return false;
			}
			else{
				if(formulaire.confirmMDP.value==''){
					if(Langue=="EN"){alert('You have not confirmed the password.');}
					else{alert('Vous n\'avez pas confirmé le mot de passe.');}
					return false;
				}
				else{
					if(formulaire.confirmMDP.value!=formulaire.nouveauMDP.value){
						if(Langue=="EN"){alert('The new password is different from the confirmation.');}
						else{alert('Le nouveau mot de passe est différent de la confirmation.');}
						formulaire.confirmMDP.value='';
						return false;
					}
					else{return true;}
				}
			}
		}

	}
</script>
<?php

if($_POST){
	$requete="UPDATE new_rh_etatcivil SET ";
	$requete.="Motdepasse='".$_POST['nouveauMDP']."'";
	$requete.=" WHERE Id=".$_SESSION['Id_Personne'];
	$result=mysqli_query($bdd,$requete);
	$_SESSION['Mdp']=$_POST['nouveauMDP'];
	echo "<body onload='top.location.href=\"".$chemin."/Accueil.php\";'>";
}
?>
<form id="formulaire" method="POST" action="MotDePasse.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<table style="width:30%; height:40%; align:center; margin-top:50px;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($_SESSION['Langue']=="EN"){ echo "New Password";}else{echo "Nouveau mot de passe";} ?> </td>
			<td>
				<input type="password" name="nouveauMDP" value="">
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($_SESSION['Langue']=="EN"){ echo "Confirm new password";}else{echo "Confirmer nouveau mot de passe";} ?> </td>
			<td>
				<input type="password" name="confirmMDP" value="">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){ echo "Validate";}else{echo "Valider";} ?>"></td>
		</tr>
	</table>
</form>
</body>
</html>
