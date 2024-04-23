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
	function Excel_PersonnesSansFormationNonObligatoire(){
		var w=window.open("Excel_PersonnesSansFormationNonObligatoire.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesFormationEnCours(){
		var w=window.open("Excel_PersonnesFormationEnCours.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_DureeFormationMetier(){
		var w=window.open("Excel_DureeFormationMetier.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
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
	function Excel_AvancementBesoin(){
		var w=window.open("Excel_AvancementBesoin.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesFormeesPrestations(qualification){
		var w=window.open("Excel_PersonnesFormeesPrestations.php?qualification="+qualification,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_PersonnesFormationNonEnCours(){
		var w=window.open("Excel_PersonnesFormationNonEnCours.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
</script>
<form id="formulaire" action="TDB_IndicateursBesoins.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#edf430;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Indicateurs - Besoins";}else{echo "Indicators - Needs";}
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
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=NbBesoin";?>" <?php if($Indicateur=="NbBesoin"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre besoins / Prestation";}else{echo "Number of needs / Site";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=AvancementBesoin";?>" <?php if($Indicateur=="AvancementBesoin"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "% avancement de traitement des besoins";}else{echo "% progress of needs processing";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=PersonnesFormationsEnCours";?>" <?php if($Indicateur=="PersonnesFormationsEnCours"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Formations en cours de validité";}else{echo "Training courses valid";}?>
					<span><?php if($LangueAffichage=="FR"){echo "Au moins une des qualifications de la formation en cours de validité";}else{echo "At least one of the qualifications of the course being valid";}?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=PersonnesFormationNonENCours";?>" <?php if($Indicateur=="PersonnesFormationNonENCours"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Personnes n'ayant pas une formation donnée";}else{echo "People with no training";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=PersonnesSansFormation";?>" <?php if($Indicateur=="PersonnesSansFormation"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Personnes n'ayant jamais suivi une formation donnée";}else{echo "People who have never taken a specific course";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=PersonnesSansFormationNonObligatoire";?>" <?php if($Indicateur=="PersonnesSansFormationNonObligatoire"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Personnes n'ayant jamais suivi de formations non obligatoires";}else{echo "People who have never completed any non-compulsory training";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=DureeFormationMetier";?>" <?php if($Indicateur=="DureeFormationMetier"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Durée formations / métier / prestation";}else{echo "Duration of training / profession / site";}?>
					</a>
				</li>
				<li>
					<a href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/TDB_IndicateursBesoins.php?Indicateur=PersonnesFormees";?>" <?php if($Indicateur=="PersonnesFormees"){echo "style='background: #3f93ef;'";} ?>>&bull;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Personnes formées / Prestation";}else{echo "Trained Persons / site";}?>
					</a>
				</li>
			</ul>
		</td>
		<td width="85%" valign="top">
			<input type="hidden" name="Indicateur" value="<?php echo $Indicateur;?>" />
			<?php 
			if($Indicateur=="PersonnesFormees"){
				require "PersonnesFormeesPrestations.php";
			}
			elseif($Indicateur=="NbBesoin"){
				require "NbBesoinFormation.php";
			}
			elseif($Indicateur=="PersonnesSansFormation"){
				require "PersonnesSansFormation.php";
			}
			elseif($Indicateur=="PersonnesFormationNonENCours"){
				require "PersonnesFormationNonENCours.php";
			}
			elseif($Indicateur=="PersonnesSansFormationNonObligatoire"){
				require "PersonnesSansFormationNonObligatoire.php";
			}
			elseif($Indicateur=="PersonnesFormationsEnCours"){
				require "PersonnesFormationsEnCours.php";
			}
			elseif($Indicateur=="DureeFormationMetier"){
				require "DureeFormationMetier.php";
			}
			elseif($Indicateur=="AvancementBesoin"){
				require "AvancementBesoin.php";
			}
			
			?>
		</td>
	</tr>
	<tr><td height="4"></td>
	</table>
</form>
</html>
	