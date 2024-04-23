<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Besoin.js?time=<?php echo time();?>"></script>
<script language="javascript">
	function OuvreFenetreModif(Mode,Id,BesoinRaison)
	{
		Confirm=false;
		if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
		{
			if(Mode=="Suppr")
			{
				if(BesoinRaison==0){var w= window.open("Ajout_Besoin_Formation.php?Mode="+Mode+"&Id="+Id,"PageBesoinFormation","status=no,menubar=no,width=620,height=450");}
				else{var w= window.open("Supprimer_Besoin_Raison.php?Id="+Id,"PageBesoinFormation","status=no,menubar=no,width=620,height=200");}
			}
			else{var w= window.open("Ajout_Besoin_Formation.php?Mode="+Mode+"&Id="+Id,"PageBesoinFormation","status=no,menubar=no,width=620,height=450");}
			w.focus();
		}
	}
	
	function Inscrire(Id)
	{
		var w = window.open("InscrireSession.php?Id_Plateforme="+document.getElementById('plateforme').value+"&Id_Prestation="+document.getElementById('prestation').value+"&Id_Session="+Id,"PageSession","status=no,menubar=no,width=800,height=800");
		w.focus();
	}
	
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
	
	function ValiderCheck()
	{
		var elements = document.getElementsByClassName("check");
		Id="";
		ref="";
		for(var i=0, l=elements.length; i<l; i++)
		{
			if(elements[i].checked == true){Id+=elements[i].name+";";}
		}				
	}
	
	function SelectionnerToutPriseEC()
	{
		var elements = document.getElementsByClassName("checkEC");
		if (formulaire.selectAllPriseEC.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	
	function ValiderCheckEC()
	{
		var elements = document.getElementsByClassName("checkEC");
		Id="";
		ref="";
		for(var i=0, l=elements.length; i<l; i++)
		{
			if(elements[i].checked == true){Id+=elements[i].name+";";}
		}				
	}

	function updateSessions()
	{
		var target = $(".ListeSession");
		target.hide(); 
		bTrouve=0;
		for (var i = 0 ; i < target.length ; i++)
		{
			if(target[i].id.indexOf("Form"+document.getElementById('formation').value+";")>=0)
			{
				target[i].style.display="";
				bTrouve=1;
			}	  
		}
	}
	
	function Excel()
	{
		var w=window.open("Excel_Besoin.php?Type="+document.getElementById('Id_TypeFormation').value+"&Id_Personne="+document.getElementById('PersonneR').value+"&Etat="+document.getElementById('etatR').value+"&Formation="+document.getElementById('formationR').value,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
	
	function OuvreFenetreQCM(Id)
	{
		var w= window.open("QCM_SansSession.php?&Id_Besoin="+Id,"PageQCM","status=no,menubar=no,scrollbars=yes,width=800,height=400");
		w.focus();
	}
	function MettreCommentaire(Id)
	{
		var w= window.open("Mettre_Commentaire.php?Id="+Id,"PageCommentaire","status=no,menubar=no,width=400,height=200");
		w.focus();
	}
	function OuvreFenetreBesoinPersonne()
	{
		var w= window.open("Ajout_Besoin_Formation_Personne.php","PageBesoinFormation","status=no,menubar=no,width=900,height=450")
		w.focus();
	}
</script>
<?php
//Suppression des besoins qui ne font pas parti des prestations ouvertes de la personne 
$req="UPDATE form_besoin 
	SET Suppr=1,
        Motif_Suppr='Date de fin prestation dépassée'
	WHERE Suppr=0 
	AND (Valide=0 OR (Valide=1 AND (Traite=0 OR Traite=5))) 
	AND Id NOT IN (SELECT Id_Besoin FROM new_competences_relation WHERE Suppr=0 AND Evaluation<>'B')
	AND (SELECT COUNT(Id) 
		FROM new_competences_personne_prestation
		WHERE new_competences_personne_prestation.Id_Personne=form_besoin.Id_Personne
		AND new_competences_personne_prestation.Id_Prestation=form_besoin.Id_Prestation
		AND new_competences_personne_prestation.Id_Pole=form_besoin.Id_Pole
		AND new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
        )=0";
 $result=mysqli_query($bdd,$req);
 
 //Suppression des 'B' reliées à des besoins supprimés
$req = "
	UPDATE new_competences_relation
	SET Suppr=1
	WHERE new_competences_relation.Suppr=0
	AND new_competences_relation.Id_Besoin>0
	AND Evaluation='B' 
	AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
		OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
	)
	AND Id_Besoin IN (SELECT form_besoin.Id FROM form_besoin WHERE form_besoin.Suppr=1)";
$result = mysqli_query($bdd,$req);

 //Suppression des sessions sans formations liées à des besoins supprimés
$req = "
	UPDATE form_session_personne_qualification
	SET Suppr=1
	WHERE form_session_personne_qualification.Suppr=0
	AND form_session_personne_qualification.Id_Besoin>0
	AND Id_Besoin IN (SELECT form_besoin.Id FROM form_besoin WHERE Traite=0 AND form_besoin.Suppr=1)";
$result = mysqli_query($bdd,$req);

if($_POST)
{
	if(isset($_POST['Id_TypeFormation'])){$_SESSION['FiltreBesoin_Type']=$_POST['Id_TypeFormation'];}
	if(isset($_POST['FinValiditeQualifR'])){$_SESSION['FiltreBesoin_FinValiditeQualif']=$_POST['FinValiditeQualifR'];}
	if(isset($_POST['MotifR'])){$_SESSION['FiltreBesoin_Motif']=$_POST['MotifR'];}
	if(isset($_POST['PersonneR'])){$_SESSION['FiltreBesoin_Personne']=$_POST['PersonneR'];}
	if(isset($_POST['formationR']))
	{
	    if($_POST['formationR']==0){$_SESSION['FiltreBesoin_Formation']=0;}
		else{$_SESSION['FiltreBesoin_Formation']=$_POST['formationR'];}
	}
	if(isset($_POST['PrisEnCompteR'])){$_SESSION['FiltreBesoin_PrisEnCompte']=$_POST['PrisEnCompteR'];}
	if(isset($_POST['etatR'])){$_SESSION['FiltreBesoin_Etat']=$_POST['etatR'];}
}
if(isset($_GET['Tri']))
{
	$tab = array("LIBELLE_TYPEFORMATION","LIBELLE_FORMATION","Contrat","LIBELLE_PRESTATION","NOM_PRENOM","MOTIF_DEMANDE","DATE_DEMANDE","DateFinQualif");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriBesoins_General']= str_replace($tri." ASC,","",$_SESSION['TriBesoins_General']);
			$_SESSION['TriBesoins_General']= str_replace($tri." DESC,","",$_SESSION['TriBesoins_General']);
			$_SESSION['TriBesoins_General']= str_replace($tri." ASC","",$_SESSION['TriBesoins_General']);
			$_SESSION['TriBesoins_General']= str_replace($tri." DESC","",$_SESSION['TriBesoins_General']);
			if($_SESSION['TriBesoins_'.$tri]==""){$_SESSION['TriBesoins_'.$tri]="ASC";$_SESSION['TriBesoins_General'].= $tri." ".$_SESSION['TriBesoins_'.$tri].",";}
			elseif($_SESSION['TriBesoins_'.$tri]=="ASC"){$_SESSION['TriBesoins_'.$tri]="DESC";$_SESSION['TriBesoins_General'].= $tri." ".$_SESSION['TriBesoins_'.$tri].",";}
			else{$_SESSION['TriBesoins_'.$tri]="";}
		}
	}
}

$vert="#6fff55";
$orange="#ffe915";
$rouge="#ff151c";
$gris="#aaaaaa";
$blanc="#ffffff";
$etatR="";
Ecrire_Code_JS_Init_Date();

if($_POST)
{
	if(isset($_POST['ValiderBesoin']))
	{
		echo "<script>ValiderCheck()</script>";
		//Parcourir les checklists cochés
		foreach($_POST['Besoins'] as $valeur)
		{
            $req="UPDATE form_besoin SET Valide=1, Id_Valideur=".$IdPersonneConnectee." WHERE Id=".$valeur;
            $resultValide=mysqli_query($bdd,$req);
            
            //Ajout des B dans la gestion des compétences
            $ReqBesoin="SELECT Id_Personne, Id_Formation FROM form_besoin WHERE Id=".$valeur;
            $ResultBesoin=mysqli_query($bdd,$ReqBesoin);
            $RowBesoin=mysqli_fetch_array($ResultBesoin);
            Creer_B_Competences_PersonneFormation($RowBesoin['Id_Personne'], $RowBesoin['Id_Formation'], $valeur);
		}
	}
	elseif(isset($_POST['RefuserBesoin']))
	{
		echo "<script>ValiderCheck()</script>";
		foreach($_POST['Besoins'] as $valeur)
		{
            $req="UPDATE form_besoin SET Valide=-1, Id_Valideur=".$IdPersonneConnectee." WHERE Id=".$valeur;
            $resultValide=mysqli_query($bdd,$req);
		}
	}
	elseif(isset($_POST['SupprimerBesoin']))
	{
		echo "<script>ValiderCheckSuppr()</script>";
		if(isset($_POST['BesoinsSuppr'])){
			$_SESSION['Besoin_Suppr']=implode(";",$_POST['BesoinsSuppr']);
			echo '<script>var w= window.open("Supprimer_Besoin_Raisons.php","PageBesoinFormation","status=no,menubar=no,width=620,height=200");</script>';
		
		}
	}
	if(isset($_POST['PrendreEnCompte']))
	{
		echo "<script>ValiderCheckEC()</script>";
		//Parcourir les checklists cochés
		foreach($_POST['PriseEnCompte'] as $valeur)
		{
            $req="UPDATE form_besoin SET TraiteAF=1, Id_PersonneTraiteAF=".$IdPersonneConnectee.", Date_TraiteAF='".date('Y-m-d')."' WHERE Id=".$valeur;
            $resultTraite=mysqli_query($bdd,$req);
		}
	}
}

$requetePersonnes="
    SELECT
		Id_Personne
	FROM
		new_competences_personne_prestation
	WHERE
		Date_Fin>='".$DateJour."' 
		AND (
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN
			(
				SELECT Id_Plateforme
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS).")
			)
			OR CONCAT(Id_Prestation,'_',Id_Pole) IN
			(
				SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation
				WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
			)
		)
		
		";
