<?php
session_start();
require("../../ConnexioniSansBody.php");
require_once("../../Fonctions.php");
require_once("../Globales_Fonctions.php");

require_once '../../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$ReqFormSessionPersonneDoc="
    SELECT
        form_session_personne.Id_Personne,
        form_session_personne_document.DateHeureRepondeur,
		form_session_personne_document.Id_Document,
		form_session_personne_document.Id_LangueDocument,
        form_session_personne.Id_Session,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Stagiaire
    FROM
        form_session_personne_document
    LEFT JOIN form_session_personne
        ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
    WHERE
        form_session_personne_document.Id=".$_GET['Id_Session_Personne_Document'];
$ResultFormSessionPersonneDoc=mysqli_query($bdd,$ReqFormSessionPersonneDoc);
$RowFormSessionPersonneDoc=mysqli_fetch_array($ResultFormSessionPersonneDoc);

$ReqDoc_Langue="
	SELECT
		Id,
		Id_Document,
		Id_Langue,
		Libelle
	FROM
		form_document_langue
	WHERE
		Suppr=0
		AND Id_Langue=".$RowFormSessionPersonneDoc['Id_LangueDocument']."
		AND Id_Document=".$RowFormSessionPersonneDoc['Id_Document'];
$ResultDoc_Langue=mysqli_query($bdd,$ReqDoc_Langue);
$RowDoc_Langue=mysqli_fetch_array($ResultDoc_Langue);

$ResultSession=get_session($RowFormSessionPersonneDoc['Id_Session']);
$RowSession=mysqli_fetch_array($ResultSession);

$Doc_Q_R_RStagiaires=Generer_Document($RowDoc_Langue['Id'], $_GET['Id_Session_Personne_Document'], true);

$visa="";
foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
{
    if($Ligne_Q_R_RStagiaires[4]==1)
    {
        if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){$visa="'Signature électronique'<br>".stripslashes($RowFormSessionPersonneDoc['Stagiaire']);}
        else{$visa="'Electronical signature'<br>".stripslashes($RowFormSessionPersonneDoc['Stagiaire']);}
    }
}

$formulaire='<html>
 <head>
    	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
		<link rel=Stylesheet href=Individual_Chart/stylesheet.css>
		<style>
			html{margin:40px 20px}
		</style>
    </head>
';

$formulaire.='<body style="background-color:#ffffff">

