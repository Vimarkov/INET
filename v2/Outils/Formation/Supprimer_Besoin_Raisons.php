<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Suppression besoin en formation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Production.js"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger()
		{
			window.opener.document.getElementById('formulaire').submit();
			window.close();
		}

		function VerifChamps(Langue)
		{
			if(Langue=="FR")
			{
				if(formulaire.Raison.value==''){alert('Vous n\'avez pas renseigné la raison.');return false;}
			}
			else
			{
				if(formulaire.Raison.value==''){alert('You did not fill in the reason.');return false;}
			}
			return true;
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	$tab=explode(";",$_POST['Id']);
	foreach($tab as $Id){
		
		//MODE SUPPRESSION
		//----------------
		$ReqSupprBesoin="
			UPDATE
				form_besoin
			SET
				Suppr=1,
				Motif_Suppr='Depuis la suppression par un CQP pour une raison précise',
				Id_Personne_MAJ=".$IdPersonneConnectee.",
				Date_MAJ='".date('Y-m-d')."',
				RaisonSuppression='".addslashes($_POST['Raison'])."'
			WHERE
				Id=".$Id;
		$ResultSupprBesoin=mysqli_query($bdd,$ReqSupprBesoin);
		
		//Suppression des qualifications créées dans la gestion des compétences suite au besoin généré, uniquement si relation non traité
		$ReqSupprRelation="UPDATE new_competences_relation 
				SET Suppr=1 
				WHERE Evaluation='B' 
				AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
					OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
				)
				AND Id_Besoin=".$Id;
		$ResultSupprRelation=mysqli_query($bdd,$ReqSupprRelation);
	
	}
	echo "<script>FermerEtRecharger();</script>";
}
?>
<form id="formulaire" method="POST" action="Supprimer_Besoin_Raisons.php" onSubmit="return VerifChamps('<?php echo $LangueAffichage ?>');">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td><input type="hidden" id="Id" name="Id" value="<?php echo $_SESSION['Besoin_Suppr']; ?>" /></td>
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($LangueAffichage=="FR"){echo "Raison de la suppression";}else{echo "Reason for deletion ";} ?> :
			</td>
			<td style="width:70%;" colspan="3">
				<textarea name="Raison" id="Raison" cols="40" style="resize:none;"></textarea>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php

mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
