<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT sp_olwficheintervention.Id,
sp_olwficheintervention.Id_Dossier,
sp_olwdossier.MSN,sp_olwdossier.Titre,
(SELECT Libelle FROM sp_client WHERE Id=Id_Client) AS Client,sp_olwdossier.TypeACP AS TypeDossier,
CONCAT(IF(Ajusteur=1,'Ajustage<br>',''),IF(Elec=1,'Elec<br>',''),IF(Meca=1,'Meca','')) AS TypeTravail,
sp_olwdossier.DateCreation,sp_olwdossier.Reference,sp_olwdossier.DateDossier,sp_olwdossier.HeureDossier,
sp_olwdossier.CaecACP,sp_olwdossier.Priorite,sp_olwdossier.CodeUsine,sp_olwdossier.TAI_RestantACP,sp_olwdossier.Programme,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS CreateurDossier,
sp_olwdossier.Ajusteur,sp_olwdossier.Elec,sp_olwdossier.Meca,
sp_olwficheintervention.CommentairePROD,
sp_olwficheintervention.CommentaireQUALITE,
sp_olwficheintervention.DateTERA,
sp_olwficheintervention.DateTERC,
sp_olwficheintervention.TempsProd,
sp_olwficheintervention.NumFI,
sp_olwdossier.Id_StatutPREPA,
(SELECT sp_localisation.Libelle FROM sp_localisation WHERE sp_localisation.Id=sp_olwdossier.Id_ZoneDeTravail) AS Localisation,
(SELECT sp_poste.Libelle FROM sp_poste WHERE sp_poste.Id=sp_olwdossier.Id_Poste) AS Poste,
sp_olwdossier.DatePrevisionnelleIntervention,sp_olwficheintervention.Vacation AS Vacation2,
	sp_olwficheintervention.DateIntervention,
