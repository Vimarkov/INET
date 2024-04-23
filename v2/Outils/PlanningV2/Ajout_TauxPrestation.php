<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger(Menu)
		{
			window.opener.location="ParametrageCout.php?Menu="+Menu;
			window.close();
		}
		function ajouter(){
			for(y=0;y<document.getElementById('Id_Prestation').length;y++)
			{
				if(document.getElementById('Id_Prestation').options[y].selected == true)
				{
					nouvel_element = new Option(document.getElementById('Id_Prestation').options[y].text,document.getElementById('Id_Prestation').options[y].value,false,false);
					document.getElementById('PrestationSelect').options[document.getElementById('PrestationSelect').length] = nouvel_element;
					document.getElementById('Id_Prestation').options[y] = null;
				}
			}
			
			Liste= new Array();
			Obj= document.getElementById('PrestationSelect')
			 
			for(i=0;i<Obj.options.length;i++){
				Liste[i]=new Array()
				Liste[i][0]=Obj.options[i].text
				Liste[i][1]=Obj.options[i].value
			}
			Liste=Liste.sort()
			 
			for(i=0;i<Obj.options.length;i++){
				Obj.options[i].text=Liste[i][0]
				Obj.options[i].value=Liste[i][1]
			}
		}

		function effacer(){
			for(y=0;y<document.getElementById('PrestationSelect').length;y++)
			{
				if(document.getElementById('PrestationSelect').options[y].selected == true)
				{
					nouvel_element = new Option(document.getElementById('PrestationSelect').options[y].text,document.getElementById('PrestationSelect').options[y].value,false,false);
					document.getElementById('Id_Prestation').options[document.getElementById('Id_Prestation').length] = nouvel_element;
					document.getElementById('PrestationSelect').options[y] = null;
				}
			}
			
			Liste= new Array();
			Obj= document.getElementById('Id_Prestation')
			 
			for(i=0;i<Obj.options.length;i++){
				Liste[i]=new Array()
				Liste[i][0]=Obj.options[i].text
				Liste[i][1]=Obj.options[i].value
			}
			Liste=Liste.sort()
			 
			for(i=0;i<Obj.options.length;i++){
				Obj.options[i].text=Liste[i][0]
				Obj.options[i].value=Liste[i][1]
			}
		}

		function selectall(){
			for(y=0;y<document.getElementById('PrestationSelect').length;y++){
				document.getElementById('PrestationSelect').options[y].selected = true;
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("Fonctions_Planning.php");

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	if(isset($_POST['btnSave'])){
		$Liste="";
		if(isset($_POST['PrestationSelect']))
		{
			$ListeSelect = $_POST['PrestationSelect'];
			for($i=0;$i<sizeof($ListeSelect);$i++)
			{
				if(isset($ListeSelect[$i])){$Liste.=$ListeSelect[$i].";";}
			}
		}
		$TabListe = preg_split("/[;]+/", $Liste);
		
		$taux=0;
		if($_POST['taux']<>""){$taux=$_POST['taux'];}
		
		for($i=0;$i<sizeof($TabListe)-1;$i++){
			$result=mysqli_query($bdd,"SELECT Id FROM rh_parametrage_cout WHERE Suppr=0 AND Id_TypeMetier=0 AND Id_Plateforme=".$_POST['plateforme']." AND Id_Prestation=".$TabListe[$i]." ");
			$nbenreg=mysqli_num_rows($result);
			if($nbenreg>0)
			{
				$row=mysqli_fetch_array($result);
				$req="UPDATE rh_parametrage_cout SET Taux=".$taux.", DateCreation='".date('Y-m-d')."', Id_Createur=".$_SESSION['Id_Personne']." WHERE Id=".$row['Id'];
			}
			else{
				$req="INSERT INTO rh_parametrage_cout (Id_Plateforme,Id_Prestation,Id_TypeMetier,Id_Vacation,Taux,DateCreation,Id_Createur) VALUES (".$_POST['plateforme'].",".$TabListe[$i].",0,0,".$taux.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].")";
			}
			$resultInsertUpdate=mysqli_query($bdd,$req);
		}
		echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
	}
}

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>
	<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_TauxPrestation.php" onsubmit=" return selectall();">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
	<table style="width:95%; align:center;" class="TableCompetences">
		<tr>
			<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
			<td width="35%" valign="top">
				<select name="plateforme" id="plateforme" onchange="submit()">
					<option value="0"></option>
				<?php
				$rq="SELECT DISTINCT Id_Plateforme AS Id, 
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
					FROM rh_parametrage_cout
					WHERE Suppr=0 AND Id_Plateforme<>0
					AND Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteControleGestion.")
						)
					ORDER BY Libelle";
				$result=mysqli_query($bdd,$rq);
				$PlateformeSelect = 0;
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				while($row=mysqli_fetch_array($result))
				{
					$selected="";
					if($PlateformeSelect<>"")
						{if($PlateformeSelect==$row['Id']){$selected="selected";}}
					echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
				}
				?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
			<td width="35%" valign="top">
				<select name="Id_Prestation" id="Id_Prestation" multiple size="10" onDblclick="ajouter();">
				<?php
				$rq="SELECT Id, Libelle
					FROM new_competences_prestation
					WHERE Id_Plateforme=".$PlateformeSelect."
					AND Active=0
					ORDER BY Libelle";
				$result=mysqli_query($bdd,$rq);
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row['Id']."'>".str_replace("'"," ",$row['Libelle'])."</option>\n";
				}
				?>
				</select>
			</td>
			<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestations sélectionnées (double-clic) :";}else{echo "Selected site (double-click) :";} ?></td>
			<td width="30%" valign="top">
				<select name="PrestationSelect[]" id="PrestationSelect" multiple size="10" onDblclick="effacer();"></select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Taux horaire moyen";}else{echo "Average hourly rate";}?> : </td>
			<td colspan="3"><input onKeyUp="nombre(this)" name="taux" size="5" type="text" value=""></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input class="Bouton" type="submit" name="btnSave" id="btnSave" value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?>" />
			</td>
		</tr>
	</table>
	</form>
</body>
</html>