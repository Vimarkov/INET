<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");
require_once("../Fonctions.php");

$gris="#e4eae8";
$violet="#cd8be9";
$jaune="#f2ff43";
$bleu="#52a5f0";
$vert="#5cec4e";

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$sheet->setTitle(utf8_encode('PLANNING'));

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../../Images/Logos/Logo_AAA_FR.png');
$objDrawing->setHeight(120);
$objDrawing->setWidth(190);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(20);
$objDrawing->setOffsetY(13);
$objDrawing->setWorksheet($sheet);

$tmpDate = TrsfDate_($_GET['date']);
$dateFin=TrsfDate_($_GET['dateFin']);
if($LangueAffichage=="FR"){
	$MoisLettre = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
}
else{
	$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
}
$sheet->mergeCells('A1:D1');
$sheet->mergeCells('A4:D4');
$sheet->mergeCells('A3:BE3');
$sheet->mergeCells('E1:BE1');
$sheet->mergeCells('A2:BE2');
$sheet->getRowDimension(1)->setRowHeight(80);
$sheet->getRowDimension(2)->setRowHeight(20);
$sheet->getRowDimension(3)->setRowHeight(35);

$sheet->getColumnDimension('A')->setWidth(6);
$sheet->getColumnDimension('B')->setWidth(6);
$sheet->getColumnDimension('C')->setWidth(6);
$sheet->getColumnDimension('D')->setWidth(20);

