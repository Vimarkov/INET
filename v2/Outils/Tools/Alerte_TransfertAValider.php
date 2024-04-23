<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions.php");

$Headers='From: "SAVY Sébastien"<ssavy@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$table1="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
			<tr>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>N° AAA</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:100px;'>S/N</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Type</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:100px;'>Famille</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Modèle</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>N°</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:150px;'>Donneur d'ordre</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Provenance</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:200px;'>Destination</td>
				<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:100px;'>Date du transfert</td>
			</tr>";

$table3="</table>";

$req=" SELECT Id,DateReception,Type,Id_Materiel__Id_Caisse,Id_Caisse,
	(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=Id_Caisse) AS Caisse,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,
	IF(Type=1,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
		IF(Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
			(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.EtatValidation<>-1 
			AND TAB_Mouvement.TypeMouvement=0 
			AND TAB_Mouvement.Suppr=0 
			AND TAB_Mouvement.Type=1 
			AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
			ORDER BY DateReception DESC, Id DESC 
			LIMIT 1
			)
		)
	) AS Id_Plateforme,
	IF(Type=1,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
		IF(Id_Caisse=0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
			(SELECT (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.EtatValidation<>-1 
			AND TAB_Mouvement.TypeMouvement=0 
			AND TAB_Mouvement.Suppr=0 
			AND TAB_Mouvement.Type=1 
			AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
			ORDER BY DateReception DESC, Id DESC 
			LIMIT 1
			)
		)
	) AS Plateforme,
	IF(Type=1,tools_mouvement.Id_Prestation,
		IF(Id_Caisse=0,tools_mouvement.Id_Prestation,
			(SELECT TAB_Mouvement.Id_Prestation
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.EtatValidation<>-1 
			AND TAB_Mouvement.TypeMouvement=0 
			AND TAB_Mouvement.Suppr=0 
			AND TAB_Mouvement.Type=1 
			AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
			ORDER BY DateReception DESC, Id DESC 
			LIMIT 1
			)
		)
	) AS Id_Prestation,
	IF(Type=1,(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
		IF(Id_Caisse=0,(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
			(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.EtatValidation<>-1 
			AND TAB_Mouvement.TypeMouvement=0 
			AND TAB_Mouvement.Suppr=0 
			AND TAB_Mouvement.Type=1 
			AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
			ORDER BY DateReception DESC, Id DESC 
			LIMIT 1
			)
		)
	) AS Prestation,
	IF(Type=1,tools_mouvement.Id_Pole,
		IF(Id_Caisse=0,tools_mouvement.Id_Pole,
			(SELECT TAB_Mouvement.Id_Pole
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.EtatValidation<>-1 
			AND TAB_Mouvement.TypeMouvement=0 
			AND TAB_Mouvement.Suppr=0 
			AND TAB_Mouvement.Type=1 
			AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
			ORDER BY DateReception DESC, Id DESC 
			LIMIT 1
			)
		)
	) AS Id_Pole ,
	IF(Type=1,(SELECT Libelle FROM new_competences_pole WHERE Id=tools_mouvement.Id_Pole),
		IF(Id_Caisse=0,(SELECT Libelle FROM new_competences_pole WHERE Id=tools_mouvement.Id_Pole),
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=TAB_Mouvement.Id_Pole)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.EtatValidation<>-1 
			AND TAB_Mouvement.TypeMouvement=0 
			AND TAB_Mouvement.Suppr=0 
			AND TAB_Mouvement.Type=1 
			AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
			ORDER BY DateReception DESC, Id DESC 
			LIMIT 1
			)
		)
	) AS Pole ,
	IF(Type=0,(SELECT NumAAA FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT NumAAA FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) AS NumAAA,
	IF(Type=0,(SELECT SN FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT SN FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) AS SN,
	IF(Type=0,(SELECT (SELECT Libelle FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT (SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Modele,
	IF(Type=0,(SELECT (SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Famille,
	IF(Type=0,(SELECT (SELECT (SELECT (SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),'Caisse') AS TypeMateriel,
	IF(Type=0,(SELECT (SELECT (SELECT 
	IF(Id_TypeMateriel=".$TypeTelephone.",tools_materiel.NumTelephone,
		IF(Id_TypeMateriel=".$TypeClef.",tools_materiel.NumClef,
			IF(Id_TypeMateriel=".$TypeMaqueDeControle.",tools_materiel.NumMC,
				IF(Id_TypeMateriel=".$TypeInformatique.",tools_materiel.NumPC,
					IF(Id_TypeMateriel=".$TypeVehicule.",tools_materiel.Immatriculation,
						IF(Id_TypeMateriel=".$TypeMacaron.",tools_materiel.ImmatriculationAssociee,'')
					)
				)
			)
		)
) 
FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT Num FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Num
FROM tools_mouvement
	WHERE EtatValidation=0
	AND tools_mouvement.TypeMouvement=0
	AND Suppr=0 
ORDER BY DateReception ASC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$listePresta="";
$tabTools = array();
$i=0;
if ($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		if($listePresta<>""){$listePresta.=",";}
		$listePresta.= "'".$row['Id_Prestation']."_".$row['Id_Pole']."'";
		
		$requete=" SELECT Id,DateReception,Id_Caisse,
			(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
			(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=Id_Caisse) AS Caisse,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
			Id_Prestation,Id_Pole
			FROM tools_mouvement
			WHERE EtatValidation=1
			AND TypeMouvement=0
			AND Type=".$row['Type']."
			AND Id_Materiel__Id_Caisse=".$row['Id_Materiel__Id_Caisse']."
			AND Suppr=0 
			AND Id<>".$row['Id']."
			ORDER BY DateReception DESC, Id DESC
			";

		$resultProvenance=mysqli_query($bdd,$requete);
		$nbResultaProvenance=mysqli_num_rows($resultProvenance);
		$Provenance="";
		$Id_PlateformeProvenance=0;
		if($nbResultaProvenance>0){
			$rowProvenance=mysqli_fetch_array($resultProvenance);
			if($row['Id_Plateforme']<>$rowProvenance['Id_Plateforme']){$Provenance=$rowProvenance['Plateforme']."<br>";}
			if($rowProvenance['Prestation']<>""){$Provenance.=$rowProvenance['Prestation'];}
			if($rowProvenance['Pole']<>""){$Provenance.=" - ".$rowProvenance['Pole'];}
			if($rowProvenance['Lieu']<>""){$Provenance.=" - ".$rowProvenance['Lieu'];}
			if($rowProvenance['Caisse']<>""){$Provenance.="<br>".$rowProvenance['Caisse'];}
			if($rowProvenance['Personne']<>""){$Provenance.="<br>".$rowProvenance['Personne'];}
			
			if($rowProvenance['Id_Caisse']>0){
				$Provenance="";
				$requete=" SELECT Id,DateReception,
					(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
					FROM tools_mouvement
					WHERE EtatValidation=1
					AND TypeMouvement=0
					AND Type=1
					AND Id_Materiel__Id_Caisse=".$rowProvenance['Id_Caisse']."
					AND Suppr=0 
					ORDER BY DateReception DESC, Id DESC
					";

				$resultCaisse=mysqli_query($bdd,$requete);
				$nbResultaCaisse=mysqli_num_rows($resultCaisse);
				if($nbResultaCaisse>0){
					$rowCaisse=mysqli_fetch_array($resultCaisse);
					if($row['Id_Plateforme']<>$rowCaisse['Id_Plateforme']){$Provenance=$rowCaisse['Plateforme']."<br>";}
					if($rowCaisse['Prestation']<>""){$Provenance.=$rowCaisse['Prestation'];}
					if($rowCaisse['Pole']<>""){$Provenance.=" - ".$rowCaisse['Pole'];}
					if($rowCaisse['Lieu']<>""){$Provenance.=" - ".$rowCaisse['Lieu'];}
					if($rowProvenance['Caisse']<>""){$Provenance.="<br>".$rowProvenance['Caisse'];}
					if($rowCaisse['Personne']<>""){$Provenance.="<br>".$rowCaisse['Personne'];}

				}
			}
		}
		
		$destination="";
		if($row['Id_Plateforme']<>$Id_PlateformeProvenance){$destination=$row['Plateforme']."<br>";}
		if($row['Prestation']<>""){$destination.=$row['Prestation'];}
		if($row['Pole']<>""){$destination.=" - ".$row['Pole'];}
		if($row['Lieu']<>""){$destination.=" - ".$row['Lieu'];}
		if($row['Caisse']<>""){$destination.="<br>".$row['Caisse'];}
		if($row['Personne']<>""){$destination.="<br>".$row['Personne'];}
		
		
		if($row['Id_Caisse']>0){
			$destination="";
			$requete=" SELECT Id,DateReception,
				(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
				FROM tools_mouvement
				WHERE EtatValidation=1
				AND TypeMouvement=0
				AND Type=1
				AND Id_Materiel__Id_Caisse=".$row['Id_Caisse']."
				AND Suppr=0 
				ORDER BY DateReception DESC, Id DESC
				";

			$resultCaisseD=mysqli_query($bdd,$requete);
			$nbResultaCaisseD=mysqli_num_rows($resultCaisseD);
			if($nbResultaCaisseD>0){
				$rowCaisseD=mysqli_fetch_array($resultCaisseD);
				if($rowCaisseD['Id_Plateforme']<>$Id_PlateformeProvenance){$destination=$rowCaisseD['Plateforme']."<br>";}
				if($rowCaisseD['Prestation']<>""){$destination.=$rowCaisseD['Prestation'];}
				if($rowCaisseD['Pole']<>""){$destination.=" - ".$rowCaisseD['Pole'];}
				if($rowCaisseD['Lieu']<>""){$destination.=" - ".$rowCaisseD['Lieu'];}
				if($row['Caisse']<>""){$destination.="<br>".$row['Caisse'];}
				if($rowCaisseD['Personne']<>""){$destination.="<br>".$rowCaisseD['Personne'];}

			}
		}

		$tabTools[$i] = array("NumAAA" => $row['NumAAA'],"SN" => $row['SN'],"TypeMateriel" => $row['TypeMateriel']
				,"Famille" => $row['Famille'],"Modele" => $row['Modele']
				,"Id_Prestation" => $row['Id_Prestation'],"Id_Pole" => $row['Id_Pole']
				,"Num" => $row['Num']
				,"Demandeur" => $row['Demandeur']
				,"Provenance" => $Provenance
				,"Destination" => $destination
				,"DateReception" => AfficheDateJJ_MM_AAAA($row['DateReception'])
				);
$i++;
	}
}
$Requete="

SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
FROM new_competences_personne_poste_prestation
WHERE Id_Personne>0 
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=1
AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.")
AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$listePresta.")
AND Id_Personne NOT IN (494,11506,15567) ";

$result2=mysqli_query($bdd,$Requete);
$nbResulta2=mysqli_num_rows($result2);
if($nbResulta2>0){
	while($row=mysqli_fetch_array($result2)){
		if($row['EmailPro']<>""){
			
			$table2="";
			$req="SELECT DISTINCT Id_Prestation, Id_Pole
			FROM new_competences_personne_poste_prestation
			WHERE Id_Personne=".$row['Id_Personne']."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.")
			AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$listePresta.")";

			$resultPresta=mysqli_query($bdd,$req);
			$nbResultaPresta=mysqli_num_rows($resultPresta);
			if($nbResultaPresta>0){
				while($rowPresta=mysqli_fetch_array($resultPresta)){
					foreach($tabTools as $tools){
						if($tools['Id_Prestation']==$rowPresta['Id_Prestation'] && $tools['Id_Pole']==$rowPresta['Id_Pole']){
							$table2.="
								<tr>
									<td style='border:1px solid black;text-align:center;'>".$tools['NumAAA']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['SN']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['TypeMateriel']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Famille']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Modele']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Num']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Demandeur']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Provenance']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['Destination']."</td>
									<td style='border:1px solid black;text-align:center;'>".$tools['DateReception']."</td>
								</tr>
							";
						}
					}
				}
			}
			
			echo $row['EmailPro']."<br><br>";
			$Emails=$row['EmailPro'];
			
			$sujet="Alerte transferts à valider - Suivi du matériel";
					
			$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							Du matériel a été affecté sur l'une de vos prestations
							<br>
							Merci de vous connecter à l'extranet (Suivi du matériel -> Transferts en cours) et valider/refuser les transferts.
							<br><br>
							".$table1.$table2.$table3."
							<br>
							Bonne journée,<br>
							Sébastien SAVY
						</body>
					</html>";
			echo $message_html;
			echo "<br><br>";
			
			//$Emails="pfauge@aaa-aero.com";
			mail($Emails,$sujet,$message_html,$Headers,'-f ssavy@aaa-aero.com');	
					
		}
	}
}

?>