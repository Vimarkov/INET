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
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requete="
            INSERT INTO new_surveillances_surveillance
                (
                ID_Questionnaire,
                ID_Prestation,
                ID_Surveillant,
                ID_Surveille,
                DatePlanif,
                Etat
                )
            VALUES
                (".
                $_POST['Id_Questionnaire'].",".
                $_POST['Id_Prestation'].",".
                $_POST['Id_Surveillant'].",".
                $_POST['Id_Surveille'].",
                '".TrsfDate($_POST['DatePlanif'])."',
                'Planifié'
                )";
	}
	elseif($_POST['Mode']=="Modif")
	{
		$resultSurveillance=mysqli_query($bdd,"SELECT ID,ID_Questionnaire,ID_Prestation,ID_Surveillant,ID_Surveille,DatePlanif,Etat,DateReplanif FROM new_surveillances_surveillance WHERE ID=".$_POST['Id']);
		$LigneSurveillance=mysqli_fetch_array($resultSurveillance);
		
		$requete="
            UPDATE
                new_surveillances_surveillance
            SET
                ID_Questionnaire=".$_POST['Id_Questionnaire'].",
                ID_Prestation=".$_POST['Id_Prestation'].",
                ID_Surveillant=".$_POST['Id_Surveillant'].",
                ID_Surveille=".$_POST['Id_Surveille'];
		
		if($LigneSurveillance['DatePlanif'] != TrsfDate_($_POST['DatePlanif']) || $LigneSurveillance['DateReplanif'] >"0001-01-01")
		{
			$requete.=", DateReplanif='".TrsfDate($_POST['DatePlanif'])."',";
			$requete.="Etat='Replanifié'";
		}
		$requete.=" WHERE ID=".$_POST['Id'];
	}
	$result=mysqli_query($bdd,$requete);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$requeteSurveillance="
                SELECT
                    ID,
                    ID_Questionnaire,
                    (SELECT ID_Theme FROM new_surveillances_questionnaire WHERE new_surveillances_surveillance.ID_Questionnaire=new_surveillances_questionnaire.ID) as IDTHEME,
                    (SELECT ID_Plateforme FROM new_surveillances_questionnaire WHERE new_surveillances_surveillance.ID_Questionnaire=new_surveillances_questionnaire.ID) as IDPLATEFORMEQUESTIONNAIRE,
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
		}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

		<form id="formulaire" method="POST" action="Ajout_Surveillance.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $LigneSurveillance['ID'];}?>">
		<input type="hidden" id="Id_Prestation_Initiale" value="<?php if($_GET['Mode']=="Modif"){echo $LigneSurveillance['ID_Prestation'];}?>">
		<input type="hidden" id="Id_Questionnaire_Initial" value="<?php if($_GET['Mode']=="Modif"){echo $LigneSurveillance['ID_Questionnaire'];}elseif(isset($_GET['Id_Questionnaire'])){echo $_GET['Id_Questionnaire'];}?>">
		<input type="hidden" id="Id_Surveillant_Initial" value="<?php if($_GET['Mode']=="Modif"){echo $LigneSurveillance['ID_Surveillant'];}?>">
		<input type="hidden" id="Id_Surveille_Initial" value="<?php if($_GET['Mode']=="Modif"){echo $LigneSurveillance['ID_Surveille'];}?>">
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Type : </td>
				<td>
					<select name="Type" id="Type" onchange="Change_Type();">
						<option value="Générique" <?php if($_GET['Mode']=="Modif" && $LigneSurveillance['IDPLATEFORMEQUESTIONNAIRE']==0){echo " selected";}elseif(isset($_GET['ID_Plateforme'])){if($_GET['ID_Plateforme']==0){echo " selected";}} ?>>Générique</option>
						<option value="Spécifique" <?php if($_GET['Mode']=="Modif" && $LigneSurveillance['IDPLATEFORMEQUESTIONNAIRE']>0){echo " selected";}elseif(isset($_GET['ID_Plateforme'])){if($_GET['ID_Plateforme']>0){echo " selected";}} ?>>Spécifique</option>
					</select>
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
					<select name="Id_Theme_Questionnaire" id="Id_Theme_Questionnaire" onchange="Recharge_Liste_Questionnaire();">
					<?php
					$result2=mysqli_query($bdd,"SELECT ID, Nom FROM new_surveillances_theme ORDER BY Nom ASC");
					while($row2=mysqli_fetch_array($result2))
					{
						if(($_GET['Mode']=="Modif" && $LigneSurveillance['IDTHEME']==$row2['ID']) || $_GET['Mode']<>"Modif"){
							echo "<option value='".$row2['ID']."'";
							if($_GET['Mode']=="Modif"){if($LigneSurveillance['IDTHEME']==$row2['ID']){echo " selected";}}
							elseif(isset($_GET['Id_Theme'])){if($_GET['Id_Theme']==$row2['ID']){echo " selected";}}
							echo ">".$row2['Nom']."</option>\n";
						}
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Date :</td>
				<td>
					<input id="DatePlanif" type="date" name="DatePlanif" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneSurveillance['DatePlanif']);}elseif(isset($_GET['DateAdd'])){echo AfficheDateFR($_GET['DateAdd']);} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers" id="Plateforme_Questionnaire2">
				<td>Questionnaire : </td>
				<td>
					<div id="Questionnaire">
						<select size="1" name="Id_Questionnaire"></select>
					</div>
					<?php
					$requete_Questionnaire="SELECT ID, ID_Plateforme, ID_Theme, CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom FROM new_surveillances_questionnaire WHERE Supprime=0 ORDER BY Actif, Nom ASC";
					$result_Questionnaire= mysqli_query($bdd,$requete_Questionnaire) or die ("Select impossible");
					$i=0;
					while ($row_Questionnaire=mysqli_fetch_row($result_Questionnaire))
					{
						 echo "<script>Liste_Questionnaire_Theme_Plateforme[".$i."] = new Array(".$row_Questionnaire[0].",".$row_Questionnaire[1].",".$row_Questionnaire[2].",'".addslashes($row_Questionnaire[3])."');</script>\n";
						 $i+=1;
					}
					?>
				</td>
			</tr>
			
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Entité :";}
						else{echo "Entity :";}
					?>
				</td>
				<td>
					<select id="Id_Plateforme" name="Id_Plateforme" onchange="Recharge_Liste_Prestation_Personne();">
					<?php
					$req="SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ";
					if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
						
					}
					else{
						$req.="AND (Id IN (
							SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
						)
						OR 
						Id IN (
							SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
						)
						)";
					}
					$req.="ORDER BY Libelle ASC";
					$result2=mysqli_query($bdd,$req);
					while($row2=mysqli_fetch_array($result2))
					{
						echo "<option value='".$row2['Id']."'";
						if($_GET['Mode']=="Modif"){if($LigneSurveillance['IDPLATEFORMEPRESTATION']==$row2['Id']){echo " selected";}}
						elseif(isset($_GET['ID_Plateforme2'])){if($_GET['ID_Plateforme2']>0){if($_GET['ID_Plateforme2']==$row2['Id']){echo " selected";}}}
						echo ">".$row2['Libelle']."</option>\n";
					}
					?>
					</select>
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
					<div id="Prestation">
						<select size="1" name="Id_Prestation"></select>
					</div>
					<?php
					$requete_Prestation="SELECT Id, Id_Plateforme, Libelle FROM new_competences_prestation WHERE Active=0 ";
					if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
						
					}
					else{
						$requete_Prestation.="AND (Id_Plateforme IN (
							SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
						)
						OR 
						Id IN (
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
							)
						) ";
					}
					$requete_Prestation.="ORDER BY Libelle ASC";
					$result_Prestation= mysqli_query($bdd,$requete_Prestation) or die ("Select impossible");
					$i=0;
					while ($row_Prestation=mysqli_fetch_row($result_Prestation))
					{
						 echo "<script>Liste_Plateforme_Prestation[".$i."] = new Array('".$row_Prestation[0]."','".$row_Prestation[1]."','".addslashes($row_Prestation[2])."');</script>\n";
						 $i+=1;
					}
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
					$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, new_competences_personne_plateforme.Id_Plateforme, CONCAT(Nom, ' ', Prenom) as NomPrenom
									FROM new_rh_etatcivil
									LEFT JOIN new_competences_personne_plateforme 
									ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
									ORDER BY NomPrenom ASC";
					$result_Personne= mysqli_query($bdd,$requetePersonne) or die ("Select impossible");
					$i=0;
					while ($row_Personne=mysqli_fetch_row($result_Personne))
					{
						 echo "<script>Liste_Plateforme_Personne[".$i."] = new Array('".$row_Personne[0]."','".$row_Personne[1]."','".addslashes($row_Personne[2])."');</script>\n";
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
							if($_GET['Mode']=="Modif")
							{
								if($_SESSION['Langue']=="FR"){echo "value='Valider'";}
								else{echo "value='Validate'";}
							}
							else
							{
								if($_SESSION['Langue']=="FR"){echo "value='Ajouter'";}
								else{echo "value='Add'";}
							}
						?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"DELETE FROM new_surveillances_surveillance WHERE ID=".$_GET['Id']);
		$result=mysqli_query($bdd,"DELETE FROM new_surveillances_surveillance_question WHERE ID_Surveillance=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	echo "<script>Recharge_Liste_Prestation_Personne();</script>";
	echo "<script>Recharge_Liste_Questionnaire();</script>";
	echo "<script>Change_Type();</script>";
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>