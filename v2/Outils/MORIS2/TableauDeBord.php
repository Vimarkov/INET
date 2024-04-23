<?php
require("../../Menu.php");
?>
<script>
	function reset2(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnReset2' name='btnReset2' value='Reset'>";
		document.getElementById('reset').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnReset2").dispatchEvent(evt);
		document.getElementById('reset').innerHTML="";
	}
	function OuvreFenetreExcel(Page){
		window.open("Export_"+Page+".php","PageExcel","status=no,menubar=no,width=50,height=50");
	}
	function OuvreFenetre(Page){
		window.open(Page+".php","NewPage","status=no,menubar=no,width=50,height=50");
	}
	function OuvreFenetre2(Page,Parametre){
		window.open(Page+".php?Parametre="+Parametre,"NewPage","status=no,menubar=no,width=50,height=50");
	}
	tabUER = new Array();
	tabPresta = new Array();
	tabRP = new Array();
	function SelectionnerTout2(Champ)
	{
		var elements = document.getElementsByClassName("check"+Champ);
		if (document.getElementById('selectAll'+Champ).checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function SelectionnerTout(Champ)
	{
		var elements = document.getElementsByClassName("check"+Champ);
		if (document.getElementById('selectAll'+Champ).checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
		for(var i=0, l=elements.length; i<l; i++){
			Recherche="";
			leChamps="";
			if(Champ=="Division"){
				Selectionner("UER",elements[i].value);
			}
		}
		if(Champ!="Division" && Champ!="Prestation"){
			Selectionner("Prestation",-1);
		}
	}
	function Selectionner(Champ,Valeur){
		if(Champ=="UER"){
			checkF=false;
			checkT=true;
			if(document.getElementById("division"+Valeur).checked == false){
				checkF=true;
				checkT=false;
			}
			var elements = document.getElementsByClassName("check"+Champ);
			for(var i=0, l=elements.length; i<l; i++){
				check=checkF;
				for(var k=0, m=tabUER.length; k<m; k++){
					if(tabUER[k][0]==elements[i].value){
						if(tabUER[k][1]==Valeur){
							elements[i].checked =checkT;
							Selectionner("Prestation",elements[i].value);
						}
					}
				}
			}
		}
		else if(Champ=="Prestation"){
			var elements = document.getElementsByClassName("check"+Champ);
			for(var i=0, l=elements.length; i<l; i++){
				for(var k=0, m=tabPresta.length; k<m; k++){
					if(tabPresta[k][0]==elements[i].value){
						if(
							(document.getElementById("plateforme"+tabPresta[k][1])!= null && tabPresta[k][1]==document.getElementById("plateforme"+tabPresta[k][1]).value && document.getElementById("plateforme"+tabPresta[k][1]).checked == true)
							&&
							(document.getElementById("contrat"+tabPresta[k][2])!= null && tabPresta[k][2]==document.getElementById("contrat"+tabPresta[k][2]).value && document.getElementById("contrat"+tabPresta[k][2]).checked == true)
							&&
							(document.getElementById("r03"+tabPresta[k][3])!= null && tabPresta[k][3]==document.getElementById("r03"+tabPresta[k][3]).value && document.getElementById("r03"+tabPresta[k][3]).checked == true)
							&&
							(document.getElementById("client"+tabPresta[k][4])!= null && tabPresta[k][4]==document.getElementById("client"+tabPresta[k][4]).value && document.getElementById("client"+tabPresta[k][4]).checked == true)
							&&
							(document.getElementById("divisionclient"+tabPresta[k][5])!= null && tabPresta[k][5]==document.getElementById("divisionclient"+tabPresta[k][5]).value && document.getElementById("divisionclient"+tabPresta[k][5]).checked == true)
							&&
							(document.getElementById("entiteachat"+tabPresta[k][6])!= null && tabPresta[k][6]==document.getElementById("entiteachat"+tabPresta[k][6]).value && document.getElementById("entiteachat"+tabPresta[k][6]).checked == true)
							&&
							((document.getElementById("RP"+tabPresta[k][7])!= null && tabPresta[k][7]==document.getElementById("RP"+tabPresta[k][7]).value && document.getElementById("RP"+tabPresta[k][7]).checked == true)
							|| (tabPresta[k][7]== null && document.getElementById("RP0").checked == true)
							)
						){
							elements[i].checked =true;
						}
						else{
							elements[i].checked =false;
						}
					}
				}
			}
		}
	}
	function CockpitPDF()
	{
		chargeCapa=1;
		production=1;
		management=1;
		otd=1;
		oqd=1;
		competence=1;
		prm=1;
		securite=1;
		nc=1;
		if(document.getElementById("checkChargeCapa").checked==false){chargeCapa=0;}
		if(document.getElementById("checkProductivite").checked==false){production=0;}
		if(document.getElementById("checkManagement").checked==false){management=0;}
		if(document.getElementById("checkOTD").checked==false){otd=0;}
		if(document.getElementById("checkOQD").checked==false){oqd=0;}
		if(document.getElementById("checkCompetence").checked==false){competence=0;}
		if(document.getElementById("checkPRM").checked==false){prm=0;}
		if(document.getElementById("checkSecurite").checked==false){securite=0;}
		if(document.getElementById("checkNC").checked==false){nc=0;}
		savePDF(chargeCapa,production,management,otd,oqd,competence,prm,securite,nc);
	}
	function GraphiquesPDF()
	{
		chargeCapa=1;
		production=1;
		management=1;
		otd=1;
		oqd=1;
		competence=1;
		prm=1;
		securite=1;
		nc=1;
		pdp=1;
		if(document.getElementById("checkChargeCapa").checked==false){chargeCapa=0;}
		if(document.getElementById("checkProductivite").checked==false){production=0;}
		if(document.getElementById("checkManagement").checked==false){management=0;}
		if(document.getElementById("checkOTD").checked==false){otd=0;}
		if(document.getElementById("checkOQD").checked==false){oqd=0;}
		if(document.getElementById("checkCompetence").checked==false){competence=0;}
		if(document.getElementById("checkPRM").checked==false){prm=0;}
		if(document.getElementById("checkSecurite").checked==false){securite=0;}
		if(document.getElementById("checkNC").checked==false){nc=0;}  
		if(document.getElementById("checkPDP").checked==false){pdp=0;}
		savePDF(chargeCapa,production,management,otd,oqd,competence,prm,securite,nc,pdp);
	}
	function nombreEval(champ){
		var chiffres = new RegExp("[0-9\.NA]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
		var verif;
		var points = 0; /* Supprimer cette ligne */

		for(x = 0; x < champ.value.length; x++)
		{
		verif = chiffres.test(champ.value.charAt(x));
		if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
		if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
		if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
		}
	}
	function AfficherInfoBulle(div,liste,titre){
		document.getElementById("div_"+div).innerHTML="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'><tr><td style='border:1px solid black;'>"+titre+"</td></tr><tr><td style='border:1px solid black;'>"+liste+"</td></tr></table>";
	}
	function OuvreFenetreCockpit(prestation,annee,mois){
		var xhr2 = $.ajax({
			url : 'Ajax_MAJVariableSession.php?Prestation='+prestation+'&Annee='+annee+'&Mois='+mois,
			dataType : 'html',
			async : false,
			error:function(msg, string){
				
				},
			success:function(data){
				}
		});
		window.location.href=document.getElementById('http').value+"://"+document.getElementById('servername').value+"/v2/Outils/MORIS2/TableauDeBord.php?Menu=1";
	}
</script>
<style>
	#leHover2{
		position: relative;
		color : black;
		text-decoration: none;
	}
	#leHover2 span {
	   display: none; /*  On masque l'infobulle. */
	}

	#leHover2:hover  {
	   z-index: 500; /* On définit une valeur pour l'ordre d'affichage. */
		/* cursor: help; On change le curseur par défaut par un curseur d'aide. */
	}
	#leHover2:hover  span {
	   display: inline; /* On affiche l'infobulle. */
	   position: absolute;
	   top: 30px; /* On positionne notre infobulle. */
	   left: 20px;
	   background: white;
	   color: black;
	   padding: 3px;
	   border: 1px solid #75a3ff;
	   border-left: 4px solid #75a3ff;
	   font-size:12px;
	   text-align:left;
	   width:500px;
	}

	#leHover2:hover  span table,#leHover2:hover  span table td {
	   border: 1px solid black;
	   text-align : center;
	   border-collapse : collapse;
	}
