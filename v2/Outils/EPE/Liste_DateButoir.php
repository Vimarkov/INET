<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvrirTemplate()
		{var w=window.open("OuvrirTemplateDateButoir.php","PageDateButoir","status=no,menubar=no,width=100,height=100");
		w.focus();
		}
	function AjouterDateButoir()
		{var w=window.open("Ajouter_DateButoirNonPrevu.php","PageDateButoir","status=no,menubar=no,width=700,height=300");
		w.focus();
		}
	function MiseEnAttente(Id){
		document.getElementById('Id_Personne').value=Id;
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnMettreEnAttente' name='btnMettreEnAttente' value='MettreEnAttente'>";
		document.getElementById('divMettreEnAttente').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnMettreEnAttente").dispatchEvent(evt);
		document.getElementById('divMettreEnAttente').innerHTML="";
	}
	function MiseEnAttenteNon(Id){
		document.getElementById('Id_Personne').value=Id;
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnMettreEnAttenteNon' name='btnMettreEnAttenteNon' value='PasMettreEnAttente'>";
		document.getElementById('divPasMettreEnAttente').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnMettreEnAttenteNon").dispatchEvent(evt);
		document.getElementById('divPasMettreEnAttente').innerHTML="";
	}
	function SelectionnerTout(){
		var elements = document.getElementsByClassName("check");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
			}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = false;
			}
		}
	}
	function ValiderCheck()
	{
		var elements = document.getElementsByClassName("check");
		Id="";
		ref="";
		for(var i=0, l=elements.length; i<l; i++)
		{
			if(elements[i].checked == true){Id+=elements[i].name+";";}
		}				
	}
</script>
<?php
$ResultatImport="";
$ResultatImport1="";

