<script language="javascript">
	function OuvreFenetreAjout(){
		var w=window.open("Ajout_Contrat.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
		w.focus();
		}
	function OuvreFenetreSuppr(Id){
		if(window.confirm('Etes-vous sûr de vouloir supprimer l\'accès à cette prestation ?')){
			var w=window.open("Ajout_Contrat.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
	function OuvreFenetreModif(Id)
		{
			var w= window.open("Ajout_Contrat.php?Mode=M&Id="+Id,"PageLieu","status=no,menubar=no,width=800,height=250");
			w.focus();
		}
</script>
<?php
function SousTitre($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:3px solid #ffffff;font-style:italic;font-size:14px;";$couleurTexte="#ffffff";}
	echo "<td style=\"height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
	<tr bgcolor="#6EB4CD">
		<?php
		if($_SESSION["Langue"]=="FR"){SousTitre("ACCES SUPP.","Outils/MORIS2/TableauDeBord.php?Menu=16",false);}
		else{SousTitre("ADDITIONAL ACCESS","Outils/MORIS2/TableauDeBord.php?Menu=16",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("ADMINISTRATEURS","Outils/MORIS2/TableauDeBord.php?Menu=3",false);}
		else{SousTitre("ADMINISTRATOR","Outils/MORIS2/TableauDeBord.php?Menu=3",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("LISTE PRESTATIONS","Outils/MORIS2/TableauDeBord.php?Menu=4",false);}
		else{SousTitre("LIST OF SITE","Outils/MORIS2/TableauDeBord.php?Menu=4",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("AIDE","Outils/MORIS2/TableauDeBord.php?Menu=5",false);}
		else{SousTitre("HELP","Outils/MORIS2/TableauDeBord.php?Menu=5",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		else{SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("DIVISION CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		else{SousTitre("CUSTOMER DIVISION","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CONTRAT","Outils/MORIS2/TableauDeBord.php?Menu=9",true);}
		else{SousTitre("CONTRACT","Outils/MORIS2/TableauDeBord.php?Menu=9",true);}

		if($_SESSION["Langue"]=="FR"){SousTitre("ENTITE ACHAT","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
		else{SousTitre("PURCHASE ENTITY","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("PROGRAMME / PRODUIT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
		else{SousTitre("PROGRAM / PRODUCT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("FAMILLE METIER/FONCTION","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
		else{SousTitre("JOB/POSITION FAMILY","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("OBJECTIFS","Outils/MORIS2/TableauDeBord.php?Menu=18",false);}
		else{SousTitre("OBJECTIVES","Outils/MORIS2/TableauDeBord.php?Menu=18",false);}
		
		?>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center" colspan="11">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a contract";}else{echo "Ajouter un contrat";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="11">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:20%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="95%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libellé";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id, Libelle
					FROM moris_contrat
					WHERE Suppr=0
					ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td><?php echo $row['Libelle'];?></td>
							<td align="center">
								<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>'>
								</a>
							</td>
							<td align="center">
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
							</td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
</table>