<?php
require("../../Menu.php");

$EnAttente="#ffbf03";
$TransmisRH="#449ef0";
$Automatique="#3d9538";
$Validee="#6beb47";
$Refusee="#ff5353";
$Gris="#dddddd";
$AbsenceInjustifies="#ff0303";

$Id_Personne=$_SESSION['FiltreRHPlanning2_Personne'];
if(isset($_POST['Id_Personne'])){$Id_Personne=$_POST['Id_Personne'];}
$_SESSION['FiltreRHPlanning2_Personne']=$Id_Personne;


$annee=$_SESSION['FiltreRHPlanning2_Annee'];
if(isset($_POST['annee'])){$annee=$_POST['annee'];}
if($annee==""){$annee=date("Y");}
$_SESSION['FiltreRHPlanning2_Annee']=$annee;

$Debut=date("Y-m-d",mktime(0,0,0,1,1,$_SESSION['FiltreRHPlanning2_Annee']));
$Fin=date("Y-m-d",mktime(0,0,0,13,0,$_SESSION['FiltreRHPlanning2_Annee']));

$tmpDate=date("Y-m-d",mktime(0,0,0,1,1,$_SESSION['FiltreRHPlanning2_Annee']));

//Liste des congés
$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
		rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
		rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
		(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
		(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_N1) AS Resp1,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_N2) AS Resp2,
		Commentaire1,Commentaire2,
		(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS Refus1,
		(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS Refus2
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
$resultConges=mysqli_query($bdd,$reqConges);
$nbConges=mysqli_num_rows($resultConges);

//Liste des absences
$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
			rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
			(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
			(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_N1) AS Resp1,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_N2) AS Resp2,
			Commentaire1,Commentaire2,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS Refus1,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS Refus2
			FROM rh_absence 
			LEFT JOIN rh_personne_demandeabsence 
			ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			WHERE rh_personne_demandeabsence.Id_Personne=".$Id_Personne." 
			AND rh_absence.DateFin>='".$Debut."' 
			AND rh_absence.DateDebut<='".$Fin."' 
			AND rh_personne_demandeabsence.Suppr=0 
			AND rh_absence.Suppr=0  
			AND rh_personne_demandeabsence.Conge=0 
			ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
$resultAbs=mysqli_query($bdd,$reqAbs);
$nbAbs=mysqli_num_rows($resultAbs);

//Liste des heures supplémentaires
$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,Etat2,Etat3,Etat4,
			IF(
				rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1,
				1,
				IF(
					rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1,
					2,
					IF(
						rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01',
						3,
						IF(
							rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1,
							4,
							5
						)
					)
				)
			)
			AS Etat,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Responsable2) AS Resp2,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Responsable3) AS Resp3,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Responsable4) AS Resp4,
		Commentaire2,Commentaire3,Commentaire4,
		(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS Refus2,
		(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN3) AS Refus3,
		(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN4) AS Refus4
		FROM rh_personne_hs
		WHERE Suppr=0 
		AND Id_Personne=".$Id_Personne." 
		AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$Debut."' 
		AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$Fin."' 
		";
$resultHS=mysqli_query($bdd,$req);
$nb2HS=mysqli_num_rows($resultHS);
	
$req="SELECT IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte) AS DateAstreinte,EtatN1,EtatN2,
	IF(
		rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1,
		1,
		IF(
			rh_personne_rapportastreinte.DateValidationRH<='0001-01-01' AND rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.EtatN1=1,
			2,
			IF(
				rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.DateValidationRH>'0001-01-01',
				3,
				IF(
					rh_personne_rapportastreinte.EtatN2=-1 OR rh_personne_rapportastreinte.EtatN1=-1,
					4,
					5
				)
			)
		)
	) AS Etat,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_ValidateurN1) AS Resp1,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_ValidateurN2) AS Resp2,
