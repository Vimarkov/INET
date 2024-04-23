<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

require_once '../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
	
	
$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
		(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
		(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
		EPPBilan,EPPBilanRefuseSalarie,NbEntretienPro,ComNbEntretiensPro,ActionFormationOEPPBilan,ActionFormationNonOEPPBilan,CertifParFormation,EvolutionSalariale,EvolutionPro,
		DateEvaluateur,DateSalarie,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPP Bilan'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);


$Plateforme="";
$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Plateforme=$RowPresta['Libelle'];
}

$Manager=stripslashes($rowEPERempli['Manager']);
$MatriculeAAAManager=$rowEPERempli['MatriculeAAAManager'];
$MetierManager=stripslashes($rowEPERempli['MetierManager']);

$Titre= "<img src='../../Images/FlecheBlancheGauche.png' width='15px' border='0' />Etat des lieux récapitulatif du parcours professionnel<img width='15px' src='../../Images/FlecheBlancheDroite.png' border='0' /><br>Bilan à 6 ans";

$EPPBilanRefuseSalarie="<img src='../../Images/CaseNonCoche.png' width='10px' border='0' />";
$signatureRefus="";
if($rowEPERempli['EPPBilanRefuseSalarie']==1){$EPPBilanRefuseSalarie="<img src='../../Images/CaseCoche.png' width='10px' border='0' />";$signatureRefus=$rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." <br>'signature électronique'";}


$formulaire="
<html style='background-color:#ffffff;font-family:cursive;'>
	<head>
		<link type='text/css' href='../../CSS/FeuillePDF.css' rel='stylesheet' />
	<style>
		@font-face {
                font-family: 'Courier';           
                font-weight: normal;
                font-style: normal;
                src: url('Courier.afm') format('truetype');
        } 
		@page { margin: 110px 50px; }
		header {
                position: fixed; 
                top: -100 px; 
                left: 0px; 
                right: 0px;
                height: 400px; 
				padding-bottom: 200px;
            }
		footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                /** Extra personal styles **/
                text-align: center;
                line-height: 35px;
            }
		footer .pagenum:before {
			  content: counter(page);
		}
		footer .pagenum2:after {
			  content: counter(page);
		}
        </style>
	</head>
	<header>
		<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
			<tr>
				<td width='100px' rowspan='2'  style='border:1px solid black' align='center'><img width='100px' src='../../Images/Logos/AAA_Group.gif' border='0' /></td>
				<td width='400px' rowspan='2' height='40px' style='font:bold 14px;border:1px black solid;background-color:#002060;color:#ffffff;' align='center'>
					".$Titre."
				</td>
				<td width='100px'  style='border:1px solid black;font-size:12px;' align='center'>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width='100px' height='20px' style='font:bold 14px;border:1px black solid;font-size:12px;' align='center'>&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>
	</header>
";

