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
	function SelectionnerToutRespProjet()
	{
		var elements = document.getElementsByClassName("checkRespProjet");
		if (formulaire.selectAllRespProjet.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function SelectionnerToutFormateur()
	{
		var elements = document.getElementsByClassName("checkFormateur");
		if (formulaire.selectAllFormateur.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function SelectionnerToutCategorie()
	{
		var elements = document.getElementsByClassName("checkCategorie");
		if (formulaire.selectAllCategorie.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function ExcelNbBesoin(){
		var w=window.open("Excel_NbBesoinsFormation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesSansFormation(){
		var w=window.open("Excel_PersonnesSansFormation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
	function Excel_NbSessionParFormation(){
		var w=window.open("Excel_NbSessionParFormation.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_SessionAnnulees(){
		var w=window.open("Excel_SessionAnnulees.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_ListeDesinscription(){
		var w=window.open("Excel_ListeDesinscription.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_BesoinsSansSession(){
		var w=window.open("Excel_BesoinsSansSession.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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
<form id="formulaire" action="TDB_IndicateursSessions.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6fb543;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Indicateurs - Sessions de formation";}else{echo "Indicators - Training sessions";}
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
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=FormationsAnnulees";?>" <?php if($Indicateur=="FormationsAnnulees"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Formations annulées";}else{echo "Canceled training";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=BesoinsSansSession";?>" <?php if($Indicateur=="BesoinsSansSession"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Liste des besoins en formation sans proposition de sessions";}else{echo "List of training needs without sessions proposal";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=ListeDesinscription";?>" <?php if($Indicateur=="ListeDesinscription"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Liste des désinscriptions";}else{echo "List of unsubscriptions";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=NbHeuresFormation";?>" <?php if($Indicateur=="NbHeuresFormation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre d'heures de formation";}else{echo "Number of hours of training";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=NbPersonnesInscrites";?>" <?php if($Indicateur=="NbPersonnesInscrites"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre de personnes inscrites";}else{echo "Number of people registered";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=NbPersonnesPresentesAbs";?>" <?php if($Indicateur=="NbPersonnesPresentesAbs"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre de personnes présentes / absentes";}else{echo "Number of people present / absent";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=NbSessionFormation";?>" <?php if($Indicateur=="NbSessionFormation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre de sessions de formation";}else{echo "Number of training sessions";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=NbSessionParFormation";?>" <?php if($Indicateur=="NbSessionParFormation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre de sessions par formation";}else{echo "Number of sessions per training";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=NbEtCoutParFormation";?>" <?php if($Indicateur=="NbEtCoutParFormation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombres et coûts par formation";}else{echo "Numbers and costs per training";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=EvaluationAChaud";?>" <?php if($Indicateur=="EvaluationAChaud"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "OQD : Traitement des évaluations à chaud";}else{echo "OQD: Hot Appraisal Processing";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=TauxRemplissageSessions";?>" <?php if($Indicateur=="TauxRemplissageSessions"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Taux de remplissage des sessions";}else{echo "Session fill rate";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=TauxReussite";?>" <?php if($Indicateur=="TauxReussite"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Taux de réussite";}else{echo "Success rate";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursSessions.php?Indicateur=InscritsAbsPresta";?>" <?php if($Indicateur=="InscritsAbsPresta"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "% des inscrits et des absents / prestations";}else{echo "% of registered and absent / site";}?>
					</a>
				</li>
			</ul>
		</td>
		<td width="85%" valign="top">
			<input type="hidden" name="Indicateur" value="<?php echo $Indicateur;?>" />
			<?php 
			if($Indicateur=="FormationsAnnulees"){
				require "FormationsAnnulees.php";
			}
			if($Indicateur=="ListeDesinscription"){
				require "Liste_Desinscription.php";
			}
			elseif($Indicateur=="BesoinsSansSession"){
				require "BesoinsSansSession.php";
			}
			elseif($Indicateur=="NbHeuresFormation"){
				require "NbHeuresFormation.php";
			}
			elseif($Indicateur=="NbPersonnesInscrites"){
				require "NbPersonnesInscrites.php";
			}
			elseif($Indicateur=="NbPersonnesPresentesAbs"){
				require "NbPersonnesPresentesAbs.php";
			}
			elseif($Indicateur=="NbSessionFormation"){
				require "NbSessionFormation.php";
			}
			elseif($Indicateur=="NbSessionParFormation"){
				require "NbSessionParFormation.php";
			}
			elseif($Indicateur=="NbEtCoutParFormation"){
				require "NbEtCoutParFormation.php";
			}
			elseif($Indicateur=="EvaluationAChaud"){
				require "EvaluationAChaud.php";
			}
			elseif($Indicateur=="TauxReussite"){
				require "TauxReussite.php";
			}
			elseif($Indicateur=="TauxRemplissageSessions"){
				require "TauxRemplissageSessions.php";
			}
			elseif($Indicateur=="TauxReussite"){
				require "TauxReussite.php";
			}
			elseif($Indicateur=="InscritsAbsPresta"){
				require "InscritsAbsPresta.php";
			}
			
			?>
		</td>
	</tr>
	<tr><td height="4"></td>
	</table>
</form>
</html>
	