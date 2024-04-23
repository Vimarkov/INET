<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function SelectionnerTout(Champ)
		{
			var elements = document.getElementsByClassName("check"+Champ);
			if (document.getElementById('selectAll'+Champ).checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
			}
			if(Champ=="Prestation"){CouleurObjectif();}
		}
		function CouleurObjectif()
		{
			nbPresta=0;
			var elements = document.getElementsByClassName("checkPrestation");
			for(var i=0, l=elements.length; i<l; i++){
				if(elements[i].checked == true){
					nbPresta++;
				}
			}
			if(nbPresta>=document.getElementById('objectifDiversite2').value){
				document.getElementById('objectifDiversite').style.backgroundColor="#4fd21c";
			}
			else{
				document.getElementById('objectifDiversite').style.backgroundColor="#e64852";
			}
		}
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

$annee = date("Y",strtotime($_SESSION['FiltreSODA_DateDebut']." +0 month"));
if($annee<=date("Y")){
	$annee=date("Y");
	$semaine=date("W");
}
else{
	$semaine=1;
}

$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

if($_POST)
{
	if(isset($_POST['BtnSave'])){
		$volumeAPlanifier=$_POST['volumeAPlanifie'];
		$objectifDiversite=$_POST['objectifDiversite2'];
		$nbSemaine=0;
		$nbPresta=0;
		$nbQuestionnaire=0;
		$nbSurveillant=0;
		
		for($sem=$semaine;$sem<=52;$sem++)
		{
			if(isset($_POST['Sem_'.$sem])){
				$nbSemaine++;
			}
		}
		
		$req="SELECT Id,Libelle
			FROM new_competences_prestation
			WHERE Id_Plateforme NOT IN (11,14)
			AND SousSurveillance IN ('','Oui/Yes')
			AND Active=0 
			AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." ";
		$req.="ORDER BY RAND() ";
		$result2=mysqli_query($bdd,$req);
		$nbPre=mysqli_num_rows($result2);
		$listePresta="";
		if ($nbPre > 0)
		{
			while($row=mysqli_fetch_array($result2))
			{
				if(isset($_POST['Presta_'.$row['Id']])){
					if($listePresta<>""){$listePresta.=",";}
					$listePresta.=$row['Id'];
					$nbPresta++;
				}
			}
		}
		
		$requetePersonne="
			SELECT DISTINCT
				new_rh_etatcivil.Id,
				CONCAT(Nom, ' ', Prenom) as NomPrenom
			FROM
				new_rh_etatcivil
			INNER JOIN soda_surveillant
			ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
			WHERE new_rh_etatcivil.Id IN (SELECT Id_Surveillant FROM soda_surveillant_theme WHERE Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme'].")
			AND new_rh_etatcivil.Id IN (
				SELECT Id_Personne
				FROM new_competences_personne_plateforme
				WHERE Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']."
			)
			
			UNION 
			
			SELECT DISTINCT Id_Personne AS Id, 
			(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom
			FROM 
				new_competences_relation 
			WHERE 
			(
				Evaluation='L'
				OR
				(Evaluation='X'
				AND Date_Debut<='".date('Y-m-d')."'
				AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
				)
			)
			AND Id_Qualification_Parrainage IN (
				SELECT Id_Qualification
				FROM soda_theme 
				WHERE Id=".$_SESSION['FiltreSODAPlannif_Theme']."
				)
			AND Suppr=0
			AND new_competences_relation.Id_Personne IN (
				SELECT Id_Personne
				FROM new_competences_personne_plateforme
				WHERE Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']."
			)
			";
		$result2=mysqli_query($bdd,$requetePersonne);
		$nbSurv=mysqli_num_rows($result2);
		$listeSurv="";
		if ($nbSurv > 0)
		{
			while($row=mysqli_fetch_array($result2))
			{
				if(isset($_POST['Surveillant_'.$row['Id']])){
					if($listeSurv<>""){$listeSurv.=",";}
					$listeSurv.=$row['Id'];
					$nbSurveillant++;
				}
			}
		}
		
		$resultQ=mysqli_query($bdd,"SELECT Id, Libelle FROM soda_questionnaire WHERE Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme']." AND Actif=0 AND Suppr=0 AND Specifique=0 ORDER BY RAND()");
		$nbQ=mysqli_num_rows($resultQ);
		$listeQ="";
		if($nbQ>0){
			while($rowQ=mysqli_fetch_array($resultQ))
			{
				if(isset($_POST['Questionnaire_'.$rowQ['Id']])){
					if($listeQ<>""){$listeQ.=",";}
					$listeQ.=$rowQ['Id'];
					$nbQuestionnaire++;
				}
			}
		}
		
		if($volumeAPlanifier>0 && $nbSemaine>0 && $nbPresta>0 && $nbQuestionnaire>0){
			$nbVolume=floor($volumeAPlanifier/$nbSemaine);
			if($nbVolume==0){
				$nbVolume=1;
			}
			$recurrence=floor($nbSemaine/$volumeAPlanifier);
			$restant=$volumeAPlanifier-($nbVolume*$nbSemaine);
			
			//Définir quand réaliser les volumes restants
			$recurrenceRestant=0;
			if($restant>0){
				$recurrenceRestant=floor($nbSemaine/$restant);
			}

			//Parcourir en boucle les semaines et relancer la requete prestation et questionnaire pour choisir aléatoirement une nouvelle presta et questionnaire
			$nb=1;
			$nbP=0;
			
			$reqP="SELECT Id,Libelle
				FROM new_competences_prestation
				WHERE Id_Plateforme NOT IN (11,14)
				AND SousSurveillance IN ('','Oui/Yes')
				AND Active=0 
				AND Id IN (".$listePresta.")
				AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." 
				ORDER BY RAND() ";
			$resultPresta=mysqli_query($bdd,$reqP);
			
			$reqQ="SELECT Id, Libelle 
				FROM soda_questionnaire 
				WHERE Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme']." 
				AND Id IN (".$listeQ.")
				AND Actif=0 
				AND Suppr=0 
				AND Specifique=0 
				ORDER BY RAND()";
			$resultQ=mysqli_query($bdd,$reqQ);
			
			if($nbSurveillant>0){
				$requetePersonne="
					SELECT DISTINCT
						new_rh_etatcivil.Id
					FROM
						new_rh_etatcivil
					WHERE 
						new_rh_etatcivil.Id IN (".$listeSurv.")
					ORDER BY RAND()";
				$resultSurv=mysqli_query($bdd,$requetePersonne);
			}
			
			$compteurRecurrence=-1;
			$leVolumePlanifie=1;
			for($sem=$semaine;$sem<=52;$sem++)
			{
				if($leVolumePlanifie<=$volumeAPlanifier){
					if($compteurRecurrence==-1 || $compteurRecurrence>=$recurrence){
						if(isset($_POST['Sem_'.$sem])){
							for($i=1;$i<=$nbVolume;$i++){
								if($leVolumePlanifie<=$volumeAPlanifier){
									$rowP=mysqli_fetch_array($resultPresta);
									$resultQ=mysqli_query($bdd,$reqQ);
									$rowQ=mysqli_fetch_array($resultQ);
									
									$Id_Surveillant=0;
									if($nbSurveillant>0){
										$resultSurv=mysqli_query($bdd,$requetePersonne);
										$rowS=mysqli_fetch_array($resultSurv);
										$Id_Surveillant=$rowS['Id'];
									}
									
									$reqPlannif="SELECT Volume 
									FROM soda_plannifmanuelle 
									WHERE Annee=".$annee." 
									AND Semaine=".$sem." 
									AND Id_Questionnaire=".$rowQ['Id']." 
									AND Id_Prestation=".$rowP['Id']." ";
									$resultPlannif=mysqli_query($bdd,$reqPlannif);
									$nbPlannif=mysqli_num_rows($resultPlannif);
									if($nbPlannif>0){
										$rowPlannif=mysqli_fetch_array($resultPlannif);
										
										$reqUpd="UPDATE soda_plannifmanuelle 
										SET Volume=Volume+1,
										Id_Surveillant=".$Id_Surveillant."
										WHERE Annee=".$annee." 
										AND Semaine=".$sem." 
										AND Id_Questionnaire=".$rowQ['Id']." 
										AND Id_Prestation=".$rowP['Id']." ";
										$resultPlannif=mysqli_query($bdd,$reqUpd);
									}
									else{
										$reqIn="INSERT INTO soda_plannifmanuelle (Annee,Semaine,Id_Questionnaire,Id_Prestation,Volume,Id_Surveillant)
										VALUES (".$annee.",".$sem.",".$rowQ['Id'].",".$rowP['Id'].",1,".$Id_Surveillant.")";
										$resultPlannif=mysqli_query($bdd,$reqIn);
										$leVolumePlanifie++;
									}
									$nbP++;
									if($nbP==$nbPresta){
										$resultPresta=mysqli_query($bdd,$reqP);
										$nbP=0;
									}
								}
							}
							if($restant>0){
								if($leVolumePlanifie<=$volumeAPlanifier){
									if($nb==$recurrenceRestant){
										$rowP=mysqli_fetch_array($resultPresta);
										$resultQ=mysqli_query($bdd,$reqQ);
										$rowQ=mysqli_fetch_array($resultQ);
										
										$Id_Surveillant=0;
										if($nbSurveillant>0){
											$resultSurv=mysqli_query($bdd,$requetePersonne);
											$rowS=mysqli_fetch_array($resultSurv);
											$Id_Surveillant=$rowS['Id'];
										}
										
										$reqPlannif="SELECT Volume 
										FROM soda_plannifmanuelle 
										WHERE Annee=".$annee." 
										AND Semaine=".$sem." 
										AND Id_Questionnaire=".$rowQ['Id']." 
										AND Id_Prestation=".$rowP['Id']." ";
										$resultPlannif=mysqli_query($bdd,$reqPlannif);
										$nbPlannif=mysqli_num_rows($resultPlannif);
										if($nbPlannif>0){
											$rowPlannif=mysqli_fetch_array($resultPlannif);
											
											$reqUpd="UPDATE soda_plannifmanuelle 
											SET Volume=Volume+1,
											Id_Surveillant=".$Id_Surveillant."
											WHERE Annee=".$annee." 
											AND Semaine=".$sem." 
											AND Id_Questionnaire=".$rowQ['Id']." 
											AND Id_Prestation=".$rowP['Id']." ";
											$resultPlannif=mysqli_query($bdd,$reqUpd);
										}
										else{
											$reqIn="INSERT INTO soda_plannifmanuelle (Annee,Semaine,Id_Questionnaire,Id_Prestation,Volume,Id_Surveillant)
											VALUES (".$annee.",".$sem.",".$rowQ['Id'].",".$rowP['Id'].",1,".$Id_Surveillant.")";
											$resultPlannif=mysqli_query($bdd,$reqIn);
											$leVolumePlanifie++;
										}
										$restant=$restant-1;
										$nb=0;
										
										$nbP++;
										if($nbP==$nbPresta){
											$resultPresta=mysqli_query($bdd,$reqP);
											$nbP=0;
										}
									}
								}
							}
							$nb++;
						}
						$compteurRecurrence=0;
					}
				}
				$compteurRecurrence++;
			}
			echo "<script>opener.location.reload();</script>";	
		}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form id="formulaire" method="POST" action="PlannifAuto.php">
	<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
		<tr>
			<td width="20%" valign="top">
				<table style="width:100%; border-spacing:0; align:center;">
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Année";} ?> : <?php echo $annee;?></td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Entity";}else{echo "Entité";} ?> : </td>
					</tr>
					<tr class="TitreColsUsers">
						<td>
						<?php 
							$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$_SESSION['FiltreSODA_Plateforme']." ";
							$result=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($result);
							
							if ($nb > 0)
							{
								$row=mysqli_fetch_array($result);
								echo stripslashes($row['Libelle']);
							}
						?>
						</td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle">
							<?php
								if($_SESSION['Langue']=="FR"){echo "Thématique :";}
								else{echo "Theme :";}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<select name="Id_Theme" id="Id_Theme" onchange="submit();" style="width:200px;">
							<?php
							$theme=$_SESSION['FiltreSODAPlannif_Theme'];
							if($_POST){
								if($theme<>$_POST['Id_Theme']){
									$_SESSION['FiltreSODAPlannif_Questionnaire']=0;
								}
								$theme=$_POST['Id_Theme'];
							}
							else{
								if(isset($_GET['Id_Theme'])){
									$theme=$_GET['Id_Theme'];
								}
								else{
									$theme=0;
								}
							}
							$_SESSION['FiltreSODAPlannif_Theme']=$theme;
							
							//On ne prend pas en compte le thème processus
							$req="SELECT Id, Libelle 
								FROM soda_theme 
								WHERE Suppr=0 
								AND Id<>8
								AND (SELECT COUNT(Id) FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Specifique=0 AND Id_Theme=soda_theme.Id)>0 ";
							if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentSurveillance))){}
							else{
								$req.="AND Id
									IN (SELECT Id 
										FROM soda_theme 
										WHERE Suppr=0 
										AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")
										) ";
							}
							$req.="ORDER BY Libelle ASC";
							$result2=mysqli_query($bdd,$req);
							while($row2=mysqli_fetch_array($result2))
							{
								if ($_SESSION['FiltreSODAPlannif_Theme'] == 0){$_SESSION['FiltreSODAPlannif_Theme'] = $row2['Id'];}
								echo "<option value='".$row2['Id']."'";
								if($_SESSION['FiltreSODAPlannif_Theme']==$row2['Id']){echo " selected";}
								echo ">".$row2['Libelle']."</option>\n";
							}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Volume objectif restant";}else{echo "Remaining target volume";} ?> : </td>
					</tr>
					<tr>
						<td>
							<?php 
								$pourcentageApplicabilite=0;
								$pourcentageDiversite=0;
								$req="SELECT PourcentageApplicabilite, PourcentageDiversite
								FROM soda_objectif_theme
								WHERE Annee=".$annee." 
								AND Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme']." ";
								$result=mysqli_query($bdd,$req);
								$nb=mysqli_num_rows($result);
								if ($nb > 0)
								{
									$rowT=mysqli_fetch_array($result);
									$pourcentageApplicabilite=$rowT['PourcentageApplicabilite']/100;
									$pourcentageDiversite=$rowT['PourcentageDiversite']/100;
								}
								
								$reqPresta="SELECT Id,Libelle
									FROM new_competences_prestation
									WHERE Id_Plateforme NOT IN (11,14)
									AND SousSurveillance IN ('','Oui/Yes')
									AND Active=0 
									AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." 
									ORDER BY Libelle;";
								$resultPresta=mysqli_query($bdd,$reqPresta);
								$nbPresta=mysqli_num_rows($resultPresta);

								$req="SELECT SUM(Volume) AS Vol
								FROM soda_plannifmanuelle 
								WHERE Annee=".$annee." 
								AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme']." AND Specifique=0) 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreSODA_Plateforme']." ";
								$resultVolumePlanifie=mysqli_query($bdd,$req);
								$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);
								
								$volumeObjectif=round(($nbPresta*$pourcentageApplicabilite),0);
								
								$volumePlanifie=0;
								if ($nbVolumePlanifie > 0)
								{
									$rowP=mysqli_fetch_array($resultVolumePlanifie);
									$volumePlanifie=$rowP['Vol'];
								}
								
								$deltaPlanifie=$volumeObjectif-$volumePlanifie;
								$objectifDiversite=round(($nbPresta*$pourcentageDiversite),0);
								if($deltaPlanifie<0){$deltaPlanifie=0;}
								
								echo $deltaPlanifie;
							?>
							<input type="hidden" name="volumeObjectif" value="<?php echo $deltaPlanifie; ?>" />
							<input type="hidden" id="objectifDiversite2" name="objectifDiversite2" value="<?php echo $objectifDiversite; ?>" />
						</td>
					</tr>
					<tr>
						<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Volume à planifier";}else{echo "Volume to be planned";} ?> : </td>
					</tr>
					<tr>
						<td>
							<input type="texte" size="5" onKeyUp="nombre(this)" name="volumeAPlanifie" value="<?php echo $deltaPlanifie; ?>" />
						</td>
					</tr>
				</table>
			</td>
			<td width="25%" valign="top">
				<table style="width:100%; border-spacing:0; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Questionnaire";}else{echo "Questionnaire";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllQuestionnaire" id="selectAllQuestionnaire" checked onclick="SelectionnerTout('Questionnaire')" /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Questionnaire' style='height:200px;width:250px;overflow:auto;'>
								<table>
								<?php
								$result2=mysqli_query($bdd,"SELECT Id, Libelle FROM soda_questionnaire WHERE Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme']." AND Actif=0 AND Suppr=0 AND Specifique=0 ORDER BY Libelle ASC");
								$nbQ=mysqli_num_rows($result2);
								if($nbQ>0){
									$Couleur="#FFFFFF";
									while($rowQ=mysqli_fetch_array($result2))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										echo "<tr bgcolor='".$Couleur."'>
												<td><input class='checkQuestionnaire' type='checkbox' checked value='".$rowQ['Id']."' id='Questionnaire_".$rowQ['Id']."' name='Questionnaire_".$rowQ['Id']."' onclick='CouleurObjectif()' >".stripslashes($rowQ['Libelle'])."</td>
											</tr>";
									}
								}
								?>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td width="25%" valign="top">
				<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllPrestation" id="selectAllPrestation" checked onclick="SelectionnerTout('Prestation')" /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Prestation' style='height:200px;width:250px;overflow:auto;'>
								<table>
							<?php
								if ($nbPresta > 0)
								{
									$Couleur="#FFFFFF";
									$resultPresta=mysqli_query($bdd,$reqPresta);
									while($row=mysqli_fetch_array($resultPresta))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										$presta=substr(trim($row['Libelle']),0,strpos(trim($row['Libelle'])," "));
										if($presta==""){$presta=$row['Libelle'];}
										
										if($presta==""){$presta=$row['Libelle'];}
										echo "<tr bgcolor='".$Couleur."'>
												<td><input class='checkPrestation' type='checkbox' checked value='".$row['Id']."' id='Presta_".$row['Id']."' name='Presta_".$row['Id']."' onclick='CouleurObjectif()' >".$presta."</td>
											</tr>";
									}
								}
							?>
								</table>
							</div>
						</td>
					</tr>
				</table>
				<table>
					<tr>
						<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Objectif diversité prestation";}else{echo "Site diversity objective";} ?> : </td>
					</tr>
					<tr>
						<td id="objectifDiversite" bgcolor="<?php if($nbPresta>=$objectifDiversite){echo "#4fd21c";}else{echo "#e64852";} ?>" >
							<?php echo $objectifDiversite; ?>
						</td>
					</tr>
				</table>
			</td>
			<td width="10%" valign="top">
				<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Week";}else{echo "Semaine";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllSemaine" id="selectAllSemaine" checked onclick="SelectionnerTout('Semaine')" /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
						<td>
							<div id='Div_Semaine' style='height:200px;width:100px;overflow:auto;'>
								<table>
							<?php
								$Couleur="#FFFFFF";
								for($sem=$semaine;$sem<=52;$sem++)
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									echo "<tr bgcolor='".$Couleur."'>
											<td><input class='checkSemaine' type='checkbox' checked value='".$sem."' id='Sem_".$sem."' name='Sem_".$sem."' >S".$sem."</td>
										</tr>";
								}
							?>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td width="30%" valign="top">
				<table style="width:100%; border-spacing:0; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Supervisor";}else{echo "Surveillant";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllSurveillant" id="selectAllSurveillant" onclick="SelectionnerTout('Surveillant')" /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Surveillant' style='height:200px;width:200px;overflow:auto;'>
								<table>
								<?php
								$requetePersonne="
									SELECT DISTINCT
										new_rh_etatcivil.Id,
										CONCAT(Nom, ' ', Prenom) as NomPrenom,
										(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) 
												FROM new_competences_personne_prestation 
												WHERE Id_Personne=new_rh_etatcivil.Id 
												AND Date_Debut<='".date('Y-m-d')."'
												AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
												ORDER BY Date_Debut DESC
												LIMIT 1
										) AS Presta
									FROM
										new_rh_etatcivil
									INNER JOIN soda_surveillant
									ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
									WHERE new_rh_etatcivil.Id IN (SELECT Id_Surveillant FROM soda_surveillant_theme WHERE Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme'].")
									AND new_rh_etatcivil.Id IN (
										SELECT Id_Personne
										FROM new_competences_personne_plateforme
										WHERE Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']."
									)
									
									UNION 
									
									SELECT DISTINCT Id_Personne AS Id, 
									(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom,
										(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) 
												FROM new_competences_personne_prestation 
												WHERE Id_Personne=new_competences_relation.Id_Personne 
												AND Date_Debut<='".date('Y-m-d')."'
												AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
												ORDER BY Date_Debut DESC
												LIMIT 1
										) AS Presta
									FROM 
										new_competences_relation 
									WHERE 
									(
										Evaluation='L'
										OR
										(Evaluation='X'
										AND Date_Debut<='".date('Y-m-d')."'
										AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
										)
									)
									AND Suppr=0
									AND Id_Qualification_Parrainage IN (
										SELECT Id_Qualification
										FROM soda_theme 
										WHERE Id=".$_SESSION['FiltreSODAPlannif_Theme']."
										)
									AND new_competences_relation.Id_Personne IN (
										SELECT Id_Personne
										FROM new_competences_personne_plateforme
										WHERE Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']."
									)
									ORDER BY NomPrenom ASC";
		
								$result2=mysqli_query($bdd,$requetePersonne);
								$nbS=mysqli_num_rows($result2);
								if($nbS>0){
									$Couleur="#FFFFFF";
									while($rowS=mysqli_fetch_array($result2))
									{
										$requete="
											SELECT DISTINCT
												new_rh_etatcivil.Id,
												CONCAT(Nom, ' ', Prenom) as NomPrenom
											FROM
												new_rh_etatcivil
											INNER JOIN soda_surveillant
											ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
											WHERE new_rh_etatcivil.Id IN (SELECT Id_Surveillant FROM soda_surveillant_theme WHERE Id_Theme=".$_SESSION['FiltreSODAPlannif_Theme'].")
											AND new_rh_etatcivil.Id=".$rowS['Id']." ";
										$resultV2=mysqli_query($bdd,$requete);
										$nbV2=mysqli_num_rows($resultV2);
										
										$requete="
											SELECT DISTINCT Id_Personne AS Id, 
											(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom
											FROM 
												new_competences_relation 
											WHERE 
											Evaluation='L'
											AND Id_Qualification_Parrainage IN (
												SELECT Id_Qualification
												FROM soda_theme 
												WHERE Id=".$_SESSION['FiltreSODAPlannif_Theme']."
												)
											AND Suppr=0
											AND new_competences_relation.Id_Personne=".$rowS['Id']." ";
										$resultV2QualifEnFormation=mysqli_query($bdd,$requete);
										$nbV2QualifEnFormation=mysqli_num_rows($resultV2QualifEnFormation);
								
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										echo "<tr bgcolor='".$Couleur."'>
												<td><input class='checkSurveillant' type='checkbox' value='".$rowS['Id']."' id='Surveillant_".$rowS['Id']."' name='Surveillant_".$rowS['Id']."' >".stripslashes($rowS['NomPrenom'])." [".$rowS['Presta']."]";
										if($nbV2QualifEnFormation>0 && $nbV2==0){
											 if($_SESSION['Langue']=="FR"){echo "<i> [En formation] </i>";}
											 else{echo "<i> [In training] </i>";}
										}
										echo "</td>
											</tr>";
									}
								}
								?>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td colspan="5" align="center">
				<input class="Bouton" name="BtnSave" type="submit" 
					<?php if($_SESSION['Langue']=="FR"){echo "value='Générer'";}else{echo "value='Generate'";}?>
				/>
			</td>
		</tr>
	</table>
</form>	
</body>
</html>