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

$formulaire="";

$ReqRelation="
	SELECT
		Id_Qualification_Parrainage,DateSensibilisation,Id_Formation,Lieu,Duree,
		(SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) AS Id_TypeFormation,
		(SELECT Reference FROM form_formation WHERE Id=Id_Formation) AS Reference,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Sensibilisation) AS Formateur,
		(SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Prestation,
		(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Plateforme,
		Id_Personne
	FROM
		new_competences_relation
	WHERE
	
		new_competences_relation.Id = ".$_GET['Id'];
$ResultRelation=mysqli_query($bdd, $ReqRelation);
$RowRelation=mysqli_fetch_array($ResultRelation);

//Plateforme
$req="SELECT Libelle,Logo FROM new_competences_plateforme WHERE Id=".$RowRelation['Id_Plateforme'];
$result=mysqli_query($bdd,$req);
$rowPlat=mysqli_fetch_array($result);

//INFORMATION SUR LA FORMATION 
//INTITULE
$req="SELECT Id_Langue 
	FROM form_formation_plateforme_parametres 
	WHERE Suppr=0 AND Id_Plateforme=".$RowRelation['Id_Plateforme']." AND Id_Formation=".$RowRelation['Id_Formation'];
$result=mysqli_query($bdd,$req);
$rowLangue=mysqli_fetch_array($result);

$req="SELECT Libelle, LibelleRecyclage 
	FROM form_formation_langue_infos 
	WHERE Suppr=0 AND Id_Langue=".$rowLangue['Id_Langue']." AND Id_Formation=".$RowRelation['Id_Formation'];
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
		<td width=150 height=20 style="color:#000080;;font-size:17px;" align="center"><img width=15 height=15 src="../../Images/checkednot.png">&nbsp;&nbsp;<b>FORMATION</b></td>
		<td width=180 height=20 style="color:#000080;;font-size:17px;" align="center"><img width=15 height=15 src="../../Images/checkednot.png">&nbsp;&nbsp;<b>INFORMATION</b></td>
		<td width=250 height=20 style="color:#000080;;font-size:17px;" align="center"><img width=15 height=15 src="../../Images/checked.png">&nbsp;&nbsp;<b>SENSIBILISATION</b></td>
	</tr>
</table>

<br><br>';

$Libelle=stripslashes($rowLibelle['Libelle']);

$interne='<img width=15 height=15 src="../../Images/checkednot.png">';
$externe='<img width=15 height=15 src="../../Images/checkednot.png">';

if($RowRelation['Id_TypeFormation']==$IdTypeFormationInterne)
{
	$interne='<img width=15 height=15 src="../../Images/checked.png">';
}
else{
	$externe='<img width=15 height=15 src="../../Images/checked.png">';
}

$formulaire.='

<table cellpadding=0 cellspacing=0 align=left>
	<tr>
		<td width=100 height=20 style="font-size:14px;" align="center" colspan="2">&nbsp;&nbsp;<b>INTITULE : '.$Libelle.'</b></td>
	</tr>
	<tr>
		<td width=100 height=20 style="font-size:14px;" align="center"></td>
		<td width=300 height=20 style="font-size:14px;" align="center">&nbsp;&nbsp;<b>N° REF : '.stripslashes($RowRelation['Reference']).'</b>&nbsp;&nbsp;&nbsp;&nbsp;'.$interne.'&nbsp;<b>INTERNE</b>&nbsp;&nbsp;'.$externe.'&nbsp;<b>EXTERNE</b>&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td width=100 height=20 style="font-size:14px;" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LIEU : '.stripslashes($RowRelation['Lieu']).'</b></td>
		<td width=300 height=20 style="font-size:14px;">&nbsp;&nbsp;<b>DUREE : '.stripslashes($RowRelation['Duree']).'</b></td>
	</tr>
</table>
<br><br>
';

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
			<b>'.AfficheDateJJ_MM_AAAA($RowRelation['DateSensibilisation']).'</b>
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
		</td>
	</tr>
';


$formulaire.="<tr>";
	$formulaire.='<td height=20 style="font-size:14px;border:1px solid black;" align="center">'.$RowRelation['Personne'].'</td>';
	if($LangueAffichage=="FR"){$signatureElectronique = stripslashes($RowRelation['Personne'])."<br> \"Signature électronique\"";}
	else{$signatureElectronique = stripslashes($RowRelation['Personne'])."<br> \"Electronic signature\"";}
	$formulaire.='<td height=20 style="font-size:14px;border:1px solid black;" align="center">'.$signatureElectronique.'</td>
				<td height=40 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
				<td height=40 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
	';
$formulaire.="</tr>";

for($i=0;$i<10;$i++){
	$formulaire.='
	<tr>
		<td height=40 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
		<td height=40 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
		<td height=40 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
		<td height=40 style="font-size:14px;border:1px solid black;" align="center">&nbsp;</td>
	</tr>
	';
}

$formulaire.='
</table>
';

$VISA='';
if($RowRelation['Formateur']<>"")
{
	$VISA=$RowRelation['Formateur'].'&nbsp;&nbsp;"Signature électronique"';
}

$formulaire.='
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table cellpadding=0 cellspacing=0 align=left>
		<tr>
			<td width=300 height=40 style="font-size:14px;border:1px solid black;" align="center">
				<table width="100%">
					<tr>
						<td style="font-size:14px;" align="left">&nbsp;&nbsp;&nbsp;<b>NOM DE L\'INTERVENANT :</b>&nbsp;&nbsp;&nbsp;'.$RowRelation['Formateur'].'</td>
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

echo $formulaire;
/*
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
*/
?>
