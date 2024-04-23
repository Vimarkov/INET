<?php
require("../../Menu.php");
?>
<script type="text/javascript">

</script>
<?php
$DateJour=date("Y-m-d");
$bEnregistrement=false;
$bExiste=false;
$ResultatImport="";
$DirFichier="DSK/Extract_WD.csv";

$Headers='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

/*
if(mail("p.fauge@daher.com","test","voici le message",$Headers,'-f noreply.extranet@aaa-aero.com')){echo "OK";}
else{echo "NOK";}*/

// Génération d'une chaine aléatoire
function chaine_aleatoire($nb_car, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
{
    $nb_lettres = strlen($chaine) - 1;
    $generation = '';
    for($i=0; $i < $nb_car; $i++)
    {
        $pos = mt_rand(0, $nb_lettres);
        $car = $chaine[$pos];
        $generation .= $car;
    }
    return $generation;
}

function fctRetirerAccents($varMaChaine)
{
	$search  = array("'",'-','À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
	//Préférez str_replace à strtr car strtr travaille directement sur les octets, ce qui pose problème en UTF-8
	$replace = array(" ",' ','A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');

	$varMaChaine = str_replace($search, $replace, $varMaChaine);
	return $varMaChaine; //On retourne le résultat
}

if($_POST){
	if(isset($_POST['btnImporter'])){
		if($_FILES['fichier']['name']!=""){
			//CREATION DU FICHIER EXCEL 
			$workbook = new PHPExcel;
			$sheetR = $workbook->getActiveSheet();

			//Ligne En-tete
			$sheetR->setCellValue('A1',utf8_encode('Rapport'));
			$ligneR=2;
			$sheetR->getStyle('A1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
			$sheetR->getColumnDimension('A')->setWidth(40);
			
			
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){
				$ResultatImport.="<tr><td style=\"color:#e80c0c\">Le fichier est introuvable</td></tr>";
				$sheetR->setCellValue('A'.$ligneR,utf8_encode('Le fichier est introuvable'));
				$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
				$ligneR++;
			}
			else{
				//On verifie l'extension
				$type_file=strrchr($_FILES['fichier']['name'], '.'); 
				if($type_file !='.xlsx'){
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">Le fichier doit être au format .xlsx</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode('Le fichier doit être au format .xlsx'));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				else
				{
					//On vérifie la taille du fichier
					if(filesize($_FILES['fichier']['tmp_name'])>30000000){
						$ResultatImport.="<tr><td style=\"color:#e80c0c\">Le fichier est trop volumineux</td></tr>";
						$sheetR->setCellValue('A'.$ligneR,utf8_encode('Le fichier est trop volumineux'));
						$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
						$ligneR++;
					}
					else{
						if(file_exists($DirFichier)){
							if(!unlink($DirFichier)){
								
							}
						}
						if(!move_uploaded_file($tmp_file,$DirFichier)){
							$ResultatImport.="<tr><td style=\"color:#e80c0c\">Impossible de copier le fichier</td></tr>";
							$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
							$sheetR->setCellValue('A'.$ligneR,utf8_encode('Impossible de copier le fichier'));
							$ligneR++;
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
						//  Only read the heading row, and the rows that are configu#e80c0c in $this->_startRow and $this->_endRow 
						if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) { 
						   return true;
						}
						return false;
					} 
				}

				//$objReader = PHPExcel_IOFactory::createReader('CSV');
				//$objReader->setDelimiter(";");
				$objReader = new PHPExcel_Reader_Excel2007();
				
				$objPHPExcel = $objReader->load($DirFichier);

				
				$sheet = $objPHPExcel->getActiveSheet();

				//Parcours de la première colonne pour vérifier qu'on a toutes les colonnes souhaitées 
				$ColLastName="";
				$ColFirstName="";
				$ColGender="";
				$ColNationality="";
				$ColDateOfBirth="";
				$ColCityOfBirth="";
				$ColWorkerType="";
				$ColEmailHome="";
				$ColWorkerId="";
				$ColContractReasonID="";
				
				$ligne=1;
				for($column = 'A'; $column<>'M'; $column++){
					switch(utf8_decode($sheet->getCell($column.$ligne)->getValue())){
						case "Last Name":
							$ColLastName=$column;
							break;
						case "First Name":
							$ColFirstName=$column;
							break;
						case "Gender":
							$ColGender=$column;
							break;
						case "Primary Nationality (Worker) - Locale Sensitive":
							$ColNationality=$column;
							break;
						case "CF_FD_Date of Birth":
							$ColDateOfBirth=$column;
							break;
						case "City of Birth (Locale Sensitive)":
							$ColCityOfBirth=$column;
							break;
						case "Worker Type":
							$ColWorkerType=$column;
							break;
						case "Email - Home":
							$ColEmailHome=$column;
							break;
						case "CFINT100 Dashless Employee ID":
							$ColWorkerId=$column;
							break;
						case "CF LRV Worker's Contract Reason ID":
							$ColContractReasonID=$column;
							break;
					}
				}
				
				$bChampsExistes=0;
				if($ColLastName==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"Last Name\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"Last Name\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColFirstName==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"First Name\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"First Name\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColGender==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"Gender\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"Gender\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColNationality==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"Primary Nationality (Worker) - Locale Sensitive\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"Primary Nationality (Worker) - Locale Sensitive\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColDateOfBirth==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"CF_FD_Date of Birth\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"CF_FD_Date of Birth\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColCityOfBirth==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"Country of Birth (Locale Sensitive)\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"Country of Birth (Locale Sensitive)\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColWorkerType==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"Worker Type\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"Worker Type\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColEmailHome==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"Email - Home\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"Email - Home\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColWorkerId==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"CFINT100 Dashless Employee ID\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"CFINT100 Dashless Employee ID\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				if($ColContractReasonID==""){
					$bChampsExistes=1;
					$ResultatImport.="<tr><td style=\"color:#e80c0c\">La colonne \"CF LRV Worker's Contract Reason ID\" n'existe pas</td></tr>";
					$sheetR->setCellValue('A'.$ligneR,utf8_encode("La colonne \"CF LRV Worker's Contract Reason ID\" n'existe pas"));
					$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
					$ligneR++;
				}
				
				$nbLigne=0;
				$nbAjout=0;
				$nbNonAjout=0;
				$nbMAJ=0;
				$nbNonMAJ=0;
				if($bChampsExistes==0){
					$STOP=0;
					for($ligne=2;$ligne<6000;$ligne++){
						if($sheet->getCell('A'.$ligne)->getValue()==""){$STOP=1;}
						if($STOP==0){
							$nbLigne++;
							//Récupérer les valeurs de chaque champs 
							$LastName=utf8_decode($sheet->getCell($ColLastName.$ligne)->getValue());
							$LastName=fctRetirerAccents($LastName); // On retire tous les accents
							$FirstName=utf8_decode($sheet->getCell($ColFirstName.$ligne)->getValue());
							$FirstName=fctRetirerAccents($FirstName); // On retire tous les accents// On retire tous les accents
							$Gender=utf8_decode($sheet->getCell($ColGender.$ligne)->getValue());
							if($Gender=="Masculin"){$Gender="Homme";}else{$Gender="Femme";}
							$Nationality=utf8_decode($sheet->getCell($ColNationality.$ligne)->getValue());
							$DateOfBirth=$sheet->getCell($ColDateOfBirth.$ligne)->getValue();
							$CityOfBirth=utf8_decode($sheet->getCell($ColCityOfBirth.$ligne)->getValue());
							$WorkerType=utf8_decode($sheet->getCell($ColWorkerType.$ligne)->getValue());
							$ContractReasonID=utf8_decode($sheet->getCell($ColContractReasonID.$ligne)->getValue());
							
							
							if($WorkerType=="Intervenant extérieur" || $WorkerType=="Contingent Worker"){
								$WorkerType="Intérimaire";
							}
							else{
								$WorkerType="";
								
								//Analyse du type de contrat 
								if($ContractReasonID=="Employee_Contract_Reason_CDI"){
									$WorkerType="CDI";
								}
								elseif($ContractReasonID=="Employee_Contract_Reason_Expatriation"){
									$WorkerType="CDIC";
								}
								elseif($ContractReasonID=="Employee_Contract_Reason_CDD_Surcroit"){
									$WorkerType="CDD";
								}
								elseif($ContractReasonID=="Employee_Contract_Reason_Apprenticeship"){
									$WorkerType="Alternant";
								}
								elseif($ContractReasonID=="Employee_Contract_Reason_Professionnalisation"){
									$WorkerType="Alternant";
								}
								elseif($ContractReasonID=="Employee_Contract_Reason_Stage_Remunere"){
									$WorkerType="Stage";
								}
							}
							$EmailHome=utf8_decode($sheet->getCell($ColEmailHome.$ligne)->getValue());
							$WorkerId=$sheet->getCell($ColWorkerId.$ligne)->getValue();
							
							if($DateOfBirth<>""){
								$DateOfBirth=substr($DateOfBirth,6,4)."-".substr($DateOfBirth,3,2)."-".substr($DateOfBirth,0,2);
							} 
							
							if($LastName=="" || $FirstName=="" || $DateOfBirth=="" || $WorkerId==""){
								$ResultatImport.="<tr><td style=\"color:#e80c0c\">".$LastName." ".$FirstName." : Import impossible [Nom, Prénom, Matricule Daher ou date de naissance non renseigné dans l'import WD]</td></tr>";
								$sheetR->setCellValue('A'.$ligneR,utf8_encode($LastName." ".$FirstName." : Import impossible [Nom, Prénom, Matricule Daher ou date de naissance non renseigné dans l'import WD]"));
								$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e80c0c'))));
								$ligneR++;
							}
							else{
								//Recherche si la personne existe 
								$req="SELECT Id, Nom, Prenom, Date_Naissance 
								FROM new_rh_etatcivil 
								WHERE UPPER(                 
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(          
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(
									Nom  
									, 'à' , 'a' ), 'á' , 'a' ), 'â' , 'a' ), 'ã' , 'a' ), 'ä' , 'a' ),'å' , 'a' )     
									, 'ç' , 'c' )      
									, 'è' , 'e' ), 'é' , 'e' ), 'ê' , 'e' ), 'ë' , 'e' )
									, 'ì' , 'i' ), 'í' , 'i' ), 'î' , 'i' ), 'ï' , 'i' ) 
									, 'ð' , 'o' ), 'ò' , 'o' ), 'ó' , 'o' ), 'ô' , 'o' ), 'õ' , 'o' ), 'ö' , 'o' ) 
									, 'ù' , 'u' ), 'ú' , 'u' ), 'û' , 'u' ), 'ü' , 'u' ) 
									, 'ý' , 'y' ), 'ÿ' , 'y' ) 
									, '-' , ' ' ), '\'' , ' ' ) 
								)=\"".$LastName."\" 
								AND UPPER(                 
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(          
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
									REPLACE(REPLACE(REPLACE(REPLACE(
									Prenom  
									, 'à' , 'a' ), 'á' , 'a' ), 'â' , 'a' ), 'ã' , 'a' ), 'ä' , 'a' ),'å' , 'a' )     
									, 'ç' , 'c' )      
									, 'è' , 'e' ), 'é' , 'e' ), 'ê' , 'e' ), 'ë' , 'e' )
									, 'ì' , 'i' ), 'í' , 'i' ), 'î' , 'i' ), 'ï' , 'i' ) 
									, 'ð' , 'o' ), 'ò' , 'o' ), 'ó' , 'o' ), 'ô' , 'o' ), 'õ' , 'o' ), 'ö' , 'o' ) 
									, 'ù' , 'u' ), 'ú' , 'u' ), 'û' , 'u' ), 'ü' , 'u' ) 
									, 'ý' , 'y' ), 'ÿ' , 'y' ) 
									, '-' , ' ' ), '\'' , ' ' ) 
								)=\"".$FirstName."\"
								AND Date_Naissance<='0001-01-01'
								";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								
								if($nbResulta>0){
									//Comparaison impossible car des personnes existent dans l'extranet sans date de naissance
									$ResultatImport.="<tr><td style=\"color:#e0a974\">".$LastName." ".$FirstName." : Personne en doublon dans l'extranet [Date de naissance non renseignée dans l'extranet. Veuillez saisir une date de naissance]</td></tr>";
									$sheetR->setCellValue('A'.$ligneR,utf8_encode($LastName." ".$FirstName." : Personne en doublon dans l'extranet [Date de naissance non renseignée dans l'extranet. Veuillez saisir une date de naissance]"));
									$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e0a974'))));
									$ligneR++;
								}
								else{
									$Id=0;
									$AModifier=0;
									$ACreer=0;

									$req="SELECT Id, Nom, Prenom, Date_Naissance 
									FROM new_rh_etatcivil 
									WHERE UPPER(                 
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(          
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(
										Nom  
										, 'à' , 'a' ), 'á' , 'a' ), 'â' , 'a' ), 'ã' , 'a' ), 'ä' , 'a' ),'å' , 'a' )     
										, 'ç' , 'c' )      
										, 'è' , 'e' ), 'é' , 'e' ), 'ê' , 'e' ), 'ë' , 'e' )
										, 'ì' , 'i' ), 'í' , 'i' ), 'î' , 'i' ), 'ï' , 'i' ) 
										, 'ð' , 'o' ), 'ò' , 'o' ), 'ó' , 'o' ), 'ô' , 'o' ), 'õ' , 'o' ), 'ö' , 'o' ) 
										, 'ù' , 'u' ), 'ú' , 'u' ), 'û' , 'u' ), 'ü' , 'u' ) 
										, 'ý' , 'y' ), 'ÿ' , 'y' ) 
										, '-' , ' ' ), '\'' , ' ' ) 
									)=\"".$LastName."\" 
									AND UPPER(                 
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(          
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
										REPLACE(REPLACE(REPLACE(REPLACE(
										Prenom  
										, 'à' , 'a' ), 'á' , 'a' ), 'â' , 'a' ), 'ã' , 'a' ), 'ä' , 'a' ),'å' , 'a' )     
										, 'ç' , 'c' )      
										, 'è' , 'e' ), 'é' , 'e' ), 'ê' , 'e' ), 'ë' , 'e' )
										, 'ì' , 'i' ), 'í' , 'i' ), 'î' , 'i' ), 'ï' , 'i' ) 
										, 'ð' , 'o' ), 'ò' , 'o' ), 'ó' , 'o' ), 'ô' , 'o' ), 'õ' , 'o' ), 'ö' , 'o' ) 
										, 'ù' , 'u' ), 'ú' , 'u' ), 'û' , 'u' ), 'ü' , 'u' ) 
										, 'ý' , 'y' ), 'ÿ' , 'y' ) 
										, '-' , ' ' ), '\'' , ' ' ) 
									)=\"".$FirstName."\"  
									AND Date_Naissance='".$DateOfBirth."' ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									
									if($nbResulta>1){
										//DOUBLON DE PROFIL SUR L'EXTRANET
										$ResultatImport.="<tr><td style=\"color:#e0a974\">".$LastName." ".$FirstName." : Personne en doublon dans l'extranet</td></tr>";
										$sheetR->setCellValue('A'.$ligneR,utf8_encode($LastName." ".$FirstName." : Personne en doublon dans l'extranet"));
										$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e0a974'))));
										$ligneR++;
									}
									elseif($nbResulta==1){
										//CAS POUR DE LA MISE A JOUR 
										$row=mysqli_fetch_array($result);
										$Id=$row['Id'];
	
										//Vérifier si matricule WD n'appartient pas à 2 personnes différentes dans l'extranet
										$req="SELECT Id FROM new_rh_etatcivil WHERE Id<>".$row['Id']." AND MatriculeDaher='".$WorkerId."' ";
										$resultMatricule=mysqli_query($bdd,$req);
										$nbMatricule=mysqli_num_rows($resultMatricule);
										
										if($nbMatricule>0){
											$ResultatImport.="<tr><td style=\"color:#e0a974\">".$LastName." ".$FirstName." : Mise à jour impossible [Doublon du matricule Daher dans l'extranet] </td></tr>";
											$sheetR->setCellValue('A'.$ligneR,utf8_encode($LastName." ".$FirstName." : Mise à jour impossible [Doublon du matricule Daher dans l'extranet]"));
											$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e0a974'))));
											$ligneR++;
										}
										else{
											//Actualisation des informations 
											//A ACTIVER
											/*
											$req="UPDATE new_rh_etatcivil 
											SET Sexe='".$Gender."',
												Nationalite='".addslashes($Nationality)."',
												Ville_Naissance='".addslashes($CityOfBirth)."',
												Contrat='".$WorkerType."',
												MatriculeDaher='".$WorkerId."' ";
											//MAJ adresse mail si renseignée
											if($EmailHome<>""){
												$req.=" ,Email='".addslashes($EmailHome)."' ";
											}
											$req.=" WHERE Id=".$row['Id']." ";
											$resultUpdt=mysqli_query($bdd,$req);*/
										}
									}
									else{
										//Vérifier si matricule WD n'appartient pas à 2 personnes différentes 
										$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeDaher='".$WorkerId."' ";
										$resultMatricule=mysqli_query($bdd,$req);
										$nbMatricule=mysqli_num_rows($resultMatricule);
										
										if($nbMatricule>0){
											$ResultatImport.="<tr><td style=\"color:#e0a974\">".$LastName." ".$FirstName." : Personne non ajoutée [Matricule Daher déjà existant dans l'extranet] </td></tr>";
											$sheetR->setCellValue('A'.$ligneR,utf8_encode($LastName." ".$FirstName." : Personne non ajoutée [Matricule Daher déjà existant dans l'extranet]"));
											$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e0a974'))));
											$ligneR++;
										}
										else{
											//Créer la personne (+ envoi des identifiants)
											
											$Login=str_replace("'","",strtolower(substr($FirstName,0,1).$LastName));
											$Login=str_replace(" ","",$Login);
											
											//Vérifier existance Login dans la base
											$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$Login."%'";
											$result=mysqli_query($bdd,$select);
											$nbResulta=mysqli_num_rows($result);
											if($nbResulta>0){$Login=$Login.$nbResulta;}
											
											$MotDePasse=chaine_aleatoire(8);
											
											/*$req="INSERT INTO new_rh_etatcivil 
												(Nom,Prenom,Sexe,Nationalite,Date_Naissance,Ville_Naissance,Email,Contrat,
												MatriculeDaher,Login,Motdepasse)
											VALUES 
												(\"".addslashes($LastName)."\",\"".addslashes($FirstName)."\",\"".$Gender."\",\"".addslashes($Nationality)."\",\"".$DateOfBirth."\"
												,\"".addslashes($CityOfBirth)."\",\"".addslashes($EmailHome)."\",\"".$WorkerType."\",\"".$WorkerId."\",\"".$Login."\",\"".$MotDePasse."\")
											";
											$resultAjout=mysqli_query($bdd,$req);
											$Id_Personne=mysqli_insert_id($bdd);*/
											
											$ResultatImport.="<tr><td style=\"color:#198306\">".$LastName." ".$FirstName." : Création du profil dans l'extranet. Veuillez renseigner son UER, métier et prestation dans son profil</td></tr>";
											$sheetR->setCellValue('A'.$ligneR,utf8_encode($LastName." ".$FirstName." : Création du profil dans l'extranet. Veuillez renseigner son UER, métier et prestation dans son profil"));
											$sheetR->getStyle('A'.$ligneR)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'198306'))));
											$ligneR++;
											//Envoi d'un Email pour informer l'utilisateur
											
											//A ACTIVER
											/*
											//GenererMailIdentifiantsExtranet($LastName,$FirstName,$Login,$MotDePasse,$DateOfBirth,$EmailHome,$_SESSION['Langue']);
											//GenererMailIdentifiantsExtranet($LastName,$FirstName,$Login,$MotDePasse,$DateOfBirth,"p.fauge@daher.com",$_SESSION['Langue']);
											*/
										}
									}
								}
							}
							
						}
					}
				}
				
				//Free up some of the memory 
				$objPHPExcel->disconnectWorksheets(); 
				unset($objPHPExcel);
			}
			
			ExcelRapport($workbook);
			
			//Envoi d'un email avec le résultat de l'import à la personne connectée -> $ResultatImport
			if($ResultatImport<>""){
				//$Email=$_SESSION['EmailPro'];
				$Email="p.fauge@daher.com";
				if($Email<>"")
				{
					$PJ = array();
			
					$pj_itemLieu = array();
					$pj_itemLieu['chemin'] = 'Workday/';
					$pj_itemLieu['nom'] = 'Rapport_ImportWD.xlsx';
					$pj_itemLieu['MIME-Type'] = mime_content_type('Workday/Rapport_ImportWD.xlsx');
					$pj_itemLieu['attachement'] = encoderFichier('Workday/Rapport_ImportWD.xlsx');
					
					array_push($PJ, $pj_itemLieu);
			
					$sujet="OPTEA - Import Workday / Extranet - Rapport du ".date('d/m/Y')." ";
					$message_html="";
					
					envoyerMailRH($Email, $sujet, "", $message_html, $PJ);
				}
			}
			
			
		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

?>

<form id="formulaire" class="test" enctype="multipart/form-data" action="Import_Personne.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#87ceff;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>"; 
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Importer nouveau personnel";}else{echo "Import new personnel";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td height="5"></td></tr>
				<tr>
					<td class="Libelle" width="10%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier Workday";}else{echo "Workday file";}?> : </td>
					<td width="20%"><input name="fichier" type="file" onChange="CheckFichier();"></td>
					<td width="80%">
						<input class="Bouton" type="submit" id="btnImporter" name="btnImporter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
					</td>
				</tr>
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
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>