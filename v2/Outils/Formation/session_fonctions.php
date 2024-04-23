<?php 
/**
 * session_fonctions.php
 * 
 * Ce fichier regroupe toutes les fonctions concernant les sessions de formations
 * 
 * @package Formation\Session
 * Modif
 */

require_once("session_requetes.php");

/**
 * get_session
 * 
 * Recupere les informations necessaires a une session de formation
 * RECUPERATION DES QCM EN FONCTION DES QUALIFICATIONS DE LA FORMATION POUR LA SESSION EN COURS
 * 
 * @param int $Id_Session Identifiant de session de formation
 * @return resource Contient les informations de session
 * 
 * @author Remy Parran <rparran@aaa-aero.com>
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function get_session($Id_Session)
{
	$resultDates= getRessource(getchaineSQL_infosSession($Id_Session));
	$Rowinfos = mysqli_fetch_array($resultDates);
	$dateDebut = $Rowinfos['DateSession'];
	$heureDebut = $Rowinfos['Heure_Debut'];
	$resultDates->data_seek(mysqli_num_rows($resultDates) - 1);
	$Rowinfos = mysqli_fetch_array($resultDates);
	$dateFin = $Rowinfos['DateSession'];
	$heureFin = $Rowinfos['Heure_Fin'];
	
	return getRessource(getchaineSQL_session($Id_Session, $dateDebut, $dateFin, $heureDebut, $heureFin));
}

/**
 * desinscrire_candidat
 * 
 * Cette fonction permet de desinscrire un candidat affecte a une session de formation
  * 
 * @param int $Id_Besoin Identifiant du besoin
 * @param int $Id_Session_Personne Identifiant de la session de la personne
 * @return string La chaine SQL
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function desinscrire_candidat($Id_Besoin, $Id_Session_Personne)
{
	global $bdd;
	
	//Suppression de la ligne dans la table form_session_personne
	getRessource(getchaineSQL_desinscrireCandidat_MAJSessionPersonne($Id_Session_Personne));
	
	//Suppression des qualifications associées à la formation pour cette session et cette personne
	getRessource(getchaineSQL_desinscrireCandidat_MAJSessionPersonneQualification($Id_Session_Personne));
	
	//Suppression des QCM associés à la formation pour cette session et cette personne
	getRessource(getchaineSQL_desinscrireCandidat_MAJSessionPersonneQualificationQCM($Id_Session_Personne));
	
	//Suppression des Bi dans la gestion des compétences
	getRessource(getchaineSQL_desinscrireCandidat_MAJCompetencesRelation($Id_Besoin));
	
	$req="SELECT Id FROM form_besoin WHERE Motif='Renouvellement' AND Id=".$Id_Besoin;
	getRessource($req);
	$resultB=getRessource($req);
	$nbB=mysqli_num_rows($resultB);
	
	//Mise à jour du besoin afin que la personne puisse se réisncrire
	getRessource(getchaineSQL_desinscrireCandidat_MAJBesoin($Id_Besoin));
	
	$req="SELECT form_session.Id,form_session.Id,form_session.Id_GroupeSession,form_session.Formation_Liee,form_session.Nb_Stagiaire_Maxi,form_session.Id_Formation,  ";
	$req.="form_session.Recyclage,form_session.TarifGroupe,Id_Plateforme ";
	$req.="FROM form_session 
		LEFT JOIN form_session_personne
		ON form_session_personne.Id_Session=form_session.Id 
		WHERE form_session_personne.Id =".$Id_Session_Personne." ";
	$resultSessionDate=mysqli_query($bdd,$req);
	$rowSessionForm=mysqli_fetch_array($resultSessionDate);
		
	$req="SELECT CoutSalarieAAA, CoutSalarieAAARecyclage, CoutInterimaire, CoutInterimaireRecyclage , CoutTarifGroupe, CoutTarifGroupeRecyclage
	FROM form_formation_plateforme_parametres
	WHERE Id_Formation=".$rowSessionForm['Id_Formation']." AND Id_Plateforme=".$rowSessionForm['Id_Plateforme'];
	$resultCout=mysqli_query($bdd,$req);
	$nbCout=mysqli_num_rows($resultCout);
	if($nbCout>0){
		$rowCout=mysqli_fetch_array($resultCout);
		if($rowSessionForm['Recyclage']==1){
			$CoutTarifGroupe=$rowCout['CoutTarifGroupeRecyclage'];
		}
		else{
			$CoutTarifGroupe=$rowCout['CoutTarifGroupe'];
		}
	}
	
	//Si la session est en tarif de groupe alors partage du cout 
	if($rowSessionForm['TarifGroupe']==1){
		$req="SELECT Id 
			FROM form_session_personne 
			WHERE Suppr=0 
			AND Validation_Inscription<>-1
			AND Id_Session=".$rowSessionForm['Id']." ";
		$resultPersDejaInscrit=mysqli_query($bdd,$req);
		$nbPersDejaInscrit=mysqli_num_rows($resultPersDejaInscrit);
		if($CoutTarifGroupe<>-1){
			$CoutTarifGroupe=$CoutTarifGroupe/$nbPersDejaInscrit;
		}
		
		$req="UPDATE form_session_personne 
		SET Cout=".$CoutTarifGroupe." 
		WHERE Id_Session=".$rowSessionForm['Id']." 
		AND Suppr=0
		AND Validation_Inscription<>-1 
		AND Presence IN (0,1) ";
		$resultUpdate=mysqli_query($bdd,$req);
		
		$req="UPDATE form_session_personne 
		SET Cout=0
		WHERE Id_Session=".$rowSessionForm['Id']." 
		AND (Validation_Inscription=-1 OR Presence=-1 OR Suppr=1) ";
		$resultUpdate=mysqli_query($bdd,$req);
	}
}

/**
 * inscription d'une personne manuellement
 * @param	int	$PersonneAInscrire	Identifiant de la personne a inscrire
 * @param	int	$Session_Id			Identifiant de session de formation
 * @param	int	$Prestation_Id		Identifiant de la prestation
 * @param	int	$Plateforme_Id		Identifiant de la plateforme
 * @param	int	$AF					par défaut = 0, correspond si c'est l'AF qui réalise l'inscription
 * @param	int	$Pole_Id			Identifiant du pole
 *  
 * @author Pauline Fauge <pfauge@aaa-aero.com>
 */
