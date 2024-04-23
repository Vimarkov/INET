<?php
session_start();
require("../ConnexioniSansBody.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");

require_once '../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$req = "
SELECT
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS STAGIAIRE,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne_qualification.Id_Ouvreur) AS FORMATEUR_NOMPRENOM,
	form_besoin.Id_Formation AS ID_FORMATION,
	(SELECT Id_TypeFormation FROM form_formation WHERE Id=form_besoin.Id_Formation) AS ID_TYPEFORMATION,
	(SELECT Reference FROM form_formation WHERE Id=form_besoin.Id_Formation) AS FORMATION_REFERENCE,
	IF(form_besoin.Motif='Renouvellement',1,0) AS RECYCLAGE,
	'' AS LIEU,
	(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) AS ID_PLATEFORME,
	LEFT(form_session_personne_qualification.DateHeureRepondeur,10) AS DATE_DEBUT
FROM
	form_session_personne_qualification
LEFT JOIN form_besoin
ON form_session_personne_qualification.Id_Besoin=form_besoin.Id
WHERE
	form_session_personne_qualification.Id=".$_GET['Id']."";
$ResultSession=mysqli_query($bdd,$req);
$RowSession=mysqli_fetch_array($ResultSession);

//$ResultSession=get_session($_GET['Id']);
//$RowSession=mysqli_fetch_array($ResultSession);

//Plateforme
$req="SELECT Libelle,Logo FROM new_competences_plateforme WHERE Id=".$RowSession['ID_PLATEFORME'];
$result=mysqli_query($bdd,$req);
$rowPlat=mysqli_fetch_array($result);

//INFORMATION SUR LA FORMATION 
//INTITULE
$req="SELECT Id_Langue 
	FROM form_formation_plateforme_parametres 
	WHERE Suppr=0 AND Id_Plateforme=".$RowSession['ID_PLATEFORME']." AND Id_Formation=".$RowSession['ID_FORMATION'];
$result=mysqli_query($bdd,$req);
$rowLangue=mysqli_fetch_array($result);

$req="SELECT Libelle, LibelleRecyclage 
	FROM form_formation_langue_infos 
	WHERE Suppr=0 AND Id_Langue=".$rowLangue['Id_Langue']." AND Id_Formation=".$RowSession['ID_FORMATION'];
$result=mysqli_query($bdd,$req);
$rowLibelle=mysqli_fetch_array($result);

$formulaire='<html>
 <head>
    	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
		<style>
			html{margin:40px 20px}
		</style>
    </head>
';

$formulaire.='<body style="background-color:#ffffff">
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=113 style="border:1px solid black" align="center" rowspan="2"><img width=113 height=52 src="../../Images/Logos/Logo Daher_posi.png"></td>
		<td width=350 height=56 align="center" style="border:1px solid black;background-color:#e7e6e6;color:#000080;font-size:20px;" rowspan="2">
			<img src="../../Images/FlecheGauche.png">
			<b>&nbsp;&nbsp;&nbsp;FICHE DE PRESENCE&nbsp;&nbsp;</b>
			<img src="../../Images/FlecheDroite.png">
		</td>
		<td width=113 style="border:1px solid black" align="center"></td>
	</tr>
	<tr>
		<td width=113 height="15" style="border:1px solid black" align="center"><b>'.$rowPlat['Libelle'].'</b></td>
	</tr>
</table>

<br><br><br><br><br><br>

<table cellpadding=0 cellspacing=0 align=left style="background-color:#c0c0c0">
	<tr>
		<td width=150 height=20 style="color:#000080;;font-size:17px;" align="center"><img width=15 height=15 src="../../Images/checked.png">&nbsp;&nbsp;<b>FORMATION</b></td>
		<td width=180 height=20 style="color:#000080;;font-size:17px;" align="center"><img width=15 height=15 src="../../Images/checkednot.png">&nbsp;&nbsp;<b>INFORMATION</b></td>
		<td width=250 height=20 style="color:#000080;;font-size:17px;" align="center"><img width=15 height=15 src="../../Images/checkednot.png">&nbsp;&nbsp;<b>SENSIBILISATION</b></td>
	</tr>
</table>

<br><br>';

if($RowSession['RECYCLAGE']==0){$Libelle=$rowLibelle['Libelle'];}
else{$Libelle=$rowLibelle['LibelleRecyclage'];}

$interne='<img width=15 height=15 src="../../Images/checkednot.png">';
$externe='<img width=15 height=15 src="../../Images/checkednot.png">';

if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationInterne)
{
	$interne='<img width=15 height=15 src="../../Images/checked.png">';
}
else{
	$externe='<img width=15 height=15 src="../../Images/checked.png">';
}
$formulaire.='

<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=150 height=20 style="font-size:14px;" align="center">&nbsp;&nbsp;<b>&nbsp;&nbsp;&nbsp;INTITULE : '.$Libelle.'</b></td>
		<td width=250 height=20 style="font-size:14px;" align="center">&nbsp;&nbsp;<b>N° REF : '.$RowSession['FORMATION_REFERENCE'].'</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$interne.'&nbsp;<b>INTERNE</b>&nbsp;&nbsp;'.$externe.'&nbsp;<b>EXTERNE</b>&nbsp;&nbsp;</td>
	</tr>
</table>';

