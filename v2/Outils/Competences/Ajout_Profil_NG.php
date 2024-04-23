<html>
<head>
	<title>Compétences - Profil personne - EtatCivil</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

if($_POST)
{		    
	$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET Matricule='".$_POST['NG']."' WHERE Id=".$_POST['Id_Personne']);
			
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$result=mysqli_query($bdd,"SELECT Id, Matricule FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']);
	$row=mysqli_fetch_array($result);
?>
<form id="formulaire" method="POST" action="Ajout_Profil_NG.php">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "ST / NG";}else{echo "ST / NG";}?> : </td>
			<td>
				<input name="NG" size="10" type="text" value="<?php echo $row['Matricule'];?>">
			</td>
			<td><input class="Bouton" type="submit"
				<?php
					if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
				?>
			></td>
		</tr>
	</table>
</form>
<?php
}
mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>