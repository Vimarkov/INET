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
$excel = $workbook->load('TemplatePlanning.xlsx');
$sheetContrat1 = $excel->getSheetByName('Contrat S1');
$sheetContrat2 = $excel->getSheetByName('Contrat S2');
$sheetSemestre1 = $excel->getSheetByName('Semestre1');
$sheetSemestre2 = $excel->getSheetByName('Semestre2');


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
		
		$sheetSemestre1->setCellValue('A'.($i+1),utf8_encode($row['Nom']));
		$sheetSemestre1->setCellValue('B'.($i+1),utf8_encode($row['Prenom']));
		$sheetSemestre1->setCellValue('C'.($i+1),utf8_encode($Matricule));
		$sheetSemestre1->setCellValue('D'.($i+1),utf8_encode(($i+1)));
		
		$sheetSemestre2->setCellValue('A'.($i+1),utf8_encode($row['Nom']));
		$sheetSemestre2->setCellValue('B'.($i+1),utf8_encode($row['Prenom']));
		$sheetSemestre2->setCellValue('C'.($i+1),utf8_encode($Matricule));
		$sheetSemestre2->setCellValue('D'.($i+1),utf8_encode(($i+1)));
		
		//Liste des contrats de la personne
		$req="
			SELECT Id,Id_TempsTravail,
				(SELECT Code FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS CodeContrat,
				(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
				Coeff,DateDebut,DateFin,SalaireBrut,TauxHoraire*151.57 AS SalaireInt,
				(SELECT Code FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS CodeAgence
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
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
		
		$debutSemS1="";
		$debutSemS2="";
		$finSemS1="";
		$finSemS2="";
		
		$dateDebutS1="";
		$dateDebutS2="";
		$dateFinS1="";
		$dateFinS2="";
		
		
		if($nbContrat>0){
			while($rowContrat=mysqli_fetch_array($resultContrat))
			{
				//DEBUT
				if($rowContrat['DateDebut']<'2020-07-01'){
					//Début Semestre 1
					if($rowContrat['DateDebut']<'2020-01-01'){
						$debutS1="AI";
						$debutSemS1="G";
						$dateDebutS1=date('2020-01-01');
					}
					else{
						$col="AI";
						$colSem="G";
						for($laDate='2020-01-01';$laDate<'2020-07-01';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($laDate==$rowContrat['DateDebut']){
								$debutS1=$col;
								$debutSemS1=$colSem;
								$dateDebutS1=$laDate;
								break;
							}
							$col++;
						}
					}
				}
				else{
					//Début Semestre 2
					$col="AH";
					$colSem="F";
					for($laDate='2020-07-01';$laDate<='2020-12-31';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						if($laDate==$rowContrat['DateDebut']){
							$debutS2=$col;
							$debutSemS2=$colSem;
							$dateDebutS2=$laDate;
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
						$finSemS2="GG";
						$dateFinS2=date('2020-12-31');
					}
					else{
						$col="AH";
						$colSem="F";
						for($laDate='2020-07-01';$laDate<='2020-12-31';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($laDate==$rowContrat['DateFin']){
								$finS2=$col;
								$finSemS2=$colSem;
								$dateFinS2=$laDate;
								break;
							}
							$col++;
						}
					}
				}
				else{
					//Fin Semestre 1
					$col="AI";
					$colSem="G";
					for($laDate='2020-01-01';$laDate<'2020-07-01';$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						if($laDate==$rowContrat['DateFin']){
							$finS1=$col;
							$finSemS1=$colSem;
							$dateFinS1=$laDate;
							break;
						}
						$col++;
					}
				}
				if($debutS1<>"" && $finS2<>""){
					$finS1="HG";
					$debutS2="AH";
					
					$finSemS1="GE";
					$debutSemS2="F";
					
					$dateDebutS2=date('2020-07-01');
					$dateFinS1=date('2020-06-30');
				}
				
				//Compléter Semestre 1
				if($debutS1<>"" && $finS1<>"" && $debutS1<=$finS1){
					$leDebutSem=$debutSemS1;
					for($leDebut=$debutS1;$leDebut<=$finS1;$leDebut++){
						//Contrat
						if($rowContrat['EstInterim']==1){
							$sheetContrat1->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeAgence']));
						}
						else{
							$sheetContrat1->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeContrat']));
						}
					}
				}
				
				//Compléter Semestre 2
				if($debutS2<>"" && $finS2<>"" && $debutS2<=$finS2){
					$leDebutSem=$debutSemS2;
					for($leDebut=$debutS2;$leDebut<=$finS2;$leDebut++){
						//Contrat
						if($rowContrat['EstInterim']==1){
							$sheetContrat2->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeAgence']));
						}
						else{
							$sheetContrat2->setCellValue($leDebut.$i,utf8_encode($rowContrat['CodeContrat']));
						}
					}
				}
			}
		}
		
		//Semestres 
		
		//Liste des absences
		$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
					rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
					AND rh_absence.DateFin>='2020-01-01' 
					AND rh_absence.DateDebut<='2020-12-31' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND rh_personne_demandeabsence.Conge=0 
					AND EtatN1<>-1
					AND EtatN2<>-1
					ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);
		
		$tmpDate = date("2020-01-01");
		$colonne = 6;
		$colonneLettre = "G";
		//Semestre 1
		while ($tmpDate < date("2020-06-30")) {
			
			//Métier ce jour là
			$reqContrat="SELECT 
					(SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".$tmpDate."'
					AND (DateFin>='".$tmpDate."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					AND Id_Personne=".$row['Id_Personne']."
					ORDER BY DateDebut DESC, Id DESC";
			$resultContrat=mysqli_query($bdd,$reqContrat);
			$nbResultaContrat=mysqli_num_rows($resultContrat);

			$CodeMetier="";
			if($nbResultaContrat>0)
			{
				$rowContat=mysqli_fetch_array($resultContrat);
				$CodeMetier=$rowContat['CodeMetier'];
			}
		
			//Recherche si planning pour ce jour-ci
			$contenu="";
			$Id_Contenu=0;
			$estUnConge=0;
			$Travail=0;
			$PrestationSelect=0;
			$PoleSelect=0;
			$Couleur=TravailCeJourDeSemaine($tmpDate,$row['Id_Personne']);
			
			//Récupérer la prestation et pôle de la personne 
			$PrestaPole=PrestationPole_Personne($tmpDate,$row['Id_Personne']);
			
			if($PrestaPole<>0){
				$tab=explode("_",$PrestaPole);
				$PrestationSelect=$tab[0];
				$PoleSelect=$tab[1];
			}
			
			if(appartientPrestation($tmpDate,$row['Id_Personne'],$PrestationSelect,$PoleSelect)==1){
				$reqPrestation = "SELECT Libelle, Id_Plateforme FROM new_competences_prestation WHERE Id='".$PrestationSelect."'";
				$resultPrestation=mysqli_query($bdd,$reqPrestation);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				$codePrestation = "";

				if ($nbPrestation>0){
					$rowPresta=mysqli_fetch_array($resultPrestation);
					if($rowPresta['Id_Plateforme']==1){
						$codePrestation = AfficheCodePrestation($rowPresta['Libelle']);
					}
					else{
						$codePrestation = "T-YYYYY";
					}
				}
				else{
					$codePrestation = "T-YYYYY";
				}
				
				$tabDateMois = explode('-', $tmpDate);
				$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
				if($Couleur <> ""){
					$Travail=1;
					$contenu="J";
					$Id_Vacation=IdVacationCeJourDeSemaine($tmpDate,$row['Id_Personne']);
					if($Id_Vacation>0){
						$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
						$resultVac=mysqli_query($bdd,$req);
						$nbVac=mysqli_num_rows($resultVac);
						if($nbVac>0){
							$rowVac=mysqli_fetch_array($resultVac);
							$contenu=$rowVac['Nom'];
						}
					}
					$Id_Contenu=1;
					$estUneVacation=1;

					$jourFixe=estJour_Fixe($tmpDate,$row['Id_Personne']);
					if($jourFixe<>""){
						$contenu=$jourFixe;
						$Id_Contenu=estJour_Fixe_Id($tmpDate,$row['Id_Personne']);
						$estUneVacation=0;
					}
					
					//Vérifier si la personne n'a pas une vacation particulière ce jour là 
					$Id_Vacation=VacationPersonne($tmpDate,$row['Id_Personne'],$PrestationSelect,$PoleSelect);
					if($Id_Vacation>0){
						$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
						$resultVac=mysqli_query($bdd,$req);
						$nbVac=mysqli_num_rows($resultVac);
						if($nbVac>0){
							$rowVac=mysqli_fetch_array($resultVac);
							$contenu=$rowVac['Nom'];
							$Id_Contenu=$Id_Vacation;
							$estUneVacation=1;
						}
					}
				}
					
				//Absences
				if($Travail==1){
					if($nbAbs>0){
						mysqli_data_seek($resultAbs,0);
						while($rowAbs=mysqli_fetch_array($resultAbs)){
							if($rowAbs['DateDebut']<=$tmpDate && $rowAbs['DateFin']>=$tmpDate){
								if($rowAbs['NbHeureAbsJour']<>0 || $rowAbs['NbHeureAbsNuit']<>0){
									
								}
								else{
									if($rowAbs['TypeAbsenceDef']<>""){
										$contenu=$rowAbs['TypeAbsenceDef'];
										$Id_Contenu=$rowAbs['Id_TypeAbsenceDefinitif'];
										$estUneVacation=0;
										if($rowAbs['Id_TypeAbsenceDefinitif']==0){
											$contenu="ABS";
											$Id_Contenu=0;
											$estUneVacation=0;
										}
									}
									else{
										$contenu=$rowAbs['TypeAbsenceIni'];
										$Id_Contenu=$rowAbs['Id_TypeAbsenceInitial'];
										$estUneVacation=0;
										if($rowAbs['Id_TypeAbsenceInitial']==0){$contenu="ABS";$Id_Contenu=0;}
									}
								}
								break;
							}
						}
					}
				}

				//Cellule finale
				if ($contenu<>"")
				{
					//$sheetSemestre1->setCellValueByColumnAndRow($colonne,($i+1),utf8_encode($codePrestation."\n".$CodeMetier."\n".$contenu));
					$sheetSemestre1->setCellValueByColumnAndRow($colonne,($i+1),utf8_encode($contenu));
					$sheetSemestre1->getStyle($colonneLettre.($i+1))->getAlignment()->setWrapText(true);
				}
			}
			//Jour suivant
			$tabDate = explode('-', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
			$tmpDate = date("Y-m-d", $timestamp);
			$colonne++;
			$colonneLettre++;
		}
		
		$tmpDate = date("2020-07-01");
		$colonne = 5;
		$colonneLettre = "F";
		//Semestre 2
		while ($tmpDate < date("2020-12-31")) {
			
			//Métier ce jour là
			$reqContrat="SELECT 
					(SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".$tmpDate."'
					AND (DateFin>='".$tmpDate."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					AND Id_Personne=".$row['Id_Personne']."
					ORDER BY DateDebut DESC, Id DESC";
			$resultContrat=mysqli_query($bdd,$reqContrat);
			$nbResultaContrat=mysqli_num_rows($resultContrat);

			$CodeMetier="";
			if($nbResultaContrat>0)
			{
				$rowContat=mysqli_fetch_array($resultContrat);
				$CodeMetier=$rowContat['CodeMetier'];
			}
		
			//Recherche si planning pour ce jour-ci
			$contenu="";
			$Id_Contenu=0;
			$estUnConge=0;
			$Travail=0;
			$PrestationSelect=0;
			$PoleSelect=0;
			$Couleur=TravailCeJourDeSemaine($tmpDate,$row['Id_Personne']);
			
			//Récupérer la prestation et pôle de la personne 
			$PrestaPole=PrestationPole_Personne($tmpDate,$row['Id_Personne']);
			
			if($PrestaPole<>0){
				$tab=explode("_",$PrestaPole);
				$PrestationSelect=$tab[0];
				$PoleSelect=$tab[1];
			}
			
			if(appartientPrestation($tmpDate,$row['Id_Personne'],$PrestationSelect,$PoleSelect)==1){
				$reqPrestation = "SELECT Libelle,Id_Plateforme FROM new_competences_prestation WHERE Id='".$PrestationSelect."'";
				$resultPrestation=mysqli_query($bdd,$reqPrestation);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				$codePrestation = "";

				if ($nbPrestation>0){
					$rowPresta=mysqli_fetch_array($resultPrestation);
					if($rowPresta['Id_Plateforme']==1){
						$codePrestation = AfficheCodePrestation($rowPresta['Libelle']);
					}
					else{
						$codePrestation = "T-YYYYY";
					}
				}
				else{
					$codePrestation = "T-YYYYY";
				}
				$tabDateMois = explode('-', $tmpDate);
				$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
				if($Couleur <> ""){
					$Travail=1;
					$contenu="J";
					$Id_Vacation=IdVacationCeJourDeSemaine($tmpDate,$row['Id_Personne']);
					if($Id_Vacation>0){
						$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
						$resultVac=mysqli_query($bdd,$req);
						$nbVac=mysqli_num_rows($resultVac);
						if($nbVac>0){
							$rowVac=mysqli_fetch_array($resultVac);
							$contenu=$rowVac['Nom'];
						}
					}
					$Id_Contenu=1;
					$estUneVacation=1;

					$jourFixe=estJour_Fixe($tmpDate,$row['Id_Personne']);
					if($jourFixe<>""){
						$contenu=$jourFixe;
						$Id_Contenu=estJour_Fixe_Id($tmpDate,$row['Id_Personne']);
						$estUneVacation=0;
					}
					
					//Vérifier si la personne n'a pas une vacation particulière ce jour là 
					$Id_Vacation=VacationPersonne($tmpDate,$row['Id_Personne'],$PrestationSelect,$PoleSelect);
					if($Id_Vacation>0){
						$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
						$resultVac=mysqli_query($bdd,$req);
						$nbVac=mysqli_num_rows($resultVac);
						if($nbVac>0){
							$rowVac=mysqli_fetch_array($resultVac);
							$contenu=$rowVac['Nom'];
							$Id_Contenu=$Id_Vacation;
							$estUneVacation=1;
						}
					}
				}
					
				//Absences
				if($Travail==1){
					if($nbAbs>0){
						mysqli_data_seek($resultAbs,0);
						while($rowAbs=mysqli_fetch_array($resultAbs)){
							if($rowAbs['DateDebut']<=$tmpDate && $rowAbs['DateFin']>=$tmpDate){
								if($rowAbs['NbHeureAbsJour']<>0 || $rowAbs['NbHeureAbsNuit']<>0){
									
								}
								else{
									if($rowAbs['TypeAbsenceDef']<>""){
										$contenu=$rowAbs['TypeAbsenceDef'];
										$Id_Contenu=$rowAbs['Id_TypeAbsenceDefinitif'];
										$estUneVacation=0;
										if($rowAbs['Id_TypeAbsenceDefinitif']==0){
											$contenu="ABS";
											$Id_Contenu=0;
											$estUneVacation=0;
										}
									}
									else{
										$contenu=$rowAbs['TypeAbsenceIni'];
										$Id_Contenu=$rowAbs['Id_TypeAbsenceInitial'];
										$estUneVacation=0;
										if($rowAbs['Id_TypeAbsenceInitial']==0){$contenu="ABS";$Id_Contenu=0;}
									}
								}
								break;
							}
						}
					}
				}

				//Cellule finale
				if ($contenu<>"")
				{
					//$sheetSemestre2->setCellValueByColumnAndRow($colonne,($i+1),utf8_encode($codePrestation."\n".$CodeMetier."\n".$contenu));
					$sheetSemestre2->setCellValueByColumnAndRow($colonne,($i+1),utf8_encode($contenu));
					$sheetSemestre2->getStyle($colonneLettre.($i+1))->getAlignment()->setWrapText(true);
				}
			}
			//Jour suivant
			$tabDate = explode('-', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
			$tmpDate = date("Y-m-d", $timestamp);
			$colonne++;
			$colonneLettre++;
		}
		
		$i++;
	}
}

//Enregistrement du fichier excel

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="BDD_Planning.xlsx"'); 
header('Cache-Control: max-age=0'); 
	
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');

?>