<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un document - Langue</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript">
		function OuvreFenetreModif(Mode,Id_Document_Langue,Id_Document,Id)
		{
			Confirm=false;
			if(document.getElementById('Langue').value=="FR"){
				if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
			}
			else{
				if(Mode=="Suppr"){Confirm=window.confirm('Are you sure you want to delete?');}
			}
			if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
			{
				var w=window.open("Ajout_Document_Langue_Question.php?Mode="+Mode+"&Id_Document_Langue="+Id_Document_Langue+"&Id_Document="+Id_Document+"&Id="+Id,"PageDocumentLangueQuestion","status=no,menubar=no,width=520,height=250");
				w.focus();
			}
		}

		function OuvreFenetreAjoutQuestionnaire(Id_Document_Langue,Id_Document,Id)
		{
			var w=window.open("Ajout_Document_Langue_QuestionnaireComplet.php?&Id_Document_Langue="+Id_Document_Langue+"&Id_Document="+Id_Document,"PageDocumentLangueQuestionnaireComplet","status=no,menubar=no,scrollbars=yes,width=1000,height=700");
			w.focus();
		}

		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				if(formulaire.Date_MAJ.value==''){alert('Vous n\'avez pas renseigné la date de mise à jour.');return false;}
				if(formulaire.Id_Personne_MAJ.value=='0'){alert('Vous n\'avez pas renseigné le champs "Mis à jour par".');return false;}
				return true;
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				if(formulaire.Date_MAJ.value==''){alert('You did not fill in the update date.');return false;}
				if(formulaire.Id_Personne_MAJ.value=='0'){alert('You did not fill in the "Updated by".');return false;}
				return true;

			}
		}
			
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
		function Lister_Reponses(){
			if(document.getElementById('Langue').value=="FR"){
				Lister_Dependances('Table_Questions','Liste_Reponses','Id_Document_Langue_Question|Réponse|Image','0|250|20','Affichage_Liste_Reponses','Ajout_QCM_Langue_Question_Reponse','520','150','Liste des réponses');
			}
			else{
				Lister_Dependances('Table_Questions','Liste_Reponses','Id_Document_Langue_Question|Answer|Picture','0|250|20','Affichage_Liste_Reponses','Ajout_QCM_Langue_Question_Reponse','520','150','List of answers');
			}
		}
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
	</script>
</head>
<body>

<?php
Ecrire_Code_JS_Init_Date();

if($_POST){$DirFichier=$CheminFormation."Document/".$_POST['Id_Document']."/";}
else{$DirFichier=$CheminFormation."Document/".$_GET['Id_Document']."/";}

if(!file_exists($DirFichier))
{
	$res=mkdir_ftp($DirFichier,0773);
	if(!$res){echo 'Echec lors de la création des répertoires...';}
}


