<!DOCTYPE html>
<?php 
require_once("../Connexioni.php");
require_once("../Fonctions.php");
session_start();
?>
<html>
<head>
	<title>Comeptences - Indicateurs compétences</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="../../amcharts/core.js"></script>	
	<script type="text/javascript" src="../../amcharts/charts.js"></script>	
	<script type="text/javascript" src="../../amcharts/themes/animated.js"></script>
	<script type="text/javascript" src="../../amcharts/themes/dataviz.js"></script>
	
	<script type="text/javascript" type="text/javascript" >
		function showTooltip(div, title, desc)
		{
		 div.style.display = 'inline';
		 div.style.position = 'absolute';
		 div.style.width = '200';
		 div.style.backgroundColor = '#EFFCF0';
		 div.style.border = 'dashed 1px black';
		 div.style.padding = '10px';
		 div.innerHTML = '<b>' + title + '</b><div style="text-align:left; padding-left:10; padding-right:5">' + desc + '</div>';
		}
		 
		function hideTooltip(div)
		{
		 div.style.display = 'none';
		}
		function OuvreFenetreProfil(Mode,Id)
			{window.open("Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");}
		function filtrer(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnReset2' name='btnReset2' value='Reset'>";
			document.getElementById('filtrer').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnReset2").dispatchEvent(evt);
			document.getElementById('filtrer').innerHTML="";
		}
	</script>
</head>

<?php

if($_POST){
	$Id=$_POST['Id'];
	$Type=$_POST['Type'];
	$dateDebut=date('Y-m-d');
	$dateFin=date('Y-m-d');
}
else{
	$Id=$_GET['Id'];
	$Type=$_GET['Type'];
	$dateDebut=date('Y-m-d');
	$dateFin=date('Y-m-d');
}

if($Type=="Prestation")
{
	$Requetes_Titre="SELECT Libelle, Code_Analytique, Id_Plateforme, 
		(SELECT Logo FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) AS Logo
		FROM new_competences_prestation WHERE Id IN (".$Id.")";
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$nbenreg=mysqli_num_rows($Result_Titre);
	$Presta="";
	$Img="";
	$nb=0;
	
	while($Ligne_Titre=mysqli_fetch_array($Result_Titre))
	{
		$Presta.=" ".$Ligne_Titre['Libelle']." - ".$Ligne_Titre['Code_Analytique']."<br>";
		if($nb==0){$Img.="<img src='../../Images/Logos/".$Ligne_Titre['Logo']."'>";$nb=1;}
	}
}
elseif($Type=="Plateforme")
{
	$Requetes_Titre="SELECT Libelle, Logo FROM new_competences_plateforme WHERE Id IN (".$Id.")";
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$nbenreg=mysqli_num_rows($Result_Titre);
	$Plat="";
	$Img="";
	
	while($Ligne_Titre=mysqli_fetch_array($Result_Titre))
	{
		$Plat.=" ".$Ligne_Titre['Libelle'];
		if($nbenreg<6){$Img.="<img src='../../Images/Logos/".$Ligne_Titre[1]."'>";}
	}
}

