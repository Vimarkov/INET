<?php
require("../../Menu.php");

$_SESSION['FiltreEPE_Annee']=2023;

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:130px;height:110px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$HTTPServeur.$Lien."' >
						<img width='40px' src='../../Images/".$Image."' border='0' /><br>
						".$Libelle."
					</a>
				</td>
			</tr>";
	
	$css="";
	
	if($InfosSupp<>""){$css="bgcolor='".$Couleur."' width='250px'";}
	
	echo "
		<tr>
			<td ".$css.">
				".$InfosSupp."
			</tD>
		</tr>
	";
	echo "</table>";
}

function WidgetTDB($Libelle,$Image,$Couleur,$CouleurLogo,$nb,$Libelle2,$Lien){
	$couleurNombre="";
	if($nb<>"0" && $nb<>"0/0"){$couleurNombre="color:#de0006;";}
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:190px;height:90px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:35%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
								<img width='40px' src='../../Images/".$Image."' border='0' />
								</a>
							</td>
							<td width='65%' style='font-size:32px;".$couleurNombre."'>
								".$nb."
							</td>
						</tr>
						<tr>
							<td>
								".$Libelle."
							</td>
						</tr>
						<tr>
							<td colspan='2' style='color:red;'>
								".$Libelle2."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
}

$reqPrestaPoste = "SELECT Id_Prestation 
FROM new_competences_personne_poste_prestation 
WHERE Id_Personne =".$IdPersonneConnectee."  
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
";	
$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));

$requete2="SELECT DISTINCT new_rh_etatcivil.Id,CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
MatriculeAAA,
(SELECT COUNT(Id) FROM epe_personne_prestation WHERE Suppr=0 AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
AND epe_personne_prestation.Annee=".date('Y')." AND epe_personne_prestation.Id_Manager=0 AND (SELECT COUNT(Id_Prestation) FROM new_competences_personne_prestation WHERE 
							new_competences_personne_prestation.Id_Prestation=epe_personne_prestation.Id_Prestation
							AND new_competences_personne_prestation.Id_Pole=epe_personne_prestation.Id_Pole
							AND new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))>0) AS NbPresta
";
$requete="FROM new_rh_etatcivil
	RIGHT JOIN epe_personne_datebutoir 
	ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
	WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1)
	";

//Vérifier si appartient à une prestation OPTEA ou compétence
$requete.="AND (
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
	)>1) 
	AND (
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
		if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
			
		}
		else{
			$requete.="
			AND 
			((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
				)
			) ";
		}
	$requete.=")>0)
	AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".date('Y')." 
	AND IF((SELECT COUNT(Id)
FROM epe_personne 
WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".date('Y')." )>0,
(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
FROM epe_personne 
WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".date('Y')." LIMIT 1),
'A faire') IN ('A faire') ";

$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);

$nbRestant=0;
if($nbResulta>0){
	while($row=mysqli_fetch_array($result))
	{
		if($row['NbPresta']==0){$nbRestant++;}
	}
}

//FORMATION & QUALIF A CONFIGURER 
$req="SELECT new_competences_qualification.Id 
FROM new_competences_qualification, new_competences_categorie_qualification 
WHERE new_competences_categorie_qualification.Id_Categorie_Maitre<>0
AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id 
AND new_competences_qualification.Id NOT IN (1643,1644)
AND new_competences_qualification.Obligatoire=0 ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

$req="SELECT Id 
FROM new_competences_formation 
WHERE new_competences_formation.Obligatoire=0 ";
$result=mysqli_query($bdd,$req);
$nbenreg2=mysqli_num_rows($result);

$NbAConfigurer=$nbenreg+$nbenreg2;
if($NbAConfigurer==0){$NbAConfigurer="";}
else{$NbAConfigurer=" [".$NbAConfigurer."]";}

$requete="SELECT DISTINCT epe_personne.Id
FROM new_rh_etatcivil
	RIGHT JOIN epe_personne 
	ON new_rh_etatcivil.Id=epe_personne.Id_Personne 
	WHERE Suppr=0 AND epe_personne.Type='EPP' 
	AND ((SouhaitEvolutionON=1 AND SouhaitEvolution<>'') OR (SouhaitMobiliteON=1 AND SouhaitMobilite<>''))
	AND YEAR(epe_personne.DateButoir) = ".date('Y')." 
	AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') ";
if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
	
}
else{
	//Vérifier si appartient à une prestation OPTEA ou compétence
$requete.="AND
	( ";
	$requete.="
		((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
			)
		)
	) ";
}
$requete.="AND ((SouhaitEvolutionON=1 AND SouhaitEvolution<>'' AND (PasEvolutionEPP=0 AND (SELECT COUNT(Id) FROM epe_personne_souhaitevolution WHERE Id_EPE=epe_personne.Id)=0)) OR (SouhaitMobiliteON=1 AND SouhaitMobilite<>'' AND (PasMobiliteEPP=0 AND (SELECT COUNT(Id) FROM epe_personne_souhaitmobilite WHERE Id_EPE=epe_personne.Id)=0))) ";

$resultSouhaits=mysqli_query($bdd,$requete);
$nbSouhaits=mysqli_num_rows($resultSouhaits);

