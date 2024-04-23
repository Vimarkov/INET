<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Projet.php?Mode="+Mode+"&Id="+Id,"PageModifProjet","status=no,menubar=no,width=300,height=10,resizable=yes,scrollbars=yes");}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
|| DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)))
{
	$Droits="Ecriture";
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
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Getion des projets";}else{echo "Competencies management # Projects management";}?></td>
					<?php
					if($Droits=="Administrateur" || $Droits=="Ecriture")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un projet">
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
						<table class="TableCompetences" style="width:750px;">
						<?php
                            $Plateformes=0;
                            if(isset($_SESSION['Id_Plateformes'])){$Plateformes=implode(",",$_SESSION['Id_Plateformes']);}
							$req="
                                SELECT
                                    new_competences_projet.Id,
                                    new_competences_projet.Libelle,
                                    new_competences_plateforme.Libelle
                                FROM
                                    new_competences_projet
                                    LEFT JOIN new_competences_plateforme ON new_competences_projet.Id_Plateforme=new_competences_plateforme.Id
                                WHERE
                                    new_competences_projet.Id_Plateforme IN (".$Plateformes.")
                                ORDER BY
                                    new_competences_plateforme.Libelle ASC,
                                    new_competences_projet.Libelle ASC";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td colspan="3" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width=150><?php echo $row[2];?></td>
								<td width=450><?php echo $row[1];?></td>
							<?php
								if($Droits=="Administrateur")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['0']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['0']; ?>');}">
								</td>
							<?php
								}
								elseif($Droits=="Ecriture")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['0']; ?>');">
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