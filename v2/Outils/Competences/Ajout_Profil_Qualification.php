<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Profil personne - Qualification</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
			window.close();
		}
		List = new Array();
		function Remplir(valeur,evalu,Id_Categorie_Maitre){
		  var sel="";
		  sel ="<select name='Qualification' style='width:500px;'>";
		  // Parcourir le tableau
		  for (var i=0;i<List.length;i++)
		   {
			 // tester si la ligne du tableau (Sous-catégorie) correspond à la valeur de la catégorie
			 if (List[i][1]==valeur)
			 {
			   // Ajouter une rubrique sous-catégorie au variable SEL
			   sel= sel + "<option value="+List[i][0];
			   if (List[i][0]==List[i][3]){sel = sel + " selected";}
			   sel= sel + ">"+List[i][2]+"</option>";
			 }
		   }
		   sel =sel + "</select>";
		   // Modifier le DIV scat par la nouvelle List à partir du variable SEL
		   document.getElementById('scat').innerHTML=sel;
		   
		   //Languages
		   //---------
		    var Liste_Eval = new Array();
			sel="";
			sel ="<select size='1' name='Evaluation'>";
		
			document.getElementById('Cours').innerHTML="";
			document.getElementById('Resultat_QCM').innerHTML="Résultat QCM :";
			document.getElementById('Date_QCM').innerHTML="Date QCM :";
			document.getElementById('Date_Fin1').innerHTML="Date Fin :";
			document.getElementById('Date_Fin1').style.display="";
			document.getElementById('Date_Fin2').style.display="";
			document.getElementById('Surveillance').style.display="";
			document.getElementById('Dates_DebutFinSansLimite').style.display="";
			document.getElementById('Date_SansLimite1').style.display="";
			document.getElementById('Date_SansLimite2').style.display="";
			if(valeur == 6)	//équivaut à Language
			{
				if(document.getElementById('UniquementB').value==0){Liste_Eval=["B"];}
				else{Liste_Eval=["B","Low","Medium","High"];}
				legende="<font size=1 color=black>";
				legende+="</font>";
				document.getElementById('Legende').innerHTML=legende;
			}
			else if(Id_Categorie_Maitre == 1)	//équivaut à Job Validation
			{
				if(document.getElementById('UniquementB').value==0){Liste_Eval=["B"];}
				else{Liste_Eval=["B","L","V"];}
				legende="<font size=1 color=black>";
				legende+="B:Besoin";
				legende+="<br>L:Toute catégorie de personnel en cours de validation théorique métier";
				legende+="<br>V:Toute catégorie de personnel ayant validé la théorie métier";
				legende+="</font>";
				document.getElementById('Legende').innerHTML=legende;
				document.getElementById('Cours').innerHTML="AAA Cours génériques<br>____________________________________<br>Durée validation = Sans limite";
				document.getElementById('Surveillance').style.display="none";
				document.getElementById('Date_Fin1').style.display="none";
				document.getElementById('Date_Fin2').style.display="none";
				document.getElementById('Date_SansLimite1').style.display="none";
				document.getElementById('Date_SansLimite2').style.display="none";
			}
			else if(Id_Categorie_Maitre == 2)	//équivaut à Special Processes
			{
				if(document.getElementById('UniquementB').value==0){Liste_Eval=["B"];}
				else{
					if(valeur==147) //Equivaut à Airbus Helicopter QBP
					{
						Liste_Eval=["B","L","Q","Q1","Q2","Q3","S","T"];
					}
					else{
						Liste_Eval=["B","L","Q","S","T"];
					}
				}
				legende="<font size=1 color=black>";
				legende+="B:Besoin";
				legende+="<br>L:Toute catégorie de personnel en cours de qualification procédé spécial";
				legende+="<br>Q:Opérateur qualifié au procédé spécial";
				legende+="<br>S:Contrôleur/Inspecteur qualifié au procédé spécial";
				legende+="<br>T:Autre catégorie de personnel ayant suivi la théorie du procédé spécial mais ne l'exerçant pas";
				legende+="</font>";
				document.getElementById('Date_SansLimite1').style.display="none";
				document.getElementById('Date_SansLimite2').style.display="none";
				document.getElementById('Cours').innerHTML="AIPI/AIPS/AITM : Formation en direct sur documents AIRBUS<br>Autres Procédés avec exigences spécifiques client : Cours génériques AAA + Annexes client<br>Autres procédés sans exigence spécifique client : Cours génériques<br>____________________________________<br>Durée de validité de la qualification procédé spécial = 4 ans";
				document.getElementById('Legende').innerHTML=legende;
			}
			else if(Id_Categorie_Maitre == 3)	//équivaut à Non special Processes
			{
				if(document.getElementById('UniquementB').value==0){Liste_Eval=["B"];}
				else{Liste_Eval=["B","L","X"];}
				legende="<font size=1 color=black>";
				legende+="B:Besoin";
				legende+="<br>L:Toute catégorie de personnel en cours de qualification au procédé non spécial/validation à la compétence spécifique";
				legende+="<br>X:Toute catégorie de personnel qualifiée au procédé non spécial/validée à la compétence spécifique";
				legende+="</font>";
				document.getElementById('Legende').innerHTML=legende;
				document.getElementById('Resultat_QCM').innerHTML="Résultat QCM (si applicable) :";
				document.getElementById('Date_QCM').innerHTML="Date QCM (si applicable) :";
				document.getElementById('Surveillance').style.display="none";
				document.getElementById('Date_Fin1').innerHTML="Date Fin (si applicable) :";
				document.getElementById('Date_SansLimite1').style.display="none";
				document.getElementById('Date_SansLimite2').style.display="none";
			}
			
			for (i=0;i<Liste_Eval.length;i++)
			{
				sel= sel + "<option value="+Liste_Eval[i];
				if (Liste_Eval[i]==evalu){sel = sel + " selected";}
				sel= sel + ">"+Liste_Eval[i]+"</option>";
			}
			
			sel =sel + "</select>";
			// Modifier le DIV Eval par la nouvelle Liste_Eval à partir du variable SEL
			document.getElementById('Eval').innerHTML=sel;
		}
		
		function VerifChamps()
		{
			//if(formulaire.Date_Debut.value==""){alert('La date de début doit être au format aaaa-mm-jj.');return false;}
			//if(formulaire.Date_Fin.value==""){alert('La date de fin doit être au format aaaa-mm-jj.');return false;}
			return true;
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>

<?php
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteReferentQualiteSysteme))
|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
)
{
	$Droits="Ecriture";
}
$IdPersonneConnectee=0;
if(isset($_SESSION['Id_Personne'])){$IdPersonneConnectee=$_SESSION['Id_Personne'];}

