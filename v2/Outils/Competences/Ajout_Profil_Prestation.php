<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Profil personne - Prestation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
		
		function VerifChamps(NavigOk)
		{
			$debut = formulaire.Date_Debut.value;
			$fin = formulaire.Date_Fin.value;
			if(NavigOk !=1){
				var $tabDate = formulaire.Date_Debut.value.split("/");
				$debut = $tabDate[2]+'-'+$tabDate[1]+'-'+$tabDate[0] ;
				
				var $tabDate = formulaire.Date_Fin.value.split("/");
				$fin = $tabDate[2]+'-'+$tabDate[1]+'-'+$tabDate[0];
			}
			if(formulaire.Date_Debut.value==""){alert('La date de début doit être au format aaaa-mm-jj.');return false;}
			if(formulaire.Date_Fin.value==""){alert('La date de fin doit être au format aaaa-mm-jj.');return false;}
			if($debut > $fin){alert('La date de fin doit être superieure à la date de début.');return false;}
			
			return true;
		}
		function DemandeSuppression(question1,question2,question3,question4,debut1,debut2,fin1,fin2,Id_Prestation,Id_Personne,ModeProfil,Id_Pole){
			question="Voulez-vous également supprimer le planning \n"+question1+"\n"+question2+"\n"+question3+"\n"+question4+" ?";
			if(window.confirm(question)){
				window.location = "Supprime_Planning.php?Debut1="+debut1+"&Debut2="+debut2+"&Fin1="+fin1+"&Fin2="+fin2+"&Id_Prestation="+Id_Prestation+"&Id_Personne="+Id_Personne+"&ModeProfil="+ModeProfil+"&Id_Pole="+Id_Pole;
			}
		}
		Liste_Pole_Prestation = new Array();
		function Recharge_Liste_Pole()
		{
			var sel="";
			sel ="<select size='1' name='Id_Pole'>";
			var bExiste=0;
			for(var i=0;i<Liste_Pole_Prestation.length;i++)
			{
				if (Liste_Pole_Prestation[i][0]==document.getElementById('Id_Prestation').value)
				{
					if (Liste_Pole_Prestation[i][1]==document.getElementById('Id_Pole_Initial').value || Liste_Pole_Prestation[i][3]=="0"){
						bExiste=1;
						sel= sel + "<option value="+Liste_Pole_Prestation[i][1];
						if(Liste_Pole_Prestation[i][1]==document.getElementById('Id_Pole_Initial').value){sel = sel + " selected";}
						sel= sel + ">"+Liste_Pole_Prestation[i][2]+"</option>";
					}
				}
			}
			if(bExiste==0){
				sel = sel + "<option value='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
			}
			sel =sel + "</select>";
			document.getElementById('Pole').innerHTML=sel;
		}
	</script>
</head>
<body>

