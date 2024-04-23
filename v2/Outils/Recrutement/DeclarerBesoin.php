<?php
require("../../Menu.php");
require("Fonction_Recrutement.php");
?>
<script type="text/javascript">
	function nombre(champ){
		var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
		var verif;
		var points = 0; /* Supprimer cette ligne */

		for(x = 0; x < champ.value.length; x++)
		{
		verif = chiffres.test(champ.value.charAt(x));
		if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
		if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
		if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
		}
	}
</script>
<?php
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$bEnregistrement=false;

//RECUPERATION VARIABLES FICHIERS
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
$DirFichier="Documents/";
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST){
	if($_SESSION['Id_Personne']<>""){
		$req="SELECT Id_Plateforme, Id_Domaine, Programme FROM new_competences_prestation WHERE Id=".$_POST['Id_Prestation'];
		$resultsite=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($resultsite);
		$programme="";
		$Id_Domaine=0;
		$Id_Plateforme=0;
		$OuvertureAutresPlateformes=0;
		if($nbenreg>0)
		{
			$rowsite=mysqli_fetch_array($resultsite);
			$programme=$rowsite['Programme'];
			$Id_Domaine=$rowsite['Id_Domaine'];
			$Id_Plateforme=$rowsite['Id_Plateforme'];
		}
		if($_POST['posteDefinitif']==1){$OuvertureAutresPlateformes=1;}
		
		$requete="INSERT INTO recrut_annonce 
			(Id_Demandeur,DateDemande,Id_Prestation,Id_Domaine,Programme,Lieu,Metier,
			Nombre,DateBesoin,PosteDefinitif,Duree,CreationPoste,Id_TypeHoraire,Id_CategorieProfessionnelle,Horaire,DescriptifPoste,SavoirFaire,SavoirEtre,Langue,Prerequis,CategorieProf,IGD,Salaire,MotifDemande,OuvertureAutresPlateformes,Division,DemandePSE) 
			VALUES 
			(".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',".$_POST['Id_Prestation'].",".$Id_Domaine.",'".$programme."','".addslashes($_POST['lieu'])."','".addslashes($_POST['metier'])."',
			".$_POST['nombr'].",'".TrsfDate_($_POST['dateSouhaitee'])."',".$_POST['posteDefinitif'].",'".addslashes($_POST['duree'])."',".$_POST['etatPoste'].",
			".$_POST['Id_TypeHoraire'].",".$_POST['categorie'].",'".addslashes($_POST['horaire'])."',
			'".addslashes($_POST['DescriptifPoste'])."','".addslashes($_POST['savoirfaire'])."','".addslashes($_POST['savoiretre'])."','".addslashes($_POST['Langues'])."','".addslashes($_POST['Prerequis'])."','".addslashes($_POST['categorieProfessionnelle'])."','".addslashes($_POST['IGD'])."','".addslashes($_POST['salaire'])."','".addslashes($_POST['MotifDemande'])."',".$OuvertureAutresPlateformes.",'".addslashes($_POST['division'])."',".$_POST['demandePSE'].") ";
		$result=mysqli_query($bdd,$requete);
		$IdCree = mysqli_insert_id($bdd);
		$bEnregistrement=true;
		
		if($IdCree>0){
			$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
			$result=mysqli_query($bdd,$req);
			$nbenreg=mysqli_num_rows($result);
			if($nbenreg>0)
			{
				while($rowSE=mysqli_fetch_array($result))
				{
					if(isset($_POST['savoiretres_'.$rowSE['Id']])){
						$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$IdCree.",".$rowSE['Id'].") ";
						$resultAdd=mysqli_query($bdd,$req);
					}
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
			if($Problem==0){

				$requeteUpt="UPDATE recrut_annonce SET";
				$requeteUpt.=" FicheMetier='".$Fichier."'";
				$requeteUpt.=" WHERE Id=".$IdCree;
				$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		
		if(DroitsPrestation(array($IdPosteResponsableOperation),$_POST['Id_Prestation']) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteResponsablePlateforme))){
			$requeteUpdate="UPDATE recrut_annonce SET 
						Id_Validateur=".$_SESSION['Id_Personne'].",
						DateValidation='".date('Y-m-d')."',
						EtatValidation=1
						WHERE Id=".$IdCree." ";
				$resultat=mysqli_query($bdd,$requeteUpdate);
		}
		elseif(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
			//17=Siège social
			$requeteUpdate="UPDATE recrut_annonce SET 
						Id_Validateur=".$_SESSION['Id_Personne'].",
						DateValidation='".date('Y-m-d')."',
						EtatValidation=1,
						Id_Approbation=".$_SESSION['Id_Personne'].",
						DateApprobation='".date('Y-m-d')."',
						EtatApprobation=1,
						OuvertureAutresPlateformes=".$_POST['deploiementOffre']."
						WHERE Id=".$IdCree." ";
				$resultat=mysqli_query($bdd,$requeteUpdate);
				
			creerMail("BESOIN INTERNE",$_SESSION['Langue'],$IdCree);
		}
	}
}
?>

<form id="formulaire" class="test" enctype="multipart/form-data" action="DeclarerBesoin.php" method="post" onsubmit=" return selectall();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<input type="hidden" name="RHParis" id="RHParis" value="<?php if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){echo 1;}else{echo 0;} ?>" />
	<tr>
		<td colspan="5">
			<table class ="GeneralPage" style="width:100%; border-spacing:0; background-color:#42d3d6;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Recrutement/Tableau_De_Bord.php'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclarer un besoin";}else{echo "Declare a need";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php 
		if($bEnregistrement==true){
			echo "<tr>";
			echo "<td colspan='5' align='center' bgcolor='#ff7777' style='font-weight:bold;'>";
			if($_SESSION["Langue"]=="FR"){
				echo "Demande créée";
			}
			else{
				echo "Application created";
			}
			echo "</td>";
			echo "</tr>
				<tr>
					<td height='5'></td>
				</tr>";
		}
	?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" align="center" width="99%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Division";}else{echo "Division";}?> : </td>
							<td width="18%" colspan="5">&nbsp;
								<select name="division" id="division">
									<option value=""></option>
									<option value="FAL">FAL</option>
									<option value="SUC">SUC</option>
									<option value="DIVISIONS">DIVISIONS</option>
								</select>
							</td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;<?php if($_SESSION['Id_Personne']<>1132 && $_SESSION['Id_Personne']<>4320){echo "display:none;";} ?>">&nbsp;<?php if($LangueAffichage=="FR"){echo "Demande PSE";}else{echo "PSE request";}?> : </td>
							<td width="18%" style="<?php if($_SESSION['Id_Personne']<>1132 && $_SESSION['Id_Personne']<>4320){echo "display:none;";} ?>">&nbsp;
								<select name="demandePSE" id="demandePSE">
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
									<option value="0"><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="10%">&nbsp;
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();" style="width:100px;">
									<?php
										if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27)

												AND Active=0
												ORDER BY Libelle ASC";
										}
										else{
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE 
												(Id IN 
													(SELECT Id_Prestation 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
													)
												OR Id_Plateforme IN 
													(SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
													)	
												)
												AND Active=0
												ORDER BY Libelle ASC";
										}
										
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option value='".$rowsite['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?> : </td>
							<td width="18%">&nbsp;
								<input style="width:200px" name="lieu" id="lieu" value=""/>
							</td>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?></td>
							<td width="20%">&nbsp;
								<input style="width:200px" name="metier" id="metier" value=""/>
								<input name="fichier" type="file" onChange="CheckFichier();">
							</td>
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre";}else{echo "Number";}?> : </td>
							<td width="25%">&nbsp;<input onKeyUp="nombre(this)" style="width:30px" name="nombr" id="nombr" value=""/></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date début souhaitée";}else{echo "Desired start date";}?> : </td>
							<td width="10%">&nbsp;<input type="date" id="dateSouhaitee" name="dateSouhaitee" size="10" value=""></td>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type de poste";}else{echo "Position type";}?> : </td>
							<td width="18%">&nbsp;
								<select name="posteDefinitif" id="posteDefinitif" onchange="afficherDureeV2();">
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Poste définitif";}else{echo "Definitive position";}?></option>
									<option value="0"><?php if($LangueAffichage=="FR"){echo "Mission";}else{echo "Mission";}?></option>
									<option value="2"><?php if($LangueAffichage=="FR"){echo "CDD 6 mois";}else{echo "CDD 6 mois";}?></option>
									<option value="3"><?php if($LangueAffichage=="FR"){echo "CDD 2 mois";}else{echo "CDD 2 mois";}?></option>
									<option value="4"><?php if($LangueAffichage=="FR"){echo "CDD";}else{echo "CDD";}?></option>
								</select>
							</td>
							<td id="duree1" class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;display:none;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Durée";}else{echo "Duration";}?> : </td>
							<td id="duree2" width="20%" style="display:none;">
								&nbsp;<input style="width:150px" name="duree" id="duree" value=""/>
							</td>
							
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type d'horaire";}else{echo "Schedule type";}?> : </td>
							<td width="25%">
								<table>
									<tr>
										<td style="white-space: nowrap;">
											&nbsp;<select name="Id_TypeHoraire" id="Id_TypeHoraire" style="width:100px;">
													<option value="0"></option>
												<?php
													$requete="SELECT Id, Libelle
													FROM recrut_typehoraire
													WHERE Suppr=0
													ORDER BY Libelle ASC";
													$results=mysqli_query($bdd,$requete);
													while($rows=mysqli_fetch_array($results))
													{
														echo "<option value='".$rows['Id']."'>";
														echo str_replace("'"," ",stripslashes($rows['Libelle']))."</option>\n";
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td style="white-space: nowrap;">
											&nbsp;<input  style="width:150px" name="horaire" id="horaire" value=""/>
										</td>
									</tr>
								</table>
							</td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Catégorie d’emploi, à titre indicatif :";}else{echo "Job category, for information only :";} ?></td>
							<td width="10%">&nbsp;
									<select name="categorie" id="categorie" style="width:200px;">
										<option value="0"></option>
									<?php
										$requete="SELECT Id, Libelle
											FROM recrut_categorieprofessionnelle
											WHERE Suppr=0
											ORDER BY Libelle ASC";
										
										$result=mysqli_query($bdd,$requete);
										while($row=mysqli_fetch_array($result))
										{
											echo "<option value='".$row['Id']."'>";
											echo str_replace("'"," ",stripslashes($row['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat du poste";}else{echo "Position status";}?> : </td>
							<td width="18%">
								&nbsp;<select name="etatPoste" id="etatPoste">
									<option value=""></option>
									<option value="0"><?php if($LangueAffichage=="FR"){echo "Création de poste";}else{echo "Job creation";}?></option>
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Poste vacant";}else{echo "Vacancy";}?></option>
								</select>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut";}else{echo "Status";}?> : </td>
							<td width="18%" >
								&nbsp;<select name="categorieProfessionnelle" id="categorieProfessionnelle">
									<option value=""></option>
									<option value="Agent de maitrise">Agent de maitrise</option>
									<option value="ART 4 BIS">ART 4 BIS</option>
									<option value="Cadre">Cadre</option>
									<option value="Employé">Employé</option>
									<option value="Ouvrier">Ouvrier</option>
									<option value="Technicien">Technicien</option>
								</select>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Salaire ";}else{echo "Salary ";}?> : </td>
							<td width="18%" >
								&nbsp;<input  style="width:150px" name="salaire" id="salaire" value=""/>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;display:none;">&nbsp;<?php if($LangueAffichage=="FR"){echo "IGD ";}else{echo "IGD ";}?> : </td>
							<td width="18%" style="display:none;">
								&nbsp;<select name="IGD" id="IGD">
									<option value=""></option>
									<option value="Oui">Oui</option>
									<option value="Non" selected>Non</option>
								</select>
							</td>
						</tr>
						<tr style="display:none;"><td height="4"></td></tr>
						<tr style="display:none;">
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Motif de la demande ";}else{echo "Reason for the request ";}?> : </td>
							<td width="18%"  colspan="5">
								&nbsp;<input  style="width:800px" name="MotifDemande" id="MotifDemande" value=""/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Descriptif du poste :";}else{echo "Job Description :";} ?></td>
							<td width="30%" colspan="3">
								&nbsp;<textarea name="DescriptifPoste" id="DescriptifPoste" cols="90" rows="8" style="resize:none;"></textarea>
							</td>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Diplômes :";}else{echo "Diplomas :";} ?></td>
							<td width="30%" colspan="3">
								&nbsp;<textarea name="Prerequis" id="Prerequis" cols="90" rows="8" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir faire,prérequis :<br><br>Qualités professionnelles<br><br>Polyvalence métier<br><br>Compétences techniques<br><br>Compétences managériales";}else{echo "Know-how, prerequisites:<br><br>Professional skills<br><br>-Experience<br><br>Business versatility<br><br>Technical skills<br><br>Managerial skills";} ?></td>
							<td width="30%" colspan="3" valign="top">
								&nbsp;<textarea name="savoirfaire" id="savoirfaire" cols="90" rows="12" style="resize:none;"></textarea>
							</td>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir être :";}else{echo "know how to be :";} ?></td>
							<td width="30%" colspan="3" valign="top">
								&nbsp;
								<table width="100%">
									<tr>
										<td width="50%" valign="top">
											<table width="100%">
												<?php
													$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
													$result=mysqli_query($bdd,$req);
													$nbenreg=mysqli_num_rows($result);
													if($nbenreg>0)
													{
														while($rowSE=mysqli_fetch_array($result))
														{
															echo "<tr><td>";
															echo"<input type='checkbox' class='savoiretres' name='savoiretres_".$rowSE['Id']."' value='".$rowSE['Id']."'>".stripslashes($rowSE['Libelle'])." ";
															echo "</td></tr>";
														}
													}
												?>
											</table>
										</td>
										<td width="50%" valign="top">
											<textarea name="savoiretre" id="savoiretre" cols="45" rows="12" style="resize:none;"></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Langues ";}else{echo "Languages ";}?> : </td>
							<td width="30%" colspan="6">
								&nbsp;<input  style="width:550px" name="Langues" id="Langues" value=""/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trDeploiement" style="display:none;">
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Déploiement de l'offre";}else{echo "Deployment of the offer";}?> : </td>
							<td width="18%">
								&nbsp;<select name="deploiementOffre" id="deploiementOffre">
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Autres unités d'exploitations";}else{echo "Other operating units";}?></option>
									<option value="0"><?php if($LangueAffichage=="FR"){echo "Interne";}else{echo "Internal";}?></option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr style="display:none;">
							<td class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
							<td colspan="5">
								
								<?php
									$requetePersonnePoste="SELECT DISTINCT Id_Prestation, Id_Poste, CONCAT(Nom,' ',Prenom) as NomPrenom, Id_Personne,(@row_number:=@row_number + 1) AS rnk ";
									$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
									$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
									$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Poste = ".$IdPosteResponsableOperation." 
															GROUP BY Id_Prestation, Id_Personne ";
									$requetePersonnePoste.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste ASC";
									
									$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
									$i=0;
									echo "<script>";
									while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste))
									{
										 echo "Liste_Poste_Prestation[".$i."] = new Array(".$rowPersonnePoste['Id_Prestation'].",".$rowPersonnePoste['Id_Poste'].",'".$rowPersonnePoste['NomPrenom']."',".$rowPersonnePoste['Id_Personne'].");\n";
										 $i+=1;
									}
									echo "</script>";
								?>
								<div id="PostesValidateurs">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="100"></td></tr>
</table>
</form>
<?php
	echo "<script>Recharge_Responsables();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>