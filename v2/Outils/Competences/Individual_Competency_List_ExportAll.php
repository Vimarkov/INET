<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once('../ConnexioniSansBody.php');
require_once('../Fonctions.php');

//**********MAIN**********
// creation du fichier et remplissage du contenu
if($_GET['Id'] <> "")
{
    $workbook = new PHPExcel;
    $Ids=explode(";", $_GET['Id']);
    
    foreach($Ids as $Id) {
    	$sheet = $workbook->createSheet();
    	creerWorksheet($sheet, $Id);
    }
    $workbook->removeSheetByIndex();
    enregistrerFichier($workbook);
}
//**********Fonctions**********
function creerWorksheet($sheet, $Id)
{
	global $bdd;
	global $DateJour;
    
    $result=mysqli_query($bdd,"SELECT  Nom, Prenom, NumBadge, Date_Naissance FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$Id);
	$row=mysqli_fetch_array($result);
	$Prenom=$row['Prenom'];
	$Nom=$row['Nom'];
	$NumBadge=$row['NumBadge'];
	$Date_Naissance=$row['Date_Naissance'];

	$sheet->setTitle(utf8_encode(mb_strimwidth($Nom." ".$Prenom, 0, 15)));

	//Logo
	$Logo_Plateforme="";
	$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$Id." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
	$nbenreg=mysqli_num_rows($result);
	if($nbenreg>0)
	{
		$row=mysqli_fetch_array($result);
		$Logo_Plateforme=$row["Logo"];
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

	$sheet->setCellValueByColumnAndRow(2,1,utf8_encode("I N D I V I D U A L   C O M P E T E N C Y   L I S T"));
	$sheet->mergeCells('C1:L1');
	$sheet->getStyle('C1')->getFont()->setSize(25);//Taille du texte
	$sheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$sheet->getStyle('C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00325F'))));
	$sheet->getStyle('C1')->getFont()->getColor()->setRGB('ffffff');

	if($Logo_Plateforme<>""){
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
	
	$sheet->setCellValue("M1",utf8_encode("D-0732-1 \nTemplate Issue 2 \nFeb. 20, 2024"));
	$sheet->getStyle('M1')->getAlignment()->setWrapText(true);
	$sheet->getStyle('M1')->getFont()->getColor()->setRGB('505F69');
	$sheet->getStyle('M1')->getFont()->setSize(10);//Taille du texte
	$sheet->getStyle('M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	//**********************CORPS*****************************//
	//1ere ligne
	$sheet->setCellValueByColumnAndRow(0,3,utf8_encode("OPERATING UNIT"));
	$sheet->setCellValueByColumnAndRow(4,3,utf8_encode("AAA ADDRESS"));
	$sheet->setCellValueByColumnAndRow(12,3,utf8_encode("ARP ID"));

	$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$Id." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
	$nbenreg=mysqli_num_rows($result);
	$Couleur="#FFFFFF";
	if($nbenreg>0)
	{
		$row=mysqli_fetch_array($result);
		$sheet->setCellValueByColumnAndRow(0,4,utf8_encode($row['Libelle']));
		$sheet->setCellValueByColumnAndRow(4,4,utf8_encode($row['Adresse']));
		$sheet->setCellValueByColumnAndRow(12,4,utf8_encode($row['ARP_Id']));
	}
	$sheet->mergeCells('A3:D3');
	$sheet->mergeCells('A4:D4');
	$sheet->mergeCells('E3:L3');
	$sheet->mergeCells('E4:L4');
	$sheet->mergeCells('M3:N3');
	$sheet->mergeCells('M4:N4');
	$sheet->getStyle('A3:N4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A3')->getFont()->getColor()->setRGB('ffffff');
	$sheet->getStyle('A3:N3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A3:N3')->getFont()->getColor()->setRGB('ffffff');

	$sheet->getStyle('A1:N1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
	$sheet->getStyle('A3:A4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));	
	$sheet->getStyle('A3:N4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

	//2eme ligne
	$requeteFonctionMetier="
        SELECT
            new_competences_metier.Libelle,
            new_competences_metier.Fiche
        FROM
            new_competences_personne_metier,
            new_competences_metier
        WHERE
            new_competences_personne_metier.Id_Metier=new_competences_metier.Id
            AND new_competences_personne_metier.Id_Personne=".$Id."
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
	$result=mysqli_query($bdd,"SELECT Num_Stamp, Scope, Date_Debut, Date_Fin FROM new_competences_personne_stamp WHERE Id_Personne=".$Id." ORDER BY Num_Stamp ASC");
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
	$sheet->setCellValueByColumnAndRow(0,6,utf8_encode("NAME"));
	$sheet->setCellValueByColumnAndRow(3,6,utf8_encode("DATE OF BIRTH"));
	$sheet->setCellValueByColumnAndRow(5,6,utf8_encode("JOB/FUNCTION"));
	$sheet->setCellValueByColumnAndRow(7,6,utf8_encode("BADGE"));
	$sheet->setCellValueByColumnAndRow(9,6,utf8_encode("STAMPS NUMBERS"));
	$sheet->getStyle('J7')->getAlignment()->setWrapText(true);
	$sheet->getStyle('F7')->getAlignment()->setWrapText(true);
	$sheet->setCellValueByColumnAndRow(0,7,utf8_encode($Nom." ".$Prenom));
	$sheet->setCellValueByColumnAndRow(3,7,AfficheDateJJ_MM_AAAA($Date_Naissance));
	$sheet->setCellValueByColumnAndRow(5,7,utf8_encode($FonctionMetier));
	$sheet->setCellValueByColumnAndRow(7,7,utf8_encode($NumBadge));
	$sheet->setCellValueByColumnAndRow(9,7,utf8_encode($Stamps));
	$sheet->mergeCells('A6:C6');
	$sheet->mergeCells('A7:C7');
	$sheet->mergeCells('D6:E6');
	$sheet->mergeCells('D7:E7');
	$sheet->mergeCells('F6:G6');
	$sheet->mergeCells('F7:G7');
	$sheet->mergeCells('H6:I6');
	$sheet->mergeCells('H7:I7');
	$sheet->mergeCells('J6:N6');
	$sheet->mergeCells('J7:N7');
	$sheet->getStyle('A6:N7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A6:N6')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A6:N6')->getFont()->getColor()->setRGB('ffffff');
	$sheet->getStyle('A6:N7')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

	//3eme ligne
	$sheet->setCellValueByColumnAndRow(0,9,utf8_encode("ACTIVITIES"));
	$sheet->setCellValueByColumnAndRow(0,10,utf8_encode("Wording"));
	$sheet->setCellValueByColumnAndRow(9,10,utf8_encode("Start date"));
	$sheet->mergeCells('A9:N9');
	$sheet->getStyle('A9:N9')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A9:N9')->getFont()->getColor()->setRGB('ffffff');
	$sheet->mergeCells('A10:I10');
	$sheet->mergeCells('J10:N10');
	$sheet->getStyle('A10:N10')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'999999'))));
	$sheet->getStyle('A10:N10')->getFont()->getColor()->setRGB('ffffff');
	$sheet->getStyle('A10:N10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$requete="SELECT new_competences_prestation.Libelle, new_competences_personne_prestation.Date_Debut, new_competences_prestation.Id";
	$requete.=" FROM new_competences_personne_prestation, new_competences_prestation";
	$requete.=" WHERE new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id";
	$requete.=" AND new_competences_personne_prestation.Id_Personne=".$Id;
	$requete.=" AND new_competences_personne_prestation.Date_Fin >= '".$DateJour."'";
	$requete.=" ORDER BY new_competences_personne_prestation.Date_Debut DESC";
	$result=mysqli_query($bdd,$requete);
	$nbenreg=mysqli_num_rows($result);
	$Couleur="EEEEEE";
	$i=11;
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
			$sheet->getStyle('A'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$i++;
		}
	}
	$i--;
	$sheet->getStyle('A9:N'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

	//4eme ligne
	$i++;
	$i++;
	$j=$i+1;
	$k=$i;
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
	$sheet->getStyle('A'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
        new_competences_relation.Sans_Fin,
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
        AND new_competences_relation.Visible=0
        AND new_competences_relation.Id_Personne=".$Id."
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
	$sheet->getStyle('A'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$i++;
	$i++;

	$Couleur="#EEEEEE";
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
				if($LigneQualification[6]>'0001-01-01'&& $LigneQualification[6]!='0001-01-01'){
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
	$sheet->getStyle('A'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$i++;
	$i++;

	$Couleur="#EEEEEE";
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

	//7eme ligne
	$i=$i+2;
	$j=$i+1;
	$k=$i;
	$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode("OTHERS TRAININGS"));
	$sheet->setCellValueByColumnAndRow(0,$j,utf8_encode("Wording"));
	$sheet->setCellValueByColumnAndRow(12,$j,utf8_encode("Date"));
	$sheet->mergeCells('A'.$i.':N'.$i);
	$sheet->mergeCells('A'.$j.':L'.$j);
	$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'585858'))));
	$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('ffffff');
	$sheet->mergeCells('M'.$j.':N'.$j);
	$sheet->getStyle('A'.$j.':N'.$j)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'999999'))));
	$sheet->getStyle('A'.$j.':N'.$j)->getFont()->getColor()->setRGB('ffffff');
	$sheet->getStyle('A'.$j.':N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$i++;
	$i++;

	$requete="SELECT * FROM new_competences_personne_formation, new_competences_formation";
	$requete.=" WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id";
	$requete.=" AND new_competences_personne_formation.Id_Personne=".$Id;
	$requete.=" ORDER BY new_competences_personne_formation.Type DESC, new_competences_formation.Libelle, new_competences_personne_formation.Date DESC";
	$result=mysqli_query($bdd,$requete);
	$nbenreg=mysqli_num_rows($result);
	$Couleur="#EEEEEE";
	$Libelle="";
	$Categorie="";
	if($nbenreg > 0)
	{
		while($row=mysqli_fetch_array($result))
		{
			if($Categorie!=$row['Type'])
			{
				$Couleur="EEEEEE";
				$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($row['Type']));
				$sheet->mergeCells('A'.$i.':N'.$i);
				$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'a9d0f5'))));
				$sheet->getStyle('A'.$i.':N'.$i)->getFont()->getColor()->setRGB('000000');
				$i++;
			}
			$Categorie=$row['Type'];
			if($Libelle != $row[6])
			{
				if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
				else{$Couleur="EEEEEE";}
				$Libelle=$row[6];

				$sheet->setCellValueByColumnAndRow(0,$i,utf8_encode($Libelle));
				if($row[3]>'0001-01-01' && $row[3]!='0001-01-01'){
					$sheet->setCellValueByColumnAndRow(12,$i,utf8_encode($row[3]));
				}
				$sheet->mergeCells('A'.$i.':L'.$i);
				$sheet->mergeCells('M'.$i.':N'.$i);
				$sheet->getStyle('A'.$i.':N'.$i)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
				$sheet->getStyle('M'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$i++;
			}
		}
	}
	$i--;
	$sheet->getStyle('A'.$k.':N'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

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

}

function enregistrerFichier($workbook) {
	//Enregistrement du fichier excel

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="D-0732.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = new PHPExcel_Writer_Excel2007($workbook);

	$chemin = '../../tmp/D-0732.xlsx';
	$writer->save($chemin);
	readfile($chemin);
}
?>