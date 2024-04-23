<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/Demo_calendar_style.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<link href="../../CSS/Demo_calendar_jquery.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

echo "<script>alert(window.opener.location);</script>";

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

$Personne="";
$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id_Preparateur;
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if ($nbResulta>0){
	$row=mysqli_fetch_array($result);
	$Personne=$row['Nom']." ".$row['Prenom'];
}
?>
<?php Ecrire_Code_JS_Init_Date(); ?>
<table style="height:10%;" width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Planning.php">
	<input type="hidden" name="langue" id="langue" value="<?php echo $_SESSION['Langue']; ?>">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "PLANNING - ".$Personne;}else{echo "PLANNING - ".$Personne;} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<div id="content">
				<div id="switcher_agenda_options">
					<div id="choix_plage_horaire">
						<span class="semaine_en_cours">
							<?php
								if(date("Y",strtotime($lundi))==date("Y",strtotime($lundi." +6 day"))){
									$valeur= date("d",strtotime($lundi))." - ".date("d",strtotime($lundi." +6 day")); 
									if($_SESSION['Langue']=="EN"){
										$valeur.=" ".$tabEN[date("m",strtotime($lundi))-1];
									}
									else{
										$valeur.=" ".$tabFR[date("m",strtotime($lundi))-1];
									} 
									$valeur.=" ".date("Y",strtotime($lundi));
								}
								else{
									$valeur= date("d",strtotime($lundi)); 
									if($_SESSION['Langue']=="EN"){
										$valeur.=" ".$tabEN[date("m",strtotime($lundi))-1];
									}
									else{
										$valeur.=" ".$tabFR[date("m",strtotime($lundi))-1];
									} 
									$valeur.=" ".date("Y",strtotime($lundi));
									$valeur.=" - ".date("Y-m-d",strtotime($lundi." +6 day")); 
									if($_SESSION['Langue']=="EN"){
										$valeur.=" ".$tabEN[date("Y-m-d",strtotime($lundi." +6 day"))+1];
									}
									else{
										$valeur.=" ".$tabFR[date("Y-m-d",strtotime($lundi." +6 day"))+1];
									} 
									$valeur.=" ".date("Y-m-d",strtotime($lundi." +6 day"));
								}
								echo $valeur;
							?>
						</span>
					</div>
				</div>
				<div id="calendrier">
					<table id="calendar_table">
						<thead>
							<tr>
								<th class="info_horaires"></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "Mo. ".AfficheDateFR2($lundi);}else{echo "Lu. ".AfficheDateFR2($lundi);} ?></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "Tu. ".AfficheDateFR2($mardi);}else{echo "Ma. ".AfficheDateFR2($mardi);} ?></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "We. ".AfficheDateFR2($mercredi);}else{echo "Me. ".AfficheDateFR2($mercredi);} ?></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "Th. ".AfficheDateFR2($jeudi);}else{echo "Je. ".AfficheDateFR2($jeudi);} ?></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "Fr. ".AfficheDateFR2($vendredi);}else{echo "Ve. ".AfficheDateFR2($vendredi);} ?></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "Sa. ".AfficheDateFR2($samedi);}else{echo "Sa. ".AfficheDateFR2($samedi);} ?></th>
								<th><?php if($_SESSION['Langue']=="EN"){echo "Su. ".AfficheDateFR2($dimanche);}else{echo "Di. ".AfficheDateFR2($dimanche);} ?></th>
							</tr>
						</thead>
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