<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("check");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function OuvreFenetreValidation(Type,Langue,Id_Prestation,Personne,DateQCM,Qualif)
	{
		var elements = document.getElementsByClassName("check");
		Id="";
		ref="";
		for(var i=0, l=elements.length; i<l; i++)
		{
			if(elements[i].checked == true){Id+=elements[i].name+";";}
		}
		if(Id!="")
		{
			if(Type=="V")
			{
				if(Langue=="EN"){texte='Are you sure you want to validate?';}
				else{texte='Etes-vous sûr de vouloir valider ?';}
				if(window.confirm(texte))
				{
					var w=window.open("Valider_FinValiditeQualification.php?Type=V&Id="+Id+"&Id_Prestation="+Id_Prestation+"&Personne="+Personne+"&DateQCM="+DateQCM+"&Qualif="+Qualif,"PageValidation","status=no,menubar=no,scrollbars=yes,width=20,height=20");
				}
			}
			else
			{
				if(Langue=="EN"){texte='Are you sure you want to refuse?';}
				else{texte='Etes-vous sûr de vouloir refuser ?';}
				if(window.confirm(texte))
				{
					var w=window.open("Valider_FinValiditeQualification.php?Type=R&Id="+Id+"&Id_Prestation="+Id_Prestation+"&Personne="+Personne+"&DateQCM="+DateQCM+"&Qualif="+Qualif,"PageValidation","status=no,menubar=no,scrollbars=yes,width=20,height=20");
				}
			}
		}
	}
	
	function Excel()
	{
		var w=window.open("Excel_FinValiditeQualification.php?Prestation="+document.getElementById('prestation').value+"&Id_Personne="+document.getElementById('personne').value+"&DateQCM="+document.getElementById('dateQCM').value+"&Qualification="+document.getElementById('qualification').value,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
</script>
<?php
if($_POST)
{
	if(isset($_POST['prestation'])){$_SESSION['FiltreQualifLT_Prestation']=$_POST['prestation'];}
	if(isset($_POST['personne'])){$_SESSION['FiltreQualifLT_Personne']=$_POST['personne'];}
	if(isset($_POST['dateQCM'])){$_SESSION['FiltreQualifLT_DateQCM']=$_POST['dateQCM'];}
	if(isset($_POST['anneeQCM'])){$_SESSION['FiltreQualifLT_AnneeQCM']=$_POST['anneeQCM'];}
	if(isset($_POST['qualification'])){$_SESSION['FiltreQualifLT_Qualification']=$_POST['qualification'];}
	if(isset($_POST['evaluation'])){$_SESSION['FiltreQualifLT_Evaluation']=$_POST['evaluation'];}
}
if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Personne")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Personne ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Personne DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Personne ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Personne DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_Personne']==""){$_SESSION['TriQualifLT_Personne']="ASC";$_SESSION['TriQualifLT_General'].= "Personne ".$_SESSION['TriQualifLT_Personne'].",";}
		elseif($_SESSION['TriQualifLT_Personne']=="ASC"){$_SESSION['TriQualifLT_Personne']="DESC";$_SESSION['TriQualifLT_General'].= "Personne ".$_SESSION['TriQualifLT_Personne'].",";}
		else{$_SESSION['TriQualifLT_Personne']="";}
	}
	if($_GET['Tri']=="Metier")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Metier ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Metier DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Metier ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Metier DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_Metier']==""){$_SESSION['TriQualifLT_Metier']="ASC";$_SESSION['TriQualifLT_General'].= "Metier ".$_SESSION['TriQualifLT_Metier'].",";}
		elseif($_SESSION['TriQualifLT_Metier']=="ASC"){$_SESSION['TriQualifLT_Metier']="DESC";$_SESSION['TriQualifLT_General'].= "Metier ".$_SESSION['TriQualifLT_Metier'].",";}
		else{$_SESSION['TriQualifLT_Metier']="";}
	}
	if($_GET['Tri']=="Prestation")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Prestation ASC,Pole ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Prestation DESC,Pole DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Prestation ASC,Pole ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Prestation DESC,Pole DESC,","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_Prestation']==""){$_SESSION['TriQualifLT_Prestation']="ASC";$_SESSION['TriQualifLT_General'].= "Prestation ".$_SESSION['TriQualifLT_Prestation'].","."Pole ".$_SESSION['TriQualifLT_Prestation'].",";}
		elseif($_SESSION['TriQualifLT_Prestation']=="ASC"){$_SESSION['TriQualifLT_Prestation']="DESC";$_SESSION['TriQualifLT_General'].= "Prestation ".$_SESSION['TriQualifLT_Prestation'].","."Pole ".$_SESSION['TriQualifLT_Prestation'].",";}
		else{$_SESSION['TriQualifLT_Prestation']="";}
	}
	if($_GET['Tri']=="Qualif")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Qualif ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Qualif DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Qualif ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Qualif DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_Qualification']==""){$_SESSION['TriQualifLT_Qualification']="ASC";$_SESSION['TriQualifLT_General'].= "Qualif ".$_SESSION['TriQualifLT_Qualification'].",";}
		elseif($_SESSION['TriQualifLT_Qualification']=="ASC"){$_SESSION['TriQualifLT_Qualification']="DESC";$_SESSION['TriQualifLT_General'].= "Qualif ".$_SESSION['TriQualifLT_Qualification'].",";}
		else{$_SESSION['TriQualifLT_Qualification']="";}
	}
	if($_GET['Tri']=="Categorie")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Categorie ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Categorie DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Categorie ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Categorie DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_Categorie']==""){$_SESSION['TriQualifLT_Categorie']="ASC";$_SESSION['TriQualifLT_General'].= "Categorie ".$_SESSION['TriQualifLT_Categorie'].",";}
		elseif($_SESSION['TriQualifLT_Categorie']=="ASC"){$_SESSION['TriQualifLT_Categorie']="DESC";$_SESSION['TriQualifLT_General'].= "Categorie ".$_SESSION['TriQualifLT_Categorie'].",";}
		else{$_SESSION['TriQualifLT_Categorie']="";}
	}
	if($_GET['Tri']=="DateDebut")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Date_Debut ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Date_Debut DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Date_Debut ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Date_Debut DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_DateDebut']==""){$_SESSION['TriQualifLT_DateDebut']="ASC";$_SESSION['TriQualifLT_General'].= "Date_Debut ".$_SESSION['TriQualifLT_DateDebut'].",";}
		elseif($_SESSION['TriQualifLT_DateDebut']=="ASC"){$_SESSION['TriQualifLT_DateDebut']="DESC";$_SESSION['TriQualifLT_General'].= "Date_Debut ".$_SESSION['TriQualifLT_DateDebut'].",";}
		else{$_SESSION['TriQualifLT_DateDebut']="";}
	}
	if($_GET['Tri']=="DateQCM")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Date_QCM ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Date_QCM DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Date_QCM ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Date_QCM DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_DateQCM']==""){$_SESSION['TriQualifLT_DateQCM']="ASC";$_SESSION['TriQualifLT_General'].= "Date_QCM ".$_SESSION['TriQualifLT_DateQCM'].",";}
		elseif($_SESSION['TriQualifLT_DateQCM']=="ASC"){$_SESSION['TriQualifLT_DateQCM']="DESC";$_SESSION['TriQualifLT_General'].= "Date_QCM ".$_SESSION['TriQualifLT_DateQCM'].",";}
		else{$_SESSION['TriQualifLT_DateQCM']="";}
	}
	if($_GET['Tri']=="Evaluation")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Evaluation ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Evaluation DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Evaluation ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Evaluation DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_Evaluation']==""){$_SESSION['TriQualifLT_Evaluation']="ASC";$_SESSION['TriQualifLT_General'].= "Evaluation ".$_SESSION['TriQualifLT_Evaluation'].",";}
		elseif($_SESSION['TriQualifLT_Evaluation']=="ASC"){$_SESSION['TriQualifLT_Evaluation']="DESC";$_SESSION['TriQualifLT_General'].= "Evaluation ".$_SESSION['TriQualifLT_Evaluation'].",";}
		else{$_SESSION['TriQualifLT_Evaluation']="";}
	}
	if($_GET['Tri']=="ResultatQCM")
	{
		$_SESSION['TriQualifLT_General']= str_replace("Resultat_QCM ASC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Resultat_QCM DESC,","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Resultat_QCM ASC","",$_SESSION['TriQualifLT_General']);
		$_SESSION['TriQualifLT_General']= str_replace("Resultat_QCM DESC","",$_SESSION['TriQualifLT_General']);
		if($_SESSION['TriQualifLT_ResultatQCM']==""){$_SESSION['TriQualifLT_ResultatQCM']="ASC";$_SESSION['TriQualifLT_General'].= "Resultat_QCM ".$_SESSION['TriQualifLT_ResultatQCM'].",";}
		elseif($_SESSION['TriQualifLT_ResultatQCM']=="ASC"){$_SESSION['TriQualifLT_ResultatQCM']="DESC";$_SESSION['TriQualifLT_General'].= "Resultat_QCM ".$_SESSION['TriQualifLT_ResultatQCM'].",";}
		else{$_SESSION['TriQualifLT_ResultatQCM']="";}
	}
}

