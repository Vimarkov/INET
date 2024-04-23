<!DOCTYPE html>
<html>
	<head>
		<title>AAA</title><meta name="robots" content="noindex">
		<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
		<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Dosis'><link rel="stylesheet" href="../style.css">
		<link href="../css/FeuilleMobile.css" rel="stylesheet" type="text/css">
		<!-- HTML5 Shim -->
		<!--[if lt IE 9]><script src="../JS/html5.js"></script><![endif]-->		
		<!-- Modernizr -->
		<script src="../JS/modernizr.js"></script>
		<!-- jQuery  -->
		<script src="../JS/jquery-1.4.3.min.js"></script>
		<script src="../JS/jquery-ui-1.8.5.min.js"></script>
		<script>
			function OuvreFenetreModif(Id)
			{
				window.location='Modif_Surveillance.php?Id='+Id;
			}
		</script>
	</head>
<body style="background-color:#cccccc;">

<?php
require("../Connexioni.php");
require("../../v2/Outils/Formation/Globales_Fonctions.php");
require("../../v2/Outils/Fonctions.php");
require("../Menu.php");

$AccesQualite=false;
if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,8))){$AccesQualite=true;}

$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='X'
	AND Date_Debut<='".date('Y-m-d')."'
	AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantQualifie=mysqli_num_rows($resultSurQualifie);

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='L'
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantECQualif=mysqli_num_rows($resultSurQualifie);

