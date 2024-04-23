<html>
<head>
<script type="text/javascript" src="../JS/jquery.min.js"></script>
<script src="../JS/js/jquery-1.4.3.min.js"></script>
<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
<script language="javascript" src="Production.js?t=<?php echo time();?>"></script>
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
<body>
<?php
header('Content-type: text/html; charset=iso-8859-1');

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
	
	$leJour=TrsfDate_($_GET['dateTravail']);
	$tabDateTransfert = explode('-', $leJour);
	$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
	$jour=date("Y-m-d",$timestampTransfert);
	$semaine=date("W",$timestampTransfert);
	$annee=date("Y",$timestampTransfert);

	$req="SELECT Id,DateDebut, HeureDebut, HeureFin,Id_Tache,Id_WP,Id_Prestation, ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS MinuteDebut, ";
	$req.="((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS DureeMinute, ";
	$req.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_planning.Id_WP) AS WP, ";
	$req.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_planning.Id_Tache) AS Tache, ";
	$req.="(SELECT Libelle FROM trame_prestation WHERE trame_prestation.Id=trame_planning.Id_Prestation) AS Prestation, ";
	$req.="(SELECT Couleur FROM trame_prestation WHERE trame_prestation.Id=trame_planning.Id_Prestation) AS Couleur, ";
	$req.="Commentaire ";
	$req.="FROM trame_planning WHERE DateDebut='".$jour."' AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	$reqPoint="SELECT Id, Id_Prestation FROM trame_plannif WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
	$reqPoint.=" AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee." ";
	
	$resultPoint=mysqli_query($bdd,$reqPoint);
	$nbResultaPoint=mysqli_num_rows($resultPoint);
	$calendarTD="calendar_td";
	$calendarEvent="calendar_event";
	$calendarEventDate="calendar_event_date";
	if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}
	if($nbResultaPoint==0){ 
		echo '<table width="50%" align="center" class="TableCompetences">';
			echo '<tr>';
				echo '<td width="2%"><input type="checkbox" name="blocPlanning" id="blocPlanning"/></td>';
				if($_SESSION['Langue']=="EN"){
					echo '<td width="30%" class="Libelle">Add a block to the schedule</td>';
					echo '<td width="5%">from</td>';
					echo '<td width="15%">
							<div class="input-group bootstrap-timepicker timepicker">
								<input class="form-control input-small" type="text" name="heureDebut" id="heureDebut" size="6" value="">
							</div>
						</td>';
					echo '<td width="5%">to</td>';
					echo '<td width="15%">
							<div class="input-group bootstrap-timepicker timepicker">
							<input class="form-control input-small" class="time" type="text" name="heureFin" id="heureFin" size="6" value="<?php echo $HeureH; ?>">
							</div>
						</td>';
					echo '<tr>
						<td colspan="7" align="center" class="Libelle">
						Warning: Block overlay is not possible !
						</td>
					</tr>';
				}else{
					echo '<td width="30%" class="Libelle">Ajouter un bloc au planning</td>';
					echo '<td width="5%">de</td>';
					echo '<td width="15%">
							<div class="input-group bootstrap-timepicker timepicker">
								<input class="form-control input-small" type="text" name="heureDebut" id="heureDebut" size="6" value="">
							</div>
						</td>';
					echo '<td width="5%">à</td>';
					echo '<td width="15%">
							<div class="input-group bootstrap-timepicker timepicker">
							<input class="form-control input-small" class="time" type="text" name="heureFin" id="heureFin" size="6" value="">
							</div>
						</td>';
					echo '<tr>
						<td colspan="7" align="center" class="Libelle">
						Attention : La superposition de blocs n\'est pas possible !
						</td>
					</tr>';
				}		
			echo '</tr>';
		echo '</table>';
	}
?>
</body>
</html>