$dateQCM=$_SESSION['FiltreQualifLT_DateQCM'];
$Prestation=$_SESSION['FiltreQualifLT_Prestation'];
$date_12mois=date("Y-m-d",strtotime(date("Y-m-d")." - 12 month"));
$date_6mois=date("Y-m-d",strtotime(date("Y-m-d")." - 6 month"));

if($dateQCM==""){$requeteDateQCM="";}
elseif($dateQCM=="1"){$requeteDateQCM="AND TAB.Date_QCM>='".$date_6mois."' AND TAB.Date_QCM>'0001-01-01' ";}//Moins de 6 moins
elseif($dateQCM=="2"){$requeteDateQCM="AND TAB.Date_QCM<='".$date_6mois."' AND TAB.Date_QCM>'".$date_12mois."' ";}//Plus de 6 moins
elseif($dateQCM=="3"){$requeteDateQCM="AND TAB.Date_QCM<='".$date_12mois."' AND TAB.Date_QCM>'0001-01-01' ";}//Plus de 1 an
elseif($dateQCM=="4"){$requeteDateQCM="AND TAB.Date_QCM<='0001-01-01' ";}//Pas de date

if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS))
{
    $requeteDroits="
        AND new_competences_prestation.Id_Plateforme IN
			(
				SELECT
					Id_Plateforme
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
					AND Id_Personne=".$IdPersonneConnectee."
			) ";
}
else
{
    $requeteDroits="
        AND CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN
        (
            SELECT
                CONCAT(Id_Prestation,'_',Id_Pole)
            FROM
                new_competences_personne_poste_prestation
            WHERE
                Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
                AND Id_Personne=".$IdPersonneConnectee."
        ) ";
}

