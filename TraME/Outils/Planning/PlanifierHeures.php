<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(laDate){
			window.opener.parent.location="Pointage.php?laDate="+laDate;
			window.close();
		}
		function VerifChamps(langue){
			
			if(formulaire.semaineDebut.value>formulaire.semaineFin.value){
				if(langue=="EN"){
						alert('The start week must be less than the end week.');
					}
					else{
						alert('La semaine de début doit être inférieure à la semaine de fin.');
					}
				return false;
			}
			return true;
		}
	function nombre(champ){
		var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
		var verif;
		var points = 0; /* Supprimer cette ligne */

		for(x = 0; x < champ.value.length; x++)
		{
		verif = chiffres.test(champ.value.charAt(x));
		if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
		if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
		if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
		}
	}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();
if($_POST){
	
	if(isset($_POST['btn_save'])){
		for($i=-14;$i<=49;$i=$i+7){
			if(date("Y/W",strtotime($_POST['laDateEC']." ".$i." day"))>=$_POST['semaineDebut'] && date("Y/W",strtotime($_POST['laDateEC']." ".$i." day"))<=$_POST['semaineFin']){
				$nbHeure=0;
				$semaine=date("W",strtotime($_POST['laDateEC']." ".$i." day"));
				$annee=date("Y",strtotime($_POST['laDateEC']." ".$i." day"));
				if($_POST['nbHeure']<>""){
					$nbHeure=$_POST['nbHeure'];
				}
				$req="SELECT Id FROM trame_plannif WHERE Id_Preparateur=".$_POST['preparateur']." AND Semaine=".$semaine."  AND Annee=".$annee." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if($nbResulta>0){
					$req="UPDATE trame_plannif SET NbHeure=".$nbHeure.", Id_Responsable=".$_SESSION['Id_PersonneTR'].", DateMAJ='".date("Y-m-d")."' WHERE Id_Preparateur=".$_POST['preparateur']." AND Semaine=".$semaine."  AND Annee=".$annee." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
					$result=mysqli_query($bdd,$req);
				}
				else{
					$req="INSERT INTO trame_plannif (Id_Preparateur,Semaine,Annee,NbHeure,Id_Responsable,DateMAJ,Id_Prestation) ";
					$req.="VALUES (".$_POST['preparateur'].",".$semaine.",".$annee.",".$nbHeure.",".$_SESSION['Id_PersonneTR'].",'".date("Y-m-d")."',".$_SESSION['Id_PrestationTR'].") ";
					$result=mysqli_query($bdd,$req);
				}
			}
		}

	}
	echo "<script>FermerEtRecharger('".$_POST['laDateEC']."');</script>";
}
elseif($_GET)
{
$laDate=$_GET['laDate'];
?>

	<form id="formulaire" method="POST" action="PlanifierHeures.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<table width="95%" align="center" class="TableCompetences">
		<tr class="TitreColsUsers">
			<input type="hidden" name="laDateEC" id="laDateEC" value="<?php echo $_GET['laDate']; ?>">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing engineer";}else{echo "Préparateur";} ?></td>
			<td>
				<select id="preparateur" name="preparateur" colspan="2">
					<?php
						$req="SELECT Id_Personne, Nom, Prenom FROM trame_acces ";
						$req.="LEFT JOIN new_rh_etatcivil ON trame_acces.Id_Personne=new_rh_etatcivil.Id WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND (LEFT(Droit,1)=1 OR MID(Droit,2,1)=1) ORDER BY Nom, Prenom";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowPreparateur=mysqli_fetch_array($result)){
								echo "<option value='".$rowPreparateur['Id_Personne']."'>".$rowPreparateur['Nom']." ".$rowPreparateur['Prenom']."</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Week";}else{echo "Semaine";} ?></td>
			<td>
				<select id="semaineDebut" name="semaineDebut">
					<?php
						echo "<option value='".date("Y/W",strtotime($laDate." -14 day"))."'> ".date("W/Y",strtotime($laDate." -14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." -7 day"))."'> ".date("W/Y",strtotime($laDate." -7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +0 day"))."'> ".date("W/Y",strtotime($laDate." +0 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +7 day"))."'> ".date("W/Y",strtotime($laDate." +7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +14 day"))."'> ".date("W/Y",strtotime($laDate." +14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +21 day"))."'> ".date("W/Y",strtotime($laDate." +21 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +28 day"))."'> ".date("W/Y",strtotime($laDate." +28 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +35 day"))."'> ".date("W/Y",strtotime($laDate." +35 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +42 day"))."'> ".date("W/Y",strtotime($laDate." +42 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +49 day"))."'> ".date("W/Y",strtotime($laDate." +49 day"))."</option>";
					?>
				</select>
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "to";}else{echo "à";} ?></td>
			<td>
				<select id="semaineFin" name="semaineFin">
					<?php
						echo "<option value='".date("Y/W",strtotime($laDate." -14 day"))."'> ".date("W/Y",strtotime($laDate." -14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." -7 day"))."'> ".date("W/Y",strtotime($laDate." -7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +0 day"))."'> ".date("W/Y",strtotime($laDate." +0 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +7 day"))."'> ".date("W/Y",strtotime($laDate." +7 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +14 day"))."'> ".date("W/Y",strtotime($laDate." +14 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +21 day"))."'> ".date("W/Y",strtotime($laDate." +21 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +28 day"))."'> ".date("W/Y",strtotime($laDate." +28 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +35 day"))."'> ".date("W/Y",strtotime($laDate." +35 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +42 day"))."'> ".date("W/Y",strtotime($laDate." +42 day"))."</option>";
						echo "<option value='".date("Y/W",strtotime($laDate." +49 day"))."'> ".date("W/Y",strtotime($laDate." +49 day"))."</option>";
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Nb hours";}else{echo "Nb heures";} ?>
			</td>
			<td>
				<input type="text" onKeyUp='nombre(this)' size="10px" name="nbHeure" id="nbHeure" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="6" align="center">
				<input class="Bouton" type="submit" name="btn_save" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
			</td>
		</tr>
	</table>
	</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>