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
	function EPP_PDF(Id)
	{window.open("EPP_PDF.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function VerifChamps(){
		/*
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
		*/
		
		if(document.getElementById('RefusSalarie').checked == false){
			if(document.getElementById("ComEvaluateur").value==""){alert("Veuillez compléter le commentaire évaluateur");return false;}
		}
	}
	function AfficherTR(nb){
		var elements = document.getElementsByClassName('OA'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('OOA'+(nb-1));
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='none';
		}
	}
	function AfficherTR2(nb){
		var elements = document.getElementsByClassName('BF'+nb);
		for (i=0; i<elements.length; i++){
		  elements[i].style.display='';
		}
		
		var elements = document.getElementsByClassName('BBF'+(nb-1));
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
		}
		else{
			document.getElementById(name).style.display='';
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
	if(isset($_POST['btnSignerS'])){
		$req="UPDATE epe_personne SET DateSalarie='".date('Y-m-d')."'
		WHERE Id=".$_POST['Id_EPE']." ";
		$resultAjout=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif(isset($_POST['btnSignerE'])){
		$req="UPDATE epe_personne SET DateEvaluateur='".date('Y-m-d')."', ComEvaluateur='".addslashes($_POST['ComEvaluateur'])."'
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
		EPP2Ans,EPPReprise,EPPRefuseSalarie,SouhaitEvolutionON,SouhaitEvolution,SouhaitMobiliteON,SouhaitMobilite,FormationEvolutionON,FormationEvolution,ComEvaluateurEPP,
		ComSalarie,ComEvaluateur,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPP'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);


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
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)." ORDER BY DateCreation DESC)>0,
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
			AND TypeEntretien='EPP'
			AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir))<=".($rowEPE['Annee']-1)." 
		ORDER BY IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) DESC
		";
$result_1=mysqli_query($bdd,$requete);
$Nb_1=mysqli_num_rows($result_1);
?>

