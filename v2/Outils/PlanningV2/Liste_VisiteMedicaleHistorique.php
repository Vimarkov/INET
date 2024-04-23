<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id,Id_Personne,Page)
		{var w=window.open("Ajout_VM.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=700,height=400,scrollbars=1'");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id,Id_Personne,Page)
		{var w=window.open("Ajout_VM.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=700,height=400");
		w.focus();
		}
	function NouvelleVisite(Id_Personne,Page)
		{var w=window.open("Ajout_VM.php?Mode=A&Id=0&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=700,height=400,scrollbars=1'");
		w.focus();
		}
	function VMExcel(Id)
		{window.open("Export_VM.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if(($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)) || ($Menu==9 && DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH)))){
$personne=0;
if(isset($_GET['Id_Personne'])){$personne=$_GET['Id_Personne'];}
elseif(isset($_POST['Id_Personne'])){$personne=$_POST['Id_Personne'];}

$bExiste=false;
if(isset($_GET['Tri'])){
	$tab = array("Id","TypeDocument","TypeContrat","AgenceInterim","Metier","Coeff","DateDebut","DateFin","SalaireBrut","TauxHoraire","TempsTravail");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHContratH_General']= str_replace($tri." ASC,","",$_SESSION['TriRHContratH_General']);
			$_SESSION['TriRHContratH_General']= str_replace($tri." DESC,","",$_SESSION['TriRHContratH_General']);
			$_SESSION['TriRHContratH_General']= str_replace($tri." ASC","",$_SESSION['TriRHContratH_General']);
			$_SESSION['TriRHContratH_General']= str_replace($tri." DESC","",$_SESSION['TriRHContratH_General']);
			if($_SESSION['TriRHContratH_'.$tri]==""){$_SESSION['TriRHContratH_'.$tri]="ASC";$_SESSION['TriRHContratH_General'].= $tri." ".$_SESSION['TriRHContratH_'.$tri].",";}
			elseif($_SESSION['TriRHContratH_'.$tri]=="ASC"){$_SESSION['TriRHContratH_'.$tri]="DESC";$_SESSION['TriRHContratH_General'].= $tri." ".$_SESSION['TriRHContratH_'.$tri].",";}
			else{$_SESSION['TriRHContratH_'.$tri]="";}
		}
	}
}

