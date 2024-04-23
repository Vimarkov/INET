<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Id)
	{
		var w= window.open("Modif_Prestation.php?Id="+Id,"PagePrestation","status=no,menubar=no,width=550,height=200");
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
						
					if($LangueAffichage=="FR"){echo "Prestations";}else{echo "Sites";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="left">
			<table class="TableCompetences" width="60%">
				<tr>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Programme";}else{echo "Program";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Domaine";}else{echo "Domain";}?></td>
					<td class="EnTeteTableauCompetences" width="2%"></td>
				</tr>
			<?php
				$req="SELECT new_competences_prestation.Id, 
					LEFT(new_competences_prestation.Libelle,7) AS Prestation,
					new_competences_plateforme.Libelle AS Plateforme,
					new_competences_prestation.Programme,
					(SELECT Libelle FROM recrut_domaine WHERE Id=Id_DomaineRecrutement) AS Domaine
					FROM new_competences_prestation
					LEFT JOIN new_competences_plateforme 
					ON new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme
					WHERE new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27)
					AND new_competences_prestation.Active=0
					ORDER BY Plateforme,Prestation ";
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
					<td><?php echo stripslashes($row['Plateforme']);?></td>
					<td><?php echo stripslashes($row['Prestation']);?></td>
					<td><?php echo stripslashes($row['Programme']);?></td>
					<td><?php echo stripslashes($row['Domaine']);?></td>
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