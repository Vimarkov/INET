<!doctype html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script src="Corriger_Questionnaire6.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript">
		function AfficherQA(Id_Questionnaire,Num){
			document.getElementById('BtnPlus_'+Id_Questionnaire+'_'+Num).style.display='none';
			Num=Num+1;
			document.getElementById('Question_'+Id_Questionnaire+'_'+Num).style.display='';
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	if(isset($_POST['btnEnregistrer']) || isset($_POST['btnBrouillon']))
	{
		$req = "SELECT Id,Id_Questionnaire,
		(SELECT AutoriserQuestionsAdditionnelles FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS AutoriserQuestionsAdditionnelles
		FROM soda_surveillance 
		WHERE Id_PlannifManuelle=".$_POST['Id']."
		AND Suppr=0
		AND Etat IN ('Planifié','Brouillon')
		";
		$result=mysqli_query($bdd,$req);
		$nbQuestionnaire=mysqli_num_rows($result);
		if($nbQuestionnaire){
			while($rowQuestionnaire=mysqli_fetch_array($result)){
				$reqQuestion = "
					SELECT
						soda_surveillance_question.Id,
						soda_question.Question,
						soda_question.Question_EN,
						soda_question.Reponse,
						soda_question.Reponse_EN
					FROM
						soda_surveillance_question
					LEFT JOIN soda_question
						ON soda_surveillance_question.Id_Question = soda_question.Id
					WHERE
						soda_surveillance_question.Id_Surveillance =".$rowQuestionnaire['Id']." 
					AND soda_surveillance_question.Id_Question>0 ";
				$resultQuestion=mysqli_query($bdd,$reqQuestion);
				$nbQuestion=mysqli_num_rows($resultQuestion);
				
				if($nbQuestion > 0)
				{
					while($rowQuestion=mysqli_fetch_array($resultQuestion))
					{
						$etat="";
						if(isset($_POST['radio_'.$rowQuestion['Id']])){$etat=$_POST['radio_'.$rowQuestion['Id']];}
						$req="UPDATE soda_surveillance_question SET Etat='".$etat."' ,Commentaire='".addslashes($_POST['observation_'.$rowQuestion['Id']])."',Action='".addslashes($_POST['action_'.$rowQuestion['Id']])."',TypeNA=".$_POST['typeNA_'.$rowQuestion['Id']]." WHERE Id=".$rowQuestion['Id']." ";
						$resultUpdt=mysqli_query($bdd,$req);
					}
					
				}
				
				$resultat=0;
				if($_POST["score_".$rowQuestionnaire['Id_Questionnaire']]<>""){$resultat=$_POST["score_".$rowQuestionnaire['Id_Questionnaire']];}
				
				if(isset($_POST['btnEnregistrer'])){
					$req="UPDATE soda_surveillance SET MAJManuelle=1, SignatureSurveillant=1, SignatureSurveille=1, Resultat=".$resultat.", NumActionTracker='".$_POST["numActionTraker_".$rowQuestionnaire['Id_Questionnaire']]."', Commentaire='".addslashes($_POST["commentaire_".$rowQuestionnaire['Id_Questionnaire']])."', Etat='Clôturé', DateHeureRepondeur='".date("Y-m-d H:i:s")."' WHERE Id=".$rowQuestionnaire['Id']." ";
				}
				else{
					$req="UPDATE soda_surveillance SET MAJManuelle=1, Resultat=".$resultat.", NumActionTracker='".$_POST["numActionTraker_".$rowQuestionnaire['Id_Questionnaire']]."', Commentaire='".addslashes($_POST["commentaire_".$rowQuestionnaire['Id_Questionnaire']])."', Etat='Brouillon' WHERE Id=".$rowQuestionnaire['Id']." ";
				}
				$resultUpdt=mysqli_query($bdd,$req);
				
				//Suppression des anciennes
				$req="DELETE FROM soda_surveillance_question WHERE Id_Surveillance=".$rowQuestionnaire['Id']." AND Id_Question=0 ";
				$resultDelete=mysqli_query($bdd,$req);
				
				//Création des questions additionnelles
				if($rowQuestionnaire['AutoriserQuestionsAdditionnelles']==1){
					for($k=0;$k<10;$k++){
						if(isset($_POST['radio_'.$rowQuestionnaire['Id_Questionnaire'].'_'.$k])){
							if($_POST["QuestionAQ_".$rowQuestionnaire['Id_Questionnaire']."_".$k]<>""){
								$req="INSERT INTO soda_surveillance_question (Id_Surveillance,Id_Question,Ponderation,QuestionAdditionnelle,ReponseAdditionnelle,Etat,Commentaire,Action) 
								VALUES (".$rowQuestionnaire['Id'].",0,1,'".addslashes($_POST["QuestionAQ_".$rowQuestionnaire['Id_Questionnaire']."_".$k])."',
								'".addslashes($_POST["QuestionAR_".$rowQuestionnaire['Id_Questionnaire']."_".$k])."',
								'".$_POST["radio_".$rowQuestionnaire['Id_Questionnaire']."_".$k]."',
								'".addslashes($_POST["observation_".$rowQuestionnaire['Id_Questionnaire']."_".$k])."',
								'".$_POST["action_".$rowQuestionnaire['Id_Questionnaire']."_".$k]."') ";
								$resultInsert=mysqli_query($bdd,$req);
							}
						}
					}
				}
			}
		}
		echo "<script>opener.location='Tableau_De_Bord.php';</script>";
		if(isset($_POST['btnEnregistrer'])){
			echo "<script>window.close();</script>";
		}
	}
}

if($_POST){
	$Id_SurveillanceMere=$_POST['Id'];
}
else{
	$Id_SurveillanceMere=$_GET['Id'];
}
$req = "SELECT Id, 
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,Id_Questionnaire,
		(SELECT Annexe FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Annexe,
		(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SeuilReussite,Id_Plateforme,
		IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		DateSurveillance,Commentaire,NumActionTracker,
		(SELECT Libelle FROM new_competences_metier WHERE Id=soda_surveillance.Id_Metier) AS Metier,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,
		(SELECT AutoriserQuestionsAdditionnelles FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS AutoriserQuestionsAdditionnelles
		FROM soda_surveillance 
		WHERE Id_PlannifManuelle=".$Id_SurveillanceMere."
		AND Suppr=0
		AND Etat IN  ('Planifié','Brouillon')
		";	
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);

?>
<form id="formulaire" method="POST" action="RealiserSurveillance.php">
<table style="width:95%; height:95%; align:center;">
	<input type="hidden" name="Id" value="<?php echo $Id_SurveillanceMere; ?>">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td>
			<table style="width:100%; align:center;" class="TableCompetences">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Surveillant";}else{echo "Supervisor";}?> : </td>
					<td class="Libelle" width="10%">
						<?php echo $row['Surveillant'];?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
					<td width="10%">
						<?php echo $row['Plateforme'];?>
					</td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
					<td width="10%">
						<?php if($row['Id_Plateforme']>0){echo "N/A";}else{echo $row['Prestation'];}?>
					</td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
					<td width="10%">
						<?php echo $row['Surveille'];?>
					</td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Métier : ";}else{echo "Job : ";}?></td>
					<td width="10%">
						<?php echo $row['Metier'];?>
					</td>
				</tr>
				<tr>
					<td colspan="8" align="right">
						<input style="" class="Bouton" type="submit" name="btnBrouillon" value="<?php if($_SESSION['Langue']=="FR"){echo "Brouillon";}else{echo "Draft";}?>">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<?php 
	$result=mysqli_query($bdd,$req);
	$nbQuestionnaire=mysqli_num_rows($result);
	$AutoriserQuestionsAdditionnellesPrecedent=0;
	if($nbQuestionnaire){
		$nbQ=1;
		$result2=mysqli_query($bdd,$req);
		$rowQuestionnaire2=mysqli_fetch_array($result2);
		$IdQuestionnairePrecedent=0;
		$IdPrecedent=0;
		while($rowQuestionnaire=mysqli_fetch_array($result)){
			$rowQuestionnaire2=mysqli_fetch_array($result2);
			if($rowQuestionnaire2 <> null){
				if($rowQuestionnaire2['Id_Questionnaire']>0){
					$Id_Questionnaire2= $rowQuestionnaire2['Id_Questionnaire'];
				}
				else{
					$Id_Questionnaire2= 0;
				}
			}
			else{
				$Id_Questionnaire2= 0;
			}
	?>
		<?php 
			if($nbQ>1){
				echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' id='QuestionnaireEnTete_".$rowQuestionnaire['Id_Questionnaire']."' style='display:none;' >";
			}
			else{
				echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' id='QuestionnaireEnTete_".$rowQuestionnaire['Id_Questionnaire']."' >";
			}
		?>
			<td>
				<table class="TableCompetences" style="width:100%; align:center;background-color:#bac8ff;">
					<?php
						if($rowQuestionnaire['Annexe']<>""){
					?>
					<tr>
						<td class="Libelle" align="right" colspan="5"><?php if($LangueAffichage=="FR"){echo "Annexe";}else{echo "Appendix";}?> : 
						<?php
							if($LangueAffichage=="FR"){
								echo "<a class=\"Info\" href=\"DocumentQCM/".$rowQuestionnaire['Annexe']."\"><img src='../../Images/dossier.png' style='border:0;width:15px;' title='Annexe'></a>";
							}
							else{
								echo "<a class=\"Info\" href=\"DocumentQCM/".$rowQuestionnaire['Annexe']."\"><img src='../../Images/dossier.png' style='border:0;width:15px;' title='Appendix'>></a>";
							}
						?>
						</td>
					</tr>
					<?php
						}
					?>
					<tr>
						<td class="Libelle" align="center" colspan="5">
							<?php if($nbQ>1){ ?>
								<img style="cursor:pointer;" width='30px' src='../../Images/Gauche.png' onclick="Afficher('G','<?php echo $rowQuestionnaire['Id_Questionnaire'];?>','<?php echo $IdQuestionnairePrecedent;?>')" border='0' alt='Précédent' title='Précédent'>
							<?php	} ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<img style="cursor:pointer;" width='30px' src='../../Images/Droite.png' onclick="Afficher('D','<?php echo $rowQuestionnaire['Id_Questionnaire'];?>','<?php echo $Id_Questionnaire2; ?>')" border='0' alt='Suivant' title='Suivant'>
						</td>
					</tr>
					<tr>
						<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
						<td width="20%" class="Libelle"><?php echo $rowQuestionnaire['Theme'];?></td>
						<td width="10%" class="Libelle">Questionnaire</td>
						<td width="55%" class="Libelle"><?php echo $rowQuestionnaire['Questionnaire'];?></td>
						<td width="5%" class="Libelle" align="right"><?php echo $nbQ." / ".$nbQuestionnaire ;?></td>
					</tr>
				</table>
			</td>
		</tr>
		<?php 
			if($nbQ>1){
				echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' id='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' style='display:none;' >";
			}
			else{
				echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' id='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."'>";
			}
		?>
			<td valign="top">
			<table style="width:100%; align:center;" class="TableCompetences">
				<tr align="center">
					<td class="EnTeteTableauCompetences" style="color:#00567c;" width="65%">Question</td>
				</tr>
				<?php
					$total = 0;
					$C = 0;
					
					$reqQuestion = "
						SELECT
							soda_surveillance_question.Id,
							soda_question.Question,
							soda_question.Question_EN,
							soda_question.Reponse,
							soda_question.Reponse_EN,
							soda_question.ImageQuestion,
							soda_surveillance_question.Ponderation,
							soda_surveillance_question.Etat,
							soda_surveillance_question.Commentaire,
							soda_surveillance_question.Action,
							soda_surveillance_question.TypeNA
						FROM
							soda_surveillance_question
						LEFT JOIN soda_question
							ON soda_surveillance_question.Id_Question = soda_question.Id
						WHERE
							soda_surveillance_question.Id_Surveillance =".$rowQuestionnaire['Id']." 
						AND soda_surveillance_question.Id_Question>0 
						ORDER BY soda_surveillance_question.Id";
					$resultQuestion=mysqli_query($bdd,$reqQuestion);
					$nbQuestion=mysqli_num_rows($resultQuestion);
					
					$idsQuestion="";
					if($nbQuestion > 0)
					{
						$nb=1;
						$resultQuestion2=mysqli_query($bdd,$reqQuestion);
						$rowQuestion2=mysqli_fetch_array($resultQuestion2);
						while($rowQuestion=mysqli_fetch_array($resultQuestion))
						{
							$rowQuestion2=mysqli_fetch_array($resultQuestion2);
							
							if($rowQuestion2 <> null){
								if($rowQuestion2['Id']>0){
									$Id_Question2= $rowQuestion2['Id'];
								}
								else{
									$Id_Question2= 0;
								}
							}
							else{
								$Id_Question2= 0;
							}
								echo "<tr id='Question_".$rowQuestion['Id']."'>";
								echo "<td >
										<table width='100%'>";
											if($idsQuestion<>""){$idsQuestion.=";";}
											$idsQuestion.=$rowQuestion['Id'];
											?>
											<tr>
												<td class="Libelle" align="left">
													<?php echo $nb." / ".$nbQuestion; ?>
												</td>
											</tr>
											<?php
											echo "<tr>";
												echo "<td >";
												if($_SESSION['Langue']=="FR"){
													echo "<b>Q</b> ".$rowQuestion['Question']."<br><br><span style='color:#0a7800;'><b>R</b> ".$rowQuestion['Reponse']."</span>";
												}
												else{
													echo "<b>Q</b> ".$rowQuestion['Question_EN']."<br><br><span style='color:#0a7800;'><b>R</b> ".$rowQuestion['Reponse_EN']."</span> ";
												}
												if($rowQuestion['ImageQuestion']<>""){
													if(file_exists ('ImageQCM/'.$rowQuestion['ImageQuestion'])){
														echo "<br><img src='ImageQCM/".$rowQuestion['ImageQuestion']."' style='border:0;' title='Image'>";
													}
												}
												echo "</td>
											</tr>";
											
											$checkNA = "";
											$observation ="";

											$checkC = "";
											$checkNC = "";
											$action = "";
											$typeNA = "";
											$cloture = "";
											$display = "style='display:none;'";
											$displayNC = "style='display:none;'";
											$displayNA = "style='display:none;'";
											
											if($rowQuestion['Etat']=='C'){$checkC = "checked";}
											elseif($rowQuestion['Etat']=='NC'){$checkNC = "checked";$action = stripslashes($rowQuestion['Action']);$displayNC = "";}
											elseif($rowQuestion['Etat']=='NA'){$checkNA = "checked";$typeNA =$rowQuestion['TypeNA'];$displayNA = "";}
											
											$observation =stripslashes($rowQuestion['Commentaire']);

											echo "<tr>
												<td>
											";
											echo "<input type='hidden' id='ponderation_".$rowQuestion['Id']."' name='ponderation_".$rowQuestion['Id']."' value='".$rowQuestion['Ponderation']."' >";
											echo "<input type='hidden' id='ValideQA_".$rowQuestion['Id']."' name='ValideQA_".$rowQuestion['Id']."' value='0' >";
											if($_SESSION['Langue']=="FR"){
											echo "
													<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['Id']."' value='C' ".$checkC.">Conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['Id']."' value='NC' ".$checkNC.">Non conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['Id']."' value='NA' ".$checkNA.">Non applicable
												";
											}
											else{
											echo "
													<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['Id']."' value='C' ".$checkC.">Compliant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['Id']."' value='NC' ".$checkNC.">Non Compliant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['Id']."' value='NA' ".$checkNA.">Not applicable
												";	
											}
											echo "</td>
												</tr>
											";
											?>
										</tr>
										<tr height="5px" id="tr2_<?php echo $rowQuestion['Id']; ?>" style="display:none;"></tr>
										<input type="hidden" id="NomQuestionnaire_<?php echo $rowQuestion['Id'];?>" name="NomQuestionnaire_<?php echo $rowQuestion['Id'];?>" value="<?php echo $rowQuestionnaire['Questionnaire']; ?>" />
										<tr id="tr_<?php echo $rowQuestion['Id']; ?>">
											<td>
												<label id="label_<?php echo $rowQuestion['Id'];?>"></label><br>
												<input style="width:570px;" <?php echo $display; ?> type="text" id="<?php echo "observation_".$rowQuestion['Id'];?>" name="<?php echo "observation_".$rowQuestion['Id'];?>" value="<?php echo stripslashes($observation);?>">
												<br>
												<label <?php echo $displayNC; ?> id="labelAction_<?php echo $rowQuestion['Id'];?>">Action</label><br>
												<select <?php echo $displayNC; ?> class="actions_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" id="<?php echo "action_".$rowQuestion['Id'];?>" name="<?php echo "action_".$rowQuestion['Id'];?>">
												<?php 
													if($_SESSION['Langue']=="FR"){
														if($action==""){echo "<option value='' selected></option>";}
														else{
															if($action<>"Action immédiate" && $action<>"Action immédiate + Action Tracker"){echo "<option value='".$action."' selected>".$action."</option>";}
														}
														$selected="";
														if($action=="Action immédiate"){$selected="selected";}
														echo "<option value='Action immédiate' ".$selected.">Action immédiate</option>";
														$selected="";
														if($action=="Action immédiate + Action Tracker"){$selected="selected";}
														echo "<option value='Action immédiate + Action Tracker' ".$selected.">Action immédiate + Action Tracker</option>";
													}
													else{
														if($action==""){echo "<option value='' selected></option>";}
														else{
															if($action<>"Immediate action" && $action<>"Immediate action + Action Tracker"){echo "<option value='".$action."' selected>".$action."</option>";}
														}
														$selected="";
														if($action=="Immediate action"){$selected="selected";}
														echo "<option value='Immediate action' ".$selected.">Immediate action</option>";
														$selected="";
														if($action=="Immediate action + Action Tracker"){$selected="selected";}
														echo "<option value='Immediate action + Action Tracker' ".$selected.">Immediate action + Action Tracker</option>";
													}
												?>
												</select>
												<label <?php echo $displayNA; ?> id="labelNA_<?php echo $rowQuestion['Id'];?>"><?php if($_SESSION['Langue']=="FR"){echo "Type de non applicabilité";}else{echo "Type of non-applicability";}?></label><br>
												<select <?php echo $displayNA; ?> class="typeNA_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" id="<?php echo "typeNA_".$rowQuestion['Id'];?>" name="<?php echo "typeNA_".$rowQuestion['Id'];?>">
													<option value="0" selected></option>
													<option value="1" <?php if($typeNA=="1"){echo "selected";}?>><?php if($_SESSION['Langue']=="FR"){echo "Liée aux circonstances de la surveillance (temporaire)";}else{echo "Related to circumstances of supervision (temporary)";}?></option>
													<option value="2" <?php if($typeNA=="2"){echo "selected";}?>><?php if($_SESSION['Langue']=="FR"){echo "Liée à la prestation (permanente)";}else{echo "Linked to the activity (permanent)";}?></option>
												</select>
											</td>
										</tr>
											<?php
											$nb++;
							echo "
									</table>
								</td>
							</tr>";
							$IdPrecedent=$rowQuestion['Id'];
						}
					}
				?>
			</table>
		</td></tr>
		<tr><td height="5"></td></tr>
		<input type="hidden" id="AutoriserQuestionsAdditionnelles_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" name="AutoriserQuestionsAdditionnelles_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" value="<?php echo $rowQuestionnaire['AutoriserQuestionsAdditionnelles']; ?>" />
		<input type="hidden" id="NomQuestionnaire2_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" name="NomQuestionnaire2_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" value="<?php echo $rowQuestionnaire['Questionnaire']; ?>" />
		<?php 
			if($nbQ>1 || $rowQuestionnaire['AutoriserQuestionsAdditionnelles']==0){
				echo "<tr id='QuestionnaireAdditionnel_".$rowQuestionnaire['Id_Questionnaire']."' style='display:none;' >";
			}
			else{
				echo "<tr id='QuestionnaireAdditionnel_".$rowQuestionnaire['Id_Questionnaire']."'>";
			}
		?>
			<td valign="top">
				<table style="width:100%; align:center;" class="TableCompetences">
					<tr align="center">
						<td class="EnTeteTableauCompetences" colspan="2" style="color:#00567c;" width="65%"><?php if($_SESSION['Langue']=="FR"){echo "Question additionnelle";}else{echo "Additional question";}?></td>
					</tr>
					<?php
					$reqQuestionA = "
						SELECT
							soda_surveillance_question.Id,
							soda_surveillance_question.QuestionAdditionnelle,
							soda_surveillance_question.ReponseAdditionnelle,
							soda_surveillance_question.Ponderation,
							soda_surveillance_question.Etat,
							soda_surveillance_question.Commentaire,
							soda_surveillance_question.Action,
							soda_surveillance_question.TypeNA
						FROM
							soda_surveillance_question
						WHERE
							soda_surveillance_question.Id_Surveillance =".$rowQuestionnaire['Id']." 
						AND soda_surveillance_question.Id_Question=0 ";
					$resultQuestionA=mysqli_query($bdd,$reqQuestionA);
					$nbQuestionA=mysqli_num_rows($resultQuestionA);
					$nbQA=0;
					$laCouleur="#ffffff";
					for($k=0;$k<10;$k++)
					{
						if($k==0){
							echo "<tr bgcolor='".$laCouleur."' id='Question_".$rowQuestionnaire['Id_Questionnaire']."_".$k."'>";
						}
						else{
							$displayQA="display:none;";
							if($nbQA<$nbQuestionA){
								$displayQA="";
							}
							echo "<tr bgcolor='".$laCouleur."' id='Question_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' style='".$displayQA."'>";
						}
							echo "<td width='90%' >
									<table width='100%'>";
										$question="";
										$reponse="";
										$checkNA = "";
										$observation ="";

										$checkC = "";
										$checkNC = "";
										$action = "";
										$cloture = "";
										$display = "style='display:none;'";
										$displayNC = "style='display:none;'";
										$displayNA = "style='display:none;'";
										if($nbQA<$nbQuestionA){
											$rowQA=mysqli_fetch_array($resultQuestionA);
											$question=stripslashes($rowQA['QuestionAdditionnelle']);
											$reponse=stripslashes($rowQA['ReponseAdditionnelle']);
											$observation =stripslashes($rowQA['Commentaire']);
											
											if($rowQA['Etat']=="C"){
												$checkC = "checked";
											}
											elseif($rowQA['Etat']=="NC"){
												$checkNC = "checked";
												$displayNC="";
											}
											$action = stripslashes($rowQA['Action']);;
										}
										
										echo "
										<tr>";
											echo "<td >";
											echo "<b>Q</b> <input type='text' size='150' id='QuestionAQ_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' name='QuestionAQ_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" value='".$question."' />";
											echo "</td>
										</tr>
										<tr>";
											echo "<td >";
											echo "<b>R</b> <input type='text' size='150' id='QuestionAR_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' name='QuestionAR_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='".$reponse."' />";
											echo "</td>
										</tr>
										";										

										echo "<tr>
											<td>
										";
										echo "<input type='hidden' id='ponderation_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' name='ponderation_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='1' >";
										echo "<input type='hidden' id='ValideQA_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' name='ValideQA_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='1' >";
										if($_SESSION['Langue']=="FR"){
										echo "
												<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='C' ".$checkC.">Conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='NC' ".$checkNC.">Non conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											";
										}
										else{
										echo "
												<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='C' ".$checkC.">Compliant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' value='NC' ".$checkNC.">Non Compliant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											";	
										}
										echo "</td>
											</tr>
										";
										?>
									</tr>
									<tr height="5px" id="tr2_<?php echo $rowQuestionnaire['Id_Questionnaire']."_".$k; ?>" style="display:none;"></tr>
									<input type="hidden" id="NomQuestionnaire_<?php echo $rowQuestionnaire['Id_Questionnaire']."_".$k;?>" name="NomQuestionnaire_<?php echo$rowQuestionnaire['Id_Questionnaire']."_".$k;?>" value="<?php echo $rowQuestionnaire['Questionnaire']; ?>" />
									<tr id="tr_<?php echo $rowQuestionnaire['Id_Questionnaire']."_".$k; ?>">
										<td>
											<label id="label_<?php echo $rowQuestionnaire['Id_Questionnaire']."_".$k;?>"></label><br>
											<input style="width:570px;" type="text" id="<?php echo "observation_".$rowQuestionnaire['Id_Questionnaire']."_".$k;?>" name="<?php echo "observation_".$rowQuestionnaire['Id_Questionnaire']."_".$k;?>" value="<?php echo stripslashes($observation);?>">
											<br>
											<label <?php echo $displayNC; ?> id="labelAction_<?php echo $rowQuestionnaire['Id_Questionnaire']."_".$k;?>">Action</label><br>
											<select <?php echo $displayNC; ?> class="actions_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" id="<?php echo "action_".$rowQuestionnaire['Id_Questionnaire']."_".$k;?>" name="<?php echo "action_".$rowQuestionnaire['Id_Questionnaire']."_".$k;?>">
											<?php 
												if($_SESSION['Langue']=="FR"){
													if($action==""){echo "<option value='' selected></option>";}
													else{
														if($action<>"Action immédiate" && $action<>"Action immédiate + Action Tracker"){echo "<option value='".$action."' selected>".$action."</option>";}
													}
													$selected="";
													if($action=="Action immédiate"){$selected="selected";}
													echo "<option value='Action immédiate' ".$selected.">Action immédiate</option>";
													$selected="";
													if($action=="Action immédiate + Action Tracker"){$selected="selected";}
													echo "<option value='Action immédiate + Action Tracker' ".$selected.">Action immédiate + Action Tracker</option>";
												}
												else{
													if($action==""){echo "<option value='' selected></option>";}
													else{
														if($action<>"Immediate action" && $action<>"Immediate action + Action Tracker"){echo "<option value='".$action."' selected>".$action."</option>";}
													}
													$selected="";
													if($action=="Immediate action"){$selected="selected";}
													echo "<option value='Immediate action' ".$selected.">Immediate action</option>";
													$selected="";
													if($action=="Immediate action + Action Tracker"){$selected="selected";}
													echo "<option value='Immediate action + Action Tracker' ".$selected.">Immediate action + Action Tracker</option>";
												}
											?>
											</select>
											<label <?php echo $display; ?> id="labelNA_<?php echo $rowQuestionnaire['Id_Questionnaire']."_".$k;?>"><?php if($_SESSION['Langue']=="FR"){echo "Type de non applicabilité";}else{echo "Type of non-applicability";}?></label><br>
											<select <?php echo $display; ?> class="typeNA_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" id="<?php echo "typeNA_".$rowQuestionnaire['Id_Questionnaire']."_".$k;?>" name="<?php echo "typeNA_".$rowQuestionnaire['Id_Questionnaire']."_".$k;?>">
												<option value="0" selected></option>
												<option value="1"><?php if($_SESSION['Langue']=="FR"){echo "Liée aux circonstances de la surveillance (temporaire)";}else{echo "Related to circumstances of supervision (temporary)";}?></option>
												<option value="2"><?php if($_SESSION['Langue']=="FR"){echo "Liée à la prestation (permanente)";}else{echo "Linked to the activity (permanent)";}?></option>
											</select>
										</td>
									</tr>
										<?php
						echo "
								</table>
							</td>
							<td width='10%' >";
						if($k<9){
							if(($nbQA+1)>=$nbQuestionA){
								echo "<img style='cursor:pointer;' id='BtnPlus_".$rowQuestionnaire['Id_Questionnaire']."_".$k."' width='30px' src='../../Images/add.png' onclick='AfficherQA(".$rowQuestionnaire['Id_Questionnaire'].",".$k.")' border='0' alt='Suivant' title='Suivant'>";
							}
						}	
						echo	"</td>
						</tr>";
						if($laCouleur=="#ffffff"){$laCouleur="#cdd9ff";}
						else{$laCouleur="#ffffff";}
						$nbQA++;
					}
					?>
				</table>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<?php 
			if($nbQ>1){
				echo "<tr id='QuestionnaireNote_".$rowQuestionnaire['Id_Questionnaire']."' style='display:none;' >";
			}
			else{
				echo "<tr id='QuestionnaireNote_".$rowQuestionnaire['Id_Questionnaire']."'>";
			}
		?>
			<td valign="top">
				<table style="width:100%; align:center;" class="TableCompetences">
					<tr><td height="5"></td></tr>
					<tr>
						<td style="color:#00567c;" colspan="3" align="left" class="Libelle">
							Note :
							<?php
								$note = "100";
							?>
							<input style="" readonly id="note_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" type="text" value="<?php echo $note."%" ?>" size="5"/>
							
							<input id="score_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" name="score_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" type="hidden" value="<?php echo $note ?>" size="5"/>
							<input id="seuil_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" name="seuil_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" type="hidden" value="<?php echo $rowQuestionnaire['SeuilReussite'] ?>" size="5"/>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td colspan="3" style="color:#00567c;" align="left" class="Libelle">
							<?php if($_SESSION['Langue']=="FR"){echo "N° fiche Action Tracker :";}else{echo "Action Tracker form #:";}?>
							<input class="AT" onKeyUp="nombre(this)" name="numActionTraker_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" id="numActionTraker_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" type="text" value="<?php echo $rowQuestionnaire['NumActionTracker'];?>" size="10"/>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td colspan="3" style="color:#00567c;" valign="top" align="left" class="Libelle">
							<?php if($_SESSION['Langue']=="FR"){echo "Commentaire :";}else{echo "Comment :";}?>
							<textarea name="commentaire_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" id="commentaire_<?php echo $rowQuestionnaire['Id_Questionnaire'];?>" cols="100" rows="5"  style="font-size:14px;resize:none;"><?php echo stripslashes($rowQuestionnaire['Commentaire']);?></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php 
			if($nbQ>1){
				echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' id='QuestionnaireFin_".$rowQuestionnaire['Id_Questionnaire']."' style='display:none;' >";
			}
			else{
				echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' id='QuestionnaireFin_".$rowQuestionnaire['Id_Questionnaire']."' >";
			}
		?>
			<td>
				<table class="TableCompetences" style="width:100%; align:center;background-color:#bac8ff;">
					<tr>
						<td class="Libelle" align="center" colspan="5">
							<?php if($nbQ>1){ ?>
								<img style="cursor:pointer;" width='30px' src='../../Images/Gauche.png' onclick="Afficher('G','<?php echo $rowQuestionnaire['Id_Questionnaire'];?>','<?php echo $IdQuestionnairePrecedent;?>')" border='0' alt='Précédent' title='Précédent'>
							<?php	} ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<img style="cursor:pointer;" width='30px' src='../../Images/Droite.png' onclick="Afficher('D','<?php echo $rowQuestionnaire['Id_Questionnaire'];?>','<?php echo $Id_Questionnaire2; ?>')" border='0' alt='Suivant' title='Suivant'>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php 
			$nbQ++;
			$IdQuestionnairePrecedent=$rowQuestionnaire['Id_Questionnaire'];
			if($rowQuestionnaire['AutoriserQuestionsAdditionnelles']==1){$AutoriserQuestionsAdditionnellesPrecedent=1;}
			echo "<script>Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."');</script>";
		}
		?>
		<tr id="resultat" style="display:none;">
			<td>
				<table style="width:100%; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle" align="center">
							<img style="cursor:pointer;" width='30px' src='../../Images/Gauche.png' onclick="Afficher('resultat',0,<?php echo $IdQuestionnairePrecedent;?>)" border='0' alt='Précédent' title='Précédent'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<tr><td height="10"></td></tr>
					<tr>
						<td colspan="3" style="" class="Libelle">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="checkbox" class="signatures" name="signatureSurveillant" value="signatureSurveillant" >
							&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le Surveillant confirme la réalisation de la surveillance et les potentielles actions qui en découlent";}else{echo "The Supervisor confirms the completion of the surveillance and the potential actions arising from it";}?>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td colspan="3" style="" class="Libelle">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="checkbox" class="signatures" name="signatureSurveille" value="signatureSurveille" >
							&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le surveillé a été informé des constats réalisés lors de cette surveillance";}else{echo "The supervisee has been informed of the findings of the surveillance";}?>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td colspan="7" align="center">
							<input style="" class="Bouton" type="submit" name="btnEnregistrer" onclick="return VerifChamps();" value="<?php if($_SESSION['Langue']=="FR"){echo "Clôturer";}else{echo "Enclose";}?>">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input style="" class="Bouton" type="submit" name="btnBrouillon" value="<?php if($_SESSION['Langue']=="FR"){echo "Brouillon";}else{echo "Draft";}?>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php 
		}
	?>
</table>
</form>
</body>
</html>