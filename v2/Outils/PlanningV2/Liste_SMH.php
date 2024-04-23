<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Menu,Cotation)
	{
		var w= window.open("Modif_SMH.php?Menu="+Menu+"&Cotation="+Cotation,"PageTAG","status=no,menubar=no,width=750,height=300");
		w.focus();
	}
</script>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=6'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Contrats # Grille des salaires minima hiérarchiques";}else{echo "Contracts # Minimum wage scale";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="center">
			<table class="TableCompetences" width="35%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Cotation";}else{echo "Quotation";}?></td>
					<td class="EnTeteTableauCompetences" width="85%"><?php if($_SESSION["Langue"]=="FR"){echo "Salaire";}else{echo "Salary";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:right">
					</td>
				</tr>
				<?php
				
					$req="SELECT Cotation,Salaire FROM rh_smh WHERE Suppr=0 ";
					$resultTAG=mysqli_query($bdd,$req);
					$nbTAG=mysqli_num_rows($resultTAG);
					
					$arrayCotation= array("A1","A2","B3","B4","C5","C6","D7","D8","E9","E10","F11","F12","G13","G14","H15","H16","I17","I18");
					
					$i=0;
					$Couleur="#EEEEEE";
					foreach($arrayCotation as $cotation){
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
					<tr bgcolor="<?php echo $Couleur;?>">
				<?php		
						$salaire="";
						$req="SELECT Salaire 
							FROM rh_smh 
							WHERE Suppr=0 
							AND Cotation='".$cotation."' ";
						$resultTAG=mysqli_query($bdd,$req);
						$nbenregTAG=mysqli_num_rows($resultTAG);
						if($nbenregTAG>0)
						{
							$rowTAG=mysqli_fetch_array($resultTAG);
							if($rowTAG['Salaire']>0){$salaire=$rowTAG['Salaire'];}
						}
						echo "<td>".$cotation."</td>";
						echo "<td>".$salaire."</td>";
				?>
						<td>
							<a class="Modif" href="javascript:OuvreFenetreModif('6','<?php echo $cotation; ?>');">
								<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
							</a>
						</td>
					</tr>
				<?php
						$i++;
					}
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