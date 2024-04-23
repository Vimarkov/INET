<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<?php
session_start();
$_SESSION['Formulaire']="Planning/Pointage.php";
$laDate="";
if(isset($_GET['laDate'])){
	$laDate="?laDate=".$_GET['laDate'];
}
?>
<FRAMESET ROWS="120px,*">
	<frame name="entete" src="PointageEnTete.php<?php echo $laDate;?>"  scrolling="no" noresize="noresize">
	<frame name="corps" src="PointageCorps.php<?php echo $laDate;?>" noresize="noresize">
</FRAMESET>
</html>