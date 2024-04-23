<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Prestation.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=520");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Prestation.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=520");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_Prestation.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
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
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Administrateur/Prestation.php";

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitTR'],5,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Prestation.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Site list";}else{echo "Liste des prestations";} ?></td>
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
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a site";}else{echo "Ajouter une prestation";} ?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:85%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libellé";} ?></td>
				<td class="EnTeteTableauCompetences" width="14%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Planning";}else{echo "Planning";} ?></td>
				<td class="EnTeteTableauCompetences" width="14%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "FTR objective";}else{echo "Objectif FTR";} ?></td>
				<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Corresponding site (Extranet)";}else{echo "Prestation correspondante (Extranet)";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,Planning,Couleur,ObjectifFTR,
					(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_PrestationExtra) AS PrestaExtra, ";
				$req.="(SELECT COUNT(Id) FROM trame_travaileffectue WHERE trame_travaileffectue.Id_Prestation=trame_prestation.Id) AS NbLigne ";
				$req.="FROM trame_prestation ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						if($_SESSION['Langue']=="EN"){
							$planning="Enabled";
						}
						else{
							$planning="Activé";
						}
						if($row['Planning']==1){
							if($_SESSION['Langue']=="EN"){
								$planning="Deactivated";
							}
							else{
								$planning="Désactivé";
							}
						}
						if($row['Couleur']<>""){
							$couleur=$row['Couleur'];
						}
						else{
							$couleur="#ffffff";
						}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="16%" style="border-bottom:1px #0077aa dotted;">&nbsp;<?php echo $row['Libelle'];?></td>
								<td width="14%" style="border-bottom:1px #0077aa dotted;">&nbsp;<?php echo $planning;?></td>
								<td width="14%" style="border-bottom:1px #0077aa dotted;">&nbsp;<?php echo $row['ObjectifFTR']."%";?></td>
								<td width="20%" style="border-bottom:1px #0077aa dotted;">&nbsp;<?php echo $row['PrestaExtra'];?></td>
								<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
									</a>
								</td>
								<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;" align="center">
									<?php if($row['NbLigne']==0){ ?>
									<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
									</a>
									<?php } ?>
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
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>