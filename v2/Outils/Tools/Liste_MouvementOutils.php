<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreHistorique(Type,Id){
		var w=window.open("HistoriqueMateriel.php?Page=Materiel&Type="+Type+"&Id="+Id,"PageToolsHistorique","status=no,menubar=no,width=1300,height=650");
		w.focus();
	}
	function OuvreFenetreModif(Id)
		{var w=window.open("Suppr_MouvementOutils.php?Id="+Id,"PageMouvement","status=no,menubar=no,width=50,height=50");
		w.focus();
		}
	function OuvreFenetreRefus(){
		var elements = document.getElementsByClassName("checkR");
		Id_Outils="";
		for(var i=0, l=elements.length; i<l; i++){
			if(elements[i].checked ==true){
				Id_Outils=Id_Outils+elements[i].value+",";
			}
		}
		if(Id_Outils!=""){
			Id_Outils=Id_Outils.substring(0,Id_Outils.length-1);
			var w=window.open("Refuser_MouvementOutilsMasse.php?Page=Materiel&Ids="+Id_Outils,"PageToolsTransfert","status=no,menubar=no,width=850,height=500");
			w.focus();
		}			
	}
	function CocherValide(){
		if(document.getElementById('check_Valide').checked==true){
			var elements = document.getElementsByClassName('check');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('check');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
	function CocherRefus(){
		if(document.getElementById('check_Refuse').checked==true){
			var elements = document.getElementsByClassName('checkR');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('checkR');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
	function CocherPriseEnCompte(){
		if(document.getElementById('check_PriseEnCompte').checked==true){
			var elements = document.getElementsByClassName('checkPriseEnCompte');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('checkPriseEnCompte');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
</script>
<?php
$date7=date("Y-m-d",strtotime(date("Y-m-d")." -7 day"));

//Interface qui ne concerne que les managers
?>

<form class="test" action="Liste_MouvementOutils.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#66e27d;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des mouvements des outils";}else{echo "List of tool movements";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
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
									DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM new_rh_etatcivil
								
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
			</tr>
			<tr>
				<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° AAA :";}else{echo "N° AAA :";} ?></td>
				<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "N° ou S/N :";}else{echo "N° ou S/N :";} ?></td>
				<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de matériel :";}else{echo "Kind of material :";} ?></td>
				<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Famille de matériel :";}else{echo "Family of material :";} ?></td>
				<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Modèle de matériel :";}else{echo "Model of material :";} ?></td>
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
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
		</table>
	</td></tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4" style="background-color:#22b53d;font-weight:bold;color:#000000;font-size:12px;height:20px;border-radius:25px 25px 25px 25px;">
						&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Arrivées en cours";}else{echo "Current arrivals";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete=" SELECT Id,DateReception,Type,Id_Materiel__Id_Caisse,Id_Caisse,
				(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
				(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=Id_Caisse) AS Caisse,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,Id_Prestation,Id_Pole,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
				IF(Type=0,(SELECT NumAAA FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT NumAAA FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) AS NumAAA,
				IF(Type=0,(SELECT SN FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT SN FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) AS SN,
				IF(Type=0,(SELECT (SELECT Libelle FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT (SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Modele,
				IF(Type=0,(SELECT (SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Famille,
				IF(Type=0,(SELECT (SELECT (SELECT (SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),'Caisse') AS TypeMateriel,
				IF(Type=0,(SELECT (SELECT (SELECT 
				IF(Id_TypeMateriel=".$TypeTelephone.",tools_materiel.NumTelephone,
					IF(Id_TypeMateriel=".$TypeClef.",tools_materiel.NumClef,
						IF(Id_TypeMateriel=".$TypeMaqueDeControle.",tools_materiel.NumMC,
							IF(Id_TypeMateriel=".$TypeInformatique.",tools_materiel.NumPC,
								IF(Id_TypeMateriel=".$TypeVehicule.",tools_materiel.Immatriculation,
									IF(Id_TypeMateriel=".$TypeMacaron.",tools_materiel.ImmatriculationAssociee,'')
								)
							)
						)
					)
				) 
			FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT Num FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Num
		";
		$requete.=" FROM tools_mouvement
					WHERE EtatValidation=0
					AND tools_mouvement.TypeMouvement=0
					AND Suppr=0 
					AND (
						IF(Type=1,CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole),
							IF(Id_Caisse=0,CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole),
								(SELECT CONCAT(TAB_Mouvement.Id_Prestation,'_',TAB_Mouvement.Id_Pole) 
								FROM tools_mouvement AS TAB_Mouvement
								WHERE TAB_Mouvement.EtatValidation<>-1 
								AND TAB_Mouvement.TypeMouvement=0 
								AND TAB_Mouvement.Suppr=0 
								AND TAB_Mouvement.Type=1 
								AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								ORDER BY DateReception DESC, Id DESC 
								LIMIT 1
								)
							)
						) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						) 
						
						OR 
						
						IF(Type=1,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
							IF(Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
								(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation) 
								FROM tools_mouvement AS TAB_Mouvement
								WHERE TAB_Mouvement.EtatValidation<>-1 
								AND TAB_Mouvement.TypeMouvement=0 
								AND TAB_Mouvement.Suppr=0 
								AND TAB_Mouvement.Type=1 
								AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								ORDER BY DateReception DESC, Id DESC 
								LIMIT 1
								)
							)
						) IN 
						(SELECT Id_Plateforme
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") 
						)
					)
					";
		if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0"){$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = tools_mouvement.Id_Prestation ) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";}
		if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
		{
			$requete.=" AND Id_Prestation = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
			if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$requete.=" AND tools_mouvement.Id_Pole = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
			if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$requete.=" AND tools_mouvement.Id_Lieu = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
		}
		if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$requete.=" AND Id_Caisse = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
		if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$requete.=" AND Id_Personne = ".$_SESSION['FiltreToolsSuivi_Personne']." ";}
		if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$requete.=" AND IF(Type=0,(SELECT NumAAA FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT NumAAA FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
		if($_SESSION['FiltreToolsSuivi_Num']<>""){
			$requete.=" AND (IF(Type=0,(SELECT (SELECT (SELECT 
							IF(Id_TypeMateriel=".$TypeTelephone.",tools_materiel.NumTelephone,
								IF(Id_TypeMateriel=".$TypeClef.",tools_materiel.NumClef,
									IF(Id_TypeMateriel=".$TypeMaqueDeControle.",tools_materiel.NumMC,
										IF(Id_TypeMateriel=".$TypeInformatique.",tools_materiel.NumPC,
											IF(Id_TypeMateriel=".$TypeVehicule.",tools_materiel.Immatriculation,
												IF(Id_TypeMateriel=".$TypeMacaron.",tools_materiel.ImmatriculationAssociee,'')
											)
										)
									)
								)
							) 
						FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT Num FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)
						)
						LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
						OR 
						IF(Type=0,(SELECT SN FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT SN FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
						) ";
		}
		
		if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"0" && $_SESSION['FiltreToolsSuivi_TypeMateriel']<>"-1"){
			$requete.=" AND Type=0 
						AND (SELECT (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_TypeMateriel']." ";
			if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){
				$requete.=" AND (SELECT (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";
			}
			if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){
				$requete.=" AND (SELECT Id_ModeleMateriel FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";
			}
		}
		elseif($_SESSION['FiltreToolsSuivi_TypeMateriel']=="-1"){
			$requete.=" AND Type=1 
						AND (SELECT Id FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)>0 ";
			if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){
				$requete.=" AND (SELECT Id_FamilleMateriel FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";
			}
			if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){
				$requete.=" AND (SELECT Id_CaisseType FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";
			}
			
		}

		$requete.=" ORDER BY DateReception ASC";

		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="2%"></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N°AAA";}else{echo "N°AAA";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "S/N";}else{echo "S/N";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Famille";}else{echo "Family";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Modèle";}else{echo "Material";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N°";}else{echo "N°";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Donneur d'ordre";}else{echo "Customer";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Provenance";}else{echo "Origin";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Destination";}else{echo "Destination";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date transfert";}else{echo "Transfer date";} ?></td>
					<td class='EnTeteTableauCompetences' width="8%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"><br>
						<input type='checkbox' id="check_Valide" name="check_Valide" value="" onchange="CocherValide()">
					</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">
						<input type="button" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" onclick="OuvreFenetreRefus();" name="refuserSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?>"><br>
						<input type='checkbox' id="check_Refuse" name="check_Refuse" value="" onchange="CocherRefus()">
					</td>
				</tr>
	<?php
			if(isset($_POST['validerSelection'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['check_'.$row['Id'].''])){
						$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne(date('Y-m-d'),$IdPersonneConnectee));
						$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
						$IdPolePersonneConnectee=0;
						if($IdPrestationPersonneConnectee>0){
							$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
						}
	
						$requeteUpdate="UPDATE tools_mouvement SET 
								Id_Recepteur=".$_SESSION['Id_Personne'].",
								Id_PrestationRecepteur=".$IdPrestationPersonneConnectee.",
								Id_PoleRecepteur=".$IdPolePersonneConnectee.",
								EtatValidation=1,
								DateReception='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";

						$resultat=mysqli_query($bdd,$requeteUpdate);
						
						//Mettre à jour matériel/caisse
						$req="SELECT Id_Materiel__Id_Caisse, Type FROM tools_mouvement WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($resultat);
						if($nbResulta>0){
							$Row2=mysqli_fetch_array($resultat);
							if($Row2['Type']==0){
								$req="UPDATE tools_materiel
									SET
									EtatValidationT=1,
									DateReceptionT='".date('Y-m-d')."'
								WHERE Id=".$Row2['Id_Materiel__Id_Caisse'];
								$resultat=mysqli_query($bdd,$req);
							}
							else{
								$req="UPDATE tools_caisse
								SET
								EtatValidationT=1,
								DateReceptionT='".date('Y-m-d')."'
								WHERE Id=".$Row2['Id_Materiel__Id_Caisse'];
								$resultat=mysqli_query($bdd,$req);
							}
						}
					}
				}
			}
			$result=mysqli_query($bdd,$requete);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$requete=" SELECT Id,DateReception,Id_Caisse,
						(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
						(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=Id_Caisse) AS Caisse,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
						FROM tools_mouvement
						WHERE EtatValidation=1
						AND TypeMouvement=0
						AND Type=".$row['Type']."
						AND Id_Materiel__Id_Caisse=".$row['Id_Materiel__Id_Caisse']."
						AND Suppr=0 
						AND Id<>".$row['Id']."
						ORDER BY DateReception DESC, Id DESC
						";

					$resultProvenance=mysqli_query($bdd,$requete);
					$nbResultaProvenance=mysqli_num_rows($resultProvenance);
					$Provenance="";
					$Id_PlateformeProvenance=0;
					if($nbResultaProvenance>0){
						$rowProvenance=mysqli_fetch_array($resultProvenance);
						if($row['Id_Plateforme']<>$rowProvenance['Id_Plateforme']){$Provenance=$rowProvenance['Plateforme']."<br>";}
						if($rowProvenance['Prestation']<>""){$Provenance.=$rowProvenance['Prestation'];}
						if($rowProvenance['Pole']<>""){$Provenance.=" - ".$rowProvenance['Pole'];}
						if($rowProvenance['Lieu']<>""){$Provenance.=" - ".$rowProvenance['Lieu'];}
						if($rowProvenance['Caisse']<>""){$Provenance.="<br>".$rowProvenance['Caisse'];}
						if($rowProvenance['Personne']<>""){$Provenance.="<br>".$rowProvenance['Personne'];}
						
						if($rowProvenance['Id_Caisse']>0){
							$Provenance="";
							$requete=" SELECT Id,DateReception,
								(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
								(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
								FROM tools_mouvement
								WHERE EtatValidation=1
								AND TypeMouvement=0
								AND Type=1
								AND Id_Materiel__Id_Caisse=".$rowProvenance['Id_Caisse']."
								AND Suppr=0 
								ORDER BY DateReception DESC, Id DESC
								";

							$resultCaisse=mysqli_query($bdd,$requete);
							$nbResultaCaisse=mysqli_num_rows($resultCaisse);
							if($nbResultaCaisse>0){
								$rowCaisse=mysqli_fetch_array($resultCaisse);
								if($row['Id_Plateforme']<>$rowCaisse['Id_Plateforme']){$Provenance=$rowCaisse['Plateforme']."<br>";}
								if($rowCaisse['Prestation']<>""){$Provenance.=$rowCaisse['Prestation'];}
								if($rowCaisse['Pole']<>""){$Provenance.=" - ".$rowCaisse['Pole'];}
								if($rowCaisse['Lieu']<>""){$Provenance.=" - ".$rowCaisse['Lieu'];}
								if($rowProvenance['Caisse']<>""){$Provenance.="<br>".$rowProvenance['Caisse'];}
								if($rowCaisse['Personne']<>""){$Provenance.="<br>".$rowCaisse['Personne'];}

							}
						}
					}
					
					$destination="";
					if($row['Id_Plateforme']<>$Id_PlateformeProvenance){$destination=$row['Plateforme']."<br>";}
					if($row['Prestation']<>""){$destination.=$row['Prestation'];}
					if($row['Pole']<>""){$destination.=" - ".$row['Pole'];}
					if($row['Lieu']<>""){$destination.=" - ".$row['Lieu'];}
					if($row['Caisse']<>""){$destination.="<br>".$row['Caisse'];}
					if($row['Personne']<>""){$destination.="<br>".$row['Personne'];}
					
					$Id_Prestation=$row['Id_Prestation'];
					$Id_Pole=$row['Id_Pole'];
					
					if($row['Id_Caisse']>0){
						$destination="";
						$requete=" SELECT Id,DateReception,
							(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
							(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
							(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,Id_Prestation,
							(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,Id_Pole
							FROM tools_mouvement
							WHERE EtatValidation=1
							AND TypeMouvement=0
							AND Type=1
							AND Id_Materiel__Id_Caisse=".$row['Id_Caisse']."
							AND Suppr=0 
							ORDER BY DateReception DESC, Id DESC
							";

						$resultCaisseD=mysqli_query($bdd,$requete);
						$nbResultaCaisseD=mysqli_num_rows($resultCaisseD);
						if($nbResultaCaisseD>0){
							$rowCaisseD=mysqli_fetch_array($resultCaisseD);
							if($rowCaisseD['Id_Plateforme']<>$Id_PlateformeProvenance){$destination=$rowCaisseD['Plateforme']."<br>";}
							if($rowCaisseD['Prestation']<>""){$destination.=$rowCaisseD['Prestation'];}
							if($rowCaisseD['Pole']<>""){$destination.=" - ".$rowCaisseD['Pole'];}
							if($rowCaisseD['Lieu']<>""){$destination.=" - ".$rowCaisseD['Lieu'];}
							if($row['Caisse']<>""){$destination.="<br>".$row['Caisse'];}
							if($rowCaisseD['Personne']<>""){$destination.="<br>".$rowCaisseD['Personne'];}
							
							$Id_Prestation=$rowCaisseD['Id_Prestation'];
							$Id_Pole=$rowCaisseD['Id_Pole'];

						}
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td>
							<a style="text-decoration:none;" href="javascript:OuvreFenetreHistorique('<?php echo $row['Type']; ?>','<?php echo $row['Id_Materiel__Id_Caisse']; ?>');" ><img src="../../Images/Livre.png" width="18px" border="0"></a>
						</td>
						<td align="center"><?php echo stripslashes($row['NumAAA']);?></td>
						<td><?php echo stripslashes($row['SN']);?></td>
						<td><?php echo stripslashes($row['TypeMateriel']);?></td>
						<td><?php echo stripslashes($row['Famille']);?></td>
						<td><?php echo stripslashes($row['Modele']);?></td>
						<td><?php echo stripslashes($row['Num']);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo $Provenance;?></td>
						<td><?php echo $destination;?></td>
						<td><?php 
						if($row['DateReception']<=$date7){echo "<span class='blink_me'><img width='15px' src='../../Images/attention.png' border='0' /></span>";}
						echo AfficheDateJJ_MM_AAAA($row['DateReception']);
						?></td>
						<td align="center">
							<?php 
								$visible=1;
								
								//Vérifier si prestation ayant le lieu "Magasin" ou "Magasin Paris" ou "Magasin Toulouse"
								$req="SELECT Id 
									FROM tools_lieu 
									WHERE Libelle LIKE 'Magasin%'
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole." ";
								$ResultLieu=mysqli_query($bdd,$req);
								$NbLieu=mysqli_num_rows($ResultLieu);
								
								if(DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet),$Id_Prestation,$Id_Pole)==0
									&& 
									(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))==0
									|| 
									(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))>0 && $NbLieu==0)
									)
								){
									$visible=0;
								}
								if($visible==1){
									echo "<input class='check' type='checkbox' name='check_".$row['Id']."' value=''>";
								}
							?>
						</td>
						<td align="center">
							<?php
							if($visible==1){
								echo "<input class='checkR' type='checkbox' name='checkR_".$row['Id']."' value='".$row['Id']."'>";
							}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4" style="background-color:#22b53d;font-weight:bold;color:#000000;font-size:12px;height:20px;border-radius:25px 25px 25px 25px;">
						&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Départs en cours";}else{echo "Departures in progress";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete=" SELECT Id,DateReception,Type,Id_Materiel__Id_Caisse,Id_Caisse,EtatValidation,CommentaireRefus,
				(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
				(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=Id_Caisse) AS Caisse,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
				IF(Type=0,(SELECT NumAAA FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT NumAAA FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) AS NumAAA,
				IF(Type=0,(SELECT SN FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT SN FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) AS SN,
				IF(Type=0,(SELECT (SELECT Libelle FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT (SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Modele,
				IF(Type=0,(SELECT (SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Famille,
				IF(Type=0,(SELECT (SELECT (SELECT (SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),'Caisse') AS TypeMateriel,
				IF(Type=0,(SELECT (SELECT (SELECT 
				IF(Id_TypeMateriel=".$TypeTelephone.",tools_materiel.NumTelephone,
					IF(Id_TypeMateriel=".$TypeClef.",tools_materiel.NumClef,
						IF(Id_TypeMateriel=".$TypeMaqueDeControle.",tools_materiel.NumMC,
							IF(Id_TypeMateriel=".$TypeInformatique.",tools_materiel.NumPC,
								IF(Id_TypeMateriel=".$TypeVehicule.",tools_materiel.Immatriculation,
									IF(Id_TypeMateriel=".$TypeMacaron.",tools_materiel.ImmatriculationAssociee,'')
								)
							)
						)
					)
			) 
			FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT Num FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)) AS Num
		";
		$requete.=" FROM tools_mouvement
					WHERE tools_mouvement.TypeMouvement=0
					AND DatePriseEnCompteDemandeur<='0001-01-01'
					AND Suppr=0 
					AND (
						IF(Type=1,
							(SELECT CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole)
								FROM tools_mouvement AS TAB
								WHERE EtatValidation=1
								AND TAB.TypeMouvement=0
								AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
								AND TAB.Type=1
								AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
								AND TAB.Suppr=0 
								AND TAB.Id<>tools_mouvement.Id
								ORDER BY DateReception DESC, Id DESC LIMIT 1)
						,
							(SELECT IF(TAB.Id_Caisse=0,CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole),
									(SELECT CONCAT(TABCaisse.Id_Prestation,'_',TABCaisse.Id_Pole)
									FROM tools_mouvement AS TABCaisse
									WHERE TABCaisse.EtatValidation=1
									AND TABCaisse.TypeMouvement=0
									AND TABCaisse.DatePriseEnCompteDemandeur>'0001-01-01'
									AND TABCaisse.Type=1
									AND TABCaisse.Id_Materiel__Id_Caisse=TAB.Id_Caisse
									AND TABCaisse.Suppr=0 
									ORDER BY DateReception DESC, Id DESC LIMIT 1)
								)
								FROM tools_mouvement AS TAB
								WHERE EtatValidation=1
								AND TAB.TypeMouvement=0
								AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
								AND TAB.Type=0
								AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
								AND TAB.Suppr=0 
								AND TAB.Id<>tools_mouvement.Id
								ORDER BY DateReception DESC, Id DESC LIMIT 1)
						) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						) 
						
						OR 
						
						IF(Type=1,
							(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB.Id_Prestation)
								FROM tools_mouvement AS TAB
								WHERE EtatValidation=1
								AND TAB.TypeMouvement=0
								AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
								AND TAB.Type=1
								AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
								AND TAB.Suppr=0 
								AND TAB.Id<>tools_mouvement.Id
								ORDER BY DateReception DESC, Id DESC LIMIT 1)
						,
							(SELECT IF(TAB.Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB.Id_Prestation),
									(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TABCaisse.Id_Prestation)
									FROM tools_mouvement AS TABCaisse
									WHERE TABCaisse.EtatValidation=1
									AND TABCaisse.TypeMouvement=0
									AND TABCaisse.DatePriseEnCompteDemandeur>'0001-01-01'
									AND TABCaisse.Type=1
									AND TABCaisse.Id_Materiel__Id_Caisse=TAB.Id_Caisse
									AND TABCaisse.Suppr=0 
									ORDER BY DateReception DESC, Id DESC LIMIT 1)
								)
								FROM tools_mouvement AS TAB
								WHERE EtatValidation=1
								AND TAB.TypeMouvement=0
								AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
								AND TAB.Type=0
								AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
								AND TAB.Suppr=0 
								AND TAB.Id<>tools_mouvement.Id
								ORDER BY DateReception DESC, Id DESC LIMIT 1)
						)
						IN 
						(SELECT Id_Plateforme
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") 
						)
					)
					";
		if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0"){$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = tools_mouvement.Id_Prestation ) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";}
		if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
		{
			$requete.=" AND Id_Prestation = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
			if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$requete.=" AND tools_mouvement.Id_Pole = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
			if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$requete.=" AND tools_mouvement.Id_Lieu = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
		}
		if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$requete.=" AND Id_Caisse = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
		if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$requete.=" AND Id_Personne = ".$_SESSION['FiltreToolsSuivi_Personne']." ";}
		if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$requete.=" AND IF(Type=0,(SELECT NumAAA FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT NumAAA FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
		if($_SESSION['FiltreToolsSuivi_Num']<>""){
			$requete.=" AND (IF(Type=0,(SELECT (SELECT (SELECT 
							IF(Id_TypeMateriel=".$TypeTelephone.",tools_materiel.NumTelephone,
								IF(Id_TypeMateriel=".$TypeClef.",tools_materiel.NumClef,
									IF(Id_TypeMateriel=".$TypeMaqueDeControle.",tools_materiel.NumMC,
										IF(Id_TypeMateriel=".$TypeInformatique.",tools_materiel.NumPC,
											IF(Id_TypeMateriel=".$TypeVehicule.",tools_materiel.Immatriculation,
												IF(Id_TypeMateriel=".$TypeMacaron.",tools_materiel.ImmatriculationAssociee,'')
											)
										)
									)
								)
							) 
						FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse),(SELECT Num FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse)
						)
						LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
						OR 
						IF(Type=0,(SELECT SN FROM tools_materiel WHERE Id=Id_Materiel__Id_Caisse),(SELECT SN FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)) LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
						) ";
		}
		
		if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"0" && $_SESSION['FiltreToolsSuivi_TypeMateriel']<>"-1"){
			$requete.=" AND Type=0 
						AND (SELECT (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_TypeMateriel']." ";
			if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){
				$requete.=" AND (SELECT (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";
			}
			if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){
				$requete.=" AND (SELECT Id_ModeleMateriel FROM tools_materiel WHERE tools_materiel.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";
			}
		}
		elseif($_SESSION['FiltreToolsSuivi_TypeMateriel']=="-1"){
			$requete.=" AND Type=1 
						AND (SELECT Id FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse)>0 ";
			if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){
				$requete.=" AND (SELECT Id_FamilleMateriel FROM tools_caisse WHERE Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";
			}
			if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){
				$requete.=" AND (SELECT Id_CaisseType FROM tools_caisse WHERE tools_caisse.Id=Id_Materiel__Id_Caisse) = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";
			}
			
		}
		$requete.=" ORDER BY DateReception ASC";
		
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="2%"></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N°AAA";}else{echo "N°AAA";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "S/N";}else{echo "S/N";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Famille";}else{echo "Family";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Modèle";}else{echo "Material";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N°";}else{echo "N°";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Donneur d'ordre";}else{echo "Customer";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Provenance";}else{echo "Origin";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Destination";}else{echo "Destination";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date transfert";}else{echo "Transfer date";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Validation receveur";}else{echo "Validation receiver";} ?></td>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
						<input type='checkbox' id="check_PriseEnCompte" name="check_PriseEnCompte" value="" onchange="CocherPriseEnCompte()">
					</td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Annuler";}else{echo "Annuler";} ?></td>
				</tr>
	<?php
			if(isset($_POST['priseEnCompte'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkOUT_'.$row['Id'].''])){
						$requeteUpdate="UPDATE tools_mouvement SET 
								Id_DemandeurPrisEnCompte=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteDemandeur='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
			}
			
			$result=mysqli_query($bdd,$requete);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$requete=" SELECT Id,DateReception,Id_Caisse,
						(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
						(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=Id_Caisse) AS Caisse,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
						FROM tools_mouvement
						WHERE EtatValidation=1
						AND TypeMouvement=0
						AND Type=".$row['Type']."
						AND Id_Materiel__Id_Caisse=".$row['Id_Materiel__Id_Caisse']."
						AND Suppr=0 
						AND Id<>".$row['Id']."
						ORDER BY DateReception DESC, Id DESC
						";

					$resultProvenance=mysqli_query($bdd,$requete);
					$nbResultaProvenance=mysqli_num_rows($resultProvenance);
					$Provenance="";
					$Id_PlateformeProvenance=0;
					if($nbResultaProvenance>0){
						$rowProvenance=mysqli_fetch_array($resultProvenance);
						if($row['Id_Plateforme']<>$rowProvenance['Id_Plateforme']){$Provenance=$rowProvenance['Plateforme']."<br>";}
						if($rowProvenance['Prestation']<>""){$Provenance.=$rowProvenance['Prestation'];}
						if($rowProvenance['Pole']<>""){$Provenance.=" - ".$rowProvenance['Pole'];}
						if($rowProvenance['Lieu']<>""){$Provenance.=" - ".$rowProvenance['Lieu'];}
						if($rowProvenance['Caisse']<>""){$Provenance.="<br>".$rowProvenance['Caisse'];}
						if($rowProvenance['Personne']<>""){$Provenance.="<br>".$rowProvenance['Personne'];}
						
						if($rowProvenance['Id_Caisse']>0){
							$requete=" SELECT Id,DateReception,
								(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
								(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
								FROM tools_mouvement
								WHERE EtatValidation=1
								AND TypeMouvement=0
								AND Type=1
								AND Id_Materiel__Id_Caisse=".$rowProvenance['Id_Caisse']."
								AND Suppr=0 
								ORDER BY DateReception DESC, Id DESC
								";

							$resultCaisse=mysqli_query($bdd,$requete);
							$nbResultaCaisse=mysqli_num_rows($resultCaisse);
							if($nbResultaCaisse>0){
								$rowCaisse=mysqli_fetch_array($resultCaisse);
								if($row['Id_Plateforme']<>$rowCaisse['Id_Plateforme']){$Provenance=$rowCaisse['Plateforme']."<br>";}
								if($rowCaisse['Prestation']<>""){$Provenance.=$rowCaisse['Prestation'];}
								if($rowCaisse['Pole']<>""){$Provenance.=" - ".$rowCaisse['Pole'];}
								if($rowCaisse['Lieu']<>""){$Provenance.=" - ".$rowCaisse['Lieu'];}
								if($rowProvenance['Caisse']<>""){$Provenance.="<br>".$rowProvenance['Caisse'];}
								if($rowCaisse['Personne']<>""){$Provenance.="<br>".$rowCaisse['Personne'];}

							}
						}
					}
					
					$destination="";
					if($row['Id_Plateforme']<>$Id_PlateformeProvenance){$destination=$row['Plateforme']."<br>";}
					if($row['Prestation']<>""){$destination.=$row['Prestation'];}
					if($row['Pole']<>""){$destination.=" - ".$row['Pole'];}
					if($row['Lieu']<>""){$destination.=" - ".$row['Lieu'];}
					if($row['Caisse']<>""){$destination.="<br>".$row['Caisse'];}
					if($row['Personne']<>""){$destination.="<br>".$row['Personne'];}
					if($row['Id_Caisse']>0){
						$requete=" SELECT Id,DateReception,
							(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
							(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
							(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
							FROM tools_mouvement
							WHERE EtatValidation=1
							AND TypeMouvement=0
							AND Type=1
							AND Id_Materiel__Id_Caisse=".$row['Id_Caisse']."
							AND Suppr=0 
							ORDER BY DateReception DESC, Id DESC
							";

						$resultCaisseD=mysqli_query($bdd,$requete);
						$nbResultaCaisseD=mysqli_num_rows($resultCaisseD);
						if($nbResultaCaisseD>0){
							$rowCaisseD=mysqli_fetch_array($resultCaisseD);
							if($rowCaisseD['Id_Plateforme']<>$Id_PlateformeProvenance){$destination=$rowCaisseD['Plateforme']."<br>";}
							if($rowCaisseD['Prestation']<>""){$destination.=$rowCaisseD['Prestation'];}
							if($rowCaisseD['Pole']<>""){$destination.=" - ".$rowCaisseD['Pole'];}
							if($rowCaisseD['Lieu']<>""){$destination.=" - ".$rowCaisseD['Lieu'];}
							if($row['Caisse']<>""){$destination.="<br>".$row['Caisse'];}
							if($rowCaisseD['Personne']<>""){$destination.="<br>".$rowCaisseD['Personne'];}

						}
					}
					
					$Etat="";
					$couleurEtat="#ffed3b";
					$Hover="";
					if($_SESSION["Langue"]=="FR"){$Etat="En attente de validation";}
					else{$Etat="Waiting for validation";}
					if($row['EtatValidation']==1){
						if($_SESSION["Langue"]=="FR"){$Etat="Validé";}
						else{$Etat="Validated";}
						$couleurEtat="#469400";
					}
					elseif($row['EtatValidation']==-1){
						if($_SESSION["Langue"]=="FR"){$Etat="Refusé";}
						else{$Etat="Refused";}
						$couleurEtat="#e92525";
						
						$Hover=" id='leHover' ";
						$Etat.="<span>".stripslashes($row['CommentaireRefus'])."</span>";
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td>
							<a style="text-decoration:none;" href="javascript:OuvreFenetreHistorique('<?php echo $row['Type']; ?>','<?php echo $row['Id_Materiel__Id_Caisse']; ?>');" ><img src="../../Images/Livre.png" width="18px" border="0"></a>
						</td>
						<td align="center"><?php echo stripslashes($row['NumAAA']);?></td>
						<td><?php echo stripslashes($row['SN']);?></td>
						<td><?php echo stripslashes($row['TypeMateriel']);?></td>
						<td><?php echo stripslashes($row['Famille']);?></td>
						<td><?php echo stripslashes($row['Modele']);?></td>
						<td><?php echo stripslashes($row['Num']);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo $Provenance;?></td>
						<td><?php echo $destination;?></td>
						<td><?php 
						if($row['DateReception']<=$date7){echo "<span class='blink_me'><img width='15px' src='../../Images/attention.png' border='0' /></span>";}
						echo AfficheDateJJ_MM_AAAA($row['DateReception']);?></td>
						<td bgcolor="<?php echo $couleurEtat;?>" <?php echo $Hover; ?>><?php echo $Etat;?></td>
						<td align="center">
							<?php 
								if($row['EtatValidation']<>0){
									echo "<input class='checkPriseEnCompte' type='checkbox' name='checkOUT_".$row['Id']."' value=''>";
								}
							?>
						</td>
						<td align="center">
							<?php
							if($row['EtatValidation']==0){
							?>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreModif('<?php echo $row['Id']; ?>');}else{return false;}"><img style="width:20px;" src="../../Images/error.png" border="0" title="Annuler"></a>
							<?php
							}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
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