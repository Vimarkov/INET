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
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>
<?php
	session_start();
	require("../Connexioni.php");
	
if($_POST)
{	$sqlUpdate = "UPDATE new_rh_etatcivil SET TempsPartiel=".$_POST['TempsPartiel']." WHERE Id=".$_POST['Id_Personne']."";
	$resultUpdate=mysqli_query($bdd,$sqlUpdate);
	if ($_POST['TempsPartiel'] =="1"){
		$resultVacation=mysqli_query($bdd,"SELECT new_planning_vacationabsence.Id FROM new_planning_vacationabsence WHERE new_planning_vacationabsence.AbsenceVacation=1");
		$NbLigneVacation=mysqli_num_rows($resultVacation);

		$requeteInsert="INSERT INTO new_planning_personne_vacation_tp (ID_Personne, ID_Vacation, NbHeureJour, NbHeureEquipeJour, NbHeureEquipeNuit,NbHeurePause,JourSemaine)";
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
				
				$requeteInsert.=" (".$_POST['Id_Personne'].",".$rowVacation[0].",".$NbHeureJour.",".$NbHeureEquipeJour.",";
				$requeteInsert.= "".$NbHeureEquipeNuit.",".$NbHeurePause.",".$NbCompteJour.")";
				if($NbCompteJour<=6 && $NbCompteVacation<=$NbLigneVacation ){$requeteInsert.=",";}
			}
		}
		$requeteInsert =  substr($requeteInsert, 0, -1).";" ;

		$requeteSupp="DELETE FROM new_planning_personne_vacation_tp WHERE ID_Personne=".$_POST['Id_Personne'];
		$resultSupp=mysqli_query($bdd,$requeteSupp);
		
		$resultInsert=mysqli_query($bdd,$requeteInsert);
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>
<form id="formulaire" method="POST" action="TempsPartiel.php">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="TitreSousPagePlanning" width="4"></td>
						<?php
						$resultPersonne=mysqli_query($bdd,"SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_rh_etatcivil.TempsPartiel FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']." ");
						$rowPersonne=mysqli_fetch_array($resultPersonne);
						?>
						<td class="TitrePage">Temps partiel / Forfait- <?php echo $rowPersonne['Nom']." ".$rowPersonne['Prenom'] ?></td>
						<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
						<input type="hidden" name="Acces" value="<?php echo $_GET['Acces'];?>">
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="10"><br></td>
		</tr>
		<tr>
			<td colspan="3">
				Temps partiel / Forfait :
				<select name="TempsPartiel" onchange=";">
				<?php if($rowPersonne['TempsPartiel'] ==0){
					echo "<option name='0' value='0' Selected>Non</option>";
					echo "<option name='1' value='1' >Oui</option>";
				}
				else{
					echo "<option name='0' value='0' >Non</option>";
					echo "<option name='1' value='1' Selected>Oui</option>";
				}
				?>
				
				</select>
			</td>
		</tr>
		<tr>
		<?php
		if($_GET['Acces']){
		?>
			<td align="center" width="10"><input class="Bouton" type="submit" value='Enregistrer'></td>
		<?php
		}
		?>
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
											$i=0;
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
													$requete = "SELECT new_planning_personne_vacation_tp.NbHeureJour, new_planning_personne_vacation_tp.NbHeureEquipeJour, new_planning_personne_vacation_tp.NbHeureEquipeNuit, ";
													$requete .= "new_planning_personne_vacation_tp.NbHeurePause FROM new_planning_personne_vacation_tp ";
													$requete .= "WHERE new_planning_personne_vacation_tp.ID_Personne=".$_GET['Id_Personne']." AND ";
													$requete .= "new_planning_personne_vacation_tp.JourSemaine=".$i." AND new_planning_personne_vacation_tp.ID_Vacation=".$idVacation."";
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
											} ?>
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