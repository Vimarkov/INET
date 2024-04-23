<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id,Page)
		{var w=window.open("Modif_ODM.php?Mode=M&Id="+Id+"&Menu="+Menu+"&Page="+Page,"PageODM","status=no,menubar=no,width=1000,height=550,scrollbars=1");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id)
		{var w=window.open("Modif_ODM.php?Mode=S&Id="+Id+"&Menu="+Menu,"PageODM","status=no,menubar=no,width=1000,height=550,scrollbars=1'");
		w.focus();
		}
	function CreerODMMasse(Menu,Page)
		{var w=window.open("Creer_ODMenMasse.php?Mode=M&Menu="+Menu+"&Page="+Page,"PageODM","status=no,menubar=no,width=900,height=300,scrollbars=1");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_ListeODM.php?Menu="+document.getElementById('Menu').value,"PageExcel","status=no,menubar=no,width=90,height=45");}
	function ODMExcel(Id)
		{window.open("Export_ODM.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=45");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Metier","TypeContrat","DateDebut","DateFin","Motif","Etat","Titre","Plateforme");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHODM_General']= str_replace($tri." ASC,","",$_SESSION['TriRHODM_General']);
			$_SESSION['TriRHODM_General']= str_replace($tri." DESC,","",$_SESSION['TriRHODM_General']);
			$_SESSION['TriRHODM_General']= str_replace($tri." ASC","",$_SESSION['TriRHODM_General']);
			$_SESSION['TriRHODM_General']= str_replace($tri." DESC","",$_SESSION['TriRHODM_General']);
			if($_SESSION['TriRHODM_'.$tri]==""){$_SESSION['TriRHODM_'.$tri]="ASC";$_SESSION['TriRHODM_General'].= $tri." ".$_SESSION['TriRHODM_'.$tri].",";}
			elseif($_SESSION['TriRHODM_'.$tri]=="ASC"){$_SESSION['TriRHODM_'.$tri]="DESC";$_SESSION['TriRHODM_General'].= $tri." ".$_SESSION['TriRHODM_'.$tri].",";}
			else{$_SESSION['TriRHODM_'.$tri]="";}
		}
	}
}

