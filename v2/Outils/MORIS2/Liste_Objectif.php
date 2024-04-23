<script language="javascript">
	function OuvreFenetreAjout(){
		var w=window.open("Ajout_Objectif.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
		w.focus();
		}
	function OuvreFenetreSuppr(Id){
		if(window.confirm('Etes-vous sûr de vouloir supprimer l\'accès à cette prestation ?')){
			var w=window.open("Ajout_Objectif.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
	function OuvreFenetreModif(Id)
		{
			var w= window.open("Ajout_Objectif.php?Mode=M&Id="+Id,"PageLieu","status=no,menubar=no,width=800,height=250");
			w.focus();
		}
	function AfficherleTheme(Id,checked)
	{
		formulaire.AfficherTheme.value=Id;
		formulaire.BAfficherTheme.value=checked;
		formulaire.submit();
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
if($_POST){
	if($_POST['AfficherTheme']<>"")
	{
		$afficher=0;
		if($_POST['BAfficherTheme']<>""){
			$afficher=1;
		}
		
		$req="SELECT Afficher FROM moris_objectifaffichage WHERE Theme='".$_POST['AfficherTheme']."' ";
		$resultAff=mysqli_query($bdd,$req);
		$nbResultaAff=mysqli_num_rows($resultAff);
		if($nbResultaAff>0){
			$rowAff=mysqli_fetch_array($resultAff);
			if($rowAff['Afficher']==1){
				$afficher=0;
			}
			else{
				$afficher=1;
			}
			$req="UPDATE moris_objectifaffichage SET Afficher=".$afficher." WHERE Theme='".$_POST['AfficherTheme']."' ";
			$resultUpd=mysqli_query($bdd,$req);
		}
		else{
			$req="INSERT INTO moris_objectifaffichage (Theme,Afficher) VALUES ('".$_POST['AfficherTheme']."',1) ";
			$resultUpd=mysqli_query($bdd,$req);
		}
	}
}
?>
<form id="formulaire" action="Liste_Objectif.php" method="post">
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="AfficherTheme" id="AfficherTheme" value="">
	<input type="hidden" name="BAfficherTheme" id="BAfficherTheme" value="">
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
			
			if($_SESSION["Langue"]=="FR"){SousTitre("CONTRAT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}
			else{SousTitre("CONTRACT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}

			if($_SESSION["Langue"]=="FR"){SousTitre("ENTITE ACHAT","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
			else{SousTitre("PURCHASE ENTITY","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
			
			if($_SESSION["Langue"]=="FR"){SousTitre("PROGRAMME / PRODUIT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
			else{SousTitre("PROGRAM / PRODUCT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
			
			if($_SESSION["Langue"]=="FR"){SousTitre("FAMILLE METIER/FONCTION","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
			else{SousTitre("JOB/POSITION FAMILY","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
			
			if($_SESSION["Langue"]=="FR"){SousTitre("OBJECTIFS","Outils/MORIS2/TableauDeBord.php?Menu=18",true);}
			else{SousTitre("OBJECTIVES","Outils/MORIS2/TableauDeBord.php?Menu=18",true);}
			
			?>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr><td colspan="11">
			<table style="width:100%;">
				<tr>
					<td width="50%" align="center">
						<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add an objective";}else{echo "Ajouter un objectif";} ?>&nbsp;</a>
					</td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td width="50%">
						<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:70%;">
							<tr>
								<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Thème";} ?></td>
								<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Objective";}else{echo "Objectif";} ?></td>
								<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date début";} ?></td>
								<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date fin";} ?></td>
								<td class="EnTeteTableauCompetences" width="2%" colspan="2"></td>
							</tr>
							<?php
								$req="SELECT Id, Theme,Pourcentage,DateDebut,DateFin
									FROM moris_objectifglobal
									WHERE Suppr=0
									ORDER BY Theme,DateDebut DESC;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$couleur="#ffffff";
									while($row=mysqli_fetch_array($result)){
										?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td><?php echo $row['Theme'];?></td>
											<td><?php echo $row['Pourcentage'];?></td>
											<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
											<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
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
					</td>
					<td width="50%">
						<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:50%;">
							<tr>
								<td class="EnTeteTableauCompetences" width="40%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Thème";} ?></td>
								<td class="EnTeteTableauCompetences" width="60%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Display target on graphs";}else{echo "Afficher l'objectif sur les graphiques";} ?></td>
							</tr>
							<?php
								$couleur="#ffffff";
								
								$tabTheme = array("OTD activité","OTD livrable","OQD activité","OQD livrable","Plan de prévention","Productivité corrigée","Satisfaction client","Taux de polyvalence","Taux de qualification");
								foreach($tabTheme as $theme){
									$afficher="";
									$req="SELECT Id FROM moris_objectifaffichage WHERE Theme='".$theme."' AND Afficher=1 ";
									$resultAff=mysqli_query($bdd,$req);
									$nbResultaAff=mysqli_num_rows($resultAff);
									if($nbResultaAff>0){
										$afficher="checked";
									}
							?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td><?php echo $theme;?></td>
										<td align="center">
											<label class='switch'>
											  <input type='checkbox' id='CB_"<?php echo $theme;?>' name='CB_<?php echo $theme;?>' <?php echo $afficher;?> onchange='javascript:AfficherleTheme("<?php echo $theme;?>","<?php echo $theme;?>");'>                          
											  <span class='slider round'></span>
											</label>
										</td>
									</tr>
							<?php
									if($couleur=="#ffffff"){$couleur="#a3e4ff";}
									else{$couleur="#ffffff";}
								}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>