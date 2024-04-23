<?php
require("../../Menu.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Prestation","Contrat","TempsTravail","CP","CPA","RTT");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHCompteur_General']= str_replace($tri." ASC,","",$_SESSION['TriRHCompteur_General']);
			$_SESSION['TriRHCompteur_General']= str_replace($tri." DESC,","",$_SESSION['TriRHCompteur_General']);
			$_SESSION['TriRHCompteur_General']= str_replace($tri." ASC","",$_SESSION['TriRHCompteur_General']);
			$_SESSION['TriRHCompteur_General']= str_replace($tri." DESC","",$_SESSION['TriRHCompteur_General']);
			if($_SESSION['TriRHCompteur_'.$tri]==""){$_SESSION['TriRHCompteur_'.$tri]="ASC";$_SESSION['TriRHCompteur_General'].= $tri." ".$_SESSION['TriRHCompteur_'.$tri].",";}
			elseif($_SESSION['TriRHCompteur_'.$tri]=="ASC"){$_SESSION['TriRHCompteur_'.$tri]="DESC";$_SESSION['TriRHCompteur_General'].= $tri." ".$_SESSION['TriRHCompteur_'.$tri].",";}
			else{$_SESSION['TriRHCompteur_'.$tri]="";}
		}
	}
}
?>
	
