<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//ECME
$Requete="

	SELECT DISTINCT
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole
	FROM 
	(
	SELECT
		tools_materiel.Id AS ID,
		'Outils' AS TYPESELECT,
		NumAAA,
		NumFicheImmo,
		SN,
		DateDerniereVerification,
		PeriodiciteVerification,
		Designation,
		tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
		(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
		tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
		tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
		(SELECT IF(TAB_Mouvement.Id_Caisse=0,
					CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
					(
					SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
			)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
		) AS AffectationMouvement
		FROM
			tools_materiel
		LEFT JOIN
			tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
		LEFT JOIN
			tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
		WHERE tools_materiel.Suppr=0
		AND PeriodiciteVerification>0
		AND DateDerniereVerification>'0001-01-01' 
		AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<='".date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))."'
		AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 1
		) AS TAB_MATERIEL 

WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

$Requete.=" AND 
(
SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
)";
$result=mysqli_query($bdd,$Requete);
$nbResulta=mysqli_num_rows($result);
$listePresta="";
$i=0;
if ($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		if($listePresta<>""){$listePresta.=",";}
		$listePresta.= "'".$row['Id_Prestation']."_".$row['Id_Pole']."'";
	}
}

$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$tableDepasse="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#ca2a40;width:120px;' colspan='4'>ECME DEPASSE</td>
	</tr>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Modèle</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>N° AAA</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:150px;'>Prestation</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Prochain Ctrl</td>
	</tr>";
	
$table15Jours="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#dd7957;width:120px;' colspan='4'>ECME A 15 JOURS</td>
	</tr>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Modèle</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>N° AAA</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:150px;'>Prestation</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Prochain Ctrl</td>
	</tr>";
	
$table1Mois="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eae650;width:120px;' colspan='4'>ECME A 1 MOIS</td>
	</tr>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Modèle</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>N° AAA</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:150px;'>Prestation</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Prochain Ctrl</td>
	</tr>";

$table3="</table>";

if($listePresta<>""){
	$req="SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
	FROM new_competences_personne_poste_prestation
	WHERE  CONCAT(Id_Prestation,'_',Id_Pole) IN (".$listePresta.") 
	AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteMagasinier.") 
	AND Id_Personne>0
	AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
	";
	$listeEmails="";
	$result2=mysqli_query($bdd,$req);
	$nbResulta2=mysqli_num_rows($result2);
	if($nbResulta2>0){
		while($row=mysqli_fetch_array($result2)){
			if($listeEmails<>""){$listeEmails.=";";}
			$listeEmails.= $row['EmailPro'];
		}
	}
	if($listeEmails<>""){
		//DEPASSE
		$Requete="
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS ProchainCtrl,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE
			FROM 
			(
			SELECT
				tools_materiel.Id AS ID,
				'Outils' AS TYPESELECT,
				NumAAA,
				NumFicheImmo,
				SN,
				DateDerniereVerification,
				PeriodiciteVerification,
				Designation,
				tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
				(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
				tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
				tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
				(SELECT IF(TAB_Mouvement.Id_Caisse=0,
							CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
							(
							SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
					)
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
				FROM
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
				WHERE tools_materiel.Suppr=0
				AND PeriodiciteVerification>0
				AND DateDerniereVerification>'0001-01-01' 
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<='".date('Y-m-d')."'
				AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 1
				) AS TAB_MATERIEL 

		WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

		$Requete.=" AND 
		(
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
		AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
		)
		ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ), DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)
		";

		$resultRapport=mysqli_query($bdd,$Requete);
		$nbRapport=mysqli_num_rows($resultRapport);
		$table2Depasse="";
		if($nbRapport>0){
			$couleur="EEEEEE";
			while($row=mysqli_fetch_array($resultRapport)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$LIBELLE_POLE="";
				if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
				
				$req="SELECT 
					tools_mouvement.DateReception,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
					(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=".$row['ID']."
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

				$ResultTransfertEC=mysqli_query($bdd,$req);
				$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
				
				$transfert="";
				if($NbEnregTransfertEC>0)
				{
					$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
					
					$LIBELLE_POLE_Transfert="";
					if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
				
					$transfert= " [Transfert E/C ".substr($RowTransfertEC['Prestation'],0,7)." ".$LIBELLE_POLE_Transfert."] ";
				}
				
				$table2Depasse.="<tr bgcolor='".$couleur."'>
							<td style='border:1px solid black;text-align:center;'>".$row['LIBELLE_MODELEMATERIEL']."</td>
							<td style='border:1px solid black;text-align:center;'>".$row['NumAAA']."</td>
							<td style='border:1px solid black;text-align:center;'>".substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE.$transfert."</td>
							<td style='border:1px solid black;text-align:center;'>".AfficheDateJJ_MM_AAAA($row['ProchainCtrl'])."</td>
						</tr>";
			}
		}
		
		//15 JOURS
		$Requete="
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS ProchainCtrl,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE
			FROM 
			(
			SELECT
				tools_materiel.Id AS ID,
				'Outils' AS TYPESELECT,
				NumAAA,
				NumFicheImmo,
				SN,
				DateDerniereVerification,
				PeriodiciteVerification,
				Designation,
				tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
				(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
				tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
				tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
				(SELECT IF(TAB_Mouvement.Id_Caisse=0,
							CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
							(
							SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
					)
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
				FROM
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
				WHERE tools_materiel.Suppr=0
				AND PeriodiciteVerification>0
				AND DateDerniereVerification>'0001-01-01' 
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)>'".date('Y-m-d')."'
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<'".date('Y-m-d',strtotime(date('Y-m-d')." +15 day"))."'
				AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 1
				) AS TAB_MATERIEL 

		WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

		$Requete.=" AND 
		(
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
		AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
		)
		ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ), DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)
		";

		$resultRapport=mysqli_query($bdd,$Requete);
		$nbRapport=mysqli_num_rows($resultRapport);
		$table215Jours="";
		if($nbRapport>0){
			$couleur="EEEEEE";
			while($row=mysqli_fetch_array($resultRapport)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$LIBELLE_POLE="";
				if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
				
				$req="SELECT 
					tools_mouvement.DateReception,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
					(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=".$row['ID']."
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

				$ResultTransfertEC=mysqli_query($bdd,$req);
				$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
				
				$transfert="";
				if($NbEnregTransfertEC>0)
				{
					$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
					
					$LIBELLE_POLE_Transfert="";
					if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
				
					$transfert= " [Transfert E/C ".substr($RowTransfertEC['Prestation'],0,7)." ".$LIBELLE_POLE_Transfert."] ";
				}
				
				$table215Jours.="<tr bgcolor='".$couleur."'>
							<td style='border:1px solid black;text-align:center;'>".$row['LIBELLE_MODELEMATERIEL']."</td>
							<td style='border:1px solid black;text-align:center;'>".$row['NumAAA']."</td>
							<td style='border:1px solid black;text-align:center;'>".substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE.$transfert."</td>
							<td style='border:1px solid black;text-align:center;'>".AfficheDateJJ_MM_AAAA($row['ProchainCtrl'])."</td>
						</tr>";
			}
		}
		
		//15 JOURS
		$Requete="
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS ProchainCtrl,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE
			FROM 
			(
			SELECT
				tools_materiel.Id AS ID,
				'Outils' AS TYPESELECT,
				NumAAA,
				NumFicheImmo,
				SN,
				DateDerniereVerification,
				PeriodiciteVerification,
				Designation,
				tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
				(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
				tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
				tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
				(SELECT IF(TAB_Mouvement.Id_Caisse=0,
							CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
							(
							SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
					)
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
				FROM
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
				WHERE tools_materiel.Suppr=0
				AND PeriodiciteVerification>0
				AND DateDerniereVerification>'0001-01-01' 
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)>='".date('Y-m-d',strtotime(date('Y-m-d')." +15 day"))."'
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<'".date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))."'
				AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 1
				) AS TAB_MATERIEL 

		WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

		$Requete.=" AND 
		(
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
		AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
		)
		ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ), DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)
		";

		$resultRapport=mysqli_query($bdd,$Requete);
		$nbRapport=mysqli_num_rows($resultRapport);
		$table21Mois="";
		if($nbRapport>0){
			$couleur="EEEEEE";
			while($row=mysqli_fetch_array($resultRapport)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$LIBELLE_POLE="";
				if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
				
				$req="SELECT 
					tools_mouvement.DateReception,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
					(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=".$row['ID']."
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

				$ResultTransfertEC=mysqli_query($bdd,$req);
				$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
				
				$transfert="";
				if($NbEnregTransfertEC>0)
				{
					$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
					
					$LIBELLE_POLE_Transfert="";
					if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
				
					$transfert= " [Transfert E/C ".substr($RowTransfertEC['Prestation'],0,7)." ".$LIBELLE_POLE_Transfert."] ";
				}
				
				$table21Mois.="<tr bgcolor='".$couleur."'>
							<td style='border:1px solid black;text-align:center;'>".$row['LIBELLE_MODELEMATERIEL']."</td>
							<td style='border:1px solid black;text-align:center;'>".$row['NumAAA']."</td>
							<td style='border:1px solid black;text-align:center;'>".substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE.$transfert."</td>
							<td style='border:1px solid black;text-align:center;'>".AfficheDateJJ_MM_AAAA($row['ProchainCtrl'])."</td>
						</tr>";
			}
		}
		
		if($table2Depasse<>"" || $table215Jours<>"" || $table21Mois<>""){
			$sujet="Relance ECME - ".date('d/m/Y');
			$message_html="	<html>
				<head><title>".$sujet."</title></head>
				<body>
					Bonjour,<br><br>
					Vous trouverez ci-dessous un tableau récapitulant les ECME de votre prestation en fin de validité d’étalonnage.<br> 
					N’hésitez pas à déposer vos ECME en avance de phase afin d’éviter les pénuries sur site.<br>
					Pour toutes incohérences entre cette liste et vos outils présents sur site, merci de nous en faire part.<br>
					Légende des couleurs :<br>
					<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#ca2a40;width:120px;'>XX/XX/XXXX</td>
							<td style='border:1px solid black;'>Date de validité dépassée à ce jour : ECME à ne plus utiliser et à ramener au plus vite</td>
						</tr>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#dd7957;width:120px;'>XX/XX/XXXX</td>
							<td style='border:1px solid black;'>Fin de validité dans moins de 15 jours</td>
						</tr>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#eae650;width:120px;'>XX/XX/XXXX</td>
							<td style='border:1px solid black;'>Fin de validité dans moins d’un mois</td>
						</tr>
					</table>
					<br><br>
					".$tableDepasse.$table2Depasse.$table3."<br><br>
					".$table15Jours.$table215Jours.$table3."<br><br>
					".$table1Mois.$table21Mois.$table3."<br><br>
					<br>
					".$listeEmails.";c.hutcheson@daher.com
					<br>
					Merci par avance pour vos actions.<br>
					Bonne journée,<br>
					L'Extranet Daher industriel services DIS.
				</body>
			</html>";
			$Emails="ssavy@aaa-aero.com";
			$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_Personne']." ";
			
			$resultPer=mysqli_query($bdd,$req);
			$nbResultaPers=mysqli_num_rows($resultPer);
			if ($nbResultaPers>0){
				$rowPers=mysqli_fetch_array($resultPer);
				if($rowPers['EmailPro']<>""){
					mail($rowPers['EmailPro'],$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com');
				}
			}
		}

	}
}

//EPI SS
$Requete="

	SELECT DISTINCT
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole
	FROM 
	(
	SELECT
		tools_materiel.Id AS ID,
		'Outils' AS TYPESELECT,
		NumAAA,
		NumFicheImmo,
		SN,
		DateDerniereVerification,
		PeriodiciteVerification,
		Designation,
		tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
		(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
		tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
		tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
		(SELECT IF(TAB_Mouvement.Id_Caisse=0,
					CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
					(
					SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
			)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
		) AS AffectationMouvement
		FROM
			tools_materiel
		LEFT JOIN
			tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
		LEFT JOIN
			tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
		WHERE tools_materiel.Suppr=0
		AND PeriodiciteVerification>0
		AND DateDerniereVerification>'0001-01-01' 
		AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<='".date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))."'
		AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 2
		) AS TAB_MATERIEL 

WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

$Requete.=" AND 
(
SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
)";
$result=mysqli_query($bdd,$Requete);
$nbResulta=mysqli_num_rows($result);
$listePresta="";
$i=0;
if ($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		if($listePresta<>""){$listePresta.=",";}
		$listePresta.= "'".$row['Id_Prestation']."_".$row['Id_Pole']."'";
	}
}

$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$tableDepasse="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#ca2a40;width:120px;' colspan='4'>EPI SS DEPASSE</td>
	</tr>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Modèle</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>N° AAA</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:150px;'>Prestation</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Prochain Ctrl</td>
	</tr>";
	
$table15Jours="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#dd7957;width:120px;' colspan='4'>EPI SS A 15 JOURS</td>
	</tr>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Modèle</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>N° AAA</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:150px;'>Prestation</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Prochain Ctrl</td>
	</tr>";
	
$table1Mois="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eae650;width:120px;' colspan='4'>EPI SS A 1 MOIS</td>
	</tr>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Modèle</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>N° AAA</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:150px;'>Prestation</td>
		<td style='border:1px solid black;text-align:center;background-color:#eeeeee;width:120px;'>Prochain Ctrl</td>
	</tr>";

$table3="</table>";

if($listePresta<>""){
	$req="SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
	FROM new_competences_personne_poste_prestation
	WHERE  CONCAT(Id_Prestation,'_',Id_Pole) IN (".$listePresta.") 
	AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteMagasinier.") 
	AND Id_Personne>0
	AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
	";
	$listeEmails="";
	$result2=mysqli_query($bdd,$req);
	$nbResulta2=mysqli_num_rows($result2);
	if($nbResulta2>0){
		while($row=mysqli_fetch_array($result2)){
			if($listeEmails<>""){$listeEmails.=";";}
			$listeEmails.= $row['EmailPro'];
		}
	}
	if($listeEmails<>""){
		//DEPASSE
		$Requete="
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS ProchainCtrl,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE
			FROM 
			(
			SELECT
				tools_materiel.Id AS ID,
				'Outils' AS TYPESELECT,
				NumAAA,
				NumFicheImmo,
				SN,
				DateDerniereVerification,
				PeriodiciteVerification,
				Designation,
				tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
				(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
				tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
				tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
				(SELECT IF(TAB_Mouvement.Id_Caisse=0,
							CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
							(
							SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
					)
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
				FROM
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
				WHERE tools_materiel.Suppr=0
				AND PeriodiciteVerification>0
				AND DateDerniereVerification>'0001-01-01' 
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<='".date('Y-m-d')."'
				AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 2
				) AS TAB_MATERIEL 

		WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

		$Requete.=" AND 
		(
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
		AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
		)
		ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ), DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)
		";

		$resultRapport=mysqli_query($bdd,$Requete);
		$nbRapport=mysqli_num_rows($resultRapport);
		$table2Depasse="";
		if($nbRapport>0){
			$couleur="EEEEEE";
			while($row=mysqli_fetch_array($resultRapport)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$LIBELLE_POLE="";
				if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
				
				$req="SELECT 
					tools_mouvement.DateReception,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
					(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=".$row['ID']."
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

				$ResultTransfertEC=mysqli_query($bdd,$req);
				$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
				
				$transfert="";
				if($NbEnregTransfertEC>0)
				{
					$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
					
					$LIBELLE_POLE_Transfert="";
					if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
				
					$transfert= " [Transfert E/C ".substr($RowTransfertEC['Prestation'],0,7)." ".$LIBELLE_POLE_Transfert."] ";
				}
				
				$table2Depasse.="<tr bgcolor='".$couleur."'>
							<td style='border:1px solid black;text-align:center;'>".$row['LIBELLE_MODELEMATERIEL']."</td>
							<td style='border:1px solid black;text-align:center;'>".$row['NumAAA']."</td>
							<td style='border:1px solid black;text-align:center;'>".substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE.$transfert."</td>
							<td style='border:1px solid black;text-align:center;'>".AfficheDateJJ_MM_AAAA($row['ProchainCtrl'])."</td>
						</tr>";
			}
		}
		
		//15 JOURS
		$Requete="
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS ProchainCtrl,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE
			FROM 
			(
			SELECT
				tools_materiel.Id AS ID,
				'Outils' AS TYPESELECT,
				NumAAA,
				NumFicheImmo,
				SN,
				DateDerniereVerification,
				PeriodiciteVerification,
				Designation,
				tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
				(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
				tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
				tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
				(SELECT IF(TAB_Mouvement.Id_Caisse=0,
							CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
							(
							SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
					)
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
				FROM
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
				WHERE tools_materiel.Suppr=0
				AND PeriodiciteVerification>0
				AND DateDerniereVerification>'0001-01-01' 
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)>'".date('Y-m-d')."'
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<'".date('Y-m-d',strtotime(date('Y-m-d')." +15 day"))."'
				AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 2
				) AS TAB_MATERIEL 

		WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

		$Requete.=" AND 
		(
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
		AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
		)
		ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ), DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)
		";

		$resultRapport=mysqli_query($bdd,$Requete);
		$nbRapport=mysqli_num_rows($resultRapport);
		$table215Jours="";
		if($nbRapport>0){
			$couleur="EEEEEE";
			while($row=mysqli_fetch_array($resultRapport)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$LIBELLE_POLE="";
				if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
				
				$req="SELECT 
					tools_mouvement.DateReception,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
					(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=".$row['ID']."
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

				$ResultTransfertEC=mysqli_query($bdd,$req);
				$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
				
				$transfert="";
				if($NbEnregTransfertEC>0)
				{
					$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
					
					$LIBELLE_POLE_Transfert="";
					if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
				
					$transfert= " [Transfert E/C ".substr($RowTransfertEC['Prestation'],0,7)." ".$LIBELLE_POLE_Transfert."] ";
				}
				
				$table215Jours.="<tr bgcolor='".$couleur."'>
							<td style='border:1px solid black;text-align:center;'>".$row['LIBELLE_MODELEMATERIEL']."</td>
							<td style='border:1px solid black;text-align:center;'>".$row['NumAAA']."</td>
							<td style='border:1px solid black;text-align:center;'>".substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE.$transfert."</td>
							<td style='border:1px solid black;text-align:center;'>".AfficheDateJJ_MM_AAAA($row['ProchainCtrl'])."</td>
						</tr>";
			}
		}
		
		//15 JOURS
		$Requete="
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS ProchainCtrl,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE
			FROM 
			(
			SELECT
				tools_materiel.Id AS ID,
				'Outils' AS TYPESELECT,
				NumAAA,
				NumFicheImmo,
				SN,
				DateDerniereVerification,
				PeriodiciteVerification,
				Designation,
				tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
				(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
				tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
				tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
				(SELECT IF(TAB_Mouvement.Id_Caisse=0,
							CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
							(
							SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
					)
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
				FROM
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
				WHERE tools_materiel.Suppr=0
				AND PeriodiciteVerification>0
				AND DateDerniereVerification>'0001-01-01' 
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)>='".date('Y-m-d',strtotime(date('Y-m-d')." +15 day"))."'
				AND DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)<'".date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))."'
				AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = 2
				) AS TAB_MATERIEL 

		WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = 1 ";

		$Requete.=" AND 
		(
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 65
		AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) <> 66
		)
		ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ), DATE_ADD(DateDerniereVerification, INTERVAL PeriodiciteVerification MONTH)
		";

		$resultRapport=mysqli_query($bdd,$Requete);
		$nbRapport=mysqli_num_rows($resultRapport);
		$table21Mois="";
		if($nbRapport>0){
			$couleur="EEEEEE";
			while($row=mysqli_fetch_array($resultRapport)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$LIBELLE_POLE="";
				if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
				
				$req="SELECT 
					tools_mouvement.DateReception,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
					(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=".$row['ID']."
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

				$ResultTransfertEC=mysqli_query($bdd,$req);
				$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
				
				$transfert="";
				if($NbEnregTransfertEC>0)
				{
					$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
					
					$LIBELLE_POLE_Transfert="";
					if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
				
					$transfert= " [Transfert E/C ".substr($RowTransfertEC['Prestation'],0,7)." ".$LIBELLE_POLE_Transfert."] ";
				}
				
				$table21Mois.="<tr bgcolor='".$couleur."'>
							<td style='border:1px solid black;text-align:center;'>".$row['LIBELLE_MODELEMATERIEL']."</td>
							<td style='border:1px solid black;text-align:center;'>".$row['NumAAA']."</td>
							<td style='border:1px solid black;text-align:center;'>".substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE.$transfert."</td>
							<td style='border:1px solid black;text-align:center;'>".AfficheDateJJ_MM_AAAA($row['ProchainCtrl'])."</td>
						</tr>";
			}
		}
		
		if($table2Depasse<>"" || $table215Jours<>"" || $table21Mois<>""){
			$sujet="Relance EPI SS - ".date('d/m/Y');
			$message_html="	<html>
				<head><title>".$sujet."</title></head>
				<body>
					Bonjour,<br><br>
					Vous trouverez ci-dessous un tableau récapitulant les EPI SS de votre prestation en fin de validité d’étalonnage.<br> 
					N’hésitez pas à déposer vos EPI SS en avance de phase afin d’éviter les pénuries sur site.<br>
					Pour toutes incohérences entre cette liste et vos outils présents sur site, merci de nous en faire part.<br>
					Légende des couleurs :<br>
					<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#ca2a40;width:120px;'>XX/XX/XXXX</td>
							<td style='border:1px solid black;'>Date de validité dépassée à ce jour : EPI SS à ne plus utiliser et à ramener au plus vite</td>
						</tr>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#dd7957;width:120px;'>XX/XX/XXXX</td>
							<td style='border:1px solid black;'>Fin de validité dans moins de 15 jours</td>
						</tr>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#eae650;width:120px;'>XX/XX/XXXX</td>
							<td style='border:1px solid black;'>Fin de validité dans moins d’un mois</td>
						</tr>
					</table>
					<br><br>
					".$tableDepasse.$table2Depasse.$table3."<br><br>
					".$table15Jours.$table215Jours.$table3."<br><br>
					".$table1Mois.$table21Mois.$table3."<br><br>
					<br>
					".$listeEmails.";c.hutcheson@daher.com
					<br>
					Merci par avance pour vos actions.<br>
					Bonne journée,<br>
					L'Extranet Daher industriel services DIS.
				</body>
			</html>";
			$Emails="ssavy@aaa-aero.com";
			
			$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_Personne']." ";
			
			$resultPer=mysqli_query($bdd,$req);
			$nbResultaPers=mysqli_num_rows($resultPer);
			if ($nbResultaPers>0){
				$rowPers=mysqli_fetch_array($resultPer);
				if($rowPers['EmailPro']<>""){
					mail($rowPers['EmailPro'],$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com');
				}
			}
			
		}

	}
}

echo "<script>window.close();</script>";

?>