<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id){
		Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif"){
			var w=window.open("Ajout_FormationEquivalente.php?Mode="+Mode+"&Id="+Id,"PageGroupeFormation","status=no,menubar=no,width=1000,height=500");
			w.focus();
		}
	}
</script>
<?php
if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Plateforme"){
		$_SESSION['TriFormEquivalente_General']= str_replace("Plateforme ASC,","",$_SESSION['TriFormEquivalente_General']);
		$_SESSION['TriFormEquivalente_General']= str_replace("Plateforme DESC,","",$_SESSION['TriFormEquivalente_General']);
		$_SESSION['TriFormEquivalente_General']= str_replace("Plateforme ASC","",$_SESSION['TriFormEquivalente_General']);
		$_SESSION['TriFormEquivalente_General']= str_replace("Plateforme DESC","",$_SESSION['TriFormEquivalente_General']);
		if($_SESSION['TriFormEquivalente_Plateforme']==""){$_SESSION['TriFormEquivalente_Plateforme']="ASC";$_SESSION['TriFormEquivalente_General'].= "Plateforme ".$_SESSION['TriFormEquivalente_Plateforme'].",";}
		elseif($_SESSION['TriFormEquivalente_Plateforme']=="ASC"){$_SESSION['TriFormEquivalente_Plateforme']="DESC";$_SESSION['TriFormEquivalente_General'].= "Plateforme ".$_SESSION['TriFormEquivalente_Plateforme'].",";}
		else{$_SESSION['TriFormEquivalente_Plateforme']="";}
	}
	if($_GET['Tri']=="Libelle"){
		$_SESSION['TriFormEquivalente_General']= str_replace("Libelle ASC,","",$_SESSION['TriFormEquivalente_General']);
		$_SESSION['TriFormEquivalente_General']= str_replace("Libelle DESC,","",$_SESSION['TriFormEquivalente_General']);
		$_SESSION['TriFormEquivalente_General']= str_replace("Libelle ASC","",$_SESSION['TriFormEquivalente_General']);
		$_SESSION['TriFormEquivalente_General']= str_replace("Libelle DESC","",$_SESSION['TriFormEquivalente_General']);
		if($_SESSION['TriFormEquivalente_Libelle']==""){$_SESSION['TriFormEquivalente_Libelle']="ASC";$_SESSION['TriFormEquivalente_General'].= "Libelle ".$_SESSION['TriFormEquivalente_Libelle'].",";}
		elseif($_SESSION['TriFormEquivalente_Libelle']=="ASC"){$_SESSION['TriFormEquivalente_Libelle']="DESC";$_SESSION['TriFormEquivalente_General'].= "Libelle ".$_SESSION['TriFormEquivalente_Libelle'].",";}
		else{$_SESSION['TriFormEquivalente_Libelle']="";}
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
						
					if($LangueAffichage=="FR"){echo "Formations équivalentes";}else{echo "Equivalent training";}
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
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_FormationEquivalente.php?Tri=Plateforme"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?><?php if($_SESSION['TriFormEquivalente_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormEquivalente_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_FormationEquivalente.php?Tri=Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?><?php if($_SESSION['TriFormEquivalente_Libelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormEquivalente_Libelle']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="66%" style="color:#003cff"><?php if($LangueAffichage=="FR"){echo "Formations";}else{echo "Trainings";}?></td>
					<td align="right" width="2%" class="EnTeteTableauCompetences">
					<td align="right" width="2%" class="EnTeteTableauCompetences">
						<?php if($DroitPlateformeAF_RF>0){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un groupe de formations équivalentes">
						</a>
						<?php } ?>
					</td>
				</tr>
				<?php
				$resultPlat=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme 
					WHERE Id_Poste 
						IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.") 
					AND Id_Personne=".$IdPersonneConnectee);
				$nbPlat=mysqli_num_rows($resultPlat);
				
				$reqFormationEquivalente="SELECT Id,Id_Plateforme, Libelle, ";
				$reqFormationEquivalente.="(SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) AS Plateforme ";
				$reqFormationEquivalente.="FROM form_formationequivalente WHERE form_formationequivalente.Suppr=0 ";
				if($nbPlat>0){
					$reqFormationEquivalente.="AND ( ";
					while($rowPlat=mysqli_fetch_array($resultPlat)){
						$reqFormationEquivalente.="form_formationequivalente.Id_Plateforme=".$rowPlat['Id_Plateforme']." OR ";
					}
					$reqFormationEquivalente=substr($reqFormationEquivalente,0,-3);
					$reqFormationEquivalente.=") ";
				}
				else{
					$reqFormationEquivalente.="AND form_formationequivalente.Id_Plateforme=0 ";
				}
				if($_SESSION['TriFormEquivalente_General']<>""){
					$reqFormationEquivalente.="ORDER BY ".substr($_SESSION['TriFormEquivalente_General'],0,-1);
				}
				$resultFormationEquivalente=mysqli_query($bdd,$reqFormationEquivalente);
				$nbFormationEquivalente=mysqli_num_rows($resultFormationEquivalente);
				
				if($nbFormationEquivalente>0)
				{
					$requeteInfos="SELECT Id,Id_Formation,Id_Langue,Libelle,LibelleRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ";
					$resultInfos=mysqli_query($bdd,$requeteInfos);
					$nbInfos=mysqli_num_rows($resultInfos);
					
					//PARAMETRE PLATEFORME
					$requeteParam="SELECT Id,Id_Formation,Id_Langue,Id_Plateforme, (SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme FROM form_formation_plateforme_parametres ";
					$resultParam=mysqli_query($bdd,$requeteParam);
					$nbParam=mysqli_num_rows($resultParam);
					
					$Couleur="#EEEEEE";
					while($rowFormationEquivalente=mysqli_fetch_array($resultFormationEquivalente)){
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $rowFormationEquivalente['Plateforme'];?></td>
					<td><?php echo $rowFormationEquivalente['Libelle'];?></td>
					<td>
						<?php 
							$reqFormation="SELECT Id_Formation,Id_FormationEquivalente, Recyclage ";
							$reqFormation.="FROM form_formationequivalente_formationplateforme WHERE Id_FormationEquivalente=".$rowFormationEquivalente['Id']." ";
							$formations="";
							$resultFormation=mysqli_query($bdd,$reqFormation);
							$nbFormation=mysqli_num_rows($resultFormation);
							if($nbFormation>0){
								while($rowFormation=mysqli_fetch_array($resultFormation)){
									$Id_Langue=0;
									$Organisme="";
									if($nbParam>0){
										mysqli_data_seek($resultParam,0);
										while($rowParam=mysqli_fetch_array($resultParam)){
											if($rowParam['Id_Formation']==$rowFormation['Id_Formation'] && $rowParam['Id_Plateforme']==$rowFormationEquivalente['Id_Plateforme']){
												$Id_Langue=$rowParam['Id_Langue'];
												if($rowParam['Organisme']<>""){
													$Organisme=" (".stripslashes($rowParam['Organisme']).")";
												}
											}
										}
									}
									$Infos="";
									$InfosRecyclage="";
									if($nbInfos>0){
										mysqli_data_seek($resultInfos,0);
										while($rowInfo=mysqli_fetch_array($resultInfos)){
											if($rowInfo['Id_Formation']==$rowFormation['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue){
												$Infos=stripslashes($rowInfo['Libelle']).$Organisme."";
												$InfosRecyclage=stripslashes($rowInfo['LibelleRecyclage']).$Organisme."";
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
							<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $rowFormationEquivalente['Id']; ?>');">
								<img src="../../Images/Modif.gif" border="0" alt="Modification">
							</a>
						<?php } ?>
					</td>
					<td>
						<?php if($DroitPlateformeAF_RF>0){ ?>
							<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $rowFormationEquivalente['Id']; ?>');">
								<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
							</a>
						<?php } ?>
					</td>
				</tr>
				<?php
					}	//Fin boucle
				}		//Fin If
				mysqli_free_result($resultFormationEquivalente);	// Libération des résultats
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="20px"></td>
	</tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>