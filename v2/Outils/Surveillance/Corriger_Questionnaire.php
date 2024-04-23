<html>
<head>
	<title>Surveillances - Surveillance</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script src="Corriger_Questionnaire2.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function VerifChamps(){
			if(formulaire.Id_Prestation.value=='0' || formulaire.Id_Prestation.value==''){alert('Vous n\'avez pas renseigné la prestation.');return false;}
			if(formulaire.Id_Surveillant.value=='0' || formulaire.Id_Surveillant.value==''){alert('Vous n\'avez pas renseigné le surveillant.');return false;}
			if(formulaire.Id_Surveille.value=='0' || formulaire.Id_Surveille.value==''){alert('Vous n\'avez pas renseigné le surveillé.');return false;}
			var inputSignatures = document.getElementsByClassName('signatures');
			nSignatures=0;
			for(var i=0; inputSignatures[i]; i++){
				  if(inputSignatures[i].checked){
					   nSignatures++;
				  }
			}
			if(nSignatures!=2){
				if(document.getElementById('Langue').value=="FR"){
					Confirm=window.confirm('Surveillance non signée par le surveillant et/ou le surveillé. Voulez-vous quitter ? Si OK, les données seront enregistrées mais le questionnaire restera en statut planifié');
				}else{
					Confirm=window.confirm('Surveillance not signed by the supervisor and/or by the supervised person. Do you want to quit ? If OK, data will be saved but the questionnaire will stay on planned status');
				}
				if(Confirm==false){return false;}
			}
			
			var inputRadio = document.getElementsByClassName('radioNote');
			for(var k=0, l=inputRadio.length; k<l; k++){
				if(inputRadio[k].checked){
					if(inputRadio[k].value=="NC"){
						valeur=inputRadio[k].name.substr(6);
						if(document.getElementById('observation_'+valeur).value=='' || document.getElementById('action_'+valeur).value==''){
							alert('Veuillez renseigner les descriptions et les actions des réponses NC');return false;
						}
					}
				}
			}
			
			var inputActions = document.getElementsByClassName('actions');
			nbActionsTrackers=0;
			for(var i=0; inputActions[i]; i++){
				  if(inputActions[i].value=="Action immédiate + Action Tracker" || inputActions[i].value=="Immediate action + Action Tracker"){
					   nbActionsTrackers++;
				  }
			}
			
			if(document.getElementById('score').value<80){
				if(nbActionsTrackers==0){
					if(document.getElementById('Langue').value=="FR"){
						alert('Veuillez renseigner au moins une action "Action immédiate + Action Tracker"');return false;
					}
					else{
						alert('Please fill in at least one action "Immediate action + Action Tracker"');return false;
					}
				}
			}
			
			if(nbActionsTrackers>0 && (document.getElementById('numActionTraker').value=="" || document.getElementById('numActionTraker').value=="0")){
				if(document.getElementById('Langue').value=="FR"){
					Confirm=window.confirm('N° fiche Action Tracker requis et non renseigné. Voulez-vous quitter ? Si OK, les données seront enregistrées mais le questionnaire restera en statut planifié.');
				}else{
					Confirm=window.confirm('Action Tracker form # requested and not filled-in. Do you want to quit ? If OK, data will be saved but the questionnaire will stay on planned status.');
				}
				if(Confirm==false){return false;}
			}
			
			return true;
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

$AccesQualite=false;
if(DroitsFormationPlateformes(array(6,7,12,15,16,18,20,22,26,30),array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))
	|| DroitsFormationPrestations(array(6,7,12,15,16,18,20,22,26,30),array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,8)))
	{$AccesQualite=true;}

