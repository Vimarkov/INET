<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require_once("Fonctions_Planning.php");

require_once '../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$requete="SELECT rh_personne_demandeabsence.Id,DateCreation,
	rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,Id_Personne,
	rh_personne_demandeabsence.DatePriseEnCompteRH,rh_personne_demandeabsence.DateValidationN1,rh_personne_demandeabsence.DateValidationN2,
	rh_personne_demandeabsence.Id_Prestation,rh_personne_demandeabsence.Id_Pole,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_N1) AS ResponsableN1,  
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_N2) AS ResponsableN2,  
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS RaisonRefus1,Commentaire1,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,Commentaire2,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation, 
	(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Nom, 
	(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Prenom,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne,
	(SELECT IF(TelephoneProMobil<>'',TelephoneProMobil,TelephoneProFixe) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Tel,  
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole 
	FROM rh_personne_demandeabsence
	WHERE rh_personne_demandeabsence.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$signatureN2="";
if($row['DateValidationN2']>'0001-01-01'){$signatureN2=$row['ResponsableN2']."<br>'Signature électronique'";}

$req="SELECT
	(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS Contrat 
	FROM rh_personne_contrat 
	WHERE Id=".IdContrat($row['Id_Personne'],$row['DateCreation']);
$resultContrat=mysqli_query($bdd,$req);
$nbContrat=mysqli_num_rows($resultContrat);
$cdd="CDD";
$cdi="CDI";
$interim="Intérim";
if($nbContrat>0){
	$rowContat=mysqli_fetch_array($resultContrat);
	if(substr($rowContat['Contrat'],0,3)=="CDI"){
		$cdd="<span style='text-decoration:line-through;'>CDD</span>";
		$interim="<span style='text-decoration:line-through;'>Intérim</span>";
	}
	elseif($rowContat['Contrat']=="CDD" || $rowContat['Contrat']=="Alternant AAA"){
		$cdi="<span style='text-decoration:line-through;'>CDI</span>";
		$interim="<span style='text-decoration:line-through;'>Intérim</span>";
	}
	elseif($rowContat['Contrat']=="Intérim"  || $rowContat['Contrat']=="Alternant intérimaire"){
		$cdi="<span style='text-decoration:line-through;'>CDI</span>";
		$cdd="<span style='text-decoration:line-through;'>CDD</span>";
	}
	else{
		$cdi="<span style='text-decoration:line-through;'>CDI</span>";
		$cdd="<span style='text-decoration:line-through;'>CDD</span>";
		$interim="<span style='text-decoration:line-through;'>Intérim</span>";
	}
}

$requete="SELECT rh_absence.Id, rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,rh_absence.NbJour,
	IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) AS Id_TypeAbs,
	(SELECT rh_typeabsence.NbJourAutorise FROM rh_typeabsence WHERE rh_typeabsence.Id=IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial)) AS NbJourAutorise,
	rh_absence.Id_TypeAbsenceInitial,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,rh_absence.Id_FonctionRepresentative,
	(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsIni,
	(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsDef,
	(SELECT Libelle FROM rh_fonctionrepresentative WHERE rh_fonctionrepresentative.Id=Id_FonctionRepresentative) AS FonctionRepresentative,
	rh_absence.HeureDepart,rh_absence.HeureArrivee
	FROM rh_absence
	WHERE Suppr=0 AND rh_absence.Id_Personne_DA=".$_GET['Id']."
	ORDER BY DateDebut
	" ;
$resultAbs=mysqli_query($bdd,$requete);
$nbAbs=mysqli_num_rows($resultAbs);
$listeAbs="";
$CP=0;
$CA=0;
$CPA=0;
$CSS=0;
$CSSHeures=0;
$ReposComp=0;
$RTT=0;
$Mar=0;
$Nai=0;
$Dec=0;
$BDD=0;
$nbHeures=0;
$fonction="";
if($nbAbs>0){
	while($rowAbs=mysqli_fetch_array($resultAbs)){
		$listeAbs.="
			<tr>
				<td width='20%' style='font:bold 16px;border-left:solid 1px black;border-bottom:solid 1px black;color:#0070c0;'>&nbsp;du :</td>
				<td width='20%' style='font:bold 16px;border-bottom:solid 1px black;border-right:solid 1px black;color:#0070c0;' align='center'>".AfficheDateJJ_MM_AAAA($rowAbs['DateDebut'])."</td>
				<td width='20%' style='font:bold 16px;border-bottom:solid 1px black;color:#0070c0;'>&nbsp;Au :</td>
				<td width='20%' style='font:bold 16px;border-bottom:solid 1px black;color:#0070c0;' align='center'>".AfficheDateJJ_MM_AAAA($rowAbs['DateFin'])."</td>
				<td width='20%' style='font:bold 16px;border-bottom:solid 1px black;border-right:solid 1px black;color:#0070c0;' align='center'>inclus</td>
			</tr>
		";
		$IdType=0;
		if($rowAbs['Id_TypeAbsenceDefinitif']>0){$IdType=$rowAbs['Id_TypeAbsenceDefinitif'];}
		else{$IdType=$rowAbs['Id_TypeAbsenceInitial'];}
		
		
		if($IdType==2 || $IdType==22){$Nai+=$rowAbs['NbJour'];}
		elseif($IdType==3){$CP+=$rowAbs['NbJour'];}
		elseif($IdType==4){$CA+=$rowAbs['NbJour'];}
		elseif($IdType==5){$CPA+=$rowAbs['NbJour'];}
		elseif($IdType==7){$RTT+=$rowAbs['NbJour'];}
		elseif($IdType==8){$CSS+=$rowAbs['NbJour'];$CSSHeures+=$rowAbs['NbHeureAbsJour']+$rowAbs['NbHeureAbsNuit'];}
		elseif($IdType==9){$BDD+=$rowAbs['NbJour'];$nbHeures+=$rowAbs['NbHeureAbsJour'];}
		elseif($IdType==11){$ReposComp+=$rowAbs['NbJour'];}
		elseif($IdType==12 || $IdType==20 || $IdType==21){$Mar+=$rowAbs['NbJour'];}
		elseif($IdType==13 || $IdType==14 || $IdType==15 || $IdType==16 || $IdType==17 || $IdType==18 || $IdType==19 || $IdType==27){$Dec+=$rowAbs['NbJour'];}
	}
}
if($CP==0){$CP="";}
if($CA==0){$CA="";}
if($CPA==0){$CPA="";}
if($CSS==0){$CSS="";}
if($ReposComp==0){$ReposComp="";}
if($RTT==0){$RTT="";}
if($Mar==0){$Mar="";}
if($Nai==0){$Nai="";}	
if($Dec==0){$Dec="";}	
if($BDD==0){$BDD="";}	
if($nbHeures==0){$nbHeures="";}else{$nbHeures.=" h";}
if($CSSHeures==0){$CSSHeures="";}else{$CSSHeures=" | ".$CSSHeures." h";}

$requete="SELECT DISTINCT Id_FonctionRepresentative,
	(SELECT Libelle FROM rh_fonctionrepresentative WHERE rh_fonctionrepresentative.Id=Id_FonctionRepresentative) AS FonctionRepresentative
	FROM rh_absence
	WHERE Suppr=0 AND rh_absence.Id_Personne_DA=".$_GET['Id']."
	" ;
$resultAbs=mysqli_query($bdd,$requete);
$nbAbs=mysqli_num_rows($resultAbs);
if($nbAbs>0){
	while($rowAbs=mysqli_fetch_array($resultAbs)){
		$fonction.=$rowAbs['FonctionRepresentative']." ";
	}
}
$formulaire="
<html style='background-color:#ffffff;'>
<head>
	<link type='text/css' href='../../CSS/FeuillePDF.css' rel='stylesheet' />
</head>
<table width='100%' style='background-color:#ffffff;'>
	<tr>
		<td width='20%'><img width='200px' src='../../Images/Logos/Logo Daher_posi.png' border='0' /></td>
	</tr>
	<tr>
		<td width='60%' colspan='4' style='font:bold 30px;background:#0070c0;color:#ffffff;border:1px solid black;' align='center'>DEMANDE d'ABSENCE</td>
	</tr>
	<tr>
		<td width='100%' colspan='4'>
			<table width='95%' cellpadding='0' cellspacing='0'>
				<tr>
					<td colspan='4' style='font:bold 16px;border:solid 1px black;'>&nbsp;<span style='color:#0070c0;'>Nom : </span><span style='font:15px;'>".$row['Nom']."</span></td>
				</tr>
				<tr>
					<td colspan='4' style='font:bold 16px;border:solid 1px black;'>&nbsp;<span style='color:#0070c0;'>Prénom : </span><span style='font:15px;'>".$row['Prenom']."</span></td>
				</tr>
				<tr>
					<td colspan='4' style='font:bold 16px;border:solid 1px black;'>&nbsp;<span style='color:#0070c0;'>Téléphone professionnel : </span><span style='font:15px;'>".$row['Tel']."</span></td>
				</tr>
				<tr>
					<td colspan='4' style='font:bold 16px;border:solid 1px black;'>&nbsp;<span style='color:#0070c0;'>Site : </span><span style='font:15px;'>".substr($row['Prestation'],0,7)." ".$row['Pole']."</span></td>
				</tr>
				<tr>
					<td width='40%' style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Contrat <span style='font:15px;'>(Barrer la mention inutile)</span> </td>
					<td width='20%' style='font:bold 16px;border:solid 1px black;color:#0070c0;' align='center'>".$cdd."</td>
					<td width='20%' style='font:bold 16px;border:solid 1px black;color:#0070c0;' align='center'>".$cdi."</td>
					<td width='20%' style='font:bold 16px;border:solid 1px black;color:#0070c0;' align='center'>".$interim."</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height='5'></td>
	</tr>
	<tr>
		<td width='100%' colspan='4'>
			<table width='95%' cellpadding='0' cellspacing='0'>
				<tr>
					<td colspan='5' style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Période d'absence souhaitée : </td>
				</tr>
				".$listeAbs."
			</table>
		</td>
	</tr>
	<tr>
		<td height='5'></td>
	</tr>
	<tr>
		<td width='100%' colspan='4'>
			<table width='95%' cellpadding='0' cellspacing='0'>
				<tr>
					
					<td width='38%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;' align='center'>Type d'absences</td>
					<td width='13%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;' align='center'>Nombre de jours</td>
					<td width='13%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;' align='center'>Indiquer période si multiple</td>
					<td width='22%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;border-top:solid 2px black;border-left:solid 2px black;' align='center'>Nombre réellement décompté</td>
					<td width='22%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;border-top:solid 2px black;' align='center'>Nombre restants à prendre aprés cette demande</td>
					<td width='5%' rowspan='11' style='font:bold;border:solid 1px black;background-color:#e7e6e6;border-right:solid 2px black;border-top:solid 2px black;border-bottom:solid 2px black;'><img width='25px' src='../../Images/reserve.png' border='0' /></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Congés Payés</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$CP."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Congés Ancienneté</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$CA."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Congés par Anticipation</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$CPA."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Congés sans Solde</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$CSS." ".$CSSHeures."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Congés Parental</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Repos Compensateur</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$ReposComp."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;RTT</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$RTT."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Mariage*</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$Mar."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Naissance*</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$Nai."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Décès*</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$Dec."</td>
					<td style='font:16px;border:solid 1px black;' align='center'></td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;border-bottom:solid 2px black;'></td>
					<td style='font:16px;border:solid 1px black;border-bottom:solid 2px black;'></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan='4'><span style='color:#00b050;font:bold;'>* joindre un justificatif - indiquer le lien de parenté en cas de décès</span></td>
	</tr>
	<tr>
		<td height='5'></td>
	</tr>
	<tr>
		<td width='100%' colspan='4'>
			<table width='95%' cellpadding='0' cellspacing='0'>
				<tr>
					<td width='38%' style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Absence hors catégories ci-dessus</td>
					<td width='13%' style='font:16px;border:solid 1px black;'></td>
					<td width='22%' style='font:16px;border:solid 1px black;'></td>
					<td width='22%' style='font:16px;border:solid 1px black;'></td>
					<td width='5%' style='font:16px;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Motif de cette Absence</td>
					<td style='font:16px;border:solid 1px black;' colspan='3'></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height='5'></td>
	</tr>
	<tr>
		<td width='100%' colspan='4'>
			<table width='95%' cellpadding='0' cellspacing='0'>
				<tr>
					<td width='15%'></td>
					<td width='25%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;' align='center'>Le Salarié</td>
					<td width='25%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;border-top:solid 2px black;border-left:solid 2px black;' align='center'>Le Responsable</td>
					<td width='25%' style='font:bold 16px;border:solid 1px black;background-color:#00b050;color:#ffffff;border-top:solid 2px black;' align='center'>La Direction</td>
					<td width='5%' rowspan='3' style='font:bold;border:solid 1px black;background-color:#e7e6e6;color:#ffffff;border-right:solid 2px black;border-top:solid 2px black;border-bottom:solid 2px black;'><img width='50px' src='../../Images/reserve2.png' border='0' /></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Date</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".AfficheDateJJ_MM_AAAA($row['DateCreation'])."</td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;' align='center'>".AfficheDateJJ_MM_AAAA($row['DateValidationN2'])."</td>
					<td style='font:16px;border:solid 1px black;'></td>
				</tr>
				<tr>
					<td style='font:bold 16px;border:solid 1px black;color:#0070c0;'>&nbsp;Signature</td>
					<td style='font:16px;border:solid 1px black;' align='center'>".$row['Personne']."<br>'Signature électronique'</td>
					<td style='font:16px;border:solid 1px black;border-left:solid 2px black;border-bottom:solid 2px black;' align='center'>".$signatureN2."</td>
					<td style='font:16px;border:solid 1px black;border-bottom:solid 2px black;'></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height='5'></td>
	</tr>
	<tr>
		<td colspan='4'><span style='color:#00b050;font:bold;'>Cette demande est à faire signer par sa hiérarchie qui la transmet au siège sans attendre.</span></td>
	</tr>
	<tr>
		<td height='8'></td>
	</tr>
	<tr>
		<td colspan='4' align='center'>
			<table width='95%' cellpadding='0' cellspacing='0'>
				<tr>
					<td width='5%' rowspan='2' align='right'><img src='../../Images/afaq1.png' border='0' /></td>
					<td width='90%' style='border-bottom:1px solid #4bacc6;font:10px;' align='center'>Siège Social : 10, rue Mercœur - 75011 Paris - Tél. 33 (0)1 48 06 85 85 - Fax. 33 (0)1 48 06 32 19<br>Société par Actions Simplifiée au capital de 1.600.000 Euros<br>RCS Paris B 353 522 204 - N° Siret 353 522 204 00059 - Code NAF 3030 Z - TVA FR52 353 522 204</td>
					<td width='5%' rowspan='2'><img src='../../Images/afaq2.png' border='0' /></td>
				</tr>
				<tr>
					<td height='10'></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</html>
";

$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
?>