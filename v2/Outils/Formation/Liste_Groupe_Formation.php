<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
	{
		Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif"){
			var w=window.open("Ajout_Groupe_Formation.php?Mode="+Mode+"&Id="+Id,"PageGroupeFormation","status=no,menubar=no,width=1000,height=500");
			w.focus();
		}
	}
</script>
<?php
if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Plateforme"){
		$_SESSION['TriGroupeForm_General']= str_replace("Plateforme ASC,","",$_SESSION['TriGroupeForm_General']);
		$_SESSION['TriGroupeForm_General']= str_replace("Plateforme DESC,","",$_SESSION['TriGroupeForm_General']);
		$_SESSION['TriGroupeForm_General']= str_replace("Plateforme ASC","",$_SESSION['TriGroupeForm_General']);
		$_SESSION['TriGroupeForm_General']= str_replace("Plateforme DESC","",$_SESSION['TriGroupeForm_General']);
		if($_SESSION['TriGroupeForm_Plateforme']==""){$_SESSION['TriGroupeForm_Plateforme']="ASC";$_SESSION['TriGroupeForm_General'].= "Plateforme ".$_SESSION['TriGroupeForm_Plateforme'].",";}
		elseif($_SESSION['TriGroupeForm_Plateforme']=="ASC"){$_SESSION['TriGroupeForm_Plateforme']="DESC";$_SESSION['TriGroupeForm_General'].= "Plateforme ".$_SESSION['TriGroupeForm_Plateforme'].",";}
		else{$_SESSION['TriGroupeForm_Plateforme']="";}
	}
	if($_GET['Tri']=="Libelle"){
		$_SESSION['TriGroupeForm_General']= str_replace("Libelle ASC,","",$_SESSION['TriGroupeForm_General']);
		$_SESSION['TriGroupeForm_General']= str_replace("Libelle DESC,","",$_SESSION['TriGroupeForm_General']);
		$_SESSION['TriGroupeForm_General']= str_replace("Libelle ASC","",$_SESSION['TriGroupeForm_General']);
		$_SESSION['TriGroupeForm_General']= str_replace("Libelle DESC","",$_SESSION['TriGroupeForm_General']);
		if($_SESSION['TriGroupeForm_Libelle']==""){$_SESSION['TriGroupeForm_Libelle']="ASC";$_SESSION['TriGroupeForm_General'].= "Libelle ".$_SESSION['TriGroupeForm_Libelle'].",";}
		elseif($_SESSION['TriGroupeForm_Libelle']=="ASC"){$_SESSION['TriGroupeForm_Libelle']="DESC";$_SESSION['TriGroupeForm_General'].= "Libelle ".$_SESSION['TriGroupeForm_Libelle'].",";}
		else{$_SESSION['TriGroupeForm_Libelle']="";}
	}
}

$DroitPlateformeAF_RF=DroitsFormationPlateforme($TableauIdPostesAF_RF);
?>

