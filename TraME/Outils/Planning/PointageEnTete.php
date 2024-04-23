<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/Demo_calendar_style_pointage.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<link href="../../CSS/Demo_calendar_jquery.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<script language="javascript">
	function Planifier(date){
		var w=window.open("PlanifierHeures.php?laDate="+date,"PageExtract","status=no,menubar=no,scrollbars=yes,width=520,height=200");
		w.focus();
	}
	function PointageExtranet(date){
		var w=window.open("RecupererPointageExtranet.php?laDate="+date,"PageExtract","status=no,menubar=no,scrollbars=yes,width=520,height=200");
		w.focus();
	}
	function Excel_Pointage(){
		var w=window.open("../Reporting/Ajout_CritereExtractPointagePrepa.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=650,height=300");
		w.focus();
	}
	</script>
</head>
<?php
require("../../Menu.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d");
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
<table style="height:10%;" width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Pointage.php">
	<input type="hidden" name="langue" id="langue" value="<?php echo $_SESSION['Langue']; ?>">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "SCHEDULE ";}else{echo "POINTAGE ";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<table align="right" width="44%" cellpadding="0" cellspacing="0">
			<tr>
				<?php if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'&& substr($_SESSION['DroitTR'],4,1)=='0'){ ?>
				<td width="10%"><a style="text-decoration:none;" href="javascript:Excel_Pointage()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Extract Schedule";}else{echo "Extract Pointage";}?>&nbsp;</a></td>
				<?php }
					else{
				?>
				<?php 
					$req="SELECT Id_PrestationExtra FROM trame_prestation WHERE Id_PrestationExtra>0 AND Id=".$_SESSION['Id_PrestationTR'];
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){ ?>
				<td width="12%"><a style="text-decoration:none;" class="Bouton" href="javascript:PointageExtranet('<?php echo $laDate; ?>')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Extranet Planning";}else{echo "Pointage Extranet";}?>&nbsp;</a></td>
					<?php } ?>
				<td width="12%"><a style="text-decoration:none;" class="Bouton" href="javascript:Planifier('<?php echo $laDate; ?>')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Plan hours";}else{echo "Planifier heures";}?>&nbsp;</a></td>
					<?php } ?>
				<td width="6%" bgcolor="#92d050" align="center" style="font-weight:bold;"><?php if($_SESSION['Langue']=="EN"){echo "Validated";}else{echo "Validé";} ?></td>
				<td width="6%" bgcolor="#ffc000" align="center" style="font-weight:bold;"><?php if($_SESSION['Langue']=="EN"){echo "To be validated";}else{echo "A valider";} ?></td>
				<td width="6%" bgcolor="#ffffff" align="center" style="font-weight:bold;"><?php if($_SESSION['Langue']=="EN"){echo "In progress";}else{echo "En cours";} ?></td>
				<td width="6%" bgcolor="#ff0000" align="center" style="font-weight:bold;"><?php if($_SESSION['Langue']=="EN"){echo "Anomaly, late";}else{echo "Anomalie, retard";} ?></td>
			</tr>
		</table>
	</tr>
	<tr>
		<td>
			<div id="content">
				<div id="switcher_agenda_options">
					<div id="choix_plage_horaire">
						<input onclick="window.parent.location='Pointage.php?laDate=<?php echo $Precedent;?>';" type="image" src="../../Images/bef_week.png" title="<?php echo "Wk ".date("W/Y",strtotime($laDate." -63 day"))." - "."Wk ".date("W/Y",strtotime($laDate." +0 day")); ?>"/>
						<input onclick="window.parent.location='Pointage.php?laDate=<?php echo $Suivant;?>';" type="image" src="../../Images/next_week.png" title="<?php echo "Wk ".date("W/Y",strtotime($laDate." +35 day"))." - "."Wk ".date("W/Y",strtotime($laDate." +98 day")); ?>"/>
						<span class="semaine_en_cours">
							<?php
								$valeur="Wk ".date("W/Y",strtotime($laDate." -14 day"))." - "."Wk ".date("W/Y",strtotime($laDate." +49 day"));
								echo $valeur;
							?>
						</span>
					</div>
				</div>
				<div id="calendrier">
					<table id="calendar_table">
						<thead>
							<tr>
								<th class="premier"></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." -14 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." -14 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." -7 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." -7 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +0 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +0 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +7 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +7 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +14 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +14 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +21 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +21 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +28 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +28 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +35 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +35 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +42 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +42 day")); ?></th>
								<th class="autre" <?php if(date("W/Y",strtotime($laDate." +49 day"))==date("W/Y")){echo "style='background-color:#fbf485;'";} ?> colspan="2"><?php echo "Wk ".date("W/Y",strtotime($laDate." +49 day")); ?></th>
							</tr>
							<tr>
								<th class="premier"><?php if($_SESSION['Langue']=="EN"){echo "Collaborator";}else{echo "Collaborateur";} ?></th>
								<?php
									$val=-14;
									for($i=0;$i<10;$i++){
										$hPla = "H. plan.";
										$hPoin = "H. point.";
										if($_SESSION['Langue']=="EN"){
											$hPla = "Plan. H.";
											$hPoin = "Sched. H.";
										}
										
										$style="";
										if(date("W/Y",strtotime($laDate." ".$val." day"))==date("W/Y")){$style = "style='background-color:#fbf485;'";}
										echo "<th class='autre2' ".$style.">".$hPla."</th>";
										echo "<th class='autre2' ".$style.">".$hPoin."</th>";
										$val+=7;
									}
								?>
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