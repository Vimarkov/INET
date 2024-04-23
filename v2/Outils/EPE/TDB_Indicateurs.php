<?php
require("../../Menu.php");
require("EPE_PDFs.php");
require("EPP_PDFs.php");
require("EPPBilan_PDFs.php");
?>
<script language="javascript">
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkPlateforme");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function Excel_EntretiensEnRetard(){
		var w=window.open("Excel_EntretiensEnRetard.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_EntretiensNonRealises(){
		var w=window.open("Excel_EntretiensNonRealises.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_EntretiensNonSignes(){
		var w=window.open("Excel_EntretiensNonSignes.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_EntretiensRealises(){
		var w=window.open("Excel_EntretiensRealises.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesSansDateButoir(){
		var w=window.open("Excel_PersonnesSansDateButoir.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesSansPresta(){
		var w=window.open("Excel_PersonnesSansPresta.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesSansMatricule(){
		var w=window.open("Excel_PersonnesSansMatricule.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_SalarieCadreNonCadre(){
		var w=window.open("Excel_SalarieCadreNonCadre.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_Bilan(){
		var w=window.open("Excel_Bilan.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_RPS(){
		var w=window.open("Excel_RPS.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_TE(){
		var w=window.open("Excel_TE.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_M(){
		var w=window.open("Excel_M.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_SouhaitFormation(){
		var w=window.open("Excel_SouhaitFormation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_BesoinFormation(){
		var w=window.open("Excel_BesoinFormation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_EPEEPP(){
		var w=window.open("Excel_EPEEPP.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_EvaluationFroidFormation(){
		var w=window.open("Excel_EvaluationFroidFormation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
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
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#63c021;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
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
				<?php 
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=SouhaitFormation";?>" <?php if($Indicateur=="SouhaitFormation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Analyse EPE/EPP
					</a>
				</li>
				<?php 
				}
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=Bilan";?>" <?php if($Indicateur=="Bilan"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Bilan à 6 ans non conformes
					</a>
				</li>
				<?php 
				}
				?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=EntretiensEnRetard";?>" <?php if($Indicateur=="EntretiensEnRetard"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Entretiens en retard
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=EntretiensNonRealises";?>" <?php if($Indicateur=="EntretiensNonRealises"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Entretiens non réalisés
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=EntretiensNonSignes";?>" <?php if($Indicateur=="EntretiensNonSignes"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Entretiens non signés
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=EntretiensRealises";?>" <?php if($Indicateur=="EntretiensRealises"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Entretiens réalisés
					</a>
				</li>
				<?php 
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=PersonnesSansMatricule";?>" <?php if($Indicateur=="PersonnesSansMatricule"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Personnes avec des informations manquantes
					</a>
				</li>
				<?php 
				}
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=PersonnesSansPresta";?>" <?php if($Indicateur=="PersonnesSansPresta"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Personnes sans prestation
					</a>
				</li>
				<?php 
				}
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=PersonnesSansDateButoir";?>" <?php if($Indicateur=="PersonnesSansDateButoir"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Personnes sans date butoir
					</a>
				</li>
				<?php 
				}
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=TauxRealisation";?>" <?php if($Indicateur=="TauxRealisation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Taux de réalisation
					</a>
				</li>
				<?php 
				}
				if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){ 
				?>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/TDB_Indicateurs.php?Indicateur=ExportEntretiens";?>" <?php if($Indicateur=="ExportEntretiens"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;Export des entretiens
					</a>
				</li>
				<?php 
				}
				?>
			</ul>
		</td>
		<td width="85%" valign="top">
			<input type="hidden" name="Indicateur" value="<?php echo $Indicateur;?>" />
			<?php 
			if($Indicateur=="Bilan"){
				require "Bilan.php";
			}
			if($Indicateur=="EntretiensEnRetard"){
				require "EntretiensEnRetard.php";
			}
			if($Indicateur=="EntretiensNonRealises"){
				require "EntretiensNonRealises.php";
			}
			if($Indicateur=="EntretiensNonSignes"){
				require "EntretiensNonSignes.php";
			}
			elseif($Indicateur=="EntretiensRealises"){
				require "EntretiensRealises.php";
			}
			elseif($Indicateur=="PersonnesSansDateButoir"){
				require "PersonnesSansDateButoir.php";
			}
			elseif($Indicateur=="PersonnesSansPresta"){
				require "PersonnesSansPresta.php";
			}
			elseif($Indicateur=="PersonnesSansMatricule"){
				require "PersonnesSansMatricule.php";
			}
			elseif($Indicateur=="SalarieCadreNonCadre"){
				require "SalarieCadreNonCadre.php";
			}
			elseif($Indicateur=="TauxRealisation"){
				require "TauxRealisation.php";
			}
			elseif($Indicateur=="TauxRefus"){
				require "TauxRefus.php";
			}
			elseif($Indicateur=="RPS"){
				require "RPS.php";
			}
			elseif($Indicateur=="SouhaitEvolution"){
				require "SouhaitEvolution.php";
			}
			elseif($Indicateur=="SouhaitMobilite"){
				require "SouhaitMobilite.php";
			}
			elseif($Indicateur=="SouhaitFormation"){
				require "SouhaitFormation.php";
			}
			elseif($Indicateur=="ExportEntretiens"){
				require "ExportEntretiens.php";
			}

			?>
		</td>
	</tr>
	<tr><td height="4"></td>
	</table>
</form>
</html>
	