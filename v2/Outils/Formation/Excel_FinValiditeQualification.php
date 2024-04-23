<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$date_4mois=date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"));
$date_2mois=date("Y-m-d",strtotime(date("Y-m-d")." + 2 month"));
$date_moins_6mois=date("Y-m-d",strtotime(date("Y-m-d")." - 6 month"));

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Qualifications"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation - Pôle"));
	$sheet->setCellValue('C1',utf8_encode("Qualification"));
	$sheet->setCellValue('D1',utf8_encode("Catégorie"));
	$sheet->setCellValue('E1',utf8_encode("Date de fin"));
}
else{
	$sheet->setTitle(utf8_encode("Qualifications"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Activity - Pole"));
	$sheet->setCellValue('C1',utf8_encode("Qualification"));
	$sheet->setCellValue('D1',utf8_encode("Category"));
	$sheet->setCellValue('E1',utf8_encode("End date"));
}

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(45);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getStyle('A1:E1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:E1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:E1')->getFont()->setBold(true);
$sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('1f49a6');

//QUALIFICATIONS A REPASSER DANS LES 4 MOIS
$requeteQualificationFiltre="";
if($_SESSION['FiltreFinQualif_Qualification']<>"0"){$requeteQualificationFiltre="AND new_competences_relation.Id_Qualification_Parrainage=".$_SESSION['FiltreFinQualif_Qualification']." ";}

$requeteEtat="
	AND
		(
		SELECT
			COUNT(Id)
		FROM
			form_qualificationnecessaire_prestation
		WHERE
			form_qualificationnecessaire_prestation.Id_Relation=TAB.Id
			AND
				(
				form_qualificationnecessaire_prestation.Necessaire=0
				AND form_qualificationnecessaire_prestation.Id_Prestation=TAB.Id_Prestation
				AND form_qualificationnecessaire_prestation.Id_Pole=TAB.Id_Pole
				)
		)";
if($_SESSION['FiltreFinQualif_Etat']==0){$requeteEtat.="=0";}
else{$requeteEtat.=">0";}

if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS))
{
    $requeteDroits="
        AND new_competences_personne_prestation.Id_Prestation IN
        (
            SELECT
                Id
            FROM
                new_competences_prestation
            WHERE
                Id_Plateforme IN
                (
                    SELECT
                        Id_Plateforme
                    FROM
                        new_competences_personne_poste_plateforme
                    WHERE
                        Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS).")
                        AND Id_Personne=".$_SESSION['Id_Personne']."
                )
        ) ";
}
else
{
    $requeteDroits="
        AND CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN
        (
            SELECT
                CONCAT(Id_Prestation,'_',Id_Pole)
            FROM
                new_competences_personne_poste_prestation
            WHERE
                Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
                AND Id_Personne=".$_SESSION['Id_Personne']."
        ) ";
}
if($_SESSION['FiltreFinQualif_Caduque']=="0" || $_SESSION['FiltreFinQualif_Caduque']=="" || $_SESSION['FiltreFinQualif_Caduque']=="4"){$requeteCaduque="AND TAB.Date_Fin<='".$date_4mois."' ";}
elseif($_SESSION['FiltreFinQualif_Caduque']=="2"){$requeteCaduque="AND TAB.Date_Fin<='".$date_2mois."' ";}

