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

<form class="test" action="Liste_HeureSuppAlerte.php" method="post">
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
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'&TDB=RH>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Alertes - heures supplémentaires";}else{echo "Alerts - Overtime";}
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
						
						$personne=$_SESSION['FiltreRHHSAlerte_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHHSAlerte_Personne']= $personne;
						
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
						$mois=$_SESSION['FiltreRHHSAlerte_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHHSAlerte_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							echo "<option value='".($i+1)."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHHSAlerte_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHHSAlerte_Annee']=$annee;
					?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="25%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type d'alerte :";}else{echo "Alert type :";} ?>
				<select id="typeAlerte" name="typeAlerte" onchange="submit();">
					<?php
						$typeAlerte=$_SESSION['FiltreRHHSAlerte_TypeAlerte'];
						if($_POST){$typeAlerte=$_POST['typeAlerte'];}
						$_SESSION['FiltreRHHSAlerte_TypeAlerte']=$typeAlerte;
					?>
					<option value='10h' <?php if($typeAlerte=="10h"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Plus de 10h/jour";}else{echo "More than 10h / day";} ?></option>
					<option value='48h' <?php if($typeAlerte=="48h"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Plus de 48h/semaine";}else{echo "More than 48h / week";} ?></option>
					<option value='6jours' <?php if($typeAlerte=="6jours"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Plus de 6 jours consécutifs";}else{echo "More than 6 consecutive days";} ?></option>
					<option value='0h' <?php if($typeAlerte=="0h"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Pas d'heures sur une journée en vacation";}else{echo "No hours on a day in vacation";} ?></option>
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
		$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));

		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Type d'alerte";}else{echo "Alert type";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Nb heures";}else{echo "Number of hours";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Semaine";}else{echo "Semaine";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Jour";}else{echo "Day";} ?></td>
				</tr>
	<?php
			$dernierJourMois=date('Y-m-d',strtotime($dernierJourMois." + 6 days"));
			$nb=0;
			if($typeAlerte=="10h" || $typeAlerte=="48h" || $typeAlerte=="6jours"){
				$requete = "SELECT DISTINCT new_rh_etatcivil.Id, 
					CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
				FROM new_rh_etatcivil
				LEFT JOIN rh_personne_mouvement 
				ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
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
				)
				AND (
					(
						SELECT COUNT(rh_personne_hs.Id) 
						FROM rh_personne_hs
						WHERE rh_personne_hs.Suppr=0 
						AND rh_personne_hs.Id_Personne=new_rh_etatcivil.Id
						AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$annee."-".$mois."-01'
						AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$dernierJourMois."'
						AND rh_personne_hs.Etat4=1
					)>0
				OR 
					(
						SELECT COUNT(rh_personne_rapportastreinte.Id) 
						FROM rh_personne_rapportastreinte
						WHERE rh_personne_rapportastreinte.Suppr=0 
						AND rh_personne_rapportastreinte.Id_Personne=new_rh_etatcivil.Id
						AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$annee."-".$mois."-01'
						AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)<='".$dernierJourMois."'
						AND rh_personne_rapportastreinte.EtatN2=1
					)>0
				)
				ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";

				$result=mysqli_query($bdd,$requete);
				$nbResulta=mysqli_num_rows($result);
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						$semaine=0;
						$nbJoursConsecutifs=0;
						$ListeDates="";
						for($laDate=date("Y-m-d",mktime(0,0,0,$mois,1-6,$annee));$laDate<=$dernierJourMois;$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							//Vérifier si heures supp ce jour là
							$requete = "SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE new_rh_etatcivil.Id=".$row['Id']."
							AND (
								SELECT rh_personne_mouvement.Id_Prestation
								FROM rh_personne_mouvement
								WHERE rh_personne_mouvement.DateDebut<='".$laDate."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDate."')
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
								WHERE rh_personne_mouvement.DateDebut<='".$laDate."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDate."')
								AND rh_personne_mouvement.EtatValidation=1 
								AND new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
								LIMIT 1
							) IN (
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
							)
							AND (
								(
									SELECT COUNT(rh_personne_hs.Id) 
									FROM rh_personne_hs
									WHERE rh_personne_hs.Suppr=0 
									AND rh_personne_hs.Id_Personne=new_rh_etatcivil.Id
									AND IF(DateRH>'0001-01-01',DateRH,DateHS)='".$laDate."'
									AND rh_personne_hs.Etat4=1
								)>0
							OR 
								(
									SELECT COUNT(rh_personne_rapportastreinte.Id) 
									FROM rh_personne_rapportastreinte
									WHERE rh_personne_rapportastreinte.Suppr=0 
									AND rh_personne_rapportastreinte.Id_Personne=new_rh_etatcivil.Id
									AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)='".$laDate."'
									AND rh_personne_rapportastreinte.EtatN2=1
								)>0
							) ";
							$resultHS=mysqli_query($bdd,$requete);
							$nbResultaHS=mysqli_num_rows($resultHS);
							if($nbResultaHS>0){
								$nb++;
								if($typeAlerte=="10h" && date('m',strtotime($laDate." + 0 days"))==$mois){
									$prestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
									$ok=1;
									if(substr($prestation,2,5)=="YYYYY" || substr($prestation,2,5)=="CIF00" || substr($prestation,2,5)=="CSS00" || substr($prestation,2,5)=="PAR00" || substr($prestation,2,5)=="SAB00"){$ok=0;}
									if($ok==1){
										$nbHeureJour=NombreHeuresJournee($row['Id'],$laDate);
										if($nbHeureJour>10){
										if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
										else{$couleur="#FFFFFF";}
										
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
										
										
							?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td><?php echo stripslashes($row['Personne']);?></td>
											<td><?php echo $Contrat;?></td>
											<td><?php echo $prestation;?></td>
											<td><?php if($_SESSION["Langue"]=="FR"){echo "Plus de 10h/jour";}else{echo "More than 10h / day";} ?></td>
											<td><?php echo $nbHeureJour;?></td>
											<td><?php echo date('W', strtotime($laDate." + 0  day")); ?></td>
											<td><?php echo AfficheDateJJ_MM_AAAA($laDate);?></td>
										</tr>
								<?php
										}
									}
								}
								if($typeAlerte=="48h" && date('m',strtotime($laDate." + 0 days"))==$mois){
									$prestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
									$ok=1;
									if(substr($prestation,2,5)=="YYYYY" || substr($prestation,2,5)=="CIF00" || substr($prestation,2,5)=="CSS00" || substr($prestation,2,5)=="PAR00" || substr($prestation,2,5)=="SAB00"){$ok=0;}
									if($ok==1){
										$nbHeureSemaine=0;
										if($semaine<>date('W', strtotime($laDate." + 0  day"))){
											$semaine=date('W', strtotime($laDate." + 0  day"));
											$nbHeureSemaine=NombreHeuresSemaine($row['Id'],$laDate);
										}
										if($nbHeureSemaine>48){
										if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
										else{$couleur="#FFFFFF";}
										
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
									
							?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td><?php echo stripslashes($row['Personne']);?></td>
											<td><?php echo $Contrat;?></td>
											<td><?php echo $prestation;?></td>
											<td><?php if($_SESSION["Langue"]=="FR"){echo "Plus de 48h/semaine";}else{echo "More than 48h / week";} ?></td>
											<td><?php echo $nbHeureSemaine;?></td>
											<td><?php echo date('W', strtotime($laDate." + 0  day")); ?></td>
											<td></td>
										</tr>
								<?php
										}
									}
								}
							}
							if($typeAlerte=="6jours"){
								if(ADesHeuresCeJourLa($row['Id'],$laDate)==1){
									$nbJoursConsecutifs++;
									if($ListeDates<>""){$ListeDates.="<br>";}
									$ListeDates.=AfficheDateJJ_MM_AAAA($laDate);
								}
								else{$nbJoursConsecutifs=0;$ListeDates="";}
								if($nbJoursConsecutifs>6 && $semaine<>date('W', strtotime($laDate." + 0  day"))){
									$semaine=date('W', strtotime($laDate." + 0  day"));
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
										
										
						?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td><?php echo stripslashes($row['Personne']);?></td>
										<td><?php echo $Contrat;?></td>
										<td><?php echo $prestation;?></td>
										<td><?php if($_SESSION["Langue"]=="FR"){echo "Plus de 6 jours consécutifs";}else{echo "More than 6 consecutive days";} ?></td>
										<td></td>
										<td><?php echo date('W', strtotime($laDate." + 0  day")); ?></td>
										<td><?php echo $ListeDates; ?></td>
									</tr>
							<?php
									}
								}
							}
						}
					}	
				}
			}
			elseif($typeAlerte=="0h"){
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
				)
				ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
				$result=mysqli_query($bdd,$requete);
				$nbResulta=mysqli_num_rows($result);
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						
						$semaine=0;
						$nbJoursConsecutifs=0;
						$ListeDates="";
						for($laDate=date("Y-m-d",mktime(0,0,0,$mois,1,$annee));$laDate<=$dernierJourMois;$laDate=date('Y-m-d',strtotime($laDate." + 1 day"))){
							if($typeAlerte=="0h"){
								if(EstEnVacationCeJour($row['Id'],$laDate)>0){
									$nbHeureJour=NombreHeuresJournee($row['Id'],$laDate);
									if($nbHeureJour==0){
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
							?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td><?php echo stripslashes($row['Personne']);?></td>
											<td><?php echo $Contrat;?></td>
											<td><?php echo $prestation;?></td>
											<td><?php if($_SESSION["Langue"]=="FR"){echo "Pas d'heures";}else{echo "No hours";} ?></td>
											<td><?php echo $nbHeureJour;?></td>
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