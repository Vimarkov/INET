<?php
require("../../Menu.php");
?>
<form class="test" action="Liste_PersonneSansMateriel.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#51c9e9;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Personnes sans matériel ";}else{echo "People without equipment";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
				<select class="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
						)
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);

				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreToolsPersonnesSansMateriel_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreToolsPersonnesSansMateriel_Plateforme']=$PlateformeSelect;	
				
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect<>"")
							{if($PlateformeSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 else{
					 echo "<option name='0' value='0' Selected></option>";
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
					$requeteSite="SELECT Id, Libelle, Active
						FROM new_competences_prestation
						WHERE Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
							)
						ORDER BY Libelle ASC";


				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreToolsPersonnesSansMateriel_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsPersonnesSansMateriel_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				$PrestationAAfficher=array();
				if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
					array_push($PrestationAAfficher,0);
				}
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						$active="";
						if($row['Active']<>0){$active=" [INACTIVE]";}
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])." ".$active."</option>\n";
						array_push($PrestationAAfficher,$row['Id']);
					}
				 }
				 ?>
				</select>
			</td>
			<td width="40%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table style="width:100%;">

				<tr>
						<td width="50%" align="center" valign="top">
							<div style="width:100%;height:400px;overflow:auto;">
							<table class="TableCompetences" style="width:75%;">
								<tr>
									<td class="EnTeteTableauCompetences" width="18%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
									<td class="EnTeteTableauCompetences" width="30%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
									<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Type of contract";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Agence d'intérim";}else{echo "Acting Agency";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date entrée";}else{echo "Date entered";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin du dernier contrat";}else{echo "End date of last contract";} ?></td>
								</tr>
								<?php 
									$dateDebut=date('Y-m-d');
									$dateFin=date('Y-m-d');

									$req="SELECT DISTINCT Id_Personne,
									CONCAT(Nom,' ',Prenom) AS Personne
									FROM rh_personne_contrat
									LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne
									WHERE Suppr=0
									AND DateDebut<='".$dateFin."'
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									AND Id_Personne NOT IN (
										SELECT 
											TAB2.Id_Personne
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
											AND tools_famillemateriel.Id IN (165,271,452,451,453,405)
									)
									AND (
										SELECT 
											COUNT(TAB2.Id_Personne)
										FROM 
											(SELECT *
												FROM 
												(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, Commentaire,(@row_number:=@row_number + 1) AS rnk
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
											AND tools_famillemateriel.Id IN (165,271,452,451,453,405)
											AND TAB2.Commentaire LIKE CONCAT('%',Nom,' ',Prenom,'%')
									)=0
									ORDER BY Personne ASC";

									$resultEntree=mysqli_query($bdd,$req);
									$nbEntree=mysqli_num_rows($resultEntree);
									
									$couleur="#FFFFFF";
									if($nbEntree>0){
										while($rowpersonne=mysqli_fetch_array($resultEntree))
										{
											//Prestation et date d'entrée à cette date 
											$req="SELECT Id_Prestation, Id_Pole,
												(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
												(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
												FROM rh_personne_mouvement
												WHERE Suppr=0
												AND Id_Personne=".$rowpersonne['Id_Personne']." 
												AND EtatValidation=1
												AND rh_personne_mouvement.DateDebut<='".$dateFin."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."') ";
											$result=mysqli_query($bdd,$req);
											$nb=mysqli_num_rows($result);
											$Prestation="";
											$Id_Presta=0;
											
											if($nb>0){
												$rowMouv=mysqli_fetch_array($result);
												$Prestation=substr($rowMouv['Prestation'],0,7);
												$Id_Presta=$rowMouv['Id_Prestation'];
												if($rowMouv['Id_Pole']>0){
													$Prestation.=" - ".$rowMouv['Pole'];
												}
											}
											
											$PrestaOK=1;
											if(substr($Prestation,0,1)=="M" || substr($Prestation,2,5)=="LMTOU" || substr($Prestation,2,5)=="SCALI" || substr($Prestation,2,5)=="YYYYY" || substr($Prestation,2,5)=="CIF00" || substr($Prestation,2,5)=="CSS00" || substr($Prestation,2,5)=="PAR00" || substr($Prestation,2,5)=="SAB00" || substr($Prestation,2,5)=="PSETO"  || substr($Prestation,2,5)=="PSEMS"){$PrestaOK=0;}
											
											if($PrestationSelect<>0){
												if($Id_Presta<>$PrestationSelect){$PrestaOK=0;}
												else{$PrestaOK=1;}
											}
											$DateEntree="";
											$Interim="";
											$Metier="";
											$TypeContrat="";
											$ColBlanc=0;
											$req="SELECT DateDebut,DateFin,
											(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
											(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
											(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
											(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
											(SELECT IF(Col='Blanc' OR Col='',1,0) FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS ColBlanc
											FROM rh_personne_contrat
											WHERE Suppr=0
											AND Id_Personne=".$rowpersonne['Id_Personne']."
											AND DateDebut<='".$dateFin."'
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
											AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
											AND TypeDocument IN ('Nouveau','Avenant')
											ORDER BY DateDebut ASC";
											$resultDate=mysqli_query($bdd,$req);
											$nbDate=mysqli_num_rows($resultDate);
											if($nbDate>0){
												$rowDate=mysqli_fetch_array($resultDate);
												$DateEntree=AfficheDateJJ_MM_AAAA($rowDate['DateDebut']);
												if($rowDate['ColBlanc']>0){
												$ColBlanc=1;
												}
												if($rowDate['EstInterim']>0){
													$Interim=$rowDate['AgenceInterim'];
												}
												$TypeContrat=$rowDate['TypeContrat'];
												$Metier=$rowDate['Metier'];
											}
											
											
											$DateFin="";
											$req="SELECT DateDebut,DateFin
											FROM rh_personne_contrat
											WHERE Suppr=0
											AND Id_Personne=".$rowpersonne['Id_Personne']."
											AND DateDebut<='".$dateFin."'
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
											AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
											AND TypeDocument IN ('Nouveau','Avenant')
											ORDER BY DateDebut DESC";
											$resultDate=mysqli_query($bdd,$req);
											$nbDate=mysqli_num_rows($resultDate);
											if($nbDate>0){
												$rowDate=mysqli_fetch_array($resultDate);

												$DateFin=AfficheDateJJ_MM_AAAA($rowDate['DateFin']);
											}
											
											if($ColBlanc==1 && $PrestaOK==1){
												if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
												else{$couleur="#FFFFFF";}
											
												echo "<tr bgcolor=".$couleur.">";
													echo "<td>".$rowpersonne['Personne']."</td>";
													echo "<td>".$Prestation."</td>";
													echo "<td>".$Metier."</td>";
													echo "<td>".$TypeContrat."</td>";
													echo "<td>".$Interim."</td>";
													echo "<td>".$DateEntree."</td>";
													echo "<td>".$DateFin."</td>";
												echo "</tr>";
											}
										}
									}
								?>
							</table>
							</div>
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