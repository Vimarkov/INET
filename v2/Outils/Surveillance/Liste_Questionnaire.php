<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreAjoutQuestion(ID_Questionnaire,ID_Personne)
		{var w=window.open("Ajout_Question.php?Mode=A&ID=0&ID_Questionnaire="+ID_Questionnaire+"&ID_Personne="+ID_Personne,"PageAQuestion","status=no,menubar=no,width=1000,height=1250");
		w.focus();}
	function OuvreFenetreHistoriqueQuestionnaire(ID_Questionnaire)
		{var w=window.open("Historique_Questionnaire.php?ID_Questionnaire="+ID_Questionnaire,"PageHQuestion","status=no,menubar=no,scrollbars=yes,width=1000,height=450");
		w.focus();}
	function OuvreFenetreModifQuestion(Id,ID_Questionnaire,ID_Personne)
		{var w=window.open("Ajout_Question.php?Mode=M&ID="+Id+"&ID_Questionnaire="+ID_Questionnaire+"&ID_Personne="+ID_Personne,"PageMQuestion","status=no,menubar=no,width=1000,height=250");
		w.focus();}
	function OuvreFenetreSupprQuestion(Id,ID_Questionnaire,ID_Personne)
	{
			if(window.confirm('Vous êtes sûr de vouloir supprimer ?')){
				var w=window.open("Ajout_Question.php?Mode=S&ID="+Id+"&ID_Questionnaire="+ID_Questionnaire+"&ID_Personne="+ID_Personne,"PageSQuestion","status=no,menubar=no,width=500,height=150");
				w.focus();}
	}
	function OuvreFenetreAjoutQuestionnaire(ID_Personne)
		{var w=window.open("Ajout_Questionnaire.php?Mode=A&ID=0&ID_Personne="+ID_Personne,"PageAQuestionnaire","status=no,menubar=no,width=700,height=400");
		w.focus();}
	function OuvreFenetreModifQuestionnaire(Id,ID_Personne)
		{var w=window.open("Ajout_Questionnaire.php?Mode=M&ID="+Id+"&ID_Personne="+ID_Personne,"PageMQuestionnaire","status=no,menubar=no,width=500,height=150");
		w.focus();}
	function OuvreFenetreSupprQuestionnaire(Id,ID_Personne)
	{
			if(window.confirm('Vous êtes sûr de vouloir supprimer ?')){
				var w=window.open("Ajout_Questionnaire.php?Mode=S&ID="+Id+"&ID_Personne="+ID_Personne,"PageSQuestionnaire","status=no,menubar=no,width=500,height=150");
				w.focus();
			}
	}
	function QuestionnairePDF(Id,Langue)
		{window.open("QuestionnairePDF.php?Id="+Id+"&Langue="+Langue,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function QuestionnaireExcel(Id,Langue)
		{window.open("QuestionnaireExcel.php?Id="+Id+"&Langue="+Langue,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	Liste_Questionnaire = new Array();
	function Change_Visible(){
		var i;
		var nbcheked=0;
		document.getElementById('ajoutQuestion').style.display="none";document.getElementById('historiqueQuestionnaire').style.display="none";
		document.getElementById('QuestionnaireExcel_FR').style.display="none";
		document.getElementById('QuestionnaireExcel_EN').style.display="none";
		document.getElementById('QuestionnairePDF_FR').style.display="none";
		document.getElementById('QuestionnairePDF_EN').style.display="none";
		table = document.getElementsByTagName('input');
		for (l=0;l<table.length;l++){
			if (table[l].type == 'radio'){
				if(table[l].checked == true){
					nbcheked++;
					for(i=0;i<Liste_Questionnaire.length;i++){
						if (Liste_Questionnaire[i][0]==table[l].value){
							document.getElementById('ajoutQuestion').style.display="";
							document.getElementById('ajoutQuestion').href="javascript:OuvreFenetreAjoutQuestion("+Liste_Questionnaire[i][0]+","+document.getElementById('ID_Personne').value+")";
							document.getElementById('historiqueQuestionnaire').style.display="";
							document.getElementById('historiqueQuestionnaire').href="javascript:OuvreFenetreHistoriqueQuestionnaire("+Liste_Questionnaire[i][0]+")";
							document.getElementById('QuestionnaireExcel_FR').style.display="";
							document.getElementById('QuestionnaireExcel_EN').style.display="";
							document.getElementById('QuestionnairePDF_FR').style.display="";
							document.getElementById('QuestionnairePDF_EN').style.display="";
							}
						}
					}
				}
			}
			if(nbcheked == 0)
			{
				Table_Questions = document.getElementById('Table_Questions').getElementsByTagName('TR');
				for(l=1;l<Table_Questions.length+1;l++){Table_Questions[l].style.display = 'none';}
			}
		}
</script>
<?php
$AccesQualite=false;
if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteAssistantFormationInterne,$IdPosteFormateur))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,8)))
	{$AccesQualite=true;}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" method="POST" action="Liste_Questionnaire.php">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="3">
			<table class="GeneralPage" style="width:100%; border-spacing:0;background-color:#68de2a;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Surveillance/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "Gestion des surveillances # Questionnaires";}
							else{echo "Monitoring management # Questionnaire";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>

	<td width="30%">
		<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
			<tr>
				<td colspan="6"><b>&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Critères de recherche";}else{echo "Serach options";}?> : </b></td>
			</tr>
			<tr>
			<tr>
				<td width=8%>
					&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?> :
				</td>
				<td width=12%>
					<select id="theme" name="theme" onchange="submit();">
					<?php
					$req = "SELECT new_surveillances_theme.Id, new_surveillances_theme.Nom
                            FROM new_surveillances_theme
                            ORDER BY new_surveillances_theme.Nom;";
					
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$ThemeSelect = 0;
					$Selected = "";
					if ($nbTheme > 0)
					{
						if (!empty($_POST['theme']))
						{
							echo "<option name='0' value='0' Selected></option>";
							if ($ThemeSelect == 0){$ThemeSelect = $_POST['theme'];}
							while($row=mysqli_fetch_array($resultTheme))
							{
								if ($row[0] == $_POST['theme']){$Selected = "Selected";}
								echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
								$Selected = "";
							}
						}
						else
						{
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
			</tr>
			<tr><td height="4"></td></tr>
		</table>
	</td>
	<td width="70%"></td>
	</tr>
	<tr><td height="4"><input id="ID_Personne" type="hidden" name="Mode" value="<?php echo $_SESSION['Id_Personne']; ?>"></td></tr>
	
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="3">
			<table style="width:100%;">
				<tr>
					<td align="center">
						<?php
                            if($_SESSION['Id_Personne'] == 10852 || $_SESSION['Id_Personne'] == 857 || $_SESSION['Id_Personne'] == 2526 || $_SESSION['Id_Personne'] == 295)
                            {
    							if($_SESSION['Langue']=="FR"){echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutQuestionnaire(".$_SESSION['Id_Personne'].")'>&nbsp;Ajouter un questionnaire&nbsp;</a>";}
    							else{echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutQuestionnaire(".$_SESSION['Id_Personne'].")'>&nbsp;Add a questionnaire&nbsp;</a>";}
                            }
    				    ?>
					</td>
					<td  align="center">
						<?php
							$IdPlateforme=0;
							$DQ_FR="";
							$DQ_EN="";
							if (!empty($_POST['QuestionnaireSelect']))
							{
								$req = "
                                    SELECT
                                        new_surveillances_questionnaire.ID_Plateforme,
                                        new_surveillances_questionnaire.DQ_FR,
                                        new_surveillances_questionnaire.DQ_EN
                                    FROM
                                        new_surveillances_questionnaire
                                    WHERE
                                        new_surveillances_questionnaire.ID =".$_POST['QuestionnaireSelect']." ";
								$result=mysqli_query($bdd,$req);
								$nbResult=mysqli_num_rows($result);
								$IdPlateforme=0;
								$DQ_FR="";
								$DQ_EN="";
								if($nbResult > 0)
								{
									$rowPlateforme=mysqli_fetch_array($result);
									$IdPlateforme=$rowPlateforme['ID_Plateforme'];
									$DQ_FR=$rowPlateforme['DQ_FR'];
									$DQ_EN=$rowPlateforme['DQ_EN'];
								}
							}
							if(($IdPlateforme > 0 && $AccesQualite) || $_SESSION['Id_Personne'] == 1351 || $_SESSION['Id_Personne'] == 406 || $_SESSION['Id_Personne'] == 5208 || $_SESSION['Id_Personne'] == 857 || $_SESSION['Id_Personne'] == 5618 || $_SESSION['Id_Personne'] == 2526 || $_SESSION['Id_Personne'] == 295 || $IdPersonneConnectee=="2526" || $IdPersonneConnectee=="8033" || $IdPersonneConnectee=="1762" || $IdPersonneConnectee=="12529")
							{
								if($_SESSION['Langue']=="FR"){echo "<a id='ajoutQuestion' style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutQuestion()'>&nbsp;Ajouter une question&nbsp;</a>";}
								else{echo "<a id='ajoutQuestion' style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutQuestion()'>&nbsp;Add a question&nbsp;</a>";}
							}
						?>
					</td>
					<td  align="center">
						<?php
							if (!empty($_POST['QuestionnaireSelect']))
							{
								
								if($_SESSION['Langue']=="FR"){
									echo "<a id='historiqueQuestionnaire' style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreHistoriqueQuestionnaire(".$_POST['QuestionnaireSelect'].")'>&nbsp;Historique du questionnaire&nbsp;</a>";
									}
								else{echo "<a id='historiqueQuestionnaire' style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreHistoriqueQuestionnaire(".$_POST['QuestionnaireSelect'].")'>&nbsp;Questionnaire history&nbsp;</a>";}
							}
						?>
					</td>
					<td>
						<?php
							if (!empty($_POST['QuestionnaireSelect']))
							{
								if($nbResult > 0)
								{
									echo "<a id='QuestionnaireExcel_FR' style='text-decoration:none;' class='Bouton' href=\"javascript:QuestionnaireExcel('".$_POST['QuestionnaireSelect']."','FR')\">&nbsp;Questionnaire FR Excel&nbsp;</a>&nbsp;&nbsp;&nbsp;";
									echo "<a id='QuestionnaireExcel_EN' style='text-decoration:none;' class='Bouton' href=\"javascript:QuestionnaireExcel('".$_POST['QuestionnaireSelect']."','EN')\">&nbsp;Questionnaire EN Excel&nbsp;</a>&nbsp;&nbsp;&nbsp;";
									echo "<a id='QuestionnairePDF_FR' style='text-decoration:none;' class='Bouton' href=\"javascript:QuestionnairePDF('".$_POST['QuestionnaireSelect']."','FR')\">&nbsp;Questionnaire FR PDF&nbsp;</a>";
									echo "<a id='QuestionnairePDF_EN' style='text-decoration:none;' class='Bouton' href=\"javascript:QuestionnairePDF('".$_POST['QuestionnaireSelect']."','EN')\">&nbsp;Questionnaire EN PDF&nbsp;</a>";
								}
							}
						?>
					</td>
				</tr>
				<tr>
					<td width="30%" valign="top">
						<table class="TableCompetences" style="width:100%;">
							<tr align="center">
								<td class="EnTeteTableauCompetences" width="5%"></td>
								<td class="EnTeteTableauCompetences" width="20"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?></td>
								<td class="EnTeteTableauCompetences" width="50"><?php if($_SESSION["Langue"]=="FR"){echo "Questionnaires";}else{echo "Questionnaire";}?></td>
								<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION["Langue"]=="FR"){echo "A/I";}else{echo "A/I";}?></td>
								<td class="EnTeteTableauCompetences" width="2"></td>
								<td class="EnTeteTableauCompetences" width="2"></td>
							</tr>
							<?php
								if ($ThemeSelect <> 0){
								$req = "
                                    SELECT
                                        new_surveillances_questionnaire.ID,
                                        new_surveillances_questionnaire.ID_Plateforme,
                                        new_surveillances_questionnaire.DQ_FR,
                                        new_surveillances_questionnaire.DQ_EN,
                                        (SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_surveillances_questionnaire.ID_Plateforme) AS Plateforme,
                                        new_surveillances_questionnaire.Nom,
										new_surveillances_questionnaire.Actif
                                    FROM
                                        new_surveillances_questionnaire
                                    WHERE
                                        new_surveillances_questionnaire.ID_Theme =".$ThemeSelect."
                                        AND new_surveillances_questionnaire.Supprime =0
                                    ORDER BY
										new_surveillances_questionnaire.Actif,
                                        Plateforme,
                                        new_surveillances_questionnaire.Nom ;";
									$resultQuestionnaire=mysqli_query($bdd,$req);
									$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);

									if($nbQuestionnaire > 0)
									{
										$i=0;
										while($rowQuestionnaire=mysqli_fetch_array($resultQuestionnaire))
										{
											echo "<tr>";
											$checkedQuestionnaire = "";
											if (!empty($_POST['QuestionnaireSelect']))
											{
												if ($_POST['QuestionnaireSelect'] == $rowQuestionnaire['ID'] ){$checkedQuestionnaire = "checked";}
											}
											echo "<script>Liste_Questionnaire[".$i."] = new Array(".$rowQuestionnaire['ID'].",".$rowQuestionnaire['ID_Plateforme'].",'".$rowQuestionnaire['DQ_FR']."','".$rowQuestionnaire['DQ_EN']."');</script>\n";
											$i+=1;
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'><input id='QuestionnaireSelect' onchange='submit();' type='radio' name='QuestionnaireSelect' value='".$rowQuestionnaire['ID']."' ".$checkedQuestionnaire."></td>";
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'>".$rowQuestionnaire['Plateforme']."</td>";
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$rowQuestionnaire['Nom']."</td>";
											if($rowQuestionnaire['Actif']==0){
												echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>A</td>";
											}
											else{
												echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>I</td>";
											}
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
											if($_SESSION['Id_Personne'] == 10852 || $_SESSION['Id_Personne'] == 857 || $_SESSION['Id_Personne'] == 2526 || $_SESSION['Id_Personne'] == 295)
											{
												echo "<a href='javascript:OuvreFenetreModifQuestionnaire(".$rowQuestionnaire['ID'].",".$_SESSION['Id_Personne'].")'>";
												echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier le questionnaire'>";
												echo "</a>&nbsp;";
											}
											echo "</td>";
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
											if($_SESSION['Id_Personne'] == 10852 || $_SESSION['Id_Personne'] == 857 || $_SESSION['Id_Personne'] == 2526 || $_SESSION['Id_Personne'] == 295)
											{
												echo "<a href='javascript:OuvreFenetreSupprQuestionnaire(".$rowQuestionnaire['ID'].",".$_SESSION['Id_Personne'].")'>";
												echo "<img src='../../Images/Suppression.gif' border='0' alt='Suppression' title='Supprimer le questionnaire'>";
												echo "</a>&nbsp;";
											}
											echo "</td>";
											echo "</tr>";
										}
									}
								}
							?>
						</table>
					</td>
					<td width="70%" valign="top" colspan="5">
						<table class="TableCompetences" style="width:100%;" id="Table_Questions">
							<tr align="center">
								<td class="EnTeteTableauCompetences" width="5">N°</td>
								<td class="EnTeteTableauCompetences" width="250">Question / Réponse</td>
								<td class="EnTeteTableauCompetences" width="10"><?php if($_SESSION["Langue"]=="FR"){echo "Modifiable";}else{echo "Editable";}?></td>
								<td class="EnTeteTableauCompetences" width="2"></td>
							</tr>
							<?php
								if (!empty($_POST['QuestionnaireSelect']))
								{
									$req = "
                                        SELECT
                                            new_surveillances_question.ID,
                                            new_surveillances_question.Numero,
                                            new_surveillances_questionnaire.ID_Plateforme,
                                            new_surveillances_question.Question,
                                            new_surveillances_question.Question_EN,
											new_surveillances_question.Reponse,
                                            new_surveillances_question.Reponse_EN,
                                            new_surveillances_question.Modifiable
                                        FROM
                                            new_surveillances_question
                                        LEFT JOIN new_surveillances_questionnaire
                                            ON new_surveillances_questionnaire.ID = new_surveillances_question.ID_Questionnaire
                                        WHERE
                                            new_surveillances_questionnaire.ID =".$_POST['QuestionnaireSelect']."
                                            AND new_surveillances_question.Supprime =0
                                        ORDER BY
                                            new_surveillances_question.Numero ;";
									$resultQuestion=mysqli_query($bdd,$req);
									$nbQuestion=mysqli_num_rows($resultQuestion);

									if($nbQuestion > 0)
									{
										while($rowQuestion=mysqli_fetch_array($resultQuestion))
										{
											echo "<tr>";
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'>".$rowQuestion['Numero']."</td>";
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='250'>
												Question FR : ".$rowQuestion['Question']."
												<br>Question EN : ".$rowQuestion['Question_EN']."
												<br><span style='color:#333abb;'>Réponse FR : ".$rowQuestion['Reponse']."
												<br>Réponse EN : ".$rowQuestion['Reponse_EN']."</span>
												</td>";
											$Modiable ="N";
											if($rowQuestion['Modifiable']=="1"){$Modiable ="O";}
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10'>".$Modiable."</td>";
											echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2'>";
											if (($rowQuestion['ID_Plateforme'] > 0 && $AccesQualite) || $_SESSION['Id_Personne'] == 1351 || $_SESSION['Id_Personne'] == 406 || $_SESSION['Id_Personne'] == 5208 || $_SESSION['Id_Personne'] == 857 || $_SESSION['Id_Personne'] == 5618 || $_SESSION['Id_Personne'] == 2526 || $_SESSION['Id_Personne'] == 295 || $IdPersonneConnectee=="2526" || $IdPersonneConnectee=="8033" || $IdPersonneConnectee=="1762" || $IdPersonneConnectee=="12529")
											{
												echo "<a href='javascript:OuvreFenetreModifQuestion(".$rowQuestion['ID'].",".$_POST['QuestionnaireSelect'].",".$_SESSION['Id_Personne'].")'>";
												echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier la question'>";
												echo "</a>&nbsp;";
												echo "<a href='javascript:OuvreFenetreSupprQuestion(".$rowQuestion['ID'].",".$_POST['QuestionnaireSelect'].",".$_SESSION['Id_Personne'].")'>";
												echo "<img src='../../Images/Suppression.gif' border='0' alt='Suppression' title='Supprimer la question'>";
												echo "</a>&nbsp;";
											}
											echo "</td>";
										}
									}
								}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

<?php
	echo "<script>Change_Visible();</script>";
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>