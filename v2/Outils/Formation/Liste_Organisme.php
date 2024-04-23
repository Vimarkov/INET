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
			var w= window.open("Ajout_Organisme.php?Mode="+Mode+"&Id="+Id,"PageLieu","status=no,menubar=no,width=420,height=250");
			w.focus();
		}
	}
</script>
<?php
if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Plateforme"){
		$_SESSION['TriOrganisme_General']= str_replace("Plateforme ASC,","",$_SESSION['TriOrganisme_General']);
		$_SESSION['TriOrganisme_General']= str_replace("Plateforme DESC,","",$_SESSION['TriOrganisme_General']);
		$_SESSION['TriOrganisme_General']= str_replace("Plateforme ASC","",$_SESSION['TriOrganisme_General']);
		$_SESSION['TriOrganisme_General']= str_replace("Plateforme DESC","",$_SESSION['TriOrganisme_General']);
		if($_SESSION['TriOrganisme_Plateforme']==""){$_SESSION['TriOrganisme_Plateforme']="ASC";$_SESSION['TriOrganisme_General'].= "Plateforme ".$_SESSION['TriOrganisme_Plateforme'].",";}
		elseif($_SESSION['TriOrganisme_Plateforme']=="ASC"){$_SESSION['TriOrganisme_Plateforme']="DESC";$_SESSION['TriOrganisme_General'].= "Plateforme ".$_SESSION['TriOrganisme_Plateforme'].",";}
		else{$_SESSION['TriOrganisme_Plateforme']="";}
	}
	if($_GET['Tri']=="Libelle"){
		$_SESSION['TriOrganisme_General']= str_replace("Libelle ASC,","",$_SESSION['TriOrganisme_General']);
		$_SESSION['TriOrganisme_General']= str_replace("Libelle DESC,","",$_SESSION['TriOrganisme_General']);
		$_SESSION['TriOrganisme_General']= str_replace("Libelle ASC","",$_SESSION['TriOrganisme_General']);
		$_SESSION['TriOrganisme_General']= str_replace("Libelle DESC","",$_SESSION['TriOrganisme_General']);
		if($_SESSION['TriOrganisme_Libelle']==""){$_SESSION['TriOrganisme_Libelle']="ASC";$_SESSION['TriOrganisme_General'].= "Libelle ".$_SESSION['TriOrganisme_Libelle'].",";}
		elseif($_SESSION['TriOrganisme_Libelle']=="ASC"){$_SESSION['TriOrganisme_Libelle']="DESC";$_SESSION['TriOrganisme_General'].= "Libelle ".$_SESSION['TriOrganisme_Libelle'].",";}
		else{$_SESSION['TriOrganisme_Libelle']="";}
	}
}
?>

<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing : 0; align:center;">
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
						
					if($LangueAffichage=="FR"){echo "Gestion des organismes";}else{echo "Management of training organizations";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" style="width:450px;">
							<tr>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Organisme.php?Tri=Plateforme"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?><?php if($_SESSION['TriOrganisme_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOrganisme_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
								<td colspan="2" class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Organisme.php?Tri=Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?><?php if($_SESSION['TriOrganisme_Libelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOrganisme_Libelle']=="ASC"){echo "&darr;";}?></a></td>
								<td align="right" width="10" class="EnTeteTableauCompetences">
									<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
										<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un lieu">
									</a>
								</td>
							</tr>
						<?php
							$resultPlat=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme 
							WHERE Id_Poste 
							IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.") 
							AND Id_Personne=".$IdPersonneConnectee);
							$nbPlat=mysqli_num_rows($resultPlat);
							
							$req="SELECT Id, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme, Libelle, Suppr ";
							$req.="FROM form_organisme WHERE Suppr=0 ";
							if($nbPlat>0){
								$req.="AND ( ";
								while($rowPlat=mysqli_fetch_array($resultPlat)){
									$req.="Id_Plateforme=".$rowPlat['Id_Plateforme']." OR ";
								}
								$req=substr($req,0,-3);
								$req.=") ";
							}
							else{
								$req.="AND Id=0 ";
							}
							if($_SESSION['TriOrganisme_General']<>""){
								$req.="ORDER BY ".substr($_SESSION['TriOrganisme_General'],0,-1);
							}
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
								<td><?php echo $row['Plateforme'];?></td>
								<td><?php echo $row['Libelle'];?></td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
									</a>
								</td>
								<td width="20">
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
		</td>
	</tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>