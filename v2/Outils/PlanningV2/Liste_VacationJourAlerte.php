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

<form class="test" action="Liste_VacationJourAlerte.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffffff;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'&TDB=RH>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Vacations - jours d'alertes";}else{echo "Vacations - days of alerts";}
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						if(DroitsFormationPlateforme($TableauIdPostesRH)){
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM rh_personne_hs
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=rh_personne_hs.Id_Personne
									WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
									)
									ORDER BY Personne ASC";
						}

						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHJourAlerte_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHJourAlerte_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHJourAlerte_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHJourAlerte_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							echo "<option value='".($i+1)."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHJourAlerte_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHJourAlerte_Annee']=$annee;
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
		

		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Vacation";}else{echo "Vacation";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Semaine";}else{echo "Semaine";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Jour";}else{echo "Day";} ?></td>
				</tr>
	<?php
			$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));

			$nb=0;
			$requete = "SELECT DISTINCT new_rh_etatcivil.Id, 
				CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
			FROM new_rh_etatcivil
			WHERE (
				SELECT rh_personne_mouvement.Id_Prestation
				FROM rh_personne_mouvement
				WHERE rh_personne_mouvement.DateDebut<='".$annee."-".$mois."-01'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dernierJourMois."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
				LIMIT 1
			) NOT IN (87,976,977,978,979,980,981,982,983,984,985,1264,1265,1266,1267)
			AND 
			(
				SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)
				FROM rh_personne_mouvement
				WHERE rh_personne_mouvement.DateDebut<='".$annee."-".$mois."-01'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dernierJourMois."')
				AND rh_personne_mouvement.EtatValidation=1 
				AND new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
				LIMIT 1
			) IN (
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			) ";
			
			if($_SESSION['FiltreRHJourAlerte_Personne']<>"0" && $_SESSION['FiltreRHJourAlerte_Personne']<>""){
				$requete.=" AND new_rh_etatcivil.Id=".$_SESSION['FiltreRHJourAlerte_Personne']." ";
			}
			$requete.= " ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
			$result=mysqli_query($bdd,$requete);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					
					$semaine=0;
					$nbJoursConsecutifs=0;
					$ListeDates="";
					for($laDate=date("Y-m-d",mktime(0,0,0,$mois,1,$annee));$laDate<=$dernierJourMois;$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
						$req="SELECT Id FROM rh_jouralerte WHERE Suppr=0 AND DateJour='".$laDate."' ";
						$resultJourAlert=mysqli_query($bdd,$req);
						$nbJourAlerte=mysqli_num_rows($resultJourAlert);
						if($nbJourAlerte>0){
							$Id_Vacation=EstEnVacationCeJour($row['Id'],$laDate);
							if($Id_Vacation>0){
									if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
									else{$couleur="#FFFFFF";}
									$prestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
									
									$ok=1;
									if(substr($prestation,2,5)=="YYYYY" || substr($prestation,2,5)=="CIF00" || substr($prestation,2,5)=="CSS00" || substr($prestation,2,5)=="PAR00" || substr($prestation,2,5)=="SAB00"){$ok=0;}
									if($ok==1){
										$req="SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS Contrat
										FROM rh_personne_contrat
										WHERE rh_personne_contrat.Suppr=0
										AND rh_personne_contrat.DateDebut<='".$laDate."'
										AND (rh_personne_contrat.DateFin>='".$laDate."' OR rh_personne_contrat.DateFin<='0001-01-01')
										AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
										AND rh_personne_contrat.Id_Personne=".$row['Id']."
										ORDER BY DateDebut DESC, Id DESC";
										$resultC=mysqli_query($bdd,$req);
										$nbResultaC=mysqli_num_rows($resultC);
										$Contrat="";
										if($nbResultaC>0){
											$rowC=mysqli_fetch_array($resultC);
											$Contrat=$rowC['Contrat'];
										}
										
										$Vacation="";
										$req="SELECT Nom FROM rh_vacation WHERE Id=".$Id_Vacation;
										$resultVac=mysqli_query($bdd,$req);
										$nbVac=mysqli_num_rows($resultVac);
										if($nbVac>0){
											$rowVac=mysqli_fetch_array($resultVac);
											$Vacation=$rowVac['Nom'];
										}
						?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td><?php echo stripslashes($row['Personne']);?></td>
										<td><?php echo $Contrat;?></td>
										<td><?php echo $prestation;?></td>
										<td><?php echo $Vacation;?></td>
										<td><?php echo date('W', strtotime($laDate." + 0  day")); ?></td>
										<td><?php echo AfficheDateJJ_MM_AAAA($laDate);?></td>
									</tr>
							<?php
									}
							}
						}
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