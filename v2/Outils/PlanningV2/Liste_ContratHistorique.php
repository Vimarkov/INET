<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_Contrat.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550,scrollbars=1'");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_Contrat.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
	function OuvreFenetreModifODM(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_ODM.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550,scrollbars=1'");
		w.focus();
		}
	function OuvreFenetreSupprODM(Menu,Id,Id_Personne,Page)
		{var w=window.open("Modif_ODM.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
	function NouveauContrat(Id_Personne,Page)
		{var w=window.open("Ajout_Contrat.php?Mode=A&Id=0&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1100,height=600,scrollbars=1'");
		w.focus();
		}
	function NouvelAvenant(Id_Personne,Id,Page)
		{var w=window.open("Ajout_ContratAvenant.php?Mode=A&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1100,height=600,scrollbars=1'");
		w.focus();
		}
	function NouveauODM(Id_Personne,Id,Page)
		{var w=window.open("Ajout_ODM.php?Mode=A&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageContrat","status=no,menubar=no,width=1100,height=600,scrollbars=1'");
		w.focus();
		}
	function ContratExcel(Id)
		{window.open("Export_Contrat.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
	function ODMExcel(Id)
		{window.open("Export_ODM.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
	function OuvreFenetreAvenantTempsTravail(Page)
		{var w=window.open("Ajout_ContratAvenantTT.php?Mode=A&Menu="+document.getElementById('Menu').value+"&Page="+Page+"&Id_Personne=0","PageContrat","status=no,menubar=no,width=1000,height=650,scrollbars=1'");
		w.focus();
		}
	function OuvreFenetreODMCommun(Page)
		{var w=window.open("Ajout_ODMCommun.php?Mode=A&Menu="+document.getElementById('Menu').value+"&Page="+Page+"&Id_Personne=0","PageContratCommun","status=no,menubar=no,width=1000,height=650,scrollbars=1'");
		w.focus();
		}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
$personne=0;
if(isset($_GET['Id_Personne'])){$personne=$_GET['Id_Personne'];}
elseif(isset($_POST['Id_Personne'])){$personne=$_POST['Id_Personne'];}

$bExiste=false;
if($_POST){
	if(isset($_POST['btnModifierEtatCivil'])){
		//Vérfier si le nom et prénom existe 
		$req="SELECT Id FROM new_rh_etatcivil WHERE Id<>".$personne." AND Nom='".$_POST['nom']."' AND Prenom='".$_POST['prenom']."' ";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			$bExiste=true;
		}
		else{
			
			$req="UPDATE new_rh_etatcivil 
				SET
					Nom='".addslashes($_POST['nom'])."',
					Prenom='".addslashes($_POST['prenom'])."',
					Sexe='".$_POST['sexe']."',
					Nationalite='".addslashes($_POST['nationalite'])."',
					Date_Naissance='".TrsfDate_($_POST['dateNaissance'])."',
					Ville_Naissance='".addslashes($_POST['lieuNaissance'])."',
					Num_SS='".$_POST['numSecu']."',
					Adresse='".addslashes(addslashes($_POST['adresse']))."',
					CP='".$_POST['cp']."',
					Ville='".addslashes($_POST['ville'])."',
					TelephoneMobil='".$_POST['telephonePerso']."',
					Email='".$_POST['emailPerso']."',
					Type_TitreTravailEtranger='".addslashes($_POST['titreSejour'])."',
					Num_TitreTravailEtranger='".addslashes($_POST['numTitreSejour'])."',
					DateAncienneteCDI='".TrsfDate_($_POST['dateAnciennete'])."',
					DateDebut1erContratAAA='".TrsfDate_($_POST['dateAncienneteAdministrative'])."',
					DateDebut18Mois='".TrsfDate_($_POST['dateDebutContrat18Mois'])."',
					MatriculeAAA='".addslashes($_POST['matriculeAAA'])."',
					MatriculeDaher='".addslashes($_POST['matriculeDaher'])."',
					MatriculeDSK='".addslashes($_POST['matriculeDSK'])."',
					MatriculeCEGID='".addslashes($_POST['matriculeCEGID'])."'
				WHERE Id=".$personne."
				";
				$resultModif=mysqli_query($bdd,$req);
		}
	}
}
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
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#5c4165';\" onmouseout=\"this.style.color='#5c4165';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}

?>

<form class="test" action="Liste_ContratHistorique.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $personne; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des contrats";}else{echo "Contract management";}
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
				<tr bgcolor="#cdbad2">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("CONTRATS EN COURS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",false);}
						else{Titre1("CONTRACTS IN PROGRESS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("ODM EN COURS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						else{Titre1("MISSION ORDER IN PROGRESS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",true);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",true);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT AUGMENTATIONS","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT INCREASES","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td align="right">
			<input class="Bouton" type="button" id="avenantCommun" name="avenantCommun" value="<?php if($_SESSION["Langue"]=="FR"){echo "Émettre un avenant commun \n(Modification du temps de travail)";}else{echo "Issue a joint endorsement \n(Change of working time)";} ?>" onClick="OuvreFenetreAvenantTempsTravail('Liste_ContratHistorique')">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="5"></td></tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher : ";}else{echo "Search : ";} 
				if($_POST){$_SESSION['FiltreRHContrat_Recherche']=$_POST['recherche'];}
				?>
				<input id="recherche" name="recherche" type="texte" value="<?php echo $_SESSION['FiltreRHContrat_Recherche']; ?>" size="25"/>&nbsp;&nbsp;&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="85%" rowspan="4">
			<?php
				if($personne>0){
					$req="SELECT Id,Nom,Prenom,Sexe,Nationalite,Date_Naissance,Ville_Naissance,Num_SS,Adresse,CP,Ville,TelephoneMobil,Email,DateDebut18Mois,
						Type_TitreTravailEtranger,Num_TitreTravailEtranger,DateAncienneteCDI,MatriculeAAA,MatriculeDaher,MatriculeDSK,MatriculeCEGID,DateDebut1erContratAAA,
						CentreDeCout
						FROM new_rh_etatcivil 
						WHERE Id=".$personne." ";
					$result=mysqli_query($bdd,$req);
					$rowEtatCivil=mysqli_fetch_array($result);
					
			?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr><td>
						<table width="95%" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="6">
								<table width="100%">
									<tr>
										<td width="98%" bgcolor="#d597b3" style="height:20px;border-spacing:0;text-align:center;color:#000000;valign:top;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "ETAT CIVIL";}else{echo "CIVIL STATUS";}?></td>
										<td width="2%"><a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="window.location='Liste_ContratHistorique.php?Menu=<?php echo $Menu; ?>&Id_Personne=<?php echo $personne; ?>';"><img src="../../Images/refresh.png" style="width:18px;" border="0" title="Refresh" alt="Refresh"></a></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr><td height="8"></td></tr>
							<?php
								if($bExiste==true){
									if($_SESSION["Langue"]=="FR"){
										echo "<tr><td style='color:#ff0000;font:bold;'>Modification impossible car cette personne existe déjà</td></tr>";
									}
									else{
										echo "<tr><td style='color:#ff0000;font:bold;'>Cannot change because this person already exists</td></tr>";
									}
									echo "<tr><td height='8'></td></tr>";
								}
							?>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nom :";}else{echo "Name :";} ?></td>
								<td width="10%">
									<input name="nom" id="nom" size="15" value="<?php echo stripslashes($rowEtatCivil['Nom']); ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom :";}else{echo "First name :";} ?></td>
								<td width="10%">
									<input name="prenom" id="prenom" size="15" value="<?php echo stripslashes($rowEtatCivil['Prenom']); ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Sexe :";}else{echo "Gender :";} ?></td>
								<td width="10%">
									<select name="sexe" id="sexe">
										<option value="Homme" <?php if($rowEtatCivil['Sexe']=="Homme"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Homme";}else{echo "Man";} ?></option>
										<option value="Femme" <?php if($rowEtatCivil['Sexe']=="Femme"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Femme";}else{echo "Woman";} ?></option>
									</select>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nationalité :";}else{echo "Nationality :";} ?></td>
								<td width="10%">
									<input name="nationalite" id="nationalite" size="15" value="<?php echo stripslashes($rowEtatCivil['Nationalite']); ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance :";}else{echo "Birth date :";} ?> </td>
								<td width="10%"><input type="date" style="text-align:center;" id="dateNaissance" name="dateNaissance" size="10" value="<?php echo AfficheDateFR($rowEtatCivil['Date_Naissance']); ?>"></td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu de naissance :";}else{echo "Place of birth :";} ?></td>
								<td width="10%">
									<input name="lieuNaissance" id="lieuNaissance" size="15" value="<?php echo stripslashes($rowEtatCivil['Ville_Naissance']); ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° sécurité social :";}else{echo "Social security number :";} ?></td>
								<td width="10%">
									<input name="numSecu" id="numSecu" size="20" value="<?php echo $rowEtatCivil['Num_SS']; ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Titre de séjour :";}else{echo "Title of stay :";} ?></td>
								<td width="10%">
									<input name="titreSejour" id="titreSejour" size="45" value="<?php echo stripslashes($rowEtatCivil['Type_TitreTravailEtranger']); ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° titre de séjour :";}else{echo "Number of residence permit :";} ?></td>
								<td width="10%">
									<input name="numTitreSejour" id="numTitreSejour" size="30" value="<?php echo stripslashes($rowEtatCivil['Num_TitreTravailEtranger']); ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse :";}else{echo "Address :";} ?></td>
								<td width="10%">
									<input name="adresse" id="adresse" size="50" value="<?php echo stripslashes($rowEtatCivil['Adresse']); ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "CP :";}else{echo "PC :";} ?></td>
								<td width="10%">
									<input name="cp" id="cp" size="8" value="<?php echo $rowEtatCivil['CP']; ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ville :";}else{echo "City :";} ?></td>
								<td width="10%">
									<input name="ville" id="ville" size="15" value="<?php echo stripslashes($rowEtatCivil['Ville']); ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° téléphone personnel :";}else{echo "Personal telephone number :";} ?></td>
								<td width="10%">
									<input name="telephonePerso" id="telephonePerso" size="15" value="<?php echo $rowEtatCivil['TelephoneMobil']; ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Email personnel :";}else{echo "Personal email :";} ?></td>
								<td width="10%">
									<input name="emailPerso" id="emailPerso" size="20" value="<?php echo $rowEtatCivil['Email']; ?>">
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule CEGID :";}else{echo "CEGID number :";} ?></td>
								<td width="10%">
									<input name="matriculeCEGID" id="matriculeCEGID" size="15" value="<?php echo $rowEtatCivil['MatriculeCEGID']; ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'ancienneté (si CDI) :";}else{echo "Date of seniority (if CDI):";} ?> </td>
								<td width="10%"><input type="date" style="text-align:center;" id="dateAnciennete" name="dateAnciennete" size="10" value="<?php echo AfficheDateFR($rowEtatCivil['DateAncienneteCDI']); ?>"></td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule AAA Paris : <br>(si CDI)";}else{echo "AAA Paris number (if CDI) :";} ?></td>
								<td width="10%">
									<table>
									<tr>
									<td>
									<input name="matriculeAAA" id="matriculeAAA" size="15" value="<?php echo $rowEtatCivil['MatriculeAAA']; ?>">
									</td>
									
									<?php 
										$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE MatriculeAAA<>'' AND MatriculeAAA='".$rowEtatCivil['MatriculeAAA']."' ORDER BY Personne ASC";
										$resultPers=mysqli_query($bdd,$req);
										$nbResultaPers=mysqli_num_rows($resultPers);
										if ($nbResultaPers>1){
											$lesPersonnes="";
											while($rowPers=mysqli_fetch_array($resultPers)){
												$lesPersonnes.="- ".$rowPers['Personne']."<br>";
											}
											
											if($LangueAffichage=="FR"){
												$text="Plusieurs personnes ont le même matricule AAA : ";
											}
											else{
												$text="Several people have the same AAA number : ";
											}
											echo "<td id='leHover' style='display: inline-block'><img width='15px' src='../../Images/attention.png'/><span>".$text."<br>".$lesPersonnes."</span></td> ";
											
										}
									?>
										</tr>
										</table>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule DirectSkill :";}else{echo "DirectSkill number :";} ?></td>
								<td width="10%">
									<table>
									<tr>
									<td>
									<input name="matriculeDSK" id="matriculeDSK" size="15" value="<?php echo $rowEtatCivil['MatriculeDSK']; ?>">
									</td>
									<?php 
										$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE MatriculeDSK<>'' AND MatriculeDSK='".$rowEtatCivil['MatriculeDSK']."' ORDER BY Personne ASC";
										$resultPers=mysqli_query($bdd,$req);
										$nbResultaPers=mysqli_num_rows($resultPers);
										if ($nbResultaPers>1){
											$lesPersonnes="";
											while($rowPers=mysqli_fetch_array($resultPers)){
												$lesPersonnes.="- ".$rowPers['Personne']."<br>";
											}
											if($LangueAffichage=="FR"){
												$text="Plusieurs personnes ont le même matricule DSK : ";
											}
											else{
												$text="Several people have the same DSK number : ";
											}
											echo "<td id='leHover'><img width='15px' src='../../Images/attention.png'/><span>".$text."<br>".$lesPersonnes."</span></td>";
										}
									?>
									</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="13%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début de contrat :<br>(pour calcul des 18 mois)";}else{echo "Contract start date :<br> (for 18 months calculation)";} ?> </td>
								<td width="10%"><input type="date" style="text-align:center;" id="dateDebutContrat18Mois" name="dateDebutContrat18Mois" size="10" value="<?php echo AfficheDateFR($rowEtatCivil['DateDebut18Mois']); ?>"></td>
								
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'ancienneté administrative";}else{echo "Date of administrative seniority";} ?> </td>
								<td width="10%"><input type="date" style="text-align:center;" id="dateAncienneteAdministrative" name="dateAncienneteAdministrative" size="10" value="<?php echo AfficheDateFR($rowEtatCivil['DateDebut1erContratAAA']); ?>"></td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule Daher : <br>(si CDI)";}else{echo "Daher number (if CDI) :";} ?></td>
								<td width="10%">
									<table>
									<tr>
									<td>
									<input name="matriculeDaher" id="matriculeDaher" size="15" value="<?php echo $rowEtatCivil['MatriculeDaher']; ?>">
									</td>
									
									<?php 
										$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE MatriculeDaher<>'' AND MatriculeDaher='".$rowEtatCivil['MatriculeDaher']."' ORDER BY Personne ASC";
										$resultPers=mysqli_query($bdd,$req);
										$nbResultaPers=mysqli_num_rows($resultPers);
										if ($nbResultaPers>1){
											$lesPersonnes="";
											while($rowPers=mysqli_fetch_array($resultPers)){
												$lesPersonnes.="- ".$rowPers['Personne']."<br>";
											}
											
											if($LangueAffichage=="FR"){
												$text="Plusieurs personnes ont le même matricule Daher : ";
											}
											else{
												$text="Several people have the same Daher number : ";
											}
											echo "<td id='leHover' style='display: inline-block'><img width='15px' src='../../Images/attention.png'/><span>".$text."<br>".$lesPersonnes."</span></td> ";
											
										}
									?>
										</tr>
										</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Centre de coût :";}else{echo "Cost center :";} ?></td>
								<td width="10%">
									<input name="centreDeCout" id="centreDeCout" readonly size="20" value="<?php echo $rowEtatCivil['CentreDeCout']; ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td colspan="6" align="center">
									<div id="Ajouter">
									</div>
									<input class="Bouton" type="submit" id="btnModifierEtatCivil" name="btnModifierEtatCivil" value="<?php if($_SESSION["Langue"]=="FR"){echo "Modifier";}else{echo "Edit";} ?>" onClick="Enregistrer()">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						</table>
					</td></tr>
				</table>
			<?php
				}
			?>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Liste des personnes : ";}else{echo "List of people : ";} 
				?>
			</td>
		</tr>
		<tr>
			<td width="15%" valign="top">
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
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."&Id_Personne=".$row['Id']."#selection'>".$row['Personne']."</a></td></tr>";
						}
					}
					echo "</table>";
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				if($personne>0){
			?>
				<table width="100%" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td bgcolor="#d597b3" colspan="20" style="height:20px;border-spacing:0;text-align:center;color:#000000;valign:top;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "CONTRATS";}else{echo "CONTRACTS";}?></td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td colspan="20" align="center">
							<input class="Bouton" type="button" id="nouveauContrat" name="nouveauContrat" value="<?php if($_SESSION["Langue"]=="FR"){echo "Nouveau contrat";}else{echo "New contract";} ?>" onClick="NouveauContrat('<?php echo $personne; ?>','Liste_ContratHistorique')">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr><td colspan="20" align="center">
						<table width="50%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td width="10%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de début :";}else{echo "Start date :";} 
									
									$signeDateDebut=$_SESSION['FiltreRHContrat_SigneDateDebut'];
									if($_POST){$signeDateDebut=$_POST['signeDateDebut'];}
									$_SESSION['FiltreRHContrat_SigneDateDebut']=$signeDateDebut;
									?>
									<select id="signeDateDebut" name="signeDateDebut" onchange="submit();">
										<option value='=' <?php if($signeDateDebut=="="){echo "selected";} ?>>=</option>
										<option value='<' <?php if($signeDateDebut=="<"){echo "selected";} ?>><</option>
										<option value='>' <?php if($signeDateDebut==">"){echo "selected";} ?>>></option>
									</select>
									<?php 
									$dateDebut=$_SESSION['FiltreRHContrat_DateDebut'];
									if($_POST){$dateDebut=$_POST['dateDebut'];}
									$_SESSION['FiltreRHContrat_DateDebut']=$dateDebut;
									
									?>
									<input id="dateDebut" name="dateDebut" type="date" value="<?php echo $dateDebut; ?>" size="10"/>&nbsp;&nbsp;
								</td>
								<td width="10%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} 
									
									$signeDateFin=$_SESSION['FiltreRHContrat_SigneDateFin'];
									if($_POST){$signeDateFin=$_POST['signeDateFin'];}
									$_SESSION['FiltreRHContrat_SigneDateFin']=$signeDateFin;
									?>
									<select id="signeDateFin" name="signeDateFin" onchange="submit();">
										<option value='=' <?php if($signeDateFin=="="){echo "selected";} ?>>=</option>
										<option value='<' <?php if($signeDateFin=="<"){echo "selected";} ?>><</option>
										<option value='>' <?php if($signeDateFin==">"){echo "selected";} ?>>></option>
									</select>
									<?php 
									$dateFin=$_SESSION['FiltreRHContrat_DateFin'];
									if($_POST){$dateFin=$_POST['dateFin'];}
									$_SESSION['FiltreRHContrat_DateFin']=$dateFin;
									
									?>
									<input id="dateFin" name="dateFin" type="date" value="<?php echo $dateFin; ?>" size="10"/>&nbsp;&nbsp;
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
						</table>
					</td></tr>
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "N° contrat";}else{echo "Contract number";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Titre";}else{echo "Title";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Agence intérim";}else{echo "Interim agency";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Coeff";}else{echo "Coefficient";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin";}else{echo "End date";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Mensuel";}else{echo "Monthly";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux horaire";}else{echo "Hourly rate";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail";}else{echo "Work time";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?></td>
						<td class="EnTeteTableauCompetences" width="1%"></td>
						<td class="EnTeteTableauCompetences" width="1%"></td>
						<td class="EnTeteTableauCompetences" width="1%"></td>
						<td class="EnTeteTableauCompetences" width="1%"></td>
					</tr>
					<?php
					$req="SELECT 
							Id,TypeDocument,Coeff,DateDebut,DateFin,Titre,
							(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
							(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
							(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContratEN,
							(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
							(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
							(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS MetierEN,
							(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
							IF(DateSignatureSiege=0,1,
								IF(DateSignatureSalarie=0,2,
									IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
										IF(DateRetourSigneAuSiege>'0001-01-01',4,
										0
										)
									)
								)
							) AS Etat,
							SalaireBrut,TauxHoraire
						FROM rh_personne_contrat
						WHERE Suppr=0 
						AND Id_Personne=".$personne."
						AND Id_ContratInitial=0 ";
					if($_SESSION['FiltreRHContrat_DateDebut']<>""){
						$req.=" AND DateDebut ".$_SESSION['FiltreRHContrat_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateDebut'])."' ";
					}
					if($_SESSION['FiltreRHContrat_DateFin']<>""){
						if($_SESSION['FiltreRHContrat_SigneDateFin']=="<"){
							$req.=" AND DateFin ".$_SESSION['FiltreRHContrat_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateFin'])."' 
							AND DateFin>'0001-01-01'
							";
						}
						elseif($_SESSION['FiltreRHContrat_SigneDateFin']==">"){
							$req.=" AND (DateFin ".$_SESSION['FiltreRHContrat_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateFin'])."' 
							OR DateFin<='0001-01-01' )
							";
						}
						elseif($_SESSION['FiltreRHContrat_SigneDateFin']=="="){
							$req.=" AND DateFin ".$_SESSION['FiltreRHContrat_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateFin'])."' 
							";
						}
					}
					$req.="ORDER BY DateDebut DESC
					";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					
					$couleur="#EEEEEE";
					if($nbResulta>0){
						while($row=mysqli_fetch_array($result))
						{
							if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
							else{$couleur="#FFFFFF";}
							
							if(IdContratEC($personne)==$row['Id']){
								$couleurLigne="#c6ff8d";
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}
							}
							else{
								$couleurLigne=$couleur;
							}
					?>
							<tr bgcolor="<?php echo $couleurLigne;?>">
								<td style="border-bottom:1px dotted #976fa1;" align="left">&nbsp;<a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>,<?php echo $personne; ?>,'Liste_ContratHistorique')"><?php echo stripslashes($row['Id']);?></a></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($row['Titre']);?></td>
								<td style="border-bottom:1px dotted #976fa1;"> <?php if($row['TypeDocument']=="Nouveau"){if($_SESSION["Langue"]=="FR"){echo "Nouveau";}else{echo "New";}}elseif($row['TypeDocument']=="Avenant"){if($_SESSION["Langue"]=="FR"){echo "Avenant";}else{echo "Amendment";}}else{if($_SESSION["Langue"]=="FR"){echo "ODM";}else{echo "Mission order";}}?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php if($_SESSION["Langue"]=="FR"){echo $row['TypeContrat'];}else{echo $row['TypeContratEN'];} ?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($row['AgenceInterim']);?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php if($_SESSION["Langue"]=="FR"){echo $row['Metier'];}else{echo $row['MetierEN'];} ?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($row['Coeff']);?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
								<td style="border-bottom:1px dotted #976fa1;">
									<?php 
										if($row['SalaireBrut']>0){
											echo stripslashes($row['SalaireBrut']);
										} 
									?>
								</td>
								<td style="border-bottom:1px dotted #976fa1;">
									<?php 
										if($row['TauxHoraire']>0){
											echo stripslashes($row['TauxHoraire']);
										} 
									?>
								</td>
								<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($row['TempsTravail']);?></td>
								<td style="border-bottom:1px dotted #976fa1;">
								<?php 
									if($row['Etat']==1){if($_SESSION["Langue"]=="FR"){echo "Attente signature siège";}else{echo "Waiting signature head office";}}
									elseif($row['Etat']==2){if($_SESSION["Langue"]=="FR"){echo "Signature siège et attente signature salarié";}else{echo "Signature head office and waiting signature employee";}}
									elseif($row['Etat']==3){if($_SESSION["Langue"]=="FR"){echo "Signature salarié OK";}else{echo "Employee Signature OK";}}
									elseif($row['Etat']==4){if($_SESSION["Langue"]=="FR"){echo "Retour signé au siège (clôturé)";}else{echo "Signed return to head office (closed)";}}
								?>
								</td>
								<td style="border-bottom:1px dotted #976fa1;" align="center">
									<input class="Bouton" type="button" id="nouvelAvenant" name="nouvelAvenant" value="<?php if($_SESSION["Langue"]=="FR"){echo "AV";}else{echo "AM";} ?>" onClick="NouvelAvenant(<?php echo $personne; ?>,<?php echo $row['Id']; ?>,'Liste_ContratHistorique')">
								</td>
								<td style="border-bottom:1px dotted #976fa1;" align="center">
									<input class="Bouton" type="button" id="nouveauODM" name="nouveauODM" value="<?php if($_SESSION["Langue"]=="FR"){echo "ODM";}else{echo "MO";} ?>" onClick="NouveauODM(<?php echo $personne; ?>,<?php echo $row['Id']; ?>,'Liste_ContratHistorique')">
								</td>
								<td style="border-bottom:1px dotted #976fa1;" align="center">
									<a href="javascript:ContratExcel(<?php echo $row['Id'];?>)">
										<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
									</a>
								</td>
								<td style="border-bottom:1px dotted #976fa1;" align="center">
									<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>',<?php echo $personne; ?>,'Liste_ContratHistorique');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
								</td>
							</tr>
						<?php
							//Parcours des avenants et ODM
							$req="SELECT 
									Id,TypeDocument,Coeff,DateDebut,DateFin,Titre,
									(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
									(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
									(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContratEN,
									(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
									(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
									(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS MetierEN,
									(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
									IF(DateSignatureSiege=0,1,
										IF(DateSignatureSalarie=0,2,
											IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
												IF(DateRetourSigneAuSiege>'0001-01-01',4,
												0
												)
											)
										)
									) AS Etat,
									SalaireBrut,TauxHoraire
								FROM rh_personne_contrat
								WHERE Suppr=0 
								AND Id_ContratInitial=".$row['Id']." ";
							if($_SESSION['FiltreRHContrat_DateDebut']<>""){
								$req.=" AND DateDebut ".$_SESSION['FiltreRHContrat_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRHContrat_DateFin']<>""){
								if($_SESSION['FiltreRHContrat_SigneDateFin']=="<"){
									$req.=" AND DateFin ".$_SESSION['FiltreRHContrat_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateFin'])."' 
									AND DateFin>'0001-01-01'
									";
								}
								elseif($_SESSION['FiltreRHContrat_SigneDateFin']==">"){
									$req.=" AND (DateFin ".$_SESSION['FiltreRHContrat_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateFin'])."' 
									OR DateFin<='0001-01-01' )
									";
								}
								elseif($_SESSION['FiltreRHContrat_SigneDateFin']=="="){
									$req.=" AND DateFin ".$_SESSION['FiltreRHContrat_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContrat_DateFin'])."' 
									";
								}
							}
							$req.=" ORDER BY Id DESC
							";
							$resultPlus=mysqli_query($bdd,$req);
							$nbResultaPlus=mysqli_num_rows($resultPlus);
							
							if($nbResultaPlus>0){
								while($rowPlus=mysqli_fetch_array($resultPlus))
								{
										
									if($rowPlus['TypeDocument']=="ODM"){$fonction="OuvreFenetreModifODM";}
									else{$fonction="OuvreFenetreModif";}
									
									if(IdContratEC($personne)==$rowPlus['Id']){
										$couleurLigne="#c6ff8d";
									}
									else{
										$couleurLigne=$couleur;
									}
									if($rowPlus['TypeDocument']=="ODM"){
										
										if(IdODMEC($personne)==$rowPlus['Id']){
											$couleurLigne="#d3a6fc";
										}
										else{
											$couleurLigne=$couleur;
										}
									}
								?>
									<tr bgcolor="<?php echo $couleurLigne;?>">
										<td style="border-bottom:1px dotted #976fa1;" align="left">&nbsp;&nbsp;&nbsp;<img src="../../Images/flecheAngle.png" style="width:15px;" border="0" title="fleche" alt="fleche">&nbsp;&nbsp;<a style="color:#3e65fa;" href="javascript:<?php echo $fonction;?>(<?php echo $Menu; ?>,<?php echo $rowPlus['Id']; ?>,<?php echo $personne; ?>,'Liste_ContratHistorique')"><?php echo stripslashes($rowPlus['Id']);?></a></td>
										<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($rowPlus['Titre']);?></td>
										<td style="border-bottom:1px dotted #976fa1;">
											
											<?php if($rowPlus['TypeDocument']=="Avenant"){if($_SESSION["Langue"]=="FR"){echo "Avenant";}else{echo "Amendment";}}else{if($_SESSION["Langue"]=="FR"){echo "ODM";}else{echo "Mission order";}} ?>
											
										</td>
										<td style="border-bottom:1px dotted #976fa1;"><?php if($_SESSION["Langue"]=="FR"){echo $rowPlus['TypeContrat'];}else{echo $rowPlus['TypeContratEN'];} ?></td>
										<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($rowPlus['AgenceInterim']);?></td>
										<td style="border-bottom:1px dotted #976fa1;"><?php if($_SESSION["Langue"]=="FR"){echo $rowPlus['Metier'];}else{echo $rowPlus['MetierEN'];} ?></td>
										<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($rowPlus['Coeff']);?></td>
										<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($rowPlus['DateDebut']);?></td>
										<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($rowPlus['DateFin']);?></td>
										<td style="border-bottom:1px dotted #976fa1;">
											<?php 
											if($rowPlus['TypeDocument']=="Avenant"){
												if($rowPlus['SalaireBrut']>0){
													echo stripslashes($rowPlus['SalaireBrut']);
												} 
												
											}
											?>
											
										</td>
										<td style="border-bottom:1px dotted #976fa1;">
											<?php 
											if($rowPlus['TypeDocument']=="Avenant"){
												
												if($rowPlus['TauxHoraire']>0){
													echo stripslashes($rowPlus['TauxHoraire']);
												}
												
											}
											?>
										</td>
										<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($rowPlus['TempsTravail']);?></td>
										<td style="border-bottom:1px dotted #976fa1;">
										<?php 
											if($rowPlus['Etat']==1){if($_SESSION["Langue"]=="FR"){echo "Attente signature siège";}else{echo "Waiting signature head office";}}
											elseif($rowPlus['Etat']==2){if($_SESSION["Langue"]=="FR"){echo "Signature siège et attente signature salarié";}else{echo "Signature head office and waiting signature employee";}}
											elseif($rowPlus['Etat']==3){if($_SESSION["Langue"]=="FR"){echo "Signature salarié OK";}else{echo "Employee Signature OK";}}
											elseif($rowPlus['Etat']==4){if($_SESSION["Langue"]=="FR"){echo "Retour signé au siège (clôturé)";}else{echo "Signed return to head office (closed)";}}
										?>
										</td>
										<td style="border-bottom:1px dotted #976fa1;" align="center">
											<?php if($rowPlus['TypeDocument']=="Avenant"){?>
											<input class="Bouton" type="button" id="nouvelAvenant" name="nouvelAvenant" value="<?php if($_SESSION["Langue"]=="FR"){echo "AV";}else{echo "AM";} ?>" onClick="NouvelAvenant(<?php echo $personne; ?>,<?php echo $rowPlus['Id']; ?>,'Liste_ContratHistorique')">
											<?php }?>
										</td>
										<td style="border-bottom:1px dotted #976fa1;" align="center">
											<input class="Bouton" type="button" id="nouveauODM" name="nouveauODM" value="<?php if($_SESSION["Langue"]=="FR"){echo "ODM";}else{echo "MO";} ?>" onClick="NouveauODM(<?php echo $personne; ?>,<?php echo $rowPlus['Id']; ?>,'Liste_ContratHistorique')">
										</td>
										<td style="border-bottom:1px dotted #976fa1;" align="center">
											<?php
												if($rowPlus['TypeDocument']=="ODM"){
											?>
												<a href="javascript:ODMExcel(<?php echo $rowPlus['Id'];?>)">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											<?php
												}
												else{
											?>
												<a href="javascript:ContratExcel(<?php echo $rowPlus['Id'];?>)">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											<?php		
												}
											?>
										</td>
										<?php 
											if($rowPlus['TypeDocument']=="ODM"){$fonction="OuvreFenetreSupprODM";}
											else{$fonction="OuvreFenetreSuppr";}
										?>
										<td style="border-bottom:1px dotted #976fa1;" align="center">
											<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){<?php echo $fonction;?>('<?php echo $Menu; ?>','<?php echo $rowPlus['Id']; ?>',<?php echo $personne; ?>,'Liste_ContratHistorique');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
										</td>
									</tr>
								<?php			
								}
							}
						}	
					}
					?>
				</table>
			<?php
				}
			?>
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