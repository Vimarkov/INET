<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:50%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="12%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Person";}else{echo "Personne";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Dernière affectation";}else{echo "Last assignment";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Futur prestation";}else{echo "Future site";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date d'arrivée sur prestation";}else{echo "Arrival date on site";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
			</tr>
			<?php
				$req="SELECT Id,
						CONCAT(Nom,' ',Prenom) AS Personne,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id LIMIT 1) AS UER,
						Contrat,
						(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
							FROM rh_personne_contrat
							WHERE Suppr=0
							AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
							AND TypeDocument IN ('Nouveau','Avenant')
							ORDER BY DateDebut DESC, Id DESC LIMIT 1
						) AS ContratOPTEA,
						(SELECT Date_Fin
						FROM new_competences_personne_prestation 
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
						ORDER BY Date_Debut DESC
						LIMIT 1
						) AS DerniereAffectation,
						(SELECT Date_Debut
						FROM new_competences_personne_prestation 
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
						AND Date_Debut>='".date('Y-m-d')."'
						AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
						ORDER BY Date_Debut
						LIMIT 1
						) AS Date_Debut,
						(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
						FROM new_competences_personne_prestation 
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
						AND Date_Debut>='".date('Y-m-d')."'
						AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
						ORDER BY Date_Debut
						LIMIT 1
						) AS Prestation
					FROM new_rh_etatcivil 
					WHERE
						(
							Contrat IN ('CDI','CDIC','CDD','Alternance','Stage','AFPR')
							OR 
							(SELECT (SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
							FROM rh_personne_contrat
							WHERE Suppr=0
							AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
							AND TypeDocument IN ('Nouveau','Avenant')
							ORDER BY DateDebut DESC, Id DESC LIMIT 1)=0
						)
					AND
						(SELECT COUNT(Id_Plateforme) 
						FROM new_competences_personne_plateforme 
						WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id
						AND Id_Plateforme IN (11,14)
						)=0
					AND 
						(SELECT COUNT(Id_Prestation) 
						FROM new_competences_personne_prestation 
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
						AND Date_Debut<='".date('Y-m-d')."'
						AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
						)=0
					ORDER BY UER,Personne ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						$Contrat=$row['ContratOPTEA'];
						if($Contrat==""){
							$Contrat=$row['Contrat'];
						}
						?>
						<tr>
							<td style='border-bottom:1px dotted #001dcf' width="15%">
							<?php echo "<a class=\"TableCompetences\" href=\"javascript:OuvreFenetreProfil('Lecture','".$row['Id']."');\">".$row['Personne']."</a>";?></td>
							<td style='border-bottom:1px dotted #001dcf' width="15%"><?php echo $row['UER'];?></td>
							<td style='border-bottom:1px dotted #001dcf' width="15%"><?php if($row['Date_Debut']==''){echo AfficheDateJJ_MM_AAAA($row['DerniereAffectation']);}?></td>
							<td style='border-bottom:1px dotted #001dcf' width="15%"><?php echo $row['Prestation'];?></td>
							<td style='border-bottom:1px dotted #001dcf' width="15%"><?php echo AfficheDateJJ_MM_AAAA($row['Date_Debut']);?></td>
							<td style='border-bottom:1px dotted #001dcf' width="15%"><?php echo $Contrat;?></td>
						</tr>
						<?php
					}
				}
			?>
		</table>
	</td></tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
<style>
	/*code CSS */
	.tde {height:20px;width:20px;cursor:pointer;}
	.tdf {height:20px;width:20px;cursor:pointer;}
	#glob {display: flex;}
	#glob2 {display: flex;}
</style>
</body>
</html>