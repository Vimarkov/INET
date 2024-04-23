<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
?>

<html>
<head>
	<title>Formations - Inscription à une formation pour un CQP ou un CE</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" charset="utf-8" src="Session.js"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>
	<script>
		function Desinscrire(Id_Personne,Id_SessionPersonne,Id_Besoin,Langue){
			var message = "";
			if(Langue=="FR"){message='Etes-vous sûr de vouloir désinscrire cette personne ?';}
			else{message='Are you sure you want to unsubscribe?';}
			if(window.confirm(message)){
				var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnDesinscrire' name='btnDesinscrire' value='Desinscrire'>";
				document.getElementById('Id_PersonneDesinscription').value=Id_Personne;
				document.getElementById('Id_SessionPersonneDesinscription').value=Id_SessionPersonne;
				document.getElementById('Id_BesoinPersonneDesinscription').value=Id_Besoin;
				document.getElementById('Desinscrire2').innerHTML=bouton;
				var evt = document.createEvent("MouseEvents");
				evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
				document.getElementById("btnDesinscrire").dispatchEvent(evt);
				document.getElementById('Desinscrire2').innerHTML="";
				
			}
		}
	</script>
	<script>
		function datepick() {
			if (!Modernizr.inputtypes['date']) {
				$('input[type=date]').datepicker({
					dateFormat: 'dd/mm/yy'
				});
			}
		}
	</script>
</head>
<body>

<?php
Ecrire_Code_JS_Init_Date();
		
if($_POST){
	if(isset($_POST['inscrire']) || isset($_POST['btnDesinscrire'])){
		if(isset($_POST['inscrire'])){
			//Parcourir les checklists cochés
			if(isset($_POST['personneAFormer'])){
				foreach($_POST['personneAFormer'] as $valeur){
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form)){$AF=1;}
					else{$AF=0;}
					$tab=explode("_",$valeur);
				   inscriptionPersonneSession($tab[0],$_POST['Id_Session'],$tab[1],$_POST['Id_Plateforme'],$tab[2],$AF);
				}
			}
			
			if(isset($_POST['personneSansBesoin'])){
				foreach($_POST['personneSansBesoin'] as $valeur){
					$tab=explode("_",$valeur);
					
					$req="SELECT Id_Formation, Recyclage FROM form_session WHERE Id=".$_POST['Id_Session'];
					$ResultSession=mysqli_query($bdd,$req);
					$rowSession=mysqli_fetch_array($ResultSession);
					
					//Qualification liées à la formation
					$ReqQualifFormation="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$rowSession['Id_Formation']." AND Suppr=0 AND Masquer=0 ";
					$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
					$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
					
					//Créer le besoin
					if(Get_NbBesoinExistant($tab[0], $rowSession['Id_Formation'])==0){
						
						$Motif="Nouveau";
						if($rowSession['Recyclage']==1){$Motif="Renouvellement";}
						
						$ReqInsertBesoin="
							INSERT INTO
								form_besoin
								(
									Id_Demandeur,
									EmisParAF,
									Id_Prestation,
									Id_Pole,
									Id_Formation,
									Id_Personne,
									Date_Demande,
									Motif,
									Valide,
									Id_Valideur,
									Id_Personne_MAJ,
									Date_MAJ
								)
							VALUES
								(".
									$IdPersonneConnectee.",
									1,".
									$tab[1].",".
									$tab[2].",".
									$rowSession['Id_Formation'].",".
									$tab[0].",
									'".date('Y-m-d')."',
									'".$Motif."',
									1,
									".$IdPersonneConnectee.",".
									$IdPersonneConnectee.",".
									"'".date('Y-m-d')."'
								)";
						$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
						$ID_BESOIN=mysqli_insert_id($bdd);
						
						//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
						if($NbQualifFormation>0)
						{
							mysqli_data_seek($ResultQualifFormation,0);
							$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
							while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
							{
								$ReqInsertBesoinGPEC.="(";
								$ReqInsertBesoinGPEC.=$tab[0];
								$ReqInsertBesoinGPEC.=",'Qualification'";
								$ReqInsertBesoinGPEC.=",".$RowQualifFormation['Id_Qualification'];
								$ReqInsertBesoinGPEC.=",'B'";
								$ReqInsertBesoinGPEC.=",0";
								$ReqInsertBesoinGPEC.=",".$ID_BESOIN;
								$ReqInsertBesoinGPEC.="),";
							
							}
							$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
							$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
						}
						
						$req="SELECT Id_Metier, 
							(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
							FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$tab[0];
						$ResultMetier=mysqli_query($bdd,$req);
						$NbMetier=mysqli_num_rows($ResultMetier);
					}
				   inscriptionPersonneSession($tab[0],$_POST['Id_Session'],$tab[1],$_POST['Id_Plateforme'],$tab[2],1);
				}
			}
		}
		if(isset($_POST['btnDesinscrire'])){
			if($_POST['Formation_Liee']==1 && $_POST['Id_GroupeSession']<>0){
				//DESINCRIRE DE TOUTES LES SESSIONS DE FORMATION LIEES
				$req="SELECT form_session_personne.Id_Besoin, form_session_personne.Id 
				FROM form_session_personne
				LEFT JOIN form_session 
				ON form_session_personne.Id_Session=form_session.Id
				WHERE form_session_personne.Suppr=0 AND form_session_personne.Id_Personne=".$_POST['Id_PersonneDesinscription']." 
				AND form_session.Id_GroupeSession=".$_POST['Id_GroupeSession'];
				$result=mysqli_query($bdd,$req);
				$nbSessionPers=mysqli_num_rows($result);
				if($nbSessionPers>0){
					while($rowSessionPers=mysqli_fetch_array($result)){
						desinscrire_candidat($rowSessionPers['Id_Besoin'],$rowSessionPers['Id']);
					}
				}
				
			}
			else{
				desinscrire_candidat($_POST['Id_BesoinPersonneDesinscription'],$_POST['Id_SessionPersonneDesinscription']);
			}
		}
		echo "<script>window.opener.opener.document.getElementById('formulaire').submit();</script>";
	}
}

$Id_Session=0;
$Id_Prestation=0;
$Id_Plateforme=0;
if($_POST){
	$Id_Session=$_POST['Id_Session'];
	$Id_Plateforme=$_POST['Id_Plateforme'];
}
else{
	$Id_Session=$_GET['Id_Session'];
	$Id_Plateforme=$_GET['Id_Plateforme'];
}

//Récupérer les informations de la session
$req="SELECT Id,Id_GroupeSession,Formation_Liee  ";
$req.="FROM form_session WHERE Id=".$Id_Session;
$result=mysqli_query($bdd,$req);
$LigneSession=mysqli_fetch_array($result);

$formationLiee="";
$tab = array();
//Vérifier si cette session n'appartient pas à un groupe de sessions liées
if($LigneSession['Formation_Liee']>0 && $LigneSession['Id_GroupeSession']>0){
	$req="SELECT DISTINCT form_session.Id  ";
	$req.="FROM form_session_groupe ";
	$req.="LEFT JOIN form_session ON form_session_groupe.Id=form_session.Id_GroupeSession ";
	$req.="WHERE form_session.Suppr=0 AND form_session.Id_GroupeSession=".$LigneSession['Id_GroupeSession'];
	$result=mysqli_query($bdd,$req);
	while($row=mysqli_fetch_array($result)){
		$tab[]=$row['Id'];
	}
	$formationLiee="<tr>";
	$formationLiee.="<td class='Libelle' colspan='6' align='center'><img width='15px' src='../../Images/attention.png' />&nbsp;";
	if($LangueAffichage=="FR"){
		$formationLiee.="La présence à toutes les formations est obligatoire</td>";
	}
	else{
		$formationLiee.="Participation in all training is mandatory</td>";
	}
	$formationLiee.="</tr>";
}
else{
	$tab[]=$LigneSession['Id'];
}

