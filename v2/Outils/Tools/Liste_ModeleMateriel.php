<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{var w=window.open("Ajout_ModeleMateriel.php?Mode="+Mode+"&Id="+Id,"PageToolsAjoutModeleMateriel","status=no,menubar=no,width=675,height=250");
		w.focus();
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
						
					if($LangueAffichage=="FR"){echo "Modèles de matériel";}else{echo "Equipment models";}
					?>
					</td>
					<?php
						if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
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
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" style="width:950px;">
						<?php
                            $Requete="
                                SELECT
                                    tools_modelemateriel.Id,
									tools_typemateriel.Libelle AS LIBELLE_TYPEMATERIEL,
									tools_famillemateriel.Libelle AS LIBELLE_FAMILLEMATERIEL,
                                    tools_modelemateriel.Libelle AS LIBELLE,
									tools_modelemateriel.Reglable,
									tools_modelemateriel.Connectiques,
									tools_modelemateriel.Id_FamilleMateriel
                                FROM
                                    tools_modelemateriel
								LEFT JOIN tools_famillemateriel
									ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
								LEFT JOIN tools_typemateriel
									ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
                                WHERE
									tools_modelemateriel.Suppr=0
								ORDER BY
									LIBELLE_TYPEMATERIEL,
									LIBELLE_FAMILLEMATERIEL,
									LIBELLE";
                            $Result=mysqli_query($bdd,$Requete);
							$NbEnreg=mysqli_num_rows($Result);
							if($NbEnreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type de materiel";}else{echo "King of material";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Material family";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Réglable (écran)";}else{echo "Adjustable (screen)";}?></td>
								<td class="EnTeteTableauCompetences" width="30%" colspan="3"><?php if($LangueAffichage=="FR"){echo "Connectiques (écran)";}else{echo "Connectors (screen)";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$Reglable="";
								if($Row['Id_FamilleMateriel']==165){
									if($Row['Reglable']==0){$Reglable="Non";}
									else{$Reglable="Oui";}
								}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($Row['LIBELLE_TYPEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_FAMILLEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE']);?></td>
								<td><?php echo stripslashes($Reglable);?></td>
								<td><?php echo stripslashes($Row['Connectiques']);?></td>
							<?php
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $Row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
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