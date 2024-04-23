<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Besoins"));
	
	$sheet->setCellValue('A1',utf8_encode("Type"));
	$sheet->setCellValue('B1',utf8_encode("Formation / Organisme"));
	$sheet->setCellValue('C1',utf8_encode("Prestation - Pôle"));
	$sheet->setCellValue('D1',utf8_encode("Personne"));
	$sheet->setCellValue('E1',utf8_encode("Motif"));
	$sheet->setCellValue('F1',utf8_encode("Date demande"));
	$sheet->setCellValue('G1',utf8_encode("Date fin validité qualification"));
	$sheet->setCellValue('H1',utf8_encode("Etat"));
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
		$sheet->setCellValue('I1',utf8_encode("Contrat"));
	}
}
else{
	$sheet->setTitle(utf8_encode("Needs"));
	$sheet->setCellValue('A1',utf8_encode("Type"));
	$sheet->setCellValue('B1',utf8_encode("Training / Organization"));
	$sheet->setCellValue('C1',utf8_encode("Activity - Pole"));
	$sheet->setCellValue('D1',utf8_encode("Person"));
	$sheet->setCellValue('E1',utf8_encode("Pattern"));
	$sheet->setCellValue('F1',utf8_encode("Demand date"));
	$sheet->setCellValue('G1',utf8_encode("End date of validity of qualifications"));
	$sheet->setCellValue('H1',utf8_encode("State"));
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
		$sheet->setCellValue('I1',utf8_encode("Contract"));
	}
}

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(25);

if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
	$sheet->getStyle('A1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:I1')->getFont()->setBold(true);
	$sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('1f49a6');
}
else{
	$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:H1')->getFont()->setBold(true);
	$sheet->getStyle('A1:H1')->getFont()->getColor()->setRGB('1f49a6');
}

$DateJour=date('Y-m-d');

$requetePersonnes="SELECT
			Id_Personne
		FROM
			new_competences_personne_prestation
		WHERE
			Date_Fin>='".$DateJour."'
			AND Id_Prestation IN ";
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
		$requetePersonnes.="(SELECT Id_Prestation 
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation 
		ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
		WHERE Date_Fin>='".$DateJour."' AND Id_Plateforme IN (
		SELECT Id_Plateforme 
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableRH.")
		)) ";
	}
	else{
		$requetePersonnes.="(SELECT Id_Prestation 
		FROM new_competences_personne_prestation
		WHERE Date_Fin>='".$DateJour."' AND Id_Prestation IN (
			SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation
			WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
		)) ";
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
$requete="
SELECT
    form_besoin.Id AS ID_BESOIN,
	form_besoin.Suppr,
	form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
	form_besoin.Id_Formation AS ID_FORMATION,
	form_formation.Reference AS REFERENCE_FORMATION,
	form_formation_langue_infos.Libelle AS LIBELLE_FORMATION,
	new_competences_prestation.Libelle AS LIBELLE_PRESTATION,
	(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) AS Pole,
	CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOM_PRENOM,
	new_rh_etatcivil.Contrat,
	form_besoin.Id_Personne,
	form_besoin.Motif AS MOTIF_DEMANDE,
	form_besoin.Date_Demande AS DATE_DEMANDE,
	form_besoin.Commentaire AS COMMENTAIRE,
	form_besoin.Id_Prestation,
	form_besoin.Valide AS VALIDE,
	new_competences_prestation.Id_Plateforme,
	form_formation_plateforme_parametres.Id_Langue, 
	(SELECT Id_Langue FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Id_Langue,
	(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Organisme,
	form_besoin.Obligatoire,
    form_besoin.Etat
FROM
	form_besoin,
	form_typeformation,
	form_formation,
	form_formation_langue_infos,
	new_rh_etatcivil,
	new_competences_prestation,
	form_formation_plateforme_parametres
WHERE
	form_besoin.Id_Formation=form_formation.Id
	AND form_formation.Id_TypeFormation=form_typeformation.Id
	AND form_besoin.Id_Prestation=new_competences_prestation.Id
	AND form_besoin.Id_Personne=new_rh_etatcivil.Id

	AND form_formation_langue_infos.Id_Langue = form_formation_plateforme_parametres.Id_Langue 
	AND form_formation_langue_infos.Id_Formation = form_besoin.Id_Formation
	AND form_formation_langue_infos.Suppr=0

	AND form_formation_plateforme_parametres.Id_Formation = form_besoin.Id_Formation
	AND form_formation_plateforme_parametres.Id_Plateforme = new_competences_prestation.Id_Plateforme 
	AND form_formation_plateforme_parametres.Suppr = 0 

	AND form_besoin.Id_Personne IN
	(".$listeRespPers.")
	AND form_besoin.Valide>=0
	AND form_besoin.Traite=0 ";
	if($_SESSION['FiltreBesoin_Etat']<>"Supprime"){
		$requete.="AND form_besoin.Suppr=0 ";
	}
if($_SESSION['FiltreBesoin_Type']>0){$requete.="AND form_formation.Id_TypeFormation=".$_SESSION['FiltreBesoin_Type']." ";}
if($_SESSION['FiltreBesoin_Prestation']<>"")
{
	$requetePersonnesPrestaR="
		SELECT
			Id_Personne
		FROM
			new_competences_personne_prestation
		WHERE
			Date_Fin>='".date('Y-m-d')."' 
		AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$_SESSION['FiltreBesoin_Prestation'].") ";
	$resultPersResp=mysqli_query($bdd,$requetePersonnesPrestaR);
	$nbPersResp=mysqli_num_rows($resultPersResp);
	$listeRespPersPrestaR=0;
	if($nbPersResp>0)
	{
		$listeRespPersPrestaR="";
		while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPersPrestaR.=$rowPersResp['Id_Personne'].",";}
		$listeRespPersPrestaR=substr($listeRespPersPrestaR,0,-1);
	}

	$requete.="AND form_besoin.Id_Personne IN (".$listeRespPersPrestaR.") ";
}

if($_SESSION['FiltreBesoin_RespProjet']<>""){
	$requetePersonnesPrestaR="
		SELECT
			Id_Personne
		FROM
			new_competences_personne_prestation
		WHERE
			Date_Fin>='".$DateJour."' 
		AND CONCAT(Id_Prestation,'_',Id_Pole) IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$_SESSION['FiltreBesoin_RespProjet'].")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				) ";
	$resultPersResp=mysqli_query($bdd,$requetePersonnesPrestaR);
	$nbPersResp=mysqli_num_rows($resultPersResp);
	$listeRespPersPrestaR=0;
	if($nbPersResp>0)
	{
		$listeRespPersPrestaR="";
		while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPersPrestaR.=$rowPersResp['Id_Personne'].",";}
		$listeRespPersPrestaR=substr($listeRespPersPrestaR,0,-1);
	}

	$requete.="AND form_besoin.Id_Personne IN (".$listeRespPersPrestaR.") ";
}

