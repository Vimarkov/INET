<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Secteur.php?Mode="+Mode+"&Id="+Id,"PageModifSecteur","status=no,menubar=no,width=300,height=10,resizable=yes,scrollbars=yes");}
	function OuvreFenetreModifPresta(Mode,Id)
		{window.open("Modif_SecteurPresta.php?Mode="+Mode+"&Id="+Id,"PageModifPresta","status=no,menubar=no,width=600,height=50,resizable=yes,scrollbars=yes");}
</script>
<?php
//Vérification des droits
$req = "SELECT new_competences_personne_poste_prestation.Id ";
$req .= "FROM new_competences_personne_poste_prestation WHERE ";
$req .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." AND (new_competences_personne_poste_prestation.Id_Poste>=1 AND new_competences_personne_poste_prestation.Id_Poste<=9);";
$resultDroit=mysqli_query($bdd,$req);
$nbDroit=mysqli_num_rows($resultDroit);
?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="3">
			<table class="GeneralPage" style="width:100%; border-spacing:0;background-color:#f3f414;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PERFOS/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "SQCDPF # Secteurs";}
							else{echo "SQCDPF # Activity area";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
		if($nbDroit>0)
		{
		?>
		<tr>
			<td height="4" colspan="2"/>
		</tr>
		<tr>
			<td width="70%" align="center">
			</td>
			<td width="30%" align="center">
				<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreModif('Ajout','0');">&nbsp;Ajouter un secteur&nbsp;</a>
			</td>
		</tr>
		<?php
			}
		?>
	<tr>
		<td height="4" colspan="2"/>
	</tr>
	<tr>
		<td width="70%" align="left" valign="top">
			<table class="TableCompetences" width="95%">
			<?php
				$req="SELECT new_competences_prestation.Id, new_competences_prestation.Libelle, ";
				$req.="(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) AS Plateforme, ";
				$req.="(SELECT new_secteur.Libelle FROM new_secteur WHERE new_secteur.Id=new_competences_prestation.Id_Secteur) AS Secteur ";
				$req.="FROM new_competences_prestation ";
				$req.="WHERE new_competences_prestation.Id_Plateforme=1 ";
				$req.="ORDER BY Plateforme ASC, new_competences_prestation.Libelle ASC";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				?>
				<tr>
					<td class="EnTeteTableauCompetences">Plateforme</td>
					<td class="EnTeteTableauCompetences">Prestation</td>
					<td colspan="2" class="EnTeteTableauCompetences">Secteur</td>
				</tr>
				<?php
				if($nbenreg>0)
				{		
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width=50><?php echo $row['Plateforme'];?></td>
							<td width=200><?php echo $row['Libelle'];?></td>
							<td width=150><?php echo $row['Secteur'];?></td>
						<?php
							if($nbDroit>0)
							{
						?>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModifPresta('Modif','<?php echo $row['Id']; ?>');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
							<?php
							}
							?>
						</tr>
				<?php
					}
				}
				mysqli_free_result($result);	// Libération des résultats
			?>
			</table>
		</td>
		<td width="30%" align="center" valign="top">
			<table class="TableCompetences" width="100%">
			<?php
				$req="SELECT new_secteur.Id, new_secteur.Libelle ";
				$req.=" FROM new_secteur";
				$req.=" WHERE Id_Plateforme=1 ";
				$req.=" ORDER BY new_secteur.Libelle ASC";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				?>
				<tr>
					<td colspan="3" class="EnTeteTableauCompetences">Secteur</td>
				</tr>
				<?php
				if($nbenreg>0)
				{		
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width=150><?php echo $row['Libelle'];?></td>
						<?php
							if($nbDroit>0)
							{
						?>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
							<td width="20">
								<input type="image" src="../../Images/Suppression.gif" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');}">
							</td>
							<?php
							}
							?>
						</tr>
				<?php
					}
				}
				mysqli_free_result($result);	// Libération des résultats
			?>
			</table>
		</td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>