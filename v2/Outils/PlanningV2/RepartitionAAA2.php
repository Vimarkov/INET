<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel()
		{window.open("Export_RepartitionAAA2.php","PageExcel","status=no,menubar=no,width=90,height=90");}
	function Recharger(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnCharger2' name='btnCharger2' value='Charger'>";
		document.getElementById('charger').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnCharger2").dispatchEvent(evt);
		document.getElementById('charger').innerHTML="";			
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>

<form class="test" action="RepartitionAAA2.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="listeReleves" id="listeReleves" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#fbb161;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Réparitions AAA";}else{echo "Allocation AAA";}
					?>
					</td>
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestation">
					<option value="0" selected></option>
				<?php
				$requetePresta="SELECT Id, Libelle
					FROM new_competences_prestation
					WHERE Id IN 
						(
							SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
						)
					OR Id_Plateforme IN
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
						)
					ORDER BY Libelle ASC";
				$resultPresta=mysqli_query($bdd,$requetePresta);
				$nbPresta=mysqli_num_rows($resultPresta);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHRepartitionAAA_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestation'];}
				$_SESSION['FiltreRHRepartitionAAA_Prestation']=$PrestationSelect;	
				
				if ($nbPresta > 0)
				{
					while($row=mysqli_fetch_array($resultPresta))
					{
						$selected="";
						if($PrestationSelect==$row['Id']){$selected="selected";}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="12%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHRepartitionAAA_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHRepartitionAAA_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$leMois=($i+1);
							if($leMois<10){$leMois="0".$leMois;}
							echo "<option value='".$leMois."'";
							if($mois== $leMois){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHRepartitionAAA_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHRepartitionAAA_Annee']=$annee;
					?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
				<div id="charger"></div>
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
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		if($_POST){
		$dateDebut=date($annee."-".$mois."-01");;
		$dateFin = $dateDebut;

		$tabDateFin = explode('-', $dateFin);
		$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
		$dateFin = date("Y-m-d", $timestampFin);
		
		$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
			CONCAT(Nom,' ',Prenom) AS Personne,
			(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<='".$dateFin."'
			AND (rh_personne_contrat.DateFin>='".$dateDebut."' OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier,
			new_rh_etatcivil.MatriculeAAA
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect." ";		
		$requeteOrder="ORDER BY Personne ASC";

		$result=mysqli_query($bdd,$req.$requeteOrder);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";
	?>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<div id='Div_EnTete' align="center" style='width:99%;'>
			<table class="TableCompetences" align="center" width="70%">
				<tr>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Heures";}else{echo "Hours";} ?></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id='Div_Personnes' align="center" style='height:500px;width:100%;overflow:auto;'>
			<table class="TableCompetences" align="center" width="70%">
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					$req = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation,
						(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
					FROM rh_personne_mouvement 
					WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
					AND rh_personne_mouvement.EtatValidation=1 
					AND rh_personne_mouvement.Suppr=0
					AND rh_personne_mouvement.Id_Personne=".$row['Id']."
					AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect." ";		
					$requeteOrder="ORDER BY Prestation ASC";

					$resultPresta=mysqli_query($bdd,$req.$requeteOrder);
					$nbResultaPresta=mysqli_num_rows($resultPresta);
					if($nbResultaPresta>0){
						while($rowPresta=mysqli_fetch_array($resultPresta))
						{
							if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
							else{$couleur="#FFFFFF";}
							
							$NbHeures=0;
							for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
								$NbHeures=$NbHeures+NombreHeuresTotalJourneeRepartitionV3($row['Id'],$laDate,$rowPresta['Id_Prestation']);
							}
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="8%"><?php echo stripslashes($row['Personne']);?></td>
								<td width="15%"><?php echo stripslashes($row['Metier']);?></td>
								<td width="8%"><?php echo stripslashes($rowPresta['Prestation']);?></td>
								<td width="6%"><?php echo $NbHeures;?></td>
							</tr>
							<?php
						}
					}
				}
			}

			?>
			</table>
			</div>
		</td>
	</tr>
	<?php 
	}
	?>
</table>
</form>
</body>
</html>