</style>
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

$DirFichierSQCDPF="Outils/MORIS2/SQCDPF/";
$DirFichierSQCDPF2="SQCDPF/";

$DirFichierAT="Outils/MORIS2/AT/";
$DirFichierAT2="AT/";

$DirFichierNC="Outils/MORIS2/NCDAC/";
$DirFichierNC2="NCDAC/";

$DirFichierPRM="Outils/MORIS2/PRM/";
$DirFichierPRM2="PRM/";

$DirFichierSatisfactionClient="Outils/MORIS2/SatisfactionClient/";
$DirFichierSatisfactionClient2="SatisfactionClient/";

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}
function unNombreSinon0ouNA($leNombre){
	$nb=0;
	if($leNombre<>""){
		if($leNombre=="NA" || $leNombre=="N" || $leNombre=="A"){
			$nb=-1;
		}
		else{
			$nb=(double)$leNombre;
		}
	}
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
			<?php
			if($LangueAffichage=="FR"){echo "Vous avez des questions, un problème ? Contactez-nous : ";}
			else{echo "Do you have questions or a problem? Contact us : ";}
			?>
			<span style="color:#00577c;">help-record.aaa@daher.com </span>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr bgcolor="#6EB4CD">
		<td style="width:20%;font-size:20px;height:20px;border-spacing:0;text-align:center;color:#00567c;valign:top;font-weight:bold;background:#ffffff;border:#6EB4CD 5px dotted;">
			<?php 
				if($_SESSION["Langue"]=="FR"){echo "RECORD";}
				else{echo "RECORD";}
			?>
		</td>
	<?php
		
		$select=false;
		if(isset($Menu)){
			if($Menu==1){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("COCKPIT PRESTATION","Outils/MORIS2/TableauDeBord.php?Menu=1",$select);}
		else{Titre1("COCKPIT SITE","Outils/MORIS2/TableauDeBord.php?Menu=1",$select);}

		$select=false;
		if(isset($Menu)){
			if($Menu==2){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("SAISIE PRESTATION","Outils/MORIS2/TableauDeBord.php?Menu=2",$select);}
		else{Titre1("ENTRY SITE","Outils/MORIS2/TableauDeBord.php?Menu=2",$select);}
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Prestation=new_competences_prestation.Id 
				AND Id_Poste IN (4,5)
				)>0";
		$resultPrestation=mysqli_query($bdd,$req);
		$nbPrestation=mysqli_num_rows($resultPrestation);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Prestation=new_competences_prestation.Id 
				AND Id_Poste IN (4)
				)>0";
		$resultRP=mysqli_query($bdd,$req);
		$nbRP=mysqli_num_rows($resultRP);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND ((SELECT COUNT(Id) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Id_Poste IN (6)
				)>0
				OR 
				(SELECT COUNT(Id) 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Id_Poste IN (6)
				)>0
				)";
		$resultCQS=mysqli_query($bdd,$req);
		$nbCQS=mysqli_num_rows($resultCQS);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE new_competences_prestation.UtiliseMORIS=1
			AND (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Id_Poste IN (27)
				)>0";
		$resultCG=mysqli_query($bdd,$req);
		$nbCG=mysqli_num_rows($resultCG);
		
		$req="SELECT Id
			FROM new_competences_prestation
			WHERE (SELECT COUNT(Id) 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Plateforme=17
				AND Id_Poste IN (9,15,27,41,44)
				)>0";
		$resultPlat=mysqli_query($bdd,$req);
		$nbPlat=mysqli_num_rows($resultPlat);
		
		if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbPrestation>0 || $nbPlat>0 || $nbCQS>0 || $nbCG>0){
			$select=false;
			if(isset($Menu)){
				if($Menu==7){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("GRAPHIQUES CONSOLIDÉS","Outils/MORIS2/TableauDeBord.php?Menu=7",$select);}
			else{Titre1("CONSOLIDATED GRAPHICS","Outils/MORIS2/TableauDeBord.php?Menu=7",$select);}
		}
		
		if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRP>0){
			$select=false;
			if(isset($Menu)){
				if($Menu==8){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("SUIVI DES ENREGISTREMENTS","Outils/MORIS2/TableauDeBord.php?Menu=8",$select);}
			else{Titre1("FOLLOW UP RECORDINGS","Outils/MORIS2/TableauDeBord.php?Menu=8",$select);}
		}
		
		if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0)){
			$select=false;
			if(isset($Menu)){
				if($Menu==3 || $Menu==5 || $Menu==4 || $Menu==9 || $Menu==10 || $Menu==11 || $Menu==12 || $Menu==13 || $Menu==15 || $Menu==16 || $Menu==18){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("ADMINISTRATION","Outils/MORIS2/TableauDeBord.php?Menu=3",$select);}
			else{Titre1("ADMINISTRATION","Outils/MORIS2/TableauDeBord.php?Menu=3",$select);}
		}
		
		if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne IN (1351,2526)  AND Id_Personne=".$IdPersonneConnectee))>0)){
			$select=false;
			if(isset($Menu)){
				if($Menu==17){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("IMPORT","Outils/MORIS2/TableauDeBord.php?Menu=17",$select);}
			else{Titre1("IMPORT","Outils/MORIS2/TableauDeBord.php?Menu=17",$select);}
		}
		
		$select=false;
		if(isset($Menu)){
			if($Menu==14){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("TUTORIEL","Outils/MORIS2/TableauDeBord.php?Menu=14",$select);}
		else{Titre1("TUTORIAL","Outils/MORIS2/TableauDeBord.php?Menu=14",$select);}
	?>
	</tr>
	<tr>
		<td colspan="14" align="center" style="width:100%">
		<?php	
			if($Menu==1){
				require "TDB.php";
			}
			elseif($Menu==2){
				require "TDB_Saisie.php";
			}
			elseif($Menu==3){
				require "Liste_Administrateur.php";
			}
			elseif($Menu==4){
				require "Liste_Prestation.php";
			}
			elseif($Menu==5){
				
				require "Liste_Aide.php";
			}
			elseif($Menu==7){
				require "Liste_Graphique.php";
			}
			elseif($Menu==8){
				require "Liste_Administration.php";
			}
			elseif($Menu==9){
				require "Liste_Contrat.php";
			}
			elseif($Menu==10){
				require "Liste_Client.php";
			}
			elseif($Menu==11){
				require "Liste_EntiteAchat.php";
			}
			elseif($Menu==12){
				require "Liste_Programme.php";
			}
			elseif($Menu==13){
				require "Liste_DivisionClient.php";
			}
			elseif($Menu==14){
				require "Tutoriel.php";
			}
			elseif($Menu==15){
				require "Liste_Famille.php";
			}
			elseif($Menu==16){
				require "Liste_AccesSupplementaires.php";
			}
			elseif($Menu==17){
				require "Liste_Import.php";
			}
			elseif($Menu==18){
				require "Liste_Objectif.php";
			}
		
		?>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>