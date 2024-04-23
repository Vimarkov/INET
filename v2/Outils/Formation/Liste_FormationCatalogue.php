<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function AfficherDiv(Id){
		if(document.getElementById("div_"+Id).style.height==""){
			document.getElementById("div_"+Id).style.height="30px";
			document.getElementById("img_"+Id).src="../../Images/3points.png";
		}
		else{
			document.getElementById("div_"+Id).style.height="";
			document.getElementById("img_"+Id).src="../../Images/Moins.gif";
		}
	}
	function OuvreFenetreExport(){
		var w=window.open("FormationsCatalogue_Extract.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&type="+document.getElementById('typeFormation').value+"&recyclage="+document.getElementById('recyclage').value+"&motcle="+document.getElementById('motcles').value,"PageExport","status=no,menubar=no,scrollbars=yes,width=90,height=60");
		w.blur();
		}
	function OuvreFenetreBesoin(Id_TypeFormation,Id_Formation){
		var w= window.open("Ajout_Besoin_Formation.php?Mode=A&Id=0&Id_Formation="+Id_Formation+"&Id_TypeFormation="+Id_TypeFormation+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value,"PageBesoinFormation","status=no,menubar=no,width=620,height=450");
		w.focus();
	}
</script>
<?php
if($_POST){
	$_SESSION['FiltreCatalogueForm_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreCatalogueForm_Type']=$_POST['typeFormation'];
	$_SESSION['FiltreCatalogueForm_Recyclage']=$_POST['recyclage'];
	$_SESSION['FiltreCatalogueForm_MotCle']=$_POST['motcles'];
	$_SESSION['FiltreCatalogueForm_Organisme']=$_POST['organismeR'];
}
if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Reference"){
		$_SESSION['TriCatalogueForm_General']= str_replace("Reference ASC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("Reference DESC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("Reference ASC","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("Reference DESC","",$_SESSION['TriCatalogueForm_General']);
		if($_SESSION['TriCatalogueForm_Reference']==""){$_SESSION['TriCatalogueForm_Reference']="ASC";$_SESSION['TriCatalogueForm_General'].= "Reference ".$_SESSION['TriCatalogueForm_Reference'].",";}
		elseif($_SESSION['TriCatalogueForm_Reference']=="ASC"){$_SESSION['TriCatalogueForm_Reference']="DESC";$_SESSION['TriCatalogueForm_General'].= "Reference ".$_SESSION['TriCatalogueForm_Reference'].",";}
		else{$_SESSION['TriCatalogueForm_Reference']="";}
	}
	if($_GET['Tri']=="Type"){
		$_SESSION['TriCatalogueForm_General']= str_replace("TypeFormation ASC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("TypeFormation DESC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("TypeFormation ASC","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("TypeFormation DESC","",$_SESSION['TriCatalogueForm_General']);
		if($_SESSION['TriCatalogueForm_Type']==""){$_SESSION['TriCatalogueForm_Type']="ASC";$_SESSION['TriCatalogueForm_General'].= "TypeFormation ".$_SESSION['TriCatalogueForm_Type'].",";}
		elseif($_SESSION['TriCatalogueForm_Type']=="ASC"){$_SESSION['TriCatalogueForm_Type']="DESC";$_SESSION['TriCatalogueForm_General'].= "TypeFormation ".$_SESSION['TriCatalogueForm_Type'].",";}
		else{$_SESSION['TriCatalogueForm_Type']="";}
	}
	if($_GET['Tri']=="Intitule"){
		$_SESSION['TriCatalogueForm_General']= str_replace("form_formation_langue_infos.Libelle ASC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("form_formation_langue_infos.Libelle DESC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("form_formation_langue_infos.Libelle ASC","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("form_formation_langue_infos.Libelle DESC","",$_SESSION['TriCatalogueForm_General']);
		if($_SESSION['TriCatalogueForm_Intitule']==""){$_SESSION['TriCatalogueForm_Intitule']="ASC";$_SESSION['TriCatalogueForm_General'].= "form_formation_langue_infos.Libelle ".$_SESSION['TriCatalogueForm_Intitule'].",";}
		elseif($_SESSION['TriCatalogueForm_Intitule']=="ASC"){$_SESSION['TriCatalogueForm_Intitule']="DESC";$_SESSION['TriCatalogueForm_General'].= "form_formation_langue_infos.Libelle ".$_SESSION['TriCatalogueForm_Intitule'].",";}
		else{$_SESSION['TriCatalogueForm_Intitule']="";}
	}
	if($_GET['Tri']=="Recyclage"){
		$_SESSION['TriCatalogueForm_General']= str_replace("Recyclage ASC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("Recyclage DESC,","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("Recyclage ASC","",$_SESSION['TriCatalogueForm_General']);
		$_SESSION['TriCatalogueForm_General']= str_replace("Recyclage DESC","",$_SESSION['TriCatalogueForm_General']);
		if($_SESSION['TriCatalogueForm_Recyclage']==""){$_SESSION['TriCatalogueForm_Recyclage']="ASC";$_SESSION['TriCatalogueForm_General'].= "Recyclage ".$_SESSION['TriCatalogueForm_Recyclage'].",";}
		elseif($_SESSION['TriCatalogueForm_Recyclage']=="ASC"){$_SESSION['TriCatalogueForm_Recyclage']="DESC";$_SESSION['TriCatalogueForm_General'].= "Recyclage ".$_SESSION['TriCatalogueForm_Recyclage'].",";}
		else{$_SESSION['TriCatalogueForm_Recyclage']="";}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" method="POST" action="Liste_FormationCatalogue.php">
<table style="width:100%; border-spacing : 0;text-align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%;text-align:left; border-spacing:0; background-color:#5f80ff;">
				<tr>
					<td class="TitrePage">
						<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
						if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";

						if($LangueAffichage=="FR"){echo "Catalogue de formations";}else{echo "Training Catalog";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table class="TableCompetences" style="width:100%;text-align:left; border-spacing:0;">
				<tr>
					<td class="Libelle" width="6%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
					<td width="10%">
						<select id="Id_Plateforme" name="Id_Plateforme" onchange="submit()">
							<?php
							$Plateforme=$_SESSION['FiltreCatalogueForm_Plateforme'];
							$resultPlateforme=mysqli_query($bdd,"
								SELECT DISTINCT Id_Plateforme, 
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM new_competences_personne_poste_plateforme 
								WHERE Id_Personne=".$IdPersonneConnectee." 
								UNION 
								SELECT DISTINCT Id_Plateforme,(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM new_competences_personne_plateforme 
								WHERE Id_Personne=".$IdPersonneConnectee." 
								ORDER BY Libelle ");
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
					<td class="Libelle" width="5%"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> : </td>
					<td width="12%">
						<select id="typeFormation" name="typeFormation" onchange="submit()">
							<option value="0"></option>
							<?php 
							$typeFormation=$_SESSION['FiltreCatalogueForm_Type']; 
							$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
							$selected="";
							while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation)){
								$selected="";
								if($typeFormation<>""){
									if($typeFormation==$rowTypeFormation['Id']){$selected="selected";}
								}
								echo "<option ".$selected." value='".$rowTypeFormation['Id']."'>".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
							}
							
							?>
						</select>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Formation recyclage";}else{echo "Recycling training";}?> : </td>
					<td width="8%">
						<select id="recyclage" name="recyclage" onchange="submit()">
							<?php $recyclage=$_SESSION['FiltreCatalogueForm_Recyclage']; ?>
							<option value="" <?php if($recyclage==""){echo "selected";} ?>></option>
							<option value="0" <?php if($recyclage=="0"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
							<option value="1" <?php if($recyclage=="1"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
						</select>
					</td>
					<td width="5%" rowspan="2" valign="middle" align="left">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
						&nbsp;<a style="text-decoration:none;" href="javascript:OuvreFenetreExport();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
						</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="Libelle" width="8%"><?php if($LangueAffichage=="FR"){echo "Mots clés";}else{echo "Keywords";}?> : </td>
					<td width="15%">
						<input name="motcles" id="motcles" value="<?php $motcle=$_SESSION['FiltreCatalogueForm_MotCle']; echo $motcle; ?>"/>
					</td>
					<td class="Libelle" width="8%"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";}?> : </td>
					<td width="15%">
						<input name="organismeR" id="organismeR" value="<?php $organismeR=$_SESSION['FiltreCatalogueForm_Organisme'];echo $organismeR; ?>"/>
					</td>
				</tr>
			</table>
	</td></tr>
	<tr><td height="8"></td></tr>
	<tr><td>
		<div style="width:100%;height:400px;overflow:auto;">
		<table style="border-spacing:0; text-align:left;" style="width:100%;">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationCatalogue.php?Tri=Reference">&nbsp;<?php if($LangueAffichage=="FR"){echo "Référence";}else{echo "Reference";} ?><?php if($_SESSION['TriCatalogueForm_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCatalogueForm_Reference']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationCatalogue.php?Tri=Type">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";} ?><?php if($_SESSION['TriCatalogueForm_Type']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCatalogueForm_Type']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Recyclage";}else{echo "Recycling";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FormationCatalogue.php?Tri=Intitule">&nbsp;<?php if($LangueAffichage=="FR"){echo "Intitulés";}else{echo "Title";} ?><?php if($_SESSION['TriCatalogueForm_Intitule']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCatalogueForm_Intitule']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Qualifications acquises";}else{echo "Qualifications acquired";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Nb jours";}else{echo "Number of days";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Nb heures";}else{echo "Number of hours";} ?></td>
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Description";}else{echo "Description";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Document";}else{echo "Document";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;"></td>
			</tr>
			<?php
				//FORMATIONS SMQ + PLATEFORME		
				$requeteFormation="SELECT ";
				$requeteFormation.="		form_formation.Id, ";
				$requeteFormation.="		form_formation.Id_Plateforme, ";
				$requeteFormation.="		Reference, ";
				$requeteFormation.="		Id_TypeFormation, ";
				$requeteFormation.="		(SELECT ";
				$requeteFormation.="				Libelle ";
				$requeteFormation.="		FROM ";
				$requeteFormation.="				form_typeformation ";
				$requeteFormation.="		WHERE ";
				$requeteFormation.="				Id=Id_TypeFormation) AS TypeFormation, ";
				$requeteFormation.="		Tuteur, ";
				$requeteFormation.="		Recyclage, ";
				$requeteFormation.="		form_formation.Id_Personne_MAJ, ";
				$requeteFormation.="		(SELECT ";
				$requeteFormation.="				CONCAT(Nom,' ',Prenom) ";
				$requeteFormation.="		FROM ";
				$requeteFormation.="				new_rh_etatcivil ";
				$requeteFormation.="		WHERE Id=form_formation.Id_Personne_MAJ) as Personne_MAJ, ";
				$requeteFormation.="		form_formation.Date_MAJ ";
				
				$requeteFormation.="FROM ";
				$requeteFormation.="		form_formation, ";
				$requeteFormation.="		form_formation_langue_infos, ";
				$requeteFormation.="		form_formation_plateforme_parametres ";
				$requeteFormation.="WHERE ";
				$requeteFormation.="		form_formation.Suppr=0 ";
				$requeteFormation.="AND (form_formation.Id_Plateforme=0 OR form_formation.Id_Plateforme=".$Plateforme.") ";
				$requeteFormation.="AND form_formation_langue_infos.Id_Formation = form_formation.Id ";
				$requeteFormation.="AND form_formation_langue_infos.Suppr = 0 ";
				$requeteFormation.="AND form_formation_langue_infos.Id_Langue = form_formation_plateforme_parametres.Id_Langue ";
				$requeteFormation.="AND form_formation_plateforme_parametres.Id_Formation = form_formation.Id ";
				$requeteFormation.="AND form_formation_plateforme_parametres.Id_Plateforme = ".$Plateforme." ";
				if($typeFormation<>"0"){
					$requeteFormation.="AND form_formation.Id_TypeFormation=".$typeFormation." ";
				}
				if($_SESSION['TriCatalogueForm_General']<>""){
					$requeteFormation.="ORDER BY ".substr($_SESSION['TriCatalogueForm_General'],0,-1);
				}
				$resultFormation=mysqli_query($bdd,$requeteFormation);
				$nbFormation=mysqli_num_rows($resultFormation);
				
				//QUALIFICATIONS
				$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif,new_competences_qualification.Duree_Validite ";
				$requeteQualifications.=" FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre";
				$requeteQualifications.=" WHERE ";
				$requeteQualifications.=" form_formation_qualification.Id_Qualification=new_competences_qualification.Id";
				$requeteQualifications.=" AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id";
				$requeteQualifications.=" AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id";
				$requeteQualifications.=" AND form_formation_qualification.Suppr=0 AND form_formation_qualification.Masquer=0 ";
				$requeteQualifications.=" ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, new_competences_categorie_qualification.Libelle ASC,new_competences_qualification.Libelle ASC";
				$resultQualifications=mysqli_query($bdd,$requeteQualifications);
				$nbQualifs=mysqli_num_rows($resultQualifications);
				
				$requeteInfos="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage,Fichier,FichierRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Libelle, Langue";
				$resultInfos=mysqli_query($bdd,$requeteInfos);
				$nbInfos=mysqli_num_rows($resultInfos);
				
				//PARAMETRE PLATEFORME
				$requeteParam="SELECT Id,Id_Formation,Id_Langue,Duree,DureeRecyclage,NbJour,NbJourRecyclage, ";
				$requeteParam.="(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme ";
				$requeteParam.= "FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$Plateforme." ";
				$resultParam=mysqli_query($bdd,$requeteParam);
				$nbParam=mysqli_num_rows($resultParam);
				
				if ($nbFormation>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultFormation)){
						
						$Id_Langue=0;
						$Duree="";
						$DureeR="";
						$NbJour="";
						$NbJourR="";
						$Organisme="";
						if($nbParam>0){
							mysqli_data_seek($resultParam,0);
							while($rowParam=mysqli_fetch_array($resultParam)){
								if($rowParam['Id_Formation']==$row['Id']){
									$Id_Langue=$rowParam['Id_Langue'];
									$Duree=$rowParam['Duree'];
									$DureeR=$rowParam['DureeRecyclage'];
									$NbJour=$rowParam['NbJour'];
									$NbJourR=$rowParam['NbJourRecyclage'];
									if($rowParam['Organisme']<>""){
										$Organisme="<br>Organisme : ".$rowParam['Organisme'];
									}
								}
							}
						}
						$Infos="";
						$Description="";
						$InfosRecyclage="";
						$DescriptionRecyclage="";
						$Fichier="";
						$FichierRecyclage="";
						if($nbInfos>0){
							mysqli_data_seek($resultInfos,0);
							while($rowInfo=mysqli_fetch_array($resultInfos)){
								if($rowInfo['Id_Formation']==$row['Id'] && $rowInfo['Id_Langue']==$Id_Langue){
									$Infos=trim(stripslashes($rowInfo['Libelle']).""," ");
									$Description=nl2br(stripslashes($rowInfo['Description']))."";
									$InfosRecyclage=trim(stripslashes($rowInfo['LibelleRecyclage']).""," ");
									$DescriptionRecyclage=nl2br(stripslashes($rowInfo['DescriptionRecyclage']))."";
									$Fichier=$rowInfo['Fichier']."";
									$FichierRecyclage=$rowInfo['FichierRecyclage']."";
								}
							}
						}
						
						$couleur2=$couleur;
						$nb=1;
						$qualifications="";
						$qualificationsRecyclage="";
						if($nbQualifs>0){
							mysqli_data_seek($resultQualifications,0);
							while($rowQualif=mysqli_fetch_array($resultQualifications)){
								if($rowQualif['Id_Formation']==$row['Id']){
									$border="";
									$qualifications.="<tr bgcolor=".$couleur.">";
									$qualifications.="<td id='leHover'>	&bull; ".stripslashes($rowQualif['Qualif'])." (Validité : ".$rowQualif['Duree_Validite']." mois)<span>Catégorie maitre : ".stripslashes($rowQualif['QualifMaitre'])."<br>Catégorie : ".stripslashes($rowQualif['CategorieQualif'])."</span></td>";
									$qualifications.="</tr>";
									$nb++;
									
									if($recyclage=="0" || $recyclage==""){
										if($couleur=="#ffffff"){$couleur2="#b1daeb";}
										else{$couleur2="#ffffff";}
									}
									$qualificationsRecyclage.="<tr bgcolor=".$couleur2.">";
									$qualificationsRecyclage.="<td ".$border." id='leHover'>&bull; ".stripslashes($rowQualif['Qualif'])." (Validité : ".$rowQualif['Duree_Validite']." mois)<span>Catégorie maitre : ".stripslashes($rowQualif['QualifMaitre'])."<br>Catégorie : ".stripslashes($rowQualif['CategorieQualif'])."</span></td>";
									$qualificationsRecyclage.="</tr>";
								}
							}
						}
						$btrouve=1;
						if($motcle<>""){
							if(stripos($row['Reference'],$motcle)===false && stripos($Description,$motcle)===false && stripos($Infos,$motcle)===false && stripos($qualifications,$motcle)===false){
								$btrouve=0;
							}
						}
						if($organismeR<>""){
							if(stripos($Organisme,$organismeR)===false){$btrouve=0;}
						}
						if($btrouve==1 && ($recyclage=="0" || $recyclage=="") ){
							$rowspanQ="";
							if($nb>1){$rowspanQ="rowspan='2'";}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td style="valign:center;" rowspan="<?php echo $nb;?>">&nbsp;<?php echo $row['Reference'];?></td>
									<td style="valign:center;" rowspan="<?php echo $nb;?>"><?php echo $row['TypeFormation'];?></td>
									<td style="valign:center;" rowspan="<?php echo $nb;?>"><?php echo ""; ?></td>
									<td style="valign:center;" rowspan="<?php echo $nb;?>"><b><?php echo $Infos.$Organisme;?></b></td>
									<td style="valign:center;"><?php echo "";?></td>
									<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>"><?php if($NbJour<>"" && $NbJour<>"0"){echo $NbJour;}else{echo "";} ?></td>
									<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>"><?php if($Duree<>""){echo $Duree;}else{echo "";} ?></td>
									<td style="valign:center;" rowspan="<?php echo $nb;?>"><div id="div_<?php echo $row['Id']; ?>" style="overflow:hidden;height:30px;" ><?php echo $Description; if($Description<>""){?></div><img id="img_<?php echo $row['Id']; ?>" onclick="AfficherDiv('<?php echo $row['Id'];?>')" width="10px" src='../../Images/3points.png' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Suite";}else{echo "Suite";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Suite";}else{echo "Suite";} ?>'><?php } ?></td>
									<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>">
									<?php 
										if($Fichier<>""){
											echo "<a class='Info' href='Docs/Formation/".$row['Id']."/".$Fichier."' target='_blank'>";
											echo "<img style='width:18px;height:18px;' src='../../Images/Tableau.gif' border='0' alt='Document' title='Document'>";
											echo "</a>";
										}
									?>
									</td>
									<td style="valign:center;text-align:center;" valign="middle" rowspan="<?php echo $nb;?>">
									<?php 
										if(DroitsFormationPlateforme($TableauIdPostesAF_RF) || $_SERVER['SERVER_NAME']=="192.168.20.3"){
										if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ)){ 
										if($row['Id_TypeFormation']<>"1"){
									?>
										&nbsp;<a style="text-decoration:none;" href="javascript:OuvreFenetreBesoin(<?php echo $row['Id_TypeFormation'] ?>,<?php echo $row['Id'] ?>);">
											<img src="../../Images/B.png" width="20px" border="0" alt="Générer un besoin" title="Générer un besoin">
										</a>&nbsp;
									<?php 
										}
										}
									} ?>
									</td>
								</tr>
							<?php
							if($couleur=="#ffffff"){
								$qualifications=str_replace("#b1daeb",$couleur,$qualifications);
							}
							else{
								$qualifications=str_replace("#ffffff",$couleur,$qualifications);
							}
							echo $qualifications;
							if($couleur=="#ffffff"){$couleur="#b1daeb";}
							else{$couleur="#ffffff";}
						}
						
						if($btrouve==1 && $row['Recyclage']==1){
							$btrouve=1;
							if($motcle<>""){
								if(stripos($row['Reference'],$motcle)===false && stripos($DescriptionRecyclage,$motcle)===false && stripos($InfosRecyclage,$motcle)===false && stripos($row['TypeFormation'],$motcle)===false && stripos($qualificationsRecyclage,$motcle)===false && stripos($Organisme,$motcle)===false){
									$btrouve=0;
								}
								else{
									$btrouve=1;
								}
							}
							if($btrouve==1 && ($recyclage=="1" || $recyclage=="")){
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td style="valign:center;" rowspan="<?php echo $nb;?>">&nbsp;<?php echo $row['Reference'];?></td>
										<td style="valign:center;" rowspan="<?php echo $nb;?>"><?php echo $row['TypeFormation'];?></td>
										<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>"><?php echo "X"; ?></td>
										<td style="valign:center;" rowspan="<?php echo $nb;?>"><b><?php echo $InfosRecyclage.$Organisme;?></b></td>
										<td style="valign:center;"><?php echo "";?></td>
										<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>"><?php if($NbJourR<>"" && $NbJourR<>"0"){echo $NbJourR;}else{echo "";} ?></td>
										<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>"><?php if($DureeR<>""){echo $DureeR;}else{echo "";} ?></td>
										<td style="valign:center;" rowspan="<?php echo $nb;?>"><div id="div_R<?php echo $row['Id']; ?>" style="overflow:hidden;height:30px;" ><?php echo $DescriptionRecyclage; if($Description<>""){?></div><img id="img_<?php echo $row['Id']; ?>" onclick="AfficherDiv('<?php echo "R".$row['Id'];?>')" width="10px" src='../../Images/3points.png' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Suite";}else{echo "Suite";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Suite";}else{echo "Suite";} ?>'><?php } ?></td>
										<td style="valign:center;text-align:center;" rowspan="<?php echo $nb;?>">
										<?php 
											if($FichierRecyclage<>""){
												echo "<a class='Info' href='Docs/Formation/".$row['Id']."/".$FichierRecyclage."' target='_blank'>";
												echo "<img style='width:18px;height:18px;' src='../../Images/Tableau.gif' border='0' alt='Document' title='Document'>";
												echo "</a>";
											}
										?>
										</td>
										<td style="valign:center;text-align:center;" valign="middle" rowspan="<?php echo $nb;?>">
										</td>
									</tr>
								<?php
								if($couleur=="#ffffff"){
									$qualificationsRecyclage=str_replace("#b1daeb",$couleur,$qualificationsRecyclage);
								}
								else{
									$qualificationsRecyclage=str_replace("#ffffff",$couleur,$qualificationsRecyclage);
								}
								echo $qualificationsRecyclage;
								if($couleur=="#ffffff"){$couleur="#b1daeb";}
								else{$couleur="#ffffff";}
							}
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