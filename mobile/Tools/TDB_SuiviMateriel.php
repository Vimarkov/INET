<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../../v2/Outils/Fonctions.php");
require_once("../../v2/Outils/Formation/Globales_Fonctions.php");
require_once("../../v2/Outils/Tools/Fonctions.php");
?>

<html>
<head>
	<title>Suivi du matériel - Tableau de bord</title><meta name="robots" content="noindex">
	
	<link rel="stylesheet" href="../../v2/Outils/JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../v2/CSS/Perfos.css">
	<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../v2/CSS/New_Menu2.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../../v2/CSS/Menu2.css">
	
	<script src="../../v2/Outils/JS/modernizr.js"></script>	
	<script src="../../v2/Outils/JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../v2/Outils/JS/js/jquery-ui-1.8.5.min.js"></script>	
	<script type="text/javascript" src="../../v2/Outils/JS/jquery.min.js"></script>
	<script type="text/javascript">
		function filtrer(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnFiltrer2' name='btnFiltrer2' value='Filtrer'>";
			document.getElementById('filtrer').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnFiltrer2").dispatchEvent(evt);
			document.getElementById('filtrer').innerHTML="";
		}
		function OuvreFenetreTransfert(Id){
			var w=window.open("Ajout_TransfertMateriel.php?Page=Materiel&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=850,height=650");
			w.focus();
		}
		function OuvreFenetreTransfertCaisse(Id){
			var w=window.open("Ajout_TransfertCaisse.php?Page=Materiel&Id="+Id,"PageToolsTransfert","status=no,menubar=no,width=650,height=650");
			w.focus();
		}
	</script>
	<style>
		html,body
		{
			background-color:#ffffff;
		}
		html {
		  height: 100%;
		  overflow-y: scroll;
		}
		body {
		  min-height: 100%;
		  margin: 0;
		  padding: 0;
		}
		body {position: relative;}
		footer {position: absolute; bottom: 0; left: 0; right: 0}
	</style>
</head>

<?php
Ecrire_Code_JS_Init_Date(); 

$Page="";
if(isset($_GET['Page'])){$Page=$_GET['Page'];}
if($_POST){
	if(isset($_POST['Page'])){$Page=$_POST['Page'];}
}
?>
<form id="formulaire" action="TDB_SuiviMateriel.php" method="post">
<?php 
if($Page=="Rechercher"){
?>
<table style="width:100%;height:100%;border-spacing:0; align:center;" valign="top">
	<tr height="150px" bgcolor="#d0d0d0">
		<td width="10%" align="left" valign="center">
			<img style="border: none;border-radius:12px 12px 12px 12px;" width="200px" src="../../v2/Images/Logos/Logo_AAA_FR.png" /> 
		</td>
		<td width="50%" align="left" valign="center">&nbsp;&nbsp;&nbsp;
			<?php
				$num=$_SESSION['FiltreToolsSuivi_Num'];
				if($_POST){$num=$_POST['num'];}
				$_SESSION['FiltreToolsSuivi_Num']=$num;
			?>
			&nbsp;&nbsp;&nbsp;<input id="num" name="num" type="texte" style="border: 1px solid #0066CC;height:50px;width:500px;font-size:40px;" value="<?php echo $num; ?>" />&nbsp;&nbsp;
		</td>
		<td width="40%" align="left" valign="center">
			<img id="btnFiltrer" name="btnFiltrer" width="80px" src="../../v2/Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
			<div id="filtrer"></div>
		</td>
	</tr>
	<tr height="95%">
		<td colspan="3" valign="top">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr height="5%" bgcolor="#d0d0d0">
					<td></td>
				</tr>
				<tr height="90%">
					<td>
						<div id='Div_Recherche' style="width:100%;height:1000px;overflow:auto;">
							<table style="width:100%; border-spacing:0; align:center;">
								<?php 
									if($Page=="Rechercher"){
										require "Rechercher.php";
									}
									elseif($Page=="Inventaire"){
										require "Inventaire.php";
									}
								?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php 
}
?>
<footer>
		<table style="width:100%;height:100%;border-spacing:0; align:center;">
			<input type="hidden" name="Page" value="<?php echo $Page;?>" />
			<tr height="5%" bgcolor="#91dfff">
				<td width="50%" align="center" style="font-size:50px;">
					<a style="text-decoration:none;<?php if($Page=="Rechercher"){echo "color:#ffffff;";}else{echo "color:#cff1ff;";} ?>" href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/mobile/Tools/TDB_SuiviMateriel.php?Page=Rechercher";?>" >
					<img style="border: none;" src="../../v2/Images/Loupe.gif" style="marginheight:0; marginwidth:0;" width="10%"><br>
					<?php if($LangueAffichage=="FR"){echo "RECHERCHER";}else{echo "SEARCH";}?>
					</a>
				</td>
				<td width="47%" align="center" style="font-size:50px;">
					<a style="text-decoration:none;<?php if($Page=="Inventaire"){echo "color:#ffffff;";}else{echo "color:#cff1ff;";} ?>" href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/mobile/Tools/TDB_SuiviMateriel.php?Page=Inventaire";?>" >
					<img style="border: none;" src="../../v2/Images/qcm.png" style="marginheight:0; marginwidth:0;" width="10%"><br>
					<?php if($LangueAffichage=="FR"){echo "INVENTAIRE";}else{echo "INVENTORY";}?>
					</a>
				</td>
			</tr>
		</table>
</footer>
</form>
</html>
	