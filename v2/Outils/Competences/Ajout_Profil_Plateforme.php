<html>
<head>
	<title>Compétences - Profil personne - UER</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
		
		function AfficherDate()
		{
			if(document.getElementById('Plateforme').value=="14"){document.getElementById('sortie').style.display='';}
			else{document.getElementById('sortie').style.display='none';}
		}
		
		function VerifChamps()
		{
			if(document.getElementById('Plateforme').value=="14")
			{
				if(document.getElementById('DateSortie').value=="")
				{
					alert("Veuillez compléter la date de fin");
					return false;
				}
			}
			return true;
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

/**
 * RecupererListeFormations
 * 
 * Recupere les formations auquelles est inscrite la personne
 * 
 * @param int $Id_Personne Identifiant personne
 * @return resource la ressource des formations
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function RecupererListeFormations($Id_Personne) {
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
			AND form_session_date.Suppr=0
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription<>-1
			AND form_session_date.DateSession >= '".date('Y-m-d')."'
			AND form_session_personne.Id_personne = ".$Id_Personne.";";
    
    return getRessource($reqSessionFormation);
}

/**
 * RecupererDestinataires_InscriptionsEnZSORTIE
 * 
 * Recupere les destinataires 
 * 
 * @param resource $resPrestation Ressource des prestations de la personne
 * @param string $Email1 Une adresse email
 * @param string $Id_plateforme l'Id plateforme
 * @return string les emails destinataires
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 * 
 */
function RecupererDestinataires_InscriptionsEnZSORTIE($resPrestation, $Email1,$Id_Plateforme) {

    //Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
    $TousDestinataires = "";
	if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
		$TousDestinataires.= "cpere@aaa-aero.com,";
    }
	else{
		$TousDestinataires.="informatique_tls@aaa-aero.com,";
	}
    while($rowPresta = mysqli_fetch_array($resPrestation)) {
        $Destinataires="";
        if($Email1 != "")
            $Destinataires.= $Email1.",";
            $requeteResponsablePostePrestation="
            SELECT DISTINCT
                new_competences_personne_poste_prestation.Id_Poste,
                new_competences_personne_poste_prestation.Backup,
                CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom,
                new_rh_etatcivil.EmailPro, new_rh_etatcivil.Id
            FROM
                new_competences_personne_poste_prestation,
                new_rh_etatcivil
            WHERE
                new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
                AND new_competences_personne_poste_prestation.Id_Prestation=".$rowPresta['Id_Prestation']."
            ORDER BY
                new_competences_personne_poste_prestation.Id_Poste,
                new_competences_personne_poste_prestation.Backup ASC";
		$resultResponsablePostePrestation = getRessource($requeteResponsablePostePrestation);
        while($rowResponsablePostePrestation = mysqli_fetch_array($resultResponsablePostePrestation))
            switch($rowResponsablePostePrestation[0])
            {
                case 2 :
                case 3 :
                    if($rowResponsablePostePrestation[3] <> "" && strpos($Destinataires,$rowResponsablePostePrestation[3]) == false)
                        $Destinataires.= $rowResponsablePostePrestation[3].",";
                    break;
            }
        $TousDestinataires.= $Destinataires;
    }
	
	//Ajout des AF 
	$Destinataires="";
	$requeteResponsablePostePlateforme="
        SELECT DISTINCT
            new_competences_personne_poste_plateforme.Id_Poste,
            new_competences_personne_poste_plateforme.Backup,
            CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom,
            new_rh_etatcivil.EmailPro,
            
        FROM
            new_competences_personne_poste_plateforme,
            new_rh_etatcivil
        WHERE
            new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
            AND new_competences_personne_poste_plateforme.Id_Plateforme=".$Id_Plateforme."
        ORDER BY
            new_competences_personne_poste_plateforme.Id_Poste,
            new_competences_personne_poste_plateforme.Backup ASC";
	$resultResponsablePostePlateforme = getRessource($requeteResponsablePostePlateforme);
	while($rowResponsablePostePlateforme = mysqli_fetch_array($resultResponsablePostePlateforme)){
		switch($rowResponsablePostePlateforme[0])
		{
			case 17 :
			case 18 :
			case 19 :
				if($rowResponsablePostePlateforme[3] <> "" && strpos($Destinataires,$rowResponsablePostePlateforme[3]) == false)
					$Destinataires.= $rowResponsablePostePlateforme[3].",";
				break;
		}
	}
	$TousDestinataires.= $Destinataires;
	if($TousDestinataires<>""){
		$TousDestinataires=substr($TousDestinataires,0,strlen($TousDestinataires)-1);
	}
    return $TousDestinataires;
}

