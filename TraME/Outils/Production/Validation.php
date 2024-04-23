<!DOCTYPE html>
<html>
	<head>
		<title>TraME</title><meta name="robots" content="noindex">
		<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
		<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../JS/date.js"></script>
		<script type="text/javascript" src="../JS/jquery.min.js"></script>
		<!-- HTML5 Shim -->
		<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
		<!-- Modernizr -->
		<script src="../JS/modernizr.js"></script>
		<!-- jQuery  -->
		<script src="../JS/js/jquery-1.4.3.min.js"></script>
		<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
		<script type="text/javascript" src="../../JS/jquery.min.js"></script>
		<script type="text/javascript" src="ValidationListe.js"></script>
		<?php
			require("../../Menu.php");
			require("../Fonctions.php");
		?>	
	</head>
	<body>	
		<?php
			$_SESSION['Formulaire']="Production/Validation.php";
			if($_POST){
				if(isset($_POST['Recherche_RAZ'])){
					$tab =array("Reference","DateDebut","DateFin","WP","FamilleTache","Tache","MotCles","Preparateur","Controleur","Statut","Delai","PageDateDebut","PageDateFin","PagePreparateur","PageWP","FamilleTache");
					foreach($tab as $value){
						ViderFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',$value);
						
						$_SESSION['VALI_'.$value]="";
						$_SESSION['VALI_'.$value.'2']="";
					}
					
					$_SESSION['VALI_ModeFiltre']="oui";
					$_SESSION['VALI_Page']="0";
				}
				elseif(isset($_POST['BtnRechercher'])){
					$_SESSION['VALI_PageDateDebut2']=$_POST['dateDebut'];
					$_SESSION['VALI_PageDateFin2']=$_POST['dateFin'];
					$_SESSION['VALI_PagePreparateur2']=$_POST['preparateur'];
					$_SESSION['VALI_PageWP2']=$_POST['wp'];
					$_SESSION['VALI_ModeFiltre']="oui";
					
					$tab =array("PageDateDebut","PageDateFin","PagePreparateur","PageWP");
					foreach($tab as $value){
						$req="UPDATE trame_parametrage 
						SET Valeur='".addslashes($_SESSION['PROD_'.$value])."', Valeur2='".addslashes($_SESSION['PROD_'.$value.'2'])."'
						WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
						AND Id_Personne=".$_SESSION['Id_PersonneTR']." 
						AND Type='Filtre' AND Page='Validation' 
						AND Variable='".$value."' ";
						
						$resultTest=mysqli_query($bdd,$req);
					}
				}
				elseif(isset($_POST['Tri_RAZ'])){
					$tab =array("Reference","Date","WP","FamilleTache","Tache","Preparateur","Statut","TempsPasse","TempsAlloue","Responsable","RaisonRefus","Delai","CommentaireDelai","Commentaire","Controleur","General");
					foreach($tab as $value){
						if($value=="General"){
							RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Validation',$value,"Id DESC,");
							$_SESSION['TriVALI_'.$value]="Id DESC,";
						}
						else{
							ViderFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Validation',$value);
							$_SESSION['TriVALI_'.$value]="";
						}
					}
				}
				elseif(isset($_POST['BtnNbLigne'])){
					if($_POST['nbLigne']<>"" && $_POST['nbLigne']>0){
						$_SESSION['VALI_NbLigne']=$_POST['nbLigne'];
					}
				}
			}
			
			$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
			Ecrire_Code_JS_Init_Date();
		?>			
		
		<table width="100%" cellpadding="0" cellspacing="0" align="center">
			<form class="test" id="formulaire" method="POST" action="Validation.php">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td width="4"></td>
								<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATION";}else{echo "VALIDATION";} ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td>
