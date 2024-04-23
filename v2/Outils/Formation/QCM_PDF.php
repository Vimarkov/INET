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

$formulaire="<html>
    <head>
    	<link href='../../CSS/Feuille.css' rel='stylesheet' type='text/css'>
		<style>
			html{margin:40px 20px}
		</style>
    </head>
";
$rouge="#fd7b7b";
$vert="#87db6b";

//Vérification si droit d'accès au QCM
//et QCM Ouvert et si QCM répondu
//------------------------------------
$QCM_Ouvert=false;
$QCM_Acces_OK=false;
$QCM_Repondu=false;

$ReqTypePassage="
	SELECT
		form_session_personne_qualification.TypePassageQCM
	FROM
		form_session_personne_qualification
	WHERE
		form_session_personne_qualification.Id=".$_GET['Id_Session_Personne_Qualification'];
$ResultTypePassage=mysqli_query($bdd,$ReqTypePassage);
$RowTypePassage=mysqli_fetch_array($ResultTypePassage);
if($RowTypePassage['TypePassageQCM']==0)
{
	$ReqFormSessionPersonneQualification="
		SELECT
			form_session_personne.Id_Personne,
			form_session_personne_qualification.DateHeureRepondeur,
			form_session_personne_qualification.Id_QCM,
			form_session_personne_qualification.Id_LangueQCM,
			form_session_personne_qualification.Id_QCM_Lie,
			form_session_personne_qualification.Id_LangueQCMLie,
			Id_Repondeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Stagiaire
			
		FROM
			form_session_personne_qualification
		LEFT JOIN form_session_personne
			ON form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
		WHERE
			form_session_personne_qualification.Id=".$_GET['Id_Session_Personne_Qualification'];
}
elseif($RowTypePassage['TypePassageQCM']==1)
{
	$ReqFormSessionPersonneQualification="
		SELECT
			form_besoin.Id_Personne,
			form_session_personne_qualification.DateHeureRepondeur,
			form_session_personne_qualification.Id_QCM,
			form_session_personne_qualification.Id_LangueQCM,
			form_session_personne_qualification.Id_QCM_Lie,
			form_session_personne_qualification.Id_LangueQCMLie,
			Id_Repondeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS Stagiaire
		FROM
			form_session_personne_qualification
		LEFT JOIN form_besoin
			ON form_session_personne_qualification.Id_Besoin=form_besoin.Id
		WHERE
			form_session_personne_qualification.Id=".$_GET['Id_Session_Personne_Qualification'];
}
elseif($RowTypePassage['TypePassageQCM']==2)
{
	$ReqFormSessionPersonneQualification="
		SELECT
			new_competences_relation.Id_Personne,
			form_session_personne_qualification.DateHeureRepondeur,
			form_session_personne_qualification.Id_QCM,
			form_session_personne_qualification.Id_LangueQCM,
			form_session_personne_qualification.Id_QCM_Lie,
			form_session_personne_qualification.Id_LangueQCMLie,
			Id_Repondeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_relation.Id_Personne) AS Stagiaire
		FROM
			form_session_personne_qualification
		LEFT JOIN new_competences_relation
			ON form_session_personne_qualification.Id_Relation=new_competences_relation.Id
		WHERE
			form_session_personne_qualification.Id=".$_GET['Id_Session_Personne_Qualification'];
}
$ResultFormSessionPersonneQualification=mysqli_query($bdd,$ReqFormSessionPersonneQualification);
$RowFormSessionPersonneQualification=mysqli_fetch_array($ResultFormSessionPersonneQualification);
if($RowFormSessionPersonneQualification['DateHeureRepondeur']>0){$QCM_Repondu=true;}

