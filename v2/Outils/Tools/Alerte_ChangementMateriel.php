<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions.php");

$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$table1="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
			<tr>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>N° AAA</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:100px;'>S/N</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Modèle</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:100px;'>N°</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Personne</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Prestation</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>Date d'affectation</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Nouvelle prestation</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>Date d'affectation</td>
			</tr>";

$table3="</table>";

$req="SELECT 
		tools_materiel.Id,
		'Outils' AS TypeSelect,
		NumAAA,
		SN,
		IF(tools_famillemateriel.Id_TypeMateriel=".$TypeTelephone.",NumTelephone,
			IF(tools_famillemateriel.Id_TypeMateriel=".$TypeClef.",NumClef,
				IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMaqueDeControle.",NumMC,
					IF(tools_famillemateriel.Id_TypeMateriel=".$TypeInformatique.",NumPC,
						IF(tools_famillemateriel.Id_TypeMateriel=".$TypeVehicule.",Immatriculation,
							IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMacaron.",ImmatriculationAssociee,'')
						)
					)
				)
			)
		) AS Num,
		TAB2.EtatValidation AS TransfertEC,
		tools_famillemateriel.Id_TypeMateriel,
		(SELECT COUNT(Id)
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne  
		) AS NbContrat,
		(SELECT DateDebut
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne  
			ORDER BY DateDebut DESC, Id DESC
			LIMIT 1
		) AS DateDebutContrat,
		(SELECT DateFin
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne  
			ORDER BY DateDebut DESC, Id DESC
			LIMIT 1
		) AS DateFinContrat,
		tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
		DateReception AS DateDerniereAffectation,
		TAB2.Id_Prestation,
		TAB2.Id_Pole,
		(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
		(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
		TAB2.Id_Personne,
		(SELECT rh_personne_mouvement.Id_Prestation
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne 
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PrestationNew,
		(SELECT rh_personne_mouvement.Id_Pole
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne  
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PoleNew,
	(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne 
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVELLEPRESTATION,
	(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne  
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVEAUPOLE,
	(SELECT DateDebut
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne  
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS DateMouvementPrestation
	FROM 
		(SELECT *
		FROM 
		(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, EtatValidation,(@row_number:=@row_number + 1) AS rnk
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0
		AND tools_mouvement.Suppr=0
		AND tools_mouvement.Type=0
		AND tools_mouvement.EtatValidation IN (0,1)
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
		AS TAB
		GROUP BY Id_Materiel__Id_Caisse) AS TAB2
	LEFT JOIN 
		tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
	LEFT JOIN
		tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
	LEFT JOIN
		tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
	LEFT JOIN
		tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
	WHERE Id_Personne>0
	AND tools_materiel.Suppr=0 
	AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB2.Id_Prestation)=1
	AND 
	(
		(SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1)
		<> CONCAT(TAB2.Id_Prestation,'_',TAB2.Id_Pole)
		OR 
		(SELECT COUNT(Id)
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".date('Y-m-d')."'
		AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne
		)=0
	)

	UNION ALL
		
	SELECT 
		tools_caisse.Id,
		'Caisse' AS TypeSelect,
		NumAAA AS NumAAA,
		SN AS SN,
		Num AS Num,
		TAB2.EtatValidation AS TransfertEC,
		-1 AS Id_TypeMateriel,
		(SELECT COUNT(Id)
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne  
		) AS NbContrat,
		(SELECT DateDebut
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne  
			ORDER BY DateDebut DESC, Id DESC
			LIMIT 1
		) AS DateDebutContrat,
		(SELECT DateFin
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne  
			ORDER BY DateDebut DESC, Id DESC
			LIMIT 1
		) AS DateFinContrat,
		(SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
		DateReception AS DateDerniereAffectation,
		TAB2.Id_Prestation,
		TAB2.Id_Pole,
		(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
		(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
		TAB2.Id_Personne,
		(SELECT rh_personne_mouvement.Id_Prestation
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne 
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PrestationNew,
		(SELECT rh_personne_mouvement.Id_Pole
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne  
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PoleNew,
	(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne 
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVELLEPRESTATION,
	(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne  
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVEAUPOLE,
	(SELECT DateDebut
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne  
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS DateMouvementPrestation
	FROM 
		(
			SELECT *
			FROM 
			(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, EtatValidation,(@row_number:=@row_number + 1) AS rnk
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0
			AND tools_mouvement.Suppr=0
			AND tools_mouvement.Type=1
			AND tools_mouvement.EtatValidation IN (0,1)
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
			AS TAB
			GROUP BY Id_Materiel__Id_Caisse
		) AS TAB2
	LEFT JOIN 
		tools_caisse ON tools_caisse.Id=TAB2.Id_Materiel__Id_Caisse
	WHERE Id_Personne>0
	AND tools_caisse.Suppr=0 
	AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB2.Id_Prestation)=1
	AND 
	(
		(SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)
		FROM rh_personne_mouvement
		WHERE Suppr=0
		AND rh_personne_mouvement.Id_Personne=TAB2.Id_Personne
		AND rh_personne_mouvement.EtatValidation IN (0,1)
		AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1)
		<> CONCAT(TAB2.Id_Prestation,'_',TAB2.Id_Pole)
		OR 
		(SELECT COUNT(Id)
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND DateDebut<='".date('Y-m-d')."'
		AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		AND rh_personne_contrat.Id_Personne=TAB2.Id_Personne
		)=0
	)
	ORDER BY NOMPRENOM_PERSONNE
	";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$listePresta="";
$tabTools = array();
$i=0;
if ($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		if($listePresta<>""){$listePresta.=",";}
		$listePresta.= "'".$row['Id_Prestation']."_".$row['Id_Pole']."'";
		
		$LIBELLE_POLE="";
		if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
		
		$LIBELLE_NOUVEAUPOLE="";
		if($row['LIBELLE_NOUVEAUPOLE']<>""){$LIBELLE_NOUVEAUPOLE=" - ".$row['LIBELLE_NOUVEAUPOLE'];}
		
		if($row['NbContrat']>0){
		$tabTools[$i] = array("NumAAA" => $row['NumAAA'],"SN" => $row['SN'],"LIBELLE_MODELEMATERIEL" => $row['LIBELLE_MODELEMATERIEL']
						,"Num" => $row['Num'],"Personne" => $row['NOMPRENOM_PERSONNE']
						,"Id_Prestation" => $row['Id_Prestation'],"Id_Pole" => $row['Id_Pole']
						,"PrestaPole" => stripslashes(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE)
						,"DateDerniereAffectation" => AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])
						,"NewPrestaPole" => stripslashes(substr($row['LIBELLE_NOUVELLEPRESTATION'],0,7).$LIBELLE_NOUVEAUPOLE)
						,"DateMouvementPrestation" => $row['DateMouvementPrestation']);
		}
		else{
			$tabTools[$i] = array("NumAAA" => $row['NumAAA'],"SN" => $row['SN'],"LIBELLE_MODELEMATERIEL" => $row['LIBELLE_MODELEMATERIEL']
							,"Num" => $row['Num'],"Personne" => $row['NOMPRENOM_PERSONNE']
							,"Id_Prestation" => $row['Id_Prestation'],"Id_Pole" => $row['Id_Pole']
							,"PrestaPole" => stripslashes(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE)
							,"DateDerniereAffectation" => AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])
							,"NewPrestaPole" => "Sans contrat"
							,"DateMouvementPrestation" => $row['DateFinContrat']);
		}
		$i++;
	}
}
//Enlever envoie à CVERDIE, TMENSAC, EAUBERTIN
$Requete="
SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
FROM new_competences_personne_poste_prestation
WHERE Id_Personne>0 
AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.")
AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$listePresta.")
AND Id_Personne NOT IN (494,11506,15567)";

$result2=mysqli_query($bdd,$Requete);
$nbResulta2=mysqli_num_rows($result2);
$i=0;
if($nbResulta2>0){
	while($row=mysqli_fetch_array($result2)){
		if($row['EmailPro']<>""){
			
			$table2="";
			$req="SELECT DISTINCT Id_Prestation, Id_Pole
			FROM new_competences_personne_poste_prestation
			WHERE Id_Personne=".$row['Id_Personne']."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.")
			AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$listePresta.")";

			$resultPresta=mysqli_query($bdd,$req);
			$nbResultaPresta=mysqli_num_rows($resultPresta);
			if($nbResultaPresta>0){
				while($rowPresta=mysqli_fetch_array($resultPresta)){
					foreach($tabTools as $tools){
						if($tools['Id_Prestation']==$rowPresta['Id_Prestation'] && $tools['Id_Pole']==$rowPresta['Id_Pole'] && $tools['DateMouvementPrestation']<=date('Y-m-d')){
							$style="";
							if($tools['DateMouvementPrestation']<date('Y-m-d',strtotime(date('Y-m-d')." -2 month"))){
								$style="background-color:red;";
							}
							elseif($tools['DateMouvementPrestation']<date('Y-m-d',strtotime(date('Y-m-d')." -7 day"))){
								$style="background-color:orange;";
							}
							
							$table2.="
								<tr>
									<td style='border:1px solid black;text-align:center;'>".$tools['NumAAA']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['SN']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['LIBELLE_MODELEMATERIEL']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Num']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Personne']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['PrestaPole']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['DateDerniereAffectation']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['NewPrestaPole']."</td>
									<td style='border:1px solid black;text-align:center;".$style."'>".AfficheDateJJ_MM_AAAA($tools['DateMouvementPrestation'])."</td>
								</tr>
							";
						}
					}
				}
			}
			
			echo $row['EmailPro']."<br>";

			$sujet="Alerte changements prestations - Suivi du matériel";
					
			$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							Des personnes de votre secteur ont changé de prestation ou sont sans contrat.
							<br>
							Merci de vous connecter à l'extranet (Suivi du matériel -> Alerte changements !) et indiquer où se trouve le matériel de ces personnes.
							<br><br>
							".$table1.$table2.$table3."
							<br>
							Bonne journée,<br>
						</body>
					</html>";
			echo $message_html;
			echo "<br><br>";
			
			$Emails=$row['EmailPro'];
			//$Emails="pfauge@aaa-aero.com";
			//mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com');	
			$i++;			
		}
	}
}
?>