<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Mode,Menu,Id,Id_Personne,Page)
		{var w=window.open("Ajout_VM.php?Mode="+Mode+"&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+Menu+"&Page="+Page,"PageContrat","status=no,menubar=no,width=700,height=400,scrollbars=1'");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_ListeVM.php?Menu="+document.getElementById('Menu').value,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)) || ($Menu==9 && DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH)))){
if(isset($_GET['Tri'])){
	$tab = array("Personne","Metier","TypeContrat","AgenceInterim","DateDebut","DateFin","DateDerniereVM","TypeVisite","SMR","Restriction","DateProchaineVM");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHVMEC_General']= str_replace($tri." ASC,","",$_SESSION['TriRHVMEC_General']);
			$_SESSION['TriRHVMEC_General']= str_replace($tri." DESC,","",$_SESSION['TriRHVMEC_General']);
			$_SESSION['TriRHVMEC_General']= str_replace($tri." ASC","",$_SESSION['TriRHVMEC_General']);
			$_SESSION['TriRHVMEC_General']= str_replace($tri." DESC","",$_SESSION['TriRHVMEC_General']);
			if($_SESSION['TriRHVMEC_'.$tri]==""){$_SESSION['TriRHVMEC_'.$tri]="ASC";$_SESSION['TriRHVMEC_General'].= $tri." ".$_SESSION['TriRHVMEC_'.$tri].",";}
			elseif($_SESSION['TriRHVMEC_'.$tri]=="ASC"){$_SESSION['TriRHVMEC_'.$tri]="DESC";$_SESSION['TriRHVMEC_General'].= $tri." ".$_SESSION['TriRHVMEC_'.$tri].",";}
			else{$_SESSION['TriRHVMEC_'.$tri]="";}
		}
	}
}

