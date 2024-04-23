<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(laDate){
			window.opener.parent.location="Pointage.php?laDate="+laDate;
			window.close();
		}
		function VerifChamps(langue){
			
			if(formulaire.semaineDebut.value>formulaire.semaineFin.value){
				if(langue=="EN"){
						alert('The start week must be less than the end week.');
					}
					else{
						alert('La semaine de début doit être inférieure à la semaine de fin.');
					}
				return false;
			}
			return true;
		}
	function nombre(champ){
		var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
		var verif;
		var points = 0; /* Supprimer cette ligne */

		for(x = 0; x < champ.value.length; x++)
		{
		verif = chiffres.test(champ.value.charAt(x));
		if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
		if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
		if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
		}
	}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();

$req="SELECT Id_PrestationExtra FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$rowPresta=mysqli_fetch_array($result);
$Id_PrestaExtranet=$rowPresta['Id_PrestationExtra'];
				
//Heures prévues par prestations
$reqHeuresPrevues = "SELECT new_planning_prestation_vacation.ID_Vacation, new_planning_prestation_vacation.JourSemaine , new_planning_prestation_vacation.NbHeureJour, ";
$reqHeuresPrevues .= "new_planning_prestation_vacation.NbHeureEquipeJour, new_planning_prestation_vacation.NbHeureEquipeNuit ";
$reqHeuresPrevues .= "FROM new_planning_prestation_vacation ";
$reqHeuresPrevues .= "WHERE new_planning_prestation_vacation.Id_Prestation =".$Id_PrestaExtranet." ";
$HeuresPrevues=mysqli_query($bdd,$reqHeuresPrevues);
$nbHeuresPrevues=mysqli_num_rows($HeuresPrevues);
if($_POST){
	if(isset($_POST['btn_save'])){
		//Récupérer l'Id de la prestation 
		
		for($i=-14;$i<=49;$i=$i+7){
			if(date("Y/W",strtotime($_POST['laDateEC']." ".$i." day"))>=$_POST['semaineDebut'] && date("Y/W",strtotime($_POST['laDateEC']." ".$i." day"))<=$_POST['semaineFin']){
				$semaine=date("W",strtotime($_POST['laDateEC']." ".$i." day"));
				$annee=date("Y",strtotime($_POST['laDateEC']." ".$i." day"));
				
				//Récupérer le pointage extranet
				//Liste des personnes
				$tab = array();
				
				$req="SELECT DISTINCT Id_Personne, TempsPartiel
					FROM trame_acces 
					LEFT JOIN new_rh_etatcivil 
				ON trame_acces.Id_Personne=new_rh_etatcivil.Id 
				WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
				AND (LEFT(Droit,1)=1 OR MID(Droit,2,1)=1) ";
				if($_POST['preparateur']>0){
					$req.="AND Id_Personne=".$_POST['preparateur']." ";
				}
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($row=mysqli_fetch_array($result)){
						$nbHeure=0;
						//Temps partiel
						$reqTempsPartiel = "SELECT new_planning_personne_vacation_tp.ID_Vacation, new_planning_personne_vacation_tp.JourSemaine , new_planning_personne_vacation_tp.NbHeureJour, ";
						$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeureEquipeJour, new_planning_personne_vacation_tp.NbHeureEquipeNuit ";
						$reqTempsPartiel .= "FROM new_planning_personne_vacation_tp ";
						$reqTempsPartiel .= "WHERE new_planning_personne_vacation_tp.ID_Personne=".$row['Id_Personne']."";
						$TempsPartiel=mysqli_query($bdd,$reqTempsPartiel);
						$nbTempsPartiel=mysqli_num_rows($TempsPartiel);
						
						//Recherche ses heures supp
						$reqHS = "SELECT new_rh_heures_supp.Date, new_rh_heures_supp.Nb_Heures_Jour, new_rh_heures_supp.Nb_Heures_Nuit ";
						$reqHS .= "FROM new_rh_heures_supp ";
						$reqHS .= "WHERE new_rh_heures_supp.Id_Personne =".$row['Id_Personne']." AND new_rh_heures_supp.Etat4='Validée' 
								AND	new_rh_heures_supp.Id_Prestation=".$Id_PrestaExtranet." ";
						$reqHS .= "AND CONCAT(WEEK(new_rh_heures_supp.Date,1),'/',YEAR(new_rh_heures_supp.Date))='".$semaine."/".$annee."' ;";
						$heureSupp=mysqli_query($bdd,$reqHS);
						$nbHeureSupp=mysqli_num_rows($heureSupp);
						
						//Recherche ses formations
						$reqFor = "SELECT new_planning_personne_formation.NbHeureVacation, new_planning_personne_formation.NbHeureHorsVacation, new_planning_personne_formation.DateFormation ";
						$reqFor .= "FROM new_planning_personne_formation ";
						$reqFor .= "WHERE new_planning_personne_formation.Id_Personne =".$row['Id_Personne']." ";
						$reqFor .= "AND CONCAT(WEEK(new_planning_personne_formation.DateFormation,1),'/',YEAR(new_planning_personne_formation.DateFormation))='".$semaine."/".$annee."' ;";
						$formationJour=mysqli_query($bdd,$reqFor);
						$formationJour=mysqli_query($bdd,$reqFor);
						$nbformationJour=mysqli_num_rows($formationJour);

						//Recherche si planning
						$reqPla = "SELECT new_planning_vacationabsence.Id, new_planning_personne_vacationabsence.DatePlanning ";
						$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
						$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$row['Id_Personne']." 
									AND new_planning_personne_vacationabsence.ID_Prestation=".$Id_PrestaExtranet." ";
						$reqPla .= "AND CONCAT(WEEK(new_planning_personne_vacationabsence.DatePlanning,1),'/',YEAR(new_planning_personne_vacationabsence.DatePlanning))='".$semaine."/".$annee."';";
						$vacationJour=mysqli_query($bdd,$reqPla);
						$nbVacationJour=mysqli_num_rows($vacationJour);
						
						//Recherche si en heure supp ce jour-ci
						$NbHeureSuppJ =0;
						$NbHeureSuppN =0;
						if ($nbHeureSupp>0){
							mysqli_data_seek($heureSupp,0);
							while($rowHS=mysqli_fetch_array($heureSupp)) {
								$NbHeureSuppJ += $rowHS[1];
								$NbHeureSuppN += $rowHS[2];
							}
						}
						
						//Recherche si en formation ce jour-ci
						$NbForVac =0;
						if ($nbformationJour>0){
							mysqli_data_seek($formationJour,0);
							while($rowFormation=mysqli_fetch_array($formationJour)) {
								$NbForVac +=$rowFormation[0];
							}
						}
						
						$resJ = 0;
						$resEJ = 0;
						$resEN = 0;
						if ($nbVacationJour > 0){
							mysqli_data_seek($vacationJour,0);
							while($rowPlanning=mysqli_fetch_array($vacationJour)){
								$tabDate = explode('-', $rowPlanning['DatePlanning']);
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
								$jour = date('w', $timestamp);
								if ($row['TempsPartiel'] == 1){
									if ($nbTempsPartiel > 0){
										mysqli_data_seek($TempsPartiel,0);
										while($rowTempsPartiel=mysqli_fetch_array($TempsPartiel)) {
											if($rowTempsPartiel['ID_Vacation'] == $rowPlanning['Id'] && $rowTempsPartiel['JourSemaine'] == $jour){
												//Récupérer NbHeures prévu pour cette personne, cette vacation, ce jour de la semaine en temps partiel
												$resJ += $rowTempsPartiel['NbHeureJour'];
												$resEJ += $rowTempsPartiel['NbHeureEquipeJour'];
												$resEN += $rowTempsPartiel['NbHeureEquipeNuit'];
											}
										}
									}
								}
								else{
									if ($nbHeuresPrevues > 0){
										mysqli_data_seek($HeuresPrevues,0);
										while($rowHeuresPrevues=mysqli_fetch_array($HeuresPrevues)) {
											if($rowHeuresPrevues['ID_Vacation'] == $rowPlanning['Id'] && $rowHeuresPrevues['JourSemaine'] == $jour){
												//Récupérer NbHeures prévu pour cette prestation, cette vacation, ce jour de la semaine
												$resJ += $rowHeuresPrevues['NbHeureJour'];
												$resEJ += $rowHeuresPrevues['NbHeureEquipeJour'];
												$resEN += $rowHeuresPrevues['NbHeureEquipeNuit'];
											}
										}
									}
								}
							}
						}
						$nbHeure=$resJ+$resEJ+$resEN+$NbHeureSuppJ+$NbHeureSuppN-$NbForVac;

						//Ajout du nombre d'heure à la personne
						$req="SELECT Id FROM trame_plannif WHERE Id_Preparateur=".$row['Id_Personne']." AND Semaine=".$semaine."  AND Annee=".$annee." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
						$resultHeure=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($resultHeure);
						if($nbResulta>0){
							$req="UPDATE trame_plannif SET NbHeure=".$nbHeure.", Id_Responsable=".$row['Id_Personne'].", DateMAJ='".date("Y-m-d")."' WHERE Id_Preparateur=".$row['Id_Personne']." AND Semaine=".$semaine."  AND Annee=".$annee." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
							$result2=mysqli_query($bdd,$req);
						}
						else{
							$req="INSERT INTO trame_plannif (Id_Preparateur,Semaine,Annee,NbHeure,Id_Responsable,DateMAJ,Id_Prestation) ";
							$req.="VALUES (".$row['Id_Personne'].",".$semaine.",".$annee.",".$nbHeure.",".$row['Id_Personne'].",'".date("Y-m-d")."',".$_SESSION['Id_PrestationTR'].") ";
							$result2=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}

	}
	echo "<script>FermerEtRecharger('".$_POST['laDateEC']."');</script>";
}
elseif($_GET)
{
$laDate=$_GET['laDate'];
?>

	<form id="formulaire" method="POST" action="RecupererPointageExtranet.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<table width="95%" align="center" class="TableCompetences">
		<tr class="TitreColsUsers">
			<input type="hidden" name="laDateEC" id="laDateEC" value="<?php echo $_GET['laDate']; ?>">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing engineer";}else{echo "Préparateur";} ?></td>
			<td>
				<select id="preparateur" name="preparateur" colspan="2">
					<option value='0'><?php if($_SESSION['Langue']=="EN"){echo "All";}else{echo "Tous";} ?></option>
					<?php
						$req="SELECT Id_Personne, Nom, Prenom FROM trame_acces ";
						$req.="LEFT JOIN new_rh_etatcivil ON trame_acces.Id_Personne=new_rh_etatcivil.Id WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND (LEFT(Droit,1)=1 OR MID(Droit,2,1)=1) ORDER BY Nom, Prenom";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowPreparateur=mysqli_fetch_array($result)){
								echo "<option value='".$rowPreparateur['Id_Personne']."'>".$rowPreparateur['Nom']." ".$rowPreparateur['Prenom']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Week";}else{echo "Semaine";} ?></td>
			<td>
				<select id="semaineDebut" name="semaineDebut">
					<?php
						echo "<option value='".date("Y/W",strtotime($laDate." -14 day"))."'> ".date("W/Y",strtotime($laDate." -14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." -7 day"))."'> ".date("W/Y",strtotime($laDate." -7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +0 day"))."'> ".date("W/Y",strtotime($laDate." +0 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +7 day"))."'> ".date("W/Y",strtotime($laDate." +7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +14 day"))."'> ".date("W/Y",strtotime($laDate." +14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +21 day"))."'> ".date("W/Y",strtotime($laDate." +21 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +28 day"))."'> ".date("W/Y",strtotime($laDate." +28 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +35 day"))."'> ".date("W/Y",strtotime($laDate." +35 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +42 day"))."'> ".date("W/Y",strtotime($laDate." +42 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +49 day"))."'> ".date("W/Y",strtotime($laDate." +49 day"))."</option>";
					?>
				</select>
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "to";}else{echo "à";} ?></td>
			<td>
				<select id="semaineFin" name="semaineFin">
					<?php
						echo "<option value='".date("Y/W",strtotime($laDate." -14 day"))."'> ".date("W/Y",strtotime($laDate." -14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." -7 day"))."'> ".date("W/Y",strtotime($laDate." -7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +0 day"))."'> ".date("W/Y",strtotime($laDate." +0 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +7 day"))."'> ".date("W/Y",strtotime($laDate." +7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +14 day"))."'> ".date("W/Y",strtotime($laDate." +14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +21 day"))."'> ".date("W/Y",strtotime($laDate." +21 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +28 day"))."'> ".date("W/Y",strtotime($laDate." +28 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +35 day"))."'> ".date("W/Y",strtotime($laDate." +35 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +42 day"))."'> ".date("W/Y",strtotime($laDate." +42 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +49 day"))."'> ".date("W/Y",strtotime($laDate." +49 day"))."</option>";
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="6" align="center">
				<input class="Bouton" type="submit" name="btn_save" value="<?php if($_SESSION['Langue']=="EN"){echo "Recover";}else{echo "Récupérer";}?>">
			</td>
		</tr>
	</table>
	</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>