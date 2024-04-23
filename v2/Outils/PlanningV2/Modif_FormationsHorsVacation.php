<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeAbsence.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script language="javascript">
		function FermerEtRecharger(Menu,TDB,OngletTDB)
		{
			window.opener.location="Liste_FormationsHorsVacation.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['Enregistrer']))
	{
		//Modifier la vacation si selection d'une vacation 
		if ($_POST['vacationAbsence'] <> "0"){
			$req="UPDATE rh_personne_vacation
				SET Suppr=1,
				Id_Suppr=".$_SESSION['Id_Personne']." 
				AND DateSuppr='".date('Y-m-d')."' 
				WHERE Id_Prestation=".$_POST['Id_Prestation']."
				AND Id_Pole=".$_POST['Id_Pole']." 
				AND Id_Personne=".$_POST['Id_Personne']." 
				AND DateVacation='".$_POST['DateSession']."' ";
			$resultSuppr=mysqli_query($bdd,$req);
			
			//Ajout des nouvelles données vacation
			if(TravailCeJourDeSemaine($_POST['DateSession'],$_POST['Id_Personne'])<>""){
				$nbJ=0;
				$nbEJ=0;
				$nbEN=0;
				$nbPause=0;
				$nbForm=0;
				$nbFormETT=0;
				if($_POST['NbHeureJour']<>""){$nbJ=$_POST['NbHeureJour'];}
				if($_POST['NbHeureEquipeJour']<>""){$nbEJ=$_POST['NbHeureEquipeJour'];}
				if($_POST['NbHeureEquipeNuit']<>""){$nbEN=$_POST['NbHeureEquipeNuit'];}
				if($_POST['NbHeurePause']<>""){$nbPause=$_POST['NbHeurePause'];}
				if($_POST['NbHeureFormation']<>""){$nbForm=$_POST['NbHeureFormation'];}
				if($_POST['NbHeureFormationETT']<>""){$nbFormETT=$_POST['NbHeureFormationETT'];}
				$reqSuite=",".$nbJ.",".$nbEJ.",".$nbEN.",".$nbPause.",".$nbForm.",".$nbFormETT."";

			
				$requeteInsert="INSERT INTO rh_personne_vacation (Id_Personne, Id_Vacation, Id_Prestation, Id_Pole, DateVacation, DateCreation, Id_Createur,EmisParRH,Divers,NbHeureJour,NbHeureEquipeJour,NbHeureEquipeNuit,NbHeurePause,NbHeureFormation,NbHeureFormationETT)";
				$requeteInsert.=" VALUES ";
				$requeteInsert.="(".$_POST['Id_Personne'].",'".$_POST['vacationAbsence']."',".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",'".$_POST['DateSession']."','".date('Y-m-d')."',".$_SESSION['Id_Personne'].",1,'".addslashes($_POST['divers'])."'".$reqSuite.")";
				$resultAjout=mysqli_query($bdd,$requeteInsert);
			}
		}
		
		//Création des heures supplémentaires si selection d'heures
		if($_POST['Nb_Heures_Jour']<>0 || $_POST['Nb_Heures_Nuit']<>0){
			$requete="INSERT INTO rh_personne_hs 
				(HeuresFormation,Id_Prestation,Id_Pole,Id_Personne,Nb_Heures_Jour,Nb_Heures_Nuit,DateHS,Motif,
					Id_Responsable1,Date1,
					Id_Responsable2,Date2,Etat2,
					Id_Responsable3,Date3,Etat3,
					Id_Responsable4,Date4,Etat4,
					Id_RH,DateRH,DatePriseEnCompteRH,Avant25Mois) 
				VALUES 
				(1,".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",".$_POST['Id_Personne'].",".$_POST['Nb_Heures_Jour'].",".$_POST['Nb_Heures_Nuit'].",'".$_POST['DateSession']."','".addslashes($_POST['Motif'])."',
					0,'".date('Y-m-d')."',
					0,'".date('Y-m-d')."',1,
					0,'".date('Y-m-d')."',1,
					0,'".date('Y-m-d')."',1,
					".$_SESSION['Id_Personne'].",'".TrsfDate($_POST['Date'])."','".date('Y-m-d')."',1)
				";
			$resultAjout=mysqli_query($bdd,$requete);
			
			$requeteUpdate="UPDATE form_session_personne SET 
				Id_RH=".$_SESSION['Id_Personne'].",
				DatePriseEnCompteRH='".date('Y-m-d')."'
				WHERE Id=".$_POST['Id']." ";
			$resultat=mysqli_query($bdd,$requeteUpdate);
		}
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].",".$_POST['TDB'].",'".$_POST['OngletTDB']."');</script>";
}
$Menu=$_GET['Menu'];

