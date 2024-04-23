<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Suppr_MouvementPersonnelHistorique.php?Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageMouement","status=no,menubar=no,width=50,height=50");
		w.focus();
		}
	function OuvreFenetreModif2(Menu,Id)
		{var w=window.open("Modif_MouvementPersonnelHistorique.php?Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageMouement","status=no,menubar=no,width=600,height=200");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_MouvementPersonnel.php?Menu="+Menu,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function filtrerSansContrat(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnFiltrerSansContrat2' name='btnFiltrerSansContrat2' value='Filtrer'>";
		document.getElementById('filtrer').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnFiltrerSansContrat2").dispatchEvent(evt);
		document.getElementById('filtrer').innerHTML="";
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$personne=0;
if(isset($_GET['Id_Personne'])){$personne=$_GET['Id_Personne'];}
elseif(isset($_POST['Id_Personne'])){$personne=$_POST['Id_Personne'];}

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	if($Selected==true){$tiret="border-bottom:4px solid white;";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#0c4985;valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#0c4985;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#0c4985';\" onmouseout=\"this.style.color='#0c4985';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}

//Interface qui ne concerne que les managers

$TDB=0;
if($_GET){
	if(isset($_GET['TDB'])){
		$TDB=$_GET['TDB'];
	}
}
else{
	$TDB=$_POST['TDB'];
}
$OngletTDB="";
if($_GET){
	if(isset($_GET['OngletTDB'])){
		$OngletTDB=$_GET['OngletTDB'];
	}
}
else{
	$OngletTDB=$_POST['OngletTDB'];
}
?>

<form class="test" action="Liste_MouvementPersonnelHistorique.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#1365b6;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des mouvements de personnel";}else{echo "List of staff movements";}
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
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr bgcolor="#92c3f4">
					<?php
						$ParametreTDB="";
						if($TDB>0){$ParametreTDB="&TDB=".$TDB;}
						if($OngletTDB<>""){$ParametreTDB.="&OngletTDB=".$OngletTDB;}
						if($Menu==4){
							if($_SESSION["Langue"]=="FR"){Titre1("MOUVEMENTS EN COURS","Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=".$Menu.$ParametreTDB,false);}
							else{Titre1("MOVEMENTS IN PROGRESS","Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=".$Menu."",false);}
						}
						else{
							if($_SESSION["Langue"]=="FR"){Titre1("MOUVEMENTS EN COURS","Outils/PlanningV2/Liste_MouvementPersonnel.php?Menu=".$Menu.$ParametreTDB,false);}
							else{Titre1("MOVEMENTS IN PROGRESS","Outils/PlanningV2/Liste_MouvementPersonnel.php?Menu=".$Menu."",false);}

						}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu.$ParametreTDB,true);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu."",true);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="5"></td></tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher : ";}else{echo "Search : ";} 
				if($_POST){$_SESSION['FiltreRHMouvement_Recherche']=$_POST['recherche'];}
				?>
				<input id="recherche" name="recherche" type="texte" value="<?php if($_SESSION['FiltreRHMouvement_Recherche']<>"-1"){echo $_SESSION['FiltreRHMouvement_Recherche'];} ?>" size="25"/>&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> <br>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Affectées mais sans contrat ";}else{echo "Assigned but without contract ";}
				if($_POST){if(isset($_POST['btnFiltrerSansContrat2'])){$_SESSION['FiltreRHMouvement_Recherche']="-1";}}
				?>
				<img id="btnFiltrerSansContrat" name="btnFiltrerSansContrat" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrerSansContrat();"/>
				<div id="filtrer"></div>
			</td>
			<td width="80%" rowspan="4" valign="top">
				<?php
					if($personne>0){
				?>
					<table width="100%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="8"></td></tr>
						<tr>
							<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
							<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Code analytique";}else{echo "Analytical code";} ?></td>
							<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
							<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";} ?></td>
							<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?></td>
							<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?></td>
							<td class="EnTeteTableauCompetences" width="5%"></td>
							<td class="EnTeteTableauCompetences" width="5%"></td>
						</tr>
						<?php
						$req="SELECT 
								Id,DateDebut,DateFin,Id_Prestation,Id_Pole,Id_PrestationDepart,Id_PoleDepart,DateCreation,
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
								(SELECT Code_Analytique FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Code_Analytique,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole
							FROM rh_personne_mouvement
							WHERE Suppr=0 
							AND Id_Personne=".$personne."
							AND EtatValidation=1
						ORDER BY DateDebut DESC
						";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						
						$Id_PrestaPole=PrestationPole_Personne(date('Y-m-d'),$personne);
						$couleur="#FFFFFF";
						$premier=0;
						if($nbResulta>0){
							while($row=mysqli_fetch_array($result))
							{
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}
						?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes(str_replace("///","",$row['Plateforme'])); ?></td>
									<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes(str_replace("///","",$row['Code_Analytique'])); ?></td>
									<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes(str_replace("///","",$row['Prestation']));if($row['Pole']<>""){echo " - ".stripslashes(str_replace("///","",$row['Pole']));} ?></td>
									<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
									<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
									<td style="border-bottom:1px dotted #976fa1;"><?php if($row['DateFin']<="0001-01-01"){echo "-";}else{echo AfficheDateJJ_MM_AAAA($row['DateFin']);}?></td>
									<td style="border-bottom:1px dotted #976fa1;">
									<?php
									if($Menu==4){
										if($premier==0){
									?>
										<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="OuvreFenetreModif2('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');"><img src="../../Images/Modif.gif" border="0" title="Modif"></a>
									<?php
										}
									}
									?>
									</td>
									<td style="border-bottom:1px dotted #976fa1;">
									<?php
									if($Menu==4){
									?>
										<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreModif('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer"></a>
									<?php
									}
									?>
									</td>
								</tr>
						<?php
								$premier=1;
							}	
						}
						?>
					</table>
				<?php
					}
				?>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Liste des personnes : ";}else{echo "List of people : ";} 
				?>
			</td>
		</tr>
		<tr>
			<td width="20%" valign="top">
				&nbsp;<div id='div_Personne' style='height:160px;width:100%;overflow:auto;' >
					<?php
					echo "<table width='100%' valign='top'>";
					$requete="SELECT DISTINCT new_rh_etatcivil.Id, 
						CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
						FROM new_rh_etatcivil
						WHERE  ";
					if($_SESSION['FiltreRHMouvement_Recherche']==""){
						$requete.="Id=0 ";
					}
					elseif($_SESSION['FiltreRHMouvement_Recherche']=="-1"){
						//Liste des personnes qui ont un mouvement E/C mais pas de contrat 
						$requete.=" (SELECT COUNT(rh_personne_contrat.Id)
								FROM rh_personne_contrat
								WHERE rh_personne_contrat.Suppr=0
								AND rh_personne_contrat.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_contrat.DateFin>='".date('Y-m-d')."' OR rh_personne_contrat.DateFin<='0001-01-01')
								AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
								AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant'))=0
								
								AND 
								(SELECT COUNT(rh_personne_mouvement.Id)
								FROM rh_personne_mouvement
								WHERE rh_personne_mouvement.Suppr=0
								AND rh_personne_mouvement.Id_Personne=new_rh_etatcivil.Id
								AND rh_personne_mouvement.EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."'))>0
								";
					}
					else{
						$requete.="CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) LIKE \"%".$_SESSION['FiltreRHMouvement_Recherche']."%\" ";
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
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&Id_Personne=".$row['Id']."#selection'>".$row['Personne']."</a></td></tr>";
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
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>