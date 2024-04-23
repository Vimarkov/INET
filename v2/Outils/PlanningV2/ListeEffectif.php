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
	<script language="javascript">
	function OuvreFenetreExcel(Id)
			{window.open("ListeEffectifExport.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=90");}
	</script>
</head>

<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();
?>

<form class="test" action="ListeEffectif.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					if($LangueAffichage=="FR"){echo "Suivi des effectifs";}else{echo "Workforce monitoring";}
					?>
					</td>
					<td width="5%">
						&nbsp;&nbsp;&nbsp;
						<a href="javascript:OuvreFenetreExcel(<?php echo $_GET['Id'];?>)">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>
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
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre";}else{echo "Number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
				</tr>
	<?php
		$semaine=$_SESSION['FiltreRHSuiviEffectif_Semaine'];
		$annee=$_SESSION['FiltreRHSuiviEffectif_Annee'];
		$mois=$_SESSION['FiltreRHSuiviEffectif_Mois'];
		$selectType=$_SESSION['FiltreRHSuiviEffectif_TypeSelect'];
		
		$week = sprintf('%02d',$semaine);
		$start = strtotime($annee.'W'.$week);

		if($selectType=="Semaine"){
			$dateDebut=date('Y-m-d',strtotime('Monday',$start));
			$dateFin=date('Y-m-d',strtotime('Sunday',$start));
		}
		else{
			$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
			$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));

		}
		
		$req="
			SELECT *
			FROM
			(
				SELECT *
				FROM 
					(
						SELECT Id,Id_Personne,
						(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
						(SELECT Sexe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Sexe,
						(SELECT EstInterne FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterne,
						(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstInterim,
						(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						DateDebut,DateFin,Id_TypeContrat,Id_Metier,
						(SELECT Code FROM new_competences_metier WHERE Id=Id_Metier) AS CodeMetier,
						(SELECT Id_GroupeMetier 
						FROM new_competences_metier 
						WHERE new_competences_metier.Id=Id_Metier) AS Id_GroupeMetier,(@row_number:=@row_number + 1) AS rnk
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
		if($_SESSION['FiltreRHSuiviEffectif_Metier']>0){
			$req.="AND table_contrat2.Id_Metier=".$_SESSION['FiltreRHSuiviEffectif_Metier']." ";
		}
		if($_SESSION['FiltreRHSuiviEffectif_GroupeMetier']>0){
			$req.="AND table_contrat2.Id_GroupeMetier=".$_SESSION['FiltreRHSuiviEffectif_GroupeMetier']." ";
		}

		if($_SESSION['FiltreRHSuiviEffectif_Plateforme']>0){
			$req.="AND (SELECT COUNT(Id)
						FROM rh_personne_mouvement 
						WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation)=".$_SESSION['FiltreRHSuiviEffectif_Plateforme']."
						)>0 ";
			
			if($_SESSION['FiltreRHSuiviEffectif_Prestation']>0){
				$req.="AND (SELECT COUNT(Id)  
							FROM rh_personne_mouvement 
							WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
							AND Id_Prestation=".$_SESSION['FiltreRHSuiviEffectif_Prestation']."
							)>0 ";
					
				if($_SESSION['FiltreRHSuiviEffectif_Pole']>0){
				$req.="AND (SELECT COUNT(Id) 
							FROM rh_personne_mouvement 
							WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=table_contrat2.Id_Personne
							AND Id_Pole=".$_SESSION['FiltreRHSuiviEffectif_Pole']."
							)>0 ";
				}
			}
		}
		$req.="ORDER BY Personne ASC";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		
		if($selectType=="Semaine"){
			$dateDebut=date('Y-m-d',strtotime('Monday',$start));
			$dateFin=date('Y-m-d',strtotime('Sunday',$start));
		}
		else{
			$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
			$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));

		}
			
		if($nbenreg>0)
		{
			$couleur="#FFFFFF";
			while($rowContrat=mysqli_fetch_array($result))
			{
				$SommevaleurTempsTravail=0;
				$nbInterne=0;
				$nbExterne=0;
				$nbStagiaire=0;
				$EffectifInterneH=0;
				$EffectifInterneF=0;
				$EffectifExterne=0;
				$EffectifStagiaire=0;
				
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
						ORDER BY DateDebut DESC, Id DESC ";
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
						elseif($rowLeContrat['EstInterne'] && $rowLeContrat['EstSalarie']==0){
							$nbStagiaire+=$valeurTempsTravail;
						}
						
						$SommevaleurTempsTravail+=$valeurTempsTravail;
					}
				}

				if($selectType=="Semaine"){
					$SommevaleurTempsTravail=$SommevaleurTempsTravail/7;
					$nbInterne=$nbInterne/7;
					$nbExterne=$nbExterne/7;
					$nbStagiaire=$nbStagiaire/7;
					
				}
				else{
					$nbInterne=$nbInterne/30;
					$nbExterne=$nbExterne/30;
					$nbStagiaire=$nbStagiaire/30;
					$SommevaleurTempsTravail=$SommevaleurTempsTravail/30;
				}
				
				//Trouver si contrat interne ou externe
				if($rowContrat['Sexe']=="Femme"){$EffectifInterneF+=$nbInterne;}
				else{$EffectifInterneH+=$nbInterne;}
				$EffectifExterne+=$nbExterne;
				$EffectifStagiaire+=$nbStagiaire;
				
				$total=0;
				if($_GET['Id']==$rowContrat['Id_TypeContrat']){
					$total=$SommevaleurTempsTravail;
				}
				elseif($_GET['Id']==0){
					$total=$EffectifExterne+$EffectifInterneF+$EffectifInterneH+$EffectifStagiaire;
				}
				elseif($_GET['Id']==-1){
					$total=$EffectifInterneF+$EffectifInterneH;
				}
				elseif($_GET['Id']==-2){
					$total=$EffectifInterneH;
				}
				elseif($_GET['Id']==-3){
					$total=$EffectifInterneF;
				}
				elseif($_GET['Id']==-4){
					$total=$EffectifExterne;
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
						<td><?php echo stripslashes($rowContrat['MatriculeAAA']);?></td>
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