function Titre1($Libelle,$Lien,$Selected){
		$tiret="";
		if($Selected==true){$tiret="border-bottom:4px solid white;";}
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#5c4165';\" onmouseout=\"this.style.color='#5c4165';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}
?>

<form class="test" action="Liste_ODMEC.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des contrats";}else{echo "Contract management";}
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
				<tr bgcolor="#cdbad2">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("CONTRATS EN COURS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",false);}
						else{Titre1("CONTRACTS IN PROGRESS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("ODM EN COURS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",true);}
						else{Titre1("MISSION ORDER IN PROGRESS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",true);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT AUGMENTATIONS","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT INCREASES","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
				<select class="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
					<option value='' selected></option>
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreRHODM_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHODM_Plateforme']=$PlateformeSelect;	
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect==$row['Id']){$selected="selected";}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} 
				
				$personne=$_SESSION['FiltreRHODM_Personne'];
				if($_POST){$personne=$_POST['personne'];}
				$_SESSION['FiltreRHODM_Personne']=$personne;
				
				?>
				<input id="personne" name="personne" type="texte" value="<?php echo $personne; ?>" size="20"/>&nbsp;&nbsp;
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?>
				<select style="width:150px;" name="metier" onchange="submit();">
				<?php
				if($_SESSION["Langue"]=="FR"){
					$requete="SELECT Id, Libelle
						FROM new_competences_metier
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				}
				else{
					$requete="SELECT Id, LibelleEN AS Libelle
						FROM new_competences_metier
						WHERE Suppr=0
						ORDER BY Libelle ASC";
					
				}
				$result=mysqli_query($bdd,$requete);
				$nbMetier=mysqli_num_rows($result);
				
				$MetierSelect = 0;
				$Selected = "";
				
				$MetierSelect=$_SESSION['FiltreRHODM_Metier'];
				if($_POST){$MetierSelect=$_POST['metier'];}
				$_SESSION['FiltreRHODM_Metier']=$MetierSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbMetier > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($MetierSelect<>"")
							{if($MetierSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat :";}else{echo "Type of contract :";} ?>
				<select style="width:150px;" name="typeContrat" onchange="submit();">
				<?php
				if($_SESSION["Langue"]=="FR"){
					$requete="SELECT Id, Libelle
						FROM rh_typecontrat
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				}
				else{
					$requete="SELECT Id, LibelleEN AS Libelle
						FROM rh_typecontrat
						WHERE Suppr=0
						ORDER BY Libelle ASC";
					
				}
				$result=mysqli_query($bdd,$requete);
				$nbType=mysqli_num_rows($result);
				
				$TypeContratSelect = 0;
				$Selected = "";
				
				$TypeContratSelect=$_SESSION['FiltreRHODM_TypeContrat'];
				if($_POST){$TypeContratSelect=$_POST['typeContrat'];}
				$_SESSION['FiltreRHODM_TypeContrat']=$TypeContratSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbType > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($TypeContratSelect<>"")
							{if($TypeContratSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="5%">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:OuvreFenetreExcel()">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de début :";}else{echo "Start date :";} 
				
				$signeDateDebut=$_SESSION['FiltreRHODM_SigneDateDebut'];
				if($_POST){$signeDateDebut=$_POST['signeDateDebut'];}
				$_SESSION['FiltreRHODM_SigneDateDebut']=$signeDateDebut;
				?>
				<select id="signeDateDebut" name="signeDateDebut" onchange="submit();">
					<option value='=' <?php if($signeDateDebut=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateDebut=="<"){echo "selected";} ?>><</option>
					<option value='<=' <?php if($signeDateDebut=="<="){echo "selected";} ?>>&#8804;</option>
					<option value='>' <?php if($signeDateDebut==">"){echo "selected";} ?>>></option>
					<option value='>=' <?php if($signeDateDebut==">="){echo "selected";} ?>>&#8805;</option>
				</select>
				<?php 
				$dateDebut=$_SESSION['FiltreRHODM_DateDebut'];
				if($_POST){$dateDebut=$_POST['dateDebut'];}
				$_SESSION['FiltreRHODM_DateDebut']=$dateDebut;
				
				?>
				<input id="dateDebut" name="dateDebut" type="date" value="<?php echo $dateDebut; ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} 
				
				$signeDateFin=$_SESSION['FiltreRHODM_SigneDateFin'];
				if($_POST){$signeDateFin=$_POST['signeDateFin'];}
				$_SESSION['FiltreRHODM_SigneDateFin']=$signeDateFin;
				?>
				<select id="signeDateFin" name="signeDateFin" onchange="submit();">
					<option value='=' <?php if($signeDateFin=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateFin=="<"){echo "selected";} ?>><</option>
					<option value='<=' <?php if($signeDateFin=="<="){echo "selected";} ?>>&#8804;</option>
					<option value='>' <?php if($signeDateFin==">"){echo "selected";} ?>>></option>
					<option value='>=' <?php if($signeDateFin==">="){echo "selected";} ?>>&#8805;</option>
				</select>
				<?php 
				$dateFin=$_SESSION['FiltreRHODM_DateFin'];
				if($_POST){$dateFin=$_POST['dateFin'];}
				$_SESSION['FiltreRHODM_DateFin']=$dateFin;
				
				?>
				<input id="dateFin" name="dateFin" type="date" value="<?php echo $dateFin; ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
				<select style="width:150px;" name="etat" onchange="submit();">
				<?php
				$EtatSelect = 0;
				$EtatSelect=$_SESSION['FiltreRHODM_Etat'];
				if($_POST){$EtatSelect=$_POST['etat'];}
				$_SESSION['FiltreRHODM_Etat']=$EtatSelect;	
				?>
				<option value="0" selected></option>
				<option value="1" <?php if($EtatSelect==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Attente signature siège";}else{echo "Waiting signature head office";} ?></option>
				<option value="2" <?php if($EtatSelect==2){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Signature siège et attente signature salarié";}else{echo "Signature head office and waiting signature employee";} ?></option>
				<option value="3" <?php if($EtatSelect==3){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Signature salarié OK";}else{echo "Employee Signature OK";} ?></option>
				<option value="4" <?php if($EtatSelect==4){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Retour signé au siège (clôturé)";}else{echo "Signed return to head office (closed)";} ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		$laDate=date('Y-m-d');
		if($_SESSION['FiltreRHODM_DateDebut']<>""){
			if(TrsfDate_($_SESSION['FiltreRHODM_DateDebut'])>$laDate){
				$laDate=TrsfDate_($_SESSION['FiltreRHODM_DateDebut']);
			}
		}
		
		if($_SESSION["Langue"]=="FR"){
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Titre,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
						FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
						DateDebut,DateFin,Motif,
						IF(DateSignatureSiege=0,1,
							IF(DateSignatureSalarie=0,2,
								IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
									IF(DateRetourSigneAuSiege>'0001-01-01',4,
									0
									)
								)
							)
						) AS Etat,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".$laDate."'
						AND (DateFin>='".$laDate."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('ODM')
						ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				";
		}
		else{
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Titre,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
						FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
						DateDebut,DateFin,Motif,
						IF(DateSignatureSiege=0,1,
							IF(DateSignatureSalarie=0,2,
								IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
									IF(DateRetourSigneAuSiege>'0001-01-01',4,
									0
									)
								)
							)
						) AS Etat,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".$laDate."'
						AND (DateFin>='".$laDate."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('ODM')
						ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				";
		}
		if($_SESSION['FiltreRHODM_Plateforme']<>""){
			$requete2.=" AND Id_Plateforme = ".$_SESSION['FiltreRHODM_Plateforme']." ";
		}
		if($_SESSION['FiltreRHODM_Personne']<>""){
			$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHODM_Personne']."%\" ";
		}
		
		if($_SESSION['FiltreRHODM_Metier']<>"0"){
			$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHODM_Metier']." ";
		}
		if($_SESSION['FiltreRHODM_TypeContrat']<>"0"){
			$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHODM_TypeContrat']." ";
		}
		if($_SESSION['FiltreRHODM_Etat']<>"0"){
			$requete2.=" AND Etat = ".$_SESSION['FiltreRHODM_Etat']." ";
		}
		if($_SESSION['FiltreRHODM_DateDebut']<>""){
			$requete2.=" AND DateDebut ".$_SESSION['FiltreRHODM_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHODM_DateDebut'])."' ";
		}
		if($_SESSION['FiltreRHODM_DateFin']<>""){
			if($_SESSION['FiltreRHODM_SigneDateFin']=="<"){
				$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
				AND DateFin>'0001-01-01'
				";
			}
			elseif($_SESSION['FiltreRHODM_SigneDateFin']==">"){
				$requete2.=" AND (DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
				OR DateFin<='0001-01-01' )
				";
			}
			elseif($_SESSION['FiltreRHODM_SigneDateFin']=="="){
				$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
				";
			}
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHODM_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHODM_General'],0,-1);
		}
		
		$result=mysqli_query($bdd,$requete2);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*100).",100";
		$nbResulta=mysqli_num_rows($result);
		$result=mysqli_query($bdd,$requete2.$requeteOrder.$requete3);
		$nombreDePages=ceil($nbResulta/100);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="right">
			<a class="Bouton" href="javascript:CreerODMMasse(<?php echo $Menu; ?>,'Liste_ODMEC')"><?php if($_SESSION["Langue"]=="FR"){echo "Créer ODM pour la liste";}else{echo "Create ODM for the list";} ?></a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_ODMEC.php?Menu=".$Menu."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_ODMEC.php?Menu=".$Menu."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_ODMEC.php?Menu=".$Menu."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHODM_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=Titre"><?php if($_SESSION["Langue"]=="FR"){echo "Titre";}else{echo "Title";} ?><?php if($_SESSION['TriRHODM_Titre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_Titre']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriRHODM_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=TypeContrat"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Contract type";} ?><?php if($_SESSION['TriRHODM_TypeContrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_TypeContrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHODM_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHODM_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=Motif"><?php if($_SESSION["Langue"]=="FR"){echo "Mission";}else{echo "Mission";} ?><?php if($_SESSION['TriRHODM_Motif']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_Motif']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="13%"><?php if($_SESSION["Langue"]=="FR"){echo "Frais de déplacement/Repas";}else{echo "Travel Expenses / Meals";} ?></td>
					<td class="EnTeteTableauCompetences" width="13%"><?php if($_SESSION["Langue"]=="FR"){echo "Primes-indemnités diverses";}else{echo "Miscellaneous allowances";} ?></td>
					<td class="EnTeteTableauCompetences" width="13%"><?php if($_SESSION["Langue"]=="FR"){echo "Moyens de déplacement";}else{echo "Means of displacement";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ODMEC.php?Menu=<?php echo $Menu; ?>&Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?><?php if($_SESSION['TriRHODM_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHODM_Etat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="1%"></td>
				</tr>
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>,'Liste_ODMEC')"><?php echo stripslashes($row['Personne']);?></a></td>
						<td><?php echo stripslashes($row['Titre']); ?></td>
						<td><?php echo stripslashes($row['Metier']); ?></td>
						<td><?php echo stripslashes($row['TypeContrat']); ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
						<td><?php echo stripslashes(str_replace("///","",$row['Motif']));?></td>
						<td>
						<?php 
							$fraisDeplacementRepas="";
							if($row['MontantIPD']>0){
								if($_SESSION["Langue"]=="FR"){$fraisDeplacementRepas= "Indemnité déplacement : ".$row['MontantIPD']."&euro;";}else{$fraisDeplacementRepas= "Displacement allowance : ".$row['MontantIPD']."&euro;";}
							}
							if($row['MontantRepas']>0){
								if($fraisDeplacementRepas<>""){$fraisDeplacementRepas.="<br>";}
								if($_SESSION["Langue"]=="FR"){$fraisDeplacementRepas.= "Indemnité repas : ".$row['MontantRepas']."&euro;";}else{$fraisDeplacementRepas.= "Meal allowance : ".$row['MontantRepas']."&euro;";}
							}
							if($row['MontantIGD']>0){
								if($fraisDeplacementRepas<>""){$fraisDeplacementRepas.="<br>";}
								if($_SESSION["Langue"]=="FR"){$fraisDeplacementRepas.= "Indemnité de découcher + petit déjeuner : ".$row['MontantIGD']."&euro;";}else{$fraisDeplacementRepas.= "Allowance to leave + breakfast : ".$row['MontantIGD']."&euro;";}
							}
							if($row['MontantRepasGD']>0){
								if($fraisDeplacementRepas<>""){$fraisDeplacementRepas.="<br>";}
								if($_SESSION["Langue"]=="FR"){$fraisDeplacementRepas.= "Indemnité de repas (GD) : ".$row['MontantRepasGD']."&euro;";}else{$fraisDeplacementRepas.= "Allowance to leave + breakfast : ".$row['MontantRepasGD']."&euro;";}
							}
							if($row['FraisReel']>0){
								if($fraisDeplacementRepas<>""){$fraisDeplacementRepas.="<br>";}
								if($_SESSION["Langue"]=="FR"){$fraisDeplacementRepas.= "Frais réels : ".$row['FraisReel']."&euro;";}else{$fraisDeplacementRepas.= "Real costs : ".$row['FraisReel']."&euro;";}
							}
							echo $fraisDeplacementRepas;
						?>
						</td>
						<td>
						<?php 
							$primesDiverses="";
							if($row['PrimeResponsabilite']>0){
								if($_SESSION["Langue"]=="FR"){$primesDiverses= "Prime de responsabilité : ".$row['PrimeResponsabilite']."&euro;";}else{$primesDiverses= "Liability premium : ".$row['PrimeResponsabilite']."&euro;";}
							}
							if($row['PrimeEquipe']>0){
								if($primesDiverses<>""){$primesDiverses.="<br>";}
								if($_SESSION["Langue"]=="FR"){$primesDiverses.= "Prime d'équipe : ".$row['PrimeEquipe']."&euro;";}else{$primesDiverses.= "Team bonus : ".$row['PrimeEquipe']."&euro;";}
							}
							if($row['IndemniteOutillage']>0){
								if($primesDiverses<>""){$primesDiverses.="<br>";}
								if($_SESSION["Langue"]=="FR"){$primesDiverses.= "Indemnité outillage : ".$row['IndemniteOutillage']."&euro;";}else{$primesDiverses.= "Tool allowance : ".$row['IndemniteOutillage']."&euro;";}
							}
							if($row['PanierGrandeNuit']>0){
								if($primesDiverses<>""){$primesDiverses.="<br>";}
								if($_SESSION["Langue"]=="FR"){$primesDiverses.= "Panier grande nuit : ".$row['PanierGrandeNuit']."&euro;";}else{$primesDiverses.= "Basket big night : ".$row['PanierGrandeNuit']."&euro;";}
							}
							if($row['MajorationVSD']>0){
								if($primesDiverses<>""){$primesDiverses.="<br>";}
								if($_SESSION["Langue"]=="FR"){$primesDiverses.= "Majoration VSD : ".$row['MajorationVSD']."%";}else{$primesDiverses.= "FSS enhancement : ".$row['MajorationVSD']."%";}
							}
							if($row['PanierVSD']>0){
								if($primesDiverses<>""){$primesDiverses.="<br>";}
								if($_SESSION["Langue"]=="FR"){$primesDiverses.= "Panier VSD : ".$row['PanierVSD']."&euro;";}else{$primesDiverses.= "FSS Basket : ".$row['PanierVSD']."&euro;";}
							}
							echo $primesDiverses;
						?>
						</td>
						<td>
						<?php 
							$req="SELECT 
								(SELECT Libelle FROM rh_moyendeplacement WHERE Id=Id_MoyenDeplacement) AS Moyen,
								(SELECT Libelle FROM rh_moyendeplacement WHERE Id=Id_MoyenDeplacement) AS MoyenEN,
								Montant,Periodicite 
								FROM rh_personne_contrat_moyendeplacement 
								WHERE Suppr=0 
								AND Id_Personne_Contrat=".$row['Id'];
							$resultM=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($resultM);
							if ($nbResulta>0){
								$nb=0;
								while($rowM=mysqli_fetch_array($resultM)){
									if($nb>0){echo "<br>";}
									if($_SESSION["Langue"]=="FR"){
										echo $rowM['Moyen']." : ".$rowM['Montant']."&euro; ->Périodicité ".$rowM['Periodicite'];
									}
									else{
										echo $rowM['MoyenEN']." : ".$rowM['Montant']."&euro; ->Periodicity ".$rowM['Periodicite'];
									}
									$nb++;
								}
							}
						?>
						</td>
						<td>
							<?php 
								if($row['Etat']==1){if($_SESSION["Langue"]=="FR"){echo "Attente signature siège";}else{echo "Waiting signature head office";}}
								elseif($row['Etat']==2){if($_SESSION["Langue"]=="FR"){echo "Signature siège et attente signature salarié";}else{echo "Signature head office and waiting signature employee";}}
								elseif($row['Etat']==3){if($_SESSION["Langue"]=="FR"){echo "Signature salarié OK";}else{echo "Employee Signature OK";}}
								elseif($row['Etat']==4){if($_SESSION["Langue"]=="FR"){echo "Retour signé au siège (clôturé)";}else{echo "Signed return to head office (closed)";}}
							?>
						</td>
						<td><?php echo stripslashes($row['Plateforme']);?></td>
						<td>
							<a href="javascript:ODMExcel(<?php echo $row['Id'];?>)">
								<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>
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
}
?>
	
</body>
</html>