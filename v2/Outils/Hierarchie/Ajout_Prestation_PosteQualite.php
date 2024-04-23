<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(Id_Plateforme){
			window.opener.location="Liste_Prestation_Poste.php?plateforme="+Id_Plateforme;
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");

$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE Id=5 ORDER BY Id DESC");
$NbLignePoste=mysqli_num_rows($resultPoste);
if($_POST)
{	
	$requeteSupp="DELETE FROM new_competences_personne_poste_prestation WHERE Id_Poste=5 AND Id_Prestation=".$_POST['Id_Prestation'];
	if($_POST['Id_Pole']>0){$requeteSupp.=" AND Id_Pole=".$_POST['Id_Pole'];}
	$resultSupp=mysqli_query($bdd,$requeteSupp);

	$requeteInsert="INSERT INTO new_competences_personne_poste_prestation (Id_Poste, Id_Personne, Id_Prestation, Id_Pole, Backup)";
	$requeteInsert.=" VALUES";
	$NbComptePoste=0;
	mysqli_data_seek($resultPoste,0);
	while($rowPoste=mysqli_fetch_array($resultPoste))
	{
		$NbComptePoste+=1;
		$requeteInsert.=" (".$rowPoste[0].",".$_POST['Poste_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",0)";

		if($_POST['Poste_Backup_'.$rowPoste[0]]>0){
			$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",1)";
		}
		if($_POST['Poste_Backup2_'.$rowPoste[0]]>0){
			$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup2_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",2)";
		}
		if($_POST['Poste_Backup3_'.$rowPoste[0]]>0){
			$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup3_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",3)";
		}		
		if($NbComptePoste<$NbLignePoste){$requeteInsert.=",";}
	}
	$requeteInsert=mysqli_query($bdd,$requeteInsert);

	echo "<script>FermerEtRecharger('".$_POST['Id_Plateforme']."');</script>";
}
elseif($_GET){
	$requetePresta = "SELECT new_competences_prestation.Libelle, new_competences_prestation.Active, new_competences_prestation.Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=".$_GET['Id_Prestation'];
	$resultPresta=mysqli_query($bdd,$requetePresta);
	$rowPrestation=mysqli_fetch_array($resultPresta);
	
	$Pole = "";
	if ($_GET['Id_Pole'] <> "0"){
		$requetePole = "SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=".$_GET['Id_Pole'];
		$resultPole=mysqli_query($bdd,$requetePole);
		$rowPole=mysqli_fetch_array($resultPole);
		$Pole = " - ".$rowPole['Libelle'];
	}
	
	//CQS ou RP
	$req="SELECT Id 
			FROM new_competences_personne_poste_plateforme 
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (6,15)
			AND Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_GET['Id_Prestation'].") ";
	$resultPersPlat=mysqli_query($bdd,$req);
	$NbPersPlat=mysqli_num_rows($resultPersPlat);
	
	$disabled="disabled='disabled'";
	if($NbPersPlat>0 || $rowPrestation['Id_Plateforme']<>1 || (($_SESSION['Id_Personne']==7234 || $_SESSION['Id_Personne']==4788) && $rowPrestation['Id_Plateforme']==1)){$disabled="";}
	
	$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_prestation.Active ";
	$requetePersonne.=" FROM new_rh_etatcivil, new_competences_personne_plateforme, new_competences_prestation";
	$requetePersonne.=" WHERE new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
						AND new_rh_etatcivil.Id<>6572 ";
	$requetePersonne.=" AND new_competences_personne_plateforme.Id_Plateforme=new_competences_prestation.Id_Plateforme";
	$requetePersonne.=" AND new_competences_prestation.Id=".$_GET['Id_Prestation'];
	$requetePersonne.=" ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	$resultPersonne=mysqli_query($bdd,$requetePersonne);
	$resultPrestationPoste=mysqli_query($bdd,"SELECT * FROM new_competences_personne_poste_prestation WHERE Id_Prestation=".$_GET['Id_Prestation']." AND Id_Pole=".$_GET['Id_Pole']);
	$NbLignePrestationPoste=mysqli_num_rows($resultPrestationPoste);
?>
	<form id="formulaire" method="POST" action="Ajout_Prestation_PosteQualite.php" onsubmit="return Verification_Saisie();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Plateforme" id="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
		<input type="hidden" name="Id_Prestation" value="<?php echo $_GET['Id_Prestation'];?>">
		<input type="hidden" name="Id_Pole" value="<?php echo $_GET['Id_Pole'];?>">
		<input type="hidden" name="OldActive" value="<?php echo $rowPrestation['Active'];?>">
		<input type="hidden" id="QualiteModifiable" name="QualiteModifiable" value="<?php echo $disabled;?>">
		<input type="hidden" name="Prestation" value="<?php echo $rowPrestation['Libelle']."".$Pole;?>">
		<table class="TableCompetences" style="width:100%; align:center;">
			<tr>
				<td colspan="3" class="PetitCompetence">Prestation : <?php echo $rowPrestation['Libelle']."".$Pole; ?></td>
			</tr>
			<?php
				while($rowPoste=mysqli_fetch_array($resultPoste))
				{
					echo "<tr><td bgcolor='#eeeeee' colspan='9' align='center'>".$rowPoste[1]."</td></tr>";
			?>
					<tr>
						<td class="PetitCompetence"><?php echo $rowPoste[1];?> : </td>
						<td>
							<select id="poste" name="<?php echo "Poste_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									$PersonneBackup1=0;
									$PersonneBackup2=0;
									$PersonneBackup3=0;
									$PersonneBackup4=0;
									$PersonneBackup5=0;
									$PersonneBackup6=0;
									$PersonneBackup7=0;
									$PersonneBackup8=0;
									$PersonneBackup9=0;
									$PersonneBackup10=0;
									$PersonneBackup11=0;
									
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										while($rowPrestationPoste=mysqli_fetch_array($resultPrestationPoste))
										{
											if($rowPrestationPoste[2]==$rowPersonne[0] && $rowPrestationPoste[1]==$rowPoste[0]){
												if($rowPrestationPoste[5]==0){echo " selected";}
												if($rowPrestationPoste[5]==1){$PersonneBackup1=$rowPersonne[0];}
												if($rowPrestationPoste[5]==2){$PersonneBackup2=$rowPersonne[0];}
												if($rowPrestationPoste[5]==3){$PersonneBackup3=$rowPersonne[0];}
												if($rowPrestationPoste[5]==4){$PersonneBackup4=$rowPersonne[0];}
												if($rowPrestationPoste[5]==5){$PersonneBackup5=$rowPersonne[0];}
												if($rowPrestationPoste[5]==6){$PersonneBackup6=$rowPersonne[0];}
												if($rowPrestationPoste[5]==7){$PersonneBackup7=$rowPersonne[0];}
												if($rowPrestationPoste[5]==8){$PersonneBackup8=$rowPersonne[0];}
												if($rowPrestationPoste[5]==9){$PersonneBackup9=$rowPersonne[0];}
												if($rowPrestationPoste[5]==10){$PersonneBackup10=$rowPersonne[0];}
												if($rowPrestationPoste[5]==11){$PersonneBackup11=$rowPersonne[0];}
											}
										}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
						<td class="PetitCompetence"><?php echo $rowPoste[1];if($rowPoste[1] > 1){echo " Backup";}?> : </td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersonneBackup1){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
						<td class="PetitCompetence"><?php echo $rowPoste[1];if($rowPoste[1] > 1){echo " Backup";}?> : </td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup2_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersonneBackup2){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
						<td class="PetitCompetence"><?php echo $rowPoste[1];if($rowPoste[1] > 1){echo " Backup";}?> : </td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup3_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersonneBackup3){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
					</tr>
					<?php
				}
			?>
			<tr>
				<td height="20"><br/></td>
			</tr>
			<tr>
				<td colspan="9" align="center"><input class="Bouton" type="submit" value="Valider"></td>
			</tr>
		</table>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>