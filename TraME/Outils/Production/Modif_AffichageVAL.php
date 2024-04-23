<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Validation.php";
			window.close();
		}
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
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$tabChamps=array("Reference","Date","WP","FamilleTache","Tache","Delai","Statut","TempsAlloue","TempsPasse","Preparateur","Controleur","InfosComplementaires","Commentaire","Responsable","RaisonRefus","CommentaireDelai");
$tabIntituleFR = array("Référence","Date du travail","Workpackage","Famille tâche","Tâche","Délai","Statut","Temps alloué","Temps passé","Préparateur","Contrôleur","Infos complementaires","Commentaire","Responsable","Raison du retour","Commentaire délai");
$tabIntituleEN = array("Reference","Date of work","Workpackage","Task family","Task","Delay","Status","Allotted time","Time spent","Manufacturing Engineer","Controller","Further information","Comment","Responsible","Reason for return","Comment delay");
 if($_POST){
	foreach($tabChamps as $value){
		$_SESSION['ChampsVAL_'.$value]=$value."_".$_POST['taille_'.$value]."_".$_POST['visible_'.$value];
		$tabCh=explode("_",$_SESSION['ChampsVAL_'.$value]);
		$taille=$tabCh[1];
		if($taille==""){$taille=0;}
		$req="UPDATE trame_champsaffichage 
			SET Taille=".$taille.", 
			Visible=".$tabCh[2]." 
			WHERE Id_Personne=".$_SESSION['Id_PersonneTR']." 
			AND Page='Validation' 
			AND Champ='".$value."' ";
		$resultTest=mysqli_query($bdd,$req);
	}
	echo "<script>FermerEtRecharger();</script>";
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Modif_AffichageVAL.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATION - COLUMNS";}else{echo "VALIDATION - COLONNES";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
		<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Field";}else{echo "Champ";} ?></td>
		<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Size (%)";}else{echo "Taille (%)";} ?></td>
		<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Visible";}else{echo "Visible";} ?></td>
	</tr>
		<?php
			$i=0;
			$couleur="#b1e1f7";
			
			foreach($tabChamps as $value){
				$tabCh=explode("_",$_SESSION['ChampsVAL_'.$value]);
			?>
				<tr bgcolor="<?php echo $couleur;?>" height="25px">
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo $tabIntituleEN[$i];}else{echo $tabIntituleFR[$i];} ?></td>
					<td> 
						<input onKeyUp="nombre(this)" type="texte" name="taille_<?php echo $value; ?>" size="8" value="<?php echo $tabCh[1]; ?>">
					</td>
					<td>
						<select name="visible_<?php echo $value; ?>">
							<option value="1" <?php if($tabCh[2]==1){echo "selected";} ?>><?php if($_SESSION['Langue']=="EN"){echo "Yes";}else{echo "Oui";}?></option>
							<option value="0" <?php if($tabCh[2]==0){echo "selected";} ?>><?php if($_SESSION['Langue']=="EN"){echo "No";}else{echo "Non";}?></option>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			<?php
				if($couleur=="#b1e1f7"){$couleur="#ffffff";}
				else{$couleur="#b1e1f7";}
				$i++;
			}
		?>
		<tr>
			<td align="center" colspan="5">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modifier";} ?>">
			</td>
			
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>