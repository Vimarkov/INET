<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function valider(id)
	{
		var w=window.open("WorkflowDesSurveillancesQBP_Valider.php?Id="+id, "PageQCMValider", "width=500,height=150");
		w.focus();
	}
	function planifier(id)
	{
		var w=window.open("WorkflowDesSurveillancesQBP_Planifier.php?Id="+id, "PageQCMPlanifier", "width=500,height=150");
		w.focus();
	}
	function ignorer(id)
	{
		var w=window.open("WorkflowDesSurveillancesQBP_Ignorer.php?Id="+id, "PageQCMValider", "width=500,height=150");
		w.focus();
	}
	function Excel(){
		var w=window.open("Excel_SurveillancesQBP.php?Prestation="+document.getElementById('Prestation').value+"&Personne="+document.getElementById('Personne').value+"&Statut="+document.getElementById('Statut').value+"","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
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
	$_SESSION['FiltreFormSurveillanceQBP_Plateforme']=$_POST['Plateforme'];
	$_SESSION['FiltreFormSurveillanceQBP_Prestation']=$_POST['Prestation'];
	$_SESSION['FiltreFormSurveillanceQBP_Personne']=$_POST['Personne'];
	$_SESSION['FiltreFormSurveillanceQBP_Statut']=$_POST['Statut'];
}

//Fonction de filtrage
global $laPrestation;
global $laPersonne;
global $laQualification;
global $leStatut;

$laPlateforme = $_SESSION['FiltreFormSurveillanceQBP_Plateforme'];
$laPrestation = $_SESSION['FiltreFormSurveillanceQBP_Prestation'];
$laPersonne = $_SESSION['FiltreFormSurveillanceQBP_Personne'];
$laQualification = $_SESSION['FiltreFormSurveillanceQBP_Qualification'];
$leStatut = $_SESSION['FiltreFormSurveillanceQBP_Statut'];

if(isset($_GET['Tri']))
{
	$tab = array("Personne","Prestation","Qualification","DateDebut","DateSurveillance","Statut");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriFormSurveillanceQBP_General']= str_replace($tri." ASC,","",$_SESSION['TriFormSurveillanceQBP_General']);
			$_SESSION['TriFormSurveillanceQBP_General']= str_replace($tri." DESC,","",$_SESSION['TriFormSurveillanceQBP_General']);
			$_SESSION['TriFormSurveillanceQBP_General']= str_replace($tri." ASC","",$_SESSION['TriFormSurveillanceQBP_General']);
			$_SESSION['TriFormSurveillanceQBP_General']= str_replace($tri." DESC","",$_SESSION['TriFormSurveillanceQBP_General']);
			if($_SESSION['TriFormSurveillanceQBP_'.$tri]==""){$_SESSION['TriFormSurveillanceQBP_'.$tri]="ASC";$_SESSION['TriFormSurveillanceQBP_General'].= $tri." ".$_SESSION['TriFormSurveillanceQBP_'.$tri].",";}
			elseif($_SESSION['TriFormSurveillanceQBP_'.$tri]=="ASC"){$_SESSION['TriFormSurveillanceQBP_'.$tri]="DESC";$_SESSION['TriFormSurveillanceQBP_General'].= $tri." ".$_SESSION['TriFormSurveillanceQBP_'.$tri].",";}
			else{$_SESSION['TriFormSurveillanceQBP_'.$tri]="";}
		}
	}
}
?>
<form class="test" id="formulaire" method="POST" action="WorkflowDesSurveillancesQBP_Liste.php">
<input type="hidden" name="Ouverture" id="Ouverture" value="">
<input type="hidden" name="BOuverture" id="BOuverture" value="">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffffff;">
				<tr>
					<td width="4">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
						if($LangueAffichage=="FR"){echo "<img width='20px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='20px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a></td>";
					?>
					</td>
					<td class="TitrePage">
						<?php 
                            if($LangueAffichage=="FR")
                            	echo "Gestion des surveillances # Workflow des surveillances QBP";
                            else
                            	echo "Monitoring management # Workflow monitoring QBP";
                        ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr><td height="4"></td>
                <tr>
					<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
					<td width="10%">
						<select id="Plateforme" name="Plateforme" onchange="submit()">
							<?php
							$Plateforme=$_SESSION['FiltreFormSurveillanceQBP_Plateforme'];
							
							$reqPla="
								SELECT DISTINCT
									Id_Plateforme, 
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM
									new_competences_personne_poste_prestation
								LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE
									Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee."
								UNION 
								 SELECT DISTINCT
									Id_Plateforme, 
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
								FROM
									new_competences_personne_poste_plateforme
								WHERE
									Id_Poste IN (".$IdPosteFormateur.",".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee."
								ORDER BY
									Libelle";
							$resultPlateforme=mysqli_query($bdd,$reqPla);
							$nbFormation=mysqli_num_rows($resultPlateforme);
							if($nbFormation>0)
							{
								while($rowplateforme=mysqli_fetch_array($resultPlateforme))
								{
									$selected="";
									if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
									if($Plateforme==$rowplateforme['Id_Plateforme']){$selected="selected";}
									echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
								}
							}
							$_SESSION['FiltreFormSurveillanceQBP_Plateforme']=$Plateforme;
							?>
						</select>
					</td>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation / Pôle";}else{echo "Activity / Pole";}?></td>
					<td>
						<select id="Prestation" name="Prestation" onchange="submit()">
							<option value="" selected></option>
							<?php
							$Prestation=$_SESSION['FiltreFormSurveillanceQBP_Prestation'];
							

							$rqPrestation="SELECT Id AS Id_Prestation, 
								Id_Plateforme,
								LEFT(Libelle,7) AS Libelle,
								0 AS Id_Pole,
								'' AS Pole
								FROM new_competences_prestation 
								WHERE Id NOT IN (
									SELECT Id_Prestation
									FROM new_competences_pole
									WHERE Actif=0
								)
								AND new_competences_prestation.Active=0
								AND new_competences_prestation.Id_Plateforme=".$_SESSION['FiltreFormSurveillanceQBP_Plateforme']."
								AND (Id_Plateforme IN (
									SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Poste 
										IN (".$IdPosteFormateur.",".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee." 
								)
								OR 
									(SELECT COUNT(Id)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
										AND Id_Personne=".$IdPersonneConnectee." 
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									)>0
								)
								
								UNION
								
								SELECT Id_Prestation,
								new_competences_prestation.Id_Plateforme,
								LEFT(new_competences_prestation.Libelle,7) AS Libelle,
								new_competences_pole.Id AS Id_Pole,
								CONCAT(' - ',new_competences_pole.Libelle) AS Pole
								FROM new_competences_pole
								INNER JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								AND new_competences_pole.Actif=0
								AND new_competences_prestation.Active=0
								AND new_competences_prestation.Id_Plateforme=".$_SESSION['FiltreFormSurveillanceQBP_Plateforme']."
								AND (Id_Plateforme IN (
									SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Poste 
										IN (".$IdPosteFormateur.",".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
									AND Id_Personne=".$IdPersonneConnectee." 
								)
								OR 
									(SELECT COUNT(Id)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
										AND Id_Personne=".$IdPersonneConnectee." 
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									)>0
								)
								ORDER BY Libelle, Pole";
							$resultPresta=mysqli_query($bdd,$rqPrestation);
							$nbPresta=mysqli_num_rows($resultPresta);
							if($nbPresta>0)
							{
								while($rowPresta=mysqli_fetch_array($resultPresta))
								{
									$selected="";
									if($Prestation==$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']){$selected="selected";}
									echo "<option value='".$rowPresta['Id_Prestation']."_".$rowPresta['Id_Pole']."' ".$selected.">".$rowPresta['Libelle'].$rowPresta['Pole']."</option>\n";
								}
							}
							$_SESSION['FiltreFormSurveillanceQBP_Prestation']=$Prestation;
							?>
						</select>
					</td>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
					<?php $Personne=$_SESSION['FiltreFormSurveillanceQBP_Personne'];?>
					<td><input style="width:200px" id="Personne" name="Personne" value="<?php echo $Personne;?>"></td>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){ echo "Statut";}else{echo "Status";}?></td>
					<td>
						<select id="Statut" name="Statut" onchange="submit()">
						   <option value="" selected>EN ATTENTE</option>
						   <?php
								$selected="";
								$Statut=$_SESSION['FiltreFormSurveillanceQBP_Statut'];
								if($Statut=="IGNORE"){$selected="selected";}
								echo "<option value='IGNORE' ".$selected.">IGNORE</option>";
								
								$selected="";
								if($Statut=="PLANIFIE"){$selected="selected";}
								echo "<option value='PLANIFIE' ".$selected.">PLANIFIE</option>";
							?>
						</select>
					</td>
					<td><input style='cursor:pointer;' class="Bouton" type="button" value="Filtrer" onclick="this.form.submit()"></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td valign="top" colspan="8" class="Libelle" <?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)==0){echo "style='display:none;'";} ?>>
						<?php if($LangueAffichage=="FR"){echo "CQP";}else{echo "CQP";}?> :<br>
								<?php
								
									$Id_CQP=$_SESSION['FiltreFormSurveillanceQBP_CQP'];
									if($_POST){
										$Id_CQP="";
										if(isset($_POST['Id_CQP'])){
											if (is_array($_POST['Id_CQP'])) {
												foreach($_POST['Id_CQP'] as $value){
													if($Id_CQP<>''){$Id_CQP.=",";}
												  $Id_CQP.=$value;
												}
											} else {
												$value = $_POST['Id_CQP'];
												$Id_CQP = $value;
											}
										}
									}
									$_SESSION['FiltreFormSurveillanceQBP_CQP']=$Id_CQP;
			
									$rqCQP="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE Id_Poste IN (".$IdPosteReferentQualiteProduit.")
									AND Id_Plateforme IN (
										".$Plateforme."
									)
									AND Id_Personne<>0
									ORDER BY Personne";
									
									$resultCQP=mysqli_query($bdd,$rqCQP);
									$Id_CQP=0;
									while($rowCQP=mysqli_fetch_array($resultCQP))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_CQP']) ? $_POST['Id_CQP'] : array();
											foreach($checkboxes as $value) {
												if($rowCQP['Id_Personne']==$value){$checked="checked";}
											}
										}
										else{
											$checkboxes = explode(',',$_SESSION['FiltreFormSurveillanceQBP_CQP']);
											foreach($checkboxes as $value) {
												if($rowCQP['Id_Personne']==$value){$checked="checked";}
											}
										}
										echo "<input type='checkbox' class='checkCQP' name='Id_CQP[]' Id='Id_CQP[]' value='".$rowCQP['Id_Personne']."' ".$checked.">".$rowCQP['Personne'];
									}
								?>
					</td>
					<td>
						&nbsp;<a style='text-decoration:none;' href='javascript:Excel();'>
								<img src='../../Images/excel.gif' border='0' alt='Excel' title='Excel'>
							</a>&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td height="8"></td></tr>
		<tr>
		<td align="center" style="font-size:14px;">
			<?php 	
				
					$ListePersonneSelonProfilConnecte="";
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS))
					{
						$ListePersonneSelonProfilConnecte.="
								SELECT
									Id_Personne 
								FROM
									new_competences_personne_prestation
								LEFT JOIN new_competences_prestation 
									ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE
									Date_Fin>='".date('Y-m-d')."'
									AND Id_Plateforme IN
									(
										SELECT Id_Plateforme
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS).")
									)
							";
						
					}
					else
					{
						$ListePersonneSelonProfilConnecte.="
								SELECT
									Id_Personne  
								FROM
									new_competences_personne_prestation
								WHERE
									Date_Fin>='".date('Y-m-d')."'
									AND CONCAT(Id_Prestation,'_',Id_Pole) IN
									(
										SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
									)
							";
					}

					//Uniquement les WA Basic de Airbus Helicopters QBP
					$req="SELECT *
						FROM
						(SELECT
							new_competences_relation.Id,
							(SELECT CONCAT (Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							new_competences_relation.Id_Personne,
							new_competences_qualification.Id AS Id_Qualif,
							new_competences_qualification.Libelle AS Qualification,
							(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Prestation,
							(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Pole,
							(SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Prestation,
							(SELECT Id_Pole FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Pole,
							new_competences_relation.Date_Debut,Date_Surveillance AS DateSurveillance,
							IF(IgnorerSurveillance=1,'IGNORE',IF(Date_PlanifSurveillance>'0001-01-01','PLANIFIE',new_competences_relation.Statut_Surveillance)) AS Statut,
							Statut_Surveillance,IgnorerSurveillance,(@row_number:=@row_number + 1) AS rnk
						FROM
							new_competences_relation,
							new_competences_qualification
						WHERE
							new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
							AND new_competences_relation.Suppr = 0
							AND new_competences_relation.Evaluation NOT IN ('B','L','')
							AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
							AND new_competences_qualification.Id_Categorie_Qualification=147
							AND new_competences_qualification.Libelle LIKE 'WA - Basic%'
							AND new_competences_relation.Id_Personne IN
							("
								.$ListePersonneSelonProfilConnecte."
							)";
					if($_SESSION['FiltreFormSurveillanceQBP_Plateforme']<>"")
					{
						$req.="
							AND (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1) IN (".$_SESSION['FiltreFormSurveillanceQBP_Plateforme'].") 
							";
					}
					if($_SESSION['FiltreFormSurveillanceQBP_Prestation']<>"")
					{
						$req.=" AND (SELECT COUNT(Id) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') AND CONCAT(Id_Prestation,'_',Id_Pole)='".$_SESSION['FiltreFormSurveillanceQBP_Prestation']."')>0 ";
					}
					if($_SESSION['FiltreFormSurveillanceQBP_CQP']<>""){
						$req.="
								AND CONCAT((SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1),'_',(SELECT Id_Pole FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND Date_Fin>='".date('Y-m-d')."' LIMIT 1)) 
									IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne IN (".$_SESSION['FiltreFormSurveillanceQBP_CQP'].")
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.")
									)
									";
					}
					
					if($_SESSION['FiltreFormSurveillanceQBP_Personne']<>""){$req.="AND (SELECT CONCAT (Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) LIKE '%".$_SESSION['FiltreFormSurveillanceQBP_Personne']."%' ";}
					if($_SESSION['FiltreFormSurveillanceQBP_Statut']<>""){
						$req.="AND IF(IgnorerSurveillance=1,'IGNORE',IF(Date_PlanifSurveillance>'0001-01-01','PLANIFIE',new_competences_relation.Statut_Surveillance)) LIKE '%".$_SESSION['FiltreFormSurveillanceQBP_Statut']."%' ";
					}
					else{
						$req.="AND IF(IgnorerSurveillance=1,'IGNORE',IF(Date_PlanifSurveillance>'0001-01-01','PLANIFIE',new_competences_relation.Statut_Surveillance)) IN ('','VALIDE','ECHEC') ";
					}
					/*$req.="ORDER BY Id_Personne,Date_Debut) AS TAB
						WHERE 
						IF(DateSurveillance<='0001-01-01',ADDDATE(Date_Debut,INTERVAL 1 YEAR),ADDDATE(DateSurveillance,INTERVAL 2 YEAR)) <= '".date('Y-m-d',strtotime(date('Y-m-d')." +4 month"))."'
						GROUP BY Id_Personne
						
					 ";*/
					 
					$req.="ORDER BY Id_Personne,Date_Debut) AS TAB
						GROUP BY Id_Personne
						
					 ";
					
					
					$val=50;
					
					$reqPartie2="";
					if($_SERVER['SERVER_NAME']=="127.0.0.1"){
						$reqPartie2=" LIMIT 500";
					}
					$resultSurveillance=mysqli_query($bdd,$req.$reqPartie2);
					$nbSurveillance=mysqli_num_rows($resultSurveillance);
					
					
		
					$nombreDePages=ceil($nbSurveillance/$val);
					if(isset($_GET['Page'])){$_SESSION['FORM_Surveillance_Page']=$_GET['Page'];}
					else{$_SESSION['FORM_Surveillance_Page']=0;}
		
		
					if($_SESSION['TriFormSurveillanceQBP_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriFormSurveillanceQBP_General'],0,-1);}
					$req2=" LIMIT ".($_SESSION['FORM_Surveillance_Page']*$val).",".$val;

					$resultSurveillance=mysqli_query($bdd,$req.$req2);
					$nbQualifs=mysqli_num_rows($resultSurveillance);
					
					if($_SESSION['FORM_Surveillance_Page']>1){echo "<b> <a style='color:#00599f;' href='WorkflowDesSurveillances_Liste.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['FORM_Surveillance_Page']<=5){$valeurDepart=1;}
					elseif($_SESSION['FORM_Surveillance_Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
					else{$valeurDepart=$_SESSION['FORM_Surveillance_Page']-5;}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
					{
						if($i<=$nombreDePages)
						{
							if($i==($_SESSION['FORM_Surveillance_Page']+1)){echo "<b> [ ".$i." ] </b>";}	
							else{echo "<b> <a style='color:#00599f;' href='WorkflowDesSurveillances_Liste.php?Page=".($i-1)."'>".$i."</a> </b>";}
						}
					}
					if($_SESSION['FORM_Surveillance_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='WorkflowDesSurveillances_Liste.php?Page=".($nombreDePages-1)."'>>></a> </b>";}

					?>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">

					<tr bgcolor='#2c8bb4'>
						<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} if($_SESSION['TriFormSurveillanceQBP_Personne']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillanceQBP_Personne']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="5%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Prestation"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";} if($_SESSION['TriFormSurveillanceQBP_Prestation']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillanceQBP_Prestation']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="25%">
							<?php if($LangueAffichage=="FR"){echo "Qualifications";}else{echo "Qualifications";}?><br>
							<table width='100%'>
								<tr>
									<td width='50%' style="text-decoration:none;color:#ffffff;font-weight:bold;"><?php if($LangueAffichage=="FR"){echo "Qualif.";}else{echo "Qualif.";}?></td>
									<td width='25%' style="text-decoration:none;color:#ffffff;font-weight:bold;"><?php if($LangueAffichage=="FR"){echo "Date Début";}else{echo "Start date";}?></td>
									<td width='25%' style="text-decoration:none;color:#ffffff;font-weight:bold;"><?php if($LangueAffichage=="FR"){echo "Date surveillance";}else{echo "Monitoring date";}?></td>
								</tr>
							</table>
						</td>
						
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=DateDebut"><?php if($LangueAffichage=="FR"){echo "1ère date de qualif";}else{echo "1st qualifying date";} if($_SESSION['TriFormSurveillanceQBP_DateDebut']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillanceQBP_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=DateSurveillance"><?php if($LangueAffichage=="FR"){echo "Dernière date surveillance";}else{echo "Last monitoring date";} if($_SESSION['TriFormSurveillanceQBP_DateSurveillance']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillanceQBP_DateSurveillance']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="WorkflowDesSurveillances_Liste.php?Tri=Statut"><?php if($LangueAffichage=="FR"){echo "Statut";}else{echo "Status";} if($_SESSION['TriFormSurveillanceQBP_Statut']=="DESC"){echo "&uarr;";}elseif($_SESSION['TriFormSurveillanceQBP_Statut']=="ASC"){echo "&darr;";}?></a></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Planifié";}else{echo "Planned";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?></td>
						<td class="EnTeteTableauCompetences" style="color:#ffffff;" width='3%'><?php if($LangueAffichage=="FR"){echo "Ignorer";}else{echo "Ignore";}?></td>
					</tr>
					<tr><td height="4"></td></tr>

					<?php
					$Couleur="#EEEEEE";
					while($row = mysqli_fetch_array($resultSurveillance)){
							$lacouleur="";
							if($row['DateSurveillance']<='0001-01-01'){
								if(date('Y-m-d',strtotime($row['Date_Debut']." +1 year"))<date('Y-m-d')){
									$lacouleur="bgcolor='#f80a3f'";
								}
								elseif(date('Y-m-d',strtotime($row['Date_Debut']." +1 year"))<date('Y-m-d',strtotime(date('Y-m-d')." +2 month"))){
									$lacouleur="bgcolor='#fcb98a'";
								}	
							}
							else{
								if(date('Y-m-d',strtotime($row['DateSurveillance']." +2 year"))<date('Y-m-d')){
									$lacouleur="bgcolor='#f80a3f'";
								}
								elseif(date('Y-m-d',strtotime($row['DateSurveillance']." +2 year"))<date('Y-m-d',strtotime(date('Y-m-d')." +2 month"))){
									$lacouleur="bgcolor='#fcb98a'";
								}
							}
							$styleIgore="";
							if($row['IgnorerSurveillance']==1){
								$Couleur="#5847ff";
								$styleIgore="style='color:#000000;'";
							}
							
							$qualifications="<table width='100%'>";
							$reqQ="SELECT
									new_competences_relation.Id,
									new_competences_qualification.Libelle AS Qualification,
									new_competences_relation.Date_Debut,
									Date_Surveillance AS DateSurveillance
								FROM
									new_competences_relation,
									new_competences_qualification
								WHERE
									new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
									AND new_competences_relation.Suppr = 0
									AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
									AND new_competences_qualification.Id_Categorie_Qualification=147
									AND new_competences_qualification.Libelle LIKE 'WA - Basic%'
									AND new_competences_relation.Id_Personne=".$row['Id_Personne']." 
								ORDER BY Date_Debut";
							$resultQ=mysqli_query($bdd,$reqQ);
							$nbQ=mysqli_num_rows($resultQ);
							if($nbQ>0){
								while($rowQ = mysqli_fetch_array($resultQ)){
									$qualifications.="
										<tr>
											<td width='50%'>".stripslashes($rowQ['Qualification'])."</td>
											<td width='25%'>".AfficheDateJJ_MM_AAAA($rowQ['Date_Debut'])."</td>
											<td width='25%'>".AfficheDateJJ_MM_AAAA($rowQ['DateSurveillance'])."</td>
										</tr>";	
								}
							}
							$qualifications.="</table>";
									
							$ligne = "
								<tr bgcolor='".$Couleur."' > ";
							
							$ligne.= "<td><a class='TableCompetences' ".$styleIgore." href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a></td>
									<td>".$row['Prestation']." ".$row['Pole']."</td>
									<td>".$qualifications."</td>
									<td>".AfficheDateJJ_MM_AAAA($row['Date_Debut'])."</td>
									<td ".$lacouleur." >".AfficheDateJJ_MM_AAAA($row['DateSurveillance'])."</td>
									<td>".$row['Statut']."</td>
									";
								if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
									if($row['Statut']=="" || $row['Statut']=="IGNORE"){
										$ligne .= "<td><a href='javascript:planifier(".$row['Id_Personne'].")'><img width='20px' src=\"../../Images/P.png\"></a></td>";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
								else{
									$ligne .= "<td></td>";
								}
								if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
									if($row['Statut']=="PLANIFIE"){
										$ligne .= "<td><a href='javascript:valider(".$row['Id_Personne'].")'><img width='20px' src=\"../../Images/Valider.png\"></a></td>";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
								else{
									$ligne .= "<td></td>";
								}
								if($row['Statut']=="" && $row['IgnorerSurveillance']==0){
									if(DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
										$ligne .= "<td><a href='javascript:ignorer(".$row['Id_Personne'].")'><img width='20px' src=\"../../Images/info2.png\"></a></td>\n";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
								else{
									if($row['Statut']=="VALIDE" && DroitsPrestationPole($TableauIdPostesCQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
										$ligne .= "<td><a href='javascript:ignorer(".$row['Id_Personne'].")'><img width='20px' src=\"../../Images/info2.png\"></a></td>\n";
									}
									else{
										$ligne .= "<td></td>";
									}
								}
							$ligne .= "</tr>\n";
							echo $ligne;
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
					}
				?>
			</table>		
		</td>
	</tr>
	<tr><td height='300px'></td></tr>
</table>
</form>
</html>