<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Id)
	{var w=window.open("ListeTauxAbs.php?Id="+Id,"PageTauxAbs","status=no,menubar=no,width=1000,height=550");
	w.focus();
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$TDB=0;
if($_GET){
	if(isset($_GET['TDB'])){
		$TDB=$_GET['TDB'];
	}
}
else{
	$TDB=$_POST['TDB'];
}
$OngletTDB="";
if($_GET){
	if(isset($_GET['OngletTDB'])){
		$OngletTDB=$_GET['OngletTDB'];
	}
}
else{
	$OngletTDB=$_POST['OngletTDB'];
}

?>

<form class="test" action="TauxAbsenteisme.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f561a4;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Taux d'absentéisme";}else{echo "Absenteeism";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
				<select class="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreRHTauxAbsenteisme_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHTauxAbsenteisme_Plateforme']=$PlateformeSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect<>"")
							{if($PlateformeSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Domaine :";}else{echo "Domain :";} ?>
				<select class="domaine" style="width:100px;" name="domaine" onchange="submit();">
				<?php
				$requeteDomaine="SELECT Id, Libelle
					FROM rh_domaine
					WHERE Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
					AND Id_Plateforme=".$PlateformeSelect."
					ORDER BY Libelle ASC";
					
				$resultDomaine=mysqli_query($bdd,$requeteDomaine);
				$nbDomaine=mysqli_num_rows($resultDomaine);
				
				$DomaineSelect = 0;
				$Selected = "";
				
				$DomaineSelect=$_SESSION['FiltreRHTauxAbsenteisme_Domaine'];
				if($_POST){$DomaineSelect=$_POST['domaine'];}
				$_SESSION['FiltreRHTauxAbsenteisme_Domaine']=$DomaineSelect;	
				
				$Trouve=0;
				echo "<option name='0' value='0' Selected></option>";
				if ($nbDomaine > 0)
				{
					while($row=mysqli_fetch_array($resultDomaine))
					{
						$selected="";
						if($DomaineSelect<>"")
							{if($DomaineSelect==$row['Id']){$selected="selected";$Trouve=1;}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 if($Trouve==0){
					 $DomaineSelect=0;
					 $_SESSION['FiltreRHTauxAbsenteisme_Domaine']=$DomaineSelect;
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				
				$reqSuite="";
				if($DomaineSelect>0){
					$reqSuite="AND Id_Domaine=".$DomaineSelect." ";
				}
				$requeteSite="SELECT Id, Libelle
					FROM new_competences_prestation
					WHERE Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
					AND Active=0
					AND Id_Plateforme=".$PlateformeSelect."
					".$reqSuite."
					ORDER BY Libelle ASC";
					
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHTauxAbsenteisme_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHTauxAbsenteisme_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				$Trouve=0;
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";$Trouve=1;}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 if($Trouve==0){
					 $PrestationSelect=0;
					 $_SESSION['FiltreRHTauxAbsenteisme_Prestation']=$PrestationSelect;
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php
				$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
						FROM new_competences_pole
						LEFT JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
						AND Actif=0
						AND new_competences_pole.Id_Prestation=".$PrestationSelect."
						ORDER BY new_competences_pole.Libelle ASC";
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHTauxAbsenteisme_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHTauxAbsenteisme_Pole']=$PoleSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				$Trouve=0;
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";$Trouve=1;}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 if($Trouve==0){
					 $PoleSelect=0;
					 $_SESSION['FiltreRHTauxAbsenteisme_Pole']=$PoleSelect;
				 }
				 ?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHTauxAbsenteisme_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHTauxAbsenteisme_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHTauxAbsenteisme_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHTauxAbsenteisme_Annee']=$annee;
					?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
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
						(SELECT Sexe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Sexe,
						DateDebut,DateFin,Id_TypeContrat,Id_Metier,
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
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		

		$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
		$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));
			
		$EffectifInterne=0;
		$EffectifExterne=0;
		$NbJoursABS=0;
		$NbJoursMAL=0;
		$NbJoursAT=0;
		$NbJoursMAT=0;
		if($nbenreg>0)
		{
			while($rowContrat=mysqli_fetch_array($result))
			{
				$nbInterne=0;
				$nbExterne=0;
		
				
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
				$nbInterne=$nbInterne/30;
				$nbExterne=$nbExterne/30;
				
				if($nbInterne>0){$nbInterne=1;}
				if($nbExterne>0){$nbExterne=1;}
					
				//Trouver si contrat interne ou externe
				$EffectifInterne+=$nbInterne;
				$EffectifExterne+=$nbExterne;
				
			}
		}


	$heuresTravaillee=round((round($EffectifInterne))*151.67);
	$heuresAbs=$NbJoursABS*7;
	$heuresMal=$NbJoursMAL*7;
	$heuresAT=$NbJoursAT*7;
	$heuresMat=$NbJoursMAT*7;
	$Taux=0;
	if($heuresTravaillee>0){
		$Taux=round(($heuresAbs/$heuresTravaillee)*100,2);
	}
	?>
	<tr>
		<td>
			<table style="width:30%;" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle" height="50px" style='border-bottom:1px dotted black;' width="80%">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "HEURES TRAVAILLEES EN THEORIE<br> (Effectif moyen mensuel x 151,67)";}else{echo "HOURS WORKING IN THEORY <br> (Average Monthly Enrollment x 151.67)";} ?>
					</td>
					<td style='border-bottom:1px dotted black;border-left:1px dotted black;' width="20%" align="center">
					<?php echo (round($EffectifInterne))." x 151.67 = ".$heuresTravaillee; ?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" height="50px" style='border-bottom:1px dotted black;'>
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "NOMBRES D'HEURES D'ABSENCES DE L'EFFECTIF MOYEN<br>(Maladie+AT+Maternité)";}else{echo "NUMBER OF HOURS OF ABSENCES OF THE AVERAGE WORKFORCE <br> (Sickness + Work accident + Maternity)";} ?>
					</td>
					<td style='border-bottom:1px dotted black;border-left:1px dotted black;' align="center">
					<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(0)'>";?>
					<?php echo $heuresAbs; ?>
					<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" height="20px" style='border-bottom:1px dotted black;' align="right">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Maladie";}else{echo "Sickness";} ?>&nbsp;&nbsp;
					</td>
					<td style='border-bottom:1px dotted black;border-left:1px dotted black;' align="center">
					<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(1)'>";?>
					<?php echo $heuresMal; ?>
					<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" height="20px" style='border-bottom:1px dotted black;' align="right">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "AT";}else{echo "Work accident";} ?>&nbsp;&nbsp;
					</td>
					<td style='border-bottom:1px dotted black;border-left:1px dotted black;' align="center">
					<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(2)'>";?>
					<?php echo $heuresAT; ?>
					<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" height="20px" style='border-bottom:1px dotted black;' align="right">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Maternité";}else{echo "Maternity";} ?>&nbsp;&nbsp;
					</td>
					<td style='border-bottom:1px dotted black;border-left:1px dotted black;' align="center">
					<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(3)'>";?>
					<?php echo $heuresMat; ?>
					<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" height="50px" style='border-bottom:1px dotted black;'>
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "TAUX D'ABSENTEISME";}else{echo "ABSENTEEISM";} ?>
					</td>
					<td style='border-left:1px dotted black;' align="center" bgcolor='#fff817'>
					<?php echo $Taux." %"; ?>
					</td>
				</tr>
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