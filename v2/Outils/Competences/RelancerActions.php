<?php
session_start();
require("../ConnexioniSansBody.php");
require("../Fonctions.php");

$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
$headers.='Content-Type: text/html; charset="UTF-8"'."\n";

$req = "SELECT Id,DateCreation, Action, Delai,Commentaire, ";
$req .= "DateRelance, Avancement, DateSolde,DateReport, ";
$req .= "(SELECT new_commercial_plateforme.Libelle FROM new_commercial_plateforme WHERE new_commercial_plateforme.Id = new_commercial_action.Id_PlateformeCom) AS Plateforme, ";
$req .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_commercial_action.Id_Emetteur) AS Emetteur, ";
$req .= "(SELECT new_rh_etatcivil.EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_commercial_action.Id_Emetteur) AS EmailEmetteur ";
$req .= "FROM new_commercial_action ";
$req .= "WHERE DateRelance>'0001-01-01'  AND DateRelance<='".date('Y-m-d')."' AND DateSolde>'0001-01-01'  AND RelanceFaite=0;";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
if ($nb > 0){
	while($row=mysqli_fetch_array($result)){
		$Destinataires ="";
		//---------Adresse de l'emetteur--------//
		if($row['EmailEmetteur']<>""){
			$Destinataires .= $row['EmailEmetteur'].",";
		}
		
		//---------Adresses des acteurs--------//
		$reqActeur="SELECT DISTINCT ";
		$reqActeur.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_commercial_action_acteur.Id_Personne) AS Acteur, ";
		$reqActeur.= "(SELECT new_rh_etatcivil.EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_commercial_action_acteur.Id_Personne) AS EmailActeur ";
		$reqActeur.="FROM new_commercial_action_acteur WHERE new_commercial_action_acteur.Id_ActionCom=".$row['Id'];
		$acteurs="";
		$resultActeur=mysqli_query($bdd,$reqActeur);
		$nbActeur=mysqli_num_rows($resultActeur);
		if($nbActeur > 0){
			while($rowActeur=mysqli_fetch_array($resultActeur)){
				if($rowActeur['EmailActeur']<>""){
					$Destinataires.=$rowActeur['EmailActeur'].", ";
				}
				$acteurs.=$rowActeur['Acteur'].", ";
			}
		}
		if($acteurs<>""){$acteurs = substr($acteurs,0,-2);}
		
		//Plateforme
		$reqActeur="SELECT DISTINCT ";
		$reqActeur.="(SELECT new_commercial_plateforme.Libelle FROM new_commercial_plateforme WHERE new_commercial_plateforme.Id=new_commercial_action_acteur.Id_PlateformeCom ) AS Plateforme ";
		$reqActeur.="FROM new_commercial_action_acteur WHERE new_commercial_action_acteur.Id_ActionCom=".$row['Id'];
		$plateformes="";
		$resultActeur=mysqli_query($bdd,$reqActeur);
		$nbActeur=mysqli_num_rows($resultActeur);
		if($nbActeur > 0){
			while($rowActeur=mysqli_fetch_array($resultActeur)){
				$plateformes.=$rowActeur['Plateforme'].", ";
			}
		}
		if($plateformes<>""){$plateformes = substr($plateformes,0,-2);}
		
		$objetMail="Relance de l'action n° ".$row['Id']." - ".$plateformes;
		
		$message='<html>';
		$message.='<head>';
		$message.='<title>Action</title><meta name="robots" content="noindex">';
		$message.='</head><body>Bonjour,<br/><br/>';
		
		$message.="<table width='90%' cellpadding='0' cellspacing='0'>";
			$message.="<tr><td>Relance de l'action n° ".$row['Id']."</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Action : ".utf8_encode($row['Action'])."<br/></td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Commentaire : ".utf8_encode(stripslashes($row['Commentaire']))."<br/></td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Emetteur : ".$row['Emetteur']."</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Acteurs : ".$acteurs." de ".$plateformes."</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Délai : ".AfficheDateFR($row['Delai'])."</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Date report : ".AfficheDateFR($row['DateReport'])."</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td>Avancement : ".utf8_encode(stripslashes($row['Avancement']))."</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td height='20'><br/><br/></td></tr>";
			$message.="<tr><td>L'équipe commerciale de la Plateforme de Toulouse</td></tr>";
			$message.="<tr><td height='8'><br/><br/></td></tr>";
			$message.="<tr><td><i>Pour toutes demandes de modifications des actions, merci de bien vouloir vous adresser à Justine Larroque ( jlarroque@aaa-aero.com )<i></td></tr>";
		$message.="</table> \n";
		$message.='</body></html>';
		//if(mail($Destinataires, $objetMail ,$message , $headers,'-f extranet@aaa-aero.com')){echo '';}
		//else{echo 'Le message n\'a pu être envoyé';}
		
		//$req="UPDATEew_commercial_action SET RelanceFaite=1 WHERE Id=".$row['Id'];
		//$resultADD=mysqli_query($bdd,$req);
	}
}
?>