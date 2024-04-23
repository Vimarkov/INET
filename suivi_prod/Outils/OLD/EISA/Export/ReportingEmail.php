<!DOCTYPE html>
<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");

$dateReporting = $_GET['date'];
$leJour=TrsfDate_($dateReporting);
	
Ecrire_Code_JS_Init_Date();

$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$destinataire="";
$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneSP'];
$resulEmail=mysqli_query($bdd,$req);
$nbEmail=mysqli_num_rows($resulEmail);
if ($nbEmail>0){
	$row=mysqli_fetch_array($resulEmail);
	$destinataire=$row['EmailPro'];
}

$object = "Reporting du ".$dateReporting;
$req="SELECT Id,MSN,OrdreMontage,Designation,Id_StatutPROD,Id_StatutQUALITE,DatePROD,DateQUALITE,Commentaire,";
$req.="(SELECT sp_atrmoteur.PosteMontage FROM sp_atrmoteur WHERE sp_atrmoteur.MSN=sp_atrot.MSN LIMIT 1) AS PosteMontage, ";
$req.="(SELECT sp_atrarticle.TypeMoteur FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS TypeMoteur, ";
$req.="(SELECT sp_atrarticle.MoteurSharklet FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS MoteurSharklet, ";
$req.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardPROD) AS CauseP, ";
$req.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardQUALITE) AS CauseQ ";
$req.="FROM sp_atrot ";
$req.="WHERE sp_atrot.Id_Prestation=463 AND sp_atrot.Supprime=0 AND (DatePROD='".$leJour."' OR DateQUALITE='".$leJour."') ";
$req.="ORDER BY MSN ASC, OrdreMontage ASC ";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$messageSuite="";
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$AMcree="";
		
		$req="SELECT NumAMNC FROM sp_atram WHERE NumOF='".$row['OrdreMontage']."' AND Id_Prestation=463";
		$resultAM=mysqli_query($bdd,$req);
		$nbResultaAM=mysqli_num_rows($resultAM);
		if($nbResultaAM>0){
			while($rowAM=mysqli_fetch_array($resultAM)){
				$AMcree.=$rowAM['NumAMNC']." <br>";
			}
		}
		$messageSuite.="<tr>";
		$messageSuite.="<td> ".$row['MSN']." </td>";
		$messageSuite.="<td> ".$row['OrdreMontage']." </td>";
		$messageSuite.="<td> ".$row['Designation']." </td>";
		$messageSuite.="<td> ".$row['Id_StatutPROD']." </td>";
		$messageSuite.="<td> ".$row['Id_StatutQUALITE']." </td>";
		$messageSuite.="<td> ".$row['CauseQ']." </td>";
		$messageSuite.="<td> ".$AMcree." </td>";
		$messageSuite.="</tr>";
	}
}

$message="<html>";
$message.="<head>";
$message.="<title>Reporting</title><meta name="robots" content="noindex">";
$message.="</head>";
$message.="<body>";
$message.="<table width='100%'>";
$message.="<tr><td>Bonjour,</td></tr>";

$message.="<tr><td><table border='2' width='100%' cellpadding='0' cellspacing='0' align='left' style='font-size:12px;'>";
$message.="<tr>";
$message.="<td align='center'>MSN</td>";
$message.="<td align='center'>N° OF</td>";
$message.="<td align='center'>Désignation</td>";
$message.="<td align='center'>Statut PROD</td>";
$message.="<td align='center'>Statut QUALITE</td>";
$message.="<td align='center'>Cause retard QUALITE</td>";
$message.="<td align='center'>N° AM créée</td>";
$message.="</tr>";
$message.=$messageSuite;
$message.="</table></td></tr>";
$message.="</table></body></html>";

if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
	echo "<script>window.close();</script>";
}
else{echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";}	

echo "<script>window.close();</script>";
 ?>