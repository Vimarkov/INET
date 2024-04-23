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
	
	function OuvreFenetreValidation(Type,Langue,Id_Prestation,Personne,Caduque,Qualif)
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
					var w=window.open("Valider_FinValiditeQualification.php?Type=V&Id="+Id+"&Id_Prestation="+Id_Prestation+"&Personne="+Personne+"&Caduque="+Caduque+"&Qualif="+Qualif,"PageValidation","status=no,menubar=no,scrollbars=yes,width=20,height=20");
				}
			}
			else
			{
				if(Langue=="EN"){texte='Are you sure you want to refuse?';}
				else{texte='Etes-vous sûr de vouloir refuser ?';}
				if(window.confirm(texte))
				{
					var w=window.open("Valider_FinValiditeQualification.php?Type=R&Id="+Id+"&Id_Prestation="+Id_Prestation+"&Personne="+Personne+"&Caduque="+Caduque+"&Qualif="+Qualif,"PageValidation","status=no,menubar=no,scrollbars=yes,width=20,height=20");
				}
			}
		}
	}
	
	function Excel()
	{
		var w=window.open("Excel_FinValiditeQualification.php?Prestation="+document.getElementById('prestation').value+"&Id_Personne="+document.getElementById('personne').value+"&Caduque="+document.getElementById('caduque').value+"&Qualification="+document.getElementById('qualification').value,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
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
	if(isset($_POST['prestation'])){$_SESSION['FiltreFinQualif_Prestation']=$_POST['prestation'];}
	if(isset($_POST['personne'])){$_SESSION['FiltreFinQualif_Personne']=$_POST['personne'];}
	if(isset($_POST['caduque'])){$_SESSION['FiltreFinQualif_Caduque']=$_POST['caduque'];}
	if(isset($_POST['qualification'])){$_SESSION['FiltreFinQualif_Qualification']=$_POST['qualification'];}
	if(isset($_POST['etat'])){$_SESSION['FiltreFinQualif_Etat']=$_POST['etat'];}
}
if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Personne")
	{
		$_SESSION['TriFinQualif_General']= str_replace("Personne ASC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Personne DESC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Personne ASC","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Personne DESC","",$_SESSION['TriFinQualif_General']);
		if($_SESSION['TriFinQualif_Personne']==""){$_SESSION['TriFinQualif_Personne']="ASC";$_SESSION['TriFinQualif_General'].= "Personne ".$_SESSION['TriFinQualif_Personne'].",";}
		elseif($_SESSION['TriFinQualif_Personne']=="ASC"){$_SESSION['TriFinQualif_Personne']="DESC";$_SESSION['TriFinQualif_General'].= "Personne ".$_SESSION['TriFinQualif_Personne'].",";}
		else{$_SESSION['TriFinQualif_Personne']="";}
	}
	if($_GET['Tri']=="Prestation")
	{
		$_SESSION['TriFinQualif_General']= str_replace("Prestation ASC,Pole ASC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Prestation DESC,Pole DESC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Prestation ASC,Pole ASC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Prestation DESC,Pole DESC,","",$_SESSION['TriFinQualif_General']);
		if($_SESSION['TriFinQualif_Prestation']==""){$_SESSION['TriFinQualif_Prestation']="ASC";$_SESSION['TriFinQualif_General'].= "Prestation ".$_SESSION['TriFinQualif_Prestation'].","."Pole ".$_SESSION['TriFinQualif_Prestation'].",";}
		elseif($_SESSION['TriFinQualif_Prestation']=="ASC"){$_SESSION['TriFinQualif_Prestation']="DESC";$_SESSION['TriFinQualif_General'].= "Prestation ".$_SESSION['TriFinQualif_Prestation'].","."Pole ".$_SESSION['TriFinQualif_Prestation'].",";}
		else{$_SESSION['TriFinQualif_Prestation']="";}
	}
	if($_GET['Tri']=="Qualif")
	{
		$_SESSION['TriFinQualif_General']= str_replace("Qualif ASC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Qualif DESC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Qualif ASC","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Qualif DESC","",$_SESSION['TriFinQualif_General']);
		if($_SESSION['TriFinQualif_Qualification']==""){$_SESSION['TriFinQualif_Qualification']="ASC";$_SESSION['TriFinQualif_General'].= "Qualif ".$_SESSION['TriFinQualif_Qualification'].",";}
		elseif($_SESSION['TriFinQualif_Qualification']=="ASC"){$_SESSION['TriFinQualif_Qualification']="DESC";$_SESSION['TriFinQualif_General'].= "Qualif ".$_SESSION['TriFinQualif_Qualification'].",";}
		else{$_SESSION['TriFinQualif_Qualification']="";}
	}
	if($_GET['Tri']=="Categorie")
	{
		$_SESSION['TriFinQualif_General']= str_replace("Categorie ASC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Categorie DESC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Categorie ASC","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Categorie DESC","",$_SESSION['TriFinQualif_General']);
		if($_SESSION['TriFinQualif_Categorie']==""){$_SESSION['TriFinQualif_Categorie']="ASC";$_SESSION['TriFinQualif_General'].= "Categorie ".$_SESSION['TriFinQualif_Categorie'].",";}
		elseif($_SESSION['TriFinQualif_Categorie']=="ASC"){$_SESSION['TriFinQualif_Categorie']="DESC";$_SESSION['TriFinQualif_General'].= "Categorie ".$_SESSION['TriFinQualif_Categorie'].",";}
		else{$_SESSION['TriFinQualif_Categorie']="";}
	}
	if($_GET['Tri']=="DateFin")
	{
		$_SESSION['TriFinQualif_General']= str_replace("Date_Fin ASC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Date_Fin DESC,","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Date_Fin ASC","",$_SESSION['TriFinQualif_General']);
		$_SESSION['TriFinQualif_General']= str_replace("Date_Fin DESC","",$_SESSION['TriFinQualif_General']);
		if($_SESSION['TriFinQualif_DateFin']==""){$_SESSION['TriFinQualif_DateFin']="ASC";$_SESSION['TriFinQualif_General'].= "Date_Fin ".$_SESSION['TriFinQualif_DateFin'].",";}
		elseif($_SESSION['TriFinQualif_DateFin']=="ASC"){$_SESSION['TriFinQualif_DateFin']="DESC";$_SESSION['TriFinQualif_General'].= "Date_Fin ".$_SESSION['TriFinQualif_DateFin'].",";}
		else{$_SESSION['TriFinQualif_DateFin']="";}
	}
}

