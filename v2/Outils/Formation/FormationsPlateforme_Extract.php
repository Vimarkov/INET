<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode('Formations'));
	$sheet->setCellValue('A1',utf8_encode("Référence"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Recyclage différent"));
	$sheet->setCellValue('D1',utf8_encode("Intitulé"));
	$sheet->setCellValue('E1',utf8_encode("Organisme"));
	$sheet->setCellValue('F1',utf8_encode("Qualifications aquises"));
	$sheet->setCellValue('G1',utf8_encode("Coût salarié AAA"));
	$sheet->setCellValue('H1',utf8_encode("Coût intérimaire"));
	$sheet->setCellValue('I1',utf8_encode("Nb jours"));
	$sheet->setCellValue('J1',utf8_encode("Durée (heure)"));
	$sheet->setCellValue('K1',utf8_encode("Est une formation tuteur"));
	$sheet->setCellValue('L1',utf8_encode("Langue d'affichage"));
	$sheet->setCellValue('M1',utf8_encode("Langue des documents par défaut"));
	$sheet->setCellValue('N1',utf8_encode("Qualifications non acquises"));
	$sheet->setCellValue('O1',utf8_encode("Formations correspondantes"));
	$sheet->setCellValue('P1',utf8_encode("Documents à signer"));
	$sheet->setCellValue('Q1',utf8_encode("Description"));
	$sheet->setCellValue('R1',utf8_encode("Description recyclage"));
	$sheet->setCellValue('S1',utf8_encode("Catégorie"));
}
else{
	$sheet->setTitle(utf8_encode('Training'));
	$sheet->setCellValue('A1',utf8_encode("Reference"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Different Recycling"));
	$sheet->setCellValue('D1',utf8_encode("Entitled"));
	$sheet->setCellValue('E1',utf8_encode("Organism"));
	$sheet->setCellValue('F1',utf8_encode("Qualifications acquired"));
	$sheet->setCellValue('G1',utf8_encode("Cost per AAA employee"));
	$sheet->setCellValue('H1',utf8_encode("Interim cost"));
	$sheet->setCellValue('I1',utf8_encode("Number of days"));
	$sheet->setCellValue('J1',utf8_encode("Time (hours)"));
	$sheet->setCellValue('K1',utf8_encode("Is a tutor training"));
	$sheet->setCellValue('L1',utf8_encode("Display language"));
	$sheet->setCellValue('M1',utf8_encode("Default document language"));
	$sheet->setCellValue('N1',utf8_encode("Unqualified qualifications"));
	$sheet->setCellValue('O1',utf8_encode("Corresponding trainings"));
	$sheet->setCellValue('P1',utf8_encode("Documents to sign"));
	$sheet->setCellValue('Q1',utf8_encode("Description"));
	$sheet->setCellValue('R1',utf8_encode("Description recycling"));
	$sheet->setCellValue('S1',utf8_encode("Category"));
}
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(70);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);
$sheet->getColumnDimension('L')->setWidth(20);
$sheet->getColumnDimension('M')->setWidth(20);
$sheet->getColumnDimension('N')->setWidth(70);
$sheet->getColumnDimension('O')->setWidth(40);
$sheet->getColumnDimension('P')->setWidth(40);
$sheet->getColumnDimension('Q')->setWidth(60);
$sheet->getColumnDimension('R')->setWidth(60);
$sheet->getColumnDimension('S')->setWidth(40);


$sheet->getStyle('A1:S1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:S1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:S1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:S1')->getFont()->setBold(true);
$sheet->getStyle('A1:S1')->getFont()->getColor()->setRGB('1f49a6');

//FORMATIONS SMQ + PLATEFORME
$requeteFormation="SELECT Id, Id_Plateforme, Reference, Id_TypeFormation,Categorie, ";
$requeteFormation.="(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation, ";
$requeteFormation.="Tuteur, Recyclage, Id_Personne_MAJ, ";
$requeteFormation.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) as Personne_MAJ, Date_MAJ ";
$requeteFormation.="FROM form_formation WHERE Suppr=0 AND (Id_Plateforme=0 OR Id_Plateforme=".$_GET['Id_Plateforme'].")  ";
$requeteFormation.="ORDER BY Reference ASC";
$resultFormation=mysqli_query($bdd,$requeteFormation);
$nbFormation=mysqli_num_rows($resultFormation);

//QUALIFICATIONS
$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif ";
$requeteQualifications.=" FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre";
$requeteQualifications.=" WHERE ";
$requeteQualifications.=" form_formation_qualification.Id_Qualification=new_competences_qualification.Id";
$requeteQualifications.=" AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id";
$requeteQualifications.=" AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id";
$requeteQualifications.=" AND form_formation_qualification.Suppr=0 AND form_formation_qualification.Masquer=0 ";
$requeteQualifications.=" ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, new_competences_categorie_qualification.Libelle ASC,new_competences_qualification.Libelle ASC";
$resultQualifications=mysqli_query($bdd,$requeteQualifications);
$nbQualifs=mysqli_num_rows($resultQualifications);

//QUALIFICATIONS NON ACQUISES
$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif ";
$requeteQualifications.=" FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre";
$requeteQualifications.=" WHERE ";
$requeteQualifications.=" form_formation_qualification.Id_Qualification=new_competences_qualification.Id";
$requeteQualifications.=" AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id";
$requeteQualifications.=" AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id";
$requeteQualifications.=" AND form_formation_qualification.Suppr=0 AND form_formation_qualification.Masquer=1 ";
$requeteQualifications.=" ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, new_competences_categorie_qualification.Libelle ASC,new_competences_qualification.Libelle ASC";
$resultQualificationsNonA=mysqli_query($bdd,$requeteQualifications);
$nbQualifsNonA=mysqli_num_rows($resultQualificationsNonA);

$requeteInfos="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Langue";
$resultInfos=mysqli_query($bdd,$requeteInfos);
$nbInfos=mysqli_num_rows($resultInfos);

//PARAMETRE PLATEFORME
$requeteParam="SELECT Id,Id_Formation,Id_Langue,CoutSalarieAAA,CoutInterimaire,Duree,CoutSalarieAAARecyclage,CoutInterimaireRecyclage,DureeRecyclage,NbJour,NbJourRecyclage,";
$requeteParam.= "(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme, ";
$requeteParam.= "(SELECT Libelle FROM form_langue WHERE form_langue.Id=Id_Langue) AS Langue, ";
$requeteParam.= "(SELECT Libelle FROM form_langue WHERE form_langue.Id=Id_Langue_Document) AS LangueDocument ";
$requeteParam.="FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$_GET['Id_Plateforme']." ";
$resultParam=mysqli_query($bdd,$requeteParam);
$nbParam=mysqli_num_rows($resultParam);

//Formation compétences
$requeteFormCompetence="SELECT Id,Id_Formation,Id_FormationCompetence,
		(SELECT Libelle FROM new_competences_formation WHERE Id=Id_FormationCompetence) AS Formation 
		FROM form_formation_formationcompetence WHERE Suppr=0 ORDER BY Formation";
$resultFormCompetence=mysqli_query($bdd,$requeteFormCompetence);
$nbFormCompetence=mysqli_num_rows($resultFormCompetence);

//Documents à signer
$requeteFormDoc="SELECT Id,Id_Formation,Id_Document,
		(SELECT Reference FROM form_document WHERE Id=Id_Document) AS Document 
		FROM form_formation_document WHERE Suppr=0 ORDER BY Document";
$resultFormDoc=mysqli_query($bdd,$requeteFormDoc);
$nbFormDoc=mysqli_num_rows($resultFormDoc);

//Liste des QCM 
$req="SELECT form_formation_qualification_qcm.Id_Formation_Qualification, form_formation_qualification_qcm.Id_QCM, 
	form_qcm.Code,form_formation_qualification_qcm.Id_Langue, ";
$req.="(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_formation_qualification_qcm.Id_Langue) AS Langue ";
$req.="FROM form_formation_qualification_qcm LEFT JOIN form_qcm ";
$req.="ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id ";
$req.="WHERE form_formation_qualification_qcm.Suppr=0 ORDER BY Code";
$resultQCM=mysqli_query($bdd,$req);
$nbResultaQCM=mysqli_num_rows($resultQCM);

if ($nbFormation>0){
	$i=2;
	while($row=mysqli_fetch_array($resultFormation)){
		$Id_Langue=0;
		$Cout="";
		$Duree="";
		$CoutR="";
		$DureeR="";
		$NbJour="";
		$NbJourR="";
		$CoutInterim="";
		$CoutInterimR="";
		$Organisme="";
		$Tuteur="";
		$Langue="";
		$LangueDocument="";
		$Description="";
		$DescriptionRecyclage="";
		if($nbParam>0){
			mysqli_data_seek($resultParam,0);
			while($rowParam=mysqli_fetch_array($resultParam)){
				if($rowParam['Id_Formation']==$row['Id']){
					$Id_Langue=$rowParam['Id_Langue'];
					$Cout=$rowParam['CoutSalarieAAA'];
					$CoutInterim=$rowParam['CoutInterimaire'];
					$Duree=str_replace(".",":",$rowParam['Duree']);
					$NbJour=$rowParam['NbJour'];
					$CoutR=$rowParam['CoutSalarieAAARecyclage'];
					$CoutInterimR=$rowParam['CoutInterimaireRecyclage'];
					$DureeR=$rowParam['DureeRecyclage'];
					$NbJourR=$rowParam['NbJourRecyclage'];
					$Organisme=$rowParam['Organisme'];
					$Langue=$rowParam['Langue'];
					$LangueDocument=$rowParam['LangueDocument'];
				}
			}
		}
		$Infos="";
		$InfosR="";
		if($nbInfos>0){
			mysqli_data_seek($resultInfos,0);
			while($rowInfo=mysqli_fetch_array($resultInfos)){
				if($rowInfo['Id_Formation']==$row['Id'] && $rowInfo['Id_Langue']==$Id_Langue){
					$Infos=stripslashes($rowInfo['Libelle'])."";
					$InfosR=stripslashes($rowInfo['LibelleRecyclage'])."";
					$Description=stripslashes($rowInfo['Description'])."";
					$DescriptionRecyclage=stripslashes($rowInfo['DescriptionRecyclage'])."";
				}
			}
		}
		
		$FormationCompetences="";
		if($nbFormCompetence>0){
			mysqli_data_seek($resultFormCompetence,0);
			while($rowFormCompetences=mysqli_fetch_array($resultFormCompetence)){
				if($rowFormCompetences['Id_Formation']==$row['Id']){
					if($FormationCompetences<>""){$FormationCompetences.="\n";}
					$FormationCompetences.="- ".stripslashes($rowFormCompetences['Formation'])."";
				}
			}
		}
		
		$Documents="";
		if($nbFormDoc>0){
			mysqli_data_seek($resultFormDoc,0);
			while($rowFormDoc=mysqli_fetch_array($resultFormDoc)){
				if($rowFormDoc['Id_Formation']==$row['Id']){
					if($Documents<>""){$Documents.="\n";}
					$Documents.="- ".stripslashes($rowFormDoc['Document'])."";
				}
			}
		}
		
		if($Infos==""){
			if($LangueAffichage=="FR"){$Infos="A DEFINIR";}else{$Infos="TO DEFINE";}
		}
		$qualifications="";
		if($nbQualifs>0){
			mysqli_data_seek($resultQualifications,0);
			while($rowQualif=mysqli_fetch_array($resultQualifications)){
				if($rowQualif['Id_Formation']==$row['Id']){
					$qcm="";
					if($nbResultaQCM>0){
						mysqli_data_seek($resultQCM,0);
						while($rowQCM=mysqli_fetch_array($resultQCM)){
							if($rowQCM['Id_Formation_Qualification']==$rowQualif['Id']){
								$qcm.="\n         QCM : ".$rowQCM['Code']." (".$rowQCM['Langue'].")";
							}
						}
					}
					if($qualifications<>""){$qualifications.="\n";}
					$qualifications.="- ".stripslashes($rowQualif['Qualif'])." (".stripslashes($rowQualif['QualifMaitre'])." - ".stripslashes($rowQualif['CategorieQualif']).")".$qcm;
				}
			}
		}
		
		$qualificationsNonA="";
		if($nbQualifsNonA>0){
			mysqli_data_seek($resultQualificationsNonA,0);
			while($rowQualif=mysqli_fetch_array($resultQualificationsNonA)){
				if($rowQualif['Id_Formation']==$row['Id']){
					$qcm="";
					if($nbResultaQCM>0){
						mysqli_data_seek($resultQCM,0);
						while($rowQCM=mysqli_fetch_array($resultQCM)){
							if($rowQCM['Id_Formation_Qualification']==$rowQualif['Id']){
								$qcm.="\n         QCM : ".$rowQCM['Code']." (".$rowQCM['Langue'].")";
							}
						}
					}
					if($qualificationsNonA<>""){$qualificationsNonA.="\n";}
					$qualificationsNonA.="- ".stripslashes($rowQualif['Qualif'])." (".stripslashes($rowQualif['QualifMaitre'])." - ".stripslashes($rowQualif['CategorieQualif']).")".$qcm;
				}
			}
		}
		
		$btrouve=1;
		if($_GET['motcle']<>""){
			if(stripos($row['Reference'],$_GET['motcle'])===false && stripos($Infos,$_GET['motcle'])===false && stripos($row['TypeFormation'],$_GET['motcle'])===false && stripos($qualifications,$_GET['motcle'])===false && stripos($Organisme,$_GET['motcle'])===false){
				$btrouve=0;
			}
			else{
				$btrouve=1;
			}
		}
		if($btrouve==1){
			if($LangueAffichage=="FR"){
				if($row['Recyclage']==1){$recyclage="Oui";}else{$recyclage="Non";};
				if($row['Tuteur']==1){$Tuteur="Oui";}else{$Tuteur="Non";};
			}
			else{
				if($row['Recyclage']==1){$recyclage="Yes";}else{$recyclage="No";};
				if($row['Tuteur']==1){$Tuteur="Yes";}else{$Tuteur="No";};
			}
			$CoutAAA="";
			$CoutInt="";
			$NbJourAff="";
			$DureeAff="";
			$InfosAff="";
			if($row['Recyclage']==1){
				if($LangueAffichage=="FR"){
					$CoutAAA="Initial : ".$Cout."\nRecyclage : ".$CoutR;
					$CoutInt="Initial : ".$CoutInterim."\nRecyclage : ".$CoutInterimR;
					$NbJourAff="Initial : ".$NbJour."\nRecyclage : ".$NbJourR;
					$DureeAff="Initial : ".$Duree."\nRecyclage : ".$DureeR;
					$InfosAff="Initial : ".$Infos."\nRecyclage : ".$InfosR;
				}
				else{
					$CoutAAA="Initial : ".$Cout."\nRecycling : ".$CoutR;
					$CoutInt="Initial : ".$CoutInterim."\nRecycling : ".$CoutInterimR;
					$NbJourAff="Initial : ".$NbJour."\nRecycling : ".$NbJourR;
					$DureeAff="Initial : ".$Duree."\nRecycling : ".$DureeR;
					$InfosAff="Initial : ".$Infos."\nRecycling : ".$InfosR;
				}
				
				if($Cout==""){if($LangueAffichage=="FR"){$CoutAAA="A DEFINIR";}else{$CoutAAA="TO DEFINE";}}
				if($CoutInterim==""){if($LangueAffichage=="FR"){$CoutInt="A DEFINIR";}else{$CoutInt="TO DEFINE";}}
				if($NbJour==""){if($LangueAffichage=="FR"){$NbJourAff="A DEFINIR";}else{$NbJourAff="TO DEFINE";}}
				if($Duree==""){if($LangueAffichage=="FR"){$DureeAff="A DEFINIR";}else{$DureeAff="TO DEFINE";}}
				if($Infos==""){if($LangueAffichage=="FR"){$InfosAff="A DEFINIR";}else{$InfosAff="TO DEFINE";}}
			}
			else{
				$CoutAAA=$Cout;
				$CoutInt=$CoutInterim;
				$NbJourAff=$NbJour;
				$DureeAff=$Duree;
				$InfosAff=$Infos;
				
				if($CoutAAA==""){if($LangueAffichage=="FR"){$CoutAAA="A DEFINIR";}else{$CoutAAA="TO DEFINE";}}
				if($CoutInt==""){if($LangueAffichage=="FR"){$CoutInt="A DEFINIR";}else{$CoutInt="TO DEFINE";}}
				if($NbJourAff==""){if($LangueAffichage=="FR"){$NbJourAff="A DEFINIR";}else{$NbJourAff="TO DEFINE";}}
				if($DureeAff==""){if($LangueAffichage=="FR"){$DureeAff="A DEFINIR";}else{$DureeAff="TO DEFINE";}}
				if($InfosAff==""){if($LangueAffichage=="FR"){$InfosAff="A DEFINIR";}else{$InfosAff="TO DEFINE";}}
			}
			
			$sheet->setCellValue('A'.$i,utf8_encode($row['Reference']));
			$sheet->setCellValue('B'.$i,utf8_encode($row['TypeFormation']));
			$sheet->setCellValue('C'.$i,utf8_encode($recyclage));
			$sheet->setCellValue('D'.$i,utf8_encode($InfosAff));
			$sheet->setCellValue('E'.$i,utf8_encode($Organisme));
			$sheet->setCellValue('F'.$i,utf8_encode($qualifications));
			$sheet->setCellValue('G'.$i,utf8_encode($CoutAAA));
			$sheet->setCellValue('H'.$i,utf8_encode($CoutInt));
			$sheet->setCellValue('I'.$i,utf8_encode($NbJourAff));
			$sheet->setCellValue('J'.$i,utf8_encode($DureeAff));
			$sheet->setCellValue('K'.$i,utf8_encode($Tuteur));
			$sheet->setCellValue('L'.$i,utf8_encode($Langue));
			$sheet->setCellValue('M'.$i,utf8_encode($LangueDocument));
			$sheet->setCellValue('N'.$i,utf8_encode($qualificationsNonA));
			$sheet->setCellValue('O'.$i,utf8_encode($FormationCompetences));
			$sheet->setCellValue('P'.$i,utf8_encode($Documents));
			$sheet->setCellValue('Q'.$i,utf8_encode($Description));
			$sheet->setCellValue('R'.$i,utf8_encode($DescriptionRecyclage));
			$sheet->setCellValue('S'.$i,utf8_encode($row['Categorie']));
			
			$sheet->getStyle('D'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('E'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('F'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('G'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('H'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('I'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('J'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('N'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('O'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('P'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('Q'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('R'.$i)->getAlignment()->setWrapText(true);

			$sheet->getStyle('A'.$i.':S'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$i.':S'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$i.':S'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

			$i++;
		}
	}
}
				
$sheet->getSheetView()->setZoomScale(80);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="Formations_Plateforme.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Training_Platform.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/FormationPlateforme.xlsx';

$writer->save($chemin);
readfile($chemin);
?>