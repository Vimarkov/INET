<?php
require("../../Menu.php");
?>
<script language="javascript" src="DemandeAbsence.js?t=<?php echo time(); ?>"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#heureDebut').timepicker({
			minuteStep: 1,
			template: 'modal',
			appendWidgetTo: 'body',
			showSeconds: false,
			showMeridian: false,
			defaultTime: false
		});
		Mask.newMask({ 
			$el: $('#heureDebut'), 
			mask: 'HH:mm' 
		});
		$('#heureFin').timepicker({
			minuteStep: 1,
			template: 'modal',
			appendWidgetTo: 'body',
			showSeconds: false,
			showMeridian: false,
			defaultTime: false
		});
		Mask.newMask({ 
			$el: $('#heureFin'), 
			mask: 'HH:mm' 
		});
	});
</script>
<?php
$EnCours="#ce5edc";
$EnAttente="#fab342";
$TransmisRH="#449ef0";
$Automatique="#3d9538";
$Validee="#6beb47";
$Refusee="#ff5353";
$Gris="#dddddd";

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		$Id_Prestation=0;
		$Id_Pole=0;
		//Récupération de la prestation au moment de la demande 
		$req="SELECT Id_Prestation, Id_Pole 
			FROM rh_personne_mouvement
			WHERE Id_Personne=".$_POST['Id_Personne']." 
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin>='".date('Y-m-d')."' OR rh_personne_mouvement.DateFin<='0001-01-01')
			AND rh_personne_mouvement.EtatValidation=1
			AND Id_Prestation<>0
			";

		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			$row=mysqli_fetch_array($result);
			$Id_Prestation=$row['Id_Prestation'];
			$Id_Pole=$row['Id_Pole'];
		}
		else{
			$laDate="";
			$tabAbsence = explode("|",$_POST['absences']);
			foreach($tabAbsence as $abs){
				if($abs<>""){
					$tabInfo = explode(";",$abs);
				
					$nbJour=0;
					$tmpDate = $tabInfo[0];
					if($laDate==""){
						$laDate=$tmpDate;
					}
				}
			}
			if($laDate<>""){
				$prestationPole=PrestationPole_Personne($laDate,$_POST['Id_Personne']);
				if($prestationPole<>0){
					$tab=explode("_",$prestationPole);
					$Id_Prestation=$tab[0];
					$Id_Pole=$tab[1];
				}
			}
		}
		$RealiseParRH=0;
		if($_POST['Menu']==4){$RealiseParRH=1;}
		
		//Création de la demande d'absence
		$req="INSERT INTO rh_personne_demandeabsence (Id_Personne,Backup,Id_Createur,Id_Prestation,Id_Pole,Conge,RealiseParRH,DateCreation,Id_N1,DateValidationN1,EtatN1,Id_N2,DateValidationN2,EtatN2,Id_RH,DateValidationRH,EtatRH) 
			VALUES 
			(".$_POST['Id_Personne'].",'".addslashes($_POST['backup'])."',".$_SESSION['Id_Personne'].",".$Id_Prestation.",".$Id_Pole.",1,".$RealiseParRH.",'".$DateJour."',";
		if($_POST['Menu']==4){
			$req.="".$_SESSION['Id_Personne'].",'".$DateJour."',1,".$_SESSION['Id_Personne'].",'".$DateJour."',1,".$_SESSION['Id_Personne'].",'".$DateJour."',1";
		}
		else{
			if(NiveauValidationCongesPrestation($Id_Prestation)==1){
				//1 responsable ne peut pas valider ses propres congés
				$req.="0,'0001-01-01',0,0,'0001-01-01',0,0,'0001-01-01',0";
			}
			else{
				if(DroitsPrestationPole(array($IdPosteChefEquipe),$Id_Prestation,$Id_Pole) || DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$Id_Prestation,$Id_Pole)){
					$req.="".$_SESSION['Id_Personne'].",'".$DateJour."',1,";
				}
				else{
					$req.="0,'0001-01-01',0,";
				}
				//NIVEAU 2
				$req.="0,'0001-01-01',0,0,'0001-01-01',0";
			}
		}
		$req.=")";

		$resultAjout=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		
		//Creation de chaque absence
		if($IdCree>0){
			$tabAbsence = explode("|",$_POST['absences']);
			foreach($tabAbsence as $abs){
				if($abs<>""){
					$tabInfo = explode(";",$abs);
				
					$nbJour=0;
					$tmpDate = $tabInfo[0];
					$dateFin = $tabInfo[1];

					$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,DateDebut,DateFin,HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
						VALUES ";
					$nbJ=0;
					$nbN=0;
					$heure="00:00:00";
					$heureFin="00:00:00";
					$nbJours=0;
					if($tabInfo[4]<>""){$nbJ=$tabInfo[4];} //Nb heure jour
					elseif($tabInfo[6]<>""){$nbJ=$tabInfo[6];} //Nb heure BDD
					elseif($tabInfo[8]<>""){$nbJ=$tabInfo[8];} //Nb heure RC
					
					if($tabInfo[5]<>""){$nbN=$tabInfo[5];} //Nb heure nuit
					
					if($tabInfo[10]<>""){$heure=$tabInfo[10];} //Heure début 
					if($tabInfo[11]<>""){$heureFin=$tabInfo[11];} //Heure fin 
					
					if($tabInfo[9]<>""){$nbJours=$tabInfo[9];} //Nb jour
					$req.="(".$IdCree.",".$tabInfo[2].",'".$tabInfo[0]."','".$tabInfo[1]."','".$heure."','".$heureFin."',".$nbJ.",".$nbN.",".$tabInfo[7].",".$nbJours.")";
					$resultAjout=mysqli_query($bdd,$req);
				}

			}
			$bEnregistrement=true;
		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

//Liste des jours férié, par défaut nous ne prenoms que les jours >= année en cours et 1 an précédent

?>

<form id="formulaire" class="test" action="Ajout_Conges.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="NbJoursMax" id="NbJoursMax" value="0" />
	<input type="hidden" name="jourCalendaire" id="jourCalendaire" value="0" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>"; 
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Demande de congés / absence";}else{echo "Request for leave / absence";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php if($bEnregistrement==true){ 
		if($_POST['Menu']==4){?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php if($_SESSION["Langue"]=="FR"){echo "La demande d'absence a été enregistrée.";}
			else{echo "The absence request has been recorded.";} ?>
			
		</td></tr>
			<tr><td height="4"></td></tr>
	<?php
		}
		else{
	?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php if($_SESSION["Langue"]=="FR"){echo "Votre demande d'absence a été enregistrée et transmise à votre responsable.";}
			else{echo "Your absence request has been recorded and sent to your manager.";} ?>
			
		</td></tr>
			<tr><td height="4"></td></tr>
	<?php 
		}
	}
	?>
	<tr><td>
		<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
			<tr <?php if($Menu==2){echo "style='display:none;'";} ?>>
				<td width="10%" class="Libelle" align="center" colspan="3"><?php if($_SESSION["Langue"]=="FR"){echo "Personne concernée : ";}else{echo "Concerned person : ";} ?>
				&nbsp;<select name="Id_Personne" id="Id_Personne" onchange="submit();">
						<?php
							if($Menu==4){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM new_rh_etatcivil
								LEFT JOIN rh_personne_mouvement 
								ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
								WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date("Y-m-d",strtotime(date('Y-m-d')." -3 month"))."')
								AND rh_personne_mouvement.EtatValidation=1 
								AND rh_personne_mouvement.Id_Prestation<>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								ORDER BY Personne ASC";
							}
							elseif($Menu==2){

									$requetePersonne="SELECT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_Personne'];
									
							}
							$resultPersonne=mysqli_query($bdd,$requetePersonne);
							$Id_Personne=0;
							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								$selected="";
								if($_POST){if($_POST['Id_Personne']==$rowPersonne['Id']){$selected="selected";$Id_Personne=$rowPersonne['Id'];}}
								else{
									if($Id_Personne==0){$Id_Personne=$rowPersonne['Id'];}
								}
								echo "<option value='".$rowPersonne['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPersonne['Personne']))."</option>\n";
							}
							$req="SELECT DateJour,Id_Prestation 
								FROM rh_jourfixe 
								WHERE Suppr=0 
								AND Id_TypeAbsence=10
								AND YEAR(DateJour)>=".(date('Y')-1)."
								AND Id_Plateforme IN (
										SELECT Id_Plateforme 
										FROM rh_personne_mouvement
										LEFT JOIN new_competences_prestation
										ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
										WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
										AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
										AND rh_personne_mouvement.EtatValidation=1 
										AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
										AND rh_personne_mouvement.Id_Prestation<>0
									)
								AND (Id_Prestation=0 OR Id_Prestation IN (
										SELECT Id_Prestation 
										FROM rh_personne_mouvement
										LEFT JOIN new_competences_prestation
										ON rh_personne_mouvement.Id_Prestation=new_competences_prestation.Id
										WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
										AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
										AND rh_personne_mouvement.EtatValidation=1 
										AND rh_personne_mouvement.Id_Personne=".$Id_Personne."
										AND rh_personne_mouvement.Id_Prestation<>0
									)
								)
								";
							$resultJours=mysqli_query($bdd,$req);
							$listeJours="";
							while($rowJours=mysqli_fetch_array($resultJours)){
								$listeJours.=$rowJours['DateJour'].";";
							}
							
							$Id_Prestation=0;
							$Id_Pole=0;
							$Niveau=2;
							//Récupération de la prestation au moment de la demande 
							$req="SELECT Id_Prestation, Id_Pole,
								(SELECT NbNiveauValidationConges FROM new_competences_prestation WHERE Id=Id_Prestation) AS Niveau
								FROM rh_personne_mouvement
								WHERE Id_Personne=".$Id_Personne." 
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin>='".date('Y-m-d')."' OR rh_personne_mouvement.DateFin<='0001-01-01')
								AND rh_personne_mouvement.EtatValidation=1 
								AND rh_personne_mouvement.Id_Prestation<>0
								AND Suppr=0
								";

							$result=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($result);
							if($nb>0){
								$row=mysqli_fetch_array($result);
								$Id_Prestation=$row['Id_Prestation'];
								$Id_Pole=$row['Id_Pole'];
								if($row['Niveau']>0){$Niveau=$row['Niveau'];}
							}
						?>
					</select>
					<input type="hidden" name="ListeJoursFerie" id="ListeJoursFerie" value="<?php echo $listeJours; ?>" />
					<input type="hidden" name="leJour" id="leJour" value="" />
					<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $Id_Prestation;?>" />
					<input type="hidden" name="Id_Pole" id="Id_Pole" value="<?php echo $Id_Pole;?>" />
					<input type="hidden" name="Niveau" id="Niveau" value="<?php echo $Niveau;?>" />
					<?php 
						$req="SELECT
							(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS Contrat 
							FROM rh_personne_contrat 
							WHERE Id=".IdContratEC($Id_Personne);
						$resultContrat=mysqli_query($bdd,$req);
						$nbContrat=mysqli_num_rows($resultContrat);
						if($nbContrat>0){
							$rowContat=mysqli_fetch_array($resultContrat);
							echo $rowContat['Contrat'];
						}
					?>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="6" align="center">
					<table bgcolor="#eaf5fa" width="70%" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Du :";}else{echo "from :";} ?></td>
							<td width="15%"><input type="date" style="text-align:center;" onchange="Modif_TypeAbsence();" onFocus="issetfocus='dateDebut';" id="dateDebut" name="dateDebut" size="10" value=""></td>
							<td width="10%" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "au :";}else{echo "to :";} ?></td>
							<td width="15%" class="Libelle"><input type="date" style="text-align:center;" onchange="Modif_TypeAbsence();" id="dateFin" onFocus="issetfocus='dateFin';" name="dateFin" size="10" value="">&nbsp;&nbsp;inclus</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence :";}else{echo "Type of absence :";} ?></td>
							<td width="20%">
								<select id="typeAbsence" name="typeAbsence" onchange="Modif_TypeAbsence();" onFocus="issetfocus='';" style="width: 60%;">
									<option value="0"></option>
									<?php
										if($_SESSION["Langue"]=="FR"){
											
											$Dispo="DispoPourSalarie=1";
											if(estSalarie(date('Y-m-d'),$Id_Personne)==0){$Dispo="DispoPourInterimaire=1";}
											
											$reqAbsVac = "SELECT Id ,Libelle, CodePlanning, InformationSalarie, NbJourAutorise, JourCalendaire 
														FROM rh_typeabsence 
														WHERE Suppr=0 
														AND ".$Dispo."
														ORDER BY CodePlanning ";
											//Manque analyse si la personne est intérimaire ou pas
										}
										else{
											$Dispo="DispoPourSalarie=1";
											if(estSalarie(date('Y-m-d'),$Id_Personne)==0){$Dispo="DispoPourInterimaire=1";}
											
											$reqAbsVac = "SELECT Id ,LibelleEN AS Libelle, CodePlanning, InformationSalarie, NbJourAutorise, JourCalendaire
														FROM rh_typeabsence 
														WHERE Suppr=0 
														AND ".$Dispo."
														ORDER BY CodePlanning ";
											//Manque analyse si la personne est intérimaire ou pas
										}
										$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
										$nbAbsVac=mysqli_num_rows($resultAbsVac);
										if ($nbAbsVac > 0){
											$i=0;
											while($rowAbsVac=mysqli_fetch_array($resultAbsVac)){	
												echo "<option value='".$rowAbsVac['Id']."' >".$rowAbsVac['CodePlanning']." | ".$rowAbsVac['Libelle']."</option>";
												echo "<script>ListeTypeAbsence[".$i."] = new Array('".$rowAbsVac['Id']."','".$rowAbsVac['NbJourAutorise']."','".$rowAbsVac['JourCalendaire']."') </script>";
												$i++;
											}
										}
									?>
								</select>
								<div id="nbJourTypeAbs" style="display:inline;color:black;"></div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="journee" style="display:none;">
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Journée complète :";}else{echo "Full day :";} ?> </td>
							<td width="15%">
								<input type="radio" id='journeeComplete' name='journeeComplete' onclick="Modif_TypeAbsence2()" value="Oui" checked><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
								<input type="radio" id='journeeComplete' name='journeeComplete' onclick="Modif_TypeAbsence2()" value="Non" ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure" style="display:none;">
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?> : </td>
							<td width="15%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" type="text" name="heureDebut" id="heureDebut" size="8" value="">
								</div>
							</td>
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?> : </td>
							<td width="15%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" type="text" name="heureFin" id="heureFin" size="8" value="">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeure" style="display:none;">
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 6h et 21h";}else{echo "Number of hours of absence between 6h and 21h";} ?> : </td>
							<td width="15%">
								<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureJour" id="nbHeureJour" size="10" type="text" value= "">
							</td>
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 21h et 6h";}else{echo "Number of hours of absence between 21h and 6h";} ?>  : </td>
							<td width="15%">
								<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureNuit" id="nbHeureNuit" size="10" type="text" value= "">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="nbHeuresRC" style="display:none;">
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heures";}else{echo "Number of hours";} ?> : </td>
							<td width="15%">
								<input onKeyUp="nombre2(this)" type="text" name="nbHeureRC" id="nbHeureRC" size="6" value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="delegation" style="display:none;">
							<td width="10%" colspan="2" class="Libelle">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heures";}else{echo "Number of hours";} ?> : </td>
							<td width="15%">
								<input onKeyUp="nombre(this)" type="text" name="nbHeuresBDD" id="nbHeuresBDD" size="6" value="">
							</td>
							<td width="10%" colspan="2" class="Libelle" valign="top">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fonction représentative";}else{echo "Representative function";} ?> : </td>
							<td width="15%" valign="top">
								<select id="fonctionRepresentative" name="fonctionRepresentative" style="width: 60%;">
									<option name="0" value="0"></option>
									<?php
										$req = "SELECT Id ,Libelle
													FROM rh_fonctionrepresentative 
													WHERE Suppr=0 
													ORDER BY Libelle ";

										$result=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($result);
										if ($nb > 0){
											while($row=mysqli_fetch_array($result)){	
												echo "<option value='".$row['Id']."' >".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<?php
						$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
						$nbAbsVac=mysqli_num_rows($resultAbsVac);
						if ($nbAbsVac > 0){
							$i=0;
							while($rowAbsVac=mysqli_fetch_array($resultAbsVac)){	
						?>
							<tr id="infosSalarie<?php echo $rowAbsVac['Id']; ?>" style="display:none;" align="center">
								<td colspan="6" style="color:red;font-weight:bold;">
									<?php echo stripslashes($rowAbsVac['InformationSalarie']); ?>
								</td>
							</tr>
						<?php
							}
						}
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="CongesExistants">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="Formation">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HS">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HorsContrat">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
							<div id="Ajouter">
							</div>
							<input class="Bouton" type="button" id="btnAjouter" name="btnAjouter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>" onClick="AfficherAjouter()">
							<div style="display:none;">
								<input id="travailCeJour" value="" />
								<input id="VSD" value="" />
							</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="6" align="center">
					<table bgcolor="#eaf5fa" width="70%" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center" class="Libelle">
								<?php if($_SESSION["Langue"]=="FR"){echo "Backup :";}else{echo "Backup :";} ?>
								<input type="text" id="backup" name="backup" size="25" value="<?php if(isset($_POST['backup'])){echo $_POST['backup'];}?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr align="center">
				<td colspan="6" align="center">
				<table align="center" width="100%" cellpadding="0" cellspacing="0">
				<?php
					$Debut=date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
					$Fin=date("Y-m-d",mktime(0,0,0,date("m")+6,0,date("Y")));
					
					$reqAbsence="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
								rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
								(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
								(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
								FROM rh_absence 
								LEFT JOIN rh_personne_demandeabsence 
								ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
								WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
								AND rh_absence.DateFin>='".$Debut."' 
								AND rh_absence.DateDebut<='".$Fin."' 
								AND rh_personne_demandeabsence.Suppr=0 
								AND rh_absence.Suppr=0 
								AND rh_personne_demandeabsence.Annulation=0 
								AND rh_personne_demandeabsence.Conge=1 
								ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
					$resultAbsences=mysqli_query($bdd,$reqAbsence);
					$nbAbsences=mysqli_num_rows($resultAbsences);
					
					$tmpDate=date("Y-m-d",mktime(0,0,0,date("m"),1,date("Y")));
					if($_SESSION["Langue"]=="FR"){
						$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
						$joursem = array("D", "L", "Mar", "Mer", "J", "V", "S");
						$joursem2 = array("L", "Mar", "Mer", "J", "V", "S","D");
					}
					else{
						$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
						$joursem = array("Sun", "M", "Tu", "W", "Th", "F", "Sat");
						$joursem2 = array("M", "Tu", "W", "Th", "F", "Sat","Sun");
					}
					for($i=1;$i<=6;$i++){
						if($i==1 || $i==4){echo "<tr>";}
							echo "<td>";
								echo "<table style='border:1px solid #787878;' width='70%' cellpadding='0' cellspacing='0'>";
									$tabDate = explode('-', $tmpDate);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$mois = $tabDate[1];
									echo "<tr><td class='cEnTete' colspan='8' align='center'>".$MoisLettre[$mois-1]." ".$tabDate[0]."</td></tr>";
									if($_SESSION["Langue"]=="FR"){
										echo "<tr><td class='cLigne1' align='center'></td><td class='cLigne1' align='center'>Lun.</td><td class='cLigne1' align='center'>Mar.</td><td class='cLigne1' align='center'>Mer.</td>";
										echo "<td class='cLigne1' align='center'>Jeu.</td><td class='cLigne1' align='center'>Ven.</td><td class='cLigne1' align='center'>Sam.</td><td class='cLigne1' align='center'>Dim.</td></tr>";
									}
									else{
										echo "<tr><td class='cLigne1' align='center'></td><td class='cLigne1' align='center'>Mon.</td><td class='cLigne1' align='center'>Tue.</td><td class='cLigne1' align='center'>Wed.</td>";
										echo "<td class='cLigne1' align='center'>Thu.</td><td class='cLigne1' align='center'>Fri.</td><td class='cLigne1' align='center'>Sat.</td><td class='cLigne1' align='center'>Sun.</td></tr>";
									}
									//Premier jour du mois
									$dateMois=date("Y-m-d",mktime(0,0,0,$tabDate[1],1,$tabDate[0]));
									for($ligne=1;$ligne<=6;$ligne++){
										echo "<tr>";
										for($colonne=0;$colonne<=7;$colonne++){
											$tabDateMois = explode('-', $dateMois);
											$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
											$semaine = date('W', $timestampMois);
											$jour = $tabDateMois[2];
											$jourSemaine = date('w', $timestampMois);
											
											$Trouve=false;
											$TypeDC="";
											if($_POST){
												$tabAbsence = explode("|",$_POST['absences']);
												foreach($tabAbsence as $abs){
													if($abs<>""){
														$tabInfo = explode(";",$abs);
														$dateDebut=$tabInfo[0];
														$dateFin=$tabInfo[1];
														$Id_Type=$tabInfo[2];
														
														$reqAbsVac = "SELECT CodePlanning FROM rh_typeabsence WHERE Id=".$Id_Type."; ";
														$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
														$nbAbsVac=mysqli_num_rows($resultAbsVac);
														$leType="";
														if ($nbAbsVac > 0){
															$rowAbsVac=mysqli_fetch_array($resultAbsVac);
															$leType=$rowAbsVac['CodePlanning'];
														}
														
														if($dateMois>=$dateDebut && $dateMois<=$dateFin){
															$Trouve=true;
															$TypeDC=$leType;
														}
													}
												}
												
												if($_POST['dateDebut']<>""){
													$reqAbsVac = "SELECT CodePlanning FROM rh_typeabsence WHERE Id=".$_POST['typeAbsence']."; ";
													$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
													$nbAbsVac=mysqli_num_rows($resultAbsVac);
													
													$leType="";
													if ($nbAbsVac > 0){
														$rowAbsVac=mysqli_fetch_array($resultAbsVac);
														$leType=$rowAbsVac['CodePlanning'];
													}
													
													$nbJour=0;
													$tmpDate2 = TrsfDate_($_POST['dateDebut']);
													$dateFin2 = TrsfDate_($_POST['dateFin']);
													while ($tmpDate2 <= $dateFin2){
														if($dateMois==$tmpDate2){
															//Vérifier si la personne travaille ce jour là
															$Id_Contrat =IdContrat($Id_Personne,$tmpDate2);
															if($Id_Contrat>0){

																if(TravailCeJourDeSemaine($tmpDate2,$Id_Personne)<>""){
																	if(estJour_Fixe($tmpDate2,$Id_Personne)=="" || estCalendaire($_POST['typeAbsence'])==1 || Id_TypeContrat($Id_Contrat)==18 || Id_TypeContrat($Id_Contrat)==41){
																		$Trouve=true;
																		$TypeDC=$leType;
																		$tmpDate2=$dateFin2;
																	}
																}
															}
															else{
																if(TravailCeJourDeSemaineDernierContrat($tmpDate2,$Id_Personne)<>""){
																	$travailCeJour=TravailCeJourDeSemaineDernierContrat($tmpDate,$Id_Personne);
																	if(estJour_Fixe($tmpDate2,$Id_Personne)=="" || estCalendaire($_POST['typeAbsence'])==1 || $travailCeJour==18 || $travailCeJour==41){
																		$Trouve=true;
																		$TypeDC=$leType;
																		$tmpDate2=$dateFin2;
																	}
																}
															}
														}
														//Jour suivant
														$tmpDate2 =date("Y-m-d",strtotime($tmpDate2." +1 day"));
													}
												}
											}
											$bEtat="rien";
											$type="";
											if($nbAbsences>0){
												mysqli_data_seek($resultAbsences,0);
												while($rowAbsences=mysqli_fetch_array($resultAbsences)){
													if($rowAbsences['DateDebut']<=$dateMois && $rowAbsences['DateFin']>=$dateMois){
														if($rowAbsences['TypeAbsenceDef']<>""){$type=$rowAbsences['TypeAbsenceDef'];}
														else{$type=$rowAbsences['TypeAbsenceIni'];}
														$bEtat="attenteValidation";
														if($rowAbsences['EtatN1']==-1 || $rowAbsences['EtatN2']==-1){$bEtat="refusee";}
														elseif($rowAbsences['EtatRH']==1){$bEtat="validee";}
														elseif($rowAbsences['EtatRH']==0 && ($rowAbsences['EtatN1']==1 && $rowAbsences['EtatN2']==1)){$bEtat="TransmisRH";}
														break;
													}
												}
											}
											if($colonne==0){
												echo "<td align='center' style='color:#979797;'>".$semaine."</td>";
											}
											else{
												if($jour==1){
													if($joursem[$jourSemaine]==$joursem2[$colonne-1] && $tabDate[1]==$tabDateMois[1]){
														$bgcolor="";
														if(estWE($timestampMois)){
															$bgcolor="bgcolor='".$Gris."'";
														}
														if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
														elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
														elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
														elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
														
														$jourFixe=estJour_Fixe($dateMois,$Id_Personne);
														$Id_Contrat =IdContrat($Id_Personne,$dateMois);
														if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
															$bgcolor="bgcolor='".$Automatique."'";
															$type=$jourFixe;
														}
														
														if($_POST){
															if(isset($_POST['btnAjouter2'])){
																if($Trouve==true){
																	$bgcolor="bgcolor='".$EnCours."'";
																	$type=$TypeDC;
																}
															}
														}
														
														$dateJourCouleur="";
														if($dateMois==date('Y-m-d')){$dateJourCouleur="color:red;text-weight:bold;";}
														echo "<td style=\"border:1px solid #b9b9b9;font-size:12px;".$dateJourCouleur."\" ".$bgcolor." align='center'>".$jour."<sup>".$type."</sup></td>";
														$tabDateMois = explode('-', $dateMois);
														$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
														$dateMois = date("Y-m-d", $timestampMois);
													}
													else{
														echo "<td style='border:1px solid #b9b9b9;font-size:12px;' align='center'></td>";
													}
												}
												else{
													$bgcolor="";
													if(estWE($timestampMois)){
														$bgcolor="bgcolor='".$Gris."'";
													}
													if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
													elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
													elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
													elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
													
													$jourFixe=estJour_Fixe($dateMois,$Id_Personne);
													$Id_Contrat =IdContrat($Id_Personne,$dateMois);
													if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
														$bgcolor="bgcolor='".$Automatique."'";
														$type=$jourFixe;
													}
													
													if($_POST){
														if(isset($_POST['btnAjouter2'])){
															if($Trouve==true){
																$bgcolor="bgcolor='".$EnCours."'";
																$type=$TypeDC;
															}
														}
													}
													$dateJourCouleur="";
													if($dateMois==date('Y-m-d')){$dateJourCouleur="color:red;text-weight:bold;";}
													
													echo "<td style=\"border:1px solid #b9b9b9;font-size:12px;".$dateJourCouleur."\" ".$bgcolor." align='center'>".$jour."<sup>".$type."</sup></td>";
													$tabDateMois = explode('-', $dateMois);
													$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
													$dateMois = date("Y-m-d", $timestampMois);
												}
											}
											
										}
										echo "</tr>";
									}
								echo "</table>";
							echo "</td>";
						if($i==3 || $i==6){echo "</tr><tr><td height='20'></td></tr>";}
						
						//Mois suivant
						$tabDate = explode('-', $tmpDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1]+1, $tabDate[2], $tabDate[0]);
						$tmpDate = date("Y-m-d", $timestamp);
					}
				?>
				</table>
			</td></tr>
			<tr>
				<td colspan="6" align="center">
					<table align="center" width="60%" cellpadding="0" cellspacing="0">
						<tr align="left">
							<td bgcolor="<?php echo $EnCours; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Demande en cours";}else{echo "Demand in progress";} ?></td>
							<td bgcolor="<?php echo $EnAttente; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "En attente de pré validation";}else{echo "Waiting for pre-validation";} ?></td>
							<td bgcolor="<?php echo $TransmisRH; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Transmis aux RH";}else{echo "Submitted to HR";} ?></td>
							<td bgcolor="<?php echo $Automatique; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Automatique";}else{echo "Automatic";} ?></td>
							<td bgcolor="<?php echo $Validee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validée";}else{echo "Validated";} ?></td>
							<td bgcolor="<?php echo $Refusee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Refusée";}else{echo "Declined";} ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<?php
				$valeur2="";
				$valeur="";
				if($bEnregistrement==false){
					if($_POST){
						$tabAbsence = explode("|",$_POST['absences']);
						//Ce qui est en cours déjà ajouté
						foreach($tabAbsence as $abs){
							if($abs<>""){
								$tabInfo = explode(";",$abs);
								$dateDebut=$tabInfo[0];
								$dateFin=$tabInfo[1];
								$Id_Type=$tabInfo[2];
								$NbJour=$tabInfo[9];
								$reqAbsVac = "SELECT CodePlanning FROM rh_typeabsence WHERE Id=".$Id_Type."; ";
								$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
								$nbAbsVac=mysqli_num_rows($resultAbsVac);
								
								$type="";
								if ($nbAbsVac > 0){
									$rowAbsVac=mysqli_fetch_array($resultAbsVac);
									$type=$rowAbsVac['CodePlanning'];
								}
								
								$valeur2.="<tr><td>Du ".AfficheDateJJ_MM_AAAA($dateDebut)." au ".AfficheDateJJ_MM_AAAA($dateFin)." (".$NbJour." ".$type.")";
								$valeur2.="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$dateDebut.";".$dateFin.";".$Id_Type.";".$tabInfo[3].";".$tabInfo[4].";".$tabInfo[5].";".$tabInfo[6].";".$tabInfo[7].";".$tabInfo[8].";".$tabInfo[9].";".$tabInfo[10].";".$tabInfo[11]."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a></td></tr>";
								$valeur.="".$dateDebut.";".$dateFin.";".$Id_Type.";".$tabInfo[3].";".$tabInfo[4].";".$tabInfo[5].";".$tabInfo[6].";".$tabInfo[7].";".$tabInfo[8].";".$tabInfo[9].";".$tabInfo[10].";".$tabInfo[11]."|";
							}
						}
						//Ce qui vient d'être ajouté
						if($_POST['dateDebut']<>""){
							$reqAbsVac = "SELECT CodePlanning FROM rh_typeabsence WHERE Id=".$_POST['typeAbsence']."; ";
							$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
							$nbAbsVac=mysqli_num_rows($resultAbsVac);
							$type="";
							if ($nbAbsVac > 0){
								$rowAbsVac=mysqli_fetch_array($resultAbsVac);
								$type=$rowAbsVac['CodePlanning'];
							}
							$nbJour=0;
							$tmpDate = TrsfDate_($_POST['dateDebut']);
							$dateFin = TrsfDate_($_POST['dateFin']);
							$jourAStocker=$tmpDate;
							while ($tmpDate <= $dateFin){
								//Vérifier si la personne travaille ce jour là
								$Id_Contrat =IdContrat($Id_Personne,$tmpDate2);
								if($Id_Contrat>0){
									if(TravailCeJourDeSemaine($tmpDate,$Id_Personne)<>""){
										//18=VSD
										if(estJour_Fixe($tmpDate,$Id_Personne)=="" || estCalendaire($_POST['typeAbsence']) || Id_TypeContrat($Id_Contrat)==18 || Id_TypeContrat($Id_Contrat)==41){
											$nbJour++;
										}
										else{
											if($nbJour>0){
												$jourFin=date("Y-m-d",strtotime($tmpDate." -1 day"));
												$valeur2.="<tr><td>Du ".AfficheDateJJ_MM_AAAA($jourAStocker)." au ".AfficheDateJJ_MM_AAAA($jourFin)." (".$nbJour." ".$type.")";
												$valeur2.="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a></td></tr>";
												$valeur.="".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."|";
											}
											$jourAStocker=date("Y-m-d",strtotime($tmpDate." +1 day"));
											$nbJour=0;
										}
									}
									else{
										if($nbJour>0){
											$jourFin=date("Y-m-d",strtotime($tmpDate." -1 day"));
											$valeur2.="<tr><td>Du ".AfficheDateJJ_MM_AAAA($jourAStocker)." au ".AfficheDateJJ_MM_AAAA($jourFin)." (".$nbJour." ".$type.")";
											$valeur2.="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a></td></tr>";
											$valeur.="".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."|";
										}
										$jourAStocker=date("Y-m-d",strtotime($tmpDate." +1 day"));
										$nbJour=0;
									}
								}
								else{
									if(TravailCeJourDeSemaineDernierContrat($tmpDate,$Id_Personne)<>""){
										$travailCeJour=TravailCeJourDeSemaineDernierContrat($tmpDate,$Id_Personne);
										if(estJour_Fixe($tmpDate,$Id_Personne)=="" || estCalendaire($_POST['typeAbsence']) || $travailCeJour==18 || $travailCeJour==41){
											$nbJour++;
										}
										else{
											if($nbJour>0){
												$jourFin=date("Y-m-d",strtotime($tmpDate." -1 day"));
												$valeur2.="<tr><td>Du ".AfficheDateJJ_MM_AAAA($jourAStocker)." au ".AfficheDateJJ_MM_AAAA($jourFin)." (".$nbJour." ".$type.")";
												$valeur2.="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a></td></tr>";
												$valeur.="".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."|";
											}
											$jourAStocker=date("Y-m-d",strtotime($tmpDate." +1 day"));
											$nbJour=0;
										}
									}
									else{
										if($nbJour>0){
											$jourFin=date("Y-m-d",strtotime($tmpDate." -1 day"));
											$valeur2.="<tr><td>Du ".AfficheDateJJ_MM_AAAA($jourAStocker)." au ".AfficheDateJJ_MM_AAAA($jourFin)." (".$nbJour." ".$type.")";
											$valeur2.="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a></td></tr>";
											$valeur.="".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."|";
										}
										$jourAStocker=date("Y-m-d",strtotime($tmpDate." +1 day"));
										$nbJour=0;
									}
								}
								//Jour suivant
								$tmpDate =date("Y-m-d",strtotime($tmpDate." +1 day"));
							}
							if($nbJour>0){
								$jourFin=date("Y-m-d",strtotime($tmpDate." -1 day"));
								$valeur2.="<tr><td >Du ".AfficheDateJJ_MM_AAAA($jourAStocker)." au ".AfficheDateJJ_MM_AAAA($jourFin)." (".$nbJour." ".$type.")";
								$valeur2.="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a></td></tr>";
								$valeur.="".$jourAStocker.";".$jourFin.";".$_POST['typeAbsence'].";".$_POST['journeeComplete'].";".$_POST['nbHeureJour'].";".$_POST['nbHeureNuit'].";".$_POST['nbHeuresBDD'].";".$_POST['fonctionRepresentative'].";".$_POST['nbHeureRC'].";".$nbJour.";".$_POST['heureDebut'].";".$_POST['heureFin']."|";
							}
						}
					}
				}
			?>
			<tr <?php if($valeur2==""){echo 'style="display:none;"';}?>>
				<td colspan="6" align="center">
					<table class="TableCompetences" style="border:3px #edf430 solid;" width="30%" cellpadding="0" cellspacing="0"  align="center">
						<tr><td height="4" bgcolor="#edf430"></td></tr>
						<tr>
							<td colspan="6" align="center" class="Libelle" bgcolor="#edf430"><?php if($_SESSION["Langue"]=="FR"){echo "VOULEZ-VOUS ENREGISTRER CETTE DEMANDE DE CONGES ?";}else{echo "REQUEST FOR LEAVE IN PROGRESS";} ?></td>
						</tr>
						<tr><td height="4" bgcolor="#edf430"></td></tr>
						<tr style="display:none;">
							<td colspan="6" bgcolor="#edf430" style="display:none;"><input type="texte" id="absences" name="absences" value="<?php if($_POST){if(isset($_POST['btnAjouter2'])){echo $valeur;}} ?>" size="100"></td>
						</tr>
						<tr>
							<td colspan="6" bgcolor="#edf430">
							<?php
								if($_POST){
									if(isset($_POST['btnAjouter2'])){
										echo "<table align='center' width='98%' cellpadding='0' cellspacing='0'>";
										echo $valeur2;
										echo "</table>";
									}
								}
							?>
							</td>
						</tr>
						<tr><td height="4" bgcolor="#edf430"></td></tr>
						<tr>
							<td colspan="6" align="center" bgcolor="#edf430">
								<input class="Bouton" style="font-size:16px;" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="Enregistrer()">
							</td>
						</tr>
						<tr><td height="4" bgcolor="#edf430"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
				<td width="65%" colspan="4">
					<div id="PostesValidateurs">
					<?php
						$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Prestation, new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_competences_personne_poste_prestation.Id_Pole";
						$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
						$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
						$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Poste >= 1";
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
			<tr style="display:none;">
				<td colspan="10" align="center">
					<input id="AS" value="" />
				</td>
			</tr>
		</table>
	</td></tr>
</table>
</form>
<?php
	echo "<script>Recharge_Responsables();</script>";
?>
</body>
</html>