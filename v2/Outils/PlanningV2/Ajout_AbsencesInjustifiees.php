<?php
require("../../Menu.php");
?>
<script language="javascript" src="Absences.js"></script>
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
			//Création du rapport d'astreinte
			
			$dateN2='0001-01-01';
			$Id_N2=0;
			if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole'])){
				$dateN2=$DateJour;
				$Id_N2=$_SESSION['Id_Personne'];
			}
			$req="INSERT INTO rh_personne_demandeabsence (Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Conge,RealiseParRH,Prevue,DateCreation,Id_N1,DateValidationN1,DatePriseEnCompteN1,EtatN1,DatePriseEnCompteN2,Id_PriseEnCompteN2) 
			VALUES 
				(".$TabPersonne[$i].",".$_SESSION['Id_Personne'].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",0,0,".$_POST['absencePrevue'].",'".$DateJour."','".$_SESSION['Id_Personne']."','".$DateJour."','".$DateJour."',1,'".$dateN2."',".$Id_N2.")";
			$resultAjout=mysqli_query($bdd,$req);
			$IdCree = mysqli_insert_id($bdd);
			
			if($IdCree>0){
				$heureD="00:00:00";
				$heureF="00:00:00";
				$nbJ=0;
				$nbN=0;
				$nbJour=0;
				if($_POST['heureDebut']<>""){$heureD=$_POST['heureDebut'];}
				if($_POST['heureFin']<>""){$heureF=$_POST['heureFin'];}
				if($_POST['nbHeureJour']<>""){$nbJ=$_POST['nbHeureJour'];}
				if($_POST['nbHeureNuit']<>""){$nbN=$_POST['nbHeureNuit'];}
				
				$nbJour=0;
				$tmpDate2 = TrsfDate_($_POST['dateDebut']);
				$dateFin2 = TrsfDate_($_POST['dateFin']);
				
				while ($tmpDate2 <= $dateFin2){
					//Vérifier si la personne travaille ce jour là
					if(TravailCeJourDeSemaine($tmpDate2,$TabPersonne[$i])<>""){
						if(estJour_Fixe($tmpDate2,$TabPersonne[$i])==""){
							$nbJour++;
						}
					}
					//Jour suivant
					$tmpDate2 =date("Y-m-d",strtotime($tmpDate2." +1 day"));
				}
				$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,DateDebut,DateFin,HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,NbJour) 
					VALUES (".$IdCree.",0,'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',
					'".$heureD."','".$heureF."',".$nbJ.",".$nbN.",".$nbJour.")";
				$resultAjoutAst=mysqli_query($bdd,$req);
				$bEnregistrement=true;
				
				//Supprimer les HS pendant ce créneau 
				$req="UPDATE rh_personne_hs
					SET Suppr=1,
					Id_Suppr=".$_SESSION['Id_Personne'].",
					DateSuppr='".date('Y-m-d')."' 
					WHERE Suppr=0
					AND Id_Personne=".$TabPersonne[$i]." 
					AND Etat2<>-1
					AND Etat3<>-1
					AND Etat4<>-1
					AND DateHS<='".TrsfDate_($_POST['dateFin'])."'
					AND DateHS>='".TrsfDate_($_POST['dateDebut'])."'";
				$resultSupprHS=mysqli_query($bdd,$req);
			}			
			
		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>

<form id="formulaire" class="test" action="Ajout_AbsencesInjustifiees.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#f561a4;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclaration d'absence";}else{echo "Report absence";}
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
				if($_SESSION["Langue"]=="FR"){echo "Votre déclaration d'absence a été enregistrée.";}
				else{echo "Your declaration of absence has been registered.";} 
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
	<?php } ?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="60%" align="center" cellpadding="0" cellspacing="0">
						<?php
						?>
						<tr>
							<td width="20%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="30%">
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();" style="width:200px;">
									<?php
										$requeteSite="SELECT Id, Libelle
											FROM new_competences_prestation
											WHERE (
											Id_Plateforme IN 
												(
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
												)
											OR 
											new_competences_prestation.Id IN 
												(SELECT Id_Prestation 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION["Id_Personne"]."
												AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
												)
											)
											AND Active=0
											ORDER BY Libelle ASC";
										
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option value='".$rowsite['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td width="20%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="30%">
								<select name="Id_Pole" id="Id_Pole" onchange="Recharge_Personnel();">
									<?php
										$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
												FROM new_competences_pole
												LEFT JOIN new_competences_prestation
												ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
												WHERE (
													Id_Plateforme IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
													)
													OR
													CONCAT(new_competences_prestation.Id,'_',new_competences_pole.Id) IN 
													(SELECT CONCAT(Id_Prestation,'_',Id_Pole)
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
													)
												)
												AND Actif=0
												ORDER BY new_competences_pole.Libelle ASC";
										
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
							<td width="10%" valign="top">
								<select name="Id_Personne" id="Id_Personne" multiple size="12" onDblclick="ajouter();">
								<?php
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
									rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
									AND rh_personne_mouvement.EtatValidation=1 
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
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées<br> (double-clic) :";}else{echo "Selected people<br> (double-click) :";} ?></td>
							<td width="40%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="12" onDblclick="effacer();">
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
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prévenue :";}else{echo "Warned";} ?> </td>
							<td width="10%">
								<input type="radio" id='absencePrevue' name='absencePrevue' value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='absencePrevue' name='absencePrevue' value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début de l'absence :";}else{echo "Start date of absence :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" onchange="ModifDate();" onFocus="issetfocus='dateDebut';" id="dateDebut" name="dateDebut" size="10" value=""></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Journée entière :";}else{echo "Whole day";} ?> </td>
							<td width="10%">
								<input type="radio" id='journeeEntiere' name='journeeEntiere' onclick="Affiche_Heure(1)" value="1" checked><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='journeeEntiere' name='journeeEntiere' onclick="Affiche_Heure(0)" value="0" ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="heures" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début de l'absence";}else{echo "Time of absence start";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureDebut" id="heureDebut" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin de l'absence";}else{echo "End time of absence";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureFin" id="heureFin" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr class="heures" style="display:none;"><td height="4"></td></tr>
						<tr class="heures" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 6h et 21h";}else{echo "Number of hours of absence between 6h and 21h";} ?> : </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureJour" id="nbHeureJour" size="10" type="text" value= "">
							</td>
							<td width="10%"class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 21h et 6h";}else{echo "Number of hours of absence between 21h and 6h";} ?>  : </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureNuit" id="nbHeureNuit" size="10" type="text" value= "">
							</td>
						</tr>
						<tr class="heures" style="display:none;">
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin de l'absence :";}else{echo "End date of absence :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" onchange="ModifDate();" id="dateFin" onFocus="issetfocus='dateFin';" name="dateFin" size="10" value=""></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="CongesAbsences">
								</div>
							</td>
						</tr>
						<tr style="display:none;">
							<td colspan="10" align="center">
								<div id="HS">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerManager()">
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