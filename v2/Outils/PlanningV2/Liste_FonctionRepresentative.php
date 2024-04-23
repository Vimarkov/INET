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
			var w= window.open("Ajout_FonctionRepresentative.php?Mode="+Mode+"&Id="+Id,"PageLieu","status=no,menubar=no,width=450,height=200");
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
						
					if($LangueAffichage=="FR"){echo "Congés / Absences # Fonction représentative";}else{echo "Leave / Absence # Representative function";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="left">
			<table class="TableCompetences" width="30%">
				<tr>
					<td class="EnTeteTableauCompetences" width="75%"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
					<td colspan="2" class="EnTeteTableauCompetences" style="text-align:right">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0">
						</a>
					</td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle, Suppr
					FROM rh_fonctionrepresentative
					WHERE Suppr=0 
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