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
			var elements2 = document.getElementsByClassName("input"+Champ);
			var elements3 = document.getElementsByClassName("select"+Champ);
			if (document.getElementById('selectAll'+Champ).checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;elements2[i].style.display="";elements3[i].style.display="";}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;elements2[i].style.display="none";elements3[i].style.display="none";}
			}
		}
		function AfficherInput(Champ)
		{
			if (document.getElementById('Presta_'+Champ).checked == true)
			{
				document.getElementById('volume_'+Champ).style.display="";
				document.getElementById('Id_Surveillant_'+Champ).style.display="";
			}
			else
			{
				document.getElementById('volume_'+Champ).style.display="none";
				document.getElementById('Id_Surveillant_'+Champ).style.display="none";
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

$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

if($_POST)
{
	if(isset($_POST['BtnSave'])){
		$req="SELECT Id,Libelle,
			(SELECT COUNT(Id)
					FROM soda_plannifmanuelle 
					WHERE Annee=".$_POST['annee']."
					AND Semaine=".$_POST['semaine']."
					AND Id_Questionnaire=".$_POST['Id_Questionnaire']."
					AND Id_Prestation=new_competences_prestation.Id) AS NbPlanif
			FROM new_competences_prestation
			WHERE Id_Plateforme NOT IN (11,14)
			AND (SousSurveillance IN ('','Oui/Yes')
				OR (SELECT COUNT(Id)
				FROM soda_plannifmanuelle 
				WHERE Annee=".$_POST['annee']."
				AND Semaine=".$_POST['semaine']."
				AND Id_Questionnaire=".$_POST['Id_Questionnaire']."
				AND Id_Prestation=new_competences_prestation.Id)>0
			)
			AND (Active=0 
				OR (SELECT COUNT(Id)
				FROM soda_plannifmanuelle 
				WHERE Annee=".$_POST['annee']."
				AND Semaine=".$_POST['semaine']."
				AND Id_Questionnaire=".$_POST['Id_Questionnaire']."
				AND Id_Prestation=new_competences_prestation.Id)>0
			) 
			AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." ";
		if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
			
		}
		else{
			$req.="AND (Id_Plateforme IN (
				SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
			)
			OR 
			Id_Plateforme IN (
				SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
				)
			) ";
		}
		$result2=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result2);
		
		if ($nb > 0)
		{
			while($row=mysqli_fetch_array($result2))
			{
				//Si prestation sélectionnée
				if(isset($_POST['Presta_'.$row['Id']]) && $_POST['volume_'.$row['Id']]<>""){
					//Si une plannif existe
					if($row['NbPlanif']>0){
						//Volume = Volume + (Volume saisie - Volume restant)
						$req="UPDATE soda_plannifmanuelle 
						SET Volume=Volume+(".$_POST['volume_'.$row['Id']]."-".$_POST['volumeRestant_'.$row['Id']]."),
						Id_Surveillant=".$_POST['Id_Surveillant_'.$row['Id']]."
						WHERE Annee=".$_POST['annee']." 
						AND Semaine=".$_POST['semaine']." 
						AND Id_Questionnaire=".$_POST['Id_Questionnaire']." 
						AND Id_Prestation=".$row['Id']." ";
						$result=mysqli_query($bdd,$req);
					}
					else{
						//Sinon créer la planif
						$req="INSERT INTO soda_plannifmanuelle (Annee,Semaine,Id_Questionnaire,Id_Prestation,Volume,Id_Surveillant)
						VALUES (".$_POST['annee'].",".$_POST['semaine'].",".$_POST['Id_Questionnaire'].",".$row['Id'].",".$_POST['volume_'.$row['Id']].",".$_POST['Id_Surveillant_'.$row['Id']].")";
						$result=mysqli_query($bdd,$req);
					}
				}
				else{
					//Sinon  (la prestation est déselectionnée ou pas de volume) alors Volume = Volume - Volume restant
					//(si planif existante)
					if($row['NbPlanif']>0){
						$req="UPDATE soda_plannifmanuelle 
						SET Volume=Volume-".$_POST['volumeRestant_'.$row['Id']]."
						WHERE Annee=".$_POST['annee']." 
						AND Semaine=".$_POST['semaine']." 
						AND Id_Questionnaire=".$_POST['Id_Questionnaire']." 
						AND Id_Prestation=".$row['Id']." ";
						$result=mysqli_query($bdd,$req);
					}
				}
			}
		}
		echo "<script>opener.location.reload();</script>";
	}
}

