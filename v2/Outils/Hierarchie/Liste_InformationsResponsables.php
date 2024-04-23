<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Id_Personne){
		var w=window.open("Ajout_InformationsResponsables.php?Mode=A&Id_Personne="+Id_Personne,"PageFichier","status=no,menubar=no,width=500,height=400,resizable=yes,scrollbars=yes");
		w.focus();
		}
	function OuvreFenetreSuppr(Id_Personne){
		var w=window.open("Ajout_InformationsResponsables.php?Mode=S&Id_Personne="+Id_Personne,"PageFichier","status=no,menubar=no,width=500,height=400,resizable=yes,scrollbars=yes");
		w.focus();
		}
</script>
<form method="POST" action="Liste_Plateforme_Poste.php">
	<table style="width:100%; border-spacing:0; align:center;">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">Hiérarchie du personnel # Informations Responsables</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
		<td height="4"></td>
		</tr>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				<table class="TableCompetences" style="align:center; width:55%;">
					<tr>
						<td class="EnTeteTableauCompetences" width="20%">Responsables / Backup</td>
						<td class="EnTeteTableauCompetences" width="10%">Email pro</td>
						<td class="EnTeteTableauCompetences" width="15%">UER</td>
						<td class="EnTeteTableauCompetences" width="3%"></td>
						<td class="EnTeteTableauCompetences" width="15%"></td>
					</tr>
				<?php
					$Couleur="#EEEEEE";
					$requete="SELECT DISTINCT new_competences_personne_poste_plateforme.Id_Personne, 
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne, 
							(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_competences_personne_plateforme.Id_Personne=new_competences_personne_poste_plateforme.Id_Personne LIMIT 1) AS UER,
								(SELECT COUNT(Id_Plateforme) 
								FROM new_competences_personne_plateforme 
								WHERE new_competences_personne_plateforme.Id_Personne=new_competences_personne_poste_plateforme.Id_Personne 
								AND new_competences_personne_plateforme.Id_Plateforme=14) AS Sortie
							FROM new_competences_personne_poste_plateforme
							UNION 
							SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne, 
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne, 
							(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_competences_personne_plateforme.Id_Personne=new_competences_personne_poste_prestation.Id_Personne LIMIT 1) AS UER,
								(SELECT COUNT(Id_Plateforme) 
								FROM new_competences_personne_plateforme 
								WHERE new_competences_personne_plateforme.Id_Personne=new_competences_personne_poste_prestation.Id_Personne 
								AND new_competences_personne_plateforme.Id_Plateforme=14) AS Sortie
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne<>0
							ORDER BY EmailPro, Personne";
					$result=mysqli_query($bdd,$requete);
					$nbenreg=mysqli_num_rows($result);
					
					if($nbenreg>0){
						while($rowPersonne=mysqli_fetch_array($result)){
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $rowPersonne['Personne']; ?></td>
								<td><?php echo $rowPersonne['EmailPro']; ?></td>
								<td><?php echo $rowPersonne['UER']; ?></td>
								<td width="3%">
									<a class="Modif" href="javascript:OuvreFenetreModif(<?php echo $rowPersonne['Id_Personne']; ?>);">
										<img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier">
									</a>
								</td>
								<td width="3%">
									<?php if($rowPersonne['Sortie']>0){?>
									<a class="Modif Bouton" href="javascript:OuvreFenetreSuppr(<?php echo $rowPersonne['Id_Personne']; ?>);">
										Enlever de la hiérarchie
									</a>
									<?php } ?>
								</td>
							</tr>
				<?php
						}
					}
				?>
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