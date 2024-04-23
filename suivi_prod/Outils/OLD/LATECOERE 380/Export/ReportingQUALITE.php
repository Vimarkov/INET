<!DOCTYPE html>
<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");

$pole = $_GET['pole'];
$vacation = $_GET['vacation'];
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
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);	
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

$lePole="";
$req="SELECT IF(Id=5,'STRUCTURE',Libelle) AS Libelle FROM new_competences_pole WHERE Id=".$pole;
$resulPole=mysqli_query($bdd,$req);
$nbPole=mysqli_num_rows($resulPole);
if ($nbPole>0){
	$row=mysqli_fetch_array($resulPole);
	$lePole=$row['Libelle'];
}
$laVacation="";
if($vacation=="J"){$laVacation="Jour";}
elseif($vacation=="S"){$laVacation="Soir";}
if($vacation=="N"){$laVacation="Nuit";}
if($vacation=="VSD"){$laVacation="VSD";}

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.Titre,sp_dossier.PNE, ";
$req.="sp_dossier.Priorite,sp_ficheintervention.CommentaireQUALITE,sp_ficheintervention.Id_RetourQUALITE,sp_ficheintervention.SaisieQualite, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($laVacation=="VSD"){
	$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND ( ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leVendredi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Jour') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leDimanche."' AND sp_ficheintervention.VacationQ='VSD Jour')) ";
}
elseif($vacation=="N" && $NumJour=="1"){
	$req.="WHERE sp_ficheintervention.DateInterventionQ='".$laVeille."' AND sp_ficheintervention.VacationQ='VSD Nuit' AND sp_ficheintervention.Id_Pole=".$pole." ";
}
else{
	$req.="WHERE sp_ficheintervention.DateInterventionQ='".$leJour."' AND sp_ficheintervention.VacationQ='".$vacation."' AND sp_ficheintervention.Id_Pole=".$pole." ";
}
$req.="ORDER BY sp_dossier.MSN ASC, sp_dossier.Reference ASC ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$req="SELECT sp_ficheintervention.Id ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND sp_ficheintervention.DateCreation>='2016-05-23' AND sp_ficheintervention.DateIntervention<='".$leJour."' AND (sp_ficheintervention.Id_StatutPROD='QARJ' OR sp_ficheintervention.Id_StatutPROD='REWORK') AND ( sp_ficheintervention.Id_StatutQUALITE='' OR ( ";
if($laVacation=="VSD"){
	$req.=" sp_ficheintervention.Id_StatutQUALITE<>'' AND ( ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leVendredi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Jour') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leSamedi."' AND sp_ficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateInterventionQ='".$leDimanche."' AND sp_ficheintervention.VacationQ='VSD Jour')))) ";
}
elseif($vacation=="N" && $NumJour=="1"){
	$req.=" sp_ficheintervention.DateInterventionQ='".$laVeille."'  AND sp_ficheintervention.Id_StatutQUALITE<>'' AND sp_ficheintervention.VacationQ='VSD Nuit' AND sp_ficheintervention.Id_Pole=".$pole.")) ";
}
else{
	$req.=" sp_ficheintervention.DateInterventionQ='".$leJour."' AND sp_ficheintervention.Id_StatutQUALITE<>'' AND sp_ficheintervention.VacationQ='".$vacation."' AND sp_ficheintervention.Id_Pole=".$pole.")) ";
}
$resultDispo=mysqli_query($bdd,$req);
$nbDispo=mysqli_num_rows($resultDispo);

$messageSuite="";
$Dispo=0;
$Cert=0;
$CertPNE=0;
$CertOnly=0;
$Avancee=0;
$Retc=0;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){	
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut=$row['Id_StatutQUALITE'];
		$retour=$row['RetourQUALITE'];
		
		$couleur="#ffffff";
		if($statut=="CERT"){$couleur="#00b050";}
		elseif($statut=="TVS"){$couleur="#ffc000";}
		
		if(($row['Id_StatutPROD']<>"QARJ" && $row['Id_StatutPROD']<>"REWORK") || (($row['Id_StatutPROD']=="QARJ" || $row['Id_StatutPROD']=="REWORK") && $statut<>"")){
			$Dispo++;
		}
		if($statut=="CERT"){
			if($row['SaisieQualite']==1){
				$CertOnly++;
			}
			else{
				if($row['PNE']==0){$Cert++;}
				else{$CertPNE++;}
			}
		}
		elseif($statut=="TVS"){
			if($row['Id_RetourQUALITE']==5){$Avancee++;}
			else{$Retc++;}
		}
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		$messageSuite.="<tr>";
		$messageSuite.="<td>".$row['MSN']."</td>";
		$messageSuite.="<td>".$row['Reference']."</td>";
		$messageSuite.="<td>".$row['Titre']."</td>";
		$messageSuite.="<td>".$Priorite."</td>";
		$messageSuite.="<td>".$statut."</td>";
		$messageSuite.="<td>".$retour."</td>";
		$messageSuite.="<td>".$row['CommentaireQUALITE']."</td>";
		$messageSuite.="</tr>";
	}
}

