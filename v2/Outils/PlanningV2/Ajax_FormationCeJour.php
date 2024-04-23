<?php
session_start();
require("../Connexioni.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");

$dateDebut=TrsfDate_($_GET['DateDebut']);
$dateFin=TrsfDate_($_GET['DateFin']);
$Id_Personne=$_GET['Id_Personne'];
$Attention="";

$req="
	SELECT
		form_session_personne.Id,
		form_session_personne.Id_Besoin,
		form_session_personne.Id_Personne AS Id_Personne,
		form_session_personne.Id_Session AS Id_Session,
		form_session_personne.Validation_Inscription,
		form_session_personne.Presence,
		form_session_personne.SemiPresence
	FROM
		form_session_personne 
	LEFT JOIN
		form_session
	ON
		form_session_personne.Id_Session=form_session.Id
	WHERE
		form_session_personne.Suppr=0
		AND form_session.Annule=0
		AND form_session.Suppr=0
		AND form_session_personne.Validation_Inscription <> -1
		AND form_session_personne.Presence NOT IN (-1,-2)
		AND Id_Personne =".$Id_Personne."
		AND (SELECT COUNT(form_session_date.Id)	 
			FROM form_session_date 
			WHERE form_session_date.Suppr=0 
			AND form_session_date.Id_Session=form_session_personne.Id_Session 
			AND DateSession>='".$dateDebut."'
			AND DateSession<='".$dateFin."'
			)>0
	";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if($nb>0){
	if($_SESSION['Langue']=="FR"){
		$Attention="<img width='25px' src='../../Images/attention.png'/>Vous êtes en formation à cette période. Vous devez demander la désinscription à votre responsable pour pouvoir faire votre demande de congés. ";
	}
	else{
		$Attention="<img width='25px' src='../../Images/attention.png'/>You are in training at this time. You must request the unsubscription to your manager to be able to request your leave.";
	}
}
echo $Attention;
?>