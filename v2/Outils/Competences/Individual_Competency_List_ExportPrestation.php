<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once('../ConnexioniSansBody.php');
require_once('../Fonctions.php');

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('D-0732');

$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance,CertifyingStaffNumber,CertifyingStaffPrecision FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_GET['Id']);
$row=mysqli_fetch_array($result);
$Prenom=$row['Prenom'];
$Nom=$row['Nom'];
$NumBadge=$row['NumBadge'];
$Date_Naissance=$row['Date_Naissance'];
$CertifyingStaffNumber=$row['CertifyingStaffNumber'];
$CertifyingStaffPrecision=$row['CertifyingStaffPrecision'];
$Filiale=0;

//Logo
$Logo_Plateforme="";
$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$_GET['Id']." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	$row=mysqli_fetch_array($result);
	$Logo_Plateforme=$row["Logo"];
}

//Prestation 
$requete="
	SELECT
		Libelle,CDCRef,CDCTitre,SiteAirbus,Commodity,Produit,Scope,Programme,AfficherDateAnniversaire,
		SousTraitant,SousTraitantAdresse,SousTraitantARP_ID,Id_Plateforme,
		SousTraitantPointFocal,SousTraitantPointFocalTel,SousTraitantPointFocalEmail,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=4
		LIMIT 1) AS RespProjet,
		(SELECT (SELECT TelephoneProMobil FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=4
		LIMIT 1) AS TelRespProjet,
		(SELECT (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=4
		LIMIT 1) AS MailRespProjet,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=5
		LIMIT 1) AS CQP
	FROM
		new_competences_prestation
	WHERE
		Id=".$_GET['Id_Prestation']." ";

$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);
$cdcRef="";
$cdcTitre="";
$siteAirbus="";
$commodity="";
$produit="";
$scope="";
$Libelle="";
$programme="";
$RespProjet="";
$TelRespProjet="";
$EmailRespProjet="";
$AfficherDateAnniversaire="";
$cqp="";
$SousTraitant="";
$SousTraitantAdresse="";
$SousTraitantARP_ID="";
$SousTraitantPointFocal="";
$SousTraitantPointFocalTel="";
$SousTraitantPointFocalEmail="";
if($nbenreg>0)
{
	$row=mysqli_fetch_array($result);
	$Libelle=$row["Libelle"];
	$Libelle=substr($row['Libelle'],0,strpos($row['Libelle']," "));
	if($Libelle==""){$Libelle=$row['Libelle'];}
							
	$cdcRef=$row["CDCRef"];
	$cdcTitre=$row["CDCTitre"];
	$siteAirbus=$row["SiteAirbus"];
	$commodity=$row["Commodity"];
	$produit=$row["Produit"];
	$scope=$row["Scope"];
	$programme=$row["Programme"];
	$RespProjet=$row["RespProjet"];
	$TelRespProjet=$row["TelRespProjet"];
	$EmailRespProjet=$row["MailRespProjet"];
	$cqp=$row["CQP"];
	$AfficherDateAnniversaire=$row["AfficherDateAnniversaire"];
	$SousTraitant=stripslashes($row["SousTraitant"]);
	$SousTraitantAdresse=stripslashes($row["SousTraitantAdresse"]);
	$SousTraitantARP_ID=stripslashes($row["SousTraitantARP_ID"]);
	$SousTraitantPointFocal=stripslashes($row["SousTraitantPointFocal"]);
	$SousTraitantPointFocalTel=stripslashes($row["SousTraitantPointFocalTel"]);
	$SousTraitantPointFocalEmail=stripslashes($row["SousTraitantPointFocalEmail"]);
	
	if($row['Id_Plateforme']==7 || $row['Id_Plateforme']==12 || $row['Id_Plateforme']==16
	|| $row['Id_Plateforme']==18 || $row['Id_Plateforme']==20 || $row['Id_Plateforme']==22
	|| $row['Id_Plateforme']==26 || $row['Id_Plateforme']==30){$Filiale=1;}
}

//Plateforme 
$requete="
	SELECT
		new_competences_plateforme.Libelle,
		new_competences_plateforme.Adresse,
		new_competences_plateforme.ARP_Id,
		new_competences_plateforme.Company,
		new_competences_plateforme.CompanyAdresse
	FROM
		new_competences_prestation
	LEFT JOIN new_competences_plateforme
	ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
	WHERE
		new_competences_prestation.Id=".$_GET['Id_Prestation']." ";
$resultPlateforme=mysqli_query($bdd,$requete);
$nbenregPlateforme=mysqli_num_rows($resultPlateforme);

