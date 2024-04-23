<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_CaisseType.php?Mode="+Mode+"&Id="+Id,"PageToolsAjoutCaisseType","status=no,menubar=no,width=1000,height=500");}
	
	function Lister_Materiel()
	{
		Lister_Dependances('Table_CaissesTypes','Liste_ContenuCaisseType','Id_CaisseType_ModeleMateriel|Matériel|Qté','0|500|20','Affichage_Contenu_CaisseType','Contenu de la caisse type');
	}
</script>
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#67cff1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Caisses \"types\"";}else{echo "Boxes \"types\"";}
					?>
					</td>
					<?php
						if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter">
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
		<td width="100%">
			<table width="100%">
				<tr>
					<td width="2%"></td>
					<td width="40%" valign="top">
						<table class="TableCompetences" id="Table_CaissesTypes" width="100%">
						<?php
                            $Requete="
                                SELECT
                                    Id,
									Libelle,
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme
                                FROM
                                    tools_caissetype
                                WHERE
                                    Suppr=0
								AND 
									Id_Plateforme IN (
										SELECT DISTINCT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme 
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
									)
								ORDER BY
									Plateforme, Libelle";
                            $Result=mysqli_query($bdd,$Requete);
							$NbEnreg=mysqli_num_rows($Result);
							if($NbEnreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences" ><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="EnTeteTableauCompetences" colspan="3" ><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td valign="top" width="70%">
									<input onclick="Lister_Materiel();" type="radio" name="CaisseTypeSelect" value="<?php echo $Row['Id'];?>">
									<?php echo stripslashes($Row['Libelle']);?>
								</td>
								<td valign="top" width="26%">
									<?php echo stripslashes($Row['Plateforme']);?>
								</td>
							<?php
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
							?>
								<td width="2%">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $Row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="2%">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $Row['Id']; ?>');}">
								</td>
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
						mysqli_free_result($Result);	// Libération des résultats
						?>
						</table>
					</td>
					<td width="60%" valign="top" align="center">
						<div id="Affichage_Contenu_CaisseType">
						</div>
						<?php
						$RequeteContenuCaisseType="
							SELECT
								Id,
								Id_CaisseType,
								(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
								Quantite
							FROM
								tools_caissetype_contenu
							WHERE
								Suppr=0
							ORDER BY
								LIBELLE_MODELEMATERIEL";
						$ResultContenuCaisseType=mysqli_query($bdd,$RequeteContenuCaisseType);
						$Liste_ContenuCaisseType="";
						while($RowContenuCaisseType=mysqli_fetch_array($ResultContenuCaisseType))
						{
							$Liste_ContenuCaisseType.=$RowContenuCaisseType['Id']."|".$RowContenuCaisseType['Id_CaisseType']."|".stripslashes($RowContenuCaisseType['LIBELLE_MODELEMATERIEL'])."|".$RowContenuCaisseType['Quantite']."µ";
						}
						?>
						<input type="hidden" id="Liste_ContenuCaisseType" value="<?php echo str_replace("\"","",$Liste_ContenuCaisseType);?>">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

<script>Lister_Materiel();</script>

</body>
</html>