<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:80%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site not specified";}else{echo "Prestation non renseignée";} ?></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,SousSurveillance,
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER
					FROM new_competences_prestation
					WHERE Active=0
					AND SousSurveillance = ''
					AND Id_Plateforme NOT IN (11,14)
					ORDER BY UER,Libelle ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td><?php echo $row['UER'];?></td>
							<td><?php echo $row['Libelle'];?></td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:80%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?></td>
				<td class="EnTeteTableauCompetences" width="30%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Reason not monitoring";}else{echo "Raison non surveillance";} ?></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,SousSurveillance,
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER
					FROM new_competences_prestation
					WHERE Active=0
					AND SousSurveillance NOT IN ('','Oui/Yes')
					ORDER BY UER,Libelle ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td><?php echo $row['UER'];?></td>
							<td><?php echo $row['Libelle'];?></td>
							<td><?php echo $row['SousSurveillance'];?></td>
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