<form id="formulaire" class="test" action="Modif_EPP.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Id_EPE" id="Id_EPE" value="<?php echo $rowEPERempli['Id']; ?>" />
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
							<td width="5%" class="Libelle2"><input type='radio' class="EPP2ans" name="EPP2ans" id="EPP2ans" disabled value="1" <?php if($rowEPERempli['EPP2Ans']==1){echo "checked";} ?>>Entretien périodique proposé tous les 2 ans</td>
						</tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='radio' class="EPP2ans" name="EPP2ans" id="EPP2ans" disabled value="0" <?php if($rowEPERempli['EPP2Ans']==0){echo "checked";} ?>>Entretien proposé au salarié reprenant son activité (maladie, maternité, …)</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='checkbox' disabled class="RefusSalarie" name="RefusSalarie" disabled id="RefusSalarie" value="1" <?php if($rowEPERempli['EPPRefuseSalarie']==1){echo "checked";} ?>>Le salarié ne souhaite pas bénéficier de l'entretien professionnel proposé</td>
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
							<td width="70%" class="Libelle2"><input type='radio' class="souhaitEvolutionON" disabled name="souhaitEvolutionON" id="souhaitEvolutionON" value="1" onclick="AfficheCom('idsouhaiteEvolution','souhaitEvolutionON','1')" <?php if($rowEPERempli['SouhaitEvolutionON']==1 && $rowEPERempli['EPPRefuseSalarie']==0){echo "checked";} ?>>Oui<input type='radio' disabled class="souhaitEvolutionON" id="souhaitEvolutionON" name="souhaitEvolutionON" onclick="AfficheCom('idsouhaiteEvolution','souhaitEvolutionON','0')" value="0" <?php if($rowEPERempli['SouhaitEvolutionON']==0 && $rowEPERempli['EPPRefuseSalarie']==0){echo "checked";} ?>>Non</td>
						</tr>
						<tr>
							<td width="3%" class="Libelle2" id="idsouhaiteMobilite3">
								<table>
									<?php 
									$req="SELECT DISTINCT Id_SouhaitEvolution, 
									(SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution) AS Evolution 
									FROM epe_personne_souhaitevolution2 
									WHERE Id_EPE=".$rowEPERempli['Id']." 
									ORDER BY (SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution)";
									$resultE=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultE);
									
									if($nb>0){
									while($rowE=mysqli_fetch_array($resultE)){
									?>
										<tr class="idsouhaiteEvolution<?php echo $i;?>">
											<td class="Libelle2">
												<?php
												echo $rowE['Evolution'];
												?>
											</td>
										</tr>
									<?php
									}
									}
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="70%" class="Libelle2" id="idsouhaiteEvolution" <?php if($rowEPERempli['SouhaitEvolutionON']==0){echo "style='display:none'";} ?> ><textarea name="souhaitEvolution" disabled id="souhaitEvolution" cols="120" rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['SouhaitEvolution']); ?></textarea></td>
						</tr>
						<tr>
							<td width="30%" class="Libelle2" rowspan="3" valign="top">Souhait de mobilité géographique nationale ou internationale éventuel (précisez la région ou le pays souhaité)</td>
							<td width="70%" class="Libelle2"><input type='radio' class="souhaitMobiliteON" name="souhaitMobiliteON" id="souhaitMobiliteON" value="1" disabled onclick="AfficheCom('idsouhaiteMobilite','souhaitMobiliteON','1')" <?php if($rowEPERempli['SouhaitMobiliteON']==1 && $rowEPERempli['EPPRefuseSalarie']==0){echo "checked";} ?>>Oui<input type='radio' disabled class="souhaitMobiliteON" id="souhaitMobiliteON" name="souhaitMobiliteON" onclick="AfficheCom('idsouhaiteMobilite','souhaitMobiliteON','0')" value="0"  <?php if($rowEPERempli['SouhaitMobiliteON']==0 && $rowEPERempli['EPPRefuseSalarie']==0){echo "checked";} ?>>Non</td>
						</tr>
						<tr>
							<td width="3%" class="Libelle2" id="idsouhaiteMobilite2">
								<table>
									<?php 
									$req="SELECT DISTINCT Id_SouhaitMobilite, 
									(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Mobilite 
									FROM epe_personne_souhaitmobilite2 
									WHERE Id_EPE=".$rowEPERempli['Id']." 
									ORDER BY (SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite)";
									$resultM=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultM);
									
									if($nb>0){
									while($rowM=mysqli_fetch_array($resultM)){
									?>
										<tr class="idsouhaiteMobilite<?php echo $i;?>">
											<td class="Libelle2">
												<?php
												echo $rowM['Mobilite'];
												?>
											</td>
										</tr>
									<?php
									}
									}
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td width="70%" class="Libelle2" id="idsouhaiteMobilite" <?php if($rowEPERempli['SouhaitMobiliteON']==0){echo "style='display:none'";} ?>><textarea name="souhaitMobilite" disabled id="souhaitMobilite" cols="120" rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['SouhaitMobilite']); ?></textarea></td>
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
							<td width="70%" class="Libelle2"><input type='radio' class="souhaitFormationON" name="souhaitFormationON" id="souhaitFormationON" value="1" disabled onclick="AfficheCom('idsouhaiteFormation','souhaitFormationON','1')" <?php if($rowEPERempli['FormationEvolutionON']==1 && $rowEPERempli['EPPRefuseSalarie']==0){echo "checked";} ?>>Oui<input type='radio' disabled class="souhaitFormationON" id="souhaitFormationON" name="souhaitFormationON" onclick="AfficheCom('idsouhaiteFormation','souhaitFormationON','0')" value="0"  <?php if($rowEPERempli['FormationEvolutionON']==0 && $rowEPERempli['EPPRefuseSalarie']==0){echo "checked";} ?>>Non</td>
						</tr>
						<tr>
							<td width="70%" class="Libelle2" id="idsouhaiteFormation"  <?php if($rowEPERempli['FormationEvolutionON']==0){echo "style='display:none'";} ?>><textarea name="souhaitFormation" disabled id="souhaitFormation" cols="120" rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['FormationEvolution']); ?></textarea></td>
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
							<td width="30%" class="Libelle2" align="center"><textarea <?php if($rowEPERempli['Id_Evaluateur']<>$_SESSION['Id_Personne'] || $rowEPERempli['Etat']<>"Signature manager"){echo "disabled";} ?> name="ComEvaluateur" id="ComEvaluateur" cols="120" rows="3" noresize="noresize"><?php echo stripslashes($rowEPERempli['ComEvaluateurEPP']);?></textarea></td>
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