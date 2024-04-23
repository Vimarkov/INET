<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreCompetences(Id)
		{window.open("Tableau_Competences.php?Type=Conglomeration&Id="+Id,"PageTableauCompetences","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreCompetencesExport(Id)
		{window.open("Tableau_Competences_Export.php?Type=Conglomeration&Id="+Id,"PageTableauCompetencesExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Conglomeration.php?Mode="+Mode+"&Id="+Id,"PageModifProjet","status=no,menubar=no,width=1200,height=800,resizable=yes,scrollbars=yes");}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
|| DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
)
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
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Getion des conglomeration";}else{echo "Competencies management # Conglomeration management";}?></td>
					<?php
					if($Droits=="Administrateur" || $Droits=="Ecriture")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une conglomeration">
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
							$Id_Plateformes=0;
							if(isset($_SESSION['Id_Plateformes'])){$Id_Plateformes=implode(",",$_SESSION['Id_Plateformes']);}
							
							$req="
                                SELECT
                                    Id,
                                    Libelle
                                FROM
                                    new_competences_conglomeration
                               WHERE
                                    Suppr=0
								AND (SELECT COUNT(new_competences_conglomeration_prestation.Id) 
									FROM new_competences_conglomeration_prestation 
									LEFT JOIN new_competences_prestation
									ON new_competences_conglomeration_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE new_competences_prestation.Id_Plateforme IN (".$Id_Plateformes.")
								AND Id_Conglomeration=new_competences_conglomeration.Id
								)
                                ORDER BY
                                    Libelle";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td colspan="5" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestations";}else{echo "Sites";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width=400><?php echo $row['Libelle'];?></td>
								<td width=400><?php 
								$req="SELECT Libelle 
								FROM new_competences_conglomeration_prestation 
								LEFT JOIN new_competences_prestation
								ON new_competences_conglomeration_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Conglomeration=".$row['Id']." 
								ORDER BY Libelle";
								$result2=mysqli_query($bdd,$req);
								$nbenreg2=mysqli_num_rows($result2);
								if($nbenreg2>0)
								{
									while($row2=mysqli_fetch_array($result2))
									{
										echo $row2['Libelle']."<br>";
									}
								}
								?></td>
								<td width="20">
								<?php
									if($Droits=="Administrateur" || $Droits=="Ecriture")
									{
								?>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
											<img src="../../Images/Modif.gif" border="0" alt="Modification">
										</a>
								<?php 
									}
								?>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row['0']; ?>');">
										<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row['0']; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
									</a>
								</td>
								<td width="20">
								<?php
									if($Droits=="Administrateur")
									{
								?>
										<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');}">
								<?php 
									}
								?>
								</td>
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