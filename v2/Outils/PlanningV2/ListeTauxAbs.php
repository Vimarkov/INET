<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>

<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();
?>

<form class="test" action="ListeTauxAbs.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					if($LangueAffichage=="FR"){echo "Suivi du taux d'absentéisme";}else{echo "Monitoring of absenteeism rate";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heures";}else{echo "Number of hours";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
				</tr>
	<?php
		$annee=$_SESSION['FiltreRHTauxAbsenteisme_Annee'];
		$mois=$_SESSION['FiltreRHTauxAbsenteisme_Mois'];
		$plateforme=$_SESSION['FiltreRHTauxAbsenteisme_Plateforme'];
		$domaine=$_SESSION['FiltreRHTauxAbsenteisme_Domaine'];
		$prestation= $_SESSION['FiltreRHTauxAbsenteisme_Prestation'];
		$pole=$_SESSION['FiltreRHTauxAbsenteisme_Pole'];
		
		$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
		$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));
		
		$req="
			SELECT *
			FROM
			(
				SELECT *
				FROM 
					(
						SELECT Id,Id_Personne,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
						(SELECT Sexe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Sexe,
						DateDebut,DateFin,Id_TypeContrat,Id_Metier,
						(SELECT Id_GroupeMetier 
						FROM new_competences_metier 
						WHERE new_competences_metier.Id=Id_Metier) AS Id_GroupeMetier,
						(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT EstInterne FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterne,
						(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterim,
						(SELECT Code FROM new_competences_metier WHERE Id=Id_Metier) AS CodeMetier,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".$dateFin."'
						AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant') 
						ORDER BY Id_Personne, DateDebut DESC, Id DESC
					) AS table_contrat 
					GROUP BY Id_Personne
			) AS table_contrat2
			WHERE Id_Personne<>0
				";
		if($_SESSION['FiltreRHTauxAbsenteisme_Plateforme']>0){
			$req.="AND (SELECT COUNT(Id)
						FROM rh_personne_mouvement 
						WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation)=".$_SESSION['FiltreRHTauxAbsenteisme_Plateforme']."
						)>0 ";
			
			if($_SESSION['FiltreRHTauxAbsenteisme_Domaine']>0){
				$req.="AND (SELECT COUNT(Id)  
							FROM rh_personne_mouvement 
							WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
							AND (SELECT Id_Domaine FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation)=".$_SESSION['FiltreRHTauxAbsenteisme_Domaine']."
							)>0 ";
			}
			
			if($_SESSION['FiltreRHTauxAbsenteisme_Prestation']>0){
				$req.="AND (SELECT COUNT(Id)  
							FROM rh_personne_mouvement 
							WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
							AND Id_Prestation=".$_SESSION['FiltreRHTauxAbsenteisme_Prestation']."
							)>0 ";
					
				if($_SESSION['FiltreRHTauxAbsenteisme_Pole']>0){
				$req.="AND (SELECT COUNT(Id) 
							FROM rh_personne_mouvement 
							WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
							AND Id_Pole=".$_SESSION['FiltreRHTauxAbsenteisme_Pole']."
							)>0 ";
				}
			}
		}
		$req.="ORDER BY Personne ASC";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		
		$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
		$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));
			
		$EffectifInterne=0;
		$EffectifExterne=0;
		
		if($nbenreg>0)
		{
			$couleur="#FFFFFF";
			while($rowContrat=mysqli_fetch_array($result))
			{
				$nbInterne=0;
				$nbExterne=0;
				$NbJoursABS=0;
				$NbJoursMAL=0;
				$NbJoursAT=0;
				$NbJoursMAT=0;
				
				//Pour chaque jour trouver le contrat de la personne
				for($laDate=$dateDebut;$laDate<=$dateFin;$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
					$req="SELECT Id,Id_Personne,
						(SELECT Sexe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Sexe,
						DateDebut,DateFin,Id_TypeContrat,Id_Metier,
						(SELECT EstInterne FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterne,
						(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterim,
						(SELECT EstUnTempsPlein FROM rh_tempstravail WHERE rh_tempstravail.Id=rh_personne_contrat.Id_TempsTravail) AS EstUnTempsPlein,
						(SELECT NbHeureSemaine FROM rh_tempstravail WHERE rh_tempstravail.Id=rh_personne_contrat.Id_TempsTravail) AS NbHeureSemaine
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".$laDate."'
						AND (DateFin>='".$laDate."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant') 
						AND Id_Personne=".$rowContrat['Id_Personne']."
						ORDER BY Id DESC ";
					$resultLeContrat=mysqli_query($bdd,$req);
					$nbLeContrat=mysqli_num_rows($resultLeContrat);
					if($nbLeContrat>0){
						$rowLeContrat=mysqli_fetch_array($resultLeContrat);
						
						$valeurTempsTravail=1;
						if($rowLeContrat['EstUnTempsPlein']==0){
							$valeurTempsTravail=$rowLeContrat['NbHeureSemaine']/35;
						}
						
						if($rowLeContrat['EstInterne'] && $rowLeContrat['EstSalarie']){
							$nbInterne+=$valeurTempsTravail;
						}
						elseif($rowLeContrat['EstInterne']==0){
							$nbExterne+=$valeurTempsTravail;
						}
						
					}
					//Compter le nombre d'heures d'absence 
					
					$total=0;
					
					if($rowLeContrat['EstInterne'] && $rowLeContrat['EstSalarie']){
						$req="SELECT Id_Personne,DateDebut,DateFin,HeureDepart,HeureArrivee,NbJour,
							IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial) AS Id_TypeAbsence
							FROM rh_absence
							LEFT JOIN rh_personne_demandeabsence
							ON rh_personne_demandeabsence.Id=rh_absence.Id_Personne_DA
							WHERE rh_personne_demandeabsence.Suppr=0
							AND rh_absence.Suppr=0
							AND rh_personne_demandeabsence.EtatN1<>-1
							AND rh_personne_demandeabsence.EtatN2<>-1
							AND rh_absence.DateDebut<='".$laDate."'
							AND rh_absence.DateFin>='".$laDate."'
							AND IF(rh_absence.Id_TypeAbsenceDefinitif>0,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.Id_TypeAbsenceInitial) IN (1,23,28)
							AND rh_personne_demandeabsence.Id_Personne=".$rowContrat['Id_Personne']." ";
						$resultABS=mysqli_query($bdd,$req);
						$nbABS=mysqli_num_rows($resultABS);
						if($nbABS>0){
							while($rowABS=mysqli_fetch_array($resultABS)){
								if(TravailCeJourDeSemaine($laDate,$rowContrat['Id_Personne'])){
									$NbJoursABS++;
									if($rowABS['Id_TypeAbsence']==1){
										$NbJoursMAL++;
									}
									elseif($rowABS['Id_TypeAbsence']==23){
										$NbJoursAT++;
									}
									elseif($rowABS['Id_TypeAbsence']==28){
										$NbJoursMAT++;
									}
								}
							}
							
						}
					}
				}
				$total=0;
				if($_GET['Id']==0){
					$total=$NbJoursABS*7;
				}
				elseif($_GET['Id']==1){
					$total=$NbJoursMAL*7;
				}
				elseif($_GET['Id']==2){
					$total=$NbJoursAT*7;
				}
				elseif($_GET['Id']==3){
					$total=$NbJoursMAT*7;
				}
				
				if($total>0){
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
				
					$Id_Prestation=0;
					$Id_Pole=0;
					$Prestation="";
					$Pole="";
					$PrestaPole=PrestationPole_Personne($dateDebut,$rowContrat['Id_Personne']);
					if($PrestaPole<>0){
						$tab=explode("_",$PrestaPole);
						$Id_Prestation=$tab[0];
						$Id_Pole=$tab[1];
					}
					$req="SELECT LEFT(Libelle,7) AS Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation;
					$result2=mysqli_query($bdd,$req);
					$nb2=mysqli_num_rows($result2);
					if($nb2>0){
						$row2=mysqli_fetch_array($result2);
						$Prestation=$row2['Libelle'];
					}
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
					$result2=mysqli_query($bdd,$req);
					$nb2=mysqli_num_rows($result2);
					if($nb2>0){
						$row2=mysqli_fetch_array($result2);
						$Pole=$row2['Libelle'];
					}
					$typeConrat=$rowContrat['TypeContrat'];
					if($rowContrat['EstInterim']==1){
						$typeConrat=$rowContrat['AgenceInterim'];
					}
					?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td><?php echo stripslashes($rowContrat['Personne']);?></td>
							<td><?php echo round($total,1);?></td>
							<td><?php echo stripslashes($Prestation);?></td>
							<td><?php echo stripslashes($Pole);?></td>
							<td><?php echo stripslashes($typeConrat);?></td>
							<td><?php echo stripslashes($rowContrat['CodeMetier']);?></td>
						</tr>
					<?php
				}
			}
		}
	?>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>