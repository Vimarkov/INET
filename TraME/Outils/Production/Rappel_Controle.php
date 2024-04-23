<html>
<head>
	<title>Contrôles - Rappel</title><meta name="robots" content="noindex">
</head>
<body>

<?php


session_start();
require_once("../Connexioni.php");
require("../Fonctions.php");

$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$req="SELECT Id, Libelle FROM trame_prestation";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if($nbResulta>0){
	while($rowPresta=mysqli_fetch_array($result)){
		
		$destinataire="";
		$req="SELECT 
			(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=trame_acces.Id_Personne) AS EmailPro 
			FROM trame_acces WHERE SUBSTRING(Droit,2,1)=1 AND Id_Prestation=".$rowPresta['Id'].";";
		$resulEmail=mysqli_query($bdd,$req);
		$nbEmail=mysqli_num_rows($resulEmail);
		if ($nbEmail>0){
			while($row=mysqli_fetch_array($resulEmail)){
				if($row['EmailPro']<>""){
					$destinataire.=$row['EmailPro'].",";
				}
			}
		}

		$sujet ="Relance auto-contrôle / contrôle croisé // Remind of self-checks / cross checks (".$rowPresta['Libelle'].") - ".date('d/m/Y');
		
		$message_html="	<html>
		<head><title>Rappel des contrôles</title></head>
		<body>
			Bonjour,
			<br><br>
			";
			
		//Liste des autocontrôles 
		$requete="SELECT Designation,DatePreparateur,
			(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur 
			FROM trame_travaileffectue 
			WHERE Statut='AC' AND Id_Prestation=".$rowPresta['Id']."
			ORDER BY Designation";
		$resultAC=mysqli_query($bdd,$requete);
		$nbResultaAC=mysqli_num_rows($resultAC);
		
		if($nbResultaAC>0){
			$message_html.="<br>
					Les autocontrôles suivant sont à réaliser / The following self-checks are to be realized :
					<table style='border-spacing:0;padding:0px;'>
						<tr>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Référence / Reference</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Tâche / Task</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Date de production / Production date</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Préparateur / Manufacturing engineer</td>
						</tr>\n
						";
			while($rowAC=mysqli_fetch_array($resultAC)){
				$message_html.="<tr>
							<td style='border:1px solid black;'>".$rowAC['Designation']."</td>
							<td style='border:1px solid black;'>".stripslashes(str_replace("//","",$rowAC['Tache']))."</td>
							<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($rowAC['DatePreparateur'])."</td>
							<td style='border:1px solid black;'>".$rowAC['Preparateur']."</td>
							</tr>";
			}
			$message_html.="</table>";
		}
		
		//Liste des contrôles 
		$requete="SELECT Designation,DatePreparateur,
			(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur,
			(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_controlecroise.Id_Controleur)
				FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id LIMIT 1) AS Controleur
			FROM trame_travaileffectue 
			WHERE Statut='CONTROLE' AND Id_Prestation=".$rowPresta['Id']."
			ORDER BY Designation";
		$resultCON=mysqli_query($bdd,$requete);
		$nbResultaCON=mysqli_num_rows($resultCON);
		
		if($nbResultaCON>0){
			$message_html.="<br>
					Les contrôles suivant sont à réaliser / The following controlls are to be realized :
					<table style='border-spacing:0;padding:0px;'>
						<tr>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Référence / Reference</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Tâche / Task</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Date de production / Production date</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Préparateur / Manufacturing engineer</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Contrôleur / Controller</td>
						</tr>\n
						";
			while($rowCON=mysqli_fetch_array($resultCON)){
				$message_html.="<tr>
							<td style='border:1px solid black;'>".$rowCON['Designation']."</td>
							<td style='border:1px solid black;'>".stripslashes(str_replace("//","",$rowCON['Tache']))."</td>
							<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($rowCON['DatePreparateur'])."</td>
							<td style='border:1px solid black;'>".$rowCON['Preparateur']."</td>
							<td style='border:1px solid black;'>".$rowCON['Controleur']."</td>
							</tr>";
			}
			$message_html.="</table>";
		}	
		
		//Liste des recontrôles 
		$requete="SELECT Designation,DatePreparateur,
			(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur,
			(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_controlecroise.Id_Controleur)
				FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id LIMIT 1) AS Controleur
			FROM trame_travaileffectue 
			WHERE Statut='REC' AND Id_Prestation=".$rowPresta['Id']."
			ORDER BY Designation";
		$resultREC=mysqli_query($bdd,$requete);
		$nbResultaREC=mysqli_num_rows($resultREC);
		
		if($nbResultaREC>0){
			$message_html.="<br>
					Les recontrôles suivant sont à réaliser / The following recontrollings are to be realized :
					<table style='border-spacing:0;padding:0px;'>
						<tr>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Référence / Reference</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Tâche / Task</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Date de production / Production date</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Préparateur / Manufacturing engineer</td>
							<td style='border:1px solid black;' bgcolor='#a3d6f1'>Contrôleur / Controller</td>
						</tr>\n
						";
			while($rowREC=mysqli_fetch_array($resultREC)){
				$message_html.="<tr>
							<td style='border:1px solid black;'>".$rowREC['Designation']."</td>
							<td style='border:1px solid black;'>".stripslashes(str_replace("//","",$rowREC['Tache']))."</td>
							<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($rowREC['DatePreparateur'])."</td>
							<td style='border:1px solid black;'>".$rowREC['Preparateur']."</td>
							<td style='border:1px solid black;'>".$rowREC['Controleur']."</td>
							</tr>";
			}
			$message_html.="</table>";
		}
		
		if($nbResultaAC>0 || $nbResultaCON>0 || $nbResultaREC>0){
			if($destinataire<>""){
				if(mail($destinataire,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com'))
				{echo "";}
			}
		}
	}
}
echo "<script>window.close();</script>";
?>
</body>
</html>