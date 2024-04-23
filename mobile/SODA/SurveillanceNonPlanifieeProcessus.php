<html>
<head>
	<title>AAA</title><meta name="robots" content="noindex">
	<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../css/FeuilleMobile.css" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Dosis'><link rel="stylesheet" href="../style.css">
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/jquery-1.4.3.min.js"></script>
	<script src="../JS/jquery-ui-1.8.5.min.js"></script>
	<script>
		function VerifChampsSurveillance()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.nbQuestions2.value=='' || formulaire.nbQuestions2.value=='0'){alert('Vous n\'avez pas renseigné de sous-thématique.');return false;}
				return true;
			}
			else{
				if(formulaire.nbQuestions2.value=='' || formulaire.nbQuestions2.value=='0'){alert('You have not entered a sub-theme.');return false;}
				return true;
			}
		}
		Liste_Questionnaire = new Array();
		function CalculerQuestionnaire(){
			nbQuestions=0;
			var Elements_Obj = document.getElementsByClassName("theme");
			for(var k=0, l=Elements_Obj.length; k<l; k++){
				if(Elements_Obj[k].checked){
					for(m=0; m<3;m++){
						if(document.getElementById("Id_Questionnaire_"+Elements_Obj[k].value+"_"+m) != null){
							if(document.getElementById("Id_Questionnaire_"+Elements_Obj[k].value+"_"+m).value!=0){
								laQuestion=0;
								for(i=0;i<Liste_Questionnaire.length;i++){
									if(Liste_Questionnaire[i][0]==document.getElementById("Id_Questionnaire_"+Elements_Obj[k].value+"_"+m).value){
										if(Liste_Questionnaire[i][1]==0){laQuestion=Liste_Questionnaire[i][2];}
										else{
											if(Liste_Questionnaire[i][1]<Liste_Questionnaire[i][2]){laQuestion=Liste_Questionnaire[i][1];}
											else{laQuestion=Liste_Questionnaire[i][2];}
										}
									}
								}
								nbQuestions=nbQuestions+laQuestion;
							}
						}
					}
				}
			}
			document.getElementById("nbQuestions").value=nbQuestions;
			document.getElementById("nbQuestions2").value=nbQuestions;
			duree=nbQuestions*2;
			laDuree="";
			if(duree>0 && duree<=5){laDuree="0-5 minutes";}
			else if(duree<=10){laDuree="5-10 minutes";}
			else if(duree<=20){laDuree="10-20 minutes";}
			else if(duree<=30){laDuree="20-30 minutes";}
			else if(duree<=45){laDuree="30-45 minutes";}
			else if(duree<=60){laDuree="45-60 minutes";}
			else{laDuree="60+ minutes";}
			document.getElementById("nbQuestions").innerHTML=nbQuestions;
			document.getElementById("dureeApproximative").innerHTML=laDuree;
		}
		function AfficherQA(Id_Questionnaire,Num){
			document.getElementById('BtnPlus_'+Id_Questionnaire+'_'+Num).style.display='none';
			Num=Num+1;
			document.getElementById('Question_'+Id_Questionnaire+'_'+Num).style.display='';
		}
	</script>
</head>
<body style="background-color:#cccccc;">

<?php
require("../Connexioni.php");
require("../../v2/Outils/Formation/Globales_Fonctions.php");
require("../../v2/Outils/Fonctions.php");
require("../Menu.php");

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;
$IdCreeMere=0;

