<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Id)
	{
		var w= window.open("Modif_PeriodiciteVM.php?Id="+Id,"PageLieu","status=no,menubar=no,width=450,height=200");
		w.focus();
	}
</script>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#cdcc8d;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=6'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Visite médicale # Périodicité";}else{echo "Medical visit # Periodicity";}
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
					<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Périodicité sans SMR<br>(en mois)";}else{echo "Frequency without SMR<br>(in months)";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Périodicité avec SMR<br>(en mois)";}else{echo "Frequency with SMR<br>(in months)";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:right" width="2%">
					</td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle, LibelleEN,Periodicite_VM,Periodicite_VM_AvecSMR,
					Suppr
					FROM new_competences_metier WHERE 
					Suppr=0 
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
					<td><?php if($_SESSION["Langue"]=="FR"){echo stripslashes($row['Libelle']);}else{echo stripslashes($row['LibelleEN']);} ?></td>
					<td><?php echo stripslashes($row['Periodicite_VM']);?></td>
					<td><?php echo stripslashes($row['Periodicite_VM_AvecSMR']);?></td>
					<td style="text-align:right">
						<a class="Modif" href="javascript:OuvreFenetreModif('<?php echo $row['Id']; ?>');">
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