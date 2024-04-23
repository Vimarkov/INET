<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(Id_Plateforme){
			window.opener.location="Liste_Prestation_Poste.php?plateforme="+Id_Plateforme;
			window.close();
		}
		function Verification_Saisie(Langue){
			var mySelect = document.getElementById("formulaire").getElementsByTagName("select")
			var nomSelect = "";
			var bUnRempli = 0;
			var bActif = 0;
			for (var i=0; i<=mySelect.length-1; i++){
				nomSelect = mySelect[i].name;
				if (nomSelect.substr(0,5) == 'Poste'){
					if (mySelect[i].value != '0'){
						bUnRempli = 1;
					}
				}
				else{
					if (mySelect[i].value != '0'){
						bActif = 1;
					}
				}
			}
			
			if (bActif == 1){
				if (bUnRempli == 1){
					if(Langue=="FR"){texte='Cette prestation est non active. Voulez-vous effacer tous les responsables de cette prestation ?';}
					else{texte='This activity is not active. Do you want to delete all the people responsible for this activity?';}
					if (confirm(texte)){ 
						for (var i=0; i<=mySelect.length-1; i++){
							nomSelect = mySelect[i].name;
							if (nomSelect.substr(0,5) == 'Poste'){
								mySelect[i].value = '0';
							}
						}
					}
				}
			}
			else{
				if (bUnRempli == 1){
				
					for (var i=0; i<=mySelect.length-1; i++){
						if (mySelect[i].name == 'Poste_2' || mySelect[i].name == 'Poste_3' || mySelect[i].name == 'Poste_4' || mySelect[i].name == 'Poste_7' || mySelect[i].name == 'Poste_9'){
							if (mySelect[i].value == '0'){
								if(Langue=="FR"){alert("Veuillez compléter les responsables de cette prestation ou tout effacer");}
								else{alert("Please fill in the managers of this activity (mini 1 name per N...) or cancel everything");}
								return false;
							}
						}
					}
				}
			}
			return true;
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");

$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE Id<6 ORDER BY Id DESC");
$NbLignePoste=mysqli_num_rows($resultPoste);
if($_POST)
{

	$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE (Id<=5) OR Id=22 ORDER BY Id DESC");
	$NbLignePoste=mysqli_num_rows($resultPoste);

		
	if($_POST['QualiteModifiable']==""){
		$requeteSupp="DELETE FROM new_competences_personne_poste_prestation WHERE Id_Prestation=".$_POST['Id_Prestation'];
		if($_POST['Id_Pole']>0){$requeteSupp.=" AND Id_Pole=".$_POST['Id_Pole'];}
		$resultSupp=mysqli_query($bdd,$requeteSupp);
	}
	else{
		$requeteSupp="DELETE FROM new_competences_personne_poste_prestation WHERE Id_Poste<>6 AND Id_Poste<>5 AND Id_Poste<>8 AND Id_Prestation=".$_POST['Id_Prestation'];
		if($_POST['Id_Pole']>0){$requeteSupp.=" AND Id_Pole=".$_POST['Id_Pole'];}
		$resultSupp=mysqli_query($bdd,$requeteSupp);
	}
	
	$requeteInsert="INSERT INTO new_competences_personne_poste_prestation (Id_Poste, Id_Personne, Id_Prestation, Id_Pole, Backup)";
	$requeteInsert.=" VALUES";
	$NbComptePoste=0;
	mysqli_data_seek($resultPoste,0);
	while($rowPoste=mysqli_fetch_array($resultPoste))
	{
		$NbComptePoste+=1;
		if($_POST['QualiteModifiable']=="" || ($rowPoste[0]<>8 && $rowPoste[0]<>6 && $rowPoste[0]<>5)){
			$requeteInsert.=" (".$rowPoste[0].",".$_POST['Poste_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",0)";
			if($_POST['Poste_Backup_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",1)";}
		}
		if ($rowPoste[0] <= 3){
			if($_POST['Poste_Backup_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup2_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",2)";}
			if($_POST['Poste_Backup_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup3_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",3)";}
		}
		if ($rowPoste[0] == 2 || $rowPoste[0] == 3){
			if($_POST['Poste_Backup4_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup4_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",4)";}
			if($_POST['Poste_Backup5_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup5_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",5)";}
			if($_POST['Poste_Backup6_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup6_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",6)";}
		}
		if ($rowPoste[0] == 1){
			if($_POST['Poste_Backup4_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup4_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",4)";}
			if($_POST['Poste_Backup5_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup5_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",5)";}
			if($_POST['Poste_Backup6_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup6_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",6)";}
			if($_POST['Poste_Backup7_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup7_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",7)";}
			if($_POST['Poste_Backup8_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup8_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",8)";}
			if($_POST['Poste_Backup9_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup9_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",9)";}
			if($_POST['Poste_Backup10_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup10_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",10)";}
			if($_POST['Poste_Backup11_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup11_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",11)";}
		}
		if ($rowPoste[0] == 10){
			if($_POST['Poste_Backup2_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup2_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",2)";}
			if($_POST['Poste_Backup3_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup3_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",3)";}
			if($_POST['Poste_Backup4_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup4_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",4)";}
			if($_POST['Poste_Backup5_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup5_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",5)";}
			if($_POST['Poste_Backup6_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup6_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",6)";}
			if($_POST['Poste_Backup7_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup7_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",7)";}
		}
		if ($rowPoste[0] == 22){
			if($_POST['Poste_Backup2_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup2_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",2)";}
			if($_POST['Poste_Backup3_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup3_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",3)";}
		}
		if($_POST['QualiteModifiable']==""){
			if ($rowPoste[0] == 5){
				if($_POST['Poste_Backup2_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup2_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",2)";}
				if($_POST['Poste_Backup3_'.$rowPoste[0]]>0){$requeteInsert.=", (".$rowPoste[0].",".$_POST['Poste_Backup3_'.$rowPoste[0]].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",3)";}		
			}
		}
		if($_POST['QualiteModifiable']=="" || ($rowPoste[0]<>8 && $rowPoste[0]<>6 && $rowPoste[0]<>5)){
			if($NbComptePoste<$NbLignePoste){$requeteInsert.=",";}
		}
	}
	$requeteInsert=mysqli_query($bdd,$requeteInsert);
	
	if($_POST['Id_Plateforme']==1){
		if($_POST['OldActive']==-1 && $_POST['Etat']==0){
			$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
			$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			
			if($_SESSION['Langue']=="FR"){
				$Objet="Prestation réactivée dans l'extranet : ".$_POST['Prestation'];
				$Message="	<html>
								<head><title>Prestation réactivée dans l'extranet </title></head>
								<body>
									Bonjour,
									<br><br>
									La prestation suivante a été réactivée sur l'extranet : ".$_POST['Prestation']."<br>
									Pensez à la configurer au niveau des besoins en formation par métier par prestation
									<br>
									Bonne journée.<br>
									L'Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			else
			{
				$Objet="Reactivated site in the extranet : ".$_POST['Prestation'];
				$Message="	<html>
								<head><title>Reactivated site in the extranet</title></head>
								<body>
									Hello,
									<br><br>
									The following site has been reactivated on the extranet : ".$_POST['Prestation']."<br>
									Remember to configure it at the level of training needs by profession by delivery
									<br>
									Have a good day.<br>
									Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			$Emails="";
			
			//Liste des AF
			$reqAF="
				SELECT DISTINCT EmailPro
				FROM new_competences_personne_poste_plateforme
				LEFT JOIN new_rh_etatcivil
				ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
				WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".implode(",",array($IdPosteResponsableFormation,$IdPosteAssistantFormationExterne,$IdPosteResponsableQualite)).")
				AND Id_Plateforme=".$_POST['Id_Plateforme']." ";
			$ResultAF=mysqli_query($bdd,$reqAF);
			$NbAF=mysqli_num_rows($ResultAF);
			if($NbAF>0)
			{
				while($RowAF=mysqli_fetch_array($ResultAF))
				{
					if($RowAF['EmailPro']<>""){$Emails.=$RowAF['EmailPro'].",";}
				}
			}
			
			//Liste des CQP
			$reqCQ="SELECT DISTINCT EmailPro 
					FROM new_competences_personne_poste_prestation
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteReferentQualiteProduit.") 
					AND (
						SELECT Id_Plateforme 
						FROM new_competences_prestation 
						WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation
						)=".$_POST['Id_Plateforme']." ";
			$ResultCQ=mysqli_query($bdd,$reqCQ);
			$NbCQ=mysqli_num_rows($ResultCQ);
			if($NbCQ>0)
			{
				while($RowCQ=mysqli_fetch_array($ResultCQ))
				{
					if($RowCQ['EmailPro']<>""){$Emails.=$RowCQ['EmailPro'].",";}
				}
			}
			
			$Emails=substr($Emails,0,-1);
			
			if($Emails<>"")
			{
				if(mail($Emails,$Objet,$Message,$Headers,'-f extranet@aaa-aero.com'))
					{}
			}
		}
	}
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
	
	$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
	$requetePersonne.=" FROM new_rh_etatcivil ";
	$requetePersonne.=" ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	$resultPersonneAll=mysqli_query($bdd,$requetePersonne);
?>
	<form id="formulaire" method="POST" action="Ajout_Prestation_Poste.php" onsubmit="return Verification_Saisie('<?php echo $_SESSION["Langue"];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Plateforme" id="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
		<input type="hidden" name="Id_Prestation" value="<?php echo $_GET['Id_Prestation'];?>">
		<input type="hidden" name="Id_Pole" value="<?php echo $_GET['Id_Pole'];?>">
		<input type="hidden" name="OldActive" value="<?php echo $rowPrestation['Active'];?>">
		<input type="hidden" id="QualiteModifiable" name="QualiteModifiable" value="<?php echo $disabled;?>">
		<input type="hidden" name="Prestation" value="<?php echo $rowPrestation['Libelle']."".$Pole;?>">
		<table class="TableCompetences" style="width:100%; height:95%; align:center;">
			<tr>
				<td colspan="3" class="PetitCompetence"><?php if($_SESSION["Langue"]=="FR"){ echo "Prestations";}else{echo "Activities";}?> : <?php echo $rowPrestation['Libelle']."".$Pole; ?></td>
				<td colspan="1" class="PetitCompetence"><?php if($_SESSION["Langue"]=="FR"){ echo "Etat";}else{echo "Status";}?> : </td>
				<td>
					<select name="Etat" <?php echo $disabled; ?>>
						<?php
							if ($rowPrestation['Active'] == 0){
								echo "<option name='0' value='0' selected>Active</option>";
								echo "<option name='-1' value='-1' >Non active</option>";
							}
							else{
								echo "<option name='0' value='0' >Active</option>";
								echo "<option name='-1' value='-1' selected>Non active</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				while($rowPoste=mysqli_fetch_array($resultPoste))
				{
					echo "<tr><td bgcolor='#eeeeee' colspan='9' align='center'>".$rowPoste[1]."</td></tr>";
			?>
					<tr>
						<td class="PetitCompetence"></td>
						<td>
							<select id="poste" name="<?php echo "Poste_".$rowPoste[0];?>" <?php if($rowPoste[0]==5){echo $disabled;} ?>>
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
						<td class="PetitCompetence"></td>
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
						<?php
							if ($rowPoste[0] < 4){
						?>
						<td class="PetitCompetence"></td>
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
						<?php
							}
							if ($rowPoste[0] <= 3){
						?>
							<td class="PetitCompetence"></td>
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
						<?php
							}
							if ($rowPoste[0] == 5){
								?>
							<td class="PetitCompetence"></td>
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
							<td class="PetitCompetence"></td>
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
						<?php
							}
						?>
					</tr>
					<?php
						if ($rowPoste[0] ==2 || $rowPoste[0] ==3){
					?>
					<tr>
						<td class="PetitCompetence"></td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup4_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersonneBackup4){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
						<td class="PetitCompetence"></td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup5_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersonneBackup5){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
								?>
							</select>
						</td>
						<td class="PetitCompetence"></td>
						<td>
							<select id="poste" name="<?php echo "Poste_Backup6_".$rowPoste[0];?>" <?php if($rowPoste[0]==8 || $rowPoste[0]==5 || $rowPoste[0]==6){echo $disabled;} ?>>
								<option value='0'></option>
								<?php
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersonneBackup6){echo " selected";}
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
					<?php
						if ($rowPoste[0] == 1){
							echo "<tr>";
							for ($i=4;$i<=7;$i++){
								echo "<td class='PetitCompetence'></td>";
								echo "<td>";
									echo "<select id='poste' name='Poste_Backup".$i."_".$rowPoste[0]."'>";
									echo "<option value='0'></option>";
									if ($i == 4){$PersBackup = $PersonneBackup4;}
									elseif ($i == 5){$PersBackup = $PersonneBackup5;}
									elseif ($i == 6){$PersBackup = $PersonneBackup6;}
									elseif ($i == 7){$PersBackup = $PersonneBackup7;}
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersBackup){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
									echo "</select>";
								echo "</td>";
							}
							echo "</tr>";
							echo "<tr>";
							for ($i=8;$i<=11;$i++){
								echo "<td class='PetitCompetence'></td>";
								echo "<td>";
									echo "<select id='poste' name='Poste_Backup".$i."_".$rowPoste[0]."'>";
									echo "<option value='0'></option>";
									if ($i == 8){$PersBackup = $PersonneBackup8;}
									elseif ($i == 9){$PersBackup = $PersonneBackup9;}
									elseif ($i == 10){$PersBackup = $PersonneBackup10;}
									elseif ($i == 11){$PersBackup = $PersonneBackup11;}
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne[0]."'";
										if($rowPersonne[0]==$PersBackup){echo " selected";}
										echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
										if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
									}
									mysqli_data_seek($resultPersonne,0);
									echo "</select>";
								echo "</td>";
							}
							echo "</tr>";
						}
					?>
			<?php
				}
			?>
			<tr><td bgcolor='#eeeeee' colspan='9' align='center'>Magasinier / Storekeeper</td></tr>
				<tr>
					<td class='PetitCompetence'></td>
					<td>
						<select id="poste" name="Poste_22">
							<option value='0'></option>
							<?php
								$PersonneBackup1=0;
								$PersonneBackup2=0;
								$PersonneBackup3=0;
								
								while($rowPersonne=mysqli_fetch_array($resultPersonne))
								{
									echo "<option value='".$rowPersonne[0]."'";
									while($rowPrestationPoste=mysqli_fetch_array($resultPrestationPoste))
									{
										if($rowPrestationPoste[2]==$rowPersonne[0] && $rowPrestationPoste[1]==22){
											if($rowPrestationPoste[5]==0){echo " selected";}
											if($rowPrestationPoste[5]==1){$PersonneBackup1=$rowPersonne[0];}
											if($rowPrestationPoste[5]==2){$PersonneBackup2=$rowPersonne[0];}
											if($rowPrestationPoste[5]==3){$PersonneBackup3=$rowPersonne[0];}
										}
									}
									echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
									if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
								}
								mysqli_data_seek($resultPersonne,0);
							?>
						</select>
					</td>
					<?php
					echo "<td class='PetitCompetence'></td>";
					echo "<td>";
						echo "<select id='poste' name='Poste_Backup_22'>";
						echo "<option value='0'></option>";
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne[0]."'";
							if($rowPersonne[0]==$PersonneBackup1){echo " selected";}
							echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
							if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
						}
						mysqli_data_seek($resultPersonne,0);
						echo "</select>";
					echo "</td>";
					for ($i=2;$i<=3;$i++){
						echo "<td class='PetitCompetence'></td>";
						echo "<td>";
							echo "<select id='poste' name='Poste_Backup".$i."_22'>";
							echo "<option value='0'></option>";
							if ($i == 1){$PersBackup = $PersonneBackup1;}
							elseif ($i == 2){$PersBackup = $PersonneBackup2;}
							elseif ($i == 3){$PersBackup = $PersonneBackup3;}
							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								echo "<option value='".$rowPersonne[0]."'";
								if($rowPersonne[0]==$PersBackup){echo " selected";}
								echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
								if($NbLignePrestationPoste>0){mysqli_data_seek($resultPrestationPoste,0);}
							}
							mysqli_data_seek($resultPersonne,0);
							echo "</select>";
						echo "</td>";
					}
					echo "</tr>";
			?>
			</tr>
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