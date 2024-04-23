<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<script language="javascript" src="CreneauRecurrent.js"></script>
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#heureDebut').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
			
			$('#heureFin').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
		});
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$lundi=date("Y-m-d");
$semaine=date("W");
$annee=date("Y");
Ecrire_Code_JS_Init_Date();
 if($_POST){
	$req="INSERT INTO trame_planning_recurrence (Id_Createur,DateCreation) ";
	$req.="VALUES (".$_SESSION['Id_PersonneTR'].",'".date('Y-m-d')."')";
	$result=mysqli_query($bdd,$req);
	$Id_Recurrence = mysqli_insert_id($bdd);
	
	$dateDebut=TrsfDate_($_POST['dateDebut']);
	$dateFin=TrsfDate_($_POST['dateFin']);
	if($Id_Recurrence>0){
		if($_POST['periodicite']=="hebdomadaire"){
			for($laDate=$dateDebut;$laDate<=$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
				$aCreer=0;
				if(isset($_POST['lundi']) && date('N',strtotime($laDate." +0 day"))==1){$aCreer=1;}
				elseif(isset($_POST['mardi']) && date('N',strtotime($laDate." +0 day"))==2){$aCreer=1;}
				elseif(isset($_POST['mercredi']) && date('N',strtotime($laDate." +0 day"))==3){$aCreer=1;}
				elseif(isset($_POST['jeudi']) && date('N',strtotime($laDate." +0 day"))==4){$aCreer=1;}
				elseif(isset($_POST['vendredi']) && date('N',strtotime($laDate." +0 day"))==5){$aCreer=1;}
				elseif(isset($_POST['samedi']) && date('N',strtotime($laDate." +0 day"))==6){$aCreer=1;}
				elseif(isset($_POST['dimanche']) && date('N',strtotime($laDate." +0 day"))==7){$aCreer=1;}
				
				if($aCreer==1){
					//Vérifier si cette semaine n'est pas vérouillée
					$semaine=date("W",strtotime($laDate." +0 day"));
					$annee=date("Y",strtotime($laDate." +0 day"));
					$reqPoint="SELECT Id, Id_Prestation FROM trame_plannif WHERE Id_Prestation=".$_POST['new_event_presta']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
					$reqPoint.=" AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee." ";
					$resultPoint=mysqli_query($bdd,$reqPoint);
					$nbResultaPoint=mysqli_num_rows($resultPoint);
					
					if($nbResultaPoint==0){
						//Vérifier si ce créneau n'est pas déjà occupé
						$reqPla="SELECT Id FROM trame_planning WHERE Id_Prestation=".$_POST['new_event_presta']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
						$reqPla.=" AND DateDebut='".$laDate."' AND HeureDebut<'".$_POST['heureFin']."' AND HeureFin>'".$_POST['heureDebut']."' ";
						$resultPla=mysqli_query($bdd,$reqPla);
						$nbResultaPla=mysqli_num_rows($resultPla);
						if($nbResultaPla==0){
							$req="INSERT INTO trame_planning (Id_Prestation,Id_Preparateur,Id_Tache,Id_WP,DateDebut,HeureDebut,HeureFin,Id_Recurrence) ";
							$req.="VALUES (".$_POST['new_event_presta'].",".$_SESSION['Id_PersonneTR'].",".$_POST['new_event_tache'].",".$_POST['wp'].",'".$laDate."','".$_POST['heureDebut']."','".$_POST['heureFin']."',".$Id_Recurrence.")";
							$result=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}
		else{
			for($laDate=$dateDebut;$laDate<=$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
				$tabJour=array("monday","tuesday","wednesday","thursday","friday","saturday","sunday");
				$tabMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
				$mois=date('m',strtotime($laDate." +0 day"));
				$annee=date('Y',strtotime($laDate." +0 day"));
				$leJour="";
				$time = mktime(0, 0, 0, $mois, 1, $annee);
				if($_POST['numSemaine']==1){$leJour = date('Y-m-d',strtotime('first '.$tabJour[$_POST['numJour']-1], $time));}
				elseif($_POST['numSemaine']==2){$leJour = date('Y-m-d',strtotime('second '.$tabJour[$_POST['numJour']-1], $time));}
				elseif($_POST['numSemaine']==3){$leJour = date('Y-m-d',strtotime('third '.$tabJour[$_POST['numJour']-1], $time));}
				elseif($_POST['numSemaine']==4){$leJour = date('Y-m-d',strtotime('third '.$tabJour[$_POST['numJour']-1], $time));}
				elseif($_POST['numSemaine']==5){$leJour = date('Y-m-d',strtotime('last '.$tabJour[$_POST['numJour']-1].' of '.$tabMois[$mois-1].' '.$annee));}
				if($laDate==$leJour){
					//Vérifier si cette semaine n'est pas vérouillée
					$semaine=date("W",strtotime($laDate." +0 day"));
					$annee=date("Y",strtotime($laDate." +0 day"));
					$reqPoint="SELECT Id, Id_Prestation FROM trame_plannif WHERE Id_Prestation=".$_POST['new_event_presta']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
					$reqPoint.=" AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee." ";
					$resultPoint=mysqli_query($bdd,$reqPoint);
					$nbResultaPoint=mysqli_num_rows($resultPoint);
					
					if($nbResultaPoint==0){
						//Vérifier si ce créneau n'est pas déjà occupé
						$reqPla="SELECT Id FROM trame_planning WHERE Id_Prestation=".$_POST['new_event_presta']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
						$reqPla.=" AND DateDebut='".$laDate."' AND HeureDebut<'".$_POST['heureFin']."' AND HeureFin>'".$_POST['heureDebut']."' ";
						$resultPla=mysqli_query($bdd,$reqPla);
						$nbResultaPla=mysqli_num_rows($resultPla);
						if($nbResultaPla==0){
							$req="INSERT INTO trame_planning (Id_Prestation,Id_Preparateur,Id_Tache,Id_WP,DateDebut,HeureDebut,HeureFin,Id_Recurrence) ";
							$req.="VALUES (".$_POST['new_event_presta'].",".$_SESSION['Id_PersonneTR'].",".$_POST['new_event_tache'].",".$_POST['wp'].",'".$laDate."','".$_POST['heureDebut']."','".$_POST['heureFin']."',".$Id_Recurrence.")";
							$result=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}
	}
	echo "<script>FermerEtRecharger('".$_POST['laDateEC']."');</script>";
}
 ?>

<form id="formulaire" class="test" method="POST" action="Ajout_CreneauRecurrent.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Recurring slot";}else{echo "Créneau récurrent";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<input type="hidden" name="laDateEC" id="laDateEC" value="<?php echo $_GET['laDateEC'];?>" />
	<input type="hidden" name="langue" id="langue" value="<?php echo $_SESSION['Langue'];?>" />
	<tr><td height="4"></td></tr>
	<tr>
	<td>
	<table width="100%" align="center" class="TableCompetences">
		<tr>
			<td width="20%" class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Activity";}else{echo "Prestation";} ?> :
			</td>
			<td colspan="4">
				<select name="new_event_presta" id="new_event_presta" onchange="RechargerWP('<?php echo $_SESSION['Langue']; ?>')">
				<?php
					echo"<option value='0'></option>";
					$req="SELECT DISTINCT trame_prestation.Id, trame_prestation.Libelle 
						FROM trame_acces
						LEFT JOIN trame_prestation 
						ON trame_acces.Id_Prestation=trame_prestation.Id
						WHERE trame_acces.Id_Personne=".$_SESSION['Id_PersonneTR']."
						AND (SELECT COUNT(trame_plannif.Id) 
							FROM trame_plannif 
							WHERE trame_plannif.Id_Prestation=trame_acces.Id_Prestation 
							AND Id_Preparateur=".$_SESSION['Id_PersonneTR']." 
							AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee.")=0 
						ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						$i=0;
						while($rowPresta=mysqli_fetch_array($result)){
							$selected="";
							echo "<option value='".$rowPresta['Id']."' ".$selected.">".$rowPresta['Libelle']."</option>";
							echo "<script>Liste_Presta[".$i."]= Array('".$rowPresta['Id']."','".addslashes($rowPresta['Libelle'])."')</script>";
							$i++;
						}
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?> :
			</td>
			<td colspan="4">
				<div id="divWP">
				<select name="wp" id="wp" onchange="RechargerTache()">
				<?php
					echo"<option value='0'></option>";
					$req="SELECT Id, Libelle, Id_Prestation,Supprime FROM trame_wp WHERE Supprime=0 ORDER BY Libelle ";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						$i=0;
						while($rowWP=mysqli_fetch_array($result)){
							$selected="";
							echo "<script>Liste_WP[".$i."]= Array('".$rowWP['Id']."','".$rowWP['Id_Prestation']."','".addslashes($rowWP['Libelle'])."')</script>";
							$i++;
						}
					}
				?>
				</select><br/>
				</div>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?> :
			</td>
			<td colspan="4">
				<div id="divTache">
				<select name="new_event_tache" id="new_event_tache">
				<?php
						$req="SELECT DISTINCT trame_tache.Id, trame_tache.Libelle, trame_tache_wp.Id_WP, trame_tache.Supprime,trame_tache.Id_FamilleTache ";
						$req.="FROM trame_tache_wp LEFT JOIN trame_tache ON trame_tache_wp.Id_Tache=trame_tache.Id ";
						$req.="ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							$nb=0;
							$i=0;
							while($rowTache=mysqli_fetch_array($result)){
								echo "<script>Liste_Tache_WP[".$i."]= Array('".$rowTache['Id']."','".$rowTache['Id_WP']."','".$rowTache['Supprime']."','".str_replace("'"," ",str_replace("\\","",stripslashes($rowTache['Libelle'])))."','".$rowTache['Id_FamilleTache']."')</script>";
								$i++;
							}
							if($i==0){echo "<option value='0'></option>";}
						}
						else{
							echo "<option value='0'></option>";
						}
					?>
				</select>
				</div>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle" width="20%">
				<?php if($_SESSION['Langue']=="EN"){echo "From ";}else{echo "A partir du ";} ?>
			</td>
			<td width="30%">
				<input type="date" id="dateDebut" size="10" name="dateDebut" value="" />
			</td>
			<td class="Libelle" width="20%">
				<?php if($_SESSION['Langue']=="EN"){echo "to ";}else{echo "jusqu'au ";} ?>
			</td>
			<td width="30%">
				<input type="date" id="dateFin" size="10" name="dateFin" value="" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "From";}else{echo "De";}?></td>
			<td>
				<div class="input-group bootstrap-timepicker timepicker">
					<input class="form-control input-small" type="text" name="heureDebut" id="heureDebut" size="7" value="<?php echo "00:00:00"; ?>">
				</div>
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "to";}else{echo "à";}?></td>
			<td>
				<div class="input-group bootstrap-timepicker timepicker">
				<input class="form-control input-small" class="time" type="text" name="heureFin" id="heureFin" size="7" value="<?php echo "00:00:00"; ?>">
				</div>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle" width="20%">
				<?php if($_SESSION['Langue']=="EN"){echo "Periodicity ";}else{echo "Périodicité ";} ?>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				<input type="radio" name="periodicite" id="periodicite" value="hebdomadaire" checked /><?php if($_SESSION['Langue']=="EN"){echo "Weekly ";}else{echo "Hebdomadaire ";} ?>
			</td>
			<td colspan="4">
				<table width="100%">
					<tr>
						<td width="10%">
							<input type="checkbox" name="lundi" id="lundi" value="1" checked /><?php if($_SESSION['Langue']=="EN"){echo "Monday ";}else{echo "Lundi ";} ?>
						</td>
						<td width="10%">
							<input type="checkbox" name="mardi" id="mardi" value="2" checked /><?php if($_SESSION['Langue']=="EN"){echo "Tuesday ";}else{echo "Mardi ";} ?>
						</td>
						<td width="10%">
							<input type="checkbox" name="mercredi" id="mercredi" value="3" checked /><?php if($_SESSION['Langue']=="EN"){echo "Wednesday ";}else{echo "Mercredi ";} ?>
						</td>
						<td width="10%">
							<input type="checkbox" name="jeudi" id="jeudi" value="4" checked /><?php if($_SESSION['Langue']=="EN"){echo "Thursday ";}else{echo "Jeudi ";} ?>
						</td>
						<td width="10%">
							<input type="checkbox" name="vendredi" id="vendredi" value="5" checked /><?php if($_SESSION['Langue']=="EN"){echo "Friday ";}else{echo "Vendredi ";} ?>
						</td>
						<td width="10%">
							<input type="checkbox" name="samedi" id="samedi" value="6" /><?php if($_SESSION['Langue']=="EN"){echo "Saturday ";}else{echo "Samedi ";} ?>
						</td>
						<td width="10%">
							<input type="checkbox" name="dimanche" id="dimanche" value="7" /><?php if($_SESSION['Langue']=="EN"){echo "Sunday ";}else{echo "Dimanche ";} ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<input type="radio" name="periodicite" id="periodicite" value="mensuel" /><?php if($_SESSION['Langue']=="EN"){echo "Monthly ";}else{echo "Mensuel ";} ?>
			</td>
			<td colspan="4">
				<?php if($_SESSION['Langue']=="EN"){echo "The ";}else{echo "Le ";} ?>
				<select name="numSemaine" id="numSemaine">
					<option value="1"><?php if($_SESSION['Langue']=="EN"){echo "1st";}else{echo "1er";} ?></option>
					<option value="2"><?php if($_SESSION['Langue']=="EN"){echo "2nd";}else{echo "2ème";} ?></option>
					<option value="3"><?php if($_SESSION['Langue']=="EN"){echo "3rd";}else{echo "3ème";} ?></option>
					<option value="4"><?php if($_SESSION['Langue']=="EN"){echo "4th";}else{echo "4ème";} ?></option>
					<option value="5"><?php if($_SESSION['Langue']=="EN"){echo "last";}else{echo "dernier";} ?></option>
				</select>
				<select name="numJour" id="numJour">
					<option value="1"><?php if($_SESSION['Langue']=="EN"){echo "monday";}else{echo "lundi";} ?></option>
					<option value="2"><?php if($_SESSION['Langue']=="EN"){echo "tuesday";}else{echo "mardi";} ?></option>
					<option value="3"><?php if($_SESSION['Langue']=="EN"){echo "wednesday";}else{echo "mercredi";} ?></option>
					<option value="4"><?php if($_SESSION['Langue']=="EN"){echo "thursday";}else{echo "jeudi";} ?></option>
					<option value="5"><?php if($_SESSION['Langue']=="EN"){echo "friday";}else{echo "vendredi";} ?></option>
					<option value="6"><?php if($_SESSION['Langue']=="EN"){echo "saturday";}else{echo "samedi";} ?></option>
					<option value="7"><?php if($_SESSION['Langue']=="EN"){echo "sunday";}else{echo "dimanche";} ?></option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="5">
				<img src="../../Images/attention.png" width="15px" border="0" />
				<?php if($_SESSION['Langue']=="EN"){echo "<font color='red'><b>Slots will not be added to slots already in use and locked weeks</b></font>";}
				else{echo "<font color='red'><b>Les créneaux ne seront pas rajoutés sur des créneaux déjà utilisés et sur des semaines vérouillées</b></font> ";} ?>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<tr>
				<td align="center" colspan="5">
					<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";} ?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
	</table>
	</td></tr>
</table>
</form>
</body>
</html>