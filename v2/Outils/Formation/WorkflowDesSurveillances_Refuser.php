<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Refuser une surveillance</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">	
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function consulterQCM()
		{
			var w=window.open("Consult_QCM.php", "", "width=500,height=1000");
			w.focus();
		}
	</script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>
	<script>
		function datepick()
		{
			if (!Modernizr.inputtypes['date']){$('input[type=date]').datepicker({dateFormat: 'dd/mm/yy'});}
		}
	</script>
</head>
<?php

Ecrire_Code_JS_Init_Date();

/**
 * afficherTitrePage
 * 
 * Affiche le titre de la page
 */	
function afficherTitrePage()
{
	global $LangueAffichage;
	
	if($LangueAffichage=="FR")
		echo "Refuser la surveillance";
	else
		echo "Refuse monitoring";
}

/**
 * afficherQuestion
 * 
 * Affiche la question en fonction de la langue choisie
 */
function afficherQuestion()
{
	global $LangueAffichage;
	
	if($LangueAffichage=="FR")
		echo "Souhaitez-vous modifier la date de fin de qualification ?";
	else
		echo "Do you want to modify the end of qualification date ?";
}
?>
<?php
	if($_POST)
	{
		$date="";
		if($_POST['QualificationDate']<>""){$date=", Date_Fin='".TrsfDate_($_POST['QualificationDate'])."'";}
		$req="UPDATE new_competences_relation SET Statut_Surveillance = 'REFUSE',IgnorerSurveillance=0, Date_Ignore='0001-01-01', Id_Ignore=0 ".$date." WHERE Id = ".$_POST['Id'];
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
?>	
<form id="formulaire" action="WorkflowDesSurveillances_Refuser.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Id" name="Id" value="<?php echo $_GET['Id']; ?>">

<table class="GeneralInfo" style="width:100%; border-spacing:0; align:center;">	
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php afficherTitrePage(); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td class="Libelle">
			<?php afficherQuestion(); ?>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td class="Libelle">
				<?php if($LangueAffichage=="FR"){echo "Fin de qualification";}else{echo "End of qualification";}?> : <input type="date" id="QualificationDate" name="QualificationDate" value="">			
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
