<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8 />
	<link href="CSS/Bandeau.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function OuvreFenetreUtilisateur(Id,Prestation){
			window.open("Outils/"+Prestation+"/Acces/Utilisateur_Change_Profil.php?Id="+Id,"ChangeProfil","status=no,menubar=no,width=530,height=150");
		}

		function Deconnexion()
		{
			top.location="index.php";
			window.close();
		}
	</script>
	<script type="text/javascript">
	<!--
	//
	var position=0;
	var msg="            ";
	var msg="         /!\\ Le site extranet se sécurise (passage en https) . Veuillez utiliser dès à présent l'adresse suivante https://extranet.aaa-aero.com/suivi_prod . A partir du 18/06 l'adresse en http ne sera plus accessible /!\\       /!\\ The extranet site is secure (switch to https). Please use the following address https://extranet.aaa-aero.com/suivi_prod right now. From 18/06 the address in http will no longer be accessible /!\\            "+msg;
	var longue=msg.length;
	var fois=(270/msg.length)+1;
	for(i=0;i<=fois;i++) msg+=msg;
	function textdefil() {
	document.form1.deftext.value=msg.substring(position,position+270);
	position++;
	if(position == longue) position=0;
	setTimeout("textdefil()",150);
	}
	window.onload = textdefil;
	//-->
	</script>
</head>
<?php
	session_start();
	require("Outils/Connexioni.php");
	
	if(isset($_SESSION['LogSP'])){
		setcookie("cookieLogSP", $_SESSION['LogSP'], time()+360000);
		setcookie("cookieMdpSP", $_SESSION['MdpSP'], time()+360000);
	}
?>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<td width="120">
			<table>
				<tr>
					<td>
						<a style="border: none;" href="Accueil.php" target="_top">
						<img style="border: none;" src="Images/Logo DaherMonogramme_Neg.png" marginheight="0" marginwidth="0" width="120" alt="Accueil" title="Accueil">
						</a>
					</td>
				</tr>
			</table>
		</td>
		<td class="Titre" >SUIVI PRODUCTION 
		<?php 
		if($_SESSION['PrestationSP']=="TRSA-TRMY"){echo "SATRM";}
		else{echo $_SESSION['PrestationSP'];}
		?>
		</td>
		<td width="220">
			<table width="100%">
				<tr>
					<td class="Identification">Bonjour <?php echo $_SESSION['PrenomSP']." ".$_SESSION['NomSP']; ?></td>
				</tr>
				<tr>
					<td>
						<input type="submit" class="Bouton" value="Modifier son profil" onclick="javascript:OuvreFenetreUtilisateur(<?php echo $_SESSION['Id_PersonneSP'].",'".$_SESSION['PrestationSP']."'";?>);">
					</td>
				</tr>
				<tr>
					<td>
					<a style="text-decoration:none;font:12px Calibri;" target="_top" class="Bouton" href="index.php">&nbsp;Deconnexion&nbsp;</a>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
	<!--<tr>
		<td colspan="6" align="center">
			<form name="form1">
			<div align="center">
			<input style="color:red;font-weight: bold;" type="text" name="deftext" size=180>
			</div>
			</form>
		</td>
	</tr>-->
</table>
</body>
</html>