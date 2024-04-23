<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<!-- Feuille de style -->
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function FermerEtRechargerPointage(Id_Prestation,uneDate,Id_Pole,Tri)
		{
			opener.location.href="Pointage.php?Id_Prestation="+Id_Prestation+"&uneDate="+uneDate+"&Id_Pole="+Id_Pole+"&Tri="+Tri;
			window.close();
		}
		function nombre2(champ)
		{
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */
			for(x = 0; x < champ.value.length; x++){
				verif = chiffres.test(champ.value.charAt(x));
				if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
				if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
				if(verif == false){
					champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;
				}
			}
			if(champ.value>7){
				champ.value='';
				x=0;
			}
		}
		function OuvreFenetreAidePointage()
			{window.open("AidePointage.php","PageAidePlanning","status=no,menubar=no,width=900,height=500");}
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->	
	
	<!-- Modernizr -->
	<script src="modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="js/jquery-1.4.3.min.js"></script>
	<script src="js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
	session_start();
	require("../Connexioni.php");
	
	if(isset($_POST['submitValider'])){
		//Mise à jour de l'enregistrement new_planning_personne_vacationabsence
		$dateDebutReq = date("Y/m/d", $_POST['ValeurDate']);
		$requeteUpdate = "UPDATE new_planning_personne_vacationabsence ";
		$requeteUpdate .= "SET new_planning_personne_vacationabsence.NbHeureJour=".$_POST['NbHeureJour'].", ";
		$requeteUpdate .= "new_planning_personne_vacationabsence.NbHeureEquipeJour=".$_POST['NbHeureEquipeJour'].", ";
		$requeteUpdate .= "new_planning_personne_vacationabsence.NbHeureEquipeNuit=".$_POST['NbHeureEquipeNuit'].", ";
		$requeteUpdate .= "new_planning_personne_vacationabsence.NbHeurePause=".$_POST['NbHeurePause'].", ";
		$requeteUpdate .= "new_planning_personne_vacationabsence.NbHeureFormation=".$_POST['NbHeureFormation'].",";
		$requeteUpdate .= "new_planning_personne_vacationabsence.ValidationResponsable=1, ";
		$requeteUpdate .= "new_planning_personne_vacationabsence.Commentaire='".addslashes($_POST['Commentaire'])."', ";
		$requeteUpdate .= "new_planning_personne_vacationabsence.Divers='".addslashes($_POST['Divers'])."'";
		$requeteUpdate .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$_POST['Personne']." ";
		$requeteUpdate .= "AND new_planning_personne_vacationabsence.DatePlanning='".$dateDebutReq."';";

		$resultUpdate=mysqli_query($bdd,$requeteUpdate);
		//Fermeture de la fenêtre et rechargement de la fenetre planning
		echo "<script>FermerEtRechargerPointage('".$_POST['Prestation']."','".$_POST['dateARenvoyer']."','".$_POST['Pole']."','".$_POST['Tri']."');</script>";
	 }
	 if($_POST){
		$IdPrestation = $_POST['Prestation'];
		$IdPole = $_POST['Pole'];
		$IdPersonne = $_POST['Personne'];
		$ValeurDate = $_POST['ValeurDate'];
		$laDateARenvoyer = $_POST['dateARenvoyer'];
		$Tri = $_POST['Tri'];
		$laDateDebut = date("d/m/Y",$ValeurDate);
		$dateDebutReq = date("Y/m/d", $ValeurDate);
		$jour = date('w', $ValeurDate);
	}
	elseif($_GET){
		$ValeurDate = $_GET['lDate'];
		$laDateDebut = date("d/m/Y", $_GET['lDate']);
		$dateDebutReq = date("Y/m/d",  $_GET['lDate']);
		$IdPrestation = $_GET['Id_Prestation'];
		$Tri = $_GET['Tri'];
		$IdPole = $_GET['Id_Pole'];
		$IdPersonne = $_GET['Id_Personne'];
		$laDateARenvoyer = $_GET['lDateEnvoi'];
		$jour = date('w', $_GET['lDate']);
	}

	//Personnes  présentent sur cette prestation à ces dates
	$req = "SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne, new_rh_etatcivil.TempsPartiel ";
	$req .= "FROM new_rh_etatcivil ";
	$req .= "WHERE new_rh_etatcivil.Id=".$IdPersonne.";";

	$resultPersonne=mysqli_query($bdd,$req);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	
	$PersonneTempsPartiel = 0;
	if ($nbPersonne > 0){
		$row=mysqli_fetch_array($resultPersonne);
		$PersonneTempsPartiel = $row['TempsPartiel'];
	}
	//Recherche si en formations
	$reqFor = "SELECT new_planning_personne_formation.NbHeureVacation, new_planning_personne_formation.NbHeureHorsVacation ";
	$reqFor .= "FROM new_planning_personne_formation ";
	$reqFor .= "WHERE new_planning_personne_formation.Id_Personne =".$IdPersonne." ";
	$reqFor .= "AND new_planning_personne_formation.DateFormation ='".$dateDebutReq."';";

	$formationJour=mysqli_query($bdd,$reqFor);
	$nbformationJour=mysqli_num_rows($formationJour);
	
	$NbForVac = 0;
	$NbForHorsVac = 0;
	$NbFor = 0;
	if ($nbformationJour>0){
		$rowformationJour=mysqli_fetch_array($formationJour);
		$NbForVac = $rowformationJour[0];
		$NbForHorsVac = $rowformationJour[1];
		$NbFor = $NbForVac;
	}
	
	//Heures prévues par prestations
	$reqHeuresPrevues = "SELECT new_planning_prestation_vacation.ID_Vacation, new_planning_prestation_vacation.JourSemaine , new_planning_prestation_vacation.NbHeureJour, ";
	$reqHeuresPrevues .= "new_planning_prestation_vacation.NbHeureEquipeJour, new_planning_prestation_vacation.NbHeureEquipeNuit, ";
	$reqHeuresPrevues .= "new_planning_prestation_vacation.NbHeurePause ";
	$reqHeuresPrevues .= "FROM new_planning_prestation_vacation ";
	$reqHeuresPrevues .= "WHERE new_planning_prestation_vacation.Id_Prestation=".$IdPrestation.";";
	$HeuresPrevues=mysqli_query($bdd,$reqHeuresPrevues);
	$nbHeuresPrevues=mysqli_num_rows($HeuresPrevues);
	
	//Temps partiel
	$reqTempsPartiel = "SELECT new_planning_personne_vacation_tp.ID_Vacation, new_planning_personne_vacation_tp.JourSemaine , new_planning_personne_vacation_tp.NbHeureJour, ";
	$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeureEquipeJour, new_planning_personne_vacation_tp.NbHeureEquipeNuit, ";
	$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeurePause ";
	$reqTempsPartiel .= "FROM new_planning_personne_vacation_tp ";
	$reqTempsPartiel .= "WHERE new_planning_personne_vacation_tp.ID_Personne=".$IdPersonne.";";
	$TempsPartiel=mysqli_query($bdd,$reqTempsPartiel);
	$nbTempsPartiel=mysqli_num_rows($TempsPartiel);
	
	//Recherche si planning
	$NbHeureJ = 0;
	$NbHeureEJ = 0;
	$NbHeureEN = 0;
	$NbHeureP = 0;
	$NbHeureFor = 0;
	$reqPla = "SELECT new_planning_vacationabsence.Nom, new_planning_vacationabsence.Couleur, new_planning_personne_vacationabsence.Commentaire, new_planning_vacationabsence.Id, ";
	$reqPla .= "new_planning_personne_vacationabsence.NbHeureJour, new_planning_personne_vacationabsence.NbHeureEquipeJour, new_planning_personne_vacationabsence.NbHeureEquipeNuit, new_planning_personne_vacationabsence.NbHeurePause, new_planning_personne_vacationabsence.ValidationResponsable, new_planning_personne_vacationabsence.NbHeureFormation, new_planning_personne_vacationabsence.Divers ";
	$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
	$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$IdPersonne." ";
	$reqPla .= "AND new_planning_personne_vacationabsence.DatePlanning='".$dateDebutReq."';";
	
	$vacationJour=mysqli_query($bdd,$reqPla);
	$nbVacationJour=mysqli_num_rows($vacationJour);
	
	//Recherche ses heures supp
	$reqHS = "SELECT new_rh_heures_supp.Nb_Heures_Jour, new_rh_heures_supp.Nb_Heures_Nuit ";
	$reqHS .= "FROM new_rh_heures_supp ";
	$reqHS .= "WHERE new_rh_heures_supp.Id_Personne =".$IdPersonne." AND new_rh_heures_supp.Etat4='Validée' AND new_rh_heures_supp.Date='".$dateDebutReq."';";
	$heureSupp=mysqli_query($bdd,$reqHS);
	$nbHeureSupp=mysqli_num_rows($heureSupp);
	
	$NbHeureSuppJ =0;
	$NbHeureSuppN =0;
	while($rowHS=mysqli_fetch_array($heureSupp)) {
		$NbHeureSuppJ += $rowHS[0];
		$NbHeureSuppN += $rowHS[1];
	}
	
	$Id_VacationJour = 0;
	$Commentaire = "";
	$Divers = "";
	$Id_PrestationJour = "";
	$Rappel = "";
	if ($nbVacationJour==1){
		$rowVacationJour=mysqli_fetch_array($vacationJour);
		$Id_VacationJour = $rowVacationJour[3];
		$Commentaire = $rowVacationJour[2];
		$Divers = $rowVacationJour['Divers'];
		$Id_PrestationJour = $rowVacationJour[4];
		if ($rowVacationJour[8] == 1){
			$NbHeureJ = $rowVacationJour[4];
			$NbHeureEJ = $rowVacationJour[5];
			$NbHeureEN = $rowVacationJour[6];
			$NbHeureP = $rowVacationJour[7];
			$NbHeureFor = $rowVacationJour[9];
			$Rappel = "Rappel : Ces informations ne sont pas calculées car elles ont été validées";
		}
		else{
			if ($PersonneTempsPartiel == 1){
				$NbHeureFor = $NbFor;
				$Rappel = "Rappel : Cette personne est à temps partiel. Ces informations sont calculées en fonction du type de vacation, des heures supplémentaires et des formations";
				if ($nbTempsPartiel > 0){
					mysqli_data_seek($TempsPartiel,0);
					while($rowTempsPartiel=mysqli_fetch_array($TempsPartiel)) {
						//Récupérer NbHeures prévu cette vacation, ce jour de la semaine
						if($rowTempsPartiel[0] == $rowVacationJour[3] && $rowTempsPartiel[1] == $jour){
							$NbHeureJ = $rowTempsPartiel[2];
							$NbHeureEJ = $rowTempsPartiel[3];
							$NbHeureEN = $rowTempsPartiel[4];
							$NbHeureP = $rowTempsPartiel[5];
							if ($NbHeureJ > 0){
								$NbHeureJ = $NbHeureJ - $NbForVac;
							}
							elseif ($NbHeureEJ > 0){
								$NbHeureEJ = $NbHeureEJ - $NbForVac;
							}
							if($NbFor>=7){$NbHeureJ=0;}
							$NbHeureJ += $NbHeureSuppJ;
							$NbHeureEN += $NbHeureSuppN;
						}
					}
				}
			}
			else{
				$NbHeureFor = $NbFor;
				$Rappel = "Rappel : Ces informations sont calculées en fonction du type de vacation, des heures supplémentaires et des formations";
				if ($nbHeuresPrevues > 0){
					mysqli_data_seek($HeuresPrevues,0);
					while($rowHeuresPrevues=mysqli_fetch_array($HeuresPrevues)) {
						//Récupérer NbHeures prévu cette vacation, ce jour de la semaine
						if($rowHeuresPrevues[0] == $rowVacationJour[3] && $rowHeuresPrevues[1] == $jour){
							$NbHeureJ = $rowHeuresPrevues[2];
							$NbHeureEJ = $rowHeuresPrevues[3];
							$NbHeureEN = $rowHeuresPrevues[4];
							$NbHeureP = $rowHeuresPrevues[5];
							if ($NbHeureJ > 0){
								$NbHeureJ = $NbHeureJ - $NbForVac;
							}
							elseif ($NbHeureEJ > 0){
								$NbHeureEJ = $NbHeureEJ - $NbForVac;
							}
							if($NbFor>=7){$NbHeureJ=0;}
							$NbHeureJ += $NbHeureSuppJ;
							$NbHeureEN += $NbHeureSuppN;
						}
					}
				}
			}
		}
	}
	
	if ($Id_VacationJour == ""){
		$Id_VacationJour = 0;
	}
	//Absences / Vacations
	$reqAbsVac = "SELECT new_planning_vacationabsence.Nom ,new_planning_vacationabsence.Description, new_planning_vacationabsence.Couleur ";
	$reqAbsVac .= "FROM new_planning_vacationabsence WHERE new_planning_vacationabsence.Id=".$Id_VacationJour.";";

	$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
	$nbAbsVac=mysqli_num_rows($resultAbsVac);
