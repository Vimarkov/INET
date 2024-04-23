<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreSuppr(Menu,Id){
		var w=window.open("Suppr_DemandeConge.php?Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageDemande","status=no,menubar=no,scrollbars=yes,width=80,height=30");		
	}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_DemandesSansAffectation.php?Menu="+Menu,"PageExcel","status=no,menubar=no,width=900,height=450");}
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
?>

<form class="test" action="Liste_DemandesSansAffectation.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#edf430;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des demandes de congées sans affectation";}else{echo "List of unassigned leave requests";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete="SELECT rh_personne_demandeabsence.Id,DateCreation,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne,
			(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
			AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat
			FROM rh_personne_demandeabsence
			WHERE rh_personne_demandeabsence.Suppr=0 
			AND rh_personne_demandeabsence.EtatN1<>-1
			AND rh_personne_demandeabsence.EtatN2<>-1
			AND rh_personne_demandeabsence.Conge=1
			AND rh_personne_demandeabsence.Id_Prestation=0 
			";
		$requeteOrder="";
		$requeteOrder="ORDER BY Personne ";
		
		$result=mysqli_query($bdd,$requete);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);

		$result=mysqli_query($bdd,$requete.$requeteOrder.$requete3);
	
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_DemandesSansAffectation.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_DemandesSansAffectation.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_DemandesSansAffectation.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="50%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande";}else{echo "Request number";} ?></td>
					<td class="EnTeteTableauCompetences" width="22%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date création";}else{echo "Creation Date";} ?></td>
					<?php 
					if($Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="2%"><?php if($_SESSION["Langue"]=="FR"){echo "Supp.";}else{echo "Delete";} ?></td>
					<?php } ?>
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
						<td><?php echo stripslashes($row['Contrat']);?></td>
						<td><?php echo stripslashes($row['Id']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>

						<?php if($Menu==4){ ?>
						<td>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
						</td>
						<?php } ?>
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