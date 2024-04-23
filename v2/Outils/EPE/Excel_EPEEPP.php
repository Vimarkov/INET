<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Fonctions.php");

function unNombreSinonRien($leNombre){
	$nb="";
	if($leNombre<>0){$nb=$leNombre;}
	return $nb;
}

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('TableauEPEEPP.xlsx');
$sheet = $excel->getSheetByName('Feuil1');

$dateDebut=date($_SESSION['FiltreEPEIndicateurs_Annee'].'-01-01');
$dateFin=date($_SESSION['FiltreEPEIndicateurs_Annee'].'-12-31');

$requete="
SELECT DISTINCT (SELECT MetierPaie FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MetierPaie,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
	(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT DateAncienneteCDI FROM new_rh_etatcivil WHERE Id=Id_Personne) AS DateAncienneteCDI,
	(SELECT Contrat FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Contrat,
	(SELECT Cadre FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Cadre,
	Id_Personne
	FROM epe_personne_datebutoir
	WHERE epe_personne_datebutoir.TypeEntretien IN ('EPE','EPP','EPP Bilan') 
	AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
	AND 
	(
		SELECT Id_Prestation
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=epe_personne_datebutoir.Id_Personne
		AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		ORDER BY Date_Fin DESC, Date_Debut DESC
		LIMIT 1
	) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
ORDER BY Nom, Prenom";

$result=mysqli_query($bdd,$requete);
$nbResulta=mysqli_num_rows($result);

$req="DROP TEMPORARY TABLE TMP_EPEEPP;";
$ResultD=mysqli_query($bdd,$req);

$req="CREATE TEMPORARY TABLE TMP_EPEEPP (MetierPaie VARCHAR(255),Nom VARCHAR(255),Prenom VARCHAR(255),MatriculeAAA VARCHAR(255),DateAncienneteCDI DATE,Contrat VARCHAR(255),Cadre INT(11),Id_Personne INT(11),Id_Plateforme INT(11),Id_Prestation INT(11),Id_Manager INT(11),Manager VARCHAR(255),Plateforme VARCHAR(255));";
$resultC=mysqli_query($bdd,$req);

$annee=$_SESSION['FiltreEPEIndicateurs_Annee'];
$dateDebut=date($annee.'-01-01');
$dateFin=date($annee.'-12-31');;
					
if($nbResulta>0){
	while($row=mysqli_fetch_array($result))
	{
		$reqNb="
			SELECT Id_Prestation, Id_Pole,CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole) AS PrestaPole,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) AS Etat,
			Id_Evaluateur AS Id_Manager,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager
			FROM epe_personne
			WHERE epe_personne.Suppr=0 AND epe_personne.Type IN ('EPE','EPP','EPP Bilan') 
			AND Id_Personne=".$row['Id_Personne']."
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
		$ResultNb=mysqli_query($bdd,$reqNb);
		$leNb=mysqli_num_rows($ResultNb);
		$rowNb=mysqli_fetch_array($ResultNb);
		
		$Id_Prestation=0;
		$Id_Pole=0;
		$Id_Plateforme=0;
		
		$req="SELECT Id_Prestation,Id_Pole 
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$row['Id_Personne']." 
			AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
			ORDER BY Date_Fin DESC, Date_Debut DESC
			";
		$resultch=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($resultch);
		$Id_PrestationPole="0_0";
		if($nb>0){
			$rowMouv=mysqli_fetch_array($resultch);
			$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
		}

		$TableauPrestationPole=explode("_",$Id_PrestationPole);
		$Id_Prestation=$TableauPrestationPole[0];
		$Id_Pole=$TableauPrestationPole[1];
		
		$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
		$ResultPlat=mysqli_query($bdd,$req);
		$NbPlat=mysqli_num_rows($ResultPlat);
		if($NbPlat>0){
			$RowPlat=mysqli_fetch_array($ResultPlat);
			$Id_Plateforme=$RowPlat['Id_Plateforme'];
		}
		if($leNb==0){
			$req="SELECT Id_Prestation,Id_Pole 
				FROM new_competences_personne_prestation
				WHERE Id_Personne=".$row['Id_Personne']." 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
				ORDER BY Date_Fin DESC, Date_Debut DESC ";
			
			$resultch=mysqli_query($bdd,$req);
			$lenb=mysqli_num_rows($resultch);
			
			if($lenb>1){
				$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$row['Id_Personne']." AND Id_Manager=0 AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
				$ResultlaPresta=mysqli_query($bdd,$req);
				$NblaPresta=mysqli_num_rows($ResultlaPresta);
				if($NblaPresta>0){
					$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
					$Id_Prestation=$RowlaPresta['Id_Prestation'];
					$Id_Pole=$RowlaPresta['Id_Pole'];
					$Id_Plateforme=$RowlaPresta['Id_Plateforme'];
				}
			}
		}
		else{
			$tab = explode("_",$rowNb['PrestaPole']);
			$Id_Prestation=$tab[0];
			$Id_Pole=$tab[1];
			$Id_Plateforme=$rowNb['Id_Plateforme'];
		}
		
		$Presta="";
		$Plateforme="";
		$req="SELECT LEFT(Libelle,7) AS Prestation, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Presta=$RowPresta['Prestation'];
			$Plateforme=$RowPresta['Plateforme'];
		}
		
		$Pole="";
		$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
		$ResultPole=mysqli_query($bdd,$req);
		$NbPole=mysqli_num_rows($ResultPole);
		if($NbPole>0){
			$RowPole=mysqli_fetch_array($ResultPole);
			$Pole=$RowPole['Libelle'];
		}
		
		if($Pole<>""){$Presta.=" - ".$Pole;}
		
		$Manager="";
		$Id_Manager=0;
		if($leNb==0){
			$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id_Personne']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
			$ResultlaPresta=mysqli_query($bdd,$req);
			$NblaPresta=mysqli_num_rows($ResultlaPresta);
			if($NblaPresta>0){
				$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
				$Id_Manager=$RowlaPresta['Id_Manager'];
				$Manager=$RowlaPresta['Manager'];
			}
			else{
				$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						AND Id_Personne=".$row['Id_Personne']."
						ORDER BY Backup ";
				$ResultManager2=mysqli_query($bdd,$req);
				$NbManager2=mysqli_num_rows($ResultManager2);
				if($NbManager2>0){
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteCoordinateurProjet."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						ORDER BY Backup ";
					$ResultManager=mysqli_query($bdd,$req);
					$NbManager=mysqli_num_rows($ResultManager);
					if($NbManager>0){
						$RowManager=mysqli_fetch_array($ResultManager);
						$Manager=$RowManager['Personne'];
						$Id_Manager=$RowManager['Id'];
					}
				}
				else{
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteChefEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						AND Id_Personne=".$row['Id_Personne']."
						ORDER BY Backup ";
					$ResultManager2=mysqli_query($bdd,$req);
					$NbManager2=mysqli_num_rows($ResultManager2);
					if($NbManager2>0){
						$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							ORDER BY Backup ";
						$ResultManager=mysqli_query($bdd,$req);
						$NbManager=mysqli_num_rows($ResultManager);
						if($NbManager>0){
							$RowManager=mysqli_fetch_array($ResultManager);
							$Manager=$RowManager['Personne'];
							$Id_Manager=$RowManager['Id'];
						}
					}
					else{
						$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteChefEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						ORDER BY Backup ";
						$ResultManager=mysqli_query($bdd,$req);
						$NbManager=mysqli_num_rows($ResultManager);
						if($NbManager>0){
							$RowManager=mysqli_fetch_array($ResultManager);
							$Manager=$RowManager['Personne'];
							$Id_Manager=$RowManager['Id'];
						}
					}
				}
			}
		}
		else{
			$Manager=$rowNb['Manager'];
			$Id_Manager=$rowNb['Id_Manager'];
		}
		
		$req= "INSERT INTO TMP_EPEEPP (MetierPaie,Nom,Prenom,MatriculeAAA,DateAncienneteCDI,Contrat,Cadre,Id_Personne,Id_Plateforme,Id_Prestation,Id_Manager,Manager,Plateforme)
			VALUES ('".addslashes($row['MetierPaie'])."','".addslashes($row['Nom'])."','".addslashes($row['Prenom'])."','".$row['MatriculeAAA']."','".$row['DateAncienneteCDI']."','".addslashes($row['Contrat'])."',".$row['Cadre'].",".$row['Id_Personne'].",".$Id_Plateforme.",".$Id_Prestation.",".$Id_Manager.",'".addslashes($Manager)."','".addslashes($Plateforme)."');";
		$ResultI=mysqli_query($bdd,$req);
	}
}