<br><br><br><br><br>
';
$formulaire.='<span style="position:absolute;z-index:3;margin-left:36px;margin-top:2px;width:550;height:85px"><img width="550" height="85" src="Individual_Chart/titre.png" ></span>
<table border=0 cellpadding=0 cellspacing=0 width=550 style="border-collapse:collapse;table-layout:fixed;">
	<tr height=50>
		<td colspan=9 height=50 class=xl75 width=550 style="border:1px black solid;background-color:#bfbfbf;" align="center"><b>CHARTE INDIVIDUELLE<br>DES METIERS DE L\'AERONAUTIQUE</b></td>
	</tr>
	<tr height=45>
	  <td colspan=8 height=45 class=xl74 width=600><b>En qualité de professionnel de l’aéronautique, je m’engage à appliquer et à faire respecter les règles fondamentales suivantes :</b></td>
	  <td class=xl65>&nbsp;</td>
	</tr>
	<tr>
	  <td height=50 class=xl66 width=10>1.</td>
	  <td colspan=6 class=xl71 width=600 ><font class="font7">CONNAITRE LES REGLES</font><font class="font0">, 
		les processus et les procéduresapplicables à la plateforme / site d’origine A.A.A. et client (consignes de sécurité,note
		d’organisation, procédures, directives, ...).</font></td>
	 </tr>
	 <tr>
		  <td height=50 class=xl66>2.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">
			PRENDRE SOIN DE MA SECURITE</font><font class="font0"> et de ma santé ainsi que de celles des autres personnes concernées du fait de mes actes (protection, balisage, alertes,...).</font></td>
	 </tr>
	 <tr>
		  <td height=50 class=xl66>3.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">SAVOIR
		  LIRE, COMPRENDRE ET UTILISER LA DOCUMENTATION</font><font class="font0"> de
		  travail appropriée (référentiels, gammes, plans, fiches techniques, manuel de maintenance, ...).</font></td>
	 </tr>
	 <tr>
		  <td height=40 class=xl66>4.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">LOCALISER
		  CORRECTEMENT ET PROTEGER LA ZONE</font><font class="font0"> de travail et
		  les éléments d’aéronefs (montés ou en attente de montage).</font></td>
	 </tr>
	 <tr>
		  <td height=40 class=xl66>5.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">M’ASSURER
		  D’AVOIR LES CONNAISSANCES</font><font class="font0"> et les qualifications
		  nécessaires pour réaliser le travail que je dois exécuter.</font></td>
	 </tr>
	 <tr>
		  <td height=40 class=xl66>6.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">UTILISER
		  DE L’OUTILLAGE</font><font class="font0">, du matériel, des produits et des
		  moyens de contrôle, de manutention et de transport adaptés, conformes et validés.</font></td>
	 </tr>
	 <tr>
		  <td height=55 class=xl66>7.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">GARANTIR
		  LA TRAÇABILITE DE MON TRAVAIL</font><font class="font0"> en renseignant et en
		  visant la documentation appropriée (gammes, fiches suiveuses, étiquettes, fiche d’intervention, fiches de non conformité ...).</font></td>
	 </tr>
	 <tr>
		  <td height=30 class=xl66>8.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">VERIFIER
		  ET REMETTRE EN PLACE</font><font class="font0"> l’ensemble des moyens
		  utilisés.</font></td>
	 </tr>
	 <tr>
		  <td height=55 class=xl66>9.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">SIGNALER
		  À MA HIERARCHIE</font><font class="font0"> et aux services compétents toute
		  anomalie produite ou constatée (malfaçon, difficultés de réalisation,
		  d’approvisionnement, de formation, ...).</font></td>
	 </tr>
	 <tr>
		  <td height=35 class=xl66>10.</td>
		  <td colspan=6 class=xl71 width=600 ><font class="font7">MAINTENIR
		  PROPRE</font><font class="font0"> et ordonnée la zone de travail pendant et
		  après l’exécution de la tâche confiée. Protéger les zones sensibles avant toute intervention.</font></td>
	 </tr>
</table>
<table border=0 cellpadding=0 cellspacing=0 width=500>
	 <tr>
		  <td width=20 valign=center>&nbsp; Nom :</td>
		  <td width= 150 valign=center>'.stripslashes($RowFormSessionPersonneDoc['Stagiaire']).'</td>
		  <td width=50 valign=center>&nbsp;Date :</td>
		  <td width=70 valign=center>'.AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneDoc['DateHeureRepondeur'],0,10)).'</td>
		  <td width=50 valign=center>&nbsp;Visa :</td>
		  <td width=70>'.$visa.'</td>
	</tr>
</table>
<br><br>
<table border=0 cellpadding=0 cellspacing=0 width=500>
	 <tr>
		<td><img width="56" height="59" src="Individual_Chart/afao2.png" ></td>
		  <td colspan=5 class=xl69 width=461>Siège Social : 10, rue
		  Mercœur - 75011 Paris - Tél. 33 (0)1 48 06 85 85 - Fax. 33 (0)1 48 06 32
		  19<br>
			Société par Actions Simplifiée au capital de 1.600.000 Euros<br>
			RCS Paris B 353 522 204 - N° Siret 353 522 204 00059 - Code NAF 3030 Z -
		  TVA FR52 353 522 204
		  </td>
		 <td><img width="54" height="59" src="Individual_Chart/afao1.png" ></td>
	</tr>
';
 
 
 $formulaire.='</table>';
/*
$sheet->setCellValue('C14',utf8_encode(stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));
$sheet->setCellValue('E14',utf8_encode($RowFormSessionPersonneDoc['DateHeureRepondeur']));

foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
{
    if($Ligne_Q_R_RStagiaires[4]==1)
    {
        if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){$sheet->setCellValue('G14',utf8_encode("'Signature électronique'\n".stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));}
        else{$sheet->setCellValue('G14',utf8_encode("'Electronical signature'\n".stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));}
    }
}*/

$formulaire.='
</body>
';
$formulaire.='</html>';
//$dompdf->set_paper("a4", "landscape" ); 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
?>