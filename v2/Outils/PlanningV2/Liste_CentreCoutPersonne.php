<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Id)
	{
		var w= window.open("Modif_CentreCoutPersonne.php?Id="+Id,"PageCC","status=no,menubar=no,width=450,height=200");
		w.focus();
	}
	function OuvrirTemplate()
	{var w=window.open("OuvrirTemplateCC.php","PageCC","status=no,menubar=no,width=100,height=100");
	w.focus();
	}
	function OuvrirTemplateInterim()
	{var w=window.open("OuvrirTemplateCCInterim.php","PageCC","status=no,menubar=no,width=100,height=100");
	w.focus();
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$ResultatImport="";
$DirFichier="CentreDeCout/Extract_CCPersonne.xlsx";
$DirFichierInterim="CentreDeCout/Extract_CCInterim.xlsx";

if($_POST){
	if(isset($_POST['btnImporter'])){
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
				$ColMatriculeDaher="A";
				$ColCC="B";

				$nbLigne=0;
				$nbMAJ=0;
				$nbNonTrouve=0;
				$STOP=0;
				for($ligne=2;$ligne<8000;$ligne++){
					if($sheet->getCell($ColMatriculeDaher.$ligne)->getValue()==""){$STOP=1;}
					if($STOP==0){
						$nbLigne++;
						//Récupérer les valeurs de chaque champs 
						$MatriculeDaher=utf8_decode($sheet->getCell($ColMatriculeDaher.$ligne)->getValue());
						if($MatriculeDaher<>""){
							if(substr($MatriculeDaher,0,1)=="_"){
								$MatriculeDaher=substr($MatriculeDaher,1);
							}
						}
						$CC=utf8_decode($sheet->getCell($ColCC.$ligne)->getValue());

						$Id_Personne=0;

						$bAjout=1;
						$bExiste=0;

						if($bAjout==1 && $MatriculeDaher==""){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule Daher n'est pas renseigné</td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the Daher number is not filled in</td></tr>";}
						}

						//Rechercher si ce matricule existe dans la base de données 
						$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeDaher=".$MatriculeDaher." ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta==1){
							$row=mysqli_fetch_array($result);
							$Id_Personne=$row['Id'];
						}
						if($bAjout==1 && $Id_Personne==0){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule Daher ".$MatriculeDaher." n'existe pas </td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$MatriculeDaher." Daher number does not exist </td></tr>";}
						}

						if($bAjout==1){
							$nbMAJ++;
							
							$req="UPDATE new_rh_etatcivil SET CentreDeCout='".$CC."' WHERE Id=".$Id_Personne;
							$result=mysqli_query($bdd,$req);
						}
						else{
							$nbNonTrouve++;
						}
					}
				}
				
				if($nbMAJ>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbMAJ." ligne(s) mise(s) à jour / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbMAJ." updated line(s) / ".$nbLigne." </td></tr>";}
				}
				if($nbNonTrouve>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbNonTrouve." matricule(s) non trouvé(s) / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbNonTrouve." number not found / ".$nbLigne." </td></tr>";}
				}

				//Free up some of the memory 
				$objPHPExcel->disconnectWorksheets(); 
				unset($objPHPExcel);
			}
		}
	}
	elseif(isset($_POST['btnImporterInterim'])){
		if($_FILES['fichierDSK']['name']!=""){
			$tmp_file=$_FILES['fichierDSK']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){
				if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier est introuvable</td></tr>";}
				else{$ResultatImport.="<tr><td>File not found</td></tr>";}
			}
			else{
				//On verifie l'extension
				$type_file=strrchr($_FILES['fichierDSK']['name'], '.'); 
				if($type_file !='.xlsx'){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>Le fichier doit être au format .xlsx</td></tr>";}
					else{$ResultatImport.="<tr><td>The file must be in .xlsx format</td></tr>";}
				}
				else
				{
					//On vérifie la taille du fichier
					if(filesize($_FILES['fichierDSK']['tmp_name'])>30000000){
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
				$ColMatriculeDaher="A";
				$ColCC="B";

				$nbLigne=0;
				$nbMAJ=0;
				$nbNonTrouve=0;
				$STOP=0;
				for($ligne=2;$ligne<8000;$ligne++){
					if($sheet->getCell($ColMatriculeDaher.$ligne)->getValue()==""){$STOP=1;}
					if($STOP==0){
						$nbLigne++;
						//Récupérer les valeurs de chaque champs 
						$MatriculeDSK=utf8_decode($sheet->getCell($ColMatriculeDaher.$ligne)->getValue());
						$CC=utf8_decode($sheet->getCell($ColCC.$ligne)->getValue());

						$Id_Personne=0;

						$bAjout=1;
						$bExiste=0;

						if($bAjout==1 && $MatriculeDSK==""){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule DSK n'est pas renseigné</td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the DSK number is not filled in</td></tr>";}
						}

						//Rechercher si ce matricule existe dans la base de données 
						$req="SELECT Id FROM new_rh_etatcivil WHERE MatriculeDSK=".$MatriculeDSK." ";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta==1){
							$row=mysqli_fetch_array($result);
							$Id_Personne=$row['Id'];
						}
						if($bAjout==1 && $Id_Personne==0){
							$bAjout=0;
							if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>La ligne n°".$ligne." n'a pas été rajoutée car le matricule DSK ".$MatriculeDSK." n'existe pas </td></tr>";}
							else{$ResultatImport.="<tr><td>Line ".$ligne." has not been added because the ".$MatriculeDSK." DSK number does not exist </td></tr>";}
						}

						if($bAjout==1){
							$nbMAJ++;
							
							$req="UPDATE new_rh_etatcivil SET CentreDeCout='".$CC."' WHERE Id=".$Id_Personne;
							$result=mysqli_query($bdd,$req);
						}
						else{
							$nbNonTrouve++;
						}
					}
				}
				
				if($nbMAJ>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbMAJ." ligne(s) mise(s) à jour / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbMAJ." updated line(s) / ".$nbLigne." </td></tr>";}
				}
				if($nbNonTrouve>0){
					if($_SESSION["Langue"]=="FR"){$ResultatImport.="<tr><td>".$nbNonTrouve." matricule(s) non trouvé(s) / ".$nbLigne." </td></tr>";}
					else{$ResultatImport.="<tr><td".$nbNonTrouve." number not found / ".$nbLigne." </td></tr>";}
				}

				//Free up some of the memory 
				$objPHPExcel->disconnectWorksheets(); 
				unset($objPHPExcel);
			}
		}
	}
}
?>

