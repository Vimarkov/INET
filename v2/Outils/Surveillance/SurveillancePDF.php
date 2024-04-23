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

$requeteSurveillance = "
    SELECT
        new_surveillances_surveillance.ID,
		new_surveillances_questionnaire.ID_Theme,
        (SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
        new_surveillances_questionnaire.Nom AS Questionnaire,
        new_surveillances_questionnaire.ID AS ID_Questionnaire,
        new_competences_prestation.Id_Plateforme AS Id_Plateforme,
        (SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme,
        LEFT(new_competences_prestation.Libelle,7) AS Prestation,
        new_competences_prestation.Id AS Id_Prestation,
        new_surveillances_surveillance.ID_Surveille AS ID_Surveille,
        new_surveillances_surveillance.ID_Surveillant AS ID_Surveillant,
        (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveille) AS Surveille,
        (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveillant) AS Surveillant,
        new_surveillances_surveillance.DatePlanif AS DatePlanif,
        new_surveillances_surveillance.DateReplanif AS DateReplanif,
        IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance,
        IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') AS Etat,
		new_surveillances_surveillance.Etat AS Etat2,
		new_surveillances_surveillance.NumActionTracker,SignatureSurveillant,SignatureSurveille
    FROM
        (
            (
            new_surveillances_surveillance
            LEFT JOIN new_competences_prestation
                ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id
            )
        LEFT JOIN new_surveillances_questionnaire
            ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id
        ) ";
$requeteSurveillance.=" WHERE new_surveillances_surveillance.ID=".$_GET['Id'];

$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

if($LigneSurveillance['ID_Theme']==2){$leD="D-0902 - Issue 1";}
else{$leD="D-0901 - Issue 1";}

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
				<td width='160px' rowspan='3'  style='border:1px solid black' align='center'><img width='100px' src='../../Images/Logos/AAA_Group.gif' border='0' /></td>
				<td width='300px' height='40px' style='font:bold 14px;border:1px black solid;background-color:#e7e6e6;' align='center'>
					<img src='../../Images/FlecheGauche.png' width='15px' border='0' />SURVEILLANCE OPERATIONNELLE /<br> <span style='color:#0527ff;'>OPERATIONAL SURVEILLANCE</span><img width='15px' src='../../Images/FlecheDroite.png' border='0' />
				</td>
				<td width='160px' rowspan='2'  style='border:1px solid black' align='center'>".$LigneSurveillance['Plateforme']."</td>
			</tr>
			<tr>
				<td width='80%' height='20px' style='font:bold 14px;border:1px black solid;color:#0527ff;' align='center'>N° ".$LigneSurveillance['ID']."</td>
			</tr>
			<tr>
				<td width='80%' height='10px' style='font:bold 14px;border:1px black solid;color:#0527ff;' align='center'>".$LigneSurveillance['Questionnaire']."</td>
				<td width='10%' style='border:1px solid black' align='center'>".$LigneSurveillance['Prestation']."</td>
			</tr>
		</table>
	</header>
	<footer>
		<table width='100%' cellpadding='0' cellspacing='0' height='100%'>
			<tr>
				<td width='25%' style='font:8px;' >
					".$leD."
				</td>
				<td width='50%' style='font:8px;' align='center'>
					AAA GROUP QUALITY MANAGEMENT DOCUMENT
				</td>
				<td width='25%' style='font:8px;' align='right'>
				</td>
			</tr>
			<tr>
				<td style='font:8px;'>
					01/09/2017
				</td>
				<td style='font:8px;' align='center'>
					Reproduction forbidden without written authorization by AAA Group
				</td>
				<td>
					
				</td>
			</tr>
		</table>
		
	</footer>
";

$formulaire.="
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='25%' height='10px' style='font:bold 10px;border:1px black solid;' align='center'>SURVEILLANCE EFFECTUEE LE :<br> <span style='color:#0527ff;'>MONITORING DONE ON :</span></td>
		<td width='25%' style='font:10px;border:1px solid black' align='center'>".AfficheDateJJ_MM_AAAA($LigneSurveillance['DateSurveillance'])."</td>
		<td width='5%' height='10px' style='font:bold 10px;border:1px black solid;' align='center'>PAR :<br> <span style='color:#0527ff;'>BY :</span></td>
		<td width='45%' style='font:10px;border:1px solid black' align='center'>".stripslashes($LigneSurveillance['Surveillant'])."</td>
	</tr>
	<tr>
		<td width='25%' height='10px' style='font:bold 10px;border:1px black solid;' align='center'>COLLABORATEUR SURVEILLE :<br> <span style='color:#0527ff;'>MONITORED COLLABORATOR :</span></td>
		<td width='25%' style='font:10px;border:1px solid black' align='center'>".stripslashes($LigneSurveillance['Surveille'])."</td>
		<td></td>
		<td></td>
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='50%' height='10px' rowspan='2' colspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Question posée<br> <span style='color:#0527ff;'>Question asked</span></td>
		<td width='15%' height='10px' colspan='3' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Constat <span style='color:#0527ff;'> /Statement</span></td>
		<td width='35%' height='10px' rowspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Description de la NC / Preuves - Actions<br> <span style='color:#0527ff;'>NC Description / Evidences - Actions</span></td>
	</tr>
	<tr>
		<td width='5%' height='10px' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>C</td>
		<td width='5%' height='10px' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>NC</td>
		<td width='5%' height='10px' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>NA</td>
	</tr>
	<tr>
		<td width='5%' height='6px' colspan='6' style='font:bold 8px;border:1px black solid;background-color:#dce6f1;' align='center'></td>
	</tr>
";
$reqQuestionSurveillance = "
	SELECT
		new_surveillances_surveillance_question.ID,
		new_surveillances_question.Numero,
		new_surveillances_question.Question,
		new_surveillances_question.Question_EN,
		new_surveillances_question.Modifiable,
		new_surveillances_surveillance_question.QuestionModifiable,
		new_surveillances_surveillance_question.Etat,
		new_surveillances_surveillance_question.Commentaire,
		new_surveillances_surveillance_question.Action,
		new_surveillances_surveillance_question.Cloturee
	FROM
		new_surveillances_question
	LEFT JOIN new_surveillances_surveillance_question
		ON new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question
	WHERE
		new_surveillances_surveillance_question.ID_Surveillance =".$LigneSurveillance['ID']."
	ORDER BY
		new_surveillances_question.Numero ;";
$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
$nbQuestion=mysqli_num_rows($resultQuestion);
$total = 0;
$C = 0;
if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		if($rowQuestion['Modifiable'] == "0"){$laQuestion.= "FR : ".$rowQuestion['Question']."<br>EN : ".$rowQuestion['Question_EN'];}
		else{$laQuestion = str_replace("EN","<br>EN",$rowQuestion['QuestionModifiable']);}
		
		$checkC = "";
		$checkNC = "";
		$checkNA = "";
		$observation ="";
		$action = "";
		$cloture = "";
		if ($LigneSurveillance['Etat2'] <> "Planifié" && $LigneSurveillance['Etat2'] <> "Replanifié")
		{	
			if ($rowQuestion['Etat'] == "NC")
			{
				$checkNC = "X";

				$total++;
			}
			elseif ($rowQuestion['Etat'] == "C")
			{
				$checkC = "X";
				$total++;
				$C++;
			}
			elseif($rowQuestion['Etat'] == "NA"){$checkNA = "X";}
			$observation = $rowQuestion['Commentaire'];
			$action = $rowQuestion['Action'];
			$cloture = $rowQuestion['Cloturee'];
		}
		else{$checkC = "X";}
		
		if($observation<>"" && $action<>""){
			$observation.="<br>".$action;
		}
		elseif($observation=="" && $action<>""){
			$observation.=$action;
		}
		
		
		$formulaire.="
		<tr>
			<td width='10px' height='10px'style='font:8px;border-left:1px black solid;border-top:1px black solid;border-bottom:1px black solid;' align='center'>".$rowQuestion['Numero']."</td>
			<td width='45%' height='10px'style='font:8px;border-right:1px black solid;border-top:1px black solid;border-bottom:1px black solid;;'>".$laQuestion."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$checkC."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid' align='center'>".$checkNC."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$checkNA."</td>
			<td width='35%' height='10px' style='font:8px;border:1px black solid;'  align='center'>".stripslashes($observation)."</td>
		</tr>";
	}
}

