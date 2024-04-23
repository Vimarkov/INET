<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
    <head>
    	<title>Formations - Autorisation de conduite (Web)</title><meta name="robots" content="noindex">
    	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
    </head>

	<?php
//Responsable plateforme
$Resp="";
$req="
    SELECT
		new_rh_etatcivil.Id,
        Nom,
        Prenom
	FROM
        new_competences_personne_poste_plateforme
	LEFT JOIN new_rh_etatcivil
	   ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
	WHERE
        Id_Poste=9 
		AND Backup=0
		AND Id_Plateforme IN
        (
            SELECT
                Id_Plateforme 
            FROM
                new_competences_personne_plateforme
            WHERE
                Id_Personne=".$_GET['Id']."
		)";
$resultResp=mysqli_query($bdd,$req);
$nbResp=mysqli_num_rows($resultResp);
if($nbResp>0)
{
	$rowResp=mysqli_fetch_array($resultResp);
	if($rowResp['Id']=10749){
		$Resp=substr($rowResp['Prenom'],0,1).". B. ".$rowResp['Nom'];
	}
	else{
		$Resp=substr($rowResp['Prenom'],0,1).". ".$rowResp['Nom'];
	}
}

$req="
    SELECT
        Nom,
        Prenom
    FROM
        new_rh_etatcivil
    WHERE
        Id=".$_GET['Id'];
$resultPers=mysqli_query($bdd,$req);
$rowPers=mysqli_fetch_array($resultPers);

if($LangueAffichage=="FR")
{
	$Libelle_Titre="AUTORISATION DE CONDUITE";
	$Libelle_Moyens="Moyens";
	$Libelle_Categories="Catégories";
	$Libelle_FinValidite="Fin de validité";
	$Libelle_Nom="Nom : ";
	$Libelle_Prenom="Prénom : ";
	$Libelle_Delivre="Délivré par : ";
}
else
{
    $Libelle_Titre="AUTHORIZATION OF WORK";
    $Libelle_Moyens="Means";
    $Libelle_Categories="Categories";
    $Libelle_FinValidite="End of validity";
    $Libelle_Nom="Last name : ";
    $Libelle_Prenom="First name : ";
    $Libelle_Delivre="Delivered by : ";
}

