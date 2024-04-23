<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Personne";} ?></td>
				<?php 
					$req="SELECT Id, Libelle 
					FROM new_competences_qualification 
					WHERE Id_Categorie_Qualification=151 
					AND Id<>3777 
					ORDER BY Libelle ";
					$resultT=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultT);
					if ($nbTheme > 0)
					{
						while($rowT=mysqli_fetch_array($resultT))
						{
				?>
						<td width="10%" class="EnTeteTableauCompetences" style="text-align:center"><?php echo $rowT['Libelle'];?></td>
				<?php
						}
					}
				?>
			</tr>
			<?php
				$req="SELECT DISTINCT 
					new_rh_etatcivil.Id,
					new_rh_etatcivil.Nom,
					new_rh_etatcivil.Prenom,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id LIMIT 1) AS UER
					FROM new_competences_relation 
					LEFT JOIN new_rh_etatcivil 
					ON new_competences_relation.Id_Personne=new_rh_etatcivil.Id 
					WHERE new_competences_relation.Suppr=0
					AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
					ORDER BY (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id LIMIT 1),Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td>&nbsp;<?php echo $row['UER'];?></td>
							<td>&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
							<?php 
							$req="SELECT Id, Libelle 
							FROM new_competences_qualification 
							WHERE Id_Categorie_Qualification=151 
							AND Id<>3777 
							ORDER BY Libelle ";
							$resultT=mysqli_query($bdd,$req);
							while($rowTheme=mysqli_fetch_array($resultT))
									{
										$surveillable="<img width='15px' src='../../Images/delete.png' border='0' />";
										$req="SELECT Evaluation
											FROM new_competences_relation 
											WHERE Id_Qualification_Parrainage=".$rowTheme['Id']."
											AND Id_Personne=".$row['Id']."
											AND Evaluation IN ('L','X')
											AND Suppr=0
											AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
											ORDER BY Evaluation DESC";
										$resultSurveillable=mysqli_query($bdd,$req);
										$nbSurveillable=mysqli_num_rows($resultSurveillable);
										if ($nbSurveillable > 0)
										{
											$rowEva=mysqli_fetch_array($resultSurveillable);
											if($rowEva['Evaluation']=="L"){
												$surveillable="L";
											}
											else{
												$surveillable="<img width='15px' src='../../Images/tick.png' border='0' />";
											}
										}
							?>
										<td align="center" class="Libelle"><?php echo $surveillable;?></td>
							<?php			
									}
							?>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>