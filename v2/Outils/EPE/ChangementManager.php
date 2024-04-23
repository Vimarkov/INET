<?php
require("../../Menu.php");

$personne=0;
if(isset($_GET['Id_Personne'])){$personne=$_GET['Id_Personne'];}
elseif(isset($_POST['Id_Personne'])){$personne=$_POST['Id_Personne'];}

$bExiste=false;
if($_POST){
	if(isset($_POST['btnModifier'])){
		$req="UPDATE epe_personne_prestation SET Suppr=1 WHERE Id_Personne=".$personne." AND Id_Manager<>0 AND Id_Prestation=".$_POST['Id_Prestation']." AND Id_Pole=".$_POST['Id_Pole']." AND Annee=".$_SESSION['FiltreEPEChangement_Annee']." ";
		$resultUpdt=mysqli_query($bdd,$req);
		$req="INSERT INTO epe_personne_prestation (Id_Personne,Id_Prestation,Id_Pole,Id_Manager,Annee,Id_RH) VALUES (".$personne.",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",".$_POST['manager'].",".$_SESSION['FiltreEPEAffectation_Annee'].",".$_SESSION['Id_Personne'].") ";
		$resultinsert=mysqli_query($bdd,$req);
	}
}
function Titre1($Libelle,$Lien,$Selected){
		$tiret="";
		if($Selected==true){$tiret="border-bottom:4px solid white;";}
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#5c4165';\" onmouseout=\"this.style.color='#5c4165';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}

?>

<form class="test" action="ChangementManager.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $personne; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#b13095;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Changer le manager";}else{echo "Change the manager";}
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
				<?php
				$annee=$_SESSION['FiltreEPEChangement_Annee'];
				if($_POST){$annee=$_POST['annee'];}
				if($annee==""){$annee=date("Y");}
				$_SESSION['FiltreEPEChangement_Annee']=$annee;
				?>
				<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/><br><br>
				
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher : ";}else{echo "Search : ";} 
				if($_POST){$_SESSION['FiltreEPEChangement_Recherche']=$_POST['recherche'];}
				?>
				<input id="recherche" name="recherche" type="texte" value="<?php echo $_SESSION['FiltreEPEChangement_Recherche']; ?>" size="25"/>&nbsp;&nbsp;&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="85%" rowspan="4" valign="top">
			<?php
				if($personne>0){
					$req="SELECT Id,Nom,Prenom,Sexe,Nationalite,Date_Naissance,Ville_Naissance,Num_SS,Adresse,CP,Ville,TelephoneMobil,Email,DateDebut18Mois,
						DateAncienneteCDI,MatriculeAAA,MatriculeDSK,MatriculeCEGID,DateDebut1erContratAAA,
						Contrat,Cadre,MetierPaie
						FROM new_rh_etatcivil 
						WHERE Id=".$personne." ";
					$result=mysqli_query($bdd,$req);
					$rowEtatCivil=mysqli_fetch_array($result);
					
					$Id_Prestation=0;
					$Id_Pole=0;
					$Id_Plateforme=0;
					
					$req="SELECT Id_Prestation,Id_Pole 
						FROM new_competences_personne_prestation
						WHERE Id_Personne=".$rowEtatCivil['Id']." 
						AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
						ORDER BY Date_Fin DESC, Date_Debut DESC
						";
					$resultch=mysqli_query($bdd,$req);
					$nb=mysqli_num_rows($resultch);
					$Id_PrestationPole="0_0";
					if($nb>0){
						$rowMouv=mysqli_fetch_array($resultch);
						$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
					}
					$TableauPrestationPole=explode("_",$Id_PrestationPole);
					$Id_Prestation=$TableauPrestationPole[0];
					$Id_Pole=$TableauPrestationPole[1];
					
					$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
					$ResultPlat=mysqli_query($bdd,$req);
					$NbPlat=mysqli_num_rows($ResultPlat);
					if($NbPlat>0){
						$RowPlat=mysqli_fetch_array($ResultPlat);
						$Id_Plateforme=$RowPlat['Id_Plateforme'];
					}
					
					$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
					TypeEntretien AS TypeE,
					IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
					epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee'].")>0,
					(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager')))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']."),
					'A faire')
					AS Etat,
					(SELECT Id_Evaluateur
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']." LIMIT 1) AS Id_Manager,
					(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']." LIMIT 1) AS Manager,
					(SELECT Id
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']." LIMIT 1) AS Id_PersonneEPE,
					(SELECT Id_Plateforme
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']." LIMIT 1) AS Id_Plateforme,
					(SELECT LectureRH
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']." LIMIT 1) AS LectureRH,
					(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEChangement_Annee']." LIMIT 1) AS PrestaPole
					FROM new_rh_etatcivil
					RIGHT JOIN epe_personne_datebutoir 
					ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
					WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
					AND MetierPaie<>'' AND Cadre IN (0,1) 
					AND new_rh_etatcivil.Id=".$rowEtatCivil['Id']."
					AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEChangement_Annee']." ";
					$ResultNb=mysqli_query($bdd,$reqNb);
					$leNb=mysqli_num_rows($ResultNb);
		
					$rowNb=mysqli_fetch_array($ResultNb);
					
					if($rowNb['Etat']=="A faire"){
						if($nb>1){
							$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$rowEtatCivil['Id']." AND Id_Manager=0 AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEChangement_Annee']." ";
							$ResultlaPresta=mysqli_query($bdd,$req);
							$NblaPresta=mysqli_num_rows($ResultlaPresta);
							if($NblaPresta>0){
								$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
								$Id_Prestation=$RowlaPresta['Id_Prestation'];
								$Id_Pole=$RowlaPresta['Id_Pole'];
								$Id_Plateforme=$RowlaPresta['Id_Plateforme'];
							}
						}
					}
					else{
						if($rowNb['PrestaPole']<>""){
							$tab = explode("_",$rowNb['PrestaPole']);
							$Id_Prestation=$tab[0];
							$Id_Pole=$tab[1];
							$Id_Plateforme=$rowNb['Id_Plateforme'];
						}
					}
					
					$Presta="";
					$Plateforme="";
					$req="SELECT LEFT(Libelle,7) AS Prestation, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
					$ResultPresta=mysqli_query($bdd,$req);
					$NbPrest=mysqli_num_rows($ResultPresta);
					if($NbPrest>0){
						$RowPresta=mysqli_fetch_array($ResultPresta);
						$Presta=$RowPresta['Prestation'];
						$Plateforme=$RowPresta['Plateforme'];
					}
					
					$Pole="";
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
					$ResultPole=mysqli_query($bdd,$req);
					$NbPole=mysqli_num_rows($ResultPole);
					if($NbPole>0){
						$RowPole=mysqli_fetch_array($ResultPole);
						$Pole=$RowPole['Libelle'];
					}
					
					if($Pole<>""){$Presta.=" - ".$Pole;}
					
					$Manager="";
					$Id_Manager=0;
					if($rowNb['Etat']=="A faire"){
						$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$rowEtatCivil['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEChangement_Annee']." ";
						$ResultlaPresta=mysqli_query($bdd,$req);
						$NblaPresta=mysqli_num_rows($ResultlaPresta);
						if($NblaPresta>0){
							$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
							$Id_Manager=$RowlaPresta['Id_Manager'];
							$Manager=$RowlaPresta['Manager'];
						}
						else{
							$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$rowEtatCivil['Id']."
									ORDER BY Backup ";
							$ResultManager2=mysqli_query($bdd,$req);
							$NbManager2=mysqli_num_rows($ResultManager2);
							if($NbManager2>0){
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurProjet."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									ORDER BY Backup ";
								$ResultManager=mysqli_query($bdd,$req);
								$NbManager=mysqli_num_rows($ResultManager);
								if($NbManager>0){
									$RowManager=mysqli_fetch_array($ResultManager);
									$Manager=$RowManager['Personne'];
									$Id_Manager=$RowManager['Id'];
								}
							}
							else{
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteChefEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$rowEtatCivil['Id']."
									ORDER BY Backup ";
								$ResultManager2=mysqli_query($bdd,$req);
								$NbManager2=mysqli_num_rows($ResultManager2);
								if($NbManager2>0){
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
										$Id_Manager=$RowManager['Id'];
									}
								}
								else{
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteChefEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
										$Id_Manager=$RowManager['Id'];
									}
								}
							}
						}
					}
					else{
						$Manager=$rowNb['Manager'];
						$Id_Manager=$rowNb['Id_Manager'];
					}
					
			?>
				<table width="100%" cellpadding="0" cellspacing="0" valign="top">
					<tr><td>
						<table width="95%" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="6">
								<table width="100%">
									<tr>
										<td width="98%" bgcolor="#d597b3" style="height:20px;border-spacing:0;text-align:center;color:#000000;valign:top;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "ETAT CIVIL";}else{echo "CIVIL STATUS";}?></td>
										<td width="2%"><a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="window.location='ChangementManager.php?Id_Personne=<?php echo $personne; ?>';"><img src="../../Images/refresh.png" style="width:18px;" border="0" title="Refresh" alt="Refresh"></a></td>
									</tr>
								</table>
								</td>
							</tr>
							<tr><td height="8"></td></tr>
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
							<tr><td height="8"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($Plateforme); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($Presta); ?>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($Pole); ?>
								</td>
							</tr>
							<tr><td height="8"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Manager actuel :";}else{echo "Current manager :";} ?></td>
								<td width="10%">
									<?php echo stripslashes($Manager); ?>
								</td>
							</tr>
							<tr><td height="15"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Choix du manager :";}else{echo "Manager's choice :";} ?></td>
							</tr>
							<?php 
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										AND Id_Personne=".$rowEtatCivil['Id']."
										ORDER BY Backup ";
								$ResultManager2=mysqli_query($bdd,$req);
								$NbManager2=mysqli_num_rows($ResultManager2);
								if($NbManager2>0){
									$reqListe="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteCoordinateurProjet."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										AND Id_Personne<>0
										ORDER BY Personne ";
								}
								else{
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteChefEquipe."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										AND Id_Personne=".$rowEtatCivil['Id']."
										ORDER BY Backup ";
									$ResultManager2=mysqli_query($bdd,$req);
									$NbManager2=mysqli_num_rows($ResultManager2);
									if($NbManager2>0){
										$reqListe="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
											FROM new_competences_personne_poste_prestation 
											LEFT JOIN new_rh_etatcivil
											ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
											WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
											AND Id_Prestation=".$Id_Prestation."
											AND Id_Pole=".$Id_Pole."
											AND Id_Personne<>0
											ORDER BY Personne ";
									}
									else{
										$reqListe="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteChefEquipe."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										AND Id_Personne<>0
										ORDER BY Personne ";
									}
								}

								$ResultManager=mysqli_query($bdd,$reqListe);
								$NbManager=mysqli_num_rows($ResultManager);
								if($NbManager>=1){
									while($rowM=mysqli_fetch_array($ResultManager))
									{
										$selected="";
										if($Id_Manager==$rowM['Id']){$selected="checked";}
							?>
									<tr>
										<td width="10%"><input type="radio" name="manager" <?php echo $selected; ?> value="<?php echo $rowM['Id']; ?>" /><?php echo $rowM['Personne']; ?></td>
									</tr>
							<?php
									}
								}
								else{
							?>
									<tr>
										<td width="10%" class="Libelle"><?php echo stripslashes($Manager); ?></td>
									</tr>
							<?php		
								}
							?>
							<input type="hidden" name="Id_Prestation" value="<?php echo $Id_Prestation;?>" />
							<input type="hidden" name="Id_Pole" value="<?php echo $Id_Pole;?>" />
							<tr><td height="4"></td></tr>
							<tr>
								<td colspan="6" align="center">
									<div id="Ajouter">
									</div>
									<?php 
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
											FROM new_competences_personne_poste_prestation 
											LEFT JOIN new_rh_etatcivil
											ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
											WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.")
											AND Id_Prestation=".$Id_Prestation."
											AND Id_Pole=".$Id_Pole."
											AND Id_Personne=".$_SESSION['Id_Personne']." ";
									$ResultManager2=mysqli_query($bdd,$req);
									$NbManager3=mysqli_num_rows($ResultManager2);

									if($NbManager>=1 && ($NbManager3>0 || DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)))){ ?>
									<input class="Bouton" type="submit" id="btnModifier" name="btnModifier" value="<?php if($_SESSION["Langue"]=="FR"){echo "Modifier";}else{echo "Edit";} ?>" onClick="Enregistrer()">
									<?php } ?>
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
					if($_SESSION['FiltreEPEChangement_Recherche']==""){
						$requete.="Id=0 ";
					}
					else{
						$requete.="CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) LIKE \"%".$_SESSION['FiltreEPEChangement_Recherche']."%\" ";
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
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/ChangementManager.php?Id_Personne=".$row['Id']."#selection'>".$row['Personne']."</a></td></tr>";
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