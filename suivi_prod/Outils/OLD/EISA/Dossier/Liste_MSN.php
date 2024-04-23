<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_MSN.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1200,height=500");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_MSN.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1200,height=500");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_MSN.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
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
require("../../../Menu.php");
require("../../Fonctions.php");

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_MSN.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des MSN</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:90%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter un MSN&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:90%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;MSN</td>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;Date moulage</td>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;Date démoulage</td>
				<td class="EnTeteTableauCompetences" width="30%" >&nbsp;Visites clients</td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,MSN,DateMoulage,HeureMoulage,DateDemoulage,HeureDemoulage ";
				$req.="FROM sp_atrmsn WHERE Id_Prestation=463 ORDER BY MSN;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						$req="SELECT sp_atrvisite.Libelle FROM sp_atrmsn_customer LEFT JOIN sp_atrvisite ON sp_atrmsn_customer.Id_Visite = sp_atrvisite.Id WHERE Id_MSN=".$row['Id'];
						$resultVisite=mysqli_query($bdd,$req);
						$nbVisite=mysqli_num_rows($resultVisite);
						$Visites="";
						if ($nbVisite>0){
							while($rowVisite=mysqli_fetch_array($resultVisite)){
								$Visites.=$rowVisite['Libelle']."<br>";
							}
						}
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
						$HeureDemoulage="";
						if(AfficheDateFR($row['DateDemoulage'])<>""){$HeureDemoulage=$row['HeureDemoulage'];}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="16%">&nbsp;<?php echo $row['MSN'];?></td>
								<td width="16%">&nbsp;<?php echo AfficheDateFR($row['DateMoulage'])." ".$row['HeureMoulage'];?></td>
								<td width="16%">&nbsp;<?php echo AfficheDateFR($row['DateDemoulage'])." ".$HeureDemoulage;?></td>
								<td width="20%">&nbsp;<?php echo $Visites;?></td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
									</a>
								</td>
								<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
								<img src='../../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>
								</a>
								</td>
							</tr>
						<?php
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
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>