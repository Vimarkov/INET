<!DOCTYPE html>
<html>
<head>
<title>TraME</title><meta name="robots" content="noindex">
</head>
<?php
	session_start();
?>

<frameset rows="130,*" name="Total" border="0" frameborder="0" framespacing="0">
	<frame name="BandeauHaut" src="BandeauHaut.php" noresize>
	<frame name='General' src='Outils/<?php echo $_SESSION['Formulaire'];?>' noresize>
</frameset>

<noframes>TraME</noframes>

</html>
