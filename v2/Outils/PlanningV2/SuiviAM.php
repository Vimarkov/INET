<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Modif_AbsenceInjustifiee.php?Mode=M&Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageHS","status=no,menubar=no,width=1000,height=400");
		w.focus();
		}
	function OuvreFenetreAjoutVM(Menu,Id,Id_Personne,Page)
	{var w=window.open("Ajout_VM.php?Mode=A&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=700,height=400,scrollbars=1'");
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

<form class="test" action="SuiviAM.php" method="post">
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
						
					if($LangueAffichage=="FR"){echo "Suivi des arrêts maladies long";}else{echo "Follow-up of long illnesses";}
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
				
				$PlateformeSelect=$_SESSION['FiltreRHSuiviAM_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHSuiviAM_Plateforme']=$PlateformeSelect;	
				
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
				 
				 $dateSuivi=$_SESSION['FiltreRHSuiviAM_Date'];
				if($_POST){$dateSuivi=$_POST['dateSuivi'];}
				if($dateSuivi==""){$dateSuivi=AfficheDateFR(date("Y-m-d"));}
				$_SESSION['FiltreRHSuiviAM_Date']=$dateSuivi;
				 ?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date :";}else{echo "Date :";} ?>
				<input id="dateSuivi" name="dateSuivi" type="date" value="<?php echo $dateSuivi; ?>" size="5"/>&nbsp;&nbsp;
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
		$requete2="SELECT rh_personne_demandeabsence.Id,Id_Personne,
				rh_personne_demandeabsence.DatePriseEnCompteN1,rh_personne_demandeabsence.DatePriseEnCompteRH,
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne ";
		$requete=" FROM rh_personne_demandeabsence
					WHERE rh_personne_demandeabsence.Suppr=0 
					AND Conge=0
					AND (SELECT COUNT(Id)
						FROM rh_absence 
						WHERE Suppr=0
						AND Id_Personne_DA=rh_personne_demandeabsence.Id
						AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,23)
						AND DateDebut<='".$dateSuivi."'
						AND DateFin>='".$dateSuivi."'
					)>0 ";
		$requete.="ORDER BY Personne ";
		
		$result=mysqli_query($bdd,$requete2.$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date début AM";}else{echo "Start date sick leave";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin AM";}else{echo "End date sick leave";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de reprise prévisionnelle";}else{echo "Expected recovery date";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb jours calendaires";}else{echo "Number of calendar days";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Visite médicale";}else{echo "Medical visit";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Retour salarié";}else{echo "Employee return";} ?></td>
				</tr>
	<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					$dateATester=TrsfDate_($dateSuivi);
					$bValide=1;
					$nbJourAM=0;
					
					while($bValide==1){
						
						$req="SELECT rh_personne_demandeabsence.Id 
						FROM rh_personne_demandeabsence
						WHERE rh_personne_demandeabsence.Suppr=0 
						AND Conge=0
						AND Id_Personne=".$row['Id_Personne']."
						AND (SELECT COUNT(Id)
							FROM rh_absence 
							WHERE Suppr=0
							AND Id_Personne_DA=rh_personne_demandeabsence.Id
							AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,23)
							AND DateDebut<='".$dateATester."'
							AND DateFin>='".$dateATester."'
						)>0 ";
						$resultTest=mysqli_query($bdd,$req);
						$nbTest=mysqli_num_rows($resultTest);
						
						if($nbTest>0){
							$nbJourAM++;
							$dateATester=date('Y-m-d',strtotime($dateATester." - 1 day"));
						}
						else{
							$bValide=0;
						}
					}
					$dateDebut=date('Y-m-d',strtotime($dateATester." + 1 day"));
					
					$dateATester=date('Y-m-d',strtotime(TrsfDate_($dateSuivi)." + 1 day"));
					$bValide=1;
				
					while($bValide==1){
						
						$req="SELECT rh_personne_demandeabsence.Id 
						FROM rh_personne_demandeabsence
						WHERE rh_personne_demandeabsence.Suppr=0 
						AND Conge=0
						AND Id_Personne=".$row['Id_Personne']."
						AND (SELECT COUNT(Id)
							FROM rh_absence 
							WHERE Suppr=0
							AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (1,23)
							AND Id_Personne_DA=rh_personne_demandeabsence.Id
							AND DateDebut<='".$dateATester."'
							AND DateFin>='".$dateATester."'
						)>0 ";
						$resultTest=mysqli_query($bdd,$req);
						$nbTest=mysqli_num_rows($resultTest);
						
						if($nbTest>0){
							$nbJourAM++;
							$dateATester=date('Y-m-d',strtotime($dateATester." + 1 day"));
						}
						else{
							$bValide=0;
						}
					}
					$dateFin=date('Y-m-d',strtotime($dateATester." - 1 day"));
					
					
					//Prestation actuelle de la personne
					
					
					if($nbJourAM>30){
						
						//Trouver le prochain jour travaillé 
						//Attention : valable uniquement si la personne a un contrat
						$dateATester=date('Y-m-d',strtotime($dateFin." + 1 day"));
						$Continuer=1;
						$EnContrat=1;
						while($Continuer==1){
							$EnContrat=0;
							if(EnContratCeJour($dateATester,$row['Id_Personne'])){
								if(TravailCeJourDeSemaine($dateATester,$row['Id_Personne'])<>"" && estJour_Fixe($dateATester,$row['Id_Personne'])==""){
									$Continuer=0;
								}
								else{
									$dateATester=date('Y-m-d',strtotime($dateATester." + 1 day"));
								}
								$EnContrat=1;
							}
							else{
								$Continuer=0;
								$EnContrat=0;
							}
						}
						$dateReprise="?";
						if($EnContrat==1){
							$dateReprise=$dateATester;
						}
						
						$Prestation="";
						$Pole="";
						$Id_Plateforme=0;
						$prestaPole=PrestationPole_Personne(date('Y-m-d'),$row['Id_Personne']);
						if($prestaPole<>0){
							$tab=explode("_",$prestaPole);
							$req="SELECT Libelle, Id_Plateforme FROM new_competences_prestation WHERE Id=".$tab[0];
							$resultP=mysqli_query($bdd,$req);
							$nbP=mysqli_num_rows($resultP);
							if($nbP>0){
								$rowP=mysqli_fetch_array($resultP);
								$Prestation=$rowP['Libelle'];
								$Id_Plateforme=$rowP['Id_Plateforme'];
							}
							
							$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$tab[1];
							$resultP=mysqli_query($bdd,$req);
							$nbP=mysqli_num_rows($resultP);
							if($nbP>0){
								$rowP=mysqli_fetch_array($resultP);
								$Pole=$rowP['Libelle'];
							}
						}
						
						$req="SELECT Id 
							FROM new_competences_personne_poste_plateforme 
							WHERE 
							Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
							AND Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Plateforme=".$Id_Plateforme." ";
						$resultP=mysqli_query($bdd,$req);
						$nbP=mysqli_num_rows($resultP);
						
						if($nbP>0){
							if(($PlateformeSelect>0 && $PlateformeSelect==$Id_Plateforme) || $PlateformeSelect==0){
								
								//Visite médicale 
								$dateVM="0001-01-01";
								$req="SELECT DateVisite 
									FROM rh_personne_visitemedicale 
									WHERE Id_Personne=".$row['Id_Personne']." 
									AND Id_TypeVisite=2
									AND Suppr=0 
									AND DateVisite>'".$dateFin."'
									ORDER BY DateVisite
									";
								$resultVM=mysqli_query($bdd,$req);
								$nbVM=mysqli_num_rows($resultVM);
								if($nbVM>0){
									$rowVM=mysqli_fetch_array($resultVM);
									$dateVM=$rowVM['DateVisite'];
								}
								
								//Trouver le prochain jour travaillé sans AD
								//Attention : valable uniquement si la personne a un contrat
								$dateATester=date('Y-m-d',strtotime($dateFin." + 1 day"));
								$Continuer=1;
								$EnContrat=1;
								while($Continuer==1){
									$EnContrat=0;
									if(EnContratCeJour($dateATester,$row['Id_Personne'])){
										if(TravailCeJourDeSemaine($dateATester,$row['Id_Personne'])<>"" && estJour_Fixe($dateATester,$row['Id_Personne'])==""){
											
											//Vérifier si pas en attente à domicile
											$Id_PrestationPole=PrestationPole_Personne($dateATester,$row['Id_Personne']);
											if($Id_PrestationPole<>0){
												$tabPresta=explode("_",$Id_PrestationPole);
												$Id_Presta=$tabPresta[0];
												$Id_Pole=$tabPresta[1];
												
												$req="SELECT rh_personne_vacation.Id
													FROM rh_personne_vacation 
													LEFT JOIN rh_vacation
													ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
													WHERE rh_personne_vacation.Suppr=0
													AND rh_personne_vacation.Id_Vacation=7
													AND rh_personne_vacation.Id_Personne=".$row['Id_Personne']."
													AND rh_personne_vacation.DateVacation='".$dateATester."' 
													AND Id_Prestation=".$Id_Presta."
													AND Id_Pole=".$Id_Pole."
													";
												$resultVac=mysqli_query($bdd,$req);
												$nbVac=mysqli_num_rows($resultVac);
												if($nbVac==0){
													$Continuer=0;
												}
												else{
													$dateATester=date('Y-m-d',strtotime($dateATester." + 1 day"));
												}
											}
											
										}
										else{
											$dateATester=date('Y-m-d',strtotime($dateATester." + 1 day"));
										}
										$EnContrat=1;
									}
									else{
										$Continuer=0;
										$EnContrat=0;
									}
								}
								$dateRetour="?";
								if($EnContrat==1){
									$dateRetour=$dateATester;
								}
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}

				?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td><?php echo stripslashes($row['Personne']);?></td>
								<td><?php echo AfficheDateJJ_MM_AAAA($dateDebut);?></td>
								<td><?php echo AfficheDateJJ_MM_AAAA($dateFin);?></td>
								<td><?php if($dateReprise<>"?"){echo AfficheDateJJ_MM_AAAA($dateReprise);}else{echo $dateReprise;}?></td>
								<td><?php echo $Prestation." ".$Pole;?></td>
								<td><?php echo $nbJourAM;?></td>
								<td>
								<?php 
								if($dateVM>'0001-01-01'){
									echo AfficheDateJJ_MM_AAAA($dateVM);
								}
								else{
								?>
									<a class="Modif" href="javascript:OuvreFenetreAjoutVM('<?php echo $Menu; ?>',0,<?php echo $row['Id_Personne']; ?>,'SuiviAM');">
										<img src="../../Images/add-icon.png" style="border:0;" alt="Ajout">
									</a>
								<?php
								}
								?>
								</td>
								<td><?php if($dateVM>'0001-01-01' && $dateVM<=date('Y-m-d')){echo AfficheDateJJ_MM_AAAA($dateRetour);} ?></td>
							</tr>
						<?php
							}
						}
					}
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>