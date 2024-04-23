<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function AjouterTaux(Type)
	{
		var w=window.open("Ajout_Taux"+Type+".php?Menu="+document.getElementById('Menu').value,"PageCout","status=no,menubar=no,width=1300,height=300,scrollbars=1");
		w.focus();
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

?>

<table style="width:100%; border-spacing:0; align:center;">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#fff927;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Paramétrage des coûts";}else{echo "Cost setting";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="100%" colspan="4" align="center">
			<table class="TableCompetences" width="50%">
				<tr>
					<td class="Libelle">
						<?php if($LangueAffichage=="FR"){echo "Ajouter un taux horaire moyen : ";}else{echo "Add an average hourly rate : ";}?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<input class="Bouton" type="button" id="btnGeneral" name="btnGeneral" value="<?php if($_SESSION["Langue"]=="FR"){echo "Général";}else{echo "General";} ?>" onClick="AjouterTaux('General')">
						&nbsp;&nbsp;&rarr;&nbsp;&nbsp;
						<input class="Bouton" type="button" id="btnPlateforme" name="btnPlateforme" value="<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?>" onClick="AjouterTaux('Plateforme')">
						&nbsp;&nbsp;&rarr;&nbsp;&nbsp;
						<input class="Bouton" type="button" id="btnPrestation" name="btnPrestation" value="<?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?>" onClick="AjouterTaux('Prestation')">
						&nbsp;&nbsp;&rarr;&nbsp;&nbsp;
						<input class="Bouton" type="button" id="btnTypeMetier" name="btnTypeMetier" value="<?php if($_SESSION["Langue"]=="FR"){echo "Type métier";}else{echo "Business type";} ?>" onClick="AjouterTaux('TypeMetier')">
						&nbsp;&nbsp;&rarr;&nbsp;&nbsp;
						<input class="Bouton" type="button" id="btnVacation" name="btnVacation" value="<?php if($_SESSION["Langue"]=="FR"){echo "Vacation";}else{echo "Session";} ?>" onClick="AjouterTaux('Vacation')">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center">
			<table class="TableCompetences" width="50%">
			<?php
				$req="SELECT Taux FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Plateforme=0 AND Id_Prestation=0 AND Id_TypeMetier=0 AND Id_Vacation=0 ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				
				$Couleur="#EEEEEE";
				if($nbenreg>0)
				{
						$row=mysqli_fetch_array($result);
				?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width="30%" class="Libelle">&rarr;<?php if($_SESSION["Langue"]=="FR"){echo "Taux Général";}else{echo "General rate";} ?></td>
							<td width="70%"><?php echo $row['Taux'];?></td>
						</tr>
			<?php
						
				}
				$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme,Taux FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Plateforme<>0 AND Id_Prestation=0 AND Id_TypeMetier=0 AND Id_Vacation=0 ORDER BY Plateforme ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="30%" class="Libelle" class='collapser'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;<?php echo $row['Plateforme'];?></td>
					<td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['Taux'];?></td>
				</tr>
			<?php
					
						$req="SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,Id_Prestation,Taux FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Plateforme=".$row['Id_Plateforme']." AND Id_Prestation<>0 AND Id_TypeMetier=0 AND Id_Vacation=0 ORDER BY Prestation ";
						$resultPresta=mysqli_query($bdd,$req);
						$nbenregPresta=mysqli_num_rows($resultPresta);
						if($nbenregPresta>0)
						{
							while($rowPresta=mysqli_fetch_array($resultPresta))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
					?>
						<tr bgcolor="<?php echo $Couleur;?>" class="Plateforme_<?php echo $row['Id_Plateforme'];?>">
							<td width="30%" class="Libelle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;<?php echo substr($rowPresta['Prestation'],0,7);?></td>
							<td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowPresta['Taux'];?></td>
						</tr>
					<?php
								$req="SELECT (SELECT Libelle FROM rh_groupemetier WHERE Id=Id_TypeMetier) AS TypeMetier,Id_TypeMetier,Taux FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Plateforme=".$row['Id_Plateforme']." AND Id_Prestation=".$rowPresta['Id_Prestation']." AND Id_TypeMetier<>0 AND Id_Vacation=0 ORDER BY TypeMetier ";
								$resultTypeMetier=mysqli_query($bdd,$req);
								$nbenregTypeMetier=mysqli_num_rows($resultTypeMetier);
								if($nbenregTypeMetier>0)
								{
									while($rowTypeMetier=mysqli_fetch_array($resultTypeMetier))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
							?>
								<tr bgcolor="<?php echo $Couleur;?>" class="Plateforme_<?php echo $row['Id_Plateforme'];?> Prestation_<?php echo $rowPresta['Id_Prestation'];?>">
									<td width="30%" class="Libelle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;<?php echo $rowTypeMetier['TypeMetier'];?></td>
									<td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowTypeMetier['Taux'];?></td>
								</tr>
							<?php
										$req="SELECT (SELECT Nom FROM rh_vacation WHERE Id=Id_Vacation) AS Vacation,Id_Vacation,Taux FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Plateforme=".$row['Id_Plateforme']." AND Id_Prestation=".$rowPresta['Id_Prestation']." AND Id_TypeMetier=".$rowTypeMetier['Id_TypeMetier']." AND Id_Vacation<>0 ORDER BY Vacation ";
										$resultVacation=mysqli_query($bdd,$req);
										$nbenregVacation=mysqli_num_rows($resultVacation);
										if($nbenregVacation>0)
										{
											while($rowVacation=mysqli_fetch_array($resultVacation))
											{
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
									?>
										<tr bgcolor="<?php echo $Couleur;?>" class="Plateforme_<?php echo $row['Id_Plateforme'];?> Prestation_<?php echo $rowPresta['Id_Prestation'];?> TypeMetier_<?php echo $rowTypeMetier['Id_TypeMetier'];?>">
											<td width="30%" class="Libelle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;<?php echo $rowVacation['Vacation'];?></td>
											<td width="70%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowVacation['Taux'];?></td>
										</tr>
									<?php
											
												
											}	
										}
										
									}	
								}
								
							}	
						}
					}	
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