//Liste des autorisations de conduite
$reqAT="
    SELECT
    DISTINCT
        new_competences_relation.Id_Qualification_Parrainage,
        new_competences_relation.Date_Fin,
        (SELECT Libelle FROM new_competences_moyen_categorie WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Categorie,
        (SELECT new_competences_moyen_categorie.Id_Moyen FROM new_competences_moyen_categorie WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Id_Moyen,
        (
            SELECT
                (SELECT Libelle FROM new_competences_moyen WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) 
            FROM
                new_competences_moyen_categorie 
            WHERE
                new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie
        ) AS Moyen  
    FROM
        new_competences_relation 
    LEFT JOIN new_competences_qualification_moyen
        ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification_moyen.Id_Qualification 
    LEFT JOIN new_competences_qualification
        ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
    WHERE
        new_competences_qualification_moyen.Suppr=0 
        AND new_competences_relation.Suppr=0 
        AND new_competences_qualification_moyen.Suppr=0 
        AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
        AND Date_Debut>'0001-01-01' 
        AND new_competences_relation.Evaluation NOT IN ('B','')
        AND new_competences_relation.Id_Personne=".$_GET['Id']." 
		AND (
		new_competences_qualification_moyen.Id_Moyen_Categorie NOT IN (1,2)
		OR (new_competences_qualification_moyen.Id_Moyen_Categorie IN (1,2)
		AND 
		(SELECT COUNT(Id)
		FROM new_competences_relation
		WHERE Suppr=0
		AND Evaluation NOT IN ('B','')
		AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
		AND Date_Debut>'0001-01-01' 
		AND Id_Personne=".$_GET['Id']."
		AND Id_Qualification_Parrainage=75)>0

		AND (SELECT COUNT(Id)
		FROM new_competences_relation
		WHERE Suppr=0
		AND Evaluation NOT IN ('B','')
		AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
		AND Date_Debut>'0001-01-01' 
		AND Id_Personne=".$_GET['Id']."
		AND Id_Qualification_Parrainage=12)>0

		AND (SELECT COUNT(Id)
		FROM new_competences_relation
		WHERE Suppr=0
		AND Evaluation NOT IN ('B','')
		AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
		AND Date_Debut>'0001-01-01' 
		AND Id_Personne=".$_GET['Id']."
		AND Id_Qualification_Parrainage=13)>0

		AND (SELECT COUNT(Id)
		FROM new_competences_relation
		WHERE Suppr=0
		AND Evaluation NOT IN ('B','')
		AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
		AND Date_Debut>'0001-01-01' 
		AND Id_Personne=".$_GET['Id']."
		AND Id_Qualification_Parrainage=133)>0)
		)
		";
$resultAT=mysqli_query($bdd,$reqAT);
$nbAT=mysqli_num_rows($resultAT);

//Mise à jour de la date autorisations de conduite
$reqUpdateAT="
    UPDATE
        new_competences_relation 
    SET
        new_competences_relation.DateEditionAutorisationTravail='".date('Y-m-d')."' 
    WHERE
        (
            Date_Fin>='".date('Y-m-d')."'
            OR 
	        (
                SELECT
                    Duree_Validite 
	            FROM
                    new_competences_qualification
	           WHERE
                    new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
            )=0
        )
        AND Date_Debut>'0001-01-01' 
        AND new_competences_relation.Suppr=0 
        AND new_competences_relation.Evaluation NOT IN ('B','')
        AND new_competences_relation.DateEditionAutorisationTravail<='0001-01-01'
        AND new_competences_relation.Id_Personne=".$_GET['Id']." ";
$resultUpdateAT=mysqli_query($bdd,$reqUpdateAT);

$req="
    UPDATE
        new_rh_etatcivil
    SET
        DateEditionAutorisationTravail='".date('Y-m-d')."'
    WHERE
        Id=".$_GET['Id'];
$resultUpdtPers=mysqli_query($bdd,$req);
	
if($LangueAffichage=="FR"){$Avertissement="Toute personne ne respectant pas les règles de sécurité se verra retirer son autorisation de conduite.";}
else{$Avertissement="Anyone who does not respect the safety rules will be removed from his driving authorization.";}
?>
    <table style="background-color:white;border-spacing:0;">
    	<tr>
    		<td width="45%" colspan=3 style="border:1px solid black;"><img src="../../Images/Logos/Logo_AAA_FR.png"></td>
    		<td width="55%" rowspan=5>
    			<table style="border:1px;border-spacing:0;">
    				<tr>
                		<td style="border:1px solid black;">Moyens</td>
                		<td style="border:1px solid black;">Catégories</td>
                		<td style="border:1px solid black;">Fin de validité</td>
                	</tr>
                	<?php 
                    	if($nbAT>0)
                    	{
                    	    while($rowAT=mysqli_fetch_array($resultAT))
                    	    {
                    	        echo "
                                    <tr>
                                        <td style='border:1px solid black;'>".$rowAT['Moyen']."</td>
                                        <td style='border:1px solid black;'>".$rowAT['Categorie']."</td>
                                        <td style='border:1px solid black;'>".$rowAT['Date_Fin']."</td>
                                    </tr>";
                    	    }
                    	}
                	?>
                	<tr>
                		<td colspan=3 style="background-color: red; font-color:white; border:1px solid black;"><?php echo $Avertissement;?></td>
                	</tr>
    			</table>
    		</td>
    	</tr>
    	<tr>
    		<td colspan=3 style="border:1px solid black;"><b>AUTRISATION DE TRAVAIL</b></td>
    	</tr>
    	<tr>
    		<td style="border:1px solid black;">Nom : </td>
    		<td style="border:1px solid black;"><?php echo $rowPers['Nom'];?></td>
    		<td width="60px" rowspan=2 style="border:1px solid black;">&nbsp;</td>
    	</tr>
    	<tr>
    		<td style="border:1px solid black;">Prénom : </td>
    		<td style="border:1px solid black;"><?php echo $rowPers['Prenom'];?></td>
    	</tr>
    	<tr>
    		<td style="border:1px solid black;">Délivré par : </td>
    		<td style="border:1px solid black;"><?php echo $Resp;?></td>
    		<td style="border:1px solid black;">&nbsp;</td>
    	</tr>
    </table>

</body>
</html>