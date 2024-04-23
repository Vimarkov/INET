<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Convocation à la formation</title><meta name="robots" content="noindex">
</head>
<body>

<?php
if($_GET)
{
	//Récupération des informations liées à la session
	$ResultSession=get_session($_GET['Id']);
	$RowSession=mysqli_fetch_array($ResultSession);
	
	$req="SELECT form_session.Id
		FROM form_session 
		LEFT JOIN form_formation 
		ON form_session.Id_Formation=form_formation.Id
		WHERE form_formation.Id_TypeFormation<>3 
		AND form_session.Suppr=0
		AND form_session.Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION']." ";
	$ResultTest=mysqli_query($bdd,$req);
	$NbTest=mysqli_num_rows($ResultTest);
	
	if($RowSession['ID_GROUPE_SESSION']>0 && $RowSession['FORMATION_LIEE']==1 && $NbTest==0)
	{
		$GroupeFormation="";
		$req="SELECT Libelle 
			FROM form_groupe_formation 
			WHERE Id=(
					SELECT form_session_groupe.Id_GroupeFormation 
					FROM form_session_groupe 
					WHERE form_session_groupe.Id=".$RowSession['ID_GROUPE_SESSION'].") ";
					
		$ResultGroupeSession=mysqli_query($bdd,$req);
		$RowGroupeSession=mysqli_fetch_array($ResultGroupeSession);
		$GroupeFormation=$RowGroupeSession['Libelle'];
		
		$req=" 
			SELECT DateSession AS DateDebut, Heure_Debut AS HEURE_DEBUT
			FROM form_session_date 
			LEFT JOIN form_session 
			ON form_session_date.Id_Session=form_session.Id
			WHERE form_session_date.Suppr=0
			AND form_session.Suppr=0
			AND Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION']." 
			AND Formation_Liee=1
			ORDER BY DateSession ASC, Heure_Debut ASC
			";
		$ResultInfos=mysqli_query($bdd,$req);
		$RowInfosD=mysqli_fetch_array($ResultInfos);

		$req=" 
			SELECT DateSession AS DateFin, Heure_Fin AS HEURE_FIN
			FROM form_session_date 
			LEFT JOIN form_session 
			ON form_session_date.Id_Session=form_session.Id
			WHERE form_session_date.Suppr=0
			AND form_session.Suppr=0
			AND Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION']." 
			AND Formation_Liee=1
			ORDER BY DateSession DESC, Heure_Fin DESC
			";
		$ResultInfos=mysqli_query($bdd,$req);
		$RowInfosF=mysqli_fetch_array($ResultInfos);
		
		if($LangueAffichage=="FR")
		{
			$ObjetConvocation=
				"Convocation au groupe de formation ".$GroupeFormation." du ".AfficheDateJJ_MM_AAAA($RowInfosD['DateDebut'])." au ".AfficheDateJJ_MM_AAAA($RowInfosF['DateFin']);
			$Convocation=
				"Convocation au groupe de formation "
				.$GroupeFormation."
				du ".AfficheDateJJ_MM_AAAA($RowInfosD['DateDebut'])." au ".AfficheDateJJ_MM_AAAA($RowInfosF['DateFin'])." de ".$RowInfosD['HEURE_DEBUT']." à ".$RowInfosF['HEURE_FIN'];
		}
		else
		{
			$ObjetConvocation=
				"Convocation to the training group ".$GroupeFormation." from ".AfficheDateJJ_MM_AAAA($RowInfosD['DateDebut'])." to ".AfficheDateJJ_MM_AAAA($RowInfosF['DateFin']);
			$Convocation=
				"Convocation to training group "
				.$GroupeFormation."
				from ".AfficheDateJJ_MM_AAAA($RowInfosD['DateDebut'])." to ".AfficheDateJJ_MM_AAAA($RowInfosF['DateFin'])." from ".$RowInfosD['HEURE_DEBUT']." to ".$RowInfosF['HEURE_FIN'];
		}
		
		$formations="<br>";
		//Liste des formations concernées 
		$req = "SELECT
				form_session.Id,
				form_formation.Reference AS FORMATION_REFERENCE,
				form_session.Id_Formation AS ID_FORMATION,
				(SELECT form_lieu.Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS LIEU,
				(SELECT form_lieu.Adresse FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS LIEU_ADRESSE,
				form_session.Recyclage AS RECYCLAGE
			FROM
				form_formation,
				form_session
			WHERE
				form_formation.Id=form_session.Id_Formation
				AND form_session.Suppr=0
				AND form_session.Id_GroupeSession = ".$RowSession['ID_GROUPE_SESSION']." ";

		$ResultForm=mysqli_query($bdd,$req);
		while($RowForm=mysqli_fetch_array($ResultForm))
		{
			$formation=$RowForm['FORMATION_REFERENCE'];
			$req="SELECT Libelle, LibelleRecyclage
					FROM form_formation_langue_infos
					WHERE Id_Formation=".$RowForm['ID_FORMATION']."
					AND Id_Langue=
						(SELECT Id_Langue 
						FROM form_formation_plateforme_parametres 
						WHERE Id_Plateforme=".$_GET['Id_Plateforme']."
						AND Id_Formation=".$RowForm['ID_FORMATION']."
						AND Suppr=0 
						LIMIT 1)
					AND Suppr=0";
			$ResultFormation=mysqli_query($bdd,$req);
			$nbFormation=mysqli_num_rows($ResultFormation);
			if($nbFormation>0)
			{
				$RowFormation=mysqli_fetch_array($ResultFormation);
				if($RowSession['RECYCLAGE']==0){$formation=$RowFormation['Libelle'];}
				else{$formation=$RowFormation['LibelleRecyclage'];}
			}
			
			$ResultSessionPropre=get_session($RowForm['Id']);
			$RowSessionPropre=mysqli_fetch_array($ResultSessionPropre);
			
			//$formations.="- ".$formation." : ".AfficheDateJJ_MM_AAAA($RowInfosD['DateDebut'])." (".$RowInfosD['HEURE_DEBUT'].") - ".AfficheDateJJ_MM_AAAA($RowInfosF['DateFin'])." (".$RowInfosF['HEURE_FIN'].") <br>";
			$formations.="- ".$formation." : ".AfficheDateJJ_MM_AAAA($RowSessionPropre['DATE_DEBUT'])." (".$RowSessionPropre['HEURE_DEBUT'].")
                          - ".AfficheDateJJ_MM_AAAA($RowSessionPropre['DATE_FIN'])." (".$RowSessionPropre['HEURE_FIN'].") ";
			if($LangueAffichage=="FR")
			{
			    $formations.="aura lieu à ".$RowSessionPropre['LIEU']."(".$RowSessionPropre['LIEU_ADRESSE'].") de ".$RowSessionPropre['HEURE_DEBUT']." à ".$RowSessionPropre['HEURE_FIN']."<BR>";
			}
			else
			{
			    $formations.="will take place ".$RowSessionPropre['LIEU']."(".$RowSessionPropre['LIEU_ADRESSE'].") from ".$RowSessionPropre['HEURE_DEBUT']." to ".$RowSessionPropre['HEURE_FIN']."<BR>";
			}
		}
		
		//Définition d'une tableau contenant les ID_SESSION_PERSONNE
		$TableauIdSessionPersonnesConvoquees=array();
			
		//Trier dans un tableau les personnes de la session de formation par prestation
		//-----------------------------------------------------------------------------
		//Comme suit
		// |ID_PRESTATION|PRESTATION|INFORMATIONS PERSONNES|
		//                          |INFORMATIONS PERSONNES|
		//                          |INFORMATIONS_PERSONNES|
		// |ID_PRESTATION|PRESTATION|INFORMATIONS PERSONNES|
		//							|INFORMATIONS_PERSONNES|
		
		$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($_GET['Id']));
		$TableauPersonnesParPrestation=array();
		
		$personnesInscrites="";
		while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes))
		{
			if($RowSessionPersonnes['VALIDATION_INSCRIPTION']==1 && $RowSessionPersonnes['CONVOCATION_ENVOYEE']==0)
			{
				
				$TrouvePrestation=false;
				for($i=0;$i<count($TableauPersonnesParPrestation);$i++)
				{
					if($TableauPersonnesParPrestation[$i][0]==$RowSessionPersonnes['ID_PRESTATION'] && $TableauPersonnesParPrestation[$i][3]==$RowSessionPersonnes['ID_POLE'])
					{
						$TrouvePrestation=true;
						$TableauPersonnesParPrestation[$i][2][]=array($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']);
						$TableauIdSessionPersonnesConvoquees[]=$RowSessionPersonnes['ID'];
						break;
					}
				}
				if(!$TrouvePrestation)
				{
					$TableauPersonnesParPrestation[]=array($RowSessionPersonnes['ID_PRESTATION'],$RowSessionPersonnes['PRESTATION'],array(array($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['STAGIAIRE_NOMPRENOM'])),$RowSessionPersonnes['ID_POLE']);
					$TableauIdSessionPersonnesConvoquees[]=$RowSessionPersonnes['ID'];
				}
				if($RowSessionPersonnes['EmailPro']<>""){
					$personnesInscrites.=",".$RowSessionPersonnes['EmailPro'];
				}
			}
		}
		
		//Mise à jour des états Convocation_Envoyee dans la table form_session_personne
		//-----------------------------------------------------------------------------
		$ReqMAJSessionPersonneConvocation="
			UPDATE
				form_session_personne
			SET
				Convocation_Envoyee=1
			WHERE
				Suppr=0
				AND Id_Session IN (SELECT Id FROM form_session WHERE Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION'].") ";
		$ResultMAJSessionPersonneConvocation=mysqli_query($bdd,$ReqMAJSessionPersonneConvocation);
		
		//Envoie de mails regroupés par prestations
		//-----------------------------------------
		for($i=0;$i<count($TableauPersonnesParPrestation);$i++)
		{
			//Récupération des responsables de chaque prestation
			
			//Tableau des personnes inscrites pour cette prestation en format tableau HTML
			$TableauHTMLPersonnesPrestation="";
			for($j=0;$j<count($TableauPersonnesParPrestation[$i][2]);$j++)
			{
				$resultPrestationsPersonne = getRessource(getchaineSQL_getprestationsActives($TableauPersonnesParPrestation[$i][2][$j][0]));
				$PrestationsPersonne = array(); 
				while($row = mysqli_fetch_array($resultPrestationsPersonne))
				{
					array_push($PrestationsPersonne, $row['Id_Prestation']."_".$row['Id_Pole']);
				}
				
				$TableauEmailResponsablesPrestationPoleFiltre= array();
				foreach($PrestationsPersonne as $curPresta)
				{
					$reponse = array();
					$tabPresta=explode("_",$curPresta);
					$TableauResponsablesPrestationPole = GetTableau_ResponsablesPrestationPole($tabPresta[0],$tabPresta[1]);
					$reponse=GetTableau_EmailResponsablesPourPostes($TableauResponsablesPrestationPole,array($IdPosteChefEquipe, $IdPosteCoordinateurEquipe));
					
					foreach ($reponse as $email)
						array_push($TableauEmailResponsablesPrestationPoleFiltre, $email);
				}
				
				$TableauHTMLPersonnesPrestation.="<tr><td style='border:1px solid black;'>".$TableauPersonnesParPrestation[$i][2][$j][1]."</td></tr>\n";
			}
			
			//Construction et envoie de l'email
			$destinataire = implode(",",$TableauEmailResponsablesPrestationPoleFiltre);
			$destinataire.=$personnesInscrites.",";
			//Si la formation est de type interne alors ajouter les AFI
			if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationInterne){
				//Liste des AF
				$reqAF="
					SELECT DISTINCT EmailPro 
					FROM new_competences_personne_poste_plateforme 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_plateforme.Id_Poste =".$IdPosteAssistantFormationInterne." 
					AND Id_Plateforme=".$_GET['Id_Plateforme']." ";
				$ResultAF=mysqli_query($bdd,$reqAF);
				$NbAF=mysqli_num_rows($ResultAF);
				if($NbAF>0)
				{
					while($RowAF=mysqli_fetch_array($ResultAF))
					{
						if($RowAF['EmailPro']<>""){$destinataire.=",".$RowAF['EmailPro'];}
					}
				}
			}
			
			$sujet =$ObjetConvocation;
			
			if($LangueAffichage=="FR")
			{
				$message_txt="	
					Convocation en formation
					
						Bonjour,
						<br><br>
						<i>Cette boîte mail est une boîte mail générique</i>
						<br><br>
						".$Convocation."
						
						".$formations."
						
						".$TableauHTMLPersonnesPrestation."
						
						".stripslashes($RowSession['MessageConvocation'])."
						
						Attention : Aucune absence ne sera tolérée

						Bonne journée.
						Formation Extranet Daher industriel services DIS.
					";
				
				$message_html="	<html>
					<head><title>Convocation en formation</title></head>
					<body>
						Bonjour,
						<br><br>
						<i>Cette boîte mail est une boîte mail générique</i>
						<br><br>".
						$Convocation."
						<br>
						".$formations."
						<br>
						<table style='border:1px solid black; border-spacing:0;'>
							<tr>
								<td style='border:1px solid black;'>Personnes concernées : </td>
							</tr>\n".
							$TableauHTMLPersonnesPrestation."
						</table>
						<br>
						".stripslashes($RowSession['MessageConvocation'])."
						<br>
						<font color='red'>Attention : Aucune absence ne sera tolérée </font>
						<br>
						<br>
						Bonne journée.<br>
						Formation Extranet Daher industriel services DIS.
					</body>
				</html>";
			}
			else
			{
				$message_txt="	
					Convocation in training
					
						Hello,
						".$Convocation."
						
						".$formations."
						
						".$TableauHTMLPersonnesPrestation."
						
						".stripslashes($RowSession['MessageConvocation'])."
						
						Attention: No absence will be tolerated!
						
						Have a good day.
						Training Extranet Daher industriel services DIS.
					";
				
				$message_html="	<html>
					<head><title>Convocation in training</title></head>
					<body>
						Hello,
						<br><br>
						<i>This mailbox is a generic mailbox</i>
						<br><br>".
						$Convocation."
						<br>
						".$formations."
						<br>
						<table style='border:1px solid black; border-spacing:0;'>
							<tr>
								<td style='border:1px solid black;'>Personnes concernées : </td>
							</tr>\n".
							$TableauHTMLPersonnesPrestation."
						</table>
						<br>
						".stripslashes($RowSession['MessageConvocation'])."
						<br>
						<font color='red'>Attention: No absence will be tolerated!</font>
						<br>
						<br>
						Have a good day.<br>
						Training Extranet Daher industriel services DIS.
					</body>
				</html>";
			}
						
			//Récupération des pièces jointes
			$PJ = array();
			$ressource = getRessource("SELECT DISTINCT chemin_fichier, nom_fichier FROM form_session WHERE chemin_fichier<>'' AND Suppr=0 AND Id_GroupeSession = ".$RowSession['ID_GROUPE_SESSION']);
			

			while($row=mysqli_fetch_array($ressource))
			{
				$pj_item = array();
				$pj_item['chemin'] = $row['chemin_fichier']; 
				$pj_item['nom'] = $row['nom_fichier'];
				$pj_item['MIME-Type'] = mime_content_type($row['chemin_fichier'].$row['nom_fichier']);
				$pj_item['attachement'] = encoderFichier($row['chemin_fichier'].$row['nom_fichier']);
			
				array_push($PJ, $pj_item);
			}
			
			//Recherche du document du lieu...
			$req = "SELECT DISTINCT form_lieu.chemin_fichier, form_lieu.Fichier 
					FROM form_session,form_lieu 
					WHERE form_session.Id_lieu = form_lieu.Id 
					AND Id_GroupeSession = ".$RowSession['ID_GROUPE_SESSION'].";";
	
			$ressource = getRessource($req);
			
			while($row=mysqli_fetch_array($ressource))
			{
				if($row['chemin_fichier'] <> "" && $row['Fichier'] <> "")
				{
					$pj_itemLieu = array();
					$pj_itemLieu['chemin'] = $row['chemin_fichier'];
					$pj_itemLieu['nom'] = $row['Fichier'];
					$pj_itemLieu['MIME-Type'] = mime_content_type($row['chemin_fichier'].$row['Fichier']);
					$pj_itemLieu['attachement'] = encoderFichier($row['chemin_fichier'].$row['Fichier']);
					
					array_push($PJ, $pj_itemLieu);
				}
			}			

			if(envoyerMail($destinataire, $sujet, $message_txt, $message_html, $PJ))
			{echo "Un message a été envoyé à ".$destinataire."\n";}
			else{echo "Un message n'a pu être envoyé à ".$destinataire."\n";}
		}
		
	}
	else
	{
		$formation=$RowSession['FORMATION_REFERENCE'];
		$req="SELECT Libelle, LibelleRecyclage
				FROM form_formation_langue_infos
				WHERE Id_Formation=".$RowSession['ID_FORMATION']."
				AND Id_Langue=
					(SELECT Id_Langue 
					FROM form_formation_plateforme_parametres 
					WHERE Id_Plateforme=".$_GET['Id_Plateforme']."
					AND Id_Formation=".$RowSession['ID_FORMATION']."
					AND Suppr=0 
					LIMIT 1)
				AND Suppr=0";
		$ResultFormation=mysqli_query($bdd,$req);
		$nbFormation=mysqli_num_rows($ResultFormation);
		if($nbFormation>0)
		{
			$RowFormation=mysqli_fetch_array($ResultFormation);
			if($RowSession['RECYCLAGE']==0){$formation=$RowFormation['Libelle'];}
			else{$formation=$RowFormation['LibelleRecyclage'];}
		}
		
		if($LangueAffichage=="FR")
		{
			$ObjetConvocation=
				"Convocation à la formation ".$formation." du ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." au ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN']);
			$Convocation=
				"Convocation à la formation "
				.$formation."
				du ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." au ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])." 
				aura lieu à ".$RowSession['LIEU']."(".$RowSession['LIEU_ADRESSE'].") de ".$RowSession['HEURE_DEBUT']." à ".$RowSession['HEURE_FIN'];
		}
		else
		{
			$ObjetConvocation=
				"Convocation to training ".$RowSession['FORMATION_REFERENCE']." from ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." to ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN']);
			$Convocation=
				"Convocation to training "
				.$RowSession['FORMATION_REFERENCE']."
				from ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." to ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])." 
				will take place in ".$RowSession['LIEU']."(".$RowSession['LIEU_ADRESSE'].") from ".$RowSession['HEURE_DEBUT']." to ".$RowSession['HEURE_FIN'];
		}
		//Définition d'une tableau contenant les ID_SESSION_PERSONNE
		$TableauIdSessionPersonnesConvoquees=array();
			
		//Trier dans un tableau les personnes de la session de formation par prestation
		//-----------------------------------------------------------------------------
		//Comme suit
		// |ID_PRESTATION|PRESTATION|INFORMATIONS PERSONNES|
		//                          |INFORMATIONS PERSONNES|
		//                          |INFORMATIONS_PERSONNES|
		// |ID_PRESTATION|PRESTATION|INFORMATIONS PERSONNES|
		//							|INFORMATIONS_PERSONNES|
		
		$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($_GET['Id']));
		$TableauPersonnesParPrestation=array();
		
		$personnesInscrites="";
		while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes))
		{
			if($RowSessionPersonnes['VALIDATION_INSCRIPTION']==1 && $RowSessionPersonnes['CONVOCATION_ENVOYEE']==0)
			{
				
				$TrouvePrestation=false;
				for($i=0;$i<count($TableauPersonnesParPrestation);$i++)
				{
					if($TableauPersonnesParPrestation[$i][0]==$RowSessionPersonnes['ID_PRESTATION'] && $TableauPersonnesParPrestation[$i][3]==$RowSessionPersonnes['ID_POLE'])
					{
						$TrouvePrestation=true;
						$TableauPersonnesParPrestation[$i][2][]=array($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']);
						$TableauIdSessionPersonnesConvoquees[]=$RowSessionPersonnes['ID'];
						break;
					}
				}
				if(!$TrouvePrestation)
				{
					$TableauPersonnesParPrestation[]=array($RowSessionPersonnes['ID_PRESTATION'],$RowSessionPersonnes['PRESTATION'],array(array($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['STAGIAIRE_NOMPRENOM'])),$RowSessionPersonnes['ID_POLE']);
					$TableauIdSessionPersonnesConvoquees[]=$RowSessionPersonnes['ID'];
				}
				
				if($RowSessionPersonnes['EmailPro']<>""){
					$personnesInscrites.=",".$RowSessionPersonnes['EmailPro'];
				}				
			}
		}
				
		
		//Mise à jour des états Convocation_Envoyee dans la table form_session_personne
		//-----------------------------------------------------------------------------
		$ReqMAJSessionPersonneConvocation="
			UPDATE
				form_session_personne
			SET
				Convocation_Envoyee=1
			WHERE
				Id IN (".implode(",",$TableauIdSessionPersonnesConvoquees).")";
		$ResultMAJSessionPersonneConvocation=mysqli_query($bdd,$ReqMAJSessionPersonneConvocation);
			
		//Envoie de mails regroupés par prestations
		//-----------------------------------------
		for($i=0;$i<count($TableauPersonnesParPrestation);$i++)
		{
			//Récupération des responsables de chaque prestation
			
			//Tableau des personnes inscrites pour cette prestation en format tableau HTML
			$TableauHTMLPersonnesPrestation="";
			$IdsPersonnes=array();
			$numId=0;
			for($j=0;$j<count($TableauPersonnesParPrestation[$i][2]);$j++)
			{
				$resultPrestationsPersonne = getRessource(getchaineSQL_getprestationsActives($TableauPersonnesParPrestation[$i][2][$j][0]));
				$PrestationsPersonne = array(); 
				while($row = mysqli_fetch_array($resultPrestationsPersonne))
				{
					array_push($PrestationsPersonne, $row['Id_Prestation']."_".$row['Id_Pole']);
				}
				
				$TableauEmailResponsablesPrestationPoleFiltre= array();
				foreach($PrestationsPersonne as $curPresta)
				{
					$reponse = array();
					$tabPresta=explode("_",$curPresta);
					$TableauResponsablesPrestationPole = GetTableau_ResponsablesPrestationPole($tabPresta[0],$tabPresta[1]);
					$reponse=GetTableau_EmailResponsablesPourPostes($TableauResponsablesPrestationPole,array($IdPosteChefEquipe, $IdPosteCoordinateurEquipe));
					
					foreach ($reponse as $email)
						array_push($TableauEmailResponsablesPrestationPoleFiltre, $email);
				}
				
				$TableauHTMLPersonnesPrestation.="<tr><td style='border:1px solid black;'>".$TableauPersonnesParPrestation[$i][2][$j][1]."</td></tr>\n";
				$IdsPersonnes[$numId]=$TableauPersonnesParPrestation[$i][2][$j][0];
				$numId++;
			}
			
			//Construction et envoie de l'email
			$destinataire = implode(",",$TableauEmailResponsablesPrestationPoleFiltre);
			$destinataire.=$personnesInscrites.",";
			
			//Si la formation est de type interne alors ajouter les AFI
			if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationInterne){
				//Liste des AF
				$reqAF="
					SELECT DISTINCT EmailPro 
					FROM new_competences_personne_poste_plateforme 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_plateforme.Id_Poste =".$IdPosteAssistantFormationInterne." 
					AND Id_Plateforme=".$_GET['Id_Plateforme']." ";
				$ResultAF=mysqli_query($bdd,$reqAF);
				$NbAF=mysqli_num_rows($ResultAF);
				if($NbAF>0)
				{
					while($RowAF=mysqli_fetch_array($ResultAF))
					{
						if($RowAF['EmailPro']<>""){$destinataire.=",".$RowAF['EmailPro'];}
					}
					$destinataire.=",";
				}
			}
			
			$sujet =$ObjetConvocation;
			
			if($LangueAffichage=="FR")
			{
				$message_txt="	
					Convocation en formation
					
						Bonjour,
						<br><br>
						<i>Cette boîte mail est une boîte mail générique</i>
						<br><br>
						".$Convocation."
						
						".$TableauHTMLPersonnesPrestation."
						
						".stripslashes($RowSession['MessageConvocation'])."
						
						Attention : Aucune absence ne sera tolérée

						Bonne journée.
						Formation Extranet Daher industriel services DIS.
					";
				
				$message_html="	<html>
					<head><title>Convocation en formation</title></head>
					<body>
						Bonjour,
						<br><br>
						<i>Cette boîte mail est une boîte mail générique</i>
						<br><br>".
						$Convocation."
						<table style='border:1px solid black; border-spacing:0;'>
							<tr>
								<td style='border:1px solid black;'>Personnes concernées : </td>
							</tr>\n".
							$TableauHTMLPersonnesPrestation."
						</table>
						<br>
						".stripslashes($RowSession['MessageConvocation'])."
						<br>
						<font color='red'>Attention : Aucune absence ne sera tolérée </font>
						<br>
						<br>
						Bonne journée.<br>
						Formation Extranet Daher industriel services DIS.
					</body>
				</html>";
			}
			else
			{
				$message_txt="	
					Convocation in training
					
						Hello,
						".$Convocation."
						
						".$TableauHTMLPersonnesPrestation."
						
						".stripslashes($RowSession['MessageConvocation'])."
						
						Attention: No absence will be tolerated!
						
						Have a good day.
						Extranet Daher industriel services DIS.
					";
				
				$message_html="	<html>
					<head><title>Convocation in training</title></head>
					<body>
						Hello,
						<br><br>
						<i>This mailbox is a generic mailbox</i>
						<br><br>".
						$Convocation."
						<table style='border:1px solid black; border-spacing:0;'>
							<tr>
								<td style='border:1px solid black;'>Personnes concernées : </td>
							</tr>\n".
							$TableauHTMLPersonnesPrestation."
						</table>
						<br>
						".stripslashes($RowSession['MessageConvocation'])."
						<br>
						<font color='red'>Attention: No absence will be tolerated!</font>
						<br>
						<br>
						Have a good day.<br>
						Extranet Daher industriel services DIS.
					</body>
				</html>";
			}
						
			//Récupération des pièces jointes
			$PJ = array();
			$ressource = getRessource(getChaineSQL_getInfosDocument($_GET['Id']));
			
			$row = mysqli_fetch_array($ressource);
			//$result = new finfo();
			
			if($row['chemin_fichier'] <> "")
			{
				$pj_item = array();
				$pj_item['chemin'] = $row['chemin_fichier']; 
				$pj_item['nom'] = $row['nom_fichier'];
				$pj_item['MIME-Type'] = mime_content_type($row['chemin_fichier'].$row['nom_fichier']);
				$pj_item['attachement'] = encoderFichier($row['chemin_fichier'].$row['nom_fichier']);
			
				array_push($PJ, $pj_item);
			}
			
			//Ajout des convocations individuelles
			$ressource = getRessource("SELECT DISTINCT Convocation FROM form_session_personne WHERE Convocation<>'' AND Suppr=0 AND Id_Personne IN (".implode(',',$IdsPersonnes).") AND Id_Session = ".$_GET['Id']);
		
			while($row=mysqli_fetch_array($ressource))
			{
				$pj_item = array();
				$pj_item['chemin'] = "Docs/convocations/"; 
				$pj_item['nom'] = $row['Convocation'];
				$pj_item['MIME-Type'] = mime_content_type("Docs/convocations/".$row['Convocation']);
				$pj_item['attachement'] = encoderFichier("Docs/convocations/".$row['Convocation']);
			
				array_push($PJ, $pj_item);
			}
			
			//Recherche du document du lieu...
			$ressource = getRessource(getChaineSQL_getInfosDocumentLieu($_GET['Id']));
			$row = mysqli_fetch_array($ressource);
			
			if($row['chemin_fichier'] <> "" && $row['Fichier'] <> "")
			{
				$pj_itemLieu = array();
				$pj_itemLieu['chemin'] = $row['chemin_fichier'];
				$pj_itemLieu['nom'] = $row['Fichier'];
				$pj_itemLieu['MIME-Type'] = mime_content_type($row['chemin_fichier'].$row['Fichier']);
				$pj_itemLieu['attachement'] = encoderFichier($row['chemin_fichier'].$row['Fichier']);
				
				array_push($PJ, $pj_itemLieu);
			}	

			if(envoyerMail($destinataire, $sujet, $message_txt, $message_html, $PJ))
			{echo "Un message a été envoyé à ".$destinataire."\n";}
			else{echo "Un message n'a pu être envoyé à ".$destinataire."\n";}
		}
	}
}

if($_GET['Page']=="Contenu_Session")
{
	echo "<script>window.opener.location = 'Contenu_Session.php?ancre=".$_GET['ancre']."&Id=".$_GET['Id']."&Id_Plateforme=".$_GET['Id_Plateforme']."'</script>";
	echo "<script>window.opener.opener.document.getElementById('formulaire').submit();</script>";
	echo "<script>window.close();</script>";
}
else
{
	echo "<script>window.opener.document.getElementById('formulaire').submit();</script>";
	echo "<script>window.close();</script>";
}
?>
</body>
</html>