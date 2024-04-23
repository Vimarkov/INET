<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModifM(Mode,Id)
		{window.open("Ajout_Categorie_Formation.php?Mode="+Mode+"&Id="+Id,"PageCategorieFormation","status=no,menubar=no,width=800,height=50");}
		
	function Up(Id){
		var tr=document.getElementById('tr_'+Id);
		tbody=document.getElementById('test');
		trs=tbody.getElementsByTagName('tr');
		count=trs.length;
		i=0;
		found=false;
		while(i<count && !found){
			if(trs[i]==tr){
				found=true;
			}else{
				i++;
			}
		}
		
		//Echanger les couleurs
		j=0;
		found=false;
		while(j<count && !found){
			if(j==(i-1)){
				var color=tr.style.backgroundColor;
				tr.style.backgroundColor=trs[j].style.backgroundColor;
				trs[j].style.backgroundColor=color;
				$.ajax({
					url : 'EchangerOrdre.php',
					data : 'Id1='+tr.id.substr(3)+'&Id2='+trs[j].id.substr(3),
				});
				found=true;
			}else{
				j++;
			}
		}
		
		tr2=tbody.insertRow(i-1);
		tbody.replaceChild(tr,tr2);
	}
	function Down(Id){
		var tr=document.getElementById('tr_'+Id);
		tbody=document.getElementById('test');
		trs=tbody.getElementsByTagName('tr');
		count=trs.length;
		i=0;
		found=false;
		while(i<count && !found){
			if(trs[i]==tr){
				found=true;
			}else{
				i++;
			}
		}
		//Echanger les couleurs
		j=0;
		found=false;
		while(j<count && !found){
			if(j==(i+1)){
				var color=tr.style.backgroundColor;
				tr.style.backgroundColor=trs[j].style.backgroundColor;
				trs[j].style.backgroundColor=color;
				$.ajax({
					url : 'EchangerOrdre.php',
					data : 'Id1='+tr.id.substr(3)+'&Id2='+trs[j].id.substr(3),
				});
				found=true;
			}else{
				j++;
			}
		}
		
		tr2=tbody.insertRow(i+2);
		tbody.replaceChild(tr,tr2);
	}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)))
{
	$Droits="Administrateur";
}
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage"><?php 
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Competences/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "Modules de formations # Catégorie";}else{echo "Training modules # Category";}?></td>
					<?php
					if($Droits=="Administrateur")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModifM('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un une catégorie d'autorisation">
						</a>
					</td>
					<?php
					}
					?>
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
						<table class="TableCompetences" style="width:600px;">
						<?php
							$result=mysqli_query($bdd,"SELECT Id, Libelle FROM moduleformation_categorie WHERE Suppr=0 ORDER BY Ordre ASC");
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<thead>
								<tr>
									<td colspan="4" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?></td>
								</tr>
							</thead>
							<tbody id="test">
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr id="tr_<?php echo $row['Id']; ?>">
							<?php
								if($Droits=="Administrateur")
								{
							?>
								<td width="3%" align="center" style="border-bottom:1px solid black;">
									<a href="javascript:Up(<?php echo $row['Id']; ?>)">
									<img id="Haut" src='../../Images/haut_Gris.png' width="13px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Up";}else{echo "Monter";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Up";}else{echo "Monter";} ?>'
									onmouseover="this.src='../../Images/haut.png'" onmouseout="this.src='../../Images/haut_Gris.png'">
									</a></br>
									<a href="javascript:Down(<?php echo $row['Id']; ?>)">
									<img id="Bas" src='../../Images/bas_Gris.png' width="13px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Down";}else{echo "Descendre";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Down";}else{echo "Descendre";} ?>'
									onmouseover="this.src='../../Images/bas.png'" onmouseout="this.src='../../Images/bas_Gris.png'">
									</a>
								</td>
							<?php
								}
								else
								{
							?>
								<td style="border-bottom:1px solid black;"></td>
							<?php
								}
							?>
								<td style="border-bottom:1px solid black;"><?php echo stripslashes($row['Libelle']);?></td>
							<?php
								if($Droits=="Administrateur")
								{
							?>
								<td width="20" style="border-bottom:1px solid black;">
									<a class="Modif" href="javascript:OuvreFenetreModifM('Modif','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20" style="border-bottom:1px solid black;">
									<a class="Modif" href="javascript:OuvreFenetreModifM('Suppr','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
									</a>
								</td>
							<?php
								}
								else
								{
							?>
								<td colspan="2" style="border-bottom:1px solid black;"></td>
							<?php
								}
							?>
							</tr>
						<?php
									}	//Fin boucle
								}		//Fin If
								mysqli_free_result($result);	// Libération des résultats
						?>
							</tbody>
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