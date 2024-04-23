<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

require_once '../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
	
	
$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
		(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
		(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
		EPP2Ans,EPPReprise,EPPRefuseSalarie,SouhaitEvolutionON,SouhaitEvolution,SouhaitMobiliteON,SouhaitMobilite,FormationEvolutionON,FormationEvolution,ComEvaluateurEPP,
		ComSalarie,ComEvaluateur,DateEvaluateur,DateSalarie,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPP'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);


$Plateforme="";
$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Plateforme=$RowPresta['Libelle'];
}

$Manager=stripslashes($rowEPERempli['Manager']);
$MatriculeAAAManager=$rowEPERempli['MatriculeAAAManager'];
$MetierManager=stripslashes($rowEPERempli['MetierManager']);

$Titre= "<img src='../../Images/FlecheBlancheGauche.png' width='15px' border='0' />ENTRETIEN PROFESSIONNEL PARCOURS  - E.P.P.<img width='15px' src='../../Images/FlecheBlancheDroite.png' border='0' /><br>Elaboration des projets professionnels du salarié / périodicité réglementaire : tous les 2 ans";

$EPP2Ans="<img src='../../Images/CaseNonCoche.png' width='10px' border='0' />";
if($rowEPERempli['EPP2Ans']==1){$EPP2Ans="<img src='../../Images/CaseCoche.png' width='10px' border='0' />";}
$EPPReprise="<img src='../../Images/CaseNonCoche.png' width='10px' border='0' />";
if($rowEPERempli['EPPReprise']==1){$EPPReprise="<img src='../../Images/CaseCoche.png' width='10px' border='0' />";}
$EPPRefuseSalarie="<img src='../../Images/CaseNonCoche.png' width='10px' border='0' />";
$signatureRefus="";
if($rowEPERempli['EPPRefuseSalarie']==1){$EPPRefuseSalarie="<img src='../../Images/CaseCoche.png' width='10px' border='0' />";$signatureRefus=$rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." <br>'signature électronique'";}


$req="SELECT DISTINCT Id_SouhaitMobilite, 
(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Mobilite 
FROM epe_personne_souhaitmobilite2 
WHERE Id_EPE=".$rowEPERempli['Id']." 
ORDER BY (SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite)";
$resultM=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultM);

$mobilite="";
if($nb>0){
	while($rowM=mysqli_fetch_array($resultM)){
		if($mobilite<>""){$mobilite.="<br>";}
		$mobilite.=$rowM['Mobilite'];
	}
}
if($rowEPERempli['SouhaitMobilite']<>""){
	$mobilite.="<br>";
}

$req="SELECT DISTINCT Id_SouhaitEvolution, 
(SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution) AS Evolution 
FROM epe_personne_souhaitevolution2 
WHERE Id_EPE=".$rowEPERempli['Id']." 
ORDER BY (SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution)";
$resultE=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultE);

$evolution="";
if($nb>0){
	while($rowE=mysqli_fetch_array($resultE)){
		if($evolution<>""){$evolution.="<br>";}
		$evolution.=$rowE['Evolution'];
	}
}
if($rowEPERempli['SouhaitEvolution']<>""){
	$evolution.="<br>";
}

$formulaire="
<html style='background-color:#ffffff;font-family:cursive;'>
	<head>
		<link type='text/css' href='../../CSS/FeuillePDF.css' rel='stylesheet' />
	<style>
		@font-face {
                font-family: 'Courier';           
                font-weight: normal;
                font-style: normal;
                src: url('Courier.afm') format('truetype');
        } 
		@page { margin: 110px 50px; }
		header {
                position: fixed; 
                top: -100 px; 
                left: 0px; 
                right: 0px;
                height: 400px; 
				padding-bottom: 200px;
            }
		footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                /** Extra personal styles **/
                text-align: center;
                line-height: 35px;
            }
		footer .pagenum:before {
			  content: counter(page);
		}
		footer .pagenum2:after {
			  content: counter(page);
		}
        </style>
	</head>
	<header>
		<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
			<tr>
				<td width='100px' rowspan='2'  style='border:1px solid black' align='center'><img width='100px' src='../../Images/Logos/AAA_Group.gif' border='0' /></td>
				<td width='400px' rowspan='2' height='40px' style='font:bold 14px;border:1px black solid;background-color:#002060;color:#ffffff;' align='center'>
					".$Titre."
				</td>
				<td width='100px'  style='border:1px solid black;font-size:12px;' align='center'>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td width='100px' height='20px' style='font:bold 14px;border:1px black solid;font-size:12px;' align='center'>&nbsp;&nbsp;&nbsp;</td>
			</tr>
		</table>
	</header>
";

