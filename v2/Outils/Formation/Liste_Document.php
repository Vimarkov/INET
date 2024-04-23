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
			var w=window.open("Ajout_Document.php?Mode="+Mode+"&Id="+Id,"PageDocument","status=no,menubar=no,width=800,height=250");
			w.focus();
		}
	}
	function OuvreExcel(NomDocument,Id_Document,Id_Document_Langue){
		if(NomDocument=="EVALUATION_A_CHAUD"){
			var w=window.open("FICHE_EVAL.php?Id_Document="+Id_Document+"&Id_Document_Langue="+Id_Document_Langue,"PageDocument","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
	}
</script>
<?php
if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Reference"){
		$_SESSION['TriDocument_General']= str_replace("Reference ASC,","",$_SESSION['TriDocument_General']);
		$_SESSION['TriDocument_General']= str_replace("Reference DESC,","",$_SESSION['TriDocument_General']);
		$_SESSION['TriDocument_General']= str_replace("Reference ASC","",$_SESSION['TriDocument_General']);
		$_SESSION['TriDocument_General']= str_replace("Reference DESC","",$_SESSION['TriDocument_General']);
		if($_SESSION['TriDocument_Reference']==""){$_SESSION['TriDocument_Reference']="ASC";$_SESSION['TriDocument_General'].= "Reference ".$_SESSION['TriDocument_Reference'].",";}
		elseif($_SESSION['TriDocument_Reference']=="ASC"){$_SESSION['TriDocument_Reference']="DESC";$_SESSION['TriDocument_General'].= "Reference ".$_SESSION['TriDocument_Reference'].",";}
		else{$_SESSION['TriDocument_Reference']="";}
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
						
					if($LangueAffichage=="FR"){echo "Gestion des documents complémentaires";}else{echo "Management of additional documents";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Document.php?Tri=Reference"><?php if($LangueAffichage=="FR"){echo "Reference";}else{echo "Reference";}?><?php if($_SESSION['TriDocument_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDocument_Reference']=="ASC"){echo "&darr;";}?></a></td>
					<?php
					$requete="SELECT Id, Reference ";
					$resultlangue=mysqli_query($bdd,"SELECT Id, Libelle FROM form_langue WHERE Suppr=0 ORDER BY Libelle");
					$nblangue=mysqli_num_rows($resultlangue);
					while($rowlangue=mysqli_fetch_array($resultlangue))
					{
						echo "<td class='EnTeteTableauCompetences' style='width:200;color:#003cff;'>".$rowlangue['Libelle']."</td>";
						$requete.=", (SELECT Libelle FROM form_document_langue WHERE Id_Document=form_document.Id AND Suppr=0 AND Id_Langue=".$rowlangue['Id']." LIMIT 1)";
						$requete.=", (SELECT Id FROM form_document_langue WHERE Id_Document=form_document.Id AND Suppr=0 AND Id_Langue=".$rowlangue['Id']." LIMIT 1)";
						$requete.=", (SELECT NomDocument FROM form_document_langue WHERE Id_Document=form_document.Id AND Suppr=0 AND Id_Langue=".$rowlangue['Id']." LIMIT 1)";
					}
					$requete.=" FROM form_document WHERE Suppr=0 ";
					if($_SESSION['TriDocument_General']<>""){
						$requete.="ORDER BY ".substr($_SESSION['TriDocument_General'],0,-1);
					}
					$result=mysqli_query($bdd,$requete);
					$nbenreg=mysqli_num_rows($result);
					
					if($IdPersonneConnectee==1351 || $IdPersonneConnectee==406 || $IdPersonneConnectee==3556){
					?>
					<td align="right" width="5%" colspan="2" class="EnTeteTableauCompetences">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un document">
						</a>
					</td>
					<?php
					}
					?>
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
					<td><?php echo $row['Reference'];?></td>
					<?php
						$DirFichier="https://extranet.aaa-aero.com/v2/Outils/Formation/Docs/Document/";
						for($i=0;$i<($nblangue*3);$i=$i+3)
						{
							echo "<td>".$row[$i+2];
							if($row[$i+3]<>""){
								//Recuperation du document 
								
								echo "&nbsp;&nbsp;<a class='Modif' href=\"".$DirFichier.$row[0]."/".$row[$i+4]."\">";
								echo "<img src='../../Images/Tableau.gif' style='border:0;' alt='Doc'>";
								echo "</a>";
									
							}
							echo "</td>";
						}
					?>
					<td>
						<?php if($IdPersonneConnectee==1351 || $IdPersonneConnectee==406 || $IdPersonneConnectee==3556){ ?>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
						<?php } ?>
					</td>
					<td>
						<?php if($IdPersonneConnectee==1351 || $IdPersonneConnectee==406 || $IdPersonneConnectee==3556){ ?>
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