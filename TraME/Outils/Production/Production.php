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
	<script type="text/javascript" src="ProductionListe.js?time=<?php echo time();?>"></script>
	<script type="text/javascript">
		$(function(){
			$(window).scroll(
				function () {//Au scroll dans la fenetre on d?clenche la fonction
					if ($(this).scrollTop() > 1) { //si on a d?fil? de plus de 150px du haut vers le bas
						$('#navigation').addClass("fixNavigation"); //on ajoute la classe "fixNavigation" ? <div id="navigation">
					} else {
						$('#navigation').removeClass("fixNavigation");//sinon on retire la classe "fixNavigation" ? <div id="navigation">
					}
				}
			);			 
		});
	</script>
</head>
<?php
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Production/Production.php";

//echo date("h:i:s")."<br>";
echo "<script>console.log('".json_encode(date("h:i:s"))."');</script>";
 
if($_SESSION['RappelAC']==""){
	//Message de rappel des auto-contrôles et recontrôle à faire sur toutes les prestations
	//Message de rappel des contrôles à faire sur toutes les prestations 
	//Uniquement si la personne est contrôleur sur la prestation en question 
	$requete="SELECT Designation,(SELECT Libelle FROM trame_prestation WHERE trame_prestation.Id=trame_travaileffectue.Id_Prestation) AS Prestation ";
	$requete.="FROM trame_travaileffectue 
		WHERE (Id_Preparateur=".$_SESSION['Id_PersonneTR']." AND (Statut='AC' OR Statut='REC')) 
		OR (((SELECT Id_Controleur FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ORDER BY trame_controlecroise.Id DESC LIMIT 1)=".$_SESSION['Id_PersonneTR']."
					OR ((SELECT Id_Controleur FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ORDER BY trame_controlecroise.Id DESC LIMIT 1)=0
						AND (SELECT COUNT(trame_acces.Id)
							FROM trame_acces 
							WHERE Id_Personne=".$_SESSION['Id_PersonneTR']." 
							AND trame_acces.Id_Prestation=trame_travaileffectue.Id_Prestation
							AND SUBSTR(trame_acces.Droit,3,1)=1)>0
						AND Id_Preparateur<>".$_SESSION['Id_PersonneTR']."
					)
				) 
			AND (Statut='CONTROLE'))
		ORDER BY Designation";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	
	if($nbResulta>0){
		if($_SESSION['Langue']=="EN"){
			$message="Don\'t forget to check the following deliverables : ;";
		}
		else{
			$message="N\'oubliez pas de contrôler les livrables suivants : ;";
		}
		while($row=mysqli_fetch_array($result)){
			$message.="- ".$row['Designation']."  (".$row['Prestation'].");";
		}
		echo "<script type='text/javascript'>messageAC('".$message."');</script>";
	}
	
	$_SESSION['RappelAC']="OK";
}
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$tab =array("Reference","DateDebut","DateFin","WP","FamilleTache","Tache","MotCles","Preparateur","Controleur","Statut","PageDateDebut","PageDateFin","PagePreparateur","PageWP");
		foreach($tab as $value){
			ViderFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Production',$value);
			
			$_SESSION['PROD_'.$value]="";
			$_SESSION['PROD_'.$value.'2']="";
		}
		
		$_SESSION['PROD_ModeFiltre']="oui";
		$_SESSION['PROD_Page']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['PROD_PageDateDebut2']=$_POST['dateDebut'];
		$_SESSION['PROD_PageDateFin2']=$_POST['dateFin'];
		$_SESSION['PROD_PagePreparateur2']=$_POST['preparateur'];
		$_SESSION['PROD_PageWP2']=$_POST['wp'];
		$_SESSION['PROD_ModeFiltre']="oui";
		
		$tab =array("PageDateDebut","PageDateFin","PagePreparateur","PageWP");
		foreach($tab as $value){
			$req="UPDATE trame_parametrage 
			SET Valeur='".addslashes($_SESSION['PROD_'.$value])."', Valeur2='".addslashes($_SESSION['PROD_'.$value.'2'])."'
			WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
			AND Id_Personne=".$_SESSION['Id_PersonneTR']." 
			AND Type='Filtre' AND Page='Production' 
			AND Variable='".$value."' ";
			
			$resultTest=mysqli_query($bdd,$req);
		}
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$tab =array("Reference","Date","WP","FamilleTache","Tache","Preparateur","Statut","TempsPasse","TempsAlloue","Responsable","RaisonRefus","Delai","CommentaireDelai","Commentaire","Controleur","General");
		foreach($tab as $value){
			if($value=="General"){
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',$value,"Id DESC,");
				$_SESSION['TriPROD_'.$value]="Id DESC,";
			}
			else{
				ViderFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',$value);
				$_SESSION['TriPROD_'.$value]="";
			}
		}
	}
	elseif(isset($_POST['BtnNbLigne'])){
		if($_POST['nbLigne']<>"" && $_POST['nbLigne']>0){
			$_SESSION['PROD_NbLigne']=$_POST['nbLigne'];
		}
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); 
?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Production.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "PRODUCTION";}else{echo "PRODUCTION";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
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
				<input type="date" name="dateDebut" size="10" id="datepicker" value="<?php echo $_SESSION['PROD_PageDateDebut2'];?>"/>
			&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?>&nbsp;
			<input type="date" name="dateFin"  size="10" value="<?php echo $_SESSION['PROD_PageDateFin2'];?>"/>
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
								if($_SESSION['PROD_PagePreparateur2']==$rowPrepa['Id']){$selected="selected";}
								echo "<option ".$selected." value=\"".$rowPrepa['Id']."\">".$rowPrepa['Personne']."</option>";
							}
						}
					?>
				</select>
				&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?>&nbsp;
				<select id="wp" name="wp">
					<?php
						echo"<option value=''></option>";
						$req="SELECT trame_wp.Id,Libelle 
							FROM trame_wp 
							WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
							ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($result)){
								$selected="";
								if($_SESSION['PROD_PageWP2']==$rowWP['Id']){$selected="selected";}
								echo "<option ".$selected." value=\"".$rowWP['Id']."\">".$rowWP['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<?php
			if($_SESSION['PROD_MotCles']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Keywords : ".$_SESSION['PROD_MotCles']."</td>";
				}
				else{
					echo "<td>&nbsp; Mots clés : ".$_SESSION['PROD_MotCles']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_Reference']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; References : ".$_SESSION['PROD_Reference']."</td>";
				}
				else{
					echo "<td>&nbsp; Références : ".$_SESSION['PROD_Reference']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_Tache']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Tasks : ".$_SESSION['PROD_Tache']."</td>";
				}
				else{
					echo "<td>&nbsp; Tâches : ".$_SESSION['PROD_Tache']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_Controleur']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Controllers : ".$_SESSION['PROD_Controleur']."</td>";
				}
				else{
					echo "<td>&nbsp; Contrôleurs : ".$_SESSION['PROD_Controleur']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_Preparateur']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Manufacturing Engineer : ".$_SESSION['PROD_Preparateur']."</td>";
				}
				else{
					echo "<td>&nbsp; Préparateurs : ".$_SESSION['PROD_Preparateur']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_Statut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Status : ".$_SESSION['PROD_Statut']."</td>";
				}
				else{
					echo "<td>&nbsp; Statuts : ".$_SESSION['PROD_Statut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_WP']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Workpackages : ".$_SESSION['PROD_WP']."</td>";
				}
				else{
					echo "<td>&nbsp; Workpackages : ".$_SESSION['PROD_WP']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_DateDebut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Start date : ".$_SESSION['PROD_DateDebut']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de début : ".$_SESSION['PROD_DateDebut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['PROD_DateFin']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; End date : ".$_SESSION['PROD_DateFin']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de fin : ".$_SESSION['PROD_DateFin']."</td>";
				}
				echo "</tr>";
			}
		?>
		<tr>
			
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Tri_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Delete sorts";}else{echo "Effacer les tris";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
			</td>
		</tr>
		
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a work";}else{echo "Ajouter un travail";}?>&nbsp;</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutR()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a recurring job";}else{echo "Ajouter un travail récurrent";}?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<?php
			echo "<script>console.log('".json_encode(date("h:i:s"))."');</script>";
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="Reference"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Designation ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Designation DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Designation ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Designation DESC","",$_SESSION['TriPROD_General']);
					if($_SESSION['TriPROD_Reference']==""){$_SESSION['TriPROD_Reference']="ASC";$_SESSION['TriPROD_General'].= "Designation ".$_SESSION['TriPROD_Reference'].",";}
					elseif($_SESSION['TriPROD_Reference']=="ASC"){$_SESSION['TriPROD_Reference']="DESC";$_SESSION['TriPROD_General'].= "Designation ".$_SESSION['TriPROD_Reference'].",";}
					else{$_SESSION['TriPROD_Reference']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Reference",$_SESSION['TriPROD_Reference']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Date"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DatePrepa ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DatePrepa DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DatePrepa ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DatePrepa DESC","",$_SESSION['TriPROD_General']);
					if($_SESSION['TriPROD_Date']==""){$_SESSION['TriPROD_Date']="ASC";$_SESSION['TriPROD_General'].= "DatePrepa ".$_SESSION['TriPROD_Date'].",";}
					elseif($_SESSION['TriPROD_Date']=="ASC"){$_SESSION['TriPROD_Date']="DESC";$_SESSION['TriPROD_General'].= "DatePrepa ".$_SESSION['TriPROD_Date'].",";}
					else{$_SESSION['TriPROD_Date']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Date",$_SESSION['TriPROD_Date']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="WP"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("WP ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("WP DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("WP ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("WP DESC","",$_SESSION['TriPROD_General']);
					if($_SESSION['TriPROD_WP']==""){$_SESSION['TriPROD_WP']="ASC";$_SESSION['TriPROD_General'].= "WP ".$_SESSION['TriPROD_WP'].",";}
					elseif($_SESSION['TriPROD_WP']=="ASC"){$_SESSION['TriPROD_WP']="DESC";$_SESSION['TriPROD_General'].= "WP ".$_SESSION['TriPROD_WP'].",";}
					else{$_SESSION['TriPROD_WP']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"WP",$_SESSION['TriPROD_WP']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="FamilleTache"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("FamilleTache ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("FamilleTache DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("FamilleTache ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("FamilleTache DESC","",$_SESSION['TriPROD_General']);
					if($_SESSION['TriPROD_FamilleTache']==""){$_SESSION['TriPROD_FamilleTache']="ASC";$_SESSION['TriPROD_General'].= "FamilleTache ".$_SESSION['TriPROD_FamilleTache'].",";}
					elseif($_SESSION['TriPROD_FamilleTache']=="ASC"){$_SESSION['TriPROD_FamilleTache']="DESC";$_SESSION['TriPROD_General'].= "FamilleTache ".$_SESSION['TriPROD_FamilleTache'].",";}
					else{$_SESSION['TriPROD_FamilleTache']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"FamilleTache",$_SESSION['TriPROD_FamilleTache']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Tache"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Tache ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Tache DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Tache ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Tache DESC","",$_SESSION['TriPROD_General']);
					if($_SESSION['TriPROD_Tache']==""){$_SESSION['TriPROD_Tache']="ASC";$_SESSION['TriPROD_General'].= "Tache ".$_SESSION['TriPROD_Tache'].",";}
					elseif($_SESSION['TriPROD_Tache']=="ASC"){$_SESSION['TriPROD_Tache']="DESC";$_SESSION['TriPROD_General'].= "Tache ".$_SESSION['TriPROD_Tache'].",";}
					else{$_SESSION['TriPROD_Tache']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Tache",$_SESSION['TriPROD_Tache']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Statut"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Statut ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Statut DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Statut ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Statut DESC","",$_SESSION['TriPROD_General']);
					if($_SESSION['TriPROD_Statut']==""){$_SESSION['TriPROD_Statut']="ASC";$_SESSION['TriPROD_General'].= "Statut ".$_SESSION['TriPROD_Statut'].",";}
					elseif($_SESSION['TriPROD_Statut']=="ASC"){$_SESSION['TriPROD_Statut']="DESC";$_SESSION['TriPROD_General'].= "Statut ".$_SESSION['TriPROD_Statut'].",";}
					else{$_SESSION['TriPROD_Statut']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Statut",$_SESSION['TriPROD_Statut']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Preparateur"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Preparateur ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Preparateur DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Preparateur ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Preparateur DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_Preparateur']==""){$_SESSION['TriPROD_Preparateur']="ASC";$_SESSION['TriPROD_General'].= "Preparateur ".$_SESSION['TriPROD_Preparateur'].",";}
					elseif($_SESSION['TriPROD_Preparateur']=="ASC"){$_SESSION['TriPROD_Preparateur']="DESC";$_SESSION['TriPROD_General'].= "Preparateur ".$_SESSION['TriPROD_Preparateur'].",";}
					else{$_SESSION['TriPROD_Preparateur']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Preparateur",$_SESSION['TriPROD_Preparateur']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Controleur"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Controleur ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Controleur DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Controleur ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Controleur DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_Controleur']==""){$_SESSION['TriPROD_Controleur']="ASC";$_SESSION['TriPROD_General'].= "Controleur ".$_SESSION['TriPROD_Controleur'].",";}
					elseif($_SESSION['TriPROD_Controleur']=="ASC"){$_SESSION['TriPROD_Controleur']="DESC";$_SESSION['TriPROD_General'].= "Controleur ".$_SESSION['TriPROD_Controleur'].",";}
					else{$_SESSION['TriPROD_Controleur']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Preparateur",$_SESSION['TriPROD_Preparateur']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Responsable"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Responsable ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Responsable DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Responsable ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("Responsable DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_Responsable']==""){$_SESSION['TriPROD_Responsable']="ASC";$_SESSION['TriPROD_General'].= "Responsable ".$_SESSION['TriPROD_Responsable'].",";}
					elseif($_SESSION['TriPROD_Responsable']=="ASC"){$_SESSION['TriPROD_Responsable']="DESC";$_SESSION['TriPROD_General'].= "Responsable ".$_SESSION['TriPROD_Responsable'].",";}
					else{$_SESSION['TriPROD_Responsable']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"Responsable",$_SESSION['TriPROD_Responsable']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="RaisonRefus"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("RaisonRefus ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("RaisonRefus DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("RaisonRefus ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("RaisonRefus DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_RaisonRefus']==""){$_SESSION['TriPROD_RaisonRefus']="ASC";$_SESSION['TriPROD_General'].= "RaisonRefus ".$_SESSION['TriPROD_RaisonRefus'].",";}
					elseif($_SESSION['TriPROD_RaisonRefus']=="ASC"){$_SESSION['TriPROD_RaisonRefus']="DESC";$_SESSION['TriPROD_General'].= "RaisonRefus ".$_SESSION['TriPROD_RaisonRefus'].",";}
					else{$_SESSION['TriPROD_RaisonRefus']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"RaisonRefus",$_SESSION['TriPROD_RaisonRefus']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Delai"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("StatutDelai ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("StatutDelai DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("StatutDelai ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("StatutDelai DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_Delai']==""){$_SESSION['TriPROD_Delai']="ASC";$_SESSION['TriPROD_General'].= "StatutDelai ".$_SESSION['TriPROD_Delai'].",";}
					elseif($_SESSION['TriPROD_Delai']=="ASC"){$_SESSION['TriPROD_Delai']="DESC";$_SESSION['TriPROD_General'].= "StatutDelai ".$_SESSION['TriPROD_Delai'].",";}
					else{$_SESSION['TriPROD_Delai']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"StatutDelai",$_SESSION['TriPROD_Delai']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="CommentaireDelai"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("CommentaireDelai ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("CommentaireDelai DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("CommentaireDelai ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("CommentaireDelai DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_CommentaireDelai']==""){$_SESSION['TriPROD_CommentaireDelai']="ASC";$_SESSION['TriPROD_General'].= "CommentaireDelai ".$_SESSION['TriPROD_CommentaireDelai'].",";}
					elseif($_SESSION['TriPROD_CommentaireDelai']=="ASC"){$_SESSION['TriPROD_CommentaireDelai']="DESC";$_SESSION['TriPROD_General'].= "CommentaireDelai ".$_SESSION['TriPROD_CommentaireDelai'].",";}
					else{$_SESSION['TriPROD_CommentaireDelai']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"CommentaireDelai",$_SESSION['TriPROD_CommentaireDelai']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="Commentaire"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DescriptionModification ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DescriptionModification DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DescriptionModification ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("DescriptionModification DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_Commentaire']==""){$_SESSION['TriPROD_Commentaire']="ASC";$_SESSION['TriPROD_General'].= "DescriptionModification ".$_SESSION['TriPROD_Commentaire'].",";}
					elseif($_SESSION['TriPROD_Commentaire']=="ASC"){$_SESSION['TriPROD_Commentaire']="DESC";$_SESSION['TriPROD_General'].= "DescriptionModification ".$_SESSION['TriPROD_Commentaire'].",";}
					else{$_SESSION['TriPROD_Commentaire']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"DescriptionModification",$_SESSION['TriPROD_Commentaire']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
				if($_GET['Tri']=="TempsPasse"){
					$_SESSION['TriPROD_General']= str_replace("Id DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("TempsPasse ASC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("TempsPasse DESC,","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("TempsPasse ASC","",$_SESSION['TriPROD_General']);
					$_SESSION['TriPROD_General']= str_replace("TempsPasse DESC","",$_SESSION['TriPROD_General']);
					
					if($_SESSION['TriPROD_TempsPasse']==""){$_SESSION['TriPROD_TempsPasse']="ASC";$_SESSION['TriPROD_General'].= "TempsPasse ".$_SESSION['TriPROD_TempsPasse'].",";}
					elseif($_SESSION['TriPROD_TempsPasse']=="ASC"){$_SESSION['TriPROD_TempsPasse']="DESC";$_SESSION['TriPROD_General'].= "TempsPasse ".$_SESSION['TriPROD_TempsPasse'].",";}
					else{$_SESSION['TriPROD_TempsPasse']="";}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"TempPasse",$_SESSION['TriPROD_TempsPasse']);
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Tri','Production',"General",$_SESSION['TriPROD_General']);
				}
			}

			if($_SESSION['PROD_ModeFiltre']=="oui"){
				$reqAnalyse="SELECT trame_travaileffectue.Id ";
				$req2="SELECT Id,Statut,Designation,DatePreparateur AS DatePrepa,StatutDelai,DescriptionModification,Id_Preparateur,TempsPasse,Id_Tache, ";
				$req2.="(SELECT COUNT(Id) FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id) AS Controle, ";
				$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS WP,CommentaireDelai,StatutDelai, ";
				$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache,RaisonRefus, ";
				$req2.="(SELECT Supprime FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS SupprTache, ";
				$req2.="(SELECT (SELECT Libelle FROM trame_familletache WHERE Id=Id_FamilleTache) FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS FamilleTache, ";
				$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur, ";
				$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=(SELECT Id_Controleur FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ORDER BY trame_controlecroise.Id DESC LIMIT 1)) AS Controleur, ";
				$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Responsable) AS Responsable, ";
				$req2.="(SELECT Libelle FROM trame_responsabledelais WHERE trame_responsabledelais.Id=trame_travaileffectue.Id_ResponsableDelai) AS RespDelais, ";
				$req2.="(SELECT Libelle FROM trame_causedelais WHERE trame_causedelais.Id=trame_travaileffectue.Id_CauseDelai) AS CauseDelais, 
						(SELECT SUM(TempsAlloue) FROM trame_travaileffectue_uo WHERE TravailFait=1 AND trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id) AS TempsAlloue, ";
				$req2.="IF(Statut='AC',0,IF(Statut='CONTROLE',1,IF(Statut='REC',2,3))) AS OrdreStatut ";
				$req="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
				if($_SESSION['PROD_Reference2']<>""){
					$tab = explode(";",$_SESSION['PROD_Reference2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Designation='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_PageWP2']<>""){
					$req.="Id_WP=".$_SESSION['PROD_PageWP2']." AND ";
				}
				if($_SESSION['PROD_WP2']<>""){
					$tab = explode(";",$_SESSION['PROD_WP2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_WP=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_Tache2']<>""){
					$tab = explode(";",$_SESSION['PROD_Tache2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Tache=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_Statut2']<>""){
					$tab = explode(";",$_SESSION['PROD_Statut2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Statut='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_PagePreparateur2']<>""){
					$req.="Id_Preparateur=".$_SESSION['PROD_PagePreparateur2']." AND ";
				}
				if($_SESSION['PROD_Preparateur2']<>""){
					$tab = explode(";",$_SESSION['PROD_Preparateur2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Preparateur=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_Controleur2']<>""){
					$tab = explode(";",$_SESSION['PROD_Controleur2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="(SELECT Id_Controleur FROM trame_controlecroise WHERE trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ORDER BY trame_controlecroise.Id DESC LIMIT 1)=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_MotCles2']<>""){
					$tab = explode(";",$_SESSION['PROD_MotCles2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Designation LIKE '%".$valeur."%' OR DescriptionModification LIKE '%".$valeur."%' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['PROD_PageDateDebut2']<>""){
					$req.="DatePreparateur>='".TrsfDate_($_SESSION['PROD_PageDateDebut2'])."' AND ";
				}
				if($_SESSION['PROD_PageDateFin2']<>""){
					$req.="DatePreparateur<='".TrsfDate_($_SESSION['PROD_PageDateFin2'])."' AND ";
				}
				if($_SESSION['PROD_DateDebut2']<>"" || $_SESSION['PROD_DateFin2']<>""){
					$req.=" ( ";
					if($_SESSION['PROD_DateDebut2']<>""){
						$req.="DatePreparateur >= '".TrsfDate_($_SESSION['PROD_DateDebut2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['PROD_DateFin2']<>""){
						$req.="DatePreparateur <= '".TrsfDate_($_SESSION['PROD_DateFin2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
				
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['TriPROD_General']<>""){
					$req.="ORDER BY OrdreStatut ASC, ".substr($_SESSION['TriPROD_General'],0,-1);
				}
				else{
					$req.="ORDER BY OrdreStatut ASC ";
				
				}
				$nombreDePages=ceil($nbResulta/$_SESSION['PROD_NbLigne']);
				if(isset($_GET['Page'])){$_SESSION['PROD_Page']=$_GET['Page'];}
				else{$_SESSION['PROD_Page']=0;}
				$req3=" LIMIT ".($_SESSION['PROD_Page']*$_SESSION['PROD_NbLigne']).",".$_SESSION['PROD_NbLigne']."";

				$result=mysqli_query($bdd,$req2.$req.$req3);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				if($_SESSION['PROD_ModeFiltre']=="oui"){
					$nbPage=0;
					if($_SESSION['PROD_Page']>1){echo "<b> <a style='color:#00599f;' href='Production.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['PROD_Page']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['PROD_Page']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['PROD_Page']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['PROD_Page']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Production.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['PROD_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Production.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				}
			?>
		</td>
	</tr>
	<tr>
		<td align="right" colspan="5">
			<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreAffichage()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Viewing";}else{echo "Affichage";}?>&nbsp;&nbsp;</a>
		</td>
	</tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<?php
				$tabChamps=array("Reference","Date","WP","FamilleTache","Tache","Statut","TempsAlloue","TempsPasse","Preparateur","Controleur","InfosComplementaires","Responsable","RaisonRefus","Delai","CommentaireDelai","Commentaire");
				$tabIntituleFR = array("Référence","Date du travail","Workpackage","Famille tâche","Tâche","Statut","Temps alloué","Temps passé","Préparateur","Contrôleur","Infos complementaires","Responsable","Raison du retour","Délai","Commentaire délai","Commentaire");
				$tabIntituleEN = array("Reference","Date of work","Workpackage","Task family","Task","Status","Allotted time","Time spent","Manufacturing Engineer","Controller","Further information","Responsible","Reason for return","Delay","Comment delay","Comment");
				$i=0;
				
				$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
				$resultPlanning=mysqli_query($bdd,$reqPlanning);
				$nbResultaPlanning=mysqli_num_rows($resultPlanning);
				
				$tabVisible= array();
				foreach($tabChamps as $value){
					$tabCh=explode("_",$_SESSION['ChampsPROD_'.$value]);
					$tabVisible[$i]=$tabCh[2];
					if($tabCh[2]==1){
						if($value<>"TempsPasse" || $nbResultaPlanning>0){
					?>
						<td class="EnTeteTableauCompetences" width="<?php echo $tabCh[1];?>%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Production.php?Tri=<?php echo $tabCh[0];?>"><?php if($_SESSION['Langue']=="EN"){echo $tabIntituleEN[$i];}else{echo $tabIntituleFR[$i];} ?><?php if($value<>'TempsAlloue' && $value<>'InfosComplementaires'){if($_SESSION['TriPROD_'.$value]=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPROD_'.$value]=="ASC"){echo "&darr;";}} ?></a></td>
					<?php
						}
					}
					$i++;
				}
				?>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="5%"></td>
			</tr>
			<?php
				if($_SESSION['PROD_ModeFiltre']=="oui"){
					if ($nbResulta>0){
						$couleur="#ffffff";

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
							
							$statut=$row['Statut'];
							$baliseAC="";
							if($_SESSION['Langue']=="EN"){
								if($row['Statut']=="EN COURS"){$statut="IN PROGRESS";}
								elseif($row['Statut']=="BLOQUE"){$statut="BLOCKED";}
								elseif($row['Statut']=="EN ATTENTE"){$statut="WAITING";}
								elseif($row['Statut']=="A VALIDER"){$statut="TO BE VALIDATED";}
								elseif($row['Statut']=="VALIDE"){$statut="VALIDATED";}
								elseif($row['Statut']=="REFUSE"){$statut="RETURN";}
								elseif($row['Statut']=="AC"){$statut="<span class='blink_me'>AUTO CONTROL</span>";}
								elseif($row['Statut']=="CONTROLE"){$statut="CONTROL";}
								if($row['Statut']=="REC"){$statut="<span class='blink_me'>CONTROL AGAIN</span>";}
							}
							else{
								if($row['Statut']=="AC"){$statut="<span class='blink_me'>AUTO-CONTROLE</span>";}
								elseif($row['Statut']=="REC"){$statut="<span class='blink_me'>RECONTROLE</span>";}
								elseif($row['Statut']=="CONTROLE"){$statut="<span class='blink_me'>CONTROLE</span>";}
								elseif($row['Statut']=="REFUSE"){$statut="RETOURNE";}
							}
							if($row['Statut']=="AC"){
								if($_SESSION['Id_PersonneTR']==$row['Id_Preparateur'] || substr($_SESSION['DroitTR'],3,1)=='1'){
									$baliseAC="<a href='javascript:OuvreFenetreAC(".$row['Id'].")'><img src='../../Images/checklist2.png' border='0' width='25px'  alt='Check-list' title='Check-list'></a>";
								}
							}
							elseif($row['Statut']=="CONTROLE"){$baliseAC="<a href='javascript:OuvreFenetreControle(".$row['Id'].")'><img src='../../Images/checklist.png' width='25px' border='0' alt='Check-list' title='Check-list'></a>";}
							elseif($row['Statut']=="VALIDE" || $row['Statut']=="REFUSE" || $row['Statut']=="A VALIDER"){
								if($row['Controle']>0){
									$baliseAC="<a href='javascript:OuvreFenetreAfficheControle(".$row['Id'].")'><img src='../../Images/checklist.png' width='25px' border='0' alt='Check-list' title='Check-list'></a>";
								}
							}
							elseif($row['Statut']=="REC"){
								if($_SESSION['Id_PersonneTR']==$row['Id_Preparateur']){$baliseAC="<a href='javascript:OuvreFenetreReControle(".$row['Id'].")'><img src='../../Images/checklist2.png' width='25px' border='0' alt='Check-list' title='Check-list'></a>";}
								else{$baliseAC="<a href='javascript:OuvreFenetreAfficheControle(".$row['Id'].")'><img src='../../Images/checklist.png' width='25px' border='0' alt='Check-list' title='Check-list'></a>";}
							}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td <?php if($tabVisible[0]==0){echo "style='display:none;'";} ?>>&nbsp;<?php echo $row['Designation'];?></td>
									<td <?php if($tabVisible[1]==0){echo "style='display:none;'";} ?>><?php echo AfficheDateFR($row['DatePrepa']);?></td>
									<td <?php if($tabVisible[2]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['WP']));?></td>
									<td <?php if($tabVisible[3]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['FamilleTache']));?></td>
									<td <?php if($tabVisible[4]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['Tache'])); ?></td>
									<td <?php if($tabVisible[5]==0){echo "style='display:none;'";} ?>><?php echo $statut; ?></td>
									<td <?php if($tabVisible[6]==0){echo "style='display:none;'";} ?> align="center"><?php echo $row['TempsAlloue']; ?></td>
									<?php 
										if($nbResultaPlanning>0){
									?>
										<td <?php if($tabVisible[7]==0){echo "style='display:none;'";} ?> align="center"><?php echo $row['TempsPasse']; ?></td>
									<?php
										}
									?>
									<td <?php if($tabVisible[8]==0){echo "style='display:none;'";} ?>><?php echo $row['Preparateur']; ?></td>
									<td <?php if($tabVisible[9]==0){echo "style='display:none;'";} ?>><?php echo $row['Controleur']; ?></td>
									<td <?php if($tabVisible[10]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$Infos)); ?></td>
									<td <?php if($tabVisible[11]==0){echo "style='display:none;'";} ?>><?php echo $row['Responsable']; ?></td>
									<td <?php if($tabVisible[12]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['RaisonRefus'])); ?></td>
									<td <?php if($tabVisible[13]==0){echo "style='display:none;'";} ?>><?php echo $row['StatutDelai']; ?></td>
									<td <?php if($tabVisible[14]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['CommentaireDelai'])); ?></td>
									<td <?php if($tabVisible[15]==0){echo "style='display:none;'";} ?>><?php echo stripslashes(str_replace("\\","",$row['DescriptionModification'])); ?></td>
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
									if($Id_CL>0 && $Id_CLVersion>0 && $nbResultaContenuVersion>0 && $row['Controle']==0 && $row['Statut']=="A VALIDER" && (substr($_SESSION['DroitTR'],1,1)==1 || substr($_SESSION['DroitTR'],3,1)==1 || $_SESSION['Id_PersonneTR']==$row['Id_Preparateur'])){
									?>
										<a href="javascript:OuvreFenetreC(<?php echo $row['Id'].",'".$_SESSION['Langue']."'"; ?>)">
											<img src='../../Images/c.png' style="width:22px;" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Ask for control";}else{echo "Demander le contrôle";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Ask for control";}else{echo "Demander le contrôle";} ?>'>
										</a>
									<?php
									}
									?>
									</td>
									<td><?php 
									if($_SESSION['Id_PersonneTR']==$row['Id_Preparateur'] || substr($_SESSION['DroitTR'],2,1)=='1' || (substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1 || substr($_SESSION['DroitTR'],4,1)=='1') ){
										echo $baliseAC; 
									}
									?>
									
									</td>
									<td align="center">
										<a href="javascript:OuvreFenetreLecture(<?php echo $row['Id']; ?>)">
											<img src='../../Images/Loupe.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Display";}else{echo "Visualiser";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Display";}else{echo "Visualiser";} ?>'>
										</a>
									</td>
									<td align="center">
										<?php 
											if((substr($_SESSION['DroitTR'],0,1)==1 && $_SESSION['Id_PersonneTR']==$row['Id_Preparateur'] && $row['Statut']<>"VALIDE") || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1 || substr($_SESSION['DroitTR'],4,1)==1){
										?>
										<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
											<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
										</a>
										<?php } ?>
									</td>
									<td align="center">
										<?php 
											if((substr($_SESSION['DroitTR'],0,1)==1 && $_SESSION['Id_PersonneTR']==$row['Id_Preparateur'] && $row['Statut']<>"VALIDE") || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1 || substr($_SESSION['DroitTR'],4,1)==1){
										?>
										<a href="javascript:OuvreFenetreAnomalie(<?php echo $row['Id']; ?>)">
											<img src='../../Images/A.png' width="15px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Anomaly";}else{echo "Anomalie";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Anomaly";}else{echo "Anomalie";} ?>'>
										</a>
										<?php } ?>
									</td>
									<td align="center">
										<?php 
											if($row['SupprTache']==0){
											if(substr($_SESSION['DroitTR'],0,1)==1 || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1 || substr($_SESSION['DroitTR'],4,1)==1){
										
										?>
										<a href="javascript:OuvreFenetreDupliquer(<?php echo $row['Id']; ?>)">
											<img src='../../Images/add.png' border='0' style="width:18px;height:18px;" alt='<?php if($_SESSION['Langue']=="EN"){echo "Duplicate";}else{echo "Dupliquer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Duplicate";}else{echo "Dupliquer";} ?>'>
										</a>
											<?php }} ?>
									</td>
									<td align="right">
										<?php 
											if((substr($_SESSION['DroitTR'],0,1)==1 && $_SESSION['Id_PersonneTR']==$row['Id_Preparateur'] && $row['Statut']<>"VALIDE") || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1 || substr($_SESSION['DroitTR'],4,1)==1){
										?>
										<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
											<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
										</a>
										<?php } ?>
									</td>
								</tr>
							<?php
						}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="15"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="15%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Number of lines per page ";}else{echo "Nombre de ligne par page ";}?></td>
				<td width="60%">
					<input type="text" id="nbLigne" name="nbLigne" size="10" value="<?php echo $_SESSION['PROD_NbLigne'];?>"/>
					<input class="Bouton" name="BtnNbLigne" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
		</table>
	</td></tr>
</form>
</table>

<?php
	//echo date("h:i:s")."<br>";
	echo "<script>console.log('".json_encode(date("h:i:s"))."');</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>