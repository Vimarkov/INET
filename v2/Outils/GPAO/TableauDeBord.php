<?php
require("../../Menu.php");
?>
<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
<script>
		$(function(){
			$('#gpao').change(function (){
				$.ajax({
					url : 'ajax_Prestation.php',
					type : 'GET',
					data : 'Id_Prestation='+document.getElementById('gpao').value,
					async: false,
				});
				top.location="TableauDeBord.php?Menu=1";
			});
		});
	</script>

<?php

if($_GET){$_SESSION['Menu']=$_GET['Menu'];}
else{$_SESSION['Menu']=$_POST['Menu'];}

if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
	$MoisLettre3 = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
	$MoisLettre3 = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
}

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}
function Titre($Libelle,$Lien){
	echo "<tr>
			<td style='font-size:14px;' colspan='8' >&nbsp;&bull;&nbsp;
				<a style=\"color:black;text-decoration: none;font-weight:bold;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
					".$Libelle."
				</a>
			</td>
		</tr>\n
		<tr>
			<td height=\"5px\">
			
			</td>
		</tr>
		";
}

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:3px solid #ffffff;font-style:italic;font-size:14px;";$couleurTexte="#ffffff";}
	echo "<td style=\"width:10%;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="TableauDeBord.php" method="post">
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td style="display:none;">
		<input name="Langue" id="Langue" value="<?php echo $LangueAffichage;?>">
		<input name="http" id="http" value="<?php echo $_SESSION['HTTP'];?>">
		<input name="servername" id="servername" value="<?php echo $_SERVER['SERVER_NAME'];?>">
		</td>
	</tr>
	<tr>
		<td colspan="13" height="20px" valign="center" align="right" style="font-weight:bold;font-size:15px;">

		</td>
	</tr>
	<tr bgcolor="#6EB4CD">
		<td style="width:20%;font-size:20px;height:20px;border-spacing:0;text-align:left;color:#00567c;valign:top;font-weight:bold;background:#ffffff;border:#6EB4CD 5px dotted;">
			<?php 
				if($_SESSION["Langue"]=="FR"){echo "GPAO";}
				else{echo "GPAO";}
			?>
			<select style="font-size:25px;"  id="gpao" name="gpao">
				<?php
					//A FAIRE : Ajouter droits d'accès aux différentes GPAO
					$req="SELECT Id, Libelle FROM gpao_prestation WHERE Suppr=0 ORDER BY Libelle ";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							if($_SESSION['Id_GPAO']==0){
								$_SESSION['Id_GPAO']=$row['Id'];
							}
							if($row['Id']==$_SESSION['Id_GPAO']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected." >".$row['Libelle']."</option>";
						}
					}
				?>
			</select>
		</td>
	<?php
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==1){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("DISPLAY WO","Outils/GPAO/TableauDeBord.php?Menu=1",$select);}
		else{Titre1("DISPLAY WO","Outils/GPAO/TableauDeBord.php?Menu=1",$select);}

		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==2){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("CREATE WO","Outils/GPAO/TableauDeBord.php?Menu=2",$select);}
		else{Titre1("CREATE WO","Outils/GPAO/TableauDeBord.php?Menu=2",$select);}
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==7){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("SQCDP","Outils/GPAO/TableauDeBord.php?Menu=7",$select);}
		else{Titre1("SQCDP","Outils/GPAO/TableauDeBord.php?Menu=7",$select);}
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==6){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("QUALITY","Outils/GPAO/TableauDeBord.php?Menu=6",$select);}
		else{Titre1("QUALITY","Outils/GPAO/TableauDeBord.php?Menu=6",$select);}
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==4){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("REQUESTS","Outils/GPAO/TableauDeBord.php?Menu=4",$select);}
		else{Titre1("REQUESTS","Outils/GPAO/TableauDeBord.php?Menu=4",$select);}
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==5){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("INVOICING","Outils/GPAO/TableauDeBord.php?Menu=5",$select);}
		else{Titre1("INVOICING","Outils/GPAO/TableauDeBord.php?Menu=5",$select);}
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==8){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("PRODUCTION TIME","Outils/GPAO/TableauDeBord.php?Menu=8",$select);}
		else{Titre1("PRODUCTION TIME","Outils/GPAO/TableauDeBord.php?Menu=8",$select);}
		
		$select=false;
		if(isset($_SESSION['Menu'])){
			if($_SESSION['Menu']==3){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("ADMIN","Outils/GPAO/TableauDeBord.php?Menu=3",$select);}
		else{Titre1("ADMIN","Outils/GPAO/TableauDeBord.php?Menu=3",$select);}
		
	?>
	</tr>
</table>

<?php	
	if($_SESSION['Menu']==1){
		require "Liste_WO.php";
	}
	elseif($_SESSION['Menu']==2){
		require "Ajout_WO.php";
	}
	elseif($_SESSION['Menu']==3){
		require "ListeDeroulante.php";
	}
	elseif($_SESSION['Menu']==4){
		require "ListeRequests.php";
	}
	elseif($_SESSION['Menu']==5){
		require "Invoicing.php";
	}
	elseif($_SESSION['Menu']==6){
		require "ListeQuality.php";
	}
	elseif($_SESSION['Menu']==7){
		require "SQCDP.php";
	}
	elseif($_SESSION['Menu']==8){
		require "ProductionTime.php";
	}

?>

</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>