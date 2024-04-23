<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel()
		{window.open("Export_CoutSalariesAAA.php","PageExcel","status=no,menubar=no,width=90,height=90");}
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
if($Menu==11 && DroitsFormationPlateforme(array($IdPosteControleGestion))){
?>

<form class="test" action="CoutsSalariesAAA.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="listeReleves" id="listeReleves" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#3098f6;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Coûts salariés AAA";}else{echo "AAA employee costs";}
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
			<td width="3%" class="Libelle" valign="center">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Division :";}else{echo "Division :";} ?>
			</td>
			<td width="7%">
				<select style="width:100px;height:100px;" name="division[]" multiple>
					
				<?php
				$requeteDiv="SELECT DISTINCT Id_Division AS Id, 
					(SELECT Libelle FROM new_competences_division WHERE Id=Id_Division) AS Libelle
					FROM new_competences_prestation
					WHERE Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteControleGestion.")
						)
					AND Id_Division<>0
					ORDER BY Libelle ASC";
				$resultDiv=mysqli_query($bdd,$requeteDiv);
				$nbDiv=mysqli_num_rows($resultDiv);
				
				$DivSelect = 0;
				$Selected = "";
				
				$DivSelect=$_SESSION['FiltreRHCoutAAA_Division'];
				if($_POST){$DivSelect=$_POST['division'];}
				$_SESSION['FiltreRHCoutAAA_Division']=$DivSelect;	
				
				$selected="";
				foreach($DivSelect as $div){
					if($div==0){$selected="selected";}
				}
				echo "<option value='0' ".$selected."></option>";
				if ($nbDiv > 0)
				{
					while($row=mysqli_fetch_array($resultDiv))
					{
						$selected="";
						foreach($DivSelect as $div){
							if($div==$row['Id']){$selected="selected";}
						}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="3%" class="Libelle" valign="center">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
			</td>
			<td width="7%">
				<select class="plateforme" style="width:100px;height:100px;" name="plateforme[]" multiple>
					
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteControleGestion.")
						)
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreRHCoutAAA_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHCoutAAA_Plateforme']=$PlateformeSelect;	
				
				$selected="";
				foreach($PlateformeSelect as $plat){
					if($plat==0){$selected="selected";}
				}
				echo "<option value='0' ".$selected."></option>";
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						foreach($PlateformeSelect as $plat){
							if($plat==$row['Id']){$selected="selected";}
						}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="8%" class="Libelle" valign="center">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Responsable projet :";}else{echo "Project manager :";} ?>
			</td>
			<td width="7%">
				<select style="width:200px;height:100px;" name="respProjet[]" multiple>
					
				<?php
				$requeteRespProjet="SELECT DISTINCT Id_Personne AS Id, 
							(SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
							FROM new_competences_personne_poste_prestation
							WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
							AND Id_Personne<>0
					ORDER BY Personne ASC";
				$resultRespProjet=mysqli_query($bdd,$requeteRespProjet);
				$nbRespProjet=mysqli_num_rows($resultRespProjet);
				
				$RespProjetSelect = 0;
				$Selected = "";
				
				$RespProjetSelect=$_SESSION['FiltreRHCoutAAA_RespProjet'];
				if($_POST){$RespProjetSelect=$_POST['respProjet'];}
				$_SESSION['FiltreRHCoutAAA_RespProjet']=$RespProjetSelect;	
				
				$selected="";
				foreach($RespProjetSelect as $respP){
					if($respP==0){$selected="selected";}
				}
				echo "<option value='0' ".$selected."></option>";
				if ($nbRespProjet > 0)
				{
					while($row=mysqli_fetch_array($resultRespProjet))
					{
						$selected="";
						foreach($RespProjetSelect as $respP){
							if($respP==$row['Id']){$selected="selected";}
						}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Personne'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="5%" class="Libelle" valign="center">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
			</td>
			<td width="7%">
				<select style="width:100px;height:100px;" name="prestation[]" multiple>
					
				<?php
				$requetePresta="SELECT Id, SUBSTR(Libelle,1,7) AS Libelle
					FROM new_competences_prestation
					WHERE Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteControleGestion.")
						)
					ORDER BY Libelle ASC";
				$resultPresta=mysqli_query($bdd,$requetePresta);
				$nbPresta=mysqli_num_rows($resultPresta);
				
				$PrestaSelect = 0;
				$Selected = "";
				
				$PrestaSelect=$_SESSION['FiltreRHCoutAAA_Prestation'];
				if($_POST){$PrestaSelect=$_POST['prestation'];}
				$_SESSION['FiltreRHCoutAAA_Prestation']=$PrestaSelect;	
				
				$selected="";
				foreach($PrestaSelect as $presta){
					if($presta==0){$selected="selected";}
				}
				echo "<option value='0' ".$selected."></option>";
				if ($nbPresta > 0)
				{
					while($row=mysqli_fetch_array($resultPresta))
					{
						$selected="";
						foreach($PrestaSelect as $presta){
							if($presta==$row['Id']){$selected="selected";}
						}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="11%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="moisD" name="moisD" >
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$moisD=$_SESSION['FiltreRHCoutAAA_MoisD'];
						if($_POST){$moisD=$_POST['moisD'];}
						$_SESSION['FiltreRHCoutAAA_MoisD']=$moisD;
						
						for($i=0;$i<=11;$i++){
							$leMois=($i+1);
							if($leMois<10){$leMois="0".$leMois;}
							echo "<option value='".$leMois."'";
							if($moisD== $leMois){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$anneeD=$_SESSION['FiltreRHCoutAAA_AnneeD'];
						if($_POST){$anneeD=$_POST['anneeD'];}
						if($anneeD==""){$anneeD=date("Y");}
						$_SESSION['FiltreRHCoutAAA_AnneeD']=$anneeD;
					?>
				</select>
				<input onKeyUp="nombre(this)" id="anneeD" name="anneeD" type="texte" value="<?php echo $anneeD; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<?php
			?>
			<td width="3%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
				<div id="charger"></div>
			</td>
			<td width="3%">
				&nbsp;&nbsp;&nbsp;
				<!--<a href="javascript:OuvreFenetreExcel()">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>-->
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="5%" class="Libelle" valign="center" colspan="2">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Traitement des contrats intérim :";}else{echo "Interim contract processing :";} ?>
			</td>
			<td width="7%" colspan="4">
				<select style="width:400px;" name="traitementInterim" >
					<?php 
						$traitementInterim=$_SESSION['FiltreRHCoutAAA_TypeTraitement'];
						if($_POST){$traitementInterim=$_POST['traitementInterim'];}
						$_SESSION['FiltreRHCoutAAA_TypeTraitement']=$traitementInterim;
					?>
					<option value="0" <?php if($traitementInterim==0){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Ne pas compter aprés la date de fin de contrat";}else{echo "Do not count after the end date of the contract";} ?></option>
					<option value="1" <?php if($traitementInterim==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Compter aprés date de fin de contrat si toujours affecté à la prestation";}else{echo "Count after contract end date if still assigned to the service";} ?></option>
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
	if($_POST){
		$dateDebut=date($anneeD."-".$moisD."-01");;
		$dateFin = $dateDebut;

		$tabDateFin = explode('-', $dateFin);
		$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
		$dateFin = date("Y-m-d", $timestampFin);
		
		$req="SELECT Id, SUBSTR(Libelle,1,7) AS Libelle 
			FROM new_competences_prestation 
			WHERE Id>0 ";
		
		$Id_Division="";
		foreach($DivSelect as $division){
			if($Id_Division<>""){$Id_Division.=",";}
			if($division<>0){$Id_Division.=$division;}
		}
		if($Id_Division<>""){
			$req.=" AND Id_Division IN (".$Id_Division.") ";
		}
		
		$Id_Plateforme="";
		foreach($PlateformeSelect as $plateforme){
			if($Id_Plateforme<>""){$Id_Plateforme.=",";}
			if($plateforme<>0){$Id_Plateforme.=$plateforme;}
		}
		if($Id_Plateforme<>""){
			$req.=" AND Id_Plateforme IN (".$Id_Plateforme.") ";
		}
		
		$Id_Presta="";
		foreach($PrestaSelect as $presta){
			if($Id_Presta<>""){$Id_Presta.=",";}
			if($presta<>0){$Id_Presta.=$presta;}
		}
		if($Id_Presta<>""){
			$req.=" AND Id IN (".$Id_Presta.") ";
		}
		
		$Id_RespProjet="";
		foreach($RespProjetSelect as $respProjet){
			if($Id_RespProjet<>""){$Id_RespProjet.=",";}
			if($respProjet<>0){$Id_RespProjet.=$respProjet;}
		}
		if($Id_RespProjet<>""){
			$req.=" AND Id IN (
						SELECT Id_Prestation
						FROM new_competences_personne_poste_prestation
						WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
						AND Id_Personne IN (".$Id_RespProjet.")
				) ";
		}
		
		$req.=" ORDER BY Libelle ";
		$resultPresta=mysqli_query($bdd,$req);
		$nbResultaPresta=mysqli_num_rows($resultPresta);

		$couleur="#FFFFFF";
	?>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<div id='Div_EnTete' align="center" style='width:99%;'>
			<table class="TableCompetences" align="center" width="50%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="40%"><?php echo $moisD."/".$anneeD; ?></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id='Div_Prestations' align="center" style='height:300px;width:100%;overflow:auto;'>
			<table class="TableCompetences" align="center" width="50%">
		<?php
			
			if($nbResultaPresta>0){
				while($rowPresta=mysqli_fetch_array($resultPresta))
				{
					$req = "SELECT DISTINCT new_rh_etatcivil.Id
					FROM new_rh_etatcivil
					LEFT JOIN rh_personne_mouvement 
					ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
					WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
					AND rh_personne_mouvement.EtatValidation=1 
					AND rh_personne_mouvement.Suppr=0
					AND rh_personne_mouvement.Id_Prestation=".$rowPresta['Id']." ";	

					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					
					$NbHeures=0;
					if($nbResulta>0){
						while($row=mysqli_fetch_array($result))
						{
							for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
								
								if(estSalarie($laDate,$row['Id'] || estInterne($laDate,$row['Id']) || $traitementInterim==1 || (estInterim($laDate,$row['Id']) && $traitementInterim==0) ){
									$NbHeures=$NbHeures+NombreHeuresTotalJourneeCout($row['Id'],$laDate,$rowPresta['Id']);
								}
							}
						}
					}
					
					$taux=TauxPrestation($rowPresta['Id']);
					
					$valeur=$NbHeures*$taux;
					$valeur = number_format($valeur, 2, ',', ' ');

					?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td width="10%"><?php echo stripslashes($rowPresta['Libelle']);?></td>
						<td width="10%"><?php echo number_format($NbHeures, 2, ',', ' ')." x ".$taux." = ".$valeur; ?> 	&#8364;</td>
					</tr>
					<?php
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
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
<?php
}

?>
</body>
</html>