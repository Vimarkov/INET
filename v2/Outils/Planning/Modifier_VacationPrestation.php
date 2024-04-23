<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function FermerEtRecharger()
		{
			//opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>
<?php
	session_start();
	require("../Connexioni.php");
	
if($_POST)
{	
	$resultVacation=mysqli_query($bdd,"SELECT new_planning_vacationabsence.Id FROM new_planning_vacationabsence WHERE new_planning_vacationabsence.AbsenceVacation=1");
	$NbLigneVacation=mysqli_num_rows($resultVacation);
	
	
	
	$requeteInsert="INSERT INTO new_planning_prestation_vacation (ID_Prestation, ID_Vacation, NbHeureJour, NbHeureEquipeJour, NbHeureEquipeNuit,NbHeurePause,JourSemaine)";
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
		while($NbCompteJour<6)
		{
			$NbCompteJour+=1;
			$NbHeureJour = 0;
			$NbHeureEquipeJour = 0;
			$NbHeureEquipeNuit = 0;
			$NbHeurePause = 0;
			if(!empty($_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_J'])){$NbHeureJour = $_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_J'];}
			if(!empty($_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_EJ'])){$NbHeureEquipeJour = $_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_EJ'];}
			if(!empty($_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_EN'])){$NbHeureEquipeNuit = $_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_EN'];}
			if(!empty($_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_P'])){$NbHeurePause = $_POST[''.$rowVacation[0].'_'.$NbCompteJour.'_P'];}
			
			$requeteInsert.=" (".$_POST['Id_Prestation'].",".$rowVacation[0].",".$NbHeureJour.",".$NbHeureEquipeJour.",";
			$requeteInsert.= "".$NbHeureEquipeNuit.",".$NbHeurePause.",".$NbCompteJour.")";
			if($NbCompteJour<=6 && $NbCompteVacation<=$NbLigneVacation ){$requeteInsert.=",";}
		}
	}
	$requeteInsert =  substr($requeteInsert, 0, -1).";" ;

	$requeteSupp="DELETE FROM new_planning_prestation_vacation WHERE ID_Prestation=".$_POST['Id_Prestation'];
	$resultSupp=mysqli_query($bdd,$requeteSupp);
	
	$resultInsert=mysqli_query($bdd,$requeteInsert);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>
<form id="formulaire" method="POST" action="Modifier_VacationPrestation.php">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="TitreSousPagePlanning" width="4"></td>
						<?php
						$resultPrestation=mysqli_query($bdd,"SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE Id=".$_GET['Id_Prestation']." ");
						$rowPrestation=mysqli_fetch_array($resultPrestation);
						?>
						<td class="TitrePage">Vacations - <?php echo $rowPrestation['Libelle'] ?></td>
						<input type="hidden" name="Id_Prestation" value="<?php echo $_GET['Id_Prestation'];?>">
					</tr>
					
				</table>
			</td>
		</tr>
		<tr>
		<td align="center" width="10"><input class="Bouton" type="submit" value='Enregistrer'></td>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td width="10"></td>
						<td>
							<table class="TableCompetences" width="350">
							<?php
								$result=mysqli_query($bdd,"SELECT new_planning_vacationabsence.Id, new_planning_vacationabsence.Nom FROM new_planning_vacationabsence WHERE new_planning_vacationabsence.AbsenceVacation=1 ORDER BY new_planning_vacationabsence.Nom ASC");
								$nbenreg=mysqli_num_rows($result);
								if($nbenreg>0)
								{
							?>
									<tr>
										<td width=100 class="EnTetePlanning">Vacation</td>
										<td width=100 class="EnTetePlanning">Jour semaine</td>
										<td width=100 class="EnTetePlanning">J</td>
										<td width=100 class="EnTetePlanning">EJ</td>
										<td width=100 class="EnTetePlanning">EN</td>
										<td width=100 class="EnTetePlanning">Pause</td>
									</tr>
									<?php
									$Couleur="#EEEEEE";
									$Vacation=0;
									$idVacation=0;
									while($row=mysqli_fetch_array($result))
									{
										$Vacation=$row['1'];
										$idVacation=$row['0'];
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td rowspan=8 width=20 style="text-align:center;"><?php echo $Vacation;?></td>
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
																echo "Lundi";
																break;
															case 2:
																echo "Mardi";
																break;
															case 3:
																echo "Mercredi";
																break;
															case 4:
																echo "Jeudi";
																break;
															case 5:
																echo "Vendredi";
																break;
															case 6:
																echo "Samedi";
																break;
															case 0:
																echo "Dimanche";
																break;
														}
													?>
													</td>
													<?php
													$requete = "SELECT new_planning_prestation_vacation.NbHeureJour, new_planning_prestation_vacation.NbHeureEquipeJour, new_planning_prestation_vacation.NbHeureEquipeNuit, ";
													$requete .= "new_planning_prestation_vacation.NbHeurePause FROM new_planning_prestation_vacation ";
													$requete .= "WHERE new_planning_prestation_vacation.Id_Prestation=".$_GET['Id_Prestation']." AND ";
													$requete .= "new_planning_prestation_vacation.JourSemaine=".$i." AND new_planning_prestation_vacation.ID_Vacation=".$idVacation."";
													$resultPrestationVacation=mysqli_query($bdd,$requete);
													$nbenregPrestationVacation=mysqli_num_rows($resultPrestationVacation);
													if($nbenregPrestationVacation>0)
													{
													$rowVacation=mysqli_fetch_array($resultPrestationVacation)
													?>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $idVacation."_".$i."_J"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeureJour']; ?> "></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EJ"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeureEquipeJour']; ?> "></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EN"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeureEquipeNuit']; ?> "></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_P"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeurePause']; ?> "></td>

													<?php
													}
													else
													{
													?>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $idVacation."_".$i."_J"; ?> size="10" type="text" value=""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EJ"; ?> size="10" type="text" value= ""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EN"; ?> size="10" type="text" value= ""></td>
														<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_P"; ?> size="10" type="text" value= ""></td>
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
												<td class="EnTeteTableauCompetences">Dimanche</td>
												<?php
												$requete = "SELECT new_planning_prestation_vacation.NbHeureJour, new_planning_prestation_vacation.NbHeureEquipeJour, new_planning_prestation_vacation.NbHeureEquipeNuit, ";
												$requete .= "new_planning_prestation_vacation.NbHeurePause FROM new_planning_prestation_vacation ";
												$requete .= "WHERE new_planning_prestation_vacation.Id_Prestation=".$_GET['Id_Prestation']." AND ";
												$requete .= "new_planning_prestation_vacation.JourSemaine=".$i." AND new_planning_prestation_vacation.ID_Vacation=".$idVacation."";
												$resultPrestationVacation=mysqli_query($bdd,$requete);
												$nbenregPrestationVacation=mysqli_num_rows($resultPrestationVacation);
												if($nbenregPrestationVacation>0)
												{
												$rowVacation=mysqli_fetch_array($resultPrestationVacation)
												?>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $idVacation."_".$i."_J"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeureJour']; ?> "></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EJ"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeureEquipeJour']; ?> "></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EN"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeureEquipeNuit']; ?> "></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_P"; ?> size="10" type="text" value=" <?php echo $rowVacation['NbHeurePause']; ?> "></td>

												<?php
												}
												else
												{
												?>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $idVacation."_".$i."_J"; ?> size="10" type="text" value=""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EJ"; ?> size="10" type="text" value= ""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_EN"; ?> size="10" type="text" value= ""></td>
													<td width=30><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $idVacation."_".$i."_P"; ?> size="10" type="text" value= ""></td>
												<?php
												}
												?>
											</tr>
											<tr height='1' bgcolor='#66AACC'><td colspan='9'></td></tr>
										</tr>
										<?php
									} //fin boucle for
								}		//Fin if
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