<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Refuser recyclage qualification</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">	
			function FermerEtRecharger()
			{
				if(window.opener.document.getElementById('formulaire')){
					window.opener.document.getElementById('formulaire').submit();
				}
				window.close();
			}
	</script>
</head>

<?php
	if($_POST)
	{
		$req="UPDATE form_qualificationnecessaire_prestation SET Necessaire=0, Motif='".addslashes($_POST['Motif'])."', DateValidation='".date('Y-m-d')."' WHERE Id = ".$_POST['Id'];
		$result=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
?>	
<form id="formulaire" action="AnnulerRecyclageQualif.php" method="post">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
	<input type="hidden" id="Id" name="Id" value="<?php echo $_GET['Id']; ?>">
	<input type="hidden" id="Id_Personne" name="Id_Personne" value="<?php echo $_GET['Id_Personne']; ?>">
	<input type="hidden" id="Id_Qualification" name="Id_Qualification" value="<?php echo $_GET['Id_Qualification']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Refus du recyclage d'une qualification";}else{echo "Refusal of a retraining of a qualification";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td class="Libelle">
				<?php if($LangueAffichage=="FR"){echo "Motif";}else{echo "Reason";}?> : 
		</td>
	</tr>
	<tr>
		<td class="Libelle">
				<textarea name="Motif" rows="3" cols="50"style="resize:none"></textarea>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr align="center">
		<td>
			<input class="Bouton" type="submit" name="refuser" <?php if($LangueAffichage=="FR"){echo "value='Refuser'";}else{echo "value='Refuse'";} ?>>
		</td>
	</tr>	
</table>
</form>