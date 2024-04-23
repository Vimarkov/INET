<?php
require("../../Menu.php");

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
					Contrat='".$_POST['contrat']."',
					Cadre=".$_POST['cadre'].",
					MetierPaie='".addslashes($_POST['metierPaie'])."',
					Type_TitreTravailEtranger='".addslashes($_POST['titreSejour'])."',
					Num_TitreTravailEtranger='".addslashes($_POST['numTitreSejour'])."',
					DateAncienneteCDI='".TrsfDate_($_POST['dateAnciennete'])."',
					DateDebut1erContratAAA='".TrsfDate_($_POST['dateAncienneteAdministrative'])."',
					DateDebut18Mois='".TrsfDate_($_POST['dateDebutContrat18Mois'])."',
					MatriculeAAA='".addslashes($_POST['matriculeAAA'])."',
					MatriculeDSK='".addslashes($_POST['matriculeDSK'])."',
					MatriculeCEGID='".addslashes($_POST['matriculeCEGID'])."'
				WHERE Id=".$personne."
				";
				$resultModif=mysqli_query($bdd,$req);
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

<form class="test" action="InformationPersonnel.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $personne; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Informations du personnel";}else{echo "Staff information";}
					?>
					</td>
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
						Type_TitreTravailEtranger,Num_TitreTravailEtranger,DateAncienneteCDI,MatriculeAAA,MatriculeDSK,MatriculeCEGID,DateDebut1erContratAAA,
						Contrat,Cadre,MetierPaie
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
										<td width="2%"><a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="window.location='InformationPersonnel.php?Id_Personne=<?php echo $personne; ?>';"><img src="../../Images/refresh.png" style="width:18px;" border="0" title="Refresh" alt="Refresh"></a></td>
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
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche :";}else{echo "Hiring date :";} ?> </td>
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
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début de contrat :<br>(pour calcul des 18 mois)";}else{echo "Contract start date :<br> (for 18 months calculation)";} ?> </td>
								<td width="10%"><input type="date" style="text-align:center;" id="dateDebutContrat18Mois" name="dateDebutContrat18Mois" size="10" value="<?php echo AfficheDateFR($rowEtatCivil['DateDebut18Mois']); ?>"></td>
								
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'ancienneté administrative";}else{echo "Date of administrative seniority";} ?> </td>
								<td width="10%"><input type="date" style="text-align:center;" id="dateAncienneteAdministrative" name="dateAncienneteAdministrative" size="10" value="<?php echo AfficheDateFR($rowEtatCivil['DateDebut1erContratAAA']); ?>"></td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Métier paie :";}else{echo "Payroll profession :";} ?></td>
								<td width="10%">
									<input name="metierPaie" id="metierPaie" size="45" value="<?php echo stripslashes($rowEtatCivil['MetierPaie']); ?>">
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?> </td>
								 <td>
									<select name="contrat">
									<?php
										$Tableau=array('','CDI','CDIC','CDIE','CDD','Intérimaire','Alternance','Stage','AFPR','Consultant');
										foreach($Tableau as $indice => $valeur)
										{
											echo "<option value='".$valeur;
											if($rowEtatCivil['Contrat']==$valeur){echo "' selected>";}else{echo "'>";}
											echo $valeur."</option>";
										}
									?>
									</select>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Cadre / Non cadre";}else{echo "Executive / Non-executive ";} ?> </td>
								 <td>
									<select name="cadre">
										<option value="-1" <?php if($rowEtatCivil['Cadre']==-1){echo "selected";}?>></option>
										<option value="0" <?php if($rowEtatCivil['Cadre']==0){echo "selected";}?>>Non cadre</option>
										<option value="1" <?php if($rowEtatCivil['Cadre']==1){echo "selected";}?>>Cadre</option>
									</select>
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
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/InformationPersonnel.php?Id_Personne=".$row['Id']."#selection'>".$row['Personne']."</a></td></tr>";
						}
					}
					echo "</table>";
					?>
				</div>
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion

?>
	
</body>
</html>