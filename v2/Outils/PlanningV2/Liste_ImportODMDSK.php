<?php
require("../../Menu.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
$ResultatImport="";
$ResultatImport1="";
$ResultatMouvement="";
$DirFichier="DSK/Extract_ODMDSK.xlsx";

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
				//Supprimer le contenu de la table donnée ACP
				$XLSXDocument = new PHPExcel_Reader_Excel2007();
				
				/**  Define how many rows we want to read for each "chunk"  **/ 
				$chunkSize = 5000;
				/**  Create a new Instance of our Read Filter  **/ 
				$chunkFilter = new chunkReadFilter(); 
				/**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/ 
				$XLSXDocument->setReadFilter($chunkFilter); 
				
				//Parcours de la première colonne pour vérifier qu'on a toutes les colonnes souhaitées 
				$ColMatriculeDSK="";
				$ColDossier="";
				$ColDebut="";
				$ColFin="";
				$ColIndemnite="";
				$ColQuantite="";
				$ColTarif="";

				$chunkFilter->setRows(1,$chunkSize); 
				$objPHPExcel = $XLSXDocument->load($DirFichier); 
				$sheet = $objPHPExcel->getSheet(0);
				
				$ligne=3;
				for($column = 'A'; $column<>'AB'; $column++){
					switch(utf8_decode($sheet->getCell($column.$ligne)->getValue())){
						case "Matricule Intérimaire":
							$ColMatriculeDSK=$column;
							break;
						case "Dossier":
							$ColDossier=$column;
							break;
						case "Date début bordereaux":
							$ColDebut=$column;
							break;
						case "Date fin bordereaux":
							$ColFin=$column;
							break;
						case "Prestation":
							$ColIndemnite=$column;
							break;
						case "Qté":
							$ColQuantite=$column;
							break;
						case "Tarif / Taux":
							$ColTarif=$column;
							break;
					}
				}
				
				$bChampsExistes=0;
				if($ColMatriculeDSK==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Matricule Intérimaire\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Matricule Intérimaire\" column does not exist</td></tr>";}
				}
				if($ColDossier==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Dossier\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Dossier\" column does not exist</td></tr>";}
				}
				if($ColDebut==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Date début bordereaux\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Date début bordereaux\" column does not exist</td></tr>";}
				}
				if($ColFin==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Date fin bordereaux\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Date fin bordereaux\" column does not exist</td></tr>";}
				}
				if($ColIndemnite==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Prestation\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Prestation\" column does not exist</td></tr>";}
				}
				if($ColQuantite==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Qté\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Qté\" column does not exist</td></tr>";}
				}
				if($ColTarif==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Tarif / Taux\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Tarif / Taux\" column does not exist</td></tr>";}
				}
				
				$nbLigne=0;
				$nbAjout=0;
				$nbNonAjout=0;
				$nbMAJ=0;
				$nbNonMAJ=0;
				if($bChampsExistes==0){
					$STOP=0;
					for($ligne=4;$ligne<20000;$ligne++){
						if($sheet->getCell('A'.$ligne)->getValue()==""){$STOP=1;}
						if($STOP==0){
							$nbLigne++;
							//Récupérer les valeurs de chaque champs 
							$MatriculeDSK=utf8_decode($sheet->getCell($ColMatriculeDSK.$ligne)->getValue());
							if($MatriculeDSK<>""){
								if(substr($MatriculeDSK,0,1)=="'"){
									$MatriculeDSK=substr($MatriculeDSK,1);
								}
							}
							
							$Dossier=$sheet->getCell($ColDossier.$ligne)->getValue();
							$Debut=$sheet->getCell($ColDebut.$ligne)->getValue();
							$Fin=$sheet->getCell($ColFin.$ligne)->getValue();
							
							$Indemnite=utf8_decode($sheet->getCell($ColIndemnite.$ligne)->getValue());
							$Quantite=$sheet->getCell($ColQuantite.$ligne)->getValue();
							$Tarif=$sheet->getCell($ColTarif.$ligne)->getValue();
							
							$Id_Personne=0;
							$bAjout=1;
							$bExiste=0;
							
							//Rechercher si ce matricule existe dans la base de données 
							$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeDSK=\"".$MatriculeDSK."\"";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_Personne=$row['Id'];
							}
							
							if($bAjout==1 && $Id_Personne==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule ".$MatriculeDSK." n'existe pas : Dossier ".$Dossier." </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$MatriculeDSK." number does not exist: File ".$Dossier." </td></tr>";}
							}
							
							//Rechercher si ce dossier existe dans la base de données 
							$req="SELECT Id, Id_ContratInitial FROM rh_personne_contrat WHERE TypeDocument<>'ODM' AND DossierDSK=\"".$Dossier."\" AND Suppr=0 ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$Id_ContratInitial=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								if($row['Id_ContratInitial']==0){$Id_ContratInitial=$row['Id'];}
								else{$Id_ContratInitial=$row['Id_ContratInitial'];}
								
							}
							
							if($bAjout==1 && $Id_ContratInitial==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le dossier ".$Dossier." n'existe pas : Matricule ".$MatriculeDSK." </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$Dossier." file does not exist: Number DSK ".$MatriculeDSK."  </td></tr>";}
							}
							
							if($Debut<>""){
								$Debut =  TrsfDateExcel_($Debut);
							} 
							else{$Debut="0001-01-01";}
							
							if($Fin<>""){
								$Fin = TrsfDateExcel_($Fin);
							} 
							else{$Fin="0001-01-01";}
							
							//Rechercher si cet ODM existe 
							$req="SELECT Id FROM rh_personne_contrat WHERE TypeDocument='ODM' AND DossierDSK=\"".$Dossier."\" AND Suppr=0 ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);

							$Id_ODM=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_ODM=$row['Id'];
							}
							if($Id_ODM==0){
								if($bAjout==1){
									$nbAjout++;
									
									$Id_TypeContrat=0;
									$Id_Metier=0;
									$Id_Prestation=0;
									$Id_Pole=0;
									$Id_Client=0;
									$Id_Responsable=0;
									
									$req="SELECT Id_TypeContrat,Id_Metier,Id_Prestation,Id_Pole,Id_Client,Id_Responsable
										FROM rh_personne_contrat 
										WHERE Id=".$Id_ContratInitial;
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									$Id_ODM=0;
									if ($nbResulta>0){
										$row=mysqli_fetch_array($result);
										$Id_TypeContrat=$row['Id_TypeContrat'];
										$Id_Metier=$row['Id_Metier'];
										$Id_Prestation=$row['Id_Prestation'];
										$Id_Pole=$row['Id_Pole'];
										$Id_Client=$row['Id_Client'];
										$Id_Responsable=$row['Id_Responsable'];
									}
									
									//Rechercher les infos nécessaires sur le contrat initial 
									$req="INSERT INTO rh_personne_contrat (Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_Metier,DateDebut,DateFin,
										Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,Id_Client,Id_Responsable,DateSignatureSiege,DateSignatureSalarie,DateRetourSigneAuSiege,DossierDSK) 
										VALUES 
											(".$Id_ContratInitial.",".$Id_Personne.",".$Id_TypeContrat.",".$Id_Metier.",
											'".$Debut."','".$Fin."',".$Id_Prestation.",".$Id_Pole.",'ODM','".date('Y-m-d')."',".$_SESSION['Id_Personne'].",
											".$Id_Client.",".$Id_Responsable.",'".date('Y-m-d')."','".date('Y-m-d')."','".date('Y-m-d')."','".$Dossier."')";
									$resultAjout=mysqli_query($bdd,$req);
									$IdCree = mysqli_insert_id($bdd);
									
									if($IdCree>0){
										//Mise à jour de l'indemnité
										if(stripos($Indemnite, "Indemnité repas") !== false){
											$req="UPDATE rh_personne_contrat SET MontantRepas=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Ind. forf. découché part héb") !== false){
											$req="UPDATE rh_personne_contrat SET MontantIGD=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Ind. forf. découché part nour") !== false){
											$req="UPDATE rh_personne_contrat SET MontantRepasGD=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Indemnité Caisse") !== false){
											$req="UPDATE rh_personne_contrat SET IndemniteOutillage=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Indemnité de panier VSD") !== false){
											$req="UPDATE rh_personne_contrat SET PanierVSD=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Indemnité panier grande nuit") !== false){
											$req="UPDATE rh_personne_contrat SET PanierGrandeNuit=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Indemnité transport JT") !== false){
											$req="UPDATE rh_personne_contrat SET MontantIPD=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Majoration des Heures travaillées en VSD") !== false){
											$req="UPDATE rh_personne_contrat SET MajorationVSD=50 WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Prime de Responsabilité") !== false){
											$req="UPDATE rh_personne_contrat SET PrimeResponsabilite=".($Tarif*$Quantite)." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										elseif(stripos($Indemnite, "Prime d''équipe") !== false){
											$req="UPDATE rh_personne_contrat SET PrimeEquipe=".$Tarif." WHERE Id=".$IdCree." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										
									}
								}
								else{
									$nbNonAjout++;
								}
							}
							else{
								if($bAjout==1){
									$nbMAJ++;
									
									$req="SELECT DateDebut,DateFin FROM rh_personne_contrat WHERE Id=".$Id_ODM;
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$row=mysqli_fetch_array($result);
										if($row['DateDebut']>$Debut){
											$req="UPDATE rh_personne_contrat SET DateDebut='".$Debut."' WHERE Id=".$Id_ODM." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										if($row['DateFin']<$Fin){
											$req="UPDATE rh_personne_contrat SET DateFin='".$Fin."' WHERE Id=".$Id_ODM." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
									}
									
									
									$IdCree=$Id_ODM;
									
									//Mise à jour de l'indemnité
									if(stripos($Indemnite, "Indemnité repas") !== false){
										$req="UPDATE rh_personne_contrat SET MontantRepas=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Ind. forf. découché part héb") !== false){
										$req="UPDATE rh_personne_contrat SET MontantIGD=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Ind. forf. découché part nour") !== false){
										$req="UPDATE rh_personne_contrat SET MontantRepasGD=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Indemnité Caisse") !== false){
										$req="UPDATE rh_personne_contrat SET IndemniteOutillage=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Indemnité de panier VSD") !== false){
										$req="UPDATE rh_personne_contrat SET PanierVSD=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Indemnité panier grande nuit") !== false){
										$req="UPDATE rh_personne_contrat SET PanierGrandeNuit=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Indemnité transport JT") !== false){
										$req="UPDATE rh_personne_contrat SET MontantIPD=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Majoration des Heures travaillées en VSD") !== false){
										$req="UPDATE rh_personne_contrat SET MajorationVSD=50 WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Prime de Responsabilité") !== false){
										$req="UPDATE rh_personne_contrat SET PrimeResponsabilite=".($Tarif*$Quantite)." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
									elseif(stripos($Indemnite, "Prime d''équipe") !== false){
										$req="UPDATE rh_personne_contrat SET PrimeEquipe=".$Tarif." WHERE Id=".$IdCree." ";
										$resultUpdt=mysqli_query($bdd,$req);
									}
								}
								else{
									$nbNonMAJ++;
								}
							}
						}
					}
					
					if($nbAjout>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbAjout." lignes ajoutées / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbAjout." lines added / ".$nbLigne." </td></tr>";}
					}
					if($nbNonAjout>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbNonAjout." lignes non ajoutées / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbNonAjout." lines not added / ".$nbLigne." </td></tr>";}
					}
					if($nbMAJ>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbMAJ." lignes mises à jour / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbMAJ." lines updated / ".$nbLigne." </td></tr>";}
					}
					if($nbNonMAJ>0){
						if($_SESSION["Langue"]=="FR"){$ResultatImport1.="<tr><td>".$nbNonMAJ." lignes non mises à jour / ".$nbLigne." </td></tr>";}
						else{$ResultatImport1.="<tr><td".$nbNonMAJ." lines not updated / ".$nbLigne." </td></tr>";}
					}

					//Free up some of the memory 
					$objPHPExcel->disconnectWorksheets(); 
					unset($objPHPExcel);
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

<form class="test" enctype="multipart/form-data" action="Liste_ImportODMDSK.php" method="post">
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
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",true);}
						else{Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",true);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT AUGMENTATIONS","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT INCREASES","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
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
			<td class="Libelle" width="10%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier ODM DirectSkills";}else{echo "DirectSkills ODM file";}?> : </td>
			<td width="20%"><input name="fichier" type="file" onChange="CheckFichier();"></td>
			<td width="80%">
				<input class="Bouton" type="submit" id="btnImporter" name="btnImporter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
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