$plateforme="";
$adresse="";
$arp_id="";
$entreprise="";
$entrepriseAdresse="";
if($nbenregPlateforme>0)
{
	$rowPlateforme=mysqli_fetch_array($resultPlateforme);
	$plateforme=stripslashes($rowPlateforme["Libelle"]);				
	$adresse=stripslashes($rowPlateforme["Adresse"]);
	$arp_id=stripslashes($rowPlateforme["ARP_Id"]);
	$entreprise=stripslashes($rowPlateforme["Company"]);
	$entrepriseAdresse=stripslashes($rowPlateforme["CompanyAdresse"]);
}

$sheet->getStyle('A:O')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffffff'))));

//*****************EN TETE**********************************//
$sheet->getRowDimension('1')->setRowHeight(80);
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(13);
$sheet->getColumnDimension('F')->setWidth(13);
$sheet->getColumnDimension('G')->setWidth(13);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(13);
$sheet->getColumnDimension('J')->setWidth(13);
$sheet->getColumnDimension('K')->setWidth(13);
$sheet->getColumnDimension('L')->setWidth(13);
$sheet->getColumnDimension('M')->setWidth(10);
$sheet->getColumnDimension('N')->setWidth(10);
$sheet->mergeCells('A1:B1');
$sheet->mergeCells('M1:N1');

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../../Images/Logos/Logo Daher_posi.png');
$objDrawing->setHeight(70);
$objDrawing->setWidth(130);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(10);
$objDrawing->setOffsetY(8);
$objDrawing->setWorksheet($sheet);

$sheet->setCellValueByColumnAndRow(2,1,utf8_encode("INDIVIDUAL COMPETENCY LIST - ICL/IDS"));
$sheet->mergeCells('C1:L1');
$sheet->getStyle('C1')->getFont()->setSize(15);//Taille du texte
$sheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->getStyle('C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00325F'))));
$sheet->getStyle('C1')->getFont()->getColor()->setRGB('ffffff');

 if($Logo_Plateforme != "")
 {
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Logos/'.$Logo_Plateforme);
	$objDrawing->setWidth(100);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(50);
	$objDrawing->setWorksheet($sheet);
}

$MoisLettre = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sept.", "Oct.", "Nov.", "Dec.");
$sheet->setCellValue("A2",utf8_encode("Content updated on : ".$MoisLettre[date('m')-1].date(' d, Y')));



$sheet->setCellValueByColumnAndRow(7,2,utf8_encode("FOR INFORMATION"));


$sheet->getStyle('A1:N1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

$sheet->setCellValue("M1",utf8_encode("D-0732-1 \nTemplate Issue 2 \nFeb. 20, 2024"));
$sheet->getStyle('M1')->getAlignment()->setWrapText(true);
$sheet->getStyle('M1')->getFont()->getColor()->setRGB('505F69');
$sheet->getStyle('M1')->getFont()->setSize(10);//Taille du texte
$sheet->getStyle('M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//**********************CORPS*****************************//
//1ere ligne
$ligne=3;

$sheet->setCellValueByColumnAndRow(11,$ligne,utf8_encode("REF DOC"));
$sheet->setCellValueByColumnAndRow(11,($ligne+1),utf8_encode("ICL-".$Libelle));

$sheet->setCellValueByColumnAndRow(13,$ligne,utf8_encode("Issue Doc"));
$sheet->setCellValueByColumnAndRow(13,($ligne+1),utf8_encode("1"));

$sheet->getStyle('L'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('L'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('L'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));	
$sheet->mergeCells('L'.$ligne.':M'.$ligne);
$sheet->mergeCells('L'.($ligne+1).':M'.($ligne+1));
$sheet->getStyle('N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$ligne=$ligne+3;

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("SUPPLIER COMPANY"));
$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode("SUPPLIER COMPANY ADDRESS"));

$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$_GET['Id']." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
$nbenreg=mysqli_num_rows($result);
$Couleur="#FFFFFF";
if($nbenreg>0)

$sheet->setCellValueByColumnAndRow(0,$ligne+1,utf8_encode($entreprise));
$sheet->setCellValueByColumnAndRow(4,$ligne+1,utf8_encode($entrepriseAdresse));


$sheet->mergeCells('A'.$ligne.':D'.$ligne);
$sheet->mergeCells('A'.($ligne+1).':D'.($ligne+1));

$sheet->mergeCells('E'.$ligne.':N'.$ligne);
$sheet->mergeCells('E'.($ligne+1).':N'.($ligne+1));

$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+2;

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("OPERATING UNIT"));
$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode("AAA ADDRESS"));
$sheet->setCellValueByColumnAndRow(12,$ligne,utf8_encode("ARP ID"));


$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($plateforme));
$sheet->setCellValueByColumnAndRow(4,($ligne+1),utf8_encode($adresse));
$sheet->setCellValueByColumnAndRow(12,($ligne+1),utf8_encode($arp_id));


