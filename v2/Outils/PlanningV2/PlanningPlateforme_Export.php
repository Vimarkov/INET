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
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Heures');

$reqPrestation = "SELECT Id_Plateforme FROM new_competences_prestation WHERE Id='".$_SESSION['FiltreRHPlanning_Prestation']."'";
$resultPrestation=mysqli_query($bdd,$reqPrestation);
$nbPrestation=mysqli_num_rows($resultPrestation);
$PlateformeSelect = 0;

if ($nbPrestation>0){
	$row=mysqli_fetch_array($resultPrestation);
	$PlateformeSelect = $row['Id_Plateforme'];
}

$annee = date("Y", strtotime($_SESSION['FiltreRHPlanning_DateDebut']." +0 day"));
$moisAffichage = date("m", strtotime($_SESSION['FiltreRHPlanning_DateDebut']." +0 day"));
$DateCalcul = date("Y-m-d",strtotime($_SESSION['FiltreRHPlanning_DateDebut']." +0 day"));
$tabDateCalcul = explode('-', $DateCalcul);
$timestampCalcul = mktime(0, 0, 0, $tabDateCalcul[1], $tabDateCalcul[2], $tabDateCalcul[0]);
$DateResult = date("Y-m-d",$timestampCalcul);
$DateAffichageResult = date("d-m-Y",$timestampCalcul);

$dateDebut = $DateAffichageResult;
$dateFin = date("Y-m-d",strtotime($_SESSION['FiltreRHPlanning_DateFin']." +0 day"));
$tabDateFin = explode('-', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y-m-d", $timestampFin);
$dateDeFin = date('d-m-Y', $timestampFin);
		
		
$tabDateDebut = explode('-', $dateDebut);
$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
$tmpDate = date("Y-m-d",$timestampDebut);

$tabDateFin = explode('-', $dateDeFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
$dateFin = date("Y-m-d", $timestampFin);

$tabDate = explode('-', $tmpDate);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$tmpMois = date('n', $timestamp) . ' ' . date('Y', $timestamp);
$cptJour = 0;

if($_SESSION["Langue"]=="FR"){
	$joursem = array("D", "L", "M", "M", "J", "V", "S");
	$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
}
else{
	$joursem = array("M", "T", "W", "T", "F", "S", "S");
	$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
}
// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
$mois = 0;
$colonne=5;
$colonneL = "E";
$colDebutMois = "F";
$colFinMois = "F";
$colDernierMois = "E";
$colDebutSem = "F";
$colFinSem = "F";
$colDernierSem = "E";

while ($tmpDate <= $dateFin) {
	$colDernierMois++;
	$colDernierSem++;
	$sheet->getColumnDimension($colDernierMois)->setWidth(5);
	$tabDate = explode('-', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jour = date('w', $timestamp);
	$mois = $tabDate[1];
	$semaine = date('W', $timestamp);
	
	$sheet->setCellValueByColumnAndRow($colonne,3,utf8_encode($joursem[$jour]));
	$sheet->setCellValueByColumnAndRow($colonne,4,utf8_encode($tabDate[2]));

	//Jour suivant
	$tabDate = explode('-', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
	$tmpDate = date("Y-m-d", $timestamp);
	
	if (date('m', $timestamp) <> $tabDate[1]){
		$sheet->mergeCells($colDebutMois.'1:'.$colFinMois.'1');
		$sheet->setCellValue($colDebutMois.'1',utf8_encode($MoisLettre[$mois-1]." ".$tabDate[0]));
		$colFinMois++;
		$colDebutMois = $colFinMois;
	}
	else{
		$colFinMois++;
	}
	if (date('W', $timestamp) <> $semaine){
		$sheet->mergeCells($colDebutSem.'2:'.$colFinSem.'2');
		$sheet->setCellValue($colDebutSem.'2',utf8_encode("S".$semaine.""));
		$colFinSem++;
		$colDebutSem = $colFinSem;
	}
	else{
		$colFinSem++;
	}
	$cptJour++;
	$colonne++;
	$colonneL++;
}

if (date('m', $timestamp) == $tabDate[1]){
	$sheet->mergeCells($colDebutMois.'1:'.$colDernierMois.'1');
	$sheet->setCellValue($colDebutMois.'1',utf8_encode($MoisLettre[$mois-1]." ".$tabDate[0]));
}

if ($joursem[$jour]<>"D"){
	$colFinSem++;
	$sheet->mergeCells($colDebutSem.'2:'.$colDernierSem.'2');
	$sheet->setCellValue($colDebutSem.'2',utf8_encode("S".$semaine.""));
}

$colonneL++;
$colonneDivers = 47;

$colonneL = "AV";
$sheet->mergeCells($colonneL.'1:'.$colonneL.'4');

$sheet->getColumnDimension('A')->setWidth(12);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(0);

$sheet->getStyle('F1:'.$colonneL.'4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$colonneL.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F3:'.$colonneL.'4')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffcc99'))));

$sheet->getStyle('A1:E4')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'c0c0c0'))));

