<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

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
		$sheet->setCellValue('D1',utf8_encode('Nombre de X (polyvalence)'));
		$sheet->setCellValue('E1',utf8_encode('Nombre de L (polyvalence)'));
		$sheet->setCellValue('F1',utf8_encode('Nombre de mono compétences (polyvalence)'));
		$sheet->setCellValue('G1',utf8_encode('Taux de polyvalence'));
		$sheet->setCellValue('H1',utf8_encode('Taux de qualification'));
		$sheet->setCellValue('I1',utf8_encode('Commentaire / Plan d\'action formation'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Number of X (versatility)'));
		$sheet->setCellValue('E1',utf8_encode('Number of L (versatility)'));
		$sheet->setCellValue('F1',utf8_encode('Number of mono skills (versatility)'));
		$sheet->setCellValue('G1',utf8_encode('Versatility rate'));
		$sheet->setCellValue('H1',utf8_encode('Qualification rate'));
		$sheet->setCellValue('I1',utf8_encode('Comments / Training action plan'));
	}
	$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	$sheet->getColumnDimension('I')->setWidth(60);

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
		NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,TauxQualif,CommentairePlanActionFormation
		FROM moris_moisprestation
		WHERE Annee=".$anneeEC." 
		AND Mois=".$moisEC."
		AND Suppr=0 											
		";
		if($listePrestation2<>""){
			$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
		}
		$resultEC=mysqli_query($bdd,$req);
		$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
		
		if($nbResultaMoisPrestaEC>0){
			while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
				if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbMonoCompetence']>0 || $LigneMoisPrestationEC['TauxQualif']>0){
					if($couleur=="FFFFFF"){$couleur="EEEEEE";}
					else{$couleur="FFFFFF";}
					
					$tauxPolyvalence=0;
					if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0){
						$tauxPolyvalence=round(($LigneMoisPrestationEC['NbXTableauPolyvalence']/($LigneMoisPrestationEC['NbXTableauPolyvalence']+$LigneMoisPrestationEC['NbLTableauPolyvalence']))*100,2);
					}
										
					$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
					$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
					$sheet->setCellValue('D'.$ligne,utf8_encode($LigneMoisPrestationEC['NbXTableauPolyvalence']));
					$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['NbLTableauPolyvalence']));
					$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['NbMonoCompetence']));
					$sheet->setCellValue('G'.$ligne,utf8_encode($tauxPolyvalence));
					$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['TauxQualif']));
					$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['CommentairePlanActionFormation'])));
					$sheet->getStyle('I'.$ligne)->getAlignment()->setWrapText(true);
					
					$sheet->getStyle('A'.$ligne.':I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
					$ligne++;
				}
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
		$sheet->setCellValue('D1',utf8_encode('Nombre de X (polyvalence)'));
		$sheet->setCellValue('E1',utf8_encode('Nombre de L (polyvalence)'));
		$sheet->setCellValue('F1',utf8_encode('Nombre de mono compétences (polyvalence)'));
		$sheet->setCellValue('G1',utf8_encode('Taux de polyvalence'));
		$sheet->setCellValue('H1',utf8_encode('Taux de qualification'));
		$sheet->setCellValue('I1',utf8_encode('Commentaire / Plan d\'action formation'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Number of X (versatility)'));
		$sheet->setCellValue('E1',utf8_encode('Number of L (versatility)'));
		$sheet->setCellValue('F1',utf8_encode('Number of mono skills (versatility)'));
		$sheet->setCellValue('G1',utf8_encode('Versatility rate'));
		$sheet->setCellValue('H1',utf8_encode('Qualification rate'));
		$sheet->setCellValue('I1',utf8_encode('Comments / Training action plan'));
	}
	$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	$sheet->getColumnDimension('I')->setWidth(60);

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
			NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,TauxQualif,CommentairePlanActionFormation
			FROM moris_moisprestation
			WHERE Annee=".$anneeEC." 
			AND Mois=".$moisEC."
			AND Suppr=0 											
			AND Id_Prestation = ".$rowPresta['Id']." ";
			$resultEC=mysqli_query($bdd,$req);
			$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
			
			if($nbResultaMoisPrestaEC>0){
				while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
					if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbMonoCompetence']>0 || $LigneMoisPrestationEC['TauxQualif']>0){
						if($couleur=="FFFFFF"){$couleur="EEEEEE";}
						else{$couleur="FFFFFF";}
						
						$tauxPolyvalence=0;
						if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0){
							$tauxPolyvalence=round(($LigneMoisPrestationEC['NbXTableauPolyvalence']/($LigneMoisPrestationEC['NbXTableauPolyvalence']+$LigneMoisPrestationEC['NbLTableauPolyvalence']))*100,2);
						}
											
						$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
						$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
						$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
						$sheet->setCellValue('D'.$ligne,utf8_encode($LigneMoisPrestationEC['NbXTableauPolyvalence']));
						$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['NbLTableauPolyvalence']));
						$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['NbMonoCompetence']));
						$sheet->setCellValue('G'.$ligne,utf8_encode($tauxPolyvalence));
						$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['TauxQualif']));
						$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['CommentairePlanActionFormation'])));
						$sheet->getStyle('I'.$ligne)->getAlignment()->setWrapText(true);
						
						$sheet->getStyle('A'.$ligne.':I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
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