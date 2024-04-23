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
        new_surveillances_questionnaire.ID_Theme,
        (SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
        new_surveillances_questionnaire.Nom AS Questionnaire,
        new_surveillances_questionnaire.ID AS ID_Questionnaire
    FROM new_surveillances_questionnaire
     WHERE new_surveillances_questionnaire.ID=".$_GET['Id'];
	 
$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

if($LigneSurveillance['ID_Theme']==2){$leD="D-0902 - Issue 2";}
else{$leD="D-0901 - Issue 2";}

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
				<td width='300px' height='40px' style='font:bold 14px;border:1px black solid;background-color:#e7e6e6;' align='center'>";
if($_GET['Langue']=="FR"){
					$formulaire.="<img src='../../Images/FlecheGauche.png' width='15px' border='0' />SURVEILLANCE OPERATIONNELLE<img width='15px' src='../../Images/FlecheDroite.png' border='0' />";
}
else{$formulaire.="<img src='../../Images/FlecheGauche.png' width='15px' border='0' />OPERATIONAL SURVEILLANCE<img width='15px' src='../../Images/FlecheDroite.png' border='0' />";}
				$formulaire.="</td>
				<td width='160px' rowspan='2'  style='border:1px solid black' align='center'>&nbsp;</td>
			</tr>
			<tr>
				<td width='80%' height='20px' style='font:bold 14px;border:1px black solid;color:#0527ff;' align='center'>N° </td>
			</tr>
			<tr>
				<td width='80%' height='10px' style='font:bold 14px;border:1px black solid;color:#0527ff;' align='center'>".$LigneSurveillance['Questionnaire']."</td>
				<td width='10%' style='border:1px solid black' align='center'>&nbsp;</td>
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
					03/03/2021
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
		<td width='25%' height='10px' style='font:bold 10px;border:1px black solid;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="SURVEILLANCE EFFECTUEE LE :";}
else{$formulaire.="MONITORING DONE ON :";}
$formulaire.="</td>
		<td width='25%' style='font:10px;border:1px solid black' align='center'>&nbsp;</td>
		<td width='5%' height='10px' style='font:bold 10px;border:1px black solid;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="PAR :";}
else{$formulaire.="BY :";}
$formulaire.="</td>
		<td width='45%' style='font:10px;border:1px solid black' align='center'>&nbsp;</td>
	</tr>
	<tr>
		<td width='25%' height='10px' style='font:bold 10px;border:1px black solid;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="COLLABORATEUR SURVEILLE :";}
else{$formulaire.="MONITORED COLLABORATOR :";}
$formulaire.="</td>
		<td width='25%' style='font:10px;border:1px solid black' align='center'>&nbsp;</td>
		<td></td>
		<td></td>
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='50%' height='10px' rowspan='2' colspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Question posée";}
else{$formulaire.="Question asked";}
$formulaire.="</td>
		<td width='15%' height='10px' colspan='3' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Constat";}
else{$formulaire.="Statement";}
$formulaire.="</td>
		<td width='35%' height='10px' rowspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Description de la NC / Preuves - Actions";}
else{$formulaire.="NC Description / Evidences - Actions";}
$formulaire.="</td>
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
$reqQuestion = "
	SELECT
		new_surveillances_question.ID,
		new_surveillances_question.Numero,
		new_surveillances_question.Question,
		new_surveillances_question.Question_EN,
		new_surveillances_question.Reponse,
		new_surveillances_question.Reponse_EN,
		new_surveillances_question.Modifiable
	FROM
		new_surveillances_question
	LEFT JOIN new_surveillances_questionnaire
		ON new_surveillances_questionnaire.ID = new_surveillances_question.ID_Questionnaire
	WHERE
		new_surveillances_questionnaire.ID =".$LigneSurveillance['ID_Questionnaire']."
		AND new_surveillances_question.Supprime =0
	ORDER BY
		new_surveillances_question.Numero ;";
$resultQuestion=mysqli_query($bdd,$reqQuestion);
$nbQuestion=mysqli_num_rows($resultQuestion);

$total = 0;
$C = 0;
if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		if($_GET['Langue']=="FR"){
			$laQuestion.= stripslashes($rowQuestion['Question'])."<br><span style='color:#3548b9'>".stripslashes($rowQuestion['Reponse'])."</span>";
		}
		else{
			$laQuestion.= stripslashes($rowQuestion['Question_EN'])."<br><span style='color:#3548b9'>".stripslashes($rowQuestion['Reponse_EN'])."</span>";
		}

		$formulaire.="
		<tr>
			<td width='10px' height='10px'style='font:8px;border-left:1px black solid;border-top:1px black solid;border-bottom:1px black solid;' align='center'>".$rowQuestion['Numero']."</td>
			<td width='45%' height='10px'style='font:8px;border-right:1px black solid;border-top:1px black solid;border-bottom:1px black solid;;'>".$laQuestion."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'></td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid' align='center'></td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'></td>
			<td width='35%' height='10px' style='font:8px;border:1px black solid;'  align='center'></td>
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

$formulaire.="</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='50%' colspan='3' height='10px' style='font:bold 10px;' align='right'>";
if($_GET['Langue']=="FR"){$formulaire.="Taux de bonne réponses :";}
else{$formulaire.="Ratio of correct answers :";}
$formulaire.="</td>
		<td width='10%' style='font:bold 10px;border-top:1px solid black;border-bottom:1px solid black;' align='center'></td>
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
		<td width='50%' colspan='3' height='10px' style='font:10px;' align='right'>";
if($_GET['Langue']=="FR"){$formulaire.="Taux de bonne réponses requis :";}
else{$formulaire.="Requested ratio of correct answers :";}
$formulaire.="</td>
		<td style='font:10px;'>80 %</td>
		<td></td>
		<td></td>
		<td style='font:10px;' align='left'>NA : Non Applicable</td>
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='25%' height='10px' style='font:bold 10px;border:1px solid black;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Résultat";}
else{$formulaire.="Result";}
$formulaire.="</td>
		<td width='30%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'></td>
		<td width='45%'></td>
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='50%' height='10px' style='font:bold 10px;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Action(s) supplémentaire(s) :<br> à traçer sur Action Tracker";}
else{$formulaire.="Additional Action(s) :<br> to be tracked in Action Tracker";}
$formulaire.="</td>
		<td width='10%' style='font:bold 10px;border:1px solid black;' align='center'></td>
";

$formulaire.="<td width='10%' height='10px' style='font:bold 10px;' align='right'>N° :</td>
	<td width='10%' style='font:bold 10px;border:1px solid black;' align='center'></td>
	<td width='20%'></td>
	";

$formulaire.="
	</tr>
</table>
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='20%'></td>
		<td width='30%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Signature Surveillant";}
else{$formulaire.="Supervisor";}
$formulaire.="</td>
		<td width='5%'></td>
		<td width='30%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>";
if($_GET['Langue']=="FR"){$formulaire.="Signature Surveillé";}
else{$formulaire.="Supervised";}
$formulaire.="</td>
		<td width='15%'></td>
	</tr>
	<tr>
		<td width='20%'></td>
		<td width='30%' height='50px' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>&nbsp;</td>
		<td width='5%'></td>
		<td width='30%' height='50px' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>&nbsp;</td>
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
$dompdf->stream(str_replace("/"," ",str_replace("'","",$LigneSurveillance['Questionnaire'])));
?>