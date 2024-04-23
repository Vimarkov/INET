<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
	function Fermer(){
			window.close();
		}
	function ajouter(){
		for(y=0;y<document.getElementById('Id_Personne').length;y++){
			if(document.getElementById('Id_Personne').options[y].selected == true){
				nouvel_element = new Option(document.getElementById('Id_Personne').options[y].text,document.getElementById('Id_Personne').options[y].value,false,false);
				document.getElementById('PersonneSelect').options[document.getElementById('PersonneSelect').length] = nouvel_element;
				document.getElementById('Id_Personne').options[y] = null;
			}
		}
		
		Liste= new Array();
		Obj= document.getElementById('PersonneSelect')
		 
		for(i=0;i<Obj.options.length;i++){
			Liste[i]=new Array()
			Liste[i][0]=Obj.options[i].text
			Liste[i][1]=Obj.options[i].value
		}
		Liste=Liste.sort()
		 
		for(i=0;i<Obj.options.length;i++){
			Obj.options[i].text=Liste[i][0]
			Obj.options[i].value=Liste[i][1]
		}

	}
		
	function effacer(){
		for(y=0;y<document.getElementById('PersonneSelect').length;y++){
			if(document.getElementById('PersonneSelect').options[y].selected == true){
				nouvel_element = new Option(document.getElementById('PersonneSelect').options[y].text,document.getElementById('PersonneSelect').options[y].value,false,false);
				document.getElementById('Id_Personne').options[document.getElementById('Id_Personne').length] = nouvel_element;
				document.getElementById('PersonneSelect').options[y] = null;
			}
		}
		
		Liste= new Array();
		Obj= document.getElementById('Id_Personne')
		 
		for(i=0;i<Obj.options.length;i++){
			Liste[i]=new Array()
			Liste[i][0]=Obj.options[i].text
			Liste[i][1]=Obj.options[i].value
		}
		Liste=Liste.sort()
		 
		for(i=0;i<Obj.options.length;i++){
			Obj.options[i].text=Liste[i][0]
			Obj.options[i].value=Liste[i][1]
		}
	}
	function selectall()
	{
		for(y=0;y<document.getElementById('PersonneSelect').length;y++){document.getElementById('PersonneSelect').options[y].selected = true;}
	}
	</script>
</head>
<?php
	require("../Connexioni.php");
	
	if(isset($_POST['submitValider'])){
		//Suppression des anciennes personnes
		$Pole = "0";
		$req = "DELETE FROM new_sqcdpf_prestation_equipemail WHERE new_sqcdpf_prestation_equipemail.Id_Prestation = ".$_POST['prestations']." ";
		if (!empty($_POST['pole'])){
			$req .= "AND new_sqcdpf_prestation_equipemail.Id_Pole = ".$_POST['pole']." ";
			$Pole = $_POST['pole'];
		}
		$resultSupp=mysqli_query($bdd,$req);
		
		$Personne="";
		if(isset($_POST['PersonneSelect']))
		{
			$PersonneSelect = $_POST['PersonneSelect'];
			for($i=0;$i<sizeof($PersonneSelect);$i++)
			{
				if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
			}
		}
		
		$TabPersonne = preg_split("/[;]+/", $Personne);
		for($i=0;$i<sizeof($TabPersonne)-1;$i++)
		{
			$requete="INSERT INTO new_sqcdpf_prestation_equipemail ";
			$requete.="(Id_Prestation,Id_Pole,Id_Personne) VALUES ";
			$requete.="(".$_POST['prestations'].",".$Pole.",".$TabPersonne[$i].")";
			$result=mysqli_query($bdd,$requete);
		}
		
		echo "<script>Fermer();</script>";
	}
	
	if ($_GET){
		$IdPersonne = $_GET['Id_Personne'];
	}
	if ($_POST){
		$IdPersonne = $_POST['Personne'];
	}
	$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>