$req="SELECT DISTINCT new_rh_etatcivil.Id
	FROM new_rh_etatcivil
	WHERE new_rh_etatcivil.Id NOT IN (1726,1739)
	AND (MatriculeAAA='' OR (MatriculeAAA<>'' AND DateAncienneteCDI<='0001-01-01') OR MetierPaie='' OR Cadre=-1) AND Contrat IN ('','CDI','CDD','CDIC','CDIE')
	AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
		WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14)
		AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		)>0 
	AND (SELECT COUNT(new_competences_personne_prestation.Id) FROM new_competences_personne_prestation 
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		) > 0
	";
	if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){
		$req.="AND (SELECT Id_Plateforme FROM new_competences_personne_plateforme
		WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14) LIMIT 1) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";	
	}
	if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
		
	}
	else{
		$req.="
			AND
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
				AND 
				((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
					)
				) 
			)>0
			";
	}
	$result=mysqli_query($bdd,$req);
	$nbInfosMaquantes=mysqli_num_rows($result);
	
	$req="SELECT DISTINCT new_rh_etatcivil.Id
	FROM new_rh_etatcivil
	WHERE new_rh_etatcivil.Id NOT IN (1726,1739)
	AND MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
	AND MetierPaie<>'' AND Cadre IN (0,1) 
	AND new_rh_etatcivil.Id<>1739
	AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
		WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14)
		AND new_competences_personne_plateforme.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		)>0
	AND 
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
	)=0 ";
	if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
		
	}
	else{
		$req.="
			AND
			(SELECT COUNT(Id_Plateforme) 
			FROM new_competences_personne_plateforme
			WHERE new_rh_etatcivil.Id=Id_Personne 
			AND Id_Plateforme NOT IN (11,14)
			AND Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
				)
			)>0
			";
	}
	$result=mysqli_query($bdd,$req);
	$nbSansPresta=mysqli_num_rows($result);
	
	$dateCloture="";
	$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".date('Y')." ";
	$resultDateCloture=mysqli_query($bdd,$req);
	$nbDateCloture=mysqli_num_rows($resultDateCloture);
	if($nbDateCloture>0){
		$rowDateCloture=mysqli_fetch_array($resultDateCloture);
		$dateCloture=$rowDateCloture['DateCloture'];
	}
	
	$requete="SELECT DISTINCT new_rh_etatcivil.Id 
		FROM new_rh_etatcivil
		WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1)
		AND new_rh_etatcivil.Id<>1739
		AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
		AND 
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		)>0 
		AND (
			SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,TAB.DateButoir)) = ".date('Y')." 
			AND TAB.TypeEntretien = 'EPE'
			)=0
		AND (SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE epe_personne_na.Id_Personne=new_rh_etatcivil.Id 
			AND epe_personne_na.Annee=".date('Y')."
			AND epe_personne_na.TypeEntretien='EPE'
			)=0
		AND (SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id 
		AND epe_personne_attente.Annee=".date('Y')."
			AND epe_personne_attente.TypeEntretien='EPE')=0
		";
		if($dateCloture<>"" && $dateCloture>"0001-01-01"){
			$requete.=" AND DateAncienneteCDI<'".$dateCloture."' ";
		}
	if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
		
	}
	else{
		$requete.="
			AND
			(SELECT COUNT(Id_Plateforme) 
			FROM new_competences_personne_plateforme
			WHERE new_rh_etatcivil.Id=Id_Personne 
			AND Id_Plateforme NOT IN (11,14)
			AND Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
				)
			)>0
			";
	}
	$requete.=" UNION SELECT DISTINCT new_rh_etatcivil.Id 
		FROM new_rh_etatcivil
		WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1)
		AND new_rh_etatcivil.Id<>1739
		AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date('Y-m-d')." -2 year"))."'
		AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
		AND 
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		)>0 
		AND (SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 
				AND epe_personne.Type='EPP' 
				AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
				AND ModeBrouillon=0 
				AND YEAR(DateEntretien) >= ".date('Y',strtotime(date('Y-m-d')." -1 year"))."
			)=0
		AND (
			SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,TAB.DateButoir)) = ".date('Y')." 
			AND TAB.TypeEntretien = 'EPP'
			)=0
		AND (SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE epe_personne_na.Id_Personne=new_rh_etatcivil.Id 
			AND epe_personne_na.Annee=".date('Y')."
			AND epe_personne_na.TypeEntretien='EPP'
			)=0
		AND (SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id 
		AND epe_personne_attente.Annee=".date('Y')."
			AND epe_personne_attente.TypeEntretien='EPP')=0
		";
		if($dateCloture<>"" && $dateCloture>"0001-01-01"){
			$requete.=" AND DateAncienneteCDI<'".$dateCloture."' ";
		}
	if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
		
	}
	else{
		$requete.="
			AND
			(SELECT COUNT(Id_Plateforme) 
			FROM new_competences_personne_plateforme
			WHERE new_rh_etatcivil.Id=Id_Personne 
			AND Id_Plateforme NOT IN (11,14)
			AND Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
				)
			)>0
			";
	}
	$requete.=" UNION SELECT DISTINCT new_rh_etatcivil.Id 
		FROM new_rh_etatcivil
		WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1) 
		AND new_rh_etatcivil.Id<>1739
		AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date('Y-m-d')." -6 year"))."'
		AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
		AND 
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		)>0 
		AND (SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 
			AND epe_personne.Type='EPP' 
			AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
			AND ModeBrouillon=0 
			AND YEAR(DateEntretien) >= ".date('Y',strtotime(date('Y-m-d')." -5 year"))."
		)=0
		AND (
			SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,TAB.DateButoir)) = ".date('Y')." 
			AND TAB.TypeEntretien = 'EPP Bilan'
			)=0
		AND (SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE epe_personne_na.Id_Personne=new_rh_etatcivil.Id 
			AND epe_personne_na.Annee=".date('Y')."
			AND epe_personne_na.TypeEntretien='EPP Bilan'
			)=0
		AND (SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id 
		AND epe_personne_attente.Annee=".date('Y')."
			AND epe_personne_attente.TypeEntretien='EPP Bilan')=0
		";
		if($dateCloture<>"" && $dateCloture>"0001-01-01"){
			$requete.=" AND DateAncienneteCDI<'".$dateCloture."' ";
		}
	if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
		
	}
	else{
		$requete.="
			AND
			(SELECT COUNT(Id_Plateforme) 
			FROM new_competences_personne_plateforme
			WHERE new_rh_etatcivil.Id=Id_Personne 
			AND Id_Plateforme NOT IN (11,14)
			AND Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
				)
			)>0
			";
	}
	$result=mysqli_query($bdd,$requete);
	$nbSansDateButoir=mysqli_num_rows($result);
	$nbSansDateButoir2=$nbSansDateButoir;
	
	if($nbSansDateButoir==0){$nbSansDateButoir="";}
	else{$nbSansDateButoir=" [".$nbSansDateButoir."]";}

