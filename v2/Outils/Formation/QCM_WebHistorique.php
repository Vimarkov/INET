<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
    <head>
    	<title>Formations - QCM WEB</title><meta name="robots" content="noindex">
    	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
    	<script type="text/javascript" src="Fonctions.js"></script>
    	<script type="text/javascript">
    	function VerifChamps()
    	{
    		var manquereponse = false;
			var cocheinput = 0;
			var idquestion = 0;
        	var ListeReponses="";
    		var Reponses=document.getElementsByTagName("input");
    		for(var i=0;i<Reponses.length;i++)
        	{
            	if(Reponses[i].type=="checkbox")
            	{
					ReponseQCM=Reponses[i].value.split('_');
					if(ReponseQCM[0]==document.getElementById("qcm1").value)
					{
						ListeReponses+=ReponseQCM[1]+"|";
						if(Reponses[i].checked){ListeReponses+="1";}
						else{ListeReponses+="0";}
						ListeReponses+="#";
					}
					//Vérification si intégralité des questions n'ont pas été répondues
					//Invite à valider ou non le QCM
					if(Reponses[i].getAttribute("id") != idquestion)
					{
						if(cocheinput == 0 && idquestion > 0){manquereponse = true;}
						cocheinput = 0;
						if(Reponses[i].checked){cocheinput++;}
						idquestion = Reponses[i].getAttribute("id");
					}
					else{if(Reponses[i].checked){cocheinput++;}}
            	}
            }
    		document.getElementById("ListeReponses").value=ListeReponses;
    		if(cocheinput == 0){manquereponse = true;}
			
			//Uniquement sur qcm annexe
			if(document.getElementById("qcm2").value!=""){
				var ListeReponsesFille="";
				var Reponses=document.getElementsByTagName("input");
				for(var i=0;i<Reponses.length;i++)
				{
					if(Reponses[i].type=="checkbox")
					{
						ReponseQCM=Reponses[i].value.split('_');
						if(ReponseQCM[0]==document.getElementById("qcm2").value){
							ListeReponsesFille+=ReponseQCM[1]+"|";
							if(Reponses[i].checked){ListeReponsesFille+="1";}
							else{ListeReponsesFille+="0";}
							ListeReponsesFille+="#";
						}
					}
				}
				document.getElementById("ListeReponsesFille").value=ListeReponsesFille;
			}
			if(manquereponse == true)
			{
				if(confirm('Une des questions n\'a aucune réponse de cochée. Souhaitez-vous tout de même valider votre QCM ?')){return true;}
				else{return false;}
			}
			else {return true;}
    	}

    	function FermerEtRecharger(Id,Page)
		{
			if(Page=="Gestion_SessionFormation"){
				opener.location="Gestion_SessionFormation.php";
			}
			else if(Page=="WorkflowDesSurveillances_Liste"){
				opener.location="WorkflowDesSurveillances_Liste.php";
			}
			else if(Page=="Liste_QCMsansFormation"){
				opener.location="Liste_QCMsansFormation.php";
			}
			else{
				opener.location="Tableau_De_Bord_Stagiaire.php";
			}
			window.location="QCM_Web_v3.php?Page="+Page+"&Retour=1&Id_Session_Personne_Qualification="+Id;
		}
		function OuvreExcel(Id,Id_QCM_Langue)
		{
			var w=window.open("QCM_PDF.php?Id_Session_Personne_Qualification="+Id+"&Id_QCM_Langue="+Id_QCM_Langue,"PageQCM","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
		function VerifChamps2(){
			bCheck=0;
			var check=document.getElementsByTagName("input");

			for(var i=0;i<check.length;i++)
			{
				if(check[i].type=="checkbox")
				{
					if(check[i].value=="engagement"){
						if(check[i].checked){
							bCheck=1;
						}
					}
				}
			}
			if(bCheck==0){
				alert("Veuillez cocher la case de prise en compte des erreurs");
				return false;
			}
		}
		
		function FermerEtRecharger2(Id,Page)
		{
			if(Page=="Gestion_SessionFormation"){
				opener.location="Gestion_SessionFormation.php";
			}
			else if(Page=="WorkflowDesSurveillances_Liste"){
				opener.location="WorkflowDesSurveillances_Liste.php";
			}
			else if(Page=="Liste_QCMsansFormation"){
				opener.location="Liste_QCMsansFormation.php";
			}
			else{
				opener.location="Tableau_De_Bord_Stagiaire.php";
			}
			window.close();
		}
    	</script>
    </head>
<?php
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
if(QCMestOuvert($_GET['Id_Session_Personne_Qualification']))
{
	$QCM_Ouvert=true;
}

//Le filtre suivant concernant les DroitsFormationsPlateforme n'est pas effectué par rapport à la plateforme de la personne concernée par le QCM
//Mais on va partir du principe que les AF,RF,FORM,RQP ne vont pas aller voir les QCM des autres plateformes
if($RowFormSessionPersonneQualification['Id_Personne']==$IdPersonneConnectee || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || ($QCM_Repondu && DroitsFormationPrestation($TableauIdPostesCQ)) || (DroitsFormationPrestation($TableauIdPostesCQ) && $RowTypePassage['TypePassageQCM']>0)){$QCM_Acces_OK=true;}
//------------------------------------
//Les AF, RF, PS et RQP n'ont pas besoin d'ouvrir le QCM pour y accéder
if(($QCM_Ouvert || $QCM_Repondu || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || (DroitsFormationPrestation($TableauIdPostesCQ) && $RowTypePassage['TypePassageQCM']>0)) && $QCM_Acces_OK)
{
	$tabQCM=array();
	$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM']."_".$RowFormSessionPersonneQualification['Id_LangueQCM'];
	if($RowFormSessionPersonneQualification['Id_QCM_Lie']>0)
	{
		$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM_Lie']."_".$RowFormSessionPersonneQualification['Id_LangueQCMLie'];
	}
	$Page="";
	if(isset($_GET['Page'])){$Page=$_GET['Page'];}
	echo "
		<form id='formulaire' method='POST' action='QCM_WebHistorique.php' onSubmit='return VerifChamps();'>
		<input type='hidden' id='ListeReponses' name='ListeReponses' value=''>
		<input type='hidden' id='Page' name='Page' value='".$Page."'>
		<input type='hidden' id='ListeReponsesFille' name='ListeReponsesFille' value=''>
		<input type='hidden' name='Id_Session_Personne_Qualification' value='".$_GET['Id_Session_Personne_Qualification']."'>";
	$nb=0;
	$sommeCoeff=0;
	$sommeNote=0;
	foreach($tabQCM AS $QCM)
	{
		$nb++;
		$tabLeQCM = explode("_",$QCM);
		$sommeCoeffQCM=0;
		$sommeNoteQCM=0;
		if($nb==1){echo "<input type='hidden' id='qcm1' name='qcm1' value='".$tabLeQCM[0]."'>";}
		else{echo "<input type='hidden' id='qcm2' name='qcm2' value='".$tabLeQCM[0]."'>";}
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
				Id_Langue=".$tabLeQCM[1]." 
				AND Id_QCM=".$tabLeQCM[0];
			if($QCM_Repondu == false){
				$ReqQCM_Langue.=" AND Suppr=0 ";
			}
			$ReqQCM_Langue.=" ORDER BY Id DESC" ;
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
		echo "
				<table class='GeneralInfo' border='1' style='width:100%; height:100%; border-spacing:0;'>";
				if(DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || (DroitsFormationPrestation($TableauIdPostesCQ) && $RowTypePassage['TypePassageQCM']>0))
				{
					echo "<tr>
							<td colspan='10' align='right'>
							&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreExcel('".$_GET['Id_Session_Personne_Qualification']."','".$RowQCM_Langue['Id']."');\">
							<img width='20px' src='../../Images/pdf.png' style='border:0;' alt='QCM'>&nbsp;&nbsp;&nbsp;&nbsp;
							</a>
							</td>
						</tr>";
				}
				if($RowQCM['Fichier']<>""){
					echo "<tr>
							<td colspan='10' align='right' style='font-size:20px;'>";
					if($_SESSION['Langue']=="FR"){
						echo "<span style='color:red;'>Annexe : </span>";
					}
					else{
						echo "<span style='color:red;'>Appendice : </span>";
					}
					echo "
							&nbsp;&nbsp;<a class='Modif' target=\"_blank\" href=\"Docs/QCM/".$RowQCM['Id']."/".$RowQCM['Fichier']."\">
							<img src='../../Images/Tableau.gif' style='border:0;' alt='QCM'>&nbsp;&nbsp;&nbsp;&nbsp;
							</a>
							</td>
						</tr>";
				}
					
					echo "<tr>
						<td colspan='2' rowspan='3'><img src='../../Images/Logos/Logo_Doc_Group.png'></td>
						<td colspan='3' align='center' style='font-size:25px;font-weight:bold;'>".$titre."</td>
						<td colspan='4'>&nbsp;</td>
					</tr>
					<tr>
						<td colspan='3' align='center' style='color:#162bdd;'>".$explication1."</td>
						<td colspan='2' align='center' style='font-size:15px;font-weight:bold;'>CODE</td>
						<td colspan='2' align='center' style='font-size:15px;font-weight:bold;'>".$RowQCM['Code']."</td>
					</tr>
					<tr>
						<td colspan='3' align='center' style='color:#162bdd;'>".$explication2."</td>";
				if($RowQCM['Client']<>"")
				{
					echo "
						<td colspan='2'>CLIENT</td>
						<td colspan='2'>".$RowQCM['Client']."</td>";
				}
				else
				{
					echo "<td colspan='4'>&nbsp;</td>";
				}
				echo "</tr><tr>";
				if($RowFormSessionPersonneQualification['Id_LangueQCM']==1)
				{
					echo "
						<td colspan='2' style='font-weight:bold;'>Mis à jour le ".AfficheDateJJ_MM_AAAA($RowQCM_Langue['Date_MAJ'])."</td>
						<td colspan='3'>&nbsp;</td>
						<td colspan='4' style='font-weight:bold;' align='right'>Par ".$RowQCM_Langue['Personne']."&nbsp;&nbsp;</td>";
				}
				else
				{
					echo "
						<td colspan='2'>Updated the ".AfficheDateFR($RowQCM_Langue['Date_MAJ'])."</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td colspan='4' align='right'>By ".$RowQCM_Langue['Personne']."</td>";
				}
				echo "</tr><tr bgcolor='#55d5f1' style='font-size:20px;font-weight:bold;'>";
				if($RowFormSessionPersonneQualification['Id_LangueQCM']==1)
				{
					echo "
						<td width='3%' align='center'>N°</td>
						<td width='35%' align='center' colspan='2'>Question</td>
						<td width='35%' align='center'>Choix</td>
						<td width='5%' align='center'>Réponse</td>
						<td width='5%' align='center'>Résultat</td>";
				}
				else
				{
					echo "
						<td width='3%' align='center'>No</td>
						<td width='35%' align='center' colspan='2'>Question</td>
						<td width='35%' align='center'>Choice</td>
						<td width='5%' align='center'>Reply</td>
						<td width='5%' align='center'>Result</td>";
				}
				echo "<td width='5%' align='center'>Note</td>";
				echo "<td width='5%' align='center'>Coefficient</td>";
				echo "<td width='5%' align='center'>Total</td>";
				if($QCM_Repondu){echo "<td align='center'>Solution</td>";}
			echo "</tr>";
			
			$assistant=0;
			if($Page=="Gestion_SessionFormation" || $Page=="Liste_QCMsansFormation" || $Page=="WorkflowDesSurveillances_Liste"){$assistant=1;}
			$QCM_Q_R_RStagiaires=Generer_QCMHistorique($RowQCM_Langue['Id'], $_GET['Id_Session_Personne_Qualification'], $_GET['NbLigne'],$_GET['Num']);
		
		$QuestionPrecedente="";
		$num=1;
		$couleur="#ffffff";
		foreach($QCM_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
		{
			//Réponses
			//--------
			$ReponseDebut="";
			$ImageReponse="";
			$ReponseFin="";
			if($Ligne_Q_R_RStagiaires[7]<>"")
			{
				if(file_exists ('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$RowQCM_Langue['Id'].'/'.$Ligne_Q_R_RStagiaires[7]))
				{
					$ImageReponse="<img src='Docs/QCM/".$RowQCM_Langue['Id_QCM']."/".$RowQCM_Langue['Id']."/".$Ligne_Q_R_RStagiaires[7]."' height='60px' width='60px'>";
				}
			}
			$ReponseDebut="<td valign='middle'>".$Ligne_Q_R_RStagiaires[6].$ImageReponse."</td>";
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
				
				$ReponseDebut.="<td ".$couleur2." align='center'>&nbsp;";
				if($Ligne_Q_R_RStagiaires[9]>0){$ReponseDebut.="X";}
				$ReponseDebut.="
					</td>
					<td ".$couleur2." align='center'>".$NoteReponse."</td>";
				if($Ligne_Q_R_RStagiaires[8]==0){$ReponseFin="<td align='center'>Faux</td>";}
				else{$ReponseFin="<td align='center'>Vrai</td>";}
			}
			else
			{
				$ReponseDebut.="<td align='center'><input name='Reponses[]' id='".$Ligne_Q_R_RStagiaires[0]."' value='".$RowQCM_Langue['Id_QCM']."_".$Ligne_Q_R_RStagiaires[11]."|".$Ligne_Q_R_RStagiaires[5]."' size='30' type='checkbox'></td>\n";
			}
			//-------
			
			if($Ligne_Q_R_RStagiaires[1] <> $QuestionPrecedente)
			{
				if($couleur=="#ffffff"){$couleur="#dcdcdc";}else{$couleur="#ffffff";}
				echo "<tr bgcolor='".$couleur."'>\n";
				$QuestionPrecedente=$Ligne_Q_R_RStagiaires[1];
				$ImageQuestion="";
				$sommeCoeff+=$Ligne_Q_R_RStagiaires[2];
				$sommeCoeffQCM+=$Ligne_Q_R_RStagiaires[2];
				if($Ligne_Q_R_RStagiaires[3]<>"")
				{
					if(file_exists('Docs/QCM/'.$RowQCM_Langue['Id_QCM'].'/'.$RowQCM_Langue['Id'].'/'.$Ligne_Q_R_RStagiaires[3]))
					{
						$ImageQuestion="<img src='Docs/QCM/".$RowQCM_Langue['Id_QCM']."/".$RowQCM_Langue['Id']."/".$Ligne_Q_R_RStagiaires[3]."' height='60px' width='60px'>";
					}
				}
				
				echo "
					<td width='10' rowspan='".$Ligne_Q_R_RStagiaires[4]."' align='center' valign='middle'>".$Ligne_Q_R_RStagiaires[0]."</td>\n
					<td colspan='2' rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Ligne_Q_R_RStagiaires[1].$ImageQuestion."</td>\n
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
						echo "
							<td rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Note."/1</td>
							<td rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Ligne_Q_R_RStagiaires[2]."</td>
							<td rowspan='".$Ligne_Q_R_RStagiaires[4]."' valign='middle' align='center'>".$Note*$Ligne_Q_R_RStagiaires[2]."/".$Ligne_Q_R_RStagiaires[2]."</td>"
							.$ReponseFin."\n";
					}
				echo "</tr>\n";
			}
			else
			{
				echo "
					<tr bgcolor='".$couleur."'>".
						$ReponseDebut.$ReponseFin."
					</tr>\n";
			}

			$num++;
		}
		echo "<tr>
				<td width='55%' colspan='6'></td>
				<td width='15%' align='center' colspan='2'>TOTAL</td>
				<td width='15%' align='center' colspan='2'>".$sommeNoteQCM."/".$sommeCoeffQCM."</td>
			</tr>
			<tr>
				<td width='55%' colspan='6'></td>
				<td width='15%' align='center' colspan='2'>%</td>";
				if($sommeCoeffQCM>0){echo "<td width='15%' align='center' colspan='2'>".round($sommeNoteQCM/$sommeCoeffQCM*100,2)."</td>";}
				else{echo "<td width='15%' align='center' colspan='2'></td>";}
		echo "</tr>
		</table>
		<br>";
	}
	if($nb==1){echo "<input type='hidden' id='qcm2' name='qcm2' value=''>";}
	if(!$QCM_Repondu)
	{
		echo "
			<table width='100%'>
			<tr>
				<td align='center'>
					<input type='submit' name='valider' value='Valider QCM'>
				</td>
			</tr>
			</table>";
		echo"<br>";
	}
	else
	{
		$Repondeur=$RowFormSessionPersonneQualification['Repondeur'];
		$DateSignature="";
		if($RowFormSessionPersonneQualification['Id_Personne']==$RowFormSessionPersonneQualification['Id_Repondeur']){
			$Repondeur=$RowFormSessionPersonneQualification['Stagiaire'];
		}
		else{
			if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){$Repondeur="QCM complété par ".$RowFormSessionPersonneQualification['Repondeur'].' sur la base des réponses données par '.$RowFormSessionPersonneQualification['Stagiaire']." ";}
			else{$Repondeur="Filled in by : MCQ completed by ".$RowFormSessionPersonneQualification['Repondeur'].' based on answers given by '.$RowFormSessionPersonneQualification['Stagiaire'];}
		}
		if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){
			$DateSignature=AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneQualification['DateHeureRepondeur'],0,10))." / ".$RowFormSessionPersonneQualification['Repondeur']." \"signature électronique\"";
		}
		else{
			$DateSignature=AfficheDateJJ_MM_AAAA(substr($RowFormSessionPersonneQualification['DateHeureRepondeur'],0,10))." / ".$RowFormSessionPersonneQualification['Repondeur']." \"electronic signature\"";
		}
		echo "<table class='GeneralInfo' border='1' style='width:100%; height:100%; border-spacing:0;'>";
		echo "<tr>";
			if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){echo "<td width='15%'>Renseigné par</td>";}
			else{echo "<td width='15%'>Filled in by</td>";}
			echo "<td width='55%'>".$Repondeur."</td>
				<td width='15%' align='center'>TOTAL</td>
				<td width='15%' align='center'>".$sommeNote."/".$sommeCoeff."</td>
			</tr>";
		echo "<tr>";
			if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){echo "<td width='15%'>Date et signature</td>";}
			else{echo "<td width='15%'>Date and signature</td>";}
			echo "<td width='55%'>".$DateSignature."</td>
				<td width='15%' align='center'>%</td>";
				if($sommeCoeff>0){echo "<td width='15%' align='center'>".round($sommeNote/$sommeCoeff*100,2)."</td>";}
				else{echo "<td width='15%' align='center'>0</td>";}
			echo "</tr>";
		echo "</table>";
		echo"<br>";
	}
	echo "</form>";
	
	if($QCM_Repondu)
	{
		echo "
			<form id='formulaire' method='POST' action='QCM_Web_v3.php' onSubmit='return VerifChamps2();'>
			<input type='hidden' id='Page' name='Page' value='".$_GET['Page']."'>
			<input type='hidden' name='Id_Session_Personne_Qualification' value='".$_GET['Id_Session_Personne_Qualification']."'>
			<table class='GeneralInfo' style='width:100%; height:100%; border-spacing:0;'>";
		echo "<tr>
				<td>";
		
		$check="";
		if(!isset($_GET['Retour']))
		{
			$check="checked disabled='disabled'";
		}
		echo "<input type='checkbox' ".$check." name='engagement' id='engagement' value='engagement'> ";
		if($RowFormSessionPersonneQualification['Id_LangueQCM']==1){
			echo " Je reconnais avoir pris en compte la correction de mes erreurs ";
		}
		else{
			echo "I acknowledge having taken into account the correction of my errors";
		}
		
		echo "</td>
			</tr>";
		if(isset($_GET['Retour']))
		{
			echo "
				<tr>
					<td align='center'>
						<input type='submit' name='terminer' value='Terminer'>
					</td>
				</tr>
				";
		}
		echo"
			</table>
			</form>
			";
	}
}
else 
{
	echo "Vous n'avez pas les droit d'accès pour accéder à ce QCM.<br>";
	if(!$QCM_Ouvert){echo "Le QCM n'est pas ouvert pour réponse pour le moment<br>";}
	if(!$QCM_Acces_OK){echo "Vous n'avez pas les droits pour ouvrir ce QCM.";}
}
    ?>
</body>
</html>