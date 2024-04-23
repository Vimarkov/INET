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
			var w= window.open("Ajout_Client.php?Mode="+Mode+"&Id="+Id,"PageClient","status=no,menubar=no,width=420,height=250");
			w.focus();
		}
	}
</script>
<?php
if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Libelle")
	{
		$_SESSION['TriClient_General']= str_replace("Libelle ASC,","",$_SESSION['TriClient_General']);
		$_SESSION['TriClient_General']= str_replace("Libelle DESC,","",$_SESSION['TriClient_General']);
		$_SESSION['TriClient_General']= str_replace("Libelle ASC","",$_SESSION['TriClient_General']);
		$_SESSION['TriClient_General']= str_replace("Libelle DESC","",$_SESSION['TriClient_General']);
		if($_SESSION['TriClient_Libelle']==""){$_SESSION['TriClient_Libelle']="ASC";$_SESSION['TriClient_General'].= "Libelle ".$_SESSION['TriClient_Libelle'].",";}
		elseif($_SESSION['TriClient_Libelle']=="ASC"){$_SESSION['TriClient_Libelle']="DESC";$_SESSION['TriClient_General'].= "Libelle ".$_SESSION['TriClient_Libelle'].",";}
		else{$_SESSION['TriClient_Libelle']="";}
	}
}
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
						
					if($LangueAffichage=="FR"){echo "Gestion des clients";}else{echo "Customer management";}
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
								<td colspan="2" class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Client.php?Tri=Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?><?php if($_SESSION['TriClient_Libelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriClient_Libelle']=="ASC"){echo "&darr;";}?></a></td>
								<td align="right" width="10" class="EnTeteTableauCompetences">
									<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
										<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un client">
									</a>
								</td>
							</tr>
						<?php
							$req="SELECT Id, 
								Libelle, Suppr
								FROM form_client WHERE Suppr=0 ";
							if($_SESSION['TriClient_General']<>""){
								$req.="ORDER BY ".substr($_SESSION['TriClient_General'],0,-1);
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