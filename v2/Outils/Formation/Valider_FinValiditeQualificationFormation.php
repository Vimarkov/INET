<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
<title>Formation - Valider les formations des qualifications</title><meta name="robots" content="noindex">
<link rel="stylesheet" href="../JS/styleCalendrier.css">
<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="Production.js"></script>
<script type="text/javascript" src="../JS/date.js"></script>
<script type="text/javascript" src="../JS/jquery.min.js"></script>
<!-- HTML5 Shim -->
<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
<!-- Modernizr -->
<script src="../JS/modernizr.js"></script>
<!-- jQuery  -->
<script src="../JS/js/jquery-1.4.3.min.js"></script>
<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
<script>
function FermerEtRecharger(Id_Prestation,Personne,Qualif){
	window.opener.location = "Liste_FinValiditeQualificationFormation.php?prestation="+Id_Prestation+"&personne="+Personne+"&qualification="+Qualif;
	window.close();
}
</script>
</head>
<body>

<?php
if($_GET)
{
	$tab = explode(";",$_GET['Id']);
	foreach($tab as $formation)
	{
		$tabFormation = explode("_",$formation);
		if($formation<>"")
		{
			$Id_QualifAFormer = $tabFormation[1];
			$Id_Formation = $tabFormation[2];
			if($Id_QualifAFormer<>"" && $Id_Formation<>"")
			{
				$req="
					SELECT
						form_qualificationnecessaire_prestation.Id_Relation,
						form_qualificationnecessaire_prestation.Id_Prestation,
						form_qualificationnecessaire_prestation.Id_Pole,
						form_qualificationnecessaire_prestation.Necessaire,
						form_qualificationnecessaire_prestation.Id_Validateur,
						form_qualificationnecessaire_prestation.DateValidation,
						new_competences_relation.Id_Personne,
						new_competences_relation.Id_Qualification_Parrainage
					FROM
						form_qualificationnecessaire_prestation
					LEFT JOIN new_competences_relation
						ON form_qualificationnecessaire_prestation.Id_Relation=new_competences_relation.Id
					WHERE
						form_qualificationnecessaire_prestation.Id=".$Id_QualifAFormer;
				$resultQualifNecessaire=mysqli_query($bdd,$req);
				$nbQualifsNecessaire=mysqli_num_rows($resultQualifNecessaire);
				
				if($nbQualifsNecessaire>0){
					$rowQualifNecessaire = mysqli_fetch_array($resultQualifNecessaire);
					//Test si le besoin n'est pas déjà émis - MAJ 29/11/2017
					if(Get_NbBesoinExistant($rowQualifNecessaire['Id_Personne'], $Id_Formation)==0)
					{
						//Création du besoin
						$requete="
							INSERT INTO
								form_besoin
								(
									Id_Demandeur,
									Id_Prestation,
									Id_Pole,
									Id_Formation,
									Id_Personne,
									Date_Demande,
									Motif,
									Valide,
									Id_Personne_MAJ,
									Date_MAJ)
							VALUES
								(
									".$rowQualifNecessaire['Id_Validateur'].",
									".$rowQualifNecessaire['Id_Prestation'].",
									".$rowQualifNecessaire['Id_Pole'].",
									".$Id_Formation.",
									".$rowQualifNecessaire['Id_Personne'].",
									'".date("Y-m-d")."',
									'Renouvellement',
									1,
									".$IdPersonneConnectee.",
									'".date("Y-m-d")."'
								) ";
						$result=mysqli_query($bdd,$requete);
						$IdCree = mysqli_insert_id($bdd);
						
						//Création des qualifications associées
						if($IdCree>0){
							//Qualifications valides
							$ReqQualifsValides="
								SELECT
									Id_Qualification_Parrainage
								FROM
									new_competences_relation
								WHERE
									Type='Qualification'
									AND (Date_Fin<='0001-01-01' OR Date_Fin >= '".date("Y-m-d")."')
									AND Id_Qualification_Parrainage IN
										(
											SELECT
												Id_Qualification
											FROM
												form_formation_qualification
											WHERE
												Id_Formation=".$Id_Formation."
												AND Suppr=0
												AND Masquer=0
										)
									AND Id_Personne =".$rowQualifNecessaire['Id_Personne']." ";
							$ResultQualifValide=mysqli_query($bdd,$ReqQualifsValides);
							$nbQualifsValide=mysqli_num_rows($ResultQualifValide);
							
							$reqQualifsB="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$Id_Formation." AND Suppr=0 AND Masquer=0 ";
							$ResultQualifB=mysqli_query($bdd,$reqQualifsB);
							$nbQualifsB=mysqli_num_rows($ResultQualifB);
							
							//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
							if($nbQualifsB>0){
								while($rowQualifB=mysqli_fetch_array($ResultQualifB)){
									$visible=0;
									$ReqInsertBesoinGPEC="
										INSERT INTO
											new_competences_relation
											(
												Id_Personne,
												Type,
												Id_Qualification_Parrainage,
												Evaluation,
												Visible,
												Id_Besoin
											)
										VALUES
											(
												".$rowQualifNecessaire['Id_Personne'].",
												'Qualification',
												".$rowQualifB['Id_Qualification'].",
												'B',
												".$visible.",
												".$IdCree."
											)";
									$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
								}
							}
							
							$req="DELETE FROM form_qualificationnecessaire_prestation ";
							$req.="WHERE Id=".$Id_QualifAFormer;
							$resultSuppr=mysqli_query($bdd,$req);
						}
					}
					else
					{
						$req="DELETE FROM form_qualificationnecessaire_prestation ";
						$req.="WHERE Id=".$Id_QualifAFormer;
						$resultSuppr=mysqli_query($bdd,$req);
					}
				}
			}
		}
	}
	echo "<script>FermerEtRecharger('".$_GET['Id_Prestation']."','".$_GET['Personne']."','".$_GET['Qualif']."');</script>";
}	
?>
</body>
</html>