?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="Indicateur_Competences.php" method="post">
<table style="border-spacing:0; align:center;">
<input type="hidden" name="Id" value="<?php if($_POST){echo $_POST['Id'];}else{echo $_GET['Id'];} ?>">
<input type="hidden" name="Type" value="<?php if($_POST){echo $_POST['Type'];}else{echo $_GET['Type'];} ?>">
	<tr>
		<td>
			<table class="TableCompetences" style="border-spacing:0;">
			<tr>
				<td colspan="4" align="center">
					<img src="../../Images/Logos/Logo.gif" height="75" width="148">
					<?php 
						echo $Img;
					?>
					<br/><br/>
					<font style="text-decoration=underline; font-weight=bold;"><?php if($LangueAffichage=="FR"){echo "INDICATEUR COMPETENCES";}else{echo "COMPETENCIES KPIS";}?></font>
					<?php
						if($LangueAffichage=="FR")
						{
							if($Type=="Prestation"){echo "<br><br><b>Prestation : </b>".$Presta;}
							elseif($Type=="Plateforme"){echo "<br><br><b>Unité d'exploitation : </b>".$Plat;}
							echo "<br><br><u>Mise à jour le  : </u>".$DateJour;
						}
						else
						{
							if($Type=="Prestation"){echo "<br><br><b>Activity : </b>".$Presta;}
							elseif($Type=="Plateforme"){echo "<br><br><b>Operating unit : </b>".$Ligne_Titre[0];}
							echo "<br><br><u>Update on : </u>".$DateJour;
						}
					?>
					<br/><br/>
				</td>
			</tr>
			<tr>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
				<td class="EnTeteTableauCompetences">% Qualif.</td>
			</tr>
				<?php 
					$req="SELECT
							Id,Libelle
						FROM
							new_competences_prestation
						WHERE
							new_competences_prestation.Id_Plateforme IN (".$Id.")
							AND new_competences_prestation.Active=0
						ORDER BY Libelle";
					$Result_Presta=mysqli_query($bdd,$req);
					$nbenregPresta=mysqli_num_rows($Result_Presta);
					if($nbenregPresta>0){
						$couleur="#d3d3d3";
						while($LignePresta=mysqli_fetch_array($Result_Presta)){
							$valeur = 0;
							$nbVQSXBL=0;
							$nbVQSX=0;
							
							//NB QUALIF CORRECT
							$Requete_Ligne_Qualifications="SELECT count(*) FROM (";
							$Requete_Ligne_Qualifications.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
							$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
							$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
							$Requete_Ligne_Qualifications.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$LignePresta['Id'].") AND ";
							$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
							$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
							$Requete_Ligne_Qualifications.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>''  ";
							$Requete_Ligne_Qualifications.=" AND (new_competences_relation.Date_Fin>='".$dateDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')";
							$Requete_Ligne_Qualifications.=" AND new_competences_relation.Id_Qualification_Parrainage IN (
										SELECT
									DISTINCT new_competences_qualification.Id
								FROM
									new_competences_qualification
									LEFT JOIN new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
								WHERE
									new_competences_prestation_qualification.Id_Prestation IN (".$LignePresta['Id'].")
									) 
							ORDER BY new_competences_relation.Date_QCM DESC) AS toto";
							$Requete_Ligne_Qualifications.=" GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 ";

							$Requete_Ligne_Qualifications.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='Q1' or toto2.Evaluation='Q2' or toto2.Evaluation='Q3' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
							$Requete_Ligne_Qualifications.=" or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
							
							$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
							$Qualif=mysqli_fetch_array($Result_Ligne_Qualifications);
							
							//Somme QUALIF
							$Requete_Ligne_QualificationsSomme="SELECT count(*) FROM (";
							$Requete_Ligne_QualificationsSomme.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";

							$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
							$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
							$Requete_Ligne_QualificationsSomme.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$LignePresta['Id'].") AND ";
							$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
							$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;

							$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>''  ";
							$Requete_Ligne_QualificationsSomme.=" AND (new_competences_relation.Date_Fin>='".$dateDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')";
							$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Id_Qualification_Parrainage IN (
										SELECT
									DISTINCT new_competences_qualification.Id
								FROM
									new_competences_qualification
									LEFT JOIN new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
								WHERE
									new_competences_prestation_qualification.Id_Prestation IN (".$LignePresta['Id'].")
									)
									ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
							$Requete_Ligne_QualificationsSomme.=" GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 ";
							$Requete_Ligne_QualificationsSomme.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='Q1' or toto2.Evaluation='Q2' or toto2.Evaluation='Q3' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
							$Requete_Ligne_QualificationsSomme.="or toto2.Evaluation='B' or toto2.Evaluation='Bi' or toto2.Evaluation='L' or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
							
							$Result_Ligne_QualificationsSomme=mysqli_query($bdd,$Requete_Ligne_QualificationsSomme);
							$QualifSomme=mysqli_fetch_array($Result_Ligne_QualificationsSomme);
							
							$nbVQSX = $Qualif[0]; 
							$nbVQSXBL = $QualifSomme[0];
							
							if($nbVQSXBL>0){
								$valeur = round(($nbVQSX / $nbVQSXBL) * 100);
							}
						?>
							<tr>
							<td style="background-color:<?php echo $couleur;?>;border:1px #000000 solid;"><?php echo $LignePresta['Libelle'];?></td>
							<td style="background-color:<?php echo $couleur;?>;border:1px #000000 solid;"><?php echo $valeur;?> %</td>
							</tr>
						<?php
							if($couleur=="#d3d3d3"){
								$couleur="#ffffff";
							}
							else{
								$couleur="#d3d3d3";
							}
						}
					}
				?>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>