<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Id)
	{var w=window.open("Modif_Besoin.php?Mode=M&Id="+Id,"PageBesoin","status=no,scrollbars=1,menubar=no,width=1400,height=650");
	w.focus();
	}
	function OuvreFenetreDupliquer(Id)
	{var w=window.open("Dupliquer_Besoin.php?Id="+Id,"PageBesoin","status=no,scrollbars=1,menubar=no,width=1400,height=650");
	w.focus();
	}
	function OuvreFormatExcel()
	{window.open("Export_Besoin.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function OuvreFenetreSuppr(Id)
	{var w=window.open("Modif_Besoin.php?Mode=S&Id="+Id,"PageBesoin","status=no,scrollbars=1,menubar=no,width=50,height=50");
	w.focus();
	}
	function OuvreFenetreRefus(Menu,Id){
		if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to refuse?';}
		else{texte='Etes-vous sûr de vouloir refuser ?';}
		if(window.confirm(texte)){
			var w=window.open("Refuser_Besoin.php?Id="+Id,"PageBesoin","status=no,menubar=no,scrollbars=yes,width=800,height=300");
		}			
	}
	function OuvreFenetreValider(Menu,Id){
		var w=window.open("Valider_Besoin.php?Id="+Id,"PageBesoin","status=no,menubar=no,scrollbars=yes,width=50,height=50");			
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
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("Ref","Prestation","Plateforme","Domaine","Programme","DateDemande","Demandeur","Etat","Lieu","Metier","DateBesoin","Duree","Nombre","Horaire","Etat","Statut","Statut2","CreationPoste","DateRecrutementMAJ","DateRecrutement","DateActualisation");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRecrutBesoin_General']= str_replace($tri." ASC,","",$_SESSION['TriRecrutBesoin_General']);
			$_SESSION['TriRecrutBesoin_General']= str_replace($tri." DESC,","",$_SESSION['TriRecrutBesoin_General']);
			$_SESSION['TriRecrutBesoin_General']= str_replace($tri." ASC","",$_SESSION['TriRecrutBesoin_General']);
			$_SESSION['TriRecrutBesoin_General']= str_replace($tri." DESC","",$_SESSION['TriRecrutBesoin_General']);
			if($_SESSION['TriRecrutBesoin_'.$tri]==""){$_SESSION['TriRecrutBesoin_'.$tri]="ASC";$_SESSION['TriRecrutBesoin_General'].= $tri." ".$_SESSION['TriRecrutBesoin_'.$tri].",";}
			elseif($_SESSION['TriRecrutBesoin_'.$tri]=="ASC"){$_SESSION['TriRecrutBesoin_'.$tri]="DESC";$_SESSION['TriRecrutBesoin_General'].= $tri." ".$_SESSION['TriRecrutBesoin_'.$tri].",";}
			else{$_SESSION['TriRecrutBesoin_'.$tri]="";}
		}
	}
}

?>

