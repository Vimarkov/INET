<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModifM(Mode,Id)
		{window.open("Ajout_Categorie_Autorisation.php?Mode="+Mode+"&Id="+Id,"PageCategorieAutorisation","status=no,menubar=no,width=620,height=50");}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite)))
{
	$Droits="Administrateur";
}
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage"><?php 
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Competences/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des catégories d'autorisation";}else{echo "Competencies management # Authorization categories management";}?></td>
					<?php
					if($Droits=="Administrateur")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModifM('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un une catégorie d'autorisation">
						</a>
					</td>
					<?php
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" style="width:450px;">
						<?php
							$result=mysqli_query($bdd,"SELECT Id, Id_Moyen, Libelle, (SELECT Libelle FROM new_competences_moyen WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) AS Moyen FROM new_competences_moyen_categorie WHERE Suppr=0 ORDER BY Moyen ASC, Libelle ASC");
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Moyen";}else{echo "Mean";}?></td>
								<td colspan="3" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $row['Moyen'];?></td>
								<td><?php echo $row['Libelle'];?></td>
							<?php
								if($Droits=="Administrateur")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModifM('Modif','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModifM('Suppr','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
									</a>
								</td>
							<?php
								}
								elseif($Droits=="Ecriture")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModifM('Modif','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20"></td>
							<?php
								}
								else
								{
							?>
								<td colspan="2"></td>
							<?php
								}
							?>
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

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>