<!-- 					tableau de recherche -->
						<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
							<tr>
								<td><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
								<td align="right">
									<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>">&nbsp;&nbsp;</a>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date de début";} ?>&nbsp;
									<input type="date" name="dateDebut" size="10" id="datepicker" value="<?php echo $_SESSION['VALI_PageDateDebut2'];?>"/>
								&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?>&nbsp;
								<input type="date" name="dateFin"  size="10" value="<?php echo $_SESSION['VALI_PageDateFin2'];?>"/>
								&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?>&nbsp;
									<select id="preparateur" name="preparateur">
										<?php
											echo"<option value=''></option>";
											$req="SELECT DISTINCT trame_travaileffectue.Id_Preparateur AS Id, 
											(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Preparateur) AS Personne
											FROM trame_travaileffectue
											WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
											AND trame_travaileffectue.Id_Preparateur IN (
												SELECT trame_acces.Id_Personne 
												FROM trame_acces 
												WHERE trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." 
											)
											ORDER BY Personne;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowPrepa=mysqli_fetch_array($result)){
													$selected="";
													if($_SESSION['VALI_PagePreparateur2']==$rowPrepa['Id']){$selected="selected";}
													echo "<option ".$selected." value=\"".$rowPrepa['Id']."\">".$rowPrepa['Personne']."</option>";
												}
											}
										?>
									</select>
									&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?>&nbsp;
									<select id="wp" name="wp">
										<?php
											echo"<option value=''></option>";
											$req="SELECT DISTINCT trame_wp.Id, trame_wp.Libelle FROM trame_travaileffectue LEFT JOIN trame_wp on trame_travaileffectue.Id_WP=trame_wp.Id WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowWP=mysqli_fetch_array($result)){
													$selected="";
													if($_SESSION['VALI_PageWP2']==$rowWP['Id']){$selected="selected";}
													echo "<option ".$selected." value=\"".$rowWP['Id']."\">".$rowWP['Libelle']."</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<?php
								if($_SESSION['VALI_MotCles']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Keywords : ".$_SESSION['VALI_MotCles']."</td>";
									}
									else{
										echo "<td>&nbsp; Mots clés : ".$_SESSION['VALI_MotCles']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_Reference']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; References : ".$_SESSION['VALI_Reference']."</td>";
									}
									else{
										echo "<td>&nbsp; Références : ".$_SESSION['VALI_Reference']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_Tache']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Tasks : ".$_SESSION['VALI_Tache']."</td>";
									}
									else{
										echo "<td>&nbsp; Tâches : ".$_SESSION['VALI_Tache']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_Preparateur']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Manufacturing Engineer : ".$_SESSION['VALI_Preparateur']."</td>";
									}
									else{
										echo "<td>&nbsp; Préparateurs : ".$_SESSION['VALI_Preparateur']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_Controleur']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Controllers : ".$_SESSION['VALI_Controleur']."</td>";
									}
									else{
										echo "<td>&nbsp; Contrôleurs : ".$_SESSION['VALI_Controleur']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_Statut']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Status : ".$_SESSION['VALI_Statut']."</td>";
									}
									else{
										echo "<td>&nbsp; Statuts : ".$_SESSION['VALI_Statut']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_Delai']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Delay : ".$_SESSION['VALI_Delai']."</td>";
									}
									else{
										echo "<td>&nbsp; Delai : ".$_SESSION['VALI_Delai']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_WP']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Workpackages : ".$_SESSION['VALI_WP']."</td>";
									}
									else{
										echo "<td>&nbsp; Workpackages : ".$_SESSION['VALI_WP']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_FamilleTache']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Task families : ".$_SESSION['VALI_FamilleTache']."</td>";
									}
									else{
										echo "<td>&nbsp; Familles tâches : ".$_SESSION['VALI_FamilleTache']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_DateDebut']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; Start date : ".$_SESSION['VALI_DateDebut']."</td>";
									}
									else{
										echo "<td>&nbsp; Date de début : ".$_SESSION['VALI_DateDebut']."</td>";
									}
									echo "</tr>";
								}
								if($_SESSION['VALI_DateFin']<>""){
									echo "<tr>";
									if($_SESSION['Langue']=="EN"){
										echo "<td>&nbsp; End date : ".$_SESSION['VALI_DateFin']."</td>";
									}
									else{
										echo "<td>&nbsp; Date de fin : ".$_SESSION['VALI_DateFin']."</td>";
									}
									echo "</tr>";
								}
							?>
							
							<!-- 					Les boutons de recherhes -->
							<tr>
								<td align="center" colspan="6">
									<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
									<input class="Bouton" name="Recherche_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
									<input class="Bouton" name="Tri_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Delete sorts";}else{echo "Effacer les tris";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
									<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
								</td>
							</tr>
							
						</table>
					</td>
				</tr>
				<tr>
					<td height="4"/>
				</tr>
				<tr>
					<td height="4"/>
				</tr>
				<tr>
					<td>
<!-- 						requete générale de la page -->
						<?php
							if(isset($_GET['Tri'])){
								if($_GET['Tri']=="Reference"){
									$_SESSION['TriVALI_General']= str_replace("Designation ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Designation DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Designation ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Designation DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Reference']==""){$_SESSION['TriVALI_Reference']="ASC";$_SESSION['TriVALI_General'].= "Designation ".$_SESSION['TriVALI_Reference'].",";}
									elseif($_SESSION['TriVALI_Reference']=="ASC"){$_SESSION['TriVALI_Reference']="DESC";$_SESSION['TriVALI_General'].= "Designation ".$_SESSION['TriVALI_Reference'].",";}
									else{$_SESSION['TriVALI_Reference']="";}
								}
								if($_GET['Tri']=="DateTravail"){
									$_SESSION['TriVALI_General']= str_replace("DatePreparateur ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("DatePreparateur DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("DatePreparateur ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("DatePreparateur DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Date']==""){$_SESSION['TriVALI_Date']="ASC";$_SESSION['TriVALI_General'].= "DatePreparateur ".$_SESSION['TriVALI_Date'].",";}
									elseif($_SESSION['TriVALI_Date']=="ASC"){$_SESSION['TriVALI_Date']="DESC";$_SESSION['TriVALI_General'].= "DatePreparateur ".$_SESSION['TriVALI_Date'].",";}
									else{$_SESSION['TriVALI_Date']="";}
								}
								if($_GET['Tri']=="WP"){
									$_SESSION['TriVALI_General']= str_replace("WP ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("WP DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("WP ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("WP DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_WP']==""){$_SESSION['TriVALI_WP']="ASC";$_SESSION['TriVALI_General'].= "WP ".$_SESSION['TriVALI_WP'].",";}
									elseif($_SESSION['TriVALI_WP']=="ASC"){$_SESSION['TriVALI_WP']="DESC";$_SESSION['TriVALI_General'].= "WP ".$_SESSION['TriVALI_WP'].",";}
									else{$_SESSION['TriVALI_WP']="";}
								}
								if($_GET['Tri']=="FamilleTache"){
									$_SESSION['TriVALI_General']= str_replace("FamilleTache ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("FamilleTache DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("FamilleTache ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("FamilleTache DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_FamilleTache']==""){$_SESSION['TriVALI_FamilleTache']="ASC";$_SESSION['TriVALI_General'].= "FamilleTache ".$_SESSION['TriVALI_FamilleTache'].",";}
									elseif($_SESSION['TriVALI_FamilleTache']=="ASC"){$_SESSION['TriVALI_FamilleTache']="DESC";$_SESSION['TriVALI_General'].= "FamilleTache ".$_SESSION['TriVALI_FamilleTache'].",";}
									else{$_SESSION['TriVALI_FamilleTache']="";}
								}
								if($_GET['Tri']=="Tache"){
									$_SESSION['TriVALI_General']= str_replace("Tache ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Tache DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Tache ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Tache DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Tache']==""){$_SESSION['TriVALI_Tache']="ASC";$_SESSION['TriVALI_General'].= "Tache ".$_SESSION['TriVALI_Tache'].",";}
									elseif($_SESSION['TriVALI_Tache']=="ASC"){$_SESSION['TriVALI_Tache']="DESC";$_SESSION['TriVALI_General'].= "Tache ".$_SESSION['TriVALI_Tache'].",";}
									else{$_SESSION['TriVALI_Tache']="";}
								}
								if($_GET['Tri']=="Delai"){
									$_SESSION['TriVALI_General']= str_replace("StatutDelai ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("StatutDelai DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("StatutDelai ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("StatutDelai DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Delai']==""){$_SESSION['TriVALI_Delai']="ASC";$_SESSION['TriVALI_General'].= "StatutDelai ".$_SESSION['TriVALI_Delai'].",";}
									elseif($_SESSION['TriVALI_Delai']=="ASC"){$_SESSION['TriVALI_Delai']="DESC";$_SESSION['TriVALI_General'].= "StatutDelai ".$_SESSION['TriVALI_Delai'].",";}
									else{$_SESSION['TriVALI_Delai']="";}
								}
								if($_GET['Tri']=="Statut"){
									$_SESSION['TriVALI_General']= str_replace("Statut ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Statut DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Statut ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Statut DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Statut']==""){$_SESSION['TriVALI_Statut']="ASC";$_SESSION['TriVALI_General'].= "Statut ".$_SESSION['TriVALI_Statut'].",";}
									elseif($_SESSION['TriVALI_Statut']=="ASC"){$_SESSION['TriVALI_Statut']="DESC";$_SESSION['TriVALI_General'].= "Statut ".$_SESSION['TriVALI_Statut'].",";}
									else{$_SESSION['TriVALI_Statut']="";}
								}
								if($_GET['Tri']=="Preparateur"){
									$_SESSION['TriVALI_General']= str_replace("Preparateur ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Preparateur DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Preparateur ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Preparateur DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Preparateur']==""){$_SESSION['TriVALI_Preparateur']="ASC";$_SESSION['TriVALI_General'].= "Preparateur ".$_SESSION['TriVALI_Preparateur'].",";}
									elseif($_SESSION['TriVALI_Preparateur']=="ASC"){$_SESSION['TriVALI_Preparateur']="DESC";$_SESSION['TriVALI_General'].= "Preparateur ".$_SESSION['TriVALI_Preparateur'].",";}
									else{$_SESSION['TriVALI_Preparateur']="";}
								}
								if($_GET['Tri']=="Controleur"){
									$_SESSION['TriVALI_General']= str_replace("Controleur ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Controleur DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Controleur ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Controleur DESC","",$_SESSION['TriVALI_General']);
									if($_SESSION['TriVALI_Controleur']==""){$_SESSION['TriVALI_Controleur']="ASC";$_SESSION['TriVALI_General'].= "Controleur ".$_SESSION['TriVALI_Controleur'].",";}
									elseif($_SESSION['TriVALI_Controleur']=="ASC"){$_SESSION['TriVALI_Controleur']="DESC";$_SESSION['TriVALI_General'].= "Controleur ".$_SESSION['TriVALI_Controleur'].",";}
									else{$_SESSION['TriVALI_Controleur']="";}
								}
								if($_GET['Tri']=="TempsPasse"){
									$_SESSION['TriVALI_General']= str_replace("TempsPasse ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("TempsPasse DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("TempsPasse ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("TempsPasse DESC","",$_SESSION['TriVALI_General']);
									
									if($_SESSION['TriVALI_TempsPasse']==""){$_SESSION['TriVALI_TempsPasse']="ASC";$_SESSION['TriVALI_General'].= "TempsPasse ".$_SESSION['TriVALI_TempsPasse'].",";}
									elseif($_SESSION['TriVALI_TempsPasse']=="ASC"){$_SESSION['TriVALI_TempsPasse']="DESC";$_SESSION['TriVALI_General'].= "TempsPasse ".$_SESSION['TriVALI_TempsPasse'].",";}
									else{$_SESSION['TriVALI_TempsPasse']="";}
								}
								if($_GET['Tri']=="TempsAlloue"){
									$_SESSION['TriVALI_General']= str_replace("TempsAlloue ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("TempsAlloue DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("TempsAlloue ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("TempsAlloue DESC","",$_SESSION['TriVALI_General']);
									
									if($_SESSION['TriVALI_TempsAlloue']==""){$_SESSION['TriVALI_TempsAlloue']="ASC";$_SESSION['TriVALI_General'].= "TempsAlloue ".$_SESSION['TriVALI_TempsAlloue'].",";}
									elseif($_SESSION['TriVALI_TempsAlloue']=="ASC"){$_SESSION['TriVALI_TempsAlloue']="DESC";$_SESSION['TriVALI_General'].= "TempsAlloue ".$_SESSION['TriVALI_TempsAlloue'].",";}
									else{$_SESSION['TriVALI_TempsAlloue']="";}
								}
								if($_GET['Tri']=="Commentaire"){
									$_SESSION['TriVALI_General']= str_replace("DescriptionModification ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("DescriptionModification DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("DescriptionModification ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("DescriptionModification DESC","",$_SESSION['TriVALI_General']);
									
									if($_SESSION['TriVALI_Commentaire']==""){$_SESSION['TriVALI_Commentaire']="ASC";$_SESSION['TriVALI_General'].= "DescriptionModification ".$_SESSION['TriVALI_Commentaire'].",";}
									elseif($_SESSION['TriVALI_Commentaire']=="ASC"){$_SESSION['TriVALI_Commentaire']="DESC";$_SESSION['TriVALI_General'].= "DescriptionModification ".$_SESSION['TriVALI_Commentaire'].",";}
									else{$_SESSION['TriVALI_Commentaire']="";}
								}
								if($_GET['Tri']=="Responsable"){
									$_SESSION['TriVALI_General']= str_replace("Responsable ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Responsable DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Responsable ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("Responsable DESC","",$_SESSION['TriVALI_General']);
									
									if($_SESSION['TriVALI_Responsable']==""){$_SESSION['TriVALI_Responsable']="ASC";$_SESSION['TriVALI_General'].= "Responsable ".$_SESSION['TriVALI_Responsable'].",";}
									elseif($_SESSION['TriVALI_Responsable']=="ASC"){$_SESSION['TriVALI_Responsable']="DESC";$_SESSION['TriVALI_General'].= "Responsable ".$_SESSION['TriVALI_Responsable'].",";}
									else{$_SESSION['TriVALI_Responsable']="";}
								}
								if($_GET['Tri']=="RaisonRefus"){
									$_SESSION['TriVALI_General']= str_replace("RaisonRefus ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("RaisonRefus DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("RaisonRefus ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("RaisonRefus DESC","",$_SESSION['TriVALI_General']);
									
									if($_SESSION['TriVALI_RaisonRefus']==""){$_SESSION['TriVALI_RaisonRefus']="ASC";$_SESSION['TriVALI_General'].= "RaisonRefus ".$_SESSION['TriVALI_RaisonRefus'].",";}
									elseif($_SESSION['TriVALI_RaisonRefus']=="ASC"){$_SESSION['TriVALI_RaisonRefus']="DESC";$_SESSION['TriVALI_General'].= "RaisonRefus ".$_SESSION['TriVALI_RaisonRefus'].",";}
									else{$_SESSION['TriVALI_RaisonRefus']="";}
								}
								if($_GET['Tri']=="CommentaireDelai"){
									$_SESSION['TriVALI_General']= str_replace("CommentaireDelai ASC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("CommentaireDelai DESC,","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("CommentaireDelai ASC","",$_SESSION['TriVALI_General']);
									$_SESSION['TriVALI_General']= str_replace("CommentaireDelai DESC","",$_SESSION['TriVALI_General']);
									
									if($_SESSION['TriVALI_CommentaireDelai']==""){$_SESSION['TriVALI_CommentaireDelai']="ASC";$_SESSION['TriVALI_General'].= "CommentaireDelai ".$_SESSION['TriVALI_CommentaireDelai'].",";}
									elseif($_SESSION['TriVALI_CommentaireDelai']=="ASC"){$_SESSION['TriVALI_CommentaireDelai']="DESC";$_SESSION['TriVALI_General'].= "CommentaireDelai ".$_SESSION['TriVALI_CommentaireDelai'].",";}
									else{$_SESSION['TriVALI_CommentaireDelai']="";}
								}
							}
							if($_SESSION['VALI_ModeFiltre']=="oui"){
								$reqAnalyse="SELECT trame_travaileffectue.Id ";
								$req2="SELECT Id,Statut,Designation,DatePreparateur,StatutDelai,DescriptionModification,Id_Preparateur,RaisonRefus,TempsPasse,Id_Tache, ";
								$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS WP,RaisonRefus,CommentaireDelai, ";
								$req2.="(SELECT (SELECT Libelle FROM trame_familletache WHERE Id=Id_FamilleTache) FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS FamilleTache, ";
								$req2.="(SELECT COUNT(Id) FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id) AS Controle, ";
								$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Responsable) AS Responsable, ";
								$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=(SELECT Id_Controleur FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ORDER BY trame_controlecroise.Id DESC LIMIT 1)) AS Controleur, ";
								$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache, ";
								$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur ";
								$req="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Statut <> \"EN COURS\" AND ";						
								if($_SESSION['VALI_Reference2']<>""){
									$tab = explode(";",$_SESSION['VALI_Reference2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="Designation='".$valeur."' OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_PageWP2']<>""){
									$req.="Id_WP=".$_SESSION['VALI_PageWP2']." AND ";
								}
								if($_SESSION['VALI_WP2']<>""){
									$tab = explode(";",$_SESSION['VALI_WP2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="Id_WP=".$valeur." OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_Tache2']<>""){
									$tab = explode(";",$_SESSION['VALI_Tache2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="Id_Tache=".$valeur." OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_FamilleTache2']<>""){
									$tab = explode(";",$_SESSION['VALI_FamilleTache2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="(SELECT Id_FamilleTache FROM trame_tache WHERE Id=Id_Tache)=".$valeur." OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_Statut2']<>""){
									$tab = explode(";",$_SESSION['VALI_Statut2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="Statut='".$valeur."' OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_Delai2']<>""){
									$tab = explode(";",$_SESSION['VALI_Delai2']);
									$req.="(";
									foreach($tab as $valeur){
										if($valeur<>""){
											$req.="StatutDelai='".$valeur."' OR ";
										}
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_PagePreparateur2']<>""){
									$req.="Id_Preparateur=".$_SESSION['VALI_PagePreparateur2']." AND ";
								}
								if($_SESSION['VALI_Preparateur2']<>""){
									$tab = explode(";",$_SESSION['VALI_Preparateur2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="Id_Preparateur=".$valeur." OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_Controleur2']<>""){
									$tab = explode(";",$_SESSION['VALI_Controleur2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="(SELECT Id_Controleur FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ORDER BY trame_controlecroise.Id DESC LIMIT 1)=".$valeur." OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_MotCles2']<>""){
									$tab = explode(";",$_SESSION['VALI_MotCles2']);
									$req.="(";
									foreach($tab as $valeur){
										 if($valeur<>""){
											$req.="Designation LIKE '%".$valeur."%' OR DescriptionModification LIKE '%".$valeur."%' OR ";
										 }
									}
									$req=substr($req,0,-3);
									$req.=") AND ";
								}
								if($_SESSION['VALI_PageDateDebut2']<>""){
									$req.="DatePreparateur>='".TrsfDate_($_SESSION['VALI_PageDateDebut2'])."' AND ";
								}
								if($_SESSION['VALI_PageDateFin2']<>""){
									$req.="DatePreparateur<='".TrsfDate_($_SESSION['VALI_PageDateFin2'])."' AND ";
								}
								if($_SESSION['VALI_DateDebut2']<>"" || $_SESSION['VALI_DateFin2']<>""){
									$req.=" ( ";
									if($_SESSION['VALI_DateDebut2']<>""){
										$req.="DatePreparateur >= '". TrsfDate_($_SESSION['VALI_DateDebut2'])."' ";
										$req.=" AND ";
									}
									if($_SESSION['VALI_DateFin2']<>""){
										$req.="DatePreparateur <= '". TrsfDate_($_SESSION['VALI_DateFin2'])."' ";
										$req.=" ";
									}
									if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
									$req.=" ) ";
								}
								if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
								if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
								$result=mysqli_query($bdd,$reqAnalyse.$req);
								$nbResulta=mysqli_num_rows($result);
								
								if($_SESSION['TriVALI_General']<>""){
									$req.="ORDER BY ".substr($_SESSION['TriVALI_General'],0,-1);
								}
				
								$nombreDePages=ceil($nbResulta/$_SESSION['VALI_NbLigne']);
								if(isset($_GET['Page'])){$_SESSION['VALI_Page']=$_GET['Page'];}
								else{$_SESSION['VALI_Page']=0;}
								$req3=" LIMIT ".($_SESSION['VALI_Page']*$_SESSION['VALI_NbLigne']).",".$_SESSION['VALI_NbLigne']."";
								$result=mysqli_query($bdd,$req2.$req.$req3);
								$nbResulta=mysqli_num_rows($result);

							}
						?>
					<tr>
						<td align="center" style="font-size:14px;">
							<?php
								if($_SESSION['VALI_ModeFiltre']=="oui"){
									$nbPage=0;
									if($_SESSION['VALI_Page']>1){echo "<b> <a style='color:#00599f;' href='Validation.php?Page=0'><<</a> </b>";}
									$valeurDepart=1;
									if($_SESSION['VALI_Page']<=5){
										$valeurDepart=1;
									}
									elseif($_SESSION['VALI_Page']>=($nombreDePages-6)){
										$valeurDepart=$nombreDePages-6;
									}
									else{
										$valeurDepart=$_SESSION['VALI_Page']-5;
									}
									for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
										if($i<=$nombreDePages){
											if($i==($_SESSION['VALI_Page']+1)){
												echo "<b> [ ".$i." ] </b>"; 
											}	
											else{
												echo "<b> <a style='color:#00599f;' href='Validation.php?Page=".($i-1)."'>".$i."</a> </b>";
											}
										}
									}
									if($_SESSION['VALI_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Validation.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
								}
							?>
						</td>
					</tr>				
				<tr><td>
				<tr>
					<td align="right" colspan="5">
						<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreAffichage()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Viewing";}else{echo "Affichage";}?>&nbsp;&nbsp;</a>
					</td>
				</tr>
				<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
					<tr bgcolor="#00325F">
						<?php
						$tabChamps=array("Reference","Date","WP","FamilleTache","Tache","Delai","Statut","TempsAlloue","TempsPasse","Preparateur","Controleur","InfosComplementaires","Commentaire","Responsable","RaisonRefus","CommentaireDelai");
						$tabIntituleFR = array("Référence","Date du travail","Workpackage","Famille tâche","Tâche","Délai","Statut","Temps alloué","Temps passé","Préparateur","Contrôleur","Infos complementaires","Commentaire","Responsable","Raison du retour","Commentaire délai");
						$tabIntituleEN = array("Reference","Date of work","Workpackage","Task family","Task","Delay","Status","Allotted time","Time spent","Manufacturing Engineer","Controller","Further information","Comment","Responsible","Reason for return","Comment delay");
						$i=0;
						
						$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
						$resultPlanning=mysqli_query($bdd,$reqPlanning);
						$nbResultaPlanning=mysqli_num_rows($resultPlanning);
						
						$tabVisible= array();
						foreach($tabChamps as $value){
							$tabCh=explode("_",$_SESSION['ChampsVAL_'.$value]);
							$tabVisible[$i]=$tabCh[2];
							if($tabCh[2]==1){
								if($value<>"TempsPasse" || $nbResultaPlanning>0){
							?>
								<td class="EnTeteTableauCompetences" width="<?php echo $tabCh[1];?>%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Validation.php?Tri=<?php echo $tabCh[0];?>"><?php if($_SESSION['Langue']=="EN"){echo $tabIntituleEN[$i];}else{echo $tabIntituleFR[$i];} ?><?php if($value<>'TempsAlloue' && $value<>'InfosComplementaires'){if($_SESSION['TriVALI_'.$value]=="DESC"){echo "&uarr;";} elseif($_SESSION['TriVALI_'.$value]=="ASC"){echo "&darr;";}} ?></a></td>
							<?php
								}
							}
							$i++;
						}
						?>
						<td class="EnTeteTableauCompetences" width="2%"></td>
						<td class="EnTeteTableauCompetences" width="2%"></td>
						<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
							<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreValidation('V','<?php echo $_SESSION['Langue']; ?>')" title="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "V";}else{echo "V";}?>&nbsp;&nbsp;&nbsp;&nbsp;</a>
							<a style="text-decoration:none;" class="Bouton" title="<?php if($_SESSION['Langue']=="EN"){echo "Return";}else{echo "Retourner";}?>" href="javascript:OuvreFenetreValidation('R','<?php echo $_SESSION['Langue']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "R";}else{echo "R";}?>&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
							<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($_SESSION['Langue']=="EN"){echo "Select all";}else{echo "Sél. tout";} ?>
						</td>
					</tr>
					<?php
						if($_SESSION['VALI_ModeFiltre']=="oui"){
							if ($nbResulta>0){
								$couleur="#ffffff";
								$i=0;
								while($row=mysqli_fetch_array($result)){
									if($couleur=="#ffffff"){$couleur="#E1E1D7";}
									else{$couleur="#ffffff";}
									$Infos="";
									$req="SELECT ValeurInfo, ";
									$req.="(SELECT Info FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Info, ";
									$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
									$req.="FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$row['Id'];
									$resultInfo=mysqli_query($bdd,$req);
									$nbResultaInfo=mysqli_num_rows($resultInfo);
									if ($nbResultaInfo>0){
										while($rowInfo=mysqli_fetch_array($resultInfo)){
											if($rowInfo['Type']=="Date"){
												$Infos.=$rowInfo['Info']." : ".AfficheDateFR($rowInfo['ValeurInfo'])."<br>";
											}
											elseif($rowInfo['Type']=="Oui/Non"){
												if($_SESSION['Langue']=="FR"){
													$valeur="Non";
												}
												else{
													$valeur="No";
												}
												if($rowInfo['ValeurInfo']==1){
													if($_SESSION['Langue']=="FR"){
														$valeur="Oui";
													}
													else{
														$valeur="Yes";
													}
												}
												$Infos.=$rowInfo['Info']." : ".$valeur."<br>";
											}
											else{
												$Infos.=$rowInfo['Info']." : ".$rowInfo['ValeurInfo']."<br>";
											}
										}
									}
									
									$Hover2="";
									$infoBulle2 ="";
									if($row['RaisonRefus']<>""){
										$Hover2="id='leHover3'";
										$infoBulle2 = "\n<span>".nl2br($row['RaisonRefus'])."</span>\n";
									}
									$TempsAlloue=0;
									$UO_M="";
									$UO_O="";
									$req="SELECT trame_travaileffectue_uo.Id,
										trame_travaileffectue_uo.TempsAlloue,
										trame_travaileffectue_uo.Relation, 
										(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UO 
										FROM trame_travaileffectue_uo
										WHERE trame_travaileffectue_uo.TravailFait=1 
										AND trame_travaileffectue_uo.Id_TravailEffectue=".$row['Id']."
										ORDER BY UO ";
									$resultTA=mysqli_query($bdd,$req);
									$nbResultaTA=mysqli_num_rows($resultTA);
									if ($nbResultaTA>0){
										mysqli_data_seek($resultTA,0);
										while($rowTA=mysqli_fetch_array($resultTA)){
											$TempsAlloue=$TempsAlloue+floatval($rowTA['TempsAlloue']);
											if($rowTA['Relation']=="Mandatory"){
												$UO_M.="&#x2794;".$rowTA['UO']."<br>";
											}
											else{
												$UO_O.="&#x2794;".$rowTA['UO']."<br>";
											}
										}
									}
									$infoBulle3="";
									if($UO_M<>"" || $UO_O<>""){
										if($_SESSION['Langue']=="EN"){
											$infoBulle3 = "\n<span>".nl2br("<b>WU Mandatory</b><br>".$UO_M."<b>WU Optional</b><br>".$UO_O)."</span>\n";
										}
										else{
											$infoBulle3 = "\n<span>".nl2br("<b>UO Mandatory</b><br>".$UO_M."<b>UO Optional</b><br>".$UO_O)."</span>\n";
										}
									}
									?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td <?php if($tabVisible[0]==0){echo "style='display:none;'";} ?>>&nbsp;<?php echo $row['Designation'];?></td>
											<td <?php if($tabVisible[1]==0){echo "style='display:none;'";} ?>><?php echo AfficheDateFR($row['DatePreparateur']);?></td>
											<td <?php if($tabVisible[2]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['WP']));?></td>
											<td <?php if($tabVisible[3]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['FamilleTache']));?></td>
											<td <?php if($tabVisible[4]==0){echo "style='display:none;'";} ?> id="leHover3"><?php echo stripslashes(str_replace("\\","",$row['Tache'])).$infoBulle3;?></td>
											<td <?php if($tabVisible[5]==0){echo "style='display:none;'";} ?>><?php echo $row['StatutDelai'];?></td>
											<td <?php if($tabVisible[6]==0){echo "style='display:none;'";} ?> <?php echo $Hover2; ?>><?php echo $row['Statut'].$infoBulle2;?></td>
											<td <?php if($tabVisible[7]==0){echo "style='display:none;'";} ?> align="center"><?php echo $TempsAlloue;?></td>
											<?php 
												if($nbResultaPlanning>0){
													if($row['TempsPasse']==0){
														echo "<script>Liste_Temps0[".$i."]= Array(\"".$row['Id']."\",\"".$row['Designation']."\")</script>";
														$i++;
													}
											?>
												<td  <?php if($tabVisible[8]==0){echo "style='display:none;'";} ?> align="center"><?php echo $row['TempsPasse']; ?></td>
											<?php
												}
											?>											
											<td <?php if($tabVisible[9]==0){echo "style='display:none;'";} ?>><?php echo $row['Preparateur'];?></td>
											<td <?php if($tabVisible[10]==0){echo "style='display:none;'";} ?>><?php echo $row['Controleur'];?></td>
											<td <?php if($tabVisible[11]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$Infos));?></td>
											<td <?php if($tabVisible[12]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['DescriptionModification']));?></td>
											<td <?php if($tabVisible[13]==0){echo "style='display:none;'";} ?>><?php echo $row['Responsable'];?></td>
											<td <?php if($tabVisible[14]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['RaisonRefus']));?></td>
											<td <?php if($tabVisible[15]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['CommentaireDelai']));?></td>
											<td><?php 
												//Récupérer la CL de la tâche + niveau
												$Id_CL=0;
												$Niveau=0;
												$reqTache="SELECT Id_CL, NiveauControle,Delais FROM trame_tache WHERE Id=".$row['Id_Tache'];
												$resultTache=mysqli_query($bdd,$reqTache);
												$nbResultaTache=mysqli_num_rows($resultTache);
												if ($nbResultaTache>0){
													$rowTache=mysqli_fetch_array($resultTache);
													$Id_CL=$rowTache['Id_CL'];
													$Niveau=$rowTache['NiveauControle'];
												}
												$Id_CLVersion=0;
												//Recherche de la version du CL
												$req="SELECT Id FROM trame_cl_version WHERE Id_CL=".$Id_CL." AND Valide=1 AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
												$resultCL=mysqli_query($bdd,$req);
												$nbResultaCL=mysqli_num_rows($resultCL);
												if ($nbResultaCL>0){
													$rowCL=mysqli_fetch_array($resultCL);
													$Id_CLVersion=$rowCL['Id'];
												}
												
												//Recherche le contenu de la version
												$reqCLContenu="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Id_CLVersion;
												$resultContenuVersion=mysqli_query($bdd,$reqCLContenu);
												$nbResultaContenuVersion=mysqli_num_rows($resultContenuVersion);
												
												if($Id_CL>0 && $Id_CLVersion>0 && $nbResultaContenuVersion>0 && $row['Controle']==0 && $row['Statut']=="A VALIDER" && substr($_SESSION['DroitTR'],1,1)==1){
												?>
													<a href="javascript:OuvreFenetreC(<?php echo $row['Id'].",'".$_SESSION['Langue']."'"; ?>)">
														<img src='../../Images/c.png' style="width:22px;" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Ask for control";}else{echo "Demander le contrôle";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Ask for control";}else{echo "Demander le contrôle";} ?>'>
													</a>
												<?php
												}
												?>
											</td>
											<td align="center">
												<a href="javascript:OuvreFenetreLecture(<?php echo $row['Id']; ?>)">
													<img src='../../Images/Loupe.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Display";}else{echo "Visualiser";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Display";}else{echo "Visualiser";} ?>'>
												</a>
											</td>				
											<td align="left">
												<input class="check" type="checkbox" name="<?php echo $row['Id']; ?>" id="<?php echo $row['Id']; ?>"/>
											</td>
										</tr>
									<?php
								}
							}
						}
					?>
					<tr><td height="400"/></tr>
				</table>
			</td></tr>
			<tr><td height="15"></td></tr>
			<tr><td>
				<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
					<tr><td height="4"></td></tr>
					<tr>
						<td width="15%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Number of lines per page ";}else{echo "Nombre de ligne par page ";}?></td>
						<td width="60%">
							<input id="nbLigne" name="nbLigne" size="10" value="<?php echo $_SESSION['VALI_NbLigne'];?>"/>
							<input class="Bouton" name="BtnNbLigne" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";}?>">
						</td>
					</tr>
					<tr><td height="4"></td></tr>
				</table>
			</td></tr>
		</form>
		</table>		
	</body>
	

	<footer> <!-- le pied de page -->	
	</footer>
</html>