RaisonRefusN1,RaisonRefusN2,
(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS Refus1,
(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS Refus2,
TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,Montant,Intervention
FROM rh_personne_rapportastreinte
WHERE rh_personne_rapportastreinte.Suppr=0
AND rh_personne_rapportastreinte.Id_Personne=".$Id_Personne."
AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$Debut."' 
AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)<='".$Fin."' 
AND EtatN1<>-1
AND EtatN2<>-1
	";

$resultAst=mysqli_query($bdd,$req);
$nbAst=mysqli_num_rows($resultAst);

//Liste des vacations différentes
$req="SELECT Id_Vacation,Id_Prestation,Id_Pole,DateVacation,Divers,
	rh_vacation.Nom,rh_vacation.Couleur
	FROM rh_personne_vacation 
	LEFT JOIN rh_vacation
	ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
	WHERE rh_personne_vacation.Suppr=0
	AND rh_personne_vacation.Id_Vacation>0
	AND rh_personne_vacation.Id_Personne=".$Id_Personne."
	AND rh_personne_vacation.DateVacation>='".$Debut."' 
	AND rh_personne_vacation.DateVacation<='".$Fin."' 
	";
$resultVac=mysqli_query($bdd,$req);
$nbVac=mysqli_num_rows($resultVac);

//Formation dans l'outil formation 
$req="  SELECT
			form_session_date.DateSession,
			Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause
		FROM
			form_session_date 
			LEFT JOIN form_session 
			ON form_session_date.Id_Session=form_session.Id
		WHERE
			form_session_date.Suppr=0 
			AND form_session.Suppr=0
			AND form_session.Annule=0 
			AND form_session_date.DateSession>='".$Debut."'
			AND form_session_date.DateSession<='".$Fin."'
			AND
			(
				SELECT
					COUNT(form_session_personne.Id) 
				FROM
					form_session_personne
				WHERE
					form_session_personne.Suppr=0
					AND form_session_personne.Id_Personne=".$Id_Personne." 
					AND form_session_personne.Validation_Inscription=1
					AND form_session_personne.Id_Session=form_session.Id
					AND Presence IN (0,1)
		   )>0 ";
$resultSession=mysqli_query($bdd,$req);
$nbSession=mysqli_num_rows($resultSession);

//VM
$req="  SELECT DateVisite,HeureVisite, DATE_ADD(HeureVisite, INTERVAL 2 HOUR) AS HeureFin
		FROM rh_personne_visitemedicale
		WHERE Suppr=0 
		AND DateVisite>='".$Debut."'
		AND DateVisite<='".$Fin."'
		AND Id_Personne=".$Id_Personne." ";
$resultVM=mysqli_query($bdd,$req);
$nbVM=mysqli_num_rows($resultVM);

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

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

?>

<form action="PlanningPersonnel2.php" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="10">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#77c39a;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Planning / Personne";}else{echo "Planning / Person";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
				<td width="30%" class="Libelle" align="center"><?php if($_SESSION["Langue"]=="FR"){echo "Personne concernée : ";}else{echo "Concerned person : ";} ?>
				&nbsp;<select name="Id_Personne" id="Id_Personne" onchange="submit();">
						<option value="0"></option>
						<?php
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date("Y-m-d",strtotime(date('Y-m-d')." -3 month"))."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Id_Prestation<>0
							AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN (1) 
							OR 
							(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteResponsablePlateforme.")
							)
							)
							ORDER BY Personne ASC";
							
							$resultPersonne=mysqli_query($bdd,$requetePersonne);
							$Id_Personne=0;
							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								$selected="";
								if($_POST){if($_POST['Id_Personne']==$rowPersonne['Id']){$selected="selected";$Id_Personne=$rowPersonne['Id'];}}
								echo "<option value='".$rowPersonne['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPersonne['Personne']))."</option>\n";
							}
						?>
					</select>
				</td>
				<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
				&nbsp;&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer(5);"/> 
				<div id="filtrer"></div>
				</td></tr>
				<tr><td height="4"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
