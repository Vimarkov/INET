<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreValidation(Langue,Id_Prestation,Personne,Qualif){
		var elements = document.getElementsByClassName("form");
		Id="";
		for(var i=0, l=elements.length; i<l; i++){
			if(elements[i].value != 0){
				Id+=elements[i].name+"_"+elements[i].value+";";
			}
		}
		if(Id!=""){
			if(Langue=="EN"){texte='Are you sure you want to validate?';}
			else{texte='Etes-vous sûr de vouloir valider ?';}
			if(window.confirm(texte)){
				var w=window.open("Valider_FinValiditeQualificationFormation.php?Id="+Id+"&Id_Prestation="+Id_Prestation+"&Personne="+Personne+"&Qualif="+Qualif,"PageValidation","status=no,menubar=no,scrollbars=yes,width=20,height=20");
			}
		}				
	}
	function Excel(){
		var w=window.open("Excel_FinValiditeQualificationFormation.php?Id_Prestation="+document.getElementById('prestation').value+"&Personne="+document.getElementById('personne').value+"&Qualification="+document.getElementById('qualification').value,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function AnnulerRecyclageQualif(Id,Id_Personne,Id_Qualification){
		Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');
		}
		else{
			Confirm=window.confirm('Are you sure you want to delete?');
		}
		if(Confirm==true)
		{
			window.open("AnnulerRecyclageQualif.php?Id="+Id+"&Id_Personne="+Id_Personne+"&Id_Qualification="+Id_Qualification,"PageAnnuleRecyclage","status=no,menubar=no,width=400,height=200");
		}
	}
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
</script>
<?php
$date_4mois=date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"));
$date_2mois=date("Y-m-d",strtotime(date("Y-m-d")." + 2 month"));

if($_POST){
	$_SESSION['FiltreQualifEnAttente_Prestation']=$_POST['prestation'];
	$_SESSION['FiltreQualifEnAttente_Personne']=$_POST['personne'];
	$_SESSION['FiltreQualifEnAttente_Qualification']=$_POST['qualification'];
}
if(isset($_GET['Tri'])){
	if($_GET['Tri']=="Personne"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("Personne ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Personne DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Personne ASC","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Personne DESC","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_Personne']==""){$_SESSION['TriQualifEnAttente_Personne']="ASC";$_SESSION['TriQualifEnAttente_General'].= "Personne ".$_SESSION['TriQualifEnAttente_Personne'].",";}
		elseif($_SESSION['TriQualifEnAttente_Personne']=="ASC"){$_SESSION['TriQualifEnAttente_Personne']="DESC";$_SESSION['TriQualifEnAttente_General'].= "Personne ".$_SESSION['TriQualifEnAttente_Personne'].",";}
		else{$_SESSION['TriQualifEnAttente_Personne']="";}
	}
	if($_GET['Tri']=="Prestation"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("Prestation ASC,Pole ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Prestation DESC,Pole DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Prestation ASC,Pole ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Prestation DESC,Pole DESC,","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_Prestation']==""){$_SESSION['TriQualifEnAttente_Prestation']="ASC";$_SESSION['TriQualifEnAttente_General'].= "Prestation ".$_SESSION['TriQualifEnAttente_Prestation'].","."Pole ".$_SESSION['TriQualifEnAttente_Prestation'].",";}
		elseif($_SESSION['TriQualifEnAttente_Prestation']=="ASC"){$_SESSION['TriQualifEnAttente_Prestation']="DESC";$_SESSION['TriQualifEnAttente_General'].= "Prestation ".$_SESSION['TriQualifEnAttente_Prestation'].","."Pole ".$_SESSION['TriQualifEnAttente_Prestation'].",";}
		else{$_SESSION['TriQualifEnAttente_Prestation']="";}
	}
	if($_GET['Tri']=="Qualif"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("Qualif ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Qualif DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Qualif ASC","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Qualif DESC","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_Qualification']==""){$_SESSION['TriQualifEnAttente_Qualification']="ASC";$_SESSION['TriQualifEnAttente_General'].= "Qualif ".$_SESSION['TriQualifEnAttente_Qualification'].",";}
		elseif($_SESSION['TriQualifEnAttente_Qualification']=="ASC"){$_SESSION['TriQualifEnAttente_Qualification']="DESC";$_SESSION['TriQualifEnAttente_General'].= "Qualif ".$_SESSION['TriQualifEnAttente_Qualification'].",";}
		else{$_SESSION['TriQualifEnAttente_Qualification']="";}
	}
	if($_GET['Tri']=="Categorie"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("Categorie ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Categorie DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Categorie ASC","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Categorie DESC","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_Categorie']==""){$_SESSION['TriQualifEnAttente_Categorie']="ASC";$_SESSION['TriQualifEnAttente_General'].= "Categorie ".$_SESSION['TriQualifEnAttente_Categorie'].",";}
		elseif($_SESSION['TriQualifEnAttente_Categorie']=="ASC"){$_SESSION['TriQualifEnAttente_Categorie']="DESC";$_SESSION['TriQualifEnAttente_General'].= "Categorie ".$_SESSION['TriQualifEnAttente_Categorie'].",";}
		else{$_SESSION['TriQualifEnAttente_Categorie']="";}
	}
	if($_GET['Tri']=="DateFin"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("Date_Fin ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Date_Fin DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Date_Fin ASC","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Date_Fin DESC","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_DateFin']==""){$_SESSION['TriQualifEnAttente_DateFin']="ASC";$_SESSION['TriQualifEnAttente_General'].= "Date_Fin ".$_SESSION['TriQualifEnAttente_DateFin'].",";}
		elseif($_SESSION['TriQualifEnAttente_DateFin']=="ASC"){$_SESSION['TriQualifEnAttente_DateFin']="DESC";$_SESSION['TriQualifEnAttente_General'].= "Date_Fin ".$_SESSION['TriQualifEnAttente_DateFin'].",";}
		else{$_SESSION['TriQualifEnAttente_DateFin']="";}
	}
	if($_GET['Tri']=="Metier"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("Metier ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Metier DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Metier ASC","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("Metier DESC","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_Metier']==""){$_SESSION['TriQualifEnAttente_Metier']="ASC";$_SESSION['TriQualifEnAttente_General'].= "Metier ".$_SESSION['TriQualifEnAttente_Metier'].",";}
		elseif($_SESSION['TriQualifEnAttente_Metier']=="ASC"){$_SESSION['TriQualifEnAttente_Metier']="DESC";$_SESSION['TriQualifEnAttente_General'].= "Metier ".$_SESSION['TriQualifEnAttente_Metier'].",";}
		else{$_SESSION['TriQualifEnAttente_Metier']="";}
	}
	if($_GET['Tri']=="FuturMetier"){
		$_SESSION['TriQualifEnAttente_General']= str_replace("FuturMetier ASC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("FuturMetier DESC,","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("FuturMetier ASC","",$_SESSION['TriQualifEnAttente_General']);
		$_SESSION['TriQualifEnAttente_General']= str_replace("FuturMetier DESC","",$_SESSION['TriQualifEnAttente_General']);
		if($_SESSION['TriQualifEnAttente_FuturMetier']==""){$_SESSION['TriQualifEnAttente_FuturMetier']="ASC";$_SESSION['TriQualifEnAttente_General'].= "FuturMetier ".$_SESSION['TriQualifEnAttente_FuturMetier'].",";}
		elseif($_SESSION['TriQualifEnAttente_FuturMetier']=="ASC"){$_SESSION['TriQualifEnAttente_FuturMetier']="DESC";$_SESSION['TriQualifEnAttente_General'].= "FuturMetier ".$_SESSION['TriQualifEnAttente_FuturMetier'].",";}
		else{$_SESSION['TriQualifEnAttente_FuturMetier']="";}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" id="formulaire" method="POST" action="Liste_FinValiditeQualificationFormation.php">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ee70a1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Qualifications en attente de formation";}else{echo "Qualification pending training";}
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
					<td class="Libelle" width="8%"><?php if($LangueAffichage=="FR"){echo "Prestation ou Pôle";}else{echo "Activity or Pole";}?> : </td>
					<td width="15%">
						<input id="prestation" name="prestation" style="width:200px;" value="<?php $Prestation=$_SESSION['FiltreQualifEnAttente_Prestation']; echo $Prestation; ?>">
					</td>
					<td class="Libelle" width="8%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
					<td width="15%">
						<input name="personne" id="personne" value="<?php $personne=$_SESSION['FiltreQualifEnAttente_Personne']; echo $personne; ?>"/>
					</td>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?> : </td>
					<td width="20%">
						<input name="qualification" id="qualification" style="width:300px;" value="<?php $qualification=$_SESSION['FiltreQualifEnAttente_Qualification']; echo $qualification; ?>"/>
					</td>
					<td></td>
					<td width="5%" align="left">
						<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					</td>
					<td width="5%" align="left">
					&nbsp;<a style="text-decoration:none;" href="javascript:Excel();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td valign="top" colspan="8" class="Libelle">
						<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
								<?php
								
									$Id_RespProjet=$_SESSION['FiltreQualifEnAttente_RespProjet'];
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
									$_SESSION['FiltreQualifEnAttente_RespProjet']=$Id_RespProjet;
			
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
										AND Id_Poste IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.")
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
											$checkboxes = explode(',',$_SESSION['FiltreQualifEnAttente_RespProjet']);
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
				$requeteQualificationsAnalyse="
                    SELECT
                        form_qualificationnecessaire_prestation.Id,
                        form_qualificationnecessaire_prestation.Id_Relation,
                        form_qualificationnecessaire_prestation.Id_Prestation,
                        form_qualificationnecessaire_prestation.Necessaire,
                        form_qualificationnecessaire_prestation.Id_Validateur,
                        form_qualificationnecessaire_prestation.DateValidation ";
				$requeteQualifications="
                    SELECT
                        form_qualificationnecessaire_prestation.Id,
                        form_qualificationnecessaire_prestation.Id_Relation,
                        form_qualificationnecessaire_prestation.Id_Prestation,
                        form_qualificationnecessaire_prestation.Necessaire,
                        new_competences_relation.Id_Qualification_Parrainage,
                        form_qualificationnecessaire_prestation.Id_Validateur,
                        form_qualificationnecessaire_prestation.DateValidation,
                        new_competences_relation.Date_Fin,
                        new_competences_relation.Id_Personne,
                        (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne,
                        (
                            SELECT
                                (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=new_competences_personne_metier.Id_Metier)
                            FROM
                                new_competences_personne_metier
                            WHERE
                                Futur=0 AND new_competences_personne_metier.Id_Personne=new_competences_relation.Id_Personne LIMIT 1
                        ) AS Metier,
                        (
                            SELECT
                                (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=new_competences_personne_metier.Id_Metier)
                            FROM
                                new_competences_personne_metier
                            WHERE
                                Futur=1
                                AND new_competences_personne_metier.Id_Personne=new_competences_relation.Id_Personne LIMIT 1
                        ) AS FuturMetier,
                        (SELECT Libelle FROM new_competences_prestation WHERE Id=form_qualificationnecessaire_prestation.Id_Prestation) AS Prestation,
                        (SELECT Libelle FROM new_competences_pole WHERE Id=form_qualificationnecessaire_prestation.Id_Pole) AS Pole,
                        (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_qualificationnecessaire_prestation.Id_Prestation) AS Id_Plateforme,
                        (SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif,
                        (
                            SELECT
                                (SELECT Libelle FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification)
                            FROM
                                new_competences_qualification
                            WHERE
                                Id=new_competences_relation.Id_Qualification_Parrainage
                        ) AS Categorie ";
				$requeteQualifications2="
                    FROM
                        form_qualificationnecessaire_prestation
                        LEFT JOIN new_competences_relation
                            ON form_qualificationnecessaire_prestation.Id_Relation=new_competences_relation.Id
                    WHERE
                        Necessaire=1
                        AND new_competences_relation.Suppr=0
                        AND form_qualificationnecessaire_prestation.Id_Prestation IN
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
                                        Id_Poste IN (".implode(",",$TableauIdPostesAF_RH).")
                                        AND Id_Personne=".$IdPersonneConnectee."
                                )
                        )
						";
				if($qualification<>"")
				{
					$requeteQualifications2.="AND (SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) LIKE '%".$qualification."%' ";
				}
				if($Prestation<>"")
				{
					$requeteQualifications2.="AND CONCAT((SELECT Libelle FROM new_competences_prestation WHERE Id=form_qualificationnecessaire_prestation.Id_Prestation),' ',IF((SELECT Libelle FROM new_competences_pole WHERE Id=form_qualificationnecessaire_prestation.Id_Pole)<>NULL,(SELECT Libelle FROM new_competences_pole WHERE Id=form_qualificationnecessaire_prestation.Id_Pole),'')) LIKE '%".$Prestation."%' ";
				}
				if($_SESSION['FiltreQualifEnAttente_RespProjet']<>""){
					$requeteQualifications2.="
							AND CONCAT(form_qualificationnecessaire_prestation.Id_Prestation,'_',form_qualificationnecessaire_prestation.Id_Pole) 
								IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
									FROM new_competences_personne_poste_prestation
									WHERE Id_Personne IN (".$_SESSION['FiltreQualifEnAttente_RespProjet'].")
									AND Id_Poste IN (".$IdPosteResponsableProjet.")
								)
								";
				}
				if($personne<>"")
				{
					$requeteQualifications2.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) LIKE '%".$personne."%' ";
				}
				$resultQualifications=mysqli_query($bdd,$requeteQualificationsAnalyse.$requeteQualifications2);
				$nbQualifs=mysqli_num_rows($resultQualifications);
				
				$nombreDePages=ceil($nbQualifs/500);
				if(isset($_GET['Page'])){$_SESSION['FORM_FINVALIDITEFORMATION_Page']=$_GET['Page'];}
				else{$_SESSION['FORM_FINVALIDITEFORMATION_Page']=0;}
				if($_SESSION['TriQualifEnAttente_General']<>""){
					$requeteQualifications2.=" ORDER BY ".substr($_SESSION['TriQualifEnAttente_General'],0,-1);
				}
				$req3=" LIMIT ".($_SESSION['FORM_FINVALIDITEFORMATION_Page']*500).",500";
				
				
				$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2.$req3);
				$nbQualifs=mysqli_num_rows($resultQualifications);
				
				$nbPage=0;
				if($_SESSION['FORM_FINVALIDITEFORMATION_Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_FinValiditeQualificationFormation.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['FORM_FINVALIDITEFORMATION_Page']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['FORM_FINVALIDITEFORMATION_Page']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['FORM_FINVALIDITEFORMATION_Page']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['FORM_FINVALIDITE_Page']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_FinValiditeQualificationFormation.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['FORM_FINVALIDITE_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_FinValiditeQualificationFormation.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr><td>
		<div style="width:100%;height:400px;overflow:auto;">
		<table style="width:100%; border-spacing:0; align:left;" class="GeneralInfo">
			<tr bgcolor="#2c8bb4">
				<td class="EnTeteTableauCompetences" width="13%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=Personne">&nbsp;<?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriQualifEnAttente_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_Personne']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=Metier">&nbsp;<?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriQualifEnAttente_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_Metier']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=FuturMetier">&nbsp;<?php if($LangueAffichage=="FR"){echo "Futur métier";}else{echo "Future job";} ?><?php if($_SESSION['TriQualifEnAttente_FuturMetier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_FuturMetier']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=Prestation">&nbsp;<?php if($LangueAffichage=="FR"){echo "Prestation - Pôle";}else{echo "Activity - Pole";} ?><?php if($_SESSION['TriQualifEnAttente_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_Prestation']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=Qualif">&nbsp;<?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";} ?><?php if($_SESSION['TriQualifEnAttente_Qualification']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_Qualification']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="14%" style="text-decoration:none;color:#ffffff;font-weight:bold;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=Categorie">&nbsp;<?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";} ?><?php if($_SESSION['TriQualifEnAttente_Categorie']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_Categorie']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="7%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_FinValiditeQualificationFormation.php?Tri=DateFin">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriQualifEnAttente_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQualifEnAttente_DateFin']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";} ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreValidation('<?php echo $LangueAffichage; ?>','<?php echo $Prestation; ?>','<?php echo $personne; ?>','<?php echo $qualification; ?>')" title="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>&nbsp;&nbsp;&nbsp;&nbsp;</a>
				</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">
				</td>
			</tr>
			<?php
				if ($nbQualifs>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($resultQualifications)){
							$couleur2="black";
							if($row['Date_Fin']<=date('Y-m-d')){
								$couleur2="red";
							}
							elseif($row['Date_Fin']<=$date_2mois){
								$couleur2="orange";
							}
							
							$Pole="";
							if($row['Pole']<>""){
								$Pole=" - ".stripslashes($row['Pole']);
							}
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td style="border-bottom:1px dotted #003333;" valign="middle">&nbsp;<?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a>";?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle">&nbsp;<?php echo $row['Metier'];?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle">&nbsp;<?php echo $row['FuturMetier'];?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo stripslashes($row['Prestation']).$Pole;?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Qualif']; ?></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle"><?php echo $row['Categorie']; ?></td>
								<td style="border-bottom:1px dotted #003333;color:<?php echo $couleur2;?>;" align="center" valign="middle"><b><?php echo AfficheDateJJ_MM_AAAA($row['Date_Fin']);?></b></td>
								<td style="border-bottom:1px dotted #003333;" valign="middle">
									<select class="form" id="form_<?php echo $row['Id']; ?>" name="form_<?php echo $row['Id']; ?>" style="width:200px;" >
										<option value="0"></option>
										<?php 
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
													WHERE (form_formation.Id_Plateforme=0 OR form_formation.Id_Plateforme=".$row['Id_Plateforme'].") 
													AND form_formation.Suppr=0 
													AND form_formation.Id_TypeFormation<>1
													AND form_formation_qualification.Suppr=0 
													AND form_formation_qualification.Id_Qualification=".$row['Id_Qualification_Parrainage']." 
												ORDER BY Libelle ";
											$result=mysqli_query($bdd,$req);
											echo $req;
											$nbResult=mysqli_num_rows($result);
											if($nbResult>0){
												while($rowForm=mysqli_fetch_array($result)){
													$organisme="";
													if($rowForm['Organisme']<>""){
														$organisme=" (".$rowForm['Organisme'].") ";
													}
													echo "<option value='".$rowForm['Id']."'>".stripslashes($rowForm['Libelle']).$organisme."</option>";
												}
											}
										?>
									</select>
								</td>
								<td style="border-bottom:1px dotted #003333;" valign="middle">
									<a style="text-decoration:none;" href="javascript:AnnulerRecyclageQualif(<?php echo $row['Id']; ?>,<?php echo $row['Id_Personne']; ?>,<?php echo $row['Id_Qualification_Parrainage']; ?>);">
										<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
									</a>
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