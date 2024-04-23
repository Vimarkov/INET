<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Mode,Id_Prestation)
	{
		var w= window.open("Modifier_VacationPrestation.php?Mode="+Mode+"&Id_Prestation="+Id_Prestation,"PageFichier","status=no,menubar=no,scrollbars=1,width=800,height=600");
		w.focus();
	}
	function OuvreFenetreExcelVacationPrestation(Id_Plateformes)
		{window.open("VacationPrestation_Excel.php?Id_Plateformes="+Id_Plateformes,"PageExcel","status=no,menubar=no,width=300,height=300");}
</script>
<?php
function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	if($Selected==true){$tiret="border-bottom:4px solid #bbbabc;";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#656466;valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#656466;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#656466';\" onmouseout=\"this.style.color='#656466';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
$Menu=6;
?>

<form class="test" action="Liste_PrestationVacation.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#FFFFFF;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=6'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Prestations / Vacations";}else{echo "Sites / Vacations";}
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
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr bgcolor="#ffffff">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("VACATIONS EN COURS","Outils/PlanningV2/Liste_PrestationVacation.php?Menu=".$Menu."",true);}
						else{Titre1("VACATIONS IN PROGRESS","Outils/PlanningV2/Liste_PrestationVacation.php?Menu=".$Menu."",true);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_PrestationVacationHistorique.php?Menu=".$Menu."",false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_PrestationVacationHistorique.php?Menu=".$Menu."",false);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td width="25%" class="Libelle">
					&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} 
					
					$prestation=$_SESSION['FiltreRHPrestaVacation_Prestation'];
					if($_POST){$prestation=$_POST['prestation'];}
					$_SESSION['FiltreRHPrestaVacation_Prestation']=$prestation;
					
					?>
					<select id="prestation" name="prestation" onchange="submit()">&nbsp;&nbsp;
						<?php
							$req="SELECT DISTINCT Id AS Id_Prestation, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
							FROM new_competences_prestation
							WHERE Active=0
							AND Id NOT IN (
								SELECT Id_Prestation
								FROM new_competences_pole    
								WHERE new_competences_pole.Actif=0
							)
							UNION 
							
							SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
								new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
								FROM new_competences_pole
								INNER JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								AND Active=0
								AND Actif=0
								
							ORDER BY Libelle, LibellePole";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($prestation==$row['Id_Prestation']."_".$row['Id_Pole']){$selected="selected";}
									$pole="";
									if($row['Id_Pole']<>0){$pole= " - ".stripslashes($row['LibellePole']);}
									echo "<option value='".$row['Id_Prestation']."_".$row['Id_Pole']."' ".$selected.">".stripslashes($row['Libelle'].$pole)."</option>";
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table class="TableCompetences" width="100%">
			<?php
				$req="SELECT DISTINCT Id AS Id_Prestation, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
					FROM new_competences_prestation
					WHERE Active=0
					AND CONCAT(Id,'_',0)='".$prestation."'
					AND Id NOT IN (
						SELECT Id_Prestation
						FROM new_competences_pole    
					)
					UNION 
					
					SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
						new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
						FROM new_competences_pole
						INNER JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE Active=0
						AND Actif=0
						AND CONCAT(Id_Prestation,'_',new_competences_pole.Id)='".$prestation."'
					ORDER BY Libelle, LibellePole";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				
				if($nbenreg>0)
				{
			?>
				<tr>
					<td width="5%" class="EnTeteTableauCompetences" rowspan="2"></td>
					<?php
						$resultVac=mysqli_query($bdd,"SELECT Id, Nom FROM rh_vacation WHERE Suppr=0 ORDER BY Nom ASC");
						$nbenregVac=mysqli_num_rows($resultVac);
						
						if($_SESSION["Langue"]=="FR"){
							$joursem = array("L", "M", "M", "J", "V", "S","D");
						}
						else{
							$joursem = array("M", "T", "W", "T", "F", "S", "S");
						}
						for($i=0;$i<7;$i++){
							echo "<td class='EnTeteTableauCompetences' colspan='".$nbenregVac."'>".$joursem[$i]."</td>";
						}
					?>
				</tr>
				<tr>
				<?php
					for($i=0;$i<7;$i++){
						mysqli_data_seek($resultVac,0);
						while($rowVac=mysqli_fetch_array($resultVac)){
							echo "<td width='2%' class='EnTeteTableauCompetences' >".$rowVac['Nom']."</td>";
						}
					}
				?>
				</tr>
			<?php
				$Couleur="#EEEEEE";
				$Plateforme=0;
				$idPrestation=0;
					while($row=mysqli_fetch_array($result))
					{
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
					?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "J";}else{echo "D";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"NbHeureJ",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "EJ";}else{echo "DT";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"NbHeureEJ",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "EN";}else{echo "NT";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"NbHeureEN",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Pause";}else{echo "Break";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"NbHeurePause",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "FOR";}else{echo "TRA";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"NbHeureFOR",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"HeureDebut",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr bgcolor="<?php echo $Couleur;?>">
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="5%" class="EnTetePlanning"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";}?></td>
								<?php
									for($i=0;$i<7;$i++){
										mysqli_data_seek($resultVac,0);
										while($rowVac=mysqli_fetch_array($resultVac)){
											echo "<td width='2%' class='EnTetePlanning' >".VacationPrestation($row['Id_Prestation'],$row['Id_Pole'],$i+1,$rowVac['Id'],"HeureFin",date('Y-m-d'))."</td>";
										}
									}
								?>
							</tr>
				<?php
						} //fin boucle for
					}		//Fin boucle
					mysqli_free_result($result);	// Libération des résultats
			?>
			</table>
		</tr>
	</td>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>