<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("../Database_fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un QCM</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript">
		function OuvreFenetreModif(Mode,Id_QCM,Id)
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
				var w=window.open("Ajout_QCM_Langue.php?Mode="+Mode+"&Id_QCM="+Id_QCM+"&Id="+Id,"PageQCMLangue","status=no,menubar=no,width=1200,height=650,scrollbars=1");
				w.focus();
			}
		}
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Code.value==''){alert('Vous n\'avez pas renseigné le code.');return false;}
				else if(formulaire.Nb_Question.value==''){alert('Vous n\'avez pas renseigné le nombre de question à sortir.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Code.value==''){alert('You did not fill in the code.');return false;}
				else if(formulaire.Nb_Question.value==''){alert('You did not fill in the number of questions to leave.');return false;}
				else{return true;}
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
//RECUPERATION VARIABLES FICHIERS
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
if($_POST){$DirFichier=$CheminFormation."QCM/".$_POST['Id']."/";}
else{$DirFichier=$CheminFormation."QCM/".$_GET['Id']."/";}
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if(!file_exists($DirFichier))
{
	$res=mkdir_ftp($DirFichier,0773);
	if(!$res){echo 'Echec lors de la création des répertoires...';}
}


function verificationNbDeQuestions($Id_QCM, $Nb_Question)
{
  //Un QCM qui ne possède pas assez de questions doit être en mode brouillon
  $req = "
    SELECT
      form_qcm_langue.Id,
      COUNT(form_qcm_langue_question.Id) AS NB_Questions_contenu
    FROM
      form_qcm_langue,
      form_qcm_langue_question
    WHERE
      form_qcm_langue.Id = form_qcm_langue_question.Id_QCM_Langue
      AND form_qcm_langue.Id_QCM = ".$Id_QCM."
	  AND form_qcm_langue.Suppr=0
	  AND form_qcm_langue_question.Suppr=0
    GROUP BY
      form_qcm_langue.Id;
  ";

  $res = getRessource($req);
  $arr = Array();
    
  while($r = mysqli_fetch_array($res)){
		if($r['NB_Questions_contenu'] < $Nb_Question){
		  array_push($arr, $r['Id']);
		}
	}
            
      $IdsQCM_Brouillon = implode(',', $arr);
      if($IdsQCM_Brouillon <> "") {
        $req = "
          UPDATE form_qcm_langue SET Brouillon = 1
          WHERE Id IN (".$IdsQCM_Brouillon.");
        ";
                
        getRessource($req); 
      }
}

if($_POST)
{
	$requete="";
	if($_POST['Mode']=="Ajout")
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_qcm WHERE Suppr=0 AND Code='".addslashes($_POST['Code'])."'"))==0)
		{
			$requete="INSERT INTO form_qcm (Code, Id_Client, Nb_Question, Id_QCM_Lie,Id_Personne_MAJ,Date_MAJ,Fichier)";
			$requete.=" VALUES (";
			$requete.="'".$_POST['Code']."'";
			$requete.=",".$_POST['Client'];
			$requete.=",".$_POST['Nb_Question'];
			$requete.=",".$_POST['Id_QCM_Lie'];
			$requete.=",".$IdPersonneConnectee;
			$requete.=",'".date('Y-m-d')."'";
			$requete.=",'##FICHIER##'";
			$requete.=")";
		}
	}
	else	//Mode modification
	{

		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}

		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_qcm WHERE Suppr=0 AND Code='".$_POST['Code']."' AND Id!=".$_POST['Id']))==0)
		{
			$requete="UPDATE form_qcm SET";
			$requete.=" Code='".$_POST['Code']."'";
			$requete.=", Id_Client=".$_POST['Client'];
			$requete.=", Nb_Question=".$_POST['Nb_Question'];
			$requete.=", Id_QCM_Lie=".$_POST['Id_QCM_Lie'];
			$requete.=", Id_Personne_MAJ=".$IdPersonneConnectee;
			$requete.=", Fichier='##FICHIER##'";
			$requete.=", Date_MAJ='".date('Y-m-d')."'";
			$requete.=" WHERE Id=".$_POST['Id'];
		}
	}
	
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
	
	if($requete!="")
	{
		$result=mysqli_query($bdd,str_replace("##FICHIER##",$Fichier,$requete));
		echo str_replace("##FICHIER##",$Fichier,$requete);
		verificationNbDeQuestions($_POST['Id'], $_POST['Nb_Question']);
		if($_POST['Mode']=="Ajout")
		{
			//Création du répertoire pour la gestion des fichiers joints des questions des QCM
			$res = mkdir_ftp($CheminFormation."QCM/".mysqli_insert_id($bdd), 0773);
			if(!$res){echo 'Echec lors de la création des répertoires...';}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Ce code existe déjà.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0'){
			$Modif=true;
			$result=mysqli_query($bdd,"SELECT Id, Code, Id_Client, Nb_Question, Id_QCM_Lie, Fichier FROM form_qcm WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}

Ecrire_Code_JS_Init_Date();
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_QCM.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle">Code : </td>
				<td>
					<input name="Code" size="50" value="<?php if($Modif){echo $row['Code'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Client";}else{echo "Client";}?> : </td>
				<td>
					<select name="Client">
						<option value='0' selected></option>
						<?php
						$resultClient=mysqli_query($bdd,"SELECT Id, Libelle, Suppr FROM form_client");
						while($rowClient=mysqli_fetch_array($resultClient))
						{
							$bAjout=0;
							if($Modif){if($rowClient['Id']==$row['Id_Client'] || $rowClient['Suppr']==0){$bAjout=1;}}
							else{if($rowClient['Suppr']==0){$bAjout=1;}}
							if($bAjout==1){
								echo "<option value='".$rowClient['Id']."'";
								if($Modif){if($rowClient['Id']==$row['Id_Client']){echo " selected";}}
								echo ">".stripslashes($rowClient['Libelle'])."</option>\n";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "QCM lié";}else{echo "Linked MCQ";}?> : </td>
				<td>
					<select name="Id_QCM_Lie">
						<option value='0'></option>
						<?php
						$resultQCMLie=mysqli_query($bdd,"SELECT Id, Code FROM form_qcm WHERE Suppr=0");
						while($rowQCMLie=mysqli_fetch_array($resultQCMLie))
						{
							echo "<option value='".$rowQCMLie['Id']."'";
							if($Modif){if($rowQCMLie['Id']==$row['Id_QCM_Lie']){echo " selected";}}
							echo ">".$rowQCMLie['Code']."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Nombre de question";}else{echo "Number of question";}?> : </td>
				<td>
					<select name="Nb_Question">
						<?php
						for($i=0;$i<=50;$i++)
						{
							echo "<option value='".$i."'";
							if($Modif){if($i==$row['Nb_Question']){echo " selected";}}
							echo ">".$i."</option>\n";
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
				if($Modif && $row['Fichier']!="")
				{
				?>
				<td>
					<a class="Info" target="_blank" href="Docs/QCM/<?php echo $row['Id'];?>/<?php echo $row['Fichier'];?>"><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['Fichier'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
			<tr>
				<td colspan=2 align="center">
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
			<!-- Gestion des différentes langues  -->
			<tr>
				<td colspan=3>
					<table class="ProfilCompetence" style="width:100%;">
						<tr>
							<td class="PetiteCategorieCompetence" width="20%"><?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";}?></td>
							<td class="PetiteCategorieCompetence" width="45%"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
							<td class="PetiteCategorieCompetence" width="10%"><?php if($LangueAffichage=="FR"){echo "Brouillon";}else{echo "Draft";}?></td>
							<td class="PetiteCategorieCompetence" width="15%"><?php if($LangueAffichage=="FR"){echo "Mis à jour par";}else{echo "Update By";}?></td>
							<td class="PetiteCategorieCompetence" width="15%"><?php if($LangueAffichage=="FR"){echo "Mis à jour le";}else{echo "Updated on";}?></td>
							<td class="PetiteCategorieCompetence" colspan=2 width="5%" align="right">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','<?php echo $_GET['Id'];?>','0');">
									<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une langue au QCM">
								</a>
							</td>
						</tr>
						<?php 
						$reqLangue="SELECT Id, Id_Langue, Brouillon, (SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue, Libelle, Date_MAJ, ";
						$reqLangue.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_qcm_langue.Id_Personne_MAJ) AS Personne ";
						$reqLangue.="FROM form_qcm_langue WHERE Id_QCM=".$_GET['Id']." AND Suppr=0";
						$resultLangue=mysqli_query($bdd,$reqLangue);
						$nbLangue=mysqli_num_rows($resultLangue);
						$Couleur="#EEEEEE";
						if($nbLangue>0)
						{
							while($rowLangue=mysqli_fetch_array($resultLangue))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td class="PetitCompetence"><?php echo $rowLangue['Langue'];?></td>
									<td class="PetitCompetence"><?php echo $rowLangue['Libelle'];?></td>
									<td class="PetitCompetence">
										<?php 
											if($rowLangue['Brouillon']==0){
												if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}
											}
											else{
												if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}
											}
										?>
									</td>
									<td class="PetitCompetence"><?php echo $rowLangue['Personne'];?></td>
									<td class="PetitCompetence"><?php echo AfficheDateJJ_MM_AAAA($rowLangue['Date_MAJ']);?></td>
									<td>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif', '<?php echo $_GET['Id'];?>','<?php echo $rowLangue['Id']; ?>');">
											<img src="../../Images/Modif.gif" border="0" alt="Modification">
										</a>
									</td>
									<td>
										<a class="Modif" href="javascript:OuvreFenetreModif('Suppr', '<?php echo $_GET['Id'];?>','<?php echo $rowLangue['Id']; ?>');">
											<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
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
		$result=mysqli_query($bdd,"UPDATE form_qcm SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>