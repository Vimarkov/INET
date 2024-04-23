<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreTransfert(Id){
		var w=window.open("Ajout_TransfertMateriel.php?Page=Etalonnage&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreEtalonnage(Id){
		var w=window.open("Ajout_Etalonnage.php?Page=Etalonnage&Id="+Id,"PageToolsEtalonnage","status=no,menubar=no,width=1100,height=650");
		w.focus();
	}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("PN","SN","LIBELLE_MODELEMATERIEL","NumAAA","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TypeMateriel','FamilleMateriel','DateDernierEtalonnage','PeriodiciteVerification','DateProchainEtalonnage');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsEtalonnage_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsEtalonnage_General']);
			$_SESSION['TriToolsEtalonnage_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsEtalonnage_General']);
			$_SESSION['TriToolsEtalonnage_General']= str_replace($tri." ASC","",$_SESSION['TriToolsEtalonnage_General']);
			$_SESSION['TriToolsEtalonnage_General']= str_replace($tri." DESC","",$_SESSION['TriToolsEtalonnage_General']);
			if($_SESSION['TriToolsEtalonnage_'.$tri]==""){$_SESSION['TriToolsEtalonnage_'.$tri]="ASC";$_SESSION['TriToolsEtalonnage_General'].= $tri." ".$_SESSION['TriToolsEtalonnage_'.$tri].",";}
			elseif($_SESSION['TriToolsEtalonnage_'.$tri]=="ASC"){$_SESSION['TriToolsEtalonnage_'.$tri]="DESC";$_SESSION['TriToolsEtalonnage_General'].= $tri." ".$_SESSION['TriToolsEtalonnage_'.$tri].",";}
			else{$_SESSION['TriToolsEtalonnage_'.$tri]="";}
		}
	}
}

