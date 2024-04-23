<!DOCTYPE html>
<html lang="en">

<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="icon" type="image/x-icon" href="Images/Logos/Logo DaherMonogramme_Posi.png">
	<link href="CSS/Accueil.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script src="Outils/JS/js/jquery.js" type="text/javascript"></script>
	<script src="Outils/JS/js/jquery.ui.draggable.js" type="text/javascript"></script>
	<script src="Outils/JS/js/jquery.alerts.js" type="text/javascript"></script>
	<link href="Outils/JS/js/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen" />
	<script src="Outils/JS/jquery-1.12.4.min.js" type="text/javascript"></script>
	<script src="Outils/JS/jquery.flurry.js" type="text/javascript"></script>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript">
		$(document).ready( function() {

			$("#alert_button").click( function() {
				jAlert('<i><b>informatique.aaa@daher.com</b></i><br/><br/>Merci de nous contacter à cette adresse mail pour des questions liées au site extranet', 'Contactez-nous');
			});
			
			$("#alert_IE8").click( function() {
				jAlert('<img src="Images/IE8.png" WIDTH="400" HEIGHT="300"/>', 'Internet Explorer 8');
			});
			
			$("#alert_IE").click( function() {
				jAlert('<img src="Images/IE.png" WIDTH="500" HEIGHT="300"/>', 'Internet Explorer');
			});
		});
	</script>
</head>
<body class="bodyAccueil">
	<form id="formulaire" method="POST" action="Outils/VerifLogin.php">
		<div class="encadreAccueil">
			<div class="divCenter">
				<img id="logoAccueil" src="Images/Logos/Logo Daher_posi.png">
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
				<input class="input2" type="text" name="login" value="">
				<span class="focus-input2"></span>
			</div>
			<div class="LibelleAccueil">Mot de passe</div>
			<div class="wrap-input2 validate-input2" data-validate="Mot de passe obligatoire">
				<input class="input2" type="password" name="motdepasse" value="">
				<span class="focus-input2"></span>
			</div>
			<div class="divCenter">
				<input type="submit" class="Bouton2" id="Accueil" value="&nbsp;&nbsp;&nbsp;SE CONNECTER&nbsp;&nbsp;&nbsp;">
			</div>
			<div class="divCenter LibelleAccueil2">
				Vous avez des questions liées à l'utilisation du site internet ou souhaitez demander votre mot de passe ?
			</div>
			<div class="divCenter">
				<input class="Bouton2" id="alert_button" type="button" value="&nbsp;&nbsp;&nbsp;CONTACTEZ-NOUS&nbsp;&nbsp;&nbsp;" />
			</div>
		</div>
	</form>
</body>

</html>