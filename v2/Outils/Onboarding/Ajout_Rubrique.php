<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(Mode)
		{
			if(formulaire.Mode.value=='A'){
				if(formulaire.theme.value==''){alert('Vous n\'avez pas renseigné le thème.');return false;}
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné l\'intitulé de la rubrique.');return false;}
				if(formulaire.fichier.value==''){alert('Vous n\'avez pas ajouter de document.');return false;}
			}
			return true;
		}

		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
		function CheckImage(){if(formulaire.image.value!=''){formulaire.SupprImage.checked=true;}}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");

$DirFichier="Contenu/";
$dateDuJour = date("Y-m-d");
$SrcProblem="";
$Problem=0;
$Fichier="";
$image="";
$FichierTransfert=0;

$req="SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
$resultAdm=mysqli_query($bdd,$req);
$nbAdm=mysqli_num_rows($resultAdm);

if($_POST)
{
	if($_POST['Mode']=="A")
	{
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la suppression de l ancien du fichier joint (".$SrcProblem.");</script>";}
		else
		{
			//****TRANSFERT FICHIER****
			if($_FILES['fichier']['name']!="")
			{
				$tmp_file=$_FILES['fichier']['tmp_name'];
				if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
					else
					{
						// on copie le fichier dans le dossier de destination
						$name_file=$_FILES['fichier']['name'];
						$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
						while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
						else{$Fichier=$name_file;$FichierTransfert=1;}
					}
				}
			}
			
			if($_FILES['image']['name']!="")
			{
				$extensions_valides = array('.jpg', '.jpeg', '.gif', '.png');
				$extension = strtolower(strrchr($_FILES['image']['name'], '.'));
				$tmp_file=$_FILES['image']['tmp_name'];
				if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le image est introuvable.";$Problem=1;}
				else
				{
					if(in_array($extension, $extensions_valides)){
						//On vérifie la taille du fichiher
						if(filesize($_FILES['image']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
						{$SrcProblem.="L'image est trop volumineuse.";$Problem=1;}
						else
						{
							// on copie le fichier dans le dossier de destination
							$name_file=$_FILES['image']['name'];
							$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
							while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
							if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
							{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
							else{$image=$name_file;$FichierTransfert=1;}
						}
					}
					else
					{
						$SrcProblem.= "Le fichier ".$_FILES['image']['name']." n'est pas au bon format. Il doit être au format jpg, jpeg, gif ou png.";
					}
				}
			}
				
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{
				$valide=0;
				$Id_Valideur=0;
			
						
				$requete="
				INSERT INTO onboarding_contenu
					(
					Libelle,
					Id_Createur,
					Rubrique,
					TypeDocument,
					VisibleUniquementSalarie,
					Description,
					DateCreation,
					Document,
					Image,
					Id_Plateforme,
					Valide,
					Id_Valideur
					)
				VALUES
					(
					'".addslashes($_POST['Libelle'])."',
					".$_SESSION['Id_Personne'].",
					'".addslashes($_POST['theme'])."',
					'".addslashes($_POST['typeDocument'])."',
					'".addslashes($_POST['visibleUniquementSalarie'])."',
					'".addslashes($_POST['description'])."',
					'".date('Y-m-d')."',
					'".$Fichier."',
					'".$image."',
					".$_POST['uer'].",
					".$valide.",
					".$Id_Valideur."
					)";
					echo $requete;
				$resultInsert=mysqli_query($bdd,$requete);
				$Id_New=mysqli_insert_id($bdd);
			}
		}
	}
	elseif($_POST['Mode']=="M"){
		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){
					$SrcProblem.="Impossible de supprimer le fichier.";
					$Problem=1;
				}
				elseif($FichierTransfert==0){$Fichier="";}
				
				$requete="
				UPDATE onboarding_contenu
				SET
					Document='',
				WHERE Id=".$_POST['Id']." ";
				$resultUpdt=mysqli_query($bdd,$requete);
			}
		}
		
		if(isset($_POST['SupprImage']))
		{
			if($_POST['SupprImage'])
			{
				if(!unlink($DirFichier.$_POST['imageAccueil'])){
					$SrcProblem.="Impossible de supprimer le fichier.";
					$Problem=1;
				}
				elseif($FichierTransfert==0){$Fichier="";}
				
				$requete="
				UPDATE onboarding_contenu
				SET
					Image='',
				WHERE Id=".$_POST['Id']." ";
				$resultUpdt=mysqli_query($bdd,$requete);
			}
		}
		
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la suppression de l ancien du fichier joint (".$SrcProblem.");</script>";}
		else
		{
			//****TRANSFERT FICHIER****
			if($_FILES['fichier']['name']!="")
			{
				$tmp_file=$_FILES['fichier']['tmp_name'];
				if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
					else
					{
						// on copie le fichier dans le dossier de destination
						$name_file=$_FILES['fichier']['name'];
						$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
						while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
						else{$Fichier=$name_file;$FichierTransfert=1;}
					}
				}
			}
			
			if($_FILES['image']['name']!="")
			{
				$extensions_valides = array('.jpg', '.jpeg', '.gif', '.png');
				$extension = strtolower(strrchr($_FILES['image']['name'], '.'));
				$tmp_file=$_FILES['image']['tmp_name'];
				if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le image est introuvable.";$Problem=1;}
				else
				{
					if(in_array($extension, $extensions_valides)){
						//On vérifie la taille du fichiher
						if(filesize($_FILES['image']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
						{$SrcProblem.="L'image est trop volumineuse.";$Problem=1;}
						else
						{
							// on copie le fichier dans le dossier de destination
							$name_file=$_FILES['image']['name'];
							$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
							while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
							if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
							{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
							else{$image=$name_file;$FichierTransfert=1;}
						}
					}
					else
					{
						$SrcProblem.= "Le fichier ".$_FILES['image']['name']." n'est pas au bon format. Il doit être au format jpg, jpeg, gif ou png.";
					}
				}
			}
				
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{
						
				$requete="
				UPDATE onboarding_contenu
				SET
					Libelle='".addslashes($_POST['Libelle'])."',
					Rubrique='".addslashes($_POST['theme'])."',
					TypeDocument='".addslashes($_POST['typeDocument'])."',
					VisibleUniquementSalarie='".addslashes($_POST['visibleUniquementSalarie'])."',
					Description='".addslashes($_POST['description'])."',
					Id_Plateforme=".$_POST['uer']."
				WHERE Id=".$_POST['Id']." ";
				$resultUpdt=mysqli_query($bdd,$requete);
				
				if($Fichier<>""){
					$requete="
					UPDATE onboarding_contenu
					SET
						Document='".$Fichier."'
					WHERE Id=".$_POST['Id']." ";
					$resultUpdt=mysqli_query($bdd,$requete);
				}
				
				if($image<>""){
					$requete="
					UPDATE onboarding_contenu
					SET
						Image='".$image."'
					WHERE Id=".$_POST['Id']." ";
					$resultUpdt=mysqli_query($bdd,$requete);
				}
				
			}
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M")
	{
		if($_GET['Mode']=="M"){
			$req="SELECT Libelle,Id_Createur,Rubrique,Document,Id_Plateforme,TypeDocument,Description,Image,Valide,VisibleUniquementSalarie FROM onboarding_contenu WHERE Id=".$_GET['Id']." ";
			$resultContenu=mysqli_query($bdd,$req);
			$rowContenu=mysqli_fetch_array($resultContenu);
		}
?>
		<form id="formulaire" method="POST" enctype="multipart/form-data" action="Ajout_Rubrique.php" onSubmit="return VerifChamps('<?php echo $_GET['Mode']; ?>');">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
		<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
			<tr><td height="10"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Thème";} ?></td>
				<td class="Libelle">
					<select id="theme" name="theme">
						<option value=""></option>
						<?php 
							$req="SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
							$resultAdm=mysqli_query($bdd,$req);
							$nbAdm=mysqli_num_rows($resultAdm);
							if($nbAdm>0){
						?>
							<option value="Achats" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Achats"){echo "selected";}}?>>Achats</option>
							<option value="Bienvenue chez AAA" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Bienvenue chez AAA"){echo "selected";}}?>>Bienvenue chez AAA</option>
							<option value="Excellence opérationnelle" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Excellence opérationnelle"){echo "selected";}}?>>Excellence opérationnelle</option>
							<option value="Formation interne" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Formation interne"){echo "selected";}}?>>Formation interne</option>
							<option value="Informatique" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Informatique"){echo "selected";}}?>>Informatique</option>
							<option value="Innovation" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Innovation"){echo "selected";}}?>>Innovation</option>
							<option value="Qualité" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Qualité"){echo "selected";}}?>>Qualité</option>
							<option value="Ressources humaines" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Ressources humaines"){echo "selected";}}?>>Ressources humaines</option>
							<option value="Sécurité et environnement" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Sécurité et environnement"){echo "selected";}}?>>Sécurité et environnement</option>
							<option value="Vie quotidienne" <?php if($_GET['Mode']=="M"){if($rowContenu['Rubrique']=="Vie quotidienne"){echo "selected";}}?>>Vie quotidienne</option>
						<?php 
							}
							else{
								$req="SELECT Rubrique FROM onboarding_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']." ORDER BY Rubrique ";
								$resultAdm=mysqli_query($bdd,$req);
								$nbAdm=mysqli_num_rows($resultAdm);
								if($nbAdm>0){
									while($row=mysqli_fetch_array($resultAdm)){
										$selected="";
										echo "<option value='".$row['Rubrique']."' ".$selected.">".stripslashes($row['Rubrique'])."</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intitulé de la rubrique";}else{echo "Section title";}?> : </td>
				<td>
					<input id="Libelle" name="Libelle" size="50" type="text" value="<?php if($_GET['Mode']=="M"){echo stripslashes($rowContenu['Libelle']);}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Image d'affichage";}else{echo "Display image";}?> : </td>
				<td><input id="image" name="image" type="file" onClick="CheckImage();"></td>
			</tr>
			<tr>
				<?php
				if($_GET['Mode']=="M" && $rowContenu['Image']!="")
				{
				?>
				<td colspan="4">
					<?php 
						echo "<img class='imageAccueil' width='200px' src='".$DirFichier."/".$rowContenu['Image']."' />";
					?>
					<input type="hidden" name="imageactuel" value="<?php echo $rowContenu['Image'];?>">
				</td>
			</tr>
			<tr colspan="4">
				<td class="PoliceModif"><input type="checkbox" name="SupprImage" onClick="CheckImage();"><?php if($_SESSION['Langue']=="FR"){echo "Supprimer l'image";}else{echo "Delete the image";}?></td>
				<?php
				}
				?>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Description";}else{echo "Description";}?> : </td>
				<td>
					<textarea id="description" name="description" cols="80" rows="15" style="resize:none;"><?php if($_GET['Mode']=="M"){echo stripslashes($rowContenu['Description']);}?></textarea>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Document";}else{echo "Document";}?> : </td>
				<td><input id="fichier" name="fichier" type="file" onClick="CheckFichier();"></td>
			</tr>
			<tr>
				<?php
				if($_GET['Mode']=="M" && $rowContenu['Document']!="")
				{
				?>
				<td>
					<?php 
						if($_SESSION['Langue']=="FR"){
							echo "<a class=\"Info\" href=\"".$DirFichier."/".$rowContenu['Document']."\" target=\"_blank\">Ouvrir</a>";
						}
						else{
							echo "<a class=\"Info\" href=\"".$DirFichier."/".$rowContenu['Document']."\" target=\"_blank\">Open</a>";
						}
					?>
					<input type="hidden" name="fichieractuel" value="<?php echo $rowContenu['Document'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($_SESSION['Langue']=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Specific UER";}else{echo "UER Spécifique";} ?> : </td>
				<td>
					<select id="uer" name="uer">
						<option value='0' selected></option>
						<?php 
						$req="SELECT Id, Libelle
							FROM new_competences_plateforme
							WHERE Id NOT IN (11,14)
							ORDER BY Libelle;";
						$resultPlate=mysqli_query($bdd,$req);
						$nbPlate=mysqli_num_rows($resultPlate);
						$i=0;
						if ($nbPlate > 0)
						{
							while($row=mysqli_fetch_array($resultPlate))
							{
								$selected="";
								if($_GET['Mode']=="M"){
									if($row['Id']==$rowContenu['Id_Plateforme']){$selected="selected";}
								}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Visible only to employees";}else{echo "Visible uniquement aux salariés";} ?></td>
				<td class="Libelle">
					<select id="visibleUniquementSalarie" name="visibleUniquementSalarie">
						<option value="1" <?php if($_GET['Mode']=="M"){if($rowContenu['VisibleUniquementSalarie']==1){echo "selected";}}?>>Oui</option>
						<option value="0" <?php if($_GET['Mode']=="M"){if($rowContenu['VisibleUniquementSalarie']==0){echo "selected";}}else{echo "selected";}?>>Non</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Document type";}else{echo "Type de document";} ?> : </td>
				<td>
					<select id="typeDocument" name="typeDocument">
						<option value="A télécharger" <?php if($_GET['Mode']=="M"){if($rowContenu['TypeDocument']=="A télécharger"){echo "selected";}}?>>A télécharger</option>
						<option value="Vidéo" <?php if($_GET['Mode']=="M"){if($rowContenu['TypeDocument']=="Vidéo"){echo "selected";}}?>>Vidéo</option>
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="M"){
							if($_SESSION['Langue']=="FR"){echo "value='Modifier'";}
							else{echo "value='Edit'";}
						}
						else{
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
	elseif($_GET['Mode']=="T")
	{
		$requete="
            UPDATE
                onboarding_contenu
            SET
				DateCreation='".date('Y-m-d')."',
                Valide=2,
                Id_Valideur=".$_SESSION['Id_Personne']."
            WHERE
                Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="AV")
	{
		$requete="
            UPDATE
                onboarding_contenu
            SET
                Valide=0,
                Id_Valideur=".$_SESSION['Id_Personne']."
            WHERE
                Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="V")
	{
		$requete="
            UPDATE
                onboarding_contenu
            SET
				DateCreation='".date('Y-m-d')."',
                Valide=1,
                Id_Valideur=".$_SESSION['Id_Personne']."
            WHERE
                Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="R")
	{
		$requete="
            UPDATE
                onboarding_contenu
            SET
                Valide=-1,
                Id_Valideur=".$_SESSION['Id_Personne']."
            WHERE
                Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression
	{
		$requete="
            UPDATE
                onboarding_contenu
            SET
                Suppr=1,
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