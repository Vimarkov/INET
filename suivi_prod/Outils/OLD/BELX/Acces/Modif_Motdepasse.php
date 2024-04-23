<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(){
			if(formulaire.nouveauMDP.value==''){alert('Vous n\'avez pas renseigné le nouveau mot de passe.');return false;}
			else{
				if(formulaire.nouveauMDP.value.length<8){
					alert('Le mot de passe doit avoir plus de 7 caractères.');
				}
				else{
					if(formulaire.confirmMDP.value==''){
						alert('Vous n\'avez pas confirmé le mot de passe.');
						return false;
					}
					else{
						if(formulaire.confirmMDP.value!=formulaire.nouveauMDP.value){
							alert('Le nouveau mot de passe est différent de la confirmation.');
							formulaire.confirmMDP.value='';
							return false;
						}
						else{return true;}
					}
				}
			}

		}
	</script>
</head>
<?php
session_start();
require("../../Connexioni.php");

if($_POST){
	$requete="UPDATE new_rh_etatcivil SET ";
	$requete.="MdpSP='".$_POST['nouveauMDP']."'";
	$requete.=" WHERE Id=".$_SESSION['Id_PersonneSP'];
	$result=mysqli_query($bdd,$requete);
	$_SESSION['MdpSP']=$_POST['nouveauMDP'];
	
	echo "<script>window.location.replace(\"".$chemin."/Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php\");</script>";
}
?>
	<form id="formulaire" method="POST" action="Modif_Motdepasse.php" onSubmit="return VerifChamps();">
	<table width="30%" height="40%" align="center" class="TableCompetences" style="margin-top:50px;">
		<tr class="TitreColsUsers">
			<td>Nouveau mot de passe </td>
			<td>
				<input type="password" name="nouveauMDP" value="">
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>Confirmer nouveau mot de passe </td>
			<td>
				<input type="password" name="confirmMDP" value="">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input class="Bouton" type="submit" value="Valider"></td>
		</tr>
	</table>
	</form>
</body>
</html>