<form class="test" enctype="multipart/form-data" action="Liste_CentreCoutPersonne.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="listeReleves" id="listeReleves" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#da94d0;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Centre de coût du personnel";}else{echo "Personnel cost center";}
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
			<td width="11%" class="Libelle">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Centre de coût renseigné :";}else{echo "Informed cost center :";} ?>
							<select id="ccRenseigne" name="ccRenseigne" >
								<?php
									$arrayValeur=array("0","1","-1");
									$arrayNom=array("","Oui","Non");
									
									$ccRenseigne=$_SESSION['FiltreRHCC_CC'];
									if($_POST){$ccRenseigne=$_POST['ccRenseigne'];}
									$_SESSION['FiltreRHCC_CC']=$ccRenseigne;
									
									for($i=0;$i<=2;$i++){
										echo "<option value='".$arrayValeur[$i]."'";
										if($ccRenseigne== $arrayValeur[$i]){echo " selected ";}
										echo ">".$arrayNom[$i]."</option>\n";
									}
								?>
							</select>
						</td>
						<td>
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Z-SORTIE :";}else{echo "Z-SORTIE :";} ?>
							<select id="zSortie" name="zSortie" >
								<?php
									$arrayValeur=array("0","1","-1");
									$arrayNom=array("","Oui","Non");
									
									$zSortie=$_SESSION['FiltreRHCC_Sortie'];
									if($_POST){$zSortie=$_POST['zSortie'];}
									$_SESSION['FiltreRHCC_Sortie']=$zSortie;
									
									for($i=0;$i<=2;$i++){
										echo "<option value='".$arrayValeur[$i]."'";
										if($zSortie== $arrayValeur[$i]){echo " selected ";}
										echo ">".$arrayNom[$i]."</option>\n";
									}
								?>
							</select>
						</td>
						<td>
							<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
							<div id="filtrer"></div>
							<div id="charger"></div>
						</td>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<?php
			$req = "SELECT Id,
				CONCAT(Nom,' ',Prenom) AS Personne,
				MatriculeDaher,MatriculeAAA,MatriculeDSK,
				CentreDeCout
			FROM new_rh_etatcivil
			WHERE (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme WHERE Id_Plateforme NOT IN (11,14) AND Id_Personne=new_rh_etatcivil.Id)>0 ";
			if($_SESSION['FiltreRHCC_CC']==1){
				$req.=" AND CentreDeCout<>'' ";
			}
			elseif($_SESSION['FiltreRHCC_CC']==-1){
				$req.=" AND CentreDeCout='' ";
			}
			if($_SESSION['FiltreRHCC_Sortie']==1){
				$req.=" AND (SELECT COUNT(Id_Personne) FROM new_competences_personne_plateforme WHERE Id_Personne=new_rh_etatcivil.Id AND Id_Plateforme=14)>0 ";
			}
			elseif($_SESSION['FiltreRHCC_Sortie']==-1){
				$req.=" AND (SELECT COUNT(Id_Personne) FROM new_competences_personne_plateforme WHERE Id_Personne=new_rh_etatcivil.Id AND Id_Plateforme=14)=0 ";
			}
			$req .= "ORDER BY Personne ASC";

			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			$couleur="#FFFFFF";
		?>
		<tr>
			<td width="60%" valign="top">
				<table width="100%">
					<tr>
						<td>
							<div id='Div_EnTete' align="center" style='width:99%;'>
							<table class="TableCompetences" align="center" width="100%">
								<tr>
									<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
									<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule AAA";}else{echo "AAA number";} ?></td>
									<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule Daher";}else{echo "Daher number";} ?></td>
									<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule DSK";}else{echo "DSK number";} ?></td>
									<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Centre de coût";}else{echo "Cost center";} ?></td>
									<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestations";}else{echo "Sites";} ?></td>
									<td class="EnTeteTableauCompetences" width="5%"></td>
								</tr>
							</table>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Personnes' align="center" style='height:500px;width:100%;overflow:auto;'>
							<table class="TableCompetences" align="center" width="100%">
						<?php
							if($nbResulta>0){
								while($row=mysqli_fetch_array($result))
								{
									$CC="";
									if($row['MatriculeDSK']<>'' && $row['MatriculeDaher']=='' && $row['CentreDeCout']==''){
										//Trouver le centre de coût des intérimaires (correspond à la prestation du contrat initial
										//Récupérer le contrat E/C 
										$CC = CentreDeCoutContratInitial($row['Id'],date('Y-m-d'));
										
										if($CC<>""){
											$req="UPDATE new_rh_etatcivil SET CentreDeCout='".$CC."' WHERE Id=".$row['Id']." ";
											$resultUpdt=mysqli_query($bdd,$req);
										}
										else{
											//Centre de coût du dernier contrat initial
											$CC = CentreDeCoutContratInitialDernierContrat($row['Id']);	
											
											if($CC<>""){
												$req="UPDATE new_rh_etatcivil SET CentreDeCout='".$CC."' WHERE Id=".$row['Id']." ";
												$resultUpdt=mysqli_query($bdd,$req);
											}											
										}
										
									}
									
									if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
									else{$couleur="#FFFFFF";}
									?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td width="8%"><?php echo stripslashes($row['Personne']);?></td>
										<td width="6%"><?php echo stripslashes($row['MatriculeAAA']);?></td>
										<td width="6%"><?php echo stripslashes($row['MatriculeDaher']);?></td>
										<td width="6%"><?php echo stripslashes($row['MatriculeDSK']);?></td>
										<td width="15%"><?php echo stripslashes($row['CentreDeCout']);?></td>
										<td width="15%">
										<?php 
											$Presta="";
											
											$req="SELECT DISTINCT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation 
												FROM new_competences_personne_prestation
												WHERE Id_Personne=".$row['Id']."
												AND Date_Debut<='".date('Y-m-d')."'
												AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
												";
											$resultPresta=mysqli_query($bdd,$req);
											$nbResultaPresta=mysqli_num_rows($resultPresta);
											if($nbResultaPresta>0){
												while($rowPresta=mysqli_fetch_array($resultPresta))
												{
													$Presta.=$rowPresta['Prestation']."<br>";
												}
											}
											
											echo $Presta;
										?>
										</td>
										<td width="5%">
											<a class="Modif" href="javascript:OuvreFenetreModif('<?php echo $row['Id']; ?>');">
												<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
											</a>
										</td>
									</tr>
									<?php
								}
							}

							?>
							</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
			<td width="40%" valign="top">
				<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
					<tr><td height="5"></td></tr>
					<tr>
						<td width="20%" colspan="3" align="right">
							<input class="Bouton" type="button"  onclick="OuvrirTemplate();" value="<?php if($_SESSION["Langue"]=="FR"){echo "Template pour l'import des centres de coûts des salariés";}else{echo "Template for importing employee cost centers";}?>">
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td class="Libelle" width="30%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier centre de coût des salariés";}else{echo "Employee cost center file";}?> : </td>
						<td width="30%"><input name="fichier" type="file" onChange="CheckFichier();"></td>
						<td width="30%">
							<input class="Bouton" type="submit" id="btnImporter" name="btnImporter" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td width="20%" colspan="3" align="right">
							<input class="Bouton" type="button"  onclick="OuvrirTemplateInterim();" value="<?php if($_SESSION["Langue"]=="FR"){echo "Template pour l'import des centres de coûts des intérimaires";}else{echo "Template for importing temporary workers' cost centers";}?>">
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td class="Libelle" width="30%">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Fichier centre de coût des intérimaires";}else{echo "Cost center file for temporary workers";}?> : </td>
						<td width="30%"><input name="fichierDSK" type="file" onChange="CheckFichier();"></td>
						<td width="30%">
							<input class="Bouton" type="submit" id="btnImporterInterim" name="btnImporterInterim" value="<?php if($_SESSION["Langue"]=="FR"){echo "Importer";}else{echo "Import";}?>">
						</td>
					</tr>
					<tr><td height="4"></td></tr>
					<tr>
						<td colspan="6">
							<table width="100%" cellpadding="0" cellspacing="0" align="center">
								<?php
									echo $ResultatImport;
								?>	
							</table>
						</td>
					</tr>
					<tr><td height="4"></td></tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>