<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require_once '../Fonctions.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('releve heure base.xlsx');
$sheet = $excel->getSheetByName('Releve d heures');

$reqPrestation = "SELECT Libelle, Code_Analytique FROM new_competences_prestation WHERE Id='".$_GET['Id_Prestation']."'";
$resultPrestation=mysqli_query($bdd,$reqPrestation);
$nbPrestation=mysqli_num_rows($resultPrestation);
$NomPrestation = "";
$codePrestation = "";
$codeAnalytique = "";


if ($nbPrestation>0)
{
	$row=mysqli_fetch_array($resultPrestation);
	$NomPrestation = $row[0];
	$codePrestation = AfficheCodePrestation($NomPrestation);
	$codeAnalytique = $row[1];
}
mysqli_free_result($resultPrestation);
	
$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id='".$_GET['Id_Pole']."'";
$resultPole=mysqli_query($bdd,$reqPole);
$nbPole=mysqli_num_rows($resultPole);
$NomPole = "";
if ($nbPole>0)
{
	$row=mysqli_fetch_array($resultPole);
	$NomPole = $row[0];
}
mysqli_free_result($resultPole);

$PrestationSelect = $_GET['Id_Prestation'];
$PoleSelect = $_GET['Id_Pole'];
$IdPersonne = $_GET['Id_Personne'];

$annee = date("Y", $_GET['lDate']);
$moisAffichage = date("m", $_GET['lDate']);
$DateCalcul = date("Y/m/01",$_GET['lDate']);
$tabDateCalcul = explode('/', $DateCalcul);
$timestampCalcul = mktime(0, 0, 0, $tabDateCalcul[1], $tabDateCalcul[2], $tabDateCalcul[0]);
$JourCalcul = date("w",$timestampCalcul);
$converJour = array(6, 0, 1, 2, 3, 4, 5);
$JourCalcul = $converJour[$JourCalcul];

$dateFin = date("Y/m/1",$_GET['lDate']);
$tabDateFin = explode('/', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y/m/d", $timestampFin);

$Personne = "";
$reqPers = "SELECT Nom, Prenom, Matricule FROM new_rh_etatcivil WHERE Id=".$IdPersonne."";
$resultPers=mysqli_query($bdd,$reqPers);
$nbPers=mysqli_num_rows($resultPers);
if ($nbPers>0)
{
	$row=mysqli_fetch_array($resultPers);
	$Personne = $row['Nom']." ".$row['Prenom'];
}

$sheet->setCellValue('M1',$moisAffichage);
$sheet->setCellValue('Q1',$annee);
$sheet->setCellValue('D8',utf8_encode($Personne));
$sheet->setCellValue('D10',$codePrestation);
$sheet->setCellValue('O6',$codeAnalytique);

//Recherche si planning
$reqPla = "SELECT new_planning_vacationabsence.Nom, new_planning_vacationabsence.Couleur, new_planning_vacationabsence.AbsenceVacation, new_planning_vacationabsence.Description, new_planning_personne_vacationabsence.Commentaire, new_planning_personne_vacationabsence.DatePlanning, new_planning_personne_vacationabsence.Id_Prestation, ";
$reqPla .= "new_planning_personne_vacationabsence.NbHeureJour, new_planning_personne_vacationabsence.NbHeureEquipeJour, new_planning_personne_vacationabsence.NbHeureEquipeNuit, new_planning_personne_vacationabsence.NbHeurePause, new_planning_personne_vacationabsence.ValidationResponsable, new_planning_vacationabsence.Id, new_planning_personne_vacationabsence.NbHeureFormation ";
$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$IdPersonne." AND new_planning_personne_vacationabsence.ValidationResponsable = 1 ";
//$reqPla .= "AND new_planning_personne_vacationabsence.Id_Prestation=".$PrestationSelect." ";
$reqPla .= "AND new_planning_personne_vacationabsence.DatePlanning>='".$DateCalcul."' AND new_planning_personne_vacationabsence.DatePlanning<='".$dateFin."';";
$vacationJour=mysqli_query($bdd,$reqPla);
$nbVacationJour=mysqli_num_rows($vacationJour);

//Recherche du premier jour du mois
$colonneF = "C";
$colonneJ = "D";
$colonneEJ = "F";
$colonneEN = "G";
$colonneP = "H";

$ligne = 15 + $JourCalcul;

while ($DateCalcul  < $dateFin)
{
	//Recherche si planning pour ce jour-ci
	$Absence="";
	$Vacation="";
	$NbForVac ="";
	$NbForHorsVac = "";
	$NbFor = "";
	$NbHeureJ = "";
	$NbHeureEJ = "";
	$NbHeureEN = "";
	$NbHeureP = "";
	
	if ($nbVacationJour>0)
	{
		mysqli_data_seek($vacationJour,0);
		while($rowPlanning=mysqli_fetch_array($vacationJour))
		{
			$tabDateVac = explode('-', $rowPlanning[5]);
			$timestampVac = mktime(0, 0, 0, $tabDateVac[1], $tabDateVac[2], $tabDateVac[0]);
			$dateVac = date("Y/m/d", $timestampVac);
		
			if ($dateVac == $DateCalcul)
			{
				$CelPlanning=$rowPlanning[0];
				if ($rowPlanning[2] == 0)
				{
					$NbFor ="";
					$NbHeureJ = $rowPlanning[0];
					$NbHeureEJ = "";
					$NbHeureEN = "";
					$NbHeureP = "";
				}
				else
				{
					$NbFor = $rowPlanning[13];
					$NbHeureJ = $rowPlanning[7];
					$NbHeureEJ = $rowPlanning[8];
					$NbHeureEN = $rowPlanning[9];
					$NbHeureP = $rowPlanning[10];
				}
			}
		}
	}
	
	//Cellule finale
	if ($NbFor == 0){$NbFor = "";}
	if ($NbHeureEJ == 0){$NbHeureEJ = "";}
	if ($NbHeureEN == 0){$NbHeureEN = "";}
	if ($NbHeureP == 0){$NbHeureP = "";}
	if ($NbHeureJ == 0 && is_numeric($NbHeureJ)){$NbHeureJ = "";}
	
	$sheet->setCellValue($colonneF.$ligne,$NbFor);
	$sheet->setCellValue($colonneJ.$ligne,$NbHeureJ);
	$sheet->setCellValue($colonneEJ.$ligne,$NbHeureEJ);
	$sheet->setCellValue($colonneEN.$ligne,$NbHeureEN);
	$sheet->setCellValue($colonneP.$ligne,$NbHeureP);
	
	//Jour suivant
	$tabDate = explode('/', $DateCalcul);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
	$DateCalcul = date("Y/m/d", $timestamp);
	
	$ligne++;
	if ($ligne == 22 || $ligne == 30){$ligne++;}
	if ($colonneF == "C")
	{
		if ($ligne == 38)
		{
			$colonneF = "M";
			$colonneJ = "N";
			$colonneEJ = "P";
			$colonneEN = "Q";
			$colonneP = "R";
			$ligne = 15;
		}
	}
}
if ($nbVacationJour>0)
{
	mysqli_data_seek($vacationJour,0);
	while($rowPlanning=mysqli_fetch_array($vacationJour)){}
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="PointageIndividuel.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/PointageIndividuel.xlsx';
$writer->save($chemin);
readfile($chemin);
?>