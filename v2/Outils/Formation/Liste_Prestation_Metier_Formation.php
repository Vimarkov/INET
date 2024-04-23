<?php 
require("../../Menu.php");
?>
<script type="text/javascript" charset="UTF-8" src="Ajout_Prestation_Metier_Formation.js"></script>
<script type="text/javascript">
	function OuvreFenetreGenererBesoins(){
		var w=window.open("RegenererBesoins.php?Id_PrestationId_Pole="+document.getElementById('Id_Prestation').value,"PageBesoin","status=no,menubar=no,scrollbars=yes,width=1000,height=400");
		w.focus();
	}
	function OuvreFenetreSupprimerBesoinsPersonne(){
		var w=window.open("SupprimerBesoinsInutilesPersonne.php?Id_PrestationId_Pole="+document.getElementById('Id_Prestation').value,"PageBesoin","status=no,menubar=no,scrollbars=yes,width=700,height=400");
		w.focus();
	}
	function OuvreFenetreSupprimerBesoinsPrestation(){
		var w=window.open("SupprimerBesoinsInutilesPrestation.php?Id_Plateforme="+document.getElementById('Id_Plateforme').value,"PageBesoin","status=no,menubar=no,scrollbars=yes,width=700,height=400");
		w.focus();
	}
	</script>