if($total>0){
	$note = round(($C/$total)*100,0);
}
else{
	$note = 0;
}

$couleur="#ff0000";
$valeur="Non Atteint / Not Reached";
if($note>=80){$couleur="#197b16";$valeur="Atteint / Reached";}

$ActionTracker="NON / NO";
if($LigneSurveillance['NumActionTracker']>0){
	$ActionTracker="OUI / YES";
}

$formulaire.="</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='50%' colspan='3' height='10px' style='font:bold 10px;' align='right'>Taux de bonne réponses / <span style='color:#0527ff;'>Ratio of correct answers :</span></td>
		<td width='10%' style='font:bold 10px;border-top:1px solid black;border-bottom:1px solid black;color:".$couleur.";' align='center'>".$note." %</td>
		<td width='10%'></td>
		<td width='10%'></td>
		<td style='font:10px;' align='left'>C : Conforme</td>
	</tr>
	<tr>
		<td colspan='3'></td>
		<td></td>
		<td></td>
		<td></td>
		<td style='font:10px;' align='left'>NC : Non Conforme</td>
	</tr>
	<tr>
		<td width='50%' colspan='3' height='10px' style='font:10px;' align='right'>Taux de bonne réponses requis : <br> <span style='color:#0527ff;'>Requested ratio of correct answers :</span></td>
		<td style='font:10px;'>80 %</td>
		<td></td>
		<td></td>
		<td style='font:10px;' align='left'>NA : Non Applicable</td>
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='25%' height='10px' style='font:bold 10px;border:1px solid black;' align='center'>Résultat /<br><span style='color:#0527ff;'>Result</span></td>
		<td width='30%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;color:".$couleur.";' align='center'>".$valeur."</td>
		<td width='45%'></td>
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='50%' height='10px' style='font:bold 10px;' align='center'>Action(s) supplémentaire(s) / Additional Action(s) : <br><span style='color:#6a746f;'>à traçer sur Action Tracker / To be tracked in Action Tracker :</span></td>
		<td width='10%' style='font:bold 10px;border:1px solid black;' align='center'>".$ActionTracker."</td>
