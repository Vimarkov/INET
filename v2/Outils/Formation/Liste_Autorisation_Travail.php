<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreAutorisation(Id, Mode){
		if(Mode=="EXCEL"){var w=window.open("AutorisationTravail_Excel.php?Id="+Id,"PageAT","status=no,menubar=no,scrollbars=yes,,width=60,height=40");}
		else if(Mode=="WEB"){var w=window.open("AutorisationTravail_Web.php?Id="+Id,"PageAT","status=no,menubar=no,scrollbars=yes,,width=500,height=200");}
		w.focus();
		}
	
	function OuvreMultiAutorisation() {
		var elements = document.getElementsByClassName("check");
		Id="";
		for(var i=0, l=elements.length; i<l; i++)
			if(elements[i].checked == true)
				if(Id=="")
					Id=elements[i].name;
				else
					Id+=","+elements[i].name;

		var w=window.open("MultiAutorisationTravail_Excel.php?Id="+Id,"PageAT","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
		w.focus();
	}
	
	function Excel(){
		var el = document.getElementById("etat");
		etat="";
		if (el) {
			etat=document.getElementById("etat").value;
		}
		var w=window.open("Excel_AutorisationTravail.php?Prestation="+document.getElementById('prestation').value+"&Personne="+document.getElementById('personne').value+"&Etat="+etat,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function Excel2(){
		var el = document.getElementById("etat");
		etat="";
		if (el) {
			etat=document.getElementById("etat").value;
		}
		var w=window.open("Excel_AutorisationTravailMoyen.php?Prestation="+document.getElementById('prestation').value+"&Personne="+document.getElementById('personne').value+"&Etat="+etat,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	
	function SelectionnerTout(){
		var elements = document.getElementsByClassName("check");
		if (formulaire.selectAll.checked == true)
			for(var i=0, l=elements.length; i<l; i++)
				elements[i].checked = true;
		else
			for(var i=0, l=elements.length; i<l; i++)
				elements[i].checked = false;
	}
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
</script>
<?php
$etatF="";

if($_POST)
{
	$_SESSION['FiltreAT_Prestation']=$_POST['prestation'];
	$_SESSION['FiltreAT_Personne']=$_POST['personne'];
	$_SESSION['FiltreAT_Moyen']=$_POST['moyen'];
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){$_SESSION['FiltreAT_Etat']=$_POST['etat'];}
}

if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Personne")
	{
		$_SESSION['TriAT_General']= str_replace("Personne ASC,","",$_SESSION['TriAT_General']);
		$_SESSION['TriAT_General']= str_replace("Personne DESC,","",$_SESSION['TriAT_General']);
		$_SESSION['TriAT_General']= str_replace("Personne ASC","",$_SESSION['TriAT_General']);
		$_SESSION['TriAT_General']= str_replace("Personne DESC","",$_SESSION['TriAT_General']);
		if($_SESSION['TriAT_Personne']==""){$_SESSION['TriAT_Personne']="ASC";$_SESSION['TriAT_General'].= "Personne ".$_SESSION['TriAT_Personne'].",";}
		elseif($_SESSION['TriAT_Personne']=="ASC"){$_SESSION['TriAT_Personne']="DESC";$_SESSION['TriAT_General'].= "Personne ".$_SESSION['TriAT_Personne'].",";}
		else{$_SESSION['TriAT_Personne']="";}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" id="formulaire" method="POST" action="Liste_Autorisation_Travail.php">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ff645f;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Autorisation de conduite";}else{echo "Driving authorization";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table class="TableCompetences" style="width:100%; border-spacing:0;">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation / Pôle";}else{echo "Activity / Pole";}?> : </td>
					<td width="10%">
						<input name="prestation" id="prestation" value="<?php $prestation=$_SESSION['FiltreAT_Prestation']; echo $prestation; ?>"/>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
					<td width="10%">
						<input name="personne" id="personne" value="<?php $personne=$_SESSION['FiltreAT_Personne']; echo $personne; ?>"/>
					</td>
					<td class="Libelle" width="5%"><?php if($LangueAffichage=="FR"){echo "Moyen";}else{echo "Means";}?> : </td>
					<td width="10%">
						<select name="moyen" id="moyen" style="width:150px;" onchange="submit()">
							<option value="0"></option>
							<?php
							$Moyen=$_SESSION['FiltreAT_Moyen'];
							if($_POST){$Moyen=$_POST['moyen'];}
							$resultMoyen=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_moyen WHERE Suppr=0 ORDER BY Libelle ASC");
							while($rowMoyen=mysqli_fetch_array($resultMoyen))
							{
								$selected="";
								if($Moyen<>"")
								{
									if($Moyen==$rowMoyen['Id']){$selected="selected";}
								}
								echo "<option value='".$rowMoyen['Id']."' ".$selected.">".stripslashes($rowMoyen['Libelle'])."</option>\n";
							}
							?>
						</select>
					</td>
					<?php 
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){
						$etatF=$_SESSION['FiltreAT_Etat'];
						if($_POST){$etatF=$_POST['etat'];}
						$_SESSION['FiltreAT_Etat']=$etatF;
					?>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?> : </td>
					<td width="10%">
						<select name="etat" onchange="submit()">
							<option value="" selected></option>
							<option value="A jour" <?php if($etatF=="A jour"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "A jour";}else{echo "Up to date";}?></option>
							<option value="A éditer" <?php if($etatF=="A éditer"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "A éditer";}else{echo "To edit";}?></option>
						</select>
					</td>
					<?php 
						}
					?>
					<td width="5%" rowspan="2">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					</td>
					<td width="10%">
						&nbsp;<a style="text-decoration:none;" class="Bouton" href="javascript:Excel();">
							<?php if($LangueAffichage=="FR"){echo "Extract par personne";}else{echo "Extract per person";}?>
						</a>&nbsp;<br>
						&nbsp;<a style="text-decoration:none;" class="Bouton" href="javascript:Excel2();">
							<?php if($LangueAffichage=="FR"){echo "Extract par personne / moyen";}else{echo "Extract per person / medium";}?>
						</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td valign="top" colspan="8" class="Libelle">
						<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
								<?php
								
									$Id_RespProjet=$_SESSION['FiltreAT_RespProjet'];
									if($_POST){
										$Id_RespProjet="";
										if(isset($_POST['Id_RespProjet'])){
											if (is_array($_POST['Id_RespProjet'])) {
												foreach($_POST['Id_RespProjet'] as $value){
													if($Id_RespProjet<>''){$Id_RespProjet.=",";}
												  $Id_RespProjet.=$value;
												}
											} else {
												$value = $_POST['Id_RespProjet'];
												$Id_RespProjet = $value;
											}
										}
									}
									$_SESSION['FiltreAT_RespProjet']=$Id_RespProjet;
			
									$rqRespProjet="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
									AND Id_Plateforme IN (
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.")
									)
									AND Id_Personne<>0
									ORDER BY Personne";
									
									$resultRespProjet=mysqli_query($bdd,$rqRespProjet);
									$Id_RespProjet=0;
									while($rowRespProjet=mysqli_fetch_array($resultRespProjet))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_RespProjet']) ? $_POST['Id_RespProjet'] : array();
											foreach($checkboxes as $value) {
												if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
											}
										}
										else{
											$checkboxes = explode(',',$_SESSION['FiltreAT_RespProjet']);
											foreach($checkboxes as $value) {
												if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
											}
										}
										echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
									}
								?>
					</td>
				</tr>
			</table>
	</td></tr>
	<tr><td height="8"></td></tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				//PERSONNES AYANT UNE AUTORISATION DE CONDUITE
				$requeteAnalyse=" SELECT DISTINCT Id_Personne ";
				$req="SELECT DISTINCT new_competences_relation.Id_Personne, 
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne, 
				(SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS DateEditionAutorisationTravail ";
				$req2="FROM new_competences_relation 
				LEFT JOIN new_competences_qualification
				ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
				WHERE (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
				AND Date_Debut>'0001-01-01'
				AND new_competences_relation.Evaluation NOT IN ('B','')
				AND new_competences_relation.Suppr=0 
				AND (
						(SELECT COUNT(new_competences_qualification_moyen.Id_Moyen_Categorie)
							FROM new_competences_qualification_moyen 
							WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
							AND Id_Moyen_Categorie NOT IN (1,2)
						)>0 
					OR (
						(SELECT COUNT(new_competences_qualification_moyen.Id_Moyen_Categorie)
							FROM new_competences_qualification_moyen 
							WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
							AND Id_Moyen_Categorie IN (1,2)
						)>0
					
						AND 
						(
							((SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=new_competences_relation.Id_Personne
							AND Tab2.Id_Qualification_Parrainage=75)>0
							
							AND 
							(SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							LEFT JOIN new_competences_qualification AS Tab3
							ON Tab2.Id_Qualification_Parrainage=Tab3.Id
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=new_competences_relation.Id_Personne
							AND Tab2.Id_Qualification_Parrainage=12)>0
							
							AND 
							(SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							LEFT JOIN new_competences_qualification AS Tab3
							ON Tab2.Id_Qualification_Parrainage=Tab3.Id
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=new_competences_relation.Id_Personne
							AND Tab2.Id_Qualification_Parrainage=13)>0
							
							AND 
							(SELECT COUNT(Tab2.Id)
							FROM new_competences_relation AS Tab2
							LEFT JOIN new_competences_qualification AS Tab3
							ON Tab2.Id_Qualification_Parrainage=Tab3.Id
							WHERE Tab2.Suppr=0
							AND Tab2.Evaluation NOT IN ('B','')
							AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
							AND Tab2.Date_Debut>'0001-01-01' 
							AND Tab2.Id_Personne=new_competences_relation.Id_Personne
							AND Tab2.Id_Qualification_Parrainage=133)>0)
							
							OR 
							(
								((SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=new_competences_relation.Id_Personne
								AND Tab2.Id_Qualification_Parrainage=75)=0
								
								AND 
								(SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								LEFT JOIN new_competences_qualification AS Tab3
								ON Tab2.Id_Qualification_Parrainage=Tab3.Id
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=new_competences_relation.Id_Personne
								AND Tab2.Id_Qualification_Parrainage=12)=0
								
								AND 
								(SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								LEFT JOIN new_competences_qualification AS Tab3
								ON Tab2.Id_Qualification_Parrainage=Tab3.Id
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=new_competences_relation.Id_Personne
								AND Tab2.Id_Qualification_Parrainage=13)=0
								
								AND 
								(SELECT COUNT(Tab2.Id)
								FROM new_competences_relation AS Tab2
								LEFT JOIN new_competences_qualification AS Tab3
								ON Tab2.Id_Qualification_Parrainage=Tab3.Id
								WHERE Tab2.Suppr=0
								AND Tab2.Evaluation NOT IN ('B','')
								AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
								AND Tab2.Date_Debut>'0001-01-01' 
								AND Tab2.Id_Personne=new_competences_relation.Id_Personne
								AND Tab2.Id_Qualification_Parrainage=133)=0)
							)
							OR 
							
							new_competences_relation.Id_Qualification_Parrainage IN (1606,1607,2130,1683,2490,2145)
						)
					)
				)
				";
				if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){
				$req2.="
						AND (
						SELECT COUNT(Id_Personne)
						FROM new_competences_personne_plateforme
						WHERE new_competences_personne_plateforme.Id_Personne=new_competences_relation.Id_Personne 
						AND Id_Plateforme IN(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme 
						WHERE new_competences_personne_poste_plateforme.Id_Personne=".$IdPersonneConnectee."
						AND new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableRH.",".$IdPosteResponsableHSE.") 
						))>0 ";
				}
				else{
					$req2.="
						AND (
						SELECT COUNT(Id_Personne) 
						FROM new_competences_personne_prestation
						WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne 
						AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
						AND CONCAT(Id_Prestation,'_',Id_Pole) IN (
						SELECT CONCAT(Id_Prestation,'_',Id_Pole)
						FROM new_competences_personne_poste_prestation 
						WHERE new_competences_personne_poste_prestation.Id_Personne=".$IdPersonneConnectee."
						AND new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
						))>0 ";
				}
				$req2.=" AND (SELECT COUNT(Id)
					FROM new_competences_qualification_moyen
					WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
					AND Suppr=0)>0 ";
				if($Moyen<>"" && $Moyen<>"0"){
					$req2.=" AND (SELECT COUNT(Id)
					FROM new_competences_qualification_moyen
					WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
					AND (SELECT Id_Moyen FROM new_competences_moyen_categorie WHERE Id=Id_Moyen_Categorie)=".$Moyen."
					AND Suppr=0)>0 ";
				}
				if($prestation<>""){
					$req2.=" AND (
							SELECT COUNT(new_competences_prestation.Libelle) 
							FROM new_competences_personne_prestation 
							LEFT JOIN new_competences_prestation 
							ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
							WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne 
							AND Date_Debut<='".date('Y-m-d')."' 
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
							AND new_competences_prestation.Libelle LIKE '%".$prestation."%'
						)>0 ";
				}
				if($_SESSION['FiltreAT_RespProjet']<>""){
					$req2.="AND (
								SELECT COUNT(new_competences_prestation.Id) 
								FROM new_competences_personne_prestation 
								LEFT JOIN new_competences_prestation 
								ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne 
								AND Date_Debut<='".date('Y-m-d')."' 
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
								AND CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) 
								IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
									FROM new_competences_personne_poste_prestation
									WHERE Id_Personne IN (".$_SESSION['FiltreAT_RespProjet'].")
									AND Id_Poste IN (".$IdPosteResponsableProjet.")
								)
							)>0
								";
				}
			
				if($personne<>""){
					$req2.=" AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) LIKE '%".$personne."%' ";
				}
				
				if($etatF<>""){
					if($etatF=="A jour"){
						$req2.=" AND new_competences_relation.DateEditionAutorisationTravail>'0001-01-01' ";
					}
					elseif($etatF=="A éditer"){
						$req2.=" AND new_competences_relation.DateEditionAutorisationTravail<='0001-01-01' ";
					}
				}
							
				$resultPersonne=mysqli_query($bdd,$requeteAnalyse.$req2);
				$nbPersonne=mysqli_num_rows($resultPersonne);
				
				if($_SESSION['TriAT_General']<>""){
					$req2.=" ORDER BY ".substr($_SESSION['TriAT_General'],0,-1);
				}

				$nombreDePages=ceil($nbPersonne/100);
				if(isset($_GET['Page'])){$_SESSION['FORM_AUTORISATIONTRAVAIL_Page']=$_GET['Page'];}
				else{$_SESSION['FORM_AUTORISATIONTRAVAIL_Page']=0;}
				$req3=" LIMIT ".($_SESSION['FORM_AUTORISATIONTRAVAIL_Page']*100).",100";
				
				$resultPersonne=mysqli_query($bdd,$req.$req2.$req3);
				$nbPersonne=mysqli_num_rows($resultPersonne);
				
				$nbPage=0;
				if($_SESSION['FORM_AUTORISATIONTRAVAIL_Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_Autorisation_Travail.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['FORM_AUTORISATIONTRAVAIL_Page']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['FORM_AUTORISATIONTRAVAIL_Page']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['FORM_AUTORISATIONTRAVAIL_Page']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['FORM_AUTORISATIONTRAVAIL_Page']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_Autorisation_Travail.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['FORM_AUTORISATIONTRAVAIL_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Autorisation_Travail.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr><td>
		<div style="width:100%;height:400px;overflow:auto;">
		<table style="width:100%; border-spacing:0; align:left;" class="GeneralInfo">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Autorisation_Travail.php?Tri=Personne">&nbsp;<?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriAT_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAT_Personne']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="40%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Prestation - Pôle";}else{echo "Activity - Pole";} ?></td>
				<td class="EnTeteTableauCompetences" width="27%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Autorisation de conduite<br> Moyens - Catégories (Fin de validité)";}else{echo "Driving Authorization<br> Means - Categories (End of validity)";} ?></td>
				<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){ ?><td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";} ?></td><?php } ?>
				<td class="EnTeteTableauCompetences" align="center" width="5%">				
				</td>
			<?php 
                if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){
			?>				
				<td class="EnTeteTableauCompetences" align="center" width="5%">
					<a href="javascript:OuvreMultiAutorisation();" ><img src="../../Images/imprimer.jpg" /></a>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>				
				</td>
			<?php 
				}
			?>				
			</tr>
			<?php
				if ($nbPersonne>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultPersonne)){
							$bReedition=0;
							if($row['DateEditionAutorisationTravail']<='0001-01-01'){$bReedition=1;}
							//Liste des prestations de la personne
							$Prestations="";
							$reqPresta="SELECT DISTINCT Id_Prestation,
							(SELECT Libelle FROM new_competences_prestation 
							WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
							(SELECT Libelle FROM new_competences_pole 
							WHERE new_competences_pole.Id=Id_Pole) AS Pole
							FROM new_competences_personne_prestation
							WHERE Id_Personne=".$row['Id_Personne']." 
							AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') ";
							$resultPresta=mysqli_query($bdd,$reqPresta);
							$nbPresta=mysqli_num_rows($resultPresta);
							if($nbPresta>0){
								while($rowPresta=mysqli_fetch_array($resultPresta)){
									$Pole="";
									if($rowPresta['Pole']<>""){$Pole=" - ".stripslashes($rowPresta['Pole']);}
									$Prestations.="&bull;".stripslashes($rowPresta['Prestation']).$Pole."<br>";
								}
								$Prestations=substr($Prestations,0,-4);
							}
							
							//Liste des autorisations de conduite
							$AT="";
							$reqAT="
									SELECT * 
									FROM 
									(SELECT DISTINCT
                                        new_competences_relation.Date_Fin,
							            new_competences_relation.DateEditionAutorisationTravail,
							            (
                                            SELECT
                                                Libelle
                                            FROM
                                                new_competences_moyen_categorie 
							                WHERE
                                                new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie
                                        ) AS Categorie,
							            (
                                            SELECT 
								                (SELECT Libelle FROM new_competences_moyen WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) 
							                FROM
                                                new_competences_moyen_categorie 
							                WHERE
                                                new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie
                                        ) AS Moyen,
										new_competences_qualification_moyen.Id,(@row_number:=@row_number + 1) AS rnk										
                                    FROM
                                        new_competences_relation 
                                    LEFT JOIN new_competences_qualification_moyen
							             ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification_moyen.Id_Qualification
							        LEFT JOIN new_competences_qualification
							             ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id							
							        WHERE
                                        new_competences_qualification_moyen.Suppr=0
                                        AND new_competences_relation.Suppr=0 
							            AND new_competences_qualification_moyen.Suppr=0 
            							AND new_competences_relation.Evaluation NOT IN ('B','')
            							AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
            							AND Date_Debut>'0001-01-01' 
										AND (
												new_competences_qualification_moyen.Id_Moyen_Categorie NOT IN (1,2)
												OR (
												new_competences_qualification_moyen.Id_Moyen_Categorie IN (1,2)
												AND 
												((SELECT COUNT(Id)
												FROM new_competences_relation
												WHERE Suppr=0
												AND Evaluation NOT IN ('B','')
												AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
												AND Date_Debut>'0001-01-01' 
												AND Id_Personne=".$row['Id_Personne']."
												AND Id_Qualification_Parrainage=75)>0
												
												AND (SELECT COUNT(Id)
												FROM new_competences_relation
												WHERE Suppr=0
												AND Evaluation NOT IN ('B','')
												AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
												AND Date_Debut>'0001-01-01' 
												AND Id_Personne=".$row['Id_Personne']."
												AND Id_Qualification_Parrainage=12)>0
												
												AND (SELECT COUNT(Id)
												FROM new_competences_relation
												WHERE Suppr=0
												AND Evaluation NOT IN ('B','')
												AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
												AND Date_Debut>'0001-01-01' 
												AND Id_Personne=".$row['Id_Personne']."
												AND Id_Qualification_Parrainage=13)>0
												
												AND (SELECT COUNT(Id)
												FROM new_competences_relation
												WHERE Suppr=0
												AND Evaluation NOT IN ('B','')
												AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
												AND Date_Debut>'0001-01-01' 
												AND Id_Personne=".$row['Id_Personne']."
												AND Id_Qualification_Parrainage=133)>0)
												OR
												(
													((SELECT COUNT(Tab2.Id)
													FROM new_competences_relation AS Tab2
													WHERE Tab2.Suppr=0
													AND Tab2.Evaluation NOT IN ('B','')
													AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
													AND Tab2.Date_Debut>'0001-01-01' 
													AND Tab2.Id_Personne=".$row['Id_Personne']."
													AND Tab2.Id_Qualification_Parrainage=75)=0
													
													AND 
													(SELECT COUNT(Tab2.Id)
													FROM new_competences_relation AS Tab2
													LEFT JOIN new_competences_qualification AS Tab3
													ON Tab2.Id_Qualification_Parrainage=Tab3.Id
													WHERE Tab2.Suppr=0
													AND Tab2.Evaluation NOT IN ('B','')
													AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
													AND Tab2.Date_Debut>'0001-01-01' 
													AND Tab2.Id_Personne=".$row['Id_Personne']."
													AND Tab2.Id_Qualification_Parrainage=12)=0
													
													AND 
													(SELECT COUNT(Tab2.Id)
													FROM new_competences_relation AS Tab2
													LEFT JOIN new_competences_qualification AS Tab3
													ON Tab2.Id_Qualification_Parrainage=Tab3.Id
													WHERE Tab2.Suppr=0
													AND Tab2.Evaluation NOT IN ('B','')
													AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
													AND Tab2.Date_Debut>'0001-01-01' 
													AND Tab2.Id_Personne=".$row['Id_Personne']."
													AND Tab2.Id_Qualification_Parrainage=13)=0
													
													AND 
													(SELECT COUNT(Tab2.Id)
													FROM new_competences_relation AS Tab2
													LEFT JOIN new_competences_qualification AS Tab3
													ON Tab2.Id_Qualification_Parrainage=Tab3.Id
													WHERE Tab2.Suppr=0
													AND Tab2.Evaluation NOT IN ('B','')
													AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
													AND Tab2.Date_Debut>'0001-01-01' 
													AND Tab2.Id_Personne=".$row['Id_Personne']."
													AND Tab2.Id_Qualification_Parrainage=133)=0)
												)
												OR 
							
													new_competences_relation.Id_Qualification_Parrainage IN (1606,1607,2130,1683,2490,2145)
												)
											)
            							AND new_competences_relation.Id_Personne=".$row['Id_Personne']." 
										ORDER BY Moyen, Categorie, Date_Fin DESC
										) AS TAB 
										GROUP BY Moyen,Categorie
										
									";
							$resultAT=mysqli_query($bdd,$reqAT);
							$nbAT=mysqli_num_rows($resultAT);
							if($nbAT>0){
								while($rowAT=mysqli_fetch_array($resultAT)){
									if(AfficheDateJJ_MM_AAAA($rowAT['Date_Fin'])<>""){$dateFin=AfficheDateJJ_MM_AAAA($rowAT['Date_Fin']);}
									else{
										if($LangueAffichage=="FR"){$dateFin="sans limite";}
										else{$dateFin="illimitable";}
									}
									$AT.="&bull;".stripslashes($rowAT['Moyen'])." - ".stripslashes($rowAT['Categorie'])." (".$dateFin.") <br>";
									if($bReedition==0){
										if($rowAT['DateEditionAutorisationTravail']<='0001-01-01'){
											$bReedition=1;
										}
									}
								}
								$AT=substr($AT,0,-4);
							}
							$etat="";
							if($bReedition==1){
								if($LangueAffichage=="FR"){
									$etat="A éditer";
								}
								else{
									$etat="To edit";
								}
							}

							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td style="border-bottom:1px dotted #003333;" valign="middle">&nbsp;<?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a>" ;?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $Prestations;?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $AT;?></td>
							<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){ ?>
								<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $etat;?></td>
							<?php }?>
								<td style="border-bottom:1px dotted #003333;" valign="middle" align="center">
							<?php 
                                if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE) || DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
                                {
                                    if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){$OuvreFichier="OuvreAutorisation(".$row['Id_Personne'].",'EXCEL');";}
                                    else{$OuvreFichier="OuvreAutorisation(".$row['Id_Personne'].",'WEB');";}
							?>
								<a href="javascript:<?php echo $OuvreFichier; ?>">
									<img style="width:30px" src='../../Images/AutorisationTravail.png' border='0' alt='<?php if($LangueAffichage=="EN"){echo "Driving Authorization";}else{echo "Autorisation de conduite";} ?>' title='<?php if($LangueAffichage=="EN"){echo "Driving Authorization";}else{echo "Autorisation de conduite";} ?>'>
								</a>								</td>
								
								<?php  if(DroitsFormationPlateforme($TableauIdPostesAF_RF_HSE)){ ?>
								<td>
									<input class="check" type="checkbox" name="<?php echo $row['Id_Personne'];?>" id="<?php echo $row['Id_Personne'];?>" />
								</td>
							<?php
								}
								}
							?>								
							</tr>
						<?php
							if($couleur=="#ffffff"){$couleur="#b1daeb";}
							else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
		</div>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>