<?php
require("../ConnexioniSansBody.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

/**
 * supprimer_BesoinEtBprestation
 * 
 * Supprime les besoins en formation et les 'B' a partir de la prestation
 * 
 * @param int $Id_Prestation Identifiant de la prestation
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function supprimer_BesoinEtBprestation($Id_Prestation,$Id_Pole)
{
    //Suppression des besoins
    //$_GET['Id_Personne'] Identifiant de la personne

    //Récupération de toutes les prestations similaires de la personne
    //(Actuelle ou futur)
    $req = "
      SELECT *
      FROM new_competences_personne_prestation
      WHERE
          Id_Personne = ".$_POST['Id_Personne']."
      AND Id_Prestation = ".$Id_Prestation."
	  AND Id_Pole = ".$Id_Pole."
      AND Date_Fin >= NOW()
    ;";
    $res = getRessource($req);
    $nbMemePrestaFutur = mysqli_num_rows($res);
    
    if($nbMemePrestaFutur > 1)
        return;
    
    //Récupération des formations
    $resu = get_FormationsDeBesoinsPersonne($_POST['Id_Personne'], $Id_Prestation);
	
    while($row = mysqli_fetch_array($resu))
    {
        $Idformation = $row['Id_Formation'];
		
        //Suppression des besoins
        $resBesoinsAffectes = Supprimer_BesoinsFormations($Id_Prestation, $Idformation,$Id_Pole,$_POST['Id_Personne'], "Ajout_Profil_Prestation");
    }
	
	//Suppression des 'B' reliées à des besoins supprimés
	$req = "
		UPDATE new_competences_relation
		SET Suppr=1
		WHERE new_competences_relation.Suppr=0
		AND new_competences_relation.Id_Besoin>0
		AND Evaluation='B' 
		AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
			OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
		)
		AND Id_Besoin IN (
			SELECT form_besoin.Id FROM form_besoin 
			WHERE form_besoin.Suppr=1
		)";
	$result = getRessource($req);
}

if($_POST)
{
	if($_POST['Prestation']!="")
	{	    
		$Pole = "0";
		if (isset($_POST['Id_Pole'])){$Pole = $_POST['Id_Pole'];}
		if($_POST['Mode']=="Ajout")
		{
			$requete="INSERT INTO new_competences_personne_prestation (Id_Personne, Id_Prestation, Id_Pole, Date_Debut, Date_Fin) VALUES (".$_POST['Id_Personne'].",".$_POST['Prestation'].",".$_POST['Id_Pole'].",'".TrsfDate($_POST['Date_Debut'])."','".TrsfDate($_POST['Date_Fin'])."')";
		}
		else
		{
			$requete="UPDATE new_competences_personne_prestation SET Id_Prestation=".$_POST['Prestation'].", Id_Pole=".$Pole.", Date_Debut='".TrsfDate($_POST['Date_Debut'])."', Date_Fin='".TrsfDate($_POST['Date_Fin'])."' WHERE Id=".$_POST['Id'];
		}
		
		//Suppression des besoins de l'ancienne prestation
		if($_POST['Mode']<>"Ajout")
		{
			/*
			$Id=$_POST['Id'];
			$req="SELECT Date_Debut, Date_Fin, Id_Personne, Id_Prestation FROM new_competences_personne_prestation WHERE Id='".$Id."' ;";
			$resultSelect=mysqli_query($bdd,$req);
			$rowSelect=mysqli_fetch_array($resultSelect);*/
			
			//supprimer_BesoinEtBprestation($_POST['Prestation'],$Pole);// Suppression des besoins et des B
		}
		
		//Suppression des besoins si la date < à la date du jour
		if(TrsfDate_($_POST['Date_Fin'])<$DateJour)
		{
			supprimer_BesoinEtBprestation($_POST['Prestation'],$Pole);// Suppression des besoins et des B
		}
		
		//GESTION DES BESOINS EN FORMATIONS AUTOMATIQUEMENT CREES EN FONCTION DU METIER ET DE LA PRESTATION
		//#################################################################################################
		if(($_POST['oldDateFin']== "" || TrsfDate_($_POST['oldDateFin'])<$DateJour) && TrsfDate_($_POST['Date_Fin'])>=$DateJour)
		{
			$ResultMetierPersonne=Get_LesMetiersFutur($_POST['Id_Personne']);
			$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
			if($nbPersonnePrestation>0){
				while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
				{
					$Id_Metier_Personne=$Metier_Personne[0];
					$Motif="Changement de prestation";
					Creer_BesoinsFormations_PersonnePrestationMetier($_POST['Id_Personne'], $_POST['Prestation'], $Pole, $Id_Metier_Personne, $Motif, 0,0,-1);
				}
			}
			else{
				$ResultMetierPersonne=Get_LesMetiersNonFutur($_POST['Id_Personne']);
				$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
				if($nbPersonnePrestation>0){
					while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
					{
						$Id_Metier_Personne=$Metier_Personne[0];
						$Motif="Changement de prestation";
						Creer_BesoinsFormations_PersonnePrestationMetier($_POST['Id_Personne'], $_POST['Prestation'], $Pole, $Id_Metier_Personne, $Motif, 0,0,-1);
					}
				}
			}
		}
		//#################################################################################################
		
		//Envoie mail aux RH Toulouse
		//---------------------------
		$result=mysqli_query($bdd,"SELECT Prenom, Nom FROM new_rh_etatcivil WHERE Id=".$_POST['Id_Personne']);
		$row=mysqli_fetch_array($result);
		$Personne_Modifiee=$row[0]." ".$row[1];
		
		$Email1="";
		$result=mysqli_query($bdd,"SELECT Prenom, Nom, EmailPro FROM new_rh_etatcivil WHERE Login='".$_SESSION['Log']."'");
		$row=mysqli_fetch_array($result);
		$User=$row[0]." ".$row[1];
		$Email1=$row[2];
		
		$Pole="";
		if (isset($_POST['Id_Pole']))
		{
			$result=mysqli_query($bdd,"SELECT Libelle FROM new_competences_pole WHERE Id=".$_POST['Id_Pole']);
			$row=mysqli_fetch_array($result);
			$Pole=$row[0];
		}
		
		$result=mysqli_query($bdd,"SELECT Libelle,Id_Plateforme FROM new_competences_prestation WHERE Id=".$_POST['Prestation']);
		$row=mysqli_fetch_array($result);
		$Prestation=$row[0];
		
		//Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
		$Destinataires="";

		$requeteResponsablePostePrestation="
            SELECT DISTINCT
                new_competences_personne_poste_prestation.Id_Poste,
                new_competences_personne_poste_prestation.Backup,
                CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom,
                new_rh_etatcivil.EmailPro,
                new_rh_etatcivil.Id
            FROM
                new_competences_personne_poste_prestation,
                new_rh_etatcivil
            WHERE
                new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
                AND new_competences_personne_poste_prestation.Id_Prestation=".$_POST['Prestation']."
            ORDER BY
                new_competences_personne_poste_prestation.Id_Poste,
                new_competences_personne_poste_prestation.Backup ASC";
		$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
		while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
		{
			switch($rowResponsablePostePrestation[0])
			{
				case 2 : 
				case 3 : 
				case 4 : 
				case 5 : 
				case 6 : if($rowResponsablePostePrestation[3]<>"" && strpos($Destinataires,$rowResponsablePostePrestation[3])==false){$Destinataires.=$rowResponsablePostePrestation[3].",";}break;
			}
		}
		$Destinataires=substr($Destinataires,0,strlen($Destinataires)-1);
		
		if($row[1]==1)	//Plateforme de Toulouse
		{
			$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'." \n";
			$headers.='Content-Type: text/html; charset="iso-8859-1"'." \n";
			$message = '<html><head><title>Ajout ou modification d"affectation de prestation - '.$Personne_Modifiee.'</title></head>';
			$message.= '<body>Bonjour,<br><br>';
			$message.= '<table cellpadding="0" cellspacing="0" style="border:1px solid black;">';
			$message.= '<tr><td style="border:1px solid black;">Personne concernée : </td><td style="border:1px solid black;">'.$Personne_Modifiee.'</td></tr>';
			if($_POST['Mode']=="Ajout")
			{
				$message.='<tr><td style="border:1px solid black;">Type : </td><td style="border:1px solid black;">Nouvelle affectation</td></tr>';
			}
			else
			{
				$message.='<tr><td style="border:1px solid black;">Type : </td><td style="border:1px solid black;">Modification de l\'affectation ou de la date de fin de présence</td></tr>';
			}
			$message.='<tr><td style="border:1px solid black;">Prestation : </td><td style="border:1px solid black;">'.$Prestation.'</td></tr>';
			if($Pole<>"")
			{
				$message.='<tr><td style="border:1px solid black;">Pôle : </td><td style="border:1px solid black;">'.$Pole.'</td></tr>';
			}
			$message.='<tr><td style="border:1px solid black;">Dates : </td><td style="border:1px solid black;">du '.$_POST['Date_Debut'].' au '.$_POST['Date_Fin'].'</td></tr>';
			if($_POST['Mode']=="Ajout")
			{
				$message.='<tr><td style="border:1px solid black;">Ajouté par : </td><td style="border:1px solid black;">'.$User.'</td></tr>';
			}
			else
			{
				$message.='<tr><td style="border:1px solid black;">Modifié par : </td><td style="border:1px solid black;">'.$User.'</td></tr>';
			}
			$message.='</table>';
			
			//La liste des formations auquelles la personne est inscrite
			$reqSessionFormation = "
				SELECT
						DISTINCT form_formation.Reference, form_session_date.DateSession
				FROM
						form_session,
						form_formation,
						form_session_personne,
						form_session_date
				WHERE
						form_session.Id_Formation = form_formation.Id
						AND form_session.Id = form_session_date.Id_Session
						AND form_session.Id = form_session_personne.Id_session
						AND form_session.Suppr=0 
						AND form_session.Annule=0 
						AND form_session_date.Suppr=0 
						AND form_session_date.DateSession >= '".date('Y-m-d')."'
						AND form_session_personne.Id_personne = ".$_POST['Id_Personne'].";";
			
			$message.='<br><br> Liste des inscriptions aux formations pour M.'.$Personne_Modifiee.'<br>';
			$message.='<table cellpadding="0" cellspacing="0" style="border:1px solid black;">';
			$message.='<tr>';
			$message.='		<td style="border:1px solid black;">';
			$message.='				<b>Intitulé de formation</b>';
			$message.='		</td>';
			$message.='		<td style="border:1px solid black;">';
			$message.='				<b>Date</b>';
			$message.='		</td>';
			$message.='</tr>';
			$res = getRessource($reqSessionFormation);
			while($rowSessionFormation = mysqli_fetch_array($res))
			{
				$message.='<tr>';
				$message.='		<td style="border:1px solid black;">';
				$message.='				'.$rowSessionFormation['Reference'];
				$message.='		</td>';
				$message.='		<td style="border:1px solid black;">';
				$message.='				'.$rowSessionFormation['DateSession'];
				$message.='		</td>';
				$message.='</tr>';
			}
			$message.='</table>';	
			
			$message.="<br><br><font color='ff0000' size='3'>Pensez à avertir les moyens généraux et le pôle informatique pour le transfert du matériel<br>Pensez à mettre à jour le tableau de polyvalence si besoin.</font><br>";
			$message.='<br><br>Bonne journée.</body></html>';

			
			if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
			{
				if(mail($Destinataires, 'Ajout ou modification d"affectation de prestation - '.$Personne_Modifiee, $message, $headers,'-f extranet@aaa-aero.com')){echo 'Le message a été envoyé';}
				else{echo 'Le message n\'a pu être envoyé';}
			} 
			else
			{
			    if(mail($Destinataires, $_SERVER['SERVER_NAME'].' - Ajout ou modification d"affectation de prestation - '.$Personne_Modifiee, $message, $headers,'-f extranet@aaa-aero.com')){echo 'Le message a été envoyé';}
			    else{echo 'Le message n\'a pu être envoyé';}
			}
		}
		
		if($_POST['Mode']<>"Ajout")
		{	
			$Id=$_POST['Id'];
			$DateDebut=TrsfDate_($_POST['Date_Debut']);
			$DateFin=TrsfDate_($_POST['Date_Fin']);
			$req="SELECT Date_Debut, Date_Fin, Id_Personne, Id_Prestation, Id_Pole FROM new_competences_personne_prestation WHERE Id='".$Id."' ;";
			$resultSelect=mysqli_query($bdd,$req);
			$rowSelect=mysqli_fetch_array($resultSelect);
			$debut1 = 0;
			$debut2 = 0;
			$fin1 = 0;
			$fin2 = 0;
			if($DateDebut < $rowSelect['Date_Debut'])
			{
				if($DateFin < $rowSelect['Date_Debut'])
				{
					$debut1 = $rowSelect['Date_Debut'];
					$debut2 = $rowSelect['Date_Fin'];
				}
				elseif($DateFin > $rowSelect['Date_Debut'] && $DateFin < $rowSelect['Date_Fin'])
				{
					$tabDate = explode('-', $DateFin);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
					$debut1 = date('Y-m-d', $timestamp);
					$debut2 = $rowSelect['Date_Fin'];
				
				}
				elseif($DateFin == $rowSelect['Date_Debut'] && $rowSelect['Date_Debut'] < $rowSelect['Date_Fin'])
				{
					$tabDate = explode('-', $rowSelect['Date_Debut']);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
					$debut1 = date('Y-m-d', $timestamp);
					$debut2 = $rowSelect['Date_Fin'];
				}
			}
			elseif($DateDebut > $rowSelect['Date_Debut'] && $DateFin < $rowSelect['Date_Fin'])
			{
				$tabDate = explode('-', $DateDebut);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
				$debut1 = $rowSelect['Date_Debut'];
				$debut2 = date('Y-m-d', $timestamp);
				
				$tabDate = explode('-', $DateFin);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
				$fin1 = date('Y-m-d', $timestamp);
				$fin2 = $rowSelect['Date_Fin'];
			}
			elseif($DateDebut == $rowSelect['Date_Debut'] && $DateFin < $rowSelect['Date_Fin'])
			{
				$tabDate = explode('-', $DateFin);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
				$debut1 = date('Y-m-d', $timestamp);
				$debut2 = $rowSelect['Date_Fin'];
			}
			elseif($DateDebut > $rowSelect['Date_Debut'] && $DateFin == $rowSelect['Date_Fin'])
			{
				$tabDate = explode('-', $DateDebut);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
				$debut1 = $rowSelect['Date_Debut'];
				$debut2 = date('Y-m-d', $timestamp);
			}
			elseif($DateFin > $rowSelect['Date_Fin'])
			{
				if($DateDebut > $rowSelect['Date_Fin'])
				{
					$debut1 = $rowSelect['Date_Debut'];
					$debut2 = $rowSelect['Date_Fin'];
				}
				elseif($DateDebut < $rowSelect['Date_Fin'] && $DateDebut > $rowSelect['Date_Debut'])
				{
					$tabDate = explode('-', $DateDebut);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
					$debut1 = $rowSelect['Date_Debut'];
					$debut2 = date('Y-m-d', $timestamp);
				}
				elseif($DateDebut == $rowSelect['Date_Fin'] && $rowSelect['Date_Debut'] < $rowSelect['Date_Fin'])
				{
					$tabDate = explode('-', $rowSelect['Date_Fin']);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
					$debut1 = $rowSelect['Date_Debut'];
					$debut2 =date('Y-m-d', $timestamp);
				}
			}

			if($debut1 <> 0)
			{
				$reqPersonne="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$rowSelect['Id_Personne'];
				$result=mysqli_query($bdd,$reqPersonne);
				$LignePersonne=mysqli_fetch_array($result);
				$reqPresta="SELECT Libelle FROM new_competences_prestation WHERE Id=".$rowSelect['Id_Prestation'];
				$result=mysqli_query($bdd,$reqPresta);
				$LignePrestation=mysqli_fetch_array($result);
				$question1="de ".$LignePersonne['Nom']." ".$LignePersonne['Prenom']."";
				$question2="pour la prestation ".$LignePrestation['Libelle']." ";
				$question3="du ".$debut1." au ".$debut2." ";
				$tabDate = explode('-', $debut1);
				$tmpDebut1 = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$tabDate = explode('-', $debut2);
				$tmpDebut2 = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$tmpFin1 = 0;
				$tmpFin2 = 0;
				$question4="";
				if($fin1 <> 0)
				{
					$question4="et du ".$fin1." au ".$fin2." ";
					$tabDate = explode('-', $fin1);
					$tmpFin1 = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
					$tabDate = explode('-', $fin2);
					$tmpFin2 = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				}
				echo "<script>DemandeSuppression('".$question1."','".$question2."','".$question3."','".$question4."','".$tmpDebut1."','".$tmpDebut2."','".$tmpFin1."','".$tmpFin2."','".$rowSelect['Id_Prestation']."','".$rowSelect['Id_Personne']."','".$_POST['ModeProfil']."','".$rowSelect['Id_Pole']."');</script>";
			}
		}
		$result=mysqli_query($bdd,$requete);
	}
	echo "<script>alert('Pensez à avertir les moyens généraux et le pôle informatique pour le transfert du matériel !');</script>";
		
	echo "<script>FermerEtRecharger('Profil.php?Mode=".$_POST['ModeProfil']."&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta")
	{
		if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta")
		{
			$Prestation=mysqli_query($bdd,"SELECT * FROM new_competences_personne_prestation WHERE Id=".$_GET['Id']);
			$LignePrestation=mysqli_fetch_array($Prestation);
		}
?>

<?php Ecrire_Code_JS_Init_Date();?>

	<form id="formulaire" method="POST" action="Ajout_Profil_Prestation.php" onSubmit="return VerifChamps('<?php echo $NavigOk;?>');" class="None">
	<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta"){echo $_GET['Id'];}?>">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="ModeProfil" value="<?php echo $_GET['ModeProfil']; ?>">
	<input type="hidden" name="oldDateFin" value="<?php if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta"){echo AfficheDateFR($LignePrestation['Date_Fin']);} ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<input type="hidden" id="Id_Pole_Initial" value="<?php if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta"){echo $LignePrestation['Id_Pole'];}else{echo "0";}?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?> : </td>
			<td>
				<?php
				$requete="
                    SELECT
                        new_competences_prestation.Id,
                        new_competences_prestation.Libelle AS Prestation,
                        new_competences_plateforme.Libelle,
                        new_competences_prestation.Active
                    FROM
                        new_competences_prestation,
                        new_competences_plateforme
                    WHERE
                        new_competences_prestation.Id_Plateforme IN
                            (SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$_GET['Id_Personne'].")
                        AND new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
                    ORDER BY
                        new_competences_plateforme.Libelle ASC,
                        new_competences_prestation.Libelle ASC";
				$result=mysqli_query($bdd,$requete);
				$nbResulta=mysqli_num_rows($result);
                $nbPrestaAAfficher=0;
				if($nbResulta>0)
				{
				    echo "
                        <select id='Id_Prestation' name='Prestation' onchange='Recharge_Liste_Pole();'>
					       <option value=''>&nbsp;</option>";
				    
					while($row=mysqli_fetch_array($result))
					{
						$PrestationAffiche=false;
						if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta")
						{
							if($LignePrestation['Id_Prestation']==$row[0]){$PrestationAffiche=true;}
						}
						elseif($_GET['Mode']=="Ajout" && $row['Active']==0){$PrestationAffiche=true;}
						
						if($PrestationAffiche)
						{
						    $nbPrestaAAfficher++;
							echo "<option value='".$row['Id']."'";
							if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta"){if($LignePrestation['Id_Prestation']==$row[0]){echo " selected";}}
							echo ">".$row[2]." # ".$row[1]."</option>";
						}
					}
					echo "</select>";
				}
				if($nbPrestaAAfficher==0)
				{
				    if($LangueAffichage=="FR")
				    {
				        echo "<br>Vous ne pouvez pas modifier les dates ou cette prestation car la personne n'est plus affectée à l'unité d'exploitation dont la prestation dépend.<br>
                        Pour pouvoir effectuer des modifications, vous devez d'abord rajouter l'unité d'exploitation concernée dans le profil de la personne.";
				    }
				    else
				    {
				        echo "<br>You can't change dates or this activity because the person is no longer assigned to the operating unit whose activity depends. <br>
                         In order to make changes, you must first add the relevant operating unit in the person's profile.";
				    }
				}
				?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Pôle";}else{echo "Pole";}?> : </td>
				<td>
					<div id="Pole">
						<select size="1" name="Id_Pole"></select>
					</div>
					<?php
					$requete_Pole="SELECT Id_Prestation, Id, Libelle, Actif FROM new_competences_pole ORDER BY Libelle ASC";
					$result_Pole= mysqli_query($bdd,$requete_Pole) or die ("Select impossible");
					$i=0;
					while ($row_Pole=mysqli_fetch_row($result_Pole))
					{
						$PoleAffiche=false;
						if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta")
						{
							if($LignePrestation['Id_Pole']==$row_Pole[1]){$PoleAffiche=true;}
						}
						elseif($_GET['Mode']=="Ajout" && $row_Pole[3]==0){$PoleAffiche=true;}
						
						if($PoleAffiche)
						{
							 echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$row_Pole[0].",".$row_Pole[1].",'".addslashes($row_Pole[2])."',".$row_Pole[3].");</script>";
							 $i+=1;
						}
					}
					?>
				</td>
			</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> :</td>
			<td>
				<input type="date" name="Date_Debut" size="10" value="<?php if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta"){echo AfficheDateFR($LignePrestation['Date_Debut']);} ?>">
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> :</td>
			<td>
				<input type="date" name="Date_Fin" size="10" value="<?php if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta"){echo AfficheDateFR($LignePrestation['Date_Fin']);} ?>">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input class="Bouton" type="submit"
				<?php
					if($_GET['Mode']=="Modif" || $_GET['Mode']=="ModifPresta")
					{
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					}
					else
					{
						if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
					}
				?>
			></td>
		</tr>
	</table>
	</form>
<?php
	}
	if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	echo "<script>Recharge_Liste_Pole();</script>";
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>