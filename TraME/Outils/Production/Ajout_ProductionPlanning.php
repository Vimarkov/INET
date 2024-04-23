<html>
<head>
<script language="javascript" src="Production.js?t=<?php echo time();?>"></script>
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
	
	echo '<table align="center" cellpadding="0" cellspacing="0">';
	echo '<tr>';

	for($i=0;$i<=23;$i++){
		echo "<td class=\"info_horairesPROD info_horaires_content2\">";
		$heure=$i;
		if($i<10){$heure="0".$i;}
		echo "<label style=\"font-size:14px;width:60px;\">".$heure."</label><sup>00</sup>";
		echo "</td>";
	}

	
	echo '</tr>';
	echo '<input type="hidden" name="" id="nbResultaPoint" value="'.$nbResultaPoint.'">';
	echo '<tr>';
		$j=0;
			if ($nbResulta>0){
				echo "<td colspan='24' valign=\"top\" class=\"other_day ".$calendarTD."\" height=\"80px\" id=\"".$jour."\">";
				while($row=mysqli_fetch_array($result)){
					$couleur="#cbcbcb";
					if($row['Couleur']<>""){$couleur=$row['Couleur'];}
					if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendar_event";$calendarEventDate="calendar_event_date";}
					else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
					echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"position:absolute;width:".($row['DureeMinute']*0.6)."px; margin-left:".($row['MinuteDebut']*0.6)."px;background-color:".$couleur.";display:table-cell;\">";
						echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
							echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
							echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
							echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
							echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
						echo "</div>";
						echo "<div class=\"calendar_event_title hoverCritereOTD\" id=\"".$row['Id']."_title\">[".$row['Prestation']."]\n<span>".$row['Tache']."</span><br></div>";
					echo "</div>";
					echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
					
					$j++;
				}
				echo "</td>";
			}
	echo '</tr>';
echo '</table>';
?>
</body>
</html>