$resultPersResp=mysqli_query($bdd,$requetePersonnes);
$nbPersResp=mysqli_num_rows($resultPersResp);
$listeRespPers=0;
if($nbPersResp>0)
{
	$listeRespPers="";
	while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPers.=$rowPersResp['Id_Personne'].",";}
	$listeRespPers=substr($listeRespPers,0,-1);
}

?>

<form id="formulaire" action="Liste_Besoin_Formation.php" method="post">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="4">
			<table class="GeneralPage"  style="width:100%; border-spacing : 0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Workflow des besoins";}else{echo "Needs workflow";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="8px"></td></tr>
	<tr>
		<td align="left" valign="top" width="15%">
			<br><br>
			<table class="GeneralInfo" style="border-spacing:0; width:98%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<tr>
					<td width="5%" align="right" >
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;Type</td>
				</tr>
				<tr>
					<td width="10%">
						<select name="Id_TypeFormation" id="Id_TypeFormation" onchange="submit()">
							<option value="0"></option>
							<?php
							$TypeForm=$_SESSION['FiltreBesoin_Type'];
							if($_POST){$TypeForm=$_POST['Id_TypeFormation'];}
							$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
							while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
							{
								$selected="";
								if($TypeForm<>"")
								{
									if($TypeForm==$rowTypeFormation['Id']){$selected="selected";}
								}
								echo "<option value='".$rowTypeFormation['Id']."' ".$selected.">".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation ";}else{echo "Training ";}?></td>
				</tr>
				<tr>
					<td width="10%">
						<?php
							
						?>
						<select name="formationR" id="formationR" style="width:200px" onchange="submit()">
							<option value="0"></option>
							<?php
							$formation=$_SESSION['FiltreBesoin_Formation'];
							if($_POST)
							{
								if(isset($_POST['formationR']))
								{
									if($_POST['formationR']==0){$formation=0;}
									else{$formation=$_POST['formationR'];}
								}
							} 
							
							$reqSuite="";
							if($TypeForm<>"" && $TypeForm<>"0"){
								$reqSuite="AND form_formation.Id_TypeFormation=".$TypeForm." ";
							}
							$requete="
									SELECT DISTINCT
										IF(Id_FormationEquivalente>0,Id_FormationEquivalente,Id_Formation) AS Id_Formation,
										IF(Id_FormationEquivalente>0,(SELECT Libelle FROM form_formationequivalente WHERE form_formationequivalente.Id=Id_FormationEquivalente),Libelle) AS Formation,
										IF(Id_FormationEquivalente>0,4,Recyclage) AS Recyclage
									FROM 
										(SELECT DISTINCT
											form_besoin.Id_Formation,
											IF(form_formation.Recyclage=1,IF(form_besoin.Motif='Renouvellement',1,0),3) AS Recyclage,
											 (
											 SELECT form_formationequivalente_formationplateforme.Id_FormationEquivalente
											 FROM form_formationequivalente_formationplateforme
											 WHERE form_formationequivalente_formationplateforme.Id_Formation=form_formation.Id 
											 AND form_formationequivalente_formationplateforme.Recyclage=IF(form_formation.Recyclage=1,IF(form_besoin.Motif='Renouvellement',1,0),0) 
											LIMIT 1
											) AS Id_FormationEquivalente,
											IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,form_formation_langue_infos.LibelleRecyclage,form_formation_langue_infos.Libelle) AS Libelle
											,(@row_number:=@row_number + 1) AS rnk
										FROM
											form_besoin,
											form_formation,
											form_formation_langue_infos,
											form_formation_plateforme_parametres
										WHERE
											form_besoin.Id_Formation=form_formation.Id
											
											AND form_formation_langue_infos.Id_Langue = form_formation_plateforme_parametres.Id_Langue 
											AND form_formation_langue_infos.Id_Formation = form_besoin.Id_Formation
											AND form_formation_langue_infos.Suppr=0

											AND form_formation_plateforme_parametres.Id_Formation = form_besoin.Id_Formation
											AND form_formation_plateforme_parametres.Id_Plateforme = (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
											AND form_formation_plateforme_parametres.Suppr = 0 
											
											AND form_besoin.Id_Personne IN
											(".$listeRespPers.")
											AND form_besoin.Suppr=0
											AND form_besoin.Traite=0 
											AND form_besoin.Valide>=0
											".$reqSuite."
										GROUP BY
											form_besoin.Id_Formation, IF(form_formation.Recyclage=1,IF(form_besoin.Motif='Renouvellement',1,0),3)
										) AS TAB
									GROUP BY 
										IF(Id_FormationEquivalente>0,Id_FormationEquivalente,Id_Formation), IF(Id_FormationEquivalente>0,4,Recyclage)
									ORDER BY 
										Formation
										";

							$resultForm=mysqli_query($bdd,$requete);
							while($rowForm=mysqli_fetch_array($resultForm))
							{
								$selected="";
								if($formation<>"")
								{
									if($formation==$rowForm['Id_Formation']."_".$rowForm['Recyclage']){$selected="selected";}
								}
								echo "<option value='".$rowForm['Id_Formation']."_".$rowForm['Recyclage']."' ".$selected.">";
								echo stripslashes($rowForm['Formation']);
								echo "</option>\n";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
				</tr>
				<tr>
					<td width="10%">
						<select name="PersonneR" id="PersonneR" style="width:150px" onchange="submit()">
							<option value="0"></option>
							<?php
							$PersonneR=$_SESSION['FiltreBesoin_Personne'];
							if($_POST){$PersonneR=$_POST['PersonneR'];}
							
							$requete="	SELECT DISTINCT
										form_besoin.Id_Personne,
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM
										form_besoin,
										new_rh_etatcivil
									WHERE
										form_besoin.Id_Personne=new_rh_etatcivil.Id
										AND form_besoin.Id_Personne IN
										(".$listeRespPers.")
    									AND form_besoin.Suppr=0
    									AND form_besoin.Traite=0 
    									AND form_besoin.Valide>=0
									ORDER BY
                                        Personne ";
							$resultPers=mysqli_query($bdd,$requete);
							while($rowPers=mysqli_fetch_array($resultPers))
							{
								$selected="";
								if($PersonneR<>"")
								{
								    if($PersonneR==$rowPers['Id_Personne']){$selected="selected";}
								}
								echo "<option value='".$rowPers['Id_Personne']."' ".$selected.">".stripslashes($rowPers['Personne'])."</option>\n";
							}
							?>
						</select>

					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?></td>
				</tr>
				<tr>
					<td width="10%">
						<select name="etatR" id="etatR" style="width:200px" onchange="submit()">
							<?php $etatR=$_SESSION['FiltreBesoin_Etat']; ?>
							<option value="" selected></option>
							<option value="AConfirmer" <?php if( $etatR=="AConfirmer"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Besoin à confirmer";}else{echo "Need to be confirmed";}?></option>
							<option value="Refuse" <?php if( $etatR=="Refuse"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Besoin refusé";}else{echo "Need refused";}?></option>
							<option value="Supprime" <?php if( $etatR=="Supprime"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Besoin supprimé";}else{echo "Need deleted";}?></option>
							<option value="Dispo" <?php if( $etatR=="Dispo"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Dates disponibles dans le planning ";}else{echo "Dates available in the schedule ";}?></option>
							<option value="PasDispo" <?php if( $etatR=="PasDispo"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Pas de date disponible ";}else{echo "No date available ";}?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;">
						<?php 
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH))
						{
							if($LangueAffichage=="FR"){echo "Besoins pris en compte";}else{echo "Needs taken into account";}
						} 
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?php 
						$PrisEnCompteR=$_SESSION['FiltreBesoin_PrisEnCompte'];
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH))
						{
						?>
							<select name="PrisEnCompteR" id="PrisEnCompteR" onchange="submit()">
							<option value="" selected></option>
							<option value="1" <?php if( $PrisEnCompteR=="1"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
							<option value="0" <?php if( $PrisEnCompteR=="0"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";} ?></option>
						</select>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Motif";}else{echo "Pattern";}?></td>
				</tr>
				<tr>
					<td width="10%">
						<?php  ?>
						<select name="MotifR" id="MotifR" style="width:100px" onchange="submit()">
							<?php $MotifR=$_SESSION['FiltreBesoin_Motif']; ?>
							<option value="" selected></option>
							<option value="Initial" <?php if( $MotifR=="Initial"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Initial";}else{echo "Initial";}?></option>
							<option value="Renouvellement" <?php if( $MotifR=="Renouvellement"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Renouvellement";}else{echo "Renewal";}?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date fin validité qualification";}else{echo "End date validity qualification";}?></td>
				</tr>
				<tr>
					<td width="10%">
						<select name="FinValiditeQualifR" id="FinValiditeQualifR" style="width:100px" onchange="submit()">
							<?php $FinValiditeQualifR=$_SESSION['FiltreBesoin_FinValiditeQualif']; ?>
							<option value="" selected></option>
							<option value="<=1mois" <?php if( $FinValiditeQualifR=="<=1mois"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "&#8804; 1 mois";}else{echo "&#8804; 1 month";}?></option>
							<option value=">1mois" <?php if( $FinValiditeQualifR==">1mois"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "> 1 mois";}else{echo "> 1 month";}?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER/Department/Subsidiary";}else{echo "UER/Dept/Filiale";} ?>&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td>
						<input type="checkbox" name="selectAllUER" id="selectAllUER" onclick="SelectionnerToutUER()" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
					</td>
				</tr>
				<tr>
					<td>
						<div id='Div_Plateforme' style='height:150px;width:200px;overflow:auto;'>
						<table>
					<?php
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
							$req="SELECT DISTINCT Id_Plateforme, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme
								FROM new_competences_prestation 
								WHERE new_competences_prestation.Active=0
								AND Id_Plateforme IN (
									SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Poste 
										IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteFormateur.") 
									AND Id_Personne=".$IdPersonneConnectee." 
								)
								ORDER BY Plateforme";
						}
						else{
							$req="SELECT DISTINCT Id_Plateforme, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme
								FROM new_competences_prestation 
								WHERE (SELECT COUNT(Id)
									FROM new_competences_personne_poste_prestation
									WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
									AND Id_Personne=".$IdPersonneConnectee." 
									AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0
								AND new_competences_prestation.Active=0
								AND Active=0
								ORDER BY Plateforme";
						}
						$resultPlate=mysqli_query($bdd,$req);
						$nbPlate=mysqli_num_rows($resultPlate);
						$i=0;
						if ($nbPlate > 0)
						{
							while($row=mysqli_fetch_array($resultPlate))
							{
								$selected="";
								if($_POST && !isset($_POST['btnReset2'])){
									if(isset($_POST['plateforme'.$row['Id_Plateforme']])){$selected="checked";}
								}
								else{
									$selected="checked";
								}
								echo "<tr><td><input class='checkUER' type='checkbox' ".$selected." value='".$row['Id_Plateforme']."' onclick=\"Selectionner('Presta',".$row['Id_Plateforme'].")\" id='plateforme".$row['Id_Plateforme']."' name='plateforme".$row['Id_Plateforme']."'>".stripslashes($row['Plateforme'])."</td></tr>";
							}
						}
						 
						$listePlateforme="";
						if ($nbPlate > 0)
						{
							mysqli_data_seek($resultPlate,0);
							while($row=mysqli_fetch_array($resultPlate))
							{
								if($_POST && !isset($_POST['btnReset2'])){
									if(isset($_POST['plateforme'.$row['Id_Plateforme']])){
										if($listePlateforme<>""){$listePlateforme.=",";}
										$listePlateforme.=$row['Id_Plateforme'];
									}
								}
							}
						}
						$_SESSION['FiltreRECORD_UER']=$listePlateforme;
					?>
						</table>
						</div>
					</td>
				</tr>
				<tr>
					<td class="Libelle" style="background-color:#B2AE9F;" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><input type="checkbox" name="selectAllPresta" id="selectAllPresta" onclick="SelectionnerToutPresta()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
				</tr>
				<tr>
					<td>
						<div id='Div_Prestations' style="height:100px;overflow:auto;">
							<table width='100%'>
								<?php
									if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS)){
										$rqPrestation="SELECT Id AS Id_Prestation, 
											Id_Plateforme,
											Libelle,
											0 AS Id_Pole,
											'' AS Pole
											FROM new_competences_prestation 
											WHERE Id NOT IN (
												SELECT Id_Prestation
												FROM new_competences_pole
												WHERE Actif=0
											)
											AND new_competences_prestation.Active=0
											AND Id_Plateforme IN (
												SELECT Id_Plateforme
												FROM new_competences_personne_poste_plateforme 
												WHERE Id_Poste 
													IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteFormateur.") 
												AND Id_Personne=".$IdPersonneConnectee." 
											)
											
											UNION
											
											SELECT Id_Prestation,
											new_competences_prestation.Id_Plateforme,
											new_competences_prestation.Libelle,
											new_competences_pole.Id AS Id_Pole,
											CONCAT(' - ',new_competences_pole.Libelle) AS Pole
											FROM new_competences_pole
											INNER JOIN new_competences_prestation
											ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
											AND new_competences_pole.Actif=0
											AND new_competences_prestation.Active=0
											AND Id_Plateforme IN (
												SELECT Id_Plateforme
												FROM new_competences_personne_poste_plateforme 
												WHERE Id_Poste 
													IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteFormateur.") 
												AND Id_Personne=".$IdPersonneConnectee." 
											)
											ORDER BY Libelle, Pole";
									}
									else{
										$rqPrestation="SELECT Id AS Id_Prestation, 
											Id_Plateforme,
											Libelle,
											0 AS Id_Pole,
											'' AS Pole
											FROM new_competences_prestation 
											WHERE Id NOT IN (
												SELECT Id_Prestation
												FROM new_competences_pole 
												WHERE Actif=0   
											)
											AND (SELECT COUNT(Id)
												FROM new_competences_personne_poste_prestation
												WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
												AND Id_Personne=".$IdPersonneConnectee." 
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0
											AND new_competences_prestation.Active=0
											AND Active=0
											
											UNION
											
											SELECT Id_Prestation,
											new_competences_prestation.Id_Plateforme,
											new_competences_prestation.Libelle,
											new_competences_pole.Id AS Id_Pole,
											CONCAT(' - ',new_competences_pole.Libelle) AS Pole
											FROM new_competences_pole
											INNER JOIN new_competences_prestation
											ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
											WHERE (SELECT COUNT(Id)
												FROM new_competences_personne_poste_prestation
												WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
												AND Id_Personne=".$IdPersonneConnectee." 
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id)>0
											AND new_competences_pole.Actif=0
											AND new_competences_prestation.Active=0
											AND Active=0
											AND Actif=0
											ORDER BY Libelle, Pole";
									}
									$resultPrestation=mysqli_query($bdd,$rqPrestation);
									$Id_PrestationPole=0;
									
									$PrestaR=$_SESSION['FiltreBesoin_Prestation'];
									$NouveauPresta=0;
									if($PrestaR<>""){$NouveauPresta=1;}
									if($_POST){
										$PrestaR="";
									}
									$i=0;
									while($rowPrestation=mysqli_fetch_array($resultPrestation))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_Presta']) ? $_POST['Id_Presta'] : array();
											foreach($checkboxes as $value) {
												if($rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']==$value){
													$checked="checked";
													if($PrestaR<>""){$PrestaR.=",";}
													$PrestaR.="'".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."'";
												}
											}
										}
										else{
												
											if($NouveauPresta==0){
												$checked="checked";
												if($PrestaR<>""){$PrestaR.=",";}
												$PrestaR.="'".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."'";
											}
											else{
												$checkboxes = explode(",",$PrestaR);
												foreach($checkboxes as $value) {
													if("'".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."'"==$value){
														$checked="checked";
													}
												}
											}											
										}
										$_SESSION['FiltreBesoin_Prestation']=$PrestaR;
										echo "<tr><td>";
										echo "<input type='checkbox' class='checkPresta' name='Id_Presta[]' Id='Id_Presta[]' value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$checked.">".stripslashes(substr($rowPrestation['Libelle'],0,7).$rowPrestation['Pole']);
										echo "</td></tr>";
										echo "<script>tabPresta[".$i."]= new Array('".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."','".$rowPrestation['Id_Plateforme']."');</script>";
										$i++;
									}
								?>
							</table>
						</div>
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
			<tr <?php if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==0){echo "style='display:none;'";} ?>>
				<td class="Libelle" width="10%" ><?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> : </td>
			</tr>
			<tr <?php if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==0){echo "style='display:none;'";} ?>>
				<td>
					<div id='Div_RespProjet' style="height:100px;overflow:auto;">
						<table width='100%'>
							<?php
								$Id_RespProjet=$_SESSION['FiltreBesoin_RespProjet'];
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
									$_SESSION['FiltreBesoin_RespProjet']=$Id_RespProjet;
									
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
										$checkboxes = explode(',',$_SESSION['FiltreBesoin_RespProjet']);
										foreach($checkboxes as $value) {
											if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
										}
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
									echo "</td></tr>";
								}	
							?>
						</table>
					</div>
				</td>
			</tr>
				<tr>
					<td width="5%" align="center">
						<input class="Bouton" id="BtnRechercher" name="BtnRechercher" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Rechercher";}else{echo "Search";}?>">
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
			</table>
		</td>

		<td width="85%" valign="top" align="center">
			<table style="width:100%;">
				<tr>
					<td align="center">
						<table>
							<tr>
								<td style="font-weight:bold;border-radius: 8px;padding: 2px;background-color:<?php echo $vert; if($etatR=="Refuse" || $etatR=="Supprime"){echo ";display:none;";} ?>" align="center">
									<?php
										if($LangueAffichage=="FR"){echo "Dates disponibles dans le planning ";}
										else{echo "Dates available in the schedule ";}
									?>
									<div id="compteurDispo"></div>
								</td>
								<td style="font-weight:bold;border-radius: 8px;padding: 2px;background-color:<?php echo $orange; if($etatR=="Refuse" || $etatR=="Supprime"){echo ";display:none;";} ?>" align="center">
									<?php
										if($LangueAffichage=="FR"){echo "Pas de date disponible ";}
										else{echo "No date available ";}
									?>
									<div id="compteurPasDispo"></div>
								</td>
								<td style="font-weight:bold;border-radius: 8px;padding: 2px;background-color:<?php echo $rouge; if($etatR=="Refuse" || $etatR=="Supprime"){echo ";display:none;";} ?>" align="center">
									<?php
										if($LangueAffichage=="FR"){echo "Besoin à confirmer ";}
										else{echo "Need to be confirmed ";}
									?>
									<div id="compteurAConfirmer"></div>
								</td>
								<td style="font-weight:bold;border-radius: 8px;padding: 2px;background-color:<?php echo $gris; if($etatR<>"Refuse"){echo ";display:none;";} ?>" align="center">
									<?php
										if($LangueAffichage=="FR"){echo "Besoin refusé ";}
										else{echo "Need refused ";}
									?>
									<div id="compteurRefuse"></div>
								</td>
								<td style="font-weight:bold;border-radius: 8px;padding: 2px;background-color:<?php echo $blanc; if($etatR<>"Supprime"){echo ";display:none;";}?>" align="center">
									<?php
										if($LangueAffichage=="FR"){echo "Besoin supprimé ";}
										else{echo "Need deleted ";}
									?>
									<div id="compteurSupprime"></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
				<?php
					//Requete pour calcul de l'état de chaque besoin
					$requete="	SELECT
									form_besoin.Etat,
									form_besoin.Id AS ID_BESOIN,
									form_besoin.Id_Formation AS ID_FORMATION,
									form_formation.Recyclage AS RECYCLAGE_IDENTIQUE,
									form_besoin.Id_Personne,
									form_besoin.Id_Prestation,
									form_besoin.Id_Pole,
									form_besoin.Valide AS VALIDE,
									form_besoin.TraiteAF,
									new_competences_prestation.Id_Plateforme,
									IF(form_besoin.Motif='Renouvellement',1,0) AS Recyclage							
								FROM
									form_besoin,
									form_formation,
									new_competences_prestation
								WHERE
									form_besoin.Id_Formation=form_formation.Id
									AND form_besoin.Id_Prestation=new_competences_prestation.Id
									AND form_besoin.Id_Personne IN
									(".$listeRespPers.")
									AND form_besoin.Suppr=0
									AND form_besoin.Traite=0 ";
					    if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS) && !DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
                        {
                            if(!DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne, $IdPosteAssistantFormationInterne, $IdPosteResponsableFormation,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteFormateur))){
								$requete.="AND form_besoin.Valide=1 ";
							}
						}
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS) && DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
						{
                            if(!DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne, $IdPosteAssistantFormationInterne, $IdPosteResponsableFormation,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteFormateur)))
						    {
								$requete.="
                                    AND
                                   (
                                        form_besoin.Valide=1 
    							        OR CONCAT(form_besoin.Id_Prestation,' ',form_besoin.Id_Pole) 
    									IN
                                        (
                                            SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,' ',new_competences_personne_poste_prestation.Id_Pole) 
    										FROM new_competences_personne_poste_prestation
    										WHERE Id_Personne=".$IdPersonneConnectee."
    										AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
    									)
    					           ) ";
							 }
							 else
							 {
								 $requete.="
                                    AND
                                   (
                                        form_besoin.Valide IN (0,1,-1) 
    							        OR CONCAT(form_besoin.Id_Prestation,' ',form_besoin.Id_Pole) 
    									IN
                                        (
                                            SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,' ',new_competences_personne_poste_prestation.Id_Pole) 
    										FROM new_competences_personne_poste_prestation
    										WHERE Id_Personne=".$IdPersonneConnectee."
    										AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
    									)
    					           ) ";
							 }
						}
						
						if($TypeForm>0){$requete.="AND form_formation.Id_TypeFormation=".$TypeForm." ";}
						if($PrestaR<>"")
						{
							$requetePersonnesPrestaR="
								SELECT
									Id_Personne
								FROM
									new_competences_personne_prestation
								WHERE
									Date_Fin>='".$DateJour."' 
								AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$PrestaR.") ";
							$resultPersResp=mysqli_query($bdd,$requetePersonnesPrestaR);
							$nbPersResp=mysqli_num_rows($resultPersResp);
							$listeRespPersPrestaR=0;
							if($nbPersResp>0)
							{
								$listeRespPersPrestaR="";
								while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPersPrestaR.=$rowPersResp['Id_Personne'].",";}
								$listeRespPersPrestaR=substr($listeRespPersPrestaR,0,-1);
							}

							$requete.="AND form_besoin.Id_Personne IN (".$listeRespPersPrestaR.") ";
						}
						if($MotifR=="Renouvellement")
						{
							$requete.="AND form_besoin.Motif LIKE '".$MotifR."' ";
						}
						elseif($MotifR=="Initial")
						{
							$requete.="AND form_besoin.Motif <> 'Renouvellement' ";
						}
						if($PersonneR>0){$requete.="AND form_besoin.Id_Personne=".$PersonneR." ";}
						if($PrisEnCompteR<>""){$requete.="AND form_besoin.TraiteAF=".$PrisEnCompteR." ";}
						if($formation>0)
						{
							$tabForm=explode("_",$formation);
							if($tabForm[1]==0 || $tabForm[1]==1){
								$requete.="AND form_besoin.Id_Formation=".$tabForm[0]." 
										AND IF(form_besoin.Motif='Renouvellement',1,0)=".$tabForm[1]." ";
							}
							elseif($tabForm[1]==3){
								$requete.="AND form_besoin.Id_Formation=".$tabForm[0]." 
										AND IF(form_besoin.Motif='Renouvellement',1,0) IN (0,1) ";
							}
							elseif($tabForm[1]==4){
								$requete.="AND 
										IF(form_formation.Recyclage=1,
											CONCAT(form_besoin.Id_Formation,IF(form_besoin.Motif='Renouvellement',1,0)) IN (SELECT CONCAT(Id_Formation,Recyclage) FROM form_formationequivalente_formationplateforme
										WHERE Id_FormationEquivalente=".$tabForm[0]."),
											form_besoin.Id_Formation IN (SELECT Id_Formation FROM form_formationequivalente_formationplateforme
										WHERE Id_FormationEquivalente=".$tabForm[0].")
										)";
							}
						}
						$resultCalcul=mysqli_query($bdd,$requete);
						$nbCalcul=mysqli_num_rows($resultCalcul);
						if($nbCalcul>0)
						{
							while($row=mysqli_fetch_array($resultCalcul))
							{
								switch($row['VALIDE'])
								{
									case -1: 
										if($row['Etat']<>'Refuse')
										{
											$reqUpdt="UPDATE form_besoin SET Etat='Refuse' WHERE Id=".$row['ID_BESOIN']." ";
											$resultUpdt=mysqli_query($bdd,$reqUpdt);
										}
										break;
									case 0: 
										if($row['Etat']<>'AConfirmer')
										{
											$reqUpdt="UPDATE form_besoin SET Etat='AConfirmer' WHERE Id=".$row['ID_BESOIN']." ";
											$resultUpdt=mysqli_query($bdd,$reqUpdt);
										}
										break;
									case 1: 
										$Etat="PasDispo";
										$Recyl=0;
										if($row['Recyclage']=="1"){$Recyl=1;}
										if($row['RECYCLAGE_IDENTIQUE']==0){$Recyl=0;}
										//Rajouter en fonction du planning la couleur orange ou verte
										$reqF="
											SELECT form_session_date.Id AS Id_SessionDate, 
												form_session_date.Id_Session, 
												form_session_date.DateSession,
												form_session.Id_GroupeSession,
												form_session.Id_Formation,
												form_session.Formation_Liee,
												form_session.Nb_Stagiaire_Maxi,
												form_session.Recyclage 
											FROM form_session_date 
												LEFT JOIN form_session 
												ON form_session_date.Id_Session=form_session.Id 
											WHERE form_session_date.Suppr=0 
												AND form_session_date.DateSession>'".date('Y-m-d')."' 
												AND form_session.Suppr=0 
												AND form_session.Annule=0 
												AND form_session.Diffusion_Creneau=1 
												AND form_session_date.Id_Session IN 
													(SELECT Id_Session FROM form_session_prestation WHERE Suppr=0 AND Id_Prestation=".$row['Id_Prestation'].") 
												AND ( 
													(form_session.Id_Formation=".$row['ID_FORMATION']." AND form_session.Recyclage=".$Recyl.") OR ";
										$reqSimil="SELECT Id_FormationEquivalente  
													FROM form_formationequivalente_formationplateforme 
													LEFT JOIN form_formationequivalente 
													ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
													WHERE form_formationequivalente.Id_Plateforme=".$row['Id_Plateforme']." 
													AND form_formationequivalente_formationplateforme.Id_Formation=".$row['ID_FORMATION']."
													AND form_formationequivalente_formationplateforme.Recyclage=".$Recyl;
										$resultSimil=mysqli_query($bdd,$reqSimil);
										$nbSimil=mysqli_num_rows($resultSimil);
										if($nbSimil>0)
										{
											while($rowSimil=mysqli_fetch_array($resultSimil))
											{
												$reqSimil2="SELECT Id_Formation, Recyclage   
													FROM form_formationequivalente_formationplateforme 
													LEFT JOIN form_formationequivalente 
													ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
													WHERE form_formationequivalente_formationplateforme.Id_FormationEquivalente=".$rowSimil['Id_FormationEquivalente']." ";
												$resultSimil2=mysqli_query($bdd,$reqSimil2);
												$nbSimil2=mysqli_num_rows($resultSimil2);
												if($nbSimil2>0)
												{
													while($rowSimil2=mysqli_fetch_array($resultSimil2))
													{
														$reqF.=" ( form_session.Id_Formation=".$rowSimil2['Id_Formation']." AND form_session.Recyclage=".$rowSimil2['Recyclage'].") OR ";
													}
												}
											}
										}
										$reqF=substr($reqF,0,-3);
										$reqF.=") ";
										$resultSession=mysqli_query($bdd,$reqF);
										$nbSession=mysqli_num_rows($resultSession);
										if($nbSession>0)
										{
											$bOK=true;
											$PlacesRestante=false;
											while($rowSessionDate=mysqli_fetch_array($resultSession))
											{
												//Vérifier si places restantes
												$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSessionDate['Id_Session'];
												$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
												$nbInscrit=mysqli_num_rows($resultNbInscrit);
												
												if($rowSessionDate['Nb_Stagiaire_Maxi']>$nbInscrit){$PlacesRestante=true;}
												//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
												$req="SELECT form_session_date.Id 
													FROM form_session_date 
														LEFT JOIN form_session 
														ON form_session_date.Id_Session=form_session.Id 
													WHERE form_session_date.DateSession<='".date('Y-m-d')."' 
													AND form_session_date.Suppr=0 AND form_session.Suppr=0 
													AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 
													AND form_session_date.Id_Session=".$rowSessionDate['Id_Session'];
												$resultDepasse=mysqli_query($bdd,$req);
												$nbDepasse=mysqli_num_rows($resultDepasse);
												if($nbDepasse>0){$bOK=false;}
											}
											if($bOK==true && $PlacesRestante==true){$Etat="Dispo";}
										}
										if($row['Etat']<>$Etat)
										{
											$reqUpdt="UPDATE form_besoin SET Etat='".$Etat."' WHERE Id=".$row['ID_BESOIN']." ";
											$resultUpdt=mysqli_query($bdd,$reqUpdt);
										}
										break;
								}
							}
						}
						
						//Requete affichage
						$requeteAnalyse="	SELECT form_besoin.Id AS ID_BESOIN ";
						$requete="	SELECT
										form_besoin.Id AS ID_BESOIN,
										form_besoin.Suppr,
										form_besoin.RaisonSuppression,
										form_besoin.Motif_Suppr,
										form_besoin.Date_MAJ,
										form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
										form_besoin.Id_Formation AS ID_FORMATION,
										form_formation.Reference AS REFERENCE_FORMATION,
										form_formation.Recyclage AS RECYCLAGE_IDENTIQUE,
										form_formation_langue_infos.Libelle AS LIBELLE_FORMATION,
										new_competences_prestation.Libelle AS LIBELLE_PRESTATION,
										(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) AS Pole,
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOM_PRENOM,
										form_besoin.Id_Personne,
										(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne) 
										FROM new_competences_personne_poste_prestation
										WHERE Backup=0 AND Id_Poste=".$IdPosteChefEquipe."
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
										AND new_competences_personne_poste_prestation.Id_Pole=form_besoin.Id_Pole
										LIMIT 1
										) AS N1,
										(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne) 
										FROM new_competences_personne_poste_prestation
										WHERE Backup=0 AND Id_Poste=".$IdPosteCoordinateurEquipe."
										AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
										AND new_competences_personne_poste_prestation.Id_Pole=form_besoin.Id_Pole
										LIMIT 1
										) AS N2,
										form_besoin.CommentaireCE,
										form_besoin.Motif AS MOTIF_DEMANDE,
										form_besoin.Date_Demande AS DATE_DEMANDE,
										form_besoin.Commentaire AS COMMENTAIRE,
										form_besoin.Id_Prestation,
										form_besoin.Id_Pole,
										form_besoin.Valide AS VALIDE,
										form_besoin.TraiteAF,
										(
										SELECT
											new_competences_relation.Date_Fin
										FROM new_competences_relation
										WHERE
											new_competences_relation.Id_Personne=form_besoin.Id_Personne
											AND new_competences_relation.Type='Qualification'
											AND new_competences_relation.Visible=0
											AND new_competences_relation.Suppr=0
											AND new_competences_relation.Id_Qualification_Parrainage IN 
											(
												SELECT form_formation_qualification.Id_Qualification
												FROM form_formation_qualification
												WHERE form_formation_qualification.Id_Formation=form_besoin.Id_Formation
												AND form_formation_qualification.Suppr=0
											)
										ORDER BY
											Date_QCM DESC, Date_Fin DESC
											LIMIT 1
										) AS DateFinQualif,
										new_competences_prestation.Id_Plateforme,
										form_formation_plateforme_parametres.Id_Langue, 
										(SELECT Libelle FROM form_organisme WHERE Id=form_formation_plateforme_parametres.Id_Organisme) AS Organisme,
										form_besoin.Obligatoire,
										form_besoin.EmisParAF, 
										IF(form_besoin.Motif='Renouvellement',1,0) AS Recyclage,
										form_formation_plateforme_parametres.Id_Organisme,
										IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,form_formation_langue_infos.LibelleRecyclage,form_formation_langue_infos.Libelle) AS Libelle,
										form_besoin.Etat
											";
							$requeteV2="SELECT
										form_besoin.Id_Formation AS ID_FORMATION,
										form_formation.Recyclage AS RECYCLAGE_IDENTIQUE,
										form_besoin.Id_Prestation,
										form_besoin.Valide AS VALIDE,
										form_besoin.Motif AS MOTIF_DEMANDE,
										new_competences_prestation.Id_Plateforme
											";
								$req2="FROM
										form_besoin,
										form_typeformation,
										form_formation,
										form_formation_langue_infos,
										new_rh_etatcivil,
										new_competences_prestation,
										form_formation_plateforme_parametres
									WHERE
										form_besoin.Id_Formation=form_formation.Id
										AND form_formation.Id_TypeFormation=form_typeformation.Id
										AND form_besoin.Id_Prestation=new_competences_prestation.Id
										AND form_besoin.Id_Personne=new_rh_etatcivil.Id

										AND form_formation_langue_infos.Id_Langue = form_formation_plateforme_parametres.Id_Langue 
										AND form_formation_langue_infos.Id_Formation = form_besoin.Id_Formation
										AND form_formation_langue_infos.Suppr=0

										AND form_formation_plateforme_parametres.Id_Formation = form_besoin.Id_Formation
										AND form_formation_plateforme_parametres.Id_Plateforme = new_competences_prestation.Id_Plateforme 
										AND form_formation_plateforme_parametres.Suppr = 0 

										AND form_besoin.Id_Personne IN
										(".$listeRespPers.")
										AND form_besoin.Traite=0 ";
										if($etatR<>"Supprime"){
											$req2.="AND form_besoin.Suppr=0 ";
										}
							if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH) && !DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
							{
							    if(!DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne, $IdPosteAssistantFormationInterne, $IdPosteResponsableFormation,$IdPosteFormateur)))
								{
									$req2.="AND (form_besoin.Valide IN (1,-1) ) ";
								}
							}
							if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH) && DroitsFormationPrestation($TableauIdPostesRespPresta_CQ))
							{
							    if(!DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne, $IdPosteAssistantFormationInterne, $IdPosteResponsableFormation,$IdPosteFormateur)))
							    {
								$req2.="AND (form_besoin.Valide IN (1,-1) 
										OR CONCAT(form_besoin.Id_Prestation,' ',form_besoin.Id_Pole) 
											IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,' ',new_competences_personne_poste_prestation.Id_Pole) 
												FROM new_competences_personne_poste_prestation
												WHERE Id_Personne=".$IdPersonneConnectee."
												AND Id_Poste IN 
													(".$IdPosteChefEquipe.",
													".$IdPosteCoordinateurEquipe.",
													".$IdPosteCoordinateurProjet.",
													".$IdPosteResponsableProjet.",
													".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite."
													)
											)
								
								) ";
								}
								else
								{
									$req2.="AND (form_besoin.Valide IN (0,1,-1) 
										OR CONCAT(form_besoin.Id_Prestation,' ',form_besoin.Id_Pole) 
											IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,' ',new_competences_personne_poste_prestation.Id_Pole) 
												FROM new_competences_personne_poste_prestation
												WHERE Id_Personne=".$IdPersonneConnectee."
												AND Id_Poste IN 
													(".$IdPosteChefEquipe.",
													".$IdPosteCoordinateurEquipe.",
													".$IdPosteCoordinateurProjet.",
													".$IdPosteResponsableProjet.",
													".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite."
													)
											)
								
								) ";
								}
							}
							if($TypeForm>0){$req2.="AND form_formation.Id_TypeFormation=".$TypeForm." ";}
							if($PrestaR<>"")
							{
								$requetePersonnesPrestaR="
									SELECT
										Id_Personne
									FROM
										new_competences_personne_prestation
									WHERE
										Date_Fin>='".$DateJour."' 
									AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$PrestaR.") ";
								$resultPersResp=mysqli_query($bdd,$requetePersonnesPrestaR);
								$nbPersResp=mysqli_num_rows($resultPersResp);
								$listeRespPersPrestaR=0;
								if($nbPersResp>0)
								{
									$listeRespPersPrestaR="";
									while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPersPrestaR.=$rowPersResp['Id_Personne'].",";}
									$listeRespPersPrestaR=substr($listeRespPersPrestaR,0,-1);
								}

								$req2.="AND form_besoin.Id_Personne IN (".$listeRespPersPrestaR.") ";
							}
							
							if($_SESSION['FiltreBesoin_RespProjet']<>""){
								$requetePersonnesPrestaR="
									SELECT
										Id_Personne
									FROM
										new_competences_personne_prestation
									WHERE
										Date_Fin>='".$DateJour."' 
									AND CONCAT(Id_Prestation,'_',Id_Pole) IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
												FROM new_competences_personne_poste_prestation
												WHERE Id_Personne IN (".$_SESSION['FiltreBesoin_RespProjet'].")
												AND Id_Poste IN (".$IdPosteResponsableProjet.")
											) ";
								$resultPersResp=mysqli_query($bdd,$requetePersonnesPrestaR);
								$nbPersResp=mysqli_num_rows($resultPersResp);
								$listeRespPersPrestaR=0;
								if($nbPersResp>0)
								{
									$listeRespPersPrestaR="";
									while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPersPrestaR.=$rowPersResp['Id_Personne'].",";}
									$listeRespPersPrestaR=substr($listeRespPersPrestaR,0,-1);
								}

								$req2.="AND form_besoin.Id_Personne IN (".$listeRespPersPrestaR.") ";
							}
				

							if($MotifR=="Renouvellement")
							{
								$req2.="AND form_besoin.Motif LIKE '".$MotifR."' ";
							}
							elseif($MotifR=="Initial")
							{
								$req2.="AND form_besoin.Motif <> 'Renouvellement' ";
							}
							
							if($PersonneR>0){$req2.="AND form_besoin.Id_Personne=".$PersonneR." ";}
							if($PrisEnCompteR<>""){$req2.="AND form_besoin.TraiteAF=".$PrisEnCompteR." ";}
							if($formation>0)
							{
								$tabForm=explode("_",$formation);
								if($tabForm[1]==0 || $tabForm[1]==1){
									$req2.="AND form_besoin.Id_Formation=".$tabForm[0]." 
											AND IF(form_besoin.Motif='Renouvellement',1,0)=".$tabForm[1]." ";
								}
								elseif($tabForm[1]==3){
									$req2.="AND form_besoin.Id_Formation=".$tabForm[0]." 
											AND IF(form_besoin.Motif='Renouvellement',1,0) IN (0,1) ";
								}
								elseif($tabForm[1]==4){
									
									$req2.="AND 
											IF(form_formation.Recyclage=1,
												CONCAT(form_besoin.Id_Formation,IF(form_besoin.Motif='Renouvellement',1,0)) IN (SELECT CONCAT(Id_Formation,Recyclage) FROM form_formationequivalente_formationplateforme
											WHERE Id_FormationEquivalente=".$tabForm[0]."),
												form_besoin.Id_Formation IN (SELECT Id_Formation FROM form_formationequivalente_formationplateforme
											WHERE Id_FormationEquivalente=".$tabForm[0].")
											)";
								}
							}
							if($etatR<>""){
								if($etatR<>"Supprime"){
									$req2.="AND form_besoin.Etat='".$etatR."' ";
								}
								else{
									$req2.="AND form_besoin.Suppr=1 ";
								}
							}
							else{$req2.="AND form_besoin.Etat<>'Refuse' ";}
							
							if($FinValiditeQualifR<>""){
								if($FinValiditeQualifR=="<=1mois"){$lareq="<='".date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))."'";}
								else{$lareq=">'".date('Y-m-d',strtotime(date('Y-m-d')." +1 month"))."'";}
								
								$req2.="
									AND
									(
									SELECT
										new_competences_relation.Date_Fin
									FROM new_competences_relation
									WHERE
										new_competences_relation.Id_Personne=form_besoin.Id_Personne
										AND new_competences_relation.Type='Qualification'
										AND new_competences_relation.Visible=0
										AND new_competences_relation.Suppr=0
										AND new_competences_relation.Id_Qualification_Parrainage IN 
										(
											SELECT form_formation_qualification.Id_Qualification
											FROM form_formation_qualification
											WHERE form_formation_qualification.Id_Formation=form_besoin.Id_Formation
											AND form_formation_qualification.Suppr=0
										)
									ORDER BY
										Date_QCM DESC, Date_Fin DESC
										LIMIT 1
									)".$lareq." ";
							}
							
						$result=mysqli_query($bdd,$requeteAnalyse.$req2);
						$nbenreg=mysqli_num_rows($result);
						
						$nombreDePages=ceil($nbenreg/50);
						if(isset($_GET['Page'])){$_SESSION['FORM_BESOINFORMATION_Page']=$_GET['Page'];}
						else{$_SESSION['FORM_BESOINFORMATION_Page']=0;}
						
						$req3="";
						if($_SESSION['TriBesoins_General']<>""){$req3=" ORDER BY ".substr($_SESSION['TriBesoins_General'],0,-1);}
						
						$req4=" LIMIT ".($_SESSION['FORM_BESOINFORMATION_Page']*50).",50";
						$result=mysqli_query($bdd,$requete.$req2.$req3.$req4);
						$nbenreg=mysqli_num_rows($result);

						$nbPage=0;
						if($_SESSION['FORM_BESOINFORMATION_Page']>1){echo "<b> <a style='color:#00599f;font-size:18px;' href='Liste_Besoin_Formation.php?Page=0'><<</a> </b>";}
						$valeurDepart=1;
						if($_SESSION['FORM_BESOINFORMATION_Page']<=5){$valeurDepart=1;}
						elseif($_SESSION['FORM_BESOINFORMATION_Page']>=($nombreDePages-6)){$valeurDepart=$nombreDePages-6;}
						else{$valeurDepart=$_SESSION['FORM_BESOINFORMATION_Page']-5;}
						for($i=$valeurDepart; $i<=($valeurDepart+9); $i++)
						{
							if($i<=$nombreDePages)
							{
								if($i==($_SESSION['FORM_BESOINFORMATION_Page']+1)){echo "<b style='font-size:18px;'> [ ".$i." ] </b>";}	
								else{echo "<b> <a style='color:#00599f;font-size:18px;' href='Liste_Besoin_Formation.php?Page=".($i-1)."'>".$i."</a> </b>";}
							}
						}
						if($_SESSION['FORM_BESOINFORMATION_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;font-size:18px;' href='Liste_Besoin_Formation.php?Page=".($nombreDePages-1)."'>>></a> </b>";}

						$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
									FROM form_formation_langue_infos 
									WHERE Suppr=0";
						$resultFormLangue=mysqli_query($bdd,$reqLangue);
						$nbFormLangue=mysqli_num_rows($resultFormLangue);	
				?>
					</td>
				</tr>
				<tr>
					<td style="width:100%;" valign="top" align="center">
						<table class="TableCompetences" style="width:100%;">
							<tr>
								<?php if(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ)){ ?>
								<td class="EnTeteTableauCompetences" width="5%"></td>
								<?php } ?>
								<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){ ?>
								<td class="EnTeteTableauCompetences" width="4%">
									<input class="Bouton" style="cursor: pointer;" name="PrendreEnCompte" size="3" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>"><br>
									<input type="checkbox" name="selectAllPriseEC" id="selectAllPriseEC" onclick="SelectionnerToutPriseEC()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
								<td class="EnTeteTableauCompetences" width="6%"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?><?php if($_SESSION['TriBesoins_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_Contrat']=="ASC"){echo "&darr;";}?></td>
								<?php } ?>
								<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Besoin_Formation.php?Tri=LIBELLE_TYPEFORMATION">Type<?php if($_SESSION['TriBesoins_LIBELLE_TYPEFORMATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_LIBELLE_TYPEFORMATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="30%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Besoin_Formation.php?Tri=LIBELLE_FORMATION"><?php if($LangueAffichage=="FR"){echo "Formation / Organisme";}else{echo "Training / Organization";}?><?php if($_SESSION['TriBesoins_LIBELLE_FORMATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_LIBELLE_FORMATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Besoin_Formation.php?Tri=LIBELLE_PRESTATION"><?php if($LangueAffichage=="FR"){echo "Prestation - Pôle";}else{echo "Activity - Pole";}?><?php if($_SESSION['TriBesoins_LIBELLE_PRESTATION']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_LIBELLE_PRESTATION']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Besoin_Formation.php?Tri=NOM_PRENOM"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?><?php if($_SESSION['TriBesoins_NOM_PRENOM']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_NOM_PRENOM']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="2%"></td>
								<td class="EnTeteTableauCompetences" width="7%"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Besoin_Formation.php?Tri=DATE_DEMANDE"><?php if($LangueAffichage=="FR"){echo "Date demande";}else{echo "Demand date";}?><?php if($_SESSION['TriBesoins_DATE_DEMANDE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_DATE_DEMANDE']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" width="7%" style="color:#003cff"><a style="text-decoration:none;color:#003cff;font-weight:bold;" id="tri" href="Liste_Besoin_Formation.php?Tri=DateFinQualif"><?php if($LangueAffichage=="FR"){echo "Date fin validité qualification";}else{echo "End date of validity of qualifications";}?><?php if($_SESSION['TriBesoins_DateFinQualif']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriBesoins_DateFinQualif']=="ASC"){echo "&darr;";}?></a></td>
								<td class="EnTeteTableauCompetences" align="center" width="5%">
									<input class="Bouton" style="cursor: pointer;" name="ValiderBesoin" title="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validated";}?>" size="3" type="submit" value="<?php if($LangueAffichage=="FR"){echo "V";}else{echo "V";}?>"><br>
									<input class="Bouton" style="cursor: pointer;" name="RefuserBesoin" title="<?php if($LangueAffichage=="FR"){echo "Refuser";}else{echo "Refused";}?>" size="3" type="submit" value="<?php if($LangueAffichage=="FR"){echo "R";}else{echo "R";}?>"><br>
									<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($LangueAffichage=="FR"){echo "<br>Sél.<br>tout";}else{echo "<br>Select<br>all";} ?>
								</td>
								<td class="EnTeteTableauCompetences" align="center" width="2%">
									<?php 
										if(DroitsFormationPlateforme($TableauIdPostesAF_RF)){
									?>
									<input class="Bouton" style="cursor: pointer;" name="SupprimerBesoin" size="3" type="submit" value="<?php if($LangueAffichage=="FR"){echo "S";}else{echo "S";}?>"><br>
									<input type="checkbox" name="selectAllSuppr" id="selectAllSuppr" onclick="SelectionnerToutSuppr()" /><?php if($LangueAffichage=="FR"){echo "<br>Sél.<br>tout";}else{echo "<br>Select<br>all";} ?>
									<?php
										}
									?>
								</td>
								<td align="right"  width="4%" class="EnTeteTableauCompetences">
									<?php 
										if(DroitsFormationPlateforme($TableauIdPostesAF_RF) || DroitsFormationPrestations(array(4),$TableauIdPostesRespPresta_CQ) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) 
										|| mysqli_num_rows($resAcc3=mysqli_query($bdd,"SELECT Id_Poste FROM new_competences_personne_poste_plateforme WHERE Id_Plateforme IN (3,4,9,10,19,22) AND Id_Poste IN (13,15,17,18,19,21) AND Id_Personne =".$IdPersonneConnectee))>0){
									?>
									<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0','0');">
										<img src="../../Images/add.png" width="25px" border="0" alt="<?php if($LangueAffichage=="FR"){echo "Ajouter un besoin en formation";}else{echo "Add a training need";} ?>" title="<?php if($LangueAffichage=="FR"){echo "Ajouter un besoin en formation";}else{echo "Add a training need";} ?>">
									</a>
									<br>
									<a class="Bouton" href="javascript:OuvreFenetreBesoinPersonne();">
										<?php if($LangueAffichage=="FR"){echo "Ajouter plusieurs besoins";}else{echo "Add multiple requirements";} ?>
									</a>
									<?php
										}
									?>
								</td>
							</tr>
						</table>
						<div style="width:100%;height:400px;overflow:auto;">
						<table class="TableCompetences" style="width:100%;">
						<?php
						$Comptdispo=0;
						$Comptpasdispo=0;
						$Comptaconfirmer=0;
						$Comptrefuse=0;
						$Comptsuppr=0;
						if($nbenreg>0)
						{
							while($row=mysqli_fetch_array($result))
							{
								//Gestion des couleurs en fonction du traitement du besoin
								$Couleur="#FFFFFF";
								if($row['Suppr']==1){
									$Couleur=$blanc;
								}
								else{
									switch($row['Etat'])
									{
										case "Refuse": $Couleur=$gris;break;
										case "Supprime": $Couleur=$blanc;break;
										case "AConfirmer": $Couleur=$rouge;break;
										case "PasDispo": $Couleur=$orange;break;
										case "Dispo":$Couleur=$vert;break;
									}
								}
								
								$Libelle=stripslashes($row['Libelle']);
								if($row['Organisme']<>""){$Libelle.=" (".$row['Organisme'].")";}
								if($Libelle==""){$Libelle="<<".$row['REFERENCE_FORMATION'].">>";}
								
								if($Couleur==$vert){$Comptdispo++;}
								elseif($Couleur==$orange){$Comptpasdispo++;}
								elseif($Couleur==$rouge){$Comptaconfirmer++;}
								elseif($Couleur==$gris){$Comptrefuse++;}
								elseif($Couleur==$blanc){$Comptsuppr++;}
								
								$Motif=$row['MOTIF_DEMANDE'];
								if($LangueAffichage<>"FR")
								{
									switch($Motif)
									{
										case "Nouveau":$Motif="New";break;
										case "Renouvellement":$Motif="Renewal";break;
										case "Suite à absence":$Motif="Following absence";break;
										case "Changement de prestation":$Motif="Change of service";break;
										case "Nouveau besoin pour ce métier et cette prestation":$Motif="New need for this profession and this service";break;
									}
									$Motif=str_replace("En formation sur nouveau métier","In training on a new job",$Motif);
									$Motif=str_replace("Nouveau besoin pour ce métier","New need for this profession",$Motif);
									$Motif=str_replace("et cette prestation","and this service",$Motif);
								}
								
								$hover="id='leHover'";
								$span="<span>".$Motif;
								if($row['COMMENTAIRE']<>"" || $row['Suppr']==1)
								{
									$span.="<br>";
									if($row['COMMENTAIRE']<>""){$span.=stripslashes($row['COMMENTAIRE'])."<br>";}
									if($row['Suppr']==1){
										if($LangueAffichage=="FR"){
											$span.="Supprimé le ".AfficheDateJJ_MM_AAAA($row['Date_MAJ'])."<br>";
											$span.="Raison de la suppression : ".stripslashes($row['RaisonSuppression'])."<br>".stripslashes($row['Motif_Suppr'])."<br>";
										}
										else{
											$span.="Deleted on ".AfficheDateJJ_MM_AAAA($row['Date_MAJ'])."<br>";
											$span.="Reason for deletion : ".stripslashes($row['RaisonSuppression'])."<br>".stripslashes($row['Motif_Suppr'])."<br>";
										}
									}
								}
								$span.="</span>";
								
								$Pole="";
								if($row['Pole']<>""){$Pole=" - ".$row['Pole'];}
							?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<?php
										$imgInscrire="";
										if(DroitsAUnePrestation($TableauIdPostesRespPresta_CQ,$row['Id_Prestation'],$row['Id_Pole']) && $Couleur==$vert){
											$imgInscrire="<img style=\"cursor:pointer;\" onclick=\"InscrireSession('".$row['ID_BESOIN']."')\" width='15px' src='../../Images/I.png' border='0' alt='Inscription' title='Inscription'>";
										}
									?>
									<?php 
										if(DroitsFormationPrestation($TableauIdPostesRespPresta_CQ)){
											echo "<td width='5%' style='border-bottom:dotted 1px #003333;' height='35'>";
											if(DroitsAUnePrestation($TableauIdPostesRespPresta_CQ,$row['Id_Prestation'],$row['Id_Pole']))
											{
												echo "<img style=\"cursor:pointer;\" onclick=\"MettreCommentaire('".$row['ID_BESOIN']."')\" width='18px' src='../../Images/Modif.gif' border='0'>";
											}
											echo "</td>";
										}
									?>
									<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){ ?>
									<td width="4%" style="border-bottom:dotted 1px #003333;">
										<?php 
											if($row['TraiteAF']==1)
											{
												echo "<img src='../../Images/tick.png' border='0' alt='Prise en compte' title='Prise en compte'>";
											}
											else
											{
										?>
										<input class="checkEC" type="checkbox" name="PriseEnCompte[]" value="<?php echo $row['ID_BESOIN']; ?>" />
										<?php
											}
										?>
									</td>
									<?php
									$Contrat="";
									$IdContrat=IdContrat($row['Id_Personne'],date('Y-m-d'));
									if($IdContrat>0){
										if(TypeContrat2($IdContrat)<>10){
											$Contrat=TypeContrat($IdContrat);
										}
										else{
											$tab=AgenceInterimContrat($IdContrat);
											if($tab<>0){
												$Contrat=$tab[0];
											}
										}
									}
									?>
									<td width="6%" style="border-bottom:dotted 1px #003333;"><?php echo $Contrat;?></td>
									<?php } ?>
									<td width="6%" style="border-bottom:dotted 1px #003333;" height="35"><?php echo $row['LIBELLE_TYPEFORMATION'];?></td>
									<td width="30%" style="border-bottom:dotted 1px #003333;"><?php echo $Libelle;?></td>
									<td width="10%" style="border-bottom:dotted 1px #003333;" id="leHover"><?php echo AfficheCodePrestation($row['LIBELLE_PRESTATION']).$Pole;?><span><?php echo "N+1 : ".$row['N1']."<br> N+2 : ".$row['N2']."";?></span></td>
									<td width="10%" style="border-bottom:dotted 1px #003333;"><?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['NOM_PRENOM']."</a>";
										if($row['CommentaireCE']<>""){
											echo "&nbsp;&nbsp;&nbsp;<img width='15px' src='../../Images/Commentaire.png' border='0' alt=\"".stripslashes($row['CommentaireCE'])."\" title=\"".stripslashes($row['CommentaireCE'])."\">";
										}
									?>
									</td>
									<td width="2%" style="border-bottom:dotted 1px #003333;"><?php echo $imgInscrire;?></td>
									<td width="7%" style="border-bottom:dotted 1px #003333;" id="leHover"><?php echo AfficheDateJJ_MM_AAAA($row['DATE_DEMANDE']).$span;?></td>
									<td width="7%" style="border-bottom:dotted 1px #003333;"><?php echo AfficheDateJJ_MM_AAAA($row['DateFinQualif'])?></td>
									<td width="5%" style="border-bottom:dotted 1px #003333;" valign="middle" align="center">
										<?php if($Couleur<>$blanc && $row['Obligatoire']==0 && ($row['VALIDE']==0 || ($row['VALIDE']==-1 && Get_NbBesoinExistant($row['Id_Personne'], $row['ID_FORMATION'])==0 && Get_QualifAJour($row['Id_Personne'], $row['ID_FORMATION'])==0)))
										      { 
										          if(DroitsAUnePrestation($TableauIdPostesRespPresta_CQ,$row['Id_Prestation'],$row['Id_Pole']) || DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne, $IdPosteAssistantFormationInterne, $IdPosteResponsableFormation)))
												{
													
										?>
											<input class="check" type="checkbox" name="Besoins[]" value="<?php echo $row['ID_BESOIN']; ?>" />
										<?php 
												}
											}
										?>
									</td>
									<td style="border-bottom:dotted 1px #003333;" width="2%">
										<?php
										$Suppression=false;
										if($row['VALIDE']==1){
											if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH) || DroitsFormationPrestation($TableauIdPostesCQ))
											{
												if((DroitsAUnePrestation($TableauIdPostesRespPresta_CQ,$row['Id_Prestation'],$row['Id_Pole'])) || DroitsFormationPlateforme($TableauIdPostesAF_RF_RH))
												{
													$Suppression=true;
												}
											}
										}
										if($Couleur<>$blanc){
											if($Suppression)
											{
												$AF_RQ_RF_CQ=0;
												if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS) || DroitsFormationPrestation($TableauIdPostesCQ)){$AF_RQ_RF_CQ=1;}
												if(DroitsFormationPlateforme($TableauIdPostesAF_RF)){
													echo '<input class="checkSuppr" type="checkbox" name="BesoinsSuppr[]" value="'.$row['ID_BESOIN'].'" />';
												}
												else{
													echo "<a class=\"Modif\" href=\"javascript:OuvreFenetreModif('Suppr','".$row['ID_BESOIN']."','".$AF_RQ_RF_CQ."');\">";
													echo "<img src=\"../../Images/Suppression2.gif\" style=\"border:0;\" alt=\"Suppression\">";
													echo "</a>";
												}
											}
										}
										?>
									</td>
									<td style="border-bottom:dotted 1px #003333;" width="4%">
									<?php
										if($_SESSION['PartieFormation']>1)
										{
										
										//EN COMMENTAIRE EN ATTENDANT LE DEPLOIEMENT DE CETTE PARTIE
											if((DroitsFormationPlateforme($TableauIdPostesAFI_RF_FORM) || DroitsFormationPrestation($TableauIdPostesCQ)) && $row['VALIDE']==1)
											{
												if(DroitsFormationPlateforme($TableauIdPostesAFI_RF_FORM) || DroitsFormationPrestation($TableauIdPostesCQ) || $_SESSION['Id_Personne']==6700){
													//Vérifier si la formation a des qualifications et si toutes les qualifications nécessite des QCM 
													$req="SELECT Id 
														FROM form_formation_qualification 
														WHERE Id_Formation=".$row['ID_FORMATION']." 
														AND Suppr=0 
														AND Masquer=0 
														AND (SELECT COUNT(form_formation_qualification_qcm.Id) 
															FROM form_formation_qualification_qcm
															WHERE Id_Formation_Qualification=form_formation_qualification.Id 
															AND Suppr=0)>0 ";
													$resultQCM=mysqli_query($bdd,$req);
													$nbQCM=mysqli_num_rows($resultQCM);
													if($nbQCM>0)
													{
														//Possibilité de passer le QCM sans session de formation 
														echo "<a class=\"Modif\" href=\"javascript:OuvreFenetreQCM('".$row['ID_BESOIN']."');\">";
														if($LangueAffichage=="FR")
														{
															echo "<img src=\"../../Images/qcm.png\" width='25px' style=\"border:0;\" alt=\"QCM sans session de formation\">";
														}
														else
														{
															echo "<img src=\"../../Images/qcm.png\" width='25px' style=\"border:0;\" alt=\"MCQ without training session\">";
														}
														echo "</a>";
													}
												}
											}
											
										}
									?>
									</td>
								</tr>
							<?php
							}	//Fin boucle
						}		//Fin If
						mysqli_free_result($result);	// Libération des résultats
						?>
						</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="200px"></td></tr>
