<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script>
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/webforms2-0/webforms2-p.js"></script>	
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../JS/colorpicker.js"></script>
</head>
 <script>
	var initColorpicker = function() {  
	$('input[type=color]').each(function() {  
		var $input = $(this);  
		$input.ColorPicker({  
			onSubmit: function(hsb, hex, rgb, el) {  
				$(el).val(hex);  
				$(el).ColorPickerHide();  
			}  
		});  
	});  
	};  

	if(!Modernizr.inputtypes.color){$(document).ready(initColorpicker);};
</script>
<?php
session_start();
require("../Connexioni.php");

if($_POST)
{
	$requeteSupp="DELETE FROM new_competences_personne_poste_plateforme WHERE Id_Poste=".$_POST['Id_Poste']." AND Id_Plateforme=".$_POST['Id_Plateforme'];
	$resultSupp=mysqli_query($bdd,$requeteSupp);
	
	//Couleur uniquement pour Formateur
	if($_POST['resp']<>0){
		$Couleur="";
		if($_POST['Id_Poste']==21){
			$Couleur=$_POST['color-picker'];
		}
		$requeteInsert="INSERT INTO new_competences_personne_poste_plateforme (Id_Poste, Id_Personne, Id_Plateforme, Backup,Couleur) ";
		$requeteInsert.="VALUES(".$_POST['Id_Poste'].",".$_POST['resp'].",".$_POST['Id_Plateforme'].",0,'".$Couleur."') ";
		$requeteInsert=mysqli_query($bdd,$requeteInsert);
	}
	$imax=7;
	if($_POST['Id_Poste']==21){
		$imax=24;
	}
	elseif($_POST['Id_Poste']==18){
		$imax=12;
	}
	elseif($_POST['Id_Poste']==44){
		$imax=13;
	}
	elseif($_POST['Id_Poste']>=49 && $_POST['Id_Poste']<=52){
		$imax=20;
	}
	elseif($_POST['Id_Poste']==62 || $_POST['Id_Poste']==63){
		$imax=25;
	}
	for($i=1;$i<=$imax;$i++){
		if($_POST['backup'.$i]<>0){
			$Couleur="";
			if($_POST['Id_Poste']==21){
				$Couleur=$_POST['color-picker'.$i];
			}
			$requeteInsert="INSERT INTO new_competences_personne_poste_plateforme (Id_Poste, Id_Personne, Id_Plateforme, Backup,Couleur) ";
			$requeteInsert.="VALUES(".$_POST['Id_Poste'].",".$_POST['backup'.$i].",".$_POST['Id_Plateforme'].",".$i.",'".$Couleur."') ";
			$requeteInsert=mysqli_query($bdd,$requeteInsert);
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	$requetePlat = "SELECT Libelle FROM new_competences_plateforme WHERE Id=".$_GET['Id_Plateforme'];
	$resultPlat=mysqli_query($bdd,$requetePlat);
	$rowPlat=mysqli_fetch_array($resultPlat);

	$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
	$requetePersonne.="FROM new_rh_etatcivil ";
	$requetePersonne.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	$resultPersonne=mysqli_query($bdd,$requetePersonne);
	
	$resultPlatPoste=mysqli_query($bdd,"SELECT Id_Personne,Id_Poste,Backup, Couleur FROM new_competences_personne_poste_plateforme WHERE Id_Plateforme=".$_GET['Id_Plateforme']);
	$NbLignePlatPoste=mysqli_num_rows($resultPlatPoste);
	$resp=0;
	$couleur="#000000";
	$tab = array();
	$tabCouleur = array();
	for($i=1;$i<=24;$i++){
		$tab[$i]=0;
		$tabCouleur[$i]="#000000";
	}
	if($NbLignePlatPoste){
		while($row=mysqli_fetch_array($resultPlatPoste)){
			if($row['Id_Poste']==$_GET['Id_Poste']){
				if($row['Backup']==0){
					$resp=$row['Id_Personne'];
					$couleur=$row['Couleur'];
				}
				else{
					$tab[$row['Backup']]=$row['Id_Personne'];
					$tabCouleur[$row['Backup']]=$row['Couleur'];
				}
			}
		}
	}
	$resultPoste=mysqli_query($bdd,"SELECT Libelle FROM new_competences_poste WHERE Id=".$_GET['Id_Poste']."");
	$NbLignePoste=mysqli_num_rows($resultPoste);
	$rowPoste=mysqli_fetch_array($resultPoste);
	
?>
	<form id="formulaire" method="POST" action="Ajout_Plateforme_Poste.php">
		<input type="hidden" name="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
		<input type="hidden" name="Id_Poste" value="<?php echo $_GET['Id_Poste'];?>">
		<table class="TableCompetences" style="width:95%; height:95%; align:center;">
			<tr>
				<td class="PetitCompetence">Poste : </td>
				<td><?php echo $rowPoste['Libelle']; ?></td>
				<td class="PetitCompetence">Plateforme : </td>
				<td><?php echo $rowPlat['Libelle']; ?></td>
			</tr>
			<tr>
				<td class="PetitCompetence">Responsable : </td>
				<td>
					<select id="resp" name="resp">
						<option value='0'></option>
						<?php
							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								$selected="";
								if($rowPersonne['Id']==$resp){$selected="selected";}
								echo "<option value='".$rowPersonne['Id']."' ".$selected." >".$rowPersonne['Nom']." ".$rowPersonne['Prenom']."</option>\n";
							}
							mysqli_data_seek($resultPersonne,0);
						?>
					</select>
				</td>
				<?php
					if($_GET['Id_Poste']==21){
						echo '<td>';
						echo '<input type="color" name="color-picker" id="color-picker" value="'.$couleur.'">';						
						echo '</td>';
					}
				?>
			</tr>
			<?php
				$imax=7;
				if($_GET['Id_Poste']==21){
					$imax=24;
				}
				elseif($_GET['Id_Poste']==18){
					$imax=12;
				}
				elseif($_GET['Id_Poste']==44){
					$imax=13;
				}
				elseif($_GET['Id_Poste']>=49 && $_GET['Id_Poste']<=52){
					$imax=20;
				}
				elseif($_GET['Id_Poste']==62 || $_GET['Id_Poste']==63){
					$imax=25;
				}
				for($i=1;$i<=$imax;$i++){
					echo '<tr><td height="1"><br/></td></tr>';
					echo'<tr>';
					echo '<td class="PetitCompetence">Backup : </td>';
					echo '<td>';
					echo '<select id="backup'.$i.'" name="backup'.$i.'">';
					echo '<option value="0"></option>';
					while($rowPersonne=mysqli_fetch_array($resultPersonne))
					{
						$selected="";
						if($rowPersonne['Id']==$tab[$i]){
							$selected="selected";
						}
						echo "<option value='".$rowPersonne['Id']."' ".$selected." >".$rowPersonne['Nom']." ".$rowPersonne['Prenom']."</option>\n";
					}
					mysqli_data_seek($resultPersonne,0);
					echo '</select>';
					echo '</td>';
					if($_GET['Id_Poste']==21){
						echo '<td>';
						echo '<input type="color" name="color-picker'.$i.'" id="color-picker'.$i.'" value="';
						echo $tabCouleur[$i];
						echo '">';
						echo '</td>';
					}
					echo '</tr>';
				}
			?>
			<tr><td height="20"><br/></td></tr>
			<tr>
				<td colspan="9" align="center"><input class="Bouton" type="submit" value="Valider"></td>
			</tr>
		</table>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>