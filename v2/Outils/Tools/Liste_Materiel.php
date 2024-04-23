<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id){
		if(Mode=="Ajout"){
			var w=window.open("Ajout_Materiel.php?Page=Materiel&Mode="+Mode+"&Id="+Id,"PageToolsAjoutMateriel","status=no,menubar=no,width=1200,height=650");
		}
		else{
			var w=window.open("Ajout_Materiel.php?Page=Materiel&Mode="+Mode+"&Id="+Id,"PageToolsModifMateriel","status=no,menubar=no,width=1200,height=800");
		}
		w.focus();
	}
	function OuvreFenetreModifCaisse(Mode,Id)
		{var w=window.open("Ajout_Caisse.php?Page=Materiel&Mode="+Mode+"&Id="+Id,"PageToolsAjoutCaisse","status=no,menubar=no,width=900,height=400");w.focus();}
	function OuvreFenetreTransfert(Id){
		var w=window.open("Ajout_TransfertMateriel.php?Page=Materiel&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=850,height=650");
		w.focus();
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
			var w=window.open("Ajout_TransfertMateriels.php?Page=Materiel&Ids="+Id_Outils,"PageToolsTransfert","status=no,menubar=no,width=850,height=500");
			w.focus();
		}
	}
	function OuvreFenetreEtalonnage(Id){
		var w=window.open("Ajout_Etalonnage.php?Page=Materiel&Id="+Id,"PageToolsEtalonnage","status=no,menubar=no,width=1100,height=650");
		w.focus();
	}
	function OuvreFenetreSAV(Type,Id){
		var w=window.open("Ajout_SAV.php?Page=Materiel&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreHistorique(Type,Id){
		var w=window.open("HistoriqueMateriel.php?Page=Materiel&Type="+Type+"&Id="+Id,"PageToolsSAV","status=no,menubar=no,scrollbars=1,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreMaterielPersonne(Id_Personne){
		var w=window.open("MaterielPersonne.php?Page=Materiel&Id_Personne="+Id_Personne,"PageToolsPersonne","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreMaterielCaisse(Id_Caisse){
		var w=window.open("MaterielCaisse.php?Page=Materiel&Id_Caisse="+Id_Caisse,"PageToolsCaisse","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetrePerte(Type,Id){
		var w=window.open("Ajout_Perte.php?Page=Materiel&Type="+Type+"&Id="+Id,"PageToolsPerte","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreExcel()
		{window.open("Export_Materiel.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreInventaire()
		{window.open("Inventaire_Materiel.php","PageInventaire","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreParcInformatique()
		{window.open("ParcInformatique_Materiel.php","PageInventaire","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreMarqueControle()
		{window.open("MarqueControle_Materiel.php","PageInventaire","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreMarqueControle2()
		{window.open("MarqueControle_Materiel2.php","PageInventaire","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreTransfertCaisse(Id){
		var w=window.open("Ajout_TransfertCaisse.php?Page=Materiel&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=700,height=650");
		w.focus();
	}
	function EditerPretMateriel2(Id){window.open("EditerPretMateriel2.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");}
	function EditerInventaire(Id){window.open("EditerInventaire.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");}
	function EditerInventairePeriodique(Id){window.open("EditerInventairePeriodique.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");}
	function OuvreFenetreModif2(Mode,Id)
		{var w=window.open("Ajout_MaterielEnMasse.php?Page=Materiel&Mode="+Mode+"&Id="+Id,"PageToolsAjoutMateriel","status=no,menubar=no,width=1200,height=650");w.focus();}
	function OuvreFenetreModif3()
		{var w=window.open("Ajout_CaisseEtMateriel.php?Page=Materiel&","PageToolsAjoutMateriel","status=no,menubar=no,width=900,height=500");w.focus();}
	function reset(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnReset2' name='btnReset2' value='Reset'>";
		document.getElementById('reset').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnReset2").dispatchEvent(evt);
		document.getElementById('reset').innerHTML="";
	}
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
	function EditerPretMateriel(Id)
	{
		window.open("EditerPretMateriel.php?Id="+Id,"Fiche_PretMateriel","status=no,menubar=no,width=20,height=20");
	}
</script>
<?php
if($_SESSION['Id_Personne']==1351){echo date("H:i:s");}
if(isset($_GET['Tri'])){
	$tab =  array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","DateReception","LIBELLE_FOURNISSEUR","LIBELLE_FABRICANT","LIBELLE_PLATEFORME","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TYPEMATERIEL','FAMILLEMATERIEL','Designation');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriToolsSuivi_General']= str_replace($tri." ASC,","",$_SESSION['TriToolsSuivi_General']);
			$_SESSION['TriToolsSuivi_General']= str_replace($tri." DESC,","",$_SESSION['TriToolsSuivi_General']);
			$_SESSION['TriToolsSuivi_General']= str_replace($tri." ASC","",$_SESSION['TriToolsSuivi_General']);
			$_SESSION['TriToolsSuivi_General']= str_replace($tri." DESC","",$_SESSION['TriToolsSuivi_General']);
			if($_SESSION['TriToolsSuivi_'.$tri]==""){$_SESSION['TriToolsSuivi_'.$tri]="ASC";$_SESSION['TriToolsSuivi_General'].= $tri." ".$_SESSION['TriToolsSuivi_'.$tri].",";}
			elseif($_SESSION['TriToolsSuivi_'.$tri]=="ASC"){$_SESSION['TriToolsSuivi_'.$tri]="DESC";$_SESSION['TriToolsSuivi_General'].= $tri." ".$_SESSION['TriToolsSuivi_'.$tri].",";}
			else{$_SESSION['TriToolsSuivi_'.$tri]="";}
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
<form id="formulaire" class="test" action="Liste_Materiel.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#23b63e;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Matériel";}else{echo "Equipment";}
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
				if(isset($_POST['btnReset2'])){
					$_SESSION['FiltreToolsSuivi_NumAAA']="";
					$_SESSION['FiltreToolsSuivi_Num']="";
					$_SESSION['FiltreToolsSuivi_NumFicheImmo']="";
					$_SESSION['FiltreToolsSuivi_Plateforme']="-1";
					$_SESSION['FiltreToolsSuivi_Prestation']="0";
					$_SESSION['FiltreToolsSuivi_Pole']="0";
					$_SESSION['FiltreToolsSuivi_Lieu']="0";
					$_SESSION['FiltreToolsSuivi_Caisse']="0";
					$_SESSION['FiltreToolsSuivi_Personne']="0";
					$_SESSION['FiltreToolsSuivi_TypeMateriel']="0";
					$_SESSION['FiltreToolsSuivi_FamilleMateriel']="0";
					$_SESSION['FiltreToolsSuivi_ModeleMateriel']="0";
					$_SESSION['FiltreToolsSuivi_DateAffectation']="";
					$_SESSION['FiltreToolsSuivi_TypeDateAffectation']="";
					$_SESSION['FiltreToolsSuivi_Remarque']="";
					$_SESSION['FiltreToolsSuivi_Designation']="";
					$_SESSION['FiltreToolsSuivi_DateReception']="";
					$_SESSION['FiltreToolsSuivi_TypeDateReception']="";
					$_SESSION['FiltreToolsSuivi_BonCommande']="";
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
					
					if(isset($_SESSION['Id_Plateformes'])){
						foreach($_SESSION['Id_Plateformes'] as $value){
							if($_SESSION['FiltreToolsSuivi_Plateforme']=="-1"){
								$_SESSION['FiltreToolsSuivi_Plateforme']=$value;
							}
						}
					}
				}
				
				$req="
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
					FROM new_competences_personne_prestation
					WHERE new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
					AND Id_Personne IN (
						SELECT new_competences_personne_metier.Id_Personne 
						FROM new_competences_personne_metier
						WHERE Futur=0 
						AND Id_Metier=85)
					)
				";
				$ResultIQ=mysqli_query($bdd,$req);
				$NbIQ=mysqli_num_rows($ResultIQ);
				
				if(isset($_POST['btnReset2'])){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
			?>
			<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Lieu :";}else{echo "Place :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?></td>
		</tr>
		
		<tr>
			<?php
				$numAAA=$_SESSION['FiltreToolsSuivi_NumAAA'];
				if($_POST){
					if(isset($_POST['numAAA'])){
						$numAAA=$_POST['numAAA'];
					}
				}
				if($numAAA<>$_SESSION['FiltreToolsSuivi_NumAAA']){
					$_SESSION['FiltreToolsSuivi_Recherche']=1;
				}
				if(isset($_POST['btnReset2'])){
					$numAAA=$_SESSION['FiltreToolsSuivi_NumAAA'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_NumAAA']=$numAAA;
				
				$Id_Plateforme=$_SESSION['FiltreToolsSuivi_Plateforme'];
				if($_POST){
					if($Id_Plateforme<>$_POST['laplateforme']){
						$_SESSION['FiltreToolsSuivi_Recherche']=0;
					}
					if(isset($_POST['btnReset2'])){
						$Id_Plateforme=$_SESSION['FiltreToolsSuivi_Plateforme'];
						$_SESSION['FiltreToolsSuivi_Recherche']=0;
					}
					$Id_Plateforme=$_POST['laplateforme'];
				}
				$_SESSION['FiltreToolsSuivi_Plateforme']=$Id_Plateforme;
			?>
			<td width="10%" class="Libelle">
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
			<td width="10%" class="Libelle">
				&nbsp;<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php

				$requeteSite="SELECT Id, Libelle, Active
					FROM new_competences_prestation 
					WHERE Id>0 ";
				if($Id_Plateforme>0){
					$requeteSite.=" AND Id_Plateforme=".$Id_Plateforme." ";
				}
				$requeteSite.=" ORDER BY Libelle ASC";

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreToolsSuivi_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				if($PrestationSelect<>$_SESSION['FiltreToolsSuivi_Prestation']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$PrestationSelect=$_SESSION['FiltreToolsSuivi_Prestation'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_Prestation']=$PrestationSelect;	
				
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
			<td width="15%" class="Libelle">
				&nbsp;<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php
				$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle, Actif
						FROM new_competences_pole
						LEFT JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE new_competences_pole.Id>0
						AND new_competences_pole.Id_Prestation=".$PrestationSelect." ";
				$requetePole.=" ORDER BY new_competences_pole.Libelle ASC";
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreToolsSuivi_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				if($PoleSelect<>$_SESSION['FiltreToolsSuivi_Pole']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$PoleSelect=$_SESSION['FiltreToolsSuivi_Pole'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if($PrestationSelect==0){$PoleSelect=0;}
				$_SESSION['FiltreToolsSuivi_Pole']=$PoleSelect;
				
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
			<td width="18%" class="Libelle">
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
				
				$LieuSelect=$_SESSION['FiltreToolsSuivi_Lieu'];
				if($_POST){$LieuSelect=$_POST['lieu'];}
				if($LieuSelect<>$_SESSION['FiltreToolsSuivi_Lieu']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$LieuSelect=$_SESSION['FiltreToolsSuivi_Lieu'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if($PrestationSelect==0){$LieuSelect=0;}
				$_SESSION['FiltreToolsSuivi_Lieu']=$LieuSelect;
				
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
			<td width="18%" class="Libelle">
				&nbsp;<select style="width:200px;" name="caisse" onchange="submit();">
				<?php
				$requete="SELECT DISTINCT tools_caisse.Id, tools_caisse.Num, (SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS CaisseType
						FROM tools_caisse
						WHERE tools_caisse.Id>0 
						AND tools_caisse.Suppr=0 ";
				
				if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0"){
					$requete.=" AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN (".$_SESSION['FiltreToolsSuivi_Plateforme'].")";
				}
				$requete.=" ORDER BY tools_caisse.Num ASC";

				$resultCaisse=mysqli_query($bdd,$requete);
				$nbCaisse=mysqli_num_rows($resultCaisse);
				
				$CaisseSelect=$_SESSION['FiltreToolsSuivi_Caisse'];
				if($_POST){$CaisseSelect=$_POST['caisse'];}
				if($CaisseSelect<>$_SESSION['FiltreToolsSuivi_Caisse']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$CaisseSelect=$_SESSION['FiltreToolsSuivi_Caisse'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_Caisse']=$CaisseSelect;
				
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
					 $_SESSION['FiltreToolsSuivi_Caisse']="0";
				 }
				 ?>
				</select>

			</td>
			<td width="15%" class="Libelle">
				<?php
					
					$requetePersonne="
							SELECT
								new_rh_etatcivil.Id, 
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM new_rh_etatcivil
							WHERE Id IN (
								SELECT DISTINCT Id_Personne FROM tools_mouvement
							)
							ORDER BY
								Personne ASC";
					$resultPersonne=mysqli_query($bdd,$requetePersonne);
					$NbPersonne=mysqli_num_rows($resultPersonne);
					
					$personne=$_SESSION['FiltreToolsSuivi_Personne'];
					if($_POST){$personne=$_POST['personne'];}
					if($personne<>$_SESSION['FiltreToolsSuivi_Personne']){
						$_SESSION['FiltreToolsSuivi_Recherche']=0;
					}
					if(isset($_POST['btnReset2'])){
						$personne=$_SESSION['FiltreToolsSuivi_Personne'];
						$_SESSION['FiltreToolsSuivi_Recherche']=0;
					}
					$_SESSION['FiltreToolsSuivi_Personne']= $personne;
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
			<td width="5%">
				<img id="btnReset" name="btnReset" width="15px" src="../../Images/Gomme.png" alt="submit" style="cursor:pointer;" onclick="reset();"/> 
				<div id="reset"></div>
			</td>
		</tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° ou S/N :";}else{echo "N° ou S/N :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de matériel :";}else{echo "Kind of material :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Famille de matériel :";}else{echo "Family of material :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Modèle de matériel :";}else{echo "Model of material :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Désignation :";}else{echo "Designation :";} ?></td>
		</tr>
		<tr>
			<?php
				$num=$_SESSION['FiltreToolsSuivi_Num'];
				if($_POST){$num=$_POST['num'];}
				if($num<>$_SESSION['FiltreToolsSuivi_Num']){
					$_SESSION['FiltreToolsSuivi_Recherche']=1;
				}
				if(isset($_POST['btnReset2'])){
					$num=$_SESSION['FiltreToolsSuivi_Num'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_Num']=$num;
			?>
			<td width="10%" class="Libelle">
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
				$TypeMaterielSelect=$_SESSION['FiltreToolsSuivi_TypeMateriel'];
				if($_POST){$TypeMaterielSelect=$_POST['typeMateriel'];}
				if($TypeMaterielSelect<>$_SESSION['FiltreToolsSuivi_TypeMateriel']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$TypeMaterielSelect=$_SESSION['FiltreToolsSuivi_TypeMateriel'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_TypeMateriel']=$TypeMaterielSelect;	
				
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
			<td width="10%" class="Libelle">
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
				$FamilleMaterielSelect=$_SESSION['FiltreToolsSuivi_FamilleMateriel'];
				if($_POST){$FamilleMaterielSelect=$_POST['familleMateriel'];}
				if($TypeMaterielSelect==0){$FamilleMaterielSelect=0;}
				if($FamilleMaterielSelect<>$_SESSION['FiltreToolsSuivi_FamilleMateriel']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$FamilleMaterielSelect=$_SESSION['FiltreToolsSuivi_FamilleMateriel'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_FamilleMateriel']=$FamilleMaterielSelect;	
				
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
				$nbTypeMateriel=0;
				
				if($TypeMaterielSelect==-1){
					$Requete="
						SELECT
							Id,
							Libelle
						FROM
							tools_caissetype
						WHERE
							Suppr=0
						AND Id_Plateforme=".$Id_Plateforme."
						ORDER BY
							Libelle ASC";
					$ResultTypeMateriel=mysqli_query($bdd,$Requete);
					$nbTypeMateriel=mysqli_num_rows($ResultTypeMateriel);
				}
				else{
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
				}
				$Selected = "";
				$ModeleMaterielSelect=$_SESSION['FiltreToolsSuivi_ModeleMateriel'];
				if($_POST){$ModeleMaterielSelect=$_POST['modeleMateriel'];}
				if($TypeMaterielSelect>-1){if($FamilleMaterielSelect==0){$ModeleMaterielSelect=0;}}
				if($ModeleMaterielSelect<>$_SESSION['FiltreToolsSuivi_ModeleMateriel']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$ModeleMaterielSelect=$_SESSION['FiltreToolsSuivi_ModeleMateriel'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_ModeleMateriel']=$ModeleMaterielSelect;	
				
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
				$remarque=$_SESSION['FiltreToolsSuivi_Remarque'];
				if($_POST){$remarque=$_POST['remarque'];}
				if($remarque<>$_SESSION['FiltreToolsSuivi_Remarque']){
					$_SESSION['FiltreToolsSuivi_Recherche']=1;
				}
				if(isset($_POST['btnReset2'])){
					$remarque=$_SESSION['FiltreToolsSuivi_Remarque'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_Remarque']=$remarque;
				
				$designation=$_SESSION['FiltreToolsSuivi_Designation'];
				if($_POST){$designation=$_POST['designation'];}
				if($designation<>$_SESSION['FiltreToolsSuivi_Designation']){
					$_SESSION['FiltreToolsSuivi_Recherche']=1;
				}
				if(isset($_POST['btnReset2'])){
					$designation=$_SESSION['FiltreToolsSuivi_Designation'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_Designation']=$designation;
			?>
			<td class="Libelle">
				&nbsp;<input id="designation" name="designation" type="texte" value="<?php echo $designation; ?>" size="15"/>
			</td>
			<td width="8%">
				<a href="javascript:OuvreFenetreExcel()">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
			</td>
			<td width="3%">
			</td>
		</tr>
		<tr>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date d'affectation :";}else{echo "Date of assignment :";}?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° fiche immo. :";}else{echo "Asset sheet no. :";}?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Remarque :";}else{echo "Note :";} ?></td>
			<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Bon de commande :";}else{echo "Purchase order :";} ?></td>
			<td align="right" colspan="5" rowspan="2">
				&bull; <a href="javascript:OuvreFenetreInventaire();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0710 - Fiche d'inventaire";}else{echo "D-0710 - Inventory form";} ?></a>&nbsp;&nbsp;&nbsp;<br>
				&bull; <a href="javascript:OuvreFenetreParcInformatique();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0714 - Parc informatique";}else{echo "D-0714 - IT stock";} ?></a>&nbsp;&nbsp;&nbsp;<br>
				&bull; <a href="javascript:OuvreFenetreMarqueControle();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0835 - Marques de contrôle";}else{echo "D-0835 - Inspection";} ?></a>&nbsp;&nbsp;&nbsp;<br>
				&bull; <a href="javascript:OuvreFenetreMarqueControle2();" style="color:black;"><?php if($LangueAffichage=="FR"){echo "Marques de contrôle (historique)";}else{echo "Control marks (history)";} ?></a>&nbsp;&nbsp;&nbsp;
				<?php if($personne>0){ ?>
				<br>&bull; <a href="javascript:EditerPretMateriel('<?php echo $personne;?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0711 - Prêt de matériel";}else{echo "D-0711 - Loan of equipment";} ?></a>&nbsp;&nbsp;&nbsp;
				<?php } 
				if($CaisseSelect>0){?>
				<br>&bull; <a href="javascript:EditerPretMateriel2('<?php echo $CaisseSelect;?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0829-001 - Inventaire Général";}else{echo "D-0829-001 - General Inventory";} ?></a>&nbsp;&nbsp;&nbsp;
				<br>&bull; <a href="javascript:EditerInventaire('<?php echo $CaisseSelect;?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0829-002 - Inventaire contenant de transfert";}else{echo "D-0829-002 - Inventaire contenant de transfert";} ?></a>&nbsp;&nbsp;&nbsp;
				
				<br>&bull; <a href="javascript:EditerInventairePeriodique('<?php echo $CaisseSelect;?>');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "D-0829-003 - Inventaire visuel et périodique des contenants";}else{echo "D-0829-003 - Visual and periodic inventory of containers";} ?></a>&nbsp;&nbsp;&nbsp;
				<?php
				}
				?>
			</td>
		</tr>
		<tr>	
			<td width="15%" class="Libelle">
				<?php
				$signeDateAffectation=$_SESSION['FiltreToolsSuivi_TypeDateAffectation'];
				if($_POST){$signeDateAffectation=$_POST['signeDateAffectation'];}
				if($signeDateAffectation<>$_SESSION['FiltreToolsSuivi_TypeDateAffectation']){
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				if(isset($_POST['btnReset2'])){
					$signeDateAffectation=$_SESSION['FiltreToolsSuivi_TypeDateAffectation'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_TypeDateAffectation']=$signeDateAffectation;
				?>
				&nbsp;<select id="signeDateAffectation" name="signeDateAffectation" onchange="submit();">
					<option value='=' <?php if($signeDateAffectation=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateAffectation=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateAffectation==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateAffectation=$_SESSION['FiltreToolsSuivi_DateAffectation'];
				if($_POST){$dateAffectation=$_POST['dateAffectation'];}
				if($dateAffectation<>$_SESSION['FiltreToolsSuivi_DateAffectation']){
					$_SESSION['FiltreToolsSuivi_Recherche']=1;
				}
				if(isset($_POST['btnReset2'])){
					$dateAffectation=$_SESSION['FiltreToolsSuivi_DateAffectation'];
					$_SESSION['FiltreToolsSuivi_Recherche']=0;
				}
				$_SESSION['FiltreToolsSuivi_DateAffectation']=$dateAffectation;
				
				?>
				<input id="dateAffectation" name="dateAffectation" type="date" value="<?php echo $dateAffectation; ?>" size="10"/>
			</td>
			<td width="13%"  class="Libelle">
				<?php
					$numFicheImmo=$_SESSION['FiltreToolsSuivi_NumFicheImmo'];
					if($_POST){$numFicheImmo=$_POST['numFicheImmo'];}
					if($numFicheImmo<>$_SESSION['FiltreToolsSuivi_NumFicheImmo']){
						$_SESSION['FiltreToolsSuivi_Recherche']=1;
					}
					if(isset($_POST['btnReset2'])){
						$numFicheImmo=$_SESSION['FiltreToolsSuivi_NumFicheImmo'];
						$_SESSION['FiltreToolsSuivi_Recherche']=0;
					}
					$_SESSION['FiltreToolsSuivi_NumFicheImmo']=$numFicheImmo;
					
					$bonCommande=$_SESSION['FiltreToolsSuivi_BonCommande'];
					if($_POST){$bonCommande=$_POST['bonCommande'];}
					if($bonCommande<>$_SESSION['FiltreToolsSuivi_BonCommande']){
						$_SESSION['FiltreToolsSuivi_Recherche']=1;
					}
					if(isset($_POST['btnReset2'])){
						$bonCommande=$_SESSION['FiltreToolsSuivi_BonCommande'];
						$_SESSION['FiltreToolsSuivi_Recherche']=0;
					}
					$_SESSION['FiltreToolsSuivi_BonCommande']=$bonCommande;
				?>
				&nbsp;<input id="numFicheImmo" name="numFicheImmo" type="texte" value="<?php echo $numFicheImmo; ?>" size="15"/>
			</td>
			<td class="Libelle">
				&nbsp;<input id="remarque" name="remarque" type="texte" value="<?php echo $remarque; ?>" size="15"/>
			</td>
			<td class="Libelle">
				&nbsp;<input id="bonCommande" name="bonCommande" type="texte" value="<?php echo $bonCommande; ?>" size="15"/>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr>
		<td>
			<table width="100%">
				<tr>
				<td align="center" width="85%" style="font-size:18px;">
					
				</td>
				<?php
					if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteControleGestion))){
				?>
				<td align="right">
					<table style='border-spacing: 0px;display:inline-table;' >
						<tr>
							<td style="width:30px;height:30px;border-style:outset; border-radius: 15px;border-color:#48dc62;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;" bgcolor='#48dc62'>
								<a style="text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;" onmouseover="this.style.color='#black';" onmouseout="this.style.color='#black';" href="javascript:OuvreFenetreModif('Ajout','0');" >
									<img src="../../Images/Cles.png" width="20px" border="0" title="Ajouter un matériel">
								</a>
							</td>
						</tr>
					</table>
					<table style='border-spacing: 0px;display:inline-table;' >
						<tr>
							<td style="width:30px;height:30px;border-style:outset; border-radius: 15px;border-color:#48dc62;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;" bgcolor='#48dc62'>
								<a style="text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;" onmouseover="this.style.color='#black';" onmouseout="this.style.color='#black';" href="javascript:OuvreFenetreModif2('Ajout','0');" >
									<img src="../../Images/Cles2.png" width="20px" border="0" title="Ajouter une liste de matériel">
								</a>
							</td>
						</tr>
					</table>
					<table style='border-spacing: 0px;display:inline-table;' >
						<tr>
							<td style="width:30px;height:30px;border-style:outset; border-radius: 15px;border-color:#48dc62;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;" bgcolor='#48dc62'>
								<a style="text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;" onmouseover="this.style.color='#black';" onmouseout="this.style.color='#black';" href="javascript:OuvreFenetreModif3();" >
									<img src="../../Images/servante.gif" width="20px" border="0" title="Ajouter une caisse">
								</a>
							</td>
						</tr>
					</table>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
				<?php
					}
				?>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	
	if(isset($_POST['btnFiltrer2'])){
		$_SESSION['FiltreToolsSuivi_Recherche']=1;
	}

	if($_SESSION['FiltreToolsSuivi_Recherche']==1){
		$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.") 
			UNION 
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
			FROM new_competences_personne_prestation
			WHERE new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND Id_Personne=".$_SESSION["Id_Personne"]."
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
			AND Id_Personne IN (
				SELECT new_competences_personne_metier.Id_Personne 
				FROM new_competences_personne_metier
				WHERE Futur=0
				AND Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Metier=85)
			)
		";
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
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.",".$IdPosteDirection.") 
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
					Designation,
					tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
					tools_typemateriel.Libelle AS TYPEMATERIEL,
					tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
					tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
					CommentaireT AS Remarque,
					DateReceptionT AS DateDerniereAffectation,
					EtatValidationT AS TransfertEC,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT) AS LIBELLE_PLATEFORME,
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT) AS Id_Plateforme,
					(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS LIBELLE_PRESTATION,
					Id_PrestationT AS Id_Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = Id_PoleT ) AS LIBELLE_POLE,
					Id_PoleT AS Id_Pole,
					(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) AS LIBELLE_LIEU,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
					Id_PersonneT AS Id_Personne,
					Id_CaisseT AS Id_Caisse,
					(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
					FROM tools_caisse WHERE Id=Id_CaisseT) AS LIBELLE_CAISSETYPE
					";
			$Requete="FROM
						tools_materiel
					LEFT JOIN
						tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
					LEFT JOIN
						tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
					LEFT JOIN
						tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
					WHERE tools_materiel.Suppr=0
						";
			
			if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"0"){$Requete.=" AND tools_famillemateriel.Id_TypeMateriel = ".$_SESSION['FiltreToolsSuivi_TypeMateriel']." ";}
			if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";}
			if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"-1"){if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";}}
			else{if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = 0 ";}}
			if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
			if($_SESSION['FiltreToolsSuivi_NumFicheImmo']<>""){$Requete.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsSuivi_NumFicheImmo']."%' ";}
			if($_SESSION['FiltreToolsSuivi_Designation']<>""){$Requete.=" AND Designation LIKE '%".$_SESSION['FiltreToolsSuivi_Designation']."%' ";}
			if($_SESSION['FiltreToolsSuivi_BonCommande']<>""){$Requete.=" AND BonCommande LIKE '%".$_SESSION['FiltreToolsSuivi_BonCommande']."%' ";}
				
			$Requete.=" 
					 ";
				
				if($_SESSION['FiltreToolsSuivi_Num']<>""){
					$Requete.=" AND (IF(tools_famillemateriel.Id_TypeMateriel=".$TypeTelephone.",NumTelephone,
						IF(tools_famillemateriel.Id_TypeMateriel=".$TypeClef.",NumClef,
							IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMaqueDeControle.",NumMC,
								IF(tools_famillemateriel.Id_TypeMateriel=".$TypeInformatique.",NumPC,
									IF(tools_famillemateriel.Id_TypeMateriel=".$TypeVehicule.",Immatriculation,
										IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMacaron.",ImmatriculationAssociee,'')
									)
								)
							)
						)
					)
						LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
						OR 
						SN LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
						)";
				}
				if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$Requete.=" AND Id_CaisseT = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
				if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsSuivi_Personne']." ";
				}
				if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0")
				{
					$Requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";
				}
				if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
				{
					$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
					if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
					if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$Requete.=" AND Id_LieuT = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
				}

				if($_SESSION['FiltreToolsSuivi_DateAffectation']<>""){$Requete.=" AND DateReceptionT ".$_SESSION['FiltreToolsSuivi_TypeDateAffectation']." '".$_SESSION['FiltreToolsSuivi_DateAffectation']."' ";}
				
				if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))  || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || $NbIQ>0){
				
				}
				else{
					$Requete.=" AND Id_PersonneT = ".$IdPersonneConnectee." ";
				}
				if($_SESSION['FiltreToolsSuivi_Remarque']<>""){$Requete.=" AND (CommentaireT LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\" 
				OR Remarques LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\"
				OR NumSIM LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\"
				OR NumIMEI LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\"
				)";}
				
				//PARTIE CAISSE DE LA REQUETE
				$Requete2Caisse="UNION ALL
					SELECT 
						tools_caisse.Id AS ID,
						'Caisse' AS TYPESELECT,
						NumAAA AS NumAAA,
						NumFicheImmo,
						SN AS SN,
						Num AS Num,
						'' AS Designation,
						-1 AS Id_TYPEMATERIEL,
						'Caisse' AS TYPEMATERIEL,
						tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
						CommentaireT AS Remarque,
						DateReceptionT AS DateDerniereAffectation,
						EtatValidationT AS TransfertEC,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS LIBELLE_PLATEFORME,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS Id_Plateforme,
						(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) AS LIBELLE_PRESTATION,
						Id_PrestationT AS Id_Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = Id_PoleT ) AS LIBELLE_POLE,
						Id_PoleT AS Id_Pole,
						(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) AS LIBELLE_LIEU,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
						Id_PersonneT AS Id_Personne,
						tools_caisse.Id AS Id_Caisse,
						(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE TAB_CAISSE.Id_CaisseType=tools_caissetype.Id)
						FROM tools_caisse AS TAB_CAISSE WHERE TAB_CAISSE.Id=tools_caisse.Id) AS LIBELLE_CAISSETYPE
					";
				$RequeteCaisse="FROM
					tools_caisse
					LEFT JOIN tools_famillemateriel ON tools_famillemateriel.Id=tools_caisse.Id_FamilleMateriel
				WHERE 
					tools_caisse.Suppr=0 ";
				if($_SESSION['FiltreToolsSuivi_TypeMateriel']>0){$RequeteCaisse.=" AND tools_caisse.Id=0 ";}
				if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){$RequeteCaisse.=" AND Id_FamilleMateriel=".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";}
				if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"-1"){if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND tools_caisse.Id=0 ";}}
				else{if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id_CaisseType=".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";}}
				if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$RequeteCaisse.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
				if($_SESSION['FiltreToolsSuivi_Designation']<>""){$RequeteCaisse.=" AND tools_caisse.Id=0 ";}
				if($_SESSION['FiltreToolsSuivi_NumFicheImmo']<>""){$RequeteCaisse.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsSuivi_NumFicheImmo']."%' ";}
				if($_SESSION['FiltreToolsSuivi_BonCommande']<>""){$RequeteCaisse.=" AND BonCommande LIKE '%".$_SESSION['FiltreToolsSuivi_BonCommande']."%' ";}
				$RequeteCaisse.=" ";
				
				if($_SESSION['FiltreToolsSuivi_Num']<>""){$RequeteCaisse.=" AND (SN LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' OR Num LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%')";}
				if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0"){$RequeteCaisse.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT ) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";}
				if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
				{
					$RequeteCaisse.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
					if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$RequeteCaisse.=" AND Id_PoleT = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
					if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$RequeteCaisse.=" AND Id_LieuT = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
				}
				if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$RequeteCaisse.=" AND tools_caisse.Id = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
				if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$RequeteCaisse.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsSuivi_Personne']." ";}
				if($_SESSION['FiltreToolsSuivi_DateAffectation']<>""){$RequeteCaisse.=" AND DateReceptionT ".$_SESSION['FiltreToolsSuivi_TypeDateAffectation']." '".$_SESSION['FiltreToolsSuivi_DateAffectation']."' ";}

				if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))  || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || $NbIQ>0){
				}
				else{
					$RequeteCaisse.=" AND Id_PersonneT = ".$IdPersonneConnectee." ";
				}
				if($_SESSION['FiltreToolsSuivi_Remarque']<>""){$RequeteCaisse.=" AND (CommentaireT LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\" 
				)";}
				
				
				$reqAnalyse="

				SELECT 
					tools_materiel.Id AS ID
					";
				
				$reqAnalyseCaisse="UNION ALL
					SELECT 
						tools_caisse.Id AS ID
					";
				$result=mysqli_query($bdd,$reqAnalyse.$Requete.$reqAnalyseCaisse.$RequeteCaisse);
				$nbResulta=mysqli_num_rows($result);
				$nombreDePages=ceil($nbResulta/$_SESSION['NbLigne_ToolsChangement']);
						
				$requeteOrder="";
				if($_SESSION['TriToolsSuivi_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsSuivi_General'],0,-1);}
				
				if(isset($_GET['Page'])){$page=$_GET['Page'];}
				else{$page=0;}
				$_SESSION['Page_ToolsChangement']=$page;
				
				$requeteLimite=" LIMIT ".($page*$_SESSION['NbLigne_ToolsChangement']).",".$_SESSION['NbLigne_ToolsChangement']."";

				$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder.$requeteLimite);
				
				$NbEnreg=mysqli_num_rows($Result);	
		?>
		<tr>
			<td>
				<table width="100%">
					<tr>
					<td align="center" width="85%" style="font-size:18px;">
						<?php
							$nbPage=0;
							if($_SESSION['Page_ToolsChangement']>1){
								echo "<b> <a style='color:#00599f;' href='Liste_Materiel.php?Page=0'><<</a> </b>";
							}
							$valeurDepart=1;
							if($_SESSION['Page_ToolsChangement']<=5){
								$valeurDepart=1;
							}
							elseif($_SESSION['Page_ToolsChangement']>=($nombreDePages-6)){
								$valeurDepart=$nombreDePages-6;
							}
							else{
								$valeurDepart=$_SESSION['Page_ToolsChangement']-5;
							}
							for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
								if($i<=$nombreDePages){
									if($i==($_SESSION['Page_ToolsChangement']+1)){
										echo "<b> [ ".$i." ] </b>"; 
									}	
									else{
										echo "<b> <a style='color:#00599f;' href='Liste_Materiel.php?Page=".($i-1)."'>".$i."</a> </b>";
									}
								}
							}
							if($_SESSION['Page_ToolsChangement']<($nombreDePages-1)){
								echo "<b> <a style='color:#00599f;' href='Liste_Materiel.php?Page=".($nombreDePages-1)."'>>></a> </b>";
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
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=NumAAA"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?><?php if($_SESSION['TriToolsSuivi_NumAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_NumAAA']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=SN"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?><?php if($_SESSION['TriToolsSuivi_SN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_SN']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=TYPEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?><?php if($_SESSION['TriToolsSuivi_TypeMateriel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_TypeMateriel']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=FAMILLEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Famille";}else{echo "Family";}?><?php if($_SESSION['TriToolsSuivi_FamilleMateriel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_FamilleMateriel']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=LIBELLE_MODELEMATERIEL"><?php if($LangueAffichage=="FR"){echo "Modèle";}else{echo "Material";}?><?php if($_SESSION['TriToolsSuivi_LIBELLE_MODELEMATERIEL']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_LIBELLE_MODELEMATERIEL']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=Designation"><?php if($LangueAffichage=="FR"){echo "Désignation";}else{echo "Designation";}?><?php if($_SESSION['TriToolsSuivi_Designation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_Designation']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=Num"><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?><?php if($_SESSION['TriToolsSuivi_Num']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_Num']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=LIBELLE_PLATEFORME"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?><?php if($_SESSION['TriToolsSuivi_LIBELLE_PLATEFORME']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_LIBELLE_PLATEFORME']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=LIBELLE_PRESTATION"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?><?php if($_SESSION['TriToolsSuivi_LIBELLE_PRESTATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_LIBELLE_PRESTATION']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=LIBELLE_LIEU"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?><?php if($_SESSION['TriToolsSuivi_LIBELLE_LIEU']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_LIBELLE_LIEU']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=LIBELLE_CAISSETYPE"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?><?php if($_SESSION['TriToolsSuivi_LIBELLE_CAISSETYPE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_LIBELLE_CAISSETYPE']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=NOMPRENOM_PERSONNE"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriToolsSuivi_NOMPRENOM_PERSONNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_NOMPRENOM_PERSONNE']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Materiel.php?Tri=DateDerniereAffectation"><?php if($LangueAffichage=="FR"){echo "Date d'affectation";}else{echo "Date of assignment";}?><?php if($_SESSION['TriToolsSuivi_DateDerniereAffectation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriToolsSuivi_DateDerniereAffectation']=="ASC"){echo "&darr;";}?></a></td>
									<td class="EnTeteTableauCompetences"  ></td>
									<td class="EnTeteTableauCompetences" colspan="5">
									<?php if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){ ?>
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
									
										if(DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteControleGestion)))
										{
											if($Row['TYPESELECT']=="Outils")
											{
									?>
												<td <?php if($NbEnregTransfertEC>0){echo "style='border:dotted #22b63d 5px' id='leHover'";} ?>><?php echo $transfert;?><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif('Modif','<?php echo $Row['ID']; ?>');"><?php echo $Row['NumAAA'];?></a></td>
									<?php 			
											}
											else
											{
									?>
												<td <?php if($NbEnregTransfertEC>0){echo "style='border:dotted #22b63d 5px' id='leHover'";} ?>><?php echo $transfert;?><a style="color:#3e65fa;" href="javascript:OuvreFenetreModifCaisse('Modif','<?php echo $Row['ID']; ?>');"><?php echo $Row['NumAAA'];?></a></td>
									<?php 			
											}
										}
										else
										{
									?>
											<td <?php if($NbEnregTransfertEC>0){echo "style='border:dotted #22b63d 5px' id='leHover'";} ?>><?php echo $transfert;?><?php echo $Row['NumAAA'];?></td>
									<?php 		
										}
									?>
									<td><?php echo $Row['SN'];?></td>
									<td><?php echo stripslashes($Row['TYPEMATERIEL']);?></td>
									<td><?php echo stripslashes($Row['FAMILLEMATERIEL']);?></td>
									<td><?php echo stripslashes($Row['LIBELLE_MODELEMATERIEL']);?></td>
									<td><?php echo stripslashes($Row['Designation']);?></td>
									<td><?php echo stripslashes($Row['Num']);?></td>
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
									if(DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique)))
									{
										if($Row['TYPESELECT']=="Outils")
										{
									?>
											<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreTransfert('<?php echo $Row['ID']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a></td>
											<td width="20"><?php if($NbEnregTransfertEC==0){echo "<input type='checkbox' class='checkOutils' name='Id_Outils[]' Id='checkOutils_".$Row['ID']."_".$Row['TYPESELECT']."' value='".$Row['ID']."' onchange='couleurLigne(\"".$valeur."\");' >";} ?></td>
											<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreSAV('0','<?php echo $Row['ID']; ?>');" ><img src="../../Images/SAV.png" width="30px" title="<?php if($LangueAffichage=="FR"){echo "SAV";}else{echo "SAV";} ?>" border="0"></a></td>
											<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetrePerte('0','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Recherche.png" title="<?php if($LangueAffichage=="FR"){echo "Perte/Vol";}else{echo "Loss/Theft";} ?>" width="20px" border="0"></a></td>
											<td width="20"><a style="text-decoration:none;" href="javascript:if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $Row['ID']; ?>');}" ><img src="../../Images/Suppression.gif" width="20px" border="0"></a></td>
									<?php 			
										}
										else
										{
									?>
											<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertCaisse('<?php echo $Row['ID']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a></td>
											<td width="20"></td>
											<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetreSAV('1','<?php echo $Row['ID']; ?>');" ><img src="../../Images/SAV.png" width="30px" title="<?php if($LangueAffichage=="FR"){echo "SAV";}else{echo "SAV";} ?>" border="0"></a></td>
											<td width="20"><a style="text-decoration:none;" href="javascript:OuvreFenetrePerte('1','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Recherche.png" title="<?php if($LangueAffichage=="FR"){echo "Perte/Vol";}else{echo "Loss/Theft";} ?>" width="20px" border="0"></a></td>
											<td width="20"><a style="text-decoration:none;" href="javascript:if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModifCaisse('Suppr','<?php echo $Row['ID']; ?>');}" ><img src="../../Images/Suppression.gif" width="20px" border="0"></a></td>
									<?php 			
										}
									}
									else
									{
								?>
									<td>
										<?php 
											if(DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole']) || DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteResponsablePlateforme))){
												if($Row['TYPESELECT']=="Outils")
												{
												?>
												<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfert('<?php echo $Row['ID']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a>
												<?php 
												}
												else{
												?>
												<a style="text-decoration:none;" href="javascript:OuvreFenetreTransfertCaisse('<?php echo $Row['ID']; ?>');" ><img src="../../Images/RH/Transfert.png" title="<?php if($LangueAffichage=="FR"){echo "Transfert";}else{echo "Transfer";} ?>" width="18px" border="0"></a>
												<?php 
												}
											}
										?>
									</td>
									<td>
										<?php 
											if(DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole']) || DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteResponsablePlateforme))){
												if($Row['TYPESELECT']=="Outils")
												{
										?>
													<?php if($NbEnregTransfertEC==0){echo "<input type='checkbox' class='checkOutils' name='Id_Outils[]' Id='checkOutils_".$Row['ID']."_".$Row['TYPESELECT']."' value='".$Row['ID']."' onchange='couleurLigne(\"".$valeur."\");' >";} ?>
										<?php
												}
											}
										?>
									</td>
									<td></td>
									<td>
									<?php 
											if(DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation),$Row['Id_Prestation'],$Row['Id_Pole']) || DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteResponsablePlateforme))){
												if($Row['TYPESELECT']=="Outils")
												{
												?>
												<a style="text-decoration:none;" href="javascript:OuvreFenetrePerte('0','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Recherche.png" title="<?php if($LangueAffichage=="FR"){echo "Perte/Vol";}else{echo "Loss/Theft";} ?>" width="20px" border="0"></a>
												<?php 
												}
												else{
												?>
												<a style="text-decoration:none;" href="javascript:OuvreFenetrePerte('1','<?php echo $Row['ID']; ?>');" ><img src="../../Images/Recherche.png" title="<?php if($LangueAffichage=="FR"){echo "Perte/Vol";}else{echo "Loss/Theft";} ?>" width="20px" border="0"></a>
												<?php 
												}
											}
										?>
									</td>
									<td></td>
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
		<tr><td height="15"></td></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
					<td width="15%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Number of lines per page ";}else{echo "Nombre de ligne par page ";}?></td>
					<td width="60%">
						<input type="text" onKeyUp="nombre(this)" id="nbLigne" name="nbLigne" size="10" value="<?php echo $_SESSION['NbLigne_ToolsChangement'];?>"/>
						<input class="Bouton"  name="BtnNbLigne" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";}?>">
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			</table>
		</td></tr>
		<tr>
			<td height="150px"></td>
		</tr>
	<?php 
	}
	?>
</table>
</form>
<?php

mysqli_close($bdd);					// Fermeture de la connexion
if($_SESSION['Id_Personne']==1351){echo date("H:i:s");}
?>

</body>
</html>