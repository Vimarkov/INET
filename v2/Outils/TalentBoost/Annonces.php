<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Id)
	{var w=window.open("Modif_Annonce.php?Mode=M&Id="+Id,"PageBesoin","status=no,scrollbars=1,menubar=no,width=1400,height=650");
	w.focus();
	}
	function OuvreFenetreCandidature(Id)
	{var w=window.open("Candidatures.php?Id="+Id,"PageCandidature","status=no,scrollbars=1,menubar=no,width=1200,height=500");
	w.focus();
	}
	function OuvreFenetreCV()
	{var w=window.open("CVs.php","PageCV","status=no,scrollbars=1,menubar=no,width=600,height=500");
	w.focus();
	}
	function OuvreFormatExcel()
			{window.open("Export_Offre.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function OuvreFormatExcelCandidature()
			{window.open("Export_Candidature.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function OuvreFormatExcelMatrice()
			{window.open("Export_TableauCandidatures.php","PageExcel","status=no,menubar=no,width=90,height=40");}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("Ref","DateRecrutement","Plateforme","Domaine","DateButoire","Lieu","NombrePoste","CategorieProf","Metier","DateBesoin","DateButoire","Demandeur","Nombre","Statut","DateRecrutementMAJ","PersonneAContacter");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRecrutAnnonce_General']= str_replace($tri." ASC,","",$_SESSION['TriRecrutAnnonce_General']);
			$_SESSION['TriRecrutAnnonce_General']= str_replace($tri." DESC,","",$_SESSION['TriRecrutAnnonce_General']);
			$_SESSION['TriRecrutAnnonce_General']= str_replace($tri." ASC","",$_SESSION['TriRecrutAnnonce_General']);
			$_SESSION['TriRecrutAnnonce_General']= str_replace($tri." DESC","",$_SESSION['TriRecrutAnnonce_General']);
			if($_SESSION['TriRecrutAnnonce_'.$tri]==""){$_SESSION['TriRecrutAnnonce_'.$tri]="ASC";$_SESSION['TriRecrutAnnonce_General'].= $tri." ".$_SESSION['TriRecrutAnnonce_'.$tri].",";}
			elseif($_SESSION['TriRecrutAnnonce_'.$tri]=="ASC"){$_SESSION['TriRecrutAnnonce_'.$tri]="DESC";$_SESSION['TriRecrutAnnonce_General'].= $tri." ".$_SESSION['TriRecrutAnnonce_'.$tri].",";}
			else{$_SESSION['TriRecrutAnnonce_'.$tri]="";}
		}
	}
}

?>

<form class="test" action="Annonces.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#d9d9d9;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/TalentBoost/Tableau_De_Bord.php'>";
					if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Offre mobilit� interne";}else{echo "Internal mobility offer";}
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unit� d'exploitation :";}else{echo "Operating unit :";} ?>
				<select style="width:90px;" name="plateforme" onchange="submit();">
				<?php
				
				$requete="SELECT DISTINCT Id_Plateforme AS Id,
						(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
					  FROM talentboost_annonce
					  WHERE Suppr=0
					  AND ValidationContratDG>0
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
						OR
							(talentboost_annonce.Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							)
							OR OuvertureAutresPlateformes=1
							)
					  )
					ORDER BY Libelle ASC";

				$result=mysqli_query($bdd,$requete);
				$nb=mysqli_num_rows($result);
				
				$PtfSelect=$_SESSION['FiltreRecrutAnnonce_Plateforme'];
				if($_POST){$PtfSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRecrutAnnonce_Plateforme']=$PtfSelect;	
				
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
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Domaine :";}else{echo "Domain :";} ?>
				<select id="domaine" style="width:100px;" name="domaine" onchange="submit();">
					<option value='0'></option>
					<?php
						$requete="SELECT DISTINCT Id_Domaine,(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine 
								  FROM talentboost_annonce
								  WHERE Suppr=0
								  AND ValidationContratDG>0
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
									OR
										(talentboost_annonce.Id_Plateforme IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										)
										OR OuvertureAutresPlateformes=1
										)
							  )";
						
					

						$resultDomaine=mysqli_query($bdd,$requete);
						$NbDomaine=mysqli_num_rows($resultDomaine);
						
						$domaine=$_SESSION['FiltreRecrutAnnonce_Domaine'];
						if($_POST){$domaine=$_POST['domaine'];}
						$_SESSION['FiltreRecrutAnnonce_Domaine']= $domaine;
						
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
						$requete="SELECT DISTINCT Programme
								  FROM talentboost_annonce
								  WHERE Suppr=0
								  AND ValidationContratDG>0
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
									OR
										(talentboost_annonce.Id_Plateforme IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										)
										OR OuvertureAutresPlateformes=1
										)
								ORDER BY Programme
							  )";
						$resultProgramme=mysqli_query($bdd,$requete);
						$NbProgramme=mysqli_num_rows($resultProgramme);
						
						$programme=$_SESSION['FiltreRecrutAnnonce_Programme'];
						if($_POST){$programme=$_POST['programme'];}
						$_SESSION['FiltreRecrutAnnonce_Programme']= $programme;
						
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
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "M�tier :";}else{echo "Job :";} 
				?>
				<select id="metier" style="width:200px;" name="metier" onchange="submit();">
					<option value=''></option>
					<?php
						$requete="SELECT DISTINCT Metier 
								  FROM talentboost_annonce
								  WHERE Suppr=0 
								  AND ValidationContratDG>0
								  AND Metier<>'' ";
						$resultMetier=mysqli_query($bdd,$requete);
						$NbMetier=mysqli_num_rows($resultMetier);
						
						$metier=$_SESSION['FiltreRecrutAnnonce_Metier'];
						if($_POST){$metier=$_POST['metier'];}
						$_SESSION['FiltreRecrutAnnonce_Metier']= $metier;
						
						if($NbMetier>0){
							while($rowDomaine=mysqli_fetch_array($resultMetier))
							{
								echo "<option value=\"".$rowDomaine['Metier']."\"";
								if ($metier == $rowDomaine['Metier']){echo " selected ";}
								echo ">".$rowDomaine['Metier']."</option>\n";
							}
						}
					?>
				</select>
			</td>
			<td width="2%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Statut du poste :";}else{echo "Job status :";} ?>
				<select id="etat" style="width:120px;" name="etat" onchange="submit();">
					<option value="-2"></option>
					<?php 
						$etat=$_SESSION['FiltreRecrutAnnonce_Etat'];
						if($_POST){$etat=$_POST['etat'];}
						$_SESSION['FiltreRecrutAnnonce_Etat']=$etat;
					?>
					<option value="0" <?php if($etat==0){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste ouvert";}else{echo "Open post";} ?></option>
					<option value="1" <?php if($etat==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste pourvu";}else{echo "Position filled";} ?></option>
					<option value="2" <?php if($etat==2){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste non pourvu";}else{echo "Position not filled";} ?></option>
					<option value="3" <?php if($etat==3){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste pourvu partiellement";}else{echo "Position partially filled";} ?></option>
					<option value="-1" <?php if($etat==-1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Poste annul�";}else{echo "Position canceled";} ?></option>
					<option value="4" <?php if($etat==4){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Demande cl�tur�e";}else{echo "Request closed";} ?></option>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de d�marrage :";}else{echo "Starting date :";} 
				
				$signeDateDemarrage=$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage'];
				if($_POST){$signeDateDemarrage=$_POST['signeDateDemarrage'];}
				$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']=$signeDateDemarrage;
				?>
				<select id="signeDateDemarrage" name="signeDateDemarrage" onchange="submit();">
					<option value='=' <?php if($signeDateDemarrage=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateDemarrage=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateDemarrage==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateDemarrage=$_SESSION['FiltreRecrutAnnonce_DateDemarrage'];
				if($_POST){$dateDemarrage=TrsfDate_($_POST['dateDemarrage']);}
				$_SESSION['FiltreRecrutAnnonce_DateDemarrage']=$dateDemarrage;
				
				?>
				<input id="dateDemarrage" name="dateDemarrage" type="date" value="<?php echo AfficheDateFR($dateDemarrage); ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle">
				<?php
					$mesCandidatures=$_SESSION['FiltreRecrutAnnonce_MesCandidatures'];
					if($_POST){
						if(!empty($_POST['mesCandidatures'])){
							$mesCandidatures="1";
						}
						else{
							$mesCandidatures="0";
						}
					}
					$_SESSION['FiltreRecrutAnnonce_MesCandidatures']=$mesCandidatures;
				?>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mes candidatures :";}else{echo "My applications :";} ?>
				<input type="checkbox" name="mesCandidatures" <?php if($mesCandidatures=="1"){echo "checked";} ?> onchange="submit();"/>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Information :";}else{echo "Information :";} 
				
				$information=$_SESSION['FiltreRecrutAnnonce_Information'];
				if($_POST){$information=$_POST['information'];}
				$_SESSION['FiltreRecrutAnnonce_Information']=$information;
				
				?>
				<input id="information" name="information" type="text" value="<?php echo $information; ?>" size="40"/>&nbsp;&nbsp;
			</td>
			<td>
				<?php if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<a class="Bouton" href="javascript:OuvreFormatExcel()">
				<?php if($_SESSION["Langue"]=="FR"){echo "Offres";}else{echo "Offers";} ?>
				</a><br>
				<a class="Bouton" href="javascript:OuvreFormatExcelCandidature()">
				<?php if($_SESSION["Langue"]=="FR"){echo "Candidatures";}else{echo "Applications";} ?>
				</a>
				<?php } ?>
				<?php if($_SESSION['Id_Personne']==1132 || $_SESSION['Id_Personne']==4320){ ?>
				<a class="Bouton" href="javascript:OuvreFormatExcelMatrice()">
				<?php if($_SESSION["Langue"]=="FR"){echo "Matrice";}else{echo "Matrix";} ?>
				</a>
				<a class="Bouton" href="javascript:OuvreFenetreCV()">
				<?php if($_SESSION["Langue"]=="FR"){echo "CV";}else{echo "CV";} ?>
				</a>
				<?php } ?>
			</td>
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
						IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande cl�tur�e','Poste annul�')))))
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
		$requete2="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre AS NombrePoste,Lieu,Suppr,
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateValidationDG,'%d%m%y')
			) AS Ref,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateRecrutement,DateRecrutementMAJ,EtatPoste,ValidationContratDG,
			".$reqSuite."
			DateBesoin,Duree,PosteDefinitif,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateButoire,DateRecrutementMAJ,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_PersonneAContacter) AS PersonneAContacter,
			(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			Id_Plateforme,
			(SELECT COUNT(Id) FROM talentboost_candidature WHERE Id_Annonce=talentboost_annonce.Id AND talentboost_candidature.Suppr=0) AS Nombre,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation ";
		$requete=" FROM talentboost_annonce
					WHERE Suppr=0  AND ValidationContratDG>0 ";
		if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
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
							OR
								(talentboost_annonce.Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								)
								OR OuvertureAutresPlateformes=1
								)
					  ) ";
					  
		}
		if($_SESSION['FiltreRecrutAnnonce_Plateforme']<>0){
			$requete.=" AND Id_Plateforme=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
		}
		if($_SESSION['FiltreRecrutAnnonce_Metier']<>""){
			$requete.=" AND talentboost_annonce.Metier LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Metier']."%\" ";
		}
		if($_SESSION['FiltreRecrutAnnonce_Domaine']<>0){
			$requete.=" AND talentboost_annonce.Id_Domaine = ".$_SESSION['FiltreRecrutAnnonce_Domaine']." ";
		}
		if($_SESSION['FiltreRecrutAnnonce_Programme']<>"0"){
			$requete.=" AND talentboost_annonce.Programme LIKE \"".$_SESSION['FiltreRecrutAnnonce_Programme']."\" ";
		}
		if($_SESSION['FiltreRecrutAnnonce_Etat']<>-2){
			$requete.=" AND talentboost_annonce.EtatPoste=".$_SESSION['FiltreRecrutAnnonce_Etat']." ";
		}
		if($_SESSION['FiltreRecrutAnnonce_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutAnnonce_DateDemarrage']<>""){
			$requete.=" AND talentboost_annonce.DateBesoin".$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutAnnonce_DateDemarrage']."' ";
		}
		if($_SESSION['FiltreRecrutAnnonce_Information']<>""){
			$requete.=" AND (
				Lieu LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR Diplome LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
				OR Langue LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
			) ";
		}
		if($_SESSION['FiltreRecrutAnnonce_MesCandidatures']=="1"){
			$requete.=" AND (
							SELECT COUNT(talentboost_candidature.Id) 
							FROM talentboost_candidature 
							WHERE talentboost_candidature.Suppr=0
							AND talentboost_candidature.Id_Personne=".$_SESSION['Id_Personne']."
							AND talentboost_candidature.Id_Annonce=talentboost_annonce.Id
							)>0 ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRecrutAnnonce_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutAnnonce_General'],0,-1);
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
		<td align="center" style="font-size:18px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Annonces.php?debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Annonces.php?debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Annonces.php?debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="13%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=Ref"><?php if($_SESSION["Langue"]=="FR"){echo "Ref";}else{echo "Ref";} ?><?php if($_SESSION['TriRecrutAnnonce_Ref']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_Ref']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=DateRecrutement"><?php if($_SESSION["Langue"]=="FR"){echo "Date apparition offre";}else{echo "Offer appearance date";} ?><?php if($_SESSION['TriRecrutAnnonce_DateRecrutement']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_DateRecrutement']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=NombrePoste"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de poste";}else{echo "Number of post";} ?><?php if($_SESSION['TriRecrutAnnonce_NombrePoste']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_NombrePoste']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="18%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "M�tier";}else{echo "Job";} ?><?php if($_SESSION['TriRecrutAnnonce_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="9%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=Lieu"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu";}else{echo "Place";} ?><?php if($_SESSION['TriRecrutAnnonce_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_Lieu']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%"  style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=Domaine"><?php if($_SESSION["Langue"]=="FR"){echo "Domaine";}else{echo "Domain";} ?><?php if($_SESSION['TriRecrutAnnonce_Domaine']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_Domaine']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=DateBesoin"><?php if($_SESSION["Langue"]=="FR"){echo "Date d�marrage";}else{echo "Start date";} ?><?php if($_SESSION['TriRecrutAnnonce_DateBesoin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_DateBesoin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=DateButoire"><?php if($_SESSION["Langue"]=="FR"){echo "Date butoir pour postuler";}else{echo "Deadline for applying";} ?><?php if($_SESSION['TriRecrutAnnonce_DateButoire']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_DateButoire']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=PersonneAContacter"><?php if($_SESSION["Langue"]=="FR"){echo "Personne � contacter";}else{echo "Contact person";} ?><?php if($_SESSION['TriRecrutAnnonce_PersonneAContacter']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_PersonneAContacter']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=Statut"><?php if($_SESSION["Langue"]=="FR"){echo "Statut du poste";}else{echo "Job status";} ?><?php if($_SESSION['TriRecrutAnnonce_Statut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_Statut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-decoration:none;color:#000000;font-weight:bold;" ><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Annonces.php?Tri=Nombre"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de candidats";}else{echo "Number of candidates";} ?><?php if($_SESSION['TriRecrutAnnonce_Nombre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutAnnonce_Nombre']=="ASC"){echo "&darr;";}?></a></td>
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
					
					$couleurPoste="";
					if($row['EtatPoste']==1){$couleurPoste="bgcolor='#66e733'";}
					elseif($row['EtatPoste']==-1){$couleurPoste="bgcolor='#919497'";}
					
					$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +22 day"));
					$JourdateButoir=date("w",strtotime($row['DateButoire']." +22 day"));
					if($JourdateButoir==6){$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +24 day"));}
					if($JourdateButoir==0){$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +23 day"));}

		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Ref']);?></a></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateRecrutement']);?></td>
						<td><?php echo stripslashes($row['NombrePoste']);?></td>
						<td><?php echo stripslashes($row['Metier']);?></td>
						<td><?php echo stripslashes($row['Lieu']);?></td>
						<td><?php echo stripslashes($row['Domaine']);?></td>
						<td><?php 
						if($row['DateBesoin']<date('Y-m-d') && $row['Nombre']==0){echo "<span class='blink_me'>";}
						echo AfficheDateJJ_MM_AAAA($row['DateBesoin']);
						if($row['DateBesoin']<date('Y-m-d') && $row['Nombre']==0){echo "</span>";}
						?>
						</td>
						<td><?php echo $dateButoir;?></td>
						<td><?php echo stripslashes($row['PersonneAContacter']);?></td>
						<td <?php echo $couleurPoste; ?>><?php echo stripslashes($row['Statut2']);?></td>
						<td style="font-size:14px;font-weight:bold;<?php if($row['Nombre']==0){echo "color:red;";}?>" align="center">
						<?php 
						if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						?>
							<a style="<?php if($row['Nombre']==0){echo "color:red;";}else{echo "color:#3e65fa;";}?>" href="javascript:OuvreFenetreCandidature(<?php echo $row['Id']; ?>)">
							<?php echo $row['Nombre'];?>
							</a>
						<?php 
						}
						else{
							echo $row['Nombre'];
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
		<td height="50"></td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>