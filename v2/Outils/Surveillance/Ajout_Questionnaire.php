<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.ID_Theme.value==0){alert('Vous n\'avez pas renseigné le thème du questionnaire.');return false;}
			else
			{
				if(formulaire.nom.value==''){alert('Vous n\'avez pas renseigné le nom du questionnaire.');return false;}
				else{return true;}
			}
		}

		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
$dateDuJour = date("Y/m/d");
if($_POST)
{
	if($_POST['Mode']=="A")
	{
		$requete="INSERT INTO new_surveillances_questionnaire (ID_Theme,ID_Plateforme,ID_Personne,DateModification,Nom,Actif)";
		$requete.=" VALUES (";
		$requete.=$_POST['ID_Theme'].",";
		$requete.=$_POST['ID_Plateforme'].",";
		$requete.=$_POST['ID_Personne'].",'";
		$requete.=$dateDuJour."','";
		$requete.=addslashes($_POST['nom'])."',
		".$_POST['actif']."
		)";
		$result=mysqli_query($bdd,$requete);
		$Id=mysqli_insert_id($bdd);
		
		if($_POST['QuestionnaireADupliquer']>0){
			$req="INSERT INTO new_surveillances_question (ID_Questionnaire,Numero,Question,Question_EN,Reponse,Reponse_EN,Modifiable,DateModification,ID_Personne) 
				SELECT ".$Id.",Numero,Question,Question_EN,Reponse,Reponse_EN,Modifiable,".date('Y-m-d').",".$_SESSION['Id_Personne']." 
				FROM new_surveillances_question 
				WHERE Supprime=0 
				AND ID_Questionnaire=".$_POST['QuestionnaireADupliquer']." ";
			$result=mysqli_query($bdd,$req);
			
			//Changer les questionnaires des surveillances uniquement planifiées (si elles ont commencé à être réliser alors on y touche pas)
			$req="UPDATE new_surveillances_surveillance SET ID_Questionnaire=".$Id." 
				WHERE ID_Questionnaire=".$_POST['QuestionnaireADupliquer']."
				AND Etat IN ('Planifié','Replanifié') ";
			$resultUpdt=mysqli_query($bdd,$req);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M")
	{
		$resultQuestionnaire=mysqli_query($bdd,"SELECT ID,ID_Theme,ID_Plateforme,Nom FROM new_surveillances_questionnaire WHERE ID=".$_POST['ID']);
		$LigneQuestionnaire=mysqli_fetch_array($resultQuestionnaire);
		
		$requete="UPDATE new_surveillances_questionnaire SET ";
		$requete.="ID_Theme=".$_POST['ID_Theme'].", ";
		$requete.="ID_Plateforme=".$_POST['ID_Plateforme'].", ";
		$requete.="Actif=".$_POST['actif'].", ";
		$requete.="ID_Personne=".$_POST['ID_Personne'].", ";
		$requete.="DateModification='".$dateDuJour."', ";
		$requete.="Nom='".addslashes($_POST['nom'])."' ";
		$requete.="WHERE ID=".$_POST['ID'];
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
			$resultQuestionnaire=mysqli_query($bdd,"SELECT ID,ID_Theme,ID_Plateforme,Nom,Actif FROM new_surveillances_questionnaire WHERE ID=".$_GET['ID']);
			$LigneQuestionnaire=mysqli_fetch_array($resultQuestionnaire);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Questionnaire.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="ID" value="<?php if($_GET['Mode']=="M"){echo $LigneQuestionnaire['ID'];}?>">
		<input type="hidden" name="ID_Personne" value="<?php echo $_GET['ID_Personne']; ?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Thème :";}
						else{echo "Theme :";}
					?>
				</td>
				<td>
					<select name="ID_Theme" id="ID_Theme">
					<?php
						$result2=mysqli_query($bdd,"SELECT ID, Nom FROM new_surveillances_theme ORDER BY Nom ASC");
						echo "<option value='0' selected></option>";
						while($row2=mysqli_fetch_array($result2))
						{
							echo "<option value='".$row2['ID']."'";
							if($_GET['Mode']=="M"){if($LigneQuestionnaire['ID_Theme']==$row2['ID']){echo " selected";}}
							echo ">".$row2['Nom']."</option>\n";
						}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Entité :";}
						else{echo "Entity :";}
					?>
				</td>
				<td>
					<select id="ID_Plateforme" name="ID_Plateforme">
					<?php
						$result2=mysqli_query($bdd,"SELECT ID, Libelle FROM new_competences_plateforme ORDER BY Libelle ASC");
						echo "<option value='0' selected></option>";
						while($row2=mysqli_fetch_array($result2))
						{
							echo "<option value='".$row2['ID']."'";
							if($_GET['Mode']=="M"){if($LigneQuestionnaire['ID_Plateforme']==$row2['ID']){echo " selected";}}
							echo ">".$row2['Libelle']."</option>\n";
						}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Nom du questionnaire :";}
						else{echo "Questionnaire's name :";}
					?>
				</td>
				<td>
					<input id="nom" type="texte" style="text-align:left;" name="nom" size="40" value="<?php if($_GET['Mode']=="M"){echo $LigneQuestionnaire['Nom'];} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Actif/Inactif :";}
						else{echo "Active / Inactive :";}
					?>
				</td>
				<td>
					<select name="actif" id="actif">
						<option value="0" <?php if($_GET['Mode']=="M"){if($LigneQuestionnaire['Actif']==0){echo " selected";}}else{echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Actif";}else{echo "Active";} ?></option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($LigneQuestionnaire['Actif']==1){echo " selected";}}else{echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Inactif";}else{echo "Inactive";} ?></option>
					</select>
				</td>
			</tr>
			<?php if($_GET['Mode']=="A"){ ?>
			<tr class="TitreColsUsers">
				<td>
					<?php
						if($_SESSION['Langue']=="FR"){echo "Dupliquer le questionnaire :";}
						else{echo "Duplicate the questionnaire :";}
					?>
				</td>
				<td>
					<select name="QuestionnaireADupliquer" id="QuestionnaireADupliquer" width="300px">
					<?php
						$req = "
						SELECT
							new_surveillances_questionnaire.ID,
							new_surveillances_questionnaire.ID_Plateforme,
							new_surveillances_questionnaire.DQ_FR,
							new_surveillances_questionnaire.DQ_EN,
							(SELECT Nom FROM new_surveillances_theme WHERE ID=ID_Theme) AS Theme,
							(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_surveillances_questionnaire.ID_Plateforme) AS Plateforme,
							new_surveillances_questionnaire.Nom
						FROM
							new_surveillances_questionnaire
						WHERE
							new_surveillances_questionnaire.Supprime =0
						ORDER BY
							Theme,Plateforme,
							new_surveillances_questionnaire.Nom ;";
						$resultQuestionnaire=mysqli_query($bdd,$req);
						$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
						echo "<option value='0' selected></option>";
						while($row2=mysqli_fetch_array($resultQuestionnaire))
						{
							echo "<option value='".$row2['ID']."'";
							echo ">".$row2['Theme']." [".$row2['Plateforme']."] ".$row2['Nom']."</option>\n";
						}
					?>
					</select>
				</td>
			</tr>
			<?php } ?>
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
		$requete="UPDATE new_surveillances_questionnaire SET ";
		$requete.="Supprime=1, ";
		$requete.="ID_Personne=".$_GET['ID_Personne'].", ";
		$requete.="DateModification='".$dateDuJour."' ";
		$requete.="WHERE ID=".$_GET['ID'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>