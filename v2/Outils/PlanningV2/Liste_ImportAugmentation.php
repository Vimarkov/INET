<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvrirTemplate()
		{var w=window.open("OuvrirTemplateAugmentation.php","PageContrat","status=no,menubar=no,width=100,height=100");
		w.focus();
		}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
$ResultatImport="";
$ResultatImport1="";
$DirFichier="Augmentation/Extract_Augmentation.xlsx";
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

					/**  Set the list of rows that we want to read  */ 
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
				
				/**  Define how many rows we want to read for each "chunk"  **/ 
				$chunkSize = 5000;
				/**  Create a new Instance of our Read Filter  **/ 
				$chunkFilter = new chunkReadFilter(); 
				/**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/ 
				$XLSXDocument->setReadFilter($chunkFilter); 
				$chunkFilter->setRows(1,$chunkSize); 
				$objPHPExcel = $XLSXDocument->load($DirFichier); 
				$sheet = $objPHPExcel->getSheet(0);
				
				//Parcours de la première colonne pour vérifier qu'on a toutes les colonnes souhaitées 
				$ColMatriculeAAA="A";
				$ColMetier="B";
				$ColNiveau="C";
				$ColCoeff="D";
				$ColIndice="E";
				$ColHoraire="F";
				$ColSalaire="G";
				$Mois=$sheet->getCell('B1')->getValue();
				$Annee=$sheet->getCell('E1')->getValue();

				$nbLigne=0;
				$nbAjout=0;
				$nbNonAjout=0;
				$nbExisteDeja=0;
				$STOP=0;
				if(($Mois==1 || $Mois==2 || $Mois==3 || $Mois==4 || $Mois==5 || $Mois==6 || $Mois==7 || $Mois==8 || $Mois==9 || $Mois==10 || $Mois==11 || $Mois==12) && $Annee<>"" && is_numeric($Annee)){
					for($ligne=3;$ligne<4000;$ligne++){
						if($sheet->getCell($ColMatriculeAAA.$ligne)->getValue()==""){$STOP=1;}
						if($STOP==0){
							$nbLigne++;
							//Récupérer les valeurs de chaque champs 
							$MatriculeAAA=utf8_decode($sheet->getCell($ColMatriculeAAA.$ligne)->getValue());
							if($MatriculeAAA<>""){
								if(substr($MatriculeAAA,0,1)=="'"){
									$MatriculeAAA=substr($MatriculeAAA,1);
								}
							}
							$Emploi=utf8_decode($sheet->getCell($ColMetier.$ligne)->getValue());
							$Niveau=$sheet->getCell($ColNiveau.$ligne)->getValue();
							$Coefficient=$sheet->getCell($ColCoeff.$ligne)->getValue();
							$Indice=$sheet->getCell($ColIndice.$ligne)->getValue();
							$Horaire=utf8_decode($sheet->getCell($ColHoraire.$ligne)->getValue());
							$Salaire=$sheet->getCell($ColSalaire.$ligne)->getValue();
							
							$Id_Personne=0;
							$Id_PersonneContrat=0;
							$bAjout=1;
							$bExiste=0;

							if($bAjout==1 && $MatriculeAAA==""){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule AAA n'est pas renseigné</td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the AAA number is not filled in</td></tr>";}
							}
							
							//Rechercher si ce matricule existe dans la base de données 
							$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeAAA=\"".$MatriculeAAA."\" OR MatriculeAAA LIKE \"%00".$MatriculeAAA."\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta==1){
								$row=mysqli_fetch_array($result);
								$Id_Personne=$row['Id'];
							}
							if($bAjout==1 && $Id_Personne==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule ".$MatriculeAAA." n'existe pas </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$MatriculeAAA." number does not exist </td></tr>";}
							}
							
							//Recherche le contrat en cours de la personne
							$Id_ContratEC=IdContratEC($Id_Personne);
							if($Id_ContratEC>0){
								$req="SELECT Id, Id_ContratInitial FROM rh_personne_contrat WHERE Id=".$Id_ContratEC." ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$row=mysqli_fetch_array($result);
									if($row['Id_ContratInitial']>0){$Id_PersonneContrat=$row['Id_ContratInitial'];}
									else{$Id_PersonneContrat=$row['Id'];}
								}
							}
							
							if($bAjout==1 && $Id_PersonneContrat==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car cette personne n'a pas de contrat en cours </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because this person has no current contract </td></tr>";}
							}

							//Vérifier si cet avenant existe déjà (Id_Personne,Mois,Année)
							$req="SELECT Id 
								FROM rh_personne_contrat 
								WHERE Suppr=0 
								AND Id_Personne=".$Id_Personne." 
								AND MoisImport=".$Mois." 
								AND AnneeImport=".$Annee." ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if($bAjout==1 && $nbResulta>0){
								$bAjout=0;
								$bExiste=1;
								$nbExisteDeja++;
							}
							
							//Rechercher si ce métier existe dans la base de données 
							$req="SELECT Id,Libelle,Id_Classification FROM new_competences_metier WHERE Suppr=0 AND Libelle = \"".$Emploi."\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$Id_Metier=0;
							$Id_ClassificationMetier=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_Metier=$row['Id'];
								$Id_ClassificationMetier=$row['Id_Classification'];
							}
							if($bAjout==1 && $Id_Metier==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le métier ".$Emploi." n'existe pas </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$Emploi." job does not exist </td></tr>";}
							}
							
							//Salaire
							if($Salaire==""){
								$Salaire=0;
							}
							
							//Temps de travail
							$req="SELECT Id,Libelle FROM rh_tempstravail WHERE Suppr=0 AND Libelle = \"".$Horaire."\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$Id_Horaire=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_Horaire=$row['Id'];
							}
							if($bAjout==1 && $Id_Horaire==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car l'horaire ".$Horaire." n'existe pas </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$Horaire." schedule does not exist </td></tr>";}
							}

							if($bExiste==0){
								if($bAjout==1){
									$nbAjout++;
									
									$TypeDocument="Avenant";
									$Id_TypeContrat=0;
									$Id_LieuTravail=0;
									$Id_Prestation=0;
									$Id_Client=0;
									$Debut=$Annee."-".$Mois."-01";
									$Motif="Augmentation ".$Mois."/".$Annee;
									
									$req="SELECT Id_TypeContrat,Id_LieuTravail,Id_Prestation,Id_Client
										FROM rh_personne_contrat 
										WHERE Id=".$Id_PersonneContrat;
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$row=mysqli_fetch_array($result);
										$Id_TypeContrat=$row['Id_TypeContrat'];
										$Id_LieuTravail=$row['Id_LieuTravail'];
										$Id_Prestation=$row['Id_Prestation'];
										$Id_Client=$row['Id_Client'];
									}
									
									$SalaireReference=0;
									$req="SELECT Salaire 
										FROM rh_tag 
										WHERE Suppr=0 
										AND Id_ClassificationMetier=".$Id_ClassificationMetier." 
										AND Coeff='".$Coefficient."' 
										AND Niveau='".$Niveau."' 
										AND Echelon='".$Indice."' ";
									$resultTAG=mysqli_query($bdd,$req);
									$nbTAG=mysqli_num_rows($resultTAG);
									if($nbTAG>0){
										$row=mysqli_fetch_array($resultTAG);
										if($row['Salaire']<>""){$SalaireReference=$row['Salaire'];}
									}

									$req="INSERT INTO rh_personne_contrat (Id_Personne,Id_TypeContrat,Id_Metier,Coeff,Niveau,Echelon,
											SalaireBrut,SalaireReference,DateDebut,Id_TempsTravail,Id_LieuTravail,Id_Prestation,TypeDocument,DateCreation,Id_Createur,
											Id_Client,Motif,MoisImport,AnneeImport,Id_ContratInitial) 
										VALUES 
											(".$Id_Personne.",".$Id_TypeContrat.",".$Id_Metier.",'".$Coefficient."','".$Niveau."','".$Indice."',
											".$Salaire.",".$SalaireReference.",'".$Debut."',".$Id_Horaire.",".$Id_LieuTravail.",".$Id_Prestation.",'".$TypeDocument."','".date('Y-m-d')."',".$_SESSION['Id_Personne'].",
											".$Id_Client.",\"".addslashes($Motif)."\",".$Mois.",".$Annee.",".$Id_PersonneContrat.")";
									$resultAjout=mysqli_query($bdd,$req);
									$IdCree = mysqli_insert_id($bdd);
									
									$req="INSERT INTO rh_personne_contrat_tempspartiel (Id_Personne_Contrat,Id_Vacation,NbHeureJour,NbHeureEJ,NbHeureEN,NbHeurePause,JourSemaine,HeureDebut,HeureFin,Teletravail)
										SELECT ".$IdCree.",Id_Vacation,NbHeureJour,NbHeureEJ,NbHeureEN,NbHeurePause,JourSemaine,HeureDebut,HeureFin,Teletravail
										FROM rh_personne_contrat_tempspartiel 
										WHERE Suppr=0
										AND Id_Personne_Contrat=".$Id_PersonneContrat."
										";
									$resultTP=mysqli_query($bdd,$req);
								}
								else{
									$nbNonAjout++;
								}
							}
						}
					}
					
					if($nbAjout>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbAjout." ligne(s) ajoutée(s) / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbAjout." lines added / ".$nbLigne." </td></tr>";}
					}
					if($nbNonAjout>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbNonAjout." ligne(s) non ajoutée(s) / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbNonAjout." lines not added / ".$nbLigne." </td></tr>";}
					}
					if($nbExisteDeja>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbExisteDeja." ligne(s) existe(nt) déjà / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbExisteDeja." lines already exist / ".$nbLigne." </td></tr>";}
					}

					//Free up some of the memory 
					$objPHPExcel->disconnectWorksheets(); 
					unset($objPHPExcel);
				}
				else{
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le mois et l'année doivent être complétés</td></tr>";}
					else{$ResultatImport.="<tr><td>Month and year must be completed</td></tr>";}
				}
			}
		}
	}
}

function Titre1($Libelle,$Lien,$Selected){
		$tiret="";
		if($Selected==true){$tiret="border-bottom:4px solid white;";}
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#5c4165';\" onmouseout=\"this.style.color='#5c4165';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}

?>

<form class="test" enctype="multipart/form-data" action="Liste_ImportAugmentation.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $personne; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des contrats";}else{echo "Contract management";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr bgcolor="#cdbad2">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("CONTRATS EN COURS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",false);}
						else{Titre1("CONTRACTS IN PROGRESS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("ODM EN COURS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						else{Titre1("MISSION ORDER IN PROGRESS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT AUGMENTATIONS","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",true);}
						else{Titre1("IMPORT INCREASES","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",true);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="5"></td></tr>
		<tr>
			<td class="Libelle" width="10%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier augmentations";}else{echo "File increases";}?> : </td>
			<td width="20%"><input name="fichier" type="file" onChange="CheckFichier();"></td>
			<td width="60%">
				<input class="Bouton" type="submit" id="btnImporter" name="btnImporter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
			</td>
			<td width="20%">
				<input class="Bouton" type="button"  onclick="OuvrirTemplate();" value="<?php if($_SESSION["Langue"]=="FR"){echo "Template pour l'import";}else{echo "Template for import";}?>">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="6">
				<table width="100%" cellpadding="0" cellspacing="0" align="center">
					<?php
						echo $ResultatImport1;
					?>	
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="6">
				<div id='Div_Erreurs' style='height:400px;width:100%;overflow:auto;'>
				<table width="100%" cellpadding="0" cellspacing="0" align="center">
					<?php
						echo $ResultatImport;
					?>	
				</table>
				</div>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
}
?>
	
</body>
</html>