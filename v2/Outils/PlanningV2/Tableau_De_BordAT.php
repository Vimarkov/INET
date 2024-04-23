<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetreExcel(Page){
		window.open("Export_"+Page+".php","PageExcel","status=no,menubar=no,width=50,height=50");
	}
</script>
<?php

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

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

if($_GET){
	if(isset($_GET['Menu'])){
		$Menu=$_GET['Menu'];
	}
	else{
		$Menu=1;
	}
}
else{
	if(isset($_POST['Menu'])){
		$Menu=$_POST['Menu'];
	}
	else{
		$Menu=1;
	}
}

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=$leNombre;}
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
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle;
		
	echo "</a></td>\n";
}

?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="Tableau_De_BordAT.php" method="post">
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td style="display:none;"><input name="Langue" id="Langue" value="<?php echo $LangueAffichage;?>"></td>
	</tr>
	<tr>
		<td valign="center" colspan="6" align="right" style="font-weight:bold;font-size:15px;">
			<table style="align:center;">
				<tr>
					<td style="height:20px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;border-radius: 15px;">&nbsp;&nbsp;
						<?php
							if($_SESSION['Id_Personne']>0){
								echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								if($LangueAffichage=="FR"){echo "Guide utilisateur : ";}else{echo "User Guide : ";}
								echo "<a target='_blank' href='Guide_Utilisateur_AT.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#6EB4CD">
		<td style="width:20%;font-size:20px;height:20px;border-spacing:0;text-align:center;color:#00567c;valign:top;font-weight:bold;background:#ffffff;border:#6EB4CD 5px dotted;">
			<?php 
				if($_SESSION["Langue"]=="FR"){echo "ACCIDENTS DE TRAVAIL";}
				else{echo "WORK ACCIDENTS";}
			?>
		</td>
	<?php
		
		$select=false;
		if(isset($Menu)){
			if($Menu==1){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("LISTE DES AT","Outils/PlanningV2/Tableau_De_BordAT.php?Menu=1",$select);}
		else{Titre1("","Outils/PlanningV2/Tableau_De_BordAT.php?Menu=1",$select);}

		$select=false;
		if(isset($Menu)){
			if($Menu==2){$select=true;}
		}
		if(
			DroitsFormation1Plateforme("1,3,4,5,9,10,13,17,19,23,24,27,28,29,32",array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteResponsablePlateforme,$IdPosteOperateurSaisieRH,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))
			|| DroitsFormationPrestations(array(1,3,4,5,9,10,13,17,19,23,24,27,28,29,32),$TableauIdPostesResponsablesPrestation)
		)
		{
			if($_SESSION["Langue"]=="FR"){Titre1("DECLARER UN AT","Outils/PlanningV2/Tableau_De_BordAT.php?Menu=2",$select);}
			else{Titre1("DECLARE AN ACCIDENT AT WORK","Outils/PlanningV2/Tableau_De_BordAT.php?Menu=2",$select);}
		}
		
		$select=false;
		if(isset($Menu)){
			if($Menu==3){$select=true;}
		}
		if(
			DroitsFormation1Plateforme("17",array($IdPosteResponsableHSE))
		)
		{
			if($_SESSION["Langue"]=="FR"){Titre1("DESTINATAIRES EXTERNES","Outils/PlanningV2/Tableau_De_BordAT.php?Menu=3",$select);}
			else{Titre1("EXTERNAL RECIPIENTS","Outils/PlanningV2/Tableau_De_BordAT.php?Menu=3",$select);}
		}
	?>
	</tr>
	<tr>
		<td colspan="14" align="center" style="width:100%">
		<?php	
			if($Menu==1){
				require "Liste_AT2.php";
			}
			elseif($Menu==2){
				require "Ajout_AT2.php";
			}
			elseif($Menu==3){
				require "Liste_DestinataireExterne.php";
			}
		?>
		</td>
	</tr>
</table>
</form>
</body>
</html>