<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id,Type)
		{window.open("Ajout_Tiers.php?Mode="+Mode+"&Type="+Type+"&Id="+Id,"PageToolsAjoutTiers","status=no,menubar=no,width=480,height=275");}
</script>
<?php

if($LangueAffichage=="FR"){$TableauTiers=Array(1 => "Fabricants", 2 => "Fournisseurs", 3 => "Laboratoires");}
else{$TableauTiers=Array(1 => "Manufacturers", 2 => "Suppliers", 3 => "Laboratories");}
if($_GET){$Type=$_GET['Type'];}
else{$Type=$_POST['Type'];}
?>

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

					echo $TableauTiers[$Type];
					?>
					</td>
					<?php
						if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0','<?php echo $Type;?>');">
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
			<table width="100%">
				<tr>
					<td width="1%"></td>
					<td  width="99%">
						<table class="TableCompetences" width="90%">
						<?php
                            $Requete="
                                SELECT
                                    Id,
									Libelle,
									Adresse,
									Contact,
									TelFixe,
									TelMobile,
									Fax,
									Email
                                FROM
                                    tools_tiers
                                WHERE
									Type='".$Type."'
                                    AND Suppr=0
								ORDER BY
									Libelle";
                            $Result=mysqli_query($bdd,$Requete);
							$NbEnreg=mysqli_num_rows($Result);
							if($NbEnreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="EnTeteTableauCompetences" width="30%"><?php if($LangueAffichage=="FR"){echo "Adresse";}else{echo "Address";}?></td>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Contact";}else{echo "Contact";}?></td>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Tél. fixe";}else{echo "Tel.";}?></td>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Tél. portable";}else{echo "Mobile";}?></td>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Fax";}else{echo "Fax";}?></td>
								<td class="EnTeteTableauCompetences" colspan="3" ><?php if($LangueAffichage=="FR"){echo "Courriel";}else{echo "Email";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($Row['Libelle']);?></td>
								<td><?php echo stripslashes($Row['Adresse']);?></td>
								<td><?php echo stripslashes($Row['Contact']);?></td>
								<td><?php echo stripslashes($Row['TelFixe']);?></td>
								<td><?php echo stripslashes($Row['TelMobile']);?></td>
								<td><?php echo stripslashes($Row['Fax']);?></td>
								<td><?php echo stripslashes($Row['Email']);?></td>
							<?php
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $Row['Id']; ?>','<?php echo $Type; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $Row['Id']; ?>','<?php echo $Type; ?>');}">
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