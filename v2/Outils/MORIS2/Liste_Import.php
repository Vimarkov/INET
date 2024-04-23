<script language="javascript">

</script>
<?php
$ResultatImport="";

$DirFichier="Import/Extract_NC.xlsx";
if($_POST){
	if(isset($_POST['btnImporter'])){
		if($_FILES['fichier']['name']!=""){
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){
				if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier est introuvable</td></tr>";}
				else{$ResultatImport.="<tr><td>File not found</td></tr>";}
			}
			else{
				//On verifie l'extension
				$type_file=strrchr($_FILES['fichier']['name'], '.'); 
				if($type_file !='.xlsx'){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier doit être au format .xlsx</td></tr>";}
					else{$ResultatImport.="<tr><td>The file must be in .xlsx format</td></tr>";}
				}
				else
				{
					//On vérifie la taille du fichier
					if(filesize($_FILES['fichier']['tmp_name'])>30000000){
						if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier est trop volumineux</td></tr>";}
						else{$ResultatImport.="<tr><td>The file is too large</td></tr>";}
					}
					else{
						if(file_exists($DirFichier)){
							if(!unlink($DirFichier)){
								
							}
						}
						if(!move_uploaded_file($tmp_file,$DirFichier)){
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Impossible de copier le fichier</td></tr>";}
							else{$ResultatImport.="<tr><td>Could not copy file</td></tr>";}
						}
					}
				}
			}
			if($ResultatImport==""){
				class chunkReadFilter implements PHPExcel_Reader_IReadFilter {
					private $_startRow = 0;
					private $_endRow = 0;

					//  Set the list of rows that we want to read 
					public function setRows($startRow, $chunkSize) { 
						$this->_startRow    = $startRow; 
						$this->_endRow      = $startRow + $chunkSize;
					} 

					public function readCell($column, $row, $worksheetName = '') {
						//  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow 
						if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) { 
						   return true;
						}
						return false;
					} 
				}
				
				$XLSXDocument = new PHPExcel_Reader_Excel2007();
				
				//  Define how many rows we want to read for each "chunk"  
				$chunkSize = 10000;
				//  Create a new Instance of our Read Filter  
				$chunkFilter = new chunkReadFilter(); 
				//  Tell the Reader that we want to use the Read Filter that we've Instantiated  
				$XLSXDocument->setReadFilter($chunkFilter); 
				
				//Parcours de la première colonne pour vérifier qu'on a toutes les colonnes souhaitées 
				$ColNC="";
				$ColFiche="";
				$ColDateNC="";
				$ColType="";
				$ColNiveau="";
				$ColPresta="";
				$ColTitre="";
				$ColResponsable="";
				$ColEntite="";
				
				$chunkFilter->setRows(1,$chunkSize); 
				$objPHPExcel = $XLSXDocument->load($DirFichier); 
				$sheet = $objPHPExcel->getSheet(0);
				
				$ligne=1;
				for($column = 'A'; $column<>'L'; $column++){
					switch(utf8_decode($sheet->getCell($column.$ligne)->getValue())){
						case "NC REFERENCE":
							$ColNC=$column;
							break;
						case "Fiche #":
							$ColFiche=$column;
							break;
						case "NC DATE":
							$ColDateNC=$column;
							break;
						case "DEVIATION TYPE":
							$ColType=$column;
							break;
						case "CRITICITY":
							$ColNiveau=$column;
							break;
						case "TITLE":
							$ColTitre=$column;
							break;
						case "ACTIVITY":
							$ColPresta=$column;
						case "LIABILITY":
							$ColResponsable=$column;
							break;
						case "ENTITY":
							$ColEntite=$column;
							break;
					}
				}
				
				$bChampsExistes=0;
				if($ColNC==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"NC REFERENCE\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"NC REFERENCE\" column does not exist</td></tr>";}
				}
				if($ColFiche==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Fiche #\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Fiche #\" column does not exist</td></tr>";}
				}
				if($ColDateNC==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"NC DATE\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"NC DATE\" column does not exist</td></tr>";}
				}
				if($ColType==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"DEVIATION TYPE\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"DEVIATION TYPE\" column does not exist</td></tr>";}
				}
				if($ColNiveau==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"CRITICITY\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"CRITICITY\" column does not exist</td></tr>";}
				}
				if($ColTitre==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"TITLE\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"TITLE\" column does not exist</td></tr>";}
				}
				if($ColPresta==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"ACTIVITY\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"ACTIVITY\" column does not exist</td></tr>";}
				}
				if($ColResponsable==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"LIABILITY\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"LIABILITY\" column does not exist</td></tr>";}
				}
				if($ColEntite==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"ENTITY\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"ENTITY\" column does not exist</td></tr>";}
				}
				
				$nbLigne=0;
				$nbAjout=0;
				$nbNonAjout=0;
				if($bChampsExistes==0){
					//Supprimer les données déjà existante sur l'extranet pour ce mois-ci
					$req="UPDATE moris_moisprestation_ncdac
					SET Suppr=1,DateSuppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']."
					WHERE YEAR(DateCreationNC)=".$_POST['annee']."
					AND MONTH(DateCreationNC)=".$_POST['mois']."
					AND Suppr=0 											
					";
					$resultSuppr=mysqli_query($bdd,$req);
					
					$STOP=0;
					for($ligne=2;$ligne<10000;$ligne++){
						if($sheet->getCell($ColFiche.$ligne)->getValue()==""){$STOP=1;}
						if($STOP==0){
							$nbLigne++;
							//Récupérer les valeurs de chaque champs 
							$Responsable=utf8_decode($sheet->getCell($ColResponsable.$ligne)->getValue());
							if($Responsable<>"Client / Customer"){
								$NC=utf8_decode($sheet->getCell($ColNC.$ligne)->getValue());
								$Fiche=utf8_decode($sheet->getCell($ColFiche.$ligne)->getValue());
								if($Fiche<>""){
									if(substr($Fiche,0,1)=="'"){
										$Fiche=substr($Fiche,1);
									}
								}
								$Entite=utf8_decode($sheet->getCell($ColEntite.$ligne)->getValue());
								$DateNC=$sheet->getCell($ColDateNC.$ligne)->getValue();
								$Type=utf8_decode($sheet->getCell($ColType.$ligne)->getValue());
								$Niveau=$sheet->getCell($ColNiveau.$ligne)->getValue();
								$Presta=utf8_decode($sheet->getCell($ColPresta.$ligne)->getValue());
								$Titre=utf8_decode($sheet->getCell($ColTitre.$ligne)->getValue());
								$Titre=addslashes($Titre);
								
								$Id_Prestation=0;
								$bAjout=1;
								$bExiste=0;
								
								if($bAjout==1 && $Fiche==""){
									$bAjout=0;
								}
								
								if($bAjout==1 && $DateNC==""){
									$bAjout=0;
								}
								
								if($bAjout==1 && $Presta==""){
									$bAjout=0;
								}
								
								$leMois=0;
								$laAnnee=0;
								if($DateNC<>""){
									$leMois= date("m", PHPExcel_Shared_Date::ExcelToPHP($DateNC));
									$laAnnee= date("Y", PHPExcel_Shared_Date::ExcelToPHP($DateNC));
									$DateNC =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($DateNC));
								}
								//Vérifier si la NC est sur le mois à importer
								if($laAnnee==$_POST['annee'] && $leMois==$_POST['mois']){
									//Rechercher si cette prestation existe dans la base de données 
									$req="SELECT Id FROM new_competences_prestation WHERE Libelle LIKE \"%".$Presta."%\" AND UtiliseMORIS=1";
									
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$row=mysqli_fetch_array($result);
										$Id_Prestation=$row['Id'];
									}
									
									if($bAjout==1 && $Id_Prestation>0 && $Fiche<>"" 
									&& $Entite<>"AAA Canada" 
									&& $Entite<>"AAA Clark/Philippines" 
									&& $Entite<>"AAA Clark/Philippines "
									&& $Entite<>"AAA Group" 
									&& $Entite<>"AAA Tianjin/China" 
									&& $Entite<>"AAA USA" 
									&& $Entite<>"Maroc" 
									&& $Entite<>"Montreal" 
									&& $Entite<>"Toronto"){
										$Id_MoisPrestation=0;
										//Vérifier si la prestation a déjà rempli des données sur le mois en question
										$req="SELECT Id FROM moris_moisprestation WHERE Suppr=0 AND Id_Prestation=".$Id_Prestation." AND Annee=".$_POST['annee']." AND Mois=".$_POST['mois']." ";
										$resultMP=mysqli_query($bdd,$req);
										$nbResultaMoisPresta=mysqli_num_rows($resultMP);
										if ($nbResultaMoisPresta>0){
											$rowMP=mysqli_fetch_array($resultMP);
											$Id_MoisPrestation=$rowMP['Id'];
										}
										else{
											$annee_M_1=$_POST['annee'];
											$mois_M_1=$_POST['mois']-1;
											if($mois_M_1==0){
												$annee_M_1=$_POST['annee']-1;
												$mois_M_1=12;
											}
											
											$req="SELECT Id,Libelle, RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,Id_Programme,
												(SELECT Id_Personne
												FROM new_competences_personne_poste_prestation 
												WHERE new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id  
												AND Id_Poste=2
												AND Backup=0 LIMIT 1) AS CoorEquipe,
												(SELECT Id_Personne 
												FROM new_competences_personne_poste_prestation 
												WHERE new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id  
												AND Id_Poste=4
												AND Backup=0 LIMIT 1) AS RespProjet,
												Id_EntiteAchat,MailAcheteur,MailDO
											FROM new_competences_prestation
											WHERE new_competences_prestation.Id=".$Id_Prestation." ";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											$Ligne=mysqli_fetch_array($result);
											
											$RefCDC=$Ligne['RefCDC'];
											$IntituleCDC=$Ligne['IntituleCDC'];
											$AcheteurClient=$Ligne['AcheteurClient'];
											$DonneurOrdre=$Ligne['DonneurOrdre'];
											$Sigle=$Ligne['Sigle'];
											$Id_Contrat=$Ligne['Id_Contrat'];
											$Id_Programme=$Ligne['Id_Programme'];
											$Id_EntiteAchat=$Ligne['Id_EntiteAchat'];
											$MailAcheteur=$Ligne['MailAcheteur'];
											$MailDO=$Ligne['MailDO'];
											
											$Id_CoorEquipe=0;
											$Id_RespProjet=0;
											
											if($Ligne['CoorEquipe']>0){$Id_CoorEquipe=$Ligne['CoorEquipe'];}
											if($Ligne['RespProjet']>0){$Id_RespProjet=$Ligne['RespProjet'];}
											
											$DerniereDatePRM="0001-01-01";
											$DerniereDateEvaluation="0001-01-01";
											$PeriodicitePRM="";
											$DateEnvoiDemandeSatisfaction="0001-01-01";
											$FormatAT=0;
											$EvaluationQualite=0;
											$EvaluationDelais=0;
											$EvaluationCompetencePersonnel=0;
											$EvaluationAutonomie=0;
											$EvaluationAnticipation=0;
											$EvaluationCommunication=0;
											$PointFortSatisfaction="";
											$PointFaibleSatisfaction="";
											$CommentaireSatisfaction="";
											$req="SELECT Id,RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,Id_Contrat,
												Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,Id_Programme,
												FormatAT,
												DerniereDatePRM,DerniereDateEvaluation,ProchaineDatePRM,PeriodicitePRM,DateEnvoiDemandeSatisfaction,
												EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
												Verouillage,PasAT,PasNC,PointFortSatisfaction,PointFaibleSatisfaction,CommentaireSatisfaction
											FROM moris_moisprestation
											WHERE moris_moisprestation.Id_Prestation=".$Id_Prestation." 
											AND Annee=".$annee_M_1." 
											AND Mois=".$mois_M_1."
											AND Suppr=0 											
											";
											$resultM1=mysqli_query($bdd,$req);
											$nbResultaMoisPrestaM1=mysqli_num_rows($resultM1);
											if($nbResultaMoisPrestaM1>0){
												$LigneMoisPrestationM1=mysqli_fetch_array($resultM1);
												$DerniereDatePRM=$LigneMoisPrestationM1['DerniereDatePRM'];
												$DerniereDateEvaluation=$LigneMoisPrestationM1['DerniereDateEvaluation'];
												$PeriodicitePRM=$LigneMoisPrestationM1['PeriodicitePRM'];
												$DateEnvoiDemandeSatisfaction=$LigneMoisPrestationM1['DateEnvoiDemandeSatisfaction'];
												$FormatAT=$LigneMoisPrestationM1['FormatAT'];
												$EvaluationQualite=$LigneMoisPrestationM1['EvaluationQualite'];
												$EvaluationDelais=$LigneMoisPrestationM1['EvaluationDelais'];
												$EvaluationCompetencePersonnel=$LigneMoisPrestationM1['EvaluationCompetencePersonnel'];
												$EvaluationAutonomie=$LigneMoisPrestationM1['EvaluationAutonomie'];
												$EvaluationAnticipation=$LigneMoisPrestationM1['EvaluationAnticipation'];
												$EvaluationCommunication=$LigneMoisPrestationM1['EvaluationCommunication'];
												$PointFortSatisfaction=$LigneMoisPrestationM1['PointFortSatisfaction'];
												$PointFaibleSatisfaction=$LigneMoisPrestationM1['PointFaibleSatisfaction'];
												$CommentaireSatisfaction=$LigneMoisPrestationM1['CommentaireSatisfaction'];
												
												$RefCDC=$LigneMoisPrestationM1['RefCDC'];
												$IntituleCDC=$LigneMoisPrestationM1['IntituleCDC'];
												$AcheteurClient=$LigneMoisPrestationM1['AcheteurClient'];
												$DonneurOrdre=$LigneMoisPrestationM1['DonneurOrdre'];
												$Sigle=$LigneMoisPrestationM1['Sigle'];
												$Id_Contrat=$LigneMoisPrestationM1['Id_Contrat'];
												$Id_Programme=$LigneMoisPrestationM1['Id_Programme'];
												$Id_EntiteAchat=$LigneMoisPrestationM1['Id_EntiteAchat'];
												$MailAcheteur=$LigneMoisPrestationM1['MailAcheteur'];
												$MailDO=$LigneMoisPrestationM1['MailDO'];
												$Id_CoorEquipe=$LigneMoisPrestationM1['Id_CoorEquipe'];
												$Id_RespProjet=$LigneMoisPrestationM1['Id_RespProjet'];
											}
											
											
											//Création de la ligne pour le mois 
											$req="INSERT INTO moris_moisprestation (Id_Createur,DateCreation,Id_Prestation,Annee,Mois,RefCDC,Sigle,IntituleCDC,Id_Contrat,Id_Programme,
												AcheteurClient,DonneurOrdre,Id_CoorEquipe,Id_RespProjet,Id_EntiteAchat,MailAcheteur,MailDO,
												DerniereDatePRM,DerniereDateEvaluation,PeriodicitePRM,DateEnvoiDemandeSatisfaction,
												FormatAT,
												EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
												PointFortSatisfaction,PointFaibleSatisfaction,CommentaireSatisfaction
												) 
												VALUES (".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',".$Id_Prestation.",".$_POST['annee'].",".$_POST['mois'].",'".addslashes($RefCDC)."','".addslashes($Sigle)."',
													'".addslashes($IntituleCDC)."',".$Id_Contrat.",".$Id_Programme.",
													'".addslashes($AcheteurClient)."','".addslashes($DonneurOrdre)."',".$Id_CoorEquipe.",".$Id_RespProjet.",".$Id_EntiteAchat.",
													'".addslashes($MailAcheteur)."','".addslashes($MailDO)."',
													'".$DerniereDatePRM."','".$DerniereDateEvaluation."','".$PeriodicitePRM."','".$DateEnvoiDemandeSatisfaction."',".$FormatAT.",
													".$EvaluationQualite.",".$EvaluationDelais.",".$EvaluationCompetencePersonnel.",
													".$EvaluationAutonomie.",".$EvaluationAnticipation.",".$EvaluationCommunication.",'".addslashes($PointFortSatisfaction)."','".addslashes($PointFaibleSatisfaction)."','".addslashes($CommentaireSatisfaction)."'
													)
											";
											
											$result=mysqli_query($bdd,$req);
											$Id_MoisPrestation=mysqli_insert_id($bdd);
										}
										
										if($Id_MoisPrestation>0){
											//Recherche si cette ligne existe déjà dans l'outils
											$req="SELECT Id FROM moris_moisprestation_ncdac WHERE Suppr=0 AND DateCreationNC=\"".$DateNC."\" AND FicheAT=\"".$Fiche."\" AND Id_MoisPrestation=".$Id_MoisPrestation." ";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta==0){
												if($NC==""){$NC=$Fiche;}
												$leType="";
												if($Type=="RECLAMATION CLIENT - CUSTOMER COMPLAINT"){
													$leType="RC";
												}
												elseif($Type=="NC PRODUIT/PRESTATION - PRODUCT NC"){
													if($Niveau==1){$leType="NC";}
													elseif($Niveau==2){$leType="NC Niv 2";}
													elseif($Niveau==3){$leType="NC Niv 3";}
												}
												if($leType<>""){
													$nbAjout++;
													$req="INSERT INTO moris_moisprestation_ncdac (Id_MoisPrestation,Ref,NC_DAC,DateCreationNC,Commentaire,FicheAT,Responsable,Id_Createur,DateCreation) 
														VALUES 
															(".$Id_MoisPrestation.",'".$NC."','".$leType."','".$DateNC."','".$Titre."','".$Fiche."','".$Responsable."',".$_SESSION['Id_Personne'].",'".date('Y-m-d')."')";
													$resultAjout=mysqli_query($bdd,$req);
												}
											}
										}
									}
								}
							}
						}
					}
					
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbAjout." lignes ajoutées </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbAjout." lines added </td></tr>";}

					//Free up some of the memory 
					$objPHPExcel->disconnectWorksheets(); 
					unset($objPHPExcel);
				}
			}
		}
	}
}
?>
<form class="test" enctype="multipart/form-data" action="Liste_Import.php" method="post">
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<br>
	<table class="GeneralInfo" style="width:30%" cellpadding="0" cellspacing="0" align="center">
		<tr><td height="5"></td></tr>
		<tr>
			<td class="Libelle" width="25%">
				&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Année";} ?>&nbsp;&nbsp;
			</td>
			<td class="Libelle" width="25%">
				<select id="annee" name="annee">
					<?php
						$annee=$_SESSION['MORIS_Annee2'];
						if($_POST){$annee=$_POST['annee'];}
						$_SESSION['MORIS_Annee2']=$annee;
					?>
					<option value="<?php echo date('Y')-1; ?>" <?php if($annee==date('Y')-1){echo "selected";} ?>><?php echo date('Y')-1; ?></option>
					<option value="<?php echo date('Y'); ?>" <?php if($annee==date('Y')){echo "selected";} ?>><?php echo date('Y'); ?></option>
					<option value="<?php echo date('Y')+1; ?>" <?php if($annee==date('Y')+1){echo "selected";} ?>><?php echo date('Y')+1; ?></option>
				</select>
			</td>
			<td class="Libelle" width="25%">
				&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?>&nbsp;&nbsp;
			</td>
			<td class="Libelle" width="25%">
				<select id="mois" name="mois">
					<?php
						if($_SESSION["Langue"]=="EN"){
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
							
						}
						else{
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						$mois=$_SESSION['MORIS_Mois2'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['MORIS_Mois2']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td class="Libelle" width="10%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier NC Action tracker";}else{echo "NC Action tracker file";}?> : </td>
			<td width="20%"><input name="fichier" type="file" onChange="CheckFichier();"></td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td colspan="4" align="center">
				<input class="Bouton" type="submit" id="btnImporter" name="btnImporter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td colspan="4">
				<table width="100%" cellpadding="0" cellspacing="0" align="center">
					<?php
						echo $ResultatImport;
					?>	
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
</form>