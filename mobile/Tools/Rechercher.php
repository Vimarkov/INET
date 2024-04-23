
				<?php
					if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX))){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")
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
								AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
								)
							AND Active=0
							ORDER BY Libelle ASC";
						
					}
					$resultPrestation=mysqli_query($bdd,$requeteSite);
					$nbPrestation=mysqli_num_rows($resultPrestation);
					
					$PrestationSelect = 0;
					$Selected = "";
					
					$PrestationSelect=$_SESSION['FiltreToolsSuivi_Prestation'];
					$_SESSION['FiltreToolsSuivi_Prestation']=$PrestationSelect;	
					
					$PrestationAAfficher=array();
					if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX))){
						array_push($PrestationAAfficher,0);
					}
					if ($nbPrestation > 0)
					{
						while($row=mysqli_fetch_array($resultPrestation))
						{
							$selected="";
							if($PrestationSelect<>"")
								{if($PrestationSelect==$row['Id']){$selected="selected";}}
							array_push($PrestationAAfficher,$row['Id']);
						}
					 }
					 
					$Requete2="
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
						tools_typemateriel.Id AS ID_TYPEMATERIEL,
						tools_typemateriel.Libelle AS TYPEMATERIEL,
						tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
						tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
						(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
						(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
						(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
						(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
						(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
						(
							SELECT new_competences_prestation.Libelle
							FROM tools_mouvement
							LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_PRESTATION,
						(
							SELECT new_competences_pole.Libelle
							FROM tools_mouvement
							LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_POLE,
						(
							SELECT tools_lieu.Libelle
							FROM tools_mouvement
							LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_LIEU,
						(
							SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
							FROM tools_mouvement
							LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
							LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_CAISSETYPE,
						(
							SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom)
							FROM tools_mouvement
							LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS NOMPRENOM_PERSONNE ";
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

					if($_SESSION['FiltreToolsSuivi_Num']<>""){
						$Requete.=" AND (
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
							) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
							OR 
							SN LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
							OR  
							Remarques LIKE \"%".$_SESSION['FiltreToolsSuivi_Num']."%\"
							OR 
							NumAAA LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%'
							OR 
							(SELECT CONCAT(Nom,' ',Prenom) FROM tools_mouvement LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id WHERE TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%'
							OR
							(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%'
							)
							";
					}
					
					$Requete.=" AND (SELECT Id_Prestation FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Type=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN (".implode(',',$PrestationAAfficher).") ";

					//PARTIE CAISSE DE LA REQUETE
					$Requete2Caisse=" UNION ALL
						SELECT Id,
						'Caisse' AS TYPESELECT,
						Num AS NumAAA,
						'' AS SN,
						'' AS Num,
						-1 AS Id_TYPEMATERIEL,
						'' AS TYPEMATERIEL,
						'' AS FAMILLEMATERIEL,
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
						'' AS LIBELLE_FOURNISSEUR,
						'' AS LIBELLE_FABRICANT,
						(SELECT DateReception FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
						(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
						(SELECT DateReception FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
						(
							SELECT new_competences_prestation.Libelle
							FROM tools_mouvement
							LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_PRESTATION,
						(
							SELECT new_competences_pole.Libelle
							FROM tools_mouvement
							LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_POLE,
						(
							SELECT tools_lieu.Libelle
							FROM tools_mouvement
							LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_LIEU,
						(
							SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
							FROM tools_mouvement
							LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
							LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS LIBELLE_CAISSETYPE,
						(
							SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom)
							FROM tools_mouvement
							LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
							WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
							ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						) AS NOMPRENOM_PERSONNE ";
					$RequeteCaisse="FROM
						tools_caisse
					WHERE 
						tools_caisse.Suppr=0 ";
					
					if($_SESSION['FiltreToolsSuivi_Num']<>""){
						$RequeteCaisse.=" AND (
									Num LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%'
									OR 
									(SELECT CONCAT(Nom,' ',Prenom) FROM tools_mouvement LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id WHERE TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%'
									OR 
									(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%'
						
						) ";
					}

					$RequeteCaisse.=" AND (SELECT Id_Prestation FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN (".implode(',',$PrestationAAfficher).") ";

					$requeteOrder=" ORDER BY NumAAA ";

					$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder);
					$NbEnreg=mysqli_num_rows($Result);

					if($NbEnreg>0)
					{
						$Couleur="#EEEEEE";
						while($Row=mysqli_fetch_array($Result))
						{
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
							
							$LIBELLE_POLE="";
							if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
							?>
							<tr>
								<td align="left" style="font-size:30px;">
								<table width="100%">
									<tr>
										<td style="font-size:30px;" colspan="2"><b><?php echo $Row['FAMILLEMATERIEL']." - ".$Row['LIBELLE_MODELEMATERIEL'];?></b></td>
									</tr>
									<tr>
										<td style="font-size:30px;" width="30%" valign="top"><?php echo $Row['NumAAA'];?></td>
										<td style="font-size:30px;" width="67%" rowspan="3">
											<table width="100%" style="border:1px solid black">
												<tr><td style="font-size:25px;"><?php echo substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE;?></td></tr>
												<tr><td style="font-size:25px;"><?php echo $Row['LIBELLE_CAISSETYPE'];?></td></tr>
												<tr><td style="font-size:25px;"><?php echo $Row['LIBELLE_LIEU'];?></td></tr>
												<tr><td style="font-size:25px;"><b><?php echo $Row['NOMPRENOM_PERSONNE'];?></b></td></tr>
												<tr><td style="font-size:25px;"><?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td></tr>
											</table>
										</td>
										<td width="3%" valign="center" rowspan="3">
											<?php
											/*
												if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX)))
												{
													if($Row['TYPESELECT']=="Outils")
													{
												?>
														<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfert('<?php echo $Row['ID']; ?>');" ><img src="../../v2/Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="50px" border="0"></a>
												<?php 			
													}
													else
													{
												?>
														<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertCaisse('<?php echo $Row['ID']; ?>');" ><img src="../../v2/Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="50px" border="0"></a>
												<?php 			
													}
												}
												*/
											?>
										</td>
									</tr>
									<tr>
										<td style="font-size:30px;"><b><?php echo $Row['SN'];?></b></td>
									</tr>
									<tr>
										<td style="font-size:30px;" colspan="2"><b><?php if($Row['Num']<>0){echo $Row['Num'];}?></b></td>
									</tr>
									<tr>
										<td style="border-bottom:1px solid #d0d0d0" colspan="2"></td>
									</tr>
								</table>
								</td>
							</tr>
							<?php
						}
					}
				?>
			
	