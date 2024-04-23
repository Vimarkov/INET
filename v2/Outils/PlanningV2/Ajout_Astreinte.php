<?php
require("../../Menu.php");
?>
<script language="javascript" src="RapportAstreinte.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('.heures').timepicker({
			minuteStep: 1,
			template: 'modal',
			appendWidgetTo: 'body',
			showSeconds: false,
			showMeridian: false,
			defaultTime: false
		});
		Mask.newMask({ $el: $('#heureDebut11'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut21'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut31'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin11'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin21'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin31'), mask: 'HH:mm' });
		Mask.newMask({ $el: $('#heureDebut12'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut22'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut32'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin12'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin22'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin32'), mask: 'HH:mm' });
		Mask.newMask({ $el: $('#heureDebut13'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut23'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut33'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin13'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin23'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin33'), mask: 'HH:mm' });
		Mask.newMask({ $el: $('#heureDebut14'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut24'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut34'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin14'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin24'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin34'), mask: 'HH:mm' });
		Mask.newMask({ $el: $('#heureDebut15'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut25'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut35'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin15'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin25'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin35'), mask: 'HH:mm' });
		Mask.newMask({ $el: $('#heureDebut16'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut26'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut36'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin16'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin26'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin36'), mask: 'HH:mm' });
		Mask.newMask({ $el: $('#heureDebut17'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut27'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureDebut37'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin17'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin27'), mask: 'HH:mm' });Mask.newMask({ $el: $('#heureFin37'), mask: 'HH:mm' });
	});
</script>
<?php
$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
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
			for($j=1;$j<=7;$j++){
				if($_POST['dateDebut'.$j] <> ""){
					
					$prestation=0;
					$pole=0;
					if($_POST['Id_Prestation']==-1){
						$prestationPole=PrestationPole_Personne(TrsfDate($_POST['dateDebut'.$j]),$TabPersonne[$i]);
						if($prestationPole<>0){
							$tab=explode("_",$prestationPole);
							$prestation=$tab[0];
							$pole=$tab[1];
						}
					}
					else{
						$prestation=$_POST['Id_Prestation'];
						$pole=$_POST['Id_Pole'];
					}
					
					//Création du rapport d'astreinte
					$req="INSERT INTO rh_personne_rapportastreinte (Id_Personne,Id_Createur,Id_Prestation,Id_Pole,DateCreation,
							DateAstreinte,Commentaire,Intervention,
							HeureDebut1,HeureFin1,Commentaire1,
							HeureDebut2,HeureFin2,Commentaire2,
							HeureDebut3,HeureFin3,Commentaire3,
							Id_ValidateurN1,EtatN1,DateValidationN1,
							Id_ValidateurN2,EtatN2,DateValidationN2,
							Id_ValidateurRH,EtatRH,DateValidationRH,DatePriseEnCompte) 
						VALUES 
							(".$TabPersonne[$i].",".$_SESSION['Id_Personne'].",".$prestation.",".$pole.",'".$DateJour."',";
					
						$heureDebut1='00:00:00';
						if($_POST['heureDebut1'.$j]<>""){$heureDebut1=$_POST['heureDebut1'.$j];}
						$heureFin1='00:00:00';
						if($_POST['heureFin1'.$j]<>""){$heureFin1=$_POST['heureFin1'.$j];}
						
						$heureDebut2='00:00:00';
						if($_POST['heureDebut2'.$j]<>""){$heureDebut2=$_POST['heureDebut2'.$j];}
						$heureFin2='00:00:00';
						if($_POST['heureFin2'.$j]<>""){$heureFin2=$_POST['heureFin2'.$j];}
						
						$heureDebut3='00:00:00';
						if($_POST['heureDebut3'.$j]<>""){$heureDebut3=$_POST['heureDebut3'.$j];}
						$heureFin3='00:00:00';
						if($_POST['heureFin3'.$j]<>""){$heureFin3=$_POST['heureFin3'.$j];}
						
						$req.="'".TrsfDate_($_POST['dateDebut'.$j])."','".addslashes($_POST['commentaire'.$j])."',".$_POST['intervention'.$j].",
								'".$heureDebut1."','".$heureFin1."','".addslashes($_POST['commentaire1'.$j])."',
								'".$heureDebut2."','".$heureFin2."','".addslashes($_POST['commentaire2'.$j])."',
								'".$heureDebut3."','".$heureFin3."','".addslashes($_POST['commentaire3'.$j])."',";
					if($_POST['Menu']==2){
						if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$prestation,$pole) || 
							DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$prestation,$pole)){
							$req.="".$_SESSION['Id_Personne'].",1,'".$DateJour."',".$_SESSION['Id_Personne'].",1,'".$DateJour."',0,0,'0001-01-01','0001-01-01'";
						}
						elseif(DroitsPrestationPole(array($IdPosteChefEquipe),$prestation,$pole)){
							$req.="".$_SESSION['Id_Personne'].",1,'".$DateJour."',0,0,'0001-01-01',0,0,'0001-01-01','0001-01-01'";
						}
						else{
							$req.="0,0,'0001-01-01',0,0,'0001-01-01',0,0,'0001-01-01','0001-01-01'";
						}
					}
					elseif($_POST['Menu']==3){
						if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$prestation,$pole) || 
							DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$prestation,$pole)){
							$req.="".$_SESSION['Id_Personne'].",1,'".$DateJour."',".$_SESSION['Id_Personne'].",1,'".$DateJour."',0,0,'0001-01-01','0001-01-01'";
						}
						elseif(DroitsPrestationPole(array($IdPosteChefEquipe),$prestation,$pole)){
							$req.="".$_SESSION['Id_Personne'].",1,'".$DateJour."',0,0,'0001-01-01',0,0,'0001-01-01','0001-01-01'";
						}
						else{
							$req.="0,0,'0001-01-01',0,0,'0001-01-01',0,0,'0001-01-01','0001-01-01'";
						}
					}
					elseif($_POST['Menu']==4){
						$req.="".$_SESSION['Id_Personne'].",1,'".$DateJour."',".$_SESSION['Id_Personne'].",1,'".$DateJour."',".$_SESSION['Id_Personne'].",1,'".$DateJour."','".TrsfDate_($_POST['dateDebut'.$j])."'";
					}
					
					$req.=")";	
					$resultAjout=mysqli_query($bdd,$req);
					$IdCree = mysqli_insert_id($bdd);
					if($IdCree>0){
						$bEnregistrement=true;
						
						//Modification pour rajouter le montant 
						$reqRA="SELECT 
						TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
						TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
						TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3
						FROM rh_personne_rapportastreinte
						WHERE Id=".$IdCree."
						";
						$resultRA=mysqli_query($bdd,$reqRA);
						$nbResultaRA=mysqli_num_rows($resultRA);
						if($nbResultaRA>0){
							$rowRA=mysqli_fetch_array($resultRA);

							$Id_Plateforme=0;
							$reqPresta="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$prestation;
							$resultPresta=mysqli_query($bdd,$reqPresta);
							$nbResultaPresta=mysqli_num_rows($resultPresta);
							if($nbResultaPresta>0){
								$rowPresta=mysqli_fetch_array($resultPresta);
								$Id_Plateforme=$rowPresta['Id_Plateforme'];
							}
						
							$reqUpdt="UPDATE rh_personne_rapportastreinte
								SET Montant=".MontantAstreinte($Id_Plateforme,TrsfDate_($_POST['dateDebut'.$j]),$rowRA['DiffHeures1'],$rowRA['DiffHeures2'],$rowRA['DiffHeures3'])."
								WHERE Id=".$IdCree."
								";
							$resultUpdate=mysqli_query($bdd,$reqUpdt);
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

<form id="formulaire" class="test" action="Ajout_Astreinte.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#6fb543;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>"; 
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclaration d'astreinte";}else{echo "Declaration of on-call";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php if($bEnregistrement==true){ ?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php 
			if($Menu==2){
				if($_SESSION["Langue"]=="FR"){echo "Votre rapport d'astreinte a été enregistré et transmis à votre responsable.";}
				else{echo "Your on-call report has been recorded and sent to your manager.";} 
			}
			elseif($Menu==3){
				if($_SESSION["Langue"]=="FR"){echo "Votre rapport d'astreinte a été enregistré et transmis.";}
				else{echo "Your on-call report has been recorded and sent.";} 
			}
			elseif($Menu==4){
				if($_SESSION["Langue"]=="FR"){echo "Votre rapport d'astreinte a été enregistré.";}
				else{echo "Your on-call report has been recorded.";} 
			}
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
	<?php } ?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="70%" align="center" cellpadding="0" cellspacing="0">
						<?php
						?>
						<tr <?php if($Menu==2){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="30%">
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();">
									<?php
										if($Menu==4){
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
													AND Active=0
													ORDER BY Libelle ASC";
											}
										}
										elseif($Menu==3){
											if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
												$requeteSite="SELECT Id, Libelle
													FROM new_competences_prestation
													WHERE Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
														)
													AND Active=0
													ORDER BY Libelle ASC";
											}
											else{
												$requeteSite="SELECT Id, Libelle
													FROM new_competences_prestation
													WHERE Id IN 
														(SELECT Id_Prestation 
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION["Id_Personne"]."
														AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
														)
													AND Active=0
													ORDER BY Libelle ASC";
												
											}
										}
										elseif($Menu==2){
											$requeteSite="SELECT new_competences_prestation.Id, Libelle
												FROM rh_personne_mouvement
												LEFT JOIN new_competences_prestation
												ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
												WHERE Id_Personne=".$_SESSION["Id_Personne"]." 
												AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
												AND rh_personne_mouvement.EtatValidation=1 
												AND rh_personne_mouvement.Suppr=0
												LIMIT 1
												";
										}
										$resultsite=mysqli_query($bdd,$requeteSite);
										$nbSite=mysqli_num_rows($resultsite);
										if($nbSite>0){
											while($rowsite=mysqli_fetch_array($resultsite))
											{
												echo "<option value='".$rowsite['Id']."'>";
												echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
											}
										}
										else{
											echo "<option value='0'></option>";
										}
										
										if($Menu==3){
											echo "<option value='-1'>";
											if($_SESSION["Langue"]=="FR"){
												echo "Toutes</option>\n";
											}
											else{
												echo "All</option>\n";
											}
										}
									?>
								</select>
								
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="30%">
								<select name="Id_Pole" id="Id_Pole" onchange="Recharge_ResponsablesP();">
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
										elseif($Menu==3){
											if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
												$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
														FROM new_competences_pole
														LEFT JOIN new_competences_prestation
														ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
														WHERE Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
														)
														AND Actif=0
														ORDER BY new_competences_pole.Libelle ASC";
											}
											else{
												$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
													FROM new_competences_pole
													LEFT JOIN new_competences_prestation
													ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
													WHERE CONCAT(new_competences_prestation.Id,'_',new_competences_pole.Id) IN 
														(SELECT CONCAT(Id_Prestation,'_',Id_Pole)
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION["Id_Personne"]."
														AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
														)
													AND Actif=0
													ORDER BY new_competences_pole.Libelle ASC";
											}
										}
										elseif($Menu==2){
											$requetePole="SELECT new_competences_pole.Id,new_competences_pole.Id_Prestation, Libelle
												FROM rh_personne_mouvement
												LEFT JOIN new_competences_pole
												ON rh_personne_mouvement.Id_Pole=new_competences_pole.Id
												WHERE Id_Personne=".$_SESSION["Id_Personne"]." 
												AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
												AND rh_personne_mouvement.EtatValidation=1 
												AND rh_personne_mouvement.Suppr=0
												LIMIT 1
												";
										}
										$resultPole=mysqli_query($bdd,$requetePole);
										$nbPole=mysqli_num_rows($resultPole);
										if($nbPole>0){
											$i=0;
											while($rowPole=mysqli_fetch_array($resultPole)){
												echo "<option value='".$rowPole['Id']."'>";
												echo str_replace("'"," ",$rowPole['Libelle'])."</option>\n";
												 echo "<script>Liste_Pole_Prestation[".$i."] = new Array('".$rowPole[0]."','".$rowPole[1]."','".$rowPole[2]."');</script>";
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
						<tr <?php if($Menu==2){echo "style='display:none;'";} ?>><td height="4"></td></tr>
						<tr <?php if($Menu==2){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes :";}else{echo "People :";} ?></td>
							<td width="35%" valign="top">
								<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
								<?php
								$rq2="";
								if($Menu==2){
									$rq2="AND new_rh_etatcivil.Id=".$_SESSION['Id_Personne']." ";
								}
								
								$laDateFin=date('Y-m-d');
								if($Menu==4){
									$laDateFin=date("Y-m-d",strtotime(date('Y-m-d')." -3 month"));
								}
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
									rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDateFin."')
									AND rh_personne_mouvement.EtatValidation=1
									AND rh_personne_mouvement.Suppr=0									
									".$rq2."
									ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								$i=0;
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
									echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."','".$rowpersonne['Id_Prestation']."','".$rowpersonne['Id_Pole']."');</script>";
									$i+=1;
								}
								?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées (double-clic) :";}else{echo "Selected people (double-click) :";} ?></td>
							<td width="30%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();">
								<?php
								if($Menu==2){
									$rq2="AND new_rh_etatcivil.Id=".$_SESSION['Id_Personne']." ";
								
									$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
										rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
										FROM new_rh_etatcivil
										LEFT JOIN rh_personne_mouvement 
										ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
										WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
										AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
										AND rh_personne_mouvement.EtatValidation=1 
										AND rh_personne_mouvement.Suppr=0
										".$rq2."
										ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
									$resultpersonne=mysqli_query($bdd,$rq);
									while($rowpersonne=mysqli_fetch_array($resultpersonne))
									{
										echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
									}
								}
								?>
								</select>
							</td>
						</tr>
						<tr <?php if($Menu==2){echo "style='display:none;'";} ?>><td height="4"></td></tr>
						<tr <?php if($Menu==2){echo "style='display:none;'";} ?>><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateDebut1" name="dateDebut1" size="10" value="" onchange="estFerie('dateDebut1')"><div id="div_dateDebut1" style="display: inline"></div></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire1" id="commentaire1" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention1" width="55%"  colspan="4">
								<input type="radio" id='intervention1' name='intervention1' onclick="Affiche_Heure(1)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention1' name='intervention1' onclick="Affiche_Heure(1)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure1" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut11" id="heureDebut11" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin11" id="heureFin11" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure1" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire11" id="commentaire11" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure1" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut21" id="heureDebut21" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin21" id="heureFin21" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure1" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire21" id="commentaire21" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure1" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut31" id="heureDebut31" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin31" id="heureFin31" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure1" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire31" id="commentaire31" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="R2"><td colspan="4" ><input class="Bouton" type="button" name="newAstreinte" id="newAstreinte" onclick="AfficherTR('2')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter une astreinte";}else{echo "Add a penalty";} ?>" /></td></tr>
						<tr style="display:none;" class="RA2"><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="RA2">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateDebut2" name="dateDebut2" size="10" value="" onchange="estFerie('dateDebut2')"><div style="display: inline" id="div_dateDebut2"></div></td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4" ></td></tr>
						<tr style="display:none;" class="RA2">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire2" id="commentaire2" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="RA2">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention2" width="55%"  colspan="4">
								<input type="radio" id='intervention2' name='intervention2' onclick="Affiche_Heure(2)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention2' name='intervention2' onclick="Affiche_Heure(2)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure2" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut12" id="heureDebut12" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin12" id="heureFin12" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure2" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire12" id="commentaire12" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure2" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut22" id="heureDebut22" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin22" id="heureFin22" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure2" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire22" id="commentaire22" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure2" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut32" id="heureDebut32" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin32" id="heureFin32" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA2"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure2" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire32" id="commentaire32" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA2 R3"><td colspan="4" ><input class="Bouton" type="button" name="newAstreinte" id="newAstreinte" onclick="AfficherTR('3')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter une astreinte";}else{echo "Add a penalty";} ?>" /></td></tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="RA3"><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="RA3">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateDebut3" name="dateDebut3" size="10" value="" onchange="estFerie('dateDebut3')"><div style="display: inline" id="div_dateDebut3"></div></td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4" ></td></tr>
						<tr style="display:none;" class="RA3">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire3" id="commentaire3" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="RA3">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention3" width="55%"  colspan="4">
								<input type="radio" id='intervention3' name='intervention3' onclick="Affiche_Heure(3)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention3' name='intervention3' onclick="Affiche_Heure(3)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure3" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut13" id="heureDebut13" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin13" id="heureFin13" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure3" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire13" id="commentaire13" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure3" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut23" id="heureDebut23" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin23" id="heureFin23" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure3" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire23" id="commentaire23" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure3" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut33" id="heureDebut33" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin33" id="heureFin33" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA3"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure3" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire33" id="commentaire33" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA3 R4"><td colspan="4" ><input class="Bouton" type="button" name="newAstreinte" id="newAstreinte" onclick="AfficherTR('4')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter une astreinte";}else{echo "Add a penalty";} ?>" /></td></tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="RA4"><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="RA4">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateDebut4" name="dateDebut4" size="10" value="" onchange="estFerie('dateDebut4')"><div style="display: inline" id="div_dateDebut4"></div></td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4" ></td></tr>
						<tr style="display:none;" class="RA4">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire4" id="commentaire4" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="RA4">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention4" width="55%"  colspan="4">
								<input type="radio" id='intervention4' name='intervention4' onclick="Affiche_Heure(4)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention4' name='intervention4' onclick="Affiche_Heure(4)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure4" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut14" id="heureDebut14" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin14" id="heureFin14" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure4" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire14" id="commentaire14" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure4" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut24" id="heureDebut24" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin24" id="heureFin24" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure4" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire24" id="commentaire24" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure4" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut34" id="heureDebut34" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin34" id="heureFin34" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA4"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure4" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire34" id="commentaire34" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA4 R5"><td colspan="4" ><input class="Bouton" type="button" name="newAstreinte" id="newAstreinte" onclick="AfficherTR('5')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter une astreinte";}else{echo "Add a penalty";} ?>" /></td></tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="RA5"><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="RA5">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateDebut5" name="dateDebut5" size="10" value="" onchange="estFerie('dateDebut5')"><div style="display: inline" id="div_dateDebut5"></div></td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4" ></td></tr>
						<tr style="display:none;" class="RA5">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire5" id="commentaire5" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="RA5">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention5" width="55%"  colspan="4">
								<input type="radio" id='intervention5' name='intervention5' onclick="Affiche_Heure(5)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention5' name='intervention5' onclick="Affiche_Heure(5)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure5" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut15" id="heureDebut15" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin15" id="heureFin15" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure5" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire15" id="commentaire15" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure5" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut25" id="heureDebut25" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin25" id="heureFin25" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure5" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire25" id="commentaire25" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure5" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut35" id="heureDebut35" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin35" id="heureFin35" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA5"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure5" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire35" id="commentaire35" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA5 R6"><td colspan="4" ><input class="Bouton" type="button" name="newAstreinte" id="newAstreinte" onclick="AfficherTR('6')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter une astreinte";}else{echo "Add a penalty";} ?>" /></td></tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="RA6"><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="RA6">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateDebut6" name="dateDebut6" size="10" value="" onchange="estFerie('dateDebut6')"><div style="display: inline" id="div_dateDebut6"></div></td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4" ></td></tr>
						<tr style="display:none;" class="RA6">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire6" id="commentaire6" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="RA6">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention5" width="55%"  colspan="4">
								<input type="radio" id='intervention6' name='intervention6' onclick="Affiche_Heure(6)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention6' name='intervention6' onclick="Affiche_Heure(6)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure6" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut16" id="heureDebut16" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin16" id="heureFin16" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure6" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire16" id="commentaire16" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure6" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut26" id="heureDebut26" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin26" id="heureFin26" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure6" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire26" id="commentaire26" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure6" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut36" id="heureDebut36" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin36" id="heureFin36" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA6"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure6" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire36" id="commentaire36" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA6 R7"><td colspan="4" ><input class="Bouton" type="button" name="newAstreinte" id="newAstreinte" onclick="AfficherTR('7')" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter une astreinte";}else{echo "Add a penalty";} ?>" /></td></tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="RA7"><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="RA7">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?></td>
							<td width="10%" colspan="4"><input type="date" style="text-align:center;" id="dateDebut7" name="dateDebut7" size="10" value="" onchange="estFerie('dateDebut7')"><div style="display: inline" id="div_dateDebut7"></div></td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4" ></td></tr>
						<tr style="display:none;" class="RA7">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire7" id="commentaire7" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="RA7">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
							<td class="intervention7" width="55%"  colspan="4">
								<input type="radio" id='intervention7' name='intervention7' onclick="Affiche_Heure(7)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='intervention7' name='intervention7' onclick="Affiche_Heure(7)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure7" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut17" id="heureDebut17" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin17" id="heureFin17" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure7" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire17" id="commentaire17" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure7" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut27" id="heureDebut27" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin27" id="heureFin27" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure7" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire27" id="commentaire27" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure7" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureDebut37" id="heureDebut37" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small heures" style="text-align:center;" name="heureFin37" id="heureFin37" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr style="display:none;" class="RA7"><td height="4"></td></tr>
						<tr style="display:none;" class="nbHeure7" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
							<td width="10%" colspan="3">
								<textarea name="commentaire37" id="commentaire37" cols="100" rows="2" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
							<td width="65%" colspan="4">
								<div id="PostesValidateurs">
								<?php
									$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Prestation, new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_competences_personne_poste_prestation.Id_Pole";
									$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
									$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
									$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Poste > 1";
									$requetePersonnePoste.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
									$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
									$i=0;
									echo "<script>";
									while($rowPersonnePoste=mysqli_fetch_row($resultPersonnePoste))
									{
										 echo "Liste_Poste_Prestation[".$i."] = new Array(".$rowPersonnePoste[0].",".$rowPersonnePoste[1].",".$rowPersonnePoste[2].",'".$rowPersonnePoste[3]."',".$rowPersonnePoste[4].");\n";
										 $i+=1;
									}
									echo "</script>";
								?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="ABS_INJ">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HorsContrat">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HSJourNonT">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="AS">
								</div>
							</td>
						</tr>
						<tr style="display:none;">
							<td colspan="10" align="center">
								<div id="HS">
								</div>
							</td>
						</tr>
						<tr style="display:none;">
							<td colspan="10" align="center">
								<div id="ABS">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="Enregistrer()">
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