//Liste des formations dans l'ordre
$req="
	SELECT
		form_session_date.Id AS Id_SessionDate,
		form_session_date.DateSession,
		form_session_date.Heure_Debut,
		form_session_date.Heure_Fin,
		form_session.Id,form_session.Id_GroupeSession,
		form_session.Formation_Liee,
		form_session.Nb_Stagiaire_Maxi,
		form_session.Id_Formation,
		form_session.MessageInscription,
		(SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,
		form_session.Recyclage,
		(SELECT Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Recyclage_Identique
	FROM
		form_session_date
	LEFT JOIN
		form_session
		ON form_session_date.Id_Session=form_session.Id
	WHERE
		form_session.Id IN (";
foreach ($tab as $val) {
	$req.=$val.",";
}
$req=substr($req,0,-1);
$req.=")
	ORDER BY
		form_session_date.DateSession";
$resultSessionDate=mysqli_query($bdd,$req);

?>
<form id="formulaire" method="POST" action="InscrireSessionAF_CE_CQP.php" onSubmit="return VerifChamps();">

<input type="hidden" name="Id_Session" value="<?php echo $Id_Session;?>">
<input type="hidden" name="Id_Plateforme" value="<?php echo $Id_Plateforme;?>">
<input type="hidden" name="Formation_Liee" value="<?php echo $LigneSession['Formation_Liee'];?>">
<input type="hidden" name="Id_GroupeSession" value="<?php echo $LigneSession['Id_GroupeSession'];?>">
<input type="hidden" name="Id_PersonneDesinscription" id="Id_PersonneDesinscription" value="">
<input type="hidden" name="Id_SessionPersonneDesinscription" id="Id_SessionPersonneDesinscription" value="">
<input type="hidden" name="Id_BesoinPersonneDesinscription" id="Id_BesoinPersonneDesinscription" value="">

<table style="width:100%; align:center;" class="TableCompetences">
	<tr class="TitreColsUsers">
		<td style="text-align:center;font-size:23px;Font-Weight:Bold;" colspan="4"><?php if($LangueAffichage=="FR"){echo "INSCRIPTION";}else{echo "REGISTRATION";}  ?></td>
	</tr>
	<tr class="TitreColsUsers">
		<td height="4px" colspan="4"></td>
	</tr>
	<tr class="TitreColsUsers">
		<td class="Libelle" colspan="4">
			<?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?> :
			<?php
			$Id_Prestation=0;
			if($_POST){
				$Id_Prestation=$_POST['Id_Prestation'];
			}
			echo "<select name='Id_Prestation' id='Id_Prestation' onChange='submit();'>";
			echo "<option value='0'></option>";
			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form))
			{
				$reqPrestation=Get_SQL_PrestationsResponsablesPourPersonne($Id_Plateforme,true,array(0));
			}
			elseif(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
			{
				$reqPrestation=Get_SQL_PrestationsResponsablesPourPersonne($Id_Plateforme,false,$TableauIdPostesRespPresta_CQ);
			}			
			$resultPrestation=mysqli_query($bdd,$reqPrestation);
			while($rowPrestation=mysqli_fetch_array($resultPrestation)){
				$selected="";
				if($_POST){
					if($Id_Prestation==$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']){$selected="selected";}
				}
				echo "<option value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$selected.">".stripslashes($rowPrestation['Libelle']).stripslashes($rowPrestation['Pole'])."</option>\n";
			}
			echo "</select>";
			?>
		</td>
	</tr>
	<tr>
		<td width="50%"  valign="top">
			<table>
				<tr>
					<td style="text-align:center;color:#1f46da;font-size:18px;text-decoration:underline;Font-Weight:Bold;"><?php if($LangueAffichage=="FR"){echo "FORMATIONS";}else{echo "TRAINING";}  ?></td>
				</tr>
			<?php
				//---------LISTE DES FORMATIONS ET DES SESSIONS CONCERNEES----------//
				$Id_Formation=0;
				$tabFormation=array();
				$tabDate=array();
				$tabDateHeure=array();
				$nbInscrit=0;
				$nbMaxPlace=0;
				//Liste des sessions de formations + Afficher le nom des formations
				while($rowSessionForm=mysqli_fetch_array($resultSessionDate)){
					if($rowSessionForm['Id_Formation']<>$Id_Formation){
						//Afficher les informations de la formation
						$req=Get_SQL_InformationsPourFormation($Id_Plateforme, $rowSessionForm['Id_Formation']);
						$resultFormation=mysqli_query($bdd,$req);
						$nbFormation=mysqli_num_rows($resultFormation);
						if($nbFormation>0){
							$rowForm=mysqli_fetch_array($resultFormation);
							$organisme="";
							if($rowForm['Organisme']<>""){$organisme=" (".stripslashes($rowForm['Organisme']).")";}
							$formation="";
							if($rowSessionForm['Recyclage']==0){$formation=$rowForm['Libelle'];}
							else{$formation=$rowForm['LibelleRecyclage'];}
								if($Id_Formation<>0){
									echo "</table></td></tr>";
								}
							?>
								<tr class="TitreColsUsers">
									<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}  ?>: </td>
									<td class="Libelle" colspan="3"><?php echo $formation.$organisme; ?></td>
								</tr>
								<tr class="TitreColsUsers">
									<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";}  ?>: </td>
									<td class="Libelle"><?php echo $rowForm['TypeFormation']; ?></td>
								</tr>
								<tr class="TitreColsUsers">
									<td  class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Initiale / Recyclage";}else{echo "Initial / Recycling";}  ?> : </td>
									<td class="Libelle">
										<?php
											if($rowSessionForm['Recyclage']==0){
												if($LangueAffichage=="FR"){echo "Initiale";}else{echo "Initial";}
											}
											else{
												if($LangueAffichage=="FR"){echo "Recyclage";}else{echo "Recycling";}
											}
										
										?>
									</td>
								</tr>
								<?php
								if($rowSessionForm['MessageInscription']<>""){
								?>
								<tr class="TitreColsUsers">
									<td class="Libelle" style="color:red;" colspan="2">
										<?php
											echo stripslashes($rowSessionForm['MessageInscription']);
										?>
									</td>
								</tr>
								<?php
								}
								?>
								<tr class="TitreColsUsers">
									<td class="Libelle" valign="top">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date(s)";}else{echo "Date(s)";}  ?>: </td>
									<td>
										<table>
							<?php
						}
						$Id_Formation=$rowSessionForm['Id_Formation'];
						$tabFormation[]=array($rowSessionForm['Id_Formation'],$rowSessionForm['Recyclage'],$rowSessionForm['Recyclage_Identique']);
					}
					//Liste des dates de formations
					echo "<tr><td>".AfficheDateJJ_MM_AAAA($rowSessionForm['DateSession'])." (".substr($rowSessionForm['Heure_Debut'],0,-3)."-".substr($rowSessionForm['Heure_Fin'],0,-3).")</td></tr>";
					$tabDate[]=$rowSessionForm['DateSession'];
					$tabDateHeure[]=array($rowSessionForm['DateSession'],$rowSessionForm['Heure_Debut'],$rowSessionForm['Heure_Fin']);
					
					//Nombre de places restantes
					$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSessionForm['Id'];
					$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
					$nbInscrit=mysqli_num_rows($resultNbInscrit);
					$nbMaxPlace=$rowSessionForm['Nb_Stagiaire_Maxi'];
				}
				echo "</table></td></tr>";
				//Si ce sont des formations liées -> Informer de l'obligation de s'incrire à toutes les formations
				echo $formationLiee;
			?>
			</table>
		</td>
		<td width="50%" valign="top">
			<table>
			<tr>
				<td colspan="2" style="text-align:center;color:#1f46da;font-size:18px;text-decoration:underline;Font-Weight:Bold;"><?php if($LangueAffichage=="FR"){echo "PERSONNEL A INSCRIRE / DEJA INSCRIT";}else{echo "STAFF TO REGISTER / ALREADY ENROLLED";}  ?></td>
			</tr>
			<?php 
				//---------PERSONNEL A INSCRIRE OU DEJA INSCRIT----------//
				
				if($Id_Prestation<>0){
					$tabPresta=explode("_",$Id_Prestation);
				}

				//Personnes inscrites ou en liste d'attente
				$req="
					SELECT
						form_session_personne.Id_Personne,
						form_session_personne.Id_Besoin,
						form_session_personne.Id_Session,
						form_session_personne.Validation_Inscription,
						form_session_personne.Id,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) AS Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE Id=form_besoin.Id_Pole) AS Pole,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne
					FROM
						form_session_personne
					LEFT JOIN
						form_besoin
						ON form_session_personne.Id_Besoin=form_besoin.Id
					WHERE
						form_session_personne.Validation_Inscription IN (0,1)
						AND form_session_personne.Suppr=0
						AND form_session_personne.Id_Session IN 
						(";
				foreach($tab as $val){$req.=$val.",";}
				$req=substr($req,0,-1);
				$req.="	) ";
				
				if($Id_Prestation==0){
					$req.="
						AND CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) IN
					";
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form))
					{
						$req.="
							(SELECT
							CONCAT(Id,'_',0) AS Id_PrestationPole
						FROM
							new_competences_prestation
						WHERE
							Id_Plateforme=".$Id_Plateforme."
							AND new_competences_prestation.Active=0
							AND Id
								NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE Actif=0)
						UNION
						SELECT
							CONCAT(Id_Prestation,'_',new_competences_pole.Id) AS Id_PrestationPole
						FROM
							new_competences_pole
						LEFT JOIN
							new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE
							Id_Plateforme=".$Id_Plateforme."
							AND new_competences_prestation.Active=0
							AND new_competences_pole.Actif=0)
						";
					}
					elseif(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
					{
						$req.="
							(SELECT
							CONCAT(Id,'_',0) AS Id_PrestationPole
						FROM
							new_competences_prestation
						WHERE
							Id_Plateforme=".$Id_Plateforme."
							AND Id
								NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE Actif=0)
							AND
							(
								SELECT
									COUNT(Id)
								FROM
									new_competences_personne_poste_prestation
								WHERE
									Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
								AND Id_Personne=".$IdPersonneConnectee."
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
							)>0
						UNION
						SELECT
							CONCAT(Id_Prestation,'_',new_competences_pole.Id) AS Id_PrestationPole
						FROM
							new_competences_pole
						LEFT JOIN
							new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE
							Id_Plateforme=".$Id_Plateforme."
						AND
						(
							SELECT
								COUNT(Id)
							FROM
								new_competences_personne_poste_prestation
							WHERE
								Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
								AND Id_Personne=".$IdPersonneConnectee."
								AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id
						)>0)
						";
					}	
				}
				else{
					$req.="
						AND form_besoin.Id_Prestation=".$tabPresta[0]."
						AND form_besoin.Id_Pole=".$tabPresta[1]."
					";
				}
				
			
				$req.="ORDER BY
						Personne";

				$resultSessionPersonne=mysqli_query($bdd,$req);
				$nbSessionPersonne=mysqli_num_rows($resultSessionPersonne);
				$listeBesoin="";
				$attenteValidation=0;
				if($LangueAffichage=="FR"){
					echo "<tr><td style='text-decoration:underline;'>Personnel inscrit</td></tr>";
				}
				else{
					echo "<tr><td style='text-decoration:underline;'>Registered staff</td></tr>";
				}
				$tabListeTotalPersone=array();
				$itabTotal=0;
				if($nbSessionPersonne>0){
					$tabPer=array();
					$itab=0;
					//Personnes inscrites
					while($rowSessionPersonne=mysqli_fetch_array($resultSessionPersonne)){
						if($rowSessionPersonne['Validation_Inscription']==1){
							$bExiste=0;
							for($k=0;$k<=(sizeof($tabPer)-1);$k++){
								if($tabPer[$k]==$rowSessionPersonne['Id_Personne']){$bExiste=1;}
							}
							if($bExiste==0){
								$Pole="";
								if($rowSessionPersonne['Pole']<>""){$Pole=" - ".$rowSessionPersonne['Pole'];}
								echo "<tr><td>&bull; ".$rowSessionPersonne['Personne']." (".substr($rowSessionPersonne['Prestation'],0,7).$Pole.")</td></tr>";
								$tabPer[$itab]=$rowSessionPersonne['Id_Personne'];
								$tabListeTotalPersone[$itabTotal]=$rowSessionPersonne['Id_Personne'];
								$itab++;
								$itabTotal++;
							}
							$listeBesoin.=$rowSessionPersonne['Id_Besoin'].",";
						}
					}
					$tabPer=array();
					$itab=0;
					//En attente de validation
					mysqli_data_seek($resultSessionPersonne,0);
					while($rowSessionPersonne=mysqli_fetch_array($resultSessionPersonne)){
						if($rowSessionPersonne['Validation_Inscription']==0){
							if($attenteValidation==0){
								if($LangueAffichage=="FR"){
									echo "<tr><td style='text-decoration:underline;'>En attente de validation</td></tr>";
								}
								else{
									echo "<tr><td style='text-decoration:underline;'>Waiting for validation</td></tr>";
								}
								$attenteValidation=1;
							}
							$bExiste=0;
							for($k=0;$k<=(sizeof($tabPer)-1);$k++){
								if($tabPer[$k]==$rowSessionPersonne['Id_Personne']){$bExiste=1;}
							}
							if($bExiste==0){
								$Pole="";
								if($rowSessionPersonne['Pole']<>""){$Pole=" - ".$rowSessionPersonne['Pole'];}
								echo "<tr><td>&bull; ".$rowSessionPersonne['Personne']." (".substr($rowSessionPersonne['Prestation'],0,7).$Pole.")
								<a style=\"text-decoration:none;\" href=\"javascript:Desinscrire('".$rowSessionPersonne['Id_Personne']."','".$rowSessionPersonne['Id']."','".$rowSessionPersonne['Id_Besoin']."','".$LangueAffichage."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Désinscrire\" title=\"Désinscrire\">&nbsp;&nbsp;</a>
								</td></tr>";
								$tabPer[$itab]=$rowSessionPersonne['Id_Personne'];
								$tabListeTotalPersone[$itabTotal]=$rowSessionPersonne['Id_Personne'];
								$itab++;
								$itabTotal++;
							}
							$listeBesoin.=$rowSessionPersonne['Id_Besoin'].",";
						}
					}
				}
				if($listeBesoin<>""){
					$listeBesoin=substr($listeBesoin,0,-1);
				}
				if($LangueAffichage=="FR"){
					echo "<tr><td style='text-decoration:underline;'>Personnel à inscrire</td></tr>";
				}
				else{
					echo "<tr><td style='text-decoration:underline;'>Staff to register</td></tr>";
				}
				
				//Personne à inscrire
				$req="SELECT DISTINCT form_besoin.Id_Personne, 
					Id_Prestation,Id_Pole,
					(SELECT Libelle FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=form_besoin.Id_Pole) AS Pole,
				";
				$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS Personne ";
				$req.="FROM form_besoin ";
				$req.="WHERE form_besoin.Suppr=0 AND form_besoin.Traite=0 AND (";
				foreach ($tabFormation as $val) {
					$Motif="Motif<>'Renouvellement'";
					if($val[1]==1){$Motif="Motif='Renouvellement'";}
					if($val[2]==0){$Motif="(Motif='Renouvellement' OR Motif<>'Renouvellement')";}
					$req.=" ( Id_Formation=".$val[0]." AND ".$Motif.") OR ";
					//Pour chaque formation vérifier si celle-ci n'a pas une formation équivalente
					$reqSimil="SELECT Id_FormationEquivalente  
								FROM form_formationequivalente_formationplateforme 
								LEFT JOIN form_formationequivalente 
								ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
								WHERE form_formationequivalente.Id_Plateforme=".$Id_Plateforme." 
								AND form_formationequivalente_formationplateforme.Id_Formation=".$val[0]."
								AND form_formationequivalente_formationplateforme.Recyclage=".$val[1];
					$resultSimil=mysqli_query($bdd,$reqSimil);
					$nbSimil=mysqli_num_rows($resultSimil);
					if($nbSimil>0){
						while($rowSimil=mysqli_fetch_array($resultSimil)){
							$reqSimil2="SELECT Id_Formation, Recyclage,
								(SELECT Recyclage FROM form_formation WHERE form_formation.Id=form_formationequivalente_formationplateforme.Id_Formation) AS Recyclage_Identique
								FROM form_formationequivalente_formationplateforme 
								LEFT JOIN form_formationequivalente 
								ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
								WHERE form_formationequivalente_formationplateforme.Id_FormationEquivalente=".$rowSimil['Id_FormationEquivalente']." ";
							$resultSimil2=mysqli_query($bdd,$reqSimil2);
							$nbSimil2=mysqli_num_rows($resultSimil2);
							if($nbSimil2>0){
								while($rowSimil2=mysqli_fetch_array($resultSimil2)){
									$Motif2="Motif<>'Renouvellement'";
									if($rowSimil2['Recyclage']==1){$Motif2="Motif='Renouvellement'";}
									if($rowSimil2['Recyclage_Identique']==0){$Motif2="(Motif='Renouvellement' OR Motif<>'Renouvellement')";}
									$req.=" ( Id_Formation=".$rowSimil2['Id_Formation']." AND ".$Motif2.") OR ";
								}
							}
						}
					}
				}
				$req=substr($req,0,-3);
				$req.=") ";
				
				if($Id_Prestation==0){
					$req.="
						AND CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) IN
					";
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form))
					{
						$req.="
							(SELECT
							CONCAT(Id,'_',0) AS Id_PrestationPole
						FROM
							new_competences_prestation
						WHERE
							Id_Plateforme=".$Id_Plateforme."
							AND new_competences_prestation.Active=0
							AND Id
								NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE Actif=0)
						UNION
						SELECT
							CONCAT(Id_Prestation,'_',new_competences_pole.Id) AS Id_PrestationPole
						FROM
							new_competences_pole
						LEFT JOIN
							new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE
							Id_Plateforme=".$Id_Plateforme."
							AND new_competences_prestation.Active=0
							AND new_competences_pole.Actif=0)
						";
					}
					elseif(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
					{
						$req.="
							(SELECT
							CONCAT(Id,'_',0) AS Id_PrestationPole
						FROM
							new_competences_prestation
						WHERE
							Id_Plateforme=".$Id_Plateforme."
							AND Id
								NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE Actif=0)
							AND
							(
								SELECT
									COUNT(Id)
								FROM
									new_competences_personne_poste_prestation
								WHERE
									Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
								AND Id_Personne=".$IdPersonneConnectee."
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
							)>0
						UNION
						SELECT
							CONCAT(Id_Prestation,'_',new_competences_pole.Id) AS Id_PrestationPole
						FROM
							new_competences_pole
						LEFT JOIN
							new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE
							Id_Plateforme=".$Id_Plateforme."
						AND
						(
							SELECT
								COUNT(Id)
							FROM
								new_competences_personne_poste_prestation
							WHERE
								Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
								AND Id_Personne=".$IdPersonneConnectee."
								AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id
						)>0)
						";
					}	
				}
				else{
					$req.="
						AND form_besoin.Id_Prestation=".$tabPresta[0]."
						AND form_besoin.Id_Pole=".$tabPresta[1]."
					";
				}
				
				$req.=" AND Valide=1 ORDER BY Personne";
				
				$resultAFormer=mysqli_query($bdd,$req);
				$nbAFormer=mysqli_num_rows($resultAFormer);
				$places=$nbMaxPlace-$nbInscrit;
				if($nbAFormer>0){
					echo "<tr><td>";
					echo '<div style="height:100px;overflow:auto;">';
					echo "<table>";
					while($rowAFormer=mysqli_fetch_array($resultAFormer))
					{
						//Vérifier si  la personne n'est pas déjà en formation à cette heure
						$req="SELECT Id FROM form_session_date WHERE Suppr=0 AND (";
						foreach ($tabDateHeure as $valDateHeure) {
							$req.=" (DateSession='".$valDateHeure[0]."' ";
							if($valDateHeure[1]<>0){
									$req.=" AND Heure_Debut<'".$valDateHeure[2]."' AND Heure_Fin>'".$valDateHeure[1]."' ";
							}
							$req.=") OR ";
						}
						$req=substr($req,0,-3);
						$req.=") ";
						$req.=" AND Id_Session IN (SELECT Id_Session FROM form_session_personne WHERE Suppr=0 AND Validation_Inscription>-1 AND Presence IN (0,1) AND Id_Personne=".$rowAFormer['Id_Personne']." ) ";
						$resultDispo=mysqli_query($bdd,$req);
						$nbDispo=mysqli_num_rows($resultDispo);
						
						//Vérifier si la personne n'est pas déjà inscrite à une formation similaire
						$req="SELECT Id FROM form_besoin 
							WHERE Suppr=0 AND Id_Personne=".$rowAFormer['Id_Personne']." AND (Traite=1 OR Traite=2) AND (";
						foreach ($tabFormation as $val) {
							$Motif="Motif<>'Renouvellement'";
							if($val[1]==1){$Motif="Motif='Renouvellement'";}
							if($val[2]==0){$Motif="(Motif='Renouvellement' OR Motif<>'Renouvellement')";}
							$req.=" ( Id_Formation=".$val[0]." AND ".$Motif.") OR ";
							//Pour chaque formation vérifier si celle-ci n'a pas une formation équivalente
							$reqSimil="SELECT Id_FormationEquivalente  
										FROM form_formationequivalente_formationplateforme 
										LEFT JOIN form_formationequivalente 
										ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
										WHERE form_formationequivalente.Id_Plateforme=".$Id_Plateforme." 
										AND form_formationequivalente_formationplateforme.Id_Formation=".$val[0]."
										AND form_formationequivalente_formationplateforme.Recyclage=".$val[1];
							$resultSimil=mysqli_query($bdd,$reqSimil);
							$nbSimil=mysqli_num_rows($resultSimil);
							if($nbSimil>0){
								while($rowSimil=mysqli_fetch_array($resultSimil)){
									$reqSimil2="SELECT Id_Formation, Recyclage,
										(SELECT Recyclage FROM form_formation WHERE form_formation.Id=form_formationequivalente_formationplateforme.Id_Formation) AS Recyclage_Identique
										FROM form_formationequivalente_formationplateforme 
										LEFT JOIN form_formationequivalente 
										ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
										WHERE form_formationequivalente_formationplateforme.Id_FormationEquivalente=".$rowSimil['Id_FormationEquivalente']." ";
									$resultSimil2=mysqli_query($bdd,$reqSimil2);
									$nbSimil2=mysqli_num_rows($resultSimil2);
									if($nbSimil2>0){
										while($rowSimil2=mysqli_fetch_array($resultSimil2)){
											$Motif2="Motif<>'Renouvellement'";
											if($rowSimil2['Recyclage']==1){$Motif2="Motif='Renouvellement'";}
											if($rowSimil2['Recyclage_Identique']==0){$Motif2="(Motif='Renouvellement' OR Motif<>'Renouvellement')";}
											$req.=" ( Id_Formation=".$rowSimil2['Id_Formation']." AND ".$Motif2.") OR ";
										}
									}
								}
							}
						}
						$req=substr($req,0,-3);
						$req.=") ";
						$req.=" ";
						
						$resultDejaInscritForm=mysqli_query($bdd,$req);
						$nbDejaInscritForm=mysqli_num_rows($resultDejaInscritForm);
						
						//Vérifier si la personne n'est pas absente ce jour là
						$reqAbsence="SELECT Id FROM new_planning_personne_vacationabsence 
									WHERE Id_Personne=".$rowAFormer['Id_Personne']." 
									AND (SELECT AbsenceVacation FROM new_planning_vacationabsence 
										WHERE new_planning_vacationabsence.Id=new_planning_personne_vacationabsence.ID_VacationAbsence LIMIT 1)=0 ";
						$reqAbsence.=" AND (";
						foreach ($tabDate as $val) {
							$reqAbsence.=" DatePlanning='".$val."' OR ";
						}
						$reqAbsence=substr($reqAbsence,0,-3);
						$reqAbsence.=") ";
						$resultAbs=mysqli_query($bdd,$reqAbsence);
						$nbAbs=mysqli_num_rows($resultAbs);
						
						//Vérifier si la personne n'est pas absente ce jour là sous OPTEA 
						$reqAbsence="SELECT Id FROM rh_personne_demandeabsence 
									WHERE Id_Personne=".$rowAFormer['Id_Personne']." 
									AND Suppr=0
									AND rh_personne_demandeabsence.Annulation=0 
									AND rh_personne_demandeabsence.Conge=1 
									AND EtatN1<>-1
									AND EtatN2<>-1
									AND (SELECT COUNT(rh_absence.Id) FROM rh_absence 
										WHERE rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
										AND Suppr=0  ";
						$reqAbsence.=" AND (";
						foreach ($tabDate as $val) {
							$reqAbsence.=" (DateFin<='".$val."' AND DateDebut>='".$val."') OR ";
						}
						$reqAbsence=substr($reqAbsence,0,-3);
						$reqAbsence.=")) ";
						$resultAbsOPTEA=mysqli_query($bdd,$reqAbsence);
						$nbAbsOPTEA=mysqli_num_rows($resultAbsOPTEA);
						
						$input="&bull; &nbsp;&nbsp;&nbsp;&nbsp;";
						if($nbDispo==0 && $nbAbs==0 && $nbDejaInscritForm==0 && $places>0 && $nbAbsOPTEA==0){
							$input="<input type='checkbox' name='personneAFormer[]' value='".$rowAFormer['Id_Personne']."_".$rowAFormer['Id_Prestation']."_".$rowAFormer['Id_Pole']."' >";
						}
						$Info="";
						if($nbAbsOPTEA>0){
							if($LangueAffichage=="FR"){$Info=" [Absent]";}
							else{$Info=" [Absent]";}
						}
						elseif($nbDejaInscritForm>0){
							$rowBesoinDeja=mysqli_fetch_array($resultDejaInscritForm);
							
							$date="";
							$reqBesoin="SELECT DateSession 
										FROM form_session_date 
										WHERE Suppr=0 
										AND Id_Session IN (SELECT Id_Session FROM form_session_personne WHERE Suppr=0 AND Id_Besoin=".$rowBesoinDeja['Id'].")
										ORDER BY DateSession ASC ";
							$resultB=mysqli_query($bdd,$reqBesoin);
							$nbB=mysqli_num_rows($resultB);
							if($nbB>0){
								$rowB=mysqli_fetch_array($resultB);
								$date="(".AfficheDateJJ_MM_AAAA($rowB['DateSession']).")";
							}
							
							if($LangueAffichage=="FR"){$Info=" [Déjà inscrit à une formation similaire ".$date."]";}
							else{$Info=" [Already enrolled in a similar training ".$date."]";}
						}
						elseif($nbDispo>0){
							if($LangueAffichage=="FR"){$Info=" [Déjà en formation pendant ce créneau]";}
							else{$Info=" [Already in training during this time slot]";}
						}
						$Pole="";
						if($rowAFormer['Pole']<>""){$Pole=" - ".$rowAFormer['Pole'];}
						echo "<tr><td>".$input.$rowAFormer['Personne']." (".substr($rowAFormer['Prestation'],0,7).$Pole.") <font style='color:red;'>".$Info."</font></td></tr>";
						$tabListeTotalPersone[$itabTotal]=$rowAFormer['Id_Personne'];
						$itabTotal++;
					}
					echo "	</table>
							</div>
							</td>
							</tr>";
				}
				if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form))
				{
					if($LangueAffichage=="FR"){
						echo "<tr><td style='text-decoration:underline;'>Personnel sans besoin</td></tr>";
					}
					else{
						echo "<tr><td style='text-decoration:underline;'>Staff without need</td></tr>";
					}
					
					$reqSuite="";
					if(sizeof($tabListeTotalPersone)>0){
						$reqSuite="AND Id_Personne NOT IN (".implode(",",$tabListeTotalPersone).") ";
					}
					$reqSuite2="";
					if($Id_Prestation<>0){
						$reqSuite2.="
						AND Id_Prestation=".$tabPresta[0]."
						AND Id_Pole=".$tabPresta[1]."
						";
					}
					
					//Personne sans besoin
					$req="SELECT DISTINCT Id_Personne, 
						Id_Prestation,Id_Pole,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne 
						FROM new_competences_personne_prestation 
						WHERE (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)=".$Id_Plateforme."
						".$reqSuite."
						".$reqSuite2."
						ORDER BY Personne";

					$resultSansBesoin=mysqli_query($bdd,$req);
					$nbSansBesoin=mysqli_num_rows($resultSansBesoin);
					if($nbSansBesoin>0){
						echo "<tr><td>";
						echo '<div style="height:200px;overflow:auto;">';
						echo "<table>";
						while($rowSansBesoin=mysqli_fetch_array($resultSansBesoin))
						{
							//Vérifier si la personne n'est pas absente ce jour là sous OPTEA 
							$reqAbsence="SELECT Id FROM rh_personne_demandeabsence 
										WHERE Id_Personne=".$rowSansBesoin['Id_Personne']." 
										AND Suppr=0
										AND rh_personne_demandeabsence.Annulation=0 
										AND rh_personne_demandeabsence.Conge=1 
										AND EtatN1<>-1
										AND EtatN2<>-1
										AND (SELECT COUNT(rh_absence.Id) FROM rh_absence 
											WHERE rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
											AND Suppr=0  ";
							$reqAbsence.=" AND (";
							foreach ($tabDate as $val) {
								$reqAbsence.=" (DateFin<='".$val."' AND DateDebut>='".$val."') OR ";
							}
							$reqAbsence=substr($reqAbsence,0,-3);
							$reqAbsence.=")) ";
							$resultAbsOPTEA=mysqli_query($bdd,$reqAbsence);
							$nbAbsOPTEA=mysqli_num_rows($resultAbsOPTEA);
							
							$input="&bull; &nbsp;&nbsp;&nbsp;&nbsp;";
							if($places>0 && $nbAbsOPTEA==0){
								$input="<input type='checkbox' name='personneSansBesoin[]' value='".$rowSansBesoin['Id_Personne']."_".$rowSansBesoin['Id_Prestation']."_".$rowSansBesoin['Id_Pole']."' >";
							}
							$Info="";
							if($nbAbsOPTEA>0){
								if($LangueAffichage=="FR"){$Info=" [Absent]";}
								else{$Info=" [Absent]";}
							}
							$Pole="";
							if($rowSansBesoin['Pole']<>""){$Pole=" - ".$rowSansBesoin['Pole'];}
							echo "<tr><td>".$input.$rowSansBesoin['Personne']." (".substr($rowSansBesoin['Prestation'],0,7).$Pole.") <font style='color:red;'>".$Info."</font></td></tr>";
						}
						echo "	</table>
						</div>
						</td>
						</tr>";
					}
				}
				?>
				<tr>
					<td>
					<?php
						if($places>0)
						{
					?>
					<input class="Bouton" name="inscrire" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Inscrire";}else{echo "Register";}?>">
					<?php
						}
					?>
					</td>
				</tr>
				<tr>
					<td style="font-size:16px;font-weight:bold">
				<?php
					if($places<0){$places=0;}
					if($LangueAffichage=="FR"){
						echo $places." places restantes";
					}
					else{
						echo $places." remaining places";
					}
				?>
					</td>
				</tr>
				<tr>
					<td style="font-size:14px;font-weight:bold">
				<?php
					if($LangueAffichage=="FR"){
						echo "Attention : Vous ne pouvez pas inscrire plus de personnes que de places restantes";
					}
					else{
						echo "Warning : you can not register more people than the remaining places";
					}
				?>
					</td>
				</tr>
			</table>
			<div id="Desinscrire2"></div>
		</td>
	</tr>
	<?php 
		if(!DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form)){
	?>
	<tr>
		<td colspan="2">
			<div style="height:350px;overflow:auto;">
			<table style='background-color:#e0e0e0'>
				<tr>
					<td style='background-color:#ffffff;margin:0px;'></td>
					<td style='background-color:#ffffff;margin:0px;'></td>
					<td style='background-color:#ffffff;margin:0px;'></td>
		<?php
			//--------- PLANNING DES PERSONNES DE LA PRESTATION AUX DATES DE LA FORMATION
			
			//Liste des personnes
			$reqPers = "SELECT DISTINCT new_competences_personne_prestation.Id_Personne,
						CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
						Id_Prestation,Id_Pole,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
						FROM new_rh_etatcivil,
						new_competences_personne_prestation 
						WHERE new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
						AND (";	
			foreach ($tabDate as $val) {
				$reqPers.="(new_competences_personne_prestation.Date_Debut<='".$val."' AND (new_competences_personne_prestation.Date_Fin>='".$val."' OR new_competences_personne_prestation.Date_Fin<='0001-01-01')) OR ";
			}
			$reqPers=substr($reqPers,0,-3);
			$reqPers.=") ";
			
			if($Id_Prestation==0){
				$reqPers.=" AND 
					CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN
				";
				if(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
				{
					$reqPers.="
						(SELECT
							CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole)
						FROM
							new_competences_personne_poste_prestation
						WHERE
							Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
							AND Id_Personne=".$IdPersonneConnectee."
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$Id_Plateforme."
						)
					";
				}	
			}
			else{
				$reqPers.="
					AND 
					new_competences_personne_prestation.Id_Prestation=".$tabPresta[0]."
					AND new_competences_personne_prestation.Id_Pole=".$tabPresta[1]."
				";
			}
			
			$reqPers .= "ORDER BY Prestation,Pole,Personne;";

			$resultPersonne=mysqli_query($bdd,$reqPers);
			$nbPersonne=mysqli_num_rows($resultPersonne);
				
			//Afficher la liste des jours
			foreach ($tabDate as $val) {
				echo "<td class='EnTetePlanning' style='width:150px'>".AfficheDateJJ_MM_AAAA($val)."</td>";
			}
		?>
			</tr>
		<?php
			//Liste des personnes
			if($nbPersonne>0){
				while($rowPers=mysqli_fetch_array($resultPersonne)){
					echo "<tr>";
						$Pole="";
						if($rowPers['Pole']<>""){$Pole=" - ".$rowPers['Pole'];}
						echo "<td class='PersonnePlanning' style='width:100px'>".substr($rowPers['Prestation'],0,7).$Pole."</td>";
						echo "<td class='PersonnePlanning' style='width:150px'>".$rowPers['Personne']."</td>";
						$CodesMetiers="";
						$SpanMetiers="";
						//Liste des métiers (la personne peut avoir un futur métier)
						$reqMetier="SELECT DISTINCT Id_Metier,
									(SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=new_competences_personne_metier.Id_Metier) AS CodeMetier,
									(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=new_competences_personne_metier.Id_Metier) AS Libelle 
									FROM new_competences_personne_metier
									WHERE Id_Personne=".$rowPers['Id_Personne'];
						$metiers=mysqli_query($bdd,$reqMetier);
						$nbMetier=mysqli_num_rows($metiers);
						if($nbMetier>0){
							$SpanMetiers="<span>Métier : ";
							if($LangueAffichage<>"FR"){$SpanMetiers="<span>Job : ";}
							while($rowMetier=mysqli_fetch_array($metiers)){
								$CodesMetiers.=$rowMetier['CodeMetier']."<br>";
								$SpanMetiers.=$rowMetier['Libelle']."<br>";
							}
							$CodesMetiers=substr($CodesMetiers,0,-4);
							$SpanMetiers=substr($SpanMetiers,0,-4);
							$SpanMetiers.="</span>";
						}
						echo "<td id='leHoverPersonne' class='MetierPlanning'>".$CodesMetiers.$SpanMetiers."</td>";
						
						//Liste des congés
						$reqConges="SELECT rh_personne_demandeabsence.Id ,rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
									rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
									rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
									(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
									(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
									(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS CouleurIni,
									(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS CouleurDef
									FROM rh_absence 
									LEFT JOIN rh_personne_demandeabsence 
									ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
									WHERE rh_personne_demandeabsence.Id_Personne=".$rowPers['Id_Personne']." 
									AND (";
						foreach ($tabDate as $val) {
							$reqConges.=" (rh_absence.DateFin>='".$val."' AND rh_absence.DateDebut<='".$val."') OR ";
						}
						$reqConges=substr($reqConges,0,-3);
						$reqConges.="
									)
									AND rh_personne_demandeabsence.Suppr=0 
									AND rh_absence.Suppr=0 
									AND rh_personne_demandeabsence.Annulation=0 
									AND rh_personne_demandeabsence.Conge=1 
									AND EtatN1<>-1
									AND EtatN2<>-1
									ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
						$resultConges=mysqli_query($bdd,$reqConges);
						$nbConges=mysqli_num_rows($resultConges);

						//Liste des absences
						$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
									rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
									(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
									(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
									(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS CouleurIni,
									(SELECT rh_typeabsence.Couleur FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS CouleurDef
									FROM rh_absence 
									LEFT JOIN rh_personne_demandeabsence 
									ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
									WHERE rh_personne_demandeabsence.Id_Personne=".$rowPers['Id_Personne']." 
									AND (
									";
							foreach ($tabDate as $val) {
								$reqAbs.=" (rh_absence.DateFin>='".$val."' AND rh_absence.DateDebut<='".$val."') OR ";
							}
							$reqAbs=substr($reqAbs,0,-3);
							$reqAbs.="
									)
									AND rh_personne_demandeabsence.Suppr=0 
									AND rh_absence.Suppr=0  
									AND rh_personne_demandeabsence.Conge=0 
									AND EtatN1<>-1
									AND EtatN2<>-1
									ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
						$resultAbs=mysqli_query($bdd,$reqAbs);
						$nbAbs=mysqli_num_rows($resultAbs);

						//Liste des heures supplémentaires
						$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,
									IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,
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
									AS Etat
								FROM rh_personne_hs
								WHERE Suppr=0 
								AND Id_Personne=".$rowPers['Id_Personne']." 
								AND (";
							foreach ($tabDate as $val) {
								$req.=" (IF(DateRH>'0001-01-01',DateRH,DateHS)='".$val."') OR ";
							}
							$req=substr($req,0,-3);
							$req.="
									) 
								AND Etat2<>-1
								AND Etat3<>-1
								AND Etat4<>-1
								";
						$resultHS=mysqli_query($bdd,$req);
						$nb2HS=mysqli_num_rows($resultHS);
											
						//Liste des astreintes
						$req="SELECT IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte) AS DateAstreinte,
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
							TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
							TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
							TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,Montant,Intervention
							FROM rh_personne_rapportastreinte
							WHERE rh_personne_rapportastreinte.Suppr=0
							AND rh_personne_rapportastreinte.Id_Personne=".$rowPers['Id_Personne']." 
							AND (";
						foreach ($tabDate as $val) {
							$req.=" (IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)='".$val."') OR ";
						}
						$req=substr($req,0,-3);
						$req.="
							) 
							AND EtatN1<>-1
							AND EtatN2<>-1
							";
						$resultAst=mysqli_query($bdd,$req);
						$nbAst=mysqli_num_rows($resultAst);
						
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
							AND (";
						foreach ($tabDate as $val) {
							$req.=" (form_session_date.DateSession='".$val."') OR ";
						}
						$req=substr($req,0,-3);
						$req.="
							) 
									AND
									(
										SELECT
											COUNT(form_session_personne.Id) 
										FROM
											form_session_personne
										WHERE
											form_session_personne.Suppr=0
											AND form_session_personne.Id_Personne=".$rowPers['Id_Personne']."
											AND form_session_personne.Validation_Inscription=1
											AND form_session_personne.Presence IN (0,1)
											AND form_session_personne.Id_Session=form_session.Id
											AND Presence=1
								   )>0 ";
						$resultSession=mysqli_query($bdd,$req);
						$nbSession=mysqli_num_rows($resultSession);
						
						foreach ($tabDate as $val) {
							$Couleur="";
							$CelPlanning="";
							$Commentaire="";
							$ClassDiv="";
							$Divers="";
							$Travail=0;
							$contenu="";
							
							$Couleur=TravailCeJourDeSemaine($val,$rowPers['Id_Personne']);
							
							$PrestaPole=PrestationPole_Personne($val,$rowPers['Id_Personne']);
							$PrestationSelect=0;
							$PoleSelect=0;
							if($PrestaPole<>0){
								$tab=explode("_",$PrestaPole);
								$PrestationSelect=$tab[0];
								$PoleSelect=$tab[1];
							}
							
							$tabDateMois = explode('-', $val);
							$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
							if(appartientPrestation($val,$rowPers['Id_Personne'],$PrestationSelect,$PoleSelect)==1){
								if ($Couleur == ""){
										if(estWE($timestampMois)){
											$Couleur="style='background-color:".$Gris.";text-align:center;color:#000000;font-weight:Bold;'";
											$ClassDiv ="weekFerieV2";
										}
										else{
											$ClassDiv ="semaine";
										}
								}
								else{
									$Travail=1;
									if(estWE($timestampMois)){
										$ClassDiv ="weekFerieV2";
									}
									else{
										$ClassDiv ="semaine";
									}
									$Id_Contenu=IdVacationCeJourDeSemaine($val,$rowPers['Id_Personne']);
									if($Id_Contenu==1){
										if($_SESSION["Langue"]=="FR"){$contenu="J";}
										else{$contenu="D";}
									}
									else{
										if($_SESSION["Langue"]=="FR"){$contenu="VSD";}
										else{$contenu="VSD";}
									}
									$estUneVacation=1;
									$Couleur="style='background-color:".$Couleur.";text-align:center;color:#000000;font-weight:Bold;'";

									$jourFixe=estJour_Fixe($val,$rowPers['Id_Personne']);
									if($jourFixe<>""){
										$Couleur="style='background-color:".$Automatique.";text-align:center;color:#000000;font-weight:Bold;'";
										$contenu=$jourFixe;
										$Id_Contenu=estJour_Fixe_Id($val,$rowPers['Id_Personne']);
										$estUneVacation=0;
									}
									
									//Vérifier si la personne n'a pas une vacation particulière ce jour là 
									$Id_Vacation=VacationPersonne($val,$rowPers['Id_Personne'],$PrestationSelect,$PoleSelect);
									if($Id_Vacation>0){
										$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
										$resultVac=mysqli_query($bdd,$req);
										$nbVac=mysqli_num_rows($resultVac);
										if($nbVac>0){
											$rowVac=mysqli_fetch_array($resultVac);
											$Couleur="style='background-color:".$rowVac['Couleur'].";text-align:center;color:#000000;font-weight:Bold;'";
											$contenu=$rowVac['Nom'];
											$Id_Contenu=$Id_Vacation;
											$estUneVacation=1;
										}
									}
								}
									
								//Absences
								if($Travail==1){
									if($nbAbs>0){
										mysqli_data_seek($resultAbs,0);
										while($rowAbs=mysqli_fetch_array($resultAbs)){
											if($rowAbs['DateDebut']<=$val && $rowAbs['DateFin']>=$val){
												if($rowAbs['NbHeureAbsJour']<>0 || $rowAbs['NbHeureAbsNuit']<>0){
													$NbHeureAbsJour=$rowAbs['NbHeureAbsJour'];
													$NbHeureAbsNuit=$rowAbs['NbHeureAbsNuit'];
													if($rowAbs['TypeAbsenceDef']<>""){
														$IndiceAbs=$rowAbs['TypeAbsenceDef']." ";
														if($rowAbs['Id_TypeAbsenceDefinitif']==0){
															$IndiceAbs="ABS ";
														}
													}
													else{
														$IndiceAbs=$rowAbs['TypeAbsenceIni']." ";
														if($rowAbs['Id_TypeAbsenceInitial']==0){
															$IndiceAbs="ABS ";
														}
													}
												}
												else{
													if($rowAbs['TypeAbsenceDef']<>""){
														$contenu=$rowAbs['TypeAbsenceDef'];
														$Id_Contenu=$rowAbs['Id_TypeAbsenceDefinitif'];
														$estUneVacation=0;
														$Couleur="style='background-color:".$rowAbs['CouleurDef'].";text-align:center;color:#000000;font-weight:Bold'";
														if($rowAbs['Id_TypeAbsenceDefinitif']==0){
															$contenu="ABS";
															$Id_Contenu=0;
															$estUneVacation=0;
															$Couleur="style='background-color:#ff1111;;text-align:center;color:#000000;font-weight:Bold'";
														}
													}
													else{
														$contenu=$rowAbs['TypeAbsenceIni'];
														$Id_Contenu=$rowAbs['Id_TypeAbsenceInitial'];
														$estUneVacation=0;
														$Couleur="style='background-color:".$rowAbs['CouleurIni'].";;text-align:center;color:#000000;font-weight:Bold'";
														if($rowAbs['Id_TypeAbsenceInitial']==0){$contenu="ABS";$Id_Contenu=0;$Couleur="style='background-color:#ff1111;text-align:center;color:#000000;font-weight:Bold'";}
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
										if($rowConges['DateDebut']<=$val && $rowConges['DateFin']>=$val){
											$IndiceAbs="";
											$NbHeureAbsJour=0;
											$NbHeureAbsNuit=0;
											
											$jourFixe=estJour_Fixe($val,$rowPers['Id_Personne']);
											$Id_Type=$rowConges['Id_TypeAbsenceInitial'];
											if($rowConges['Id_TypeAbsenceDefinitif']<>0){$Id_Type=$rowConges['Id_TypeAbsenceDefinitif'];}
											if($jourFixe<>"" && estCalendaire($Id_Type)==0){
												$Couleur="style='background-color:".$Automatique.";text-align:center;color:#000000;font-weight:Bold'";
												$contenu=$jourFixe;
												$Id_Contenu=estJour_Fixe_Id($val,$rowPers['Id_Personne']);
												$estUneVacation=0;
											}
											else{
												if($rowConges['NbHeureAbsJour']<>0 || $rowConges['NbHeureAbsNuit']<>0){
													$NbHeureAbsJour=$rowConges['NbHeureAbsJour'];
													$NbHeureAbsNuit=$rowConges['NbHeureAbsNuit'];
													if($rowConges['TypeAbsenceDef']<>""){
														$IndiceAbs=$rowConges['TypeAbsenceDef']." ";
													}
													else{
														$IndiceAbs=$rowConges['TypeAbsenceIni']." ";
													}
												}
												else{
													if($rowConges['TypeAbsenceDef']<>""){
														$contenu=$rowConges['TypeAbsenceDef'];
														$Id_Contenu=$rowConges['Id_TypeAbsenceDefinitif'];
														$estUneVacation=0;
														$Couleur="style='background-color:".$rowConges['CouleurDef'].";text-align:center;color:#000000;font-weight:Bold;'";
													}
													else{
														$contenu=$rowConges['TypeAbsenceIni'];
														$Id_Contenu=$rowConges['Id_TypeAbsenceInitial'];
														$estUneVacation=0;
														$Couleur="style='background-color:".$rowConges['CouleurIni'].";text-align:center;color:#000000;font-weight:Bold;'";
													}
												}
											}
											break;
										}
									}
								}
									
								//Astreintes
								if($nbAst>0){
									mysqli_data_seek($resultAst,0);
									while($rowAst=mysqli_fetch_array($resultAst)){
										if($rowAst['DateAstreinte']==$val){
											$valAstreinte=" AS";
											$nbHeures="0 ";
											if($rowAst['Intervention']==1){
												$nbHeures=Ajouter_Heures($rowAst['DiffHeures1'],$rowAst['DiffHeures2'],$rowAst['DiffHeures3']);
												$valAstreinte.=" ".$nbHeures;
											}
										}
									}
								}
									
								//HS
								if($nb2HS>0){
									mysqli_data_seek($resultHS,0);
									while($rowHS=mysqli_fetch_array($resultHS)){
										if($rowHS['DateHS']==$val){
											if($rowHS['HeuresFormation']==1){
												$nbHeureSuppForm+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
											}
											else{
												$nbHS+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
												$NbHeureSuppJour+=$rowHS['Nb_Heures_Jour'];
												$NbHeureSuppNuit+=$rowHS['Nb_Heures_Nuit'];
											}
											if($indice<>""){$indice.="+";}
											if($_SESSION["Langue"]=="FR"){$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."HS";}
											else{$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."OT";}
										}
									}
								}
									

								//Formation 
								if($nbSession>0){
									mysqli_data_seek($resultSession,0);
									while($rowForm=mysqli_fetch_array($resultSession)){
										if($rowForm['DateSession']==$val){
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
									
									//VM 
									if($nbVM>0){
										$bTrouve=0;
										mysqli_data_seek($resultVM,0);
										while($rowVM=mysqli_fetch_array($resultVM)){
											if($rowVM['DateVisite']==$val){
												
												//Nombre total d'heure de formation
												$hF=strtotime($rowVM['HeureFin']);
												$hD=strtotime($rowVM['HeureVisite']);
												$val=gmdate("H:i",$hF-$hD);
												$bTrouve=1;
												
												if(estSalarie($val,$rowPers['Id_Personne'])==0){
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
							}
							/*
							
							if ($Couleur == ""){
								$ClassDiv ="class='semaine'";
								$Couleur = "align='center' style='color:#000000;font-weight:Bold;'";
							}
							
							if ($trouve==0){
								$contenu = "";
								$Couleur = " style='background-color:#000000;color=#ffffff;' ";
							}
							else{
								$contenu = $CelPlanning;
							}*/
							
							if ($Couleur == ""){
								$ClassDiv ="class='semaine'";
							}
							echo "<td>";
								echo "<table width='100%' height='100%' cellspadding='0' cellspacing='0'>";
									echo "<tr>";
										echo "<td ".$ClassDiv." ".$Couleur.">".$contenu."</td>\n";
									echo "</tr>";
									//Liste des formations de la personne inscrits
									$reqSession="SELECT DISTINCT form_session_date.Id, form_session_date.Heure_Debut, form_session_date.Heure_Fin
												FROM form_session_date 
												WHERE form_session_date.Suppr=0 AND form_session_date.DateSession='".$val."' 
												AND 
												(SELECT COUNT(form_session_personne.Id) FROM form_session_personne
												WHERE form_session_personne.Id_Personne=".$rowPers['Id_Personne']." AND Suppr=0 
												AND Id_Session=form_session_date.Id_Session AND form_session_personne.Validation_Inscription=1)>0 
												ORDER BY Heure_Debut";
									$sessionPersonne=mysqli_query($bdd,$reqSession);
									$nbSessionPersonne=mysqli_num_rows($sessionPersonne);
									if($nbSessionPersonne>0){
										$FOR="FOR ";
										if($LangueAffichage<>"FR"){$FOR="TRAINING ";}
										while($rowSessionPersonne=mysqli_fetch_array($sessionPersonne)){
											$AV="";
											echo "<tr>";
												echo "<td ".$ClassDiv." ".$Couleur." >".$FOR.$AV." : ".substr($rowSessionPersonne['Heure_Debut'],0,5)." - ".substr($rowSessionPersonne['Heure_Fin'],0,5)."</td>\n";
											echo "</tr>";
										}
									}
									//Liste des formations de la personne en attente de validation
									$reqSession="SELECT DISTINCT form_session_date.Id, form_session_date.Heure_Debut, form_session_date.Heure_Fin
												FROM form_session_date 
												WHERE form_session_date.Suppr=0 AND form_session_date.DateSession='".$val."' 
												AND 
												(SELECT COUNT(form_session_personne.Id) FROM form_session_personne
												WHERE form_session_personne.Id_Personne=".$rowPers['Id_Personne']." AND Suppr=0 
												AND Id_Session=form_session_date.Id_Session AND form_session_personne.Validation_Inscription=0)>0 
												ORDER BY Heure_Debut";
									$sessionPersonne=mysqli_query($bdd,$reqSession);
									$nbSessionPersonne=mysqli_num_rows($sessionPersonne);
									if($nbSessionPersonne>0){
										$FOR="FOR ";
										if($LangueAffichage<>"FR"){$FOR="TRAINING ";}
										while($rowSessionPersonne=mysqli_fetch_array($sessionPersonne)){
											$AV=" (AV)";
											if($LangueAffichage<>"FR"){$AV=" (TO VALIDATE)";}
											echo "<tr>";
												echo "<td ".$ClassDiv." ".$Couleur." >".$FOR.$AV." : ".substr($rowSessionPersonne['Heure_Debut'],0,5)." - ".substr($rowSessionPersonne['Heure_Fin'],0,5)."</td>\n";
											echo "</tr>";
										}
									}
								echo "</table>";
							echo "</td>";
						}
					echo "</tr>";
				}
			}
		?>
			</table>
			</div>
		</td>
	</tr>
	<?php 
		}
	?>
</table>
</td>
</tr>
</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>