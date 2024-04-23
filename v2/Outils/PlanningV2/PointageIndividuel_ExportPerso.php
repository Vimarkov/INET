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

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('releve heure base.xlsx');
$sheet = $excel->getSheetByName('Releve d heures');

$mois=$_GET['Mois'];
$dateDebut=date($_GET['Annee']."-".$mois."-01");;
$dateFin = $dateDebut;
$tabDateFin = explode('-', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y-m-d", $timestampFin);
		
$Id_Personne=$_SESSION['Id_Personne'];

$laDateDebut=$dateDebut;
$laDateFin=$dateFin;

$sheet2 = $sheet->copy();

$reqPers="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
$resultPers=mysqli_query($bdd,$reqPers);
$nbPers=mysqli_num_rows($resultPers);
if ($nbPers>0)
{
	$rowPersonne=mysqli_fetch_array($resultPers);
	$personne = strtr(
		$rowPersonne['Personne'], 
		'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
		'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
	);

	$sheet2->setTitle(substr($personne,0,31));
	$excel->addSheet($sheet2);
	
	completerReleve($sheet2,$Id_Personne,$laDateDebut,$laDateFin);
}



$excel->removeSheetByIndex($excel->getIndex($sheet));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="PointagesIndividuels.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/PointagesIndividuels.xlsx';
$writer->save($chemin);
readfile($chemin);

function completerReleve($sheet,$IdPersonne,$DateCalcul,$dateFin){
	global $bdd;
	$EnAttente="#ffbf03";
	$Automatique="#3d9538";
	$Validee="#6beb47";
	$Refusee="#ff5353";
	$Gris="#dddddd";
	$AbsenceInjustifies="#ff0303";
	$annee = date("Y", strtotime($DateCalcul." +0 day"));
	$moisAffichage = date("m", strtotime($DateCalcul." +0 day"));
	$tabDateCalcul = explode('-', $DateCalcul);
	$timestampCalcul = mktime(0, 0, 0, $tabDateCalcul[1], $tabDateCalcul[2], $tabDateCalcul[0]);
	$JourCalcul = date("w",$timestampCalcul);
	$converJour = array(6, 0, 1, 2, 3, 4, 5);
	$JourCalcul = $converJour[$JourCalcul];

	$Personne = "";
	$MatriculeAAA="";
	$reqPers = "SELECT Nom, Prenom, IF(MatriculeAAA<>'',MatriculeAAA,MatriculeDaher) AS MatriculeAAA, MatriculeDSK FROM new_rh_etatcivil WHERE Id=".$IdPersonne."";
	$resultPers=mysqli_query($bdd,$reqPers);
	$nbPers=mysqli_num_rows($resultPers);
	if ($nbPers>0)
	{
		$row=mysqli_fetch_array($resultPers);
		$Personne = $row['Nom']." ".$row['Prenom'];
	}

	$sheet->setCellValue('M1',$moisAffichage);
	$sheet->setCellValue('Q1',$annee);
	$sheet->setCellValue('D6',utf8_encode($MatriculeAAA));
	$sheet->setCellValue('D8',utf8_encode($Personne));
	//$sheet->setCellValue('D10',utf8_encode($codePrestation));
	//$sheet->setCellValue('O6',utf8_encode($codeAnalytique));
	
	//Liste des congés
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
				WHERE rh_personne_demandeabsence.Id_Personne=".$IdPersonne." 
				AND rh_absence.DateFin>='".$DateCalcul."' 
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
				WHERE rh_personne_demandeabsence.Id_Personne=".$IdPersonne." 
				AND rh_absence.DateFin>='".$DateCalcul."' 
				AND rh_absence.DateDebut<='".$dateFin."' 
				AND rh_personne_demandeabsence.Suppr=0 
				AND rh_absence.Suppr=0  
				AND rh_personne_demandeabsence.Conge=0 
				AND EtatN1<>-1
				AND EtatN2<>-1
				ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
	$resultAbs=mysqli_query($bdd,$reqAbs);
	$nbAbs=mysqli_num_rows($resultAbs);

	//Liste des heures supplémentaires
	$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,DatePriseEnCompteRH,HeuresFormation
			FROM rh_personne_hs
			WHERE Suppr=0 
			AND Id_Personne=".$IdPersonne." 
			AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$DateCalcul."' 
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
		AND rh_personne_rapportastreinte.Id_Personne=".$IdPersonne."
		AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$DateCalcul."' 
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
				AND form_session_date.DateSession>='".$DateCalcul."'
				AND form_session_date.DateSession<='".$dateFin."'
				AND
				(
					SELECT
						COUNT(form_session_personne.Id) 
					FROM
						form_session_personne
					WHERE
						form_session_personne.Suppr=0
						AND form_session_personne.Id_Personne=".$IdPersonne." 
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
			AND DateVisite>='".$DateCalcul."'
			AND DateVisite<='".$dateFin."'
			AND Id_Personne=".$IdPersonne." ";
	$resultVM=mysqli_query($bdd,$req);
	$nbVM=mysqli_num_rows($resultVM);
	
	$divers="";
			
	$divers="";
	$req="SELECT Divers	 
		FROM rh_personne_plateforme_planning_export 
		WHERE Suppr=0 
		AND Id_Personne=".$IdPersonne." 
		AND Mois=".date('m',strtotime($DateCalcul.' 00:00:00'))." 
		AND Annee=".date('Y',strtotime($DateCalcul.' 00:00:00'))." ";
	$resultDivers=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($resultDivers);
	if($nb>0){
		$rowDivers=mysqli_fetch_array($resultDivers);
		$divers=stripslashes($rowDivers['Divers']);
	}
	
	//Recherche du premier jour du mois
	$colonneJour = "B";
	$colonneF = "C";
	$colonneJ = "D";
	$colonneEJ = "F";
	$colonneEN = "G";
	$colonneP = "H";
	$colonnePresta="I";
	$colonneVac="J";

	$ligne = 15 + $JourCalcul;
	
	while ($DateCalcul  < $dateFin)
	{
		//Recherche si planning pour ce jour-ci
		$Couleur = "";
		$CelPlanning= "";
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
		$nbHeureFormationVac=date('H:i',strtotime($DateCalcul.' 00:00:00'));
		$nbHeureFormation=date('H:i',strtotime($DateCalcul.' 00:00:00'));
		
		$nbHeureVMVac=date('H:i',strtotime($DateCalcul.' 00:00:00'));
		$nbHeureVM=date('H:i',strtotime($DateCalcul.' 00:00:00'));
									
		$Couleur=TravailCeJourDeSemaine($DateCalcul,$IdPersonne);

		$tabDateMois = explode('-', $DateCalcul);
		$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
		
		$PrestationSelect=0;
		$PoleSelect=0;
		$Prestation="";
		$PrestaPole=PrestationPole_Personne($DateCalcul,$IdPersonne);
		if($PrestaPole<>0){
			$tab=explode("_",$PrestaPole);
			$PrestationSelect=$tab[0];
			$PoleSelect=$tab[1];
			
			$req="SELECT Id_Plateforme,LEFT(Libelle,7) AS Presta FROM new_competences_prestation WHERE Id=".$PrestationSelect;
			$resultPresta=mysqli_query($bdd,$req);
			$nPresta=mysqli_num_rows($resultPresta);
			if($nPresta>0){
				$rowPresta=mysqli_fetch_array($resultPresta);
				$Prestation=$rowPresta['Presta'];
			}
		}
		
		//Récupérer les dates pour cette agence
		$req = "SELECT 
			(SELECT UCASE(CONCAT(Nom,' ',Prenom)) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
			DateDebut,DateFin
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".$DateCalcul."'
			AND (DateFin>='".$DateCalcul."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			AND Id_Personne=".$IdPersonne."
			ORDER BY DateDebut DESC, Id DESC
		";
		$resultContrat=mysqli_query($bdd,$req);
		$nbC=mysqli_num_rows($resultContrat);

		//Uniquement si la prestation appartient à la plateforme E/C + vérifier si en contrat a cette date pour cette agence
		if($nbC>0){
			if ($Couleur == ""){
				if(estWE($timestampMois)){
					$Couleur="style='background-color:".$Gris.";'";
				}
			}
			else{
				$Travail=1;
				//Vérifier si la personne est en VSD ce jour là
				$Id_Contenu=IdVacationCeJourDeSemaine($DateCalcul,$IdPersonne);
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

				$jourFixe=estJour_Fixe($DateCalcul,$IdPersonne);
				$Id_Contrat =IdContrat($IdPersonne,$DateCalcul);
				if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
					$Couleur="style='background-color:".$Automatique.";'";
					$contenu=$jourFixe;
					$Id_Contenu=estJour_Fixe_Id($DateCalcul,$IdPersonne);
					$estUneVacation=0;
				}
				
				//Vérifier si la personne n'a pas une vacation particulière ce jour là 
				$Id_Vacation=VacationPersonne($DateCalcul,$IdPersonne,$PrestationSelect,$PoleSelect);
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
					
					$infoDivers=VacationPersonneDivers($DateCalcul,$IdPersonne,$PrestationSelect,$PoleSelect);
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
						if($rowAbs['DateDebut']<=$DateCalcul && $rowAbs['DateFin']>=$DateCalcul){
							if($rowAbs['NbHeureAbsJour']<>0 || $rowAbs['NbHeureAbsNuit']<>0){
								$NbHeureAbsJour=$rowAbs['NbHeureAbsJour'];
								$NbHeureAbsNuit=$rowAbs['NbHeureAbsNuit'];
								$nbTotalHeures=$NbHeureAbsJour+$NbHeureAbsNuit;
								if($rowAbs['TypeAbsenceDef']<>""){
									$IndiceAbs=$rowAbs['TypeAbsenceDef']." ";
									if($rowAbs['Id_TypeAbsenceDefinitif']==0){
										$IndiceAbs="ABS ";
									}
									if($rowAbs['NecessiteJustifDef']==1){
										if($divers<>""){$divers.="\n";}
										if($_SESSION['Langue']=="FR"){
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
										}
									}
									//EM 100% et 50%
									if($rowAbs['Id_TypeAbsenceDefinitif']==26 || $rowAbs['Id_TypeAbsenceDefinitif']==30){
										if($divers<>""){$divers.="\n";}
										$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : ".$rowAbs['TypeAbsenceDef']." ( ".$nbTotalHeures."h)";
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
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
										}
									}
									//EM 100% et 50%
									if($rowAbs['Id_TypeAbsenceInitial']==26 || $rowAbs['Id_TypeAbsenceInitial']==30){
										if($divers<>""){$divers.="\n";}
										$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : ".$rowAbs['TypeAbsenceIni']." ( ".$nbTotalHeures."h)";
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
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
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
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
										}
									}
								}
							}
							break;
						}
					}
				}
			}
			//Congés
			if($nbConges>0){
				mysqli_data_seek($resultConges,0);
				while($rowConges=mysqli_fetch_array($resultConges)){
					if($rowConges['DateDebut']<=$DateCalcul && $rowConges['DateFin']>=$DateCalcul){
						
						$IndiceAbs="";
						$NbHeureAbsJour=0;
						$NbHeureAbsNuit=0;
						
						$jourFixe=estJour_Fixe($DateCalcul,$IdPersonne);
						$Id_Contrat =IdContrat($IdPersonne,$DateCalcul);
						$Id_Type=$rowConges['Id_TypeAbsenceInitial'];
						if($rowConges['Id_TypeAbsenceDefinitif']<>0){$Id_Type=$rowConges['Id_TypeAbsenceDefinitif'];}
						if($jourFixe<>"" && estCalendaire($Id_Type)==0 && Id_TypeContrat($Id_Contrat)<>18){
							$Couleur="style='background-color:".$Automatique.";'";
							$contenu=$jourFixe;
							$Id_Contenu=estJour_Fixe_Id($DateCalcul,$IdPersonne);
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
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
										}
									}
								}
								else{
									$IndiceAbs=$rowConges['TypeAbsenceIni']." ";
									if($rowConges['NecessiteJustifIni']==1){
										if($divers<>""){$divers.="\n";}
										if($_SESSION['Langue']=="FR"){
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
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
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
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
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Justificatif manquant ";
										}
										else{
											$divers.=AfficheDateJJ_MM_AAAA($DateCalcul)." : Missing proof ";
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
					if($rowAst['DateAstreinte']==$DateCalcul){
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
						if(estSalarie($DateCalcul,$IdPersonne)==1){
							if($_SESSION['Langue']=="FR"){
								if($divers<>""){$divers.="\n";}
								$divers.="Le ".AfficheDateJJ_MM($DateCalcul).", astreinte avec ".$nbHeures."h d'intervention = ".$rowAst['Montant']." euros";
							}
							else{
								if($divers<>""){$divers.="\n";}
								$divers.="".AfficheDateJJ_MM($DateCalcul).", on-call ".$nbHeures."h of intervention = ".$rowAst['Montant']." euros";
							}
						}
					}
				}
			}
			
			//HS
			if($nb2HS>0){
				mysqli_data_seek($resultHS,0);
				while($rowHS=mysqli_fetch_array($resultHS)){
					if($rowHS['DateHS']==$DateCalcul){
						if($rowHS['HeuresFormation']==1){
							$nbHeureSuppForm+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
						}
						else{
							$nbHS+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
							$NbHeureSuppJour+=$rowHS['Nb_Heures_Jour'];
							$NbHeureSuppNuit+=$rowHS['Nb_Heures_Nuit'];
						}
						if($indice<>""){$indice.="+";}
						if($_SESSION["Langue"]=="FR"){$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."HS";}
						else{$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."OT";}
					}
				}
			}
			
			//Horaires de la personne
			$HeureDebutTravail="00:00:00";
			$HeureFinTravail="00:00:00";

			$tab=HorairesJournee($IdPersonne,$DateCalcul);
			if(sizeof($tab)>0){
				$HeureDebutTravail=$tab[0];
				$HeureFinTravail=$tab[1];
			}

			
			if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
				//Formation 
				if($nbSession>0){
					mysqli_data_seek($resultSession,0);
					while($rowForm=mysqli_fetch_array($resultSession)){
						if($rowForm['DateSession']==$DateCalcul){
						//Nombre total d'heure de formation
						$hF=strtotime($rowForm['Heure_Fin']);
						$hD=strtotime($rowForm['Heure_Debut']);
						$val=gmdate("H:i",$hF-$hD);
						$bTrouve=1;
						if($rowForm['PauseRepas']==1){
							$hFP=strtotime($rowForm['HeureFinPause']);
							$hDP=strtotime($rowForm['HeureDebutPause']);
							if($hDP<$hF && $hFP>$hD){
								if($hFP>$hF){$hFP=$hF;}
								if($hDP<$hD){$hDP=$hD;}
								$valPause=gmdate("H:i",$hFP-$hDP);
								$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
							}
						}
						
						$nbHeureFormation=date('H:i',strtotime($nbHeureFormation." ".str_replace(":"," hour ",$val)." minute"));

						//Nombre d'heure pendant la vacation 
						if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";}
						$hFTravail=strtotime($HeureFinTravail);
						$hDTravail=strtotime($HeureDebutTravail);
						if($hDTravail>$hD || $hFTravail<$hF){
							if($hFTravail<$hF){$hF=$hFTravail;}
							if($hDTravail>$hD){$hD=$hDTravail;}
						}
						$val=gmdate("H:i",$hF-$hD);
						
						if($hDTravail>$hF || $hFTravail<$hD){
							$hF=0;
							$hD=0;
							$val=0;
						}
						
						if($hD<>0 && $hF<>0){
							if($rowForm['PauseRepas']==1){
								$hFP=strtotime($rowForm['HeureFinPause']);
								$hDP=strtotime($rowForm['HeureDebutPause']);
								if($hDP<$hF && $hFP>$hD){
									if($hFP>$hF){$hFP=$hF;}
									if($hDP<$hD){$hDP=$hD;}
									$valPause=gmdate("H:i",$hFP-$hDP);
									$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
								}
							}
						}
		
						$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$val)." minute"));

					}
					}
				}
				
				//VM 
				if($nbVM>0){
					$bTrouve=0;
					mysqli_data_seek($resultVM,0);
					while($rowVM=mysqli_fetch_array($resultVM)){
						if($rowVM['DateVisite']==$DateCalcul){
							
							//Nombre total d'heure de formation
							$hF=strtotime($rowVM['HeureFin']);
							$hD=strtotime($rowVM['HeureVisite']);
							$val=gmdate("H:i",$hF-$hD);
							$bTrouve=1;
							if($_SESSION['Langue']=="FR"){
							 $divers.="<br>Visite médicale (".substr($rowVM['HeureVisite'],0,5).")";	
							}
							else{
								$divers.="<br>Medical visit (".substr($rowVM['HeureVisite'],0,5).")";	
							}
							
							if(estSalarie($DateCalcul,$IdPersonne)==0){
								$nbHeureVM=date('H:i',strtotime($nbHeureVM." ".str_replace(":"," hour ",$val)." minute"));
								//Nombre d'heure pendant la vacation 
								$hFTravail=strtotime($HeureFinTravail);
								$hDTravail=strtotime($HeureDebutTravail);
								if($hFTravail<$hF){$hF=$hFTravail;}
								if($hDTravail>$hD){$hD=$hDTravail;}
								$val=gmdate("H:i",$hF-$hD);
								
								$nbHeureVMVac=date('H:i',strtotime($nbHeureVMVac." ".str_replace(":"," hour ",$val)." minute"));
							}
						}
					}
				}
			}
			
			//récupérer le jour de la semaine 
			$tabDate = explode('-', $DateCalcul);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
			$jourSemaine = date('w', $timestamp);
			$tab=PointagePrestationVacation($PrestationSelect,$PoleSelect,$Id_Contenu,$jourSemaine,$DateCalcul);
			$nbHeure=0;
			$nbHeureJ=0;
			$nbHeureEJ=0;
			$nbHeureEN=0;
			$nbHeurePause=0;
			$nbHeureFor=0;
			$nbHeureForm=intval(date('H',strtotime($nbHeureFormation." + 0 hour"))).".".substr((date('i',strtotime($nbHeureFormation." + 0 hour"))/0.6),0,2);
			//On ne compte pas les heures hors vacation
			$nbHeureForm=0;
			$lesminutes=substr(date('i',strtotime($nbHeureFormationVac." + 0 hour"))/0.6,0,2);
			if(substr($lesminutes,1,1)=="."){
				$lesminutes="0".substr($lesminutes,0,1);
			}
			$nbHeureFormVac=intval(date('H',strtotime($nbHeureFormationVac." + 0 hour"))).".".$lesminutes;
			
			$nbHeureVisite=intval(date('H',strtotime($nbHeureVM." + 0 hour"))).".".substr((date('i',strtotime($nbHeureVM." + 0 hour"))/0.6),0,2);
			$nbHeureVisitemVac=intval(date('H',strtotime($nbHeureVMVac." + 0 hour"))).".".substr((date('i',strtotime($nbHeureVMVac." + 0 hour"))/0.6),0,2);
			
			$nbHeureFormPlus=0;
			if(estInterim($DateCalcul,$IdPersonne)){
				if($nbHeureFormVac==7){$nbHeureFormPlus=1;}
			}
			
			if($estUneVacation==0){
				$nbHeureFormVac=0;
			}
			
			$info="";
			if($estUneVacation<>0){
				if(sizeof($tab)>0){
					$nbHeure=$tab[0]+$tab[1]+$tab[2]+$tab[3];
					$nbHeureJ=$tab[0];
					$nbHeureEJ=$tab[1];
					$nbHeureEN=$tab[2];
					$nbHeurePause=$tab[3];
					$nbHeureFor=$tab[4];
				}

				$tabContrat=PointagePersonneContrat($DateCalcul,$IdPersonne,$Id_Contenu,$jourSemaine);
				if(sizeof($tabContrat)>0){
					$nbHeure=$tabContrat[0]+$tabContrat[1]+$tabContrat[2]+$tabContrat[3];
					$nbHeureJ=$tabContrat[0];
					$nbHeureEJ=$tabContrat[1];
					$nbHeureEN=$tabContrat[2];
					$nbHeurePause=$tabContrat[3];
					$nbHeureFor=0;
					
					if($Id_Contenu==6){
						$nbHeureFor=$nbHeureJ;
						$nbHeureJ=0;
					}
					
				}
			}
			
			if($estUneVacation==0){
				$nbHeureJ=0;
			}
			
			if($nbHeureFormVac>0){
				$nbHeureJ=$nbHeureJ-$nbHeureFormVac-$nbHeureFormPlus;

				if($nbHeureJ<0){
					if($nbHeureEJ>0){
						$nbHeureEJ=$nbHeureEJ+$nbHeureJ;
					}
					if($nbHeureEJ<0){
						$nbHeureEJ=0;
					}
					$nbHeureJ=0;
				}
			}
			
			if($nbHeureVisitemVac>0){
				$nbHeureJ=$nbHeureJ-$nbHeureVisitemVac;
			}

			//Ajout des heures supp 
			if($nbHeureEJ>0){
				$nbHeureEJ=$nbHeureEJ+$NbHeureSuppJour;
			}
			else{
				$nbHeureJ=$nbHeureJ+$NbHeureSuppJour;
			}
			
			$nbHeureEN=$nbHeureEN+$NbHeureSuppNuit;
			$nbHeure=$nbHeure+$NbHeureSuppJour+$NbHeureSuppNuit;
			
			if($NbHeureAbsJour>0){
				$nbHeureJ=$nbHeureJ-$NbHeureAbsJour;
				if($nbHeureJ<0){
					if($nbHeureEJ>=0){
						$nbHeureEJ=$nbHeureEJ+$nbHeureJ;
					}
					if($nbHeureEJ<=0){
						if($nbHeureFormVac>=0){
							$nbHeureFormVac=$nbHeureFormVac+$nbHeureEJ;
						}
						if($nbHeureFormVac<=0){
							$nbHeureFormVac=0;
						}
						$nbHeureEJ=0;
					}
					$nbHeureJ=0;
				}
			}
			if($NbHeureAbsNuit>0){
				$nbHeureEN=$nbHeureEN-$NbHeureAbsNuit;
				if($nbHeureEN<0){

					$nbHeureEN=0;
				}
			}
			
			if($estUneVacation==0){
				if($contenu<>""){$nbHeureJ=$contenu;}
				else{
					if($nbHeureJ==0){$nbHeureJ=$contenu;}
				}
			}
			
			$nbHeureForm=$nbHeureForm+$nbHeureFor+$nbHeureFormVac+$nbHeureSuppForm;
			
			$tab=PointagePersonneExceptionnel($IdPersonne,$PrestationSelect,$PoleSelect,$DateCalcul);
			if($estUneVacation<>0){
				if(sizeof($tab)>0){
					if($tab[0]+$tab[1]+$tab[2]+$tab[4]+$tab[6]>0 || $tab[5]==1){
						$nbHeure=$tab[0]+$tab[1]+$tab[2]+$tab[4]+$tab[6];
						$nbHeureJ=$tab[0];
						$nbHeureEJ=$tab[1];
						$nbHeureEN=$tab[2];
						$nbHeurePause=$tab[3];
						$nbHeureForm=$tab[4]+$tab[6];
					}
				}
			}
			
			//Cellule finale
			if ($nbHeureForm == 0){$nbHeureForm = "";}
			if ($nbHeureEJ == 0){$nbHeureEJ = "";}
			if ($nbHeureEN == 0){$nbHeureEN = "";}
			if ($nbHeurePause == 0){$nbHeurePause = "";}
			if ($nbHeureJ == 0 && is_numeric($nbHeureJ)){
				if(AfficheZeroPrestationVacation($PrestationSelect,$PoleSelect,$Id_Contenu,$jourSemaine,$DateCalcul)==0){
					$nbHeureJ = "";
				}
			}
			
			$tabDate = explode('-', $DateCalcul);
			
			if($contenu=="TELETRAVAIL"){$contenu="TT";}
			if($contenu=="TT"){$nbHeureJ=$contenu;}
			
			$sheet->setCellValue($colonneJour.$ligne,$tabDate[2]);
			$sheet->setCellValue($colonneF.$ligne,$nbHeureForm);
			$sheet->setCellValue($colonneJ.$ligne,$nbHeureJ.$valAstreinte);
			$sheet->setCellValue($colonneEJ.$ligne,$nbHeureEJ);
			$sheet->setCellValue($colonneEN.$ligne,$nbHeureEN);
			$sheet->setCellValue($colonneP.$ligne,$nbHeurePause);
			if($nbHeureJ<>"" || $nbHeureForm<>"" || $nbHeureEJ<>"" || $nbHeureEN<>"" || $nbHeurePause<>"" || $valAstreinte<>""){
				$sheet->setCellValue($colonnePresta.$ligne,$Prestation);
			}
			$sheet->setCellValue($colonneVac.$ligne,$contenu);
		}
		//Jour suivant
		$tabDate = explode('-', $DateCalcul);
		$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
		$DateCalcul = date("Y-m-d", $timestamp);
		
		$ligne++;
		if ($ligne == 22 || $ligne == 30){$ligne++;}
		if ($colonneF == "C")
		{
			if ($ligne == 38)
			{
				$colonneJour = "L";
				$colonneF = "M";
				$colonneJ = "N";
				$colonneEJ = "P";
				$colonneEN = "Q";
				$colonneP = "R";
				$colonnePresta="S";
				$colonneVac="T";
				$ligne = 15;
			}
		}
	}
	$sheet->setCellValue('E47',utf8_encode($divers));
	$sheet->getStyle('E47')->getAlignment()->setWrapText(true);
}

?>