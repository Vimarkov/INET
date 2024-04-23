<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreAjoutSurveillance(Mode,ID)
	{
		if(Mode=="Suppr")
		{
			if(window.confirm('Vous êtes sûr de vouloir supprimer ?'))
			{
				var w=window.open("Ajout_Surveillance.php?Mode="+Mode+"&Id="+ID,"PageASurveillance","status=no,menubar=no,width=600,height=350");
				w.focus();
			}
		}
		else
		{
			var w=window.open("Ajout_Surveillance.php?Mode="+Mode+"&Id="+ID,"PageASurveillance","status=no,menubar=no,width=800,height=350");
			w.focus();
		}
	}
	function OuvreFenetreModifSurveillance(ID)
	{
		var w=window.open("Modif_Surveillance.php?Id="+ID,"PageASurveillance","status=no,menubar=no,width=800,height=350");
		w.focus();
	}
	function OuvreFenetreCorrigerQuestionnaire(ID)
		{var w=window.open("Corriger_Questionnaire.php?Id="+ID,"PageCQuestionnaire","status=no,menubar=no,scrollbars=yes,fullscreen=yes");
		w.focus();
		}
	function Excel(Id_Plateforme,Id_Prestation,DateSurveillance,Id_Surveille,Id_Surveillant,TypeTheme,Theme,Id_PlateformeTheme,Id_PlateformeQuestionnaire,Etat,NumSurveillance){
		var w=window.open("ExportExcel.php?Id_Plateforme="+Id_Plateforme+"&Id_Prestation="+Id_Prestation+"&DateSurveillance="+DateSurveillance+"&Id_Surveille="+Id_Surveille+"&Id_Surveillant="+Id_Surveillant+"&Theme="+Theme+"&TypeTheme="+TypeTheme+"&Id_PlateformeTheme="+Id_PlateformeTheme+"&Id_PlateformeQuestionnaire="+Id_PlateformeQuestionnaire+"&Etat="+Etat+"&NumSurveillance="+NumSurveillance,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function OuvreFenetrePlanActions()
		{var w=window.open("Extract_PlanActions.php?Id","PagePlanActions","status=no,menubar=no,scrollbars=yes,fullscreen=yes,width=10,height=10");
		w.focus();
		}
	function SurveillancePDF(Id)
		{window.open("SurveillancePDF.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function SurveillanceExcel(Id)
		{window.open("SurveillanceExcel.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
</script>
<?php
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

$AccesQualite=false;
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_Surveillance.php">
	<tr>
		<td>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0" style="background-color:#42d3d6;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Surveillance/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "Gestion des surveillances # Surveillances";}
							else{echo "Monitoring management # Monitoring";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan="6"><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Critères de recherche";}else{echo "Search options";}?> : </b></td>
		</tr>
		<tr>
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?> :
			</td>
			<td width=10%>
				<select name="plateforme" onchange="submit();">
				<?php
				$req="	SELECT
							DISTINCT
							new_competences_prestation.Id_Plateforme AS Id,
							(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Libelle 
						FROM ((new_surveillances_surveillance
						LEFT JOIN new_competences_prestation
						ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id)
						LEFT JOIN new_surveillances_questionnaire
						ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id) ";
				if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
					
				}
				else{
					$req.="WHERE new_competences_prestation.Id_Plateforme IN (
						SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
						AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
					)
					OR 
					new_surveillances_surveillance.ID_Prestation IN (
						SELECT Id_Prestation 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION['Id_Personne']."
						AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
						)
					OR Id_Surveillant=".$_SESSION['Id_Personne']."
					";
				}
				$req.="ORDER BY Libelle ASC";
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
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?> :
			</td>
			<td width=25%>
				<select class="prestation" name="prestations" style="width:150px">
				<?php
				$req="	SELECT
							DISTINCT
							new_surveillances_surveillance.ID_Prestation AS Id,
							CONCAT(new_competences_prestation.Libelle,' ',IF(Active=0,'[Actif]','[Inactif]')) AS Libelle
						FROM ((new_surveillances_surveillance
						LEFT JOIN new_competences_prestation
						ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id)
						LEFT JOIN new_surveillances_questionnaire
						ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id) ";
				if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
					
				}
				else{
					$req.="WHERE new_competences_prestation.Id_Plateforme IN (
						SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
						AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
					)
					OR 
					new_surveillances_surveillance.ID_Prestation IN (
						SELECT Id_Prestation 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION['Id_Personne']."
						AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
						)
					OR Id_Surveillant=".$_SESSION['Id_Personne']."
					";
				}
				$req.="ORDER BY Active DESC, new_competences_prestation.Libelle;";
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
						$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
						echo "<option value='".$row['Id']."' ".$selected.">".$presta."</option>\n";
					}
				 }
				
				 ?>
				</select>
			</td>
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Date surveillance";}else{echo "Monitoring date";}?> :
			</td>
			<td width=25%>
				<?php
					$dateDebut=$_SESSION['FiltreSurveillance_DateSurveillance'];
					if($_POST){$dateDebut=$_POST['DateSurveillance'];}
					$_SESSION['FiltreSurveillance_DateSurveillance']=$dateDebut;
				?>
				<input type="date" style="text-align:center;" name="DateSurveillance" size="10" value="<?php echo $dateDebut; ?>">
			</td>
		</tr>
		<tr>
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?> :
			</td>
			<td width=10%>
				<select name="theme" onchange="submit();">
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
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Surveillé";}else{echo "Supervised";}?> :
			</td>
			<td width=25%>
				<select name="surveille" style="width:350px">
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
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?> :
			</td>
			<td width=25%>
				<select name="surveillant" style="width:350px">
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
		</tr>
		<tr <?php if ($ThemeSelect == 0){echo "style='display:none;'";} ?>>
			<td width=12%>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Type du thème";}else{echo "Theme type";}?> : </td>
			<td width=10%>
				<?php
				$TypeTheme=$_SESSION['FiltreSurveillance_TypeTheme'];
				if($_POST){
					$TypeTheme=$_POST['TypeTheme'];
				}
				$_SESSION['FiltreSurveillance_TypeTheme']=$TypeTheme;
				?>
				<input onchange="submit();" type="radio" name="TypeTheme" value="Tous" <?php if ($TypeTheme == "Tous" || $TypeTheme == ""){echo "checked";}?>><?php if($_SESSION['Langue']=="FR"){echo "Tous";}else{echo "All";}?><br>
				<input onchange="submit();" type="radio" name="TypeTheme" value="Generique" <?php if ($TypeTheme == "Generique"){echo "checked";}?>><?php if($_SESSION['Langue']=="FR"){echo "Générique";}else{echo "Generic";}?><br>
				<input onchange="submit();" type="radio" name="TypeTheme" value="Specifique" <?php if ($TypeTheme == "Specifique"){echo "checked";}?>><?php if($_SESSION['Langue']=="FR"){echo "Spécifique";}else{echo "Specific";}?>
			</td>
			<td width=8% <?php if ($TypeTheme == "Tous" || $TypeTheme == "Generique"){echo "style='display:none;'";} ?>>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Entité émettrice";}else{echo "Issuing entity";}?> :
			</td>
			<td width=25% <?php if ($TypeTheme == "Tous" || $TypeTheme == "Generique"){echo "style='display:none;'";} ?>>
				<select name="plateformeTheme" onchange="submit();">
				<?php
				$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle
						FROM new_competences_plateforme
						ORDER BY new_competences_plateforme.Libelle;";
				$resultPlateforme=mysqli_query($bdd,$req);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeThemeSelect = $_SESSION['FiltreSurveillance_PlateformeTheme'];
				$changementPlateformeTheme=0;
				if($_POST)
				{
					$PlateformeThemeSelect=$_POST['plateformeTheme'];
					if($PlateformeThemeSelect<>$_SESSION['FiltreSurveillance_PlateformeTheme']){$changementPlateformeTheme=1;}
				}
				$_SESSION['FiltreSurveillance_PlateformeTheme']=$PlateformeThemeSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeThemeSelect==$row['Id']){$selected="selected";}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 
				 ?>
				</select>
			</td>
			<td width=12% <?php if ($TypeTheme == "Tous"){echo "style='display:none;'";} ?>>
				&nbsp; Questionnaire :
			</td>
			<td width=25% <?php if ($TypeTheme == "Tous"){echo "style='display:none;'";} ?>>
				<select name="Questionnaire">
				<?php
				$req = "SELECT new_surveillances_questionnaire.ID, 
						CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom
						FROM new_surveillances_questionnaire
						WHERE ";
				if($TypeTheme=="Specifique" && $PlateformeThemeSelect==0){$req.="new_surveillances_questionnaire.ID_Plateforme>0";}
				else{$req.="new_surveillances_questionnaire.ID_Plateforme =".$PlateformeThemeSelect;}
				$req.=	" AND new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." 
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
			<td width=12%>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "Status";}?> : </td>
			<td width=10%>
				<?php
					$EtatSelect=$_SESSION['FiltreSurveillance_Etat'];
					if($_POST){$EtatSelect=$_POST['etat'];}
					$_SESSION['FiltreSurveillance_Etat']=$EtatSelect;
				?>
				<select name="etat">
					<option name="tous" value="tous" <?php if($EtatSelect=="tous"){echo "selected";}?>></option>
					<option name="planifié" value="planifié" <?php if($EtatSelect=="planifié"){echo "selected";}?>>Planifié</option>
					<option name="clôturé" value="clôturé" <?php if($EtatSelect=="clôturé"){echo "selected";}?>>Clôturé</option>
				</select>
			</td>
			<td width="8%">
				<?php
					$annee=$_SESSION['FiltreSurveillance_Annee'];
					if($_POST){$annee=$_POST['annee'];}
					$_SESSION['FiltreSurveillance_Annee']=$annee;
				?>
				&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
			</td>
			<td width=10%>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td align="center" colspan="6"><b><?php if($_SESSION['Langue']=="FR"){echo "OU";}else{echo "OR";}?></b></td>
		</tr>
		<tr>
			<td width=12%>
				&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "N° surveillance";}else{echo "Monitoring number";}?> :
			</td>
			<td colspan="5">
				<?php
				$numSurveillance = $_SESSION['FiltreSurveillance_NumSurveillance'];
				if($_POST){$numSurveillance=$_POST['numSurveillance'];}
				$_SESSION['FiltreSurveillance_NumSurveillance']=$numSurveillance;
				?>
				<input type="texte" style="text-align:center;" name="numSurveillance" size="10" value="<?php echo $numSurveillance; ?>">
			</td>
		</tr>
		<tr>
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="FR"){echo "Rechercher";}else{echo "Search";}?>">&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel(<?php echo $PlateformeSelect.",".$PrestationSelect.",'".$dateDebut."',".$SurveilleSelect.",".$SurveillantSelect.",'".$TypeTheme."',".$ThemeSelect.",".$PlateformeThemeSelect.",".$QuestionnaireSelect.",'".$EtatSelect."','".$numSurveillance."'"; ?>)">&nbsp;Export Excel&nbsp;</a>
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
	if($AccesQualite)
	{
	?>
	<tr>
		<td align="center">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutSurveillance("Ajout","0")'>&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Planifier une surveillance";}else{echo "Plan a monitoring";}?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
	}
	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$req2="	SELECT
							new_surveillances_surveillance.ID,
							(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
							new_surveillances_questionnaire.Nom AS Questionnaire,
							new_surveillances_surveillance.ID_Surveillant,
							new_competences_prestation.Id_Plateforme AS Id_Plateforme,
							(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme,
							new_competences_prestation.Libelle AS Prestation,
							new_surveillances_surveillance.ID_Prestation,
							(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveille) AS Surveille,
							(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveillant) AS Surveillant,
							new_surveillances_surveillance.DatePlanif AS DatePlanif,
							new_surveillances_surveillance.DateReplanif AS DateReplanif,
							IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance,
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
					if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
						
					}
					else{
						$req.="AND (new_competences_prestation.Id_Plateforme IN (
							SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
						)
						OR 
						new_surveillances_surveillance.ID_Prestation IN (
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
							)
						OR Id_Surveillant=".$_SESSION['Id_Personne']."
						) ";
					}
				}
				elseif ($PlateformeSelect <> 0 or $PrestationSelect <> 0 or $ThemeSelect <> 0 or $SurveilleSelect <> 0 or $SurveillantSelect <> 0 or $dateDebut <> "" or $EtatSelect <> "tous")
				{
					$req .= "WHERE ";
					if ($PlateformeSelect <> 0){$req .= "new_competences_prestation.Id_Plateforme =".$PlateformeSelect." AND ";}
					if ($PrestationSelect <> 0){$req .= "new_surveillances_surveillance.ID_Prestation =".$PrestationSelect." AND ";}
					if ($dateDebut <> ""){$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) ='".TrsfDate_($dateDebut)."' AND ";}
					if($annee <> ""){$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', YEAR(new_surveillances_surveillance.DateReplanif), YEAR(new_surveillances_surveillance.DatePlanif)) ='".$annee."' AND ";}
					if ($ThemeSelect <> 0)
					{
						$req .= "new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." AND ";
						if ($TypeTheme == "Generique")
						{
							$req .= "new_surveillances_questionnaire.ID_Plateforme =0 AND ";
							if($QuestionnaireSelect <> 0){$req .= "new_surveillances_questionnaire.ID =".$QuestionnaireSelect." AND ";}
						}
						elseif ($TypeTheme == "Specifique")
						{
							$req .= "new_surveillances_questionnaire.ID_Plateforme <>0 AND ";
							if($PlateformeThemeSelect <>0){$req .= "new_surveillances_questionnaire.ID_Plateforme =".$PlateformeThemeSelect." AND ";}
							if($QuestionnaireSelect <> 0){$req .= "new_surveillances_questionnaire.ID =".$QuestionnaireSelect." AND ";}
						}
					}
					if ($SurveilleSelect <> 0){$req .= "new_surveillances_surveillance.ID_Surveille =".$SurveilleSelect." AND ";}
					if ($SurveillantSelect <> 0){$req .= "new_surveillances_surveillance.ID_Surveillant =".$SurveillantSelect." AND ";}
					if($EtatSelect <> "tous" && $EtatSelect <> ""){$req .= "IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') ='".$EtatSelect."' AND ";}
					$req = substr($req,0,-4);
					
					if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
						
					}
					else{
						$req.="AND (new_competences_prestation.Id_Plateforme IN (
							SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
						)
						OR 
						new_surveillances_surveillance.ID_Prestation IN (
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
							)
						OR Id_Surveillant=".$_SESSION['Id_Personne']."
						) ";
					}
				}
				else{
					if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
						
					}
					else{
						$req.="WHERE (new_competences_prestation.Id_Plateforme IN (
							SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
						)
						OR 
						new_surveillances_surveillance.ID_Prestation IN (
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
							)
						OR Id_Surveillant=".$_SESSION['Id_Personne']."
						) ";
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
						else{echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=".($i-1)."'>".$i."</a> </b>";}
					}
				}
				if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr align="center">
					<td class="EnTeteTableauCompetences" width="3"><?php if($_SESSION["Langue"]=="FR"){echo "N° surveillance";}else{echo "Monitoring number";}?></td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?></td>
					<td class="EnTeteTableauCompetences" width="100"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td class="EnTeteTableauCompetences" width="10">Date</td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td class="EnTeteTableauCompetences" width="30">Questionnaire</td>
					<td class="EnTeteTableauCompetences" width="20"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillé";}else{echo "Supervised";}?></td>
					<td class="EnTeteTableauCompetences" width="20"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?></td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "Status";}?></td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION["Langue"]=="FR"){echo "Note";}else{echo "Score";}?></td>
					<td class="EnTeteTableauCompetences" width="2"></td>
					<td class="EnTeteTableauCompetences" width="2"></td>
					<td class="EnTeteTableauCompetences" width="2"></td>
					<td class="EnTeteTableauCompetences" width="2"></td>
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
							
							$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
							if($presta==""){$presta=$rowSurveillance['Prestation'];}
							
							echo "<tr>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='3'>".$rowSurveillance['ID']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$rowSurveillance['Plateforme']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='100'>".$presta."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$rowSurveillance['DateSurveillance']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$rowSurveillance['Theme']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='30'>".$rowSurveillance['Questionnaire']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='20'>".$rowSurveillance['Surveille']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='20'>".$rowSurveillance['Surveillant']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$rowSurveillance['Etat']."</td>";
							$note = 0;
							if ($nbTot == 0){$note = 100;}
							else{$note = round(($nbC / $nbTot)*100,0);}
							if ($rowSurveillance['Etat'] == "Planifié"){$note = "";}
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$note."%</td>";
							if($AccesQualite)
							{
								echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
								if($rowSurveillance['Etat'] != "Clôturé")
								{
									echo "<a href='javascript:OuvreFenetreAjoutSurveillance(\"Modif\",\"".$rowSurveillance['ID']."\")'>";
									echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier la surveillance'>";
									echo "</a>";
								}
								echo "&nbsp;</td>";
								echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
								echo "<a href='javascript:OuvreFenetreAjoutSurveillance(\"Suppr\",\"".$rowSurveillance['ID']."\")'>";
								echo "<img src='../../Images/Suppression.gif' border='0' alt='Suppresion' title='Supprimer la surveillance'>";
								echo "</a>";
								echo "&nbsp;</td>";
							}
							else{
								echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
								if(DroitsFormationPrestationV2(array($rowSurveillance['ID_Prestation']),array($IdPosteCoordinateurEquipe))){
									
										echo "<a href='javascript:OuvreFenetreModifSurveillance(\"".$rowSurveillance['ID']."\")'>";
										echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier la surveillance'>";
										echo "</a>";
									
								}
								echo "&nbsp;</td>";
							}
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
							echo "<a href='javascript:OuvreFenetreCorrigerQuestionnaire(".$rowSurveillance['ID'].")'>";
							if($AccesQualite || $rowSurveillance['ID_Surveillant']==$_SESSION['Id_Personne']){echo "<img src='../../Images/formulaire.gif' border='0' alt='Correction' title='Corriger le questionnaire'>";}
							else{echo "<img src='../../Images/formulaire.gif' border='0' alt='Visualisation' title='Visualiser le questionnaire'>";}
							echo "</a>&nbsp;";
							echo "</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
								if ($rowSurveillance['Etat2'] == "Clôturé" || $rowSurveillance['Etat2'] =='Réalisé'){
									echo "<a href='javascript:SurveillancePDF(".$rowSurveillance['ID'].");'>";
									echo "<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>";
									echo "</a>";
								}
								else{
									echo "<a href='javascript:SurveillanceExcel(".$rowSurveillance['ID'].");'>";
									echo "<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>";
									echo "</a>";
								}
							echo "</td>";
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="font-size:14px;">
		<?php
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
					else{echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=".($i-1)."'>".$i."</a> </b>";}
				}
			}
			if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Surveillance.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
		?>
		</td>
	</tr>
	<tr height='15'>
		<td>
			<?php if($_SESSION["Langue"]=="FR"){echo "Seuls les 50 premières surveillances sont affichées";}
			else{echo "Only the 50 first monitoring are listed";}?>
		</td>
	</tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>