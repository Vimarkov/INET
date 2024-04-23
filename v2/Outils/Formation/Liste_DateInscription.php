<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Date Inscription</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<script type="text/javascript">
	function Inscrire(Id)
		{
			var w = window.open("InscrireSessionAF_CE_CQP.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&Id_Prestation="+document.getElementById('Id_Prestation').value+"&Id_Session="+Id,"PageSession","status=no,menubar=no,width=800,height=800");
			w.focus();
		}
	</script>
</head>


<form id="formulaire" action="Liste_Besoin_Formation.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<input type="hidden" name="id" value="<?php echo $_GET['Id_Besoin']; ?>"/>
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Sessions de formations disponibles";}else{echo "Available training sessions";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width:100%;" valign="top">
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td style="height:4px;"></td>
				</tr>
				<?php
					$req="SELECT form_besoin.Id, 
						form_besoin.Id_Formation,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
						new_competences_prestation.Libelle AS Prestation,
						new_competences_prestation.Id AS Id_Prestation,
						form_besoin.Id_Pole,
						new_competences_prestation.Id_Plateforme,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
						(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Organisme,
						(SELECT Id_Langue FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Id_Langue,
						IF(form_besoin.Motif='Renouvellement',1,0) AS Recyclage,
						(SELECT IF(form_besoin.Motif='Renouvellement',IF(form_formation.Recyclage=1,LibelleRecyclage,Libelle),Libelle)
							FROM form_formation_langue_infos
							WHERE Id_Formation=form_besoin.Id_Formation
							AND Id_Langue=
								(SELECT Id_Langue 
								FROM form_formation_plateforme_parametres 
								WHERE form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme
								AND Id_Formation=form_besoin.Id_Formation
								AND Suppr=0 
								LIMIT 1)
							AND Suppr=0) AS Formation
						FROM form_besoin,
						new_competences_prestation,
						form_formation
						WHERE form_besoin.Id=".$_GET['Id_Besoin']."
						AND form_besoin.Id_Formation=form_formation.Id
						AND form_besoin.Id_Prestation=new_competences_prestation.Id ";

						$resultBesoin=mysqli_query($bdd,$req);
						$rowB=mysqli_fetch_array($resultBesoin);
				?>
				<input type="hidden" name="Id_Plateforme" id="Id_Plateforme" value="<?php echo $rowB['Id_Plateforme']; ?>"/>
				<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $rowB['Id_Prestation']."_".$rowB['Id_Pole']; ?>"/>
				<tr>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?>
					</td>
					<td style="width:20%;">
						<?php echo $rowB['Personne']; ?>
					</td>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?>
					</td>
					<td style="width:60%;">
						<?php echo $rowB['Prestation']; 
							if($rowB['Pole']<>""){
								echo " - ".$rowB['Pole'];
							}
						?>
					</td>
				</tr>
				<tr>
					<td style="height:4px;"></td>
				</tr>
				<tr>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Initiale / Recyclage";}else{echo "Initial / Recycling";}?>
					</td>
					<td style="width:20%;">
							<?php
							if($rowB['Recyclage']==0){
								if($LangueAffichage=="FR"){echo "Initiale";}else{echo "Initial";}
							}
							else{
								if($LangueAffichage=="FR"){echo "Recyclage";}else{echo "Recycling";}
							}
							?>
					</td>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?>
					</td>
					<td style="width:60%;">
						<?php echo $rowB['Formation']; 
							if($rowB['Organisme']<>""){
								echo " (".$rowB['Organisme'].")";
							}
						?>
					</td>
				</tr>
				<tr>
					<td style="width:10px"></td>
				</tr>
				<tr>
					<td colspan="4">
						<table style="width:100%">
				<?php						
					//Afficher la liste des futurs sessions des prestations dont est responsable la personne connecté 
					$reqF="
						SELECT
							form_session_date.Id AS Id_SessionDate,
							form_session_date.Id_Session,
							form_session_date.DateSession,
							form_session_date.Heure_Debut,
							form_session_date.Heure_Fin,
							form_session_date.PauseRepas,
							form_session_date.HeureDebutPause,
							form_session_date.HeureFinPause,
							form_session.Id_GroupeSession,
							form_session.Id_Formation,
							form_session.Formation_Liee,
							form_session.Nb_Stagiaire_Maxi,
							form_session.MessageInscription,
							form_session.Recyclage,
							(SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Recyclage2
						FROM
							form_session_date
						LEFT JOIN form_session
							ON form_session_date.Id_Session=form_session.Id
						WHERE
							form_session_date.Suppr=0
							AND form_session_date.DateSession>'".date('Y-m-d')."'
							AND form_session.Suppr=0
							AND form_session.Annule=0
							AND form_session.Diffusion_Creneau=1
							AND (
								(SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=0
								OR
								((SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=1
								AND form_session.Recyclage=".$rowB['Recyclage']."
								)
								)
							AND (form_session.Id_Formation=".$rowB['Id_Formation']."
									OR form_session.Id_Formation IN  (SELECT Id_Formation 
								FROM form_formationequivalente_formationplateforme 
								WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
								FROM form_formationequivalente_formationplateforme 
								WHERE Id_Formation=".$rowB['Id_Formation']."))
							
							)
						
							AND form_session_date.Id_Session IN (SELECT Id_Session FROM form_session_prestation WHERE Suppr=0 AND Id_Prestation=".$rowB['Id_Prestation'].")
						ORDER BY
							form_session_date.DateSession ";

					$resultSession=mysqli_query($bdd,$reqF);
			
					$resultAutresSessions=mysqli_query($bdd,$reqF);

					$nbSession=mysqli_num_rows($resultSession);
					$tabForm=array();
					$itab=0;
					if($nbSession>0)
					{
						while($rowSessionDate=mysqli_fetch_array($resultSession))
						{
							$bExiste=0;
							for($k=0;$k<=(sizeof($tabForm)-1);$k++)
							{
								if($tabForm[$k]==$rowSessionDate['Id_SessionDate']){$bExiste=1;}
							}
							if($bExiste==0)
							{
								//Liste des prestations ayant accès
								$valId="";
								$tabSession="";
								$bValide=1;
								if($rowSessionDate['Id_GroupeSession']>0 && $rowSessionDate['Formation_Liee']==1)
								{
									//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
									$req="
										SELECT
											form_session_date.Id
										FROM
											form_session_date
										LEFT JOIN form_session
											ON form_session_date.Id_Session=form_session.Id
										WHERE
											form_session_date.DateSession<='".date('Y-m-d')."'
											AND form_session_date.Suppr=0
											AND form_session.Suppr=0
											AND form_session.Annule=0
											AND form_session.Diffusion_Creneau=1
											AND form_session.Id_GroupeSession=".$rowSessionDate['Id_GroupeSession'];
									$resultDepasse=mysqli_query($bdd,$req);
									$nbDepasse=mysqli_num_rows($resultDepasse);
									if($nbDepasse>0){$bValide=0;}
								}
								else
								{
									//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
									$req="
										SELECT
											form_session_date.Id
										FROM
											form_session_date
										LEFT JOIN form_session
											ON form_session_date.Id_Session=form_session.Id
										WHERE
											form_session_date.DateSession<='".date('Y-m-d')."'
											AND form_session_date.Suppr=0
											AND form_session.Suppr=0
											AND form_session.Annule=0
											AND form_session.Diffusion_Creneau=1
											AND form_session_date.Id_Session=".$rowSessionDate['Id_Session'];
									$resultDepasse=mysqli_query($bdd,$req);
									$nbDepasse=mysqli_num_rows($resultDepasse);
								}
								if($nbDepasse>0){$bValide=0;}
								if($bValide==1)
								{
									//Vérifier si la session est sur plusieurs dates ou groupe de formation lié
									mysqli_data_seek($resultAutresSessions,0);
									while($rowSessionAutresDates=mysqli_fetch_array($resultAutresSessions))
									{
										if($rowSessionAutresDates['Id_Session']==$rowSessionDate['Id_Session'] || ($rowSessionDate['Formation_Liee']==1 && $rowSessionAutresDates['Id_GroupeSession']<>0 && $rowSessionAutresDates['Id_GroupeSession']==$rowSessionDate['Id_GroupeSession']))
										{
											$tabSession.= "<tr><td><a href='javascript:Inscrire(".$rowSessionDate['Id_Session'].");'>".AfficheDateJJ_MM_AAAA($rowSessionAutresDates['DateSession'])." (".substr($rowSessionAutresDates['Heure_Debut'],0,-3)."-".substr($rowSessionAutresDates['Heure_Fin'],0,-3).")</a></td></tr>";
											$tabForm[$itab]=$rowSessionAutresDates['Id_SessionDate'];
											$IniR="I";
											if($rowSessionAutresDates['Recyclage']=="1"){$IniR="R";}
											$valId.="Form".$rowSessionAutresDates['Id_Formation']."_".$IniR.";";
											$itab++;
										}
									}

									echo "<tr class='ListeSession' id='".$valId."' >";
									echo "<td width='3%' valign='center'>&bull;</td>";
									echo "<td width='35%' valign='top'><table width='100%'>";
									echo $tabSession;
									echo "</table></td>";
									//Places restantes
									$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSessionDate['Id_Session'];
									$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
									$nbInscrit=mysqli_num_rows($resultNbInscrit);
									$groupeFormation="";
									if($rowSessionDate['Formation_Liee']==1)
									{
										if($LangueAffichage=="FR")
										{
											$groupeFormation="<img width='15px' src='../../Images/attention.png' />&nbsp;Cette session contient plusieurs formations.<br>La présence à toutes les formations est obligatoire";
										}
										else
										{
											$groupeFormation="<img width='15px' src='../../Images/attention.png' />&nbsp;This session contains several trainings<br>Attendance at all courses is mandatory";
										}
									}
									if($rowSessionDate['Nb_Stagiaire_Maxi']-$nbInscrit<0){$nbInscrit=0;}
									else{$nbInscrit=$rowSessionDate['Nb_Stagiaire_Maxi']-$nbInscrit;}
									echo "<td width='62%'>";
									echo "<table>";
									echo "<tr><td>";
									if($rowSessionDate['MessageInscription']<>""){
										echo "<span style='color:red;'>".stripslashes($rowSessionDate['MessageInscription'])."</span><br>";
									}
									echo "(Places restantes :".($nbInscrit)."/".$rowSessionDate['Nb_Stagiaire_Maxi'].")</td></tr>";
									echo "<tr><td>".$groupeFormation."</td></tr>";
									echo "</table>";
									echo "</tr>";
								}
							}
						}
					}
				?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>