$DirFichier="Extract_DateButoir.xlsx";
if($_POST){
	if(isset($_POST['ModifierDate']))
	{
		echo "<script>ValiderCheck()</script>";
		
		if(isset($_POST['EPEDateButoir'])){
			$_SESSION['EPE_DateButoir']=implode(";",$_POST['EPEDateButoir']);
		}
		echo '<script>var w= window.open("Modifier_DateButoir.php","PageDateButoir","status=no,menubar=no,width=620,height=200");</script>';
		
	}
	elseif(isset($_POST['btnMettreEnAttente']))
	{
		$req="DELETE FROM epe_personne_attente WHERE Id_Personne=".$_POST['Id_Personne']." AND Annee=".$_SESSION['FiltreEPEDateButoir_Annee']." AND TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."' ";
		$Result=mysqli_query($bdd,$req);
		
		$req="INSERT INTO epe_personne_attente (Id_Personne,DateCreation,Id_Createur,TypeEntretien,Annee) VALUES (".$_POST['Id_Personne'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".$_SESSION['FiltreEPEDateButoir_TypeEPE']."',".$_SESSION['FiltreEPEDateButoir_Annee'].") ";
		$Result=mysqli_query($bdd,$req);
	}
	elseif(isset($_POST['btnMettreEnAttenteNon']))
	{
		$req="DELETE FROM epe_personne_attente WHERE Id_Personne=".$_POST['Id_Personne']." AND Annee=".$_SESSION['FiltreEPEDateButoir_Annee']." AND TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."' ";
		$Result=mysqli_query($bdd,$req);
		
	}
	elseif(isset($_POST['btnImporter'])){
		if($_FILES['fichier']['name']!=""){
			$tmp_file=$_FILES['fichier']['tmp_name'];
			
			if(!is_uploaded_file($tmp_file)){
				if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier est introuvable</td></tr>";}
				else{$ResultatImport.="<tr><td>File not found</td></tr>";}
			}
			else{
				//On verifie l'extension
				$type_file=strrchr($_FILES['fichier']['name'], '.'); 
				if($type_file !='.xlsx'){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier doit être au format .xlsx</td></tr>";}
					else{$ResultatImport.="<tr><td>The file must be in .xlsx format</td></tr>";}
				}
				else
				{
					//On vérifie la taille du fichier
					if(filesize($_FILES['fichier']['tmp_name'])>30000000){
						if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier est trop volumineux</td></tr>";}
						else{$ResultatImport.="<tr><td>The file is too large</td></tr>";}
					}
					else{
						if(file_exists($DirFichier)){
							if(!unlink($DirFichier)){
								
							}
						}
						if(!move_uploaded_file($tmp_file,$DirFichier)){
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Impossible de copier le fichier</td></tr>";}
							else{$ResultatImport.="<tr><td>Could not copy file</td></tr>";}
						}
					}
				}
			}
			
			if($ResultatImport==""){
				class chunkReadFilter implements PHPExcel_Reader_IReadFilter {
					private $_startRow = 0;
					private $_endRow = 0;

					/**  Set the list of rows that we want to read  */ 
					public function setRows($startRow, $chunkSize) { 
						$this->_startRow    = $startRow; 
						$this->_endRow      = $startRow + $chunkSize;
					} 

					public function readCell($column, $row, $worksheetName = '') {
						//  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow 
						if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) { 
						   return true;
						}
						return false;
					} 
				}

				$XLSXDocument = new PHPExcel_Reader_Excel2007();
				
				/**  Define how many rows we want to read for each "chunk"  **/ 
				$chunkSize = 5000;
				/**  Create a new Instance of our Read Filter  **/ 
				$chunkFilter = new chunkReadFilter(); 
				/**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/ 
				$XLSXDocument->setReadFilter($chunkFilter); 
				$chunkFilter->setRows(1,$chunkSize); 
				$objPHPExcel = $XLSXDocument->load($DirFichier); 
				$sheet = $objPHPExcel->getSheet(0);
				
				//Parcours de la première colonne pour vérifier qu'on a toutes les colonnes souhaitées 
				$ColMatriculeAAA="A";
				$ColType="B";
				$ColDateButoir="C";

				$nbLigne=0;
				$nbAjout=0;
				$nbNonAjout=0;
				$nbExisteDeja=0;
				$STOP=0;
				$nbMAJ=0;
				$nbNonMAJ=0;
				
				for($ligne=2;$ligne<4000;$ligne++){
					if($sheet->getCell($ColMatriculeAAA.$ligne)->getValue()==""){$STOP=1;}
					if($STOP==0){
						$nbLigne++;
						//Récupérer les valeurs de chaque champs 
						$MatriculeAAA=utf8_decode($sheet->getCell($ColMatriculeAAA.$ligne)->getValue());
						if($MatriculeAAA<>""){
							if(substr($MatriculeAAA,0,1)=="'"){
								$MatriculeAAA=substr($MatriculeAAA,1);
							}
						}
						$TypeEPE=utf8_decode($sheet->getCell($ColType.$ligne)->getValue());
						
						$DateButoir=$sheet->getCell($ColDateButoir.$ligne)->getValue();

						$Id_Personne=0;
						$Id_PersonneEPE=0;
						$bAjout=1;
						$bExiste=0;

						if($bAjout==1 && $MatriculeAAA==""){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule AAA n'est pas renseigné</td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the AAA number is not filled in</td></tr>";}
						}
						
						if($bAjout==1 && ($TypeEPE<>"EPE" && $TypeEPE<>"EPP" && $TypeEPE<>"EPP Bilan")){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le type d'EPE n'existe pas</td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the type of EPE does not exist</td></tr>";}
						}
						
						//Rechercher si ce matricule existe dans la base de données 
						$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeAAA=\"".$MatriculeAAA."\" OR MatriculeAAA LIKE \"%00".$MatriculeAAA."\" ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta==1){
							$row=mysqli_fetch_array($result);
							$Id_Personne=$row['Id'];
						}
						if($bAjout==1 && $Id_Personne==0){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule ".$MatriculeAAA." n'existe pas </td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$MatriculeAAA." number does not exist </td></tr>";}
						}
						
						$AnneeButoir="0000";
						if($DateButoir<>""){
							$AnneeButoir =  date("Y", PHPExcel_Shared_Date::ExcelToPHP($DateButoir));
							$DateButoir =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($DateButoir));
							
						}
						else{$DateButoir="0001-01-01";}
						
						//Recherche si cette ligne existe déjà dans l'outils 
						$req="SELECT Id FROM epe_personne_datebutoir WHERE Id_Personne=".$Id_Personne." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir))=".$AnneeButoir." AND TypeEntretien='".$TypeEPE."' ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta==1){
							$row=mysqli_fetch_array($result);
							$Id_PersonneEPE=$row['Id'];
						}
						
						//Recherche si la personne appartient à une prestation PSE
						$req="SELECT Id
						FROM new_competences_personne_prestation
						WHERE new_competences_personne_prestation.Id_Personne=".$Id_Personne."
						AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
						AND new_competences_personne_prestation.Id_Prestation IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
						";
						$resultComp=mysqli_query($bdd,$req);
						$nbResultaComp=mysqli_num_rows($resultComp);
						if($bAjout==1 && $nbResultaComp=0){
							$bAjout=0;
							$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule ".$MatriculeAAA." est affecté à une prestation PSE </td></tr>";
						}
						
						if($Id_PersonneEPE==0){
							if($bAjout==1){
								$nbAjout++;
								
								$req="INSERT INTO epe_personne_datebutoir (Id_Personne,DateCreation,Id_Createur,DateButoir,TypeEntretien) 
									VALUES (".$Id_Personne.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".$DateButoir."','".$TypeEPE."')";
								$resultAjout=mysqli_query($bdd,$req);
								$IdCree = mysqli_insert_id($bdd);
							}
							else{
								$nbNonAjout++;
							}
						}
						else{
							if($bAjout==1){
								$nbMAJ++;
								$req="UPDATE epe_personne_datebutoir 
									SET DateCreation='".date('Y-m-d')."', DateReport='".$DateButoir."'
									WHERE Id_Personne=".$Id_Personne." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir))='".$AnneeButoir."' AND TypeEntretien='".$TypeEPE."' ";
								$resultAjout=mysqli_query($bdd,$req);
							}
							else{
								$nbNonMAJ++;
							}
						}
					}
				}
				
				if($nbAjout>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbAjout." ligne(s) ajoutée(s) / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbAjout." lines added / ".$nbLigne." </td></tr>";}
				}
				if($nbNonAjout>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbNonAjout." ligne(s) non ajoutée(s) / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbNonAjout." lines not added / ".$nbLigne." </td></tr>";}
				}
				if($nbMAJ>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbMAJ." lignes mises à jour / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbMAJ." lines updated / ".$nbLigne." </td></tr>";}
				}
				if($nbNonMAJ>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbNonMAJ." lignes non mises à jour / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbNonMAJ." lines not updated / ".$nbLigne." </td></tr>";}
				}
				
				//Free up some of the memory 
				$objPHPExcel->disconnectWorksheets(); 
				unset($objPHPExcel);
			}
		}
	}
}

?>

<form id="formulaire" class="test" enctype="multipart/form-data" action="Liste_DateButoir.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<div id="divMettreEnAttente"></div>
	<div id="divPasMettreEnAttente"></div>
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Dates butoirs";}else{echo "Deadline";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="60%" valign="top">
			<table width="100%">
				<tr>
					<td width="100%">
						<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td width="20%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?>
									<select style="width:100px;" name="plateforme" onchange="submit();">
									<?php
									$requetePlateforme="
										SELECT Id, Libelle
										FROM new_competences_plateforme
										WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
										ORDER BY Libelle ASC";
									$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
									$nbPlateforme=mysqli_num_rows($resultPlateforme);
									
									$Plateforme=$_SESSION['FiltreEPEDateButoir_Plateforme'];
									if($_POST){$Plateforme=$_POST['plateforme'];}
									$_SESSION['FiltreEPEDateButoir_Plateforme']=$Plateforme;	
									
									echo "<option name='0' value='0' Selected></option>";
									if ($nbPlateforme > 0)
									{
										while($row=mysqli_fetch_array($resultPlateforme))
										{
											$selected="";
											if($Plateforme<>""){if($Plateforme==$row['Id']){$selected="selected";}}
											echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
										}
									 }
									 ?>
									</select>
								</td>
								<td width="12%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
									<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
									<?php
									$requeteSite="
										SELECT Id, Libelle
										FROM new_competences_prestation
										WHERE Active=0
										AND Id_Plateforme=".$Plateforme."
										ORDER BY Libelle ASC";
									$resultPrestation=mysqli_query($bdd,$requeteSite);
									$nbPrestation=mysqli_num_rows($resultPrestation);
									
									$Prestation=$_SESSION['FiltreEPEDateButoir_Prestation'];
									if($_POST){$Prestation=$_POST['prestations'];}
									$_SESSION['FiltreEPEDateButoir_Prestation']=$Prestation;	
									
									echo "<option name='0' value='0' Selected></option>";
									if ($nbPrestation > 0)
									{
										while($row=mysqli_fetch_array($resultPrestation))
										{
											$selected="";
											if($Prestation<>""){if($Prestation==$row['Id']){$selected="selected";}}
											echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
										}
									 }
									 ?>
									</select>
								</td>
								<td width="15%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
									<select class="pole" style="width:100px;" name="pole" onchange="submit();">
									<?php
									$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
										FROM new_competences_pole
										LEFT JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										WHERE Actif=0
										AND new_competences_pole.Id_Prestation=".$Prestation."
										ORDER BY new_competences_pole.Libelle ASC";
									$resultPole=mysqli_query($bdd,$requetePole);
									$nbPole=mysqli_num_rows($resultPole);
									
									$Pole=$_SESSION['FiltreEPEDateButoir_Pole'];
									if($_POST){$Pole=$_POST['pole'];}
									$_SESSION['FiltreEPEDateButoir_Pole']=$Pole;
									
									$Selected = "";
									echo "<option name='0' value='0' Selected></option>";
									if ($nbPole > 0)
									{
										while($row=mysqli_fetch_array($resultPole))
										{
											$selected="";
											if($Pole<>"")
											{if($Pole==$row['Id']){$selected="selected";}}
											echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
										}
									 }
									 ?>
									</select>
								</td>
								<?php
								$personne=$_SESSION['FiltreEPEDateButoir_Personne'];
								if($_POST){$personne=$_POST['personne'];}
								$_SESSION['FiltreEPEDateButoir_Personne']=$personne;
								?>
								<td valign="top" width="15%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
									<select id="personne" style="width:100px;" name="personne" onchange="submit();">
										<option value='0'></option>
										<?php

											$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
													CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
													FROM new_rh_etatcivil
														LEFT JOIN epe_personne_datebutoir 
														ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
														WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
														OR 
															(SELECT COUNT(Id)
															FROM epe_personne 
															WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEDateButoir_Annee'].")>0
														) 
														AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
													WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
													AND new_rh_etatcivil.Id<>1739
													AND (
														(
															SELECT COUNT(new_competences_personne_prestation.Id)
															FROM new_competences_personne_prestation
															LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
															WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
															AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
															AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
															AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
															AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)";
															if($_SESSION['FiltreEPEDateButoir_Plateforme']<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPEDateButoir_Plateforme']." ";}
															if($_SESSION['FiltreEPEDateButoir_Prestation']<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPEDateButoir_Prestation']." ";}
															if($_SESSION['FiltreEPEDateButoir_Pole']<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPEDateButoir_Pole']." ";}
											$requetePersonne.="
														)>0)
											 ";
											$requetePersonne.="ORDER BY Personne ASC";
											$resultPersonne=mysqli_query($bdd,$requetePersonne);
											$NbPersonne=mysqli_num_rows($resultPersonne);
											
											$personne=$_SESSION['FiltreEPEDateButoir_Personne'];
											if($_POST){$personne=$_POST['personne'];}
											$_SESSION['FiltreEPEDateButoir_Personne']= $personne;
											
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
									<?php
										$SansDate=$_SESSION['FiltreEPEDateButoir_SansDate'];
										if($_POST){
											if(isset($_POST['SansDate'])){$SansDate="checked";}else{$SansDate="";}				
										}
										$_SESSION['FiltreEPEDateButoir_SansDate']=$SansDate;
									?>
									<input type="checkbox" id="SansDate" name="SansDate" value="SansDate" <?php echo $SansDate; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Sans date butoir";}else{echo "No deadline";} ?> &nbsp;&nbsp;
									<?php
										$NA=$_SESSION['FiltreEPEDateButoir_NA'];
										if($_POST){
											if(isset($_POST['NA'])){$NA="checked";}else{$NA="";}				
										}
										$_SESSION['FiltreEPEDateButoir_NA']=$NA;
									?>
									<input type="checkbox" id="NA" name="NA" value="NA" <?php echo $NA; ?>><?php if($_SESSION["Langue"]=="FR"){echo "N/A";}else{echo "N/A";} ?> &nbsp;&nbsp;
								</td>
								<td width="20%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type :";}else{echo "Type :";} ?>
									<select class="type" name="type" onchange="submit();">
									<?php
										
										$type=$_SESSION['FiltreEPEDateButoir_TypeEPE'];
										if($_POST){$type=$_POST['type'];}
										$_SESSION['FiltreEPEDateButoir_TypeEPE']= $type;
									?>
										<option value="EPE" <?php if($type=="EPE"){echo "selected";} ?>>EPE</option>
										<option value="EPP" <?php if($type=="EPP"){echo "selected";} ?>>EPP</option>
										<option value="EPP Bilan" <?php if($type=="EPP Bilan"){echo "selected";} ?>>EPP Bilan</option>
									</select>
								</td>
								<?php
								$annee=$_SESSION['FiltreEPEDateButoir_Annee'];
								if($_POST){$annee=$_POST['annee'];}
								if($annee==""){$annee=date("Y");}
								$_SESSION['FiltreEPEDateButoir_Annee']=$annee;
								
								$anneeEmbauche=$_SESSION['FiltreEPEDateButoir_AnneeEmbauche'];
								if($_POST){$anneeEmbauche=$_POST['anneeEmbauche'];}
								$_SESSION['FiltreEPEDateButoir_AnneeEmbauche']=$anneeEmbauche;
								?>
								<td width="10%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
									<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
								</td>
								<td width="10%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année embauche:";}else{echo "Year hired :";} ?>
									<input onKeyUp="nombre(this)" id="anneeEmbauche" name="anneeEmbauche" type="texte" value="<?php echo $anneeEmbauche; ?>" size="5"/>&nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td width="15%" class="Libelle">
									<?php
										$EnAttente=$_SESSION['FiltreEPEDateButoir_EnAttente'];
										if($_POST){
											if(isset($_POST['EnAttente'])){$EnAttente="checked";}else{$EnAttente="";}				
										}
										$_SESSION['FiltreEPEDateButoir_EnAttente']=$EnAttente;
									?>
									<input type="checkbox" id="EnAttente" name="EnAttente" value="EnAttente" <?php echo $EnAttente; ?>><?php if($_SESSION["Langue"]=="FR"){echo "En attente";}else{echo "Waiting";} ?> &nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td align="right">
						<input class="Bouton" type="button"  onclick="AjouterDateButoir();" value="<?php if($_SESSION["Langue"]=="FR"){echo "Date butoir non prévue";}else{echo "Deadline not planned";}?>">
					</td>
				</tr>
				<?php
					$dateCloture="";
					$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPEDateButoir_Annee']." ";
					$resultDateCloture=mysqli_query($bdd,$req);
					$nbDateCloture=mysqli_num_rows($resultDateCloture);
					if($nbDateCloture>0){
						$rowDateCloture=mysqli_fetch_array($resultDateCloture);
						$dateCloture=$rowDateCloture['DateCloture'];
					}
					
					$TypeEntretien="";
					if($_SESSION['FiltreEPEDateButoir_SansDate']==""){$TypeEntretien=",TypeEntretien";}
					$requeteAnalyse="SELECT DISTINCT new_rh_etatcivil.Id".$TypeEntretien.",
					(SELECT IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir) 
						FROM epe_personne_datebutoir AS TAB 
						WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
						AND YEAR(IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." 
						AND TAB.TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."' LIMIT 1
					) AS DateButoir ";
					$requete2="SELECT DISTINCT new_rh_etatcivil.Id, 
					CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
					MatriculeAAA".$TypeEntretien.",
					(SELECT IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
					AND YEAR(IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TAB.TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."' LIMIT 1) AS DateButoir,
					DateAncienneteCDI,
					(SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE epe_personne_na.Id_Personne=new_rh_etatcivil.Id AND epe_personne_na.Annee=".$_SESSION['FiltreEPEDateButoir_Annee']."
					AND epe_personne_na.TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."') AS NA,
					(SELECT (SELECT Libelle FROM epe_motifnonrealisation WHERE Id=Id_MotifNonRealisation) FROM epe_personne_na WHERE epe_personne_na.Id_Personne=new_rh_etatcivil.Id AND epe_personne_na.Annee=".$_SESSION['FiltreEPEDateButoir_Annee']."
					AND epe_personne_na.TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."' LIMIT 1) AS MotifNonRealisation,
					(SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id AND epe_personne_attente.Annee=".$_SESSION['FiltreEPEDateButoir_Annee']."
					AND epe_personne_attente.TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."') AS EnAttente,
					(SELECT COUNT(epe_personne.Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEDateButoir_Annee']." 
					) AS EPECree
					";
					$requete="FROM new_rh_etatcivil
						LEFT JOIN epe_personne_datebutoir 
						ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
						WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
						OR 
							(SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEDateButoir_Annee'].")>0
						) 
						AND new_rh_etatcivil.Id<>1739						
						AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
					WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
						";
					if($_SESSION['FiltreEPEDateButoir_TypeEPE']=="EPP Bilan"){
						if($_SESSION['FiltreEPEDateButoir_Annee']>=2022){
							if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
							$requete.=" AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEDateButoir_Annee'].'-m-d')." -6 year"))."'
										AND (SELECT COUNT(Id)
										FROM epe_personne 
										WHERE Suppr=0 
										AND epe_personne.Type='EPP Bilan' 
										AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
										AND ModeBrouillon=0 
										AND YEAR(DateEntretien) >= ".date('Y',strtotime(date($_SESSION['FiltreEPEDateButoir_Annee'].'-m-d')." -5 year"))."
									)=0
								";
							}
						}
						else{
							if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
								$requete.=" AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEDateButoir_Annee'].'-m-d')." -6 year"))."' ";
							}
						}
					}
					elseif($_SESSION['FiltreEPEDateButoir_TypeEPE']=="EPP" && $_SESSION['FiltreEPEDateButoir_Annee']>=2022){
						if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
						$requete.=" 
								AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEDateButoir_Annee'].'-m-d')." -2 year"))."' 	
								AND (SELECT COUNT(Id)
									FROM epe_personne 
									WHERE Suppr=0 
									AND epe_personne.Type='EPP' 
									AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
									AND ModeBrouillon=0 
									AND YEAR(DateEntretien) >= ".date('Y',strtotime(date($_SESSION['FiltreEPEDateButoir_Annee'].'-m-d')." -1 year"))."
								)=0
							";
						}
					}

					if($_SESSION['FiltreEPEDateButoir_AnneeEmbauche']<>""){
						$requete.=" AND YEAR(DateAncienneteCDI)='".$_SESSION['FiltreEPEDateButoir_AnneeEmbauche']."' ";
					}
					if($dateCloture<>"" && $dateCloture>"0001-01-01"){
						$requete.=" AND DateAncienneteCDI<'".$dateCloture."' ";
					}
					//Vérifier si appartient à une prestation OPTEA ou compétence
					$requete.="
						AND (
						(
							SELECT COUNT(new_competences_personne_prestation.Id)
							FROM new_competences_personne_prestation
							LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
							AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
							AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)";
							if($_SESSION['FiltreEPEDateButoir_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPEDateButoir_Plateforme']." ";}
							if($_SESSION['FiltreEPEDateButoir_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPEDateButoir_Prestation']." ";}
							if($_SESSION['FiltreEPEDateButoir_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPEDateButoir_Pole']." ";}
			$requete.="
						)>0) ";
					
					if($_SESSION['FiltreEPEDateButoir_Personne']<>"0"){
						$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPEDateButoir_Personne']." ";
					}
					if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
						$requete.="AND (SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
						AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." AND TAB.TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."')=0  ";
					}
					else{
						$requete.="AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEDateButoir_Annee']." ";
						$requete.="AND TypeEntretien = '".$_SESSION['FiltreEPEDateButoir_TypeEPE']."' ";
					}
					if($_SESSION['FiltreEPEDateButoir_NA']<>""){
						$requete.="AND (SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE epe_personne_na.Id_Personne=new_rh_etatcivil.Id AND epe_personne_na.Annee=".$_SESSION['FiltreEPEDateButoir_Annee']."
							AND epe_personne_na.TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."')>0  ";
					}
					if($_SESSION['FiltreEPEDateButoir_EnAttente']<>""){
						$requete.="AND (SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id AND epe_personne_attente.Annee=".$_SESSION['FiltreEPEDateButoir_Annee']."
							AND epe_personne_attente.TypeEntretien='".$_SESSION['FiltreEPEDateButoir_TypeEPE']."')>0  ";
					}
					$result=mysqli_query($bdd,$requeteAnalyse.$requete);
					$requete.="ORDER BY Personne ";
					
					if(isset($_GET['Page'])){$page=$_GET['Page'];}
					else{$page=0;}
					$requete3=" LIMIT ".($page*40).",40";
					$nbResulta=mysqli_num_rows($result);

					$result=mysqli_query($bdd,$requete2.$requete.$requete3);
					$nombreDePages=ceil($nbResulta/40);
					$couleur="#FFFFFF";

					?>
					<tr>
						<td align="center" style="font-size:14px;">
							<?php
								$nbPage=0;
								if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_DateButoir.php?debut=1&Page=0'><<</a> </b>";}
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
											echo "<b> <a style='color:#00599f;' href='Liste_DateButoir.php?debut=1&Page=".($i-1)."'>".$i."</a> </b>";
										}
									}
								}
								if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_DateButoir.php?debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<table class="TableCompetences" align="center" width="95%">
								<tr>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date butoir";}else{echo "Deadline";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Motif<br>non réalisation";}else{echo "Reason<br>non-achievement";} ?></td>
									<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "En attente";}else{echo "Waiting";} ?></td>
									<td class='EnTeteTableauCompetences' width="5%" align="center">
										<input class="Bouton" style="cursor: pointer;" name="ModifierDate" title="<?php if($LangueAffichage=="FR"){echo "Modifier";}else{echo "Edit";}?>" size="3" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Modifier";}else{echo "Edit";}?>"><br>
										<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" />
									</td>
									<td class='EnTeteTableauCompetences' width="5%" align="center"></td>
								</tr>
					<?php			
							if($nbResulta>0){
								while($row=mysqli_fetch_array($result))
								{
									if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
									else{$couleur="#FFFFFF";}
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
										<td><?php echo stripslashes($row['Personne']);?></td>
										<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAncienneteCDI']);?></td>
										<td><?php echo stripslashes($_SESSION['FiltreEPEDateButoir_TypeEPE']); ?></td>
										<td><?php if($_SESSION['FiltreEPEDateButoir_SansDate']==""){echo AfficheDateJJ_MM_AAAA($row['DateButoir']);}
										else{
											if($row['NA']>0){
												echo "N/A";
											}
										} 
										?></td>
										<td><?php if($_SESSION['FiltreEPEDateButoir_SansDate']==""){echo "";}
										else{
											if($row['NA']>0){
												echo $row['MotifNonRealisation'];
											}
										} 
										?></td>
										<td><?php if($_SESSION['FiltreEPEDateButoir_SansDate']==""){if($row['EnAttente']>0){echo "X";}}?></td>
										<td>
											<?php
												if($row['EPECree']==0){
													echo "<input class='check' type='checkbox' name='EPEDateButoir[]' value='".$row['Id']."' value=''>";
												}
											?>
										</td>
										<td>
										<?php if($_SESSION['FiltreEPEDateButoir_SansDate']==""){
											if($row['EnAttente']==0){
												if($row['EPECree']==0){
										?>
										<input class="Bouton" style="cursor: pointer;" name="MettreEnAttente" size="3" type="button" onclick="MiseEnAttente(<?php echo $row['Id']; ?>);" value="<?php if($LangueAffichage=="FR"){echo "Mettre en attente";}else{echo "Put on hold";}?>">
										<?php
												}
											}
											else{
										?>
										<input class="Bouton" style="cursor: pointer;background-color:#2c2da3;" name="PasMettreEnAttente" size="3" type="button" onclick="MiseEnAttenteNon(<?php echo $row['Id']; ?>);" value="<?php if($LangueAffichage=="FR"){echo "Enlever attente";}else{echo "Remove wait ";}?>">
										<?php
											}
										}
										?>
										</td>
									</tr>
								<?php 
								}
							}
								?>
							</table>
						</td>
					</tr>
			</table>
		</td>
		<td width="40%"  valign="top">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="5"></td></tr>
				<tr>
					<td class="Libelle" width="30%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier date butoir";}else{echo "Deadline file";}?> : </td>
					<td width="10%"><input name="fichier" type="file" onChange="CheckFichier();"></td>
					<td width="40%">
						<input class="Bouton" type="submit" id="btnImporter" name="btnImporter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
					</td>
					<td width="20%">
						<input class="Bouton" type="button"  onclick="OuvrirTemplate();" value="<?php if($_SESSION["Langue"]=="FR"){echo "Template pour l'import";}else{echo "Template for import";}?>">
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td colspan="6">
						<table width="100%" cellpadding="0" cellspacing="0" align="center">
							<?php
								echo $ResultatImport1;
							?>	
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td colspan="6">
						<div id='Div_Erreurs' style='height:400px;width:100%;overflow:auto;'>
						<table width="100%" cellpadding="0" cellspacing="0" align="center">
							<?php
								echo $ResultatImport;
							?>	
						</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion

?>
	
</body>
</html>