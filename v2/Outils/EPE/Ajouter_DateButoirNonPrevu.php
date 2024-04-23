<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
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
	</script>
</head>
<body>

<?php
if($_POST)
{	
	if($_POST['personne']<>0 || TrsfDate_($_POST['dateButoir'])>"0001-01-01"){
		$Req="INSERT INTO epe_personne_datebutoir (Id_Personne,DateCreation,Id_Createur,TypeEntretien,DateButoir) VALUES (".$_POST['personne'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".$_SESSION['FiltreEPEDateButoir_TypeEPE']."','".TrsfDate_($_POST['dateButoir'])."')";
		$Result=mysqli_query($bdd,$Req);
		echo $Req;
	}
	echo "<script>FermerEtRecharger();</script>";
}
?>
<form id="formulaire" method="POST" action="Ajouter_DateButoirNonPrevu.php" onSubmit="return VerifChamps('<?php echo $LangueAffichage ?>');">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> :
				<?php echo $_SESSION['FiltreEPEDateButoir_TypeEPE']; ?>
			</td>
				
		</tr>
		<tr>
			<td valign="top" width="15%" class="Libelle">
				<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" name="personne">
					<option value='0'></option>
					<?php
						$requete="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						";
						$requete.="FROM new_rh_etatcivil 
							WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE')
							AND MetierPaie<>'' AND Cadre IN (0,1) 
							AND new_rh_etatcivil.Id<>1739						
							AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
						WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
						AND (
								(
									SELECT COUNT(new_competences_personne_prestation.Id)
									FROM new_competences_personne_prestation
									LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
									WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
									AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
									AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
									AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
								)>0
							) 
						AND (SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
						AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TAB.TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."')=0
						ORDER BY Personne
							";
					
						$resultPersonne=mysqli_query($bdd,$requete);
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($LangueAffichage=="FR"){echo "Date butoir";}else{echo "Deadline";}?> :
				<input type="date" style="text-align:center;width:130px;" id="dateButoir" name="dateButoir" value="">
			</td>
				
		</tr>
		<tr class="TitreColsUsers">
			<td align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php

mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