if($_GET){
	$Page=$_GET['Page'];
	$_SESSION['FORM_FINVALIDITE_Page']=$Page;
}
else{
	if(isset($_POST['BtnRechercher'])){
		$Page=0;
		$_SESSION['FORM_FINVALIDITE_Page']=0;
	}
	else{
		$Page=$_SESSION['FORM_FINVALIDITE_Page'];
	}
	
}

?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" id="formulaire" method="POST" action="WorkflowDesLT.php">
<table style="width:100%; border-spacing:0; align:center;">
	<input type="hidden" id="Page" name="Page" value="<?php echo $_SESSION['FORM_FINVALIDITE_Page']; ?>">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ff8b41;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des L & T";}else{echo "L / T management";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr> 
	<tr><td height="4"></td></tr>
	<tr><td>
		<table class="TableCompetences" style="width:100%; border-spacing:0;">
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation ou Pôle";}else{echo "Activity or Pole";}?> : </td>
					<td width="20%">
						<select id="prestation" name="prestation" style="width:200px;" onchange="submit()">
							<option value="0"></option>
							<?php
							$requetePresta="
                                SELECT DISTINCT
                                    TAB.Id_Prestation,
                                    TAB.Id_Pole,
									(SELECT Libelle FROM new_competences_prestation WHERE Id=TAB.Id_Prestation) AS Prestation,
									(SELECT  IF(Libelle<>'',CONCAT(' - ',Libelle),'') FROM new_competences_pole WHERE Id=TAB.Id_Pole) AS Pole
                                FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                    (
                                        SELECT
                                            new_competences_relation.Id,
                                            new_competences_relation.Id_Personne,
                                            new_competences_relation.Evaluation,
                                            new_competences_relation.Id_Qualification_Parrainage,
                                            new_competences_relation.Date_QCM,
                                            new_competences_relation.Date_Debut,
                                            new_competences_personne_prestation.Id_Prestation,
                                            new_competences_personne_prestation.Id_Pole,(@row_number:=@row_number + 1) AS rnk
                                        FROM new_competences_relation 
										RIGHT JOIN new_competences_personne_prestation ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
										LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
                                        WHERE new_competences_relation.Type='Qualification' 
                                            ".$requeteDroits."
								            AND new_competences_relation.Suppr=0
											AND (new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' OR new_competences_personne_prestation.Date_Fin<='0001-01-01')
											AND new_competences_relation.Evaluation<>''
                                        ORDER BY
                                            new_competences_relation.Date_Debut DESC
                                    ) AS Tab_Qualif
                                    GROUP BY
                                        Tab_Qualif.Id_Personne,
                                        Tab_Qualif.Id_Prestation,
                                        Tab_Qualif.Id_Qualification_Parrainage
                                ) AS TAB
                                WHERE
                                    TAB.Evaluation IN ('L','T')
									".$requeteDateQCM." 
									ORDER BY
										Prestation,
										Pole";
							$resultPresta=mysqli_query($bdd,$requetePresta);
							$nbPresta=mysqli_num_rows($resultPresta);
							if($nbPresta>0)
							{
								while($rowPresta=mysqli_fetch_array($resultPresta))
								{
									$selected="";
									if($Prestation<>"")
										{if($Prestation==$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']){$selected="selected";}}
										echo "<option value='".$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']."' ".$selected.">".stripslashes(substr($rowPresta['Prestation'],0,7).$rowPresta['Pole'])."</option>\n";
								}
							}
							?>
						</select>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
					<td width="20%">
						<input name="personne" id="personne" value="<?php $personne=$_SESSION['FiltreQualifLT_Personne']; echo $personne; ?>"/>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "Date MCQ";}?> : </td>
					<td width="20%">
						<select id="dateQCM" name="dateQCM" style="width:100px;" onchange="submit()">
							<option value=""></option>
							<option value="1" <?php if($dateQCM=="1"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Moins de 6 mois";}else{echo "Less than 6 months";}?></option>
							<option value="2" <?php if($dateQCM=="2"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Plus de 6 mois";}else{echo "More than 6 months";}?></option>
							<option value="3" <?php if($dateQCM=="3"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Plus de 1 an";}else{echo "More than 1 year";}?></option>
							<option value="4" <?php if($dateQCM=="4"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Pas de date";}else{echo "No date";}?></option>
						</select>
					</td>
					<td width="40%" align="left">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					</td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?> : </td>
					<td width="20%">
						<?php $qualification=$_SESSION['FiltreQualifLT_Qualification']; ?>
						<select id="qualification" name="qualification" style="width:300px;" onchange="submit()">
							<option value="0"></option>
							<?php
							$requeteQualifications="
                                SELECT DISTINCT
                                    (SELECT Libelle FROM new_competences_qualification WHERE Id=TAB.Id_Qualification_Parrainage) AS Qualif,
                                    TAB.Id_Qualification_Parrainage,
									(SELECT
									(
										SELECT
											Libelle
										FROM
											new_competences_categorie_qualification
										WHERE
											new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification
									)
									FROM new_competences_qualification WHERE Id=TAB.Id_Qualification_Parrainage ) AS Categorie
                                FROM
                                (
                                    SELECT
                                        *
                                    FROM
                                    (
                                        SELECT
                                            new_competences_relation.Id,
                                            new_competences_relation.Id_Personne,
                                            new_competences_relation.Evaluation,
                                            new_competences_relation.Id_Qualification_Parrainage,
                                            new_competences_relation.Date_QCM,
                                            new_competences_relation.Date_Debut,
                                            new_competences_personne_prestation.Id_Prestation,
                                            new_competences_personne_prestation.Id_Pole,(@row_number:=@row_number + 1) AS rnk
                                        FROM new_competences_relation 
										RIGHT JOIN new_competences_personne_prestation ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
										LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
                                        WHERE new_competences_relation.Type='Qualification' 
                                            ".$requeteDroits."
								            AND new_competences_relation.Suppr=0
											AND new_competences_relation.Evaluation<>''
											AND (new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' OR new_competences_personne_prestation.Date_Fin<='0001-01-01')
                                        ORDER BY
                                            new_competences_relation.Date_Debut DESC
                                    ) AS Tab_Qualif
                                    GROUP BY
                                        Tab_Qualif.Id_Personne,
                                        Tab_Qualif.Id_Prestation,
                                        Tab_Qualif.Id_Qualification_Parrainage
                                ) AS TAB
                                WHERE
                                    TAB.Evaluation IN ('L','T')
                                    ".$requeteDateQCM." 
									AND (SELECT Libelle FROM new_competences_qualification WHERE Id=TAB.Id_Qualification_Parrainage)<>''
								ORDER BY Qualif ";
							$resultQualif=mysqli_query($bdd,$requeteQualifications);
							$nbQualifs=mysqli_num_rows($resultQualif);
							if($nbQualifs>0)
							{
								while($rowQualif=mysqli_fetch_array($resultQualif))
								{
									$selected="";
									if($qualification<>"")
										{if($qualification==$rowQualif['Id_Qualification_Parrainage']){$selected="selected";}}
									echo "<option value='".$rowQualif['Id_Qualification_Parrainage']."' ".$selected.">".stripslashes($rowQualif['Qualif'])." (".stripslashes($rowQualif['Categorie']).")</option>\n";
								}
							}
							?>
						</select>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Evaluation";}else{echo "Evaluation";}?> : </td>
					<td width="20%">
						<?php $evaluation=$_SESSION['FiltreQualifLT_Evaluation']; ?>
						<select id="evaluation" name="evaluation" style="width:100px;" onchange="submit()">
							<option value="" <?php if($evaluation==""){echo "selected";}?>></option>
							<option value="L" <?php if($evaluation=="L"){echo "selected";}?>>L</option>
							<option value="T" <?php if($evaluation=="T"){echo "selected";}?>>T</option>
						</select>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Année QCM";}else{echo "Year MCQ";}?> : </td>
					<td width="20%">
						<input onKeyUp="nombre(this)" style="width:30px" name="anneeQCM" id="anneeQCM" value="<?php $anneeQCM=$_SESSION['FiltreQualifLT_AnneeQCM']; echo $anneeQCM; ?>"/>
					</td>
					<td width="5%">
						<!--&nbsp;<a style="text-decoration:none;" href="javascript:Excel();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;-->
					</td>
				</tr>
			</table>
	</td></tr>
	<tr><td height="8"></td></tr>
		<tr>
		<td align="center" style="font-size:14px;">
		<?php
		$requeteQualificationFiltre="";
		if($qualification<>"0"){$requeteQualificationFiltre="AND new_competences_relation.Id_Qualification_Parrainage=".$qualification." ";}
		
		$requeteQualifications="
             SELECT DISTINCT
				(SELECT Libelle FROM new_competences_qualification WHERE Id=TAB.Id_Qualification_Parrainage) AS Qualif,
				TAB.Id_Qualification_Parrainage,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=TAB.Id_Personne) AS Personne,
				TAB.Evaluation,
				TAB.Date_QCM,
				TAB.Date_Debut,
				TAB.Id_Pole,
				(SELECT Libelle FROM new_competences_prestation WHERE Id=TAB.Id_Prestation) AS Prestation,
				(SELECT  IF(Libelle<>'',CONCAT(' - ',Libelle),'') FROM new_competences_pole WHERE Id=TAB.Id_Pole) AS Pole,
				(SELECT (SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=TAB.Id_Personne ORDER BY Futur DESC LIMIT 1) AS Metier,
				TAB.Id_Personne,
				TAB.Id_Prestation,
				TAB.Resultat_QCM,
				TAB.Id,
				 (SELECT
					(
						SELECT
							Libelle
						FROM
							new_competences_categorie_qualification
						WHERE
							new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification
					)
					FROM new_competences_qualification WHERE Id=TAB.Id_Qualification_Parrainage ) AS Categorie
			FROM
			(
				SELECT
					*
				FROM
				(
					SELECT
						new_competences_relation.Id,
						new_competences_relation.Id_Personne,
						new_competences_relation.Evaluation,
						new_competences_relation.Resultat_QCM,
						new_competences_relation.Id_Qualification_Parrainage,
						new_competences_relation.Date_QCM,
						new_competences_relation.Date_Debut,
						new_competences_personne_prestation.Id_Prestation,
						new_competences_personne_prestation.Id_Pole,(@row_number:=@row_number + 1) AS rnk
					FROM new_competences_relation 
					RIGHT JOIN new_competences_personne_prestation ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
					LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
					WHERE new_competences_relation.Type='Qualification' 
						".$requeteDroits."
						".$requeteQualificationFiltre."
						AND new_competences_relation.Suppr=0
						AND new_competences_relation.Evaluation<>''
						AND (new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' OR new_competences_personne_prestation.Date_Fin<='0001-01-01')
					ORDER BY
						new_competences_relation.Date_QCM DESC
				) AS Tab_Qualif
				GROUP BY
					Tab_Qualif.Id_Personne,
					Tab_Qualif.Id_Qualification_Parrainage
			) AS TAB ";
		$requeteQualifications2="WHERE TAB.Evaluation IN ('L','T')
				AND(
				(
					TAB.Evaluation='T'
					AND 
					(
						SELECT
							COUNT(form_besoin.Id)
						FROM
							form_besoin
						WHERE
							form_besoin.Suppr=0
							AND form_besoin.Id_Personne=TAB.Id_Personne
							AND form_besoin.Valide >=0 
							AND form_besoin.Traite<3
							AND form_besoin.Id_Formation IN
							(
								SELECT
									form_formation_qualification.Id_Formation
								FROM
									form_formation_qualification
								WHERE
									form_formation_qualification.Suppr=0
									AND form_formation_qualification.Id_Qualification=TAB.Id_Qualification_Parrainage
							)
					)=0
				)
				OR 
				TAB.Evaluation='L')
		
				".$requeteDateQCM." ";
		if($personne<>""){$requeteQualifications2.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=TAB.Id_Personne) LIKE '%".$personne."%' ";}
		if($anneeQCM<>""){$requeteQualifications2.="AND YEAR(Date_QCM) = '".$anneeQCM."' ";}
		if($Prestation<>"0"){$requeteQualifications2.="AND CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole)='".$Prestation."' ";}
		if($evaluation<>""){$requeteQualifications2.="AND TAB.Evaluation='".$evaluation."' ";}
		$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2);
		$nbQualifs=mysqli_num_rows($resultQualifications);

		$val=50;
		
		$nombreDePages=ceil($nbQualifs/$val);
		$_SESSION['FORM_FINVALIDITE_Page']=$Page;
		
		if($_SESSION['TriQualifLT_General']<>""){$requeteQualifications2.=" ORDER BY ".substr($_SESSION['TriQualifLT_General'],0,-1);}
		$req3=" LIMIT ".($_SESSION['FORM_FINVALIDITE_Page']*$val).",".$val;

		$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2.$req3);
		$nbQualifs=mysqli_num_rows($resultQualifications);

		if($_SESSION['FORM_FINVALIDITE_Page']>1){echo "<b> <a style='color:#00599f;' href='WorkflowDesLT.php?Page=0'><<</a> </b>";}
		$valeurDepart=1;
		if($_SESSION['FORM_FINVALIDITE_Page']<=5){$valeurDepart=1;}
		elseif($_SESSION['FORM_FINVALIDITE_Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
		else{$valeurDepart=$_SESSION['FORM_FINVALIDITE_Page']-5;}
		for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
		{
			if($i<=$nombreDePages)
			{
				if($i==($_SESSION['FORM_FINVALIDITE_Page']+1)){echo "<b> [ ".$i." ] </b>";}	
				else{echo "<b> <a style='color:#00599f;' href='WorkflowDesLT.php?Page=".($i-1)."'>".$i."</a> </b>";}
			}
		}
		if($_SESSION['FORM_FINVALIDITE_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='WorkflowDesLT.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
		
		if(isset($_POST['TransformerT'])){
			while($row=mysqli_fetch_array($resultQualifications)){
				if (isset($_POST['checkL_'.$row['Id'].''])){
					$requeteUpdate="UPDATE new_competences_relation SET 
							Evaluation='T'
							WHERE Id=".$row['Id']." ";
					$resultat=mysqli_query($bdd,$requeteUpdate);
				}
			}
			$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2.$req3);
		}
		if(isset($_POST['GenererB'])){
			while($row=mysqli_fetch_array($resultQualifications)){
				if (isset($_POST['form_'.$row['Id']])){
					if($_POST['form_'.$row['Id']]<>0){
						if(Get_NbBesoinExistant($row['Id_Personne'], $_POST['form_'.$row['Id']])==0){
							$requeteUpdate="UPDATE new_competences_relation SET 
									Visible=1
									WHERE Id=".$row['Id']." ";
							$resultat=mysqli_query($bdd,$requeteUpdate);
					
							$ReqInsertBesoin="
								INSERT INTO
									form_besoin
									(
										Id_Demandeur,
										EmisParAF,
										Id_Prestation,
										Id_Pole,
										Id_Formation,
										Id_Personne,
										Date_Demande,
										Motif,
										Commentaire,
										Valide,
										Id_Valideur,
										Id_Personne_MAJ,
										Date_MAJ
									)
								VALUES
									(".
										$IdPersonneConnectee.",".
										DroitsFormationPlateforme($TableauIdPostesAF_RF).",".
										$row['Id_Prestation'].",".
										$row['Id_Pole'].",".
										$_POST['form_'.$row['Id']].",".
										$row['Id_Personne'].",'".date('Y-m-d')."',
										'Nouveau',
										'Génération T en B',
										1,
										".$IdPersonneConnectee.",".
										$IdPersonneConnectee.",".
										"'".date('Y-m-d')."'
									)";
							$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
							$ID_BESOIN=mysqli_insert_id($bdd);
							
							//Qualification liées à la formation
							$ReqQualifFormation="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$_POST['form_'.$row['Id']]." AND Suppr=0 AND Masquer=0 ";
							$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
							$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
							
							//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
							if($NbQualifFormation>0)
							{
								mysqli_data_seek($ResultQualifFormation,0);
								$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
								while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
								{
									$ReqInsertBesoinGPEC.="(";
									$ReqInsertBesoinGPEC.=$row['Id_Personne'];
									$ReqInsertBesoinGPEC.=",'Qualification'";
									$ReqInsertBesoinGPEC.=",".$RowQualifFormation['Id_Qualification'];
									$ReqInsertBesoinGPEC.=",'B'";
									$ReqInsertBesoinGPEC.=",0";
									$ReqInsertBesoinGPEC.=",".$ID_BESOIN;
									$ReqInsertBesoinGPEC.="),";
								}
								$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
								$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
							}
						}
					}
				}
			}
			$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2.$req3);
		}
		if(isset($_POST['TransformeTGenererB'])){
			while($row=mysqli_fetch_array($resultQualifications)){
				if (isset($_POST['form_'.$row['Id']])){
					if($_POST['form_'.$row['Id']]<>0){
						if(Get_NbBesoinExistant($row['Id_Personne'], $_POST['form_'.$row['Id']])==0){
							$requeteUpdate="UPDATE new_competences_relation SET 
									Evaluation='T',
									Visible=1
									WHERE Id=".$row['Id']." ";
							$resultat=mysqli_query($bdd,$requeteUpdate);
					
							$ReqInsertBesoin="
								INSERT INTO
									form_besoin
									(
										Id_Demandeur,
										EmisParAF,
										Id_Prestation,
										Id_Pole,
										Id_Formation,
										Id_Personne,
										Date_Demande,
										Motif,
										Commentaire,
										Valide,
										Id_Valideur,
										Id_Personne_MAJ,
										Date_MAJ
									)
								VALUES
									(".
										$IdPersonneConnectee.",".
										DroitsFormationPlateforme($TableauIdPostesAF_RF).",".
										$row['Id_Prestation'].",".
										$row['Id_Pole'].",".
										$_POST['form_'.$row['Id']].",".
										$row['Id_Personne'].",'".date('Y-m-d')."',
										'Nouveau',
										'Génération T en B',
										1,
										".$IdPersonneConnectee.",".
										$IdPersonneConnectee.",".
										"'".date('Y-m-d')."'
									)";
							$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
							$ID_BESOIN=mysqli_insert_id($bdd);
							
							//Qualification liées à la formation
							$ReqQualifFormation="SELECT Id_Qualification FROM form_formation_qualification WHERE Id_Formation=".$_POST['form_'.$row['Id']]." AND Suppr=0 AND Masquer=0 ";
							$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
							$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
							
							//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
							if($NbQualifFormation>0)
							{
								mysqli_data_seek($ResultQualifFormation,0);
								$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
								while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
								{
									$ReqInsertBesoinGPEC.="(";
									$ReqInsertBesoinGPEC.=$row['Id_Personne'];
									$ReqInsertBesoinGPEC.=",'Qualification'";
									$ReqInsertBesoinGPEC.=",".$RowQualifFormation['Id_Qualification'];
									$ReqInsertBesoinGPEC.=",'B'";
									$ReqInsertBesoinGPEC.=",0";
									$ReqInsertBesoinGPEC.=",".$ID_BESOIN;
									$ReqInsertBesoinGPEC.="),";
								}
								$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
								$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
							}
						}
					}
				}
			}
			$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2.$req3);
		}
		?>
		</td>
	</tr>
	<tr><td>
		<div style="width:100%;height:400px;overflow:auto;">
		<table style="width:100%; border-spacing:0; align:cleft;" class="GeneralInfo">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=Personne">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Personne";}
    					else{echo "Person";}
    					if($_SESSION['TriQualifLT_Personne']=="DESC"){echo "&uarr;";}
                        elseif($_SESSION['TriQualifLT_Personne']=="ASC"){echo "&darr;";}
                        ?>
                    </a>
                </td>
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=Metier">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Métier";}
    					else{echo "Job";}
    					if($_SESSION['TriQualifLT_Metier']=="DESC"){echo "&uarr;";}
                        elseif($_SESSION['TriQualifLT_Metier']=="ASC"){echo "&darr;";}
                        ?>
                    </a>
                </td>
				<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=Prestation">
						&nbsp;
						<?php 
						if($LangueAffichage=="FR"){echo "Prestation";}
						else{echo "Activity";}
						if($_SESSION['TriQualifLT_Prestation']=="DESC"){echo "&uarr;";}
						elseif($_SESSION['TriQualifLT_Prestation']=="ASC"){echo "&darr;";}
						?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=Qualif">
						&nbsp;
						<?php
						if($LangueAffichage=="FR"){echo "Qualification";}
						else{echo "Qualification";}
						if($_SESSION['TriQualifLT_Qualification']=="DESC"){echo "&uarr;";}
						elseif($_SESSION['TriQualifLT_Qualification']=="ASC"){echo "&darr;";}
						?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="14%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=Categorie">
						&nbsp;
						<?php
						if($LangueAffichage=="FR"){echo "Catégorie";}
						else{echo "Category";}
						if($_SESSION['TriQualifLT_Categorie']=="DESC"){echo "&uarr;";}
						elseif($_SESSION['TriQualifLT_Categorie']=="ASC"){echo "&darr;";}
						?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=DateDebut">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Date début";}
    					else{echo "Start date";}
    					if($_SESSION['TriQualifLT_DateDebut']=="DESC"){echo "&uarr;";}
    					elseif($_SESSION['TriQualifLT_DateDebut']=="ASC"){echo "&darr;";}
    					?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=DateQCM">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Date QCM";}
    					else{echo "MCQ date";}
    					if($_SESSION['TriQualifLT_DateQCM']=="DESC"){echo "&uarr;";}
    					elseif($_SESSION['TriQualifLT_DateQCM']=="ASC"){echo "&darr;";}
    					?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=ResultatQCM">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Résultat QCM";}
    					else{echo "MCQ Result";}
    					if($_SESSION['TriQualifLT_ResultatQCM']=="DESC"){echo "&uarr;";}
    					elseif($_SESSION['TriQualifLT_ResultatQCM']=="ASC"){echo "&darr;";}
    					?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesLT.php?Tri=Evaluation">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Evaluation";}
    					else{echo "Evaluation";}
    					if($_SESSION['TriQualifLT_Evaluation']=="DESC"){echo "&uarr;";}
    					elseif($_SESSION['TriQualifLT_Evaluation']=="ASC"){echo "&darr;";}
    					?>
					</a>
				</td>
				<?php if($evaluation=="L" || $evaluation==""){ ?>
				<td class="EnTeteTableauCompetences" align="center" width="5%">
					<input type="submit" class="Bouton" style="cursor:pointer;" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="TransformerT" value="<?php if($_SESSION["Langue"]=="FR"){echo "Transformer\nen T";}else{echo "Transform\ninto T";} ?>">
				</td>
				<?php } ?>
				<?php if($evaluation=="L"){ ?>
				<td class="EnTeteTableauCompetences" style="text-align:center;" width="5%" colspan="2">
					<input type="submit" class="Bouton" style="cursor:pointer;" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="TransformeTGenererB" value="<?php if($_SESSION["Langue"]=="FR"){echo "Transformer en T\nGénerer un B";}else{echo "Transform into T\nGenerate a B";} ?>">
				</td>
				<?php } ?>
				<?php if($evaluation=="T" || $evaluation==""){ ?>
				<td class="EnTeteTableauCompetences" style="text-align:center;" width="5%" colspan="2">
					<input type="submit" class="Bouton" style="cursor:pointer;" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="GenererB" value="<?php if($_SESSION["Langue"]=="FR"){echo "Génerer un B";}else{echo "Generate a B";} ?>">
				</td>
				<?php } ?>
				
			</tr>
			<?php
				if ($nbQualifs>0)
				{
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultQualifications))
					{
						$couleur2="black";
						if($row['Date_QCM']<=date('Y-m-d')){$couleur2="red";}
						elseif($row['Date_QCM']<=$date_2mois){$couleur2="orange";}
						$Pole="";
						if($row['Pole']<>""){$Pole=" - ".stripslashes($row['Pole']);}
			?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td style="border-bottom:1px dotted #003333;" valign="middle">&nbsp;<?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a>" ;?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Metier']; ?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo AfficheCodePrestation(stripslashes($row['Prestation'])).$Pole;?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Qualif']; ?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Categorie']; ?></td>
							<td style="border-bottom:1px dotted #003333;" align="center" valign="middle"><b><?php echo AfficheDateJJ_MM_AAAA($row['Date_Debut']);?></b></td>
							<td style="border-bottom:1px dotted #003333;color:<?php echo $couleur2;?>;" align="center" valign="middle"><b><?php echo AfficheDateJJ_MM_AAAA($row['Date_QCM']);?></b></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle" align="center"><?php echo $row['Resultat_QCM']; ?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle" align="center"><?php echo $row['Evaluation']; ?></td>
							<?php 
								//Rechercher les formations à passer
								$req="SELECT DISTINCT form_formation.Id, 
										form_formation.Reference,
										form_formation.Recyclage,
										(SELECT IF(form_formation.Recyclage=1,LibelleRecyclage,Libelle)
											FROM form_formation_langue_infos
											WHERE Id_Formation=form_formation.Id
											AND Id_Langue=
												(SELECT Id_Langue 
												FROM form_formation_plateforme_parametres 
												WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$row['Id_Prestation'].")
												AND Id_Formation=form_formation.Id
												AND Suppr=0 
												LIMIT 1)
											AND Suppr=0) AS Libelle,
										(SELECT (SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme)
											FROM form_formation_plateforme_parametres 
											WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$row['Id_Prestation'].")
											AND Id_Formation=form_formation.Id
											AND Suppr=0 
											LIMIT 1) AS Organisme
										FROM form_formation 
										LEFT JOIN form_formation_qualification 
										ON form_formation.Id=form_formation_qualification.Id_Formation 
										WHERE (form_formation.Id_Plateforme=0 OR form_formation.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$row['Id_Prestation'].")) 
										AND form_formation.Suppr=0 
										AND form_formation.Id_TypeFormation<>1
										AND form_formation_qualification.Suppr=0 
										AND form_formation_qualification.Id_Qualification=".$row['Id_Qualification_Parrainage']." 
									ORDER BY Libelle ";
								$result=mysqli_query($bdd,$req);
								$nbResult=mysqli_num_rows($result);
							if($evaluation=="L" || $evaluation==""){ ?>
							<td style="border-bottom:1px dotted #003333;" valign="middle" align="center">
								<?php 
								if($row['Evaluation']=="L"){ 
									echo "<input class='checkL' type='checkbox' name='checkL_".$row['Id']."' value=''>";
								} ?>
							</td>
							<?php } ?>
							<?php if($evaluation=="T" || $evaluation==""){ ?>
							<td style="border-bottom:1px dotted #003333;" valign="middle" align="center">
								<?php 
								if($row['Evaluation']=="T"){
									if($nbResult>0){
								?>
									<select class="form" id="form_<?php echo $row['Id']; ?>" name="form_<?php echo $row['Id']; ?>" style="width:200px;" >
										<option value="0"></option>
										<?php 
											while($rowForm=mysqli_fetch_array($result)){
												$organisme="";
												if($rowForm['Organisme']<>""){
													$organisme=" (".$rowForm['Organisme'].") ";
												}
												echo "<option value='".$rowForm['Id']."'>".stripslashes($rowForm['Libelle']).$organisme."</option>";
											}
										?>
									</select>
									
								<?php
									}
									else{
										if($_SESSION["Langue"]=="FR"){echo "Pas de formations disponibles";}else{echo "No training available";}
									}
								} ?>
							</td>
							<?php } ?>
							<?php if($evaluation=="L"){ ?>
							<td style="border-bottom:1px dotted #003333;" valign="middle" align="center">
								<?php if($row['Evaluation']=="L"){
									if($nbResult>0){
								?>
									<select class="form" id="form_<?php echo $row['Id']; ?>" name="form_<?php echo $row['Id']; ?>" style="width:200px;" >
										<option value="0"></option>
										<?php 
											while($rowForm=mysqli_fetch_array($result)){
												$organisme="";
												if($rowForm['Organisme']<>""){
													$organisme=" (".$rowForm['Organisme'].") ";
												}
												echo "<option value='".$rowForm['Id']."'>".stripslashes($rowForm['Libelle']).$organisme."</option>";
											}
										?>
									</select>
									
								<?php
									}
									else{
										if($_SESSION["Langue"]=="FR"){echo "Pas de formations disponibles";}else{echo "No training available";}
									}
								} ?>
							</td>
							<?php } ?>
							<td>
								<?php 
									if($nbResult>1){
										if($_SESSION["Langue"]=="FR"){
											echo "<img width='15px' src='../../Images/attention.png' border='0' alt='Plusieurs formations disponibles' title='Plusieurs formations disponibles'/>";
										}
										else{
											echo "<img width='15px' src='../../Images/attention.png' border='0' alt='Several trainings available' title='Several trainings available'/>";
										}
									}
								?>
							</td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#b1daeb";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
		</div>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>