if($_POST)
{
	if(isset($_POST['btnValider']))
	{
		$tabIdCree=array();
		$m=0;
		if(isset($_POST['themes'])){
			foreach($_POST['themes'] as $value){
				for($p=0;$p<3;$p++){
					if(isset($_POST['Id_Questionnaire_'.$value.'_'.$p]) && $_POST['Id_Questionnaire_'.$value.'_'.$p]<>0){
						$req="SELECT Id, Libelle,NonAleatoire,
						soda_questionnaire.NbQuestion,
						(
							SELECT COUNT(soda_question.Id) 
							FROM soda_question 
							WHERE soda_question.Suppr=0 
							AND soda_question.Id_Questionnaire=soda_questionnaire.Id
							AND (
								SELECT COUNT(soda_question_exceptionuer.Id) 
								FROM soda_question_exceptionuer 
								WHERE soda_question_exceptionuer.Suppr=0 
								AND soda_question_exceptionuer.Id_Question=soda_question.Id
								AND soda_question_exceptionuer.Id_Plateforme=".$_POST['Id_Plateforme']."
							)=0
						) AS lesQuestions
						FROM soda_questionnaire 
						WHERE Id=".$_POST['Id_Questionnaire_'.$value.'_'.$p]." 
						AND Suppr=0
						AND Actif=0 ";
						$resultQuestionnaire=mysqli_query($bdd,$req);
						$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
						
						if($nbQuestionnaire>0){
							$rowQ=mysqli_fetch_array($resultQuestionnaire);
							$nbQuestion=0;
							if($rowQ['NbQuestion']==0){$nbQuestion=$rowQ['lesQuestions'];}
							else{
								if($rowQ['NbQuestion']<$rowQ['lesQuestions']){$nbQuestion=$rowQ['NbQuestion'];}
								else{$nbQuestion=$rowQ['lesQuestions'];}
							}
							
							//Liste des questions 
							$req="SELECT Id, Ponderation
							FROM soda_question 
							WHERE Suppr=0 
							AND soda_question.Id_Questionnaire=".$rowQ['Id']."
							AND (
								SELECT COUNT(soda_question_exceptionuer.Id) 
								FROM soda_question_exceptionuer 
								WHERE soda_question_exceptionuer.Suppr=0 
								AND soda_question_exceptionuer.Id_Question=soda_question.Id
								AND soda_question_exceptionuer.Id_Plateforme=".$_POST['Id_Plateforme']."
							)=0
							";
							$resultQuestion=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($resultQuestion);
							
							$tabQuestionsFinales=array();
							$tabQuestionsFinales2=array();
							$tab4=array();
							$tab123=array();
							if($nb>0){
								$i=0;
								while($rowQuestion=mysqli_fetch_array($resultQuestion)){
									if($rowQuestion['Ponderation']==4){
										$tab4[$i]=$rowQuestion['Id'];
										$i++;
									}
								}
								if(sizeof($tab4)<=$nbQuestion){
									for($i=0;$i<sizeof($tab4);$i++){
										$tabQuestionsFinales[$i]=array($tab4[$i],4);
									}
								}
								else{
									//choix aléatoire des questions en pondération 4 
									if($rowQ['NonAleatoire']==0){
										shuffle($tab4);
									}
									for($i=0;$i<$nbQuestion;$i++){
										$tabQuestionsFinales[$i]=array($tab4[$i],4);
									}
								}
								
								//Récupération des pondérations 1,2,3
								$resultQuestion=mysqli_query($bdd,$req);
								$i=0;
								while($rowQuestion=mysqli_fetch_array($resultQuestion)){
									if($rowQuestion['Ponderation']==1 || $rowQuestion['Ponderation']==2 || $rowQuestion['Ponderation']==3){
										//Vérifier si pondération à doubler (suite à 2 NC)
										$req="SELECT Etat
										FROM 
											(
												SELECT soda_surveillance_question.Etat
												FROM soda_surveillance_question
												LEFT JOIN soda_surveillance
												ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
												WHERE soda_surveillance.Suppr=0
												AND soda_surveillance.AutoSurveillance=0 
												AND soda_surveillance.Etat='Clôturé'
												AND soda_surveillance.Id_Plateforme=".$_POST['Id_Plateforme']."
												AND soda_surveillance_question.Id_Question=".$rowQuestion['Id']."
												ORDER BY soda_surveillance.DateSurveillance DESC
												LIMIT 2
											) AS TAB
										WHERE Etat='NC'
										";
										$resultNC=mysqli_query($bdd,$req);
										$nbNC=mysqli_num_rows($resultNC);
										$ponderation=$rowQuestion['Ponderation'];
										if($nbNC==2){
											if($ponderation<3){
												$ponderation=$ponderation+1;
											}
										}
										for($k=1;$k<=$ponderation;$k++){
											$tab123[$i]=array($rowQuestion['Id'],$ponderation);
											$i++;
										}
									}
								}
								
								//choix aléatoire des questions en pondération 1,2,3
								if($rowQ['NonAleatoire']==0){
									shuffle($tab123);
								}
								$j=0;
								for($i=sizeof($tabQuestionsFinales);$i<$nbQuestion;$i++){
									$ajout=0;
									while($ajout==0){
										$tabTrouve=$tabQuestionsFinales;
										$trouve=0;
										foreach($tabTrouve as $questionTrouve){
											if($questionTrouve[0]==$tab123[$j][0]){$trouve=1;}
										}
										if($trouve==0){
											$tabQuestionsFinales[$i]=array($tab123[$j][0],$tab123[$j][1]);
											$ajout=1;
										}
										$j++;
									}
								}
							}
							if($rowQ['NonAleatoire']==0){
								shuffle($tabQuestionsFinales);
							}
							
							//Vérifier Si la personne peut faire les surveillances de cette thématique
							$req="SELECT Id FROM soda_surveillant_theme WHERE Id_Surveillant=".$_SESSION['Id_Personne']." AND Id_Theme=".$value." ";
							$resultSurvTheme=mysqli_query($bdd,$req);
							$nbSurvTheme=mysqli_num_rows($resultSurvTheme);
							
							$req="SELECT Id FROM soda_theme WHERE Id=".$value." AND Id_Qualification IN (
								SELECT DISTINCT Id_Qualification_Parrainage 
								FROM new_competences_relation 
								WHERE (Evaluation='X'
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
								)
								AND Suppr=0
								AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
								AND Id_Personne=".$_SESSION['Id_Personne']."
							) ";
							$resultSurTheme= mysqli_query($bdd,$req);	
							$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
							
							$req="SELECT Id FROM soda_theme WHERE Id=".$value." AND Id_Qualification IN (
								SELECT DISTINCT Id_Qualification_Parrainage 
								FROM new_competences_relation 
								WHERE Evaluation='L'
								AND Suppr=0
								AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
								AND Id_Personne=".$_SESSION['Id_Personne']."
							) ";
							$resultSurTheme= mysqli_query($bdd,$req);	
							$nbSurvThemeECQualifie=mysqli_num_rows($resultSurTheme);
							
							$EnFormation=0;
							if($nbSurvThemeECQualifie>0){$EnFormation=1;}
							elseif($nbSurvTheme>0 || $nbSurvThemeQualifie>0){$EnFormation=0;}
							
							$req="INSERT INTO soda_surveillance (Id_PlannifManuelle,Id_SurveillanceMere,Id_Questionnaire,Id_Plateforme,Id_Surveillant,Id_Surveille,Id_Metier,AutoSurveillance,DateSurveillance,Etat,DateHeureCreation,Enformation) 
							VALUES (0,".$IdCreeMere.",".$_POST['Id_Questionnaire_'.$value.'_'.$p].",".$_POST['Id_Plateforme'].",".$_SESSION['Id_Personne'].",".$_POST['Id_Surveille']."
							,".$_POST['Id_Metier'].",0,'".date('Y-m-d')."','Planifié','".date("Y-m-d H:i:s")."',".$EnFormation.") ";
							$resultInsert=mysqli_query($bdd,$req);
							$IdCree = mysqli_insert_id($bdd);
							if($IdCreeMere==0){$IdCreeMere=$IdCree;}
							
							$tabIdCree[$m]=$IdCree;
							$m++;
							
							$Id_Question="";
							foreach($tabQuestionsFinales as $question){
								if($Id_Question<>""){$Id_Question.=",";}
								$Id_Question.=$question[0];
							}
							
							
							if($Id_Question<>""){
								$i2=0;
								$req="SELECT Id FROM soda_question WHERE Id IN (".$Id_Question.") ORDER BY Ordre ";
								$resultQuestion2=mysqli_query($bdd,$req);
								$nb2=mysqli_num_rows($resultQuestion2);
								if($nb2>0){
									while($rowQuestion2=mysqli_fetch_array($resultQuestion2)){
										foreach($tabQuestionsFinales as $question){
											if($question[0]==$rowQuestion2['Id']){
												$tabQuestionsFinales2[$i2]=array($question[0],$question[1]);
												$i2++;
											}
										}
									}
								}
							}
							
							foreach($tabQuestionsFinales2 as $question){
								$req="INSERT INTO soda_surveillance_question (Id_Surveillance,Id_Question,Ponderation) 
									VALUES (".$IdCree.",".$question[0].",".$question[1].") ";
									$resultInsert=mysqli_query($bdd,$req);
							}
						}
					}
				}
			}
		}
		echo "<script>window.location='RealiserSurveillance2.php?Id_SurveillanceMere=".$IdCreeMere."';</script>";
	}
	
}
?>
<form class="test" id="formulaire" action="SurveillanceNonPlanifieeProcessus.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr bgcolor="#91dfff" >
		<td colspan="3" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;border-style:outset;">
			<span style="font-size:3em;">
			SODA<br>
			</span>
			<span style="font-size:2.5em;">
			<?php if($LangueAffichage=="FR"){echo "Surveillance Opérationnelle Dé-matérialisée Analytique";}else{echo "Digital Adaptive Operational Monitoring";}?>
			</span>
		</td>
	</tr>
