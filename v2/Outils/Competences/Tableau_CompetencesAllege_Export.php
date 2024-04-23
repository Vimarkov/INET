<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require_once("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Competences');

$AfficherStamp=0;
$AfficherBadge=0;
$Filiale=0;
if($_GET['Type']=="Prestation")
{
	$Requetes_Titre="SELECT Libelle, Code_Analytique, Id_Plateforme, AfficherBadgeStamp, AfficherBadge FROM new_competences_prestation WHERE Id=".$_GET['Id'];
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$ResultLogo=mysqli_query($bdd,"SELECT Logo FROM new_competences_plateforme WHERE Id=".$Ligne_Titre[2]);
	$LigneLogo=mysqli_fetch_array($ResultLogo);
	$Logo=$LigneLogo[0];
	if($Ligne_Titre['AfficherBadgeStamp']==1){$AfficherStamp=1;}
	if($Ligne_Titre['AfficherBadge']==1){$AfficherBadge=1;}
	if($Ligne_Titre['Id_Plateforme']==7 || $Ligne_Titre['Id_Plateforme']==12 || $Ligne_Titre['Id_Plateforme']==16
	|| $Ligne_Titre['Id_Plateforme']==18 || $Ligne_Titre['Id_Plateforme']==20 || $Ligne_Titre['Id_Plateforme']==22
	|| $Ligne_Titre['Id_Plateforme']==26 || $Ligne_Titre['Id_Plateforme']==30){$Filiale=1;}
}
elseif($_GET['Type']=="Plateforme")
{
	$Requetes_Titre="SELECT Libelle, Logo,Id FROM new_competences_plateforme WHERE Id=".$_GET['Id'];
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$Logo=$Ligne_Titre[1];
	if($Ligne_Titre['Id']==7 || $Ligne_Titre['Id']==12 || $Ligne_Titre['Id']==16
	|| $Ligne_Titre['Id']==18 || $Ligne_Titre['Id']==20 || $Ligne_Titre['Id']==22
	|| $Ligne_Titre['Id']==26 || $Ligne_Titre['Id']==30){$Filiale=1;}
}
elseif($_GET['Type']=="Pole")
{
	$Requetes_Titre="SELECT Libelle, Code_Analytique, Id_Plateforme, AfficherBadgeStamp, AfficherBadge FROM new_competences_prestation WHERE Id IN (SELECT Id_Prestation FROM new_competences_pole WHERE Id=".$_GET['Id'].")";
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$ResultLogo=mysqli_query($bdd,"SELECT Logo FROM new_competences_plateforme WHERE Id=".$Ligne_Titre[2]);
	$LigneLogo=mysqli_fetch_array($ResultLogo);
	$Logo=$LigneLogo[0];
	$Requete_Libelle_Pole="SELECT Libelle FROM new_competences_pole WHERE Id=".$_GET['Id'];
	$Result_Libelle_Pole=mysqli_query($bdd,$Requete_Libelle_Pole);
	$Ligne_Libelle_Pole=mysqli_fetch_array($Result_Libelle_Pole);
	if($Ligne_Titre['AfficherBadgeStamp']==1){$AfficherStamp=1;}
	if($Ligne_Titre['AfficherBadge']==1){$AfficherBadge=1;}
	if($Ligne_Titre['Id_Plateforme']==7 || $Ligne_Titre['Id_Plateforme']==12 || $Ligne_Titre['Id_Plateforme']==16
	|| $Ligne_Titre['Id_Plateforme']==18 || $Ligne_Titre['Id_Plateforme']==20 || $Ligne_Titre['Id_Plateforme']==22
	|| $Ligne_Titre['Id_Plateforme']==26 || $Ligne_Titre['Id_Plateforme']==30){$Filiale=1;}
}

if($_GET['Type']=="Prestation")
{
	$Requetes_Liste_Qualifs="
		SELECT
			new_competences_qualification.Id,
			new_competences_qualification.Id_Categorie_Qualification,
			new_competences_qualification.Libelle,
			new_competences_categorie_qualification.Id_Categorie_Maitre
		FROM
			new_competences_qualification
			LEFT JOIN
				new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
			LEFT JOIN
				new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
		WHERE
			new_competences_prestation_qualification.Id_Prestation=".$_GET['Id'];
}
elseif($_GET['Type']=="Plateforme")
{
	$Requetes_Liste_Qualifs="
		SELECT
			DISTINCT new_competences_qualification.Id,
			new_competences_qualification.Id_Categorie_Qualification,
			new_competences_qualification.Libelle,
			new_competences_categorie_qualification.Id_Categorie_Maitre
		FROM
			new_competences_qualification
			LEFT JOIN
				new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
			LEFT JOIN
				new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
		WHERE
			new_competences_prestation_qualification.Id_Prestation IN
			(
				SELECT
					Id
				FROM
					new_competences_prestation
				WHERE
					Id_Plateforme=".$_GET['Id']."
			)";
}
elseif($_GET['Type']=="Pole")
{
	$Requetes_Liste_Qualifs="
		SELECT
			new_competences_qualification.Id,
			new_competences_qualification.Id_Categorie_Qualification,
			new_competences_qualification.Libelle,
			new_competences_categorie_qualification.Id_Categorie_Maitre
		FROM
			new_competences_qualification
			LEFT JOIN
				new_competences_pole_qualification ON new_competences_qualification.Id=new_competences_pole_qualification.Id_Qualification
			LEFT JOIN new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
		WHERE
			new_competences_pole_qualification.Id_Pole = ".$_GET['Id']." 
			AND new_competences_pole_qualification.Id_Qualification IN (
				SELECT Id_Qualification
				FROM new_competences_prestation_qualification
				WHERE Id_Prestation IN (SELECT Id_Prestation FROM new_competences_pole WHERE Id=".$_GET['Id'].")
			)";
}
$Requetes_Liste_Qualifs.="
		ORDER BY
			new_competences_categorie_qualification.Libelle ASC,
			new_competences_qualification.Libelle ASC";
$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);

