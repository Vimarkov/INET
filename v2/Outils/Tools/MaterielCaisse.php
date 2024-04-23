<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function EditerPretMateriel(Id)
		{
			window.open("EditerPretMateriel2.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");
		}
		function EditerInventaire(Id)
		{
			window.open("EditerInventaire.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");
		}
		function EditerInventairePeriodique(Id)
		{
			window.open("EditerInventairePeriodique.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");

$Requete="
	SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) AS Caisse
	FROM tools_caisse
	LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
	WHERE tools_caisse.Id='".$_GET['Id_Caisse']."';";
$Result=mysqli_query($bdd,$Requete);
$Row=mysqli_fetch_array($Result);
?>
<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
	<input type="hidden" name="Page" name="Page" value="<?php if(isset($_GET['Page'])){echo $_GET['Page'];} ?>">
	<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#22b63d;">
		<tr>
			<td class="TitrePage">
			<?php
			if($LangueAffichage=="FR"){echo "Liste du matériel de la caisse ".$Row['Caisse'];}else{echo "List of body equipment ".$Row['Caisse']." ";}
			?>
			</td>
		</tr>
	</table><br>
<table style="width:100%; border-spacing:0; align:center;">
	<?php
		//PARTIE OUTILS DE LA REQUETE
	$Requete2="
		SELECT
			tools_materiel.Id AS ID,
			'Outils' AS TYPESELECT,
			NumAAA,
			NumFicheImmo,
			SN,
			Prix,
			IF(tools_famillemateriel.Id_TypeMateriel=".$TypeTelephone.",NumTelephone,
				IF(tools_famillemateriel.Id_TypeMateriel=".$TypeClef.",NumClef,
					IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMaqueDeControle.",NumMC,
						IF(tools_famillemateriel.Id_TypeMateriel=".$TypeInformatique.",NumPC,
							IF(tools_famillemateriel.Id_TypeMateriel=".$TypeVehicule.",Immatriculation,
								IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMacaron.",ImmatriculationAssociee,'')
							)
						)
					)
				)
			) AS Num,
			Designation,
			tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
			(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
			tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
			(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
			(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
			(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
			(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.EtatValidation,
						(
						SELECT EtatValidation
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS TransfertEC,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,(SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme),
						(
							SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
							FROM tools_mouvement
							LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
							WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
					) 
				FROM tools_mouvement AS TAB_Mouvement
				LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS LIBELLE_PLATEFORME,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,(SELECT Libelle FROM new_competences_prestation WHERE TAB_Mouvement.Id_Prestation=new_competences_prestation.Id),
						(
							SELECT (SELECT Libelle FROM new_competences_prestation WHERE tools_mouvement.Id_Prestation=new_competences_prestation.Id)
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						))
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS LIBELLE_PRESTATION,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Prestation,
						(
							SELECT tools_mouvement.Id_Prestation
							FROM tools_mouvement
							WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
						)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS Id_Prestation,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,(SELECT Libelle FROM new_competences_pole WHERE TAB_Mouvement.Id_Pole=new_competences_pole.Id),
							(
								SELECT (SELECT Libelle FROM new_competences_pole WHERE tools_mouvement.Id_Pole=new_competences_pole.Id)
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
						)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS LIBELLE_POLE,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Pole,
							(
								SELECT tools_mouvement.Id_Pole
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
						)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS Id_Pole,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,(SELECT Libelle FROM tools_lieu WHERE TAB_Mouvement.Id_Lieu=tools_lieu.Id),
							(
								SELECT (SELECT Libelle FROM tools_lieu WHERE tools_mouvement.Id_Lieu=tools_lieu.Id)
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
						)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS LIBELLE_LIEU,
			(
				SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
				FROM tools_mouvement
				LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_CAISSETYPE,
			(
				SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS Id_Caisse,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE TAB_Mouvement.Id_Personne=new_rh_etatcivil.Id) ,
							(
								SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE tools_mouvement.Id_Personne=new_rh_etatcivil.Id)
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
						)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS NOMPRENOM_PERSONNE,
			(
				SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Personne ,
							(
								SELECT tools_mouvement.Id_Personne
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)
						)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS Id_Personne
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
			LEFT JOIN
				tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
			WHERE
				tools_materiel.Suppr=0 ";

	$Requete.=" AND (SELECT Id_Caisse FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_GET['Id_Caisse']." ";

		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse=" UNION ALL
			SELECT Id,
			'Caisse' AS TYPESELECT,
			NumAAA AS NumAAA,
			NumFicheImmo,
			SN AS SN,
			Prix,
			Num AS Num,
			'' AS Designation,
			-1 AS Id_TYPEMATERIEL,
			'Caisse' AS TYPEMATERIEL,
			(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
			(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
			(SELECT DateReception FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND tools_mouvement.EtatValidation IN (0,1) AND Suppr=0 AND Type=1 ORDER BY DateReception ASC LIMIT 1) AS DateReception,
			(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
			(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND Suppr=0 AND Type=1 ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
			(
				SELECT EtatValidation
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS TransfertEC,
			(
				SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_PLATEFORME,
			(
				SELECT (SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE tools_mouvement.Id_Prestation=new_competences_prestation.Id)
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_PRESTATION,
			(
				SELECT tools_mouvement.Id_Prestation
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS Id_Prestation,
			(
				SELECT (SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE tools_mouvement.Id_Pole=new_competences_pole.Id)
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_POLE,
			(
				SELECT tools_mouvement.Id_Pole
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS Id_Pole,
			(
				SELECT (SELECT tools_lieu.Libelle FROM tools_lieu WHERE tools_mouvement.Id_Lieu=tools_lieu.Id)
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_LIEU,
			(
				SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
				FROM tools_mouvement
				LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_CAISSETYPE,
			tools_caisse.Id AS Id_Caisse,
			(
				SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE tools_mouvement.Id_Personne=new_rh_etatcivil.Id)
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS NOMPRENOM_PERSONNE,
			(
				SELECT tools_mouvement.Id_Personne
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS Id_Personne
			";
		$RequeteCaisse="FROM
			tools_caisse
		WHERE 
			tools_caisse.Suppr=0 ";
		$RequeteCaisse.=" AND Id = ".$_GET['Id_Caisse']." ";


		$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse);
		$NbEnreg=mysqli_num_rows($Result);
	?>
	<tr class="TitreColsUsers">
		<td align="right">
			&bull; <a href="javascript:EditerPretMateriel('<?php echo $_GET['Id_Caisse'];?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0829-001 - Inventaire Général";}else{echo "D-0829-001 - General Inventory";} ?></a>&nbsp;&nbsp;&nbsp;<br>
			&bull; <a href="javascript:EditerInventaire('<?php echo $_GET['Id_Caisse'];?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0829-002 - Inventaire contenant de transfert";}else{echo "D-0829-002 - Inventaire contenant de transfert";} ?></a>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr class="TitreColsUsers">
		<td align="right">
			&bull; <a href="javascript:EditerInventairePeriodique('<?php echo $_GET['Id_Caisse'];?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0829-003 - Inventaire visuel et périodique des contenants";}else{echo "D-0829-003 - Visual and periodic inventory of containers";} ?></a>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table width="100%">
				<tr>
					<td width="10"></td>
					<td width="100%">
						<table class="TableCompetences" width="100%">
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Désignation";}else{echo "Designation";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date réception";}else{echo "Reception date";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Plateform";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?></td>
							</tr>
							
						<?php
						$SommePrix=0;
						if($NbEnreg>0)
						{
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$SommePrix+=$Row['Prix'];
								$LIBELLE_POLE="";
								if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td  <?php if($Row['TransfertEC']==0){echo "style='border:dotted #22b63d 5px' id='leHover'";} ?>><?php 
								if($Row['TYPESELECT']=="Outils"){
									$leType=0;
									$leId=$Row['ID'];
									if($Row['Id_Caisse']>0){
										$leId=$Row['Id_Caisse'];
										$leType=1;
									}
								}
								else{
									$leType=1;
									$leId=$Row['ID'];
								}
								
								$req="SELECT 
									tools_mouvement.DateReception,
									(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
									(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
									(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
									(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
									(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
									FROM tools_mouvement
									WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=".$leType." AND tools_mouvement.Id_Materiel__Id_Caisse=".$leId."
									ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

								$ResultTransfertEC=mysqli_query($bdd,$req);
								$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
								
								if($NbEnregTransfertEC>0)
								{
									$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
									
									$LIBELLE_POLE_Transfert="";
									if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
								
									echo "<span><b>Transfert en cours</b>
									</span>";
								}
								
								echo $Row['NumAAA'];?></td>
								<td><?php echo $Row['SN'];?></td>
								<td><?php echo stripslashes($Row['TYPEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['FAMILLEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['Designation']);?></td>
								<td><?php echo stripslashes($Row['Num']);?></td>
								<td><?php echo AfficheDateJJ_MM_AAAA($Row['DateReception']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_FABRICANT']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_PLATEFORME']);?></td>
								<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_LIEU']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_CAISSETYPE']);?></td>
								<td <?php if($Row['Remarque']<>""){echo "id='leHover'";} ?>>
								<?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);
								if($Row['Remarque']<>""){
									echo "<img width='10px' src='../../Images/etoile.png' border='0'>";
									echo "<span>".stripslashes($Row['Remarque'])."</span>";
								}
								?>
								</td>
								
								<td><?php
								
								echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td>
								<td><?php echo stripslashes($Row['Prix']);?></td>
							</tr>
						<?php
							}
							
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td colspan="14"></td>
							<td bgcolor="#22b63d"><?php if($LangueAffichage=="FR"){echo "Prix total";}else{echo "Total price";}?></td>
							<td bgcolor="#22b63d"><?php echo $SommePrix;?></td>
						</tr>
						<?php
						}		
						mysqli_free_result($Result);	// Libération des résultats
						?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>