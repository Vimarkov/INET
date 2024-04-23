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

$reqAnalyse="SELECT DISTINCT sp_olwdossier.MSN ";
$req2="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.ReferencePF,sp_olwdossier.Reference,sp_olwdossier.ReferenceNC,sp_olwdossier.ReferenceAM,";
$req2.="sp_olwficheintervention.NumDERO,sp_olwficheintervention.NumDA,sp_olwdossier.Titre,sp_olwficheintervention.NumFI,sp_olwdossier.CommentaireZICIA,";
$req2.="(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client,sp_olwdossier.Imputation,sp_olwficheintervention.DateIntervention,sp_olwficheintervention.Vacation,
						IF(sp_olwficheintervention.Vacation='',0,
							IF(sp_olwficheintervention.Vacation='J',1,
								IF(sp_olwficheintervention.Vacation='S',2,
									IF(sp_olwficheintervention.Vacation='N',3,
										IF(sp_olwficheintervention.Vacation='VSD Jour',4,
											IF(sp_olwficheintervention.Vacation='VSD Nuit',5,0)
										)
									)
								)
							)
						) AS Vacation2,";
$req2.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, ";
$req2.="(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client,sp_olwdossier.Imputation,sp_olwdossier.Programme, ";
$req2.="sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_StatutQUALITE ";
$req="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=-15 AND ";
if($_SESSION['Archive2']<>""){
	$tab = explode(";",$_SESSION['Archive2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="Oui"){
				$req.="sp_olwdossier.Archive=1 OR ";
			}
			elseif($valeur=="Non"){
				$req.="sp_olwdossier.Archive=0 OR ";
			}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['MSN2']<>""){
	$tab = explode(";",$_SESSION['MSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumPointFolio2']<>""){
	$tab = explode(";",$_SESSION['NumPointFolio2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.ReferencePF='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumDossier2']<>""){
	$tab = explode(";",$_SESSION['NumDossier2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Reference='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumNC2']<>""){
	$tab = explode(";",$_SESSION['NumNC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.ReferenceNC='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumAM2']<>""){
	$tab = explode(";",$_SESSION['NumAM2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.ReferenceAM='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Section2']<>""){
	$tab = explode(";",$_SESSION['Section2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.SectionACP='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Zone2']<>""){
	$tab = explode(";",$_SESSION['Zone2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Priorite2']<>""){
	$tab = explode(";",$_SESSION['Priorite2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Priorite=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CreateurDossier2']<>""){
	$tab = explode(";",$_SESSION['CreateurDossier2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Id_Personne=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Imputation2']<>""){
	$tab = explode(";",$_SESSION['Imputation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Imputation='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Client2']<>""){
	$tab = explode(";",$_SESSION['Client2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Id_Client='".substr($valeur,1)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['TravailRealise2']<>""){
	$tab = explode(";",$_SESSION['TravailRealise2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.TravailRealise LIKE '%".addslashes($valeur)."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Localisation2']<>""){
	$tab = explode(";",$_SESSION['Localisation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.CommentaireZICIA LIKE '%".addslashes($valeur)."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Vacation2']<>""){
	$tab = explode(";",$_SESSION['Vacation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Vacation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CE2']<>""){
	$tab = explode(";",$_SESSION['CE2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Id_PROD=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['SansDate2']=="oui"){
	$req.=" ( ";
	$req.="sp_olwficheintervention.DateIntervention <= '0001-01-01' OR ";
}
if($_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['DateDebut2']<>""){
		$req.="sp_olwficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['DateFin2']<>""){
		$req.="sp_olwficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['SansDate2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['SansDate2']=="oui" || $_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
	$req.=" AND ";
}
if($_SESSION['VacationQUALITE2']<>""){
	$tab = explode(";",$_SESSION['VacationQUALITE2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.VacationQ='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['IQ2']<>""){
	$tab = explode(";",$_SESSION['IQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Id_QUALITE=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Stamp2']<>""){
	$tab = explode(";",$_SESSION['Stamp2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$reqIQ="SELECT Id_Personne FROM new_competences_personne_stamp WHERE Num_Stamp='".$valeur."'";
			$resultIQ=mysqli_query($bdd,$reqIQ);
			$nbResultaIQ=mysqli_num_rows($resultIQ);
			if($nbResultaIQ>0){
				while($rowIQ=mysqli_fetch_array($resultIQ)){
					$req.="sp_olwficheintervention.Id_QUALITE=".$rowIQ['Id_Personne']." OR ";
				}
			}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['SansDateQUALITE2']=="oui"){
	$req.=" ( ";
	$req.="sp_olwficheintervention.DateInterventionQ <= '0001-01-01' OR ";
}
if($_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
	$req.=" ( ";
	if($_SESSION['DateDebutQUALITE2']<>""){
		$req.="sp_olwficheintervention.DateInterventionQ >= '". TrsfDate_($_SESSION['DateDebutQUALITE2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['DateFinQUALITE2']<>""){
		$req.="sp_olwficheintervention.DateInterventionQ <= '". TrsfDate_($_SESSION['DateFinQUALITE2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['SansDateQUALITE2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['SansDateQUALITE2']=="oui" || $_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
	$req.=" AND ";
}
if($_SESSION['NumDERO2']<>""){
	$tab = explode(";",$_SESSION['NumDERO2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.NumDERO='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumDA2']<>""){
	$tab = explode(";",$_SESSION['NumDA2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.NumDA='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumIC2']<>""){
	$tab = explode(";",$_SESSION['NumIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.NumFI='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Poste2']<>""){
	$tab = explode(";",$_SESSION['Poste2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.PosteAvionACP='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CreateurIC2']<>""){
	$tab = explode(";",$_SESSION['CreateurIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Id_Createur=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['StatutPrepa2']<>""){
	$tab = explode(";",$_SESSION['StatutPrepa2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_olwficheintervention.StatutPrepa='' OR ";}
			else{$req.="sp_olwficheintervention.StatutPrepa='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['StatutIC2']<>""){
	$tab = explode(";",$_SESSION['StatutIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_olwficheintervention.Id_StatutPROD='' OR sp_olwficheintervention.Id_StatutQUALITE='' OR ";}
			elseif($valeur=="TFS" || $valeur=="TERA" || $valeur=="RETOUR PROD" || $valeur=="RETOUR PREPA"){$req.="sp_olwficheintervention.Id_StatutPROD='".$valeur."' OR ";}
			elseif($valeur=="TVS" || $valeur=="TERC" || $valeur=="RETQ PREPA" || $valeur=="RETQ PROD"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$result=mysqli_query($bdd,$reqAnalyse.$req." ORDER BY MSN");
$nbResulta=mysqli_num_rows($result);

$reqFin="";
if($_SESSION['TriGeneral']<>""){
	$reqFin=" ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
}

$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);

$PremierMSN=true;
if($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		//Création de l'onglet
		if($PremierMSN==true){
			$sheet = $workbook->getActiveSheet();
			$PremierMSN=false;
		}
		else{
			$sheet = $workbook->createSheet();
		}
		$sheet->setTitle($row['MSN']);
		
		$sheet->setCellValue('A1',utf8_encode("MSN"));
		$sheet->setCellValue('B1',utf8_encode("N° AM"));
		$sheet->setCellValue('C1',utf8_encode("N° OF"));
		$sheet->setCellValue('D1',utf8_encode("Titre"));
		$sheet->setCellValue('E1',utf8_encode("Travail à réaliser"));
		$sheet->setCellValue('F1',utf8_encode("N° IC"));
		$sheet->setCellValue('G1',utf8_encode("Zone"));
		$sheet->setCellValue('H1',utf8_encode("Localisation"));
		$sheet->setCellValue('I1',utf8_encode("Poste d'intervention"));
		
		$sheet->getStyle('A1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
		$sheet->getStyle('A1:I1')->getFont()->setBold(true);
		$sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('1f49a6');
		
		$sheet->getColumnDimension('A')->setWidth(8);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(12);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(12);
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->getColumnDimension('H')->setWidth(30);
		$sheet->getColumnDimension('I')->setWidth(20);
		
		$ligne=2;
		mysqli_data_seek($result2,0);
		while($row2=mysqli_fetch_array($result2)){
			if($row2['MSN']==$row['MSN']){
				$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
				$sheet->setCellValue('B'.$ligne,utf8_encode($row2['ReferenceAM']));
				$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Reference']));
				$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row2['Titre'])));
				$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row2['TravailRealise'])));
				$sheet->setCellValue('F'.$ligne,utf8_encode($row2['NumFI']));
				$sheet->setCellValue('G'.$ligne,utf8_encode($row2['Zone']));
				$sheet->setCellValue('H'.$ligne,utf8_encode($row2['CommentaireZICIA']));
				$sheet->setCellValue('I'.$ligne,utf8_encode($row2['PosteAvionACP']));
				
				$sheet->getStyle('A'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
				$ligne++;
			}
		}
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