//------------------------------------
//Les AF, RF, PS et RQP n'ont pas besoin d'ouvrir le QCM pour y accéder
if(($QCM_Ouvert || $QCM_Repondu || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || (DroitsFormationPrestation($TableauIdPostesCQ) && $RowTypePassage['TypePassageQCM']>0)))
{
	$tabQCM=array();
	$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM']."_".$RowFormSessionPersonneQualification['Id_LangueQCM'];

	$Page="";
	if(isset($_GET['Page'])){$Page=$_GET['Page'];}
	$nb=0;
	$sommeCoeff=0;
	$sommeNote=0;
	foreach($tabQCM AS $QCM)
	{
		$nb++;
		$tabLeQCM = explode("_",$QCM);
		$sommeCoeffQCM=0;
		$sommeNoteQCM=0;
		$ReqQCM_Langue="
			SELECT
				Id,
				Id_QCM,
				Id_Langue,
				Libelle,
				Date_MAJ,
				Id_Personne_MAJ,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) AS Personne
			FROM
				form_qcm_langue
			WHERE
				Suppr=0
				AND Id_Langue=".$tabLeQCM[1]." 
				AND Id_QCM=".$tabLeQCM[0];
		$ResultQCM_Langue=mysqli_query($bdd,$ReqQCM_Langue);
		$RowQCM_Langue=mysqli_fetch_array($ResultQCM_Langue);
		
		$ReqQCM="
			SELECT
				Id,
				Code,
				(SELECT Libelle FROM form_client WHERE form_client.Id=form_qcm.Id_Client) AS Client,
				Nb_Question,
				Id_QCM_Lie,
				Fichier
			FROM
				form_qcm
			WHERE
				Id=".$RowQCM_Langue['Id_QCM'];
		$ResultQCM=mysqli_query($bdd,$ReqQCM);
		$RowQCM=mysqli_fetch_array($ResultQCM);
		
		$titre="QCM: ".stripslashes($RowQCM_Langue['Libelle']);
		$explication1="Cocher la (les) bonne(s) réponse(s). Il peut y avoir 1, 2 ou 3 bonnes réponses dans la colonne \"Réponse\".
						<b>Attention, le coefficient varie selon l'importance des questions.</b>";
		$explication2="Si une réponse est cochée alors qu'elle n'aurait pas dû l'être, cela engendre la perte totale des points pour la question concernée.
						1 bonne réponse / 3 = 0,33 point - 1 bonne réponse / 2 = 0,5 point - 2 bonnes réponses / 3 = 0,66 point";
		if($RowFormSessionPersonneQualification['Id_LangueQCM']<>1)
		{
			$titre="MCQ: ".$RowQCM_Langue['Libelle'];
			$explication1="Tick the relevant answer(s). There can be 1, 2 or 3 correct answers in the \"Answer\" column.
							<b>Caution, the coefficient of the answers varies depending on the questions importance.</b>";
			$explication2="If an answer is ticked when it shouldn't be, it will result in the total lost of the points for the concerned question.
							1 good answer / 3 = 0.33 point - 1 good answer / 2 = 0.5 point - 2 good answers / 3 = 0.66 point";
		}
		$formulaire.= "<body style='background-color:#ffffff;'>
		<table bgcolor='#ffffff' border='1' style='width:100%; border-spacing:0;'>";
					
		$formulaire.= "<tr>
			<td colspan='2' rowspan='3'><img src='../../Images/Logos/Logo_Doc_Group.png'></td>
			<td colspan='3' align='center' style='font-size:25px;font-weight:bold;'>".$titre."</td>
			<td colspan='5'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='3' align='center' style='color:#162bdd;border-color:#000000;'>".$explication1."</td>
			<td colspan='2' align='center' style='font-size:15px;font-weight:bold;'>CODE</td>
			<td colspan='3' align='center' style='font-size:15px;font-weight:bold;'>".$RowQCM['Code']."</td>
		</tr>
		<tr>
			<td colspan='3' align='center' style='color:#162bdd;border-color:#000000;'>".$explication2."</td>";
		if($RowQCM['Client']<>"")
		{
			$formulaire.= "
				<td colspan='2' align='center'>CLIENT</td>
				<td colspan='3' align='center'>".$RowQCM['Client']."</td>";
		}
		else
		{
			$formulaire.= "<td colspan='5'>&nbsp;</td>";
		}
		$formulaire.= "</tr><tr>";
		if($RowFormSessionPersonneQualification['Id_LangueQCM']==1)
		{
			$formulaire.= "
				<td colspan='2' style='font-weight:bold;'>Mis à jour le ".AfficheDateJJ_MM_AAAA($RowQCM_Langue['Date_MAJ'])."</td>
				<td colspan='3'>&nbsp;</td>
				<td colspan='5' style='font-weight:bold;' align='right'>Par ".$RowQCM_Langue['Personne']."&nbsp;&nbsp;</td>";
		}
		else
		{
			$formulaire.= "
				<td colspan='2'>Updated the ".AfficheDateFR($RowQCM_Langue['Date_MAJ'])."</td>
				<td colspan='3'>&nbsp;</td>
				<td colspan='5' align='right'>By ".$RowQCM_Langue['Personne']."</td>";
		}
		$formulaire.= "</tr>";
		$formulaire.= "</table>";
		$formulaire.="<br>";
		$formulaire.= "<table bgcolor='#ffffff' border='1' style='width:100%; border-spacing:0;'>";
		$formulaire.= "<tr bgcolor='#fff800' style='font-size:20px;font-weight:bold;'>";
		if($RowFormSessionPersonneQualification['Id_LangueQCM']==1)
		{
			$formulaire.= "
				<td width='3%' align='center'>N°</td>
				<td width='35%' align='center' colspan='2'>Question</td>
				<td width='35%' align='center'>Choix</td>
				<td width='5%' align='center'>Réponse</td>";
			if($QCM_Repondu){
				$formulaire.= "<td width='5%' align='center'>Résultat</td>";
			}
		}
		else
		{
			$formulaire.= "
				<td width='3%' align='center'>No</td>
				<td width='35%' align='center' colspan='2'>Question</td>
				<td width='35%' align='center'>Choice</td>
				<td width='5%' align='center'>Reply</td>";
			if($QCM_Repondu){
				$formulaire.= "<td width='5%' align='center'>Result</td>";
			}
		}
		if($QCM_Repondu){
		$formulaire.= "<td  width='5%' align='center'>Note</td>";
		$formulaire.= "<td width='5%' align='center' bgcolor='#c0c0c0' style='font-size:9px;'>Coefficient</td>";
		$formulaire.= "<td width='7%' colspan='2' align='center'>Total</td>";
		}
		$formulaire.= "</tr>";
			
		$formulaire.= "</table>";
		$formulaire.= "<table bgcolor='#ffffff' border='1' style='width:100%; border-spacing:0;'>";
		
		$assistant=0;
		if($Page=="Gestion_SessionFormation"){$assistant=1;}
		$QCM_Q_R_RStagiaires=Generer_QCM($RowQCM_Langue['Id'], $_GET['Id_Session_Personne_Qualification'], $QCM_Repondu,$assistant);
		
		$QuestionPrecedente="";
		$num=1;
		$couleur="#ffffff";
		
		foreach($QCM_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
		{
			//Réponses
			//--------
			$ReponseDebut="";
			$ImageReponse="";
			if($Ligne_Q_R_RStagiaires[7]<>"")
			{
				if(file_exists ('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$RowQCM_Langue['Id'].'/'.$Ligne_Q_R_RStagiaires[7]))
				{
					$ImageReponse="<img src='Docs/QCM/".$RowQCM_Langue['Id_QCM']."/".$RowQCM_Langue['Id']."/".$Ligne_Q_R_RStagiaires[7]."' height='60px'>";
				}
			}
			$ReponseDebut="<td width='35%' valign='middle'>".$Ligne_Q_R_RStagiaires[6]."<br>".$ImageReponse."</td>";
			if($QCM_Repondu)
			{
				$Nb_BonnesReponses=$Ligne_Q_R_RStagiaires[12];
				$Nb_ReponsesCorrects=$Ligne_Q_R_RStagiaires[13];
				$Nb_ReponsesFausses=$Ligne_Q_R_RStagiaires[14];
				
				$couleur2="";
				if($Ligne_Q_R_RStagiaires[8]==$Ligne_Q_R_RStagiaires[9] && $Ligne_Q_R_RStagiaires[9]==1){$NoteReponse=round(1/$Nb_BonnesReponses,2);$couleur2="bgcolor='".$vert."'";}
				else{$NoteReponse=0;}
				if($Nb_ReponsesFausses>0){$NoteReponse=0;}
				if($Ligne_Q_R_RStagiaires[8]<>$Ligne_Q_R_RStagiaires[9] && $Ligne_Q_R_RStagiaires[9]==0){$couleur2="bgcolor='".$rouge."'";}
				if($Ligne_Q_R_RStagiaires[8]<>$Ligne_Q_R_RStagiaires[9] && $Ligne_Q_R_RStagiaires[9]==1){$couleur2="bgcolor='".$rouge."'";}
				
				$ReponseDebut.="<td width='5%' ".$couleur2." align='center'>&nbsp;";
				if($Ligne_Q_R_RStagiaires[9]>0){$ReponseDebut.="X";}
				$ReponseDebut.="
					</td>
					<td width='5%' ".$couleur2." align='center'>".$NoteReponse."</td>";
			}
			else
			{
				$ReponseDebut.="<td width='5%' align='center'></td>\n";
			}
			//-------
			
			if($Ligne_Q_R_RStagiaires[1] <> $QuestionPrecedente)
			{
				if($couleur=="#ffffff"){$couleur="#dcdcdc";}else{$couleur="#ffffff";}
				
				$QuestionPrecedente=$Ligne_Q_R_RStagiaires[1];
				$ImageQuestion="";
				$sommeCoeff+=$Ligne_Q_R_RStagiaires[2];
				$sommeCoeffQCM+=$Ligne_Q_R_RStagiaires[2];
				if($Ligne_Q_R_RStagiaires[3]<>"")
				{
					if(file_exists('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$RowQCM_Langue['Id'].'/'.$Ligne_Q_R_RStagiaires[3]))
					{
						$ImageQuestion="<img src='Docs/QCM/".$RowQCM_Langue['Id_QCM']."/".$RowQCM_Langue['Id']."/".$Ligne_Q_R_RStagiaires[3]."' height='60px'>";
					}
				}
				
				$formulaire.= "</table>";
				$formulaire.= "<table bgcolor='#ffffff' border='1' style='width:100%; border-spacing:0;page-break-inside: avoid;'>";
				$formulaire.= "<tr'>\n";
				$formulaire.= "
				<td width='3%' rowspan='".$Ligne_Q_R_RStagiaires[4]."' align='center' valign='middle'>".$Ligne_Q_R_RStagiaires[0]."</td>\n
				<td width='35%' colspan='2' rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Ligne_Q_R_RStagiaires[1]."<br>".$ImageQuestion."</td>\n
				".$ReponseDebut;

				if($QCM_Repondu)
				{
					$Nb_BonnesReponses=$Ligne_Q_R_RStagiaires[12];
					$Nb_ReponsesCorrects=$Ligne_Q_R_RStagiaires[13];
					$Nb_ReponsesFausses=$Ligne_Q_R_RStagiaires[14];

					$Note=0;
					if($Nb_ReponsesFausses==0 || $Nb_ReponsesFausses=="")
					{
						$Note=round($Nb_ReponsesCorrects/$Nb_BonnesReponses,2);
						$sommeNote+=$Note*$Ligne_Q_R_RStagiaires[2];
						$sommeNoteQCM+=$Note*$Ligne_Q_R_RStagiaires[2];
					}
					$formulaire.= "
						<td width='5%' rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Note."/1</td>
						<td width='5%' rowspan='".$Ligne_Q_R_RStagiaires[4]."' bgcolor='#c0c0c0' valign='middle' align='center'>".$Ligne_Q_R_RStagiaires[2]."</td>
						<td width='7%' colspan='2' rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Note*$Ligne_Q_R_RStagiaires[2]."/".$Ligne_Q_R_RStagiaires[2]."</td>"."\n";
				}
				$formulaire.= "</tr>\n";
			}
			else
			{
				
				$formulaire.= "<tr'>
				".
						$ReponseDebut."
					</tr>\n";
			}

			$num++;
		}
		
		$Repondeur=$RowFormSessionPersonneQualification['Repondeur'];
		if($Repondeur==""){
			$Repondeur=$RowFormSessionPersonneQualification['Stagiaire'];
		}
		
		$formulaire.= "</table>";
		
		if($QCM_Repondu)
			{
			$formulaire.= "<table bgcolor='#ffffff' border='1' style='width:100%; border-spacing:0;page-break-inside: avoid;'>";
			$formulaire.= "<tr>";
			
			if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){
				if($RowFormSessionPersonneQualification['Id_Personne']==$RowFormSessionPersonneQualification['Id_Repondeur']){
					$formulaire.= "<td colspan='2' width='15%'>Renseigné par : ".$RowFormSessionPersonneQualification['Stagiaire']."</td>";
				}
				else{
					$formulaire.= "<td colspan='2' width='15%'>Renseigné par : QCM complété par ".$RowFormSessionPersonneQualification['Repondeur'].' sur la base des réponses données par '.$RowFormSessionPersonneQualification['Stagiaire']."</td>";
				}
				$formulaire.="<td colspan='2'>Date et signature : ".AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneQualification['DateHeureRepondeur'],0,10))." / ".$RowFormSessionPersonneQualification['Repondeur']." \"signature électronique\" </td>";
			}
			else{
				if($RowFormSessionPersonneQualification['Id_Personne']==$RowFormSessionPersonneQualification['Id_Repondeur']){
					$formulaire.= "<td colspan='2' width='15%'>Filled in by : ".$RowFormSessionPersonneQualification['Stagiaire']."</td>";
				}
				else{
					$formulaire.= "<td colspan='2' width='15%'>Filled in by : MCQ completed by ".$RowFormSessionPersonneQualification['Repondeur'].' based on answers given by '.$RowFormSessionPersonneQualification['Stagiaire']."</td>";
				}
				$formulaire.="<td colspan='2'>Date and signature : ".AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneQualification['DateHeureRepondeur'],0,10))." / ".$RowFormSessionPersonneQualification['Repondeur']." \"electronic signature\" </td>";
			}
			$formulaire.= "
					<td width='15%' align='center' colspan='3'>TOTAL</td>
					<td width='15%' align='center' colspan='3' bgcolor='#c0c0c0'>".$sommeNoteQCM."/".$sommeCoeffQCM."</td>
				</tr>
				<tr>";
			if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){$formulaire.= "<td colspan='2' width='15%'>Corrigé par : Correction automatique</td>";}
					else{$formulaire.= "<td colspan='2' width='15%'>Corrected by : Autocorrect</td>";}
			if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){
					$formulaire.= "<td colspan='2'>Date et signature : ".AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneQualification['DateHeureRepondeur'],0,10))." \"signature électronique\"</td>";
			}
			else{
				$formulaire.= "<td colspan='2'>Date and signature : ".AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneQualification['DateHeureRepondeur'],0,10))." \"electronic signature\"</td>";
			}
			$formulaire.= "<td width='15%' align='center' colspan='3'>%</td>";
					if($sommeCoeffQCM>0){$formulaire.= "<td width='15%' align='center' colspan='3'>".round($sommeNoteQCM/$sommeCoeffQCM*100,2)."</td>";}
					else{$formulaire.= "<td width='15%' align='center' colspan='3'></td>";}
			$formulaire.= "</tr>";
			$formulaire.= "
			</table>
			<br>";
		}
		
	}
}

$formulaire.="</body>";
$formulaire.="</html>";


$dompdf->set_paper("a4", "landscape" ); 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

$canvas = $dompdf->get_canvas();
$font = 0;                  
$canvas->page_text(790, 570, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 10, array(0,0,0));
$canvas->page_text(350, 565, "DOCUMENT QUALITE", $font, 10, array(0,0,0));
$canvas->page_text(360, 575, "Copyright protected", $font, 10, array(0,0,0));
$canvas->page_text(10, 565, "Edition 1", $font, 10, array(0,0,0));
$canvas->page_text(10, 575, "24/02/2014", $font, 10, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();
?>