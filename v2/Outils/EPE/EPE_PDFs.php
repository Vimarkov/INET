<?php
require '../ConnexioniSansBody.php';

require_once '../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;
$file_to_save = 'documents/';

function ExporterEPEPDFs($tabEPE){
	global $bdd;
	global $file_to_save;

	//Générer plusieurs PDF à la fois 
	foreach ($tabEPE as $epe){
		$Id_Personne=$epe[0];
		$cadre=$epe[1];
		$annee=$epe[2];

		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
			
		$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
				(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
				(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
				(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
				(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
				ConnaissanceMetier,ComConnaissanceMetier,UtilisationDoc,ComUtilisationDoc,Productivite,ComProductivite,Organisation,ComOrganisation,CapaciteManager,ComCapaciteManager,
				RespectObjectif,ComRespectObjectif,AnglaisTech,ComAnglaisTech,CapaciteTuteur,ComCapaciteTuteur,Reporting,ComReporting,PlanAction,ComPlanAction,RespectBudget,ComRespectBudget,
				RepresentationEntreprise,ComRepresentationEntreprise,SouciSatisfaction,ComSouciSatisfaction,Ecoute,ComEcoute,TraitementInsatisfaction,ComTraitementInsatisfaction,ExplicationSolution,ComExplicationSolution,
				ComprehensionInsatisfaction,ComComprehensionInsatisfaction,ConnaissanceManagement,ComConnaissanceManagement,ConnaissanceMetierEquipe,ComConnaissanceMetierEquipe,CapaciteFixerObjectif,ComCapaciteFixerObjectif,
				Delegation,ComDelegation,AnimationEquipe,ComAnimationEquipe,RespectQSE,ComRespectQSE,ContributionNC,ComContributionNC,RespectRegles,ComRespectRegles,PortTenues,ComPortTenues,
				PortEPI,ComPortEPI,RespectOutils,ComRespectOutils,Assiduite,ComAssiduite,EspritEntreprise,ComEspritEntreprise,TravailEquipe,ComTravailEquipe,Dispo,ComDispo,Autonomie,ComAutonomie,Initiative,ComInitiative,
				Communication,ComCommunication,OrganisationCharge,ComSOrganisationCharge,ComEOrganisationCharge,AmplitudeJournee,ComSAmplitudeJournee,ComEAmplitudeJournee,OrganisationTravail,
				ComSOrganisationTravail,ComEOrganisationTravail,ArticulationActiviteProPerso,ComSArticulationActiviteProPerso,ComEArticulationActiviteProPerso,Remuneration,ComSRemuneration,
				ComERemuneration,Stress,ComSStress,ComEStress,EntretienRH,EntretienMedecienTravail,EntretienLumanisy,EntretienSoutienPsycho,EntretienHSE,EntretienAutre,FormationOrganisationTravail,FormationStress,
				FormationSophrologie,FormationAutre,ComEntretienRH,ComEntretienMedecienTravail,ComEntretienLumanisy,ComEntretienSoutienPsycho,ComEntretienHSE,ComEntretienAutre,ComEEntretienAutre,
				ComFormationOrganisationTravail,ComFormationStress,ComFormationSophrologie,ComFormationAutre,ComEFormationAutre,CommentaireLibreS,CommentaireLibreE,
				PointFort,PointFaible,ObjectifProgression,ComSalarie,ComEvaluateur,DateEvaluateur,DateSalarie,SalarieRefuseSignature
			FROM epe_personne 
			WHERE Suppr=0 
			AND ModeBrouillon=0
			AND Id_Personne=".$Id_Personne."
			AND YEAR(DateButoir)='".$annee."'
			AND Type='EPE'
			ORDER BY Id DESC ";
		$result=mysqli_query($bdd,$req);
		$rowEPERempli=mysqli_fetch_array($result);


		$Plateforme="";
		$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Plateforme=$RowPresta['Libelle'];
		}

		$Manager=stripslashes($rowEPERempli['Manager']);
		$MatriculeAAAManager=$rowEPERempli['MatriculeAAAManager'];
		$MetierManager=stripslashes($rowEPERempli['MetierManager']);

		$Titre="";
		if($cadre==0){
			$Titre= "<img src='../../Images/FlecheBlancheGauche.png' width='15px' border='0' />ENTRETIEN PROFESSIONNEL D'EVALUATION - E.P.E<img width='15px' src='../../Images/FlecheBlancheDroite.png' border='0' /><br>NON CADRES<br>Bilan des activités annuelles";
		}
		else{
			$Titre= "<img src='../../Images/FlecheBlancheGauche.png' width='15px' border='0' />ENTRETIEN PROFESSIONNEL D'EVALUATION - E.P.E<img width='15px' src='../../Images/FlecheBlancheDroite.png' border='0' /><br>CADRES<br>Bilan des activités annuelles";
		}

		$formulaire="
		<html style='background-color:#ffffff;font-family:cursive;'>
			<head>
				<link type='text/css' href='../../CSS/FeuillePDF.css' rel='stylesheet' />
			<style>
				@font-face {
						font-family: 'Courier';           
						font-weight: normal;
						font-style: normal;
						src: url('Courier.afm') format('truetype');
				} 
				@page { margin: 110px 50px; }
				header {
						position: fixed; 
						top: -100 px; 
						left: 0px; 
						right: 0px;
						height: 400px; 
						padding-bottom: 200px;
					}
				footer {
						position: fixed; 
						bottom: -60px; 
						left: 0px; 
						right: 0px;
						height: 50px; 

						// Extra personal styles
						text-align: center;
						line-height: 35px;
					}
				footer .pagenum:before {
					  content: counter(page);
				}
				footer .pagenum2:after {
					  content: counter(page);
				}
				</style>
			</head>
			<header>
				<table width='100%' cellpadding='0' cellspacing='0' style='background-color:#ffffff;' height='100%'>
					<tr>
						<td width='100px' rowspan='2'  style='border:1px solid black' align='center'><img width='100px' src='../../Images/Logos/AAA_Group.gif' border='0' /></td>
						<td width='400px' rowspan='2' height='40px' style='font:bold 14px;border:1px black solid;background-color:#002060;color:#ffffff;' align='center'>
							".$Titre."
						</td>
						<td width='100px'  style='border:1px solid black;font-size:12px;' align='center'>&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td width='100px' height='20px' style='font:bold 14px;border:1px black solid;font-size:12px;' align='center'>&nbsp;&nbsp;&nbsp;</td>
					</tr>
				</table>
			</header>
		";

		$formulaire.="
		<table width='100%'>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table style='border-spacing:0px;border:1px #000000 solid;padding:0px;' width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr>
									<td width='20%' style='font-weight:bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Matricule</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['MatriculeAAA']."</td>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Date de l'entretien</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien'])."</td>
								</tr>
								<tr>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Nom</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Nom']."</td>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Unité d'exploitation</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$Plateforme."</td>
								</tr>
								<tr>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Prénom</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Prenom']."</td>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Evaluateur</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$Manager."</td>
								</tr>
								<tr>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Fonction/métier</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$rowEPERempli['Metier']."</td>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Matricule</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$MatriculeAAAManager."</td>
								</tr>
								<tr>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Date d'ancienneté</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete'])."</td>
									<td width='20%' style='font-weight : bold;border:1px #000000 solid;font-size:9px;' align='center' bgcolor='#d8d8d4'>Fonction /métier</td>
									<td width='30%' style='border:1px #000000 solid;font-size:9px;' align='center'>".$MetierManager."</td>
								</tr>
							</table>
						</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
					1. EPE - Bilan annuel Global - Performance individuelle annuelle
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr bgcolor='#1a0078'>
									<td colspan='6' style='border:1px #000000 solid;font-size:8px;color:#ffffff;'>RAPPEL DES OBJECTIFS DE L'ANNEE ECOULEE</td>
									<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Commentaires</td>
								</tr>
								<tr bgcolor='d8d8d4'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' >Evaluation*</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;'  align='center'>NA</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;'  align='center'>1</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;'  align='center'>2</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;'  align='center'>3</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;'  align='center'>4</td>
									<td width='60%' style='border-left:1px #000000 solid;border-top:1px #000000 solid;border-right:1px #000000 solid;font-size:8px;' bgcolor='ffffff'>
									*Evaluation NA  = non applicable, ex : absence longue durée sur la période de réalisation , ou arrivé  en  fin de période d'évaluation;
									1 = Résultats non atteints;
									2 = Résultats partiellement atteints;
									3 = Résultats atteints;
									4 = Résultats dépassés
									</td>
								</tr>
								";

								$req="SELECT Id, Evaluation, Note, Commentaire
								FROM epe_personne_objectifanneeprecedente 
								WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";

								$resultAnneePrec=mysqli_query($bdd,$req);
								$NbAnneePrec=mysqli_num_rows($resultAnneePrec);

								if($NbAnneePrec>0){
									while($rowAnneePrec=mysqli_fetch_array($resultAnneePrec)){

									$formulaire.="<tr>
													<td width='30%' style='border:1px #000000 solid;font-size:9px;'>".stripslashes($rowAnneePrec['Evaluation'])."</td>
													<td width='2%' style='border:1px #000000 solid;font-size:9px;'  align='center'>";
													if($rowAnneePrec['Note']==-1){$formulaire.= "X";}
													$formulaire.="</td>
													<td width='2%' style='border:1px #000000 solid;font-size:9px;'  align='center'>";
													if($rowAnneePrec['Note']==1){$formulaire.= "X";}
													$formulaire.="</td>
													<td width='2%' style='border:1px #000000 solid;font-size:9px;'  align='center'>";
													if($rowAnneePrec['Note']==2){$formulaire.= "X";}
													$formulaire.="</td>
													<td width='2%' style='border:1px #000000 solid;font-size:9px;'  align='center'>";
													if($rowAnneePrec['Note']==3){$formulaire.= "X";}
													$formulaire.="</td>
													<td width='2%' style='border:1px #000000 solid;font-size:9px;'  align='center'>";
													if($rowAnneePrec['Note']==4){$formulaire.= "X";}
													$formulaire.="</td>
													<td width='60%' style='border:1px #000000 solid;font-size:9px;'>".stripslashes($rowAnneePrec['Commentaire'])."</td>
												</tr>";
									}
								}
							$formulaire.="</table>
						</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
					2. EPE - Grille d'évaluation des compétences
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr bgcolor='#1a0078'>
									<td colspan='6' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' >TECHNIQUE</td>
									<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;' >Commentaires</td>
								</tr>
								<tr bgcolor='d8d8d4'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;'>Focus métier</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>NA</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>4</td>
									<td width='60%' style='border:1px #000000 solid;font-size:8px;' bgcolor='ffffff'>
									* NA = Le salarié n'est pas concerné par le critère d'évaluation (ex : pas de management, pas d'anglais requis…);
									1 = Compétence insuffisante par rapport aux attentes;
									2 = Compétence partielle par rapport aux attentes;
									3 = Compétences maîtrisées;
									4 = Au-delà des compétences attendues
									</td>
								</tr>";

								$tab=array('Connaissance et maitrise technique poste et métier (règles de l’art, lecture de plans…)','Utilisation des documents de travail (Qualité, …)','Productivité – rapidité d’exécution','Organisation dans le travail','Capacité à manager un projet','Respect des objectifs (délais fixés, …)','Anglais technique ','Capacité à tutorer (aptitude pédagogique à transmettre son savoir)','Reporting','Mise en place et suivi des plans d’actions','Respect des lignes budgétaires, des coûts / délais');
								$tab2=array('ConnaissanceMetier','UtilisationDoc','Productivite','Organisation','CapaciteManager','RespectObjectif','AnglaisTech','CapaciteTuteur','Reporting','PlanAction','RespectBudget'); 

								for($i=0;$i<sizeof($tab);$i++){

								$formulaire.="<tr>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==-1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==2){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==3){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==4){$formulaire.="X";}
										$formulaire.="</td>
										<td width='60%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['Com'.$tab2[$i]])."</td>
									</tr>";
								}

								$formulaire.="<tr bgcolor='d8d8d4'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;'>Focus Relation Client (interne comme externe)</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>NA</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>4</td>
									<td width='60%' style='border:1px #000000 solid;font-size:11px;'></td>
								</tr>";

								$tab=array('Représentation de l’entreprise auprès du client','Souci de satisfaction client / sens du service','Ecoute et empathie','Qualité de traitement des insatisfactions','Explication des solutions, valorisation','Compréhension des raisons de l’insatisfaction');
								$tab2=array('RepresentationEntreprise','SouciSatisfaction','Ecoute','TraitementInsatisfaction','ExplicationSolution','ComprehensionInsatisfaction'); 
								for($i=0;$i<sizeof($tab);$i++){

								$formulaire.="<tr>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==-1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==2){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==3){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==4){$formulaire.="X";}
										$formulaire.="</td>
										<td width='60%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['Com'.$tab2[$i]])."</td>
									</tr>";
								}

								$formulaire.="<tr bgcolor='d8d8d4'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;'>Focus Management</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>NA</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>4</td>
									<td width='60%' style='border:1px #000000 solid;font-size:11px;'></td>
								</tr>";
								
								$tab=array('Connaissance des techniques de management','Connaissance des métiers de ses équipes','Capacité à fixer des objectifs','Aptitude à la délégation','Animation et gestion d’équipe');
								$tab2=array('ConnaissanceManagement','ConnaissanceMetierEquipe','CapaciteFixerObjectif','Delegation','AnimationEquipe'); 

								for($i=0;$i<sizeof($tab);$i++){

								$formulaire.="<tr>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==-1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==2){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==3){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==4){$formulaire.="X";}
										$formulaire.="</td>
										<td width='60%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['Com'.$tab2[$i]])."</td>
									</tr>";
								}
								
								$formulaire.="<tr bgcolor='d8d8d4'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;'>Focus Qualité - Sécurité - Environnement</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>NA</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>4</td>
									<td width='60%' style='border:1px #000000 solid;font-size:11px;'></td>
								</tr>";
		 
								$tab=array('Respect des normes QSE en vigueur','Contribution aux NC et actions correctives associées','Respect des consignes, règles et procédures','Port des tenues identifiées AAA','Port des EPI','Respect des outils et / ou matériels mis à disposition');
								$tab2=array('RespectQSE','ContributionNC','RespectRegles','PortTenues','PortEPI','RespectOutils');
				
								for($i=0;$i<sizeof($tab);$i++){

								$formulaire.="<tr>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==-1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==2){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==3){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==4){$formulaire.="X";}
										$formulaire.="</td>
										<td width='60%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['Com'.$tab2[$i]])."</td>
									</tr>";
								}

								$formulaire.="<tr bgcolor='#1a0078'>
									<td colspan='6' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' '>COMPORTEMENT</td>
									<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;'  align='center'>Commentaires</td>
								</tr>
								<tr bgcolor='d8d8d4'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;'>Savoir être Général</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>NA</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
									<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>4</td>
									<td width='60%' style='border:1px #000000 solid;font-size:8px;' bgcolor='ffffff'>
									* NA = Le salarié n'est pas concerné par le critère d'évaluation (ex : pas de management, pas d'anglais requis…);
									1 = Compétence insuffisante par rapport aux attentes;
									2 = Compétence partielle par rapport aux attentes;
									3 = Compétences maîtrisées;
									4 = Au-delà des compétences attendues
									</td>
								</tr>";

								$tab=array('Assiduité','Esprit d’entreprise / engagement','Capacité à travailler en équipe','Disponibilité / implication','Autonomie','Initiative','Communication / relationnel');
								$tab2=array('Assiduite','EspritEntreprise','TravailEquipe','Dispo','Autonomie','Initiative','Communication');

								for($i=0;$i<sizeof($tab);$i++){

								$formulaire.="<tr>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==-1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==1){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==2){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==3){$formulaire.="X";}
										$formulaire.="</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==4){$formulaire.="X";}
										$formulaire.="</td>
										<td width='60%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['Com'.$tab2[$i]])."</td>
									</tr>";
								}
								
							$formulaire.="</table>
						</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
					3. EPE - Définition des objectifs annuels à venir
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr bgcolor='#1a0078'>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Objectifs</td>
									<td width='20%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Indicateurs</td>
									<td width='20%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Moyens associés</td>
									<td width='27%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Commentaires</td>
								</tr>";

								$req="SELECT Id, Objectif, Indicateur, MoyensAssocies, Commentaire
								FROM epe_personne_objectifannee 
								WHERE Suppr=0 AND  Id_epepersonne=".$rowEPERempli['Id']." ";

								$resultAnnee=mysqli_query($bdd,$req);
								$NbAnnee=mysqli_num_rows($resultAnnee);
								
								if($NbAnnee>0){
									while($rowAnnee=mysqli_fetch_array($resultAnnee)){

									$formulaire.="<tr>
											<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowAnnee['Objectif'])."</td>
											<td width='20%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowAnnee['Indicateur'])."</td>
											<td width='20%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowAnnee['MoyensAssocies'])."</td>
											<td width='27%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowAnnee['Commentaire'])."</td>
									</tr>";
									}
								}
							$formulaire.="</table>
						</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
					4. EPE - Formations
				</td>
			</tr>
			</table>
			<table width='100%' align='center' cellpadding='0' cellspacing='0'>
					<tr bgcolor='#1a0078'>
						<td width='36%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Bilan des formations annuelles réalisées</td>
						<td width='16%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center' colspan='2'>Période</td>
						<td width='8%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center' colspan='4'>CO-EVALUATION à froid Manager/Salarié</td>
						<td width='40%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center' >Commentaires</td>
					</tr>
					<tr bgcolor='#d9d9d9'>
						<td width='36%' style='border:1px #000000 solid;font-size:8px;' align='center'>Intitulé de la formation</td>
						<td width='8%' style='border:1px #000000 solid;font-size:8px;' align='center'>date début</td>
						<td width='8%' style='border:1px #000000 solid;font-size:8px;' align='center'>date fin</td>
						<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
						<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
						<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
						<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>4</td>
						<td width='40%' style='border:1px #000000 solid;font-size:8px;' align='center'>1= Insuffisant ; 2 = Moyen ; 3 = Efficace ; 4 = Très efficace </td>
					</tr>";

					//Liste des formations et qualifications de l'année précédente 
					$req="SELECT Formation, DateDebut, DateFin, EvaluationAFroid, Commentaire 
							FROM epe_personne_bilanformation 
							WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
					$result2=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result2);

					if($nbenreg>0){
						while($row2=mysqli_fetch_array($result2)){

						$formulaire.="<tr>
							<td width='36%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($row2['Formation'])."</td>
							<td width='8%' style='border:1px #000000 solid;font-size:8px;' align='center'>".AfficheDateJJ_MM_AAAA($row2['DateDebut'])."</td>
							<td width='8%' style='border:1px #000000 solid;font-size:8px;' align='center'>".AfficheDateJJ_MM_AAAA($row2['DateFin'])."</td>
							<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
							if($row2['EvaluationAFroid']==1){$formulaire.="X";}
							$formulaire.="</td>
							<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
							if($row2['EvaluationAFroid']==2){$formulaire.="X";}
							$formulaire.="</td>
							<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
							if($row2['EvaluationAFroid']==3){$formulaire.="X";}
							$formulaire.="</td>
							<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
							if($row2['EvaluationAFroid']==4){$formulaire.="X";}
							$formulaire.="</td>
							<td width='40%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($row2['Commentaire'])."</td>
						</tr>";
						}
					}
					
				$formulaire.="
			</table>
			<br>
			<table width='100%' align='center' cellpadding='0' cellspacing='0'>
				<tr bgcolor='#1a0078'>
					<td width='30%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Besoins en formation identifié par le manager</td>
					<td width='20%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Période prévisionnelle</td>
					<td width='47%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Commentaires</td>
				</tr>";
				
				$req="SELECT Id, Formation, DateDebut, DateFin, Commentaire 
						FROM epe_personne_besoinformation 
						WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
				$ListeFor=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($ListeFor);

				if($nbenreg>0){
					while($rowFor=mysqli_fetch_array($ListeFor)){

					$formulaire.="<tr>
						<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowFor['Formation'])."</td>
						<td width='20%' style='border:1px #000000 solid;font-size:8px;' align='center'>".AfficheDateJJ_MM_AAAA($rowFor['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($rowFor['DateFin'])."</td>
						<td width='47%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowFor['Commentaire'])."</td>
					</tr>";
					}
				}
			$formulaire.="
			</table>
			<br>
			<table width='100%' align='center' cellpadding='0' cellspacing='0'>
					<tr bgcolor='#1a0078'>
						<td width='40%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Souhait de formation exprimé par le salarié</td>
						<td width='20%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Avis évaluateur</td>
						<td width='10%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Ordre de priorité : 1 : Prioritaire pour l'activité, 2 : nécessaire, 3 : non urgent</td>
						<td width='27%' style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>Commentaires</td>
					</tr>";

					$req="SELECT Id, Formation, Favorable,Priorite, Commentaire 
							FROM epe_personne_souhaitformation 
							WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
					$ListeFor=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($ListeFor);
					
					if($nbenreg>0){
						while($rowFor=mysqli_fetch_array($ListeFor)){

						$formulaire.="<tr>

							<td width='40%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowFor['Formation'])."</td>
							<td width='20%' style='border:1px #000000 solid;font-size:8px;'><input type='checkbox' value='1'";
							if($rowFor['Favorable']==1){$formulaire.= "checked";}
							$formulaire.=">Favorable<input type='checkbox' value='0' ";
							if($rowFor['Favorable']==0){$formulaire.= "checked";} 
							$formulaire.=">Défavorable</td>
							<td width='10%' style='border:1px #000000 solid;font-size:8px;' align='center'>".$rowFor['Priorite']."</td>
							<td width='47%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowFor['Commentaire'])."</td>
						</tr>";
						}
					}
				$formulaire.="
			</table>
			<br>
			<table width='100%' align='center' cellpadding='0' cellspacing='0'>
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>";
					if($cadre==0){
						$formulaire.="5. EPE - Suivi de la charge de travail";
					}
					else{
						$formulaire.="5. EPE - Suivi du forfait en jour";
					}
				$formulaire.="</td>
			</tr>
			<tr>
				<td>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>
						<tr bgcolor='#1a0078'>
							<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center'>item</td>
							<td align='center' style='border:1px #000000 solid;font-size:8px;color:#ffffff;'>Commentaires du salarié</td>
							<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;'  align='center'>Commentaires de l'évaluateur</td>
						</tr>";

						if($cadre==0){
							$tab=array('Organisation et charge de travail','Articulation entre activité professionnelle et vie personnelle et familiale');
							$tab2=array('OrganisationCharge','ArticulationActiviteProPerso');
						}
						else{
							$tab=array('Organisation et charge de travail','Amplitude des journées d’activité','Organisation du travail dans l’entreprise ','Articulation entre activité professionnelle et vie personnelle et familiale','Conformité par rapport à la grille de rémunération conventionnelle');
							$tab2=array('OrganisationCharge','AmplitudeJournee','OrganisationTravail','ArticulationActiviteProPerso','Remuneration');
						}

						for($i=0;$i<sizeof($tab);$i++){
							$formulaire.="<tr>
								<td width='32%' bgcolor='d8d8d4' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
								<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['ComS'.$tab2[$i]])."</td>
								<td width='30%' style='border:1px #000000 solid;font-size:8px;'>".stripslashes($rowEPERempli['ComE'.$tab2[$i]])."</td>
							</tr>";
						}
						$formulaire.= "
					</table>
				</td>
			</tr>";
			$formulaire.= "
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>
					6. EPE - Temps d'écoute
				</td>
			</tr>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr bgcolor='#1a0078'>
									<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;' align='center' rowspan='2'>item</td>
									<td colspan='4'  align='center' style='border:1px #000000 solid;font-size:8px;color:#ffffff;'>Evaluation et commentaires du salarié</td>
									<td style='border:1px #000000 solid;font-size:8px;color:#ffffff;'  align='center' rowspan='2'>Commentaires de l'évaluateur</td>
								</tr>
								<tr bgcolor='d8d8d4' height='35px'>
									<td style='border:1px #000000 solid;font-size:8px;' align='center'>1</td>
									<td style='border:1px #000000 solid;font-size:8px;' align='center'>2</td>
									<td style='border:1px #000000 solid;font-size:8px;' align='center'>3</td>
									<td style='border:1px #000000 solid;font-size:8px;' align='center'>1= Je ne me sens pas bien ; 2 = Je me sens bien ; 3 = Je me sens très bien</td>
								</tr>";
								
								$tab=array('Comment évalueriez vous votre niveau stress ?');
								$tab2=array('Stress');

								for($i=0;$i<sizeof($tab);$i++){

									$formulaire.= "<tr>
										<td width='32%' style='border:1px #000000 solid;font-size:8px;'>".$tab[$i]."</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==1){$formulaire.= "X";}
										$formulaire.= "</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==2){$formulaire.= "X";}
										$formulaire.= "</td>
										<td width='2%' style='border:1px #000000 solid;font-size:8px;' align='center'>";
										if($rowEPERempli[$tab2[$i]]==3){$formulaire.= "X";}
										$formulaire.= "</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComS'.$tab2[$i]])."</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComE'.$tab2[$i]])."</td>
									</tr>";
								}
									$formulaire.= "
									<tr>
										<td width='32%' bgcolor='d8d8d4' style='border:1px #000000 solid;font-size:8px;' rowspan='11' >Si votre niveau de stress = 1, de quel dispositif d'accompagnement auriez-vous besoin ?</td>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['EntretienRH']==1){$formulaire.="checked";}
										$formulaire.=">Entretien RH</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEntretienRH'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['EntretienMedecienTravail']==1){$formulaire.= "checked";}
										$formulaire.=">Entretien avec la médecine du travail</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEntretienMedecienTravail'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['EntretienLumanisy']==1){$formulaire.= "checked";}
										$formulaire.=">Entretien avec le service social du travail</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEntretienLumanisy'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['EntretienSoutienPsycho']==1){$formulaire.= "checked";}
										$formulaire.= ">Soutien psychologique</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEntretienSoutienPsycho'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['EntretienHSE']==1){$formulaire.= "checked";}
										$formulaire.= ">Entretien avec service HSE</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEntretienHSE'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['EntretienAutre']==1){$formulaire.= "checked";}
										$formulaire.= ">Autres : ".stripslashes($rowEPERempli['ComEntretienAutre'])."</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEEntretienAutre'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'>Formation</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'></td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['FormationOrganisationTravail']==1){$formulaire.= "checked";}
										$formulaire.= ">Organisation du travail, gestion du temps et des priorités</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComFormationOrganisationTravail'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['FormationStress']==1){$formulaire.= "checked";}
										$formulaire.= ">Gestion du stress</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComFormationStress'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4'><input type='checkbox' ";
										if($rowEPERempli['FormationAutre']==1){$formulaire.= "checked";}
										$formulaire.= ">Autres : ".stripslashes($rowEPERempli['ComFormationAutre'])."</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ComEFormationAutre'])."</td>
									</tr>
									<tr>
										<td width='5%' style='border:1px #000000 solid;font-size:8px;' colspan='4' style='border:1px #000000 solid;font-size:8px;' align='center'>Nous vous rappelons également que les membres du CSE et du CSSCT se tiennent à votre disposition si nécessaire.</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'></td>
									</tr>
									<tr>
										<td width='32%' bgcolor='d8d8d4' style='border:1px #000000 solid;font-size:8px;'>Commentaires libres</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center' colspan='4'>".stripslashes($rowEPERempli['CommentaireLibreS'])."</td>
										<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['CommentaireLibreE'])."</td>
									</tr>";
							$formulaire.= "</table>
						</td></tr>
					</table>
				</td>
			</tr>";
			$formulaire.= "<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>";
					$formulaire.= "7. EPE - Synthèse";
				$formulaire.= "</td>
			</tr>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Synthèse des points forts</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['PointFort'])."</td>
								</tr>
								<tr>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Synthèse des axes d'amélioration</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['PointFaible'])."</td>
								</tr>
								<tr>
									<td width='20%' bgcolor='d8d8d4' style='border:1px #000000 solid;font-size:8px;'>Objectifs de progression / plan d'action correctif</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".stripslashes($rowEPERempli['ObjectifProgression'])."</td>
								</tr>
							</table>
						</td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor='#00a431' style='color:#ffffff;font-size:9px;' align='center'>";
				$formulaire.= "8. EPE - Conclusion";
				
				
				if($rowEPERempli['SalarieRefuseSignature']==1){
					$signature=" refuse de signer son entretien";
				}
				else{
					$signature="<br>'signature électronique'";
				}
				
				$VisaCollab="";
				if($rowEPERempli['DateSalarie']>'0001-01-01'){
					$VisaCollab=$rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." ".$signature;
				}
				
				$VisaManager="";
				if($rowEPERempli['DateEvaluateur']>'0001-01-01'){
					$VisaManager=$Manager." <br>'signature électronique'";
				}
				
				$formulaire.= "</td>
			</tr>
			<tr>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0'>
						<tr><td>
							<table width='100%' align='center' cellpadding='0' cellspacing='0'>
								<tr height='20px'>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>COMMENTAIRES DU COLLABORATEUR</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' colspan='3' align='center'>".stripslashes($rowEPERempli['ComSalarie'])."</td>
								</tr>
								<tr>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>COMMENTAIRES DE L'EVALUATEUR</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' colspan='3' align='center'>".stripslashes($rowEPERempli['ComEvaluateur'])."</td>
								</tr>
								<tr>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Fait le :</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien'])."</td>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Transmis à la DRH le :</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".AfficheDateJJ_MM_AAAA($rowEPERempli['DateEvaluateur'])."</td>
								</tr>
								<tr>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Visa du collaborateur</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".$VisaCollab."</td>
									<td bgcolor='d8d8d4' width='20%' style='border:1px #000000 solid;font-size:8px;'>Visa de l'évaluateur</td>
									<td width='30%' style='border:1px #000000 solid;font-size:8px;' align='center'>".$VisaManager." </td>
								</tr>
							</table>
						</td></tr>
					</table>
				</td>
			</tr>
		</table>";
		?>

		<?php 
		$dompdf->loadHtml(utf8_encode($formulaire));

		// Render the HTML as PDF
		$dompdf->render();

		// add the header
		$canvas = $dompdf->get_canvas();
		$font = 0;  
		// the same call as in my previous example
		$canvas->page_text(550, 770, "{PAGE_NUM} / {PAGE_COUNT}", 0, 6, array(0,0,0));
		$canvas->page_text(200, 765, "DOCUMENT DIRECTION QUALITE AAA GROUP", $font, 6, array(0,0,0));
		$canvas->page_text(190, 775, "Reproduction interdite sans autorisation Ã©crite de AAA Group", $font, 6, array(0,0,0));
		$canvas->page_text(10, 765, "D-0705/012 - Edition 3", $font, 6, array(0,0,0));
		$canvas->page_text(10, 775, "22/03/2021", $font, 6, array(0,0,0));

		// Output the generated PDF to Browser
		$dompdf->render();
		
		$requete="SELECT new_rh_etatcivil.Id, MatriculeAAA,MatriculeDaher,
					CONCAT(Nom,'_',Prenom) AS Personne
					FROM new_rh_etatcivil
					WHERE Id=".$epe[0];
		$result=mysqli_query($bdd,$requete);
		$rowPersonne=mysqli_fetch_array($result);
		
		$personne = strtr(
				$rowPersonne['Personne'], 
				'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
				'aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
			);
		$annee=$epe[2];
		$matricule=$rowPersonne['MatriculeDaher'];
		
		 //Save PDF in server.
		 file_put_contents($file_to_save.$matricule."_".$personne."_EPE_".$annee.".pdf", $dompdf->output()); 
		 
		 echo "<script type='text/javascript'>";
		 echo "window.open('".$file_to_save.$matricule."_".$personne."_EPE_".$annee.".pdf');" ;
		 echo "</script>";
	}
}

?>