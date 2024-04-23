<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
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
	function VerifChamps(){
		var Elements_EPP2ans = document.getElementsByClassName("EPP2ans");
		var Elements_Evolution = document.getElementsByClassName("souhaitEvolutionON");
		var Elements_Mobilite = document.getElementsByClassName("souhaitMobiliteON");
		var Elements_Formation = document.getElementsByClassName("souhaitFormationON");
		checkedObjet=false;
		for(var k=0, l=Elements_EPP2ans.length; k<l; k++){
			if(Elements_EPP2ans[k].checked){
				checkedObjet=true;
			}
		}
		if(checkedObjet==false){alert("Veuillez renseigner le cadre de l'entretien");return false;}
		
		if(document.getElementById('RefusSalarie').checked == false){
			checkedObjet=false;
			for(var k=0, l=Elements_Evolution.length; k<l; k++){
				if(Elements_Evolution[k].checked){
					checkedObjet=true;
				}
			}
			if(checkedObjet==false){alert("Veuillez renseigner le souhait d'évolution profesionnelle");return false;}
			else{
				if(Elements_Evolution[0].checked){
					if(document.getElementById("souhaitEvolution").value==''){alert("Veuillez renseigner le souhait d'évolution profesionnelle");return false;}
				}
			}
			
			checkedObjet=false;
			for(var k=0, l=Elements_Mobilite.length; k<l; k++){
				if(Elements_Mobilite[k].checked){
					checkedObjet=true;
				}
			}
			if(checkedObjet==false){alert("Veuillez renseigner le souhait de mobilité");return false;}
			else{
				if(Elements_Mobilite[0].checked){
					mobiliteRemplie=0;
					for(var k=0;k<10; k++){
						if(document.getElementById("Mobilite_"+k).value!=0){mobiliteRemplie=1;}
					}
					if(document.getElementById("souhaitMobilite").value=='' && mobiliteRemplie==0){alert("Veuillez renseigner le souhait de mobilité");return false;}
				}
			}
			checkedObjet=false;
			for(var k=0, l=Elements_Formation.length; k<l; k++){
				if(Elements_Formation[k].checked){
					checkedObjet=true;
				}
			}
			if(checkedObjet==false){alert("Veuillez renseigner les souhaits de formations");return false;}
			else{
				if(Elements_Formation[0].checked){
					
					if(document.getElementById("souhaitFormation").value==''){alert("Veuillez renseigner les souhaits de formations");return false;}
				}
			}
			
			if(document.getElementById("ComEvaluateur").value==""){alert("Veuillez compléter le commentaire évaluateur");return false;}
		}
		var Confirm=false;
		Confirm=window.confirm('Attention, aucune modification ne sera possible. Etes-vous sur de vouloir valider ? ');
		if(Confirm==false){
			return false;
		}
	}
	function AfficherTR(nb){
		var elements = document.getElementsByClassName('idsouhaiteMobilite'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('idlesouhaiteMobilite'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTR2(nb){
		var elements = document.getElementsByClassName('idsouhaiteEvolution'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('idlesouhaiteEvolution'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTR3(nb){
		var elements = document.getElementsByClassName('SF'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('SSF'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	
	function AfficheCom(name,name2,valeur){
		if(valeur==0){
			document.getElementById(name).style.display='none';
			if(name=="idsouhaiteMobilite"){
				document.getElementById(name+"2").style.display='none';
			}
			if(name=="idsouhaiteEvolution"){
				document.getElementById(name+"2").style.display='none';
			}
		}
		else{
			document.getElementById(name).style.display='';
			if(name=="idsouhaiteMobilite"){
				document.getElementById(name+"2").style.display='';
			}
			if(name=="idsouhaiteEvolution"){
				document.getElementById(name+"2").style.display='';
			}
		}
	}
	function FermerEtRecharger()
	{
		window.opener.location="Liste_EPE.php";
		window.close();
	}
	</script>
</head>


<?php
require_once("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		
		$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,IF(DateReport>'0001-01-01' ,DateReport,DateButoir) AS DateButoir,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		$rowEPE=mysqli_fetch_array($result);

		$Id_Prestation=0;
		$Id_Pole=0;

		$req="SELECT Id_Prestation,Id_Pole 
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$rowEPE['Id']." 
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
		$resultch=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($resultch);
		$Id_PrestationPole="0_0";
		if($nb>0){
			$rowMouv=mysqli_fetch_array($resultch);
			$Id_Prestation=$rowMouv['Id_Prestation'];
			$Id_Pole=$rowMouv['Id_Pole'];
		}
		
		$req="SELECT Id_Prestation, Id_Pole FROM epe_personne_prestation WHERE Id_Personne=".$rowEPE['Id']." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
		$ResultlaPresta=mysqli_query($bdd,$req);
		$NblaPresta=mysqli_num_rows($ResultlaPresta);
		if($NblaPresta>0){
			$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
			$Id_Prestation=$RowlaPresta['Id_Prestation'];
			$Id_Pole=$RowlaPresta['Id_Pole'];
		}

		$Id_Plateforme=0;
		$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Id_Plateforme=$RowPresta['Id_Plateforme'];
		}

		$MetierManager="";
		$req="SELECT MetierPaie AS Metier
				FROM new_rh_etatcivil
				WHERE Id=".$_SESSION['Id_Personne'];
		$ResultManager=mysqli_query($bdd,$req);
		$NbManager=mysqli_num_rows($ResultManager);
		if($NbManager>0){
			$RowManager=mysqli_fetch_array($ResultManager);
			$MetierManager=$RowManager['Metier'];
		}
		
		$EPP2Ans=0;
		$EPPReprise=0;
		$RefusSalarie=0;
		if($_POST['EPP2ans']==1){$EPP2Ans=1;}
		elseif($_POST['EPP2ans']==0){$EPPReprise=1;}
		if(isset($_POST['RefusSalarie'])){$RefusSalarie=1;}
		
		$souhaitEvolutionON=0;
		$souhaitEvolution="";
		$souhaitMobiliteON=0;
		$souhaitMobilite="";
		$souhaitFormationON=0;
		$souhaitFormation="";
		if($_POST['souhaitEvolutionON']==1){$souhaitEvolutionON=1;$souhaitEvolution=addslashes($_POST['souhaitEvolution']);}
		if($_POST['souhaitMobiliteON']==1){$souhaitMobiliteON=1;$souhaitMobilite=addslashes($_POST['souhaitMobilite']);}
		if($_POST['souhaitFormationON']==1){$souhaitFormationON=1;$souhaitFormation=addslashes($_POST['souhaitFormation']);}

		//Création d'un EPP
		$req="INSERT INTO epe_personne (Type,Id_Prestation,Id_Pole,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,Id_Plateforme,
			EPP2Ans,EPPReprise,EPPRefuseSalarie,SouhaitEvolutionON,SouhaitEvolution,SouhaitMobiliteON,SouhaitMobilite,FormationEvolutionON,FormationEvolution,ComEvaluateurEPP) 
			VALUES 
				('EPP',".$Id_Prestation.",".$Id_Pole.",".$rowEPE['Id'].",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".addslashes($rowEPE['Metier'])."','".$rowEPE['DateAncienneteCDI']."','".date('Y-m-d')."','".$rowEPE['DateButoir']."',
				".$_SESSION['Id_Personne'].",'".addslashes($MetierManager)."',".$Id_Plateforme.",".$EPP2Ans.",".$EPPReprise.",".$RefusSalarie.",".$souhaitEvolutionON.",
				'".$souhaitEvolution."',".$souhaitMobiliteON.",'".$souhaitMobilite."',".$souhaitFormationON.",'".$souhaitFormation."','".addslashes($_POST['ComEvaluateur'])."')";
		
		$resultAjout=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		echo $req;
		if($IdCree>0){
			//Mobilités
			for($i=0;$i<10;$i++){
				if($_POST['Mobilite_'.$i]<>"0"){
					$req="INSERT INTO epe_personne_souhaitmobilite2 (Id_EPE,Id_SouhaitMobilite) 
						VALUES (".$IdCree.",".$_POST['Mobilite_'.$i].") ";
					$resultAjout=mysqli_query($bdd,$req);
				}
			}
			
			//souhait evolution
			for($i=0;$i<10;$i++){
				if($_POST['Mobilite_'.$i]<>"0"){
					$req="INSERT INTO epe_personne_souhaitevolution2 (Id_EPE,Id_SouhaitEvolution) 
						VALUES (".$IdCree.",".$_POST['Evolution_'.$i].") ";
					$resultAjout=mysqli_query($bdd,$req);
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$Id_Prestation=0;
$Id_Pole=0;

$req="SELECT Id_Prestation,Id_Pole 
	FROM new_competences_personne_prestation
	WHERE Id_Personne=".$rowEPE['Id']." 
	AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
$resultch=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultch);
$Id_PrestationPole="0_0";
if($nb>0){
	$rowMouv=mysqli_fetch_array($resultch);
	$Id_Prestation=$rowMouv['Id_Prestation'];
	$Id_Pole=$rowMouv['Id_Pole'];
}


$Presta="";
$Plateforme="";
$Id_Plateforme=0;
$req="SELECT LEFT(Libelle,7) AS Prestation,(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Presta=$RowPresta['Prestation'];
	$Plateforme=$RowPresta['Plateforme'];
	$Id_Plateforme=$RowPresta['Id_Plateforme'];
}

$Pole="";
$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
$ResultPole=mysqli_query($bdd,$req);
$NbPole=mysqli_num_rows($ResultPole);
if($NbPole>0){
	$RowPole=mysqli_fetch_array($ResultPole);
	$Pole=$RowPole['Libelle'];
}

if($Pole<>""){$Presta.=" - ".$Pole;}

$Manager="";
$MatriculeAAAManager="";
$MetierManager="";
$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, MatriculeAAA,MetierPaie AS Metier
		FROM new_rh_etatcivil
		WHERE Id=".$_GET['Id_Manager'];
$ResultManager=mysqli_query($bdd,$req);
$NbManager=mysqli_num_rows($ResultManager);
if($NbManager>0){
	$RowManager=mysqli_fetch_array($ResultManager);
	$Manager=$RowManager['Personne'];
	$MatriculeAAAManager=$RowManager['MatriculeAAA'];
	$MetierManager=$RowManager['Metier'];
}

$requete="SELECT Id,IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)." ORDER BY DateCreation DESC)>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC),
			'A faire')
			AS Etat,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC)>0,
			(SELECT YEAR(epe_personne.DateButoir)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC),
			'A faire')
			AS Annee
			FROM epe_personne_datebutoir
			WHERE Id_Personne=".$rowEPE['Id']."
			AND TypeEntretien='EPP'
			AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir))<=".($rowEPE['Annee']-1)." 
		ORDER BY IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir) DESC
		";
$result_1=mysqli_query($bdd,$requete);
$Nb_1=mysqli_num_rows($result_1);

?>

<form id="formulaire" class="test" action="Ajout_EPP.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<?php 
		if($Nb_1>0){
			$rowEPE_1=mysqli_fetch_array($result_1);
			if($rowEPE_1['Etat']=="Réalisé"){
				echo "<tr><td class='Libelle' align='right'>EPP ".$rowEPE_1['Annee']." :";
	?>
		<a class="Modif" href="javascript:EPP_PDF(<?php echo $rowEPE_1['Id']; ?>);">
			<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
		</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php
				echo "</td></tr>";
			}
		}
	?>
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#1a0078;">
				<tr>
					<td class="TitrePage" align="center" style="color:#ffffff;">
						ENTRETIEN PROFESSIONNEL PARCOURS - E.P.P<br>Elaboration des projets professionnels du salarié / périodicité réglementaire : tous les 2 ans
					</td>
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
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $rowEPE['MatriculeAAA']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'entretien";}else{echo "Interview date";} ?></td>
							<td width="30%"><?php echo date('d/m/Y'); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Name";} ?></td>
							<td width="30%"><?php echo $rowEPE['Nom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
							<td width="30%"><?php echo $Plateforme; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="30%"><?php echo $rowEPE['Prenom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Evaluateur";}else{echo "Evaluator";} ?></td>
							<td width="30%"><?php echo $Manager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction/métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo $rowEPE['Metier']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $MatriculeAAAManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
							<td width="30%"><?php echo AfficheDateJJ_MM_AAAA($rowEPE['DateAncienneteCDI']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction /métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo $MetierManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			1. EPP - cadre de l'entretien
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='radio' class="EPP2ans" name="EPP2ans" id="EPP2ans" value="1">Entretien périodique proposé tous les 2 ans</td>
						</tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='radio' class="EPP2ans" name="EPP2ans" id="EPP2ans" value="0">Entretien proposé au salarié reprenant son activité (maladie, maternité, …)</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='checkbox' class="RefusSalarie" name="RefusSalarie" id="RefusSalarie" value="1">Le salarié ne souhaite pas bénéficier de l'entretien professionnel proposé</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			2. EPP - Expression du collaborateur sur son parcours
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="4" align="center" style="color:#ffffff;" >Evolution souhaitée par le salarié dans son poste ou autre projet professionnel du salarié (les souhaits exprimés vont faire l'objet d'une étude à la DRH)</td>
						</tr>
						<tr>
							<td width="30%" class="Libelle2" rowspan="3">Souhait d'évolution professionnelle éventuel</td>
							<td width="70%" class="Libelle2"><input type='radio' class="souhaitEvolutionON" name="souhaitEvolutionON" id="souhaitEvolutionON" value="1" onclick="AfficheCom('idsouhaiteEvolution','souhaitEvolutionON','1')">Oui<input type='radio' class="souhaitEvolutionON" id="souhaitEvolutionON" name="souhaitEvolutionON" onclick="AfficheCom('idsouhaiteEvolution','souhaitEvolutionON','0')" value="0">Non</td>
						</tr>
						<tr>
							<td width="3%" class="Libelle2" id="idsouhaiteEvolution2" style="color:#ffffff;display:none;" >
								<table>
									<?php 
									$req="SELECT Id, Libelle FROM epe_typeevolution WHERE Suppr=0 ORDER BY Libelle";
									$resultM=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultM);
								
									for($i=0;$i<10;$i++){
									?>
										<tr class="idsouhaiteEvolution<?php echo $i;?>" <?php if($i>2){echo "style='display:none;'";} ?>>
											<td class="Libelle2" align="center" style="color:#ffffff;" >
											<?php if($i>1 && $i<>9){ ?>
											<input class="Bouton idlesouhaiteEvolution<?php echo $i;?>" type="button" name="newsouhaiteEvolution" id="newsouhaiteEvolution" onclick="AfficherTR2(<?php echo ($i+1)?>)" value="+" />
											<?php } ?>
											</td>
											<td class="Libelle2" align="center">
												<?php
												echo "<select style='width:90px;' id='Evolution_".$i."' name='Evolution_".$i."' >";
												echo "<option name='0' value='0' selected></option>";
												if ($nb > 0)
												{
													$resultM=mysqli_query($bdd,$req);
													while($rowM=mysqli_fetch_array($resultM))
													{
														$selected="";
														echo "<option value='".$rowM['Id']."' ".$selected.">".stripslashes($rowM['Libelle'])."</option>\n";
													}
												}
												echo "</select>";
												?>
											</td>
										</tr>
									<?php
									}
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="70%" class="Libelle2" id="idsouhaiteEvolution" style="display:none"><textarea name="souhaitEvolution" id="souhaitEvolution" cols="120" rows="3" noresize="noresize"></textarea></td>
						</tr>
						<tr>
							<td width="30%" class="Libelle2" rowspan="3" valign="top">Souhait de mobilité géographique nationale ou internationale éventuel (précisez la région ou le pays souhaité)</td>
							<td width="70%" class="Libelle2"><input type='radio' class="souhaitMobiliteON" name="souhaitMobiliteON" id="souhaitMobiliteON" value="1" onclick="AfficheCom('idsouhaiteMobilite','souhaitMobiliteON','1')">Oui<input type='radio' class="souhaitMobiliteON" id="souhaitMobiliteON" name="souhaitMobiliteON" onclick="AfficheCom('idsouhaiteMobilite','souhaitMobiliteON','0')" value="0">Non</td>
						</tr>
						<tr>
							<td width="3%" class="Libelle2" id="idsouhaiteMobilite2" style="color:#ffffff;display:none;" >
								<table>
									<?php 
									$req="SELECT Id, Libelle FROM epe_mobilite WHERE Suppr=0 ORDER BY Libelle";
									$resultM=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultM);
								
									for($i=0;$i<10;$i++){
									?>
										<tr class="idsouhaiteMobilite<?php echo $i;?>" <?php if($i>2){echo "style='display:none;'";} ?>>
											<td class="Libelle2" align="center" style="color:#ffffff;" >
											<?php if($i>1 && $i<>9){ ?>
											<input class="Bouton idlesouhaiteMobilite<?php echo $i;?>" type="button" name="newsouhaiteMobilite" id="newsouhaiteMobilite" onclick="AfficherTR(<?php echo ($i+1)?>)" value="+" />
											<?php } ?>
											</td>
											<td class="Libelle2" align="center">
												<?php
												echo "<select style='width:90px;' id='Mobilite_".$i."' name='Mobilite_".$i."' >";
												echo "<option name='0' value='0' selected></option>";
												if ($nb > 0)
												{
													$resultM=mysqli_query($bdd,$req);
													while($rowM=mysqli_fetch_array($resultM))
													{
														$selected="";
														echo "<option value='".$rowM['Id']."' ".$selected.">".stripslashes($rowM['Libelle'])."</option>\n";
													}
												}
												echo "</select>";
												?>
											</td>
										</tr>
									<?php
									}
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="70%" class="Libelle2" id="idsouhaiteMobilite" style="display:none"><textarea name="souhaitMobilite" id="souhaitMobilite" cols="120" rows="3" noresize="noresize"></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="4" align="center" style="color:#ffffff;" >
							Actions de formation évoquées<br>
							récapitulatif des actions et dispositifs envisageables sous réserve des priorités du plan de formation AAA, de l'éligibilité aux dispositifs de financement et des possibilités de réalisation.<br>
							NB : ce support ne contractualise en aucun cas ni un engagement de réalisation, ni une demande d'utilisation du CPF, mais constate formellement la tenue de l'entretien, ainsi que les souhaits qui auront pu y être exprimés
							</td>
						</tr>
						<tr>
							<td width="30%" class="Libelle2" rowspan="2">Souhait de formation<br>(Formations évoquées pour accompagner l'évolution professionnelle)</td>
							<td width="70%" class="Libelle2"><input type='radio' class="souhaitFormationON" name="souhaitFormationON" id="souhaitFormationON" value="1" onclick="AfficheCom('idsouhaiteFormation','souhaitFormationON','1')">Oui<input type='radio' class="souhaitFormationON" id="souhaitFormationON" name="souhaitFormationON" onclick="AfficheCom('idsouhaiteFormation','souhaitFormationON','0')" value="0">Non</td>
						</tr>
						<tr>
							<td width="70%" class="Libelle2" id="idsouhaiteFormation" style="display:none"><textarea name="souhaitFormation" id="souhaitFormation" cols="120" rows="3" noresize="noresize"></textarea></td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			3 .EPP - Commentaires évaluateur
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="Libelle2">Commentaire de l'évaluateur sur le projet défini</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="ComEvaluateur" id="ComEvaluateur" cols="120" rows="3" noresize="noresize"></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="6" align="center">
			<?php if($_GET['Id_Manager']==$_SESSION['Id_Personne'] || $_SESSION['FiltreEPE_AffichageBackup']<>"" || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){ ?>
			<input class="Bouton" name="btnEnregistrer" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
			<?php } ?>
		</td>
	</tr>
	</tr>
</table>
</form>
</body>
</html>