if($_SESSION["Langue"]=="FR"){
	
	$sheet->setCellValue('B1',utf8_encode('ANNEE'));
	$sheet->setCellValue('B2',utf8_encode('MOIS'));
	$sheet->setCellValue('B3',utf8_encode('CODE SITE'));
}
else{
	$sheet->setCellValue('B1',utf8_encode('YEAR'));
	$sheet->setCellValue('B2',utf8_encode('MONTH'));
	$sheet->setCellValue('B3',utf8_encode('SITE CODE'));

}
$sheet->getStyle('B1:B3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'cbffcb'))));

$sheet->setCellValue('C1',utf8_encode($annee));
$sheet->setCellValue('C2',utf8_encode($moisAffichage));
$sheet->getStyle('C1:C3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffffff'))));

$sheet->setCellValue('E2','0');

if($_SESSION["Langue"]=="FR"){
	$sheet->setCellValue('A4',utf8_encode('PRESTATION'));
	$sheet->setCellValue('B4',utf8_encode("Nom"));
	$sheet->setCellValue('C4',utf8_encode('Pr�nom'));
	$sheet->setCellValue('D4',utf8_encode('M�tier'));
}
else{
	$sheet->setCellValue('A4',utf8_encode('SITE'));
	$sheet->setCellValue('B4',utf8_encode("Name"));
	$sheet->setCellValue('C4',utf8_encode('First name'));
	$sheet->setCellValue('D4',utf8_encode('Job'));
	
}
$sheet->setCellValue('E4',utf8_encode($DateAffichageResult));
// FIN GESTION DES ENTETES DU TABLEAU

//DEBUT CORPS DU TABLEAU
$tabDateDebut = explode('-', $dateDebut);
$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
$tmpDate = date("Y-m-d",$timestampDebut);

