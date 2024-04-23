<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeHS.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
			}
			else{
				if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

			}
			return true;
		}
		function FermerEtRecharger()
		{
			window.opener.location="Liste_Demande_Besoin.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	$requeteUpdate="UPDATE form_demandebesoin SET 
			Id_Valideur=".$_SESSION['Id_Personne'].",
			Date_Validation='".date('Y-m-d')."',
			Etat=-1,
			RaisonRefus='".addslashes($_POST['commentaire'])."' 
			WHERE Id=".$_POST['Id']." ";

	$resultat=mysqli_query($bdd,$requeteUpdate);
	echo "<script>FermerEtRecharger();</script>";
}
?>

<form id="formulaire" class="test" action="Refuser_DemandeBesoin.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Raison du refus :";}else{echo "Reason for refusal :";} ?></td>
							<td width="30%" colspan="6">
								<textarea name="commentaire" id="commentaire" cols="100" rows="4" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>