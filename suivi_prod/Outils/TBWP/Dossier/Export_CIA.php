<?php
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$pole=$_GET['pole'];
//Eporter les IC à traiter du pôle
$req="SELECT sp_ficheintervention.Id,sp_ficheintervention.Id_Dossier,sp_dossier.MSN,sp_dossier.Reference,sp_dossier.SectionACP AS MCA,sp_ficheintervention.DateCreation AS DateCreationIC,";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=sp_ficheintervention.Id_Pole) AS Pole, ";
$req.="(SELECT sp_urgence.Libelle FROM sp_urgence WHERE sp_urgence.Id=sp_dossier.Id_Urgence) AS Urgence,sp_dossier.DateCreation,sp_dossier.DateCreationACP,sp_dossier.ACP_Id, ";
$req.="sp_dossier.TypeACP AS Type,sp_dossier.Origine,sp_dossier.NumOrigine,sp_dossier.TAI_RestantACP AS TAI_Restant,sp_dossier.CaecACP AS Caec,";
$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_dossier.Id_Personne) AS CreateurDossier, ";
$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_Createur) AS CreateurIC, ";
$req.="sp_dossier.Priorite,sp_dossier.Titre,sp_ficheintervention.NumFI, sp_ficheintervention.Vacation,sp_dossier.Id_Urgence, ";
$req.="sp_dossier.Id_ZoneDeTravail,sp_dossier.Elec,sp_dossier.Systeme,sp_dossier.Structure,sp_dossier.Oxygene,sp_dossier.Hydraulique,";
$req.="sp_dossier.Fuel,sp_dossier.Metal,sp_dossier.CommentaireZICIA,sp_ficheintervention.PosteAvionACP,sp_ficheintervention.Id_Pole,";
$req.="sp_ficheintervention.Commentaire,sp_ficheintervention.CommentaireMICIA,sp_ficheintervention.CommentairePOCIA,sp_ficheintervention.InfoSuppPOCIA,";
$req.="sp_ficheintervention.RaisonInterventionCIA,sp_ficheintervention.StatutCIA,sp_ficheintervention.TypeCIA,sp_ficheintervention.PosteInterventionCIA,sp_ficheintervention.ESN,sp_ficheintervention.Pneumatique,sp_ficheintervention.PasDeMesure,";
$req.="(SELECT sp_activite.Libelle FROM sp_activite WHERE sp_activite.Id=sp_ficheintervention.Id_ActiviteCIA) AS Activite,sp_ficheintervention.Id_FIIndicage,sp_ficheintervention.RaisonIndicage,";
$req.="(SELECT sp_typetravail.Libelle FROM sp_typetravail WHERE sp_typetravail.Id=sp_ficheintervention.Id_TypeTravailCIA) AS TypeTravail,";
$req.="sp_ficheintervention.RefAInstallerCIA,sp_ficheintervention.HeureDebutCIA,sp_ficheintervention.HeureFinCIA,sp_ficheintervention.DateFinCIA,";
$req.="(SELECT sp_impact.Libelle FROM sp_impact WHERE sp_impact.Id=sp_ficheintervention.Id_ImpactElementCIA) AS Impact,sp_ficheintervention.RefCableCIA,";
$req.="(SELECT sp_FI.NumFI FROM sp_ficheintervention AS sp_FI WHERE sp_FI.Id=sp_ficheintervention.Id_FIIndicage) AS NumFIIndicage,";
$req.="sp_ficheintervention.DescriptionTypeTravailCIA,sp_ficheintervention.PowerOffPartielCIA,sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.DateCreationPROD,";
$req.="sp_ficheintervention.Id_RetourPROD,sp_ficheintervention.Id_PROD,sp_ficheintervention.Id_StatutQUALITE,sp_ficheintervention.DateCreationQUALITE,sp_ficheintervention.Id_RetourQUALITE,";
$req.="sp_ficheintervention.Id_QUALITE,sp_ficheintervention.CommentairePROD,sp_ficheintervention.CommentaireQUALITE,sp_ficheintervention.StatutICCIA,";
$req.="sp_ficheintervention.DateIntervention,sp_ficheintervention.TravailRealise,sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.Id_StatutQUALITE,sp_ficheintervention.EtatICCIA ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND (sp_ficheintervention.EtatICCIA='A TRAITER' OR sp_ficheintervention.EtatICCIA='VALIDEE') AND sp_ficheintervention.TypeCIA<>'' AND sp_ficheintervention.StatutICCIA<6 AND sp_ficheintervention.Vacation<>'' AND sp_ficheintervention.DateIntervention>'0001-01-01' ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$export=$nbResulta;