$sheet->mergeCells('A'.$ligne.':D'.$ligne);
$sheet->mergeCells('A'.($ligne+1).':D'.($ligne+1));

$sheet->mergeCells('E'.$ligne.':L'.$ligne);
$sheet->mergeCells('E'.($ligne+1).':L'.($ligne+1));
$sheet->mergeCells('M'.$ligne.':N'.$ligne);
$sheet->mergeCells('M'.($ligne+1).':N'.($ligne+1));

$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+2;

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("SUPPLIER FOCAL POINT"));

$sheet->setCellValueByColumnAndRow(0,$ligne+1,utf8_encode($RespProjet));
$sheet->setCellValueByColumnAndRow(4,$ligne+1,utf8_encode($TelRespProjet));
$sheet->setCellValueByColumnAndRow(12,$ligne+1,utf8_encode($EmailRespProjet));

$sheet->mergeCells('A'.$ligne.':N'.$ligne);
$sheet->mergeCells('A'.($ligne+1).':D'.($ligne+1));
$sheet->mergeCells('E'.($ligne+1).':L'.($ligne+1));
$sheet->mergeCells('M'.($ligne+1).':N'.($ligne+1));

$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+3;

$requeteFonctionMetier="
    SELECT
        new_competences_metier.Libelle,
        new_competences_metier.Fiche
    FROM
        new_competences_personne_metier,
        new_competences_metier
    WHERE
        new_competences_personne_metier.Id_Metier=new_competences_metier.Id
        AND new_competences_personne_metier.Id_Personne=".$_GET['Id']."
    ORDER BY
        new_competences_personne_metier.Id DESC";
$FonctionMetier="";
$result=mysqli_query($bdd,$requeteFonctionMetier);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	while($row=mysqli_fetch_array($result))
	{
		if($FonctionMetier<>""){$FonctionMetier.=" ";}
        $FonctionMetier.= $row['Libelle'];
	}
}

$Stamps="";
$result=mysqli_query($bdd,"SELECT Num_Stamp, Scope, Date_Debut, Date_Fin FROM new_competences_personne_stamp WHERE Id_Personne=".$_GET['Id']." AND Date_Debut<='".$DateJour."' AND (Date_Fin>='".$DateJour."' OR Date_Fin<='0001-01-01') ORDER BY Num_Stamp ASC");
$nbenreg=mysqli_num_rows($result);
$Couleur="#EEEEEE";
if($nbenreg>0)
{
	while($row=mysqli_fetch_array($result))
	{
		if($Stamps<>""){$Stamps.=" | ";}
		$Stamps.=$row['Num_Stamp']." # ".$row['Scope'];
		if(($row['Date_Debut']>'0001-01-01' ) || ($row['Date_Fin']>'0001-01-01' )){
			$Stamps.= " (".AfficheDateJJ_MM_AAAA($row['Date_Debut'])." - ".AfficheDateJJ_MM_AAAA($row['Date_Fin']).")";
		}
	}
}


$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("NAME OF THE INTERVENER"));
if($AfficherDateAnniversaire==1){
	$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode("DATE OF BIRTH"));
}
$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode("JOB/FUNCTION"));
$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode("BADGE"));
$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode("STAMPS NUMBERS"));
$sheet->getStyle('J'.($ligne+1))->getAlignment()->setWrapText(true);
$sheet->getStyle('F'.($ligne+1))->getAlignment()->setWrapText(true);

if($_GET['Affiche']=="Nom"){
	$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($Nom." ".$Prenom));
}
else{
	$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode("AAA-".$_GET['Id']));
}
if($AfficherDateAnniversaire==1){
	$sheet->setCellValueByColumnAndRow(3,($ligne+1),AfficheDateJJ_MM_AAAA($Date_Naissance));
}
$sheet->setCellValueByColumnAndRow(5,($ligne+1),utf8_encode($FonctionMetier));
$sheet->setCellValueByColumnAndRow(7,($ligne+1),utf8_encode($NumBadge));
$sheet->setCellValueByColumnAndRow(9,($ligne+1),utf8_encode($Stamps));