?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="5px"></td>
	</tr>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "COMMUNICATION";}else{echo "COMMUNICATION";}?>
		</td>
	</tr>
	<tr>
		<td  height="20px" valign="center" align="center" style="font-weight:bold;font-size:15px;">
			<table style="align:center;">
				<tr>
					<td width="50%" style="height:50px;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;border-radius: 15px;" bgcolor="#c1edff">&nbsp;&nbsp;
						<?php
							if($_SESSION['Id_Personne']>0){
								echo 'Note salarié : ';
								echo "<a target='_blank' href='NOTE - Campagne entretiens professionnels 2022.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							}
						?>
						<?php if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || $nbPoste>0){ ?>
						<br>
						<?php
							/*echo 'Note manager : ';
							echo "<a target='_blank' href='NOTE Manager - Campagne entretiens professionnels 2021.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<br>";*/
							echo 'Guide utilisateur manager : ';
							echo "<a target='_blank' href='Guide utilisateur Manager.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
						<?php } ?>
					</td>
					<?php if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || $nbPoste>0){ ?>
					<td width="50%" style="height:50px;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;border-radius: 15px;" bgcolor="#c1edff">&nbsp;&nbsp;
						<?php
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo 'Guide de réalisation "EPE" : ';
							echo "<a target='_blank' href='Guide de réalisation manager EPE - D-0705-010.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
					<br>
						<?php
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo 'Guide de réalisation "EPP" : ';
							echo "<a target='_blank' href='Guide de réalisation manager EPP - D-0705-011.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
					<br>
						<?php
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo 'Guide de réalisation "Etat des lieux récapitulatif du parcours professionnel" : ';
							echo "<a target='_blank' href='Guide de réalisation manager Etat des lieux récapitulatif du parcours professionnel - D-0705-09.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
					</td>
					<?php } ?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "EPE / EPP";}else{echo "EPE / EPP";}?>
		</td>
	</tr>
	<tr >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;valign:top;font-weight:bold;font-size:18px;">
			La campagne EIP (Entretien Individuel de Progrès) anciennement EPE/EPP se fera désormais sur Workday. <br>
			Rendez-vous sur Workday pour la campagne EIP 2024 ! 
		</td>
	</tr>
	<tr>
		<td width="80%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<?php
					if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)))
					{
					?>
					<td align="center" style="width:20%" valign="top">
						<table>
							<tr>
								<td>
								<?php
								
								$libelle7="";
								if($_SESSION["Langue"]=="FR"){$libelle="Personnes avec plusieurs affectations";}else{$libelle="People with multiple assignments";}
								WidgetTDB($libelle,"Formation/Association.png","#9bb7df","#2e578e",$nbRestant,$libelle7,"Outils/EPE/Liste_PlusieursAffectations.php");
								
								$libelle7="";
								if($_SESSION["Langue"]=="FR"){$libelle="Souhaits EPP à affecter";}else{$libelle="EPP wishes to assign";}
								WidgetTDB($libelle,"RH/Question.png","#cad0cd","#717f7a",$nbSouhaits,$libelle7,"Outils/EPE/Liste_Souhaits.php");
								?>
								</td>
							</tr>
						</table>
					</td>
					<?php
					}
					?>
					<td align="center" style="width:60%" valign="top">
						<table>
							<tr>
								<td>
								<?php
								if($LangueAffichage=="FR"){$libelle="<br>EPE / EPP";}else{$libelle="<br>EPE / EPP";}
								Widget($libelle,"Outils/EPE/Liste_EPE.php","EPE.png","#fa2036");
								
									
								if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || $nbPoste>0){
									if($LangueAffichage=="FR"){$libelle="<br>Changer le manager";}else{$libelle="<br>Change the manager";}
									Widget($libelle,"Outils/EPE/ChangementManager.php","RH/Transfert.png","#b13095");
								}	
								
								if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || $nbPoste>0){	
									if($LangueAffichage=="FR"){$libelle="<br>Indicateurs";}else{$libelle="<br>Indicators";}
									$infos="";
									if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){
										if($nbInfosMaquantes>0 || $nbSansPresta>0 || $nbSansDateButoir2>0){
											$infos="<table>";
											if($nbInfosMaquantes>0){
												if($LangueAffichage=="FR"){
													$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nbInfosMaquantes." personne(s) avec des informations manquantes</b></td></tr>";
												}
												else{
													$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nbInfosMaquantes." people with missing information </b></td></tr>";
												}
											}
											if($nbSansPresta>0){
												if($LangueAffichage=="FR"){
													$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nbSansPresta." personne(s) sans prestation</b></td></tr>";
												}
												else{
													$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nbSansPresta." without site </b></td></tr>";
												}
											}
											if($nbSansDateButoir2>0){
												if($LangueAffichage=="FR"){
													$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nbSansDateButoir2." personne(s) sans date butoir</b></td></tr>";
												}
												else{
													$infos.= "<tr><td style=\"color:red\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$nbSansDateButoir2." without deadline </b></td></tr>";
												}
											}
											$infos.="</table>";
										}
									}
									Widget($libelle,"Outils/EPE/TDB_Indicateurs.php","Formation/Graphique.png","#63c021",$infos);
								}
								?>
								</td>
							</tr>
						</table>
					</td>
					<?php
					if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){
					?>
					<td align="center" width="25%" valign="top">
						<table style='border-spacing:15px;display:inline-table;' >
							<tr>
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#f5f74b;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#f5f74b'>
									<table width='100%' height='100%'>	
										<tr>
											<td style="width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;">
												<img width='40px' src='../../Images/Formation/Parametrage.png' border='0' /><br>
											</td>
										</tr>
										<tr>
											<td>
												<table style="width:100%; align:left; valign:top;">
												<?php
													if($LangueAffichage=="FR"){Titre("Clôture campagne","Outils/EPE/Liste_ClotureCampagne.php");}
													else{Titre("Campaign closing","Outils/EPE/Liste_ClotureCampagne.php");}
													
													if($LangueAffichage=="FR"){Titre("Dates butoirs".$nbSansDateButoir,"Outils/EPE/Liste_DateButoir.php");}
													else{Titre("Deadline".$nbSansDateButoir,"Outils/EPE/Liste_DateButoir.php");}
													
													if($LangueAffichage=="FR"){Titre("Informations du personnel","Outils/EPE/InformationPersonnel.php");}
													else{Titre("Staff information","Outils/EPE/InformationPersonnel.php");}
													
													if($LangueAffichage=="FR"){Titre("Formations & Qualifications obligatoires".$NbAConfigurer,"Outils/EPE/Liste_QualifsObligatoires.php");}
													else{Titre("Compulsory training & qualifications".$NbAConfigurer,"Outils/EPE/Liste_QualifsObligatoires.php");}
													
													if($LangueAffichage=="FR"){Titre("Mobilité","Outils/EPE/Liste_Mobilite.php");}
													else{Titre("Mobility","Outils/EPE/Liste_Mobilite.php");}
													
													if($LangueAffichage=="FR"){Titre("Motif de non réalisation","Outils/EPE/Liste_MotifNonRealisation.php");}
													else{Titre("Reason for non-achievement","Outils/EPE/Liste_MotifNonRealisation.php");}
													
													if($LangueAffichage=="FR"){Titre("Progressions salariale et professionnelles","Outils/EPE/Liste_Progressions.php");}
													else{Titre("Salary and professional progressions","Outils/EPE/Liste_Progressions.php");}
													
													if($LangueAffichage=="FR"){Titre("Type d'évolution","Outils/EPE/Liste_TypeEvolution.php");}
													else{Titre("Evolution type","Outils/EPE/Liste_TypeEvolution.php");}
												?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
	$annee=$_SESSION['FiltreEPE_Annee'];
	$dateDebut=date($annee.'-01-01');
	$dateFin=date($annee.'-12-31');
	
	if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || $nbPoste>0){ 
	
	$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA ";
	$requete="FROM new_rh_etatcivil
		RIGHT JOIN epe_personne_datebutoir 
		ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
		WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
				) 
		AND 
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
				AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
			)>0 
			AND 
			(
				SELECT Id_Prestation
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
				ORDER BY Date_Fin DESC, Date_Debut DESC
				LIMIT 1
			) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		";

	$requete.="AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
	
	$result=mysqli_query($bdd,$requete2.$requete);
	$nbResulta=mysqli_num_rows($result);

	$EPEAFaire=0;
	$EPEBrouillon=0;
	$EPESignatureS=0;
	$EPESignatureE=0;
	$EPERealise=0;
	
	$EPPAFaire=0;
	$EPPBrouillon=0;
	$EPPSignatureS=0;
	$EPPSignatureE=0;
	$EPPRealise=0;
	
	$EPPBAFaire=0;
	$EPPBBrouillon=0;
	$EPPBSignatureS=0;
	$EPPBSignatureE=0;
	$EPPBRealise=0;
	
	$EPEResteUER=0;
	$EPESigneUER=0;
	$EPERealiseUER=0;
	
	$EPPResteUER=0;
	$EPPSigneUER=0;
	$EPPRealiseUER=0;
	
	$EPPBResteUER=0;
	$EPPBSigneUER=0;
	$EPPBRealiseUER=0;
	
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			$req="SELECT Id_Prestation,Id_Pole 
				FROM new_competences_personne_prestation
				WHERE Id_Personne=".$row['Id']." 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
				ORDER BY Date_Fin DESC, Date_Debut DESC ";
			$resultch=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($resultch);
			
			if($nb==0){
				$reqNb="SELECT DISTINCT new_rh_etatcivil.Id,MatriculeAAA,
				TypeEntretien AS TypeE,
				IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
				epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
				IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire')
				AS Etat,
				(SELECT Id_Evaluateur
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
				(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
				(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole,
				(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme
				FROM new_rh_etatcivil
				RIGHT JOIN epe_personne_datebutoir 
				ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
				WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
				) 
				AND new_rh_etatcivil.Id=".$row['Id']."
				AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." 
				AND IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
						(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
						'A faire') NOT IN ('A faire')
				";
	
			}
			else{
				$reqNb="SELECT DISTINCT new_rh_etatcivil.Id,MatriculeAAA,
				TypeEntretien AS TypeE,
				IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
				epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
				IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire')
				AS Etat,
				(SELECT Id_Evaluateur
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
				(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
				(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole,
				(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme
				FROM new_rh_etatcivil
				RIGHT JOIN epe_personne_datebutoir 
				ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
				WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
				) 
				AND new_rh_etatcivil.Id=".$row['Id']."
				AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
			}
			
			$ResultNb=mysqli_query($bdd,$reqNb);
			$leNb=mysqli_num_rows($ResultNb);
			
			$Manager="";
			$Id_Manager=0;
		
			if($leNb>0){
				while($rowNb=mysqli_fetch_array($ResultNb))
				{
					
					$Id_Prestation=0;
					$Id_Pole=0;
					$Id_Plateforme=0;

					$req="SELECT Id_Prestation,Id_Pole 
						FROM new_competences_personne_prestation
						WHERE Id_Personne=".$row['Id']." 
						AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
						ORDER BY Date_Fin DESC, Date_Debut DESC
						";
					$resultch=mysqli_query($bdd,$req);
					$nb=mysqli_num_rows($resultch);
					$Id_PrestationPole="0_0";
					if($nb>0){
						$rowMouv=mysqli_fetch_array($resultch);
						$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
					}

					$TableauPrestationPole=explode("_",$Id_PrestationPole);
					$Id_Prestation=$TableauPrestationPole[0];
					$Id_Pole=$TableauPrestationPole[1];
					
					$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
					$ResultPresta=mysqli_query($bdd,$req);
					$NbPrest=mysqli_num_rows($ResultPresta);
					if($NbPrest>0){
						$RowPresta=mysqli_fetch_array($ResultPresta);
						$Id_Plateforme=$RowPresta['Id_Plateforme'];
					}
					if($rowNb['Etat']=="A faire"){
						$req="SELECT Id_Prestation,Id_Pole 
							FROM new_competences_personne_prestation
							WHERE Id_Personne=".$row['Id']." 
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
							ORDER BY Date_Fin DESC, Date_Debut DESC ";
						
						$resultch=mysqli_query($bdd,$req);
						$lenb=mysqli_num_rows($resultch);
						
						if($lenb>1){
							$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ORDER BY Id DESC";
							$ResultlaPresta=mysqli_query($bdd,$req);
							$NblaPresta=mysqli_num_rows($ResultlaPresta);
							if($NblaPresta>0){
								$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
								$Id_Prestation=$RowlaPresta['Id_Prestation'];
								$Id_Pole=$RowlaPresta['Id_Pole'];
								$Id_Plateforme=$RowlaPresta['Id_Plateforme'];
							}
						}
					}
					else{
						$tab = explode("_",$rowNb['PrestaPole']);
						$Id_Prestation=$tab[0];
						$Id_Pole=$tab[1];
						$Id_Plateforme=$rowNb['Id_Plateforme'];
					}
					
					
					
					if($rowNb['Etat']=="A faire"){
						$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
						$ResultlaPresta=mysqli_query($bdd,$req);
						$NblaPresta=mysqli_num_rows($ResultlaPresta);
						if($NblaPresta>0){
							$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
							$Id_Manager=$RowlaPresta['Id_Manager'];
							$Manager=$RowlaPresta['Manager'];
						}
						else{
							$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$row['Id']."
									AND Id_Personne>0
									ORDER BY Backup ";
							$ResultManager2=mysqli_query($bdd,$req);
							$NbManager2=mysqli_num_rows($ResultManager2);
							if($NbManager2>0){
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurProjet."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne>0
									ORDER BY Backup ";
								$ResultManager=mysqli_query($bdd,$req);
								$NbManager=mysqli_num_rows($ResultManager);
								if($NbManager>0){
									$RowManager=mysqli_fetch_array($ResultManager);
									$Manager=$RowManager['Personne'];
									$Id_Manager=$RowManager['Id'];
								}
							}
							else{
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteChefEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$row['Id']."
									AND Id_Personne>0
									ORDER BY Backup ";
								$ResultManager2=mysqli_query($bdd,$req);
								$NbManager2=mysqli_num_rows($ResultManager2);
								if($NbManager2>0){
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										AND Id_Personne>0
										ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
										$Id_Manager=$RowManager['Id'];
									}
								}
								else{
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteChefEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne>0
									ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
										$Id_Manager=$RowManager['Id'];
									}
								}
							}
						}
					}
					else{
						$Manager=$rowNb['Manager'];
						$Id_Manager=$rowNb['Id_Manager'];
					}
					
					$PlateformeEC=0;
					$req="SELECT Id_Prestation
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_competences_prestation
						ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$Id_Plateforme."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.") ";
					$ResultPlat=mysqli_query($bdd,$req);
					$NbPlat=mysqli_num_rows($ResultPlat);
					$Plat="";
					if($NbPlat>0){
						$PlateformeEC=1;
					}
					
					if($PlateformeEC>0){
						If($rowNb['TypeE']=="EPE"){
							if($rowNb['Etat']=="A faire"){$EPEResteUER++;}
							elseif($rowNb['Etat']=="Brouillon"){$EPEResteUER++;}
							elseif($rowNb['Etat']=="Signature salarié"){$EPESigneUER++;}
							elseif($rowNb['Etat']=="Signature manager"){$EPESigneUER++;}
							elseif($rowNb['Etat']=="Réalisé"){$EPERealiseUER++;}
						}
						elseIf($rowNb['TypeE']=="EPP"){
							if($rowNb['Etat']=="A faire"){$EPPResteUER++;}
							elseif($rowNb['Etat']=="Brouillon"){$EPPResteUER++;}
							elseif($rowNb['Etat']=="Signature salarié"){$EPPSigneUER++;}
							elseif($rowNb['Etat']=="Signature manager"){$EPPSigneUER++;}
							elseif($rowNb['Etat']=="Réalisé"){$EPPRealiseUER++;}
						}
						elseIf($rowNb['TypeE']=="EPP Bilan"){
							if($rowNb['Etat']=="A faire"){$EPPBResteUER++;}
							elseif($rowNb['Etat']=="Brouillon"){$EPPBResteUER++;}
							elseif($rowNb['Etat']=="Signature salarié"){$EPPBSigneUER++;}
							elseif($rowNb['Etat']=="Signature manager"){$EPPBSigneUER++;}
							elseif($rowNb['Etat']=="Réalisé"){$EPPBRealiseUER++;}
						}
					}
					
					$PlateformeEC=0;
					$req="SELECT Id_Plateforme
						FROM new_competences_personne_poste_plateforme 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Plateforme=".$Id_Plateforme."
						AND Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") ";
					$ResultPlat=mysqli_query($bdd,$req);
					$NbPlat=mysqli_num_rows($ResultPlat);
					$Plat="";
					if($NbPlat>0){
						$PlateformeEC=1;
					}
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || $PlateformeEC>0 || $Id_Manager==$_SESSION['Id_Personne']){
						If($rowNb['TypeE']=="EPE"){
							if($rowNb['Etat']=="A faire"){$EPEAFaire++;}
							elseif($rowNb['Etat']=="Brouillon"){$EPEBrouillon++;}
							elseif($rowNb['Etat']=="Signature salarié"){$EPESignatureS++;}
							elseif($rowNb['Etat']=="Signature manager"){$EPESignatureE++;}
							elseif($rowNb['Etat']=="Réalisé"){$EPERealise++;}
						}
						elseIf($rowNb['TypeE']=="EPP"){
							if($rowNb['Etat']=="A faire"){$EPPAFaire++;}
							elseif($rowNb['Etat']=="Brouillon"){$EPPBrouillon++;}
							elseif($rowNb['Etat']=="Signature salarié"){$EPPSignatureS++;}
							elseif($rowNb['Etat']=="Signature manager"){$EPPSignatureE++;}
							elseif($rowNb['Etat']=="Réalisé"){$EPPRealise++;}
						}
						elseIf($rowNb['TypeE']=="EPP Bilan"){
							if($rowNb['Etat']=="A faire"){$EPPBAFaire++;}
							elseif($rowNb['Etat']=="Brouillon"){$EPPBBrouillon++;}
							elseif($rowNb['Etat']=="Signature salarié"){$EPPBSignatureS++;}
							elseif($rowNb['Etat']=="Signature manager"){$EPPBSignatureE++;}
							elseif($rowNb['Etat']=="Réalisé"){$EPPBRealise++;}
						}
					}
				}
			}
			
		}
	}
	
	$EPEtotRealise=0;
	if(($EPEAFaire+$EPEBrouillon+$EPESignatureS+$EPESignatureE+$EPERealise)>0){
		$EPEtotRealise=round((($EPESignatureS+$EPESignatureE+$EPERealise)/($EPEAFaire+$EPEBrouillon+$EPESignatureS+$EPESignatureE+$EPERealise))*100,1);
	}
	$EPPtotRealise=0;
	if(($EPPAFaire+$EPPBrouillon+$EPPSignatureS+$EPPSignatureE+$EPPRealise)>0){
		$EPPtotRealise=round((($EPPSignatureS+$EPPSignatureE+$EPPRealise)/($EPPAFaire+$EPPBrouillon+$EPPSignatureS+$EPPSignatureE+$EPPRealise))*100,1);
	}
	$EPPBtotRealise=0;
	if(($EPPBAFaire+$EPPBBrouillon+$EPPBSignatureS+$EPPBSignatureE+$EPPBRealise)>0){
		$EPPBtotRealise=round((($EPPBSignatureS+$EPPBSignatureE+$EPPBRealise)/($EPPBAFaire+$EPPBBrouillon+$EPPBSignatureS+$EPPBSignatureE+$EPPBRealise))*100,1);
	}
	
	$EPEtotRealiseUER=0;
	if(($EPEResteUER+$EPESigneUER+$EPERealiseUER)>0){
		$EPEtotRealiseUER=round((($EPESigneUER+$EPERealiseUER)/($EPEResteUER+$EPESigneUER+$EPERealiseUER))*100,1);
	}
	$EPPtotRealiseUER=0;
	if(($EPPResteUER+$EPPSigneUER+$EPPRealiseUER)>0){
		$EPPtotRealiseUER=round((($EPPSigneUER+$EPPRealiseUER)/($EPPResteUER+$EPPSigneUER+$EPPRealiseUER))*100,1);
	}
	$EPPBtotRealiseUER=0;
	if(($EPPBResteUER+$EPPBSigneUER+$EPPBRealiseUER)>0){
		$EPPBtotRealiseUER=round((($EPPBSigneUER+$EPPBRealiseUER)/($EPPBResteUER+$EPPBSigneUER+$EPPBRealiseUER))*100,1);
	}
	
	$EPEtotSigne=0;
	if(($EPEAFaire+$EPEBrouillon+$EPESignatureS+$EPESignatureE+$EPERealise)>0){
		$EPEtotSigne=round(($EPERealise/($EPEAFaire+$EPEBrouillon+$EPESignatureS+$EPESignatureE+$EPERealise))*100,1);
	}
	$EPPtotSigne=0;
	if(($EPPAFaire+$EPPBrouillon+$EPPSignatureS+$EPPSignatureE+$EPPRealise)>0){
		$EPPtotSigne=round(($EPPRealise/($EPPAFaire+$EPPBrouillon+$EPPSignatureS+$EPPSignatureE+$EPPRealise))*100,1);
	}
	$EPPBtotSigne=0;
	if(($EPPBAFaire+$EPPBBrouillon+$EPPBSignatureS+$EPPBSignatureE+$EPPBRealise)>0){
		$EPPBtotSigne=round(($EPPBRealise/($EPPBAFaire+$EPPBBrouillon+$EPPBSignatureS+$EPPBSignatureE+$EPPBRealise))*100,1);
	}
	
	$EPEtotSigneUER=0;
	if(($EPEResteUER+$EPESigneUER+$EPERealiseUER)>0){
		$EPEtotSigneUER=round(($EPERealiseUER/($EPEResteUER+$EPESigneUER+$EPERealiseUER))*100,1);
	}
	$EPPtotSigneUER=0;
	if(($EPPResteUER+$EPPSigneUER+$EPPRealiseUER)>0){
		$EPPtotSigneUER=round(($EPPRealiseUER/($EPPResteUER+$EPPSigneUER+$EPPRealiseUER))*100,1);
	}
	$EPPBtotSigneUER=0;
	if(($EPPBResteUER+$EPPBSigneUER+$EPPBRealiseUER)>0){
		$EPPBtotSigneUER=round(($EPPBRealiseUER/($EPPBResteUER+$EPPBSigneUER+$EPPBRealiseUER))*100,1);
	}
	
	
	
	$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA ";
	$requete="FROM new_rh_etatcivil
		RIGHT JOIN epe_personne_datebutoir 
		ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
		WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
				) 
		AND
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		)>0 
		AND 
		(
			SELECT Id_Prestation
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			ORDER BY Date_Fin DESC, Date_Debut DESC
			LIMIT 1
		) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
	$result=mysqli_query($bdd,$requete2.$requete);
	$nbResulta=mysqli_num_rows($result);
	
	$EPEResteFR=0;
	$EPESigneFR=0;
	$EPERealiseFR=0;
	
	$EPPResteFR=0;
	$EPPSigneFR=0;
	$EPPRealiseFR=0;
	
	$EPPBResteFR=0;
	$EPPBSigneFR=0;
	$EPPBRealiseFR=0;
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			$req="SELECT Id_Prestation,Id_Pole 
				FROM new_competences_personne_prestation
				WHERE Id_Personne=".$row['Id']." 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
				ORDER BY Date_Fin DESC, Date_Debut DESC ";
			$resultch=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($resultch);
			
			if($nb==0){
				$reqNb="SELECT DISTINCT new_rh_etatcivil.Id,MatriculeAAA,
				TypeEntretien AS TypeE,
				IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,DateButoir,
				epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
				IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire')
				AS Etat,
				(SELECT Id_Evaluateur
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
				(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole
				FROM new_rh_etatcivil
				RIGHT JOIN epe_personne_datebutoir 
				ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
				WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
				) 
				AND new_rh_etatcivil.Id=".$row['Id']."
				AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." 
				AND IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
						(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
						'A faire') NOT IN ('A faire')
				";
	
			}
			else{
				$reqNb="SELECT DISTINCT new_rh_etatcivil.Id,MatriculeAAA,
				TypeEntretien AS TypeE,
				IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,DateButoir,
				epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
				IF((SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
				(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
				'A faire')
				AS Etat,
				(SELECT Id_Evaluateur
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
				(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole
				FROM new_rh_etatcivil
				RIGHT JOIN epe_personne_datebutoir 
				ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
				WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
				) 
				AND new_rh_etatcivil.Id=".$row['Id']."
				AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
			}

			$ResultNb=mysqli_query($bdd,$reqNb);
			$leNb=mysqli_num_rows($ResultNb);
			
			if($leNb>0){
				while($rowNb=mysqli_fetch_array($ResultNb))
				{
					If($rowNb['TypeE']=="EPE"){
						if($rowNb['Etat']=="A faire"){$EPEResteFR++;}
						elseif($rowNb['Etat']=="Brouillon"){$EPEResteFR++;}
						elseif($rowNb['Etat']=="Signature salarié"){$EPESigneFR++;}
						elseif($rowNb['Etat']=="Signature manager"){$EPESigneFR++;}
						elseif($rowNb['Etat']=="Réalisé"){$EPERealiseFR++;}
					}
					elseIf($rowNb['TypeE']=="EPP"){
						if($rowNb['Etat']=="A faire"){$EPPResteFR++;}
						elseif($rowNb['Etat']=="Brouillon"){$EPPResteFR++;}
						elseif($rowNb['Etat']=="Signature salarié"){$EPPSigneFR++;}
						elseif($rowNb['Etat']=="Signature manager"){$EPPSigneFR++;}
						elseif($rowNb['Etat']=="Réalisé"){$EPPRealiseFR++;}
					}
					elseIf($rowNb['TypeE']=="EPP Bilan"){
						if($rowNb['Etat']=="A faire"){$EPPBResteFR++;}
						elseif($rowNb['Etat']=="Brouillon"){$EPPBResteFR++;}
						elseif($rowNb['Etat']=="Signature salarié"){$EPPBSigneFR++;}
						elseif($rowNb['Etat']=="Signature manager"){$EPPBSigneFR++;}
						elseif($rowNb['Etat']=="Réalisé"){$EPPBRealiseFR++;}
					}
				}
			}
		}
	}
	
	$EPEtotRealiseFR=0;
	if(($EPEResteFR+$EPESigneFR+$EPERealiseFR)>0){
		$EPEtotRealiseFR=round((($EPESigneFR+$EPERealiseFR)/($EPEResteFR+$EPESigneFR+$EPERealiseFR))*100,1);
		
	}
	$EPPtotRealiseFR=0;
	if(($EPPResteFR+$EPPSigneFR+$EPPRealiseFR)>0){
		$EPPtotRealiseFR=round((($EPPSigneFR+$EPPRealiseFR)/($EPPResteFR+$EPPSigneFR+$EPPRealiseFR))*100,1);
	}
	$EPPBtotRealiseFR=0;
	if(($EPPBResteFR+$EPPBSigneFR+$EPPBRealiseFR)>0){
		$EPPBtotRealiseFR=round((($EPPBSigneFR+$EPPBRealiseFR)/($EPPBResteFR+$EPPBSigneFR+$EPPBRealiseFR))*100,1);
	}
	
	$EPEtotSigneFR=0;
	if(($EPEResteFR+$EPESigneFR+$EPERealiseFR)>0){
		$EPEtotSigneFR=round(($EPERealiseFR/($EPEResteFR+$EPESigneFR+$EPERealiseFR))*100,1);
	}
	$EPPtotSigneFR=0;
	if(($EPPResteFR+$EPPSigneFR+$EPPRealiseFR)>0){
		$EPPtotSigneFR=round(($EPPRealiseFR/($EPPResteFR+$EPPSigneFR+$EPPRealiseFR))*100,1);
	}
	$EPPBtotSigneFR=0;
	if(($EPPBResteFR+$EPPBSigneFR+$EPPBRealiseFR)>0){
		$EPPBtotSigneFR=round(($EPPBRealiseFR/($EPPBResteFR+$EPPBSigneFR+$EPPBRealiseFR))*100,1);
	}
	?>
	<tr>
		<td align="center">
			<table style="width:65%;background-color:#ffffff;display:inline-table;border-style:outset;border-spacing:0;">
				<tr>
					<td align="left">
							<table style="width:100%; border-spacing:0;" align="center">
								<tr bgcolor='#2c2da3'>
									<td style="font-size:14px;color:#ffffff;" align="center" colspan="8"><b><?php if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ echo "RH";}else{echo "MANAGER";}?></b></td>
									<?php if($nbPoste>0){ 
										$req="SELECT DISTINCT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme 
											FROM new_competences_personne_poste_prestation 
											LEFT JOIN new_competences_prestation
											ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
											WHERE Id_Personne=".$_SESSION["Id_Personne"]."
											AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.") 
											ORDER BY Plateforme";
										$ResultPlat=mysqli_query($bdd,$req);
										$NbPlat=mysqli_num_rows($ResultPlat);
										$Plat="";
										if($NbPlat>0){
											while($rowPlat=mysqli_fetch_array($ResultPlat)){
												if($Plat<>""){$Plat.="<br>";}
												$Plat.=$rowPlat['Plateforme'];
											}
										}
									?>
									<td <?php if($Plat<>""){echo "id='leHover'";}?> style="font-size:14px;color:#ffffff;" align="center" colspan="2"><b>UER<?php if($Plat<>""){echo "<span>".$Plat."</span>";}?></b></td><?php } ?>
									<td style="font-size:14px;color:#ffffff;" align="center" colspan="2"><b>NATIONALE</b></td>
								</tr>
								<tr bgcolor='#4d9ced'>
									<td width='8%' style="font-size:14px;" align="center"><b>Type</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>A faire</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>Brouillon</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>Signature salarié</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>Signature manager</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>Réalisé</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>% réalisé</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>% signé</b></td>
									<?php if($nbPoste>0){ ?>
									<td width='8%' style="font-size:14px;" align="center"><b>% réalisé</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>% signé</b></td>
									<?php } ?>
									<td width='8%' style="font-size:14px;" align="center"><b>% réalisé</b></td>
									<td width='8%' style="font-size:14px;" align="center"><b>% signé</b></td>
								</tr>
								<tr>
									<td width='8%' style="font-size:14px;" align="center"><b>EPE</b></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEAFaire; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEBrouillon; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPESignatureS; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPESignatureE; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPERealise; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEtotRealise; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEtotSigne; ?> %</td>
									<?php if($nbPoste>0){ ?>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEtotRealiseUER; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEtotSigneUER; ?> %</td>
									<?php } ?>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEtotRealiseFR; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPEtotSigneFR; ?> %</td>
								</tr>
								<tr bgcolor='#cfe6fd'>
									<td width='8%' style="font-size:14px;" align="center"><b>EPP</b></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPAFaire; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBrouillon; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPSignatureS; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPSignatureE; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPRealise; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPtotRealise; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPtotSigne; ?> %</td>
									<?php if($nbPoste>0){ ?>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPtotRealiseUER; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPtotSigneUER; ?> %</td>
									<?php } ?>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPtotRealiseFR; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPtotSigneFR; ?> %</td>
								</tr>
								<tr>
									<td width='8%' style="font-size:14px;" align="center"><b>EPP Bilan</b></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBAFaire; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBBrouillon; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBSignatureS; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBSignatureE; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBRealise; ?></td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBtotRealise; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBtotSigne; ?> %</td>
									<?php if($nbPoste>0){ ?>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBtotRealiseUER; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBtotSigneUER; ?> %</td>
									<?php } ?>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBtotRealiseFR; ?> %</td>
									<td width='8%' style="font-size:14px;" align="center"><?php echo $EPPBtotSigneFR; ?> %</td>
								</tr>
							</table>
						</td>
					</tr>
				</td>
		</table>
	</tr>
	<?php } ?>
	<tr>
		<td height="150px"></td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>