<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_ModuleFormation.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=1400,height=600");}
	function OuvrirFichier(Fic)
			{window.open("../../../Qualite/DQ/4/DQ413/Modules_de_formation/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
	$(document).ready(function()
	{
		$(".collapser").click(function()
		{
			var src = jQuery(this).find("img").attr('src');
			var endsrc = src.substring(src.length -6, src.length);
			if(endsrc == "us.gif")
			{
				jQuery(this).find("img").attr('src', "../../Images/Moins.gif");
				for( var i = 0; i < $(".modules").length; i++)
					if ($(".modules")[i].id == jQuery(this).find("img").attr('id'))
						$(".modules")[i].style.display='';
			}
			else
			{
				jQuery(this).find("img").attr('src', "../../Images/Plus.gif");
				for( var i = 0; i < $(".modules").length; i++)
					if ($(".modules")[i].id == jQuery(this).find("img").attr('id'))
						$(".modules")[i].style.display='none';
			}
		});
	});
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
					if($LangueAffichage=="FR"){echo "Modules de formations # Formations";}else{echo "Training modules # Training";}?></td>
					<?php
					if($Droits=="Administrateur")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une formation">
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
			<table class="TableCompetences" style="width:100%;">
			<?php
				$result=mysqli_query($bdd,"SELECT Id,Reference,Intitule,(SELECT Libelle FROM moduleformation_categorie WHERE Id=Id_Categorie) AS Categorie FROM moduleformation_formation WHERE Suppr=0 AND Id_Formation=0 ORDER BY Categorie, Reference");
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					
			?>
				<tr>
					<td class="EnTeteTableauCompetences" width="30%"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Référence";}else{echo "Reference";}?></td>
					<td class="EnTeteTableauCompetences" width="30%"><?php if($LangueAffichage=="FR"){echo "Intitulé";}else{echo "Title";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Indice";}else{echo "Indice";}?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
					<td class="EnTeteTableauCompetences" width="2%"></td>
					<td class="EnTeteTableauCompetences" width="2%"></td>
				</tr>
			<?php
				$Couleur="#EEEEEE";
				while($row=mysqli_fetch_array($result))
				{
					$reqDoc="SELECT Id, Id_Formation, Reference , Intitule, Indice, DateDocument,Lien,TypeDocument FROM moduleformation_formation WHERE Suppr=0 AND Id_Formation=".$row['Id']." ";
					$resultDoc=mysqli_query($bdd,$reqDoc);
					$nbDocument=mysqli_num_rows($resultDoc);
					
					if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
					else{$Couleur="#EEEEEE";}
					
					$btn="";
					if($nbDocument>0){
						$btn="<img id=\"".$row['Id']."\" src='../../Images/Plus.gif'>&nbsp;&nbsp;";
					}
					
					$couleurAlerte="";
					if($nbDocument>0){
						while($rowDoc=mysqli_fetch_array($resultDoc))
						{
							if($rowDoc['Lien']<>""){
								if(!file_exists ('../../../Qualite/DQ/4/DQ413/Modules_de_formation/'.$rowDoc['Lien'])){
									$couleurAlerte="bgcolor='#f53939' ";
								}
							}
							
						}
					}
				?>
					<tr bgcolor="<?php echo $Couleur;?>" >
						<td width="30%" class="collapser" style='border-bottom:1px solid black;'><?php echo $btn;echo stripslashes($row['Categorie']);?></td>
						<td width="10%" style='border-bottom:1px solid black;'><?php echo stripslashes($row['Reference']);?></td>
						<td width="30%" style='border-bottom:1px solid black;' <?php echo $couleurAlerte;?>><?php echo stripslashes($row['Intitule']);?></td>
						<td width="10%" style='border-bottom:1px solid black;'></td>
						<td width="15%" style='border-bottom:1px solid black;'></td>
					<?php
						if($Droits=="Administrateur")
						{
					?>
						<td width="2%" style='border-bottom:1px solid black;'>
							<input type="image" src="../../Images/Modif.gif" style="border:0;" alt="Modification" title="Modification" onclick="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
						</td>
						<td width="2%" style='border-bottom:1px solid black;'>
							<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');}">
						</td>
					<?php
						}
						else
						{
					?>
						<td colspan="2" width="10%" style='border-bottom:1px solid black;'></td>
					<?php
						}
					?>
					</tr>
				<?php
						if($nbDocument>0){
							$resultDoc=mysqli_query($bdd,$reqDoc);
							while($rowDoc=mysqli_fetch_array($resultDoc))
							{
								$couleurAlerte="";
								if($rowDoc['Lien']<>""){
									if(!file_exists ('../../../Qualite/DQ/4/DQ413/Modules_de_formation/'.$rowDoc['Lien'])){
										$couleurAlerte="bgcolor='#f53939' ";
									}
								}
									
								?>
								<tr bgcolor="<?php echo $Couleur;?>" class="modules" id="<?php echo $rowDoc['Id_Formation'] ?>" style="display:none;">
									<td width="30%"></td>
									<td width="10%" style='border-bottom:1px solid black;'><?php echo stripslashes($rowDoc['Reference']);?></td>
									<td width="30%" style='border-bottom:1px solid black;' <?php echo $couleurAlerte;?> >
									<?php 
										if($rowDoc['Lien']<>""){
											if($rowDoc['TypeDocument']=="Document" || ($rowDoc['TypeDocument']=="QCM" && $QCM)){
												echo  "<a href=\"javascript:OuvrirFichier('".$rowDoc['Lien']."');\" style='color:#2509bf;' >";
											}
										}
										echo stripslashes($rowDoc['Intitule']);
										if($rowDoc['Lien']<>""){
											if($rowDoc['TypeDocument']=="Document" || ($rowDoc['TypeDocument']=="QCM" && $QCM)){
												echo  "</a>";
											}
										}
									?>
									</td>
									<td width="10%" style='border-bottom:1px solid black;'><?php echo stripslashes($rowDoc['Indice']);?></td>
									<td width="15%" style='border-bottom:1px solid black;'><?php echo AfficheDateJJ_MM_AAAA($rowDoc['DateDocument']);?></td>
									<td colspan="2" width="10%"></td>
								</tr>
				<?php			
							}
						}
					}	
				}	
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