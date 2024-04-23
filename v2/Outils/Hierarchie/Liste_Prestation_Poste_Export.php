<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

$etat = $_GET['Etat'];
$IdPersonne = $_GET['Id_Personne'];

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$requete="SELECT Id AS IDPrestation, 
	Id_Plateforme,
	Libelle AS LibellePrestation,
	0 AS IdPole,
	'' AS LibellePole,
	Active
	FROM new_competences_prestation 
	WHERE Id NOT IN (
		SELECT Id_Prestation
		FROM new_competences_pole  
		WHERE new_competences_pole.Actif=0
	)
	AND Id_Plateforme=".$_GET['Id_Plateforme']." ";
$requete.="UNION
	
	SELECT Id_Prestation AS IDPrestation,
	new_competences_prestation.Id_Plateforme,
	new_competences_prestation.Libelle AS LibellePrestation,
	new_competences_pole.Id AS IdPole,
	new_competences_pole.Libelle AS LibellePole,
	Active
	FROM new_competences_pole
	INNER JOIN new_competences_prestation
	ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
	AND new_competences_pole.Actif=0
	AND new_competences_prestation.Id_Plateforme=".$_GET['Id_Plateforme']." ";
$requete.="ORDER BY LibellePrestation, LibellePole";

$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0){

	//Ligne En-tete
	if($_SESSION["Langue"]=="FR"){
		$sheet->setCellValue('A1','Etat');
		$sheet->setCellValue('B1','Code planning');
		$sheet->setCellValue('C1','Prestation');
		$sheet->setCellValue('D1',utf8_encode('Pôle'));
	}
	else{
		$sheet->setCellValue('A1','Status');
		$sheet->setCellValue('B1','Activities code');
		$sheet->setCellValue('C1','Activities');
		$sheet->setCellValue('D1',utf8_encode('Division'));

	}

	$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE Id<=5 OR Id=22 ORDER BY Id ASC");
	$NbLignePoste=mysqli_num_rows($resultPoste);
	$colonne = 4;
	while($rowPoste=mysqli_fetch_array($resultPoste)){
		$sheet->setCellValueByColumnAndRow($colonne,1,utf8_encode($rowPoste[1]));
		$colonne++;
	}
	$sheet->getDefaultColumnDimension()->setWidth(25);
	$sheet->getColumnDimension('A')->setWidth(5);
	$sheet->getColumnDimension('D')->setWidth(15);
	
	$Couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($result)){
		if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
		else{$Couleur="EEEEEE";}
		$sheet->getStyle('A'.$ligne.':N'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		
		if ($row['Active'] == "0"){
			$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode('A'));
		}
		else{
			$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode('NA'));
		}
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode(substr($row['LibellePrestation'],0,7)));
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($row['LibellePrestation']));
		$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($row['LibellePole']));
		
		$requetePersonnePoste2="SELECT new_competences_personne_poste_prestation.Id_Poste, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom";
		$requetePersonnePoste2.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
		$requetePersonnePoste2.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
		$requetePersonnePoste2.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row['IDPrestation'];
		if($row['IdPole']>0){$requetePersonnePoste2.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['IdPole'];}
		$requetePersonnePoste2.=" AND new_competences_personne_poste_prestation.Backup<>0";
		$resultPersonnePoste2=mysqli_query($bdd,$requetePersonnePoste2);
		$NbLignePersonnePoste2=mysqli_num_rows($resultPersonnePoste2);
		
		$requetePersonnePoste="SELECT new_competences_personne_poste_prestation.Id_Poste, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom";
		$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
		$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
		$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row['IDPrestation'];
		if($row['IdPole']>0){$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['IdPole'];}
		$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Backup=0";
		$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
		$NbLignePersonnePoste=mysqli_num_rows($resultPersonnePoste);
		mysqli_data_seek($resultPoste,0);
		$colonne = 4;
		$colonneL = "E";
		while($rowPoste=mysqli_fetch_array($resultPoste)){
			if($rowPoste[0]==1){$titre = stripslashes($rowPoste[1])." : \n";}
			else{$titre = "Backup ".stripslashes($rowPoste[1])." : \n";}
			if($NbLignePersonnePoste2>0)
			{
				mysqli_data_seek($resultPersonnePoste2,0);
				while($rowPersonnePoste2=mysqli_fetch_array($resultPersonnePoste2))
				{
					if($rowPersonnePoste2[0]==$rowPoste[0]){$titre .= stripslashes($rowPersonnePoste2[1]." ".$rowPersonnePoste2[2])."\n";}
				}
			}
			
			if($NbLignePersonnePoste>0){
				mysqli_data_seek($resultPersonnePoste,0);
				while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste)){
					if($rowPersonnePoste[0]==$rowPoste[0]){
						$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode($rowPersonnePoste[1]." ".$rowPersonnePoste[2]));
					}
				}
				
			}
			
			$sheet->getComment($colonneL.$ligne)->getText()->createTextRun(utf8_encode($titre));
			$sheet->getComment($colonneL.$ligne)->setWidth('150pt');
			$sheet->getComment($colonneL.$ligne)->setHeight('100pt');
			
			$colonne++;
			$colonneL++;
		}
		$ligne ++;
	}
}

$sheet->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:N1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:N1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
 
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="ResponsablesPrestations.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/ResponsablesPrestations.xlsx';
$writer->save($chemin);
readfile($chemin);

mysqli_free_result($result);	// Libération des résultats
mysqli_close($bdd);	// Fermeture de la connexion
?>