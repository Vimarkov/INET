<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Rappel à la formation</title><meta name="robots" content="noindex">
</head>
<body>

<?php
//Liste des prestations ayant une personne inscrite
$req="
	SELECT DISTINCT 
			(
				SELECT
					form_besoin.Id_Prestation
				FROM
					form_besoin
				WHERE form_besoin.Id=form_session_personne.Id_Besoin
			) AS Id_Prestation,
			IF((SELECT
					form_besoin.Id_Pole
				FROM
					form_besoin
				WHERE form_besoin.Id=form_session_personne.Id_Besoin
			)<>'',(SELECT
					form_besoin.Id_Pole
				FROM
					form_besoin
				WHERE form_besoin.Id=form_session_personne.Id_Besoin
			),0) AS Id_Pole
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
			AND form_session_personne.Validation_Inscription=1
			AND (
				SELECT COUNT(form_session_date.Id)
				FROM form_session_date
				WHERE form_session_date.DateSession>='".TrsfDate_($_GET['DateDebutRappel'])."'
				AND form_session_date.DateSession<='".TrsfDate_($_GET['DateFinRappel'])."'
				AND form_session_date.Suppr=0 
				AND form_session_date.Id_Session=form_session.Id
				)>0
			AND (
				SELECT COUNT(form_session_prestation.Id)
				FROM form_session_prestation
				WHERE 
					(SELECT Id_Plateforme 
					FROM new_competences_prestation 
					WHERE new_competences_prestation.Id=form_session_prestation.Id_Prestation)=".$_GET['Id_Plateforme']."
					AND form_session_prestation.Id_Session=form_session.Id
				)>0
			AND (
				SELECT
					form_besoin.Id_Prestation
				FROM
					form_besoin
				WHERE form_besoin.Id=form_session_personne.Id_Besoin
			)<>''
			AND (
				SELECT
					form_formation.Id_TypeFormation
				FROM
					form_formation
				WHERE form_formation.Id=form_session.Id_Formation
			)=".$_GET['TypeFormationRappel']."
	 ";
echo $req."<br><br>";
$ResultPrestation=mysqli_query($bdd,$req);
$NbPrestation=mysqli_num_rows($ResultPrestation);

$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

