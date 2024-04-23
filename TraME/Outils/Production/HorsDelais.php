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
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereHD.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=850,height=300");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereHD.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function OuvreFenetreModif(){
			var elements = document.getElementsByClassName("check");
			Id="";
			for(var i=0, l=elements.length; i<l; i++){
				//Tu fais ce que tu veux avec l'élément parcouru
				if(elements[i].checked ==true){
					Id+=elements[i].name+";";
				}
			}
			if(Id!=""){
				var w=window.open("Ajout_HorsDelais.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=200");
				w.focus();
			}
		}
		function Excel(){
			var w=window.open("Extract_HorsDelais.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function SelectionnerTout(){			
			var elements = document.getElementsByClassName("check");
			if (formulaire.selectAll.checked == true){
				for(var i=0, l=elements.length; i<l; i++){
					//Tu fais ce que tu veux avec l'élément parcouru
					elements[i].checked = true;
				}
			}
			else{
				for(var i=0, l=elements.length; i<l; i++){
					//Tu fais ce que tu veux avec l'élément parcouru
					elements[i].checked = false;
				}
			}
		}
	</script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
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

$_SESSION['Formulaire']="Production/HorsDelais.php";
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['HorsDelais_Reference']="";
		$_SESSION['HorsDelais_DateDebut']="";
		$_SESSION['HorsDelais_DateFin']="";
		$_SESSION['HorsDelais_WP']="";
		$_SESSION['HorsDelais_Tache']="";
		$_SESSION['HorsDelais_Statut']="";
		$_SESSION['HorsDelais_Preparateur']="";
		$_SESSION['HorsDelais_MotCles']="";
		$_SESSION['HorsDelais_RespDelais']="";
		$_SESSION['HorsDelais_CauseDelais']="";
		
		$_SESSION['HorsDelais_Reference2']="";
		$_SESSION['HorsDelais_DateDebut2']="";
		$_SESSION['HorsDelais_DateFin2']="";
		$_SESSION['HorsDelais_WP2']="";
		$_SESSION['HorsDelais_Tache2']="";
		$_SESSION['HorsDelais_Statut2']="";
		$_SESSION['HorsDelais_Preparateur2']="";
		$_SESSION['HorsDelais_MotCles2']="";
		$_SESSION['HorsDelais_RespDelais2']="";
		$_SESSION['HorsDelais_CauseDelais2']="";
		
		$_SESSION['HorsDelais_PageDateDebut2']="";
		$_SESSION['HorsDelais_PageDateFin2']="";
		$_SESSION['HorsDelais_PagePreparateur2']="";
		$_SESSION['HorsDelais_PageWP2']="";
		
		$_SESSION['HorsDelais_ModeFiltre']="oui";
		$_SESSION['HorsDelais_Page']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['HorsDelais_PageDateDebut2']=$_POST['dateDebut'];
		$_SESSION['HorsDelais_PageDateFin2']=$_POST['dateFin'];
		$_SESSION['HorsDelais_PagePreparateur2']=$_POST['preparateur'];
		$_SESSION['HorsDelais_PageWP2']=$_POST['wp'];
		$_SESSION['HorsDelais_ModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriHorsDelais_Reference']="";
		$_SESSION['TriHorsDelais_Date']="";
		$_SESSION['TriHorsDelais_WP']="";
		$_SESSION['TriHorsDelais_Tache']="";
		$_SESSION['TriHorsDelais_StatutDelais']="";
		$_SESSION['TriHorsDelais_RespDelais']="";
		$_SESSION['TriHorsDelais_CauseDelais']="";
		$_SESSION['TriHorsDelais_Statut']="";
		$_SESSION['TriHorsDelais_Preparateur']="";
		$_SESSION['TriHorsDelais_General']="";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" id="formulaire" method="POST" action="HorsDelais.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "LEADTIMES";}else{echo "HORS DELAIS";} ?></td>
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
				<input type="date" name="dateDebut" size="10" id="datepicker" value="<?php echo $_SESSION['HorsDelais_PageDateDebut2'];?>"/>
			&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?>&nbsp;
			<input type="date" name="dateFin"  size="10" value="<?php echo $_SESSION['HorsDelais_PageDateFin2'];?>"/>
			&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?>&nbsp;
				<select id="preparateur" name="preparateur">
					<?php
						echo"<option value=''></option>";
						$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM trame_travaileffectue LEFT JOIN new_rh_etatcivil on trame_travaileffectue.Id_Preparateur=new_rh_etatcivil.Id WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowPrepa=mysqli_fetch_array($result)){
								$selected="";
								if($_SESSION['HorsDelais_PagePreparateur2']==$rowPrepa['Id']){$selected="selected";}
								echo "<option ".$selected." value=\"".$rowPrepa['Id']."\">".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."</option>";
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
								if($_SESSION['HorsDelais_PageWP2']==$rowWP['Id']){$selected="selected";}
								echo "<option ".$selected." value=\"".$rowWP['Id']."\">".$rowWP['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<?php
			if($_SESSION['HorsDelais_MotCles']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Keywords : ".$_SESSION['HorsDelais_MotCles']."</td>";
				}
				else{
					echo "<td>&nbsp; Mots clés : ".$_SESSION['HorsDelais_MotCles']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_Reference']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; References : ".$_SESSION['HorsDelais_Reference']."</td>";
				}
				else{
					echo "<td>&nbsp; Références : ".$_SESSION['HorsDelais_Reference']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_Tache']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Tasks : ".$_SESSION['HorsDelais_Tache']."</td>";
				}
				else{
					echo "<td>&nbsp; Tâches : ".$_SESSION['HorsDelais_Tache']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_Preparateur']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Manufacturing Engineer : ".$_SESSION['HorsDelais_Preparateur']."</td>";
				}
				else{
					echo "<td>&nbsp; Préparateurs : ".$_SESSION['HorsDelais_Preparateur']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_Statut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Status : ".$_SESSION['HorsDelais_Statut']."</td>";
				}
				else{
					echo "<td>&nbsp; Statuts : ".$_SESSION['HorsDelais_Statut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_WP']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Workpackages : ".$_SESSION['HorsDelais_WP']."</td>";
				}
				else{
					echo "<td>&nbsp; Workpackages : ".$_SESSION['HorsDelais_WP']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_DateDebut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Start date : ".$_SESSION['HorsDelais_DateDebut']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de début : ".$_SESSION['HorsDelais_DateDebut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_DateFin']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; End date : ".$_SESSION['HorsDelais_DateFin']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de fin : ".$_SESSION['HorsDelais_DateFin']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_RespDelais']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Responsible for delays : ".$_SESSION['HorsDelais_RespDelais']."</td>";
				}
				else{
					echo "<td>&nbsp; Responsable délais : ".$_SESSION['HorsDelais_RespDelais']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['HorsDelais_CauseDelais']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Cause of delay : ".$_SESSION['HorsDelais_CauseDelais']."</td>";
				}
				else{
					echo "<td>&nbsp; Cause délais : ".$_SESSION['HorsDelais_CauseDelais']."</td>";
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
	<tr><td>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="Reference"){
					$_SESSION['TriHorsDelais_General']= str_replace("Designation ASC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Designation DESC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Designation ASC","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Designation DESC","",$_SESSION['TriHorsDelais_General']);
					if($_SESSION['TriHorsDelais_Reference']==""){$_SESSION['TriHorsDelais_Reference']="ASC";$_SESSION['TriHorsDelais_General'].= "Designation ".$_SESSION['TriHorsDelais_Reference'].",";}
					elseif($_SESSION['TriHorsDelais_Reference']=="ASC"){$_SESSION['TriHorsDelais_Reference']="DESC";$_SESSION['TriHorsDelais_General'].= "Designation ".$_SESSION['TriHorsDelais_Reference'].",";}
					else{$_SESSION['TriHorsDelais_Reference']="";}
				}
				if($_GET['Tri']=="DateTravail"){
					$_SESSION['TriHorsDelais_General']= str_replace("DatePreparateur ASC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("DatePreparateur DESC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("DatePreparateur ASC","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("DatePreparateur DESC","",$_SESSION['TriHorsDelais_General']);
					if($_SESSION['TriHorsDelais_Date']==""){$_SESSION['TriHorsDelais_Date']="ASC";$_SESSION['TriHorsDelais_General'].= "DatePreparateur ".$_SESSION['TriHorsDelais_Date'].",";}
					elseif($_SESSION['TriHorsDelais_Date']=="ASC"){$_SESSION['TriHorsDelais_Date']="DESC";$_SESSION['TriHorsDelais_General'].= "DatePreparateur ".$_SESSION['TriHorsDelais_Date'].",";}
					else{$_SESSION['TriHorsDelais_Date']="";}
				}
				if($_GET['Tri']=="WP"){
					$_SESSION['TriHorsDelais_General']= str_replace("WP ASC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("WP DESC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("WP ASC","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("WP DESC","",$_SESSION['TriHorsDelais_General']);
					if($_SESSION['TriHorsDelais_WP']==""){$_SESSION['TriHorsDelais_WP']="ASC";$_SESSION['TriHorsDelais_General'].= "WP ".$_SESSION['TriHorsDelais_WP'].",";}
					elseif($_SESSION['TriHorsDelais_WP']=="ASC"){$_SESSION['TriHorsDelais_WP']="DESC";$_SESSION['TriHorsDelais_General'].= "WP ".$_SESSION['TriHorsDelais_WP'].",";}
					else{$_SESSION['TriHorsDelais_WP']="";}
				}
				if($_GET['Tri']=="Tache"){
					$_SESSION['TriHorsDelais_General']= str_replace("Tache ASC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Tache DESC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Tache ASC","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Tache DESC","",$_SESSION['TriHorsDelais_General']);
					if($_SESSION['TriHorsDelais_Tache']==""){$_SESSION['TriHorsDelais_Tache']="ASC";$_SESSION['TriHorsDelais_General'].= "Tache ".$_SESSION['TriHorsDelais_Tache'].",";}
					elseif($_SESSION['TriHorsDelais_Tache']=="ASC"){$_SESSION['TriHorsDelais_Tache']="DESC";$_SESSION['TriHorsDelais_General'].= "Tache ".$_SESSION['TriHorsDelais_Tache'].",";}
					else{$_SESSION['TriHorsDelais_Tache']="";}
				}
				if($_GET['Tri']=="Statut"){
					$_SESSION['TriHorsDelais_General']= str_replace("Statut ASC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Statut DESC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Statut ASC","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Statut DESC","",$_SESSION['TriHorsDelais_General']);
					if($_SESSION['TriHorsDelais_Statut']==""){$_SESSION['TriHorsDelais_Statut']="ASC";$_SESSION['TriHorsDelais_General'].= "Statut ".$_SESSION['TriHorsDelais_Statut'].",";}
					elseif($_SESSION['TriHorsDelais_Statut']=="ASC"){$_SESSION['TriHorsDelais_Statut']="DESC";$_SESSION['TriHorsDelais_General'].= "Statut ".$_SESSION['TriHorsDelais_Statut'].",";}
					else{$_SESSION['TriHorsDelais_Statut']="";}
				}
				if($_GET['Tri']=="Preparateur"){
					$_SESSION['TriHorsDelais_General']= str_replace("Preparateur ASC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Preparateur DESC,","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Preparateur ASC","",$_SESSION['TriHorsDelais_General']);
					$_SESSION['TriHorsDelais_General']= str_replace("Preparateur DESC","",$_SESSION['TriHorsDelais_General']);
					if($_SESSION['TriHorsDelais_Preparateur']==""){$_SESSION['TriHorsDelais_Preparateur']="ASC";$_SESSION['TriHorsDelais_General'].= "Preparateur ".$_SESSION['TriHorsDelais_Preparateur'].",";}
					elseif($_SESSION['TriHorsDelais_Preparateur']=="ASC"){$_SESSION['TriHorsDelais_Preparateur']="DESC";$_SESSION['TriHorsDelais_General'].= "Preparateur ".$_SESSION['TriHorsDelais_Preparateur'].",";}
					else{$_SESSION['TriHorsDelais_Preparateur']="";}
				}
			}
			if($_SESSION['HorsDelais_ModeFiltre']=="oui"){
				$reqAnalyse="SELECT trame_travaileffectue.Id ";
				$req2="SELECT Id,Statut,Designation,DatePreparateur,StatutDelai,DescriptionModification,Id_Preparateur,StatutDelai, ";
				$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS WP,CommentaireDelai, ";
				$req2.="(SELECT Libelle FROM trame_responsabledelais WHERE trame_responsabledelais.Id=trame_travaileffectue.Id_ResponsableDelai) AS RespDelais, ";
				$req2.="(SELECT Libelle FROM trame_causedelais WHERE trame_causedelais.Id=trame_travaileffectue.Id_CauseDelai) AS CauseDelais, ";
				$req2.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Tache, ";
				$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Preparateur ";
				$req="FROM trame_travaileffectue WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND StatutDelai='KO' AND ";
				if($_SESSION['HorsDelais_Reference2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_Reference2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Designation='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_PageWP2']<>""){
					$req.="Id_WP=".$_SESSION['HorsDelais_PageWP2']." AND ";
				}
				if($_SESSION['HorsDelais_WP2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_WP2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_WP=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_Tache2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_Tache2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Tache=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_RespDelais2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_RespDelais2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_ResponsableDelai=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_CauseDelais2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_CauseDelais2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_CauseDelai=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_Statut2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_Statut2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Statut='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_PagePreparateur2']<>""){
					$req.="Id_Preparateur=".$_SESSION['HorsDelais_PagePreparateur2']." AND ";
				}
				if($_SESSION['HorsDelais_Preparateur2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_Preparateur2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Preparateur=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_MotCles2']<>""){
					$tab = explode(";",$_SESSION['HorsDelais_MotCles2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Designation LIKE '%".$valeur."%' OR DescriptionModification LIKE '%".$valeur."%' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['HorsDelais_PageDateDebut2']<>""){
					$req.="DatePreparateur>='".TrsfDate_($_SESSION['HorsDelais_PageDateDebut2'])."' AND ";
				}
				if($_SESSION['HorsDelais_PageDateFin2']<>""){
					$req.="DatePreparateur<='".TrsfDate_($_SESSION['HorsDelais_PageDateFin2'])."' AND ";
				}
				if($_SESSION['HorsDelais_DateDebut2']<>"" || $_SESSION['HorsDelais_DateFin2']<>""){
					$req.=" ( ";
					if($_SESSION['HorsDelais_DateDebut2']<>""){
						$req.="DatePreparateur >= '". TrsfDate_($_SESSION['HorsDelais_DateDebut2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['HorsDelais_DateFin2']<>""){
						$req.="DatePreparateur <= '". TrsfDate_($_SESSION['HorsDelais_DateFin2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
				
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['TriHorsDelais_General']<>""){
					$req.="ORDER BY ".substr($_SESSION['TriHorsDelais_General'],0,-1);
				}

				$nombreDePages=ceil($nbResulta/50);
				if(isset($_GET['Page'])){$_SESSION['HorsDelais_Page']=$_GET['Page'];}
				else{$_SESSION['HorsDelais_Page']=0;}
				$req3=" LIMIT ".($_SESSION['HorsDelais_Page']*50).",50";

				$result=mysqli_query($bdd,$req2.$req.$req3);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				if($_SESSION['HorsDelais_ModeFiltre']=="oui"){
					$nbPage=0;
					if($_SESSION['HorsDelais_Page']>1){echo "<b> <a style='color:#00599f;' href='HorsDelais.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['HorsDelais_Page']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['HorsDelais_Page']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['HorsDelais_Page']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['HorsDelais_Page']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='HorsDelais.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['HorsDelais_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='HorsDelais.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				}
			?>
		</td>
	</tr>
	<tr>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=Reference"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?><?php if($_SESSION['TriHorsDelais_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_Reference']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=DateTravail"><?php if($_SESSION['Langue']=="EN"){echo "Date of work";}else{echo "Date du travail";} ?><?php if($_SESSION['TriHorsDelais_Date']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_Date']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=WP"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?><?php if($_SESSION['TriHorsDelais_WP']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_WP']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=Tache"><?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?><?php if($_SESSION['TriHorsDelais_Tache']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_Tache']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=StatutDelai"><?php if($_SESSION['Langue']=="EN"){echo "Deadline";}else{echo "Statut du délais";} ?><?php if($_SESSION['TriHorsDelais_StatutDelais']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_StatutDelais']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=RespDelais"><?php if($_SESSION['Langue']=="EN"){echo "Responsible of delay";}else{echo "Responsable délais";} ?><?php if($_SESSION['TriHorsDelais_RespDelais']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_RespDelais']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=CauseDelais"><?php if($_SESSION['Langue']=="EN"){echo "Cause of delay";}else{echo "Cause délais";} ?><?php if($_SESSION['TriHorsDelais_CauseDelais']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_CauseDelais']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=Statut"><?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Statut";} ?><?php if($_SESSION['TriHorsDelais_Statut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_Statut']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri=Preparateur"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?><?php if($_SESSION['TriHorsDelais_Preparateur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriHorsDelais_Preparateur']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="11%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="HorsDelais.php?Tri="><?php if($_SESSION['Langue']=="EN"){echo "Further information";}else{echo "Infos complémentaires";} ?></a></td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreModif()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($_SESSION['Langue']=="EN"){echo "Select all";}else{echo "Sélectionner tout";} ?>
				</td>
			</tr>
			<?php
				if($_SESSION['HorsDelais_ModeFiltre']=="oui"){
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
							$nbResultaInfo=mysqli_num_rows($result);
							if ($nbResultaInfo>0){
								while($rowInfo=mysqli_fetch_array($resultInfo)){
									if($rowInfo['Type']=="Date"){
										$Infos.=$rowInfo['Info']." : ".AfficheDateFR($rowInfo['ValeurInfo'])."<br>";
									}
									else{
										$Infos.=$rowInfo['Info']." : ".$rowInfo['ValeurInfo']."<br>";
									}
								}
							}
							$commentaireDelais="";
							$Hover="";
							$infoBulle ="";
							if($row['CommentaireDelai']<>""){
								$Hover="id='leHover2'";
								$infoBulle = "\n<span>".stripslashes(str_replace("\\","",$row['CommentaireDelai']))."</span>\n";
							}
							
							$statut=$row['Statut'];
							if($_SESSION['Langue']=="EN"){
								if($row['Statut']=="EN COURS"){$statut="IN PROGRESS";}
								elseif($row['Statut']=="BLOQUE"){$statut="BLOCKED";}
								elseif($row['Statut']=="EN ATTENTE"){$statut="WAITING";}
								elseif($row['Statut']=="A VALIDER"){$statut="TO BE VALIDATED";}
								elseif($row['Statut']=="VALIDE"){$statut="VALIDATED";}
								elseif($row['Statut']=="REFUSE"){$statut="RETURN";}
							}
							else{
								if($row['Statut']=="REFUSE"){$statut="RETOURNE";}
							}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="8%">&nbsp;<?php echo $row['Designation'];?></td>
									<td width="8%"><?php echo AfficheDateFR($row['DatePreparateur']);?></td>
									<td width="10%"><?php echo stripslashes(str_replace("\\","",$row['WP']));?></td>
									<td width="12%"><?php echo stripslashes(str_replace("\\","",$row['Tache'])); ?></td>
									<td width="5%"><?php echo $row['StatutDelai']; ?></td>
									<td width="8%"><?php echo $row['RespDelais']; ?></td>
									<td width="8%" <?php echo $Hover;?>><?php echo stripslashes(str_replace("\\","",$row['CauseDelais'])).$infoBulle; ?></td>
									<td width="8%"><?php echo $statut; ?></td>
									<td width="10%"><?php echo $row['Preparateur']; ?></td>
									<td width="11%"><?php echo stripslashes(str_replace("\\","",$Infos)); ?></td>
									<td width="12%" align="left">
										<input class="check" type="checkbox" name="<?php echo $row['Id']; ?>" id="<?php echo $row['Id']; ?>"/>
									</td>
								</tr>
							<?php
						}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>