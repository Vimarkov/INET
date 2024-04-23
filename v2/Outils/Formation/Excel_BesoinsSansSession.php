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

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("NbSessions"));
	
	$sheet->setCellValue('A1',utf8_encode("Formation"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Nombre"));
	$sheet->setCellValue('D1',utf8_encode("Date dernière session"));
}
else{
	$sheet->setTitle(utf8_encode("NoSessions"));
	$sheet->setCellValue('A1',utf8_encode("Training"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Number"));
	$sheet->setCellValue('D1',utf8_encode("Date last session"));
}

$sheet->getColumnDimension('A')->setWidth(100);

$requetePersonnes="
	SELECT
		Id_Personne
	FROM
		new_competences_personne_prestation
	WHERE
		Date_Fin>='".date('Y-m-d')."' 
		AND Id_Prestation IN (SELECT Id_Prestation FROM new_competences_prestation WHERE Id_Plateforme=".$_SESSION['FiltreBesoinsSansSession_Plateforme']." )
		";
$resultPersResp=mysqli_query($bdd,$requetePersonnes);
$nbPersResp=mysqli_num_rows($resultPersResp);
$listeRespPers=0;
if($nbPersResp>0)
{
	$listeRespPers="";
	while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPers.=$rowPersResp['Id_Personne'].",";}
	$listeRespPers=substr($listeRespPers,0,-1);
}

$requete="	SELECT 
	form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
	form_besoin.Id_Formation,
	form_formation.Reference AS REFERENCE_FORMATION,
	(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Organisme,
	 IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,1,0) AS Recyclage,
	(SELECT IF(form_besoin.Motif='Renouvellement',
			IF(LibelleRecyclage='',Libelle,LibelleRecyclage),
			Libelle
			)
		FROM form_formation_langue_infos
		WHERE form_formation_langue_infos.Id_Formation=form_besoin.Id_Formation
		AND form_formation_langue_infos.Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
			AND Id_Formation=form_besoin.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0) AS Libelle,
	COUNT(form_besoin.Id) AS NombreFormation
FROM
	form_besoin,
	form_typeformation,
	form_formation,
	new_rh_etatcivil,
	new_competences_prestation
WHERE
	form_besoin.Id_Formation=form_formation.Id
	AND form_formation.Id_TypeFormation=form_typeformation.Id
	AND form_besoin.Id_Prestation=new_competences_prestation.Id
	AND form_besoin.Id_Personne=new_rh_etatcivil.Id
	AND form_besoin.Traite=0
	AND form_besoin.Suppr=0
	AND form_besoin.Valide=1
	AND form_besoin.Id_Personne IN
	(".$listeRespPers.")
	GROUP BY Libelle, Organisme
	ORDER BY Libelle, Organisme
	 ";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$organisme="";
		if($row['Organisme']<>""){
			$organisme=" ".$row['Organisme'];
		}
		
		//Date dernière session de formation ou formation similaire 
		$reqF="
		SELECT
			form_session_date.DateSession
		FROM
			form_session_date
		LEFT JOIN form_session
			ON form_session_date.Id_Session=form_session.Id
		WHERE
			form_session_date.Suppr=0
			
			AND form_session.Suppr=0
			AND form_session.Annule=0
			AND form_session.Diffusion_Creneau=1
			AND (
				(SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=0
				OR
				((SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=1
				AND form_session.Recyclage=".$row['Recyclage']."
				)
				)
			AND (form_session.Id_Formation=".$row['Id_Formation']."
					OR form_session.Id_Formation IN  (SELECT Id_Formation 
				FROM form_formationequivalente_formationplateforme 
				WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
				FROM form_formationequivalente_formationplateforme 
				WHERE Id_Formation=".$row['Id_Formation']."))
			)
		ORDER BY
			form_session_date.DateSession DESC ";
		$resultSession=mysqli_query($bdd,$reqF);
		$nbSession=mysqli_num_rows($resultSession);
		$dateSession="";
		$dateSession2=date('0001-01-01');
		if($nbSession>0){
			$rowSession=mysqli_fetch_array($resultSession);
			$dateSession=AfficheDateJJ_MM_AAAA($rowSession['DateSession']);
			$dateSession2=$rowSession['DateSession'];
		}
		
		
		if($_SESSION['FiltreBesoinsSansSession_Periode']=="1 mois"){
			$periode=date('Y-m-d', strtotime(date('Y-m-d')." -1 month"));
		}
		elseif($_SESSION['FiltreBesoinsSansSession_Periode']=="3 mois"){
			$periode=date('Y-m-d', strtotime(date('Y-m-d')." -3 month"));
		}
		elseif($_SESSION['FiltreBesoinsSansSession_Periode']=="6 mois"){
			$periode=date('Y-m-d', strtotime(date('Y-m-d')." -6 month"));
		}
		elseif($_SESSION['FiltreBesoinsSansSession_Periode']=="1 an"){
			$periode=date('Y-m-d', strtotime(date('Y-m-d')." -1 year"));
		}
		
		$ok=0;
		if($dateSession2<$periode){$ok=1;}
				
		if($ok==1){
			$ligne++;
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['Libelle'].$organisme));
			$sheet->setCellValue('B'.$ligne,utf8_encode($row['LIBELLE_TYPEFORMATION']));
			$sheet->setCellValue('C'.$ligne,utf8_encode($row['NombreFormation']));
			$sheet->setCellValue('D'.$ligne,utf8_encode($dateSession));
		
			$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':D'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		}
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>