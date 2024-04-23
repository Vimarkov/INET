<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeHS.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
			}
			else{
				if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

			}
			return true;
		}
		function FermerEtRecharger()
		{
			window.opener.location="Liste_MouvementOutils.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne(date('Y-m-d'),$IdPersonneConnectee));
	$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
	$IdPolePersonneConnectee=0;
	if($IdPrestationPersonneConnectee>0){
		$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
	}

	$Ids=$_POST['Ids'];
	$TabId=explode(",",$Ids);
	foreach($TabId as $Id){
		$requeteUpdate="UPDATE tools_mouvement SET 
				Id_Recepteur=".$_SESSION['Id_Personne'].",
				Id_PrestationRecepteur=".$IdPrestationPersonneConnectee.",
				Id_PoleRecepteur=".$IdPolePersonneConnectee.",
				EtatValidation=-1,
				CommentaireRefus='".addslashes($_POST['commentaire'])."',
				DateReception=".date('Y-m-d')."
				WHERE Id=".$Id." ";

		$resultat=mysqli_query($bdd,$requeteUpdate);
		
		//Mettre à jour matériel/caisse
		$req="SELECT Id_Materiel__Id_Caisse, Type FROM tools_mouvement WHERE Id=".$Id." ";
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
	}
	echo "<script>FermerEtRecharger();</script>";
}

?>

<form id="formulaire" class="test" action="Refuser_MouvementOutilsMasse.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Ids" id="Ids" value="<?php echo $_GET['Ids']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?></td>
							<td width="30%" colspan="6">
								<textarea name="commentaire" id="commentaire" cols="100" rows="4" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>