<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.nom.value==''){alert('Vous n\'avez pas renseigné l\'intitulé de la question.');return false;}
		}

		function FermerEtRecharger()
		{
			opener.opener.location='Tableau_De_Bord.php?Menu=15';
			opener.location.reload();
			window.close();
		}
		function SelectionnerTout(Champ)
		{
			var elements = document.getElementsByClassName("check"+Champ);
			if (document.getElementById('selectAll'+Champ).checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
$dateDuJour = date("Y-m-d");
if($_POST)
{
	$requete="SELECT Id FROM soda_question WHERE Id_Questionnaire=".$_POST['Id_Questionnaire'];
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	$ordre=$nbResulta+1;
	
	$requete="
		INSERT INTO soda_question
			(
			Id_Questionnaire,
			Question,
			Question_EN,
			Reponse,
			Reponse_EN,
			Ponderation,
			Id_Creation,
			DateCreation,
			Ordre
			)
		VALUES
			(".
			$_POST['Id_Questionnaire'].",
			'".addslashes($_POST['nom'])."',
			'".addslashes($_POST['nom_EN'])."',
			'".addslashes($_POST['reponse'])."',
			'".addslashes($_POST['reponse_EN'])."',
			".$_POST['ponderation'].",
			".$_SESSION['Id_Personne'].",
			'".date('Y-m-d')."',
			".$ordre."
			)";
	$result=mysqli_query($bdd,$requete);
	$Id=mysqli_insert_id($bdd);
	
	$req="SELECT Id FROM moris_client WHERE Suppr=0 ";
	$resultClient=mysqli_query($bdd,$req);
	$nbClient=mysqli_num_rows($resultClient);
	if ($nbClient > 0)
	{
		while($row=mysqli_fetch_array($resultClient))
		{
			if(!isset($_POST['client'.$row['Id']])){
				$req="INSERT INTO soda_question_exceptionclient (Id_Question,Id_Client,DateCreation,Id_Creation)
				VALUES (".$Id.",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}
	
	$req="SELECT Id FROM moris_famille_r03 WHERE Suppr=0 ";
	$resultR03=mysqli_query($bdd,$req);
	$nbR03=mysqli_num_rows($resultR03);
	if ($nbR03 > 0)
	{
		while($row=mysqli_fetch_array($resultR03))
		{
			if(!isset($_POST['R03'.$row['Id']])){
				$req="INSERT INTO soda_question_exceptionr03 (Id_Question,Id_R03,DateCreation,Id_Creation)
				VALUES (".$Id.",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}
	
	$req="SELECT Id FROM new_competences_plateforme WHERE Id NOT IN (11,14) ";
	$resultUER=mysqli_query($bdd,$req);
	$nbUER=mysqli_num_rows($resultUER);
	if ($nbUER > 0)
	{
		while($row=mysqli_fetch_array($resultUER))
		{
			if(!isset($_POST['UER'.$row['Id']])){
				$req="INSERT INTO soda_question_exceptionuer (Id_Question,Id_Plateforme,DateCreation,Id_Creation)
				VALUES (".$Id.",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}
	
	$req="UPDATE soda_surveillance_question SET EtatQA=1, Id_QA=".$_SESSION['Id_Personne'].", Date_QA=".date('Y-m-d')." WHERE Id=".$_POST['Id']." ";
	$resultUpdt=mysqli_query($bdd,$req);
				
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$req = "SELECT soda_surveillance_question.Id,
			soda_surveillance_question.QuestionAdditionnelle,
			soda_surveillance_question.ReponseAdditionnelle,
			soda_surveillance.Id_Questionnaire
			FROM soda_surveillance_question 
			LEFT JOIN soda_surveillance
			ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
			WHERE soda_surveillance_question.Id=".$_GET['Id']."
			";
			
	$result=mysqli_query($bdd,$req);
	$LigneQuestion=mysqli_fetch_array($result);
?>
		<form id="formulaire" method="POST" action="Ajout_Question_QA.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="Id_Questionnaire" value="<?php echo $LigneQuestion['Id_Questionnaire'];?>">
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
			<tr><td height="10"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Question FR";}else{echo "Question FR";}?> : </td>
				<td>
					<textarea name="nom" id="nom" cols="160" rows="2" style="resize:none;"><?php echo stripslashes(htmlspecialchars($LigneQuestion['QuestionAdditionnelle'])); ?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Question EN";}else{echo "Question EN";}?> : </td>
				<td>
					<textarea name="nom_EN" id="nom_EN" cols="160" rows="2" style="resize:none;"><?php echo stripslashes(htmlspecialchars($LigneQuestion['QuestionAdditionnelle'])); ?></textarea>
				</td>
			</tr>
			<tr>
				<td height="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse FR";}else{echo "Answer FR";}?> : </td>
				<td>
					<textarea name="reponse" id="reponse" cols="160" rows="2" style="resize:none;"><?php echo stripslashes(htmlspecialchars($LigneQuestion['ReponseAdditionnelle'])); ?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse EN";}else{echo "Answer EN";}?> : </td>
				<td>
					<textarea name="reponse_EN" id="reponse_EN" cols="160" rows="2" style="resize:none;"><?php echo stripslashes(htmlspecialchars($LigneQuestion['ReponseAdditionnelle'])); ?></textarea>
				</td>
			</tr>
			<tr>
				<td height="10px">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">
					<?php
						if($_SESSION['Langue']=="FR"){echo "Pondération :";}else{echo "Weighting :";}
					?>
				</td>
				<td>
					<select name="ponderation" style="width=10;">
						<option value="0"><?php if($_SESSION["Langue"]=="FR"){echo "0 - Inactif";}else{echo "0 - Inactive ";}?></option>
						<option value="1" selected>1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
					</select>
				</td>
			</tr>
		</table>
		<table>
			<tr class="TitreColsUsers">
				<td>
					<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
						<tr>
							<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?>&nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="selectAllClient" id="selectAllClient" onclick="SelectionnerTout('Client')" checked /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							</td>
						</tr>
						<tr>
							<td>
								<div id='Div_Client' style='height:200px;width:200px;overflow:auto;'>
									<table>
								<?php
									$req="SELECT Id,Libelle
										FROM moris_client
										WHERE Suppr=0 
										ORDER BY Libelle;";
									$resultClient=mysqli_query($bdd,$req);
									$nbClient=mysqli_num_rows($resultClient);
									
									if ($nbClient > 0)
									{
										while($row=mysqli_fetch_array($resultClient))
										{
											$selected="checked";
											echo "<tr><td><input class='checkClient' type='checkbox' ".$selected." value='".$row['Id']."' name='client".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
										}
									}
								?>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
						<tr>
							<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Famille R03";}else{echo "Family R03";} ?>&nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="selectAllR03" id="selectAllR03" onclick="SelectionnerTout('R03')" checked /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							</td>
						</tr>
						<tr>
							<td>
								<div id='Div_R03' style='height:200px;width:200px;overflow:auto;'>
									<table>
								<?php
									$req="SELECT Id,Num
										FROM moris_famille_r03
										WHERE Suppr=0 
										ORDER BY Num;";
								
									$resultR03=mysqli_query($bdd,$req);
									$nbR03=mysqli_num_rows($resultR03);
									
									if ($nbR03 > 0)
									{
										while($row=mysqli_fetch_array($resultR03))
										{
											$selected="checked";
											echo "<tr><td><input class='checkR03' type='checkbox' ".$selected." value='".$row['Id']."' name='R03".$row['Id']."'>".stripslashes($row['Num'])."</td></tr>";
										}
									}
								?>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
				<td>
					<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
						<tr>
							<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?>&nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="selectAllUER" id="selectAllUER" onclick="SelectionnerTout('UER')" checked /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							</td>
						</tr>
						<tr>
							<td>
								<div id='Div_UER' style='height:200px;width:200px;overflow:auto;'>
									<table>
								<?php
									$req="SELECT Id,Libelle
										FROM new_competences_plateforme
										WHERE Id NOT IN (11,14)
										ORDER BY Libelle;";
								
									$resultUER=mysqli_query($bdd,$req);
									$nbUER=mysqli_num_rows($resultUER);
									
									if ($nbUER > 0)
									{
										while($row=mysqli_fetch_array($resultUER))
										{
											$selected="checked";
											echo "<tr><td><input class='checkUER' type='checkbox' ".$selected." value='".$row['Id']."' name='UER".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
										}
									}
								?>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
							if($_SESSION['Langue']=="FR"){echo "value='Ajouter'";}
							else{echo "value='Add'";}
					?>
					>
				</td>
			</tr>
		</table><br>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>