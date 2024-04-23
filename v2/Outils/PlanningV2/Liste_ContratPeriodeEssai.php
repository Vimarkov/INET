<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetreExcel()
	{window.open("Export_ContratPeriodeEssai.php","PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

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

if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Prestation","Metier","TypeDocument","TypeContrat","AgenceInterim","DateDebut","DateFin","DateFinPeriodeEssai");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHContratPeriodeEssai_General']= str_replace($tri." ASC,","",$_SESSION['TriRHContratPeriodeEssai_General']);
			$_SESSION['TriRHContratPeriodeEssai_General']= str_replace($tri." DESC,","",$_SESSION['TriRHContratPeriodeEssai_General']);
			$_SESSION['TriRHContratPeriodeEssai_General']= str_replace($tri." ASC","",$_SESSION['TriRHContratPeriodeEssai_General']);
			$_SESSION['TriRHContratPeriodeEssai_General']= str_replace($tri." DESC","",$_SESSION['TriRHContratPeriodeEssai_General']);
			if($_SESSION['TriRHContratPeriodeEssai_'.$tri]==""){$_SESSION['TriRHContratPeriodeEssai_'.$tri]="ASC";$_SESSION['TriRHContratPeriodeEssai_General'].= $tri." ".$_SESSION['TriRHContratPeriodeEssai_'.$tri].",";}
			elseif($_SESSION['TriRHContratPeriodeEssai_'.$tri]=="ASC"){$_SESSION['TriRHContratPeriodeEssai_'.$tri]="DESC";$_SESSION['TriRHContratPeriodeEssai_General'].= $tri." ".$_SESSION['TriRHContratPeriodeEssai_'.$tri].",";}
			else{$_SESSION['TriRHContratPeriodeEssai_'.$tri]="";}
		}
	}
}

?>

<form class="test" action="Liste_ContratPeriodeEssai.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des fins de période d'essai à moins de 3 mois";}else{echo "List of trial period ends at less than 3 months";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<?php
		if($_SESSION["Langue"]=="FR"){
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,DateFinPeriodeEssai,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
						Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
						(
							SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) AS Prestation
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							LIMIT 1
						) AS Prestation,
						(
							SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_mouvement.Id_Pole) AS Pole
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							LIMIT 1
						) AS Pole,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
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
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				AND DateFinPeriodeEssai>='".date('Y-m-d')."'
				AND DATE_SUB(DateFinPeriodeEssai, INTERVAL 3 MONTH)<'".date('Y-m-d')."'
				";
		}
		else{
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,DateFinPeriodeEssai,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
						Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
						(
							SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) AS Prestation
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							LIMIT 1
						) AS Prestation,
						(
							SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_mouvement.Id_Pole) AS Pole
							FROM rh_personne_mouvement
							WHERE Suppr=0
							AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							LIMIT 1
						) AS Pole,
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
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				AND DateFinPeriodeEssai>='".date('Y-m-d')."'
				AND DATE_SUB(DateFinPeriodeEssai, INTERVAL 3 MONTH)<'".date('Y-m-d')."'
				";
		}
	
		$requeteOrder="";
		if($_SESSION['TriRHContratPeriodeEssai_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHContratPeriodeEssai_General'],0,-1);
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
		<td colspan="6">
		<table width="100%">
			<tr>
				<td colspan="3" align="right"><a style="cursor:pointer;" onclick="window.location='Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH';"><img src="../../Images/refresh.png" style="width:18px;" border="0" title="Refresh" alt="Refresh"></a></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="6">
		<table width="100%">
			<tr>
				<td colspan="3" align="right">
					<a href="javascript:OuvreFenetreExcel()">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_ContratPeriodeEssai.php?Menu=".$Menu."&TDB=1&OngletTDB=RH&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_ContratPeriodeEssai.php?Menu=".$Menu."&TDB=1&OngletTDB=RH&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_ContratPeriodeEssai.php?Menu=".$Menu."&TDB=1&OngletTDB=RH&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=TypeDocument"><?php if($_SESSION["Langue"]=="FR"){echo "Type de document";}else{echo "Document type";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_TypeDocument']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_TypeDocument']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=TypeContrat"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Contract type";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_TypeContrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_TypeContrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=AgenceInterim"><?php if($_SESSION["Langue"]=="FR"){echo "Agence d'intérim";}else{echo "Acting Agency";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_AgenceInterim']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_AgenceInterim']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratPeriodeEssai.php?Menu=<?php echo $Menu; ?>&TDB=1&OngletTDB=RH&Tri=DateFinPeriodeEssai"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin période d'essai";}else{echo "End date trial period";} ?><?php if($_SESSION['TriRHContratPeriodeEssai_DateFinPeriodeEssai']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratPeriodeEssai_DateFinPeriodeEssai']=="ASC"){echo "&darr;";}?></a></td>
				</tr>
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo stripslashes($row['Prestation']." ".$row['Pole']);?></td>
						<td><?php echo stripslashes($row['Metier']); ?></td>
						<td><?php if($row['TypeDocument']=="Nouveau"){if($_SESSION["Langue"]=="FR"){echo "Nouveau";}else{echo "New";}}elseif($row['TypeDocument']=="Avenant"){if($_SESSION["Langue"]=="FR"){echo "Avenant";}} ?></td>
						<td><?php echo stripslashes($row['TypeContrat']); ?></td>
						<td><?php echo stripslashes($row['AgenceInterim']); ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFinPeriodeEssai']);?></td>
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