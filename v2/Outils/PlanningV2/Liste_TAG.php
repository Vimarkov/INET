<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Menu,Niveau,Coeff,Echelon)
	{
		var w= window.open("Modif_TAG.php?Menu="+Menu+"&Niveau="+Niveau+"&Coeff="+Coeff+"&Echelon="+Echelon,"PageTAG","status=no,menubar=no,width=750,height=300");
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
						
					if($LangueAffichage=="FR"){echo "Contrats # TAG";}else{echo "Contracts # TAG";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="2%"></td>
		<td align="center">
			<table class="TableCompetences" width="75%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Niveau / Position";}else{echo "Level / Position";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Coefficient";}else{echo "Coefficient";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Echelon";}else{echo "Echelon";}?></td>
					<?php
					if($_SESSION['Langue']=="FR"){
						$req="SELECT Id, 
						Libelle
						FROM rh_classificationmetier
						WHERE Suppr=0
						ORDER BY Libelle ";
					}
					else{
						$req="SELECT Id, 
						LibelleEN AS Libelle
						FROM rh_classificationmetier
						WHERE Suppr=0
						ORDER BY LibelleEN ";
					}
					$result=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result);
					if($nbenreg>0)
					{
						while($row=mysqli_fetch_array($result))
						{
					?>
							<td class="EnTeteTableauCompetences" width="18%"><?php echo $row['Libelle']; ?></td>
					<?php
						}
					}
					
					?>
					<td class="EnTeteTableauCompetences" style="text-align:right">
					</td>
				</tr>
				<?php
				
					$req="SELECT Id_ClassificationMetier, Niveau, Echelon, Coeff,Salaire FROM rh_tag WHERE Suppr=0 ";
					$resultTAG=mysqli_query($bdd,$req);
					$nbTAG=mysqli_num_rows($resultTAG);
					
					$tabNiveau=array("I","I","I","II","II","II","III","III","III","IV","IV","IV","V","V","V","V","","","","","","","","II","II","II","II","II","II","II","IIIA","IIIB","IIIC");
					$tabCoeff=array("140","145","155","170","180","190","215","225","240","255","270","285","305","335","365","395","60","68","76","80","84","86","92","100","108","114","120","125","130","135","135","180","240");
					$tabEchelon=array("1","2","3","1","2","3","1","2","3","1","2","3","1","2","3","3","","","","","","","","","","","","","","","","","");
					
					$i=0;
					$Couleur="#EEEEEE";
					foreach($tabNiveau as $niveau){
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo $niveau; ?></td>
						<td><?php echo $tabCoeff[$i]; ?></td>
						<td><?php echo $tabEchelon[$i]; ?></td>
						
				<?php		if($nbenreg>0)
							{
								mysqli_data_seek($result,0);
								while($row=mysqli_fetch_array($result)){
									$TAG="";
									if($nbTAG>0){
										mysqli_data_seek($resultTAG,0);
										while($rowTAG=mysqli_fetch_array($resultTAG)){
											if($rowTAG['Id_ClassificationMetier']==$row['Id'] && $rowTAG['Niveau']==$niveau && $rowTAG['Echelon']==$tabEchelon[$i] && $rowTAG['Coeff']==$tabCoeff[$i]){
												if($rowTAG['Salaire']>0){$TAG=$rowTAG['Salaire'];}
											}
										}
									}
									echo "<td>".$TAG."</td>";
								}
							}
				?>
						<td>
							<a class="Modif" href="javascript:OuvreFenetreModif('6','<?php echo $niveau; ?>','<?php echo $tabCoeff[$i]; ?>','<?php echo $tabEchelon[$i]; ?>');">
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