if($AfficherDateAnniversaire==1){
	$sheet->mergeCells('A'.$ligne.':C'.$ligne);
	$sheet->mergeCells('A'.($ligne+1).':C'.($ligne+1));
	$sheet->mergeCells('D'.$ligne.':E'.$ligne);
	$sheet->mergeCells('D'.($ligne+1).':E'.($ligne+1));
}
else{
	$sheet->mergeCells('A'.$ligne.':E'.$ligne);
	$sheet->mergeCells('A'.($ligne+1).':E'.($ligne+1));
}
$sheet->mergeCells('F'.$ligne.':G'.$ligne);
$sheet->mergeCells('F'.($ligne+1).':G'.($ligne+1));
$sheet->mergeCells('H'.$ligne.':I'.$ligne);
$sheet->mergeCells('H'.($ligne+1).':I'.($ligne+1));
$sheet->mergeCells('J'.$ligne.':N'.$ligne);
$sheet->mergeCells('J'.($ligne+1).':N'.($ligne+1));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('J'.($ligne+1))->getAlignment()->setWrapText(true);


//3eme ligne

$ligne=$ligne+3;
$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("ACTIVITIES"));
$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode("Wording"));
$sheet->setCellValueByColumnAndRow(9,($ligne+1),utf8_encode("Start date"));
$sheet->mergeCells('A'.$ligne.':N'.$ligne);
$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');
$sheet->mergeCells('A'.($ligne+1).':I'.($ligne+1));
$sheet->mergeCells('J'.($ligne+1).':N'.($ligne+1));
$sheet->getStyle('A'.($ligne+1).':N'.($ligne+1))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'999999'))));
$sheet->getStyle('A'.($ligne+1).':N'.($ligne+1))->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('B'.($ligne+1).':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$requete="
    SELECT
        new_competences_prestation.Libelle,
        new_competences_personne_prestation.Date_Debut,
        new_competences_prestation.Id
    FROM
        new_competences_personne_prestation, new_competences_prestation
    WHERE
        new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
        AND new_competences_personne_prestation.Id_Personne=".$_GET['Id']."
		AND new_competences_personne_prestation.Id=".$_GET['Id_PrestaPers']."
    ORDER BY
        new_competences_personne_prestation.Date_Debut DESC";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);
$Couleur="EEEEEE";
$i=$ligne+2;
if($nbenreg>0)
{
	while($row=mysqli_fetch_array($result))
	{
		if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
		else{$Couleur="EEEEEE";}
		$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($row[0]));
		$sheet->setCellValueByColumnAndRow(9,$i,utf8_encode($row[1]));
		$sheet->mergeCells('A'.$i.':I'.$i);
		$sheet->mergeCells('J'.$i.':N'.$i);
		$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		$sheet->getStyle('B'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$i++;
	}
}
$i--;
$sheet->getStyle('A'.$ligne.':N'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

$ligne=$i+2;

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("WORK SPECIFICATION REF"));
$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode("WORK SPECIFICATION TITLE/DESCRIPTION (if needed)"));

$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($cdcRef));
$sheet->setCellValueByColumnAndRow(4,($ligne+1),utf8_encode($cdcTitre));

$sheet->mergeCells('A'.$ligne.':D'.$ligne);
$sheet->mergeCells('A'.($ligne+1).':D'.($ligne+1));

$sheet->mergeCells('E'.$ligne.':N'.$ligne);
$sheet->mergeCells('E'.($ligne+1).':N'.($ligne+1));

$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+2;

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("INTERVENTION SITE"));

$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($siteAirbus));

$sheet->mergeCells('A'.$ligne.':N'.$ligne);
$sheet->mergeCells('A'.($ligne+1).':N'.($ligne+1));

$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+3;

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("COMMODITY"));
$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode("PRODUCT"));
$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode("SCOPE"));
$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode("PROGRAM"));

$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($commodity));
$sheet->setCellValueByColumnAndRow(3,($ligne+1),utf8_encode($produit));
$sheet->setCellValueByColumnAndRow(6,($ligne+1),utf8_encode($scope));
$sheet->setCellValueByColumnAndRow(9,($ligne+1),utf8_encode($programme));

$sheet->mergeCells('A'.$ligne.':C'.$ligne);
$sheet->mergeCells('D'.$ligne.':F'.$ligne);
$sheet->mergeCells('G'.$ligne.':I'.$ligne);
$sheet->mergeCells('J'.$ligne.':N'.$ligne);

