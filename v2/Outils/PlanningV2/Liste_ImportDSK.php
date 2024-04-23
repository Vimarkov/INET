<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_Contrat.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_Contrat.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
	function OuvreFenetreModifODM(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_ODM.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
	function OuvreFenetreSupprODM(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_ODM.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
	function NouveauContrat(Id_Personne,Page)
		{var w=window.open("Ajout_Contrat.php?Mode=A&Id=0&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1100,height=600");
		w.focus();
		}
	function NouvelAvenant(Id_Personne,Id,Page)
		{var w=window.open("Ajout_ContratAvenant.php?Mode=A&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1100,height=600");
		w.focus();
		}
	function NouveauODM(Id_Personne,Id,Page)
		{var w=window.open("Ajout_ODM.php?Mode=A&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1100,height=600");
		w.focus();
		}
	function ContratExcel(Id)
		{window.open("Export_Contrat.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
	function ODMExcel(Id)
		{window.open("Export_ODM.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
$ResultatImport="";
$ResultatImport1="";
$ResultatMouvement="";
$DirFichier="DSK/Extract_DSK.xlsx";
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
				
				//Parcours de la première colonne pour vérifier qu'on a toutes les colonnes souhaitées 
				$ColMatriculeDSK="";
				$ColDossier="";
				$ColSequence="";
				$ColStatut="";
				$ColDebut="";
				$ColFin="";
				$ColEnregistre="";
				$ColAgence="";
				$ColCoeffFacturation="";
				$ColTauxHoraire="";
				$ColSouplesse="";
				$ColHoraires="";
				$ColMotif="";
				$ColCategorie="";
				$ColEmploi="";
				$ColCoeff="";
				$ColJustification="";
				$ColJustificationAvenant="";
				$ColClient="";
				$ColAffaire="";
				$ColLieu="";
				
				$chunkFilter->setRows(1,$chunkSize); 
				$objPHPExcel = $XLSXDocument->load($DirFichier); 
				$sheet = $objPHPExcel->getSheet(0);
				
				$ligne=1;
				for($column = 'A'; $column<>'FA'; $column++){
					switch(utf8_decode($sheet->getCell($column.$ligne)->getValue())){
						case "Matricule":
							$ColMatriculeDSK=$column;
							break;
						case "Dos.":
							$ColDossier=$column;
							break;
						case "Seq":
							$ColSequence=$column;
							break;
						case "Statut":
							$ColStatut=$column;
							break;
						case "Début":
							$ColDebut=$column;
							break;
						case "Fin":
							$ColFin=$column;
							break;
						case "Enreg.":
							$ColEnregistre=$column;
							break;
						case "Agence":
							$ColAgence=$column;
							break;
						case "Coeff. facturation":
							$ColCoeffFacturation=$column;
							break;
						case "Tx payé":
							$ColTauxHoraire=$column;
							break;
						case "Souplesse":
							$ColSouplesse=$column;
							break;
						case "Horaires":
							$ColHoraires=$column;
							break;
						case "Motif":
							$ColMotif=$column;
							break;
						case "Cat. emploi":
							$ColCategorie=$column;
							break;
						case "Emploi":
							$ColEmploi=$column;
							break;
						case "Coeff. contrat":
							$ColCoeff=$column;
							break;
						case "Justification":
							$ColJustification=$column;
							break;
						case "Justification Avenant Modification":
							$ColJustificationAvenant=$column;
							break;
						case "CLIENT":
							$ColClient=$column;
							break;
						case "AFFAIRE":
							$ColAffaire=$column;
							break;
						case "Site":
							$ColLieu=$column;
							break;
					}
				}
				
				$bChampsExistes=0;
				if($ColMatriculeDSK==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Matricule\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Matricule\" column does not exist</td></tr>";}
				}
				if($ColDossier==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Dos.\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Dos.\" column does not exist</td></tr>";}
				}
				if($ColSequence==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Seq\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Seq\" column does not exist</td></tr>";}
				}
				if($ColStatut==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Statut\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Statut\" column does not exist</td></tr>";}
				}
				if($ColDebut==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Début\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Début\" column does not exist</td></tr>";}
				}
				if($ColFin==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Fin\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Fin\" column does not exist</td></tr>";}
				}
				if($ColEnregistre==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Enreg.\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Enreg.\" column does not exist</td></tr>";}
				}
				if($ColAgence==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Agence\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Agence\" column does not exist</td></tr>";}
				}
				if($ColCoeffFacturation==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Coeff. facturation\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Coeff. facturation\" column does not exist</td></tr>";}
				}
				if($ColTauxHoraire==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Tx payé\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Tx payé\" column does not exist</td></tr>";}
				}
				if($ColSouplesse==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Souplesse\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Souplesse\" column does not exist</td></tr>";}
				}
				if($ColHoraires==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Horaires\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Horaires\" column does not exist</td></tr>";}
				}
				if($ColMotif==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Motif\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Motif\" column does not exist</td></tr>";}
				}
				if($ColCategorie==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Cat. emploi\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Cat. emploi\" column does not exist</td></tr>";}
				}
				if($ColEmploi==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Emploi\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Emploi\" column does not exist</td></tr>";}
				}
				if($ColCoeff==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Coeff. contrat\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Coeff. contrat\" column does not exist</td></tr>";}
				}
				if($ColJustification==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Justification\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Justification\" column does not exist</td></tr>";}
				}
				if($ColLieu==""){
					$bChampsExistes=1;
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La colonne \"Site\" n'existe pas</td></tr>";}
					else{$ResultatImport.="<tr><td>The \"Site\" column does not exist</td></tr>";}
				}
				
				$nbLigne=0;
				$nbAjout=0;
				$nbNonAjout=0;
				$nbMAJ=0;
				$nbNonMAJ=0;
				if($bChampsExistes==0){
					$STOP=0;
					for($ligne=2;$ligne<4000;$ligne++){
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
							$Sequence=$sheet->getCell($ColSequence.$ligne)->getValue();
							if($Sequence<>""){
								$Sequence=date("d", PHPExcel_Shared_Date::ExcelToPHP($Sequence));
							}
							$Statut=utf8_decode($sheet->getCell($ColStatut.$ligne)->getValue());
							$Debut=$sheet->getCell($ColDebut.$ligne)->getValue();
							$Fin=substr($sheet->getCell($ColFin.$ligne)->getValue(),0,10);
							$Enregistre=$sheet->getCell($ColEnregistre.$ligne)->getValue();
							$Agence=utf8_decode(trim($sheet->getCell($ColAgence.$ligne)->getValue()));
							if($Agence<>""){
								if(substr($Agence,0,1)=="'"){
									$Agence=substr($Agence,1);
								}
							}
							$CoeffFacturation=$sheet->getCell($ColCoeffFacturation.$ligne)->getValue();
							$TauxHoraire=utf8_decode($sheet->getCell($ColTauxHoraire.$ligne)->getValue());
							$Souplesse=$sheet->getCell($ColSouplesse.$ligne)->getValue();
							$Horaires=utf8_decode(trim($sheet->getCell($ColHoraires.$ligne)->getValue()));
							$Motif=utf8_decode($sheet->getCell($ColMotif.$ligne)->getValue());
							$Categorie=utf8_decode(trim($sheet->getCell($ColCategorie.$ligne)->getValue()));
							$Emploi=utf8_decode(trim($sheet->getCell($ColEmploi.$ligne)->getValue()));
							$Coeff=trim($sheet->getCell($ColCoeff.$ligne)->getValue());
							$Justification=utf8_decode($sheet->getCell($ColJustification.$ligne)->getValue());
							$JustificationAvenant="";
							if($ColJustificationAvenant<>""){
								$JustificationAvenant=utf8_decode($sheet->getCell($ColJustificationAvenant.$ligne)->getValue());
							}
							$Client="";
							if($ColClient<>""){
							$Client=utf8_decode(trim($sheet->getCell($ColClient.$ligne)->getValue()));
							}
							$Affaire="";
							if($ColAffaire<>""){
								$Affaire=utf8_decode(trim($sheet->getCell($ColAffaire.$ligne)->getValue()));
							}
							$Lieu=utf8_decode(trim($sheet->getCell($ColLieu.$ligne)->getValue()));
							
							$Id_Personne=0;
							$Id_PersonneContrat=0;
							$bAjout=1;
							$bExiste=0;
							$Id_Agence=0;
							
							if($bAjout==1 && $Dossier==""){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le n° de dossier n'est pas renseigné</td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the file number is not filled in</td></tr>";}
							}
							
							if($bAjout==1 && $Sequence==""){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le n° de séquence n'est pas renseigné</td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the sequence number is not filled in</td></tr>";}
							}
							
							
							//Recherche si cette ligne existe déjà dans l'outils 
							$req="SELECT Id FROM rh_personne_contrat WHERE Suppr=0 AND DossierDSK=\"".$Dossier."\" AND SequenceDSK=\"".$Sequence."\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta==1){
								$row=mysqli_fetch_array($result);
								$Id_PersonneContrat=$row['Id'];
							}
							
							//Rechercher si ce matricule existe dans la base de données 
							$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeDSK=\"".$MatriculeDSK."\"";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta==1){
								$row=mysqli_fetch_array($result);
								$Id_Personne=$row['Id'];
							}
							if($bAjout==1 && $Id_Personne==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule ".$MatriculeDSK." n'existe pas : Dossier ".$Dossier.", Séquence ".$Sequence." </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$MatriculeDSK." number does not exist: File ".$Dossier.", Sequence ".$Sequence." </td></tr>";}
							}
							
							if($Debut<>""){
								$Debut =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($Debut));
							} 
							else{$Debut="0001-01-01";}
							if($Fin<>""){
								if(stristr($Fin,"/")===false){
									$Fin =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($Fin));
								}
								else{
									$Fin = TrsfDateExcel_($Fin);
								}
							} 
							else{$Fin="0001-01-01";}
							if($Enregistre<>""){
								$Enregistre =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($Enregistre));
							} 
							else{$Enregistre="0001-01-01";}
							
							if($bAjout==1 && $Debut>$Fin && $Fin>'0001-01-01'){
								$bAjout=0;
							}
							
							//Rechercher si cette agence existe dans la base de données 
							$req="SELECT Id,Libelle FROM rh_agenceinterim WHERE Suppr=0 ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									if(stristr($Agence,$row['Libelle'])===false){
										
									}
									else{
										$Id_Agence=$row['Id'];
									}
								}
							}
							if($bAjout==1 && $Id_Agence==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car l'agence ".$Agence." n'existe pas : Dossier ".$Dossier.", Séquence ".$Sequence." </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$Agence." temp agency does not exist: File ".$Dossier.", Sequence ".$Sequence." </td></tr>";}
							}
							
							//Rechercher si ce métier existe dans la base de données 
							$Emploi=substr($Emploi,0,strpos($Emploi," -"));
							$req="SELECT Id,Libelle FROM new_competences_metier WHERE Suppr=0 AND Libelle LIKE \"%".$Emploi."%\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							
							$Id_Metier=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_Metier=$row['Id'];
							}
							
							if($bAjout==1 && $Id_Metier==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le métier ".$Emploi." n'existe pas : Dossier ".$Dossier.", Séquence ".$Sequence." </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$Emploi." job does not exist: File ".$Dossier.", Sequence ".$Sequence." </td></tr>";}
							}
							
							//Taux  horaire
							if($TauxHoraire<>""){
								$TauxHoraire=str_replace(" ","",substr($TauxHoraire,0,strpos($TauxHoraire," ")));
							}
							else{
								$TauxHoraire=0;
							}
							$SouplesseNegative='0001-01-01';
							$SouplessePositive='0001-01-01';
							if($Souplesse<>""){
								$SouplesseNegative = TrsfDateExcel_(substr($Souplesse,0,10));
								$SouplessePositive = TrsfDateExcel_(substr($Souplesse,11,10));
							}
							
							//Temps de travail
							if($Categorie=="Cadre"){
								$Id_TempsTravail=10;
							}
							else{
								if(stristr($Horaires,"VSD")===false){
									$Id_TempsTravail=1;
								}
								else{
									$Id_TempsTravail=18;
								}
							}
							
							$Coeff=str_replace("coeff ","",$Coeff);
							if($Coeff==""){$Coeff=0;}
							
							//Rechercher si ce client existe dans la base de données 
							$req="SELECT Id,Libelle FROM rh_client WHERE Suppr=0 AND Libelle LIKE \"%".$Client."%\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$Id_Client=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_Client=$row['Id'];
							}
							
							//Rechercher si cette affaire existe dans la base de données 
							$Id_Prestation=0;
							if($Affaire<>""){
								$req="SELECT Id,Libelle FROM new_competences_prestation WHERE Libelle LIKE \"".$Affaire."%\" ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								
								if ($nbResulta>0){
									$row=mysqli_fetch_array($result);
									$Id_Prestation=$row['Id'];
								}
							}
							
							//Rechercher si ce lieu existe dans la base de données 
							$req="SELECT Id,Libelle FROM rh_lieutravail WHERE Libelle LIKE \"%".$Lieu."%\" ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$Id_LieuTravail=0;
							if ($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$Id_LieuTravail=$row['Id'];
							}
							if($bAjout==1 && $Id_LieuTravail==0){
								$bAjout=0;
								if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le lieu ".$Lieu." n'existe pas : Dossier ".$Dossier.", Séquence ".$Sequence." </td></tr>";}
								else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$Lieu." place does not exist: File ".$Dossier.", Sequence ".$Sequence." </td></tr>";}
							}

							if($Id_PersonneContrat==0){
								if($Statut<>"annulé"){
									if($bAjout==1){
										$nbAjout++;
										
										if($Sequence==1){
											$TypeDocument="Nouveau";
											$remarque=$Justification;
										}
										else{
											$TypeDocument="Avenant";
											$remarque=$JustificationAvenant;
										}
										//Contrat Intérim
										$Id_TypeContrat=10;
										
										
										$req="INSERT INTO rh_personne_contrat (Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,Coeff,CoeffFacturationAgence,
												TauxHoraire,DateDebut,DateFin,Id_TempsTravail,Id_LieuTravail,Id_Prestation,TypeDocument,DateCreation,Id_Createur,
												DateSouplessePositive,DateSouplesseNegative,Remarque,Id_Client,Motif,DossierDSK,SequenceDSK) 
											VALUES 
												(".$Id_Personne.",".$Id_TypeContrat.",".$Id_Agence.",".$Id_Metier.",'".$Coeff."',".$CoeffFacturation.",".$TauxHoraire.",
												'".$Debut."','".$Fin."',".$Id_TempsTravail.",".$Id_LieuTravail.",".$Id_Prestation.",'".$TypeDocument."','".$Enregistre."',0,
												'".$SouplessePositive."','".$SouplesseNegative."',\"".addslashes($remarque)."\",".$Id_Client.",\"".addslashes($Motif)."\",'".$Dossier."','".$Sequence."')";
										$resultAjout=mysqli_query($bdd,$req);
										$IdCree = mysqli_insert_id($bdd);
										
										if($IdCree>0){
											if($Sequence==1){
												$req="UPDATE rh_personne_contrat 
												SET Id_ContratInitial=".$IdCree." 
												WHERE DossierDSK='".$Dossier."'
												AND SequenceDSK<>'01' 
												AND Id<>".$IdCree." 
												AND Suppr=0 ";
												$resultUpdt=mysqli_query($bdd,$req);
											}
											else{
												$req="SELECT Id FROM rh_personne_contrat WHERE DossierDSK='".$Dossier."' AND SequenceDSK='01' AND Suppr=0 ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												$Id_Contrat=0;
												if ($nbResulta>0){
													$row=mysqli_fetch_array($result);
													$Id_Contrat=$row['Id'];
												}
												if($Id_Contrat>0){
													$req="UPDATE rh_personne_contrat 
													SET Id_ContratInitial=".$Id_Contrat." 
													WHERE Id=".$IdCree." ";
													$resultUpdt=mysqli_query($bdd,$req);
												}
											}
										}
										
										if($TypeDocument=="Nouveau" && $Id_Prestation>0){

											$reqCC="SELECT CentreDeCout FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
											$resultCC=mysqli_query($bdd,$reqCC);
											$nbCC=mysqli_num_rows($resultCC);
											if($nbCC>0){
												$rowCC=mysqli_fetch_array($resultCC);
		
												if($rowCC['CentreDeCout']<>""){
													//Mettre à jour le centre de cout de la personne 
													$reqUpdtCC="UPDATE new_rh_etatcivil SET CentreDeCout='".$rowCC['CentreDeCout']."' WHERE CentreDeCout='' AND Id=".$Id_Personne." ";
													$resultUpdtCC=mysqli_query($bdd,$reqUpdtCC);
												}
											}
										}
										
										//Créer un mouvement si le mouvement n'existe pas 
										if($Id_Prestation>0){
											$req="SELECT Id FROM new_competences_prestation 
												WHERE Id NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE new_competences_pole.Actif=0) 
												AND Id=".$Id_Prestation;
											$resultatPrestaSansPole=mysqli_query($bdd,$req);
											$nbPrestaSansPole=mysqli_num_rows($resultatPrestaSansPole);
										
										
											$req="SELECT Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation;
											$resultatPresta=mysqli_query($bdd,$req);
											$nbPresta=mysqli_num_rows($resultatPresta);
											$site="";
											$LaPersonne="";
											if($nbPresta>0){
												$rowPresta=mysqli_fetch_array($resultatPresta);
												$site=$rowPresta['Libelle'];
											}
											
											$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
											$resultatPers=mysqli_query($bdd,$req);
											$nbPers=mysqli_num_rows($resultatPers);
											if($nbPers>0){
												$rowPers=mysqli_fetch_array($resultatPers);
												$LaPersonne=$rowPers['Personne'];
											}
											
											if($Id_Prestation>0){
												if($nbPrestaSansPole>0){
													$req="SELECT Id
														FROM rh_personne_mouvement
														WHERE (rh_personne_mouvement.DateDebut<='".$Fin."' OR '".$Fin."'<='0001-01-01')
														AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$Debut."')
														AND rh_personne_mouvement.EtatValidation IN (0,1) 
														AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
														AND rh_personne_mouvement.Suppr=0";
													$resultatMod=mysqli_query($bdd,$req);
													$nbResultaMod=mysqli_num_rows($resultatMod);
													if($nbResultaMod==0){
														$requete="INSERT INTO rh_personne_mouvement ";
														$requete.="(Id_PrestationDepart,Id_PoleDepart,Id_Prestation,Id_Pole,Id_Personne,DateDebut,DateFin,Id_Createur,DateCreation) VALUES ";
														$requete.="(0,0,".$Id_Prestation.",0,".$Id_Personne.",'".$Debut."','".$Fin."',".$_SESSION['Id_Personne'].",'".date('Y-m-d')."')";
														$result=mysqli_query($bdd,$requete);
													}
												}
												else{
													if($_SESSION["Langue"]=="FR"){$ResultatMouvement.="<tr><td style='color:#ae0000;'>Le mouvement de ".$LaPersonne." n'a pas été créé car la prestation ".$site." contient des pôles </td></tr>";}
													else{$ResultatMouvement.="<tr><td style='color:#ae0000;'>The movement of ".$LaPersonne." was not created because the ".$site." site contains poles </td></tr>";}
												}
											}
										}
									}
									else{
										$nbNonAjout++;
									}
								}
							}
							else{
								if($bAjout==1){
									$nbMAJ++;
									$Suppr=0;
									if($Statut<>"annulé"){
										$Suppr=1;
									}
									
									if($Sequence==1){
										$TypeDocument="Nouveau";
										$remarque=$Justification;
									}
									else{
										$TypeDocument="Avenant";
										$remarque=$JustificationAvenant;
									}
									//Contrat Intérim
									$Id_TypeContrat=10;
									$req="UPDATE rh_personne_contrat 
										SET 
											Id_TypeContrat=".$Id_TypeContrat.",
											Id_AgenceInterim=".$Id_Agence.",
											Id_Metier=".$Id_Metier.",
											TypeCoeff=\"".$Coeff."\",
											CoeffFacturationAgence=".$CoeffFacturation.",
											TauxHoraire=".$TauxHoraire.",
											DateDebut='".$Debut."',
											DateFin='".$Fin."',
											Id_TempsTravail=".$Id_TempsTravail.",
											Id_LieuTravail=".$Id_LieuTravail.",
											Id_Prestation=".$Id_Prestation.",
											DateSouplessePositive='".$SouplessePositive."',
											DateSouplesseNegative='".$SouplesseNegative."',
											Remarque=\"".addslashes($remarque)."\",
											Id_Client=".$Id_Client.",
											Motif=\"".addslashes($Motif)."\"
										WHERE 
											DossierDSK='".$Dossier."'
											AND SequenceDSK='".$Sequence."'
											AND Suppr=0 ";
									$resultModif=mysqli_query($bdd,$req);
									$IdCree=0;
									$req="SELECT Id FROM rh_personne_contrat WHERE DossierDSK='".$Dossier."' AND SequenceDSK='".$Sequence."' AND Suppr=0 ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$row=mysqli_fetch_array($result);
										$IdCree=$row['Id'];
									}
									
									if($IdCree>0){
										if($Sequence==1){
											$req="UPDATE rh_personne_contrat 
											SET Id_ContratInitial=".$IdCree." 
											WHERE DossierDSK='".$Dossier."'
											AND SequenceDSK<>'01' 
											AND Id<>".$IdCree." 
											AND Suppr=0 ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										else{
											$req="SELECT Id FROM rh_personne_contrat WHERE DossierDSK='".$Dossier."' AND SequenceDSK='01' AND Suppr=0 ";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											$Id_Contrat=0;
											if ($nbResulta>0){
												$row=mysqli_fetch_array($result);
												$Id_Contrat=$row['Id'];
											}
											if($Id_Contrat>0){
												$req="UPDATE rh_personne_contrat 
												SET Id_ContratInitial=".$Id_Contrat." 
												WHERE Id=".$IdCree." ";
												$resultUpdt=mysqli_query($bdd,$req);
											}
										}
									}
									
									if($TypeDocument=="Nouveau" && $Id_Prestation>0){

										$reqCC="SELECT CentreDeCout FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
										$resultCC=mysqli_query($bdd,$reqCC);
										$nbCC=mysqli_num_rows($resultCC);
										if($nbCC>0){
											$rowCC=mysqli_fetch_array($resultCC);
	
											if($rowCC['CentreDeCout']<>""){
												//Mettre à jour le centre de cout de la personne 
												$reqUpdtCC="UPDATE new_rh_etatcivil SET CentreDeCout='".$rowCC['CentreDeCout']."' WHERE CentreDeCout='' AND Id=".$Id_Personne." ";
												$resultUpdtCC=mysqli_query($bdd,$reqUpdtCC);
											}
										}
									}
									
									//Créer un mouvement si le mouvement n'existe pas 
									if($Id_Prestation>0){
										$req="SELECT Id FROM new_competences_prestation 
											WHERE Id NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE new_competences_pole.Actif=0) 
											AND Id=".$Id_Prestation;
										$resultatPrestaSansPole=mysqli_query($bdd,$req);
										$nbPrestaSansPole=mysqli_num_rows($resultatPrestaSansPole);
										
										$req="SELECT Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation;
										$resultatPresta=mysqli_query($bdd,$req);
										$nbPresta=mysqli_num_rows($resultatPresta);
										$site="";
										$LaPersonne="";
										if($nbPresta>0){
											$rowPresta=mysqli_fetch_array($resultatPresta);
											$site=$rowPresta['Libelle'];
										}
										
										$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
										$resultatPers=mysqli_query($bdd,$req);
										$nbPers=mysqli_num_rows($resultatPers);
										if($nbPers>0){
											$rowPers=mysqli_fetch_array($resultatPers);
											$LaPersonne=$rowPers['Personne'];
										}
										
										if($Id_Prestation>0){
											if($nbPrestaSansPole>0){
												$req="SELECT Id
													FROM rh_personne_mouvement
													WHERE (rh_personne_mouvement.DateDebut<='".$Fin."' OR '".$Fin."'<='0001-01-01')
													AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$Debut."')
													AND rh_personne_mouvement.EtatValidation IN (0,1) 
													AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
													AND rh_personne_mouvement.Suppr=0";
												$resultatMod=mysqli_query($bdd,$req);
												$nbResultaMod=mysqli_num_rows($resultatMod);
												if($nbResultaMod==0){
													$requete="INSERT INTO rh_personne_mouvement ";
													$requete.="(Id_PrestationDepart,Id_PoleDepart,Id_Prestation,Id_Pole,Id_Personne,DateDebut,DateFin,Id_Createur,DateCreation) VALUES ";
													$requete.="(0,0,".$Id_Prestation.",0,".$Id_Personne.",'".$Debut."','".$Fin."',".$_SESSION['Id_Personne'].",'".date('Y-m-d')."')";
													$result=mysqli_query($bdd,$requete);
												}
											}
											else{
													$req="SELECT Id
														FROM rh_personne_mouvement
														WHERE (rh_personne_mouvement.DateDebut<='".$Fin."' OR '".$Fin."'<='0001-01-01')
														AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$Debut."')
														AND rh_personne_mouvement.EtatValidation IN (0,1) 
														AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
														AND rh_personne_mouvement.Suppr=0";
													$resultatMod=mysqli_query($bdd,$req);
													$nbResultaMod=mysqli_num_rows($resultatMod);
													if($nbResultaMod==0){
														if($_SESSION["Langue"]=="FR"){$ResultatMouvement.="<tr><td style='color:#ae0000;'>Le mouvement de ".$LaPersonne." n'a pas été créé car la prestation ".$site." contient des pôles </td></tr>";}
														else{$ResultatMouvement.="<tr><td style='color:#ae0000;'>The movement of ".$LaPersonne." was not created because the ".$site." site contains poles </td></tr>";}
													}
											}
										}
										else{
											if($_SESSION["Langue"]=="FR"){$ResultatMouvement.="<tr><td style='color:#ae0000;'>Le mouvement de ".$LaPersonne." n'a pas été créé car la prestation n'existe pas </td></tr>";}
											else{$ResultatMouvement.="<tr><td style='color:#ae0000;'>The movement of ".$LaPersonne." was not created because the site does not exist </td></tr>";}
										}
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

<form class="test" enctype="multipart/form-data" action="Liste_ImportDSK.php" method="post">
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
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",true);}
						else{Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",true);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						
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
			<td class="Libelle" width="10%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier DirectSkills";}else{echo "DirectSkills file";}?> : </td>
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
		<tr><td height="10"></td></tr>
		<tr>
			<td colspan="6">
				<div id='Div_Mouvement' style='height:400px;width:100%;overflow:auto;'>
				<table width="100%" cellpadding="0" cellspacing="0" align="center">
					<?php
						echo $ResultatMouvement;
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