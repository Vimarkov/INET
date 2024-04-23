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
		function FermerEtRecharger()
		{
			window.opener.location="Liste_Besoin_Formation.php";
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
	$requeteUpdate="UPDATE form_besoin SET 
			CommentaireCE='".addslashes($_POST['commentaire'])."' 
			WHERE Id=".$_POST['Id']." ";

	$resultat=mysqli_query($bdd,$requeteUpdate);
	echo "<script>FermerEtRecharger();</script>";
}

$req="SELECT CommentaireCE FROM form_besoin WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);
?>

<form id="formulaire" class="test" action="Mettre_Commentaire.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?></td>
						</tr>
						<tr>
							<td width="30%">
								<textarea name="commentaire" id="commentaire" cols="50" rows="4" style="resize:none;"><?php echo stripslashes($row['CommentaireCE']); ?></textarea>
							</td>
						</tr>
						<tr>
							<td align="center">
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