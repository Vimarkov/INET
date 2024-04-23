<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetre(Page,Haut,Long)
		{window.open(Page,"PageSelectionRecherche","status=no,menubar=no,scrollbars=yes,width="+Long+",height="+Haut);}
	function OuvreFenetreProfil(Mode,Id)
		{window.open("Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1060,height=800");}
	function OuvreFenetreCompetences2(Id)
		{window.open("Individual_Competency_List.php?Id_Personne="+Id,"CompetencyList","status=no,menubar=no,scrollbars=yes,rezisable=yes,width=1010,height=600");}
	function OuvreFenetreModif(Mode,Id)
		{window.open("../RH/Ajout_Personne.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=800,height=200");}
	function OuvreFenetreCompetences2Export(Id)
		{window.open("Individual_Competency_List_Export.php?Type=Prestation&Id="+Id,"PageExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}		
	function OuvreFenetreCompetences2ExportQBP(Id)
		{window.open("Individual_Competency_List_ExportQBP.php?Type=Prestation&Id="+Id,"PageExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
	function ExporterTout()
	{
		//concaténantion des Identifiants
		var elements = document.getElementsByName("IdPersonne");
		var checks = document.getElementsByName("checked");

		first=true;
		Ids="";
		// Récupération de chaque Id de chaque enregistrement
		for(var i=0, l=elements.length; i<l; i++)
			//Vérifier également si la checkbox est checkée
			if (checks[i].checked) {
				if (first) {
					Ids+=elements[i].value;
					first=false;
				} else
					Ids+=";"+elements[i].value;
			}
		window.open("Individual_Competency_List_ExportAll.php?Type=Prestation&Id="+Ids,"PageExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");
	}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
)
{
	$Droits="Ecriture";
}
$DroitsModifPrestation=EstPresent_HierarchiePrestation();

$DateJourPlus1Mois=date("Y-m-d",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
$DateJourPlus2Ans1Mois=date("Y-m-d",mktime(0,0,0,date("m")+1,date("d"),date("Y")+2));

//Update des lignes concernées par le signalement pour éviter de renvoyer l'information (logiquement : toutes celles qu'on retrouve dans la boucle du dessus)
$requete_CompetencesArrivantAEcheance_Update="
	UPDATE
		new_competences_relation
	SET
		SignalMailEnvoye=2
	WHERE
		(SignalMailEnvoye=0 OR SignalMailEnvoye=1)
		AND Sans_Fin ='Non'
		AND Date_Fin >'".$DateJour."'
		AND Date_Fin <='".$DateJourPlus1Mois."'";
$result_CompetencesArrivantAEcheance_Update=mysqli_query($bdd,$requete_CompetencesArrivantAEcheance_Update);

$requete_CompetencesArrivantAEcheance_Update="
	UPDATE
		new_competences_relation
	SET
		SignalMailEnvoye=1
	WHERE
		SignalMailEnvoye=0
		AND Sans_Fin ='Non'
		AND (Date_Surveillance <='0001-01-01')
		AND (Evaluation='Q' OR Evaluation='S')
		AND Date_Fin >'".$DateJour."'
		AND Date_Fin <='".$DateJourPlus2Ans1Mois."'";
$result_CompetencesArrivantAEcheance_Update=mysqli_query($bdd,$requete_CompetencesArrivantAEcheance_Update);

//####################################################################
//##### EMAIL POUR COMPETENCES ARRIVANT A ECHEANCES A LA SEMAINE #####
//####################################################################

$DateJourSem=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$DateJourPlus1MoisSem=date("Y-m-d",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
$DateJourPlus2Ans1MoisSem=date("Y-m-d",mktime(0,0,0,date("m")+1,date("d"),date("Y")+2));
if(date("w")==1){
	//1-Liste des prestations qui ont des qualifs arrivant à échéances 
	$requete_CompetencesArrivantAEcheance="
		SELECT
			DISTINCT
			new_competences_personne_poste_prestation.Id_Personne,
			(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=new_competences_personne_poste_prestation.Id_Personne) AS EmailPro
		FROM new_competences_personne_poste_prestation,
			new_competences_relation
		WHERE
			new_competences_personne_poste_prestation.Id_Prestation IN 
			(SELECT new_competences_prestation.Id
				FROM new_competences_personne_prestation,new_competences_prestation
				WHERE
					new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
					AND new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne
					AND new_competences_personne_prestation.Date_Fin >= '".$DateJourSem."'
					AND new_competences_prestation.Id_Plateforme<>1)
			AND new_competences_personne_poste_prestation.Id_Personne<>387
			AND new_competences_personne_poste_prestation.Id_Poste IN (1,2,3,4,5,6)
			AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=new_competences_personne_poste_prestation.Id_Personne)<>''
			AND new_competences_relation.Sans_Fin = 'Non'
			AND new_competences_relation.Suppr=0
			AND new_competences_relation.Date_Fin >'".$DateJourSem."'
			AND ((new_competences_relation.Date_Fin <='".$DateJourPlus1MoisSem."'
			AND (new_competences_relation.SignalMailEnvoyeSemaine =1 OR new_competences_relation.SignalMailEnvoyeSemaine =0))
			OR (new_competences_relation.Date_Fin <='".$DateJourPlus2Ans1MoisSem."'
			AND (SELECT Periodicite_Surveillance FROM new_competences_qualification WHERE Id=Id_Categorie_Qualification)>0
			AND (new_competences_relation.Date_Surveillance<='0001-01-01')
			AND (new_competences_relation.Evaluation='Q' OR new_competences_relation.Evaluation='S')
			AND new_competences_relation.SignalMailEnvoyeSemaine =0))
			AND (
				SELECT
					count(test.Id)
				FROM
					new_competences_relation AS test
				WHERE
					test.Date_Fin > new_competences_relation.Date_Fin
					AND test.Id <> new_competences_relation.Id
					AND test.Id_Qualification_Parrainage = new_competences_relation.Id_Qualification_Parrainage
					AND test.Id_Personne = new_competences_relation.Id_Personne
				) = 0
			AND (SELECT COUNT(new_competences_prestation.Id)
				FROM
					new_competences_personne_prestation,
					new_competences_prestation
				WHERE
					new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
					AND new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne
					AND new_competences_personne_prestation.Date_Fin >= '".$DateJourSem."'
					AND new_competences_prestation.Id_Plateforme<>1
				)>0
				";
	$result_CompetencesArrivantAEcheance=mysqli_query($bdd,$requete_CompetencesArrivantAEcheance);
	$nbenreg=mysqli_num_rows($result_CompetencesArrivantAEcheance);
	if($nbenreg>0){
		while($row=mysqli_fetch_array($result_CompetencesArrivantAEcheance)){
			$Destinataires=trim($row['EmailPro']);
			
			$req="
				SELECT DISTINCT 
					new_competences_relation.Id_Qualification_Parrainage,
					new_competences_relation.Date_Fin,
					(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) as NomPrenom,
					(SELECT Libelle FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage) AS Qualif,
					(SELECT (SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=Id_Categorie_Qualification) FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage) AS Categorie,
					(SELECT (SELECT (SELECT Libelle FROM new_competences_categorie_qualification_maitre WHERE Id=Id_Categorie_Maitre) FROM new_competences_categorie_qualification WHERE Id=Id_Categorie_Qualification) FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage) AS CategorieMaitre,
					(SELECT new_competences_prestation.Libelle
					FROM new_competences_personne_prestation,new_competences_prestation
					WHERE
						new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
						AND new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne
						AND new_competences_personne_prestation.Date_Fin >= '".$DateJourSem."'
						AND new_competences_prestation.Id_Plateforme<>1
					ORDER BY
						new_competences_personne_prestation.Date_Debut DESC LIMIT 1) AS Prestation
				FROM
					new_competences_personne_poste_prestation,
					new_competences_relation
				WHERE
					CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) IN 
					(
						SELECT CONCAT(new_competences_prestation.Id,'_',new_competences_personne_prestation.Id_Pole)
						FROM new_competences_personne_prestation,new_competences_prestation
						WHERE new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
						AND new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne
						AND new_competences_personne_prestation.Date_Fin >= '".$DateJourSem."'
						AND new_competences_prestation.Id_Plateforme<>1
					)
					AND new_competences_personne_poste_prestation.Id_Personne<>387
					AND new_competences_personne_poste_prestation.Id_Personne=".$row['Id_Personne']."
					AND new_competences_personne_poste_prestation.Id_Poste IN (1,2,3,4,5,6)
					AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=new_competences_personne_poste_prestation.Id_Personne)<>''
					AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation)<>1
					AND new_competences_relation.Sans_Fin = 'Non'
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Date_Fin >'".$DateJourSem."'
					AND ((new_competences_relation.Date_Fin <='".$DateJourPlus1MoisSem."'
					AND (new_competences_relation.SignalMailEnvoyeSemaine =1 OR new_competences_relation.SignalMailEnvoyeSemaine =0))
					OR (new_competences_relation.Date_Fin <='".$DateJourPlus2Ans1MoisSem."'
					AND (SELECT Periodicite_Surveillance FROM new_competences_qualification WHERE Id=Id_Categorie_Qualification)>0
					AND (new_competences_relation.Date_Surveillance<='0001-01-01')
					AND (new_competences_relation.Evaluation='Q' OR new_competences_relation.Evaluation='S')
					AND new_competences_relation.SignalMailEnvoyeSemaine =0))
					AND (
						SELECT
							count(test.Id)
						FROM
							new_competences_relation AS test
						WHERE
							test.Date_Fin > new_competences_relation.Date_Fin
							AND test.Id <> new_competences_relation.Id
							AND test.Id_Qualification_Parrainage = new_competences_relation.Id_Qualification_Parrainage
							AND test.Id_Personne = new_competences_relation.Id_Personne
						) = 0
					AND (SELECT COUNT(new_competences_prestation.Id)
						FROM
							new_competences_personne_prestation,
							new_competences_prestation
						WHERE
							new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
							AND new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne
							AND new_competences_personne_prestation.Date_Fin >= '".$DateJourSem."'
							AND new_competences_prestation.Id_Plateforme<>1
						)>0
					ORDER BY NomPrenom
						";
			$res=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($res);

			if($Destinataires <> "" && $nb>0)
			{
				$headers="From: 'Extranet AAA'<extranet@aaa-aero.com>"."\n";
				$headers.="Content-Type: text/html; charset='iso-8859-1'"."\n";
				$message="<html><head><title>Compétence arrivant à échéance/surveillance - Extranet</title></head><body>Bonjour,<br><br>";
				$message.="Les compétence suivantes arrivent à échéance/surveillance : <br>";
				$message.="<br><table border=1><tr><td>Prestation</td><td>Personne concernée</td><td>Catégorie Maitre</td><td>Catégorie compétence</td><td>Compétence</td><td>Date de fin de validité</td>";
				while($row_Qualifs=mysqli_fetch_array($res))
				{
					$message.="<tr><td>".stripslashes($row_Qualifs['Prestation'])."</td>";
					$message.="<td>".stripslashes($row_Qualifs['NomPrenom'])."</td>";
					$message.="<td>".stripslashes($row_Qualifs['CategorieMaitre'])."</td>";
					$message.="<td>".stripslashes($row_Qualifs['Categorie'])."</td>";
					$message.="<td>".stripslashes($row_Qualifs['Qualif'])."</td>";
					$message.="<td>".$row_Qualifs['Date_Fin']."</td>";
				}
				$message.="</tr></table>";
				$message.="<br><br>Merci de planifier le plus rapidement possible la formation ou la surveillance requise.";
				$message.="<br><br>Bonne journée.<br><a href='https://extranet.aaa-aero.com'> Extranet </a></body></html><br>-------------------------------------------------------";
				
				//A REMETTRE QUAND ON BASCULE SUR LE VRAI Extranet
				//################################################
				if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
					if(mail($Destinataires, 'Liste compétences arrivant à échéance/surveillance - Extranet', $message, $headers,'-f extranet@aaa-aero.com')){echo '';}
				}
			}
		}
	}
	
	//Update des lignes concernées par le signalement pour éviter de renvoyer l'information (logiquement : toutes celles qu'on retrouve dans la boucle du dessus)
	$requete_CompetencesArrivantAEcheance_Update="
		UPDATE
			new_competences_relation
		SET
			SignalMailEnvoyeSemaine=2
		WHERE
			(SignalMailEnvoyeSemaine=0 OR SignalMailEnvoyeSemaine=1)
			AND Sans_Fin ='Non'
			AND Date_Fin >'".$DateJourSem."'
			AND Date_Fin <='".$DateJourPlus1MoisSem."'";
	$result_CompetencesArrivantAEcheance_Update=mysqli_query($bdd,$requete_CompetencesArrivantAEcheance_Update);

	$requete_CompetencesArrivantAEcheance_Update="
		UPDATE
			new_competences_relation
		SET
			SignalMailEnvoyeSemaine=1
		WHERE
			SignalMailEnvoyeSemaine=0
			AND Sans_Fin ='Non'
			AND (Date_Surveillance <='0001-01-01')
			AND (Evaluation='Q' OR Evaluation='S')
			AND Date_Fin >'".$DateJourSem."'
			AND Date_Fin <='".$DateJourPlus2Ans1MoisSem."'";
	$result_CompetencesArrivantAEcheance_Update=mysqli_query($bdd,$requete_CompetencesArrivantAEcheance_Update);
}
//Mise des valeurs de recherche dans des variables sessions
if($_POST)
{
	if(isset($_POST['Nom']))
	{
		if(!isset($_SESSION["Competences_Recherche_Personne"])){$_SESSION["Competences_Recherche_Personne"]=preg_split("/;/",substr($_POST['Nom'],0));}
		elseif($_SESSION["Competences_Recherche_Personne"]==""){$_SESSION["Competences_Recherche_Personne"]=preg_split("/;/",substr($_POST['Nom'],0));}
		else{$_SESSION["Competences_Recherche_Personne"][count($_SESSION["Competences_Recherche_Personne"])]=$_POST['Nom'];}
	}
	if(isset($_POST['Recherche_RAZ']))
	{
		switch($_POST['Recherche_RAZ'])
		{
			case -1 : 
				$_SESSION["Competences_Recherche_Personne"]="";
				$_SESSION["Competences_Recherche_Plateforme"]="";
				$_SESSION["Competences_Recherche_Prestation"]="";
				$_SESSION["Competences_Recherche_Metier"]="";
				$_SESSION["Competences_Recherche_Formation"]="";
				$_SESSION["Competences_Recherche_Qualification"]="";
				$_SESSION["Competences_Recherche_EvaluationQualification"]="";
				break;
			case 1 : 
				$_SESSION["Competences_Recherche_Personne"]="";
				break;
			case 2 : 
				$_SESSION["Competences_Recherche_Plateforme"]="";
				break;
			case 3 : 
				$_SESSION["Competences_Recherche_Prestation"]="";
				break;
			case 4 : 
				$_SESSION["Competences_Recherche_Metier"]="";
				break;
			case 5 : 
				$_SESSION["Competences_Recherche_Formation"]="";
				break;
			case 6 : 
				$_SESSION["Competences_Recherche_Qualification"]="";
				break;
			case 7 : 
				$_SESSION["Competences_Recherche_EvaluationQualification"]="";
				break;
			default : break;
		}
	}
	
	$SelectRequete="SELECT DISTINCT new_rh_etatcivil.Id FROM ";
	$DebutRequete="new_rh_etatcivil";
	$InnerJoin=" LEFT JOIN new_competences_personne_plateforme ON new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id ";
	$InnerJoin.="LEFT JOIN new_competences_plateforme ON new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
	$MilieuRequete="";
	$FinRequete=" ORDER BY new_competences_plateforme.Libelle ASC, new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	
	$RECHERCHE="";
	
	//ETAT CIVIL
	if(isset($_SESSION["Competences_Recherche_Personne"]))
	{
		if($_SESSION["Competences_Recherche_Personne"]!="")
		{
			$RequeteCivil="";
			if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
			$MilieuRequete.=" (";
			foreach($_SESSION['Competences_Recherche_Personne'] as &$value)
			{
				if($RequeteCivil!=""){$RequeteCivil.=" OR";}
				$RequeteCivil.=" REPLACE(new_rh_etatcivil.Nom,\"'\",\" \") LIKE '".str_replace("'"," ",$value)."'";
				
				if($RequeteCivil!=""){$RequeteCivil.=" OR";}
				$RequeteCivil.=" CONCAT('AAA-',new_rh_etatcivil.Id) LIKE '".str_replace("'"," ",$value)."'";
				
				$RECHERCHE.=" - Nom : ".$value."<br>";
			}
			$MilieuRequete.=$RequeteCivil;
			$MilieuRequete.=")";
		}
	}
		
	//PLATEFORME
	if(isset($_SESSION["Competences_Recherche_Plateforme"]))
	{
		if($_SESSION["Competences_Recherche_Plateforme"]!="")
		{
			$RequetePlateforme="";
			if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
			$MilieuRequete.=" (";
			foreach($_SESSION['Competences_Recherche_Plateforme'] as &$value)
			{
				if($RequetePlateforme!=""){$RequetePlateforme.=" OR";}
				$RequetePlateforme.=" new_competences_personne_plateforme.Id_Plateforme=".$value;
				$requete="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$value;
				$result=mysqli_query($bdd,$requete);
				$row=mysqli_fetch_array($result);
				$RECHERCHE.=" - Plateforme : ".$row[0]."<br>";
				mysqli_free_result($result);
			}
			$MilieuRequete.=$RequetePlateforme;
			$MilieuRequete.=")";
		}
	}
		
	//PRESTATION
	if(isset($_SESSION["Competences_Recherche_Prestation"]))
	{
		if($_SESSION["Competences_Recherche_Prestation"]!="")
		{
			$RequetePrestation="";
			$InnerJoin.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id";
			if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
			$MilieuRequete.=" (";
			foreach($_SESSION["Competences_Recherche_Prestation"] as &$value)
			{
				if($RequetePrestation!=""){$RequetePrestation.=" OR";}
				$RequetePrestation.=" (new_competences_personne_prestation.Date_Debut<='".$DateJour."' AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$DateJour."') AND new_competences_personne_prestation.Id_Prestation=".$value.") ";
				$requete="SELECT Libelle FROM new_competences_prestation WHERE Id=".$value;
				$result=mysqli_query($bdd,$requete);
				$row=mysqli_fetch_array($result);
				$RECHERCHE.=" - Prestation : ".$row[0]."<br>";
				mysqli_free_result($result);
			}
			$MilieuRequete.=$RequetePrestation;
			$MilieuRequete.=")";
		}
	}
		
	//METIER
	if(isset($_SESSION["Competences_Recherche_Metier"]))
	{
		if($_SESSION["Competences_Recherche_Metier"]!="")
		{
			$RequeteMetier="";
			$InnerJoin.=" LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne=new_rh_etatcivil.Id";
			if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
			$MilieuRequete.=" (";
			foreach($_SESSION["Competences_Recherche_Metier"] as &$value)
			{
				if($RequeteMetier!=""){$RequeteMetier.=" OR";}
				$RequeteMetier.=" new_competences_personne_metier.Id_Metier=".$value;
				$requete="SELECT Libelle FROM new_competences_metier WHERE Id=".$value;
				$result=mysqli_query($bdd,$requete);
				$row=mysqli_fetch_array($result);
				$RECHERCHE.=" - Métier : ".$row[0]."<br>";
				mysqli_free_result($result);
			}
			$MilieuRequete.=$RequeteMetier;
			$MilieuRequete.=") ";
		}
	}
		
	//FORMATION
	if(isset($_SESSION["Competences_Recherche_Formation"]))
	{
		if($_SESSION["Competences_Recherche_Formation"]!="")
		{
			$RequeteFormation="";
			$InnerJoin.=" LEFT JOIN new_competences_personne_formation ON new_competences_personne_formation.Id_Personne=new_rh_etatcivil.Id";
			if($MilieuRequete==""){
				$MilieuRequete.=" WHERE";}
			else{$MilieuRequete.=" AND ";}
			$MilieuRequete.=" (";
			foreach($_SESSION["Competences_Recherche_Formation"] as &$value)
			{
				if($RequeteFormation!=""){$RequeteFormation.=" OR";}
				$RequeteFormation.=" new_competences_personne_formation.Id_Formation=".$value;
				$requete="SELECT Libelle FROM new_competences_formation WHERE Id=".$value;
				$result=mysqli_query($bdd,$requete);
				$row=mysqli_fetch_array($result);
				$RECHERCHE.=" - Formation : ".$row[0]."<br>";
				mysqli_free_result($result);
			}
			$MilieuRequete.=$RequeteFormation;
			$MilieuRequete.=")";
		}
	}
		
	//QUALIFICATION
	if(isset($_SESSION["Competences_Recherche_Qualification"]))
	{
		if($_SESSION["Competences_Recherche_Qualification"]!="")
		{
			$RequeteQualif="";
			$InnerJoin.=" LEFT JOIN new_competences_relation ON new_competences_relation.Id_Personne=new_rh_etatcivil.Id";
			if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
			$MilieuRequete.=" (";
			foreach($_SESSION["Competences_Recherche_Qualification"] as &$value)
			{
				if($RequeteQualif!=""){$RequeteQualif.=" OR";}
				$RequeteQualif.=" (new_competences_relation.Id_Qualification_Parrainage=".$value." AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 )";
				$requete2="SELECT Libelle FROM new_competences_qualification WHERE Id=".$value;
				$result2=mysqli_query($bdd,$requete2);
				$row2=mysqli_fetch_array($result2);
				$RECHERCHE.=" - Qualification : ".$row2[0]."<br>";
				mysqli_free_result($result2);
			}
			$MilieuRequete.=$RequeteQualif;
			$MilieuRequete.=")";
			
			//Evaluation
			if(isset($_SESSION["Competences_Recherche_EvaluationQualification"]))
			{
				if($_SESSION["Competences_Recherche_EvaluationQualification"]!="")
				{
					$RequeteEvaluationQualif="";
					$MilieuRequete.=" AND";
					$MilieuRequete.=" (";
					foreach($_SESSION["Competences_Recherche_EvaluationQualification"] as &$value)
					{
						if($RequeteEvaluationQualif!=""){$RequeteEvaluationQualif.=" OR";}
						$RequeteEvaluationQualif.=" (new_competences_relation.Evaluation='".$value."')";
						$RECHERCHE.=" - Qualification (Evaluation) : ".$value."<br>";
					}
					$MilieuRequete.=$RequeteEvaluationQualif;
					$MilieuRequete.=")";
				}
			}
		}
	}
	
	//FIN REQUETE
	//-----------
	$requete=$SelectRequete.$DebutRequete.$InnerJoin.$MilieuRequete.$FinRequete;
}

