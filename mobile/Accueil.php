<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
</head>
<?php
	session_start();
	if(isset($_GET['langue'])){
		$_SESSION['Langue'] = $_GET['langue'];
	}
?>
<frameset name="Total" border="0" frameborder="0" framespacing="0">
	<frame name="General" src="TDB_Accueil.php" noresize>
</frameset>

<noframes>Extranet | Daher</noframes>

</html>
