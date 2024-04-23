<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel()
		{window.open("Export_MouvementRH.php","PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	if($Selected==true){$tiret="border-bottom:4px solid white;";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#0c4985;valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#0c4985;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#0c4985';\" onmouseout=\"this.style.color='#0c4985';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}

if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Contrat","PrestationDepart","PrestationDestination","DateDebut","DateFin",'DatePriseEnCompteRH');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHMouvement_General']= str_replace($tri." ASC,","",$_SESSION['TriRHMouvement_General']);
			$_SESSION['TriRHMouvement_General']= str_replace($tri." DESC,","",$_SESSION['TriRHMouvement_General']);
			$_SESSION['TriRHMouvement_General']= str_replace($tri." ASC","",$_SESSION['TriRHMouvement_General']);
			$_SESSION['TriRHMouvement_General']= str_replace($tri." DESC","",$_SESSION['TriRHMouvement_General']);
			if($_SESSION['TriRHMouvement_'.$tri]==""){$_SESSION['TriRHMouvement_'.$tri]="ASC";$_SESSION['TriRHMouvement_General'].= $tri." ".$_SESSION['TriRHMouvement_'.$tri].",";}
			elseif($_SESSION['TriRHMouvement_'.$tri]=="ASC"){$_SESSION['TriRHMouvement_'.$tri]="DESC";$_SESSION['TriRHMouvement_General'].= $tri." ".$_SESSION['TriRHMouvement_'.$tri].",";}
			else{$_SESSION['TriRHMouvement_'.$tri]="";}
		}
	}
}

$TDB=0;
if($_GET){
	if(isset($_GET['TDB'])){
		$TDB=$_GET['TDB'];
	}
}
else{
	$TDB=$_POST['TDB'];
}
$OngletTDB="";
if($_GET){
	if(isset($_GET['OngletTDB'])){
		$OngletTDB=$_GET['OngletTDB'];
	}
}
else{
	$OngletTDB=$_POST['OngletTDB'];
}

$TypeTri="";
if($_GET){
	if(isset($_GET['TypeTri'])){
		$TypeTri=$_GET['TypeTri'];
	}
}
else{
	$TypeTri=$_POST['TypeTri'];
}

?>

