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
			for(y=0;y<document.getElementById('Id_Personnes_A_Former').length;y++){document.getElementById('Id_Personnes_A_Former').options[y].selected = true;}
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
		if(isset($_POST['Id_Formation'])){
			$tabPresta=explode("_",$_POST['Id_Prestation']);
			$IdPrestation=$tabPresta[0];
			$IdPole=$tabPresta[1];
			$Message="";
			$Personne="";
			//Qualification liées à la formation
			$ReqQualifFormation="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$_POST['Id_Formation']." AND Suppr=0 AND Masquer=0 ";
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
					AND new_competences_relation.Id_Personne IN (".implode(",",$_POST['Id_Personnes_A_Former']).")
					AND new_competences_relation.Suppr=0 
				ORDER BY
					new_competences_relation.Id_Qualification_Parrainage ASC,
					new_competences_relation.Date_QCM DESC,
					new_competences_relation.Date_Debut DESC";
			$ResultQualifsValides=mysqli_query($bdd,$ReqQualifsValides);
			$NbQualifsValides=mysqli_num_rows($ResultQualifsValides);
			
			//Inscriptions aux sessions de formations pour les personnes prévues en formation
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
					AND form_session_personne.Id_Personne IN (".implode(",",$_POST['Id_Personnes_A_Former']).")";
			$ResultFormationInscrit=mysqli_query($bdd,$ReqFormationInscrit);
			$NbFormationInscrit=mysqli_num_rows($ResultFormationInscrit);
			
			//Boucle pour faire les INSERT dans la table des besoins et dans les qualifications
			$Valide=1;
			$Id_Valideur=0;
			if(DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteResponsableRH,$IdPosteResponsableHSE)))){$Valide=1;$Id_Valideur=$IdPersonneConnectee;}
			foreach($_POST['Id_Personnes_A_Former'] as $value)
			{
				//Vérifier si la personne n'a pas déjà un B 
				if(Get_NbBesoinExistant($value, $_POST['Id_Formation'])==0){
					$Motif="Nouveau";
					if($_POST['formationR']==1){$Motif="Renouvellement";}
					
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
								$_POST['Id_Formation'].",".
								$value.",'".$DateJour."',
								'".$Motif."',
								'".addslashes($_POST['Commentaire'])."',".
								$Valide.",
								".$Id_Valideur.",".
								$IdPersonneConnectee.",".
								"'".$DateJour."'
							)";
					$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
					$ID_BESOIN=mysqli_insert_id($bdd);
					
					//Vérification si la personne a déjà une inscription à la même formation
					if($NbFormationInscrit>0)
					{
						mysqli_data_seek($ResultFormationInscrit,0);
						while($RowFormationInscrit=mysqli_fetch_array($ResultFormationInscrit))
						{
							if($RowFormationInscrit['ID_FORMATION']==$_POST['Id_Formation'] && $RowFormationInscrit['ID_PERSONNE']==$value)
							{
								$Message.=$RowFormationInscrit['NOMPRENOM']." est déjà inscrit à la formation. Veillez à vérifier la réelle nécessité du besoin en formation.<br>";
							}
						}
					}
					
					//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
					if($NbQualifFormation>0)
					{
						mysqli_data_seek($ResultQualifFormation,0);
						$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
						while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
						{
							$ReqInsertBesoinGPEC.="(";
							$ReqInsertBesoinGPEC.=$value;
							$ReqInsertBesoinGPEC.=",'Qualification'";
							$ReqInsertBesoinGPEC.=",".$RowQualifFormation['Id_Qualification'];
							$ReqInsertBesoinGPEC.=",'B'";
							$ReqInsertBesoinGPEC.=",0";
							$ReqInsertBesoinGPEC.=",".$ID_BESOIN;
							$ReqInsertBesoinGPEC.="),";
							
							//Vérification si la personne a déjà des qualifications en cours de validité pour cette qualification
							if($NbQualifsValides>0)
							{
								mysqli_data_seek($ResultQualifsValides,0);
								while($RowQualifsValides=mysqli_fetch_array($ResultQualifsValides))
								{
									if($RowQualifsValides['ID_QUALIFICATION']==$RowQualifFormation['Id_Qualification'] && $RowQualifsValides['ID_PERSONNE']==$value)
									{
										$Message.=$RowQualifsValides['NOMPRENOM']." a la qualification ".$RowQualifsValides['LIBELLE']." encore valide. Veillez à vérifier la réelle nécessité du besoin en formation.<br>";
									}
								}
							}
						}
						$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
						$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
					}
					
					$req="SELECT Id_Metier, 
						(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
						FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$value;
					$ResultMetier=mysqli_query($bdd,$req);
					$NbMetier=mysqli_num_rows($ResultMetier);
					
					//Vérifier si ce besoin existe dans la table form_prestation_metier_formation 
					if($NbMetier>0){
						$reqExiste="
							SELECT Id 
							FROM form_prestation_metier_formation
							WHERE Id_Prestation=".$Id_Prestation." 
							AND Id_Pole=".$IdPole." 
							AND Id_Formation=".$_POST['Id_Formation']." 
							AND Suppr=0 
							AND Id_Metier IN (SELECT Id_Metier FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$value." ) ";
						
						$req="
							SELECT Id_Metier, 
							(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
							FROM new_competences_personne_metier WHERE Futur=1 AND Id_Personne=".$value;
						$ResultMetier=mysqli_query($bdd,$req);
					}
					else{
						$reqExiste="
							SELECT Id 
							FROM form_prestation_metier_formation
							WHERE Id_Prestation=".$IdPrestation." 
							AND Id_Pole=".$IdPole." 
							AND Id_Formation=".$_POST['Id_Formation']." 
							AND Suppr=0 
							AND Id_Metier IN (SELECT Id_Metier FROM new_competences_personne_metier WHERE Futur=0 AND Id_Personne=".$value." ) ";
					
						$req="
							SELECT Id_Metier, 
							(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
							FROM new_competences_personne_metier WHERE Futur=0 AND Id_Personne=".$value;
						$ResultMetier=mysqli_query($bdd,$req);
					}
					$ResultExiste=mysqli_query($bdd,$reqExiste);
					$NbExiste=mysqli_num_rows($ResultExiste);
					if($NbExiste==0){
						$reqPers="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$value;
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
				
				if($_POST['formationR']==1){
				$reqForm="
					SELECT LibelleRecyclage AS Libelle
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
					AND Id_Formation=".$_POST['Id_Formation'];
				}
				else{
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
					AND Id_Formation=".$_POST['Id_Formation'];
				}
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
					WHERE Id_Formation=".$_POST['Id_Formation']." 
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
					if(mail($Emails,$Objet,$MessageMail,$Headers,'-f qualipso@aaa-aero.com')){echo "Un message a été envoyé à ".$Emails."\n";}
					else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
				}
			}
			//En fonction des besoins demandées et des qualifications existantes, affichage du message
			if($Message!=""){echo "<script>opener.location.reload();</script>";}
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}
?>
<form id="formulaire" method="POST" action="Ajout_Besoin_Formation.php" onSubmit="return VerifChamps();">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td><input type="hidden" name="oldPlat" value="<?php if($_POST){echo $_POST['plateforme'];}else{if(isset($_GET['Id_Plateforme'])){echo $_GET['Id_Plateforme'];}} ?>" /></td>
		</tr>
		<tr>
			<td><input type="hidden" name="oldTypeForm" value="<?php if($_POST){echo $_POST['Id_TypeFormation'];}else{if(isset($_GET['Id_TypeFormation'])){echo $_GET['Id_TypeFormation'];}} ?>" /></td>
		</tr>
		<tr>
			<td class="Libelle" style="width:20%;">
				<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> :
			</td>
		</tr>
		<tr>
			<td style="width:80%;" colspan="3">
				<select name="plateforme" id="plateforme" style="width:100px;" onchange="submit()">
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
		<tr>
			<td  class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Initiale / Recyclage";}else{echo "Initial / Recycling";} ?> : </td>
		</tr>
		<tr>
			<td class="Libelle">
				&nbsp;<select name="formationR" id="formationR" onchange="submit()">
					<?php
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF)){
						if($LangueAffichage=="FR"){
							$Tableau=array('Initiale|0','Recyclage|1');
						}
						else{
							$Tableau=array('Initial|0','Recycling|1');
						}
					}
					else{
						if($LangueAffichage=="FR"){
							$Tableau=array('Initiale|0');
						}
						else{
							$Tableau=array('Initial|0');
						}

					}
					$iniRec=0;
					if($_POST){$iniRec=$_POST['formationR'];}
					
					foreach($Tableau as $indice => $valeur)
					{
						$valeur=explode("|",$valeur);
						echo "<option value='".$valeur[1]."' ";
						if($iniRec==$valeur[1]){echo "selected";}
						echo ">".$valeur[0]."</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";}?> : </td>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
		</tr>
		<tr>
			<td>
				<select name="Id_TypeFormation" id="Id_TypeFormation" onchange="submit()">
					<?php
					$nbForm=0;
					$TypeForm=0;
					if($_GET){
						if(isset($_GET['Id_TypeFormation'])){$TypeForm=$_GET['Id_TypeFormation'];}
					}
					else{$TypeForm=$_POST['Id_TypeFormation'];}
					$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 AND Id<>1 ORDER BY Libelle ASC");
					while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation)){
						$selected="";
						if($_POST){
							if($TypeForm==$rowTypeFormation['Id']){$selected="selected";}
						}
						else{
							if(isset($_GET['Id_TypeFormation'])){
								if($TypeForm==$rowTypeFormation['Id']){$selected="selected";}
							}
							if($TypeForm==0){$TypeForm=$rowTypeFormation['Id'];}
						}
						$nbForm++;
						echo "<option value='".$rowTypeFormation['Id']."' ".$selected.">".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
					}
					?>
				</select>
			</td>
			<td>
				<?php
					$Form=0;
					if($_GET){
						if(isset($_GET['Id_Formation'])){$Form=$_GET['Id_Formation'];}
					}
					else{
						if(($_POST['plateforme']==$_POST['oldPlat'] && $_POST['Id_TypeFormation']==$_POST['oldTypeForm'] && isset($_POST['Id_Formation'])) || ($_POST['oldTypeForm']=="" && $_POST['oldPlat']=="")){
							$Form=$_POST['Id_Formation'];
						}
					}
				?>
				<select name="Id_Formation" id="Id_Formation" style="width:200px;" onchange="submit()">
					<?php
						
						$req="
							SELECT form_formation.Id,
							(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme,
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
							AND form_formation.Id_TypeFormation=".$TypeForm."
							ORDER BY Libelle,LibelleRecyclage 
							";

						$resultGroupeFormation=mysqli_query($bdd,$req);
						$NbForm=mysqli_num_rows($resultGroupeFormation);
						$i=0;
						$nb=0;
						if($NbForm>0){
							while($rowGF=mysqli_fetch_array($resultGroupeFormation)){
								$selected="";
								if($_POST && isset($_POST['Id_Formation']) && $Form<>0){
									if($Form==$rowGF['Id']){$selected="selected";}
								}
								else{
									if(isset($_GET['Id_Formation'])){
										if($Form==$rowGF['Id']){$selected="selected";}
									}
									if($Form==0){$Form=$rowGF['Id'];}
								}
								$Organisme="";
								if($rowGF['Organisme']<>""){$Organisme=" (".$rowGF['Organisme'].")";}
								if($rowGF['Recyclage']==1 && $iniRec==1){
									echo "<option value='".$rowGF['Id']."' ".$selected.">".stripslashes($rowGF['LibelleRecyclage']).$Organisme."</option>";
								}
								else{
									echo "<option value='".$rowGF['Id']."' ".$selected.">".stripslashes($rowGF['Libelle']).$Organisme."</option>";
								}
							}
						}
						else{
							$Form=0;
							echo "<option value='0' selected></option>\n";
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
				echo "<select name='Id_Prestation' id='Id_Prestation' onChange='submit();'>";
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
					if($Presta==0){$Presta=$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole'];}
					echo "<option value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$selected.">".stripslashes($rowPrestation['Libelle']).stripslashes($rowPrestation['Pole'])."</option>\n";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		
		<tr class="TitreColsUsers">
			<td valign="top" class="Libelle" width="100%" colspan="2">
				<?php if($LangueAffichage=="FR"){echo "Cocher les personnes à former";}else{echo "Check people to train";}?> : <br>
				<?php
				//Liste des qualifications de cette formation 
				$reqQualif="
					SELECT DISTINCT Id_Qualification,Masquer,
					(SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
					FROM form_formation_qualification 
					WHERE Id_Formation=".$Form." 
					AND Suppr=0";
				$resultQualif=mysqli_query($bdd,$reqQualif);
				$NbQualif=mysqli_num_rows($resultQualif);
				
				//Liste des formations compétences de cette formation 
				$reqFormationCompetence="
					SELECT DISTINCT Id_FormationCompetence,
					(SELECT Libelle FROM new_competences_formation WHERE new_competences_formation.Id=Id_FormationCompetence) AS FormationCompetence 
					FROM form_formation_formationcompetence
					WHERE Id_Formation=".$Form." 
					AND Suppr=0";
				$resultFormationCompetence=mysqli_query($bdd,$reqFormationCompetence);
				$NbFormationCompetence=mysqli_num_rows($resultFormationCompetence);
				
				//Personnes présentes par prestation à cette date
				$reqPersonnes="
					SELECT
						DISTINCT new_competences_personne_prestation.Id_Personne,
						CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
					FROM
						new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
					WHERE
						new_competences_personne_prestation.Date_Fin>='".$DateJour."' ";
				if($Presta==0){
						$reqPersonnes.="AND new_competences_personne_prestation.Id_Prestation=".$Presta." ";
				}
				else{
					$tabPresta=explode("_",$Presta);
					$reqPersonnes.="AND new_competences_personne_prestation.Id_Prestation=".$tabPresta[0]." ";
					$reqPersonnes.="AND new_competences_personne_prestation.Id_Pole=".$tabPresta[1]." ";
				}
				$reqPersonnes.="ORDER BY Personne ASC;";
				$resultPersonnes=mysqli_query($bdd,$reqPersonnes);
				$NbPersonne=mysqli_num_rows($resultPersonnes);
				?>
				<div id="listePresta" style="width:100%;height:200px;overflow:auto;">
				<?php
					if($NbPersonne>0){
						while($rowPersonnes=mysqli_fetch_array($resultPersonnes)){
							echo "<div>";
							$htmlQualif="";
							$NonAcquis=0;
							
							//Recherche si la personne n'a pas déjà un besoin en cours
							$reqBesoin="SELECT Id,Traite,Valide 
										FROM form_besoin
										WHERE Suppr=0
										AND Traite<3
										AND Valide>=0
										AND Id_Formation=".$Form."
										AND Id_Personne=".$rowPersonnes['Id_Personne']." ";
							$resultBesoin=mysqli_query($bdd,$reqBesoin);
							$nbBesoin=mysqli_num_rows($resultBesoin);
							$EnCoursAcquisition=0;
							if($nbBesoin>0){
								$rowBesoin=mysqli_fetch_array($resultBesoin);
								if($LangueAffichage=="FR"){
									if($rowBesoin['Traite']==0 && $rowBesoin['Valide']==1){$htmlQualif="<font color='green'>Besoin émis</font>";$EnCoursAcquisition=1;}
									elseif($rowBesoin['Traite']==0 && $rowBesoin['Valide']==0){$htmlQualif="<font color='green'>Besoin à confirmer</font>";$EnCoursAcquisition=1;}
									elseif($rowBesoin['Traite']==1){$htmlQualif="<font color='green'>Pré-inscrit</font>";$EnCoursAcquisition=1;}
									elseif($rowBesoin['Traite']==2){
										$reqBesoin="SELECT DateSession 
													FROM form_session_date 
													WHERE Suppr=0 
													AND Id_Session IN (SELECT Id_Session FROM form_session_personne WHERE Suppr=0 AND Id_Besoin=".$rowBesoin['Id'].")
													ORDER BY DateSession ASC ";
										$resultB=mysqli_query($bdd,$reqBesoin);
										$nbB=mysqli_num_rows($resultB);
										$date="";
										if($nbB>0){
											$rowB=mysqli_fetch_array($resultB);
											$date="(".AfficheDateJJ_MM_AAAA($rowB['DateSession']).")";
										}
										$htmlQualif="<font color='green'>Déjà inscrit ".$date."</font>";
										$EnCoursAcquisition=1;
									}
								}
								else{
									if($rowBesoin['Traite']==0 && $rowBesoin['Valide']==1){$htmlQualif="<font color='green'>Need issued</font>";$EnCoursAcquisition=1;}
									elseif($rowBesoin['Traite']==0 && $rowBesoin['Valide']==0){$htmlQualif="<font color='green'>Need to confirm</font>";$EnCoursAcquisition=1;}
									elseif($rowBesoin['Traite']==1){$htmlQualif="<font color='green'>Pre-registered</font>";$EnCoursAcquisition=1;}
									elseif($rowBesoin['Traite']==2){$htmlQualif="<font color='green'>Already registered</font>";$EnCoursAcquisition=1;}
								}
							}
							$QualifMasque=0;
							if($NbQualif>0){
								mysqli_data_seek($resultQualif,0);
								while($rowQualif=mysqli_fetch_array($resultQualif)){
									$htmlQualif.= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;".$rowQualif['Qualif'];
									//Recherche de la date de peremption de la qualification 
									$req="
										SELECT
											new_competences_relation.Id,
											new_competences_relation.Date_Debut,
											new_competences_relation.Date_Fin,
											new_competences_relation.Date_QCM,
											new_competences_relation.Id_Qualification_Parrainage, 
											new_competences_relation.Sans_Fin,
											new_competences_relation.Evaluation,
											new_competences_qualification.Duree_Validite
										FROM new_competences_relation
										LEFT JOIN new_competences_qualification
										ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
										WHERE
											new_competences_relation.Id_Personne=".$rowPersonnes['Id_Personne']."
											AND new_competences_relation.Type='Qualification'
											AND new_competences_relation.Visible=0
											AND new_competences_relation.Suppr=0
											AND new_competences_relation.Evaluation<>''
											AND new_competences_relation.Evaluation<>'B'
											AND new_competences_relation.Id_Qualification_Parrainage=".$rowQualif['Id_Qualification']."
										ORDER BY
											new_competences_relation.Date_QCM DESC";
									$resultQualifPersonne=mysqli_query($bdd,$req);
									$nbQualifPersonne=mysqli_num_rows($resultQualifPersonne);
									$dateFin="";
									$lettre="";
									$dateQCM="";
									if($nbQualifPersonne>0){
										while($rowQualifPersonne=mysqli_fetch_array($resultQualifPersonne)){
											if($rowQualifPersonne['Duree_Validite']==0){
												if($LangueAffichage=="FR"){
													$dateFin="Sans limite";
												}
												else{
													$dateFin="Illimitable";
												}
											}
											elseif($rowQualifPersonne['Date_Fin']>$dateFin){
												$dateFin=$rowQualifPersonne['Date_Fin'];
											}
											if($rowQualifPersonne['Date_QCM']>$dateQCM){
												$dateQCM=$rowQualifPersonne['Date_QCM'];
												$lettre=$rowQualifPersonne['Evaluation'];
											}
										}
									}
									if($dateFin=="Sans limite" || $dateFin=="Illimitable"){$htmlQualif.= " <font color='green'>(".$dateFin.")</font>";if($rowQualif['Masquer']==1){$QualifMasque=1;}}
									elseif($dateFin<>"" && $dateFin>"0001-01-01"){
										if($dateFin>=date('Y-m-d')){
											$htmlQualif.= " <font color='green'>(".AfficheDateJJ_MM_AAAA($dateFin).")</font>";
											if($rowQualif['Masquer']==1){$QualifMasque=1;}
										}
										else{
											if($rowQualif['Masquer']==0){$NonAcquis=1;}
											$htmlQualif.= " <font color='red'>(".AfficheDateJJ_MM_AAAA($dateFin).")</font>";
										}
									}
									else{
										if($lettre<>""){
											if($LangueAffichage=="FR"){
												$htmlQualif.= " <font color='orange'>(Lettre : ".$lettre." Date : ".AfficheDateJJ_MM_AAAA($dateQCM).")</font>";
											}
											else{
												$htmlQualif.= " <font color='orange'>(Letter : ".$lettre." Date : ".AfficheDateJJ_MM_AAAA($dateQCM).")</font>";
											}
										}
										else{
											if($LangueAffichage=="FR"){
												if($rowQualif['Masquer']==0){$NonAcquis=1;}
												$htmlQualif.= " <font color='red'>(Non acquise)</font>";
											}
											else{
												if($rowQualif['Masquer']==0){$NonAcquis=1;}
												$htmlQualif.= " <font color='red'>(Not acquired)</font>";
											}
										}
									}
								}
							}
							if($NbFormationCompetence>0){
								mysqli_data_seek($resultFormationCompetence,0);
								while($rowFormationCompetence=mysqli_fetch_array($resultFormationCompetence)){
									$htmlQualif.= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;".$rowFormationCompetence['FormationCompetence'];
									//Recherche de la date de passage de la formation  
									$req="
										SELECT
											Id,
											Date,
											Type
										FROM new_competences_personne_formation
										WHERE
											Id_Personne=".$rowPersonnes['Id_Personne']."
											AND Id_Formation=".$rowFormationCompetence['Id_FormationCompetence']."
										ORDER BY
											Date DESC";
									$resultFormationPersonne=mysqli_query($bdd,$req);
									$nbFormationPersonne=mysqli_num_rows($resultFormationPersonne);
									$dateForm="";
									if($nbFormationPersonne>0){
										while($rowFormationPersonne=mysqli_fetch_array($resultFormationPersonne)){
											if($rowFormationPersonne['Date']>$dateForm){
												$dateForm=$rowFormationPersonne['Date'];
											}
										}
									}
									if($dateForm<>""){$htmlQualif.= " <font color='green'>(".$dateForm.")</font>";}
									else{
										if($LangueAffichage=="FR"){
											$NonAcquis=1;
											$htmlQualif.= " <font color='red'>(Non réalisée)</font>";
										}
										else{
											$NonAcquis=1;
											$htmlQualif.= " <font color='red'>(Not carried out)</font>";
										}
									}
								}
							}
							if(($EnCoursAcquisition==0 && $NonAcquis==1 && $QualifMasque==0) || DroitsFormationPlateforme($TableauIdPostesAF_RF) || DroitsFormationPrestation($TableauIdPostesCQ)){
								echo "<input class='check' type='checkbox' name='Id_Personnes_A_Former[]' value='".$rowPersonnes['Id_Personne']."'>".$rowPersonnes['Personne']."&nbsp;".$htmlQualif;
							}
							else{
								echo "&#x204E;&nbsp;&nbsp;".$rowPersonnes['Personne']."&nbsp;".$htmlQualif;
							}
							echo "</div>";
						}
					}
				?>
				</div>
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

if($_GET){
	if($_GET['Mode']=="Suppr"){
		//MODE SUPPRESSION
		//----------------
		$ReqSupprBesoin="
			UPDATE
				form_besoin
			SET
				Suppr=1,
				Id_Personne_MAJ=".$IdPersonneConnectee.",
				Date_MAJ='".date('Y-m-d')."',
				Motif_Suppr='Depuis la liste des besoins'
			WHERE
				Id=".$_GET['Id'];
		$ResultSupprBesoin=mysqli_query($bdd,$ReqSupprBesoin);
		
		//Suppression des qualifications créées dans la gestion des compétences suite au besoin généré
		$ReqSupprRelation="
			UPDATE
				new_competences_relation
			SET
				Suppr=1
			WHERE
				Id_Besoin=".$_GET['Id'];
		$ResultSupprRelation=mysqli_query($bdd,$ReqSupprRelation);
		echo "<script>FermerEtRecharger();</script>";
	}
}
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>