<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#67cff1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des groupes de formation";}else{echo "Group training management";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table class="TableCompetences" style="width:98%;" id="Table_Formations">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Groupe_Formation.php?Tri=Plateforme"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?><?php if($_SESSION['TriGroupeForm_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriGroupeForm_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Groupe_Formation.php?Tri=Libelle"><?php if($LangueAffichage=="FR"){echo "Groupe de formation";}else{echo "Training group";}?><?php if($_SESSION['TriGroupeForm_Libelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriGroupeForm_Libelle']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="66%" style="color:#003cff"><?php if($LangueAffichage=="FR"){echo "Références des formations";}else{echo "References of the trainings";}?></td>
					<td align="right" width="2%" class="EnTeteTableauCompetences">
					<td align="right" width="2%" class="EnTeteTableauCompetences">
						<?php if($DroitPlateformeAF_RF>0){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/add.png" width="25px" border="0" alt="Ajouter un groupe de formation">
						</a>
						<?php } ?>
					</td>
				</tr>
				<?php
				$resultPlat=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Poste IN (".implode(",",$TableauIdPostesAF_RF).") AND Id_Personne=".$IdPersonneConnectee);
				$nbPlat=mysqli_num_rows($resultPlat);
				
				$reqGroupeFormation="SELECT Id,Id_Plateforme, Libelle, Id_Plateforme, ";
				$reqGroupeFormation.="(SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) AS Plateforme ";
				$reqGroupeFormation.="FROM form_groupe_formation WHERE form_groupe_formation.Suppr=0 ";
				if($nbPlat>0){
					$reqGroupeFormation.="AND ( ";
					while($rowPlat=mysqli_fetch_array($resultPlat)){
						$reqGroupeFormation.="form_groupe_formation.Id_Plateforme=".$rowPlat['Id_Plateforme']." OR ";
					}
					$reqGroupeFormation=substr($reqGroupeFormation,0,-3);
					$reqGroupeFormation.=") ";
				}
				else{
					$reqGroupeFormation.="AND form_groupe_formation.Id_Plateforme=0 ";
				}
				
				if($_SESSION['TriGroupeForm_General']<>""){
					$reqGroupeFormation.="ORDER BY ".substr($_SESSION['TriGroupeForm_General'],0,-1);
				}
				$resultGroupeFormation=mysqli_query($bdd,$reqGroupeFormation);
				$nbGroupeFormation=mysqli_num_rows($resultGroupeFormation);
				
				if($nbGroupeFormation>0)
				{
					$requeteInfos="SELECT Id,Id_Formation,Id_Langue,Libelle,LibelleRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ";
					$resultInfos=mysqli_query($bdd,$requeteInfos);
					$nbInfos=mysqli_num_rows($resultInfos);
					
					//PARAMETRE PLATEFORME
					$requeteParam="SELECT Id,Id_Formation,Id_Langue,Id_Plateforme FROM form_formation_plateforme_parametres ";
					$resultParam=mysqli_query($bdd,$requeteParam);
					$nbParam=mysqli_num_rows($resultParam);
					
					$Couleur="#EEEEEE";
					while($rowGroupeFormation=mysqli_fetch_array($resultGroupeFormation)){
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $rowGroupeFormation['Plateforme'];?></td>
					<td><?php echo nl2br($rowGroupeFormation['Libelle']);?></td>
					<td>
						<?php 
							$reqFormation="SELECT form_groupe_formation_formation.Id_Formation,form_groupe_formation_formation.Id_Groupe_Formation,form_organisme.Libelle AS Organisme,form_groupe_formation_formation.Recyclage ";
							$reqFormation.="FROM form_groupe_formation_formation LEFT JOIN form_formation_plateforme_parametres ON form_groupe_formation_formation.Id_formation = form_formation_plateforme_parametres.Id_formation AND form_formation_plateforme_parametres.Id_Plateforme = ".$rowGroupeFormation['Id_Plateforme']." LEFT JOIN form_organisme ON form_formation_plateforme_parametres.Id_organisme = form_organisme.Id ";
							$reqFormation.="WHERE form_groupe_formation_formation.Suppr=0 AND form_groupe_formation_formation.Id_Groupe_Formation=".$rowGroupeFormation['Id'].";";
							$formations="";
							$test="";
							$resultFormation=mysqli_query($bdd,$reqFormation);
							$nbFormation=mysqli_num_rows($resultFormation);
							if($nbFormation>0){
								while($rowFormation=mysqli_fetch_array($resultFormation)){
									$Id_Langue=0;
									$Organisme="";
									if($nbParam>0){
										mysqli_data_seek($resultParam,0);
										while($rowParam=mysqli_fetch_array($resultParam)){
											$test = $rowParam['Id_Formation'];
											if($rowParam['Id_Formation']==$rowFormation['Id_Formation'] && $rowParam['Id_Plateforme']==$rowGroupeFormation['Id_Plateforme']){
												$Id_Langue=$rowParam['Id_Langue'];
											}
										}
									}
									$Infos="";
									$InfosRecyclage="";
									if($nbInfos>0){
										mysqli_data_seek($resultInfos,0);
										while($rowInfo=mysqli_fetch_array($resultInfos)){
											if($rowInfo['Id_Formation']==$rowFormation['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue){
												if($rowFormation['Organisme']<>""){
													$Organisme=" (".stripslashes($rowFormation['Organisme']).")";
												}
												$Infos=stripslashes($rowInfo['Libelle']).$Organisme;
												$InfosRecyclage=stripslashes($rowInfo['LibelleRecyclage']).$Organisme;
											}
										}
									}
									if($rowFormation['Recyclage']==0){$formations.=$Infos."<br>";}
									else{$formations.=$InfosRecyclage."<br>";}
								}
								$formations=substr($formations,0,-4);
							}
							echo $formations;
						?>
					</td>
					<td>
						<?php 
							if($DroitPlateformeAF_RF>0){ 
						?>
							<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $rowGroupeFormation['Id']; ?>');">
								<img src="../../Images/Modif.gif" border="0" alt="Modification">
							</a>
						<?php } ?>
					</td>
					<td>
						<?php if($DroitPlateformeAF_RF>0){ ?>
							<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $rowGroupeFormation['Id']; ?>');">
								<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
							</a>
						<?php } ?>
					</td>
				</tr>
				<?php
					}	//Fin boucle
				}		//Fin If
				mysqli_free_result($resultGroupeFormation);	// Libération des résultats
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