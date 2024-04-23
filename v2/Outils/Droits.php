<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Droits</title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function Fermer(){window.close();}
	</script>
</head>
<body>

<?php
require("Connexioni.php");

function Ecrire_Ligne_Droits($Classe,$Dossiers)
{
    global $bdd;
    
    $Droit_Aucun="";
    $Droit_Lecture="";
    $Droit_Ecriture="";
    $Droit_Administrateur="";
    
    $Espaces="&nbsp;&nbsp;";
    if($Classe == "Petit"){$Espaces.="&nbsp;&nbsp;";}
    $TableauDossiers=explode("|",$Dossiers);
    $TexteAffichage=$TableauDossiers[1];
    if($TableauDossiers[2] != ""){$TexteAffichage=$TableauDossiers[2];}
    
    $result=mysqli_query($bdd,"SELECT * FROM new_acces WHERE Login='".$_GET['Id']."' AND Page='".$TableauDossiers[0]."' AND Dossier1='".$TableauDossiers[1]."'  AND Dossier2='".$TableauDossiers[2]."'");
    $row=mysqli_fetch_array($result);
    $nb=mysqli_num_rows($result);
    
    if($nb==0){$Droit_Aucun=" selected ";}
    if($row['Droits']=="Lecture"){$Droit_Lecture=" selected";}
    if($row['Droits']=="Ecriture"){$Droit_Ecriture=" selected";}
    if($row['Droits']=="Administrateur"){$Droit_Administrateur=" selected";}
    
    echo "          <tr>
                        <td class='".$Classe."Droits'>".$Espaces.$TexteAffichage."</td>
                        <td>
                            <select name='".$Dossiers."'>
                				<option".$Droit_Aucun.">Aucun</option>
                				<option".$Droit_Lecture.">Lecture</option>
                                <option".$Droit_Ecriture.">Ecriture</option>
                                <option".$Droit_Administrateur.">Administrateur</option>
                			</select>
                		</td>
                	</tr>";
}

