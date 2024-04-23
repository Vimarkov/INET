<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
	{
		var Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
		{
			var w= window.open("Ajout_ProduitPerissable.php?Mode="+Mode+"&Id="+Id,"PageProduitPerissable","status=no,menubar=no,width=600,height=300");
			w.focus();
		}
	}
	function OuvreFenetreExcel()
		{window.open("Export_ProduitPerissable.php","Page","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
</script>
<?php
$DirFichier=$CheminProduitsPerissables;
?>

<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					if($LangueAffichage=="FR"){echo "Produits périssables";}else{echo "Perishable goods";}
					?>
					</td>
					<td align="right"><a href="javascript:OuvreFenetreExcel()">
					<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="left">
			<table class="TableCompetences" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Famille de produits";}else{echo "Product family";}?></td>
					<td class="EnTeteTableauCompetences" width="25%"><?php if($_SESSION["Langue"]=="FR"){echo "Référence";}else{echo "Reference";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Température mini <br>de stockage";}else{echo "Storage mini <br>Temperature";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Température maxi <br>de stockage";}else{echo "Storage maxi <br>Temperature";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Péremption après ouverture";}else{echo "Validity after open";}?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Fiche de données sécurité (FDS)";}else{echo "Safety Data Sheet <br>(FDS in french)";}?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Document";}else{echo "Safety Data <br>Sheet Document";}?></td>
					<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
							|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
						)
						{
					?>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Fiche technique des produits";}else{echo "Technical data sheet <br>(only for Quality – « Update version not followed »)";}?></td>
					<?php
						}
					?>
					<td colspan="2" class="EnTeteTableauCompetences" style="text-align:right">
					<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
							|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
						)
						{
					?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0">
						</a>
					<?php
						}
					?>
					</td>
				</tr>
			<?php
				$req="SELECT Id,AIMS,Reference,TemperatureMini,TemperatureMaxi,Peremption,FDS,FTP,Document 
					FROM produit_perrissable 
					WHERE Suppr=0 
					ORDER BY Reference ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo stripslashes($row['AIMS']);?></td>
					<td><?php echo stripslashes($row['Reference']);?></td>
					<td><?php echo stripslashes($row['TemperatureMini']);?></td>
					<td><?php echo stripslashes($row['TemperatureMaxi']);?></td>
					<td><?php echo stripslashes($row['Peremption']);?></td>
					<td><?php echo stripslashes($row['FDS']);?></td>
					<td>
						<?php
							if($row['Document']<>""){
								echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" target=\"_blank\">";
								echo "<img src='../../Images/image.png' border='0'>";
								echo "</a>";
							}
						?>
					</td>
					<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
							|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
						)
						{
					?>
					<td>
						<?php
							if($row['FTP']<>""){
								echo "<a class=\"Info\" href=\"".$DirFichier.$row['FTP']."\" target=\"_blank\">";
								echo "<img src='../../Images/image.png' border='0'>";
								echo "</a>";
							}
						?>
					</td>
					<?php
						}
					?>
					<td>
						<?php
							if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
								|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
							)
							{
						?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
						<?php
							}
						?>
					</td>
					<td>
						<?php
							if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
								|| DroitsFormation1Plateforme(1,array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteReferentQualiteSysteme,$IdPosteFormateur))
							)
							{
						?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
						</a>
						<?php
							}
						?>
					</td>
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

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>