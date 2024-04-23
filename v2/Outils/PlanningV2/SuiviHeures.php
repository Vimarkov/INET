<?php
require("../../Menu.php");

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

<form class="test" action="SuiviHeures.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#11b9a7;">
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
						
					if($LangueAffichage=="FR"){echo "Suivi des heures travaillées & heures supp.";}else{echo "Tracking hours worked & overtime";}
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
				
				$PlateformeSelect=$_SESSION['FiltreRHSuiviHeures_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHSuiviHeures_Plateforme']=$PlateformeSelect;	
				
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect==0){$PlateformeSelect=$row['Id'];}
						if($PlateformeSelect<>"")
							{if($PlateformeSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 else{
					 echo "<option name='0' value='0' Selected></option>";
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
				
				$PrestationSelect=$_SESSION['FiltreRHSuiviHeures_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHSuiviHeures_Prestation']=$PrestationSelect;	
				
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
				 
				  $selectType=$_SESSION['FiltreRHSuiviHeures_TypeSelect'];
				 if($_POST){$selectType=$_POST['selectType'];}
				$_SESSION['FiltreRHSuiviHeures_TypeSelect']=$selectType;
				 ?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<input id="selectType" name="selectType" type="radio" onchange="submit()" value="Semaine" <?php if($selectType=="Semaine"){echo "checked";} ?>/><?php if($_SESSION["Langue"]=="FR"){echo "Semaine :";}else{echo "Week :";} ?>
				<select id="semaine" name="semaine" onchange="submit();">
					<?php
						$semaine=$_SESSION['FiltreRHSuiviHeures_Semaine'];
						if($_POST){$semaine=$_POST['semaine'];}
						$_SESSION['FiltreRHSuiviHeures_Semaine']=$semaine;
						
						for($i=1;$i<=52;$i++){
							echo "<option value='".$i."'";
							if($semaine== $i){echo " selected ";}
							echo ">".$i."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHSuiviHeures_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHSuiviHeures_Annee']=$annee;
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
						$mois=$_SESSION['FiltreRHSuiviHeures_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHSuiviHeures_Mois']=$mois;
						
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
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Par :";}else{echo "By :";} ?>
				<select style="width:100px;" name="par" onchange="submit();">
				<?php
				$ParSelect=$_SESSION['FiltreRHSuiviHeures_Par'];
				if($_POST){$ParSelect=$_POST['par'];}
				$_SESSION['FiltreRHSuiviHeures_Par']=$ParSelect;	
				?>
				<option  value='0' <?php if($ParSelect==0){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></option>
				<option  value='1' <?php if($ParSelect==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></option>
				</select>
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
		//Prestation
		if($ParSelect==0){
			$req="SELECT Id, Libelle 
				FROM new_competences_prestation 
				WHERE Id_Plateforme=".$_SESSION['FiltreRHSuiviHeures_Plateforme']." ";
			if($_SESSION['FiltreRHSuiviHeures_Prestation']<>0){
				$req.="AND Id=".$_SESSION['FiltreRHSuiviHeures_Prestation'];
			}
			else{
				$req.="AND Active=0";
			}
		}
		else{
			$req="SELECT Id_Personne AS Id,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Libelle 
				FROM rh_personne_mouvement 
				WHERE rh_personne_mouvement.DateDebut<='".$dateFin."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation)=".$_SESSION['FiltreRHSuiviHeures_Plateforme']." ";
				if($_SESSION['FiltreRHSuiviHeures_Prestation']<>0){
					$req.="AND Id=".$_SESSION['FiltreRHSuiviHeures_Prestation'];
				}
		
		}
?>
	
</body>
</html>