<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Id)
	{
		var w= window.open("Modif_Metier.php?Id="+Id,"PageMetier","status=no,menubar=no,width=450,height=200");
		w.focus();
	}
</script>
<?php
$DirFichier=$CheminRecrutement;
?>


<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Recrutement/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Fiches métiers";}else{echo "Trade sheets";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="left">
			<table class="TableCompetences" width="40%">
				<tr>
					<td class="EnTeteTableauCompetences" width="60%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Profession";}?></td>
					<td class="EnTeteTableauCompetences" width="40%"><?php if($_SESSION["Langue"]=="FR"){echo "Fiche métier";}else{echo "Trade sheets";}?></td>
					<td class="EnTeteTableauCompetences"></td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle, 
					DocumentFiche
					FROM new_competences_metier
					ORDER BY Libelle ";
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
					<td><?php echo stripslashes($row['Libelle']);?></td>
					<td><?php 
						if($row['DocumentFiche']<>""){
							echo "<a class=\"Info\" href=\"".$DirFichier.$row['DocumentFiche']."\" target=\"_blank\">";
							echo "<img src='../../Images/image.png' border='0'>";
							echo "</a>";
						}
						?>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>);">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
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