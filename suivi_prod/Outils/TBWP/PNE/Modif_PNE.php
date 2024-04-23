<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Liste_PNE.php";
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
		Liste_Poste = new Array();
		function Recharge_Poste(){
			var i;
			var sel="";
			var isElement = false;
			sel ="<select id='poste' size='1' name='poste'>";
			for(i=0;i<Liste_Poste.length;i++){
				if (Liste_Poste[i][1]==document.getElementById('pole').value){
					sel= sel + "<option value='"+Liste_Poste[i][0];
					sel= sel + "'>"+Liste_Poste[i][0]+"</option>";
					isElement = true;
				}
			}
			if(isElement == false){sel= sel + "<option value='0' selected></option>";}
			sel =sel + "</select>";
			document.getElementById('postes').innerHTML=sel;
		}
		function VerifChamps(){
			if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
			if(formulaire.numFormA.value==''){alert('Vous n\'avez pas renseigné le n° Form A.');return false;}
			if(formulaire.pole.value=='0'){alert('Vous n\'avez pas renseigné le pôle.');return false;}
			if(formulaire.poste.value=='0'){alert('Vous n\'avez pas le poste.');return false;}
			return true;
		}
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		$dateIntervention="0001-01-01";
		if($_POST['dateIntervention']<>""){$dateIntervention=TrsfDate_($_POST['dateIntervention']);}
		$nbRetouche=0;
		if($_POST['nbRetouche']<>""){$nbRetouche=$_POST['nbRetouche'];}
		if($_POST['idPNE']==0){
			//Ajout PNE
			$req="INSERT INTO sp_pne (NumFormA,Id_Pole,Poste,Id_Zone,MSN,Id_Compagnon,NbRetouche,NumEIC,DateIntervention,Vacation,Id_Createur,DateCreation,Commentaire) VALUES ";
			$req.="('".addslashes($_POST['numFormA'])."',".$_POST['pole'].",'".addslashes($_POST['poste'])."',".$_POST['zone'].",".$_POST['msn'].",";
			$req.=$_POST['compagnon'].",".$nbRetouche.",'".addslashes($_POST['numEIC'])."','".$dateIntervention."','".$_POST['vacation']."',";
			$req.=$_POST['idPersonne'].",'".$DateJour."','".addslashes($_POST['commentaire'])."')";
			echo $req;
			$resultInsert=mysqli_query($bdd,$req);
		}
		else{
			//Mise à jour PNE
			$req="UPDATE sp_pne SET ";
			$req.="NumFormA='".addslashes($_POST['numFormA'])."',";
			$req.="Id_Pole=".$_POST['pole'].",";
			$req.="Poste='".addslashes($_POST['poste'])."',";
			$req.="Id_Zone=".$_POST['zone'].",";
			$req.="MSN=".$_POST['msn'].",";
			$req.="Id_Compagnon=".$_POST['compagnon'].",";
			$req.="NbRetouche=".$nbRetouche.",";
			$req.="NumEIC='".addslashes($_POST['numEIC'])."',";
			$req.="DateIntervention='".$dateIntervention."',";
			$req.="Vacation='".$_POST['vacation']."',";
			$req.="Commentaire='".addslashes($_POST['commentaire'])."'";
			$req.="WHERE Id=".$_POST['idPNE']."";
			$resultUpdate=mysqli_query($bdd,$req);
			
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$IdPersonne=$_GET['Id_Personne'];
	$PNE=$_GET['Id'];
	$titre="";
	if($_GET['Mode']=="A"){
		$titre="Ajouter un poste neutre";
	}
	elseif($_GET['Mode']=="M"){
		$titre="Modifier un poste neutre";
		$req="SELECT sp_pne.Id,sp_pne.NumFormA,sp_pne.MSN,sp_pne.NbRetouche,sp_pne.NumEIC,sp_pne.DateIntervention,sp_pne.Vacation,sp_pne.DateCreation,sp_pne.Poste,";
		$req.="sp_pne.Id_Pole,sp_pne.Id_Createur,sp_pne.Id_Zone,";
		$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_pne.Id_Createur) AS Createur, ";
		$req.="sp_pne.Id_Compagnon,sp_pne.Commentaire ";
		$req.="FROM sp_pne ";
		$req.="WHERE sp_pne.Id=".$PNE;
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
	}
	elseif($_GET['Mode']=="S"){
		//Suppression du PNE
		$req="DELETE FROM sp_pne WHERE Id=".$PNE;
		$resultSuppr=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Modif_PNE.php" onSubmit="return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php echo $titre;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr style="display:none;"><td><input type="text" name="idPNE" value="<?php echo $PNE;?>"></td></tr>
			<tr style="display:none;"><td><input type="text" name="idPersonne" value="<?php echo $IdPersonne;?>"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input onKeyUp="nombre(this)" id="msn" name="msn" size="5" value="<?php if($_GET["Mode"]=="M"){echo $row['MSN'];}?>"></td>
				</td>
				<td width="13%" class="Libelle">&nbsp; N° Form A : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input id="numFormA" name="numFormA" value="<?php if($_GET["Mode"]=="M"){echo $row['NumFormA'];}?>"></td>
				</td>
				<td colspan="2" align="left">
				</td>
				<td width="20%"></td>

			</tr>
			<?php
				if($_GET["Mode"]=="M"){
			?>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="13%" class="Libelle">&nbsp; Créateur : </td>
					<td width="20%">
						<?php echo $row['Createur'];?>
					</td>
					<td width="13%" class="Libelle">&nbsp; Date de création : </td>
					<td width="20%">
						<?php echo $row['DateCreation'];?>
					</td>
					<td colspan="2" align="left">
					</td>
					<td width="20%"></td>
				</tr>
			<?php
				}
			?>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Pôle : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<select id="pole" name="pole" onchange="Recharge_Poste();">
						<option name="0" value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM new_competences_pole WHERE (Id IN (1,2,3,5,6,42) AND Actif=0 AND Id_Prestation=255) OR Id=176 ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowPole=mysqli_fetch_array($result)){
									$selected="";
									if($_GET["Mode"]=="M"){
										if($rowPole['Id']==$row['Id_Pole']){$selected="selected";}
									}
									echo "<option name='".$rowPole['Id']."' value='".$rowPole['Id']."' ".$selected.">".$rowPole['Libelle']."</option>";
								}
							}
						?>
					</select>
				</td>
				<td width="13%" class="Libelle">&nbsp; Poste : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<div id="postes">
						<select id="poste" name="poste">
							<option name="0" value="0"></option>
							<?php
								$req="SELECT Poste,Id_Pole FROM sp_poste_pole;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowPoste=mysqli_fetch_array($result)){
										$selected="";
										if($_GET["Mode"]=="M"){
											if($rowPoste['Poste']==$row['Poste']){$selected="selected";}
											if($row['Id_Pole']==$rowPoste['Id_Pole']){
												echo "<option name='".$rowPoste['Poste']."' value='".$rowPoste['Poste']."' ".$selected.">".$rowPoste['Poste']."</option>";
											}
										}
										echo "<script>Liste_Poste[".$i."] = new Array('".$rowPoste['Poste']."','".$rowPoste['Id_Pole']."');</script>\n";
										$i++;
									}
								}
							?>
						</select>
					</div>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Zone de travail : </td>
				<td width='20%'>
					<select id='zone' name='zone' ".$disabled.">
						<option name='0' value='0'></option>
						<?php
						$req="SELECT Id,Libelle FROM sp_zonedetravail ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowZone=mysqli_fetch_array($result)){
								$selected="";
								if($_GET["Mode"]=="M"){
									if($row['Id_Zone']==$rowZone['Id']){$selected="selected";}
								}
								echo "<option name='".$rowZone['Id']."' value='".$rowZone['Id']."' ".$selected.">".$rowZone['Libelle']."</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4" colspan="6"></td></tr>
			<tr>
				<td width='13%' class='Libelle' valign="top">&nbsp; Compagnon : </td>
				<td width='20%' valign="top">
					<select id="compagnon" id="compagnon" name="compagnon" style="width:130px;">
						<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
						$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=255 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowPersonne=mysqli_fetch_array($result)){
								if($_GET["Mode"]=="M"){
									if($row['Id_Compagnon']==$rowPersonne['Id']){$selected="selected";}
								}
								echo "<option name='".$rowPersonne['Id']."' value='".$rowPersonne['Id']."' ".$selected.">".$rowPersonne['Nom']." ".$rowPersonne['Prenom']."</option>";
							}
						}
						?>
					</select>
				</td>
				<td width="13%" class="Libelle" valign="top">&nbsp; Nb. retouches : <br>&nbsp; (1 retouche = 25IM) </td>
				<td width="20%" valign="top">
					<input onKeyUp="nombre(this)" id="nbRetouche" name="nbRetouche" size="5" value="<?php if($_GET["Mode"]=="M"){echo $row['NbRetouche'];}?>"></td>
				</td>
				<td width="13%" class="Libelle" valign="top">&nbsp; N° d'eic : </td>
				<td width="20%" valign="top">
					<input id="numEIC" name="numEIC" value="<?php if($_GET["Mode"]=="M"){echo $row['NumEIC'];}?>"></td>
				</td>
			</tr>
			<tr><td height="4" colspan="6"></td></tr>
			<tr>
				<td width="13%" class="Libelle" valign="top">&nbsp; Date intervention : </td>
				<td width="20%" valign="top">
					<input type="date" style="text-align:center;" id="dateIntervention" name="dateIntervention" size="15" value="<?php if($_GET["Mode"]=="M"){echo AfficheDateFR($row['DateIntervention']);}?>">
				</td>
				<td width="13%" class="Libelle" valign="top">&nbsp; Vacation : </td>
				<td width="20%" valign="top">
					<select id="vacation" name="vacation">
						<option name="" value=""></option>
						<option name="J" value="J" <?php if($_GET["Mode"]=="M"){if($row['Vacation']=="J"){echo "selected";}} ?>>Jour</option>
						<option name="S" value="S" <?php if($_GET["Mode"]=="M"){if($row['Vacation']=="S"){echo "selected";}} ?>>Soir</option>
						<option name="N" value="N" <?php if($_GET["Mode"]=="M"){if($row['Vacation']=="N"){echo "selected";}} ?>>Nuit</option>
						<option name="VSD Jour" value="VSD Jour" <?php if($_GET["Mode"]=="M"){if($row['Vacation']=="VSD Jour"){echo "selected";}} ?>>VSD Jour</option>
						<option name="VSD Nuit" value="VSD Nuit" <?php if($_GET["Mode"]=="M"){if($row['Vacation']=="VSD Nuit"){echo "selected";}} ?>>VSD Nuit</option>
					</select>
				</td>
				<td width="13%" class="Libelle" valign="top">&nbsp; Commentaire : </td>
				<td width="20%" valign="top">
					<textarea id="commentaire" name="commentaire" rows="5" cols="30" style="resize:none;"><?php if($_GET["Mode"]=="M"){echo $row['Commentaire'];}?></textarea>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires à remplir</td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
		?>
		<td colspan="6" align="center"><input class="Bouton" type="submit" name="btnEnregistrer" value="Enregistrer"></td>
		<?php
		}
		?>
	</tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>