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
        form_session_personne_document.Id_SessionPersonneQualification
    FROM
        form_session_personne_document
    WHERE
        form_session_personne_document.Id=".$_GET['Id_Session_Personne_Document'];
$ResultFormSessionPersonneDoc2=mysqli_query($bdd,$ReqFormSessionPersonneDoc);
$RowFormSessionPersonneDoc2=mysqli_fetch_array($ResultFormSessionPersonneDoc2);

if($RowFormSessionPersonneDoc2['Id_SessionPersonneQualification']>0){
	$ReqFormSessionPersonneDoc="
    SELECT
        form_besoin.Id_Personne,
        form_session_personne_document.DateHeureRepondeur,
		form_session_personne_document.Id_Document,
		form_session_personne_document.Id_LangueDocument,
        0 AS Id_Session,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne_document.Id_Repondeur) AS Repondeur,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS Stagiaire
    FROM
        form_session_personne_document,
		form_session_personne_qualification,
		form_besoin
    WHERE
		form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
		AND form_session_personne_qualification.Id_Besoin=form_besoin.Id
		AND form_session_personne_document.Suppr=0
		AND form_session_personne_qualification.Suppr=0
        AND form_session_personne_document.Id=".$_GET['Id_Session_Personne_Document'];
	
	$ResultFormSessionPersonneDoc=mysqli_query($bdd,$ReqFormSessionPersonneDoc);
	$RowFormSessionPersonneDoc=mysqli_fetch_array($ResultFormSessionPersonneDoc);
	
	$req = "
	SELECT
		(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS STAGIAIRE,
		(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne_qualification.Id_Ouvreur) AS FORMATEUR,
		form_session_personne_qualification.Lieu AS LIEU,
		(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) AS ID_PLATEFORME,
		form_besoin.Id_Formation AS ID_FORMATION,
		IF(form_besoin.Motif='Renouvellement',1,0) AS RECYCLAGE,
		(SELECT Id_TypeFormation FROM form_formation WHERE Id=form_besoin.Id_Formation) AS ID_TYPEFORMATION,
		(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
		FROM form_formation_langue_infos
		WHERE Id_Formation=form_besoin.Id_Formation
		AND Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
			AND Id_Formation=form_besoin.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0) AS Formation,
		(SELECT IF(form_besoin.Motif='Renouvellement',DureeRecyclage,Duree)
		FROM form_formation_plateforme_parametres
		WHERE Id_Formation=form_besoin.Id_Formation
		AND Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
		AND form_formation_plateforme_parametres.Suppr=0 
		LIMIT 1) AS Duree,
		LEFT(form_session_personne_qualification.DateHeureRepondeur,10) AS DateSession
	FROM
		form_session_personne_qualification
	LEFT JOIN form_besoin
	ON form_session_personne_qualification.Id_Besoin=form_besoin.Id
	WHERE
		form_session_personne_qualification.Id=".$RowFormSessionPersonneDoc2['Id_SessionPersonneQualification']."";
	$ResultSession=mysqli_query($bdd,$req);
	$RowSession=mysqli_fetch_array($ResultSession);
}
else{
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
	
	$ResultSession=get_session($RowFormSessionPersonneDoc['Id_Session']);
	$RowSession=mysqli_fetch_array($ResultSession);
}


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

$Logo_Plateforme="";
$Libelle_Plateforme="";
$Requete_Logo="SELECT Libelle,Logo FROM new_competences_plateforme WHERE Id=".$RowSession['ID_PLATEFORME'];
$Result_Logo=mysqli_query($bdd,$Requete_Logo);
$Nb_Result_Logo=mysqli_num_rows($Result_Logo);
if($Nb_Result_Logo>0)
{
    $Row_Logo=mysqli_fetch_array($Result_Logo);
    $Logo_Plateforme=$Row_Logo["Logo"];
    $Libelle_Plateforme=$Row_Logo["Libelle"];
}


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
		<td width=113 style="border:1px solid black" align="center" rowspan="2"><img width=113 src="../../../Images/Logos/Logo Daher_posi.png"></td>
		<td width=350 height=56 align="center" style="border:1px solid black;background-color:#e7e6e6;color:#000080;font-size:20px;" rowspan="2">
			<img src="../../../Images/FlecheGauche.png">
			<b>&nbsp;&nbsp;&nbsp;FICHE D\'EVALUATION</b>&nbsp;&nbsp;&nbsp;<img src="../../../Images/FlecheDroite.png"><br><b>FORMATION&nbsp;&nbsp;</b>
			
		</td>
		<td width=113 style="border:1px solid black" align="center"></td>
	</tr>
	<tr>
		<td width=113 height="15" style="border:1px solid black" align="center"><b>'.$Libelle_Plateforme.'</b></td>
	</tr>
