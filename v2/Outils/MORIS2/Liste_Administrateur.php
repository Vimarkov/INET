<script language="javascript">
	function OuvreFenetreAjout(){
		var w=window.open("Ajout_Utilisateur.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=150");
		w.focus();
		}
	function OuvreFenetreSuppr(Id){
		if(window.confirm('Etes-vous s�r de vouloir supprimer les acc�s?')){
			var w=window.open("Ajout_Utilisateur.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
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
		
		if($_SESSION["Langue"]=="FR"){SousTitre("ADMINISTRATEURS","Outils/MORIS2/TableauDeBord.php?Menu=3",true);}
		else{SousTitre("ADMINISTRATOR","Outils/MORIS2/TableauDeBord.php?Menu=3",true);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("LISTE PRESTATIONS","Outils/MORIS2/TableauDeBord.php?Menu=4",false);}
		else{SousTitre("LIST OF SITE","Outils/MORIS2/TableauDeBord.php?Menu=4",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("AIDE","Outils/MORIS2/TableauDeBord.php?Menu=5",false);}
		else{SousTitre("HELP","Outils/MORIS2/TableauDeBord.php?Menu=5",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		else{SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("DIVISION CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		else{SousTitre("CUSTOMER DIVISION","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CONTRAT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}
		else{SousTitre("CONTRACT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}

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
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add administrator";}else{echo "Ajouter un administrateur";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="11">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:40%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Personne";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom 
					FROM moris_administrateur 
					LEFT JOIN new_rh_etatcivil ON moris_administrateur.Id_Personne=new_rh_etatcivil.Id 
					ORDER BY Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="16%">&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
							<td width="2%" align="center">
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