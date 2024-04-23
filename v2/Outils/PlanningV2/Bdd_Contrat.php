<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require_once '../Fonctions.php';
require_once("Fonctions_Planning.php"); 

$EnAttente="#ffbf03";
$Automatique="#3d9538";
$Validee="#6beb47";
$Refusee="#ff5353";
$Gris="#dddddd";
$AbsenceInjustifies="#ff0303";

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array('memoryCacheSize ' => '2048MB', 'cacheTime' => 12000);
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Nouveau fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('TemplateBDD_Contrat.xlsx');
$sheetContrat1 = $excel->getSheetByName('Contrat S1');
$sheetContrat2 = $excel->getSheetByName('Contrat S2');
$sheetMensuel1 = $excel->getSheetByName('Mensuel S1');
$sheetMensuel2 = $excel->getSheetByName('Mensuel S2');
$sheetJT1 = $excel->getSheetByName('JT S1');
$sheetJT2 = $excel->getSheetByName('JT S2');
$sheetJC1 = $excel->getSheetByName('JC S1');
$sheetJC2 = $excel->getSheetByName('JC S2');
$sheetOUT1 = $excel->getSheetByName('OUT S1');
$sheetOUT2 = $excel->getSheetByName('OUT S2');
$sheetCoeff1 = $excel->getSheetByName('Coeff S1');
$sheetCoeff2 = $excel->getSheetByName('Coeff S2');
$sheetPrime1 = $excel->getSheetByName('Prime S1');
$sheetPrime2 = $excel->getSheetByName('Prime S2');
$sheetListe = $excel->getSheetByName('Liste');

