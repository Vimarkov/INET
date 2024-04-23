<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
	this.onbeforeunload=function(){
		window.opener.parent.location="Pointage.php?laDate="+window.location.href.substring(window.location.href.lastIndexOf('DateEC=')+7)+"";
	}
	</script>
</head>
<?php
session_start();
?>
<FRAMESET ROWS="80px,*">
	<frame name="entete" src="PlanningEnTeteResp.php?Id=<?php echo $_GET['Id'];?>&Semaine=<?php echo $_GET['Semaine'];?>&Annee=<?php echo $_GET['Annee'];?>&DateEC=<?php echo $_GET['DateEC'];?>"  scrolling="no" noresize="noresize">
	<frame name="corps" src="PlanningCorpsResp.php?Id=<?php echo $_GET['Id'];?>&Semaine=<?php echo $_GET['Semaine'];?>&Annee=<?php echo $_GET['Annee'];?>&DateEC=<?php echo $_GET['DateEC'];?>#DebutJournee" noresize="noresize">
</FRAMESET>
</html>