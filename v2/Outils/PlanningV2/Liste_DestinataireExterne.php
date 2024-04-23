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
			var w= window.open("Ajout_DestinataireExterne.php?Mode="+Mode+"&Id="+Id+"&Menu=3","PageLieu","status=no,menubar=no,width=650,height=300");
			w.focus();
		}
	}
</script>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="10px"></td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="left">
			<table class="TableCompetences" width="80%">
				<tr>
					<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";}?></td>
					<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";}?></td>
					<td class="EnTeteTableauCompetences" width="50%"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse destinataire externe";}else{echo "External recipient address";}?></td>
					<td colspan="2" class="EnTeteTableauCompetences" style="text-align:right">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0">
						</a>
					</td>
				</tr>
			<?php
				$req="SELECT Id, 
					(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
					AdresseExterne
					FROM rh_at_destinataireexterne 
					WHERE Suppr=0 
					ORDER BY Prestation, Pole, AdresseExterne ";
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
					<td><?php echo stripslashes($row['Prestation']);?></td>
					<td><?php echo stripslashes($row['Pole']);?></td>
					<td><?php echo stripslashes($row['AdresseExterne']);?></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
						</a>
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