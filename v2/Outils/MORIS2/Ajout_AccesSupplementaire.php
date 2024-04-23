<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");

$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE Id=46 ORDER BY Id DESC");
$NbLignePoste=mysqli_num_rows($resultPoste);
if($_POST)
{
	$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE Id=46 ORDER BY Id DESC");
	$NbLignePoste=mysqli_num_rows($resultPoste);

	$requeteSupp="DELETE FROM new_competences_personne_poste_prestation WHERE Id_Poste=46 AND Id_Prestation=".$_POST['Id_Prestation'];
	$resultSupp=mysqli_query($bdd,$requeteSupp);
	
	$requeteInsert="INSERT INTO new_competences_personne_poste_prestation (Id_Poste, Id_Personne, Id_Prestation, Id_Pole, Backup)";
	$requeteInsert.=" VALUES";
	$NbComptePoste=0;
	mysqli_data_seek($resultPoste,0);
	while($rowPoste=mysqli_fetch_array($resultPoste))
	{
		$NbComptePoste+=1;
		if($_POST['Poste_'.$rowPoste[0]]>0){$requeteInsert.=" (".$rowPoste[0].",".$_POST['Poste_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,0)";}
		if($_POST['Poste_Backup2_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup2_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,2)";}
		if($_POST['Poste_Backup3_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup3_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,3)";}
		if($_POST['Poste_Backup4_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup4_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,4)";}
		if($_POST['Poste_Backup5_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup5_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,5)";}
		if($_POST['Poste_Backup6_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup6_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,6)";}
		if($_POST['Poste_Backup7_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup7_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",0,7)";}
		
		if($NbComptePoste<$NbLignePoste){$requeteInsert.=",";}
	}
	$requeteInsert=mysqli_query($bdd,$requeteInsert);
	
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	$requetePresta = "SELECT new_competences_prestation.Libelle, new_competences_prestation.Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=".$_GET['Id_Prestation'];
	$resultPresta=mysqli_query($bdd,$requetePresta);
	$rowPrestation=mysqli_fetch_array($resultPresta);
	
	$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom
		FROM new_rh_etatcivil, new_competences_personne_plateforme, new_competences_prestation
		WHERE new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
		AND new_competences_personne_plateforme.Id_Plateforme=new_competences_prestation.Id_Plateforme
		AND new_competences_prestation.Id=".$_GET['Id_Prestation']."
		ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	$resultPersonne=mysqli_query($bdd,$requetePersonne);
	$resultPrestationPoste=mysqli_query($bdd,"SELECT * FROM new_competences_personne_poste_prestation WHERE Id_Prestation=".$_GET['Id_Prestation']);
	$NbLignePrestationPoste=mysqli_num_rows($resultPrestationPoste);
?>
	<form id="formulaire" method="POST" action="Ajout_AccesSupplementaire.php">
		<input type="hidden" name="Id_Prestation" value="<?php echo $_GET['Id_Prestation'];?>">
		<table class="TableCompetences" style="width:100%; align:center;">
			<tr>
				<td colspan="3" class="PetitCompetence Libelle">Prestation : <?php echo $rowPrestation['Libelle']; ?></td>
			</tr>
			<?php
				while($rowPoste=mysqli_fetch_array($resultPoste))
				{
			?>
					<tr>
						<td class="PetitCompetence"><?php echo $rowPoste['Libelle'];?> : </td>
						<td>
							<select id="poste" name="<?php echo "Poste_".$rowPoste['Id'];?>">
								<option value='0'></option>
								<?php
									$PersonneBackup1=0;
									$PersonneBackup2=0;
									$PersonneBackup3=0;
									$PersonneBackup4=0;
									$PersonneBackup5=0;
									$PersonneBackup6=0;
									$PersonneBackup7=0;
									
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne['Id']."'";
										while($rowPrestationPoste=mysqli_fetch_array($resultPrestationPoste))
										{
											if($rowPrestationPoste['Id_Personne']==$rowPersonne[0] && $rowPrestationPoste['Id_Poste']==$rowPoste['Id']){
												if($rowPrestationPoste['Backup']==0){echo " selected";}
												if($rowPrestationPoste['Backup']==1){$PersonneBackup1=$rowPersonne['Id'];}
												if($rowPrestationPoste['Backup']==2){$PersonneBackup2=$rowPersonne['Id'];}
												if($rowPrestationPoste['Backup']==3){$PersonneBackup3=$rowPersonne['Id'];}
												if($rowPrestationPoste['Backup']==4){$PersonneBackup4=$rowPersonne['Id'];}
												if($rowPrestationPoste['Backup']==5){$PersonneBackup5=$rowPersonne['Id'];}
												if($rowPrestationPoste['Backup']==6){$PersonneBackup6=$rowPersonne['Id'];}
												if($rowPrestationPoste['Backup']==7){$PersonneBackup7=$rowPersonne['Id'];}
											}
										}
										echo ">".$rowPersonne['Nom']." ".$rowPersonne['Prenom']."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
					</tr>
					<?php 
						$nb=2;
						for($i=1;$i<=2;$i++){
							echo "<tr>";
							for($j=1;$j<=3;$j++){
								$lavaleur="";
								if($nb>0){$lavaleur=$nb;}
								
								$PersonneBackup=0;
								if($nb==2){$PersonneBackup=$PersonneBackup2;}
								elseif($nb==3){$PersonneBackup=$PersonneBackup3;}
								elseif($nb==4){$PersonneBackup=$PersonneBackup4;}
								elseif($nb==5){$PersonneBackup=$PersonneBackup5;}
								elseif($nb==6){$PersonneBackup=$PersonneBackup6;}
								elseif($nb==7){$PersonneBackup=$PersonneBackup7;}
					?>
						<td class="PetitCompetence"></td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup".$lavaleur."_".$rowPoste['Id'];?>">
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne['Id']."'";
										if($rowPersonne['Id']==$PersonneBackup){echo " selected";}
										echo ">".$rowPersonne['Nom']." ".$rowPersonne['Prenom']."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
					<?php
							$nb++;
							}
							echo "</tr>";
						}
					?>
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