?>

 </script>
	<table class="TableCompetences" width=100%>
		 <form id="formulaire" method="post" action="ModifierPointage.php">
			<tr style="display:none;">
				<td><input type="text" name="Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
				<td><input type="text" name="Prestation" size="11" value="<?php echo $IdPrestation; ?>"></td>
				<td><input type="text" name="Pole" size="11" value="<?php echo $IdPole; ?>"></td>
				<td><input type="text" name="dateARenvoyer" size="11" value="<?php echo $laDateARenvoyer; ?>"></td>
				<td><input type="text" name="ValeurDate" size="11" value="<?php echo $ValeurDate; ?>"></td>
				<td><input type="text" name="Tri" size="11" value="<?php echo $Tri; ?>"></td>
			</tr>
			<tr>
			<tr>
				<td>Personne :</td>
				<td>
					<?php
					if ($nbPersonne > 0){
						echo $row[0];
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
					Date :
				</td>
				<td>
					<?php echo $laDateDebut; ?>
				</td>
			</tr>
			
			<tr>
				<td>Vacation / absence :
				</td>
				<?php
				if ($nbAbsVac > 0){
					$rowAbsVac=mysqli_fetch_array($resultAbsVac);
					echo "<td style='background-color:".$rowAbsVac[2].";'>".$rowAbsVac[0]." | ".$rowAbsVac[1]."</td>";
				}
				else{
					echo "<td></td>";
				}
				?>
			</tr>
			<tr>
				<td>Nombre d'heures en formation pendant la vacation : </td>
				<td><?php echo $NbForVac; ?></td>
			</tr>
			<tr>
				<td>Nombre d'heures en formation hors vacation : </td>
				<td><?php echo $NbForHorsVac; ?></td>
			</tr>
			<tr>
				<td>Nombre d'heures supplémentaires de jour : </td>
				<td><?php echo $NbHeureSuppJ; ?></td>
			</tr>
			<tr>
				<td>Nombre d'heures supplémentaires de nuit : </td>
				<td><?php echo $NbHeureSuppN; ?></td>
			</tr>
			<tr>
				<td>
					Commentaire :
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="Commentaire" rows=2 cols=90 resize="none"><?php echo $Commentaire; ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Divers :
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="Divers" rows=1 cols=90 resize="none"><?php echo $Divers; ?></textarea>
				</td>
			</tr>
			<tr height='1' bgcolor="#66AACC"><td colspan="4"></td></tr>
			<tr>
				<td colspan="2" style="color:red;">
				<?php echo $Rappel; ?>
				</td>
			</tr>
			<tr>
				<td>Nb heures Jour : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureJour" size="10" type="text" value= "<?php echo $NbHeureJ; ?>"></td>
			</tr>
			<tr>
				<td>Nb heures Formation : </td>
				<td><input onKeyUp="nombre2(this)" name="NbHeureFormation" size="10" type="text" value= "<?php echo $NbHeureFor; ?>"></td>
			</tr>
			<tr>
				<td>Nb heures Equipe Jour : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureEquipeJour" size="10" type="text" value= "<?php echo $NbHeureEJ; ?>"></td>
			</tr>
			<tr>
				<td>Nb heures Equipe Nuit : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureEquipeNuit" size="10" type="text" value= "<?php echo $NbHeureEN; ?>"></td>
			</tr>
			<tr>
				<td>Nb heures Pause : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeurePause" size="10" type="text" value= "<?php echo $NbHeureP; ?>"></td>
			</tr>
			<tr height='1' bgcolor="#66AACC"><td colspan="4"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" name="submitValider" type="submit" value='Valider'>
				</td>
			</tr>
		</form>
	</table>

</body>
</html>