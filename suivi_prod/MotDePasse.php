<?php
	session_start();
?>
<html>
<head>
<title>SUIVI PRODUCTION</title><meta name="robots" content="noindex">
</head>
<frameset rows="90,*" name="Total" border="0" frameborder="0" framespacing="0">
	<frame name="BandeauHaut" src="BandeauHaut.php" noresize>
	<frame name="General" src="Outils/<?php echo $_SESSION['PrestationSP'];?>/Acces/Modif_Motdepasse.php" noresize>
</frameset>

<noframes>SUIVI PRODUCTION</noframes>

</html>