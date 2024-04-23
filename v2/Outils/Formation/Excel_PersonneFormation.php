<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Formations"));
	$sheet->setCellValue('A1',utf8_encode("Prestation"));
	$sheet->setCellValue('B1',utf8_encode("Matricule"));
	$sheet->setCellValue('C1',utf8_encode("Nom"));
	$sheet->setCellValue('D1',utf8_encode("Prénom"));
	$sheet->setCellValue('E1',utf8_encode("Contrat"));
	$sheet->setCellValue('F1',utf8_encode("Type"));
	$sheet->setCellValue('G1',utf8_encode("Formation / Groupe de formation"));
	$sheet->setCellValue('H1',utf8_encode("Lieu"));
	$sheet->setCellValue('I1',utf8_encode("Etat"));
	$sheet->setCellValue('J1',utf8_encode("Présence"));
	$sheet->setCellValue('K1',utf8_encode("Date de début"));
	$sheet->setCellValue('L1',utf8_encode("Heure de début"));
	$sheet->setCellValue('M1',utf8_encode("Date de fin"));
	$sheet->setCellValue('N1',utf8_encode("Heure de fin"));
	$sheet->setCellValue('O1',utf8_encode("Durée"));
	
}
else{
	$sheet->setTitle(utf8_encode("Training"));
	$sheet->setCellValue('A1',utf8_encode("Activity"));
	$sheet->setCellValue('B1',utf8_encode("Matricule"));
	$sheet->setCellValue('C1',utf8_encode("Last name"));
	$sheet->setCellValue('D1',utf8_encode("First name"));
	$sheet->setCellValue('E1',utf8_encode("Contract"));
	$sheet->setCellValue('F1',utf8_encode("Type"));
	$sheet->setCellValue('G1',utf8_encode("Training / Training group"));
	$sheet->setCellValue('H1',utf8_encode("Place"));
	$sheet->setCellValue('I1',utf8_encode("State"));
	$sheet->setCellValue('J1',utf8_encode("Presence"));
	$sheet->setCellValue('K1',utf8_encode("Start date"));
	$sheet->setCellValue('L1',utf8_encode("Start time"));
	$sheet->setCellValue('M1',utf8_encode("End date"));
	$sheet->setCellValue('N1',utf8_encode("End time"));
	$sheet->setCellValue('O1',utf8_encode("Duration"));
	
}

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(30);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);

$sheet->getStyle('A1:O1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:O1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:O1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:O1')->getFont()->setBold(true);
$sheet->getStyle('A1:O1')->getFont()->getColor()->setRGB('1f49a6');

global $id_prestation;
global $id_pole;
global $date_debut;
global $date_fin;
global $formation;
global $grpe_formation;
global $stagiaire;
global $etat;
global $TypeForm;

$prestation=$_SESSION['FiltrePersFormation_Prestation'];
$date_debut=$_SESSION['FiltrePersFormation_DateDebut'];
$date_fin=$_SESSION['FiltrePersFormation_DateFin'];
$formation=$_SESSION['FiltrePersFormation_Formation'];
$grpe_formation=$_SESSION['FiltrePersFormation_GroupeFormation'];
$stagiaire=$_SESSION['FiltrePersFormation_Personne'];
$etat=$_SESSION['FiltrePersFormation_Etat'];
$TypeForm=$_SESSION['FiltrePersFormation_TypeFormation'];

$dateDeFin=date('Y-m-d');
if($date_debut<>""){
	$dateDeFin=TrsfDate_($date_debut);
}
$requetePersonnes="
	SELECT
		Id_Personne
	FROM
		new_competences_personne_prestation
	WHERE
		Date_Fin>='".$dateDeFin."' ";
if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS))
{
	$requetePersonnes.="
		AND Id_Prestation IN
		(
			SELECT
				Id_Prestation 
			FROM
				new_competences_personne_prestation
			LEFT JOIN new_competences_prestation 
				ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE
				Date_Fin>='".$dateDeFin."'
				AND Id_Plateforme IN
				(
					SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
				)
		) ";
	
}
else
{
	$requetePersonnes.="
		AND CONCAT(Id_Prestation,'_',Id_Pole) IN
		(
			SELECT
				CONCAT(Id_Prestation,'_',Id_Pole)  
			FROM
				new_competences_personne_prestation
			WHERE
				Date_Fin>='".$dateDeFin."'
				AND CONCAT(Id_Prestation,'_',Id_Pole) IN
				(
					SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
				)
		)";
}

