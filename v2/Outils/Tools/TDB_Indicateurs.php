<?php
require("../../Menu.php");
?>
<script language="javascript">
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkPresta");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function SelectionnerToutPlateforme()
	{
		var elements = document.getElementsByClassName("checkPlateforme");
		if (formulaire.selectAllPlateforme.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function SelectionnerToutType()
	{
		var elements = document.getElementsByClassName("checkType");
		if (formulaire.selectAllType.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
</script>
<?php

if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=$leNombre;}
	return $nb;
}
?>
<form id="formulaire" action="TDB_Indicateurs.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#e779a4;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Indicateurs";}else{echo "Indicators";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td>
	<tr>
		<?php
			$Indicateur="";
			if(isset($_GET['Indicateur'])){$Indicateur=$_GET['Indicateur'];}
			if($_POST){
				if(isset($_POST['Indicateur'])){$Indicateur=$_POST['Indicateur'];}
			}
		?>
		<td width="15%" valign="top">
			<ul class="sidenav">
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/TDB_Indicateurs.php?Indicateur=EtatGlobalMateriel";?>" <?php if($Indicateur=="EtatGlobalMateriel"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat global du matériel";}else{echo "Overall condition of the equipment";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/TDB_Indicateurs.php?Indicateur=Pertes";?>" <?php if($Indicateur=="Pertes"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre de pertes";}else{echo "Number of losses";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/TDB_Indicateurs.php?Indicateur=CoutPertes";?>" <?php if($Indicateur=="CoutPertes"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Coût des pertes";}else{echo "Cost of losses";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/TDB_Indicateurs.php?Indicateur=TypePertes";?>" <?php if($Indicateur=="TypePertes"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Type des pertes";}else{echo "Type of losses";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/TDB_Indicateurs.php?Indicateur=Investissement";?>" <?php if($Indicateur=="Investissement"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Investissements";}else{echo "Investments";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Tools/TDB_Indicateurs.php?Indicateur=Immobilisation";?>" <?php if($Indicateur=="Immobilisation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Immobilisations";}else{echo "Fixed assets";}?>
					</a>
				</li>
			</ul>
		</td>
		<td width="85%" valign="top">
			<input type="hidden" name="Indicateur" value="<?php echo $Indicateur;?>" />
			<?php 
			if($Indicateur=="EtatGlobalMateriel"){
				require "EtatGlobalMateriel.php";
			}
			elseif($Indicateur=="Pertes"){
				require "Pertes.php";
			}
			elseif($Indicateur=="CoutPertes"){
				require "CoutPertes.php";
			}
			elseif($Indicateur=="TypePertes"){
				require "TypePertes.php";
			}
			elseif($Indicateur=="Investissement"){
				require "Investissement.php";
			}
			elseif($Indicateur=="Immobilisation"){
				require "Immobilisation.php";
			}
			
			?>
		</td>
	</tr>
	<tr><td height="4"></td>
	</table>
</form>
</html>
	