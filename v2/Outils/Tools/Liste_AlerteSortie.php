<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
	function OuvreFenetreTransfert(Id){
		var w=window.open("Ajout_TransfertMateriel.php?Page=AlerteSortie&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreTransfertCaisse(Id){
		var w=window.open("Ajout_TransfertCaisse.php?Page=AlerteSortie&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
		w.focus();
	}
	function OuvreFenetreExcel()
		{window.open("Export_AlerteSortie.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
	function OuvreFenetreTransfertOutils(){
		var elements = document.getElementsByClassName("checkOutils");
		Id_Outils="";
		for(var i=0, l=elements.length; i<l; i++){
			if(elements[i].checked ==true){
				elmt=elements[i].value.substr(0,elements[i].value.length-7);
				Id_Outils=Id_Outils+elmt+",";
			}
		}
		if(Id_Outils!=""){
			Id_Outils=Id_Outils.substring(0,Id_Outils.length-1);
			var w=window.open("Ajout_TransfertMateriels.php?Page=AlerteSortie&Ids="+Id_Outils,"PageToolsTransfert","status=no,menubar=no,width=850,height=500");
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
	function EditerPretMateriel(Id)
	{
		window.open("EditerPretMateriel.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");
	}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_PRESTATION",'NOMPRENOM_PERSONNE','DateDerniereAffectation','LIBELLE_NOUVELLEPRESTATION','DateMouvementPrestation');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsAlerteSortie_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsAlerteSortie_General']);
			$_SESSION['TriToolsAlerteSortie_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsAlerteSortie_General']);
			$_SESSION['TriToolsAlerteSortie_General']= str_replace($tri." ASC","",$_SESSION['TriToolsAlerteSortie_General']);
			$_SESSION['TriToolsAlerteSortie_General']= str_replace($tri." DESC","",$_SESSION['TriToolsAlerteSortie_General']);
			if($_SESSION['TriToolsAlerteSortie_'.$tri]==""){$_SESSION['TriToolsAlerteSortie_'.$tri]="ASC";$_SESSION['TriToolsAlerteSortie_General'].= $tri." ".$_SESSION['TriToolsAlerteSortie_'.$tri].",";}
			elseif($_SESSION['TriToolsAlerteSortie_'.$tri]=="ASC"){$_SESSION['TriToolsAlerteSortie_'.$tri]="DESC";$_SESSION['TriToolsAlerteSortie_General'].= $tri." ".$_SESSION['TriToolsAlerteSortie_'.$tri].",";}
			else{$_SESSION['TriToolsAlerteSortie_'.$tri]="";}
		}
	}
}

?>
<form id="formulaire" class="test" action="Liste_AlerteSortie.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#feff19;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des personnes en Z-SORTIE ( ou prestation inactive) avec du matériel";}else{echo "List of people in Z-EXIT (or inactive site) with equipment";}
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
				$numAAA=$_SESSION['FiltreToolsAlerteSortie_NumAAA'];
				if($_POST){$numAAA=$_POST['numAAA'];}
				$_SESSION['FiltreToolsAlerteSortie_NumAAA']=$numAAA;
			?>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?>
				<input id="numAAA" name="numAAA" type="texte" value="<?php echo $numAAA; ?>" size="15"/>&nbsp;&nbsp;
			</td>
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
							ORDER BY Libelle ASC";
						
					}

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreToolsAlerteSortie_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsAlerteSortie_Prestation']=$PrestationSelect;	
				
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
						AND new_competences_pole.Id_Prestation=".$PrestationSelect."
						ORDER BY new_competences_pole.Libelle ASC";
				}
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreToolsAlerteSortie_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsAlerteSortie_Pole']=$PoleSelect;
				
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
						
						$personne=$_SESSION['FiltreToolsAlerteSortie_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreToolsAlerteSortie_Personne']= $personne;
						
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
				$TypeMaterielSelect=$_SESSION['FiltreToolsAlerteSortie_TypeMateriel'];
				if($_POST){$TypeMaterielSelect=$_POST['typeMateriel'];}
				$_SESSION['FiltreToolsAlerteSortie_TypeMateriel']=$TypeMaterielSelect;	
				
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
				$FamilleMaterielSelect=$_SESSION['FiltreToolsAlerteSortie_FamilleMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				$_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']=$FamilleMaterielSelect;	
				
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
				$ModeleMaterielSelect=$_SESSION['FiltreToolsAlerteSortie_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}
				$_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']=$ModeleMaterielSelect;	
				
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
			<td colspan="3" align="right">
				<?php if($personne>0){ ?>
				&bull; <a href="javascript:EditerPretMateriel('<?php echo $personne;?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0711 - Prêt de matériel";}else{echo "D-0711 - Loan of equipment";} ?></a>&nbsp;&nbsp;&nbsp;
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<?php
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme,$IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
			$req="SELECT CONCAT(Id,'_0') AS Id
				FROM new_competences_prestation
				WHERE Id_Plateforme IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
					)
				AND Id NOT IN (
						SELECT Id_Prestation
						FROM new_competences_pole    
						WHERE Actif=0
					)
					
				UNION 
				
				SELECT DISTINCT CONCAT(new_competences_pole.Id_Prestation,'_',new_competences_pole.Id) AS Id
					FROM new_competences_pole
					INNER JOIN new_competences_prestation
					ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
					AND Actif=0
					AND Id_Plateforme IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
					)
				";
		}
		else{
			$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			";
			
		}
		$Result=mysqli_query($bdd,$req);
		
		$listePrestaPole="('-1_-1')";
		$NbEnreg=mysqli_num_rows($Result);
		if($NbEnreg){
			$listePrestaPole="(";
			while($RowListe=mysqli_fetch_array($Result)){
				if($listePrestaPole<>"("){$listePrestaPole.=",";}
				$listePrestaPole.="'".$RowListe['Id']."'";
			}
			$listePrestaPole.=")";
		}
		
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
					EtatValidationT AS TransfertEC,
					tools_famillemateriel.Id_TypeMateriel,
					tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
					DateReceptionT AS DateDerniereAffectation,
					Id_PrestationT AS Id_Prestation,
					Id_PoleT AS Id_Pole,
					(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT) AS LIBELLE_PRESTATION,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT) AS LIBELLE_POLE,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
					Id_PersonneT AS Id_Personne,
					(
						IF((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT)=-1,'FIN PRESTATION',
							IF((SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT)=1,'FIN PÔLE',
								IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
									FROM new_competences_personne_plateforme
									WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
									AND new_competences_personne_plateforme.Id_Plateforme=14)>0,'SORTIE',
										IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
										FROM new_competences_personne_plateforme
										WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
										AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0,
										(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_personne_plateforme.Id_Plateforme)
										FROM new_competences_personne_plateforme
										WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
										AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT)),''
										)
								)
							)
						)
					) AS NouvelleAffectation
				FROM 
					tools_materiel
				LEFT JOIN
					tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
				LEFT JOIN
					tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
				LEFT JOIN
					tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
				WHERE Id_PersonneT>0
				AND tools_materiel.Suppr=0 
				AND EtatValidationT IN (0,1)
				AND 
				(
					(
						(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
						FROM new_competences_personne_plateforme
						WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
						AND new_competences_personne_plateforme.Id_Plateforme=14)>0
					AND
						(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
						FROM new_competences_personne_plateforme
						WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1
					)
					OR 
					(
						SELECT Active 
						FROM new_competences_prestation 
						WHERE new_competences_prestation.Id=Id_PrestationT
					)=-1
					OR 
					(
						SELECT Actif
						FROM new_competences_pole
						WHERE new_competences_pole.Id=Id_PoleT
					)=1
					OR 
					(
						(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
						FROM new_competences_personne_plateforme
						WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
						AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0
					AND
						(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
						FROM new_competences_personne_plateforme
						WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1
					)
				)
				
		";

		if($_SESSION['FiltreToolsAlerteSortie_NumAAA']<>""){
			$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsAlerteSortie_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_Prestation']<>"0"){
			$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsAlerteSortie_Prestation']." ";
			if($_SESSION['FiltreToolsAlerteSortie_Pole']<>"0"){
				$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsAlerteSortie_Pole']." ";
			}
		}
		else
		{
			$Requete.=" AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
		}

		if($_SESSION['FiltreToolsAlerteSortie_Personne']<>"0"){
			$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsAlerteSortie_Personne']." ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_TypeMateriel']<>"0"){
			$Requete.=" AND Id_TypeMateriel = ".$_SESSION['FiltreToolsAlerteSortie_TypeMateriel']." ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']<>"0"){
			$Requete.=" AND Id_FamilleMateriel = ".$_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']." ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']<>"0"){
			$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']." ";
		}

		//PARTIE CAISSE DE LA REQUETE
		$Requete.="
				UNION ALL
				SELECT 
					tools_caisse.Id,
					'Caisse' AS TypeSelect,
					NumAAA AS NumAAA,
					SN AS SN,
					Num AS Num,
					EtatValidationT AS TransfertEC,
					-1 AS Id_TypeMateriel,
					(SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
					DateReceptionT AS DateDerniereAffectation,
					Id_PrestationT AS Id_Prestation,
					Id_PoleT AS Id_Pole,
					(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT) AS LIBELLE_PRESTATION,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT) AS LIBELLE_POLE,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
					Id_PersonneT AS Id_Personne,
					(
						IF((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT)=-1,'FIN PRESTATION',
							IF((SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT)=1,'FIN PÔLE',
								IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
									FROM new_competences_personne_plateforme
									WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
									AND new_competences_personne_plateforme.Id_Plateforme=14)>0,'SORTIE',
										IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
										FROM new_competences_personne_plateforme
										WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
										AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_personne_plateforme.Id_Plateforme)
											FROM new_competences_personne_plateforme
											WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
											AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT)),''))
							)
						)
					) AS NouvelleAffectation
				FROM 
					tools_caisse
				WHERE Id_PersonneT>0
				AND tools_caisse.Suppr=0
				AND EtatValidationT IN (0,1)
				AND 
				(
					((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
					FROM new_competences_personne_plateforme
					WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
					AND new_competences_personne_plateforme.Id_Plateforme=14)>0
					AND
					(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
					FROM new_competences_personne_plateforme
					WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
					OR 
					(
						SELECT Active 
						FROM new_competences_prestation 
						WHERE new_competences_prestation.Id=Id_PrestationT
					)=-1
					OR 
					(
						SELECT Actif
						FROM new_competences_pole
						WHERE new_competences_pole.Id=Id_PoleT
					)=1
					OR 
					((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
					FROM new_competences_personne_plateforme
					WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
					AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0
					AND
					(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
					FROM new_competences_personne_plateforme
					WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
				)
				
		";

		if($_SESSION['FiltreToolsAlerteSortie_NumAAA']<>""){
			$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsAlerteSortie_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_Prestation']<>"0"){
			$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsAlerteSortie_Prestation']." ";
			if($_SESSION['FiltreToolsAlerteSortie_Pole']<>"0"){
				$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsAlerteSortie_Pole']." ";
			}
		}
		else
		{
			$Requete.=" AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
		}
		
		if($_SESSION['FiltreToolsAlerteSortie_Personne']<>"0"){
			$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsAlerteSortie_Personne']." ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_TypeMateriel']<>"0"){
			$Requete.=" AND Id=0 ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']<>"0"){
			$Requete.=" AND Id=0 ";
		}
		if($_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']<>"0"){
			$Requete.=" AND Id=0 ";
		}
		

		$requeteOrder="";
		if($_SESSION['TriToolsAlerteSortie_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsAlerteSortie_General'],0,-1);
		}

		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$_SESSION['Page_ToolsAlerteSortieMateriel']=$page;
		$Result=mysqli_query($bdd,$Requete.$requeteOrder);
		$NbEnreg=mysqli_num_rows($Result);
	?>
	<tr>
		<td width="100%">
			<table width="100%">
				<tr>
					<td width="10"></td>
					<td width="100%">
						<table class="TableCompetences" width="100%">
							<tr>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsAlerteSortie_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsAlerteSortie_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_SN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsAlerteSortie_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=Num"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriToolsAlerteSortie_Num']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_Num']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=NOMPRENOM_PERSONNE"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsAlerteSortie_NOMPRENOM_PERSONNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_NOMPRENOM_PERSONNE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%" style='border-left:1px dotted black'><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=LIBELLE_PRESTATION"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?><?php if($_SESSION['TriToolsAlerteSortie_LIBELLE_PRESTATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_LIBELLE_PRESTATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%" style='border-right:1px dotted black'><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AlerteSortie.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?><?php if($_SESSION['TriToolsAlerteSortie_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsAlerteSortie_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%" style='border-right:1px dotted black'><?php if($LangueAffichage=="FR"){echo "Nouvelle prestation";}else{echo "New site";}?></td>
								<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;vertical-align:bottom;"></td>
								<td class="EnTeteTableauCompetences" width="2%">
									<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertOutils();" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert outils";}else{echo "Tool transfer";} ?>" width="20px" border="0"></a><br>
									<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" />
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
								if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
								
								$valeur=$Row['Id']."_".$Row['TypeSelect'];
						?>
							<tr id="<?php echo $valeur;?>" style="background-color:<?php echo $Couleur;?>;">
								<td><?php echo $Row['NumAAA'];?></td>
								<td><?php echo $Row['SN'];?></td>
								<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['Num']);?></td>
								<td><?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$Row['Id_Personne']."\");'>";echo stripslashes($Row['NOMPRENOM_PERSONNE'])."</a>";?></td>
								<td style='border-left:1px dotted black'><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
								<td style='border-right:1px dotted black'><?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']);?></td>
								<td style='border-right:1px dotted black'><?php echo stripslashes($Row['NouvelleAffectation']);?></td>
							<?php
								if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))  || DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole'])){
									if($Row['TypeSelect']=="Outils"){
								?>
										<td>
											<input type="image" class="checkTransfert" width="15px" src="../../Images/RH/Transfert.png" style="border:0;" alt="Transfert" title="Transfert" onclick="javascript:OuvreFenetreTransfert('<?php echo $Row['Id']; ?>');">
										</td>
										<td>
										<?php if($Row['TransfertEC']==1){echo "<input type='checkbox' class='checkOutils' name='Id_Outils[]' Id='checkOutils_".$Row['Id']."_".$Row['TypeSelect']."' value='".$Row['Id']."_".$Row['TypeSelect']."' onchange='couleurLigne(\"".$valeur."\");' >";} ?>
										</td>
								<?php 			
									}
									else{
								?>
										<td>
											<input type="image" class="checkPersonne" width="15px" src="../../Images/RH/Transfert.png" style="border:0;" alt="Transfert" title="Transfert" onclick="javascript:OuvreFenetreTransfertCaisse('<?php echo $Row['Id']; ?>');">
										</td>
										<td></td>
								<?php 			
									}
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
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>