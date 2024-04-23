<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css?t=<?php echo time(); ?>" rel="stylesheet">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.libelle.value==''){alert('You didn\'t enter the wording.');return false;}
			}
			else{
				if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			}
			return true;
		}
		function FermerEtRecharger(){
			window.opener.parent.location.reload();
			window.close();
		}
		function nombre(champ){
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
			verif = chiffres.test(champ.value.charAt(x));
			if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
			if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
			if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
	</script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/webforms2-0/webforms2-p.js"></script>	
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../JS/colorpicker.js"></script>
</head>
<body>
 <script>
	var initColorpicker = function() {  
	$('input[type=color]').each(function() {  
		var $input = $(this);  
		$input.ColorPicker({  
			onSubmit: function(hsb, hex, rgb, el) {  
				$(el).val(hex);  
				$(el).ColorPickerHide();  
			}  
		});  
	});  
	};  

	if(!Modernizr.inputtypes.color){$(document).ready(initColorpicker);};
</script>
<?php

session_start();
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$droit="";
		$objectif=0;
		if($_POST['objectifFTR']<>""){$objectif=$_POST['objectifFTR'];}
		$requete="INSERT INTO trame_prestation (Libelle,Planning,Id_PrestationExtra,Couleur,ObjectifFTR) VALUES ('".addslashes($_POST['libelle'])."',".$_POST['planning'].",".$_POST['prestation'].",'".$_POST['color-picker']."',".$objectif.") ";
		$result=mysqli_query($bdd,$requete);
		$IdCree = mysqli_insert_id($bdd);
		if($IdCree>0){
			if($_POST['dupliquer']>0){
				//Domaines techniques
				$req="INSERT INTO trame_domainetechnique (Libelle,Id_Prestation,OldID) ";
				$req.="SELECT Libelle, ".$IdCree.", Id FROM trame_domainetechnique WHERE Supprime=false AND Id_Prestation=".$_POST['dupliquer'];
				$result=mysqli_query($bdd,$req);
				
				//Catégories 
				$req="INSERT INTO trame_categorie (Libelle,Id_Prestation,OldID) ";
				$req.="SELECT Libelle, ".$IdCree.", Id FROM trame_categorie WHERE Supprime=false AND Id_Prestation=".$_POST['dupliquer'];
				$result=mysqli_query($bdd,$req);
				
				//UO
				$req="INSERT INTO trame_uo (Libelle,Id_Prestation,Description,OldID,Id_Categorie) ";
				$req.="SELECT Libelle,".$IdCree.",Description,Id,";
				$req.="(SELECT Id FROM trame_categorie WHERE trame_categorie.Id_Prestation=".$IdCree." AND trame_categorie.OldID=trame_uo.Id_Categorie) AS Id_Categorie ";
				$req.="FROM trame_uo WHERE Supprime=false AND Id_Prestation=".$_POST['dupliquer'];
				$result=mysqli_query($bdd,$req);
				
				//Temps alloués
				$req="INSERT INTO trame_tempsalloue (Complexite,TypeTravail,Temps,Id_UO,Id_DomaineTechnique) ";
				$req.="SELECT Complexite,TypeTravail,Temps,trame_uo.Id,trame_domainetechnique.Id ";
				$req.="FROM trame_tempsalloue ";
				$req.="LEFT JOIN trame_uo ON trame_uo.OldID=trame_tempsalloue.Id_UO ";
				$req.="LEFT JOIN trame_domainetechnique ON trame_domainetechnique.OldID=trame_tempsalloue.Id_DomaineTechnique ";
				$req.="WHERE trame_uo.Supprime=false AND trame_domainetechnique.Supprime=false AND trame_domainetechnique.Id_Prestation=".$IdCree." AND trame_uo.Id_Prestation=".$IdCree;
				$result=mysqli_query($bdd,$req);
				/*
				if($_POST['libelle']=="PELR"){
					//Responsable délai
					$req="INSERT INTO trame_responsabledelais (Libelle,Id_Prestation,OldID,Supprime) ";
					$req.="SELECT Libelle, ".$IdCree.", Id,Supprime FROM trame_responsabledelais WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Famille erreur
					$req="INSERT INTO trame_familleerreur (Libelle,Id_Prestation,OldID,Supprime) ";
					$req.="SELECT Libelle, ".$IdCree.", Id,Supprime FROM trame_familleerreur WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Origine
					$req="INSERT INTO trame_origine (Libelle,Id_Prestation,OldID,Supprime) ";
					$req.="SELECT Libelle, ".$IdCree.", Id,Supprime FROM trame_origine WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Ponderation
					$req="INSERT INTO trame_ponderation (Libelle,Id_Prestation,OldID,Supprime,Numero) ";
					$req.="SELECT Libelle, ".$IdCree.", Id,Supprime,Numero FROM trame_ponderation WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Responsable anomalie
					$req="INSERT INTO trame_responsable (Libelle,Id_Prestation,OldID,Supprime) ";
					$req.="SELECT Libelle, ".$IdCree.", Id,Supprime FROM trame_responsable WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Famille Tâche
					$req="INSERT INTO trame_familletache (Libelle,Id_Prestation,OldID,Supprime) ";
					$req.="SELECT Libelle, ".$IdCree.", Id,Supprime FROM trame_familletache WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Workpackage
					$req="INSERT INTO trame_wp (Libelle,Id_Prestation,DateDebut,DateFin,OldID) ";
					$req.="SELECT Libelle, ".$IdCree.",DateDebut,DateFin, Id FROM trame_wp WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Tâches
					$req="INSERT INTO trame_tache (Libelle,Id_Prestation,Id_FamilleTache,CritereOTD,NiveauControle,Delais,OldID,Supprime) ";
					$req.="SELECT Libelle,".$IdCree.",";
					$req.="(SELECT Id FROM trame_familletache WHERE trame_familletache.Id_Prestation=".$IdCree." AND trame_familletache.OldID=trame_tache.Id_FamilleTache) AS Id_FamilleTache, ";
					$req.="CritereOTD,NiveauControle,Delais,Id,Supprime ";
					$req.="FROM trame_tache WHERE SUBSTRING(Libelle,1,1)='E' AND Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Tâches infocomplémentaire
					$req="INSERT INTO trame_tache_infocomplementaire (Id_Prestation,Id_Tache,Info,Type,Supprime,OldID) ";
					$req.="SELECT ".$IdCree.",trame_tache.Id, ";
					$req.="trame_tache_infocomplementaire.Info,trame_tache_infocomplementaire.Type,trame_tache_infocomplementaire.Supprime,trame_tache_infocomplementaire.Id ";
					$req.="FROM trame_tache_infocomplementaire LEFT JOIN trame_tache ON trame_tache_infocomplementaire.Id_Tache=trame_tache.OldID WHERE trame_tache.Id_Prestation=".$IdCree." AND trame_tache.OldID>0 AND trame_tache_infocomplementaire.Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Taches UO
					$req="INSERT INTO trame_tache_uo (Id_Prestation,Id_Tache,Id_UO,Id_DT,Complexite,Relation,TypeTravail) ";
					$req.="SELECT ".$IdCree.",trame_tache.Id,";
					$req.="(SELECT Id FROM trame_uo WHERE trame_uo.Id_Prestation=".$IdCree." AND trame_uo.OldID=trame_tache_uo.Id_UO) AS Id_UO, ";
					$req.="(SELECT Id FROM trame_domainetechnique WHERE trame_domainetechnique.Id_Prestation=".$IdCree." AND trame_domainetechnique.OldID=trame_tache_uo.Id_DT) AS Id_DT, ";
					$req.="Complexite,Relation,TypeTravail ";
					$req.="FROM trame_tache_uo LEFT JOIN trame_tache ON trame_tache_uo.Id_Tache=trame_tache.OldID WHERE trame_tache.Id_Prestation=".$IdCree." AND trame_tache.OldID>0 AND trame_tache_uo.Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Taches WP
					$req="INSERT INTO trame_tache_wp (Id_Prestation,Id_Tache,Id_WP,Supprime) ";
					$req.="SELECT ".$IdCree.",trame_tache.Id,";
					$req.="(SELECT Id FROM trame_wp WHERE trame_wp.Id_Prestation=".$IdCree." AND trame_wp.OldID=trame_tache_wp.Id_WP) AS Id_WP, ";
					$req.="trame_tache_wp.Supprime ";
					$req.="FROM trame_tache_wp LEFT JOIN trame_tache ON trame_tache_wp.Id_Tache=trame_tache.OldID WHERE trame_tache.Id_Prestation=".$IdCree." AND trame_tache.OldID>0 AND trame_tache_wp.Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Accès
					$req="INSERT INTO trame_acces (Id_Personne,Id_Prestation,Droit,Id_WP) ";
					$req.="SELECT Id_Personne,".$IdCree.",Droit,";
					$req.="(SELECT Id FROM trame_wp WHERE trame_wp.Id_Prestation=".$IdCree." AND trame_wp.OldID=trame_acces.Id_WP) AS Id_WP ";
					$req.="FROM trame_acces WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Anomalie
					$req="INSERT INTO trame_anomalie (Id_Prestation,Id_Origine,Id_Ponderation,Id_FamilleErreur1,Id_FamilleErreur2,Id_Responsable,Id_WP,Id_Createur,DateAnomalie,Reference,Probleme,ActionCurative,AnalyseCause,ActionPreventive,Observation,DatePrevisionnelle,DateReport,DateCloture) ";
					$req.="SELECT ".$IdCree.",";
					$req.="(SELECT Id FROM trame_origine WHERE trame_origine.Id_Prestation=".$IdCree." AND trame_origine.OldID=trame_anomalie.Id_Origine) AS Id_Origine, ";
					$req.="(SELECT Id FROM trame_ponderation WHERE trame_ponderation.Id_Prestation=".$IdCree." AND trame_ponderation.OldID=trame_anomalie.Id_Ponderation) AS Id_Ponderation, ";
					$req.="(SELECT Id FROM trame_familleerreur WHERE trame_familleerreur.Id_Prestation=".$IdCree." AND trame_familleerreur.OldID=trame_anomalie.Id_FamilleErreur1) AS Id_FamilleErreur1, ";
					$req.="(SELECT Id FROM trame_familleerreur WHERE trame_familleerreur.Id_Prestation=".$IdCree." AND trame_familleerreur.OldID=trame_anomalie.Id_FamilleErreur2) AS Id_FamilleErreur2, ";
					$req.="(SELECT Id FROM trame_responsable WHERE trame_responsable.Id_Prestation=".$IdCree." AND trame_responsable.OldID=trame_anomalie.Id_Responsable) AS Id_Responsable, ";
					$req.="(SELECT Id FROM trame_wp WHERE trame_wp.Id_Prestation=".$IdCree." AND trame_wp.OldID=trame_anomalie.Id_WP) AS Id_WP, ";
					$req.="Id_Createur,DateAnomalie,Reference,Probleme,ActionCurative,AnalyseCause,ActionPreventive,Observation,DatePrevisionnelle,DateReport,DateCloture ";
					$req.="FROM trame_anomalie WHERE Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Travail effectue
					$req="INSERT INTO trame_travaileffectue (Id_Prestation,Id_Tache,Id_WP,Id_ResponsableDelai,Statut,Id_Preparateur,Id_Responsable,Designation,DatePreparateur,DateValidation,RaisonRefus,DescriptionModification,StatutDelai,CommentaireDelai,Attestation,NiveauControle,TempsPasse,OldID) ";
					$req.="SELECT ".$IdCree.",trame_tache.Id,";
					$req.="(SELECT Id FROM trame_wp WHERE trame_wp.Id_Prestation=".$IdCree." AND trame_wp.OldID=trame_travaileffectue.Id_WP) AS Id_WP, ";
					$req.="(SELECT Id FROM trame_responsabledelais WHERE trame_responsabledelais.Id_Prestation=".$IdCree." AND trame_responsabledelais.OldID=trame_travaileffectue.Id_ResponsableDelai) AS Id_ResponsableDelai, ";
					$req.="Statut,Id_Preparateur,Id_Responsable,Designation,DatePreparateur,DateValidation,RaisonRefus,DescriptionModification,StatutDelai,CommentaireDelai,Attestation,trame_travaileffectue.NiveauControle,TempsPasse,trame_travaileffectue.Id ";
					$req.="FROM trame_travaileffectue LEFT JOIN trame_tache ON trame_travaileffectue.Id_Tache=trame_tache.OldID WHERE trame_tache.Id_Prestation=".$IdCree." AND trame_tache.OldID>0 AND trame_tache.Id_Prestation=".$IdCree." AND trame_travaileffectue.Id_Prestation=".$_POST['dupliquer'];
					$result=mysqli_query($bdd,$req);
					
					//Travail effectue info
					$req="INSERT INTO trame_travaileffectue_info (Id_TravailEffectue,ValeurInfo,Id_InfoTache) ";
					$req.="SELECT trame_travaileffectue.Id,ValeurInfo,";
					$req.="(SELECT Id FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id_Prestation=".$IdCree." AND trame_tache_infocomplementaire.OldID=trame_travaileffectue_info.Id_InfoTache) AS Id_InfoTache ";
					$req.="FROM trame_travaileffectue_info LEFT JOIN trame_travaileffectue ON trame_travaileffectue_info.Id_TravailEffectue=trame_travaileffectue.OldID ";
					$req.="WHERE trame_travaileffectue.Id_Prestation=".$IdCree."";
					$result=mysqli_query($bdd,$req);
					
					//Travail effectue uo
					$req="INSERT INTO trame_travaileffectue_uo (Id_TravailEffectue,Id_UO,Id_DomaineTechnique,Complexite,Relation,TypeTravail,TravailFait,TempsAlloue) ";
					$req.="SELECT trame_travaileffectue.Id,";
					$req.="(SELECT Id FROM trame_uo WHERE trame_uo.Id_Prestation=".$IdCree." AND trame_uo.OldID=trame_travaileffectue_uo.Id_UO) AS Id_UO, ";
					$req.="(SELECT Id FROM trame_domainetechnique WHERE trame_domainetechnique.Id_Prestation=".$IdCree." AND trame_domainetechnique.OldID=trame_travaileffectue_uo.Id_DomaineTechnique) AS Id_DomaineTechnique, ";
					$req.="Complexite,Relation,TypeTravail,TravailFait,TempsAlloue ";
					$req.="FROM trame_travaileffectue_uo LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.OldID ";
					$req.="WHERE trame_travaileffectue.Id_Prestation=".$IdCree."";
					$result=mysqli_query($bdd,$req);
				}
				*/
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$objectif=0;
		if($_POST['objectifFTR']<>""){$objectif=$_POST['objectifFTR'];}
		
		$requete="UPDATE trame_prestation SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Planning=".$_POST['planning'].",";
		$requete.="ObjectifFTR=".$objectif.",";
		$requete.="Couleur='".$_POST['color-picker']."',";
		$requete.="Id_PrestationExtra=".$_POST['prestation']."";
		$requete.=" WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Planning, Id_PrestationExtra,Couleur,ObjectifFTR, PointageExtranet FROM trame_prestation WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Prestation.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libellé";} ?> </td>
				<td>
					<input type="texte" name="libelle" id="libelle" size="60" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><label for="color-picker"><?php if($_SESSION['Langue']=="EN"){echo "Color";}else{echo "Couleur";} ?></label></td>
				<td>
					<input type="color" name="color-picker" id="color-picker" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Couleur'];}?>"/>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Planning";}else{echo "Planning";} ?> </td>
				<td>
					<select	id="planning" name="planning">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['Planning']==0){echo "selected";}}else{echo "selected";} ?>><?php if($_SESSION['Langue']=="EN"){echo "Enabled";}else{echo "Activé";}?></option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['Planning']==1){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "Deactivated";}else{echo "Désactivé";}?></option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?> </td>
				<td>
					<select	id="prestation" name="prestation" style="width:200px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM new_competences_prestation ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($row['Id']==$Ligne['Id_PrestationExtra']){$selected="selected";}
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "FTR objective (%)";}else{echo "Objectif FTR (%)";} ?> </td>
				<td><input onKeyUp='nombre(this)' id='objectifFTR' name='objectifFTR' value='<?php if($_GET['Mode']=="M"){echo $Ligne['ObjectifFTR'];}?>' size='5'></td>
			</tr>
			<?php 
				if($_GET['Mode']=="A"){
			?>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Duplicate the work unit's site catalog";}else{echo "Dupliquer le catalogue d'UO de la prestation ";} ?> </td>
				<td>
					<select	id="dupliquer" name="dupliquer">
						<option value="0" selected></option>
						<?php
							$req="SELECT Id, Libelle FROM trame_prestation ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									echo "<option value='".$row['Id']."'>".$row['Libelle']."</option>";
								}
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				}
			?>
			<tr class="TitreColsUsers">
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="DELETE FROM trame_acces WHERE Id_Prestation=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		
		$requete="DELETE FROM trame_prestation WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>