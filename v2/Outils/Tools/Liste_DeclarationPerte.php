<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
	function OuvreFenetreExcel(Id)
		{window.open("DeclarationPerte.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreHistorique(Type,Id){
		var w=window.open("HistoriqueMateriel.php?Page=Materiel&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("FAMILLEMATERIEL","LIBELLE_MODELEMATERIEL","NumAAA","Prestation",'PV_Date','PV_RefDeclaration','Declarant','PV_Lieu','PV_TypeMSN','PV_MSN','PV_Condition');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsDeclarationsPerte_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsDeclarationsPerte_General']);
			$_SESSION['TriToolsDeclarationsPerte_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsDeclarationsPerte_General']);
			$_SESSION['TriToolsDeclarationsPerte_General']= str_replace($tri." ASC","",$_SESSION['TriToolsDeclarationsPerte_General']);
			$_SESSION['TriToolsDeclarationsPerte_General']= str_replace($tri." DESC","",$_SESSION['TriToolsDeclarationsPerte_General']);
			if($_SESSION['TriToolsDeclarationsPerte_'.$tri]==""){$_SESSION['TriToolsDeclarationsPerte_'.$tri]="ASC";$_SESSION['TriToolsDeclarationsPerte_General'].= $tri." ".$_SESSION['TriToolsDeclarationsPerte_'.$tri].",";}
			elseif($_SESSION['TriToolsDeclarationsPerte_'.$tri]=="ASC"){$_SESSION['TriToolsDeclarationsPerte_'.$tri]="DESC";$_SESSION['TriToolsDeclarationsPerte_General'].= $tri." ".$_SESSION['TriToolsDeclarationsPerte_'.$tri].",";}
			else{$_SESSION['TriToolsDeclarationsPerte_'.$tri]="";}
		}
	}
}

