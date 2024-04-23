<?php
require("../../Menu.php");

$EnAttente="#ffbf03";
$Automatique="#3d9538";
$EnTraitementRH="#449ef0";
$Validee="#6beb47";
$Refusee="#ff5353";
$Gris="#dddddd";
$AbsenceInjustifies="#ff0303";

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Personne","CodeMetier");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHPlanningGlobal_General']= str_replace($tri." ASC,","",$_SESSION['TriRHPlanningGlobal_General']);
			$_SESSION['TriRHPlanningGlobal_General']= str_replace($tri." DESC,","",$_SESSION['TriRHPlanningGlobal_General']);
			$_SESSION['TriRHPlanningGlobal_General']= str_replace($tri." ASC","",$_SESSION['TriRHPlanningGlobal_General']);
			$_SESSION['TriRHPlanningGlobal_General']= str_replace($tri." DESC","",$_SESSION['TriRHPlanningGlobal_General']);
			if($_SESSION['TriRHPlanningGlobal_'.$tri]==""){$_SESSION['TriRHPlanningGlobal_'.$tri]="ASC";$_SESSION['TriRHPlanningGlobal_General'].= $tri." ".$_SESSION['TriRHPlanningGlobal_'.$tri].",";}
			elseif($_SESSION['TriRHPlanningGlobal_'.$tri]=="ASC"){$_SESSION['TriRHPlanningGlobal_'.$tri]="DESC";$_SESSION['TriRHPlanningGlobal_General'].= $tri." ".$_SESSION['TriRHPlanningGlobal_'.$tri].",";}
			else{$_SESSION['TriRHPlanningGlobal_'.$tri]="";}
		}
	}
}
?>
	
