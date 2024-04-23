<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Contrat.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heure').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
		});
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		if($_SESSION['Id_Personne']<>""){
			$Personne="";
			if(isset($_POST['PersonneSelect']))
			{
				$PersonneSelect = $_POST['PersonneSelect'];
				for($i=0;$i<sizeof($PersonneSelect);$i++)
				{
					if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
				}
			}
			$TabPersonne = preg_split("/[;]+/", $Personne);
			for($i=0;$i<sizeof($TabPersonne)-1;$i++){
				$Id_ContratEC= IdContratEC($TabPersonne[$i]);
				$Id_PersonneContrat=0;
				if($Id_ContratEC>0){
					$req="SELECT Id, Id_ContratInitial FROM rh_personne_contrat WHERE Id=".$Id_ContratEC." ";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						$row=mysqli_fetch_array($result);
						if($row['Id_ContratInitial']>0){$Id_PersonneContrat=$row['Id_ContratInitial'];}
						else{$Id_PersonneContrat=$row['Id'];}
					}
				}
				
				if($Id_ContratEC>0){
					$req="INSERT INTO rh_personne_contrat (Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,Id_ClassificationMetier,Niveau,Coeff,Echelon,Cotation,Id_FicheEmploi,SMHReference,
							SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,
							TauxHoraire,DateDebut,DateFin,DateFinPeriodeEssai,Id_TempsTravail,Id_LieuTravail,Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,
							DateSouplessePositive,DateSouplesseNegative,Remarque,Id_Client,Titre) 
						SELECT ".$Id_PersonneContrat.",Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,Id_ClassificationMetier,Niveau,Coeff,Echelon,Cotation,Id_FicheEmploi,SMHReference,
							SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,
							TauxHoraire,'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',DateFinPeriodeEssai,".$_POST['tempsTravail'].",Id_LieuTravail,Id_Prestation,Id_Pole,'Avenant',DateCreation,Id_Createur,
							DateSouplessePositive,DateSouplesseNegative,Remarque,Id_Client,Titre
						FROM rh_personne_contrat
						WHERE Id=".$Id_ContratEC."
					";
					$resultAjout=mysqli_query($bdd,$req);
					$IdCree = mysqli_insert_id($bdd);
					
					//Ajout des temps partiels 
					$requeteInsert="INSERT INTO rh_personne_contrat_tempspartiel 
									(Id_Personne_Contrat, Id_Vacation, NbHeureJour, NbHeureEJ, NbHeureEN,NbHeurePause,JourSemaine,HeureDebut,HeureFin,Teletravail) 
									VALUES";
					$NbCompteVacation=0;
					
					$resultVacation=mysqli_query($bdd,"SELECT Id FROM rh_vacation WHERE Suppr=0 ");
					$NbLigneVacation=mysqli_num_rows($resultVacation);
					while($rowVacation=mysqli_fetch_array($resultVacation)){
						$NbCompteVacation+=1;
						$NbCompteJour=-1;
						$NbHeureJour = 0;
						$NbHeureEquipeJour = 0;
						$NbHeureEquipeNuit = 0;
						$NbHeurePause = 0;
						$heureDebut='00:00:00';
						$heureFin='00:00:00';
						$teletravail=0;
						while($NbCompteJour<6)
						{
							$NbCompteJour+=1;
							$NbHeureJour = 0;
							$NbHeureEquipeJour = 0;
							$NbHeureEquipeNuit = 0;
							$NbHeurePause = 0;
							$heureDebut='00:00:00';
							$heureFin='00:00:00';
							$teletravail=0;
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_J']<>""){$NbHeureJour = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_J'];}
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EJ']<>""){$NbHeureEquipeJour = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EJ'];}
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EN']<>""){$NbHeureEquipeNuit = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EN'];}
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_P']<>""){$NbHeurePause = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_P'];}
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureDebut']<>""){$heureDebut = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureDebut'];}
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureFin']<>""){$heureFin = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureFin'];}
							if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_Teletravail']<>""){$teletravail = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_Teletravail'];}
							
							$requeteInsert.=" (".$IdCree.",".$rowVacation['Id'].",".$NbHeureJour.",".$NbHeureEquipeJour.",
											".$NbHeureEquipeNuit.",".$NbHeurePause.",".$NbCompteJour.",'".$heureDebut."','".$heureFin."',".$teletravail.")";
							if($NbCompteJour<=6 && $NbCompteVacation<=$NbLigneVacation ){$requeteInsert.=",";}
						}
					}
					$requeteInsert =  substr($requeteInsert, 0, -1).";" ;
					$resultInsert=mysqli_query($bdd,$requeteInsert);
					
					//Mettre une date de fin 
					if(TrsfDate_($_POST['dateDebut'])<>'000-00-00'){
						$req="UPDATE rh_personne_contrat 
							SET DateFin='".date('Y-m-d',strtotime(TrsfDate_($_POST['dateDebut'])." - 1 day"))."'
							WHERE 
							DateFin<='0001-01-01'
							AND Id<>0
							AND TypeDocument<>'ODM'
							AND Id<>".$IdCree."
							AND Id_Personne=".$TabPersonne[$i]."
							AND (Id_ContratInitial=".$Id_PersonneContrat." OR Id=".$Id_PersonneContrat.") ";
						$result=mysqli_query($bdd,$req);
					}
					echo "<script>FermerEtRecharger('".$Menu."','".$_POST['Id_Personne2']."','".$_POST['Page']."')</script>";
				}
			}
		}
	}
}

$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";
?>