Ecrire_Code_JS_Init_Date(); 
?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="ConsulterSurveillances.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr bgcolor="#91dfff" >
		<td colspan="3" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;border-style:outset;">
			<span style="font-size:3em;">
			SODA<br>
			</span>
			<span style="font-size:2.5em;">
			<?php if($LangueAffichage=="FR"){echo "Surveillance Opérationnelle Dé-matérialisée Analytique";}else{echo "Digital Adaptive Operational Monitoring";}?>
			</span>
		</td>
	</tr>
	<tr>
		<td height='10'></td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="15%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
				<td width="15%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
				<td width="10%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Année : ";}else{echo "Year  : ";}?></td>
				<td width="20%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Date surveillance : ";}else{echo "Monitoring date  : ";}?></td>
			</tr>
			<tr>
				<td width="15%">
					<select name="plateforme" class="Mobile" onchange="submit();">
					<?php
					$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
							FROM new_competences_plateforme
							WHERE Id<> 11 AND Id<>14
							ORDER BY new_competences_plateforme.Libelle;";
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$changementPlateforme=0;
					$PlateformeSelect = $_SESSION['FiltreSODAConsult_Plateforme'];
					if($_POST)
					{
						$PlateformeSelect=$_POST['plateforme'];
						if($PlateformeSelect<>$_SESSION['FiltreSODAConsult_Plateforme']){$changementPlateforme=1;}
					}
					$_SESSION['FiltreSODAConsult_Plateforme']=$PlateformeSelect;

					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbPlateforme > 0)
					{
						while($row=mysqli_fetch_array($resultPlateforme))
						{
							$selected="";
							if($PlateformeSelect<>"0")
								{if($PlateformeSelect==$row['Id']){$selected="selected";}}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 ?>
					</select></td>
				<td width="20%">
					<select name="prestations" class="Mobile">
						<?php
						$req = "SELECT new_competences_prestation.Id, 
								Libelle,
								IF(Active=0,'[Actif]','[Inactif]') AS Active
								FROM new_competences_prestation
								WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." 
								ORDER BY Active ASC, new_competences_prestation.Libelle;";
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = $_SESSION['FiltreSODAConsult_Prestation'];
						if($changementPlateforme==0)
						{
							if($_POST){$PrestationSelect=$_POST['prestations'];}
						}
						else
						{
							$PrestationSelect=0;
						}
						 $_SESSION['FiltreSODAConsult_Prestation']=$PrestationSelect;
						 
						$Selected = "";
						
						echo "<option value='0' Selected></option>";
						if ($nbPrestation > 0)
						{
							while($row=mysqli_fetch_array($resultPrestation))
							{
								$selected="";
								if($PrestationSelect==$row['Id']){$selected="selected";}
								$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "))." ".$row['Active'];
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($presta)."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
				
				<td width="15%" class="Libelle">
					<?php
						$annee=$_SESSION['FiltreSODAConsult_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						$_SESSION['FiltreSODAConsult_Annee']=$annee;
					?>
					<input onKeyUp="nombre(this)" class="Mobile" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
				</td>
				
				<td width="15%" class="Libelle">
					<?php
						$dateDebut=$_SESSION['FiltreSODAConsult_DateSurveillance'];
						if($_POST){$dateDebut=$_POST['DateSurveillance'];}
						$_SESSION['FiltreSODAConsult_DateSurveillance']=$dateDebut;
					?>
					<input type="date" class="Mobile" name="DateSurveillance"  value="<?php echo $dateDebut; ?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Etat : ";}else{echo "State : ";}?></td>
			</tr>
			<tr>
				<td>
					<select class="Mobile" name="theme" onchange="submit();">
					<?php
					$req = "SELECT soda_theme.Id, soda_theme.Libelle
							FROM soda_theme
							WHERE Suppr=0
							ORDER BY soda_theme.Libelle;";
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$ThemeSelect = $_SESSION['FiltreSODAConsult_Theme'];
					if($_POST){$ThemeSelect=$_POST['theme'];}
					$_SESSION['FiltreSODAConsult_Theme']=$ThemeSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbTheme > 0)
					{
						while($row=mysqli_fetch_array($resultTheme))
						{
							$selected="";
							if($ThemeSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 
					 ?>
					</select>
				</td>
				<td>
					<select name="Questionnaire" class="Mobile">
					<?php
					$req = "SELECT soda_questionnaire.Id, 
							CONCAT(soda_questionnaire.Libelle,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Libelle
							FROM soda_questionnaire
							WHERE soda_questionnaire.Id_Theme =".$ThemeSelect." 
							AND soda_questionnaire.Suppr=0
							ORDER BY 
							soda_questionnaire.Actif,
							soda_questionnaire.Libelle;";
					$resultQuestionnaire=mysqli_query($bdd,$req);
					$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
					
					$QuestionnaireSelect = $_SESSION['FiltreSODAConsult_Questionnaire'];
					if($changementPlateformeTheme==0)
					{
						if($_POST){$QuestionnaireSelect=$_POST['Questionnaire'];}
					}
					else
					{
						$QuestionnaireSelect=0;
					}
					$_SESSION['FiltreSODAConsult_Questionnaire']=$QuestionnaireSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbQuestionnaire > 0)
					{
						while($row=mysqli_fetch_array($resultQuestionnaire))
						{
							$selected="";
							if($QuestionnaireSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 ?>
					</select>
				</td>
				<td colspan="2">
					<select class="Mobile" name="etat" onchange="submit();">
					<?php
						$EtatSelect = $_SESSION['FiltreSODAConsult_Etat'];
						if($_POST){$EtatSelect=$_POST['etat'];}
						$_SESSION['FiltreSODAConsult_Etat']=$EtatSelect;
						$Selected = "";
						?>
						<option value="" selected></option>
						<option value="A VALIDER" <?php if($EtatSelect=="A VALIDER"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "A Valider";}else{echo "Validate";}?></option>
						<option value="Brouillon" <?php if($EtatSelect=="Brouillon"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Brouillon";}else{echo "Draft";}?></option>
						<option value="Clôturé" <?php if($EtatSelect=="Clôturé"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Clôturé";}else{echo "Closed";}?></option>
						<option value="En cours - papier" <?php if($EtatSelect=="En cours - papier"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "En cours - papier";}else{echo "In progress - paper";}?></option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "N° surveillance : ";}else{echo "Monitoring number : ";}?></td>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "N° ActionTracker : ";}else{echo "ActionTracker number : ";}?></td>
			</tr>
			<tr>
				<td>
					<?php
					$numSurveillance = $_SESSION['FiltreSODAConsult_NumSurveillance'];
					if($_POST){$numSurveillance=$_POST['numSurveillance'];}
					$_SESSION['FiltreSODAConsult_NumSurveillance']=$numSurveillance;
					?>
					<input type="texte" class="Mobile" name="numSurveillance"  value="<?php echo $numSurveillance; ?>">
				</td>
				<td>
					<?php
					$numAT = $_SESSION['FiltreSODAConsult_NumAT'];
					if($_POST){$numAT=$_POST['numAT'];}
					$_SESSION['FiltreSODAConsult_NumAT']=$numAT;
					
					$ATNonRenseigne = $_SESSION['FiltreSODAConsult_ATNonRenseigne'];
					if($_POST){if(isset($_POST['ATNonRenseigne'])){$ATNonRenseigne="checked";}else{$ATNonRenseigne="";}}
					$_SESSION['FiltreSODAConsult_ATNonRenseigne']=$ATNonRenseigne;
					?>
					<input type="texte" class="Mobile" name="numAT"  value="<?php echo $numAT; ?>">
				</td>
				<td colspan="2" valign="center" class="LibelleMobile">
					<input type="checkbox" class="Checkbox" name="ATNonRenseigne" <?php if($ATNonRenseigne<>""){echo "checked";} ?>> <?php if($_SESSION['Langue']=="FR"){echo "N° ActionTracker non renseigné";}else{echo "ActionTracker number not filled in ";}?>
				</td>
			</tr>
			<tr>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
				<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
			</tr>
			<tr>
				<td>
					<select name="surveille" class="Mobile">
						<?php
							$req = "SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
									FROM soda_surveillance 
									LEFT JOIN new_rh_etatcivil
									ON soda_surveillance.Id_Surveille=new_rh_etatcivil.Id
									WHERE soda_surveillance.Suppr=0 
									AND Id_Surveille>0
									AND Id_Surveille<>6572
									ORDER BY Nom, Prenom;";
							$resultSurveille=mysqli_query($bdd,$req);
							$nbSurveille=mysqli_num_rows($resultSurveille);
							
							$SurveilleSelect = $_SESSION['FiltreSODAConsult_Surveille'];
							if($_POST){$SurveilleSelect=$_POST['surveille'];}
							$_SESSION['FiltreSODAConsult_Surveille']=$SurveilleSelect;
							$Selected = "";

							echo "<option name='0' value='0' Selected></option>";
							if ($nbSurveille > 0)
							{
								while($row=mysqli_fetch_array($resultSurveille))
								{
									$selected="";
									if($SurveilleSelect==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Nom'])." ".stripslashes($row['Prenom'])."</option>\n";
								}
							 }
						 ?>
					</select>
				</td>
				
				<td>
					<select name="surveillant" class="Mobile">
						<?php
						$req = "SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
									FROM soda_surveillance 
									LEFT JOIN new_rh_etatcivil
									ON soda_surveillance.Id_Surveillant=new_rh_etatcivil.Id
									WHERE soda_surveillance.Suppr=0 
									AND Id_Surveillant>0
									AND Id_Surveillant<>6572
									ORDER BY Nom, Prenom;";
						$resultSurveillant=mysqli_query($bdd,$req);
						$nbSurveillant=mysqli_num_rows($resultSurveillant);
						
						$SurveillantSelect = $_SESSION['FiltreSODAConsult_Surveillant'];
						if($_POST){$SurveillantSelect=$_POST['surveillant'];}
						$_SESSION['FiltreSODAConsult_Surveillant']=$SurveillantSelect;
						$Selected = "";
						echo "<option name='0' value='0' Selected></option>";
						if ($nbSurveillant > 0)
						{
							while($row=mysqli_fetch_array($resultSurveillant))
							{
								$selected="";
								if($SurveillantSelect==$row['Id']){$selected="selected";}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Nom'])." ".stripslashes($row['Prenom'])."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
				<td class="LibelleMobile">
					<?php
					$SurveillanceInfObjectif = $_SESSION['FiltreSODAConsult_InfObjectif'];
					if($_POST){if(isset($_POST['SurveillanceInfObjectif'])){$SurveillanceInfObjectif="checked";}else{$SurveillanceInfObjectif="";}}
					$_SESSION['FiltreSODAConsult_InfObjectif']=$SurveillanceInfObjectif;
					?>
					<input type="checkbox" class="Checkbox" name="SurveillanceInfObjectif" <?php if($SurveillanceInfObjectif<>""){echo "checked";} ?>>
					<?php if($_SESSION['Langue']=="FR"){echo "Surveillance < Objectif";}else{echo "Monitoring < Objective";}?>
				</td>
				<td class="LibelleMobile">
					<?php
					$MonPerimetre = $_SESSION['FiltreSODAConsult_MonPerimetre'];
					if($_POST){if(isset($_POST['MonPerimetre'])){$MonPerimetre="checked";}else{$MonPerimetre="";}}
					$_SESSION['FiltreSODAConsult_MonPerimetre']=$MonPerimetre;
					?>
					<input type="checkbox" class="Checkbox" name="MonPerimetre" <?php if($MonPerimetre<>""){echo "checked";} ?>>
					<?php if($_SESSION['Langue']=="FR"){echo "Mon périmètre";}else{echo "My perimeter";}?>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td align="center" colspan="10">
				<input class="Bouton BoutonMobile" name="BtnRechercher" type="submit" value="<?php if($_SESSION['Langue']=="FR"){echo "Rechercher";}else{echo "Search";}?>">
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" style="font-size:3.5em;">
			<?php
				$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
				$nbAccess=mysqli_num_rows($resAcc);
				
				$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
				$nbSuperAdmin=mysqli_num_rows($resAcc);
				
				$req="SELECT Id FROM soda_theme 
					WHERE Suppr=0 
					AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.") ";
				$resAcc=mysqli_query($bdd,$req);
				$nbGestionnaire=mysqli_num_rows($resAcc);

				$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
				$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));
				
				$req2="	SELECT Id,
						(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
						(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,Id_Prestation,EnFormation,AttestationSurveillance,
						IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						YEAR(DateSurveillance) AS Annee,DATE_FORMAT(DateSurveillance,'%u') AS Semaine,DateSurveillance,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,Id_Surveille,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,Id_Surveillant,
						(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SeuilReussite,
						soda_surveillance.Etat,NumActionTracker,
						ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100) AS Resultat ";
				$req = "FROM soda_surveillance 
						WHERE Suppr=0 
						AND AutoSurveillance=0 
						AND Etat IN ('Clôturé','En cours - papier','Brouillon') ";
				if($numSurveillance <> "")
				{
					$req .= "AND Id =".$numSurveillance." ";
				}
				else
				{
					if ($PlateformeSelect <> 0){$req .= "AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$PlateformeSelect." OR Id_Plateforme=".$PlateformeSelect.") ";}
					if ($PrestationSelect <> 0){$req .= "AND soda_surveillance.Id_Prestation =".$PrestationSelect." ";}
					if ($dateDebut <> ""){$req .= "AND soda_surveillance.DateSurveillance='".TrsfDate_($dateDebut)."' ";}
					if($annee <> ""){$req .= "AND YEAR(soda_surveillance.DateSurveillance) ='".$annee."' ";}
					if($EtatSelect <> ""){$req .= "AND soda_surveillance.Etat ='".$EtatSelect."' ";}
					if ($ThemeSelect <> 0)
					{
						$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$ThemeSelect." ";
						if($QuestionnaireSelect <> 0){$req .= "AND Id_Questionnaire =".$QuestionnaireSelect." ";}
					}
					if ($SurveilleSelect <> 0){$req .= "AND soda_surveillance.Id_Surveille =".$SurveilleSelect." ";}
					if ($SurveillantSelect <> 0){$req .= "AND soda_surveillance.Id_Surveillant =".$SurveillantSelect." ";}
					if($ATNonRenseigne<>""){$req .= "AND soda_surveillance.NumActionTracker ='' ";}
					if ($numAT <> ""){
						$req .= "AND soda_surveillance.NumActionTracker ='".$numAT."' ";
					}
					elseif($SurveillanceInfObjectif<>""){
						$req .= "AND ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)<(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AND Etat='Clôturé' ";
					}
					if($MonPerimetre<>""){
						if($nbAccess>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme))){
							
						}
						else{
							$req.="AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (
								SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.")
							)
							OR 
							Id_Prestation IN (
								SELECT Id_Prestation
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION['Id_Personne']."
								AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
								)
							)
							";
						}
					}
				}
				$reqOrder = " ORDER BY DateSurveillance DESC, Id DESC ";
				$reqAnalyse="SELECT soda_surveillance.Id ";

				$resultSurveillance=mysqli_query($bdd,$reqAnalyse.$req);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);

				$nombreDePages=ceil($nbSurveillance/50);
				if(isset($_GET['Page'])){$_SESSION['Page']=$_GET['Page'];}
				if($_SESSION['Page']>$nombreDePages){$_SESSION['Page']="0";}
				$reqLimit=" LIMIT ".($_SESSION['Page']*50).",50";
				
				$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder.$reqLimit);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);
				
				$nbPage=0;
				if($_SESSION['Page']>1){echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['Page']<=5){$valeurDepart=1;}
				elseif($_SESSION['Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
				else{$valeurDepart=$_SESSION['Page']-5;}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
				{
					if($i<=$nombreDePages)
					{
						if($i==($_SESSION['Page']+1)){echo "<b> [ ".$i." ] </b>";}	
						else{echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Page=".($i-1)."'>".$i."</a> </b>&nbsp;";}
					}
				}
				if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetencesMobile" rowspan="3" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "N°";}else{echo "N°";}?></td>
					<td class="EnTeteTableauCompetencesMobile" width="25%"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?></td>
					<td class="EnTeteTableauCompetencesMobile" width="35%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td class="EnTeteTableauCompetencesMobile" width="15%">Date</td>
					<td class="EnTeteTableauCompetencesMobile" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillé";}else{echo "Supervised";}?></td>
				</tr>
				<tr>	
					<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td class="EnTeteTableauCompetencesMobile" colspan='2'>Questionnaire</td>
					<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";}?></td>
					<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Note";}else{echo "Score";}?></td>
					<td class="EnTeteTableauCompetencesMobile" colspan="3"><?php if($_SESSION["Langue"]=="FR"){echo "N° Action Tracker";}else{echo "Action Tracker number";}?></td>
				</tr>
				<?php
					if($nbSurveillance > 0)
					{
						$couleur="#ffffff";
						while($rowSurveillance=mysqli_fetch_array($resultSurveillance))
						{
							$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
							
							echo "<tr style='background-color:".$couleur."' >";
								echo "<td class='LigneMobile' rowspan='3'>";
								
								if($AccesQualite 
								|| DroitsFormationPrestationV2(array($rowSurveillance['Id_Prestation']),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)) 
								|| $nbAccess>0 
								|| $nbSuperAdmin>0 
								|| $nbGestionnaire>0	
								|| $rowSurveillance['Id_Surveillant']==$_SESSION['Id_Personne']
								|| ($rowSurveillance['Etat']=="Clôturé" && $rowSurveillance['Id_Surveillant']<>$_SESSION['Id_Personne'] && $rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0)
								){
									echo '<a style="color:#3e65fa;" href="javascript:OuvreFenetreModif('.$rowSurveillance['Id'].');">';
								}
								echo $rowSurveillance['Id'];
								if($AccesQualite 
								|| DroitsFormationPrestationV2(array($rowSurveillance['Id_Prestation']),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)) 
								|| $nbAccess>0 
								|| $nbSuperAdmin>0 
								|| $nbGestionnaire>0	
								|| $rowSurveillance['Id_Surveillant']==$_SESSION['Id_Personne']
								|| ($rowSurveillance['Etat']=="Clôturé" && $rowSurveillance['Id_Surveillant']<>$_SESSION['Id_Personne'] && $rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0)
								){
									echo '</a>';
								}
								
								$etat=$rowSurveillance['Etat'];
								if($_SESSION["Langue"]=="EN"){
									if($rowSurveillance['Etat']=="Clôturé"){
										$etat="Closed";
										if($rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0){
											$etat="Validate";
										}
									}
									if($rowSurveillance['Etat']=="En cours - papier"){$etat="In progress - paper";}
									if($rowSurveillance['Etat']=="Brouillon"){$etat="Draft";}
								}
								else{
									if($rowSurveillance['Etat']=="Clôturé"){
										if($rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0){
											$etat="A Valider";
										}
									}
								}
								echo "</td>";
								echo "<td class='LigneMobile'>".$rowSurveillance['Plateforme']."</td>";
								echo "<td class='LigneMobile'>".$presta."</td>";
								echo "<td class='LigneMobile'>".AfficheDateJJ_MM_AAAA($rowSurveillance['DateSurveillance'])."</td>";
								echo "<td class='LigneMobile' style='font-weight:bold;'>".$rowSurveillance['Surveille']."</td>";
							echo "</tr>";
							echo "<tr style='background-color:".$couleur."' >";
								echo "<td class='LigneMobile'>".$rowSurveillance['Theme']."</td>";
								echo "<td class='LigneMobile' colspan='2'>".$rowSurveillance['Questionnaire']."</td>";
								echo "<td class='LigneMobile'>".$rowSurveillance['Surveillant']."</td>";
							echo "</tr>";
							echo "<tr style='background-color:".$couleur."' >";

								echo "<td class='LigneMobile'>".$etat."</td>";
								if($rowSurveillance['Etat']=="Clôturé"){
									if($rowSurveillance['Resultat']<$rowSurveillance['SeuilReussite']){
										echo "<td class='LigneMobile' style='color:red;'>".$rowSurveillance['Resultat']." %</td>";
									}
									else{
										echo "<td class='LigneMobile' style='font-weight:bold;'>".$rowSurveillance['Resultat']." %</td>";
									}
								}
								else{
									echo "<td class='LigneMobile'></td>";
								}
								echo "<td class='LigneMobile' colspan='3'>".$rowSurveillance['NumActionTracker']."</td>";
							echo "</tr>";
							
							if($couleur=="#ffffff"){$couleur="#b7dfe3";}
							else{$couleur="#ffffff";}
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr><td height="150"></td></tr>
</table>
</form>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script><script  src="../script.js"></script>
</body>
</html>