$caduque=$_SESSION['FiltreFinQualif_Caduque'];
$Prestation=$_SESSION['FiltreFinQualif_Prestation'];
$date_4mois=date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"));
$date_2mois=date("Y-m-d",strtotime(date("Y-m-d")." + 2 month"));
$date_moins_6mois=date("Y-m-d",strtotime(date("Y-m-d")." - 6 month"));

if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS))
{
    $requeteDroits="
        AND new_competences_personne_prestation.Id_Prestation IN
        (
            SELECT
                Id
            FROM
                new_competences_prestation
            WHERE
                Id_Plateforme IN
                (
                    SELECT
                        Id_Plateforme
                    FROM
                        new_competences_personne_poste_plateforme
                    WHERE
                        Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS).")
                        AND Id_Personne=".$IdPersonneConnectee."
                )
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
if($caduque=="0" || $caduque=="" || $caduque=="4"){$requeteCaduque="AND TAB.Date_Fin<='".$date_4mois."' ";}
elseif($caduque=="2"){$requeteCaduque="AND TAB.Date_Fin<='".$date_2mois."' ";}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" id="formulaire" method="POST" action="Liste_FinValiditeQualification.php">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#c28bd3;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Fin de validité des qualifications";}else{echo "EN of validity of qualifications";}
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
							$requeteQualifications="
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
                                            new_competences_relation.Date_Fin,new_competences_relation.Date_Debut,
                                            new_competences_personne_prestation.Id_Prestation,
                                            new_competences_personne_prestation.Id_Pole,(@row_number:=@row_number + 1) AS rnk
                                        FROM
                                            new_competences_relation
                                        RIGHT JOIN new_competences_personne_prestation
                                            ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
										LEFT JOIN new_competences_qualification
											ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
                                        WHERE
                                        (
                                            new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
                                            OR new_competences_personne_prestation.Date_Fin<='0001-01-01'
                                        ) "
                                            .$requeteDroits."
                                            AND new_competences_relation.Type='Qualification' 
            								AND new_competences_relation.Suppr=0 
            								AND new_competences_qualification.Duree_Validite>0
                                            AND new_competences_relation.Date_Debut>'0001-01-01'
                                            AND new_competences_relation.Date_Fin > '0001-01-01'
                                            AND new_competences_relation.Date_Fin >= '".$date_moins_6mois."'
                                        ORDER BY
                                            new_competences_relation.Date_Debut DESC
                                    ) AS Tab_Qualif
                                    GROUP BY
                                        Tab_Qualif.Id_Personne,
                                        Tab_Qualif.Id_Prestation,
                                        Tab_Qualif.Id_Qualification_Parrainage
                                ) AS TAB
                                WHERE
                                    TAB.Evaluation<>'B'
                                    AND TAB.Evaluation<>''
                                    AND TAB.Date_Fin<='".$date_4mois."'
                                    AND
                                    (
                                        SELECT
                                            COUNT(Id)
                                        FROM
                                            form_qualificationnecessaire_prestation
                                        WHERE
                                            form_qualificationnecessaire_prestation.Id_Relation=TAB.Id
                                            AND form_qualificationnecessaire_prestation.Necessaire=0
                                            AND form_qualificationnecessaire_prestation.Id_Prestation=TAB.Id_Prestation
										    AND form_qualificationnecessaire_prestation.Id_Pole=TAB.Id_Pole

                                    )=0 
									AND
                                    (
                                        SELECT
                                            COUNT(form_besoin.Id)
										FROM
                                            form_besoin
										WHERE
                                            form_besoin.Suppr=0
											AND form_besoin.Motif='Renouvellement'
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
									ORDER BY
										Prestation,
										Pole";
							$resultPresta=mysqli_query($bdd,$requeteQualifications);
							$nbQualifs=mysqli_num_rows($resultPresta);
							if($nbQualifs>0)
							{
								while($rowPresta=mysqli_fetch_array($resultPresta))
								{
									$selected="";
									if($Prestation<>"")
										{if($Prestation==$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']){$selected="selected";}}
										echo "<option value='".$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']."' ".$selected.">".stripslashes(AfficheCodePrestation($rowPresta['Prestation']).$rowPresta['Pole'])."</option>\n";
								}
							}
							?>
						</select>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
					<td width="20%">
						<input name="personne" id="personne" value="<?php $personne=$_SESSION['FiltreFinQualif_Personne']; echo $personne; ?>"/>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Caduque dans";}else{echo "Decaf in";}?> : </td>
					<td width="20%">
						<select id="caduque" name="caduque" style="width:100px;" onchange="submit()">
							<option value="0"></option>
							<option value="2" <?php if($caduque=="2"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Moins de 2 mois";}else{echo "Less than 2 months";}?></option>
							<option value="4" <?php if($caduque=="4"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Moins de 4 mois";}else{echo "Less than 4 months";}?></option>
						</select>
					</td>
					<td width="40%" align="left">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					</td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?> : </td>
					<td width="20%" colspan="2">
						<?php $qualification=$_SESSION['FiltreFinQualif_Qualification']; ?>
						<select id="qualification" name="qualification" style="width:300px;" onchange="submit()">
							<option value="0"></option>
							<?php
							$requeteQualifications="
                                SELECT DISTINCT
                                    TAB.Qualif,
                                    TAB.Id_Qualification_Parrainage
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
                                            new_competences_relation.Date_Fin,
                                            new_competences_relation.Date_Debut,
                                            new_competences_personne_prestation.Id_Prestation,
                                            new_competences_personne_prestation.Id_Pole,
                                            (SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif,(@row_number:=@row_number + 1) AS rnk
                                        FROM
                                            new_competences_relation
                                        RIGHT JOIN new_competences_personne_prestation
                                            ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
        								LEFT JOIN new_competences_qualification
        								    ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
                                        WHERE
                                        (
                                            new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
                                            OR new_competences_personne_prestation.Date_Fin<='0001-01-01'
                                        ) 
                                            ".$requeteDroits."
                                            AND new_competences_relation.Type='Qualification' 
								            AND new_competences_relation.Suppr=0
								            AND new_competences_qualification.Duree_Validite>0
                                            AND new_competences_relation.Date_Debut>'0001-01-01'
                                            AND new_competences_relation.Date_Fin > '0001-01-01'
                                            AND new_competences_relation.Date_Fin >= '".$date_moins_6mois."'
                                        ORDER BY
                                            new_competences_relation.Date_Debut DESC
                                    ) AS Tab_Qualif
                                    GROUP BY
                                        Tab_Qualif.Id_Personne,
                                        Tab_Qualif.Id_Prestation,
                                        Tab_Qualif.Id_Qualification_Parrainage
                                ) AS TAB
                                WHERE
                                    TAB.Evaluation<>'B'
                                    AND TAB.Evaluation<>''
                                    ".$requeteCaduque."
                                    AND
                                    (
                                        SELECT
                                            COUNT(Id)
                                        FROM 
                                            form_qualificationnecessaire_prestation
                                        WHERE
                                            form_qualificationnecessaire_prestation.Id_Relation=TAB.Id
                                            AND
                                            (
                                                form_qualificationnecessaire_prestation.Necessaire=0
                                                AND form_qualificationnecessaire_prestation.Id_Prestation=TAB.Id_Prestation
                                                AND form_qualificationnecessaire_prestation.Id_Pole=TAB.Id_Pole
                                            )
                                    )=0
									AND (
										(TAB.Id_Qualification_Parrainage IN (133,2145,2490,13,12,1683,75,167)
										AND 
											(
												SELECT
												   COUNT(new_competences_relation.Id)
												FROM
													new_competences_relation
												WHERE new_competences_relation.Id_Qualification_Parrainage IN (1606,2130,3258)
													AND new_competences_relation.Suppr=0
													AND new_competences_relation.Id_Personne=TAB.Id_Personne
													AND (new_competences_relation.Date_Fin <= '0001-01-01'
													OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
											)=0
										)
									
									OR
										TAB.Id_Qualification_Parrainage NOT IN (133,2145,2490,13,12,1683,75,167)
									)
                                    AND
                                    (
                                        SELECT
                                            COUNT(form_besoin.Id)
										FROM
                                            form_besoin
										WHERE
                                            form_besoin.Suppr=0
    										AND form_besoin.Motif='Renouvellement'
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
									)=0 ";
							$resultQualif=mysqli_query($bdd,$requeteQualifications);
							$nbQualifs=mysqli_num_rows($resultQualif);
							if($nbQualifs>0)
							{
								while($rowQualif=mysqli_fetch_array($resultQualif))
								{
									$selected="";
									if($qualification<>"")
										{if($qualification==$rowQualif['Id_Qualification_Parrainage']){$selected="selected";}}
									echo "<option value='".$rowQualif['Id_Qualification_Parrainage']."' ".$selected.">".stripslashes($rowQualif['Qualif'])."</option>\n";
								}
							}
							$caduque=$_SESSION['FiltreFinQualif_Caduque'];
							?>
						</select>
					</td>
					<td></td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?> : </td>
					<td width="20%">
						<?php $etat=$_SESSION['FiltreFinQualif_Etat']; ?>
						<select id="etat" name="etat" style="width:100px;" onchange="submit()">
							<option value="0" <?php if($etat=="0"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "En attente";}else{echo "Waiting";}?></option>
							<option value="1" <?php if($etat=="1"){echo "selected";}?>><?php if($LangueAffichage=="FR"){echo "Refusé";}else{echo "Refuse";}?></option>
						</select>
					</td>
					
					<td width="5%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td valign="top" colspan="8" class="Libelle" <?php if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==0){echo "style='display:none;'";} ?>>
						<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
								<?php
								
									$Id_RespProjet=$_SESSION['FiltreFinQualif_RespProjet'];
									if($_POST){
										$Id_RespProjet="";
										if(isset($_POST['Id_RespProjet'])){
											if (is_array($_POST['Id_RespProjet'])) {
												foreach($_POST['Id_RespProjet'] as $value){
													if($Id_RespProjet<>''){$Id_RespProjet.=",";}
												  $Id_RespProjet.=$value;
												}
											} else {
												$value = $_POST['Id_RespProjet'];
												$Id_RespProjet = $value;
											}
										}
									}
									$_SESSION['FiltreFinQualif_RespProjet']=$Id_RespProjet;
			
									$rqRespProjet="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
									AND Id_Plateforme IN (
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteAssistantFormationExterne.")
									)
									AND Id_Personne<>0
									ORDER BY Personne";
									
									$resultRespProjet=mysqli_query($bdd,$rqRespProjet);
									$Id_RespProjet=0;
									while($rowRespProjet=mysqli_fetch_array($resultRespProjet))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_RespProjet']) ? $_POST['Id_RespProjet'] : array();
											foreach($checkboxes as $value) {
												if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
											}
										}
										else{
											$checkboxes = explode(',',$_SESSION['FiltreFinQualif_RespProjet']);
											foreach($checkboxes as $value) {
												if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
											}
										}
										echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
									}
								?>
					</td>
				</tr>
			</table>
	</td></tr>
	<tr><td height="8"></td></tr>
		<tr>
		<td align="center" style="font-size:14px;">
		<?php
		//QUALIFICATIONS A REPASSER DANS LES 4 MOIS
		$requeteQualificationFiltre="";
		if($qualification<>"0"){$requeteQualificationFiltre="AND new_competences_relation.Id_Qualification_Parrainage=".$qualification." ";}
		
		$requeteEtat="
            AND
                (
                SELECT
                    COUNT(Id)
                FROM
                    form_qualificationnecessaire_prestation
                WHERE
                    form_qualificationnecessaire_prestation.Id_Relation=TAB.Id
                    AND
                        (
                        form_qualificationnecessaire_prestation.Necessaire=0
                        AND form_qualificationnecessaire_prestation.Id_Prestation=TAB.Id_Prestation
						AND form_qualificationnecessaire_prestation.Id_Pole=TAB.Id_Pole
                        )
                )";
		if($etat==0){$requeteEtat.="=0";}
		else{$requeteEtat.=">0";}
		
		$requeteQualificationsAnalyse="
            SELECT
                *
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
                        new_competences_personne_prestation.Id_Prestation,
                        new_competences_personne_prestation.Id_Pole,
                        new_competences_relation.Id_Qualification_Parrainage,
                        new_competences_relation.Date_Debut,
                        new_competences_relation.Date_Fin,
						new_competences_relation.Date_QCM,
                        (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne ";
		$requeteQualifications="
            SELECT
                *,
			   (
					SELECT
						COUNT(form_besoin.Id)
					FROM
						form_besoin
					WHERE
						form_besoin.Suppr=0
						AND form_besoin.Motif<>'Renouvellement'
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
					) AS NbBesoin
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
                        new_competences_relation.Date_Fin,
                        new_competences_relation.Date_Debut,
						new_competences_relation.Date_QCM,
                        (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne,
                        (SELECT Libelle FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Prestation,
                        (SELECT Libelle FROM new_competences_pole WHERE Id=new_competences_personne_prestation.Id_Pole) AS Pole,
                        (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Id_Plateforme,
                        new_competences_personne_prestation.Id_Prestation,
                        new_competences_personne_prestation.Id_Pole,
                        (SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif,
                        (
                            SELECT
                            (
                                SELECT
                                    Libelle
                                FROM
                                    new_competences_categorie_qualification
                                WHERE
                                    new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification
                            )
                            FROM
                                new_competences_qualification
                            WHERE
                                Id=new_competences_relation.Id_Qualification_Parrainage
                        ) AS Categorie,(@row_number:=@row_number + 1) AS rnk ";
		$requeteQualifications2="
                    FROM
                        new_competences_relation
                    RIGHT JOIN new_competences_personne_prestation
                        ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
                    LEFT JOIN new_competences_qualification
                        ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
                    WHERE
                    (
                        new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
                        OR new_competences_personne_prestation.Date_Fin<='0001-01-01'
                    )"
                        .$requeteDroits
                        .$requeteQualificationFiltre."
                        AND new_competences_relation.Type='Qualification' 
            			AND new_competences_relation.Suppr=0
                        AND new_competences_relation.Statut_Surveillance != 'REFUSE'
            			AND new_competences_qualification.Duree_Validite>0
                        AND new_competences_relation.Date_Debut>'0001-01-01'
                        AND new_competences_relation.Date_Fin > '0001-01-01'
                        AND new_competences_relation.Date_Fin >= '".$date_moins_6mois."'
                    ORDER BY
                        new_competences_relation.Date_Debut DESC
                    ) AS Tab_Qualif
                GROUP BY
                    Tab_Qualif.Id_Personne,
					Tab_Qualif.Id_Prestation,
                    Tab_Qualif.Id_Qualification_Parrainage
                ) AS TAB
            WHERE
                TAB.Evaluation<>'B'
                AND TAB.Evaluation<>''"
                .$requeteCaduque
                .$requeteEtat."
                AND
                (
                    SELECT
                        COUNT(form_besoin.Id)
					FROM
                        form_besoin
					WHERE
                        form_besoin.Suppr=0
    					AND form_besoin.Motif='Renouvellement'
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
				
				AND (
					(TAB.Id_Qualification_Parrainage IN (133,2145,2490,13,12,1683,75,167)
					AND 
						(
							SELECT
							   COUNT(new_competences_relation.Id)
							FROM
								new_competences_relation
							WHERE new_competences_relation.Id_Qualification_Parrainage IN (1606,2130,3258)
								AND new_competences_relation.Suppr=0
								AND new_competences_relation.Id_Personne=TAB.Id_Personne
								AND (new_competences_relation.Date_Fin <= '0001-01-01'
								OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
						)=0
					)
				
				OR
					TAB.Id_Qualification_Parrainage NOT IN (133,2145,2490,13,12,1683,75,167)
				)
				
				AND (
                    SELECT
                       COUNT(new_competences_relation.Id)
                    FROM
                        new_competences_relation
                    WHERE new_competences_relation.Id_Qualification_Parrainage=TAB.Id_Qualification_Parrainage
            			AND new_competences_relation.Suppr=0
						AND new_competences_relation.Id_Personne=TAB.Id_Personne
						AND new_competences_relation.Evaluation IN ('L','T')
						AND new_competences_relation.Date_QCM>=TAB.Date_QCM
                        AND (new_competences_relation.Date_Fin <= '0001-01-01'
                        OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
				)=0
				AND (
					SELECT
					   COUNT(new_competences_relation.Id)
					FROM
						new_competences_relation
					WHERE new_competences_relation.Id_Qualification_Parrainage=TAB.Id_Qualification_Parrainage
						AND new_competences_relation.Suppr=0
						AND new_competences_relation.Id_Personne=TAB.Id_Personne
						AND new_competences_relation.Evaluation NOT IN ('B','')
						AND new_competences_relation.Date_QCM>=TAB.Date_QCM
						AND (new_competences_relation.Date_Fin <= '0001-01-01'
						OR new_competences_relation.Date_Fin >= '".date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"))."')
					)=0
				";

		if($personne<>""){$requeteQualifications2.=" AND TAB.Personne LIKE '%".$personne."%' ";}
		if($Prestation<>"0"){$requeteQualifications2.=" AND CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole)='".$Prestation."' ";}
		if($_SESSION['FiltreFinQualif_RespProjet']<>""){
			$requeteQualifications2.="
					AND CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) 
						IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne IN (".$_SESSION['FiltreFinQualif_RespProjet'].")
							AND Id_Poste IN (".$IdPosteResponsableProjet.")
						)
						";
		}
		$resultQualifications=mysqli_query($bdd,$requeteQualificationsAnalyse.$requeteQualifications2);
		$nbQualifs=mysqli_num_rows($resultQualifications);
		

		$val=50;
		if($_SERVER['SERVER_NAME']<>"192.168.20.3"){$val=50;}
		
		$nombreDePages=ceil($nbQualifs/$val);
		if(isset($_GET['Page'])){$_SESSION['FORM_FINVALIDITE_Page']=$_GET['Page'];}
		else{$_SESSION['FORM_FINVALIDITE_Page']=0;}
		
		if($_SESSION['TriFinQualif_General']<>""){$requeteQualifications2.=" ORDER BY ".substr($_SESSION['TriFinQualif_General'],0,-1);}
		$req3=" LIMIT ".($_SESSION['FORM_FINVALIDITE_Page']*$val).",".$val;

		$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2.$req3);
		$nbQualifs=mysqli_num_rows($resultQualifications);

		if($_SESSION['FORM_FINVALIDITE_Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_FinValiditeQualification.php?Page=0'><<</a> </b>";}
		$valeurDepart=1;
		if($_SESSION['FORM_FINVALIDITE_Page']<=5){$valeurDepart=1;}
		elseif($_SESSION['FORM_FINVALIDITE_Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
		else{$valeurDepart=$_SESSION['FORM_FINVALIDITE_Page']-5;}
		for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
		{
			if($i<=$nombreDePages)
			{
				if($i==($_SESSION['FORM_FINVALIDITE_Page']+1)){echo "<b> [ ".$i." ] </b>";}	
				else{echo "<b> <a style='color:#00599f;' href='Liste_FinValiditeQualification.php?Page=".($i-1)."'>".$i."</a> </b>";}
			}
		}
		if($_SESSION['FORM_FINVALIDITE_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_FinValiditeQualification.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
		?>
		</td>
	</tr>
	<tr><td>
		<div style="width:100%;height:400px;overflow:auto;">
		<table style="width:100%; border-spacing:0; align:cleft;" class="GeneralInfo">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualification.php?Tri=Personne">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Personne";}
    					else{echo "Person";}
    					if($_SESSION['TriFinQualif_Personne']=="DESC"){echo "&uarr;";}
                        elseif($_SESSION['TriFinQualif_Personne']=="ASC"){echo "&darr;";}
                        ?>
                    </a>
                </td>
				<td class="EnTeteTableauCompetences" width="17%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualification.php?Tri=Prestation">
						&nbsp;
						<?php 
						if($LangueAffichage=="FR"){echo "Prestation - Pôle";}
						else{echo "Activity - Pole";}
						if($_SESSION['TriFinQualif_Prestation']=="DESC"){echo "&uarr;";}
						elseif($_SESSION['TriFinQualif_Prestation']=="ASC"){echo "&darr;";}
						?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="30%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualification.php?Tri=Qualif">
						&nbsp;
						<?php
						if($LangueAffichage=="FR"){echo "Qualification";}
						else{echo "Qualification";}
						if($_SESSION['TriFinQualif_Qualification']=="DESC"){echo "&uarr;";}
						elseif($_SESSION['TriFinQualif_Qualification']=="ASC"){echo "&darr;";}
						?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="14%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualification.php?Tri=Categorie">
						&nbsp;
						<?php
						if($LangueAffichage=="FR"){echo "Catégorie";}
						else{echo "Category";}
						if($_SESSION['TriFinQualif_Categorie']=="DESC"){echo "&uarr;";}
						elseif($_SESSION['TriFinQualif_Categorie']=="ASC"){echo "&darr;";}
						?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">
					<a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualification.php?Tri=DateFin">
    					&nbsp;
    					<?php
    					if($LangueAffichage=="FR"){echo "Date de fin";}
    					else{echo "End date";}
    					if($_SESSION['TriFinQualif_DateFin']=="DESC"){echo "&uarr;";}
    					elseif($_SESSION['TriFinQualif_DateFin']=="ASC"){echo "&darr;";}
    					?>
					</a>
				</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">
					&nbsp;
					<?php
					if($LangueAffichage=="FR"){echo "Etat";}
					else{echo "State";}
					?>
				</td>
				<td class="EnTeteTableauCompetences" align="center" width="5%">
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreValidation('V','<?php echo $LangueAffichage; ?>','<?php echo $Prestation; ?>','<?php echo $personne; ?>','<?php echo $caduque; ?>','<?php echo $qualification; ?>')" title="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($LangueAffichage=="FR"){echo "V";}else{echo "V";}?>&nbsp;&nbsp;&nbsp;&nbsp;
					</a>
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreValidation('R','<?php echo $LangueAffichage; ?>','<?php echo $Prestation; ?>','<?php echo $personne; ?>','<?php echo $caduque; ?>','<?php echo $qualification; ?>')" title="<?php if($LangueAffichage=="FR"){echo "Refuser";}else{echo "Refuse";}?>">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($LangueAffichage=="FR"){echo "R";}else{echo "R";}?>&nbsp;&nbsp;&nbsp;&nbsp;
					</a>
					<br>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<?php
				if ($nbQualifs>0)
				{
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultQualifications))
					{
						$couleur2="black";
						if($row['Date_Fin']<=date('Y-m-d')){$couleur2="red";}
						elseif($row['Date_Fin']<=$date_2mois){$couleur2="orange";}
						$Pole="";
						if($row['Pole']<>""){$Pole=" - ".stripslashes($row['Pole']);}
			?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td style="border-bottom:1px dotted #003333;" valign="middle">&nbsp;<?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a>" ;?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo AfficheCodePrestation(stripslashes($row['Prestation'])).$Pole;?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Qualif']; ?></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Categorie']; ?></td>
							<td style="border-bottom:1px dotted #003333;color:<?php echo $couleur2;?>;" align="center" valign="middle"><b><?php echo AfficheDateJJ_MM_AAAA($row['Date_Fin']);?></b></td>
							<td style="border-bottom:1px dotted #003333;" valign="middle">
							<?php 
								$req="
                                    SELECT
                                        Id
									FROM
                                        form_qualificationnecessaire_prestation 
									WHERE
                                        Necessaire=1
    									AND Id_Relation=".$row['Id']."
    									AND Id_Prestation=".$row['Id_Prestation']."
    									AND Id_Pole=".$row['Id_Pole'];
								$resultEnAttente=mysqli_query($bdd,$req);
								$nbEnAttente=mysqli_num_rows($resultEnAttente);
								if($nbEnAttente>0)
								{
									if($LangueAffichage=="FR"){echo "En attente validation des assistantes de formations";}
									else{echo "Waiting for validation of training assistants";}
								}
								else
								{
									$req="
                                        SELECT
                                            Id
                                        FROM
                                            form_qualificationnecessaire_prestation 
                                        WHERE
                                            Necessaire=0
        									AND Id_Relation=".$row['Id']."
        									AND Id_Prestation=".$row['Id_Prestation']."
        									AND Id_Pole=".$row['Id_Pole'];
									$resultEnAttente=mysqli_query($bdd,$req);
									$nbRefuse=mysqli_num_rows($resultEnAttente);
									if($nbRefuse>0)
									{
										if($LangueAffichage=="FR"){echo "Refusé";}
										else{echo "Refuse";}
									}
									if($row['NbBesoin']>0){
										$nbEnAttente=1;
										if($LangueAffichage=="FR"){echo "Besoin (initial) déjà existant<br>Supprimer le besoin";}
										else{echo "Need (initial) already existing<br>Remove the need";}
									}
								}
							?>
							</td>
							<td style="border-bottom:1px dotted #003333;" valign="middle" align="center">
								<input class="check" type="checkbox" name="<?php echo $row['Id']."_".$row['Id_Prestation']."_".$row['Id_Qualification_Parrainage']."_".$row['Id_Plateforme']."_".$row['Id_Personne']."_".$row['Id_Pole']; ?>" id="<?php echo $row['Id']."_".$row['Id_Prestation']."_".$row['Id_Pole'] ?>" />
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