<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
	function OuvreFenetreTransfert(Id){
		var w=window.open("Ajout_TransfertMateriel.php?Page=Inventaire&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreTransfertCaisse(Id){
		var w=window.open("Ajout_TransfertCaisse.php?Page=Inventaire&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreHistorique(Type,Id){
		var w=window.open("HistoriqueMateriel.php?Page=Materiel&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreExcel()
		{window.open("Export_Inventaire.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreInventaire()
		{window.open("Inventaire_Materiel2.php","PageInventaire","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreNbInventaire()
		{window.open("Export_NbInventaire.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function CocherMemeAffectation()
	{
		var elements = document.getElementsByClassName("checkMemeAffectation");
		if (formulaire.check_MemeAffectation.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
				if(document.getElementById('checkOutils_'+elements[i].value)){document.getElementById('checkOutils_'+elements[i].value).checked=false;}
				arrayCellules = document.getElementById(elements[i].value).cells;
				longueur = arrayCellules.length;
				j=0;
				while(j<longueur)
				{
					arrayCellules[j].style.backgroundColor = "#2eadf8";
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
	function OuvreFenetreTransfertOutils(){
		var elements = document.getElementsByClassName("checkOutils");
		Id_Outils="";
		for(var i=0, l=elements.length; i<l; i++){
			if(elements[i].checked ==true){
				Id_Outils=Id_Outils+elements[i].value+",";
			}
		}
		if(Id_Outils!=""){
			Id_Outils=Id_Outils.substring(0,Id_Outils.length-1);
			var w=window.open("Ajout_TransfertMateriels.php?Page=Inventaire&Ids="+Id_Outils,"PageToolsTransfert","status=no,menubar=no,width=850,height=500");
			w.focus();
		}
	}
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkOutils");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
				if(document.getElementById('checkMemeAffectation_'+elements[i].value)){document.getElementById('checkMemeAffectation_'+elements[i].value).checked=false;}
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
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','LIBELLE_NOUVELLEPRESTATION','DateMouvementPrestation','TYPEMATERIEL','FAMILLEMATERIEL','Designation');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsInventaire_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsInventaire_General']);
			$_SESSION['TriToolsInventaire_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsInventaire_General']);
			$_SESSION['TriToolsInventaire_General']= str_replace($tri." ASC","",$_SESSION['TriToolsInventaire_General']);
			$_SESSION['TriToolsInventaire_General']= str_replace($tri." DESC","",$_SESSION['TriToolsInventaire_General']);
			if($_SESSION['TriToolsInventaire_'.$tri]==""){$_SESSION['TriToolsInventaire_'.$tri]="ASC";$_SESSION['TriToolsInventaire_General'].= $tri." ".$_SESSION['TriToolsInventaire_'.$tri].",";}
			elseif($_SESSION['TriToolsInventaire_'.$tri]=="ASC"){$_SESSION['TriToolsInventaire_'.$tri]="DESC";$_SESSION['TriToolsInventaire_General'].= $tri." ".$_SESSION['TriToolsInventaire_'.$tri].",";}
			else{$_SESSION['TriToolsInventaire_'.$tri]="";}
		}
	}
}
if($_POST){
	if(isset($_POST['nbLigne'])){
		if($_POST['nbLigne']<>"" && $_POST['nbLigne']>0 && $_POST['nbLigne']<500){
			$_SESSION['NbLigne_ToolsChangement']=$_POST['nbLigne'];
		}
	}
}
?>
<form id="formulaire" class="test" action="Liste_Inventaire.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#8863ab;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Inventaires à traiter < ".date('d/m/Y',strtotime(date('Y-m-d')."- 6 month"));}else{echo "Inventories to be processed < ".date('d/m/Y',strtotime(date('Y-m-d')."- 6 month"));}
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
				$numAAA=$_SESSION['FiltreToolsInventaire_NumAAA'];
				if($_POST){$numAAA=$_POST['numAAA'];}
				$_SESSION['FiltreToolsInventaire_NumAAA']=$numAAA;
			?>
			
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php

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
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreToolsInventaire_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsInventaire_Prestation']=$PrestationSelect;	
				
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

				if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
					$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
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
				
				$PoleSelect=$_SESSION['FiltreToolsInventaire_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsInventaire_Pole']=$PoleSelect;
				
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
						
						$personne=$_SESSION['FiltreToolsInventaire_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreToolsInventaire_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?>
				<select style="width:200px;" name="caisse" onchange="submit();">
				<?php
				$requete="SELECT DISTINCT tools_caisse.Id, tools_caisse.Num, (SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS CaisseType
						FROM tools_caisse
						WHERE tools_caisse.Id>0 ";
				
				if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
					$requete.="AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
							) ";
				}
				elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
					$requete.="AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
							) ";
				}
				else{
					$requete.="AND (SELECT Id_Prestation FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN 
							(SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							) ";
					
				}
				$requete.=" ORDER BY tools_caisse.Num ASC";

				$resultCaisse=mysqli_query($bdd,$requete);
				$nbCaisse=mysqli_num_rows($resultCaisse);
				
				$CaisseSelect=$_SESSION['FiltreToolsInventaire_Caisse'];
				if($_POST){$CaisseSelect=$_POST['caisse'];}
				$_SESSION['FiltreToolsInventaire_Caisse']=$CaisseSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				$bTrouve=0;
				if ($nbCaisse > 0)
				{
					while($row=mysqli_fetch_array($resultCaisse))
					{
						$selected="";
						if($CaisseSelect<>""){if($CaisseSelect==$row['Id']){$selected="selected";$bTrouve=1;}}
						echo "<option value='".$row['Id']."' ".$selected.">"."n° ".$row['Num']." ".stripslashes($row['CaisseType'])."</option>\n";
					}
				 }
				 if($bTrouve==0){
					 $_SESSION['FiltreToolsInventaire_Caisse']="0";
				 }
				 ?>
				</select>

			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="5%">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:OuvreFenetreExcel()">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?>
				<input id="numAAA" name="numAAA" type="texte" value="<?php echo $numAAA; ?>" size="15"/>&nbsp;&nbsp;
			</td>
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
				$TypeMaterielSelect=$_SESSION['FiltreToolsInventaire_TypeMateriel'];
				if($_POST){$TypeMaterielSelect=$_POST['typeMateriel'];}
				$_SESSION['FiltreToolsInventaire_TypeMateriel']=$TypeMaterielSelect;	
				
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
				$FamilleMaterielSelect=$_SESSION['FiltreToolsInventaire_FamilleMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				$_SESSION['FiltreToolsInventaire_FamilleMateriel']=$FamilleMaterielSelect;	
				
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
				$ModeleMaterielSelect=$_SESSION['FiltreToolsInventaire_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}
				$_SESSION['FiltreToolsInventaire_ModeleMateriel']=$ModeleMaterielSelect;	
				
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
			<td align="right" colspan="2">
				&bull; <a href="javascript:OuvreFenetreInventaire();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0710 - Fiche d'inventaire";}else{echo "D-0710 - Inventory form";} ?></a>
				<?php if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){?>
				<br>
				&bull; <a href="javascript:OuvreFenetreNbInventaire();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "Nb inventaire restant/prestation";}else{echo "Nb remaining inventory/service";} ?></a>
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
	$Requete2="
		SELECT 
			tools_materiel.Id AS Id,
			'Outils' AS TypeSelect,
			NumAAA,
			NumFicheImmo,
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
			Designation,
			tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
			(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
			tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			CommentaireT AS Remarque,
			DateReceptionT AS DateDerniereAffectation,
			EtatValidationT AS TransfertEC,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT) AS LIBELLE_PLATEFORME,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS LIBELLE_PRESTATION,
			Id_PrestationT AS Id_Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = Id_PoleT ) AS LIBELLE_POLE,
			Id_PoleT AS Id_Pole,
			(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) AS LIBELLE_LIEU,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
			Id_PersonneT AS Id_Personne,
			Id_CaisseT AS Id_Caisse,
			(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',TAB_Caisse.Num) FROM tools_caissetype WHERE TAB_Caisse.Id_CaisseType=tools_caissetype.Id)
			FROM tools_caisse AS TAB_Caisse WHERE TAB_Caisse.Id=Id_CaisseT) AS LIBELLE_CAISSETYPE,
			Id_LieuT AS Id_Lieu
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
			WHERE tools_materiel.Suppr=0
				";
	
	if($_SESSION['FiltreToolsInventaire_TypeMateriel']<>"0"){$Requete.=" AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsInventaire_TypeMateriel']." ";}
	if($_SESSION['FiltreToolsInventaire_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsInventaire_FamilleMateriel']." ";}
	if($_SESSION['FiltreToolsInventaire_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsInventaire_ModeleMateriel']." ";}
	if($_SESSION['FiltreToolsInventaire_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsInventaire_NumAAA']."%' ";}

	$Requete.=" 
	AND DateReceptionT < '".date('Y-m-d',strtotime(date('Y-m-d')."- 6 month"))."'
		AND (
			(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) NOT IN ('Perdu','Perdu officiellement','Recyclage','Volé','Détruit','Réformé','Echange (SAV)','Don')
			OR Id_LieuT=0
			)
		AND ( CONCAT(Id_PrestationT,'_',Id_PoleT) IN 
			
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			)
			
			OR 
			
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT) IN 
		
			(SELECT Id_Plateforme
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.")  
			)
		
		OR Id_PersonneT = ".$IdPersonneConnectee."
		
		)
		
		";
		
		if($_SESSION['FiltreToolsInventaire_Caisse']<>"0"){$Requete.=" AND Id_CaisseT = ".$_SESSION['FiltreToolsInventaire_Caisse']." ";}
		if($_SESSION['FiltreToolsInventaire_Prestation']<>"0")
		{
			$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsInventaire_Prestation']." ";
			if($_SESSION['FiltreToolsInventaire_Pole']<>"0"){$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsInventaire_Pole']." ";}
		}

		if($_SESSION['FiltreToolsInventaire_Personne']<>"0"){$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsInventaire_Personne']." ";
		}
		
		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse="UNION ALL
			SELECT 
				Id AS Id,
				'Caisse' AS TypeSelect,
				NumAAA AS NumAAA,
				NumFicheImmo,
				SN AS SN,
				Num AS Num,
				'' AS Designation,
				-1 AS Id_TYPEMATERIEL,
				'Caisse' AS TYPEMATERIEL,
				(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
				(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
				CommentaireT AS Remarque,
				DateReceptionT AS DateDerniereAffectation,
				EtatValidationT AS TransfertEC,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)
					FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS LIBELLE_PLATEFORME,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS LIBELLE_PRESTATION,
				Id_PrestationT AS Id_Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = Id_PoleT ) AS LIBELLE_POLE,
				Id_PoleT AS Id_Pole,
				(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) AS LIBELLE_LIEU,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
				Id_PersonneT AS Id_Personne,
				tools_caisse.Id AS Id_Caisse,
				(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',TAB_Caisse.Num) FROM tools_caissetype WHERE TAB_Caisse.Id_CaisseType=tools_caissetype.Id)
				FROM tools_caisse AS TAB_Caisse WHERE TAB_Caisse.Id=tools_caisse.Id) AS LIBELLE_CAISSETYPE,
				Id_LieuT AS Id_Lieu
			";
		$RequeteCaisse="FROM
			tools_caisse
		WHERE 
			tools_caisse.Suppr=0 ";
		if($_SESSION['FiltreToolsInventaire_TypeMateriel']>0){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsInventaire_FamilleMateriel']<>"0"){$RequeteCaisse.=" AND Id_FamilleMateriel=".$_SESSION['FiltreToolsInventaire_FamilleMateriel']." ";}
		if($_SESSION['FiltreToolsInventaire_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsInventaire_NumAAA']<>""){$RequeteCaisse.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsInventaire_NumAAA']."%' ";}
		//if($_SESSION['FiltreToolsInventaire_Designation']<>""){$RequeteCaisse.=" AND Id=0 ";}
		
		$RequeteCaisse.="
		AND DateReceptionT < '".date('Y-m-d',strtotime(date('Y-m-d')."- 6 month"))."'
				AND ((SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) NOT IN ('Perdu','Perdu officiellement','Recyclage','Volé','Détruit','Réformé','Echange (SAV)','Don')
				OR Id_LieuT=0
				)
				
				 AND (CONCAT(Id_PrestationT,'_',Id_PoleT) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				)
				
				OR 
				
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT)
				IN 
			
				(SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.")  
				)
				
				OR Id_PersonneT = ".$IdPersonneConnectee."
			
			)  ";
			
		if($_SESSION['FiltreToolsInventaire_Caisse']<>"0"){$RequeteCaisse.=" AND tools_caisse.Id = ".$_SESSION['FiltreToolsInventaire_Caisse']." ";}
		if($_SESSION['FiltreToolsInventaire_Prestation']<>"0")
		{
			$RequeteCaisse.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsInventaire_Prestation']." ";
			if($_SESSION['FiltreToolsInventaire_Pole']<>"0"){$RequeteCaisse.=" AND Id_PoleT = ".$_SESSION['FiltreToolsInventaire_Pole']." ";}
		}

		if($_SESSION['FiltreToolsInventaire_Personne']<>"0"){$RequeteCaisse.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsInventaire_Personne']." ";}

		$requeteOrder="";
		if($_SESSION['TriToolsInventaire_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsInventaire_General'],0,-1);}
		
		$reqAnalyse="
		SELECT 
			tools_materiel.Id
			";
		$reqAnalyseCaisse="UNION ALL
			SELECT 
				Id
			";
		
		$result=mysqli_query($bdd,$reqAnalyse.$Requete.$reqAnalyseCaisse.$RequeteCaisse);
		$nbResulta=mysqli_num_rows($result);
		$nombreDePages=ceil($nbResulta/$_SESSION['NbLigne_ToolsChangement']);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$_SESSION['Page_ToolsInventaireMateriel']=$page;
		
		$requeteLimite=" LIMIT ".($page*$_SESSION['NbLigne_ToolsChangement']).",".$_SESSION['NbLigne_ToolsChangement']."";
		
		$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder.$requeteLimite);
		$NbEnreg=mysqli_num_rows($Result);
	?>
	<tr>
		<td>
			<table width="100%">
				<tr>
				<td width="90%" align="center" style="color:red;">
					<?php
						if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)) || DroitsFormationPrestation(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation)) || DroitsPlateforme(array($IdPosteResponsablePlateforme))){}
						else{
							if($_SESSION["Langue"]=="FR"){echo "VALIDEZ LE MATERIEL QUE VOUS AVEZ EN COMPTE.<br>CONTACTEZ VOTRE RESPONSABLE SI VOUS N'AVEZ PLUS/OU PAS EN COMPTE DU MATERIEL DE LA LISTE.";}
							else{echo "VALIDATE THE MATERIAL THAT YOU HAVE IN ACCOUNT. <br> CONTACT YOUR MANAGER IF YOU NO LONGER / OR NOT ACCOUNT FOR THE EQUIPMENT IN THE LIST.";}
						}
					?>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%">
				<tr>
				<td width="90%" align="center" style="font-size:18px;">
					<?php
						$nbPage=0;
						if($_SESSION['Page_ToolsInventaireMateriel']>1){
							echo "<b> <a style='color:#00599f;' href='Liste_Inventaire.php?Page=0'><<</a> </b>";
						}
						$valeurDepart=1;
						if($_SESSION['Page_ToolsInventaireMateriel']<=5){
							$valeurDepart=1;
						}
						elseif($_SESSION['Page_ToolsInventaireMateriel']>=($nombreDePages-6)){
							$valeurDepart=$nombreDePages-6;
						}
						else{
							$valeurDepart=$_SESSION['Page_ToolsInventaireMateriel']-5;
						}
						for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
							if($i<=$nombreDePages){
								if($i==($_SESSION['Page_ToolsInventaireMateriel']+1)){
									echo "<b> [ ".$i." ] </b>"; 
								}	
								else{
									echo "<b> <a style='color:#00599f;' href='Liste_Inventaire.php?Page=".($i-1)."'>".$i."</a> </b>";
								}
							}
						}
						if($_SESSION['Page_ToolsInventaireMateriel']<($nombreDePages-1)){
							echo "<b> <a style='color:#00599f;' href='Liste_Inventaire.php?Page=".($nombreDePages-1)."'>>></a> </b>";
						}
					?>
				</td>
				</tr>
			</table>
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
								<td class="EnTeteTableauCompetences"></td>
								<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsInventaire_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsInventaire_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_SN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=TYPEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?><?php if($_SESSION['TriToolsInventaire_TYPEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_TYPEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=FAMILLEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?><?php if($_SESSION['TriToolsInventaire_FAMILLEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_FAMILLEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsInventaire_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="13%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=Designation"><?php if($LangueAffichage=="FR"){echo "Désignation";}else{echo "Designation";}?><?php if($_SESSION['TriToolsInventaire_Designation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_Designation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=Num"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriToolsInventaire_Num']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_Num']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=LIBELLE_PRESTATION"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?><?php if($_SESSION['TriToolsInventaire_LIBELLE_PRESTATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_LIBELLE_PRESTATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=LIBELLE_LIEU"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?><?php if($_SESSION['TriToolsInventaire_LIBELLE_LIEU']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_LIBELLE_LIEU']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=LIBELLE_CAISSETYPE"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?><?php if($_SESSION['TriToolsInventaire_LIBELLE_CAISSETYPE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_LIBELLE_CAISSETYPE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=NOMPRENOM_PERSONNE"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsInventaire_NOMPRENOM_PERSONNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_NOMPRENOM_PERSONNE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="8%" style='border-right:1px dotted black'><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Inventaire.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?><?php if($_SESSION['TriToolsInventaire_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsInventaire_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
								
								<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;vertical-align:bottom;">
									<input type="submit" class="Bouton" style="cursor:pointer;" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="transfertMemeAffectation" value="<?php if($_SESSION["Langue"]=="FR"){echo "Même affectation";}else{echo "Same assignment";} ?>"><br>
									<input type='checkbox' id="check_MemeAffectation" name="check_MemeAffectation" value="" onchange="CocherMemeAffectation()">
								</td>
								<?php
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)) || DroitsFormationPrestation(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation)) || DroitsPlateforme(array($IdPosteResponsablePlateforme))){
								?>
								<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;vertical-align:bottom;">
								
								</td>
								
								<?php
								}
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)) || DroitsFormationPrestation(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation)) || DroitsPlateforme(array($IdPosteResponsablePlateforme))){
								?>
								<td class="EnTeteTableauCompetences">
									<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertOutils();" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert outils";}else{echo "Tool transfer";} ?>" width="20px" border="0"></a><br>
									<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" />
								</td>
								<?php
									}
								?>
							</tr>
							
						<?php
							if(isset($_POST['transfertMemeAffectation'])){
								
								$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder);
								$NbEnreg=mysqli_num_rows($Result);
									
								if($NbEnreg>0)
								{
									while($Row=mysqli_fetch_array($Result))
									{
										if (isset($_POST['checkMemeAffectation_'.$Row['Id'].'_'.$Row['TypeSelect']])){
											if($Row['TypeSelect']=="Outils"){
												
												$IdPrestationPersonneConnectee=0;
												$IdPolePersonneConnectee=0;
												
												$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne(date('Y-m-d'),$IdPersonneConnectee));
												if(sizeof($TableauPrestationPolePersonneConnectee)>0){
													$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
													$IdPolePersonneConnectee=0;
													if($IdPrestationPersonneConnectee>0){
														$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
													}
												}
												
												$RequeteMouvement="
													INSERT INTO
														tools_mouvement
													(
														Type,TypeMouvement,Id_Materiel__Id_Caisse,
														Id_Prestation,Id_Pole,Id_Caisse,Id_Lieu,
														Id_Personne,
														Id_Demandeur,
														Id_PrestationDemandeur,
														Id_PoleDemandeur,
														Id_Recepteur,
														Id_PrestationRecepteur,
														Id_PoleRecepteur,
														DateDemande,
														DateReception,
														DatePriseEnCompteDemandeur,
														Id_DemandeurPrisEnCompte,
														Commentaire,
														EtatValidation
													)
													VALUES
													(
														'0','0','".$Row['Id']."',
														'".$Row['Id_Prestation']."','".$Row['Id_Pole']."','".$Row['Id_Caisse']."','".$Row['Id_Lieu']."',
														'".$Row['Id_Personne']."',
														'".$IdPersonneConnectee."','".$IdPrestationPersonneConnectee."','".$IdPolePersonneConnectee."',
														'".$IdPersonneConnectee."','".$IdPrestationPersonneConnectee."','".$IdPolePersonneConnectee."',
														'".date('Y-m-d')."',
														'".date('Y-m-d')."',
														'".date('Y-m-d')."','".$IdPersonneConnectee."',
														'',
														1
													);";
												$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
												
												//Mettre à jour l'affectation dans matériel
												$req="UPDATE tools_materiel 
													SET Id_PrestationT=".$Row['Id_Prestation'].", Id_PoleT=".$Row['Id_Pole'].", Id_LieuT=".$Row['Id_Lieu'].", Id_PersonneT=".$Row['Id_Personne'].", 
														Id_CaisseT=".$Row['Id_Caisse'].", DateReceptionT='".date('Y-m-d')."', EtatValidationT=1, CommentaireT='".$Row['Remarque']."'
													WHERE Id=".$Row['Id']." ";
												$ResultUpdt=mysqli_query($bdd,$req);
											}
											else{
												$IdPrestationPersonneConnectee=0;
												$IdPolePersonneConnectee=0;
												
												$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne(date('Y-m-d'),$IdPersonneConnectee));
												if(sizeof($TableauPrestationPolePersonneConnectee)>0){
													$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
													$IdPolePersonneConnectee=0;
													if($IdPrestationPersonneConnectee>0){
														$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
													}
												}
												
												$RequeteMouvement="
													INSERT INTO
														tools_mouvement
													(
														Type,TypeMouvement,Id_Materiel__Id_Caisse,
														Id_Prestation,Id_Pole,Id_Lieu,
														Id_Personne,
														Id_Demandeur,
														Id_PrestationDemandeur,
														Id_PoleDemandeur,
														Id_Recepteur,
														Id_PrestationRecepteur,
														Id_PoleRecepteur,
														DateDemande,
														DateReception,
														DatePriseEnCompteDemandeur,
														Id_DemandeurPrisEnCompte,
														Commentaire,
														EtatValidation
													)
													VALUES
													(
														'1','0','".$Row['Id']."',
														'".$Row['Id_Prestation']."','".$Row['Id_Pole']."','".$Row['Id_Lieu']."',
														'".$Row['Id_Personne']."',
														'".$IdPersonneConnectee."','".$IdPrestationPersonneConnectee."','".$IdPolePersonneConnectee."',
														'".$IdPersonneConnectee."','".$IdPrestationPersonneConnectee."','".$IdPolePersonneConnectee."',
														'".date('Y-m-d')."',
														'".date('Y-m-d')."',
														'".date('Y-m-d')."','".$IdPersonneConnectee."',
														'',
														1
													);";
												$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
												
												//Mettre à jour l'affectation dans caisse
												$req="UPDATE tools_caisse 
													SET Id_PrestationT=".$Row['Id_Prestation'].", Id_PoleT=".$Row['Id_Pole'].", Id_LieuT=".$Row['Id_Lieu'].", Id_PersonneT=".$Row['Id_Personne'].", 
														DateReceptionT='".date('Y-m-d')."', EtatValidationT=1, CommentaireT='".$Row['Remarque']."'
													WHERE Id=".$Row['Id']." ";
												$ResultUpdt=mysqli_query($bdd,$req);
											}
										}
									}
									$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder.$requeteLimite);
									$NbEnreg=mysqli_num_rows($Result);
								}
							}
							
							if($NbEnreg>0)
							{
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$LIBELLE_POLE="";
								if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
								
								$valeur=$Row['Id']."_".$Row['TypeSelect'];

						?>
							<tr id="<?php echo $valeur;?>" style="background-color:<?php echo $Couleur;?>;">
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
								<td><?php echo stripslashes($Row['TYPEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['FAMILLEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['Designation']);?></td>
								<td><?php echo stripslashes($Row['Num']);?></td>
								<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_LIEU']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_CAISSETYPE']);?></td>
								<td><?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);?></td>
								<td style='border-right:1px dotted black'><?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td>
							<?php
								echo "<td align='center'><input class='checkMemeAffectation' type='checkbox' id='checkMemeAffectation_".$Row['Id']."_".$Row['TypeSelect']."' name='checkMemeAffectation_".$Row['Id']."_".$Row['TypeSelect']."' value='".$Row['Id']."_".$Row['TypeSelect']."' onchange='couleurLigne2(\"".$valeur."\");' ></td>";
									if($Row['TypeSelect']=="Outils"){
								?>
										<?php if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)) || DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole']) || DroitsPlateforme(array($IdPosteResponsablePlateforme))){ ?>
										<td>
											<input type="image" class="checkTransfert" width="15px" src="../../Images/RH/Transfert.png" style="border:0;" alt="Transfert" title="Transfert" onclick="javascript:OuvreFenetreTransfert('<?php echo $Row['Id']; ?>');" >
										</td>
										<?php } 
											if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)) || DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole']) || DroitsPlateforme(array($IdPosteResponsablePlateforme))){
										?>
										<td width="20"><?php if($Row['TransfertEC']==1){echo "<input type='checkbox' class='checkOutils'  Id='checkOutils_".$Row['Id']."_".$Row['TypeSelect']."' name='Id_Outils[]' value='".$Row['Id']."' onchange='couleurLigneInventaire(\"".$valeur."\");' >";} ?></td>
										<?php } ?>
								<?php 			
									}
									else{
								?>
										<?php if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)) || DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole']) || DroitsPlateforme(array($IdPosteResponsablePlateforme))){ ?>
										<td>
											<input type="image" class="checkPersonne" width="15px" src="../../Images/RH/Transfert.png" style="border:0;" alt="Transfert" title="Transfert" onclick="javascript:OuvreFenetreTransfertCaisse('<?php echo $Row['Id']; ?>');">
										</td>
										<?php } 
											if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
										?>
										<td>
										</td>
										<?php }
										else{
											echo "<td></td>";
										}
										?>
								<?php 			
									}
							?>
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
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="15%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Number of lines per page ";}else{echo "Nombre de ligne par page ";}?></td>
				<td width="60%">
					<input type="text" onKeyUp="nombre(this)" id="nbLigne" name="nbLigne" size="10" value="<?php echo $_SESSION['NbLigne_ToolsInventaireMateriel'];?>"/>
					<input class="Bouton"  name="BtnNbLigne" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
		</table>
	</td></tr>
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>