$req="SELECT Id,Commodity, Product FROM new_competences_prestation_parametrage WHERE Suppr=0 AND Id_Prestation=".$_GET['Id_Prestation']." ";
$resultParam=mysqli_query($bdd,$req);
$nbParam=mysqli_num_rows($resultParam);
$nbLigne=0;
if($nbParam>0)
{
	$nbLigne=1;
	while($rowParam=mysqli_fetch_array($resultParam))
	{
		$sheet->setCellValueByColumnAndRow(0,($ligne+$nbLigne),utf8_encode(stripslashes($rowParam['Commodity'])));
		$sheet->setCellValueByColumnAndRow(3,($ligne+$nbLigne),utf8_encode(stripslashes($rowParam['Product'])));
		
		$scope="";
		$req="SELECT Info,Autre
			FROM new_competences_prestation_parametrage_detail 
		WHERE Suppr=0 
		AND Type='Scope' 
		AND Id_PrestationParametrage=".$rowParam['Id']." ";
		$resultParamD=mysqli_query($bdd,$req);
		$nbParamD=mysqli_num_rows($resultParamD);
		if($nbParamD>0)
		{
			$k=0;
			while($rowParamD=mysqli_fetch_array($resultParamD))
			{
				if($k>0){$scope.= " | ";}
				$scope.= $rowParamD['Info'];
				if($rowParamD['Autre']<>""){$scope.= " (".stripslashes($rowParamD['Autre']).")";}
				$k++;
			}
		}
		
		$sheet->setCellValueByColumnAndRow(6,($ligne+$nbLigne),utf8_encode($scope));
		
		$programme="";
		$req="SELECT Info 
		FROM new_competences_prestation_parametrage_detail 
		WHERE Suppr=0 
		AND Type='Program' 
		AND Id_PrestationParametrage=".$rowParam['Id']." ";
		$resultParamD=mysqli_query($bdd,$req);
		$nbParamD=mysqli_num_rows($resultParamD);
		if($nbParamD>0)
		{
			$k=0;
			while($rowParamD=mysqli_fetch_array($resultParamD))
			{
				if($k>0){$programme.= " | ";}
				$programme.= $rowParamD['Info'];
				$k++;
			}
		}
		$sheet->setCellValueByColumnAndRow(9,($ligne+$nbLigne),utf8_encode($programme));
		
		$sheet->mergeCells('A'.($ligne+$nbLigne).':C'.($ligne+$nbLigne));
		$sheet->mergeCells('D'.($ligne+$nbLigne).':F'.($ligne+$nbLigne));
		$sheet->mergeCells('G'.($ligne+$nbLigne).':I'.($ligne+$nbLigne));
		$sheet->mergeCells('J'.($ligne+$nbLigne).':N'.($ligne+$nbLigne));

		$nbLigne++;
	}
}
$sheet->getStyle('A'.$ligne.':N'.($ligne+$nbLigne-1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+$nbLigne-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+$nbLigne;
$ligne=$ligne+2;

if($SousTraitant<>""){
	$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("CUSTOMER COMPANY"));
	$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode("CUSTOMER COMPANY ADDRESS"));

	$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($SousTraitant));
	$sheet->setCellValueByColumnAndRow(4,($ligne+1),utf8_encode($SousTraitantAdresse));

	$sheet->mergeCells('A'.$ligne.':D'.$ligne);
	$sheet->mergeCells('A'.($ligne+1).':D'.($ligne+1));

	$sheet->mergeCells('E'.$ligne.':L'.$ligne);
	$sheet->mergeCells('E'.($ligne+1).':L'.($ligne+1));

	$sheet->mergeCells('M'.$ligne.':N'.$ligne);
	$sheet->mergeCells('M'.($ligne+1).':N'.($ligne+1));

	$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
	$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

	$ligne=$ligne+2;
	
	$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("CUSTOMER FOCAL POINT"));

	$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode($SousTraitantPointFocal));
	$sheet->setCellValueByColumnAndRow(4,($ligne+1),utf8_encode($SousTraitantPointFocalTel));
	$sheet->setCellValueByColumnAndRow(12,($ligne+1),utf8_encode($SousTraitantPointFocalEmail));

	$sheet->mergeCells('A'.$ligne.':N'.$ligne);
	$sheet->mergeCells('A'.($ligne+1).':D'.($ligne+1));
	$sheet->mergeCells('E'.($ligne+1).':L'.($ligne+1));
	$sheet->mergeCells('M'.($ligne+1).':N'.($ligne+1));

	$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
	$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

	$ligne=$ligne+3;
}

$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode("Certifying Staff"));
$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode("Certifying Staff number"));
$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode("Authorization to sign"));
$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode("Authorization to sign"));

if($CertifyingStaffNumber<>""){
	$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode("Yes"));
}
else{
	$sheet->setCellValueByColumnAndRow(0,($ligne+1),utf8_encode("No")); 
}
$sheet->setCellValueByColumnAndRow(2,($ligne+1),utf8_encode($CertifyingStaffNumber));
$result=mysqli_query($bdd,"SELECT AutorisationSign FROM new_competences_personne_certifying WHERE Id_Personne=".$_GET['Id']." ORDER BY AutorisationSign ASC");
$nbenreg=mysqli_num_rows($result);
$autorisation="";
if($nbenreg>0)
{
	$k=0;
	while($rowAuto=mysqli_fetch_array($result))
	{
		if($k>0){$autorisation.= " | ";}
		$autorisation.=$rowAuto['AutorisationSign'];
		$k++;
	}
}

