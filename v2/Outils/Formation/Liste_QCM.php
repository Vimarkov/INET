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
		if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
		{
			var w=window.open("Ajout_QCM.php?Mode="+Mode+"&Id="+Id,"PageQCM","status=no,menubar=no,width=900,height=250");
			w.focus();
		}
	}
	function OuvreExcel(Id_QCM,Id_QCM_Langue){
		var w=window.open("QCM_Excel.php?Id_QCM="+Id_QCM+"&Id_QCM_Langue="+Id_QCM_Langue,"PageQCM","status=no,menubar=no,width=50,height=50");
		w.focus();
	}
	function Excel(){
		var w=window.open("Export_QCM.php","PageQCM","status=no,menubar=no,width=50,height=50");
		w.focus();
	}
</script>
<?php
if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Code")
	{
		$_SESSION['TriQCM_General']= str_replace("Code ASC,","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Code DESC,","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Code ASC","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Code DESC","",$_SESSION['TriQCM_General']);
		if($_SESSION['TriQCM_Code']==""){$_SESSION['TriQCM_Code']="ASC";$_SESSION['TriQCM_General'].= "Code ".$_SESSION['TriQCM_Code'].",";}
		elseif($_SESSION['TriQCM_Code']=="ASC"){$_SESSION['TriQCM_Code']="DESC";$_SESSION['TriQCM_General'].= "Code ".$_SESSION['TriQCM_Code'].",";}
		else{$_SESSION['TriQCM_Code']="";}
	}
	if($_GET['Tri']=="Client")
	{
		$_SESSION['TriQCM_General']= str_replace("Client ASC,","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Client DESC,","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Client ASC","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Client DESC","",$_SESSION['TriQCM_General']);
		if($_SESSION['TriQCM_Client']==""){$_SESSION['TriQCM_Client']="ASC";$_SESSION['TriQCM_General'].= "Client ".$_SESSION['TriQCM_Client'].",";}
		elseif($_SESSION['TriQCM_Client']=="ASC"){$_SESSION['TriQCM_Client']="DESC";$_SESSION['TriQCM_General'].= "Client ".$_SESSION['TriQCM_Client'].",";}
		else{$_SESSION['TriQCM_Client']="";}
	}
	if($_GET['Tri']=="NbQuestion")
	{
		$_SESSION['TriQCM_General']= str_replace("Nb_Question ASC,","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Nb_Question DESC,","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Nb_Question ASC","",$_SESSION['TriQCM_General']);
		$_SESSION['TriQCM_General']= str_replace("Nb_Question DESC","",$_SESSION['TriQCM_General']);
		if($_SESSION['TriQCM_NbQuestion']==""){$_SESSION['TriQCM_NbQuestion']="ASC";$_SESSION['TriQCM_General'].= "Nb_Question ".$_SESSION['TriQCM_NbQuestion'].",";}
		elseif($_SESSION['TriQCM_NbQuestion']=="ASC"){$_SESSION['TriQCM_NbQuestion']="DESC";$_SESSION['TriQCM_General'].= "Nb_Question ".$_SESSION['TriQCM_NbQuestion'].",";}
		else{$_SESSION['TriQCM_NbQuestion']="";}
	}
}
?>

