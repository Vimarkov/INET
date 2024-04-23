<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetreAjoutAction(Id_Personne,Id_Prestation,Id_Pole,dateEnvoi,vision,createur,acteur,avancement,niveau,lettre)
		{var w=window.open("ModifAction.php?Mode=A&Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&dateEnvoi="+dateEnvoi+"&vision="+vision+"&createur="+createur+"&acteur="+acteur+"&avancement="+avancement+"&niveau="+niveau+"&lettre="+lettre,"PageAction","status=no,menubar=no,width=1100,height=600,scrollbars=1");
		w.focus();
		}
	function SupprimerAction(Id_Action,Id_Personne,Id_Prestation,Id_Pole,dateEnvoi,vision,createur,acteur,avancement,niveau,lettre){
		question="Êtes-vous sûre de vouloir supprimer cette action ?";
		if(window.confirm(question)){
			var w=window.open("ModifAction.php?Mode=S&Id_Action="+Id_Action+"&Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&dateEnvoi="+dateEnvoi+"&vision="+vision+"&createur="+createur+"&acteur="+acteur+"&avancement="+avancement+"&niveau="+niveau+"&lettre="+lettre,"PageAction","status=no,menubar=no,width=1100,height=650,scrollbars=1");
			w.focus();
		}
	}
	function ModifierAction(Id_Action, Id_Personne,Id_Prestation,Id_Pole,dateEnvoi,vision,createur,acteur,avancement,niveau,lettre)
		{var w=window.open("ModifAction.php?Mode=M&Id_Action="+Id_Action+"&Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&dateEnvoi="+dateEnvoi+"&vision="+vision+"&createur="+createur+"&acteur="+acteur+"&avancement="+avancement+"&niveau="+niveau+"&lettre="+lettre,"PageAction","status=no,menubar=no,width=1100,height=700,scrollbars=1");
		w.focus();
		}
	function OuvreFenetreConsultAction(Id)
		{var w=window.open("ConsultAction.php?Id_Action="+Id,"PageConsultAction","status=no,menubar=no,width=1100,height=600,scrollbars=1");
		w.focus();
		}
	function OuvreFenetreExport(Id_Personne,Id_Prestation,Id_Pole)
		{window.open("PA_PERFOS.php?Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole,"PagePAExport","status=no,menubar=no,scrollbars=1,width=30,height=40");}
</script>
<?php
//Vérification des droits de lecture, écriture, administration
$DroitAjout=false;
$resultDroits=mysqli_query($bdd,"SELECT MIN(Id_Poste) FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbDroits=mysqli_num_rows($resultDroits);
$rowDroits=mysqli_fetch_array($resultDroits);
if($rowDroits[0]<3){$DroitAjout=true;}

$resultDroitsPresta=mysqli_query($bdd,"SELECT MIN(Id_Poste), Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']." GROUP BY Id_Prestation, Id_Pole");
$nbDroitsPresta=mysqli_num_rows($resultDroitsPresta);

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>
<form class="test" method="POST" action="Liste_Action.php">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="3">
			<table class="GeneralPage" style="width:100%; border-spacing:0;background-color:#68de2a;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PERFOS/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "SQCDPF # Actions";}
							else{echo "SQCDPF # Actions";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td width=5%>
				&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Prestation :";}else{echo "Activity :";}?>
			</td>
			<td width=25%>
				<select class="prestation" name="prestations" onchange="submit();" style="width:150px">
				<?php
				if($IdPersonneConnectee == 1351 || $IdPersonneConnectee == 2526){
					$req = "SELECT DISTINCT Id_Prestation, 
							(SELECT LEFT(Libelle,7) FROM new_competences_prestation ";
					$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation 
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
									WHERE new_competences_prestation.Id_Plateforme=1 
							ORDER BY NomPrestation ";
				}
				else{
					$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT LEFT(Libelle,7) FROM new_competences_prestation ";
					$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
					$req .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." ORDER BY NomPrestation;";
				}
				$resultPrestation=mysqli_query($bdd,$req);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				if ($nbPrestation > 0)
				{
					if (!empty($_GET['IdPrestationSelect'])){
						echo "<option value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_GET['IdPrestationSelect'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row[0] == $_GET['IdPrestationSelect']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['prestations'])){
						echo "<option value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row[0] == $_POST['prestations']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						echo "<option value='0' Selected></option>";
						$PrestationSelect == 0;
						while($row=mysqli_fetch_array($resultPrestation))
						{
							echo "<option value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width=7%>
				&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Pôle :";}else{echo "Pole :";}?>
			</td>
			<td width=10%>
				<select class="pole" name="pole" onchange="submit();">
				<?php
				if($IdPersonneConnectee == 1351 || $IdPersonneConnectee == 2526){
					$reqPole = "SELECT DISTINCT new_competences_personne_poste_prestation.Id_Pole, ";
					$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
					$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
					$reqPole .= "new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." ORDER BY LibellePole;";
				}
				else{
					$reqPole = "SELECT DISTINCT new_competences_personne_poste_prestation.Id_Pole, ";
					$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
					$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
					$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." AND new_competences_personne_poste_prestation.Id_Poste <3 ";
					$reqPole .= "AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." ORDER BY LibellePole;";
				}	
				$resultPole=mysqli_query($bdd,$reqPole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect = 0;
				$Selected = "";
				if ($nbPole > 0)
				{
					echo "<option value='0' Selected></option>";
					if (!empty($_GET['Id_Pole'])){
						if ($PoleSelect == 0){$PoleSelect = $_GET['Id_Pole'];}
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($row[0] == $_GET['Id_Pole']){$Selected = "Selected";}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['pole'])){
						if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($row[0] == $_POST['pole']){$Selected = "Selected";}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($PoleSelect == 0){$PoleSelect = 0;}
							echo "<option value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width=5%>
				&nbsp;
				Date :
			</td>
			<td width=20%>
				<?php
					$dateEnvoi =0;
					$dateRequete = "";
					if (!empty($_GET['DateSelect'])){
						$dateEnvoi = $_GET['DateSelect'];
						if  ($dateEnvoi <> ""){
							if ($NavigOk ==1){
								$dateDebut = date("Y-m-d",$_GET['DateSelect']);
								
								$tabDateDebut = explode('-', $dateDebut);
								$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
							}
							else{
								$dateDebut = date("d/m/Y",$_GET['DateSelect']);
								
								$tabDateDebut = explode('/', $dateDebut);
								$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
							}
							$dateRequete = date("Y-m-d",$timestampDebut);
						}
						else{
							$dateDebut = "";
							$dateRequete = "";
						}
					}
					else{
						if (!empty($_POST['DateDeDebut'])){
							if  ($_POST['DateDeDebut'] <> ""){
								if ($NavigOk ==1){
									$dateDebut = $_POST['DateDeDebut'];
									$tabDateDebut = explode('-', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
								else{
									$dateDebut = $_POST['DateDeDebut'];
									$tabDateDebut = explode('/', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
							}
							else{
								$dateDebut = "";
								$dateEnvoi = 0;
								$dateRequete = "";
							}
						}
						else{
							$dateDebut = "";
							$dateEnvoi = 0;
							$dateRequete = "";
						}
					}
				?>
				<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
				<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="Valider">
			</td>
			<td width=5%>
				&nbsp; Vision :
			</td>
			<td width=23%>
				<select class="vision" name="vision" onchange="submit();">
					<?php
						$Vision1 = "";
						$Vision2 = "";
						$Vision3 = "";
						$visionSelect =0;
						if (!empty($_GET['VisionSelect'])){
							if ($_GET['VisionSelect'] == "1"){$Vision1 = "Selected";$visionSelect =1;}
							if ($_GET['VisionSelect'] == "2"){$Vision2 = "Selected";$visionSelect =2;}
							if ($_GET['VisionSelect'] == "3"){$Vision3 = "Selected";$visionSelect =3;}
							
							echo "<option value='1' ".$Vision1.">Actions ouvertes sous ma responsabilité</option>";
							echo "<option value='2' ".$Vision2.">Mon backlog</option>";
							echo "<option value='3' ".$Vision3.">Historique actions closes</option>";
						}
						elseif (!empty($_POST['vision'])){
							if ($_POST['vision'] == "1"){$Vision1 = "Selected";$visionSelect =1;}
							if ($_POST['vision'] == "2"){$Vision2 = "Selected";$visionSelect =2;}
							if ($_POST['vision'] == "3"){$Vision3 = "Selected";$visionSelect =3;}
							
							echo "<option value='1' ".$Vision1.">Actions ouvertes sous ma responsabilité</option>";
							echo "<option value='2' ".$Vision2.">Mon backlog</option>";
							echo "<option value='3' ".$Vision3.">Historique actions closes</option>";
						}
						else{
							$visionSelect =1;
							echo "<option value='1' Selected>Actions ouvertes sous ma responsabilité</option>";
							echo "<option value='2'>Mon backlog</option>";
							echo "<option value='3'>Historique actions closes</option>";
						}
					
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td width=5%>
				&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Créateur :";}else{echo "Creator :";}?>
			</td>
			<td width=25%>
				<select class="createur" name="createur" onchange="submit();" style="width:300px">
				<?php
				$req = "SELECT DISTINCT new_action.Id_Createur, ";
				$req .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_action.Id_Createur) AS Createur ";
				$req .= " FROM new_action ORDER BY Createur;";
				
				$resultCreateur=mysqli_query($bdd,$req);
				$nbCreateur=mysqli_num_rows($resultCreateur);
				
				$CreateurSelect = 0;
				$Selected = "";
				if ($nbCreateur > 0)
				{
					if (!empty($_GET['IdCreateurSelect'])){
						echo "<option value='0' Selected></option>";
						if ($CreateurSelect == 0){$CreateurSelect = $_GET['IdCreateurSelect'];}
						while($row=mysqli_fetch_array($resultCreateur))
						{
							if ($row[0] == $_GET['IdCreateurSelect']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['createur'])){
						echo "<option value='0' Selected></option>";
						if ($CreateurSelect == 0){$CreateurSelect = $_POST['createur'];}
						while($row=mysqli_fetch_array($resultCreateur))
						{
							if ($row[0] == $_POST['createur']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						echo "<option value='0' Selected></option>";
						$CreateurSelect == 0;
						while($row=mysqli_fetch_array($resultCreateur))
						{
							echo "<option value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width=7%>
				&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Avancement :";}else{echo "Advancement :";}?>
			</td>
			<td width=10%>
				<select class="avancement" name="avancement" onchange="submit();">
				<?php
				$AvancementSelect = 6;
				if (!empty($_GET['AvancementSelect'])){
					echo "<option value='6' Selected></option>";
					if ($AvancementSelect == 6){$AvancementSelect = $_GET['AvancementSelect'];}
					$Selected = "";
					if ($AvancementSelect == "5"){$Selected = "Selected";}
					echo "<option value='5' ".$Selected.">Point non pris en compte</option>";
					$Selected = "";
					if ($AvancementSelect == "1"){$Selected = "Selected";}
					echo "<option value='1' ".$Selected.">Point pris en compte</option>";
					$Selected = "";
					if ($AvancementSelect == "2"){$Selected = "Selected";}
					echo "<option value='2' ".$Selected.">Point e/c</option>";
					$Selected = "";
					if ($AvancementSelect == "3"){$Selected = "Selected";}
					echo "<option value='3' ".$Selected.">Solution/Action</option>";
					$Selected = "";
					if ($AvancementSelect == "4"){$Selected = "Selected";}
					echo "<option value='4' ".$Selected.">Action clôturée</option>";
				}
				elseif (!empty($_POST['avancement'])){
					echo "<option value='6' Selected></option>";
					if ($AvancementSelect == 6){$AvancementSelect = $_POST['avancement'];}
					$Selected = "";
					if ($AvancementSelect == 5){$Selected = "Selected";}
					echo "<option value='5' ".$Selected.">Point non pris en compte</option>";
					$Selected = "";
					if ($AvancementSelect == 1){$Selected = "Selected";}
					echo "<option value='1' ".$Selected.">Point pris en compte</option>";
					$Selected = "";
					if ($AvancementSelect == 2){$Selected = "Selected";}
					echo "<option value='2' ".$Selected.">Point e/c</option>";
					$Selected = "";
					if ($AvancementSelect == 3){$Selected = "Selected";}
					echo "<option value='3' ".$Selected.">Solution/Action</option>";
					$Selected = "";
					if ($AvancementSelect == 4){$Selected = "Selected";}
					echo "<option value='4' ".$Selected.">Action clôturée</option>";
				}
				else{
					echo "<option value='6' Selected></option>";
					$Selected = "";
					echo "<option value='5' ".$Selected.">Point non pris en compte</option>";
					echo "<option value='1' ".$Selected.">Point pris en compte</option>";
					echo "<option value='2' ".$Selected.">Point e/c</option>";
					echo "<option value='3' ".$Selected.">Solution/Action</option>";
					echo "<option value='4' ".$Selected.">Action clôturée</option>";
				}
				 ?>
				</select>
			</td>
			<td width=5%>
				&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Niveau :";}else{echo "Level :";}?>
			</td>
			<td width=20%>
				<select class="niveau" name="niveau" onchange="submit();">
				<?php
				$NiveauSelect = 0;
				if (!empty($_GET['NiveauSelect'])){
					echo "<option value='0' Selected></option>";
					if ($NiveauSelect == 0){$NiveauSelect = $_GET['NiveauSelect'];}
					$Selected = "";
					if ($NiveauSelect == "1"){$Selected = "Selected";}
					echo "<option value='1' ".$Selected.">1</option>";
					$Selected = "";
					if ($NiveauSelect == "2"){$Selected = "Selected";}
					echo "<option value='2' ".$Selected.">2</option>";
					$Selected = "";
					if ($NiveauSelect == "3"){$Selected = "Selected";}
					echo "<option value='3' ".$Selected.">3</option>";
					$Selected = "";
					if ($NiveauSelect == "4"){$Selected = "Selected";}
					echo "<option value='4' ".$Selected.">4</option>";
				}
				elseif (!empty($_POST['niveau'])){
					echo "<option value='0' Selected></option>";
					if ($NiveauSelect == 0){$NiveauSelect = $_POST['niveau'];}
					$Selected = "";
					if ($NiveauSelect == 1){$Selected = "Selected";}
					echo "<option value='1' ".$Selected.">1</option>";
					$Selected = "";
					if ($NiveauSelect == 2){$Selected = "Selected";}
					echo "<option value='2' ".$Selected.">2</option>";
					$Selected = "";
					if ($NiveauSelect == 3){$Selected = "Selected";}
					echo "<option value='3' ".$Selected.">3</option>";
					$Selected = "";
					if ($NiveauSelect == 4){$Selected = "Selected";}
					echo "<option value='4' ".$Selected.">4</option>";
				}
				else{
					echo "<option value='0' Selected></option>";
					$Selected = "";
					echo "<option value='1' ".$Selected.">1</option>";
					echo "<option value='2' ".$Selected.">2</option>";
					echo "<option value='3' ".$Selected.">3</option>";
					echo "<option value='4' ".$Selected.">4</option>";
				}
				 ?>
				</select>
			</td>
			<td width=5%>
				&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Lettre :";}else{echo "Letter :";}?>
			</td>
			<td width=23%>
				<select class="lettre" name="lettre" onchange="submit();">
				<?php
				$LettreSelect = "";
				if (!empty($_GET['LettreSelect'])){
					echo "<option value='' Selected></option>";
					if ($LettreSelect == ""){$LettreSelect = $_GET['LettreSelect'];}
					$Selected = "";
					if ($LettreSelect == "S"){$Selected = "Selected";}
					echo "<option value='S' ".$Selected.">S</option>";
					$Selected = "";
					if ($LettreSelect == "Q"){$Selected = "Selected";}
					echo "<option value='Q' ".$Selected.">Q</option>";
					$Selected = "";
					if ($LettreSelect == "C"){$Selected = "Selected";}
					echo "<option value='C' ".$Selected.">C</option>";
					$Selected = "";
					if ($LettreSelect == "D"){$Selected = "Selected";}
					echo "<option value='D' ".$Selected.">D</option>";
					$Selected = "";
					if ($LettreSelect == "P"){$Selected = "Selected";}
					echo "<option value='P' ".$Selected.">P</option>";
					$Selected = "";
					if ($LettreSelect == "F"){$Selected = "Selected";}
					echo "<option value='F' ".$Selected.">F</option>";
				}
				elseif (!empty($_POST['lettre'])){
					echo "<option value='' Selected></option>";
					if ($LettreSelect == ""){$LettreSelect = $_POST['lettre'];}
					$Selected = "";
					if ($LettreSelect == "S"){$Selected = "Selected";}
					echo "<option value='S' ".$Selected.">S</option>";
					$Selected = "";
					if ($LettreSelect == "Q"){$Selected = "Selected";}
					echo "<option value='Q' ".$Selected.">Q</option>";
					$Selected = "";
					if ($LettreSelect == "C"){$Selected = "Selected";}
					echo "<option value='C' ".$Selected.">C</option>";
					$Selected = "";
					if ($LettreSelect == "D"){$Selected = "Selected";}
					echo "<option value='D' ".$Selected.">D</option>";
					$Selected = "";
					if ($LettreSelect == "P"){$Selected = "Selected";}
					echo "<option value='P' ".$Selected.">P</option>";
					$Selected = "";
					if ($LettreSelect == "F"){$Selected = "Selected";}
					echo "<option value='F' ".$Selected.">F</option>";
				}
				else{
					echo "<option value='' Selected></option>";
					$Selected = "";
					echo "<option value='S' ".$Selected.">S</option>";
					echo "<option value='Q' ".$Selected.">Q</option>";
					echo "<option value='C' ".$Selected.">C</option>";
					echo "<option value='D' ".$Selected.">D</option>";
					echo "<option value='P' ".$Selected.">P</option>";
					echo "<option value='F' ".$Selected.">F</option>";
				}
				 ?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td width=5%>
				&nbsp; <?php if($_SESSION['Langue']=="FR"){echo "Acteur";}else{echo "Actor";}?>
			</td>
			<td width=25%>
				<select class="acteur" name="acteur" onchange="submit();" style="width:300px">
				<?php
				$req = "SELECT DISTINCT new_action.Id_Acteur, ";
				$req .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_action.Id_Acteur) AS Acteur ";
				$req .= " FROM new_action WHERE new_action.Id_Acteur<>0 ORDER BY Acteur;";
				
				$resultActeur=mysqli_query($bdd,$req);
				$nbActeur=mysqli_num_rows($resultActeur);
				
				$ActeurSelect = 0;
				$Selected = "";
				if ($nbActeur > 0)
				{
					if (!empty($_GET['IdActeurSelect'])){
						echo "<option value='0' Selected></option>";
						if ($ActeurSelect == 0){$ActeurSelect = $_GET['IdActeurSelect'];}
						while($row=mysqli_fetch_array($resultActeur))
						{
							if ($row[0] == $_GET['IdActeurSelect']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['acteur'])){
						echo "<option value='0' Selected></option>";
						if ($ActeurSelect == 0){$ActeurSelect = $_POST['acteur'];}
						while($row=mysqli_fetch_array($resultActeur))
						{
							if ($row[0] == $_POST['acteur']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						echo "<option value='0' Selected></option>";
						$ActeurSelect == 0;
						while($row=mysqli_fetch_array($resultActeur))
						{
							echo "<option value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr align="center"><td><img src="../../Images/Legende_Avancement.gif" width="60%" border='0' alt="Legende" title="Legende"></td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" colspan="4">
			<?php 
				$parametre= $_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.",".$dateEnvoi.",".$visionSelect.",".$CreateurSelect.",".$ActeurSelect.",".$AvancementSelect.",".$NiveauSelect.",'".$LettreSelect."'";
			?>
			<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreAjoutAction(<?php echo $parametre ?>)">&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Créer une action";}else{echo "Create an action";}?>&nbsp;</a>
			<?php
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreExport(".$_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.")'>&nbsp;Plan d'action SQCDPF&nbsp;</a>";
			?>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
	if($nbDroits>0)
	{
	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="2%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;N°</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="4%"><?php if($_SESSION['Langue']=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="7%"><?php if($_SESSION['Langue']=="FR"){echo "Pôle";}else{echo "Pole";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%"><?php if($_SESSION['Langue']=="FR"){echo "Niveau";}else{echo "Level";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="2%"><?php if($_SESSION['Langue']=="FR"){echo "Lettre";}else{echo "Letter";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="2%"><?php if($_SESSION['Langue']=="FR"){echo "Point chaud";}else{echo "Hot point";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="7%"><?php if($_SESSION['Langue']=="FR"){echo "Date création";}else{echo "Creation date";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="7%"><?php if($_SESSION['Langue']=="FR"){echo "Vacation";}else{echo "Vacation";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="10%"><?php if($_SESSION['Langue']=="FR"){echo "Problème (description)";}else{echo "Problem (description)";}?></td>
 					<td class="EnTeteTableauCompetences" style="text-align:center;" width="10%"><?php if($_SESSION['Langue']=="FR"){echo "Action";}else{echo "Action";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="7%"><?php if($_SESSION['Langue']=="FR"){echo "Nb actions<br>liées";}else{echo "Nb linked<br>actions";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="6%"><?php if($_SESSION['Langue']=="FR"){echo "Acteur";}else{echo "Actor";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="8%"><?php if($_SESSION['Langue']=="FR"){echo "Délais";}else{echo "Delay";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="5%"><?php if($_SESSION['Langue']=="FR"){echo "Avancement";}else{echo "Advancement";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="7%"><?php if($_SESSION['Langue']=="FR"){echo "Date de solde";}else{echo "Closure date";}?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="4%"></td>
				</tr>
				<?php
					$req = "SELECT new_action.Id, new_action.Type,Vacation, new_action.Lettre, new_action.PointChaud, new_action.DateCreation, new_action.Id_Createur, new_action.Probleme, new_action.Action, new_action.Id_Acteur, ";
					$req .= "new_action.Delais, new_action.Avancement, new_action.DateSolde, new_action.Niveau, new_action.ReprisDQ506, new_action.Id_ActionLiee, ";
					$req .= "(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id = new_action.Id_Prestation) AS Prestation, new_action.Id_Prestation, ";
					$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_action.Id_Pole) AS Pole, new_action.Id_Pole, ";
					$req .= "(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Createur) AS NomCreateur, ";
					$req .= "(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Createur) AS PrenomCreateur, ";
					$req .= "(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Acteur) AS NomActeur, ";
					$req .= "(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Acteur) AS PrenomActeur ";
					$req .= "FROM new_action ";
					$req .= "WHERE ";
					//$req .= "Type='SQCDPF' AND ";
					if($dateRequete <> ""){
						$req .= "new_action.DateCreation ='".$dateRequete."' AND ";
					}
					if($CreateurSelect <> 0){
						$req .= "new_action.Id_Createur =".$CreateurSelect." AND ";
					}
					if($ActeurSelect <> 0){
						$req .= "new_action.Id_Acteur =".$ActeurSelect." AND ";
					}
					if($AvancementSelect <> 6){
						if($AvancementSelect == 5){$req .= "new_action.Avancement =0 AND ";}
						else{$req .= "new_action.Avancement =".$AvancementSelect." AND ";}
					}
					if($NiveauSelect <> 0){
						$req .= "new_action.Niveau =".$NiveauSelect." AND ";
					}
					if($LettreSelect <> ""){
						$req .= "new_action.Lettre ='".$LettreSelect."' AND ";
					}
					$req .= "(";
					if ($PrestationSelect <> 0){
						$req .= "new_action.Id_Prestation =".$PrestationSelect." AND ";
						if ($PoleSelect <> 0){
							$req .= "new_action.Id_Pole =".$PoleSelect." AND ";
							
							$reqMin = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']. " AND Id_Prestation=".$PrestationSelect;
							$reqMin .= "  AND Id_Pole=".$PoleSelect;
							$reqMax = "SELECT MAX(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']. " AND Id_Prestation=".$PrestationSelect;
							$reqMax .= "  AND Id_Pole=".$PoleSelect;
							$resultMin=mysqli_query($bdd,$reqMin);
							$rowMin=mysqli_fetch_array($resultMin);
							$resultMax=mysqli_query($bdd,$reqMax);
							$rowMax=mysqli_fetch_array($resultMax);
							$niveauMin=0;
							$niveauMax=0;
							if($rowMin['Id_Poste'] < 3){$niveauMin=1;}
							else if($rowMin['Id_Poste'] == 3 || $rowMin['Id_Poste'] == 5){$niveauMin=2;}
							else if($rowMin['Id_Poste'] == 4 || $rowMin['Id_Poste'] == 6 || $rowMin['Id_Poste'] == 7){$niveauMin=3;}
							else if($rowMin['Id_Poste'] == 8 || $rowMin['Id_Poste'] == 9){$niveauMin=4;}
							if($rowMax['Id_Poste'] < 3){$niveauMax=1;}
							else if($rowMax['Id_Poste'] == 3 || $rowMax['Id_Poste'] == 5){$niveauMax=2;}
							else if($rowMax['Id_Poste'] == 4 || $rowMax['Id_Poste'] == 6 || $rowMax['Id_Poste'] == 7){$niveauMax=3;}
							else if($rowMax['Id_Poste'] == 8 || $rowMax['Id_Poste'] == 9){$niveauMax=4;}
							if($visionSelect == 1){
								$req .= "new_action.Niveau <=".$niveauMax." AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement<>4 AND ";
							}
							elseif($visionSelect == 2){
								$req .= "((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement<>4 AND ";
							}
							elseif($visionSelect == 3){
								$req .= "((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement=4 AND ";
							}
						}
						else{
							$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation ";
							$reqPrestaPoste .= "WHERE Id_Personne=".$_SESSION['Id_Personne']." AND Id_Prestation=".$PrestationSelect.";";
							$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
							$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
							if($nbPrestaPoste > 0){
								if($nbPrestaPoste > 1){$req .= "(";}
								while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
									$req .= "(new_action.Id_Pole =".$rowPrestaPoste['Id_Pole']." ";
									
									$reqMin = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']. " AND Id_Prestation=".$PrestationSelect;
									$reqMin .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
									$reqMax = "SELECT MAX(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']. " AND Id_Prestation=".$PrestationSelect;
									$reqMax .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
									$resultMin=mysqli_query($bdd,$reqMin);
									$rowMin=mysqli_fetch_array($resultMin);
									$resultMax=mysqli_query($bdd,$reqMax);
									$rowMax=mysqli_fetch_array($resultMax);
									$niveauMin=0;
									$niveauMax=0;
									if($rowMin['Id_Poste'] < 3){$niveauMin=1;}
									else if($rowMin['Id_Poste'] == 3 || $rowMin['Id_Poste'] == 5){$niveauMin=2;}
									else if($rowMin['Id_Poste'] == 4 || $rowMin['Id_Poste'] == 6 || $rowMin['Id_Poste'] == 7){$niveauMin=3;}
									else if($rowMin['Id_Poste'] == 8 || $rowMin['Id_Poste'] == 9){$niveauMin=4;}
									if($rowMax['Id_Poste'] < 3){$niveauMax=1;}
									else if($rowMax['Id_Poste'] == 3 || $rowMax['Id_Poste'] == 5){$niveauMax=2;}
									else if($rowMax['Id_Poste'] == 4 || $rowMax['Id_Poste'] == 6 || $rowMax['Id_Poste'] == 7){$niveauMax=3;}
									else if($rowMax['Id_Poste'] == 8 || $rowMax['Id_Poste'] == 9){$niveauMax=4;}
									if($visionSelect == 1){
										$req .= "AND new_action.Niveau <=".$niveauMax." AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement<>4 ";
									}
									elseif($visionSelect == 2){
										$req .= "AND ((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement<>4 ";
									}
									elseif($visionSelect == 3){
										$req .= "AND ((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement=4 ";
									}
									$req .= ") OR ";
								}
								$req = substr($req,0,-3);
								if($nbPrestaPoste > 1){$req .= ")";}
								$req .= " AND ";
							}
						}
					}
					else{
						if($IdPersonneConnectee == 1351 || $IdPersonneConnectee == 2526){
							$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole 
											FROM new_competences_personne_poste_prestation 
											LEFT JOIN new_competences_prestation 
											ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
											WHERE new_competences_prestation.Id_Plateforme=1 ";
						}
						else{
							$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation ";
							$reqPrestaPoste .= "WHERE Id_Personne=".$_SESSION['Id_Personne'].";";
						}
						$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
						$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
						if($nbPrestaPoste > 0){
							while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
								$req .= "(new_action.Id_Prestation =".$rowPrestaPoste['Id_Prestation']." AND new_action.Id_Pole =".$rowPrestaPoste['Id_Pole']." ";
								
								$reqMin = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']. " AND Id_Prestation=".$rowPrestaPoste['Id_Prestation'];
								$reqMin .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
								$reqMax = "SELECT MAX(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']. " AND Id_Prestation=".$rowPrestaPoste['Id_Prestation'];
								$reqMax .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
								$resultMin=mysqli_query($bdd,$reqMin);
								$rowMin=mysqli_fetch_array($resultMin);
								$resultMax=mysqli_query($bdd,$reqMax);
								$rowMax=mysqli_fetch_array($resultMax);
								$niveauMin=0;
								$niveauMax=0;
								if($rowMin['Id_Poste'] < 3){$niveauMin=1;}
								else if($rowMin['Id_Poste'] == 3 || $rowMin['Id_Poste'] == 5){$niveauMin=2;}
								else if($rowMin['Id_Poste'] == 4 || $rowMin['Id_Poste'] == 6 || $rowMin['Id_Poste'] == 7){$niveauMin=3;}
								else if($rowMin['Id_Poste'] == 8 || $rowMin['Id_Poste'] == 9){$niveauMin=4;}
								if($rowMax['Id_Poste'] < 3){$niveauMax=1;}
								else if($rowMax['Id_Poste'] == 3 || $rowMax['Id_Poste'] == 5){$niveauMax=2;}
								else if($rowMax['Id_Poste'] == 4 || $rowMax['Id_Poste'] == 6 || $rowMax['Id_Poste'] == 7){$niveauMax=3;}
								else if($rowMax['Id_Poste'] == 8 || $rowMax['Id_Poste'] == 9){$niveauMax=4;}
								if($visionSelect == 1){
									$req .= "AND new_action.Niveau <=".$niveauMax." AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement<>4 ";
								}
								elseif($visionSelect == 2){
									$req .= "AND ((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement<>4 ";
								}
								elseif($visionSelect == 3){
									$req .= "AND ((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND (new_action.Id_Createur=".$_SESSION['Id_Personne']." OR new_action.Id_Acteur=".$_SESSION['Id_Personne']."  OR new_action.Id_Acteur=0) AND new_action.Avancement=4 ";
								}
								$req .= ") OR ";
							}
							$req = substr($req,0,-3);
							$req .= " AND ";
						}
					}
					if (substr($req,-4) =="AND "){
						$req = substr($req,0,-4);
					}
					$req .= ") ";
					$req .= "ORDER BY DateCreation DESC ";
					$req .= "LIMIT 0,100;";
					
					$resultAction=mysqli_query($bdd,$req);
					$nbAction=mysqli_num_rows($resultAction);

					$vert = "#00b050";
					$rose = "#ff7c80";
					$bleu = "#00b0f0";
					$violet = "#cc66ff";
					$jaune = "#ffff00";
					
					if($nbAction > 0){
						while($rowAction=mysqli_fetch_array($resultAction)){
							$couleur = "";
							$couleurCloture = "";
							$couleurCloture2 ="";
							if ($rowAction['Avancement'] == 4){
								$couleur = $vert;
								$req2 = "SELECT Id ";
								$req2 .= "FROM new_action ";
								if($rowAction['Id_ActionLiee'] == 0){
									$req2 .= "WHERE (new_action.Id =".$rowAction['Id']." OR new_action.Id_ActionLiee=".$rowAction['Id'].") AND Avancement<>4 ";
								}
								else{
									$req2 .= "WHERE (new_action.Id =".$rowAction['Id_ActionLiee']." OR new_action.Id_ActionLiee=".$rowAction['Id_ActionLiee'].") AND Avancement<>4 ";
								}
								$resultAction2=mysqli_query($bdd,$req2);
								$nbAction2=mysqli_num_rows($resultAction2);
								
								if($nbAction2 == 0){
									$couleurCloture2 = $vert;
								}
								$couleurCloture = "bgcolor='".$vert."'";
							}
							else{
								if($rowAction['Niveau'] == 1){
									$couleur = $rose;
								}
								elseif($rowAction['Niveau'] == 2){
									$couleur = $bleu;
								}
								elseif($rowAction['Niveau'] == 3){
									$couleur = $violet;
								}
								elseif($rowAction['Niveau'] == 4){
									$couleur = $jaune;
								}
							}
							echo "<tr align='center' bgcolor='".$couleurCloture2."'>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='4%'>";
							echo "<a href='javascript:OuvreFenetreConsultAction(".$rowAction['Id'].");'>";
							echo "<img src='../../Images/Loupe.gif' border='0' alt='Consulter' title='Consulter'>";
							echo "</a>&nbsp;&nbsp;&nbsp;&nbsp;";
							echo $rowAction['Id']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='4%'>".$rowAction['Prestation']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='7%'>".$rowAction['Pole']."</td>";							
							echo "<td style='border-bottom:1px #d9d9d7 solid;' bgcolor='".$couleur."' width='3%'>".$rowAction['Niveau']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2%'>".$rowAction['Lettre']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='2%'>".$rowAction['PointChaud']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='7%'>".$rowAction['DateCreation']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='7%'>".$rowAction['Vacation']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10%'>".$rowAction['Probleme']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='10%'>".$rowAction['Action']."</td>";
							$nbActionLiee = 0;
							if($rowAction['Id_ActionLiee'] == 0){
								$reqActionLiee = "SELECT Id FROM new_action WHERE Id_ActionLiee = ".$rowAction['Id'];
								$resultActionLiee=mysqli_query($bdd,$reqActionLiee);
								$nbActionLiee=mysqli_num_rows($resultActionLiee); 
							}
							else{
								$reqActionLiee = "SELECT Id FROM new_action WHERE Id_ActionLiee = ".$rowAction['Id_ActionLiee'];
								$resultActionLiee=mysqli_query($bdd,$reqActionLiee);
								$nbActionLiee=mysqli_num_rows($resultActionLiee);
							}
							if ($nbActionLiee==0){$nbActionLiee="";}
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='7%'>".$nbActionLiee."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='6%'>".$rowAction['NomActeur']." ".$rowAction['PrenomActeur']."</td>";
							
							$dateSolde = "";
							if($rowAction['DateSolde'] > "0001-01-01"){
								$dateSolde = $rowAction['DateSolde'];
							}
							
							$delais = "";
							$couleurDelais = $couleurCloture;
							if($rowAction['Delais'] > "0001-01-01"){
								$delais = $rowAction['Delais'];
								if($rowAction['DateSolde'] <= "0001-01-01"){
									if($rowAction['Delais']<date("Y-m-d")){$couleurDelais = "bgcolor='#ff0000'";}
								}
							}
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='8%' ".$couleurDelais.">".$delais."</td>";
							$image = "";
							
							if ($rowAction['Avancement'] == 0){
								$image = "<img src='../../Images/NonPrisEnCompte.gif' border='0' alt='NonPrisEnCompte' title='Non pris en compte'>";
							}
							elseif ($rowAction['Avancement'] == 1){
								$image = "<img src='../../Images/EnCompte.gif' border='0' alt='EnCompte' title='En compte'>";
							}
							elseif ($rowAction['Avancement'] == 2){
								$image = "<img src='../../Images/EnCours.gif' border='0' alt='EnCours' title='En cours'>";
							}
							elseif ($rowAction['Avancement'] == 3){
								$image = "<img src='../../Images/Solution.gif' border='0' alt='Solution' title='Solution/action'>";
							}
							elseif ($rowAction['Avancement'] >= 4){
								$image = "<img src='../../Images/Cloturee.gif' border='0' alt='Cloturee' title='Cloturée'>";
							}
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='5%' ".$couleurCloture.">".$image."</td>";							
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='8%' ".$couleurCloture.">".$dateSolde."</td>";							
							echo "<td style='border-bottom:1px #d9d9d7 solid;' width='4%'>";
						
							$bvalide=false;
							$bvalideSuppr = false;
							if($rowAction['Id_Createur'] == $_SESSION['Id_Personne']){
								$bvalideSuppr = true;
								if($rowAction['Id_Acteur'] == $_SESSION['Id_Personne'] || $rowAction['Id_Acteur'] ==0){
									$bvalide=true;
								}
							}
							else{
								if($rowAction['Id_Acteur'] == $_SESSION['Id_Personne']){
									$bvalide=true;
								}
								else{
									$reqRecherche = "SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
									$reqRecherche .= "AND Id_Prestation=".$rowAction['Id_Prestation']." AND Id_Pole=".$rowAction['Id_Pole']." ";
									if($rowAction['Niveau'] == 1){
										$reqRecherche .= "AND (Id_Poste=1 OR Id_Poste=2)";
									}
									elseif($rowAction['Niveau'] == 2){
										$reqRecherche .= "AND (Id_Poste=3 OR Id_Poste=5)";
									}
									if($rowAction['Niveau'] == 3){
										$reqRecherche .= "AND (Id_Poste=4 OR Id_Poste=6 OR Id_Poste=7)";
									}
									elseif($rowAction['Niveau'] == 4){
										$reqRecherche .= "AND (Id_Poste=8 OR Id_Poste=9)";
									}
									$resultRecherche=mysqli_query($bdd,$reqRecherche);
									$nbRecherche=mysqli_num_rows($resultRecherche);
									if($nbRecherche > 0){
										$bvalide=true;
									}
								}
							}
							if($bvalide == true){
								$parametre= $rowAction['Id'].",".$_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.",".$dateEnvoi.",".$visionSelect.",".$CreateurSelect.",".$ActeurSelect.",".$AvancementSelect.",".$NiveauSelect.",'".$LettreSelect."'";
								
								echo "<a href=\"javascript:ModifierAction(".$parametre.")\">";								
								echo "<img src='../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>";
								echo "</a>&nbsp;";
							}
							if($bvalideSuppr == true){
								$parametre= $rowAction['Id'].",".$_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.",".$dateEnvoi.",".$visionSelect.",".$CreateurSelect.",".$ActeurSelect.",".$AvancementSelect.",".$NiveauSelect.",'".$LettreSelect."'";
								
								echo "<a href=\"javascript:SupprimerAction(".$parametre.")\">";								
								echo "<img src='../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>";
								echo "</a>&nbsp;";
							}
							echo "</td>";
							
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr height='15'>
		<td>
			<?php
				if($_SESSION['Langue']=="FR"){echo "Seuls les 100 premières actions vous concernant sont affichées.";}
				else{echo "Only the first 100 actions are displayed.";}
			?>
			
		</td>
	</tr>
<?php
	}			//Fin vérification des droits
	else
	{
?>
		<tr><td class="Erreur">
			<?php
				if($_SESSION['Langue']=="FR"){echo "Vous n'avez pas les droits pour afficher le contenu de ce dossier.";}
				else{echo "You do not have permission to view the contents of this folder.";}
			?>
		</td></tr>
<?php
	}
?>
</table>
</form>

<?php
	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>