?>
<form id="formulaire" class="test" action="Liste_DeclarationPerte.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#8af0b6;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des déclarations de perte";}else{echo "List of loss declarations";}
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
				$numAAA=$_SESSION['FiltreToolsDeclarationsPerte_NumAAA'];
				if($_POST){$numAAA=$_POST['numAAA'];}
				$_SESSION['FiltreToolsDeclarationsPerte_NumAAA']=$numAAA;
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
				
				$PrestationSelect=$_SESSION['FiltreToolsDeclarationsPerte_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsDeclarationsPerte_Prestation']=$PrestationSelect;	
				
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
				
				$PoleSelect=$_SESSION['FiltreToolsDeclarationsPerte_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsDeclarationsPerte_Pole']=$PoleSelect;
				
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Déclarant :";}else{echo "Declaring :";} ?>
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
						
						$personne=$_SESSION['FiltreToolsDeclarationsPerte_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreToolsDeclarationsPerte_Personne']= $personne;
						
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
				$TypeMaterielSelect=$_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel'];
				if($_POST){$TypeMaterielSelect=$_POST['typeMateriel'];}
				$_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel']=$TypeMaterielSelect;	
				
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
				$FamilleMaterielSelect=$_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				$_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel']=$FamilleMaterielSelect;	
				
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
				$ModeleMaterielSelect=$_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}
				$_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel']=$ModeleMaterielSelect;	
				
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
					tools_materiel.Id,
					NumAAA,
					'Outils' AS TypeSelect,
					tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
					tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
					PV_Date,
					PV_RefDeclaration,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Declarant,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
					PV_Lieu,
					PV_TypeMSN,
					PV_MSN,
					PV_Condition,
					tools_mouvement.Id AS Id_Mouvement
				FROM tools_mouvement
				LEFT JOIN tools_materiel ON tools_materiel.Id=tools_mouvement.Id_Materiel__Id_Caisse
				LEFT JOIN tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
				LEFT JOIN tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
				WHERE tools_mouvement.TypeMouvement=2
				AND tools_mouvement.PV_Type<>1
				AND tools_mouvement.Suppr=0
				AND tools_mouvement.Type=0
				AND tools_materiel.Suppr=0 
		";
		if($_SESSION['FiltreToolsDeclarationsPerte_NumAAA']<>""){
			$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsDeclarationsPerte_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsDeclarationsPerte_Prestation']<>"0"){
			$Requete.=" AND PV_Id_Prestation = ".$_SESSION['FiltreToolsDeclarationsPerte_Prestation']." ";
			if($_SESSION['FiltreToolsDeclarationsPerte_Pole']<>"0"){
				$Requete.=" AND PV_Id_Pole = ".$_SESSION['FiltreToolsDeclarationsPerte_Pole']." ";
			}
		}
		else
		{
			
			$Requete.=" AND PV_Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
		}
		if($_SESSION['FiltreToolsDeclarationsPerte_Personne']<>"0"){
			$Requete.=" AND PV_Id_Declarant = ".$_SESSION['FiltreToolsDeclarationsPerte_Personne']." ";
		}
		
		if($_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel']<>"0"){
			if($_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel']==-1){
				$Requete.=" AND tools_materiel.Id=0 ";
			}
			else{
				$Requete.=" AND Id_TypeMateriel = ".$_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel']." ";
			}
		}
		
		if($_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel']<>"0"){
			$Requete.=" AND Id_FamilleMateriel = ".$_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel']." ";
		}
		if($_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel']<>"0"){
			$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel']." ";
		}
		
		//PARTIE CAISSE DE LA REQUETE
		$Requete.=" UNION ALL
				SELECT 
					tools_caisse.Id,
					NumAAA,
					'Caisse' AS TypeSelect,
					(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
					(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
					PV_Date,
					PV_RefDeclaration,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Declarant,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
					PV_Lieu,
					PV_TypeMSN,
					PV_MSN,
					PV_Condition,
					tools_mouvement.Id AS Id_Mouvement
				FROM tools_mouvement
				LEFT JOIN tools_caisse ON tools_caisse.Id=tools_mouvement.Id_Materiel__Id_Caisse
				WHERE tools_mouvement.TypeMouvement=2
				AND tools_mouvement.PV_Type<>1
				AND tools_mouvement.Suppr=0
				AND tools_mouvement.Type=1
				AND tools_caisse.Suppr=0 
					";
		if($_SESSION['FiltreToolsDeclarationsPerte_NumAAA']<>""){
			$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsDeclarationsPerte_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsDeclarationsPerte_Prestation']<>"0"){
			$Requete.=" AND PV_Id_Prestation = ".$_SESSION['FiltreToolsDeclarationsPerte_Prestation']." ";
			if($_SESSION['FiltreToolsDeclarationsPerte_Pole']<>"0"){
				$Requete.=" AND PV_Id_Pole = ".$_SESSION['FiltreToolsDeclarationsPerte_Pole']." ";
			}
		}
		else
		{
			
			$Requete.=" AND PV_Id_Prestation IN (".implode(',',$PrestationAAfficher).") ";
		}
		if($_SESSION['FiltreToolsDeclarationsPerte_Personne']<>"0"){
			$Requete.=" AND PV_Id_Declarant = ".$_SESSION['FiltreToolsDeclarationsPerte_Personne']." ";
		}
		
		if($_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel']>0){
			$Requete.=" AND tools_caisse.Id=0 ";
		}
		
		if($_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel']<>"0"){
			$Requete.=" AND Id_FamilleMateriel = ".$_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel']." ";
		}
		if($_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel']<>"0"){
			$Requete.=" AND Id_CaisseType = ".$_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel']." ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriToolsDeclarationsPerte_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsDeclarationsPerte_General'],0,-1);
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
								<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsDeclarationsPerte_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=FAMILLEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?><?php if($_SESSION['TriToolsDeclarationsPerte_FAMILLEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_FAMILLEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsDeclarationsPerte_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=PV_Date"><?php if($LangueAffichage=="FR"){echo "Date déclaration";}else{echo "Declaration date";}?><?php if($_SESSION['TriToolsDeclarationsPerte_PV_Date']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_PV_Date']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=PV_RefDeclaration"><?php if($LangueAffichage=="FR"){echo "Ref déclaration";}else{echo "Ref declaration";}?><?php if($_SESSION['TriToolsDeclarationsPerte_PV_RefDeclaration']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_PV_RefDeclaration']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=Declarant"><?php if($LangueAffichage=="FR"){echo "Déclarant";}else{echo "Declaring";}?><?php if($_SESSION['TriToolsDeclarationsPerte_Declarant']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_Declarant']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=Prestation"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?><?php if($_SESSION['TriToolsDeclarationsPerte_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_Prestation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=PV_Lieu"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Location";}?><?php if($_SESSION['TriToolsDeclarationsPerte_PV_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_PV_Lieu']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=PV_TypeMSN"><?php if($LangueAffichage=="FR"){echo "Type d'aéronef";}else{echo "Aircraft type";}?><?php if($_SESSION['TriToolsDeclarationsPerte_PV_TypeMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_PV_TypeMSN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=PV_MSN"><?php if($LangueAffichage=="FR"){echo "MSN";}else{echo "MSN";}?><?php if($_SESSION['TriToolsDeclarationsPerte_PV_MSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_PV_MSN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="30%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DeclarationPerte.php?Tri=PV_Condition"><?php if($LangueAffichage=="FR"){echo "Condition";}else{echo "Condition";}?><?php if($_SESSION['TriToolsDeclarationsPerte_PV_Condition']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsDeclarationsPerte_PV_Condition']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="2%"></td>
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
								if($Row['Pole']<>""){$LIBELLE_POLE=" - ".$Row['Pole'];}
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
								<td><?php echo $Row['FAMILLEMATERIEL'];?></td>
								<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
								<td><?php echo stripslashes(AfficheDateJJ_MM_AAAA($Row['PV_Date']));?></td>
								<td><?php echo stripslashes($Row['PV_RefDeclaration']);?></td>
								<td><?php echo stripslashes($Row['Declarant']);?></td>
								<td><?php echo stripslashes($Row['Prestation'].$LIBELLE_POLE);?></td>
								<td><?php echo stripslashes($Row['PV_Lieu']);?></td>
								<td><?php echo stripslashes($Row['PV_TypeMSN']);?></td>
								<td><?php echo stripslashes($Row['PV_MSN']);?></td>
								<td><?php echo stripslashes($Row['PV_Condition']);?></td>
								<td><a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('<?php echo $Row['Id_Mouvement']; ?>');" ><img src="../../Images/excel.gif" border="0"></a></td>
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