function Titre1($Libelle,$Lien,$Selected){
		$tiret="";
		if($Selected==true){$tiret="border-bottom:4px solid white;";}
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#848339;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#848339;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#848339';\" onmouseout=\"this.style.color='#848339';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}
?>

<form class="test" action="Liste_VisiteMedicaleEC.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#cdcc8d;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des visites médicales";}else{echo "Management of medical visits";}
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
				<tr bgcolor="#e8e7ca">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("A VENIR","Outils/PlanningV2/Liste_VisiteMedicaleEC.php?Menu=".$Menu."",true);}
						else{Titre1("TO COME UP","Outils/PlanningV2/Liste_VisiteMedicaleEC.php?Menu=".$Menu."",true);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_VisiteMedicaleHistorique.php?Menu=".$Menu."",false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_VisiteMedicaleHistorique.php?Menu=".$Menu."",false);}
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} 
				
				$personne=$_SESSION['FiltreRHVMEC_Personne'];
				if($_POST){$personne=$_POST['personne'];}
				$_SESSION['FiltreRHVMEC_Personne']=$personne;
				
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
				
				$MetierSelect=$_SESSION['FiltreRHVMEC_Metier'];
				if($_POST){$MetierSelect=$_POST['metier'];}
				$_SESSION['FiltreRHVMEC_Metier']=$MetierSelect;	
				
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
				
				$TypeContratSelect=$_SESSION['FiltreRHVMEC_TypeContrat'];
				if($_POST){$TypeContratSelect=$_POST['typeContrat'];}
				$_SESSION['FiltreRHVMEC_TypeContrat']=$TypeContratSelect;	
				
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
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de visite :";}else{echo "Type of visit :";} ?>
				<select style="width:150px;" name="typeVisite" onchange="submit();">
				<?php
				if($_SESSION["Langue"]=="FR"){
					$requete="SELECT Id, Libelle
						FROM rh_typevisitemedicale
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				}
				else{
					$requete="SELECT Id, LibelleEN AS Libelle
						FROM rh_typevisitemedicale
						WHERE Suppr=0
						ORDER BY Libelle ASC";
					
				}
				$result=mysqli_query($bdd,$requete);
				$nbType=mysqli_num_rows($result);
				
				$TypeVisiteSelect = 0;
				$Selected = "";
				
				$TypeVisiteSelect=$_SESSION['FiltreRHVMEC_TypeVisite'];
				if($_POST){$TypeVisiteSelect=$_POST['typeVisite'];}
				$_SESSION['FiltreRHVMEC_TypeVisite']=$TypeVisiteSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbType > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($TypeVisiteSelect<>"")
							{if($TypeVisiteSelect==$row['Id']){$selected="selected";}}
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
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "SMR :";}else{echo "SMR :";} ?>
				<select style="width:50px;" name="smr" onchange="submit();">
					<option value='' <?php if($_POST){if($_POST['smr']==""){echo "selected";}} ?>></option>
					<option value='0' <?php if($_POST){if($_POST['smr']=="0"){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
					<option value='1' <?php if($_POST){if($_POST['smr']=="1"){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
				<?php
					$SMRSelect=$_SESSION['FiltreRHVMEC_SMR'];
					if($_POST){$SMRSelect=$_POST['smr'];}
					$_SESSION['FiltreRHVMEC_SMR']=$SMRSelect;
				 ?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Restriction aptitude :";}else{echo "Restriction aptitude :";} ?>
				<select style="width:50px;" name="restriction" onchange="submit();">
					<option value='' <?php if($_POST){if($_POST['restriction']==""){echo "selected";}} ?>></option>
					<option value='0' <?php if($_POST){if($_POST['restriction']=="0"){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
					<option value='1' <?php if($_POST){if($_POST['restriction']=="1"){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
				<?php
					$RestricitionSelect=$_SESSION['FiltreRHVMEC_Restricition'];
					if($_POST){$RestricitionSelect=$_POST['restriction'];}
					$_SESSION['FiltreRHVMEC_Restricition']=$RestricitionSelect;
				 ?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date dernière visite :";}else{echo "Last visit date :";} 
				
				$signeDateDerniereVM=$_SESSION['FiltreRHVMEC_SigneDateDerniereVM'];
				if($_POST){$signeDateDerniereVM=$_POST['signeDateDerniereVM'];}
				$_SESSION['FiltreRHVMEC_SigneDateDerniereVM']=$signeDateDerniereVM;
				?>
				<select name="signeDateDerniereVM" onchange="submit();">
					<option value='=' <?php if($signeDateDerniereVM=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateDerniereVM=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateDerniereVM==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateDerniereVM=$_SESSION['FiltreRHVMEC_DateDerniereVM'];
				if($_POST){$dateDerniereVM=$_POST['dateDerniereVM'];}
				$_SESSION['FiltreRHVMEC_DateDerniereVM']=$dateDerniereVM;
				
				?>
				<input id="dateDerniereVM" name="dateDerniereVM" type="date" value="<?php echo $dateDerniereVM; ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date prochaine visite :";}else{echo "Next date visit :";} 
				
				$signeDateProchaineVM=$_SESSION['FiltreRHVMEC_SigneDateProchaineVM'];
				if($_POST){$signeDateProchaineVM=$_POST['signeDateProchaineVM'];}
				$_SESSION['FiltreRHVMEC_SigneDateProchaineVM']=$signeDateProchaineVM;
				?>
				<select name="signeDateProchaineVM" onchange="submit();">
					<option value='=' <?php if($signeDateProchaineVM=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateProchaineVM=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateProchaineVM==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateProchaineVM=$_SESSION['FiltreRHVMEC_DateProchaineVM'];
				if($_POST){$dateProchaineVM=$_POST['dateProchaineVM'];}
				$_SESSION['FiltreRHVMEC_DateProchaineVM']=$dateProchaineVM;
				
				?>
				<input id="dateProchaineVM" name="dateProchaineVM" type="date" value="<?php echo $dateProchaineVM; ?>" size="10"/>&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		if($_SESSION["Langue"]=="FR"){
			$requete2="
				SELECT *,
				ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) AS DateProchaineVM
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,DateDebut,DateFin,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						(SELECT Id FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS Id_Personne_VM,
						(SELECT DateVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS DateDerniereVM,
						(SELECT (SELECT Libelle FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=rh_personne_visitemedicale.Id_TypeVisite) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS TypeVisite,
						(SELECT Id_TypeVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS Id_TypeVisite,
						(SELECT RestrictionAptitude FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS Restriction,
						(SELECT (SELECT COUNT(Id) FROM rh_personne_vm_smr WHERE rh_personne_vm_smr.Id_Personne_VM=rh_personne_visitemedicale.Id ) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS SMR,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat 
						WHERE Suppr=0
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				";
		}
		else{
			$requete2="
				SELECT *,
				ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) AS DateProchaineVM
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,DateDebut,DateFin,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						(SELECT Id FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS Id_Personne_VM,
						(SELECT DateVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS DateDerniereVM,
						(SELECT (SELECT Libelle FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=rh_personne_visitemedicale.Id_TypeVisite) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS TypeVisite,
						(SELECT Id_TypeVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS Id_TypeVisite,
						(SELECT RestrictionAptitude FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS Restriction,
						(SELECT (SELECT COUNT(Id) FROM rh_personne_vm_smr WHERE rh_personne_vm_smr.Id_Personne_VM=rh_personne_visitemedicale.Id ) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
						ORDER BY DateVisite DESC LIMIT 1) AS SMR,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				";
		}
		if($_SESSION['FiltreRHVMEC_Personne']<>""){
			$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHVMEC_Personne']."%\" ";
		}
		
		if($_SESSION['FiltreRHVMEC_Metier']<>"0"){
			$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHVMEC_Metier']." ";
		}
		if($_SESSION['FiltreRHVMEC_TypeContrat']<>"0"){
			$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHVMEC_TypeContrat']." ";
		}
		if($_SESSION['FiltreRHVMEC_TypeVisite']<>"0"){
			$requete2.=" AND Id_TypeVisite = ".$_SESSION['FiltreRHVMEC_TypeVisite']." ";
		}
		
		if($_SESSION['FiltreRHVMEC_SMR']=="0"){
			$requete2.=" AND (SMR=0 OR SMR='') ";
		}
		elseif($_SESSION['FiltreRHVMEC_SMR']=="1"){
			$requete2.=" AND SMR>0 ";
		}
		
		if($_SESSION['FiltreRHVMEC_Restricition']=="0"){
			$requete2.=" AND Restriction=0 ";
		}
		elseif($_SESSION['FiltreRHVMEC_Restricition']=="1"){
			$requete2.=" AND Restriction=1 ";
		}

		if($_SESSION['FiltreRHVMEC_DateDerniereVM']<>""){
			$requete2.=" AND DateDerniereVM ".$_SESSION['FiltreRHVMEC_SigneDateDerniereVM']." '".TrsfDate_($_SESSION['FiltreRHVMEC_DateDerniereVM'])."' ";
		}
		
		if($_SESSION['FiltreRHVMEC_DateProchaineVM']<>""){
			$requete2.=" AND ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) ".$_SESSION['FiltreRHVMEC_SigneDateProchaineVM']." '".TrsfDate_($_SESSION['FiltreRHVMEC_DateProchaineVM'])."' ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHVMEC_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHVMEC_General'],0,-1);
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
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_VisiteMedicaleEC.php?Menu=".$Menu."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_VisiteMedicaleEC.php?Menu=".$Menu."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_VisiteMedicaleEC.php?Menu=".$Menu."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHVMEC_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriRHVMEC_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=TypeContrat"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Contract type";} ?><?php if($_SESSION['TriRHVMEC_TypeContrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_TypeContrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=AgenceInterim"><?php if($_SESSION["Langue"]=="FR"){echo "Agence d'intérim";}else{echo "Acting Agency";} ?><?php if($_SESSION['TriRHVMEC_AgenceInterim']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_AgenceInterim']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHVMEC_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHVMEC_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=DateDerniereVM"><?php if($_SESSION["Langue"]=="FR"){echo "Date dernière visite";}else{echo "Last visit date";} ?><?php if($_SESSION['TriRHVMEC_DateDerniereVM']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_DateDerniereVM']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=TypeVisite"><?php if($_SESSION["Langue"]=="FR"){echo "Type de visite";}else{echo "Type of visit";} ?><?php if($_SESSION['TriRHVMEC_TypeVisite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_TypeVisite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=SMR"><?php if($_SESSION["Langue"]=="FR"){echo "SMR";}else{echo "SMR";} ?><?php if($_SESSION['TriRHVMEC_SMR']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_SMR']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=Restriction"><?php if($_SESSION["Langue"]=="FR"){echo "Restricition d'aptitude";}else{echo "Restriction of aptitude";} ?><?php if($_SESSION['TriRHVMEC_Restriction']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_Restriction']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VisiteMedicaleEC.php?Menu=<?php echo $Menu; ?>&Tri=DateProchaineVM"><?php if($_SESSION["Langue"]=="FR"){echo "Date prochaine visite";}else{echo "Next visit date";} ?><?php if($_SESSION['TriRHVMEC_DateProchaineVM']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVMEC_DateProchaineVM']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" colspan="2"></td>
				</tr>
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$restriction="";
					$smr="";
					if($row['DateDerniereVM']<>''){
						if($row['Restriction']==0){if($_SESSION["Langue"]=="FR"){$restriction= "Non";}else{$restriction= "No";}}
						else{if($_SESSION["Langue"]=="FR"){$restriction= "Oui";}else{$restriction= "Yes";}}
					}
					if($row['DateDerniereVM']<>''){
						if($row['SMR']==0){if($_SESSION["Langue"]=="FR"){$smr= "Non";}else{$smr= "No";}}
						else{if($_SESSION["Langue"]=="FR"){$smr= "Oui";}else{$smr= "Yes";}}
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo stripslashes($row['Metier']); ?></td>
						<td><?php echo stripslashes($row['TypeContrat']); ?></td>
						<td><?php echo stripslashes($row['AgenceInterim']); ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDerniereVM']);?></td>
						<td><?php echo stripslashes($row['TypeVisite']); ?></td>
						<td><?php echo $smr; ?></td>
						<td><?php echo $restriction;?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateProchaineVM']);?></td>
						<td align="center">
							<?php if($row['Id_Personne_VM']>0){?>
							<a class="Modif" href="javascript:OuvreFenetreModif('M','<?php echo $Menu; ?>','<?php echo $row['Id_Personne_VM']; ?>',<?php echo $row['Id_Personne']; ?>,'Liste_VisiteMedicaleEC');">
								<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
							</a>
							<?php } ?>
						</td>
						<td align="center">
							<a class="Modif" href="javascript:OuvreFenetreModif('A','<?php echo $Menu; ?>',0,<?php echo $row['Id_Personne']; ?>,'Liste_VisiteMedicaleEC');">
								<img src="../../Images/add-icon.png" style="border:0;" alt="Ajout">
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
}
?>
</body>
</html>