if($_POST)
{	
	if(isset($_POST['btn_Save'])){
		$Plateforme_Identique=false;
		//Plateforme
		$PLATEFORME="";
		$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id FROM new_competences_plateforme, new_competences_personne_plateforme";
		$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$_POST['Id_Personne']." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
		$requete_plateforme.=" ORDER BY new_competences_plateforme.Libelle ASC";
		$result_plateforme=mysqli_query($bdd,$requete_plateforme);
		$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
		if(isset($_SESSION['Id_Plateformes']))
		{
			if($nbenreg_plateforme>0)
			{
				while($row_plateforme=mysqli_fetch_array($result_plateforme))
				{
					foreach($_SESSION['Id_Plateformes'] as &$value){if($row_plateforme['Id']==$value){$Plateforme_Identique=true;}}
				}
			}
		}
		
		$DroitsModifPrestation=false;
		if($IdPersonneConnectee > 0)
		{
			$IdPersonneConnectee=$_SESSION['Id_Personne'];
			$resultHierarchie=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee." ORDER BY Id_Poste DESC");
			$nbHierarchie=mysqli_num_rows($resultHierarchie);
			if($nbHierarchie>0){$DroitsModifPrestation=true;}
		}
		
		if($_POST['Qualification']!="")
		{
			if($_POST['Mode']=="Ajout")
			{
				if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
					$Requete="INSERT INTO new_competences_relation (";
					$Requete.="Id_Personne,";
					$Requete.="Type,";
					$Requete.="Id_Personne_MAJ_Manuelle,";
					$Requete.="Date_MAJ_Manuelle,";
					$Requete.="ModifManuelle,";
					$Requete.="Id_Qualification_Parrainage,";
					if(isset($_POST['Date_Debut'])){$Requete.="Date_Debut,";}
					if(isset($_POST['Date_Fin'])){$Requete.="Date_Fin,";}
					if(isset($_POST['visible'])){$Requete.="visible,";}
					$Requete.="Resultat_QCM,";
					$Requete.="Evaluation,";
					$Requete.="Date_QCM,";
					$Requete.="Commentaire,";
					if(isset($_POST['Date_Surveillance'])){$Requete.="Date_Surveillance,";}
					if($_POST['Id_Categorie_Maitre']==1){$Requete.="Sans_Fin,";}	//Cas job validation
					if(isset($_POST['QCM_Surveillance'])){$Requete.="QCM_Surveillance";}
					$Requete.=") VALUES (";
					$Requete.=$_POST['Id_Personne'].",";
					$Requete.="'Qualification',";
					$Requete.=$IdPersonneConnectee.",";
					$Requete.="'".date('Y-m-d')."',";
					$Requete.="1,";
					$Requete.=$_POST['Qualification'].",";
					if(isset($_POST['Date_Debut'])){$Requete.="'".TrsfDate_($_POST['Date_Debut'])."',";}
					if(isset($_POST['Date_Fin'])){$Requete.="'".TrsfDate_($_POST['Date_Fin'])."',";}
					if(isset($_POST['visible'])){$Requete.="".$_POST['visible'].",";}
					$Requete.="'".$_POST['Resultat_QCM']."',";
					$Requete.="'".$_POST['Evaluation']."',";
					$Requete.="'".TrsfDate_($_POST['Date_QCM'])."',";
					$Requete.="'".addslashes($_POST['commentaire'])."',";
					if(isset($_POST['Date_Surveillance'])){$Requete.="'".TrsfDate_($_POST['Date_Surveillance'])."',";}
					if($_POST['Id_Categorie_Maitre']==1){$Requete.="'Oui',";}	//Cas job validation
					if(isset($_POST['QCM_Surveillance'])){$Requete.="'".$_POST['QCM_Surveillance']."')";}
				}
			}
			elseif($_POST['Mode']=="Modif")
			{
				$Requete="UPDATE new_competences_relation SET ";
				$Requete.="Id_Personne=".$_POST['Id_Personne'].",";
				$Requete.="Id_Personne_MAJ_Manuelle=".$IdPersonneConnectee.",";
				$Requete.="Date_MAJ_Manuelle='".date('Y-m-d')."',";
				$Requete.="ModifManuelle=1,";
				$Requete.="Type='Qualification',";
				$Requete.="Id_Qualification_Parrainage=".$_POST['Qualification'].",";
				if(isset($_POST['Date_Debut'])){$Requete.="Date_Debut='".TrsfDate_($_POST['Date_Debut'])."',";}
				if(isset($_POST['Date_Fin'])){$Requete.="Date_Fin='".TrsfDate_($_POST['Date_Fin'])."',";}
				if(isset($_POST['visible'])){$Requete.="Visible=".$_POST['visible'].",";}
				$Requete.="Resultat_QCM='".$_POST['Resultat_QCM']."',";
				$Requete.="Evaluation='".$_POST['Evaluation']."',";
				$Requete.="Date_QCM='".TrsfDate_($_POST['Date_QCM'])."',";
				$Requete.="Commentaire='".addslashes($_POST['commentaire'])."',";
				if(isset($_POST['Date_Surveillance'])){$Requete.="Date_Surveillance='".TrsfDate_($_POST['Date_Surveillance'])."',";}
				if($_POST['Id_Categorie_Maitre']==1){$Requete.="Sans_Fin='Oui',";}	//Cas job validation
				if(isset($_POST['QCM_Surveillance'])){$Requete.="QCM_Surveillance='".$_POST['QCM_Surveillance']."'";}
				$Requete.="WHERE Id=".$_POST['Id'];
			}
			$result=mysqli_query($bdd,$Requete);
		}
		
		//Ajouter fiche de qualif si document
		if(!empty($_FILES['uploaded_fileFicheQualif']))
		{
			if($_FILES['uploaded_fileFicheQualif']['name'] <> ""){
				$reqAttest="SELECT FicheQualification FROM new_competences_relation WHERE FicheQualification<>'' AND Id=".$_POST['Id'];
				$resultAttest=mysqli_query($bdd,$reqAttest);
				$nbAttest=mysqli_num_rows($resultAttest);
				if($nbAttest>0){
					$rowAttest=mysqli_fetch_array($resultAttest);
					if(file_exists ("FichesQualifications/".$rowAttest['FicheQualification'])){
						//Supprimer le document
						unlink("FichesQualifications/".$rowAttest['FicheQualification']);	
					}
					$reqUpdateAttestation="UPDATE new_competences_relation SET FicheQualification='' WHERE Id=".$_POST['Id'];
					$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
				}
				
				$nomfichier = transferer_fichier($_FILES['uploaded_fileFicheQualif']['name'], $_FILES['uploaded_fileFicheQualif']['tmp_name'], "FichesQualifications/");
				$reqUpdateAttestation="UPDATE new_competences_relation SET FicheQualification='".$nomfichier."' WHERE Id=".$_POST['Id'];
				$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
			}
		}
	}
	
	if(isset($_POST['SupprimerFicheQualif'])){
		//Vérifier si document existe déjà 
		$reqAttest="SELECT FicheQualification FROM new_competences_relation WHERE FicheQualification<>'' AND Id=".$_POST['Id'];
		$resultAttest=mysqli_query($bdd,$reqAttest);
		$nbAttest=mysqli_num_rows($resultAttest);
		if($nbAttest>0){
			$rowAttest=mysqli_fetch_array($resultAttest);
			if(file_exists ("FichesQualifications/".$rowAttest['FicheQualification'])){
				//Supprimer le document
				unlink("FichesQualifications/".$rowAttest['FicheQualification']);	
			}
			$reqUpdateAttestation="UPDATE new_competences_relation SET FicheQualification='' WHERE Id=".$_POST['Id'];
			$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
		}
	}
	
	if(isset($_POST['SauvegarderAttestation']) || isset($_POST['SupprimerAttestation'])){
		//Vérifier si document existe déjà 
		$reqAttest="SELECT AttestationFormation FROM new_competences_relation WHERE AttestationFormation<>'' AND Id=".$_POST['Id'];
		$resultAttest=mysqli_query($bdd,$reqAttest);
		$nbAttest=mysqli_num_rows($resultAttest);
		if($nbAttest>0){
			$rowAttest=mysqli_fetch_array($resultAttest);
			if(file_exists ("AttestationsFormations/".$rowAttest['AttestationFormation'])){
				//Supprimer le document
				unlink("AttestationsFormations/".$rowAttest['AttestationFormation']);	
			}
			$reqUpdateAttestation="UPDATE new_competences_relation SET AttestationFormation='' WHERE Id=".$_POST['Id'];
			$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
		}
		if(isset($_POST['SauvegarderAttestation']) && !empty($_FILES['uploaded_fileAttestation']))
		{
			if($_FILES['uploaded_fileAttestation']['name'] <> ""){
				$nomfichier = transferer_fichier($_FILES['uploaded_fileAttestation']['name'], $_FILES['uploaded_fileAttestation']['tmp_name'], "AttestationsFormations/");
			}
			$reqUpdateAttestation="UPDATE new_competences_relation SET AttestationFormation='".$nomfichier."' WHERE Id=".$_POST['Id'];
			$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
		}
	}
	
	if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
		echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
	}
	else{
		if($DroitsModifPrestation==false){
			echo "<script>FermerEtRecharger('Profil.php?Mode=Lecture&Id_Personne=".$_POST['Id_Personne']."');</script>";
		}
		else{
			echo "<script>FermerEtRecharger('Profil.php?Mode=ModifPresta&Id_Personne=".$_POST['Id_Personne']."');</script>";
		}
	}
}
elseif($_GET)
{
	$UniquementB=0;
	if($Droits=="Ecriture" || $Droits=="Administrateur"){
		$UniquementB=1;
	}
//Mode ajout ou modification
	if($_GET['Mode']=="Modif")
	{
		$Relation=mysqli_query($bdd,"SELECT * FROM new_competences_relation WHERE Id=".$_GET['Id']);
		$LigneRelation=mysqli_fetch_array($Relation);
	}
	$i=0; // variable de test
	$j=0; // variable pour garder la valeur du premier enregistrement catégorie pour l'affichage
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" enctype="multipart/form-data" action="Ajout_Profil_Qualification.php" onSubmit="return VerifChamps();" class="None">
	<input type="hidden" id="Mode" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<input type="hidden" name="Id_Categorie_Maitre" value="<?php echo $_GET['Id_Categorie_Maitre'];?>">
	<input type="hidden" id="UniquementB" name="UniquementB" value="<?php echo $UniquementB;?>">
	<table  style="width:100%; align:center;" class="TableCompetences">
		<tr>
			<td>
				<table style="width:100%;">
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
						<td>
							<?php
								$result=mysqli_query($bdd,"SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']);
								$row=mysqli_fetch_array($result);
								echo $row['Nom']." ".$row['Prenom'];
							?>
						</td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Group";}?> : </td>
						<td>
							<?php
							$Evaluation="L";
							$IdCategorie=0;
							$IdQualification=0;
							if($_GET['Mode']=="Modif")
							{
								$result=mysqli_query($bdd,"SELECT new_competences_categorie_qualification.* FROM new_competences_categorie_qualification, new_competences_relation, new_competences_qualification WHERE new_competences_relation.Id=".$_GET['Id']." AND new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id AND Id_Categorie_Maitre=".$_GET['Id_Categorie_Maitre']);
								$row=mysqli_fetch_array($result);
								$IdCategorie=$row['Id'];
								$IdQualification=$LigneRelation['Id_Qualification_Parrainage'];
								$Evaluation=$LigneRelation['Evaluation'];
							}
							else
							{
								$result=mysqli_query($bdd,"SELECT Id FROM new_competences_categorie_qualification WHERE Id_Categorie_Maitre=".$_GET['Id_Categorie_Maitre']." ORDER BY Libelle ASC");
								$row=mysqli_fetch_array($result);
								$IdCategorie=$row['Id'];
							}
							$result=mysqli_query($bdd,"SELECT * FROM new_competences_categorie_qualification WHERE Id_Categorie_Maitre=".$_GET['Id_Categorie_Maitre']." ORDER BY Libelle");
							?>
							<select name="Categorie" OnChange="Remplir(Categorie.value,'<?php echo $Evaluation;?>',<?php echo $_GET['Id_Categorie_Maitre'];?>)">
							<?php
							while($row=mysqli_fetch_array($result))
							{
								if($IdCategorie==0){$IdCategorie=$row['Id'];}
								if ($i==0) { $j=$row['Id']; $i=1; } // garder la valeur du premier enregistrement
							?>
								<option value="<?php echo $row['Id'];?>" <?php if($_GET['Mode']=="Modif"){if($IdCategorie==$row['Id']){echo "selected";}}?>><?php echo $row['Libelle'];?></option>
							<?php
							}
							?>
							</select>
						</td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle">Qualification : </td>
						<td>
							<DIV id="scat">
								<select name="Qualification" style="width:300px;">
								</select>
							</DIV>
							<?php
							// Séléction de tous les enregistrements de la table Sous-Catégorie
							$rq="SELECT * FROM new_competences_qualification ORDER BY Libelle ASC;";
							$result= mysqli_query($bdd,$rq) or die ("Select impossible");
							// $i = initialise le variable i
							$i=0;
							while ($row=mysqli_fetch_row($result))
							{
								 // Remplir le tableau (array) en javascript
								 // ex : List[1]=new Array (1,1,"Sous-catégorie 1");
								 // ex : List[2]=new Array (2,1,"Sous-catégorie 2");
								 echo "<script>List[".$i."] = new Array(".$row[0].",".$row[1].",'".addslashes($row[2])."',".$IdQualification.");</script>\n";
								 $i=$i+1; // Incrémentation de $i
							}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr><td><table><tr><td bgcolor=yellow><div id="Cours" style="font-size:10px"></div></td></tr></table></td></tr>
		
		<tr>
			<td>
				<table style="width:100%;">
					<tr class="TitreColsUsers" id="Dates_DebutFinSansLimite">
						<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> :</td>
						<td>
							<input type="date" name="Date_Debut" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneRelation['Date_Debut']);} ?>">
						</td>
						<td width="10"></td>
						<td class="Libelle" id="Date_Fin1"><?php if($LangueAffichage=="FR"){echo "Datez fin";}else{echo "End date";}?> :</td>
						<td id="Date_Fin2">
							<input type="date" name="Date_Fin" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneRelation['Date_Fin']);} ?>">
						</td>
						<td width="10"></td>
						<td class="Libelle" id="Date_SansLimite1"><?php if($LangueAffichage=="FR"){echo "Sans limite";}else{echo "Without limit";}?> :</td>
						<td id="Date_SansLimite2">
							<select name="Sans_Fin">
								<option <?php if($_GET['Mode']=="Modif"){if($LigneRelation['Sans_Fin']=="Non"){echo "selected";}}?> value="Non">Non</option>
								<option <?php if($_GET['Mode']=="Modif"){if($LigneRelation['Sans_Fin']=="Oui"){echo "selected";}}?> value="Oui">Oui</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<div id="Legende"></div>
						</td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle" id="Resultat_QCM"><?php if($LangueAffichage=="FR"){echo "Résultat QCM";}else{echo "MCQ score";}?> :</td>
						<td><input size="10" name="Resultat_QCM" <?php if($_GET['Mode']=="Modif"){echo "value='".$LigneRelation['Resultat_QCM']."'";}?>></td>
						<td width="10"></td>
						<td class="Libelle">Evaluation :</td>
						<td>
							<DIV id="Eval">
								<select size="1" name="Evaluation">
								</select>
							</DIV>
						</td>
					</tr>
					<tr class="TitreColsUsers">
						<td class="Libelle" id="Date_QCM"><?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "MCQ date";}?> :</td>
						<td>
							<input type="date" name="Date_QCM" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneRelation['Date_QCM']);} ?>">
						</td>
					</tr>
					<tr class="TitreColsUsers" id="Surveillance">
						<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date surveillance";}else{echo "Monitoring date";}?> :</td>
						<td>
							<input type="date" name="Date_Surveillance" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneRelation['Date_Surveillance']);} ?>">
						</td>
						<td width="10"></td>
						<td><?php if($LangueAffichage=="FR"){echo "QCM Surveillance";}else{echo "Monitoring MCQ";}?> :</td><td><input size="10" name="QCM_Surveillance" <?php if($_GET['Mode']=="Modif"){echo "value='".$LigneRelation['QCM_Surveillance']."'";}?>></td>
					</tr>
					<?php
					if($IdPersonneConnectee > 0)
					{
						$resultHierar=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee." AND (Id_Poste=5 OR Id_Poste=8) ");
						$nbHierar=mysqli_num_rows($resultHierar);
						
						$resultHierarPlat=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND (Id_Poste=17 OR Id_Poste=18 OR Id_Poste=13) ");
						$nbHierarPlat=mysqli_num_rows($resultHierarPlat);
						if($nbHierar>0  || $nbHierarPlat>0)
						{
					?>
					<tr class="TitreColsUsers">
						<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Masquer la ligne dans le tableau de compétence";}else{echo "Hide the line in the competency table";}?> :</td>
						<td>
							<select name="visible">
								<option value="0" <?php if($_GET['Mode']=="Modif"){if($LigneRelation['Visible']==0){echo "selected";}}else{echo "selected";} ?>>Non</option>
								<option value="1" <?php if($_GET['Mode']=="Modif"){if($LigneRelation['Visible']==1){echo "selected";}} ?>>Oui</option>
							</select>
						</td>
					</tr>
					<?php
						}
					}
					?>
					<tr class="TitreColsUsers" id="Surveillance">
						<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Commentaire";}else{echo "Comment";}?> :</td>
					</tr>
					<tr>
						<td colspan="8">
							<textarea name="commentaire" id="commentaire" cols="100" rows="6" style="resize:none;"><?php if($_GET['Mode']=="Modif"){echo stripslashes($LigneRelation['Commentaire']);} ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php if($LangueAffichage=="FR"){echo "Fiche de qualification à joindre";}else{echo "Qualification sheet to join";}?> :</b>
						</td>
						<td colspan="6">
							<?php 
								if($LigneRelation['FicheQualification']<>""){
									if($LangueAffichage=="FR"){
										echo "<a class=\"Info\" href=\"FichesQualifications/".$LigneRelation['FicheQualification']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Fiche'></a>";
									}
									else{
										echo "<a class=\"Info\" href=\"FichesQualifications/".$LigneRelation['FicheQualification']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Sheet'>></a>";
									}
								}
							?>
							<input type="file" name="uploaded_fileFicheQualif" />
							&nbsp;&nbsp;&nbsp;<input class="Bouton" type="submit" name="SupprimerFicheQualif" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Supprimer fiche de qualification";}else{echo "Delete qualification sheet";}?>" />
						</td>
					</tr>
					<tr><td colspan="8" align="center">
					<?php 
					$Plateforme_Identique=false;
							//Plateforme
							$PLATEFORME="";
							$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id FROM new_competences_plateforme, new_competences_personne_plateforme";
							$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$_GET['Id_Personne']." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
							$requete_plateforme.=" ORDER BY new_competences_plateforme.Libelle ASC";
							$result_plateforme=mysqli_query($bdd,$requete_plateforme);
							$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
							if(isset($_SESSION['Id_Plateformes']))
							{
								if($nbenreg_plateforme>0)
								{
									while($row_plateforme=mysqli_fetch_array($result_plateforme))
									{
										foreach($_SESSION['Id_Plateformes'] as &$value){if($row_plateforme['Id']==$value){$Plateforme_Identique=true;}}
									}
								}
							}
							if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
					?>
						<input class="Bouton" type="submit" name="btn_Save"
						<?php

								if($_GET['Mode']=="Modif")
								{
									if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
								}
								else
								{
									if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
								}
						?>
					>
					<?php 
					}
					?>
					</td></tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
	<?php 
		//Uniquement hors qualipso
		if($_GET['Mode']=="Modif")
	{
		if($LigneRelation['Id_Besoin']==0){
	?>
	<table class="TableCompetences" style="width:100%;">
		
		<tr><td><b><?php if($LangueAffichage=="FR"){echo "Attestation de formation à joindre";}else{echo "Attestation of formation to join";}?> :</b></td>
			<td>
				<?php 
					if($LigneRelation['AttestationFormation']<>""){
						if($LangueAffichage=="FR"){
							echo "<a class=\"Info\" href=\"AttestationsFormations/".$LigneRelation['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";
						}
						else{
							echo "<a class=\"Info\" href=\"AttestationsFormations/".$LigneRelation['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'>></a>";
						}
					}
				?>
				<input type="file" name="uploaded_fileAttestation" />
				<input class="Bouton" type="submit" name="SauvegarderAttestation" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Sauvegarder";}else{echo "Save";}?>" />
				
				
				&nbsp;&nbsp;&nbsp;<input class="Bouton" type="submit" name="SupprimerAttestation" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Supprimer attestation";}else{echo "Delete certificate";}?>" />
			</td>
		</tr>
	</table>
	<br>
	<?php 
		}
	}
	?>
	</form>
<?php
	if($_GET['Mode']=="Modif"){$j=$IdCategorie;}
	echo "<script>Remplir ($j,'$Evaluation',".$_GET['Id_Categorie_Maitre']."); </script>"; 	// Remplir la deuxième liste de choix avec les données
																							// des sous-catégories en utilisant la valeur j
	if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>