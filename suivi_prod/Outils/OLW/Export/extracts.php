<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
// pour la connection à la base de données pour un fichier Excel
require '../../ConnexioniSansBody.php';


 global $clients;
 global $extract;
 global $aipi;
 global $controleurs;

 function extractDossiers($client=NULL) {
 		extractDossiers_requêtes($client);
 		extractDossiers_genererExcel();
 }

 function extractDossiers_requêtes($client) {
	//[197] - Chargement de la liste des clients
	global $clients;
	global $extract;
	
	
	$req="SELECT DISTINCT sp_client.Id, sp_client.Libelle \n";
	$req.="FROM \n";
	$req.="sp_olwficheintervention, \n";
	$req.="sp_olwdossier, \n";
	$req.="sp_client \n";
	$req.="WHERE \n";
	$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id \n";
	$req.="AND sp_olwdossier.Id_Client = sp_client.Id; \n";
	
	$clients=mysqli_query($bdd,$req);
	
	$req="SELECT \n";
	$req.="		sp_client.Libelle AS Client, \n";
	$req.="		sp_olwdossier.MSN AS MSN, \n";
	$req.="		sp_olwdossier.Reference AS NumDossier, \n";
	$req.="		sp_olwdossier.Titre AS Titre, \n";
	$req.="		sp_olwdossier.Elec AS Elec, \n";
	$req.="		sp_olwdossier.Systeme AS Systeme, \n";
	$req.="		sp_olwdossier.Structure AS Structure, \n";
	$req.="		sp_olwdossier.Oxygene AS Oxygene, \n";
	$req.="		sp_olwdossier.Hydraulique AS Hydraulique, \n";
	$req.="		sp_olwdossier.Fuel AS Fuel, \n";
	$req.="		sp_olwdossier.Metal AS Metal, \n";
	
	$req.="		sp_olwficheintervention.TravailRealise AS TravailARealiser, \n";
	$req.="		sp_olwficheintervention.PosteAvionACP AS Poste, \n";
	$req.="		sp_olwficheintervention.CommentaireQUALITE AS CommentaireQUALITE, \n";
	
	$req.="		sp_olwretour.Id_Statut AS RetourQUALITE, \n";
	
	$req.="		new_rh_etatcivil.Nom, \n";
	$req.="		new_rh_etatcivil.Prenom, \n";
	$req.="		sp_olwfi_travaileffectue.TempsPasse AS HeuresPassees, \n";
	$req.="		sp_olwficheintervention.Id_StatutPROD AS StatutProd, \n";
	$req.="		sp_olwficheintervention.Id_StatutQUALITE AS StatutQualite \n";
	
	$req.="FROM \n";
	$req.="		sp_olwdossier, \n";	
	$req.="		sp_olwficheintervention LEFT JOIN sp_olwretour ON (sp_olwficheintervention.Id_RetourQUALITE = sp_olwretour.Id), \n";
	$req.="		sp_client, \n";
	$req.="		sp_olwfi_travaileffectue, \n";
	$req.="		new_rh_etatcivil \n";
	
	$req.="WHERE \n";
	$req.="		sp_olwficheintervention.id_dossier = sp_olwdossier.id \n";
	$req.="AND sp_olwdossier.Id_Client = sp_client.id \n";
	$req.="AND sp_olwfi_travaileffectue.Id_FI = sp_olwficheintervention.Id \n";
	$req.="AND sp_olwfi_travaileffectue.Id_Personne = new_rh_etatcivil.Id \n";
	
	if (! is_null($client) )
		$req.="AND sp_olwdossier.Id_Client = ".$client." \n";
	
	$req.="ORDER BY sp_client.Libelle; ";
	
	$extract=mysqli_query($bdd,$req);
 }

 function extractDossiers_genererExcel() {
	
 	$workbook = new PHPExcel;
 	$sheet = $workbook->getActiveSheet();
	$sheet->setTitle('ExtractDossier');
	
	$sheet->setCellValue('A1',utf8_encode("Client"));
	$sheet->setCellValue('B1',utf8_encode("Poste"));
	$sheet->setCellValue('C1',utf8_encode("N°MSN"));
	$sheet->setCellValue('D1',utf8_encode("N°Dossier"));
	$sheet->setCellValue('E1',utf8_encode("Compétence"));
	$sheet->setCellValue('F1',utf8_encode("Titre"));
	$sheet->setCellValue('G1',utf8_encode("Travail à réaliser"));
	$sheet->setCellValue('H1',utf8_encode("Inspecteur"));
	$sheet->setCellValue('I1',utf8_encode("Statut Qualité"));
	$sheet->setCellValue('J1',utf8_encode("Retour Qualité"));
	$sheet->setCellValue('K1',utf8_encode("Commentaire Qualité"));
	
	//Remplir le tableau
	extractDossiers_remplirTableau($sheet);
	
	//Formatter le tableau
	extractDossiers_formattageTableau($sheet);
	
	//Enregistre le fichier Excel côté server
 	$writer = new PHPExcel_Writer_Excel2007($workbook);
 	$writer->save('../../../tmp/Extract.xlsx');
	
}

