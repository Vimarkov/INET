<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_LIEU",'DateDerniereAffectation','DernierePresta','DernierLieu','DerniereCaisse','DerniereCaisse','DernierePersonne');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsOutilsEtalonnage_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsOutilsEtalonnage_General']);
			$_SESSION['TriToolsOutilsEtalonnage_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsOutilsEtalonnage_General']);
			$_SESSION['TriToolsOutilsEtalonnage_General']= str_replace($tri." ASC","",$_SESSION['TriToolsOutilsEtalonnage_General']);
			$_SESSION['TriToolsOutilsEtalonnage_General']= str_replace($tri." DESC","",$_SESSION['TriToolsOutilsEtalonnage_General']);
			if($_SESSION['TriToolsOutilsEtalonnage_'.$tri]==""){$_SESSION['TriToolsOutilsEtalonnage_'.$tri]="ASC";$_SESSION['TriToolsOutilsEtalonnage_General'].= $tri." ".$_SESSION['TriToolsOutilsEtalonnage_'.$tri].",";}
			elseif($_SESSION['TriToolsOutilsEtalonnage_'.$tri]=="ASC"){$_SESSION['TriToolsOutilsEtalonnage_'.$tri]="DESC";$_SESSION['TriToolsOutilsEtalonnage_General'].= $tri." ".$_SESSION['TriToolsOutilsEtalonnage_'.$tri].",";}
			else{$_SESSION['TriToolsOutilsEtalonnage_'.$tri]="";}
		}
	}
}

