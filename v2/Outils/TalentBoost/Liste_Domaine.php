<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Domaine.php?Mode="+Mode+"&Id="+Id,"PageToolsAjoutLieu","status=no,menubar=no,width=675,height=150");}
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
						
					if($LangueAffichage=="FR"){echo "Domaines";}else{echo "Domains";}
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
					<td class="EnTeteTableauCompetences" width="90%"><?php if($_SESSION["Langue"]=="FR"){echo "Domaine";}else{echo "Domain";}?></td>
					<td class="EnTeteTableauCompetences" colspan="2" align="right">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter">
						</a>
					</td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle
					FROM talentboost_domaine
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
					<td width="20">
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" border="0" alt="Modification">
						</a>
					</td>
					<td width="20">
						<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');}">
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