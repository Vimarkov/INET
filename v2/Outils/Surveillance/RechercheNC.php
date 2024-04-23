<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel(MotClef,Theme,Questionnaire,NumQuestion,Annee)
		{window.open("RechercheNC_Export.php?motCle="+MotClef+"&theme="+Theme+"&Questionnaire="+Questionnaire+"&numQuestion="+NumQuestion+"&annee="+Annee,"PageRechercheNC","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
</script>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="RechercheNC.php">
	<tr>
		<td>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0" style="background-color:#b6b5d3;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Surveillance/Tableau_De_Bord.php'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						?>
						<?php
							if($_SESSION['Langue']=="FR"){echo "Gestion des surveillances # Recherche non conformités";}
							else{echo "Monitoring management # Search Nonconformities";}
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
				<td colspan="6"><b>&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Critères de recherche";}else{echo "Search options";}?> : </b></td>
			</tr>
			<tr>
				<td width=8%>
					&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Mot clé :";}else{echo "Keyword :";}?>
				</td>
				<td colspan="5">
					<?php
					if (!empty($_POST['motCle'])){
						$motCle = $_POST['motCle'];
					}
					else{
						$motCle = "";
					}
					?>
					<input type="texte" name="motCle" size="50" value="<?php echo $motCle; ?>">
				</td>
				<td width="8%">
					<?php
						$annee=$_SESSION['FiltreSurveillance_Annee'];
						if($_POST){$annee=$_POST['annee'];}
					?>
					&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				</td>
				<td width=10%>
					<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
				</td>
			</tr>
			<tr>
				<td width=8%>
					&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "N° question";}else{echo "Question n°";}?>
				</td>
				<td width=10% colspan="5">
					<?php
					if (!empty($_POST['numQuestion'])){
						$numQuestion = $_POST['numQuestion'];
					}
					else{
						$numQuestion = "";
					}
					?>
					<input type="texte" style="text-align:center;" name="numQuestion" size="10" value="<?php echo $numQuestion; ?>">
				</td>
				<td width=8%>
					&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Thème :";}else{echo "Theme :";}?>
				</td>
				<td width=10%>
					<select name="theme" onchange="submit();">
					<?php
					$req = "SELECT new_surveillances_theme.Id, new_surveillances_theme.Nom ";
					$req .= "FROM new_surveillances_theme ";
					$req .= "ORDER BY new_surveillances_theme.Nom;";
					
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$ThemeSelect = 0;
					$Selected = "";
					if ($nbTheme > 0)
					{
						if (!empty($_POST['theme'])){
							echo "<option name='0' value='0' Selected></option>";
							if ($ThemeSelect == 0){$ThemeSelect = $_POST['theme'];}
							while($row=mysqli_fetch_array($resultTheme))
							{
								if ($row[0] == $_POST['theme']){
									$Selected = "Selected";
								}
								echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
								$Selected = "";
							}
						}
						else{
							echo "<option name='0' value='0' Selected></option>";
							$ThemeSelect == 0;
							while($row=mysqli_fetch_array($resultTheme))
							{
								echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
							}
						}
					 }
					 ?>
					</select>
				</td>
				<td width=10%>
					&nbsp; Questionnaire :
				</td>
				<td width=30%>
					<select name="Questionnaire">
					<?php
					$req = "SELECT new_surveillances_questionnaire.ID, CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom, ";
					$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_surveillances_questionnaire.ID_Plateforme) AS Plateforme ";
					$req .= "FROM new_surveillances_questionnaire ";
					$req .= "WHERE new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." ";
					$req .= "AND new_surveillances_questionnaire.Supprime =0 ";
					$req .= "ORDER BY Actif, new_surveillances_questionnaire.Nom, Plateforme;";
					
					$resultQuestionnaire=mysqli_query($bdd,$req);
					$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
					
					$QuestionnaireSelect = 0;
					$Selected = "";
					if ($nbQuestionnaire > 0)
					{
						if (!empty($_POST['Questionnaire'])){
							echo "<option name='0' value='0' Selected></option>";
							if ($QuestionnaireSelect == 0){$QuestionnaireSelect = $_POST['Questionnaire'];}
							while($row=mysqli_fetch_array($resultQuestionnaire))
							{
								if ($row[0] == $_POST['Questionnaire']){
									$Selected = "Selected";
								}
								if ($row['Plateforme'] == ""){
									echo "<option name='".$row['ID']."' value='".$row['ID']."' ".$Selected.">".$row['Nom']."</option>";
								}
								else{
									echo "<option name='".$row['ID']."' value='".$row['ID']."' ".$Selected.">".$row['Nom']." (".$row['Plateforme'].")</option>";
								}
								$Selected = "";
							}
						}
						else{
							echo "<option name='0' value='0' Selected></option>";
							$QuestionnaireSelect == 0;
							while($row=mysqli_fetch_array($resultQuestionnaire))
							{
								if ($row['Plateforme'] == ""){
									echo "<option name='".$row['ID']."' value='".$row['ID']."' >".$row['Nom']."</option>";
								}
								else{
									echo "<option name='".$row['ID']."' value='".$row['ID']."' >".$row['Nom']." (".$row['Plateforme'].")</option>";
								}
							}
						}
					}
					 
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="10"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher";}else{echo "Search";}?>"></td>
			</tr>
		</table>
	</td></tr>
	<tr>
		<td height="4" align="right">
			<a href="javascript:OuvreFenetreExcel('<?php echo $motCle;?>','<?php echo $ThemeSelect;?>','<?php echo $QuestionnaireSelect;?>','<?php echo $numQuestion;?>','<?php echo $annee;?>')">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
			</a>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr align="center">
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION['Langue']=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION['Langue']=="FR"){echo "Unité d'exploitation du questionnaire";}else{echo "Questionnaire operating unit";}?></td>
					<td class="EnTeteTableauCompetences" width="20">Questionnaire</td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION['Langue']=="FR"){echo "Unité d'exploitation surveillance";}else{echo "Monitoring operating unit";}?></td>
					<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION['Langue']=="FR"){echo "Prestation surveillance";}else{echo "Monitoring activity";}?></td>
					<td class="EnTeteTableauCompetences" width="4"><?php if($_SESSION['Langue']=="FR"){echo "N° question";}else{echo "Question n°";}?>°</td>
					<td class="EnTeteTableauCompetences" width="200"><?php if($_SESSION['Langue']=="FR"){echo "Description de la NC / Preuves";}else{echo "NC Description / Evidences";}?></td>
					<td class="EnTeteTableauCompetences" width="200">Action</td>
					<td class="EnTeteTableauCompetences" width="10">Date</td>
				</tr>
				<?php
				//Mise en forme de la requête
				$req = "SELECT new_surveillances_surveillance_question.ID, ";
				$req .= "(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_questionnaire.ID_Theme = new_surveillances_theme.ID) AS Theme, ";
				$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_surveillances_questionnaire.ID_Plateforme) AS PlateformeQ, ";
				$req .= "new_surveillances_questionnaire.Nom AS Questionnaire, ";
				$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme, ";
				$req .= "new_competences_prestation.Libelle AS Prestation, ";
				$req .= "(SELECT new_surveillances_question.Numero FROM new_surveillances_question WHERE new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question) AS Question, ";
				$req .= "new_surveillances_surveillance_question.Commentaire, ";
				$req .= "new_surveillances_surveillance_question.Action, ";
				$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01',new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance, ";
				$req .= "new_surveillances_surveillance_question.Cloturee ";
				$req .= "FROM (((new_surveillances_surveillance ";
				$req .= "LEFT JOIN new_competences_prestation ";
				$req .= "ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id) ";
				$req .= "LEFT JOIN new_surveillances_questionnaire ";
				$req .= "ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id) ";
				$req .= "LEFT JOIN new_surveillances_surveillance_question ";
				$req .= "ON new_surveillances_surveillance.ID = new_surveillances_surveillance_question.ID_Surveillance) ";
				$req .= "WHERE new_surveillances_surveillance_question.Etat='NC' AND ";
				if ($motCle <> ""){
					$req .= "(new_surveillances_surveillance_question.Commentaire LIKE '%".$motCle."%' OR new_surveillances_surveillance_question.Action LIKE '%".$motCle."%') AND ";
				}
				if ($numQuestion <> ""){
					$req .= "(SELECT new_surveillances_question.Numero FROM new_surveillances_question WHERE new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question) ='".$numQuestion."' AND ";
				}
				if ($ThemeSelect <> 0){
					$req .= "new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." AND ";
					if ($QuestionnaireSelect <> 0){
						$req .= "new_surveillances_questionnaire.ID =".$QuestionnaireSelect." AND ";
					}
				}
				if($annee<>""){
					$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', YEAR(new_surveillances_surveillance.DateReplanif), YEAR(new_surveillances_surveillance.DatePlanif)) ='".$annee."' AND ";
				}
				$req = substr($req,0,-4);
				$req .= "ORDER BY Theme, PlateformeQ, Questionnaire, Plateforme, Question, DateSurveillance DESC; ";
				$resultQuestion=mysqli_query($bdd,$req);
				$nbQuestion=mysqli_num_rows($resultQuestion);
				
				if($nbQuestion > 0)
				{
					while($row=mysqli_fetch_array($resultQuestion))
					{
						echo "<tr>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$row['Theme']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$row['PlateformeQ']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='20'>".$row['Questionnaire']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$row['Plateforme']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$row['Prestation']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='4'>".$row['Question']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='200'>".$row['Commentaire']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='200'>".$row['Action']."</td>";
						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".AfficheDateJJ_MM_AAAA($row['DateSurveillance'])."</td>";
					}
				}
				?>
			</table>
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