</table>

<br><br><br><br><br><br>
';

$Doc_Q_R_RStagiaires=Generer_Document($RowDoc_Langue['Id'], $_GET['Id_Session_Personne_Document'], true);

$ReqLangue="
    SELECT
        Id_Langue
	FROM
        form_formation_plateforme_parametres
	WHERE
        Suppr=0
        AND Id_Plateforme=".$RowSession['ID_PLATEFORME']."
        AND Id_Formation=".$RowSession['ID_FORMATION'];
$ResultLangue=mysqli_query($bdd,$ReqLangue);
$RowLangue=mysqli_fetch_array($ResultLangue);

$ReqLibelle="
    SELECT
        Libelle,
        LibelleRecyclage
	FROM
        form_formation_langue_infos
	WHERE
        Suppr=0
        AND Id_Langue=".$RowLangue['Id_Langue']."
        AND Id_Formation=".$RowSession['ID_FORMATION'];
$ResultLibelle=mysqli_query($bdd,$ReqLibelle);
$RowLibelle=mysqli_fetch_array($ResultLibelle);

if($RowSession['RECYCLAGE']==0){$Intitule=stripslashes($RowLibelle['Libelle']);}
else{$Intitule=stripslashes($RowLibelle['LibelleRecyclage']);}
$Lieu=stripslashes($RowSession['LIEU']);
$DateHeure=$RowFormSessionPersonneDoc['DateHeureRepondeur'];

$interne='<img width=15 height=15 src="../../../Images/checkednot.png">';
$externe='<img width=15 height=15 src="../../../Images/checkednot.png">';

if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationInterne)
{
	$interne='<img width=15 height=15 src="../../../Images/checked.png">';
}
else{
	$externe='<img width=15 height=15 src="../../../Images/checked.png">';
}
$Stagiaire=stripslashes($RowFormSessionPersonneDoc['Stagiaire']);

$formulaire.='
<br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=570 height=20 style="font-size:14px;border:1px solid black;">
			<table width="100%">
				<tr>
					<td height=20 style="font-size:14px;">INTITULE DE LA FORMATION : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$Intitule.'</td>
				</tr>
				<tr>
					<td height=20 style="font-size:14px;">LIEU DE LA FORMATION : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$Lieu.'</td>
				</tr>
				<tr>
					<td height=20 style="font-size:14px;">DATE(S) : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$DateHeure.'
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					'.$interne.'&nbsp;&nbsp;INTERNE 
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					'.$externe.'&nbsp;&nbsp;EXTERNE
					</td>
				</tr>
				<tr>
					<td height=20 style="font-size:14px;">NOM DU PARTICIPANT: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$Stagiaire.'</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br><br><br><br><br><br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=570 height=20 style="font-size:14px;" align="center">
			<i>Exprimez votre niveau de satisfaction en mettant une croix dans la case de votre choix<br>
			(de 1 "pas satisfaisant" à 6 "très satisfaisant")
			</i>
		</td>
	</tr>
</table>
<br><br><br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=150 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>NIVEAU DE SATISFACTION</b>
		</td>
		<td width=30 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>1</b>
		</td>
		<td width=30 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>2</b>
		</td>
		<td width=30 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>3</b>
		</td>
		<td width=30 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>4</b>
		</td>
		<td width=30 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>5</b>
		</td>
		<td width=30 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>6</b>
		</td>
		<td width=250 height=20 style="font-size:14px;background-color:#c0c0c0;border:1px solid black;" align="center">
			<b>OBSERVATIONS</b>
		</td>
	</tr>