";
if($ActionTracker<>"OUI / YES"){
	$formulaire.="<td width='40%'></td>";
}
else{
	$formulaire.="<td width='10%' height='10px' style='font:bold 10px;' align='right'>N° :</td>
		<td width='10%' style='font:bold 10px;border:1px solid black;' align='center'>".$LigneSurveillance['NumActionTracker']."</td>
		<td width='20%'></td>
		";
}

$signatureSurveillant="";
$signatureSurveille="";
if($LigneSurveillance['SignatureSurveillant']>0){
	$signatureSurveillant="SIGNE/SIGNED";
}
if($LigneSurveillance['SignatureSurveille']>0){
	$signatureSurveille="SIGNE/SIGNED";
}

$formulaire.="
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='20%'></td>
		<td width='30%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>Signature Surveillant <span style='color:#0527ff;'>/ Supervisor</span></td>
		<td width='5%'></td>
		<td width='30%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>Signature Surveillé <span style='color:#0527ff;'>/ Supervised</span></td>
		<td width='15%'></td>
	</tr>
	<tr>
		<td width='20%'></td>
		<td width='30%' height='50px' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>".$signatureSurveillant."</td>
		<td width='5%'></td>
		<td width='30%' height='50px' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>".$signatureSurveille."</td>
		<td width='15%'></td>
	</tr>
</table>
";


$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

// add the header
$canvas = $dompdf->get_canvas();

// the same call as in my previous example
$canvas->page_text(550, 770, "{PAGE_NUM} / {PAGE_COUNT}", 0, 6, array(0,0,0));
					  
// Output the generated PDF to Browser
$dompdf->stream();
?>