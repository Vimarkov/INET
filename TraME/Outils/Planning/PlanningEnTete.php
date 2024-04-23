<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/Demo_calendar_style.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<link href="../../CSS/Demo_calendar_jquery.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<script language="javascript">
	function EnvoyerEmail(date){
			var w=window.open("EnvoyerEmail.php?Date="+date,"Email","status=no,menubar=no,scrollbars=yes,width=100,height=100");
			w.focus();
			}
	function CreneauRecurrent(laDate){
		var w=window.open("Ajout_CreneauRecurrent.php?laDateEC="+laDate,"PageCreaneau","status=no,menubar=no,scrollbars=yes,width=700,height=350");w.focus();}
	</script>
</head>
<?php
require("../../Menu.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d");
if(isset($_GET['laDate'])){
	$tabDateTransfert = explode('-', $_GET['laDate']);
	$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
	$lundi=date("Y-m-d",$timestampTransfert);
}
else{
	if(date("N")==1){
		$lundi=date("Y-m-d");
	}
	else{
		$lundi=date("Y-m-d",strtotime("last Monday"));
	}
}
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

$tabFR=array('janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
$tabEN=array('january','february','march','april','may','june','july','august','september','october','november','december');
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
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "PLANNING";}else{echo "PLANNING";} ?></td>
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
						<input onclick="window.parent.location='Planning.php?laDate=<?php echo $lundiPrecedent;?>';" type="image" src="../../Images/bef_week.png" title="<?php echo AfficheDateFR($lundiPrecedent)." - ".AfficheDateFR($dimanchePrecedent); ?>"/>
						<input onclick="window.parent.location='Planning.php?laDate=<?php echo $lundiSuivant;?>';" type="image" src="../../Images/next_week.png" title="<?php echo AfficheDateFR($lundiSuivant)." - ".AfficheDateFR($dimancheSuivant); ?>"/>
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
								$nom="Avertir mon responsable";
								if($_SESSION['Langue']=="EN"){
									$nom="Warn my manager";
								}
							?>
						</span>
						<?php
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<a href=\"javascript:EnvoyerEmail('".$valeur."')\"><img src=\"../../Images/Email.png\" width=\"25px\" border=\"0\" alt=\"".$nom."\" title=\"".$nom."\"></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
							<a style='text-decoration:none;' valign="top" href="javascript:CreneauRecurrent('<?php echo $lundi; ?>')" class="Bouton">&nbsp;&nbsp;<?php  if($_SESSION['Langue']=="EN"){echo "Add a recurring slot";}else{echo "Ajouter un créneau récurrent";} ?>&nbsp;&nbsp;</a>
					</div>
				</div>
				<div>
				<br>
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