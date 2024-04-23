<?php
require("../../Menu.php");

/**
 * afficherTitrePage
 * 
 * Affiche le titre de la page en fonction de la langue selectionee
 */
function afficherTitrePage()
{
	global $LangueAffichage
	
	if($LangueAffichage=="FR")
		echo "Gestion des formations # Tableau de bord";
	else
		echo "Formation management # Dashboard";
}

/**
 * afficherTitreListe
 * 
 * affiche le titre de la liste en fonction de la langue selectionee
 */
function afficherTitreListe()
{
	global $LangueAffichage
	
	if($LangueAffichage=="FR")
		echo "Ma liste de surveillance";
	else
		echo "My monitoring list";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Formations - Tableau de bord des surveillances</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>	
</head>

<table style="width:100%; border-spacing:0; align:center;">
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
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr>
					<td colspan="6"><b>&nbsp; <?php afficherTitreListe(); ?> : </b></td>
				</tr>
				<tr><td height="4"></td></tr>
				<?php				    
				    echo \WorkflowDesSurveillances\Bibliotheque\TableauDeBord_construireListe($IdPersonneConnectee);
				?>
			</table>
		</td>
	</tr>
</table>