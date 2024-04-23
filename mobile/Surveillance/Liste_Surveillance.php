<html>
<head>
	<title>AAA</title><meta name="robots" content="noindex">
	<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Dosis'><link rel="stylesheet" href="../style.css">
	<script src="Corriger_Questionnaire2.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function nombre(champ){
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
			verif = chiffres.test(champ.value.charAt(x));
			if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
			if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
			if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
		function OuvreFenetreModifSurveillance(ID)
		{
			window.location="Modifier_Surveillance.php?Id="+ID;
		}
	</script>
</head>
<body style="background-color:#cccccc;">

<?php
require("../Connexioni.php");
require("../../v2/Outils/Formation/Globales_Fonctions.php");
require("../../v2/Outils/Fonctions.php");
require("../Menu.php");

$DateJour=date("Y-m-d");

$AccesQualite=false;
if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,8))){$AccesQualite=true;}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_Surveillance.php">
	<tr bgcolor="#91dfff" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;border-style:outset;">
			<span style="font-size:3em;">
			SODA v0.1 (Alpha)<br>
			</span>
			<span style="font-size:2.5em;">
			<?php if($LangueAffichage=="FR"){echo "Surveillance Opérationnel Digital Adaptative";}else{echo "Digital Adaptive Operational Monitoring";}?>
			</span>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6"><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Critères de recherche";}else{echo "Search options";}?> : </b></td>
			</tr>
			<tr>
				<td width="30%" style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
				<td width="1%">&nbsp;</td>
				<td width="40%" style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
				<td width="1%">&nbsp;</td>
				<td width="28%" style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Date surveillance : ";}else{echo "Monitoring date  : ";}?></td>
			</tr>
			<tr>
				<td>
					<select name="plateforme" style="font-size:3em;width:250px;" onchange="submit();">
					<?php
					$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
							FROM new_competences_plateforme
							WHERE Id<> 11 AND Id<>14
							ORDER BY new_competences_plateforme.Libelle;";
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$changementPlateforme=0;
					$PlateformeSelect = $_SESSION['FiltreSurveillance_Plateforme'];
					if($_POST)
					{
						$PlateformeSelect=$_POST['plateforme'];
						if($PlateformeSelect<>$_SESSION['FiltreSurveillance_Plateforme']){$changementPlateforme=1;}
					}
					$_SESSION['FiltreSurveillance_Plateforme']=$PlateformeSelect;

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
				<td>&nbsp;</td>
				<td>
					<select class="prestation" name="prestations" style="font-size:3em;width:230px;">
					<?php
					$req = "SELECT new_competences_prestation.Id, CONCAT(LEFT(new_competences_prestation.Libelle,7),' ',IF(Active=0,'[Actif]','[Inactif]')) AS Libelle
							FROM new_competences_prestation
							WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." 
							ORDER BY Active DESC, new_competences_prestation.Libelle;";
					$resultPrestation=mysqli_query($bdd,$req);
					$nbPrestation=mysqli_num_rows($resultPrestation);
					
					$PrestationSelect = $_SESSION['FiltreSurveillance_Prestation'];
					if($changementPlateforme==0)
					{
						if($_POST){$PrestationSelect=$_POST['prestations'];}
					}
					else
					{
						$PrestationSelect=0;
					}
					 $_SESSION['FiltreSurveillance_Prestation']=$PrestationSelect;
					 
					$Selected = "";
					
					echo "<option value='0' Selected></option>";
					if ($nbPrestation > 0)
					{
						while($row=mysqli_fetch_array($resultPrestation))
						{
							$selected="";
							if($PrestationSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					
					 ?>
					</select>
				</td>
				<td>&nbsp;</td>
				<td>
					<?php
						$dateDebut=$_SESSION['FiltreSurveillance_DateSurveillance'];
						if($_POST){$dateDebut=$_POST['DateSurveillance'];}
						$_SESSION['FiltreSurveillance_DateSurveillance']=$dateDebut;
					?>
					<input type="date" style="font-size:3em;width:350px;" name="DateSurveillance"  value="<?php echo $dateDebut; ?>">
				</td>
			</tr>
			<tr>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
				<td>&nbsp;</td>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
				<td>&nbsp;</td>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Année : ";}else{echo "Year  : ";}?></td>
			</tr>
			<tr>
				<td>
					<select name="surveille" style="font-size:2.5em;width:230px;">
						<?php
							$req = "SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
									FROM new_rh_etatcivil
									ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
							$resultSurveille=mysqli_query($bdd,$req);
							$nbSurveille=mysqli_num_rows($resultSurveille);
							
							$SurveilleSelect = $_SESSION['FiltreSurveillance_Surveille'];
							if($_POST){$SurveilleSelect=$_POST['surveille'];}
							$_SESSION['FiltreSurveillance_Surveille']=$SurveilleSelect;
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
				<td>&nbsp;</td>
				<td>
					<select name="surveillant" style="font-size:2.5em;width:230px;">
						<?php
						$req = "SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
								FROM new_rh_etatcivil
								ORDER BY new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom;";
						$resultSurveillant=mysqli_query($bdd,$req);
						$nbSurveillant=mysqli_num_rows($resultSurveillant);
						
						$SurveillantSelect = $_SESSION['FiltreSurveillance_Surveillant'];
						if($_POST){$SurveillantSelect=$_POST['surveillant'];}
						$_SESSION['FiltreSurveillance_Surveillant']=$SurveillantSelect;
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
				<td>&nbsp;</td>
				<td>
					<?php
						$annee=$_SESSION['FiltreSurveillance_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						$_SESSION['FiltreSurveillance_Annee']=$annee;
					?>
					<input onKeyUp="nombre(this)" style="font-size:3em;width:250px;" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
				<td>&nbsp;</td>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
				<td>&nbsp;</td>
				<td style="font-size:2em;color:#00567c;"></td>
			</tr>
			<tr>
				<td>
					<select style="font-size:3em;width:250px;" name="theme" onchange="submit();">
					<?php
					$req = "SELECT new_surveillances_theme.ID, new_surveillances_theme.Nom
							FROM new_surveillances_theme
							ORDER BY new_surveillances_theme.Nom;";
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$ThemeSelect = $_SESSION['FiltreSurveillance_Theme'];
					if($_POST){$ThemeSelect=$_POST['theme'];}
					$_SESSION['FiltreSurveillance_Theme']=$ThemeSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbTheme > 0)
					{
						while($row=mysqli_fetch_array($resultTheme))
						{
							$selected="";
							if($ThemeSelect==$row['ID']){$selected="selected";}
							echo "<option value='".$row['ID']."' ".$selected.">".stripslashes($row['Nom'])."</option>\n";
						}
					 }
					 
					 ?>
					</select>
				</td>
				<td>&nbsp;</td>
				<td colspan="3">
					<select name="Questionnaire" style="font-size:3em;width:600px;">
					<?php
					$req = "SELECT new_surveillances_questionnaire.ID, 
							CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom
							FROM new_surveillances_questionnaire
							WHERE new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." 
							ORDER BY 
							new_surveillances_questionnaire.Actif,
							new_surveillances_questionnaire.Nom;";
					$resultQuestionnaire=mysqli_query($bdd,$req);
					$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
					
					$QuestionnaireSelect = $_SESSION['FiltreSurveillance_Questionnaire'];
					if($changementPlateformeTheme==0)
					{
						if($_POST){$QuestionnaireSelect=$_POST['Questionnaire'];}
					}
					else
					{
						$QuestionnaireSelect=0;
					}
					$_SESSION['FiltreSurveillance_Questionnaire']=$QuestionnaireSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbQuestionnaire > 0)
					{
						while($row=mysqli_fetch_array($resultQuestionnaire))
						{
							$selected="";
							if($QuestionnaireSelect==$row['ID']){$selected="selected";}
							echo "<option value='".$row['ID']."' ".$selected.">".stripslashes($row['Nom'])."</option>\n";
						}
					 }
					 ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "N° surveillance : ";}else{echo "Monitoring number : ";}?></td>
				<td>&nbsp;</td>
				<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Etat : ";}else{echo "Status : ";}?></td>
				<td>&nbsp;</td>
				<td style="font-size:2em;color:#00567c;">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<?php
					$numSurveillance = $_SESSION['FiltreSurveillance_NumSurveillance'];
					if($_POST){$numSurveillance=$_POST['numSurveillance'];}
					$_SESSION['FiltreSurveillance_NumSurveillance']=$numSurveillance;
					?>
					<input type="texte" style="font-size:3em;width:150px;" name="numSurveillance"  value="<?php echo $numSurveillance; ?>">
				</td>
				<td>&nbsp;</td>
				<td>
					<?php
						$EtatSelect=$_SESSION['FiltreSurveillance_Etat'];
						if($_POST){$EtatSelect=$_POST['etat'];}
						$_SESSION['FiltreSurveillance_Etat']=$EtatSelect;
					?>
					<select name="etat" style="font-size:3em;width:200px;">
						<option name="tous" value="tous" <?php if($EtatSelect=="tous"){echo "selected";}?>></option>
						<option name="planifié" value="planifié" <?php if($EtatSelect=="planifié"){echo "selected";}?>>Planifié</option>
						<option name="clôturé" value="clôturé" <?php if($EtatSelect=="clôturé"){echo "selected";}?>>Clôturé</option>
					</select>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center" colspan="6">
				<input class="Bouton" name="BtnRechercher" style="font-size:2em;" type="submit" value="<?php if($_SESSION['Langue']=="FR"){echo "Rechercher";}else{echo "Search";}?>">
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" style="font-size:4em;">
			<?php
				$req2="	SELECT
							new_surveillances_surveillance.ID,
							(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
							new_surveillances_questionnaire.Nom AS Questionnaire,
							new_surveillances_surveillance.ID_Surveillant,
							new_competences_prestation.Id_Plateforme AS Id_Plateforme,
							(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme,
							LEFT(new_competences_prestation.Libelle,7) AS Prestation,
							new_surveillances_surveillance.ID_Prestation,
							(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveille) AS Surveille,
							(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveillant) AS Surveillant,
							new_surveillances_surveillance.DatePlanif AS DatePlanif,
							new_surveillances_surveillance.DateReplanif AS DateReplanif,
							IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance,
							IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') AS Etat,
							new_surveillances_surveillance.Etat AS Etat2							";
				$req="FROM ((new_surveillances_surveillance
						LEFT JOIN new_competences_prestation
						ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id)
						LEFT JOIN new_surveillances_questionnaire
						ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id) ";
				if($numSurveillance <> "")
				{
					$req .= "WHERE new_surveillances_surveillance.ID =".$numSurveillance." ";
				}
				else
				{
					if ($PlateformeSelect <> 0 or $PrestationSelect <> 0 or $ThemeSelect <> 0 or $SurveilleSelect <> 0 or $SurveillantSelect <> 0 or $dateDebut <> "" or $EtatSelect <> "tous")
					{
						$req .= "WHERE ";
						if ($PlateformeSelect <> 0){$req .= "new_competences_prestation.Id_Plateforme =".$PlateformeSelect." AND ";}
						if ($PrestationSelect <> 0){$req .= "new_surveillances_surveillance.ID_Prestation =".$PrestationSelect." AND ";}
						if ($dateDebut <> ""){$req .= "IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) ='".TrsfDate_($dateDebut)."' AND ";}
						if($annee <> ""){$req .= "IF(new_surveillances_surveillance.DateReplanif >0, YEAR(new_surveillances_surveillance.DateReplanif), YEAR(new_surveillances_surveillance.DatePlanif)) ='".$annee."' AND ";}
						if ($ThemeSelect <> 0)
						{
							$req .= "new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." AND ";
							if($QuestionnaireSelect <> 0){$req .= "new_surveillances_questionnaire.ID =".$QuestionnaireSelect." AND ";}
						}
						if ($SurveilleSelect <> 0){$req .= "new_surveillances_surveillance.ID_Surveille =".$SurveilleSelect." AND ";}
						if ($SurveillantSelect <> 0){$req .= "new_surveillances_surveillance.ID_Surveillant =".$SurveillantSelect." AND ";}
						if($EtatSelect <> "tous" && $EtatSelect <> ""){$req .= "IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') ='".$EtatSelect."' AND ";}
						$req = substr($req,0,-4);
					}
				}
				$reqOrder = "ORDER BY DateSurveillance DESC ";
				$reqAnalyse="SELECT new_surveillances_surveillance.Id ";
				$resultSurveillance=mysqli_query($bdd,$reqAnalyse.$req);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);
				
				$nombreDePages=ceil($nbSurveillance/50);
				if(isset($_GET['Page'])){$_SESSION['Page']=$_GET['Page'];}
				if($_SESSION['Page']>$nombreDePages){$_SESSION['Page']="0";}
				$reqLimit=" LIMIT ".($_SESSION['Page']*50).",50";
				
				$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder.$reqLimit);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);
				
				$nbPage=0;
				if($_SESSION['Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['Page']<=5){$valeurDepart=1;}
				elseif($_SESSION['Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
				else{$valeurDepart=$_SESSION['Page']-5;}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
				{
					if($i<=$nombreDePages)
					{
						if($i==($_SESSION['Page']+1)){echo "<b> [ ".$i." ] </b>";}	
						else{echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=".($i-1)."'>".$i."</a> </b>&nbsp;";}
					}
				}
				if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td style="font-size:2em;color:#00567c;border-bottom:2px #055981 solid;" rowspan="3" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "N°";}else{echo "N°";}?></td>
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;" width="25%"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?></td>
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;" width="35%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;" width="15%">Date</td>
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillé";}else{echo "Supervised";}?></td>
				</tr>
				<tr>	
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;" colspan='2'>Questionnaire</td>
					<td style="font-size:2em;color:#00567c;border-bottom:1px #d9d9d7 solid;"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?></td>
				</tr>
				<tr>
					<td style="font-size:2em;color:#00567c;border-bottom:2px #055981 solid;"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "Status";}?></td>
					<td style="font-size:2em;color:#00567c;border-bottom:2px #055981 solid;" colspan="3"><?php if($_SESSION["Langue"]=="FR"){echo "Note";}else{echo "Score";}?></td>
				</tr>
				<?php
					if($nbSurveillance > 0)
					{
						while($rowSurveillance=mysqli_fetch_array($resultSurveillance))
						{
							$reqQuestionC = "SELECT ID FROM new_surveillances_surveillance_question WHERE ID_Surveillance=".$rowSurveillance['ID']." AND Etat='C'";
							$resultC=mysqli_query($bdd,$reqQuestionC);
							$nbC=mysqli_num_rows($resultC);
							
							$reqQuestionTot = "SELECT ID FROM new_surveillances_surveillance_question WHERE ID_Surveillance=".$rowSurveillance['ID']." AND (Etat='C' OR Etat='NC')";
							$resultTot=mysqli_query($bdd,$reqQuestionTot);
							$nbTot=mysqli_num_rows($resultTot);
							
							echo "<tr>";
							echo "<td style='border-bottom:2px #7e7e78 solid;font-size:2em;' rowspan='3'>";
							
							if($AccesQualite || DroitsFormationPrestationV2(array($rowSurveillance['ID_Prestation']),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)) || $rowSurveillance['ID_Surveillant']==$_SESSION['Id_Personne']){
								echo '<a style="color:#3e65fa;" href="javascript:OuvreFenetreModifSurveillance('.$rowSurveillance['ID'].');">';
							}
							echo $rowSurveillance['ID'];
							if($AccesQualite || DroitsFormationPrestationV2(array($rowSurveillance['ID_Prestation']),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)) || $rowSurveillance['ID_Surveillant']==$_SESSION['Id_Personne']){
								echo '</a>';
							}
							
							echo "</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;'>".$rowSurveillance['Plateforme']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;'>".$rowSurveillance['Prestation']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;'>".$rowSurveillance['DateSurveillance']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;'>".$rowSurveillance['Surveille']."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;'>".$rowSurveillance['Theme']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;' colspan='2'>".$rowSurveillance['Questionnaire']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;font-size:2em;'>".$rowSurveillance['Surveillant']."</td>";
							echo "</tr>";
							echo "<tr>";
							echo "<td style='border-bottom:2px #7e7e78 solid;font-size:2em;'>".$rowSurveillance['Etat']."</td>";
							$note = 0;
							if ($nbTot == 0){$note = 100;}
							else{$note = round(($nbC / $nbTot)*100,0);}
							if ($rowSurveillance['Etat'] == "Planifié"){$note = "";}
							echo "<td style='border-bottom:2px #7e7e78 solid;font-size:2em;' colspan='3'>".$note."%</td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr><td height="150"></td></tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script><script  src="../script.js"></script>
</body>
</html>