if($laVacation=="VSD"){
	$object = "Reporting QUALITE - du ".$leVendredi." | ".$leSamedi." | ".$leDimanche." Vacation ".$laVacation." Pôle ".$lePole;
}
else{
	$object = "Reporting QUALITE - du ".$dateReporting." Vacation ".$laVacation." Pôle ".$lePole;
}

$message="<html>";
$message.="<head>";
$message.="<title>Reporting</title><meta name="robots" content="noindex">";
$message.="</head>";
$message.="<body>";
$message.="<table width='100%'>";
$message.="<tr><td>Bonjour,</td></tr>";
$message.="<tr><td>1. Bilan de la vacation :</td></tr>";
$message.="<tr><td><table border='2' width='50%' cellpadding='0' cellspacing='0' align='left'>";
$message.="<tr><td >Vacation</td><td Style='text-align:center;'>".$laVacation."</td></tr> \n";
$message.="<tr><td >Pôle</td><td Style='text-align:center;'>".$lePole."</td></tr> \n";
if($laVacation=="VSD"){
	$message.="<tr><td >Date</td><td align='center'>".$leVendredi." | ".$leSamedi." | ".$leDimanche."</td></tr> \n";
}
else{
	$message.="<tr><td >Date</td><td align='center'>".$dateReporting."</td></tr> \n";
}
$Dispo+=$nbDispo;
$message.="<tr><td >Nombre d'IQ présent</td><td align='center'></td></tr> \n";
$message.="<tr><td >Gammes disponibles pour contrôle</td><td Style='text-align:center;'>".$Dispo."</td></tr> \n";
$message.="<tr><td >Gammes CERT (hors PNE) 100%</td><td Style='text-align:center;'>".$Cert."</td></tr> \n";
$message.="<tr><td >Gammes CERT PNE 100%</td><td Style='text-align:center;'>".$CertPNE."</td></tr> \n";
$message.="<tr><td >Gammes CERT only 100%</td><td Style='text-align:center;'>".$CertOnly."</td></tr> \n";
$message.="<tr><td >Gammes avancées</td><td Style='text-align:center;'>".$Avancee."</td></tr> \n";
$message.="<tr><td >Gammes RETC</td><td Style='text-align:center;'>".$Retc."</td></tr> \n";
$message.="</table></td></tr>";
$message.="<tr><td>2. Points chauds :</td></tr>";
$message.="<tr><td>3. Activité vacation :</td></tr>";
$message.="<tr><td><table border='2' width='100%' cellpadding='0' cellspacing='0' align='left' style='font-size:12px;'>";
$message.="<tr>";
$message.="<td style='text-align:center;'>MSN</td>";
$message.="<td style='text-align:center;'>OF</td>";
$message.="<td style='text-align:center;'>Titre</td>";
$message.="<td style='text-align:center;'>Priorité</td>";
$message.="<td style='text-align:center;'>Statut</td>";
$message.="<td style='text-align:center;'>Retour</td>";
$message.="<td style='text-align:center;'>Commentaire</td>";
$message.="</tr>";
$message.=$messageSuite;
$message.="</table></td></tr>";
$message.="</table></body></html>";

if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
echo "<script>window.close();</script>";
}
else{echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";}	

//echo "<script>window.close();</script>";
 ?>