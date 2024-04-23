<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script>
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
	<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_SESSION['GPAO_Id_ListeDeroulante']>0){
	$req="SELECT Id, Libelle,NomTable
		FROM gpao_listederoulante
		WHERE Id=".$_SESSION['GPAO_Id_ListeDeroulante']." ";
	$resultListe=mysqli_query($bdd,$req);
	$nbResultaList=mysqli_num_rows($resultListe);
	if ($nbResultaList>0){
		$rowListe=mysqli_fetch_array($resultListe);
	
		if($_POST){
			if($_POST['Mode']=="A"){
				$requete="";
				if($rowListe['NomTable']=="workers"){ 
					if($_POST['personne']<>'0'){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (Id_Personne,Responsability,Id_PrestationGPAO) VALUES (".$_POST['personne'].",'".addslashes($_POST['responsability'])."',".$_SESSION['Id_GPAO'].") ";
					}
				}
				elseif($rowListe['NomTable']=="coordinationworker"){
					if($_POST['personne']<>'0'){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (Id_Personne,Id_PrestationGPAO) VALUES (".$_POST['personne'].",".$_SESSION['Id_GPAO'].") ";
					}
				}
				elseif($rowListe['NomTable']=="costcenter"){
					if($_POST['nom']<>''){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (Libelle,Id_Customer,AircraftType,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."',".$_POST['customer'].",'".addslashes($_POST['aircrafttype'])."',".$_SESSION['Id_GPAO'].") ";
					}
				}
				elseif($rowListe['NomTable']=="subassembly"){
					if($_POST['nom']<>''){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (Libelle,Id_CostCenter,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."',".$_POST['costcenter'].",".$_SESSION['Id_GPAO'].") ";
					}
				}
				elseif($rowListe['NomTable']=="wocategorylist"){
					if($_POST['nom']<>''){
						$timeUsed=0;
						if($_POST['timeUsed']<>""){
							$timeUsed=$_POST['timeUsed'];
						}
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (Libelle,Id_Customer,TimeUsed,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."',".$_POST['customer'].",".$timeUsed.",".$_SESSION['Id_GPAO'].") ";
					}
				}
				elseif($rowListe['NomTable']=="aircraft"){
					$NT=0;
					if($_POST['NT']<>""){
						$NT=$_POST['NT'];
					}
					if($_POST['nom']<>''){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (MSN,Id_AircraftType,Id_AircraftDestination,NT,CreateAT,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."',".$_POST['aircrafttype'].",".$_POST['aircraftdestination'].",".$NT.",'".date('Y-m-d H:i:s')."',".$_SESSION['Id_GPAO'].") ";
					}
				}
				elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){
					if($_POST['nom']<>''){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (AircraftType,Correspondance,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."','".addslashes($_POST['correspondance'])."',".$_SESSION['Id_GPAO'].") ";
					}
				}
				else{
					if($_POST['nom']<>''){
						$requete="INSERT INTO gpao_".$rowListe['NomTable']." (Libelle,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."',".$_SESSION['Id_GPAO'].") ";
					}
				}
				if($requete<>""){
					$result=mysqli_query($bdd,$requete);
				}
			}
			elseif($_POST['Mode']=="M"){
				$requete="";
				if($rowListe['NomTable']=="workers"){ 
					if($_POST['personne']<>'0'){
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET Id_Personne=".$_POST['personne'].",
							Responsability='".addslashes($_POST['responsability'])."'
							WHERE Id=".$_POST['id']." ";
					}
				}
				elseif($rowListe['NomTable']=="coordinationworker"){
					if($_POST['personne']<>'0'){
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET Id_Personne=".$_POST['personne']."
							WHERE Id=".$_POST['id']." ";
					}
				}
				elseif($rowListe['NomTable']=="costcenter"){
					if($_POST['nom']<>''){
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET Libelle='".addslashes($_POST['nom'])."',
							Id_Customer=".$_POST['customer'].",
							AircraftType='".addslashes($_POST['aircrafttype'])."'
							WHERE Id=".$_POST['id']." ";
					}
				}
				elseif($rowListe['NomTable']=="subassembly"){
					if($_POST['nom']<>''){
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET Libelle='".addslashes($_POST['nom'])."',
							Id_CostCenter=".$_POST['costcenter']."
							WHERE Id=".$_POST['id']." ";
					}
				}
				elseif($rowListe['NomTable']=="wocategorylist"){
					if($_POST['nom']<>''){
						$timeUsed=0;
						if($_POST['timeUsed']<>""){
							$timeUsed=$_POST['timeUsed'];
						}
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET Libelle='".addslashes($_POST['nom'])."',
							Id_Customer=".$_POST['customer'].",
							TimeUsed=".$timeUsed."
							WHERE Id=".$_POST['id']." ";
					}
				}
				elseif($rowListe['NomTable']=="aircraft"){
					if($_POST['nom']<>''){
						$NT=0;
						if($_POST['NT']<>""){
							$NT=$_POST['NT'];
						}
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET MSN='".addslashes($_POST['nom'])."',
							Id_AircraftType=".$_POST['aircrafttype'].",
							Id_AircraftDestination=".$_POST['aircraftdestination'].",
							NT=".$NT."
							WHERE Id=".$_POST['id']." ";
					}
				}
				elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){
					if($_POST['nom']<>''){
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET AircraftType='".addslashes($_POST['nom'])."',
							Correspondance='".addslashes($_POST['correspondance'])."'
							WHERE Id=".$_POST['id']." ";
					}
				}
				else{
					if($_POST['nom']<>''){
						$requete="UPDATE gpao_".$rowListe['NomTable']." 
							SET Libelle='".addslashes($_POST['nom'])."'
							WHERE Id=".$_POST['id']." ";
					}
				}
				if($requete<>""){
					$result=mysqli_query($bdd,$requete);
				}
			}
			echo "<script>FermerEtRecharger();</script>";
		}
		elseif($_GET)
		{
			//Mode ajout ou modification
			if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
				if($_GET['Id']!='0')
				{
					if($rowListe['NomTable']=="workers"){ 
						$result=mysqli_query($bdd,"SELECT Id, Id_Personne,Responsability FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					elseif($rowListe['NomTable']=="coordinationworker"){
						$result=mysqli_query($bdd,"SELECT Id, Id_Personne FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					elseif($rowListe['NomTable']=="costcenter"){
						$result=mysqli_query($bdd,"SELECT Id, Libelle,Id_Customer,AircraftType FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					elseif($rowListe['NomTable']=="subassembly"){
						$result=mysqli_query($bdd,"SELECT Id, Libelle,Id_CostCenter FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					elseif($rowListe['NomTable']=="wocategorylist"){
						$result=mysqli_query($bdd,"SELECT Id, Libelle,Id_Customer,TimeUsed FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					elseif($rowListe['NomTable']=="aircraft"){
						$result=mysqli_query($bdd,"SELECT Id, MSN,Id_AircraftDestination,Id_AircraftType,NT FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){
						$result=mysqli_query($bdd,"SELECT Id, AircraftType,Correspondance FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					else{
						$result=mysqli_query($bdd,"SELECT Id, Libelle FROM gpao_".$rowListe['NomTable']." WHERE Id=".$_GET['Id']);
					}
					$Ligne=mysqli_fetch_array($result);
				}
		?>

				<form id="formulaire" method="POST" action="Ajout_Liste.php" >
				<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
				<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
				<table width="95%" align="center" class="TableCompetences">
					<?php 

						if($rowListe['NomTable']=="workers"){ 
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
								<td colspan="3">
									<select id="personne" name="personne">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE Id NOT IN (SELECT Id_Personne FROM gpao_".$rowListe['NomTable']." WHERE Suppr=0 AND Id_PrestationGPAO=".$_SESSION['Id_GPAO'].") ORDER BY Nom, Prenom;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Nom']." ".$row['Prenom']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Responsability";}else{echo "Responsability";} ?> </td>
								<td colspan="3">
									<select id="responsability" name="responsability">
										<option value=''></option>
										<option value='Coord'>Coord</option>
										<option value='Prod'>Prod</option>
									</select>
								</td>
							</tr>
						<?php
							
						}
						elseif($rowListe['NomTable']=="coordinationworker"){
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
								<td colspan="3">
									<select id="personne" name="personne">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE Id NOT IN (SELECT Id_Personne FROM gpao_".$rowListe['NomTable']." WHERE Suppr=0 AND Id_PrestationGPAO=".$_SESSION['Id_GPAO'].") ORDER BY Nom, Prenom;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Nom']." ".$row['Prenom']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
						<?php	
						}
						elseif($rowListe['NomTable']=="costcenter"){
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
								<td colspan="3">
									<input type="texte" name="nom" id="nom" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}?>">
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Customer";} ?> </td>
								<td colspan="3">
									<select id="customer" name="customer">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT Id, Libelle 
												FROM gpao_customer 
												WHERE (Suppr=0 ";
										if($_GET['Mode']=="M"){
											$req.="OR Id=".$Ligne['Id_Customer'];
										}
										$req.=")
											AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
											ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($_GET['Mode']=="M"){
													if($row['Id']==$Ligne['Id_Customer']){$selected="selected";}
												}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Aircraft type";}else{echo "Aircraft type";} ?> </td>
								<td colspan="3">
									<input type="texte" name="aircrafttype" id="aircrafttype" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['AircraftType']);}?>">
								</td>
							</tr>
						<?php	
						}
						elseif($rowListe['NomTable']=="subassembly"){
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
								<td colspan="3">
									<input type="texte" name="nom" id="nom" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}?>">
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Cost center";}else{echo "Cost center";} ?> </td>
								<td colspan="3">
									<select id="costcenter" name="costcenter">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT Id, Libelle 
												FROM gpao_costcenter 
												WHERE (Suppr=0 ";
										if($_GET['Mode']=="M"){
											$req.="OR Id=".$Ligne['Id_CostCenter'];
										}
										$req.=")
											AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
											ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($_GET['Mode']=="M"){
													if($row['Id']==$Ligne['Id_CostCenter']){$selected="selected";}
												}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
						<?php	
						}
						elseif($rowListe['NomTable']=="wocategorylist"){
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
								<td colspan="3">
									<input type="texte" name="nom" id="nom" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}?>">
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Customer";} ?> </td>
								<td colspan="3">
									<select id="customer" name="customer">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT Id, Libelle 
												FROM gpao_customer 
												WHERE (Suppr=0 ";
										if($_GET['Mode']=="M"){
											$req.="OR Id=".$Ligne['Id_Customer'];
										}
										$req.=")
											AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
											ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($_GET['Mode']=="M"){
													if($row['Id']==$Ligne['Id_Customer']){$selected="selected";}
												}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Time used";}else{echo "Time used";} ?> </td>
								<td colspan="3">
									<input type="texte" onKeyUp="nombre(this)" name="timeUsed" id="timeUsed" size="10" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['TimeUsed']);}?>">
								</td>
							</tr>
							
						<?php	
						}
						elseif($rowListe['NomTable']=="aircraft"){
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";} ?> </td>
								<td colspan="3">
									<input onKeyUp="nombre(this)" type="texte" name="nom" id="nom" size="10" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['MSN']);}?>">
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Aircraft Type";}else{echo "Aircraft Type";} ?> </td>
								<td colspan="3">
									<select id="aircrafttype" name="aircrafttype">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT Id, Libelle 
												FROM gpao_aircrafttype
												WHERE (Suppr=0 ";
										if($_GET['Mode']=="M"){
											$req.="OR Id=".$Ligne['Id_AircraftType'];
										}
										$req.=")
											AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
											ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($_GET['Mode']=="M"){
													if($row['Id']==$Ligne['Id_AircraftType']){$selected="selected";}
												}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "NT";}else{echo "NT";} ?> </td>
								<td colspan="3">
									<input onKeyUp="nombre(this)" type="texte" name="NT" id="NT" size="15" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['NT']);}?>">
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Aircraft Destination";}else{echo "Aircraft Destination";} ?> </td>
								<td colspan="3">
									<select id="aircraftdestination" name="aircraftdestination">
									<?php
										echo"<option name='0' value='0'></option>";
										$req="SELECT Id, Libelle 
												FROM gpao_aircraftdestination
												WHERE (Suppr=0 ";
										if($_GET['Mode']=="M"){
											$req.="OR Id=".$Ligne['Id_AircraftDestination'];
										}
										$req.=")
											AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
											ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($_GET['Mode']=="M"){
													if($row['Id']==$Ligne['Id_AircraftDestination']){$selected="selected";}
												}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
									</select>
								</td>
							</tr>
							
						<?php	
						}
						elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){
						?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Aircraft type";}else{echo "Aircraft type";} ?> </td>
								<td colspan="3">
									<input type="texte" name="nom" id="nom" size="20" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['AircraftType']);}?>">
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Correspondance";}else{echo "Correspondance";} ?> </td>
								<td colspan="3">
									<input type="texte" name="correspondance" id="correspondance" size="20" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Correspondance']);}?>">
								</td>
							</tr>
							
						<?php	
						}
						else{
					?>
							<tr class="TitreColsUsers">
								<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
								<td colspan="3">
									<input type="texte" name="nom" id="nom" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}?>">
								</td>
							</tr>
					<?php
						}
					?>
					
					<tr><td height="5px"></td></tr>
					<tr class="TitreColsUsers">
						<td colspan="6" align="center">
							<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
						</td>
					</tr>
				</table>
				</form>
		<?php
			}
			else
			//Mode suppression
			{
				$requete="UPDATE gpao_".$rowListe['NomTable']." SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id=".$_GET['Id'];
				$result=mysqli_query($bdd,$requete);
				echo "<script>FermerEtRecharger();</script>";
			}
		}
	}
}
?>
	
</body>
</html>