function inscriptionPersonneSession($PersonneAInscrire,$Session_Id,$Prestation_Id,$Plateforme_Id,$Pole_Id,$AF = 0)
{
	global $bdd;
	global $TableauIdPostesResponsablesPrestation;
	global $TableauIdPostesAF_RF;
	global $TableauIdPostesCHE_COOE;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteAssistantFormationTC;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;
	global $IdPersonneConnectee;
	global $LangueAffichage;
	$Contrat="";
	
	$req="SELECT Id 
		FROM form_session_personne 
		WHERE Suppr=0 
		AND Validation_Inscription<>-1
		AND Presence IN (0,1)
		AND Id_Personne=".$PersonneAInscrire." 
		AND Id_Session=".$Session_Id." ";
	$resultDejaInscrit=mysqli_query($bdd,$req);
	$nbDejaInscrit=mysqli_num_rows($resultDejaInscrit);
	
	if($nbDejaInscrit==0){
		//Récupération du type de contrat de la personne
		$Contrat="";
		$IdContrat=IdContrat($PersonneAInscrire,date('Y-m-d'));
		if($IdContrat>0){
			$Contrat=TypeContrat($IdContrat);
		}
		
		
		//Récupération du tableau (Id_Metier,Métier,Col) de la personne
		$Metier_Personne=Get_Metier($PersonneAInscrire);
		$Id_Metier_Personne=0;
		if($Metier_Personne[0]>0){
			$Id_Metier_Personne=$Metier_Personne[0];
		}
		$Col_Metier_Personne=$Metier_Personne[2];
		if($Col_Metier_Personne==""){$Col_Metier_Personne="Blanc";}
		if($Col_Metier_Personne=="Blanc"){$Col_Metier_Personne=0;}
		else{$Col_Metier_Personne=1;}
		
		//Récupérer les informations de la session
		$req="SELECT Id,Id_GroupeSession,Formation_Liee  ";
		$req.="FROM form_session WHERE Id=".$Session_Id;
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
		
		//Liste des formations associées
		$req="SELECT form_session.Id,form_session.Id,form_session.Id_GroupeSession,form_session.Formation_Liee,form_session.Nb_Stagiaire_Maxi,form_session.Id_Formation,  ";
		$req.="(SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,form_session.Recyclage,form_session.TarifGroupe,
				(SELECT Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Recyclage_Identique ";
		$req.="FROM form_session ";
		$req.="WHERE form_session.Id IN (";
		foreach ($tab as $val) {
			$req.=$val.",";
		}
		$req=substr($req,0,-1);
		$req.=")";
		$resultSessionDate=mysqli_query($bdd,$req);

		$Id_Besoin=0;
		//Liste des sessions de formations
		while($rowSessionForm=mysqli_fetch_array($resultSessionDate)){
			$FileAttente=false;
			
			//Recuperation du nombre de personnes inscrites
			$ResultNombreInscritSession=mysqli_query($bdd,getchaineSQL_NbInscritSession($rowSessionForm['Id']));

			$RowNombreInscritSession=mysqli_fetch_array($ResultNombreInscritSession);

			if(($RowNombreInscritSession['NOMBRE']) >= $rowSessionForm['Nb_Stagiaire_Maxi']){$FileAttente=true;}
			
			//Suppression à la demande d'Emilie : on peut pré-inscrire autant de personne que l'on veut
			//if($FileAttente==false){
			$req="SELECT form_formation.Id, form_formation.Id_TypeFormation FROM form_formation WHERE form_formation.Id=".$rowSessionForm['Id_Formation'];
			$resultFormation=mysqli_query($bdd,$req);

			$nbFormation=mysqli_num_rows($resultFormation);
			
			$Cout=-1;
			$CoutTarifGroupe=-1;
			
			$req="SELECT CoutSalarieAAA, CoutSalarieAAARecyclage, CoutInterimaire, CoutInterimaireRecyclage , CoutTarifGroupe, CoutTarifGroupeRecyclage
			FROM form_formation_plateforme_parametres
			WHERE Id_Formation=".$rowSessionForm['Id_Formation']." AND Id_Plateforme=".$Plateforme_Id;
			$resultCout=mysqli_query($bdd,$req);
			$nbCout=mysqli_num_rows($resultCout);
			if($nbCout>0){
				$rowCout=mysqli_fetch_array($resultCout);
				if($rowSessionForm['Recyclage']==1){
					if($Contrat<>""){
						if($Contrat=="Intérimaire" || $Contrat=="Intérim" || $Contrat=="Alternant intérimaire" || $Contrat==""){$Cout=$rowCout['CoutInterimaireRecyclage'];}
						else{$Cout=$rowCout['CoutSalarieAAARecyclage'];}
					}
					$CoutTarifGroupe=$rowCout['CoutTarifGroupeRecyclage'];
				}
				else{
					if($Contrat<>""){
						if($Contrat=="Intérimaire" || $Contrat=="Intérim" || $Contrat=="Alternant intérimaire" || $Contrat==""){$Cout=$rowCout['CoutInterimaire'];}
						else{$Cout=$rowCout['CoutSalarieAAA'];}
					}
					$CoutTarifGroupe=$rowCout['CoutTarifGroupe'];
				}
			}
			if($nbFormation>0){
				$rowForm=mysqli_fetch_array($resultFormation);
				
				$motif="Motif<>'Renouvellement'";
				if($rowSessionForm['Recyclage']==1){$motif="Motif='Renouvellement'";}
				if($rowSessionForm['Recyclage_Identique']==0){$motif="(Motif='Renouvellement' OR Motif<>'Renouvellement')";}
				//Récupérer l'Id besoin
				$req="SELECT Id ";
				$req.="FROM form_besoin ";
				$req.="WHERE Id_Prestation=".$Prestation_Id." AND Id_Pole=".$Pole_Id." AND (";
				$req.=" (Id_Formation=".$rowForm['Id']." AND ".$motif.") OR ";
				
				//vérifier si la formation n'a pas des formations équivalentes
				$reqSimil="SELECT Id_FormationEquivalente  
							FROM form_formationequivalente_formationplateforme 
							LEFT JOIN form_formationequivalente 
							ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
							WHERE form_formationequivalente.Id_Plateforme=".$Plateforme_Id." 
							AND form_formationequivalente_formationplateforme.Id_Formation=".$rowForm['Id']."
							AND form_formationequivalente_formationplateforme.Recyclage=".$rowSessionForm['Recyclage'];
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
				$req=substr($req,0,-3);
				$req.=") AND Id_Personne=".$PersonneAInscrire." AND Valide=1 AND Traite=0 AND Suppr=0 ";
				$resultBesoin=mysqli_query($bdd,$req);
				$nbBesoin=mysqli_num_rows($resultBesoin);
				if($nbBesoin>0){
					$rowBesoin=mysqli_fetch_array($resultBesoin);
					$Id_Besoin=$rowBesoin['Id'];
				}
				else{
					//**********Il faut créer le besoin + les compétences******************//
					//Qualification liées à la formation
					$ReqQualifFormation="SELECT Id_Qualification 
										FROM form_formation_qualification 
										WHERE Id_Formation=".$rowForm['Id']." 
										AND Suppr=0
										AND Masquer=0 ";
					$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
					$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
					
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
							CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPRENOM
						FROM
							new_competences_relation,
							new_competences_qualification,
							new_rh_etatcivil
						WHERE
							new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
							AND new_competences_relation.Id_Personne=new_rh_etatcivil.Id
							AND new_competences_relation.Type='Qualification'
							AND new_competences_relation.Evaluation != 'B%'
							AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
							AND new_competences_relation.Id_Personne = ".$PersonneAInscrire."
							AND new_competences_relation.Suppr=0 
						ORDER BY
							new_competences_relation.Id_Qualification_Parrainage ASC,
							new_competences_relation.Date_QCM DESC,
							new_competences_relation.Date_Debut DESC";
					$ResultQualifsValides=mysqli_query($bdd,$ReqQualifsValides);
					$NbQualifsValides=mysqli_num_rows($ResultQualifsValides);
					
					//Boucle pour faire les INSERT dans la table des besoins et dans les qualifications
					$Valide=1;
					$Id_Valideur=$IdPersonneConnectee;
					$Commentaire="";
					if($LangueAffichage=="FR"){$Commentaire="Suite à une formation liée";}
					else{$Commentaire="Following a related training";}
					
					$ReqDroits= "
						SELECT
							Id
						FROM
							new_competences_personne_poste_plateforme
						WHERE
							Id_Personne=".$IdPersonneConnectee."
							AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF).")
							AND Id_Plateforme IN (".implode(",",$_SESSION['Id_Plateformes']).")";
					$ResultDroits=mysqli_query($bdd,$ReqDroits);
					$NbEnregDroits=mysqli_num_rows($ResultDroits);
					if($NbEnregDroits>0){$EmisParAF=1;}
					else{$EmisParAF=0;}

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
								$EmisParAF.",".
								$Prestation_Id.",".
								$Pole_Id.",".
								$rowSessionForm['Id_Formation'].",".
								$PersonneAInscrire.",'".date('Y-m-d')."',
								'Nouveau',
								'".$Commentaire."',".
								$Valide.",
								".$Id_Valideur.",".
								$IdPersonneConnectee.",".
								"'".date('Y-m-d')."'
							)";
							
					$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
					$Id_Besoin=mysqli_insert_id($bdd);
					
					//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
					mysqli_data_seek($ResultQualifFormation,0);
					$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
					while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
					{
						$ReqInsertBesoinGPEC.="(";
						$ReqInsertBesoinGPEC.=$PersonneAInscrire;
						$ReqInsertBesoinGPEC.=",'Qualification'";
						$ReqInsertBesoinGPEC.=",".$RowQualifFormation['Id_Qualification'];
						$ReqInsertBesoinGPEC.=",'B'";
						$ReqInsertBesoinGPEC.=",0";
						$ReqInsertBesoinGPEC.=",".$Id_Besoin;
						$ReqInsertBesoinGPEC.="),";
					}
					$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
					$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
				}
				
				//Toutes les formations doivent être validée
				//Le besoin est donc enlevé du workflow
				if($Id_Besoin>0){
					if($AF==1){$ValeurTraite="2";}
					else{$ValeurTraite="1";}
					$ReqBesoinMAJ="UPDATE form_besoin SET Traite=".$ValeurTraite." WHERE Id=".$Id_Besoin;
					$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
				}
				$VALIDATION_INSCRIPTION="0";
				
				//UPDATE DE L'INDICE DU "B" DANS LES QUALIFICATIONS (POUR LE TABLEAU DES COMPETENCES)
				if($Id_Besoin>0){
					if($AF==1){$lettre="Bi";}
					else{$lettre="B";}
					$ReqRelationMAJ="UPDATE new_competences_relation SET Evaluation='".$lettre;
					$ReqRelationMAJ.="' WHERE Id_Besoin=".$Id_Besoin;
					$ResultRelationMAJ=mysqli_query($bdd,$ReqRelationMAJ);
				}
				
				$ValidationInscription=0;
				//Modification due à la demande d'Emilie pour le nombre d'inscription non limitée
				if($AF==1 && !$FileAttente){$ValidationInscription=1;}
				//Insertion des données pour la personne dans cette session de formation
				$ReqInsertSessionPersonne="
				INSERT INTO form_session_personne
					(
					Id_Besoin,
					Id_Session,
					Id_Personne,
					Id_Inscripteur,
					Date_Inscription,
					Validation_Inscription,
					Cout,
					Contrat,
					Id_Metier,
					ColBleu
					)
				VALUES
					(".
					$Id_Besoin.",".
					$rowSessionForm['Id'].",".
					$PersonneAInscrire.",".
					$IdPersonneConnectee.",'".
					Date('Y-m-d')."',
					".$ValidationInscription.",
					".$Cout.",
					'".$Contrat."',
					".$Id_Metier_Personne.",
					".$Col_Metier_Personne."
					)";

				$ResultInsertSessionPersonne=mysqli_query($bdd,$ReqInsertSessionPersonne);
				$ID_SESSION_PERSONNE=mysqli_insert_id($bdd);
				if($ID_SESSION_PERSONNE>0){
					//INSERTION DES QUALIFICATIONS ET DES QCM DANS LES TABLES FORM_SESSION_PERSONNE_QUALIFICATION et _QCM LORSQU'UNE PERSONNE EST AJOUTEE
					//LA LANGUE POUR LE QCM LIEE EST PAR DEFAUT LA MEME QUE POUR LE QCM INITIAL
					$QUALIFICATION_PRECEDENTE="";
					
					//RECUPERATION DES QCM EN FONCTION DES QUALIFICATIONS DE LA FORMATION
					$ReqFormationQualificationQCM="
						SELECT
							TABLE_TEMP.*
						FROM
							(
							SELECT
								form_formation_qualification.Id_Formation AS ID_FORMATION,
								form_formation_qualification.Id_Qualification AS ID_QUALIFICATION,
								new_competences_qualification.Libelle AS LIBELLE_QUALIFICATION,
								IF(ISNULL(form_qcm.Id),0,form_qcm.Id) AS ID_QCM,
								IF(ISNULL(form_qcm.Id_QCM_Lie),0,form_qcm.Id_QCM_Lie) AS ID_QCM_LIE,
								IF(ISNULL(form_qcm.Suppr),0,form_qcm.Suppr) AS SUPPR_QCM,
								IF(ISNULL(form_qcm_langue.Id_Langue),0,form_qcm_langue.Id_Langue) AS ID_QCM_LANGUE,
								IF(ISNULL(form_qcm_langue.Suppr),0,form_qcm_langue.Suppr) AS SUPPR_QCM_LANGUE,
								IF(ISNULL(form_langue.Libelle),0,form_langue.Libelle) AS LIBELLE_LANGUE,
								IF(ISNULL(form_langue.Suppr),0,form_langue.Suppr) AS SUPPR_LANGUE,
								IF(ISNULL(form_qcm.Id),0,IF(form_qcm.Id_QCM_Lie=0,form_qcm.Id,CONCAT(form_qcm.Id_QCM_Lie,'|',form_qcm.Id))) AS ID_QCM_ID_QCM_LIE
							FROM
								form_formation_qualification
								LEFT JOIN form_formation_qualification_qcm ON form_formation_qualification.Id=form_formation_qualification_qcm.Id_Formation_Qualification
								LEFT JOIN new_competences_qualification ON new_competences_qualification.Id=form_formation_qualification.Id_Qualification
								LEFT JOIN form_qcm ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
								LEFT JOIN form_qcm_langue ON form_qcm_langue.Id_QCM=form_qcm.Id
								LEFT JOIN form_langue ON form_langue.Id=form_qcm_langue.Id_Langue
							WHERE
								form_formation_qualification.Suppr=0
								AND form_formation_qualification.Masquer=0 
								AND (ISNULL(form_qcm.Suppr) OR form_qcm.Suppr=0)
								AND form_formation_qualification.Id_Formation=".$rowSessionForm['Id_Formation']."
							ORDER BY
								ID_FORMATION,
								ID_QUALIFICATION,
								ID_QCM_ID_QCM_LIE
							) AS TABLE_TEMP
						WHERE
							TABLE_TEMP.SUPPR_QCM=0
							AND TABLE_TEMP.SUPPR_QCM_LANGUE=0
							AND TABLE_TEMP.SUPPR_LANGUE=0
					";
					$ResultFormationQualificationQCM=mysqli_query($bdd,$ReqFormationQualificationQCM);
					$nbFormationQualificationQCM=mysqli_num_rows($ResultFormationQualificationQCM);
					if($nbFormationQualificationQCM>0){
						while($RowFormationQualificationQCM=mysqli_fetch_array($ResultFormationQualificationQCM)){
							$ID_SESSION_PERSONNE_QUALIFIICATION=0;
							//QUALIFICATION
							if($QUALIFICATION_PRECEDENTE!=$RowFormationQualificationQCM['ID_QUALIFICATION'])
							{
								$QUALIFICATION_PRECEDENTE=$RowFormationQualificationQCM['ID_QUALIFICATION'];
								$ReqInsertSessionPersonneQualification="
									INSERT INTO form_session_personne_qualification
										(
										Id_Session_Personne,
										Id_Qualification
										)
									VALUES
										(
										".$ID_SESSION_PERSONNE.",".
										$RowFormationQualificationQCM['ID_QUALIFICATION']."
										)";
								$ResultInsertSessionPersonneQualification=mysqli_query($bdd,$ReqInsertSessionPersonneQualification);
								$ID_SESSION_PERSONNE_QUALIFIICATION=mysqli_insert_id($bdd);
							}
							
							if($ID_SESSION_PERSONNE_QUALIFIICATION>0){
								if($_SESSION['PartieFormation']>1){
									maj_QCM_SessionPersonneQualification($ID_SESSION_PERSONNE_QUALIFIICATION);
								}
							}
						}
					}
					
					if($_SESSION['PartieFormation']>1){
						//Ajout des documents complémentaires 
						$ReqFormationDocuments="
							SELECT DISTINCT
								form_formation_document.Id_Document
							FROM
								form_formation_document
							WHERE
								form_formation_document.Suppr=0
								AND form_formation_document.Id_Formation=".$rowSessionForm['Id_Formation']."
						";
						$ResultFormationDocument=mysqli_query($bdd,$ReqFormationDocuments);
						$nbFormationDocument=mysqli_num_rows($ResultFormationDocument);
						if($nbFormationDocument>0){
							while($RowFormationDocument=mysqli_fetch_array($ResultFormationDocument)){
								$ID_SESSION_PERSONNE_DOCUMENT=0;
								//DOCUMENTS
								$ReqInsertSessionPersonneDocument="
									INSERT INTO form_session_personne_document
										(
										Id_Session_Personne,
										Id_Document
										)
									VALUES
										(
										".$ID_SESSION_PERSONNE.",".
										$RowFormationDocument['Id_Document']."
										)";
								$ResultInsertSessionPersonneDocument=mysqli_query($bdd,$ReqInsertSessionPersonneDocument);
								$ID_SESSION_PERSONNE_DOCUMENT=mysqli_insert_id($bdd);
								
								if($ID_SESSION_PERSONNE_DOCUMENT>0){
									maj_Langue_SessionPersonneDocument($ID_SESSION_PERSONNE_DOCUMENT);
								}
							}
						}
					}
					//Envoi du mail aux assitantes pour les informer de la préinscription
					$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
					$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
					
					$reqPers="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$PersonneAInscrire;
					$ResultPers=mysqli_query($bdd,$reqPers);
					$NbPersonne=mysqli_num_rows($ResultPers);
					$Personne="";
					if($NbPersonne>0){
						$rowPersonne=mysqli_fetch_array($ResultPers);
						$Personne.=$rowPersonne['Nom']." ".$rowPersonne['Prenom'];
					}
					
					$Formation="";
					$Organisme="";
					$Id_TypeFormation=0;
					//Afficher les informations de la formation
					$req=Get_SQL_InformationsPourFormation($Plateforme_Id, $rowSessionForm['Id_Formation']);
					$resultFormation=mysqli_query($bdd,$req);
					$nbFormation=mysqli_num_rows($resultFormation);
					if($nbFormation>0){
						$rowForm=mysqli_fetch_array($resultFormation);
						if($rowForm['Organisme']<>""){$Organisme=" (".stripslashes($rowForm['Organisme']).")";}
						if($rowSessionForm['Recyclage']==0){$Formation=$rowForm['Libelle'];}
						else{$Formation=$rowForm['LibelleRecyclage'];}
						$Id_TypeFormation=$rowForm['Id_TypeFormation'];
					}
					
					$Date="";
					//Liste des formations associées
					$req="SELECT form_session_date.DateSession,form_session_date.Heure_Debut,form_session_date.Heure_Fin ";
					$req.="FROM form_session_date ";
					$req.="WHERE form_session_date.Id_Session = ".$rowSessionForm['Id'];
					$resultSessionDates=mysqli_query($bdd,$req);
					$nbSessionDates=mysqli_num_rows($resultSessionDates);
					if($nbSessionDates>0){
						while($rowSessionDates=mysqli_fetch_array($resultSessionDates)){
							$Date.=AfficheDateJJ_MM_AAAA($rowSessionDates['DateSession'])." <br>";
						}
					}
					
					$Id_Plateforme=0;
					$req="SELECT LEFT(Libelle,7) AS Prestation, Id_Plateforme FROM new_competences_prestation WHERE Id=".$Prestation_Id;
					$ResultSite=mysqli_query($bdd,$req);
					$NbSite=mysqli_num_rows($ResultSite);
					$Presta="";
					if($NbSite>0){
						$rowSite=mysqli_fetch_array($ResultSite);
						$Presta.=$rowSite['Prestation'];
						$Id_Plateforme=$rowSite['Id_Plateforme'];
					}
					
					if($LangueAffichage=="FR"){
						if($AF==0){
							$Objet="Pré-inscription formation ".$Formation.$Organisme." - ".$Presta;
							
							$MessageMail="	<html>
										<head><title>Pré-inscription formation ".$Formation.$Organisme." </title></head>
										<body>
											Bonjour,
											<br><br>
											<i>Cette boîte mail est une boîte mail générique</i>
											<br><br>
											".$Personne." a été pré-inscrit(e) à la formation ".$Formation.$Organisme." du ".$Date." <br>
											Merci de valider l'inscription.
											<br>
											Bonne journée.<br>
											Formation Extranet Daher industriel services DIS.
										</body>
									</html>";
						}
						else{
							$Objet="Inscription formation ".$Formation.$Organisme." - ".$Presta;
							
							$MessageMail="	<html>
										<head><title>Inscription formation ".$Formation.$Organisme." </title></head>
										<body>
											Bonjour,
											<br><br>
											<i>Cette boîte mail est une boîte mail générique</i>
											<br><br>
											".$Personne." a été inscrit(e) à la formation ".$Formation.$Organisme." du ".$Date." <br>
											<br>
											Bonne journée.<br>
											Formation Extranet Daher industriel services DIS.
										</body>
									</html>";
						}
					}
					else{
						if($AF==0){
							$Objet="Pre-registration training ".$Formation.$Organisme." - ".$Presta;
							$MessageMail="	<html>
											<head><title>Pre-registration training ".$Formation.$Organisme."</title></head>
											<body>
												Hello,
												<br><br>
												<i>This mailbox is a generic mailbox</i>
												<br><br>
												".$Personne." was pre-enrolled in the ".$Formation.$Organisme." training of ".AfficheDateJJ_MM_AAAA($Date)." <br>
												Thank you for validating the registration.
												<br>
												Have a good day.<br>
												Training Extranet Daher industriel services DIS.
											</body>
										</html>";
						}
						else{
							$Objet="Registration training ".$Formation.$Organisme." - ".$Presta;
							$MessageMail="	<html>
											<head><title>Registration training ".$Formation.$Organisme."</title></head>
											<body>
												Hello,
												<br><br>
												<i>This mailbox is a generic mailbox</i>
												<br><br>
												".$Personne." was enrolled in the ".$Formation.$Organisme." training of ".AfficheDateJJ_MM_AAAA($Date)." <br>
												<br>
												Have a good day.<br>
												Training Extranet Daher industriel services DIS.
											</body>
										</html>";
						}
					}
					$Emails="";
					if($AF==0){
						//Liste des AF
						$reqSuite="";
						if($Id_TypeFormation==$IdTypeFormationEprouvette || $Id_TypeFormation==$IdTypeFormationInterne){$reqSuite=$IdPosteAssistantFormationInterne;}
						elseif($Id_TypeFormation==$IdTypeFormationTC){$reqSuite=$IdPosteAssistantFormationTC;}
						elseif($Id_TypeFormation==$IdTypeFormationExterne){$reqSuite=$IdPosteAssistantFormationExterne;}
						$reqAF="SELECT DISTINCT EmailPro 
								FROM new_competences_personne_poste_plateforme 
								LEFT JOIN new_rh_etatcivil
								ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
								WHERE new_competences_personne_poste_plateforme.Id_Poste =".$reqSuite." 
								AND Id_Plateforme=".$Id_Plateforme." ";
						$ResultAF=mysqli_query($bdd,$reqAF);
						$NbAF=mysqli_num_rows($ResultAF);
						if($NbAF>0){
							while($RowAF=mysqli_fetch_array($ResultAF)){
								if($RowAF['EmailPro']<>""){$Emails.=$RowAF['EmailPro'].",";}
							}
						}
						if($Emails<>""){$Emails=substr($Emails,0,-1);}
						
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f qualipso@aaa-aero.com'))
								{echo "";}
						}
					}
				}
			}
			//}
			
			//Si la session est en tarif de groupe alors partage du cout 
			if($rowSessionForm['TarifGroupe']==1){
				$req="SELECT Id 
					FROM form_session_personne 
					WHERE Suppr=0 
					AND Validation_Inscription<>-1
					AND Id_Session=".$rowSessionForm['Id']." ";
				$resultPersDejaInscrit=mysqli_query($bdd,$req);
				$nbPersDejaInscrit=mysqli_num_rows($resultPersDejaInscrit);
				if($CoutTarifGroupe<>-1){
					$CoutTarifGroupe=$CoutTarifGroupe/$nbPersDejaInscrit;
				}
				
				$req="UPDATE form_session_personne 
				SET Cout=".$CoutTarifGroupe." 
				WHERE Id_Session=".$rowSessionForm['Id']." 
				AND Suppr=0
				AND Validation_Inscription<>-1 ";
				$resultUpdate=mysqli_query($bdd,$req);
				
				$req="UPDATE form_session_personne 
				SET Cout=0
				WHERE Id_Session=".$rowSessionForm['Id']." 
				AND (Validation_Inscription=-1 OR Presence=-1 OR Suppr=1) ";
				$resultUpdate=mysqli_query($bdd,$req);
			}
		}
	}
}

