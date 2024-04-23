<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
	function OuvreFenetreTransfert(Id){
		var w=window.open("Ajout_TransfertMateriel.php?Page=OutilsEtalonnage&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreTransfertCaisse(Id){
		var w=window.open("Ajout_TransfertCaisse.php?Page=OutilsEtalonnage&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreHistorique(Type,Id){
		var w=window.open("HistoriqueMateriel.php?Page=OutilsEtalonnage&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function CocherTransfert()
	{
		var elements = document.getElementsByClassName("checkTransfert");
		if (formulaire.check_Transfert.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function OuvreFenetreTransfertOutils(){
		var elements = document.getElementsByClassName("checkOutils");
		Id_Outils="";
		for(var i=0, l=elements.length; i<l; i++){
			if(elements[i].checked ==true){
				Id_Outils=Id_Outils+elements[i].value.substring(0,elements[i].value.indexOf('_Outils'))+",";
			}
		}
		if(Id_Outils!=""){
			Id_Outils=Id_Outils.substring(0,Id_Outils.length-1);
			var w=window.open("Ajout_TransfertMateriels.php?Page=OutilsEtalonnage&Ids="+Id_Outils,"PageToolsTransfert","status=no,menubar=no,width=850,height=500");
			w.focus();
		}
	}
	function OuvreFenetreExcel()
		{window.open("Export_Etalonnage.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkOutils");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
				arrayCellules = document.getElementById(elements[i].value).cells;
				longueur = arrayCellules.length;
				j=0;
				while(j<longueur)
				{
					arrayCellules[j].style.backgroundColor = "#f1f82e";
					j++;
				}
			}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = false;
				arrayCellules = document.getElementById(elements[i].value).cells;
				longueur = arrayCellules.length;
				j=0;
				while(j<longueur)
				{
					arrayCellules[j].style.backgroundColor = document.getElementById(elements[i].value).style.backgroundColor;
					j++;
				}
			}
		}
	}
	function OuvreFenetreAlerte()
		{window.open("AlerteEtalonnageAFaire.php","PageExcel","status=no,menubar=no,width=50,height=50");}
</script>
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
<form id="formulaire" class="test" action="Liste_OutilsEtalonnage.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f6c784;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des outils à l'étalonnage / pour l'étalonnage";}else{echo "List of tools for calibration / for calibration";}
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
			<td colspan="2" align="right">
				<?php if(DroitsFormationPlateforme(array($IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteGestionnaireMGX))){ ?>
				<a class="Bouton" href="javascript:OuvreFenetreExcel()">
				<?php if($_SESSION["Langue"]=="FR"){echo "Liste des étalonnages à faire";}else{echo "List of calibrations to be done";} ?>
				</a><br><br><br>
				&bull; <a href="javascript:OuvreFenetreAlerte();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "Emails - étalonnage à faire";}else{echo "Emails - calibration to do";} ?></a>&nbsp;&nbsp;&nbsp;
				<?php } ?>
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
					tools_materiel.Id,
					'Outils' AS TypeSelect,
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
					(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS LIBELLE_LIEU,
					EtatValidation AS TransfertEC,
					tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
					DateReception AS DateDerniereAffectation,
					IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=TAB3.Id_Prestation)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=TAB3.Id_Prestation)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)
					) AS DernierePresta,
					IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=TAB3.Id_Pole)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=TAB3.Id_Pole)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)) AS DernierPole,
					IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB3.Id_Lieu)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB3.Id_Lieu)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)) AS DernierLieu,
					IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
							FROM tools_caisse
							LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
							WHERE tools_caisse.Id=TAB_Mouvement.Id_Caisse)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
							FROM tools_caisse
							LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
							WHERE tools_caisse.Id=TAB_Mouvement.Id_Caisse)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)) AS DerniereCaisse,
					IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=TAB3.Id_Personne)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=TAB3.Id_Personne)
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)) AS DernierePersonne,
					(SELECT tools_mouvement.DateReception
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0
					AND tools_mouvement.Suppr=0
					AND tools_mouvement.Type=0
					AND tools_mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC
					LIMIT 1,1) AS DerniereDateAffectation
				FROM 
					(SELECT *
					FROM 
					(SELECT Id_Materiel__Id_Caisse, Id_Personne, Id_Prestation, Id_Pole,DateReception, Id_Lieu, Id_Caisse, EtatValidation,(@row_number:=@row_number + 1) AS rnk
					FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0
					AND tools_mouvement.Suppr=0
					AND tools_mouvement.Type=0
					AND tools_mouvement.EtatValidation<>-1
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC) AS TAB
					GROUP BY Id_Materiel__Id_Caisse) AS TAB2
				LEFT JOIN tools_materiel ON tools_materiel.Id=TAB2.Id_Materiel__Id_Caisse
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
				LEFT JOIN
					tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
				WHERE (SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) IN ('Etalonnage','Pour étalonnage')
				AND tools_materiel.Suppr=0 
				
		";
		if($_SESSION['FiltreToolsOutilsEtalonnage_NumAAA']<>""){
			$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsOutilsEtalonnage_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsOutilsEtalonnage_Prestation']<>"0"){
			$Requete.=" AND IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Prestation
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Prestation
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Prestation
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Prestation
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)
					) = ".$_SESSION['FiltreToolsOutilsEtalonnage_Prestation']." ";
			if($_SESSION['FiltreToolsOutilsEtalonnage_Pole']<>"0"){
				$Requete.=" AND IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Pole
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Pole
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Pole
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Pole
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)) = ".$_SESSION['FiltreToolsOutilsEtalonnage_Pole']." ";
			}
		}
		else
		{
			
			$Requete.=" AND IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
							FROM tools_mouvement AS TAB_Mouvement
							WHERE TAB_Mouvement.TypeMouvement=0 
							AND TAB_Mouvement.EtatValidation<>-1 
							AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
							AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
						) IN ('Etalonnage','Pour étalonnage'),
						(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Prestation
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Prestation
						)
							FROM tools_mouvement AS TAB_Mouvement
							WHERE TAB_Mouvement.TypeMouvement=0 
							AND TAB_Mouvement.EtatValidation<>-1 
							AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
							AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
						),
						(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Prestation
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Prestation
						)
							FROM tools_mouvement AS TAB_Mouvement
							WHERE TAB_Mouvement.TypeMouvement=0 
							AND TAB_Mouvement.EtatValidation<>-1 
							AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
							AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
						)
						) IN (".implode(',',$PrestationAAfficher).") ";
		}
		if($_SESSION['FiltreToolsOutilsEtalonnage_Personne']<>"0"){
			$Requete.=" AND IF((SELECT (SELECT Libelle FROM tools_lieu WHERE Id=TAB_Mouvement.Id_Lieu)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					) IN ('Etalonnage','Pour étalonnage'),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Personne
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Personne
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 2,1
					),
					(SELECT IF(TAB_Mouvement.Id_Caisse>0,
							(
								SELECT TAB3.Id_Personne
								FROM tools_mouvement AS TAB3
								WHERE TAB3.TypeMouvement=0 AND TAB3.Suppr=0 AND TAB3.Type=1 AND TAB3.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
								AND TAB3.DateReception<=TAB_Mouvement.DateReception
								ORDER BY TAB3.DateReception DESC, TAB3.Id DESC LIMIT 1
							)
						,
						Id_Personne
						)
						FROM tools_mouvement AS TAB_Mouvement
						WHERE TAB_Mouvement.TypeMouvement=0 
						AND TAB_Mouvement.EtatValidation<>-1 
						AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 
						AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB2.Id_Materiel__Id_Caisse
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1,1
					)) = ".$_SESSION['FiltreToolsOutilsEtalonnage_Personne']." ";
		}
		
		if($_SESSION['FiltreToolsOutilsEtalonnage_TypeMateriel']<>"0"){
			if($_SESSION['FiltreToolsOutilsEtalonnage_TypeMateriel']==-1){
				$Requete.=" AND tools_materiel.Id=0 ";
			}
			else{
				$Requete.=" AND Id_TypeMateriel = ".$_SESSION['FiltreToolsOutilsEtalonnage_TypeMateriel']." ";
			}
		}
		
		if($_SESSION['FiltreToolsOutilsEtalonnage_FamilleMateriel']<>"0"){
			$Requete.=" AND Id_FamilleMateriel = ".$_SESSION['FiltreToolsOutilsEtalonnage_FamilleMateriel']." ";
		}
		if($_SESSION['FiltreToolsOutilsEtalonnage_ModeleMateriel']<>"0"){
			$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsOutilsEtalonnage_ModeleMateriel']." ";
		}

		$requeteOrder="";
		if($_SESSION['TriToolsOutilsEtalonnage_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsOutilsEtalonnage_General'],0,-1);
		}
		
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
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_SN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=Num"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_Num']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_Num']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=LIBELLE_LIEU"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_LIEU']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_LIBELLE_LIEU']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%" style='border-right:1px dotted black'><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'envoi étalonnage/<br>Date récupération";}else{echo "Calibration sending date/<br>Recovery date";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=DernierePresta"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DernierePresta']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DernierePresta']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=DernierLieu"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Location";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DernierLieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DernierLieu']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=DerniereCaisse"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Case";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DerniereCaisse']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DerniereCaisse']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_OutilsEtalonnage.php?Tri=DernierePersonne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsOutilsEtalonnage_DernierePersonne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsOutilsEtalonnage_DernierePersonne']=="ASC"){echo "&darr;";}?></a></td>
								<?php
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
								?>
								<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;vertical-align:bottom;">
								
								</td>
								<?php
									}
								?>
								<td class="EnTeteTableauCompetences" colspan="5">
								<?php if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){ ?>
								<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertOutils();" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert outils";}else{echo "Tool transfer";} ?>" width="20px" border="0"></a><br>
								<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" />
								<?php } ?>
								</td>
							</tr>
							
						<?php
							if($NbEnreg>0)
							{
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$LIBELLE_POLE="";
								if($Row['DernierPole']<>""){$LIBELLE_POLE=" - ".$Row['DernierPole'];}
						?>
							<tr id="<?php echo $Row['Id']."_".$Row['TypeSelect']; ?>" bgcolor="<?php echo $Couleur;?>">
								<?php
									if($Row['TypeSelect']=="Outils")
									{
								?>
								<td><a style="text-decoration:none;" href="javascript:OuvreFenetreHistorique('0','<?php echo $Row['Id']; ?>');" ><img src="../../Images/Livre.png" width="18px" border="0"></a></td>
								<?php 			
									}
									else
									{
								?>
								<td><a style="text-decoration:none;" href="javascript:OuvreFenetreHistorique('1','<?php echo $Row['Id']; ?>');" ><img src="../../Images/Livre.png" width="18px" border="0"></a></td>
								<?php
									}
								?>
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
							<?php
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
									if($Row['TypeSelect']=="Outils"){
								?>
										<td>
											<input type="image" class="checkTransfert" width="15px" src="../../Images/RH/Transfert.png" style="border:0;" alt="Transfert" title="Transfert" onclick="javascript:OuvreFenetreTransfert('<?php echo $Row['Id']; ?>');">
										</td>
								<?php 			
									}
								}
							?>
								<td width="20"><?php 
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
								if($Row['TransfertEC']==1){
									echo "<input type='checkbox' class='checkOutils' name='Id_Outils[]' Id='checkOutils_".$Row['Id']."_".$Row['TypeSelect']."' value='".$Row['Id']."_".$Row['TypeSelect']."' onchange='couleurLigne(\"".$Row['Id']."_".$Row['TypeSelect']."\");' >";} 
								}
								?></td>
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