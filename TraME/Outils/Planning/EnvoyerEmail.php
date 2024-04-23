<!DOCTYPE html>
<?php
session_start();
require("../ConnexioniSansBody.php");
require("../Fonctions.php");

Ecrire_Code_JS_Init_Date();

//Envoyer mail si refus
$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
$headers.='Content-Type: text/html; charset="utf-8"'."\n";

$destinataire="";
$req="SELECT ";
$req.="(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=trame_acces.Id_Personne) AS EmailPro ";
$req.="FROM trame_acces WHERE SUBSTRING(Droit,2,1)=1 AND Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
$resulEmail=mysqli_query($bdd,$req);
$nbEmail=mysqli_num_rows($resulEmail);
if ($nbEmail>0){
	while($row=mysqli_fetch_array($resulEmail)){
		if($row['EmailPro']<>""){
			$destinataire.=$row['EmailPro'].",";
		}
	}
}

if($destinataire<>""){
	$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneTR'];
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	$Nom="";
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$Nom=$row['Nom']." ".$row['Prenom'];
	}
	if($_SESSION['Langue']=="EN"){$object="TraME - Schedule ".$_GET['Date']." is completed (".$Nom.")";}
	else{$object="TraME - Planning du ".$_GET['Date']." terminé (".$Nom.")";}

	$message="<html>";
	$message.="<head>";
		$message.="<title>Planning</title>";
	$message.="</head>";
	$message.="<body>";
	$message.="<table width='100%'>";
	if($_SESSION['Langue']=="EN"){
			$message.="<tr><td>".$Nom."'s schedule is completed for the following dates : ".htmlentities($_GET['Date'])." </td></tr>";
	}
	else{
		$message.="<tr><td>Le planning de ".$Nom." est terminé pour les dates suivantes : ".htmlentities($_GET['Date'])." </td></tr>";
	}
	$message.="</table></td></tr>";
	$message.="</table></body></html>";
	if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
		echo "<script>window.close();</script>";
	}
	else{
		if($_SESSION['Langue']=="EN"){
			echo"<script language=\"javascript\">alert('".addslashes("The mail was not sent")."')</script>";
		}
		else{
			echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";
		}
	}	
}
else{
	if($_SESSION['Langue']=="EN"){
		echo"<script language=\"javascript\">alert('".addslashes("The mail was not sent")."')</script>";
	}
	else{
		echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";
	}
}