/**
 * Annulation d'une session
 * @param	int	$Session_Id	Identifiant de session de formation
 * @param	int	$Annuleur_Id	Identifiant de la pesonne qui annule
 * @param	int	$Id_Plateforme	Identifiant de la plateforme pour la langue de la formation
 *  
 * @author Pauline Fauge <pfauge@aaa-aero.com>
 */
function annulationSession($Session_Id,$Annuleur_Id,$Id_Plateforme)
{
	global $bdd;
	global $IdPosteChefEquipe;
	global $IdPosteCoordinateurEquipe;
	global $LangueAffichage;
	
	//Récupération des informations liées à la session
	$ResultSession=get_session($Session_Id);
	$RowSession=mysqli_fetch_array($ResultSession);
	$ObjetConvocation="Annulation de la session de formation ";
	
	$formation=$RowSession['FORMATION_REFERENCE'];
	$req="SELECT Libelle, LibelleRecyclage
			FROM form_formation_langue_infos
			WHERE Id_Formation=".$RowSession['ID_FORMATION']."
			AND Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=".$Id_Plateforme."
				AND Id_Formation=".$RowSession['ID_FORMATION']."
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0";
	echo $req;
	$ResultFormation=mysqli_query($bdd,$req);
	$nbFormation=mysqli_num_rows($ResultFormation);
	if($nbFormation>0){
		$RowFormation=mysqli_fetch_array($ResultFormation);
		if($RowSession['RECYCLAGE']==0){$formation=$RowFormation['Libelle'];}
		else{$formation=$RowFormation['LibelleRecyclage'];}
	}
		
	$Convocation=
		"Annulation de la session de formation "
		.$formation."
		du ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." à ".$RowSession['HEURE_DEBUT']."
		au ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])." à ".$RowSession['HEURE_FIN'];
		
	//Définition d'une tableau contenant les ID_SESSION_PERSONNE
	$TableauIdSessionPersonnesAnnulees=array();
		
	//Lister dans un tableau les personnes de la session de formation
	//-----------------------------------------------------------------------------
	$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($Session_Id));
	$TableauPersonnesParPrestation=array();
	$Personnes="";
	while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes)){
		$Personnes.=$RowSessionPersonnes['ID_PERSONNE'].",";
		//Traitement des enregistrements dans les différentes tables
		//----------------------------------------------------------
		desinscrire_candidat($RowSessionPersonnes['ID_BESOIN'], $RowSessionPersonnes['ID']);
	}
	//Mise à jour de la table form_session
	//------------------------------------
	$ReqSession="UPDATE form_session SET Annule=1, Id_Annuleur=".$Annuleur_Id." WHERE Id=".$Session_Id;
	$ResultSession=mysqli_query($bdd,$ReqSession);
	
	if($Personnes<>""){
		$Personnes=substr($Personnes,0,-1);
		//Envoie de l'email
		
		//Récupération de l'ensemble des responsables de chaque personne
		$reqResponsables="SELECT DISTINCT Id_Personne, 
		(SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS EmailPro 
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.") 
		AND CONCAT(Id_Prestation,'_',Id_Pole) IN 
			(SELECT DISTINCT CONCAT(Id_Prestation,'_',Id_Pole)
			FROM new_competences_personne_prestation 
			WHERE Date_Debut<='".date("Y-m-d")."'
			AND (Date_Fin>='".date("Y-m-d")."' OR Date_Fin<='0001-01-01')
			AND Id_Personne IN (".$Personnes.")
			)
		AND (SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne)<>''
		";
		$ResultResponsables=mysqli_query($bdd,$reqResponsables);
		$nbResp=mysqli_num_rows($ResultResponsables);
		
		if($nbResp>0){
			$Emails="";
			while($RowResp=mysqli_fetch_array($ResultResponsables)){
				$Emails.=$RowResp['EmailPro'].",";
			}
			$Emails=substr($Emails,0,-1);
			//Tableau des personnes inscrites pour cette prestation en format tableau HTML
			mysqli_data_seek($ResultSessionPersonnes,0);
			$TableauHTMLPersonnesPrestation="";
			while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes)){
				$TableauHTMLPersonnesPrestation.="<tr><td style='border:1px solid black;'>".$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']."</td></tr>\n";
			}
			
			//Elaboration du mail
			$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
			$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			if($LangueAffichage=="FR"){
				$Message="	<html>
								<head><title>Annulation session de formation</title></head>
								<body>
									Bonjour,
									<br><br>
									<i>Cette boîte mail est une boîte mail générique</i>
									<br><br>
									".
									$Convocation."
									<table style='border:1px solid black; border-spacing:0;'>
										<tr>
											<td style='border:1px solid black;'>Personnes concernées : </td>
										</tr>\n".
										$TableauHTMLPersonnesPrestation."
									</table>
									<br>
									Bonne journée.<br>
									Formation Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			else{
				$Message="	<html>
								<head><title>Cancellation training session</title></head>
								<body>
									Hello,
									<br><br>
									<i>This mailbox is a generic mailbox</i>
									<br><br>
									".
									$Convocation."
									<table style='border:1px solid black; border-spacing:0;'>
										<tr>
											<td style='border:1px solid black;'>Persons concerned : </td>
										</tr>\n".
										$TableauHTMLPersonnesPrestation."
									</table>
									<br>
									Have a good day.<br>
									Training Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			//if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
			//{
				if(mail($Emails,$ObjetConvocation,$Message,$Headers,'-f qualipso@aaa-aero.com'))
					{echo "Un message a été envoyé à ".$Emails."\n";}
				else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
			//}
		}
	}
}