function extractDossiers_remplirTableau(PHPExcel_Worksheet $sheet) {
	global $extract;
	
	$ligne = 2;
	
	while ($row = mysqli_fetch_array($extract)) {
		
		//Compétences
		$competences = '';
		if ($row['Elec'] == '1')
			$competences.= 'ELECTRICITE ';
		
		if ($row['Systeme'] == '1')
			$competences.= 'SYSTEME ';
		
		if ($row['Structure'] == '1')
			$competences.= 'STRUCTURE ';
		
		if ($row['Oxygene'] == '1')
			$competences.= 'OXYGENE ';
		
		if ($row['Hydraulique'] == '1')
			$competences.= 'HYDRAULIQUE ';
		
		if ($row['Fuel'] == '1')
			$competences.= 'FUEL ';
		
		if ($row['Metal'] == '1')
			$competences.= 'METAL ';
		
		
		//Opérations
		$sheet->setCellValueExplicitByColumnAndRow(0, $ligne, $row['Client']);
		$sheet->setCellValueExplicitByColumnAndRow(1, $ligne, $row['Poste']);
		$sheet->setCellValueExplicitByColumnAndRow(2, $ligne, $row['MSN']);
		$sheet->setCellValueExplicitByColumnAndRow(3, $ligne, $row['NumDossier']);
		$sheet->setCellValueExplicitByColumnAndRow(4, $ligne, $competences);
		$sheet->setCellValueExplicitByColumnAndRow(5, $ligne, $row['Titre']);
		$sheet->setCellValueExplicitByColumnAndRow(6, $ligne, $row['TravailARealiser']);
		$sheet->setCellValueExplicitByColumnAndRow(7, $ligne, $row['Nom'].' '.$row['Prenom']);
		$sheet->setCellValueExplicitByColumnAndRow(8, $ligne, $row['StatutQualite']);
		$sheet->setCellValueExplicitByColumnAndRow(9, $ligne, $row['RetourQUALITE']);
		$sheet->setCellValueExplicitByColumnAndRow(10, $ligne, $row['CommentaireQUALITE']);
		
		$ligne++;
	}
	
	mysqli_free_result($extract);
}

