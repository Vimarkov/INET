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
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Group";}?></td>
				<td class="EnTeteTableauCompetences">Qualif.</td>
				<td class="EnTeteTableauCompetences" colspan="2" style="text-align:center;">% Qualif.</td>
			</tr>
			<?php
				if($Type=="Prestation")
				{
					$Requetes_Liste_Qualifs="
						SELECT
							DISTINCT new_competences_qualification.Id,
							new_competences_qualification.Id_Categorie_Qualification,
							new_competences_qualification.Libelle
						FROM
							new_competences_qualification
							LEFT JOIN new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
							LEFT JOIN new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
						WHERE
							new_competences_prestation_qualification.Id_Prestation IN (".$Id.")";
				}
				elseif($Type=="Plateforme")
				{
					$Requetes_Liste_Qualifs="
						SELECT
							DISTINCT new_competences_qualification.Id,
							new_competences_qualification.Id_Categorie_Qualification,
							new_competences_qualification.Libelle
						FROM
							new_competences_qualification
							LEFT JOIN new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
							LEFT JOIN new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
						WHERE
							new_competences_prestation_qualification.Id_Prestation IN
							(
								SELECT
									Id
								FROM
									new_competences_prestation
								WHERE
									new_competences_prestation.Id_Plateforme IN (".$Id.")
									AND new_competences_prestation.Active=0
							) ";
				}
				$Requetes_Liste_Qualifs.=" ORDER BY new_competences_categorie_qualification.Libelle ASC, new_competences_qualification.Libelle ASC";
				$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
				$nbenreg=mysqli_num_rows($Result_Liste_Qualification);
				if($nbenreg>0){
					$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
					$Derniere_Categorie=0;
					$Affiche_Categorie=1;
					$Nb_Qualification_Categorie=1;
					$ligneQualif1 = "";
					$ligneQualif2 = "";
					$nbCategorie = 0;
					$SommeCategorie = 0;
					$premier = 0;
					$nbVQSX = 0;
					$nbVQSXBL = 0;
					
					while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification)){
						$Requete_Categorie="SELECT Libelle, Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=".$Derniere_Categorie;
						$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
						$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
						if($Derniere_Categorie!=$Ligne_Liste_Qualification['Id_Categorie_Qualification']){
							if($Derniere_Categorie!=0){
								if ($premier == 0){echo "<tr width=20>";$premier = 1;}
								echo "<td height=20 style='text-align:left;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."' rowspan=\"".$Nb_Qualification_Categorie."\">".$Ligne_Categorie[0]."</td>";
								echo $ligneQualif1;
								$resultat = 0;
								if ($SommeCategorie > 0){
									$resultat = round(($nbCategorie / $SommeCategorie) * 100);
								}
								echo "<td height=20 width=40 style='text-align:center;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."' rowspan=\"".$Nb_Qualification_Categorie."\">".$resultat."%</td>";
								echo "</tr><tr>";
								echo $ligneQualif2;
								
								$ligneQualif1 = "";
								$ligneQualif2 = "";
								$Nb_Qualification_Categorie=1;
								$nbCategorie = 0;
								$SommeCategorie = 0;
								if($Affiche_Categorie==0){$Affiche_Categorie=1;}else{$Affiche_Categorie=0;}
							}
							//NB QUALIF CORRECT
							$Requete_Ligne_Qualifications="SELECT count(*) FROM (";
							$Requete_Ligne_Qualifications.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
							if($Type=="Prestation"){
								$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_Qualifications.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$Id.") AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
							}
							elseif($Type=="Plateforme"){
								$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_Qualifications.="WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) IN (".$Id.") AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
								
							}
							$Requete_Ligne_Qualifications.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>'' 
															AND new_competences_relation.Date_Debut<='".$dateFin."' ";
							$Requete_Ligne_Qualifications.=" AND (new_competences_relation.Date_Fin>='".$dateDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')";
							
							$Requete_Categorie="SELECT Libelle, Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=".$Ligne_Liste_Qualification['Id_Categorie_Qualification'];
							$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
							$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
							
							$Requete_Ligne_Qualifications.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
							$Requete_Ligne_Qualifications.=" GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 ";
	
							$Requete_Ligne_Qualifications.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='Q1' or toto2.Evaluation='Q2' or toto2.Evaluation='Q3' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
							$Requete_Ligne_Qualifications.=" or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
							$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
							$Qualif=mysqli_fetch_array($Result_Ligne_Qualifications);

							//Somme QUALIF
							$Requete_Ligne_QualificationsSomme="SELECT count(*) FROM (";
							$Requete_Ligne_QualificationsSomme.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
							if($Type=="Prestation"){
								$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$Id.") AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
							}
							elseif($Type=="Plateforme"){
								$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) IN (".$Id.") AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
								
							}
							$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>'' 
																AND new_competences_relation.Date_Debut<='".$dateFin."' ";
							$Requete_Ligne_QualificationsSomme.=" AND (new_competences_relation.Date_Fin>='".$dateDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')";
							$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
							$Requete_Ligne_QualificationsSomme.=" GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 ";

							$Requete_Ligne_QualificationsSomme.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='Q1' or toto2.Evaluation='Q2' or toto2.Evaluation='Q3' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
							$Requete_Ligne_QualificationsSomme.="or toto2.Evaluation='B' or toto2.Evaluation='Bi' or toto2.Evaluation='L' or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
		
							$Result_Ligne_QualificationsSomme=mysqli_query($bdd,$Requete_Ligne_QualificationsSomme);
							$QualifSomme=mysqli_fetch_array($Result_Ligne_QualificationsSomme);

							//RESULTAT
							$resultat = 0;
							$nbCategorie += $Qualif[0];
							$nbVQSX += $Qualif[0]; 
							$nbVQSXBL += $QualifSomme[0];
							$SommeCategorie += $QualifSomme[0];
							if ($QualifSomme[0] > 0){
								$resultat = round(($Qualif[0] / $QualifSomme[0]) * 100);
								$resultat.="%";
							}
							else{
								$resultat ="Need not identified";
							}
							$ligneQualif1 .= "<td style='text-align:left;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."'>\n".$Ligne_Liste_Qualification['Libelle']."</td>";
							$ligneQualif1 .= "<td width=40 style='text-align:center;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."'>".$resultat."</td>";
							$Derniere_Categorie=$Ligne_Liste_Qualification['Id_Categorie_Qualification'];
						}
						else{
							//NB QUALIF CORRECT
							$Requete_Ligne_Qualifications="SELECT count(*) FROM (";
							$Requete_Ligne_Qualifications.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
							if($Type=="Prestation"){
								$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_Qualifications.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$Id.") AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
							}
							elseif($Type=="Plateforme"){
								$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_Qualifications.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_Qualifications.="WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$Id.") AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_Qualifications.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
								
							}
							$Requete_Ligne_Qualifications.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>''  ";
							$Requete_Ligne_Qualifications.=" AND (new_competences_relation.Date_Fin>='".$dateDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')";
							$Requete_Ligne_Qualifications.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto";
							$Requete_Ligne_Qualifications.=" GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 ";

							$Requete_Ligne_Qualifications.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='Q1' or toto2.Evaluation='Q2' or toto2.Evaluation='Q3' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
							$Requete_Ligne_Qualifications.=" or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
							
							$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
							$Qualif=mysqli_fetch_array($Result_Ligne_Qualifications);
							
							//Somme QUALIF
							$Requete_Ligne_QualificationsSomme="SELECT count(*) FROM (";
							$Requete_Ligne_QualificationsSomme.="SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
							if($Type=="Prestation"){
								$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="WHERE new_competences_personne_prestation.Id_Prestation IN (".$Id.") AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
							}
							elseif($Type=="Plateforme"){
								$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="= new_competences_relation.Id_Personne ";
								$Requete_Ligne_QualificationsSomme.="WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) IN (".$Id.") AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND ";
								$Requete_Ligne_QualificationsSomme.="new_competences_personne_prestation.Date_Fin>='".$dateDebut."'" ;
								
							}
							$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 AND new_competences_relation.Evaluation<>''  ";
							$Requete_Ligne_QualificationsSomme.=" AND (new_competences_relation.Date_Fin>='".$dateDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')";
							$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Id_Qualification_Parrainage=".$Ligne_Liste_Qualification['Id']." ORDER BY new_competences_relation.Date_QCM DESC) AS toto ";
							$Requete_Ligne_QualificationsSomme.=" GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 ";
							$Requete_Ligne_QualificationsSomme.=" WHERE toto2.Evaluation='Q' or toto2.Evaluation='Q1' or toto2.Evaluation='Q2' or toto2.Evaluation='Q3' or toto2.Evaluation='S' OR toto2.Evaluation='V' OR toto2.Evaluation='X' ";
							$Requete_Ligne_QualificationsSomme.="or toto2.Evaluation='B' or toto2.Evaluation='Bi' or toto2.Evaluation='L' or toto2.Evaluation='Low' or toto2.Evaluation='Medium' or toto2.Evaluation='High' ";
							
							$Result_Ligne_QualificationsSomme=mysqli_query($bdd,$Requete_Ligne_QualificationsSomme);
							$QualifSomme=mysqli_fetch_array($Result_Ligne_QualificationsSomme);
							//RESULTAT
							$resultat = 0;
							$nbCategorie += $Qualif[0];
							$SommeCategorie += $QualifSomme[0];
							$nbVQSX += $Qualif[0]; 
							$nbVQSXBL += $QualifSomme[0];
							if ($QualifSomme[0] > 0){
								$resultat = round(($Qualif[0] / $QualifSomme[0]) * 100);
								$resultat.="%";
							}
							else{
								$resultat ="Need not identified";
							}
							$ligneQualif2 .= "<td style='text-align:left;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."'>\n".$Ligne_Liste_Qualification['Libelle']."</td>";
							$ligneQualif2 .= "<td width=40 style='text-align:center;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."'>".$resultat."</td></tr><tr>";
							$Nb_Qualification_Categorie+=1;
						}
						
					}
					$Requete_Categorie="SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=".$Derniere_Categorie;
					$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
					$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
					echo "<td style='text-align:left;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."' rowspan=\"".$Nb_Qualification_Categorie."\">".$Ligne_Categorie[0]."</td>";
					echo $ligneQualif1;
					if ($SommeCategorie > 0){
						$resultat = round(($nbCategorie / $SommeCategorie) * 100);
						$resultat.="%";
					}
					else{
						$resultat ="Need not identified";
					}
					echo "<td height=20 width=40 style='text-align:center;' class='En_Tete_Cellule_Indicateur_Competence".$Affiche_Categorie."' rowspan=\"".$Nb_Qualification_Categorie."\">".$resultat."</td></tr>";
					echo $ligneQualif2;

					$valeur = 0;
					if($nbVQSXBL>0){
						$valeur = round(($nbVQSX / $nbVQSXBL) * 100);
					}
					echo "<tr>";
					echo "<td class='EnTeteTableauCompetences' colspan='2' style='border:1px #003333 solid;text-align:center;'>% Qualif</td>";
					echo "<td height=20 width=40 style='text-align:center;border:1px #003333 solid;' colspan='2'>".$valeur."%</td>";
					echo "</tr>";
				}		//Fin If
					mysqli_free_result($Result_Liste_Qualification);	// Libération des résultats
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<?php
			if($_SESSION['Id_Personne']==295 || $_SESSION['Id_Personne']==1351 || $_SESSION['Id_Personne']==2833){
				
			if($_SESSION["Langue"]=="FR")
			{
				$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
			}
			else
			{
				$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
			}

			$arrayTaux=array();
			$i=0;
			
			$listeQualif="-1";
			$Requete_ListeQualifUER="
				SELECT DISTINCT new_competences_qualification.Id 
				FROM new_competences_qualification, new_competences_prestation_qualification,new_competences_prestation
				WHERE new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification 
				AND new_competences_prestation_qualification.Id_Prestation=new_competences_prestation.Id		
				AND new_competences_prestation.Id_Plateforme IN (".$Id.")
				AND new_competences_prestation.Active=0  ";
			$Result_ListeQualifUER=mysqli_query($bdd,$Requete_ListeQualifUER);
			$nbListeQualifUER=mysqli_num_rows($Result_ListeQualifUER);
			if($nbListeQualifUER>0){
				$listeQualif="";
				while($Ligne_ListeQualifUER=mysqli_fetch_array($Result_ListeQualifUER)){
					if($listeQualif<>""){$listeQualif.=",";}
					$listeQualif.=$Ligne_ListeQualifUER['Id'];
				}
			}
				
			for($laDate=$dateDebut;$laDate<=$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 month"))){
				$leDebut=date('Y-m-1',strtotime($laDate));
				if($leDebut<$dateDebut){$leDebut=$dateDebut;}
				$laFin=date('Y-m-1',strtotime($laDate.'+ 1 month'));
				$laFin=date('Y-m-d',strtotime($laFin.'- 1 day'));
				if($dateFin<$laFin){$laFin=$dateFin;}
				
	
				//NB QUALIF CORRECT
				$Requete_Ligne_Qualifications="SELECT count(*) FROM (
							SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
				if($Type=="Prestation"){
					$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation 
												ON new_competences_personne_prestation.Id_Personne= new_competences_relation.Id_Personne 
												WHERE new_competences_personne_prestation.Id_Prestation IN (".$Id.") 
												AND new_competences_personne_prestation.Date_Debut<='".$laFin."' 
												AND new_competences_personne_prestation.Date_Fin>='".$leDebut."'" ;
				}
				elseif($Type=="Plateforme"){
					$Requete_Ligne_Qualifications.=" LEFT JOIN new_competences_personne_prestation 
													ON new_competences_personne_prestation.Id_Personne = new_competences_relation.Id_Personne
													WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$Id.") 
													AND new_competences_personne_prestation.Date_Debut<='".$laFin."' 
													AND new_competences_personne_prestation.Date_Fin>='".$leDebut."'" ;
					
				}
				$Requete_Ligne_Qualifications.=" AND new_competences_relation.Type='Qualification' 
												AND new_competences_relation.Suppr=0  AND new_competences_relation.Evaluation<>'' 
												AND new_competences_relation.Date_Debut<='".$laFin."'
												AND (new_competences_relation.Date_Fin>='".$leDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')
												AND new_competences_relation.Id_Qualification_Parrainage IN (";
												
												if($Type=="Prestation")
												{
													$Requete_Ligne_Qualifications.="
														SELECT
															DISTINCT new_competences_qualification.Id
														FROM
															new_competences_qualification
														LEFT JOIN new_competences_prestation_qualification 
														ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
														WHERE
															new_competences_prestation_qualification.Id_Prestation IN (".$Id.")";
												}
												elseif($Type=="Plateforme")
												{
													$Requete_Ligne_Qualifications.=$listeQualif;
												}
				
				$Requete_Ligne_Qualifications.=")
												ORDER BY new_competences_relation.Date_QCM DESC) AS toto

												GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 
												WHERE toto2.Evaluation='Q' 
												or toto2.Evaluation='Q1' 
												or toto2.Evaluation='Q2' 
												or toto2.Evaluation='Q3'
												OR toto2.Evaluation='S' 
												OR toto2.Evaluation='V' 
												OR toto2.Evaluation='X' 
												OR toto2.Evaluation='Low' 
												OR toto2.Evaluation='Medium' 
												OR toto2.Evaluation='High' ";

				$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
				$Qualif=mysqli_fetch_array($Result_Ligne_Qualifications);
				
				
				//Somme QUALIF
				$Requete_Ligne_QualificationsSomme="SELECT count(*) FROM (
										SELECT * FROM (SELECT new_competences_relation.Id, new_competences_relation.Id_Personne, new_competences_relation.Evaluation,Id_Qualification_Parrainage,(@row_number:=@row_number + 1) AS rnk FROM new_competences_relation";
				if($Type=="Prestation"){
					$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation 
														ON new_competences_personne_prestation.Id_Personne= new_competences_relation.Id_Personne 
														WHERE new_competences_personne_prestation.Id_Prestation IN (".$Id.") 
														AND new_competences_personne_prestation.Date_Debut<='".$laFin."' 
														AND new_competences_personne_prestation.Date_Fin>='".$leDebut."'" ;
				}
				elseif($Type=="Plateforme"){
					$Requete_Ligne_QualificationsSomme.=" LEFT JOIN new_competences_personne_prestation 
													ON new_competences_personne_prestation.Id_Personne= new_competences_relation.Id_Personne 
													WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) IN (".$Id.") 
													AND new_competences_personne_prestation.Date_Debut<='".$laFin."' 
													AND new_competences_personne_prestation.Date_Fin>='".$leDebut."'" ;
					
				}
				$Requete_Ligne_QualificationsSomme.=" AND new_competences_relation.Type='Qualification' 
													AND new_competences_relation.Suppr=0  AND new_competences_relation.Evaluation<>'' 
													AND new_competences_relation.Date_Debut<='".$laFin."'
													AND (new_competences_relation.Date_Fin>='".$leDebut."' OR new_competences_relation.Date_Fin<='0001-01-01')
													AND new_competences_relation.Id_Qualification_Parrainage IN (";
													if($Type=="Prestation")
													{
														$Requete_Ligne_QualificationsSomme.="
															SELECT
																DISTINCT new_competences_qualification.Id
															FROM
																new_competences_qualification
															LEFT JOIN new_competences_prestation_qualification 
															ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
															WHERE
																new_competences_prestation_qualification.Id_Prestation IN (".$Id.")";
													}
													elseif($Type=="Plateforme")
													{
														$Requete_Ligne_QualificationsSomme.=$listeQualif;
													}									
				$Requete_Ligne_QualificationsSomme.=")
													ORDER BY new_competences_relation.Date_QCM DESC) AS toto 
													GROUP BY toto.Id_Personne,toto.Id_Qualification_Parrainage) AS toto2 
													WHERE toto2.Evaluation='Q' 
													or toto2.Evaluation='Q1' 
													or toto2.Evaluation='Q2' 
													or toto2.Evaluation='Q3'
													OR toto2.Evaluation='S' 
													OR toto2.Evaluation='V' 
													OR toto2.Evaluation='X' 
													OR toto2.Evaluation='B' 
													OR toto2.Evaluation='Bi'
													OR toto2.Evaluation='L' 
													OR toto2.Evaluation='Low' 
													OR toto2.Evaluation='Medium' 
													OR toto2.Evaluation='High' ";
	
				$Result_Ligne_QualificationsSomme=mysqli_query($bdd,$Requete_Ligne_QualificationsSomme);
				$QualifSomme=mysqli_fetch_array($Result_Ligne_QualificationsSomme);
				
				//RESULTAT
				$resultat = null;
				if ($QualifSomme[0] > 0){
					$resultat = round(($Qualif[0] / $QualifSomme[0]) * 100);
				}
				
				
				$arrayTaux[$i]=array("Mois" => $MoisLettre[date('m',strtotime($laDate))-1]." ".date('y',strtotime($laDate)),"taux" => $resultat);
				$i++;
			}
		
			
		?>
		<table class="TableCompetences" style="width:100%;border-spacing:0;">
				<tr>
					<td width="100%">
						<div id="chart_TauxQualif" style="width:100%;height:400px;"></div>
						<script>
							var chart = am4core.create("chart_TauxQualif", am4charts.XYChart);

							// Add data
							chart.data = <?php echo json_encode($arrayTaux); ?>;
							chart.numberFormatter.numberFormat = "#'%'";

							// Create axes
							var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
							categoryAxis.dataFields.category = "Mois";
							categoryAxis.renderer.grid.template.location = 0;
							categoryAxis.renderer.minGridDistance = 15;
							categoryAxis.renderer.labels.template.horizontalCenter = "right";
							categoryAxis.renderer.labels.template.verticalCenter = "middle";
							categoryAxis.renderer.labels.template.rotation = 270;
							categoryAxis.tooltip.disabled = true;
							categoryAxis.renderer.minHeight = 0;

							var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
							valueAxis.renderer.minWidth = 0;
							valueAxis.min= 0;
							valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualification rate (%)");}else{echo json_encode("Taux de qualif. (%)");} ?>;

							// Create series
							var series1 = chart.series.push(new am4charts.ColumnSeries());
							series1.columns.template.width = am4core.percent(80);
							series1.tooltipText = "{categoryX}: {valueY.value}";
							series1.dataFields.categoryX = "Mois";
							series1.dataFields.valueY = "taux";
							series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualification rate");}else{echo json_encode("Taux de qualif.");} ?>;
							series1.stroke  = "#66b6dc";
							series1.fill  = "#66b6dc";
							
							var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
							bullet1.label.text = "{valueY}";
							bullet1.locationY = 0.5;
							bullet1.label.fill = am4core.color("#ffffff");
							bullet1.interactionsEnabled = false;
							
							// Cursor
							chart.cursor = new am4charts.XYCursor();
							chart.cursor.behavior = "panX";
							chart.cursor.lineX.opacity = 0;
							chart.cursor.lineY.opacity = 0;
							
							// Add legend 
							chart.scrollbarX = new am4core.Scrollbar();
							
							chart.exporting.menu = new am4core.ExportMenu();
						</script>
					</td>
				</tr>
			</table>	
			
			
			
		<?php 
		
		}
		?>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>