/**
 * Suppresion d'une session
 * @param	int	$Session_Id	      Identifiant de session de formation
 *
 * @author Rémy Parran <rparran@aaa-aero.com>
 */
function suppressionSession($Session_Id)
{
    global $bdd;
    global $IdPosteChefEquipe;
    global $IdPosteCoordinateurEquipe;
    global $DateJour;
    global $IdPersonneConnectee;
    
    //Mise à jour de la table form_session
    //------------------------------------
    $ReqSession="UPDATE form_session SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".$DateJour."' WHERE Id=".$Session_Id;
    $ResultSession=mysqli_query($bdd,$ReqSession);
}


/**
 * getMailsDestinataires
 * 
 * Concatene et recupere les adresses email pro a partir du resultat d\'une requete.
 * Attention : La requete doit contenir le nom de champ 'EmailPro'.
 * 
 * @param resource $ressource La ressource fournie par l'execution de la requete
 * @return string La liste des emails destinataires
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getMailsDestinataires($ressource) {
	
	$nbResp=mysqli_num_rows($ressource);
	$Emails="";
	
	if($nbResp>0){		
		while($RowResp=mysqli_fetch_array($ressource))
			$Emails.=$RowResp['EmailPro'].",";
		
		$Emails=substr($Emails,0,-1);
	}
	return $Emails;
}

/**
 * Passage qualification sans session de formation
 * @param	int	$Id_Besoin			Identifiant du besoin concerné
 * @param	int	$Id_Qualification	Identifiant de la qualification
 * @param	int	$Id_QCM				Identifiant du QCM à passer
 * @param	int	$Id_QCM_Langue		Identifiant de la langue du QCM
 * @param	int	$Id_QCMLie			Identifiant du QCM lié à passer si il y en a
 * @param	int	$Id_QCMLie_Langue	Identifiant de la langue du QCM lié à passer si il y en a
 *  
 * @author Pauline Fauge <pfauge@aaa-aero.com>
 */
