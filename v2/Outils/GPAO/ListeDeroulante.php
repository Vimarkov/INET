<script language="javascript">
	function ChoisirListe(Menu,Id){
		top.location="TableauDeBord.php?Menu="+Menu+"&Id="+Id;
		}
	function OuvreFenetreAjout(){
		var w=window.open("Ajout_Liste.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
		w.focus();
		}
	function OuvreFenetreSuppr(Id){
		if(window.confirm('Etes-vous sûr de vouloir supprimer l\'accès à cette prestation ?')){
			var w=window.open("Ajout_Liste.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
	function OuvreFenetreModif(Id)
		{
			var w= window.open("Ajout_Liste.php?Mode=M&Id="+Id,"PageLieu","status=no,menubar=no,width=800,height=250");
			w.focus();
		}
</script>

<?php 
if($_GET){
	if(isset($_GET['Id'])){
		$_SESSION['GPAO_Id_ListeDeroulante']=$_GET['Id'];
	}
}
?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="25%" valign="top">
			<table class="TableCompetences"  align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="Libelle">
						<?php if($_SESSION['Langue']=="EN"){echo "Fields list";}else{echo "Liste des champs";} ?>
					</td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td>
						<table align="center" width="100%" cellpadding="0" cellspacing="0">
							<?php 
								$req="SELECT Id, Libelle,NomTable
									FROM gpao_listederoulante
									WHERE Suppr=0
									ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($row=mysqli_fetch_array($result)){
										$select="";
										if($_SESSION['GPAO_Id_ListeDeroulante']==$row['Id']){
											$select="color:#E60032;";
										}
										?>
										<tr>
											<td <?php echo $select;?> ><a onclick="ChoisirListe(<?php echo $_SESSION['Menu'];?>,<?php echo $row['Id'];?>);" style="height:25px;cursor:pointer;<?php echo $select;?>">-> <?php echo $row['Libelle'];?></a></td>
										</tr>
										<?php
									}
								}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="75%"  valign="top">
			<?php 
				if($_SESSION['GPAO_Id_ListeDeroulante']>0){
					$req="SELECT Id, Libelle,NomTable
						FROM gpao_listederoulante
						WHERE Id=".$_SESSION['GPAO_Id_ListeDeroulante']." ";
					$resultListe=mysqli_query($bdd,$req);
					$nbResultaList=mysqli_num_rows($resultListe);
					if ($nbResultaList>0){
						$rowListe=mysqli_fetch_array($resultListe);
			?>
							<table align="center" width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td align="center">
										<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";} ?>&nbsp;</a>
									</td>
								</tr>
								<tr><td height="10"></td></tr>
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:60%;">
											<tr>
												<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php echo $rowListe['Libelle']; ?></td>
												<?php
												if($rowListe['NomTable']=="workers"){ 
												?>
													<td class="EnTeteTableauCompetences" width="20%" >Responsability</td>
												<?php
												}
												elseif($rowListe['NomTable']=="costcenter"){ 
												?>
													<td class="EnTeteTableauCompetences" width="20%" >Customer</td>
													<td class="EnTeteTableauCompetences" width="20%" >Aircraft type</td>
												<?php
												}
												elseif($rowListe['NomTable']=="subassembly"){ 
												?>
													<td class="EnTeteTableauCompetences" width="20%" >Cost center</td>
												<?php
												}
												elseif($rowListe['NomTable']=="wocategorylist"){ 
												?>
													<td class="EnTeteTableauCompetences" width="20%" >Customer</td>
													<td class="EnTeteTableauCompetences" width="20%" >Time used</td>
												<?php
												}
												elseif($rowListe['NomTable']=="aircraft"){ 
												?>
													<td class="EnTeteTableauCompetences" width="20%" >Aircraft type</td>
													<td class="EnTeteTableauCompetences" width="10%" >NT</td>
													<td class="EnTeteTableauCompetences" width="20%" >Aicraft destination</td>
													<td class="EnTeteTableauCompetences" width="10%" >Position</td>
													<td class="EnTeteTableauCompetences" width="20%" >Create at</td>
												<?php
												}
												elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){ 
												?>
													<td class="EnTeteTableauCompetences" width="20%" >Correspondance</td>
												<?php
												}
												?>
												<td class="EnTeteTableauCompetences" width="2%"></td>
												<td class="EnTeteTableauCompetences" width="2%"></td>
											</tr>
											<?php
												if($rowListe['NomTable']=="workers"){ 
													$req="SELECT Id, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Libelle,
														Responsability
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												elseif($rowListe['NomTable']=="coordinationworker"){ 
													$req="SELECT Id, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Libelle
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												elseif($rowListe['NomTable']=="costcenter"){ 
													$req="SELECT Id, Libelle,
														(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
														AircraftType
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												elseif($rowListe['NomTable']=="subassembly"){ 
													$req="SELECT Id, Libelle,
														(SELECT Libelle FROM gpao_costcenter WHERE Id=Id_CostCenter) AS CostCenter
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												elseif($rowListe['NomTable']=="wocategorylist"){ 
													$req="SELECT Id, Libelle,
														(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
														TimeUsed
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												elseif($rowListe['NomTable']=="aircraft"){ 
													$req="SELECT Id, MSN AS Libelle,NT,Position,CreateAT,
														(SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) AS AircraftType,
														(SELECT Libelle FROM gpao_aircraftdestination WHERE Id=Id_AircraftDestination) AS AircraftDestination
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){ 
													$req="SELECT Id,AircraftType AS Libelle,Correspondance
														FROM gpao_".$rowListe['NomTable']."
														WHERE Suppr=0
														AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
														ORDER BY Libelle;";
												}
												else{
													$req="SELECT Id, Libelle
													FROM gpao_".$rowListe['NomTable']."
													WHERE Suppr=0
													AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
													ORDER BY Libelle;";
												}
												
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$couleur="#ffffff";
													while($row=mysqli_fetch_array($result)){
														?>
														<tr bgcolor="<?php echo $couleur;?>">
															<td><?php echo $row['Libelle'];?></td>
															<?php
															if($rowListe['NomTable']=="workers"){ 
															?>
																<td><?php echo $row['Responsability'];?></td>
															<?php
															}
															elseif($rowListe['NomTable']=="costcenter"){ 
															?>
																<td><?php echo $row['Customer'];?></td>
																<td><?php echo $row['AircraftType'];?></td>
															<?php
															}
															elseif($rowListe['NomTable']=="subassembly"){ 
															?>
																<td><?php echo $row['CostCenter'];?></td>
															<?php
															}
															elseif($rowListe['NomTable']=="wocategorylist"){ 
															?>
																<td><?php echo $row['Customer'];?></td>
																<td><?php echo $row['TimeUsed'];?></td>
															<?php
															}
															elseif($rowListe['NomTable']=="aircraft"){ 
															?>
																<td><?php echo $row['AircraftType'];?></td>
																<td><?php echo $row['NT'];?></td>
																<td><?php echo $row['AircraftDestination'];?></td>
																<td><?php echo $row['Position'];?></td>
																<td><?php echo $row['CreateAT'];?></td>
															<?php
															}
															elseif($rowListe['NomTable']=="aircrafttypecorrespondance"){ 
															?>
																<td><?php echo $row['Correspondance'];?></td>
															<?php
															}
															?>
															<td align="center">
															<?php 
															if($rowListe['NomTable']<>"workers" && $rowListe['NomTable']<>"coordinationworker"){
															?>
																<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
																	<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>'>
																</a>
															<?php 
															}
															?>
															</td>
															<td align="center">
																<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
																	<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
																</a>
															</td>
														</tr>
														<?php
														if($couleur=="#ffffff"){$couleur="#a3e4ff";}
														else{$couleur="#ffffff";}
													}
												}
											?>
										</table>
									</td>
								</tr>
							</table>
			<?php 
					}
				}
			?>
		</td>
	</tr>
</table>