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
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
	function EPPBilan_PDF(Id)
	{window.open("EPPBilan_PDF.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function VerifChamps(){
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
	if(isset($_POST['btnSignerS'])){
		$req="UPDATE epe_personne SET DateSalarie='".date('Y-m-d')."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif(isset($_POST['btnSignerE'])){
		$req="UPDATE epe_personne SET DateEvaluateur='".date('Y-m-d')."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
}

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);


$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,Id_Plateforme,
		(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
		(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
		EPPBilan,EPPBilanRefuseSalarie,NbEntretienPro,ComNbEntretiensPro,ActionFormationOEPPBilan,ActionFormationNonOEPPBilan,CertifParFormation,EvolutionSalariale,EvolutionPro,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPP Bilan'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);

$Id_Prestation=0;
$Id_Pole=0;

$Plateforme="";
$Id_Plateforme=$rowEPERempli['Id_Plateforme'];
$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Plateforme=$RowPresta['Libelle'];
}

$Manager=stripslashes($rowEPERempli['Manager']);
$MatriculeAAAManager=$rowEPERempli['MatriculeAAAManager'];
$MetierManager=stripslashes($rowEPERempli['MetierManager']);

$requete="SELECT Id,IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC)>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
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
			AND TypeEntretien='EPP Bilan'
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir))<=".($rowEPE['Annee']-1)." 
		ORDER BY IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) DESC
		";
$result_1=mysqli_query($bdd,$requete);
$Nb_1=mysqli_num_rows($result_1);
?>

<form id="formulaire" class="test" action="Modif_EPPBilan.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Id_EPE" id="Id_EPE" value="<?php echo $rowEPERempli['Id']; ?>" />
	<?php 
		if($Nb_1>0){
			$rowEPE_1=mysqli_fetch_array($result_1);
			if($rowEPE_1['Etat']=="Réalisé"){
				echo "<tr><td class='Libelle' align='right'>EPP Bilan ".$rowEPE_1['Annee']." :";
	?>
		<a class="Modif" href="javascript:EPPBilan_PDF(<?php echo $rowEPE_1['Id']; ?>);">
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
						Etat des lieux récapitulatif du parcours professionnel<br>Bilan à 6 ans
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
							<td width="30%"><?php echo AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Name";} ?></td>
							<td width="30%"><?php echo $rowEPERempli['Nom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
							<td width="30%"><?php echo $Plateforme; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="30%"><?php echo $rowEPERempli['Prenom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Evaluateur";}else{echo "Evaluator";} ?></td>
							<td width="30%"><?php echo $Manager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction/métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo stripslashes($rowEPERempli['Metier']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $MatriculeAAAManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
							<td width="30%"><?php echo AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction /métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo stripslashes($MetierManager); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			1.Bilan - Cadre de l'entretien
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='checkbox' class="RefusSalarie" name="RefusSalarie" id="RefusSalarie" value="1" disabled <?php if($rowEPERempli['EPPBilanRefuseSalarie']==1){echo "checked";} ?>>Le salarié ne souhaite pas bénéficier de l'entretien professionnel proposé</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			2 .Bilan des EPP
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="25%" class="Libelle2">NOMBRE D'ENTRETIENS PROFESSIONNELS PERIODIQUES REALISES <span style="font-size:12px;">(date) au cours des 6 dernières années y compris celui réalisé en même temps que le bilan</span></td>
							<td width="3%" class="Libelle2" align="center" valign="center"><input size="6" disabled onKeyUp="nombre(this)" name="leNbEntretien" id="leNbEntretien" value="<?php echo $rowEPERempli['NbEntretienPro']; ?>" /></td>
							<td width="60%" class="Libelle2" align="center" valign="center"><textarea disabled name="NbEntretien" id="NbEntretien" cols="100" rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComNbEntretiensPro']); ?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			3 .Bilan des Formations
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="4" align="center" style="color:#ffffff;" >ACTIONS DE FORMATION REALISEES</td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">ACTIONS DE FORMATION OBLIGATOIRES REALISEES<span style="font-size:12px;"><br>(Date et intitulé)<br>C’est-à-dire qui conditionne l’exercice d’une activité ou d’une fonction en application d’une convention internationale ou de dispositions légales et réglementaires</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="actionFormationO" id="actionFormationO" disabled cols="120" rows="6" noresize="noresize"><?php echo stripslashes($rowEPERempli['ActionFormationOEPPBilan']); ?></textarea></td>
						</tr>
						<tr>
							<td height="15px"></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">ACTIONS DE FORMATION NON OBLIGATOIRES REALISEES<span style="font-size:12px;"><br>(Date et intitulé)<br>C’est-à-dire autre qu’une action de formation qui conditionne l’exercice d’une activité ou d’une fonction en application d’une convention internationale ou de dispositions légales et réglementaires</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="actionFormationNO" id="actionFormationNO" disabled cols="120" rows="6" noresize="noresize"><?php echo stripslashes($rowEPERempli['ActionFormationNonOEPPBilan']); ?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="4" align="center" style="color:#ffffff;" >ELEMENTS DE CERTIFICATION OBTENUS</td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">PAR LA FORMATION  ou la VAE<span style="font-size:12px;"><br>(Date et intitulé)</td>
							<td width="30%" class="Libelle2" align="center"><textarea name="VAE" id="VAE" cols="120" rows="3" disabled noresize="noresize"><?php echo stripslashes($rowEPERempli['CertifParFormation']); ?></textarea></td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			4 .Bilan - Progression Salariale ou Professionnelle
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="Libelle2">Evolution salariale (année)<span style="font-size:12px;"><br>(Date et intitulé)<br>Augmentation individuelle ou Générale</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="salaire" id="salaire" cols="120" disabled rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['EvolutionSalariale']); ?></textarea></td>
						</tr>
						<tr>
							<td height="15px"></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">Evolution professionnelle (année)<span style="font-size:12px;"><br>Changement de métier, progression en terme de responsabilités, changement de classification, etc.</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="evolutionPro" id="evolutionPro" disabled cols="120" rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['EvolutionPro']); ?></textarea></td>
						</tr>
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
			<?php 
			if($rowEPERempli['Etat']=="Brouillon"){ ?>
			<input class="Bouton" name="btnEnregistrer" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
			<?php }
				elseif($rowEPERempli['ModeBrouillon']==0 && ($rowEPERempli['Id_Personne']==$_SESSION['Id_Personne'] || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))) && $rowEPERempli['Etat']=="Signature salarié"){?>
			<input class="Bouton" name="btnSignerS" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Signer";}else{echo "Sign";} ?>"/>
			<?php	
				}
				elseif($rowEPERempli['ModeBrouillon']==0 && ($rowEPERempli['Id_Evaluateur']==$_SESSION['Id_Personne'] || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH.",".$IdPosteResponsableRH))) && $rowEPERempli['Etat']=="Signature manager"){?>
				<input class="Bouton" name="btnSignerE" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Signer";}else{echo "Sign";} ?>"/>
			<?php	
				}
			?>
		</td>
	</tr>
	</tr>
</table>
</form>
</body>
</html>