if($_SESSION['FiltreBesoin_Personne']>0){$requete.="AND form_besoin.Id_Personne=".$_SESSION['FiltreBesoin_Personne']." ";}
if($_SESSION['FiltreBesoin_PrisEnCompte']<>""){$requete.="AND form_besoin.TraiteAF=".$_SESSION['FiltreBesoin_PrisEnCompte']." ";}
if($_SESSION['FiltreBesoin_Formation']>0){
	$tabForm=explode("_",$_SESSION['FiltreBesoin_Formation']);
	$requete.="AND form_besoin.Id_Formation=".$tabForm[0]." 
			AND IF(form_besoin.Motif='Renouvellement',1,0)=".$tabForm[1]." ";
}
if($_SESSION['FiltreBesoin_Etat']<>""){
	if($_SESSION['FiltreBesoin_Etat']<>"Supprime"){
		$requete.="AND form_besoin.Etat='".$_SESSION['FiltreBesoin_Etat']."' ";
	}
	else{
		$requete.="AND form_besoin.Suppr=1 ";
	}
}
else{$requete.="AND form_besoin.Etat<>'Refuse' ";}

$requete.="	ORDER BY
	LIBELLE_PRESTATION ASC,
	REFERENCE_FORMATION ASC,
	NOM_PRENOM ASC";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);

