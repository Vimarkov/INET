<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Id_Poste,Id_Plateforme){
		var w=window.open("Ajout_Plateforme_Poste.php?Id_Poste="+Id_Poste+"&Id_Plateforme="+Id_Plateforme,"PageFichier","status=no,menubar=no,width=500,height=700,resizable=yes,scrollbars=yes");
		w.focus();
		}
	function OuvreFenetreExcel(Id_Plateforme)
		{window.open("Liste_Plateforme_Poste_Export.php?Id_Plateforme="+Id_Plateforme,"PageExcel","status=no,menubar=no,width=10,height=10");}
</script>
<form method="POST" action="Liste_Plateforme_Poste.php">
	<table style="width:100%; border-spacing:0; align:center;">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">Hiérarchie du personnel # Unité d'exploitation</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
		<td height="4"></td>
		</tr>
		<tr><td>
		<table style="width:98%; border-spacing:0; align:center;" class="GeneralInfo">
			<tr>
				<td width=20%>
					&nbsp; Unité d'exploitation :
					<select class="plateforme" name="plateforme" onchange="submit();">
					<?php
					$req = "SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ORDER BY Libelle;";
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$PlateformeSelect = 0;
					$Selected = "";
					if ($nbPlateforme > 0)
					{
						if (!empty($_POST['plateforme'])){
							if ($PlateformeSelect == 0){$PlateformeSelect = $_POST['plateforme'];}
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								if ($row[0] == $_POST['plateforme']){$Selected = "Selected";}
								echo "<option value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
								$Selected = "";
							}
						}
						else{
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								if ($PlateformeSelect == 0){$PlateformeSelect = $row['Id'];}
								echo "<option value='".$row['Id']."'>".$row['Libelle']."</option>";
							}
						}
					 }
					 
					 $Selected="";
					if ($PlateformeSelect == 0){$PlateformeSelect = $_POST['plateforme'];}
					if (-1 == $_POST['plateforme']){$Selected = "Selected";}
					 echo "<option value='-1' ".$Selected.">Autres</option>";
					 ?>
					</select>
				</td>
				<td>
					&nbsp;
					<a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('<?php echo $PlateformeSelect; ?>');">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
					</a>
					&nbsp;
				</td>
			</tr>
		</table>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				<table class="TableCompetences" style="align:center; width:60%;">
					<tr>
						<td class="EnTeteTableauCompetences" width="17%" >Poste</td>
						<td class="EnTeteTableauCompetences" width="10%" >Responsable</td>
						<td class="EnTeteTableauCompetences" width="70%" colspan="10">Backup</td>
						<td class="EnTeteTableauCompetences" width="3%"></td>
					</tr>
				<?php
					$Couleur="#EEEEEE";
					if($PlateformeSelect==17){$req="SELECT Id, Libelle FROM new_competences_poste WHERE Id IN (6,9,11,13,14,15,16,17,18,19,21,23,25,26,27,28,30,31,32,33,34,36,38,39,40,41,42,43,44,45,48,49,50,51,52,53,54,55,56,57,58,59,60,61,64) ORDER BY Libelle";}
					elseif($PlateformeSelect==-1){$req="SELECT Id, Libelle FROM new_competences_poste WHERE Id IN (62,63) ORDER BY Libelle";}
					else{$req="SELECT Id, Libelle FROM new_competences_poste WHERE Id IN (6,9,11,13,14,15,16,17,18,19,21,23,25,26,27,28,30,31,32,33,34,36,38,39,40,41,42,43,45,48,60,64) ORDER BY Libelle";}
					
					$resultPoste=mysqli_query($bdd,$req);
					$nbPoste=mysqli_num_rows($resultPoste);
					
					$requete="SELECT Id_Poste,Id_Personne,Backup, ";
					$requete.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_personne_poste_plateforme.Id_Personne) AS Personne ";
					$requete.="FROM new_competences_personne_poste_plateforme ";
					$requete.="WHERE Id_Plateforme =".$PlateformeSelect." ";
					$requete.="ORDER BY Backup,Personne ";
					$result=mysqli_query($bdd,$requete);
					$nbenreg=mysqli_num_rows($result);
					
					if($nbPoste>0){
						while($rowPoste=mysqli_fetch_array($resultPoste)){
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $rowPoste['Libelle']; ?></td>
				<?php
								$resp="";
								$backup="";
								if($nbenreg>0){
									mysqli_data_seek($result,0);
									while($row=mysqli_fetch_array($result)){
										if($row['Id_Poste']==$rowPoste['Id']){
											if($row['Backup']==0){
												$resp=$row['Personne'];
											}
											else{
												if($backup<>""){$backup.="<br>";}
												$backup.=$row['Personne'];
											}
										}
									}
								}
								
				?>
								<td width="10%"><?php echo $resp; ?></td>
								<td width="10%"><?php echo $backup; ?></td>
								<td width="3%">
					<?php			
								$ReqDroits= "
									SELECT
										Id
									FROM
										new_competences_personne_poste_plateforme
									WHERE
										Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF).")
										AND Id_Plateforme IN (".$PlateformeSelect.")";
								$ResultDroits=mysqli_query($bdd,$ReqDroits);
								$NbEnregDroits=mysqli_num_rows($ResultDroits);
								if($_SESSION['Id_Personne']==1351 || $_SESSION['Id_Personne']==406 || $_SESSION['Id_Personne']==14494 || ($rowPoste['Id']==21 && $NbEnregDroits>0)){
					?>
									<a class="Modif" href="javascript:OuvreFenetreModif(<?php echo $rowPoste['Id']; ?>,<?php echo $PlateformeSelect; ?>);">
										<img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier">
									</a>
								
					<?php
								}
					?>
								</td>
							</tr>
				<?php
						}
					}
				?>
				</table>
			</td>
		</tr>
	</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>