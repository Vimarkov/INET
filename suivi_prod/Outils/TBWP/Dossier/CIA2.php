<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	
</head>
<?php
require '../../ConnexioniSansBody.php';
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$export=-1;
$pole=0;
$NumLigne=-1;

$DirFichier="Extract ACP/EtatIC.xlsx";
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
		$Excel = $XLSXDocument->load('Extract ACP/EtatIC.xlsx');

		/**
		* récupération de la première feuille du fichier Excel
		*/
		$sheet = $Excel->getSheet(0);
		 
		// On boucle sur les lignes
		$NumLigne=0;
		foreach ($sheet->getRowIterator() as $lig => $row){				
			// On boucle sur les cellule de la ligne		
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			$NumCol=1;
			$Id="";
			$NumFI="";
			$Etat="";
			$statut="";
			foreach ($cellIterator as $col => $cell){
				if($NumCol==1){$Id=$cell->getValue();}
				elseif($NumCol==2){$NumFI=$cell->getValue();}
				elseif($NumCol==3){$Etat=$cell->getValue();}
				elseif($NumCol==4){$statut=$cell->getValue();}
				$NumCol++;
			}
			if($Id<>"" && $NumLigne>0 && ($statut=="" || $statut<=2 || ($statut>2 && $Etat<>""))){
				$req="UPDATE sp_ficheintervention SET ";
				if(strtoupper($Etat)=="VALIDEE" || strtoupper($Etat)=="REFUSEE" || strtoupper($Etat)=="ANNULEE"){
					$req.="EtatICCIA='".strtoupper($Etat)."', ";
				}
				if($statut<>""){
					$req.="StatutICCIA=".$statut.", ";
				}
				$req.="Commentaire='".$NumFI."', NumFI='".$NumFI."' WHERE Id=".$Id;
				$result=mysqli_query($bdd,$req);
			}
			$NumLigne++;
		}
		if($NumLigne>0){$NumLigne--;}
	}
}
	echo $SrcProblem;
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>