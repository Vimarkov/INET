<script language="javascript">
	function OuvreFenetreAjoutAircraft(){
		var w=window.open("Ajout_Aircraft.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
		w.focus();
		}
	function VerifRemplissage(){
		retour=true;
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.customer.value=='0'){alert('Vous n\'avez pas renseigné le client.');retour= false;}
		}
		else{
			if(formulaire.customer.value=='0'){alert('You did not fill in the customer.');retour= false;}
		}
		if(retour==true){
			formulaire.btn.value="Btn_Enregistrer";
		}
		return retour;
	}
	function SelectionnerWO(Id,Menu){
		window.location="TableauDeBord.php?Id_WO="+Id+"&Menu="+Menu;
		
	}
	function OuvreFenetreSuppr(Id,Menu){
		if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to delete?';}
		else{texte='Etes-vous sûr de vouloir supprimer ?';}
		if(window.confirm(texte)){
			window.location="TableauDeBord.php?Suppr=1&Id_WO="+Id+"&Menu="+Menu;
		}
	}
	function OuvreFenetre(fenetre){
		if(fenetre=="CMTE"){
			var w=window.open("Ajout_CMTE.php","PageCMTE","status=no,menubar=no,scrollbars=yes,width=750,height=300");
			w.focus();
		}
		else if(fenetre=="Chemical Product"){
			var w=window.open("Ajout_ChemicalProduct.php","PageChemical","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
			w.focus();
		}
		else if(fenetre=="Production"){
			var w=window.open("Ajout_Production.php","PageProduction","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
			w.focus();
		}
		else if(fenetre=="Category of Work"){
			var w=window.open("Ajout_WOCategory.php","PageCategory","status=no,menubar=no,scrollbars=yes,width=800,height=300");
			w.focus();
		}
		else if(fenetre=="Concession"){
			var w=window.open("Ajout_Concession.php","PageConcession","status=no,menubar=no,scrollbars=yes,width=700,height=300");
			w.focus();
		}
		else if(fenetre=="Coordination"){
			var w=window.open("Ajout_Coordination.php","PageCoordination","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
			w.focus();
		}
		else if(fenetre=="Intervention Card"){
			var w=window.open("Ajout_InterventionCard.php","PageIntervention","status=no,menubar=no,scrollbars=yes,width=1200,height=300");
			w.focus();
		}
		else if(fenetre=="Quality"){
			var w=window.open("Ajout_Quality.php","PageQuality","status=no,menubar=no,scrollbars=yes,width=1400,height=600");
			w.focus();
		}
	}
</script>
<?php
if($_GET){
	if(isset($_GET['Suppr'])){
		//Suppresion du WO 
		$req="UPDATE gpao_wo 
				SET 
					Suppr=1,
					DateSuppr='".date('Y-m-d')."',
					Id_Suppr=".$_SESSION['Id_Personne']."
				WHERE Id=".$_SESSION['GPAO_IdWO']." ";
			$result=mysqli_query($bdd,$req);
		$_SESSION['GPAO_IdWO']=0;
	}
	else{
		if(isset($_GET['Id_WO'])){
			$_SESSION['GPAO_IdWO']=$_GET['Id_WO'];
		}
		if(isset($_GET['Id_Aircraft'])){
			$_SESSION['GPAO_Aircraft']=$_GET['Id_Aircraft'];
		}
	}
}
else{
	$_SESSION['GPAO_Aircraft']=$_POST['aircraft'];
	if($_POST['aircraft']<>$_POST['oldMSN']){
		$_SESSION['GPAO_IdWO']=0;
	}
}
if($_POST){
	if($_POST['btn']=="Btn_Enregistrer"){
		//Mettre à jour la position de l'avion 
		$req="UPDATE gpao_aircraft
			SET Position='".$_POST['position']."'
			WHERE Id=".$_POST['aircraft'];
		$resultAircraft=mysqli_query($bdd,$req);
		
		//Calcul de Last EoW 
		$thelastEoW="";
		if($_POST['firstEoW']<>""){
			if($_POST['newEoW']<>""){
				$thelastEoW=$_POST['newEoW'];
			}
			else{
				$thelastEoW=$_POST['firstEoW'];
			}
		}
		//Ajout du WO 
		if($_SESSION['GPAO_IdWO']<=0){
			$req="INSERT INTO gpao_wo (Id_Aircraft,Id_Customer,Id_Imputation,Skills,Id_CostCenter,
				Para,AM,NC,Concession,
				OF,QLB,TLB,
				TargetTime,EscalationPoint,Id_Priority,PriorityReason,
				LimitDateFOT,NewEoW,LastEoW,UpdateDateTandem,
				PlanDate,Id_WorkingShift,WorkingProgress,ClosureDate,
				OTDEoW,OTDComment,
				Designation,Comments,CommentsA_CMS2,
				Id_CreatedBy,CreationDate,
				Id_PrestationGPAO)
				VALUES (".$_POST['aircraft'].",".$_POST['customer'].",".$_POST['imputation'].",'".addslashes($_POST['skill'])."',".$_POST['costcenter'].",
				'".addslashes($_POST['para'])."','".addslashes($_POST['am'])."','".addslashes($_POST['nc'])."','".addslashes($_POST['concession'])."',
				'".addslashes($_POST['ofot'])."','".addslashes($_POST['qlb'])."','".addslashes($_POST['tlb'])."',
				'".unNombreSinon0($_POST['targetTime'])."',".$_POST['escalationpoint'].",".$_POST['priority'].",'".addslashes($_POST['priorityReason'])."',
				'".TrsfDate_($_POST['firstEoW'])."','".TrsfDate_($_POST['newEoW'])."','".TrsfDate_($thelastEoW)."','".TrsfDate_($_POST['EoWTandem'])."',
				'".TrsfDate_($_POST['plandate'])."',".$_POST['workingShift'].",'".unNombreSinon0($_POST['workingProgress'])."','".TrsfDate_($_POST['closureDate'])."',
				".$_POST['otdEoW'].",'".addslashes($_POST['otdComment'])."',
				'".addslashes($_POST['designation'])."','".addslashes($_POST['commentACMS1'])."','".addslashes($_POST['commentACMS2'])."',
				".$_SESSION['Id_Personne'].",'".date('Y-m-d H:i:s')."',
				".$_SESSION['Id_GPAO']."
				)";
			$result=mysqli_query($bdd,$req);
			$_SESSION['GPAO_IdWO']=mysqli_insert_id($bdd);
		}
		else{
			//Plus de modification du MSN et du customer aprés la création
			$req="UPDATE gpao_wo 
				SET 
					Id_Imputation=".$_POST['imputation'].",
					Skills='".addslashes($_POST['skill'])."',
					Para='".addslashes($_POST['para'])."',
					AM='".addslashes($_POST['am'])."',
					NC='".addslashes($_POST['nc'])."',
					Concession='".addslashes($_POST['concession'])."',
					OF='".addslashes($_POST['ofot'])."',
					QLB='".addslashes($_POST['qlb'])."',
					TLB='".addslashes($_POST['tlb'])."',
					TargetTime='".unNombreSinon0($_POST['targetTime'])."',
					EscalationPoint=".$_POST['escalationpoint'].",
					Id_Priority=".$_POST['priority'].",
					PriorityReason='".addslashes($_POST['priorityReason'])."',
					LimitDateFOT='".TrsfDate_($_POST['firstEoW'])."',
					NewEoW='".TrsfDate_($_POST['newEoW'])."',
					LastEoW='".TrsfDate_($thelastEoW)."',
					UpdateDateTandem='".TrsfDate_($_POST['EoWTandem'])."',
					PlanDate='".TrsfDate_($_POST['plandate'])."',
					Id_WorkingShift=".$_POST['workingShift'].",
					WorkingProgress='".unNombreSinon0($_POST['workingProgress'])."',
					ClosureDate='".TrsfDate_($_POST['closureDate'])."',
					OTDEoW=".$_POST['otdEoW'].",
					OTDComment='".addslashes($_POST['otdComment'])."',
					Designation='".addslashes($_POST['designation'])."',
					Comments='".addslashes($_POST['commentACMS1'])."',
					CommentsA_CMS2='".addslashes($_POST['commentACMS2'])."'
				WHERE Id=".$_SESSION['GPAO_IdWO']." ";
			$result=mysqli_query($bdd,$req);	
		}
		
		//Mise à jour des satus 
		
		//PARTIE QUALITY 
		$req="UPDATE gpao_statutquality 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveaux
		for($i=0;$i<$_POST['nbLigneQ'];$i++){
			if($_POST['id_StatutList_'.$i]<>"0"){
				$timeUsed=0;
				if($_POST['timeUsed_'.$i]<>""){
					$timeUsed=$_POST['timeUsed_'.$i];
				}
				$req="INSERT INTO gpao_statutquality (Id_PrestationGPAO,Id_WO,
				Id_StatutList,DateStatut,TimeUsed,
				Id_QualityControlType,Id_ImputationRework,Id_UserName,
				IssueDetectedByCustomer,StatusComments,ICClosed,Id_NameResponsible,Id_NameResponsible2) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",
				'".$_POST['id_StatutList_'.$i]."','".$_POST['dateStatut_'.$i]."',
				'".$timeUsed."','".$_POST['id_QualityControlType_'.$i]."','".$_POST['id_ImputationRework_'.$i]."',
				'".$_POST['id_UserName_'.$i]."','".$_POST['issueDetectedByCustomer_'.$i]."',
				'".addslashes($_POST['statusComments_'.$i])."','".$_POST['iCClosed_'.$i]."',
				'".$_POST['id_NameResponsible_'.$i]."','".$_POST['id_NameResponsible2_'.$i]."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}
}
?>
<form id="formulaire" method="POST" action="Ajout_WO.php">
	<table align="center" width="100%" cellpadding="0" cellspacing="0">
		<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
		<input type="hidden" name="Menu" id="Menu" value="<?php echo $_SESSION['Menu']; ?>" />
		<input type="hidden" name="oldMSN" id="oldMSN" value="<?php echo $_SESSION['GPAO_Aircraft']; ?>" />
		<input type="hidden" name="id_WO" id="id_WO" value="<?php echo $_SESSION['GPAO_IdWO']; ?>" />
		<input type="hidden" name="btn" id="btn" value="" />
		<tr><td height="10"></td></tr>
		<tr>
			<td align="center">
				<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";} ?> :</td>
						<td width='20%'>
							<select class="aircraft" name="aircraft" style="width:130px;" onchange="submit();">
								<option value="0"></option>
							<?php
								$req="SELECT Id,MSN,Id_AircraftType,Position,
								(SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) AS AircraftType
								FROM gpao_aircraft
								WHERE Suppr=0
								AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
								AND MSN<>'' AND MSN<>'0'
								ORDER BY MSN";
								$resultAircraft=mysqli_query($bdd,$req);
								$nbAircraft=mysqli_num_rows($resultAircraft);
								
								$Selected = "";
								$TypeAircraft="";
								$Position="";
								$Id_TypeAircraft=0;
								$AircraftSelect=$_SESSION['GPAO_Aircraft'];
								if($_POST){$AircraftSelect=$_POST['aircraft'];}
								$_SESSION['GPAO_Aircraft']=$AircraftSelect;	
								
								if ($nbAircraft > 0)
								{
									while($row=mysqli_fetch_array($resultAircraft))
									{
										$selected="";
										if($AircraftSelect==$row['Id']){
											$selected="selected";
											$Id_TypeAircraft=substr($row['AircraftType'],0,strpos($row['AircraftType']," "));
											if($Id_TypeAircraft==""){$Id_TypeAircraft=$row['AircraftType'];}
											$TypeAircraft=$row['AircraftType'];
											$Position=$row['Position'];
										}
										echo "<option value='".$row['Id']."' ".$selected.">".$row['MSN']."</option>\n";
									}
								 }
							?>
							</select>
							<a href="javascript:OuvreFenetreAjoutAircraft()">
								<img src='../../Images/add.png' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>'>
							</a>
						</td>
						<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Type";}else{echo "Type";} ?> :</td>
						<td class="Libelle" width='60%'>
							<?php 
								echo $TypeAircraft;
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php if($_SESSION['GPAO_Aircraft']>0){ 
		?>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center">
				<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Client";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Imputation";}else{echo "Imputation";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "AM/BNC";}else{echo "AM/BNC";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "OF/OT";}else{echo "OF/OT";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "QLB";}else{echo "QLB";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "TLB";}else{echo "TLB";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Concession";}else{echo "Concession";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Para";}else{echo "Para";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Priority";}else{echo "Priorité";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Plan date";}else{echo "Date planifiée";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "First EoW";}else{echo "First EoW";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Designation";}else{echo "Designation";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Target time";}else{echo "Temps alloué";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%">%</td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Creation date";}else{echo "Date de création";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Created by";}else{echo "Créé par";} ?></td>
						<td class="EnTeteTableauCompetences" width="2%"></td>
					</tr>
				<?php 
					$req="SELECT Id,
							(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
							(SELECT Libelle FROM gpao_imputation WHERE Id=Id_Imputation) AS Imputation,
							(SELECT Libelle FROM gpao_priority WHERE Id=Id_Priority) AS Priority,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_CreatedBy) AS CreatedBy,
							Para,AM,NC,Concession,OF,QLB,TLB,TargetTime,LimitDateFOT,PlanDate,WorkingProgress,
							Designation,Comments,CreationDate
						FROM gpao_wo 
						WHERE Suppr=0
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
						AND Id_Aircraft=".$_SESSION['GPAO_Aircraft']." ";
					$resultList=mysqli_query($bdd,$req);
					$nbList=mysqli_num_rows($resultList);

					if ($nbList > 0)
					{
						$couleur="#ffffff";
						while($rowList=mysqli_fetch_array($resultList))
						{
							$laCouleur=$couleur;
							$couleurTexte="";
							if($_SESSION['GPAO_IdWO']>0){
								if($_SESSION['GPAO_IdWO']==$rowList['Id']){
									$laCouleur="#00325F";
									$couleurTexte="style='color:white;'";
								}
							}
				?>
					<tr bgcolor="<?php echo $laCouleur;?>" style='cursor:pointer;'>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['Customer']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['Imputation']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['AM']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['OF']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['NC']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['QLB']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['TLB']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['Concession']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['Para']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['Priority']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo AfficheDateJJ_MM_AAAA($rowList['PlanDate']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo AfficheDateJJ_MM_AAAA($rowList['LimitDateFOT']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes(substr($rowList['Designation'],0,30)); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['TargetTime']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['WorkingProgress']); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo $rowList['CreationDate']; ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes(substr($rowList['Comments'],0,30)); ?></td>
						<td <?php echo $couleurTexte;?> onclick="SelectionnerWO(<?php echo stripslashes($rowList['Id']); ?>,'<?php echo $_SESSION['Menu']; ?>');"><?php echo stripslashes($rowList['CreatedBy']); ?></td>
						<td <?php echo $couleurTexte;?> align="center">
							<a href="javascript:OuvreFenetreSuppr(<?php echo $rowList['Id']; ?>,'<?php echo $_SESSION['Menu']; ?>')">
								<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
							</a>
						</td>
					</tr>
				<?php
						if($couleur=="#ffffff"){$couleur="#6EB4CD";}
						else{$couleur="#ffffff";}
						}
					}
				?>
				<tr>
					<td colspan="5">
						<input class="Bouton" onclick="SelectionnerWO(-1,'<?php echo $_SESSION['Menu']; ?>')" style="font-size:15px;" name="Btn_Add"  value="<?php if($_SESSION['Langue']=="EN"){echo "Add WO";}else{echo "Add WO";}?>">
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<?php
		if($_SESSION['GPAO_IdWO']<>0){
			
			$Id_Customer=-1;
			$id_Imputation=0;
			$position="";
			$skill="";
			$id_CostCenter=0;
			$id_Priority=0;
			$para="";
			$AM="";
			$NC="";
			$concession="";
			$OF="";
			$QLB="";
			$TLB="";
			$targetTime="";
			$escalationPoint=-1;
			$id_Priority=0;
			$priorityReason="";
			$firstEoW="";
			$newEoW="";
			$lastEoW="TBD";
			$EoWTandem="";
			$planDate="";
			$workingShift=0;
			$workingProgress="";
			$closureDate="";
			$OTDEoW=-1;
			$OTDComment="";
			$designation="";
			$commentACMS1="";
			$commentACMS2="";
			$createdBy="";
			$creationDate=AfficheDateJJ_MM_AAAA(date('Y-m-d'));
			
			if($_POST){
				if(isset($_POST['customer'])){$Id_Customer=$_POST['customer'];}
				if(isset($_POST['imputation'])){$id_Imputation=$_POST['imputation'];}
				if(isset($_POST['position'])){$position=$_POST['position'];}
				if(isset($_POST['skill'])){$skill=$_POST['skill'];}
				if(isset($_POST['costcenter'])){$id_CostCenter=$_POST['costcenter'];}
				if(isset($_POST['priority'])){$id_Priority=$_POST['priority'];}
				if(isset($_POST['para'])){$para=$_POST['para'];}
				if(isset($_POST['am'])){$AM=$_POST['am'];}
				if(isset($_POST['nc'])){$NC=$_POST['nc'];}
				if(isset($_POST['concession'])){$concession=$_POST['concession'];}
				if(isset($_POST['ofot'])){$OF=$_POST['ofot'];}
				if(isset($_POST['qlb'])){$QLB=$_POST['qlb'];}
				if(isset($_POST['tlb'])){$TLB=$_POST['tlb'];}
				if(isset($_POST['targetTime'])){$targetTime=$_POST['targetTime'];}
				if(isset($_POST['escalationpoint'])){$escalationPoint=$_POST['escalationpoint'];}
				if(isset($_POST['priorityReason'])){$priorityReason=$_POST['priorityReason'];}
				if(isset($_POST['firstEoW'])){$firstEoW=$_POST['firstEoW'];}
				if(isset($_POST['newEoW'])){$newEoW=$_POST['newEoW'];}
				if($_POST['firstEoW']<>""){
					if($_POST['newEoW']<>""){
						$lastEoW=AfficheDateJJ_MM_AAAA(TrsfDate_($_POST['newEoW']));
					}
					else{
						$lastEoW=AfficheDateJJ_MM_AAAA(TrsfDate_($_POST['firstEoW']));
					}
				}
				if(isset($_POST['EoWTandem'])){$EoWTandem=$_POST['EoWTandem'];}
				if(isset($_POST['plandate'])){$planDate=$_POST['plandate'];}
				if(isset($_POST['workingShift'])){$workingShift=$_POST['workingShift'];}
				if(isset($_POST['workingProgress'])){$workingProgress=$_POST['workingProgress'];}
				if(isset($_POST['closureDate'])){$closureDate=$_POST['closureDate'];}
				if(isset($_POST['otdEoW'])){$OTDEoW=$_POST['otdEoW'];}
				if(isset($_POST['otdComment'])){$OTDComment=$_POST['otdComment'];}
				if(isset($_POST['designation'])){$designation=$_POST['designation'];}
				if(isset($_POST['commentACMS1'])){$commentACMS1=$_POST['commentACMS1'];}
				if(isset($_POST['commentACMS2'])){$commentACMS2=$_POST['commentACMS2'];}
				if(isset($_POST['createdBy'])){$createdBy=$_POST['createdBy'];}
				if(isset($_POST['creationDate'])){$creationDate=$_POST['creationDate'];}
			}
			else{
				if($_SESSION['GPAO_IdWO']==-1){
					//CREATION D'UN WO
					$req="SELECT Position FROM gpao_aircraft WHERE Id=".$_SESSION['GPAO_Aircraft']." ";
					$resultMSN=mysqli_query($bdd,$req);
					$nbMSN=mysqli_num_rows($resultMSN);

					if ($nbMSN > 0)
					{
						$rowMSN=mysqli_fetch_array($resultMSN);
						$position=$rowMSN['Position'];
					}
				}
				else{
					//MODIFICATION D'UN WO
					$req="SELECT Id,Id_Customer,Id_Imputation,Skills,Id_CostCenter,
							(SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Position,
							Para,AM,NC,Concession,
							OF,QLB,TLB,
							TargetTime,EscalationPoint,Id_Priority,PriorityReason,
							LimitDateFOT,NewEoW,LastEoW,UpdateDateTandem,
							PlanDate,Id_WorkingShift,WorkingProgress,ClosureDate,
							OTDEoW,OTDComment,
							Designation,Comments,CommentsA_CMS2,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_CreatedBy) AS CreatedBy,
							Id_CreatedBy,CreationDate
						FROM gpao_wo 
						WHERE Id=".$_SESSION['GPAO_IdWO']." ";
					$resultList=mysqli_query($bdd,$req);
					$nbList=mysqli_num_rows($resultList);

					if ($nbList > 0)
					{
						$rowList=mysqli_fetch_array($resultList);
						
						$Id_Customer=$rowList['Id_Customer'];
						$id_Imputation=$rowList['Id_Imputation'];
						$position=stripslashes($rowList['Position']);
						$skill=stripslashes($rowList['Skills']);
						$id_CostCenter=$rowList['Id_CostCenter'];
						$id_Priority=$rowList['Id_Priority'];
						$para=stripslashes($rowList['Para']);
						$AM=stripslashes($rowList['AM']);
						$NC=stripslashes($rowList['NC']);
						$concession=stripslashes($rowList['Concession']);
						$OF=stripslashes($rowList['OF']);
						$QLB=stripslashes($rowList['QLB']);
						$TLB=stripslashes($rowList['TLB']);
						$targetTime=stripslashes($rowList['TargetTime']);
						$escalationPoint=stripslashes($rowList['EscalationPoint']);
						$priorityReason=stripslashes($rowList['PriorityReason']);
						$firstEoW=AfficheDateFR($rowList['LimitDateFOT']);
						$newEoW=AfficheDateFR($rowList['NewEoW']);
						if(AfficheDateJJ_MM_AAAA($rowList['LastEoW'])==""){
							$lastEoW="TBD";
						}
						else{
							$lastEoW=AfficheDateJJ_MM_AAAA($rowList['LastEoW']);
						}
						$EoWTandem=AfficheDateFR($rowList['UpdateDateTandem']);
						$planDate=AfficheDateFR($rowList['PlanDate']);
						$workingShift=stripslashes($rowList['Id_WorkingShift']);
						$workingProgress=stripslashes($rowList['WorkingProgress']);
						$closureDate=AfficheDateFR($rowList['ClosureDate']);
						$OTDEoW=stripslashes($rowList['OTDEoW']);
						$OTDComment=stripslashes($rowList['OTDComment']);
						$designation=stripslashes($rowList['Designation']);
						$commentACMS1=stripslashes($rowList['Comments']);
						$commentACMS2=stripslashes($rowList['CommentsA_CMS2']);
						$createdBy=stripslashes($rowList['CreatedBy']);
						$creationDate=$rowList['CreationDate'];

					}
							
				}
			}
		?>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center">
				<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="Libelle" width='5%'>
						</td>
						<td width='7%'>
						</td>
						<td class="Libelle" width='5%'>
						</td>
						<td width='7%'>
						</td>
						<td class="Libelle" width='5%'>
						</td>
						<td width='7%'>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Created by";}else{echo "Créé par";} ?> :</td>
						<td width='7%'>
							<?php echo $createdBy;?>
							<input type="hidden" name="createdBy" id="Menu" value="<?php echo $createdBy; ?>" />
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Creation date";}else{echo "Date de création";} ?> :</td>
						<td width='7%'>
							<?php echo $creationDate;?>
							<input type="hidden" name="creationDate" id="Menu" value="<?php echo $creationDate; ?>" />
						</td>
					</tr>
					<tr><td height="4"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Customer";} ?> :</td>
						<td width='7%'>
							<select class="customer" name="customer" style="width:130px;" onchange="submit();">
							<?php
								if($_SESSION['GPAO_IdWO']<=0){
							?>
									<option value="0"></option>
							<?php
								}
								$req="SELECT Id,Libelle
								FROM gpao_customer
								WHERE Suppr=0 
								AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
								if($_SESSION['GPAO_IdWO']>0){
									$req.="AND Id=".$Id_Customer." ";
								}
								$req.="ORDER BY Libelle";
								$resultList=mysqli_query($bdd,$req);
								$nbList=mysqli_num_rows($resultList);
								
								if ($nbList > 0)
								{
									while($rowList=mysqli_fetch_array($resultList))
									{
										$selected="";
										if($Id_Customer==$rowList['Id']){$selected="selected";}
										echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
									}
								 }
							?>
							</select>
						</td>
						
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Imputation";}else{echo "Imputation";} ?> :</td>
						<td width='7%'>
							<select class="imputation" name="imputation" style="width:130px;">
								<option value="0"></option>
							<?php
								$req="SELECT Id,Libelle
								FROM gpao_imputation
								WHERE Suppr=0
								AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
								ORDER BY Libelle";
								$resultList=mysqli_query($bdd,$req);
								$nbList=mysqli_num_rows($resultList);

								if ($nbList > 0)
								{
									while($rowList=mysqli_fetch_array($resultList))
									{
										$selected="";
										if($id_Imputation==$rowList['Id']){$selected="selected";}
										echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
									}
								 }
							?>
							</select>
						</td>
						
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Position";}else{echo "Position";} ?> :</td>
						<td width='7%'>
							<select class="position" name="position" style="width:50px;">
								<option value="0"></option>
							<?php
								$tab=array("FL","L1","L2","L3","L4","WA","WB","WP");

								foreach($tab as $valeur)
								{
									$selected="";
									if($position==$valeur){$selected="selected";}
									echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
								}
							?>
							</select>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Skills";}else{echo "Skills";} ?> :</td>
						<td width='7%'>
							<select class="skill" name="skill" style="width:150px;">
								<option value="0"></option>
							<?php
								$tab=array("ELECTRIC","MECHANIC","SEALER","STRUCTURE");

								foreach($tab as $valeur)
								{
									$selected="";
									if($skill==$valeur){$selected="selected";}
									echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
								}
							?>
							</select>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Cost center";}else{echo "Cost center";} ?> :</td>
						<td width='7%'>
							<select class="costcenter" name="costcenter" <?php if($_SESSION['GPAO_IdWO']>0){echo "disabled='disabled'";}?> readonly='readonly' style="width:130px;">
							<?php
								$nb=0;
								if($_POST || $_SESSION['GPAO_IdWO']>0){
									$req="SELECT Id,Libelle
									FROM gpao_costcenter
									WHERE Suppr=0
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
									AND AircraftType=(SELECT Correspondance FROM gpao_aircrafttypecorrespondance WHERE gpao_aircrafttypecorrespondance.AircraftType='".$Id_TypeAircraft."' LIMIT 1)
									AND Id_Customer=".$Id_Customer."
									ORDER BY Libelle";
									$resultList=mysqli_query($bdd,$req);
									$nbList=mysqli_num_rows($resultList);
									$nb=$nbList;

									if ($nbList > 0)
									{
										while($rowList=mysqli_fetch_array($resultList))
										{
											$selected="";
											echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
										}
									}
								}
								
								if($nb==0){
							?>
									<option value="0"></option>
							<?php
								}
							?>
							</select>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Para";}else{echo "Para";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="para" id="para" size="15" value="<?php echo $para;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "AM/BNC";}else{echo "AM/BNC";} ?> :</td>
						<td width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="am" id="am" size="15" value="<?php echo $AM; ?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";} ?> :</td>
						<td width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="nc" id="nc" size="15" value="<?php echo $NC;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Concession";}else{echo "Concession";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="concession" id="concession" size="15" value="<?php echo $concession;?>">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "OF/OT";}else{echo "OF/OT";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="ofot" id="ofot" size="15" value="<?php echo $OF;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "QLB";}else{echo "QLB";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="qlb" id="qlb" size="15" value="<?php echo $QLB;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "TLB";}else{echo "TLB";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="tlb" id="tlb" size="15" value="<?php echo $TLB;?>">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Target time";}else{echo "Temps alloué";} ?> :</td>
						<td width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="targetTime" id="targetTime" size="8" value="<?php echo $targetTime;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Escalation point";}else{echo "Escalation point";} ?> :</td>
						<td width='7%'>
							<select class="escalationpoint" name="escalationpoint" style="width:50px;">
								<option value="0"></option>
							<?php
								$tab=array(array(1,"Yes"),array(0,"No"));

								foreach($tab as $valeur)
								{
									$selected="";
									if($escalationPoint==$valeur[0]){$selected="selected";}
									echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
								}
							?>
							</select>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Priority";}else{echo "Priorité";} ?> :</td>
						<td width='7%'>
							<select class="priority" name="priority" style="width:130px;">
								<option value="0"></option>
							<?php
								$req="SELECT Id,Libelle
								FROM gpao_priority
								WHERE Suppr=0
								AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
								ORDER BY Libelle";
								$resultList=mysqli_query($bdd,$req);
								$nbList=mysqli_num_rows($resultList);

								if ($nbList > 0)
								{
									while($rowList=mysqli_fetch_array($resultList))
									{
										$selected="";
										if($id_Priority==$rowList['Id']){$selected="selected";}
										echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
									}
								 }
							?>
							</select>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Priority reason";}else{echo "Priority reason";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="priorityReason" id="priorityReason" size="20" value="<?php echo $priorityReason;?>">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "First EoW";}else{echo "Date limite";} ?> :</td>
						<td width='7%'>
							<input type="date" name="firstEoW" id="firstEoW" size="20" value="<?php echo $firstEoW;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "New EoW";}else{echo "New EoW";} ?> :</td>
						<td width='7%'>
							<input type="date" name="newEoW" id="newEoW" size="20" value="<?php echo $newEoW;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Last EoW";}else{echo "Last EoW";} ?> :</td>
						<td width='7%'>
							<input readonly='readonly' name="lastEoW" id="lastEoW" size="10" value="<?php echo $lastEoW;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "EoW in Tandem";}else{echo "EoW in Tandem";} ?> :</td>
						<td width='7%'>
							<input type="date" name="EoWTandem" id="EoWTandem" size="20" value="<?php echo $EoWTandem;?>">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Plan date";}else{echo "Date planifiée";} ?> :</td>
						<td width='7%'>
							<input type="date" name="plandate" id="plandate" size="20" value="<?php echo $planDate;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Working shift";}else{echo "Working shift";} ?> :</td>
						<td width='7%'>
							<select class="workingShift" name="workingShift" style="width:130px;">
								<option value="0"></option>
							<?php
								$req="SELECT Id,Libelle
								FROM gpao_workingshifts
								WHERE Suppr=0
								AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
								ORDER BY Libelle";
								$resultList=mysqli_query($bdd,$req);
								$nbList=mysqli_num_rows($resultList);

								if ($nbList > 0)
								{
									while($rowList=mysqli_fetch_array($resultList))
									{
										$selected="";
										if($workingShift==$rowList['Id']){$selected="selected";}
										echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
									}
								 }
							?>
							</select>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Working <br>progress";}else{echo "Avancement";} ?> :</td>
						<td class="Libelle" width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="workingProgress" id="workingProgress" size="6" value="<?php echo $workingProgress;?>"> %
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Closure date";}else{echo "Closure date";} ?> :</td>
						<td width='7%'>
							<input type="date" readonly='readonly' name="closureDate" id="closureDate" size="20" value="<?php echo $closureDate;?>">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "OTD EoW";}else{echo "OTD EoW";} ?> :</td>
						<td width='7%'>
							<select class="otdEoW" name="otdEoW" style="width:50px;">
								<option value="0"></option>
							<?php
								$tab=array(array(1,"Yes"),array(0,"No"));

								foreach($tab as $valeur)
								{
									$selected="";
									if($OTDEoW==$valeur[0]){$selected="selected";}
									echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
								}
							?>
							</select>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "OTD Comment";}else{echo "OTD Comment";} ?> :</td>
						<td width='7%'>
							<select class="otdComment" name="otdComment" style="width:150px;">
								<option value=""></option>
							<?php
								$tab=array("Missing documentation/assessment","Missing tools","No access_IC not signed","No Access_Intervention by third parties","No manpower","No qualification/competencies","Rework - Coordination;Rework - Production","Rework - Quality;Rework - Third parties","RRA Process","Waiting for dress Out Airbus;Waiting for MAP","Waiting for NDT;Waiting for paintshop","Waiting for Para to be stamped","Waiting for parts/fasteners/chemical products","Waiting for sandblasting","Work done in time - Concession INP");

								foreach($tab as $valeur)
								{
									$selected="";
									if($OTDComment==$valeur){$selected="selected";}
									echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
								}
							?>
							</select>
						</td>
					</tr>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%' valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Designation";}else{echo "Désignation";} ?> :</td>
						<td width='7%' colspan="3" valign="top">
							<textarea id="designation" name="designation" rows="3" cols="80" style="resize:none;"><?php echo $designation;?></textarea>
						</td>
						<td colspan="4" rowspan="5" valign="top">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td class="EnTeteTableauCompetences" width="24%"><?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Status";} ?></td>
									<td class="EnTeteTableauCompetences" width="18%"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "User Name";}else{echo "User Name";} ?></td>
									<td class="EnTeteTableauCompetences" width="38%"><?php if($_SESSION['Langue']=="EN"){echo "Status Comments";}else{echo "Status Comments";} ?></td>
									<td class="EnTeteTableauCompetences" width="2%">&nbsp;</td>
								</tr>
							</table>
							<div style="overflow:auto;height:200px;width:100%;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<?php 
										//LISTE DES STATUTS
										$req="SELECT Id,Id_StatutList,DateStatut,TimeUsed,Id_QualityControlType,Id_ImputationRework,Id_UserName,
											(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_UserName) AS UserName,
											IssueDetectedByCustomer,StatusComments,ICClosed,Id_NameResponsible,Id_NameResponsible2
											FROM gpao_statutquality
											WHERE Suppr=0 
											AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
											AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
											
										$resultQ=mysqli_query($bdd,$req);
										$nbResultaQ=mysqli_num_rows($resultQ);
										
										$totalQ=5;
										if ($nbResultaQ>=5){
											$totalQ=5+$nbResultaQ;
										}
										
										$couleur="#FFFFFF";
										for($i=0;$i<$totalQ;$i++){
											$Id_StatutList="";
											$DateStatutVisu="";
											$DateStatut=date('Y-m-d H:i:s');
											$TimeUsed="";
											$Id_QualityControlType="";
											$Id_ImputationRework="";
											$UserName="";
											$Id_UserName=$_SESSION['Id_Personne'];
											$IssueDetectedByCustomer="";
											$StatusComments="";
											$ICClosed="";
											$Id_NameResponsible="";
											$Id_NameResponsible2="";
											$trouve=1;

											if ($i<$nbResultaQ){
												$row=mysqli_fetch_array($resultQ);
												$Id_StatutList=$row['Id_StatutList'];
												$DateStatutVisu=$row['DateStatut'];
												$DateStatut=$row['DateStatut'];
												$TimeUsed=$row['TimeUsed'];
												$Id_QualityControlType=$row['Id_QualityControlType'];
												$Id_ImputationRework=$row['Id_ImputationRework'];
												$UserName=stripslashes($row['UserName']);
												$Id_UserName=$row['Id_UserName'];
												$IssueDetectedByCustomer=$row['IssueDetectedByCustomer'];
												$StatusComments=stripslashes($row['StatusComments']);
												$ICClosed=$row['ICClosed'];
												$Id_NameResponsible=$row['Id_NameResponsible'];
												$Id_NameResponsible2=$row['Id_NameResponsible2'];
												$trouve=0;
											}
									?>
											<tr bgcolor="<?php echo $couleur;?>">
												<td width="20%">
													<input type="hidden" id="id_ImputationRework_<?php echo $i;?>" name="id_ImputationRework_<?php echo $i;?>" size="20" value="<?php echo $Id_ImputationRework;?>">
													<input type="hidden" id="timeUsed_<?php echo $i;?>" name="timeUsed_<?php echo $i;?>" size="5" value="<?php echo $TimeUsed;?>" >
													<input type="hidden" id="id_QualityControlType_<?php echo $i;?>" name="id_QualityControlType_<?php echo $i;?>" size="5" value="<?php echo $Id_QualityControlType;?>" >
													<input type="hidden" id="issueDetectedByCustomer_<?php echo $i;?>" name="issueDetectedByCustomer_<?php echo $i;?>" size="5" value="<?php echo $IssueDetectedByCustomer;?>" >
													<input type="hidden" id="iCClosed_<?php echo $i;?>" name="iCClosed_<?php echo $i;?>" size="5" value="<?php echo $ICClosed;?>" >
													<input type="hidden" id="id_NameResponsible_<?php echo $i;?>" name="id_NameResponsible_<?php echo $i;?>" size="5" value="<?php echo $Id_NameResponsible;?>" >
													<input type="hidden" id="id_NameResponsible2_<?php echo $i;?>" name="id_NameResponsible2_<?php echo $i;?>" size="5" value="<?php echo $Id_NameResponsible2;?>" >
													
													<select id="id_StatutList_<?php echo $i;?>" name="id_StatutList_<?php echo $i;?>" style="width:150px;">
													<?php
														if($trouve==1){
															echo"<option name='0' value='0'></option>";
														}
														$req="SELECT Id, Libelle
															FROM gpao_statutlist 
															WHERE Suppr=0 
															AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
														if($trouve==1){
															$req.="	AND Libelle NOT LIKE 'REWORK%' ";
														}
														$req.="	ORDER BY Libelle";
														$result2=mysqli_query($bdd,$req);
														$nbResulta2=mysqli_num_rows($result2);
														
														$k=0;
														if ($nbResulta2>0){
															while($row2=mysqli_fetch_array($result2)){
																$selected="";
																if($row2['Id']==$Id_StatutList){
																	$selected="selected";
																}
																echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
															}
														}
													?>
													</select>
												</td>
												<td width="20%">
													<?php echo $DateStatutVisu; ?>
													<input type="hidden" id="dateStatut_<?php echo $i;?>" name="dateStatut_<?php echo $i;?>" size="20" value="<?php echo $DateStatut;?>">
												</td>
												<td class="Libelle" width="20%">
													<?php echo $UserName; ?>
													<input type="hidden" id="id_UserName_<?php echo $i;?>" name="id_UserName_<?php echo $i;?>" size="20" value="<?php echo $Id_UserName;?>">
												</td>
												<td width="40%">
													<input type="texte" id="statusComments_<?php echo $i;?>" name="statusComments_<?php echo $i;?>" size="30" value="<?php echo $StatusComments;?>" >
												</td>
											</tr>
											<tr bgcolor="<?php echo $couleur;?>"><td height="4" colspan="4"></td></tr>
									<?php
											if($couleur=="#FFFFFF"){
												$couleur="#E1E1D7";
											}
											else{
												$couleur="#FFFFFF";
											}
										}
									?>
										<tr>
											<td colspan="4" align="center">
												<input type="hidden" name="nbLigneQ" id="nbLigneQ" value="<?php echo $totalQ; ?>" />
											</td>
										</tr>
								</table>
							</div>
						</td>
						<td colspan="2" rowspan="3" align="left" valign="top">
							<?php 
								if($_SESSION['GPAO_IdWO']>0){
							?>
								<table width="99%" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('CMTE')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "CMTE";}else{echo "CMTE";}?>">
										</td>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Chemical Product')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Chemical Product";}else{echo "Chemical Product";}?>">
										</td>
									</tr>
									<tr><td height="4"></td></tr>
									<tr>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Production')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Production";}else{echo "Production";}?>">
										</td>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Intervention Card')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Intervention Card";}else{echo "Intervention Card";}?>">
										</td>
									</tr>
									<tr><td height="4"></td></tr>
									<tr>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Category of Work')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Category of Work";}else{echo "Category of Work";}?>">
										</td>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Coordination')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Coordination";}else{echo "Coordination";}?>">
										</td>
									</tr>
									<tr><td height="4"></td></tr>
									<tr>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Quality')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Quality";}else{echo "Quality";}?>">
										</td>
										<td width="50%" align="center">
											<input class="Bouton" onclick="OuvreFenetre('Concession')" style="font-size:15px;" value="<?php if($_SESSION['Langue']=="EN"){echo "Concession";}else{echo "Concession";}?>">
										</td>
									</tr>
								</table>
							<?php
								}
							?>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%' valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Comments <br>A/CM S1";}else{echo "Comments <br>A/CM S1";} ?> :</td>
						<td width='7%' colspan="3" valign="top">
							<textarea id="commentACMS1" name="commentACMS1" rows="3" cols="80" style="resize:none;"><?php echo $commentACMS1;?></textarea>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%' valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Comments <br>A/CM S2";}else{echo "Comments <br>A/CM S2";} ?> :</td>
						<td width='7%' colspan="3" valign="top">
							<textarea id="commentACMS2" name="commentACMS2" rows="3" cols="80" style="resize:none;"><?php echo $commentACMS2;?></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="2" align="center">
				<input class="Bouton" type="submit" onclick="if(VerifRemplissage()==true){stop();}" style="font-size:15px;" name="Btn_Enregistrer"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
			</td>
		</tr>
		<tr><td height="500"></td></tr>
		<?php 
		}
		} ?>
	</table>
</form>