if($LangueAffichage=="FR"){
	$sujet ="Rappel des formations du ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateDebutRappel']))." au ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateFinRappel']));
}
else{
	$sujet ="Reminder of formations from ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateDebutRappel']))." to ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateFinRappel']));
}
if($NbPrestation>0){
	while($rowPrestation=mysqli_fetch_array($ResultPrestation)){
		$TableauEmailResponsablesPrestationPoleFiltre= array();	
		$TableauResponsablesPrestationPole = GetTableau_ResponsablesPrestationPole($rowPrestation['Id_Prestation'],$rowPrestation['Id_Pole']);
		$reponse=GetTableau_EmailResponsablesPourPostes($TableauResponsablesPrestationPole,array($IdPosteChefEquipe, $IdPosteCoordinateurEquipe));
		foreach ($reponse as $email){
			array_push($TableauEmailResponsablesPrestationPoleFiltre, $email);
		}
		$destinataire = implode(",",$TableauEmailResponsablesPrestationPoleFiltre);
		if($LangueAffichage=="FR"){
			$message_html="	<html>
				<head><title>Rappel des formations</title></head>
				<body>
					Bonjour,
					<br><br>
					<i>Cette boîte mail est une boîte mail générique</i>
					<br><br>
					Ci-dessous les formations du ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateDebutRappel']))." au ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateFinRappel']))."
					<br>
					<table style='border:1px solid black; border-spacing:0;'>
						<tr>
							<td style='border:1px solid black;' bgcolor='#A9A9A9'>Nom</td>
							<td style='border:1px solid black;' bgcolor='#A9A9A9'>Prénom</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Intitulé</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Date début</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Date fin</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Nombre d'heures</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Nombre de jours</td>
						</tr>\n";
			
				$req="
					SELECT form_session_personne.Id_Personne AS Id_Personne,
							form_session_personne.Id_Session AS Id_Session,
							form_session_personne.Validation_Inscription AS Validation_Inscription,
							(
								SELECT
									form_besoin.Id_Prestation
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) AS Id_Prestation,
							(
								SELECT
									form_besoin.Id_Pole
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) AS Id_Pole,
							(
								SELECT
									Nom
								FROM
									new_rh_etatcivil
								WHERE
									new_rh_etatcivil.Id=form_session_personne.Id_Personne
							) AS Nom,
							(
								SELECT
									Prenom
								FROM
									new_rh_etatcivil
								WHERE
									new_rh_etatcivil.Id=form_session_personne.Id_Personne
							) AS Prenom,
							form_session.Recyclage AS Recyclage,
							form_session.Id_Formation,
							(
								SELECT
									Id_Langue
								FROM
								form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
									AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
									AND Suppr=0 LIMIT 1
							) AS Id_Langue,
							(
								SELECT
								(
									SELECT
										Libelle
									FROM
										form_organisme
									WHERE
										Id=Id_Organisme
								)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							) AS Organisme 
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
							AND form_session_personne.Validation_Inscription=1
							AND (
								SELECT
									form_formation.Id_TypeFormation
								FROM
									form_formation
								WHERE form_formation.Id=form_session.Id_Formation
							)=".$_GET['TypeFormationRappel']."
							AND (
								SELECT COUNT(form_session_date.Id)
								FROM form_session_date
								WHERE form_session_date.DateSession>='".TrsfDate_($_GET['DateDebutRappel'])."'
								AND form_session_date.DateSession<='".TrsfDate_($_GET['DateFinRappel'])."'
								AND form_session_date.Suppr=0 
								AND form_session_date.Id_Session=form_session.Id
								)>0
							AND (
								SELECT COUNT(form_session_prestation.Id)
								FROM form_session_prestation
								WHERE (SELECT Id_Plateforme 
									FROM new_competences_prestation 
									WHERE new_competences_prestation.Id=form_session_prestation.Id_Prestation)=".$_GET['Id_Plateforme']."
									AND form_session_prestation.Id_Session=form_session.Id
								)>0
							AND (
								SELECT COUNT(Id)
								FROM new_competences_personne_prestation
								WHERE new_competences_personne_prestation.Id_Personne=form_session_personne.Id_Personne
								AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."' 
								AND new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
								AND new_competences_personne_prestation.Id_Prestation=".$rowPrestation['Id_Prestation']."
								AND new_competences_personne_prestation.Id_Pole=".$rowPrestation['Id_Pole']."
								)>0
				";
			$ResultPersonne=mysqli_query($bdd,$req);
			$NbPersonne=mysqli_num_rows($ResultPersonne);
			
			$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
						FROM form_formation_langue_infos 
						WHERE Suppr=0";
			$resultFormLangue=mysqli_query($bdd,$reqLangue);
			$nbFormLangue=mysqli_num_rows($resultFormLangue);

			if($NbPersonne>0){
				while($row=mysqli_fetch_array($ResultPersonne))
				{
					$Libelle="";
					if($nbFormLangue>0)
					{
						mysqli_data_seek($resultFormLangue,0);
						while($rowFormLangue=mysqli_fetch_array($resultFormLangue))
						{
							if($rowFormLangue['Id_Formation']==$row['Id_Formation'] && $rowFormLangue['Id_Langue']==$row['Id_Langue'] )
							{
								if($row['Recyclage']==0){$Libelle=stripslashes($rowFormLangue['Libelle']);}
								else
								{
									$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
									if($Libelle==""){$Libelle=stripslashes($rowFormLangue['Libelle']);}
								}
								if($row['Organisme']<>""){$Libelle.=" (".$row['Organisme'].")";}
							}
						}
					}
					
					$nbHeures="";
					$nbJours="";
					$reqPlat="SELECT NbJour, NbJourRecyclage, Duree, DureeRecyclage  
						FROM form_formation_plateforme_parametres
						WHERE Suppr=0
						AND Id_Plateforme=".$_GET['Id_Plateforme']."
						AND Id_Formation=".$row['Id_Formation']." 
						";
					$resultFormPlat=mysqli_query($bdd,$reqPlat);
					$nbPlat=mysqli_num_rows($resultFormPlat);
					if($nbPlat>0){
						$rowFormPlat=mysqli_fetch_array($resultFormPlat);
						if($row['Recyclage']==0){$nbHeures=$rowFormPlat['Duree'];$nbJours=$rowFormPlat['NbJour'];}
						else{$nbHeures=$rowFormPlat['DureeRecyclage'];$nbJours=$rowFormPlat['NbJourRecyclage'];}
					}
					
					$DateDebut=0;
					$DateFin=0;
					$HeureDebut=0;
					$HeureFin=0;
					$req="SELECT DateSession, Heure_Debut, Heure_Fin 
					FROM form_session_date
					WHERE Id_Session=".$row['Id_Session']." 
					AND Suppr=0 ";
					$resultDate=mysqli_query($bdd,$req);
					$nbDate=mysqli_num_rows($resultDate);
					if($nbDate>0)
					{
						while($rowDate=mysqli_fetch_array($resultDate))
						{
							if($DateDebut<="0001-01-01" && $DateFin<="0001-01-01")
							{
								$DateDebut=$rowDate['DateSession'];
								$HeureDebut=$rowDate['Heure_Debut'];
								$HeureFin=$rowDate['Heure_Fin'];
								$DateFin=$rowDate['DateSession'];
							}
							else
							{
								if($rowDate['DateSession']<$DateDebut)
								{
									$DateDebut=$rowDate['DateSession'];
									$HeureDebut=$rowDate['Heure_Debut'];
								}
								if($rowDate['DateSession']>$DateFin)
								{
									$DateFin=$rowDate['DateSession'];
									$HeureFin=$rowDate['Heure_Debut'];
								}
							}
						}
					}
					$message_html.="<tr>";
					$message_html.="<td style='border:1px solid black;'>".$row['Nom']."</td>";
					$message_html.="<td style='border:1px solid black;'>".$row['Prenom']."</td>";
					$message_html.="<td style='border:1px solid black;'>".$Libelle."</td>";
					$message_html.="<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($DateDebut)."</td>";
					$message_html.="<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($DateFin)."</td>";
					$message_html.="<td style='border:1px solid black;'>".$nbHeures."</td>";
					$message_html.="<td style='border:1px solid black;'>".$nbJours."</td>";
					$message_html.="</tr>";
				}
			}
			$message_html.="</table>
					<br>
					<font color='red'>Attention : Aucune absence ne sera tolérée </font>
					<br>
					<br>
					Bonne journée.<br>
					Formation Extranet Daher industriel services DIS.
				</body>
			</html>";
		}
		else{
			$message_html="	<html>
				<head><title>Training reminder</title></head>
				<body>
					Bonjour,
					<br><br>
					<i>This mailbox is a generic mailbox</i>
					<br><br>
					Below the formations from ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateDebutRappel']))." to ".AfficheDateJJ_MM_AAAA(TrsfDate_($_GET['DateFinRappel']))."
					<br>
					<table style='border:1px solid black; border-spacing:0;'>
						<tr>
							<td style='border:1px solid black;' bgcolor='#A9A9A9'>Name</td>
							<td style='border:1px solid black;' bgcolor='#A9A9A9'>First name</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Training</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Start date</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>End date</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Number of hours</td>
							<td style='border:1px solid black;' bgcolor='#556B2F'>Number of days</td>
						</tr>\n";
			
				$req="
					SELECT form_session_personne.Id_Personne AS Id_Personne,
					form_session_personne.Id_Session AS Id_Session,
					form_session_personne.Validation_Inscription AS Validation_Inscription,
					(
						SELECT
							form_besoin.Id_Prestation
						FROM
							form_besoin
						WHERE form_besoin.Id=form_session_personne.Id_Besoin
					) AS Id_Prestation,
					(
						SELECT
							form_besoin.Id_Pole
						FROM
							form_besoin
						WHERE form_besoin.Id=form_session_personne.Id_Besoin
					) AS Id_Pole,
					(
						SELECT
							Nom
						FROM
							new_rh_etatcivil
						WHERE
							new_rh_etatcivil.Id=form_session_personne.Id_Personne
					) AS Nom,
					(
						SELECT
							Prenom
						FROM
							new_rh_etatcivil
						WHERE
							new_rh_etatcivil.Id=form_session_personne.Id_Personne
					) AS Prenom,
					form_session.Recyclage AS Recyclage,
					form_session.Id_Formation,
					(
						SELECT
							Id_Langue
						FROM
						form_formation_plateforme_parametres 
						WHERE
							form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
							AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
							AND Suppr=0 LIMIT 1
					) AS Id_Langue,
					(
						SELECT
						(
							SELECT
								Libelle
							FROM
								form_organisme
							WHERE
								Id=Id_Organisme
						)
						FROM
							form_formation_plateforme_parametres 
						WHERE
							form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
						AND Suppr=0 LIMIT 1
					) AS Organisme 
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
					AND form_session_personne.Validation_Inscription=1
					AND (
						SELECT
							form_formation.Id_TypeFormation
						FROM
							form_formation
						WHERE form_formation.Id=form_session.Id_Formation
					)=".$_GET['TypeFormationRappel']."
					AND (
						SELECT COUNT(form_session_date.Id)
						FROM form_session_date
						WHERE form_session_date.DateSession>='".$_GET['DateDebutRappel']."'
						AND form_session_date.DateSession<='".$_GET['DateFinRappel']."'
						AND form_session_date.Suppr=0 
						AND form_session_date.Id_Session=form_session.Id 
						)>0
					AND (
						SELECT COUNT(form_session_prestation.Id)
						FROM form_session_prestation
						WHERE (SELECT Id_Plateforme 
							FROM new_competences_prestation 
							WHERE new_competences_prestation.Id=form_session_prestation.Id_Prestation)=".$_GET['Id_Plateforme']."
						AND form_session_prestation.Id_Session=form_session.Id
						)>0
					AND (
						SELECT COUNT(Id)
						FROM new_competences_personne_prestation
						WHERE new_competences_personne_prestation.Id_Personne=form_session_personne.Id_Personne
						AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."' 
						AND new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
						AND new_competences_personne_prestation.Id_Prestation=".$rowPrestation['Id_Prestation']."
						AND new_competences_personne_prestation.Id_Pole=".$rowPrestation['Id_Pole']."
						)>0
				";
			$ResultPersonne=mysqli_query($bdd,$req);
			$NbPersonne=mysqli_num_rows($ResultPersonne);
			
			$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
						FROM form_formation_langue_infos 
						WHERE Suppr=0";
			$resultFormLangue=mysqli_query($bdd,$reqLangue);
			$nbFormLangue=mysqli_num_rows($resultFormLangue);

			if($NbPersonne>0){
				while($row=mysqli_fetch_array($ResultPersonne))
				{
					$Libelle="";
					if($nbFormLangue>0)
					{
						mysqli_data_seek($resultFormLangue,0);
						while($rowFormLangue=mysqli_fetch_array($resultFormLangue))
						{
							if($rowFormLangue['Id_Formation']==$row['Id_Formation'] && $rowFormLangue['Id_Langue']==$row['Id_Langue'] )
							{
								if($row['Recyclage']==0){$Libelle=stripslashes($rowFormLangue['Libelle']);}
								else
								{
									$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
									if($Libelle==""){$Libelle=stripslashes($rowFormLangue['Libelle']);}
								}
								if($row['Organisme']<>""){$Libelle.=" (".$row['Organisme'].")";}
							}
						}
					}
					
					$nbHeures="";
					$nbJours="";
					$reqPlat="SELECT NbJour, NbJourRecyclage, Duree, DureeRecyclage  
						FROM form_formation_plateforme_parametres
						WHERE Suppr=0
						AND Id_Plateforme=".$_GET['Id_Plateforme']."
						AND Id_Formation=".$row['Id_Formation']." 
						";
					$resultFormPlat=mysqli_query($bdd,$reqPlat);
					$nbPlat=mysqli_num_rows($resultFormPlat);
					if($nbPlat>0){
						$rowFormPlat=mysqli_fetch_array($resultFormPlat);
						if($row['Recyclage']==0){$nbHeures=$rowFormPlat['Duree'];$nbJours=$rowFormPlat['NbJour'];}
						else{$nbHeures=$rowFormPlat['DureeRecyclage'];$nbJours=$rowFormPlat['NbJourRecyclage'];}
					}
					
					$DateDebut=0;
					$DateFin=0;
					$HeureDebut=0;
					$HeureFin=0;
					$req="SELECT DateSession, Heure_Debut, Heure_Fin 
					FROM form_session_date
					WHERE Id_Session=".$row['Id_Session']." 
					AND Suppr=0 ";
					$resultDate=mysqli_query($bdd,$req);
					$nbDate=mysqli_num_rows($resultDate);
					if($nbDate>0)
					{
						while($rowDate=mysqli_fetch_array($resultDate))
						{
							if($DateDebut<="0001-01-01" && $DateFin<="0001-01-01")
							{
								$DateDebut=$rowDate['DateSession'];
								$HeureDebut=$rowDate['Heure_Debut'];
								$HeureFin=$rowDate['Heure_Fin'];
								$DateFin=$rowDate['DateSession'];
							}
							else
							{
								if($rowDate['DateSession']<$DateDebut)
								{
									$DateDebut=$rowDate['DateSession'];
									$HeureDebut=$rowDate['Heure_Debut'];
								}
								if($rowDate['DateSession']>$DateFin)
								{
									$DateFin=$rowDate['DateSession'];
									$HeureFin=$rowDate['Heure_Debut'];
								}
							}
						}
					}
					$message_html.="<tr>";
					$message_html.="<td style='border:1px solid black;'>".$row['Nom']."</td>";
					$message_html.="<td style='border:1px solid black;'>".$row['Prenom']."</td>";
					$message_html.="<td style='border:1px solid black;'>".$Libelle."</td>";
					$message_html.="<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($DateDebut)."</td>";
					$message_html.="<td style='border:1px solid black;'>".AfficheDateJJ_MM_AAAA($DateFin)."</td>";
					$message_html.="<td style='border:1px solid black;'>".$nbHeures."</td>";
					$message_html.="<td style='border:1px solid black;'>".$nbJours."</td>";
					$message_html.="</tr>";
				}
			}
			$message_html.="</table>
					<br>
					<font color='red'>Attention: No absence will be tolerated!</font>
					<br>
					<br>
					Have a good day.<br>
					Training Extranet Daher industriel services DIS.
				</body>
			</html>";
		}
		if($destinataire<>""){
			if(mail($destinataire,$sujet,$message_html,$Headers,'-f qualipso@aaa-aero.com'))
			{echo "Un message a été envoyé à ".$destinataire."\n";}
	
		}
		
	}
}

echo "<script>window.close();</script>";
?>
</body>
</html>