<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../JS/Wickedpicker/stylesheets/wickedpicker.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../../JS/Wickedpicker/src/wickedpicker.js"></script>
	<script language="javascript" src="Dossier.js"></script>
	<script>
		function OuvreDef(){window.open("pdf.php?Doc=PDF/Definition des catégories","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
		function AfficherSubmit(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Enregistrer').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Enregistrer').innerHTML="";
		}
	</script>
	<script type="text/javascript">
		$(function(){
			$('.timepicker-two').wickedpicker({twentyFour: true});
		});
	</script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");

//Verifier si Google CHROME (true) ou Autre (false)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$HeureJour = date("H:i:s");
$modePoste = 0;
$modeDuplication=0;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		$modePoste = 1;
		//Ajout du dossier
		$req="INSERT INTO sp_olwdossier (Id_Prestation,Id_Personne,MSN,Id_Client,Reference,";
		$req.="SectionACP,CaecACP,Priorite,Titre,DateCreation,TAI_RestantACP,";
		$req.="Systeme,Structure,Metal,Mastic,Peinture,Id_ZoneDeTravail,Origine,NumOrigine,Id_Statut,Avancement,Id_Retour,CommentaireZICIA)";
		$req.=" VALUES (379,".$_SESSION['Id_PersonneSP'].",".$_POST['msn'].",".$_POST['client'].",'".addslashes($_POST['numDossier'])."',";
		$req.="'".$_POST['section']."','".$_POST['caec']."',".$_POST['priorite'].",'".addslashes($_POST['titre'])."',";
		$req.="'".$DateJour."',".$_POST['tai'].",";
		if(isset($_POST['Systeme'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Structure'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Metal'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Mastic'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Peinture'])){$req.="1,";}else{$req.="0,";}
		$req.="".$_POST['zone'].",'".$_POST['origine']."','".addslashes($_POST['numOrigine'])."',";
		if(substr($_SESSION['DroitSP'],4,1)=='1'){
			if($_POST['statutQualite']<>"0"){$req.="'".$_POST['statutQualite']."',0,".$_POST['retourQualite'].",";}
			else{$req.="'".$_POST['statutProd']."',".$_POST['avancementProd'].",".$_POST['retourProd'].",";}
		}
		elseif(substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.="'".$_POST['statutProd']."',".$_POST['avancementProd'].",".$_POST['retourProd'].",";
		}
		else{
			$req.="'',0,0,";
		}
		$req.="'".addslashes($_POST['commentaireZI'])."')";
		$resultAjour=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		if($IdCree<>0){
			//Ajout ATA/Sous-ATA
			$tabATA = explode(";",$_POST['ata_sousata']);
			foreach($tabATA as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO sp_olwdossier_ata (Id_Dossier,ATA,SousATA) VALUES (".$IdCree.",".$tab2[0].",".$tab2[1].")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
			
			//Ajout de la fiche d'intervention
			$req="INSERT INTO sp_olwficheintervention (";
			$req.="Id_Dossier,Id_Createur,DateCreation,PosteAvionACP,Id_Pole,DeposeRepose,PieceAuPoste,NumFI,TravailRealise,Commentaire";
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",DateIntervention,Vacation,TempsObjectif";
			}
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",Id_StatutPROD,Avancement,DateCreationPROD,Id_RetourPROD,Id_PROD,CommentairePROD";
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.=",DateInterventionQ,VacationQ,Id_StatutQUALITE,DateCreationQUALITE,Id_RetourQUALITE,Id_QUALITE,CommentaireQUALITE";
			}
			$req.=") VALUES (";
			
			$req.=$IdCree.",".$_SESSION['Id_PersonneSP'].",'".$DateJour."','".addslashes($_POST['poste'])."',".$_POST['pole'].",".$_POST['deposeRepose'].",";
			$req.="'".addslashes($_POST['pieceauposte'])."','".addslashes($_POST['numFI'])."','".addslashes($_POST['travailRealise'])."','".addslashes($_POST['commentaire'])."'";
			
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",";			
				$temps=0;
				if($_POST['tempsObjectif']<>""){$temps=$_POST['tempsObjectif'];}
				$req.= "'".TrsfDate_($_POST['dateIntervention'])."','".$_POST['vacation']."',".$temps."";
			}
			
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				if($_POST['statutProd']<>"0"){
					$req.= ",'".$_POST['statutProd']."',".$_POST['avancementProd'].",'".$DateJour."',".$_POST['retourProd'].",".$_SESSION['Id_PersonneSP'].",'".addslashes($_POST['commentairePROD'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,0,'".addslashes($_POST['commentairePROD'])."'";
				}
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.= ",'".TrsfDate_($_POST['dateInterventionQ'])."','".$_POST['vacationQ']."'";
				$Id_IQ=0;
				if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
				else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
				if($_POST['statutQualite']<>"0"){
					$req.= ",'".$_POST['statutQualite']."','".$DateJour."',".$_POST['retourQualite'].",".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
			}
			
			$req.= "); ";
			$resultAjour=mysqli_query($bdd,$req);
			$IdFICree = mysqli_insert_id($bdd);
			
			if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
				
				//Ajout des ECME
				$tabECME = explode(";",$_POST['ECME']);
				foreach($tabECME as $valeur){
					 if($valeur<>""){
						$tab2 = explode("_",$valeur);
						$req="INSERT INTO sp_olwfi_ecme (Id_FI,ECME) VALUES (".$IdFICree.",'".$tab2[0]."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				if(substr($_SESSION['DroitSP'],1,1)=='1'){
					//Ajout des AIPI/AIPS
					$tabAIPI = explode(";",$_POST['lesAIPI']);
					foreach($tabAIPI as $valeur){
						 if($valeur<>""){
							$req="INSERT INTO sp_olwfi_aipi (Id_FI,Id_Qualification) VALUES (".$IdFICree.",".$valeur.")";
							$resultAjour=mysqli_query($bdd,$req);
						 }
					}
				
					//Ajout des compagnons
					$tabCompagnon = explode(";",$_POST['travailEffectue']);
					foreach($tabCompagnon as $valeur){
						 if($valeur<>""){
							$tab2 = explode("C_",$valeur);
							$req="INSERT INTO sp_olwfi_travaileffectue (Id_FI,Id_Personne,TempsPasse) VALUES (".$IdFICree.",".$tab2[0].",".$tab2[1].")";
							$resultAjour=mysqli_query($bdd,$req);
						 }
					}
					
					//Ajout des ingrédients
					$tabIngredient = explode(";",$_POST['Ingredient']);
					foreach($tabIngredient as $valeur){
						 if($valeur<>""){
							$tab2 = explode("_",$valeur);
							$req="INSERT INTO sp_olwfi_ingredient (Id_FI,Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique) VALUES (";
							$req.=$IdFICree.",".$tab2[0].",'".$tab2[1]."','".TrsfDate_($tab2[2])."','".$tab2[3]."')";
							$resultAjour=mysqli_query($bdd,$req);
						 }
					}
				}
			}
		}
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			echo "<script>GenererFicheSuiveuse(".$IdCree.")</script>";
		}
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Ajouter_DossierSansACP.php" onSubmit="return VerifChamps()">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Planifier un nouveau dossier <font color="red"></font></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	if($modePoste==1){
		echo "<tr><td colspan='6' align='center' style='color:red;'>Le dossier a été créé et planifié</td></tr>";
	}
	?>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS DOSSIER
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr style="display:none;"><td><input id="idDossier" name="idDossier" value="0"  readonly="readonly"></td></tr>
		<?php
		$visible="style='display:none;'";
		$read="";
		$disabled="";
		$etoile="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
		if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0' && substr($_SESSION['DroitSP'],4,1)=='0'){
			$read="readonly='readonly'";
			$disabled="disabled='disabled'";
			$etoile="";
		}
		$etoile2="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
		?>
		<tr>
			<td width="13%" class="Libelle">&nbsp; N° dossier : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="15%">
				<input type="texte" id="numDossier" name="numDossier" size="15"/>
			</td>
			<td width='13%' class='Libelle'>&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width='15%'><input onKeyUp='nombre(this)' id='msn' name='msn' value='' size='5'></td>
			<td width='13%' class='Libelle'>&nbsp; Client : </td>
			<td width='15%'>
				<select id="client" name="client">
					<option name='0' value='0'></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_client WHERE Id_Prestation=379 AND Supprime=false ORDER BY Libelle";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowClient=mysqli_fetch_array($result)){
								$selected="";
								echo "<option name='".$rowClient['Id']."' value='".$rowClient['Id']."' ".$selected.">".$rowClient['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<?php
			//Vérification si le dossier n'existe pas déjà dans sp_olwdossier
			$req="SELECT Id,Reference FROM sp_olwdossier WHERE Id_Prestation=379 ";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_Reference[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['Reference']."');</script>\n";
					$i+=1;
				}
			}
		?>
		<tr><td height='4'></td></tr>
		<tr>
			<td width="13%" class="Libelle">&nbsp; Section : <?php echo $etoile2; ?></td>
			<td width='15%'>
				<select id="section" name="section">
					<option value=""></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_olwsection WHERE Id_Prestation=379 AND Supprime=false ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowSection=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$rowSection['Libelle']."' ".$selected.">".$rowSection['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		<?php
			echo "<td width='13%' class='Libelle'>&nbsp; Titre : ".$etoile."</td><td width='15%' colspan='3'>";
			?>
				<input id="titre" name="titre" value="" style="width: 80%;" <?php echo $read;?>>
			</td>
		</tr>
		<?php
		echo "<tr style='display:none;'><td><input id='droit' name='droit' value='".$_SESSION['DroitSP']."'  readonly='readonly'></td></tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
		echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Priorité : ".$etoile."</td>";
		echo "<td width='15%' valign='top'>";
		?>
			<select id="priorite" name="priorite" <?php echo $disabled;?>>
				<option value="1">Low</option>
				<option value="2">Medium</option>
				<option value="3">High</option>
			</select>
		<?php
		echo "</td>";
		echo "<td width='13%' class='Libelle'>&nbsp; Tps restant (h) : </td>";
		echo "<td width='20%'><input onKeyUp='nombre(this)' id='tai' name='tai' value='' size='8' ></td>";
		echo "<td width='13%' class='Libelle'>&nbsp; CA/EC : </td><td width='15%'><input id='caec' name='caec' value='' size='8'></td>";
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
		echo "<td width='13%' class='Libelle'>&nbsp; Origine : </td><td width='20%'>";
			?>
			<select id="origine" name="origine" onchange="AfficherDepose();" <?php echo $disabled;?>>
				<option name='' value=''></option>
				<option name='Admin' value='Admin'>Admin</option>
				<option name='DA' value='DA'>DA</option>
				<option name='NC' value='NC'>NC</option>
				<option name='PNE' value='PNE'>PNE</option>
			</select>
			<?php
		echo "</td>";
		echo "<td width='13%'class='Libelle'>&nbsp; N° origine : </td><td width='20%'>";
		?>
			<input id='numOrigine' name='numOrigine' value='' <?php echo $read;?>></td>
		<?php
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
			echo "<td width='13%' class='Libelle'>&nbsp; Zone de travail : ".$etoile."</td><td width='15%'>";
			echo"<select id='zone' name='zone' ".$disabled.">";
				echo"<option name='0' value='0'></option>";
				$req="SELECT Id,Libelle FROM sp_olwzonedetravail  WHERE Id_Prestation=379 AND Supprime=false ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($rowZone=mysqli_fetch_array($result)){
						$selected="";
						echo "<option name='".$rowZone['Id']."' value='".$rowZone['Id']."' ".$selected.">".$rowZone['Libelle']."</option>";
					}
				}
			echo"</select>";
			echo "</td>";
			echo "<td width='13%' class='Libelle'>&nbsp; Commentaire zone : </td><td width='15%' colspan='3'>";
			?>
				<input id="commentaireZI" name="commentaireZI" value="" style="width: 80%;" <?php echo $read;?>>
			<?php
			echo "</td>";
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
		echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Compétence(s) : ".$etoile."</td>";
		echo "<td width='15%'>";
		echo "<table width='100%' cellpadding='0' cellspacing='0' align='left'>";
		?>
			<tr>
				<td><input type="checkbox" id="Mastic" name="Mastic" value="Mastic" <?php echo $disabled;?> >Mastic &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Structure" name="Structure" value="Structure" <?php echo $disabled;?>>Structure &nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="Metal" name="Metal" value="Metal" <?php echo $disabled;?> >Metal &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Systeme" name="Systeme" value="Systeme" <?php echo $disabled;?>>Systeme &nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="Peinture" name="Peinture" value="Peinture" <?php echo $disabled;?>>Peinture &nbsp;&nbsp;</td>
				
			</tr>
		<?php
		echo "</table>";
		echo "</td>";
		echo "<td width='13%' class='Libelle'>";
				echo "<table cellpadding='0' cellspacing='0' style='-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;'>";
				echo "<tr>";
				echo "<td colspan='2'>&nbsp; Liste des ATA/Sous-ATA : &nbsp;".$etoile."";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0'>";
				echo "&nbsp; ATA : ";
				echo "</td>";
				echo "<td bgcolor='#e4e7f0'>";
				echo"<select name='ata' id='ata' onchange='Recharge_SousATA();' onkeypress='if(event.keyCode == 13)Ajouter()'>";
					echo"<option name='' value=''></option>";
					$req="SELECT DISTINCT ATA FROM sp_atasousata ORDER BY ATA;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowATA=mysqli_fetch_array($result)){
							echo "<option name='".$rowATA['ATA']."' value='".$rowATA['ATA']."'>".$rowATA['ATA']."</option>";
						}
					}
				echo"</select>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor='#e4e7f0'>&nbsp;Sous-ATA : </td>";
					echo "<td bgcolor='#e4e7f0'>";
						echo "<div id='sousatas'>";
							echo"<select id='sousata' name='sousata' onkeypress='if(event.keyCode == 13)Ajouter()'>";
								echo"<option value=''></option>";
								$req="SELECT ATA, SousATA FROM sp_atasousata ORDER BY ATA, SousATA;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowATA=mysqli_fetch_array($result)){
										echo "<script>Liste_SousATA[".$i."] = new Array('".$rowATA['ATA']."','".$rowATA['SousATA']."');</script>\n";
										$i+=1;
									}
								}
							echo"</select>";
						echo "</div>";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>";
				if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
					echo "<a style='text-decoration:none;' class='Bouton' href='javascript:Ajouter()'>&nbsp;Ajouter&nbsp;</a>";
				}
				echo "</td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "<td width='15%' valign='top'><table id='tab_ATA' width='100%' cellpadding='0' cellspacing='0'>";
			echo "<tr><td class='Libelle'>ATA</td><td class='Libelle'>Sous-ATA</td><td></td></tr>";
			$listeATA="";
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "<tr style='display:none;'><td><input id='ata_sousata' name='ata_sousata' value='".$listeATA."'  readonly='readonly'></td></tr>";
		?>
		<tr><td height="4"></td></tr>
		<?php
		$nbTempsDossier=0;
		echo "<tr><td width='13%' valign='top' class='Libelle'>&nbsp; Temps passé : </td><td width='15%' valign='top'><input type='text' size='4' style='border:none' name='tpsDossier' id='tpsDossier' value='".$nbTempsDossier."' readonly='readonly'/></td>";
		?>
		</tr>
		<tr><td height="8"></td></tr>
		</table></td></tr>
		<tr><td height="4"></td></tr>
		<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS INTERVENTION</td>
			</tr>
			<tr>
				<td colspan="2" width="25%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Poste avion : </td>
							<td width="20%">
								<select id="poste" name="poste" <?php echo $disabled;?>>
									<option name="" value=""></option>
								<?php
									$IdPole=0;
									$poste="";
									$req="SELECT Id,Libelle FROM sp_poste WHERE Id_Prestation=379 AND Supprime=false ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowPoste=mysqli_fetch_array($result)){
											$selected="";
											echo "<option value='".$rowPoste['Libelle']."' ".$selected.">".$rowPoste['Libelle']."</option>";
										}
									}
								?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Pôle : <?php echo $etoile;?></td>
							<td width="20%">
								<select id="pole" name="pole" <?php echo $disabled;?>>
									<option name="0" value="0"></option>
									<?php
										$req="SELECT Id, Libelle FROM sp_pole WHERE Id_Prestation=379 ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowPole=mysqli_fetch_array($result)){
												$selected="";
												echo "<option name='".$rowPole['Id']."' value='".$rowPole['Id']."' ".$selected.">".$rowPole['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr class="depose" style="display:none;"><td height="4" colspan="2"></td></tr>
						<tr class="depose" style="display:none;">
							<td width="13%" class="Libelle">
								&nbsp; Dépose <?php echo $etoile;?> <input type="radio" id='deposeRepose' name='deposeRepose' value="0" checked>
							</td>
							<td class="Libelle">
								&nbsp; Repose <input type="radio" id='deposeRepose' name='deposeRepose' value="1">
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" id="LibellePieceauposte" class="Libelle">&nbsp; Pièce à retirer <br> &nbsp; au poste : </td>
							<td width="20%">
								<select id="pieceauposte" name="pieceauposte" <?php echo $disabled;?>>
									<option value=""></option>
									<option value="Chariot de DA">Chariot de DA</option>
									<option value="K943">K943</option>
									<option value="Station livraison">Station livraison</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class='Libelle'>&nbsp; N° FI : </td>
							<td width="20%">
								<input id="numFI" name="numFI" value="">
							</td>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleTravailRealise" class="Libelle" valign="center">&nbsp; Travail à réaliser : <?php echo $etoile;?></td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="travailRealise" name="travailRealise" rows="3" cols="45" style="resize:none;" <?php echo $read;?>><?php if($modeDuplication==1){echo $_POST['travailRealise'];}?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleCommentaire" class="Libelle" valign="center">&nbsp; Commentaire : </td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="commentaire" name="commentaire" rows="3" cols="45" style="resize:none;" <?php echo $read;?>></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
					</table>
				</td>
				<?php
					$readSTCE="";
					$disabledSTCE="";
					$readCE="";
					$disabledCE="";
					$readIQ="";
					$disabledIQ="";
					$etoileSTCE="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
					if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){
						$readSTCE="readonly='readonly'";
						$disabledSTCE="disabled='disabled'";
						$etoileSTCE="";
					}
					if(substr($_SESSION['DroitSP'],1,1)=='0'){
						$readCE="readonly='readonly'";
						$disabledCE="disabled='disabled'";
						$etoileCE="";
					}
					if(substr($_SESSION['DroitSP'],4,1)=='0'){
						$readIQ="readonly='readonly'";
						$disabledIQ="disabled='disabled'";
						$etoileIQ="";
					}
				?>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" id="tableProd" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#dbeef9">PROD</td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#dbeef9" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateIntervention" name="dateIntervention" size="15" value="" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<select id="vacation" name="vacation" <?php echo $disabledSTCE;?>>
									<option name="" value=""></option>
									<option name="J" value="J">Jour</option>
									<option name="S" value="S">Soir</option>
									<option name="N" value="N">Nuit</option>
									<option name="VSD Jour" value="VSD Jour">VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit">VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp;Temps objectif : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsObjectif" <?php echo $readSTCE;?> name="tempsObjectif" size="5" type="text" value="">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les opérateurs :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
										<select id="compagnon" id="compagnon" name="compagnon" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterTE()">
											<?php
											echo"<option name='0' value='0'></option>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=379 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowCompagnon=mysqli_fetch_array($result)){
													echo "<option value='".$rowCompagnon['Id']."'>".$rowCompagnon['Nom']." ".$rowCompagnon['Prenom']."</option>";
													echo "<script>Liste_Personne[".$i."] = new Array('".$rowCompagnon['Id']."','".addslashes($rowCompagnon['Nom'])."','".addslashes($rowCompagnon['Prenom'])."');</script>\n";
													$i+=1;
												}
											}
											?>
										</select>
										</td>
									</tr>
									<tr>
										<td width="40%" bgcolor='#e4e7f0'>&nbsp;Temps passé : </td>
									</tr>
									<tr>
										<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
											<input onKeyUp="nombre(this)" style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterTE()" id="tempsPasse" name="tempsPasse" size="5" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterTE()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">Personne</td><td class="Libelle">Nb. heures</td></tr>
								</table>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">TOTAL</td><td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="0" readonly="readonly"/>
									</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<select id="statutProd" name="statutProd" onchange="Recharge_StatutProd();" <?php echo $disabledCE;?>>
									<option name="" value=""></option>
									<?php
									$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=379 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowStatut=mysqli_fetch_array($result)){
											echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Retour PROD : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='retourP'>
								<select id="retourProd" name="retourProd" <?php echo $disabledCE;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour WHERE Id_Prestation=379 AND Supprime=0 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowRetour=mysqli_fetch_array($result)){
											echo "<script>Liste_Retour[".$i."] = new Array(\"".$rowRetour['Id']."\",\"".$rowRetour['Libelle']."\",\"".$rowRetour['Id_Statut']."\",\"".$rowRetour['Supprime']."\");</script>\n";
											$i+=1;
										}
									}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Avancement PROD :</td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='avancementP'>
								<select id="avancementProd" name="avancementProd" <?php echo $disabledCE;?>>
									<option name="0" value="0" selected></option>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9" valign="center">&nbsp; Commentaire PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<textarea id="commentairePROD" name="commentairePROD" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>></textarea>
							</td>
						</tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#c7e048">QUALITE</td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#c7e048" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateInterventionQ" name="dateInterventionQ" size="15" value="" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select id="vacationQ" name="vacationQ" <?php echo $disabledIQ;?>>
									<option name="" value=""></option>
									<option name="J" value="J">Jour</option>
									<option name="S" value="S">Soir</option>
									<option name="N" value="N">Nuit</option>
									<option name="VSD Jour" value="VSD Jour">VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit">VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">
								&nbsp; Inspecteur qualité :
							</td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select name="IQ">
									<option name="" value=""></option>
									<?php
									$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NomPrenom ";
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=379 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowIQ=mysqli_fetch_array($result)){
											$selected="";
											if(substr($_SESSION['DroitSP'],4,1)=='1' && $_SESSION['Id_PersonneSP'] == $rowIQ['Id']){$selected="selected";}
											echo "<option name='".$rowIQ['Id']."' value='".$rowIQ['Id']."' ".$selected.">".$rowIQ['NomPrenom']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Statut QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<div id="statutsQualite">
									<select id="statutQualite" name="statutQualite" onchange="Recharge_StatutQualite();" <?php echo $disabledIQ;?>>
										<option name="" value=""></option>
										<?php
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=379 AND TypeStatut='Q' ORDER BY Id;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowStatut=mysqli_fetch_array($result)){
												echo "<script>Liste_Statut[".$i."] = new Array('".$rowStatut['Id']."');</script>\n";
												$i+=1;
											}
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td width="26%" class="Libelle" bgcolor="#c7e048">&nbsp; Retour QUALITE : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#c7e048">
								<div id='retourQ'>
								<select id="retourQualite" name="retourQualite" <?php echo $disabledIQ;?>>
									<option name="0" value="0"></option>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048" valign="center">&nbsp; Commentaire QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<textarea id="commentaireQualite" name="commentaireQualite" rows="5" cols="40" style="resize:none;" <?php echo $readIQ;?>></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
					</table>
				</td>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" id="tableIngredient" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f" colspan='2'><i>&nbsp; Ajouter les ingrédients :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Ingrédient : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefIngredient" name="RefIngredient" onkeypress="if(event.keyCode == 13)AjouterIngredient()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_olwingredient WHERE Id_Prestation=379 AND Supprime=false ORDER BY Libelle;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowIngredient=mysqli_fetch_array($result)){
														echo "<option name='".$rowIngredient['Id']."' value='".$rowIngredient['Id']."'>".$rowIngredient['Libelle']."</option>";
														echo "<script>Liste_Ingredient[".$i."] = new Array('".$rowIngredient['Id']."','".addslashes($rowIngredient['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N° lot : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="numLot" onkeypress="if(event.keyCode == 13)AjouterIngredient()" name="numLot" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Date péremption : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="datePeremption" onkeypress="if(event.keyCode == 13)AjouterIngredient()" name="datePeremption" size="8" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Coeff. Hygrométrique : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterIngredient()" id="coeffH" name="coeffH" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterIngredient()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_Ingredient" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Ingrédient</td><td class="Libelle">N° lot</td><td class="Libelle">Date</td><td class="Libelle">Coeff.</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="reference" name="reference" onkeypress="if(event.keyCode == 13)AjouterECME()" size="15" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECME()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECME" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">ECME</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="top">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0"><i>&nbsp; Ajouter les AIPI-AIPS :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefAIPI" name="RefAIPI" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT new_competences_qualification.Id,new_competences_qualification.Libelle ";
												$req.="FROM new_competences_prestation_qualification ";
												$req.="LEFT JOIN new_competences_qualification ";
												$req.="ON new_competences_prestation_qualification.Id_Qualification = new_competences_qualification.Id ";
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation=379 ";
												$req.="AND new_competences_qualification.Id_Categorie_Qualification=112 ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowAIPI=mysqli_fetch_array($result)){
														echo "<option name='".$rowAIPI['Id']."' value='".$rowAIPI['Id']."'>".$rowAIPI['Libelle']."</option>";
														echo "<script>Liste_AIPI[".$i."] = new Array('".$rowAIPI['Id']."','".addslashes($rowAIPI['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterAIPIS()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" align="center">AIPI-AIPS</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>	
				</td>
			</tr>
			<tr><td height="9" colspan="6"></td></tr>
			<tr style="display:none;"><td><input id='travailEffectue' name='travailEffectue' value=''  readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id='ECME' name='ECME' value=''  readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value=''  readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id='Ingredient' name='Ingredient' value=''  readonly='readonly'></td></tr>
			<tr style="display:none;"><td height="4" colspan="6" bgcolor="#dbeef9"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
		?>
		<td colspan="6" align="center">
		<div id="Enregistrer">
		</div>
		<div id="EnregistrerDupliquer">
		</div>	
		<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="AfficherSubmit()"> &nbsp;&nbsp;&nbsp;
		</td>
		<?php
		}
		?>
	</tr>
	<tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires à remplir</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>