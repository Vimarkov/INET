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

$object = "Reporting Journalier - Compte rendu de la veille du ".$dateReporting;
$message="<table width=\"100%\">";
$message.="<tr><td>Bonjour,</td></tr>";
$message.="<tr><td>RETOURS PROD :</td></tr>";
$message.="<tr><td><table border=\"2\" width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";

$req="SELECT DISTINCT Id_RetourPROD, (SELECT Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD, ";
$req.="(SELECT EstRetour FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS EstRetour ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE Id_RetourPROD<>0 AND sp_ficheintervention.Vacation<>'' AND (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') AND ";
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
$req.=" ORDER BY RetourPROD ";
$resultRetourProd=mysqli_query($bdd,$req);
$nbRETP=mysqli_num_rows($resultRetourProd);

$req="SELECT DISTINCT Id_Dossier ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') AND ";
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
$resultGamme=mysqli_query($bdd,$req);
$nbGamme=mysqli_num_rows($resultGamme);

$req="SELECT Id_StatutPROD, Id_RetourPROD, Id_StatutQUALITE, ";
$req.="(SELECT EstRetour FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS EstRetour ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE Id_StatutPROD='TFS' AND ";
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

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

if ($nbRETP>0){
	mysqli_data_seek($resultRetourProd,0);
	while($rowRETP=mysqli_fetch_array($resultRetourProd)){
			$message.="<tr><td width=\"70%\">".$rowRETP['RetourPROD']."</td>";
			$nb=0;
			if ($nbResulta>0){
				mysqli_data_seek($result,0);
				while($row=mysqli_fetch_array($result)){
					if($row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){$nb++;}
				}
			}
			$message.="<td align=\"center\">".$nb."</td>";
			$message.="</tr>";
	}
}
$message.="</table></td></tr>";

//Nb retours
$message.="<tr><td><table border=\"2\" width=\"60%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$message.="<tr><td width=\"70%\">Nombre de retours</td>";
$nb=0;
if ($nbResulta>0){
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		if($row['EstRetour']==1){$nb++;}
	}
}
$message.="<td align='center'>".$nb."</td>";
$message.="<tr><td width=\"70%\">Nombre de gammes</td>";
$nb=0;
if ($nbGamme>0){
	mysqli_data_seek($resultGamme,0);
	while($rowGamme=mysqli_fetch_array($resultGamme)){
		$nb++;
	}
}
$message.="<td align='center'>".$nb."</td></tr>";

$message.="<tr><td width=\"70%\">Taux de retours</td>";
$nbRetour=0;
if ($nbResulta>0){
	mysqli_data_seek($result,0);
	while($row=mysqli_fetch_array($result)){
		if($row['EstRetour']==1){$nbRetour++;}
	}
}
$nbGa=0;
if ($nbGamme>0){
	mysqli_data_seek($resultGamme,0);
	while($rowGamme=mysqli_fetch_array($resultGamme)){
		$nbGa++;
	}
}
$Taux=0;
if($nbGa>0){$Taux=round(($nbRetour/$nbGa)*100,0);}
$message.="<td align='center'>".$Taux."%</td>";
$message.="</tr>";

$message.="</table></td></tr>";

$req="SELECT sp_ficheintervention.Id, sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_ficheintervention.DateIntervention,sp_ficheintervention.Vacation,  ";
$req.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, ";
$req.="sp_dossier.Priorite, sp_ficheintervention.CommentairePROD, sp_ficheintervention.CommentaireQUALITE, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_ficheintervention.TravailRealise,sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE ";
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

