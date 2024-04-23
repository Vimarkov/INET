<?php
session_start();
require("../ConnexioniSansBody.php");
require_once("../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Autorisations"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation"));
	$sheet->setCellValue('C1',utf8_encode("Autorisation de conduite : Moyens - Catégories (Fin de validité))"));
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
		$sheet->setCellValue('D1',utf8_encode("Etat"));
	}
}
else{
	$sheet->setTitle(utf8_encode("Authorizations"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Activity"));
	$sheet->setCellValue('C1',utf8_encode("Driving Authorization : Means - Categories (End of validity)"));
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
		$sheet->setCellValue('D1',utf8_encode("State"));
	}

}

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(45);
$sheet->getColumnDimension('C')->setWidth(60);
$sheet->getColumnDimension('D')->setWidth(20);
if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
	$sheet->getStyle('A1:D1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A1:D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:D1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:D1')->getFont()->setBold(true);
	$sheet->getStyle('A1:D1')->getFont()->getColor()->setRGB('1f49a6');
}
else{
	$sheet->getStyle('A1:C1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:C1')->getFont()->setBold(true);
	$sheet->getStyle('A1:C1')->getFont()->getColor()->setRGB('1f49a6');

}

//PERSONNES AYANT UNE AUTORISATION DE CONDUITE
$req="SELECT DISTINCT new_competences_relation.Id_Personne, 
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne, 
(SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS DateEditionAutorisationTravail ";
$req2="FROM new_competences_relation 
LEFT JOIN new_competences_qualification
ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
WHERE (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
AND Date_Debut>'0001-01-01'
AND Evaluation NOT IN ('B','')
AND new_competences_relation.Suppr=0 
AND (
	(SELECT COUNT(new_competences_qualification_moyen.Id_Moyen_Categorie)
		FROM new_competences_qualification_moyen 
		WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
		AND Id_Moyen_Categorie NOT IN (1,2)
	)>0 
	OR (
		(SELECT COUNT(new_competences_qualification_moyen.Id_Moyen_Categorie)
			FROM new_competences_qualification_moyen 
			WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
			AND Id_Moyen_Categorie IN (1,2)
		)>0
	
	AND 
	(SELECT COUNT(Tab2.Id)
	FROM new_competences_relation AS Tab2
	WHERE Tab2.Suppr=0
	AND Tab2.Evaluation NOT IN ('B','')
	AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
	AND Tab2.Date_Debut>'0001-01-01' 
	AND Tab2.Id_Personne=new_competences_relation.Id_Personne
	AND Tab2.Id_Qualification_Parrainage=75)>0
	
	AND 
	(SELECT COUNT(Tab2.Id)
	FROM new_competences_relation AS Tab2
	LEFT JOIN new_competences_qualification AS Tab3
	ON Tab2.Id_Qualification_Parrainage=Tab3.Id
	WHERE Tab2.Suppr=0
	AND Tab2.Evaluation NOT IN ('B','')
	AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
	AND Tab2.Date_Debut>'0001-01-01' 
	AND Tab2.Id_Personne=new_competences_relation.Id_Personne
	AND Tab2.Id_Qualification_Parrainage=12)>0
	
	AND 
	(SELECT COUNT(Tab2.Id)
	FROM new_competences_relation AS Tab2
	LEFT JOIN new_competences_qualification AS Tab3
	ON Tab2.Id_Qualification_Parrainage=Tab3.Id
	WHERE Tab2.Suppr=0
	AND Tab2.Evaluation NOT IN ('B','')
	AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
	AND Tab2.Date_Debut>'0001-01-01' 
	AND Tab2.Id_Personne=new_competences_relation.Id_Personne
	AND Tab2.Id_Qualification_Parrainage=13)>0
	
	AND 
	(SELECT COUNT(Tab2.Id)
	FROM new_competences_relation AS Tab2
	LEFT JOIN new_competences_qualification AS Tab3
	ON Tab2.Id_Qualification_Parrainage=Tab3.Id
	WHERE Tab2.Suppr=0
	AND Tab2.Evaluation NOT IN ('B','')
	AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
	AND Tab2.Date_Debut>'0001-01-01' 
	AND Tab2.Id_Personne=new_competences_relation.Id_Personne
	AND Tab2.Id_Qualification_Parrainage=133)>0)
)
";
if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
$req2.="
		AND (
		SELECT COUNT(Id_Personne)
		FROM new_competences_personne_plateforme
		WHERE new_competences_personne_plateforme.Id_Personne=new_competences_relation.Id_Personne 
		AND Id_Plateforme IN(
		SELECT Id_Plateforme 
		FROM new_competences_personne_poste_plateforme 
		WHERE new_competences_personne_poste_plateforme.Id_Personne=".$IdPersonneConnectee."
		AND new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableRH.") 
		))>0 ";
}
else{
	$req2.="
		AND (
		SELECT COUNT(Id_Personne) 
		FROM new_competences_personne_prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne 
		AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
		AND CONCAT(Id_Prestation,'_',Id_Pole) IN (
		SELECT CONCAT(Id_Prestation,'_',Id_Pole)
		FROM new_competences_personne_poste_prestation 
		LEFT JOIN new_competences_prestation
		ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
		WHERE new_competences_personne_poste_prestation.Id_Personne=".$IdPersonneConnectee."
		AND new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
		))>0 ";
}
$req2.="AND (SELECT COUNT(Id)
	FROM new_competences_qualification_moyen
	WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
	AND Suppr=0)>0";
