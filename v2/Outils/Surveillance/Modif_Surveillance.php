<html>
<head>
	<title>Surveillances - Surveillance</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" language="Javascript" src="AjoutSurveillance3.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST)
{
	
	$resultSurveillance=mysqli_query($bdd,"SELECT ID,ID_Questionnaire,ID_Prestation,ID_Surveillant,ID_Surveille,DatePlanif,Etat,DateReplanif FROM new_surveillances_surveillance WHERE ID=".$_POST['Id']);
	$LigneSurveillance=mysqli_fetch_array($resultSurveillance);
	
	$requete="
		UPDATE
			new_surveillances_surveillance
		SET
			ID_Surveillant=".$_POST['Id_Surveillant'].",
			ID_Surveille=".$_POST['Id_Surveille'];
	
	if($LigneSurveillance['DatePlanif'] != TrsfDate_($_POST['DatePlanif']) || $LigneSurveillance['DateReplanif'] >"0001-01-01")
	{
		$requete.=", DateReplanif='".TrsfDate($_POST['DatePlanif'])."',";
		$requete.="Etat='Replanifié'";
	}
	$requete.=" WHERE ID=".$_POST['Id'];

	$result=mysqli_query($bdd,$requete);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$requeteSurveillance="
		SELECT
			ID,
			ID_Questionnaire,
			(SELECT ID_Theme FROM new_surveillances_questionnaire WHERE new_surveillances_surveillance.ID_Questionnaire=new_surveillances_questionnaire.ID) as IDTHEME,
			(SELECT (SELECT Nom FROM new_surveillances_theme WHERE ID=ID_Theme) FROM new_surveillances_questionnaire WHERE new_surveillances_surveillance.ID_Questionnaire=new_surveillances_questionnaire.ID) AS Theme,
			(SELECT CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom FROM new_surveillances_questionnaire WHERE new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id) AS Questionnaire,
			(SELECT ID_Plateforme FROM new_surveillances_questionnaire WHERE new_surveillances_surveillance.ID_Questionnaire=new_surveillances_questionnaire.ID) as IDPLATEFORMEQUESTIONNAIRE,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id) AS Prestation,
			ID_Prestation,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_surveillances_surveillance.ID_Prestation=new_competences_prestation.Id) as IDPLATEFORMEPRESTATION,
			ID_Surveillant,
			ID_Surveille,
			IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DatePlanif,
			Etat
		FROM
			new_surveillances_surveillance
		WHERE
			ID=".$_GET['Id'];
	$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
	$LigneSurveillance=mysqli_fetch_array($resultSurveillance);
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

		<form id="formulaire" method="POST" action="Modif_Surveillance.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Id" value="<?php echo $LigneSurveillance['ID'];?>">
		<input type="hidden" id="Id_Plateforme" value="<?php echo $LigneSurveillance['IDPLATEFORMEPRESTATION']; ?>">
		<input type="hidden" id="Id_Prestation_Initiale" value="<?php echo $LigneSurveillance['ID_Prestation'];?>">
		<input type="hidden" id="Id_Surveillant_Initial" value="<?php echo $LigneSurveillance['ID_Surveillant'];?>">
		<input type="hidden" id="Id_Surveille_Initial" value="<?php echo $LigneSurveillance['ID_Surveille'];?>">
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Type : </td>
				<td>
					<?php 
						if($LigneSurveillance['IDPLATEFORMEQUESTIONNAIRE']==0){echo "Générique";}
						else{echo "Spécifique";}
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Thématique :";}
						else{echo "Theme :";}
					?>
				</td>
				<td>
					<?php
						echo $LigneSurveillance['Theme'];					
					?>
				</td>
			</tr>
			<tr>
				<td>Date :</td>
				<td>
					<input id="DatePlanif" type="date" name="DatePlanif" size="10" value="<?php echo AfficheDateFR($LigneSurveillance['DatePlanif']); ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers" id="Plateforme_Questionnaire2">
				<td>Questionnaire : </td>
				<td>
					<?php
						echo $LigneSurveillance['Questionnaire'];					
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers" style="display:none;">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Prestation :";}
						else{echo "Activity :";}
					?>
				</td>
				<td>
					<div id="Prestation">
						<select size="1" name="Id_Prestation"></select>
					</div>
					<?php
					$requete_Prestation="SELECT Id, Id_Plateforme, Libelle FROM new_competences_prestation WHERE Active=0 ORDER BY Libelle ASC";
					$result_Prestation= mysqli_query($bdd,$requete_Prestation) or die ("Select impossible");
					$i=0;
					while ($row_Prestation=mysqli_fetch_row($result_Prestation))
					{
						 echo "<script>Liste_Plateforme_Prestation[".$i."] = new Array(".$row_Prestation[0].",".$row_Prestation[1].",'".addslashes($row_Prestation[2])."');</script>\n";
						 $i+=1;
					}
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Prestation :";}
						else{echo "Activity :";}
					?>
				</td>
				<td>
					<?php
						echo $LigneSurveillance['Prestation'];					
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Surveillant :";}
						else{echo "Supervisor :";}
					?>
				</td>
				<td>
					<div id="Surveillant">
						<select size="1" name="Id_Surveillant"></select>
					</div>
					<?php
					$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, new_competences_personne_plateforme.Id_Plateforme, CONCAT(Nom, ' ', Prenom) as NomPrenom";
					$requetePersonne.=" FROM new_rh_etatcivil";
					$requetePersonne.=" INNER JOIN new_competences_personne_plateforme ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne";
					$requetePersonne.=" ORDER BY NomPrenom ASC";
					$result_Personne= mysqli_query($bdd,$requetePersonne) or die ("Select impossible");
					$i=0;
					while ($row_Personne=mysqli_fetch_row($result_Personne))
					{
						 echo "<script>Liste_Plateforme_Personne[".$i."] = new Array(".$row_Personne[0].",".$row_Personne[1].",'".addslashes($row_Personne[2])."');</script>\n";
						 $i+=1;
					}
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Surveillé :";}
						else{echo "Supervised :";}
					?>
				</td>
				<td>
					<div id="Surveille">
						<select size="1" name="Id_Surveille"></select>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
						<?php
							if($_SESSION['Langue']=="FR"){echo "value='Valider'";}
							else{echo "value='Validate'";}
						?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
echo "<script>Recharge_Liste_Prestation_Personne();</script>";
}
?>
	
</body>
</html>