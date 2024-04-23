<!DOCTYPE html>
<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");

$dateReporting = $_GET['du'];
$leJour=TrsfDate_($dateReporting);
$tabDate = explode('-', $leJour);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);		
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+4-$NumJour, $tabDate[0]);
$leJeudi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);	
Ecrire_Code_JS_Init_Date();

$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
$headers .= "MIME-version: 1.0\n";
$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$destinataire="";
$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneSP'];
$resulEmail=mysqli_query($bdd,$req);
$nbEmail=mysqli_num_rows($resulEmail);
if ($nbEmail>0){
	$row=mysqli_fetch_array($resulEmail);
	$destinataire=$row['EmailPro'];
}

$object = "Reporting Journalier - Plannification du ".$dateReporting;
$message="<table width=\"100%\">";
$message.="<tr><td>Bonjour,</td></tr>";

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation, ";
$req.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite,sp_ficheintervention.Vacation, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE ";
if($NumJour<=4){
	$req.="((sp_ficheintervention.DateIntervention='".$leLendemain."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJour."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Nuit'))) ";
}
$req.="ORDER BY sp_ficheintervention.DateIntervention DESC,sp_ficheintervention.Vacation ASC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$messageJ="<tr><td>JOUR :</td></tr>";
$messageJ.="<tr><td><table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$messageJ.="<tr>";
$messageJ.="<td align=\"center\">MSN</td>";
$messageJ.="<td align=\"center\">OF</td>";
$messageJ.="<td align=\"center\">Titre</td>";
$messageJ.="<td align=\"center\">Zone Aircraft</td>";
$messageJ.="<td align=\"center\">Priorité</td>";
$messageJ.="<td align=\"center\">Statut</td>";
$messageJ.="<td align=\"center\">Retour</td>";
$messageJ.="<td align=\"center\">Date</td>";
$messageJ.="<td align=\"center\">Vacation</td>";
$messageJ.="</tr>";

$messageS="<tr><td>SOIR :</td></tr>";
$messageS.="<tr><td><table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$messageS.="<tr>";
$messageS.="<td align=\"center\">MSN</td>";
$messageS.="<td align=\"center\">OF</td>";
$messageS.="<td align=\"center\">Titre</td>";
$messageS.="<td align=\"center\">Zone Aircraft</td>";
$messageS.="<td align=\"center\">Priorité</td>";
$messageS.="<td align=\"center\">Statut</td>";
$messageS.="<td align=\"center\">Retour</td>";
$messageS.="<td align=\"center\">Date</td>";
$messageS.="<td align=\"center\">Vacation</td>";
$messageS.="</tr>";

$messageN="<tr><td>GRANDE NUIT :</td></tr>";
$messageN.="<tr><td><table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$messageN.="<tr>";
$messageN.="<td align=\"center\">MSN</td>";
$messageN.="<td align=\"center\">OF</td>";
$messageN.="<td align=\"center\">Titre</td>";
$messageN.="<td align=\"center\">Zone Aircraft</td>";
$messageN.="<td align=\"center\">Priorité</td>";
$messageN.="<td align=\"center\">Statut</td>";
$messageN.="<td align=\"center\">Retour</td>";
$messageN.="<td align=\"center\">Date</td>";
$messageN.="<td align=\"center\">Vacation</td>";
$messageN.="</tr>";

$messageVSD="<tr><td>VSD :</td></tr>";
$messageVSD.="<tr><td><table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$messageVSD.="<tr>";
$messageVSD.="<td align=\"center\">MSN</td>";
$messageVSD.="<td align=\"center\">OF</td>";
$messageVSD.="<td align=\"center\">Titre</td>";
$messageVSD.="<td align=\"center\">Zone Aircraft</td>";
$messageVSD.="<td align=\"center\">Priorité</td>";
$messageVSD.="<td align=\"center\">Statut</td>";
$messageVSD.="<td align=\"center\">Retour</td>";
$messageVSD.="<td align=\"center\">Date</td>";
$messageVSD.="<td align=\"center\">Vacation</td>";
$messageVSD.="</tr>";