$nbenreg=mysqli_num_rows($Result_Liste_Qualification);
if($nbenreg>0)
{
	/********************************************************/
	/************************EN-TETE*************************/
	/********************************************************/
	
	/*************IMAGES*******************/
	
	$sheet->getStyle('A1:E5')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffffff'))));
	$sheet->getStyle('A1:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getColumnDimension('A')->setWidth(20);
	$sheet->getColumnDimension('B')->setWidth(15);
	$sheet->getColumnDimension('C')->setWidth(55);
	$sheet->getColumnDimension('D')->setWidth(15);
	$sheet->getColumnDimension('E')->setWidth(25);
	
	$sheet->getRowDimension('1')->setRowHeight(100);
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Logos/Logo Daher_posi.png');
	$objDrawing->setWidth(130);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(40);
	$objDrawing->setWorksheet($sheet);
	
	if($Logo<>""){
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('logo');
		$objDrawing->setDescription('PHPExcel logo');
		$objDrawing->setPath('../../Images/Logos/'.$Logo);
		$objDrawing->setHeight(70);
		$objDrawing->setWidth(130);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setOffsetX(5);
		$objDrawing->setOffsetY(80);
		$objDrawing->setWorksheet($sheet);
	}
	
	$sheet->mergeCells('A1:A2');

	/*************TITRE*******************/
	if($Filiale==0){$sheet->setCellValue("B1",utf8_encode("TABLEAU DES COMPETENCES"));}
	else{$sheet->setCellValue("B1",utf8_encode("COMPETENCY LIST"));}
	$sheet->getStyle('B1')->getFont()->setSize(25);//Taille du texte
	$sheet->mergeCells('B1:C3');

	$sheet->getStyle('B1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00325F'))));
	$sheet->getStyle('B1:C1')->getFont()->getColor()->setRGB('ffffff');
	$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:E3')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	
	$sheet->setCellValue("A3",utf8_encode("Daher Industrial Services"));
	$sheet->getStyle('A3')->getFont()->getColor()->setRGB('00325F');
	$sheet->getStyle('A3')->getFont()->setSize(10);//Taille du texte
	
	$sheet->setCellValue("D1",utf8_encode("D-0731"));
	if($Filiale==0){
		$sheet->setCellValue("D2",utf8_encode("Trame Version 2"));
		$sheet->setCellValue("D3",utf8_encode("20-Fév. 2024"));
	}
	else{
		$sheet->setCellValue("D2",utf8_encode("Template issue 2"));
		$sheet->setCellValue("D3",utf8_encode("Feb. 20, 2024"));
	}
	
	$sheet->mergeCells('D1:E1');
	$sheet->mergeCells('D2:E2');
	$sheet->mergeCells('D3:E3');
	$sheet->getStyle('D1:D3')->getFont()->getColor()->setRGB('505F69');
	$sheet->getStyle('D1:E3')->getFont()->setSize(10);//Taille du texte
	
	$sheet->getStyle('B1')->getFont()->setBold(true);//Texte en gras
	
	/*************PRESTATION-PLATEFORME-POLE*******************/
	
	if($Filiale==0){
		$MoisLettre = array("Janv.", "Févr.", "Mar.", "Avr.", "Mai", "Juin", "Juil.", "Aoû.", "Sept.", "Oct.", "Nov.", "Déc.");
	}
	else
	{
		$MoisLettre = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sept.", "Oct.", "Nov.", "Dec.");
	}
	
	if($Filiale==0){
		$sheet->setCellValue("A4",utf8_encode("Contenu mis à jour le : ".date('d-').$MoisLettre[date('m')-1].date(' Y')));
	}
	else{
		$sheet->setCellValue("A4",utf8_encode("Content updated on : ".$MoisLettre[date('m')-1].date(' d, Y')));
	}
	
	$Nom = "";

	if($_GET['Type']=="Prestation"){$Nom =$Ligne_Titre[0];}
	elseif($_GET['Type']=="Plateforme"){$Nom =$Ligne_Titre[0];}
	elseif($_GET['Type']=="Pole"){$Nom ="Pôle : ".$Ligne_Titre[0]." # ".$Ligne_Libelle_Pole[0];}
	
	$sheet->setCellValue("A5",utf8_encode($Nom));
	$sheet->getStyle('A5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	
	if($Filiale==0){
		$sheet->setCellValue("A5",utf8_encode("Prestation : ".$Nom));
	}
	else{
		$sheet->setCellValue("A5",utf8_encode("Site : ".$Nom));
	}
	
	$sheet->mergeCells('A4:E4');
	$sheet->mergeCells('A5:E5');
	$sheet->getStyle('A5')->getFont()->setBold(true);//Texte en gras
	
	/*************LEGENDE*******************/
	$sheet->getRowDimension('5')->setRowHeight(150);
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('PHPExcel logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Legende_GPEC2.png');
	$objDrawing->setCoordinates('A5');
	$objDrawing->setOffsetX(8);
	$objDrawing->setOffsetY(35);
	$objDrawing->setWorksheet($sheet);
	
	$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
	$Derniere_Categorie=0;
	$Affiche_Categorie=0;
	$Nb_Qualification_Categorie=1;
	if($AfficherStamp==1 && $AfficherBadge==1 ){
		$colonne=6;
		$colonne2=6;
		$colonneL = "G";
		$colonneL2 = "G";
		$colonneL3 = "G";
	}
	else{
		$colonne=5;
		$colonne2=5;
		$colonneL = "F";
		$colonneL2 = "F";
		$colonneL3 = "F";
	}
	
	$couleur="a9d0f5";
	$couleurTexte="000000";
	while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification))
	{
		if($Derniere_Categorie!=$Ligne_Liste_Qualification['Id_Categorie_Qualification'])
		{
			if($Derniere_Categorie!=0)
			{
				if($couleur=="a9d0f5"){$couleur="999999";$couleurTexte="ffffff";}
				else{$couleur="a9d0f5";$couleurTexte="000000";}
				$Requete_Categorie="SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=".$Derniere_Categorie;
				$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
				$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
				$sheet->setCellValueByColumnAndRow($colonne,1,utf8_encode($Ligne_Categorie[0]));
				if($Nb_Qualification_Categorie > 1)
				{
					for($i=1;$i<$Nb_Qualification_Categorie;$i++){$colonneL2++;}
					$sheet->mergeCells($colonneL.'1:'.$colonneL2.'1');
				}
				$sheet->getStyle($colonneL.'1:'.$colonneL2.'2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$sheet->getStyle($colonneL.'1:'.$colonneL2.'2')->getFont()->getColor()->setRGB($couleurTexte);
				$colonne = $colonne + $Nb_Qualification_Categorie;
				$colonneL2++;
				$colonneL = $colonneL2;
				$Nb_Qualification_Categorie=1;
			}
			if($Affiche_Categorie==0){$Affiche_Categorie=1;}else{$Affiche_Categorie=0;}
			$Derniere_Categorie=$Ligne_Liste_Qualification['Id_Categorie_Qualification'];
		}
		else
		{
			$Nb_Qualification_Categorie+=1;
		}
		$sheet->setCellValueByColumnAndRow($colonne2,2,utf8_encode($Ligne_Liste_Qualification['Libelle']));
		$sheet->mergeCells($colonneL3.'2:'.$colonneL3.'5');
		$colonne2++;
		$colonneL3++;
	}
	$Requete_Categorie="SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=".$Derniere_Categorie;
	$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
	$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
	$sheet->setCellValueByColumnAndRow($colonne,1,utf8_encode($Ligne_Categorie[0]));
	if($Nb_Qualification_Categorie > 1)
	{
		for($i=1;$i<$Nb_Qualification_Categorie;$i++){$colonneL2++;}
		$sheet->mergeCells($colonneL.'1:'.$colonneL2.'1');
	}
	$Nb_Qualification_Categorie+=1;
	if($couleur=="a9d0f5"){$couleur="999999";$couleurTexte="ffffff";}
	else{$couleur="a9d0f5";$couleurTexte="000000";}
	$sheet->getStyle($colonneL.'1:'.$colonneL2.'2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
	$sheet->getStyle($colonneL.'1:'.$colonneL2.'2')->getFont()->getColor()->setRGB($couleurTexte);
	if($AfficherStamp==1 && $AfficherBadge==1){
		$sheet->getStyle('G1:'.$colonneL2.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('G2:'.$colonneL2.'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	}
	else{
		$sheet->getStyle('F1:'.$colonneL2.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('F2:'.$colonneL2.'2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
	}
	//$sheet->setCellValueByColumnAndRow($colonne2,2,utf8_encode($Ligne_Liste_Qualification['Libelle']));
	$sheet->mergeCells($colonneL3.'2:'.$colonneL3.'5');
	$colonne2++;
	$colonneL3++;
	$colonne = $colonne + $Nb_Qualification_Categorie;
	$colonneL = $colonneL2;
	if($AfficherStamp==1 && $AfficherBadge==1){
		$sheet->getStyle('G1:'.$colonneL2.'2')->getAlignment()->setWrapText(true);
		$sheet->getStyle('G1:'.$colonneL2.'6')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
	else{
		$sheet->getStyle('F1:'.$colonneL2.'2')->getAlignment()->setWrapText(true);
		$sheet->getStyle('F1:'.$colonneL2.'6')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));		
	}
	/********************************************************/
	/*************************CORPS**************************/
	/********************************************************/
	if($_GET['Type']=="Prestation")
	{
		$Requete_Liste_PersonneMilieu="
				LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
				LEFT JOIN new_competences_prestation ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE
				new_competences_prestation.Id=".$_GET['Id']."
				AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
				AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
	}
	elseif($_GET['Type']=="Plateforme")
	{
		$Requete_Liste_PersonneMilieu="
				LEFT JOIN new_competences_personne_plateforme ON new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id
				LEFT JOIN new_competences_plateforme ON new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id
			WHERE
				new_competences_plateforme.Id=".$_GET['Id'];
	}
	elseif($_GET['Type']=="Pole")
	{
		$Requete_Liste_PersonneMilieu="
				LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
				LEFT JOIN new_competences_prestation ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE
				new_competences_personne_prestation.Id_Pole=".$_GET['Id']."
				AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
				AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
	}
	$Requete_Liste_Personne="
		SELECT
			DISTINCT new_rh_etatcivil.Id
		FROM
			new_rh_etatcivil".
			$Requete_Liste_PersonneMilieu."
		ORDER BY
			new_rh_etatcivil.Nom ASC,
			new_rh_etatcivil.Prenom ASC";
	$Result_Liste_Personne=mysqli_query($bdd,$Requete_Liste_Personne);
	$nbPersonne=mysqli_num_rows($Result_Liste_Personne);
	$Couleur="eeeeee";
	$ligne=6;
	
	if($nbPersonne>0){
		while($Ligne_Liste_Personne=mysqli_fetch_array($Result_Liste_Personne)){
			//Personne
			$Nom="";
			$Prenom="";
			$NumBadge="";
			$Stamp="";
			$requete_etatcivil="SELECT Nom, Prenom,NumBadge FROM new_rh_etatcivil WHERE Id=".$Ligne_Liste_Personne[0];
			$result_etatcivil=mysqli_query($bdd,$requete_etatcivil);
			$row_etatcivil=mysqli_fetch_array($result_etatcivil);
			$Nom=$row_etatcivil[0];
			$Prenom=$row_etatcivil[1];
			$NumBadge=$row_etatcivil['NumBadge'];
			
			$req="SELECT Num_Stamp, Scope FROM new_competences_personne_stamp WHERE Id_Personne=".$Ligne_Liste_Personne[0]." AND (Date_Debut<='0001-01-01' OR (Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))) ";
			$result_stamp=mysqli_query($bdd,$req);
			$nbenreg_stamp=mysqli_num_rows($result_stamp);
			if($nbenreg_stamp>0)
			{
				while($row_stamp=mysqli_fetch_array($result_stamp)){
					if($Stamp<>""){$Stamp.="\n";}
					$Stamp.=$row_stamp['Num_Stamp']." # ".$row_stamp['Scope'];
				}
			}
			
			//Prestation
			$PRESTATION="";
			$requete_prestation="
				SELECT
					new_competences_prestation.Libelle
				FROM
					new_competences_prestation
					LEFT JOIN new_competences_personne_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE
					new_competences_personne_prestation.Id_Personne=".$Ligne_Liste_Personne[0]."
					AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
					AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
			$result_prestation=mysqli_query($bdd,$requete_prestation);
			$nbenreg_prestation=mysqli_num_rows($result_prestation);
			if($nbenreg_prestation>0)
				{while($row_prestation=mysqli_fetch_array($result_prestation)){if($PRESTATION==""){$PRESTATION=$row_prestation[0];}else{$PRESTATION.=" ".$row_prestation[0];}}}
		
			//Metier
			$METIER="";
			$requete_metier="
				SELECT
					new_competences_metier.Libelle FROM new_competences_metier
					LEFT JOIN new_competences_personne_metier ON new_competences_metier.Id=new_competences_personne_metier.Id_Metier
				WHERE
					new_competences_personne_metier.Id_Personne=".$Ligne_Liste_Personne[0]."
                    AND Futur=0
				ORDER BY
					new_competences_personne_metier.Id DESC
				LIMIT 1";
			$result_metier=mysqli_query($bdd,$requete_metier);
			$nbenreg_metier=mysqli_num_rows($result_metier);
			if($nbenreg_metier>0)
				{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.=" ".$row_metier[0];}}}
		
			if($Couleur=="eeeeee"){$Couleur="FFFFFF";}
			else{$Couleur="eeeeee";}
			$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($Nom));
			$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($Prenom));
			
			if(strrpos($METIER,"/")){
				$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode(substr($METIER,strripos($METIER,"/")+2)));
			}
			else{
				$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($METIER));
			}
			
			if($AfficherStamp==1 && $AfficherBadge==1){
				$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($NumBadge));
				$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode($Stamp));
			}
			elseif($AfficherStamp==1){
				$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($Stamp));
			}
			elseif($AfficherBadge==1){
				$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($NumBadge));
			}
			if($_GET['Type']=="Plateforme" && $_GET['Id']=="1"){
			
			}
			else{
				$sheet->getStyle('A'.$ligne.':'.$colonneL.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			}
			$Requete_Ligne_Qualifications="
				SELECT
					*
				FROM
					(
					SELECT
						Id,
						Evaluation,
						Date_Debut,
						Date_Fin,
						Resultat_QCM,
						Date_QCM,
						Date_Surveillance,
						Id_Qualification_Parrainage,
						(
							SELECT
								new_competences_qualification.Libelle
							FROM
								new_competences_qualification
							WHERE
								new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
						) AS LibelleQualif,
						Sans_Fin,(@row_number:=@row_number + 1) AS rnk
					FROM new_competences_relation
					WHERE
						Id_Personne=".$Ligne_Liste_Personne[0]."
						AND Type='Qualification'
                        AND Evaluation <> ''
						AND new_competences_relation.Suppr=0 
						AND new_competences_relation.Visible=0
						AND (Date_Fin>='".$DateJour."' OR Date_Fin<='0001-01-01' OR Sans_Fin='Oui')
					ORDER BY
						Date_QCM DESC
					) AS Toto
				 GROUP BY
					Toto.Id_Qualification_Parrainage";
			$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
				
			//Affichage de la ligne des compétences
			if($AfficherStamp==1 && $AfficherBadge==1){
				$colonne=6;
				$colonneCorpsL="G";
			}
			else{
				$colonne=5;
				$colonneCorpsL="F";
			}
			mysqli_data_seek($Result_Liste_Qualification,0);
			while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification))
			{
				if (mysqli_num_rows($Result_Ligne_Qualifications) > 0)
				{
					mysqli_data_seek($Result_Ligne_Qualifications,0);
					while($Ligne_Qualifications=mysqli_fetch_array($Result_Ligne_Qualifications))
					{
						if($Ligne_Qualifications[7] == $Ligne_Liste_Qualification[0])
						{
							$bgcolor = $Couleur;
							if($Ligne_Qualifications[1] == "L"){$bgcolor="FFFF00";}
							elseif($Ligne_Qualifications[1] == "X" || $Ligne_Qualifications[1] == "S"){$bgcolor="0099FF";}
							elseif($Ligne_Qualifications[1] == "Q" || $Ligne_Qualifications[1] == "Q1" || $Ligne_Qualifications[1] == "Q2" || $Ligne_Qualifications[1] == "Q3"){$bgcolor="00FF00";}
							elseif($Ligne_Qualifications[1] == "T"){$bgcolor="AAAAAA";}
							elseif($Ligne_Qualifications[1] == "B"){$bgcolor="F5A81D";}
							elseif($Ligne_Qualifications[1] == "V"){$bgcolor="DE63FA";}
							elseif($Ligne_Qualifications[1] == "Low" || $Ligne_Qualifications[1] == "Medium" || $Ligne_Qualifications[1] == "High"){$bgcolor="AAAAAA";}
							
							$Lettre=$Ligne_Qualifications[1];
							if($Ligne_Qualifications[1]=="Low"){$Lettre="L";}
							elseif($Ligne_Qualifications[1]=="Medium"){$Lettre="M";}
							elseif($Ligne_Qualifications[1]=="High"){$Lettre="H";}
							
							//Demande modification affichage compétences arrivant à échéances - 16.11.2021 MCAROUX
							if($Ligne_Qualifications[3]>"0001-01-01" && $Ligne_Qualifications['Sans_Fin']=="Non" && $Ligne_Qualifications[3]<=date("Y-m-d", strtotime("+2 month")) && !in_array($Ligne_Qualifications[1],array("B","Bi","Low","Medium","High"))){$bgcolor="ff5050";}
							
							$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode($Lettre));
							$sheet->getStyle($colonneCorpsL.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$bgcolor))));
							break;
						}
					}
				}
				$colonne++;
				$colonneCorpsL++;
			}
			$ligne++;
		}
		$ligne--;
		if($_GET['Type']=="Plateforme" && $_GET['Id']=="1"){}
		else
		{
			$sheet->getStyle('D6:'.$colonneCorpsL.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A6:'.$colonneCorpsL.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '0066cc'))));
			$sheet->getStyle($colonneCorpsL.'6:'.$colonneCorpsL.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_NONE ,'color' => array('rgb' => '0066cc'))));
		}
	}
}
else{
	$ligne=1;
	$colonne=2;
}
$ligne++;
$ligne++;
$colonne--;
if($colonne > 3){$colonne = round($colonne/2);}
else{$colonne =2;}
if($Filiale==0){
	$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode("DOCUMENT QUALITE DIS - Reproduction interdite sans autorisation écrite de DIS"));
}
else{
	$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode("DIS QUALITY DOCUMENT - Reproduction forbidden without written authorization from DIS"));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Tableau_Competences.xlsx"'); 
header('Cache-Control: max-age=0'); 
	
$writer = new PHPExcel_Writer_Excel2007($workbook);

$chemin = '../../tmp/Pointage.xlsx';
$writer->save($chemin);
readfile($chemin);
?>