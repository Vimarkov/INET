<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un QCM - Langue</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript">
		function OuvreFenetreModif(Mode,Id_QCM_Langue,Id_QCM,Id)
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
				var w=window.open("Ajout_QCM_Langue_Question.php?Mode="+Mode+"&Id_QCM_Langue="+Id_QCM_Langue+"&Id_QCM="+Id_QCM+"&Id="+Id,"PageQCMLangueQuestion","status=no,menubar=no,width=520,height=250");
				w.focus();
			}
		}

		function OuvreFenetreAjoutQuestionnaire(Id_QCM_Langue,Id_QCM,Id)
		{
			var w=window.open("Ajout_QCM_Langue_QuestionnaireComplet.php?&Id_QCM_Langue="+Id_QCM_Langue+"&Id_QCM="+Id_QCM,"PageQCMLangueQuestionnaireComplet","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
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
		function afficherIMG(img){
			var w=open("",'image','weigth=toolbar=no,scrollbars=no,resizable=yes, width=810, height=310');	
			w.document.write("<HTML><BODY onblur=\"window.close();\"><IMG src='"+img+"'>");
			w.document.write("</BODY></HTML>");
			w.focus();
			w.document.close();
		}
		function Lister_Reponses(){
			if(document.getElementById('Langue').value=="FR"){
				Lister_Dependances('Table_Questions','Liste_Reponses','Id_QCM_Langue_Question|N°|Réponse|Valeur|Image','0|20|550|20','Affichage_Liste_Reponses','Ajout_QCM_Langue_Question_Reponse','720','250','Liste des réponses');
			}
			else{
				Lister_Dependances('Table_Questions','Liste_Reponses','Id_QCM_Langue_Question|N°|Answer|Value|Picture','0|20|550|20','Affichage_Liste_Reponses','Ajout_QCM_Langue_Question_Reponse','720','250','List of answers');
			}
		}
	</script>
</head>
<body>

<?php
Ecrire_Code_JS_Init_Date();
$DirFichier="https://extranet.aaa-aero.com/v2/Outils/Formation/Docs/QCM/";
if($_POST)
{
	$requete="";
	if($_POST['Mode']=="Ajout")
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_qcm_langue WHERE Id_QCM='".$_POST['Id_QCM']."' AND Suppr=0 AND Id_Langue='".$_POST['Id_Langue']."'"))==0)
		{
			$requete="INSERT INTO form_qcm_langue (Id_QCM, Id_Langue, Libelle,Date_MAJ,Id_Personne_MAJ,Brouillon)";
			$requete.=" VALUES (";
			$requete.=$_POST['Id_QCM'];
			$requete.=",".$_POST['Id_Langue'];
			$requete.=",'".addslashes($_POST['Libelle'])."'";
			$requete.=",'".TrsfDate_($_POST['Date_MAJ'])."'";
			$requete.=",".$_POST['Id_Personne_MAJ']."";
			$requete.=",".$_POST['Brouillon']."";
			$requete.=")";
		}
	}
	else	//Mode modification
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_qcm_langue WHERE Id_QCM='".$_POST['Id_QCM']."' AND Suppr=0  AND Id_Langue='".$_POST['Id_Langue']."' AND Id!='".$_POST['Id']."'"))==0)
		{
			$requete="UPDATE form_qcm_langue SET";
			$requete.=" Libelle='".addslashes($_POST['Libelle'])."'";
			$requete.=", Date_MAJ='".TrsfDate_($_POST['Date_MAJ'])."'";
			$requete.=", Id_Personne_MAJ=".$_POST['Id_Personne_MAJ']."";
			$requete.=", Brouillon=".$_POST['Brouillon']."";
			$requete.=" WHERE Id=".$_POST['Id'];
		}
	}
	if($requete!="")
	{
		$result=mysqli_query($bdd,$requete);
		if($_POST['Mode']=="Ajout")
		{
			//Création du répertoire pour la gestion des fichiers joints des questions des QCM
			$res = mkdir_ftp($CheminFormation."QCM/".$_POST['Id_QCM']."/".mysqli_insert_id($bdd), 0773);
			if(!$res){echo 'Echec lors de la création des répertoires...';}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Cette langue existe déjà.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0'){
			$Modif=true;
			$result=mysqli_query($bdd,"SELECT Id, Id_QCM, Id_Langue, Libelle, Date_MAJ, Id_Personne_MAJ, Brouillon FROM form_qcm_langue WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_QCM_Langue.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" id="Id" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" id="Id_QCM" name="Id_QCM" value="<?php echo $_GET['Id_QCM'];?>">
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
						//A modifier avec la gestion des droits
						$reqUpdate="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom ";
						$reqUpdate.="FROM new_rh_etatcivil LEFT JOIN new_competences_personne_poste_plateforme ";
						$reqUpdate.="ON new_rh_etatcivil.Id=new_competences_personne_poste_plateforme.Id_Personne ";
						$reqUpdate.="WHERE Id_Poste IN(".implode(",",$TableauIdPostesRF_FORM_PS_RQP).") ORDER BY Nom, Prenom ";
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
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Brouillon";}else{echo "Draft";}?> : </td>
				<td>
					<select name="Brouillon">
						<option value="0" <?php if($Modif){if($row['Brouillon']==0){echo "selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($Modif){if($row['Brouillon']==1){echo "selected";}}else{echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					</select>
				</td>
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
			<!-- Gestion des questions  -->
			<tr>
				<td colspan="2" valign="top">
					<table>
						<tr>
							<td valign="top" width="50%">
								<table class="ProfilCompetence" id="Table_Questions" style="width:100%;">
									<tr>
										<td class="PetiteCategorieCompetence" width="10%" align="center">N°</td>
										<td class="PetiteCategorieCompetence" width="50%"><?php if($LangueAffichage=="FR"){echo "Question";}else{echo "Question";}?></td>
										<td class="PetiteCategorieCompetence" width="5%"><?php if($LangueAffichage=="FR"){echo "Coefficient";}else{echo "Coefficient";}?></td>
										<td class="PetiteCategorieCompetence" width="10%" align="center"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
										<td class="PetiteCategorieCompetence" width="15%" align="center"><?php if($LangueAffichage=="FR"){echo "Image";}else{echo "Picture";}?></td>
										<td class="PetiteCategorieCompetence" width="5%">
											<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_QCM'];?>','0');">
												<img src="../../Images/Ajout.gif" style="border:0;" alt="<?php if($LangueAffichage=="FR"){echo "Ajouter une question en cette langue pour ce QCM";}else{echo "Add a question in this language for this choice";}?>">
											</a>
										</td>
										<td class="PetiteCategorieCompetence" width="5%">
											<a class="Modif" href="javascript:OuvreFenetreAjoutQuestionnaire('<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_QCM'];?>');">
												<img src="../../Images/formulaire.gif" style="border:0;" alt="<?php if($LangueAffichage=="FR"){echo "Ajouter un questionnaire complet pour ce QCM";}else{echo "Add a complete questionnaire for this multiple choice questionnaire";}?>">
											</a>
										</td>
									</tr>
									<?php 
									$resultQuestion=mysqli_query($bdd,"SELECT Id, Id_QCM_Langue, Coefficient, Type, Libelle, Fichier,Num FROM form_qcm_langue_question WHERE Id_QCM_Langue=".$_GET['Id']." AND Suppr=0 ORDER BY Num DESC");
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
													<input onclick="Lister_Reponses();" type="radio" name="QuestionSelect" value="<?php echo $rowQuestion['Id'];?>">
													<?php echo $rowQuestion['Num'];?>
												</td>
												<td class="PetitCompetence">
													<?php echo stripslashes($rowQuestion['Libelle']);?>
												</td>
												<td class="PetitCompetence"><?php echo $rowQuestion['Coefficient'];?></td>
												<td class="PetitCompetence"><?php echo $rowQuestion['Type'];?></td>
												<td class="PetitCompetence" align='center'>
													<?php
														if($rowQuestion['Fichier']!="")
														{
															echo "<img onclick=\"afficherIMG('".$DirFichier.$_GET['Id_QCM']."/".$_GET['Id']."/".$rowQuestion['Fichier']."')\" src='../../Images/image.png' border='0'>";
														}
													?>
												</td>
												<td>
													<a class="Modif" href="javascript:OuvreFenetreModif('Modif', '<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_QCM'];?>','<?php echo $rowQuestion['Id']; ?>');">
														<img src="../../Images/Modif.gif" border="0" alt="<?php if($LangueAffichage=="FR"){echo "Modification";}else{echo "Change";}?>">
													</a>
												</td>
												<td>
													<a class="Modif" href="javascript:OuvreFenetreModif('Suppr', '<?php echo $_GET['Id'];?>','<?php echo $_GET['Id_QCM'];?>','<?php echo $rowQuestion['Id']; ?>');">
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
							<td valign="top" style="width:50%;">
								<!-- Gestion des réponses -->
								<div id="Affichage_Liste_Reponses">
								</div>
								<?php
								$requeteReponses="SELECT Id, Id_QCM_Langue_Question, Libelle, Valeur, Fichier, Num FROM form_qcm_langue_question_reponse WHERE Suppr=0 ORDER BY Num";
								$resultReponses=mysqli_query($bdd,$requeteReponses);
								$i=0;
								$Liste_Reponses="";
								while ($rowReponses=mysqli_fetch_array($resultReponses))
								{
									$fichier="";
									if($rowReponses['Fichier']!=""){
										$fichier = "<img onclick=\"afficherIMG('".$DirFichier.$_GET['Id_QCM']."/".$_GET['Id']."/".$rowReponses['Fichier']."')\" src='../../Images/image.png' border='0'>";
									}
									$Liste_Reponses.=$rowReponses['Id']."|".$rowReponses['Id_QCM_Langue_Question']."|".$rowReponses['Num']."|".stripslashes($rowReponses['Libelle'])."|".$rowReponses['Valeur']."|".$fichier."µ";
								}
								?>
								<input type="hidden" id="Liste_Reponses" value="<?php echo str_replace("\"","",$Liste_Reponses);?>">
							</td>
						</tr>
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
		$result=mysqli_query($bdd,"UPDATE form_qcm_langue SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".Date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
<script>Lister_Reponses();</script>
</body>
</html>