if ($nbResulta>0){
	$workbook = new PHPExcel;
	$sheet = $workbook->getActiveSheet();
	$sheet->setTitle('Creation_IC');
	
	$sheet2 = $workbook->createSheet();
	$sheet2->setTitle('Liste_Compagnon');
	
	$sheet2->setCellValue('A1',utf8_encode("Nom"));
	$sheet2->setCellValue('B1',utf8_encode("Prénom"));

	$result2=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result2);
	
	
	$ligne=2;
	if ($nbResulta>0){
		while($row2=mysqli_fetch_array($result2)){
			$sheet2->setCellValue('A'.$ligne,utf8_encode($row2['Nom']));
			$sheet2->setCellValue('B'.$ligne,utf8_encode($row2['Prenom']));
			$ligne++;
		}
	}
	
	$sheet->setCellValue('C5',utf8_encode("Statut (Fermer PROD / Fermer Qual)"));
	$sheet->setCellValue('D5',utf8_encode("Nom CT"));
	$sheet->setCellValue('E5',utf8_encode("MSN"));
	$sheet->setCellValue('F5',utf8_encode("Poste"));
	$sheet->setCellValue('G5',utf8_encode("Pôle"));
	$sheet->setCellValue('H5',utf8_encode("WO"));
	$sheet->setCellValue('I5',utf8_encode("Accepter Oui Non"));
	$sheet->setCellValue('J5',utf8_encode("Numéro IC"));
	$sheet->setCellValue('K5',utf8_encode("Date de Début"));
	$sheet->setCellValue('L5',utf8_encode("Vacation (D/L/N/VSD)"));
	$sheet->setCellValue('M5',utf8_encode("Priorité"));
	$sheet->setCellValue('N5',utf8_encode("Urgence"));
	$sheet->setCellValue('O5',utf8_encode("Type de travail"));
	$sheet->setCellValue('P5',utf8_encode("Raison de l'intervention"));
	$sheet->setCellValue('Q5',utf8_encode("Description du Type de travail"));
	$sheet->setCellValue('R5',utf8_encode("Com. zone interv."));
	$sheet->setCellValue('S5',utf8_encode("Compagnons"));
	$sheet->setCellValue('T5',utf8_encode("Opération"));
	$sheet->setCellValue('U5',utf8_encode("Compétence"));
	$sheet->setCellValue('V5',utf8_encode("ATA"));
	$sheet->setCellValue('W5',utf8_encode("Activité"));
	$sheet->setCellValue('X5',utf8_encode("Ref à installer"));
	$sheet->setCellValue('Y5',utf8_encode("Impact élément termniaison prise (only elec)"));
	$sheet->setCellValue('Z5',utf8_encode("Référence des câbles"));
	$sheet->setCellValue('AA5',utf8_encode("Zone d'intervention"));
	$sheet->setCellValue('AB5',utf8_encode("Type IC"));
	$sheet->setCellValue('AC5',utf8_encode("Mesures de sécurité"));
	$sheet->setCellValue('AD5',utf8_encode("Heure de début"));
	$sheet->setCellValue('AE5',utf8_encode("Heure de Fin"));
	$sheet->setCellValue('AF5',utf8_encode("Date de Fin"));
	$sheet->setCellValue('AG5',utf8_encode("Isolation électrique système"));
	$sheet->setCellValue('AH5',utf8_encode("Distrib List"));
	$sheet->setCellValue('AI5',utf8_encode("Poste de l'intervention"));
	$sheet->setCellValue('AJ5',utf8_encode("N° Moteur"));
	$sheet->setCellValue('AK5',utf8_encode("APU Oui/Non"));
	$sheet->setCellValue('AL5',utf8_encode("N/A Oui/Non"));
	$sheet->setCellValue('AM5',utf8_encode("Raison indiçage"));
	$sheet->setCellValue('AN5',utf8_encode("Type WO (OW-X)"));
	$sheet->setCellValue('AO5',utf8_encode("Section"));
	$sheet->setCellValue('AP5',utf8_encode("TAI"));
	$sheet->setCellValue('AQ5',utf8_encode("CA / EC"));
	$sheet->setCellValue('AR5',utf8_encode("Clé Primaire"));
	$sheet->setCellValue('AS5',utf8_encode("Risque lié à l'intervention"));
	$sheet->setCellValue('AT5',utf8_encode("Date cloture Prod"));
	$sheet->setCellValue('AU5',utf8_encode("Date de création IC CT"));
	$sheet->setCellValue('AV5',utf8_encode("Power Off Partiel"));
	$sheet->setCellValue('AW5',utf8_encode("Commentaire Power Off"));
	$sheet->setCellValue('AX5',utf8_encode("Information Complémentaire Power Off"));
	$sheet->setCellValue('AY5',utf8_encode("Moyen industriel"));
	$sheet->setCellValue('AZ5',utf8_encode("Commentaire Moyen industriel"));

	$ligne=6;
	while($row=mysqli_fetch_array($result)){
		if($row['StatutICCIA']>0){
			$sheet->setCellValue('C'.$ligne,utf8_encode($row['StatutICCIA']));
		}
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['CreateurIC']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['PosteAvionACP']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Pole']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['NumFI']));
		
		if($row['StatutICCIA']==0 && $row['Id_FIIndicage']>0){
				$sheet->setCellValue('C'.$ligne,utf8_encode("INDICAGE"));
				$sheet->setCellValue('J'.$ligne,utf8_encode($row['NumFIIndicage']));
		}
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['DateIntervention']));
		$vacation="";
		if($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		else{$vacation=$row['Vacation'];}
		$sheet->setCellValue('L'.$ligne,utf8_encode($vacation));
		
		if($row['EtatICCIA']=="VALIDEE" || $row['EtatICCIA']=="REFUSEE"){
			$sheet->setCellValue('I'.$ligne,utf8_encode($row['EtatICCIA']));
		}
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		$sheet->setCellValue('M'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['Urgence']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['TypeTravail']));
		$sheet->setCellValue('P'.$ligne,utf8_encode($row['RaisonInterventionCIA']));
		$sheet->setCellValue('Q'.$ligne,utf8_encode($row['DescriptionTypeTravailCIA']));
		$sheet->setCellValue('R'.$ligne,utf8_encode($row['CommentaireZICIA']));
		$reqCompagnon="SELECT (SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_fi_travaileffectue.Id_Personne) AS Nom FROM sp_fi_travaileffectue WHERE Id_FI=".$row['Id'];
		$resultCompagnon=mysqli_query($bdd,$reqCompagnon);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		$Compagnon="";
		if($nbCompagnon>0){
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$Compagnon.=$rowCompagnon['Nom']."/\n";
			}
		}
		$sheet->setCellValue('S'.$ligne,utf8_encode($Compagnon));
		$Competence="";
		if($row['Elec']==1){$Competence.="Elec\n";}
		if($row['Systeme']==1){$Competence.="Système\n";}
		if($row['Structure']==1){$Competence.="Structure\n";}
		if($row['Oxygene']==1){$Competence.="Oxygène\n";}
		if($row['Hydraulique']==1){$Competence.="Hydraulique\n";}
		if($row['Fuel']==1){$Competence.="Fuel\n";}
		if($row['Metal']==1){$Competence.="Métal\n";}
		if($Competence<>""){$Competence=substr($Competence,0,-1);}
		$sheet->setCellValue('U'.$ligne,utf8_encode($Competence));
		
		$reqATA="SELECT ATA,SousATA,IsolationElec FROM sp_dossier_ata WHERE Id_Dossier=".$row['Id_Dossier'];
		$resultATA=mysqli_query($bdd,$reqATA);
		$nbATA=mysqli_num_rows($resultATA);
		$ATA="";
		$Isolation="";
		if($nbATA>0){
			while($rowATA=mysqli_fetch_array($resultATA)){
				$ATA.=$rowATA['ATA']."_".$rowATA['SousATA']."\n";
				if($rowATA['IsolationElec']==0){$Isolation.="Non\n";}
				else{$Isolation.="Oui\n";}
				
			}
		}
		if($ATA<>""){$ATA=substr($ATA,0,-1);}
		$sheet->setCellValue('V'.$ligne,utf8_encode($ATA));
		if($row['ESN'] == 0){$sheet->setCellValue('W'.$ligne,utf8_encode($row['Activite']));}
		else{$sheet->setCellValue('W'.$ligne,utf8_encode($row['Activite']." + ESN"));}
		$sheet->setCellValue('X'.$ligne,utf8_encode($row['RefAInstallerCIA']));
		$sheet->setCellValue('Y'.$ligne,utf8_encode($row['Impact']));
		$sheet->setCellValue('Z'.$ligne,utf8_encode($row['RefCableCIA']));
		$sheet->setCellValue('AA'.$ligne,utf8_encode($row['Zone']));
		$sheet->setCellValue('AB'.$ligne,utf8_encode($row['TypeCIA']));
		
		$MesureSecurite="";
		if ($row['PasDeMesure']==1){
			$MesureSecurite="Pas de mesure";
		}
		else{
			$reqMS="SELECT Id_FI,NumCIA FROM sp_fi_mesuresecurite WHERE Id_FI=".$row['Id'];
			$resultMS=mysqli_query($bdd,$reqMS);
			$nbMS=mysqli_num_rows($resultMS);
			if($nbMS>0){
				while($rowMS=mysqli_fetch_array($resultMS)){
					$MesureSecurite.=$rowMS['NumCIA']."\n";
				}
			}
			if($MesureSecurite<>""){$MesureSecurite=substr($MesureSecurite,0,-1);}
		}
		
		$sheet->setCellValue('AC'.$ligne,utf8_encode($MesureSecurite));
		$sheet->setCellValue('AD'.$ligne,utf8_encode($row['HeureDebutCIA']));
		$sheet->setCellValue('AE'.$ligne,utf8_encode($row['HeureFinCIA']));
		$sheet->setCellValue('AF'.$ligne,utf8_encode($row['DateFinCIA']));
		$sheet->setCellValue('AG'.$ligne,utf8_encode($Isolation));
		$sheet->setCellValue('AI'.$ligne,utf8_encode($row['PosteInterventionCIA']));
		if($row['Pneumatique']=="APU"){
			$sheet->setCellValue('AK'.$ligne,utf8_encode("Oui"));
			$sheet->setCellValue('AL'.$ligne,utf8_encode("Non"));
		}
		elseif($row['Pneumatique']=="N/A"){
			$sheet->setCellValue('AK'.$ligne,utf8_encode("Non"));
			$sheet->setCellValue('AL'.$ligne,utf8_encode("Oui"));
		}
		elseif($row['Pneumatique']<>""){
			$sheet->setCellValue('AJ'.$ligne,utf8_encode($row['Pneumatique']));
			$sheet->setCellValue('AK'.$ligne,utf8_encode("Non"));
			$sheet->setCellValue('AL'.$ligne,utf8_encode("Non"));
		}
		else{
			$sheet->setCellValue('AK'.$ligne,utf8_encode("Non"));
			$sheet->setCellValue('AL'.$ligne,utf8_encode("Non"));
		}
		$sheet->setCellValue('AM'.$ligne,utf8_encode($row['RaisonIndicage']));
		$sheet->setCellValue('AN'.$ligne,utf8_encode($row['Type']));
		$sheet->setCellValue('AO'.$ligne,utf8_encode($row['MCA']));
		$sheet->setCellValue('AP'.$ligne,utf8_encode($row['TAI_Restant']));
		$sheet->setCellValue('AQ'.$ligne,utf8_encode($row['Caec']));
		$sheet->setCellValue('AR'.$ligne,utf8_encode($row['Id']));
		$reqRisque="SELECT Id_FI,NumCIA FROM sp_fi_risque WHERE Id_FI=".$row['Id'];
		$resultRisque=mysqli_query($bdd,$reqRisque);
		$nbRisque=mysqli_num_rows($resultRisque);
		$Risques="";
		if($nbRisque>0){
			while($rowRisque=mysqli_fetch_array($resultRisque)){
				$Risques.=$rowRisque['NumCIA']."\n";
			}
		}
		if($Risques<>""){$Risques=substr($Risques,0,-1);}
		$sheet->setCellValue('AS'.$ligne,utf8_encode($Risques));
		$sheet->setCellValue('AU'.$ligne,utf8_encode($row['DateCreationIC']));
		$powerOff="Non";
		if($row['PowerOffPartielCIA']==1){$powerOff="Oui";}
		$sheet->setCellValue('AV'.$ligne,utf8_encode($powerOff));
		$sheet->setCellValue('AW'.$ligne,utf8_encode($row['CommentairePOCIA']));
		$sheet->setCellValue('AX'.$ligne,utf8_encode($row['InfoSuppPOCIA']));
		$reqMI="SELECT Id_FI,NumCIA FROM sp_fi_moyenindustriel WHERE Id_FI=".$row['Id'];
		$resultMI=mysqli_query($bdd,$reqMI);
		$nbMI=mysqli_num_rows($resultMI);
		$MoyenIndustriel="";
		if($nbMI>0){
			while($rowMS=mysqli_fetch_array($resultMI)){
				$MoyenIndustriel.=$rowMS['NumCIA']."\n";
			}
		}
		
		if($MoyenIndustriel<>""){$MoyenIndustriel=substr($MoyenIndustriel,0,-1);}
		$sheet->setCellValue('AY'.$ligne,utf8_encode($MoyenIndustriel));
		$sheet->setCellValue('AZ'.$ligne,utf8_encode($row['CommentaireMICIA']));
		
		$ligne++;
	}

	//Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
	header('Content-Disposition: attachment;filename="IC_CIA.xlsx"'); 
	header('Cache-Control: max-age=0'); 

	$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

	$chemin = '../../../tmp/IC_CIA.xlsx';
	$writer->save($chemin);
	readfile($chemin);
}
?>