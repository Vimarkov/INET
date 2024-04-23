<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetreAjout(Page){
		if(Page=="Feedback.php"){
			var w=window.open(Page+"?Mode=A&Id=0","Page","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
		}
		else{
			var w=window.open(Page+"?Mode=A&Id=0","Page","status=no,menubar=no,scrollbars=yes,width=800,height=600");
		}
		w.focus();
	}
	function OuvreFenetreSuppr(Page,Id){
		if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open(Page+"?Mode=S&Id="+Id,"Page","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
	}
	function OuvreFenetreModif(Page,Mode,Id)
	{
		var Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr" || Mode=="S"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr" || Mode=="S"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if(((Mode=="Suppr" || Mode=="S") && Confirm==true) || Mode=="A" || Mode=="M" || Mode=="R" || Mode=="V" || Mode=="T" || Mode=="AV")
		{
			var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=900,height=550");
			w.focus();
		}
	}
	function OuvreFenetreModifRubrique(Page,Id)
	{
		valeur=document.getElementById('etat_'+Id).value;
		Mode="";
		if(valeur==0){Mode="AV";}
		else if(valeur==1){Mode="V";}
		else if(valeur==2){Mode="T";}
		else if(valeur==-1){Mode="R";}
		if(Mode!=""){
			var w= window.open(Page+"?Mode="+Mode+"&Id="+Id,"PageTheme","status=no,menubar=no,width=900,height=550");
			w.focus();
		}
	}
	
	function OuvreFenetreExport()
	{window.open("Export_Evaluation.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function MettreEnZSORTIE(Id_Personne){
		var w=window.open("MettreEnZSORTIE.php?Id_Personne="+Id_Personne,"PageFichier","status=no,menubar=no,width=500,height=400,resizable=yes,scrollbars=yes");
		w.focus();
	}
	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
	
	function OuvrirImage(Page,Dossier1,Dossier2,Img)
		{window.open("Outils/OuvreImage.php?Page="+Page+"&Dossier1="+Dossier1+"&Dossier2="+Dossier2+"&Image="+Img,"PageImage","status=no,menubar=no,width=600,height=350,resizable=yes");}
	function OuvrirFichier(Page,Dossier1,Dossier2,Fic)
		{window.open("../Upload/Fichiers/"+Page+"/"+Dossier1+"/"+Dossier2+"/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
	function filtrer(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnReset2' name='btnReset2' value='Reset'>";
		document.getElementById('filtrer').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnReset2").dispatchEvent(evt);
		document.getElementById('filtrer').innerHTML="";
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

$DirFichier=$CheminOnBoarding;

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}

function Widget($Libelle,$Lien,$Image,$Couleur){
	echo "
			<table style='border-spacing: 25px;display:inline-table;' >
				<tr>
					<td style=\"width:150px;height:130px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-size:14px;font-weight:bold;\" bgcolor='".$Couleur."'>
						<a style=\"text-decoration:none;width:150px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
							<img width='50px' src='../../Images/".$Image."' border='0' /><br>
							".$Libelle."
						</a>
					</td>
				</tr>
			</table>";
}

function WidgetRond($Libelle,$Lien,$Image,$Couleur,$Couleur2){
	echo "
			<table style='border-spacing: 25px;display:inline-table;' >
				<tr>
					<td style=\"width:180px;height:180px;border-radius: 80px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-size:14px;font-weight:bold;border:".$Couleur2." 3px solid;\" bgcolor='".$Couleur."'>
						<a style=\"text-decoration:none;width:150px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
							<img width='50px' src='../../Images/".$Image."' border='0' /><br>
							".$Libelle."
						</a>
					</td>
				</tr>
			</table>";
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

function SousTitre($Libelle){
	$couleurTexte="#000000";
	echo "<td style=\"width:33%;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-size:18px;font-weight:bold;\">".$Libelle."</td>\n";
}

$req="SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
$resultAdm=mysqli_query($bdd,$req);
$nbAdm=mysqli_num_rows($resultAdm);

?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="TableauDeBord.php" method="post">
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td style="display:none;"><input name="Langue" id="Langue" value="<?php echo $LangueAffichage;?>"></td>
	</tr>
	<tr bgcolor="#6EB4CD">
	<?php
		$select=false;
		if(isset($Menu)){
			if($Menu==1){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("ACCUEIL SALARIES","Outils/Onboarding/TableauDeBord.php?Menu=1",$select);}
		else{Titre1("EMPLOYEE WELCOME","Outils/Onboarding/TableauDeBord.php?Menu=1",$select);}
		
		$select=false;
		if(isset($Menu)){
			if($Menu==7){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("ACCUEIL HORS SALARIES","Outils/Onboarding/TableauDeBord.php?Menu=7",$select);}
		else{Titre1("NON-EMPLOYEE WELCOME","Outils/Onboarding/TableauDeBord.php?Menu=7",$select);}

		if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM onboarding_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0 ||
		mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee))>0){
			$select=false;
			if(isset($Menu)){
				if($Menu==5){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("RUBRIQUES","Outils/Onboarding/TableauDeBord.php?Menu=5",$select);}
			else{Titre1("HEADINGS","Outils/Onboarding/TableauDeBord.php?Menu=5",$select);}
		}
		if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee))>0){
			$select=false;
			if(isset($Menu)){
				if($Menu==4){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("SUIVI DES ÉVALUATIONS","Outils/Onboarding/TableauDeBord.php?Menu=4",$select);}
			else{Titre1("FOLLOW-UP TO EVALUATIONS","Outils/Onboarding/TableauDeBord.php?Menu=4",$select);}
			
			$select=false;
			if(isset($Menu)){
				if($Menu==6){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("LISTE DES FUTURS ARRIVANTS","Outils/Onboarding/TableauDeBord.php?Menu=6",$select);}
			else{Titre1("LIST OF FUTURE ARRIVALS","Outils/Onboarding/TableauDeBord.php?Menu=6",$select);}
		}
		
		if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee))>0){
			$select=false;
			if(isset($Menu)){
				if($Menu==3){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("SUPER ADMINISTRATEUR","Outils/Onboarding/TableauDeBord.php?Menu=3",$select);}
			else{Titre1("SUPER ADMINISTRATOR","Outils/Onboarding/TableauDeBord.php?Menu=3",$select);}

			$select=false;
			if(isset($Menu)){
				if($Menu==2){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("ADMINISTRATEUR","Outils/Onboarding/TableauDeBord.php?Menu=2",$select);}
			else{Titre1("ADMINISTRATOR","Outils/Onboarding/TableauDeBord.php?Menu=2",$select);}
		}
	?>
	</tr>
	<tr>
		<td colspan="14" align="center" style="width:100%">
		<?php	
			if($Menu==1){
				require "Accueil.php";
			}
			elseif($Menu==2){
				require "Liste_Administrateur.php";
			}
			elseif($Menu==3){
				require "Liste_SuperAdministrateur.php";
			}
			elseif($Menu==4){
				require "Liste_Evaluation.php";
			}
			elseif($Menu==5){
				require "Liste_Rubrique.php";
			}
			elseif($Menu==6){
				require "Liste_FutursArrivants.php";
			}
			elseif($Menu==7){
				require "AccueilHorsSalarie.php";
			}
		?>
		</td>
	</tr>
</table>
</form>
</body>
</html>