$sheet->setCellValueByColumnAndRow(4,($ligne+1),utf8_encode($autorisation));
$sheet->setCellValueByColumnAndRow(9,($ligne+1),utf8_encode($CertifyingStaffPrecision));


$sheet->mergeCells('A'.$ligne.':B'.$ligne);
$sheet->mergeCells('A'.($ligne+1).':B'.($ligne+1));

$sheet->mergeCells('C'.$ligne.':D'.$ligne);
$sheet->mergeCells('C'.($ligne+1).':D'.($ligne+1));

$sheet->mergeCells('E'.$ligne.':I'.$ligne);
$sheet->mergeCells('E'.($ligne+1).':I'.($ligne+1));

$sheet->mergeCells('J'.$ligne.':N'.$ligne);
$sheet->mergeCells('J'.($ligne+1).':N'.($ligne+1));

$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$ligne.':N'.($ligne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A'.$ligne.':N'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$ligne.':N'.$ligne)->getFont()->getColor()->setRGB('ffffff');

$ligne=$ligne+3;

//4eme ligne
$i=$ligne;
$j=$ligne+1;
$k=$ligne;
$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode("JOB VALIDATION"));
$sheet->setCellValueByColumnAndRow(0,$j,utf8_encode("Wording"));
$sheet->setCellValueByColumnAndRow(8,$j,utf8_encode("Start date"));
$sheet->setCellValueByColumnAndRow(9,$j,utf8_encode("End date"));
$sheet->setCellValueByColumnAndRow(10,$j,utf8_encode("B/L/V"));
$sheet->setCellValueByColumnAndRow(11,$j,utf8_encode("Score"));
$sheet->setCellValueByColumnAndRow(12,$j,utf8_encode("QCM date"));
$sheet->mergeCells('A'.$i.':N'.$i);
$sheet->mergeCells('A'.$j.':H'.$j);
$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('ffffff');
$sheet->mergeCells('M'.$j.':N'.$j);
$sheet->getStyle('A'.$j.':N'.$j)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'999999'))));
$sheet->getStyle('A'.$j.':N'.$j)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('B'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$i++;
$i++;

$requete_Deb="
    SELECT
        DISTINCT new_competences_qualification.Libelle,
        new_competences_relation.Date_Debut,
        new_competences_relation.Date_Fin,
        new_competences_relation.Resultat_QCM,
        new_competences_relation.Evaluation,
        new_competences_relation.Date_QCM,
        new_competences_relation.Date_Surveillance,
        IF(new_competences_relation.Sans_Fin='Oui',new_competences_relation.Sans_Fin,IF(Date_Fin>'0001-01-01','Non',IF(new_competences_qualification.Duree_Validite=0 && Evaluation<>'B' && Evaluation<>'Bi' && Evaluation<>'','Oui','Non'))) AS Sans_Fin,
        new_competences_categorie_qualification.Libelle,
        new_competences_relation.QCM_Surveillance
    FROM
        new_competences_relation,
        new_competences_qualification,
        new_competences_categorie_qualification
    WHERE
        new_competences_relation.Type='Qualification'
        AND new_competences_relation.Suppr=0
        AND new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
        AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
        AND
        (
            new_competences_relation.Date_Fin<='0001-01-01'
            OR new_competences_relation.Date_Fin >= '".$DateJour."'
        )
		AND new_competences_relation.Id_Qualification_Parrainage IN (
			SELECT Id_Qualification 
			FROM new_competences_prestation_qualification
			WHERE Id_Prestation=".$_GET['Id_Prestation']."
		)
        AND new_competences_relation.Visible=0
        AND new_competences_relation.Id_Personne=".$_GET['Id']."
        AND new_competences_categorie_qualification.Id_Categorie_Maitre=";
$requete_Fin="
        ORDER BY
            new_competences_categorie_qualification.Libelle ASC,
            new_competences_qualification.Libelle ASC,
            new_competences_relation.Date_QCM DESC,
            new_competences_relation.Date_Debut DESC";

$Couleur="EEEEEE";
$Requete_Categorie="1";
$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
$nbenreg=mysqli_num_rows($result);
$Categorie="";
$Libelle="";
$Evalution="";
if($nbenreg > 0)
{
	while($LigneQualification=mysqli_fetch_array($result))
	{
		if($Categorie != $LigneQualification[8])
		{
			$Categorie=$LigneQualification[8];
			$Couleur="EEEEEE";
			$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Categorie));
			$sheet->mergeCells('A'.$i.':N'.$i);
			$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'a9d0f5'))));
			$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('000000');
			$i++;
		}
		if($Libelle != $LigneQualification[0]
		|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
		|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
		)
		{
			$Libelle=$LigneQualification[0];
			$Evalution=$LigneQualification['Evaluation'];
			if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
			else{$Couleur="EEEEEE";}

			$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Libelle));
			if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(8,$i,utf8_encode($LigneQualification[1]));
			}
			if($LigneQualification[7]=='Oui'){
				$sheet->setCellValueByColumnAndRow(9,$i,utf8_encode("Sans limite"));
			}
			elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(9,$i,utf8_encode($LigneQualification[2]));
			}
			$sheet->setCellValueByColumnAndRow(10,$i,utf8_encode($LigneQualification[4]));
			$sheet->setCellValueByColumnAndRow(11,$i,utf8_encode($LigneQualification[3]));
			if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(12,$i,utf8_encode( $LigneQualification[5]));
			}
			$sheet->mergeCells('A'.$i.':H'.$i);
			$sheet->mergeCells('M'.$i.':N'.$i);
			$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			$sheet->getStyle('I'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$i++;
		}
	}
}
$i--;
$sheet->getStyle('A'.$k.':N'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
	
//5eme ligne
$i=$i+2;
$j=$i+1;
$k=$i;
$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode("SPECIAL PROCESSES"));
$sheet->setCellValueByColumnAndRow(0,$j,utf8_encode("Wording"));
$sheet->setCellValueByColumnAndRow(7,$j,utf8_encode("Start date"));
$sheet->setCellValueByColumnAndRow(8,$j,utf8_encode("End date"));
$sheet->setCellValueByColumnAndRow(9,$j,utf8_encode("B/L/Q/S/T"));
$sheet->setCellValueByColumnAndRow(10,$j,utf8_encode("Score"));
$sheet->setCellValueByColumnAndRow(11,$j,utf8_encode("QCM Date"));
$sheet->setCellValueByColumnAndRow(12,$j,utf8_encode("Monitoring score"));
$sheet->setCellValueByColumnAndRow(13,$j,utf8_encode("Monitoring date"));
$sheet->getStyle('M'.$j)->getAlignment()->setWrapText(true);
$sheet->getStyle('N'.$j)->getAlignment()->setWrapText(true);
$sheet->mergeCells('A'.$i.':N'.$i);
$sheet->mergeCells('A'.$j.':G'.$j);
$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('A'.$j.':N'.$j)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'999999'))));
$sheet->getStyle('A'.$j.':N'.$j)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('B'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$i++;
$i++;

$Requete_Categorie="2";
$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
$nbenreg=mysqli_num_rows($result);
$Categorie="";
$Libelle="";
$Evalution="";
if($nbenreg > 0)
{
	while($LigneQualification=mysqli_fetch_array($result))
	{
		if($Categorie != $LigneQualification[8])
		{
			$Categorie=$LigneQualification[8];
			$Couleur="EEEEEE";
			$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Categorie));
			$sheet->mergeCells('A'.$i.':N'.$i);
			$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'a9d0f5'))));
			$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('000000');
			$i++;
		}
		if($Libelle != $LigneQualification[0]
		|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
		|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
		)
		{
			$Libelle=$LigneQualification[0];
			$Evalution=$LigneQualification['Evaluation'];
			if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
			else{$Couleur="EEEEEE";}

			$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Libelle));
			if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(7,$i,utf8_encode($LigneQualification[1]));
			}
			if($LigneQualification[7]=='Oui'){
				$sheet->setCellValueByColumnAndRow(8,$i,utf8_encode("Sans limite"));
			}
			elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(8,$i,utf8_encode($LigneQualification[2]));
			}
			$sheet->setCellValueByColumnAndRow(9,$i,utf8_encode($LigneQualification[4]));
			$sheet->setCellValueByColumnAndRow(10,$i,utf8_encode($LigneQualification[3]));
			if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(11,$i,utf8_encode( $LigneQualification[5]));
			}
			$sheet->setCellValueByColumnAndRow(12,$i,utf8_encode($LigneQualification[9]));
			if($LigneQualification[6]>'0001-01-01' && $LigneQualification[6]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(13,$i,utf8_encode( $LigneQualification[6]));
			}
			$sheet->mergeCells('A'.$i.':G'.$i);
			$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			$sheet->getStyle('H'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$i++;
		}
	}
}
$i--;
$sheet->getStyle('A'.$k.':N'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

//6eme ligne
$i=$i+2;
$j=$i+1;
$k=$i;
$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode("NO SPECIAL PROCESSES / SPECIFIC COMPETENCIES"));
$sheet->setCellValueByColumnAndRow(0,$j,utf8_encode("Wording"));
$sheet->setCellValueByColumnAndRow(8,$j,utf8_encode("Start date"));
$sheet->setCellValueByColumnAndRow(9,$j,utf8_encode("End date"));
$sheet->setCellValueByColumnAndRow(10,$j,utf8_encode("B/L/V"));
$sheet->setCellValueByColumnAndRow(11,$j,utf8_encode("Score"));
$sheet->setCellValueByColumnAndRow(12,$j,utf8_encode("QCM date"));
$sheet->mergeCells('A'.$i.':N'.$i);
$sheet->mergeCells('A'.$j.':H'.$j);
$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('ffffff');
$sheet->mergeCells('M'.$j.':N'.$j);
$sheet->getStyle('A'.$j.':N'.$j)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'999999'))));
$sheet->getStyle('A'.$j.':N'.$j)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('B'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$i++;
$i++;

$Requete_Categorie="3";
$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
$nbenreg=mysqli_num_rows($result);
$Categorie="";
$Libelle="";
$Evalution="";
if($nbenreg > 0)
{
	while($LigneQualification=mysqli_fetch_array($result))
	{
		if($Categorie != $LigneQualification[8])
		{
			$Categorie=$LigneQualification[8];
			$Couleur="EEEEEE";
			$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Categorie));
			$sheet->mergeCells('A'.$i.':N'.$i);
			$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'a9d0f5'))));
			$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('000000');
			$i++;
		}
		if($Libelle != $LigneQualification[0]
		|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
		|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
		)
		{
			$Libelle=$LigneQualification[0];
			$Evalution=$LigneQualification['Evaluation'];
			if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
			else{$Couleur="EEEEEE";}

			$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Libelle));
			if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(8,$i,utf8_encode($LigneQualification[1]));
			}
			if($LigneQualification[7]=='Oui'){
				$sheet->setCellValueByColumnAndRow(9,$i,utf8_encode("Sans limite"));
			}
			elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(9,$i,utf8_encode($LigneQualification[2]));
			}
			$sheet->setCellValueByColumnAndRow(10,$i,utf8_encode($LigneQualification[4]));
			$sheet->setCellValueByColumnAndRow(11,$i,utf8_encode($LigneQualification[3]));
			if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){
				$sheet->setCellValueByColumnAndRow(12,$i,utf8_encode( $LigneQualification[5]));
			}
			$sheet->mergeCells('A'.$i.':H'.$i);
			$sheet->mergeCells('M'.$i.':N'.$i);
			$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
			$sheet->getStyle('I'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$i++;
		}
	}
}
$i--;
$sheet->getStyle('A'.$k.':N'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

$i=$i+2;

$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode("INTERVENER"));
$sheet->setCellValueByColumnAndRow(11,$i,utf8_encode("QUALITY"));

