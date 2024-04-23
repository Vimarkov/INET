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
			var w= window.open("Ajout_TypeAbsence.php?Mode="+Mode+"&Id="+Id,"PageLieu","status=no,menubar=no,width=550,height=450");
			w.focus();
		}
	}
</script>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=6'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Congés / Absences # Type d'absence";}else{echo "Leave / Absence # Type of absence";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="left">
			<table class="TableCompetences" width="95%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Code planning";}else{echo "Schedule code";}?></td>
					<td class="EnTeteTableauCompetences" width="30%"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
					<td class="EnTeteTableauCompetences" width="30%"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé anglais";}else{echo "Wording english";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nbr jour autorisé";}else{echo "Number of days allowed";}?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Jours calendaires";}else{echo "Calendar days";}?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Disponible pour un salarié";}else{echo "Available for an employee";}?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Disponible pour un intérimaire";}else{echo "Available for an interim";}?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Nécessite un justificatif";}else{echo "Requires proof";}?></td>
					<td colspan="2" class="EnTeteTableauCompetences" style="text-align:right">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0">
						</a>
					</td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle, LibelleEN, CodePlanning, NbJourAutorise, Couleur, InformationSalarie, PosableHeure,NecessiteJustif,
					SpecifierHeurePrevu,HeuresDeductibles,DispoPourSalarie,Dispo, DispoPourInterimaire, Suppr, JourCalendaire
					FROM rh_typeabsence WHERE Suppr=0 
					ORDER BY CodePlanning ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						
						
						$couleurLigne="style='background-color:".$row['Couleur'].";'";
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td align="center" <?php echo $couleurLigne; ?>><?php echo stripslashes($row['CodePlanning']);?></td>
					<td><?php echo stripslashes($row['Libelle']);?></td>
					<td><?php echo stripslashes($row['LibelleEN']);?></td>
					<td><?php if($row['NbJourAutorise']>0){echo $row['NbJourAutorise'];} ?></td>
					<td><?php if($row['JourCalendaire']==1){if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";}}else{if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";}}?></td>
					<td><?php if($row['DispoPourSalarie']==1){if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";}}else{if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";}}?></td>
					<td><?php if($row['DispoPourInterimaire']==1){if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";}}else{if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";}}?></td>
					<td><?php if($row['NecessiteJustif']==1){if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";}}else{if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";}}?></td>
					<td>
						<?php if($row['Dispo']==1 || DroitsFormationPlateforme($TableauIdPostesRH)){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
						<?php } ?>
					</td>
					<td>
						<?php if($row['Dispo']==1){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
						</a>
						<?php } ?>
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