<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_Matrice.xlsx');
$sheet = $excel->getSheetByName('Matrice');					

$requete="SELECT Id
FROM recrut_annonce
WHERE Suppr=0  
AND EtatRecrutement=1 
AND OuvertureAutresPlateformes=1 ";
if($_SESSION['FiltreRecrutAnnonce_Plateforme']<>0){
$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Metier']<>0){
$requete.=" AND recrut_annonce.Metier LIKE '%".$_SESSION['FiltreRecrutAnnonce_Metier']."%' ";
}
if($_SESSION['FiltreRecrutAnnonce_Domaine']<>0){
$requete.=" AND recrut_annonce.Id_Domaine=".$_SESSION['FiltreRecrutAnnonce_Domaine']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Programme']<>0){
$requete.=" AND recrut_annonce.Programme=".$_SESSION['FiltreRecrutAnnonce_Programme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Etat']<>-2){
$requete.=" AND recrut_annonce.EtatPoste=".$_SESSION['FiltreRecrutAnnonce_Etat']." ";
}
if($_SESSION['FiltreRecrutAnnonce_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutAnnonce_DateDemarrage']<>""){
$requete.=" AND recrut_annonce.DateBesoin".$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutAnnonce_DateDemarrage']."' ";
}
if($_SESSION['FiltreRecrutAnnonce_Information']<>""){
$requete.=" AND (
	(SELECT Libelle FROM recrut_typehoraire WHERE Id=Id_TypeHoraire) LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Horaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Salaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR IGD LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Lieu LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Langue LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
) ";
}
if($_SESSION['FiltreRecrutAnnonce_MesCandidatures']=="1"){
$requete.=" AND (
				SELECT COUNT(recrut_candidature.Id) 
				FROM recrut_candidature 
				WHERE recrut_candidature.Suppr=0
				AND recrut_candidature.Id_Personne=".$_SESSION['Id_Personne']."
				AND recrut_candidature.Id_Annonce=recrut_annonce.Id
				)>0 ";
}
$resultRapport=mysqli_query($bdd,$requete);
$nbRapport=mysqli_num_rows($resultRapport);

