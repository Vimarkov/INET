<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
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
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">		
		function FermerEtRecharger(Id_PrestationPole)
		{
			window.opener.location="Liste_PrestationVacationHistorique.php?Menu=6&Id_PrestationPole="+Id_PrestationPole;
			window.close();
		}
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.dateDebut.value==''){alert('Vous n\'avez pas renseigné la date de début de prise en compte.');return false;}
				return true;
			}
			else{
				if(formulaire.dateDebut.value==''){alert('You have not entered the start date of consideration.');return false;}
				return true;
			}
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heures').timepicker({
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
<body>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

if($_POST)
{	
	$tab=explode("_",$_POST['Id_PrestationPole']);
	$Id_Prestation=$tab[0];
	$Id_Pole=$tab[1];
	
	//Suppression des infos existante aux dates renseignées
	$req="UPDATE rh_prestation_vacation
		SET Suppr=1,
		Id_Suppr=".$_SESSION['Id_Personne'].",
		DateSuppr='".date('Y-m-d')."' 
		WHERE Id_Prestation=".$Id_Prestation." 
		AND Id_Pole=".$Id_Pole."
		AND Suppr=0
		AND DateDebut='".$_POST['DateDebutGET']."'
		AND DateFin='".$_POST['DateFinGET']."'";
	$resultSupp=mysqli_query($bdd,$req);
	
	//Ajout des infos
	$resultVacation=mysqli_query($bdd,"SELECT Id FROM rh_vacation WHERE Suppr=0");
	$NbLigneVacation=mysqli_num_rows($resultVacation);
	
	$requeteInsert="INSERT INTO rh_prestation_vacation (Id_Prestation,Id_Pole, Id_Vacation, NbHeureJ, NbHeureEJ, NbHeureEN,NbHeurePause,NbHeureFOR,JourSemaine,HeureDebut,HeureFin,DateDebut,DateFin,AfficherZero)";
	$requeteInsert.=" VALUES";
	$NbCompteVacation=0;
	$QueDesChiffres=0;
	while($rowVacation=mysqli_fetch_array($resultVacation))
	{
		$NbCompteVacation+=1;
		$NbCompteJour=-1;
		$NbHeureJour = 0;
		$NbHeureEquipeJour = 0;
		$NbHeureEquipeNuit = 0;
		$NbHeurePause = 0;
		$NbHeureFor=0;
		$HeureDebut='00:00:00';
		$HeureFin='00:00:00';
		$AfficherZero=0;
		while($NbCompteJour<6)
		{
			$NbCompteJour+=1;
			$NbHeureJour = 0;
			$NbHeureEquipeJour = 0;
			$NbHeureEquipeNuit = 0;
			$NbHeurePause = 0;
			$NbHeureFor=0;
			$HeureDebut='00:00:00';
			$HeureFin='00:00:00';
			$AfficherZero=0;
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_J']<>''){$NbHeureJour = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_J'];}
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EJ']<>''){$NbHeureEquipeJour = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EJ'];}
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EN']<>''){$NbHeureEquipeNuit = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EN'];}
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_P']<>''){$NbHeurePause = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_P'];}
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_FOR']<>''){$NbHeureFor = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_FOR'];}
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HD']<>''){$HeureDebut=$_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HD'];}
			if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HF']<>''){$HeureFin=$_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HF'];}
			if(isset($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_check'])){$AfficherZero=1;}
			
			$requeteInsert.=" (".$Id_Prestation.",".$Id_Pole.",".$rowVacation['Id'].",".$NbHeureJour.",".$NbHeureEquipeJour.",";
			$requeteInsert.= "".$NbHeureEquipeNuit.",".$NbHeurePause.",".$NbHeureFor.",".$NbCompteJour.",'".$HeureDebut."','".$HeureFin."','".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',".$AfficherZero.")";
			if($NbCompteJour<=6 && $NbCompteVacation<=$NbLigneVacation ){$requeteInsert.=",";}
		}
	}
	$requeteInsert =  substr($requeteInsert, 0, -1).";" ;	
	$resultInsert=mysqli_query($bdd,$requeteInsert);
	
	echo "<script>FermerEtRecharger('".$_POST['Id_PrestationPole']."');</script>";
}
elseif($_GET)
{
	
	$tab=explode("_",$_GET['Id_PrestationPole']);
	$Id_Prestation=$tab[0];
	$Id_Pole=$tab[1];
	$DateDebut=$_GET['DateDebut'];
	$DateFin=$_GET['DateFin'];
	$Mode=$_GET['Mode'];
	if($Mode=="S"){
		$req="UPDATE rh_prestation_vacation
			SET Suppr=1,
			Id_Suppr=".$_SESSION['Id_Personne'].",
			DateSuppr='".date('Y-m-d')."' 
			WHERE Id_Prestation=".$Id_Prestation." 
			AND Id_Pole=".$Id_Pole."
			AND Suppr=0
			AND DateDebut='".$DateDebut."'
			AND DateFin='".$DateFin."'";
		$resultSupp=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger('".$_GET['Id_PrestationPole']."');</script>";
	}
?>
<form id="formulaire" method="POST" action="Modifier_VacationPrestation.php" onSubmit="return VerifChamps();">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Id_PrestationPole" id="Id_PrestationPole" value="<?php echo $_GET['Id_PrestationPole']; ?>" />
	<input type="hidden" name="DateDebutGET" id="DateDebutGET" value="<?php echo $_GET['DateDebut']; ?>" />
	<input type="hidden" name="DateFinGET" id="DateFinGET" value="<?php echo $_GET['DateFin']; ?>" />
	<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>" />
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="TitreSousPagePlanning" width="4"></td>
						<?php
						$resultPrestation=mysqli_query($bdd,"SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation." ");
						$rowPrestation=mysqli_fetch_array($resultPrestation);
						
						$Pole="";
						if($Id_Pole>0){
							$resultPole=mysqli_query($bdd,"SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole." ");
							$rowPole=mysqli_fetch_array($resultPole);
							$Pole=" - ".$rowPole['Libelle'];
						}
						?>
						<td class="TitrePage">Vacations - <?php echo $rowPrestation['Libelle'].$Pole; ?></td>
					</tr>
					
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td align="center" width="10"><input class="Bouton" type="submit" value='<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?>'></td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début de prise en compte :";}else{echo "Start date of consideration :";} ?> </td>
						<td width="10%"><input type="date" style="text-align:center;" id="dateDebut" name="dateDebut" size="10" value="<?php echo AfficheDateFR($DateDebut);?>"></td>
						<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin de prise en compte :";}else{echo "End date of taking into account :";} ?> </td>
						<td width="10%"><input type="date" style="text-align:center;" id="dateFin" name="dateFin" size="10" value="<?php echo AfficheDateFR($DateFin);?>"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="10"></td>
						<td>
							<table class="TableCompetences" width="350">
							<?php
								$result=mysqli_query($bdd,"SELECT Id, Nom FROM rh_vacation WHERE Suppr=0 ORDER BY Nom ASC");
								$nbenreg=mysqli_num_rows($result);
								if($nbenreg>0)
								{
							?>
									<tr>
										<td width=100 class="EnTetePlanning">Vacation</td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Jour semaine";}else{echo "Day week";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "J";}else{echo "D";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "EJ";}else{echo "DT";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "EN";}else{echo "NT";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Pause";}else{echo "Break";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "FOR";}else{echo "TRA";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";}?></td>
										<td width=100 class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Afficher le zéro";}else{echo "Show zero";}?></td>
									</tr>
									<?php
									$Couleur="#EEEEEE";
									while($row=mysqli_fetch_array($result))
									{
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td rowspan=8 width=20 style="text-align:center;"><?php echo $row['Nom'];?></td>
											</tr>
											<?php
											$i=1;
											while($i<=6)
											{
											?>
												<tr>
													<td class="EnTeteTableauCompetences">
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
													<?php
													$requete = "SELECT NbHeureJ,NbHeureEJ,NbHeureEN,NbHeurePause,HeureDebut,HeureFin,NbHeureFOR,AfficherZero,
																NbHeureJ+NbHeureEJ+NbHeureEN+NbHeurePause+NbHeureFOR AS NbTotal
																FROM rh_prestation_vacation 
																WHERE Id_Prestation=".$Id_Prestation." 
																AND Id_Pole=".$Id_Pole."
																AND Suppr=0
																AND DateDebut='".$DateDebut."'
																AND DateFin='".$DateFin."'
																AND JourSemaine=".$i." 
																AND ID_Vacation=".$row['Id']."";
													$resultPrestationVacation=mysqli_query($bdd,$requete);
													$nbenregPrestationVacation=mysqli_num_rows($resultPrestationVacation);
													if($nbenregPrestationVacation>0)
													{
													$rowVacation=mysqli_fetch_array($resultPrestationVacation)
													?>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $row['Id']."_".$i."_J"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureJ']>0){echo $rowVacation['NbHeureJ'];} ?>"></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EJ"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureEJ']>0){echo $rowVacation['NbHeureEJ'];} ?>"></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EN"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureEN']>0){echo $rowVacation['NbHeureEN'];} ?>"></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_P"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeurePause']>0){echo $rowVacation['NbHeurePause'];} ?>"></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_FOR"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureFOR']>0){echo $rowVacation['NbHeureFOR'];} ?>"></td>
														<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HD"; ?> name=<?php echo $row['Id']."_".$i."_HD"; ?> size="10" type="text" value="<?php if($rowVacation['HeureDebut']<>'00:00:00' || $rowVacation['HeureFin']<>'00:00:00'){echo $rowVacation['HeureDebut'];} ?>"></div></td>
														<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HF"; ?> name=<?php echo $row['Id']."_".$i."_HF"; ?> size="10" type="text" value="<?php if($rowVacation['HeureDebut']<>'00:00:00' || $rowVacation['HeureFin']<>'00:00:00'){echo $rowVacation['HeureFin'];} ?>"></div></td>
														<td width=30><input name="<?php echo $row['Id']."_".$i."_check"; ?>" size="10" type="checkbox" <?php if($rowVacation['AfficherZero']>0){echo "checked";} ?> ></td>

													<?php
													}
													else
													{
													?>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $row['Id']."_".$i."_J"; ?> size="10" type="text" value=""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EJ"; ?> size="10" type="text" value= ""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EN"; ?> size="10" type="text" value= ""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_P"; ?> size="10" type="text" value= ""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_FOR"; ?> size="10" type="text" value= ""></td>
														<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HD"; ?> name=<?php echo $row['Id']."_".$i."_HD"; ?> size="10" type="text" value=""></div></td>
														<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HF"; ?> name=<?php echo $row['Id']."_".$i."_HF"; ?> size="10" type="text" value=""></div></td>
														<td width=30><input name="<?php echo $row['Id']."_".$i."_check"; ?>" size="10" type="checkbox" ></td>
													<?php
													}
													?>
												</tr>
											<?php
											$i = $i + 1;
											} 
											$i = 0;
											?>
											<tr>
												<td class="EnTeteTableauCompetences"><?php if($_SESSION["Langue"]=="FR"){echo "Dimanche";}else{echo "Sunday";}?></td>
												<?php
												$requete = "SELECT NbHeureJ,NbHeureEJ,NbHeureEN,NbHeurePause,HeureDebut,HeureFin,NbHeureFOR,AfficherZero 
																FROM rh_prestation_vacation 
																WHERE Id_Prestation=".$Id_Prestation." 
																AND Id_Pole=".$Id_Pole."
																AND Suppr=0
																AND DateDebut='".$DateDebut."'
																AND DateFin='".$DateFin."'
																AND JourSemaine=".$i." 
																AND ID_Vacation=".$row['Id']."";
												$resultPrestationVacation=mysqli_query($bdd,$requete);
												$nbenregPrestationVacation=mysqli_num_rows($resultPrestationVacation);
												if($nbenregPrestationVacation>0)
												{
												$rowVacation=mysqli_fetch_array($resultPrestationVacation);
												?>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $row['Id']."_".$i."_J"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureJ']>0){echo $rowVacation['NbHeureJ'];} ?>"></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EJ"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureEJ']>0){echo $rowVacation['NbHeureEJ'];} ?>"></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EN"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureEN']>0){echo $rowVacation['NbHeureEN'];} ?>"></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_P"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeurePause']>0){echo $rowVacation['NbHeurePause'];} ?>"></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_FOR"; ?> size="10" type="text" value="<?php if($rowVacation['NbHeureFOR']>0){echo $rowVacation['NbHeureFOR'];} ?>"></td>
													<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HD"; ?> name=<?php echo $row['Id']."_".$i."_HD"; ?> size="10" type="text" value="<?php if($rowVacation['HeureDebut']<>'00:00:00' || $rowVacation['HeureFin']<>'00:00:00'){echo $rowVacation['HeureDebut'];} ?>"></div></td>
													<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HF"; ?> name=<?php echo $row['Id']."_".$i."_HF"; ?> size="10" type="text" value="<?php if($rowVacation['HeureDebut']<>'00:00:00' || $rowVacation['HeureFin']<>'00:00:00'){echo $rowVacation['HeureFin'];} ?>"></div></td>
													<td width=30><input name="<?php echo $row['Id']."_".$i."_check"; ?>" size="10" type="checkbox" <?php if($rowVacation['AfficherZero']>0){echo "checked";} ?> ></td>
												<?php
												}
												else
												{
												?>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $row['Id']."_".$i."_J"; ?> size="10" type="text" value=""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EJ"; ?> size="10" type="text" value= ""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_EN"; ?> size="10" type="text" value= ""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_P"; ?> size="10" type="text" value= ""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $row['Id']."_".$i."_FOR"; ?> size="10" type="text" value= ""></td>
													<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HD"; ?> name=<?php echo $row['Id']."_".$i."_HD"; ?> size="10" type="text" value=""></div></td>
													<td width=30><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heures" style="text-align:center;" id=<?php echo $row['Id']."_".$i."_HF"; ?> name=<?php echo $row['Id']."_".$i."_HF"; ?> size="10" type="text" value=""></div></td>
													<td width=30><input name="<?php echo $row['Id']."_".$i."_check"; ?>" size="10" type="checkbox" ></td>
												<?php
												}
												?>
											</tr>
											<tr height='1' bgcolor='#66AACC'><td colspan='9'></td></tr>
										</tr>
										<?php
									}
								}
								mysqli_free_result($result);	// Libération des résultats
								?>
							</table>
						</td>
						
					</tr>
				</table>
			</tr>
		</td>
	</table>
</form>
<?php
}
?>
</body>
</html>