?>
<form id="formulaire" class="test" action="HistoriquePrestation.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#dbf0b5;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Historique prestation";}else{echo "Site history";}
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
			<?php
				$numAAA=$_SESSION['FiltreToolsOutilsEtalonnage_NumAAA'];
				if($_POST){$numAAA=$_POST['numAAA'];}
				$_SESSION['FiltreToolsOutilsEtalonnage_NumAAA']=$numAAA;
			?>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?>
				<input id="numAAA" name="numAAA" type="texte" value="<?php echo $numAAA; ?>" size="15"/>&nbsp;&nbsp;
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php

					if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteDirection))){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteDirection.")
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
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreToolsOutilsEtalonnage_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsOutilsEtalonnage_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				$PrestationAAfficher=array();
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						array_push($PrestationAAfficher,$row['Id']);
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteDirection))){
					$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteDirection.")
							)
							AND Actif=0
							AND new_competences_pole.Id_Prestation=".$PrestationSelect."
							ORDER BY new_competences_pole.Libelle ASC";
				}
				elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
					$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
							)
							AND Actif=0
							AND new_competences_pole.Id_Prestation=".$PrestationSelect."
							ORDER BY new_competences_pole.Libelle ASC";
				}
				else{
					$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
						FROM new_competences_pole
						LEFT JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE new_competences_pole.Id IN 
							(SELECT Id_Pole 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)
						AND Actif=0
						AND new_competences_pole.Id_Prestation=".$PrestationSelect."
						ORDER BY new_competences_pole.Libelle ASC";
				}
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreToolsOutilsEtalonnage_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsOutilsEtalonnage_Pole']=$PoleSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$requetePersonne="
							SELECT
								DISTINCT new_rh_etatcivil.Id, 
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM new_rh_etatcivil
							
							ORDER BY
								Personne ASC";

						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreToolsOutilsEtalonnage_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreToolsOutilsEtalonnage_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de matériel :";}else{echo "Kind of material :";} ?>
				<select style="width:100px;" name="typeMateriel" onchange="submit();">
				<?php

				$RequeteTypeMateriel="
					SELECT
						Id,
						Libelle
					FROM
						tools_typemateriel
					WHERE
						Suppr=0
					ORDER BY
						Libelle ASC";
				$ResultTypeMateriel=mysqli_query($bdd,$RequeteTypeMateriel);
				$nbTypeMateriel=mysqli_num_rows($ResultTypeMateriel);
				
				$Selected = "";
				$TypeMaterielSelect=$_SESSION['FiltreToolsOutilsEtalonnage_TypeMateriel'];
				if($_POST){$TypeMaterielSelect=$_POST['typeMateriel'];}
				$_SESSION['FiltreToolsOutilsEtalonnage_TypeMateriel']=$TypeMaterielSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbTypeMateriel > 0)
				{
					while($row=mysqli_fetch_array($ResultTypeMateriel))
					{
						$selected="";
						if($TypeMaterielSelect<>"")
							{if($TypeMaterielSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Famille de matériel :";}else{echo "Family of material :";} ?>
				<select style="width:100px;" name="familleMateriel" onchange="submit();">
				<?php

				$Requete="
					SELECT
							Id,
							Libelle
						FROM
							tools_famillemateriel
						WHERE
							Suppr=0
						AND Id_TypeMateriel=".$TypeMaterielSelect."
						ORDER BY
							Libelle ASC";
				$ResultTypeMateriel=mysqli_query($bdd,$Requete);
				$nbTypeMateriel=mysqli_num_rows($ResultTypeMateriel);
				
				$Selected = "";
				$FamilleMaterielSelect=$_SESSION['FiltreToolsOutilsEtalonnage_FamilleMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				$_SESSION['FiltreToolsOutilsEtalonnage_FamilleMateriel']=$FamilleMaterielSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbTypeMateriel > 0)
				{
					while($row=mysqli_fetch_array($ResultTypeMateriel))
					{
						$selected="";
						if($FamilleMaterielSelect<>"")
							{if($FamilleMaterielSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Modèle de matériel :";}else{echo "Model of material :";} ?>
				<select style="width:100px;" name="modeleMateriel" onchange="submit();">
				<?php

				$Requete="
					SELECT
							Id,
							Libelle
						FROM
							tools_modelemateriel
						WHERE
							Suppr=0
						AND Id_FamilleMateriel=".$FamilleMaterielSelect."
						ORDER BY
							Libelle ASC";
				$ResultTypeMateriel=mysqli_query($bdd,$Requete);
				$nbTypeMateriel=mysqli_num_rows($ResultTypeMateriel);
				
				$Selected = "";
				$ModeleMaterielSelect=$_SESSION['FiltreToolsOutilsEtalonnage_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}
				$_SESSION['FiltreToolsOutilsEtalonnage_ModeleMateriel']=$ModeleMaterielSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbTypeMateriel > 0)
				{
					while($row=mysqli_fetch_array($ResultTypeMateriel))
					{
						$selected="";
						if($ModeleMaterielSelect<>"")
							{if($ModeleMaterielSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<?php
		if($_SESSION['FiltreToolsOutilsEtalonnage_Prestation']<>"0"){
			
			
			if(isset($_GET['Page'])){$page=$_GET['Page'];}
			else{$page=0;}
			$_SESSION['Page_ToolsChangementMateriel']=$page;
			$Result=mysqli_query($bdd,$Requete.$requeteOrder);
			$NbEnreg=mysqli_num_rows($Result);
	?>
		<tr>
			<td width="100%">
				<table width="100%">
					<tr >
						<td width="10"></td>
						<td width="100%">
							<table class="TableCompetences" width="100%">
								<tr>
									<td class="EnTeteTableauCompetences"></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_SN']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=Num"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_Num']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_Num']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=LIBELLE_LIEU"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_LIEU']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_LIEU']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%" style='border-right:1px dotted black'><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'envoi étalonnage/<br>Date récupération";}else{echo "Calibration sending date/<br>Recovery date";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=DernierePresta"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DernierePresta']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DernierePresta']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=DernierLieu"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Location";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DernierLieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DernierLieu']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=DerniereCaisse"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Case";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DerniereCaisse']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DerniereCaisse']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="HistoriquePrestation.php?Tri=DernierePersonne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DernierePersonne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DernierePersonne']=="ASC"){echo "&darr;";}?></a></td>
								</tr>
								
							<?php
								if($NbEnreg>0)
								{
								$Couleur="#EEEEEE";
								while($Row=mysqli_fetch_array($Result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
							?>
								<tr id="<?php echo $Row['Id']."_".$Row['TypeSelect']; ?>" bgcolor="<?php echo $Couleur;?>">
									<td><?php echo $Row['NumAAA'];?></td>
									<td><?php echo $Row['SN'];?></td>
									<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
									<td><?php echo stripslashes($Row['Num']);?></td>
									<td><?php echo stripslashes($Row['LIBELLE_LIEU']);?></td>
									<td style='border-right:1px dotted black'><?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td>
									<td><?php echo stripslashes($Row['DernierePresta'].$LIBELLE_POLE);?></td>
									<td><?php echo stripslashes($Row['DernierLieu']);?></td>
									<td><?php echo stripslashes($Row['DerniereCaisse']);?></td>
									<td><?php echo stripslashes($Row['DernierePersonne']);?></td>
								</tr>
							<?php
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
	<?php 
		}
	?>
	
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>