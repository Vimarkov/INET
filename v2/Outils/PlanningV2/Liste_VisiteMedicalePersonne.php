<?php
require("../../Menu.php");

$DirFichier="Outils/PlanningV2/VM/";

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

?>

<form class="test" action="Liste_VisiteMedicalePersonne.php" method="post">
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
						
					if($LangueAffichage=="FR"){echo "Liste des visites médicales";}else{echo "List of medical visits";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td colspan="10" align="center">
			<table class="GeneralInfo" align="center">
				<tr>
					<td class="Libelle" width="10%" align="center">
						<?php if($_SESSION["Langue"]=="FR"){echo "Date prochaine visite : ";}else{echo "Next date visit : ";} ?>
					<?php
						$requete2="
							SELECT *,
							ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) AS DateProchaineVM
							FROM
							(
								SELECT *
								FROM 
									(SELECT Id,Id_Personne,Id_Metier,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
									(SELECT DateVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
									ORDER BY DateVisite DESC LIMIT 1) AS DateDerniereVM,
									(SELECT (SELECT COUNT(Id) FROM rh_personne_vm_smr WHERE rh_personne_vm_smr.Id_Personne_VM=rh_personne_visitemedicale.Id ) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
									ORDER BY DateVisite DESC LIMIT 1) AS SMR,(@row_number:=@row_number + 1) AS rnk
									FROM rh_personne_contrat 
									WHERE Suppr=0
									AND Id_Personne=".$_SESSION['Id_Personne']."
									AND DateDebut<='".date('Y-m-d')."'
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
								GROUP BY Id_Personne
							) AS table_contrat2

							";
						$result=mysqli_query($bdd,$requete2);
						$nbResulta=mysqli_num_rows($result);
						if($nbResulta>0){
							$row=mysqli_fetch_array($result);
							if($row['DateDerniereVM']>=date('Y-m-d')){echo "<p style='background-color:#d6d5a2'>".AfficheDateJJ_MM_AAAA($row['DateDerniereVM'])."</p>";}
							else{echo AfficheDateJJ_MM_AAAA($row['DateProchaineVM']);}
						}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="5"></td></tr>
		<tr>
			<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date";}else{echo "Date";} ?></td>
			<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Type de visite";}else{echo "Type of visit";} ?></td>
			<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Avis d'aptitude";}else{echo "Notice of Qualification";} ?></td>
			<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "SMR";}else{echo "SMR";} ?></td>
			<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Restriction d'aptitude";}else{echo "Restriction of aptitude";} ?></td>
			<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
		</tr>
		<?php
		$req="SELECT 
				Id,DateVisite,RestrictionAptitude,CommentaireRestriction,PJ_AvisAptitude,
				(SELECT Libelle FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=Id_TypeVisite) AS TypeVisite,
				(SELECT LibelleEN FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=Id_TypeVisite) AS TypeVisiteEN
			FROM rh_personne_visitemedicale
			WHERE Suppr=0 
			AND Id_Personne=".$_SESSION['Id_Personne']." ";
		$req.="ORDER BY DateVisite DESC
		";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		
			$couleur="#EEEEEE";
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					if($_SESSION["Langue"]=="FR"){$restriction="Non";}else{$restriction="No";}
					if($row['RestrictionAptitude']==1){if($_SESSION["Langue"]=="FR"){$restriction="Oui";}else{$restriction="Yes";}}
					
					$smr="";
					if($_SESSION["Langue"]=="FR"){
						$req="SELECT DISTINCT (SELECT Libelle FROM rh_smr WHERE rh_smr.Id=rh_personne_vm_smr.Id_SMR) AS SMR 
						FROM rh_personne_vm_smr
						WHERE Suppr=0 
						AND Id_Personne_VM=".$row['Id']." ";
					}
					else{
						$req="SELECT DISTINCT (SELECT LibelleEN FROM rh_smr WHERE rh_smr.Id=rh_personne_vm_smr.Id_SMR) AS SMR 
						FROM rh_personne_vm_smr
						WHERE Suppr=0 
						AND Id_Personne_VM=".$row['Id']." ";
					}
					$resultSMR=mysqli_query($bdd,$req);
					$nbResultaSMR=mysqli_num_rows($resultSMR);
					if($nbResultaSMR>0){
						while($rowSMR=mysqli_fetch_array($resultSMR))
						{
							if($smr<>""){$smr.="<br>";}
							$smr.=$rowSMR['SMR'];
						}
					}
					
					$avisAptitude="";
					if($row['PJ_AvisAptitude']<>""){
						$avisAptitude='<a class="Info" href="'.$chemin."/".$DirFichier.$row['PJ_AvisAptitude'].'" target="_blank"><img style="width:20px;" src="../../Images/doc.png" style="border:0;" alt="Ouvrir"></a>';
					}
					
			?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateVisite']);?></td>
						<td style="border-bottom:1px dotted #976fa1;"><?php if($_SESSION["Langue"]=="FR"){echo stripslashes($row['TypeVisite']);}else{echo stripslashes($row['TypeVisiteEN']);}?></td>
						<td style="border-bottom:1px dotted #976fa1;"><?php echo $avisAptitude; ?></td>
						<td style="border-bottom:1px dotted #976fa1;"><?php echo $smr; ?></td>
						<td style="border-bottom:1px dotted #976fa1;"><?php echo $restriction;?></td>
						<td style="border-bottom:1px dotted #976fa1;"><?php echo stripslashes($row['CommentaireRestriction']);?></td>
					</tr>
			<?php
				}	
			}
			?>
			</td>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>