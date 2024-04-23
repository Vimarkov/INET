 <!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Ajouter une note à une surveillance</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script> 	
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="Fonctions.js"></script>
	<script>
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}

		$(function(){$("#datepicker").datepicker({dateFormat: 'dd/mm/yy'});});
	</script>
</head>

<?php
//Traitements côté serveur du POST
if(isset($_POST['DateNote']) && isset($_POST['Note']))
{	
	//executer la requete SQL de mise à jour
	getRessource(getChaineSQL_enregistrerNote($_GET['Id'], $_POST['Note'], TrsfDate_($_POST['DateNote'])));
?>
	<script>FermerEtRecharger()</script>
<?php 
}
?>
	<form method="post">
		<table style="width:100%; border-spacing:0; align:center;">
			<tr>
				<td>
					<table style="width:100%; border-spacing:0;">
						<tr>
							<td width="4"></td>
							<td class="TitrePage">
		 						<?php 
		 						    if($LangueAffichage=="FR")
                                        echo "Ajouter note";
		 						    else
		 						        echo "Add grade";
		 						?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
		</table>
		
		<table>
		<tr>
			<td class="Libelle">Date : </td><td><input type="text" id="datepicker" name="DateNote" /></td>
			<td class="Libelle">Note (%) :  </td><td><input type="text" name="Note" onkeyup="nombre(this);"/></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input class="Bouton" type="button" value="<?php if($LangueAffichage=="FR"){echo "Enregistrer";}else{echo "Save";}?>" onclick="submit();" />
			</td>
		</tr>
		</table>
	</form>

	</body>
</html>

	