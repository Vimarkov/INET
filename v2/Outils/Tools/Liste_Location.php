<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
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
			var w=window.open("Ajout_TransfertMateriels.php?Page=Location&Ids="+Id_Outils,"PageToolsTransfert","status=no,menubar=no,width=850,height=500");
			w.focus();
		}
	}
	function OuvreFenetreTransfert(Id){
		var w=window.open("Ajout_TransfertMateriel.php?Page=Location&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=850,height=650");
		w.focus();
	}
	function OuvreFenetreTransfertCaisse(Id){
		var w=window.open("Ajout_TransfertCaisse.php?Page=Location&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=700,height=650");
		w.focus();
	}
	function OuvreFenetreSAV(Type,Id){
		var w=window.open("Ajout_SAV.php?Page=Location&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreHistorique(Type,Id){
		var w=window.open("HistoriqueMateriel.php?Page=Location&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreMaterielPersonne(Id_Personne){
		var w=window.open("MaterielPersonne.php?Page=Location&Id_Personne="+Id_Personne,"PageToolsPersonne","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreMaterielCaisse(Id_Caisse){
		var w=window.open("MaterielCaisse.php?Page=Location&Id_Caisse="+Id_Caisse,"PageToolsCaisse","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetrePerte(Type,Id){
		var w=window.open("Ajout_Perte.php?Page=Location&Type="+Type+"&Id="+Id,"PageToolsPerte","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreExcel()
		{window.open("Export_Location.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreInventaire()
		{window.open("Inventaire_Location.php","PageInventaire","status=no,menubar=no,width=900,height=450");}
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkOutils");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
				arrayCellules = document.getElementById(elements[i].value+'_Outils').cells;
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
				arrayCellules = document.getElementById(elements[i].value+'_Outils').cells;
				longueur = arrayCellules.length;
				j=0;
				while(j<longueur)
				{
					arrayCellules[j].style.backgroundColor = document.getElementById(elements[i].value+'_Outils').style.backgroundColor;
					j++;
				}
			}
		}
	}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab =  array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","DateReception","LIBELLE_FOURNISSEUR","LIBELLE_FABRICANT","LIBELLE_PLATEFORME","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TYPEMATERIEL','FAMILLEMATERIEL','Designation','DateDebutLocation','DateFinLocation');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsLocation_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsLocation_General']);
			$_SESSION['TriToolsLocation_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsLocation_General']);
			$_SESSION['TriToolsLocation_General']= str_replace($tri." ASC","",$_SESSION['TriToolsLocation_General']);
			$_SESSION['TriToolsLocation_General']= str_replace($tri." DESC","",$_SESSION['TriToolsLocation_General']);
			if($_SESSION['TriToolsLocation_'.$tri]==""){$_SESSION['TriToolsLocation_'.$tri]="ASC";$_SESSION['TriToolsLocation_General'].= $tri." ".$_SESSION['TriToolsLocation_'.$tri].",";}
			elseif($_SESSION['TriToolsLocation_'.$tri]=="ASC"){$_SESSION['TriToolsLocation_'.$tri]="DESC";$_SESSION['TriToolsLocation_General'].= $tri." ".$_SESSION['TriToolsLocation_'.$tri].",";}
			else{$_SESSION['TriToolsLocation_'.$tri]="";}
		}
	}
}
?>
<form id="formulaire" class="test" action="Liste_Location.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f9cf3d;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Locations";}else{echo "Rentals";}
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
			<?php
				$prestationA=$_SESSION['FiltreToolsLocation_PrestationA'];
				if($_POST){
					if(!empty($_POST['prestationA'])){
						$prestationA="1";
					}
					else{
						$prestationA="0";
					}
				}
				$_SESSION['FiltreToolsLocation_PrestationA']=$prestationA;
				
				$prestationI=$_SESSION['FiltreToolsLocation_PrestationI'];
				if($_POST){
					if(!empty($_POST['prestationI'])){
						$prestationI="1";
					}
					else{
						$prestationI="0";
					}
				}
				$_SESSION['FiltreToolsLocation_PrestationI']=$prestationI;
				
				$personneEC=$_SESSION['FiltreToolsLocation_PersonneEC'];
				if($_POST){
					if(!empty($_POST['personneEC'])){
						$personneEC="1";
					}
					else{
						$personneEC="0";
					}
				}
				$_SESSION['FiltreToolsLocation_PersonneEC']=$personneEC;
				
				$personneSortie=$_SESSION['FiltreToolsLocation_PersonneSortie'];
				if($_POST){
					if(!empty($_POST['personneSortie'])){
						$personneSortie="1";
					}
					else{
						$personneSortie="0";
					}
				}
				$_SESSION['FiltreToolsLocation_PersonneSortie']=$personneSortie;
				
				$materielEquipe=$_SESSION['FiltreToolsLocation_MaterielEquipe'];
				if($_POST){
					if(!empty($_POST['materielEquipe'])){
						$materielEquipe="1";
					}
					else{
						$materielEquipe="0";
					}
				}
				if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){}
				else{$materielEquipe=0;}
				$_SESSION['FiltreToolsLocation_MaterielEquipe']=$materielEquipe;
			?>
			<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
			<td class="Libelle">&nbsp;A&nbsp;<input type="checkbox" name="prestationA" alt="Actif" title="Actif" <?php if($prestationA=="1"){echo "checked";} ?> onchange="submit();"/></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Lieu :";}else{echo "Place :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?></td>
			<td class="Libelle" align="right">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "E/C";}else{echo "In Progress";} ?>&nbsp;<input type="checkbox" name="personneEC" alt="<?php if($_SESSION["Langue"]=="FR"){echo "E/C";}else{echo "In Progress";} ?>" title="<?php if($_SESSION["Langue"]=="FR"){echo "E/C";}else{echo "In Progress";} ?>" <?php if($personneEC=="1"){echo "checked";} ?> onchange="submit();"/></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?></td>
		</tr>
		
		<tr>
			<?php
				$numAAA=$_SESSION['FiltreToolsLocation_NumAAA'];
				if($_POST){
					if(isset($_POST['numAAA'])){
						$numAAA=$_POST['numAAA'];
					}
				}
				$_SESSION['FiltreToolsLocation_NumAAA']=$numAAA;
				
				$Id_Plateforme=$_SESSION['FiltreToolsLocation_Plateforme'];
				if($_POST){$Id_Plateforme=$_POST['laplateforme'];}
				$_SESSION['FiltreToolsLocation_Plateforme']=$Id_Plateforme;
			?>
			<td width="8%" class="Libelle">
				&nbsp;<select name="laplateforme" id="laplateforme" style="width:100px" onchange="submit();">
				<option value="0"></option>
					<?php
						$requetePlat="SELECT Id, Libelle
							FROM new_competences_plateforme
							WHERE Id NOT IN (11,14)
							ORDER BY Libelle";
						$resultsPlat=mysqli_query($bdd,$requetePlat);
						while($rowPlat=mysqli_fetch_array($resultsPlat))
						{
							$selected="";
							if($Id_Plateforme==$rowPlat['Id']){$selected="selected";}
							echo "<option value='".$rowPlat['Id']."' ".$selected.">";
							echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="3%" class="Libelle">
				&nbsp;I&nbsp;&nbsp;<input type="checkbox" name="prestationI" alt="Inactif" title="Inactif" <?php if($prestationI=="1"){echo "checked";} ?> onchange="submit();"/>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php

				$requeteSite="SELECT Id, Libelle, Active
					FROM new_competences_prestation 
					WHERE Id>0 ";
				if($Id_Plateforme>0){
					$requeteSite.=" AND Id_Plateforme=".$Id_Plateforme." ";
				}
				if($prestationA=="1" && $prestationI=="0"){$requeteSite.=" AND Active=0 ";}
				if($prestationA=="0" && $prestationI=="1"){$requeteSite.=" AND (Active=-1 OR (SELECT COUNT(Actif) FROM new_competences_pole WHERE Id_Prestation=new_competences_prestation.Id AND Actif=1)>0 ) ";}
				if($prestationA=="0" && $prestationI=="0"){$requeteSite.=" AND Active=0 ";}
				
				$requeteSite.=" ORDER BY Libelle ASC";

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreToolsLocation_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreToolsLocation_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				$PrestationAAfficher=array();
				array_push($PrestationAAfficher,0);

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
			<td width="10%" class="Libelle">
				&nbsp;<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php
				$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle, Actif
						FROM new_competences_pole
						LEFT JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE new_competences_pole.Id>0
						AND new_competences_pole.Id_Prestation=".$PrestationSelect." ";
				if($prestationA=="1" && $prestationI=="0"){$requetePole.=" AND Actif=0 ";}
				if($prestationA=="0" && $prestationI=="1"){$requetePole.=" AND Actif=1 ";}
				if($prestationA=="0" && $prestationI=="0"){$requetePole.=" AND Actif=0 ";}
				
				$requetePole.=" ORDER BY new_competences_pole.Libelle ASC";
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreToolsLocation_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsLocation_Pole']=$PoleSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						$active="";
						if($row['Actif']<>0){$active=" [INACTIVE]";}
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle']).$active."</option>\n";
					}
				 }
				 ?>
				</select>

			</td>
			<td width="15%" class="Libelle">
				&nbsp;<select style="width:150px;" name="lieu" onchange="submit();">
				<?php
				$requete="SELECT tools_lieu.Id, tools_lieu.Libelle
					FROM tools_lieu
					WHERE Suppr=0
					AND Id_Prestation=".$PrestationSelect."
					AND Id_Pole=".$PoleSelect."
					ORDER BY Libelle ASC";

				$resultLieu=mysqli_query($bdd,$requete);
				$nbLieu=mysqli_num_rows($resultLieu);
				
				$LieuSelect=$_SESSION['FiltreToolsLocation_Lieu'];
				if($_POST){$LieuSelect=$_POST['lieu'];}
				if($PrestationSelect==0){$LieuSelect=0;}
				$_SESSION['FiltreToolsLocation_Lieu']=$LieuSelect;
				
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
			<td width="15%" class="Libelle">
				&nbsp;<select style="width:200px;" name="caisse" onchange="submit();">
				<?php
				$requete="SELECT DISTINCT tools_caisse.Id, tools_caisse.Num, (SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS CaisseType
						FROM tools_caisse
						WHERE tools_caisse.Id>0 ";
				
				if($_SESSION['FiltreToolsLocation_Plateforme']<>"0"){
					$requete.=" AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN (".$_SESSION['FiltreToolsLocation_Plateforme'].")";
				}
				$requete.=" ORDER BY tools_caisse.Num ASC";

				$resultCaisse=mysqli_query($bdd,$requete);
				$nbCaisse=mysqli_num_rows($resultCaisse);
				
				$CaisseSelect=$_SESSION['FiltreToolsLocation_Caisse'];
				if($_POST){$CaisseSelect=$_POST['caisse'];}
				$_SESSION['FiltreToolsLocation_Caisse']=$CaisseSelect;
				
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
					 $_SESSION['FiltreToolsLocation_Caisse']="0";
				 }
				 ?>
				</select>

			</td>
			<td width="5%" class="Libelle" align="right">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Sorties";}else{echo "Outings";} ?>&nbsp;&nbsp;<input type="checkbox" name="personneSortie" alt="<?php if($_SESSION["Langue"]=="FR"){echo "Sorties";}else{echo "Outings";} ?>" title="<?php if($_SESSION["Langue"]=="FR"){echo "Sorties";}else{echo "Outings";} ?>" <?php if($personneSortie=="1"){echo "checked";} ?> onchange="submit();"/>
			</td>
			<td width="15%" class="Libelle">
				<?php
					if($personneEC=="1" && $personneSortie=="0"){
						if($Id_Plateforme==1){
							$requetePersonne="
									SELECT
										DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil
									WHERE (
										SELECT COUNT(rh_personne_mouvement.Id)
										FROM rh_personne_mouvement
										WHERE Suppr=0
										AND Id_Personne=new_rh_etatcivil.Id 
										AND EtatValidation=1
										AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
										AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$Id_Plateforme."
									)>0
									ORDER BY
										Personne ASC";
						}
						else{
							$requetePersonne="
									SELECT
										DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil
									WHERE (
										SELECT COUNT(new_competences_personne_prestation.Id)
										FROM new_competences_personne_prestation
										WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)=".$Id_Plateforme."
										AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
										AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
									)>0
									ORDER BY
										Personne ASC";
						}
					}
					elseif($personneEC=="0" && $personneSortie=="1"){
						$requetePersonne="
									SELECT
										DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil
									WHERE (
										SELECT COUNT(new_competences_personne_plateforme.Id_Personne)
										FROM new_competences_personne_plateforme
										WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id 
										AND Id_Plateforme IN (11,14)
									)>0
									ORDER BY
										Personne ASC";
					}
					else{
						$requetePersonne="
								SELECT
									DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM new_rh_etatcivil
								
								ORDER BY
									Personne ASC";
					}
					$resultPersonne=mysqli_query($bdd,$requetePersonne);
					$NbPersonne=mysqli_num_rows($resultPersonne);
					
					$personne=$_SESSION['FiltreToolsLocation_Personne'];
					if($_POST){$personne=$_POST['personne'];}
					$_SESSION['FiltreToolsLocation_Personne']= $personne;
				?>
				&nbsp;<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
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
			<td class="Libelle" colspan="2">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° ou S/N :";}else{echo "N° ou S/N :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de matériel :";}else{echo "Kind of material :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Famille de matériel :";}else{echo "Family of material :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Modèle de matériel :";}else{echo "Model of material :";} ?></td>
			<td></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Désignation :";}else{echo "Designation :";} ?></td>
		</tr>
		<tr>
			<?php
				$num=$_SESSION['FiltreToolsLocation_Num'];
				if($_POST){$num=$_POST['num'];}
				$_SESSION['FiltreToolsLocation_Num']=$num;
			?>
			<td colspan="2" class="Libelle">
				&nbsp;<input id="numAAA" name="numAAA" type="texte" value="<?php echo $numAAA; ?>" size="15"/>
			</td>
			<td width="13%" class="Libelle">
				&nbsp;<input id="num" name="num" type="texte" value="<?php echo $num; ?>" size="15"/>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<select style="width:100px;" name="typeMateriel" onchange="submit();">
				<?php
				$RequeteTypeMateriel="
					SELECT
						Id,
						Libelle
					FROM
						tools_typemateriel
					WHERE
						Suppr=0 ";
				$RequeteTypeMateriel.="ORDER BY
						Libelle ASC";
				$ResultTypeMateriel=mysqli_query($bdd,$RequeteTypeMateriel);
				$nbTypeMateriel=mysqli_num_rows($ResultTypeMateriel);
				
				$Selected = "";
				$TypeMaterielSelect=$_SESSION['FiltreToolsLocation_TypeMateriel'];
				if($_POST){$TypeMaterielSelect=$_POST['typeMateriel'];}
				$_SESSION['FiltreToolsLocation_TypeMateriel']=$TypeMaterielSelect;	
				
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
			<td class="Libelle">
				&nbsp;<select style="width:100px;" name="familleMateriel" onchange="submit();">
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
				$FamilleMaterielSelect=$_SESSION['FiltreToolsLocation_FamilleMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				$_SESSION['FiltreToolsLocation_FamilleMateriel']=$FamilleMaterielSelect;	
				
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
			<td class="Libelle">
				&nbsp;<select style="width:100px;" name="modeleMateriel" onchange="submit();">
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
				$ModeleMaterielSelect=$_SESSION['FiltreToolsLocation_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}
				$_SESSION['FiltreToolsLocation_ModeleMateriel']=$ModeleMaterielSelect;	
				
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
			<?php
				$remarque=$_SESSION['FiltreToolsLocation_Remarque'];
				if($_POST){$remarque=$_POST['remarque'];}
				$_SESSION['FiltreToolsLocation_Remarque']=$remarque;
				
				$designation=$_SESSION['FiltreToolsLocation_Designation'];
				if($_POST){$designation=$_POST['designation'];}
				$_SESSION['FiltreToolsLocation_Designation']=$designation;
			?>
			<td></td>
			<td class="Libelle">
				&nbsp;<input id="designation" name="designation" type="texte" value="<?php echo $designation; ?>" size="15"/>
			</td>
			<td width="5%">
				<a href="javascript:OuvreFenetreExcel()">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
			</td>
			
		</tr>
		<tr>
			<td class="Libelle" colspan=2>&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date d'affectation :";}else{echo "Date of assignment :";}?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° fiche immo. :";}else{echo "Asset sheet no. :";}?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Remarque :";}else{echo "Note :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Matériel de mes équipes :";}else{echo "Material of my teams :";}?></td>
			<td class="Libelle" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date fin location :";}else{echo "Rental end date :";}?></td>
			<td align="right" colspan="3" rowspan="2">
				&bull; <a href="javascript:OuvreFenetreInventaire();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0710 - Fiche d'inventaire";}else{echo "D-0710 - Inventory form";} ?></a>&nbsp;&nbsp;&nbsp;<br>
			</td>
		</tr>
		<tr>	
			<td width="20%" colspan=2 class="Libelle">
				<?php
				$signeDateAffectation=$_SESSION['FiltreToolsLocation_TypeDateAffectation'];
				if($_POST){$signeDateAffectation=$_POST['signeDateAffectation'];}
				$_SESSION['FiltreToolsLocation_TypeDateAffectation']=$signeDateAffectation;
				?>
				&nbsp;<select id="signeDateAffectation" name="signeDateAffectation" onchange="submit();">
					<option value='=' <?php if($signeDateAffectation=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateAffectation=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateAffectation==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateAffectation=$_SESSION['FiltreToolsLocation_DateAffectation'];
				if($_POST){$dateAffectation=$_POST['dateAffectation'];}
				$_SESSION['FiltreToolsLocation_DateAffectation']=$dateAffectation;
				
				?>
				<input id="dateAffectation" name="dateAffectation" type="date" value="<?php echo $dateAffectation; ?>" size="10"/>
			</td>
			<td width="13%"  class="Libelle">
				<?php
					$numFicheImmo=$_SESSION['FiltreToolsLocation_NumFicheImmo'];
					if($_POST){$numFicheImmo=$_POST['numFicheImmo'];}
					$_SESSION['FiltreToolsLocation_NumFicheImmo']=$numFicheImmo;
				?>
				&nbsp;<input id="numFicheImmo" name="numFicheImmo" type="texte" value="<?php echo $numFicheImmo; ?>" size="15"/>
			</td>
			<td class="Libelle">
				&nbsp;<input id="remarque" name="remarque" type="texte" value="<?php echo $remarque; ?>" size="15"/>
			</td>
			<td class="Libelle"><input type="checkbox" name="materielEquipe" <?php if($materielEquipe=="1"){echo "checked";} ?> onchange="submit();"/></td>
			<td class="Libelle">
				<?php
				$signeDateFinLocation=$_SESSION['FiltreToolsLocation_TypeDateFinLocation'];
				if($_POST){$signeDateFinLocation=$_POST['signeDateFinLocation'];}
				$_SESSION['FiltreToolsLocation_TypeDateFinLocation']=$signeDateFinLocation;
				?>
				&nbsp;<select id="signeDateFinLocation" name="signeDateFinLocation" onchange="submit();">
					<option value='=' <?php if($signeDateFinLocation=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateFinLocation=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateFinLocation==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateFinLocation=$_SESSION['FiltreToolsLocation_DateFinLocation'];
				if($_POST){$dateFinLocation=$_POST['dateFinLocation'];}
				$_SESSION['FiltreToolsLocation_DateFinLocation']=$dateFinLocation;
				
				?>
				<input id="dateFinLocation" name="dateFinLocation" type="date" value="<?php echo $dateFinLocation; ?>" size="10"/>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>

	<?php
	$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) Id
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") ";
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
	
	$req="(SELECT Id_Plateforme
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.",".$IdPosteDirection.") 
		) ";
	$Result=mysqli_query($bdd,$req);
	
	$listePlateforme="(-1)";
	$NbEnreg=mysqli_num_rows($Result);
	if($NbEnreg){
		$listePlateforme="(";
		while($RowListe=mysqli_fetch_array($Result)){
			if($listePlateforme<>"("){$listePlateforme.=",";}
			$listePlateforme.="".$RowListe['Id_Plateforme']."";
		}
		$listePlateforme.=")";
	}

	//PARTIE OUTILS DE LA REQUETE
	$Requete2="

		SELECT 
			TAB_MATERIEL.ID,
			TAB_MATERIEL.TYPESELECT,
			TAB_MATERIEL.NumAAA,
			TAB_MATERIEL.NumFicheImmo,
			TAB_MATERIEL.SN,
			TAB_MATERIEL.Num,
			TAB_MATERIEL.DateDebutLocation,
			TAB_MATERIEL.DateFinLocation,
			TAB_MATERIEL.Designation,
			TAB_MATERIEL.ID_TYPEMATERIEL,
			TAB_MATERIEL.TYPEMATERIEL,
			TAB_MATERIEL.FAMILLEMATERIEL,
			TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Remarque,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) AS DateDerniereAffectation,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) AS LIBELLE_PLATEFORME,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole,
			(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_LIEU,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))) AS NOMPRENOM_PERSONNE,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Personne,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Caisse,
			(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
			FROM tools_caisse WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5))) AS LIBELLE_CAISSETYPE

		FROM 
		(
		SELECT
			tools_materiel.Id AS ID,
			'Outils' AS TYPESELECT,
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
			DateDebutLocation AS DateDebutLocation,
			DateFinContratLocation AS DateFinLocation,
			Designation,
			tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
			(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
			tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			(SELECT IF(TAB_Mouvement.Id_Caisse=0,
						CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
						(
						SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS AffectationMouvement
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
			WHERE tools_materiel.Suppr=0
				AND tools_materiel.Location=1 
				";
	
	if($_SESSION['FiltreToolsLocation_TypeMateriel']<>"0"){$Requete.=" AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsLocation_TypeMateriel']." ";}
	if($_SESSION['FiltreToolsLocation_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsLocation_FamilleMateriel']." ";}
	if($_SESSION['FiltreToolsLocation_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsLocation_ModeleMateriel']." ";}
	if($_SESSION['FiltreToolsLocation_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsLocation_NumAAA']."%' ";}
	if($_SESSION['FiltreToolsLocation_NumFicheImmo']<>""){$Requete.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsLocation_NumFicheImmo']."%' ";}
	if($_SESSION['FiltreToolsLocation_Designation']<>""){$Requete.=" AND Designation LIKE '%".$_SESSION['FiltreToolsLocation_Designation']."%' ";}
	if($_SESSION['FiltreToolsLocation_DateFinLocation']<>""){$Requete.=" AND DateFinContratLocation ".$_SESSION['FiltreToolsLocation_TypeDateFinLocation']." '".$_SESSION['FiltreToolsLocation_DateFinLocation']."' ";}
	
		
	$Requete.="  ) AS TAB_MATERIEL 
	
	WHERE 
			 ";
		if($_SESSION['FiltreToolsLocation_PrestationA']=="1" && $_SESSION['FiltreToolsLocation_PrestationI']=="0"){
			$Requete.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0) 
			AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0) ) ";
		}
		elseif($_SESSION['FiltreToolsLocation_PrestationA']=="0" && $_SESSION['FiltreToolsLocation_PrestationI']=="1"){
			$Requete.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (-1) 
			AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),1) IN (1) ) ";
		}
		else{
			$Requete.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0,-1) 
			AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0,1) ) ";
		}
		
		if($_SESSION['FiltreToolsLocation_Num']<>""){
			$Requete.=" AND (Num
				LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%' 
				OR 
				SN LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%' 
				)";
		}
		if($_SESSION['FiltreToolsLocation_MaterielEquipe']=="1"){
			$Requete.=" AND (CONCAT(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)),'_',SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePrestaPole."
						OR 
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePlateforme."
					)  ";
		}
		if($_SESSION['FiltreToolsLocation_Caisse']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Caisse']." ";}
		if($_SESSION['FiltreToolsLocation_Personne']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Personne']." ";
		}
		if($_SESSION['FiltreToolsLocation_Plateforme']<>"0")
		{
			$Requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = ".$_SESSION['FiltreToolsLocation_Plateforme']." ";
		}
		if($_SESSION['FiltreToolsLocation_Prestation']<>"0")
		{
			$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Prestation']." ";
			if($_SESSION['FiltreToolsLocation_Pole']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Pole']." ";}
			if($_SESSION['FiltreToolsLocation_Lieu']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Lieu']." ";}
		}

		if($_SESSION['FiltreToolsLocation_DateAffectation']<>""){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) ".$_SESSION['FiltreToolsLocation_TypeDateAffectation']." '".$_SESSION['FiltreToolsLocation_DateAffectation']."' ";}
		
		if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
		
		}
		else{
			$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$IdPersonneConnectee." ";
		}
		if($_SESSION['FiltreToolsLocation_Remarque']<>""){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) LIKE \"%".$_SESSION['FiltreToolsLocation_Remarque']."%\" ";}

		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse="UNION ALL
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.NumFicheImmo,
				TAB_MATERIEL.SN,
				TAB_MATERIEL.Num,
				TAB_MATERIEL.DateDebutLocation,
				TAB_MATERIEL.DateFinLocation,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Remarque,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) AS DateDerniereAffectation,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)
					FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PLATEFORME,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole,
				(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_LIEU,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))) AS NOMPRENOM_PERSONNE,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Personne,
				TAB_MATERIEL.ID AS Id_Caisse,
				(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
				FROM tools_caisse WHERE Id=TAB_MATERIEL.ID) AS LIBELLE_CAISSETYPE
			FROM (
				SELECT Id AS ID,
				'Caisse' AS TYPESELECT,
				NumAAA AS NumAAA,
				NumFicheImmo,
				SN AS SN,
				Num AS Num,
				DateDebutLocation AS DateDebutLocation,
				DateFinContratLocation AS DateFinLocation,
				'' AS Designation,
				-1 AS Id_TYPEMATERIEL,
				'Caisse' AS TYPEMATERIEL,
				(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
				(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
				
				(
					SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',DateReception,'|.7.|',Commentaire,'|.8.|') 
					FROM tools_mouvement 
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
					ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
			";
		$RequeteCaisse="FROM
			tools_caisse
		WHERE 
			tools_caisse.Suppr=0 
			AND tools_caisse.Location=1 ";
		if($_SESSION['FiltreToolsLocation_TypeMateriel']>0){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsLocation_FamilleMateriel']<>"0"){$RequeteCaisse.=" AND Id_FamilleMateriel=".$_SESSION['FiltreToolsLocation_FamilleMateriel']." ";}
		if($_SESSION['FiltreToolsLocation_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsLocation_NumAAA']<>""){$RequeteCaisse.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsLocation_NumAAA']."%' ";}
		if($_SESSION['FiltreToolsLocation_Designation']<>""){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsLocation_NumFicheImmo']<>""){$RequeteCaisse.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsLocation_NumFicheImmo']."%' ";}
		if($_SESSION['FiltreToolsLocation_DateFinLocation']<>""){$RequeteCaisse.=" AND DateFinContratLocation ".$_SESSION['FiltreToolsLocation_TypeDateFinLocation']." '".$_SESSION['FiltreToolsLocation_DateFinLocation']."' ";}
		
		$RequeteCaisse.="  ) AS TAB_MATERIEL 
		WHERE ";
		
		if($_SESSION['FiltreToolsLocation_PrestationA']=="1" && $_SESSION['FiltreToolsLocation_PrestationI']=="0"){
			$RequeteCaisse.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0) 
			AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0) ) ";
		}
		elseif($_SESSION['FiltreToolsLocation_PrestationA']=="0" && $_SESSION['FiltreToolsLocation_PrestationI']=="1"){
			$RequeteCaisse.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (-1) 
			AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),1) IN (1) ) ";
		}
		else{
			$RequeteCaisse.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0,-1) 
			AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0,1) ) ";
		}
		
		if($_SESSION['FiltreToolsLocation_Num']<>""){$RequeteCaisse.=" AND (SN LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%' OR Num LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%')";}
		if($_SESSION['FiltreToolsLocation_Plateforme']<>"0"){$RequeteCaisse.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = ".$_SESSION['FiltreToolsLocation_Plateforme']." ";}
		if($_SESSION['FiltreToolsLocation_Prestation']<>"0")
		{
			$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Prestation']." ";
			if($_SESSION['FiltreToolsLocation_Pole']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Pole']." ";}
			if($_SESSION['FiltreToolsLocation_Lieu']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Lieu']." ";}
		}
		if($_SESSION['FiltreToolsLocation_Caisse']<>"0"){$RequeteCaisse.=" AND ID = ".$_SESSION['FiltreToolsLocation_Caisse']." ";}
		if($_SESSION['FiltreToolsLocation_Personne']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Personne']." ";}
		if($_SESSION['FiltreToolsLocation_DateAffectation']<>""){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) ".$_SESSION['FiltreToolsLocation_TypeDateAffectation']." '".$_SESSION['FiltreToolsLocation_DateAffectation']."' ";}

		if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
		}
		else{
			$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$IdPersonneConnectee." ";
		}
		if($_SESSION['FiltreToolsLocation_MaterielEquipe']=="1"){
			$RequeteCaisse.=" AND (CONCAT(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)),'_',SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePrestaPole."
						OR 
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePlateforme."
					)  ";
		}

		$requeteOrder="";
		if($_SESSION['TriToolsLocation_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsLocation_General'],0,-1);}
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$_SESSION['Page_ToolsChangement']=$page;
		
		$requeteLimite=" LIMIT ".($page*40).",40";
		
		$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder.$requeteLimite);
		
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
								<td class="EnTeteTableauCompetences"></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsLocation_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsLocation_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_SN']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=TYPEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?><?php if($_SESSION['TriToolsLocation_TypeMateriel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_TypeMateriel']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=FAMILLEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?><?php if($_SESSION['TriToolsLocation_FamilleMateriel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_FamilleMateriel']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsLocation_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=Designation"><?php if($LangueAffichage=="FR"){echo "Désignation";}else{echo "Designation";}?><?php if($_SESSION['TriToolsLocation_Designation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_Designation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=Num"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriToolsLocation_Num']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_Num']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=DateDebutLocation"><?php if($LangueAffichage=="FR"){echo "Début<br>location";}else{echo "Rental<br>start";}?><?php if($_SESSION['TriToolsLocation_DateDebutLocation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_DateDebutLocation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=DateFinLocation"><?php if($LangueAffichage=="FR"){echo "Fin<br>location";}else{echo "Rental<br>end";}?><?php if($_SESSION['TriToolsLocation_DateFinLocation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_DateFinLocation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=LIBELLE_PLATEFORME"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?><?php if($_SESSION['TriToolsLocation_LIBELLE_PLATEFORME']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_LIBELLE_PLATEFORME']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=LIBELLE_PRESTATION"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?><?php if($_SESSION['TriToolsLocation_LIBELLE_PRESTATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_LIBELLE_PRESTATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=LIBELLE_LIEU"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?><?php if($_SESSION['TriToolsLocation_LIBELLE_LIEU']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_LIBELLE_LIEU']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=LIBELLE_CAISSETYPE"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?><?php if($_SESSION['TriToolsLocation_LIBELLE_CAISSETYPE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_LIBELLE_CAISSETYPE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=NOMPRENOM_PERSONNE"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsLocation_NOMPRENOM_PERSONNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_NOMPRENOM_PERSONNE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Location.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?><?php if($_SESSION['TriToolsLocation_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsLocation_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences"  ></td>
								<td class="EnTeteTableauCompetences" colspan="5">
								<?php if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){?>
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
								$laCouleur="style=color:#000000;";
								if($Row['DateFinLocation']>'0001-01-01'){
									if($Row['DateFinLocation']<=date('Y-m-d')){$laCouleur="style=color:#f82035;";}
									elseif($Row['DateFinLocation']<=date('Y-m-d', strtotime(date('Y-m-d')." +3 month"))){$laCouleur="style=color:#edaf2b;";}
									elseif($Row['DateFinLocation']<=date('Y-m-d', strtotime(date('Y-m-d')." +6 month"))){$laCouleur="style=color:#192aff;";}
								}
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$LIBELLE_POLE="";
								if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
								
								
								$valeur=$Row['ID']."_".$Row['TYPESELECT'];
						?>
							<tr id="<?php echo $valeur;?>" style="background-color:<?php echo $Couleur;?>;">
								<?php
									if($Row['TYPESELECT']=="Outils")
									{
								?>
								<td><a style="text-decoration:none;" href="javascript:OuvreFenetreHistorique('0','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Livre.png" width="18px" border="0"></a></td>
								<?php 			
									}
									else
									{
								?>
								<td><a style="text-decoration:none;" href="javascript:OuvreFenetreHistorique('1','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Livre.png" width="18px" border="0"></a></td>
								<?php
									}
								?>
								<?php
									if($Row['TYPESELECT']=="Outils"){
									$leType=0;
									$leId=$Row['ID'];
									if($Row['Id_Caisse']>0){
										$leId=$Row['Id_Caisse'];
										$leType=1;
									}
								}
								else{
									$leType=1;
									$leId=$Row['ID'];
								}
								
								$req="SELECT 
									tools_mouvement.DateReception,
									(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
									(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
									(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
									(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
									(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
									FROM tools_mouvement
									WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=".$leType." AND tools_mouvement.Id_Materiel__Id_Caisse=".$leId."
									ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

								$ResultTransfertEC=mysqli_query($bdd,$req);
								$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
								
								$transfert="";
								if($NbEnregTransfertEC>0)
								{
									$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
									
									$LIBELLE_POLE_Transfert="";
									if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
								
									$transfert= "<span><b>Transfert en cours</b>
									</span>";
								}
								?>
								<td <?php if($Row['TransfertEC']==0){echo "style='border:dotted #22b63d 5px' id='leHover'";} ?>><?php echo $transfert;?><?php echo $Row['NumAAA'];?></td>
								<td><?php echo $Row['SN'];?></td>
								<td><?php echo stripslashes($Row['TYPEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['FAMILLEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
								<td><?php echo stripslashes($Row['Designation']);?></td>
								<td><?php echo stripslashes($Row['Num']);?></td>
								<td ><?php echo AfficheDateJJ_MM_AAAA($Row['DateDebutLocation']); ?></td>
								<td <?php echo $laCouleur; ?>><b><?php echo AfficheDateJJ_MM_AAAA($Row['DateFinLocation']); ?></b></td>
								<td><?php echo stripslashes($Row['LIBELLE_PLATEFORME']);?></td>
								<td><?php echo stripslashes(substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE);?></td>
								<td><?php echo stripslashes($Row['LIBELLE_LIEU']);?></td>
								<td>
								<?php if($Row['LIBELLE_CAISSETYPE']<>""){?><a style="color:#3e65fa;" href="javascript:OuvreFenetreMaterielCaisse('<?php echo $Row['Id_Caisse']; ?>');"><?php }?>
								<?php echo stripslashes($Row['LIBELLE_CAISSETYPE']);?>
								<?php if($Row['LIBELLE_CAISSETYPE']<>""){?></a><?php }?>
								</td>
								<td <?php if($Row['Remarque']<>""){echo "id='leHover'";} ?>>
							<?php if($Row['NOMPRENOM_PERSONNE']<>""){?><a style="color:#3e65fa;" href="javascript:OuvreFenetreMaterielPersonne('<?php echo $Row['Id_Personne']; ?>');"><?php }?>
								<?php echo stripslashes($Row['NOMPRENOM_PERSONNE']);
								if($Row['Remarque']<>""){
									echo "<img width='10px' src='../../Images/etoile.png' border='0'>";
									echo "<span>".stripslashes($Row['Remarque'])."</span>";
								}
								?>
								<?php if($Row['NOMPRENOM_PERSONNE']<>""){?></a><?php }?>
								</td>
								<td >
								<?php echo AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation']); ?>
								</td>
							<?php
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)))
								{
									if($Row['TYPESELECT']=="Outils")
									{
								?>
										<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreTransfert('<?php echo $Row['ID']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a></td>
										<td width="20"><?php if($Row['TransfertEC']==1){echo "<input type='checkbox' class='checkOutils' name='Id_Outils[]' Id='checkOutils_".$Row['ID']."_".$Row['TYPESELECT']."' value='".$Row['ID']."' onchange='couleurLigne(\"".$valeur."\");' >";} ?></td>
										<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreSAV('0','<?php echo $Row['ID']; ?>');" ><img src="../../Images/SAV.png" width="30px" title="<?php if($LangueAffichage=="FR"){echo "SAV";}else{echo "SAV";} ?>" border="0"></a></td>
										<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetrePerte('0','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Recherche.png" title="<?php if($LangueAffichage=="FR"){echo "Perte/Vol";}else{echo "Loss/Theft";} ?>" width="20px" border="0"></a></td>
								<?php 			
									}
									else
									{
								?>
										<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertCaisse('<?php echo $Row['ID']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a></td>
										<td width="20"></td>
										<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreSAV('1','<?php echo $Row['ID']; ?>');" ><img src="../../Images/SAV.png" width="30px" title="<?php if($LangueAffichage=="FR"){echo "SAV";}else{echo "SAV";} ?>" border="0"></a></td>
										<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetrePerte('1','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Recherche.png" title="<?php if($LangueAffichage=="FR"){echo "Perte/Vol";}else{echo "Loss/Theft";} ?>" width="20px" border="0"></a></td>
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
	<tr>
		<td height="150px"></td>
	</tr>
</table>
</form>
<?php

mysqli_close($bdd);					// Fermeture de la connexion

?>

</body>
</html>