$requete="SELECT form_session_personne.Id,
		form_session_date.DateSession,form_session_personne.Id_Personne,DatePriseEnCompteRH,
		Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause,
		(SELECT SUM(Nb_Heures_Jour+Nb_Heures_Nuit) AS Nb 
			FROM rh_personne_hs 
			WHERE rh_personne_hs.Suppr=0 
			AND rh_personne_hs.Id_Personne=form_session_personne.Id_Personne
			AND DateHS=DateSession) AS NbHeuresSupp,
		(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
		(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne,
		(SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
			FROM form_formation_langue_infos
			WHERE form_formation_langue_infos.Id_Formation=form_session.Id_Formation
			AND form_formation_langue_infos.Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=1
				AND Id_Formation=form_session.Id_Formation
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0) AS Formation 
		FROM
			form_session_date,
			form_session,
			form_session_personne
		WHERE
			form_session_date.Id_Session=form_session.Id
			AND form_session_date.Id_Session=form_session_personne.Id_Session
			AND form_session_date.Id=".$_GET['Id_SessionDate']."
			AND form_session_personne.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";

$Id_Presta=0;
$Id_Pole=0;
$Id_PrestationPole=PrestationPole_Personne($row['DateSession'],$row['Id_Personne']);
if($Id_PrestationPole<>0){
	$tabPresta=explode("_",$Id_PrestationPole);
	$Id_Presta=$tabPresta[0];
	$Id_Pole=$tabPresta[1];
}
?>

<form id="formulaire" class="test" action="Modif_FormationsHorsVacation.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $row['Id_Personne']; ?>" />
	<input type="hidden" name="DateSession" id="DateSession" value="<?php echo $row['DateSession']; ?>" />
	<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $Id_Presta; ?>" />
	<input type="hidden" name="Id_Pole" id="Id_Pole" value="<?php echo $Id_Pole; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $_GET['TDB']; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $_GET['OngletTDB']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
							<td width="20%">
								<?php echo $row['Personne']; ?>
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="20%">
								<?php echo stripslashes($row['Prestation']); ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"></td>
							<td>
							</td>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td >
								<?php echo stripslashes($row['Pole']); ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date session :";}else{echo "Date session :";} ?></td>
							<td>
								<?php 
									echo AfficheDateJJ_MM_AAAA($row['DateSession']);
								?>
							</td>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Formation :";}else{echo "Training :";} ?></td>
							<td colspan="3">
								<?php 
									echo stripslashes($row['Formation']);
								?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Vacation :";}else{echo "Vacation :";} ?></td>
							<td>
								<?php 
									$Travail=0;
									$bgcolor="";
									$type="";
									$laCouleur=TravailCeJourDeSemaine($row['DateSession'],$row['Id_Personne']);
									if($laCouleur<>""){
										$Travail=1;
										$type="J";
										$bgcolor="bgcolor='".$laCouleur."'";
									}
									//Vacation particulière
									$VacParticuliere=0;
									$Id_PrestationPole=PrestationPole_Personne($row['DateSession'],$row['Id_Personne']);
									if($Id_PrestationPole<>0){
										$tabPresta=explode("_",$Id_PrestationPole);
										$Id_Presta=$tabPresta[0];
										$Id_Pole=$tabPresta[1];
										
										$req="SELECT Id_Vacation,Id_Prestation,Id_Pole,DateVacation,
											rh_vacation.Nom,rh_vacation.Couleur
											FROM rh_personne_vacation 
											LEFT JOIN rh_vacation
											ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
											WHERE rh_personne_vacation.Suppr=0
											AND rh_personne_vacation.Id_Vacation>0
											AND rh_personne_vacation.Id_Personne=".$row['Id_Personne']."
											AND rh_personne_vacation.DateVacation>='".$row['DateSession']."' 
											AND rh_personne_vacation.DateVacation<='".$row['DateSession']."' 
											";
										$resultVac=mysqli_query($bdd,$req);
										$nbVac=mysqli_num_rows($resultVac);
										if($nbVac>0){
											mysqli_data_seek($resultVac,0);
											while($rowVac=mysqli_fetch_array($resultVac)){
												if($rowVac['Id_Prestation']==$Id_Presta && $rowVac['Id_Pole']==$Id_Pole && $rowVac['DateVacation']==$row['DateSession']){
													$type=$rowVac['Nom'];
													$bgcolor="bgcolor='".$rowVac['Couleur']."'";
													$VacParticuliere=1;
													break;
												}
											}
										}
									}
									//Absences
									if($Travail==1){
										$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
											(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
											(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
											FROM rh_absence 
											LEFT JOIN rh_personne_demandeabsence 
											ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
											WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
											AND rh_absence.DateFin>='".$row['DateSession']."' 
											AND rh_absence.DateDebut<='".$row['DateSession']."' 
											AND rh_personne_demandeabsence.Suppr=0 
											AND rh_absence.Suppr=0  
											AND rh_personne_demandeabsence.Conge=0 
											AND rh_personne_demandeabsence.EtatN1<>-1 
											AND rh_personne_demandeabsence.EtatN2<>-1
											ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
									$resultAbs=mysqli_query($bdd,$reqAbs);
									$nbAbs=mysqli_num_rows($resultAbs);
										if($nbAbs>0){
											mysqli_data_seek($resultAbs,0);
											while($rowAbs=mysqli_fetch_array($resultAbs)){
												if($rowAbs['DateDebut']<=$row['DateSession'] && $rowAbs['DateFin']>=$row['DateSession']){
													$bEtat="validee";
													if($rowAbs['TypeAbsenceDef']<>""){
														$type=$rowAbs['TypeAbsenceDef'];
														if($rowAbs['Id_TypeAbsenceDefinitif']==0){
															$bEtat="absInjustifiee";
															$type="ABS";
														}
													}
													else{
														$type=$rowAbs['TypeAbsenceIni'];
														if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtat="absInjustifiee";$type="ABS";}
													}
													break;
												}
											}
										}
									}
									
									//Congés
									$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
											rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
											(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
											(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
											FROM rh_absence 
											LEFT JOIN rh_personne_demandeabsence 
											ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
											WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
											AND rh_absence.DateFin>='".$row['DateSession']."' 
											AND rh_absence.DateDebut<='".$row['DateSession']."' 
											AND rh_personne_demandeabsence.Suppr=0 
											AND rh_absence.Suppr=0 
											AND rh_personne_demandeabsence.Annulation=0 
											AND rh_personne_demandeabsence.Conge=1 
											AND rh_personne_demandeabsence.EtatN1<>-1 
											AND rh_personne_demandeabsence.EtatN2<>-1
											ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
								$resultConges=mysqli_query($bdd,$reqConges);
								$nbConges=mysqli_num_rows($resultConges);
									if($nbConges>0){
										mysqli_data_seek($resultConges,0);
										while($rowConges=mysqli_fetch_array($resultConges)){
											if($rowConges['DateDebut']<=$row['DateSession'] && $rowConges['DateFin']>=$row['DateSession']){
												if($rowConges['TypeAbsenceDef']<>""){$type=$rowConges['TypeAbsenceDef'];}
												else{$type=$rowConges['TypeAbsenceIni'];}
												$bEtat="attenteValidation";
												if($rowConges['EtatN2']==1 && $rowConges['EtatRH']==1){$bEtat="validee";}
												break;
											}
										}
									}
									if($VacParticuliere==0){
										$jourFixe=estJour_Fixe($row['DateSession'],$_SESSION['Id_Personne']);
										if($jourFixe<>""){
											$bgcolor="bgcolor='".$Automatique."'";
											$type=$jourFixe;
										}
									}
									echo $type;
								?>
							</td>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb heures hors vacation :";}else{echo "Number of hours excluding vacation :";} ?></td>
							<td colspan="3">
								<?php 
									//Horaires de la personne
									$HeureDebutTravail="00:00:00";
									$HeureFinTravail="00:00:00";
									$tab=HorairesJournee($row['Id_Personne'],$row['DateSession']);
									if(sizeof($tab)>0){
										$HeureDebutTravail=$tab[0];
										$HeureFinTravail=$tab[1];
									}
									
									$nbHeureFormationHorsVac=date('H:i',strtotime($row['DateSession'].' 00:00:00'));
									$nbHeureFormation=date('H:i',strtotime($row['DateSession'].' 00:00:00'));
									if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
										//Nombre total d'heure de formation
										$hF=strtotime($row['Heure_Fin']);
										$hD=strtotime($row['Heure_Debut']);
										
										$hFP=strtotime($row['HeureFinPause']);
										$hDP=strtotime($row['HeureDebutPause']);
											
										$hFTravail=strtotime($HeureFinTravail);
										$hDTravail=strtotime($HeureDebutTravail);
										
										$valDebut=gmdate("H:i",$hD-$hD);
										$valHPause=gmdate("H:i",$hD-$hD);
										$valFin=gmdate("H:i",$hF-$hF);
										
										//Nombre d'heure hors début vacation 
										if($hFTravail<$hD || $hDTravail>$hF){
											$valDebut=gmdate("H:i",$hF-$hD);
											if($row['PauseRepas']==1){
												if($hDP<$hF && $hFP>$hD){
													if($hFP>$hF){$hFP=$hF;}
													if($hDP<$hD){$hDP=$hD;}
													$valPause=gmdate("H:i",$hFP-$hDP);
													$valDebut=gmdate("H:i",strtotime($valDebut)-strtotime($valPause));
												}
											}
										}
										else{
											if($hD<$hDTravail){
												if($hDP<$hDTravail && $row['PauseRepas']==1){
													$valDebut=gmdate("H:i",$hDP-$hD);
													if($hFP<$hDTravail){
														$valHPause=gmdate("H:i",$hDTravail-$hFP);
													}
												}
												else{
													$valDebut=gmdate("H:i",$hDTravail-$hD);
												}
											}
											if($hF>$hFTravail){
												if($hFP>$hFTravail && $row['PauseRepas']==1){
													$valDebut=gmdate("H:i",$hF-$hFP);
													if($hDP>$hFTravail){
														$valHPause=gmdate("H:i",$hDP-$hFTravail);
													}
												}
												else{
													$valFin=gmdate("H:i",$hF-$hFTravail);
												}
											}
										}

										$nbHeureFormHorsVacDebut=intval(date('H',strtotime($valDebut." + 0 hour"))).".".substr((date('i',strtotime($valDebut." + 0 hour"))/0.6),0,2);
										$nbHeureFormHorsVacAvantPause=intval(date('H',strtotime($valHPause." + 0 hour"))).".".substr((date('i',strtotime($valHPause." + 0 hour"))/0.6),0,2);
										$nbHeureFormHorsVacFin=intval(date('H',strtotime($valFin." + 0 hour"))).".".substr((date('i',strtotime($valFin." + 0 hour"))/0.6),0,2);
										
										$nbHeureFormationHorsVac=$nbHeureFormHorsVacDebut+$nbHeureFormHorsVacAvantPause+$nbHeureFormHorsVacFin;
									}
									echo $nbHeureFormationHorsVac;
								?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heures supp. déclarées :";}else{echo "Overtime declared :";} ?></td>
							<td>
							<?php
								$HS=0;
								//Verif si HS pendant ce créneau
								$reqHS="SELECT SUM(Nb_Heures_Jour+Nb_Heures_Nuit) AS Nb 
										FROM rh_personne_hs 
										WHERE Suppr=0 
										AND Etat2<>-1
										AND Etat3<>-1
										AND Etat4<>-1
										AND Id_Personne=".$row['Id_Personne']." 
										AND DateHS='".$row['DateSession']."' ";
								$resultHS=mysqli_query($bdd,$reqHS);
								$nbHS=mysqli_num_rows($resultHS);
								if($nbHS>0){
									$rowHS=mysqli_fetch_array($resultHS);
									if($rowHS['Nb']<>""){$HS=$rowHS['Nb'];}
								}
								echo $HS;
							?>
							</td>
						</tr>
						<tr>
							<td height="10"></td>
						</tr>
						<tr>
							<td class="Libelle" style="color:#5f1667;font-weight:bold;" colspan="3"><?php if($_SESSION["Langue"]=="FR"){echo "DECLARER UNE VACATION";}else{echo "DECLARE A VACATION";} ?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nouvelle vacation :";}else{echo "New vacation :";} ?></td>
							<td>
								<select id="vacationAbsence" name="vacationAbsence">
									<option value='0' selected></option>
									<?php
									if($_SESSION["Langue"]=="FR"){
										$reqVac = "SELECT Id ,Nom, Libelle FROM rh_vacation WHERE Suppr=0 ORDER BY Nom ";
									}
									else{
										$reqVac = "SELECT Id ,Nom, LibelleEN As Libelle FROM rh_vacation WHERE Suppr=0 ORDER BY Nom ";
									}
									
									$resultVac=mysqli_query($bdd,$reqVac);
									$nbVac=mysqli_num_rows($resultVac);
									if ($nbVac > 0){
										while($rowVac=mysqli_fetch_array($resultVac))
										{	
											$Selected = "";
											if($_POST){if($_POST['vacationAbsence']==$rowVac['Id']){$Selected="selected";}}
											echo "<option value='".$rowVac['Id']."' ".$Selected.">".$rowVac['Nom']." | ".$rowVac['Libelle']."</option>";
											
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td height="4"></td>
						</tr>
						<tr>
							<td class="Libelle">
								<?php if($_SESSION["Langue"]=="FR"){echo "Divers :";}else{echo "Diverse :";} ?>
							</td>
							<td colspan="3">
								<textarea name="divers" rows=3 cols=80 resize="none"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="4" style="color:red;">
							<?php 
								if($_SESSION["Langue"]=="FR"){
									echo "Attention : Si un nombre d'heure est défini ci-dessous, seule cette information sera prise en compte au moment du pointage.";
								}
								else{
									echo "Warning: If a number of hours is defined below, only this information will be taken into account at the time of the clocking.";
								} 
							?>
							</td>
						</tr>
						<tr>
							<td class="Libelle">Nb heures Jour : </td>
							<td><input onKeyUp="nombre(this)" name="NbHeureJour" size="10" type="text" value= ""></td>
						</tr>
						<tr>
							<td class="Libelle">Nb heures Formation : </td>
							<td><input onKeyUp="nombre(this)" name="NbHeureFormation" size="10" type="text" value= ""></td>
						</tr>
						<tr>
							<td class="Libelle">Nb heures Formation payées par ETT : </td>
							<td><input onKeyUp="nombre(this)" name="NbHeureFormationETT" size="10" type="text" value= ""></td>
						</tr>
						<tr>
							<td class="Libelle">Nb heures Equipe Jour : </td>
							<td><input onKeyUp="nombre(this)" name="NbHeureEquipeJour" size="10" type="text" value= ""></td>
						</tr>
						<tr>
							<td class="Libelle">Nb heures Equipe Nuit : </td>
							<td><input onKeyUp="nombre(this)" name="NbHeureEquipeNuit" size="10" type="text" value= ""></td>
						</tr>
						<tr>
							<td class="Libelle">Nb heures Pause : </td>
							<td><input onKeyUp="nombre(this)" name="NbHeurePause" size="10" type="text" value= ""></td>
						</tr>
						<tr>
							<td height="10"></td>
						</tr>
						<tr>
							<td class="Libelle" style="color:#5f1667;font-weight:bold;" colspan="3"><?php if($_SESSION["Langue"]=="FR"){echo "DECLARER DES HEURES SUPPLEMENTAIRES";}else{echo "DECLARE ADDITIONAL HOURS";} ?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb Heures jour (6h->21h) :";}else{echo "Nb hours day (6h-> 21h):";} ?></td>
							<td>
								<select name="Nb_Heures_Jour" id="Nb_Heures_Jour">
									<?php
									for($h=0;$h<=15;$h+=0.25)
									{
										echo "<option value='".$h."'>";
										echo $h."</option>";
									}
									?>
								</select>
							</td>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb Heures nuit (21h->6h) :";}else{echo "Nb Hours night (21h-> 6h) :";} ?></td>
							<td>
								<select name="Nb_Heures_Nuit" id="Nb_Heures_Nuit">
									<?php
									for($h=0;$h<=15;$h+=0.25)
									{
										echo "<option value='".$h."'>";
										echo $h."</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Motif :";}else{echo "Motif :";} ?></td>
							<td width="30%" colspan="6">
								<textarea name="Motif" cols="80" rows="4" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td colspan='6' align='center'>
								<input class='Bouton' type='submit' name='Enregistrer' value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";} ?>" />
							</td>
						</tr>
							<?php
						?>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>