if($_POST)
{
	$requete="";
	if($_POST['Mode']=="Ajout")
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_document_langue WHERE Id_Document=".$_POST['Id_Document']." AND Suppr=0 AND Id_Langue=".$_POST['Id_Langue']))==0)
		{
			$requete="INSERT INTO form_document_langue (Id_Document, Id_Langue, Libelle,Date_MAJ,Id_Personne_MAJ,NomDocument)";
			$requete.=" VALUES (";
			$requete.=$_POST['Id_Document'];
			$requete.=",".$_POST['Id_Langue'];
			$requete.=",'".addslashes($_POST['Libelle'])."'";
			$requete.=",'".TrsfDate_($_POST['Date_MAJ'])."'";
			$requete.=",".$_POST['Id_Personne_MAJ']."";
			$requete.=",'##FICHIER##'";
			$requete.=")";
		}
	}
	else	//Mode modification
	{
		$requete="UPDATE form_document_langue SET";
		$requete.=" Libelle='".addslashes($_POST['Libelle'])."'";
		$requete.=", Date_MAJ='".TrsfDate_($_POST['Date_MAJ'])."'";
		$requete.=", Id_Personne_MAJ=".$_POST['Id_Personne_MAJ']."";
		$requete.=", NomDocument='##FICHIER##'";
		$requete.=" WHERE Id=".$_POST['Id'];
	}
	if($requete!="")
	{
		if($_POST['Mode']=="Ajout")
		{
			//Création du répertoire pour la gestion des fichiers joints des questions des QCM
			$res = mkdir_ftp($CheminFormation."Document/".$_POST['Id_Document']."/".mysqli_insert_id($bdd), 0773);
			if(!$res){echo 'Echec lors de la création des répertoires...';}
		}
		$Problem=0;
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
						while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
						else{$Fichier=$name_file;$FichierTransfert=1;}
					}
				}
			}
			
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{
				$requete = str_replace("##FICHIER##",$Fichier,$requete);
				$result=mysqli_query($bdd,$requete);
				echo "<script>FermerEtRecharger();</script>";
			}
	}
	else{echo "<font class='Erreur'>Cette langue existe déjà.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	$DirFichier="https://extranet.aaa-aero.com/v2/Outils/Formation/Docs/Document/";
	
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0'){
			$Modif=true;
			$result=mysqli_query($bdd,"SELECT Id, Id_Document, Id_Langue, Libelle, Date_MAJ, Id_Personne_MAJ, NomDocument FROM form_document_langue WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Document_Langue.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" id="Id" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" id="Id_Document" name="Id_Document" value="<?php echo $_GET['Id_Document'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";}?> : </td>
				<td>
					<select name="Id_Langue" <?php if($Modif){echo "disabled";}?>>
						<?php
						$resultLangue=mysqli_query($bdd,"SELECT Id, Libelle FROM form_langue WHERE Suppr=0");
						while($rowLangue=mysqli_fetch_array($resultLangue))
						{
							echo "<option value='".$rowLangue['Id']."'";
							if($Modif){if($rowLangue['Id']==$row['Id_Langue']){echo " selected";}}
							echo ">".$rowLangue['Libelle']."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td>
					<input id="Libelle" name="Libelle" size="75" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Mis à jour le";}else{echo "Updated on";}?> : </td>
				<td>
					<input type="date" name="Date_MAJ" size="10" value="<?php if($Modif){echo AfficheDateFR($row['Date_MAJ']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Mis à jour par";}else{echo "Update By";}?> : </td>
				<td>
					<select name="Id_Personne_MAJ">
						<option value="0"></option>
						<?php
						$reqUpdate="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom ";
						$reqUpdate.="FROM new_rh_etatcivil ";
						$reqUpdate.="ORDER BY Nom, Prenom ";
						$resultUpdater=mysqli_query($bdd,$reqUpdate);
						while($rowUpdater=mysqli_fetch_array($resultUpdater)){
							echo "<option value='".$rowUpdater['Id']."'";
							if($Modif){if($rowUpdater['Id']==$row['Id_Personne_MAJ']){echo " selected";}}
							else{if($rowUpdater['Id']==$IdPersonneConnectee){echo " selected";}}
							echo ">".$rowUpdater['Nom']." ".$rowUpdater['Prenom']."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fichier";}else{echo "File";}?> : </td>
				<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
			</tr>
			<tr>
				<?php
				if($Modif && $row['NomDocument']!="")
				{
				?>
				<td>
					<a class="Info" href="<?php echo $DirFichier.$row['NomDocument']; ?>" target="_blank"><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['NomDocument'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif)
						{
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else
						{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
			
			<?php
			if($Modif)
			{
			?>
			<!-- Gestion des questions  -->
			<tr>
				<td colspan="2" valign="top" width="100%">
					<table class="ProfilCompetence" id="Table_Questions" style="width:100%;">
						<tr>
							<td class="PetiteCategorieCompetence" width="50%"><?php if($LangueAffichage=="FR"){echo "Question";}else{echo "Question";}?></td>
							<td class="PetiteCategorieCompetence" width="15%"><?php if($LangueAffichage=="FR"){echo "Type de réponse";}else{echo "Type of answer";}?></td>
							<td class="PetiteCategorieCompetence" width="15%" align="center"><?php if($LangueAffichage=="FR"){echo "Document";}else{echo "Document";}?></td>
							<td class="PetiteCategorieCompetence" width="5%">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_Document'];?>','0');">
									<img src="../../Images/Ajout.gif" style="border:0;" alt="<?php if($LangueAffichage=="FR"){echo "Ajouter une question en cette langue pour ce document";}else{echo "Add a question in this language for this document";}?>">
								</a>
							</td>
							<td class="PetiteCategorieCompetence" width="5%">
								<a class="Modif" href="javascript:OuvreFenetreAjoutQuestionnaire('<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_Document'];?>');">
									<img src="../../Images/formulaire.gif" style="border:0;" alt="<?php if($LangueAffichage=="FR"){echo "Ajouter un questionnaire complet pour ce document";}else{echo "Add a complete questionnaire for this multiple document";}?>">
								</a>
							</td>
						</tr>
						<?php 
						$resultQuestion=mysqli_query($bdd,"SELECT Id, Id_Document_Langue, Libelle, Fichier, TypeReponse FROM form_document_langue_question WHERE Id_Document_Langue=".$_GET['Id']." AND Suppr=0 ORDER BY Id");
						$nbQuestion=mysqli_num_rows($resultQuestion);
						$Couleur="#EEEEEE";
						if($nbQuestion>0)
						{
							while($rowQuestion=mysqli_fetch_array($resultQuestion))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td class="PetitCompetence">
										<?php echo $rowQuestion['Libelle'];?>
									</td>
									<td class="PetitCompetence">
										<?php 
											if($LangueAffichage=="FR"){
												echo $rowQuestion['TypeReponse'];
											}
											else{
												if($rowQuestion['TypeReponse']=="Oui/Non"){echo "Yes/No";}
												elseif($rowQuestion['TypeReponse']=="Note (1 à 6)"){echo "Note (1 to 6)";}
												elseif($rowQuestion['TypeReponse']=="Texte facultatif"){echo "Optional text";}
												elseif($rowQuestion['TypeReponse']=="Texte obligatoire"){echo "Mandatory text";}
											}
										?>
									</td>
									<td class="PetitCompetence" align='center'>
										<?php
											if($rowQuestion['Fichier']!="")
											{
												echo "<a class=\"Info\" href=\"".$DirFichier.$_GET['Id_Document']."/".$rowQuestion['Id_Document_Langue']."/".$rowQuestion['Fichier']."\" target=\"_blank\"><img src='../../Images/image.png' border='0'></a>";
											}
										?>
									</td>
									<td>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif', '<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_Document'];?>','<?php echo $rowQuestion['Id']; ?>');">
											<img src="../../Images/Modif.gif" border="0" alt="<?php if($LangueAffichage=="FR"){echo "Modification";}else{echo "Change";}?>">
										</a>
									</td>
									<td>
										<a class="Modif" href="javascript:OuvreFenetreModif('Suppr', '<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_Document'];?>','<?php echo $rowQuestion['Id']; ?>');">
											<img src="../../Images/Suppression.gif" border="0" alt="<?php if($LangueAffichage=="FR"){echo "Suppression";}else{echo "Suppression";}?>">
										</a>
									</td>
								</tr>
						<?php
							}
						}
						?>
					</table>
				</td>
			</tr>
			<?php 
			}
			?>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE form_document_langue SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".Date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
<script>Lister_Reponses();</script>
</body>
</html>