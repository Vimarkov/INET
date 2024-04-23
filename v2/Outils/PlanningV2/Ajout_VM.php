<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.typeVisite.value=='0'){alert('Vous n\'avez pas renseigné le type de visite.');return false;}
				if(formulaire.dateVisite.value==''){alert('Vous n\'avez pas renseigné la date de visite.');return false;}
				return true;
			}
			else{
				if(formulaire.typeVisite.value=='0'){alert('You did not fill in the type of visit.');return false;}
				if(formulaire.dateVisite.value==''){alert('You did not fill in the date of visit.');return false;}
				return true;
			}
		}
			
		function FermerEtRecharger(Menu,Id_Personne,Page)
		{
			if(Page=="Liste_VisiteMedicaleHistorique"){
				window.opener.location="Liste_VisiteMedicaleHistorique.php?Menu="+Menu+"&Id_Personne="+Id_Personne;
				window.close();
			}
			else if(Page=="SuiviAM"){
				window.opener.location="SuiviAM.php?Menu="+Menu+"&Id_Personne="+Id_Personne;
				window.close();
			}
			else{
				window.opener.location="Liste_VisiteMedicaleEC.php?Menu="+Menu;
				window.close();
				
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$DirFichier2="VM/";
$DirFichier="Outils/PlanningV2/VM/";

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	$fichierPassage="";
	//****TRANSFERT FICHIER****
	if($_FILES['fichier']['name']!="")
	{
		$tmp_file=$_FILES['fichier']['tmp_name'];
		if(is_uploaded_file($tmp_file)){
			//On vérifie la taille du fichiher
			if(filesize($_FILES['fichier']['tmp_name'])<=$_POST['MAX_FILE_SIZE'])
			{
				// on copie le fichier dans le dossier de destination
				$name_file=$_FILES['fichier']['name'];
				$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
				while(file_exists($DirFichier2.$name_file)){$name_file="_".date('j-m-y')."_".date('H-i-s')." ".$name_file;}
				if(move_uploaded_file($tmp_file,$DirFichier2.$name_file))
				{$fichierPassage=$name_file;}
			}
		}
	}
	
	if($_POST['Mode']=="A")
	{
		$requeteInsertUpdate="INSERT INTO rh_personne_visitemedicale (Id_Personne,Id_TypeVisite,DateVisite,HeureVisite,RestrictionAptitude,CommentaireRestriction,PJ_AvisAptitude)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="".$_POST['Id_Personne'].",";
		$requeteInsertUpdate.="".$_POST['typeVisite'].",";
		$requeteInsertUpdate.="'".TrsfDate_($_POST['dateVisite'])."',";
		$requeteInsertUpdate.="'".$_POST['heureVisite']."',";
		$requeteInsertUpdate.="".$_POST['restrictionAptitude'].",";
		$requeteInsertUpdate.="'".addslashes($_POST['commentaireRestriction'])."',";
		$requeteInsertUpdate.="'".$fichierPassage."'";
		$requeteInsertUpdate.=")";
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		$IdCree = mysqli_insert_id($bdd);
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_personne_visitemedicale SET";
		$requeteInsertUpdate.=" Id_TypeVisite=".$_POST['typeVisite'].", ";
		$requeteInsertUpdate.=" DateVisite='".TrsfDate_($_POST['dateVisite'])."', ";
		$requeteInsertUpdate.=" HeureVisite='".$_POST['heureVisite']."', ";
		$requeteInsertUpdate.=" RestrictionAptitude=".$_POST['restrictionAptitude'].", ";
		$requeteInsertUpdate.=" CommentaireRestriction='".addslashes($_POST['commentaireRestriction'])."', ";
		$requeteInsertUpdate.=" PJ_AvisAptitude='".$fichierPassage."' ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		$IdCree = $_POST['Id'];
	}
	if($IdCree>0){
		$req="UPDATE rh_personne_vm_smr SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id_Personne_VM=".$IdCree." ";
		$resultUpdt=mysqli_query($bdd,$req);
		
		//Ajout des SMR
		$req="SELECT Id FROM rh_smr WHERE Suppr=0";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		echo $nbResulta;
		if ($nbResulta>0){
			while($row=mysqli_fetch_array($result)){
				if(isset($_POST['smr_'.$row['Id']])){
					$req="INSERT INTO rh_personne_vm_smr (Id_Personne_VM,Id_SMR)
					VALUES (".$IdCree.",".$row['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
	}
	echo "<script>FermerEtRecharger('".$_POST['Menu']."','".$_POST['Id_Personne']."','".$_POST['Page']."')</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="A" || $_GET['Mode']=="M")
	{
		if($_GET['Id']!='0')
		{
			$Modif=True;
			$result=mysqli_query($bdd,"SELECT Id,DateVisite,HeureVisite,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,RestrictionAptitude,CommentaireRestriction,PJ_AvisAptitude,Id_TypeVisite FROM rh_personne_visitemedicale WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
		else{
			$result=mysqli_query($bdd,"SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']." ");
			$row=mysqli_fetch_array($result);
		}
		
		if($_GET){$Menu=$_GET['Menu'];}
		else{$Menu=$_POST['Menu'];}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_VM.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
		<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>" />
		<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
		<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
		<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $_GET['Id_Personne']; ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
				<td width="80%"><?php echo stripslashes($row['Personne']);?></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date visite : ";}else{echo "Date visit : ";} ?></td>
				<td>
					<input type="date" style="text-align:center;" id="dateVisite" name="dateVisite" size="10" value="<?php if($Modif){echo AfficheDateFR($row['DateVisite']);}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle" width="14%"><?php if($LangueAffichage=="FR"){echo "Heure de la visite";}else{echo "Time of visit";}?> : </td>
				<td width="15%">
					<select name="heureVisite" id="heureVisite">
						<?php
						$heure=6;
						$min=0;
						for($i=1;$i<=61;$i++){
							if($min==0){$minAffiche="";}
							else{$minAffiche=$min;}
							$selected="";
							if($Modif){if($row['HeureVisite']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}}
							echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>";
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
						}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Type de visite : ";}else{echo "Type of visit : ";} ?></td>
				<td>
					<select name="typeVisite" id="typeVisite" style="width:150px">
					<?php
						if($_GET['Page']<>"SuiviAM"){	
					?>
							<option value="0"></option>
					<?php
						}
					?>
					<?php
					$reqSuite="";
					if($_GET['Page']=="SuiviAM"){
						$reqSuite="WHERE Id=2 ";
					}
					if($_SESSION["Langue"]=="FR"){
						$rq="SELECT Id, Libelle, Suppr
						FROM rh_typevisitemedicale 
						".$reqSuite."
						ORDER BY Libelle ";
					}
					else{
						$rq="SELECT Id, LibelleEN AS Libelle, Suppr
						FROM rh_typevisitemedicale 
						".$reqSuite."
						ORDER BY LibelleEN ";
					}
					$resultTypeVM=mysqli_query($bdd,$rq);
					while($rowTypeVM=mysqli_fetch_array($resultTypeVM))
					{
						if($rowTypeVM['Suppr']==0 || ($Modif && $rowTypeVM['Id']==$row['Id_TypeVisite'])){
							$selected="";
							if($Modif && $rowTypeVM['Id']==$row['Id_TypeVisite']){$selected="selected";}
							echo "<option value='".$rowTypeVM['Id']."' ".$selected.">".stripslashes($rowTypeVM['Libelle'])."</option>\n";
						}
					}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Restriction d'aptitude : ";}else{echo "Restriction of aptitude : ";} ?></td>
				<td>
					<select name="restrictionAptitude" id="restrictionAptitude" style="width:50px">
					<option value="0" <?php if($Modif){if($row['RestrictionAptitude']==0){echo "checked";}}else{echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
					<option value="1" <?php if($Modif){if($row['RestrictionAptitude']==1){echo "checked";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "Si oui, restriction : ";}else{echo "If yes, restriction : ";} ?></td>
				<td>
					<textarea name="commentaireRestriction" id="commentaireRestriction" cols="60" rows="3" style="resize: none;"><?php if($Modif){echo stripslashes($row['CommentaireRestriction']);}?></textarea>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "SMR : ";}else{echo "SMR : ";} ?></td>
				<td>
					<div id='Div_Objet' style='width:100%;overflow:auto;'>
						<?php
						echo "<table width='100%'>";
						if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_smr WHERE Suppr=0 ORDER BY Libelle";}
						else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_smr WHERE Suppr=0 ORDER BY LibelleEN";}
						$resultSMR=mysqli_query($bdd,$req);
						$nbResultaSMR=mysqli_num_rows($resultSMR);
						if ($nbResultaSMR>0){
							while($rowSMR=mysqli_fetch_array($resultSMR)){
								
								if($Modif){
									$req="SELECT Id FROM rh_personne_vm_smr WHERE Id_Personne_VM=".$row['Id']." AND Id_SMR=".$rowSMR['Id']." AND Suppr=0 ";
									$resultVMSMR=mysqli_query($bdd,$req);
									$nbResultaVMSMR=mysqli_num_rows($resultVMSMR);
								}
								else{
									$nbResultaVMSMR=0;	
								}
								$checked="";
								if($nbResultaVMSMR>0){$checked="checked";}
								echo "<tr><td><input type='checkbox' ".$checked." class='objets' name='smr_".$rowSMR['Id']."'> ".$rowSMR['Libelle']."</td></tr>";
							}
						}
						echo "</table>";
						?>
					</div>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Avis d'aptitude";}else{echo "Notice of Qualification";}?> : </td>
				<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
			</tr>
			<tr>
				<?php
				if($Modif && $row['PJ_AvisAptitude']<>"")
				{
				?>
				<td>
					<a class="Info" href="<?php echo $chemin."/".$DirFichier.$row['PJ_AvisAptitude']; ?>" target="_blank"><?php if($_SESSION["Langue"]=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['PJ_AvisAptitude'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($_SESSION["Langue"]=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif){if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
						else{if($_SESSION["Langue"]=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}}
					?>
					/>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE rh_personne_visitemedicale SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger('".$_GET['Menu']."','".$_GET['Id_Personne']."','".$_GET['Page']."')</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>