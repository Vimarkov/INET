<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="../../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
require("../../../Menu.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

if($_SESSION['Id_PersonneSP']==1351 || $_SESSION['Id_PersonneSP']==406 || $_SESSION['Id_PersonneSP']==3527 || $_SESSION['Id_PersonneSP']==3737 || $_SESSION['Id_PersonneSP']==194 || $_SESSION['Id_PersonneSP']==198 || $_SESSION['Id_PersonneSP']==1870){
if(isset($_POST['submitValider'])){
	$DirFichier="Extract ACP/Extract ACP.xlsx";
	//****TRANSFERT FICHIER****
	if($_FILES['fichier']['name']!=""){
		$SrcProblem = "";
		$tmp_file=$_FILES['fichier']['tmp_name'];
		if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable";$Problem=1;$NomFichier="";}
		else{
			//On verifie l'extension
			$type_file=strrchr($_FILES['fichier']['name'], '.'); 
			if($type_file !='.xlsx')
				{$SrcProblem.="<br>Le fichier doit être au format .xlsx";$Problem=1;$NomImage="";}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="<br>Le fichier est trop volumineux";$Problem=1;$NomFichier="";}
				else{
					if(!unlink($DirFichier)){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}
					if(!move_uploaded_file($tmp_file,$DirFichier))
						{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";};
				}
			}
		}
		if($SrcProblem<>""){
			echo $SrcProblem;
		}
		else{
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
			$result=mysqli_query($bdd,"DELETE FROM sp_donneeacp;");
			
			$XLSXDocument = new PHPExcel_Reader_Excel2007();
			
			/**  Define how many rows we want to read for each "chunk"  **/ 
			$chunkSize = 1000;
			/**  Create a new Instance of our Read Filter  **/ 
			$chunkFilter = new chunkReadFilter(); 
			/**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/ 
			$XLSXDocument->setReadFilter($chunkFilter); 
			
			/**  Loop to read our worksheet in "chunk size" blocks  **/ 
			/**  $startRow is set to 2 initially because we always read the headings in row #1  **/
			$STOP=0;
			for ($startRow = 2; $startRow <= 65000; $startRow += $chunkSize) {
				if($STOP==0){
					/**  Tell the Read Filter, the limits on which rows we want to read this iteration  **/ 
					$chunkFilter->setRows($startRow,$chunkSize); 
					/**  Load only the rows that match our filter from $inputFileName to a PHPExcel Object  **/ 
					$objPHPExcel = $XLSXDocument->load('Extract ACP/Extract ACP.xlsx'); 
					//    Do some processing here 
					$sheet = $objPHPExcel->getSheet(0);

					for($ligne=$startRow;$ligne<$startRow+$chunkSize;$ligne++){
						if($sheet->getCell('A'.$ligne)->getValue()==""){$STOP=1;}
						if($STOP==0){
							$req="INSERT INTO sp_donneeacp(ACP_Id,MSN,MCA,Type,Reference,LienHierarchique,Caec,ATASubATA,StatutUtilisateur,TAI_Restant,";
							$req.="DateCreation,DateFinRevisee,RespInterne,RespN_1,Macro_Cible,Cible,Sous_Cible) VALUES (";
							for($column = 'A'; $column<='Q'; $column++){
								
								if($column=='K' || $column=='L'){
									$InvDate= $sheet->getCell($column.$ligne)->getValue();
									if($InvDate<>""){$InvDate = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($InvDate));} 
									else{$InvDate="0001-01-01";}
									$req.= "'".$InvDate."',";
								}
								else{
									$req.= "'".addslashes($sheet->getCell($column.$ligne)->getValue())."',";
								}
							}
							$req=substr($req,0,-1);
							$req.=");";
							$result=mysqli_query($bdd,$req);
						}
					}

					//    Free up some of the memory 
					$objPHPExcel->disconnectWorksheets(); 
					unset($objPHPExcel);
				}
			}
			
			//MSN
			//Supprimer le contenu de la table sp_msnposte
			$result=mysqli_query($bdd,"DELETE FROM sp_msnposte;");

			$XLSXDocument = new PHPExcel_Reader_Excel2007();
			$XLSXDocument->setLoadSheetsOnly(array('Position_Avion'));
			$Excel = $XLSXDocument->load('Extract ACP/Extract ACP.xlsx');

			/**
			* récupération de la première feuille du fichier Excel
			* @var PHPExcel_Worksheet $sheet
			*/
			$sheet = $Excel->getSheet(0);
			 
			// On boucle sur les lignes
			$NumLigne=1;
			$req="";
			foreach ($sheet->getRowIterator() as $lig => $row){				
				// On boucle sur les cellule de la ligne		
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$NumCol=1;
				$poste0="";
				$poste1="";
				$poste2="";
				$poste3="";
				$poste4="";
				$poste5="";
				if($NumLigne>=3 && $NumLigne<=49){
					foreach ($cellIterator as $col => $cell) {
						if($NumCol==1){
							if($cell->getValue()<>""){
								$req="INSERT INTO sp_msnposte(MSN,Poste) VALUES ('".addslashes($cell->getValue())."','Hors AAA');";
								$result=mysqli_query($bdd,$req);
							}
						}
						switch($NumCol){
							case 4:
								$poste1=addslashes($cell->getValue());
								break;
							case 5:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste1."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste1."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 6 :
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste1." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste1." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 7:
								$poste2=addslashes($cell->getValue());
								break;
							case 8 :
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste2."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste2."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 9:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste2." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste2." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 10:
								$poste3=addslashes($cell->getValue());
								break;
							case 11:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste3."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste3."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 12:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste3." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste3." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 13:
								$poste4=addslashes($cell->getValue());
								break;
							case 14:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste4."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste4."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 15:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste4." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste4." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 16:
								$poste5=addslashes($cell->getValue());
								break;
							case 17:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste5."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste5."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 18:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste5." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste5." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
						}
						$NumCol++;
					}
				 
			   }
			   elseif($NumLigne>=52){
					foreach ($cellIterator as $col => $cell) {
						switch($NumCol){
							case 1:
								$poste0=addslashes($cell->getValue());
								break;
							case 2:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste0."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste0."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 3:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste0." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste0." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 4:
								$poste1=addslashes($cell->getValue());
								break;
							case 5:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste1."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste1."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 6 :
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste1." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste1." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 7:
								$poste2=addslashes($cell->getValue());
								break;
							case 8 :
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste2."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste2."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 9:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste2." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste2." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 10:
								$poste3=addslashes($cell->getValue());
								break;
							case 11:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste3."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste3."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 12:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste3." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste3." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 13:
								$poste4=addslashes($cell->getValue());
								break;
							case 14:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste4."'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Poste,Id_Pole) VALUES ('".addslashes($cell->getValue())."','".$poste4."',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 15:
								if($cell->getValue()<>""){
									$idPole=0;
									$reqPole="SELECT Id_Pole FROM sp_poste_pole WHERE poste='".$poste4." Suivant'";
									$resultPole=mysqli_query($bdd,$reqPole);
									$nbPole=mysqli_num_rows($resultPole);
									if ($nbPole > 0){
										$row=mysqli_fetch_array($resultPole);
										$idPole=$row['Id_Pole'];
									}
									$req="INSERT INTO sp_msnposte(MSN,Post,Id_Polee) VALUES ('".addslashes($cell->getValue())."','".$poste4." Suivant',".$idPole.");";
									$result=mysqli_query($bdd,$req);
								}
								break;
							case 16:
								if($cell->getValue()<>""){
									$req="INSERT INTO sp_msnposte(MSN,Poste) VALUES ('".addslashes($cell->getValue())."','TLS - DELIVERY');";
									$result=mysqli_query($bdd,$req);
								}
								break;
						}
						$NumCol++;
					}
				 
			   }
				$NumLigne++;
			}
		}
	}
}
?>


<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form name="formProjet"  enctype="multipart/form-data" class="test" method="POST" action="ImportACP.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Importer Extract ACP</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr style="display:none;">
		<td>
			<input type="hidden" name="MAX_FILE_SIZE" value="30000000">
		</td>
	</tr>
	<tr>
		<td align="left">
			<input name="fichier" type="file">
			<font color="#FF0000" size="-2">Limite de taille du fichier à 3 Mo.</font>
			<input class="Bouton" name="submitValider" type="submit" value='Importer Extract ACP'>
		</td>
	</tr>
</form>
</table>

<?php
}
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>