<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#67cff1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>
						<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>
						</a>
						&nbsp;&nbsp;&nbsp;";

					if($LangueAffichage=="FR"){echo "Gestion des QCM";}else{echo "MCQ management";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="5%" align="right" >
			&nbsp;<a style="text-decoration:none;" href="javascript:Excel();">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
			</a>&nbsp;
		</td>
	</tr>
	<tr>
		<td align="right" style="color:#404040;text-decoration:underline dotted #404040;">
			<b><?php if($LangueAffichage=="FR"){echo "QCM en brouillon";}else{echo "QCM in draft";}?></b>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_QCM.php?Tri=Code"><?php if($LangueAffichage=="FR"){echo "Code";}else{echo "Code";}?><?php if($_SESSION['TriQCM_Code']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQCM_Code']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style='color:#003cff;'><?php if($LangueAffichage=="FR"){echo "Annexe";}else{echo "Appendices";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_QCM.php?Tri=Client"><?php if($LangueAffichage=="FR"){echo "Client";}else{echo "Client";}?><?php if($_SESSION['TriQCM_Client']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQCM_Client']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_QCM.php?Tri=NbQuestion"><?php if($LangueAffichage=="FR"){echo "Nb questions";}else{echo "Number of questions";}?><?php if($_SESSION['TriQCM_NbQuestion']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQCM_NbQuestion']=="ASC"){echo "&darr;";}?></a></td>
					<?php
					$requete="SELECT Id, Code, Fichier ";
					$resultlangue=mysqli_query($bdd,"SELECT Id, Libelle FROM form_langue WHERE Suppr=0 ORDER BY Libelle");
					$nblangue=mysqli_num_rows($resultlangue);
					while($rowlangue=mysqli_fetch_array($resultlangue))
					{
						echo "<td class='EnTeteTableauCompetences' style='width:200;color:#003cff;'>".$rowlangue['Libelle']."</td>";
						$requete.=", (SELECT Libelle FROM form_qcm_langue WHERE Id_QCM=form_qcm.Id AND Suppr=0 AND Id_Langue=".$rowlangue['Id']." LIMIT 1)";
						$requete.=", (SELECT Id FROM form_qcm_langue WHERE Id_QCM=form_qcm.Id AND Suppr=0 AND Id_Langue=".$rowlangue['Id']." LIMIT 1)";
						$requete.=", (SELECT Brouillon FROM form_qcm_langue WHERE Id_QCM=form_qcm.Id AND Suppr=0 AND Id_Langue=".$rowlangue['Id']." LIMIT 1)";
					}
					$requete.=", Nb_Question ,
						(SELECT Libelle FROM form_client WHERE form_client.Id=form_qcm.Id_Client) AS Client
						FROM form_qcm WHERE Suppr=0 ";
					if($_SESSION['TriQCM_General']<>""){
						$requete.="ORDER BY ".substr($_SESSION['TriQCM_General'],0,-1);
					}
					$result=mysqli_query($bdd,$requete);
					$nbenreg=mysqli_num_rows($result);
					?>
					<td align="right" width="5%" colspan="2" class="EnTeteTableauCompetences">
						<?php if(DroitsFormationPlateforme(array($IdPosteFormateur,$IdPosteResponsableFormation,$IdPosteResponsableQualite))){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un QCM">
						</a>
						<?php } ?>
					</td>
				</tr>
				<?php
				if($nbenreg>0)
				{
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row['Code'];?></td>
					<td><?php 
						if($row['Fichier']<>""){
							echo "&nbsp;&nbsp;<a target=\"_blank\" href=\"Docs/QCM/".$row['Id']."/".$row['Fichier']."\">";
							echo "<img src='../../Images/Tableau.gif' style='border:0;' alt='QCM'>";
							echo "</a>";
						}
					?></td>
					<td><?php echo $row['Client'];?></td>
					<td><?php echo $row['Nb_Question'];?></td>
					<?php
						for($i=0;$i<($nblangue*3);$i=$i+3)
						{
							$brouillon="";
							if($row[$i+5]=="1"){$brouillon="style='color:#404040;text-decoration:underline dotted #404040;'";}
							echo "<td ".$brouillon.">".$row[$i+3];
							if($row[$i+3]<>""){
								echo "&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreExcel('".$row['Id']."','".$row[$i+4]."');\">";
								echo "<img src='../../Images/Tableau.gif' style='border:0;' alt='QCM'>";
								echo "</a>";
							}
							echo "</td>";
						}
					?>
					<td>
						<?php if(DroitsFormationPlateforme(array($IdPosteFormateur,$IdPosteResponsableFormation,$IdPosteResponsableQualite))){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
						<?php } ?>
					</td>
					<td>
						<?php if(DroitsFormationPlateforme(array($IdPosteFormateur,$IdPosteResponsableFormation,$IdPosteResponsableQualite))){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
						</a>
						<?php } ?>
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