</table>
<br>
<table class="TableCompetences" style="width:100%; height:100%; align:center;">
	<tr>
		<td class="LibelleMobile" width="10%"><?php if($LangueAffichage=="FR"){echo "Surveillant";}else{echo "Supervisor";}?> : </td>
		<td class="LibelleMobile" width="10%">
			<?php $req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_Personne']; 
			$resultP=mysqli_query($bdd,$req);
			$rowP=mysqli_fetch_array($resultP);
			echo $rowP['Personne'];
			?>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td width="20%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
		<td width="25%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Surveillé : ";}else{echo "Supervised : ";}?></td>
		<td width="25%" class="LibelleMobile"><?php if($_SESSION['Langue']=="FR"){echo "Métier : ";}else{echo "Job : ";}?></td>
	</tr>
	<tr>
		<td width="20%">
			<select id="Id_Plateforme" name="Id_Plateforme" class="Mobile" onchange="submit();" >
				<option value="0"></option>
				<?php
				$Id_Plateforme=0;
				if($_POST){
					$Id_Plateforme=$_POST['Id_Plateforme'];
				}
				$reqPlat="SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ORDER BY Libelle ASC";
				$result2=mysqli_query($bdd,$reqPlat);
				while($row2=mysqli_fetch_array($result2))
				{
					$selected="";
					if($_POST){
						if($Id_Plateforme==$row2['Id']){$selected="selected";}
					}
					else{
						if($Id_Plateforme==0){
							$Id_Plateforme=$row2['Id'];
							$selected="selected";
						}
					}
					echo "<option value='".$row2['Id']."' ".$selected.">".$row2['Libelle']."</option>";
				}
				?>
			</select>
		</td>
		<td width="25%">
			<select id="Id_Surveille" name="Id_Surveille" class="Mobile" onchange="submit()">
				<option value="0"></option>
				<?php
				$Id_Surveille=0;
				if($_POST){
					if($_POST['Id_Plateforme']==$_POST['oldPlateforme']){
						if(isset($_POST['Id_Surveille'])){$Id_Surveille=$_POST['Id_Surveille'];}
					}
					if($_POST['Id_Surveille']<>$_POST['oldSurveille']){$Id_Metier=0;}
				}
				$requetePersonne="
					SELECT DISTINCT
						new_rh_etatcivil.Id,
						CONCAT(Nom, ' ', Prenom) as NomPrenom
					FROM
						new_rh_etatcivil
					INNER JOIN new_competences_personne_prestation
						ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne
					WHERE
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation)=".$Id_Plateforme." 
						AND Date_Debut<='".date('Y-m-d')."'
						AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
					ORDER BY NomPrenom ASC";
				$result_Personne= mysqli_query($bdd,$requetePersonne);
				while ($row_Personne=mysqli_fetch_array($result_Personne))
				{
					$selected="";
					if($_POST['Id_Plateforme']==$_POST['oldPlateforme']){
						if($Id_Surveille==$row_Personne['Id']){$selected="selected";}
					}
					echo "<option value='".$row_Personne['Id']."' ".$selected.">".$row_Personne['NomPrenom']."</option>\n";
				}
				?>
			</select>
		</td>
		<td width="25%">
			<select id="Id_Metier" name="Id_Metier" class="Mobile" onchange="submit()">
				<?php
				$Id_Metier=0;
				if($_POST){
					if($_POST['Id_Surveille']==$_POST['oldSurveille']){
						if(isset($_POST['Id_Metier'])){$Id_Metier=$_POST['Id_Metier'];}
					}
				}
				if($_POST){
					$requeteMetier="
						SELECT DISTINCT
							Id_Metier,
							(SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier
						FROM
							new_competences_personne_metier
						WHERE
							Id_Personne=".$Id_Surveille."
						AND Id_Personne<>0
						ORDER BY Futur ASC";
					$result_Metier= mysqli_query($bdd,$requeteMetier);
					$nbMetier=mysqli_num_rows($result_Metier);
					if($nbMetier>0){
						while ($row_Metier=mysqli_fetch_array($result_Metier))
						{
							$selected="";
							if($_POST['Id_Surveille']==$_POST['oldSurveille']){
								if($Id_Metier==$row_Metier['Id_Metier']){$selected="selected";}
							}
							else{
								if($Id_Metier==0){
									$Id_Metier=$row_Metier['Id_Metier'];
									$selected="selected";
								}
							}
							echo "<option value='".$row_Metier['Id_Metier']."' ".$selected.">".$row_Metier['Metier']."</option>\n";
						}
					}
					else{
						echo "<option value='0'></option>";
					}
				}
				else{
					echo "<option value='0'></option>";
				}
				?>
			</select>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td colspan="8">
			<table width="60%" style="border:3px dotted #07559b;" align="center">
				<?php 
					$req="SELECT DISTINCT soda_theme.Id, soda_theme.Libelle 
					FROM soda_theme 
					LEFT JOIN soda_questionnaire 
					ON soda_theme.Id=soda_questionnaire.Id_Theme
					WHERE soda_theme.Suppr=0
					AND soda_theme.Id=8
					AND soda_questionnaire.Suppr=0 
					AND soda_questionnaire.Actif=0
					AND (
							SELECT COUNT(soda_question.Id) 
							FROM soda_question 
							WHERE soda_question.Suppr=0 
							AND soda_question.Id_Questionnaire=soda_questionnaire.Id
							AND (
								SELECT COUNT(soda_question_exceptionuer.Id) 
								FROM soda_question_exceptionuer 
								WHERE soda_question_exceptionuer.Suppr=0 
								AND soda_question_exceptionuer.Id_Question=soda_question.Id
								AND soda_question_exceptionuer.Id_Plateforme=".$Id_Plateforme."
							)=0
						)>0
					";
					$resultTheme=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultTheme);
					
					$i=0;
					if($nbTheme>0){
						$couleur="#ffffff";
						while($row2=mysqli_fetch_array($resultTheme)){
							$cadenas="";
							$checked="checked";
							$disabled="";

							echo "<tr bgcolor='".$couleur."'><td width='50%' class='theme LibelleMobile' height='80px;'><input class='theme Checkbox' name='themes[]' type='checkbox' onchange='CalculerQuestionnaire();' ".$checked." ".$disabled." value='".$row2['Id']."' />".$row2['Libelle']."".$cadenas."</td>";
							
							$req="SELECT Id, Libelle,
							soda_questionnaire.NbQuestion,
							(
								SELECT COUNT(soda_question.Id) 
								FROM soda_question 
								WHERE soda_question.Suppr=0 
								AND soda_question.Id_Questionnaire=soda_questionnaire.Id
								AND (
									SELECT COUNT(soda_question_exceptionuer.Id) 
									FROM soda_question_exceptionuer 
									WHERE soda_question_exceptionuer.Suppr=0 
									AND soda_question_exceptionuer.Id_Question=soda_question.Id
									AND soda_question_exceptionuer.Id_Plateforme=".$Id_Plateforme."
								)=0
							) AS lesQuestions
							FROM soda_questionnaire 
							WHERE Id_Theme=".$row2['Id']." AND Suppr=0 
							AND soda_questionnaire.Actif=0
							AND (
								SELECT COUNT(soda_question.Id) 
								FROM soda_question 
								WHERE soda_question.Suppr=0 
								AND soda_question.Id_Questionnaire=soda_questionnaire.Id
								AND (
									SELECT COUNT(soda_question_exceptionuer.Id) 
									FROM soda_question_exceptionuer 
									WHERE soda_question_exceptionuer.Suppr=0 
									AND soda_question_exceptionuer.Id_Question=soda_question.Id
									AND soda_question_exceptionuer.Id_Plateforme=".$Id_Plateforme."
								)=0
							)>0 
							";
							$resultQuestionnaire=mysqli_query($bdd,$req);
							$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
							?>
							<td width='50%'>
								<table width="100%">
									<?php 
									$valeur=$nbQuestionnaire;
									if($valeur>3){$valeur=3;}
									for($k=0;$k<$valeur;$k++){?>
									<tr id="Question_<?php echo $row2['Id']."_".$k;?>" <?php if($k>0){echo "style='display:none;'";}?>>
										<td>
											<select id="Id_Questionnaire_<?php echo $row2['Id']; ?>_<?php echo $k; ?>" name="Id_Questionnaire_<?php echo $row2['Id']; ?>_<?php echo $k; ?>"  class="Mobile" style="width:350px;" onchange="CalculerQuestionnaire();">
											<?php
											echo "<option value='0'></option>";
											if($nbQuestionnaire>0){
												$resultQuestionnaire=mysqli_query($bdd,$req);
												while($rowQ=mysqli_fetch_array($resultQuestionnaire)){
													$checked2="";
													if($k==0){
														echo "<script>Liste_Questionnaire[".$i."] = new Array(".$rowQ['Id'].",".$rowQ['NbQuestion'].",".$rowQ['lesQuestions'].");</script>";
														$i++;
													}
													echo "<option value='".$rowQ['Id']."' ".$checked2.">".$rowQ['Libelle']."</option>";
												}
											}
											?>
											</select>
										</td>
										<td>
											<?php 
											if($k<($valeur-1) && $valeur>1){
												echo "<img style='cursor:pointer;' id='BtnPlus_".$row2['Id']."_".$k."' width='20px' src='../../v2/Images/add.png' onclick='AfficherQA(".$row2['Id'].",".$k.")' border='0' alt='Suivant' title='Suivant'>";
											}
											?>
										</td>
									</tr>
									<?php }?>
								</table>
							</td>
							<?php
								if($couleur=="#ffffff"){$couleur="#e6fbff";}
								else{$couleur="#ffffff";}
							echo "</tr>";
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td colspan="8">
			<table width="50%" align="center">
				<tr>
					<td width="50%" class="LibelleMobile" align="center"><?php if($_SESSION['Langue']=="FR"){echo "Nombre de questions";}else{echo "Number of questions";}?></td>
					<td width="50%" class="LibelleMobile" align="center"><?php if($_SESSION['Langue']=="FR"){echo "Durée approximative";}else{echo "Approximate duration";}?></td>
				</tr>
				<tr>
					<td width="50%" align="center" style="font-size:2em;"><div id="nbQuestions"></div>
					<input type="hidden" id="nbQuestions2" name="nbQuestions2" value="">
					</td>
					<td width="50%" align="center" style="font-size:2em;"><div id="dureeApproximative"></div></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="10" align="center">
			<input class="Bouton BoutonMobile" name="btnValider" type="submit" onclick="return VerifChampsSurveillance();"
			<?php
				if($LangueAffichage=="FR"){echo "value='Réaliser la surveillance immédiatement'";}else{echo "value='Perform monitoring immediately'";}
			?>
			/>
		</td>
	</tr>
	<tr><td height="100"></td></tr>
<input type="hidden" id="oldPlateforme" name="oldPlateforme" value="<?php echo $Id_Plateforme; ?>">
<input type="hidden" id="oldSurveille" name="oldSurveille" value="<?php echo $Id_Surveille; ?>">
<input type="hidden" id="oldMetier" name="oldMetier" value="<?php echo $Id_Metier; ?>">
</table>
<?php
echo "<script>CalculerQuestionnaire();</script>";
mysqli_close($bdd);			// Fermeture de la connexion
?>
</form>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script><script  src="../script.js"></script>
</body>
</html>