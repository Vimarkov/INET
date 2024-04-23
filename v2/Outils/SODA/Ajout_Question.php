<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function VerifChamps(Mode)
		{
			if(formulaire.Mode.value=='M'){
				if(formulaire.nom.value==''){alert('Vous n\'avez pas renseigné l\'intitulé de la question.');return false;}
			}
			return true;
		}

		function FermerEtRecharger()
		{
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
		function OuvrirExceptionPrestaion(Id){
			var w=window.open("Liste_ExceptionPrestation.php?Id="+Id,"Page","status=no,menubar=no,scrollbars=yes,width=700,height=500");
			w.focus();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
$dateDuJour = date("Y-m-d");

if($_POST)
{
	if($_POST['Mode']=="A")
	{
		for($i=1;$i<=10;$i++)
		{
			if($_POST['nom'.$i]<>"")
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
                        '".addslashes($_POST['nom'.$i])."',
                        '".addslashes($_POST['nom_EN'.$i])."',
                        '".addslashes($_POST['reponse'.$i])."',
                        '".addslashes($_POST['reponse_EN'.$i])."',
						".$_POST['ponderation'.$i].",
						".$_SESSION['Id_Personne'].",
                        '".date('Y-m-d')."',
						".$ordre."
                        )";
				$result=mysqli_query($bdd,$requete);
				$Id=mysqli_insert_id($bdd);
				
				//Ajout l'image
				if(!empty($_FILES['uploaded_file'.$i]))
				{
					if($_FILES['uploaded_file'.$i]['name'] <> ""){
						$nomfichier = transferer_fichier($_FILES['uploaded_file'.$i]['name'], $_FILES['uploaded_file'.$i]['tmp_name'], "ImageQCM/");
						$reqUpdateAnnexe="UPDATE soda_question SET ImageQuestion='".$nomfichier."' WHERE Id=".$Id;
						$resultUpdateAnnexe=mysqli_query($bdd,$reqUpdateAnnexe);
					}
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M")
	{
		$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
		$nbAccess=mysqli_num_rows($resAcc);

		$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
		$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

		$req="SELECT Id FROM soda_theme 
			WHERE Suppr=0 
			AND Id=(SELECT soda_questionnaire.Id_Theme FROM soda_question LEFT JOIN soda_questionnaire ON soda_question.Id_Questionnaire=soda_questionnaire.Id WHERE soda_question.Id=".$_POST['Id'].")
			AND (Id_Gestionnaire=".$_SESSION['Id_Personne']." OR Id_Backup1=".$_SESSION['Id_Personne']." OR Id_Backup2=".$_SESSION['Id_Personne']." OR Id_Backup3=".$_SESSION['Id_Personne'].") ";
		$resAcc=mysqli_query($bdd,$req);
		$nbGestionnaireDuTheme=mysqli_num_rows($resAcc);

		if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){
			$requete="
				UPDATE
					soda_question
				SET
					Question='".addslashes($_POST['nom'])."',
					Question_EN='".addslashes($_POST['nom_EN'])."',
					Reponse='".addslashes($_POST['reponse'])."',
					Reponse_EN='".addslashes($_POST['reponse_EN'])."',
					Ponderation=".$_POST['ponderation'].",
					Id_Creation=".$_SESSION['Id_Personne'].",
					DateCreation='".date('Y-m-d')."'
				WHERE
					Id=".$_POST['Id'];
			$result=mysqli_query($bdd,$requete);
		}
		//Ajout des exceptions
		$req="UPDATE soda_question_exceptionclient SET Suppr=1,Date_Suppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id_Question=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id FROM moris_client WHERE Suppr=0 ";
		$resultClient=mysqli_query($bdd,$req);
		$nbClient=mysqli_num_rows($resultClient);
		if ($nbClient > 0)
		{
			while($row=mysqli_fetch_array($resultClient))
			{
				if(!isset($_POST['client'.$row['Id']])){
					$req="INSERT INTO soda_question_exceptionclient (Id_Question,Id_Client,DateCreation,Id_Creation)
					VALUES (".$_POST['Id'].",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		$req="UPDATE soda_question_exceptionr03 SET Suppr=1,Date_Suppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id_Question=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id FROM moris_famille_r03 WHERE Suppr=0 ";
		$resultR03=mysqli_query($bdd,$req);
		$nbR03=mysqli_num_rows($resultR03);
		if ($nbR03 > 0)
		{
			while($row=mysqli_fetch_array($resultR03))
			{
				if(!isset($_POST['R03'.$row['Id']])){
					$req="INSERT INTO soda_question_exceptionr03 (Id_Question,Id_R03,DateCreation,Id_Creation)
					VALUES (".$_POST['Id'].",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		$req="UPDATE soda_question_exceptionuer SET Suppr=1,Date_Suppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id_Question=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id FROM new_competences_plateforme WHERE Id NOT IN (11,14) ";
		$resultUER=mysqli_query($bdd,$req);
		$nbUER=mysqli_num_rows($resultUER);
		if ($nbUER > 0)
		{
			while($row=mysqli_fetch_array($resultUER))
			{
				if(!isset($_POST['UER'.$row['Id']])){
					$req="INSERT INTO soda_question_exceptionuer (Id_Question,Id_Plateforme,DateCreation,Id_Creation)
					VALUES (".$_POST['Id'].",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		$req="UPDATE soda_question_exceptionprestation SET Suppr=1,Date_Suppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id_Question=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id FROM new_competences_prestation WHERE Id_Plateforme NOT IN (11,14) ";
		$resultPresta=mysqli_query($bdd,$req);
		$nbPresta=mysqli_num_rows($resultPresta);
		if ($nbPresta > 0)
		{
			while($row=mysqli_fetch_array($resultPresta))
			{
				if(!isset($_POST['Prestation'.$row['Id']])){
					$req="INSERT INTO soda_question_exceptionprestation (Id_Question,Id_Prestation,DateCreation,Id_Creation)
					VALUES (".$_POST['Id'].",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		//Image pour la question 
		if(isset($_POST['SupprimerImage'])){
			//Vérifier si document existe déjà 
			$reqAnnexe="SELECT ImageQuestion FROM soda_question WHERE ImageQuestion<>'' AND Id=".$_POST['Id'];
			$resultAnnexe=mysqli_query($bdd,$reqAnnexe);
			$nbAnnexe=mysqli_num_rows($resultAnnexe);
			if($nbAnnexe>0){
				$rowAnnexe=mysqli_fetch_array($resultAnnexe);
				if(file_exists ("ImageQCM/".$rowAnnexe['ImageQuestion'])){
					//Supprimer le document
					unlink("ImageQCM/".$rowAnnexe['ImageQuestion']);	
				}
				$reqUpdateAttestation="UPDATE soda_question SET ImageQuestion='' WHERE Id=".$_POST['Id'];
				$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
			}
		}
		
		//Ajout l'image
		if(!empty($_FILES['uploaded_file']))
		{
			if($_FILES['uploaded_file']['name'] <> ""){
				$nomfichier = transferer_fichier($_FILES['uploaded_file']['name'], $_FILES['uploaded_file']['tmp_name'], "ImageQCM/");
				$reqUpdateAnnexe="UPDATE soda_question SET ImageQuestion='".$nomfichier."' WHERE Id=".$_POST['Id'];
				$resultUpdateAnnexe=mysqli_query($bdd,$reqUpdateAnnexe);
			}
		}
		
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="D")
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

		//Ajout l'image
		if(!empty($_FILES['uploaded_file']))
		{
			if($_FILES['uploaded_file']['name'] <> ""){
				$nomfichier = transferer_fichier($_FILES['uploaded_file']['name'], $_FILES['uploaded_file']['tmp_name'], "ImageQCM/");
				$reqUpdateAnnexe="UPDATE soda_question SET ImageQuestion='".$nomfichier."' WHERE Id=".$Id;
				$resultUpdateAnnexe=mysqli_query($bdd,$reqUpdateAnnexe);
			}
		}
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M" || $_GET['Mode']=="D")
	{
		if($_GET['Id']!='0')
		{
			$resultQuestion=mysqli_query($bdd,"SELECT Id,Question,Question_EN,Reponse,Reponse_EN,Ponderation,ImageQuestion FROM soda_question WHERE Id=".$_GET['Id']);
			$LigneQuestion=mysqli_fetch_array($resultQuestion);
		}
?>
		<form id="formulaire" method="POST" enctype="multipart/form-data" action="Ajout_Question.php" onSubmit="return VerifChamps(<?php echo $_GET['Mode']; ?>);">
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo $LigneQuestion['Id'];}?>">
		<input type="hidden" name="Id_Questionnaire" value="<?php echo $_SESSION['FiltreSODA_Questionnaire']; ?>">
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
		<?php
			$req="SELECT Id,Libelle FROM soda_questionnaire WHERE Id=".$_GET['Id']." ";
			$resultQ=mysqli_query($bdd,$req);
			$nbQ=mysqli_num_rows($resultQ);
			if($nbQ>0){
				$rowQ=mysqli_fetch_array($resultQ);
		?>
		<tr class="TitreColsUsers" bgcolor="#e0e0e0">
			<td class="Libelle">
				<?php
					if($_SESSION['Langue']=="FR"){echo "Questionnaire :";}
					else{echo "Questionnaire :";}
				?>
			</td>
			<td class="Libelle" colspan="2">
				<?php echo $rowQ["Libelle"];?>
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		<?php
			}
			if($_GET['Mode']=="A")
		    {
				$Couleur="#EEEEEE";
				for($i=1;$i<=10;$i++)
				{
					if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
					else{$Couleur="#EEEEEE";}
		?>
			<tr class="TitreColsUsers" bgcolor="<?php echo $Couleur;?>">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Question FR";}else{echo "Question FR";}?> : </td>
				<td colspan="2">
					<textarea name="nom<?php echo $i;?>" id="nom<?php echo $i;?>" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Question']));} ?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="<?php echo $Couleur;?>">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Question EN";}else{echo "Question EN";}?> : </td>
				<td colspan="2">
					<textarea name="nom_EN<?php echo $i;?>" id="nom_EN<?php echo $i;?>" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Question_EN']));} ?></textarea>
				</td>
			</tr>
			<tr bgcolor="<?php echo $Couleur;?>">
				<td height="10px" colspan="3">
				
				</td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="<?php echo $Couleur;?>">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse FR";}else{echo "Answer FR";}?> : </td>
				<td colspan="2">
					<textarea name="reponse<?php echo $i;?>" id="reponse<?php echo $i;?>" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse']));} ?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="<?php echo $Couleur;?>">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse EN";}else{echo "Answer EN";}?> : </td>
				<td colspan="2">
					<textarea name="reponse_EN<?php echo $i;?>" id="reponse_EN<?php echo $i;?>" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse_EN']));} ?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="<?php echo $Couleur;?>">
				<td class="Libelle" >
					<?php
						if($_SESSION['Langue']=="FR"){echo "Pondération :";}else{echo "Weighting :";}
					?>
				</td>
				<td colspan="2">
					<select name="ponderation<?php echo $i;?>" style="width=10;">
						<option value="0" selected><?php if($_SESSION["Langue"]=="FR"){echo "0 - Inactif";}else{echo "0 - Inactive ";}?></option>
						<option value="1" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==1){echo "selected";}} ?>>1</option>
						<option value="2" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==2){echo "selected";}} ?>>2</option>
						<option value="3" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==3){echo "selected";}} ?>>3</option>
						<option value="4" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==4){echo "selected";}} ?>>4</option>
					</select>
				</td>
			</tr>
			<tr bgcolor="<?php echo $Couleur;?>">
				<td height="10px" colspan="3">
				
				</td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="<?php echo $Couleur;?>">
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Image";}else{echo "Image";}?> : </td>
				<td>
					<input type="file" name="uploaded_file<?php echo $i;?>" />
				</td>
			</tr>
			<tr bgcolor="<?php echo $Couleur;?>">
				<td height="15px" colspan="3">
				
				</td>
			</tr>
			<?php
				}
			}
			else
			{
			?>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Question FR";}else{echo "Question FR";}?> : </td>
				<td>
					<textarea name="nom" id="nom" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Question']));} ?></textarea>
				</td>
				<td rowspan="6">
					<?php 
						if($_GET['Mode']=="M"){
							if($LigneQuestion['ImageQuestion']<>""){
								echo "<img src='ImageQCM/".$LigneQuestion['ImageQuestion']."' width='300px' style='border:0;' title='Image'>";
							}
						}
					?>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Question EN";}else{echo "Question EN";}?> : </td>
				<td>
					<textarea name="nom_EN" id="nom_EN" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Question_EN']));} ?></textarea>
				</td>
			</tr>
			<tr>
				<td height="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse FR";}else{echo "Answer FR";}?> : </td>
				<td>
					<textarea name="reponse" id="reponse" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse']));} ?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse EN";}else{echo "Answer EN";}?> : </td>
				<td>
					<textarea name="reponse_EN" id="reponse_EN" cols="130" rows="2" style="resize:none;"><?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse_EN']));} ?></textarea>
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
						<option value="0" selected><?php if($_SESSION["Langue"]=="FR"){echo "0 - Inactif";}else{echo "0 - Inactive ";}?></option>
						<option value="1" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==1){echo "selected";}} ?>>1</option>
						<option value="2" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==2){echo "selected";}} ?>>2</option>
						<option value="3" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==3){echo "selected";}} ?>>3</option>
						<option value="4" <?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){if($LigneQuestion['Ponderation']==4){echo "selected";}} ?>>4</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Image";}else{echo "Image";}?> : </td>
				<td>
					<input type="file" name="uploaded_file" />
					<?php 
						if($LigneQuestion['ImageQuestion']<>""){
					?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="SupprimerImage" style='cursor:pointer;' value="" /><?php if($LangueAffichage=="FR"){echo "Supprimer image";}else{echo "Delete image";}?>
					<?php
						}
					?>
				</td>
			</tr>
			<?php
				}
			?>
		</table>
		<table width="100%">
			<tr class="TitreColsUsers">
				<td  valign="top">
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
									$req="SELECT Id,Libelle,
										IF(".$_GET['Id']."=0,0,(SELECT COUNT(Id) FROM soda_question_exceptionclient WHERE Suppr=0 AND Id_Question=".$_GET['Id']." AND Id_Client=moris_client.Id)) AS Exception
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
											if($row['Exception']>0){$selected="";}
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
				<td  valign="top">
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
									$req="SELECT Id,Num,
										IF(".$_GET['Id']."=0,0,(SELECT COUNT(Id) FROM soda_question_exceptionr03 WHERE Suppr=0 AND Id_Question=".$_GET['Id']." AND Id_R03=moris_famille_r03.Id)) AS Exception
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
											if($row['Exception']>0){$selected="";}
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
				<td  valign="top">
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
									$req="SELECT Id,Libelle,
										IF(".$_GET['Id']."=0,0,(SELECT COUNT(Id) FROM soda_question_exceptionuer WHERE Suppr=0 AND Id_Question=".$_GET['Id']." AND Id_Plateforme=new_competences_plateforme.Id)) AS Exception
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
											if($row['Exception']>0){$selected="";}
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
				<td valign="top">
					<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
						<tr>
							<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?>&nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="selectAllPrestation" id="selectAllPrestation" onclick="SelectionnerTout('Prestation')" checked /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							</td>
						</tr>
						<tr>
							<td>
								<div id='Div_UER' style='height:200px;width:250px;overflow:auto;'>
									<table>
								<?php
									$req="SELECT Id,Libelle,
										IF(".$_GET['Id']."=0,0,(SELECT COUNT(Id) FROM soda_question_exceptionprestation WHERE Suppr=0 AND Id_Question=".$_GET['Id']." AND Id_Prestation=new_competences_prestation.Id)) AS Exception
										FROM new_competences_prestation
										WHERE Id_Plateforme NOT IN (11,14)
										ORDER BY Libelle;";
								
									$resultPresta=mysqli_query($bdd,$req);
									$nbPresta=mysqli_num_rows($resultPresta);
									
									if ($nbPresta > 0)
									{
										while($row=mysqli_fetch_array($resultPresta))
										{
											$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
											if($presta==""){$presta=$row['Libelle'];}
											$selected="checked";
											if($row['Exception']>0){$selected="";}
											echo "<tr><td><input class='checkPrestation' type='checkbox' ".$selected." value='".$row['Id']."' name='Prestation".$row['Id']."'>".stripslashes($presta)."</td></tr>";
										}
									}
								?>
									</table>
								</div>
							</td>
						</tr>
					</table>
					<?php
					if($_GET['Mode']=="M")
					{
					?>
					<input class="Bouton" type="button" onclick="OuvrirExceptionPrestaion(<?php echo $_GET['Id']; ?>)" value="Liste des exceptions de prestations" />
					<?php
					}
					?>
				</td>
			</tr>
		</table>
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="M")
						{
							if($_SESSION['Langue']=="FR"){echo "value='Valider'";}
							else{echo "value='Validate'";}
						}
						elseif($_GET['Mode']=="D")
						{
							if($_SESSION['Langue']=="FR"){echo "value='Dupliquer'";}
							else{echo "value='Duplicate'";}
						}
						else
						{
							if($_SESSION['Langue']=="FR"){echo "value='Ajouter'";}
							else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
		</table><br>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="
            UPDATE
                soda_question
            SET
                Suppr=1,
                Id_Suppr=".$_SESSION['Id_Personne'].",
                DateSuppr='".date('Y-m-d')."'
            WHERE
                Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>