<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Utilisateur.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=150");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Utilisateur.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=150");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer les accès?')){
				var w=window.open("Ajout_Utilisateur.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
				}
			}
		function OuvreFenetreReini(Id){
			if(window.confirm('Etes-vous sûr de vouloir réinitialiser le mot de passe ?')){
				var w=window.open("Ajout_Utilisateur.php?Mode=R&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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

if(substr($_SESSION['DroitSP'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_Dossier.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Accès utilisateurs</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" colspan="6">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter un utilisateur&nbsp;</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:80%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;Personne</td>
				<td class="EnTeteTableauCompetences" width="10%" >NG/ST</td>
				<td class="EnTeteTableauCompetences" width="10%" >Login</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Support technique</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Chef d'équipe</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Compagnon</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Inspecteur qualité</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Admin</td>
				<td class="EnTeteTableauCompetences" width="16%"style="text-align:center;" >Email</td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Nom,Prenom,Matricule,LoginSP,EmailPro FROM new_rh_etatcivil WHERE LoginSP<>'' ORDER BY Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
					
						$result2=mysqli_query($bdd,"SELECT Id, Droit FROM sp_acces WHERE Id_Prestation=815 AND Id_Personne=".$row['Id']);
						$nbResulta2=mysqli_num_rows($result2);
						if($nbResulta2>0){
							$Ligne2=mysqli_fetch_array($result2);
							$ST="";
							$CE="";
							$Prod="";
							$Qualite="";
							$Admin="";
							if(substr($Ligne2['Droit'],0,1)=='1'){$ST="X";}
							if(substr($Ligne2['Droit'],1,1)=='1'){$CE="X";}
							if(substr($Ligne2['Droit'],2,1)=='1'){$Prod="X";}
							if(substr($Ligne2['Droit'],3,1)=='1'){$Admin="X";}
							if(substr($Ligne2['Droit'],4,1)=='1'){$Qualite="X";}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="16%">&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
									<td width="10%">&nbsp;<?php echo $row['Matricule'];?></td>
									<td width="10%">&nbsp;<?php echo $row['LoginSP'];?></td>
									<td width="10%" align="center"><?php echo $ST;?></td>
									<td width="10%" align="center"><?php echo $CE;?></td>
									<td width="10%" align="center"><?php echo $Prod;?></td>
									<td width="10%" align="center"><?php echo $Qualite;?></td>
									<td width="10%" align="center"><?php echo $Admin;?></td>
									<td width="16%">&nbsp;<?php echo $row['EmailPro'];?></td>
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
									</td>
									<td width="2%" align="center">
									<a href="javascript:OuvreFenetreReini(<?php echo $row['Id']; ?>)">
									<img src='../../../Images/Reinitilisation.png' border='0' alt='Reinitialiser' title='Reinitialiser'>
									</a>
									</td>
								</tr>
							<?php
							if($couleur=="#ffffff"){$couleur="#E1E1D7";}
							else{$couleur="#ffffff";}
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
}
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>