<form class="test" action="Liste_MouvementPersonnelRH.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<input type="hidden" name="TypeTri" id="TypeTri" value="<?php echo $TypeTri; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#1365b6;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des mouvements de personnel";}else{echo "List of staff movements";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr bgcolor="#92c3f4">
					<?php
						$ParametreTDB="";
						if($TDB>0){$ParametreTDB="&TDB=".$TDB;}
						if($OngletTDB<>""){$ParametreTDB.="&OngletTDB=".$OngletTDB;}
						if($_SESSION["Langue"]=="FR"){Titre1("MOUVEMENTS EN COURS","Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=".$Menu.$ParametreTDB,true);}
						else{Titre1("MOVEMENTS IN PROGRESS","Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=".$Menu.$ParametreTDB."",true);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu.$ParametreTDB,false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu.$ParametreTDB."",false);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation de départ:";}else{echo "Departure site :";} ?>
				<select class="prestation" style="width:100px;" name="prestationDepart" onchange="submit();">
				<?php
					$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole
						FROM new_competences_prestation
						WHERE Active=0
						AND Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole    
						)
						
						UNION 
						
						SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
							new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole
							FROM new_competences_pole
							INNER JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							AND Active=0
							AND Actif=0
							
						ORDER BY Libelle, LibellePole";

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationDepartSelect=$_SESSION['FiltreRHMouvement_PrestationDep'];
				if($_POST){$PrestationDepartSelect=$_POST['prestationDepart'];}
				$_SESSION['FiltreRHMouvement_PrestationDep']=$PrestationDepartSelect;	
				
				echo "<option name='0_0' value='0_0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($rowsite=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationDepartSelect<>"")
							{if($PrestationDepartSelect==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}}
						echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
						$pole="";
						if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
						echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation de destination:";}else{echo "Destination site :";} ?>
				<select class="prestation" style="width:100px;" name="prestationDestination" onchange="submit();">
				<?php
					$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole
						FROM new_competences_prestation
						WHERE Active=0
						AND Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole    
						)
						
						UNION 
						
						SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
							new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole
							FROM new_competences_pole
							INNER JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							AND Active=0
							AND Actif=0
						ORDER BY Libelle, LibellePole";

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationDestinationSelect=$_SESSION['FiltreRHMouvement_PrestationDes'];
				if($_POST){$PrestationDestinationSelect=$_POST['prestationDestination'];}
				$_SESSION['FiltreRHMouvement_PrestationDes']=$PrestationDestinationSelect;	
				
				echo "<option name='0_0' value='0_0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($rowsite=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationDestinationSelect<>"")
							{if($PrestationDestinationSelect==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}}
						echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
						$pole="";
						if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
						echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM rh_personne_mouvement
								LEFT JOIN new_rh_etatcivil
								ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
								WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								ORDER BY Personne ASC";
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHMouvement_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHMouvement_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Du :";}else{echo "From :";} ?>
				<?php
				$dateDebut=$_SESSION['FiltreRHMouvement_Du'];
				if($_POST){$dateDebut=$_POST['dateDebut'];}
				$_SESSION['FiltreRHMouvement_Du']= $dateDebut;
				?>
				<input type="date" id="dateDebut" name="dateDebut" value="<?php echo $dateDebut; ?>">
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Au :";}else{echo "To :";} ?>
				<?php
				$dateFin=$_SESSION['FiltreRHMouvement_Au'];
				if($_POST){$dateFin=$_POST['dateFin'];}
				$_SESSION['FiltreRHMouvement_Au']= $dateFin;
				?>
				<input type="date" id="dateFin" name="dateFin" value="<?php echo $dateFin; ?>">
			</td>
			<td width="20%">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$PrisEnCompte=$_SESSION['FiltreRHMouvement_EtatPrisEnCompte'];
						$NonPrisEnCompte=$_SESSION['FiltreRHMouvement_EtatNonPrisEnCompte'];
						if($_POST){
							if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
							if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
						}
						$_SESSION['FiltreRHMouvement_EtatPrisEnCompte']=$PrisEnCompte;
						$_SESSION['FiltreRHMouvement_EtatNonPrisEnCompte']=$NonPrisEnCompte;
					?>
					<input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $NonPrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $PrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="5%">
				&nbsp;&nbsp;&nbsp;

			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHMouvement_RespProjet'];
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
							$_SESSION['FiltreRHMouvement_RespProjet']=$Id_RespProjet;
	
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
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
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
									$checkboxes = explode(',',$_SESSION['FiltreRHMouvement_RespProjet']);
									foreach($checkboxes as $value) {
										if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
									}
								}
								echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
							}
						?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		$requeteAnalyse="SELECT rh_personne_mouvement.Id ";
		$requete2="SELECT rh_personne_mouvement.Id, rh_personne_mouvement.Id_Personne,rh_personne_mouvement.Id_PrestationDepart,rh_personne_mouvement.Id_PoleDepart,
			rh_personne_mouvement.Id_Prestation,rh_personne_mouvement.Id_Pole,rh_personne_mouvement.DateCreation,rh_personne_mouvement.Id_Createur,
			rh_personne_mouvement.EtatValidation,rh_personne_mouvement.DateDebut,rh_personne_mouvement.DateFin,
			rh_personne_mouvement.DatePriseEnCompteRH,
			(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_mouvement.DateDebut
			AND (rh_personne_contrat.DateFin>=rh_personne_mouvement.DateDebut OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_mouvement.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
			CONCAT((SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDepart),
				IF(Id_PoleDepart>0,' - ','') ,
				IF(Id_PoleDepart>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDepart),'')
			) AS PrestationDepart,
			CONCAT((SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation),
				IF(Id_Pole>0,' - ','') ,
				IF(Id_Pole>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole),'')
			) AS PrestationDestination,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne) AS Personne ";
		$requete=" FROM rh_personne_mouvement
					WHERE Suppr=0 
					AND EtatValidation IN (0,1)
					";
		if($_SESSION['FiltreRHMouvement_PrestationDep']<>0 && $_SESSION['FiltreRHMouvement_PrestationDep']<>"0_0"){
			$requete.=" AND CONCAT(rh_personne_mouvement.Id_PrestationDepart,'_',rh_personne_mouvement.Id_PoleDepart)='".$_SESSION['FiltreRHMouvement_PrestationDep']."' ";
		}
		if($_SESSION['FiltreRHMouvement_PrestationDes']<>0 && $_SESSION['FiltreRHMouvement_PrestationDes']<>"0_0"){
			$requete.=" AND CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)='".$_SESSION['FiltreRHMouvement_PrestationDes']."' ";
		}
		if($Menu==4){
			$requete.=" AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					OR 
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDepart) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					)
					";
			if($_SESSION['FiltreRHMouvement_RespProjet']<>""){
				$requete.="AND CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) 
							IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
								FROM new_competences_personne_poste_prestation
								WHERE Id_Personne IN (".$_SESSION['FiltreRHMouvement_RespProjet'].")
								AND Id_Poste IN (".$IdPosteResponsableProjet.")
							)
							";
			}
		}
		
		if($_SESSION['FiltreRHMouvement_Personne']<>0 && $_SESSION['FiltreRHMouvement_Personne']<>""){
			$requete.=" AND rh_personne_mouvement.Id_Personne=".$_SESSION['FiltreRHMouvement_Personne']." ";
		}
		if($_SESSION['FiltreRHMouvement_Du']<>""){
			$requete.=" AND rh_personne_mouvement.DateDebut>=".$_SESSION['FiltreRHMouvement_Du']." ";
		}
		if($_SESSION['FiltreRHMouvement_Au']<>""){
			$requete.=" AND rh_personne_mouvement.DateFin<=".$_SESSION['FiltreRHMouvement_Au']." ";
		}
		if($_SESSION['FiltreRHMouvement_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHMouvement_EtatNonPrisEnCompte']<>""){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHMouvement_EtatPrisEnCompte']<>""){
				$requete.=" rh_personne_mouvement.DatePriseEnCompteRH>'0001-01-01' OR ";
			}
			if($_SESSION['FiltreRHMouvement_EtatNonPrisEnCompte']<>""){
				$requete.=" rh_personne_mouvement.DatePriseEnCompteRH<='0001-01-01' OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}
		else{
			$requete.=" AND ( ";
			$requete.=" rh_personne_mouvement.DatePriseEnCompteRH<='0001-01-01' OR ";
			$requete.=" ) ";
		}
		
		$requeteOrder="";
		if($TypeTri=="EC"){
			if($_SESSION['TriRHMouvement_General']<>""){
				$requeteOrder="ORDER BY EtatValidation DESC,".substr($_SESSION['TriRHMouvement_General'],0,-1);
			}
			else{
				$requeteOrder="ORDER BY EtatValidation DESC";
			}
		}
		else{
			if($_SESSION['TriRHMouvement_General']<>""){
				$requeteOrder="ORDER BY EtatValidation ASC,Id_Plateforme DESC,".substr($_SESSION['TriRHMouvement_General'],0,-1);
			}
			else{
				$requeteOrder="ORDER BY EtatValidation ASC,Id_Plateforme DESC";
			}
		}
		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);
		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_MouvementPersonnelRH.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&TypeTri=".$TypeTri."&debut=1&Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($page<=5){
					$valeurDepart=1;
				}
				elseif($page>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$page-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($page+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_MouvementPersonnelRH.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&TypeTri=".$TypeTri."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_MouvementPersonnelRH.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&TypeTri=".$TypeTri."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande";}else{echo "Request number";} ?><?php if($_SESSION['TriRHMouvement_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_Id']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHMouvement_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PrestationDepart"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation départ";}else{echo "Departure site";} ?><?php if($_SESSION['TriRHMouvement_PrestationDepart']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_PrestationDepart']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PrestationDestination"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation destination";}else{echo "Destination site";} ?><?php if($_SESSION['TriRHMouvement_PrestationDestination']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_PrestationDestination']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHMouvement_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHMouvement_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHMouvement_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_MouvementPersonnelRH.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DatePriseEnCompteRH"><?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?><?php if($_SESSION['TriRHMouvement_DatePriseEnCompteRH']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHMouvement_DatePriseEnCompteRH']=="ASC"){echo "&darr;";}?></a></td>
					<td class='EnTeteTableauCompetences' width="8%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Validation";}else{echo "Validation";}?>" name="validationHorsTLS" value="<?php if($_SESSION["Langue"]=="FR"){echo "Validation";}else{echo "Validation";} ?>">&nbsp;
					</td>
					<td class='EnTeteTableauCompetences' width="8%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>">&nbsp;
					</td>
				</tr>
	<?php
			$result=mysqli_query($bdd,$requete2.$requete);
			if(isset($_POST['priseEnCompte'])){
				while($row=mysqli_fetch_array($result)){
					if($row['EtatValidation']==1){
						if (isset($_POST['check_'.$row['Id']])){
							$requeteUpdate="UPDATE rh_personne_mouvement SET 
									Id_RH=".$_SESSION['Id_Personne'].",
									DatePriseEnCompteRH='".date('Y-m-d')."'
									WHERE Id=".$row['Id']." ";
							$resultat=mysqli_query($bdd,$requeteUpdate);
						}
					}
				}
			}

			if(isset($_POST['validationHorsTLS'])){
				$result=mysqli_query($bdd,$requete2.$requete);
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkHorsTLS_'.$row['Id']])){
						$requeteUpdate="UPDATE rh_personne_mouvement SET 
								Id_Validateur=".$_SESSION['Id_Personne'].",
								EtatValidation=1,
								DateValidationTransfert='".date('Y-m-d')."',
								Id_DemandeurPrisEnCompte=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteDemandeur='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
						
						$req="SELECT Id, DateDebut, DateFin, Id_Prestation,Id_Pole,Id_Personne,Id_PrestationDepart,Id_PoleDepart FROM rh_personne_mouvement WHERE Id=".$row['Id'];
						$resultatSel=mysqli_query($bdd,$req);
						$rowSel=mysqli_fetch_array($resultatSel);
						$IdCree=$row['Id'];
						
						$dateFin='0001-01-01';
						$req="SELECT Id,DateFin
						FROM rh_personne_mouvement
								WHERE (rh_personne_mouvement.DateDebut<='".$rowSel['DateFin']."' OR '".$rowSel['DateFin']."'<='0001-01-01')
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$rowSel['DateDebut']."')
								AND rh_personne_mouvement.EtatValidation=1 
								AND rh_personne_mouvement.Id_Personne=".$rowSel['Id_Personne']."
								AND rh_personne_mouvement.Suppr=0
								AND Id_Prestation=".$rowSel['Id_PrestationDepart']."
								AND Id_Pole=".$rowSel['Id_PoleDepart']."
								AND rh_personne_mouvement.Id<>".$rowSel['Id']."
								ORDER BY DateFin DESC";
						$resultatMod=mysqli_query($bdd,$req);
						$nbResultaMod=mysqli_num_rows($resultatMod);
						if($nbResultaMod>0){
							$rowMod=mysqli_fetch_array($resultatMod);
							$dateFin=$rowMod['DateFin'];
						}
						
						
						$req="SELECT Id,DateFin
						FROM rh_personne_mouvement
								WHERE (rh_personne_mouvement.DateDebut<='".$rowSel['DateFin']."' OR '".$rowSel['DateFin']."'<='0001-01-01')
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$rowSel['DateDebut']."')
								AND rh_personne_mouvement.EtatValidation=1 
								AND rh_personne_mouvement.Id_Personne=".$rowSel['Id_Personne']."
								AND rh_personne_mouvement.Suppr=0
								AND Id_Prestation=".$rowSel['Id_PrestationDepart']."
								AND Id_Pole=".$rowSel['Id_PoleDepart']."
								AND rh_personne_mouvement.Id<>".$rowSel['Id']."
								ORDER BY DateFin ASC";
						$resultatMod=mysqli_query($bdd,$req);
						$nbResultaMod=mysqli_num_rows($resultatMod);
						if($nbResultaMod>0){
							$rowMod2=mysqli_fetch_array($resultatMod);
							if($rowMod2['DateFin']<='0001-01-01'){
								$dateFin=$rowMod['DateFin'];
							}
						}

						//Mise à jour de la date de fin de la personne 
						$req="UPDATE rh_personne_mouvement
							SET DateFin='".date("Y-m-d",strtotime($rowSel['DateDebut']." -1 day"))."'
							WHERE (DateDebut<='".$rowSel['DateFin']."'  OR '".$rowSel['DateFin']."'<='0001-01-01')
							AND (DateFin<='0001-01-01' OR DateFin>='".$rowSel['DateDebut']."')
							AND EtatValidation=1 
							AND Id_Personne=".$rowSel['Id_Personne']."
							AND Id_Prestation=".$rowSel['Id_PrestationDepart']."
							AND Id_Pole=".$rowSel['Id_PoleDepart']."
							AND Suppr=0
							AND Id<>".$rowSel['Id']." ";
						$resultatUpdate=mysqli_query($bdd,$req);
						
						//Supprimer si la date de début > date de fin
						$req="UPDATE rh_personne_mouvement
							SET Suppr=1,
							Id_Suppr=".$_SESSION['Id_Personne'].",
							DateSuppr='".date('Y-m-d')."' 
							WHERE DateDebut>DateFin
							AND DateFin>'0001-01-01'
							AND EtatValidation=1 
							AND Id_Personne=".$rowSel['Id_Personne']."
							AND Suppr=0
							AND Id<>".$rowSel['Id']." ";
						$resultatUpdate=mysqli_query($bdd,$req);
						
						if($rowSel['DateFin']>'0001-01-01'){
							if($nbResultaMod>0){
								//Création de la suite
								$req="INSERT INTO rh_personne_mouvement (Id_PrestationDepart,Id_PoleDepart,Id_Prestation,Id_Pole,Id_Personne,DateDebut,DateFin,Id_Createur,DateCreation,EtatValidation,DateValidationTransfert)
									SELECT 
									".$rowSel['Id_Prestation']." AS Id_PrestationDepart,
									".$rowSel['Id_Pole']." AS Id_PoleDepart,
									Id_PrestationDepart AS Id_Prestation,
									Id_PoleDepart AS Id_Pole,
									Id_Personne,
									'".date("Y-m-d",strtotime($rowSel['DateFin']." +1 day"))."' AS DateDebut,
									'".$dateFin."' AS DateFin,
									".$_SESSION['Id_Personne']." AS Id_Createur,
									'".date('Y-m-d')."' AS DateCreation, 
									1,
									'".date('Y-m-d')."'
									FROM rh_personne_mouvement
									WHERE rh_personne_mouvement.Id=".$rowSel['Id']." ";
								$resultatInsert=mysqli_query($bdd,$req);
								$IdCree = mysqli_insert_id($bdd);
							}
						}
						else{
							//Suppression si existe un mouvement identique mais avec une date de début >
							$req="UPDATE rh_personne_mouvement
								SET Suppr=1,
								Id_Suppr=".$_SESSION['Id_Personne'].",
								DateSuppr='".date('Y-m-d')."' 
								WHERE Id<>".$IdCree." 
								AND Id_Prestation=".$rowSel['Id_Prestation']." 
								AND Id_Pole=".$rowSel['Id_Pole']." 
								AND Id_Personne=".$rowSel['Id_Personne']."
								AND DateDebut>='".$rowSel['DateDebut']."' 
								AND DateFin<='0001-01-01' ";
							$resultatSuppr=mysqli_query($bdd,$req);
						}
					}
				}
			}
			$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$Etat="";
					if($row['DatePriseEnCompteRH']>'0001-01-01'){
						$Etat="<img src=\"../../Images/tick.png\" border=\"0\">";
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><?php echo stripslashes($row['Id']);?></td>
						<td align="center"><?php echo stripslashes($row['Contrat']);?></td>
						<td><?php echo stripslashes($row['PrestationDepart']);?></td>
						<td><?php echo stripslashes($row['PrestationDestination']);?></td>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php if($row['DateFin']<="0001-01-01"){echo "-";}else{echo AfficheDateJJ_MM_AAAA($row['DateFin']);}?></td>
						<td><?php echo $Etat;?></td>
						<td align="center">
							<?php 
								if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAideRH))){
									if($row['EtatValidation']==0){
										echo "<input class='check' type='checkbox' name='checkHorsTLS_".$row['Id']."' value='' checked>";
									}
								}
							?>
						</td>
						<td align="center">
							<?php 
								if($row['EtatValidation']==0){
									if($_SESSION["Langue"]=="FR"){echo "En attente validation";}else{echo "Waiting validation";}
								}
								else{
									if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAideRH))){
										if($row['DatePriseEnCompteRH']<='0001-01-01'){
											echo "<input class='check' type='checkbox' name='check_".$row['Id']."' value='' checked>";
										}
									}
								}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
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