<form id="formulaire" class="test" action="Ajout_ContratAvenantTT.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id_Personne2" id="Id_Personne2" value="<?php echo $_GET['Id_Personne']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
	<input type="hidden" name="Mode" id="Mode" value="A" />
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage" style="background-color:#a988b2;">
					<?php 
						if($_SESSION["Langue"]=="FR"){echo "Nouvel avenant";}else{echo "New amendment";}
					?>
					</td>
					<td width="4"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="90%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes ayant un contrat en cours:";}else{echo "People with a current contract :";} ?></td>
							<td width="35%" valign="top">
								<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
								<?php
								$rq="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND DateDebut<='".date('Y-m-d')."'
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									ORDER BY Personne ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									echo "<option value='".$rowpersonne['Id_Personne']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
								}
								?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées (double-clic) :";}else{echo "Selected people (double-click) :";} ?></td>
							<td width="30%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();"></select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début : ";}else{echo "Start date : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input type="date" style="text-align:center;" id="dateDebut" name="dateDebut" size="10" value="<?php echo AfficheDateFR($rowContrat['DateDebut']); ?>">
							</td>
							<td width="10%" class="Libelle" id="LibelleDateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" id="dateFin" name="dateFin" size="10" value="<?php echo AfficheDateFR($rowContrat['DateFin']); ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleTempsTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail : ";}else{echo "Work time : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="tempsTravail" id="tempsTravail" style="width:150px">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM rh_tempstravail
									WHERE Suppr=0
									ORDER BY Libelle ASC";

								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_TempsTravail']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" class="Libelle">
								<?php if($_SESSION["Langue"]=="FR"){echo "A compléter uniquement si temps partiel ou télétravail";}else{echo "To be completed only if part-time or telecommuting";} ?>
							</td>
						</tr>
						<tr>
							<td colspan="10">
							<table width="90%" align="center">
								<tr>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Vacation";}else{echo "Session";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Jour semaine";}else{echo "Day week";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "J";}else{echo "D";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "EJ";}else{echo "DT";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "EN";}else{echo "NT";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pause";}else{echo "Break";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Télétravail";}else{echo "Telecommuting";} ?></td>
								</tr>
							</table>
							<div id='div_TP' style='height:200px;width:100%;overflow:auto;' >
								<table width="90%" align="center">
									<?php
									$req="SELECT Id,Nom FROM rh_vacation WHERE Suppr=0 ORDER BY Nom";

									$resultVac=mysqli_query($bdd,$req);
									$nbenreg=mysqli_num_rows($resultVac);
									if($nbenreg>0)
									{
										$Couleur="#EEEEEE";
										while($rowVac=mysqli_fetch_array($resultVac))
										{
											?>
												<tr bgcolor="<?php echo $Couleur;?>">
													<td width="11%" rowspan=8 width=20 style="text-align:center;"><?php echo $rowVac['Nom'];?></td>
												</tr>
												<?php
												$tabJour=array(1,2,3,4,5,6,0);
												foreach($tabJour as $i){
												?>
													<tr>
														<td class="EnTeteTableauCompetences" width="10%">
														<?php
															switch ($i) {
																case 1:
																	if($_SESSION["Langue"]=="FR"){echo "Lundi";}else{echo "Monday";}
																	break;
																case 2:
																	if($_SESSION["Langue"]=="FR"){echo "Mardi";}else{echo "Tuesday";}
																	break;
																case 3:
																	if($_SESSION["Langue"]=="FR"){echo "Mercredi";}else{echo "Wednesday";}
																	break;
																case 4:
																	if($_SESSION["Langue"]=="FR"){echo "Jeudi";}else{echo "Thursday";}
																	break;
																case 5:
																	if($_SESSION["Langue"]=="FR"){echo "Vendredi";}else{echo "Friday";}
																	break;
																case 6:
																	if($_SESSION["Langue"]=="FR"){echo "Samedi";}else{echo "Saturday";}
																	break;
																case 0:
																	if($_SESSION["Langue"]=="FR"){echo "Dimanche";}else{echo "Sunday";}
																	break;
															}
														?>
														</td>
														<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $rowVac['Id']."_".$i."_J"; ?> size="5" type="text" value=""></td>
														<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_EJ"; ?> size="5" type="text" value=""></td>
														<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_EN"; ?> size="5" type="text" value=""></td>
														<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_P"; ?> size="5" type="text" value=""></td>
														<td align="center" width="10%"><input class="heure" name=<?php echo $rowVac['Id']."_".$i."_HeureDebut"; ?> size="8" value=""></td>
														<td align="center" width="10%"><input class="heure"name=<?php echo $rowVac['Id']."_".$i."_HeureFin"; ?> size="8" value=""></td>
														<td align="center" width="10%">
															<select name=<?php echo $rowVac['Id']."_".$i."_Teletravail"; ?> style="width:60px;">
																<option value="0"><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
																<option value="1"><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
															</select>
														</td>
													</tr>
												<?php
												$i = $i + 1;
												} ?>
												<tr height='1' bgcolor='#66AACC'><td colspan='9'></td></tr>
											</tr>
											<?php
										}
									}
									?>
								</table>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td colspan="7" align="center" class="Libelle">
						<?php 
						if($_SESSION["Langue"]=="FR"){echo "Les autres informations seront reprises du contrat en cours de la personne";}
						else{echo "The other information will be taken from the current contract of the person";} 
						?>
						</td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir enregistrer ?";}else{echo "Are you sure you want to save ?";} ?>')){selectall();}else{return false;}">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
}
?>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>