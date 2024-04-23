<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(formulaire.Langue.value=="FR"){
				if(formulaire.dateDeclaration.value==''){alert('Vous n\'avez pas renseigné la date.');return false;}
				if(formulaire.personne.value=='0'){alert('Vous n\'avez pas renseigné la personne.');return false;}
				if(formulaire.prestation.value=='0_0'){alert('Vous n\'avez pas renseigné la prestation.');return false;}
				if(formulaire.lieu.value==''){alert('Vous n\'avez pas renseigné le lieu.');return false;}
			}
			else{
				if(formulaire.dateDeclaration.value==''){alert('You did not fill in the date.');return false;}
				if(formulaire.personne.value=='0'){alert('You did not inform the person.');return false;}
				if(formulaire.prestation.value=='0_0'){alert('You did not fill in the site.');return false;}
				if(formulaire.lieu.value==''){alert('You have not specified the place.');return false;}
			}
			return true;
		}
		function OuvreFenetreSuppr(Type,Id,Id_Mouvement){
			var w=window.open("Suppr_Perte.php?Type="+Type+"&Id="+Id+"&Id_Mouvement="+Id_Mouvement,"PageToolsSuppr","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
		function OuvreFenetreExcel(Id)
			{window.open("DeclarationPerte.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

if($_POST)
{
	if(isset($_POST['valider'])){
		//Nb Ref de cette année (compter les supprimés)
		$req="SELECT Id FROM tools_mouvement WHERE TypeMouvement=2 AND YEAR(PV_Date)=".date('Y',strtotime(TrsfDate_($_POST['dateDeclaration'])." + 0 day"))." ";
		$Result=mysqli_query($bdd,$req);
		$NbEnreg=mysqli_num_rows($Result);
		
		$refDeclaration=$NbEnreg+1;
		$refDeclaration.="/".date('Y',strtotime(TrsfDate_($_POST['dateDeclaration'])." + 0 day"));
		
		$tab=explode("_",$_POST['prestation']);
		$Id_Prestation=$tab[0];
		$Id_Pole=$tab[1];
		$Id_Metier=IdMetierEC($_POST['personne']);
		//Ajout de l'étalonnage
		$RequeteMouvement="
			INSERT INTO
				tools_mouvement
			(
				Type,
				TypeMouvement,
				Id_Materiel__Id_Caisse,
				PV_Date,
				PV_RefDeclaration,
				PV_Id_Declarant,
				PV_Id_Prestation,
				PV_Id_Pole,
				PV_Id_Metier,
				PV_Type,
				PV_Lieu,
				PV_Remarque,
				PV_Poste,
				PV_TypeMSN,
				PV_MSN,
				PV_Zone,
				PV_Condition,
				PV_Action,
				PV_Id_PersonneMAJ,
				PV_DateMAJ
			)
			VALUES
			(
				'".$_POST['Type']."',
				'2',
				'".$_POST['Id']."',
				'".TrsfDate_($_POST['dateDeclaration'])."',
				'".$refDeclaration."',
				'".$_POST['personne']."',
				'".$Id_Prestation."',
				'".$Id_Pole."',
				'".$Id_Metier."',
				'".$_POST['typeDeclaration']."',
				'".addslashes($_POST['lieu'])."',
				'".addslashes($_POST['remarques'])."',
				'".addslashes($_POST['poste'])."',
				'".addslashes($_POST['typeAvion'])."',
				'".addslashes($_POST['msn'])."',
				'".addslashes($_POST['zone'])."',
				'".addslashes($_POST['condition'])."',
				'".addslashes($_POST['action'])."',
				'".$IdPersonneConnectee."',
				'".$DateJour."'
			);";
		$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
		$Id_Mouvement=mysqli_insert_id($bdd);
		
		

		//1 Créer le fichier Excel 
		if($_POST['typeDeclaration']<>1){
			//echo "<script>window.open('DeclarationPerte.php?Id=".$Id_Mouvement."','Fiche_DeclarationPerte','status=no,menubar=no,width=20,height=20');</script>";
			
			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
			$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

			//Ouvrir fichier
			$workbook = new PHPExcel_Reader_Excel2007();

			if($LangueAffichage=="FR"){$excel = $workbook->load('D-0829-004-GRP.xlsx');}
			else{$excel = $workbook->load('D-0829-004-GRP-en.xlsx');}


			$sheet = $excel->getSheetByName('D-0829-004');

			$req="SELECT Id,
				Type,
				Id_Materiel__Id_Caisse,
				PV_Date,
				PV_RefDeclaration,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Personne,
				(SELECT Matricule FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Matricule,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
				PV_Poste,
				PV_TypeMSN,
				PV_MSN,
				PV_Zone,
				PV_Condition,
				PV_Action,
				PV_Type,
				PV_Lieu,
				PV_Remarque
				FROM tools_mouvement
				WHERE Suppr=0
				AND Id=".$Id_Mouvement."
				ORDER BY PV_Date DESC, Id DESC
			";
			$Result=mysqli_query($bdd,$req);
			$NbEnreg=mysqli_num_rows($Result);
			$row=mysqli_fetch_array($Result);

			$pole="";
			if($row['Pole']<>""){$pole=" (".$row['Pole'].") ";}
			$sheet->setCellValue('E10',utf8_encode($row['Prestation'].$pole." ".$row['PV_Lieu']));
			$sheet->setCellValue('J10',utf8_encode($row['PV_Poste']));

			$sheet->setCellValue('E13',utf8_encode(AfficheDateJJ_MM_AAAA($row['PV_Date'])));
			$sheet->setCellValue('I13',utf8_encode($row['PV_TypeMSN']));
			$sheet->setCellValue('M13',utf8_encode($row['PV_MSN']));

			$sheet->setCellValue('F15',utf8_encode($row['Personne']));
			$sheet->setCellValue('D17',utf8_encode($row['Matricule']));

			$sheet->setCellValue('G21',utf8_encode("1"));

			$sheet->setCellValue('B24',utf8_encode($row['PV_Zone']));
			$sheet->setCellValue('B29',utf8_encode($row['PV_Condition']));
			$sheet->setCellValue('B38',utf8_encode($row['PV_Action']));
			$sheet->setCellValue('B47',utf8_encode($row['PV_Remarque']));

			if($row['Type']==0){
				$Requete="
					SELECT
						NumAAA,
						(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) AS Type,
						(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS Designation,
						IF((SELECT tools_mouvement.Id_Caisse
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
							(
								SELECT (
									SELECT new_competences_prestation.Id_Plateforme
									FROM tools_mouvement
									LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
									WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
									ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
								)
								FROM tools_mouvement AS TAB_Mouvement
								LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
								LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							),
						(
							SELECT new_competences_prestation.Id_Plateforme
							FROM tools_mouvement
							LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
							WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)) AS Id_Plateforme

					FROM
						tools_materiel
					WHERE
						Id=".$row['Id_Materiel__Id_Caisse']."";
			}
			else{
				$Requete="
					SELECT
						NumAAA,
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType ) AS Type,
						(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS Designation,
						(
							SELECT new_competences_prestation.Id_Plateforme
							FROM tools_mouvement
							LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS Id_Plateforme
					FROM
						tools_caisse
					WHERE
						Id='".$row['Id_Materiel__Id_Caisse']."';";
			}
			$ResultMat=mysqli_query($bdd,$Requete);
			$NbEnregMat=mysqli_num_rows($ResultMat);
			$rowMat=mysqli_fetch_array($ResultMat);

			$sheet->setCellValue('B21',utf8_encode($rowMat['Type']." - ".$rowMat['Designation']));
			$sheet->setCellValue('I21',utf8_encode($rowMat['NumAAA']));

			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('case');
			$objDrawing->setDescription('PHPExcel case');

			$objDrawing2 = new PHPExcel_Worksheet_Drawing();
			$objDrawing2->setName('case');
			$objDrawing2->setDescription('PHPExcel case');

			if($row['PV_Type']==0){
				$objDrawing->setPath('../../Images/CaseCoche.png');
				$objDrawing2->setPath('../../Images/CaseNonCoche.png');
			}
			else{
				$objDrawing->setPath('../../Images/CaseNonCoche.png');
				$objDrawing2->setPath('../../Images/CaseCoche.png');
			}

			$objDrawing->setWidth(25);
			$objDrawing->setHeight(25);
			$objDrawing->setCoordinates('C7');
			$objDrawing->setOffsetX(30);
			$objDrawing->setOffsetY(5);
			$objDrawing->setWorksheet($sheet);

			$objDrawing2->setWidth(25);
			$objDrawing2->setHeight(25);
			$objDrawing2->setCoordinates('C8');
			$objDrawing2->setOffsetX(30);
			$objDrawing2->setOffsetY(5);
			$objDrawing2->setWorksheet($sheet);


			$Id_Plateforme=0;
			if($Id_Plateforme==0){
				if($rowMat['Id_Plateforme']<>0 && $rowMat['Id_Plateforme']<>""){$Id_Plateforme=$rowMat['Id_Plateforme'];}
			}

			if($Id_Plateforme>0){
				$req="SELECT Libelle, Logo FROM new_competences_plateforme WHERE Id=".$Id_Plateforme;
				$ResultPlat=mysqli_query($bdd,$req);
				$rowPlat=mysqli_fetch_array($ResultPlat);
				
				$sheet->setCellValue('K6',utf8_encode($rowPlat['Libelle']));
				if($rowPlat['Logo']<>""){
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('logo');
					$objDrawing->setDescription('PHPExcel logo');
					$objDrawing->setPath('../../Images/Logos/'.$rowPlat['Logo']);
					$objDrawing->setHeight(80);
					$objDrawing->setWidth(150);
					$objDrawing->setCoordinates('K2');
					$objDrawing->setOffsetX(30);
					$objDrawing->setOffsetY(8);
					$objDrawing->setWorksheet($sheet);
				}
			}


			//Enregistrement du fichier excel
			/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
			header('Content-Disposition: attachment;filename="D-0829-004.xlsx"');
			header('Cache-Control: max-age=0');*/

			$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$chemin = '../../tmp/D-0829-004.xlsx';
			$writer->save($chemin);
			
			//ENVOYER EMAIL
			//GESTIONNAIRES MGX + RESP MGX +  INFORMATIQUE + RESP QUALITE PLATEFORME + RESP PLATEFORME + COOR QUALITE PRESTATION + RESP PROJET PRESTATION
			
			$req="SELECT Id,
				Type,
				Id_Materiel__Id_Caisse,
				PV_Date,
				PV_RefDeclaration,
				PV_Id_Prestation,
				PV_Id_Pole,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Id_Plateforme,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Personne,
				(SELECT Matricule FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Matricule,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
				PV_Poste,
				PV_TypeMSN,
				PV_MSN,
				PV_Zone,
				PV_Condition,
				PV_Action,
				PV_Type,
				PV_Lieu,
				PV_Remarque
				FROM tools_mouvement
				WHERE Suppr=0
				AND Id=".$Id_Mouvement."
				ORDER BY PV_Date DESC, Id DESC
			";
			$Result=mysqli_query($bdd,$req);
			$NbEnreg=mysqli_num_rows($Result);
			$row=mysqli_fetch_array($Result);

			if($row['Type']==0){
				$Requete="
					SELECT
						NumAAA
					FROM
						tools_materiel
					WHERE
						Id=".$row['Id_Materiel__Id_Caisse']."";
			}
			else{
				$Requete="
					SELECT
						NumAAA
					FROM
						tools_caisse
					WHERE
						Id='".$row['Id_Materiel__Id_Caisse']."';";
			}
			$ResultMat=mysqli_query($bdd,$Requete);
			$NbEnregMat=mysqli_num_rows($ResultMat);
			$rowMat=mysqli_fetch_array($ResultMat);

			if($_SESSION['Langue']=="FR"){
				if($_POST['typeDeclaration']==0){$type="perte";}
				else{$type="découverte";}
				$sujet="Déclaration de ".$type." - ".$rowMat['NumAAA']." [".$row['Prestation']." ".$row['Pole']."]";
				$message_html="	<html>
					<head><title>".$sujet."</title></head>
					<body>
						Bonjour,
						<br>
						Veuillez trouver ci-joint une nouvelle déclaration de ".$type." pour le n° AAA : ".$rowMat['NumAAA']."
						<br>
						<br>
						Bonne journée,<br>
						L'Extranet Daher industriel services DIS.
					</body>
				</html>";
			}
			else{
				if($_POST['typeDeclaration']==0){$type="loss";}
				else{$type="discovery";}
				$sujet="Declaration of ".$type." - ".$rowMat['NumAAA'];
				$message_html="	<html>
					<head><title>".$sujet."</title></head>
					<body>
						Hello,
						<br>
						Please find attached a new declaration of ".$type." for the number AAA : ".$rowMat['NumAAA']."
						<br>
						<br>
						Have a good day.<br>
						Extranet Daher industriel services DIS.
					</body>
				</html>";
			}
			
			
			$PJ = array();

			$pj_item = array();
			$pj_item['chemin'] = '../../tmp/';
			$pj_item['nom'] = 'D-0829-004.xlsx';
			$pj_item['MIME-Type'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
			$pj_item['attachement'] = encoderFichier('../../tmp/D-0829-004.xlsx');
			
			array_push($PJ, $pj_item);

			$destinataire="pfauge@aaa-aero.com,";
			
			
			$Emails="";
			
			//GESTIONNAIRES MGX + RESP MGX + RESP QUALITE PLATEFORME + RESP PLATEFORME + BACKUP
			$reqMail="SELECT DISTINCT EmailPro, CONCAT(Nom,' ',Prenom) AS Personne 
					FROM new_competences_personne_poste_plateforme 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableMGX.",".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.")
					AND Id_Plateforme=".$row['Id_Plateforme']." ";
			$ResultMail=mysqli_query($bdd,$reqMail);
			$NbMail=mysqli_num_rows($ResultMail);
			if($NbMail>0){
				while($RowMail=mysqli_fetch_array($ResultMail)){
					if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
				}
				
			}

			//COOR QUALITE PRESTATION + RESP PROJET PRESTATION
			$reqMail="SELECT DISTINCT EmailPro, CONCAT(Nom,' ',Prenom) AS Personne  
					FROM new_competences_personne_poste_prestation
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.")
					AND Id_Prestation=".$row['PV_Id_Prestation']." 
					AND Id_Pole=".$row['PV_Id_Pole']." ";
			$ResultMail=mysqli_query($bdd,$reqMail);
			$NbMail=mysqli_num_rows($ResultMail);
			if($NbMail>0){
				while($RowMail=mysqli_fetch_array($ResultMail)){
					if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
				}
			}
		
			if($Emails<>""){$Emails=substr($Emails,0,-1);}
			envoyerMailRH($Emails, $sujet, "", $message_html, $PJ);
				
			}
		 echo "<script>window.close();</script>";
	}
	
}

$Id=0;
$Type=-1;
if($_POST){$Id=$_POST['Id'];$Type=$_POST['Type'];}
else{$Id=$_GET['Id'];$Type=$_GET['Type'];}
	if($Type==0){
		$Requete="
				SELECT
					NumAAA
				FROM
					tools_materiel
				WHERE
					Id='".$Id."';";
	}
	else{
		$Requete="
				SELECT
					Num AS NumAAA
				FROM
					tools_caisse
				WHERE
					Id='".$Id."';";
	}
		
	$Result=mysqli_query($bdd,$Requete);
	$Row=mysqli_fetch_array($Result);
?>
<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="hidden" name="Type" value="<?php echo $Type; ?>">
<input type="hidden" name="Langue" value="<?php echo $_SESSION['Langue'];?>">
<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#d286b4;">
	<tr>
		<td class="TitrePage">
		<?php
		if($LangueAffichage=="FR"){echo "Perte/Vol/Découverte ".$Row['NumAAA'];}else{echo "Loss / Theft / Discovery ".$Row['NumAAA'];}
		?>
		</td>
	</tr>
</table><br>
<table style="width:100%; height:95%; align:center;" class="TableCompetences">
	<tr>
		<td style="color:#22b63d" class="Libelle"  align="center" colspan="6"><?php if($LangueAffichage=="FR"){echo "AJOUT";}else{echo "ADD";} ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
		<td>
			<select name="prestation" id="prestation" style="width:100px">
				<option value="0_0"></option>
				<?php
					$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
						FROM new_competences_prestation
						WHERE Active=0
						AND Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole    
							WHERE Actif=0
						)
						
						UNION 
						
						SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
							new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
							FROM new_competences_pole
							INNER JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							AND Active=0
							AND Actif=0
							
						ORDER BY Libelle, LibellePole";
					$resultsite=mysqli_query($bdd,$requeteSite);
					$i=0;
					
					while($rowsite=mysqli_fetch_array($resultsite))
					{
						echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' >";
						$pole="";
						if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
						echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
					}
				?>
			</select>
		</td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?> : </td>
		<td><input name="lieu" size="50" type="text" value=""></td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Poste";}else{echo "Work station";}?> : </td>
		<td><input name="poste" size="50" type="text" value=""></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type d'aéronef";}else{echo "Type of aircraft";}?> : </td>
		<td><input name="typeAvion" size="20" type="text" value=""></td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "MSN / SN";}else{echo "MSN / SN:";}?> : </td>
		<td><input name="msn" size="20" type="text" value=""></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> : </td>
		<td>
			<select name="typeDeclaration">
				<option value="0" selected><?php if($LangueAffichage=="FR"){echo "Perte";}else{echo "Loss";}?></option>
				<option value="1" ><?php if($LangueAffichage=="FR"){echo "Vol";}else{echo "Theft";}?></option>
				<option value="2" ><?php if($LangueAffichage=="FR"){echo "Découverte";}else{echo "Discovery";}?></option>
			</select>
		</td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date de déclaration";}else{echo "Reporting date";}?> : </td>
		<td><input name="dateDeclaration" size="25" type="date" value=""></td>
		<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Déclarant :";}else{echo "Declarer :";} ?></td>
		<td>
			<select name="personne" id="personne">
			<?php
			$rq="SELECT Id, CONCAT(Nom,' ',Prenom) AS Personne
				FROM new_rh_etatcivil 
				ORDER BY Personne ASC ";
			$resul=mysqli_query($bdd,$rq);
			while($row=mysqli_fetch_array($resul))
			{
				$selected="";
				if($row['Id']==$_SESSION['Id_Personne']){$selected="selected";}
				echo "<option value='".$row['Id']."' ".$selected." >".str_replace("'"," ",stripslashes($row['Personne']))."</option>\n";
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle" colspan="6" valign="top"><?php if($LangueAffichage=="FR"){echo "Si perte, dans quelle zone de l'aéronef l'objet a-t-il été utilisé pour la dernière fois ";}else{echo "In case of loss, in which area of the aircraft has the tool been used for the last time ";}?> : </td>
	</tr>
	<tr>
		<td colspan="6"><textarea name="zone" rows="3" cols="100" rows="3" style="resize: none;"></textarea></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle" colspan="6" valign="top"><?php if($LangueAffichage=="FR"){echo "Conditions particuières lors de la perte ou de la découverte de l'objet";}else{echo "Special circumstances when the tool has been lost or found";}?> : </td>
	</tr>
	<tr>
		<td colspan="6"><textarea name="condition" rows="3" cols="100" rows="3" style="resize: none;"></textarea></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle" colspan="6" valign="top"><?php if($LangueAffichage=="FR"){echo "Si perte, actions engagées";}else{echo "In case of loss, actions carried out";}?> : </td>
	</tr>
	<tr>
		<td colspan="6"><textarea name="action" rows="3" cols="100" rows="3" style="resize: none;"></textarea></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle" colspan="6" valign="top"><?php if($LangueAffichage=="FR"){echo "Conclusion";}else{echo "Conclusion";}?> : </td>
	</tr>
	<tr>
		<td colspan="6"><textarea name="remarques" rows="3" cols="100" rows="3" style="resize: none;"></textarea></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="6" align="center">
			<input class="Bouton" name="valider" type="submit"
			<?php
				if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
			?>
			>
		</td>
	</tr>
</table><br>
<table style="width:100%; height:95%; align:center;" class="TableCompetences">
	<tr>
		<td style="color:#22b63d" class="Libelle" align="center" colspan="10"><?php if($LangueAffichage=="FR"){echo "HISTORIQUE";}else{echo "HISTORICAL";} ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="EnTeteTableauCompetences"></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Ref. déclaration";}else{echo "Ref declaration";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Déclarant";}else{echo "Declarer";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type d'aéronef";}else{echo "Type of aircraft";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "MSN/SN";}else{echo "MSN/SN";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Condition";}else{echo "Circumstances";}?></td>
		<td class="EnTeteTableauCompetences" width="2%"></td>
	</tr>
	<?php
		$req="SELECT Id,
			PV_Date,
			PV_RefDeclaration,PV_Id_Pole,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Personne,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
			PV_Poste,
			PV_TypeMSN,
			PV_MSN,
			PV_Zone,
			PV_Condition,
			PV_Action,
			PV_Type,
			PV_Lieu,
			PV_Remarque
			FROM tools_mouvement
			WHERE Suppr=0
			AND Id_Materiel__Id_Caisse=".$Id."
			AND Type=".$Type."
			AND TypeMouvement=2
			ORDER BY PV_Date DESC, Id DESC
		";
		$Result=mysqli_query($bdd,$req);
		$NbEnreg=mysqli_num_rows($Result);
		if($NbEnreg>0)
		{
		$Couleur="#EEEEEE";
		while($Row=mysqli_fetch_array($Result))
		{
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
			$type="";
			if($Row['PV_Type']==0){if($LangueAffichage=="FR"){$type= "Perte";}else{$type= "Loss";}}
			elseif($Row['PV_Type']==1){if($LangueAffichage=="FR"){$type= "Vol";}else{$type= "Theft";}}
			else{if($LangueAffichage=="FR"){$type= "Découverte";}else{$type= "Discovery";}}
			
			$pole="";
			if($Row['PV_Id_Pole']>0){$pole=" - ".$Row['Pole'];}
	?>
		<tr bgcolor="<?php echo $Couleur;?>">
			<td><?php if($Row['PV_Type']<>1){ ?><a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('<?php echo $Row['Id']; ?>');" ><img src="../../Images/excel.gif" border="0"></a><?php } ?></td>
			<td><?php echo AfficheDateJJ_MM_AAAA($Row['PV_Date']);?></td>
			<td><?php echo stripslashes($Row['PV_RefDeclaration']);?></td>
			<td><?php echo stripslashes($Row['Personne']);?></td>
			<td><?php echo stripslashes($Row['Prestation'].$pole);?></td>
			<td><?php echo stripslashes($type);?></td>
			<td><?php echo stripslashes($Row['PV_Lieu']);?></td>
			<td><?php echo stripslashes($Row['PV_TypeMSN']);?></td>
			<td><?php echo stripslashes($Row['PV_MSN']);?></td>
			<td><?php echo nl2br(stripslashes($Row['PV_Condition']));?></td>
			<td><input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreSuppr('<?php echo $Type; ?>','<?php echo $Id; ?>','<?php echo $Row['Id']; ?>');}"></td>
		</tr>
	<?php
		}	//Fin boucle
	}		//Fin If
	mysqli_free_result($Result);	// Libération des résultats
	?>
</table>
</form>
</body>
</html>