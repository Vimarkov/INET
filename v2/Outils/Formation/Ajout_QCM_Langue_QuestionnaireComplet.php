<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - QCM - Langue - Questionnaire complet</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function ModifierCoeffPossible(i){
			if(document.getElementById('Type_'+i).value=='Facultatif'){
				document.getElementById('Coefficient_'+i).options.length = 0;
				document.getElementById('Coefficient_'+i).options[document.getElementById('Coefficient_'+i).options.length]=new Option('1','1');
			}
			else{
				document.getElementById('Coefficient_'+i).options.length = 0;
				document.getElementById('Coefficient_'+i).options[document.getElementById('Coefficient_'+i).options.length]=new Option('1','1');
				document.getElementById('Coefficient_'+i).options[document.getElementById('Coefficient_'+i).options.length]=new Option('2','2');
				document.getElementById('Coefficient_'+i).options[document.getElementById('Coefficient_'+i).options.length]=new Option('3','3');
			}
		}
	</script>
</head>
<body>

<?php
if($_POST){$DirFichier=$CheminFormation."QCM/".$_POST['Id_QCM']."/".$_POST['Id_QCM_Langue']."/";}
else{$DirFichier=$CheminFormation."QCM/".$_GET['Id_QCM']."/".$_GET['Id_QCM_Langue']."/";}

if(!file_exists ($DirFichier))
{
	$res=mkdir_ftp($DirFichier,0773);
	if(!$res)
	{
		if($LangueAffichage=="FR"){echo 'Echec lors de la création des répertoires...';}
		else{echo 'Failed to create directories...';}
	}
}