if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>""){
			$statut=$row['Id_StatutQUALITE'];
			$retour=$row['RetourQUALITE'];
		}
		else{
			$statut=$row['Id_StatutPROD'];
			$retour=$row['RetourPROD'];
		}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		if($vacation=="Jour"){
			$messageJ.="<tr>";
			$messageJ.="<td width=\"3%\" align=\"center\">".$row['MSN']."</td>";
			$messageJ.="<td width=\"6%\" align=\"center\" >".$row['Reference']."</td>";
			$messageJ.="<td width=\"9%\" align=\"center\" >".$row['Titre']."</td>";
			$messageJ.="<td width=\"6%\" align=\"center\" >".$row['Zone']."</td>";
			$messageJ.="<td width=\"5%\" align=\"center\" >".$Priorite."</td>";
			$messageJ.="<td width=\"5%\" align=\"center\" >".$statut."</td>";
			$messageJ.="<td width=\"6%\" align=\"center\" >".$retour."</td>";
			$messageJ.="<td width=\"6%\" align=\"center\" >".$row['DateIntervention']."</td>";
			$messageJ.="<td width=\"6%\" align=\"center\" >".$vacation."</td>";
			$messageJ.="</tr>";
		}
		elseif($vacation=="Soir"){
			$messageS.="<tr>";
			$messageS.="<td width=\"3%\" align=\"center\" >".$row['MSN']."</td>";
			$messageS.="<td width=\"6%\" align=\"center\" >".$row['Reference']."</td>";
			$messageS.="<td width=\"9%\" align=\"center\" >".$row['Titre']."</td>";
			$messageS.="<td width=\"6%\" align=\"center\" >".$row['Zone']."</td>";
			$messageS.="<td width=\"5%\" align=\"center\" >".$Priorite."</td>";
			$messageS.="<td width=\"5%\" align=\"center\" >".$statut."</td>";
			$messageS.="<td width=\"6%\" align=\"center\" >".$retour."</td>";
			$messageS.="<td width=\"6%\" align=\"center\" >".$row['DateIntervention']."</td>";
			$messageS.="<td width=\"6%\" align=\"center\" >".$vacation."</td>";
			$messageS.="</tr>";
		}
		elseif($vacation=="Nuit"){
			$messageN.="<tr>";
			$messageN.="<td width=\"3%\" align=\"center\" >".$row['MSN']."</td>";
			$messageN.="<td width=\"6%\" align=\"center\" >".$row['Reference']."</td>";
			$messageN.="<td width=\"9%\" align=\"center\" >".$row['Titre']."</td>";
			$messageN.="<td width=\"6%\" align=\"center\" >".$row['Zone']."</td>";
			$messageN.="<td width=\"5%\" align=\"center\" >".$Priorite."</td>";
			$messageN.="<td width=\"5%\" align=\"center\" >".$statut."</td>";
			$messageN.="<td width=\"6%\" align=\"center\" >".$retour."</td>";
			$messageN.="<td width=\"6%\" align=\"center\" >".$row['DateIntervention']."</td>";
			$messageN.="<td width=\"6%\" align=\"center\" >".$vacation."</td>";
			$messageN.="</tr>";
		}
		elseif($vacation=="VSD"){
			$messageVSD.="<tr>";
			$messageVSD.="<td width=\"3%\" align=\"center\" >".$row['MSN']."</td>";
			$messageVSD.="<td width=\"6%\" align=\"center\" >".$row['Reference']."</td>";
			$messageVSD.="<td width=\"9%\" align=\"center\" >".$row['Titre']."</td>";
			$messageVSD.="<td width=\"6%\" align=\"center\" >".$row['Zone']."</td>";
			$messageVSD.="<td width=\"5%\" align=\"center\" >".$Priorite."</td>";
			$messageVSD.="<td width=\"5%\" align=\"center\" >".$statut."</td>";
			$messageVSD.="<td width=\"6%\" align=\"center\" >".$retour."</td>";
			$messageVSD.="<td width=\"6%\" align=\"center\" >".$row['DateIntervention']."</td>";
			$messageVSD.="<td width=\"6%\" align=\"center\" >".$vacation."</td>";
			$messageVSD.="</tr>";
		}
	}
}
$messageJ.="</table></td></tr>";
$messageS.="</table></td></tr>";
$messageN.="</table></td></tr>";
$messageVSD.="</table></td></tr>";
$message.=$messageJ.$messageS.$messageN.$messageVSD."</table>";
if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
	echo "<script>window.close();</script>";
}
else{echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";}	
 ?>