(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,
(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,
sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.VacationQ,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS IQ,
sp_olwficheintervention.Id_StatutPROD AS StatutPROD,sp_olwficheintervention.Id_StatutQUALITE AS StatutQUALITE ";

$req="FROM sp_olwficheintervention 
LEFT JOIN sp_olwdossier 
ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id

WHERE sp_olwdossier.Id_Prestation=1792 AND ";

if($_SESSION['FiltreMSNPage']<>""){
	$req.="sp_olwdossier.MSN=".$_SESSION['FiltreMSNPage']." AND ";
}
if($_SESSION['FiltreProgrammePage']<>""){
	$req.="sp_olwdossier.Programme='".$_SESSION['FiltreProgrammePage']."' AND ";
}
if($_SESSION['FiltreReferencePage']<>""){
	$req.="sp_olwdossier.Reference='".$_SESSION['FiltreReferencePage']."' AND ";
}
if($_SESSION['FiltreDateInterventionPage']<>""){
	$req.="sp_olwficheintervention.DateIntervention='".TrsfDate_($_SESSION['FiltreDateInterventionPage'])."' AND ";
}
if($_SESSION['FiltreVacationPage']<>""){
	$req.="sp_olwficheintervention.Vacation='".$_SESSION['FiltreVacationPage']."' AND ";
}

$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","StatutPREPA","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","NumIC","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ");
foreach($tab as $filtre){
	if($_SESSION['Filtre'.$filtre.'2']<>""){
		$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				 if($filtre=="Programme"){$req.="sp_olwdossier.Programme='".$valeur."' OR ";}
				 if($filtre=="MSN"){$req.="sp_olwdossier.MSN=".$valeur." OR ";}
				 if($filtre=="Reference"){$req.="sp_olwdossier.Reference='".$valeur."' OR ";}
				 if($filtre=="Client"){$req.="sp_olwdossier.Id_Client=".str_replace("_","",$valeur)." OR ";}
				 if($filtre=="TypeDossier"){$req.="sp_olwdossier.TypeACP='".$valeur."' OR ";}
				 if($filtre=="Priorite"){$req.="sp_olwdossier.Priorite='".$valeur."' OR ";}
				 if($filtre=="Caec"){$req.="sp_olwdossier.CaecACP='".$valeur."' OR ";}
				 if($filtre=="Section"){$req.="sp_olwdossier.SectionACP='".$valeur."' OR ";}
				 if($filtre=="StatutPREPA"){$req.="sp_olwdossier.Id_StatutPREPA='".str_replace("_","",$valeur)."' OR ";}
				 if($filtre=="Titre"){$req.="sp_olwdossier.Titre='".addslashes($valeur)."' OR ";}
				 if($filtre=="Poste"){$req.="sp_olwdossier.Id_Poste=".str_replace("_","",$valeur)." OR ";}
				 if($filtre=="Localisation"){$req.="sp_olwdossier.Id_ZoneDeTravail=".str_replace("_","",$valeur)." OR ";}
				 if($filtre=="StatutDossier"){$req.="sp_olwdossier.Id_Statut='".$valeur."' OR ";}
				 if($filtre=="DatePrevisionnelleIntervention"){$req.="sp_olwdossier.DatePrevisionnelleIntervention='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="DateInterventionDebut"){$req.="sp_olwficheintervention.DateIntervention>='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="DateInterventionFin"){$req.="sp_olwficheintervention.DateIntervention<='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="SansDateIntervention"){$req.="sp_olwficheintervention.DateIntervention<='0001-01-01' OR ";}
				 if($filtre=="VacationPROD"){$req.="sp_olwficheintervention.Vacation='".addslashes(str_replace("_","",$valeur))."' OR ";}
				 if($filtre=="NumIC"){$req.="sp_olwficheintervention.NumFI='".addslashes($valeur)."' OR ";}
				 if($filtre=="DateInterventionQDebut"){$req.="sp_olwficheintervention.DateInterventionQ>='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="DateInterventionQFin"){$req.="sp_olwficheintervention.DateInterventionQ<='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="SansDateInterventionQ"){$req.="sp_olwficheintervention.DateInterventionQ<='0001-01-01' OR ";}
				 if($filtre=="VacationQUALITE"){$req.="sp_olwficheintervention.VacationQ='".addslashes(str_replace("_","",$valeur))."' OR ";}
				 if($filtre=="IQ"){$req.="sp_olwficheintervention.Id_QUALITE=".str_replace("_","",$valeur)." OR ";}
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
}

$tab = array("StatutPROD","StatutQUALITE");
foreach($tab as $filtre){
	if($_SESSION['Filtre'.$filtre.'2']<>""){
		$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>"0"){
				 if($filtre=="StatutPROD"){$req.="sp_olwficheintervention.Id_StatutPROD='".str_replace("_","",$valeur)."' OR ";}
				 if($filtre=="StatutQUALITE"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".str_replace("_","",$valeur)."' OR ";}
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

if($_SESSION['TriGeneral']<>""){
$req.="ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setCellValue('A1',utf8_encode("Programme"));
$sheet->setCellValue('B1',utf8_encode("MSN"));
$sheet->setCellValue('C1',utf8_encode("N° dossier"));
$sheet->setCellValue('D1',utf8_encode("Client"));
$sheet->setCellValue('E1',utf8_encode("Type dossier"));
$sheet->setCellValue('F1',utf8_encode("CA/EC"));
$sheet->setCellValue('G1',utf8_encode("Créateur"));
$sheet->setCellValue('H1',utf8_encode("Date création"));
$sheet->setCellValue('I1',utf8_encode("Type travail"));
$sheet->setCellValue('J1',utf8_encode("Code usine"));
$sheet->setCellValue('K1',utf8_encode("Titre"));
$sheet->setCellValue('L1',utf8_encode("Localisation"));
$sheet->setCellValue('M1',utf8_encode("Statut PREPA"));
$sheet->setCellValue('N1',utf8_encode("Poste"));
$sheet->setCellValue('O1',utf8_encode("Priorité"));
$sheet->setCellValue('P1',utf8_encode("TAI"));
$sheet->setCellValue('Q1',utf8_encode("Date prévisionnelle intervention"));
$sheet->setCellValue('R1',utf8_encode("Date intervention PROD"));
$sheet->setCellValue('S1',utf8_encode("Vacation PROD"));
$sheet->setCellValue('T1',utf8_encode("N°IC"));
$sheet->setCellValue('U1',utf8_encode("Temps passé"));
$sheet->setCellValue('V1',utf8_encode("Opérateurs"));
$sheet->setCellValue('W1',utf8_encode("Statut PROD"));
$sheet->setCellValue('X1',utf8_encode("Date TERA"));
$sheet->setCellValue('Y1',utf8_encode("Retour PROD"));
$sheet->setCellValue('Z1',utf8_encode("Commentaire PROD"));
$sheet->setCellValue('AA1',utf8_encode("Date intervention QUALITE"));
$sheet->setCellValue('AB1',utf8_encode("Vacation QUALITE"));
$sheet->setCellValue('AC1',utf8_encode("IQ"));
$sheet->setCellValue('AD1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('AE1',utf8_encode("Date TERC"));
$sheet->setCellValue('AF1',utf8_encode("Retour QUALITE"));
$sheet->setCellValue('AG1',utf8_encode("Commentaire QUALITE"));
$sheet->setCellValue('AH1',utf8_encode("ECME PROD\nRéférence[Type]"));
$sheet->getStyle('AH1')->getAlignment()->setWrapText(true);
$sheet->setCellValue('AI1',utf8_encode("ECME QUALITE\nRéférence[Type]"));
$sheet->getStyle('AI1')->getAlignment()->setWrapText(true);
$sheet->setCellValue('AJ1',utf8_encode("ECME Client\n N° client[Type - Date fin d'étalonnage]"));
$sheet->getStyle('AJ1')->getAlignment()->setWrapText(true);
$sheet->setCellValue('AK1',utf8_encode("Produits\nIngrédient [N° lot - Date péremption - Coeff - Température"));
$sheet->getStyle('AK1')->getAlignment()->setWrapText(true);
$sheet->setCellValue('AL1',utf8_encode("Procédés spéciaux"));


$sheet->getStyle('A1:AL1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:AL1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:AL1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:AL1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:AL1')->getFont()->setBold(true);
$sheet->getStyle('A1:AL1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(25);
$sheet->getColumnDimension('Q')->setWidth(15);
$sheet->getColumnDimension('R')->setWidth(15);
$sheet->getColumnDimension('V')->setWidth(20);
$sheet->getColumnDimension('X')->setWidth(15);
$sheet->getColumnDimension('AA')->setWidth(15);
$sheet->getColumnDimension('AC')->setWidth(20);
$sheet->getColumnDimension('AE')->setWidth(15);
$sheet->getColumnDimension('AH')->setWidth(30);
$sheet->getColumnDimension('AI')->setWidth(30);
$sheet->getColumnDimension('AJ')->setWidth(30);
$sheet->getColumnDimension('AK')->setWidth(30);
$sheet->getColumnDimension('AL')->setWidth(30);

if($nbResulta2>0){
	$ligne=2;
	while($row2=mysqli_fetch_array($result2)){
		
		$vacation="";
		if($row2['Vacation2']=="J"){$vacation="Jour";}
		elseif($row2['Vacation2']=="S"){$vacation="Soir";}
		elseif($row2['Vacation2']=="N"){$vacation="Nuit";}
		elseif($row2['Vacation2']=="VSD"){$vacation="Weekend";}
		
		$vacationQ="";
		if($row2['VacationQ']=="J"){$vacationQ="Jour";}
		elseif($row2['VacationQ']=="S"){$vacationQ="Soir";}
		elseif($row2['VacationQ']=="N"){$vacationQ="Nuit";}
		elseif($row2['VacationQ']=="VSD"){$vacationQ="Weekend";}
		
		$typeTravail="";
		if($row2['Ajusteur']==1){$typeTravail.="Ajustage";}
		if($row2['Elec']==1){
			If($typeTravail<>""){$typeTravail.="\n";}
			$typeTravail.="Elec";
			}
		if($row2['Meca']==1){
			If($typeTravail<>""){$typeTravail.="\n";}
			$typeTravail.="Meca";
			}
		$operateurs="";
		$req="SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS NomPrenom 
		FROM sp_olwfi_travaileffectue 
		WHERE Id_FI=".$row2['Id']." 
		ORDER BY NomPrenom;";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($rowCompagnon=mysqli_fetch_array($result)){
				if($operateurs<>""){$operateurs.="\n";}
				$operateurs.=$rowCompagnon['NomPrenom']."";
			}
		}
		
		$listeECMEPROD="";
		$req="SELECT Id_ECME,
		IF(Id_ECME>0,sp_olwecme.Libelle,sp_olwfi_ecme.ECME) AS Libelle,
		IF(Id_ECME>0,sp_olwecme.Id_Type,sp_olwfi_ecme.Id_TypeECME) AS Id_Type,
		IF(Id_ECME>0,(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwecme.Id_Type),
		(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwfi_ecme.Id_TypeECME)) AS Type
		FROM sp_olwfi_ecme 
		LEFT JOIN sp_olwecme 
		ON sp_olwfi_ecme.Id_ECME=sp_olwecme.Id 
		WHERE sp_olwfi_ecme.ProdQualite=0 
		AND Id_FI=".$row2['Id']." ORDER BY Libelle;";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($rowECME=mysqli_fetch_array($result)){
				if($listeECMEPROD<>""){$listeECMEPROD.="\n";}
				
				$listeECMEPROD.=$rowECME['Libelle']." [".$rowECME['Type']."]";
				
			}
		}
		
		$listeECMEQUALITE="";
		$req="SELECT Id_ECME,
		IF(Id_ECME>0,sp_olwecme.Libelle,sp_olwfi_ecme.ECME) AS Libelle,
		IF(Id_ECME>0,sp_olwecme.Id_Type,sp_olwfi_ecme.Id_TypeECME) AS Id_Type,
		IF(Id_ECME>0,(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwecme.Id_Type),
		(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwfi_ecme.Id_TypeECME)) AS Type
		FROM sp_olwfi_ecme 
		LEFT JOIN sp_olwecme 
		ON sp_olwfi_ecme.Id_ECME=sp_olwecme.Id 
		WHERE sp_olwfi_ecme.ProdQualite=1 
		AND Id_FI=".$row2['Id']." ORDER BY Libelle;";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($rowECME=mysqli_fetch_array($result)){
				if($listeECMEQUALITE<>""){$listeECMEQUALITE.="\n";}
				$listeECMEQUALITE.=$rowECME['Libelle']." [".$rowECME['Type']."]";
			}
		}
		
		$listeECMECLIENT="";
		$req="SELECT Id_ECME,DateFinEtalonnage,
		IF(Id_ECME>0,sp_olwecmeclient.Libelle,sp_olwfi_ecmeclient.NumClient) AS Libelle,
		IF(Id_ECME>0,sp_olwecmeclient.Id_Type,sp_olwfi_ecmeclient.Id_TypeECME) AS Id_Type,
		IF(Id_ECME>0,(SELECT Libelle FROM sp_olwtypeecmeclient WHERE sp_olwtypeecmeclient.Id=sp_olwecmeclient.Id_Type),
		(SELECT Libelle FROM sp_olwtypeecmeclient WHERE sp_olwtypeecmeclient.Id=sp_olwfi_ecmeclient.Id_TypeECME)) AS Type
		FROM sp_olwfi_ecmeclient 
		LEFT JOIN sp_olwecmeclient 
		ON sp_olwfi_ecmeclient.Id_ECME=sp_olwecmeclient.Id 
		WHERE Id_FI=".$row2['Id']." ORDER BY Libelle;";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($rowECME=mysqli_fetch_array($result)){
				if($listeECMECLIENT<>""){$listeECMECLIENT.="\n";}
				$listeECMECLIENT.=$rowECME['Libelle']." [".$rowECME['Type']." - ".AfficheDateFR($rowECME['DateFinEtalonnage'])."]";
			}
		}
		
		$listeProduit="";
		$req="SELECT Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique,Temperature,";
		$req.="IF(Id_Ingredient>0,(SELECT sp_olwingredient.Libelle FROM sp_olwingredient WHERE sp_olwingredient.Id=sp_olwfi_ingredient.Id_Ingredient),sp_olwfi_ingredient.Ingredient) AS Produit ";
		$req.="FROM sp_olwfi_ingredient WHERE Id_FI=".$row2['Id']." ORDER BY Produit;";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($rowIngredient=mysqli_fetch_array($result)){
				if($listeProduit<>""){$listeProduit.="\n";}
				$listeProduit.=$rowIngredient['Produit']." [".$rowIngredient['NumLot']." - ".AfficheDateFR($rowIngredient['DatePeremption'])." - ".$rowIngredient['CoeffHydrometrique']." - ".$rowIngredient['Temperature']."]";
			}
		}
		
		$listeAIPI="";
		$req="SELECT Id_Qualification,Qualification, ";
		$req.="IF(Id_Qualification>0,(SELECT new_competences_qualification.Libelle ";
		$req.="FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_olwfi_aipi.Id_Qualification),Qualification) AS Libelle ";
		$req.="FROM sp_olwfi_aipi WHERE Id_FI=".$row2['Id']." ORDER BY Libelle;";
		
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			while($rowAIPI=mysqli_fetch_array($result)){
				if($listeAIPI<>""){$listeAIPI.="\n";}
				$listeAIPI.=$rowAIPI['Libelle'];
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row2['Programme']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row2['MSN']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Reference']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row2['Client']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row2['TypeDossier']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row2['CaecACP']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row2['CreateurDossier']));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateCreation'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode($typeTravail));
		$sheet->getStyle('I'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('J'.$ligne,utf8_encode($row2['CodeUsine']));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row2['Titre'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row2['Localisation'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode($row2['Id_StatutPREPA']));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row2['Poste']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row2['Priorite']));
		$sheet->setCellValue('P'.$ligne,utf8_encode($row2['TAI_RestantACP']));
		$sheet->setCellValue('Q'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DatePrevisionnelleIntervention'])));
		
		$sheet->setCellValue('R'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateIntervention'])));
		$sheet->setCellValue('S'.$ligne,utf8_encode($vacation));
		$sheet->setCellValue('T'.$ligne,utf8_encode($row2['NumFI']));
		$sheet->setCellValue('U'.$ligne,utf8_encode($row2['TempsProd']));
		$sheet->setCellValue('V'.$ligne,utf8_encode($operateurs));
		$sheet->getStyle('V'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('W'.$ligne,utf8_encode($row2['StatutPROD']));
		$sheet->setCellValue('X'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateTERA'])));
		$sheet->setCellValue('Y'.$ligne,utf8_encode($row2['RetourPROD']));
		$sheet->setCellValue('Z'.$ligne,utf8_encode(stripslashes($row2['CommentairePROD'])));
		$sheet->setCellValue('AA'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateInterventionQ'])));
		$sheet->setCellValue('AB'.$ligne,utf8_encode($vacationQ));
		$sheet->setCellValue('AC'.$ligne,utf8_encode($row2['IQ']));
		$sheet->setCellValue('AD'.$ligne,utf8_encode($row2['StatutQUALITE']));
		$sheet->setCellValue('AE'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateTERC'])));
		$sheet->setCellValue('AF'.$ligne,utf8_encode($row2['RetourQUALITE']));
		$sheet->setCellValue('AG'.$ligne,utf8_encode(stripslashes($row2['CommentaireQUALITE'])));
		$sheet->setCellValue('AH'.$ligne,utf8_encode(stripslashes($listeECMEPROD)));
		$sheet->getStyle('AH'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('AI'.$ligne,utf8_encode(stripslashes($listeECMEQUALITE)));
		$sheet->getStyle('AI'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('AJ'.$ligne,utf8_encode(stripslashes($listeECMECLIENT)));
		$sheet->getStyle('AJ'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('AK'.$ligne,utf8_encode(stripslashes($listeProduit)));
		$sheet->getStyle('AK'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('AL'.$ligne,utf8_encode(stripslashes($listeAIPI)));
		$sheet->getStyle('AL'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':AL'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
		
		
	}
}



//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Dossier.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Dossier.xlsx';
$writer->save($chemin);
readfile($chemin);
?>