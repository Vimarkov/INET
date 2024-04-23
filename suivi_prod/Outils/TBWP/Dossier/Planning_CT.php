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

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$Semaine=date("W");
if(substr($_SESSION['DroitSP'],3,1)=='1'){
if($_POST){
	if(isset($_POST['Btn_Ajouter'])){
		if($_POST['CT']<>"" && $_POST['msn']<>"" && $_POST['presence']<>""){
			//Suppression si existe deja
			$req="DELETE FROM sp_planningmsnct ";
			$req.="WHERE Id_Personne=".$_POST['CT']." AND MSN=".$_POST['msn']." AND Semaine=".$_POST['semaine']." AND Annee=".$_POST['annee']." ";
			$result=mysqli_query($bdd,$req);
			
			//Ajout
			$req="INSERT INTO sp_planningmsnct (Id_Personne,MSN,Presence,Semaine,Annee) VALUES ";
			$req.="(".$_POST['CT'].",".$_POST['msn'].",".$_POST['presence'].",".$_POST['semaine'].",".$_POST['annee'].") ";
			$result=mysqli_query($bdd,$req);
			
		}
	}
	elseif(isset($_POST['Btn_Supprimer'])){
		if($_POST['CT']<>"" && $_POST['msn']<>""){
			//Suppression si existe deja
			$req="DELETE FROM sp_planningmsnct ";
			$req.="WHERE Id_Personne=".$_POST['CT']." AND MSN=".$_POST['msn']." AND Semaine=".$_POST['semaine']." AND Annee=".$_POST['annee']." ";
			$result=mysqli_query($bdd,$req);
		}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Planning_CT.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Planning CT / MSN</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="center" style="width:60%;">
				<tr>
					<td width="13%" class="Libelle">&nbsp; Coordinateur technique : </td>
					<td width="15%">
						<select name="CT">
							<option name="" value=""></option>
							<?php
							$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NomPrenom ";
							$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=255 AND SUBSTR(sp_acces.Droit,1,1)='1' ORDER BY Nom, Prenom;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowCT=mysqli_fetch_array($result)){
									$selected="";
									if($_POST){if($_POST['CT']==$rowCT['Id']){$selected="selected";}}
									echo "<option name='".$rowCT['Id']."' value='".$rowCT['Id']."' ".$selected.">".$rowCT['NomPrenom']."</option>";
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="7%" class="Libelle">&nbsp; MSN : </td>
					<td width="15%">
						<input id="msn" onKeyUp="nombre(this)" name="msn" value="<?php if($_POST){echo $_POST['msn'];}?>" size="5">
					</td>
					<td width="7%" class="Libelle">&nbsp; Nbr de jours de présence : </td>
					<td width="15%">
						<input id="presence" onKeyUp="nombre(this)" name="presence" value="5" size="5">&nbsp;
					</td>
				</tr>
				<tr><td height="4" colspan="4"></td></tr>
				<tr>
					<td width="13%" class="Libelle">&nbsp; Semaine : </td>
					<td width="15%">
						<select name="semaine">
							<?php
								for($i=1;$i<=52;$i++){
									$selected="";
									if($_POST){if($_POST['semaine']==$i){$selected="selected";}}
									else{
										if($Semaine==$i){$selected="selected";}
									}
									echo "<option name='".$i."' value='".$i."' ".$selected.">".$i."</option>";
								}
							?>
						</select>
					</td>
					<td width="13%" class="Libelle">&nbsp; Année : </td>
					<td width="15%">
						<select name="annee">
							<?php
								$tabDate = explode('-', $DateJour);
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]-1);
								$Annee = date("Y", $timestamp);
								$selected="";
								if($_POST){if($_POST['annee']==$Annee){$selected="selected";}}
								echo "<option name='".$Annee."' value='".$Annee."' ".$selected.">".$Annee."</option>";
								
								$selected="";
								if($_POST){if($_POST['annee']==date("Y")){$selected="selected";}}
								else{$selected="selected";}
								echo "<option name='".date("Y")."' value='".date("Y")."' ".$selected.">".date("Y")."</option>";
								$tabDate = explode('-', $DateJour);
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]+1);
								$Annee = date("Y", $timestamp);
								$selected="";
								if($_POST){if($_POST['annee']==$Annee){$selected="selected";}}
								echo "<option name='".$Annee."' value='".$Annee."' ".$selected.">".$Annee."</option>";
							?>
						</select>
					</td>
				</tr>
				<tr><td height="4" colspan="4"></td></tr>
				<tr>
				<td align="center" colspan="4">
					<input type="submit" class="Bouton" name="Btn_Ajouter" value="Ajouter"/>&nbsp;&nbsp;
					<input type="submit" class="Bouton" name="Btn_Supprimer" value="Supprimer"/>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:30%;">
			<tr><td height="4" colspan="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Semaine : </td>
				<td width="15%">
					<select name="semaineRecherche" onchange="submit();">
						<?php
							for($i=1;$i<=52;$i++){
								$selected="";
								if($_POST){
									if($_POST['semaineRecherche']==$i){$selected="selected";}
								}
								else{
									if($Semaine==$i){$selected="selected";}
								}
								echo "<option name='".$i."' value='".$i."' ".$selected.">".$i."</option>";
							}
						?>
					</select>
				</td>
				<td width="13%" class="Libelle">&nbsp; Année : </td>
				<td width="15%">
					<input onKeyUp="nombre(this)" id="anneeRecherche" name="anneeRecherche" type="texte" value="<?php if($_POST){echo $_POST['anneeRecherche'];}else{echo date("Y");}?>" size="5"/>&nbsp;&nbsp;
					<input id="filtrer" name="filtrer" style="background:url(../../../Images/jumelle.png) center no-repeat;" type="submit" value="" size="6"/>&nbsp;&nbsp;
				</td>
			</tr>
			<tr><td height="4" colspan="4"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<?php
				$laSemaine=$Semaine;
				$annee=date("Y");
				if($_POST){
					$laSemaine=$_POST['semaineRecherche'];
					$annee=$_POST['anneeRecherche'];
				}
				
				echo "<tr>";
				echo "<td></td>";
				//Liste des MSN pendant cette semaine
				$req="SELECT DISTINCT MSN ";
				$req.="FROM sp_planningmsnct WHERE Semaine=".$laSemaine." AND Annee=".$annee." ORDER BY MSN;";
				$resultMSN=mysqli_query($bdd,$req);
				$nbMSN=mysqli_num_rows($resultMSN);
				if ($nbMSN>0){
					while($rowMSN=mysqli_fetch_array($resultMSN)){
						echo "<td align='center'>".$rowMSN['MSN']."</td>";
					}
				}
				echo "</tr>";
				//Liste des CT pendant cette semaine
				$req="SELECT DISTINCT Id_Personne, ";
				$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=sp_planningmsnct.Id_Personne) AS CT ";
				$req.="FROM sp_planningmsnct WHERE Semaine=".$laSemaine." AND Annee=".$annee." ORDER BY CT;";
				$resultCT=mysqli_query($bdd,$req);
				$nbCT=mysqli_num_rows($resultCT);
				if ($nbCT>0){
					$couleur="#E1E1D7";
					while($rowCT=mysqli_fetch_array($resultCT)){
						echo "<tr bgcolor='".$couleur."'>";
						echo "<td align='left'>".$rowCT['CT']."</td>";
						if ($nbMSN>0){
							$reqPresence="SELECT MSN,Presence ";
							$reqPresence.="FROM sp_planningmsnct ";
							$reqPresence.="WHERE Id_Personne=".$rowCT['Id_Personne']." AND Semaine=".$laSemaine." AND Annee=".$annee." ;";
							$resultPresence=mysqli_query($bdd,$reqPresence);
							$nbPresence=mysqli_num_rows($resultPresence);
							
							mysqli_data_seek($resultMSN,0);
							while($rowMSN=mysqli_fetch_array($resultMSN)){
								mysqli_data_seek($resultPresence,0);
								if($nbPresence>0){
									$trouve=false;
									while($rowPresence=mysqli_fetch_array($resultPresence)){
										if($rowPresence['MSN']==$rowMSN['MSN']){
											
											echo "<td align='center'>".str_replace(".0","",$rowPresence['Presence'])."</td>";
											$trouve=true;
											break;
										}
									}
									if($trouve==false){
										echo "<td></td>";
									}
								}
								else{
									echo "<td></td>";
								}
							}
						}
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
						echo "</tr>";
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