function Titre1($Libelle,$Lien,$Selected){
		$tiret="";
		if($Selected==true){$tiret="border-bottom:4px solid white;";}
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#848339;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#848339;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#848339';\" onmouseout=\"this.style.color='#848339';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}

?>

<form class="test" action="Liste_VisiteMedicaleHistorique.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $personne; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#cdcc8d;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des visites médicales";}else{echo "Management of medical visits";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5" valign="top">
			<table style="width:100%; border-spacing:0;" valign="top">
				<tr bgcolor="#e8e7ca">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("A VENIR","Outils/PlanningV2/Liste_VisiteMedicaleEC.php?Menu=".$Menu."",false);}
						else{Titre1("TO COME UP","Outils/PlanningV2/Liste_VisiteMedicaleEC.php?Menu=".$Menu."",false);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_VisiteMedicaleHistorique.php?Menu=".$Menu."",true);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_VisiteMedicaleHistorique.php?Menu=".$Menu."",true);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="5"></td></tr>
		<tr>
			<td height="2%" width="20%" class="Libelle" valign="top">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher : ";}else{echo "Search : ";} 
				if($_POST){$_SESSION['FiltreRHContrat_Recherche']=$_POST['recherche'];}
				?>
				<input id="recherche" name="recherche" type="texte" size="15" value="<?php echo $_SESSION['FiltreRHContrat_Recherche']; ?>" size="25"/>&nbsp;&nbsp;&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="85%" rowspan="4" valign="top">
			<?php
				if($personne>0){
			?>
				<table width="100%" align="center" cellpadding="0" cellspacing="0" valign="top">
					<tr>
						<td colspan="20" align="center">
							<input class="Bouton" type="button" id="nouveauContrat" name="nouveauContrat" value="<?php if($_SESSION["Langue"]=="FR"){echo "Nouvelle visite";}else{echo "New visit";} ?>" onClick="NouvelleVisite('<?php echo $personne; ?>','Liste_VisiteMedicaleHistorique')">
						</td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Date";}else{echo "Date";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Type de visite";}else{echo "Type of visit";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "SMR";}else{echo "SMR";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Restriction d'aptitude";}else{echo "Restriction of aptitude";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
						<td class="EnTeteTableauCompetences" width="1%"></td>
						<td class="EnTeteTableauCompetences" width="1%"></td>
					</tr>
					<?php
					$req="SELECT 
							Id,DateVisite,RestrictionAptitude,CommentaireRestriction,PJ_AvisAptitude,
							(SELECT Libelle FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=Id_TypeVisite) AS TypeVisite,
							(SELECT LibelleEN FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=Id_TypeVisite) AS TypeVisiteEN
						FROM rh_personne_visitemedicale
						WHERE Suppr=0 
						AND Id_Personne=".$personne." ";
					$req.="ORDER BY DateVisite DESC
					";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					
					$couleur="#EEEEEE";
					if($nbResulta>0){
						while($row=mysqli_fetch_array($result))
						{
							if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
							else{$couleur="#FFFFFF";}
							
							if($_SESSION["Langue"]=="FR"){$restriction="Non";}else{$restriction="No";}
							if($row['RestrictionAptitude']==1){if($_SESSION["Langue"]=="FR"){$restriction="Oui";}else{$restriction="Yes";}}
							
							$smr="";
							if($_SESSION["Langue"]=="FR"){
								$req="SELECT DISTINCT (SELECT Libelle FROM rh_smr WHERE rh_smr.Id=rh_personne_vm_smr.Id_SMR) AS SMR 
								FROM rh_personne_vm_smr
								WHERE Suppr=0 
								AND Id_Personne_VM=".$row['Id']." ";
							}
							else{
								$req="SELECT DISTINCT (SELECT LibelleEN FROM rh_smr WHERE rh_smr.Id=rh_personne_vm_smr.Id_SMR) AS SMR 
								FROM rh_personne_vm_smr
								WHERE Suppr=0 
								AND Id_Personne_VM=".$row['Id']." ";
							}
							$resultSMR=mysqli_query($bdd,$req);
							$nbResultaSMR=mysqli_num_rows($resultSMR);
							if($nbResultaSMR>0){
								while($rowSMR=mysqli_fetch_array($resultSMR))
								{
									if($smr<>""){$smr.="<br>";}
									$smr.=$rowSMR['SMR'];
								}
							}
							
					?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateVisite']);?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php if($_SESSION["Langue"]=="FR"){echo stripslashes($row['TypeVisite']);}else{echo stripslashes($row['TypeVisiteEN']);}?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo $smr; ?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo $restriction;?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($row['CommentaireRestriction']);?></td>
								<td style="border-bottom:1px dotted #976fa1;" align="center">
									<a class="Modif" href="javascript:OuvreFenetreModif('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>',<?php echo $personne; ?>,'Liste_VisiteMedicaleHistorique');">
										<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
									</a>
								</td>
								<td style="border-bottom:1px dotted #976fa1;" align="center">
									<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>',<?php echo $personne; ?>,'Liste_VisiteMedicaleHistorique');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
								</td>
							</tr>
					<?php
						}	
					}
					?>
				</table>
			<?php
				}
			?>
			</td>
		</tr>
		<tr><td height="5" height="2%" valign="top"></td></tr>
		<tr>
			<td width="15%" class="Libelle" valign="top" height="2%">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Liste des personnes : ";}else{echo "List of people : ";} 
				?>
			</td>
		</tr>
		<tr>
			<td width="15%" valign="top" height="99%">
				&nbsp;<div id='div_Personne' style='height:160px;width:100%;overflow:auto;' >
					<?php
					echo "<table width='100%' valign='top'>";
					$requete="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						FROM new_rh_etatcivil
						WHERE  ";
					if($_SESSION['FiltreRHContrat_Recherche']==""){
						$requete.="Id=0 ";
					}
					else{
						$requete.="CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) LIKE \"%".$_SESSION['FiltreRHContrat_Recherche']."%\" ";
					}
					$requete.="ORDER BY Personne ASC";
					$result=mysqli_query($bdd,$requete);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$couleur="";
							$ancre="";
							if($personne>0){
								if($personne==$row['Id']){$couleur="bgcolor='#f3fa72'";$ancre="id='selection'";}
							}
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_VisiteMedicaleHistorique.php?Menu=".$Menu."&Id_Personne=".$row['Id']."#selection'>".$row['Personne']."</a></td></tr>";
						}
					}
					echo "</table>";
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
}
?>
	
</body>
</html>