<?php 
if($Id_Personne>0){
for($i=1;$i<=12;$i++){
	if($i==1 || $i==4 || $i==7 || $i==10){echo "<tr>";}
		echo "<td align='center'>";
			echo "<table style='border:1px solid #787878;' width='85%' cellpadding='0' cellspacing='0'>";
				$tabDate = explode('-', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$mois = $tabDate[1];
				
				$leDebut=date("Y-m-d",mktime(0,0,0,$mois,1,$_SESSION['FiltreRHPlanning2_Annee']));
				$laFin=date("Y-m-d",mktime(0,0,0,$mois+1,0,$_SESSION['FiltreRHPlanning2_Annee']));
				

				echo "<tr><td class='cEnTete' colspan='8' align='center'>".$MoisLettre[$mois-1]." ".$tabDate[0]." </td></tr>";

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
						$bEtat="rien";
						$type="";
						$ValidateurRefus="";
						$MotifRefus="";
						$CommentaireRefus="";
						$bEtat="rien";
						$type="";	
						$Divers="";		
						$IndiceAbs="";
						$NbHeureAbsJour=0;
						$NbHeureAbsNuit=0;
						$estUneVacation=0;
						$nbHS=0;
						$indice=" ";
						$nbHeureFormationVac=date('H:i',strtotime($dateMois.' 00:00:00'));
						$nbHeureFormation=date('H:i',strtotime($dateMois.' 00:00:00'));
						$nbHeureVMVac=date('H:i',strtotime($dateMois.' 00:00:00'));
						$nbHeureVM=date('H:i',strtotime($dateMois.' 00:00:00'));
						
						if($colonne==0){
							echo "<td class='numSemaine'>".$semaine."</td>";
						}
						else{
							//Vacation contrat
							$bgcolor="";
							$Travail=0;
							$laCouleur=TravailCeJourDeSemaine($dateMois,$Id_Personne);
							if($laCouleur<>""){
								$Travail=1;
								$type="J";
								$bgcolor="bgcolor='".$laCouleur."'";
							}
							
							$estUneVacation=1;
							
							//Vacation particulière
							$VacParticuliere=0;
							$Id_PrestationPole=PrestationPole_Personne($dateMois,$Id_Personne);
							if($Id_PrestationPole<>0){
								$tabPresta=explode("_",$Id_PrestationPole);
								$Id_Presta=$tabPresta[0];
								$Id_Pole=$tabPresta[1];
								if($nbVac>0){
									mysqli_data_seek($resultVac,0);
									while($rowVac=mysqli_fetch_array($resultVac)){
										if($rowVac['Id_Prestation']==$Id_Presta && $rowVac['Id_Pole']==$Id_Pole && $rowVac['DateVacation']==$dateMois){
											$type=$rowVac['Nom'];
											$Divers=$rowVac['Divers'];	
											$bgcolor="bgcolor='".$rowVac['Couleur']."'";
											$VacParticuliere=1;
											break;
										}
									}
								}
							}
							//Absences
							if($Travail==1){
								if($nbAbs>0){
									mysqli_data_seek($resultAbs,0);
									while($rowAbs=mysqli_fetch_array($resultAbs)){
										if($rowAbs['DateDebut']<=$dateMois && $rowAbs['DateFin']>=$dateMois){
											$bEtat="validee";
											if($rowAbs['NbHeureAbsJour']<>0 || $rowAbs['NbHeureAbsNuit']<>0){
												$NbHeureAbsJour=$rowAbs['NbHeureAbsJour'];
												$NbHeureAbsNuit=$rowAbs['NbHeureAbsNuit'];
												if($rowAbs['TypeAbsenceDef']<>""){
													$IndiceAbs=$rowAbs['TypeAbsenceDef']." ";
													if($rowAbs['Id_TypeAbsenceDefinitif']==0){
														$bEtat="absInjustifiee";
														$IndiceAbs=" ABS ";
													}
												}
												else{
													$IndiceAbs=$rowAbs['TypeAbsenceIni']." ";
													if($rowAbs['Id_TypeAbsenceInitial']==0){
														$bEtat="absInjustifiee";
														$IndiceAbs=" ABS ";
													}
												}
											}
											else{
												$estUneVacation=0;
												if($rowAbs['TypeAbsenceDef']<>""){
													$type=$rowAbs['TypeAbsenceDef'];
													if($rowAbs['Id_TypeAbsenceDefinitif']==0){
														$bEtat="absInjustifiee";
														$type="ABS";
													}
													$VacParticuliere=1;
												}
												else{
													$type=$rowAbs['TypeAbsenceIni'];
													if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtat="absInjustifiee";$type="ABS";$VacParticuliere=1;}
												}
											}
											break;
										}
									}
								}
							}
							
							//Congés
							if($nbConges>0){
								mysqli_data_seek($resultConges,0);
								while($rowConges=mysqli_fetch_array($resultConges)){
									if($rowConges['DateDebut']<=$dateMois && $rowConges['DateFin']>=$dateMois){
										$IndiceAbs="";
										$NbHeureAbsJour=0;
										$NbHeureAbsNuit=0;
										if($rowConges['NbHeureAbsJour']<>0 || $rowConges['NbHeureAbsNuit']<>0){
											$NbHeureAbsJour=$rowConges['NbHeureAbsJour'];
											$NbHeureAbsNuit=$rowConges['NbHeureAbsNuit'];
											if($rowConges['TypeAbsenceDef']<>""){
												$IndiceAbs=" ".$rowConges['TypeAbsenceDef']." ";
											}
											else{
												$IndiceAbs=" ".$rowConges['TypeAbsenceIni']." ";
											}
										}
										else{
											$estUneVacation=0;
											if($rowConges['TypeAbsenceDef']<>""){$type=$rowConges['TypeAbsenceDef'];}
											else{$type=$rowConges['TypeAbsenceIni'];}
											$VacParticuliere=1;
										}
										$bEtat="attenteValidation";
										if($rowConges['EtatN1']==-1 || $rowConges['EtatN2']==-1){
											$bEtat="refusee";
											if($rowConges['EtatN1']==-1){
												$ValidateurRefus=$rowConges['Resp1'];
												$MotifRefus=$rowConges['Refus1'];
												$CommentaireRefus=$rowConges['Commentaire1'];
											}
											elseif($rowConges['EtatN2']==-1){
												$ValidateurRefus=$rowConges['Resp2'];
												$MotifRefus=$rowConges['Refus2'];
												$CommentaireRefus=$rowConges['Commentaire2'];
											}
										}
										elseif($rowConges['EtatN2']==1 && $rowConges['EtatRH']==1){$bEtat="validee";}
										elseif($rowConges['EtatN2']==1 && $rowConges['EtatRH']==0){$bEtat="TransmisRH";}
										break;
									}
								}
							}
							
							//Astreintes
							$valAstreinte="";
							if($nbAst>0){
								mysqli_data_seek($resultAst,0);
								while($rowAst=mysqli_fetch_array($resultAst)){
									if($rowAst['DateAstreinte']==$dateMois){
										$valAstreinte.=" AS";
										$nbHeures="0h ";
										if($rowAst['Intervention']==1){
											$nbHeures=Ajouter_Heures($rowAst['DiffHeures1'],$rowAst['DiffHeures2'],$rowAst['DiffHeures3']);
											$tabHeure=explode(".",$nbHeures);
											if(sizeof($tabHeure)==2){
												$valAstreinte.=" ".$tabHeure[0].".".round(($tabHeure[1]/60)*100,0);
											}
											else{
												$valAstreinte.=" ".$tabHeure[0];
											}
											
										}
										$bEtat="attenteValidation";
										if($rowAst['Etat']==4){
											$bEtat="refusee";
											if($rowAst['EtatN1']==-1){
												$ValidateurRefus=$rowAst['Resp1'];
												$MotifRefus=$rowAst['Refus1'];
												$CommentaireRefus=$rowAst['RaisonRefusN1'];
											}
											elseif($rowAst['EtatN2']==-1){
												$ValidateurRefus=$rowAst['Resp2'];
												$MotifRefus=$rowAst['Refus2'];
												$CommentaireRefus=$rowAst['RaisonRefusN2'];
											}
										}
										elseif($rowAst['Etat']==3){$bEtat="validee";}
										elseif($rowAst['Etat']==2){$bEtat="TransmisRH";}
										break;
									}
								}
							}

							//HS
							if($nb2HS>0){
								mysqli_data_seek($resultHS,0);
								while($rowHS=mysqli_fetch_array($resultHS)){
									if($rowHS['DateHS']==$dateMois){
										$nbHS+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
										if($type<>""){$type.="+";}
										if($_SESSION["Langue"]=="FR"){$type.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."HS";}
										else{$type.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."OT";}
										if($bEtat<>"refusee"){
											$bEtat="attenteValidation";
										}
										if($rowHS['Etat']==4){
											$bEtat="refusee";
											if($rowHS['Etat2']==-1){
												$ValidateurRefus=$rowHS['Resp2'];
												$MotifRefus=$rowHS['Refus2'];
												$CommentaireRefus=$rowHS['Commentaire2'];
											}
											elseif($rowHS['Etat3']==-1){
												$ValidateurRefus=$rowHS['Resp3'];
												$MotifRefus=$rowHS['Refus3'];
												$CommentaireRefus=$rowHS['Commentaire3'];
											}
											elseif($rowHS['Etat4']==-1){
												$ValidateurRefus=$rowHS['Resp4'];
												$MotifRefus=$rowHS['Refus4'];
												$CommentaireRefus=$rowHS['Commentaire4'];
											}
										}
										elseif($rowHS['Etat']==3 && $bEtat<>"refusee"){$bEtat="validee";}
										elseif($rowHS['Etat']==2 && $bEtat<>"refusee"){$bEtat="TransmisRH";}
									}
								}
							}
							
							//Horaires de la personne
							$HeureDebutTravail="00:00:00";
							$HeureFinTravail="00:00:00";

							$tab=HorairesJournee($Id_Personne,$dateMois);
							if(sizeof($tab)>0){
								$HeureDebutTravail=$tab[0];
								$HeureFinTravail=$tab[1];
							}

							
							if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){
								//Formation 
								if($nbSession>0){
									$bTrouve=0;
									mysqli_data_seek($resultSession,0);
									while($rowForm=mysqli_fetch_array($resultSession)){
										if($rowForm['DateSession']==$dateMois){
											//Nombre total d'heure de formation
											$hF=strtotime($rowForm['Heure_Fin']);
											$hD=strtotime($rowForm['Heure_Debut']);
											$val=gmdate("H:i",$hF-$hD);
											$bTrouve=1;
											if($rowForm['PauseRepas']==1){
												$hFP=strtotime($rowForm['HeureFinPause']);
												$hDP=strtotime($rowForm['HeureDebutPause']);
												if($hDP<$hF && $hFP>$hD){
													if($hFP>$hF){$hFP=$hF;}
													if($hDP<$hD){$hDP=$hD;}
													$valPause=gmdate("H:i",$hFP-$hDP);
													$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
												}
											}
											
											$nbHeureFormation=date('H:i',strtotime($nbHeureFormation." ".str_replace(":"," hour ",$val)." minute"));

											//Nombre d'heure pendant la vacation 
											if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";}
											$hFTravail=strtotime($HeureFinTravail);
											$hDTravail=strtotime($HeureDebutTravail);
											if($hDTravail>$hD || $hFTravail<$hF){
												if($hFTravail<$hF){$hF=$hFTravail;}
												if($hDTravail>$hD){$hD=$hDTravail;}
											}
											$val=gmdate("H:i",$hF-$hD);
											
											if($hDTravail>$hF || $hFTravail<$hD){
												$hF=0;
												$hD=0;
												$val=0;
											}
											
											if($hD<>0 && $hF<>0){
												if($rowForm['PauseRepas']==1){
													$hFP=strtotime($rowForm['HeureFinPause']);
													$hDP=strtotime($rowForm['HeureDebutPause']);
													if($hDP<$hF && $hFP>$hD){
														if($hFP>$hF){$hFP=$hF;}
														if($hDP<$hD){$hDP=$hD;}
														$valPause=gmdate("H:i",$hFP-$hDP);
														$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
													}
												}
											}

											$nbHeureFormationVac=date('H:i',strtotime($nbHeureFormationVac." ".str_replace(":"," hour ",$val)." minute"));

										}
									}
									if($bTrouve==1){
										if($estUneVacation<>0){
											if($indice<>""){$indice.="+";}
											$indice.="FOR";
										}
										
									}
								}

								//VM 
								if($nbVM>0){
									$bTrouve=0;
									mysqli_data_seek($resultVM,0);
									while($rowVM=mysqli_fetch_array($resultVM)){
										if($rowVM['DateVisite']==$dateMois){
											
											//Nombre total d'heure de formation
											$hF=strtotime($rowVM['HeureFin']);
											$hD=strtotime($rowVM['HeureVisite']);
											$val=gmdate("H:i",$hF-$hD);
											$bTrouve=1;
											if($_SESSION['Langue']=="FR"){
											 $Divers.="<br>Visite médicale (".substr($rowVM['HeureVisite'],0,5).")";	
											}
											else{
												$Divers.="<br>Medical visit (".substr($rowVM['HeureVisite'],0,5).")";	
											}
											
											if(estSalarie($dateMois,$Id_Personne)==0){
												$nbHeureVM=date('H:i',strtotime($nbHeureVM." ".str_replace(":"," hour ",$val)." minute"));
												//Nombre d'heure pendant la vacation 
												$hFTravail=strtotime($HeureFinTravail);
												$hDTravail=strtotime($HeureDebutTravail);
												if($hFTravail<$hF){$hF=$hFTravail;}
												if($hDTravail>$hD){$hD=$hDTravail;}
												$val=gmdate("H:i",$hF-$hD);
												
												$nbHeureVMVac=date('H:i',strtotime($nbHeureVMVac." ".str_replace(":"," hour ",$val)." minute"));
											}
										}
									}
									if($bTrouve==1){
										if($indice<>""){$indice.="+";}
										$indice.="VM";
										
									}
								}
							}
							
							$PassageSouris="";
							$Span="";
							if($bEtat=="refusee"){
								$PassageSouris="id='leHover'";
								if($_SESSION['Langue']=="FR"){
									$Span="<span>
									Refusé par : ".$ValidateurRefus."<br>
									Motif : ".stripslashes($MotifRefus)."<br>
									Commentaire : ".stripslashes($CommentaireRefus)."<br>
									</span>";
								}
								else{
									$Span="<span>
									Refused by : ".$ValidateurRefus."<br>
									Reason : ".stripslashes($MotifRefus)."<br>
									Comment : ".stripslashes($CommentaireRefus)."<br>
									</span>";
								}
								
							}

							if($jour==1){
								if($joursem[$jourSemaine]==$joursem2[$colonne-1] && $tabDate[1]==$tabDateMois[1]){
									if($laCouleur==""){
										if(estWE($timestampMois)){
											$bgcolor="bgcolor='".$Gris."'";
										}
									}
									if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
									elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
									elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
									elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
									elseif($bEtat=="absInjustifiee"){$bgcolor="bgcolor='".$AbsenceInjustifies."'";}
									
									if($VacParticuliere==0){
										$jourFixe=estJour_Fixe($dateMois,$Id_Personne);
										$Id_Contrat =IdContrat($Id_Personne,$dateMois);
										if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
											$bgcolor="bgcolor='".$Automatique."'";
											$type=$jourFixe;
										}
									}
									
									$ClassComment="";
									$leHover="";
									if($Divers<>""){
										$ClassComment="Comment";
										$Divers="<span>".$Divers."</span>";
										$leHover="Id='leHover'";
									}
									
									echo "<td class='jourSemaine ".$ClassComment."' ".$PassageSouris." ".$leHover." ".$bgcolor." align='center'>".$jour."<sup>".$type.$IndiceAbs.$valAstreinte.$indice."</sup>".$Span."".$Divers."</td>";
									$tabDateMois = explode('-', $dateMois);
									$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
									$dateMois = date("Y-m-d", $timestampMois);
								}
								else{
									echo "<td style='border:1px solid #b9b9b9;font-size:12px;' align='center'></td>";
								}
							}
							else{
								if($laCouleur==""){
									if(estWE($timestampMois)){
										$bgcolor="bgcolor='".$Gris."'";
									}
								}
								if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
								elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
								elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
								elseif($bEtat=="absInjustifiee"){$bgcolor="bgcolor='".$AbsenceInjustifies."'";}
								elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
								
								if($VacParticuliere==0){
									$jourFixe=estJour_Fixe($dateMois,$Id_Personne);
									$Id_Contrat =IdContrat($Id_Personne,$dateMois);
									if($jourFixe<>"" && Id_TypeContrat($Id_Contrat)<>18){
										$bgcolor="bgcolor='".$Automatique."'";
										$type=$jourFixe;
									}
								}
								
								$ClassComment="";
								$leHover="";
								if($Divers<>""){
									$ClassComment="Comment";
									$Divers="<span>".$Divers."</span>";
									$leHover="Id='leHover'";
								}
								
								echo "<td class='jourSemaine ".$ClassComment."' ".$PassageSouris." ".$leHover." ".$bgcolor." align='center'>".$jour."<sup>".$type.$IndiceAbs.$valAstreinte.$indice."</sup>".$Span."".$Divers."</td>";
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
	if($i==3 || $i==6 || $i==9 || $i==12){echo "</tr><tr><td height='20'></td></tr>";}
	
	//Mois suivant
	$tabDate = explode('-', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1]+1, $tabDate[2], $tabDate[0]);
	$tmpDate = date("Y-m-d", $timestamp);
	}
}
?>	
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="6" align="center">
			<table align="center" width="60%" cellpadding="0" cellspacing="0">
				<tr align="left">
					<td bgcolor="<?php echo $EnAttente; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "En attente de pré validation";}else{echo "Waiting for pre-validation";} ?></td>
					<td bgcolor="<?php echo $Automatique; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Automatique";}else{echo "Automatic";} ?></td>
					<td bgcolor="<?php echo $TransmisRH; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Transmis aux RH";}else{echo "Submitted to HR";} ?></td>
					<td bgcolor="<?php echo $Validee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validée";}else{echo "Validated";} ?></td>
					<td bgcolor="<?php echo $Refusee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Refusée";}else{echo "Declined";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php //echo date("h:s:i")."<br>"; ?>
</body>
</html>