<?php
if($_POST)
{
	if(isset($_POST['save2']) || isset($_POST['saveHome']) || isset($_POST['save']))
	{
		//Fonction sauvegarder
		if(isset($_POST['save2']) || isset($_POST['saveHome']))
		{
			$POST_PRESTATION=$_POST['lIdPresta'];
			$POST_METIERFORMATION=$_POST['lMetierFormation'];
		}
		elseif(isset($_POST['save']))
		{
			$POST_PRESTATION=$_POST['Id_Prestation'];
			$POST_METIERFORMATION=$_POST['lesMetiersFormations'];
		}
		
		$tabPresta=explode("_",$POST_PRESTATION);
		//Récupération des données actives
		$RequetePrestationPoleFormation="
			SELECT
				Id,
                Id_Prestation,
                Id_Metier,
                Id_Formation,
                Obligatoire
			FROM
				form_prestation_metier_formation
			WHERE
				Suppr=0
				AND Id_Prestation=".$tabPresta[0]." 
				AND Id_Pole=".$tabPresta[1]." ;";
		$ResultPrestationPoleFormation=mysqli_query($bdd,$RequetePrestationPoleFormation);
		
		//Récupération de la liste des personnes de la prestation choisie
		$ReqPersonnePrestation="
			SELECT
				Id_Personne
			FROM
				new_competences_personne_prestation
			WHERE
				Id_Prestation=".$tabPresta[0]." 
				AND Id_Pole=".$tabPresta[1]."
				AND Date_Fin >= '".$DateJour."'";
		$ResultPersonnePrestation=mysqli_query($bdd,$ReqPersonnePrestation);
		$nbPersonnePrestation=mysqli_num_rows($ResultPersonnePrestation);
		$Id_personnes_pour_MAJ = Array();
		
		//Création d'un tableau qui va faire la différence entre les besoins déjà existants pour ce métier/cette prestation
		//pour ne prendre en compte que les nouveaux besoins
		$TableauTousBesoins = explode(";",$POST_METIERFORMATION);
		$TableauNouveauxBesoins=Array();
	    foreach($TableauTousBesoins as $Valeurs_TableauTousBesoins)
		{
			if($Valeurs_TableauTousBesoins<>"")
			{
			    $tabValeur_TableauTousBesoins = explode("_", $Valeurs_TableauTousBesoins);
			    $FormationMetierPrestationExisteDeja=false;
			    mysqli_data_seek($ResultPrestationPoleFormation,0);
			    while($RowPrestationPoleFormation = mysqli_fetch_array($ResultPrestationPoleFormation))
			    {
			        if($tabValeur_TableauTousBesoins[0]==$RowPrestationPoleFormation['Id_Metier'] && $tabValeur_TableauTousBesoins[2]==$RowPrestationPoleFormation['Id_Formation'])
    			    {
    			        $FormationMetierPrestationExisteDeja=true;
    			        break;
    			    }
			    }
			    if(!$FormationMetierPrestationExisteDeja){array_push($TableauNouveauxBesoins, $Valeurs_TableauTousBesoins);}
			}
		}
		
		
		
		//-------------------------------------------------------------------------------------------------------------------

		//Insertion des nouvelles données dans la table liées aux formations
		//------------------------------------------------------------------
		$requeteAjout="
            INSERT INTO
                form_prestation_metier_formation
                (
                    Id_Prestation,
                    Id_Pole,
                    Id_Metier,
                    Id_Formation,
                    Obligatoire,
                    Id_Personne_MAJ,
                    Date_MAJ
                )
            VALUES ";
		$requeteAjoutValeurs="";
		foreach($TableauTousBesoins as $Valeurs_TableauTousBesoins)
		{
		    if($Valeurs_TableauTousBesoins<>"")
			{
			    $tabValeur = explode("_", $Valeurs_TableauTousBesoins);
				$requeteAjoutValeurs.="
					(".
						$tabPresta[0].",".
						$tabPresta[1].",".
						$tabValeur[0].",".
						$tabValeur[2].",".
						$tabValeur[1].",".
						$IdPersonneConnectee.",
						'".date('Y-m-d')."'
					),";
			}
		}
		
		if($_POST['lesMetiersFormationsASuppr'] <> "")
		{
		    //			$tabPresta[0] Prestation
		    //			$tabPresta[1] Pôle
		    //			$tabValeur[0] Métier
		    //			$tabValeur[2] Formation
		    
			//Correction - Suppression des besoins
			$tabus = explode(";",$_POST['lesMetiersFormationsASuppr']);
			
			foreach($tabus as $params)
			{
				if($params<>""){
					//          $tabu[0] Id_Metier
					//          $tabu[1] Obligatoire ou Facultatif
					//          $tabu[2] Id_Formation
					
					$tabu = explode("_",$params);
		
					//Suppression des formations liées à cette prestation
					//supprimer_lien_MetierPrestaFormation($tabPresta[0], $tabu[0], $tabu[2]);

					//Suppression des besoins de cette formation
					$resBesoinsSuppr = Supprimer_BesoinsFormations($tabPresta[0], $tabu[2],$tabPresta[1], -1,"Depuis Liste_Metier_Formation",$tabu[0]);
					
					//Suppression des 'B' reliées à des besoins supprimés
					$req = "
						UPDATE new_competences_relation
						SET Suppr=1
						WHERE new_competences_relation.Suppr=0
						AND new_competences_relation.Id_Besoin>0
						AND Evaluation = 'B'
						AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
							OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
						)
						AND Id_Besoin IN (
							SELECT form_besoin.Id FROM form_besoin 
							WHERE form_besoin.Suppr=1
						)";
					$result=mysqli_query($bdd,$req);
				}
 			}
			unset($_POST['lesMetiersFormationsASuppr']);
		}//Fin correction - Suppression des besoins

		//Suppression des formations liées à cette prestation
		$requete="
		UPDATE
			form_prestation_metier_formation
		SET
			Suppr=1
		WHERE
			Id_Prestation=".$tabPresta[0]."
			AND Id_Pole=".$tabPresta[1];
		$result=mysqli_query($bdd,$requete);
		
		if($requeteAjoutValeurs <> "")
		{
			$requeteAjoutValeurs=substr($requeteAjoutValeurs,0,strlen($requeteAjoutValeurs)-1);
			$resultAjout=mysqli_query($bdd,$requeteAjout.$requeteAjoutValeurs);
		}
		
		//------------------------------------------------------------------
		//Création des besoins pour chaque personnne
		//------------------------------------------
		foreach($TableauNouveauxBesoins as $Valeurs_TableauNouveauxBesoins)
		{
			
		    if($Valeurs_TableauNouveauxBesoins<>"")
			{
			    $tabValeur = explode("_", $Valeurs_TableauNouveauxBesoins);
				if($nbPersonnePrestation>0)
				{
					mysqli_data_seek($ResultPersonnePrestation,0);
					while($RowPersonnePrestation=mysqli_fetch_array($ResultPersonnePrestation))
					{
						$ResultMetierPersonne=Get_LesMetiersFutur($RowPersonnePrestation['Id_Personne']);
						$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
						if($nbPersonnePrestation>0){
							while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
							{
								$Id_Metier_Personne=$Metier_Personne[0];
								$LIBELLE_METIER="";
								if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
								$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
								if($tabValeur[0]==$Id_Metier_Personne)
								{
									Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $tabPresta[0], $tabPresta[1], $Id_Metier_Personne, $Motif, 0,$tabValeur[2],-1);
								}
							}
						}
						else{
							$ResultMetierPersonne=Get_LesMetiersNonFutur($RowPersonnePrestation['Id_Personne']);
							$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
							if($nbPersonnePrestation>0){
								while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
								{
									$Id_Metier_Personne=$Metier_Personne[0];
									$LIBELLE_METIER="";
									if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
									$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
									if($tabValeur[0]==$Id_Metier_Personne)
									{
										Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $tabPresta[0], $tabPresta[1], $Id_Metier_Personne, $Motif, 0,$tabValeur[2],-1);
									}
								}
							}
						}
						
						
						// Pour le passage en obligatoire ou facultatif
						$reqMetierFutur="
							SELECT
								Id_Personne
							FROM
								new_competences_personne_metier
							WHERE
								Id_Personne=".$RowPersonnePrestation['Id_Personne']."
								AND Id_Metier=".$tabValeur[0]."
								AND Futur=1;";
						
						$reqMetier="
							SELECT
								Id_Personne
							FROM
								new_competences_personne_metier
							WHERE
								Id_Personne=".$RowPersonnePrestation['Id_Personne']."
								AND Id_Metier=".$tabValeur[0]."
								AND Futur=0;";
						
						$resMetier = getRessource($reqMetier);
												
						if (mysqli_num_rows($resMetier) == 0)
							$resMetier = getRessource($reqMetierFutur);
						
						if (mysqli_num_rows($resMetier) > 0)
						{
							$rowMetier = mysqli_fetch_array($resMetier);
							//Ajoute l'Id_Personne				 		
							array_push($Id_personnes_pour_MAJ, $RowPersonnePrestation['Id_Personne']);
						}
					}
				}
				// Ticket sprint 3 Passer formation obligatoire en facultatif 				 	
				//#################################################################################################
				$Ids_Personne = implode(', ', $Id_personnes_pour_MAJ);
				
				$reqMAJ_enFacultatif="
					UPDATE
						form_besoin
					SET
						Obligatoire=0
					WHERE
						Id_Prestation=".$tabPresta[0]."
						AND Id_Pole=".$tabPresta[1]."
						AND Id_Formation=".$tabValeur[2]."
						AND Traite=0
						AND Id_Personne IN (".$Ids_Personne.")
						AND Id_Personne_MAJ=0;";

				$reqMAJ_enObligatoire="
					UPDATE
						form_besoin
					SET
						Obligatoire=1,
						Valide=1
					WHERE
						Id_Prestation=".$tabPresta[0]."
						AND Id_Pole=".$tabPresta[1]."
						AND Id_Formation=".$tabValeur[2]."
						AND Traite=0
						AND Id_Personne IN (".$Ids_Personne.");";
				
				$reqAncien="
					SELECT
						Obligatoire
					FROM
						form_prestation_metier_formation
					WHERE
						Id_Metier=".$tabValeur[0]."
						AND Id_Formation=".$tabValeur[2]."
						AND Id_Prestation=".$tabPresta[0]."
						AND Id_Pole=".$tabPresta[1]."
						AND Suppr=0;";
				
				$resultatAncien = getRessource($reqAncien);
				$rowAncien = mysqli_fetch_array($resultatAncien);
				//Il faut qu'il y ait des personne affectées par la modification
				if (strlen($Ids_Personne) > 0 )
				{
					//Passage en obligatoire
					if($rowAncien['Obligatoire'] == 0 && $tabValeur[1] == 1)
						getRessource($reqMAJ_enObligatoire);

					//Passage en facultatif
					if($rowAncien['Obligatoire'] == 1 && $tabValeur[1] == 0)
						getRessource($reqMAJ_enFacultatif);
				}
				//#################################################################################################
			}
		}
		
		
		//-------------------------------------------
		if(isset($_POST['saveHome']))
		{
			echo "<script>location.href = 'Tableau_De_Bord.php';</script>";
		}
	}
}
?>
<form id="formulaire" method="POST" action="Liste_Prestation_Metier_Formation.php">
<table style="width:100%; border-spacing:0; align:center;">
	<tr style="display:none;">
		<td><input name="Langue" id="Langue" value="<?php echo $LangueAffichage; ?>" /></td>
		<td><input name="DroitAF" id="DroitAF" value="<?php echo DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS); ?>" /></td>
		<?php
			$droit="";
			if(DroitsFormationPrestation($TableauIdPostesCQ) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ)){$droit="1";}
		?>
		<td><input name="DroitFormation" id="DroitFormation" value="<?php echo $droit; ?>" /></td>
	</tr>
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#67cff1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' style='cursor:pointer;' name='boutonHome' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' name='boutonHome' style='cursor:pointer;' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					
					if($LangueAffichage=="FR"){echo "Gestion des formations par métier";}else{echo "Training management by job";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="display:none;">
		<td>
			<input id="lIdPresta" name="lIdPresta" value="" />
			<input id="lMetierFormation" name="lMetierFormation" value="" />
		</td>
	</tr>
	<tr>
		<td valign="top" colspan="2">
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : 
						<?php 
						$requetePlateforme="SELECT DISTINCT Id_Plateforme AS Id, 
						(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
						FROM new_competences_personne_poste_plateforme 
						WHERE Id_Poste 
							IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
						AND Id_Personne=".$IdPersonneConnectee." 
						UNION
						SELECT DISTINCT 
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id, 
							(SELECT (SELECT Libelle FROM new_competences_plateforme 
							WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Libelle 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Poste 
							IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
						AND Id_Personne=".$IdPersonneConnectee." 
						ORDER BY Libelle";
						$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
						echo "<select name='Id_Plateforme' id='Id_Plateforme' OnChange='submit()' >";
						$Id_Plateforme=0;
						while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
							$selected="";
							if($_POST){if($_POST['Id_Plateforme']==$LigPlateforme[0]){$selected="selected";$Id_Plateforme=$LigPlateforme[0];}}
							else{
								if($Id_Plateforme==0){$Id_Plateforme=$LigPlateforme[0];}
							}
							echo "<option value='".$LigPlateforme[0]."' ".$selected.">".$LigPlateforme[1]."</option>";
						}
						echo "</select>";
						?>
					</td>
					<td class="Libelle" width="25%" align="left">
						<div id='Div_Prestations'></div>
						<?php
							if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
								$rqPrestation="SELECT Id AS Id_Prestation, 
									Id_Plateforme,
									Libelle,
									0 AS Id_Pole,
									'' AS Pole
									FROM new_competences_prestation 
									WHERE Id NOT IN (
										SELECT Id_Prestation
										FROM new_competences_pole
                                        WHERE Actif=0
									)
									AND new_competences_prestation.Active=0
									AND Id_Plateforme=".$Id_Plateforme."
									
									UNION
									
									SELECT Id_Prestation,
									new_competences_prestation.Id_Plateforme,
									new_competences_prestation.Libelle,
									new_competences_pole.Id AS Id_Pole,
									CONCAT(' - ',new_competences_pole.Libelle) AS Pole
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND new_competences_pole.Actif=0
									AND new_competences_prestation.Active=0
									AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme."
									ORDER BY Libelle, Pole";
							}
							else{
								$rqPrestation="SELECT Id AS Id_Prestation, 
									Id_Plateforme,
									Libelle,
									0 AS Id_Pole,
									'' AS Pole
									FROM new_competences_prestation 
									WHERE Id NOT IN (
										SELECT Id_Prestation
										FROM new_competences_pole 
                                        WHERE Actif=0   
									)
									AND (SELECT COUNT(Id)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
										AND Id_Personne=".$IdPersonneConnectee." 
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0
									AND new_competences_prestation.Active=0
									AND Id_Plateforme=".$Id_Plateforme."
									AND Active=0
									
									UNION
									
									SELECT Id_Prestation,
									new_competences_prestation.Id_Plateforme,
									new_competences_prestation.Libelle,
									new_competences_pole.Id AS Id_Pole,
									CONCAT(' - ',new_competences_pole.Libelle) AS Pole
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									WHERE (SELECT COUNT(Id)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
										AND Id_Personne=".$IdPersonneConnectee." 
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
										AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id)>0
									AND new_competences_pole.Actif=0
									AND new_competences_prestation.Active=0
									AND Id_Plateforme=".$Id_Plateforme."
									AND Active=0
									AND Actif=0
									ORDER BY Libelle, Pole";
							}
							$resultPrestation=mysqli_query($bdd,$rqPrestation);
							$i=0;
							echo "<select name='Id_Prestation' id='Id_Prestation' OnChange='submit()' style='width:250px' >";
							$Id_PrestationPole=0;
							echo "<option value='0_0' ></option>";
							while($rowPrestation=mysqli_fetch_array($resultPrestation))
							{
								$selected="";
								if($_POST){if($_POST['Id_Prestation']==$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']){$selected="selected";$Id_PrestationPole=$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole'];}}
								else{
									//if($Id_PrestationPole==0){$Id_PrestationPole=$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole'];}
								}
								echo "<option value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$selected.">".stripslashes($rowPrestation['Libelle'].$rowPrestation['Pole'])."</option>";
								echo '<script>ListePrestation['.$i.'] = new Array("'.$rowPrestation['Id_Prestation'].'_'.$rowPrestation['Id_Pole'].'","'.$rowPrestation['Id_Plateforme'].'","'.str_replace('"','',addslashes($rowPrestation['Libelle'].$rowPrestation['Pole'])).'")</script>;';
								echo "\n";
								$i++;
							}
							echo "</select>";
						?>
					</td>
					<td class="Libelle" align="left" width="15%">
						<?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?> :
						<select name='metier' Id='metier' style='width:150px' OnChange='RechargerListeMetierFormation();'>
							<option value='0'></option>
							<?php
							$rqMetier="SELECT Id, Libelle FROM new_competences_metier";
							$rqMetier.=" ORDER BY Libelle ASC";
							$resultMetier=mysqli_query($bdd,$rqMetier);
							while($LigMetier=mysqli_fetch_array($resultMetier))
							{
								$selected="";
								if($_POST){if($_POST['metier']==$LigMetier[0]){$selected="selected";}}
								echo "<option value='".$LigMetier[0]."' ".$selected.">".stripslashes($LigMetier[1])."</option>";
								echo "\n";
							}
							?>
						</select>
					</td>
					<td class="Libelle" align="left" width="25%">
						<div id='Div_Formations'></div>
					</td>
					<td width="5%" rowspan="2">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Tableau des formations par métier">
						</a>&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation(array($IdPosteCoordinateurEquipe,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){ ?>
	<tr>
		<td colspan="6"  align="right">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreGenererBesoins()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Regénérer des besoins";}else{echo "Regenerate needs";} ?>&nbsp;</a>
		</td>
	</tr>
	<?php } 
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesCQ)){
	?>
	<tr>
		<td height="5px">
		</td>
	</tr>
	<tr>
		<td colspan="6"  align="right">
		
		<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreSupprimerBesoinsPersonne()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Supprimer les besoins inutiles (personne)";}else{echo "Remove unnecessary needs (person)";} ?>&nbsp;</a>
		<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){?>	
			&nbsp;&nbsp;&nbsp;<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreSupprimerBesoinsPrestation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Supprimer les besoins inutiles (prestation)";}else{echo "Remove unnecessary needs (site)";} ?>&nbsp;</a>
		<?php } ?>
		</td>
	</tr>
	
	<?php } ?>
	<tr>
		<td></td>
		<td align="center">
			<input class="Bouton" style="display:none;font-size:15px;cursor:pointer;" type="submit" name="save" id="save" value="<?php if($LangueAffichage=="FR"){echo "Sauvegarder";}else{echo "Save";}?>"/>
			<input class="Bouton" style="display:none;cursor:pointer;" type="submit" name="save2" id="save2" value="Sauvegarder"/>
			<input class="Bouton" style="display:none;cursor:pointer;" type="submit" name="saveHome" id="saveHome" value="Sauvegarder"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a style='text-decoration:none;font-size:15px;' id="annuler" class='Bouton' href='javascript:RechargerListeMetierFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Annuler";}else{echo "Cancel";}?>&nbsp;</a>
		</td>
	</tr>
	<tr>
		<td valign="top" width="45%">
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="Libelle" width="30%">
						<?php
						  if($LangueAffichage=="FR")
						  {
							  echo "<input type='radio' name='Affichage_Choix' value='1' onchange='AffichageChoix();' checked=checked>Ajouter une/des formations pour un/des métier(s)<br>";
							  echo "<input type='radio' name='Affichage_Choix' value='2' onchange='AffichageChoix();'>Copier les formations d'un métier vers un autre<br>";
							  echo "<input type='radio' name='Affichage_Choix' value='3' onchange='AffichageChoix();'>Copier les formations des métiers d'une autre prestation";
						  }
						  else
						  {
							  echo "<input type='radio' name='Affichage_Choix' value='1' onchange='AffichageChoix();' checked=checked>Add one or more trainings for a job<br>";
							  echo "<input type='radio' name='Affichage_Choix' value='2' onchange='AffichageChoix();'>Copy trainings of this job<br>";
							  echo "<input type='radio' name='Affichage_Choix' value='3' onchange='AffichageChoix();'>Copy jobs trainings from other activity";
						  }
						  ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
						$rqMetier="SELECT Id, Libelle FROM new_competences_metier ORDER BY Libelle ASC";
						$resultMetier=mysqli_query($bdd,$rqMetier);
						$i=0;
						while($LigMetier=mysqli_fetch_array($resultMetier))
						{
							echo "<script>ListeMetier[".$i."] = new Array(\"".$LigMetier[0]."\",\"".stripslashes($LigMetier[1])."\");</script>\n";
							$i++;
						}
						?>
						<div id="Radio_Choix" style="width:100%;height:150px;overflow:auto;"></div>
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td class="Libelle" width="10%">
						<div id="Type_Titre">
						<?php if($LangueAffichage=="FR"){echo "Obligatoire / Facultative";}else{echo "Mandatory / Optional";}?> :
						</div>
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td align="left">
						<select name="Obligatoire" id="Obligatoire" onchange="DecocherFormations()">
							<?php
							if($LangueAffichage=="FR"){$Tableau=array('Obligatoire|1','Facultative|0');}
							else{$Tableau=array('Mandatory|1','Optional|0');}
							foreach($Tableau as $indice => $valeur)
							{
								$valeur=explode("|",$valeur);
								echo "<option value='".$valeur[1]."'>".$valeur[0]."</option>\n";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="Libelle" width="30%">
						<div id="Formation_Titre">
						<?php if($LangueAffichage=="FR"){echo "Formations";}else{echo "Training";}?> :
						</div> 
					</td>
				</tr>
				<tr>
					<td align="left">
						<div id="Div_Formation" style="height:100px;overflow:auto;"></div>
						<?php
						
						$requeteFormation="SELECT DISTINCT ";
						$requeteFormation.="		form_formation.Id,form_formation.Reference, ";
						$requeteFormation.="		form_formation.Id_Plateforme, ";
						$requeteFormation.="		form_formation_plateforme_parametres.Id_Langue,
													form_formation_langue_infos.Libelle,
													BesoinParametrableUniquementAF ";
						$requeteFormation.="FROM ";
						$requeteFormation.="		form_formation, ";
						$requeteFormation.="		form_formation_langue_infos, ";
						$requeteFormation.="		form_formation_plateforme_parametres ";
						$requeteFormation.="WHERE ";
						$requeteFormation.="		form_formation.Suppr=0 
											AND form_formation_plateforme_parametres.Suppr=0
											AND form_formation.Id_TypeFormation<>".$IdTypeFormationEprouvette."  ";
						$requeteFormation.="AND form_formation_langue_infos.Id_Formation = form_formation.Id ";
						$requeteFormation.="AND form_formation_langue_infos.Suppr = 0 ";
						$requeteFormation.="AND form_formation_langue_infos.Id_Langue = form_formation_plateforme_parametres.Id_Langue ";
						$requeteFormation.="AND form_formation_plateforme_parametres.Id_Formation = form_formation.Id 
											AND form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." ";

						$requeteFormation.="ORDER BY form_formation_langue_infos.Libelle ASC ";
						
						$resultFormation=mysqli_query($bdd,$requeteFormation);
						
						//Recherche du libellé de la formation au lieu de la référence
						//############################################################
						$ReqParametresFormations="
							SELECT
								Id_Formation,
								Id_Langue,
								(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS ORGANISME,
								Id_Plateforme
							FROM
								form_formation_plateforme_parametres
							WHERE Suppr=0
							AND Id_Plateforme=".$Id_Plateforme." ";
						$ResultParametresFormations=mysqli_query($bdd,$ReqParametresFormations);
						$NbParametresFormations=mysqli_num_rows($ResultParametresFormations);
						
						$ReqInfosFormations="
							SELECT
								Id_Formation,
								Id_Langue,
								Libelle,
								(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS LANGUE
							FROM
								form_formation_langue_infos
							WHERE
								Suppr=0
							ORDER BY
								LANGUE";
						$ResultInfosFormations=mysqli_query($bdd,$ReqInfosFormations);
						$NbInfosFormations=mysqli_num_rows($ResultInfosFormations);
						
						//############################################################
						
						$i=0;
						while($rowFormation=mysqli_fetch_array($resultFormation))
						{
							$Organisme="";
							$Id_Langue=1;
							if($NbParametresFormations>0)
							{
								mysqli_data_seek($ResultParametresFormations,0);
								while($RowParametresFormations=mysqli_fetch_array($ResultParametresFormations))
								{
									if($RowParametresFormations['Id_Formation']==$rowFormation['Id'] && $RowParametresFormations['Id_Plateforme']==$rowFormation['Id_Plateforme'])
									{
										if($RowParametresFormations['ORGANISME']!=NULL)
										{
											$Organisme=" (".$RowParametresFormations['ORGANISME'].")";
										}
										$Id_Langue=$RowParametresFormations['Id_Langue'];
										break;
									}
								}
							}

							echo '<script>ListeFormation['.$i.'] = new Array('.$rowFormation['Id'].','.$rowFormation['Id_Plateforme'].',"'.str_replace('"','',stripslashes($rowFormation['Libelle'].$Organisme)).'","'.str_replace('"','',stripslashes($rowFormation['Reference'])).'","'.$rowFormation['BesoinParametrableUniquementAF'].'");</script>';
							echo "\n";
							$i++;
						}
						?>
					</td>
				</tr>
				<tr>
					<td align='center' colspan='2' style='height:25px; valign:center;'>
						<?php if(DroitsFormationPrestation($TableauIdPostesCQ) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ)){ ?>
						<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterMetierFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}?>&nbsp;</a>
						<?php } ?>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" style="width:70%;">
			<div style="width:100%;height:400px;overflow:auto;">
			<div id="Div_Tableau_Metier_Formation"></div>
			</div>
			<?php
				$rqMetierFormation="SELECT
										form_prestation_metier_formation.Id,
										form_prestation_metier_formation.Id_Prestation,
										form_prestation_metier_formation.Id_Pole,
										(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_prestation_metier_formation.Id_Prestation) AS Id_Plateforme,
										(SELECT Libelle FROM new_competences_metier WHERE Id=form_prestation_metier_formation.Id_Metier) AS Metier,
										(SELECT Reference FROM form_formation WHERE Id=form_prestation_metier_formation.Id_Formation LIMIT 1) AS REFERENCE_FORMATION,
										form_prestation_metier_formation.Obligatoire,
										form_prestation_metier_formation.Id_Metier,
										form_prestation_metier_formation.Id_Formation,
										(
										SELECT form_formation_plateforme_parametres.BesoinParametrableUniquementAF
										FROM form_formation_plateforme_parametres
										WHERE form_formation_plateforme_parametres.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_prestation_metier_formation.Id_Prestation)
										AND form_formation_plateforme_parametres.Id_Formation=form_prestation_metier_formation.Id_Formation
										AND form_formation_plateforme_parametres.Suppr=0 LIMIT 1) BesoinParametrableUniquementAF,
										(SELECT COUNT(form_session_date.Id)
											FROM form_session_date,
											form_session
											WHERE form_session_date.Id_Session=form_session.Id
											AND form_session.Id_Formation=form_prestation_metier_formation.Id_Formation
											AND form_session_date.Suppr=0
											AND form_session_date.Id_Session IN (
												SELECT form_session_prestation.Id_Session
												FROM form_session_prestation
												WHERE form_session_prestation.Suppr=0 
												AND form_session_prestation.Id_Prestation=form_prestation_metier_formation.Id_Prestation
											)
											AND form_session.Suppr=0
											AND form_session.Annule=0
											AND (form_session_date.DateSession>='".date('Y-m-d')."'
											AND (SELECT COUNT(form_session_personne.Id)
												FROM form_session_personne
												LEFT JOIN form_besoin
												ON form_session_personne.Id_Besoin=form_besoin.Id
												WHERE form_session_personne.Suppr=0 
												AND form_session_personne.Id_Session=form_session.Id 
												AND form_session_personne.Validation_Inscription<>-1
												AND (SELECT COUNT(new_competences_personne_metier.Id) 
													FROM new_competences_personne_metier
													WHERE Id_Personne=form_besoin.Id_Personne
													AND Id_Metier=form_prestation_metier_formation.Id_Metier)>0
												AND form_besoin.Suppr=0                                                        
												AND form_besoin.Traite<3
												AND form_besoin.Id_Prestation=form_prestation_metier_formation.Id_Prestation
												AND form_besoin.Id_Pole=form_prestation_metier_formation.Id_Pole
												)>0
											)
										) AS NbSession
									FROM form_prestation_metier_formation,form_formation_langue_infos
									WHERE form_prestation_metier_formation.Suppr=0 
									AND form_formation_langue_infos.Id_Formation = form_prestation_metier_formation.Id_Formation
									AND CONCAT(form_prestation_metier_formation.Id_Prestation,'_',form_prestation_metier_formation.Id_Pole)='".$Id_PrestationPole."'
									AND form_formation_langue_infos.Suppr = 0 
									AND form_formation_langue_infos.Id_Langue = (
										SELECT form_formation_plateforme_parametres.Id_Langue
										FROM form_formation_plateforme_parametres
										WHERE form_formation_plateforme_parametres.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_prestation_metier_formation.Id_Prestation)
										AND form_formation_plateforme_parametres.Id_Formation=form_prestation_metier_formation.Id_Formation
										AND form_formation_plateforme_parametres.Suppr=0)
									ORDER BY Metier ASC, form_formation_langue_infos.Libelle ASC";
				$resultMetierFormation=mysqli_query($bdd,$rqMetierFormation);
				$i=0;
				echo "<script>";
				
				while($rowMetierFormation=mysqli_fetch_array($resultMetierFormation)){
					$Organisme="";
					$Id_Langue=1;
					if($NbParametresFormations>0)
					{
						mysqli_data_seek($ResultParametresFormations,0);
						while($RowParametresFormations=mysqli_fetch_array($ResultParametresFormations))
						{
							if($RowParametresFormations['Id_Formation']==$rowMetierFormation['Id_Formation'] && $RowParametresFormations['Id_Plateforme']==$rowMetierFormation['Id_Plateforme'])
							{
								if($RowParametresFormations['ORGANISME']!=NULL)
								{
									$Organisme=" (".addslashes($RowParametresFormations['ORGANISME']).")";
								}
								$Id_Langue=$RowParametresFormations['Id_Langue'];
								break;
							}
						}
					}
					
					$LibelleFormation="";
					if($NbInfosFormations>0)
					{
						mysqli_data_seek($ResultInfosFormations,0);
						while($RowInfosFormations=mysqli_fetch_array($ResultInfosFormations))
						{
							if($RowInfosFormations['Id_Formation']==$rowMetierFormation['Id_Formation'] && $RowInfosFormations['Id_Langue']==$Id_Langue)
							{
								$LibelleFormation=$RowInfosFormations['Libelle'];
								break;
							}
						}
					}
					if($Organisme<>""){$LibelleFormation.=" ".$Organisme;}
					
					$nbSession=$rowMetierFormation['NbSession'];
					echo 'ListeMetierFormation['.$i.'] = new Array('.$rowMetierFormation['Id'].',"'.$rowMetierFormation['Id_Prestation']."_".$rowMetierFormation['Id_Pole'].'","'.stripslashes($rowMetierFormation['Metier']).'","'.addslashes($rowMetierFormation['REFERENCE_FORMATION']).'",'.$rowMetierFormation['Obligatoire'].','.$rowMetierFormation['Id_Metier'].','.$rowMetierFormation['Id_Formation'].',"'.addslashes($LibelleFormation).$Organisme.'","'.$nbSession.'","'.$rowMetierFormation['BesoinParametrableUniquementAF'].'");';
					echo "\n";
					$i++;
				}
				echo "</script>";
			?>
		</td>
	</tr>
	<tr>
		<td colspan="10" align="center">
			<input type="hidden" id="laListeMetierFormation2" name="laListeMetierFormation2">
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" id="lesMetiersFormations" name="lesMetiersFormations" value="" readonly="readonly">
			<input type="hidden" id="lesMetiersFormationsASuppr" name="lesMetiersFormationsASuppr" value="" readonly="readonly">			
			<input type="hidden" id="post_prestation" value="<?php if($_POST){echo $_POST['Id_Prestation'];}?>"/>
			<input type="hidden" id="post_formation" value="<?php if($_POST){echo $_POST['formation'];}?>"/>
			<input type="hidden" id="bModif" value="<?php 
				if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesCQ)){
					echo "1";
				}
				else{
					echo "0";
				}
				?>"/>
		</td>
	</tr>
</table>
<script>
		RechargerListeFormation();
		RechargerListeMetierFormation();
		AffichageChoix();
</script>

</form>
</body>
</html>