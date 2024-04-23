<?php
if($_POST)
{
	if(isset($_POST['btnEnregister'])){
		if($_SESSION['FiltreSODA_Annee']<>""){
			$req="SELECT Id,
				(SELECT COUNT(Id) FROM soda_objectif_theme WHERE Annee=".$_SESSION['FiltreSODA_Annee']." AND Id_Theme=soda_theme.Id) AS nbObjectif
				FROM soda_theme
				WHERE Suppr=0 
				AND Id<>8";
			$result=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($result);
			if ($nb > 0)
			{
				while($row=mysqli_fetch_array($result))
				{
					if($row['nbObjectif']==0){
						$req="INSERT INTO soda_objectif_theme (Annee,Id_Theme,PourcentageApplicabilite,PourcentageDiversite)
						VALUES (".$_SESSION['FiltreSODA_Annee'].",".$row['Id'].",".unNombreSinon0($_POST['applicabilite_annee'.$_SESSION['FiltreSODA_Annee'].'_Theme'.$row['Id']]).",".unNombreSinon0($_POST['diversite_annee'.$_SESSION['FiltreSODA_Annee'].'_Theme'.$row['Id']]).") ";
					}
					else{
						$req="UPDATE soda_objectif_theme 
						SET PourcentageApplicabilite=".unNombreSinon0($_POST['applicabilite_annee'.$_SESSION['FiltreSODA_Annee'].'_Theme'.$row['Id']]).",
						PourcentageDiversite=".unNombreSinon0($_POST['diversite_annee'.$_SESSION['FiltreSODA_Annee'].'_Theme'.$row['Id']])."
						WHERE Annee=".$_SESSION['FiltreSODA_Annee']."
						AND Id_Theme=".$row['Id']." ";
					}
					$result2=mysqli_query($bdd,$req);
				}
			}
			
			$req="SELECT Id,Libelle
				FROM soda_questionnaire
				WHERE Suppr=0 
				AND Id_Theme=8";
			$result=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($result);
			if ($nb > 0)
			{
				while($rowQ=mysqli_fetch_array($result))
				{
					$reqPlat="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id NOT IN (11,14)";
					$resultPla=mysqli_query($bdd,$reqPlat);
					$nbPla=mysqli_num_rows($resultPla);
					if ($nbPla > 0)
					{
						while($rowPla=mysqli_fetch_array($resultPla))
						{
							$req="SELECT NbSurveillance 
								FROM soda_objectif_theme 
								WHERE Annee=".$_SESSION['FiltreSODA_Annee']." 
								AND Id_Theme=8
								AND Id_Plateforme=".$rowPla['Id']."
								AND Id_Questionnaire=".$rowQ['Id']." ";
							$resultObj=mysqli_query($bdd,$req);
							$nbObj=mysqli_num_rows($resultObj);
							if($nbObj==0){
								$req="INSERT INTO soda_objectif_theme (Annee,Id_Theme,Id_Questionnaire,Id_Plateforme,NbSurveillance)
								VALUES (".$_SESSION['FiltreSODA_Annee'].",8,".$rowQ['Id'].",".$rowPla['Id'].",".unNombreSinon0($_POST['nbSurveillance_annee'.$_SESSION['FiltreSODA_Annee'].'_Questionnaire'.$rowQ['Id'].'_UER'.$rowPla['Id']]).") ";
							}
							else{
								$req="UPDATE soda_objectif_theme 
								SET NbSurveillance=".unNombreSinon0($_POST['nbSurveillance_annee'.$_SESSION['FiltreSODA_Annee'].'_Questionnaire'.$rowQ['Id'].'_UER'.$rowPla['Id']])."
								WHERE Annee=".$_SESSION['FiltreSODA_Annee']."
								AND Id_Theme=8
								AND Id_Questionnaire=".$rowQ['Id']."
								AND Id_Plateforme=".$rowPla['Id']."								
								";
							}
							$result2=mysqli_query($bdd,$req);
						}
					}
				}
			}
			
			$req="SELECT Id FROM soda_objectif WHERE Annee=".$_SESSION['FiltreSODA_Annee']." ";
			$result=mysqli_query($bdd,$req);
			$nb=mysqli_num_rows($result);
			if($nb==0){
				$req="INSERT INTO soda_objectif (Annee,Regle)
						VALUES (".$_SESSION['FiltreSODA_Annee'].",'".addslashes($_POST['regle'])."') ";
			}
			else{
				$req="UPDATE soda_objectif
						SET Regle='".addslashes($_POST['regle'])."'
						WHERE Annee=".$_SESSION['FiltreSODA_Annee']." ";
			}
			$result2=mysqli_query($bdd,$req);
		}
	}
}
?>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="80%" valign="top">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td colspan="2">
						<table align="center" style="width:30%; border-spacing:0; align:center;" class="GeneralInfo">
							<tr><td height="4"></td></tr>
							<tr>
								<td width="15%" class="Libelle">
									&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Année";}else{echo "Year";}?> :
								</td>
								<td width="85%">
									<select id="annee" name="annee" onchange="submit();">
									<?php
										$annee=$_SESSION['FiltreSODA_Annee'];
										if($_POST){$annee=$_POST['annee'];}
										$_SESSION['FiltreSODA_Annee']=$annee;
										
										for($i=2022;$i<=date('Y')+1;$i++){
											$selected="";
											if($i==$_SESSION['FiltreSODA_Annee']){$selected="selected";}
											echo "<option value='".$i."' ".$selected.">".$i."</option>";
										}
									 ?>
									</select>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right"><input class="Bouton" name="btnEnregister" type="submit" <?php if($_SESSION['Langue']=="FR"){echo "value='Enregistrer'";}else{echo "value='Save'";} ?> /></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>			
				<tr>
					<td width="50%">
						<?php
							$req="SELECT Id,Libelle,
								(SELECT PourcentageApplicabilite FROM soda_objectif_theme WHERE Annee=".$_SESSION['FiltreSODA_Annee']." AND Id_Theme=soda_theme.Id LIMIT 1) AS PourcentageApplicabilite,
								(SELECT PourcentageDiversite FROM soda_objectif_theme WHERE Annee=".$_SESSION['FiltreSODA_Annee']." AND Id_Theme=soda_theme.Id LIMIT 1) AS PourcentageDiversite
								FROM soda_theme
								WHERE Suppr=0 
								AND Id<>8
								ORDER BY Libelle ";
							$result=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($result);
							if ($nb > 0)
							{
							?>
							<table align="center" class="TableCompetences" style="width:90%;">
								<tr>
									<td width="40%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Thématique";}else{echo "Thematic";}?></td>
									<td width="5%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Pourcentage<br> d'applicabilité";}else{echo "Percentage<br> of applicability";}?></td>
									<td width="5%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Pourcentage<br> de diversité";}else{echo "Percentage<br> of diversity";}?></td>
								</tr>
							<?php
								$Couleur="#EEEEEE";
								while($rowT=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}

							?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td><?php echo stripslashes($rowT['Libelle']);?></td>
									<td><input size="5" onKeyUp="nombre(this)" name="applicabilite_annee<?php echo $_SESSION['FiltreSODA_Annee'];?>_Theme<?php echo $rowT['Id'];?>" value="<?php echo $rowT['PourcentageApplicabilite'];?>";/>%</td>
									<td><input size="5" onKeyUp="nombre(this)" name="diversite_annee<?php echo $_SESSION['FiltreSODA_Annee'];?>_Theme<?php echo $rowT['Id'];?>" value="<?php echo $rowT['PourcentageDiversite'];?>"/>%</td>
								</tr>
							<?php
								}	//Fin boucle
							?>
							</table>
							<?php 
							}
							?>
					</td>
					<td width="50%">
						<table align="center" class="TableCompetences" style="width:100%;">
							<tr>
								<?php 
									$regle="";
									$req="SELECT Regle
										FROM soda_objectif
										WHERE Annee=".$_SESSION['FiltreSODA_Annee']." ";
									$result=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($result);
									if ($nb > 0)
									{
										$rowR=mysqli_fetch_array($result);
										$regle=stripslashes($rowR['Regle']);
									}
								?>
								<td width="10%" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Règles métiers sur l'application des surveillances";}else{echo "Business rules on the application of monitoring";}?></td>
								<td width="90%"><textarea name="regle" cols="100" rows="10"  style="font-size:14px;resize:none;"><?php echo $regle;?></textarea></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
							$req="SELECT Id,Libelle
								FROM soda_questionnaire
								WHERE Suppr=0 
								AND Id_Theme=8
								ORDER BY Libelle ";
							$result=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($result);
							if ($nb > 0)
							{
							?>
							<table align="center" class="TableCompetences" style="width:100%;">
								<?php 
									$reqPlat="SELECT Id, Libelle
									FROM new_competences_plateforme
									WHERE Id NOT IN (11,14)";
									$resultPla=mysqli_query($bdd,$reqPlat);
									$nbPla=mysqli_num_rows($resultPla);
								?>
								<tr>
									<td></td>
									<td class="EnTeteTableauCompetences" style="text-align:center;" colspan="<?php echo $nbPla;?>"><?php if($LangueAffichage=="FR"){echo "Volume de surveillances à réaliser";}else{echo "Volume of monitoring to be carried out";}?></td>
								</tr>
								<tr>
									<td width="60%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Questionnaire PROCESSUS";}else{echo "PROCESSUS Questionnaire";}?></td>
									<?php 
										if ($nbPla > 0)
										{
											while($rowPla=mysqli_fetch_array($resultPla))
											{
									?>
											<td width="2%" class="EnTeteTableauCompetences"><?php echo $rowPla['Libelle'];?></td>
									<?php
											}
										}
									?>
								</tr>
							<?php
								$Couleur="#EEEEEE";
								while($rowQ=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									$resultPla=mysqli_query($bdd,$reqPlat);
									if ($nbPla > 0)
									{

							?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td><?php echo stripslashes($rowQ['Libelle']);?></td>
							<?php
									while($rowPla=mysqli_fetch_array($resultPla))
									{
										$req="SELECT NbSurveillance 
											FROM soda_objectif_theme 
											WHERE Annee=".$_SESSION['FiltreSODA_Annee']." 
											AND Id_Theme=8
											AND Id_Plateforme=".$rowPla['Id']."
											AND Id_Questionnaire=".$rowQ['Id']." ";
										$resultObj=mysqli_query($bdd,$req);
										$nbObj=mysqli_num_rows($resultObj);
										$nbSurveillance="";
										if ($nbObj > 0)
										{
											$rowObj=mysqli_fetch_array($resultObj);
											$nbSurveillance=$rowObj['NbSurveillance'];
											if($nbSurveillance==0){$nbSurveillance="";}
										}
							?>
										<td><input size="3" onKeyUp="nombre(this)" name="nbSurveillance_annee<?php echo $_SESSION['FiltreSODA_Annee'];?>_Questionnaire<?php echo $rowQ['Id'];?>_UER<?php echo $rowPla['Id'];?>" value="<?php echo $nbSurveillance;?>"/></td>
							<?php			
									}
							?>
								</tr>
							<?php
									}
								}	//Fin boucle
							?>
							</table>
							<?php 
							}
							?>
					</td>
				</tr>
				<tr><td height="300px"></td></tr>
			</table>
		</td>
	</tr>
	
</table>
</body>
</html>