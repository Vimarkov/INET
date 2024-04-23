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
			for(y=0;y<document.getElementById('Id_Plateforme').length;y++)
			{
				if(document.getElementById('Id_Plateforme').options[y].selected == true)
				{
					nouvel_element = new Option(document.getElementById('Id_Plateforme').options[y].text,document.getElementById('Id_Plateforme').options[y].value,false,false);
					document.getElementById('PlateformeSelect').options[document.getElementById('PlateformeSelect').length] = nouvel_element;
					document.getElementById('Id_Plateforme').options[y] = null;
				}
			}
			
			Liste= new Array();
			Obj= document.getElementById('PlateformeSelect')
			 
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
			for(y=0;y<document.getElementById('PlateformeSelect').length;y++)
			{
				if(document.getElementById('PlateformeSelect').options[y].selected == true)
				{
					nouvel_element = new Option(document.getElementById('PlateformeSelect').options[y].text,document.getElementById('PlateformeSelect').options[y].value,false,false);
					document.getElementById('Id_Plateforme').options[document.getElementById('Id_Plateforme').length] = nouvel_element;
					document.getElementById('PlateformeSelect').options[y] = null;
				}
			}
			
			Liste= new Array();
			Obj= document.getElementById('Id_Plateforme')
			 
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
			for(y=0;y<document.getElementById('PlateformeSelect').length;y++){
				document.getElementById('PlateformeSelect').options[y].selected = true;
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
	$Liste="";
	if(isset($_POST['PlateformeSelect']))
	{
		$ListeSelect = $_POST['PlateformeSelect'];
		for($i=0;$i<sizeof($ListeSelect);$i++)
		{
			if(isset($ListeSelect[$i])){$Liste.=$ListeSelect[$i].";";}
		}
	}
	$TabListe = preg_split("/[;]+/", $Liste);
	
	$taux=0;
	if($_POST['taux']<>""){$taux=$_POST['taux'];}
	
	for($i=0;$i<sizeof($TabListe)-1;$i++){
		$result=mysqli_query($bdd,"SELECT Id FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Prestation=0 AND Id_Plateforme=".$TabListe[$i]." ");
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			$row=mysqli_fetch_array($result);
			$req="UPDATE rh_parametrage_cout SET Taux=".$taux.", DateCreation='".date('Y-m-d')."', Id_Createur=".$_SESSION['Id_Personne']." WHERE Id=".$row['Id'];
		}
		else{
			$req="INSERT INTO rh_parametrage_cout (Id_Plateforme,Id_Prestation,Id_TypeMetier,Id_Vacation,Taux,DateCreation,Id_Createur) VALUES (".$TabListe[$i].",0,0,0,".$taux.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].")";
		}
		$resultInsertUpdate=mysqli_query($bdd,$req);
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
	
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_TauxPlateforme.php" onsubmit=" return selectall();">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" id="Menu" name="Menu" value="<?php echo $_GET['Menu']; ?>">
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr>
				<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td width="35%" valign="top">
					<select name="Id_Plateforme" id="Id_Plateforme" multiple size="10" onDblclick="ajouter();">
					<?php
					$rq="SELECT Id, Libelle
						FROM new_competences_plateforme
						WHERE Id NOT IN (11,14)
						AND Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteControleGestion.")
						)
						ORDER BY Libelle";
					$result=mysqli_query($bdd,$rq);
					$i=0;
					while($row=mysqli_fetch_array($result))
					{
						echo "<option value='".$row['Id']."'>".str_replace("'"," ",$row['Libelle'])."</option>\n";
						echo "<script>Liste_Plateforme[".$i."] = new Array(".$row['Id'].",'".str_replace("'"," ",$row['Libelle'])."');</script>";
						$i+=1;
					}
					?>
					</select>
				</td>
				<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unités d'exploitations sélectionnées (double-clic) :";}else{echo "Selected operating unit (double-click) :";} ?></td>
				<td width="30%" valign="top">
					<select name="PlateformeSelect[]" id="PlateformeSelect" multiple size="10" onDblclick="effacer();"></select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Taux horaire moyen";}else{echo "Average hourly rate";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="taux" size="5" type="text" value=""></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?>" />
				</td>
			</tr>
		</table>
		</form>
<?php
}
?>
</body>
</html>