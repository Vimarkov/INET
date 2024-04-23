<html>
<head>
	<title>AAA</title><meta name="robots" content="noindex">
	<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../css/FeuilleMobile.css" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Dosis'><link rel="stylesheet" href="../style.css">
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
			window.location='LancerSurveillance.php?Id='+Id;
		}
		function OuvreFenetreModifP(Id)
		{
			window.location='LancerSurveillanceProcessus.php?Id='+Id;
		}
		function OuvreFenetreSupprSurveillancePlanifiee(Page,Id,Volume){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
				window.open(Page+"?Mode=S&Id="+Id+"&Volume="+Volume,"Page","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			}
		}
	</script>
</head>
<body style="background-color:#cccccc;">

<?php
require("../Connexioni.php");
require("../../v2/Outils/Formation/Globales_Fonctions.php");
require("../../v2/Outils/Fonctions.php");
require("../Menu.php");

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
?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="Accueil.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
	<table style="width:100%; border-spacing:0; align:center;">
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
		<tr>
			<td width="100%" colspan="3">
				<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
					<tr><td height="4"></td></tr>
					<tr>
						<td width="10%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Année : ";}else{echo "Year  : ";}?></td>
						<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
						<td class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
					</tr>
					<tr>
						<td width="15%" class="Libelle">
							<select id="annee" name="annee" class="Mobile" onchange="submit();">
							<?php
								$annee=$_SESSION['FiltreSODA_Annee'];
								if($_POST){
									if(isset($_POST['annee'])){
										$annee=$_POST['annee'];
									}
								}
								$_SESSION['FiltreSODA_Annee']=$annee;
								
								for($i=2022;$i<=date('Y')+1;$i++){
									$selected="";
									if($i==$_SESSION['FiltreSODA_Annee']){$selected="selected";}
									echo "<option value='".$i."' ".$selected.">".$i."</option>";
								}
							 ?>
							</select>
						</td>
						<td>
							<select class="Mobile" name="theme" onchange="submit();">
							<?php
							$req = "SELECT soda_theme.Id, soda_theme.Libelle
									FROM soda_theme
									WHERE Suppr=0
									ORDER BY soda_theme.Libelle;";
							$resultTheme=mysqli_query($bdd,$req);
							$nbTheme=mysqli_num_rows($resultTheme);
							
							$ThemeSelect = $_SESSION['FiltreSODAAccueil_Theme'];
							if($_POST){$ThemeSelect=$_POST['theme'];}
							$_SESSION['FiltreSODAAccueil_Theme']=$ThemeSelect;
							
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
							<select name="Questionnaire" class="Mobile" onchange="submit();">
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
							
							$QuestionnaireSelect = $_SESSION['FiltreSODAAccueil_Questionnaire'];
							if($changementPlateformeTheme==0)
							{
								if($_POST){$QuestionnaireSelect=$_POST['Questionnaire'];}
							}
							else
							{
								$QuestionnaireSelect=0;
							}
							$_SESSION['FiltreSODAAccueil_Questionnaire']=$QuestionnaireSelect;
							
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
					</tr>
					<tr><td height="4"></td></tr>
					<tr>
						<td width="15%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
						<td width="15%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
						<td width="15%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
					</tr>
					<tr>
						<td width="15%">
							<select name="plateforme" class="Mobile" onchange="submit();">
							<?php
							if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme))){
								$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
										FROM new_competences_plateforme
										WHERE Id<> 11 AND Id<>14
										ORDER BY new_competences_plateforme.Libelle;";
							}
							else{
								$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
										FROM new_competences_plateforme
										WHERE Id<> 11 AND Id<>14
										AND (
											Id IN (
											SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.")
										)
										OR 
										Id IN (
											SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
											)
										)
										ORDER BY new_competences_plateforme.Libelle;";
							}
							$resultPlateforme=mysqli_query($bdd,$req);
							$nbPlateforme=mysqli_num_rows($resultPlateforme);
							
							$changementPlateforme=0;
							$PlateformeSelect = $_SESSION['FiltreSODAAccueil_Plateforme'];
							if($_POST)
							{
								$PlateformeSelect=$_POST['plateforme'];
								if($PlateformeSelect<>$_SESSION['FiltreSODAAccueil_Plateforme']){$changementPlateforme=1;}
							}
							$_SESSION['FiltreSODAAccueil_Plateforme']=$PlateformeSelect;

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
							</select>
						</td>
						<td width="20%">
							<select class="prestation Mobile" name="prestations" onchange="submit();">
								<?php
								if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteChargeMissionOperation))){
									$req = "SELECT new_competences_prestation.Id, 
											Libelle,
											IF(Active=0,'[Actif]','[Inactif]') AS Active
											FROM new_competences_prestation
											WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." 
											ORDER BY Active ASC, new_competences_prestation.Libelle;";
								}
								else{
									$req = "SELECT new_competences_prestation.Id, 
											Libelle,
											IF(Active=0,'[Actif]','[Inactif]') AS Active
											FROM new_competences_prestation
											WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." 
											AND (
												Id_Plateforme IN (
												SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteChargeMissionOperation.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.")
											)
											OR 
											Id IN (
												SELECT Id_Prestation
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
												)
											)
											ORDER BY Active ASC, new_competences_prestation.Libelle;";
								}
								$resultPrestation=mysqli_query($bdd,$req);
								$nbPrestation=mysqli_num_rows($resultPrestation);
								
								$PrestationSelect = $_SESSION['FiltreSODAAccueil_Prestation'];
								if($changementPlateforme==0)
								{
									if($_POST){$PrestationSelect=$_POST['prestations'];}
								}
								else
								{
									$PrestationSelect=0;
								}
								 $_SESSION['FiltreSODAAccueil_Prestation']=$PrestationSelect;
								 
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
						<td width="20%">
							<select class="surveillant Mobile" name="surveillant" onchange="submit();">
								<?php
								$req = "
									SELECT DISTINCT Id_Personne AS Id, 
									(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom
									FROM new_competences_relation 
									WHERE (Evaluation='L'
									OR
									(Evaluation='X'
									AND Date_Debut<='".date('Y-m-d')."'
									AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
									)
									)
									AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
									UNION
									SELECT DISTINCT
										new_rh_etatcivil.Id,
										CONCAT(Nom, ' ', Prenom) as NomPrenom
									FROM
										new_rh_etatcivil
									INNER JOIN soda_surveillant
										ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
										
									ORDER BY NomPrenom ASC";
								$resultSurveillant=mysqli_query($bdd,$req);
								$nbSurveillant=mysqli_num_rows($resultSurveillant);
								
								$SurveillantSelect = $_SESSION['FiltreSODAAccueil_Surveillant'];
								if($_POST){$SurveillantSelect=$_POST['surveillant'];}
								 $_SESSION['FiltreSODAAccueil_Surveillant']=$SurveillantSelect;
								 
								echo "<option value='-1' Selected></option>";
								$selected = "";
								if($SurveillantSelect==0){$selected="selected";}
								if($_SESSION["Langue"]=="FR"){
									echo "<option value='0' ".$selected." >Pas de surveillant</option>";
								}
								else{
									echo "<option value='0' ".$selected." >No supervisor</option>";
								}
								if ($nbSurveillant > 0)
								{
									while($row=mysqli_fetch_array($resultSurveillant))
									{
										$selected="";
										if($SurveillantSelect==$row['Id']){$selected="selected";}
										echo "<option value='".$row['Id']."' ".$selected.">".$row['NomPrenom']."</option>\n";
									}
								 }
								
								 ?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="100%" class="LibelleMobile">
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Liste des surveillances planifiées";}else{echo "List of scheduled monitoring";}?> :
			</td>
		</tr>
		<tr>
			<td width="100%">
				<div id='Div_Plannif' style='height:1000px;overflow:auto;'>
					<table align="center" style="border-spacing:0; align:center;width:100%;" class="GeneralInfo">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="40%" class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?></td>
							<td width="12%" class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "UER";}else{echo "UER";}?></td>
							<td width="8%" class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Semaine";}else{echo "Week";}?></td>
							<td width="12%" class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Nb";}else{echo "Number";}?></td>
							
							<td width="5%" rowspan="2" class="EnTeteTableauCompetencesMobile"></td>
							<td width="8%" rowspan="2" class="EnTeteTableauCompetencesMobile"></td>
						</tr>
						<tr>	
							<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Questionnaire";}else{echo "Questionnaire";}?></td>
							<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation/Responsable";}else{echo "Site/Responsable";}?></td>
							<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";}?></td>
							<td class="EnTeteTableauCompetencesMobile"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?></td>
						</tr>
						<?php 
							$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
							$nbAccess=mysqli_num_rows($resAcc);
							
							$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
							$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
						
							$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
							$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

							//Suppression des surveillances planifiées non réalisé le jour même
							$req="UPDATE soda_surveillance SET Suppr=1 WHERE Suppr=0 AND DateSurveillance<'".date('Y-m-d')."' AND Etat='Planifié' ";
							$resultUpdt=mysqli_query($bdd,$req);
									
							//Liste des surveillances planifiées
							$req="SELECT Id,'PLANNIF' AS Type,
									(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
									(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Id_Theme,
									(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
									Id_Questionnaire,
									(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=soda_plannifmanuelle.Id_Prestation AND Id_Poste=1 ORDER BY Backup LIMIT 1) AS N1,
									(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=soda_plannifmanuelle.Id_Prestation AND Id_Poste=2 ORDER BY Backup LIMIT 1) AS N2,
									(SELECT Actif FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SupprQuestionnaire,
									IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
									IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) AS Id_Plateforme,
									(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,Id_Prestation,
									Volume-(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 AND Id_PlannifManuelle=soda_plannifmanuelle.Id ) AS Volume,
									Annee,Semaine,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Surveillant) AS Surveillant,
									Id_Surveillant
									FROM soda_plannifmanuelle 
									WHERE Suppr=0
									AND Annee=".$_SESSION['FiltreSODA_Annee']."
									AND (SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 
										AND Id_PlannifManuelle=soda_plannifmanuelle.Id) < soda_plannifmanuelle.Volume
									";
							if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme))){
								
							}
							else{
								$req.="AND (IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
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
							if ($PlateformeSelect <> 0 && $PlateformeSelect <> -1){$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme)=".$PlateformeSelect." ";}
							if ($PrestationSelect <> 0){$req .= "AND Id_Prestation =".$PrestationSelect." ";}
							if ($ThemeSelect <> 0)
							{
								$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$ThemeSelect." ";
								if($QuestionnaireSelect <> 0){$req .= "AND Id_Questionnaire =".$QuestionnaireSelect." ";}
							}
							$req.=" ORDER BY Semaine, Theme, Questionnaire, Prestation";
							$resultSurveillance=mysqli_query($bdd,$req);
							$nbSurveillance=mysqli_num_rows($resultSurveillance);
							$semaine=date('Y',strtotime(date('Y-m-d')."+0 month"))."S";
							if(date('W',strtotime(date('Y-m-d')."+0 month"))<10){$semaine.="0".date('W',strtotime(date('Y-m-d')."+0 month"));}
							else{$semaine.=date('W',strtotime(date('Y-m-d')."+0 month"));}
							
							$semaine2=date('Y',strtotime(date('Y-m-d')."+1 month"))."S";
							if(date('W',strtotime(date('Y-m-d')."+1 month"))<10){$semaine2.="0".date('W',strtotime(date('Y-m-d')."+1 month"));}
							else{$semaine2.=date('W',strtotime(date('Y-m-d')."+1 month"));}
							
							$Couleur="#EEEEEE";
							if($nbSurveillance>0){
								while($row=mysqli_fetch_array($resultSurveillance)){
									$volume=$row['Volume'];
									
									$affiche=1;
									if ($SurveillantSelect <> -1){
										if($row['Id_Surveillant']<>$SurveillantSelect){$affiche=0;}
									}
									
									if($volume>0 && $affiche==1){
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										
										if($_SESSION["Langue"]=="FR"){$etat="A faire";}else{echo "To do";}
										$lasemaine=$row['Annee']."S";
										$couleurTexte="";
										if($row['Semaine']<10){$lasemaine.="0".$row['Semaine'];}
										else{$lasemaine.=$row['Semaine'];}
										if($semaine>$lasemaine){
											$couleurTexte="style='color:#f31515;'";
											if($_SESSION["Langue"]=="FR"){$etat="En retard";}else{echo "Late";}
										}
										
										$presta=substr($row['Prestation'],0,strpos($row['Prestation']," "));
										?>
										<tr bgcolor="<?php echo $Couleur;?>">
											<td class="LigneMobile" <?php echo $couleurTexte;?>><?php echo stripslashes($row['Theme']);?></td>
											<td class="LigneMobile" <?php echo $couleurTexte;?>><?php echo stripslashes($row['Plateforme']);?></td>
											<td class="LigneMobile" <?php echo $couleurTexte;?>>S<?php if($row['Semaine']<10){echo "0".$row['Semaine'];}else{echo $row['Semaine'];} ?></td>
											<td class="LigneMobile" <?php echo $couleurTexte;?>><?php echo stripslashes($volume);?></td>
											<td class="LigneMobile" rowspan="2" <?php echo $couleurTexte;?> align="center">
											<?php 
												if($semaine2>=$lasemaine && ($nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0)){
													//Vérifier Si la personne peut faire les surveillances de cette thématique
													$req="SELECT Id FROM soda_surveillant_theme WHERE Id_Surveillant=".$_SESSION['Id_Personne']." AND Id_Theme=".$row['Id_Theme']." ";
													$resultSurvTheme=mysqli_query($bdd,$req);
													$nbSurvTheme=mysqli_num_rows($resultSurvTheme);
													
													$req="SELECT Id FROM soda_theme WHERE Id=".$row['Id_Theme']." AND Id_Qualification IN (
														SELECT DISTINCT Id_Qualification_Parrainage 
														FROM new_competences_relation 
														WHERE (Evaluation='X'
														AND Date_Debut<='".date('Y-m-d')."'
														AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
														)
														AND Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM soda_theme WHERE Id=".$row['Id_Theme'].")
														AND Id_Personne=".$_SESSION['Id_Personne']."
													) ";
													$resultSurTheme= mysqli_query($bdd,$req);	
													$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
													
													$req="SELECT Id FROM soda_theme WHERE Id=".$row['Id_Theme']." AND Id_Qualification IN (
														SELECT DISTINCT Id_Qualification_Parrainage 
														FROM new_competences_relation 
														WHERE Evaluation='L'
														AND Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM soda_theme WHERE Id=".$row['Id_Theme'].")
														AND Id_Personne=".$_SESSION['Id_Personne']."
													) ";
													$resultSurTheme= mysqli_query($bdd,$req);	
													$nbSurvThemeECQualifie=mysqli_num_rows($resultSurTheme);
													
													if($nbSurvTheme>0 || $nbSurvThemeQualifie>0 || ($nbSurvThemeECQualifie>0 && $row['Id_Surveillant']==$_SESSION['Id_Personne']) ){
														if($row['Id_Prestation']==0){
															?>
															<a class="Bouton" href="javascript:OuvreFenetreModifP('<?php echo $row['Id']; ?>');">
																<?php if($_SESSION["Langue"]=="FR"){echo "Lancer";}else{echo "Launch";}?>
															</a>
															<?php
														}
														else{
															?>
															<a class="Bouton" href="javascript:OuvreFenetreModif('<?php echo $row['Id']; ?>');">
																<?php if($_SESSION["Langue"]=="FR"){echo "Lancer";}else{echo "Launch";}?>
															</a>
															<?php
														}
													}
												}
											?>
											</td>
											<td class="LigneMobile" rowspan="2" <?php echo $couleurTexte;?> align="center">
											<?php
												if($row['SupprQuestionnaire']==1){
													if($nbAccess>0 || $nbSuperAdmin>0)
													{
											?>
														<a style="text-decoration:none;" href="javascript:OuvreFenetreSupprSurveillancePlanifiee('SupprimerSurveillancePlanifiee.php',<?php echo $row['Id'];?>,<?php echo $row['Volume'];?>)"><img src="../../v2/Images/Suppression2.gif" width="20px" border="0" alt="Suppr" title="Suppr"></a>
											<?php
													}
												}
											?>
											</td>
										</tr>
										<tr bgcolor="<?php echo $Couleur;?>">
											<td class="LigneMobile" <?php echo $couleurTexte;?>><b><?php echo stripslashes($row['Questionnaire']);?></b></td>
											<td class="LigneMobile" <?php echo $couleurTexte;?>><?php echo $presta;?><br><?php if($row['N1']<>""){echo stripslashes($row['N1']);}else{echo stripslashes($row['N2']);} ?></td>
											<td class="LigneMobile" <?php echo $couleurTexte;?>><?php echo $etat; ?></td>
											<td class="LigneMobile" <?php echo $couleurTexte;?>><?php echo stripslashes($row['Surveillant']);?></td>
										</tr>
										<?php
									}
								}
							}
						?>
						<tr><td height="4"></td></tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td height="100"></td>
		</tr>
	</table>
</form>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script><script  src="../script.js"></script>
</body>
</html>