';

$question="";
$oui='<img width=15 height=15 src="../../../Images/checkednot.png">';
$non='<img width=15 height=15 src="../../../Images/checkednot.png">';
$Observation="";
foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
{
    if($Ligne_Q_R_RStagiaires[3]=="Note (1 à 6)")
    {
		$formulaire.='<tr>
				<td width=150 height=20 style="font-size:14px;border:1px solid black;" align="center">
					'.$Ligne_Q_R_RStagiaires[1].'
				</td>
				<td width=30 height=20 style="font-size:14px;border:1px solid black;" align="center">
				';
		if($Ligne_Q_R_RStagiaires[4]=="1"){$formulaire.='X';}
		else{$formulaire.='&nbsp;';}				
		$formulaire.='
				</td>
				<td width=30 height=20 style="font-size:14px;border:1px solid black;" align="center">
				';
		if($Ligne_Q_R_RStagiaires[4]=="2"){$formulaire.='X';}
		else{$formulaire.='&nbsp;';}	
		$formulaire.='
				</td>
				<td width=30 height=20 style="font-size:14px;border:1px solid black;" align="center">
				';
		if($Ligne_Q_R_RStagiaires[4]=="3"){$formulaire.='X';}
		else{$formulaire.='&nbsp;';}	
		$formulaire.='
				</td>
				<td width=30 height=20 style="font-size:14px;border:1px solid black;" align="center">
				';
		if($Ligne_Q_R_RStagiaires[4]=="4"){$formulaire.='X';}
		else{$formulaire.='&nbsp;';}	
		$formulaire.='
				</td>
				<td width=30 height=20 style="font-size:14px;border:1px solid black;" align="center">
				';
		if($Ligne_Q_R_RStagiaires[4]=="5"){$formulaire.='X';}
		else{$formulaire.='&nbsp;';}	
		$formulaire.='
				</td>
				<td width=30 height=20 style="font-size:14px;border:1px solid black;" align="center">
				';
		if($Ligne_Q_R_RStagiaires[4]=="6"){$formulaire.='X';}
		else{$formulaire.='&nbsp;';}	
		$formulaire.='
				</td>
				<td width=250 height=20 style="font-size:14px;border:1px solid black;" align="center">
					'.stripslashes($Ligne_Q_R_RStagiaires[5]).'
				</td>
			</tr>';
    }
	elseif($Ligne_Q_R_RStagiaires[3]=="Oui/Non")
    {
        $question=$Ligne_Q_R_RStagiaires[1];
        if($Ligne_Q_R_RStagiaires[4]==1){$oui='<img width=15 height=15 src="../../../Images/checked.png">';}
        else{$non='<img width=15 height=15 src="../../../Images/checked.png">';}
    }
	elseif($Ligne_Q_R_RStagiaires[3]=="Texte facultatif" || $Ligne_Q_R_RStagiaires[3]=="Texte obligatoire")
    {
        $Observation=$Ligne_Q_R_RStagiaires[5];
    }
}

$formulaire.='
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=300 height=20 style="font-size:14px;" align="center">
			'.$question.'
		</td>
		<td width=50 height=20 style="font-size:14px;">
			'.$oui.' Oui <br>
			'.$non.' Non
		</td>
	</tr>
</table>
<br><br>
<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=570 height=100 style="font-size:14px;border:1px solid black;" valign="top">
			<table width="100%" valign="top">
				<tr>
					<td height=20 style="font-size:14px;"><b>OBSERVATIONS GENERALES</b></td>
				</tr>
				<tr>
					<td height=20 style="font-size:14px;" align="center">'.$Observation.'</td>
				</tr>
			</table>
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
$canvas->page_text(10, 765, "D-0727 EDITION nÂ°1", $font, 10, array(0,0,0));
$canvas->page_text(10, 775, "01/09/2017", $font, 10, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();

?>
