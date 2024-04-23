<?php
require("../../Menu.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==13){
$personne=0;
if(isset($_GET['Id_Personne'])){$personne=$_GET['Id_Personne'];}
elseif(isset($_POST['Id_Personne'])){$personne=$_POST['Id_Personne'];}

$bExiste=false;

?>

<form class="test" action="EtatCivil.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $personne; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#7beeef;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Etat civil";}else{echo "Civil status";}
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
					$req="SELECT Id,Nom,Prenom,Sexe,Nationalite,Date_Naissance,Ville_Naissance,Num_SS,Adresse,CP,Ville,TelephoneFixe,Email,DateDebut18Mois,
						Type_TitreTravailEtranger,Num_TitreTravailEtranger,DateAncienneteCDI,MatriculeAAA,MatriculeDSK,MatriculeCEGID,DateDebut1erContratAAA
						FROM new_rh_etatcivil 
						WHERE Id=".$personne." ";
					$result=mysqli_query($bdd,$req);
					$rowEtatCivil=mysqli_fetch_array($result);
					
			?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr><td>
						<table width="95%" align="center" cellpadding="0" cellspacing="0">
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
									<?php echo stripslashes($rowEtatCivil['Nom']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom :";}else{echo "First name :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Prenom']); ?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Sexe :";}else{echo "Gender :";} ?></td>
								<td width="10%">
									<?php echo $rowEtatCivil['Sexe']; ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nationalité :";}else{echo "Nationality :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Nationalite']); ?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance :";}else{echo "Birth date :";} ?> </td>
								<td width="10%">
									<?php echo AfficheDateJJ_MM_AAAA($rowEtatCivil['Date_Naissance']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu de naissance :";}else{echo "Place of birth :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Ville_Naissance']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° sécurité social :";}else{echo "Social security number :";} ?></td>
								<td width="10%">
									<?php echo $rowEtatCivil['Num_SS']; ?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Titre de séjour :";}else{echo "Title of stay :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Type_TitreTravailEtranger']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° titre de séjour :";}else{echo "Number of residence permit :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Num_TitreTravailEtranger']); ?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse :";}else{echo "Address :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Adresse']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "CP :";}else{echo "PC :";} ?></td>
								<td width="10%">
									<?php echo $rowEtatCivil['CP']; ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ville :";}else{echo "City :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($rowEtatCivil['Ville']); ?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° téléphone personnel :";}else{echo "Personal telephone number :";} ?></td>
								<td width="10%">
									<?php echo $rowEtatCivil['TelephoneFixe']; ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Email personnel :";}else{echo "Personal email :";} ?></td>
								<td width="10%">
									<?php echo $rowEtatCivil['Email']; ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule CEGID :";}else{echo "CEGID number :";} ?></td>
								<td width="10%">
									<?php echo $rowEtatCivil['MatriculeCEGID']; ?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'ancienneté (si CDI) :";}else{echo "Date of seniority (if CDI):";} ?> </td>
								<td width="10%">
									<?php echo AfficheDateJJ_MM_AAAA($rowEtatCivil['DateAncienneteCDI']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule AAA Paris : <br>(si CDI)";}else{echo "AAA Paris number (if CDI) :";} ?></td>
								<td width="10%">
									<table>
									<tr>
									<td>
									<?php echo $rowEtatCivil['MatriculeAAA']; ?>
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
										<?php echo $rowEtatCivil['MatriculeDSK']; ?>
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
								<td width="10%">
									<?php echo AfficheDateJJ_MM_AAAA($rowEtatCivil['DateDebut18Mois']); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'ancienneté administrative";}else{echo "Date of administrative seniority";} ?> </td>
								<td width="10%"><?php echo AfficheDateJJ_MM_AAAA($rowEtatCivil['DateDebut1erContratAAA']); ?>
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
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/EtatCivil.php?Menu=".$Menu."&Id_Personne=".$row['Id']."#selection'>".$row['Personne']."</a></td></tr>";
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
}
?>
	
</body>
</html>