if($_GET['Affiche']=="Nom"){
	$sheet->setCellValueByColumnAndRow(0,($i+1),utf8_encode($Nom." ".$Prenom));
}
else{
	$sheet->setCellValueByColumnAndRow(0,($i+1),utf8_encode("AAA-".$_GET['Id']));
}
$sheet->setCellValueByColumnAndRow(11,($i+1),utf8_encode($cqp));


$sheet->mergeCells('A'.$i.':K'.$i);
$sheet->mergeCells('A'.($i+1).':K'.($i+1));
$sheet->mergeCells('L'.$i.':N'.$i);
$sheet->mergeCells('L'.($i+1).':N'.($i+1));

$sheet->getStyle('A'.$i.':N'.($i+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
$sheet->getStyle('A'.$i.':N'.($i+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('ffffff');

$i=$i+2;
$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode("S02 AAA Group Process ensures that the intervener and its quality are aware of the trainings and qualifications as he signed at least and each time a training / qualification evidence"));
$sheet->mergeCells('A'.$i.':N'.$i);

$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('A'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//8eme ligne
$i=$i+2;
$j=$i+1;
$k=$i;

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Legende');
$objDrawing->setDescription('PHPExcel legende');
$objDrawing->setPath('../../Images/Legende_GPEC2.png');
$objDrawing->setCoordinates('E'.$i);
$objDrawing->setOffsetX(10);
$objDrawing->setOffsetY(10);
$objDrawing->setWorksheet($sheet);

//PIED DE PAGE
$r = chr(13); 
$sheet->getHeaderFooter()->setOddFooter('&C' .'DIS QUALITY DOCUMENT - Reproduction forbidden without written authorization from DIS');

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="D-0732.xlsx"'); 
header('Cache-Control: max-age=0'); 
	
$writer = new PHPExcel_Writer_Excel2007($workbook);

$chemin = '../../tmp/D-0732.xlsx';
$writer->save($chemin);
readfile($chemin);
?>