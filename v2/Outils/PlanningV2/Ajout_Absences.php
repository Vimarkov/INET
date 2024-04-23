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
			$PrestaPole=PrestationPole_Personne(date('Y-m-d'),$TabPersonne[$i]);
			$Id_Prestation=0;
			$Id_Pole=0;
			if($PrestaPole<>0){
				$tab=explode("_",$PrestaPole);
				$Id_Prestation=$tab[0];
				$Id_Pole=$tab[1];
			}
			else{
				$PrestaPole=PrestationPole_Personne(TrsfDate_($_POST['dateDebut']),$TabPersonne[$i]);
				$Id_Prestation=0;
				$Id_Pole=0;
				if($PrestaPole<>0){
					$tab=explode("_",$PrestaPole);
					$Id_Prestation=$tab[0];
					$Id_Pole=$tab[1];
				}
			}
			//Création du rapport d'astreinte
			$req="INSERT INTO rh_personne_demandeabsence (Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Conge,RealiseParRH,Prevue,DateCreation,DatePriseEnCompteRH) 
			VALUES 
				(".$TabPersonne[$i].",".$_SESSION['Id_Personne'].",".$Id_Prestation.",".$Id_Pole.",0,1,".$_POST['absencePrevue'].",'".$DateJour."','".$DateJour."')";
			$resultAjout=mysqli_query($bdd,$req);
			$IdCree = mysqli_insert_id($bdd);
			
			if($IdCree>0){
				$heureD="00:00:00";
				$heureF="00:00:00";
				$nbJ=0;
				$nbN=0;
				$nbJour=0;
				$Id_Type=24;
				if($_POST['heureDebut']<>""){$heureD=$_POST['heureDebut'];}
				if($_POST['heureFin']<>""){$heureF=$_POST['heureFin'];}
				if($_POST['nbHeureJour']<>""){$nbJ=$_POST['nbHeureJour'];}
				if($_POST['nbHeureNuit']<>""){$nbN=$_POST['nbHeureNuit'];}
				
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
					VALUES (".$IdCree.",".$_POST['typeAbsence'].",'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',
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

<form id="formulaire" class="test" action="Ajout_Absences.php" method="post">
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
									<?php if($Menu==4){echo "<option value='0'></option>";}?>
										
									<?php
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
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date("Y-m-d",strtotime(date('Y-m-d')." -3 month"))."')
									AND rh_personne_mouvement.EtatValidation=1 
									ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								$i=0;
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									$PrestaPole=PrestationPole_Personne(date('Y-m-d'),$rowpersonne['Id']);
									$Id_Prestation=0;
									$Id_Pole=0;
									if($PrestaPole<>0){
										$tab=explode("_",$PrestaPole);
										$Id_Prestation=$tab[0];
										$Id_Pole=$tab[1];
									}
									echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
									echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."','".$Id_Prestation."','".$Id_Pole."');</script>";
									$i+=1;
								}
								?>
								</select>
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées<br> (double-clic) :";}else{echo "Selected people<br> (double-click) :";} ?></td>
							<td width="40%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="12" onDblclick="effacer();">

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
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Absence justifiée :";}else{echo "Justified absence";} ?> </td>
							<td width="10%">
								<input type="radio" id='absenceJustifiee' name='absenceJustifiee' onclick="Affiche_TypeAbsence(1)" value="1" ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='absenceJustifiee' name='absenceJustifiee' onclick="Affiche_TypeAbsence(0)" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début de l'absence :";}else{echo "Start date of absence :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" onchange="ModifDate();" onFocus="issetfocus='dateDebut';" id="dateDebut" name="dateDebut" size="10" value=""></td>
						</tr>
						<tr class="types" style="display:none;"><td height="4"></td></tr>
						<tr class="types" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence :";}else{echo "Type of absence :";} ?></td>
							<td width="10%">
								<select id="typeAbsence" name="typeAbsence" style="width:60%;">
									<option value="0"></option>
									<?php
										if($_SESSION["Langue"]=="FR"){
											$reqAbsVac = "SELECT Id ,Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
														FROM rh_typeabsence 
														WHERE Suppr=0 
														AND DispoPourSalarie=0 
														AND DispoPourInterimaire=0
														AND Id<>10
														ORDER BY Libelle ";
										}
										else{
											$reqAbsVac = "SELECT Id ,LibelleEN AS Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
														FROM rh_typeabsence 
														WHERE Suppr=0 
														AND DispoPourSalarie=0 
														AND DispoPourInterimaire=0
														AND Id<>10
														ORDER BY Libelle ";
										}
										$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
										$nbAbsVac=mysqli_num_rows($resultAbsVac);
										if ($nbAbsVac > 0){
											while($rowAbsVac=mysqli_fetch_array($resultAbsVac)){	
												echo "<option value='".$rowAbsVac['Id']."' >".$rowAbsVac['CodePlanning']." | ".$rowAbsVac['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
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
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureDebut" id="heureDebut" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?> : </td>
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