<?php
if($_POST){
	$Id_Plateforme="";
	if(isset($_POST['Id_Plateforme'])){
		if (is_array($_POST['Id_Plateforme'])) {
			foreach($_POST['Id_Plateforme'] as $value){
				if($Id_Plateforme<>''){$Id_Plateforme.=",";}
			  $Id_Plateforme.=$value;
			}
		} else {
			$value = $_POST['Id_Plateforme'];
			$Id_Plateforme = $value;
		}
	}
	
	if($_POST){$annee=$_POST['annee'];}
	if($annee==""){$annee=date("Y");}
	
	$_SESSION['FiltreEPEIndicateurs_Plateforme']=$Id_Plateforme;
	$_SESSION['FiltreEPEIndicateurs_Personne']=$_POST['personne'];
	$_SESSION['FiltreEPEIndicateurs_Annee']=$annee;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ 
				$dateFin=date($annee.'-12-31');
				$ladateDebut=date($annee.'-01-01');
				$dateDebut=date('Y-01-01',strtotime(date($annee.'-m-d')." -6 year"));

				$AnneeFin=$annee;
				$AnneeDebut=date('Y',strtotime(date($annee.'-m-d')." -6 year"));
					
				$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAncienneteCDI,
				(SELECT COUNT(Id) FROM epe_personne_attente WHERE Id_Personne=new_rh_etatcivil.Id AND Annee=".$annee." AND epe_personne_attente.TypeEntretien='EPP Bilan') AS EnAttente
				FROM new_rh_etatcivil
				WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
				OR 
					(SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$AnneeDebut.")>0
				) 
				AND YEAR(DateAncienneteCDI)<='".$AnneeDebut."'
				AND
				(
					SELECT COUNT(new_competences_personne_prestation.Id)
					FROM new_competences_personne_prestation
					LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
					WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
					AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$ladateDebut."')
					AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
					AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
				)>0 
				
				AND 
				(
					SELECT Id_Prestation
					FROM new_competences_personne_prestation
					LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
					WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
					AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$ladateDebut."')
					AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					ORDER BY Date_Fin DESC, Date_Debut DESC
					LIMIT 1
				) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
				";
				if($_SESSION['FiltreEPEIndicateurs_Personne']<>"0"){
					$req.="AND new_rh_etatcivil.Id=".$_SESSION['FiltreEPEIndicateurs_Personne']." ";
				}
				$req.="ORDER BY Personne ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
					
			?>
				<tr >
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($annee<=2021){if($_SESSION["Langue"]=="FR"){echo "Moins d'1 EPP en 6 ans";}else{echo "Less than 1 EPP in 6 years";}}else{if($_SESSION["Langue"]=="FR"){echo "Moins de 2 EPP en 6 ans";}else{echo "Less than 2 EPP in 6 years";}} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Pas d'action de formations";}else{echo "No training action";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Mis en attente";}else{echo "Put on hold";} ?></td>
					<td class="EnTeteTableauCompetences" width="3%">
					&nbsp;<a style="text-decoration:none;" href="javascript:Excel_Bilan();">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>&nbsp;
					</td>
				</tr>
			<?php
				if($nbenreg>0){
					$total=0;
					$couleur="#d6d9dc";
					
					while($row=mysqli_fetch_array($result)){
						
						$Id_Prestation=0;
						$Id_Pole=0;
						
						$PrestaPole=PrestationPoleCompetence_Personne(date('Y-m-d'),$row['Id']);
						$TableauPrestationPole=explode("_",$PrestaPole);
						if($PrestaPole<>0){
							$Id_Prestation=$TableauPrestationPole[0];
							$Id_Pole=$TableauPrestationPole[1];
						}

						
						$Plateforme="";
						$Presta="";
						$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
						$ResultPresta=mysqli_query($bdd,$req);
						$NbPrest=mysqli_num_rows($ResultPresta);
						if($NbPrest>0){
							$RowPresta=mysqli_fetch_array($ResultPresta);
							$Presta=$RowPresta['Prestation'];
							$Plateforme=$RowPresta['Plateforme'];
						}
						
						//Nb EPP en 6 ans 
						$req="SELECT Date_Reel 
						FROM new_competences_personne_rh_eia 
						WHERE Type='EPP' AND Id_Personne=".$row['Id']." 
						AND Date_Reel>'0001-01-01' 
						AND Date_Reel>='".$dateDebut."'
						AND Date_Reel<='".$dateFin."'
						UNION
						SELECT DateEntretien AS Date_Reel
						FROM epe_personne 
						WHERE Suppr=0 
						AND Type='EPP' 
						AND Id_Personne=".$row['Id']." 
						AND DateEntretien>='".$dateDebut."'
						AND DateEntretien<='".$dateFin."'
						ORDER BY Date_Reel DESC
						";

						$result2=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result2);

						$NbEntretien=$nbenreg;
						$Entretiens="";
						if($nbenreg>0){
							while($rowEntretiens=mysqli_fetch_array($result2)){
								if($Entretiens<>""){$Entretiens.=", ";}
								$Entretiens.=AfficheDateJJ_MM_AAAA($rowEntretiens['Date_Reel']);
							}
						}
						
						//Formations Obligatoires / Non obligatoires 
						$Obligatoire="";
						$NonObligatoire="";

						//Liste des formations OBLIGATOIRES
						$req="
							SELECT DateSession,Libelle,Organisme,Type
							FROM
							(
							SELECT
							form_besoin.Id AS Id_Besoin,
							0 AS Id_PersonneFormation,
							(
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							) AS DateSession,
							(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
								WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
								AND Suppr=0 LIMIT 1) AS Organisme,
							(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0) AS Libelle,
						'Professionnelle' AS Type
						FROM
							form_besoin,
							new_competences_prestation
						WHERE
							form_besoin.Id_Personne=".$row['Id']."
							AND form_besoin.Id_Prestation=new_competences_prestation.Id
							AND (SELECT Obligatoire FROM form_formation WHERE Id=form_besoin.Id_Formation)=1
							AND form_besoin.Suppr=0
							AND form_besoin.Valide=1
							AND form_besoin.Traite=4
							AND form_besoin.Id IN
							(
							SELECT
								Id_Besoin
							FROM
								form_session_personne
							WHERE
								form_session_personne.Id NOT IN 
									(
									SELECT
										Id_Session_Personne
									FROM
										form_session_personne_qualification
									WHERE
										Suppr=0	
									)
								AND Suppr=0
								AND form_session_personne.Validation_Inscription=1
								AND form_session_personne.Presence=1
							)
							AND (
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							)>='".$dateDebut."'
							AND (
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							)<='".$dateFin."'
							) AS TAB 
							UNION 
							SELECT 
							new_competences_personne_formation.Date AS DateSession,
							(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
							'' AS Organisme,
							new_competences_personne_formation.Type 
							FROM new_competences_personne_formation
							WHERE new_competences_personne_formation.Id_Personne=".$row['Id']." 
							AND new_competences_personne_formation.Date>='".$dateDebut."'
							AND new_competences_personne_formation.Date<='".$dateFin."'
							AND (SELECT Obligatoire FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id)=1
							ORDER BY DateSession DESC, Type ASC, Libelle ASC ";

						$result2=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result2);
						if($nbenreg>0){
							$Obligatoire=$nbenreg;
						}

						$Requete_Qualif="
							SELECT
								new_competences_qualification.Id,
								new_competences_qualification.Id_Categorie_Qualification,
								new_competences_qualification.Libelle AS Qualif,
								new_competences_qualification.Periodicite_Surveillance,
								new_competences_categorie_qualification.Libelle,
								new_competences_relation.Sans_Fin,
								new_competences_relation.Evaluation,
								new_competences_relation.Date_QCM,
								new_competences_relation.QCM_Surveillance,
								new_competences_relation.Date_Surveillance,
								new_competences_relation.Id AS Id_Relation,
								new_competences_relation.Visible,
								new_competences_relation.Date_Debut,
								new_competences_relation.Date_Fin,
								new_competences_relation.Resultat_QCM,
								new_competences_relation.Id_Besoin,
								new_competences_relation.Id_Session_Personne_Qualification
							FROM
								new_competences_relation,
								new_competences_qualification,
								new_competences_categorie_qualification
							WHERE
								new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
								AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
								AND new_competences_relation.Id_Personne=".$row['Id']."
								AND new_competences_relation.Type='Qualification'
								AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)>='".$dateDebut."'
								AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)<='".$dateFin."'
								AND new_competences_relation.Suppr=0
								AND new_competences_qualification.Obligatoire=1
								AND Evaluation NOT IN ('','B','Bi')
								AND new_competences_qualification.Id NOT IN (1643,1644)
							ORDER BY
								new_competences_categorie_qualification.Libelle ASC,
								new_competences_qualification.Libelle ASC,
								new_competences_relation.Date_Debut DESC,
								new_competences_relation.Date_QCM DESC";
						$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
						$nbenreg=mysqli_num_rows($ListeQualification);
						if($nbenreg>0){
							if($Obligatoire<>""){
								$Obligatoire+=$nbenreg;
							}
							else{
								$Obligatoire=$nbenreg;
							}
						}

						//Liste des formations NON OBLIGATOIRES
						$req="
							SELECT DateSession,Libelle,Organisme,Type
							FROM
							(
							SELECT
							form_besoin.Id AS Id_Besoin,
							0 AS Id_PersonneFormation,
							(
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							) AS DateSession,
							(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
								WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
								AND Suppr=0 LIMIT 1) AS Organisme,
							(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_besoin.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
									AND Id_Formation=form_besoin.Id_Formation
									AND Suppr=0 
									LIMIT 1)
								AND Suppr=0) AS Libelle,
						'Professionnelle' AS Type
						FROM
							form_besoin,
							new_competences_prestation
						WHERE
							form_besoin.Id_Personne=".$row['Id']."
							AND form_besoin.Id_Prestation=new_competences_prestation.Id
							AND (SELECT Obligatoire FROM form_formation WHERE Id=form_besoin.Id_Formation)=0
							AND form_besoin.Suppr=0
							AND form_besoin.Valide=1
							AND form_besoin.Traite=4
							AND form_besoin.Id IN
							(
							SELECT
								Id_Besoin
							FROM
								form_session_personne
							WHERE
								form_session_personne.Id NOT IN 
									(
									SELECT
										Id_Session_Personne
									FROM
										form_session_personne_qualification
									WHERE
										Suppr=0	
									)
								AND Suppr=0
								AND form_session_personne.Validation_Inscription=1
								AND form_session_personne.Presence=1
							)
							AND (
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							)>='".$dateDebut."'
							AND (
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
							)<='".$dateFin."'
							) AS TAB 
							UNION 
							SELECT 
							new_competences_personne_formation.Date AS DateSession,
							(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
							'' AS Organisme,
							new_competences_personne_formation.Type 
							FROM new_competences_personne_formation
							WHERE new_competences_personne_formation.Id_Personne=".$row['Id']." 
							AND new_competences_personne_formation.Date>='".$dateDebut."'
							AND new_competences_personne_formation.Date<='".$dateFin."'
							AND (SELECT Obligatoire FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id)=-1
							ORDER BY DateSession DESC, Type ASC, Libelle ASC ";
						$result2=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result2);
						if($nbenreg>0){
							$NonObligatoire=$nbenreg;
						}

						$Requete_Qualif="
							SELECT
								new_competences_qualification.Id,
								new_competences_qualification.Id_Categorie_Qualification,
								new_competences_qualification.Libelle AS Qualif,
								new_competences_qualification.Periodicite_Surveillance,
								new_competences_categorie_qualification.Libelle,
								new_competences_relation.Sans_Fin,
								new_competences_relation.Evaluation,
								new_competences_relation.Date_QCM,
								new_competences_relation.QCM_Surveillance,
								new_competences_relation.Date_Surveillance,
								new_competences_relation.Id AS Id_Relation,
								new_competences_relation.Visible,
								new_competences_relation.Date_Debut,
								new_competences_relation.Date_Fin,
								new_competences_relation.Resultat_QCM,
								new_competences_relation.Id_Besoin,
								new_competences_relation.Id_Session_Personne_Qualification
							FROM
								new_competences_relation,
								new_competences_qualification,
								new_competences_categorie_qualification
							WHERE
								new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
								AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
								AND new_competences_relation.Id_Personne=".$row['Id']."
								AND new_competences_relation.Type='Qualification'
								AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)>='".$dateDebut."'
								AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)<='".$dateFin."'
								AND new_competences_relation.Suppr=0
								AND new_competences_qualification.Obligatoire=-1
								AND Evaluation NOT IN ('','B','Bi')
								AND new_competences_qualification.Id NOT IN (1643,1644)
							ORDER BY
								new_competences_categorie_qualification.Libelle ASC,
								new_competences_qualification.Libelle ASC,
								new_competences_relation.Date_Debut DESC,
								new_competences_relation.Date_QCM DESC";
						$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
						$nbenreg=mysqli_num_rows($ListeQualification);
						if($nbenreg>0){
							if($NonObligatoire<>""){
								$NonObligatoire+=$nbenreg;
							}
							else{
								$NonObligatoire=$nbenreg;
							}
						}
						
						//Nb EPP en 6 ans 
						$req="SELECT DateEntretien AS Date_Reel
						FROM epe_personne 
						WHERE Suppr=0 
						AND Type='EPP Bilan' 
						AND Id_Personne=".$row['Id']." 
						AND DateEntretien>='".$dateDebut."'
						AND DateEntretien<'".$ladateDebut."'
						ORDER BY Date_Reel DESC
						";

						$resultEPPB=mysqli_query($bdd,$req);
						$nbEPPB=mysqli_num_rows($resultEPPB);
						
						if($nbEPPB==0 && (($annee<=2021 && ($NbEntretien==0 || ($Obligatoire=="" && $NonObligatoire==""))) || ($annee>2021 && ($NbEntretien<=1 || ($NonObligatoire==""))))){
			?>
				<tr bgcolor="<?php echo $couleur; ?>">
					<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
					<td><?php echo stripslashes($row['Personne']);?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAncienneteCDI']);?></td>
					<td><?php echo stripslashes($Plateforme);?></td>
					<td><?php echo $NbEntretien;
					if($NbEntretien>0){
						echo "<br>";
						echo $Entretiens;
					}
					?></td>
					<td>
						<?php
						if($annee<=2021){
							if($Obligatoire<>""){
								echo "<u>Obligatoires : </u>";
								echo $Obligatoire."<br>";
							}
							if($NonObligatoire<>""){
								echo "<u>Non obligatoires : </u>";
								echo $NonObligatoire;
							}
						}
						else{
							if($NonObligatoire<>""){
								echo "<u>Non obligatoires : </u>";
								echo $NonObligatoire;
							}
						}
						?>
					</td>
					<td colspan="2">
					<?php if($row['EnAttente']>0){echo "X";} ?>
					</td>
				</tr>
			<?php 
						if($couleur=="#d6d9dc"){$couleur="#ffffff";}
						else{$couleur="#d6d9dc";}
						}
					} 
				}
			}
			?>
		</table>
	</td>
	<td align="right" valign="top" width="25%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreEPEIndicateurs_Annee']; ?>" size="5"/></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "People";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%">
					<select id="personne" name="personne" style="width:100px;">
						<option value='0'></option>
						<?php
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM epe_personne_datebutoir
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
									WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
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
											AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
											AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
											AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
										)>0
							 ";
							$requetePersonne.="ORDER BY Personne ASC";
							$resultPersonne=mysqli_query($bdd,$requetePersonne);
							$NbPersonne=mysqli_num_rows($resultPersonne);
							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								echo "<option value='".$rowPersonne['Id']."'";
								if($_POST){
								if ($_POST['personne'] == $rowPersonne['Id']){echo " selected ";}
								}
								echo ">".$rowPersonne['Personne']."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					
					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$checked="";
						if($_POST){
							$checkboxes = isset($_POST['Id_Plateforme']) ? $_POST['Id_Plateforme'] : array();
							foreach($checkboxes as $value) {
								if($LigPlateforme['Id']==$value){$checked="checked";}
							}
						}
						else{
							$checked="checked";	
						}
						echo "<tr><td>";
						echo "<input type='checkbox' class='checkPlateforme' name='Id_Plateforme[]' Id='Id_Plateforme[]' value='".$LigPlateforme['Id']."' ".$checked." >".$LigPlateforme['Libelle'];
						echo "</td></tr>";
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
</tr>
<tr><td height="4"></td>
</table>	