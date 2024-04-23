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
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);		
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
	
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

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.TAI_RestantACP, ";
$req.="sp_dossier.Priorite,sp_dossier.Titre,sp_ficheintervention.CommentairePROD,sp_ficheintervention.Id_RetourPROD, ";
$req.="sp_ficheintervention.Id_StatutPROD,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_ficheintervention.Id_StatutQUALITE,(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_dossier.Priorite ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
if($laVacation=="VSD"){
	$req.="WHERE sp_ficheintervention.Id_Pole=".$pole." AND ( ";
	$req.="(sp_ficheintervention.DateIntervention='".$leVendredi."' AND sp_ficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND sp_ficheintervention.Vacation='VSD Jour') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leSamedi."' AND sp_ficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_ficheintervention.DateIntervention='".$leDimanche."' AND sp_ficheintervention.Vacation='VSD Jour')) ";
}
elseif($vacation=="N" && $NumJour=="1"){
	$req.="WHERE sp_ficheintervention.DateIntervention='".$laVeille."' AND sp_ficheintervention.Vacation='VSD Nuit' AND sp_ficheintervention.Id_Pole=".$pole." ";
}
else{
	$req.="WHERE sp_ficheintervention.DateIntervention='".$leJour."' AND sp_ficheintervention.Vacation='".$vacation."' AND sp_ficheintervention.Id_Pole=".$pole." ";
}
$req.="ORDER BY sp_dossier.MSN ASC, sp_dossier.Reference ASC ";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$messageSuite="";
$Dispo=0;
$Qarj=0;
$Ec=0;
$Retp=0;
$Relancee=0;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		
		$statut="";
		$retour="";
		if($row['Id_StatutQUALITE']<>0){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		if($row['Id_RetourPROD']<>0){$retour=$row['RetourPROD'];}
		
		
		$couleur="#ffffff";
		if($statut=="QARJ" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$Dispo++;
		if($statut=="QARJ" || $statut=="REWORK"){$Qarj++;}
		elseif($statut=="TFS"){
			if($row['Id_RetourPROD']==15 || $row['Id_RetourPROD']==16 || $row['Id_RetourPROD']==38 || $row['Id_RetourPROD']==39){$Ec++;}
			elseif($row['Id_RetourPROD']==6 || $row['Id_RetourPROD']==14 || $row['Id_RetourPROD']==35){$Relancee++;}
			else{$Retp++;}
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
		$messageSuite.="<td>".$row['TAI_RestantACP']."</td>";
		$messageSuite.="<td>".$statut."</td>";
		$messageSuite.="<td>".$retour."</td>";
		$messageSuite.="<td>".$row['CommentairePROD']."</td>";
		$messageSuite.="</tr>";
	}
}
if($laVacation=="VSD"){
	$object = "Reporting PROD - du ".$leVendredi." | ".$leSamedi." | ".$leDimanche." Vacation ".$laVacation." Pôle ".$lePole;
}
else{
	$object = "Reporting PROD - du ".$dateReporting." Vacation ".$laVacation." Pôle ".$lePole;
}
$message="<html>";
$message.="<head>";
$message.="<title>Reporting</title>";
$message.="</head>";
$message.="<body>";
$message.="<table width='100%'>";
$message.="<tr><td>Bonjour,</td></tr>";
$message.="<tr><td>1. Bilan de la vacation :</td></tr>";
$message.="<tr><td><table border='2' width='50%' cellpadding='0' cellspacing='0' align='left'>";
if($laVacation=="VSD"){
	$message.="<tr><td >Date</td><td align='center'>".$leVendredi." | ".$leSamedi." | ".$leDimanche."</td></tr> \n";
}
else{
	$message.="<tr><td >Date</td><td align='center'>".$dateReporting."</td></tr> \n";
}
$message.="<tr><td >Pôle</td><td align='center'>".$lePole."</td></tr> \n";
$message.="<tr><td >Vacation</td><td align='center'>".$laVacation."</td></tr> \n";
$message.="<tr><td >Nombre de BC présent</td><td align='center'></td></tr> \n";
$message.="<tr><td >Gammes Disponibles/planifiées</td><td align='center'>".$Dispo."</td></tr> \n";
$message.="<tr><td >Gammes QARJ 100%</td><td align='center'>".$Qarj."</td></tr> \n";
$message.="<tr><td >Gammes E/C</td><td align='center'>".$Ec."</td></tr> \n";
$message.="<tr><td >Gammes RETP</td><td align='center'>".$Retp."</td></tr> \n";
$message.="<tr><td >Gammes Relancée</td><td align='center'>".$Relancee."</td></tr> \n";
$message.="</table></td></tr>";
$message.="<tr><td>2. Points chauds :</td></tr>";
$message.="<tr><td></td></tr>";
$message.="<tr><td>3. Activité vacation :</td></tr>";
$message.="<tr><td><table border='2' width='100%' cellpadding='0' cellspacing='0' align='left' style='font-size:12px;'>";
$message.="<tr>";
$message.="<td align='center'>MSN</td>";
$message.="<td align='center'>OF</td>";
$message.="<td align='center'>Titre</td>";
$message.="<td align='center'>Priorité</td>";
$message.="<td align='center'>TAI restant</td>";
$message.="<td align='center'>Statut</td>";
$message.="<td align='center'>Retour</td>";
$message.="<td align='center'>Commentaire</td>";
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