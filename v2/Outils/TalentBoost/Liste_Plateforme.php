<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Id)
	{
		var w= window.open("Modif_Plateforme.php?Id="+Id,"PagePlateforme","status=no,menubar=no,width=450,height=200");
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
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/TalentBoost/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Unités d'exploitation (Documents)";}else{echo "Operating unit (Documents)";}
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
					<td class="EnTeteTableauCompetences" width="60%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
					<td class="EnTeteTableauCompetences" width="40%"><?php if($_SESSION["Langue"]=="FR"){echo "Document";}else{echo "Document";}?></td>
					<td class="EnTeteTableauCompetences"></td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle,
					Document
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
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
						if($row['Document']<>""){
							echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" target=\"_blank\">";
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