<!DOCTYPE html>

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
</head>
<body>

<?php
$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

if($_POST)
{
	if(isset($_POST['btnEnregistrer']) || isset($_POST['btnBrouillon']))
	{
		$reqQuestion = "
			SELECT
				soda_surveillance_question.Id
			FROM
				soda_surveillance_question
			WHERE
				soda_surveillance_question.Id_Surveillance =".$_POST['Id']." 
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
		if($_POST["score_".$_POST['Id_Questionnaire']]<>""){$resultat=$_POST["score_".$_POST['Id_Questionnaire']];}
		if(isset($_POST['btnEnregistrer'])){
			$req="UPDATE soda_surveillance SET MAJManuelle=1, SignatureSurveillant=1, SignatureSurveille=1, Resultat=".$resultat.", NumActionTracker='".$_POST["numActionTraker_".$_POST['Id_Questionnaire']]."', Commentaire='".addslashes($_POST["commentaire_".$_POST['Id_Questionnaire']])."', Etat='Clôturé', DateHeureRepondeur='".date("Y-m-d H:i:s")."' WHERE Id=".$_POST['Id']." ";
		}
		else{
			$req="UPDATE soda_surveillance SET MAJManuelle=1, Resultat=".$resultat.", NumActionTracker='".$_POST["numActionTraker_".$_POST['Id_Questionnaire']]."', Commentaire='".addslashes($_POST["commentaire_".$_POST['Id_Questionnaire']])."', Etat='Brouillon' WHERE Id=".$_POST['Id']." ";
		}
		$resultUpdt=mysqli_query($bdd,$req);
		
		if($_POST["Etat"]=="En cours - papier" || $_POST["Etat"]=="Brouillon"){
			//Suppression des anciennes
			$req="DELETE FROM soda_surveillance_question WHERE Id_Surveillance=".$_POST['Id']." AND Id_Question=0 ";
			$resultDelete=mysqli_query($bdd,$req);
			
			if(isset($_POST['btnEnregistrer'])){
				$req="UPDATE soda_surveillance SET DateSurveillance='".TrsfDate_($_POST['dateSurveillance'])."',Id_Surveille=".$_POST['Id_Surveille'].",Id_Metier=".$_POST['Id_Metier'].", SignatureSurveillant=1, SignatureSurveille=1, Resultat=".$resultat.", NumActionTracker='".$_POST["numActionTraker_".$_POST['Id_Questionnaire']]."', Commentaire='".addslashes($_POST["commentaire_".$_POST['Id_Questionnaire']])."', Etat='Clôturé', DateHeureRepondeur='".date("Y-m-d H:i:s")."' WHERE Id=".$_POST['Id']." ";
				$resultUpdt=mysqli_query($bdd,$req);
			}
			else{
				$req="UPDATE soda_surveillance SET DateSurveillance='".TrsfDate_($_POST['dateSurveillance'])."',Id_Surveille=".$_POST['Id_Surveille'].",Id_Metier=".$_POST['Id_Metier'].", SignatureSurveillant=1, SignatureSurveille=1, Resultat=".$resultat.", NumActionTracker='".$_POST["numActionTraker_".$_POST['Id_Questionnaire']]."', Commentaire='".addslashes($_POST["commentaire_".$_POST['Id_Questionnaire']])."', Etat='Brouillon' WHERE Id=".$_POST['Id']." ";
				$resultUpdt=mysqli_query($bdd,$req);
			}
		
			if($_POST["AutoriserQuestionsAdditionnelles"]==1){
				for($k=0;$k<10;$k++){
					if(isset($_POST['radio_'.$_POST['Id_Questionnaire'].'_'.$k])){
						if($_POST["QuestionAQ_".$_POST['Id_Questionnaire']."_".$k]<>""){
							$req="INSERT INTO soda_surveillance_question (Id_Surveillance,Id_Question,Ponderation,QuestionAdditionnelle,ReponseAdditionnelle,Etat,Commentaire,Action) 
							VALUES (".$_POST['Id'].",0,1,'".addslashes($_POST["QuestionAQ_".$_POST['Id_Questionnaire']."_".$k])."',
							'".addslashes($_POST["QuestionAR_".$_POST['Id_Questionnaire']."_".$k])."',
							'".$_POST["radio_".$_POST['Id_Questionnaire']."_".$k]."',
							'".addslashes($_POST["observation_".$_POST['Id_Questionnaire']."_".$k])."',
							'".addslashes($_POST["action_".$_POST['Id_Questionnaire']."_".$k])."') ";
							$resultInsert=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}
		else{
			//Update des questions additionnelles
			$reqQuestion = "
				SELECT
					soda_surveillance_question.Id
				FROM
					soda_surveillance_question
				WHERE
					soda_surveillance_question.Id_Surveillance =".$_POST['Id']." 
				AND soda_surveillance_question.Id_Question=0 ";
			$resultQuestion=mysqli_query($bdd,$reqQuestion);
			$nbQuestion=mysqli_num_rows($resultQuestion);
			
			if($nbQuestion > 0)
			{
				while($rowQuestion=mysqli_fetch_array($resultQuestion))
				{
					$req="UPDATE soda_surveillance_question 
						SET 
						QuestionAdditionnelle='".addslashes($_POST["QuestionAQ_".$rowQuestion['Id']])."',
						ReponseAdditionnelle='".addslashes($_POST["QuestionAR_".$rowQuestion['Id']])."',
						Etat='".$_POST['radio_'.$rowQuestion['Id']]."' ,
						Commentaire='".addslashes($_POST['observation_'.$rowQuestion['Id']])."',
						Action='".addslashes($_POST['action_'.$rowQuestion['Id']])."',
						TypeNA=".$_POST['typeNA_'.$rowQuestion['Id']]." 
						WHERE Id=".$rowQuestion['Id']." ";
					$resultUpdt=mysqli_query($bdd,$req);
				}
				
			}
		}
		
		echo "<script>opener.location='Tableau_De_Bord.php?Menu=8';</script>";
		if(isset($_POST['btnEnregistrer'])){
			echo "<script>window.close();</script>";
		}

	}
	elseif(isset($_POST['btnModifFiche']))
	{
		$req="UPDATE soda_surveillance SET MAJManuelle=1, NumActionTracker='".$_POST["numActionTraker_".$_POST['Id_Questionnaire']]."' WHERE Id=".$_POST['Id']." ";
		$resultUpdt=mysqli_query($bdd,$req);

		echo "<script>opener.location='Tableau_De_Bord.php?Menu=8';</script>";
		echo "<script>window.close();</script>";

	}
	elseif(isset($_POST['btnAttestation']))
	{
		$attestation=0;
		if(isset($_POST['attestationSurveillance'])){$attestation=1;}
		$autonomie=0;
		if(isset($_POST['autonomieSurveillant'])){
			$autonomie=$_POST['autonomieSurveillant'];
		}
		$req="UPDATE soda_surveillance SET AttestationSurveillance=".$attestation.",Autonome=".$autonomie.", DateAttestation='".date('Y-m-d')."', Id_AttestationSurveillance='".$_SESSION["Id_Personne"]."' WHERE Id=".$_POST['Id']." ";
		$resultUpdt=mysqli_query($bdd,$req);
		
		if($_POST['Mode']=="V"){
			echo "<script>opener.location='Tableau_De_Bord.php?Menu=29';</script>";
		}
		else{
			echo "<script>opener.location='Tableau_De_Bord.php?Menu=8';</script>";
		}
		echo "<script>window.close();</script>";

	}
}
else{
	if($_GET['Mode']=="S"){
		$req="UPDATE soda_surveillance SET MAJManuelle=1, Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id=".$_GET['Id']." ";
		$resultUpdt=mysqli_query($bdd,$req);
		
		echo "<script>opener.location='Tableau_De_Bord.php?Menu=8';</script>";
		echo "<script>window.close();</script>";
	}
}

if($_POST){$Id=$_POST['Id'];}
else{$Id=$_GET['Id'];}

if($_POST){$Mode=$_POST['Mode'];}
else{$Mode=$_GET['Mode'];}

$req = "SELECT Id,Id_Old,EnFormation,AttestationSurveillance,Autonome,
		(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Id_Theme,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,Id_Questionnaire,
		(SELECT Annexe FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Annexe,
		(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SeuilReussite,Id_Plateforme,
		IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,Id_Prestation,
		DateSurveillance,NumActionTracker,Etat,Commentaire,
		(SELECT Libelle FROM new_competences_metier WHERE Id=soda_surveillance.Id_Metier) AS Metier,Id_Metier,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,Id_Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,Id_Surveillant,
		(SELECT AutoriserQuestionsAdditionnelles FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS AutoriserQuestionsAdditionnelles,
		(SELECT NonAleatoire FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS NonAleatoire
		FROM soda_surveillance 
		WHERE Id=".$Id."
		AND Suppr=0
		";
		
$result=mysqli_query($bdd,$req);
$rowQuestionnaire=mysqli_fetch_array($result);

?>
<form id="formulaire" method="POST" action="Modif_Surveillance.php">
<input type="hidden" name="Id" value="<?php if($_POST){echo $_POST['Id'];}else{echo $_GET['Id'];} ?>">
<input type="hidden" name="Mode" value="<?php if($_POST){echo $_POST['Mode'];}else{echo $_GET['Mode'];} ?>">
<input type="hidden" name="Id_Questionnaire" value="<?php echo $rowQuestionnaire['Id_Questionnaire']; ?>">
<input type="hidden" name="Etat" value="<?php echo $rowQuestionnaire['Etat']; ?>">
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:95%; height:95%; align:center;">
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td>
			<table style="width:100%; align:center;" class="TableCompetences">
				<?php
					if($rowQuestionnaire['Annexe']<>""){
				?>
				<tr>
					<td colspan="6">
					
					</td>
					<td class="Libelle" align="right" colspan="2"><?php if($LangueAffichage=="FR"){echo "Annexe";}else{echo "Appendix";}?> : 
					<?php
						if($LangueAffichage=="FR"){
							echo "<a class=\"Info\" href=\"DocumentQCM/".$rowQuestionnaire['Annexe']."\"><img src='../../Images/dossier.jpg' style='border:0;width:15px;' title='Annexe'></a>";
						}
						else{
							echo "<a class=\"Info\" href=\"DocumentQCM/".$rowQuestionnaire['Annexe']."\"><img src='../../Images/dossier.jpg' style='border:0;width:15px;' title='Appendix'>></a>";
						}
					?>
					</td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Surveillant";}else{echo "Supervisor";}?> : </td>
					<td class="Libelle" width="10%">
						<?php echo $rowQuestionnaire['Surveillant'];?>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?> : </td>
					<td class="Libelle" width="10%">
						<?php echo $rowQuestionnaire['Id'];?>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date surveillance";}else{echo "Monitoring date";}?> : </td>
					<td class="Libelle" width="10%">
						<?php 
						if($rowQuestionnaire['Etat']=="En cours - papier" || $rowQuestionnaire['Etat']=="Brouillon"){
						?>
							<input style="width:100px;" type="date" name="dateSurveillance" id="dateSurveillance" value="<?php echo AfficheDateFR($rowQuestionnaire['DateSurveillance']);?>" />
						<?php
						}
						else{
						?>
						<input type="hidden" name="dateSurveillance" id="dateSurveillance" value="<?php echo AfficheDateFR($rowQuestionnaire['DateSurveillance']);?>" />
						<?php
							echo AfficheDateJJ_MM_AAAA($rowQuestionnaire['DateSurveillance']);
						}
						?>
					</td>
					<?php if($rowQuestionnaire['Id_Old']>0){ ?>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "N° :<br>(Gestion des surveillances)";}else{echo "N° :<br>(Monitoring management)";}?></td>
					<td class="Libelle" width="10%">
						<?php echo $rowQuestionnaire['Id_Old'];?>
					</td>
					<?php } ?>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
					<td width="10%">
						<?php echo $rowQuestionnaire['Plateforme'];?>
					</td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
					<td width="10%">
						<?php if($rowQuestionnaire['Id_Plateforme']>0){echo "N/A";}else{echo $rowQuestionnaire['Prestation'];}?>
					</td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
					<td width="10%">
						<?php 
						if($rowQuestionnaire['Etat']=="En cours - papier" || $rowQuestionnaire['Etat']=="Brouillon"){
						?>
							<select id="Id_Surveille" name="Id_Surveille" style="width:200px;" onchange="submit()">
								<?php
								$Id_Surveille=0;
								if($rowQuestionnaire['Id_Plateforme']>0){
									echo "<option value='0'></option>";
									$requetePersonne="
										SELECT DISTINCT
											new_rh_etatcivil.Id,
											CONCAT(Nom, ' ', Prenom) as NomPrenom
										FROM
											new_rh_etatcivil
										INNER JOIN new_competences_personne_prestation
											ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne
										WHERE
											((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)=".$rowQuestionnaire['Id_Plateforme']." 
											AND Date_Debut<='".date('Y-m-d')."'
											AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
											AND new_rh_etatcivil.Id IN 
												(
													SELECT Id_Personne
													FROM new_competences_personne_metier
													WHERE Id_Metier NOT IN (
													SELECT new_competences_metier.Id
													FROM soda_questionnaire_exceptiongroupemetier,new_competences_metier
													WHERE soda_questionnaire_exceptiongroupemetier.Suppr=0 
													AND Id_GroupeMetierSODA=soda_questionnaire_exceptiongroupemetier.Id_GroupeMetier
													AND soda_questionnaire_exceptiongroupemetier.Id_Questionnaire=".$rowQuestionnaire['Id_Questionnaire']."
													)
												)
											)
											OR new_rh_etatcivil.Id=".$rowQuestionnaire['Id_Surveille']."
										ORDER BY NomPrenom ASC";
								}
								else{
									$requetePersonne="
										SELECT DISTINCT
											new_rh_etatcivil.Id,
											CONCAT(Nom, ' ', Prenom) as NomPrenom
										FROM
											new_rh_etatcivil
										INNER JOIN new_competences_personne_prestation
											ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne
										WHERE
											(new_competences_personne_prestation.Id_Prestation=".$rowQuestionnaire['Id_Prestation']." 
											AND Date_Debut<='".date('Y-m-d')."'
											AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
											AND new_rh_etatcivil.Id IN 
												(
													SELECT Id_Personne
													FROM new_competences_personne_metier
													WHERE Id_Metier NOT IN (
													SELECT new_competences_metier.Id
													FROM soda_questionnaire_exceptiongroupemetier,new_competences_metier
													WHERE soda_questionnaire_exceptiongroupemetier.Suppr=0 
													AND Id_GroupeMetierSODA=soda_questionnaire_exceptiongroupemetier.Id_GroupeMetier
													AND soda_questionnaire_exceptiongroupemetier.Id_Questionnaire=".$rowQuestionnaire['Id_Questionnaire']."
													)
												)
											)
											OR new_rh_etatcivil.Id=".$rowQuestionnaire['Id_Surveille']."
										ORDER BY NomPrenom ASC";
								}
								$result_Personne= mysqli_query($bdd,$requetePersonne);
								while ($row_Personne=mysqli_fetch_array($result_Personne))
								{
									$selected="";
									if($_GET){
										if($rowQuestionnaire['Id_Surveille']==$row_Personne['Id']){
											$Id_Surveille=$rowQuestionnaire['Id_Surveille'];
											$selected="selected";
										}
									}
									else{
										if($_POST['Id_Surveille']==$row_Personne['Id']){
											$Id_Surveille=$_POST['Id_Surveille'];
											$selected="selected";
										}
									}
									echo "<option value='".$row_Personne['Id']."' ".$selected.">".$row_Personne['NomPrenom']."</option>\n";
								}
								?>
							</select>
						<?php
						
						}
						else{
							echo $rowQuestionnaire['Surveille'];
						}
						?>
					</td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Métier : ";}else{echo "Job : ";}?></td>
					<td width="10%">
						<?php 
						if($rowQuestionnaire['Etat']=="En cours - papier" || $rowQuestionnaire['Etat']=="Brouillon"){
						?>
							<select id="Id_Metier" name="Id_Metier" style="width:200px;">
								<?php
								$Id_Metier=0;
								if($Id_Surveille==0){echo "<option value='0'></option>";}
								else{
									$requeteMetier="
										SELECT DISTINCT
											Id_Metier,
											(SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier
										FROM
											new_competences_personne_metier
										WHERE Id_Personne=".$Id_Surveille."
										AND new_competences_personne_metier.Id_Metier NOT IN 
											(
												SELECT new_competences_metier.Id
												FROM soda_questionnaire_exceptiongroupemetier,new_competences_metier
												WHERE soda_questionnaire_exceptiongroupemetier.Suppr=0 
												AND Id_GroupeMetierSODA=soda_questionnaire_exceptiongroupemetier.Id_GroupeMetier
												AND soda_questionnaire_exceptiongroupemetier.Id_Questionnaire=".$rowQuestionnaire['Id_Questionnaire']."
											)";
									$requeteMetier.="ORDER BY Futur ASC";
									$result_Metier= mysqli_query($bdd,$requeteMetier);
									$nbMetier=mysqli_num_rows($result_Metier);
									if($nbMetier>0){
										while ($row_Metier=mysqli_fetch_array($result_Metier))
										{
											$selected="";
											if($_GET){
												if($rowQuestionnaire['Id_Metier']==$row_Metier['Id_Metier']){
													$Id_Metier=$row_Metier['Id_Metier'];
													$selected="selected";
												}
											}
											else{
												if($_POST['Id_Surveille']==$_POST['oldSurveille']){
													if($rowQuestionnaire['Id_Metier']==$row_Metier['Id_Metier']){$selected="selected";}
												}
												else{
													if($Id_Metier==0){
														$Id_Metier=$row_Metier['Id_Metier'];
														$selected="selected";
													}
												}
											}
											echo "<option value='".$row_Metier['Id_Metier']."' ".$selected.">".$row_Metier['Metier']."</option>\n";
										}
									}
								}
								?>
							</select>
						<?php
						}
						else{
							echo $rowQuestionnaire['Metier'];
						}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<?php 
	$AutoriserQuestionsAdditionnellesPrecedent=0;
	?>
		<?php 
			echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."' >";
		?>
			<td>
				<table class="TableCompetences" style="width:100%; align:center;background-color:#bac8ff;">
					<tr>
						<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme : ";}?></td>
						<td width="20%" class="Libelle"><?php echo $rowQuestionnaire['Theme'];?></td>
						<td width="10%" class="Libelle">Questionnaire</td>
						<td width="55%" class="Libelle"><?php echo $rowQuestionnaire['Questionnaire'];?></td>
					</tr>
				</table>
			</td>
		</tr>
		<?php 
			echo "<tr class='Questionnaire_".$rowQuestionnaire['Id_Questionnaire']."'>";
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
						ORDER BY soda_question.Ordre						
						";
					$resultQuestion=mysqli_query($bdd,$reqQuestion);
					$nbQuestion=mysqli_num_rows($resultQuestion);
					
					$idsQuestion="";
					if($nbQuestion > 0)
					{
						$nb=1;
						while($rowQuestion=mysqli_fetch_array($resultQuestion))
						{
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
											$checkC = "";
											$checkNC = "";
											$display = "";
											$displayNC = "style='display:none;'";
											$displayNA = "style='display:none;'";
											$action = "";
											$typeNA = "";
											
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
										<tr height="5px" id="tr2_<?php echo $rowQuestion['Id']; ?>"></tr>
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
										<tr>
											<td>
											<?php
												if($_SESSION['Langue']=="FR"){echo "Pondération : ";}
												else{echo "Weighting :";}
												echo $rowQuestion['Ponderation'];
											?>
											</td>
										</tr>
											<?php
											$nb++;
							echo "
									</table>
								</td>
							</tr>";
						}
					}
				?>
			</table>
		</td></tr>
		<input type="hidden" id="AutoriserQuestionsAdditionnelles" name="AutoriserQuestionsAdditionnelles" value="<?php echo $rowQuestionnaire['AutoriserQuestionsAdditionnelles']; ?>" />
		<?php 
			if($rowQuestionnaire['Etat']=="En cours - papier" || $rowQuestionnaire['Etat']=="Brouillon"){
				if($rowQuestionnaire['AutoriserQuestionsAdditionnelles']==1){
		?>
				<tr id="QuestionsAdditionnelles">
					<td>
						<table style="width:100%; align:center;" class="TableCompetences">
							<tr><td height="5"></td></tr>
							<tr>
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
						</table>
					</td>
				</tr>
		<?php
				}
			}
			else{
				$reqQuestion = "
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
				$resultQuestion=mysqli_query($bdd,$reqQuestion);
				$nbQuestion=mysqli_num_rows($resultQuestion);
				if($nbQuestion > 0){
						$AutoriserQuestionsAdditionnellesPrecedent=1;
				?>
				<input type="hidden" id="AutoriserQuestionsAdditionnelles" name="AutoriserQuestionsAdditionnelles" value="<?php echo $AutoriserQuestionsAdditionnellesPrecedent; ?>" />
				<tr id="QuestionsAdditionnelles">
					<td>
						<table style="width:100%; align:center;" class="TableCompetences">
							<tr>
								<td valign="top">
									<table style="width:100%; align:center;" class="TableCompetences">
										<tr align="center">
											<td class="EnTeteTableauCompetences" colspan="2" style="color:#00567c;" width="65%"><?php if($_SESSION['Langue']=="FR"){echo "Question additionnelle";}else{echo "Additional question";}?></td>
										</tr>
										<?php
									
										$nb=1;
										$laCouleur="#ffffff";
										while($rowQuestion=mysqli_fetch_array($resultQuestion))
										{
											echo "<tr bgcolor='".$laCouleur."' id='Question_".$rowQuestion['Id']."'>";
											echo "<td width='90%' >
													<table width='100%'>";
														echo "
														<tr>";
															echo "<td >";
															echo "<b>Q</b> <input type='text' size='150' id='QuestionAQ_".$rowQuestion['Id']."' name='QuestionAQ_".$rowQuestion['Id']."' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" value=\"".stripslashes($rowQuestion['QuestionAdditionnelle'])."\" />";
															echo "</td>
														</tr>
														<tr>";
															echo "<td >";
															echo "<b>R</b> <input type='text' size='150' id='QuestionAR_".$rowQuestion['Id']."' name='QuestionAR_".$rowQuestion['Id']."' value=\"".stripslashes($rowQuestion['ReponseAdditionnelle'])."\" />";
															echo "</td>
														</tr>
														";
														
														$checkC = "";
														$checkNC = "";
														$display = "";
														$displayNC = "style='display:none;'";
														$displayNA = "style='display:none;'";
														$action = "";
														
														if($rowQuestion['Etat']=='C'){$checkC = "checked";}
														elseif($rowQuestion['Etat']=='NC'){$checkNC = "checked";$action = stripslashes($rowQuestion['Action']);$displayNC = "";}
														
														$observation =stripslashes($rowQuestion['Commentaire']);

														echo "<tr>
															<td>
														";
														echo "<input type='hidden' id='ponderation_".$rowQuestion['Id']."' name='ponderation_".$rowQuestion['Id']."' value='1' >";
														echo "<input type='hidden' id='ValideQA_".$rowQuestion['Id']."' name='ValideQA_".$rowQuestion['Id']."' value='1' >";
														if($_SESSION['Langue']=="FR"){
														echo "
																<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestion['Id']."' value='C' ".$checkC.">Conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestion['Id']."' value='NC' ".$checkNC.">Non conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															";
														}
														else{
														echo "
																<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestion['Id']."' value='C' ".$checkC.">Compliant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																<input style='height: 20px; width: 20px;' onchange=\"Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."')\" class='radioNoteQA' type='radio' name='radio_".$rowQuestion['Id']."' value='NC' ".$checkNC.">Non Compliant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															";	
														}
														echo "</td>
															</tr>
														";
														?>
													</tr>
													<tr height="5px" id="tr2_<?php echo $rowQuestion['Id']; ?>" ></tr>
													<tr id="tr_<?php echo $rowQuestion['Id']; ?>" >
														<td>
															<label id="label_<?php echo $rowQuestion['Id'];?>"></label><br>
															<input style="width:570px;" type="text" id="<?php echo "observation_".$rowQuestion['Id'];?>" name="<?php echo "observation_".$rowQuestion['Id'];?>" value="<?php echo $observation;?>">
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
																<option value="1"><?php if($_SESSION['Langue']=="FR"){echo "Liée aux circonstances de la surveillance (temporaire)";}else{echo "Related to circumstances of supervision (temporary)";}?></option>
																<option value="2"><?php if($_SESSION['Langue']=="FR"){echo "Liée à la prestation (permanente)";}else{echo "Linked to the activity (permanent)";}?></option>
															</select>
														</td>
													</tr>
													<tr>
														<td>
														<?php
															if($_SESSION['Langue']=="FR"){echo "Pondération : ";}
															else{echo "Weighting :";}
															echo $rowQuestion['Ponderation'];
														?>
														</td>
													</tr>
														<?php
											echo "
													</table>
												</td>
											</tr>";
											if($laCouleur=="#ffffff"){$laCouleur="#cdd9ff";}
											else{$laCouleur="#ffffff";}
											
										}
										?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php 
				}
			}
		?>
		<tr id="resultat">
			<td>
				<table style="width:100%; align:center;" class="TableCompetences">
					<?php 
					$i=0;
					?>
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
					<tr><td height="10"></td></tr>
					<tr>
						<td colspan="3" style="" class="Libelle">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="checkbox" <?php if($rowQuestionnaire['Etat']=="Clôturé"){echo "checked";}?> class="signatures" name="signatureSurveillant" value="signatureSurveillant" >
							&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le Surveillant confirme la réalisation de la surveillance et les potentielles actions qui en découlent";}else{echo "The Supervisor confirms the completion of the surveillance and the potential actions arising from it";}?>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td colspan="3" style="" class="Libelle">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="checkbox"  <?php if($rowQuestionnaire['Etat']=="Clôturé"){echo "checked";}?> class="signatures" name="signatureSurveille" value="signatureSurveille" >
							&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le surveillé a été informé des constats réalisés lors de cette surveillance";}else{echo "The supervisee has been informed of the findings of the surveillance";}?>
						</td>
					</tr>
					<?php
						$req="SELECT Id FROM soda_theme WHERE Id=".$rowQuestionnaire['Id_Theme']." AND Id_Qualification IN (
							SELECT DISTINCT Id_Qualification_Parrainage 
							FROM new_competences_relation 
							WHERE (Evaluation='X'
							AND Date_Debut<='".date('Y-m-d')."'
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
							)
							AND Suppr=0
							AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
							AND Id_Personne=".$_SESSION['Id_Personne']."
						) ";
						$resultSurTheme= mysqli_query($bdd,$req);	
						$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
						
						if((($rowQuestionnaire['Etat']=="En cours - papier" || $rowQuestionnaire['Etat']=="Brouillon") && $rowQuestionnaire['Id_Surveillant']==$_SESSION['Id_Personne']) || $nbSuperAdmin>0){
						?>
						<tr><td height="5"></td></tr>
						<tr>
							<td colspan="7" align="center">
								<input style="" class="Bouton" type="submit" name="btnEnregistrer" onClick="return VerifChamps2();" value="<?php if($_SESSION['Langue']=="FR"){echo "Clôturer";}else{echo "Enclose";}?>">
								<?php if($rowQuestionnaire['Etat']=="En cours - papier" || $rowQuestionnaire['Etat']=="Brouillon"){ ?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input style="" class="Bouton" type="submit" name="btnBrouillon" value="<?php if($_SESSION['Langue']=="FR"){echo "Brouillon";}else{echo "Draft";}?>">
								<?php } ?>
							</td>
						</tr>
						<?php 
						}
						else{
							if($rowQuestionnaire['Etat']=="Clôturé" && ($rowQuestionnaire['Id_Surveillant']==$_SESSION['Id_Personne'] || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteChargeMissionOperation)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8)))){
						?>
						<tr><td height="5"></td></tr>
						<tr>
							<td colspan="7" align="center">
								<input style="" class="Bouton" type="submit" name="btnModifFiche" value="<?php if($_SESSION['Langue']=="FR"){echo "Modifier n° Fiche AT";}else{echo "Modify AT sheet number";}?>">
							</td>
						</tr>
						<?php			
							}
						}
					?>
					<tr><td height="5"></td></tr>
					<tr <?php if($rowQuestionnaire['Etat']=="Clôturé" &&  $rowQuestionnaire['EnFormation']==1){echo "style='display:;'";}else{echo "style='display:none;'";} ?>>
						<td colspan="3" style="" class="Libelle">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="checkbox"  <?php if($rowQuestionnaire['AttestationSurveillance']==1){echo "checked";}?> class="attestation" name="attestationSurveillance" value="attestationSurveillance" >
							&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le Surveillant qualifié atteste la bonne réalisation de la surveillance";}else{echo "The qualified supervisor certifies that the surveillance has been carried out correctly.";}?>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr <?php if($rowQuestionnaire['Etat']=="Clôturé" &&  $rowQuestionnaire['EnFormation']==1){echo "style='display:;'";}else{echo "style='display:none;'";} ?>>
						<td colspan="3" style="" class="Libelle">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le surveillant qualifié confirme-t-il que le surveillant en formation peut réaliser les prochaines surveillances en toute autonomie ?";}else{echo "Does the qualified supervisor confirm that the trainee supervisor can carry out the next watches independently ?";}?>
							&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="radio"  <?php if($rowQuestionnaire['Autonome']==1){echo "checked";}?> class="attestation" name="autonomieSurveillant" value="1" ><?php if($_SESSION['Langue']=="FR"){echo "Oui";}else{echo "Yes";}?>
							&nbsp;&nbsp;&nbsp;<input style="height: 20px; width: 20px;" type="radio"  <?php if($rowQuestionnaire['Autonome']==-1){echo "checked";}?> class="attestation" name="autonomieSurveillant" value="-1" ><?php if($_SESSION['Langue']=="FR"){echo "Non";}else{echo "No";}?>
						</td>
					</tr>
					<?php
						$req="SELECT Id FROM soda_theme WHERE Id=".$rowQuestionnaire['Id_Theme']." AND Id_Qualification IN (
							SELECT DISTINCT Id_Qualification_Parrainage 
							FROM new_competences_relation 
							WHERE (Evaluation='X'
							AND Date_Debut<='".date('Y-m-d')."'
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
							)
							AND Suppr=0
							AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
							AND Id_Personne=".$_SESSION['Id_Personne']."
						) ";
						$resultSurTheme= mysqli_query($bdd,$req);	
						$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
						if($rowQuestionnaire['Etat']=="Clôturé" && $rowQuestionnaire['Id_Surveillant']<>$_SESSION['Id_Personne'] && $nbSurvThemeQualifie>0)
						{
						?>
						<tr><td height="5"></td></tr>
						<tr>
							<td colspan="7" align="center">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input style="" class="Bouton" type="submit" name="btnAttestation" onClick="return VerifAttestation();" value="<?php if($_SESSION['Langue']=="FR"){echo "J'atteste";}else{echo "I hereby certify";}?>">
							</td>
						</tr>
						<?php
						}
					?>
				</table>
			</td>
		</tr>
		<?php 
	echo "<script>Change_Note('".$rowQuestionnaire['Id_Questionnaire']."','".$_SESSION['Langue']."');</script>";
	?>
	<input type="hidden" id="oldSurveille" name="oldSurveille" value="<?php echo $Id_Surveille; ?>">
</table>
</form>
</body>
</html>