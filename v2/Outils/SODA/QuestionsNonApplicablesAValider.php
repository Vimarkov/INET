<script>
	function OuvreFenetreExcelNA()
		{window.open("Export_QuestionNA.php","PageExcel","status=no,menubar=no,width=90,height=90");}
</script>
<?php Ecrire_Code_JS_Init_Date(); ?>

<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr><td height="4"></td></tr>
	<tr><td width="100%">
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
				<td width="15%">
					<select name="plateforme" style="width:150px;" onchange="submit();">
					<?php
					$req = "SELECT DISTINCT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Libelle
							FROM soda_surveillance_question 
							LEFT JOIN soda_surveillance 
							ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
							WHERE soda_surveillance.Suppr=0
							AND soda_surveillance_question.Etat='NA'
							AND soda_surveillance.Etat='Clôturé'
							AND AutoSurveillance=0 
							AND TypeNA=2 
							AND TraitementNA=0 ";
					if($nbAccess>0 || $nbSuperAdmin>0){}
					else{
						$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
									IN (SELECT Id 
										FROM soda_theme 
										WHERE Suppr=0 
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
										)
									OR 
									IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
									(
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
									)	
									) ";
					}
					$req.="ORDER BY (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation);";
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$changementPlateforme=0;
					$PlateformeSelect = $_SESSION['FiltreSODAQuestionNA_Plateforme'];
					if($_POST)
					{
						$PlateformeSelect=$_POST['plateforme'];
						if($PlateformeSelect<>$_SESSION['FiltreSODAQuestionNA_Plateforme']){$changementPlateforme=1;}
					}
					$_SESSION['FiltreSODAQuestionNA_Plateforme']=$PlateformeSelect;

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
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
				<td width="15%">
					<select class="prestation" name="prestations" style="width:150px;">
						<?php
						$req = "SELECT DISTINCT Id_Prestation AS Id,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Libelle
								FROM soda_surveillance_question 
								LEFT JOIN soda_surveillance 
								ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
								WHERE soda_surveillance.Suppr=0
								AND soda_surveillance_question.Etat='NA'
								AND soda_surveillance.Etat='Clôturé'
								AND AutoSurveillance=0 
								AND TypeNA=2 
								AND TraitementNA=0 ";
								if($nbAccess>0 || $nbSuperAdmin>0){}
								else{
									$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
									IN (SELECT Id 
										FROM soda_theme 
										WHERE Suppr=0 
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
										)
									OR 
									IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
									(
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
									)	
									) ";
								}
						$req.="ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)";
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = $_SESSION['FiltreSODAQuestionNA_Prestation'];
						if($changementPlateforme==0)
						{
							if($_POST){$PrestationSelect=$_POST['prestations'];}
						}
						else
						{
							$PrestationSelect=0;
						}
						$_SESSION['FiltreSODAQuestionNA_Prestation']=$PrestationSelect;
						 
						$Selected = "";
						
						echo "<option value='0' Selected></option>";
						if ($nbPrestation > 0)
						{
							while($row=mysqli_fetch_array($resultPrestation))
							{
								$selected="";
								if($PrestationSelect==$row['Id']){$selected="selected";}
								$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
								if($presta==""){$presta=$row['Libelle'];}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($presta)."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
				<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
				<td width="15%">
					<select name="surveille" style="width:190px;">
						<?php
							$req = "SELECT DISTINCT Id_Surveille AS Id,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Surveille) AS Surveille
								FROM soda_surveillance_question 
								LEFT JOIN soda_surveillance 
								ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
								WHERE soda_surveillance.Suppr=0
								AND soda_surveillance_question.Etat='NA'
								AND soda_surveillance.Etat='Clôturé'
								AND AutoSurveillance=0 
								AND TypeNA=2 
								AND TraitementNA=0 ";
								if($nbAccess>0 || $nbSuperAdmin>0){}
								else{
									$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
									IN (SELECT Id 
										FROM soda_theme 
										WHERE Suppr=0 
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
										)
									OR 
									IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
									(
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
									)	
									) ";
								}
								$req.="ORDER BY Surveille";
							$resultSurveille=mysqli_query($bdd,$req);
							$nbSurveille=mysqli_num_rows($resultSurveille);
							
							$SurveilleSelect = $_SESSION['FiltreSODAQuestionNA_Surveille'];
							if($_POST){$SurveilleSelect=$_POST['surveille'];}
							$_SESSION['FiltreSODAQuestionNA_Surveille']=$SurveilleSelect;
							$Selected = "";

							echo "<option name='0' value='0' Selected></option>";
							if ($nbSurveille > 0)
							{
								while($row=mysqli_fetch_array($resultSurveille))
								{
									$selected="";
									if($SurveilleSelect==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Surveille'])."</option>\n";
								}
							 }
						 ?>
					</select>
				</td>
				<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
				<td width="15%">
					<select name="surveillant" style="width:190px;">
						<?php
						$req = "SELECT DISTINCT Id_Surveillant AS Id,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Surveillant) AS Surveillant
								FROM soda_surveillance_question 
								LEFT JOIN soda_surveillance 
								ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
								WHERE soda_surveillance.Suppr=0
								AND soda_surveillance_question.Etat='NA'
								AND soda_surveillance.Etat='Clôturé'
								AND AutoSurveillance=0 
								AND TypeNA=2 
								AND TraitementNA=0 ";
								if($nbAccess>0 || $nbSuperAdmin>0){}
								else{
									$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
									IN (SELECT Id 
										FROM soda_theme 
										WHERE Suppr=0 
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
										)
									OR 
									IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
									(
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
									)	
									) ";
								}
								$req.="ORDER BY Surveillant";
						$resultSurveillant=mysqli_query($bdd,$req);
						$nbSurveillant=mysqli_num_rows($resultSurveillant);
						
						$SurveillantSelect = $_SESSION['FiltreSODAQuestionNA_Surveillant'];
						if($_POST){$SurveillantSelect=$_POST['surveillant'];}
						$_SESSION['FiltreSODAQuestionNA_Surveillant']=$SurveillantSelect;
						$Selected = "";
						echo "<option name='0' value='0' Selected></option>";
						if ($nbSurveillant > 0)
						{
							while($row=mysqli_fetch_array($resultSurveillant))
							{
								$selected="";
								if($SurveillantSelect==$row['Id']){$selected="selected";}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Surveillant'])."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle" ><?php if($_SESSION['Langue']=="FR"){echo "N° surveillance : ";}else{echo "Monitoring number : ";}?></td>
				<td>
					<?php
					$numSurveillance = $_SESSION['FiltreSODAQuestionNA_NumSurveillance'];
					if($_POST){$numSurveillance=$_POST['numSurveillance'];}
					$_SESSION['FiltreSODAQuestionNA_NumSurveillance']=$numSurveillance;
					?>
					<input type="texte" style="width:80px;" name="numSurveillance"  value="<?php echo $numSurveillance; ?>">
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
				<td>
					<select style="width:200px;" name="theme" onchange="submit();">
					<?php
					$req = "SELECT soda_theme.Id, soda_theme.Libelle
							FROM soda_theme
							WHERE Suppr=0
							ORDER BY soda_theme.Libelle;";
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$ThemeSelect = $_SESSION['FiltreSODAQuestionNA_Theme'];
					if($_POST){$ThemeSelect=$_POST['theme'];}
					$_SESSION['FiltreSODAQuestionNA_Theme']=$ThemeSelect;
					
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
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
				<td colspan="3" >
					<select name="Questionnaire" style="width:300px;">
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
					
					$QuestionnaireSelect = $_SESSION['FiltreSODAQuestionNA_Questionnaire'];
					if($changementPlateformeTheme==0)
					{
						if($_POST){$QuestionnaireSelect=$_POST['Questionnaire'];}
					}
					else
					{
						$QuestionnaireSelect=0;
					}
					$_SESSION['FiltreSODAQuestionNA_Questionnaire']=$QuestionnaireSelect;
					
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
				<td width="3%">
					&nbsp;&nbsp;&nbsp;
					<a href="javascript:OuvreFenetreExcelNA()">
					<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<?php 
				$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
				$nbAccess=mysqli_num_rows($resAcc);
				
				$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
				$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

				$req2="	SELECT soda_surveillance.Id,soda_surveillance_question.Id AS Id_SurveillanceQuestion,
						(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
						(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						DateSurveillance,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,Id_Surveille,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,Id_Surveillant,
						(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
						(SELECT Question_EN FROM soda_question WHERE Id=Id_Question) AS QuestionEN,
						soda_surveillance_question.Commentaire
						";
				$req = "FROM soda_surveillance_question 
						LEFT JOIN soda_surveillance 
						ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
						WHERE soda_surveillance.Suppr=0
						AND soda_surveillance_question.Etat='NA'
						AND soda_surveillance.Etat='Clôturé'
						AND AutoSurveillance=0 
						AND TypeNA=2 
						AND TraitementNA=0 ";
						if($nbAccess>0 || $nbSuperAdmin>0){}
						else{
							$req.="AND ((SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) 
									IN (SELECT Id 
										FROM soda_theme 
										WHERE Suppr=0 
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
										)
									OR 
									IF(Id_Plateforme>0,Id_Plateforme,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)) IN 
									(
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.")
									)	
									) ";
						}
				if($numSurveillance <> "")
				{
					$req .= "AND soda_surveillance.Id =".$numSurveillance." ";
				}
				else
				{
					if ($PlateformeSelect <> 0){$req .= "AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$PlateformeSelect." ";}
					if ($PrestationSelect <> 0){$req .= "AND soda_surveillance.Id_Prestation =".$PrestationSelect." ";}
					if ($ThemeSelect <> 0)
					{
						$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$ThemeSelect." ";
						if($QuestionnaireSelect <> 0){$req .= "AND Id_Questionnaire =".$QuestionnaireSelect." ";}
					}
					if ($SurveilleSelect <> 0){$req .= "AND soda_surveillance.Id_Surveille =".$SurveilleSelect." ";}
					if ($SurveillantSelect <> 0){$req .= "AND soda_surveillance.Id_Surveillant =".$SurveillantSelect." ";}
				}
				$reqOrder = " ORDER BY DateSurveillance DESC ";
				$reqAnalyse="SELECT soda_surveillance.Id ";
				$resultSurveillance=mysqli_query($bdd,$reqAnalyse.$req);
				$nbSurveillanceTot=mysqli_num_rows($resultSurveillance);

				$nombreDePages=ceil($nbSurveillanceTot/50);
				if(isset($_GET['Page'])){$_SESSION['Page']=$_GET['Page'];}
				if($_SESSION['Page']>$nombreDePages){$_SESSION['Page']="0";}
				$reqLimit=" LIMIT ".($_SESSION['Page']*50).",50";
				
				$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder.$reqLimit);
				$nbSurveillance=mysqli_num_rows($resultSurveillance);
			?>
			<tr>
				<td class="Libelle" align="right" colspan="10">
					<?php if($_SESSION['Langue']=="FR"){echo "Nbr de questions non applicables à valider : ".$nbSurveillanceTot;}else{echo "Number of non-applicable questions to validate : ".$nbSurveillanceTot;}?>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" type="submit" value="<?php if($_SESSION['Langue']=="FR"){echo "Rechercher";}else{echo "Search";}?>">
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<?php
				$nbPage=0;
				if($_SESSION['Page']>1){echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Menu=26&Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['Page']<=5){$valeurDepart=1;}
				elseif($_SESSION['Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
				else{$valeurDepart=$_SESSION['Page']-5;}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
				{
					if($i<=$nombreDePages)
					{
						if($i==($_SESSION['Page']+1)){echo "<b> [ ".$i." ] </b>";}	
						else{echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Menu=26&Page=".($i-1)."'>".$i."</a> </b>&nbsp;";}
					}
				}
				if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Tableau_De_Bord.php?Menu=26&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="3%"><?php if($_SESSION["Langue"]=="FR"){echo "N°";}else{echo "N°";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="7%"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="20%">Questionnaire</td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Question";}else{echo "Question";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Cause";}else{echo "Cause";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="8%">Date</td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillé";}else{echo "Supervised";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillant";}else{echo "Supervisor";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="3%"><?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "To validate";}?></td>
					<td style="color:#00567c;border-bottom:2px #055981 solid;" width="3%"><?php if($_SESSION["Langue"]=="FR"){echo "Ignorer";}else{echo "Ignore";}?></td>
				</tr>
				<?php
					if($nbSurveillance > 0)
					{
						$semaine=date('Y')."S";
						if(date('W')<10){$semaine.="0".date('W');}
						else{$semaine.=date('W');}
						
						$couleur="#ffffff";
						while($rowSurveillance=mysqli_fetch_array($resultSurveillance))
						{
							$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
							if($presta==""){$presta=$rowSurveillance['Prestation'];}
							
							echo "<tr style='background-color:".$couleur."' >";
							echo "<td>";
								echo '<a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(\'Modif_Surveillance.php\',\'V\','.$rowSurveillance['Id'].');">';
								echo $rowSurveillance['Id'];
								echo '</a>';
							echo "</td>";
							echo "<td>".$rowSurveillance['Plateforme']."</td>";
							echo "<td>".$presta."</td>";
							echo "<td>".$rowSurveillance['Theme']."</td>";
							echo "<td>".$rowSurveillance['Questionnaire']."</td>";
							if($_SESSION['Langue']=="FR"){echo "<td>".$rowSurveillance['Question']."</td>";}
							else{echo "<td>".$rowSurveillance['Question_EN']."</td>";}
							echo "<td>".stripslashes($rowSurveillance['Commentaire'])."</td>";
							echo "<td>".AfficheDateJJ_MM_AAAA($rowSurveillance['DateSurveillance'])."</td>";
							echo "<td>".$rowSurveillance['Surveille']."</td>";
							echo "<td>".$rowSurveillance['Surveillant']."</td>";
							echo "<td><a href=\"javascript:valider(".$rowSurveillance['Id_SurveillanceQuestion'].",'valider')\"><img src=\"../../Images/Valider.png\" title='si vous validez la question ne sera plus posée sur la prestation'></a></td>";
							echo "<td><a href=\"javascript:valider(".$rowSurveillance['Id_SurveillanceQuestion'].",'refuser')\"><img src=\"../../Images/Refuser.gif\"></a></td>";
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
</body>
</html>