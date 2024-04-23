<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Retour.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=120");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Retour.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=120");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_Retour.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
		function OuvreDef(){window.open("pdf.php?Doc=PDF/Definition des catégories","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
	</script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script type="text/javascript">

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

if($_POST){
	if(isset($_POST['submitValider'])){
		$DirFichier="PDF/Definition des catégories.pdf";
		$SrcProblem="";
		if($_FILES['fichier']['name']!=""){
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable";$Problem=1;$NomFichier="";}
			else{
				//On verifie l'extension
				$type_file=strrchr($_FILES['fichier']['name'], '.'); 
				if($type_file !='.pdf')
					{$SrcProblem.="<br>Le fichier doit être au format .pdf";$Problem=1;$NomImage="";}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
						{$SrcProblem.="<br>Le fichier est trop volumineux";$Problem=1;$NomFichier="";}
					else{
						if(!unlink($DirFichier)){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}
						if(!move_uploaded_file($tmp_file,$DirFichier))
							{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";};
					}
				}
			}
		}
		if($SrcProblem<>""){echo $SrcProblem;}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" enctype="multipart/form-data" method="POST" action="Liste_Retour.php">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des types de retours</td>
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
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter un type de retour&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>&nbsp; Définition des retours : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/pdf.gif" alt="pdf" title="pdf"></a>&nbsp;&nbsp;
	</tr>
	<tr style="display:none;">
		<td>
			<input type="hidden" name="MAX_FILE_SIZE" value="30000000">
		</td>
	</tr>
	<tr>
		<td>
			<input name="fichier" type="file">
			<font color="#FF0000" size="-2">Limite de taille du fichier à 3 Mo.</font>
			<input class="Bouton" name="submitValider" type="submit" value='Remplacer'>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:50%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="20%" >&nbsp;Libellé</td>
				<td class="EnTeteTableauCompetences" width="10%" >Statut</td>
				<td class="EnTeteTableauCompetences" width="10%" >Est un retour</td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,Id_Statut,EstRetour FROM sp_olwretour WHERE Id_Prestation=576 AND Supprime=false ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="20%">&nbsp;<?php echo $row['Libelle'];?></td>
								<td width="10%"><?php echo $row['Id_Statut'];?></td>
								<td width="10%"><?php if($row['EstRetour']==0){echo "Non";}else{echo "Oui";}?></td>
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
</table>
</form>

<?php
}
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>