<form class="test" action="Besoins.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ededed;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/TalentBoost/Tableau_De_Bord.php'>";
					if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des besoins";}else{echo "List of requirements";}
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
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?>
				<select style="width:90px;" name="plateforme" onchange="submit();">
				<?php
				if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
					$requete="SELECT Id, Libelle
						FROM new_competences_plateforme
						WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
						ORDER BY Libelle ASC";
				}
				else{
					$requete="SELECT Id, Libelle
						FROM new_competences_plateforme
						WHERE Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
							)
						OR 
							Id IN 
							(SELECT (SELECT Id FROM new_competences_plateforme WHERE Id=Id_Prestation)
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
							)
						ORDER BY Libelle ASC";
				}
				$result=mysqli_query($bdd,$requete);
				$nb=mysqli_num_rows($result);
				
				$PtfSelect=$_SESSION['FiltreRecrutBesoin_Plateforme'];
				if($_POST){$PtfSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRecrutBesoin_Plateforme']=$PtfSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nb > 0)
				{
					while($row=mysqli_fetch_array($result))
					{
						$selected="";
						if($PtfSelect<>""){if($PtfSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:90px;" name="prestations" onchange="submit();">
				<?php
				if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
					$requeteSite="
							SELECT DISTINCT talentboost_annonce.Id_Prestation AS Id, LEFT(new_competences_prestation.Libelle,7) AS Libelle
							FROM talentboost_annonce
							LEFT JOIN new_competences_prestation
							ON talentboost_annonce.Id_Prestation=new_competences_prestation.Id
							WHERE new_competences_prestation.Id_Plateforme=".$PtfSelect."
							ORDER BY Libelle ASC";
				}
				else{
					$requeteSite="SELECT DISTINCT talentboost_annonce.Id_Prestation AS Id, LEFT(new_competences_prestation.Libelle,7) AS Libelle
							FROM talentboost_annonce
							LEFT JOIN new_competences_prestation
							ON talentboost_annonce.Id_Prestation=new_competences_prestation.Id
						WHERE new_competences_prestation.Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
							)
						OR 
							Id_Prestation IN 
							(SELECT Id_Prestation
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
							)
						ORDER BY Libelle ASC";
				}
				
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRecrutBesoin_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRecrutBesoin_Prestation']=$PrestationSelect;	
				
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
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} 
				$metier=$_SESSION['FiltreRecrutBesoin_Metier'];
				if($_POST){$metier=$_POST['metier'];}
				$_SESSION['FiltreRecrutBesoin_Metier']=$metier;
				?>
				<input id="metier" name="metier" type="text" value="<?php echo $metier; ?>" size="40"/>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de démarrage :";}else{echo "Starting date :";} 
				
				$signeDateDemarrage=$_SESSION['FiltreRecrutBesoin_SigneDateDemarrage'];
				if($_POST){$signeDateDemarrage=$_POST['signeDateDemarrage'];}
				$_SESSION['FiltreRecrutBesoin_SigneDateDemarrage']=$signeDateDemarrage;
				?>
				<select id="signeDateDemarrage" name="signeDateDemarrage" onchange="submit();">
					<option value='=' <?php if($signeDateDemarrage=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateDemarrage=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateDemarrage==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateDemarrage=$_SESSION['FiltreRecrutBesoin_DateDemarrage'];
				if($_POST){$dateDemarrage=TrsfDate_($_POST['dateDemarrage']);}
				$_SESSION['FiltreRecrutBesoin_DateDemarrage']=$dateDemarrage;
				
				?>
				<input id="dateDemarrage" name="dateDemarrage" type="date" value="<?php echo AfficheDateFR($dateDemarrage); ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="2%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="2%">
				&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Demandeur :";}else{echo "Applicant :";} ?>
				<select id="demandeur" style="width:150px;" name="demandeur" onchange="submit();">
					<option value='0'></option>
					<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
							$requete="SELECT DISTINCT Id_Demandeur,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur 
									  FROM talentboost_annonce
									  WHERE (Suppr=0 OR Suppr=1) ";
						}
						else{
							$requete="SELECT DISTINCT Id_Demandeur,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur 
									  FROM talentboost_annonce
									  WHERE (Suppr=0 OR Suppr=1) 
									  AND (
											  talentboost_annonce.Id_Plateforme IN 
												(
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsableRecrutement.",".$IdPosteRecrutement.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
												)
											OR 
												Id_Prestation IN 
												(SELECT Id_Prestation
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION["Id_Personne"]."
												AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
												)
									  )
									  ORDER BY Demandeur
									  ";
						}
						$resultDemandeur=mysqli_query($bdd,$requete);
						$NbDemandeur=mysqli_num_rows($resultDemandeur);
						
						$demandeur=$_SESSION['FiltreRecrutBesoin_Demandeur'];
						if($_POST){$demandeur=$_POST['demandeur'];}
						$_SESSION['FiltreRecrutBesoin_Demandeur']= $demandeur;
						
						if($NbDemandeur>0){
							while($rowDemandeur=mysqli_fetch_array($resultDemandeur))
							{
								echo "<option value='".$rowDemandeur['Id_Demandeur']."'";
								if ($demandeur == $rowDemandeur['Id_Demandeur']){echo " selected ";}
								echo ">".$rowDemandeur['Demandeur']."</option>\n";
							}
						}
					?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Domaine :";}else{echo "Domain :";} ?>
				<select id="domaine" style="width:100px;" name="domaine" onchange="submit();">
					<option value='0'></option>
					<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
							$requete="SELECT DISTINCT Id_Domaine,(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine 
									  FROM talentboost_annonce
									  WHERE (Suppr=0 OR Suppr=1) 
									  AND Id_Domaine>0 ";
						}
						else{
							$requete="SELECT DISTINCT Id_Domaine,(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine 
									  FROM talentboost_annonce
									  WHERE (Suppr=0 OR Suppr=1) 
									  AND Id_Domaine>0 
									  AND (
											  talentboost_annonce.Id_Plateforme IN 
												(
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
												)
											OR 
												Id_Prestation IN 
												(SELECT Id_Prestation
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION["Id_Personne"]."
												AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
												)
									  )
									  ORDER BY Domaine
									  ";
						}
						$resultDomaine=mysqli_query($bdd,$requete);
						$NbDomaine=mysqli_num_rows($resultDomaine);
						
						$domaine=$_SESSION['FiltreRecrutBesoin_Domaine'];
						if($_POST){$domaine=$_POST['domaine'];}
						$_SESSION['FiltreRecrutBesoin_Domaine']= $domaine;
						
						if($NbDomaine>0){
							while($rowDomaine=mysqli_fetch_array($resultDomaine))
							{
								echo "<option value='".$rowDomaine['Id_Domaine']."'";
								if ($domaine == $rowDomaine['Id_Domaine']){echo " selected ";}
								echo ">".$rowDomaine['Domaine']."</option>\n";
							}
						}
					?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Programme :";}else{echo "Program :";} ?>
				<select id="programme" style="width:100px;" name="programme" onchange="submit();">
					<option value='0'></option>
					<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
							$requete="SELECT DISTINCT Programme
									  FROM talentboost_annonce
									  WHERE (Suppr=0 OR Suppr=1) 
									  AND Programme<>'' ";
						}
						else{
							$requete="SELECT DISTINCT Programme
									  FROM talentboost_annonce
									  WHERE (Suppr=0 OR Suppr=1) 
									   AND Programme<>'' 
									  AND (
											  talentboost_annonce.Id_Plateforme IN 
												(
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
												)
											OR 
												Id_Prestation IN 
												(SELECT Id_Prestation
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION["Id_Personne"]."
												AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
												)
									  )
									  ORDER BY Programme
									  ";
						}
						$resultProgramme=mysqli_query($bdd,$requete);
						$NbProgramme=mysqli_num_rows($resultProgramme);
						
						$programme=$_SESSION['FiltreRecrutBesoin_Programme'];
						if($_POST){$programme=$_POST['programme'];}
						$_SESSION['FiltreRecrutBesoin_Programme']= $programme;
						
						if($NbProgramme>0){
							while($rowProgramme=mysqli_fetch_array($resultProgramme))
							{
								echo "<option value='".$rowProgramme['Programme']."'";
								if ($programme == $rowProgramme['Programme']){echo " selected ";}
								echo ">".$rowProgramme['Programme']."</option>\n";
							}
						}
					?>
				</select>
			</td>
			<td width="5%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Information :";}else{echo "Information :";} 
				
				$information=$_SESSION['FiltreRecrutBesoin_Information'];
				if($_POST){$information=$_POST['information'];}
				$_SESSION['FiltreRecrutBesoin_Information']=$information;
				
				?>
				<input id="information" name="information" type="text" value="<?php echo $information; ?>" size="40"/>&nbsp;&nbsp;
			</td>
			<td width="2%">
				<a class="Bouton" href="javascript:OuvreFormatExcel()">
				<?php if($_SESSION["Langue"]=="FR"){echo "Besoins";}else{echo "Needs";} ?>
				</a>
			</td>
		</tr>
		<tr>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validation DG :";}else{echo "DG Validation :";} ?>
				<select id="etat" style="width:150px;" name="etat" onchange="submit();">
					<option value=""></option>
					<?php 
						$etat=$_SESSION['FiltreRecrutBesoin_Etat'];
						if($_POST){$etat=$_POST['etat'];}
						$_SESSION['FiltreRecrutBesoin_Etat']=$etat;
					?>
					<option value="0" <?php if($etat=="0"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "En attente";}else{echo "Pending";} ?></option>
					<option value="1" <?php if($etat=="1"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Validé";}else{echo "Validated";} ?></option>
					<option value="-1" <?php if($etat=="-1"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Refusé";}else{echo "Refuse";} ?></option>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Statut :";}else{echo "Status :";} ?>
				<select id="statut" style="width:120px;" name="statut" onchange="submit();">
					<option value="-2"></option>
					<?php 
						$statut=$_SESSION['FiltreRecrutBesoin_Statut'];
						if($_POST){$statut=$_POST['statut'];}
						$_SESSION['FiltreRecrutBesoin_Statut']=$statut;
					?>
					<option value="0" <?php if($statut==0){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste ouvert";}else{echo "Open post";} ?></option>
					<option value="1" <?php if($statut==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste pourvu";}else{echo "Position filled";} ?></option>
					<option value="2" <?php if($statut==2){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste non pourvu";}else{echo "Position not filled";} ?></option>
					<option value="3" <?php if($statut==3){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste pourvu partiellement";}else{echo "Position partially filled";} ?></option>
					<option value="-1" <?php if($statut==-1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste annulé";}else{echo "Position canceled";} ?></option>
					<option value="-3" <?php if($statut==-3){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste non pourvu/pourvu partiellement";}else{echo "Position not filled/partially filled";} ?></option>
					<option value="4" <?php if($statut==4){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Demande clôturée";}else{echo "Request closed";} ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
	
		if($_SESSION["Langue"]=="FR"){
			$reqSuite="IF(ValidationContratDG>0,'OUI',IF(ValidationContratDG=0,'','NON')) AS Etat, 
				IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande clôturée','Poste annulé')))))
					)
				) AS Statut2, ";
		}
		else{
			$reqSuite="IF(ValidationContratDG>0,'YES',IF(ValidationContratDG=0,'','NO')) AS Etat, 
				IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled',IF(EtatPoste=4,'Request closed','Position canceled')))))
					)
				) AS Statut2, ";
		}
		$requeteAnalyse="SELECT Id ";
		$requete2="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,Division,
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',IF(DateRecrutement=0,DATE_FORMAT(DateDemande,'%d%m%y'),DATE_FORMAT(DateRecrutement,'%d%m%y'))
			) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,DateValidationDG,DateActualisation,
			EtatApprobation,ValidationContratDG,DateRecrutementMAJ,EtatPoste,DateRecrutement,
			".$reqSuite."
			DateBesoin,Duree,PosteDefinitif,OuvertureAutresPlateformes,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			Id_Plateforme,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation ";
		$requete=" FROM talentboost_annonce
					WHERE Suppr=0  ";
		if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
			$requete.="  AND OuvertureAutresPlateformes=1 ";
		}
		else{
			$requete.="  AND (
							  talentboost_annonce.Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
								)
							OR 
								Id_Prestation IN 
								(SELECT Id_Prestation
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION["Id_Personne"]."
								AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
								)
					  ) ";
					  
		}
		if($_SESSION['FiltreRecrutBesoin_Plateforme']<>0){
			$requete.=" AND Id_Plateforme=".$_SESSION['FiltreRecrutBesoin_Plateforme']." ";
		}
		if($_SESSION['FiltreRecrutBesoin_Prestation']<>0){
			$requete.=" AND talentboost_annonce.Id_Prestation=".$_SESSION['FiltreRecrutBesoin_Prestation']." ";
		}
		if($_SESSION['FiltreRecrutBesoin_Metier']<>""){
			$requete.=" AND talentboost_annonce.Metier LIKE \"%".$_SESSION['FiltreRecrutBesoin_Metier']."%\" ";
		}
		if($_SESSION['FiltreRecrutBesoin_Demandeur']<>0){
			$requete.=" AND talentboost_annonce.Id_Demandeur=".$_SESSION['FiltreRecrutBesoin_Demandeur']." ";
		}
		if($_SESSION['FiltreRecrutBesoin_Domaine']<>0){
			$requete.=" AND talentboost_annonce.Id_Domaine=".$_SESSION['FiltreRecrutBesoin_Domaine']." ";
		}
		if($_SESSION['FiltreRecrutBesoin_Programme']<> "0"){
			$requete.=" AND talentboost_annonce.Programme= \"".$_SESSION['FiltreRecrutBesoin_Programme']."\" ";
		}
		if($_SESSION['FiltreRecrutBesoin_Etat']<>""){
			$requete.=" AND ValidationContratDG=".$_SESSION['FiltreRecrutBesoin_Etat']." ";
		}
		if($_SESSION['FiltreRecrutBesoin_Statut']<>-2 && $_SESSION['FiltreRecrutBesoin_Statut']<>-3){
			$requete.=" AND EtatPoste=".$_SESSION['FiltreRecrutBesoin_Statut']." ";
		}
		elseif($_SESSION['FiltreRecrutBesoin_Statut']==-3){
			$requete.=" AND EtatPoste IN (2,3) ";
		}
		if($_SESSION['FiltreRecrutBesoin_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutBesoin_DateDemarrage']<>""){
			$requete.=" AND talentboost_annonce.DateBesoin".$_SESSION['FiltreRecrutBesoin_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutBesoin_DateDemarrage']."' ";
		}
		if($_SESSION['FiltreRecrutBesoin_Information']<>""){
			$requete.=" AND (
				Lieu LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR Diplome LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
				OR Langue LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
			) ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRecrutBesoin_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutBesoin_General'],0,-1);
		}
		
		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);

		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Besoins.php?debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Besoins.php?debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Besoins.php?debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="1%"></td>
					<td class="EnTeteTableauCompetences" width="1%"></td>
					<td class="EnTeteTableauCompetences" width="6%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Ref"><?php if($_SESSION["Langue"]=="FR"){echo "Ref";}else{echo "Ref";} ?><?php if($_SESSION['TriRecrutBesoin_Ref']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Ref']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Demandeur"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?><?php if($_SESSION['TriRecrutBesoin_Demandeur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Demandeur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=DateDemande"><?php if($_SESSION["Langue"]=="FR"){echo "Date demande";}else{echo "Request date";} ?><?php if($_SESSION['TriRecrutBesoin_DateDemande']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_DateDemande']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="11%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriRecrutBesoin_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Plateforme"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?><?php if($_SESSION['TriRecrutBesoin_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRecrutBesoin_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Domaine"><?php if($_SESSION["Langue"]=="FR"){echo "Domaine";}else{echo "Domain";} ?><?php if($_SESSION['TriRecrutBesoin_Domaine']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Domaine']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Lieu"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu";}else{echo "Place";} ?><?php if($_SESSION['TriRecrutBesoin_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Lieu']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Nombre"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre";}else{echo "Number";} ?><?php if($_SESSION['TriRecrutBesoin_Nombre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Nombre']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=DateBesoin"><?php if($_SESSION["Langue"]=="FR"){echo "Date démarrage";}else{echo "Start date";} ?><?php if($_SESSION['TriRecrutBesoin_DateBesoin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_DateBesoin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Duree"><?php if($_SESSION["Langue"]=="FR"){echo "Durée";}else{echo "Duration";} ?><?php if($_SESSION['TriRecrutBesoin_Duree']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Duree']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Validation DG";}else{echo "DG Validation";} ?><?php if($_SESSION['TriRecrutBesoin_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Etat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=Statut2"><?php if($_SESSION["Langue"]=="FR"){echo "Statut du poste";}else{echo "Job status";} ?><?php if($_SESSION['TriRecrutBesoin_Statut2']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutBesoin_Statut2']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=DateRecrutement"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'offre";}else{echo "Offer date";} ?><?php if($_SESSION['TriRecrutAnnonce_DateRecrutement']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_DateRecrutement']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Besoins.php?Tri=DateActualisation"><?php if($_SESSION["Langue"]=="FR"){echo "Date actualisation";}else{echo "Update date";} ?><?php if($_SESSION['TriRecrutAnnonce_DateActualisation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_DateActualisation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%"><?php if($_SESSION["Langue"]=="FR"){echo "Supp.";}else{echo "Delete";} ?></td>
				</tr>
	<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$Etat="";
					$CouleurEtat=$couleur;
					$Hover="";
				
					if($row['ValidationContratDG']>0){$CouleurEtat="#ecf943";}
					elseif($row['ValidationContratDG']<0){$CouleurEtat="#f55645";}
					else{$CouleurEtat="#6c94d0";}
					
					$duree=stripslashes($row['Duree']);
					if($row['PosteDefinitif']==1){
						if($_SESSION["Langue"]=="FR"){$duree="Poste définitif";}
						else{$duree="Definitive position";}
					}
					elseif($row['PosteDefinitif']==2){
						if($_SESSION["Langue"]=="FR"){$duree="CDD 6 mois";}
						else{$duree="CDD 6 mois";}
					}
					elseif($row['PosteDefinitif']==3){
						if($_SESSION["Langue"]=="FR"){$duree="CDD 2 mois";}
						else{$duree="CDD 2 mois";}
					}
					elseif($row['PosteDefinitif']==4){
						if($_SESSION["Langue"]=="FR"){$duree="CDD";}
						else{$duree="CDD";}
					}
					
					$reqPrestaPoste = "SELECT Id_Prestation 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne =".$IdPersonneConnectee."  
						AND ".$row['Id_Prestation']."
						AND Id_Poste IN (".$IdPosteResponsableOperation.")
						";	
					$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
					
					$ActionAFaire=0;
					if($row['EtatValidation']==0 && $nbPoste>0){$ActionAFaire=1;}
					elseif($row['EtatValidation']>0 && $row['EtatApprobation']==0){
						if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsablePlateforme))){$ActionAFaire=1;}
					}
					elseif($row['EtatValidation']>0 && $row['EtatApprobation']>0 && $row['ValidationContratDG']==0){
						if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){$ActionAFaire=1;}
					}
					
					$couleurPoste="";
					if($row['EtatPoste']==1){$couleurPoste="bgcolor='#66e733'";}
					elseif($row['EtatPoste']==-1){$couleurPoste="bgcolor='#919497'";}

		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php if($ActionAFaire==1){echo "<img src='../../Images/Droite.png' width='20px' border='0' />";} ?></td>
						<td><?php 
						if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
						{
						?>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="OuvreFenetreDupliquer('<?php echo $row['Id']; ?>')"><img src="../../Images/Duplication.gif" border="0" title="Dupliquer" alt="Dupliquer"></a>
						<?php
						}
						?></td>
						<td style="font-size:15px;" <?php if($ActionAFaire==1){echo "bgcolor='#f4a6ba'; cellpadding='1' ";}?>><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Ref']);?></a></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDemande']);?></td>
						<td><?php echo stripslashes($row['Metier']);?></td>
						<td><?php echo stripslashes($row['Plateforme']);
						if($row['Division']<>""){
							echo " - ".stripslashes($row['Division']);
						}
						?></td>
						<td><?php echo stripslashes($row['Prestation']);?></td>
						<td><?php echo stripslashes($row['Domaine']);?></td>
						<td><?php echo stripslashes($row['Lieu']);?></td>
						<td><?php echo stripslashes($row['Nombre']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateBesoin']);?></td>
						<td><?php echo stripslashes($duree);?></td>
						<td  bgcolor="<?php echo $CouleurEtat; ?>"><?php echo stripslashes($row['Etat']);?></td>
						<td <?php echo $couleurPoste; ?>><?php echo stripslashes($row['Statut2']);?></td>
						<td><?php if($row['ValidationContratDG']>0){echo AfficheDateJJ_MM_AAAA($row['DateValidationDG']);} ?></td>
						<td><?php if($row['ValidationContratDG']>0){echo AfficheDateJJ_MM_AAAA($row['DateActualisation']);} ?></td>
						<td>
						<?php
						if(($row['Suppr']==0 && $row['Id_Demandeur']==$_SESSION['Id_Personne'] && ($row['EtatValidation']==0 || ($row['EtatValidation']>0 && $row['EtatApprobation']==0) )) || $_SESSION['Id_Personne']==1132){
								$reqPrestaPoste = "SELECT Id_Prestation 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne =".$IdPersonneConnectee."  
									AND ".$row['Id_Prestation']."
									AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
									";	
								$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
								
								if($nbPoste>0 || $_SESSION['Id_Personne']==1132){
							 ?>
										<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
							<?php
							}
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
		<td height="50px"></td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>