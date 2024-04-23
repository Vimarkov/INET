<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

function unNombreSinonNA($leNombre){
	$nb=0;
	if($leNombre==-1){
		$nb="NA";
	}
	else{
		$nb=$leNombre;
	}
	return $nb;
}

if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

if($_SESSION['FiltreRECORD_Vision']==1){
	//Ligne En-tete
	if($_SESSION['Langue']=="FR"){
		$sheet->setCellValue('A1',utf8_encode('Mois'));
		$sheet->setCellValue('B1',utf8_encode('Prestation'));
		$sheet->setCellValue('C1',utf8_encode('UER/Dept/Filiale'));
		$sheet->setCellValue('D1',utf8_encode('Format'));
		$sheet->setCellValue('E1',utf8_encode('Q1'));
		$sheet->setCellValue('F1',utf8_encode('Q2'));
		$sheet->setCellValue('G1',utf8_encode('Q3'));
		$sheet->setCellValue('H1',utf8_encode('Q4'));
		$sheet->setCellValue('I1',utf8_encode('Q5'));
		$sheet->setCellValue('J1',utf8_encode('Q6'));
		$sheet->setCellValue('K1',utf8_encode('Moyenne'));
		$sheet->setCellValue('L1',utf8_encode('Objectif'));
		$sheet->setCellValue('M1',utf8_encode('Dernière date d\'évaluation'));
		$sheet->setCellValue('N1',utf8_encode('Date d\'envoi de la demande de satisfaction au donneur d\'ordre'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Format'));
		$sheet->setCellValue('E1',utf8_encode('Q1'));
		$sheet->setCellValue('F1',utf8_encode('Q2'));
		$sheet->setCellValue('G1',utf8_encode('Q3'));
		$sheet->setCellValue('H1',utf8_encode('Q4'));
		$sheet->setCellValue('I1',utf8_encode('Q5'));
		$sheet->setCellValue('J1',utf8_encode('Q6'));
		$sheet->setCellValue('K1',utf8_encode('Average'));
		$sheet->setCellValue('L1',utf8_encode('Objective'));
		$sheet->setCellValue('M1',utf8_encode('Last evaluation date'));
		$sheet->setCellValue('N1',utf8_encode('Date of sending of the request for satisfaction to the originator'));
	}
	$sheet->getStyle('A1:N1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	//Liste des prestations concernées + récupérer le nombre
	$req="SELECT new_competences_prestation.Id 
			FROM new_competences_prestation
			LEFT JOIN new_competences_plateforme
			ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
			WHERE new_competences_prestation.UtiliseMORIS>0 ";
	if($_SESSION['FiltreRECORD_Prestation']<>""){
		$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
	}

	$resultPrestation2=mysqli_query($bdd,$req);
	$nbPrestation2=mysqli_num_rows($resultPrestation2);

	$listePrestation2="-1";
	if ($nbPrestation2 > 0)
	{
		mysqli_data_seek($resultPrestation2,0);
		while($row=mysqli_fetch_array($resultPrestation2))
		{
			if($listePrestation2<>""){$listePrestation2.=",";}
			$listePrestation2.=$row['Id'];
		}
	}

	$moisEC=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -11 month"));

	$laDate=$date_11Mois;

	$couleur="EEEEEE";
	$ligne = 2;
		
	for($nbMois=1;$nbMois<=12;$nbMois++){
		$anneeEC=date("Y",strtotime($laDate." +0 month"));
		$moisEC=date("m",strtotime($laDate." +0 month"));
		
		$mois_6Mois=date("m",strtotime($laDate." -6 month"));
		$annee_6Mois=date("Y",strtotime($laDate." -6 month"));

		$req="SELECT
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS UER,
		DerniereDateEvaluation,DateEnvoiDemandeSatisfaction,FormatAT,
		EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
		FROM moris_moisprestation
		WHERE Annee=".$anneeEC." 
		AND Mois=".$moisEC."
		AND Suppr=0
		AND (
			(
			EvaluationQualite>0
			OR EvaluationDelais>0
			OR EvaluationCompetencePersonnel>0
			OR EvaluationAutonomie>0
			OR EvaluationAnticipation>0
			OR EvaluationCommunication>0
			)
		OR
		DerniereDateEvaluation>0
		OR
		DateEnvoiDemandeSatisfaction>0
		)
		";
		if($listePrestation2<>""){
			$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
		}
		$resultEC=mysqli_query($bdd,$req);
		$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
		
		if($nbResultaMoisPrestaEC>0){
			while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$Format="WP";
				if($LigneMoisPrestationEC['FormatAT']>0){
					$Format="AT";
				}
				
				$moyenne="";
				$total=0;
				$nbEval=0;
				if($LigneMoisPrestationEC['EvaluationQualite']>-1){
					$total+=$LigneMoisPrestationEC['EvaluationQualite'];
					$nbEval++;
				}
				if($LigneMoisPrestationEC['EvaluationDelais']>-1){
					$total+=$LigneMoisPrestationEC['EvaluationDelais'];
					$nbEval++;
				}
				if($LigneMoisPrestationEC['EvaluationCompetencePersonnel']>-1){
					$total+=$LigneMoisPrestationEC['EvaluationCompetencePersonnel'];
					$nbEval++;
				}
				if($LigneMoisPrestationEC['EvaluationAutonomie']>-1){
					$total+=$LigneMoisPrestationEC['EvaluationAutonomie'];
					$nbEval++;
				}
				if($LigneMoisPrestationEC['EvaluationAnticipation']>-1){
					$total+=$LigneMoisPrestationEC['EvaluationAnticipation'];
					$nbEval++;
				}
				if($LigneMoisPrestationEC['EvaluationCommunication']>-1){
					$total+=$LigneMoisPrestationEC['EvaluationCommunication'];
					$nbEval++;
				}
				if($nbEval>0){
					$moyenne=round($total/$nbEval,2);
				}
													
				$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
				$sheet->setCellValue('D'.$ligne,utf8_encode($Format));
				$sheet->setCellValue('E'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationQualite'])));
				$sheet->setCellValue('F'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationDelais'])));
				$sheet->setCellValue('G'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationCompetencePersonnel'])));
				$sheet->setCellValue('H'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationAutonomie'])));
				$sheet->setCellValue('I'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationAnticipation'])));
				$sheet->setCellValue('J'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationCommunication'])));
				$sheet->setCellValue('K'.$ligne,utf8_encode($moyenne));
				$sheet->setCellValue('L'.$ligne,utf8_encode(3));
				$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($LigneMoisPrestationEC['DerniereDateEvaluation'])));
				$sheet->setCellValue('N'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($LigneMoisPrestationEC['DateEnvoiDemandeSatisfaction'])));
				
				$sheet->getStyle('A'.$ligne.':N'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$ligne++;
			}
		}
		$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
	}
}
else{
	//Ligne En-tete
	if($_SESSION['Langue']=="FR"){
		$sheet->setCellValue('A1',utf8_encode('Mois'));
		$sheet->setCellValue('B1',utf8_encode('Prestation'));
		$sheet->setCellValue('C1',utf8_encode('UER/Dept/Filiale'));
		$sheet->setCellValue('D1',utf8_encode('Format'));
		$sheet->setCellValue('E1',utf8_encode('Q1'));
		$sheet->setCellValue('F1',utf8_encode('Q2'));
		$sheet->setCellValue('G1',utf8_encode('Q3'));
		$sheet->setCellValue('H1',utf8_encode('Q4'));
		$sheet->setCellValue('I1',utf8_encode('Q5'));
		$sheet->setCellValue('J1',utf8_encode('Q6'));
		$sheet->setCellValue('K1',utf8_encode('Moyenne'));
		$sheet->setCellValue('L1',utf8_encode('Objectif'));
		$sheet->setCellValue('M1',utf8_encode('Dernière date d\'évaluation'));
		$sheet->setCellValue('N1',utf8_encode('Date d\'envoi de la demande de satisfaction au donneur d\'ordre'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Format'));
		$sheet->setCellValue('E1',utf8_encode('Q1'));
		$sheet->setCellValue('F1',utf8_encode('Q2'));
		$sheet->setCellValue('G1',utf8_encode('Q3'));
		$sheet->setCellValue('H1',utf8_encode('Q4'));
		$sheet->setCellValue('I1',utf8_encode('Q5'));
		$sheet->setCellValue('J1',utf8_encode('Q6'));
		$sheet->setCellValue('K1',utf8_encode('Average'));
		$sheet->setCellValue('L1',utf8_encode('Objective'));
		$sheet->setCellValue('M1',utf8_encode('Last evaluation date'));
		$sheet->setCellValue('N1',utf8_encode('Date of sending of the request for satisfaction to the originator'));
	}
	$sheet->getStyle('A1:N1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	//Liste des plateformes sélectionnées 
	$req="SELECT DISTINCT new_competences_prestation.Id,
			new_competences_prestation.Libelle
			FROM new_competences_prestation
			LEFT JOIN new_competences_plateforme
			ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
			WHERE new_competences_prestation.UtiliseMORIS>0 ";
	if($_SESSION['FiltreRECORD_Prestation']<>""){
		$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
	}
	$req.="ORDER BY new_competences_prestation.Libelle";
	$resultPresta=mysqli_query($bdd,$req);
	$nbPresta=mysqli_num_rows($resultPresta);
	
	$anneeEC=$_SESSION['MORIS_Annee'];
	$moisEC=$_SESSION['MORIS_Mois'];
	
	$couleur="EEEEEE";
	$ligne = 2;
		
	if($nbPresta>0){
		while($rowPresta=mysqli_fetch_array($resultPresta)){

			$req="SELECT
			(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS UER,
			DerniereDateEvaluation,DateEnvoiDemandeSatisfaction,FormatAT,
			EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
			FROM moris_moisprestation
			WHERE Annee=".$anneeEC." 
			AND Mois=".$moisEC."
			AND Suppr=0
			AND (
				(
				EvaluationQualite>0
				OR EvaluationDelais>0
				OR EvaluationCompetencePersonnel>0
				OR EvaluationAutonomie>0
				OR EvaluationAnticipation>0
				OR EvaluationCommunication>0
				)
			OR
			DerniereDateEvaluation>0
			OR
			DateEnvoiDemandeSatisfaction>0
			)
			AND Id_Prestation = ".$rowPresta['Id']." ";
			$resultEC=mysqli_query($bdd,$req);
			$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
			
			if($nbResultaMoisPrestaEC>0){
				while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
					if($couleur=="FFFFFF"){$couleur="EEEEEE";}
					else{$couleur="FFFFFF";}
					
					$Format="WP";
					if($LigneMoisPrestationEC['FormatAT']>0){
						$Format="AT";
					}
					
					$moyenne="";
					$total=0;
					$nbEval=0;
					if($LigneMoisPrestationEC['EvaluationQualite']>-1){
						$total+=$LigneMoisPrestationEC['EvaluationQualite'];
						$nbEval++;
					}
					if($LigneMoisPrestationEC['EvaluationDelais']>-1){
						$total+=$LigneMoisPrestationEC['EvaluationDelais'];
						$nbEval++;
					}
					if($LigneMoisPrestationEC['EvaluationCompetencePersonnel']>-1){
						$total+=$LigneMoisPrestationEC['EvaluationCompetencePersonnel'];
						$nbEval++;
					}
					if($LigneMoisPrestationEC['EvaluationAutonomie']>-1){
						$total+=$LigneMoisPrestationEC['EvaluationAutonomie'];
						$nbEval++;
					}
					if($LigneMoisPrestationEC['EvaluationAnticipation']>-1){
						$total+=$LigneMoisPrestationEC['EvaluationAnticipation'];
						$nbEval++;
					}
					if($LigneMoisPrestationEC['EvaluationCommunication']>-1){
						$total+=$LigneMoisPrestationEC['EvaluationCommunication'];
						$nbEval++;
					}
					if($nbEval>0){
						$moyenne=round($total/$nbEval,2);
					
														
						$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
						$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
						$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
						$sheet->setCellValue('D'.$ligne,utf8_encode($Format));
						$sheet->setCellValue('E'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationQualite'])));
						$sheet->setCellValue('F'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationDelais'])));
						$sheet->setCellValue('G'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationCompetencePersonnel'])));
						$sheet->setCellValue('H'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationAutonomie'])));
						$sheet->setCellValue('I'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationAnticipation'])));
						$sheet->setCellValue('J'.$ligne,utf8_encode(unNombreSinonNA($LigneMoisPrestationEC['EvaluationCommunication'])));
						$sheet->setCellValue('K'.$ligne,utf8_encode($moyenne));
						$sheet->setCellValue('L'.$ligne,utf8_encode(3));
						$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($LigneMoisPrestationEC['DerniereDateEvaluation'])));
						$sheet->setCellValue('N'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($LigneMoisPrestationEC['DateEnvoiDemandeSatisfaction'])));
						
						$sheet->getStyle('A'.$ligne.':N'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
						$ligne++;
					}
				}
			}
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Export.xlsx';
$writer->save($chemin);
readfile($chemin);
?>