function passageQualificationsSansSession($Id_Besoin,$Id_Qualification,$Id_QCM,$Id_QCM_Langue,$Id_QCMLie,$Id_QCMLie_Langue){
	global $bdd;
	
	//Récupération des infos du besoin 
	$req="SELECT form_besoin.Id_Personne, 
		form_besoin.Id_Formation,
		form_formation.Id_TypeFormation
		FROM form_besoin 
		LEFT JOIN form_formation
		ON form_besoin.Id_Formation=form_formation.Id
		WHERE form_besoin.Id=".$Id_Besoin;
	$resultBesoin=getRessource($req);
	$nbBesoin=mysqli_num_rows($resultBesoin);
	
	if($nbBesoin>0){
		$rowBesoin=mysqli_fetch_array($resultBesoin);

		//Le besoin doit être enlevé du workflow et mis en Traite par QCM sans session = 5
		$ReqBesoinMAJ="UPDATE form_besoin SET Traite=5 WHERE Id=".$Id_Besoin;
		$ResultBesoinMAJ=getRessource($ReqBesoinMAJ);

		//Pas de mise à jour du B dans le tableau de compétence car ce n'est pas une inscription en formation
		

		//INSERTION DES QUALIFICATIONS ET DES QCM DANS LA TABLE FORM_SESSION_PERSONNE_QUALIFICATION
		$ReqInsertSessionPersonneQualification="
			INSERT INTO form_session_personne_qualification
				(
				Id_Qualification,
				Id_Besoin,
				Id_QCM,
				Id_QCM_Lie,
				Id_LangueQCM,
				Id_LangueQCMLie,
				DateHeureCreation,
				TypePassageQCM
				)
			VALUES
				(
				".$Id_Qualification.",
				".$Id_Besoin.",
				".$Id_QCM.",
				".$Id_QCMLie.",
				".$Id_QCM_Langue.",
				".$Id_QCMLie_Langue.",
				'".date('Y-m-d H:i:s')."',
				1
				)";
		$ResultInsertSessionPersonneQualification=getRessource($ReqInsertSessionPersonneQualification);
		$Id_SessionPersonneQualification=mysqli_insert_id($bdd);
		maj_QCM_SessionPersonneQualification($Id_SessionPersonneQualification,$Id_QCM,$Id_QCMLie,$Id_QCM_Langue,$Id_QCMLie_Langue);
		
		//DOCUMENTS EVALUATION A CHAUD
		$ReqInsertSessionPersonneDocument="
			INSERT INTO form_session_personne_document
				(
				Id_SessionPersonneQualification,
				Id_Document
				)
			VALUES
				(
				".$Id_SessionPersonneQualification.",
				6
				)";
		$ResultInsertSessionPersonneDocument=mysqli_query($bdd,$ReqInsertSessionPersonneDocument);
		$ID_SESSION_PERSONNE_DOCUMENT=mysqli_insert_id($bdd);
		
		if($ID_SESSION_PERSONNE_DOCUMENT>0){
			maj_Langue_SessionPersonneDocumentSansSessionPersonne($ID_SESSION_PERSONNE_DOCUMENT,1);
		}
	}
}