if($_POST)
{
    //Suppression de tous les droits dans un premier temps
	$ResultSuppressionDroits=mysqli_query($bdd,"DELETE FROM new_acces WHERE Login='".$_POST['Login']."'");
	
	//Boucle sur les POSTS pour rajouter tous les droits attribués dans une second temps
	foreach( $_POST as $Clef=>$Valeur)
	{
	    if($Clef != "Login" && $Clef != "modeInterim" && $Valeur != "Aucun")
	    {
	       $Dossiers=explode("|",$Clef);
	       $RequeteAjoutDroits="
                INSERT INTO new_acces
                    (Droits,Login,Page,Dossier1,Dossier2)
                VALUES
                    ('".$Valeur."','".$_POST['Login']."','".$Dossiers[0]."','".$Dossiers[1]."','".$Dossiers[2]."')";
	       //echo $RequeteAjoutDroits."<br>";
	       $ResultAjoutDroits=mysqli_query($bdd,$RequeteAjoutDroits);
	    }
	}
	echo "<script>Fermer();</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" method="POST" action="Droits.php">
	<input type="hidden" name="Login" value="<?php echo $_GET['Id'];?>">
	<table style="width:95%; height:95%; border-spacing:0; align:center;">
		<tr>
			<td valign="top">
				<table style="width:100%; border-spacing:0;">
	
					<tr><td colspan="2" class="GrandDroits">AAA Canada</td></tr>
					<tr><td class="MoyenDroits">&nbsp;&nbsp;ECME – Calibration</td></tr>
				    <?php 
				       
                        Ecrire_Ligne_Droits("Petit","canada|ECMECalibration|Contrat");
						Ecrire_Ligne_Droits("Petit","canada|ECMECalibration|FicheVie");
						Ecrire_Ligne_Droits("Petit","canada|ECMECalibration|NormeCalibration");
						Ecrire_Ligne_Droits("Petit","canada|ECMECalibration|SuiviExpiration");
						Ecrire_Ligne_Droits("Petit","canada|ECMECalibration|RapportEtalonnage");
						Ecrire_Ligne_Droits("Petit","canada|ECMECalibration|RapportNonConformites");
                    ?>
					<tr><td class="MoyenDroits">&nbsp;&nbsp;Reporting</td></tr>
				    <?php 
				       
                        Ecrire_Ligne_Droits("Petit","canada|Reporting|M01");
						Ecrire_Ligne_Droits("Petit","canada|Reporting|M02");
						Ecrire_Ligne_Droits("Petit","canada|Reporting|R01");
						Ecrire_Ligne_Droits("Petit","canada|Reporting|R03");
						Ecrire_Ligne_Droits("Petit","canada|Reporting|R04");
						Ecrire_Ligne_Droits("Petit","canada|Reporting|S02");
						Ecrire_Ligne_Droits("Petit","canada|Reporting|S03");
                    ?>
				    <tr><td class="MoyenDroits">&nbsp;&nbsp;Formations</td></tr>
				    <?php 
				       
                        Ecrire_Ligne_Droits("Petit","canada|Training|EBTT");
                        Ecrire_Ligne_Droits("Petit","canada|Training|EFTT");
						Ecrire_Ligne_Droits("Petit","canada|Training|Planning");
						Ecrire_Ligne_Droits("Petit","canada|Training|SBTT");
                        Ecrire_Ligne_Droits("Petit","canada|Training|SFTT");
						Ecrire_Ligne_Droits("Petit","canada|Training|Training");
                    ?>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;Qualité-OPEX</td></tr>
					<?php
						Ecrire_Ligne_Droits("Petit","canada|QUALITE_OPEX|DQ506"); //Attention correspond a D-0601
						Ecrire_Ligne_Droits("Petit","canada|QUALITE_OPEX|Ecme");
						Ecrire_Ligne_Droits("Petit","canada|QUALITE_OPEX|SpecificDocumentation");
						Ecrire_Ligne_Droits("Petit","canada|QUALITE_OPEX|Audit");
						Ecrire_Ligne_Droits("Petit","canada|QUALITE_OPEX|FormationSMQ");
					?>
					
					
				</table>
			</td>
			<td valign="top">
				<table style="width:100%; border-spacing:0;">
					<tr><td colspan="2" class="GrandDroits">AAA Canada</td></tr>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;BU-Ontario (Qualité)</td></tr>
					<?php
						Ecrire_Ligne_Droits("Petit","canada|BU_Ontario|PolyvalenceTables");
						Ecrire_Ligne_Droits("Petit","canada|BU_Ontario|OTD_OQD");
						Ecrire_Ligne_Droits("Petit","canada|BU_Ontario|CustomerSatisfaction");
						Ecrire_Ligne_Droits("Petit","canada|BU_Ontario|QualityPCS");
						Ecrire_Ligne_Droits("Petit","canada|BU_Ontario|Other");
					?>
					<tr><td class="MoyenDroits">&nbsp;&nbsp;Opérations</td></tr>
					<?php
                        Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-MIR_STL_G7_OSW");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PIT_BA_EWIS");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL3_BAMX_CHL");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL3_MHI_CHL_STR");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_AER_A220");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_AIR_A220_MET");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_BAMX_CRJ");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_CSALP_A220_AVR");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_ITT_A220");
                        Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_LAT_CRJ");
						Ecrire_Ligne_Droits("Petit","canada|Operations|QUEBEC-PL8_MIRABEL");
						Ecrire_Ligne_Droits("Petit","canada|Operations|AnalyseRisque");
				    ?>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;RECRUTEMENT</td></tr>
					<?php
						Ecrire_Ligne_Droits("Petit","canada|RECRUTEMENT|");
					?>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;Expérience Employé</td></tr>
					<?php
						Ecrire_Ligne_Droits("Petit","canada|EXPERIENCE_EMPLOYE|HRDocumentation");
						Ecrire_Ligne_Droits("Petit","canada|EXPERIENCE_EMPLOYE|KitNouveauGestionnaire");
					?>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;HSE</td></tr>		
					<?php
						Ecrire_Ligne_Droits("Petit","canada|HSE|Comite");
						Ecrire_Ligne_Droits("Petit","canada|HSE|Accident");
					?>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;Gestion Documentaire</td></tr>
					<?php
						Ecrire_Ligne_Droits("Petit","canada|GestionDocumentaire|DocApp");
						Ecrire_Ligne_Droits("Petit","canada|GestionDocumentaire|GestionDocumentaire");
				    ?>
					
					<tr><td class="MoyenDroits">&nbsp;&nbsp;Mission Handicap</td></tr>
					<?php
						Ecrire_Ligne_Droits("Petit","missionhandicap|missionhandicap|");
					?>
				</table>
			</td>
		</tr>
		<tr height="35" valign="middle">
			<td colspan="4" align="center"><input class="Bouton" type="submit" value="Valider"></td>
		</tr>
	</table>
	</form>
<?php
}
	//mysqli_free_result($result);	// Libération des résultats}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>