if($_SESSION['FiltreAT_Moyen']<>"" && $_SESSION['FiltreAT_Moyen']<>"0"){
	$req2.=" AND (SELECT COUNT(Id)
	FROM new_competences_qualification_moyen
	WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
	AND (SELECT Id_Moyen FROM new_competences_moyen_categorie WHERE Id=Id_Moyen_Categorie)=".$_SESSION['FiltreAT_Moyen']."
	AND Suppr=0)>0 ";
}
if($_SESSION['FiltreAT_Prestation']<>""){
	$req2.=" AND (
			SELECT COUNT(new_competences_prestation.Libelle) 
			FROM new_competences_personne_prestation 
			LEFT JOIN new_competences_prestation 
			ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne 
			AND Date_Debut<='".date('Y-m-d')."' 
			AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
			AND new_competences_prestation.Libelle LIKE '%".$_SESSION['FiltreAT_Prestation']."%'
		)>0 ";
}
if($_SESSION['FiltreAT_RespProjet']<>""){
	$req2.=" AND (
				SELECT COUNT(new_competences_prestation.Id) 
				FROM new_competences_personne_prestation 
				LEFT JOIN new_competences_prestation 
				ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
				WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne 
				AND Date_Debut<='".date('Y-m-d')."' 
				AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
				AND CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$_SESSION['FiltreAT_RespProjet'].")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
			)>0
				";
}

if($_SESSION['FiltreAT_Personne']<>""){
	$req2.=" AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) LIKE '%".$_SESSION['FiltreAT_Personne']."%' ";
}
$req3=" ORDER BY Personne ASC";

$resultPersonne=mysqli_query($bdd,$req.$req2.$req3);
$nbPersonne=mysqli_num_rows($resultPersonne);

