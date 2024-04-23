<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, maximum-scale=1"/>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<script src="../v2/Outils/JS/js/jquery.js" type="text/javascript"></script>
	<script src="../v2/Outils/JS/js/jquery.ui.draggable.js" type="text/javascript"></script>
	<script src="../v2/Outils/JS/js/jquery.alerts.js" type="text/javascript"></script>
	<link href="../v2/CSS/Accueil.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../v2/Outils/JS/js/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
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
		
		img.bg2 {
			/* Set rules to fill background */
			min-height: 100%;
			min-width: 50%;
			
			/* Set up proportionate scaling */
			width: 50%;
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
		#page-wrap { position: absolute; top:-30px;}
		#page-wrap2 { position: absolute; z-index:900; height:100%;}
	</style>
</head>
<?php
	$Login="";
	$Mdp="";
?>
<body class="bodyAccueil">
	<form id="formulaire" method="POST" action="VerifLogin.php">
		<div class="encadreAccueil">
			<div class="divLangue">
				<td align="right">
					<select style="font-size:3em;" id="Langue" name="Langue">
						<option value="FR">FR</option>
						<option value="EN">EN</option>
					</select>
				</td>
			</div>
			<div class="divCenter">
				<img style="border: none;" src="../v2/Images/Logos/Logo Daher_posi.png" style="marginheight:0; marginwidth:0;" width="60%">
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
			<div class="LibelleAccueil">Identifiant</div>
			<div class="wrap-input2 validate-input2" data-validate="Identifiant obligatoire">
				<input class="input2" type="text" name="login" value="" style="border: 1px solid #0066CC;height:50px;width:300px;font-size:2em;">
				<span class="focus-input2"></span>
			</div>
			<div class="LibelleAccueil">Mot de passe</div>
			<div class="wrap-input2 validate-input2" data-validate="Mot de passe obligatoire">
				<input class="input2" type="password" name="motdepasse" value="" style="border: 1px solid #0066CC;height:50px;width:300px;font-size:2em;">
				<span class="focus-input2"></span>
			</div>
			<div class="divCenter">
				<input type="submit" class="Bouton2" id="alert_button" value="&nbsp;&nbsp;&nbsp;SE CONNECTER&nbsp;&nbsp;&nbsp;">
			</div>
		</div>
	</form>
</body>
</html>