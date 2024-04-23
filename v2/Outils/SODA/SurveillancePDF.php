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

$formulaire="";

$requeteSurveillance = "SELECT Id AS ID, 
		(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS ID_Theme,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
		Id_Questionnaire AS ID_Questionnaire,
		(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
		IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		Id_Prestation,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,
		(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SeuilReussite, 
		(SELECT NonAleatoire FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS NonAleatoire, 
		DateSurveillance,
		NumActionTracker,
		SignatureSurveillant,
		SignatureSurveille,
		Commentaire
		FROM soda_surveillance 
		WHERE Id=".$_GET['Id']."
		AND Suppr=0
		";
$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

$presta=substr($LigneSurveillance['Prestation'],0,strpos($LigneSurveillance['Prestation']," "));
if($presta==""){$presta=$LigneSurveillance['Prestation'];}

if($LigneSurveillance['ID_Theme']==7){
	$leD="D-0902 - Issue 2";
	$TitreFR="SURVEILLANCE PROCÉDÉS";
	$TitreEN="PROCESS MONITORING";
}
elseif($LigneSurveillance['ID_Theme']==8){
	$leD="D-0919 - Issue 1";
	$TitreFR="SURVEILLANCE PROCESSUS";
	$TitreEN="PROCESSUS MONITORING";
}
else{
	$leD="D-0901 - Issue 2";
	$TitreFR="SURVEILLANCE OPERATIONNELLE";
	$TitreEN="OPERATIONAL SURVEILLANCE";
}



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
				<td width='160px' rowspan='3'  style='border:1px solid black' align='center'><img width='100px' src='../../Images/Logos/Logo Daher_posi.png' border='0' /></td>
				<td width='300px' height='40px' style='font:bold 14px;border:1px black solid;background-color:#e7e6e6;' align='center'>
					<img src='../../Images/FlecheGauche.png' width='15px' border='0' />".$TitreFR." /<br> <span style='color:#0527ff;'>".$TitreEN."</span><img width='15px' src='../../Images/FlecheDroite.png' border='0' />
				</td>
				<td width='160px' rowspan='2'  style='border:1px solid black' align='center'>".$LigneSurveillance['Plateforme']."</td>
			</tr>
			<tr>
				<td width='80%' height='20px' style='font:bold 14px;border:1px black solid;color:#0527ff;' align='center'>N° ".$LigneSurveillance['ID']."</td>
			</tr>
			<tr>
				<td width='80%' height='10px' style='font:bold 14px;border:1px black solid;color:#0527ff;' align='center'>".$LigneSurveillance['Questionnaire']."</td>
				<td width='10%' style='border:1px solid black' align='center'>".$presta."</td>
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
					25/10/2022
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
		<td width='50%' height='10px' rowspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Question posée<br> <span style='color:#0527ff;'>Question asked</span></td>
		<td width='15%' height='10px' colspan='3' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Constat <span style='color:#0527ff;'> /Statement</span></td>
		<td width='5%' height='10px' rowspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Note<br> <span style='color:#0527ff;'>Score</span></td>
		<td width='25%' height='10px' rowspan='2' style='font:bold 8px;border:1px black solid;background-color:#c1c1c1;' align='center'>Description NC / preuve / proposition d'actions long-terme (si nécessaire)<br> <span style='color:#0527ff;'>NC description / Evidence / proposed long-terme Actions (if necessary)</span></td>
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

$total = 0;
$C = 0;

$reqQuestionSurveillance = "
SELECT
	soda_surveillance_question.Id AS ID,
	soda_question.Question,
	soda_question.Question_EN,
	soda_question.Reponse,
	soda_question.Reponse_EN,
	soda_question.ImageQuestion,
	soda_surveillance_question.Ponderation,
	soda_surveillance_question.Etat,
	soda_surveillance_question.Commentaire,
	soda_surveillance_question.Action
FROM
	soda_surveillance_question
LEFT JOIN soda_question
	ON soda_surveillance_question.Id_Question = soda_question.Id
WHERE
	soda_surveillance_question.Id_Surveillance =".$LigneSurveillance['ID']." 
AND soda_surveillance_question.Id_Question>0 ";
if($LigneSurveillance['NonAleatoire']==1){
	$reqQuestionSurveillance .= "ORDER BY soda_question.Ordre ";
}
$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
$nbQuestion=mysqli_num_rows($resultQuestion);

if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		$laQuestion.= "FR : ".$rowQuestion['Question']."<br>EN : ".$rowQuestion['Question_EN']."<br><br>";
		$laQuestion.= "FR : ".$rowQuestion['Reponse']."<br>EN : ".$rowQuestion['Reponse_EN'];
		
		if($rowQuestion['ImageQuestion']<>""){
			$laQuestion.="<br><img src='ImageQCM/".$rowQuestion['ImageQuestion']."' width='300px' border='0'>";
		}
		$checkC = "&nbsp;";
		$checkNC = "&nbsp;";
		$checkNA = "&nbsp;";
		$observation ="";
		$action = "";
		$cloture = "";
		$score="";
		if ($rowQuestion['Etat'] == "NC")
		{
			$checkNC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$score="0/".$rowQuestion['Ponderation'];
		}
		elseif ($rowQuestion['Etat'] == "C")
		{
			$score=$rowQuestion['Ponderation']."/".$rowQuestion['Ponderation'];
			$checkC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$C=$C+$rowQuestion['Ponderation'];
		}
		elseif($rowQuestion['Etat'] == "NA"){
			$checkNA = "X";
		}
		$observation = $rowQuestion['Commentaire'];
		$action = $rowQuestion['Action'];

		if($observation<>"" && $action<>""){
			$observation.="<br>".$action;
		}
		elseif($observation=="" && $action<>""){
			$observation.=$action;
		}
		
		$formulaire.="
		<tr>
			<td width='55%' height='10px' style='font:8px;border:1px black solid;'>".$laQuestion."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$checkC."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid' align='center'>".$checkNC."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$checkNA."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$score."</td>
			<td width='35%' height='10px' style='font:8px;border:1px black solid;'  align='center'>".stripslashes($observation)."</td>
		</tr>";
	}
}
$reqQuestionSurveillance = "
SELECT
	soda_surveillance_question.Id AS ID,
	soda_surveillance_question.QuestionAdditionnelle,
	soda_surveillance_question.ReponseAdditionnelle,
	soda_surveillance_question.Ponderation,
	soda_surveillance_question.Etat,
	soda_surveillance_question.Commentaire,
	soda_surveillance_question.Action
FROM
	soda_surveillance_question
WHERE
	soda_surveillance_question.Id_Surveillance =".$LigneSurveillance['ID']." 
AND soda_surveillance_question.Id_Question=0 ";
$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
$nbQuestion=mysqli_num_rows($resultQuestion);

if($nbQuestion > 0)
{
	while($rowQuestion=mysqli_fetch_array($resultQuestion))
	{
		$laQuestion="";
		$laQuestion.= $rowQuestion['QuestionAdditionnelle']."<br><br>";
		$laQuestion.= $rowQuestion['ReponseAdditionnelle'];
		
		$checkC = "&nbsp;";
		$checkNC = "&nbsp;";
		$checkNA = "&nbsp;";
		$observation ="";
		$action = "";
		$cloture = "";
		$score=0;
		
		if ($rowQuestion['Etat'] == "NC")
		{
			$checkNC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$score="0/".$rowQuestion['Ponderation'];
		}
		elseif ($rowQuestion['Etat'] == "C")
		{
			$score=$rowQuestion['Ponderation']."/".$rowQuestion['Ponderation'];
			$checkC = "X";
			$total=$total+$rowQuestion['Ponderation'];
			$C=$C+$rowQuestion['Ponderation'];
		}
		elseif($rowQuestion['Etat'] == "NA"){
			$checkNA = "X";
		}
		$observation = $rowQuestion['Commentaire'];
		$action = $rowQuestion['Action'];

		if($observation<>"" && $action<>""){
			$observation.="<br>".$action;
		}
		elseif($observation=="" && $action<>""){
			$observation.=$action;
		}
		
		$formulaire.="
		<tr>
			<td width='55%' height='10px' style='font:8px;border:1px black solid;'>".$laQuestion."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$checkC."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid' align='center'>".$checkNC."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$checkNA."</td>
			<td width='5%' height='10px' style='font:8px;border:1px black solid;' align='center'>".$score."</td>
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
if($note>=$LigneSurveillance['SeuilReussite']){$couleur="#197b16";$valeur="Atteint / Reached";}

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
		<td style='font:10px;'>".$LigneSurveillance['SeuilReussite']." %</td>
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
<br>
<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
	<tr>
		<td width='25%' height='10px' style='font:bold 10px;border:1px solid black;' align='center'>Commentaire /<br><span style='color:#0527ff;'>Comment</span></td>
		<td width='70%' style='font:bold 10px;border-top:1px solid black;border:1px solid black;' align='center'>".$LigneSurveillance['Commentaire']."</td>
		<td width='5%'></td>
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