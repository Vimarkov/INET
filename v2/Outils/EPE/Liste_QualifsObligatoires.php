<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreQualif(Id)
	{
		var w= window.open("Modif_QualifObligatoire.php?Id="+Id,"PageQualif","status=no,menubar=no,width=600,height=200");
		w.focus();
	}
	function OuvreFenetreFormation(Id)
	{
		var w= window.open("Modif_FormationObligatoire.php?Id="+Id,"PageFormation","status=no,menubar=no,width=500,height=200");
		w.focus();
	}
	function OuvreFenetreFormationQualipso(Id)
	{
		var w= window.open("Modif_FormationQualipsoObligatoire.php?Id="+Id,"PageFormation","status=no,menubar=no,width=500,height=200");
		w.focus();
	}
</script>
<form id="formulaire" class="test" action="Liste_QualifsObligatoires.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Formations & Qualifications obligatoires";}else{echo "Compulsory training & qualifications";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="30%" colspan="4">
			<table  cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:'30%'">
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td width="30%" class="Libelle" align="center">
						<?php 
							$ObligatoireR=$_SESSION['FiltreEPEQualifFormation_Obligatoire'];
							if($_POST){$ObligatoireR=$_POST['ObligatoireR'];}
							$_SESSION['FiltreEPEQualifFormation_Obligatoire']=$ObligatoireR;
						?>
						Obligatoire : <select id="personne" style="width:100px;" name="ObligatoireR" onchange="submit();">
							<option value='' <?php if($ObligatoireR==""){echo "selected";} ?>></option>
							<option value='0' <?php if($ObligatoireR=="0"){echo "selected";} ?>>A Configurer</option>
							<option value='1' <?php if($ObligatoireR=="1"){echo "selected";} ?>>Obligatoire</option>
							<option value='-1' <?php if($ObligatoireR=="-1"){echo "selected";} ?>>Non Obligatoire</option>
							<option value='-2' <?php if($ObligatoireR=="-2"){echo "selected";} ?>>NA</option>
						</select>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="30%" >
			<table width="80%" align="center">
				<tr>
					<td width="100%" class="Libelle" bgcolor="f5f74b" align="center">
						<?php if($LangueAffichage=="FR"){echo "QUALIFICATIONS";}else{echo "QUALIFICATIONS";}?>
					</td>
				</tr>
			</table>
		</td>
		<td width="30%">
			<table width="80%" align="center">
				<tr>
					<td width="100%" class="Libelle" bgcolor="f5f74b" align="center">
						<?php if($LangueAffichage=="FR"){echo "FORMATIONS";}else{echo "TRAINING";}?>
					</td>
				</tr>
			</table>
		</td>
		<td width="30%">
			<table width="80%" align="center">
				<tr>
					<td width="100%" class="Libelle" bgcolor="f5f74b" align="center">
						<?php if($LangueAffichage=="FR"){echo "FORMATIONS QUALIPSO";}else{echo "QUALIPSO TRAINING";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="30%" valign="top">
			<table width="100%" valign="top">
				<tr>
					<td>
						<table>
							<tr>
								<td width="10"></td>
								<td>
									<table class="TableCompetences" style="width:100%;">
									<?php
										$result_Categorie_Maitre=mysqli_query($bdd,"SELECT * FROM new_competences_categorie_qualification_maitre ORDER BY Id ASC");
										while($row_Categorie_Maitre=mysqli_fetch_array($result_Categorie_Maitre))
										{
										?>
											<tr>
												<td colspan="4" class="Libelle" bgcolor="f5f74b" ><?php echo $row_Categorie_Maitre['Libelle'];?></td>
											</tr>
									<?php
										$req="SELECT new_competences_qualification.* 
										FROM new_competences_qualification, new_competences_categorie_qualification 
										WHERE new_competences_categorie_qualification.Id_Categorie_Maitre=".$row_Categorie_Maitre['Id']." 
										AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id 
										AND new_competences_qualification.Id NOT IN (1643,1644)
										";
										if($_SESSION['FiltreEPEQualifFormation_Obligatoire']<>""){
											$req.="AND new_competences_qualification.Obligatoire=".$_SESSION['FiltreEPEQualifFormation_Obligatoire']." ";
										}
										$req.="ORDER BY new_competences_categorie_qualification.Libelle ASC, new_competences_qualification.Libelle ASC";
										$result=mysqli_query($bdd,$req);
										$nbenreg=mysqli_num_rows($result);
										if($nbenreg>0)
										{
									?>
										<tr>
											<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
											<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie qualification";}else{echo "Qualification group";}?></td>
											<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Obligatoire";}else{echo "Mandatory";}?></td>
											<td class="EnTeteTableauCompetences"></td>
										</tr>
										<?php
											$Couleur="#EEEEEE";
											$Categorie=0;
											while($row=mysqli_fetch_array($result))
											{
												
												$btrouve=1;
												/*if($motcle<>""){
													if(stripos($row['Libelle'],$motcle)===false){
														$btrouve=0;
													}
													else{
														$btrouve=1;
													}
												}*/
												if($btrouve==1){
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												$result2=mysqli_query($bdd,"SELECT Libelle, Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=".$row['Id_Categorie_Qualification']);
												$row2=mysqli_fetch_array($result2);
												if($Categorie!=$row2['Libelle']){echo "<tr height='1' bgcolor='#66AACC'><td colspan='4'></td></tr>";}
												$Categorie=$row2['Libelle'];
												
												$obligatoire="A Configurer";
												if($row['Obligatoire']==1){$obligatoire="<img src='../../Images/Valider.png' border='0'>";}
												elseif($row['Obligatoire']==-1){$obligatoire="<img src='../../Images/Refuser.gif' border='0'>";}
												elseif($row['Obligatoire']==-2){$obligatoire="NA";}
										?>
										<tr bgcolor="<?php echo $Couleur;?>">
											<td width="70%"><?php echo $row['Libelle'];?></td>
											<td width="20%"><?php echo $row2['Libelle'];?></td>
											<td width="5%" align="center"><?php echo $obligatoire;?></td>
											<td width="5%">
												<a class="Modif" href="javascript:OuvreFenetreQualif('<?php echo $row['Id']; ?>');">
													<img src="../../Images/Modif.gif" border="0" alt="Modification">
												</a>
											</td>
											<td></td>
										<?php
												}
											}
										}		//Fin If
										}		// Fin Boucle Maitre
					mysqli_free_result($result);	// Libération des résultats
			?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="30%" valign="top">
			<table width="100%" valign="top">
				<tr>
					<td>
						<table class="TableCompetences" style="width:100%;">
						<?php
							$req="SELECT * 
							FROM new_competences_formation ";
							if($_SESSION['FiltreEPEQualifFormation_Obligatoire']<>""){
								$req.="WHERE new_competences_formation.Obligatoire=".$_SESSION['FiltreEPEQualifFormation_Obligatoire']." ";
							}
							$req.="ORDER BY Libelle ASC";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Obligatoire";}else{echo "Mandatory";}?></td>
								<td class="EnTeteTableauCompetences"></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$obligatoire="A Configurer";
								if($row['Obligatoire']==1){$obligatoire="<img src='../../Images/Valider.png' border='0'>";}
								elseif($row['Obligatoire']==-1){$obligatoire="<img src='../../Images/Refuser.gif' border='0'>";}
								elseif($row['Obligatoire']==-2){$obligatoire="NA";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($row['Libelle']);?></td>
								<td width="5%" align="center"><?php echo $obligatoire;?></td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreFormation('<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
							</tr>
						<?php
								}	//Fin boucle
							}		//Fin If
							mysqli_free_result($result);	// Libération des résultats
						?>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="30%" valign="top">
			<table width="100%" valign="top">
				<tr>
					<td>
						<table class="TableCompetences" style="width:100%;">
						<?php
							$req="SELECT form_formation_plateforme_parametres.Id, Obligatoire,
							(SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) AS Organisme,
							(SELECT Libelle
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_formation.Id
								AND Id_Langue=form_formation_plateforme_parametres.Id_Langue
								AND Suppr=0 LIMIT 1) AS Libelle,
							(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation,
							(SELECT Libelle FROM new_competences_plateforme WHERE Id=form_formation_plateforme_parametres.Id_Plateforme) AS Plateforme
							FROM form_formation 
							LEFT JOIN form_formation_plateforme_parametres
							ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
							WHERE form_formation.Suppr= 0 
							AND form_formation_plateforme_parametres.Suppr=0 
							AND (
									SELECT COUNT(form_formation_qualification.Id)
									FROM form_formation_qualification
									WHERE form_formation_qualification.Suppr=0
									AND form_formation_qualification.Masquer=0
									AND form_formation_qualification.Id_Formation=form_formation.Id
								)=0
							AND (SELECT COUNT(Id)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_formation.Id
								AND Id_Langue=form_formation_plateforme_parametres.Id_Langue
								AND Suppr=0) > 0 ";
							if($_SESSION['FiltreEPEQualifFormation_Obligatoire']<>""){
								$Obli=$_SESSION['FiltreEPEQualifFormation_Obligatoire'];
								if($Obli==-1){$Obli=0;}
								elseif($Obli==0 || $Obli==-2){$Obli=-1;}
								$req.="AND Obligatoire=".$Obli." ";
							}
							$req.="ORDER BY Libelle ASC";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organism";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Obligatoire";}else{echo "Mandatory";}?></td>
								<td class="EnTeteTableauCompetences"></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								if($row['Obligatoire']==1){$obligatoire="<img src='../../Images/Valider.png' border='0'>";}
								else{$obligatoire="<img src='../../Images/Refuser.gif' border='0'>";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($row['Plateforme']);?></td>
								<td><?php echo stripslashes($row['Libelle']);?></td>
								<td><?php echo stripslashes($row['Organisme']);?></td>
								<td width="5%" align="center"><?php echo $obligatoire;?></td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreFormationQualipso('<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
							</tr>
						<?php
								}	//Fin boucle
							}		//Fin If
							mysqli_free_result($result);	// Libération des résultats
						?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>