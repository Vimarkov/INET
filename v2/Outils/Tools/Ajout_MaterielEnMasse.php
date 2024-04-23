<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js?t=<?php echo time(); ?>"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
		function Change_CodeArticle()
		{
			if(document.getElementById('Id_CodeArticle').value!=0){
				tab = document.getElementById('Id_CodeArticle').value.split('_');
				if(tab[1]==1){
					document.getElementById('Immo1').style.display="";
					document.getElementById('Immo2').style.display="";
					document.getElementById('Immo3').style.display="";

					document.getElementById('Immo6').style.display="";
				}
				else{
					document.getElementById('Immo1').style.display="none";
					document.getElementById('Immo2').style.display="none";
					document.getElementById('Immo3').style.display="none";

					document.getElementById('Immo6').style.display="none";
				}
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

$TablePrincipale="tools_materiel";

if($_POST)
{
	$listeAAATO='<table style="width:100%; height:95%; align:center;" class="TableCompetences">
				<tr><td height="5px"></td></tr>';
	
	if(isset($_POST['ModeleMateriel_ValeurSelection']))
	{
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
		$Id_Caisse=0;
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
		elseif($_POST['affectation']=="caisse"){
			$Id_Caisse=$_POST['Id_Caisse'];
			
			$req="SELECT tools_mouvement.Id_Prestation,Id_Pole
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=".$Id_Caisse."
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";
			$ResultMouv=mysqli_query($bdd,$req);
			$NbEnregMouv=mysqli_num_rows($ResultMouv);
			$Id_Prestation=0;
			if($NbEnregMouv>0)
			{
				$RowMouv=mysqli_fetch_array($ResultMouv);
				$Id_Prestation=$RowMouv['Id_Prestation'];
				$Id_Pole=$RowMouv['Id_Pole'];
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
		
		if($_POST['ModeleMateriel_ValeurSelection'] != "")
		{
			
			
			$Tableau_ModeleMateriel_ValeurSelection = explode("|",$_POST['ModeleMateriel_ValeurSelection']);
			$Tableau_ModeleMateriel_QuantiteSelection = explode("|",$_POST['ModeleMateriel_QuantiteSelection']);
			
			$tabCodeArticle=explode("_",$_POST['Id_CodeArticle']);
			
			for($i=0;$i<sizeof($Tableau_ModeleMateriel_ValeurSelection);$i++)
			{
				for($j=1;$j<=$Tableau_ModeleMateriel_QuantiteSelection[$i];$j++){
					$RequeteAjoutNouveauContenu="INSERT INTO ".$TablePrincipale."(Id_ModeleMateriel,Id_Fournisseur,NumAAA,Prix,BonCommande,NumFacture,
													Id_CodeArticle,
													NumFicheImmo,DateDebutImmo,DateFinImmo,Remarques,Designation,Id_PersonneMAJ,DateMAJ) VALUES 
												('".$Tableau_ModeleMateriel_ValeurSelection[$i]."','".$_POST['Id_Fournisseur']."','".Next_CodeGravureMateriel($Id_PlateformeNew)."','".$_POST['Prix']."','".$_POST['BonCommande']."',
													'".addslashes($_POST['NumFacture'])."',
													".$tabCodeArticle[0].",
													'".addslashes($_POST['NumFicheImmo'])."',
													'".TrsfDate_($_POST['DateDebutImmo'])."',
													'".TrsfDate_($_POST['DateFinImmo'])."','".addslashes($_POST['Remarques'])."','".addslashes($_POST['Designation'])."','".$IdPersonneConnectee."','".date('Y-m-d')."')";
					$ResultAjoutNouveauContenu=mysqli_query($bdd,$RequeteAjoutNouveauContenu);
					
					$Id_Materiel=mysqli_insert_id($bdd);
					
					if($Id_Materiel>0){
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
							
							//Vérifier si la personne n'est pas gestionnaire MGX de la plateforme ou poste informatique 
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
						
						//Ajout du mouvement par défaut (magasin)
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
								Id_Caisse,
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
								'0',
								'0',
								'".$Id_Materiel."',
								'".$Id_Prestation."',
								'".$Id_Pole."',
								'".$Id_Lieu."',
								'".$Id_Personne."',
								'".$Id_Caisse."',
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
						
						//Mettre à jour l'affectation dans matériel
						$req="UPDATE tools_materiel 
							SET Id_PrestationT=".$Id_Prestation.", Id_PoleT=".$Id_Pole.", Id_LieuT=".$Id_Lieu.", Id_PersonneT=".$Id_Personne.", 
								Id_CaisseT=".$Id_Caisse.", DateReceptionT='".$DateReception."', EtatValidationT=".$EtatValidation." 
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
			}
			$listeAAATO.='<tr><td height="5px"></td></tr></table>';
			echo $listeAAATO;
			
			if($_POST['affectation']=="personne"){
				if($Id_Personne>0){
					//Editer le document de pret du matériel 
					echo "<script>window.open('EditerPretMateriel.php?laDate=".date('Y-m-d')."&Id=".$Id_Personne."','Fiche_PretMateriel','status=no,menubar=no,width=20,height=20');</script>";
				}
			}
			echo "<script>Recharger('".$_POST['Page']."');</script>";
		}
		else{
			echo "<script>FermerEtRecharger('".$_POST['Page']."');</script>";
		}
	}
	else{
		echo "<script>FermerEtRecharger('".$_POST['Page']."');</script>";
	}
	
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout")
	{
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<input type="hidden" name="Page" name="Page" value="<?php if(isset($_GET['Page'])){echo $_GET['Page'];} ?>">
		<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#23b63e;">
			<tr>
				<td class="TitrePage">
				<?php
				if($LangueAffichage=="FR"){echo "Ajouter une liste de matériel";}else{echo "Add a hardware list";}
				?>
				</td>
			</tr>
		</table><br>
		<table style="width:100%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td width="30%" class="Libelle">
								Modèles de matériel<br>
								<select name="Id_ModeleMateriel" id="Id_ModeleMateriel" multiple size="30" onDblclick="AjouterAListe('Id_ModeleMateriel','ModeleMateriel_ListeSelection','ModeleMateriel');">
								<?php
								$RequeteModeleMateriel="SELECT Id, Libelle FROM tools_modelemateriel WHERE Suppr=0 ORDER BY Libelle";
								$ResultModeleMateriel=mysqli_query($bdd,$RequeteModeleMateriel);
								while($RowModeleMateriel=mysqli_fetch_array($ResultModeleMateriel))
								{
									echo "<option value='".$RowModeleMateriel['Id']."'>".str_replace("'"," ",$RowModeleMateriel['Libelle'])."</option>\n";
								}
								?>
								</select>
							</td>
							<td width="40%">
								<table style="width:100%;align:left;">
									<tr>
										<td align="center">
											<table>
												<tr>
													<td>
														<input onKeyUp="nombre(this)" id='nbMateriel' name='nbMateriel' size='5' type='text' style="text-align:center;" value='1'>
													</td>
													<td>
														<img id="btnAjouter" name="btnAjouter" width="30px" src="../../Images/Droite.png" style="cursor:pointer;" onclick="AjouterAListe2('Id_ModeleMateriel','ModeleMateriel_ListeSelection','ModeleMateriel');"/> 
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td height="30"></td>
									</tr>
									<tr>
										<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fournisseur";}else{echo "Provider";}?> : </td>
									</tr>
									<tr>
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
												if($Modif){if($Row['Id_ModeleMateriel']==$RowFournisseur['Id']){echo " selected";}}
												echo ">".$RowFournisseur['Libelle']."</option>\n";
											}
											?>
											</select>
										</td>
									</tr>
									<tr>
										<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Prix (€)";}else{echo "Price (€)";}?> : </td>
									</tr>
									<tr>
										<td><input name='Prix' size='10' type='text' value=''></td>
									</tr>
									<tr>
										<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?> : </td>
									</tr>
									<tr>
										<td><input name='BonCommande' size='20' type='text' value=''></td>
									</tr>
									<tr>
										<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code article";}else{echo "Item code";}?> : </td>
									</tr>
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
									</tr>
									<tr style="display:none" id="Immo6">
										<td><input name='NumFicheImmo' size='15' type='text' value=''></td>
									</tr>
									<tr style="display:none" id="Immo2">
										<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début <br>immobilisation";}else{echo "Start date <br>of immobilization";}?> : </td>
										<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date fin immobilisation";}else{echo "End date of immobilization";}?> : </td>
									</tr>
									<tr style="display:none" id="Immo3">
										<td><input name='DateDebutImmo' size='15' type='date' value=''></td>
										<td><input name='DateFinImmo' size='15' type='date' value=''></td>
									</tr>

									<tr class="TitreColsUsers">
										<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° facture";}else{echo "Invoice number";}?> : </td>
									</tr>
									<tr class="TitreColsUsers">
										<td><input name='NumFacture' size='15' type='text' value=''></td>
									</tr>
									<tr class="TitreColsUsers">
										<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Désignation";}else{echo "Designation";}?> : </td>
									</tr>
									<tr>
										<td><input name='Designation' size='40' type='text' value=''></td>
									</tr>
									<tr class="TitreColsUsers">
										<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Remarques";}else{echo "Remarks";}?> : </td>
									</tr>
									<tr>
										<td colspan="2"><textarea name="Remarques" rows="3" cols="50" style="resize: none;"></textarea></td>
									</tr>
								</table>
							</td>
							<td width="30%" class="Libelle">
								Modèles de matériel à créer<br>
								<select name="ModeleMateriel_ListeSelection[]" id="ModeleMateriel_ListeSelection" multiple size="30" onDblclick="RetirerDeListe('ModeleMateriel_ListeSelection','ModeleMateriel');">
								<?php
								$valeur="";
								$quantite="";
								if($_GET['Mode']=="Modif"){
									$RequeteModeleMaterielCaisseType="
										SELECT
											Id_ModeleMateriel,
											(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
											Quantite
										FROM
											tools_caissetype_contenu
										WHERE
											Id_CaisseType=".$_GET['Id']."
											AND Suppr=0
										ORDER BY
											LIBELLE_MODELEMATERIEL";
									$ResultModeleMaterielCaisseType=mysqli_query($bdd,$RequeteModeleMaterielCaisseType);
									$i=0;
									
									while($RowModeleMaterielCaisseType=mysqli_fetch_array($ResultModeleMaterielCaisseType))
									{
										echo "<option value='".$RowModeleMaterielCaisseType['Id_ModeleMateriel']."'>".str_replace("'"," ",$RowModeleMaterielCaisseType['LIBELLE_MODELEMATERIEL'])." _________ Qty:".$RowModeleMaterielCaisseType['Quantite']."</option>\n";
										echo "<script>Tableau_InputACompleter_ValeurSelection[".$i."]=".$RowModeleMaterielCaisseType['Id_ModeleMateriel'].";</script>";
										echo "<script>Tableau_InputACompleter_QuantiteSelection[".$i."]=".$RowModeleMaterielCaisseType['Quantite'].";</script>";
										if($valeur<>""){
											$valeur.="|";
											$quantite.="|";
										}
										$valeur.=$RowModeleMaterielCaisseType['Id_ModeleMateriel'];
										$quantite.=$RowModeleMaterielCaisseType['Quantite'];
										$i++;
									}
								}
								?>
								</select>
								<input type="hidden" name="ModeleMateriel_ValeurSelection" id="ModeleMateriel_ValeurSelection" value="<?php echo $valeur;?>">
								<input type="hidden" name="ModeleMateriel_QuantiteSelection" id="ModeleMateriel_QuantiteSelection" value="<?php echo $quantite;?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			
			<tr class="TitreColsUsers">
				<td class="Libelle" colspan="2" style="display:none;">
					<input type="radio" name="affectation" onchange="AfficherAffectation('site')" value="site" checked><?php if($LangueAffichage=="FR"){echo "Site";}else{echo "Site";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('personne')" value="personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('caisse')" value="caisse"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Box";}?>
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
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_PlateformeCaisse" id="Id_PlateformeCaisse" style="width:200px" onchange="RechargerCaisse()">
						<?php
							$Id_PlateformeCaisse=$_SESSION['FiltreToolsSuivi_Plateforme'];
							$requetePlat="
								SELECT Id_Plateforme AS Id,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
								FROM (
								SELECT DISTINCT 
								(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS Id_Plateforme
								FROM tools_caisse 
								WHERE Suppr=0
								) AS TAB
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
								if($Id_PlateformeCaisse==0){$Id_PlateformeCaisse=$rowPlat['Id'];}
								if($Id_PlateformeCaisse==$rowPlat['Id']){$selected="selected";}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trCaisse"><td height="4"></td></tr>
			<tr class="trCaisse">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?></td>
				<td>
					<select name="Id_Caisse" id="Id_Caisse">
					<?php
					$rq="SELECT Id, Num, 
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS CaisseType,
						(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS Id_Plateforme
						FROM tools_caisse 
						WHERE Suppr=0
						ORDER BY tools_caisse.Num ASC";
					$resultcaisse=mysqli_query($bdd,$rq);
					$Id_Caisse=0;
					$i=0;
					while($rowCaisse=mysqli_fetch_array($resultcaisse))
					{
						$selected="";
						if($Id_Caisse==0){$Id_Caisse=$rowCaisse['Id'];$selected = "selected";}
						echo "<option value='".$rowCaisse['Id']."' ".$selected.">"."n° ".$rowCaisse['Num']." ".str_replace("'"," ",$rowCaisse['CaisseType'])."</option>\n";
						echo "<script>Liste_Caisse[".$i."] = new Array(".$rowCaisse['Id'].",'".str_replace("'"," ",$rowCaisse['CaisseType'])."','".$rowCaisse['Num']."',".$rowCaisse['Id_Plateforme'].");</script>";
						$i++;
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
						if($Modif)
						{
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else
						{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$Result=mysqli_query($bdd,"UPDATE ".$TablePrincipale." SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger('".$_GET['Page']."');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	echo "<script>RechargerPrestation();</script>";
	echo "<script>RechargerCaisse();</script>";
	echo "<script>AfficherAffectation('site');</script>";
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>