$resultPersResp=mysqli_query($bdd,$requetePersonnes);
$nbPersResp=mysqli_num_rows($resultPersResp);
$listeRespPers=0;
if($nbPersResp>0)
{
	$listeRespPers="";
	while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPers.=$rowPersResp['Id_Personne'].",";}
	$listeRespPers=substr($listeRespPers,0,-1);
}

$req="
SELECT
	*
FROM
(
	SELECT
		form_session_personne.Id,
		form_session_personne.Id_Besoin,
		form_session_personne.Id_Personne AS Id_Personne,
		form_session_personne.Id_Session AS Id_Session,
		form_session_personne.Validation_Inscription AS Validation_Inscription,
		form_session_personne.Presence,
		form_session_personne.SemiPresence,
		IF(Formation_Liee=1 AND Id_GroupeSession>0,
		(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY DateSession ASC LIMIT 1),
		(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)) AS DateDebut,
		IF(Formation_Liee=1 AND Id_GroupeSession>0,
		(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY DateSession DESC LIMIT 1),
		(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)) AS DateFin,
		IF(Formation_Liee=1 AND Id_GroupeSession>0,
		(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY Heure_Debut ASC LIMIT 1),
		(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY Heure_Debut ASC LIMIT 1)) AS HeureDebut,
		IF(Formation_Liee=1 AND Id_GroupeSession>0,
		(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY Heure_Fin DESC LIMIT 1),
		(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY Heure_Fin DESC LIMIT 1)) AS HeureFin,
		(
			SELECT
			(
				SELECT
					Libelle 
				FROM
					new_competences_prestation 
				WHERE
					new_competences_prestation.Id=form_besoin.Id_Prestation
			)
			FROM
				form_besoin
			WHERE form_besoin.Id=form_session_personne.Id_Besoin
		) AS Prestation,
		(
			SELECT
				form_besoin.Id_Prestation
			FROM
				form_besoin
			WHERE form_besoin.Id=form_session_personne.Id_Besoin
		) AS Id_Prestation,
		(
			SELECT
			(
				SELECT
					Libelle 
				FROM
					new_competences_pole 
				WHERE
					new_competences_pole.Id=form_besoin.Id_Pole
			)
			FROM
				form_besoin
			WHERE
				form_besoin.Id=form_session_personne.Id_Besoin
		) AS Pole,
		(
			SELECT
				form_besoin.Id_Pole
			FROM
				form_besoin
			WHERE form_besoin.Id=form_session_personne.Id_Besoin
		) AS Id_Pole,
		(
			SELECT
				Nom
			FROM
				new_rh_etatcivil
			WHERE
				new_rh_etatcivil.Id=form_session_personne.Id_Personne
		) AS Nom,
		(
			SELECT
				Prenom
			FROM
				new_rh_etatcivil
			WHERE
				new_rh_etatcivil.Id=form_session_personne.Id_Personne
		) AS Prenom,
		(
			SELECT
				CONCAT(Nom,' ',Prenom)
			FROM
				new_rh_etatcivil
			WHERE
				new_rh_etatcivil.Id=form_session_personne.Id_Personne
		) AS Personne,
		(
			SELECT
				MatriculeAAA
			FROM
				new_rh_etatcivil
			WHERE
				new_rh_etatcivil.Id=form_session_personne.Id_Personne
		) AS MATRICULEAAA,
		(
			SELECT 
			(
				SELECT
					Libelle 
				FROM
					form_groupe_formation 
				WHERE
					form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation
			) 
			FROM
				form_session_groupe 
			WHERE
				form_session_groupe.Id=form_session.Id_GroupeSession
		) AS GroupeFormation,
		form_session.Id_Formation AS Id_Formation,
		form_session.Formation_Liee AS Formation_Liee,
		form_session.Recyclage AS Recyclage,
		form_session.Id_Plateforme AS Id_Plateforme,
		form_session.Id_GroupeSession,
		IF(Formation_Liee=0,form_session.Id,form_session.Id_GroupeSession) AS Id_New,
		(SELECT form_formation.Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Id_TypeFormation,
		(SELECT (SELECT Libelle FROM form_typeformation WHERE Id=form_formation.Id_TypeFormation) FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS TypeFormation,
		(
			SELECT
				Libelle
			FROM form_lieu
				WHERE
			form_lieu.Id=form_session.Id_Lieu
		) AS Lieu,
		(
			SELECT
				Id_Langue
			FROM
			form_formation_plateforme_parametres 
			WHERE
				form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
				AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
				AND Suppr=0 LIMIT 1
		) AS Id_Langue,
		(
			SELECT
			(
				SELECT
					Libelle
				FROM
					form_organisme
				WHERE
					Id=Id_Organisme
			)
			FROM
				form_formation_plateforme_parametres 
			WHERE
				form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
			AND Suppr=0 LIMIT 1
		) AS Organisme ,
		(	SELECT
			IF(form_session.Recyclage=0,Duree,DureeRecyclage)
			FROM
				form_formation_plateforme_parametres 
			WHERE
				form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
			AND Suppr=0 LIMIT 1
		) AS Duree,(@row_number:=@row_number + 1) AS rnk
	FROM
		form_session_personne 
	LEFT JOIN
		form_session
	ON
		form_session_personne.Id_Session=form_session.Id
	WHERE
		form_session_personne.Suppr=0
		AND form_session.Annule=0
		AND form_session.Suppr=0
	GROUP BY Id_New,Id_Personne,Formation_Liee,Validation_Inscription
) AS TABLE_GENERALE 
	WHERE  Id_Personne IN (".$listeRespPers.") ";

if($prestation<>"")
{
	$req.="
		AND
		( 
			Prestation LIKE '%".$prestation."%' 
			OR
			Pole LIKE '%".$prestation."%'
		)";
}
if($_SESSION['FiltrePersFormation_RespProjet']<>""){
	$req.="
			AND CONCAT(TABLE_GENERALE.Id_Prestation,'_',TABLE_GENERALE.Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$_SESSION['FiltrePersFormation_RespProjet'].")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
if($stagiaire<>""){$req.="AND Personne LIKE '%".$stagiaire."%' ";}
if($etat=="-2"){$req.="AND (Validation_Inscription<>-1) ";}
else{$req.="AND (Validation_Inscription=".$etat.") ";}
if($TypeForm>0 && $TypeForm<>""){
	$req.=" AND Id_TypeFormation=".$TypeForm." ";
}
if($date_debut<>"")
{
	$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=TABLE_GENERALE.Id_Session ORDER BY DateSession ASC LIMIT 1) >= '".TrsfDate_($date_debut)."' ";
}
if($date_fin<>"")
{
	$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=TABLE_GENERALE.Id_Session ORDER BY DateSession DESC LIMIT 1) <= '".TrsfDate_($date_fin)."' ";
}

if($_SESSION['TriPersFormation_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriPersFormation_General'],0,-1);}

$ResultSessions=mysqli_query($bdd,$req);
$NbSessions=mysqli_num_rows($ResultSessions);

$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
			FROM form_formation_langue_infos 
			WHERE Suppr=0";
$resultFormLangue=mysqli_query($bdd,$reqLangue);
$nbFormLangue=mysqli_num_rows($resultFormLangue);


if($NbSessions>0){
	$ligne=2;
	while($row=mysqli_fetch_array($ResultSessions)){
		$Libelle="";
		if($nbFormLangue>0)
		{
			mysqli_data_seek($resultFormLangue,0);
			while($rowFormLangue=mysqli_fetch_array($resultFormLangue))
			{
				if($rowFormLangue['Id_Formation']==$row['Id_Formation'] && $rowFormLangue['Id_Langue']==$row['Id_Langue'] )
				{
					if($row['Recyclage']==0){$Libelle=stripslashes($rowFormLangue['Libelle']);}
					else
					{
						$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
						if($Libelle==""){$Libelle=stripslashes($rowFormLangue['Libelle']);}
					}
					if($row['Organisme']<>""){$Libelle.=" (".$row['Organisme'].")";}
				}
			}
		}
		$EtatI="";
		$couleur="";
		if($row['Validation_Inscription']==0)
		{
			if($LangueAffichage=="FR"){$EtatI="En attente validation";}
			else{$EtatI="Waiting for validation";}
			$couleur="bgcolor='#ddff00' ";
		}
		elseif($row['Validation_Inscription']==1)
		{
			if($LangueAffichage=="FR"){$EtatI="Validée";}
			else{$EtatI="Validated";}
			$couleur="bgcolor='#34bb37' ";
		}
		elseif($row['Validation_Inscription']==-1)
		{
			if($LangueAffichage=="FR"){$EtatI="Refusée";}
			else{$EtatI="Declined";}
			//$couleur="bgcolor='#f10d0d' ";
		}
		$GroupeFormation="";
		if($row['Formation_Liee']==1){$GroupeFormation=$row['GroupeFormation'];}
		
		$Presence="";
		if($row['Presence']==1 && $row['Validation_Inscription']==1){$Presence= "V";}
		elseif($row['Presence']==-1 && $row['Validation_Inscription']==1){$Presence= "X";}
		elseif($row['Presence']==-2 && $row['Validation_Inscription']==1){$Presence= substr($row['SemiPresence'],0,5);}
		
		if($row['Formation_Liee']==1){
			$laFormation=$GroupeFormation;
		}
		else{
			$laFormation=$Libelle;
		}
		
		$bTrouve=1;
		if($formation<>"" && stripos($laFormation,$formation)===false){$bTrouve=0;}

		if($bTrouve==1){
			$Contrat="";
			$IdContrat=IdContrat($row['Id_Personne'],$row['DateDebut']);
			if($IdContrat>0){
				if(TypeContrat2($IdContrat)<>10){
					$Contrat=TypeContrat($IdContrat);
				}
				else{
					$tab=AgenceInterimContrat($IdContrat);
					if($tab<>0){
						$Contrat=$tab[0];
					}
				}
			}
			$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes(substr($row['Prestation'],0,7))));
			$sheet->setCellValue('B'.$ligne,utf8_encode($row['MATRICULEAAA']));
			$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Nom'])));
			$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Prenom'])));
			$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($Contrat)));
			$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['TypeFormation'])));
			$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($laFormation)));
			$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['Lieu'])));
			$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($EtatI)));
			$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($Presence)));
			$sheet->setCellValue('K'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebut'])));
			$sheet->setCellValue('L'.$ligne,utf8_encode(substr($row['HeureDebut'],0,5)));
			$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFin'])));
			$sheet->setCellValue('N'.$ligne,utf8_encode(substr($row['HeureFin'],0,5)));
			
			
			$nbHeure=date('H:i',strtotime(date('Y-m-d').' 00:00:00'));
			$val=date(str_replace(".", ":", $row['Duree']));
			$nbHeure=date('H:i',strtotime($nbHeure." ".str_replace(":"," hour ",$val)." minute"));
			$Heure=date('H',strtotime($nbHeure." ".str_replace(":"," hour ",$val)." minute"));
			$lesminutes=substr(date('i',strtotime($nbHeure." + 0 hour"))/0.6,0,2);
			if(substr($lesminutes,1,1)=="."){
				$lesminutes="0".substr($lesminutes,0,1);
			}
			
			$nbHeure2=substr($row['Duree'],0,strpos($row['Duree'],".")).".".$lesminutes;
			
			//$sheet->setCellValue('O'.$ligne,utf8_encode(date(str_replace(".", ":", $row['Duree']))));
			$sheet->setCellValue('O'.$ligne,utf8_encode($nbHeure2));
			
			
			$sheet->getStyle('A'.$ligne.':O'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':O'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':O'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$ligne++;
		}
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="PersonneFormation.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="PersonInTraining.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/PersonneFormation.xlsx';

$writer->save($chemin);
readfile($chemin);
?>