<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Rediffuser la formation</title><meta name="robots" content="noindex">
</head>
<body>

<?php
if($_GET){
	
	//Récupérer les informations de la session
	$req="SELECT Id,Id_GroupeSession,Formation_Liee, Id_Formation  ";
	$req.="FROM form_session WHERE Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$LigneSession=mysqli_fetch_array($result);

	$formationLiee="";
	$tab = array();
	//Vérifier si cette session n'appartient pas à un groupe de sessions liées
	if($LigneSession['Formation_Liee']>0 && $LigneSession['Id_GroupeSession']>0){
		$req="SELECT DISTINCT form_session.Id  ";
		$req.="FROM form_session_groupe ";
		$req.="LEFT JOIN form_session ON form_session_groupe.Id=form_session.Id_GroupeSession ";
		$req.="WHERE form_session.Suppr=0 AND form_session.Id_GroupeSession=".$LigneSession['Id_GroupeSession'];
		$result=mysqli_query($bdd,$req);
		while($row=mysqli_fetch_array($result)){
			$tab[]=$row['Id'];
		}
		$formationLiee="";
		if($LangueAffichage=="FR"){
			$formationLiee.="Attention : Cette session est liée à d'autres sessions. La présence à toutes les formations est obligatoire.<br>";
		}
		else{
			$formationLiee.="Warning: This session is related to other sessions. Attendance at all courses is mandatory.<br>";
		}
	}
	else{
		$tab[]=$LigneSession['Id'];
	}

	//Liste des formations
	foreach ($tab as $val) {
		$dates="";
		$req="SELECT DateSession 
			FROM form_session_date
			WHERE Suppr=0
			AND Id_Session=".$val;
		$resultDates=mysqli_query($bdd,$req);
		while($rowDates=mysqli_fetch_array($resultDates)){
			$dates.=AfficheDateJJ_MM_AAAA($rowDates['DateSession'])."<br>";
		}
		
		$reqEmail="SELECT DISTINCT new_rh_etatcivil.EmailPro 
					FROM new_competences_personne_poste_prestation 
					LEFT JOIN new_rh_etatcivil 
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_prestation.Id_Poste IN (".implode(",",$TableauIdPostesCHE_COOE).") 
					AND new_competences_personne_poste_prestation.Id_Prestation IN (
						SELECT DISTINCT Id_Prestation
						FROM form_besoin
						WHERE Traite=0
						AND Valide=1
						AND Suppr=0
						AND Id_Formation=".$LigneSession['Id_Formation']."
					)
					AND new_competences_personne_poste_prestation.Id_Prestation IN (
						SELECT Id_Prestation 
						FROM form_session_prestation
						WHERE Id_Session=".$val."
					)";
		$ResultEmail=mysqli_query($bdd,$reqEmail);
		$NbEmail=mysqli_num_rows($ResultEmail);
		$Emails="";
		if($NbEmail>0){
			while($RowEmail=mysqli_fetch_array($ResultEmail)){
				if($RowEmail['EmailPro']<>""){$Emails.=$RowEmail['EmailPro'].",";}
			}
		}
		if($Emails<>""){
			$Emails=substr($Emails,0,-1);
			//Envoie mail aux resp pour avertir de la nouvelle session de formation 
			$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
			$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			
			$Formation="";
			$Organisme="";
			$Recyclage=0;
			$reqSession="SELECT Recyclage FROM form_session WHERE Id=".$val;
			$resultSession=mysqli_query($bdd,$reqSession);
			$nbSession=mysqli_num_rows($resultSession);
			if($nbSession>0){
				$rowSession=mysqli_fetch_array($resultSession);
				$Recyclage=$rowSession['Recyclage'];
			}
			//Afficher les informations de la formation
			$SQL_Formation="(SELECT form_session.Id_Formation FROM form_session WHERE form_session.Id=".$val.")";
			$req=Get_SQL_InformationsPourFormation($_GET['Id_Plateforme'], $SQL_Formation);
			$resultFormation=mysqli_query($bdd,$req);
			$nbFormation=mysqli_num_rows($resultFormation);
			if($nbFormation>0){
				$rowForm=mysqli_fetch_array($resultFormation);
				if($rowForm['Organisme']<>""){$Organisme=" (".stripslashes($rowForm['Organisme']).")";}
				if($Recyclage==0){$Formation=$rowForm['Libelle'];}
				else{$Formation=$rowForm['LibelleRecyclage'];}
			}
		
			if($LangueAffichage=="FR"){
				$Objet="Rappel : Formation ".$Formation.$Organisme." disponible dans le planning des formation ";
				$MessageMail="	<html>
								<head><title>Rappel : Formation ".$Formation.$Organisme." disponible dans le planning des formation </title></head>
								<body>
									Bonjour,
									<br><br>
									<i>Cette boîte mail est une boîte mail générique</i>
									<br><br>
									Rappel
									<br>
									Une session de formation ".$Formation.$Organisme." a été ajoutée aux dates suivantes : <br>
									".$dates." <br>
									".$formationLiee."<br>
									Pensez à inscrire votre personnel.
									<br>
									Bonne journée.<br>
									Formation Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			else{
				$formLiee="";
				if($_POST['formationsLiees']==1){$formLiee="Warning: This session is related to other sessions. Attendance at all courses is mandatory.<br>";}
				$Objet="Reminder : Training ".$Formation.$Organisme." available in the training schedule";
				$MessageMail="	<html>
								<head><title>Reminder : Training ".$Formation.$Organisme." available in the training schedule</title></head>
								<body>
									Hello,
									<br><br>
									<i>This mailbox is a generic mailbox</i>
									<br><br>
									Reminder
									<br>
									A training session ".$Formation.$Organisme." was added on the following dates : <br>
									".$dates." <br>
									".$formationLiee."<br>
									Remember to register your staff.
									<br>
									Have a good day.<br>
									Training Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			if($Emails<>""){
				if(mail($Emails,$Objet,$MessageMail,$Headers,'-f qualipso@aaa-aero.com'))
					{echo "";}
			}
		}
	}
	echo "<script>window.close();</script>";
}
?>
</body>
</html>