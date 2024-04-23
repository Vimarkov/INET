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
		function OuvreFenetreExcel(Id)
			{window.open("DeclarationPerte.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");

$SommePrix=0;
$Id=0;
$Type=-1;
if($_POST){$Id=$_POST['Id'];$Type=$_POST['Type'];}
else{$Id=$_GET['Id'];$Type=$_GET['Type'];}
	if($Type==0){
		$ListeChamps="";
		$i=0;
		foreach($Tableau_ChampsMateriel as $TableauValeur)
		{
			$ListeChamps.=$TableauValeur[0].",";
			$i+=1;
		}
		$Requete="
				SELECT
					NumAAA,
					(SELECT (SELECT (SELECT LIbelle FROM tools_typemateriel WHERE Id=Id_TypeMateriel) FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) AS TypeMateriel,
					(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) AS FamilleMateriel,
					(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
					(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS ID_FAMILLEMATERIEL,
					(SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE Id=(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel)) AS ID_TYPEMATERIEL,
					Id_ModeleMateriel,
					(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
					(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
					".$ListeChamps."
					(SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) AS Id_TypeMateriel,
					Remarques,
					NumFacture,
					Id_CodeArticle,
					(SELECT Immo FROM tools_codearticle WHERE Id=Id_CodeArticle) AS Immo,
					(SELECT CodeArticle FROM tools_codearticle WHERE Id=Id_CodeArticle) AS CodeArticle,
					NumFicheImmo,DateDebutImmo,DateFinImmo,
					Location,DateDebutLocation,DateFinContratLocation,
					InfosTechnique
				FROM
					tools_materiel
				WHERE
					Id='".$Id."';";
	}
	else{
		$Requete="
				SELECT
					NumAAA,
					Num,SN,
					0 AS Id_TypeMateriel,
					(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType ) AS CaisseType,
					Id, Id_CaisseType, BonCommande,
					(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
					(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
					NumFacture,
					Id_CodeArticle,Prix,
					(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FamilleMateriel,
					(SELECT Immo FROM tools_codearticle WHERE Id=Id_CodeArticle) AS Immo,
					(SELECT CodeArticle FROM tools_codearticle WHERE Id=Id_CodeArticle) AS CodeArticle,
					NumFicheImmo,DateDebutImmo,DateFinImmo,
					Location,DateDebutLocation,DateFinContratLocation
				FROM
					tools_caisse
				WHERE
					Id='".$Id."';";
	}
		
	$Result=mysqli_query($bdd,$Requete);
	$RowMateriel=mysqli_fetch_array($Result);
?>
<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="hidden" name="Type" value="<?php echo $Type; ?>">
<input type="hidden" name="Langue" value="<?php echo $_SESSION['Langue'];?>">
<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffffff;">
	<tr>
		<td class="TitrePage">
		<?php
		if($LangueAffichage=="FR"){echo "Historique ".$RowMateriel['NumAAA'];}else{echo "Historical ".$RowMateriel['NumAAA'];}
		?>
		</td>
	</tr>
</table><br>
<table width="100%">
	<tr>
		<td colspan="2">
			<?php
				if($Type==0){
			?>
					<table style="width:100%; height:100%; align:center;" class="TableCompetences">
						<tr>
							<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Numéro";}else{echo "Num";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['NumAAA'];?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de matériel";}else{echo "Kind of material";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['TypeMateriel'];?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Family of material";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['FamilleMateriel'];?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Modèle de matériel";}else{echo "Model of material";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['LIBELLE_MODELEMATERIEL'];?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['LIBELLE_FABRICANT'];?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fournisseur";}else{echo "Provider";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['LIBELLE_FOURNISSEUR'];?></td>
						</tr>
						
						<?php
						$IndiceLangue=1;
						if($LangueAffichage!="FR"){$IndiceLangue=2;}
						
						$i=0;
						foreach($Tableau_ChampsMateriel as $TableauValeur) 
						{
							$tab=$TableauValeur[4];
							$existe=0;
							foreach($tab as $Id_Type) 
							{
								if($Id_Type==$RowMateriel['ID_TYPEMATERIEL']){$existe=1;}
							}
							if($existe==1){
								
								$ValeurModif=stripslashes($RowMateriel[$TableauValeur[0]]);
								echo "
									<tr>
										<td class='Libelle'>".$TableauValeur[$IndiceLangue]." : </td>
										<td>".$ValeurModif."</td>
									</tr>
									<tr>
										<td height='5'></td>
									</tr>
								";
							}
						}
						?>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Achat / Location";}else{echo "Purchase / Rental";}?> : </td>
							<td>
								<?php 
									if($RowMateriel['Location']==0){
										if($LangueAffichage=="FR"){echo "Achat";}else{echo "Purchase";}
									}
									else{
										if($LangueAffichage=="FR"){echo "Location";}else{echo "Rental";}
									}
								?>
							</td>
							<td class='Libelle' style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php if($LangueAffichage=="FR"){echo "Date début contrat<br> de location";}else{echo "Rental contract<br> start date";}?> : </td>
							<td style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateDebutLocation']);?></td>
							<td class='Libelle' style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php if($LangueAffichage=="FR"){echo "Date fin contrat de location";}else{echo "Rental contract end date";}?> : </td>
							<td style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateFinContratLocation']);?></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code article";}else{echo "Item code";}?> : </td>
							<td><?php echo stripslashes($RowMateriel['CodeArticle']);?></td>
						</tr>
						<tr style="display:<?php if($RowMateriel['Immo']==0){echo "none";} ?>" >
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° fiche immobilisation";}else{echo "Asset sheet no.";}?> : </td>
							<td><?php echo stripslashes($RowMateriel['NumFicheImmo']);?></td>
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début <br>immobilisation";}else{echo "Start date <br>of immobilization";}?> : </td>
							<td><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateDebutImmo']);?></td>
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date fin immobilisation";}else{echo "End date of immobilization";}?> : </td>
							<td><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateFinImmo']);?></td>
						</tr>
						<tr>
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° facture";}else{echo "Invoice number";}?> : </td>
							<td><?php echo stripslashes($RowMateriel['NumFacture']);?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Remarques";}else{echo "Remarks";}?> : </td>
							<td><?php echo nl2br(stripslashes($RowMateriel['Remarques']));?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Infos techniques";}else{echo "Technical informations";}?> : </td>
							<td><?php echo nl2br(stripslashes($RowMateriel['InfosTechnique']));?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
					</table>
			<?php
					
				}
				else{
					$SommePrix=$RowMateriel['Prix'];
			?>
					<table style="width:100%; height:100%; align:center;" class="TableCompetences">
						<tr>
							<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Numéro";}else{echo "Num";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['NumAAA'];?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Family of material";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['FamilleMateriel']; ?></td>
							<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de caisse";}else{echo "Kind of toolbox";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['CaisseType']." n° ".$RowMateriel['Num']; ?></td>
							<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?> : </td>
							<td width="30%"><?php echo stripslashes($RowMateriel['BonCommande']);?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['LIBELLE_FABRICANT'];?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fournisseur";}else{echo "Provider";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['LIBELLE_FOURNISSEUR'];?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['SN'];?></td>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?> : </td>
							<td width="20%"><?php echo $RowMateriel['Prix'];?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Achat / Location";}else{echo "Purchase / Rental";}?> : </td>
							<td>
								<?php 
									if($RowMateriel['Location']==0){
										if($LangueAffichage=="FR"){echo "Achat";}else{echo "Purchase";}
									}
									else{
										if($LangueAffichage=="FR"){echo "Location";}else{echo "Rental";}
									}
								?>
							</td>
							<td class='Libelle' style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php if($LangueAffichage=="FR"){echo "Date début contrat<br> de location";}else{echo "Rental contract<br> start date";}?> : </td>
							<td style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateDebutLocation']);?></td>
							<td class='Libelle' style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php if($LangueAffichage=="FR"){echo "Date fin contrat de location";}else{echo "Rental contract end date";}?> : </td>
							<td style="display:<?php if($RowMateriel['Location']==0){echo "none";} ?>" ><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateFinContratLocation']);?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code article";}else{echo "Item code";}?> : </td>
							<td><?php echo stripslashes($RowMateriel['CodeArticle']);?></td>
						</tr>
						<tr style="display:<?php if($RowMateriel['Immo']==0){echo "none";} ?>" >
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° fiche immobilisation";}else{echo "Asset sheet no.";}?> : </td>
							<td><?php echo stripslashes($RowMateriel['NumFicheImmo']);?></td>
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début <br>immobilisation";}else{echo "Start date <br>of immobilization";}?> : </td>
							<td><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateDebutImmo']);?></td>
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date fin immobilisation";}else{echo "End date of immobilization";}?> : </td>
							<td><?php echo AfficheDateJJ_MM_AAAA($RowMateriel['DateFinImmo']);?></td>
						</tr>
						<tr>
							<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° facture";}else{echo "Invoice number";}?> : </td>
							<td><?php echo stripslashes($RowMateriel['NumFacture']);?></td>
						</tr>
					</table>
			<?php
				}
			?>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td width="50%" valign="top">
			<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr>
					<td style="background-color:#feff19;" class="Libelle" align="center" colspan="7"><?php if($LangueAffichage=="FR"){echo "TRANSFERT DE MATERIEL";}else{echo "MATERIAL TRANSFER";} ?></td>
				</tr>
				<tr>
					<td height="7"></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Plateform";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Note";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Etat transfert";}else{echo "Transfer status";}?></td>
				</tr>
				<?php
					$req="SELECT 
						IF(EtatValidation<>-1,DateReception,DateDemande) AS DateReception,Id_Caisse,Id,Commentaire,CommentaireRefus,
						Id_Caisse,
						IF(Id_Caisse>0,
							(
								SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
								FROM tools_mouvement AS TAB_Mouvement
								LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							)
						,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation)
						) AS Plateforme,
							IF(Id_Caisse>0,
							(
								SELECT new_competences_prestation.Libelle
								FROM tools_mouvement AS TAB_Mouvement
								LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							)
						,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
						) AS Prestation,
						EtatValidation AS TransfertEC,
						EtatValidation,
						IF(Id_Caisse>0,
						(
							SELECT new_competences_pole.Libelle
							FROM tools_mouvement AS TAB_Mouvement
							LEFT JOIN new_competences_pole ON TAB_Mouvement.Id_Pole=new_competences_pole.Id
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)) AS Pole,
					IF(Id_Caisse>0,
						(
							SELECT tools_lieu.Libelle
							FROM tools_mouvement AS TAB_Mouvement
							LEFT JOIN tools_lieu ON TAB_Mouvement.Id_Lieu=tools_lieu.Id
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu)) AS Lieu,
						(SELECT Num FROM tools_caisse WHERE Id=Id_Caisse) AS NumCaisse,
						(SELECT Libelle FROM tools_caissetype WHERE Id=(SELECT Id_CaisseType FROM tools_caisse WHERE Id=Id_Caisse)) AS CaisseType,
						IF(Id_Caisse>0,
						(
							SELECT CONCAT(Nom,' ',Prenom)
							FROM tools_mouvement AS TAB_Mouvement
							LEFT JOIN new_rh_etatcivil ON TAB_Mouvement.Id_Personne=new_rh_etatcivil.Id
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)) AS Personne 
					FROM tools_mouvement
					WHERE Suppr=0
					AND Id_Materiel__Id_Caisse=".$Id."
					AND Type=".$Type."
					AND TypeMouvement=0 
					ORDER BY DateReception DESC, Id DESC
					";

					$Result=mysqli_query($bdd,$req);
					$NbEnreg=mysqli_num_rows($Result);
					if($NbEnreg>0)
					{
					$Couleur="#EEEEEE";
					$dateReception="";
					while($Row=mysqli_fetch_array($Result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}

						if($Row['Id_Caisse']>0){
							
							$req="SELECT 
								IF(EtatValidation<>-1,DateReception,DateDemande) AS DateReception,EtatValidation AS TransfertEC,CommentaireRefus,
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
								(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne 
								FROM tools_mouvement
								WHERE Suppr=0
								AND Id_Materiel__Id_Caisse=".$Row['Id_Caisse']."
								AND Type=1
								AND TypeMouvement=0
								AND DateReception>'".$Row['DateReception']."' ";
							if($dateReception<>""){
								$req.="AND DateReception<='".$dateReception."' ";
							}
							$req.="ORDER BY DateReception DESC, Id DESC
							";

							$CouleurCaisse="#f2ddfd";
							$ResultCaisse=mysqli_query($bdd,$req);
							while($RowCaisse=mysqli_fetch_array($ResultCaisse))
							{
								if($CouleurCaisse=="#f2ddfd"){$CouleurCaisse="#cb79f9";}
								else{$CouleurCaisse="#f2ddfd";}
								$LIBELLE_POLE="";
								if($RowCaisse['Pole']<>""){$LIBELLE_POLE=" - ".$RowCaisse['Pole'];}
								
								$etatValidation="";
								if($RowCaisse['TransfertEC']==0){
									$etatValidation="Transfert E/C";
								}
								elseif($RowCaisse['TransfertEC']==-1){
									$etatValidation="Refusé";
									$CouleurCaisse="#f43434";
								}
								elseif($RowCaisse['TransfertEC']==1){
									$etatValidation="Validé";
								}
								?>
									<tr bgcolor="<?php echo $CouleurCaisse;?>">
										<td <?php if($RowCaisse['TransfertEC']==0){echo "id='leHover'";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php if($RowCaisse['TransfertEC']==0){echo "<img width='15px' src='../../Images/attention.png' border='0' />&nbsp;<span>Transfert en cours</span>";} ?><i><?php echo AfficheDateJJ_MM_AAAA($RowCaisse['DateReception']);?></i></td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes($RowCaisse['Plateforme']);?></i></td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes(substr($RowCaisse['Prestation'],0,7).$LIBELLE_POLE);?></i></td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes($RowCaisse['Lieu']);?></i></td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php if($Row['CaisseType']<>""){echo stripslashes($Row['CaisseType']." n° ".$Row['NumCaisse']);}?></i></td>
										<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes($RowCaisse['Personne']);?></i></td>
										<td></td>
										<td <?php if($RowCaisse['TransfertEC']==-1){echo "id='leHover'";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo $etatValidation;?><?php if($RowCaisse['TransfertEC']==-1){echo "<span>".stripslashes($RowCaisse['CommentaireRefus'])."</span>";} ?></i></td>
									</tr>
								<?php
							}
						}
							$LIBELLE_POLE="";
							if($Row['Pole']<>""){$LIBELLE_POLE=" - ".$Row['Pole'];}
							
							$etatValidation="";
								if($Row['EtatValidation']==0){
									$etatValidation="Transfert E/C";
								}
								elseif($Row['EtatValidation']==-1){
									$etatValidation="Refusé";
									$Couleur="#f43434";
								}
								elseif($Row['EtatValidation']==1){
									$etatValidation="Validé";
								}
							?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td <?php if($Row['TransfertEC']==0 || $Row['EtatValidation']==0){echo "id='leHover'";} ?>><?php if($Row['TransfertEC']==0 || $Row['EtatValidation']==0){echo "<img width='15px' src='../../Images/attention.png' border='0' />&nbsp;<span>Transfert en cours</span>";} ?><?php echo AfficheDateJJ_MM_AAAA($Row['DateReception']);?></td>
									<td><?php echo stripslashes($Row['Plateforme']);?></td>
									<td><?php echo stripslashes(substr($Row['Prestation'],0,7).$LIBELLE_POLE);?></td>
									<td><?php echo stripslashes($Row['Lieu']);?></td>
									<td><?php if($Row['CaisseType']<>""){echo stripslashes($Row['CaisseType']." n° ".$Row['NumCaisse']);}?></td>
									<td><?php echo stripslashes($Row['Personne']);?></td>
									<td><?php echo stripslashes(stripslashes($Row['Commentaire']));?></td>
									<td <?php if($Row['EtatValidation']==-1){echo "id='leHover'";} ?>><?php echo $etatValidation;?><?php if($Row['EtatValidation']==-1){echo "<span>".stripslashes($Row['CommentaireRefus'])."</span>";} ?></td>
								</tr>
							<?php
						
						$dateReception=$Row['DateReception'];
					}
				}		
				?>
			</table>
			<br>
			<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr>
					<td style="background-color:#d286b4;" class="Libelle" align="center" colspan="10"><?php if($LangueAffichage=="FR"){echo "PERTE / VOL";}else{echo "LOSS / THEFT";} ?></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetences"></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Ref. déclaration";}else{echo "Ref declaration";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Déclarant";}else{echo "Declarer";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Comment";}?></td>
				</tr>
				<?php
					$req="SELECT Id,
						PV_Date,
						PV_RefDeclaration,PV_Id_Pole,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Personne,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
						PV_Type,
						PV_Lieu,
						PV_Remarque
						FROM tools_mouvement
						WHERE Suppr=0
						AND Id_Materiel__Id_Caisse=".$Id."
						AND Type=".$Type."
						AND TypeMouvement=2
						ORDER BY PV_Date DESC, Id DESC
					";
					$Result=mysqli_query($bdd,$req);
					$NbEnreg=mysqli_num_rows($Result);
					if($NbEnreg>0)
					{
					$Couleur="#EEEEEE";
					while($Row=mysqli_fetch_array($Result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						$type="";
						if($Row['PV_Type']==0){if($LangueAffichage=="FR"){$type= "Perte";}else{$type= "Loss";}}
						else{if($LangueAffichage=="FR"){$type= "Vol";}else{$type= "Theft";}}
						
						$pole="";
						if($Row['PV_Id_Pole']>0){$pole=" - ".$Row['Pole'];}
				?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php if($Row['PV_Type']<>1){ ?><a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('<?php echo $Row['Id']; ?>');" ><img src="../../Images/excel.gif" border="0"></a><?php } ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($Row['PV_Date']);?></td>
						<td><?php echo stripslashes($Row['PV_RefDeclaration']);?></td>
						<td><?php echo stripslashes($Row['Personne']);?></td>
						<td><?php echo stripslashes($Row['Prestation'].$pole);?></td>
						<td><?php echo stripslashes($type);?></td>
						<td><?php echo stripslashes($Row['PV_Lieu']);?></td>
						<td><?php echo nl2br(stripslashes($Row['PV_Remarque']));?></td>
					</tr>
				<?php
					}	//Fin boucle
				}		//Fin If
				mysqli_free_result($Result);	// Libération des résultats
				?>
			</table>
		</td>
		<td width="50%" valign="top">
			<?php if($RowMateriel['Id_TypeMateriel']==1){ ?>
			<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr>
					<td style="background-color:#21b63c;" class="Libelle" align="center" colspan="10"><?php if($LangueAffichage=="FR"){echo "MAINTENANCE & ETALONNAGE";}else{echo "MAINTENANCE & CALIBRATION";} ?></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date de vérif";}else{echo "Date of verification";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "N° PV";}else{echo "PV number";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Conforme";}else{echo "Compliant";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Décision";}else{echo "Decision";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Comment";}?></td>
				</tr>
				<?php
					if($_SESSION['Langue']=="FR"){
						$req="SELECT Id,
						(SELECT Libelle FROM tools_tiers WHERE Id=FV_Id_Laboratoire) AS Organisme,
						FV_DateEtalonnage,FV_Conformite,FV_NumPV,FV_BonCommande,FV_Prix,FV_Remarques,
						(SELECT Libelle FROM tools_decision WHERE Id=FV_Id_Decision) AS Decision
						FROM tools_mouvement
						WHERE Suppr=0
						AND Id_Materiel__Id_Caisse=".$Id."
						AND TypeMouvement=1
						AND Type=".$Type."
						ORDER BY FV_DateEtalonnage DESC, Id DESC
					";
					}
					else{
						$req="SELECT Id,
						(SELECT Libelle FROM tools_tiers WHERE Id=FV_Id_Laboratoire) AS Organisme,
						FV_DateEtalonnage,FV_Conformite,FV_NumPV,FV_BonCommande,FV_Prix,FV_Remarques,
						(SELECT LibelleEN FROM tools_decision WHERE Id=FV_Id_Decision) AS Decision
						FROM tools_mouvement
						WHERE Suppr=0
						AND Id_Materiel__Id_Caisse=".$Id."
						AND TypeMouvement=1
						AND Type=".$Type."
						ORDER BY FV_DateEtalonnage DESC, Id DESC
					";
					}
					$Result=mysqli_query($bdd,$req);
					$NbEnreg=mysqli_num_rows($Result);
					if($NbEnreg>0)
					{
					$Couleur="#EEEEEE";
					while($Row=mysqli_fetch_array($Result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						if($Row['FV_Conformite']==1){$conforme="<img width='15px' src='../../Images/tick.png' border='0'>";}
						else{$conforme="<img width='15px' src='../../Images/delete.png' border='0'>";}
				?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo AfficheDateJJ_MM_AAAA($Row['FV_DateEtalonnage']);?></td>
						<td><?php echo stripslashes($Row['Organisme']);?></td>
						<td><?php echo stripslashes($Row['FV_NumPV']);?></td>
						<td><?php echo stripslashes($Row['FV_BonCommande']);?></td>
						<td><?php echo stripslashes($Row['FV_Prix']);?></td>
						<td><?php echo stripslashes($conforme);?></td>
						<td><?php echo stripslashes($Row['Decision']);?></td>
						<td><?php echo nl2br(stripslashes($Row['FV_Remarques']));?></td>
					</tr>
				<?php
					}
				}		
				?>
			</table>
			<br>
			<?php } 
			if($Type>0){ ?>
			
			<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr>
					<td style="background-color:#21b63c;" class="Libelle" align="center" colspan="9"><?php if($LangueAffichage=="FR"){echo "MATERIEL DE LA CAISSE";}else{echo "CASE MATERIAL";} ?></td>
				</tr>
				<tr>
					<td height="9"></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?></td>
				</tr>
				<?php
					$req="
						SELECT
							tools_materiel.Id AS ID,
							'Outils' AS TYPESELECT,
							NumAAA,
							SN,
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
							Prix,
							tools_typemateriel.Id AS ID_TYPEMATERIEL,
							tools_typemateriel.Libelle AS TYPEMATERIEL,
							tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
							tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
							(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
							(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
							(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
							(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
							(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
							IF((SELECT tools_mouvement.Id_Caisse
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
								(
									SELECT (
										SELECT EtatValidation
										FROM tools_mouvement
										LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
										WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
									)
									FROM tools_mouvement AS TAB_Mouvement
									LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
									LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
									WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
									ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
								),
							(
								SELECT EtatValidation
								FROM tools_mouvement
								LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
								WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
							)) AS TransfertEC
						FROM
								tools_materiel
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE
								tools_materiel.Suppr=0 
						AND (SELECT Id_Caisse FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$Id." ";


					$Result=mysqli_query($bdd,$req);
					$NbEnreg=mysqli_num_rows($Result);
					
					if($NbEnreg>0)
					{
						$Couleur="#EEEEEE";
						$dateReception="";
						while($Row=mysqli_fetch_array($Result))
						{
							$SommePrix+=$Row['Prix'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}


								?>
									<tr bgcolor="<?php echo $Couleur;?>">
										<td <?php if($Row['TransfertEC']==0){echo "style='border:dotted #22b63d 5px' id='leHover'";} ?>><?php if($Row['TransfertEC']==0){echo "<span>Transfert en cours</span>";} ?><?php echo $Row['NumAAA'];?></td>
										<td><?php echo $Row['SN'];?></td>
										<td><?php echo stripslashes($Row['TYPEMATERIEL']);?></td>
										<td><?php echo stripslashes($Row['FAMILLEMATERIEL']);?></td>
										<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
										<td><?php echo stripslashes($Row['Num']);?></td>
										<td><?php echo stripslashes($Row['LIBELLE_FABRICANT']);?></td>
										<td><?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td>
										<td><?php echo stripslashes($Row['Prix']);?></td>
									</tr>
								<?php
						}
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td colspan="7"></td>
							<td bgcolor="#22b63d"><?php if($LangueAffichage=="FR"){echo "Prix total";}else{echo "Total price";}?></td>
							<td bgcolor="#22b63d"><?php echo $SommePrix;?></td>
						</tr>
						<?php
					}		
				?>
			</table>
			<br>
			<?php } ?>
			<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr>
					<td style="background-color:#a127f1;" class="Libelle" align="center" colspan="10"><?php if($LangueAffichage=="FR"){echo "SAV";}else{echo "SAV";} ?></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Devis accepté";}else{echo "Quote accepted";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Comment";}?></td>
				</tr>
				<?php
					$req="SELECT Id,
						(SELECT Libelle FROM tools_tiers WHERE Id=FV_Id_Laboratoire) AS Organisme,
						SAV_Date,SAV_BonCommande,SAV_Prix,SAV_Remarque,SAV_DevisAccepte
						FROM tools_mouvement
						WHERE Suppr=0
						AND Id_Materiel__Id_Caisse=".$Id."
						AND Type=".$Type."
						AND TypeMouvement=3
						ORDER BY SAV_Date DESC, Id DESC
					";
					$Result=mysqli_query($bdd,$req);
					$NbEnreg=mysqli_num_rows($Result);
					if($NbEnreg>0)
					{
					$Couleur="#EEEEEE";
					while($Row=mysqli_fetch_array($Result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						if($Row['SAV_DevisAccepte']==1){$devis="<img width='15px' src='../../Images/tick.png' border='0'>";}
						else{$devis="<img width='15px' src='../../Images/delete.png' border='0'>";}
				?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo AfficheDateJJ_MM_AAAA($Row['SAV_Date']);?></td>
						<td><?php echo stripslashes($Row['Organisme']);?></td>
						<td><?php echo stripslashes($Row['SAV_BonCommande']);?></td>
						<td><?php echo stripslashes($Row['SAV_Prix']);?></td>
						<td><?php echo stripslashes($devis);?></td>
						<td><?php echo nl2br(stripslashes($Row['SAV_Remarque']));?></td>
					</tr>
				<?php
					}
				}	
				?>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>