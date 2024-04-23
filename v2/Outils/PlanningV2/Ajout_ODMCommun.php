<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Contrat.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heure').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
		});
		
		function SelectionnerTout(Champ)
		{
			var elements = document.getElementsByClassName("check"+Champ);
			if (document.getElementById('selectAll'+Champ).checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
			}
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		$Personne="";
		
		$rq="SELECT DISTINCT Id_Personne,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('ODM')
			AND Id_Personne IN (
			SELECT Id_Personne
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant'))
			AND Id_Personne>0
			AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
			ORDER BY Personne ASC";
		$resultpersonne=mysqli_query($bdd,$rq);
		while($rowpersonne=mysqli_fetch_array($resultpersonne))
		{
			if(isset($_POST['Personne_'.$rowpersonne['Id_Personne']])){
				$Personne.=$_POST['Personne_'.$rowpersonne['Id_Personne']].";";
			}
		}
		
		$repas=0;
		if($_POST['indemniteRepas']<>""){$repas=$_POST['indemniteRepas'];}
		$primeEquipe=0;
		if($_POST['primeEquipe']<>""){$primeEquipe=$_POST['primeEquipe'];}
		
		if($Personne<>""){
			$TabPersonne = preg_split("/[;]+/", $Personne);
			for($i=0;$i<sizeof($TabPersonne)-1;$i++){
				$Id_ODMEC= IdODMEC($TabPersonne[$i]);
				$Id_PersonneContrat=0;
				if($Id_ODMEC>0){
					$req="SELECT Id, Id_ContratInitial FROM rh_personne_contrat WHERE Id=".$Id_ODMEC." ";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						$row=mysqli_fetch_array($result);
						if($row['Id_ContratInitial']>0){$Id_PersonneContrat=$row['Id_ContratInitial'];}
						else{$Id_PersonneContrat=$row['Id'];}
					}
				}
				
				if($Id_ODMEC>0){
					$req="INSERT INTO rh_personne_contrat (Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_Metier,DateDebut,DateFin,
						Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,Id_Client,Id_Responsable,MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
						FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,Motif,Titre) 
						SELECT ".$Id_PersonneContrat.",Id_Personne,Id_TypeContrat,Id_Metier,'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',
						Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,Id_Client,Id_Responsable,MontantIPD,".$repas.",MontantIGD,MontantRepasGD,
						FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,".$primeEquipe.",PanierGrandeNuit,PanierVSD,Motif,'".$_POST['commentaire']."'
						FROM rh_personne_contrat
						WHERE Id=".$Id_ODMEC."
					";
					$resultAjout=mysqli_query($bdd,$req);
					$IdCree = mysqli_insert_id($bdd);
					
					//Mettre une date de fin 
					if(TrsfDate_($_POST['dateDebut'])<>'000-00-00'){
						$req="UPDATE rh_personne_contrat 
							SET DateFin='".date('Y-m-d',strtotime(TrsfDate_($_POST['dateDebut'])." - 1 day"))."'
							WHERE 
							(DateFin<='0001-01-01' OR DateFin>'".date('Y-m-d')."')
							AND Id<>0
							AND TypeDocument='ODM'
							AND Id<>".$IdCree."
							AND Id_Personne=".$TabPersonne[$i]."
							AND (Id_ContratInitial=".$Id_PersonneContrat." OR Id=".$Id_PersonneContrat.") ";
						$result=mysqli_query($bdd,$req);
					}
					echo "<script>FermerEtRecharger('".$Menu."','".$_POST['Id_Personne2']."','".$_POST['Page']."')</script>";
				}
			}
		}
	}
}

$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";
?>

<form id="formulaire" class="test" action="Ajout_ODMCommun.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
	<input type="hidden" name="Mode" id="Mode" value="A" />
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage" style="background-color:#a988b2;">
					<?php 
						if($_SESSION["Langue"]=="FR"){echo "Nouvel ODM";}else{echo "New ODM";}
					?>
					</td>
					<td width="4"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="90%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="15%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes ayant un ODM en cours:";}else{echo "People with a current ODM :";} ?></td>
						</tr>
						<tr>
							<td colspan="4">
								<input type="checkbox" name="selectAllPersonne" id="selectAllPersonne" onclick="SelectionnerTout('Personne')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							</td>
						</tr>
						<tr>
							<td valign="top" colspan="4">
								<div id='Div_Personne' style='height:200px;width:300px;overflow:auto;'>
									<table>
								<?php
								$rq="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND DateDebut<='".date('Y-m-d')."'
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('ODM')
									AND Id_Personne IN (
									SELECT Id_Personne
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND DateDebut<='".date('Y-m-d')."'
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant'))
									AND Id_Personne>0
									AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
									ORDER BY Personne ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									echo "<tr><td><input class='checkPersonne' type='checkbox' checked value='".$rowpersonne['Id_Personne']."' id='Personne_".$rowpersonne['Id_Personne']."' name='Personne_".$rowpersonne['Id_Personne']."'>".stripslashes($rowpersonne['Personne'])."</td></tr>";
								}
								?>
									</table>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début : ";}else{echo "Start date : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input type="date" style="text-align:center;" id="dateDebut" name="dateDebut" size="10" value="">
							</td>
							<td width="10%" class="Libelle" id="LibelleDateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin : ";}else{echo "End date : ";} ?><?php echo $etoile;?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" id="dateFin" name="dateFin" size="10" value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleIndemniteRepas"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité repas : ";}else{echo "Meal allowance : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" id="indemniteRepas" name="indemniteRepas" size="10" value="6.30">
							</td>
							<td width="10%" class="Libelle" id="LibellePrimeEquipe"><?php if($_SESSION["Langue"]=="FR"){echo "Prime d'équipe (par jour) : ";}else{echo "Team bonus (per day) : ";} ?><?php echo $etoile;?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;"  id="primeEquipe" name="primeEquipe" size="10" value="14.20">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Titre";}else{echo "Title";} ?></td>
						</tr>
						<tr>
							<td colspan="4">
								<textarea name="commentaire" id="commentaire" cols="90" rows="3" noresize="noresize"></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="7" align="center" class="Libelle">
							<?php 
								if($_SESSION["Langue"]=="FR"){echo "Les autres informations seront reprises de l'ODM de la personne";}
								else{echo "Other information will be taken from the person's MDO";} 
							?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir enregistrer ?";}else{echo "Are you sure you want to save ?";} ?>')){EnregistrerODMCommun();}else{return false;}">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
}
?>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>