<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Matricule'));
	$sheet->setCellValue('B1',utf8_encode('Personne'));
	$sheet->setCellValue('C1',utf8_encode('Métier'));
	$sheet->setCellValue('D1',utf8_encode('Affaire'));
	$sheet->setCellValue('E1',utf8_encode('Heures'));
	$sheet->setCellValue('F1',utf8_encode('Heures formation'));
	$sheet->setCellValue('G1',utf8_encode('Nb jour équipe'));
	$sheet->setCellValue('H1',utf8_encode('Nb CP/RTT'));
	$sheet->setCellValue('I1',utf8_encode('Nb jour maladie <= 3jours'));
	$sheet->setCellValue('J1',utf8_encode('Nb jour maladie > 3jours'));
	$sheet->setCellValue('K1',utf8_encode('Nb absence injustifiée'));
	$sheet->setCellValue('L1',utf8_encode('Nb Garde enfant'));
	$sheet->setCellValue('M1',utf8_encode('Montant Indem. Repas+ Dom/Tr'));
	$sheet->setCellValue('N1',utf8_encode('Nb Jours travaillés'));
	$sheet->setCellValue('O1',utf8_encode('Rythme'));
	$sheet->setCellValue('P1',utf8_encode("Unité d'exploitation : "));
	$sheet->setCellValue('P2',utf8_encode('Mois : '));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Number'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Job'));
	$sheet->setCellValue('D1',utf8_encode('Site'));
	$sheet->setCellValue('E1',utf8_encode('Hours'));
	$sheet->setCellValue('F1',utf8_encode('Training hours'));
	$sheet->setCellValue('G1',utf8_encode('Number of team days'));
	$sheet->setCellValue('H1',utf8_encode('Nb CP/RTT'));
	$sheet->setCellValue('I1',utf8_encode('Number of sick days <= 3 days'));
	$sheet->setCellValue('J1',utf8_encode('Number of sick days > 3 days'));
	$sheet->setCellValue('K1',utf8_encode('Absence'));
	$sheet->setCellValue('L1',utf8_encode('Nb Child care'));
	$sheet->setCellValue('M1',utf8_encode('Montant Indem. Repas+ Dom/Tr'));
	$sheet->setCellValue('N1',utf8_encode('Nb Jours travaillés'));
	$sheet->setCellValue('O1',utf8_encode('Pace'));
	$sheet->setCellValue('P1',utf8_encode('Operating unit : '));
	$sheet->setCellValue('P2',utf8_encode('Month : '));
}
$sheet->getStyle('A1:P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('P2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('P2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$annee=$_SESSION['FiltreRHRepartitionAAA_Annee'];
$mois=$_SESSION['FiltreRHRepartitionAAA_Mois'];
$PlateformeSelect=$_SESSION['FiltreRHRepartitionAAA_Plateforme'];

$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$PlateformeSelect;
$result2=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result2);


if($nbResulta>0){
	$row2=mysqli_fetch_array($result2);
	$sheet->setCellValue('Q1',utf8_encode($row2['Libelle']));
}
$sheet->setCellValue('Q2',utf8_encode($mois."/".$annee));
	

$dateDebut=date($annee."-".$mois."-01");;
$dateFin = $dateDebut;

$tabDateFin = explode('-', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y-m-d", $timestampFin);

$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
	CONCAT(Nom,' ',Prenom) AS Personne,
	(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
	FROM rh_personne_contrat
	WHERE rh_personne_contrat.Suppr=0
	AND rh_personne_contrat.DateDebut<='".$dateFin."'
	AND (rh_personne_contrat.DateFin>='".$dateDebut."' OR rh_personne_contrat.DateFin<='0001-01-01')
	AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
	AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier,
	new_rh_etatcivil.MatriculeAAA
FROM new_rh_etatcivil
LEFT JOIN rh_personne_mouvement 
ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
AND rh_personne_mouvement.EtatValidation=1 
AND rh_personne_mouvement.Suppr=0
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";		
$requeteOrder="ORDER BY Personne ASC";

$result=mysqli_query($bdd,$req.$requeteOrder);
$nbResulta=mysqli_num_rows($result);
		
if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result))
	{
		$req = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
		FROM rh_personne_mouvement 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.Id_Personne=".$row['Id']."
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";		
		$requeteOrder="ORDER BY Prestation ASC";

		$resultPresta=mysqli_query($bdd,$req.$requeteOrder);
		$nbResultaPresta=mysqli_num_rows($resultPresta);
		if($nbResultaPresta>0){
			while($rowPresta=mysqli_fetch_array($resultPresta))
			{
				$NbHeures=0;
				$NbCPRTT=0;
				$NbGardeEnfant=0;
				$NbMalInf3=0;
				$NbMalSup3=0;
				$NbAbs=0;
				$NbJourEquipe=0;
				$NbJourTravaille=0;
				$MontantIndem=0;
				$tabRythme=array();
				$nbHeureFormationVac=date('H:i',strtotime($dateDebut.' 00:00:00'));
				for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
					$tabInfo=InfoCeJourSurCettePresta($row['Id'],$laDate,$rowPresta['Id_Prestation']);
					$NbCPRTT=$NbCPRTT+$tabInfo[0];
					$NbGardeEnfant=$NbGardeEnfant+$tabInfo[1];
					$NbMalInf3=$NbMalInf3+$tabInfo[2];
					$NbMalSup3=$NbMalSup3+$tabInfo[3];
					$NbAbs=$NbAbs+$tabInfo[4];
					$NbJourTravaille=$NbJourTravaille+$tabInfo[5];
					$NbJourEquipe=$NbJourEquipe+$tabInfo[6];
					$leRtyme=$tabInfo[7];
					if($leRtyme<>""){
						$tabRythme[]=$leRtyme;
					}
					$MontantIndem=$MontantIndem+$tabInfo[8];
					$NbHeures=$NbHeures+$tabInfo[9];
					$nbHeure=$tabInfo[10];
					$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$nbHeure)." minute"));
				}

				$tab=array_unique($tabRythme);
				$Rythme="";
				foreach($tab as $val){
					if($Rythme<>""){$Rythme.="|";}
					$Rythme.=$val;
				}
				
			
				$lesminutes=substr(date('i',strtotime($nbHeureFormationVac." + 0 hour"))/0.6,0,2);
				if(substr($lesminutes,1,1)=="."){
					$lesminutes="0".substr($lesminutes,0,1);
				}
				$nbHeureFormVac=intval(date('H',strtotime($nbHeureFormationVac." + 0 hour"))).".".$lesminutes;
			
				$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['MatriculeAAA'])));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Personne'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Metier'])));
				$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($rowPresta['Prestation'])));
				$sheet->setCellValue('E'.$ligne,utf8_encode($NbHeures));
				$sheet->setCellValue('F'.$ligne,utf8_encode($nbHeureFormVac));
				$sheet->setCellValue('G'.$ligne,utf8_encode($NbJourEquipe));
				$sheet->setCellValue('H'.$ligne,utf8_encode($NbCPRTT));
				$sheet->setCellValue('I'.$ligne,utf8_encode($NbMalInf3));
				$sheet->setCellValue('J'.$ligne,utf8_encode($NbMalSup3));
				$sheet->setCellValue('K'.$ligne,utf8_encode($NbAbs));
				$sheet->setCellValue('L'.$ligne,utf8_encode($NbGardeEnfant));
				$sheet->setCellValue('M'.$ligne,utf8_encode($MontantIndem));
				$sheet->setCellValue('N'.$ligne,utf8_encode($NbJourTravaille));
				$sheet->setCellValue('O'.$ligne,utf8_encode($Rythme));

				$ligne++;
			}
		}
	}
}



//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Repartition AAA.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Repartition AAA.xlsx';
$writer->save($chemin);
readfile($chemin);
?>