?>
<form class="test" action="Liste_Etalonnage.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffbf71;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Etalonnages à faire";}else{echo "Calibrations to be done";}
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
				$numAAA=$_SESSION['FiltreToolsEtalonnage_NumAAA'];
				if($_POST){$numAAA=$_POST['numAAA'];}
				$_SESSION['FiltreToolsEtalonnage_NumAAA']=$numAAA;
			?>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?>
				<input id="numAAA" name="numAAA" type="texte" value="<?php echo $numAAA; ?>" size="15"/>&nbsp;&nbsp;
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php

					if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
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
				
				$PrestationSelect=$_SESSION['FiltreToolsEtalonnage_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsEtalonnage_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
					$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
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
				
				$PoleSelect=$_SESSION['FiltreToolsEtalonnage_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsEtalonnage_Pole']=$PoleSelect;
				
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Lieu :";}else{echo "Place :";} ?>
				<select style="width:100px;" name="lieu" onchange="submit();">
				<?php
				$requete="SELECT tools_lieu.Id, tools_lieu.Libelle
					FROM tools_lieu
					WHERE Suppr=0
					AND Id_Prestation=".$PrestationSelect."
					AND Id_Pole=".$PoleSelect."
					ORDER BY Libelle ASC";

				$resultLieu=mysqli_query($bdd,$requete);
				$nbLieu=mysqli_num_rows($resultLieu);
				
				$LieuSelect=$_SESSION['FiltreToolsEtalonnage_Lieu'];
				if($_POST){$LieuSelect=$_POST['lieu'];}
				if($PrestationSelect==0){$LieuSelect=0;}
				$_SESSION['FiltreToolsEtalonnage_Lieu']=$LieuSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbLieu > 0)
				{
					while($row=mysqli_fetch_array($resultLieu))
					{
						$selected="";
						if($LieuSelect<>""){if($LieuSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?>
				<select style="width:200px;" name="caisse" onchange="submit();">
				<?php

				if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
					$requete="SELECT DISTINCT tools_caisse.Id, tools_caisse.Num, (SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS CaisseType
							FROM tools_materiel
							LEFT JOIN tools_caisse
							ON tools_caisse.Id=(SELECT tools_mouvement.Id_Caisse FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
							WHERE tools_caisse.Id>0
							AND tools_materiel.Suppr=0
							ORDER BY CaisseType ASC , tools_caisse.Num ASC";
				}
				elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
					$requete="SELECT tools_caisse.Id, tools_caisse.Num, (SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS CaisseType
							FROM tools_materiel
							LEFT JOIN tools_caisse
							ON tools_caisse.Id=(SELECT tools_mouvement.Id_Caisse FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
							WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=(SELECT tools_mouvement.Id_Prestation FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)) IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
							)
							AND (SELECT tools_mouvement.Id_Caisse FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)>0
							AND tools_materiel.Suppr=0
							ORDER BY CaisseType ASC , tools_caisse.Num ASC";
				}
				else{
					$requete="SELECT tools_caisse.Id, tools_caisse.Num, (SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS CaisseType
							FROM tools_materiel
							LEFT JOIN tools_caisse
							ON tools_caisse.Id=(SELECT tools_mouvement.Id_Caisse FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
						WHERE (SELECT CONCAT(tools_mouvement.Id_Prestation,' ',tools_mouvement.Id_Pole) FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN 
							(SELECT Id_Pole 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)
						AND tools_materiel.Suppr=0
						AND (SELECT tools_mouvement.Id_Caisse FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)>0
							ORDER BY CaisseType ASC , tools_caisse.Num ASC";
				}
				
				$resultCaisse=mysqli_query($bdd,$requete);
				$nbCaisse=mysqli_num_rows($resultCaisse);
				
				$CaisseSelect=$_SESSION['FiltreToolsEtalonnage_Caisse'];
				if($_POST){$CaisseSelect=$_POST['caisse'];}
				$_SESSION['FiltreToolsEtalonnage_Caisse']=$CaisseSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbCaisse > 0)
				{
					while($row=mysqli_fetch_array($resultCaisse))
					{
						$selected="";
						if($CaisseSelect<>""){if($CaisseSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['CaisseType'])." n° ".$row['Num']."</option>\n";
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
						if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM tools_materiel
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=(SELECT tools_mouvement.Id_Personne FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
									WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=(SELECT tools_mouvement.Id_Prestation FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)) IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
									)
									AND (SELECT tools_mouvement.Id_Personne FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)>0
									ORDER BY Personne ASC";
						}
						elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM tools_materiel
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=(SELECT tools_mouvement.Id_Personne FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
									WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=(SELECT tools_mouvement.Id_Prestation FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)) IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
									)
									AND (SELECT tools_mouvement.Id_Personne FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)>0
									ORDER BY Personne ASC";
						}
						else{
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM tools_materiel
								LEFT JOIN new_rh_etatcivil
								ON new_rh_etatcivil.Id=(SELECT tools_mouvement.Id_Personne FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
								WHERE (SELECT CONCAT(tools_mouvement.Id_Prestation,' ',tools_mouvement.Id_Pole) FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN 
									(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
									)
								AND (SELECT tools_mouvement.Id_Personne FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)>0
								ORDER BY Personne ASC";
						}

						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreToolsEtalonnage_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreToolsEtalonnage_Personne']= $personne;
						
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Famille :";}else{echo "Family :";} ?>
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
						AND Id_TypeMateriel=1
						ORDER BY
							Libelle ASC";
				$ResultTypeMateriel=mysqli_query($bdd,$Requete);
				$nbTypeMateriel=mysqli_num_rows($ResultTypeMateriel);
				
				$Selected = "";
				$FamilleMaterielSelect=$_SESSION['FiltreToolsEtalonnage_FamilleMateriel'];
				$TypeMaterielSelect=$_SESSION['FiltreToolsEtalonnage_TypeMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				$_SESSION['FiltreToolsEtalonnage_FamilleMateriel']=$FamilleMaterielSelect;	
				
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Modèle :";}else{echo "Model :";} ?>
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
				$ModeleMaterielSelect=$_SESSION['FiltreToolsEtalonnage_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}
				$_SESSION['FiltreToolsEtalonnage_ModeleMateriel']=$ModeleMaterielSelect;	
				
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
		//PARTIE OUTILS DE LA REQUETE
		 $Requete="
				SELECT
					Id,
					'Outils' AS TypeSelect,
					NumAAA,
					SN,
					PN,
					(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) AS FamilleMateriel,
					(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
					(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
					(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS LIBELLE_PRESTATION,
					(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS LIBELLE_POLE,
					(SELECT (SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS LIBELLE_LIEU,
					(SELECT (SELECT CONCAT(Libelle,' n° ',(SELECT Num FROM tools_caisse WHERE Id=Id_Caisse)) FROM tools_caissetype WHERE Id=(SELECT Id_CaisseType FROM tools_caisse WHERE Id=Id_Caisse)) FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS LIBELLE_CAISSETYPE,
					(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS NOMPRENOM_PERSONNE,
					(SELECT FV_DateEtalonnage FROM tools_mouvement WHERE TypeMouvement=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY FV_DateEtalonnage DESC, Id DESC LIMIT 1) AS DateDernierEtalonnage,
					PeriodiciteVerification,
					(SELECT DATE_ADD(FV_DateEtalonnage, INTERVAL tools_materiel.PeriodiciteVerification MONTH) FROM tools_mouvement WHERE TypeMouvement=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY FV_DateEtalonnage DESC, Id DESC LIMIT 1) AS DateProchainEtalonnage
				FROM
					tools_materiel
				WHERE
					Suppr=0 
					AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) =1
					AND 
					((SELECT DATE_ADD(FV_DateEtalonnage, INTERVAL tools_materiel.PeriodiciteVerification MONTH) FROM tools_mouvement WHERE TypeMouvement=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY FV_DateEtalonnage DESC, Id DESC LIMIT 1) <= DATE_ADD(NOW(), INTERVAL 1 MONTH)
						OR (SELECT COUNT(tools_mouvement.Id) FROM tools_mouvement WHERE TypeMouvement=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id)=0
					)
					";
			if($_SESSION['FiltreToolsEtalonnage_NumAAA']<>""){
				$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsEtalonnage_NumAAA']."%' ";
			}
			if($_SESSION['FiltreToolsEtalonnage_Prestation']<>"0"){
				$Requete.=" AND (SELECT Id_Prestation FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsEtalonnage_Prestation']." ";
				if($_SESSION['FiltreToolsEtalonnage_Pole']<>"0"){
					$Requete.=" AND (SELECT Id_Pole FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsEtalonnage_Pole']." ";
				}
				if($_SESSION['FiltreToolsEtalonnage_Lieu']<>"0"){
					$Requete.=" AND (SELECT Id_Lieu FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsEtalonnage_Lieu']." ";
				}
			}
			if($_SESSION['FiltreToolsEtalonnage_Caisse']<>"0"){
				$Requete.=" AND (SELECT Id_Caisse FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsEtalonnage_Caisse']." ";
			}
			if($_SESSION['FiltreToolsEtalonnage_Personne']<>"0"){
				$Requete.=" AND (SELECT Id_Personne FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsEtalonnage_Personne']." ";
			}
			if($_SESSION['FiltreToolsEtalonnage_FamilleMateriel']<>"0"){
				$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsEtalonnage_FamilleMateriel']." ";
			}
			if($_SESSION['FiltreToolsEtalonnage_ModeleMateriel']<>"0"){
				$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsEtalonnage_ModeleMateriel']." ";
			}
			
			$Result=mysqli_query($bdd,$Requete);
			$NbEnreg=mysqli_num_rows($Result);
			$nombreDePages=ceil($NbEnreg/40);
			
			$requeteOrder="";
			if($_SESSION['TriToolsEtalonnage_General']<>""){
				$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsEtalonnage_General'],0,-1);
			}
		
			if(isset($_GET['Page'])){$page=$_GET['Page'];}
			else{$page=0;}
			$requete2=" LIMIT ".($page*40).",40";
			
			
			
			$Result=mysqli_query($bdd,$Requete.$requeteOrder.$requete2);
			$NbEnreg=mysqli_num_rows($Result);
	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_Etalonnage.php?debut=1&Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($page<=5){
					$valeurDepart=1;
				}
				elseif($page>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$page-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($page+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_Etalonnage.php?debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Etalonnage.php?&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
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
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=FamilleMateriel"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?><?php if($_SESSION['TriToolsEtalonnage_FamilleMateriel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_FamilleMateriel']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsEtalonnage_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=PN"><?php if($LangueAffichage=="FR"){echo "P/N";}else{echo "P/N";}?><?php if($_SESSION['TriToolsEtalonnage_PN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_PN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsEtalonnage_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_SN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsEtalonnage_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=LIBELLE_PRESTATION"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?><?php if($_SESSION['TriToolsEtalonnage_LIBELLE_PRESTATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_LIBELLE_PRESTATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=LIBELLE_LIEU"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?><?php if($_SESSION['TriToolsEtalonnage_LIBELLE_LIEU']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_LIBELLE_LIEU']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=LIBELLE_CAISSETYPE"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?><?php if($_SESSION['TriToolsEtalonnage_LIBELLE_CAISSETYPE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_LIBELLE_CAISSETYPE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=NOMPRENOM_PERSONNE"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsEtalonnage_NOMPRENOM_PERSONNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_NOMPRENOM_PERSONNE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?><?php if($_SESSION['TriToolsEtalonnage_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=DateDernierEtalonnage"><?php if($LangueAffichage=="FR"){echo "Date dernier étalonnage";}else{echo "Last date calibration";}?><?php if($_SESSION['TriToolsEtalonnage_DateDernierEtalonnage']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_DateDernierEtalonnage']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=DateProchainEtalonnage"><?php if($LangueAffichage=="FR"){echo "Date prochain étalonnage";}else{echo "Next calibration date";}?><?php if($_SESSION['TriToolsEtalonnage_DateProchainEtalonnage']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_DateProchainEtalonnage']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" colspan="2"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Etalonnage.php?Tri=PeriodiciteVerification"><?php if($LangueAffichage=="FR"){echo "Périodicité (mois)";}else{echo "Periodicity (month)";}?><?php if($_SESSION['TriToolsEtalonnage_PeriodiciteVerification']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsEtalonnage_PeriodiciteVerification']=="ASC"){echo "&darr;";}?></a></td>
							</tr>
							
						<?php
							if($NbEnreg>0)
							{
							$Couleur="#EEEEEE";
							
							$Rouge="#fa3648";
							$Orange="#f1a13f";
							$Jaune="#f9ed37";
							
							$depasse=date('Y-m-d');
							$DeuxSemaines=date('Y-m-d',strtotime(date('Y-m-d')." + 14 day"));
							$UnMois=date('Y-m-d',strtotime(date('Y-m-d')." + 1 month"));
							
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$LIBELLE_POLE="";
								if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
								
								$couleurCellule=$Couleur;
								if($Row['DateProchainEtalonnage']<=$depasse){$couleurCellule=$Rouge;}
								elseif($Row['DateProchainEtalonnage']<=$DeuxSemaines){$couleurCellule=$Orange;}
								elseif($Row['DateProchainEtalonnage']<=$UnMois){$couleurCellule=$Jaune;}
								
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($Row['FamilleMateriel']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
								<td><?php echo $Row['PN'];?></td>
								<td><?php echo $Row['SN'];?></td>
								<td><?php echo $Row['NumAAA'];?></td>
								<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_LIEU']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_CAISSETYPE']);?></td>
								<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
								<td><?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td>
								<td><?php echo AfficheDateJJ_MM_AAAA($Row['DateDernierEtalonnage']);?></td>
								<td bgcolor="<?php echo $couleurCellule;?>"><?php echo AfficheDateJJ_MM_AAAA($Row['DateProchainEtalonnage']);?></td>
								<td><?php echo stripslashes($Row['PeriodiciteVerification']);?></td>
								<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreTransfert('<?php echo $Row['Id']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a></td>
								<td width="20">
									<a style="text-decoration:none;" href="javascript:OuvreFenetreEtalonnage('<?php echo $Row['Id']; ?>');" ><img src="../../Images/Etalonnage2.png" title="<?php if($LangueAffichage=="FR"){echo "Etalonnage";}else{echo "Calibration";} ?>" width="20px" border="0"></a>
								</td>
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
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>