function extractDossiers_formattageTableau(PHPExcel_Worksheet $sheet) {
	foreach(range('A','K') as $columnID) 
		$sheet->getColumnDimension($columnID)->setAutoSize(true);
	
		$sheet->getStyle('A1:K1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}

function extractCompagnons() {
	extractCompagnons_requêtes();
	extractCompagnons_genererExcel();
}

function extractCompagnons_requêtes() {
	global $extract;
	global $aipi;
	global $controleurs;
	
	$req="SELECT \n";
	$req.="		sp_olwficheintervention.Id, \n";
	$req.="		sp_olwdossier.MSN, \n";
	$req.="		sp_olwdossier.Reference, \n";
	$req.="		sp_olwficheintervention.Id_Qualite, \n";
	$req.="		CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Compagnon \n";
	
	$req.="FROM \n";
	$req.="		sp_olwficheintervention LEFT JOIN sp_olwfi_aipi ON sp_olwficheintervention.Id = sp_olwfi_aipi.Id_FI, \n";
	$req.="		sp_olwdossier, \n";
	$req.="		sp_olwfi_travaileffectue, \n";
	$req.="		new_rh_etatcivil \n";
	
	$req.="WHERE \n";
	$req.="		sp_olwdossier.Id = sp_olwficheintervention.Id_Dossier \n";
	$req.="AND sp_olwfi_travaileffectue.Id_FI = sp_olwficheintervention.Id \n";
	$req.="AND new_rh_etatcivil.Id = sp_olwfi_travaileffectue.Id_Personne \n";
	$req.="ORDER BY sp_olwdossier.MSN, sp_olwdossier.Reference; \n";
	
	
	$extract=mysqli_query($bdd,$req);
	
	$req="SELECT\n";
	$req.="sp_olwfi_aipi.Id_FI, \n";
	$req.="new_competences_qualification.Libelle \n";
	$req.="FROM \n";
	$req.="sp_olwfi_aipi, \n";
	$req.="new_competences_qualification \n";
	$req.="WHERE \n";
	$req.="sp_olwfi_aipi.Id_Qualification = new_competences_qualification.Id; \n";
	
	$extract2=mysqli_query($bdd,$req);
	
	// Mise en mémoire du résultat dans un tableau indexé
	while($row = mysqli_fetch_array($extract2))
		$aipi[$row['Id_FI']] =  $row['Libelle'];

	mysqli_free_result($extract2);

	
	$req="SELECT \n";
	$req.="		sp_olwficheintervention.Id, \n";
	$req.="		CONCAT(new_rh_etatcivil.Nom, ' ',new_rh_etatcivil.Prenom) AS NomControleur \n";
	$req.="FROM \n";
	$req.="		sp_olwficheintervention, \n";
	$req.="		new_rh_etatcivil \n";
	$req.="WHERE \n";
	$req.="		sp_olwficheintervention.Id_StatutQUALITE = 'TERC' \n";
	$req.="AND new_rh_etatcivil.Id = sp_olwficheintervention.Id_Qualite; \n";
	
	$extract2=mysqli_query($bdd,$req);
	
	while($row = mysqli_fetch_array($extract2))
		$controleurs[$row['Id']] =  $row['NomControleur'];
	
		mysqli_free_result($extract2);
		
}

function extractCompagnons_genererExcel() {
	$workbook = new PHPExcel;
	$sheet = $workbook->getActiveSheet();
	$sheet->setTitle('ExtractCompagnons');
	
	$sheet->setCellValue('A1',utf8_encode("MSN"));
	$sheet->setCellValue('B1',utf8_encode("N° OF"));
	$sheet->setCellValue('C1',utf8_encode("Compagnon ayant réalisé le travail"));
	$sheet->setCellValue('D1',utf8_encode("Contrôleur ayant CERT"));
	$sheet->setCellValue('E1',utf8_encode("AIPI/AIPS"));
	
	//Remplir le tableau
	extractCompagnons_remplirTableau($sheet);
	
	//Formatter le tableau
	extractCompagnons_formattageTableau($sheet);
	
	//Enregistre le fichier Excel côté server
	$writer = new PHPExcel_Writer_Excel2007($workbook);
	$writer->save('../../../tmp/Extract.xlsx');
}

function extractCompagnons_remplirTableau(PHPExcel_Worksheet $sheet) {
	global $extract;
	global $aipi;
	global $controleurs;
	
	$ligne = 2;
	
	while ($row = mysqli_fetch_array($extract)) {	
			//Opérations
			$sheet->setCellValueExplicitByColumnAndRow(0, $ligne, $row['MSN']);
 			$sheet->setCellValueExplicitByColumnAndRow(1, $ligne, $row['Reference']);
 			$sheet->setCellValueExplicitByColumnAndRow(2, $ligne, $row['Compagnon']);
 			
 			if(isset($controleurs[$row['Id']]))
  			$sheet->setCellValueExplicitByColumnAndRow(3, $ligne, $controleurs[$row['Id']]);
 			
 			if(isset($aipi[$row['Id']]))
 				$sheet->setCellValueExplicitByColumnAndRow(4, $ligne, $aipi[$row['Id']]);
 			
			$ligne++;
	}
	
	mysqli_free_result($extract);
}

function extractCompagnons_formattageTableau(PHPExcel_Worksheet $sheet) {
	foreach(range('A','E') as $columnID) 
		$sheet->getColumnDimension($columnID)->setAutoSize(true);
	
		$sheet->getStyle('A1:E1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}

 ?>