//Liste des personnes en contrat sur l'année 2020
$requete="
	SELECT *
	FROM
	(
		SELECT *
		FROM 
			(SELECT Id,Id_Personne,
			(SELECT Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Nom,
			(SELECT Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Prenom,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
			(SELECT MatriculeTLS FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS MatriculeTLS,(@row_number:=@row_number + 1) AS rnk
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='2020-12-31'
			AND (DateFin>='2020-01-01' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
		GROUP BY Id_Personne
	) AS table_contrat2
	WHERE Personne<>'' 
	ORDER BY Personne
	";
$result=mysqli_query($bdd,$requete);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	$i=5;
	while($row=mysqli_fetch_array($result))
	{
		$Matricule=$row['MatriculeTLS'];
		if($Matricule==""){$Matricule=$row['Nom']."_".$row['Id_Personne'];}
		
		$sheetContrat1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetContrat1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetContrat1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetContrat2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetContrat2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetContrat2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetMensuel1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetMensuel1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetMensuel1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetMensuel2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetMensuel2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetMensuel2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetJT1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetJT1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetJT1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetJT2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetJT2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetJT2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetJC1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetJC1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetJC1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetJC2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetJC2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetJC2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetOUT1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetOUT1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetOUT1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetOUT2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetOUT2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetOUT2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetPrime1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetPrime1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetPrime1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetPrime2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetPrime2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetPrime2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetCoeff1->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetCoeff1->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetCoeff1->setCellValue('C'.$i,utf8_encode($Matricule));
		$sheetCoeff2->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetCoeff2->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetCoeff2->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$sheetListe->setCellValue('A'.$i,utf8_encode($row['Nom']));
		$sheetListe->setCellValue('B'.$i,utf8_encode($row['Prenom']));
		$sheetListe->setCellValue('C'.$i,utf8_encode($Matricule));
		
		$Id_ContratEC=IdContratEC($row['Id_Personne']);
		$Id_ODMEC=IdODMEC($row['Id_Personne']);
		if($Id_ContratEC>0){
			$req="SELECT 
				(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
				(SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier,
				(SELECT Code FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS CodeContrat,
				(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
				Coeff,DateDebut,DateFin,SalaireBrut,TauxHoraire,TypeCoeff,
				(SELECT Code FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS CodeAgence,
				TypeDocument,new_rh_etatcivil.Date_Naissance,MatriculeDSK,MatriculeAAA,DateDebut1erContratAAA
				FROM rh_personne_contrat
				LEFT JOIN new_rh_etatcivil
				ON rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
				WHERE rh_personne_contrat.Id=".$Id_ContratEC;
			$resultContratEC=mysqli_query($bdd,$req);
			$nbContratEC=mysqli_num_rows($resultContratEC);

			if($nbContratEC>0){
				$rowContratEC=mysqli_fetch_array($resultContratEC);
				$sheetListe->setCellValue("D".$i,utf8_encode($rowContratEC['Metier']));
				$sheetListe->setCellValue("E".$i,utf8_encode($rowContratEC['CodeMetier']));
				if($rowContratEC['EstInterim']==1){
					$sheetListe->setCellValue("F".$i,utf8_encode($rowContratEC['CodeAgence']));
				}
				else{
					$sheetListe->setCellValue("F".$i,utf8_encode($rowContratEC['CodeContrat']));
				}
				$sheetListe->setCellValue("G".$i,utf8_encode($rowContratEC['Coeff']));
				$sheetListe->setCellValue("H".$i,utf8_encode(AfficheDateJJ_MM_AAAA($rowContratEC['DateDebut'])));
				$sheetListe->setCellValue("I".$i,utf8_encode(AfficheDateJJ_MM_AAAA($rowContratEC['DateFin'])));
				if($rowContratEC['TypeDocument']=="Nouveau"){
					$sheetListe->setCellValue("J".$i,utf8_encode("N"));
				}
				else{
					$sheetListe->setCellValue("J".$i,utf8_encode("P"));
				}
				if($rowContratEC['EstInterim']==1){
					$sheetListe->setCellValue("K".$i,utf8_encode($rowContratEC['TauxHoraire']*151.67));
					$sheetListe->setCellValue("L".$i,utf8_encode($rowContratEC['TauxHoraire']));
				}
				else{
					$sheetListe->setCellValue("K".$i,utf8_encode($rowContratEC['SalaireBrut']));
					$sheetListe->setCellValue("L".$i,utf8_encode($rowContratEC['SalaireBrut']/151.67));
				}
				$sheetListe->setCellValue("R".$i,utf8_encode(AfficheDateJJ_MM_AAAA($rowContratEC['DateDebut1erContratAAA'])));
				$sheetListe->setCellValue("Y".$i,utf8_encode(AfficheDateJJ_MM_AAAA($rowContratEC['Date_Naissance'])));
				$sheetListe->setCellValue("AA".$i,utf8_encode($rowContratEC['TypeCoeff']));
				$sheetListe->setCellValue("AB".$i,utf8_encode($rowContratEC['MatriculeAAA']));
			}
				
		}
		
		if($Id_ODMEC>0){
			$req="SELECT 
				MontantIPD,MontantIGD,PrimeResponsabilite,IndemniteOutillage
				FROM rh_personne_contrat
				LEFT JOIN new_rh_etatcivil
				ON rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
				WHERE rh_personne_contrat.Id=".$Id_ODMEC;
			$resultContratEC=mysqli_query($bdd,$req);
			$nbContratEC=mysqli_num_rows($resultContratEC);

			if($nbContratEC>0){
				$rowContratEC=mysqli_fetch_array($resultContratEC);
				$sheetListe->setCellValue("M".$i,utf8_encode($rowContratEC['MontantIPD']));
				$sheetListe->setCellValue("N".$i,utf8_encode($rowContratEC['MontantIGD']));
				$sheetListe->setCellValue("O".$i,utf8_encode($rowContratEC['IndemniteOutillage']));
				$sheetListe->setCellValue("P".$i,utf8_encode($rowContratEC['PrimeResponsabilite']));
			}
				
		}

		//Liste des contrats de la personne
		$req="
			SELECT Id,
				(SELECT Code FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS CodeContrat,
				(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
				Coeff,DateDebut,DateFin,SalaireBrut,TauxHoraire*151.57 AS SalaireInt,
				(SELECT Code FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS CodeAgence
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='2020-12-31'
				AND (DateFin>='2020-01-01' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('Nouveau','Avenant')
				AND Id_Personne=".$row['Id_Personne']."
				ORDER BY DateDebut
			";
		$resultContrat=mysqli_query($bdd,$req);
		$nbContrat=mysqli_num_rows($resultContrat);
		
		$debutS1="";
		$debutS2="";
		$finS1="";
		$finS2="";
		if($nbContrat>0){
			while($rowContrat=mysqli_fetch_array($resultContrat))
			{
				//DEBUT
				if($rowContrat['DateDebut']<'2020-07-01'){
					//Début Semestre 1
					if($rowContrat['DateDebut']<'2020-01-01'){
						$debutS1="AI";
					}
					else{
						$col="AI";
						for($laDate='2020-01-01';$laDate<'2020-07-01';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($laDate==$rowContrat['DateDebut']){
								$debutS1=$col;
								break;
							}
							$col++;
						}
					}
				}
				else{
					//Début Semestre 2
					$col="AH";
					for($laDate='2020-07-01';$laDate<='2020-12-31';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						if($laDate==$rowContrat['DateDebut']){
							$debutS2=$col;
							break;
						}
						$col++;
					}
				}
				
				//FIN
				if($rowContrat['DateFin']>='2020-07-01' || $rowContrat['DateFin']<='0001-01-01'){
					//Fin Semestre 2
					if($rowContrat['DateFin']>'2020-12-31' || $rowContrat['DateFin']<='0001-01-01'){
						$finS2="HI";
					}
					else{
						$col="AH";
						for($laDate='2020-07-01';$laDate<='2020-12-31';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($laDate==$rowContrat['DateFin']){
								$finS2=$col;
								break;
							}
							$col++;
						}
					}
				}
				else{
					//Fin Semestre 1
					$col="AI";
					for($laDate='2020-01-01';$laDate<'2020-07-01';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						if($laDate==$rowContrat['DateFin']){
							$finS1=$col;
							break;
						}
						$col++;
					}
				}
				if($debutS1<>"" && $finS2<>""){
					$finS1="HG";
					$debutS2="AH";
				}
				
				//Compléter Semestre 1
				if($debutS1<>"" && $finS1<>"" && $debutS1<=$finS1){
					for($leDebut=$debutS1;$leDebut<=$finS1;$leDebut++){
						//Contrat
						if($rowContrat['EstInterim']==1){
							$sheetContrat1->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeAgence']));
							$sheetMensuel1->setCellValue($leDebut.$i,utf8_encode($rowContrat['SalaireInt']));
						}
						else{
							$sheetContrat1->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeContrat']));
							$sheetMensuel1->setCellValue($leDebut.$i,utf8_encode($rowContrat['SalaireBrut']));
						}
						$sheetCoeff1->setCellValue($leDebut.$i,utf8_encode($rowContrat['Coeff']));
					}
				}
				
				//Compléter Semestre 2
				if($debutS2<>"" && $finS2<>"" && $debutS2<=$finS2){
					for($leDebut=$debutS2;$leDebut<=$finS2;$leDebut++){
						//Contrat
						if($rowContrat['EstInterim']==1){
							$sheetContrat2->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeAgence']));
							$sheetMensuel2->setCellValue($leDebut.$i,utf8_encode($rowContrat['SalaireInt']));
						}
						else{
							$sheetContrat2->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeContrat']));
							$sheetMensuel2->setCellValue($leDebut.$i,utf8_encode($rowContrat['SalaireBrut']));
						}
						$sheetCoeff2->setCellValue($leDebut.$i,utf8_encode($rowContrat['Coeff']));
					}
				}
			}
		}
		
		//Liste des ODM de la personne 
		$req="
			SELECT Id,
				DateDebut,DateFin,MontantIPD,MontantIGD,PrimeResponsabilite,IndemniteOutillage
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='2020-12-31'
				AND (DateFin>='2020-01-01' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('ODM')
				AND Id_Personne=".$row['Id_Personne']."
				ORDER BY DateDebut
			";
		$resultContrat=mysqli_query($bdd,$req);
		$nbContrat=mysqli_num_rows($resultContrat);
		
		$debutS1="";
		$debutS2="";
		$finS1="";
		$finS2="";
		if($nbContrat>0){
			while($rowContrat=mysqli_fetch_array($resultContrat))
			{
				//DEBUT
				if($rowContrat['DateDebut']<'2020-07-01'){
					//Début Semestre 1
					if($rowContrat['DateDebut']<'2020-01-01'){
						$debutS1="AI";
					}
					else{
						$col="AI";
						for($laDate='2020-01-01';$laDate<'2020-07-01';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($laDate==$rowContrat['DateDebut']){
								$debutS1=$col;
								break;
							}
							$col++;
						}
					}
				}
				else{
					//Début Semestre 2
					$col="AH";
					for($laDate='2020-07-01';$laDate<='2020-12-31';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						if($laDate==$rowContrat['DateDebut']){
							$debutS2=$col;
							break;
						}
						$col++;
					}
				}
				
				//FIN
				if($rowContrat['DateFin']>='2020-07-01' || $rowContrat['DateFin']<='0001-01-01'){
					//Fin Semestre 2
					if($rowContrat['DateFin']>'2020-12-31' || $rowContrat['DateFin']<='0001-01-01'){
						$finS2="HI";
					}
					else{
						$col="AH";
						for($laDate='2020-07-01';$laDate<='2020-12-31';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($laDate==$rowContrat['DateFin']){
								$finS2=$col;
								break;
							}
							$col++;
						}
					}
				}
				else{
					//Fin Semestre 1
					$col="AI";
					for($laDate='2020-01-01';$laDate<'2020-07-01';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						if($laDate==$rowContrat['DateFin']){
							$finS1=$col;
							break;
						}
						$col++;
					}
				}
				if($debutS1<>"" && $finS2<>""){
					$finS1="HG";
					$debutS2="AH";
				}
				
				//Compléter Semestre 1
				if($debutS1<>"" && $finS1<>"" && $debutS1<=$finS1){
					for($leDebut=$debutS1;$leDebut<=$finS1;$leDebut++){
						$sheetJT1->setCellValue($leDebut.$i,utf8_encode($rowContrat['MontantIPD']));
						$sheetJC1->setCellValue($leDebut.$i,utf8_encode($rowContrat['MontantIGD']));
						$sheetOUT1->setCellValue($leDebut.$i,utf8_encode($rowContrat['IndemniteOutillage']));
						$sheetPrime1->setCellValue($leDebut.$i,utf8_encode($rowContrat['PrimeResponsabilite']));
					}
				}
				
				//Compléter Semestre 2
				if($debutS2<>"" && $finS2<>"" && $debutS2<=$finS2){
					for($leDebut=$debutS2;$leDebut<=$finS2;$leDebut++){
						$sheetJT2->setCellValue($leDebut.$i,utf8_encode($rowContrat['MontantIPD']));
						$sheetJC2->setCellValue($leDebut.$i,utf8_encode($rowContrat['MontantIGD']));
						$sheetOUT2->setCellValue($leDebut.$i,utf8_encode($rowContrat['IndemniteOutillage']));
						$sheetPrime2->setCellValue($leDebut.$i,utf8_encode($rowContrat['PrimeResponsabilite']));
					}
				}
			}
		}
		
		$i++;
	}
}
 
//Enregistrement du fichier excel

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="BDD_Contrat.xlsx"'); 
header('Cache-Control: max-age=0'); 
	
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

?>