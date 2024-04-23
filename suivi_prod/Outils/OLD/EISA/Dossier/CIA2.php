<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
</head>
<?php
session_start();
require '../../ConnexioniSansBody.php';
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$export=-1;
$pole=0;
$NumLigne=-1;

$DirFichier="Extract/ListeOT.xlsx";
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$SrcProblem = "";
//****TRANSFERT FICHIER****
if($_FILES['fichier']['name']!=""){
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
			if(filesize($_FILES['fichier']['tmp_name'])>30000000)
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
		$XLSXDocument = new PHPExcel_Reader_Excel2007();
		$Excel = $XLSXDocument->load('Extract/ListeOT.xlsx');

		/**
		* récupération de la première feuille du fichier Excel
		*/
		$sheet = $Excel->getSheet(0);
		 
		// On boucle sur les lignes
		$NumLigne=1;
		foreach ($sheet->getRowIterator() as $lig => $row){				
			// On boucle sur les cellule de la ligne		
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			$NumCol=1;
			$MSN="";
			$OM="";
			$Article="";
			$Gamme="";
			foreach ($cellIterator as $col => $cell){
				if($NumCol==1){$MSN=trim($cell->getValue());}
				elseif($NumCol==2){$OM=trim($cell->getValue());}
				elseif($NumCol==4){$Article=trim($cell->getValue());}
				elseif($NumCol==5){$Gamme=trim($cell->getValue());}
				$NumCol++;
			}
			if($MSN<>"" && $OM<>"" && $NumLigne>0){
				//Vérifier existance
				$req="SELECT Id FROM sp_atrot WHERE MSN=".$MSN." AND OrdreMontage='".$OM."' ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($nbResulta==0){
					if($NumLigne>1){
						//Insertion nouvel ordre de montage
						$req="INSERT INTO sp_atrot (MSN,DateCreation,Id_Prestation,OrdreMontage,Designation,Article) VALUES ";
						$req.="(".$MSN.",'".$DateJour."',463,'".$OM."','".addslashes(htmlentities($Gamme))."','".$Article."');";
						$result=mysqli_query($bdd,$req);
					}
					$NumLigne++;
				}
			}
		}
		if($NumLigne>0){$NumLigne--;$NumLigne--;}
	}
}
	echo $SrcProblem;
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>