<form action="PlanningGlobal.php" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="10">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#93d7cd;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Visualisation des plannings";}else{echo "Visualization of schedules";}
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
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
							<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
							<?php
							$requeteSite="SELECT Id, Libelle
								FROM new_competences_prestation
								WHERE Active=0
								ORDER BY Libelle ASC";

							$resultPrestation=mysqli_query($bdd,$requeteSite);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							$PrestationSelect = 0;
							$Selected = "";
							
							$PrestationSelect=$_SESSION['FiltreRHPlanningGlobal_Prestation'];
							$estDifferent=0;
							if($_POST){
								if($PrestationSelect<>$_POST['prestations']){$estDifferent=1;}
								$PrestationSelect=$_POST['prestations'];
							}
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
							 else{
								 echo "<option name='0' value='0' Selected></option>";
							 }
							 $_SESSION['FiltreRHPlanningGlobal_Prestation']=$PrestationSelect;
							 ?>
							</select>
						</td>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "P�le :";}else{echo "Pole :";} ?>
							<select class="pole" style="width:100px;" name="pole" onchange="submit();">
							<?php

							$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
									FROM new_competences_pole
									LEFT JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									WHERE Actif=0
									AND new_competences_pole.Id_Prestation=".$PrestationSelect."
									ORDER BY new_competences_pole.Libelle ASC";
							$resultPole=mysqli_query($bdd,$requetePole);
							$nbPole=mysqli_num_rows($resultPole);
							
							$PoleSelect=$_SESSION['FiltreRHPlanningGlobal_Pole'];
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
							 $_SESSION['FiltreRHPlanningGlobal_Pole']=$PoleSelect;
							 ?>
							</select>
						</td>
						<td width="10%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
							<select id="personne" style="width:100px;" name="personne" onchange="submit();">
								<option value='0'></option>
								<?php
									$dateDebut=AfficheDateFR($_SESSION['FiltreRHPlanningGlobal_DateDebut']);
									$dateDeFin=AfficheDateFR($_SESSION['FiltreRHPlanningGlobal_DateFin']);
									$MoisPrecedent=date("Y-m-d",strtotime(TrsfDate_($dateDebut)." - 1 month"));
									$MoisSuivant=date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month"));
									
									if(isset($_GET['DateDeDebut']))
									{
										$dateDebut=$_GET['DateDeDebut'];
										$_SESSION['FiltreRHPlanningGlobal_DateDebut']=TrsfDate_($dateDebut);
										
										if(TrsfDate_($dateDebut)>TrsfDate_($dateDeFin)){
											$dateDeFin=$dateDebut;
											$_SESSION['FiltreRHPlanningGlobal_DateFin']=TrsfDate_($dateDeFin);
										}
										$MoisPrecedent=TrsfDate_($dateDebut);
										$MoisSuivant=TrsfDate_($dateDeFin);
									}
									elseif(isset($_POST['DateDeDebut']))
									{
										$dateDebut=$_POST['DateDeDebut'];
										$_SESSION['FiltreRHPlanningGlobal_DateDebut']=TrsfDate_($dateDebut);
										
										if(TrsfDate_($dateDebut)>TrsfDate_($dateDeFin)){
											$dateDeFin=$dateDebut;
											$_SESSION['FiltreRHPlanningGlobal_DateFin']=TrsfDate_($dateDeFin);
										}
										
										$MoisPrecedent=TrsfDate_($dateDebut);
										$MoisSuivant=TrsfDate_($dateDebut);
										
										$MoisPrecedent=date("Y-m-d",strtotime($MoisPrecedent." - 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime($MoisSuivant." + 1 month"));
									}
									
									if(isset($_POST['DateDeFin']))
									{
										$dateDeFin=$_POST['DateDeFin'];
										$_SESSION['FiltreRHPlanningGlobal_DateFin']=TrsfDate_($dateDeFin);
										
										if(TrsfDate_($dateDebut)>TrsfDate_($dateDeFin)){
											$dateDebut=$dateDeFin;
											$_SESSION['FiltreRHPlanningGlobal_DateDebut']=TrsfDate_($dateDebut);
										}
									}
									if(isset($_POST['MoisPrecedent']))
									{
										$dateDebut=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." - 1 month")));
										$dateDeFin=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDeFin)." - 1 month")));
										
										$_SESSION['FiltreRHPlanningGlobal_DateDebut']=TrsfDate_($dateDebut);
										$_SESSION['FiltreRHPlanningGlobal_DateFin']=TrsfDate_($dateDeFin);
										
										$MoisPrecedent=TrsfDate_($dateDebut);
										$MoisSuivant=TrsfDate_($dateDebut);
										
										$MoisPrecedent=date("Y-m-d",strtotime($MoisPrecedent." - 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime($MoisSuivant." + 1 month"));
									}
									elseif(isset($_POST['MoisSuivant']))
									{
										$dateDebut=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month")));
										$dateDeFin=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDeFin)." + 1 month")));
										
										$_SESSION['FiltreRHPlanningGlobal_DateDebut']=TrsfDate_($dateDebut);
										$_SESSION['FiltreRHPlanningGlobal_DateFin']=TrsfDate_($dateDeFin);
										
										$MoisPrecedent=TrsfDate_($dateDebut);
										$MoisSuivant=TrsfDate_($dateDebut);
										
										$MoisPrecedent=date("Y-m-d",strtotime($MoisPrecedent." - 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime($MoisSuivant." + 1 month"));
									}
									
									$requetePersonne = "SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
										rh_personne_mouvement.Id_Prestation, 
										rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHPlanningGlobal_DateFin']."'
									AND rh_personne_mouvement.Suppr=0
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHPlanningGlobal_DateDebut']."')
									AND rh_personne_mouvement.EtatValidation=1 
									AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
									AND rh_personne_mouvement.Id_Pole=".$PoleSelect."
									ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								
									$resultPersonne=mysqli_query($bdd,$requetePersonne);
									$NbPersonne=mysqli_num_rows($resultPersonne);
									
									$personne=$_SESSION['FiltreRHPlanningGlobal_Personne'];
									if($_POST){$personne=$_POST['personne'];}
									$_SESSION['FiltreRHPlanningGlobal_Personne']= $personne;
									
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
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date d�but :";}else{echo "Start date :";} 
							
							
							?>
							<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
						</td>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date fin :";}else{echo "End date :";} ?>
							<input type="date" style="text-align:center;" name="DateDeFin"  size="10" value="<?php echo $dateDeFin; ?>">
							&nbsp;
							<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
							<div id="filtrer"></div>
						</td>
						<td width="10%">
							<input class="Bouton" name="MoisPrecedent" size="10" type="submit" value="<< <?php echo AfficheDateJJ_MM_AAAA($MoisPrecedent); ?>">
							<input class="Bouton" name="MoisSuivant" size="10" type="submit" value="<?php echo AfficheDateJJ_MM_AAAA($MoisSuivant); ?> >>">
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td>
				<table style="margin-bottom:80px;margin-right:270px;" class="GeneralInfo">
					<?php
					$EnTeteMois = "<td ";
					$EnTeteSemaine = "<td ";
					$EnTeteJourSemaine = "";
					$EnTeteJour = "";
					
					$tmpDate=$_SESSION['FiltreRHPlanningGlobal_DateDebut'];
					$dateFin=$_SESSION['FiltreRHPlanningGlobal_DateFin'];
					
					$cptMois = 0;
					$cptSemaine = 0;
					$cptJour = 0;
					
					if($_SESSION["Langue"]=="FR"){
						$joursem = array("D", "L", "M", "M", "J", "V", "S");
						$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
					}
					else{
						$joursem = array("S","M", "T", "W", "T", "F", "S");
						$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					}
					
					// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
					while ($tmpDate <= $dateFin) 
					{
						$tabDate = explode('-', $tmpDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
						$jour = date('w', $timestamp);
						$mois = $tabDate[1];
						$semaine = date('W', $timestamp);
						$cptMois++;
						$cptSemaine++;
						
						if ($dateDuJour == $tmpDate){
							$EnTeteJourSemaine .= "<td class='EnTetePlanningJourV2' >".$joursem[$jour]."</td>";
							$EnTeteJour .= "<td class='EnTetePlanningJourV2'>".$tabDate[2]."</td>";
						}
						else{
							$EnTeteJourSemaine .= "<td class='EnTetePlanningV2' >".$joursem[$jour]."</td>";
							$EnTeteJour .= "<td class='EnTetePlanningV2'>".$tabDate[2]."</td>";
						}
						
						//Jour suivant
						$tabDate = explode('-', $tmpDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
						$tmpDate = date("Y-m-d", $timestamp);
						if (date('m', $timestamp) <> $tabDate[1])
						{
							$EnTeteMois .= " class='EnTeteMoisV2' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</td><td ";
							$cptMois = 0;
						}
						if (date('W', $timestamp) <> $semaine)
						{
							$EnTeteSemaine .= " class='EnTeteSemaineV2' colspan=".$cptSemaine.">S".$semaine."</td><td ";
							$cptSemaine = 0;
						}
						$cptJour++;
					}
					if (date('m', $timestamp) == $tabDate[1]){
						$EnTeteMois .= " class='EnTeteMoisV2' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</td>";
					}
					else{
						$EnTeteMois =substr($EnTeteMois, 0, -5)."" ;
					}
					
					if ($joursem[$jour]<>"D"){
						$EnTeteSemaine .= " class='EnTeteSemaineV2' colspan=".$cptSemaine.">S".$semaine."</td>";
					}
					else{
						$EnTeteSemaine =substr($EnTeteSemaine, 0, -4)."" ;
					}
					
					?>
					<tr align="center">
						<td colspan="3" rowspan ="3" align="center" valign="middle">
							<table>
								<tr>
									<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "L�gende : ";}else{echo "Legend : ";} ?></td>
									<td style='font-weight:bold;'><?php if($_SESSION["Langue"]=="FR"){echo "Valid�";}else{echo "Validated";} ?></td>
									<td></td>
									<td class="EnAttenteValidation"><?php if($_SESSION["Langue"]=="FR"){echo "En cours de pr� validation";}else{echo "In the process of pre-validation";} ?></td>
								</tr>
							</table>
						</td>
						<?php echo $EnTeteMois ;?>
					</tr>
					<tr align="center">
						<?php echo $EnTeteSemaine ;?>
					</tr>
					<tr align="center">
						<?php echo $EnTeteJourSemaine ;?>
					</tr>
					<tr align="center">
						<td class="EnTeteSemaineV2" style="font-size:12px;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="PlanningGlobal.php?Menu=<?php echo $Menu; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHPlanningGlobal_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHPlanningGlobal_Personne']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteSemaineV2" style="font-size:12px;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="PlanningGlobal.php?Menu=<?php echo $Menu; ?>&Tri=CodeMetier"><?php if($_SESSION["Langue"]=="FR"){echo "M�tier";}else{echo "Job";} ?><?php if($_SESSION['TriRHPlanningGlobal_CodeMetier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHPlanningGlobal_CodeMetier']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteSemaineV2" style="font-size:12px;text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
						<?php echo $EnTeteJour ;?>
					</tr>
					<?php
					// FIN GESTION DES ENTETES DU TABLEAU
					
					//DEBUT CORPS DU TABLEAU
					$tmpDate=$_SESSION['FiltreRHPlanningGlobal_DateDebut'];
					$dateFin=$_SESSION['FiltreRHPlanningGlobal_DateFin'];
					
					//Personnes pr�sentes sur cette prestation � ces dates
					$PartiePersonne="";
					if($_SESSION['FiltreRHPlanningGlobal_Personne']<>0){
							$PartiePersonne="AND rh_personne_mouvement.Id_Personne=".$_SESSION['FiltreRHPlanningGlobal_Personne']." ";
					}
					if($_SESSION["Langue"]=="FR"){
					$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
								rh_personne_mouvement.Id_Prestation, 
								rh_personne_mouvement.Id_Pole,
								(SELECT (SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS CodeMetier,
								(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS Metier,
								(SELECT (SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat)
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS TypeContrat
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHPlanningGlobal_DateFin']."'
							AND rh_personne_mouvement.Suppr=0
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHPlanningGlobal_DateDebut']."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
							AND rh_personne_mouvement.Id_Pole=".$PoleSelect."
							".$PartiePersonne." ";
					}
					else{
						$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
								rh_personne_mouvement.Id_Prestation, 
								rh_personne_mouvement.Id_Pole,
								(SELECT (SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS CodeMetier,
								(SELECT (SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS Metier,
								(SELECT (SELECT LibelleEN FROM rh_typecontrat WHERE Id=Id_TypeContrat)
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=new_rh_etatcivil.Id
										ORDER BY Id_Personne, DateDebut DESC LIMIT 1) AS TypeContrat
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHPlanningGlobal_DateFin']."'
							AND rh_personne_mouvement.Suppr=0
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHPlanningGlobal_DateDebut']."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
							AND rh_personne_mouvement.Id_Pole=".$PoleSelect."
							".$PartiePersonne." ";
					}					
					$requeteOrder="";
					if($_SESSION['TriRHPlanningGlobal_General']<>""){
						$requeteOrder="ORDER BY ".substr($_SESSION['TriRHPlanningGlobal_General'],0,-1);
					}
					$resultPersonne=mysqli_query($bdd,$req.$requeteOrder);
					$nbPersonne=mysqli_num_rows($resultPersonne);
					
					if ($nbPersonne > 0){
						$couleurLigne="PersonnePlanningV2";
						$couleurLigneMetier="MetierPlanningV2";
						
						while($row=mysqli_fetch_array($resultPersonne)){
							if($couleurLigne=="PersonnePlanningV2"){$couleurLigne="PersonnePlanning2V2";$couleurLigneMetier="MetierPlanning2V2";}
							else{$couleurLigne="PersonnePlanningV2";$couleurLigneMetier="MetierPlanningV2";}
						
							$Metier="";
							$Code=$row['CodeMetier'];
							if($_SESSION["Langue"]=="FR"){
								$Metier="M�tier : ".$row['Metier'];
							}
							else{
								$Metier="Job : ".$row['Metier'];
							}
							
							echo "<tr>";
							echo "<td class='".$couleurLigne."'>".$row['Personne']."</td>";
							echo "<td id='leHoverPersonne' class='".$couleurLigneMetier."'>".$Code."<span>".$Metier."</span></td>";
							echo "<td id='leHoverPersonne' class='".$couleurLigneMetier."'>".$row['TypeContrat']."</td>";
							
							$tmpDate=$_SESSION['FiltreRHPlanningGlobal_DateDebut'];
							$dateFin=$_SESSION['FiltreRHPlanningGlobal_DateFin'];
							
							
							//Liste des cong�s
							$reqConges="SELECT rh_personne_demandeabsence.Id ,rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
										rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
										rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
										(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
										(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
										(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS CouleurIni,
										(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS CouleurDef
										FROM rh_absence 
										LEFT JOIN rh_personne_demandeabsence 
										ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
										WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id']." 
										AND rh_absence.DateFin>='".$tmpDate."' 
										AND rh_absence.DateDebut<='".$dateFin."' 
										AND rh_personne_demandeabsence.Suppr=0 
										AND rh_absence.Suppr=0 
										AND rh_personne_demandeabsence.Annulation=0 
										AND rh_personne_demandeabsence.Conge=1 
										AND EtatN1<>-1
										AND EtatN2<>-1
										ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
							$resultConges=mysqli_query($bdd,$reqConges);
							$nbConges=mysqli_num_rows($resultConges);

							//Liste des absences
							$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
										rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
										(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
										(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
										(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS CouleurIni,
										(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS CouleurDef
										FROM rh_absence 
										LEFT JOIN rh_personne_demandeabsence 
										ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
										WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id']." 
										AND rh_absence.DateFin>='".$tmpDate."' 
										AND rh_absence.DateDebut<='".$dateFin."' 
										AND rh_personne_demandeabsence.Suppr=0 
										AND rh_absence.Suppr=0  
										AND rh_personne_demandeabsence.Conge=0 
										AND EtatN1<>-1
										AND EtatN2<>-1
										ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
							$resultAbs=mysqli_query($bdd,$reqAbs);
							$nbAbs=mysqli_num_rows($resultAbs);

							//Liste des heures suppl�mentaires
							$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,
										IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,
										IF(
											rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1,
											1,
											IF(
												rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1,
												2,
												IF(
													rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01',
													3,
													IF(
														rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1,
														4,
														5
													)
												)
											)
										)
										AS Etat
									FROM rh_personne_hs
									WHERE Suppr=0 
									AND Id_Personne=".$row['Id']." 
									AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$tmpDate."' 
									AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$dateFin."' 
									AND Etat2<>-1
									AND Etat3<>-1
									AND Etat4<>-1
									";
							$resultHS=mysqli_query($bdd,$req);
							$nb2HS=mysqli_num_rows($resultHS);
												
							//Liste des astreintes
							$req="SELECT IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte) AS DateAstreinte,
									IF(
										rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1,
										1,
										IF(
											rh_personne_rapportastreinte.DateValidationRH<='0001-01-01' AND rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.EtatN1=1,
											2,
											IF(
												rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.DateValidationRH>'0001-01-01',
												3,
												IF(
													rh_personne_rapportastreinte.EtatN2=-1 OR rh_personne_rapportastreinte.EtatN1=-1,
													4,
													5
												)
											)
										)
									) AS Etat,
								TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
								TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
								TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,Montant,Intervention
								FROM rh_personne_rapportastreinte
								WHERE rh_personne_rapportastreinte.Suppr=0
								AND rh_personne_rapportastreinte.Id_Personne=".$row['Id']."
								AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$tmpDate."' 
								AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)<='".$dateFin."' 
								AND EtatN1<>-1
								AND EtatN2<>-1
								";
							$resultAst=mysqli_query($bdd,$req);
							$nbAst=mysqli_num_rows($resultAst);
							
							//Formation dans l'outil formation 
							$req="  SELECT
										form_session_date.DateSession,
										Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause
									FROM
										form_session_date 
										LEFT JOIN form_session 
										ON form_session_date.Id_Session=form_session.Id
									WHERE
										form_session_date.Suppr=0 
										AND form_session.Suppr=0
										AND form_session.Annule=0 
										AND form_session_date.DateSession>='".$tmpDate."'
										AND form_session_date.DateSession<='".$dateFin."'
										AND
										(
											SELECT
												COUNT(form_session_personne.Id) 
											FROM
												form_session_personne
											WHERE
												form_session_personne.Suppr=0
												AND form_session_personne.Id_Personne=".$row['Id']." 
												AND form_session_personne.Validation_Inscription=1
												AND form_session_personne.Id_Session=form_session.Id
												AND Presence=1
									   )>0 ";
							$resultSession=mysqli_query($bdd,$req);
							$nbSession=mysqli_num_rows($resultSession);
				
							while ($tmpDate <= $dateFin) {
								//Recherche si planning pour ce jour-ci
								$Couleur = "";
								$CelPlanning= "";
								$ClassDiv = "";
								$contenu="";
								$bEtatConges="rien";
								$bEtatAbsence="rien";
								$bEtatAstreinte="rien";
								$bEtatHS="rien";
								$indice="";
								$Id_Contenu=0;
								$estUneVacation=0;
								$valAstreinte="";
								$divers="";
								$commentaire="";
								$estUnConge=0;
								$Travail=0;
								$IndiceAbs="";
								$NbHeureAbsJour=0;
								$NbHeureAbsNuit=0;
								$NbHeureSuppJour=0;
								$NbHeureSuppNuit=0;
								$nbHS=0;
								$onClick="";
								$nbHeureFormationVac=date('H:i',strtotime($tmpDate.' 00:00:00'));
								$nbHeureFormation=date('H:i',strtotime($tmpDate.' 00:00:00'));
								$RH="";
								$ClassComment="";
								
								//V�rifier si la personne appartient � cette prestation ce jour l� 
								if(appartientPrestation($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect)==1){
									$Couleur=TravailCeJourDeSemaine($tmpDate,$row['Id']);

									$tabDateMois = explode('-', $tmpDate);
									$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
										
									if ($Couleur == ""){
										if(estWE($timestampMois)){
											$Couleur="style='background-color:".$Gris.";'";
											$ClassDiv ="weekFerieV2";
										}
										else{
											$ClassDiv ="semaine";
										}
									}
									else{
										$Travail=1;
										if(estWE($timestampMois)){
											$ClassDiv ="weekFerieV2";
										}
										else{
											$ClassDiv ="semaine";
										}
										
										//V�rifier si la personne est en VSD ce jour l�
										$Id_Contenu=IdVacationCeJourDeSemaine($tmpDate,$row['Id']);
										if($Id_Contenu==1){
											if($_SESSION["Langue"]=="FR"){$contenu="J";}
											else{$contenu="D";}
										}
										elseif($Id_Contenu==15){
											if($_SESSION["Langue"]=="FR"){$contenu="SDL";}
											else{$contenu="SDL";}
										}
										elseif($Id_Contenu==18){
											if($_SESSION["Langue"]=="FR"){$contenu="SD";}
											else{$contenu="SD";}
										}
										else{
											if($_SESSION["Langue"]=="FR"){$contenu="VSD";}
											else{$contenu="VSD";}
										}

										
										
										$estUneVacation=1;
										$Couleur="style='background-color:".$Couleur.";'";

										$jourFixe=estJour_Fixe($tmpDate,$row['Id']);
										$Id_Contrat =IdContrat($row['Id'],$tmpDate);
										if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
											$Couleur="style='background-color:".$Automatique.";'";
											$contenu=$jourFixe;
											$Id_Contenu=estJour_Fixe_Id($tmpDate,$row['Id']);
											$estUneVacation=0;
											$onClick="onclick=\"javascript:OuvreFenetreModifPlanning(".$Menu.",".$row['Id'].",'".$tmpDate."')\" ";
										}
										//V�rifier si la personne n'a pas une vacation particuli�re ce jour l� 
										$Id_Vacation=VacationPersonne($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
										if($Id_Vacation>0){
											$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
											$resultVac=mysqli_query($bdd,$req);
											$nbVac=mysqli_num_rows($resultVac);
											if($nbVac>0){
												$rowVac=mysqli_fetch_array($resultVac);
												$Couleur="style='background-color:".$rowVac['Couleur'].";'";
												$contenu=$rowVac['Nom'];
												$Id_Contenu=$Id_Vacation;
												$estUneVacation=1;
											}
											$RH="";
										
											$divers=VacationPersonneDivers($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
											$commentaire=VacationPersonneCommentaire($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
											if(VacationPersonneEmisParRH($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect)==1){
												if($_SESSION['Langue']=="FR"){$RH ="RH";}
												else{$RH ="HR";}
											}
											
											$ClassComment="";
											if($commentaire<>"" || $divers<>""){
												if($RH==""){
													$ClassComment="Comment";
												}
												else{
													if($_SESSION['Langue']=="FR"){$ClassComment ="CommentRH";}
													else{$ClassComment ="CommentHR";}
													$RH="";
												}
											}
											
											$ClassDiv .=" ".$RH." ".$ClassComment." ";
										}
									}
									
									//Absences
									if($Travail==1){
										if($nbAbs>0){
											mysqli_data_seek($resultAbs,0);
											while($rowAbs=mysqli_fetch_array($resultAbs)){
												if($rowAbs['DateDebut']<=$tmpDate && $rowAbs['DateFin']>=$tmpDate){
													$bEtatAbsence="validee";
													if($rowAbs['NbHeureAbsJour']<>0 || $rowAbs['NbHeureAbsNuit']<>0){
														$NbHeureAbsJour=$rowAbs['NbHeureAbsJour'];
														$NbHeureAbsNuit=$rowAbs['NbHeureAbsNuit'];
														if($rowAbs['TypeAbsenceDef']<>""){
															$IndiceAbs=$rowAbs['TypeAbsenceDef']." ";
															if($rowAbs['Id_TypeAbsenceDefinitif']==0){
																$bEtatAbsence="absInjustifiee";
																$IndiceAbs="ABS ";
															}
														}
														else{
															$IndiceAbs=$rowAbs['TypeAbsenceIni']." ";
															if($rowAbs['Id_TypeAbsenceInitial']==0){
																$bEtatAbsence="absInjustifiee";
																$IndiceAbs="ABS ";
															}
														}
													}
													else{
														if($rowAbs['TypeAbsenceDef']<>""){
															$contenu=$rowAbs['TypeAbsenceDef'];
															$Id_Contenu=$rowAbs['Id_TypeAbsenceDefinitif'];
															$estUneVacation=0;
															$Couleur="style='background-color:".$rowAbs['CouleurDef'].";'";
															if($rowAbs['Id_TypeAbsenceDefinitif']==0){
																$bEtatAbsence="absInjustifiee";
																$contenu="ABS";
																$Id_Contenu=0;
																$estUneVacation=0;
																$Couleur="style='background-color:#ff1111;'";
															}
														}
														else{
															$contenu=$rowAbs['TypeAbsenceIni'];
															$Id_Contenu=$rowAbs['Id_TypeAbsenceInitial'];
															$estUneVacation=0;
															$Couleur="style='background-color:".$rowAbs['CouleurIni'].";'";
															if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtatAbsence="absInjustifiee";$contenu="ABS";$Id_Contenu=0;$Couleur="style='background-color:#ff1111;'";}
														}
													}
													break;
												}
											}
										}
									}
									
									//Cong�s
									if($nbConges>0){
										mysqli_data_seek($resultConges,0);
										while($rowConges=mysqli_fetch_array($resultConges)){
											if($rowConges['DateDebut']<=$tmpDate && $rowConges['DateFin']>=$tmpDate){

												$jourFixe=estJour_Fixe($tmpDate,$row['Id']);
												$Id_Contrat =IdContrat($row['Id'],$tmpDate);
												$Id_Type=$rowConges['Id_TypeAbsenceInitial'];
												if($rowConges['Id_TypeAbsenceDefinitif']<>0){$Id_Type=$rowConges['Id_TypeAbsenceDefinitif'];}
												if($jourFixe<>"" && estCalendaire($Id_Type)==0 && Id_TypeContrat($Id_Contrat)<>18){
													$Couleur="style='background-color:".$Automatique.";'";
													$contenu=$jourFixe;
													$Id_Contenu=estJour_Fixe_Id($tmpDate,$row['Id']);
													$estUneVacation=0;
													break;
												}
												else{
													$IndiceAbs="";
													$NbHeureAbsJour=0;
													$NbHeureAbsNuit=0;
													if($rowConges['NbHeureAbsJour']<>0 || $rowConges['NbHeureAbsNuit']<>0){
														$NbHeureAbsJour=$rowConges['NbHeureAbsJour'];
														$NbHeureAbsNuit=$rowConges['NbHeureAbsNuit'];
														if($rowConges['TypeAbsenceDef']<>""){
															$IndiceAbs=$rowConges['TypeAbsenceDef']." ";
														}
														else{
															$IndiceAbs=$rowConges['TypeAbsenceIni']." ";
														}
													}
													else{
														if($rowConges['TypeAbsenceDef']<>""){
															$contenu=$rowConges['TypeAbsenceDef'];
															$Id_Contenu=$rowConges['Id_TypeAbsenceDefinitif'];
															$estUneVacation=0;
															$Couleur="style='background-color:".$rowConges['CouleurDef'].";'";
														}
														else{
															$contenu=$rowConges['TypeAbsenceIni'];
															$Id_Contenu=$rowConges['Id_TypeAbsenceInitial'];
															$estUneVacation=0;
															$Couleur="style='background-color:".$rowConges['CouleurIni'].";'";
														}
													}
													if($onClick==""){$onClick="onclick=\"javascript:OuvreFenetreModifConges(".$Menu.",".$rowConges['Id'].")\" ";}
													
													$bEtatConges="attenteValidation";
													if($rowConges['EtatN1']==-1 || $rowConges['EtatN2']==-1){$bEtatConges="refusee";}
													elseif($rowConges['EtatN2']==1 && $rowConges['EtatRH']==1){$bEtatConges="validee";}
													break;
												}
											}
										}
									}
									
									//Astreintes
									if($nbAst>0){
										mysqli_data_seek($resultAst,0);
										while($rowAst=mysqli_fetch_array($resultAst)){
											if($rowAst['DateAstreinte']==$tmpDate){
												$valAstreinte.=" AS";
												$nbHeures="0h ";
												if($rowAst['Intervention']==1){
													$nbHeures=Ajouter_Heures($rowAst['DiffHeures1'],$rowAst['DiffHeures2'],$rowAst['DiffHeures3']);
													$tabHeure=explode(".",$nbHeures);
													if(sizeof($tabHeure)==2){
														$valAstreinte.=" ".$tabHeure[0].".".round(($tabHeure[1]/60)*100,0);
													}
													else{
														$valAstreinte.=" ".$tabHeure[0];
													}
													
												}
												
												if(estSalarie($tmpDate,$row['Id'])==1){
													if($_SESSION['Langue']=="FR"){
														if($divers<>""){$divers.="<br>";}
														$divers.="Le ".AfficheDateJJ_MM($tmpDate).", astreinte avec ".$nbHeures." d'intervention = ".$rowAst['Montant']."&euro;";
													}
													else{
														if($divers<>""){$divers.="<br>";}
														$divers.="".AfficheDateJJ_MM($tmpDate).", on-call ".$nbHeures." of intervention = ".$rowAst['Montant']."&euro;";
													}
												}
												
												$bEtatAstreinte="attenteValidation";
												if($rowAst['Etat']==4){$bEtatAstreinte="refusee";}
												elseif($rowAst['Etat']==3 || $rowAst['Etat']==2){$bEtatAstreinte="validee";}
											}
										}
									}
									
									//HS
									if($nb2HS>0){
										mysqli_data_seek($resultHS,0);
										while($rowHS=mysqli_fetch_array($resultHS)){
											if($rowHS['DateHS']==$tmpDate){
												$nbHS+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
												$NbHeureSuppJour+=$rowHS['Nb_Heures_Jour'];
												$NbHeureSuppNuit+=$rowHS['Nb_Heures_Nuit'];
												if($indice<>""){$indice.="+";}
												if($_SESSION["Langue"]=="FR"){$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."HS";}
												else{$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."OT";}
												$bEtatHS="attenteValidation";
												if($rowHS['Etat']==4){$bEtatHS="refusee";}
												elseif($rowHS['Etat']==3){$bEtatHS="validee";}
											}
										}
									}
									
									//Horaires de la personne
									$HeureDebutTravail="00:00:00";
									$HeureFinTravail="00:00:00";
									if($_POST){
										$tab=HorairesJournee($row['Id'],$tmpDate);
										if(sizeof($tab)>0){
											$HeureDebutTravail=$tab[0];
											$HeureFinTravail=$tab[1];
										}
									}
									
									if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
										//Formation 
										if($nbSession>0){
											$bTrouve=0;
											mysqli_data_seek($resultSession,0);
											while($rowForm=mysqli_fetch_array($resultSession)){
												if($rowForm['DateSession']==$tmpDate){
													//Nombre total d'heure de formation
													$hF=strtotime($rowForm['Heure_Fin']);
													$hD=strtotime($rowForm['Heure_Debut']);
													$val=gmdate("H:i",$hF-$hD);
													$bTrouve=1;
													if($rowForm['PauseRepas']==1){
														$hFP=strtotime($rowForm['HeureFinPause']);
														$hDP=strtotime($rowForm['HeureDebutPause']);
														if($hDP<$hF && $hFP>$hD){
															if($hFP>$hF){$hFP=$hF;}
															if($hDP<$hD){$hDP=$hD;}
															$valPause=gmdate("H:i",$hFP-$hDP);
															$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
														}
													}
													
													$nbHeureFormation=date('H:i',strtotime($nbHeureFormation." ".str_replace(":"," hour ",$val)." minute"));

													//Nombre d'heure pendant la vacation 
													if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";}
													$hFTravail=strtotime($HeureFinTravail);
													$hDTravail=strtotime($HeureDebutTravail);
													if($hDTravail>$hD || $hFTravail<$hF){
														if($hFTravail<$hF){$hF=$hFTravail;}
														if($hDTravail>$hD){$hD=$hDTravail;}
													}
													$val=gmdate("H:i",$hF-$hD);
													
													if($hDTravail>$hF || $hFTravail<$hD){
														$hF=0;
														$hD=0;
														$val=0;
													}
													
													if($hD<>0 && $hF<>0){
														if($rowForm['PauseRepas']==1){
															$hFP=strtotime($rowForm['HeureFinPause']);
															$hDP=strtotime($rowForm['HeureDebutPause']);
															if($hDP<$hF && $hFP>$hD){
																if($hFP>$hF){$hFP=$hF;}
																if($hDP<$hD){$hDP=$hD;}
																$valPause=gmdate("H:i",$hFP-$hDP);
																$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
															}
														}
													}
									
													$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$val)." minute"));
	
												}
											}
											if($bTrouve==1){
												if($estUneVacation<>0){
													if($indice<>""){$indice.="+";}
													$indice.="FOR";
												}
												
											}
										}
									}
									//Si en attente validation alors rayer la case
									if($bEtatConges=="attenteValidation" || $bEtatAbsence=="attenteValidation" || $bEtatAstreinte=="attenteValidation"
										|| $bEtatHS=="attenteValidation"){$ClassDiv.=" EnAttenteValidation";}
									
									//r�cup�rer le jour de la semaine 
									$tabDate = explode('-', $tmpDate);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$jourSemaine = date('w', $timestamp);
									$tab=PointagePrestationVacation($PrestationSelect,$PoleSelect,$Id_Contenu,$jourSemaine,$tmpDate);
									$nbHeure=0;
									$nbHeureJ=0;
									$nbHeureEJ=0;
									$nbHeureEN=0;
									$nbHeurePause=0;
									$nbHeureFor=0;
									$nbHeureForm=intval(date('H',strtotime($nbHeureFormation." + 0 hour"))).".".substr((date('i',strtotime($nbHeureFormation." + 0 hour"))/0.6),0,2);
									//On ne compte pas les heures hors vacation
									$nbHeureForm=0;
									$lesminutes=substr(date('i',strtotime($nbHeureFormationVac." + 0 hour"))/0.6,0,2);
									if(substr($lesminutes,1,1)=="."){
										$lesminutes="0".substr($lesminutes,0,1);
									}
									$nbHeureFormVac=intval(date('H',strtotime($nbHeureFormationVac." + 0 hour"))).".".$lesminutes;
									
									$nbHeureFormPlus=0;
									if(estInterim($tmpDate,$row['Id'])){
										if($nbHeureFormVac==7){$nbHeureFormPlus=1;}
									}
									
									$info="";
									if($estUneVacation<>0){
										if(sizeof($tab)>0){
											$nbHeure=$tab[0]+$tab[1]+$tab[2]+$tab[4];
											$nbHeureJ=$tab[0];
											$nbHeureEJ=$tab[1];
											$nbHeureEN=$tab[2];
											$nbHeurePause=$tab[3];
											$nbHeureFor=$tab[4];
										}
										
										$tabContrat=PointagePersonneContrat($tmpDate,$row['Id'],$Id_Contenu,$jourSemaine);
										if(sizeof($tabContrat)>0){
											$nbHeure=$tabContrat[0]+$tabContrat[1]+$tabContrat[2];
											$nbHeureJ=$tabContrat[0];
											$nbHeureEJ=$tabContrat[1];
											$nbHeureEN=$tabContrat[2];
											$nbHeurePause=$tabContrat[3];
											$nbHeureFor=0;
											
											if($Id_Contenu==6){
												$nbHeureFor=$nbHeureJ;
												$nbHeureJ=0;
											}
										}
									}
									
									//Ajout des heures supp 
									if($nbHeureEJ>0){
										$nbHeureEJ=$nbHeureEJ+$NbHeureSuppJour;
									}
									else{
										$nbHeureJ=$nbHeureJ+$NbHeureSuppJour;
									}
									$nbHeureEN=$nbHeureEN+$NbHeureSuppNuit;
									$nbHeure=$nbHeure+$NbHeureSuppJour+$NbHeureSuppNuit;
									
									if($nbHeureFormVac>0){
										$nbHeureJ=$nbHeureJ-$nbHeureFormVac-$nbHeureFormPlus;
										$nbHeure=$nbHeure-$nbHeureFormVac-$nbHeureFormPlus;
										$nbHeure=$nbHeure." + ".$nbHeureForm." FOR";
									
										if($nbHeureJ<0){
											if($nbHeureEJ>0){
												$nbHeureEJ=$nbHeureEJ+$nbHeureJ;
											}
											if($nbHeureEJ<0){
												$nbHeureEJ=0;
											}
											$nbHeureJ=0;
										}
									}
									
									if($NbHeureAbsJour>0){
										$nbHeureJ=$nbHeureJ-$NbHeureAbsJour;
										if($nbHeureJ<0){
											if($nbHeureEJ>=0){
												$nbHeureEJ=$nbHeureEJ+$nbHeureJ;
											}
											
											if($nbHeureEJ<=0){
												if($nbHeureFormVac>0){
													$nbHeureFormVac=$nbHeureFormVac+$nbHeureEJ;
													
												}
												if($nbHeureFormVac<0){
													$nbHeureFormVac=0;
												}
												$nbHeureEJ=0;
											}
											$nbHeureJ=0;
										}
									}
									
									if($NbHeureAbsNuit>0){
										$nbHeure=$nbHeure-$NbHeureAbsNuit;
										$nbHeureEN=$nbHeureEN-$NbHeureAbsNuit;
										if($nbHeureEN<0){

											$nbHeureEN=0;
										}
									}
									
									$nbHeureForm=$nbHeureForm+$nbHeureFor+$nbHeureFormVac;
									$nbHeure=$nbHeureJ+$nbHeureEJ+$nbHeureEN+$nbHeureForm;
									
									
									$info="";
									if($_SESSION['Langue']=="FR"){
										$info.="Personne : ".$row['Personne']."<br>";
										$info.="Date : ".AfficheDateJJ_MM_AAAA($tmpDate)."<br><br>";
										$info.="<table>";
										$info.="<tr><td>J </td><td>".$nbHeureJ."</td></tr>";
										$info.="<tr><td>FOR </td><td>".$nbHeureForm."</td></tr>";
										$info.="<tr><td>EJ </td><td>".$nbHeureEJ."</td></tr>";
										$info.="<tr><td>EN </td><td>".$nbHeureEN."</td></tr>";
										$info.="<tr><td>Pause </td><td>".$nbHeurePause."</td></tr>";
										$info.="</table><br>";
										$info.="Divers : ".stripslashes($divers)."<br><br>";
										$info.="Commentaire : ".stripslashes($commentaire)."";
									}
									else{
										$info.="Person : ".$row['Personne']."<br>";
										$info.="Date : ".AfficheDateJJ_MM_AAAA($tmpDate)."<br><br>";
										$info.="<table>";
										$info.="<table>";
										$info.="<tr><td>D </td><td>".$nbHeureJ."</td></tr>";
										$info.="<tr><td>TRAINING </td><td>".$nbHeureForm."</td></tr>";
										$info.="<tr><td>TD </td><td>".$nbHeureEJ."</td></tr>";
										$info.="<tr><td>TN </td><td>".$nbHeureEN."</td></tr>";
										$info.="<tr><td>Break </td><td>".$nbHeurePause."</td></tr>";
										$info.="</table><br>";
										$info.="Miscellaneous : ".stripslashes($divers)."<br><br>";
										$info.="Commentaire : ".stripslashes($commentaire)."";
									}
									
									if($nbHeure<0){
										$nbHeure=0;
									}
									if($estUneVacation==0){
										if($contenu<>""){
											$nbHeure=$contenu;
										}
										else{
											if($nbHeure==0){$nbHeure=$contenu;}
										}
									}
									else{
										if($onClick==""){$onClick="onclick=\"javascript:OuvreFenetreModifPlanning(".$Menu.",".$row['Id'].",'".$tmpDate."')\" ";}
									}
									
									//Cellule finale
									echo "<td id='leHover' class='".$ClassDiv."' ".$Couleur." align='center'>
											<div class='planning' style=\"cursor:pointer;\">".$contenu.$valAstreinte."<sup>".$IndiceAbs.$indice."</sup></div>
											<div class='pointage' style='display:none;'>".$nbHeure.$valAstreinte."</div>
											<span>".$info."</span>
										</td>\n";
								}
								else{
									//Cellule finale
									echo "<td id='leHover' class='".$ClassDiv."' align='center'>
											<div class='planning'></div>
											<div class='pointage' style='display:none;'></div>
											<span></span>
										</td>\n";
								}
								//Jour suivant
								$tabDate = explode('-', $tmpDate);
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
								$tmpDate = date("Y-m-d", $timestamp);
							}
							//Pointage individuel
							echo "<td>";

							echo "</td>";
				
							echo "</tr>";
						}
					 }
					?>
				</table>
			</td>
		</tr>
		<tr>
		<td height="10"></td>
	</tr>
	</table>
</form>
</body>
</html>