$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue FROM form_formation_langue_infos WHERE Suppr=0";
$resultFormLangue=mysqli_query($bdd,$reqLangue);
$nbFormLangue=mysqli_num_rows($resultFormLangue);
if($nbenreg>0)
{
    $ligne=2;
    while($row=mysqli_fetch_array($result))
    {
    	//Gestion des couleurs en fonction du traitement du besoin
    	$Couleur="FFFFFF";
		if($row['Suppr']==1){
			$Couleur=$blanc;
		}
		else{
			switch($row['Etat'])
			{
				case "Refuse": $Couleur=$gris;break;
				case "AConfirmer": $Couleur=$rouge;break;
				case "PasDispo": $Couleur=$orange;break;
				case "Dispo":$Couleur=$vert;break;
			}
		}
    	$hover="";
    	$span="";
    	if($row['COMMENTAIRE']<>"")
    	{
    		$hover="id='leHover'";
    		$span="<span>".stripslashes($row['COMMENTAIRE'])."</span>";
    	}
    	
    	$Libelle="";
    	if($nbFormLangue>0)
    	{
    		mysqli_data_seek($resultFormLangue,0);
    		while($rowFormLangue=mysqli_fetch_array($resultFormLangue))
    		{
    			if($rowFormLangue['Id_Formation']==$row['ID_FORMATION'] && $rowFormLangue['Id_Langue']==$row['Id_Langue'] )
    			{
    				if($row['MOTIF_DEMANDE']=="Renouvellement")
    				{
    					$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
    					if($Libelle==""){$Libelle=stripslashes($rowFormLangue['Libelle']);}
    				}
    				else{$Libelle=stripslashes($rowFormLangue['Libelle']);}
    				if($row['Organisme']<>""){$Libelle.=" (".$row['Organisme'].")";}
    			}
    		}
    	}
    	if($Libelle==""){$Libelle="<<".$row['REFERENCE_FORMATION'].">>";}
    	$bTrouve=1;
    	if($_GET['Etat']<>"Refuse")
    	{
    		if($Couleur==$gris){$bTrouve=0;}
    	}
    	if($bTrouve==1)
    	{
        	//Liste des qualifications et leur validité
			$req="SELECT
					DISTINCT Date_Fin
				FROM
					(
					SELECT
						Id,
						Date_Debut,
						Date_Fin,
						Date_QCM,
						Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk
					FROM new_competences_relation
					WHERE
						Id_Personne=".$row['Id_Personne']."
						AND Type='Qualification'
						AND new_competences_relation.Visible=0
						AND Suppr=0
						AND Id_Qualification_Parrainage IN 
						(SELECT Id_Qualification
						FROM form_formation_qualification
						WHERE Id_Formation=".$row['ID_FORMATION']."
						AND Suppr=0
						)
					ORDER BY
						Date_QCM DESC, Date_Fin DESC
					) AS Toto
				 GROUP BY
					Toto.Id_Qualification_Parrainage";
			$resultQualif=mysqli_query($bdd,$req);
			$nbQualif=mysqli_num_rows($resultQualif);
			$dateFin="";
			if($nbQualif>0)
			{
				while($rowQualif=mysqli_fetch_array($resultQualif))
				{
					if(AfficheDateJJ_MM_AAAA($rowQualif['Date_Fin'])<>""){$dateFin.=AfficheDateJJ_MM_AAAA($rowQualif['Date_Fin'])."\n";}
				}
			}     	
			
        	$etat="";
        	if($LangueAffichage=="FR")
        	{
        		if($Couleur==$vert){$etat="Dates disponibles dans le planning";}
        		elseif($Couleur==$orange){$etat="Pas de date disponible";}
        		elseif($Couleur==$rouge){$etat="Besoin à confirmer";}
        		elseif($Couleur==$gris){$etat="Besoin refusé";}
				elseif($Couleur==$blanc){$etat="Besoin supprimé";}
        	}
        	else
        	{
        		if($Couleur==$vert){$etat="Dates available in the schedule";}
        		elseif($Couleur==$orange){$etat="No date available";}
        		elseif($Couleur==$rouge){$etat="Need to be confirmed";}
        		elseif($Couleur==$gris){$etat="Need refused";}
				elseif($Couleur==$blanc){$etat="Need deleted";}
        	}
        	
        	$Motif=$row['MOTIF_DEMANDE'];
        	if($LangueAffichage<>"FR")
        	{
        		switch($Motif)
        		{
        			case "Nouveau":$Motif="New";break;
        			case "Renouvellement":$Motif="Renewal";break;
        			case "Suite à absence":$Motif="Following absence";break;
        			case "Changement de prestation":$Motif="Change of service";break;
        			case "Nouveau besoin pour ce métier et cette prestation":$Motif="New need for this profession and this service";break;
        		}
        		$Motif=str_replace("En formation sur nouveau métier","In training on a new job",$Motif);
        		$Motif=str_replace("Nouveau besoin pour ce métier","New need for this profession",$Motif);
        		$Motif=str_replace("et cette prestation","and this service",$Motif);
        	}
        	$Pole="";
        	if($row['Pole']<>""){$Pole=" - ".$row['Pole'];}
        	$sheet->setCellValue('A'.$ligne,utf8_encode($row['LIBELLE_TYPEFORMATION']));
        	$sheet->setCellValue('B'.$ligne,utf8_encode($Libelle));
        	$sheet->setCellValue('C'.$ligne,utf8_encode($row['LIBELLE_PRESTATION'].$Pole));
        	$sheet->setCellValue('D'.$ligne,utf8_encode( $row['NOM_PRENOM']));
        	$sheet->setCellValue('E'.$ligne,utf8_encode($Motif));
        	$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DATE_DEMANDE'])));
        	$sheet->setCellValue('G'.$ligne,utf8_encode($dateFin));
			$sheet->getStyle('G'.$ligne)->getAlignment()->setWrapText(true);
        	$sheet->setCellValue('H'.$ligne,utf8_encode($etat));
        	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
					$sheet->setCellValue('I'.$ligne,utf8_encode($row['Contrat']));
			}
			
			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
				$sheet->getStyle('A'.$ligne.':I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A'.$ligne.':I'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$sheet->getStyle('A'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
				$sheet->getStyle('A'.$ligne.':I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			}
			else{
				$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
				$sheet->getStyle('A'.$ligne.':H'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			}
        	
        	$ligne++;
    	}
    }	//Fin boucle
}
						
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Besoins.xlsx"');}
else{header('Content-Disposition: attachment;filename="Needs.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Besoins.xlsx';
$writer->save($chemin);
readfile($chemin);
?>