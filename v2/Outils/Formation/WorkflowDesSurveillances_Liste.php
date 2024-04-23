<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function valider(id)
	{
		var w=window.open("WorkflowDesSurveillances_Valider.php?Id="+id, "PageQCMValider", "width=500,height=350");
		w.focus();
	}
	function ignorer(id)
	{
		var w=window.open("WorkflowDesSurveillances_Ignorer.php?Id="+id, "PageQCMValider", "width=500,height=350");
		w.focus();
	}
	function sensibilise(id)
	{
		var w=window.open("WorkflowDesSurveillances_Sensibilise.php?Id="+id, "PageQCMValider", "width=900,height=350");
		w.focus();
	}
	function ajouterNote(id)
	{
		var w=window.open("WorkflowDesSurveillances_AjouterNote.php?Id="+id, "", "width=500,height=350");
		w.focus();			
	}
	function QCM_Web(Id)
	{
		var w= window.open("QCM_Web_v3.php?Page=WorkflowDesSurveillances_Liste&Id_Session_Personne_Qualification="+Id,"PageQCMWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function Excel(){
		var w=window.open("Excel_Surveillances.php?Prestation="+document.getElementById('Prestation').value+"&Personne="+document.getElementById('Personne').value+"&Qualif="+document.getElementById('Qualif').value+"&Statut="+document.getElementById('Statut').value+"","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function OuvrirFermerAcces(Id,checked)
	{
		formulaire.Ouverture.value=Id;
		formulaire.BOuverture.value=checked;
		formulaire.submit();
	}

	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
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
	}
</script>
<?php
if($_POST)
{
	$_SESSION['FiltreFormSurveillance_Plateforme']=$_POST['Plateforme'];
	$_SESSION['FiltreFormSurveillance_Prestation']=$_POST['Prestation'];
	$_SESSION['FiltreFormSurveillance_Personne']=$_POST['Personne'];
	$_SESSION['FiltreFormSurveillance_Statut']=$_POST['Statut'];
	$_SESSION['FiltreFormSurveillance_Qualification']=$_POST['Qualif'];
	
	if($_POST['Ouverture']<>"")
	{
		if($_POST['BOuverture']=="checked"){fermerAccesQCM($_POST['Ouverture']);}
		else{ouvrirAccesQCM($_POST['Ouverture']);}
	}
}

//Fonction de filtrage
global $laPrestation;
global $laPersonne;
global $laQualification;
global $leStatut;

$laPlateforme = $_SESSION['FiltreFormSurveillance_Plateforme'];
$laPrestation = $_SESSION['FiltreFormSurveillance_Prestation'];
$laPersonne = $_SESSION['FiltreFormSurveillance_Personne'];
$laQualification = $_SESSION['FiltreFormSurveillance_Qualification'];
$leStatut = $_SESSION['FiltreFormSurveillance_Statut'];

if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Personne")
	{
		$_SESSION['TriFormSurveillance_General']= str_replace("Personne ASC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Personne DESC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Personne ASC","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Personne DESC","",$_SESSION['TriFormSurveillance_General']);
		if($_SESSION['TriFormSurveillance_Personne']==""){$_SESSION['TriFormSurveillance_Personne']="ASC";$_SESSION['TriFormSurveillance_General'].= "Personne ".$_SESSION['TriFormSurveillance_Personne'].",";}
		elseif($_SESSION['TriFormSurveillance_Personne']=="ASC"){$_SESSION['TriFormSurveillance_Personne']="DESC";$_SESSION['TriFormSurveillance_General'].= "Personne ".$_SESSION['TriFormSurveillance_Personne'].",";}
		else{$_SESSION['TriFormSurveillance_Personne']="";}
	}
	if($_GET['Tri']=="Prestation")
	{
		$_SESSION['TriFormSurveillance_General']= str_replace("Prestation ASC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Prestation DESC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Prestation ASC","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Prestation DESC","",$_SESSION['TriFormSurveillance_General']);
		if($_SESSION['TriFormSurveillance_Prestation']==""){$_SESSION['TriFormSurveillance_Prestation']="ASC";$_SESSION['TriFormSurveillance_General'].= "Prestation ".$_SESSION['TriFormSurveillance_Prestation'].",";}
		elseif($_SESSION['TriFormSurveillance_Prestation']=="ASC"){$_SESSION['TriFormSurveillance_Prestation']="DESC";$_SESSION['TriFormSurveillance_General'].= "Prestation ".$_SESSION['TriFormSurveillance_Prestation'].",";}
		else{$_SESSION['TriFormSurveillance_Prestation']="";}
	}
	if($_GET['Tri']=="Qualification")
	{
		$_SESSION['TriFormSurveillance_General']= str_replace("Qualification ASC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Qualification DESC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Qualification ASC","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Qualification DESC","",$_SESSION['TriFormSurveillance_General']);
		if($_SESSION['TriFormSurveillance_Qualification']==""){$_SESSION['TriFormSurveillance_Qualification']="ASC";$_SESSION['TriFormSurveillance_General'].= "Qualification ".$_SESSION['TriFormSurveillance_Qualification'].",";}
		elseif($_SESSION['TriFormSurveillance_Qualification']=="ASC"){$_SESSION['TriFormSurveillance_Qualification']="DESC";$_SESSION['TriFormSurveillance_General'].= "Qualification ".$_SESSION['TriFormSurveillance_Qualification'].",";}
		else{$_SESSION['TriFormSurveillance_Qualification']="";}
	}
	if($_GET['Tri']=="DateDebut")
	{
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Debut ASC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Debut DESC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Debut ASC","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Debut DESC","",$_SESSION['TriFormSurveillance_General']);
		if($_SESSION['TriFormSurveillance_DateDebut']==""){$_SESSION['TriFormSurveillance_DateDebut']="ASC";$_SESSION['TriFormSurveillance_General'].= "Date_Debut ".$_SESSION['TriFormSurveillance_DateDebut'].",";}
		elseif($_SESSION['TriFormSurveillance_DateDebut']=="ASC"){$_SESSION['TriFormSurveillance_DateDebut']="DESC";$_SESSION['TriFormSurveillance_General'].= "Date_Debut ".$_SESSION['TriFormSurveillance_DateDebut'].",";}
		else{$_SESSION['TriFormSurveillance_DateDebut']="";}
	}
	if($_GET['Tri']=="DateFin")
	{
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Fin ASC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Fin DESC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Fin ASC","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Date_Fin DESC","",$_SESSION['TriFormSurveillance_General']);
		if($_SESSION['TriFormSurveillance_DateFin']==""){$_SESSION['TriFormSurveillance_DateFin']="ASC";$_SESSION['TriFormSurveillance_General'].= "Date_Fin ".$_SESSION['TriFormSurveillance_DateFin'].",";}
		elseif($_SESSION['TriFormSurveillance_DateFin']=="ASC"){$_SESSION['TriFormSurveillance_DateFin']="DESC";$_SESSION['TriFormSurveillance_General'].= "Date_Fin ".$_SESSION['TriFormSurveillance_DateFin'].",";}
		else{$_SESSION['TriFormSurveillance_DateFin']="";}
	}
	if($_GET['Tri']=="Statut")
	{
		$_SESSION['TriFormSurveillance_General']= str_replace("Statut ASC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Statut DESC,","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Statut ASC","",$_SESSION['TriFormSurveillance_General']);
		$_SESSION['TriFormSurveillance_General']= str_replace("Statut DESC","",$_SESSION['TriFormSurveillance_General']);
		if($_SESSION['TriFormSurveillance_Statut']==""){$_SESSION['TriFormSurveillance_Statut']="ASC";$_SESSION['TriFormSurveillance_General'].= "Statut ".$_SESSION['TriFormSurveillance_Statut'].",";}
		elseif($_SESSION['TriFormSurveillance_Statut']=="ASC"){$_SESSION['TriFormSurveillance_Statut']="DESC";$_SESSION['TriFormSurveillance_General'].= "Statut ".$_SESSION['TriFormSurveillance_Statut'].",";}
		else{$_SESSION['TriFormSurveillance_Statut']="";}
	}
}
if($_POST){
	if(isset($_POST['ValiderOuvrir'])){
		if(isset($_POST['CheckVO'])){
			if (is_array($_POST['CheckVO'])) {
				foreach($_POST['CheckVO'] as $value){
					//Valider et sélection du QCM par défaut 
					$Id_Relation=$value;
					$Id_SessionPersonneQualification=0;
					$Id_QCM=0;
					$Id_Langue=0;
					$Id_QCMLie=0;
					$Id_LangueQCMLie=0;
					
					if($Id_Relation<>""){
						$ReqRelation="
							SELECT
								Id_Qualification_Parrainage,
								(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Plateforme,
								Id_Personne
							FROM
								new_competences_relation
							WHERE
							
								new_competences_relation.Id = ".$Id_Relation;
						$ResultRelation=mysqli_query($bdd, $ReqRelation);
						$RowRelation=mysqli_fetch_array($ResultRelation);

						$req="SELECT DISTINCT 
							form_formation_qualification_qcm.Id_QCM,
							(SELECT DISTINCT 
								form_qcm_langue.Id_Langue
								FROM form_qcm_langue
								LEFT JOIN form_langue
								ON form_qcm_langue.Id_Langue=form_langue.Id
								WHERE form_qcm_langue.Id_QCM=form_formation_qualification_qcm.Id_QCM
								AND form_qcm_langue.Suppr=0 
								AND form_langue.Suppr=0 LIMIT 1) AS Id_Langue,
							form_qcm.Code AS QCM,
							form_qcm.Id_QCM_Lie,
							(SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie
							FROM form_formation_qualification_qcm
							LEFT JOIN form_qcm
							ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
							LEFT JOIN form_formation_qualification
							ON form_formation_qualification_qcm.Id_Formation_Qualification=form_formation_qualification.Id
							WHERE form_formation_qualification.Id_Qualification=".$RowRelation['Id_Qualification_Parrainage']."
							AND form_formation_qualification_qcm.Suppr=0 
							AND form_qcm.Suppr=0 
							AND form_formation_qualification.Suppr=0 ";
						$resultQCM=mysqli_query($bdd,$req);
						$nbQCM=mysqli_num_rows($resultQCM);

						if($nbQCM>0){
							while($rowQCM=mysqli_fetch_array($resultQCM)){
								if($Id_QCM==0){
									$Id_QCM=$rowQCM['Id_QCM'];
									$Id_Langue=$rowQCM['Id_Langue'];
									$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
								}
								elseif($Id_QCM==$rowQCM['Id_QCM']){
									$Id_Langue=$rowQCM['Id_Langue'];
									$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
								}
							}
						}
						if($Id_QCM>0 && $Id_Langue>0){

							Set_BesoinsDeSurveillance_Valider($Id_Relation, $Id_QCM, $Id_Langue,$Id_QCMLie,$Id_LangueQCMLie);
						}
						
						//Recuperer l'Id_SessionPersonneQualification
						$req="
							SELECT Id 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Relation=".$Id_Relation."
							";
						$resultSPQ=mysqli_query($bdd,$req);
						$nbSPQ=mysqli_num_rows($resultSPQ);
						if($nbSPQ>0){
							$rowSPQ=mysqli_fetch_array($resultSPQ);
							$Id_SessionPersonneQualification=$rowSPQ['Id'];
						}
						
						//Ouvrir
						if($Id_SessionPersonneQualification>0){
							ouvrirAccesQCM($Id_SessionPersonneQualification);
						}
					}
				}
			} 
			else {
				//Valider et sélection du QCM par défaut 
				$Id_Relation = $_POST['CheckVO'];
				$Id_SessionPersonneQualification=0;
				$Id_QCM=0;
				$Id_Langue=0;
				$Id_QCMLie=0;
				$Id_LangueQCMLie=0;
				
				if($Id_Relation<>""){
					$ReqRelation="
						SELECT
							Id_Qualification_Parrainage
						FROM
							new_competences_relation
						WHERE
						
							new_competences_relation.Id = ".$Id_Relation;
					$ResultRelation=mysqli_query($bdd, $ReqRelation);
					$RowRelation=mysqli_fetch_array($ResultRelation);

					$req="SELECT DISTINCT 
						form_formation_qualification_qcm.Id_QCM,
						form_formation_qualification_qcm.Id_Langue,
						form_qcm.Code AS QCM,
						form_qcm.Id_QCM_Lie,
						(SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie
						FROM form_formation_qualification_qcm
						LEFT JOIN form_qcm
						ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
						LEFT JOIN form_formation_qualification
						ON form_formation_qualification_qcm.Id_Formation_Qualification=form_formation_qualification.Id
						WHERE form_formation_qualification.Id_Qualification=".$RowRelation['Id_Qualification_Parrainage']."
						AND form_formation_qualification_qcm.Suppr=0 
						AND form_qcm.Suppr=0 
						AND form_formation_qualification.Suppr=0
					LIMIT 1";
					$resultQCM=mysqli_query($bdd,$req);
					$nbQCM=mysqli_num_rows($resultQCM);

					if($nbQCM>0){
						while($rowQCM=mysqli_fetch_array($resultQCM)){
							if($Id_QCM==0){
								$Id_QCM=$rowQCM['Id_QCM'];
								$Id_Langue=$rowQCM['Id_Langue'];
								$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
							}
							elseif($Id_QCM==$rowQCM['Id_QCM']){
								$Id_Langue=$rowQCM['Id_Langue'];
								$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
							}
						}
					}
					if($Id_QCM>0 && $Id_Langue>0){
						Set_BesoinsDeSurveillance_Valider($Id_Relation, $Id_QCM, $Id_Langue,$Id_QCMLie,$Id_LangueQCMLie);
					}
					
					//Recuperer l'Id_SessionPersonneQualification
					$req="
						SELECT Id 
						FROM form_session_personne_qualification 
						WHERE form_session_personne_qualification.Suppr=0 
						AND form_session_personne_qualification.Id_Relation=".$Id_Relation."
						";
					$resultSPQ=mysqli_query($bdd,$req);
					$nbSPQ=mysqli_num_rows($resultSPQ);
					if($nbSPQ>0){
						$rowSPQ=mysqli_fetch_array($resultSPQ);
						$Id_SessionPersonneQualification=$rowSPQ['Id'];
					}
					
					//Ouvrir
					if($Id_SessionPersonneQualification>0){
						ouvrirAccesQCM($Id_SessionPersonneQualification);
					}
				}
			}
		}
	}
}
?>
<form class="test" id="formulaire" method="POST" action="WorkflowDesSurveillances_Liste.php">
<input type="hidden" name="Ouverture" id="Ouverture" value="">
<input type="hidden" name="BOuverture" id="BOuverture" value="">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffffff;">
				<tr>
					<td width="4">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
						if($LangueAffichage=="FR"){echo "<img width='20px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='20px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a></td>";
					?>
					</td>
					<td class="TitrePage">
						<?php 
                            if($LangueAffichage=="FR")
                            	echo "Gestion des surveillances # Workflow des surveillances";
                            else
                            	echo "Monitoring management # Workflow monitoring";
                        ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr><td height="4"></td>
                <tr>
					<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
					<td width="20%">
						<select id="Plateforme" name="Plateforme" onchange="submit()">
							<?php
							$Plateforme=$_SESSION['FiltreFormSurveillance_Plateforme'];
							
							$reqPla="
								SELECT DISTINCT
									Id_Plateforme, 
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM
									new_competences_personne_poste_prestation
								LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE
									Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee."
								UNION 
								 SELECT DISTINCT
									Id_Plateforme, 
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM
									new_competences_personne_poste_plateforme
								WHERE
									Id_Poste IN (".$IdPosteFormateur.",".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee."
								ORDER BY
									Libelle";
							$resultPlateforme=mysqli_query($bdd,$reqPla);
							$nbFormation=mysqli_num_rows($resultPlateforme);
							if($nbFormation>0)
							{
								while($rowplateforme=mysqli_fetch_array($resultPlateforme))
								{
									$selected="";
									if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
									if($Plateforme==$rowplateforme['Id_Plateforme']){$selected="selected";}
									echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
								}
							}
							$_SESSION['FiltreFormSurveillance_Plateforme']=$Plateforme;
							?>
						</select>
					</td>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation / Pôle";}else{echo "Activity / Pole";}?></td>
					<td>
						<select id="Prestation" name="Prestation" onchange="submit()">
							<option value="" selected></option>
							<?php
							$Prestation=$_SESSION['FiltreFormSurveillance_Prestation'];
							

							$rqPrestation="SELECT Id AS Id_Prestation, 
								Id_Plateforme,
								LEFT(Libelle,7) AS Libelle,
								0 AS Id_Pole,
								'' AS Pole
								FROM new_competences_prestation 
								WHERE Id NOT IN (
									SELECT Id_Prestation
									FROM new_competences_pole
									WHERE Actif=0
								)
								AND new_competences_prestation.Active=0
								AND new_competences_prestation.Id_Plateforme=".$_SESSION['FiltreFormSurveillance_Plateforme']."
								AND (Id_Plateforme IN (
									SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Poste 
										IN (".$IdPosteFormateur.",".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee." 
								)
								OR 
									(SELECT COUNT(Id)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
										AND Id_Personne=".$IdPersonneConnectee." 
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									)>0
								)
								
								UNION
								
								SELECT Id_Prestation,
								new_competences_prestation.Id_Plateforme,
								LEFT(new_competences_prestation.Libelle,7) AS Libelle,
								new_competences_pole.Id AS Id_Pole,
								CONCAT(' - ',new_competences_pole.Libelle) AS Pole
								FROM new_competences_pole
								INNER JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								AND new_competences_pole.Actif=0
								AND new_competences_prestation.Active=0
								AND new_competences_prestation.Id_Plateforme=".$_SESSION['FiltreFormSurveillance_Plateforme']."
								AND (Id_Plateforme IN (
									SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Poste 
										IN (".$IdPosteFormateur.",".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee." 
								)
								OR 
									(SELECT COUNT(Id)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
										AND Id_Personne=".$IdPersonneConnectee." 
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									)>0
								)
								ORDER BY Libelle, Pole";
							$resultPresta=mysqli_query($bdd,$rqPrestation);
							$nbPresta=mysqli_num_rows($resultPresta);
							if($nbPresta>0)
							{
								while($rowPresta=mysqli_fetch_array($resultPresta))
								{
									$selected="";
									if($Prestation==$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']){$selected="selected";}
									echo "<option value='".$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']."' ".$selected.">".$rowPresta['Libelle'].$rowPresta['Pole']."</option>\n";
								}
							}
							$_SESSION['FiltreFormSurveillance_Prestation']=$Prestation;
							?>
						</select>
					</td>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
					<?php $Personne=$_SESSION['FiltreFormSurveillance_Personne'];?>
					<td><input style="width:200px" id="Personne" name="Personne" value="<?php echo $Personne;?>"></td>
					<td><input style='cursor:pointer;' class="Bouton" type="button" value="Filtrer" onclick="this.form.submit()"></td>
				</tr>
                <tr>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Qualification ou Catégorie";}else{echo "Qualification or Category";}?></td>
					<?php $Qualif=$_SESSION['FiltreFormSurveillance_Qualification'];?>
					<td><input style="width:200px" id="Qualif" name="Qualif" value="<?php echo $Qualif;?>"></td>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){ echo "Statut";}else{echo "Status";}?></td>
					<td>
						<select id="Statut" name="Statut" onchange="submit()">
						   <option value="" selected>EN COURS</option>
						   <?php
								$selected="";
								$Statut=$_SESSION['FiltreFormSurveillance_Statut'];
								if($Statut=="IGNORE"){$selected="selected";}
								echo "<option value='IGNORE' ".$selected.">IGNORE</option>";
								
								$selected="";
								$Statut=$_SESSION['FiltreFormSurveillance_Statut'];
								if($Statut=="VALIDE"){$selected="selected";}
								echo "<option value='VALIDE' ".$selected.">VALIDE</option>";
								
								$selected="";
								$Statut=$_SESSION['FiltreFormSurveillance_Statut'];
								if($Statut=="REFUSE"){$selected="selected";}
								echo "<option value='REFUSE' ".$selected.">REFUSE</option>";
								
								$selected="";
								$Statut=$_SESSION['FiltreFormSurveillance_Statut'];
								if($Statut=="ECHEC"){$selected="selected";}
								echo "<option value='ECHEC' ".$selected.">ECHEC</option>";
							?>
						</select>
					</td>
					<td>
						&nbsp;<a style='text-decoration:none;' href='javascript:Excel();'>
								<img src='../../Images/excel.gif' border='0' alt='Excel' title='Excel'>
							</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td valign="top" colspan="8" class="Libelle" <?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)==0){echo "style='display:none;'";} ?>>
						<?php if($LangueAffichage=="FR"){echo "CQP";}else{echo "CQP";}?> :<br>
								<?php
								
									$Id_CQP=$_SESSION['FiltreFormSurveillance_CQP'];
									if($_POST){
										$Id_CQP="";
										if(isset($_POST['Id_CQP'])){
											if (is_array($_POST['Id_CQP'])) {
												foreach($_POST['Id_CQP'] as $value){
													if($Id_CQP<>''){$Id_CQP.=",";}
												  $Id_CQP.=$value;
												}
											} else {
												$value = $_POST['Id_CQP'];
												$Id_CQP = $value;
											}
										}
									}
									$_SESSION['FiltreFormSurveillance_CQP']=$Id_CQP;
			
									$rqCQP="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE Id_Poste IN (".$IdPosteReferentQualiteProduit.")
									AND Id_Plateforme IN (
										".$Plateforme."
									)
									AND Id_Personne<>0
									ORDER BY Personne";
									
									$resultCQP=mysqli_query($bdd,$rqCQP);
									$Id_CQP=0;
									while($rowCQP=mysqli_fetch_array($resultCQP))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_CQP']) ? $_POST['Id_CQP'] : array();
											foreach($checkboxes as $value) {
												if($rowCQP['Id_Personne']==$value){$checked="checked";}
											}
										}
										else{
											$checkboxes = explode(',',$_SESSION['FiltreFormSurveillance_CQP']);
											foreach($checkboxes as $value) {
												if($rowCQP['Id_Personne']==$value){$checked="checked";}
											}
										}
										echo "<input type='checkbox' class='checkCQP' name='Id_CQP[]' Id='Id_CQP[]' value='".$rowCQP['Id_Personne']."' ".$checked.">".$rowCQP['Personne'];
									}
								?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td height="8"></td></tr>
		<tr>
		<td align="center" style="font-size:14px;">
			<?php 	
				
					$ListePersonneSelonProfilConnecte="";
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS))
					{
						$ListePersonneSelonProfilConnecte.="
								SELECT
									Id_Personne 
								FROM
									new_competences_personne_prestation
								LEFT JOIN new_competences_prestation 
									ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE
									Date_Fin>='".date('Y-m-d')."'
									AND Id_Plateforme IN
									(
										SELECT Id_Plateforme
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS).")
									)
							";
						
					}
					else
					{
						$ListePersonneSelonProfilConnecte.="
								SELECT
									Id_Personne  
								FROM
									new_competences_personne_prestation
								WHERE
									Date_Fin>='".date('Y-m-d')."'
									AND CONCAT(Id_Prestation,'_',Id_Pole) IN
									(
										SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
									)
							";
					}

					//Uniquement les spécials process
					$req="SELECT
							new_competences_relation.Id,
							(SELECT CONCAT (Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							new_competences_relation.Id_Personne,Sensibilisation,
							new_competences_qualification.Id AS Id_Qualif,
							new_competences_qualification.Libelle AS Qualification,
							(SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=new_competences_qualification.Id_Categorie_Qualification) AS Categorie,
							(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Prestation,
							(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Pole,
							(SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Prestation,
							(SELECT Id_Pole FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Pole,
							new_competences_relation.Date_Debut,
							new_competences_relation.Date_Fin,IgnorerSurveillance,
							IF(IgnorerSurveillance=1,'IGNORE',new_competences_relation.Statut_Surveillance) AS Statut,
							(SELECT Id FROM form_session_personne_qualification WHERE form_session_personne_qualification.Suppr=0 AND form_session_personne_qualification.Id_Relation=new_competences_relation.Id LIMIT 1) AS Id_SessionPersonneQualification,
							(SELECT DateHeureOuverture FROM form_session_personne_qualification WHERE form_session_personne_qualification.Suppr=0 AND form_session_personne_qualification.Id_Relation=new_competences_relation.Id LIMIT 1) AS DateHeureOuverture,
							(SELECT DateHeureFermeture FROM form_session_personne_qualification WHERE form_session_personne_qualification.Suppr=0 AND form_session_personne_qualification.Id_Relation=new_competences_relation.Id LIMIT 1) AS DateHeureFermeture,
							ADDDATE(new_competences_relation.Date_Debut, INTERVAL new_competences_qualification.Periodicite_Surveillance MONTH) AS DateSurveillance
						FROM
							new_competences_relation,
							new_competences_qualification
						WHERE
							new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
							AND new_competences_relation.Suppr = 0
							AND (new_competences_relation.Date_Surveillance <= '0001-01-01'
							OR
							(new_competences_relation.Date_Surveillance > 0 AND new_competences_relation.Statut_Surveillance = 'ECHEC')
							)
							AND new_competences_relation.Date_Debut > '0001-01-01'
							AND ((new_competences_relation.Date_Fin>= '".date('Y-m-d')."' AND new_competences_relation.Statut_Surveillance<>'REFUSE') OR 
								(ADDDATE(new_competences_relation.Date_Debut, INTERVAL new_competences_qualification.Duree_Validite MONTH) >= '".date('Y-m-d')."' 
								AND new_competences_relation.Statut_Surveillance='REFUSE'
								)
								)
							AND ADDDATE(new_competences_relation.Date_Debut, INTERVAL new_competences_qualification.Periodicite_Surveillance MONTH)<='".date('Y-m-d',strtotime(date('Y-m-d')." +4 month"))."'
							AND new_competences_qualification.Periodicite_Surveillance > 0
							AND (SELECT Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=new_competences_qualification.Id_Categorie_Qualification)=2
							AND new_competences_relation.Id_Qualification_Parrainage IN
							(
								SELECT
									DISTINCT Id_Qualification
								FROM
									form_formation_qualification
								LEFT JOIN form_formation
								  ON form_formation_qualification.Id_Formation=form_formation.Id
								WHERE
									form_formation_qualification.Suppr = 0
									AND form_formation.Suppr = 0
									AND form_formation.Id_TypeFormation IN (1,3)
							) 
							AND new_competences_relation.Id_Personne IN
							("
								.$ListePersonneSelonProfilConnecte."
							)
							
					 ";
					if($_SESSION['FiltreFormSurveillance_Plateforme']<>"")
					{
						$req.="
							AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1) IN (".$_SESSION['FiltreFormSurveillance_Plateforme'].") 
							";
					}
					if($_SESSION['FiltreFormSurveillance_Prestation']<>"")
					{
						$req.=" AND (SELECT COUNT(Id) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') AND CONCAT(Id_Prestation,'_',Id_Pole)='".$_SESSION['FiltreFormSurveillance_Prestation']."')>0 ";
					}
					if($_SESSION['FiltreFormSurveillance_CQP']<>""){
						$req.="
								AND CONCAT((SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1),'_',(SELECT Id_Pole FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1)) 
									IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne IN (".$_SESSION['FiltreFormSurveillance_CQP'].")
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.")
									)
									";
					}
					
					if($_SESSION['FiltreFormSurveillance_Personne']<>""){$req.="AND (SELECT CONCAT (Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) LIKE '%".$_SESSION['FiltreFormSurveillance_Personne']."%' ";}
					if($_SESSION['FiltreFormSurveillance_Qualification']<>""){$req.="AND new_competences_qualification.Libelle LIKE '%".$_SESSION['FiltreFormSurveillance_Qualification']."%' ";}
					if($_SESSION['FiltreFormSurveillance_Statut']<>""){
						$req.="AND IF(IgnorerSurveillance=1,'IGNORE',new_competences_relation.Statut_Surveillance) LIKE '%".$_SESSION['FiltreFormSurveillance_Statut']."%' ";
					}
					else{
						$req.="AND IF(IgnorerSurveillance=1,'IGNORE',new_competences_relation.Statut_Surveillance) IN ('','VALIDE','ECHEC') ";
					}
					
					$val=50;
					
					$reqPartie2="";
					if($_SERVER['SERVER_NAME']=="127.0.0.1"){
						$reqPartie2=" LIMIT 500";
					}

					$resultSurveillance=mysqli_query($bdd,$req.$reqPartie2);
					$nbSurveillance=mysqli_num_rows($resultSurveillance);
					
					
		
					$nombreDePages=ceil($nbSurveillance/$val);
					if(isset($_GET['Page'])){$_SESSION['FORM_Surveillance_Page']=$_GET['Page'];}
					else{$_SESSION['FORM_Surveillance_Page']=0;}
		
		
					if($_SESSION['TriFormSurveillance_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriFormSurveillance_General'],0,-1);}
					$req2=" LIMIT ".($_SESSION['FORM_Surveillance_Page']*$val).",".$val;

					$resultSurveillance=mysqli_query($bdd,$req.$req2);
					$nbQualifs=mysqli_num_rows($resultSurveillance);
					
					if($_SESSION['FORM_Surveillance_Page']>1){echo "<b> <a style='color:#00599f;' href='WorkflowDesSurveillances_Liste.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['FORM_Surveillance_Page']<=5){$valeurDepart=1;}
					elseif($_SESSION['FORM_Surveillance_Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
					else{$valeurDepart=$_SESSION['FORM_Surveillance_Page']-5;}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
					{
						if($i<=$nombreDePages)
						{
							if($i==($_SESSION['FORM_Surveillance_Page']+1)){echo "<b> [ ".$i." ] </b>";}	
							else{echo "<b> <a style='color:#00599f;' href='WorkflowDesSurveillances_Liste.php?Page=".($i-1)."'>".$i."</a> </b>";}
						}
					}
					if($_SESSION['FORM_Surveillance_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='WorkflowDesSurveillances_Liste.php?Page=".($nombreDePages-1)."'>>></a> </b>";}

					?>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">

					<tr bgcolor='#2c8bb4'>
						<td class="EnTeteTableauCompetences" width="3%"></td>
						<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} if($_SESSION['TriFormSurveillance_Personne']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillance_Personne']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Prestation"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";} if($_SESSION['TriFormSurveillance_Prestation']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillance_Prestation']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="25%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Qualification"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";} if($_SESSION['TriFormSurveillance_Qualification']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillance_Qualification']=="ASC"){echo "&darr;";}?></a></td>
						
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=DateDebut"><?php if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";} if($_SESSION['TriFormSurveillance_DateDebut']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillance_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=DateFin"><?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";} if($_SESSION['TriFormSurveillance_DateFin']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillance_DateFin']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Statut"><?php if($LangueAffichage=="FR"){echo "Statut";}else{echo "Status";} if($_SESSION['TriFormSurveillance_Statut']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillance_Statut']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Sensibilisation";}else{echo "Sensitization";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Ignorer";}else{echo "Ignore";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "QCM";}else{echo "MCQ";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Ouverture";}else{echo "Opening";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='5%'>
							<input class="Bouton" type="submit" id="ValiderOuvrir" name="ValiderOuvrir" value="<?php if($LangueAffichage=="FR"){echo "Valider & Ouvrir";}else{echo "Validate & Open";}?>" />
							<br>
							<input type="checkbox" name="selectAllVO" id="selectAllVO" onclick="SelectionnerTout('VO')" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr><td height="4"></td></tr>

					<?php
					$Couleur="#EEEEEE";
					while($row = mysqli_fetch_array($resultSurveillance)){
							$lacouleur="";
							if($row['DateSurveillance']<date('Y-m-d')){
								$lacouleur="bgcolor='#f80a3f'";
							}
							elseif($row['DateSurveillance']<date('Y-m-d',strtotime(date('Y-m-d')." +2 month"))){
								$lacouleur="bgcolor='#fcb98a'";
							}
							$styleIgore="";
							if($row['IgnorerSurveillance']==1){
								$Couleur="#5847ff";
								$styleIgore="style='color:#000000;'";
							}
							$ligne = "
								<tr bgcolor='".$Couleur."' > ";
							
							if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
								$ligne.="<td><input class='Bouton' name='ReinitialiserMDP' size='10' type='Button' style='cursor:pointer;' value='MDP' onclick='javascript:OuvreFenetreIdentifiants(\"".$row['Id_Personne']."\")'></td>";
							}
							else{
								$ligne.="<td></td>";
							}
							$ligne.= "<td><a class='TableCompetences' ".$styleIgore." href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a></td>
									<td>".$row['Prestation']." ".$row['Pole']."</td>
									<td>".stripslashes($row['Qualification']." (".$row['Categorie'].")")."</td>
									<td ".$lacouleur." >".AfficheDateJJ_MM_AAAA($row['Date_Debut'])."</td>
									<td>".AfficheDateJJ_MM_AAAA($row['Date_Fin'])."</td>
									<td>".$row['Statut']."</td>
									";

								if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
									if($row['Sensibilisation']==1){
										$ligne .= "<td><a href='javascript:sensibilise(".$row['Id'].")'><img width='20px' src=\"../../Images/S.png\"></a></td>";
									}
									else{
										if($row['Statut']=="ECHEC"){
											$ligne .= "<td><input type='checkbox' onclick='sensibilise(".$row['Id'].")'/></td>\n";
										}
										else{
											$ligne .= "<td></td>";
										}
									}
								}
								else{
									$check="";
									if($row['Sensibilisation']==1){$check="X";}
									$ligne .= "<td>".$check."</td>";

								}
								if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
									if($row['Sensibilisation']==1 || ($row['Sensibilisation']==0 && $row['Statut']<>"ECHEC")){
										$ligne .= "<td><a href='javascript:valider(".$row['Id'].")'><img width='20px' src=\"../../Images/Valider.png\"></a></td>";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
								else{
									$ligne .= "<td></td>";
								}
								if($row['Statut']=="" && $row['IgnorerSurveillance']==0){
									if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
										$ligne .= "<td><a href='javascript:ignorer(".$row['Id'].")'><img width='20px' src=\"../../Images/info2.png\"></a></td>\n";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
								else{
									if($row['Statut']=="VALIDE" && DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
										$ligne .= "<td><a href='javascript:ignorer(".$row['Id'].")'><img width='20px' src=\"../../Images/info2.png\"></a></td>\n";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
								if(($row['Statut']=="VALIDE" || $row['Statut']=="ECHEC") && (DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS))){
									$ligne .= "		<td><a href='javascript:QCM_Web(".$row['Id_SessionPersonneQualification'].")'><img src=\"../../Images/qcm.png\" width=\"25px\"></a></td>\n";
								}
								else{$ligne .= "		<td></td>\n";}
							if($row['Statut']=="VALIDE")
							{
								if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
									$ligne .= "		<td>";
									$checked="";
									if($row['DateHeureOuverture']>'0001-01-01' && $row['DateHeureFermeture']<="0001-01-01"){$checked="checked";}
									$ligne .= " <label class=\"switch\">
										  <input type=\"checkbox\" id=\"CB_".$row['Id_SessionPersonneQualification']."\" name=\"CB_".$row['Id_SessionPersonneQualification']."\" ".$checked." onchange='javascript:OuvrirFermerAcces(".$row['Id_SessionPersonneQualification'].",\"".$checked."\")'>                          
										  <span class=\"slider round\" ></span>
										</label>";
									$ligne .= "</td>\n";
								}
								else{
									$ligne .= "		<td></td>\n";
								}
							}
							else{$ligne .= "		<td></td>\n";}
							$ligne .= "		<td align='center'>";
							if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
									if($row['Sensibilisation']==1 || ($row['Sensibilisation']==0 && $row['Statut']<>"ECHEC")){
								$ligne .= " <input class='checkVO' type=\"checkbox\" name=\"CheckVO[]\" value=\"".$row['Id']."\"'> ";
									}
							}
							$ligne .= "</td>\n";
							$ligne .= "</tr>\n";
							echo $ligne;
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
					}
				?>
			</table>		
		</td>
	</tr>
</table>
</form>
</html>