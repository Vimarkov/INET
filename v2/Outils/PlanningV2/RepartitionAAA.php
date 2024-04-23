<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel()
		{window.open("Export_RepartitionAAA.php","PageExcel","status=no,menubar=no,width=90,height=90");}
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
?>

<form class="test" action="RepartitionAAA.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="listeReleves" id="listeReleves" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#fbb161;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Réparitions AAA";}else{echo "Allocation AAA";}
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
							AND Id_Poste IN (".$IdPosteControleGestion.",".$IdPosteResponsableHSE.")
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
		//echo date("H:i:s")."<br>";
		$dateDebut=date($annee."-".$mois."-01");;
		$dateFin = $dateDebut;

		$tabDateFin = explode('-', $dateFin);
		$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
		$dateFin = date("Y-m-d", $timestampFin);
		
		$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
			CONCAT(Nom,' ',Prenom) AS Personne,
			(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<='".$dateFin."'
			AND (rh_personne_contrat.DateFin>='".$dateDebut."' OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier,
			new_rh_etatcivil.MatriculeAAA
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
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
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule Paris";}else{echo "Paris number";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Heures";}else{echo "Hours";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Heures <br>formation";}else{echo "Training <br>hours";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb jour <br>équipe";}else{echo "Number of <br>team days";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb <br>CP/RTT";}else{echo "Nb CP / RTT";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb jour <br>maladie <br>&#x2A7D; 3jours";}else{echo "Number of <br>sick days <br>&#x2A7D; 3 days";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb jour <br>maladie <br>> 3jours";}else{echo "Number of <br>sick days <br>> 3 days";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb absence <br>injustifiée";}else{echo "Absence";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb Garde <br>enfant";}else{echo "Nb Child <br>care";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Montant Indem. <br>Repas+ <br>Dom/Tr";}else{echo "Compensation <br>Amount Meals + <br>Home/Work";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb Jours <br>travaillés";}else{echo "Nb Days <br>worked";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Rythme";}else{echo "Pace";} ?></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id='Div_Personnes' align="center" style='height:300px;width:100%;overflow:auto;'>
			<table class="TableCompetences" align="center" width="100%">
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					$req = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
					FROM rh_personne_mouvement 
					WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
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
							if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
							else{$couleur="#FFFFFF";}
							$NbHeures=0;
							$NbCPRTT=0;
							$NbGardeEnfant=0;
							$NbMalInf3=0;
							$NbMalSup3=0;
							$NbAbs=0;
							$NbJourEquipe=0;
							$NbJourTravaille=0;
							$MontantIndem=0;
							$Rythme="";
							$nbHeureFormVac=0;
							$tabRythme=array();
							$tabInfo=array();
							$nbHeureFormationVac=date('H:i',strtotime($dateDebut.' 00:00:00'));
							for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
								$tabInfo=InfoCeJourSurCettePresta($row['Id'],$laDate,$rowPresta['Id_Prestation']);
								$NbCPRTT=$NbCPRTT+$tabInfo[0];
								$NbGardeEnfant=$NbGardeEnfant+$tabInfo[1];
								$NbMalInf3=$NbMalInf3+$tabInfo[2];
								$NbMalSup3=$NbMalSup3+$tabInfo[3];
								$NbAbs=$NbAbs+$tabInfo[4];
								$NbJourTravaille=$NbJourTravaille+$tabInfo[5];
								$NbJourEquipe=$NbJourEquipe+$tabInfo[6];
								$leRtyme=$tabInfo[7];
								if($leRtyme<>""){
									$tabRythme[]=$leRtyme;
								}
								$MontantIndem=$MontantIndem+$tabInfo[8];
								$NbHeures=$NbHeures+$tabInfo[9];
								$nbHeure=$tabInfo[10];
								$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$nbHeure)." minute"));
							}
							
							$tab=array_unique($tabRythme);
							foreach($tab as $val){
								if($Rythme<>""){$Rythme.="|";}
								$Rythme.=$val;
							}
							
							$lesminutes=substr(date('i',strtotime($nbHeureFormationVac." + 0 hour"))/0.6,0,2);
							if(substr($lesminutes,1,1)=="."){
								$lesminutes="0".substr($lesminutes,0,1);
							}
							$nbHeureFormVac=intval(date('H',strtotime($nbHeureFormationVac." + 0 hour"))).".".$lesminutes;
							
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="6%"><?php echo stripslashes($row['MatriculeAAA']);?></td>
								<td width="8%"><?php echo stripslashes($row['Personne']);?></td>
								<td width="15%"><?php echo stripslashes($row['Metier']);?></td>
								<td width="8%"><?php echo stripslashes($rowPresta['Prestation']);?></td>
								<td width="6%"><?php echo $NbHeures;?></td>
								<td width="6%"><?php echo $nbHeureFormVac;?></td>
								<td width="6%"><?php echo $NbJourEquipe;?></td>
								<td width="6%"><?php echo $NbCPRTT;?></td>
								<td width="6%"><?php echo $NbMalInf3;?></td>
								<td width="6%"><?php echo $NbMalSup3;?></td>
								<td width="6%"><?php echo $NbAbs;?></td>
								<td width="6%"><?php echo $NbGardeEnfant;?></td>
								<td width="6%"><?php echo $MontantIndem;?> &#8364;</td>
								<td width="6%"><?php echo $NbJourTravaille;?></td>
								<td width="6%"><?php echo $Rythme;?></td>
							</tr>
							<?php
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
		//echo date("H:i:s")."<br>";
		}
	?>
</table>
</form>
</body>
</html>