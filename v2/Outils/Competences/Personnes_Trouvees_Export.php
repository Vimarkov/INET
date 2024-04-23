<html>
<head>
	<title>Compétences - Export personnes recherchées</title><meta name="robots" content="noindex">
</head>
<?php
	require("../Connexioni.php");
	require_once("../Formation/Globales_Fonctions.php");
	
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=dataExport.xls");
?>

<table style="width:100%; border-spacing:0; align:center;">
	<?php
	$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
	$result=mysqli_query($bdd,str_replace("\\","",$_POST["Requete"]));
	$nbenreg=mysqli_num_rows($result);
	if($nbenreg>0)
	{
	?>
	<tr>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Code analytique";}else{echo "Analytic code";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name First name";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
	</tr>
	<?php
	$Couleur="#EEEEEE";
	while($row=mysqli_fetch_array($result))
	{
		if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
		else{$Couleur="#EEEEEE";}
		
		//Personne	//MAJ DU 27/12/12
		$Nom="";
		$Prenom="";
		$requete_etatcivil="SELECT Nom, Prenom,Contrat FROM new_rh_etatcivil WHERE Id=".$row[0];
		$result_etatcivil=mysqli_query($bdd,$requete_etatcivil);
		$row_etatcivil=mysqli_fetch_array($result_etatcivil);
		$Nom=$row_etatcivil[0];
		$Prenom=$row_etatcivil[1];
		$Contrat=$row_etatcivil[2];
		
		//Plateforme
		$PLATEFORME="";
		$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id FROM new_competences_plateforme, new_competences_personne_plateforme";
		$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$row[0]." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
		$requete_plateforme.=" ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC";
		$result_plateforme=mysqli_query($bdd,$requete_plateforme);
		$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
		if($nbenreg_plateforme>0)
		{
			while($row_plateforme=mysqli_fetch_array($result_plateforme))
			{
				if($PLATEFORME==""){$PLATEFORME=$row_plateforme[0];}else{$PLATEFORME.="<br>".$row_plateforme[0];}}
		}
		
		//Prestation
		$PRESTATION="";
		$CODE_ANALYTIQUE="";
		$requete_prestation="SELECT DISTINCT new_competences_prestation.Libelle, new_competences_prestation.Code_Analytique FROM new_competences_prestation, new_competences_personne_prestation";
		$requete_prestation.=" WHERE new_competences_personne_prestation.Id_Personne=".$row[0]." AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation ";
		$requete_prestation.=" AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
		$requete_prestation.=" ORDER By new_competences_personne_prestation.Date_Debut DESC";
		//echo $requete_prestation;
		$result_prestation=mysqli_query($bdd,$requete_prestation);
		$nbenreg_prestation=mysqli_num_rows($result_prestation);
		if($nbenreg_prestation>0)
			{while($row_prestation=mysqli_fetch_array($result_prestation)){if($PRESTATION==""){$PRESTATION=$row_prestation[0];$CODE_ANALYTIQUE=$row_prestation[1];}else{$PRESTATION.="<br>".$row_prestation[0];$CODE_ANALYTIQUE.="<br>".$row_prestation[1];}}}
		
		//Metier
		$METIER="";
		$requete_metier="SELECT DISTINCT new_competences_metier.Libelle FROM new_competences_metier, new_competences_personne_metier";
		$requete_metier.=" WHERE new_competences_personne_metier.Id_Personne=".$row[0]." AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier ";
		$requete_metier.=" ORDER By new_competences_personne_metier.Id DESC";
		$result_metier=mysqli_query($bdd,$requete_metier);
		$nbenreg_metier=mysqli_num_rows($result_metier);
		if($nbenreg_metier>0)
			{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.="<br>".$row_metier[0];}}}
							
	?>
	<tr bgcolor="<?php echo $Couleur;?>">
		<td><?php echo $PLATEFORME;?></td>
		<td><?php echo $CODE_ANALYTIQUE;?></td>
		<td><?php echo $PRESTATION;?></td>
		<td><?php echo $Nom." ".$Prenom;?></td>
		<td><?php echo $METIER;?></td>
		<td><?php echo $Contrat;?></td>
	</tr>
	<?php
		}	//Fin boucle
	}		//Fin If
	else{echo "Aucune personne ne correspond à ces critères.";}
	mysqli_free_result($result);	// Libération des résultats
	?>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>