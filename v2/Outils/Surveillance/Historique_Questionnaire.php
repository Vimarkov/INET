<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<?php
session_start();
require("../Connexioni.php");
?>
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							if($_SESSION["Langue"]=="FR"){echo "Gestion des surveillances # Historique des questions / réponses";}
							else{echo "Monitoring management # Questions / Responses histoy";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
    		<table class="TableCompetences" style="width:100%;">
    			<tr align="center">
    				<td class="EnTeteTableauCompetences" width="15">Date</td>
    				<td class="EnTeteTableauCompetences" width="20">Action</td>
    				<td class="EnTeteTableauCompetences" width="20"><?php if($_SESSION["Langue"]=="FR"){echo "Realisée par";}else{echo "Realized by";}?></td>
    				<td class="EnTeteTableauCompetences" width="5">N°</td>
    				<td class="EnTeteTableauCompetences" width="200">Question / Réponse</td>
    			</tr>
    			<?php
    				$req = "
                        SELECT
                            new_surveillances_question.ID,
                            new_surveillances_question.Numero,
                            new_surveillances_question.Question,
                            new_surveillances_question.Question_EN,
							new_surveillances_question.Reponse,
                            new_surveillances_question.Reponse_EN,
                            (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_question.ID_Personne) AS Personne,
                            new_surveillances_question.DateModification,
                            new_surveillances_question.Supprime
                        FROM
                            new_surveillances_question
                        WHERE
                            new_surveillances_question.ID_Questionnaire =".$_GET['ID_Questionnaire']."
                        ORDER BY
                            new_surveillances_question.DateModification DESC,
                            new_surveillances_question.Numero ASC ;";
    				$resultQuestion=mysqli_query($bdd,$req);
    				$nbQuestion=mysqli_num_rows($resultQuestion);
    
    				if($nbQuestion > 0)
    				{
    					while($rowQuestion=mysqli_fetch_array($resultQuestion))
    					{
    						echo "<tr>";
    						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='15'>".$rowQuestion['DateModification']."</td>";
    						if ($rowQuestion['Supprime'] == 0){echo "<td style='border-bottom:1px #d9d9d7 solid;' width='20'>Ajout</td>";}
    						else{echo "<td style='border-bottom:1px #d9d9d7 solid;' width='20'>Suppression</td>";}
    						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='20'>".$rowQuestion['Personne']."</td>";
    						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5'>".$rowQuestion['Numero']."</td>";
    						echo "<td style='border-bottom:1px #d9d9d7 solid;' width='200'>Question FR : ".$rowQuestion['Question']."<br>Question EN : ".$rowQuestion['Question_EN']."<br>Réponse FR : ".$rowQuestion['Reponse']."<br>Réponse EN : ".$rowQuestion['Reponse_EN']."</td>";
    					}
    				}
    			?>
    		</table>
    	</td>
	</tr>
</table>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>