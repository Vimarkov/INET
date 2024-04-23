<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id,Id_Categorie_Maitre){
		var w=window.open("Ajout_Qualification.php?Mode="+Mode+"&Id="+Id+"&Id_Categorie_Maitre="+Id_Categorie_Maitre,"PageFichier","status=no,menubar=no,scrollbars=yes,width=600,height=500");
		w.focus();
	}
	function OuvreFenetreCopieLettre(){
		var w=window.open("Copier_LettresQualification.php","PageLettre","status=no,menubar=no,scrollbars=yes,width=1000,height=700");
		w.focus();
	}
	function Excel(Type){
		if(Type=="General"){
			var w=window.open("Excel_Qualification.php","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
			w.focus();
		}
		else if(Type=="Moyens"){
			var w=window.open("Excel_Qualification_Moyens.php","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
			w.focus();
		}
		else if(Type=="Lettres"){
			var w=window.open("Excel_Qualification_Lettres.php","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
			w.focus();
		}
		else if(Type=="Fiches"){
			var w=window.open("Excel_Qualification_Fiches.php","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
			w.focus();
		}
	}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteReferentQualiteSysteme)))
{
	$Droits="Ecriture";
}
?>

<form class="test" method="POST" action="Liste_Qualification.php">
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
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des qualifications";}else{echo "Competencies management # Qualification management";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table class="TableCompetences" style="width:100%; border-spacing:0;">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Mots clés";}else{echo "Keywords";}?> : </td>
					<td width="20%">
						<input name="motcles" id="motcles" value="<?php $motcle=""; if(isset($_POST['motcles'])){echo $_POST['motcles'];$motcle=$_POST['motcles'];}elseif(isset($_GET['motcles'])){echo $_GET['motcles'];$motcle=$_GET['motcles'];} ?>"/>
					</td>
					<td width="20%" align="center">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">&nbsp;&nbsp;&nbsp;
					</td>
					<td width="40%" align="right">
						<a style='text-decoration:none;' class='Bouton' href='javascript:Excel("General")'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Excel - Qualifications";}else{echo "Excel - Qualifications";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
						<a style='text-decoration:none;' class='Bouton' href='javascript:Excel("Moyens")'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Excel - Moyens";}else{echo "Excel - Means";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
						<a style='text-decoration:none;' class='Bouton' href='javascript:Excel("Lettres")'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Excel - Lettres";}else{echo "Excel - Letters";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
						<a style='text-decoration:none;' class='Bouton' href='javascript:Excel("Fiches")'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Excel - données pour fiches qualifications";}else{echo "Excel - Data for qualification sheets";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="6"  align="right">
			<?php
			if($Droits=="Administrateur")
			{
				$result_Categorie_Maitre=mysqli_query($bdd,"SELECT * FROM new_competences_categorie_qualification_maitre ORDER BY Id ASC");
				while($row_Categorie_Maitre=mysqli_fetch_array($result_Categorie_Maitre))
				{
			?>
				<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Ajout','0','<?php echo $row_Categorie_Maitre['Id'];?>');">
					Ajouter une qualification "<?php echo $row_Categorie_Maitre['Libelle'];?>"
				</a>
			<?php
				}
			}
			?>
			<?php if(DroitsFormationPlateforme(array($IdPosteResponsableQualite))){ ?>
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreCopieLettre()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Dupliquer le paramétrage des lettres";}else{echo "Duplicate the setting of the letters";} ?>&nbsp;</a>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" style="width:1000px;">
						<?php
							$result_Categorie_Maitre=mysqli_query($bdd,"SELECT * FROM new_competences_categorie_qualification_maitre ORDER BY Id ASC");
							while($row_Categorie_Maitre=mysqli_fetch_array($result_Categorie_Maitre))
							{
						?>
							<tr>
								<td colspan="3" class="EnTeteTableauCompetencesMaitre"><?php echo $row_Categorie_Maitre['Libelle'];?></td>
								<?php
								if($Droits=="Administrateur")
								{
								?>
								<td>
									<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0','<?php echo $row_Categorie_Maitre['Id'];?>');">
										<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une qualification">
									</a>
								</td>
								<?php
								}
								?>
							</tr>
						<?php
							$result=mysqli_query($bdd,"SELECT new_competences_qualification.* FROM new_competences_qualification, new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id_Categorie_Maitre=".$row_Categorie_Maitre['Id']." AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id ORDER BY new_competences_categorie_qualification.Libelle ASC, new_competences_qualification.Libelle ASC");
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td colspan="3" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie qualification";}else{echo "Qualification group";}?></td>
							</tr>
							<?php
								$Couleur="#EEEEEE";
								$Categorie=0;
								while($row=mysqli_fetch_array($result))
								{
									
									$btrouve=1;
									if($motcle<>""){
										if(stripos($row['Libelle'],$motcle)===false){
											$btrouve=0;
										}
										else{
											$btrouve=1;
										}
									}
									if($btrouve==1){
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									$result2=mysqli_query($bdd,"SELECT Libelle, Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=".$row['Id_Categorie_Qualification']);
									$row2=mysqli_fetch_array($result2);
									if($Categorie!=$row2['Libelle']){echo "<tr height='1' bgcolor='#66AACC'><td colspan='4'></td></tr>";}
									$Categorie=$row2['Libelle'];
									
									
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="600"><?php echo $row['Libelle'];?></td>
								<td width="360"><?php echo $row2['Libelle'];?></td>
							<?php
                                if($Droits=="Ecriture")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>','<?php echo $row_Categorie_Maitre['Id'];?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td></td>
							<?php
								}
								elseif($Droits=="Administrateur")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>','<?php echo $row_Categorie_Maitre['Id'];?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>','<?php echo $row_Categorie_Maitre['Id'];?>');}">
								</td>
							<?php
								}
								else
								{
							?>
								<td colspan="2"></td>
							<?php
								}
							?>
							</tr>
<?php
								}
								}	//Fin boucle
							}		//Fin If
							}		// Fin Boucle Maitre
		mysqli_free_result($result);	// Libération des résultats
?>
						</table>
					</td>
				</tr>
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