$requeteQualifications="
	SELECT
		*,
	   (
			SELECT
				COUNT(form_besoin.Id)
			FROM
				form_besoin
			WHERE
				form_besoin.Suppr=0
				AND form_besoin.Motif<>'Renouvellement'
				AND form_besoin.Id_Personne=TAB.Id_Personne
				AND form_besoin.Valide >=0 
				AND form_besoin.Traite<3
				AND form_besoin.Id_Formation IN
				(
					SELECT
						form_formation_qualification.Id_Formation
					FROM
						form_formation_qualification
					WHERE
						form_formation_qualification.Suppr=0
						AND form_formation_qualification.Id_Qualification=TAB.Id_Qualification_Parrainage
				)
			) AS NbBesoin
	FROM
		(
		SELECT
			*
		FROM
			(
			SELECT
				new_competences_relation.Id,
				new_competences_relation.Id_Personne,
				new_competences_relation.Evaluation,
				new_competences_relation.Id_Qualification_Parrainage,
				new_competences_relation.Date_Fin,
				new_competences_relation.Date_Debut,
				new_competences_relation.Date_QCM,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne,
				(SELECT Libelle FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE Id=new_competences_personne_prestation.Id_Pole) AS Pole,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Id_Plateforme,
				new_competences_personne_prestation.Id_Prestation,
				new_competences_personne_prestation.Id_Pole,
				(SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif,
				(
					SELECT
					(
						SELECT
							Libelle
						FROM
							new_competences_categorie_qualification
						WHERE
							new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification
					)
					FROM
						new_competences_qualification
					WHERE
						Id=new_competences_relation.Id_Qualification_Parrainage
				) AS Categorie,(@row_number:=@row_number + 1) AS rnk ";
$requeteQualifications2="
			FROM
				new_competences_relation
			RIGHT JOIN new_competences_personne_prestation
				ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
			LEFT JOIN new_competences_qualification
				ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
			WHERE
			(
				new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
				OR new_competences_personne_prestation.Date_Fin<='0001-01-01'
			)"
				.$requeteDroits
				.$requeteQualificationFiltre."
				AND new_competences_relation.Type='Qualification' 
				AND new_competences_relation.Suppr=0
				AND new_competences_relation.Statut_Surveillance != 'REFUSE'
				AND new_competences_qualification.Duree_Validite>0
				AND new_competences_relation.Date_Debut>'0001-01-01'
				AND new_competences_relation.Date_Fin > '0001-01-01'
				AND new_competences_relation.Date_Fin >= '".$date_moins_6mois."'
			ORDER BY
				new_competences_relation.Date_Debut DESC
			) AS Tab_Qualif
		GROUP BY
			Tab_Qualif.Id_Personne,
			Tab_Qualif.Id_Prestation,
			Tab_Qualif.Id_Qualification_Parrainage
		) AS TAB
	WHERE
		TAB.Evaluation<>'B'
		AND TAB.Evaluation<>''"
		.$requeteCaduque
		.$requeteEtat."
		AND
		(
			SELECT
				COUNT(form_besoin.Id)
			FROM
				form_besoin
			WHERE
				form_besoin.Suppr=0
				AND form_besoin.Motif='Renouvellement'
				AND form_besoin.Id_Personne=TAB.Id_Personne
				AND form_besoin.Valide >=0 
				AND form_besoin.Traite<3
				AND form_besoin.Id_Formation IN
				(
					SELECT
						form_formation_qualification.Id_Formation
					FROM
						form_formation_qualification
					WHERE
						form_formation_qualification.Suppr=0
						AND form_formation_qualification.Id_Qualification=TAB.Id_Qualification_Parrainage
				)
		)=0 
		
		AND (
			(TAB.Id_Qualification_Parrainage IN (133,2145,2490,13,12,1683,75,167)
			AND 
				(
					SELECT
					   COUNT(new_competences_relation.Id)
					FROM
						new_competences_relation
					WHERE new_competences_relation.Id_Qualification_Parrainage IN (1606,2130,3258)
						AND new_competences_relation.Suppr=0
						AND new_competences_relation.Id_Personne=TAB.Id_Personne
						AND (new_competences_relation.Date_Fin <= '0001-01-01'
						OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
				)=0
			)
		
		OR
			TAB.Id_Qualification_Parrainage NOT IN (133,2145,2490,13,12,1683,75,167)
		)
		
		AND (
			SELECT
			   COUNT(new_competences_relation.Id)
			FROM
				new_competences_relation
			WHERE new_competences_relation.Id_Qualification_Parrainage=TAB.Id_Qualification_Parrainage
				AND new_competences_relation.Suppr=0
				AND new_competences_relation.Id_Personne=TAB.Id_Personne
				AND new_competences_relation.Evaluation IN ('L','T')
				AND new_competences_relation.Date_QCM>=TAB.Date_QCM
				AND (new_competences_relation.Date_Fin <= '0001-01-01'
				OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
		)=0
		";

if($_SESSION['FiltreFinQualif_Personne']<>""){$requeteQualifications2.=" AND TAB.Personne LIKE '%".$_SESSION['FiltreFinQualif_Personne']."%' ";}
if($_SESSION['FiltreFinQualif_Prestation']<>"0"){$requeteQualifications2.=" AND CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole)='".$_SESSION['FiltreFinQualif_Prestation']."' ";}
if($_SESSION['FiltreFinQualif_RespProjet']<>""){
	$requeteQualifications2.="
			AND CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$_SESSION['FiltreFinQualif_RespProjet'].")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
if($_SESSION['TriFinQualif_General']<>""){
	$requeteQualifications2.=" ORDER BY ".substr($_SESSION['TriFinQualif_General'],0,-1);
}

$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2);
$nbQualifs=mysqli_num_rows($resultQualifications);

$ligne=2;
if ($nbQualifs>0){
	while($row=mysqli_fetch_array($resultQualifications)){
			$couleur2="black";
			if($row['Date_Fin']<=date('Y-m-d')){
				$couleur2="red";
			}
			elseif($row['Date_Fin']<=$date_2mois){
				$couleur2="orange";
			}
			
			$Pole="";
			if($row['Pole']<>""){
				$Pole=" - ".stripslashes($row['Pole']);
			}
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
			$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Prestation']).$Pole));
			$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Qualif'])));
			$sheet->setCellValue('D'.$ligne,utf8_encode( $row['Categorie']));
			$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['Date_Fin'])));
			
			$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':E'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':E'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$ligne++;
	}
}
						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="FinValiditeQualification.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="EndOfQualifyingValidity.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/FinValiditeQualification.xlsx';

$writer->save($chemin);
readfile($chemin);
?>