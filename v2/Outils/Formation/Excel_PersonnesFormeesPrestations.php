<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require("../PlanningV2/Fonctions_Planning.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

$semaine = date('W', strtotime(date('Y-m-d')." + 0 month"));
if(date("N")==1){
	$lundi=date("Y-m-d");
}
else{
	$lundi=date("Y-m-d",strtotime("last Monday"));
}
$dimanche=date("Y-m-d",strtotime($lundi." +6 day"));

$qualification=$_GET['qualification'];
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setCellValue('A1',utf8_encode("Prestation"));
	$sheet->setCellValue('B1',utf8_encode("S".$semaine));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Site"));
	$sheet->setCellValue('B1',utf8_encode("S".$semaine));
}
$sheet->setCellValue('B2',utf8_encode("J"));
$sheet->setCellValue('C2',utf8_encode("S"));
$sheet->setCellValue('D2',utf8_encode("N"));
$sheet->setCellValue('E2',utf8_encode("VSD"));
$sheet->setCellValue('F2',utf8_encode("ABS"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(30);

$sheet->getStyle('A1:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:F2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:F2')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:F2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'90cbe4'))));

$sheet->mergeCells('A1:A2');
$sheet->mergeCells('B1:F1');



if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
	$rqPrestation="SELECT Id AS Id_Prestation, 
		Libelle,
		0 AS Id_Pole,
		'' AS Pole
		FROM new_competences_prestation 
		WHERE Id NOT IN (
			SELECT Id_Prestation
			FROM new_competences_pole
			WHERE Actif=0
		)
		AND new_competences_prestation.Active=0
		AND Id_Plateforme IN (
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
		)
		
		UNION
		
		SELECT Id_Prestation,
		new_competences_prestation.Libelle,
		new_competences_pole.Id AS Id_Pole,
		CONCAT(' - ',new_competences_pole.Libelle) AS Pole
		FROM new_competences_pole
		INNER JOIN new_competences_prestation
		ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
		AND new_competences_pole.Actif=0
		AND new_competences_prestation.Active=0
		AND new_competences_prestation.Id_Plateforme IN (
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
		)
		ORDER BY Libelle, Pole";
}
else{
	$rqPrestation="SELECT Id AS Id_Prestation, 
		Id_Plateforme,
		Libelle,
		0 AS Id_Pole,
		'' AS Pole
		FROM new_competences_prestation 
		WHERE Id NOT IN (
			SELECT Id_Prestation
			FROM new_competences_pole 
			WHERE Actif=0   
		)
		AND (SELECT COUNT(Id)
			FROM new_competences_personne_poste_prestation
			WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
			AND Id_Personne=".$IdPersonneConnectee." 
			AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0
		AND new_competences_prestation.Active=0
		AND Active=0
		
		UNION
		
		SELECT Id_Prestation,
		new_competences_prestation.Id_Plateforme,
		new_competences_prestation.Libelle,
		new_competences_pole.Id AS Id_Pole,
		CONCAT(' - ',new_competences_pole.Libelle) AS Pole
		FROM new_competences_pole
		INNER JOIN new_competences_prestation
		ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
		WHERE (SELECT COUNT(Id)
			FROM new_competences_personne_poste_prestation
			WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
			AND Id_Personne=".$IdPersonneConnectee." 
			AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
			AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id)>0
		AND new_competences_pole.Actif=0
		AND new_competences_prestation.Active=0
		AND Active=0
		AND Actif=0
		ORDER BY Libelle, Pole";
}
$resultPrestation=mysqli_query($bdd,$rqPrestation);
$NbPresta=mysqli_num_rows($resultPrestation);

$couleur="bgcolor='#ffffff'";
if($NbPresta>0)
{
	$ligne=3;
	while($row=mysqli_fetch_array($resultPrestation))
	{
		
		
		$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
		CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.DateDebut<='".$dimanche."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
		AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
		";
		$resultPersonneTotal=mysqli_query($bdd,$reqPersonne);
		$NbPersonneTotal=mysqli_num_rows($resultPersonneTotal);
		
		$nbJTotal=0;
		$nbSTotal=0;
		$nbNTotal=0;
		$nbVSDTotal=0;
		
		if($NbPersonneTotal>0){
			while($rowPers=mysqli_fetch_array($resultPersonneTotal))
			{
				$leLJour=$lundi;
				$nbJ=0;
				$nbS=0;
				$nbN=0;
				$nbVSD=0;
				$nbAbs=0;
				while($leLJour<=$dimanche){
					$Couleur=TravailCeJourDeSemaine($leLJour,$rowPers['Id']);
					if ($Couleur <> ""){
						$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
						if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
							$nbJTotal++;
						}
						elseif($vacation==2 || $vacation==10){
							$nbSTotal++;
						}
						elseif($vacation==4){
							$nbNTotal++;
						}
						elseif($vacation==3){
							$nbVSDTotal++;
						}
					}
					$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
				}
			}
		}
		
		$styleJ="";
		$styleS="";
		$styleN="";
		$styleVSD="";
		
		//PERSONNES FORMEES
		$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
		CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.DateDebut<='".$dimanche."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
		AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
		AND ";
		if($qualification<>""){
			$reqPersonne.="new_rh_etatcivil.Id IN (
				SELECT Id_Personne
				FROM new_competences_relation 
				WHERE Id_Qualification_Parrainage IN (".$qualification.")
				AND new_competences_relation.Date_Debut<='".$dimanche."'
				AND (new_competences_relation.Date_Fin>='".$lundi."' OR (new_competences_relation.Date_Fin<='0001-01-01' AND Sans_Fin='Oui') )
				AND Evaluation IN ('L','Q','S','T','V','X')
				AND Suppr=0)
				";
		}
		else{
			$reqPersonne.="new_rh_etatcivil.Id IN
					(SELECT
						form_besoin.Id_Personne
					FROM
						form_besoin
					WHERE
						form_besoin.Suppr=0
						AND form_besoin.Valide=1
						AND form_besoin.Traite=4
						AND form_besoin.Id IN
						(
						SELECT
							Id_Besoin
						FROM
							form_session_personne
						WHERE
							form_session_personne.Id NOT IN 
								(
								SELECT
									Id_Session_Personne
								FROM
									form_session_personne_qualification
								WHERE
									Suppr=0	
								)
							AND Suppr=0
							AND form_session_personne.Validation_Inscription=1
							AND form_session_personne.Presence=1
						)
					)
				";
		}
		$resultPersonne=mysqli_query($bdd,$reqPersonne);
		$NbPersonne=mysqli_num_rows($resultPersonne);
		
		$PersonneJ="";
		$PersonneS="";
		$PersonneN="";
		$PersonneVSD="";
		$PersonneABS="";

		if($NbPersonne>0){
			while($rowPers=mysqli_fetch_array($resultPersonne))
			{
				
				$leLJour=$lundi;
				$nbJ=0;
				$nbS=0;
				$nbN=0;
				$nbVSD=0;
				$nbAbs=0;
				while($leLJour<=$dimanche){
					$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
					if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
						$nbJ++;
					}
					elseif($vacation==2 || $vacation==10){
						$nbS++;
					}
					elseif($vacation==4){
						$nbN++;
					}
					elseif($vacation==3){
						$nbVSD++;
					}
					elseif($vacation==0){
						$nbAbs++;
					}
					$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
				}
				if($nbJ>0){
					if($PersonneJ<>""){$PersonneJ.="\n";}
					$PersonneJ.=stripslashes($rowPers['Personne']);
				}
				if($nbS>0){
					if($PersonneS<>""){$PersonneS.="\n";}
					$PersonneS.=stripslashes($rowPers['Personne']);
				}
				if($nbN>0){
					if($PersonneN<>""){$PersonneN.="\n";}
					$PersonneN.=stripslashes($rowPers['Personne']);
				}
				if($nbVSD>0){
					if($PersonneVSD<>""){$PersonneVSD.="\n";}
					$PersonneVSD.=stripslashes($rowPers['Personne']);
				}
				if($nbAbs==7){
					if($PersonneABS<>""){$PersonneABS.="\n";}
					$PersonneABS.=stripslashes($rowPers['Personne']);
				}
			}
		}
		
		//PERSONNES INSCRITES
		$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
		CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.DateDebut<='".$dimanche."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
		AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
		AND
		";
		if($qualification<>""){
			$reqPersonne.="new_rh_etatcivil.Id IN (
				SELECT Id_Personne
				FROM new_competences_relation 
				WHERE Id_Qualification_Parrainage IN (".$qualification.")
				AND Evaluation IN ('Bi')
				AND Suppr=0)
				";
		}
		else{
			$reqPersonne.="new_rh_etatcivil.Id IN
					(SELECT
						form_besoin.Id_Personne
					FROM
						form_besoin
					WHERE
						form_besoin.Suppr=0
						AND form_besoin.Valide=1
						AND form_besoin.Traite IN (1,2)
						AND form_besoin.Id IN
						(
						SELECT
							Id_Besoin
						FROM
							form_session_personne
						WHERE
							form_session_personne.Id NOT IN 
								(
								SELECT
									Id_Session_Personne
								FROM
									form_session_personne_qualification
								WHERE
									Suppr=0	
								)
							AND Suppr=0
						)
					)
			";
		}
		
		$resultPersonne=mysqli_query($bdd,$reqPersonne);
		$NbPersonne=mysqli_num_rows($resultPersonne);
		
		$PersonneJBi="";
		$PersonneSBi="";
		$PersonneNBi="";
		$PersonneVSDBi="";
		$PersonneABSBi="";

		if($NbPersonne>0){
			while($rowPers=mysqli_fetch_array($resultPersonne))
			{
				
				$leLJour=$lundi;
				$nbJBi=0;
				$nbSBi=0;
				$nbNBi=0;
				$nbVSDBi=0;
				$nbAbsBi=0;
				while($leLJour<=$dimanche){
					$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
					if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
						$nbJBi++;
					}
					elseif($vacation==2 || $vacation==10){
						$nbSBi++;
					}
					elseif($vacation==4){
						$nbNBi++;
					}
					elseif($vacation==3){
						$nbVSDBi++;
					}
					elseif($vacation==0){
						$nbAbsBi++;
					}
					$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
				}
				if($nbJ>0){
					if($PersonneJBi<>""){$PersonneJBi.="\n";}
					$PersonneJBi.=stripslashes($rowPers['Personne']);
				}
				if($nbS>0){
					if($PersonneSBi<>""){$PersonneSBi.="\n";}
					$PersonneSBi.=stripslashes($rowPers['Personne']);
				}
				if($nbN>0){
					if($PersonneNBi<>""){$PersonneNBi.="\n";}
					$PersonneNBi.=stripslashes($rowPers['Personne']);
				}
				if($nbVSD>0){
					if($PersonneVSDBi<>""){$PersonneVSDBi.="\n";}
					$PersonneVSDBi.=stripslashes($rowPers['Personne']);
				}
				if($nbAbs==7){
					if($PersonneABSBi<>""){$PersonneABSBi.="\n";}
					$PersonneABSBi.=stripslashes($rowPers['Personne']);
				}
			}
		}
		
		//PERSONNES AVEC UN B
		$reqPersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
		CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.DateDebut<='".$dimanche."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$lundi."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Id_Prestation=".$row['Id_Prestation']."
		AND rh_personne_mouvement.Id_Pole=".$row['Id_Pole']." 
		AND 
		";
		
		if($qualification<>""){
			$reqPersonne.="new_rh_etatcivil.Id IN (
				SELECT Id_Personne
				FROM new_competences_relation 
				WHERE Id_Qualification_Parrainage IN (".$qualification.")
				AND Evaluation IN ('B')
				AND Suppr=0)
				";
		}
		else{
			$reqPersonne.="new_rh_etatcivil.Id IN
					(SELECT
						form_besoin.Id_Personne
					FROM
						form_besoin
					WHERE
						form_besoin.Suppr=0
						AND form_besoin.Valide=1
						AND form_besoin.Traite IN (0)
						AND form_besoin.Id IN
						(
						SELECT
							Id_Besoin
						FROM
							form_session_personne
						WHERE
							form_session_personne.Id NOT IN 
								(
								SELECT
									Id_Session_Personne
								FROM
									form_session_personne_qualification
								WHERE
									Suppr=0	
								)
							AND Suppr=0
						)
					)
			";
		}
		
		$resultPersonne=mysqli_query($bdd,$reqPersonne);
		$NbPersonne=mysqli_num_rows($resultPersonne);
		
		$PersonneJB="";
		$PersonneSB="";
		$PersonneNB="";
		$PersonneVSDB="";
		$PersonneABSB="";

		if($NbPersonne>0){
			while($rowPers=mysqli_fetch_array($resultPersonne))
			{
				
				$leLJour=$lundi;
				$nbJB=0;
				$nbSB=0;
				$nbNB=0;
				$nbVSDB=0;
				$nbAbsB=0;
				while($leLJour<=$dimanche){
					$vacation=EstEnVacationCeJour($rowPers['Id'],$leLJour);
					if($vacation==1 || $vacation==5 || $vacation==8 || $vacation==9 || $vacation==11){
						$nbJB++;
					}
					elseif($vacation==2 || $vacation==10){
						$nbSB++;
					}
					elseif($vacation==4){
						$nbNB++;
					}
					elseif($vacation==3){
						$nbVSDB++;
					}
					elseif($vacation==0){
						$nbAbsB++;
					}
					$leLJour=date("Y-m-d",strtotime($leLJour." +1 day"));
				}
				if($nbJ>0){
					if($PersonneJB<>""){$PersonneJB.="\n";}
					$PersonneJB.=stripslashes($rowPers['Personne']);
				}
				if($nbS>0){
					if($PersonneSB<>""){$PersonneSB.="\n";}
					$PersonneSB.=stripslashes($rowPers['Personne']);
				}
				if($nbN>0){
					if($PersonneNB<>""){$PersonneNB.="\n";}
					$PersonneNB.=stripslashes($rowPers['Personne']);
				}
				if($nbVSD>0){
					if($PersonneVSDB<>""){$PersonneVSDB.="\n";}
					$PersonneVSDB.=stripslashes($rowPers['Personne']);
				}
				if($nbAbs==7){
					if($PersonneABSB<>""){$PersonneABSB.="\n";}
					$PersonneABSB.=stripslashes($rowPers['Personne']);
				}
			}
		}
		
		//RESULTAT
		if($NbPersonneTotal>0){
			
			if($couleur=="bgcolor='#ffffff'"){$couleur="bgcolor='#e6e6e6'";$laCouleur="#e6e6e6";}
			else{$couleur="bgcolor='#ffffff'";$laCouleur="#ffffff";}
			
			if($nbJTotal==0){
			$styleJ="style='background-color:#2b8bb4;'";
		}
		if($nbSTotal==0){
			$styleS="style='background-color:#2b8bb4;'";
		}
		if($nbNTotal==0){
			$styleN="style='background-color:#2b8bb4;'";
		}
		if($nbVSDTotal==0){
			$styleVSD="style='background-color:#2b8bb4;'";
		}
		
		if($PersonneJ<>"" && $PersonneJBi<>""){$PersonneJBi="\n".$PersonneJBi;}
		if($PersonneS<>"" && $PersonneSBi<>""){$PersonneSBi="\n".$PersonneSBi;}
		if($PersonneN<>"" && $PersonneNBi<>""){$PersonneNBi="\n".$PersonneNBi;}
		if($PersonneVSD<>"" && $PersonneVSDBi<>""){$PersonneVSDBi="\n".$PersonneVSDBi;}
		
		if($PersonneJ<>"" && $PersonneJB<>""){$PersonneJB="\n".$PersonneJB;}
		if($PersonneS<>"" && $PersonneSB<>""){$PersonneSB="\n".$PersonneSB;}
		if($PersonneN<>"" && $PersonneNB<>""){$PersonneNB="\n".$PersonneNB;}
		if($PersonneVSD<>"" && $PersonneVSDB<>""){$PersonneVSDB="\n".$PersonneVSDB;}
		/*
	?>
		<tr <?php echo $couleur; ?>>
			<td><?php echo stripslashes(substr($row['Libelle'],0,7))." ".stripslashes($row['Pole']); ?></td>
			<td <?php echo $styleJ; ?>><?php echo $PersonneJ."<span style='color:#10aa4e'>".$PersonneJBi.'</span>'."<span style='color:#0151b9'>".$PersonneJB.'</span>'; ?></td>
			<td <?php echo $styleS; ?>><?php echo $PersonneS."<span style='color:#10aa4e'>".$PersonneSBi.'</span>'."<span style='color:#0151b9'>".$PersonneSB.'</span>'; ?></td>
			<td <?php echo $styleN; ?>><?php echo $PersonneN."<span style='color:#10aa4e'>".$PersonneNBi.'</span>'."<span style='color:#0151b9'>".$PersonneNB.'</span>'; ?></td>
			<td <?php echo $styleVSD; ?>><?php echo $PersonneVSD."<span style='color:#10aa4e'>".$PersonneVSDBi.'</span>'."<span style='color:#0151b9'>".$PersonneVSDB.'</span>'; ?></td>
			<td><?php echo $PersonneABS; ?></td>
		</tr>
	<?php
	*/
			$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes(substr($row['Libelle'],0,7))." ".stripslashes($row['Pole'])));
			$sheet->setCellValue('B'.$ligne,utf8_encode($PersonneJ));
			$sheet->setCellValue('C'.$ligne,utf8_encode($PersonneS));
			$sheet->setCellValue('D'.$ligne,utf8_encode($PersonneN));
			$sheet->setCellValue('E'.$ligne,utf8_encode($PersonneVSD));
			$sheet->setCellValue('F'.$ligne,utf8_encode($PersonneABS));
			
			$sheet->getStyle('B'.$ligne.':F'.($ligne))->getAlignment()->setWrapText(true);
		
			$sheet->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':F'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
			if($styleJ<>""){
				$sheet->getStyle('B'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'90cbe4'))));
			}
			if($styleS<>""){
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'90cbe4'))));
			}
			if($styleN<>""){
				$sheet->getStyle('D'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'90cbe4'))));
			}
			if($styleVSD<>""){
				$sheet->getStyle('E'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'90cbe4'))));
			}
			$ligne++;
		}
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>