?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form id="formulaire" method="POST" action="PlannifManuelle.php">
	<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
		<input type="hidden" name="get_annee" value="<?php if($_GET){echo $_GET['Annee'];}else{echo $_POST['get_annee'];} ?>" />
		<input type="hidden" name="get_semaine" value="<?php if($_GET){echo $_GET['Semaine'];}else{echo $_POST['get_semaine'];} ?>" />
		<tr>
			<td width="50%" valign="top">
				<table style="width:100%; border-spacing:0; align:center;">
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Entity";}else{echo "Entité";} ?> : </td>
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
						<td class="Libelle"><?php
								if($_SESSION['Langue']=="FR"){echo "Année :";}
								else{echo "Year :";}
							?> 
						</td>
					</tr>
					<tr>
						<td>
							<select name="annee" id="annee" onchange="submit();">
								<?php
									if($_GET){$get_annee=$_GET['Annee'];}else{$get_annee=$_POST['get_annee'];}
									if($_GET){$annee=$_GET['Annee'];}else{$annee=$_POST['annee'];}
									for($i=$get_annee-1;$i<=$get_annee+1;$i++){
										$selected="";
										if($i==$annee){$selected="selected";}
										echo "<option name='".$i."' ".$selected.">".$i."</option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php
								if($_SESSION['Langue']=="FR"){echo "Semaine :";}
								else{echo "Week :";}
							?> 
						</td>
					</tr>
					<tr>
						<td>
							<select name="semaine" id="semaine" onchange="submit();">
								<?php
									if($_GET){$get_semaine=$_GET['Semaine'];}else{$get_semaine=$_POST['get_semaine'];}
									if($_GET){$semaine=$_GET['Semaine'];}else{$semaine=$_POST['semaine'];}
									for($i=1;$i<=52;$i++){
										$selected="";
										if($i==$semaine){$selected="selected";}
										echo "<option name='".$i."' ".$selected.">".$i."</option>";
									}
								?>
							</select>
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
							<select name="Id_Theme" id="Id_Theme" onchange="submit();">
							<?php
							$theme=$_SESSION['FiltreSODAPlannif_Theme'];
							if($_POST){
								if($theme<>$_POST['Id_Theme']){
									$_SESSION['FiltreSODAPlannif_Questionnaire']=0;
								}
								$theme=$_POST['Id_Theme'];
							}
							else{
								$theme=$_GET['Id_Theme'];
							}
							$_SESSION['FiltreSODAPlannif_Theme']=$theme;
							
							$req="SELECT Id, Libelle 
								FROM soda_theme 
								WHERE Suppr=0  ";
							if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteChargeMissionOperation,$IdPosteReferentSurveillance))
							|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){}
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
								echo "<option value='".$row2['Id']."'";
								if($theme==$row2['Id']){echo " selected";}
								echo ">".$row2['Libelle']."</option>\n";
							}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="Libelle">Questionnaire : </td>
					</tr>
					<tr>
						<td>
							<select name="Id_Questionnaire" id="Id_Questionnaire" onchange="submit();">
							<?php
							$questionnaire=$_SESSION['FiltreSODAPlannif_Questionnaire'];
							if($_POST){
								if($questionnaire<>0){
									$questionnaire=$_POST['Id_Questionnaire'];
								}
							}
							else{$questionnaire=$_GET['Id_Questionnaire'];}
							$_SESSION['FiltreSODAPlannif_Questionnaire']=$questionnaire;
							
							$result2=mysqli_query($bdd,"SELECT Id, Libelle FROM soda_questionnaire WHERE Id_Theme=".$theme." AND Actif=0 AND Suppr=0 ORDER BY Libelle ASC");
							while($row2=mysqli_fetch_array($result2))
							{
								echo "<option value='".$row2['Id']."'";
								if($questionnaire==0){
									$questionnaire=$row2['Id'];
									$_SESSION['FiltreSODAPlannif_Questionnaire']=$questionnaire;
									echo " selected";
								}
								elseif($questionnaire==$row2['Id']){echo " selected";}
								echo ">".stripslashes($row2['Libelle'])."</option>\n";
							}
							?>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td width="50%">
				<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Volume of scheduled monitoring";}else{echo "Volume de surveillances planifiées";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllPrestation" id="selectAllPrestation" onclick="SelectionnerTout('Prestation')" /><?php if($_SESSION['Langue']=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
						
					</tr>
					<tr>
						<td>
							<div id='Div_Prestation' style='height:200px;width:500px;overflow:auto;'>
								<table>
							<?php
								$req="SELECT Id,Libelle,
									(SELECT Volume-(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 AND Id_PlannifManuelle=soda_plannifmanuelle.Id ) FROM soda_plannifmanuelle WHERE Annee=".$annee." AND Semaine=".$semaine." AND Id_Questionnaire=".$questionnaire." AND Id_Prestation=new_competences_prestation.Id LIMIT 1) AS Volume,
									(SELECT Id_Surveillant FROM soda_plannifmanuelle WHERE Annee=".$annee." AND Semaine=".$semaine." AND Id_Questionnaire=".$questionnaire." AND Id_Prestation=new_competences_prestation.Id LIMIT 1) AS Id_Surveillant
									FROM new_competences_prestation
									WHERE Id_Plateforme NOT IN (11,14) 
									AND (SousSurveillance IN ('','Oui/Yes')
										OR (SELECT COUNT(Id)
										FROM soda_plannifmanuelle 
										WHERE Annee=".$annee."
										AND Semaine=".$semaine."
										AND Id_Questionnaire=".$questionnaire."
										AND Id_Prestation=new_competences_prestation.Id)>0
									)
									AND (Active=0 
										OR (SELECT COUNT(Id)
										FROM soda_plannifmanuelle 
										WHERE Annee=".$annee."
										AND Semaine=".$semaine."
										AND Id_Questionnaire=".$questionnaire."
										AND Id_Prestation=new_competences_prestation.Id)>0
									)
									AND Id_Plateforme=".$_SESSION['FiltreSODA_Plateforme']." ";
								if($nbAccess>0 || $nbSuperAdmin>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
									
								}
								else{
									$req.="AND (Id_Plateforme IN (
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentSurveillance.")
									)
									OR 
									Id_Plateforme IN (
										SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
										)
									) ";
								}
								$req.="ORDER BY Libelle;";
							
								$result=mysqli_query($bdd,$req);
								$nb=mysqli_num_rows($result);
								
								if ($nb > 0)
								{
									$Couleur="#FFFFFF";
									while($row=mysqli_fetch_array($result))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										$presta=substr(trim($row['Libelle']),0,strpos(trim($row['Libelle'])," "));
										if($presta==""){$presta=$row['Libelle'];}
										$selected="";
										$display="style='display:none;'";
										$display2="display:none;";
										$volume="1";
										$volumeRestant="0";
										$Id_Surveillant=0;
										if($row['Volume']>0){
											$selected="checked";$display="";$display2="";
											$volume=$row['Volume'];
											$volumeRestant=$row['Volume'];
											$Id_Surveillant=$row['Id_Surveillant'];
										}
										echo "<tr bgcolor='".$Couleur."'>
												<td><input class='checkPrestation' type='checkbox' ".$selected." value='".$row['Id']."' id='Presta_".$row['Id']."' name='Presta_".$row['Id']."' onclick='AfficherInput(".$row['Id'].")' >".$presta."</td>
												<td><input class='inputPrestation' ".$display." onKeyUp='nombre(this)' value='".$volume."' size='6' id='volume_".$row['Id']."' name='volume_".$row['Id']."'>
													<input class='inputPrestationRestant' style='display:none;' onKeyUp='nombre(this)' value='".$volumeRestant."' size='6' id='volumeRestant_".$row['Id']."' name='volumeRestant_".$row['Id']."'>
												</td>
												<td>";
												echo "<select class'selectPrestation'  id='Id_Surveillant_".$row['Id']."' name='Id_Surveillant_".$row['Id']."' style='width:200px;".$display2."'>
														<option value='0'></option>";
														
													$requetePersonne="
														SELECT DISTINCT
															new_rh_etatcivil.Id,
															CONCAT(Nom, ' ', Prenom) as NomPrenom
														FROM
															new_rh_etatcivil
														INNER JOIN soda_surveillant
														ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
														WHERE new_rh_etatcivil.Id IN (SELECT Id_Surveillant FROM soda_surveillant_theme WHERE Id_Theme=".$theme.")
														
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
														AND Suppr=0
														AND Id_Qualification_Parrainage IN (
															SELECT Id_Qualification
															FROM soda_theme 
															WHERE Id=".$theme."
															)
														ORDER BY NomPrenom ASC";
													$result_Personne= mysqli_query($bdd,$requetePersonne);
													while ($row_Personne=mysqli_fetch_array($result_Personne))
													{
														$selected="";
														if($Id_Surveillant==$row_Personne['Id']){$selected="selected";}
														
														$requete="
															SELECT DISTINCT
																new_rh_etatcivil.Id,
																CONCAT(Nom, ' ', Prenom) as NomPrenom
															FROM
																new_rh_etatcivil
															INNER JOIN soda_surveillant
															ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
															WHERE new_rh_etatcivil.Id IN (SELECT Id_Surveillant FROM soda_surveillant_theme WHERE Id_Theme=".$theme.")
															AND new_rh_etatcivil.Id=".$row_Personne['Id']." ";
														$resultV2=mysqli_query($bdd,$requete);
														$nbV2=mysqli_num_rows($resultV2);
														
														$requete="
															SELECT DISTINCT Id_Personne AS Id, 
															(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom
															FROM 
																new_competences_relation 
															WHERE 
															Evaluation='L'
															AND Suppr=0
															AND Id_Qualification_Parrainage IN (
																SELECT Id_Qualification
																FROM soda_theme 
																WHERE Id=".$theme."
																)
															AND new_competences_relation.Id_Personne=".$row_Personne['Id']." ";
														$resultV2QualifEnFormation=mysqli_query($bdd,$requete);
														$nbV2QualifEnFormation=mysqli_num_rows($resultV2QualifEnFormation);
														
														echo "<option value='".$row_Personne['Id']."' ".$selected.">".$row_Personne['NomPrenom'];
														if($nbV2QualifEnFormation>0 && $nbV2==0){
															 if($_SESSION['Langue']=="FR"){echo "<i> [En formation] </i>";}
															 else{echo "<i> [In training] </i>";}
														}
														echo "</option>\n";
													}
												
										echo "		</select>
												</td>
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
			<td colspan="2" align="center">
				<input class="Bouton" name="BtnSave" type="submit" 
					<?php if($_SESSION['Langue']=="FR"){echo "value='Enregistrer'";}else{echo "value='Save'";}?>
				/>
			</td>
		</tr>
	</table>
</form>	
</body>
</html>