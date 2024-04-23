<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreAjout(){
		var w=window.open("Ajout_FormationSMQ.php?Mode=A&Id=0&motcles="+document.getElementById('motcles').value,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1100,height=850");
		w.focus();
		}
	function OuvreFenetreModif(Id){
		var w=window.open("Ajout_FormationSMQ.php?Mode=M&Id="+Id+"&motcles="+document.getElementById('motcles').value,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1100,height=850");
		w.focus();
		}
	function OuvreFenetreSuppr(Id,NbBesoin,NbSession){
		if(NbSession>0){
			if(document.getElementById('Langue').value=="FR"){
				alert("Impossible de supprimer, des sessions sont en cours de traitement ou programmées");
			}
			else{
				alert("Can not delete, sessions are being processed or scheduled");
			}
		}
		else{
			if(NbBesoin>0){
				if(document.getElementById('Langue').value=="FR"){
					question = "Des besoins existent pour cette formation. Etes-vous sûre de vouloir supprimer cette formation ? La suppression de la formation supprimera les besoins.";
				}
				else{
					question = "There are needs for this training. Are you sure you want to delete this training? Deleting the training will remove the need.";
				}
			}
			else{
				if(document.getElementById('Langue').value=="FR"){
					question = "Etes-vous sûr de vouloir supprimer ?";
				}
				else{
					question = "Are you sure you want to delete";
				}
			}
			if(window.confirm(question)){
				var w=window.open("Ajout_FormationSMQ.php?Mode=S&Id="+Id+"&motcles="+document.getElementById('motcles').value,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
				w.focus();
			}
		}
	}
	function OuvreFenetreExport(){
		var w=window.open("FormationsSMQ_Extract.php?&motcle="+document.getElementById('motcles').value,"PageExport","status=no,menubar=no,scrollbars=yes,width=90,height=60");
		w.blur();
		}
</script>
<?php
if($_POST){
	$_SESSION['FiltreFormSMQ_MotCle']=$_POST['motcles'];
}

if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Reference"){
		$_SESSION['TriFormSMQ_General']= str_replace("Reference ASC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Reference DESC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Reference ASC","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Reference DESC","",$_SESSION['TriFormSMQ_General']);
		if($_SESSION['TriFormSMQ_Reference']==""){$_SESSION['TriFormSMQ_Reference']="ASC";$_SESSION['TriFormSMQ_General'].= "Reference ".$_SESSION['TriFormSMQ_Reference'].",";}
		elseif($_SESSION['TriFormSMQ_Reference']=="ASC"){$_SESSION['TriFormSMQ_Reference']="DESC";$_SESSION['TriFormSMQ_General'].= "Reference ".$_SESSION['TriFormSMQ_Reference'].",";}
		else{$_SESSION['TriFormSMQ_Reference']="";}
	}
	if($_GET['Tri']=="Type"){
		$_SESSION['TriFormSMQ_General']= str_replace("TypeFormation ASC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("TypeFormation DESC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("TypeFormation ASC","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("TypeFormation DESC","",$_SESSION['TriFormSMQ_General']);
		if($_SESSION['TriFormSMQ_Type']==""){$_SESSION['TriFormSMQ_Type']="ASC";$_SESSION['TriFormSMQ_General'].= "TypeFormation ".$_SESSION['TriFormSMQ_Type'].",";}
		elseif($_SESSION['TriFormSMQ_Type']=="ASC"){$_SESSION['TriFormSMQ_Type']="DESC";$_SESSION['TriFormSMQ_General'].= "TypeFormation ".$_SESSION['TriFormSMQ_Type'].",";}
		else{$_SESSION['TriFormSMQ_Type']="";}
	}
	if($_GET['Tri']=="Recyclage"){
		$_SESSION['TriFormSMQ_General']= str_replace("Recyclage ASC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Recyclage DESC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Recyclage ASC","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Recyclage DESC","",$_SESSION['TriFormSMQ_General']);
		if($_SESSION['TriFormSMQ_Recyclage']==""){$_SESSION['TriFormSMQ_Recyclage']="ASC";$_SESSION['TriFormSMQ_General'].= "Recyclage ".$_SESSION['TriFormSMQ_Recyclage'].",";}
		elseif($_SESSION['TriFormSMQ_Recyclage']=="ASC"){$_SESSION['TriFormSMQ_Recyclage']="DESC";$_SESSION['TriFormSMQ_General'].= "Recyclage ".$_SESSION['TriFormSMQ_Recyclage'].",";}
		else{$_SESSION['TriFormSMQ_Recyclage']="";}
	}
	if($_GET['Tri']=="DateMAJ"){
		$_SESSION['TriFormSMQ_General']= str_replace("Date_MAJ ASC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Date_MAJ DESC,","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Date_MAJ ASC","",$_SESSION['TriFormSMQ_General']);
		$_SESSION['TriFormSMQ_General']= str_replace("Date_MAJ DESC","",$_SESSION['TriFormSMQ_General']);
		if($_SESSION['TriFormSMQ_DateMAJ']==""){$_SESSION['TriFormSMQ_DateMAJ']="ASC";$_SESSION['TriFormSMQ_General'].= "Date_MAJ ".$_SESSION['TriFormSMQ_DateMAJ'].",";}
		elseif($_SESSION['TriFormSMQ_DateMAJ']=="ASC"){$_SESSION['TriFormSMQ_DateMAJ']="DESC";$_SESSION['TriFormSMQ_General'].= "Date_MAJ ".$_SESSION['TriFormSMQ_DateMAJ'].",";}
		else{$_SESSION['TriFormSMQ_DateMAJ']="";}
	}
}

?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" method="POST" action="Liste_FormationSMQ.php">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#67cff1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des formations SMQ";}else{echo "QMS Training Management";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table class="TableCompetences" style="width:100%; border-spacing:0;">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Mots clés";}else{echo "Keywords";}?> : </td>
					<td width="20%">
						<input name="motcles" id="motcles" value="<?php $motcle=$_SESSION['FiltreFormSMQ_MotCle']; echo $motcle; ?>"/>
					</td>
					<td width="35%" align="left">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					</td>
					<td width="5%">
						&nbsp;<a style="text-decoration:none;" href="javascript:OuvreFenetreExport();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
						</a>&nbsp;
					</td>
				</tr>
			</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table style="width:100%; align:left;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter une formation";}else{echo "Add training";} ?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<div style="width:100%;height:400px;overflow:auto;">
		<table style="width:100%; border-spacing:0; align:left;" class="GeneralInfo">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationSMQ.php?Tri=Reference">&nbsp;<?php if($LangueAffichage=="FR"){echo "Référence";}else{echo "Reference";} ?><?php if($_SESSION['TriFormSMQ_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormSMQ_Reference']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationSMQ.php?Tri=Type">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";} ?><?php if($_SESSION['TriFormSMQ_Type']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormSMQ_Type']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationSMQ.php?Tri=Recyclage">&nbsp;<?php if($LangueAffichage=="FR"){echo "Recyclage différent";}else{echo "Different recycling";} ?><?php if($_SESSION['TriFormSMQ_Recyclage']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormSMQ_Recyclage']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationSMQ.php?Tri=DateMAJ">&nbsp;<?php if($LangueAffichage=="FR"){echo "Mis à jour le";}else{echo "Updated";} ?><?php if($_SESSION['TriFormSMQ_DateMAJ']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormSMQ_DateMAJ']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Intitulés";}else{echo "Titles";} ?></td>
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Qualifications acquises";}else{echo "Qualifications acquired";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Documents";}else{echo "Documents";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				//FORMATIONS SMQ
				$requeteFormation="SELECT Id, Id_Plateforme, Reference, Id_TypeFormation, ";
				$requeteFormation.="(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation, ";
				$requeteFormation.="Tuteur, Recyclage, Id_Personne_MAJ, ";
				$requeteFormation.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) as Personne_MAJ, Date_MAJ ";
				$requeteFormation.="FROM form_formation WHERE Suppr=0 AND Id_Plateforme=0 ";
				if($_SESSION['TriFormSMQ_General']<>""){
					$requeteFormation.="ORDER BY ".substr($_SESSION['TriFormSMQ_General'],0,-1);
				}
				$resultFormation=mysqli_query($bdd,$requeteFormation);
				$nbFormation=mysqli_num_rows($resultFormation);
				
				//DOCUMENTS
				$requeteDocuments="SELECT Id,Id_Formation,(SELECT Reference FROM form_document WHERE Id=Id_Document) AS Document FROM form_formation_document WHERE Suppr=0 ORDER BY Document ASC";
				$resultDocuments=mysqli_query($bdd,$requeteDocuments);
				$nbDocuments=mysqli_num_rows($resultDocuments);
				
				//QUALIFICATIONS
				$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif ";
				$requeteQualifications.=" FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre";
				$requeteQualifications.=" WHERE ";
				$requeteQualifications.=" form_formation_qualification.Id_Qualification=new_competences_qualification.Id";
				$requeteQualifications.=" AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id";
				$requeteQualifications.=" AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id";
				$requeteQualifications.=" AND form_formation_qualification.Suppr=0 AND form_formation_qualification.Masquer=0 ";
				$requeteQualifications.=" ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, new_competences_categorie_qualification.Libelle ASC,new_competences_qualification.Libelle ASC";
				$resultQualifications=mysqli_query($bdd,$requeteQualifications);
				$nbQualifs=mysqli_num_rows($resultQualifications);
				
				$requeteInfos="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Langue";
				$resultInfos=mysqli_query($bdd,$requeteInfos);
				$nbInfos=mysqli_num_rows($resultInfos);
				
				//Liste des QCM 
				$req="SELECT form_formation_qualification_qcm.Id_Formation_Qualification, form_formation_qualification_qcm.Id_QCM, 
					form_qcm.Code,form_formation_qualification_qcm.Id_Langue,form_qcm.Suppr, ";
				$req.="(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_formation_qualification_qcm.Id_Langue) AS Langue ";
				$req.="FROM form_formation_qualification_qcm LEFT JOIN form_qcm ";
				$req.="ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id ";
				$req.="WHERE form_formation_qualification_qcm.Suppr=0 ORDER BY Code";
				$resultQCM=mysqli_query($bdd,$req);
				$nbResultaQCM=mysqli_num_rows($resultQCM);
				
				if ($nbFormation>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultFormation)){
						
						$Documents="";
						if($nbDocuments>0){
							mysqli_data_seek($resultDocuments,0);
							while($rowDoc=mysqli_fetch_array($resultDocuments)){
								if($rowDoc['Id_Formation']==$row['Id']){
									$Documents.=stripslashes($rowDoc['Document'])."</br>";
								}
							}
							if($Documents<>""){
								$Documents=substr($Documents,0,-5);
							}
						}
						$Infos="";
						if($nbInfos>0){
							mysqli_data_seek($resultInfos,0);
							while($rowInfo=mysqli_fetch_array($resultInfos)){
								if($rowInfo['Id_Formation']==$row['Id']){
									$Infos.=stripslashes($rowInfo['Libelle'])."(".stripslashes($rowInfo['Langue']).")</br>";
								}
							}
							if($Infos<>""){
								$Infos=substr($Infos,0,-5);
							}
						}
						
						$couleur2=$couleur;
						$nb=1;
						$qualifications="";
						if($nbQualifs>0){
							mysqli_data_seek($resultQualifications,0);
							while($rowQualif=mysqli_fetch_array($resultQualifications)){
								if($rowQualif['Id_Formation']==$row['Id']){
									$qcm="";
									if($nbResultaQCM>0){
										mysqli_data_seek($resultQCM,0);
										while($rowQCM=mysqli_fetch_array($resultQCM)){
											if($rowQCM['Id_Formation_Qualification']==$rowQualif['Id']){
												if($rowQCM['Suppr']==0){
													$qcm.="<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; QCM : ".$rowQCM['Code']." (".$rowQCM['Langue'].")";
												}
												else{
													$qcm.="<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style='color:red;'>QCM : ".$rowQCM['Code']." (".$rowQCM['Langue'].")</span>";
												}
											}
										}
									}
									$border="style='border-bottom:1px black dotted;'";
									$qualifications.="<tr bgcolor=".$couleur.">";
									$qualifications.="<td id='leHover'>&bull; ".stripslashes($rowQualif['Qualif']).$qcm."<span>Catégorie maitre : ".stripslashes($rowQualif['QualifMaitre'])."<br>Catégorie : ".stripslashes($rowQualif['CategorieQualif'])."</span></td>";
									$qualifications.="</tr>";
									$nb++;
									
									if($couleur2==$couleur){$couleur2="#eeeeee";}
									else{$couleur2=$couleur;}
								}
							}
						}
						$btrouve=1;
						if($motcle<>""){
							if(stripos($row['Reference'],$motcle)===false && stripos($Documents,$motcle)===false && stripos($Infos,$motcle)===false && stripos($row['TypeFormation'],$motcle)===false && stripos($qualifications,$motcle)===false){
								$btrouve=0;
							}
							else{
								$btrouve=1;
							}
						}
						if($btrouve==1){
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td valign="middle" rowspan="<?php echo $nb;?>">&nbsp;<?php echo $row['Reference'];?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $row['TypeFormation'];?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php if($LangueAffichage=="FR"){if($row['Recyclage']==1){echo "Oui";}else{echo "Non";}}else{if($row['Recyclage']==1){echo "Yes";}else{echo "No";}} ?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>" id="hover"><?php echo AfficheDateJJ_MM_AAAA($row['Date_MAJ'])."<span>".$row['Personne_MAJ']."</span>";?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $Infos;?></td>
									<td valign="middle"><?php echo "";?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $Documents;?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>" align="center">
										<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
										<img src='../../Images/Modif.gif' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
										</a>
									</td>
									<td valign="middle" rowspan="<?php echo $nb;?>" align="center">
										<?php
											//Verifier si cette formation n'a pas des besoins en cours
											$reqB="SELECT Id 
													FROM form_besoin
													WHERE Suppr=0
													AND Valide=1
													AND Id_Formation=".$row['Id']."
													AND Traite=0 ";
											$resultB=mysqli_query($bdd,$reqB);
											$nbB=mysqli_num_rows($resultB);
											
											//Verifier si cette formation n'a pas une session futur ou en traitement
											$reqSession="SELECT form_session_date.Id 
													FROM form_session_date,
													form_session
													WHERE form_session_date.Id_Session=form_session.Id
													AND form_session.Id_Formation=".$row['Id']."
													AND form_session_date.Suppr=0
													AND form_session.Suppr=0
                                                    AND form_session.Annule=0
													AND (form_session_date.DateSession>='".date('Y-m-d')."'
													OR (SELECT COUNT(form_session_personne.Id)
														FROM form_session_personne
														LEFT JOIN form_besoin
														ON form_session_personne.Id_Besoin=form_besoin.Id
														WHERE form_session_personne.Suppr=0 
														AND form_session_personne.Id_Session=form_session.Id 
														AND form_session_personne.Validation_Inscription<>-1
														AND form_besoin.Suppr=0
														AND form_besoin.Traite<3
														)>0
													) ";
											$resultSession=mysqli_query($bdd,$reqSession);
											$nbSession=mysqli_num_rows($resultSession);
										?>
										<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $nbB; ?>,<?php echo $nbSession; ?>)">
										<img src='../../Images/Suppression.gif' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
									</a>
									</td>
								</tr>
							<?php
							echo $qualifications;
							if($couleur=="#ffffff"){$couleur="#b1daeb";}
							else{$couleur="#ffffff";}
						}
					}
				}
			?>
		</table>
		</div>
	</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>