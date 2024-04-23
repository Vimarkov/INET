<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function VerifChamps(Mode)
		{
			if(Mode=="M")
			{
				if(formulaire.numero.value==''){alert('Vous n\'avez pas renseigné le numéro de la question.');return false;}
				else
				{
					if(formulaire.nom.value==''){alert('Vous n\'avez pas renseigné l\'intitulé de la question.');return false;}
					else{return true;}
				}
			}
		}

		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
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
	if($_POST['Mode']=="A")
	{
		for($i=1;$i<=10;$i++)
		{
			if($_POST['numero'.$i]<>"" && $_POST['nom'.$i]<>"")
			{
				$requete="
                    INSERT INTO new_surveillances_question
                        (
                        ID_Questionnaire,
                        ID_Personne,
                        DateModification,
                        Numero,
                        Modifiable,
                        Question,
						Question_EN,
						Reponse,
						Reponse_EN
                        )
                    VALUES
                        (".
				        $_POST['ID_Questionnaire'].",".
			            $_POST['ID_Personne'].",
                        '".$dateDuJour."',".
			            $_POST['numero'.$i].",".
				        $_POST['questionModifiable'.$i].",
                        '".addslashes($_POST['nom'.$i])."',
                        '".addslashes($_POST['nom_EN'.$i])."',
                        '".addslashes($_POST['reponse'.$i])."',
                        '".addslashes($_POST['reponse_EN'.$i])."'
                        )";
				$result=mysqli_query($bdd,$requete);
				$Id=mysqli_insert_id($bdd);
				
				//Vérifier si ce numéro n'existe pas déjà
				$req="SELECT ID, Numero FROM new_surveillances_question WHERE Supprime=0 AND ID_Questionnaire=". $_POST['ID_Questionnaire']." AND ID<>".$Id." ORDER BY Numero ";
				$resultQuestion=mysqli_query($bdd,$req);
				$nbQuestion=mysqli_num_rows($resultQuestion);
				if($nbQuestion>0){
					$numero= $_POST['numero'.$i];
					while($rowQ=mysqli_fetch_array($resultQuestion))
					{
						if($rowQ['Numero']==$numero){
							$numero++;
							$requete="
								UPDATE
									new_surveillances_question
								SET
									Supprime=1,
									ID_Personne=".$_POST['ID_Personne'].",
									DateModification='".$dateDuJour."'
								WHERE
									ID=".$rowQ['ID'];
							$result=mysqli_query($bdd,$requete);
							
							$requete="
								INSERT INTO new_surveillances_question
									(
									ID_Questionnaire,
									ID_Personne,
									DateModification,
									Numero,
									Modifiable,
									Question,
									Question_EN,
									Reponse,
									Reponse_EN
									)
								SELECT ID_Questionnaire,
									".$_POST['ID_Personne'].",
									'".$dateDuJour."',
									".$numero.",
									Modifiable,
									Question,
									Question_EN,
									Reponse,
									Reponse_EN
								FROM new_surveillances_question
								WHERE ID=".$rowQ['ID']." ";
							$result=mysqli_query($bdd,$requete);
						}
					}
				}
				
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M")
	{
		$resultQuestion=mysqli_query($bdd,"SELECT ID,ID_Questionnaire,ID_Personne,DateModification,Numero,Question,Question_EN,Reponse,Reponse_EN, Modifiable FROM new_surveillances_question WHERE ID=".$_POST['ID']);
		$LigneQuestion=mysqli_fetch_array($resultQuestion);
		
		$requete="
            UPDATE
                new_surveillances_question
            SET
                Supprime=1,
                ID_Personne=".$_POST['ID_Personne'].",
                DateModification='".$dateDuJour."'
            WHERE
                ID=".$_POST['ID'];
		$result=mysqli_query($bdd,$requete);
		
		$requete="
            INSERT INTO new_surveillances_question
                (
                ID_Questionnaire,
                ID_Personne,
                DateModification,
                Numero,
                Modifiable,
                Question,
                Question_EN,
				Reponse,
				Reponse_EN
                )
            VALUES
                (".
                $_POST['ID_Questionnaire'].",".
                $_POST['ID_Personne'].",
                '".$dateDuJour."',".
                $_POST['numero'].",".
                $_POST['questionModifiable'].",
                '".addslashes($_POST['nom'])."',
                '".addslashes($_POST['nom_EN'])."',
				 '".addslashes($_POST['reponse'])."',
                '".addslashes($_POST['reponse_EN'])."'
                )";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M")
	{
		if($_GET['ID']!='0')
		{
			$resultQuestion=mysqli_query($bdd,"SELECT ID,ID_Questionnaire,ID_Personne,DateModification,Numero,Question,Question_EN,Reponse,Reponse_EN,Modifiable FROM new_surveillances_question WHERE ID=".$_GET['ID']);
			$LigneQuestion=mysqli_fetch_array($resultQuestion);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Question.php" onSubmit="return VerifChamps(<?php echo $_GET['Mode']; ?>);">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="ID" value="<?php if($_GET['Mode']=="M"){echo $LigneQuestion['ID'];}?>">
		<input type="hidden" name="ID_Questionnaire" value="<?php echo $_GET['ID_Questionnaire']; ?>">
		<input type="hidden" name="ID_Personne" value="<?php echo $_GET['ID_Personne']; ?>">
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
		<?php
			if($_GET['Mode']=="A")
		    {
				for($i=1;$i<=10;$i++)
				{
		?>
			<tr class="TitreColsUsers">
				<td class="Libelle">
					<?php
						if($_SESSION['Langue']=="FR"){echo "Numéro :";}
						else{echo "Number :";}
					?>
				</td>
				<td>
					<input onKeyUp="nombre(this)" id="numero<?php echo $i;?>" type="text" style="text-align:left;" name="numero<?php echo $i;?>" size="10" value="<?php if($_GET['Mode']=="M"){echo $LigneQuestion['Numero'];} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Question FR : </td>
				<td>
					<input id="nom<?php echo $i;?>" type="text" style="text-align:left;" name="nom<?php echo $i;?>" size="100" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Question']));} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Question EN : </td>
				<td>
					<input id="nom_EN<?php echo $i;?>" type="text" style="text-align:left;" name="nom_EN<?php echo $i;?>" size="100" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Question_EN']));} ?>">
				</td>
			</tr>
			<tr>
				<td height="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Réponse FR : </td>
				<td>
					<input id="reponse<?php echo $i;?>" type="text" style="text-align:left;" name="reponse<?php echo $i;?>" size="100" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse']));} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Réponse EN : </td>
				<td>
					<input id="reponse_EN<?php echo $i;?>" type="text" style="text-align:left;" name="reponse_EN<?php echo $i;?>" size="100" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse_EN']));} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">
					<?php
						if($_SESSION['Langue']=="FR"){echo "Questionnaire modifiable :";}
						else{echo "Editable question :";}
					?>
				</td>
				<td>
					<select name="questionModifiable<?php echo $i;?>" style="width=10;">
						<option value="0" selected>Non</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($LigneQuestion['Modifiable']==1){echo "selected";}} ?>>Oui</option>
					</select>
				</td>
			</tr>
			<tr>
				<td height="15px">
				
				</td>
			</tr>
			<?php
				}
			}
			else
			{
			?>
			<tr class="TitreColsUsers">
				<td class="Libelle">
					<?php
						if($_SESSION['Langue']=="FR"){echo "Numéro :";}
						else{echo "Number :";}
					?>
				</td>
				<td>
					<input onKeyUp="nombre(this)" id="numero" type="text" style="text-align:left;" name="numero" size="10" value="<?php if($_GET['Mode']=="M"){echo stripslashes($LigneQuestion['Numero']);} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Question FR : </td>
				<td>
					<input id="nom" type="text" style="text-align:left;" name="nom" size="150" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Question']));} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Question EN : </td>
				<td>
					<input id="nom_EN" type="text" style="text-align:left;" name="nom_EN" size="150" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Question_EN']));} ?>">
				</td>
			</tr>
			<tr>
				<td height="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Réponse FR : </td>
				<td>
					<input id="nom" type="text" style="text-align:left;" name="reponse" size="150" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse']));} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Réponse EN : </td>
				<td>
					<input id="nom_EN" type="text" style="text-align:left;" name="reponse_EN" size="150" value="<?php if($_GET['Mode']=="M"){echo stripslashes(htmlspecialchars($LigneQuestion['Reponse_EN']));} ?>">
				</td>
			</tr>
			<tr>
				<td height="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">
					<?php
						if($_SESSION['Langue']=="FR"){echo "Questionnaire modifiable :";}
						else{echo "Editable question :";}
					?>
				</td>
				<td>
					<select name="questionModifiable" style="width=10;">
						<option value="0" selected>Non</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($LigneQuestion['Modifiable']==1){echo "selected";}} ?>>Oui</option>
					</select>
				</td>
			</tr>
			<?php
				}
			?>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="M")
						{
							if($_SESSION['Langue']=="FR"){echo "value='Valider'";}
							else{echo "value='Validate'";}
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
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="
            UPDATE
                new_surveillances_question
            SET
                Supprime=1,
                ID_Personne=".$_GET['ID_Personne'].",
                DateModification='".$dateDuJour."'
            WHERE
                ID=".$_GET['ID'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>