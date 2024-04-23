<!DOCTYPE html>
<html style="background-color:#ffffff;">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

	<title>SUIVI PRODUCTION</title><meta name="robots" content="noindex">
	<link href="CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="CSS/Accueil.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<style>
		img.bg {
			/* Set rules to fill background */
			min-height: 100%;
			min-width: 1024px;
			
			/* Set up proportionate scaling */
			width: 100%;
			height: auto;
			
			/* Set up positioning */
			position: fixed;
			top: 0;
			left: 0;
		}		
		@media screen and (max-width: 1024px){
			img.bg {
				left: 50%;
				margin-left: -512px; }
		}
		#page-wrap { position: absolute; top:0px;}
	</style>
</head>
<?php
	$Login="";
	$Mdp="";
	
	if(isset($_COOKIE["cookieLogSP"])){
		$Login=$_COOKIE["cookieLogSP"];
		$Mdp=$_COOKIE["cookieMdpSP"];
	}
?>
<body class="bodyAccueil">
	<form id="formulaire" method="POST" action="Outils/VerifLogin.php">
		<div class="encadreAccueil">
			<div class="divCenter">
				<img style="border: none;" src="Images/Logo Daher_posi.png" style="marginheight:0; marginwidth:0;" width="60%">
			</div>
			<div id="connexion">
				<?php
				if (array_key_exists('Cnx', $_GET)) {
					if ($_GET['Cnx'] == 'BAD') {
						echo "Login ou mot de passe incorrect";
					} elseif ($_GET['Cnx'] == 'BDD') {
						echo "Impossible de se connecter";
					} elseif ($_GET['Cnx'] == 'CKIES') {
						echo "Impossible de créer les cookies";
					}
				}
				?>
			</div>
			<div class="divTitre">
				SUIVI PRODUCTION
			</div>
			<div class="LibelleAccueil">Prestation</div>
			<div class="divLeft">
				<select name="prestation" style="font-size:25px;">
					<option value="AEWP">AEWP</option>
					<option value="AISLP">AISLP</option>
					<option value="ATRRQ">ATRRQ</option>
					<option value="CAALR">CAALR</option>
					<option value="CAATR">CAATR</option>
					<option value="CO330">CO330</option>
					<option value="CO350">CO350</option>
					<option value="OLW">OLW</option>
					<option value="RSP AAA GmbH">RSP AAA GmbH</option>
					<option value="S-NHHPO">S-NHHPO</option>
					<option value="TBWP">TBWP</option>
					<option value="TT350">TT350</option>
					<option value="TRSA-TRMY">SATRM</option>
					<option value="ZCS-PGC">ZCS/PGC</option>
					<option value="TEST_PREPA">TEST_PREPA</option>
					<option value="TEST_PROD">TEST_PROD</option>
				</select>
			</div>
			<div class="LibelleAccueil">Identifiant</div>
			<div class="wrap-input2 validate-input2" data-validate="Identifiant obligatoire">
				<input class="input2" type="text" name="login" value="">
				<span class="focus-input2"></span>
			</div>
			<div class="LibelleAccueil">Mot de passe</div>
			<div class="wrap-input2 validate-input2" data-validate="Mot de passe obligatoire">
				<input class="input2" type="password" name="motdepasse" value="">
				<span class="focus-input2"></span>
			</div>
			<div class="divCenter">
				<input type="submit" class="Bouton2" id="alert_button" value="&nbsp;&nbsp;&nbsp;SE CONNECTER&nbsp;&nbsp;&nbsp;">
			</div>
		</div>
	</form>
</body>

</html>