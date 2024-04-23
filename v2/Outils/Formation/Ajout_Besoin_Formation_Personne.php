<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un besoin en formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps(){
			for(y=0;y<document.getElementById('Id_Formations').length;y++){document.getElementById('Id_Formations').options[y].selected = true;}
		}
			
		function FermerEtRecharger()
		{
			if(window.opener.document.getElementById('formulaire')){
				window.opener.document.getElementById('formulaire').submit();
			}
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST){
	if(isset($_POST['generer'])){
		foreach($_POST['Id_Personnes'] as $Id_Personne)
		{
			$IdPrestation=0;
			$IdPole=0;
			if($_POST['Id_Prestation']==0){
				//Récupérer la 1ere prestation de la personne 
				$reqPresta="
					SELECT Id_Prestation,Id_Pole
					FROM
						new_competences_personne_prestation
					WHERE
						Id_Personne=".$Id_Personne."
						AND new_competences_personne_prestation.Date_Fin>='".$DateJour."' 
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_POST['plateforme']." ";
				$resultPresta=mysqli_query($bdd,$reqPresta);
				$NbPresta=mysqli_num_rows($resultPresta);
				
				if($NbPresta>0){
					$rowPresta=mysqli_fetch_array($resultPresta);
					$IdPrestation=$rowPresta['Id_Prestation'];
					$IdPole=$rowPresta['Id_Pole'];
				}
			}
			else{
				$tabPresta=explode("_",$_POST['Id_Prestation']);
				$IdPrestation=$tabPresta[0];
				$IdPole=$tabPresta[1];
			}
			
			if($IdPrestation>0){
				$Message="";
				$Personne="";
				
				$Valide=1;
				$Id_Valideur=0;
				if(DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteResponsableRH,$IdPosteResponsableHSE)))){$Valide=1;$Id_Valideur=$IdPersonneConnectee;}
				
				//Boucle pour faire les INSERT dans la table des besoins et dans les qualifications
				foreach($_POST['Id_Formations'] as $value)
				{
					//Qualification liées à la formation
					$ReqQualifFormation="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$value." AND Suppr=0 AND Masquer=0 ";
					$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
					$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);

					//Vérifier si la personne n'a pas déjà un B 
					if(Get_NbBesoinExistant($Id_Personne, $value)==0){
						$Motif="Nouveau";

						//Vérification si la personne a déjà une inscription à la même formation
						$ReqFormationInscrit="	
							SELECT
								form_session_personne.Id_Personne as ID_PERSONNE,
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPRENOM,
								form_session.Id_Formation AS ID_FORMATION
							FROM
								form_session_personne,
								form_session,
								new_rh_etatcivil
							WHERE
								form_session.Suppr=0
								AND form_session.Annule=0
								AND form_session.Id=form_session_personne.Id_Session
								AND form_session_personne.Suppr=0
								AND form_session_personne.Presence=0
								AND form_session_personne.Validation_Inscription=1
								AND form_session_personne.Id_Personne=new_rh_etatcivil.Id
								AND form_session_personne.Id_Personne=".$Id_Personne."
								AND form_session.Id_Formation=".$value." ";
						$ResultFormationInscrit=mysqli_query($bdd,$ReqFormationInscrit);
						$NbFormationInscrit=mysqli_num_rows($ResultFormationInscrit);
						
						//Vérification si la personne a déjà des qualifications en cours de validité pour cette qualification
						//Qualifications valides pour les personnes prévues en formations
						$ReqQualifsValides="
							SELECT
								DISTINCT new_competences_relation.Id_Qualification_Parrainage AS ID_QUALIFICATION,
								new_competences_qualification.Libelle AS LIBELLE,
								new_competences_relation.Date_Debut,
								new_competences_relation.Date_Fin,
								new_competences_relation.Evaluation,
								new_competences_relation.Sans_Fin,
								new_competences_relation.Id_Personne AS ID_PERSONNE,
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPRENOM,
								new_competences_qualification.Duree_Validite
							FROM
								new_competences_relation,
								new_competences_qualification,
								new_rh_etatcivil
							WHERE
								new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
								AND new_competences_relation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_relation.Type='Qualification'
								AND new_competences_relation.Evaluation != 'B%'
								AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin >= '".$DateJour."')
								AND new_competences_relation.Id_Personne=".$Id_Personne."
								AND new_competences_relation.Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$value." AND Suppr=0 AND Masquer=0)
								AND new_competences_relation.Suppr=0 ";
						$ResultQualifsValides=mysqli_query($bdd,$ReqQualifsValides);
						$NbQualifsValides=mysqli_num_rows($ResultQualifsValides);
						
						if($NbFormationInscrit==0){

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
										Commentaire,
										Valide,
										Id_Valideur,
										Id_Personne_MAJ,
										Date_MAJ
									)
								VALUES
									(".
										$IdPersonneConnectee.",".
										DroitsFormationPlateforme($TableauIdPostesAF_RF).",".
										$IdPrestation.",".
										$IdPole.",".
										$value.",".
										$Id_Personne.",'".$DateJour."',
										'".$Motif."',
										'".addslashes($_POST['Commentaire'])."',".
										$Valide.",
										".$Id_Valideur.",".
										$IdPersonneConnectee.",".
										"'".$DateJour."'
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
									$ReqInsertBesoinGPEC.=$Id_Personne;
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
								FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$Id_Personne;
							$ResultMetier=mysqli_query($bdd,$req);
							$NbMetier=mysqli_num_rows($ResultMetier);
							
							//Vérifier si ce besoin existe dans la table form_prestation_metier_formation 
							if($NbMetier>0){
								$reqExiste="
									SELECT Id 
									FROM form_prestation_metier_formation
									WHERE Id_Prestation=".$Id_Prestation." 
									AND Id_Pole=".$IdPole." 
									AND Id_Formation=".$value." 
									AND Suppr=0 
									AND Id_Metier IN (SELECT Id_Metier FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$Id_Personne." ) ";
								
								$req="
									SELECT Id_Metier, 
									(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
									FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$Id_Personne;
								$ResultMetier=mysqli_query($bdd,$req);
							}
							else{
								$reqExiste="
									SELECT Id 
									FROM form_prestation_metier_formation
									WHERE Id_Prestation=".$IdPrestation." 
									AND Id_Pole=".$IdPole." 
									AND Id_Formation=".$value." 
									AND Suppr=0 
									AND Id_Metier IN (SELECT Id_Metier FROM new_competences_personne_metier WHERE Futur=0 AND Id_Personne=".$Id_Personne." ) ";
							
								$req="
									SELECT Id_Metier, 
									(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
									FROM new_competences_personne_metier WHERE Futur=0 AND Id_Personne=".$Id_Personne;
								$ResultMetier=mysqli_query($bdd,$req);
							}
							$ResultExiste=mysqli_query($bdd,$reqExiste);
							$NbExiste=mysqli_num_rows($ResultExiste);
							if($NbExiste==0){
								$reqPers="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
								$ResultPers=mysqli_query($bdd,$reqPers);
								$NbPersonne=mysqli_num_rows($ResultPers);
								if($NbPersonne>0){
									$rowPersonne=mysqli_fetch_array($ResultPers);
									$rowMetier=mysqli_fetch_array($ResultMetier);
									$Personne.=$rowPersonne['Nom']." ".$rowPersonne['Prenom']." - ".$rowMetier['Metier']."<br>";
								}
							}
						}
					}
					
				
					if($Personne<>""){
						//Avertir par mail les différents AF des plateformes + les CQP / CQS si la besoin n'est pas prévue dans
						//la liste des formations de la prestation
						$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$reqPresta="SELECT Libelle FROM new_competences_prestation WHERE Id=".$IdPrestation;
						$ResultPresta=mysqli_query($bdd,$reqPresta);
						$NbPresta=mysqli_num_rows($ResultPresta);
						$Presta="";
						if($NbPresta>0){
							$rowPresta=mysqli_fetch_array($ResultPresta);
							$Presta=stripslashes($rowPresta['Libelle']);
						}
						
						$reqPole="SELECT Libelle FROM new_competences_pole WHERE Id=".$IdPole;
						$ResultPole=mysqli_query($bdd,$reqPole);
						$NbPole=mysqli_num_rows($ResultPole);
						$Pole="";
						if($NbPole>0){
							$rowPole=mysqli_fetch_array($ResultPole);
							$Pole=" - ".stripslashes($rowPole['Libelle']);
						}
						
						$reqForm="
							SELECT Libelle 
							FROM form_formation_langue_infos 
							WHERE Suppr=0
							AND Id_Langue=(
								SELECT Id_Langue 
								FROM form_formation_plateforme_parametres
								WHERE Suppr=0 
								AND Id_Plateforme IN (
									SELECT Id_Plateforme
									FROM new_competences_prestation
									WHERE Id=".$IdPrestation."
									)
								LIMIT 1)
							AND Id_Formation=".$value;
						$ResultForm=mysqli_query($bdd,$reqForm);
						$NbForm=mysqli_num_rows($ResultForm);
						$Form="";
						if($NbForm>0){
							$rowForm=mysqli_fetch_array($ResultForm);
							$Form=stripslashes($rowForm['Libelle']);
						}
						
						//Qualification liées à la formation
						$ReqQualifFormation="
							SELECT Id_Qualification, 
							(SELECT Libelle FROM new_competences_qualification 
							WHERE new_competences_qualification.Id=form_formation_qualification.Id_Qualification) AS Qualif
							FROM form_formation_qualification 
							WHERE Id_Formation=".$value." 
							AND Suppr=0
							AND form_formation_qualification.Masquer=0 
							ORDER BY Qualif ";
						$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
						$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
						$Qualif="";
						if($NbQualifFormation>0){
							while($rowQualif=mysqli_fetch_array($ResultQualifFormation)){
								$Qualif.=stripslashes($rowQualif['Qualif'])."<br>";
							}
						}
						
						if($LangueAffichage=="FR"){
							$Objet="Nouveau besoin en formation dans l'extranet ";
							$MessageMail="
								<html>
									<head><title>Nouveau besoin en formation dans l'extranet </title></head>
									<body>
										Bonjour,
										<br><br>
										<i>Cette boîte mail est une boîte mail générique</i>
										<br><br>
										La prestation ".$Presta.$Pole." a demandé une formation ".$Form." pour les qualifications suivantes : <br>
										".$Qualif."
										<br>
										Pour les personnes suivantes : <br>
										".$Personne."
										<br>
										Pensez à vérifier si cette formation doit être ajoutée aux besoins en formation par métier et par prestation 
										<br>
										Bonne journée.<br>
										Formation Extranet Daher industriel services DIS.
									</body>
								</html>";
						}
						else{
							$Objet="New training need in the extranet ";
							$MessageMail="
								<html>
									<head><title>New training need in the extranet</title></head>
									<body>
										Hello,
										<br><br>
										The activity ".$Presta.$Pole." requested training ".$Form." for the following qualifications : <br>
										".$Qualif."
										<br>
										For the following people : <br>
										".$Personne."
										<br>
										Remember to check if this training needs to be added to the training needs by profession and by delivery 
										<br>
										Have a good day.<br>
										Training Extranet Daher industriel services DIS.
									</body>
								</html>";
						}
						$Emails="";
						//Récupération de l'ensemble des responsables de chaque personne
						$reqResponsables="
							SELECT DISTINCT
								Id_Personne, 
								(SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS EmailPro 
							FROM
								new_competences_personne_poste_prestation 
							WHERE
								Id_Poste IN (".implode(",",$TableauIdPostesCHE_COOE).") 
								AND Id_Prestation=".$IdPrestation."
								AND Id_Pole=".$IdPole."  
								AND (SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne)<>'' ";
						$ResultResponsables=mysqli_query($bdd,$reqResponsables);
						$nbResp=mysqli_num_rows($ResultResponsables);
						
						if($nbResp>0)
						{
							while($RowResp=mysqli_fetch_array($ResultResponsables))
							{
								$Emails.=$RowResp['EmailPro'].",";
							}
						}
						$Emails=substr($Emails,0,-1);
						
						if($Emails<>"")
						{
							//if(mail($Emails,$Objet,$MessageMail,$Headers,'-f qualipso@aaa-aero.com')){echo "Un message a été envoyé à ".$Emails."\n";}
							//else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
						}
					}
				}
				//En fonction des besoins demandées et des qualifications existantes, affichage du message
				echo "<script>opener.location.reload();</script>";
				//echo "<script>FermerEtRecharger();</script>";
			}
		}
	}
}
?>
<form id="formulaire" method="POST" action="Ajout_Besoin_Formation_Personne.php" onSubmit="return VerifChamps();">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td><input type="hidden" name="oldPlat" value="<?php if($_POST){echo $_POST['plateforme'];}else{if(isset($_GET['Id_Plateforme'])){echo $_GET['Id_Plateforme'];}} ?>" />
			<input type="hidden" name="oldPresta" value="<?php if($_POST){echo $_POST['Id_Prestation'];}else{echo "0";} ?>" />
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:20%;">
				<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> :
			</td>
		</tr>
		<tr>
			<td style="width:80%;" colspan="3">
				<select name="plateforme" id="plateforme" style="width:200px;" onchange="submit()">
					<?php
					$Plat=0;
					if($_GET){
						if(isset($_GET['Id_Plateforme'])){$Plat=$_GET['Id_Plateforme'];}
					}
					else{$Plat=$_POST['plateforme'];}
					$reqPla="
						SELECT
                            DISTINCT Id_Plateforme,
                            (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
						FROM
                            new_competences_personne_poste_plateforme
						WHERE
                            Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_ARH).") AND Id_Personne=".$IdPersonneConnectee."
						UNION
						SELECT
                            DISTINCT (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation)AS Id_Plateforme,
                            (
                                SELECT
                                (
                                    SELECT
                                        Libelle
                                    FROM
                                        new_competences_plateforme
                                    WHERE
                                        new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme
                                )
                                FROM
                                    new_competences_prestation
                                WHERE
                                    new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation
                            ) AS Libelle
						FROM
                            new_competences_personne_poste_prestation
                        WHERE
                            Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
                            AND Id_Personne=".$IdPersonneConnectee." 
						ORDER BY
                            Libelle";
					$resultPlat=mysqli_query($bdd,$reqPla);
					$nbPlat=mysqli_num_rows($resultPlat);
					if($nbPlat>0){
						while($rowPlat=mysqli_fetch_array($resultPlat)){
							$selected="";
							if($_POST){
								if($Plat==$rowPlat['Id_Plateforme']){$selected="selected";}
							}
							else{
								if(isset($_GET['Id_Plateforme'])){
									if($Plat==$rowPlat['Id_Plateforme']){$selected="selected";}
								}
								if($Plat==0){$Plat=$rowPlat['Id_Plateforme'];}
							}
							echo "<option value='".$rowPlat['Id_Plateforme']."' ".$selected.">".stripslashes($rowPlat['Libelle'])."</option>";
							
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle" colspan=2>
				<?php if($LangueAffichage=="FR"){echo "Prestation - Pôle";}else{echo "Activity - Pole";}?> :<br>
				<?php
				$Presta=0;
				if($_POST){
					if($_POST['plateforme']==$_POST['oldPlat']){$Presta=$_POST['Id_Prestation'];}
				}
				echo "<select name='Id_Prestation' id='Id_Prestation' onChange='submit();' style='width:400px;'>";
				
				echo "<option value='0' ></option>\n";
				$reqPla="
					SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Plateforme=".$Plat." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_ARH).") AND Id_Personne=".$IdPersonneConnectee." ";
				$resultPlat=mysqli_query($bdd,$reqPla);
				$NbPlat=mysqli_num_rows($resultPlat);
				if($NbPlat>0){
					$reqPrestation=Get_SQL_PrestationsResponsablesPourPersonne($Plat,true,array(0));
				}
				else{
				    $reqPrestation=Get_SQL_PrestationsResponsablesPourPersonne($Plat,false,$TableauIdPostesRespPresta_CQ);
				}
				$resultPrestation=mysqli_query($bdd,$reqPrestation);
				while($rowPrestation=mysqli_fetch_array($resultPrestation)){
					$selected="";
					if($_POST){
						if($Presta==$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']){$selected="selected";}
					}
					echo "<option value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$selected.">".stripslashes($rowPrestation['Libelle']).stripslashes($rowPrestation['Pole'])."</option>\n";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle" colspan="2">
				<table width="100%">
					<tr class="TitreColsUsers">
						<td valign="top" class="Libelle" width="50%">
							<?php if($LangueAffichage=="FR"){echo "Cocher les besoins en formations";}else{echo "Check training needs";}?> : <br>
							<?php
							$req="
								SELECT form_formation.Id,
								(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme,
								(SELECT Libelle FROM form_typeformation WHERE Id=form_formation.Id_TypeFormation) AS TypeFormation,
								form_formation.Id_TypeFormation,
								(SELECT Libelle FROM form_formation_langue_infos
								WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
								AND Id_Formation=form_formation.Id AND Suppr=0) AS Libelle,
								(SELECT LibelleRecyclage FROM form_formation_langue_infos
								WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
								AND Id_Formation=form_formation.Id AND Suppr=0) AS LibelleRecyclage,
								form_formation.Recyclage
								FROM form_formation_plateforme_parametres 
								LEFT JOIN form_formation
								ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
								WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Plat."
								AND form_formation_plateforme_parametres.Suppr=0 AND form_formation.Suppr=0
								ORDER BY Libelle,LibelleRecyclage 
								";

							$resultGroupeFormation=mysqli_query($bdd,$req);
							$NbForm=mysqli_num_rows($resultGroupeFormation);
							?>
							<div id="listePresta" style="width:100%;height:200px;overflow:auto;">
							<?php
								if($NbForm>0){
									while($rowFormation=mysqli_fetch_array($resultGroupeFormation)){
										//Liste des qualifications de cette formation 
										$reqQualif="
											SELECT DISTINCT Id_Qualification,Masquer,
											(SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
											FROM form_formation_qualification 
											WHERE Id_Formation=".$rowFormation['Id']." 
											AND Suppr=0";
										$resultQualif=mysqli_query($bdd,$reqQualif);
										$NbQualif=mysqli_num_rows($resultQualif);
										
										//Liste des formations compétences de cette formation 
										$reqFormationCompetence="
											SELECT DISTINCT Id_FormationCompetence,
											(SELECT Libelle FROM new_competences_formation WHERE new_competences_formation.Id=Id_FormationCompetence) AS FormationCompetence 
											FROM form_formation_formationcompetence
											WHERE Id_Formation=".$rowFormation['Id']." 
											AND Suppr=0";
										$resultFormationCompetence=mysqli_query($bdd,$reqFormationCompetence);
										$NbFormationCompetence=mysqli_num_rows($resultFormationCompetence);
										
										echo "<div>";
										$htmlQualif="";
										
										
										$Organisme="";
										if($rowFormation['Organisme']<>""){$Organisme=" (".$rowFormation['Organisme'].")";}
										$Libelle="";
										if($rowFormation['Recyclage']==1){
											$Libelle=stripslashes($rowFormation['LibelleRecyclage']).$Organisme;
										}
										else{
											$Libelle=stripslashes($rowFormation['Libelle']).$Organisme;
										}
										
										if($NbQualif>0){
											mysqli_data_seek($resultQualif,0);
											while($rowQualif=mysqli_fetch_array($resultQualif)){
												$htmlQualif.= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;".$rowQualif['Qualif'];
											}
										}
										if($NbFormationCompetence>0){
											mysqli_data_seek($resultFormationCompetence,0);
											while($rowFormationCompetence=mysqli_fetch_array($resultFormationCompetence)){
												$htmlQualif.= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;".$rowFormationCompetence['FormationCompetence'];
											}
										}
										echo "<input class='check' type='checkbox' name='Id_Formations[]' value='".$rowFormation['Id']."'>".$Libelle."&nbsp;".$htmlQualif;
										echo "</div>";
									}
								}
							?>
							</div>
						</td>
						<td valign="top" class="Libelle" width="50%">
							<?php if($LangueAffichage=="FR"){echo "Personne à former";}else{echo "Person to train";}?> :<br>
							<div id="listePersonne" style="width:100%;height:200px;overflow:auto;">
							<?php
							//Personnes présentes par prestation à cette date
							$reqPersonnes="
								SELECT
									DISTINCT new_competences_personne_prestation.Id_Personne,
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM
									new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
								WHERE
									new_competences_personne_prestation.Date_Fin>='".$DateJour."' 
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$Plat." ";
							if($Presta<>0){
								$tabPresta=explode("_",$Presta);
								$reqPersonnes.="AND new_competences_personne_prestation.Id_Prestation=".$tabPresta[0]." ";
								$reqPersonnes.="AND new_competences_personne_prestation.Id_Pole=".$tabPresta[1]." ";
							}
							$reqPersonnes.="ORDER BY Personne ASC;";
							$resultPersonnes=mysqli_query($bdd,$reqPersonnes);
							$NbPersonne=mysqli_num_rows($resultPersonnes);
							
							if($NbPersonne>0){
								while($rowPersonne=mysqli_fetch_array($resultPersonnes)){
									echo "<div><input class='check' type='checkbox' name='Id_Personnes[]' value='".$rowPersonne['Id_Personne']."'>".stripslashes($rowPersonne['Personne'])."</div>";
								}
							}
							?>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($LangueAffichage=="FR"){echo "Commentaires";}else{echo "Comment";}?> : <br>
				<textarea name="Commentaire" cols="40" style="resize:none;"></textarea>
			<td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Générer les besoins'";}else{echo "value='Generate needs'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>