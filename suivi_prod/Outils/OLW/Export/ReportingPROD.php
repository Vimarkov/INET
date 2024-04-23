<!DOCTYPE html>
<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");

$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

Ecrire_Code_JS_Init_Date();

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

$destinataire="";
$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneSP'];
$resulEmail=mysqli_query($bdd,$req);
$nbEmail=mysqli_num_rows($resulEmail);
if ($nbEmail>0){
	$row=mysqli_fetch_array($resulEmail);
	$destinataire=$row['EmailPro'];
}

$laVacation="";
if($vacation=="J"){$laVacation="Jour";}
elseif($vacation=="S"){$laVacation="Soir";}
if($vacation=="N"){$laVacation="Nuit";}
if($vacation=="VSD"){$laVacation="VSD";}

$req="SELECT sp_olwficheintervention.Id,";
$req.="sp_olwficheintervention.Id_StatutPROD,(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_olwficheintervention.Id_StatutQUALITE,(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_client.Libelle AS Client, ";
$req.="sp_olwficheintervention.PosteAvionACP AS Poste, ";
$req.="sp_olwdossier.MSN, ";
$req.="sp_olwdossier.Reference AS NoDossier, ";
$req.="'COMPETENCES' AS Competences, ";
$req.="sp_olwdossier.Titre, ";
$req.="sp_olwficheintervention.TravailRealise, ";
$req.="sp_olwficheintervention.Id_StatutPROD, ";
$req.="sp_olwficheintervention.Id_RetourPROD, ";
$req.="sp_olwficheintervention.CommentairePROD,";
$req.="sp_olwdossier.Elec,";
$req.="sp_olwdossier.Systeme,";
$req.="sp_olwdossier.Structure,";
$req.="sp_olwdossier.Oxygene,";
$req.="sp_olwdossier.Hydraulique,";
$req.="sp_olwdossier.Fuel,";
$req.="sp_olwdossier.Metal ";

$req.="FROM ";
$req.="sp_olwdossier, ";
$req.="sp_olwficheintervention, ";
$req.="sp_client ";

$req.="WHERE ";
$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id ";
$req.="AND sp_olwdossier.Id_Client = sp_client.Id ";

if($laVacation=="VSD"){
 	$req.="AND ( ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leVendredi."' AND sp_olwficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leSamedi."' AND sp_olwficheintervention.Vacation='VSD Jour') OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leSamedi."' AND sp_olwficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leDimanche."' AND sp_olwficheintervention.Vacation='VSD Jour')) ";
}
else{
	$req.="AND sp_olwficheintervention.DateIntervention='".$leJour."' AND sp_olwficheintervention.Vacation='".$vacation."' ";		
}
$req.="ORDER BY sp_olwdossier.MSN ASC, sp_olwdossier.Reference ASC ";

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
		$statut="";
		$retour="";
		$operateurs="";
		if($row['Id_StatutQUALITE']<>0){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		if($row['Id_RetourPROD']<>0){$retour=$row['RetourPROD'];}
		
		
		$couleur="#ffffff";
		if($statut=="TERA" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$Dispo++;
		if($statut=="TERA" || $statut=="REWORK"){$Qarj++;}
		elseif($statut=="TFS"){
			$TFS++;
			if($row['Id_RetourPROD']==15 || $row['Id_RetourPROD']==16 || $row['Id_RetourPROD']==38 || $row['Id_RetourPROD']==39){$Ec++;}
			elseif($row['Id_RetourPROD']==6 || $row['Id_RetourPROD']==14 || $row['Id_RetourPROD']==35){$Relancee++;}
			else{$Retp++;}
		}

		// Concaténation des compétences
		$competences="";
		if($row['Elec']=="1")
			$competences.=" ELEC ";

		if($row['Systeme']=="1")
			$competences.=" SYSTEME ";

		if($row['Structure']=="1")
			$competences.=" STRUCTURE ";

		if($row['Oxygene']=="1")
			$competences.=" OXYGENE ";

		if($row['Hydraulique']=="1")
			$competences.=" HYDRAULIQUE ";

		if($row['Fuel']=="1")
			$competences.=" FUEL ";

		if($row['Metal']=="1")
			$competences.=" METAL ";
					
		$req="SELECT ";
		$req.="	SUM(sp_olwfi_travaileffectue.TempsPasse) AS TotalTempsPasse ";
		$req.="FROM ";
		$req.="	sp_olwfi_travaileffectue ";
		$req.="WHERE ";
		$req.="	sp_olwfi_travaileffectue.Id_FI = ".$row['Id']." ";

		$resultTP=mysqli_query($bdd,$req); 
		$rowTP=mysqli_fetch_array($resultTP);
		$sommeTP+=$rowTP['TotalTempsPasse'];
		
		$req="SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Operateur 
			FROM sp_olwfi_travaileffectue 
			WHERE Id_FI = ".$row['Id']." ";

		$resultOperateur=mysqli_query($bdd,$req); 
		$nbResultaOP=mysqli_num_rows($resultOperateur);
		if ($nbResultaOP>0){	
			while($rowOP=mysqli_fetch_array($resultOperateur)){
				if($operateurs<>""){$operateurs.="<br>";}
				$operateurs.=$rowOP['Operateur']."";
			}
		}
		
		
		$couleurPrio="#ffffff";
		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
		else{$couleurPrio="#ed1c24";}
		$messageSuite.="<tr>";
		$messageSuite.="<td>".$row['Client']."</td>";
		$messageSuite.="<td>".$row['Poste']."</td>";
		$messageSuite.="<td>".$row['MSN']."</td>";
		$messageSuite.="<td>".$row['NoDossier']."</td>";
		$messageSuite.="<td>".$competences."</td>";
		$messageSuite.="<td>".$row['Titre']."</td>";
		$messageSuite.="<td>".$row['TravailRealise']."</td>";
		$messageSuite.="<td>".$operateurs."</td>";
		$messageSuite.="<td>".$rowTP['TotalTempsPasse']."</td>";
		$messageSuite.="<td>".$statut."</td>";
		$messageSuite.="<td>".$retour."</td>";
		$messageSuite.="<td>".$row['CommentairePROD']."</td>";
		$messageSuite.="</tr>";
		
	}
}
if($laVacation=="VSD"){
	$object = "Reporting PROD - du ".$leVendredi." | ".$leSamedi." | ".$leDimanche." Vacation ".$laVacation;
}
else{
	$object = "Reporting PROD - du ".$dateReporting." Vacation ".$laVacation;
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
$message.="<td align='center'>Client</td>";
$message.="<td align='center'>Poste</td>";
$message.="<td align='center'>N° MSN</td>";
$message.="<td align='center'>N° Dossier</td>";
$message.="<td align='center'>Compétence</td>";
$message.="<td align='center'>Titre</td>";
$message.="<td align='center'>Travail à réaliser</td>";
$message.="<td align='center'>Opérateur</td>";
$message.="<td align='center'>Temps passé</td>";
$message.="<td align='center'>Statut production</td>";
$message.="<td align='center'>Retour Production</td>";
$message.="<td align='center'>Commentaire Production</td>";
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