<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel()
		{window.open("Export_RepartitionSalariesAAA.php","PageExcel","status=no,menubar=no,width=90,height=90");}
	function Recharger(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnCharger2' name='btnCharger2' value='Charger'>";
		document.getElementById('charger').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnCharger2").dispatchEvent(evt);
		document.getElementById('charger').innerHTML="";			
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==11 && DroitsFormationPlateforme(array($IdPosteControleGestion))){
?>

<form class="test" action="RepartitionSalariesAAA.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="listeReleves" id="listeReleves" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#fa9426;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Ventilation pour délestage SAP";}else{echo "Ventilation for SAP load shedding";}
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
				<select class="plateforme" style="width:100px;" name="plateforme">
					<option value="0" selected></option>
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteControleGestion.")
						)
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreRHRepartitionAAA_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHRepartitionAAA_Plateforme']=$PlateformeSelect;	
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect==$row['Id']){$selected="selected";}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="12%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHRepartitionAAA_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHRepartitionAAA_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$leMois=($i+1);
							if($leMois<10){$leMois="0".$leMois;}
							echo "<option value='".$leMois."'";
							if($mois== $leMois){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHRepartitionAAA_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHRepartitionAAA_Annee']=$annee;
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
				<div id="charger"></div>
			</td>
			<td width="5%">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:OuvreFenetreExcel()">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		if($_POST){
		$dateDebut=date($annee."-".$mois."-01");;
		$dateFin = $dateDebut;

		$tabDateFin = explode('-', $dateFin);
		$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
		$dateFin = date("Y-m-d", $timestampFin);
		
		$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
			CONCAT(Nom,' ',Prenom) AS Personne,
			new_rh_etatcivil.MatriculeAAA,new_rh_etatcivil.MatriculeDSK,
			new_rh_etatcivil.MatriculeDaher,new_rh_etatcivil.CentreDeCout
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";		
		$requeteOrder="ORDER BY Personne ASC";

		$result=mysqli_query($bdd,$req.$requeteOrder);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<div id='Div_EnTete' align="center" style='width:99%;'>
			<table class="TableCompetences" align="center" width="80%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule Paris";}else{echo "Paris number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule DSK";}else{echo "DSK number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule Daher";}else{echo "Daher number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Affaire";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Centre de coût";}else{echo "Cost center";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "EOTP";}else{echo "EOTP";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Heures";}else{echo "Hours";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Heures totales";}else{echo "Total hours";} ?></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id='Div_Personnes' align="center" style='height:300px;width:100%;overflow:auto;'>
			<table class="TableCompetences" align="center" width="80%">
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					$NbHeuresTotalSalarie=0;
					$NbHeuresTotalInterim=0;
					for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
						if(estSalarie($laDate,$row['Id']) || estInterne($laDate,$row['Id'])){
							$NbHeuresTotalSalarie=$NbHeuresTotalSalarie+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate);
						}
						elseif(estInterim($laDate,$row['Id'])){
							$NbHeuresTotalInterim=$NbHeuresTotalInterim+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate);
						}
					}
					$req = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						(SELECT EOTP FROM new_competences_prestation WHERE Id=Id_Prestation) AS EOTP
					FROM rh_personne_mouvement 
					WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
					AND rh_personne_mouvement.EtatValidation=1 
					AND rh_personne_mouvement.Suppr=0
					AND rh_personne_mouvement.Id_Personne=".$row['Id']."
					AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";		
					$requeteOrder="ORDER BY Prestation ASC";

					$resultPresta=mysqli_query($bdd,$req.$requeteOrder);
					$nbResultaPresta=mysqli_num_rows($resultPresta);
					if($nbResultaPresta>0){
						while($rowPresta=mysqli_fetch_array($resultPresta))
						{
							$NbHeuresSalarie=0;
							$NbHeuresInterim=0;
							for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
								if(estSalarie($laDate,$row['Id']) || estInterne($laDate,$row['Id'])){
									$NbHeuresSalarie=$NbHeuresSalarie+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate,$rowPresta['Id_Prestation']);
								}
								elseif(estInterim($laDate,$row['Id'])){
									$NbHeuresInterim=$NbHeuresInterim+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate,$rowPresta['Id_Prestation']);
								}
							}

							if($NbHeuresTotalSalarie>0){
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}

								?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="10%"><?php echo stripslashes($row['MatriculeAAA']);?></td>
									<td width="10%"></td>
									<td width="10%"><?php echo stripslashes($row['MatriculeDaher']);?></td>
									<td width="10%"><?php echo stripslashes($row['Personne']);?></td>
									<td width="10%"><?php echo stripslashes($rowPresta['Prestation']);?></td>
									<td width="10%"><?php echo stripslashes($row['CentreDeCout']);?></td>
									<td width="10%">A0220</td>
									<td width="10%"><?php echo stripslashes($rowPresta['EOTP']);?></td>
									<td width="10%"><?php echo $NbHeuresSalarie;?></td>
									<td width="10%"><?php echo $NbHeuresTotalSalarie;?></td>
								</tr>
								<?php
							}
							if($NbHeuresTotalInterim>0){
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}

								?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="10%"></td>
									<td width="10%"><?php echo stripslashes($row['MatriculeDSK']);?></td>
									<td width="10%"><?php echo stripslashes($row['MatriculeDaher']);?></td>
									<td width="10%"><?php echo stripslashes($row['Personne']);?></td>
									<td width="10%"><?php echo stripslashes($rowPresta['Prestation']);?></td>
									<td width="10%"><?php echo stripslashes($row['CentreDeCout']);?></td>
									<td width="10%">A0221</td>
									<td width="10%"><?php echo stripslashes($rowPresta['EOTP']);?></td>
									<td width="10%"><?php echo $NbHeuresInterim;?></td>
									<td width="10%"><?php echo $NbHeuresTotalInterim;?></td>
								</tr>
								<?php
							}
						}
					}
				}
			}

			?>
			</table>
			</div>
		</td>
	</tr>
	<?php 
		}
	?>
</table>
</form>
<?php
}

?>
</body>
</html>