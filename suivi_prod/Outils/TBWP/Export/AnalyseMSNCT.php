<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script type="text/javascript">
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
	<script type="text/javascript">
		$(function(){
			$(window).scroll(
				function () {//Au scroll dans la fenetre on d?clenche la fonction
					if ($(this).scrollTop() > 1) { //si on a d?fil? de plus de 150px du haut vers le bas
						$('#navigation').addClass("fixNavigation"); //on ajoute la classe "fixNavigation" ? <div id="navigation">
					} else {
						$('#navigation').removeClass("fixNavigation");//sinon on retire la classe "fixNavigation" ? <div id="navigation">
					}
				}
			);			 
		});
	</script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");
/* pChart library inclusions */
 include("../../../pChart/class/pData.class.php");
 include("../../../pChart/class/pDraw.class.php");
 include("../../../pChart/class/pImage.class.php"); 
 include("../../../pChart/class/pScatter.class.php"); 
 
 
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$Semaine=date("W");
?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="AnalyseMSNCT.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Analyse MSN / CT</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:30%;">
			<tr><td height="4" colspan="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; MSN : </td>
				<td width="15%">
					<input onKeyUp="nombre(this)" id="msnRecherche" name="msnRecherche" type="texte" value="<?php if($_POST){echo $_POST['msnRecherche'];}?>" size="5"/>&nbsp;&nbsp;
					<input id="filtrer" name="filtrer" style="background:url(../../../Images/jumelle.png) center no-repeat;" type="submit" value="" size="6"/>&nbsp;&nbsp;
				</td>
			</tr>
			<tr><td height="4" colspan="4"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" style="width:100%;">
			<?php
				if($_POST){
					if($_POST['msnRecherche']<>""){
						//Dossiers lancés pour ce msn / Semaine
						$reqDossiersLances="SELECT CONCAT(WEEK(DateCreation),' ',YEAR(DateCreation)) AS SemaineAnnee, COUNT(Id) AS NbDossier ";
						$reqDossiersLances.="FROM sp_dossier ";
						$reqDossiersLances.="WHERE MSN=".$_POST['msnRecherche']." ";
						$reqDossiersLances.="GROUP BY CONCAT(WEEK(DateCreation),' ',YEAR(DateCreation)) ";
						$reqDossiersLances.="ORDER BY CONCAT(YEAR(DateCreation),' ',WEEK(DateCreation)) ";
						$resultDossiersLances=mysqli_query($bdd,$reqDossiersLances);
						$nbDossiersLances=mysqli_num_rows($resultDossiersLances);
				
						//Volume attendu pour ce msn / Semaine
						$reqVolumeAttendu="SELECT CONCAT(Semaine,' ',Annee) AS SemaineAnnee, Sum(Presence)*2 AS NbVolume "; 
						$reqVolumeAttendu.="FROM sp_planningmsnct ";
						$reqVolumeAttendu.="WHERE MSN=".$_POST['msnRecherche']." ";
						$reqVolumeAttendu.="GROUP BY CONCAT(Semaine,' ',Annee) ";
						$reqVolumeAttendu.="ORDER BY CONCAT(Semaine,' ',Annee) ";
						$resultVolumeAttendu=mysqli_query($bdd,$reqVolumeAttendu);
						$nbVolumeAttendu=mysqli_num_rows($resultVolumeAttendu);
						
						//Liste des semaines
						$reqSemaine="SELECT tab.Semaine, tab.Annee ";
						$reqSemaine.="FROM ";
						$reqSemaine.="((SELECT DISTINCT WEEK(DateCreation) AS Semaine,YEAR(DateCreation) AS Annee ";
						$reqSemaine.="FROM sp_dossier ";
						$reqSemaine.="WHERE MSN=".$_POST['msnRecherche']." ";
						$reqSemaine.="ORDER BY YEAR(DateCreation),WEEK(DateCreation)) ";
						$reqSemaine.="UNION ";
						$reqSemaine.="(SELECT DISTINCT Semaine,Annee ";
						$reqSemaine.="FROM sp_planningmsnct ";
						$reqSemaine.="WHERE MSN=".$_POST['msnRecherche']." ";
						$reqSemaine.="ORDER BY Annee,Semaine)) AS tab ";
						$reqSemaine.="ORDER BY tab.Annee , tab.Semaine ";
						$resultSemaine=mysqli_query($bdd,$reqSemaine);
						$nbSemaine=mysqli_num_rows($resultSemaine);
						
						if($nbSemaine>0){
							$MyData = new pData();  
							$orientationAbscisse = 90;
							
							$arrayVolumeAttendu = array();
							$arrayDossiersLances = array();
							$arraySemaines = array();
							
							while($rowSemaine=mysqli_fetch_array($resultSemaine)){
								$arraySemaines[] = $rowSemaine['Semaine']."/".$rowSemaine['Annee']."      ";
								
								$valeur=0;
								if($nbVolumeAttendu>0){
									mysqli_data_seek($resultVolumeAttendu,0);
									while($rowVolumeAttendu=mysqli_fetch_array($resultVolumeAttendu)){
										if($rowVolumeAttendu['SemaineAnnee']==$rowSemaine['Semaine']." ".$rowSemaine['Annee']){
											$valeur=$rowVolumeAttendu['NbVolume'];
										}
									}
								}
								$arrayVolumeAttendu[] = $valeur;
								
								$valeur=0;
								if($nbDossiersLances>0){
									mysqli_data_seek($resultDossiersLances,0);
									while($rowDossiersLances=mysqli_fetch_array($resultDossiersLances)){
										if($rowDossiersLances['SemaineAnnee']==$rowSemaine['Semaine']." ".$rowSemaine['Annee']){
											$valeur=$rowDossiersLances['NbDossier'];
										}
									}
								}
								$arrayDossiersLances[] = $valeur;
							}
							$MyData->addPoints($arraySemaines,"Semaines");
							$MyData->setSerieDescription("Semaines","Semaines");
							$MyData->setAbscissa("Semaines");
							
							$MyData->addPoints($arrayVolumeAttendu,"Volume attendu");
							$serieSettingsTemp = array("R"=>255,"G"=>37,"B"=>51,"Alpha"=>100);
							$MyData->setPalette("Volume attendu",$serieSettingsTemp);
							$MyData->setSerieTicks("Volume attendu",4);
							$MyData->setAxisName(0,"nb dossiers");
							
							$MyData->addPoints($arrayDossiersLances,"dossiers lancés");
							$serieSettingsTemp = array("R"=>0,"G"=>0,"B"=>200,"Alpha"=>100);
							$MyData->setPalette("dossiers lancés",$serieSettingsTemp);
							$MyData->setAxisName(0,"nb dossiers");
							
							/* Create the pChart object */
							$myPicture = new pImage(1300,381,$MyData);
							$myPicture->drawGradientArea(0,0,1300,381,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
							$myPicture->drawGradientArea(0,0,1300,381,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20)); 
							 
							 /* Turn of Antialiasing */
							 $myPicture->Antialias = FALSE;

							 /* Add a border to the picture */
							 $myPicture->drawRectangle(0,0,1299,380,array("R"=>0,"G"=>0,"B"=>0));

							 /* Set the default font */
							 $myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/Forgotte.ttf","FontSize"=>11));
							 
							 /* Define the chart area */
							 $myPicture->setGraphArea(60,40,1297,300);

							 /* Draw the scale */
							 $scaleSettings = array("LabelRotation"=>$orientationAbscisse,"Mode" => SCALE_MODE_ADDALL_START0,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
							 $myPicture->drawScale($scaleSettings);

							 /* Write the chart legend */
							 $myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

							 /* Turn on shadow computing */ 
							 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

							 /* Draw the chart */
							 $myPicture->drawlinechart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Gradient"=>TRUE,"GradientMode"=>GRADIENT_EFFECT_CAN,"Surrounding"=>30));
							
							/* Titre du graphique */
							$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/verdana.ttf","FontSize"=>11));
							$leTitre = "MSN ".$_POST['msnRecherche'];
							$myPicture->drawText(350,55,$leTitre,array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
							
							 /* Render the picture (choose the best way) */
							 $myPicture->render("pictures2/AnalyseMSN.png");
							 
							echo "<tr><td align='center'>";
							echo "<img src='pictures2/AnalyseMSN.png' />";
							echo "</td></tr>";
						}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>