$tabDateFin = explode('-', $dateDeFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
$dateFin = date("Y-m-d", $timestampFin);

$Debut = $tmpDate;
$Fin = $dateFin;

//Personnes  pr�sentent sur cette plateforme �ces dates
$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
			new_rh_etatcivil.Nom,
			new_rh_etatcivil.Prenom,
		(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS CodeMetier
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHPlanning_DateFin']."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHPlanning_DateDebut']."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect."
		ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC
		";
$resultPersonne=mysqli_query($bdd,$req);
$nbPersonne=mysqli_num_rows($resultPersonne);
$cptPersonne = 0;

if ($nbPersonne > 0)
{
	$ligne = 5;
	while($row=mysqli_fetch_array($resultPersonne)){
		$Id_Personne = $row['Id'];
		$cptPersonne = $cptPersonne + 1;
		$divers = "";

		$Metier="";
		$Code=$row['CodeMetier'];

		//Heures absences
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($row['Nom']));
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($row['Prenom']));
		$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($Code));
		
		//Liste des cong�s
		$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
					rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
					rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
					(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS CouleurIni,
					(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS CouleurDef,
					(SELECT rh_typeabsence.NecessiteJustif FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS NecessiteJustifIni,
					(SELECT rh_typeabsence.NecessiteJustif FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS NecessiteJustifDef
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id']." 
					AND rh_absence.DateFin>='".$Debut."' 
					AND rh_absence.DateDebut<='".$dateFin."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0 
					AND rh_personne_demandeabsence.Annulation=0 
					AND rh_personne_demandeabsence.Conge=1 
					AND EtatN2=1
					AND EtatRH=1
					ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";

		$resultConges=mysqli_query($bdd,$reqConges);
		$nbConges=mysqli_num_rows($resultConges);

		//Liste des absences
		$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
					rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
					(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
					(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS CouleurIni,
					(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS CouleurDef,
					(SELECT rh_typeabsence.NecessiteJustif FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS NecessiteJustifIni,
					(SELECT rh_typeabsence.NecessiteJustif FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS NecessiteJustifDef
					FROM rh_absence 
					LEFT JOIN rh_personne_demandeabsence 
					ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
					WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id']." 
					AND rh_absence.DateFin>='".$Debut."' 
					AND rh_absence.DateDebut<='".$dateFin."' 
					AND rh_personne_demandeabsence.Suppr=0 
					AND rh_absence.Suppr=0  
					AND rh_personne_demandeabsence.Conge=0 
					AND EtatN1<>-1
					AND EtatN2<>-1
					ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
		$resultAbs=mysqli_query($bdd,$reqAbs);
		$nbAbs=mysqli_num_rows($resultAbs);

		//Liste des heures suppl�mentaires
		$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,DatePriseEnCompteRH,HeuresFormation
				FROM rh_personne_hs
				WHERE Suppr=0 
				AND Id_Personne=".$row['Id']." 
				AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$Debut."' 
				AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$dateFin."' 
				AND Etat4=1
				AND DatePriseEnCompteRH>'0001-01-01'
				";
		$resultHS=mysqli_query($bdd,$req);
		$nb2HS=mysqli_num_rows($resultHS);
							
		//Liste des astreintes
		$req="SELECT IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte) AS DateAstreinte,DatePriseEnCompte,
			TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
			TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
			TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,Montant,Intervention
			FROM rh_personne_rapportastreinte
			WHERE rh_personne_rapportastreinte.Suppr=0
			AND rh_personne_rapportastreinte.Id_Personne=".$row['Id']."
			AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$Debut."' 
			AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)<='".$dateFin."' 
			AND EtatN2=1
			AND EtatRH=1
			";
		$resultAst=mysqli_query($bdd,$req);
		$nbAst=mysqli_num_rows($resultAst);
		
		//Formation dans l'outil formation 
		$req="  SELECT
					form_session_date.DateSession,
					Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause
				FROM
					form_session_date 
					LEFT JOIN form_session 
					ON form_session_date.Id_Session=form_session.Id
				WHERE
					form_session_date.Suppr=0 
					AND form_session.Suppr=0
					AND form_session.Annule=0 
					AND form_session_date.DateSession>='".$Debut."'
					AND form_session_date.DateSession<='".$dateFin."'
					AND
					(
						SELECT
							COUNT(form_session_personne.Id) 
						FROM
							form_session_personne
						WHERE
							form_session_personne.Suppr=0
							AND form_session_personne.Id_Personne=".$row['Id']." 
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Id_Session=form_session.Id
							AND Presence IN (0,1)
				   )>0 ";
		$resultSession=mysqli_query($bdd,$req);
		$nbSession=mysqli_num_rows($resultSession);
		
		//VM
		$req="  SELECT DateVisite,HeureVisite, DATE_ADD(HeureVisite, INTERVAL 2 HOUR) AS HeureFin
				FROM rh_personne_visitemedicale
				WHERE Suppr=0 
				AND DateVisite>='".$Debut."'
				AND DateVisite<='".$dateFin."'
				AND Id_Personne=".$row['Id']." ";
		$resultVM=mysqli_query($bdd,$req);
		$nbVM=mysqli_num_rows($resultVM);
		
		$ligneDebut = $ligne;
		$ligneFin = $ligne;
		$tabDateDebut = explode('-', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
		$tmpDate = date("Y-m-d",$timestampDebut);
		$colonne = 5;
		$colonneLettre = "F";
		$codePrestation = "";
		
		while ($tmpDate <= $dateFin) {
			//Recherche si planning pour ce jour-ci
			$Couleur = "";
			$CelPlanning= "";
			$ClassDiv = "";
			$contenu="";
			$indice="";
			$Id_Contenu=0;
			$estUneVacation=0;
			$valAstreinte="";
			$estUnConge=0;
			$Travail=0;
			$IndiceAbs="";
			$NbHeureAbsJour=0;
			$NbHeureAbsNuit=0;
			$NbHeureSuppJour=0;
			$NbHeureSuppNuit=0;
			$nbHeureSuppForm=0;
			$nbHS=0;
			$nbHeureFormationVac=date('H:i',strtotime($tmpDate.' 00:00:00'));
			$nbHeureFormation=date('H:i',strtotime($tmpDate.' 00:00:00'));
			$nbHeureVMVac=date('H:i',strtotime($tmpDate.' 00:00:00'));
			$nbHeureVM=date('H:i',strtotime($tmpDate.' 00:00:00'));
			$Couleur=TravailCeJourDeSemaine($tmpDate,$row['Id']);
			
			//R�cup�rer la prestation et p�le de la personne 
			$PrestaPole=PrestationPole_Personne($tmpDate,$row['Id']);
			$PrestationSelect=0;
			$PoleSelect=0;
			if($PrestaPole<>0){
				$tab=explode("_",$PrestaPole);
				$PrestationSelect=$tab[0];
				$PoleSelect=$tab[1];
			}

			if(appartientPrestation($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect)==1){
				
				$reqPrestation = "SELECT Libelle FROM new_competences_prestation WHERE Id='".$PrestationSelect."'";
				$resultPrestation=mysqli_query($bdd,$reqPrestation);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				$NomPrestation = "";
				
				
				if($codePrestation==""){
					if ($nbPrestation>0){
						$rowPresta=mysqli_fetch_array($resultPrestation);
						$NomPrestation = $rowPresta['Libelle'];
						$codePrestation = AfficheCodePrestation($NomPrestation);
					}
				}
				
				$tabDateMois = explode('-', $tmpDate);
				$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
				if ($Couleur == ""){
						if(estWE($timestampMois)){
							$Couleur="style='background-color:".$Gris.";'";
							$ClassDiv ="weekFerieV2";
						}
						else{
							$ClassDiv ="semaine";
						}
				}
				else{
					$Travail=1;
					if(estWE($timestampMois)){
						$ClassDiv ="weekFerieV2";
					}
					else{
						$ClassDiv ="semaine";
					}
					$Id_Contenu=IdVacationCeJourDeSemaine($tmpDate,$row['Id']);
					if($Id_Contenu==1){
						if($_SESSION["Langue"]=="FR"){$contenu="J";}
						else{$contenu="D";}
					}
					elseif($Id_Contenu==15){
						if($_SESSION["Langue"]=="FR"){$contenu="SDL";}
						else{$contenu="SDL";}
					}
					elseif($Id_Contenu==18){
						if($_SESSION["Langue"]=="FR"){$contenu="SD";}
						else{$contenu="SD";}
					}
					else{
						if($_SESSION["Langue"]=="FR"){$contenu="VSD";}
						else{$contenu="VSD";}
					}
					$estUneVacation=1;
					$Couleur="style='background-color:".$Couleur.";'";

					$jourFixe=estJour_Fixe($tmpDate,$row['Id']);
					$Id_Contrat =IdContrat($row['Id'],$tmpDate);
					if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
						$Couleur="style='background-color:".$Automatique.";'";
						$contenu=$jourFixe;
						$Id_Contenu=estJour_Fixe_Id($tmpDate,$row['Id']);
						$estUneVacation=0;
					}
					
					//V�rifier si la personne n'a pas une vacation particuli�re ce jour l� 
					$Id_Vacation=VacationPersonne($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
					if($Id_Vacation>0){
						$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
						$resultVac=mysqli_query($bdd,$req);
						$nbVac=mysqli_num_rows($resultVac);
						if($nbVac>0){
							$rowVac=mysqli_fetch_array($resultVac);
							$Couleur="style='background-color:".$rowVac['Couleur'].";'";
							$contenu=$rowVac['Nom'];
							$Id_Contenu=$Id_Vacation;
							$estUneVacation=1;
						}
						$infoDivers=VacationPersonneDivers($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
						if($infoDivers<>""){
							if($divers<>""){$divers.="\n";}
							$divers.=$infoDivers;
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
									$NbHeureAbsJour=$rowAbs['NbHeureAbsJour'];
									$NbHeureAbsNuit=$rowAbs['NbHeureAbsNuit'];
									if($rowAbs['TypeAbsenceDef']<>""){
										$IndiceAbs=$rowAbs['TypeAbsenceDef']." ";
										if($rowAbs['Id_TypeAbsenceDefinitif']==0){
											$IndiceAbs="ABS ";
										}
										if($rowAbs['NecessiteJustifDef']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
									else{
										$IndiceAbs=$rowAbs['TypeAbsenceIni']." ";
										if($rowAbs['Id_TypeAbsenceInitial']==0){
											$IndiceAbs="ABS ";
										}
										if($rowAbs['NecessiteJustifIni']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
								}
								else{
									if($rowAbs['TypeAbsenceDef']<>""){
										$contenu=$rowAbs['TypeAbsenceDef'];
										$Id_Contenu=$rowAbs['Id_TypeAbsenceDefinitif'];
										$estUneVacation=0;
										$Couleur="style='background-color:".$rowAbs['CouleurDef'].";'";
										if($rowAbs['Id_TypeAbsenceDefinitif']==0){
											$contenu="ABS";
											$Id_Contenu=0;
											$estUneVacation=0;
											$Couleur="style='background-color:#ff1111;'";
										}
										if($rowAbs['NecessiteJustifDef']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
									else{
										$contenu=$rowAbs['TypeAbsenceIni'];
										$Id_Contenu=$rowAbs['Id_TypeAbsenceInitial'];
										$estUneVacation=0;
										$Couleur="style='background-color:".$rowAbs['CouleurIni'].";'";
										if($rowAbs['Id_TypeAbsenceInitial']==0){$contenu="ABS";$Id_Contenu=0;$Couleur="style='background-color:#ff1111;'";}
										if($rowAbs['NecessiteJustifIni']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
								}
								break;
							}
						}
					}
				}
				
				//Cong�s
				if($nbConges>0){
					mysqli_data_seek($resultConges,0);
					while($rowConges=mysqli_fetch_array($resultConges)){
						if($rowConges['DateDebut']<=$tmpDate && $rowConges['DateFin']>=$tmpDate){
							$IndiceAbs="";
							$NbHeureAbsJour=0;
							$NbHeureAbsNuit=0;
							
							$jourFixe=estJour_Fixe($tmpDate,$row['Id']);
							$Id_Contrat =IdContrat($row['Id'],$tmpDate);
							$Id_Type=$rowConges['Id_TypeAbsenceInitial'];
							if($rowConges['Id_TypeAbsenceDefinitif']<>0){$Id_Type=$rowConges['Id_TypeAbsenceDefinitif'];}
							if($jourFixe<>"" && estCalendaire($Id_Type)==0 && Id_TypeContrat($Id_Contrat)<>18){
								$Couleur="style='background-color:".$Automatique.";'";
								$contenu=$jourFixe;
								$Id_Contenu=estJour_Fixe_Id($tmpDate,$row['Id']);
								$estUneVacation=0;
							}
							else{
								if($rowConges['NbHeureAbsJour']<>0 || $rowConges['NbHeureAbsNuit']<>0){
									$NbHeureAbsJour=$rowConges['NbHeureAbsJour'];
									$NbHeureAbsNuit=$rowConges['NbHeureAbsNuit'];
									if($rowConges['TypeAbsenceDef']<>""){
										$IndiceAbs=$rowConges['TypeAbsenceDef']." ";
										if($rowConges['NecessiteJustifDef']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
									else{
										$IndiceAbs=$rowConges['TypeAbsenceIni']." ";
										if($rowConges['NecessiteJustifIni']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
								}
								else{
									if($rowConges['TypeAbsenceDef']<>""){
										$contenu=$rowConges['TypeAbsenceDef'];
										$Id_Contenu=$rowConges['Id_TypeAbsenceDefinitif'];
										$estUneVacation=0;
										$Couleur="style='background-color:".$rowConges['CouleurDef'].";'";
										
										if($rowConges['NecessiteJustifDef']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
									else{
										$contenu=$rowConges['TypeAbsenceIni'];
										$Id_Contenu=$rowConges['Id_TypeAbsenceInitial'];
										$estUneVacation=0;
										$Couleur="style='background-color:".$rowConges['CouleurIni'].";'";
										if($rowConges['NecessiteJustifIni']==1){
											if($divers<>""){$divers.="\n";}
											if($_SESSION['Langue']=="FR"){
												$divers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Justificatif manquant ";
											}
											else{
												$diversdivers.=AfficheDateJJ_MM_AAAA($tmpDate)." : Missing proof ";
											}
										}
									}
								}
							}
							break;
						}
					}
				}
					
				//Astreintes
				if($nbAst>0){
					mysqli_data_seek($resultAst,0);
					while($rowAst=mysqli_fetch_array($resultAst)){
						if($rowAst['DateAstreinte']==$tmpDate){
							$valAstreinte.=" AS";
							$nbHeures="0 ";
							if($rowAst['Intervention']==1){
								$nbHeures=Ajouter_Heures($rowAst['DiffHeures1'],$rowAst['DiffHeures2'],$rowAst['DiffHeures3']);
								$tabHeure=explode(".",$nbHeures);
								if(sizeof($tabHeure)==2){
									$valAstreinte.=" ".$tabHeure[0].".".round(($tabHeure[1]/60)*100,0);
								}
								else{
									$valAstreinte.=" ".$tabHeure[0];
								}
							}
							if(estSalarie($tmpDate,$row['Id'])==1){
								if($_SESSION['Langue']=="FR"){
									if($divers<>""){$divers.="\n";}
									$divers.="Le ".AfficheDateJJ_MM($tmpDate).", astreinte avec ".$nbHeures."h d'intervention = ".$rowAst['Montant']." euros";
								}
								else{
									if($divers<>""){$divers.="\n";}
									$divers.="".AfficheDateJJ_MM($tmpDate).", on-call ".$nbHeures."h of intervention = ".$rowAst['Montant']." euros";
								}
							}
						}
					}
				}
					
				$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode($contenu.$valAstreinte));			
			}
			//Jour suivant
			$tabDate = explode('-', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
			$tmpDate = date("Y-m-d", $timestamp);
			$colonne++;
			$colonneLettre++;
		}
		
		$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($codePrestation));
		$ligne ++;
	}
	$ligne = $ligne - 1;
	
	for ($i=$colDebutSem;$i<='AU';$i++){
		$sheet->getColumnDimension($i)->setWidth(5);
	}
	
	$sheet->getColumnDimension($colonneL)->setWidth(30);
	$sheet->getStyle($colonneL.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$ligne = 5;
	
	mysqli_data_seek($resultPersonne,0);
	while($row=mysqli_fetch_array($resultPersonne)){
		$ligne ++;
	}
	mysqli_free_result($resultPersonne);
	
 }

 $sheet->setCellValue('E1',$cptPersonne);


 $sheet->freezePane('F5');
 
//Enregistrement du fichier excel

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Planning.xlsx"'); 
header('Cache-Control: max-age=0'); 
	
$writer = new PHPExcel_Writer_Excel2007($workbook);

$chemin = '../../tmp/Planning.xlsx';
$writer->save($chemin);
readfile($chemin);
?>