/**
 * Passage qualification sans session de formation
 * @param	int	$Id_SessionPersonneQualification 	Identifiant de la session personne qualification
 * @param	int	$Id_QCM				Identifiant du QCM à passer (non obligatoire)
 * @param	int	$Id_QCM_Langue		Identifiant de la langue du QCM (non obligatoire)
 * @param	int	$Id_QCMLie			Identifiant du QCM lié à passer si il y en a (non obligatoire)
 * @param	int	$Id_QCMLie_Langue	Identifiant de la langue du QCM lié à passer si il y en a (non obligatoire)
 *  
 * @author Pauline Fauge <pfauge@aaa-aero.com>
 */
function maj_QCM_SessionPersonneQualification($Id_SessionPersonneQualification,$Id_QCM = 0,$Id_QCMLie = 0,$Id_QCM_Langue = 0,$Id_QCMLie_Langue = 0){
	global $bdd;
	if($Id_QCM==0){
		//Rechercher les informations par défaut
		$req="SELECT DISTINCT 
			(SELECT Id_Formation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Formation, 
			form_session_personne_qualification.Id_Qualification 
			FROM form_session_personne_qualification
			LEFT JOIN form_session_personne 
			ON form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id 
			WHERE form_session_personne_qualification.Id=".$Id_SessionPersonneQualification;
		$resultSessionPersonneQualification=getRessource($req);
		$nbSessionPersonneQualification=mysqli_num_rows($resultSessionPersonneQualification);
		if($nbSessionPersonneQualification>0){
			$rowSessionPersonneQualif=mysqli_fetch_array($resultSessionPersonneQualification);
		
			$req="SELECT 
				form_formation_qualification_qcm.Id_QCM,
				form_formation_qualification_qcm.Id_Langue,
				form_qcm.Id_QCM_Lie
				FROM form_formation_qualification_qcm
				LEFT JOIN form_qcm
				ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
				WHERE (SELECT Id_Formation FROM form_formation_qualification WHERE form_formation_qualification.Suppr=0 AND form_formation_qualification.Id=form_formation_qualification_qcm.Id_Formation_Qualification LIMIT 1) =".$rowSessionPersonneQualif['Id_Formation']."
				AND (SELECT Id_Qualification FROM form_formation_qualification WHERE form_formation_qualification.Suppr=0 AND form_formation_qualification.Id=form_formation_qualification_qcm.Id_Formation_Qualification LIMIT 1)=".$rowSessionPersonneQualif['Id_Qualification']."
				AND form_formation_qualification_qcm.Suppr=0 
				AND form_qcm.Suppr=0";
			$resultQCM=mysqli_query($bdd,$req);
			$nbQCM=mysqli_num_rows($resultQCM);
			if($nbQCM>0){
				$rowQCM=mysqli_fetch_array($resultQCM);

				$Id_QCM=$rowQCM['Id_QCM'];
				$Id_QCM_Langue=$rowQCM['Id_Langue'];
				$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
				if($Id_QCMLie>0){
					//Verifier si le QCM Lie existe dans la langue 
					$req="SELECT Id_Langue FROM form_qcm_langue WHERE Brouillon=0 AND Suppr=0 AND Id_Langue=".$rowQCM['Id_Langue']." AND Id_QCM=".$Id_QCMLie;
					$resultQCMLie=mysqli_query($bdd,$req);
					$nbQCMLie=mysqli_num_rows($resultQCMLie);
					if($nbQCMLie>0){
						$Id_QCMLie_Langue=$rowQCM['Id_Langue'];
					}
					else{
						$req="SELECT Id_Langue FROM form_qcm_langue WHERE Brouillon=0 AND Suppr=0 AND Id_QCM=".$Id_QCMLie;
						$resultQCMLie=mysqli_query($bdd,$req);
						$nbQCMLie=mysqli_num_rows($resultQCMLie);
						if($nbQCMLie>0){
							$rowQCMLie=mysqli_fetch_array($resultQCMLie);
							$Id_QCMLie_Langue=$rowQCMLie['Id_Langue'];
						}
					}
				}

			}
		}
	}
	
	//Ajout des informations dans session personne qualification 
	$req="UPDATE form_session_personne_qualification 
		SET Id_QCM=".$Id_QCM." ,
		Id_QCM_Lie=".$Id_QCMLie." ,
		Id_LangueQCM=".$Id_QCM_Langue." ,
		Id_LangueQCMLie=".$Id_QCMLie_Langue.",
		DateHeureCreation='".date('Y-m-d H:i:s')."',
		Resultat='',
		ResultatMere=0,
		Id_Repondeur=0,
		DateHeureRepondeur=0 
		WHERE Id=".$Id_SessionPersonneQualification;
	$resultUpdate=mysqli_query($bdd,$req);
	
	//Suppression des anciennes données si existantes dans session_personne_qualification_question avant d'en remettre
	$req="UPDATE form_session_personne_qualification_question 
		SET Suppr=1		
		WHERE Id_Session_Personne_Qualification=".$Id_SessionPersonneQualification;
	$resultUpdate=mysqli_query($bdd,$req);
	
	//Ajout des nouvelles données si Id_QCM > 0 
	if($Id_QCM>0){
		//Récupération du nombre de question du QCM 
		$req="SELECT Nb_Question FROM form_qcm WHERE Id=".$Id_QCM;
		$resultQCM=mysqli_query($bdd,$req);
		$nbQCM=mysqli_num_rows($resultQCM);
		if($nbQCM>0){
			$rowQCM=mysqli_fetch_array($resultQCM);
			$NbQuestion=$rowQCM['Nb_Question'];
			
			//Récupération des questions obligatoires du QCM 
			$req="SELECT form_qcm_langue_question.Id 
				FROM form_qcm_langue_question 
				LEFT JOIN form_qcm_langue 
				ON form_qcm_langue_question.Id_QCM_Langue=form_qcm_langue.Id
				WHERE form_qcm_langue_question.Suppr=0 
				AND Type='Obligatoire'
				AND form_qcm_langue.Suppr=0
				AND form_qcm_langue.Id_Langue=".$Id_QCM_Langue." 
				AND form_qcm_langue.Id_QCM=".$Id_QCM;
			$resultQuestion=mysqli_query($bdd,$req);
			$nbQuestionLangue=mysqli_num_rows($resultQuestion);
			$tab_Question = array();
			$nb=0;
			if($nbQuestionLangue>0){
				while($rowQuestion=mysqli_fetch_array($resultQuestion)){
					$tab_Question[$nb]=$rowQuestion['Id'];
					$nb++;
				}
			}
			
			if($nb<$NbQuestion){
				//Récupération des questions facultative du QCM si reste de la place
				$req="SELECT form_qcm_langue_question.Id 
					FROM form_qcm_langue_question 
					LEFT JOIN form_qcm_langue 
					ON form_qcm_langue_question.Id_QCM_Langue=form_qcm_langue.Id
					WHERE form_qcm_langue_question.Suppr=0 
					AND Type<>'Obligatoire'
					AND form_qcm_langue.Suppr=0
					AND form_qcm_langue.Id_Langue=".$Id_QCM_Langue." 
					AND form_qcm_langue.Id_QCM=".$Id_QCM."
					ORDER BY RAND() ";
				$resultQuestion=mysqli_query($bdd,$req);
				$nbQuestionLangue=mysqli_num_rows($resultQuestion);
				if($nbQuestionLangue>0){
					while($rowQuestion=mysqli_fetch_array($resultQuestion)){
						if($nb<$NbQuestion){
							$tab_Question[$nb]=$rowQuestion['Id'];
							$nb++;
						}
					
					}
				}
			}
			
			//Mélanger les questions dans $tab_Question
			shuffle($tab_Question);
			
			//Ajout dans la table form_session_personne_qualification_question
			foreach ($tab_Question as $Id_QCM_Langue_Question){
				$req="INSERT INTO form_session_personne_qualification_question (Id_Session_Personne_Qualification,Id_QCM,Id_QCM_Langue_Question) 
					VALUES (".$Id_SessionPersonneQualification.",".$Id_QCM.",".$Id_QCM_Langue_Question.") ";
				$resultInsert=mysqli_query($bdd,$req);
				
			}
		}
	}
	
	//Ajout des nouvelles données si Id_QCMLie > 0 
	if($Id_QCMLie>0){
		//Récupération du nombre de question du QCM 
		$req="SELECT Nb_Question FROM form_qcm WHERE Id=".$Id_QCMLie;
		$resultQCM=mysqli_query($bdd,$req);
		$nbQCM=mysqli_num_rows($resultQCM);
		if($nbQCM>0){
			$rowQCM=mysqli_fetch_array($resultQCM);
			$NbQuestion=$rowQCM['Nb_Question'];
			
			//Récupération des questions obligatoires du QCM 
			$req="SELECT form_qcm_langue_question.Id 
				FROM form_qcm_langue_question 
				LEFT JOIN form_qcm_langue 
				ON form_qcm_langue_question.Id_QCM_Langue=form_qcm_langue.Id
				WHERE form_qcm_langue_question.Suppr=0 
				AND Type='Obligatoire'
				AND form_qcm_langue.Suppr=0
				AND form_qcm_langue.Id_Langue=".$Id_QCMLie_Langue." 
				AND form_qcm_langue.Id_QCM=".$Id_QCMLie;
			$resultQuestion=mysqli_query($bdd,$req);
			$nbQuestionLangue=mysqli_num_rows($resultQuestion);
			$tab_Question = array();
			$nb=0;
			if($nbQuestionLangue>0){
				while($rowQuestion=mysqli_fetch_array($resultQuestion)){
					$tab_Question[$nb]=$rowQuestion['Id'];
					$nb++;
				}
			}
			
			if($nb<$NbQuestion){
				//Récupération des questions facultative du QCM si reste de la place
				$req="SELECT form_qcm_langue_question.Id 
					FROM form_qcm_langue_question 
					LEFT JOIN form_qcm_langue 
					ON form_qcm_langue_question.Id_QCM_Langue=form_qcm_langue.Id
					WHERE form_qcm_langue_question.Suppr=0 
					AND Type<>'Obligatoire'
					AND form_qcm_langue.Suppr=0
					AND form_qcm_langue.Id_Langue=".$Id_QCMLie_Langue." 
					AND form_qcm_langue.Id_QCM=".$Id_QCMLie;
				$resultQuestion=mysqli_query($bdd,$req);
				$nbQuestionLangue=mysqli_num_rows($resultQuestion);
				if($nbQuestionLangue>0){
					while($rowQuestion=mysqli_fetch_array($resultQuestion)){
						if($nb<$NbQuestion){
							$tab_Question[$nb]=$rowQuestion['Id'];
							$nb++;
						}
					
					}
				}
			}
			
			//Mélanger les questions dans $tab_Question
			shuffle($tab_Question);
			
			//Ajout dans la table form_session_personne_qualification_question
			foreach ($tab_Question as $Id_QCM_Langue_Question){
				$req="INSERT INTO form_session_personne_qualification_question (Id_Session_Personne_Qualification,Id_QCM,Id_QCM_Langue_Question) 
					VALUES (".$Id_SessionPersonneQualification.",".$Id_QCMLie.",".$Id_QCM_Langue_Question.") ";
				$resultInsert=mysqli_query($bdd,$req);
				
			}
		}
	}
}

/**
 * Passage document sans session de formation
 * @param	int	$Id_SessionPersonneDocument 	Identifiant de la session personne document
 * @param	int	$Id_LangueDocument	Identifiant de la langue du document (non obligatoire)
 *  
 * @author Pauline Fauge <pfauge@aaa-aero.com>
 */
function maj_Langue_SessionPersonneDocument($Id_SessionPersonneDocument,$Id_LangueDocument = 0){
	global $bdd;
	$Id_Document=0;
	if($Id_LangueDocument==0){
		//Rechercher les informations par défaut
		$req="SELECT DISTINCT 
			(SELECT Id_Formation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Formation, 
			form_session_personne_document.Id_Document 
			FROM form_session_personne_document
			LEFT JOIN form_session_personne 
			ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id 
			WHERE form_session_personne_document.Id=".$Id_SessionPersonneDocument;
		$resultSessionPersonneDocument=getRessource($req);
		$nbSessionPersonneDocument=mysqli_num_rows($resultSessionPersonneDocument);
		if($nbSessionPersonneDocument>0){
			$rowSessionPersonneDocument=mysqli_fetch_array($resultSessionPersonneDocument);
			$Id_Document=$rowSessionPersonneDocument['Id_Document'];
			$req="SELECT 
				form_document_langue.Id_Langue
				FROM form_document_langue
				LEFT JOIN form_document
				ON form_document_langue.Id_Document=form_document.Id
				WHERE form_document.Id=".$rowSessionPersonneDocument['Id_Document']."
				AND form_document_langue.Suppr=0 
				AND form_document.Suppr=0
				ORDER BY Id_Langue";
			$resultDocument=mysqli_query($bdd,$req);
			$nbDocument=mysqli_num_rows($resultDocument);
			if($nbDocument>0){
				$rowDocument=mysqli_fetch_array($resultDocument);
				$Id_LangueDocument=$rowDocument['Id_Langue'];
			}
		}
	}
	else{
		//Rechercher les informations par défaut
		$req="SELECT DISTINCT 
			(SELECT Id_Formation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Formation, 
			form_session_personne_document.Id_Document 
			FROM form_session_personne_document
			LEFT JOIN form_session_personne 
			ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id 
			WHERE form_session_personne_document.Id=".$Id_SessionPersonneDocument;
		$resultSessionPersonneDocument=getRessource($req);
		$nbSessionPersonneDocument=mysqli_num_rows($resultSessionPersonneDocument);
		if($nbSessionPersonneDocument>0){
			$rowSessionPersonneDocument=mysqli_fetch_array($resultSessionPersonneDocument);
			$Id_Document=$rowSessionPersonneDocument['Id_Document'];
		}
	}
	
	//Ajout des informations dans session personne qualification 
	$req="UPDATE form_session_personne_document
		SET Id_LangueDocument=".$Id_LangueDocument." ,
		DateHeureCreation='".date('Y-m-d H:i:s')."',
		Id_Repondeur=0,
		Id_Ouvreur=0,
		DateHeureOuverture=0,
		DateHeureFermeture=0,
		DateHeureRepondeur=0
		WHERE Id=".$Id_SessionPersonneDocument;
	$resultUpdate=mysqli_query($bdd,$req);
	
	//Suppression des anciennes données si existantes dans form_session_personne_document_question_reponse avant d'en remettre
	$req="UPDATE form_session_personne_document_question_reponse 
		SET Suppr=1		
		WHERE Id_Session_Personne_Document=".$Id_SessionPersonneDocument;
	$resultUpdate=mysqli_query($bdd,$req);
	
	//Ajout des nouvelles données si Id_Document > 0 
	if($Id_Document>0){
		//Récupération des questions
		$req="SELECT form_document_langue_question.Id 
			FROM form_document_langue_question 
			LEFT JOIN form_document_langue 
			ON form_document_langue_question.Id_Document_Langue=form_document_langue.Id
			WHERE form_document_langue_question.Suppr=0 
			AND form_document_langue.Suppr=0
			AND form_document_langue.Id_Langue=".$Id_LangueDocument." 
			AND form_document_langue.Id_Document=".$Id_Document;
		$resultQuestion=mysqli_query($bdd,$req);
		$nbQuestionLangue=mysqli_num_rows($resultQuestion);
		$tab_Question = array();
		$nb=0;
		if($nbQuestionLangue>0){
			while($rowQuestion=mysqli_fetch_array($resultQuestion)){
				$tab_Question[$nb]=$rowQuestion['Id'];
				$nb++;
			}
		}
			
		//Ajout dans la table form_session_personne_qualification_question
		foreach ($tab_Question as $Id_Document_Langue_Question){
			$req="INSERT INTO form_session_personne_document_question_reponse (Id_Session_Personne_Document,Id_Document_Langue_Question) 
				VALUES (".$Id_SessionPersonneDocument.",".$Id_Document_Langue_Question.") ";
			$resultInsert=mysqli_query($bdd,$req);
			
		}
	}
}

function maj_Langue_SessionPersonneDocumentSansSessionPersonne($Id_SessionPersonneDocument,$Id_LangueDocument){
	global $bdd;
	$Id_Document=0;

	//Rechercher les informations par défaut
	$req="SELECT DISTINCT 
		form_session_personne_document.Id_Document 
		FROM form_session_personne_document
		WHERE form_session_personne_document.Id=".$Id_SessionPersonneDocument;
	$resultSessionPersonneDocument=getRessource($req);
	$nbSessionPersonneDocument=mysqli_num_rows($resultSessionPersonneDocument);
	if($nbSessionPersonneDocument>0){
		$rowSessionPersonneDocument=mysqli_fetch_array($resultSessionPersonneDocument);
		$Id_Document=$rowSessionPersonneDocument['Id_Document'];
	}
	
	//Ajout des informations dans session personne qualification 
	$req="UPDATE form_session_personne_document
		SET Id_LangueDocument=".$Id_LangueDocument." ,
		DateHeureCreation='".date('Y-m-d H:i:s')."',
		Id_Repondeur=0,
		Id_Ouvreur=0,
		DateHeureOuverture=0,
		DateHeureFermeture=0,
		DateHeureRepondeur=0
		WHERE Id=".$Id_SessionPersonneDocument;
	$resultUpdate=mysqli_query($bdd,$req);
	
	//Suppression des anciennes données si existantes dans form_session_personne_document_question_reponse avant d'en remettre
	$req="UPDATE form_session_personne_document_question_reponse 
		SET Suppr=1		
		WHERE Id_Session_Personne_Document=".$Id_SessionPersonneDocument;
	$resultUpdate=mysqli_query($bdd,$req);
	
	//Ajout des nouvelles données si Id_Document > 0 
	if($Id_Document>0){
		//Récupération des questions
		$req="SELECT form_document_langue_question.Id 
			FROM form_document_langue_question 
			LEFT JOIN form_document_langue 
			ON form_document_langue_question.Id_Document_Langue=form_document_langue.Id
			WHERE form_document_langue_question.Suppr=0 
			AND form_document_langue.Suppr=0
			AND form_document_langue.Id_Langue=".$Id_LangueDocument." 
			AND form_document_langue.Id_Document=".$Id_Document;
		$resultQuestion=mysqli_query($bdd,$req);
		$nbQuestionLangue=mysqli_num_rows($resultQuestion);
		$tab_Question = array();
		$nb=0;
		if($nbQuestionLangue>0){
			while($rowQuestion=mysqli_fetch_array($resultQuestion)){
				$tab_Question[$nb]=$rowQuestion['Id'];
				$nb++;
			}
		}
			
		//Ajout dans la table form_session_personne_qualification_question
		foreach ($tab_Question as $Id_Document_Langue_Question){
			$req="INSERT INTO form_session_personne_document_question_reponse (Id_Session_Personne_Document,Id_Document_Langue_Question) 
				VALUES (".$Id_SessionPersonneDocument.",".$Id_Document_Langue_Question.") ";
			$resultInsert=mysqli_query($bdd,$req);
		}
	}
}
?>