$requete2="SELECT MetierPaie,Nom,Prenom,MatriculeAAA,DateAncienneteCDI,Contrat,Cadre,Id_Personne,Id_Plateforme,Id_Prestation,Id_Manager,Manager,Plateforme ";
$requete="FROM TMP_EPEEPP 
";
if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
	if($_SESSION['FiltreEPE_Plateforme']<>"0"){
		$requete.=" WHERE Id_Plateforme IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";
	}
}
else{
	$requete.="
	WHERE 
	(Id_Plateforme IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
		)
	)
	";
	if($_SESSION['FiltreEPE_Plateforme']<>"0"){
		$requete.=" AND Id_Plateforme IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";
	}
}
$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);
if($nbResulta>0){
	$ligne=9;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
	
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Plateforme'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['Manager'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Nom'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Prenom'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['MetierPaie']));
		if($row['DateAncienneteCDI']>'0001-01-01'){
			$date = explode("-",$row['DateAncienneteCDI']);
			if(count($date)==3){
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('F'.$ligne,$time);
				$sheet->getStyle('F'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
		}
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Contrat']));
		if($row['Cadre']==1){$cadre="Cadre";}
		else{$cadre="Non cadre";}
		$sheet->setCellValue('H'.$ligne,utf8_encode($cadre));
		
		$requete="
		SELECT Evaluation,
			IF(Note=-2,'',IF(Note=-1,'NA',Note)) AS Note,Commentaire
			FROM epe_personne_objectifanneeprecedente
			LEFT JOIN epe_personne
			ON epe_personne_objectifanneeprecedente.Id_epepersonne=epe_personne.Id
			WHERE epe_personne.Suppr=0 
			AND epe_personne_objectifanneeprecedente.Suppr=0
			AND epe_personne.Type IN ('EPE') 
			AND Id_Personne=".$row['Id_Personne']."
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
			AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') ";
		$result2=mysqli_query($bdd,$requete);
		$nbResulta2=mysqli_num_rows($result2);
		
		$NbNA=0;
		$Nb1=0;
		$Nb2=0;
		$Nb3=0;
		$Nb4=0;
		$total=0;
		if($nbResulta2>0){
			$lettre="K";
			while($row2=mysqli_fetch_array($result2)){
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['Evaluation'])));
				$lettre++;
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['Note'])));
				$lettre++;
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
				$lettre++;
				if($row2['Note']==-1){$NbNA++;}
				elseif($row2['Note']==1){$Nb1++;}
				elseif($row2['Note']==2){$Nb2++;}
				elseif($row2['Note']==3){$Nb3++;}
				elseif($row2['Note']==4){$Nb4++;}
			}
		}
		$total=$Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4);
		
		$sheet->setCellValue('AO'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NbNA))));
		$sheet->setCellValue('AP'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
		$sheet->setCellValue('AQ'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
		$sheet->setCellValue('AR'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
		$sheet->setCellValue('AS'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
		$sheet->setCellValue('AT'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($total))));
		
		$req="SELECT 
				ConnaissanceMetier,UtilisationDoc,Productivite,Organisation,CapaciteManager,
				RespectObjectif,AnglaisTech,CapaciteTuteur,Reporting,PlanAction,RespectBudget,
				RepresentationEntreprise,SouciSatisfaction,Ecoute,TraitementInsatisfaction,ExplicationSolution,
				ComprehensionInsatisfaction,ConnaissanceManagement,ConnaissanceMetierEquipe,CapaciteFixerObjectif,
				Delegation,AnimationEquipe,RespectQSE,ContributionNC,RespectRegles,PortTenues,
				PortEPI,RespectOutils,Assiduite,EspritEntreprise,TravailEquipe,Dispo,Autonomie,Initiative,
				Communication,OrganisationCharge,ComSOrganisationCharge,ComEOrganisationCharge,AmplitudeJournee,ComSAmplitudeJournee,ComEAmplitudeJournee,OrganisationTravail,
				ComSOrganisationTravail,ComEOrganisationTravail,ArticulationActiviteProPerso,ComSArticulationActiviteProPerso,ComEArticulationActiviteProPerso,Remuneration,ComSRemuneration,
				ComERemuneration,Stress,ComSStress,ComEStress,EntretienRH,EntretienMedecienTravail,EntretienLumanisy,EntretienSoutienPsycho,EntretienHSE,EntretienAutre,FormationOrganisationTravail,FormationStress,
				FormationSophrologie,FormationAutre,ComEntretienRH,ComEntretienMedecienTravail,ComEntretienLumanisy,ComEntretienSoutienPsycho,ComEntretienHSE,ComEntretienAutre,ComEEntretienAutre,
				ComFormationOrganisationTravail,ComFormationStress,ComFormationSophrologie,ComFormationAutre,ComEFormationAutre,CommentaireLibreS,CommentaireLibreE,
				PointFort,PointFaible,ObjectifProgression,ComSalarie,ComEvaluateur,DateEntretien
			FROM epe_personne 
			WHERE Suppr=0 
			AND Type='EPE'
			AND Id_Personne=".$row['Id_Personne']."
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
			AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') 
			ORDER BY Id DESC ";
		$resultEPERempli=mysqli_query($bdd,$req);
		$nbResultaEPERempli=mysqli_num_rows($resultEPERempli);
		if($nbResultaEPERempli>0){
			$rowEPERempli=mysqli_fetch_array($resultEPERempli);
			
			
			if($rowEPERempli['DateEntretien']>'0001-01-01'){
				$date = explode("-",$rowEPERempli['DateEntretien']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('J'.$ligne,$time);
				$sheet->getStyle('J'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			
			$nbItemEvalue=0;
			$nbTotalItemEvalue=0;
			$NoteMoyenne=0;
			
			//Focus Metier
			$NbNA=0;
			$Nb1=0;
			$Nb2=0;
			$Nb3=0;
			$Nb4=0;
			$total=0;
			$tab=array('ConnaissanceMetier','UtilisationDoc','Productivite','Organisation','CapaciteManager','RespectObjectif','AnglaisTech','CapaciteTuteur','Reporting','PlanAction','RespectBudget'); 
			for($i=0;$i<sizeof($tab);$i++){
				if($rowEPERempli[$tab[$i]]==-1){$NbNA++;}
				elseif($rowEPERempli[$tab[$i]]==1){$Nb1++;}
				elseif($rowEPERempli[$tab[$i]]==2){$Nb2++;}
				elseif($rowEPERempli[$tab[$i]]==3){$Nb3++;}
				elseif($rowEPERempli[$tab[$i]]==4){$Nb4++;}
			}
			$total=$Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4);
			$nbItemEvalue+=$Nb1+$Nb2+$Nb3+$Nb4;
			$nbTotalItemEvalue+=$total;
			
			$sheet->setCellValue('AU'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NbNA))));
			$sheet->setCellValue('AV'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
			$sheet->setCellValue('AW'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
			$sheet->setCellValue('AX'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
			$sheet->setCellValue('AY'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
			$sheet->setCellValue('AZ'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($total))));
			
			//Focus client
			$NbNA=0;
			$Nb1=0;
			$Nb2=0;
			$Nb3=0;
			$Nb4=0;
			$total=0;
			$tab=array('RepresentationEntreprise','SouciSatisfaction','Ecoute','TraitementInsatisfaction','ExplicationSolution','ComprehensionInsatisfaction'); 
			for($i=0;$i<sizeof($tab);$i++){
				if($rowEPERempli[$tab[$i]]==-1){$NbNA++;}
				elseif($rowEPERempli[$tab[$i]]==1){$Nb1++;}
				elseif($rowEPERempli[$tab[$i]]==2){$Nb2++;}
				elseif($rowEPERempli[$tab[$i]]==3){$Nb3++;}
				elseif($rowEPERempli[$tab[$i]]==4){$Nb4++;}
			}
			$total=$Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4);
			$nbItemEvalue+=$Nb1+$Nb2+$Nb3+$Nb4;
			$nbTotalItemEvalue+=$total;
			
			$sheet->setCellValue('BA'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NbNA))));
			$sheet->setCellValue('BB'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
			$sheet->setCellValue('BC'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
			$sheet->setCellValue('BD'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
			$sheet->setCellValue('BE'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
			$sheet->setCellValue('BF'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($total))));
			
			//Focus management
			$NbNA=0;
			$Nb1=0;
			$Nb2=0;
			$Nb3=0;
			$Nb4=0;
			$total=0;
			$tab=array('ConnaissanceManagement','ConnaissanceMetierEquipe','CapaciteFixerObjectif','Delegation','AnimationEquipe'); 
			for($i=0;$i<sizeof($tab);$i++){
				if($rowEPERempli[$tab[$i]]==-1){$NbNA++;}
				elseif($rowEPERempli[$tab[$i]]==1){$Nb1++;}
				elseif($rowEPERempli[$tab[$i]]==2){$Nb2++;}
				elseif($rowEPERempli[$tab[$i]]==3){$Nb3++;}
				elseif($rowEPERempli[$tab[$i]]==4){$Nb4++;}
			}
			$total=$Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4);
			$nbItemEvalue+=$Nb1+$Nb2+$Nb3+$Nb4;
			$nbTotalItemEvalue+=$total;
			
			$sheet->setCellValue('BG'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NbNA))));
			$sheet->setCellValue('BH'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
			$sheet->setCellValue('BI'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
			$sheet->setCellValue('BJ'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
			$sheet->setCellValue('BK'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
			$sheet->setCellValue('BL'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($total))));
			
			//Focus QSE
			$NbNA=0;
			$Nb1=0;
			$Nb2=0;
			$Nb3=0;
			$Nb4=0;
			$total=0;
			$tab=array('RespectQSE','ContributionNC','RespectRegles','PortTenues','PortEPI','RespectOutils'); 
			for($i=0;$i<sizeof($tab);$i++){
				if($rowEPERempli[$tab[$i]]==-1){$NbNA++;}
				elseif($rowEPERempli[$tab[$i]]==1){$Nb1++;}
				elseif($rowEPERempli[$tab[$i]]==2){$Nb2++;}
				elseif($rowEPERempli[$tab[$i]]==3){$Nb3++;}
				elseif($rowEPERempli[$tab[$i]]==4){$Nb4++;}
			}
			$total=$Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4);
			$nbItemEvalue+=$Nb1+$Nb2+$Nb3+$Nb4;
			$nbTotalItemEvalue+=$total;
			
			$sheet->setCellValue('BM'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NbNA))));
			$sheet->setCellValue('BN'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
			$sheet->setCellValue('BO'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
			$sheet->setCellValue('BP'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
			$sheet->setCellValue('BQ'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
			$sheet->setCellValue('BR'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($total))));
			
			//Comportement
			$NbNA=0;
			$Nb1=0;
			$Nb2=0;
			$Nb3=0;
			$Nb4=0;
			$total=0;
			$tab=array('Assiduite','EspritEntreprise','TravailEquipe','Dispo','Autonomie','Initiative','Communication');
			for($i=0;$i<sizeof($tab);$i++){
				if($rowEPERempli[$tab[$i]]==-1){$NbNA++;}
				elseif($rowEPERempli[$tab[$i]]==1){$Nb1++;}
				elseif($rowEPERempli[$tab[$i]]==2){$Nb2++;}
				elseif($rowEPERempli[$tab[$i]]==3){$Nb3++;}
				elseif($rowEPERempli[$tab[$i]]==4){$Nb4++;}
			}
			$total=$Nb1+($Nb2*2)+($Nb3*3)+($Nb4*4);
			$nbItemEvalue+=$Nb1+$Nb2+$Nb3+$Nb4;
			$nbTotalItemEvalue+=$total;
			
			$sheet->setCellValue('BS'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NbNA))));
			$sheet->setCellValue('BT'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb1))));
			$sheet->setCellValue('BU'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb2))));
			$sheet->setCellValue('BV'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb3))));
			$sheet->setCellValue('BW'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($Nb4))));
			$sheet->setCellValue('BX'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($total))));
			
			if($nbItemEvalue>0){
				$NoteMoyenne=round($nbTotalItemEvalue/$nbItemEvalue,2);
			}
			$sheet->setCellValue('BY'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($nbItemEvalue))));
			$sheet->setCellValue('BZ'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($nbTotalItemEvalue))));
			$sheet->setCellValue('CA'.$ligne,utf8_encode(stripslashes(unNombreSinonRien($NoteMoyenne))));
		}
		
		
		
		//Objectifs de l'année
		$requete="
		SELECT Objectif,Indicateur,MoyensAssocies,Commentaire
			FROM epe_personne_objectifannee
			LEFT JOIN epe_personne
			ON epe_personne_objectifannee.Id_epepersonne=epe_personne.Id
			WHERE epe_personne.Suppr=0 
			AND epe_personne_objectifannee.Suppr=0
			AND epe_personne.Type IN ('EPE') 
			AND Id_Personne=".$row['Id_Personne']."
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
			AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') ";
		$result2=mysqli_query($bdd,$requete);
		$nbResulta2=mysqli_num_rows($result2);
		
		if($nbResulta2>0){
			$lettre="CB";
			while($row2=mysqli_fetch_array($result2)){
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['Objectif'])));
				$lettre++;
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['Indicateur'])));
				$lettre++;
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['MoyensAssocies'])));
				$lettre++;
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
				$lettre++;
			}
		}
		
		if($nbResultaEPERempli>0){
			$lettre="DP";
			$tab=array('OrganisationCharge','ArticulationActiviteProPerso','AmplitudeJournee','OrganisationTravail','Remuneration');
			for($i=0;$i<sizeof($tab);$i++){
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['ComS'.$tab[$i]])));
				$lettre++;
				$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['ComE'.$tab[$i]])));
				$lettre++;
			}
			
			$lettre="DZ";
			$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['Stress'])));
			$lettre++;
			$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['ComSStress'])));
			$lettre++;
			$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['ComEStress'])));
			$lettre++;
			
			$BesoinStress="";
			if($rowEPERempli['EntretienRH']==1){$BesoinStress="Entretien RH";}
			if($rowEPERempli['EntretienMedecienTravail']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Entretien avec la médecine du travail";
			}
			if($rowEPERempli['EntretienLumanisy']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Entretien avec le service social du travail";
			}
			if($rowEPERempli['EntretienSoutienPsycho']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Soutien psychologique";
			}
			if($rowEPERempli['EntretienHSE']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Soutien psychologique";
			}
			if($rowEPERempli['EntretienAutre']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Entretien avec service HSE";
			}
			if($rowEPERempli['FormationOrganisationTravail']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Formation Organisation du travail, gestion du temps et des priorités";
			}
			if($rowEPERempli['FormationStress']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Formation Gestion du stress";
			}
			if($rowEPERempli['FormationAutre']==1){
				if($BesoinStress<>""){$BesoinStress=", ";}
				$BesoinStress="Formation Autre";
			}
			$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($BesoinStress)));
			$lettre++;
			$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['CommentaireLibreS'])));
			$lettre++;
			$sheet->setCellValue($lettre.$ligne,utf8_encode(stripslashes($rowEPERempli['CommentaireLibreE'])));
			$lettre++;
			
			$sheet->setCellValue('EF'.$ligne,utf8_encode(stripslashes($rowEPERempli['PointFort'])));
			$sheet->setCellValue('EG'.$ligne,utf8_encode(stripslashes($rowEPERempli['PointFaible'])));
			$sheet->setCellValue('EH'.$ligne,utf8_encode(stripslashes($rowEPERempli['ObjectifProgression'])));
			$sheet->setCellValue('EI'.$ligne,utf8_encode(stripslashes($rowEPERempli['ComSalarie'])));
			$sheet->setCellValue('EJ'.$ligne,utf8_encode(stripslashes($rowEPERempli['ComEvaluateur'])));
		}
		
		
		//EPP 
		$req="SELECT 
				Id,SouhaitEvolutionON,SouhaitEvolution,SouhaitMobiliteON,SouhaitMobilite,FormationEvolutionON,FormationEvolution,ComEvaluateurEPP,DateEntretien,
				PasEvolutionEPP,PasMobiliteEPP
			FROM epe_personne 
			WHERE Suppr=0 
			AND Type='EPP'
			AND Id_Personne=".$row['Id_Personne']."
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
			AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') 
			ORDER BY Id DESC ";
		$resultEPPRempli=mysqli_query($bdd,$req);
		$nbResultaEPPRempli=mysqli_num_rows($resultEPPRempli);
		if($nbResultaEPPRempli>0){
			$rowEPPRempli=mysqli_fetch_array($resultEPPRempli);
			
			if($rowEPPRempli['DateEntretien']>'0001-01-01'){
				$date = explode("-",$rowEPPRempli['DateEntretien']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('EK'.$ligne,$time);
				$sheet->getStyle('EK'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			
			$souhaitEvolution="";
			if($rowEPPRempli['SouhaitEvolutionON']==1 && $rowEPPRempli['PasEvolutionEPP']==0){
				$req="SELECT DISTINCT (SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution) AS Souhait FROM epe_personne_souhaitevolution2 WHERE Id_EPE=".$rowEPPRempli['Id']." ";
				$result2=mysqli_query($bdd,$req);
				$nbResulta2=mysqli_num_rows($result2);
				
				if($nbResulta2>0){
					while($row2=mysqli_fetch_array($result2)){
						if($souhaitEvolution<>""){$souhaitEvolution.=", ";}
						$souhaitEvolution.=$row2['Souhait'];
					}
				}
				
				$req="SELECT DISTINCT (SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution) AS Souhait FROM epe_personne_souhaitevolution WHERE Id_EPE=".$rowEPPRempli['Id']." ";
				$result2=mysqli_query($bdd,$req);
				$nbResulta2=mysqli_num_rows($result2);
				
				if($nbResulta2>0){
					while($row2=mysqli_fetch_array($result2)){
						if($souhaitEvolution<>""){$souhaitEvolution.=", ";}
						$souhaitEvolution.=$row2['Souhait'];
					}
				}
			}
			$sheet->setCellValue('EL'.$ligne,utf8_encode(stripslashes($souhaitEvolution)));
			
			$souhaitMobilite="";
			if($rowEPPRempli['SouhaitMobiliteON']==1 && $rowEPPRempli['PasMobiliteEPP']==0){
				$req="SELECT DISTINCT (SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Souhait FROM epe_personne_souhaitmobilite2 WHERE Id_EPE=".$rowEPPRempli['Id']." ";
				$result2=mysqli_query($bdd,$req);
				$nbResulta2=mysqli_num_rows($result2);
				
				if($nbResulta2>0){
					while($row2=mysqli_fetch_array($result2)){
						if($souhaitMobilite<>""){$souhaitMobilite.=", ";}
						$souhaitMobilite.=$row2['Souhait'];
					}
				}
				
				$req="SELECT DISTINCT (SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Souhait FROM epe_personne_souhaitmobilite WHERE Id_EPE=".$rowEPPRempli['Id']." ";
				$result2=mysqli_query($bdd,$req);
				$nbResulta2=mysqli_num_rows($result2);
				
				if($nbResulta2>0){
					while($row2=mysqli_fetch_array($result2)){
						if($souhaitMobilite<>""){$souhaitMobilite.=", ";}
						$souhaitMobilite.=$row2['Souhait'];
					}
				}
			}
			$sheet->setCellValue('EM'.$ligne,utf8_encode(stripslashes($souhaitMobilite)));
			
			if($rowEPPRempli['FormationEvolutionON']==1){
				$sheet->setCellValue('EN'.$ligne,utf8_encode(stripslashes($rowEPPRempli['FormationEvolution'])));
			}
			$sheet->setCellValue('EO'.$ligne,utf8_encode(stripslashes($rowEPPRempli['ComEvaluateurEPP'])));
		
		}
		
		//EPP Bilan
		$req="SELECT 
				DateEntretien
			FROM epe_personne 
			WHERE Suppr=0 
			AND Type='EPP Bilan'
			AND Id_Personne=".$row['Id_Personne']."
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
			AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') 
			ORDER BY Id DESC ";
		$resultEPPBRempli=mysqli_query($bdd,$req);
		$nbResultaEPPBRempli=mysqli_num_rows($resultEPPBRempli);
		if($nbResultaEPPBRempli>0){
			$rowEPPBRempli=mysqli_fetch_array($resultEPPBRempli);
			if($rowEPPBRempli['DateEntretien']>'0001-01-01'){
				$date = explode("-",$rowEPPBRempli['DateEntretien']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('EP'.$ligne,$time);
				$sheet->getStyle('EP'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
		}
		
		$sheet->getStyle('A'.$ligne.':EP'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':EP'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':EP'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	
		$sheet->getStyle('K'.$ligne.':M'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('Q'.$ligne.':S'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('W'.$ligne.':Y'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('AC'.$ligne.':AE'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('AI'.$ligne.':AK'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		
		$sheet->getStyle('AO'.$ligne.':AT'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('BA'.$ligne.':BF'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('BM'.$ligne.':BR'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('BY'.$ligne.':CA'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('CF'.$ligne.':CI'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('CN'.$ligne.':CQ'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('CV'.$ligne.':CY'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('DD'.$ligne.':DG'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('DL'.$ligne.':DO'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		
		$sheet->getStyle('DR'.$ligne.':DS'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('DT'.$ligne.':DU'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('DX'.$ligne.':DY'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		
		$sheet->getStyle('EF'.$ligne.':EH'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
		$sheet->getStyle('EK'.$ligne.':EO'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ddebf7'))));
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>