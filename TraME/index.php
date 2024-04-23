<!DOCTYPE html>
<html style="background-color:#ffffff;">
<head>
	<title>TraME</title><meta name="robots" content="noindex">
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
	<script language="javascript">
		function OuvreFenetreMDP(){
			var w=window.open("MDP_Oublie.php?","MDP","status=no,menubar=no,scrollbars=yes,width=800,height=250");
			w.focus();
		}
	</script>
</head>
<?php
	session_start();
	require("Outils/Connexioni.php");

	$Langue="FR";
	if(isset($_GET['L'])){
		if($_GET['L']=='EN'){$Langue="EN";}
	}
	$Login="";
	$Mdp="";
	
	if(isset($_COOKIE["cookieLogTR"])){
		$Login=$_COOKIE["cookieLogTR"];
		$Mdp=$_COOKIE["cookieMdpTR"];
	}
?>
<body class="bodyAccueil">
	<form id="formulaire" method="POST" action="Outils/VerifLogin.php?L=<?php echo $Langue;?>">
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
				TraME
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
			
			<div class="divRight">
				<a style="text-decoration:none;" href="javascript:OuvreFenetreMDP()">&nbsp;<?php if($Langue=='EN'){echo "Forgot your password";}else{echo "Mot de passe oublié";}?>&nbsp;&nbsp;</a>
			</div>
		</div>
	</form>
</body>
</html>