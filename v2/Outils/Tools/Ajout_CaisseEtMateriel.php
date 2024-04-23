<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Caisse à outils</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Num.value==''){alert('Vous n\'avez pas renseigné le numéro.');return false;}
			else{return true;}
		}
		function Change_CodeArticle()
		{
			if(document.getElementById('Id_CodeArticle').value!=0){
				tab = document.getElementById('Id_CodeArticle').value.split('_');
				if(tab[1]==1){
					document.getElementById('Immo1').style.display="";
					document.getElementById('Immo2').style.display="";
				}
				else{
					document.getElementById('Immo1').style.display="none";
					document.getElementById('Immo2').style.display="none";
				}
			}
		}
		function Change_Location()
		{
			if(document.getElementById('Location').value==1){
				document.getElementById('Location1').style.display="";
			}
			else{
				document.getElementById('Location1').style.display="none";
			}
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Formation/Globales_Fonctions.php");

$TablePrincipale="tools_caisse";

if($_POST)
{
	
	//Affecter la caisse 
	//Récupération de la prestation actuelle de la personne
	$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne($DateJour,$IdPersonneConnectee));
	$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
	if($IdPrestationPersonneConnectee>0){
		$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
	}
	else{
		$IdPolePersonneConnectee=0;
	}
	
	$Id_Prestation=0;
	$Id_Pole=0;
	$Id_Lieu=0;
	$Id_Personne=0;
	if($_POST['affectation']=="site"){
		$tab=explode("_",$_POST['Id_PrestationPole']);
		$Id_Prestation=$tab[0];
		$Id_Pole=$tab[1];
		$Id_Lieu=$_POST['Id_Lieu'];
	}
	elseif($_POST['affectation']=="personne"){
		$Id_Personne=$_POST['Id_Personne'];
		if($Id_Personne>0){
			$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne($DateJour,$Id_Personne));
			$Id_Prestation=$TableauPrestationPolePersonneConnectee[0];
			if($Id_Prestation==0){
				$TableauPrestationPolePersonneConnectee=explode("_",PrestationPoleCompetence_Personne($DateJour,$Id_Personne));
				$Id_Prestation=$TableauPrestationPolePersonneConnectee[0];
				$Id_Pole=$TableauPrestationPolePersonneConnectee[1];
			}
			else{
				$Id_Pole=$TableauPrestationPolePersonneConnectee[1];
			}
		}
	}
	
	$Id_PlateformeNew=0;
	$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
	$Result=mysqli_query($bdd,$req);
	$NbEnreg=mysqli_num_rows($Result);
	if($NbEnreg>0)
	{
		$RowPlateforme=mysqli_fetch_array($Result);
		$Id_PlateformeNew=$RowPlateforme['Id_Plateforme'];
	}
	
	$tabCodeArticle=explode("_",$_POST['Id_CodeArticle']);
	$RequeteInsertUpdate="
		INSERT INTO "
			.$TablePrincipale."
		(
			Id_CaisseType,
			Num,
			NumAAA,
			SN,
			Id_Fournisseur,
			Id_Fabricant,
			Prix,
			Id_FamilleMateriel,
			BonCommande,
			NumFacture,
			Id_CodeArticle,
			NumFicheImmo,DateDebutImmo,DateFinImmo,
			Location,DateDebutLocation,DateFinContratLocation
		)
		VALUES
		(
			'".$_POST['Id_CaisseType']."',
			'".$_POST['Num']."',
			'".Next_CodeGravureMateriel($Id_PlateformeNew)."',
			'".$_POST['SN']."',
			'".$_POST['Id_Fournisseur']."',
			'".$_POST['Id_Fabricant']."',
			'".$_POST['Prix']."',
			'".$_POST['Id_FamilleMateriel']."',
			'".stripslashes($_POST['BonCommande'])."',
			'".addslashes($_POST['NumFacture'])."',
			".$tabCodeArticle[0].",
			'".addslashes($_POST['NumFicheImmo'])."',
			'".TrsfDate_($_POST['DateDebutImmo'])."',
			'".TrsfDate_($_POST['DateFinImmo'])."',
			".$_POST['Location'].",
			'".TrsfDate_($_POST['DateDebutLocation'])."',
			'".TrsfDate_($_POST['DateFinLocation'])."'
		);";
	$ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	$Id=mysqli_insert_id($bdd);
	
	$EtatValidation=1;
	$DateReception=$DateJour;
	$Id_Recepteur=$IdPersonneConnectee;
	$DatePriseEnCompteDemandeur=$DateJour;
	if($Id_PlateformeNew>0){
		//Vérifier si la personne n'est pas MGX ou Informatique 
		$ReqDroits= "
			SELECT
				Id
			FROM
				new_competences_personne_poste_plateforme
			WHERE
				Id_Personne=".$IdPersonneConnectee."
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
				AND Id_Plateforme=".$Id_PlateformeNew." ";
		$ResultDroits=mysqli_query($bdd,$ReqDroits);
		$NbEnregDroits=mysqli_num_rows($ResultDroits);
		
		//Vérifier si prestation ayant le lieu "Magasin" ou "Magasin Paris" ou "Magasin Toulouse"
		$req="SELECT Id 
			FROM tools_lieu 
			WHERE Libelle LIKE 'Magasin%'
			AND Id_Prestation=".$Id_Prestation."
			AND Id_Pole=".$Id_Pole."";
		$ResultLieu=mysqli_query($bdd,$req);
		$NbLieu=mysqli_num_rows($ResultLieu);
		
		//Vérifier si la personne n'est pas le responsable de la prestation
		$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
			AND Id_Prestation=".$Id_Prestation." 
			AND Id_Pole=".$Id_Pole." ";
		$ResultDroits=mysqli_query($bdd,$ReqDroits);
		$NbEnregDroits2=mysqli_num_rows($ResultDroits);
		
		if(($NbEnregDroits==0 || ($NbEnregDroits>0 && $NbLieu==0)) && $NbEnregDroits2==0){
			$EtatValidation=0;
			$Id_Recepteur=0;
			$DatePriseEnCompteDemandeur=date('0001-01-01');
		}
	}
	
	//Ajout du mouvement
	$RequeteMouvement="
		INSERT INTO
			tools_mouvement
		(
			Type,
			TypeMouvement,
			Id_Materiel__Id_Caisse,
			Id_Prestation,
			Id_Pole,
			Id_Lieu,
			Id_Personne,
			Id_Demandeur,
			Id_PrestationDemandeur,
			Id_PoleDemandeur,
			Id_Recepteur,
			Id_PrestationRecepteur,
			Id_PoleRecepteur,
			DateDemande,
			DateReception,
			EtatValidation,
			Id_DemandeurPrisEnCompte,
			DatePriseEnCompteDemandeur
		)
		VALUES
		(
			'1',
			'0',
			'".$Id."',
			'".$Id_Prestation."',
			'".$Id_Pole."',
			'".$Id_Lieu."',
			'".$Id_Personne."',
			'".$IdPersonneConnectee."',
			'".$IdPrestationPersonneConnectee."',
			'".$IdPolePersonneConnectee."',
			'".$Id_Recepteur."',
			'".$IdPrestationPersonneConnectee."',
			'".$IdPolePersonneConnectee."',
			'".$DateJour."',
			'".$DateReception."',
			'".$EtatValidation."',
			'".$Id_Recepteur."',
			'".$DatePriseEnCompteDemandeur."'
		);";
	$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
	
	//Mettre à jour l'affectation dans caisse
	$req="UPDATE tools_caisse 
		SET Id_PrestationT=".$Id_Prestation.", Id_PoleT=".$Id_Pole.", Id_LieuT=".$Id_Lieu.", Id_PersonneT=".$Id_Personne.", 
			DateReceptionT='".$DateReception."', EtatValidationT=".$EtatValidation." 
		WHERE Id=".$Id." ";
	$ResultUpdt=mysqli_query($bdd,$req);
		
	$listeAAATO='<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr><td height="5px"></td></tr>';
	//Affichage du AAATO créé
	$Requete="
		SELECT
			Id,
			(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS Caisse,
			(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FamilleMateriel,
			NumAAA, Num
		FROM
			tools_caisse
		WHERE
			Id='".$Id."';";
	$Result=mysqli_query($bdd,$Requete);
	$Row=mysqli_fetch_array($Result);
	if($_SESSION['Langue']=="FR"){
		$listeAAATO.="<tr><td>".$Row['NumAAA']." créé pour : ".$Row['FamilleMateriel']." ".$Row['Caisse']." n° ".$Row['Num']."</td></tr>";
	}
	else{
		$listeAAATO.="<tr><td>".$Row['NumAAA']." created for : ".$Row['FamilleMateriel']." ".$Row['Caisse']." n° ".$Row['Num']."</td></tr>";
	}
				
	//Création du matériel de la caisse 
	$req="SELECT Id_ModeleMateriel, Quantite FROM tools_caissetype_contenu WHERE Suppr=0 AND Id_CaisseType=".$_POST['Id_CaisseType']." ";
	$ResultContenuCaisse=mysqli_query($bdd,$req);
	while($RowContenuCaisse=mysqli_fetch_array($ResultContenuCaisse))
	{
		for($j=1;$j<=$RowContenuCaisse['Quantite'];$j++){
			$RequeteAjoutNouveauContenu="INSERT INTO tools_materiel (Id_ModeleMateriel,NumAAA,BonCommande,Id_PersonneMAJ,DateMAJ) VALUES 
										('".$RowContenuCaisse['Id_ModeleMateriel']."','".Next_CodeGravureMateriel($Id_PlateformeNew)."','".$_POST['BonCommande']."','".$IdPersonneConnectee."','".date('Y-m-d')."')";
			$ResultAjoutNouveauContenu=mysqli_query($bdd,$RequeteAjoutNouveauContenu);
			
			$Id_Materiel=mysqli_insert_id($bdd);
			
			//Ajout du mouvement par défaut (magasin)
			$RequeteMouvement="
				INSERT INTO
					tools_mouvement
				(
					Type,
					TypeMouvement,
					Id_Materiel__Id_Caisse,
					Id_Caisse,
					Id_Demandeur,
					Id_PrestationDemandeur,
					Id_PoleDemandeur,
					Id_Recepteur,
					Id_PrestationRecepteur,
					Id_PoleRecepteur,
					DateDemande,
					DateReception,
					EtatValidation
				)
				VALUES
				(
					'0',
					'0',
					'".$Id_Materiel."',
					'".$Id."',
					'".$IdPersonneConnectee."',
					'".$IdPrestationPersonneConnectee."',
					'".$IdPolePersonneConnectee."',
					'".$Id_Recepteur."',
					'".$IdPrestationPersonneConnectee."',
					'".$IdPolePersonneConnectee."',
					'".$DateJour."',
					'".$DateReception."',
					'".$EtatValidation."'
				);";
			$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
			
			//Mettre à jour l'affectation dans matériel
			$req="UPDATE tools_materiel 
				SET Id_PrestationT=".$Id_Prestation.", Id_PoleT=".$Id_Pole.", Id_LieuT=".$Id_Lieu.", Id_PersonneT=".$Id_Personne.", 
					Id_CaisseT=".$Id.", DateReceptionT='".$DateReception."', EtatValidationT=".$EtatValidation." 
				WHERE Id=".$Id_Materiel." ";
			$ResultUpdt=mysqli_query($bdd,$req);
			
			//Affichage du AAATO créé
			if($_SESSION['Langue']=="FR"){
				$Requete="
					SELECT
						Id,
						(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
						NumAAA
					FROM
						tools_materiel
					WHERE
						Id='".$Id_Materiel."';";
				$Result=mysqli_query($bdd,$Requete);
				$Row=mysqli_fetch_array($Result);
				$listeAAATO.="<tr><td>".$Row['NumAAA']." créé pour le modèle de matériel ".$Row['LIBELLE_MODELEMATERIEL']."</td></tr>";
			}
			else{
				$Requete="
					SELECT
						Id,
						(SELECT LibelleEN FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
						NumAAA
					FROM
						tools_materiel
					WHERE
						Id='".$Id_Materiel."';";
				$Result=mysqli_query($bdd,$Requete);
				$Row=mysqli_fetch_array($Result);
				$listeAAATO.="<tr><td>".$Row['NumAAA']." created for the hardware model ".$Row['LIBELLE_MODELEMATERIEL']."</td></tr>";
			}
		}
	}
	$listeAAATO.='<tr><td height="5px"></td></tr></table>';
	echo $listeAAATO;
	
	if($_POST['affectation']=="personne"){
		if($Id_Personne>0){
			//Editer le document de pret du matériel 
			echo "<script>window.open('EditerPretMateriel.php?laDate=".date('Y-m-d')."&Id=".$Id_Personne."','Fiche_PretMateriel','status=no,menubar=no,width=20,height=20');</script>";
		}
	}
	echo "<script>Recharger('Materiel');</script>";
}
else{
?>
	<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
	<table style="width:95%; height:95%; align:center;" class="TableCompetences">
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Family of material";}?> : </td>
			<td>
				<select name="Id_FamilleMateriel">
				<?php
				$RequeteFamilleMateriel="
					SELECT
						Id,
						Libelle
					FROM
						tools_famillemateriel
					WHERE
						Suppr=0
					AND Id_TypeMateriel=-1
					ORDER BY
						Libelle ASC";
				$ResultFamilleMateriel=mysqli_query($bdd,$RequeteFamilleMateriel);
				$i=0;
				while($RowFamilleMateriel=mysqli_fetch_array($ResultFamilleMateriel))
				{
					echo "<option value='".$RowFamilleMateriel['Id']."'";
					echo ">".$RowFamilleMateriel['Libelle']."</option>\n";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de caisse";}else{echo "Kind of toolbox";}?> : </td>
			<td>
				<select name="Id_CaisseType">
				<?php
				$RequeteTypeCaisse="
					SELECT
						Id,
						Libelle
					FROM
						tools_caissetype
					WHERE
						Suppr=0
					AND 
						Id_Plateforme IN (
							SELECT DISTINCT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
						)
					ORDER BY
						Libelle ASC";
				$ResultTypeCaisse=mysqli_query($bdd,$RequeteTypeCaisse);
				while($RowTypeCaisse=mysqli_fetch_array($ResultTypeCaisse))
				{
					echo "<option value='".$RowTypeCaisse['Id']."'";
					echo ">".$RowTypeCaisse['Libelle']."</option>\n";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?> : </td>
			<td>
				<select name="Id_Fabricant">
				<?php
				$RequeteFabricant="
					SELECT
						Id,
						Libelle
					FROM
						tools_tiers
					WHERE
						Type=1
						AND Suppr=0
					ORDER BY
						Libelle ASC";
				$ResultFabricant=mysqli_query($bdd,$RequeteFabricant);
				while($RowFabricant=mysqli_fetch_array($ResultFabricant))
				{
					echo "<option value='".$RowFabricant['Id']."'";
					echo ">".$RowFabricant['Libelle']."</option>\n";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fournisseur";}else{echo "Provider";}?> : </td>
			<td>
				<select name="Id_Fournisseur">
				<?php
				$RequeteFournisseur="
					SELECT
						Id,
						Libelle
					FROM
						tools_tiers
					WHERE
						Type=2
						AND Suppr=0
					ORDER BY
						Libelle ASC";
				$ResultFournisseur=mysqli_query($bdd,$RequeteFournisseur);
				while($RowFournisseur=mysqli_fetch_array($ResultFournisseur))
				{
					echo "<option value='".$RowFournisseur['Id']."'";
					echo ">".$RowFournisseur['Libelle']."</option>\n";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Numéro";}else{echo "Num";}?> : </td>
			<td><input name="Num" size="10" type="text" value=""></td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?> : </td>
			<td><input name="SN" size="25" type="text" value=""></td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?> : </td>
			<td><input name="Prix" size="10" onKeyUp="nombre(this)" type="text" value=""></td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?> : </td>
			<td><input name="BonCommande" size="25" type="text" value=""></td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Achat / Location";}else{echo "Purchase / Rental";}?> : </td>
			<td>
				<select name="Location" id="Location" onclick="Change_Location();">
					<option value="0" ><?php if($LangueAffichage=="FR"){echo "Achat";}else{echo "Purchase";}?></option>
					<option value="1" ><?php if($LangueAffichage=="FR"){echo "Location";}else{echo "Rental";}?></option>
				</select>
			</td>
		</tr>
		<tr style="display:none" id="Location1" >
			<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début contrat<br> de location";}else{echo "Rental contract<br> start date";}?> : </td>
			<td><input name='DateDebutLocation' size='15' type='date' value=''></td>
			<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date fin contrat de location";}else{echo "Rental contract end date";}?> : </td>
			<td><input name='DateFinLocation' size='15' type='date' value=''></td>
		</tr>
		<tr>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code article";}else{echo "Item code";}?> : </td>
			<td>
				<select name="Id_CodeArticle" id="Id_CodeArticle" onclick="Change_CodeArticle();">
					<option value="0"></option>
				<?php
				$RequeteCodeArticle="
					SELECT
						Id,CodeArticle,Immo
					FROM
						tools_codearticle
					WHERE
						Suppr=0
					ORDER BY
						CodeArticle ASC";
				$ResultCodeArticle=mysqli_query($bdd,$RequeteCodeArticle);
				while($RowCodeArticle=mysqli_fetch_array($ResultCodeArticle))
				{
					echo "<option value='".$RowCodeArticle['Id']."_".$RowCodeArticle['Immo']."'";
					echo ">".$RowCodeArticle['CodeArticle']."</option>\n";
				}
				?>
				</select>
			</td>
		</tr>
		<tr style="display:none" id="Immo1">
			<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° fiche immobilisation";}else{echo "Asset sheet no.";}?> : </td>
			<td><input name='NumFicheImmo' size='15' type='text' value=''></td>
		</tr>
		<tr style="display:none" id="Immo2">
			<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début <br>immobilisation";}else{echo "Start date <br>of immobilization";}?> : </td>
			<td><input name='DateDebutImmo' size='15' type='date' value=''></td>
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date fin immobilisation";}else{echo "End date of immobilization";}?> : </td>
			<td><input name='DateFinImmo' size='15' type='date' value=''></td>
		</tr>
		<tr class="TitreColsUsers">
			<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° facture";}else{echo "Invoice number";}?> : </td>
			<td><input name='NumFacture' size='15' type='text' value=''></td>
		</tr>
		<tr>
				<td height="5"></td>
			</tr>
			
			<tr class="TitreColsUsers" style="display:none;">
				<td class="Libelle" colspan="2">
					<input type="radio" name="affectation" onchange="AfficherAffectation('site')" value="site" checked><?php if($LangueAffichage=="FR"){echo "Site";}else{echo "Site";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('personne')" value="personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
			<tr style="display:none;">
				<td height="5"></td>
			</tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_Plateforme" id="Id_Plateforme" style="width:200px" onchange="RechargerPrestation()">
					<option value="0"></option>
						<?php
							$Id_Plateforme=$_SESSION['FiltreToolsSuivi_Plateforme'];
							
							$requetePlat="SELECT DISTINCT Id_Plateforme AS Id,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
								FROM 
								(SELECT DISTINCT Id_Plateforme
								FROM new_competences_prestation
								WHERE Active=0
								AND Id NOT IN (
									SELECT Id_Prestation
									FROM new_competences_pole    
									WHERE Actif=0
								)
								
								UNION 
								
								SELECT DISTINCT new_competences_prestation.Id_Plateforme
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND Active=0
									AND Actif=0) AS TAB
								WHERE Id_Plateforme NOT IN (11,14)
								AND Id_Plateforme IN (SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
								)
								ORDER BY Libelle ASC";
								
							$resultsPlat=mysqli_query($bdd,$requetePlat);
							while($rowPlat=mysqli_fetch_array($resultsPlat))
							{
								$selected="";
								if($Id_Plateforme==0){$Id_Plateforme=$rowPlat['Id'];}
								if($Id_Plateforme==$rowPlat['Id']){$selected="selected";}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPrestation"><td height="4"></td></tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
				<td>
					<select name="Id_PrestationPole" id="Id_PrestationPole" class="Id_PrestationPole" style="width:300px" onchange="RechargerLieu()">
						<?php
							$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
								FROM new_competences_prestation
								WHERE Active=0
								AND Id NOT IN (
									SELECT Id_Prestation
									FROM new_competences_pole    
									WHERE Actif=0
								)
								AND (SELECT COUNT(Id) 
									FROM tools_lieu 
									WHERE Suppr=0 
									AND tools_lieu.Id_Prestation=new_competences_prestation.Id
									AND Libelle LIKE 'Magasin%')>0
								UNION 
								
								SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
									new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND Active=0
									AND Actif=0
									AND (SELECT COUNT(Id) 
										FROM tools_lieu 
										WHERE Suppr=0 
										AND tools_lieu.Id_Prestation=new_competences_prestation.Id
										AND tools_lieu.Id_Pole=new_competences_pole.Id
										AND Libelle LIKE 'Magasin%')>0
									
								ORDER BY Libelle, LibellePole";
							$resultsite=mysqli_query($bdd,$requeteSite);
							$i=0;
							
							while($rowsite=mysqli_fetch_array($resultsite))
							{
								$selected="";
								if($Id_PrestaPole==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}
								echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
								$pole="";
								if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
								echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
								echo "<script>Liste_PrestaPole[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
								$i++;
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPrestation"><td height="4"></td></tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu :";}else{echo "Place :";} ?></td>
				<td>
					<select name="Id_Lieu" id="Id_Lieu" class="Id_Lieu" style="width:200px">
						<?php
							$requeteLieu="SELECT Id, Id_Prestation, Id_Pole, Libelle
								FROM tools_lieu
								WHERE Suppr=0
								AND Libelle LIKE 'Magasin%'
								ORDER BY Libelle ";
							$resultlieu=mysqli_query($bdd,$requeteLieu);
							$i=0;
							$Id_Lieu=0;
							while($rowLieu=mysqli_fetch_array($resultlieu))
							{
								echo "<script>Liste_Lieu[".$i."] = new Array(".$rowLieu['Id'].",".$rowLieu['Id_Prestation'].",'".str_replace("'"," ",$rowLieu['Id_Pole'])."','".str_replace("'"," ",$rowLieu['Libelle'])."');</script>";
								$i++;
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPersonne">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_PlateformePersonne" id="Id_PlateformePersonne" style="width:200px" onchange="RechargerPersonne()">
					<option value="0"></option>
						<?php
							$Id_PlateformePersonne=$_SESSION['FiltreToolsSuivi_Plateforme'];
							$requetePlat="SELECT Id_Plateforme AS Id,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
								FROM (
								SELECT DISTINCT 
								IF((
									SELECT COUNT(rh_personne_mouvement.Id)
									FROM rh_personne_mouvement
									WHERE Suppr=0
									AND Id_Personne=new_rh_etatcivil.Id 
									AND EtatValidation=1
									AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								)>0,
								(
									SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
									FROM rh_personne_mouvement
									WHERE Suppr=0
									AND Id_Personne=new_rh_etatcivil.Id 
									AND EtatValidation=1
									AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
									LIMIT 1
								)
								,
								(
									SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
									FROM new_competences_personne_prestation
									WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
									AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
									LIMIT 1
								)) AS Id_Plateforme
								FROM new_rh_etatcivil 
								WHERE (
									SELECT COUNT(rh_personne_mouvement.Id)
									FROM rh_personne_mouvement
									WHERE Suppr=0
									AND Id_Personne=new_rh_etatcivil.Id 
									AND EtatValidation=1
									AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								)>0
								OR
								(
									SELECT COUNT(new_competences_personne_prestation.Id)
									FROM new_competences_personne_prestation
									WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
									AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
								)>0) AS TAB
								WHERE Id_Plateforme NOT IN (11,14)
								AND Id_Plateforme IN (SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
								)
								ORDER BY Libelle ASC";

							$resultsPlat=mysqli_query($bdd,$requetePlat);
							while($rowPlat=mysqli_fetch_array($resultsPlat))
							{
								$selected="";
								if($Id_PlateformePersonne==0){$Id_PlateformePersonne=$rowPlat['Id'];}
								if($Id_PlateformePersonne==$rowPlat['Id']){$selected="selected";}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPersonne"><td height="4"></td></tr>
			<tr class="trPersonne">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
				<td>
					<select name="Id_Personne" id="Id_Personne">
					<?php
					$rq="SELECT Id, Personne, Id_Plateforme
						FROM 
						(
						SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
						IF((
							SELECT COUNT(rh_personne_mouvement.Id)
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND Id_Personne=new_rh_etatcivil.Id 
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
						)>0,
						(
							SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND Id_Personne=new_rh_etatcivil.Id 
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							LIMIT 1
						)
						,
						(
							SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
							FROM new_competences_personne_prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
							LIMIT 1
						)) AS Id_Plateforme
						FROM new_rh_etatcivil 
						WHERE (
							SELECT COUNT(rh_personne_mouvement.Id)
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND Id_Personne=new_rh_etatcivil.Id 
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
						)>0
						OR
						(
							SELECT COUNT(new_competences_personne_prestation.Id)
							FROM new_competences_personne_prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
						)>0) AS TAB 
						WHERE Id_Plateforme NOT IN (11,14)
						AND Id_Plateforme IN (SELECT Id_Plateforme
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
						)
						ORDER BY Personne ASC";
					$resultpersonne=mysqli_query($bdd,$rq);
					$Id_Personne=0;
					$i=0;
					while($rowpersonne=mysqli_fetch_array($resultpersonne))
					{
						$selected="";
						if($Id_Personne==0){$Id_Personne=$rowpersonne['Id'];$selected = "selected";}
						echo "<option value='".$rowpersonne['Id']."' ".$selected.">".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
						echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."',".$rowpersonne['Id_Plateforme'].");</script>";
						$i++;
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="trCaisse">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?></td>
				<td>
					<select name="Id_Caisse" id="Id_Caisse">
					<?php
					$rq="SELECT Id, Num, 
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS CaisseType
						FROM tools_caisse 
						WHERE Suppr=0
						ORDER BY CaisseType ASC , tools_caisse.Num ASC";
					$resultcaisse=mysqli_query($bdd,$rq);
					$Id_Caisse=0;
					while($rowCaisse=mysqli_fetch_array($resultcaisse))
					{
						$selected="";
						if($Id_Caisse==0){$Id_Caisse=$rowCaisse['Id'];$selected = "selected";}
						echo "<option value='".$rowCaisse['Id']."' ".$selected.">".str_replace("'"," ",$rowCaisse['CaisseType'])." n° ".$rowCaisse['Num']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
		<tr>
			<td colspan=2 align="center">
				<input class="Bouton" type="submit"
				<?php
					if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
				?>
				>
			</td>
		</tr>
	</table>
	</form>
</body>
<?php
echo "<script>RechargerPrestation();</script>";
echo "<script>RechargerPersonne();</script>";
echo "<script>AfficherAffectation('site');</script>";
}
?>
</html>