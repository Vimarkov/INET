<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Récupérer QCM</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location="Gestion_SessionFormation.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_GET['Id']<>0 && $_GET['Id']<>""){
	
	$req="
	UPDATE form_session_personne_qualification_question
	SET Suppr=1
	WHERE
		Id_Session_Personne_Qualification=".$_GET['Id']."
		";
	$resultSessionsPersonne=mysqli_query($bdd,$req);
	
	$req="
		SELECT
			Id,Id_QCM_Langue_Question
		FROM
			form_session_personne_qualification_question
		WHERE
			form_session_personne_qualification_question.Suppr=1 
			AND form_session_personne_qualification_question.Id_Session_Personne_Qualification=".$_GET['Id']."
		ORDER BY 
			form_session_personne_qualification_question.Id ASC
			";
			echo $req;
	$resultSessionsPersonneOLD=mysqli_query($bdd,$req);
	$nbSessionPersonneOLD=mysqli_num_rows($resultSessionsPersonneOLD);
	
	$tabQuestion=array();
	if($nbSessionPersonneOLD>0){
		while($rowSessionPersonne=mysqli_fetch_array($resultSessionsPersonneOLD)){
			$trouve=0;
			foreach($tabQuestion as $question){
				if($question==$rowSessionPersonne['Id_QCM_Langue_Question']){
					$trouve=1;
				}
			}

			if($trouve==0){
				$req="
					UPDATE form_session_personne_qualification_question
					SET Suppr=0
					WHERE
						Id=".$rowSessionPersonne['Id']."";
				$resultUpdate=mysqli_query($bdd,$req);

				array_push($tabQuestion, $rowSessionPersonne['Id_QCM_Langue_Question']);
			}
		}
		
		
		calculResultatQCMs($_GET['Id']);
			
		//Déclaration du QCM comme étant fait
		$ReqUpdateFormSessionPersonneQualification="
			UPDATE
				form_session_personne_qualification
			SET
				Id_Repondeur=".$IdPersonneConnectee.",
				DateHeureRepondeur='".date("Y-m-d H:i:s")."'
			WHERE
				Id=".$_GET['Id'];
		$ResultUpdateFormSessionPersonneQualification=mysqli_query($bdd,$ReqUpdateFormSessionPersonneQualification);
	}
	echo "<script>FermerEtRecharger();</script>";
}
?>
</body>
</html>