<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/Demo_calendar_style_pointage.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<link href="../../CSS/Demo_calendar_jquery.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../JS/calendar/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="../JS/calendar/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="../JS/calendar/Pointage_calendar_script.js"></script>
	<script type="text/javascript" src="../JS/calendar/jquery.corner.js"></script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$DateJour=date("Y-m-d");
$semaine=date("YW");
if(isset($_GET['laDate'])){
	$tabDateTransfert = explode('-', $_GET['laDate']);
	$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
	$laDate=date("Y-m-d",$timestampTransfert);
}
else{
	$laDate=date("Y-m-d");
}
$Precedent=date("Y-m-d",strtotime($laDate." -49 day"));
$Suivant=date("Y-m-d",strtotime($laDate."+49 day"));
?>
<?php Ecrire_Code_JS_Init_Date(); ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="PointageCorps.php">
	<input type="hidden" name="langue" id="langue" value="<?php echo $_SESSION['Langue']; ?>">
	<input type="hidden" name="laDateEC" id="laDateEC" value="<?php echo $laDate; ?>">
	<input type="hidden" name="semaineAnnee" id="semaineAnnee" value="<?php echo $semaine; ?>">
	<input type="hidden" name="vert" id="vert" style="background:#92d050;" value="">
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<div id="content">
				<div id="gen_new_content" title="<?php if($_SESSION['Langue']=="EN"){echo "Number of hours";}else{echo "Nombre d'heures";}?>">
					<form action="">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<label class="label_nbH" for="new_event_nbH"><?php if($_SESSION['Langue']=="EN"){echo "Nb";}else{echo "Nb";} ?> : </label>
								</td>
								<td>
									<input type="text" onKeyUp='nombre(this)' class="lab" size="10px" name="new_event_nbH" id="new_event_nbH" />
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div id="gen_valid_content" title="<?php if($_SESSION['Langue']=="EN"){echo "Validation of the schedule";}else{echo "Validation du pointage";}?>">
					<form action="">
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="40%">
									<label><?php if($_SESSION['Langue']=="EN"){echo "collaborater";}else{echo "Collaborateur";} ?> : </label>
								</td>
								<td id="collab" align="left">
									
								</td>
							</tr>
							<tr>
								<td width="40%">
									<label><?php if($_SESSION['Langue']=="EN"){echo "planning hours";}else{echo "Heures planning";} ?> : </label>
								</td>
								<td id="HPlanning">
								</td>
							</tr>
							<tr>
								<td width="40%">
									<label><?php if($_SESSION['Langue']=="EN"){echo "Pointing hours";}else{echo "Heures pointage";} ?> : </label>
								</td>
								<td id="HPointage" align="left">
								</td>
							</tr>
						</table>
					</form>
				</div>
				<div id="calendrier">
					<table id="calendar_table">
						<tbody>
							<?php
								$req="SELECT Id_Preparateur,Semaine,Annee,NbHeure,Valide FROM trame_plannif 
									WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
								if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'&& substr($_SESSION['DroitTR'],4,1)=='0'){
									$req.="AND Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
								}
								$result2=mysqli_query($bdd,$req);
								$nbResulta2=mysqli_num_rows($result2);
								
								$req="SELECT Id_Preparateur,YEARWEEK(DateDebut,1) AS Semaine, ";
								$req.="CONCAT(HOUR(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(HeureFin,HeureDebut))))),'.',MINUTE(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(HeureFin,HeureDebut)))))) AS NbH ";
								$req.="FROM trame_planning ";
								$req.="WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
								if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'&& substr($_SESSION['DroitTR'],4,1)=='0'){
									$req.="AND Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
								}
								$req.="GROUP BY Id_Preparateur, ";
								$req.="YEARWEEK(DateDebut,1) ";
								$resultPoint=mysqli_query($bdd,$req);
								$nbResultaPoint=mysqli_num_rows($resultPoint);
								
								$req="SELECT Id_Preparateur,YEARWEEK(DateDebut,1) AS Semaine, ";
								$req.="CONCAT(HOUR(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(HeureFin,HeureDebut))))),'.',MINUTE(SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(HeureFin,HeureDebut)))))) AS NbH ";
								$req.="FROM trame_planning ";
								if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'&& substr($_SESSION['DroitTR'],4,1)=='0'){
									$req.="WHERE Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
								}
								$req.="GROUP BY Id_Preparateur, ";
								$req.="YEARWEEK(DateDebut,1) ";
								$resultPointGlobal=mysqli_query($bdd,$req);
								$nbResultaPointGlobal=mysqli_num_rows($resultPointGlobal);
								
								$req="SELECT Id_Personne, Nom, Prenom,TempsPartiel FROM trame_acces ";
								$req.="LEFT JOIN new_rh_etatcivil 
								ON trame_acces.Id_Personne=new_rh_etatcivil.Id 
								WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
								AND (LEFT(Droit,1)=1 OR MID(Droit,2,1)=1) ";
								if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'&& substr($_SESSION['DroitTR'],4,1)=='0'){
									$req.="AND Id_Personne=".$_SESSION['Id_PersonneTR']." ";
								}
								$req.="ORDER BY Nom, Prenom";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								
								//Heures prévues par prestations
								$reqHeuresPrevues = "SELECT new_planning_prestation_vacation.Id_Prestation, new_planning_prestation_vacation.ID_Vacation, new_planning_prestation_vacation.JourSemaine , new_planning_prestation_vacation.NbHeureJour, ";
								$reqHeuresPrevues .= "new_planning_prestation_vacation.NbHeureEquipeJour, new_planning_prestation_vacation.NbHeureEquipeNuit, ";
								$reqHeuresPrevues .= "new_planning_prestation_vacation.NbHeurePause ";
								$reqHeuresPrevues .= "FROM new_planning_prestation_vacation ";
								$reqHeuresPrevues .= "WHERE new_planning_prestation_vacation.Id_Prestation =".$_SESSION['Id_PrestationExtranet']." ";
								$HeuresPrevues=mysqli_query($bdd,$reqHeuresPrevues);
								$nbHeuresPrevues=mysqli_num_rows($HeuresPrevues);
								
								
								$calendarTDG="calendar_tdG";
								$calendarTDD="calendar_tdD";
								if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'&& substr($_SESSION['DroitTR'],4,1)=='0'){
									$calendarTDG="";
									$calendarTDD="";
								}
								if ($nbResulta>0){
									$i=0;
									while($row=mysqli_fetch_array($result)){
										echo "<tr>";
											echo "<td class='premier'><div class='info_horaires_content'>".$row['Nom']." ".$row['Prenom']."</div></td>";
											
											echo "<script>Liste_Personne[".$i."]= Array('".$row['Id_Personne']."','".$row['Nom']." ".$row['Prenom']."')</script>";
											$i++;
											
											$tabWeek = array("-14","-7","+0","+7","+14","+21","+28","+35","+42","+49");
											foreach($tabWeek as $week){
												$valeur="";
												$couleur="";
												if ($nbResulta2>0){
													mysqli_data_seek($result2,0);
													while($row2=mysqli_fetch_array($result2)){
														if($row['Id_Personne']==$row2['Id_Preparateur'] && date("W/Y",strtotime($laDate." ".$week." day"))==preg_replace('#^([0-9]){1}$#', "0$1", $row2['Semaine'])."/".$row2['Annee']){
															$valeur=$row2['NbHeure']."h";
															if($row2['Valide']==1){$couleur="style='background-color:#92d050;'";}
														}
													}
												}
												$valeur2="";
												if ($nbResultaPoint>0){
													mysqli_data_seek($resultPoint,0);
													while($rowPoin=mysqli_fetch_array($resultPoint)){
														if($row['Id_Personne']==$rowPoin['Id_Preparateur'] && date("YW",strtotime($laDate." ".$week." day"))==$rowPoin['Semaine']){
															$valeur2=$rowPoin['NbH']."h";
														}
													}
												}
												$valeurGlobal="";
												if ($nbResultaPointGlobal>0){
													mysqli_data_seek($resultPointGlobal,0);
													while($rowPoin=mysqli_fetch_array($resultPointGlobal)){
														if($row['Id_Personne']==$rowPoin['Id_Preparateur'] && date("YW",strtotime($laDate." ".$week." day"))==$rowPoin['Semaine']){
															$valeurGlobal=$rowPoin['NbH']."h";
														}
													}
												}
												if($valeur2==$valeurGlobal){$valeurGlobal="";}
												else{$valeurGlobal="<br>".$valeurGlobal;}
												
												if($couleur==""){
													if(date("YW") > date("YW",strtotime($laDate." ".$week." day"))){
														if($valeur<>""){
															if(substr($valeur,0,-1) > 0){
																$couleur="style='background-color:#ff0000;'";
															}
														}
													}
													elseif(date("YW") == date("YW",strtotime($laDate." ".$week." day"))){
														if($valeur<>""){
															if(substr($valeur,0,-1) > 0){
																if($valeur2==""){$couleur="style='background-color:#ff0000;'";}
																else{
																	if(substr($valeur2,0,-1) == 0){$couleur="style='background-color:#ff0000;'";}
																	else{
																		if(floatval(substr($valeur2,0,-1))>=floatval(substr($valeur,0,-1))){$couleur="style='background-color:#ffc000;'";}
																	}
																}
															}
														}
													}
												}
												if(date("W/Y",strtotime($laDate." ".$week." day"))==date("W/Y")){
													echo "<td align='center' class='autre current_day ".$calendarTDG."' ".$couleur." id='".$row['Id_Personne']."Hpl_".date("W/Y",strtotime($laDate." ".$week." day"))."'>";
												}
												else{echo "<td align='center' class='autre other_day ".$calendarTDG."' ".$couleur." id='".$row['Id_Personne']."Hpl_".date("W/Y",strtotime($laDate." ".$week." day"))."'>";}
												echo $valeur;
												echo "</td>";
												if(date("W/Y",strtotime($laDate." ".$week." day"))==date("W/Y")){
													echo "<td align='center' class='autre current_day ".$calendarTDD."' ".$couleur." id='".$row['Id_Personne']."Hpo_".date("W/Y",strtotime($laDate." ".$week." day"))."'>";
												}
												else{echo "<td align='center' class='autre other_day ".$calendarTDD."' ".$couleur." id='".$row['Id_Personne']."Hpo_".date("W/Y",strtotime($laDate." ".$week." day"))."'>";}
												echo $valeur2."<font color='#828688'>".$valeurGlobal."</font>";
												echo "</td>";
											}
										echo "</tr>";
									}
								}
							?>
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