if($_GET)
{
	if(isset($_GET['Plateformes'])){$_SESSION["Competences_Recherche_Plateforme"]=preg_split("/;/",substr($_GET['Plateformes'],0,-1));}
	if(isset($_GET['Prestations'])){$_SESSION["Competences_Recherche_Prestation"]=preg_split("/;/",substr($_GET['Prestations'],0,-1));}
	if(isset($_GET['Metiers'])){$_SESSION["Competences_Recherche_Metier"]=preg_split("/;/",substr($_GET['Metiers'],0,-1));}
	if(isset($_GET['Formations'])){$_SESSION["Competences_Recherche_Formation"]=preg_split("/;/",substr($_GET['Formations'],0,-1));}
	if(isset($_GET['Qualifications'])){$_SESSION["Competences_Recherche_Qualification"]=preg_split("/;/",substr($_GET['Qualifications'],0,-1));}
	if(isset($_GET['EvaluationQualifications'])){$_SESSION["Competences_Recherche_EvaluationQualification"]=preg_split("/;/",substr($_GET['EvaluationQualifications'],0,-1));}
}
?>

<table style="width:100%; border-spacing:0;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage">
					<?php 
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Competences/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Recherche";}else{echo "Competencies management # Search";}
					?></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="300">
						<table class="TableCompetences" style="width:100%;">
							<tr>
								<td colspan="3"><b><?php if($LangueAffichage=="FR"){echo "Choix des informations à sélectionner : ";}else{echo "Choice of information to be selected : ";}?></b></td>
							</tr>
							<tr height="25">
								<td style="width:260px; align:right;">
    								<form id="formulaire" method="POST" action="Recherche.php">
    									<?php if($LangueAffichage=="FR"){echo "Nom :";}else{echo "Name :";}?>
    									<input type="text" size="15" name="Nom" alt="% : remplace plusieurs caractères, ? : remplace un seul caractère" title="% : remplace plusieurs caractères, ? : remplace un seul caractère">
    									<input class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ajouter à la sélection";}else{echo "Add to selection";}?>">
    								</form>
								</td>
							</tr>
							<tr height="25">
								<td align="right">
									<form id="formulaire" method="POST" action="javascript:OuvreFenetre('Recherche_Plateforme.php','500','300');">
										<input style="width:250px" class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ouvrir la fenêtre de sélection de plateforme";}else{echo "Open the platform selection window";}?>">
									</form>
								</td>
							</tr>
							<tr height="25">
								<td align="right">
									<form id="formulaire" method="POST" action="javascript:OuvreFenetre('Recherche_Prestation.php','500','500');">
										<input style="width:250px" class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ouvrir la fenêtre de sélection de prestation";}else{echo "Open the activity selection window";}?>">
									</form>
								</td>
							</tr>
							<tr height="25">
								<td align="right">
									<form id="formulaire" method="POST" action="javascript:OuvreFenetre('Recherche_Metier.php','500','400');">
										<input style="width:250px" class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ouvrir la fenêtre de sélection de métier";}else{echo "Open the job selection window";}?>">
									</form>
								</td>
							</tr>
							<tr height="25">
								<td align="right">
									<form id="formulaire" method="POST" action="javascript:OuvreFenetre('Recherche_Formation.php','500','600');">
										<input style="width:250px" class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ouvrir la fenêtre de sélection de formation";}else{echo "Open the training selection window";}?>">
									</form>
								</td>
							</tr>
							<tr height="25">
								<td align="right">
									<form id="formulaire" method="POST" action="javascript:OuvreFenetre('Recherche_Qualification.php','500','800');">
										<input style="width:250px" class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ouvrir la fenêtre de sélection de qualification";}else{echo "Open the qualification selection window";}?>">
									</form>
								</td>
							</tr>
							<tr height="25">
								<td align="right">
									<form id="formulaire" method="POST" action="javascript:OuvreFenetre('Recherche_QualificationEvaluation.php','350','100');">
										<input style="width:250px" class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ouvrir la fenêtre de sélection d'évaluation";}else{echo "Open the evaluation selection window";}?>">
									</form>
								</td>
							</tr>
						</table>
					</td>
					
					<td>
						<table class="TableCompetences" style="width:100%;">
							<tr>
								<td colspan="3"><b><?php if($LangueAffichage=="FR"){echo "Résumé des informations sélectionnées : ";}else{echo "Summary of selected Information";}?></b></td>
							</tr>
							<tr height="20">
								<td width="110"><?php if($LangueAffichage=="FR"){echo "Personne : ";}else{echo "Person : ";}?></td>
								<td align="left">
									<?php
									if(isset($_SESSION['Competences_Recherche_Personne']))
									{
										if($_SESSION['Competences_Recherche_Personne'] != "")
										{
											foreach($_SESSION['Competences_Recherche_Personne'] as &$value){echo $value.";";}
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="1">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							<tr height="20">
								<td><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation : ";}else{echo "Operating unit : ";}?></td>
								<td>
									<?php
									if(isset($_SESSION['Competences_Recherche_Plateforme']))
									{
										if($_SESSION['Competences_Recherche_Plateforme'] != "")
										{
											$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_plateforme");
											$nbenreg=mysqli_num_rows($result);
											while($donnees=mysqli_fetch_array($result))
											{
												foreach($_SESSION['Competences_Recherche_Plateforme'] as &$value){if($donnees[0]==$value){echo $donnees[1].";";break;}}
											}
											mysqli_free_result($result);
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="2">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							<tr height="20">
								<td><?php if($LangueAffichage=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
								<td>
									<?php
									if(isset($_SESSION['Competences_Recherche_Prestation']))
									{
										if($_SESSION['Competences_Recherche_Prestation'] != "")
										{
											$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_prestation");
											$nbenreg=mysqli_num_rows($result);
											while($donnees=mysqli_fetch_array($result))
											{
												foreach($_SESSION['Competences_Recherche_Prestation'] as &$value){if($donnees[0]==$value){echo $donnees[1].";";break;}}
											}
											mysqli_free_result($result);
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="3">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							<tr height="20">
								<td><?php if($LangueAffichage=="FR"){echo "Métier : ";}else{echo "Job : ";}?></td>
								<td>
									<?php
									if(isset($_SESSION['Competences_Recherche_Metier']))
									{
										if($_SESSION['Competences_Recherche_Metier'] != "")
										{
											$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_metier");
											$nbenreg=mysqli_num_rows($result);
											while($donnees=mysqli_fetch_array($result))
											{
												foreach($_SESSION['Competences_Recherche_Metier'] as &$value){if($donnees[0]==$value){echo $donnees[1].";";break;}}
											}
											mysqli_free_result($result);
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="4">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							<tr height="20">
								<td><?php if($LangueAffichage=="FR"){echo "Formation : ";}else{echo "Training : ";}?></td>
								<td>
									<?php
									if(isset($_SESSION['Competences_Recherche_Formation']))
									{
										if($_SESSION['Competences_Recherche_Formation'] != "")
										{
											$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_formation");
											$nbenreg=mysqli_num_rows($result);
											while($donnees=mysqli_fetch_array($result))
											{
												foreach($_SESSION['Competences_Recherche_Formation'] as &$value){if($donnees[0]==$value){echo $donnees[1].";";break;}}
											}
											mysqli_free_result($result);
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="5">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							<tr height="20">
								<td>Qualification :</td>
								<td>
									<?php
									if(isset($_SESSION['Competences_Recherche_Qualification']))
									{
										if($_SESSION['Competences_Recherche_Qualification'] != "")
										{
											$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_qualification");
											$nbenreg=mysqli_num_rows($result);
											while($donnees=mysqli_fetch_array($result))
											{
												foreach($_SESSION['Competences_Recherche_Qualification'] as &$value){if($donnees[0]==$value){echo $donnees[1].";";break;}}
											}
											mysqli_free_result($result);
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="6">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							<tr height="20">
								<td>Qualification (éval.) :</td>
								<td>
									<?php
									if(isset($_SESSION['Competences_Recherche_EvaluationQualification']))
									{
										if($_SESSION['Competences_Recherche_EvaluationQualification'] != "")
										{
											foreach($_SESSION['Competences_Recherche_EvaluationQualification'] as &$value){echo $value.";";}
											echo '<form id="formulaire" method="POST" action="Recherche.php">';
											echo '<input type="hidden" name="Recherche_RAZ" value="7">';
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
											echo '<input type="image" src="../../Images/Suppression2.gif" alt="Supprimer ces éléments de la recherche" title="Supprimer ces éléments de la recherche">';
											echo '</form>';
										}
									}
									?>
								</td>
							</tr>
							
							<tr>
								<td colspan="3" align="center">
									<form id="formulaire" method="POST" action="Recherche.php">
										<input type="hidden" name="EnvoieRequete" value="-1">
										<input class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Chercher avec les éléments sélectionnés";}else{echo "Search with selected information";}?>">
									</form>
									<form id="formulaire" method="POST" action="Recherche.php">
										<input type="hidden" name="Recherche_RAZ" value="-1">
										<input class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Vider les critères de recherche";}else{echo "Clear search criteria";}?>">
									</form>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

<?php
if($_POST && isset($_POST['EnvoieRequete']))
{
	$result=mysqli_query($bdd,$requete);
	$nbenreg=mysqli_num_rows($result);
	if($nbenreg>0)
	{
?>
	<tr>
		<td>
			<table class="TableCompetences" width="80%">
				<tr>
					<td colspan="3">
						<form id="formulaire" method="POST" action="Personnes_Trouvees_Export.php">
							<input type="hidden" name="Requete" value="<?php echo $requete; ?>">
							<input class="Bouton" type="submit" value="Exporter les données trouvées">
							<a class="Modif" href="javascript:ExporterTout()"><img src="../../Images/excel.gif" border="0" alt="Competency List Excel" title="Competency List Excel"></a>
						</form>
					</td>
					<td colspan="4"></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Select.";}else{echo "Select.";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Code analytique";}else{echo "Analytic code";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name First name";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier/Fonction";}else{echo "Job/Function";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
					<?php
						if($IdPersonneConnectee=="4320" || $IdPersonneConnectee=="406" || $IdPersonneConnectee=="665" || DroitsPlateforme(array($IdPosteResponsableQualite)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)))
						{
							echo "<td class='EnTeteTableauCompetences'>N° badge</td>";
						}
					?>
					<td class="EnTeteTableauCompetences">NG</td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Email Pro";}else{echo "Email Pro";}?></td>
					<td class="EnTeteTableauCompetences">Matricule Daher</td>
					<td colspan="5" class="EnTeteTableauCompetences"></td>
				</tr>
	<?php
		$Couleur="#EEEEEE";
		while($row=mysqli_fetch_array($result))
		{
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
			
			$Plateforme_Identique=false;
			$Plateforme_Sortie=false;
			
			//Personne	//MAJ DU 27/12/12
			$Nom="";
			$Prenom="";
			$Contrat="";
			$NumBadge="";
			$Matricule="";
			$EmailPro="";
			$MatriculeDaher="";
			$requete_etatcivil="SELECT Nom, Prenom, Contrat, NumBadge,Matricule,EmailPro,MatriculeDaher FROM new_rh_etatcivil WHERE Id=".$row[0];
			$result_etatcivil=mysqli_query($bdd,$requete_etatcivil);
			$row_etatcivil=mysqli_fetch_array($result_etatcivil);
			$Nom=$row_etatcivil[0];
			$Prenom=$row_etatcivil[1];
			$Contrat=$row_etatcivil[2];
			$NumBadge=$row_etatcivil[3];
			$Matricule=$row_etatcivil[4];
			$EmailPro=$row_etatcivil[5];
			$MatriculeDaher=$row_etatcivil[6];
			
			//Plateforme
			$PLATEFORME="";
			$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id FROM new_competences_plateforme, new_competences_personne_plateforme";
			$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$row[0]." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
			$requete_plateforme.=" ORDER BY new_competences_plateforme.Libelle ASC";
			$result_plateforme=mysqli_query($bdd,$requete_plateforme);
			$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
			if($nbenreg_plateforme>0)
			{
				while($row_plateforme=mysqli_fetch_array($result_plateforme))
				{
					if($row_plateforme[1]==14){$Plateforme_Sortie=true;}	//14 signifie la plateforme Z-SORTIE
					if(isset($_SESSION['Id_Plateformes']))
					{
					    foreach($_SESSION['Id_Plateformes'] as &$value){if($row_plateforme[1]==$value){$Plateforme_Identique=true;}}
					}
					if($PLATEFORME==""){$PLATEFORME=$row_plateforme[0];}else{$PLATEFORME.="<br>".$row_plateforme[0];}
				}
			}
			
			//Prestation
			$PRESTATION="";
			$requete_prestation="SELECT DISTINCT new_competences_prestation.Code_Analytique FROM new_competences_prestation, new_competences_personne_prestation";
			$requete_prestation.=" WHERE new_competences_personne_prestation.Id_Personne=".$row[0]." AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation ";
			$requete_prestation.=" AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
			$requete_prestation.=" ORDER By new_competences_personne_prestation.Date_Debut DESC";
			//echo $requete_prestation;
			$result_prestation=mysqli_query($bdd,$requete_prestation);
			$nbenreg_prestation=mysqli_num_rows($result_prestation);
			if($nbenreg_prestation>0)
				{while($row_prestation=mysqli_fetch_array($result_prestation)){if($PRESTATION==""){$PRESTATION=$row_prestation[0];}else{$PRESTATION.="<br>".$row_prestation[0];}}}
			
			//Metier
			$METIER="";
			$requete_metier="SELECT DISTINCT new_competences_metier.Libelle FROM new_competences_metier, new_competences_personne_metier";
			$requete_metier.=" WHERE new_competences_personne_metier.Id_Personne=".$row[0]." AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier ";
			$requete_metier.=" ORDER By new_competences_personne_metier.Id DESC";
			$result_metier=mysqli_query($bdd,$requete_metier);
			$nbenreg_metier=mysqli_num_rows($result_metier);
			
			$req="SELECT Id 
			FROM new_competences_relation 
			WHERE Id_Personne=".$row[0]." 
			AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."') 
			AND (SELECT Id_Categorie_Qualification FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage)=147
			";
			$result_Qualif=mysqli_query($bdd,$req);
			$nbenreg_Qualif=mysqli_num_rows($result_Qualif);
			
			if($nbenreg_metier>0)
				{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.="<br>".$row_metier[0];}}}
	?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><input type="checkbox" name="checked"/></td>
					<td><?php echo $PLATEFORME;?></td>
					<td><?php echo $PRESTATION;?></td>
					<td><?php echo $Nom." ".$Prenom;?></td>
					<td><?php echo $METIER;?></td>
					<td><?php echo $Contrat;?></td>
					<?php
                        if(isset($_SESSION['Id_Personne']))
                        {
    						if($IdPersonneConnectee=="4320" || $IdPersonneConnectee=="406" || $IdPersonneConnectee=="665" || DroitsPlateforme(array($IdPosteResponsableQualite)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)) )
    						{
    							echo "<td>".$NumBadge."</td>";
    						}
                        }
						echo "<td>".$Matricule."</td>";
					?>
					<td><?php echo $EmailPro;?></td>
					<td><?php echo $MatriculeDaher;?></td>
					<td></td>
					<td width="20">
					<?php
					if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row[0]; ?>');"><img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modification"></a>
					<?php
					}
					?>
					</td>
					<td width="20">
					<?php
					if(DroitsFormationPlateforme(array($IdPosteReferentQualiteSysteme,$IdPosteResponsableQualite)))
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreCompetences2('<?php echo $row[0]; ?>');"><img src="../../Images/Competences.gif" border="0" alt="Competency List" title="Competency List"></a>
					<?php
					}
					?>
					</td>
					<td width="20">
					<?php
					if(DroitsFormationPlateforme(array($IdPosteReferentQualiteSysteme,$IdPosteResponsableQualite)))
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreCompetences2Export('<?php echo $row[0]; ?>');"><img src="../../Images/excel.gif" border="0" alt="Competency List Excel" title="Competency List Excel"></a>
					<?php
					}
					?>
					</td>
					<td width="20">
					<?php
					if($nbenreg_Qualif>0)
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreCompetences2ExportQBP(<?php echo $row[0];?>);">QBP</a>
					<?php
					}
					?>
					</td>
					<td width="20">
					<?php
					if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreProfil('Modif','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a>
					<?php
					}
					elseif($Plateforme_Sortie)
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreProfil('ModifPlateforme','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a>
					<?php
					}
					elseif($DroitsModifPrestation && $Plateforme_Identique)
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreProfil('ModifPresta','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a>
					<?php
					}
					else
					{
					?>
						<a class="Modif" href="javascript:OuvreFenetreProfil('Lecture','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a>
					<?php
					}
					?>
					</td>
					<td width="20">
					<?php
						if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
						{
					?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $row[0]; ?>');"><img src="../../Images/Suppression.gif" border="0" alt="Suppression" title="Suppression" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}"></a>
					<?php
						}
					?>
					</td>
					<td><input type="hidden" name="IdPersonne" value="<?php echo $row[0]; ?>"></td>
				</tr>
		<?php
		}	//Fin boucle
	}		//Fin If
		else{echo "<tr><td coslpan=6>Aucune personne ne correspond à ces critères.</td></tr>";}
		mysqli_free_result($result);	// Libération des résultats
		?>
						</table>
					</td>
				</tr>
<?php } ?>
			</table>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>