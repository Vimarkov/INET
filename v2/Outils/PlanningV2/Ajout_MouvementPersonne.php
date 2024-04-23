<?php
require("../../Menu.php");
?>
<script language="javascript" src="MouvementPersonne.js"></script>
<?php
$DateJour=date("Y-m-d");
$bEnregistrement=false;
$Message="";
if($_POST){
	if($_SESSION['Id_Personne']<>""){
		$Personne="";
		if(isset($_POST['PersonneSelect']))
		{
			$PersonneSelect = $_POST['PersonneSelect'];
			for($i=0;$i<sizeof($PersonneSelect);$i++)
			{
				if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
			}
		}
		$TabPersonne = preg_split("/[;]+/", $Personne);
		for($i=0;$i<sizeof($TabPersonne)-1;$i++){
			$Id_Plateforme=$_POST['Id_Plateforme'];
			$tabPresta=explode("_",$_POST['Id_PrestationPoleAccueil']);
			
			if($Id_Plateforme==-2){
				$req="UPDATE rh_personne_mouvement
					SET DateFin='".TrsfDate_($_POST['dateDebut'])."'
					WHERE Suppr=0
					AND EtatValidation=1
					AND CONCAT(Id_Prestation,'_',Id_Pole)='".$_POST['Id_Prestation']."_".$_POST['Id_Pole']."'
					AND Id_Personne=".$TabPersonne[$i]."
					AND DateDebut<='".TrsfDate_($_POST['dateDebut'])."'
					AND (DateFin<='0001-01-01' OR DateFin>='".TrsfDate_($_POST['dateDebut'])."')
					AND rh_personne_mouvement.EtatValidation=1  ";
				$resultatUpdate=mysqli_query($bdd,$req);
				
				if(isset($_POST['dupliquerProfil'])){
					$req="UPDATE new_competences_personne_prestation
						SET Date_Fin='".TrsfDate_($_POST['dateDebut'])."'
						WHERE CONCAT(Id_Prestation,'_',Id_Pole)='".$_POST['Id_Prestation']."_".$_POST['Id_Pole']."'
						AND Id_Personne=".$TabPersonne[$i]."
						AND Date_Debut<='".TrsfDate_($_POST['dateDebut'])."'
						AND (Date_Fin<='0001-01-01' OR Date_Fin>='".TrsfDate_($_POST['dateDebut'])."')  ";
					$resultatUpdate=mysqli_query($bdd,$req);
				}
			}
			else{
				//Vérifier si la personne n'est pas déjà affectée sur une autre prestation que la prestation preteuse à ces dates 
				//ou en cours de pret
				$req="SELECT Id 
				FROM rh_personne_mouvement 
				WHERE Suppr=0 
				AND (EtatValidation=1 OR EtatValidation=0)
				AND CONCAT(Id_Prestation,'_',Id_Pole)<>'".$_POST['Id_Prestation']."_".$_POST['Id_Pole']."'
				AND Id_Personne=".$TabPersonne[$i]."
				AND (DateDebut<='".TrsfDate_($_POST['dateFin'])."' OR '".TrsfDate_($_POST['dateFin'])."'='0001-01-01' ) 
				AND (DateFin<='0001-01-01' OR DateFin>='".TrsfDate_($_POST['dateDebut'])."')";
				$resultMouv=mysqli_query($bdd,$req);
				$NbMouv=mysqli_num_rows($resultMouv);
				if($tabPresta[0]<>0 && $tabPresta[0]<>""){
					if($NbMouv==0){
						$requete="INSERT INTO rh_personne_mouvement ";
						$requete.="(Id_PrestationDepart,Id_PoleDepart,Id_Prestation,Id_Pole,Id_Personne,DateDebut,DateFin,Id_Createur,DateCreation) VALUES ";
						$requete.="(".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",".$tabPresta[0].",".$tabPresta[1].",".$TabPersonne[$i].",'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',".$_SESSION['Id_Personne'].",'".date('Y-m-d')."')";
						$result=mysqli_query($bdd,$requete);
						
						if(isset($_POST['dupliquerProfil'])){
							$dateFin=TrsfDate_($_POST['dateFin']);
							if($dateFin<="0001-01-01"){$dateFin="2025-12-31";}
							$requete="INSERT INTO new_competences_personne_prestation ";
							$requete.="(Id_Prestation,Id_Pole,Id_Personne,Date_Debut,Date_Fin) VALUES ";
							$requete.="(".$tabPresta[0].",".$tabPresta[1].",".$TabPersonne[$i].",'".TrsfDate_($_POST['dateDebut'])."','".$dateFin."')";
							$result=mysqli_query($bdd,$requete);
							
							//QUALIPSO - GESTION DES BESOINS EN FORMATIONS AUTOMATIQUEMENT CREES EN FONCTION DU METIER ET DE LA PRESTATION
							//#################################################################################################
							
							$ResultMetierPersonne=Get_LesMetiersFutur($TabPersonne[$i]);
							$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
							if($nbPersonnePrestation>0){
								while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
								{
									$Id_Metier_Personne=$Metier_Personne[0];
									$Motif="Changement de prestation";
									
									$ReqPrestationsEnCours_Personne="
										SELECT
											DISTINCT 
											Id_Prestation,
											Id_Pole
										FROM
											new_competences_personne_prestation
										WHERE
											Id_Personne=".$TabPersonne[$i]."
											AND Date_Fin >= '".date('Y-m-d')."'";
									$ResultPrestationsEnCours_Personne=mysqli_query($bdd,$ReqPrestationsEnCours_Personne);
									while($RowPrestationsEnCours_Personne=mysqli_fetch_array($ResultPrestationsEnCours_Personne))
									{
										Creer_BesoinsFormations_PersonnePrestationMetier($TabPersonne[$i], $RowPrestationsEnCours_Personne['Id_Prestation'], $RowPrestationsEnCours_Personne['Id_Pole'], $Id_Metier_Personne, $Motif,0,0, -1);
									}
								}
							}
							else{
								$ResultMetierPersonne=Get_LesMetiersNonFutur($TabPersonne[$i]);
								$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
								if($nbPersonnePrestation>0){
									while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
									{
										$Id_Metier_Personne=$Metier_Personne[0];
										$Motif="Changement de prestation";
										
										$ReqPrestationsEnCours_Personne="
											SELECT
												DISTINCT 
												Id_Prestation,
												Id_Pole
											FROM
												new_competences_personne_prestation
											WHERE
												Id_Personne=".$TabPersonne[$i]."
												AND Date_Fin >= '".date('Y-m-d')."'";
										$ResultPrestationsEnCours_Personne=mysqli_query($bdd,$ReqPrestationsEnCours_Personne);
										while($RowPrestationsEnCours_Personne=mysqli_fetch_array($ResultPrestationsEnCours_Personne))
										{
											Creer_BesoinsFormations_PersonnePrestationMetier($TabPersonne[$i], $RowPrestationsEnCours_Personne['Id_Prestation'], $RowPrestationsEnCours_Personne['Id_Pole'], $Id_Metier_Personne, $Motif,0,0,-1);
										}
									}
								}
							}
						}
						$bEnregistrement=true;
					}
					else{
						$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$TabPersonne[$i];
						$resultPers=mysqli_query($bdd,$req);
						$NbPers=mysqli_num_rows($resultPers);
						if($NbPers>0){
							$rowPers=mysqli_fetch_array($resultPers);
							if($_SESSION['Langue']=="FR"){
								$Message.="Impossible de transférer ".$rowPers['Personne']." car cette personne est déjà sur une autre prestation à ces dates<br>";
							}
							else{
								$Message.="Can not transfer ".$rowPers['Personne']." because this person is already on another service on these dates<br>";
							}
						}
					}
				}
				else{
					$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$TabPersonne[$i];
					$resultPers=mysqli_query($bdd,$req);
					$NbPers=mysqli_num_rows($resultPers);
					if($NbPers>0){
						$rowPers=mysqli_fetch_array($resultPers);
						if($_SESSION['Langue']=="FR"){
							$Message.="Impossible de transférer ".$rowPers['Personne']." car la prestation de destination n'est pas renseigné<br>";
						}
						else{
							$Message.="Can not transfer ".$rowPers['Personne']." because the destination service is not filled<br>";
						}
					}
				}
			}
		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>

<form id="formulaire" class="test" action="Ajout_MouvementPersonne.php" method="post" onsubmit=" return selectall();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#1365b6;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclarer un mouvement de personnel";}else{echo "Declaring a staff movement";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php 
		if($bEnregistrement==true){
			echo "<tr>";
			echo "<td colspan='5' align='center' bgcolor='#ff7777' style='font-weight:bold;'>";
			if($_SESSION["Langue"]=="FR"){
				echo "Mouvement de personnel créé<br>";
			}
			else{
				echo "staff movement created<br>";
			}
			echo "</td>";
			echo "</tr>
				<tr>
					<td height='5'></td>
				</tr>";
		}
		if($Message<>""){
			echo "<tr>";
			echo "<td colspan='5' align='center' bgcolor='#ff7777' style='font-weight:bold;'>";
			echo $Message;
			echo "</td>";
			echo "</tr>
				<tr>
					<td height='5'></td>
				</tr>";
		}
	?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" align="center" width="70%" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<?php
								if($_GET){$dateDebut= AfficheDateFR(date('Y-m-d'));}
								else{
									if($_POST['dateDebut']==''){$dateDebut= AfficheDateFR(date('Y-m-d'));}
									else{$dateDebut= $_POST['dateDebut'];}
								}
								
								$laDate=TrsfDate_($dateDebut);
								$dateJJJJMM=date('Y-m',strtotime($laDate."+0 month"));
								
								$date_2Mois=date('Y-m',strtotime(date('Y-m-d')."- 2 month"));
								$date_1Mois=date('Y-m',strtotime(date('Y-m-d')."- 1 month"));
								$date_10=date('Y-m-10');
								$date_Jour=date('Y-m-d');
								
								if($Menu<>4){
									if($dateJJJJMM<=$date_2Mois || ($dateJJJJMM<=$date_1Mois && $date_Jour>=$date_10)){
										$dateDebut=AfficheDateFR(date('Y-m-d'));
									}
								}
							?>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début du transfert :";}else{echo "Start date of the transfer:";} ?></td>
							<td width="30%" colspan="4"><input type="date" id="dateDebut" name="dateDebut" size="10" onchange="submit()" value="<?php echo $dateDebut; ?>"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="30%">
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();">
									<?php
										if($Menu==4){
											echo "<option value='0'>";
											if($_SESSION["Langue"]=="FR"){echo "SANS AFFECTATION";}
											else{echo "WITHOUT ASSIGNMENT";}
											echo "</option>";
											if(DroitsFormationPlateforme($TableauIdPostesRH)){
												$requeteSite="SELECT Id, Libelle
													FROM new_competences_prestation
													WHERE Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
														)
													ORDER BY Libelle ASC";
											}
										}
										else{
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE 
												Id_Plateforme IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
													)
												OR
												Id IN 
													(SELECT Id_Prestation 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
													)
												ORDER BY Libelle ASC";
										}
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option value='".$rowsite['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td width="5%" class="Libelle"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="30%">
								<select name="Id_Pole" id="Id_Pole" onchange="Recharge_Personnel();">
									<?php
										if($Menu==4){
											if(DroitsFormationPlateforme($TableauIdPostesRH)){
												$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
														FROM new_competences_pole
														LEFT JOIN new_competences_prestation
														ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
														WHERE Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
														)
														AND Actif=0
														ORDER BY new_competences_pole.Libelle ASC";
											}
										}
										else{
											$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
												FROM new_competences_pole
												LEFT JOIN new_competences_prestation
												ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
												WHERE 
												(
													new_competences_prestation.Id IN 
														(SELECT Id_Prestation 
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION["Id_Personne"]."
														AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
														)
													OR 
													Id_Plateforme IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
													)
												)
												AND Actif=0
												ORDER BY new_competences_pole.Libelle ASC";
										}
	
										$resultPole=mysqli_query($bdd,$requetePole);
										$nbPole=mysqli_num_rows($resultPole);
										if($nbPole>0){
											$i=0;
											while($rowPole=mysqli_fetch_array($resultPole)){
												echo "<option value='".$rowPole['Id']."'>";
												echo str_replace("'"," ",$rowPole['Libelle'])."</option>\n";
												 echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$rowPole[0].",".$rowPole[1].",'".$rowPole[2]."');</script>";
												 $i+=1;
											}
										}
										else{
											echo "<option value='0'></option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes :";}else{echo "People :";} ?></td>
							<td width="35%" valign="top">
								<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
								<?php
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
									rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									RIGHT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".TrsfDate_($dateDebut)."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".TrsfDate_($dateDebut)."')
									AND rh_personne_mouvement.EtatValidation=1 
									AND rh_personne_mouvement.Suppr=0

									UNION 
									
									SELECT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,0 AS Id_Prestation ,0 AS Id_Pole
									FROM new_rh_etatcivil
									WHERE (
										SELECT COUNT(Tab_RH.Id)
										FROM new_rh_etatcivil AS Tab_RH
										RIGHT JOIN rh_personne_mouvement 
										ON Tab_RH.Id=rh_personne_mouvement.Id_Personne 
										WHERE rh_personne_mouvement.DateDebut<='".TrsfDate_($dateDebut)."'
										AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".TrsfDate_($dateDebut)."')
										AND rh_personne_mouvement.EtatValidation=1 
										AND rh_personne_mouvement.Suppr=0
										AND new_rh_etatcivil.Id=Tab_RH.Id
									)=0
									AND new_rh_etatcivil.Id IN (
										SELECT DISTINCT Id_Personne
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".TrsfDate_($dateDebut)."'
										AND (DateFin>='".TrsfDate_($dateDebut)."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
									)
									ORDER BY Personne ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								$i=0;
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									if($rowpersonne['Id']<>""){
										echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
										echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."','".$rowpersonne['Id_Prestation']."','".$rowpersonne['Id_Pole']."');</script>";
										$i+=1;
									}
								}
								?>
								</select>
							</td>
							<td width="5%" class="Libelle">
								<img id="btnEnlever" name="btnEnlever" width="30px" src="../../Images/Gauche.png" style="cursor:pointer;" onclick="effacer();"/>
								<img id="btnAjouter" name="btnAjouter" width="30px" src="../../Images/Droite.png" style="cursor:pointer;" onclick="ajouter();"/> 
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées (double-clic) :";}else{echo "Selected people (double-click) :";} ?></td>
							<td width="30%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();"></select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
							<td width="10%" colspan="4">
								<select name="Id_Plateforme" id="Id_Plateforme" style="width:300px" onchange="Recharge_PrestationAccueil()">
								<option value="0"></option>
								<option value="-2"><?php if($_SESSION["Langue"]=="FR"){echo "Désaffecter";}else{echo "Decommission";} ?></option>
									<?php
										$requetePlat="SELECT Id, Libelle
											FROM new_competences_plateforme
											WHERE Id NOT IN (11,14) ";
										if($Menu==4){
											
										}
										else{
											$requetePlat.= " AND Id=1 ";
										}
										$requetePlat.= " ORDER BY Libelle";
										$resultsPlat=mysqli_query($bdd,$requetePlat);
										while($rowPlat=mysqli_fetch_array($resultsPlat))
										{
											echo "<option value='".$rowPlat['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation / pôle d'accueil :";}else{echo "Recipient site / pole :";} ?></td>
							<td width="30%" colspan="4">
									<div id="div_PrestationPoleAccueil">
									<select name="Id_PrestationPoleAccueil" id="Id_PrestationPoleAccueil" style="width:400px">
									<?php
										$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole,Id_Plateforme
											FROM new_competences_prestation
											WHERE Active=0
											AND Id NOT IN (
												SELECT Id_Prestation
												FROM new_competences_pole   
												WHERE Actif=0
											)
											UNION 
											
											SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
												new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole,new_competences_prestation.Id_Plateforme
												FROM new_competences_pole
												INNER JOIN new_competences_prestation
												ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
												AND Active=0
												AND Actif=0
											ORDER BY Libelle, LibellePole";
										$resultsite=mysqli_query($bdd,$requeteSite);
										$i=0;
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option id='".$rowsite['Id']."_".$rowsite['Id_Pole']."' value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' hidden>";
											$pole="";
											if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
											echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
											echo "<script>Liste_PrestaPoleAccueil[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
											$i++;
										}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Transfert permanent :";}else{echo "Permanent transfer :";} ?> </td>
							<td width="30%" colspan="4">
								<input type="radio" id='transfertPermanent' name='transfertPermanent' onclick="AfficheDateFin()" value="1" checked><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
								<input type="radio" id='transfertPermanent' name='transfertPermanent' onclick="AfficheDateFin()" value="0" ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="30%" colspan="4" class="Libelle">
								<input type="checkbox" id='dupliquerProfil' name='dupliquerProfil'>
								<?php if($_SESSION["Langue"]=="FR"){echo "Dupliquer l'affectation dan le profil de la personne (<img width='25px' src='../../Images/attention.png'/> Cette affectation sera créée sans validation de la prestation receveuse)";}else{echo "Duplicate the assignment in the profile of the person (<img width='25px' src='../../Images/attention.png'/> This assignment will be created without validation of the recipient service)";} ?> &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trDateFin" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin du transfert :";}else{echo "End date of the transfer:";} ?></td>
							<td width="30%" colspan="4"><input type="date" id="dateFin" name="dateFin" size="10" value=""></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	echo "<script>Recharge_Responsables();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>