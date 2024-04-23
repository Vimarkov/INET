<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function ExporterCIA(pole){
			window.open("Export_CIA.php?pole="+pole,"PageExport","status=no,menubar=no,width=110,height=60,scrollbars=1");
			}
	</script>
</head>
<?php
require("../../../Menu.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$export=-1;
$pole=0;
$NumLigne=-1;
if(substr($_SESSION['DroitSP'],1,1)=='1'){
if(isset($_POST['submiRecuperer'])){
	$pole=$_POST['pole'];
	//Eporter les IC à traiter du pôle
	$req="SELECT sp_ficheintervention.Id ";
	$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
	$req.="WHERE sp_ficheintervention.Id_Pole=".$_POST['pole']." AND (sp_ficheintervention.EtatICCIA='A TRAITER' OR sp_ficheintervention.EtatICCIA='VALIDEE') AND sp_ficheintervention.TypeCIA<>'' AND sp_ficheintervention.StatutICCIA<6 AND sp_ficheintervention.Vacation<>'' AND sp_ficheintervention.DateIntervention>'0001-01-01' ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	$export=$nbResulta;
	if ($nbResulta>0){
		echo "<script>ExporterCIA('".$pole."')</script>";
	}

}
elseif(isset($_POST['submitValider'])){
	$DirFichier="Extract ACP/EtatIC.xlsx";
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
}
?>


<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form name="formProjet"  enctype="multipart/form-data" class="test" method="POST" action="CIA.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Récupérer les IC non clôturées</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			Pôle :
			<select id="pole" name="pole">
				<?php
					$req="SELECT Id, IF(Id=5,'STRUCTURE',Libelle) AS Libelle FROM new_competences_pole WHERE (Id IN (1,2,3,5,6,42) AND Actif=0 AND Id_Prestation=255) OR Id=176 ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							if($row['Id']==$pole){$selected="selected";}
							echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
						}
					}
				?>
			</select>
			<input class="Bouton" name="submiRecuperer" type="submit" value='Recuperer'>
			<?php
				if($export==0){echo " Aucune IC à récupérer !";}
				elseif($export>0){echo " Vous avez récupéré ".$export." IC ";}
			?>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Importer les IC traitées</td>
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
			<input class="Bouton" name="submitValider" type="submit" value='Importer IC traitées'> <?php if($NumLigne>-1){echo "Vous avez mis à jour ".$NumLigne." IC";}?>
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