$formulaire.="
<table width='100%'>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table style='border-spacing:0px;border:1px #000000 solid;padding:0px;' width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='20%' style='font-weight:bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Matricule</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['MatriculeAAA']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Date de l'entretien</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien'])."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Nom</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Nom']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Unité d'exploitation</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$Plateforme."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Prénom</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Prenom']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Evaluateur</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$Manager."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Fonction/métier</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Metier']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Matricule</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$MatriculeAAAManager."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Date d'ancienneté</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete'])."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Fonction /métier</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$MetierManager."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			1.Bilan - Cadre de l'entretien
		</td>
	</tr>
	<tr>
		<td >
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td style='border:1px #000000 solid;'>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='50%' style='font-size:8px;height:15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../Images/CaseCoche.png' width='10px' border='0' />&nbsp;&nbsp;&nbsp;&nbsp;Etat des lieux récapitulatif du parcours professionnel</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td style='height:20px;'></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td width='50%' style='font-size:8px;height:15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$EPPBilanRefuseSalarie."&nbsp;&nbsp;&nbsp;&nbsp;Le salarié ne souhaite pas bénéficier de l'entretien professionnel proposé</td>
							<td bgcolor='#bfbfbf' style='border:1px #000000 solid;font-size:8px;' align='center'>signature du salarié :</td>
							<td style='font-size:8px;' align='center'>".$signatureRefus."</td>
						</tr>
						<tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	";

	$formulaire.= "
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			2 .Bilan des EPP
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'><b>NOMBRE D'ENTRETIENS PROFESSIONNELS PERIODIQUES REALISES (date)</b><br>au cours des 6 dernières années y compris celui réalisé en même temps que le bilan</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'>".$rowEPERempli['NbEntretienPro']."<br>".stripslashes($rowEPERempli['ComNbEntretiensPro'])."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			3 .Bilan des Formations
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr bgcolor='#1a0078'>
							<td colspan='2' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>ACTIONS DE FORMATION REALISEES</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'><b>ACTIONS DE FORMATION OBLIGATOIRES REALISEES<br>(Date et intitulé)</b><br>C’est-à-dire qui conditionne l’exercice d’une activité ou d’une fonction en application d’une convention internationale ou de dispositions légales et réglementaires</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'>".nl2br(stripslashes($rowEPERempli['ActionFormationOEPPBilan']))."</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'><b>ACTIONS DE FORMATION NON OBLIGATOIRES REALISEES<br>(Date et intitulé)</b><br>C’est-à-dire autre qu’une action de formation qui conditionne l’exercice d’une activité ou d’une fonction en application d’une convention internationale ou de dispositions légales et réglementaires</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'>".nl2br(stripslashes($rowEPERempli['ActionFormationNonOEPPBilan']))."</td>
						</tr>
						<tr bgcolor='#1a0078'>
							<td colspan='2' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>
							ELEMENTS DE CERTIFICATION OBTENUS
							</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'><b>PAR LA FORMATION  ou la VAE<br>(Date et intitulé)</b></td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:80px;' align='center'>".nl2br(stripslashes($rowEPERempli['CertifParFormation']))."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			4 .Bilan - Progression Salariale ou Professionnelle
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:80px;'><b>Evolution salariale (année)</b><br>Augmentation individuelle ou Générale</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:80px;' colspan='3' align='center'>".nl2br(stripslashes($rowEPERempli['EvolutionSalariale']))."</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:80px;'><b>Evolution professionnelle (année)</b><br>Changement de métier, progression en terme de responsabilités, changement de classification, etc.</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:80px;' colspan='3' align='center'>".nl2br(stripslashes($rowEPERempli['EvolutionPro']))."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			5 .BIlan- Signature
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Fait le :</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
								$formulaire.= AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']);
							$formulaire.= "</td>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Transmis à la DRH le :</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
								$formulaire.= AfficheDateJJ_MM_AAAA($rowEPERempli['DateEvaluateur']);
							$formulaire.= "</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Visa du collaborateur</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
							if($rowEPERempli['EPPBilanRefuseSalarie']==0){
								$formulaire.= $rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." <br>'signature électronique'";
							}
							$formulaire.= "</td>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Visa de l'évaluateur</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
								$formulaire.= $Manager." <br>'signature électronique'";
							$formulaire.= "</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>";
?>

<?php 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

// add the header
$canvas = $dompdf->get_canvas();
$font = 0;  
// the same call as in my previous example
$canvas->page_text(550, 770, "{PAGE_NUM} / {PAGE_COUNT}", 0, 6, array(0,0,0));
$canvas->page_text(200, 765, "DOCUMENT DIRECTION QUALITE AAA GROUP", $font, 6, array(0,0,0));
$canvas->page_text(190, 775, "Reproduction interdite sans autorisation Ã©crite de AAA Group", $font, 6, array(0,0,0));
$canvas->page_text(10, 765, "D-0705/014 - Edition 1", $font, 6, array(0,0,0));
$canvas->page_text(10, 775, "15/03/2021", $font, 6, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();

?>