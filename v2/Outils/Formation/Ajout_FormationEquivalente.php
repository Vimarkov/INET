<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un groupe de formations</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				if(document.getElementById('FormationSelect').length<2){alert('Veillez sélectionner au moins 2 formations.');return false;}
				for(y=0;y<document.getElementById('FormationSelect').length;y++){document.getElementById('FormationSelect').options[y].selected = true;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				if(document.getElementById('FormationSelect').length<2){alert('Make sure you select at least 2 trainings.');return false;}
				for(y=0;y<document.getElementById('FormationSelect').length;y++){document.getElementById('FormationSelect').options[y].selected = true;}
			}
			return true;
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	if(isset($_POST['validation'])){
		$requete="";
		if($_POST['Mode']=="Ajout")
		{
			if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_formationequivalente WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme']))==0)
			{
				$requete="INSERT INTO form_formationequivalente (Id_Plateforme, Libelle,Id_Personne_MAJ,Date_MAJ)";
				$requete.=" VALUES (";
				$requete.=$_POST['Id_Plateforme'];
				$requete.=",'".addslashes($_POST['Libelle'])."'";
				$requete.=",".$IdPersonneConnectee."";
				$requete.=",'".date('Y-m-d')."'";
				$requete.=")";
			}
		}
		elseif($_POST['Mode']=="Modif")
		{
			if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_formationequivalente WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme']." AND Id!=".$_POST['Id']))==0)
			{
				$result=mysqli_query($bdd,"DELETE FROM form_formationequivalente_formationplateforme WHERE Id_FormationEquivalente=".$_POST['Id']);
				$requete="UPDATE form_formationequivalente SET";
				$requete.=" Id_Plateforme=".$_POST['Id_Plateforme'];
				$requete.=", Libelle='".addslashes($_POST['Libelle'])."'";
				$requete.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
				$requete.=", Date_MAJ='".date('Y-m-d')."'";
				$requete.=" WHERE Id=".$_POST['Id'];
			}
		}
		if($requete!="")
		{
			$result=mysqli_query($bdd,$requete);
			if($_POST['Mode']=="Ajout"){$Id_GroupeFormation=mysqli_insert_id($bdd);}else{$Id_GroupeFormation=$_POST['Id'];}
			
			//Insertion des formations du groupe de formation
			$requeteDeb="INSERT INTO form_formationequivalente_formationplateforme (Id_FormationEquivalente, Id_Formation,Recyclage,Id_Personne_MAJ,Date_MAJ) VALUES";
			$requeteFin="";
			foreach($_POST['FormationSelect'] as $value)
			{
				$tab = explode("_",$value);
				$requeteFin.="(";
				$requeteFin.=$Id_GroupeFormation;
				$requeteFin.=",".$tab[0];
				$requeteFin.=",".$tab[1];
				$requeteFin.=",".$IdPersonneConnectee."";
				$requeteFin.=",'".date('Y-m-d')."'";
				$requeteFin.="),";
			}
			$requeteFin=substr($requeteFin,0,strlen($requeteFin)-1);
			if($requeteFin!= ""){$result=mysqli_query($bdd,$requeteDeb.$requeteFin);}
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
//Mode ajout ou modification
$Modif=false;
if($_GET){
	$Mode=$_GET['Mode'];
	$IdFormationEquivalente=$_GET['Id'];
}
else{
	$Mode=$_POST['Mode'];
	$IdFormationEquivalente=$_POST['Id'];
}
if($Mode=="Ajout" || $Mode=="Modif")
{
	if($IdFormationEquivalente)
	{
		$Modif=True;
		$result=mysqli_query($bdd,"SELECT Id, Id_Plateforme, Libelle FROM form_formationequivalente WHERE Id=".$IdFormationEquivalente);
		$row=mysqli_fetch_array($result);
	}
?>
	<form id="formulaire" method="POST" action="Ajout_FormationEquivalente.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Mode" value="<?php echo $Mode; ?>">
	<input type="hidden" name="Id" value="<?php echo $IdFormationEquivalente;?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
	<table style="width:100%;align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td class="Libelle" width="15%"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
			<td width="85%"><input name="Libelle" size="35" type="text" value="<?php if($_GET){if($Modif){echo stripslashes($row['Libelle']);}}else{echo $_POST['Libelle'];} ?>"></td>
		</tr>
		<tr>
			<td class="Libelle" width="15%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			<td width="85%">
				<select name="Id_Plateforme" id="Id_Plateforme" onChange="submit();">
					<?php
					$Plateforme=-1;
					$resultPlateforme=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme AS Id, 
						(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
						FROM new_competences_personne_poste_plateforme 
						WHERE Id_Poste 
							IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.") 
						AND Id_Personne=".$IdPersonneConnectee." 
						ORDER BY Libelle");
					while($rowPlateforme=mysqli_fetch_array($resultPlateforme))
					{
						echo "<option value='".$rowPlateforme['Id']."'";
						if($_GET){
							if($Modif){if($rowPlateforme['Id']==$row['Id_Plateforme']){echo " selected";$Plateforme=$row['Id_Plateforme'];}}
							else{
								if($Plateforme==-1){
								$Plateforme=$rowPlateforme['Id'];
								}
							}
						}
						else{
							if($rowPlateforme['Id']==$_POST['Id_Plateforme']){echo " selected";$Plateforme=$_POST['Id_Plateforme'];}
						}
						echo ">".stripslashes($rowPlateforme['Libelle'])."</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table style="width:100%;">
					<tr>
						<td width="50%" class="Libelle" valign="top"><?php if($LangueAffichage=="FR"){echo "Formations disponibles";}else{echo "Training available";}?> : </td>
						<td width="50%" class="Libelle" valign="top"><?php if($LangueAffichage=="FR"){echo "Formations selectionnées (double-clic)";}else{echo "Selected courses (double-click)";}?> : </td>
					</tr>
					<tr>
						<td width="50%" valign="top">
							<?php
								if($Modif){
									$rqFormation="SELECT form_formationequivalente_formationplateforme.Id_Formation, form_formationequivalente_formationplateforme.Recyclage ";
									$rqFormation.="FROM form_formationequivalente_formationplateforme LEFT JOIN form_formationequivalente ";
									$rqFormation.="ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id ";
									$rqFormation.="WHERE form_formationequivalente_formationplateforme.Id_FormationEquivalente=".$IdFormationEquivalente." ";
									$rqFormation.="AND (form_formationequivalente.Id_Plateforme=0 OR form_formationequivalente.Id_Plateforme=".$Plateforme.") ";
									$resultFormGroupe=mysqli_query($bdd,$rqFormation);
									$nbFormGroupe=mysqli_num_rows($resultFormGroupe);
								}
							?>
							<select name="Id_Formation" id="Id_Formation" multiple size="20" style="width:450px;" onDblclick="Transferer_Liste('Id_Formation','FormationSelect');">
								<?php
								//FORMATIONS SMQ + PLATEFORME
								$requeteFormation="SELECT Id, Id_Plateforme, Reference, Recyclage ";
								$requeteFormation.="FROM form_formation WHERE Suppr=0 AND (Id_Plateforme=0 OR Id_Plateforme=".$Plateforme.")  ";
								$requeteFormation.="ORDER BY Reference ASC";
								$resultFormation=mysqli_query($bdd,$requeteFormation);
								$nbFormation=mysqli_num_rows($resultFormation);
								
								$requeteInfos="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage,Fichier,FichierRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Langue";
								$resultInfos=mysqli_query($bdd,$requeteInfos);
								$nbInfos=mysqli_num_rows($resultInfos);
								
								//PARAMETRE PLATEFORME
								$requeteParam="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$Plateforme." ";
								$resultParam=mysqli_query($bdd,$requeteParam);
								$nbParam=mysqli_num_rows($resultParam);
								
								$rqFormation="SELECT Id_Formation, Recyclage ";
								$rqFormation.= "FROM form_formationequivalente_formationplateforme WHERE Id_FormationEquivalente=".$IdFormationEquivalente." ";
								$rqFormation.="AND Suppr=0";
								$resultFormationExiste=mysqli_query($bdd,$rqFormation);
								$nbFormationExiste=mysqli_num_rows($resultFormationExiste);
								$i=0;
								$tab = array();
								if ($nbFormation>0){
									while($row=mysqli_fetch_array($resultFormation)){
										$Id_Langue=0;
										$Organisme="";
										if($nbParam>0){
											mysqli_data_seek($resultParam,0);
											while($rowParam=mysqli_fetch_array($resultParam)){
												if($rowParam['Id_Formation']==$row['Id']){
													$Id_Langue=$rowParam['Id_Langue'];
													if($rowParam['Organisme']<>""){
													$Organisme=" (".stripslashes($rowParam['Organisme']).")";
													}
												}
											}
										}
										$Infos="";
										$InfosRecyclage="";
										if($nbInfos>0){
											mysqli_data_seek($resultInfos,0);
											while($rowInfo=mysqli_fetch_array($resultInfos)){
												if($rowInfo['Id_Formation']==$row['Id'] && $rowInfo['Id_Langue']==$Id_Langue){
													$Infos=stripslashes($rowInfo['Libelle'])."";
													$InfosRecyclage=stripslashes($rowInfo['LibelleRecyclage'])."";
												}
											}
										}
										
										$bExiste=0;
										$bExisteR=0;
										if($nbFormationExiste>0){
											mysqli_data_seek($resultFormationExiste,0);
											while($rowFormExiste=mysqli_fetch_array($resultFormationExiste)){
												if($rowFormExiste['Id_Formation']."_".$rowFormExiste['Recyclage']==$row['Id']."_0"){
													$bExiste=1;
												}
												if($rowFormExiste['Id_Formation']."_".$rowFormExiste['Recyclage']==$row['Id']."_1"){
													$bExisteR=1;
												}
											}
										}
										if($bExiste==0 && $Infos<>""){
											$tab[$i]=array(
												"Id" => $row['Id']."_0",
												"Info" => stripslashes($Infos).$Organisme);
											$i++;
										}
										if($bExisteR==0 && $InfosRecyclage <>""){
											if($row['Recyclage']==1){
												$tab[$i]=array(
												"Id" => $row['Id']."_1",
												"Info" => stripslashes($InfosRecyclage).$Organisme);
												$i++;
											}
										}
									}
								}
								// Obtient une liste de colonnes
								foreach ($tab as $key => $ro) {
									$Id[$key]  = $ro['Id'];
									$Info[$key] = $ro['Info'];
								}
								// Trie les données par volume décroissant, edition croissant
								// Ajoute $data en tant que dernier paramètre, pour trier par la clé commune
								if(sizeof($tab)>0){
									array_multisort($Info, SORT_ASC, $tab);
								}
								foreach ($tab as $key => $ro) {
									$bTrouve=0;
									if($nbFormGroupe>0){
										while($rowFormGroup=mysqli_fetch_array($resultFormGroupe)){
											if($rowFormGroup['Id_Formation']."_".$rowFormGroup['Recyclage']==$ro['Id']){$bTrouve=1;}
										}
									}
									if($bTrouve==0){
										echo "<option value=\"".$ro['Id']."\">".$ro['Info']."</option>";
									}
								}

								?>
							</select>
						</td>
						<td width="50%" valign="top">
							<?php
							if($Modif){
								$rqFormation="SELECT Id_Formation, Recyclage ";
								$rqFormation.= "FROM form_formationequivalente_formationplateforme WHERE Id_FormationEquivalente=".$IdFormationEquivalente." ";
								$resultFormation=mysqli_query($bdd,$rqFormation);
								$i=0;
								$tab = array();
								while($rowFormation=mysqli_fetch_array($resultFormation))
								{
									$Id_Langue=0;
									$Organisme="";
									if($nbParam>0){
										mysqli_data_seek($resultParam,0);
										while($rowParam=mysqli_fetch_array($resultParam)){
											if($rowParam['Id_Formation']==$rowFormation['Id_Formation']){
												$Id_Langue=$rowParam['Id_Langue'];
												if($rowParam['Organisme']<>""){
													$Organisme=" (".stripslashes($rowParam['Organisme']).")";
												}
											}
										}
									}
									$Infos="";
									$InfosRecyclage="";
									if($nbInfos>0){
										mysqli_data_seek($resultInfos,0);
										while($rowInfo=mysqli_fetch_array($resultInfos)){
											if($rowInfo['Id_Formation']==$rowFormation['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue){
												$Infos=stripslashes($rowInfo['Libelle'])."";
												$InfosRecyclage=stripslashes($rowInfo['LibelleRecyclage'])."";
											}
										}
									}
									if($rowFormation['Recyclage']=="0"){
										$tab[$i]=array(
										"Id" => $rowFormation['Id_Formation']."_0",
										"Info" => stripslashes($Infos).$Organisme);
										$i++;
									}
									else{
										$tab[$i]=array(
										"Id" => $rowFormation['Id_Formation']."_1",
										"Info" => stripslashes($InfosRecyclage).$Organisme);
										$i++;
									}
								}
								// Obtient une liste de colonnes
								foreach ($tab as $key => $ro) {
									$Id2[$key]  = $ro['Id'];
									$Info2[$key] = $ro['Info'];
								}
								// Trie les données par volume décroissant, edition croissant
								// Ajoute $data en tant que dernier paramètre, pour trier par la clé commune
								if(sizeof($tab)>0){
									array_multisort($Info2, SORT_ASC, $tab);
								}
							}
							?>
							<select name="FormationSelect[]" id="FormationSelect" multiple size="20" style="width:450px;" onDblclick="Transferer_Liste('FormationSelect','Id_Formation');">
							<?php
							if($Modif){
								foreach ($tab as $key => $ro) {
									$bTrouve=0;
									if($nbFormGroupe>0){
										while($rowFormGroup=mysqli_fetch_array($resultFormGroupe)){
											if($rowFormGroup['Id_Formation']."_".$rowFormGroup['Recyclage']==$ro['Id']){$bTrouve=1;}
										}
									}
									if($bTrouve==0){
										echo "<option value=\"".$ro['Id']."\">".$ro['Info']."</option>";
									}
								}
							}
							?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan=2 align="center">
				<input name="validation" class="Bouton" type="submit" 
				<?php
					if($Modif)
					{
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					}
					else
					{
						if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
					}
				?>
				>
			</td>
		</tr>
	</table>
	</form>
<?php
}
else
//Mode suppression
{
	$result=mysqli_query($bdd,"UPDATE form_formationequivalente SET Suppr=1,Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
	echo "<script>FermerEtRecharger();</script>";
}
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>