$formulaire.="
<table width='100%'>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table style='border-spacing:0px;border:1px #000000 solid;padding:0px;' width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='20%' style='font-weight:bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Matricule</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['MatriculeAAA']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Date de l'entretien</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien'])."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Nom</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Nom']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Unité d'exploitation</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$Plateforme."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Prénom</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Prenom']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Evaluateur</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$Manager."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Fonction/métier</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Metier']."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Matricule</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$MatriculeAAAManager."</td>
						</tr>
						<tr>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Date d'ancienneté</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete'])."</td>
							<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Fonction /métier</td>
							<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$MetierManager."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			1.EPP- cadre de l'entretien
		</td>
	</tr>
	<tr>
		<td >
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td style='border:1px #000000 solid;'>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='50%' style='font-size:8px;height:15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$EPP2Ans."&nbsp;&nbsp;&nbsp;&nbsp;entretien périodique proposé tous les 2 ans : </td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td width='50%' style='font-size:8px;height:15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$EPPReprise."&nbsp;&nbsp;&nbsp;&nbsp;entretien proposé au salarié reprenant son activité (maladie, maternité, …)</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td style='height:20px;'></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td width='50%' style='font-size:8px;height:15px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$EPPRefuseSalarie."&nbsp;&nbsp;&nbsp;&nbsp;le salarié ne souhaite pas bénéficier de l'entretien professionnel proposé</td>
							<td bgcolor='#bfbfbf' style='border:1px #000000 solid;font-size:8px;' align='center'>signature du salarié :</td>
							<td style='font-size:8px;' align='center'>".$signatureRefus."</td>
						</tr>
						<tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	";

	$formulaire.= "
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			2 .EPP - Expression du collaborateur sur son parcours
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr bgcolor='#1a0078'>
							<td colspan='2' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Evolution souhaitée par le salarié dans son poste ou autre projet professionnel du salarié (les souhaits exprimés vont faire l'objet d'une étude à la DRH)</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:100px;' align='center'>Expression du souhait d'évolution professionnelle éventuel</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:100px;' align='center'>".stripslashes($evolution.$rowEPERempli['SouhaitEvolution'])."</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:100px;' align='center'>Expression du souhait de mobilité géographique nationale ou internationale éventuel (précisez la région ou le pays souhaité)</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:100px;' align='center'>".stripslashes($mobilite.$rowEPERempli['SouhaitMobilite'])."</td>
						</tr>
						<tr bgcolor='#1a0078'>
							<td colspan='2' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>
							Actions de formation évoquées<br>
							récapitulatif des actions et dispositifs envisageables sous réserve des priorités du plan de formation AAA, de l'éligibilité aux dispositifs de financement et des possibilités de réalisation.<br>
							NB : ce support ne contractualise en aucun cas ni un engagement de réalisation, ni une demande d'utilisation du CPF, mais constate formellement la tenue de l'entretien, ainsi que les souhaits qui auront pu y être exprimés
							</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:100px;' align='center'>Formations évoquées pour accompagner l'évolution professionnelle (transmission à la DRH pour avis)</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:100px;' align='center'>".stripslashes($rowEPERempli['FormationEvolution'])."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			3 .EPP - Commentaires évaluateur
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;height:100px;'>Commentaire de l'évaluateur sur le projet défini</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;height:100px;' colspan='3' align='center'>".stripslashes($rowEPERempli['ComEvaluateurEPP'])."</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
			4 .EPP- signature du bilan bisannuel
		</td>
	</tr>
	<tr>
		<td>
			<table width='100%' cellpadding='0' cellspacing='0'>
				<tr><td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Fait le :</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
								$formulaire.= AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']);
							$formulaire.= "</td>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Transmis à la DRH le :</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
								$formulaire.= AfficheDateJJ_MM_AAAA($rowEPERempli['DateEvaluateur']);
							$formulaire.= "</td>
						</tr>
						<tr>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Visa du collaborateur</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
							if($rowEPERempli['EPPRefuseSalarie']==0){
								$formulaire.= $rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." <br>'signature électronique'";
							}
							$formulaire.= "</td>
							<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Visa de l'évaluateur</td>
							<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
								$formulaire.= $Manager." <br>'signature électronique'";
							$formulaire.= "</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>";
?>

<?php 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

// add the header
$canvas = $dompdf->get_canvas();
$font = 0;  
// the same call as in my previous example
$canvas->page_text(550, 770, "{PAGE_NUM} / {PAGE_COUNT}", 0, 6, array(0,0,0));
$canvas->page_text(200, 765, "DOCUMENT DIRECTION QUALITE AAA GROUP", $font, 6, array(0,0,0));
$canvas->page_text(190, 775, "Reproduction interdite sans autorisation Ã©crite de AAA Group", $font, 6, array(0,0,0));
$canvas->page_text(10, 765, "D-0705/013 - Edition 2", $font, 6, array(0,0,0));
$canvas->page_text(10, 775, "15/03/2021", $font, 6, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();

?>