$sheet->getStyle('A:C')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A:C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$titre= date('d', strtotime($tmpDate." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($tmpDate." + 0 month")))-1]." ".date('Y', strtotime($tmpDate." + 0 month"));
$titre.= " - ";
$titre.= date('d', strtotime($dateFin." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($dateFin." + 0 month")))-1]." ".date('Y', strtotime($dateFin." + 0 month"));

if($LangueAffichage=="FR"){$sheet->setCellValue('E1',utf8_encode("PLANNING DES FORMATIONS"));}
else{$sheet->setCellValue('E1',utf8_encode("TRAINING PLANNING"));}
$sheet->getStyle('E1')->getFont()->setSize(32);

$sheet->getStyle('A3')->getFont()->setName('Arial');
$sheet->setCellValue('A3',utf8_encode($titre));
$sheet->getStyle('A3')->getFont()->setSize(24);
$sheet->getStyle('A1:BE4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:BE4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$heure=5;
$min=0;
$col="E";
for($i=1;$i<=61;$i++){
	if($min==0){$minAffiche="";}
	else{$minAffiche=$min;}
	$sheet->setCellValue($col.'4',utf8_encode($heure."h ".$minAffiche));
	$sheet->getColumnDimension($col)->setWidth(5);
	$sheet->getStyle($col.'4')->getAlignment()->setWrapText(true);
	if($min==0){$min=15;}
	elseif($min==15){$min=30;}
	elseif($min==30){$min=45;}
	else{$min=0;$heure++;}
	$col++;
}

$tmpDate = TrsfDate_($_GET['date']);
$dateFin=TrsfDate_($_GET['dateFin']);

$tmpMois = date('n', strtotime($tmpDate." + 0 month")) . ' ' . date('Y', strtotime($tmpDate." + 0 month"));
$joursem = array("Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam");

//Requete sessions de la période
$req="SELECT form_session.Id, form_session.Id_Formation,form_session_date.Id AS Id_SessionDate, ";
$req.="form_session_date.DateSession,form_session_date.Heure_Debut,form_session_date.Heure_Fin, ";
$req.="form_session.Recyclage,form_session_date.PauseRepas,form_session_date.HeureDebutPause,form_session_date.HeureFinPause, ";
$req.="(SELECT COUNT(Id) FROM form_session_date WHERE form_session_date.Id_Session=form_session.Id) AS Nb, ";
$req.="(SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,form_session.Id_GroupeSession,form_session.Formation_Liee, ";
$req.="(SELECT (SELECT Libelle FROM form_groupe_formation WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation) FROM form_session_groupe WHERE form_session_groupe.Id=form_session.Id_GroupeSession) AS Groupe, ";
$req.="form_session.Nb_Stagiaire_Maxi,form_session.Nb_Stagiaire_Mini ";
$req.="FROM form_session_date LEFT JOIN form_session ";
$req.="ON form_session_date.Id_Session = form_session.Id ";
$req.="WHERE form_session_date.Suppr=0 AND form_session.Suppr=0 AND form_session.Id_Plateforme=".$_GET['Id_Plateforme']." AND form_session_date.DateSession>='".$tmpDate."' AND form_session_date.DateSession<='".$dateFin."' ";
$req.="AND form_session.Diffusion_Creneau=1 ";
//Liste des sessions créés pour sa prestation
$req.="AND (
	form_session.Id IN (
		SELECT Id_Session 
		FROM form_session_prestation 
		WHERE Suppr=0 
		AND Id_Prestation IN (
			SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")  
			AND Id_Personne=".$IdPersonneConnectee."
			)
		) ";
	//Liste des sessions contenants du personnel de ses prestations
	$req.=" OR form_session.Id IN (
				SELECT Id_Session 
				FROM form_session_personne 
				WHERE form_session_personne.Id_Personne IN (";
					//Liste de son personnel présent et futur
					$req.="SELECT Id_Personne 
						FROM new_competences_personne_prestation 
						WHERE Date_Fin>='".date('Y-m-d')."' 
						AND Id_Prestation IN (
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
							AND Id_Personne=".$IdPersonneConnectee."
							)
					)
			)
	) ";

$req.=" AND form_session.Annule=0 ";
$req.="ORDER BY form_session_date.DateSession, Heure_Fin";
$resultSessions=mysqli_query($bdd,$req);
$resultSessions2=mysqli_query($bdd,$req);
$nbSession=mysqli_num_rows($resultSessions);	

$requeteInfos="SELECT Id,Id_Formation,Id_Langue,Libelle,LibelleRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ";
$resultInfos=mysqli_query($bdd,$requeteInfos);
$nbInfos=mysqli_num_rows($resultInfos);

$requeteParam="SELECT Id,Id_Formation,Id_Langue FROM form_formation_plateforme_parametres WHERE Suppr=0 AND Id_Plateforme=".$_GET['Id_Plateforme']." ";
$resultParam=mysqli_query($bdd,$requeteParam);
$nbParam=mysqli_num_rows($resultParam);

$semaineEC=0;
$tabSessionDate=array();
$itab2=0;
$laLigne=5;
$debutSemaine=5;
while ($tmpDate <= $dateFin) {
	$leJour = date('d', strtotime($tmpDate." + 0 month"));
	$jour = date('w', strtotime($tmpDate." + 0 month"));
	$mois = date('m', strtotime($tmpDate." + 0 month"));
	$semaine = date('W', strtotime($tmpDate." + 0 month"));

	$nbSansHeures=0;
	$nbFormationAvecHeures=0;
	$bTrouve=0;
	$test="";
	$tabForm=array();
	$itab=0;
	$nbLigne=0;
	$nbSansHeures=0;
	$bTrouve=0;
	
	//CALCUL NEW 
	$taille=0;
	if($nbSession>0)
	{
		mysqli_data_seek($resultSessions,0);
		while($rowSession=mysqli_fetch_array($resultSessions))
		{
			if($rowSession['DateSession']==$tmpDate)
			{
				$taille++;
			}
		}
	}
	$tabResult[]= array();
	$nb=0;
	$iResultat=0;
	if($taille>0){
		while($nb+$nbSansHeures<$taille){
			$heure=5;
			$min=0;
			$nbLigne++;
			for($i=1;$i<=61;$i++){
				$heureFin="00:00:00";
				$trouve=0;
				if($nbSession>0){
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions)){
						if($rowSession['DateSession']==$tmpDate){
							if($trouve==0){
								if($rowSession['Heure_Debut']==0){
									$bExiste=0;
									for($k=0;$k<=(sizeof($tabResult)-1);$k++)
									{
										if($tabResult[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
									}
									if($bExiste==0){
										$tabResult[$iResultat]=$rowSession['Id_SessionDate'];$iResultat++;
										$trouve=1;
										$heureFin="00:00:00";
										$nbSansHeures++;
										$i=54;
									}
								}
								else{
									if($rowSession['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){
										$bExiste=0;
										for($k=0;$k<=(sizeof($tabResult)-1);$k++)
										{
											if($tabResult[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
										}
										if($bExiste==0){
											$tabResult[$iResultat]=$rowSession['Id_SessionDate'];$iResultat++;
											$heureFin=$rowSession['Heure_Fin'];
											$trouve=1;
											$nb++;
											$h1=strtotime($rowSession['Heure_Fin']);
											$h2=strtotime($rowSession['Heure_Debut']);
											$val=intval(substr(gmdate("H:i",$h1-$h2),0,2))*4;
											if(substr(gmdate("H:i",$h1-$h2),3,2)=="15"){$val++;}
											elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="30"){$val=$val+2;}
											elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="45"){$val=$val+3;}
											if($val>1)
											{
												$i+=($val-1);
											}
										}
									}
								}
							}
						}
					}
				}
				
				if($min==0){$min=15;}
				elseif($min==15){$min=30;}
				elseif($min==30){$min=45;}
				else{$min=0;$heure++;}
				if($heureFin<>"00:00:00")
				{
					$heure=intval(substr($heureFin,0,2));
					$min=intval(substr($heureFin,3,2));
				}
			}
		}
		$nbLigne=$nbLigne-$nbSansHeures;
	}
	
	if($laLigne<($laLigne+$nbLigne-1)){
		$sheet->mergeCells('B'.$laLigne.':B'.($laLigne+$nbLigne-1+$nbSansHeures));
		$sheet->mergeCells('C'.$laLigne.':C'.($laLigne+$nbLigne-1+$nbSansHeures));
		$sheet->mergeCells('D'.$laLigne.':D'.($laLigne+$nbLigne-1+$nbSansHeures));
	}
	elseif($laLigne<($laLigne+$nbSansHeures-1)){
		$sheet->mergeCells('B'.$laLigne.':B'.($laLigne+$nbSansHeures-1));
		$sheet->mergeCells('C'.$laLigne.':C'.($laLigne+$nbSansHeures-1));
		$sheet->mergeCells('D'.$laLigne.':D'.($laLigne+$nbSansHeures-1));
	}
	$rowspanSemaine="";
	if($semaineEC==0 || $semaine<>$semaineEC){
		if($jour<>0){$rowspanSemaine=8-$jour;}
		//else{$sheet->mergeCells('A'..':A'.($laLigne-1));}
		if($LangueAffichage=="FR"){
			$sheet->setCellValue('A'.$laLigne,utf8_encode("S".$semaine));
		}
		else{
			$sheet->setCellValue('A'.$laLigne,utf8_encode("W".$semaine));
		}
	}
	$spanabs="";
	$FormateursAbsents="";
	$sheet->setCellValue('B'.$laLigne,utf8_encode($joursem[$jour]));
	$sheet->setCellValue('C'.$laLigne,utf8_encode($leJour));
	$sheet->setCellValue('D'.$laLigne,utf8_encode($spanabs));
	if($nbSansHeures==0 && $nbLigne==0){
		$heure=5;
		$min=0;
		$col="E";
		for($i=1;$i<=61;$i++){
			$colspan="";
			$couleur="ffffff";
			$heureFin="00:00:00";
			$trouve=0;
			if($i>=29 && $i<=33){$couleur="d6ecf2";}
			if($min==0){$min=15;}
			elseif($min==15){$min=30;}
			elseif($min==30){$min=45;}
			else{$min=0;$heure++;}
			if($couleur<>"ffffff"){
				$sheet->getStyle($col.$laLigne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>str_replace("#","",$couleur)))));
			}
			$col++;
		}
	}
	if($nbLigne>0){
		for($j=1;$j<=$nbLigne;$j++){
			$heure=5;
			$min=0;
			$col="E";
			for($i=1;$i<=61;$i++){
				$colspan="";
				$couleur="ffffff";
				$heureFin="00:00:00";
				$trouve=0;
				$formation="";
				$onclick="";
				$val=1;
				$id="";
				$couleurSession="";
				if($i>=29 && $i<=33){$couleur="d6ecf2";}
				$couleurFormateur="eff7ff";
				if($nbSession>0){
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions)){
						if($rowSession['DateSession']==$tmpDate){
							if($rowSession['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00" && $trouve==0){
								$bExiste=0;
								$couleurSession=$gris;
									//Vérifier si la formation n'a pas déjà commencée
									if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']==1){
										//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
										$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
										$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
										$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session.Id_GroupeSession=".$rowSession['Id_GroupeSession'];
										$resultDepasse=mysqli_query($bdd,$req);
										$nbDepasse=mysqli_num_rows($resultDepasse);
										if($nbDepasse>0){$bValide=0;}
									}
									else{
										//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
										$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
										$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
										$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session_date.Id_Session=".$rowSession['Id'];
										$resultDepasse=mysqli_query($bdd,$req);
										$nbDepasse=mysqli_num_rows($resultDepasse);
									}
									//Places restantes
									$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSession['Id'];
									$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
									$nbInscrit=mysqli_num_rows($resultNbInscrit);
									if($rowSession['Nb_Stagiaire_Maxi']>0){
										$nbPlaceRestante=$rowSession['Nb_Stagiaire_Maxi']-$nbInscrit;
										if($nbPlaceRestante<0){$nbPlaceRestante=0;}
									}
									//Possible uniquement si jour non passé
									if($nbDepasse==0){
										//Inscription disponible
										$reqBesoin="SELECT Id FROM form_besoin 
													WHERE Traite=0 
													AND Id_Formation=".$rowSession['Id_Formation']." 
													AND Suppr=0 AND Valide=1 
													AND Id_Prestation IN (
														SELECT Id_Prestation 
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
														AND Id_Personne=".$IdPersonneConnectee.") ";
										$resultBesoin=mysqli_query($bdd,$reqBesoin);
										$nbBesoin=mysqli_num_rows($resultBesoin);
										if($nbBesoin>0){$couleurSession=$jaune;}
										
									}
									
									//Stagiaires inscrits
									$reqStagInscrit="SELECT Id 
												FROM form_session_personne 
												WHERE Validation_Inscription=1 
												AND Suppr=0 
												AND Id_Personne IN (
													SELECT Id_Personne 
														FROM new_competences_personne_prestation 
														WHERE Date_Fin>='".date('Y-m-d')."' 
														AND Id_Prestation IN (
															SELECT Id_Prestation 
															FROM new_competences_personne_poste_prestation 
															WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
															AND Id_Personne=".$IdPersonneConnectee."
															)
													)
												AND Id_Session=".$rowSession['Id'];
									$resultStagInscrit=mysqli_query($bdd,$reqStagInscrit);
									$nbStagInscrit=mysqli_num_rows($resultStagInscrit);
									if($nbStagInscrit>0){$couleurSession=$vert;}
									
								for($k=0;$k<=(sizeof($tabForm)-1);$k++){
									if($tabForm[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
								}
								$nbPartie=1;
								for($k=0;$k<=(sizeof($tabSessionDate)-1);$k++){
									if($tabSessionDate[$k]==$rowSession['Id']){$nbPartie++;}
								}
								if($bExiste==0){$tabSessionDate[$itab2]=$rowSession['Id'];$itab2++;}
								if($bExiste==0){
									$h1=strtotime($rowSession['Heure_Fin']);
									$h2=strtotime($rowSession['Heure_Debut']);
									$val=intval(substr(gmdate("H:i",$h1-$h2),0,2))*4;
									if(substr(gmdate("H:i",$h1-$h2),3,2)=="15"){$val++;}
									elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="30"){$val=$val+2;}
									elseif(substr(gmdate("H:i",$h1-$h2),3,2)=="45"){$val=$val+3;}
									if($val>1){
										$i+=($val-1);
									}
									$heureFin=$rowSession['Heure_Fin'];
									$trouve=1;
									$tabForm[$itab]=$rowSession['Id_SessionDate'];
									$itab++;
									
									$Id_Langue=0;
									if($nbParam>0){
										mysqli_data_seek($resultParam,0);
										while($rowParam=mysqli_fetch_array($resultParam)){
											if($rowParam['Id_Formation']==$rowSession['Id_Formation']){
												$Id_Langue=$rowParam['Id_Langue'];
											}
										}
									}
									$Infos="";
									if($nbInfos>0){
										mysqli_data_seek($resultInfos,0);
										while($rowInfo=mysqli_fetch_array($resultInfos)){
											if($rowInfo['Id_Formation']==$rowSession['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue){
												if($rowSession['Recyclage']==0){
													$Infos=stripslashes($rowInfo['Libelle']);
												}
												else{
													$Infos=stripslashes($rowInfo['LibelleRecyclage']);
												}
											}
										}
									}
									if($LangueAffichage=="FR"){
										$Lieu="Lieu non défini";
									}
									else{
										$Lieu="Undefined location";
									}
									if($rowSession['Lieu']){$Lieu=$rowSession['Lieu'];}
									$Heures=substr($rowSession['Heure_Debut'],0,5)." - ".substr($rowSession['Heure_Fin'],0,5);
									if($rowSession['PauseRepas']==1){
										if($rowSession['Heure_Fin']>$rowSession['HeureDebutPause'] && $rowSession['Heure_Debut']<$rowSession['HeureFinPause']){
											$Heures=substr($rowSession['Heure_Debut'],0,5)." - ".substr($rowSession['HeureDebutPause'],0,5)." | ".substr($rowSession['HeureFinPause'],0,5)." - ".substr($rowSession['Heure_Fin'],0,5);
										}
									}
									$id=$rowSession['Id'];
									$Partie="";
									if($LangueAffichage=="FR"){if($rowSession['Nb']>1){$Partie=" (Partie".$nbPartie.")";}}
									else{if($rowSession['Nb']>1){$Partie=" (Part".$nbPartie.")";}}
									$GroupeFormation="";
									if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']>0){
										if($LangueAffichage=="FR"){$GroupeFormation="Groupe : ".$rowSession['Groupe'];}
										else{$GroupeFormation="Group : ".$rowSession['Groupe'];}
										$id="GR".$rowSession['Id_GroupeSession'];
									}
									$formation=$GroupeFormation." \n".$Infos.$Partie." \n".$Heures." \n".$Lieu;
								}
							}
						}
					}
				}
				if($formation<>""){
					if($_GET['formation']<>""){
						if(stripos($GroupeFormation.$Infos.$Partie,$_GET['formation'])===false){
							$formation="";
						}
					}
				}
				if($formation<>""){
					$couleur=$couleurSession;
				}
				if($_GET['etat']=="disponible" && $couleur<>$jaune && $formation<>""){
					$couleur="#ffffff";
					$formation="";
				}
				elseif($_GET['etat']=="indisponible" && $couleur<>$gris && $formation<>""){
					$couleur="#ffffff";
					$formation="";
				}
				elseif($_GET['etat']=="stagiaires" && $couleur<>$vert && $formation<>""){
					$couleur="#ffffff";
					$formation="";
				}
				$sheet->setCellValue($col.($laLigne+$j-1),utf8_encode($formation));
				$sheet->getStyle($col.($laLigne+$j-1))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>str_replace("#","",$couleur)))));
				$fincol=$col;
				for($m=1;$m<$val;$m++){$fincol++;}
				$sheet->getStyle($col.($laLigne+$j-1))->getAlignment()->setWrapText(true);
				$sheet->getRowDimension($laLigne+$j-1)->setRowHeight(150);
				if($col<>$fincol){
					$sheet->mergeCells($col.($laLigne+$j-1).':'.$fincol.($laLigne+$j-1));
				}
				$sheet->getStyle($col.($laLigne+$j-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$sheet->getStyle($col.($laLigne+$j-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				if($min==0){$min=15;}
				elseif($min==15){$min=30;}
				elseif($min==30){$min=45;}
				else{$min=0;$heure++;}
				if($heureFin<>"00:00:00"){
					$heure=intval(substr($heureFin,0,2));
					$min=intval(substr($heureFin,3,2));
				}
				if($val==0){
					$col++;
				}
				else{
					for($m=1;$m<$val;$m++){$col++;}
					$col++;
				}
			}
		}
		$laLigne+=$nbLigne-1;
	}
	if($nbSansHeures>0){
		//CAS DES FORMATIONS SANS HEURES DE DEBUT ET DE FIN
		if($nbSession>0){
			$id="";
			$couleur="eff7ff";
			$couleurSession="";
			mysqli_data_seek($resultSessions,0);
			while($rowSession=mysqli_fetch_array($resultSessions)){
				if($rowSession['DateSession']==$tmpDate){
					if($rowSession['Heure_Debut']=="00:00:00"){
						$bExiste=0;
						$couleurSession="";
						$couleurSession=$gris;
						$onclick="";
						//Vérifier si la formation n'a pas déjà commencée
								if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']==1){
							//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
							$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
							$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
							$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session.Id_GroupeSession=".$rowSession['Id_GroupeSession'];
							$resultDepasse=mysqli_query($bdd,$req);
							$nbDepasse=mysqli_num_rows($resultDepasse);
						}
						else{
							//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
							$req="SELECT form_session_date.Id FROM form_session_date LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id ";
							$req.="WHERE form_session_date.DateSession<='".date('Y-m-d')."' AND form_session_date.Suppr=0 ";
							$req.="AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 AND form_session_date.Id_Session=".$rowSession['Id'];
							$resultDepasse=mysqli_query($bdd,$req);
							$nbDepasse=mysqli_num_rows($resultDepasse);
						}
						//Places restantes
						$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSession['Id'];
						$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
						$nbInscrit=mysqli_num_rows($resultNbInscrit);
						if($rowSession['Nb_Stagiaire_Maxi']>0){
							$nbPlaceRestante=$rowSession['Nb_Stagiaire_Maxi']-$nbInscrit;
							if($nbPlaceRestante<0){$nbPlaceRestante=0;}
						}
						if($nbDepasse==0){
							//session disponible
							$reqBesoin="SELECT Id 
										FROM form_besoin 
										WHERE Id_Formation=".$rowSession['Id_Formation']." 
										AND Suppr=0 
										AND Valide=0 
										AND Id_Prestation IN (
											SELECT Id_Prestation 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
											AND Id_Personne=".$IdPersonneConnectee.") ";
							$resultBesoin=mysqli_query($bdd,$reqBesoin);
							$nbBesoin=mysqli_num_rows($resultBesoin);
							if($nbBesoin>0){$couleurSession=$jaune;}
							
						}
						//Stagiaires inscrits
						$reqStagInscrit="SELECT Id 
									FROM form_session_personne 
									WHERE Validation_Inscription=1 
									AND Suppr=0 
									AND Id_Personne IN (
										SELECT Id_Personne 
											FROM new_competences_personne_prestation 
											WHERE Date_Fin>='".date('Y-m-d')."' 
											AND Id_Prestation IN (
												SELECT Id_Prestation 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
												AND Id_Personne=".$IdPersonneConnectee."
												)
										)
									AND Id_Session=".$rowSession['Id'];
						$resultStagInscrit=mysqli_query($bdd,$reqStagInscrit);
						$nbStagInscrit=mysqli_num_rows($resultStagInscrit);
						if($nbStagInscrit>0){$couleurSession=$vert;}
						for($k=0;$k<=(sizeof($tabForm)-1);$k++){
							if($tabForm[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
						}
						$nbPartie=1;
						for($k=0;$k<=(sizeof($tabSessionDate)-1);$k++){
							if($tabSessionDate[$k]==$rowSession['Id']){$nbPartie++;}
						}
						$tabSessionDate[$itab2]=$rowSession['Id'];
						if($bExiste==0){
							$Id_Langue=0;
							if($nbParam>0){
								mysqli_data_seek($resultParam,0);
								while($rowParam=mysqli_fetch_array($resultParam)){
									if($rowParam['Id_Formation']==$rowSession['Id_Formation']){
										$Id_Langue=$rowParam['Id_Langue'];
									}
								}
							}
							$Infos="";
							if($nbInfos>0){
								mysqli_data_seek($resultInfos,0);
								while($rowInfo=mysqli_fetch_array($resultInfos)){
									if($rowInfo['Id_Formation']==$rowSession['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue){
										if($rowSession['Recyclage']==0){
											$Infos=stripslashes($rowInfo['Libelle']);
										}
										else{
											$Infos=stripslashes($rowInfo['LibelleRecyclage']);
										}
									}
								}
							}
							if($LangueAffichage=="FR"){
								$Lieu="Lieu non défini";
							}
							else{
								$Lieu="Undefined location";
							}
							if($rowSession['Lieu']){$Lieu=$rowSession['Lieu'];}
							$Partie="";
							if($LangueAffichage=="FR"){if($rowSession['Nb']>1){$Partie=" (Partie".$nbPartie.")";}}
							else{if($rowSession['Nb']>1){$Partie=" (Part".$nbPartie.")";}}
							$GroupeFormation="";
							$id=$rowSession['Id'];
							if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']>0){
								if($LangueAffichage=="FR"){$GroupeFormation="Groupe : ".$rowSession['Groupe'];}
								else{$GroupeFormation="Group : ".$rowSession['Groupe'];}
								$id="GR".$rowSession['Id_GroupeSession'];
							}
							if($LangueAffichage=="FR"){
								$formation=$GroupeFormation." \n".$Infos.$Partie." \n Horaires non définis \n".$Lieu;
							}
							else{
								$formation=$GroupeFormation." \n".$Infos.$Partie." \n Unresolved hours \n".$Lieu;
							}
							if($formation<>""){
								if($_GET['formation']<>""){
									if(stripos($GroupeFormation.$Infos.$Partie,$_GET['formation'])===false){
										$formation="";
									}
									else{
										$couleur=$couleurSession;
									}
								}
								else{
									$couleur=$couleurSession;
								}
							}
							else{
								$couleur=$couleurSession;
							}
							$sheet->setCellValue("E".($laLigne),utf8_encode($formation));
							$sheet->getStyle("E".($laLigne))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>str_replace("#","",$couleur)))));
							$sheet->getStyle("E".($laLigne))->getAlignment()->setWrapText(true);
							$sheet->getRowDimension($laLigne)->setRowHeight(150);
							$sheet->mergeCells("E".($laLigne).':BE'.($laLigne));
							$sheet->getStyle("E".($laLigne))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
							$sheet->getStyle("E".($laLigne))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
							$laLigne++;
						}
					}
				}
			}
		}
		$laLigne--;
	}
	$semaineEC=date('W', strtotime($tmpDate." + 0 month"));
	$laLigne++;

	//Jour suivant
	$tmpDate = date("Y-m-d", strtotime($tmpDate." + 1 day"));
}
if(($laLigne-1)>$debutSemaine){
	$sheet->mergeCells('A'.$debutSemaine.':A'.($laLigne-1));
}
$sheet->getStyle('A1:BE'.($laLigne-1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

$sheet->getSheetView()->setZoomScale(60);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="PlanningFormation.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="TrainingPlanning.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/PlanningFormation.xlsx';

$writer->save($chemin);
readfile($chemin);
?>