//DUREE
$req="SELECT Duree, DureeRecyclage 
	FROM form_formation_plateforme_parametres 
	WHERE Suppr=0 AND Id_Plateforme=".$RowSession['ID_PLATEFORME']." AND Id_Formation=".$RowSession['ID_FORMATION'];
$result=mysqli_query($bdd,$req);
$rowDuree=mysqli_fetch_array($result);

$req=" 
	SELECT DateSession AS DateDebut, Heure_Debut AS HEURE_DEBUT
	FROM form_session_date 
	LEFT JOIN form_session 
	ON form_session_date.Id_Session=form_session.Id
	WHERE form_session_date.Suppr=0
	AND form_session.Suppr=0
	AND form_session.Id=".$_GET['Id']." 
	ORDER BY DateSession ASC, Heure_Debut ASC
	";
$ResultInfos=mysqli_query($bdd,$req);
$RowInfosD=mysqli_fetch_array($ResultInfos);

$req=" 
	SELECT DateSession AS DateFin, Heure_Fin AS HEURE_FIN
	FROM form_session_date 
	LEFT JOIN form_session 
	ON form_session_date.Id_Session=form_session.Id
	WHERE form_session_date.Suppr=0
	AND form_session.Suppr=0
	AND form_session.Id=".$_GET['Id']." 
	ORDER BY DateSession DESC, Heure_Fin DESC
	";
$ResultInfos=mysqli_query($bdd,$req);
$RowInfosF=mysqli_fetch_array($ResultInfos);

$heures=" ";

if($RowSession['RECYCLAGE']==0){$laDuree=str_replace(".",":",$rowDuree['Duree']).$heures;}
else{$laDuree=str_replace(".",":",$rowDuree['DureeRecyclage']).$heures;}

$formulaire.='
<br><br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=250 height=20 style="font-size:14px;" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LIEU : '.$RowSession['LIEU'].'</b></td>
		<td width=100 height=20 style="font-size:14px;">&nbsp;&nbsp;<b>DUREE : '.$laDuree.'</b></td>
	</tr>
</table>';

$formulaire.='
<br><br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=150 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;">
			<table width="100%">
				<tr>
					<td style="font-size:14px;background-color:#c0c0c0;" align="right"><b>Date</b></td>
				</tr>
				<tr>
					<td style="font-size:14px;background-color:#c0c0c0;" align="left"><b>Nom Prénom</b></td>
				</tr>
			</table>
		</td>
		<td width=150 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>'.AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT']).'</b>
		</td>
		<td width=150 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;">
			<table width="100%">
				<tr>
					<td style="font-size:14px;background-color:#c0c0c0;" align="right"><b>Date</b></td>
				</tr>
				<tr>
					<td style="font-size:14px;background-color:#c0c0c0;" align="left"><b>Nom Prénom</b></td>
				</tr>
			</table>
		</td>
		<td width=130 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>'.AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT']).'</b>
		</td>
	</tr>
';


$formulaire.="<tr>";
$formulaire.='<td height=20 style="font-size:14px;border:1px solid black;" align="center">'.$RowSession['STAGIAIRE'].'</td>';
if($LangueAffichage=="FR"){$signatureElectronique = stripslashes($RowSession['STAGIAIRE'])."<br> \"Signature électronique\"";}
else{$signatureElectronique = stripslashes($RowSession['STAGIAIRE'])."<br> \"Electronic signature\"";}
		

$formulaire.='<td height=20 style="font-size:14px;border:1px solid black;" align="center">'.$signatureElectronique.'</td>
	<td height=20 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
				<td height=20 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>';

		
$formulaire.="</tr>";

$formulaire.='
</table>
';

$VISA='';
if($RowSession['FORMATEUR_NOMPRENOM']<>"")
{
	$VISA=$RowSession['FORMATEUR_NOMPRENOM'].'&nbsp;&nbsp;"Signature électronique"';
}

$formulaire.='
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellpadding=0 cellspacing=0 align=left>
		<tr>
			<td width=300 height=40 style="font-size:14px;border:1px solid black;" align="center">
				<table width="100%">
					<tr>
						<td style="font-size:14px;" align="left">&nbsp;&nbsp;&nbsp;<b>NOM DE L\'INTERVENANT :</b>&nbsp;&nbsp;&nbsp;'.$RowSession['FORMATEUR_NOMPRENOM'].'</td>
					</tr>
					<tr>
						<td style="font-size:14px;" align="left">&nbsp;&nbsp;&nbsp;<b>SOCIETE : </b>&nbsp;&nbsp;&nbsp;A.A.A</td>
					</tr>
				</table>
			</td>
			<td width=280 height=40 style="font-size:14px;border:1px solid black;">&nbsp;&nbsp;&nbsp;
				<b>VISA : </b>
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$VISA.'
			</td>
		</tr>
	</table>
';

$formulaire.='
</body>
';


$formulaire.='</html>';

//$dompdf->set_paper("a4", "landscape" ); 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

$canvas = $dompdf->get_canvas();
$font = 0;                  
$canvas->page_text(550, 770, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 10, array(0,0,0));
$canvas->page_text(200, 765, "DOCUMENT DIRECTION QUALITE AAA Group", $font, 10, array(0,0,0));
$canvas->page_text(190, 775, "Reproduction interdite sans autorisation Ã©crite de AAA Group", $font, 10, array(0,0,0));
$canvas->page_text(10, 765, "D-0725 Edition 1", $font, 10, array(0,0,0));
$canvas->page_text(10, 775, "01/09/2017", $font, 10, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();
?>
