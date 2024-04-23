<?php
if($_POST)
{
	if(isset($_POST['SupprimerAnnexe'])){
		//Vérifier si document existe déjà 
		$reqAnnexe="SELECT Annexe FROM soda_questionnaire WHERE Annexe<>'' AND Id=".$_SESSION['FiltreSODA_Questionnaire'];
		$resultAnnexe=mysqli_query($bdd,$reqAnnexe);
		$nbAnnexe=mysqli_num_rows($resultAnnexe);
		if($nbAnnexe>0){
			$rowAnnexe=mysqli_fetch_array($resultAnnexe);
			if(file_exists ("DocumentQCM/".$rowAnnexe['Annexe'])){
				//Supprimer le document
				unlink("DocumentQCM/".$rowAnnexe['Annexe']);	
			}
			$reqUpdateAttestation="UPDATE soda_questionnaire SET Annexe='' WHERE Id=".$_SESSION['FiltreSODA_Questionnaire'];
			$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
		}
	}
	if(isset($_POST['btn_Questionnaire'])){
		$actif=1;
		if(isset($_POST['actif'])){$actif=0;}
		$specifique=0;
		if(isset($_POST['specifique'])){$specifique=1;}
		$qAdditionnelle=0;
		if(isset($_POST['qAdditionnelle'])){$qAdditionnelle=1;}
		$nonAleatoire=0;
		if(isset($_POST['nonAleatoire'])){$nonAleatoire=1;}
		$requeteInsertUpdate="UPDATE soda_questionnaire SET";
		$requeteInsertUpdate.=" Actif=".$actif."";
		$requeteInsertUpdate.=",Specifique=".$specifique."";
		$requeteInsertUpdate.=",DateDerniereRevue='".TrsfDate_($_POST['dateDerniereRevue'])."'";
		$requeteInsertUpdate.=",NbQuestion=".unNombreSinon0($_POST['nbQuestion'])."";
		$requeteInsertUpdate.=",SeuilReussite=".unNombreSinon0($_POST['seuilQuestion'])."";
		$requeteInsertUpdate.=",AutoriserQuestionsAdditionnelles=".$qAdditionnelle."";
		$requeteInsertUpdate.=",NonAleatoire=".$nonAleatoire."";
		$requeteInsertUpdate.=",Id_Personne=".$IdPersonneConnectee."";
		$requeteInsertUpdate.=",DateMAJ='".date('Y-m-d')."'";
		$requeteInsertUpdate.=" WHERE Id=".$_SESSION['FiltreSODA_Questionnaire'];
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		
		//Ajout le fichier annexe 
		if(!empty($_FILES['uploaded_file']))
		{
			if($_FILES['uploaded_file']['name'] <> ""){
				$nomfichier = transferer_fichier($_FILES['uploaded_file']['name'], $_FILES['uploaded_file']['tmp_name'], "DocumentQCM/");
				$reqUpdateAnnexe="UPDATE soda_questionnaire SET Annexe='".$nomfichier."' WHERE Id=".$_SESSION['FiltreSODA_Questionnaire'];
				$resultUpdateAnnexe=mysqli_query($bdd,$reqUpdateAnnexe);
			}
		}
		
		//Ajout des exceptions
		$req="UPDATE soda_questionnaire_exceptiongroupemetier SET Suppr=1,Date_Suppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id_Questionnaire=".$_SESSION['FiltreSODA_Questionnaire']." ";
		$result=mysqli_query($bdd,$req);

		$req="SELECT Id FROM soda_groupemetier WHERE Suppr=0 ";
		$resultGM=mysqli_query($bdd,$req);
		$nbGM=mysqli_num_rows($resultGM);
		if ($nbGM > 0)
		{
			while($row=mysqli_fetch_array($resultGM))
			{
				if(!isset($_POST['GroupeMetier'.$row['Id']])){
					$req="INSERT INTO soda_questionnaire_exceptiongroupemetier (Id_Questionnaire,Id_GroupeMetier,DateCreation,Id_Creation)
					VALUES (".$_SESSION['FiltreSODA_Questionnaire'].",".$row['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
	}
}
?>
<form id="formulaire" method="POST" enctype="multipart/form-data" action="Liste_Questions.php" class="None">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="80%" valign="top">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td>
						<table align="center" style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
							<tr><td height="4"></td></tr>
							<tr>
								<td width="8%" class="Libelle">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?> :
								</td>
								<td width="12%">
									<select id="theme" name="theme" onchange="submit();">
										<option value="0"></option>
									<?php
									$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
									$nbAccess=mysqli_num_rows($resAcc);
									
									$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
									$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
				
									$req = "SELECT Id, Libelle
											FROM soda_theme
											WHERE Suppr=0 ";
									if($nbAccess==0 && $nbSuperAdmin==0 && !DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
										$req.="AND Id IN (SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")) ";
									}
									$req.="ORDER BY Libelle;";
									
									$resultTheme=mysqli_query($bdd,$req);
									$nbTheme=mysqli_num_rows($resultTheme);
									
									$theme=$_SESSION['FiltreSODA_Theme'];
									$recupQuestionnaire=1;
									if($_POST){
										$theme=$_POST['theme'];
										if($_SESSION['FiltreSODA_Theme']<>$_POST['theme']){
											$_SESSION['FiltreSODA_Questionnaire']=0;
											$recupQuestionnaire=0;
										}
									}
									$_SESSION['FiltreSODA_Theme']=$theme;
									if ($nbTheme > 0)
									{
										while($row=mysqli_fetch_array($resultTheme))
										{
											if ($row['Id'] == $_SESSION['FiltreSODA_Theme']){$Selected = "Selected";}
											echo "<option value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
											$Selected = "";
										}
									 }
									 
									 $req="SELECT Id FROM soda_theme 
										WHERE Suppr=0 
										AND Id=".$theme."
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.") ";
									$resAcc=mysqli_query($bdd,$req);
									$nbGestionnaireDuTheme=mysqli_num_rows($resAcc);
									 ?>
									</select>
								</td>
								<td width="13%" class="Libelle">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Questionnaire";}else{echo "Questionnaire";}?> :
								</td>
								<td width="25%">
									<select id="questionnaire" name="questionnaire" onchange="submit();">
										<option value="0"></option>
									<?php
									$req="SELECT Id,Libelle,Actif,Specifique,
										(SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) AS Theme
										FROM soda_questionnaire 
										WHERE Suppr=0 
										AND Id_Theme=".$_SESSION['FiltreSODA_Theme']." ";
									if($nbAccess==0 && $nbSuperAdmin==0 && !DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
										$req.="AND Id_Theme IN (SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")) ";
									}
									$req.="ORDER BY Actif,Libelle ";
									
									$resultQ=mysqli_query($bdd,$req);
									$nbQ=mysqli_num_rows($resultQ);
									
									$questionnaire=$_SESSION['FiltreSODA_Questionnaire'];
									if($_POST){
										if($recupQuestionnaire==1){
											$questionnaire=$_POST['questionnaire'];
										}
									}
									$_SESSION['FiltreSODA_Questionnaire']=$questionnaire;
									if ($nbQ > 0)
									{
										while($row=mysqli_fetch_array($resultQ))
										{
											if ($row['Id'] == $_SESSION['FiltreSODA_Questionnaire']){$Selected = "Selected";}
											echo "<option value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
											$Selected = "";
										}
									 }
									 ?>
									</select>
								</td>
								<td width="4%">
									<?php if($questionnaire>0){?>
									<a style="text-decoration:none;" href="javascript:QuestionnaireExcel2(<?php echo $questionnaire;?>)"><img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel"></a>
									<?php }?>
								</td>
								<td width="10%">
									<?php if($questionnaire>0){?>
									<a style="text-decoration:none;" class='Bouton' href="javascript:QuestionnaireExcel(<?php echo $questionnaire;?>)"><?php if($LangueAffichage=="FR"){echo "Format officiel";}else{echo "Official format";}?></a>
									<?php }?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<?php
					if($_SESSION['FiltreSODA_Questionnaire']>0){
						
				?>
				<tr>
					<td align="center" colspan="8">
						<?php if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){?>
							<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Ajout_Question.php','A','0')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add questions";}else{echo "Ajouter des questions";} ?>&nbsp;</a>
						<?php } ?>
					</td>
				</tr>
				<tr><td height="10"></td></tr>
				<?php
						$req="SELECT Id,Question,Question_EN,Reponse,Reponse_EN,Ponderation,ImageQuestion
							FROM soda_question
							WHERE Suppr=0 
							AND Id_Questionnaire=".$_SESSION['FiltreSODA_Questionnaire']." 
							ORDER BY Ordre ";
						$resultQ=mysqli_query($bdd,$req);
						$nbQ=mysqli_num_rows($resultQ);
						$lenb=0;
						if ($nbQ > 0)
						{
				?>				
				<tr>
					<td>
						<table align="center" class="TableCompetences" style="width:100%;">
							<thead>
								<tr>
									<td width="3%" class="EnTeteTableauCompetences"></td>
									<td width="3%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Ref";}else{echo "Ref";}?></td>
									<td width="35%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Question/Réponse";}else{echo "Question/Answer";}?></td>
									<td width="15%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Exceptions";}else{echo "Exceptions";}?></td>
									<td width="5%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Pondération";}else{echo "Weighting";}?></td>
									<td width="2%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Image";}else{echo "Image";}?></td>
									<td width="2%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Modifier";}else{echo "To modify";}?></td>
									<td width="2%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Dupliquer";}else{echo "Duplicate";}?></td>
									<td width="2%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Supprimer";}else{echo "Remove";}?></td>
								</tr>
							</thead>
							<tbody id="test">
							<?php
								$Couleur="#EEEEEE";
								while($rowQ=mysqli_fetch_array($resultQ))
								{
									$lenb++;
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									
									$Question="Q : ".$rowQ['Question']."<br>";
									$Question.="Q EN : ".$rowQ['Question_EN'];
									
									$Reponse="<span style='color:#00c935;'>R : ".$rowQ['Reponse']."<br>";
									$Reponse.="R EN : ".$rowQ['Reponse_EN']."</span>";
									
									$req="SELECT (SELECT Libelle FROM moris_client WHERE Id=Id_Client) AS Client 
									FROM soda_question_exceptionclient
									WHERE Suppr=0 AND Id_Question=".$rowQ['Id']." 
									ORDER BY Client ";
									$resultE=mysqli_query($bdd,$req);
									$nbE=mysqli_num_rows($resultE);
									$Exception="";
									if ($nbE > 0)
									{
										$Exception="<u>Client</u> : ";
										$liste="";
										while($rowE=mysqli_fetch_array($resultE))
										{
											if($liste<>""){$liste.=", ";}
											$liste.=$rowE['Client'];
										}
										$Exception.=$liste;
									}
									
									$req="SELECT (SELECT Num FROM moris_famille_r03 WHERE Id=Id_R03) AS R03 
									FROM soda_question_exceptionr03
									WHERE Suppr=0 AND Id_Question=".$rowQ['Id']." 
									ORDER BY R03 ";
									$resultE=mysqli_query($bdd,$req);
									$nbE=mysqli_num_rows($resultE);
									if ($nbE > 0)
									{
										if($Exception<>""){$Exception.="<br>";}
										$Exception.="<u>Famille R03</u> : ";
										$liste="";
										while($rowE=mysqli_fetch_array($resultE))
										{
											if($liste<>""){$liste.=", ";}
											$liste.=$rowE['R03'];
										}
										$Exception.=$liste;
									}
									
									$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER 
									FROM soda_question_exceptionuer
									WHERE Suppr=0 AND Id_Question=".$rowQ['Id']." 
									ORDER BY UER ";
									$resultE=mysqli_query($bdd,$req);
									$nbE=mysqli_num_rows($resultE);
									if ($nbE > 0)
									{
										if($Exception<>""){$Exception.="<br>";}
										$Exception.="<u>UER</u> : ";
										$liste="";
										while($rowE=mysqli_fetch_array($resultE))
										{
											if($liste<>""){$liste.=", ";}
											$liste.=$rowE['UER'];
										}
										$Exception.=$liste;
									}
									
									$req="SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation 
									FROM soda_question_exceptionprestation
									WHERE Suppr=0 AND Id_Question=".$rowQ['Id']." 
									ORDER BY Prestation ";
									$resultE=mysqli_query($bdd,$req);
									$nbE=mysqli_num_rows($resultE);
									if ($nbE > 0)
									{
										if($Exception<>""){$Exception.="<br>";}
										$Exception.="<u>Prestation</u> : ";
										$liste="";
										while($rowE=mysqli_fetch_array($resultE))
										{
											$presta=substr($rowE['Prestation'],0,strpos($rowE['Prestation']," "));
											if($presta==""){$presta=$rowE['Prestation'];}
											
											if($liste<>""){$liste.=", ";}
											$liste.=$presta;
										}
										$Exception.=$liste;
									}
							?>
								<tr id="tr_<?php echo $rowQ['Id']; ?>" bgcolor="<?php echo $Couleur;?>">
									<td width="3%" align="center">
										<a href="javascript:Up(<?php echo $rowQ['Id']; ?>)">
										<img id="Haut" src='../../Images/haut_Gris.png' width="13px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Up";}else{echo "Monter";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Up";}else{echo "Monter";} ?>'
										onmouseover="this.src='../../Images/haut.png'" onmouseout="this.src='../../Images/haut_Gris.png'">
										</a></br>
										<a href="javascript:Down(<?php echo $rowQ['Id']; ?>)">
										<img id="Bas" src='../../Images/bas_Gris.png' width="13px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Down";}else{echo "Descendre";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Down";}else{echo "Descendre";} ?>'
										onmouseover="this.src='../../Images/bas.png'" onmouseout="this.src='../../Images/bas_Gris.png'">
										</a>
									</td>
									<td><?php echo $rowQ['Id'];?></td>
									<td><?php echo $Question."<br>".$Reponse;?></td>
									<td><?php echo $Exception;?></td>
									<td><?php echo $rowQ['Ponderation']; ?></td>
									<td>
									<?php
										if($rowQ['ImageQuestion']<>""){
											echo "<a class=\"Info\" href=\"ImageQCM/".$rowQ['ImageQuestion']."\" target='_blank'><img src='../../Images/image.png' style='border:0;width:20px;' title='Image'></a>";
										}
									?>
									</td>
									<td>
										<?php if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0 || DroitsFormationPlateforme(array($IdPosteReferentQualiteSysteme))){?>
										<a class="M" href="javascript:OuvreFenetreModif('Ajout_Question.php','M','<?php echo $rowQ['Id']; ?>');">
											<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
										</a>
										<?php } ?>
									</td>
									<td>
										<?php if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){?>
										<a class="M" href="javascript:OuvreFenetreModif('Ajout_Question.php','D','<?php echo $rowQ['Id']; ?>');">
											<img src="../../Images/Duplication.gif" style="border:0;" alt="Dupliquer">
										</a>
										<?php } ?>
									</td>
									<td>
										<?php if($nbSuperAdmin>0){ ?>
										<a class="M" href="javascript:OuvreFenetreModif('Ajout_Question.php','S','<?php echo $rowQ['Id']; ?>');">
											<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
										</a>
										<?php } ?>
									</td>
								</tr>
							<?php
								}	//Fin boucle
							?>
							</tbody>
						</table>
					</td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td align="center" colspan="8">
						<?php if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){?>
							<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Ajout_Question.php','A','0')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add questions";}else{echo "Ajouter des questions";} ?>&nbsp;</a>
						<?php } ?>
					</td>
				</tr>
				<?php 
					}
				}
				?>
			</table>
		</td>
		<td width="20%" valign="top">
			<?php 
				$req="SELECT Id,Libelle,Actif,DateDerniereRevue,NbQuestion,Annexe,SeuilReussite,AutoriserQuestionsAdditionnelles,Specifique,NonAleatoire
					FROM soda_questionnaire 
					WHERE Suppr=0 
					AND Id=".$_SESSION['FiltreSODA_Questionnaire']." ";
				$resultQ=mysqli_query($bdd,$req);
				$nbQ=mysqli_num_rows($resultQ);
				if ($nbQ > 0)
				{
					$row=mysqli_fetch_array($resultQ);
			?>
			<table align="center" style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle">
						<input type="checkbox"  id="actif" name="actif" <?php if($row['Actif']==0){echo "checked";} ?> />
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo " Actif";}else{echo " Actif";}?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle">
						<input type="checkbox"  id="specifique" name="specifique" <?php if($row['Specifique']==1){echo "checked";} ?> />
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo " Spécifique";}else{echo " Specific";}?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date dernière revue";}else{echo "Date of last review";}?>
					</td>
					<td width="50%">
						<input id="dateDerniereRevue" name="dateDerniereRevue" type="date" value="<?php echo AfficheDateFR($row['DateDerniereRevue']); ?>" size="10"/>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nbr de questions";}else{echo "Number of questions";}?>
					</td>
					<td width="50%">
						<input id="nbQuestion" name="nbQuestion" onKeyUp="nombre(this)" type="texte" value="<?php echo $row['NbQuestion']; ?>" size="5"/>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Seuil de réussite";}else{echo "Pass threshold";}?>
					</td>
					<td width="50%">
						<input id="seuilQuestion" name="seuilQuestion" onKeyUp="nombre(this)" type="texte" value="<?php echo $row['SeuilReussite']; ?>" size="5"/>%
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle" colspan="2">
						<input type="checkbox"  id="nonAleatoire" name="nonAleatoire" <?php if($row['NonAleatoire']==1){echo "checked";} ?> />
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Questions non aléatoires";}else{echo "Non-random questions";}?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="50%" class="Libelle" colspan="2">
						<input type="checkbox"  id="qAdditionnelle" name="qAdditionnelle" <?php if($row['AutoriserQuestionsAdditionnelles']==1){echo "checked";} ?> />
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Autoriser les questions additionnelles";}else{echo "Allow additional questions";}?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td colspan="2" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Groupes métiers pouvant réaliser ce questionnaire";}else{echo "Business groups that can complete this questionnaire";}?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td colspan="2" class="Libelle">
						<div id='Div_GroupeMetier' style='height:200px;width:200px;overflow:auto;'>
							<table>
						<?php
							$req="SELECT Id,Libelle,
								(SELECT COUNT(Id) FROM soda_questionnaire_exceptiongroupemetier WHERE Suppr=0 AND Id_Questionnaire=".$_SESSION['FiltreSODA_Questionnaire']." AND Id_GroupeMetier=soda_groupemetier.Id) AS Exception
								FROM soda_groupemetier
								WHERE Suppr=0
								ORDER BY Libelle;";
						
							$resultUER=mysqli_query($bdd,$req);
							$nbUER=mysqli_num_rows($resultUER);
							
							if ($nbUER > 0)
							{
								while($row2=mysqli_fetch_array($resultUER))
								{
									$selected="checked";
									if($row2['Exception']>0){$selected="";}
									echo "<tr><td><input class='checkGroupeMetier' type='checkbox' ".$selected." value='".$row2['Id']."' name='GroupeMetier".$row2['Id']."'>".stripslashes($row2['Libelle'])."</td></tr>";
								}
							}
						?>
							</table>
						</div>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Annexe";}else{echo "Appendix";}?> :
						<?php 
							if($row['Annexe']<>""){
								if($LangueAffichage=="FR"){
									echo "<a class=\"Info\" href=\"DocumentQCM/".$row['Annexe']."\"><img src='../../Images/dossier.jpg' style='border:0;width:25px;' title='Annexe'></a>";
								}
								else{
									echo "<a class=\"Info\" href=\"DocumentQCM/".$row['Annexe']."\"><img src='../../Images/dossier.jpg' style='border:0;width:25px;' title='Appendix'>></a>";
								}
								if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){
						?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="Bouton" type="submit" name="SupprimerAnnexe" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Supprimer annexe";}else{echo "Delete appendix";}?>" />
						<?php
								}
							}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<input type="file" name="uploaded_file" />
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td colspan="2" align="center">
						<?php if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){?>
						<input class="Bouton" type="submit" name="btn_Questionnaire" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>" />
						<?php } ?>
					</td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td align="left" colspan="8">
						<?php 
							$req = "SELECT soda_surveillance_question.Id
									FROM soda_surveillance_question 
									LEFT JOIN soda_surveillance 
									ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
									WHERE soda_surveillance.Suppr=0
									AND soda_surveillance_question.Id_Question=0
									AND AutoSurveillance=0 
									AND EtatQA=0
									AND Id_Questionnaire=".$row['Id']."
									";	
							$result=mysqli_query($bdd,$req);
							$nbQA=mysqli_num_rows($result);
							$texte="";
							if($nbQA>0){$texte=" [".$nbQA."]";}
						?>
						<?php if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaireDuTheme>0){?>
						<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Questions_Additionnelles.php','A','<?php echo $row['Id'];?>')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Additional Questions";}else{echo "Questions additionnelles";}echo $texte; ?>&nbsp;</a>
						<?php } ?>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			</table>
			<?php 
				}
			?>
		</td>
	</tr>
	<tr><td height="50"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>