<!DOCTYPE html>
<html>
<head>
<title>SUIVI PRODUCTION</title><meta name="robots" content="noindex">
</head>
<?php
	session_start();
?>
<frameset rows="115,*" name="Total" border="0" frameborder="0" framespacing="0">
	<frame name="BandeauHaut" src="BandeauHaut.php" noresize>
	<?php
		if($_SESSION['PrestationSP']<>"P17S"){
			echo "<frame name='General' src='Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php' noresize>";
		}
		else{
			echo "<frame name='General' src='Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php' noresize>";
		}
	?>
</frameset>

<noframes>SUIVI PRODUCTION</noframes>

</html>