<form action="CompteurConges.php" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="10">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Compteur des congés";}else{echo "Leave counter";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
		<tr>
			<td colspan="10">
				<table style="width:100%; cellpadding:0; cellspacing:0; align:center;" class="GeneralInfo">
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
							
							$PlateformeSelect=$_SESSION['FiltreRHCompteur_Plateforme'];
							if($_POST){$PlateformeSelect=$_POST['plateforme'];}
							$_SESSION['FiltreRHCompteur_Plateforme']=$PlateformeSelect;	
							
							if ($nbPlateforme > 0)
							{
								while($row=mysqli_fetch_array($resultPlateforme))
								{
									$selected="";
									if($PlateformeSelect<>"")
										{if($PlateformeSelect==$row['Id']){$selected="selected";}}
									else{
										$PlateformeSelect=$row['Id'];$selected="selected";
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
								}
							 }
							 ?>
							</select>
						</td>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
							<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
							<?php
							if(DroitsFormationPlateforme($TableauIdPostesRH)){
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
							}
							$resultPrestation=mysqli_query($bdd,$requeteSite);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							$PrestationSelect = 0;
							$Selected = "";
							
							$PrestationSelect=$_SESSION['FiltreRHCompteur_Prestation'];
							$estDifferent=0;
							if($_POST){
								if($PrestationSelect<>$_POST['prestations']){$estDifferent=1;}
								$PrestationSelect=$_POST['prestations'];
							}
							 echo "<option name='0_0' value='0_0'";
							 if($PrestationSelect=="0_0"){echo "selected";}
							 echo "></option>";
						 
							if ($nbPrestation > 0)
							{
								while($row=mysqli_fetch_array($resultPrestation))
								{
									$selected="";
									if($PrestationSelect<>"0")
										{if($PrestationSelect==$row['Id']){$selected="selected";}}
									else{
										$PrestationSelect=$row['Id'];
										$selected="selected";
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
								}
							 }
							 $_SESSION['FiltreRHCompteur_Prestation']=$PrestationSelect;
							 ?>
							</select>
						</td>
						<td width="10%" class="Libelle">
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
							
							$PoleSelect=$_SESSION['FiltreRHCompteur_Pole'];
							if($estDifferent==1){$PoleSelect=0;}
							elseif($_POST){$PoleSelect=$_POST['pole'];}
							
							$Selected = "";
							if ($nbPole > 0)
							{
								while($row=mysqli_fetch_array($resultPole))
								{
									$selected="";
									if($PoleSelect<>0){
										if($PoleSelect==$row['Id']){$selected="selected";}
									}
									else{
										$PoleSelect=$row['Id'];
										$selected="selected";
										
									}
								
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
								}
							 }
							 else{
								 echo "<option name='0' value='0' Selected></option>";
							 }
							 $_SESSION['FiltreRHCompteur_Pole']=$PoleSelect;
							 ?>
							</select>
						</td>
						<td width="10%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
							<select id="personne" style="width:100px;" name="personne" onchange="submit();">
								<option value='0'></option>
								<?php
									$dateDebut=AfficheDateFR($_SESSION['FiltreRHCompteur_Date']);
									
									$requetePersonne = "SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
										rh_personne_mouvement.Id_Prestation, 
										rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."'
									AND rh_personne_mouvement.Suppr=0
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHCompteur_Date']."')
									AND rh_personne_mouvement.EtatValidation=1 
									AND (SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."'
										AND (DateFin>='".$_SESSION['FiltreRHCompteur_Date']."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1)=1
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$PlateformeSelect." ";
									
									if($PrestationSelect<>0){
										$requetePersonne .= " AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
																AND rh_personne_mouvement.Id_Pole=".$PoleSelect." ";
									}
									$requetePersonne .= " ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								
									$resultPersonne=mysqli_query($bdd,$requetePersonne);
									$NbPersonne=mysqli_num_rows($resultPersonne);
									
									$personne=$_SESSION['FiltreRHCompteur_Personne'];
									if($_POST){$personne=$_POST['personne'];}
									$_SESSION['FiltreRHCompteur_Personne']= $personne;
									
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
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date début :";}else{echo "Start date :";} 
							
							
							?>
							<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
						</td>
						<td width="10%" class="Libelle">
							<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
							<div id="filtrer"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<?php 
				if(date('m',strtotime($_SESSION['FiltreRHCompteur_Date']." + 0 day"))>=6){
						$laDateCP=date('Y-06-01',strtotime($_SESSION['FiltreRHCompteur_Date']." + 0 day"));
					}
					else{
						$laDateCP=date('Y-06-01',strtotime($_SESSION['FiltreRHCompteur_Date']." - 1 year"));
					}
					
					$laDateRTT=date('Y-01-01',strtotime($_SESSION['FiltreRHCompteur_Date']." + 0 day"));
			?>
			<td>
				<table style="margin-bottom:80px;margin-right:270px;" class="GeneralInfo">
					<tr>
						
						<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHCompteur_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_Personne']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHCompteur_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_Prestation']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHCompteur_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_Contrat']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=TempsTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail";}else{echo "Work time";} ?><?php if($_SESSION['TriRHCompteur_TempsTravail']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_TempsTravail']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=CP"><?php if($_SESSION["Langue"]=="FR"){echo "CP";}else{echo "CP";} echo " > ".AfficheDateJJ_MM_AAAA($laDateCP); ?><?php if($_SESSION['TriRHCompteur_CP']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_CP']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=CPA"><?php if($_SESSION["Langue"]=="FR"){echo "CPA";}else{echo "CPA";} echo " > ".AfficheDateJJ_MM_AAAA($laDateCP); ?><?php if($_SESSION['TriRHCompteur_CPA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_CPA']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="CompteurConges.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=RTT"><?php if($_SESSION["Langue"]=="FR"){echo "RTT";}else{echo "RTT";} echo " > ".AfficheDateJJ_MM_AAAA($laDateRTT); ?><?php if($_SESSION['TriRHCompteur_RTT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHCompteur_RTT']=="ASC"){echo "&darr;";}?></a></td>
					</tr>
					<?php


					//Personnes présentes sur cette prestation à ces dates
					$PartiePersonne="";
					if($_SESSION['FiltreRHCompteur_Personne']<>0 && $_SESSION['FiltreRHCompteur_Personne']<>""){
							$PartiePersonne="AND rh_personne_mouvement.Id_Personne=".$_SESSION['FiltreRHCompteur_Personne']." ";
					}
					
					$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
								rh_personne_mouvement.Id_Prestation, 
								rh_personne_mouvement.Id_Pole,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								(SELECT CONCAT(' - ',Libelle) FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
								(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."'
										AND (DateFin>='".$_SESSION['FiltreRHCompteur_Date']."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS Contrat,
								(SELECT (SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail)
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."'
										AND (DateFin>='".$_SESSION['FiltreRHCompteur_Date']."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS TempsTravail,
								(SELECT SUM(NbJour) AS Nb
										FROM rh_absence 
										LEFT JOIN rh_personne_demandeabsence 
										ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
										WHERE rh_personne_demandeabsence.Id_Personne=new_rh_etatcivil.Id
										AND rh_absence.DateFin>='".$laDateCP."' 
										AND rh_absence.DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."' 
										AND rh_personne_demandeabsence.Suppr=0 
										AND rh_absence.Suppr=0 
										AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) = 3 
										AND rh_personne_demandeabsence.Annulation=0 
										AND EtatN1<>-1
										AND EtatN2<>-1) AS CP,
								(SELECT SUM(NbJour) AS Nb
										FROM rh_absence 
										LEFT JOIN rh_personne_demandeabsence 
										ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
										WHERE rh_personne_demandeabsence.Id_Personne=new_rh_etatcivil.Id
										AND rh_absence.DateFin>='".$laDateCP."' 
										AND rh_absence.DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."' 
										AND rh_personne_demandeabsence.Suppr=0 
										AND rh_absence.Suppr=0 
										AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) = 4 
										AND rh_personne_demandeabsence.Annulation=0 
										AND EtatN1<>-1
										AND EtatN2<>-1) AS CPA,
								(SELECT SUM(NbJour) AS Nb
										FROM rh_absence 
										LEFT JOIN rh_personne_demandeabsence 
										ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
										WHERE rh_personne_demandeabsence.Id_Personne=new_rh_etatcivil.Id
										AND rh_absence.DateFin>='".$laDateRTT."' 
										AND rh_absence.DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."' 
										AND rh_personne_demandeabsence.Suppr=0 
										AND rh_absence.Suppr=0 
										AND IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) = 7
										AND rh_personne_demandeabsence.Annulation=0 
										AND EtatN1<>-1
										AND EtatN2<>-1) AS RTT
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."'
							AND rh_personne_mouvement.Suppr=0
							AND (SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
							FROM rh_personne_contrat
							WHERE Suppr=0
							AND DateDebut<='".$_SESSION['FiltreRHCompteur_Date']."'
							AND (DateFin>='".$_SESSION['FiltreRHCompteur_Date']."' OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant')
							AND Id_Personne=new_rh_etatcivil.Id
							ORDER BY Id_Personne, DateDebut DESC LIMIT 1)=1
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHCompteur_Date']."')
							AND rh_personne_mouvement.EtatValidation=1 
							".$PartiePersonne." 
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$PlateformeSelect." ";
									
							if($PrestationSelect<>0){
								$req .= " AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
														AND rh_personne_mouvement.Id_Pole=".$PoleSelect." ";
							}
				
					$requeteOrder="";
					if($_SESSION['TriRHCompteur_General']<>""){
						$requeteOrder="ORDER BY ".substr($_SESSION['TriRHCompteur_General'],0,-1);
					}
					

					$resultPersonne=mysqli_query($bdd,$req.$requeteOrder);
					$nbPersonne=mysqli_num_rows($resultPersonne);
					
					$couleur="#FFFFFF";
					if ($nbPersonne > 0){
						while($row=mysqli_fetch_array($resultPersonne)){
							if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
							else{$couleur="#FFFFFF";}
					
							echo "<tr bgcolor='".$couleur."'>";
							echo "<td>".$row['Personne']."</td>";
							echo "<td>".$row['Prestation']." ".$row['Pole']."</td>";
							echo "<td>".$row['Contrat']."</td>";
							echo "<td>".$row['TempsTravail']."</td>";
							echo "<td>".$row['CP']."</td>";
							echo "<td>".$row['CPA']."</td>";
							echo "<td>".$row['RTT']."</td>";

							echo "</tr>";
						}
					 }
					?>
				</table>
			</td>
		</tr>
		<tr>
		<td height="200"></td>
	</tr>
	</table>
</form>
</body>
</html>
