<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Modifier Document avec session de formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
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

$sansFormation = false;
if($_POST)
{
	if(isset($_POST['generer']))
	{
		if(isset($_POST['Id_SessionPersonneDocument']))
		{
			maj_Langue_SessionPersonneDocument($_POST['Id_SessionPersonneDocument'],$_POST['langue_document']);
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}

if($_GET){
	$Id_SessionPersonneDocument=$_GET['Id'];
	if(isset($_GET['sansFormation']))
    $sansFormation = true;
}
else{$Id_SessionPersonneDocument=$_POST['Id_SessionPersonneDocument'];}

	
if($sansFormation==true){
	$requete = "
        SELECT DISTINCT
        	\"Sans formation\" AS LIBELLE_TYPEFORMATION,
        	\"N/A\" AS REFERENCE_FORMATION,
        	\"\" AS ID_FORMATION,
        	form_session_personne_document.Id_Document,
			(SELECT Reference FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Document,
			form_session_personne_document.Id_LangueDocument,
        	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS NOM_PRENOM,
			0 AS Recyclage,
        	\"Sans formation\" AS Libelle										
        FROM
			form_session_personne_document,
        	form_session_personne_qualification,
        	form_typeformation,
        	form_formation,
        	form_besoin
        WHERE
        	form_session_personne_document.Id = ".$Id_SessionPersonneDocument."
			AND form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
        	AND form_formation.Id_TypeFormation = form_typeformation.Id
        	AND form_session_personne_qualification.Id_Besoin = form_besoin.Id;";
		
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
}
else{
	$requete="	SELECT DISTINCT
		form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
		form_formation.Reference AS REFERENCE_FORMATION,
		form_session.Id_Formation AS ID_FORMATION,
		form_session_personne_document.Id_Document,
		(SELECT Reference FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Document,
		form_session_personne_document.Id_LangueDocument,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS NOM_PRENOM,
		form_session.Recyclage,
		(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
			WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
			AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
			AND Suppr=0 LIMIT 1) AS Organisme,
		(SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
			FROM form_formation_langue_infos
			WHERE Id_Formation=form_session.Id_Formation
			AND Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=form_session.Id_Plateforme
				AND Id_Formation=form_session.Id_Formation
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0) AS Libelle										
	FROM
		form_session_personne_document,
		form_session_personne,
		form_session,
		form_typeformation,
		form_formation
	WHERE
		form_session_personne_document.Id=".$Id_SessionPersonneDocument."
		AND form_session_personne.Id=form_session_personne_document.Id_Session_Personne 
		AND form_session.Id=form_session_personne.Id_Session 
		AND form_formation.Id=form_session.Id_Formation
		AND form_formation.Id_TypeFormation=form_typeformation.Id";
		
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
}
?>
<form id="formulaire" method="POST" action="ModifierDocSession.php">
	<input type="hidden" name="Id_SessionPersonneDocument" value="<?php echo $Id_SessionPersonneDocument; ?>" />
	<table class="TableCompetences" style="width:95%; align:center;">
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['NOM_PRENOM']; ?>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> :
			</td>
			<td style="width:20%;" colspan="3">
				<?php echo $row['Libelle']; ?>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['LIBELLE_TYPEFORMATION']; ?>
			</td>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Initial / Recyclage";}else{echo "Initial / Recycling";}?> :
			</td>
			<td style="width:20%;">
				<?php 
					if($row['Recyclage']==0){
						if($LangueAffichage=="FR"){echo "Initial";}
						else{echo "Initial";}
					}
					else{
						if($LangueAffichage=="FR"){echo "Recyclage";}
						else{echo "Recycling";}
					}
				?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Document";}else{echo "Document";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['Document']; ?>
			</td>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";} ?> :
			</td>
			<td style="width:20%;">
				<select name="langue_document" id="langue_document">
					<?php
						$Id_LangueDocument=$row['Id_LangueDocument'];
						$req="SELECT DISTINCT 
							form_document_langue.Id_Langue,
							form_langue.Libelle AS Langue
							FROM form_document_langue
							LEFT JOIN form_langue
							ON form_document_langue.Id_Langue=form_langue.Id
							WHERE form_document_langue.Id_Document=".$row['Id_Document']."
							AND form_document_langue.Suppr=0 
							AND form_langue.Suppr=0 ";
						$resultLangue=mysqli_query($bdd,$req);
						$nbLangue=mysqli_num_rows($resultLangue);
						if($nbLangue>0){
							while($rowLangue=mysqli_fetch_array($resultLangue)){
								$selected="";
								if($Id_LangueDocument==$rowLangue['Id_Langue']){$selected="selected";}
								echo "<option value='".$rowLangue['Id_Langue']."' ".$selected." >".stripslashes($rowLangue['Langue'])."</option>";
							}
						}
						
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="4" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion

?>
</body>
</html>