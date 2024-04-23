<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/Demo_calendar_style.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<link href="../../CSS/Demo_calendar_jquery.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../JS/calendar/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../JS/calendar/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="../JS/calendar/Demo_calendar_script.js"></script>
	<script type="text/javascript" src="../JS/calendar/jquery.corner.js"></script>
	<script>
		Liste_Tache_WP = new Array();
		function RechargerTache(Langue){
			var i;
			var sel="";
			var isElement = false;
			var bValide = true;
			sel ="<select class='lab' name='new_event_tache' id='new_event_tache'>";
			for(i=0;i<Liste_Tache_WP.length;i++){
				if (Liste_Tache_WP[i][1]==document.getElementById('new_event_wp').value && Liste_Tache_WP[i][2]=="0"){
					sel= sel + "<option value='"+Liste_Tache_WP[i][0];
					sel= sel + "'>"+Liste_Tache_WP[i][3]+"</option>";
					isElement = true;
				}
			}
			if(isElement == false){sel= sel + "<option value='0' selected></option>";}
			sel =sel + "</select>";
			document.getElementById('divTache').innerHTML=sel;
			RechargerInfos(Langue);
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

function get_lundi_from_week($week,$year,$format="Y-m-d") {
	$firstDayInYear=date("N",mktime(0,0,0,1,1,$year));
	if ($firstDayInYear<5)
	$shift=-($firstDayInYear-1)*86400;
	else
	$shift=(8-$firstDayInYear)*86400;
	if ($week>1) $weekInSeconds=($week-1)*604800; else $weekInSeconds=0;
	$timestamp=mktime(0,0,0,1,1,$year)+$weekInSeconds+$shift;
	$timestamp_vendredi=mktime(0,0,0,1,5,$year)+$weekInSeconds+$shift;

	return date($format,$timestamp);
}

$DateJour=date("Y-m-d");
$laDate=get_lundi_from_week($_GET['Semaine'],$_GET['Annee']);
$Id_Preparateur=$_GET['Id'];

$tabDateTransfert = explode('-', $laDate);
$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
$lundi=date("Y-m-d",$timestampTransfert);
$semaine=date("W",$timestampTransfert);
$annee=date("Y",$timestampTransfert);
$lundiPrecedent=date("Y-m-d",strtotime($lundi." -7 day"));
$dimanchePrecedent=date("Y-m-d",strtotime($lundi." -1 day"));
$mardi=date("Y-m-d",strtotime($lundi." +1 day"));
$mercredi=date("Y-m-d",strtotime($lundi." +2 day"));
$jeudi=date("Y-m-d",strtotime($lundi." +3 day"));
$vendredi=date("Y-m-d",strtotime($lundi." +4 day"));
$samedi=date("Y-m-d",strtotime($lundi." +5 day"));
$dimanche=date("Y-m-d",strtotime($lundi." +6 day"));
$lundiSuivant=date("Y-m-d",strtotime($lundi." +7 day"));
$dimancheSuivant=date("Y-m-d",strtotime($lundi." +13 day"));

$tabFR=array('janvier','f&#233;vrier','mars','avril','mai','juin','juillet','ao&#251;t','septembre','octobre','novembre','d&#233;cembre');
$tabEN=array('january','february','march','april','may','june','july','august','september','october','november','december');
?>
<?php Ecrire_Code_JS_Init_Date(); ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="PlanningCorps.php">
	<input type="hidden" name="langue" id="langue" value="<?php echo $_SESSION['Langue']; ?>">
	<input type="hidden" name="laDateEC" id="laDateEC" value="<?php echo $lundi; ?>">
	<input type="hidden" name="Id_Prepa" id="Id_Prepa" value="<?php echo $Id_Preparateur; ?>">
	<input type="hidden" name="pagePHP" id="pagePHP" value="PlanningResp.php">
	<input type="hidden" name="Semaine" id="Semaine" value="<?php echo $semaine; ?>">
	<input type="hidden" name="Annee" id="Annee" value="<?php echo $annee; ?>">
	<input type="hidden" name="DateEC" id="DateEC" value="<?php echo $_GET['DateEC']; ?>">
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<div id="content">
				<div id="gen_new_content" title="<?php if($_SESSION['Langue']=="EN"){echo "New event";}else{echo "Nouvel &#233;v&#232;nement";}?>">
					<form action="">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<label class="label_presta" for="new_event_presta"><?php if($_SESSION['Langue']=="EN"){echo "Activity";}else{echo "Prestation";} ?> : </label>
								</td>
								<td>
									<select class="lab" name="new_event_presta" id="new_event_presta" onchange="RechargerWP('<?php echo $_SESSION['Langue']; ?>')"/>
									<?php
										echo"<option value='0'></option>";
										$req="SELECT DISTINCT trame_prestation.Id, trame_prestation.Libelle 
											FROM trame_acces
											LEFT JOIN trame_prestation 
											ON trame_acces.Id_Prestation=trame_prestation.Id
											WHERE trame_acces.Id_Personne=".$Id_Preparateur.";";
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
									</select><br />
								</td>
							</tr>
							<tr>
								<td>
									<label class="label_wp" for="new_event_wp"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?> : </label>
								</td>
								<td>
									<select class="lab" name="new_event_wp" id="new_event_wp" onchange="RechargerTache()"/>
									<?php
										echo"<option value='0'></option>";
										$req="SELECT Id, Libelle, Supprime FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle ;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowWP=mysqli_fetch_array($result)){
												$selected="";
												if($rowWP['Supprime']==false){
													echo "<option value='".$rowWP['Id']."' ".$selected.">".$rowWP['Libelle']."</option>";
													echo "<script>Liste_WP[".$i."]= Array('".$rowWP['Id']."','".addslashes($rowWP['Libelle'])."')</script>";
													$i++;
												}
											}
										}
									?>
									</select><br />
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td>
									<label class="label_tache" for="new_event_tache"><?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "T&#226;che";} ?> : </label>
								</td>
								<td>
									<div id="divTache">
									<select class="lab" name="new_event_tache" id="new_event_tache" />
									<?php
											$req="SELECT DISTINCT trame_tache.Id, trame_tache.Libelle, trame_tache_wp.Id_WP, trame_tache.Supprime,trame_tache.Id_FamilleTache ";
											$req.="FROM trame_tache_wp LEFT JOIN trame_tache ON trame_tache_wp.Id_Tache=trame_tache.Id ";
											$req.="WHERE trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$nb=0;
												$i=0;
												while($rowTache=mysqli_fetch_array($result)){
													echo "<script>Liste_Tache_WP[".$i."]= Array('".$rowTache['Id']."','".$rowTache['Id_WP']."','".$rowTache['Supprime']."','".addslashes($rowTache['Libelle'])."','".$rowTache['Id_FamilleTache']."')</script>";
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
								<td>
									<label class="label_evenement" for="new_event_commentaire"><?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?> : </label>
								</td>
								<td>
									<input type="text" class="lab" size="110px" name="new_event_commentaire" id="new_event_commentaire" />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div id="gen_new_calendar" title="Nouvel agenda">
					<form action="">
						<label class="label_evenement" for="new_calendar_title">Titre : </label><input type="text" class="lab" name="new_calendar_title" id="new_calendar_title" />
					</form>
				</div>
				<div id="create_event"></div>
				<div id="dialog" title="<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Suppression";}?>"><?php if($_SESSION['Langue']=="EN"){echo "Please confirm the suppress";}else{echo "Veuillez confirmer la suppression";}?></div>
				<div id="calendrier">
					<table id="calendar_table">
						<tbody>
							<tr>
								<td class="info_horaires">
									<div class='info_horaires_content'><label style="font-size:24px;">00</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">01</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">02</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">03</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">04</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">05</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">06</label><sup>00</sup></div>
									<div id="DebutJournee"></div>
									<div class='info_horaires_content'><label style="font-size:24px;">07</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">08</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">09</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">10</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">11</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">12</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">13</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">14</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">15</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">16</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">17</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">18</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">19</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">20</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">21</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">22</label><sup>00</sup></div>
									<div class='info_horaires_content'><label style="font-size:24px;">23</label><sup>00</sup></div>
								</td>
								<?php
									$req="SELECT Id,DateDebut, HeureDebut, HeureFin,Id_Tache,Id_Prestation, Id_WP, ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS MinuteDebut, ";
									$req.="((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS DureeMinute, ";
									$req.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_planning.Id_WP) AS WP, ";
									$req.="(SELECT Libelle FROM trame_prestation WHERE trame_prestation.Id=trame_planning.Id_Prestation) AS Prestation, ";
									$req.="(SELECT Couleur FROM trame_prestation WHERE trame_prestation.Id=trame_planning.Id_Prestation) AS Couleur, ";
									$req.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_planning.Id_Tache) AS Tache, ";
									$req.="Commentaire ";
									$req.="FROM trame_planning WHERE DateDebut>='".$lundi."' ";
									$req.=" AND DateDebut<='".$dimanche."' AND Id_Preparateur=".$Id_Preparateur;
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									
									$reqPoint="SELECT Id FROM trame_plannif WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Preparateur=".$Id_Preparateur;
									$reqPoint.=" AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee." ";
									$resultPoint=mysqli_query($bdd,$reqPoint);
									$nbResultaPoint=mysqli_num_rows($resultPoint);
									$calendarTD="calendar_td";
									$calendarEvent="calendar_event";
									$calendarEventDate="calendar_event_date";
									if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}
								?>
								<input type="hidden" name="leCalendarTD" id="leCalendarTD" value="<?php echo $calendarTD; ?>">
								<td valign="top" class="<?php if($lundi==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $lundi;?>">
								<?php
									$j=0;
									if ($nbResulta>0){
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$lundi){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($lundi)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>
								</td>
								<td valign="top" class="<?php if($mardi==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $mardi;?>">
								<?php
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$mardi){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($mardi)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>
								</td>
								<td valign="top" class="<?php if($mercredi==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $mercredi;?>">
								<?php
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$mercredi){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($mercredi)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>
								</td>
								<td valign="top" class="<?php if($jeudi==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $jeudi;?>">
								<?php
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$jeudi){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($jeudi)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>
								</td>
								<td valign="top" class="<?php if($vendredi==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $vendredi;?>">
									<?php
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$vendredi){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($vendredi)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>                 									
								</td>
								<td valign="top" class="<?php if($samedi==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $samedi;?>">
								<?php
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$samedi){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($samedi)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>
								</td>
								<td valign="top" class="<?php if($dimanche==$DateJour){echo "current_day ".$calendarTD;}else{echo "other_day ".$calendarTD;}?>" id="<?php echo $dimanche;?>">
								<?php
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											 if($row['DateDebut']==$dimanche){
												if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendarAutre_event";$calendarEventDate="calendarAutre_event_date";}
												else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
												echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"height:".(($row['DureeMinute']*10)/10)."px; margin-top:".(($row['MinuteDebut']*10)/10)."px;background-color:".$row['Couleur'].";\">";
													echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
														echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
														echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
														echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
													echo "</div>";
													echo "<div class=\"calendar_event_title\" id=\"".$row['Id']."_title\">[".$row['Prestation']."] ".$row['Tache']."<br></div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_laDate\">".AfficheDateJJ_MM_AAAA($dimanche)."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_commentaire\">".stripslashes($row['Commentaire'])."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_tache\">".$row['Id_Tache']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_wp\">".$row['Id_WP']."</div>";
													echo "<div style=\"display:none;\" id=\"".$row['Id']."_presta\">".$row['Id_Prestation']."</div>";
												echo "</div>";
												echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
												$j++;
											 }
										}
									}
								?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</td>
	</tr>
</form>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>