if($nbRapport>0){
	while($rowAnnonce=mysqli_fetch_array($resultRapport)){
		$sheet2 = $sheet->copy();

		$sheet2->setTitle("OFFRE_".$rowAnnonce['Id']);
		$excel->addSheet($sheet2);
		
		if($_SESSION["Langue"]=="FR"){
			$reqSuite="IF(EtatRecrutement<>0,'OFFRE','BESOIN') AS Etat, 
				IF(EtatValidation=0,'En attente validation',
					IF(EtatValidation=-1,'Refusé',
						IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
							IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'En attente validation offre',
										IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
									)
								)
							)
						)
					) AS Statut, ";
		}
		else{
			$reqSuite="IF(EtatRecrutement<>0,'OFFER','NEED') AS Etat, 
				IF(EtatValidation=0,'Pending validation',
					IF(EtatValidation=-1,'Refuse',
						IF(EtatValidation=1 && EtatApprobation=0,'Pending approval',
							IF(EtatValidation=1 && EtatApprobation=-1,'Not approved',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'Pending validation offer',
										IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
									)
								)
							)
						)
					) AS Statut, ";
		}
		$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
					".$reqSuite."
					CONCAT(Metier,'-',
					Lieu,'-',
					Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateDemande,'%d%m%y')
					) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,
					EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,IGD,Salaire,
					(SELECT Libelle FROM recrut_categorieprofessionnelle WHERE Id=Id_CategorieProfessionnelle) AS CategorieProfessionnelle,
					DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,DateRecrutement,
					(SELECT Libelle FROM recrut_typehoraire WHERE Id=Id_TypeHoraire) AS TypeHoraire,
					DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,Id_TypeHoraire,Horaire,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
					(SELECT Libelle FROM recrut_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
					(SELECT (SELECT Document FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS DocPlateforme,
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
					FicheMetier AS DocMetier,EtatPoste,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
			FROM recrut_annonce
			WHERE recrut_annonce.Id=".$rowAnnonce['Id'] ;
		$result=mysqli_query($bdd,$requete);
		$row=mysqli_fetch_array($result);

		$sheet2->setCellValue('A3',utf8_encode($row['Metier']));
		$sheet2->setCellValue('B3',utf8_encode($row['CategorieProfessionnelle']));
		$sheet2->setCellValue('C3',utf8_encode($row['Lieu']));
		$sheet2->setCellValue('D3',utf8_encode($row['Plateforme']));
		if($row['PosteDefinitif']==1){
			$sheet2->setCellValue('E3',utf8_encode("Poste définitif"));
		}
		elseif($row['PosteDefinitif']==0){
			$sheet2->setCellValue('E3',utf8_encode("Mission"));
		}
		elseif($row['PosteDefinitif']==2){
			$sheet2->setCellValue('E3',utf8_encode("CDD 6 mois"));
		}
		elseif($row['PosteDefinitif']==3){
			$sheet->setCellValue('E3',utf8_encode("CDD 2 mois"));
		}
		elseif($row['PosteDefinitif']==4){
			$sheet->setCellValue('E3',utf8_encode("CDD"));
		}
		
		$sheet2->setCellValue('F3',utf8_encode($row['CategorieProf']));
		$sheet2->setCellValue('G3',utf8_encode($row['Ref']));

		$req="SELECT Id,Id_Annonce,CandidatRetenu,Id_Personne,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
		Id_Plateforme,
		(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
		(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Suppr,DateSuppr,DateMAJ,
		DateRDV,LEFT(HeureRDV,5) AS HeureRDV, IF(Priorite=0,'',Priorite) AS Priorite,Commentaire
		FROM recrut_candidature 
		WHERE Id_Annonce=".$rowAnnonce['Id']." 
		AND Suppr=0
		ORDER BY DateCreation, HeureCreation ";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		
		$ligne = 6;
		if($nbResulta>0){
			while($row2=mysqli_fetch_array($result)){
				$sheet2->setCellValue('A'.$ligne,utf8_encode($row2['Personne']));
				$sheet2->getStyle('A'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b09fc7'))));
				$ligne++;
			}
		}

		//LISTE DES COMPETENCES 
		$Col="C";
		$sheet2->setCellValue($Col.'5',utf8_encode($row['Langue']));
		if($row['Langue']<>""){$Col++;}

		if($row['SavoirFaire']<>""){
			$lesSavoirFaire=$row['SavoirFaire'];
			$lesSavoirFaire=str_replace("-","**-",$lesSavoirFaire);
			$tab=explode("**",$lesSavoirFaire);
			foreach($tab as $savoirFaire){
				if(substr($savoirFaire,0,1)=="-"){
					$sheet2->setCellValue($Col.'5',utf8_encode(preg_replace("#\n|\t|\r#","",substr($savoirFaire,1))));
					$sheet2->getStyle($Col.'5')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b09fc7'))));
					$sheet2->getStyle($Col.'5:'.$Col.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
					$Col++;
				}
			}
		}

		$req="SELECT recrut_savoiretre.Libelle FROM recrut_annonce_savoiretre LEFT JOIN recrut_savoiretre ON recrut_annonce_savoiretre.Id_SavoirEtre=recrut_savoiretre.Id WHERE Id_Annonce=".$rowAnnonce['Id']." ORDER BY recrut_savoiretre.Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				$sheet2->setCellValue($Col.'5',utf8_encode($rowSE['Libelle']));
				$sheet2->getStyle($Col.'5')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b09fc7'))));
				$sheet2->getStyle($Col.'5:'.$Col.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));	
				$Col++;
			}
		}

		if($row['SavoirEtre']<>""){
			$lesSavoirEtre=$row['SavoirEtre'];
			$lesSavoirEtre=str_replace("-","**-",$lesSavoirEtre);
			$tab=explode("**",$lesSavoirEtre);
			foreach($tab as $savoirEtre){
				if(substr($savoirEtre,0,1)=="-"){
					$sheet2->setCellValue($Col.'5',utf8_encode(preg_replace("#\n|\t|\r#","",substr($savoirEtre,1))));
					$sheet2->getStyle($Col.'5')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b09fc7'))));
					$sheet2->getStyle($Col.'5:'.$Col.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
					$Col++;
				}
			}
		}
	}

$excel->removeSheetByIndex($excel->getIndex($sheet));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Matrice.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/Matrice.xlsx';
$writer->save($chemin);
readfile($chemin);

?>