if($_POST)
{
	$k=1;
	for($i=1;$i<=$_POST['Nb_Question'];$i++)
	{
		if($_POST['Libelle_'.$i]!="")
		{
			$SrcProblem="";
			$Problem=0;
			$FichierTransfert=0;
			
			if(isset($_POST['fichieractuel_'.$i])){$Fichier=$_POST['fichieractuel_'.$i];}
			else{$Fichier="";}
			
			$requeteInsert="INSERT INTO form_qcm_langue_question (Id_Origine, Id_QCM_Langue, Coefficient, Type, Libelle, Fichier, Id_Personne_MAJ, Date_MAJ,Num)";
			$requeteInsert.=" VALUES (";
			$requeteInsert.="0";
			$requeteInsert.=",".$_POST['Id_QCM_Langue'];
			$requeteInsert.=",".$_POST['Coefficient_'.$i];
			$requeteInsert.=",'".$_POST['Type_'.$i]."'";
			$requeteInsert.=",'".addslashes($_POST['Libelle_'.$i])."',";
			$requeteInsert.="'##FICHIER##'";
			$requeteInsert.=",".$_POST['Id_Personne_MAJ'];
			$requeteInsert.=",'".$_POST['Date_MAJ']."'";
			$requeteInsert.=",".$k."";
			$requeteInsert.=")";
			
			if($Problem==1){
				if($LangueAffichage=="FR"){echo "<script>alert('Il y a eu une erreur lors de la suppression de l ancien du fichier joint (".$SrcProblem.");</script>";}
				else{echo "<script>alert('There was an error deleting the old one from the attached file (".$SrcProblem.");</script>";}
			}
			else{
				//****TRANSFERT FICHIER****
				if($_FILES['fichier_'.$i]['name']!="")
				{
					$tmp_file=$_FILES['fichier_'.$i]['tmp_name'];
					if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
					else
					{
						//On vérifie la taille du fichiher
						if(filesize($_FILES['fichier_'.$i]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
						{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
						else
						{
							// on copie le fichier dans le dossier de destination
							$name_file=$_FILES['fichier_'.$i]['name'];
							$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
							while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
							if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
							{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
							else{$Fichier=$name_file;$FichierTransfert=1;}
						}
					}
				}	
				if($Problem==1){
					if($LangueAffichage=="FR"){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
					else{echo "<script>alert('There was an error deleting the old one from the attached file (".$SrcProblem."). Please check if it is added in what you have just created.');</script>";}
				}
				else
				{
					$resultInsert=mysqli_query($bdd,str_replace("##FICHIER##",$Fichier,$requeteInsert));
					$k++;
					//Ajout des réponses
					$Id_Question_Ajoutee=mysqli_insert_id($bdd);
					for($j=1;$j<=3;$j++){
						$FichierReponse="";
						$requeteInsert="INSERT INTO form_qcm_langue_question_reponse (Id_Origine, Id_QCM_Langue_Question,Num, Libelle, Valeur, Id_Personne_MAJ, Date_MAJ,Fichier)";
						$requeteInsert.=" VALUES (";
						$requeteInsert.="0";
						$requeteInsert.=",".$Id_Question_Ajoutee;
						$requeteInsert.=",".$_POST['ReponseNum_'.$i.'_'.$j];
						$requeteInsert.=",'".addslashes($_POST['ReponseLibelle_'.$i.'_'.$j])."'";
						$requeteInsert.=",".$_POST['ReponseValeur_'.$i.'_'.$j];
						$requeteInsert.=",".$_POST['Id_Personne_MAJ'];
						$requeteInsert.=",'".$_POST['Date_MAJ']."'";
						$requeteInsert.=",'##FICHIER##'";
						$requeteInsert.=")";
						
						//****TRANSFERT FICHIER****
						if($_FILES['fichierReponse_'.$i.'_'.$j]['name']!=""){
							$tmp_file=$_FILES['fichierReponse_'.$i.'_'.$j]['tmp_name'];
							if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
							else
							{
								//On vérifie la taille du fichiher
								if(filesize($_FILES['fichierReponse_'.$i.'_'.$j]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
								{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
								else
								{
									// on copie le fichier dans le dossier de destination
									$name_file=$_FILES['fichierReponse_'.$i.'_'.$j]['name'];
									$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
									while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
									if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
									{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
									else{$FichierReponse=$name_file;$FichierTransfert=1;}
								}
							}
						}
						if($Problem==1){
							if($LangueAffichage=="FR"){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
							else{echo "<script>alert('There was an error while copying the attached file (".$SrcProblem."). Please check if it is added in what you have just created.');</script>";}
						}
						else
						{
							$resultInsert=mysqli_query($bdd,str_replace("##FICHIER##",$FichierReponse,$requeteInsert));
						}
					}
					echo "<script>FermerEtRecharger();</script>";
				}
			}
		}
	}
}
elseif($_GET)
{
	$Requete="SELECT Nb_Question FROM form_qcm WHERE Id=".$_GET['Id_QCM'];
	$Result=mysqli_query($bdd,$Requete);
	$Row=mysqli_fetch_array($Result);
	$Nb_Question=$Row['Nb_Question'];
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_QCM_Langue_QuestionnaireComplet.php">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Id_QCM_Langue" value="<?php echo $_GET['Id_QCM_Langue'];?>">
		<input type="hidden" name="Id_QCM" value="<?php echo $_GET['Id_QCM'];?>">
		<input type="hidden" name="Id_Personne_MAJ" value="<?php echo $IdPersonneConnectee;?>">
		<input type="hidden" name="Date_MAJ" value="<?php echo date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));?>">
		<input type="hidden" name="Nb_Question" value="<?php echo $Nb_Question;?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr>
				<td class="Libelle">Coeff</td>
				<td class="Libelle">Type</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
				<td class="PoliceModif Libelle"><?php if($LangueAffichage=="FR"){echo "Image";}else{echo "Picture";}?></td>
			</tr>
			<?php 
			$couleur="#c6c6c6";
			for($i=1;$i<=$Nb_Question;$i++){
				if($couleur=="#eeeeee"){$couleur="#c6c6c6";}
				else{$couleur="#eeeeee";}
			?>
			<tr class="TitreColsUsers" bgcolor="<?php echo $couleur;?>">
				<td class="Libelle">
					<?php echo $i;?>
				</td>
				<td>
					<select name="Coefficient_<?php echo $i;?>" id="Coefficient_<?php echo $i;?>">
						<?php
						for($j=1;$j<=3;$j++){
							echo "<option value='".$j."'>".$j."</option>\n";
						}
						?>
					</select>
				</td>
				<td>
					<select name="Type_<?php echo $i;?>" id="Type_<?php echo $i;?>" onchange="ModifierCoeffPossible(<?php echo $i;?>)">
						<?php
						$Tableau=array('Obligatoire','Facultatif');
						foreach($Tableau as $indice => $valeur){
							echo "<option value='".$valeur."'>".$valeur."</option>\n";
						}
						?>
					</select>
				</td>
				<td>
					<textarea name="Libelle_<?php echo $i;?>" rows="2" cols="40" style="resize:none;"></textarea>
				</td>
				<td><input name="fichier_<?php echo $i;?>" type="file"></td>
				<td valign="top">
					<table>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Réponse";}else{echo "Answer";}?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Valeur";}else{echo "Value";}?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Image";}else{echo "Picture";}?></td>
						</tr>
						<?php 
						for($j=1;$j<=3;$j++){
						?>
						<tr>
							<td>
								<input onKeyUp="nombre(this)" name="<?php echo "ReponseNum_".$i."_".$j;?>" id="<?php echo "ReponseNum_".$i."_".$j;?>" style="width:40px" value="<?php echo $j;?>">
							</td>
							<td>
								<textarea name="<?php echo "ReponseLibelle_".$i."_".$j;?>" rows="2" cols="40" style="resize:none;"></textarea>
							</td>
							<td>
								<select name="<?php echo "ReponseValeur_".$i."_".$j;?>">
									<?php
									$Tableau=array('0','1');
									foreach($Tableau as $indice => $valeur)
									{
										echo "<option value='".$valeur."'>".$valeur."</option>\n";
									}
									?>
								</select>
							</td>
							<td><input name="fichierReponse_<?php echo $i."_".$j;?>" type="file"></td>
						</tr>
						<?php 
						}
						?>
					</table>
				</td>
			</tr>
			<?php 
			}
			?>
			<tr>
				<td colspan=10 align="center">
					<input class="Bouton" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}?>>
				</td>
			</tr>
		</table>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>