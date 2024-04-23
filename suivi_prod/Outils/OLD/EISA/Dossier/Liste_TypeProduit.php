<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_TypeProduit.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=120");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_TypeProduit.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=120");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_TypeProduit.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
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

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitSP'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_TypeProduit.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des types produits</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:30%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter un type de produit&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:30%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;Libellé</td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle FROM sp_atrtypeproduit WHERE Id_Prestation=463 AND Supprime=false ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="16%">&nbsp;<?php echo $row['Libelle'];?></td>
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
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>