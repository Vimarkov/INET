<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreAjout(Id_Plateforme){
		var w=window.open("Ajout_FormationPlateforme.php?Mode=A&Id=0&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&motcles="+document.getElementById('motcles').value,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1100,height=750");
		w.focus();
		}
	function OuvreFenetreModif(Id,Id_Plateforme){
		var w=window.open("Ajout_FormationPlateforme.php?Mode=M&Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&motcles="+document.getElementById('motcles').value,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1300,height=750");
		w.focus();
		}
	function OuvreFenetreSuppr(Id,Id_Plateforme,NbBesoin,NbSession){
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
				var w=window.open("Ajout_FormationPlateforme.php?Mode=S&Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&motcles="+document.getElementById('motcles').value,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
				w.focus();
			}
		}
	}
	function OuvreFenetreExport(){
		var w=window.open("FormationsPlateforme_Extract.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&motcle="+document.getElementById('motcles').value,"PageExport","status=no,menubar=no,scrollbars=yes,width=90,height=60");
		w.blur();
		}
	function OuvreExcel(Id_QCM,Id_QCM_Langue){
		var w=window.open("QCM_Excel.php?Id_QCM="+Id_QCM+"&Id_QCM_Langue="+Id_QCM_Langue,"PageQCM","status=no,menubar=no,width=50,height=50");
		w.focus();
	}
</script>
<?php
if($_POST){
	$_SESSION['FiltreFormPlateforme_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreFormPlateforme_Type']=$_POST['Id_TypeFormation'];
	$_SESSION['FiltreFormPlateforme_MotCle']=$_POST['motcles'];
	$_SESSION['FiltreFormPlateforme_Organisme']=$_POST['organismeR'];
}
if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Reference"){
		$_SESSION['TriFormPlateforme_General']= str_replace("Reference ASC,","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("Reference DESC,","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("Reference ASC","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("Reference DESC","",$_SESSION['TriFormPlateforme_General']);
		if($_SESSION['TriFormPlateforme_Reference']==""){$_SESSION['TriFormPlateforme_Reference']="ASC";$_SESSION['TriFormPlateforme_General'].= "Reference ".$_SESSION['TriFormPlateforme_Reference'].",";}
		elseif($_SESSION['TriFormPlateforme_Reference']=="ASC"){$_SESSION['TriFormPlateforme_Reference']="DESC";$_SESSION['TriFormPlateforme_General'].= "Reference ".$_SESSION['TriFormPlateforme_Reference'].",";}
		else{$_SESSION['TriFormPlateforme_Reference']="";}
	}
	if($_GET['Tri']=="Type"){
		$_SESSION['TriFormPlateforme_General']= str_replace("TypeFormation ASC,","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("TypeFormation DESC,","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("TypeFormation ASC","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("TypeFormation DESC","",$_SESSION['TriFormPlateforme_General']);
		if($_SESSION['TriFormPlateforme_Type']==""){$_SESSION['TriFormPlateforme_Type']="ASC";$_SESSION['TriFormPlateforme_General'].= "TypeFormation ".$_SESSION['TriFormPlateforme_Type'].",";}
		elseif($_SESSION['TriFormPlateforme_Type']=="ASC"){$_SESSION['TriFormPlateforme_Type']="DESC";$_SESSION['TriFormPlateforme_General'].= "TypeFormation ".$_SESSION['TriFormPlateforme_Type'].",";}
		else{$_SESSION['TriFormPlateforme_Type']="";}
	}
	if($_GET['Tri']=="Recyclage"){
		$_SESSION['TriFormPlateforme_General']= str_replace("Recyclage ASC,","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("Recyclage DESC,","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("Recyclage ASC","",$_SESSION['TriFormPlateforme_General']);
		$_SESSION['TriFormPlateforme_General']= str_replace("Recyclage DESC","",$_SESSION['TriFormPlateforme_General']);
		if($_SESSION['TriFormPlateforme_Recyclage']==""){$_SESSION['TriFormPlateforme_Recyclage']="ASC";$_SESSION['TriFormPlateforme_General'].= "Recyclage ".$_SESSION['TriFormPlateforme_Recyclage'].",";}
		elseif($_SESSION['TriFormPlateforme_Recyclage']=="ASC"){$_SESSION['TriFormPlateforme_Recyclage']="DESC";$_SESSION['TriFormPlateforme_General'].= "Recyclage ".$_SESSION['TriFormPlateforme_Recyclage'].",";}
		else{$_SESSION['TriFormPlateforme_Recyclage']="";}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" method="POST" action="Liste_FormationPlateforme.php">
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
						
					if($LangueAffichage=="FR"){echo "Gestion des formations / Unité d'exploitation";}else{echo "Training Management / Operating unit";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table class="TableCompetences" style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
					<td width="15%">
						<select id="Id_Plateforme" name="Id_Plateforme" onchange="submit()">
							<?php
							$Plateforme=$_SESSION['FiltreFormPlateforme_Plateforme'];
							$resultPlateforme=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM new_competences_personne_poste_plateforme 
								WHERE Id_Poste 
									IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.") 
								AND Id_Personne=".$IdPersonneConnectee." 
								ORDER BY Libelle");
							$nbPlateforme=mysqli_num_rows($resultPlateforme);
							if($nbPlateforme>0){
								$selected="";
								while($rowplateforme=mysqli_fetch_array($resultPlateforme)){
									$selected="";
									if($Plateforme<>0){
										if($Plateforme==$rowplateforme['Id_Plateforme']){$selected="selected";}
									}
									echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
									if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
								}
							}
							?>
						</select>
					</td>
					<td class="Libelle" width="8%">&nbsp;Type</td>
					<td width="15%">
						<select name="Id_TypeFormation" id="Id_TypeFormation" onchange="submit()">
							<option value="0"></option>
							<?php
							$TypeForm=$_SESSION['FiltreFormPlateforme_Type'];
							$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
							while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation)){
								$selected="";
								if($TypeForm<>""){
									if($TypeForm==$rowTypeFormation['Id']){$selected="selected";}
								}
								echo "<option value='".$rowTypeFormation['Id']."' ".$selected.">".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
							}
							?>
						</select>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";}?> : </td>
					<td width="15%">
						<select name="organismeR" id="organismeR" onchange="submit()">
							<option value="0"></option>
							<?php
							$organismeR=$_SESSION['FiltreFormPlateforme_Organisme'];
							$req="SELECT Id, Libelle FROM form_organisme WHERE Suppr=0 ORDER BY Libelle ASC ";
							$resultOrganisme=mysqli_query($bdd,$req);
							while($rowOrganisme=mysqli_fetch_array($resultOrganisme)){
								$selected="";
								if($organismeR<>""){
									if($organismeR==$rowOrganisme['Id']){$selected="selected";}
								}
								echo "<option value='".$rowOrganisme['Id']."' ".$selected.">".stripslashes($rowOrganisme['Libelle'])."</option>\n";
							}
							
							?>
						</select>
					</td>
					<td width="5%" rowspan="2" align="left" valign="middle">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					<td width="5%" rowspan="2" align="left" valign="middle">
						&nbsp;<a style="text-decoration:none;" href="javascript:OuvreFenetreExport();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
						</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Mots clés";}else{echo "Keywords";}?> : </td>
					<td width="20%" colspan="2">
						<input name="motcles" id="motcles" style="width:200px" value="<?php $motcle=$_SESSION['FiltreFormPlateforme_MotCle']; echo $motcle; ?>"/>
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
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout(<?php echo $Plateforme; ?>)'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter une formation";}else{echo "Add training";} ?>&nbsp;</a>
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
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationPlateforme.php?Tri=Reference">&nbsp;<?php if($LangueAffichage=="FR"){echo "Référence";}else{echo "Reference";} ?><?php if($_SESSION['TriFormPlateforme_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormPlateforme_Reference']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationPlateforme.php?Tri=Type">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";} ?><?php if($_SESSION['TriFormPlateforme_Type']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormPlateforme_Type']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="4%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationPlateforme.php?Tri=Recyclage">&nbsp;<?php if($LangueAffichage=="FR"){echo "Recyclage différent";}else{echo "Different recycling";} ?><?php if($_SESSION['TriFormPlateforme_Recyclage']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormPlateforme_Recyclage']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Intitulés";}else{echo "Titles";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Qualifications acquises";}else{echo "Qualifications acquired";} ?></td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Coût";}else{echo "Cost";} ?></td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Nb jours";}else{echo "Number of days";} ?></td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Durée (h)";}else{echo "Duration (h)";} ?></td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Doc. complémentaires";}else{echo "Additional documents";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				//FORMATIONS SMQ + PLATEFORME
				$requeteFormation="SELECT Id, Id_Plateforme, Reference, Id_TypeFormation, 
								(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation, 
								Tuteur, Recyclage, Id_Personne_MAJ,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) as Personne_MAJ, Date_MAJ 
								FROM form_formation WHERE Suppr=0 
								AND (Id_Plateforme=0 OR Id_Plateforme=".$Plateforme.")  ";
				if($TypeForm>0){
					$requeteFormation.="AND Id_TypeFormation=".$TypeForm." ";
				}
				if($_SESSION['TriFormPlateforme_General']<>""){
					$requeteFormation.="ORDER BY ".substr($_SESSION['TriFormPlateforme_General'],0,-1);
				}
				$resultFormation=mysqli_query($bdd,$requeteFormation);
				$nbFormation=mysqli_num_rows($resultFormation);
				
				//QUALIFICATIONS
				$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,
										new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif 
										FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre
										WHERE form_formation_qualification.Id_Qualification=new_competences_qualification.Id
										AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
										AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id
										AND form_formation_qualification.Suppr=0 
										AND form_formation_qualification.Masquer=0
										ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, 
										new_competences_categorie_qualification.Libelle ASC,
										new_competences_qualification.Libelle ASC";
				$resultQualifications=mysqli_query($bdd,$requeteQualifications);
				$nbQualifs=mysqli_num_rows($resultQualifications);
				
				$requeteInfos="SELECT Id,Id_Formation,Id_Langue,
								(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,
								Libelle,Description,LibelleRecyclage,DescriptionRecyclage 
								FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Langue";
				$resultInfos=mysqli_query($bdd,$requeteInfos);
				$nbInfos=mysqli_num_rows($resultInfos);
				
				//PARAMETRE PLATEFORME
				$requeteParam="SELECT Id,Id_Formation,Id_Langue,CoutSalarieAAA,CoutInterimaire,Duree,CoutSalarieAAARecyclage,CoutInterimaireRecyclage,DureeRecyclage,NbJour,NbJourRecyclage,Id_Organisme,";
				$requeteParam.= "(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme, CoutTarifGroupe, CoutTarifGroupeRecyclage ";
				$requeteParam.="FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$Plateforme." ";
				$resultParam=mysqli_query($bdd,$requeteParam);
				$nbParam=mysqli_num_rows($resultParam);
				
				//Liste des QCM 
				$req="SELECT form_formation_qualification_qcm.Id_Formation_Qualification, form_formation_qualification_qcm.Id_QCM, 
					form_qcm.Code,form_formation_qualification_qcm.Id_Langue,form_qcm.Suppr, ";
				$req.="(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_formation_qualification_qcm.Id_Langue) AS Langue, ";
				$req.="(SELECT Id FROM form_qcm_langue WHERE Id_QCM=form_qcm.Id AND Suppr=0 AND Id_Langue=form_formation_qualification_qcm.Id_Langue LIMIT 1) AS Id_QCMLangue ";
				$req.="FROM form_formation_qualification_qcm LEFT JOIN form_qcm ";
				$req.="ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id ";
				$req.="WHERE form_formation_qualification_qcm.Suppr=0 ORDER BY Code";
				$resultQCM=mysqli_query($bdd,$req);
				$nbResultaQCM=mysqli_num_rows($resultQCM);
				
				//Liste des documents 
				$req="SELECT form_formation_document.Id_Formation, form_formation_document.Id_Document, form_document.Reference ";
				$req.="FROM form_formation_document LEFT JOIN form_document ";
				$req.="ON form_formation_document.Id_Document=form_document.Id ";
				$req.="WHERE form_formation_document.Suppr=0 ORDER BY Reference";
				$resultDoc=mysqli_query($bdd,$req);
				$nbResultaDoc=mysqli_num_rows($resultDoc);
				
				if ($nbFormation>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultFormation)){
						$Id_Langue=0;
						$Cout="";
						$Duree="";
						$CoutR="";
						$DureeR="";
						$NbJour="";
						$NbJourR="";
						$CoutInterim="";
						$CoutInterimR="";
						$Organisme="";
						$Id_Organisme="";
						$CoutTarifGroupe="";
						$CoutTarifGroupeR="";
						if($nbParam>0){
							mysqli_data_seek($resultParam,0);
							while($rowParam=mysqli_fetch_array($resultParam)){
								if($rowParam['Id_Formation']==$row['Id']){
									$Id_Langue=$rowParam['Id_Langue'];
									$Cout=$rowParam['CoutSalarieAAA'];
									$CoutInterim=$rowParam['CoutInterimaire'];
									$Duree=$rowParam['Duree'];
									$NbJour=$rowParam['NbJour'];
									$CoutR=$rowParam['CoutSalarieAAARecyclage'];
									$CoutInterimR=$rowParam['CoutInterimaireRecyclage'];
									$DureeR=$rowParam['DureeRecyclage'];
									$NbJourR=$rowParam['NbJourRecyclage'];
									$Organisme=$rowParam['Organisme'];
									$Id_Organisme=$rowParam['Id_Organisme'];
									$CoutTarifGroupe=$rowParam['CoutTarifGroupe'];
									$CoutTarifGroupeR=$rowParam['CoutTarifGroupeRecyclage'];
								}
							}
						}
						$Infos="";
						if($nbInfos>0){
							mysqli_data_seek($resultInfos,0);
							while($rowInfo=mysqli_fetch_array($resultInfos)){
								if($rowInfo['Id_Formation']==$row['Id'] && $rowInfo['Id_Langue']==$Id_Langue){
									$Infos=stripslashes($rowInfo['Libelle'])."";
								}
							}
						}
						if($Infos==""){
							if($LangueAffichage=="FR"){$Infos="A DEFINIR";}else{$Infos="TO DEFINE";}
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
													$qcm.="&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreExcel('".$rowQCM['Id_QCM']."','".$rowQCM['Id_QCMLangue']."');\">";
													$qcm.="<img src='../../Images/Tableau.gif' style='border:0;' alt='QCM'>";
													$qcm.="</a>";
												}
												else{
													$qcm.="<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:red;'>QCM : ".$rowQCM['Code']." (".$rowQCM['Langue'].")</span>";
													$qcm.="&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreExcel('".$rowQCM['Id_QCM']."','".$rowQCM['Id_QCMLangue']."');\">";
													$qcm.="<img src='../../Images/Tableau.gif' style='border:0;' alt='QCM'>";
													$qcm.="</a>";
	
												}
											}
										}
									}
									$qualifications.="<tr bgcolor=".$couleur.">";
									//$qualifications.="<td id='leHover'>&bull; ".stripslashes($rowQualif['Qualif']).$qcm."<span>Catégorie maitre : ".stripslashes($rowQualif['QualifMaitre'])."<br>Catégorie : ".stripslashes($rowQualif['CategorieQualif'])."</span></td>";
									$qualifications.="<td>&bull; ".stripslashes($rowQualif['Qualif']).$qcm."</td>";
									$qualifications.="</tr>";
									$nb++;
								}
							}
						}
						
						$doc="";
						if($nbResultaDoc>0){
							mysqli_data_seek($resultDoc,0);
							while($rowDoc=mysqli_fetch_array($resultDoc)){
								if($rowDoc['Id_Formation']==$row['Id']){
									$doc.="".$rowDoc['Reference']."<br>";
								}
							}
						}
						
						$btrouve=1;
						if($motcle<>""){
							if(stripos($row['Reference'],$motcle)===false && stripos($Infos,$motcle)===false && stripos($row['TypeFormation'],$motcle)===false && stripos($qualifications,$motcle)===false){
								$btrouve=0;
							}
						}
						if($organismeR<>"0"){
							if($Id_Organisme<>$organismeR){$btrouve=0;}
						}
						
						if($btrouve==1){
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td valign="middle" rowspan="<?php echo $nb;?>">&nbsp;<?php echo $row['Reference'];?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $row['TypeFormation'];?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php if($row['Recyclage']==1){echo "Oui";}else{echo "Non";}; ?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $Infos;?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $Organisme;?></td>
									<td valign="middle"><?php echo "";?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php if($Cout<>""){if($row['Recyclage']==1){echo "Salarié AAA : ".$Cout."<br>Intérimaire : ".$CoutInterim."<br>Tarif groupe : ".$CoutTarifGroupe."<br><U>Recyclage</U><br>Salarié AAA : ".$CoutR."<br>Intérimaire : ".$CoutInterimR."<br>Tarif groupe : ".$CoutTarifGroupeR;}else{echo "Salarié AAA : ".$Cout."<br>Intérimaire : ".$CoutInterim."<br>Tarif groupe : ".$CoutTarifGroupe;}}else{if($LangueAffichage=="FR"){echo "A DEFINIR";}else{echo "TO DEFINE";}} ?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php if($NbJour<>""){if($row['Recyclage']==1){echo $NbJour."<br>Recyclage : ".$NbJourR;}else{echo $NbJour;}}else{if($LangueAffichage=="FR"){echo "A DEFINIR";}else{echo "TO DEFINE";}} ?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>"><?php 
									if($Duree<>""){
										if($row['Recyclage']==1)
											echo str_replace(".", ":", $Duree)."<br>Recyclage : ".str_replace(".", ":", $DureeR);
										else
											echo str_replace(".", ":", $Duree);
									}
									else{
										if($LangueAffichage=="FR"){echo "A DEFINIR";}else{echo "TO DEFINE";}
									}
									 ?></td>
									 <td valign="middle" rowspan="<?php echo $nb;?>"><?php echo $doc;?></td>
									<td valign="middle" rowspan="<?php echo $nb;?>" align="center">
										<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $Plateforme; ?>)">
										<img src='../../Images/Modif.gif' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
										</a>
									</td>
									<td valign="middle" rowspan="<?php echo $nb;?>" align="center">
										<?php if($row['Id_Plateforme']<>0){ 
											//Verifier si cette formation n'a pas des besoins en cours
											$nbSession=0;
											$nbB=0;
											$reqB="SELECT Id 
													FROM form_besoin
													WHERE Suppr=0
													AND Valide=1
													AND Id_Prestation IN (
														SELECT Id 
														FROM new_competences_prestation
														WHERE Id_Plateforme=".$Plateforme."
														)
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
													AND form_session_date.Id_Session IN (
														SELECT form_session_prestation.Id_Session
														FROM form_session_prestation
														WHERE form_session_prestation.Suppr=0 
														AND form_session_prestation.Id_Prestation IN (
															SELECT Id 
															FROM new_competences_prestation
															WHERE Id_Plateforme=".$Plateforme."
														)
													)
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
														AND form_besoin.Id_Prestation IN (
															SELECT Id 
															FROM new_competences_prestation
															WHERE Id_Plateforme=".$Plateforme."
														)
														)>0
													)";
											$resultSession=mysqli_query($bdd,$reqSession);
											$nbSession=mysqli_num_rows($resultSession);
											
										?>
										<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $Plateforme; ?>,<?php echo $nbB; ?>,<?php echo $nbSession; ?>)">
										<img src='../../Images/Suppression.gif' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
										</a>
										<?php } ?>
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
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>