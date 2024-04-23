<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function FermerEtRecharger(Id,CaisseMateriel)
		{
			if(CaisseMateriel==0){
				window.opener.location="Ajout_TransfertMateriel.php?Id="+Id;
			}
			else{
				window.opener.location="Ajout_TransfertCaisse.php?Id="+Id;
			}
			window.opener.opener.location="Liste_Materiel.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
	$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne(date('Y-m-d'),$_SESSION['Id_Personne']));
		$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
		$IdPolePersonneConnectee=0;
		if($IdPrestationPersonneConnectee>0){
			$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
		}
		
	$requeteUpdate="UPDATE tools_mouvement SET 
			Id_Recepteur=".$_SESSION['Id_Personne'].",
			Id_PrestationRecepteur=".$IdPrestationPersonneConnectee.",
			Id_PoleRecepteur=".$IdPolePersonneConnectee.",
			EtatValidation=1,
			DateReception='".date('Y-m-d')."'
			WHERE Id=".$_GET['Id']." ";
	$resultat=mysqli_query($bdd,$requeteUpdate);
	
	//Mettre à jour matériel/caisse
	$req="SELECT Id_Materiel__Id_Caisse, Type FROM tools_mouvement WHERE Id=".$_GET['Id']." ";
	$resultat=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($resultat);
	if($nbResulta>0){
		$Row=mysqli_fetch_array($resultat);
		if($Row['Type']==0){
			$req="UPDATE tools_materiel
				SET
				Id_CaisseT=(SELECT TAB_Mouvement.Id_Caisse
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_PrestationT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Prestation,
						(
						SELECT Id_Prestation
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_PoleT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Pole,
						(
						SELECT Id_Pole
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_LieuT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Lieu,
						(
						SELECT Id_Lieu
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_PersonneT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Personne,
						(
						SELECT Id_Personne
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				DateReceptionT=(SELECT TAB_Mouvement.DateReception
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				EtatValidationT=(SELECT TAB_Mouvement.EtatValidation
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				CommentaireT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Commentaire,
						(
						SELECT Commentaire
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				)
			WHERE Id=".$Row['Id_Materiel__Id_Caisse'];
			$resultat=mysqli_query($bdd,$req);
		}
		else{
			$req="UPDATE tools_caisse
			SET
			EtatValidationT=(
				SELECT EtatValidation
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			),
			Id_PrestationT=(
				SELECT Id_Prestation
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			),
			Id_PoleT=(
				SELECT Id_Pole
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			),
			Id_PersonneT=(
				SELECT Id_Personne
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			),
			DateReceptionT=(
				SELECT DateReception
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			),
			Id_LieuT=(
				SELECT Id_Lieu
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			),
			CommentaireT=(
				SELECT Commentaire
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)
			WHERE Id=".$Row['Id_Materiel__Id_Caisse'];
			$resultat=mysqli_query($bdd,$req);
			
			$req="UPDATE tools_materiel
				SET
				Id_CaisseT=(SELECT TAB_Mouvement.Id_Caisse
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_PrestationT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Prestation,
						(
						SELECT Id_Prestation
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_PoleT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Pole,
						(
						SELECT Id_Pole
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_LieuT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Lieu,
						(
						SELECT Id_Lieu
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				Id_PersonneT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Personne,
						(
						SELECT Id_Personne
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				DateReceptionT=(SELECT TAB_Mouvement.DateReception
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				EtatValidationT=(SELECT TAB_Mouvement.EtatValidation
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
				CommentaireT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Commentaire,
						(
						SELECT Commentaire
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				)
			WHERE (SELECT TAB_Mouvement.Id_Caisse
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				)=".$Row['Id_Materiel__Id_Caisse'];
			$resultat=mysqli_query($bdd,$req);
		}
	}
	
	echo "<script>FermerEtRecharger(".$_GET['Id_Outil'].",".$_GET['CaisseMateriel'].");</script>";


?>
</body>
</html>