$messageJ="<tr><td>JOUR :</td></tr>";
$messageJ.="<tr><td><table border=\"2\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
$messageJ.="<tr>";
$messageJ.="<td align=\"center\">MSN</td>";
$messageJ.="<td align=\"center\">OF</td>";
$messageJ.="<td align=\"center\">Titre</td>";
$messageJ.="<td align=\"center\">Zone Aircraft</td>";
$messageJ.="<td align=\"center\">Priorité</td>";
$messageJ.="<td align=\"center\">Statut PROD</td>";
$messageJ.="<td align=\"center\">RETP</td>";
$messageJ.="<td align=\"center\">Commentaire PROD</td>";
$messageJ.="<td align=\"center\">Statut QUALITE</td>";
$messageJ.="<td align=\"center\">RETQ</td>";
$messageJ.="<td align=\"center\">Commentaire QUALITE</td>";
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
$messageS.="<td align=\"center\">Statut PROD</td>";
$messageS.="<td align=\"center\">RETP</td>";
$messageS.="<td align=\"center\">Commentaire PROD</td>";
$messageS.="<td align=\"center\">Statut QUALITE</td>";
$messageS.="<td align=\"center\">RETQ</td>";
$messageS.="<td align=\"center\">Commentaire QUALITE</td>";
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
$messageN.="<td align=\"center\">Statut PROD</td>";
$messageN.="<td align=\"center\">RETP</td>";
$messageN.="<td align=\"center\">Commentaire PROD</td>";
$messageN.="<td align=\"center\">Statut QUALITE</td>";
$messageN.="<td align=\"center\">RETQ</td>";
$messageN.="<td align=\"center\">Commentaire QUALITE</td>";
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
$messageVSD.="<td align=\"center\">Statut PROD</td>";
$messageVSD.="<td align=\"center\">RETP</td>";
$messageVSD.="<td align=\"center\">Commentaire PROD</td>";
$messageVSD.="<td align=\"center\">Statut QUALITE</td>";
$messageVSD.="<td align=\"center\">RETQ</td>";
$messageVSD.="<td align=\"center\">Commentaire QUALITE</td>";
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
		if($row['Id_StatutQUALITE']<>""){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour" || $row['Vacation']=="VSD Nuit"){$vacation="VSD";}

		if($vacation=="Jour"){
			$messageJ.="<tr>";
			$messageJ.="<td width=\"3%\" align=\"center\"> ".$row['MSN']. "</td>";
			$messageJ.="<td width=\"6%\" align=\"center\"> ".$row['Reference']." </td>";
			$messageJ.="<td width=\"9%\" align=\"center\"> ".$row['Titre']." </td>";
			$messageJ.="<td width=\"6%\" align=\"center\"> ".$row['Zone']." </td>";
			$messageJ.="<td width=\"5%\" align=\"center\"> ".$Priorite." </td>";
			$messageJ.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutPROD']." </td>";
			$messageJ.="<td width=\"6%\" align=\"center\"> ".$row['RetourPROD']." </td>";
			$messageJ.="<td width=\"10%\" align=\"center\"> ".$row['CommentairePROD']." </td>";
			$messageJ.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutQUALITE']." </td>";
			$messageJ.="<td width=\"6%\" align=\"center\"> ".$row['RetourQUALITE']." </td>";
			$messageJ.="<td width=\"10%\" align=\"center\"> ".$row['CommentaireQUALITE']." </td>";
			$messageJ.="<td width=\"6%\" align=\"center\"> ".$row['DateIntervention']." </td>";
			$messageJ.="<td width=\"6%\" align=\"center\"> ".$vacation." </td>";
			$messageJ.="</tr>";
		}
		elseif($vacation=="Soir"){
			$messageS.="<tr>";
			$messageS.="<td width=\"3%\" align=\"center\"> ".$row['MSN']. "</td>";
			$messageS.="<td width=\"6%\" align=\"center\"> ".$row['Reference']." </td>";
			$messageS.="<td width=\"9%\" align=\"center\"> ".$row['Titre']." </td>";
			$messageS.="<td width=\"6%\" align=\"center\"> ".$row['Zone']." </td>";
			$messageS.="<td width=\"5%\" align=\"center\"> ".$Priorite." </td>";
			$messageS.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutPROD']." </td>";
			$messageS.="<td width=\"6%\" align=\"center\"> ".$row['RetourPROD']." </td>";
			$messageS.="<td width=\"10%\" align=\"center\"> ".$row['CommentairePROD']." </td>";
			$messageS.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutQUALITE']." </td>";
			$messageS.="<td width=\"6%\" align=\"center\"> ".$row['RetourQUALITE']." </td>";
			$messageS.="<td width=\"10%\" align=\"center\"> ".$row['CommentaireQUALITE']." </td>";
			$messageS.="<td width=\"6%\" align=\"center\"> ".$row['DateIntervention']." </td>";
			$messageS.="<td width=\"6%\" align=\"center\"> ".$vacation." </td>";
			$messageS.="</tr>";
		}
		elseif($vacation=="Nuit"){
			$messageN.="<tr>";
			$messageN.="<td width=\"3%\" align=\"center\"> ".$row['MSN']. "</td>";
			$messageN.="<td width=\"6%\" align=\"center\"> ".$row['Reference']." </td>";
			$messageN.="<td width=\"9%\" align=\"center\"> ".$row['Titre']." </td>";
			$messageN.="<td width=\"6%\" align=\"center\"> ".$row['Zone']." </td>";
			$messageN.="<td width=\"5%\" align=\"center\"> ".$Priorite." </td>";
			$messageN.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutPROD']." </td>";
			$messageN.="<td width=\"6%\" align=\"center\"> ".$row['RetourPROD']." </td>";
			$messageN.="<td width=\"10%\" align=\"center\"> ".$row['CommentairePROD']." </td>";
			$messageN.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutQUALITE']." </td>";
			$messageN.="<td width=\"6%\" align=\"center\"> ".$row['RetourQUALITE']." </td>";
			$messageN.="<td width=\"10%\" align=\"center\"> ".$row['CommentaireQUALITE']." </td>";
			$messageN.="<td width=\"6%\" align=\"center\"> ".$row['DateIntervention']." </td>";
			$messageN.="<td width=\"6%\" align=\"center\"> ".$vacation." </td>";
			$messageN.="</tr>";
		}
		elseif($vacation=="VSD"){
			$messageVSD.="<tr>";
			$messageVSD.="<td width=\"3%\" align=\"center\"> ".$row['MSN']. "</td>";
			$messageVSD.="<td width=\"6%\" align=\"center\"> ".$row['Reference']." </td>";
			$messageVSD.="<td width=\"9%\" align=\"center\"> ".$row['Titre']." </td>";
			$messageVSD.="<td width=\"6%\" align=\"center\"> ".$row['Zone']." </td>";
			$messageVSD.="<td width=\"5%\" align=\"center\"> ".$Priorite." </td>";
			$messageVSD.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutPROD']." </td>";
			$messageVSD.="<td width=\"6%\" align=\"center\"> ".$row['RetourPROD']." </td>";
			$messageVSD.="<td width=\"10%\" align=\"center\"> ".$row['CommentairePROD']." </td>";
			$messageVSD.="<td width=\"5%\" align=\"center\"> ".$row['Id_StatutQUALITE']." </td>";
			$messageVSD.="<td width=\"6%\" align=\"center\"> ".$row['RetourQUALITE']." </td>";
			$messageVSD.="<td width=\"10%\" align=\"center\"> ".$row['CommentaireQUALITE']." </td>";
			$messageVSD.="<td width=\"6%\" align=\"center\"> ".$row['DateIntervention']." </td>";
			$messageVSD.="<td width=\"6%\" align=\"center\"> ".$vacation." </td>";
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