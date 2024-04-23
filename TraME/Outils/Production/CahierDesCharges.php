<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(wp){
			var w=window.open("Ajout_CahierDesCharges.php?Mode=A&Id=0&WP="+wp,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=760,height=200");
			w.focus();
			}
		function OuvreFenetreModif(Id,wp){
			var w=window.open("Ajout_CahierDesCharges.php?Mode=M&Id="+Id+"&WP="+wp,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=760,height=200");
			w.focus();
			}
		function OuvreFenetreSuppr(Id,wp){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_CahierDesCharges.php?Mode=S&Id="+Id+"&WP="+wp,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
		function Excel(WP){
			var w=window.open("Extract_CDC.php?WP="+WP,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
	</script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
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
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Production/CahierDesCharges.php";

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="CahierDesCharges.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Specifications";}else{echo "Liste des cahiers des charges";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" class="GeneralInfo" style="width:100%;">
				<tr>
					<td width="8%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
					<td align="left">
						<select id="workpackage" name="workpackage" style="width:150px;" onchange="submit()">
						<?php
							$dateDebut="";
							$dateFin="";
							echo"<option name='0' value='0'></option>";
							$req="SELECT Id, Libelle, DateDebut, DateFin FROM trame_wp WHERE Supprime=false AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle  ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$WP=0;
							if ($nbResulta>0){
								$i=0;
								while($rowWP=mysqli_fetch_array($result)){
									$selected="";
									if($_POST){
										if($_POST['workpackage']==$rowWP['Id']){
											$WP=$rowWP['Id'];
											$selected="selected";
											$dateDebut=" Date de début : ".AfficheDateFR($rowWP['DateDebut']);
											$dateFin=" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Date de fin : ".AfficheDateFR($rowWP['DateFin']);
										}
									}
									elseif(isset($_GET['Id'])){
										if($_GET['Id']==$rowWP['Id']){
											$WP=$rowWP['Id'];
											$selected="selected";
											$dateDebut=" Date de début : ".AfficheDateFR($rowWP['DateDebut']);
											$dateFin=" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date de fin : ".AfficheDateFR($rowWP['DateFin']);
										}
									}
									echo "<option value='".$rowWP['Id']."' ".$selected.">".$rowWP['Libelle']."</option>";
									echo "<script>Liste_WP[".$i."] = new Array('".$rowWP['Id']."','".addslashes($rowWP['Libelle'])."');</script>\n";
									$i+=1;
								}
							}
						?>	
						</select>
						<?php
						echo $dateDebut.$dateFin;	
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<?php
					if($WP>0){
				?>
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout(<?php echo $WP;?>)'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add specification";}else{echo "Ajouter un cahier des charges";} ?>&nbsp;</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
		<a style="text-decoration:none;" class="Bouton" href="javascript:Excel(<?php echo $WP;?>)">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
				<?php
					}
				?>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "Unité d'oeuvre";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Technical domain";}else{echo "Domaine technique";} ?></td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Type of work";}else{echo "Type de travail";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Complexity";}else{echo "Complexité";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Volume";}else{echo "Volume";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OTD (%)";}else{echo "OTD (%)";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OQD (%)";}else{echo "OQD (%)";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Volume,OTD,OQD,TypeTravail,Complexite,";
				$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_uo_cdc.Id_DT) AS DT,";
				$req.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_uo_cdc.Id_WP) AS WP,";
				$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_uo_cdc.Id_UO) AS UO ";
				$req.="FROM trame_uo_cdc WHERE Id_WP=".$WP." ORDER BY UO;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				
				if ($nbResulta>0){
					$couleur="#E1E1D7";
					while($row=mysqli_fetch_array($result)){
					
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="20%">&nbsp;<?php echo stripslashes(str_replace("\\","",$row['UO']));?></td>
								<td width="15%"><?php echo $row['DT'];?></td>
								<td width="12%"><?php echo $row['TypeTravail'];?></td>
								<td width="15%"><?php echo $row['Complexite'];?></td>
								<td width="10%"><?php echo $row['Volume'];?></td>
								<td width="10%"><?php echo $row['OTD'];?></td>
								<td width="10%"><?php echo $row['OQD'];?></td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $WP;?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
									</a>
								</td>
								<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $WP;?>)">
								<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
								</td>
							</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
}
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>