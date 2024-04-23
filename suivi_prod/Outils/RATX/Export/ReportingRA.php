<!DOCTYPE html>
<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");

$msn = $_GET['msn'];
$dateReporting = $_GET['date'];
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

$object = "Reporting RA - du ".$dateReporting." MSN ".$msn;
$message="<table width=\"100%\">";
$message.="<tr><td>Bonjour,</td></tr>";
$message.="<tr><td>1. Bilan MSN :</td></tr>";
$message.="</table>";
$message.="<table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$message.="<tr><td >MSN ".$msn."</td><td align=\"center\">RTD du jour</td><td align=\"center\">OTD de la semaine</td><td align=\"center\">IN de la veille</td><td align=\"center\">OUT de la veille</td><td align=\"center\">Nbr points PROD</td><td align=\"center\">Nbr points QLS</td></tr> \n";
$message.="<tr><td >FGTR</td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td></tr> \n";
$message.="<tr><td >Système</td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td></tr> \n";
$message.="<tr><td >Structure</td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td><td align=\"center\"></td></tr> \n";
$message.="</table>";
$message.="<table width=\"100%\">";
$message.="<tr><td>2. Points chauds :</td></tr>";
$message.="<tr><td>3. Plannif de la journée :</td></tr>";
$message.="</table>";
$message.="<table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$message.="<tr>";
$message.="<td align=\"center\">MSN</td>";
$message.="<td align=\"center\">OF</td>";
$message.="<td align=\"center\">Titre</td>";
$message.="<td align=\"center\">Zone Aircraft</td>";
$message.="<td align=\"center\">Priorité</td>";
$message.="<td align=\"center\">Statut</td>";
$message.="<td align=\"center\">Retour</td>";
$message.="<td align=\"center\">Commentaire</td>";
$message.="<td align=\"center\">Date</td>";
$message.="<td align=\"center\">Vacation</td>";
$message.="</tr>";
$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation, ";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite,sp_ficheintervention.Vacation,sp_ficheintervention.Commentaire, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_dossier.MSN=".$msn." AND ";
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
		
		$couleur="#ffffff";
		if($statut=="CERT"){$couleur="#00b050";}
		elseif($statut=="QARJ" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TVS"){$couleur="#ffc000";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		$message.="<tr>";
		$message.="<td width=\"3%\" align=\"center\" >".$row['MSN']."</td>";
		$message.="<td width=\"6%\" align=\"center\" >".$row['Reference']."</td>";
		$message.="<td width=\"9%\" align=\"center\" >".$row['Titre']."</td>";
		$message.="<td width=\"6%\" align=\"center\" >".$row['Zone']."</td>";
		$message.="<td width=\"5%\" align=\"center\">".$Priorite."</td>";
		$message.="<td width=\"5%\" align=\"center\" >".$statut."</td>";
		$message.="<td width=\"6%\" align=\"center\" >".$retour."</td>";
		$message.="<td width=\"6%\" align=\"left\" >".$row['Commentaire']."</td>";
		$message.="<td width=\"6%\" align=\"center\" >".$row['DateIntervention']."</td>";
		$message.="<td width=\"6%\" align=\"center\" >".$vacation."</td>";
		$message.="</tr>";
	}
}
$message.="</table>";
$message.="<table width=\"100%\">";
$message.="<tr><td>4. Compte rendu de la veille :</td></tr>";
$message.="</table>";
$message.="<table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$message.="<tr>";
$message.="<td align=\"center\">MSN</td>";
$message.="<td align=\"center\">OF</td>";
$message.="<td align=\"center\">Titre</td>";
$message.="<td align=\"center\">Zone Aircraft</td>";
$message.="<td align=\"center\">Priorité</td>";
$message.="<td align=\"center\">Statut PROD</td>";
$message.="<td align=\"center\">RETP</td>";
$message.="<td align=\"center\">Commentaire PROD</td>";
$message.="<td align=\"center\">Statut QUALITE</td>";
$message.="<td align=\"center\">RETQ</td>";
$message.="<td align=\"center\">Commentaire QUALITE</td>";
$message.="<td align=\"center\">Date</td>";
$message.="<td align=\"center\">Vacation</td>";
$message.="</tr>";

$req="SELECT sp_ficheintervention.Id, sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation,  ";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite, sp_ficheintervention.CommentairePROD, sp_ficheintervention.CommentaireQUALITE, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_ficheintervention.TravailRealise,sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_dossier.MSN=".$msn." AND ";
if($NumJour>=2 && $NumJour<=4){
	$req.="((sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$laVeille."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
elseif($NumJour==1){
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-3, $tabDate[0]);
	$leVendredi= date("Y-m-d", $timestamp);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-2, $tabDate[0]);
	$leSamedi= date("Y-m-d", $timestamp);	
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
	$leDimanche= date("Y-m-d", $timestamp);	
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit')) OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND (sp_ficheintervention.Vacation='VSD Jour' OR sp_ficheintervention.Vacation='VSD Nuit'))) ";
}
else{
	$req.="((sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='N') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leJeudi."' AND (sp_ficheintervention.Vacation='J' OR sp_ficheintervention.Vacation='S'))) ";
}
$req.="ORDER BY sp_ficheintervention.DateIntervention DESC, sp_ficheintervention.Vacation ASC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		if($row['Id_StatutQUALITE']<>""){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}
		
		$couleur="#ffffff";
		if($statut=="CERT"){$couleur="#00b050";}
		elseif($statut=="QARJ" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TVS"){$couleur="#ffc000";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		$message.="<tr>";
		$message.="<td width=\"3%\" align=\"center\" > ".$row['MSN']. "</td>";
		$message.="<td width=\"6%\" align=\"center\" > ".$row['Reference']." </td>";
		$message.="<td width=\"9%\" align=\"center\" > ".$row['Titre']." </td>";
		$message.="<td width=\"6%\" align=\"center\" > ".$row['Zone']." </td>";
		$message.="<td width=\"5%\" align=\"center\"> ".$Priorite." </td>";
		$message.="<td width=\"5%\" align=\"center\" > ".$row['Id_StatutPROD']." </td>";
		$message.="<td width=\"6%\" align=\"center\" > ".$row['RetourPROD']." </td>";
		$message.="<td width=\"10%\" align=\"center\" > ".$row['CommentairePROD']." </td>";
		$message.="<td width=\"5%\" align=\"center\" > ".$row['Id_StatutQUALITE']." </td>";
		$message.="<td width=\"6%\" align=\"center\" > ".$row['RetourQUALITE']." </td>";
		$message.="<td width=\"10%\" align=\"center\" > ".$row['CommentaireQUALITE']." </td>";
		$message.="<td width=\"6%\" align=\"center\" > ".$row['DateIntervention']." </td>";
		$message.="<td width=\"6%\" align=\"center\" > ".$vacation." </td>";
		$message.="</tr>";
	}
}
$message.="</table>";

if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
	echo "<script>window.close();</script>";
}
else{echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";}	
 ?>