if($nbPersonne>0){
$ligne=2;
while($row=mysqli_fetch_array($resultPersonne)){
	$bReedition=0;
	if($row['DateEditionAutorisationTravail']<='0001-01-01'){$bReedition=1;}
	//Liste des prestations de la personne
	$Prestations="";
	$reqPresta="SELECT DISTINCT Id_Prestation,
	(SELECT Libelle FROM new_competences_prestation 
	WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
	(SELECT Libelle FROM new_competences_pole 
	WHERE new_competences_pole.Id=Id_Pole) AS Pole
	FROM new_competences_personne_prestation
	WHERE Id_Personne=".$row['Id_Personne']." 
	AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') ";
	$resultPresta=mysqli_query($bdd,$reqPresta);
	$nbPresta=mysqli_num_rows($resultPresta);
	if($nbPresta>0){
		while($rowPresta=mysqli_fetch_array($resultPresta)){
			$Pole="";
			if($rowPresta['Pole']<>""){$Pole=" - ".stripslashes($rowPresta['Pole']);}
			$Prestations.="".stripslashes($rowPresta['Prestation']).$Pole." \n";
		}
		$Prestations=substr($Prestations,0,-2);
	}
	
	//Liste des autorisations de conduite
	$AT="";
	$reqAT="
			SELECT *
			FROM (
			SELECT
                DISTINCT new_competences_relation.Id_Qualification_Parrainage,
                new_competences_relation.Date_Fin,
	            new_competences_relation.DateEditionAutorisationTravail,
	            (
                    SELECT
                        Libelle
                    FROM
                        new_competences_moyen_categorie
	                WHERE
                        new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie
                ) AS Categorie,
	            (
                    SELECT
		                (SELECT Libelle FROM new_competences_moyen WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen)
	                FROM
                        new_competences_moyen_categorie
	                WHERE
                        new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie
                ) AS Moyen,(@row_number:=@row_number + 1) AS rnk
            FROM
                new_competences_relation
            LEFT JOIN new_competences_qualification_moyen
	             ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification_moyen.Id_Qualification
	        LEFT JOIN new_competences_qualification
	             ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
	        WHERE
                new_competences_qualification_moyen.Suppr=0
                AND new_competences_relation.Suppr=0
	            AND new_competences_qualification_moyen.Suppr=0
				AND new_competences_relation.Evaluation NOT IN ('B','')
				AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
				AND Date_Debut>'0001-01-01'
				AND (
						(SELECT COUNT(new_competences_qualification_moyen.Id_Moyen_Categorie)
							FROM new_competences_qualification_moyen 
							WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
							AND Id_Moyen_Categorie NOT IN (1,2)
						)>0 
					OR (
						(SELECT COUNT(new_competences_qualification_moyen.Id_Moyen_Categorie)
							FROM new_competences_qualification_moyen 
							WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
							AND Id_Moyen_Categorie IN (1,2)
						)>0
					
						AND 
						(
							((SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=".$row['Id_Personne']."
							AND Tab2.Id_Qualification_Parrainage=75)>0
							
							AND 
							(SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							LEFT JOIN new_competences_qualification AS Tab3
							ON Tab2.Id_Qualification_Parrainage=Tab3.Id
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=".$row['Id_Personne']."
							AND Tab2.Id_Qualification_Parrainage=12)>0
							
							AND 
							(SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							LEFT JOIN new_competences_qualification AS Tab3
							ON Tab2.Id_Qualification_Parrainage=Tab3.Id
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=".$row['Id_Personne']."
							AND Tab2.Id_Qualification_Parrainage=13)>0
							
							AND 
							(SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							LEFT JOIN new_competences_qualification AS Tab3
							ON Tab2.Id_Qualification_Parrainage=Tab3.Id
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=".$row['Id_Personne']."
							AND Tab2.Id_Qualification_Parrainage=133)>0)
							
							OR 
							(
								((SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=".$row['Id_Personne']."
								AND Tab2.Id_Qualification_Parrainage=75)=0
								
								AND 
								(SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								LEFT JOIN new_competences_qualification AS Tab3
								ON Tab2.Id_Qualification_Parrainage=Tab3.Id
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=".$row['Id_Personne']."
								AND Tab2.Id_Qualification_Parrainage=12)=0
								
								AND 
								(SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								LEFT JOIN new_competences_qualification AS Tab3
								ON Tab2.Id_Qualification_Parrainage=Tab3.Id
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=".$row['Id_Personne']."
								AND Tab2.Id_Qualification_Parrainage=13)=0
								
								AND 
								(SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								LEFT JOIN new_competences_qualification AS Tab3
								ON Tab2.Id_Qualification_Parrainage=Tab3.Id
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=".$row['Id_Personne']."
								AND Tab2.Id_Qualification_Parrainage=133)=0)
							)
							OR 
							
							new_competences_relation.Id_Qualification_Parrainage IN (1606,1607,2130,1683,2490,2145)
						)
					)
				)
				AND new_competences_relation.Id_Personne=".$row['Id_Personne']." 
			ORDER BY Moyen, Categorie, Date_Fin DESC
			) AS TAB 
			GROUP BY Moyen,Categorie
			";
	$resultAT=mysqli_query($bdd,$reqAT);
	$nbAT=mysqli_num_rows($resultAT);
	if($nbAT>0){
		while($rowAT=mysqli_fetch_array($resultAT)){
			if(AfficheDateJJ_MM_AAAA($rowAT['Date_Fin'])<>""){$dateFin=AfficheDateJJ_MM_AAAA($rowAT['Date_Fin']);}
			else{
				if($LangueAffichage=="FR"){$dateFin="sans limite";}
				else{$dateFin="illimitable";}
			}
			$AT.="".stripslashes($rowAT['Moyen'])." - ".stripslashes($rowAT['Categorie'])." (".$dateFin.") \n";
			if($bReedition==0){
				if($rowAT['DateEditionAutorisationTravail']<='0001-01-01'){
					$bReedition=1;
				}
				elseif($rowAT['DateEditionAutorisationTravail']<$row['DateEditionAutorisationTravail']){
					$bReedition=1;
				}
			}
		}
	}
	$etat="";
	if($bReedition==1){
		if($LangueAffichage=="FR"){
			$etat="A éditer";
		}
		else{
			$etat="To edit";
		}
	}
	$btrouve=1;
	if($_GET['Prestation']<>""){
		if(stripos($Prestations,$_GET['Prestation'])===false){$btrouve=0;}
	}
	if($_GET['Personne']<>""){
		if(stripos($row['Personne'],$_GET['Personne'])===false){$btrouve=0;}
	}
	if($_GET['Etat']<>""){
		if($_GET['Etat']=="A jour" && $etat<>""){$btrouve=0;}
		elseif($_GET['Etat']=="A éditer" && $etat==""){$btrouve=0;}
	}
	if($btrouve==1){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($Prestations));
		$sheet->setCellValue('C'.$ligne,utf8_encode($AT));
		if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
			$sheet->setCellValue('D'.$ligne,utf8_encode($etat));
		}
		$sheet->getStyle('B'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);
		if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
			$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':D'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':D'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		}
		else{
			$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':C'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		}
		$ligne++;
	}
}	//Fin boucle
}
						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="AutorisationTravail.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="WorkAuthorization.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/AutorisationTravail.xlsx';

$writer->save($chemin);
readfile($chemin);
?>