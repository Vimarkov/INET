<!DOCTYPE html>
<html>
<head>
	<link href="CSS/Bandeau.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Outils/JS/jquery-3.1.0.min.js"></script>
	<script>
		function OuvreFenetreUtilisateur(Id){
			window.open("Outils/Acces/Utilisateur_Change_Profil.php?Id="+Id,"ChangeProfil","status=no,menubar=no,width=650,height=190");
		}
		function OuvreDoc(){window.open("pdf.php?Doc=eTraME","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
	</script>
	<script>
		$(function(){
			$('#langueFR').click(function (){
				$.ajax({
					url : 'ajax_Langue.php',
					type : 'GET',
					data : 'Langue=FR',
					async: false,
				});
				top.location="Accueil.php";
			});
			$('#langueEN').click(function (){
				$.ajax({
					url : 'ajax_Langue.php',
					type : 'GET',
					data : 'Langue=EN',
					async: false,
				});
				top.location="Accueil.php";
			});
			
			$('#prestation').change(function (){
				$.ajax({
					url : 'ajax_Prestation.php',
					type : 'GET',
					data : 'Id_Prestation='+document.getElementById('prestation').value,
					async: false,
				});
				top.location="Accueil.php";
			});
		});
	</script>
	<script type="text/javascript">
	<!--
	//
	var position=0;
	var msg="            ";
	var msg="         /!\\ Le site extranet se sécurise (passage en https) . Veuillez utiliser dès à présent l'adresse suivante https://extranet.aaa-aero.com/TraME . A partir du 18/06 l'adresse en http ne sera plus accessible /!\\       /!\\ The extranet site is secure (switch to https). Please use the following address https://extranet.aaa-aero.com/TraME right now. From 18/06 the address in http will no longer be accessible /!\\            "+msg;
	var longue=msg.length;
	var fois=(270/msg.length)+1;
	for(i=0;i<=fois;i++) msg+=msg;
	function textdefil() {
	document.form1.deftext.value=msg.substring(position,position+270);
	position++;
	if(position == longue) position=0;
	setTimeout("textdefil()",150);
	}
	window.onload = textdefil;
	//-->
	</script>
</head>
<?php
	session_start();
	require("Outils/Connexioni.php");
	require("Outils/Fonctions.php");
	
?>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<td width="120">
			<table>
				<tr>
					<td>
						<img style="border: none;" src="Images/Logo DaherMonogramme_Neg.png" marginheight="0" marginwidth="0" width="120" alt="" title="">
					</td>
				</tr>
			</table>
		</td>
		<td width="100" align="center">
			<?php
				//Affichage des drapeaux en fonction de la langue choisie
				if($_SESSION["Langue"]=="FR"){
					$ImgFR="FR2.jpg";
					$ImgEN="EN.jpg";
				}else{
					$ImgFR="FR.jpg";
					$ImgEN="EN2.png";
				}
				echo "<input id='langueFR' type='image' src='Images/".$ImgFR."'>&nbsp;&nbsp;";
				echo "<input id='langueEN' type='image' src='Images/".$ImgEN."'><br><br>";
			?>
		</td>
		<td width="100" align="center" valign="center">
			<input type='submit' class='Bouton' value='<?php if($_SESSION["Langue"]=="FR"){echo "AIDE";}else{echo "HELP";} ?>' onclick='javascript:OuvreDoc();'>
		</td>
		<td class="Titre" >TraME 
			<select style="font-size:30px;background-color: #00325F;color:white;"  id="prestation" name="prestation">
				<?php
					if(substr($_SESSION['DroitTR'],5,1)=="1"){
						$req="SELECT Id AS Id_Prestation, Libelle FROM trame_prestation ORDER BY Libelle ";
					}
					else{
						$req="SELECT Id_Prestation, Libelle FROM trame_acces LEFT JOIN trame_prestation ON trame_acces.Id_Prestation=trame_prestation.Id WHERE trame_prestation.Libelle<>'' AND trame_acces.Id_Prestation<>0 AND trame_acces.Id_Personne=".$_SESSION['Id_PersonneTR']." ORDER BY Libelle";
					}
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							if($row['Id_Prestation']==$_SESSION['Id_PrestationTR']){$selected="selected";}
							echo "<option value='".$row['Id_Prestation']."' ".$selected." >".$row['Libelle']."</option>";
						}
					}
				?>
				<?php echo $_SESSION['Id_PrestationTR'];?>
			</select>
		</td>
		<td width="220">
			<table width="100%">
				<tr>
					<td class="Identification">
						<?php 
							if($_SESSION['Langue']=="EN"){echo "Welcome ";}
							else{echo "Bonjour ";}
							echo $_SESSION['PrenomTR']." ".$_SESSION['NomTR']; 
						?>
						</td>
				</tr>
				<tr>
					<td>
						<input type="submit" class="Bouton" value="<?php if($_SESSION['Langue']=="EN"){echo "Edit profile";}else{ echo "Modifier son profil";}?>" onclick="javascript:OuvreFenetreUtilisateur(<?php echo $_SESSION['Id_PersonneTR']."";?>);">
					</td>
				</tr>
				<tr>
					<td>
					<a style="text-decoration:none;font:12px Calibri;" target="_top" class="Bouton" href="index.php?L=<?php echo $_SESSION['Langue'];?>">&nbsp;
						<?php 
							if($_SESSION['Langue']=="EN"){echo "Sign out";}
							else{echo "Déconnexion";}
						?>
					&nbsp;</a>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
	<!--<tr>
		<td colspan="6" align="center">
			<form name="form1">
			<div align="center">
			<input style="color:red;font-weight: bold;" type="text" name="deftext" size=180>
			</div>
			</form>
		</td>
	</tr>-->
</table>
</body>
</html>