/**
 * construireMail_InscriptionsEnZSORTIE
 * 
 * Construit le mail
 * 
 * @param string $User Nom et prenom de l'utilisateur connecte
 * @param int $Id_Personne Identifinat de la personne qui passe en Z-SORTIE
 * @param resource $resFormations ressource des formations de la personne
 * @param resource $resPrestations ressource des prestations de la personne
 * @return string Le mail en format html
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function construireMail_InscriptionsEnZSORTIE($User, $Id_Personne, $resFormations, $resPrestations){

    $req = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id = ".$Id_Personne.";";
    $row = mysqli_fetch_array(getRessource($req));
    $Personne_Modifiee = $row['Nom']." ".$row['Prenom'];
    
    $StrMailhtml='<html><head><title>Départ de Daher industriel services DIS - '.$Personne_Modifiee.'</title></head>';
    $StrMailhtml.='<body>Bonjour,<br><br>';
    $StrMailhtml.='<table cellpadding="0" cellspacing="0" style="border:1px solid black;">';
    $StrMailhtml.='<tr><td style="border:1px solid black;">Personne concernée : </td><td style="border:1px solid black;">'.$Personne_Modifiee.'</td></tr>';
    
    
    $StrMailhtml.='<tr><td style="border:1px solid black;">Type : </td><td style="border:1px solid black;">Mise en Z-SORTIE</td></tr>';
    
    //Les prestations concernées
    while($r = mysqli_fetch_array($resPrestations)) {
        //La prestation
        $req = "SELECT Libelle AS Prestation WHERE Id = ".$r['Id'].";";
        $rowPresta = mysqli_fetch_array(getRessource($req));
        $StrMailhtml.='<tr><td style="border:1px solid black;">Prestation : </td><td style="border:1px solid black;">'.$rowPresta['Prestation'].'</td></tr>';
    
        $req = "SELECT
                    new_competences_pole.Libelle AS Pole,
                    Date_Debut,
                    Date_Fin
                FROM
                    new_competences_personne_prestation,
                    new_competences_pole
                WHERE
                    new_competences_pole.Id = new_competences_personne_prestation.Id_Pole
                    AND Id_Personne = ".$Id_Personne."
                    AND Id_Prestation = ".$r['Id'].";";
        $res = getRessource($req);
        $rowPole = mysqli_fetch_array($res);
        
        //Le pôle concerné par la prestation
        if(mysqli_num_rows($res) > 0)
          $StrMailhtml.='<tr><td style="border:1px solid black;">Pôle : </td><td style="border:1px solid black;">'.$rowPole['Pole'].'</td></tr>';
          
        $StrMailhtml.='<tr><td style="border:1px solid black;">Dates : </td><td style="border:1px solid black;">du '.$rowPole['Date_Debut'].' au '.$rowPole['Date_Fin'].'</td></tr>';
    }
    
    $StrMailhtml.='<tr><td style="border:1px solid black;">Modifié par : </td><td style="border:1px solid black;">'.$User.'</td></tr>';
    
    $StrMailhtml.='</table>';
    
    $StrMailhtml.='<br><br> Liste des inscriptions aux formations pour M.'.$Personne_Modifiee.'<br>';
    $StrMailhtml.='<table cellpadding="0" cellspacing="0" style="border:1px solid black;">';
    $StrMailhtml.='<tr>';
    $StrMailhtml.='		<td style="border:1px solid black;">';
    $StrMailhtml.='				<b>Intitulé de formation</b>';
    $StrMailhtml.='		</td>';
    $StrMailhtml.='		<td style="border:1px solid black;">';
    $StrMailhtml.='				<b>Date</b>';
    $StrMailhtml.='		</td>';
    $StrMailhtml.='</tr>';
    
    while($rowSessionFormation = mysqli_fetch_array($resFormations)) {
        $StrMailhtml.='<tr>';
        $StrMailhtml.='		<td style="border:1px solid black;">';
        $StrMailhtml.='				'.$rowSessionFormation['Reference'];
        $StrMailhtml.='		</td>';
        $StrMailhtml.='		<td style="border:1px solid black;">';
        $StrMailhtml.='				'.$rowSessionFormation['DateSession'];
        $StrMailhtml.='		</td>';
        $StrMailhtml.='</tr>';
    }
    
    $StrMailhtml.='</table>';
    $StrMailhtml.="<br><br><font color='ff0000' size='3'>Pensez à avertir les moyens généraux et le pôle informatique pour le transfert du matériel<br>Pensez à mettre à jour le tableau de polyvalence si besoin.</font><br>";
    $StrMailhtml.='<br><br>Bonne journée.</body></html>';

    return $StrMailhtml;
}

/**
 * Prevenir_AF_CE_InscriptionsEnZSORTIE
 * 
 * Préviens les AF et les CE qu\'une personne pre-inscrite ou 
 * inscrite en formation est passee en Z-SORTIE
 * 
 * @param int $Id_personne Identifiant de la personne
 * @param int $Id_Plateforme Identifiant de la plateforme
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function Prevenir_AF_CE_InscriptionsEnZSORTIE($Id_personne, $Id_Plateforme)
{
    //Récupère l'utilisateur courant
    $result=getRessource("SELECT Prenom, Nom, EmailPro FROM new_rh_etatcivil WHERE Id='".$_SESSION['Id_Personne']."'");
    $row=mysqli_fetch_array($result);
    $User=$row[0]." ".$row[1];
    $Email1=$row[2];
    
	$req = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id = ".$Id_personne.";";
    $row = mysqli_fetch_array(getRessource($req));
    $Personne_Modifiee = $row['Nom']." ".$row['Prenom'];
	
    //Récupère les prestations dont il appartient 
	
	$req = "SELECT Id_Prestation FROM new_competences_personne_prestation WHERE Id_Personne=".$_POST['Id_Personne']." AND Date_Fin>='".date('Y-m-d')."' ;";
	$resPrestations = getRessource($req);
	
    //Récupérer la lisite des formations
    $resFormations = RecupererListeFormations($Id_personne);
    
    //Est-ce que la personne est inscrite ou pré-inscrite en formation
    if(mysqli_num_rows($resFormations) > 0)
    {
        //Récupérer les destinataires
        $destinataires = RecupererDestinataires_InscriptionsEnZSORTIE($resPrestations, $Email1,$Id_Plateforme);
        
        //Le sujet
        $sujet = "Z-SORTIE ".$Personne_Modifiee;
        
        //Construire le mail
        $message_html = construireMail_InscriptionsEnZSORTIE($User, $Id_personne, $resFormations, $resPrestations);
        
        //Envoyer le mail
		if($destinataires<>"")
		{
			echo $destinataires;
			envoyerMail($destinataires, $sujet, "", $message_html);
        }
    }
}

/**
 * EnvoyerMail_Sortie
 * 
 * Préviens l'IT lors du passage d'une personne en Z-SORTIE
 * 
 * @param int $Id_personne Identifiant de la personne
 * @param date $DateSortie Date de départ de la société
 * 
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function EnvoyerMail_Sortie($Id_personne,$DateSortie)
{
	$req = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id = ".$Id_personne.";";
    $row = mysqli_fetch_array(getRessource($req));
    $Personne_Modifiee = $row['Nom']." ".$row['Prenom'];
	
	//Le sujet
	$sujet = "Départ de Daher industriel services DIS ".$Personne_Modifiee;
	
	$message_html='<html><head><title>Départ de Daher industriel services DIS - '.$Personne_Modifiee.'</title></head>
					<body>Bonjour,<br><br>
					
					'.$Personne_Modifiee.' a été déclaré en SORTIE sur l\'Extranet à partir du '.$DateSortie;

	envoyerMailExtranet("informatique.aaa@daher.com,o.milandou@daher.com", $sujet, "", $message_html);
}



Ecrire_Code_JS_Init_Date();
if($_POST)
{
	if($_POST['Plateforme']!="")
	{
		$Id_OldPlateforme=0;
		$req="SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$_POST['Id_Personne']." ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			$row=mysqli_fetch_array($result);
			$Id_OldPlateforme=$row['Id_Plateforme'];
		}
		if($_POST['Mode']=="Ajout")
		{
			$result=mysqli_query($bdd,"INSERT INTO new_competences_personne_plateforme (Id_Personne, Id_Plateforme) VALUES (".$_POST['Id_Personne'].",".$_POST['Plateforme'].")");;
		}
		elseif($_POST['Mode']=="Modif")
		{		    
			$result=mysqli_query($bdd,"UPDATE new_competences_personne_plateforme SET Id_Plateforme=".$_POST['Plateforme']." WHERE Id_Personne=".$_POST['Id_Personne']);
		}
		
		if($_POST['Plateforme']==14){ // Le cas du Z-SORTIE
			// Suppression des besoin set des B
			
			//Récupère les formations
			$req= "SELECT DISTINCT Id_Prestation, Id_Formation 
				FROM form_besoin
				WHERE Id_Personne = ".$_POST['Id_Personne']." ";
			$ressourceFormations=mysqli_query($bdd,$req);
			
			//Pour chaque formation
			while($rFormations = mysqli_fetch_array($ressourceFormations)) {
				//Supprimer les besoins
				$resBesoinsAffectes = Supprimer_BesoinsFormations($rFormations['Id_Prestation'], $rFormations['Id_Formation'],-1,$_POST['Id_Personne'], "Ajout_Profil_Plateforme");
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
			$result=mysqli_query($bdd,$req);

			$result=mysqli_query($bdd,"UPDATE new_competences_personne_prestation SET Date_Fin='".TrsfDate($_POST['DateSortie'])."' WHERE Id_Personne=".$_POST['Id_Personne']." AND Date_Debut<='".TrsfDate($_POST['DateSortie'])."' AND (Date_Fin<='0001-01-01' OR Date_Fin>'".TrsfDate($_POST['DateSortie'])."')");
			
			$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET EmailPro='', MetierPaie='', DateAncienneteCDI='0001-01-01', Contrat='' WHERE Id=".$_POST['Id_Personne']." ");
			
			//Suppression des droits de la personne 
			if($Id_OldPlateforme<>18 && $Id_OldPlateforme<>20){
				EnvoyerMail_Sortie($_POST['Id_Personne'],AfficheDateJJ_MM_AAAA(TrsfDate_($_POST['DateSortie'])));
			}
		}
	}

	if($_POST['Nouveau']=="oui")
		{
		echo "<script>opener.location.reload;window.close();</script>";
		}
	else
		{
			echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
		}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Mode']=="Modif")
		{
			$result=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$_GET['Id_Personne']." AND Id_Plateforme=".$_GET['Id_Plateforme']);
			$Ligne_Plateforme=mysqli_fetch_array($result);
		}
?>
	<form id="formulaire" method="POST" action="Ajout_Profil_Plateforme.php" class="None" onSubmit="return VerifChamps()">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<input type="hidden" name="Nouveau" value="<?php if(isset($_GET['Nouveau'])=="oui"){echo "oui";}?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
			<td>
				<select name="Plateforme" id="Plateforme" onchange="javascript:AfficherDate()">
				<?php
				$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_plateforme WHERE Inactif=0 ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value=".$row['Id'];
					if($_GET['Mode']=="Modif"){if($Ligne_Plateforme['Id_Plateforme']==$row['Id']){echo " selected";}}
					echo ">".$row['Libelle']."</option>";
				}
				?>
				</select>
			</td>
			<td><input class="Bouton" type="submit"
				<?php
					if($_GET['Mode']=="Modif"){
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					}
					else{
						if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
					}
				?>
			></td>
		</tr>
		<tr class="TitreColsUsers" id="sortie" style="display:none;">
			<td><?php if($LangueAffichage=="FR"){echo "Date de sortie";}else{echo "End date";}?> : </td>
			<td>
				<input type="date" name="DateSortie" id="DateSortie" size="10" value="">
			</td>
		</tr>
	</table>
	</form>
<?php
	}
	if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>