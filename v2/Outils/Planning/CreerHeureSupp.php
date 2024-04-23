<?php
session_start();	//require("../VerifPage.php");
require("../ConnexioniSansBody.php");
?>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<script>
		function FermerEtRecharger(Id_Prestation,uneDate,Id_Pole,Tri)
		{
			opener.location.href="Planning.php?Id_Prestation="+Id_Prestation+"&uneDate="+uneDate+"&Id_Pole="+Id_Pole+"&Tri="+Tri;
			window.close();
		}
	</script>
</head>
<body>

<?php
	$NbHeures =  $_GET['nbHeures'];
	$Id_Prestation =  $_GET['Id_Prestation'];
	$Id_Personne =  $_GET['Id_Personne'];
	$Id_Createur =  $_GET['Id_Createur'];
	$tabDates =  explode(',', $_GET['dates2']);
	$NomFormation = "Formation : ".$_GET['NomFormation'];
	$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));	
	
	//#################
	//##### EMAIL #####
	//#################
	//Récupération de l'email de la personne qui poste la demande d'heure supplémentaire
	$Email1="";
	$requete_user="SELECT EmailPro FROM new_rh_etatcivil WHERE Id='".$Id_Createur."'";
	$result_user=mysqli_query($bdd,$requete_user);
	$row_user=mysqli_fetch_array($result_user);
	$Email1=$row_user[0];
	
	//Récupération de la personne qui valide l'heure supplémentaire
	$PersonneLoguee="";
	$requete_PersonneLoguee="SELECT CONCAT(Nom,' ',Prenom) as NomPrenom, Login FROM new_rh_etatcivil WHERE Id='".$Id_Createur."'";
	$result_PersonneLoguee=mysqli_query($bdd,$requete_PersonneLoguee);
	$row_PersonneLoguee=mysqli_fetch_array($result_PersonneLoguee);
	$PersonneLoguee=$row_PersonneLoguee['NomPrenom'];
	$PersonneLogin=$row_PersonneLoguee['Login'];
	
	//Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
	$Email2="";
	$Email3="";
	$Email4="";
	$PersonneConnectee_IdPosteMaxSurPrestation=0;
	$requeteResponsablePostePrestation="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_rh_etatcivil.EmailPro, new_rh_etatcivil.Id";
	$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
	$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
	$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$Id_Prestation;
	if($_GET['Pole']>0){
		$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$_GET['Pole'];
	}
	$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
	$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
	while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation)){
		//Récupération de la valeur du poste le plus haut sur cette prestation de la personne connectée
		if($rowResponsablePostePrestation['Id']==$Id_Createur && $rowResponsablePostePrestation['Id']>$PersonneConnectee_IdPosteMaxSurPrestation){
			$PersonneConnectee_IdPosteMaxSurPrestation=$rowResponsablePostePrestation['Id_Poste'];
		}
		
		switch($rowResponsablePostePrestation['Id_Poste'])
		{
			case 2: if($rowResponsablePostePrestation['EmailPro']<>""){$Email2.=$rowResponsablePostePrestation['EmailPro'].",";}break;
			case 3: if($rowResponsablePostePrestation['EmailPro']<>""){$Email3.=$rowResponsablePostePrestation['EmailPro'].",";}break;
			case 4: if($rowResponsablePostePrestation['EmailPro']<>""){$Email4.=$rowResponsablePostePrestation['EmailPro'].",";}break;
		}
	}
	$Email2=substr($Email2,0,strlen($Email2)-1);
	$Email3=substr($Email3,0,strlen($Email3)-1);
	$Email4=substr($Email4,0,strlen($Email4)-1);
	
	//4 étant le responsable Affaire : la validation des heures supplémentaire ne va pas plus loin
	if($PersonneConnectee_IdPosteMaxSurPrestation>4){$PersonneConnectee_IdPosteMaxSurPrestation=4;}
	
	//Remplissage des différentes informations à inclure dans le mail
	$Destinataires="";
	$DestinatairesEnCopie="";

	$Etat="Demandée pour validation";
	$Commentaire="";
	$Destinataires=$Email2;
	if($Email1 <> ""){$Destinataires.=",".$Email1;}
	if($PersonneConnectee_IdPosteMaxSurPrestation>=2){$Destinataires.=",".$Email3;}
	if($PersonneConnectee_IdPosteMaxSurPrestation>=3){$Destinataires.=",".$Email4;}
	if($PersonneConnectee_IdPosteMaxSurPrestation==4){
		$Etat="Validée";
		 $DestinatairesEnCopie="";
		//$DestinatairesEnCopie="extranet@aaa-aero.com";
	}

	//-------EN MODE AJOUT-------
	//###########################
	foreach($tabDates as $ldate)
	{
		$requete="INSERT INTO new_rh_heures_supp ";
		$requete.="(Id_Prestation,Id_Pole,Id_Personne,Nb_Heures_Jour,Nb_Heures_Nuit,Date,Motif,Login1,Date1";
		//Validation automatiquement rempli si le valideur est responsable du/des niveau(x) au dessus
		$requetesuite="";
		if($PersonneConnectee_IdPosteMaxSurPrestation>1)
		{
			for($j=1+1;$j<=$PersonneConnectee_IdPosteMaxSurPrestation;$j++)
			{
				$requete.=",Login".$j.",Date".$j.",Etat".$j.",Commentaire".$j."";
				$requetesuite.=",'".$PersonneLogin."','".$DateJour."','Validée','Validée automatiquement car responsable identique au demandeur'";
			}
		}
		$requete.=") VALUES ";
		$dateHS = $ldate;
		$requete.=" ('".$Id_Prestation."','".$_GET['Pole']."','".$Id_Personne."','".$NbHeures."','0','".$dateHS."','".addslashes($NomFormation)."','".$PersonneLogin."','".$DateJour."'";
		$requete.=$requetesuite;
		$requete.=")";
		$result=mysqli_query($bdd,$requete);
	}
	
	//Récupération du libellé du site
	$requete_Site="SELECT Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation;
	$result_Site=mysqli_query($bdd,$requete_Site);
	$row_Site=mysqli_fetch_array($result_Site);
	$Site=$row_Site[0];
	
	//Récupération du libellé du pole
	$requete_Pole="SELECT Libelle FROM new_competences_pole WHERE Id=".$_GET['Pole'];
	$result_Pole=mysqli_query($bdd,$requete_Pole);
	$nbPole=mysqli_num_rows($result_Pole);
	$Pole="";
	if($nbPole>0){
		$row_Pole=mysqli_fetch_array($result_Pole);
		$Pole=$row_Pole['Libelle'];
	}
	
	//Boucle sur chacune des personnes
	foreach($tabDates as $ldate)
	{
		$NOMPrenom="";
		$requete_NOMPrenom="SELECT CONCAT(Nom,' ',Prenom) AS NOMPrenom FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
		$result_NOMPrenom=mysqli_query($bdd,$requete_NOMPrenom);
		$row_NOMPrenom=mysqli_fetch_array($result_NOMPrenom);
		$NOMPrenom=$row_NOMPrenom[0];
		$dateHS = $ldate;
		
		$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
		$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
		if($DestinatairesEnCopie!=""){$headers .='Cc: '.$DestinatairesEnCopie."\n";}
		$message='<html><head><title>Heures Supplémentaires - Extranet V2 - '.$Etat.'</title></head><body>Bonjour,<br><br>';
		$message.='La demande d\'heures supplémentaires suivante a été '.$Etat.' par '.$PersonneLoguee;
		$message.='<br><table border=1><tr><td>Site/Prestation payeur</td><td>Personne concernée</td><td>Date</td><td>Nb H Jour</td><td>Nb H nuit</td><td>Motif</td>';
		$message.='<tr><td>'.substr($Site,0,4).'</td>';
		if($Pole <> ""){
			$message.=' - '.$Pole.' ';
		}
		$message.='</td>';
		$message.='<td>'.$NOMPrenom.'</td>';
		$message.='<td>'.$dateHS.'</td>';
		$message.='<td>'.addslashes($NbHeures).'</td>';
		$message.='<td>0</td>';
		$message.='<td>'.addslashes($NomFormation).'</td>';
		$message.='</tr></table>';
		if(1<4 && $PersonneConnectee_IdPosteMaxSurPrestation<4){$message.='<br>Veuillez vous rendre sur le site extranet AAA afin de la valider ou de la refuser';}
		$message.='<br><br>Bonne journée.<br><a href="https://extranet.aaa-aero.com">Extranet</a></body></html>';
		$objetMail="Heures Supplémentaires - Extranet V2";
		$objetMail.=" - ".substr($Site,0,4);
		$objetMail.=" - ".$NOMPrenom;
		$objetMail.=" - ".$dateHS;
		$objetMail.=" - ".$NbHeures.' HJ/ '."0 HN";
		$objetMail.=" - ".$Etat;
		if(mail($Destinataires, $objetMail, $message, $headers,'-f extranet@aaa-aero.com')){
		}
	}
	echo "<script>FermerEtRecharger('".$Id_Prestation."','".$_GET['dateRenvoi']."','".$_GET['Pole']."','".$_GET['Tri']."');</script>";
?>