</table>
 <!-- Mise à jour de la liste des sessions -->
 <script>updateSessions();</script> 
 <!--Mise à jour des compteurs-->
 <?php
	$result=mysqli_query($bdd,$requeteV2.$req2);
	$nbenreg=mysqli_num_rows($result);
	
	$Comptdispo=0;
	$Comptpasdispo=0;
	$Comptaconfirmer=0;
	$Comptrefuse=0;
	if($nbenreg>0)
	{
		while($row=mysqli_fetch_array($result))
		{
			//Gestion des couleurs en fonction du traitement du besoin
			$Couleur="#FFFFFF";
			switch($row['VALIDE'])
			{
				case -1: $Couleur=$gris;break;
				case 0: $Couleur=$rouge;break;
				case 1: 
					$Couleur=$orange;
					$Recyl=0;
					if($row['MOTIF_DEMANDE']=="Renouvellement"){$Recyl=1;}
					if($row['RECYCLAGE_IDENTIQUE']==0){$Recyl=0;}
					//Rajouter en fonction du planning la couleur orange ou verte
					$reqF="SELECT form_session_date.Id_Session, 
								form_session.Nb_Stagiaire_Maxi,
								(SELECT COUNT(Id) FROM form_session_personne WHERE Validation_Inscription=1 AND form_session_personne.Suppr=0 AND Id_Session=form_session_date.Id_Session) AS NbSessionPersonne,
								(SELECT COUNT(Id)
								FROM form_session_date AS TAB 
								WHERE TAB.DateSession<='".date('Y-m-d')."' 
								AND TAB.Suppr=0 
								AND TAB.Id_Session=form_session_date.Id_Session) AS NbSessionDateDepasse
							FROM form_session_date 
							LEFT JOIN form_session ON form_session_date.Id_Session=form_session.Id 
							WHERE form_session_date.Suppr=0 
							AND form_session_date.DateSession>'".date('Y-m-d')."' 
							AND form_session.Suppr=0 
							AND form_session.Annule=0 
							AND form_session.Diffusion_Creneau=1 
							AND form_session_date.Id_Session IN 
								(SELECT Id_Session FROM form_session_prestation WHERE Suppr=0 AND Id_Prestation=".$row['Id_Prestation'].") 
							AND ( 
									(form_session.Id_Formation=".$row['ID_FORMATION']." AND form_session.Recyclage=".$Recyl.") 
									OR ";
					$reqSimil="SELECT Id_Formation, Recyclage   
								FROM form_formationequivalente_formationplateforme 
								WHERE form_formationequivalente_formationplateforme.Id_FormationEquivalente 
								IN (
									SELECT Id_FormationEquivalente  
									FROM form_formationequivalente_formationplateforme 
									LEFT JOIN form_formationequivalente 
									ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
									WHERE form_formationequivalente.Id_Plateforme=".$row['Id_Plateforme']." 
									AND form_formationequivalente_formationplateforme.Id_Formation=".$row['ID_FORMATION']."
									AND form_formationequivalente_formationplateforme.Recyclage=".$Recyl."
									)";
					$resultSimil=mysqli_query($bdd,$reqSimil);
					$nbSimil=mysqli_num_rows($resultSimil);
					if($nbSimil>0)
					{
						while($rowSimil=mysqli_fetch_array($resultSimil))
						{
							$reqF.=" ( form_session.Id_Formation=".$rowSimil['Id_Formation']." AND form_session.Recyclage=".$rowSimil['Recyclage'].") OR ";
						}
					}
					$reqF=substr($reqF,0,-3);
					$reqF.=") ";
					$resultSession=mysqli_query($bdd,$reqF);
					$nbSession=mysqli_num_rows($resultSession);
					if($nbSession>0)
					{
						$bOK=true;
						$PlacesRestante=false;
						while($rowSessionDate=mysqli_fetch_array($resultSession))
						{
							if($rowSessionDate['Nb_Stagiaire_Maxi']>$rowSessionDate['NbSessionPersonne']){$PlacesRestante=true;}
							if($rowSessionDate['NbSessionDateDepasse']>0){$bOK=false;}
						}
						if($bOK==true && $PlacesRestante==true){$Couleur=$vert;}
					}
					break;
			}

			$bTrouve=1;
			if($etatR<>"")
			{
				if($etatR=="Dispo" && $Couleur<>$vert){$bTrouve=0;}
				elseif($etatR=="PasDispo" && $Couleur<>$orange){$bTrouve=0;}
				elseif($etatR=="AConfirmer" && $Couleur<>$rouge){$bTrouve=0;}
				elseif($etatR=="Refuse" && $Couleur<>$gris){$bTrouve=0;}
				elseif($etatR=="Supprime" && $Couleur<>$blanc){$bTrouve=0;}
			}
			else
			{
				if($Couleur==$gris || $Couleur==$blanc){$bTrouve=0;}
			}
			if($bTrouve==1)
			{
				if($Couleur==$vert){$Comptdispo++;}
				elseif($Couleur==$orange){$Comptpasdispo++;}
				elseif($Couleur==$rouge){$Comptaconfirmer++;}
				elseif($Couleur==$gris){$Comptrefuse++;}
				elseif($Couleur==$blanc){$Comptsuppr++;}
			}
		}	//Fin boucle
	}		//Fin If
	mysqli_free_result($result);	// Libération des résultats
 ?>
 <script>
	document.getElementById('compteurDispo').innerHTML= <?php echo $Comptdispo; ?>;
	document.getElementById('compteurPasDispo').innerHTML= <?php echo $Comptpasdispo; ?>;
	document.getElementById('compteurAConfirmer').innerHTML= <?php echo $Comptaconfirmer; ?>;
	document.getElementById('compteurRefuse').innerHTML= <?php echo $Comptrefuse; ?>;
	document.getElementById('compteurSupprime').innerHTML= <?php echo $Comptsuppr; ?>;
 </script>
<?php

	mysqli_close($bdd);					// Fermeture de la connexion
?>
</form>
</body>
</html>