$dateDuJour = date("Y/m/d");
if($_POST)
{
	if($AccesQualite || $_POST['leSurveillant']==$_SESSION['Id_Personne'])
	{
		if(isset($_POST['btnEnregistrer']) || isset($_POST['btnCloture']))
		{
			$numActionTraker=0;
			if($_POST["numActionTraker"]<>""){$numActionTraker=$_POST["numActionTraker"];}
			
			$signatureSurveillant=0;
			$signatureSurveille=0;
			if(isset($_POST['signatureSurveillant'])){$signatureSurveillant=1;} 
			if(isset($_POST['signatureSurveille'])){$signatureSurveille=1;} 
			
			//Mise à jour de la surveillance
			$reqUpdateSurv = "UPDATE new_surveillances_surveillance SET ";
			$reqUpdateSurv .= "ID_Prestation=".$_POST["Id_Prestation"].", ";
			$reqUpdateSurv .= "ID_Surveillant=".$_POST["Id_Surveillant"].", ";
			$reqUpdateSurv .= "ID_Surveille=".$_POST["Id_Surveille"].", ";
			$reqUpdateSurv .= "SignatureSurveillant=".$signatureSurveillant.", ";
			$reqUpdateSurv .= "SignatureSurveille=".$signatureSurveille.", ";
			$reqUpdateSurv .= "NumActionTracker=".$numActionTraker." ";
			if($_POST['DatePremierePlannif']<>TrsfDate($_POST['DatePlanif']) || $_POST['DatePremiereRePlannif']<>""){
					$reqUpdateSurv .= ",DateReplanif='".TrsfDate($_POST['DatePlanif'])."' ";
					if($_POST['Etat'] == "Planifié"){
						$reqUpdateSurv .= ",Etat='Replanifié' ";
					}
			}
			$reqUpdateSurv .= "WHERE ID=".$_POST['Id'];
			$result=mysqli_query($bdd,$reqUpdateSurv);
			
			if($_POST['Etat2'] == "Planifié" || $_POST['Etat2'] == "Replanifié")
			{
				$reqQuestion = "
					SELECT
						new_surveillances_question.ID,
						new_surveillances_question.Modifiable
					FROM
						new_surveillances_question
					LEFT JOIN new_surveillances_questionnaire
						ON new_surveillances_questionnaire.ID = new_surveillances_question.ID_Questionnaire
					WHERE
						new_surveillances_questionnaire.ID =".$_POST['ID_Questionnaire']."
						AND new_surveillances_question.Supprime =0
					ORDER BY
						new_surveillances_question.Numero ;";
				$resultQuest=mysqli_query($bdd,$reqQuestion);
				$nbQuest=mysqli_num_rows($resultQuest);

				$requeteInsert="
					INSERT INTO new_surveillances_surveillance_question
						(
						ID_Question,
						ID_Surveillance,
						QuestionModifiable,
						Etat,
						Commentaire,
						Action
						)
					VALUES ";
				
				$nbActionsTrackers=0;
				
				if($nbQuest>0)
				{
					while($rowQuest=mysqli_fetch_array($resultQuest))
					{
						$requeteInsert.="(";
						$requeteInsert.=$rowQuest['ID'].",";
						$requeteInsert.=$_POST['Id'].",";
						if($rowQuest['Modifiable'] == "0"){$requeteInsert.="'',";}
						else{$requeteInsert.="'".addslashes($_POST['question_'.$rowQuest['ID']])."',";}
						if ($_POST['radio_'.$rowQuest['ID']] == 'NC')
						{
							$requeteInsert.="'NC','";
							$requeteInsert.=addslashes($_POST['observation_'.$rowQuest['ID']])."','";
							$requeteInsert.=addslashes($_POST['action_'.$rowQuest['ID']])."'),";
							
							if($_POST['action_'.$rowQuest['ID']]=="Action immédiate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
						}
						elseif ($_POST['radio_'.$rowQuest['ID']] == 'C')
						{
							$requeteInsert.="'C','";
							$requeteInsert.=addslashes($_POST['observation_'.$rowQuest['ID']])."','";
							$requeteInsert.=addslashes($_POST['action_'.$rowQuest['ID']])."'),";
							if($_POST['action_'.$rowQuest['ID']]=="Action immédiate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
						}
						elseif ($_POST['radio_'.$rowQuest['ID']] == 'NA')
						{
							$requeteInsert.="'NA','";
							$requeteInsert.=addslashes($_POST['observation_'.$rowQuest['ID']])."','";
							$requeteInsert.="'),";
						}
					}
					$requeteInsert = substr($requeteInsert,0,-1);
				}
				$requeteUpdate="UPDATE new_surveillances_surveillance ";
				$nbAction=1;
				if($nbActionsTrackers>0 && $numActionTraker==0){$nbAction=0;}
				if (isset($_POST['signatureSurveillant']) && isset($_POST['signatureSurveille']) && $nbAction==1)
				{
					$requeteUpdate.="SET Etat='Clôturé', ";
					$requeteUpdate.="DateCloture='".$dateDuJour."' ";
				}
				else
				{
					$requeteUpdate.="SET Etat='Réalisé', ";
					$requeteUpdate.="DateCloture=0 ";
				}
				$requeteUpdate.="WHERE ID=".$_POST['Id'];
				
				$result=mysqli_query($bdd,$requeteInsert);
				$result=mysqli_query($bdd,$requeteUpdate);
			}
			else
			{
				$reqQuest = "
					SELECT
						new_surveillances_surveillance_question.ID,
						new_surveillances_question.Numero,
						new_surveillances_question.Question,
						new_surveillances_question.Question_EN,
						new_surveillances_question.Modifiable,
						new_surveillances_surveillance_question.QuestionModifiable,
						new_surveillances_surveillance_question.Etat,
						new_surveillances_surveillance_question.Commentaire,
						new_surveillances_surveillance_question.Action,
						new_surveillances_surveillance_question.Cloturee
					FROM
						new_surveillances_question
					LEFT JOIN new_surveillances_surveillance_question
						ON new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question
					WHERE
						new_surveillances_surveillance_question.ID_Surveillance =".$_POST['Id']."
					ORDER BY
						new_surveillances_question.Numero ;";
				
				
				$resultQuest=mysqli_query($bdd,$reqQuest);
				$nbQuest=mysqli_num_rows($resultQuest);
				$nbActionsTrackers=0;
				if ($nbQuest>0)
				{
					while($rowQuest=mysqli_fetch_array($resultQuest))
					{
						$reqUpdate = "UPDATE new_surveillances_surveillance_question SET ";
						if($rowQuest['Modifiable'] == "0"){$reqUpdate.="QuestionModifiable='', ";}
						else{$reqUpdate.="QuestionModifiable='".addslashes($_POST['question_'.$rowQuest['ID']])."', ";}
						if ($_POST['radio_'.$rowQuest['ID']] == 'NC')
						{
							$reqUpdate .= "Etat='NC', ";
							$reqUpdate .= "Commentaire='".addslashes($_POST['observation_'.$rowQuest['ID']])."', ";
							$reqUpdate .= "Action='".addslashes($_POST['action_'.$rowQuest['ID']])."' ";
							if($_POST['action_'.$rowQuest['ID']]=="Action immédiate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
						}
						elseif ($_POST['radio_'.$rowQuest['ID']] == 'C')
						{
							$reqUpdate .= "Etat='C', ";
							$reqUpdate .= "Commentaire='".addslashes($_POST['observation_'.$rowQuest['ID']])."', ";
							$reqUpdate .= "Action='".addslashes($_POST['action_'.$rowQuest['ID']])."' ";
							if($_POST['action_'.$rowQuest['ID']]=="Action immédiate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
						}
						elseif ($_POST['radio_'.$rowQuest['ID']] == 'NA')
						{
							$reqUpdate .= "Etat='NA', ";
							$reqUpdate .= "Commentaire='".addslashes($_POST['observation_'.$rowQuest['ID']])."', ";
							$reqUpdate .= "Action='' ";
						}
						$reqUpdate .= "WHERE ID=".$rowQuest['ID'].";";
						$result=mysqli_query($bdd,$reqUpdate);
					}
					$requeteUpdate2="UPDATE new_surveillances_surveillance ";
					$nbAction=1;
					if($nbActionsTrackers>0 && $numActionTraker==0){$nbAction=0;}
					if (isset($_POST['signatureSurveillant']) && isset($_POST['signatureSurveille']) && $nbAction==1)
					{
						$requeteUpdate2.="SET Etat='Clôturé', ";
						$requeteUpdate2.="DateCloture='".$dateDuJour."' ";
					}
					else
					{
						$requeteUpdate2.="SET Etat='Réalisé', ";
						$requeteUpdate2.="DateCloture=0 ";
					}
					$requeteUpdate2.="WHERE ID=".$_POST['Id'];
					$result=mysqli_query($bdd,$requeteUpdate2);
				}
			}
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}

$requeteSurveillance = "
    SELECT
        new_surveillances_surveillance.ID,
        (SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
        new_surveillances_questionnaire.Nom AS Questionnaire,
        new_surveillances_questionnaire.ID AS ID_Questionnaire,
        new_competences_prestation.Id_Plateforme AS Id_Plateforme,
        (SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme,
        new_competences_prestation.Libelle AS Prestation,
        new_competences_prestation.Id AS Id_Prestation,
        new_surveillances_surveillance.ID_Surveille AS ID_Surveille,
        new_surveillances_surveillance.ID_Surveillant AS ID_Surveillant,
        (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveille) AS Surveille,
        (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveillant) AS Surveillant,
        new_surveillances_surveillance.DatePlanif AS DatePlanif,
        new_surveillances_surveillance.DateReplanif AS DateReplanif,
        IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance,
        IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') AS Etat,
		new_surveillances_surveillance.Etat AS Etat2,
		new_surveillances_surveillance.NumActionTracker,SignatureSurveillant,SignatureSurveille
    FROM
        (
            (
            new_surveillances_surveillance
            LEFT JOIN new_competences_prestation
                ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id
            )
        LEFT JOIN new_surveillances_questionnaire
            ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id
        ) ";
if($_GET){$requeteSurveillance.=" WHERE new_surveillances_surveillance.ID=".$_GET['Id'];}
else{$requeteSurveillance.=" WHERE new_surveillances_surveillance.ID=".$_POST['Id'];}
$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

//Questionnaire
if ($LigneSurveillance['Etat2'] == "Planifié" || $LigneSurveillance['Etat2'] == "Replanifié")
{
	$reqQuestion = "
        SELECT
            new_surveillances_question.ID,
            new_surveillances_question.Numero,
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
            new_surveillances_questionnaire.ID =".$LigneSurveillance['ID_Questionnaire']."
            AND new_surveillances_question.Supprime =0
        ORDER BY
            new_surveillances_question.Numero ;";
	$resultQuestion=mysqli_query($bdd,$reqQuestion);
	$nbQuestion=mysqli_num_rows($resultQuestion);
}
else
{
	$reqQuestionSurveillance = "
        SELECT
            new_surveillances_surveillance_question.ID,
            new_surveillances_question.Numero,
            new_surveillances_question.Question,
            new_surveillances_question.Question_EN,
			new_surveillances_question.Reponse,
			new_surveillances_question.Reponse_EN,
            new_surveillances_question.Modifiable,
            new_surveillances_surveillance_question.QuestionModifiable,
            new_surveillances_surveillance_question.Etat,
            new_surveillances_surveillance_question.Commentaire,
            new_surveillances_surveillance_question.Action,
            new_surveillances_surveillance_question.Cloturee
        FROM
            new_surveillances_question
        LEFT JOIN new_surveillances_surveillance_question
            ON new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question
        WHERE
            new_surveillances_surveillance_question.ID_Surveillance =".$LigneSurveillance['ID']."
        ORDER BY
            new_surveillances_question.Numero ;";
	$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
	$nbQuestion=mysqli_num_rows($resultQuestion);
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Corriger_Questionnaire.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Id" value="<?php echo $LigneSurveillance['ID']?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<input type="hidden" name="Etat" value="<?php echo $LigneSurveillance['Etat']?>">
	<input type="hidden" name="Etat2" value="<?php echo $LigneSurveillance['Etat2']?>">
	<input type="hidden" name="ID_Questionnaire" value="<?php echo $LigneSurveillance['ID_Questionnaire']?>">
	<input type="hidden" name="DatePremierePlannif" value="<?php echo $LigneSurveillance['DatePlanif']?>">
	<input type="hidden" name="DatePremiereRePlannif" value="<?php echo $LigneSurveillance['DateReplanif']?>">
	<input type="hidden" name="leSurveillant" value="<?php echo $LigneSurveillance['ID_Surveillant']?>">
	<table style="width:100%; align:center;">
		<tr><td valign="top">
			<table style="width:95%; align:center;" class="TableCompetences">
				<tr>
					<td width="20%">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Entité :";}
							else{echo "Entity :";}
						?>
					<select id="Id_Plateforme" name="Id_Plateforme" onchange="Recharge_Liste_Prestation_Personne();">
					<?php
					$reqPlat="SELECT * FROM new_competences_plateforme 
					WHERE Id<>11 AND Id<>14 ";
					if($AccesQualite){
						
					}
					else{
						 $reqPlat.=" AND Id=".$LigneSurveillance['Id_Plateforme']." ";
					}
					$reqPlat.=" ORDER BY Libelle ASC";
					$result2=mysqli_query($bdd,$reqPlat);
					while($row2=mysqli_fetch_array($result2))
					{
						echo "<option value='".$row2['Id']."'";
						if($LigneSurveillance['Id_Plateforme']==$row2['Id']){echo " selected";}
						echo ">".$row2['Libelle']."</option>\n";
					}
					?>
					</select>
					
					</td>
					<td width="7%">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Prestation :";}
							else{echo "Activity :";}
						?>
					</td>
					<td width="25%">
						<div id="Prestation">
							<select size="1" name="Id_Prestation" style="width:300">
								<?php
									$requete_Prestation="SELECT Id, Id_Plateforme, Libelle 
									FROM new_competences_prestation 
									WHERE Id_Plateforme=".$LigneSurveillance['Id_Plateforme']." ";
									if($AccesQualite){
										
									}
									else{
										 $requete_Prestation.=" AND Id=".$LigneSurveillance['Id_Prestation']." ";
									}
									$requete_Prestation.=" ORDER BY Libelle ASC";
									$result_Prestation= mysqli_query($bdd,$requete_Prestation);
									$i=0;
									while ($rowPrestation=mysqli_fetch_array($result_Prestation))
									{
										echo "<option value='".$rowPrestation['Id']."'";
										if($LigneSurveillance['Id_Prestation']==$rowPrestation['Id']){echo " selected";}
										echo ">".$rowPrestation['Libelle']."</option>\n";
									}
								?>
							</select>
						</div>
					</td>
					<?php
					$requete_Prestation="SELECT Id, Id_Plateforme, Libelle FROM new_competences_prestation ORDER BY Libelle ASC";
					$result_Prestation= mysqli_query($bdd,$requete_Prestation) or die ("Select impossible");
					$i=0;
					while ($row_Prestation=mysqli_fetch_array($result_Prestation))
					{
						 echo "<script>Liste_Plateforme_Prestation[".$i."] = new Array(".$row_Prestation[0].",".$row_Prestation[1].",'".addslashes($row_Prestation[2])."');</script>\n";
						 $i+=1;
					}
					?>
					<td width="10%">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Surveillé :";}
							else{echo "Supervised :";}
						?>
					</td>
					<td width="25%">
					<div id="Surveille">
						<select size="1" name="Id_Surveille">
							<?php
							$requetePersonne="
                                SELECT DISTINCT
                                    new_rh_etatcivil.Id,
                                    CONCAT(Nom, ' ', Prenom) as NomPrenom
                                FROM
                                    new_rh_etatcivil
                                INNER JOIN new_competences_personne_plateforme
                                    ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
                                WHERE
                                    (new_competences_personne_plateforme.Id_Plateforme=".$LigneSurveillance['Id_Plateforme']."
                                    OR new_rh_etatcivil.Id=".$LigneSurveillance['ID_Surveille'].") ";
								if($AccesQualite || $LigneSurveillance['ID_Surveillant']==$_SESSION['Id_Personne']){
									
								}
								else{
									 $requetePersonne.=" AND new_rh_etatcivil.Id=".$LigneSurveillance['ID_Surveille']." ";
								}
							   $requetePersonne.=" ORDER BY
                                    NomPrenom ASC";
							$result_Personne= mysqli_query($bdd,$requetePersonne);
							while ($row_Personne=mysqli_fetch_array($result_Personne))
							{
								echo "<option value='".$row_Personne['Id']."'";
								if($LigneSurveillance['ID_Surveille']==$row_Personne['Id']){echo " selected";}
								echo ">".$row_Personne['NomPrenom']."</option>\n";
							}
							?>
						</select>
					</div>
						<?php
						$requetePersonne="
                            SELECT
                                new_rh_etatcivil.Id,
                                new_competences_personne_plateforme.Id_Plateforme,
                                CONCAT(Nom, ' ', Prenom) as NomPrenom
                            FROM
                                new_rh_etatcivil
                            INNER JOIN new_competences_personne_plateforme
                                ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
                            ORDER BY
                                NomPrenom ASC";
						$result_Personne= mysqli_query($bdd,$requetePersonne) or die ("Select impossible");
						$i=0;
						while ($row_Personne=mysqli_fetch_array($result_Personne))
						{
							 echo "<script>Liste_Plateforme_Personne[".$i."] = new Array(".$row_Personne[0].",".$row_Personne[1].",'".addslashes($row_Personne[2])."');</script>\n";
							 $i+=1;
						}
						?>
					</td>
				</tr>
				<tr>
					<td width="20%">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Thématique : ";}
							else{echo "Theme : ";}
							echo $LigneSurveillance['Theme'];
						?>
					</td>
					<td width="7%">Questionnaire : </td>
					<td width="25%"><?php echo $LigneSurveillance['Questionnaire'];?></td>
					<td width="10%">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Surveillant :";}
							else{echo "Supervisor :";}
						?>
					</td>
					<td width="25%">
						<div id="Surveillant">
							<select size="1" name="Id_Surveillant">
								<?php
								$requetePersonne="
                                    SELECT
                                        new_rh_etatcivil.Id,
                                        new_competences_personne_plateforme.Id_Plateforme,
                                        CONCAT(Nom, ' ', Prenom) as NomPrenom
                                    FROM
                                        new_rh_etatcivil
                                    INNER JOIN new_competences_personne_plateforme
                                        ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
                                    WHERE
                                        (new_competences_personne_plateforme.Id_Plateforme=".$LigneSurveillance['Id_Plateforme']."
                                        OR new_rh_etatcivil.Id=".$LigneSurveillance['ID_Surveillant'].") ";
									if($AccesQualite){
										
									}
									else{
										 $requetePersonne.=" AND new_rh_etatcivil.Id=".$LigneSurveillance['ID_Surveillant']." ";
									}
                                   $requetePersonne.=" ORDER BY
                                        NomPrenom ASC";
								$result_Personne= mysqli_query($bdd,$requetePersonne);
								while ($row_Personne=mysqli_fetch_array($result_Personne))
								{
									echo "<option value='".$row_Personne['Id']."'";
									if($LigneSurveillance['ID_Surveillant']==$row_Personne['Id']){echo " selected";}
									echo ">".$row_Personne['NomPrenom']."</option>\n";
								}
								?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td width="20%">Date planif : 
						<?php echo AfficheDateJJ_MM_AAAA($LigneSurveillance['DatePlanif']); ?>
					</td>
					<td width="20%">Date surveillance : 
						<input id="DatePlanif" type="date" name="DatePlanif" size="10" value="<?php echo AfficheDateFR($LigneSurveillance['DateSurveillance']); ?>">
					</td>
					<td width="7%">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Etat :";}
							else{echo "Status :";}
							
						?>
					</td>
					<td width="25%"><?php echo $LigneSurveillance['Etat'];?></td>
				</tr>
			</table>
		</td></tr>
		<tr><td height="10"></td></tr>
		<tr><td valign="top">
			<table style="width:95%; align:center;" class="TableCompetences">
				<tr align="center">
					<td class="EnTeteTableauCompetences" width="5">N°</td>
					<td class="EnTeteTableauCompetences" width="250">Question</td>
					<td class="EnTeteTableauCompetences" width="5">C</td>
					<td class="EnTeteTableauCompetences" width="5">NC</td>
					<td class="EnTeteTableauCompetences" width="5">NA</td>
					<td class="EnTeteTableauCompetences" width="40">
						<?php
							if($_SESSION['Langue']=="FR"){echo "Description de la NC / Preuves";}
							else{echo "NC Description / Evidences";}
						?>
					</td>
					<td class="EnTeteTableauCompetences" width="40">Action</td>
				</tr>
				<?php
					$total = 0;
					$C = 0;
					if($nbQuestion > 0)
					{
						while($rowQuestion=mysqli_fetch_array($resultQuestion))
						{
							echo "<tr>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'>".$rowQuestion['Numero']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='470'>";
							if($rowQuestion['Modifiable'] == "0"){echo "<b>FR :</b> <b>Q</b> ".$rowQuestion['Question']." <span style='color:#0a7800'><b>R</b> ".$rowQuestion['Reponse']."</span><br><b>EN :</b> <b>Q</b> ".$rowQuestion['Question_EN']." <span style='color:#0a7800'><b>R</b> ".$rowQuestion['Reponse_EN']."</span> ";}
							else
							{
								$laQuestion = "";
								if($LigneSurveillance['Etat'] == "Planifié" || $LigneSurveillance['Etat'] == "Replanifié"){$laQuestion = "FR : ".$rowQuestion['Question']." #### EN : ".$rowQuestion['Question_EN']." ";}
								else{$laQuestion = $rowQuestion['QuestionModifiable'];}
							?>
								<input type="text" id="<?php echo "question_".$rowQuestion['ID'];?>" name="<?php echo "question_".$rowQuestion['ID'];?>" size="70" value="<?php echo $laQuestion;?>">
							<?php
							}
							echo "</td>";
							
							$checkC = "";
							$checkNC = "";
							$checkNA = "";
							$observation ="";
							$action = "";
							$cloture = "";
							$display = "style='display:none;'";
							if ($LigneSurveillance['Etat2'] <> "Planifié" && $LigneSurveillance['Etat2'] <> "Replanifié")
							{	
								if ($rowQuestion['Etat'] == "NC")
								{
									$checkNC = "checked";
									$display = "style='display:;'";
									$total++;
								}
								elseif ($rowQuestion['Etat'] == "C")
								{
									$checkC = "checked";
									$display = "style='display:;'";
									$total++;
									$C++;
								}
								elseif($rowQuestion['Etat'] == "NA"){$checkNA = "checked";}
								$observation = $rowQuestion['Commentaire'];
								$action = $rowQuestion['Action'];
								$cloture = $rowQuestion['Cloturee'];
							}
							else{
								if($rowQuestion['Modifiable'] == "0"){
									$checkC = "checked";
								}
								else{
									$checkNA = "checked";
								}
							}
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'><input onchange='Change_Note()' class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='C' ".$checkC."></td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'><input onchange='Change_Note()' class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='NC' ".$checkNC."></td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'><input onchange='Change_Note()' class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='NA' ".$checkNA."></td>";
							?>
							<td style="border-bottom:1px #d9d9d7 solid;" width="40"><input <?php echo $display; ?> type="text" id="<?php echo "observation_".$rowQuestion['ID'];?>" name="<?php echo "observation_".$rowQuestion['ID'];?>" size="50" value="<?php echo $observation;?>"></td>
							<td style="border-bottom:1px #d9d9d7 solid;" width="40">
								<select <?php echo $display; ?> class="actions" id="<?php echo "action_".$rowQuestion['ID'];?>" name="<?php echo "action_".$rowQuestion['ID'];?>" width="150px;">
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
							</td>
							<?php
							echo "</tr>";
						}
					}
				?>
				<tr><td height="10"></td></tr>
				<tr>
					<td colspan="7" align="left" class="Libelle">
						Note :
						<?php
							if ($total == 0 || ($LigneSurveillance['Etat2'] == "Planifié" || $LigneSurveillance['Etat'] == "Replanifié2")){$note = "100";}
							else{$note = round(($C/$total)*100,0);}
						?>
						<input readonly id="note" type="text" value="<?php echo $note."%" ?>" size="5"/>
						
						<input id="score" name="score" type="hidden" value="<?php echo $note ?>" size="5"/>
					</td>
				</tr>
				<tr>
					<td colspan="7" align="left" class="Libelle">
						<?php if($_SESSION['Langue']=="FR"){echo "N° fiche Action Tracker :";}else{echo "Action Tracker form #:";}?>
						<input onKeyUp="nombre(this)" name="numActionTraker" id="numActionTraker" type="text" value="<?php if($LigneSurveillance['NumActionTracker']<>0){echo $LigneSurveillance['NumActionTracker'];} ?>" size="15"/>
					</td>
				</tr>
				<tr>
					<td colspan="4" class="Libelle">
						<?php if($_SESSION['Langue']=="FR"){echo "Le Surveillant confirme la réalisation de la surveillance et les potentielles actions qui en découlent :";}else{echo "The Supervisor confirms the completion of the surveillance and the potential actions arising from it :";}?>
						<input type="checkbox" class="signatures" name="signatureSurveillant" value="signatureSurveillant" <?php if($LigneSurveillance['SignatureSurveillant']==1){echo "checked";} ?>>
					</td>
					<td colspan="3" class="Libelle">
						<?php if($_SESSION['Langue']=="FR"){echo "Le Surveillé accepte le constat réalisé lors de cette surveillance :";}else{echo "The Supervised person’s accepts the observation done during this surveillance :";}?>
						<input type="checkbox" class="signatures" name="signatureSurveille" value="signatureSurveille" <?php if($LigneSurveillance['SignatureSurveille']==1){echo "checked";} ?>>
					</td>
				</tr>
				<?php
					if(($AccesQualite || $LigneSurveillance['ID_Surveillant']==$_SESSION['Id_Personne']) && in_array($LigneSurveillance['Id_Plateforme'],array(6,7,12,15,16,18,20,22,26,30)))
					{
				?>
				<tr>
					<td colspan="7" align="center">
						<input class="Bouton" type="submit" name="btnEnregistrer" value="Enregistrer / Save">
					</td>
				</tr>
				<?php
					}
				?>
			</table>
		</td></tr>
	</table>
	</form>
<?php
	echo "<script>Change_Note();</script>";
?>
	
</body>
</html>