<form class="test" method="POST" action="DestinatairePERFOS.php" onsubmit=" return selectall();">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">SQCDPF # Destinataire des mails</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center">
				<tr style="display:none;">
					<td><input type="text" name="Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
				</tr>
				<tr>
					<td width=30%>
						&nbsp; Prestation :
						<select class="prestation" name="prestations" onchange="submit();">
						<?php
						$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT new_competences_prestation.Libelle FROM new_competences_prestation ";
						$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
						$req .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." and new_competences_personne_poste_prestation.Id_Poste <3 ORDER BY NomPrestation;";
						
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = 0;
						$Selected = "";
						if ($nbPrestation > 0)
						{
							if (!empty($_GET['IdPrestationSelect'])){
								if ($PrestationSelect == 0){$PrestationSelect = $_GET['IdPrestationSelect'];}
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($row[0] == $_GET['IdPrestationSelect']){
										$Selected = "Selected";
									}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							elseif (!empty($_POST['prestations'])){
								if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($row[0] == $_POST['prestations']){
										$Selected = "Selected";
									}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							else{
								$PrestationSelect == 0;
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($PrestationSelect == 0){$PrestationSelect = $row[0];}
									echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
								}
							}
						 }
						 ?>
						</select>
					</td>
					<td width=15%>
						&nbsp; Pôle :
						<select class="pole" name="pole" onchange="submit();">
						<?php

						$reqPole = "SELECT distinct new_competences_personne_poste_prestation.Id_Pole, ";
						$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
						$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
						$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." AND new_competences_personne_poste_prestation.Id_Poste <3 ";
						$reqPole .= "AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." AND new_competences_personne_poste_prestation.Id_Pole > 0 ORDER BY LibellePole;";
						
						$resultPole=mysqli_query($bdd,$reqPole);
						$nbPole=mysqli_num_rows($resultPole);
						
						$PoleSelect = 0;
						$Selected = "";
						
						if ($nbPole > 0)
						{
							if (!empty($_GET['Id_Pole'])){
								if ($PoleSelect == 0){$PoleSelect = $_GET['Id_Pole'];}
								while($row=mysqli_fetch_array($resultPole))
								{
									if ($row[0] == $_GET['Id_Pole']){$Selected = "Selected";}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							elseif (!empty($_POST['pole'])){
								if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
								while($row=mysqli_fetch_array($resultPole))
								{
									if ($row[0] == $_POST['pole']){$Selected = "Selected";}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							else{
								while($row=mysqli_fetch_array($resultPole))
								{
									if ($PoleSelect == 0){$PoleSelect = $row[0];}
									echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
								}
							}
						 }
						 ?>
						</select>
					</td>
				</tr>
				<tr height="2" ></tr>
			</table>
			<table  width="100%" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td>Personnes :</td>
					<td>
						<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
						<?php
						$rq="SELECT DISTINCT  new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil";
						$rq.=" LEFT JOIN new_competences_personne_prestation ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne ";
						$rq.=" WHERE new_competences_personne_prestation.Id_Prestation=".$PrestationSelect."";
						$rq.=" AND new_competences_personne_prestation.Id_Pole=".$PoleSelect."";
						$rq.=" AND new_competences_personne_prestation.Date_Debut<='".$DateJour."'";
						$rq.=" AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
						$rq.=" 
							UNION 
							SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom 
							FROM new_rh_etatcivil
							LEFT JOIN new_competences_personne_poste_prestation
							ON new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne
							WHERE new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect."
							AND new_competences_personne_poste_prestation.Id_Pole=".$PoleSelect."
							AND Id_Poste IN (2,3,4) 
						";
						$rq.="GROUP BY Id ORDER BY Nom ASC, Prenom ASC";
						
						$resultpersonne=mysqli_query($bdd,$rq);
						
						$rqListe="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil";
						$rqListe.=" LEFT JOIN new_sqcdpf_prestation_equipemail ON new_rh_etatcivil.Id=new_sqcdpf_prestation_equipemail.Id_Personne ";
						$rqListe.=" WHERE new_sqcdpf_prestation_equipemail.Id_Prestation=".$PrestationSelect."";
						if (!empty($_POST['pole'])){$rqListe.=" AND new_sqcdpf_prestation_equipemail.Id_Pole=".$_POST['pole']."";}
						$rqListe.=" ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
						$resultpersonneListe=mysqli_query($bdd,$rqListe);
							
						while($rowpersonne=mysqli_fetch_array($resultpersonne)){
							$bExiste = false;
							mysqli_data_seek($resultpersonneListe,0);
							while($rowpersonneListe=mysqli_fetch_array($resultpersonneListe)){
								if ($rowpersonne['Id'] == $rowpersonneListe['Id']){
									$bExiste = true;
								}
							}
							if ($bExiste == false){
								echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Nom']." ".$rowpersonne['Prenom'])."</option>\n";
							}
						}
						?>
						</select>
					</td>
					<td>Personnes sélectionnées (double-clic) : </td>
					<td>
						<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();">
							<?php
							$rq="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil";
							$rq.=" LEFT JOIN new_sqcdpf_prestation_equipemail ON new_rh_etatcivil.Id=new_sqcdpf_prestation_equipemail.Id_Personne ";
							$rq.=" WHERE new_sqcdpf_prestation_equipemail.Id_Prestation=".$PrestationSelect."";
							$rq.=" AND new_sqcdpf_prestation_equipemail.Id_Pole=".$PoleSelect."";
							$rq.=" ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
							$resultpersonne=mysqli_query($bdd,$rq);
							while($rowpersonne=mysqli_fetch_array($resultpersonne))
							{
								echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Nom']." ".$rowpersonne['Prenom'])."</option>\n";
							}
							?>
						</select>
					</td>
				</tr>
			</table>
		</td></tr>
		<tr height="2" bgcolor="#2b459c"><td colspan="7"></td></tr>
		<tr height="2" ></tr>
		<tr align="center">
			<td  colspan="7" align="center" text-align="center">
				<input class="Bouton" name="submitValider" type="submit" value='Valider'>
			</td>
		</tr>
	</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>