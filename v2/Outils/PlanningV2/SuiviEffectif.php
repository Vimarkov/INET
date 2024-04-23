<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Id)
	{var w=window.open("ListeEffectif.php?Id="+Id,"PageEffectif","status=no,menubar=no,width=1000,height=550");
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

<form class="test" action="SuiviEffectif.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
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
						
					if($LangueAffichage=="FR"){echo "Suivi des effectifs";}else{echo "Workforce monitoring";}
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
				
				$PlateformeSelect=$_SESSION['FiltreRHSuiviEffectif_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHSuiviEffectif_Plateforme']=$PlateformeSelect;	
				
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
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
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
					ORDER BY Libelle ASC";
					
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHSuiviEffectif_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHSuiviEffectif_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
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
				
				$PoleSelect=$_SESSION['FiltreRHSuiviEffectif_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHSuiviEffectif_Pole']=$PoleSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 
				 $selectType=$_SESSION['FiltreRHSuiviEffectif_TypeSelect'];
				 if($_POST){$selectType=$_POST['selectType'];}
				$_SESSION['FiltreRHSuiviEffectif_TypeSelect']=$selectType;
				 ?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<input id="selectType" name="selectType" type="radio" onchange="submit()" value="Semaine" <?php if($selectType=="Semaine"){echo "checked";} ?>/><?php if($_SESSION["Langue"]=="FR"){echo "Semaine :";}else{echo "Week :";} ?>
				<select id="semaine" name="semaine" onchange="submit();">
					<?php
						$semaine=$_SESSION['FiltreRHSuiviEffectif_Semaine'];
						if($_POST){$semaine=$_POST['semaine'];}
						$_SESSION['FiltreRHSuiviEffectif_Semaine']=$semaine;
						
						for($i=1;$i<=52;$i++){
							echo "<option value='".$i."'";
							if($semaine== $i){echo " selected ";}
							echo ">".$i."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHSuiviEffectif_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHSuiviEffectif_Annee']=$annee;
					?>
				</select><br>
				&nbsp;<input id="selectType" name="selectType" type="radio" onchange="submit()" value="Mois" <?php if($selectType=="Mois"){echo "checked";} ?>/><?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHSuiviEffectif_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHSuiviEffectif_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
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
		<tr>
			<td class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Groupe métier :";}else{echo "Job Group :";} ?>
				<select class="groupeMetier" style="width:100px;" name="groupeMetier" onchange="submit();">
				<?php
				if($_SESSION["Langue"]=="FR"){
					$requeteGroupeMetier="SELECT Id, Libelle
						FROM rh_groupemetier
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				}
				else{
					$requeteGroupeMetier="SELECT Id, LibelleEN AS Libelle
						FROM rh_groupemetier
						WHERE Suppr=0
						ORDER BY LibelleEN ASC";
				}
				$resultGroupeMetier=mysqli_query($bdd,$requeteGroupeMetier);
				$nbGroupeMetier=mysqli_num_rows($resultGroupeMetier);
				
				$GroupeMetierSelect = 0;
				$Selected = "";
				
				$GroupeMetierSelect=$_SESSION['FiltreRHSuiviEffectif_GroupeMetier'];
				if($_POST){$GroupeMetierSelect=$_POST['groupeMetier'];}
				$_SESSION['FiltreRHSuiviEffectif_GroupeMetier']=$GroupeMetierSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbGroupeMetier > 0)
				{
					while($row=mysqli_fetch_array($resultGroupeMetier))
					{
						$selected="";
						if($GroupeMetierSelect<>"")
							{if($GroupeMetierSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?>
				<select class="metier" style="width:100px;" name="metier" onchange="submit();">
				<?php
				$reqSuite="";
				if($GroupeMetierSelect>0){
					$reqSuite=" AND Id_GroupeMetier=".$GroupeMetierSelect." ";
				}
				if($_SESSION["Langue"]=="FR"){
					$requeteMetier="SELECT Id, Libelle
						FROM new_competences_metier
						WHERE Suppr=0
						".$reqSuite."
						ORDER BY Libelle ASC";
				}
				else{
					$requeteMetier="SELECT Id, LibelleEN AS Libelle
						FROM new_competences_metier
						WHERE Suppr=0
						".$reqSuite."
						ORDER BY LibelleEN ASC";
				}
				$resultMetier=mysqli_query($bdd,$requeteMetier);
				$nbMetier=mysqli_num_rows($resultMetier);
				
				$MetierSelect=$_SESSION['FiltreRHSuiviEffectif_Metier'];
				if($_POST){$MetierSelect=$_POST['metier'];}
				$_SESSION['FiltreRHSuiviEffectif_Metier']=$MetierSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbMetier > 0)
				{
					while($row=mysqli_fetch_array($resultMetier))
					{
						$selected="";
						if($MetierSelect<>"")
						{if($MetierSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
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
		$requeteTypeContrat="SELECT Id
			FROM rh_typecontrat
			WHERE Suppr=0
			ORDER BY Libelle ASC";
		$resultTypeContrat=mysqli_query($bdd,$requeteTypeContrat);
		$nbTypeContrat=mysqli_num_rows($resultTypeContrat);
		$tabTypeContrat=array();
		$tabTypeContratValeur=array();
		$tabTypeContratValeur2=array();
		if ($nbTypeContrat > 0)
		{
			while($row=mysqli_fetch_array($resultTypeContrat))
			{
				$tabTypeContrat[]=$row['Id'];
				$tabTypeContratValeur[]=0;
				$tabTypeContratValeur2[]=0;
			}
		}
						
		$EffectifInterneH=0;
		$EffectifInterneF=0;
		$EffectifExterne=0;
		$EffectifStagiaire=0;
		if($nbenreg>0)
		{
			while($rowContrat=mysqli_fetch_array($result))
			{
				$nbInterne=0;
				$nbExterne=0;
				$nbStagiaire=0;
				
				$i=0;
				foreach($tabTypeContrat as $typeContrat){
					$tabTypeContratValeur2[$i]=0;
					$i++;
				}
				
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
						
						$i=0;
						foreach($tabTypeContrat as $typeContrat){
							if($typeContrat==$rowLeContrat['Id_TypeContrat']){
								$tabTypeContratValeur2[$i]+=$valeurTempsTravail;
							}
							$i++;
						}
					}
				}
				
				if($selectType=="Semaine"){
					$nbInterne=$nbInterne/7;
					$nbExterne=$nbExterne/7;
					$nbStagiaire=$nbStagiaire/7;
					
					if($nbInterne>0){$nbInterne=1;}
					if($nbExterne>0){$nbExterne=1;}
					if($nbStagiaire>0){$nbStagiaire=1;}
					
					$i=0;
					foreach($tabTypeContrat as $typeContrat){
						$tabTypeContratValeur2[$i]=$tabTypeContratValeur2[$i]/7;
						if($tabTypeContratValeur2[$i]>0){$tabTypeContratValeur2[$i]=1;}
						$i++;
					}
				}
				else{
					$nbInterne=$nbInterne/30;
					$nbExterne=$nbExterne/30;
					$nbStagiaire=$nbStagiaire/30;
					
					if($nbInterne>0){$nbInterne=1;}
					if($nbExterne>0){$nbExterne=1;}
					if($nbStagiaire>0){$nbStagiaire=1;}
					
					$i=0;
					foreach($tabTypeContrat as $typeContrat){
						$tabTypeContratValeur2[$i]=$tabTypeContratValeur2[$i]/30;
						if($tabTypeContratValeur2[$i]>0){$tabTypeContratValeur2[$i]=1;}
						$i++;
					}

				}
				//Trouver si contrat interne ou externe
				if($rowContrat['Sexe']=="Femme"){$EffectifInterneF+=$nbInterne;}
				else{$EffectifInterneH+=$nbInterne;}
				$EffectifExterne+=$nbExterne;
				$EffectifStagiaire+=$nbStagiaire;
				$i=0;
				foreach($tabTypeContrat as $typeContrat){
					$tabTypeContratValeur[$i]+=$tabTypeContratValeur2[$i];
					$i++;
				}
			}
		}



	?>
	<tr>
		<td>
			<table style="width:30%;" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle" style='border-bottom:1px dotted black;' width="80%">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "EFFECTIF TOTAL";}else{echo "TOTAL WORKFORCE";} ?>
					</td>
					<td style='border-bottom:1px dotted black;border-left:1px dotted black;' width="20%" bgcolor='#fdff0b' align="center">
					<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(0)'>";?>
					<?php echo round($EffectifInterneH)+round($EffectifInterneF)+round($EffectifExterne)+round($EffectifStagiaire); ?>
					<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" bgcolor='#ebebeb'>
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "EFFECTIF interne (Hors alternants et stagiaires)";}else{echo "INTERNAL WORKFORCE (Excluding alternates and trainees)";} ?>
					</td>
					<td style='border-left:1px dotted black;' bgcolor='#13bff7' align="center">
						<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(-1)'>";?>
						<?php echo round($EffectifInterneH)+round($EffectifInterneF); ?>
						<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" align="right" bgcolor='#ebebeb'>
						<?php if($_SESSION["Langue"]=="FR"){echo "dont hommes";}else{echo "whose men";} ?>&nbsp;
					</td>
					<td style='border-top:1px dotted black;border-left:1px dotted black;' align="center">
						<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(-2)'>";?>
						<?php echo round($EffectifInterneH); ?>
						<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" align="right" style='border-bottom:1px dotted black;' bgcolor='#ebebeb'>
						<?php if($_SESSION["Langue"]=="FR"){echo "dont femmes";}else{echo "whose women";} ?>&nbsp;
					</td>
					<td style='border-bottom:1px dotted black;border-top:1px dotted black;border-left:1px dotted black;' align="center">
						<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(-3)'>";?>
						<?php echo round($EffectifInterneF); ?>
						<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style='border-bottom:1px dotted black;'>
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "EFFECTIF externe (S.T. ou T.T.) sous traitant, mis à dispo et ETT";}else{echo "EXTERNAL STAFF (Subcontractor or temporary) subcontractor, available and temporary agency";} ?>
					</td>
					<td style='border-left:1px dotted black;border-bottom:1px dotted black;' bgcolor='#32c145' align="center">
						<?php echo "<a style='color:#000000;' href='javascript:OuvreFenetreModif(-4)'>";?>
						<?php echo round($EffectifExterne); ?>
						<?php echo "</a>";?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" bgcolor='#ebebeb'>
						<?php if($_SESSION["Langue"]=="FR"){echo "TYPE DE CONTRAT";}else{echo "TYPE OF CONTRACT";} ?>
					</td>
					<td bgcolor='#ebebeb'>
					
					</td>
				</tr>
				<?php
					if($_SESSION["Langue"]=="FR"){
						$requeteTypeContrat="SELECT Id, Libelle,EstInterne,EstSalarie
							FROM rh_typecontrat
							WHERE Suppr=0
							ORDER BY Libelle ASC";
					}
					else{
						$requeteTypeContrat="SELECT Id, LibelleEN AS Libelle,EstInterne,EstSalarie
							FROM rh_typecontrat
							WHERE Suppr=0
							ORDER BY Libelle ASC";
					}
					$resultTypeContrat=mysqli_query($bdd,$requeteTypeContrat);
					$nbTypeContrat=mysqli_num_rows($resultTypeContrat);
					
					if ($nbTypeContrat > 0)
					{
						$i=0;
						while($row=mysqli_fetch_array($resultTypeContrat))
						{
							$couleur="bgcolor='#ffffff'";
							if($row['EstInterne'] && $row['EstSalarie']){$couleur="bgcolor='#13bff7'";}
							elseif($row['EstInterne']==0){$couleur="bgcolor='#32c145'";}
							echo "<tr>";
							echo '<td class="Libelle" align="right" bgcolor="#ebebeb">'.$row['Libelle'].'&nbsp;</td>';
							echo "<td ".$couleur." style='border-top:1px dotted black;border-left:1px dotted black;' align='center'><a style='color:#000000;' href='javascript:OuvreFenetreModif(".$row['Id'].")'>".round($tabTypeContratValeur[$i])."</a></td>";
							echo "</tr>";
							$i++;
						}
					 }
				?>
				<tr><td height="4" bgcolor='#ebebeb'></td><td height="4" bgcolor='#ebebeb'></td></tr>
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