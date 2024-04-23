<?php
require("../../Menu.php");

if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
	$requeteSite="SELECT Id, Libelle
		FROM new_competences_prestation
		WHERE Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
			)
		AND Active=0
		ORDER BY Libelle ASC";
}
elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
	$requeteSite="SELECT Id, Libelle
		FROM new_competences_prestation
		WHERE Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
			)
		AND Active=0
		ORDER BY Libelle ASC";
}
else{
	$requeteSite="SELECT Id, Libelle
		FROM new_competences_prestation
		WHERE Id IN 
			(SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			)
		AND Active=0
		ORDER BY Libelle ASC";
	
}

$resultPrestation=mysqli_query($bdd,$requeteSite);
$nbPrestation=mysqli_num_rows($resultPrestation);

$PrestationAAfficher=array();
if ($nbPrestation > 0)
{
	while($row=mysqli_fetch_array($resultPrestation))
	{
		array_push($PrestationAAfficher,$row['Id']);
	}
 }
?>
<form id="formulaire" class="test" action="Liste_StandardPCConforme.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#3835ff;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Standard PC conforme";}else{echo "Standard PC compliant";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="50%" valign="top">
			<table width="100%">
				<tr>
					<td colspan="2" align="center">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" align="center" style="color:#ffffff">
									ORDINATEUR DE BUREAU STANDARD + 1 ECRAN [+ 1 ECRAN]
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT DISTINCT
								tools_materiel.Id,
								NumAAA,
								TAB2.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
								tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id=165
								) AS NbEcran,
								(
									SELECT 
										COUNT(TAB_MAT.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE TAB_MAT.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id IN (452,271,453,451,405)
									AND TAB_MAT.Id<>tools_materiel.Id
								) AS NbAutresPC
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE Id_Personne>0
							AND tools_materiel.Suppr=0 
							AND TAB2.Id_Personne>0
							AND tools_famillemateriel.Id=271
							AND (SELECT LEFT(Libelle,1) FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation)='T'
					";
					$Requete.=" AND Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
					

					$requeteOrder="ORDER BY LIBELLE_PRESTATION, LIBELLE_POLE, NOMPRENOM_PERSONNE";
					
					if(isset($_GET['Page'])){$page=$_GET['Page'];}
					else{$page=0;}
					$_SESSION['Page_ToolsChangementMateriel']=$page;

					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);
					
					$nbPC=0;
					$nbEcran=0;
				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Personn";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Modèle de PC";}else{echo "PC model";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Nb écrans";}else{echo "Nb screens";}?></td>
											<td class="EnTeteTableauCompetences" width="12%"><?php if($LangueAffichage=="FR"){echo "Autres PC";}else{echo "Other PCs";}?></td>
										</tr>
										
									<?php
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($Row['NbEcran']>=1){$ok=1;}
											if($Row['NbAutresPC']>0){$ok=0;}
											
											if($ok==0){
												$nbPC++;
												$nbEcran+=$Row['NbEcran'];

												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
												
												$autresPC="";
												if($Row['NbAutresPC']>0){
													$req="SELECT 
																tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
																COUNT(TAB_MAT.Id) AS NbMachine,
																tools_famillemateriel.Id AS Id_FamilleMateriel
															FROM 
																(SELECT *
																FROM 
																(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
																FROM tools_mouvement
																WHERE tools_mouvement.TypeMouvement=0
																AND tools_mouvement.Suppr=0
																AND tools_mouvement.Type=0
																ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
																AS TAB
																GROUP BY Id_Materiel__Id_Caisse) AS TAB3
															LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
															LEFT JOIN
																tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
															LEFT JOIN
																tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
															LEFT JOIN
																tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
															WHERE TAB_MAT.Suppr=0 
															AND TAB3.Id_Personne=".$Row['Id_Personne']."
															AND tools_famillemateriel.Id IN (452,271,453,451,405)
															AND TAB_MAT.Id<>".$Row['Id']." 
															GROUP BY LIBELLE_MODELEMATERIEL
															ORDER BY LIBELLE_MODELEMATERIEL
															";
														$ResultPC=mysqli_query($bdd,$req);
														$NbPC=mysqli_num_rows($ResultPC);
														if($NbPC>0){
															while($RowPC=mysqli_fetch_array($ResultPC))
															{
																if($autresPC<>""){$autresPC.="<br>";}
																$autresPC.=$RowPC['NbMachine']." ".$RowPC['LIBELLE_MODELEMATERIEL'];
															}
														}
												}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
												<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
												<td <?php if($Row['NbEcran']>2){echo "bgcolor='#ff5757'";} ?>><?php echo stripslashes($Row['NbEcran']);?></td>
												<td><?php echo stripslashes($autresPC);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
										<tr bgcolor="#6EB4CD">
											<td></td>
											<td></td>
											<td><?php echo $nbPC;?></td>
											<td><?php echo $nbEcran;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" align="center" style="color:#ffffff">
									ORDINATEUR DE BUREAU CATIA + 1 ECRAN 24"     [+ 1 ECRAN 24" ]
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT DISTINCT
								tools_materiel.Id,
								NumAAA,
								TAB2.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
								tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id=165
								) AS NbEcran,
								(
									SELECT 
										COUNT(TAB_MAT.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE TAB_MAT.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id IN (452,271,453,451,405)
									AND TAB_MAT.Id<>tools_materiel.Id
								) AS NbAutresPC
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE Id_Personne>0
							AND tools_materiel.Suppr=0 
							AND TAB2.Id_Personne>0
							AND tools_famillemateriel.Id=452
							AND (SELECT LEFT(Libelle,1) FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation)='T'
					";
					$Requete.=" AND Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
					

					$requeteOrder="ORDER BY LIBELLE_PRESTATION, LIBELLE_POLE, NOMPRENOM_PERSONNE";
					
					if(isset($_GET['Page'])){$page=$_GET['Page'];}
					else{$page=0;}
					$_SESSION['Page_ToolsChangementMateriel']=$page;

					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);
					
					$nbPC=0;
					$nbEcran=0;
				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Personn";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Modèle de PC";}else{echo "PC model";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Nb écrans";}else{echo "Nb screens";}?></td>
											<td class="EnTeteTableauCompetences" width="12%"><?php if($LangueAffichage=="FR"){echo "Autres PC";}else{echo "Other PCs";}?></td>
										</tr>
										
									<?php
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($Row['NbEcran']>=1){$ok=1;}
	
											if($Row['NbAutresPC']>0){$ok=0;}
											
											if($ok==0){											
												$nbPC++;
												$nbEcran+=$Row['NbEcran'];
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
												
												$autresPC="";
												if($Row['NbAutresPC']>0){
													$req="SELECT 
																tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
																COUNT(TAB_MAT.Id) AS NbMachine,
																tools_famillemateriel.Id AS Id_FamilleMateriel
															FROM 
																(SELECT *
																FROM 
																(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
																FROM tools_mouvement
																WHERE tools_mouvement.TypeMouvement=0
																AND tools_mouvement.Suppr=0
																AND tools_mouvement.Type=0
																ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
																AS TAB
																GROUP BY Id_Materiel__Id_Caisse) AS TAB3
															LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
															LEFT JOIN
																tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
															LEFT JOIN
																tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
															LEFT JOIN
																tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
															WHERE TAB_MAT.Suppr=0 
															AND TAB3.Id_Personne=".$Row['Id_Personne']."
															AND tools_famillemateriel.Id IN (452,271,453,451,405)
															AND TAB_MAT.Id<>".$Row['Id']." 
															GROUP BY LIBELLE_MODELEMATERIEL
															ORDER BY LIBELLE_MODELEMATERIEL
															";
														$ResultPC=mysqli_query($bdd,$req);
														$NbPC=mysqli_num_rows($ResultPC);
														if($NbPC>0){
															while($RowPC=mysqli_fetch_array($ResultPC))
															{
																if($autresPC<>""){$autresPC.="<br>";}
																$autresPC.=$RowPC['NbMachine']." ".$RowPC['LIBELLE_MODELEMATERIEL'];
															}
														}
												}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
												<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
												<td <?php if($Row['NbEcran']>1){echo "bgcolor='#ff5757'";} ?>><?php echo stripslashes($Row['NbEcran']);?></td>
												<td><?php echo stripslashes($autresPC);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
										<tr bgcolor="#6EB4CD">
											<td></td>
											<td></td>
											<td><?php echo $nbPC;?></td>
											<td <?php if($nbEcran>0){echo "bgcolor='#ff5757'";}?>><?php echo $nbEcran;?></td>
											
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<table width="100%">
				<tr>
					<td colspan="2" align="center">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" align="center" style="color:#ffffff">
									ORDINATEUR PORTABLE STANDARD [+ 1 ECRAN &#8804; 23"]
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT DISTINCT
								tools_materiel.Id,
								NumAAA,
								TAB2.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
								tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id=165
								) AS NbEcran,
								(
									SELECT 
										COUNT(TAB_MAT.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE TAB_MAT.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id IN (452,271,453,451,405)
									AND TAB_MAT.Id<>tools_materiel.Id
								) AS NbAutresPC
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE Id_Personne>0
							AND tools_materiel.Suppr=0 
							AND TAB2.Id_Personne>0
							AND tools_famillemateriel.Id=451
							AND (SELECT LEFT(Libelle,1) FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation)='T'
					";
					$Requete.=" AND Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
					

					$requeteOrder="ORDER BY LIBELLE_PRESTATION, LIBELLE_POLE, NOMPRENOM_PERSONNE";
					
					if(isset($_GET['Page'])){$page=$_GET['Page'];}
					else{$page=0;}
					$_SESSION['Page_ToolsChangementMateriel']=$page;

					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);
					
					$nbPC=0;
					$nbEcran=0;
				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Personn";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Modèle de PC";}else{echo "PC model";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Nb écrans";}else{echo "Nb screens";}?></td>
											<td class="EnTeteTableauCompetences" width="12%"><?php if($LangueAffichage=="FR"){echo "Autres PC";}else{echo "Other PCs";}?></td>
										</tr>
										
									<?php
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($Row['NbEcran']>=1){$ok=1;}
											if($Row['NbAutresPC']>0){$ok=0;}
											
											if($ok==0){
												$nbPC++;
												$nbEcran+=$Row['NbEcran'];
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
												
												$autresPC="";
												if($Row['NbAutresPC']>0){
													$req="SELECT 
																tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
																COUNT(TAB_MAT.Id) AS NbMachine,
																tools_famillemateriel.Id AS Id_FamilleMateriel
															FROM 
																(SELECT *
																FROM 
																(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
																FROM tools_mouvement
																WHERE tools_mouvement.TypeMouvement=0
																AND tools_mouvement.Suppr=0
																AND tools_mouvement.Type=0
																ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
																AS TAB
																GROUP BY Id_Materiel__Id_Caisse) AS TAB3
															LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
															LEFT JOIN
																tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
															LEFT JOIN
																tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
															LEFT JOIN
																tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
															WHERE TAB_MAT.Suppr=0 
															AND TAB3.Id_Personne=".$Row['Id_Personne']."
															AND tools_famillemateriel.Id IN (452,271,453,451,405)
															AND TAB_MAT.Id<>".$Row['Id']." 
															GROUP BY LIBELLE_MODELEMATERIEL
															ORDER BY LIBELLE_MODELEMATERIEL
															";
														$ResultPC=mysqli_query($bdd,$req);
														$NbPC=mysqli_num_rows($ResultPC);
														if($NbPC>0){
															while($RowPC=mysqli_fetch_array($ResultPC))
															{
																if($autresPC<>""){$autresPC.="<br>";}
																$autresPC.=$RowPC['NbMachine']." ".$RowPC['LIBELLE_MODELEMATERIEL'];
																
															}
														}
												}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
												<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
												<td <?php if($Row['NbEcran']>0){echo "bgcolor='#ff5757'";} ?>><?php echo stripslashes($Row['NbEcran']);?></td>
												<td><?php echo stripslashes($autresPC);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
										<tr bgcolor="#6EB4CD">
											<td></td>
											<td></td>
											<td><?php echo $nbPC;?></td>
											<td><?php echo $nbEcran;?></td>
											
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" align="center" style="color:#ffffff">
									ORDINATEUR PORTABLE CATIA + 1 ECRAN 19 -> 24" 
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT DISTINCT
								tools_materiel.Id,
								NumAAA,
								TAB2.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
								tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id=165
								) AS NbEcran,
								(
									SELECT 
										COUNT(TAB_MAT.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE TAB_MAT.Suppr=0 
									AND TAB3.Id_Personne=TAB2.Id_Personne
									AND tools_famillemateriel.Id IN (452,271,453,451,405)
									AND TAB_MAT.Id<>tools_materiel.Id
								) AS NbAutresPC
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE Id_Personne>0
							AND tools_materiel.Suppr=0 
							AND TAB2.Id_Personne>0
							AND tools_famillemateriel.Id=453
							AND (SELECT LEFT(Libelle,1) FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation)='T'
					";
					$Requete.=" AND Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
					

					$requeteOrder="ORDER BY LIBELLE_PRESTATION, LIBELLE_POLE, NOMPRENOM_PERSONNE";
					
					if(isset($_GET['Page'])){$page=$_GET['Page'];}
					else{$page=0;}
					$_SESSION['Page_ToolsChangementMateriel']=$page;

					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);
					
					$nbPC=0;
					$nbEcran=0;
				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Personn";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Modèle de PC";}else{echo "PC model";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Nb écrans";}else{echo "Nb screens";}?></td>
											<td class="EnTeteTableauCompetences" width="12%"><?php if($LangueAffichage=="FR"){echo "Autres PC";}else{echo "Other PCs";}?></td>
										</tr>
										
									<?php
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($Row['NbEcran']>=1){$ok=1;}
											if($Row['NbAutresPC']>0){$ok=0;}
											
											if($ok==0){
												
												$nbPC++;
												$nbEcran+=$Row['NbEcran'];
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
												
												$autresPC="";
												if($Row['NbAutresPC']>0){
													$req="SELECT 
																tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
																COUNT(TAB_MAT.Id) AS NbMachine,
																tools_famillemateriel.Id AS Id_FamilleMateriel
															FROM 
																(SELECT *
																FROM 
																(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
																FROM tools_mouvement
																WHERE tools_mouvement.TypeMouvement=0
																AND tools_mouvement.Suppr=0
																AND tools_mouvement.Type=0
																ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
																AS TAB
																GROUP BY Id_Materiel__Id_Caisse) AS TAB3
															LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
															LEFT JOIN
																tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
															LEFT JOIN
																tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
															LEFT JOIN
																tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
															WHERE TAB_MAT.Suppr=0 
															AND TAB3.Id_Personne=".$Row['Id_Personne']."
															AND tools_famillemateriel.Id IN (452,271,453,451,405)
															AND TAB_MAT.Id<>".$Row['Id']." 
															GROUP BY LIBELLE_MODELEMATERIEL
															ORDER BY LIBELLE_MODELEMATERIEL
															";
														$ResultPC=mysqli_query($bdd,$req);
														$NbPC=mysqli_num_rows($ResultPC);
														if($NbPC>0){
															while($RowPC=mysqli_fetch_array($ResultPC))
															{
																if($autresPC<>""){$autresPC.="<br>";}
																$autresPC.=$RowPC['NbMachine']." ".$RowPC['LIBELLE_MODELEMATERIEL'];
															}
														}
												}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
												<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
												<td <?php if($Row['NbEcran']>1){echo "bgcolor='#ff5757'";} ?>><?php echo stripslashes($Row['NbEcran']);?></td>
												<td><?php echo stripslashes($autresPC);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
										<tr bgcolor="#6EB4CD">
											<td></td>
											<td></td>
											<td><?php echo $nbPC;?></td>
											<td><?php echo $nbEcran;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="50%" valign="top">
			<table>
				<tr>
					<td colspan="2" align="center">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" style="color:#ffffff" align="center">
									PERSONNES AVEC ECRANS SANS PC
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT 
								TAB2.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
								tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE Id_Personne>0
							AND tools_materiel.Suppr=0 
							AND TAB2.Id_Personne>0
							AND tools_famillemateriel.Id=165
							AND (
								SELECT 
									COUNT(TAB_MAT.Id)
								FROM 
									(SELECT *
									FROM 
									(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
									FROM tools_mouvement
									WHERE tools_mouvement.TypeMouvement=0
									AND tools_mouvement.Suppr=0
									AND tools_mouvement.Type=0
									ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
									AS TAB
									GROUP BY Id_Materiel__Id_Caisse) AS TAB3
								LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
								LEFT JOIN
									tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
								LEFT JOIN
									tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
								LEFT JOIN
									tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
								WHERE TAB_MAT.Suppr=0 
								AND TAB3.Id_Personne=TAB2.Id_Personne
								AND tools_famillemateriel.Id IN (452,271,453,451,405)
							) = 0
					";
					$Requete.=" AND Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
					

					$requeteOrder="ORDER BY LIBELLE_PRESTATION, LIBELLE_POLE, NOMPRENOM_PERSONNE";
				
					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);
					
				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Personn";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Modèle écran";}else{echo "Screen template";}?></td>
										</tr>
										
									<?php
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($ok==0){
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
												<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" style="color:#ffffff" align="center">
									PERSONNES AVEC PC FIXE SANS ECRAN
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT 
								TAB2.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=TAB2.Id_Personne) AS NOMPRENOM_PERSONNE,
								tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE Id_Personne>0
							AND tools_materiel.Suppr=0 
							AND TAB2.Id_Personne>0
							AND tools_famillemateriel.Id IN (452,271)
							AND (
								SELECT 
									COUNT(TAB_MAT.Id)
								FROM 
									(SELECT *
									FROM 
									(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,(@row_number:=@row_number + 1) AS rnk
									FROM tools_mouvement
									WHERE tools_mouvement.TypeMouvement=0
									AND tools_mouvement.Suppr=0
									AND tools_mouvement.Type=0
									ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
									AS TAB
									GROUP BY Id_Materiel__Id_Caisse) AS TAB3
								LEFT JOIN tools_materiel AS TAB_MAT ON TAB_MAT.Id=TAB3.Id_Materiel__Id_Caisse
								LEFT JOIN
									tools_modelemateriel ON TAB_MAT.Id_ModeleMateriel=tools_modelemateriel.Id
								LEFT JOIN
									tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
								LEFT JOIN
									tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
								WHERE TAB_MAT.Suppr=0 
								AND TAB3.Id_Personne=TAB2.Id_Personne
								AND tools_famillemateriel.Id=165
							) = 0
					";
					$Requete.=" AND Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
					

					$requeteOrder="ORDER BY LIBELLE_PRESTATION, LIBELLE_POLE, NOMPRENOM_PERSONNE";
				
					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);

				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Personn";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Modèle de PC";}else{echo "PC model";}?></td>
										</tr>
										
									<?php
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($ok==0){
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
												<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<table width="100%">
				<tr>
					<td colspan="2" align="center" valign="top">
						<table class="TableCompetences" style="background-color:#3835ff" width="100%">
							<tr>
								<td class="Libelle" style="color:#ffffff" align="center">
									ORDINATEURS / PRESTATION
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
					//PARTIE OUTILS DE LA REQUETE
					$Requete="
							SELECT 
								(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation) AS LIBELLE_PRESTATION,
								(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=TAB2.Id_Pole) AS LIBELLE_POLE,
								COUNT(tools_materiel.Id) AS Nb,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, ID_Lieu,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB4
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Prestation=TAB2.Id_Prestation
									AND TAB3.Id_Pole=TAB2.Id_Pole
									AND TAB3.Id_Lieu NOT IN (1,2,3,4,5,6,7,8,9,10,11,188)
									AND tools_famillemateriel.Id=271
								) AS NbPCFixe,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, ID_Lieu,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB4
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Prestation=TAB2.Id_Prestation
									AND TAB3.Id_Pole=TAB2.Id_Pole
									AND TAB3.Id_Lieu NOT IN (1,2,3,4,5,6,7,8,9,10,11,188)
									AND tools_famillemateriel.Id=452
								) AS NbPCFixeCatia,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, ID_Lieu,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB4
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Prestation=TAB2.Id_Prestation
									AND TAB3.Id_Pole=TAB2.Id_Pole
									AND TAB3.Id_Lieu NOT IN (1,2,3,4,5,6,7,8,9,10,11,188)
									AND tools_famillemateriel.Id=451
								) AS NbPCPortable,
								(
									SELECT 
										COUNT(tools_materiel.Id)
									FROM 
										(SELECT *
										FROM 
										(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, ID_Lieu,(@row_number:=@row_number + 1) AS rnk
										FROM tools_mouvement
										WHERE tools_mouvement.TypeMouvement=0
										AND tools_mouvement.Suppr=0
										AND tools_mouvement.Type=0
										ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
										AS TAB4
										GROUP BY Id_Materiel__Id_Caisse) AS TAB3
									LEFT JOIN tools_materiel ON tools_materiel.Id=TAB3.Id_Materiel__Id_Caisse
									LEFT JOIN
										tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
									LEFT JOIN
										tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
									LEFT JOIN
										tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
									WHERE tools_materiel.Suppr=0 
									AND TAB3.Id_Prestation=TAB2.Id_Prestation
									AND TAB3.Id_Lieu NOT IN (1,2,3,4,5,6,7,8,9,10,11,188)
									AND TAB3.Id_Pole=TAB2.Id_Pole
									AND tools_famillemateriel.Id=453
								) AS NbPCPortableCatia
							FROM 
								(SELECT *
								FROM 
								(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception,Id_Lieu,(@row_number:=@row_number + 1) AS rnk
								FROM tools_mouvement
								WHERE tools_mouvement.TypeMouvement=0
								AND tools_mouvement.Suppr=0
								AND tools_mouvement.Type=0
								ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC)
								AS TAB
								GROUP BY Id_Materiel__Id_Caisse) AS TAB2
							LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
							LEFT JOIN
								tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
							LEFT JOIN
								tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
							LEFT JOIN
								tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
							WHERE tools_materiel.Suppr=0 
							AND tools_famillemateriel.Id IN (271,451,452,453)
							AND TAB2.Id_Lieu NOT IN (1,2,3,4,5,6,7,8,9,10,11,188)
							AND (SELECT LEFT(Libelle,1) FROM new_competences_prestation WHERE new_competences_prestation.Id=TAB2.Id_Prestation)='T'
							GROUP BY Id_Prestation,Id_Pole
					";
					$requeteOrder="ORDER BY Nb DESC";
				
					$Result=mysqli_query($bdd,$Requete.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);
				?>
				<tr>
					<td width="100%">
						<table width="100%">
							<tr>
								<td width="100%" align="center">
									<table class="TableCompetences" width="100%">
										<tr>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Ordinateur de bureau Standard";}else{echo "Standard Desktop Computer";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Ordinateur de bureau Catia";}else{echo "Catia desktop computer";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Ordinateur portable Standard";}else{echo "Standard Laptop";}?></td>
											<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Ordinateur portable Catia";}else{echo "Notebook Catia";}?></td>
											<td class="EnTeteTableauCompetences" width="5%"><?php if($LangueAffichage=="FR"){echo "Total";}else{echo "Total";}?></td>
										</tr>
										
									<?php
										$NbPCFixe=0;
										$NbPCFixeCatia=0;
										$NbPCPortable=0;
										$NbPCPortableCatia=0;
										$NbPC=0;
										if($NbEnreg>0)
										{
										$Couleur="#EEEEEE";
										while($Row=mysqli_fetch_array($Result))
										{
											$ok=0;
											if($ok==0){
												if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
												else{$Couleur="#EEEEEE";}
												
												$NbPCFixe+=$Row['NbPCFixe'];
												$NbPCFixeCatia+=$Row['NbPCFixeCatia'];
												$NbPCPortable+=$Row['NbPCPortable'];
												$NbPCPortableCatia+=$Row['NbPCPortableCatia'];
												$NbPC+=$Row['Nb'];
										
												$LIBELLE_POLE="";
												if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
										?>
											<tr bgcolor="<?php echo $Couleur;?>">
												<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
												<td><?php echo stripslashes($Row['NbPCFixe']);?></td>
												<td><?php echo stripslashes($Row['NbPCFixeCatia']);?></td>
												<td><?php echo stripslashes($Row['NbPCPortable']);?></td>
												<td><?php echo stripslashes($Row['NbPCPortableCatia']);?></td>
												<td><?php echo stripslashes($Row['Nb']);?></td>
											</tr>
										<?php
											}
										}	//Fin boucle
									}		//Fin If
									mysqli_free_result($Result);	// Libération des résultats
									?>
										<tr bgcolor="#6EB4CD">
											<td></td>
											<td><?php echo $NbPCFixe;?></td>
											<td><?php echo $NbPCFixeCatia;?></td>
											<td><?php echo $NbPCPortable;?></td>
											<td><?php echo $NbPCPortableCatia;?></td>
											<td><?php echo $NbPC;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</td>
</table>
</form>
</body>
</html>