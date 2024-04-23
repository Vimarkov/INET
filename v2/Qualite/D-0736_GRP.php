<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title>
	<style type="text/css">
		table, td, th {border : 1px solid black; border-collapse : collapse;}
		body {font-family : Arial; font-size : 12px;}
		tbody{
			height: 10em;                   /* définit une hauteur */
			overflow-x: hidden;             /* esthétique */
			overflow-y: auto;               /* permet de scroller les cellules */
		}
	</style>
	
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
	<script type="text/javascript" src="https://unpkg.com/sticky-table-headers@0.1.24/js/jquery.stickytableheaders.min.js"></script>

	<script type="text/javascript">
	$(function() {
	  $("table").stickyTableHeaders();
	});
	</script>
	
	<script language="javascript">
		function OuvrirFichier(Fic)
			{window.open("../"+Fic,"PageFichierQualite","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
		function OuvrirFichier2(Fic)
			{window.open(Fic,"PageFichierQualite","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
		
		function Voir_TR(Name)
		{
			table = document.getElementById('TABLE_DQ').getElementsByTagName('TR')
			for (l=0;l<table.length+1;l++)
			{
				if(table[l].getAttribute("name")==Name)
				{
					for (m=l+1;table.length+1;m++)
					{
						if(table[m].getAttribute("name")!=null){break;}
						if(table[m].style.display == ''){table[m].style.display = 'none';}
						else{table[m].style.display = '';}
					}
				}
			}
		}
		
		function Masquer_Tout()
		{
			table = document.getElementById('TABLE_DQ').getElementsByTagName('TR')
			for (l=5;l<table.length+1;l++)
			{
				if(table[l].getAttribute("name")==null){table[l].style.display = 'none';}
				else{table[l].style.display = '';}
			}
		}
	</script>
</head>

<?php
require("../Outils/Connexioni.php");
require_once("../Outils/Formation/Globales_Fonctions.php");
require_once("../Outils/Fonctions.php");

if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
|| $_SERVER['SERVER_NAME']=="192.168.20.3" 
|| $_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
	$chemin="http://".$_SERVER['SERVER_NAME']."/v2";
}
else{
	$chemin="https://".$_SERVER['SERVER_NAME']."/v2";
}
if(!isset($_SESSION['Log'])){echo "<body onload='window.top.location.href=\"".$chemin."/index.php\";'>";}
elseif($_SESSION['Log']==""){echo "<body onload='window.top.location.href=\"".$chemin."/index.php\";'>";}
else
{
?>

<body style="background-color:#FFFFFF">
<div id="TABLE_DQ">
<table>
	<thead>
	<tr>
		<th rowspan=3 width=100>&nbsp;</th>
		<th rowspan=2 align="center"><img src="../../../v2/Images/Logos/Logo_Doc_Group.png" border="0"></th>
		<th colspan=13 align="center"bgcolor="#DDDDDD"><font size="5" color="#1f36b4"><b>LISTE DES DOCUMENTS QUALITE EN VIGUEUR</b></font></th>
		<th rowspan=2 colspan=2 align="center">All entities</th>
	</tr>
	<tr>
		<th colspan=13 align="center" bgcolor="#AAAAAA"><font size="5" color="#1f36b4"><b>LIST OF APPLICABLE QUALITY DOCUMENTS</b></font></th>
	</tr>
	<tr height=30>
		<th colspan=2>Mis à  jour/Updated : 18/06/2019</th>
		<th colspan=12>Par/By : François MANESSE</th>
		<th colspan=2>Visa/Signature : FME</th>
	</tr>
	<tr>
		<th rowspan=2 align="center" bgcolor="#DDDDDD">Référence ancien DQ<br>Previous QD reference :</th>
		<th rowspan=2 align="center" bgcolor="#DDDDDD">Référence<br>Reference</th>
		<th rowspan=2 align="center" bgcolor="#DDDDDD">Désignation<br>Designation</th>
		<th colspan=2 align="center" bgcolor="#DDDDDD">AAA Group</th>
		<th colspan=2 align="center" bgcolor="#DDDDDD">France<br>FR</th>
		<th colspan=2 align="center" bgcolor="#DDDDDD">Germany<br>DE</th>
		<th align="center" bgcolor="#DDDDDD">China<br>CH</th>
		<th align="center" bgcolor="#DDDDDD">Philippines<br>PH</th>
		<th align="center" bgcolor="#DDDDDD">ASM Maroc<br>MA</th>
		<th colspan=2 align="center" bgcolor="#DDDDDD">Canada<br>CA</th>
		<th align="center" bgcolor="#DDDDDD">Etats-Unis<br>USA</th>
		<th rowspan=2 align="center" bgcolor="#DDDDDD">/</th>
		<th rowspan=2 align="center" bgcolor="#DDDDDD">Processus<br>Process</th>
	</tr>
	<tr>
		<th align="center" bgcolor="#DDDDDD">FR</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
		<th align="center" bgcolor="#DDDDDD">FR</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
		<th align="center" bgcolor="#DDDDDD">DE</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
		<th align="center" bgcolor="#DDDDDD">FR</th>
		<th align="center" bgcolor="#DDDDDD">FR</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
		<th align="center" bgcolor="#DDDDDD">EN</th>
	</tr>
	</thead>
	
	<!--###################################################-->
	<!--###################################################-->
	<!------------------------- DQ -------------------------->
	<!--###################################################-->
	<!--###################################################-->

	<tbody>
	<tr name="CHAPITRE_2">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_2')">
				<font style="color:white;">CHAP 2 : Hygiène,Sécurité & Environnement -- Health, Safety & Environment</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0200</td>
		<!-- COLONNE DESIGNATION   --><td>Manuel HSE</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0200/D-0200_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0210</td>
		<!-- COLONNE DESIGNATION   --><td>Exigences réglementaires et veille</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0210/D-0210_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0220</td>
		<!-- COLONNE DESIGNATION   --><td>Évaluation des risques professionnels</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0220/D-0220_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0230</td>
		<!-- COLONNE DESIGNATION   --><td>Accueil et formations HSE</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0230/D-0230_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0240</td>
		<!-- COLONNE DESIGNATION   --><td>Consignes HSE</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0240/D-0240-001-FR.pptx')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0250</td>
		<!-- COLONNE DESIGNATION   --><td>Gestion des Accidents de Travail (AT)8)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0250/D-0250_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0260</td>
		<!-- COLONNE DESIGNATION   --><td>Procédure des audits HSE</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0260/D-0260-1_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0270</td>
		<!-- COLONNE DESIGNATION   --><td>Gestions des intervenants extérieurs</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0270/D-0270_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0280</td>
		<!-- COLONNE DESIGNATION   --><td>Communications et affichages HSE</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('2/D-0280/D-0280_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>

<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_4">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_4')">
				<font style="color:white;">CHAP 4 : Contexte de l'organisme / Context of the organization</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0401</td>
		<!-- COLONNE DESIGNATION   --><td>Cartographie des processus<br>Process cartography</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('4/D-0401-GRP.pdf')">3</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('4/D-0401-GRP_EN.pdf')">3</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ403</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0402</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des processus en vigueur<br>Applicable processes list</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('4/D-0402-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('4/D-0402-GRP_EN.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0403</td>
		<!-- COLONNE DESIGNATION   --><td>Template Processus - Revue de processus (D-0738)<br>Template Process - Process review (D-0738)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('4/D-0403_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0403</td>
		<!-- COLONNE DESIGNATION   --><td>Revues de processus renseignées<br>Process review filled out</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('4/D-0403/CONSOLIDE/D-0403_D-0738-CONSOLIDE.xls')">X (consolidé)</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/FR/D-0403_D-0738-FR.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/DE/D-0403_D-0738-DE.xls')">X</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/CH/D-0403_D-0738-CH.xls')">X</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/PH/D-0403_D-0738-PH.xls')">X</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/MA/D-0403_D-0738-MA.xls')">X</a></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/CA/D-0403_D-0738-CA.xls')">X</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('4/D-0403/US/D-0403_D-0738-US.xls')">X</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<?php
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsablePlateforme)))
	{
	?>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0404</td>
		<!-- COLONNE DESIGNATION   --><td>Stratégie AAA Group<br>AAA Group Strategy</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('4/D-0403/D-0404-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0405</td>
		<!-- COLONNE DESIGNATION   --><td>SWOT (template)<br>SWOT (template)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('4/D-0405-GRP.xlsx')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0406</td>
		<!-- COLONNE DESIGNATION   --><td>Matrice des parties interessees pertinentes<br>Table of relevant interested parties</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('4/D-0406-GRP.xlsx')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0407</td>
		<!-- COLONNE DESIGNATION   --><td>Matrice des pilotes de processus<br>Table of Processes pilots</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('4/D-0407-GRP.xlsx')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">&nbsp;</td>
	</tr>
	
<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_5">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_5')">
				<font style="color:white;">CHAP 5 : Leadership / Leadership</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ505</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0501</td>
		<!-- COLONNE DESIGNATION   --><td>Organigramme direction qualité AAA Group<br>AAA Group quality Management organization chart</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('5/D-0501-GRP-fr.pdf')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ502</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0502</td>
		<!-- COLONNE DESIGNATION   --><td>Organigramme plateforme (Vierge + renseigné)<br>Platform organization chart (blank+completed)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0502_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ503</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0503</td>
		<!-- COLONNE DESIGNATION   --><td>Organigramme général<br>General organization chart</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-GRP-fr.pdf')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-GRP-en.pdf')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-FR.pdf')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-FR-en.pdf')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-DE.pdf')">1</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-CH.pdf')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-PH.pdf')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-MA.pdf')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-CA.pdf')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('5/D-0503/D-0503-US.pdf')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ504</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0504</td>
		<!-- COLONNE DESIGNATION   --><td>Organigramme Spécifique<br>Specific organization chart</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"></td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-FR-en.doc')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-DE.doc')">1</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-CH.doc')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-PH.doc')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-CA.doc')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('5/D-0504/D-0504-US.doc')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ514</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0505</td>
		<!-- COLONNE DESIGNATION   --><td>Organigramme fonctionnel FOD renseigné<br>Completed FOD functional organization chart</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('5/D-0505-GRP-fr-en.pdf')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ515</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0506</td>
		<!-- COLONNE DESIGNATION   --><td>Organigramme fonctionnel des agents CND<br>NDT personnels functional organization chart</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('5/D-0506-GRP-fr.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('5/D-0506-FR.pdf')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0734IQSS601-D0738-GRP.xls')">IQSS601</a></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ508</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0507</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche Métier (vierge)<br>Job form (blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0507/D-0507-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0507/D-0507-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ508</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0507</td>
		<!-- COLONNE DESIGNATION   --><td>Fiches Métier renseignées (voir D-0738)<br>Completed Job forms (see D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0507/D-0507_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0507/D-0507_D-0738-GRP-en.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ516</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0508</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche Fonction Support (vierge)<br>Support function form (blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0508/D-0508-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0508/D-0508-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ516</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0508</td>
		<!-- COLONNE DESIGNATION   --><td>Fiches Fonction Support renseignées (D-0738)<br>Completed Support function forms (D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0508/D-0508_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0508/D-0508_D-0738-GRP-en.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ517</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0509</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche Fonction Encadrement (vierge)<br>Supervision function form (blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0509/D-0509-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0509/D-0509-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ517</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0509</td>
		<!-- COLONNE DESIGNATION   --><td>Fiches Fonction Encadrement renseignées (D-0738)<br>Completed supervision function forms (D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0509/D-0509_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ518</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0510</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche Fonction Direction (vierge)<br>Management function form (blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0510/D-0510-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0510/D-0510-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ518</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0510</td>
		<!-- COLONNE DESIGNATION   --><td>Fiches Fonction Direction renseignées (D-0738)<br>Completed management function forms (D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0510/D-0510_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ511</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0511</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de poste<br>"On work station" form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0511-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('5/D-0511-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>

<!-- ############################################################################################################################################################# -->
<!-- MAJ DEPUIS MACRO -->
<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_6">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_6')">
				<font style="color:white;">CHAP 6 : Planification / Planning</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ506</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0601</td>
		<!-- COLONNE DESIGNATION   --><td>Plan d'actions<br>Actions plan</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('6/D-0601-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('6/D-0601-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ512</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0602</td>
		<!-- COLONNE DESIGNATION   --><td>Reporting (template)<br> Reporting (template)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('6/D-0601-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<?php
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsablePlateforme)))
	{
	?>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0602</td>
		<!-- COLONNE DESIGNATION   --><td>Reporting (renseigné) <br>Reporting (filled out)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('6/D-0602/CONSOLIDE/D-0602_D-0738-CONSOLIDE.xls')">X (consolidé)</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/FR/D-0602_D-0738-FR.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/DE/D-0602_D-0738-DE.xls')">X</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/CH/D-0602_D-0738-CH.xls')">X</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/PH/D-0602_D-0738-PH.xls')">X</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/MA/D-0602_D-0738-MA.xls')">X</a></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/CA/D-0602_D-0738-CA.xls')">X</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('6/D-0602/US/D-0602_D-0738-US.xls')">X</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ513</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0603</td>
		<!-- COLONNE DESIGNATION   --><td>Bilan des plans d'actions - Reporting<br>Actions Plans summary - Reporting</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('6/D-0603-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	
<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_7">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_7')">
				<font style="color:white;">CHAP 7 : Support / Support</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ718</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0701</td>
		<!-- COLONNE DESIGNATION   --><td>Etat du personnel<br>Personnel statement</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0701-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0701-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ728</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0702</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche contact<br>Contact form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0702-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0702-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ603</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0703</td>
		<!-- COLONNE DESIGNATION   --><td>Dossier individuel<br>Individual file</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0703-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0703-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ739</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0704</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de sortie personnel<br>Personnel quiting form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0704-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0704-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ608</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0705</td>
		<!-- COLONNE DESIGNATION   --><td>Entretiens professionnels (D-0738)<br>Professional interviews (D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0705/D-0705_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0705/D-0705_D-0738-GRP-EN.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('7/D-0705/D-0705-CH_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ610</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0706</td>
		<!-- COLONNE DESIGNATION   --><td>Lettre d’accréditation (CV)<br>Letter of accreditation (CV)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0706-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0706-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ616</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0707</td>
		<!-- COLONNE DESIGNATION   --><td>Demande d’émission d’un contrat ou d’un avenant<br>Request for contract or amendment issue</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0707-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">TBD</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">TBD</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">TBD</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0707-CA.pdf')">2</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0707-CA-en.pdf')">2</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">TBD</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ618</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0708</td>
		<!-- COLONNE DESIGNATION   --><td>Relevé d’heure individuel mensuel<br>Statement of monthly individual timesheet</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0708-FR.xlsx')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center"><a href="javascript:OuvrirFichier('7/D-0708-DE.xlsx')">1</a></td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('7/D-0708-CH.xls')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('7/D-0708-PH.xlsx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0708-CA.xlsx')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0708-CA-en.xlsx')">1</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ627</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0709</td>
		<!-- COLONNE DESIGNATION   --><td>Ordre de mission & déplacement<br> Mission & travel order</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0738_D-0709-FR.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0738_D-0709-FR-en.xls')">X</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ617</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0710</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche d’inventaire<br>Inventory form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0710-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0710-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ736</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0711</td>
		<!-- COLONNE DESIGNATION   --><td>Prêt de matériel<br>Loan of equipment</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0711-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0711-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ606</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0712</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de vie infrastructure sous surveillance<br>Under supervision infrastructure record form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0712-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0712-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ415</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0713</td>
		<!-- COLONNE DESIGNATION   --><td>Demande informatique<br>IT request</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0713-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0713-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ620</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0714</td>
		<!-- COLONNE DESIGNATION   --><td>Gestion du parc informatique<br>Management of IT stock</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0714-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0714-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ901</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0715</td>
		<!-- COLONNE DESIGNATION   --><td>Plan de prévention <br>Prevention plan</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0715-MA.doc')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ723</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0716</td>
		<!-- COLONNE DESIGNATION   --><td>Enregistrement des ECME<br>CMTE (Control, Measurement and Testing Equipment) record</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0716-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0716-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ729</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0717</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de vie<br>Lifetime form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0717-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0717-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ809</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0718</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche d'enquête suite à matériel déclassé ou réformé<br>Inquiry form further to dispositioned or rejected equipment</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0718-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0718-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ602</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0719</td>
		<!-- COLONNE DESIGNATION   --><td>Plan de formation<br>Training plan</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0719-GRP.xlsx')">2</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0719-GRP-en.xlsx')">2</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ609</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0720</td>
		<!-- COLONNE DESIGNATION   --><td>Catalogue Formation (Vierge)<br>Training catalogue (Blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0720-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('7/D-0720-CH.xls')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('7/D-0720-PH.xlsx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0720</td>
		<!-- COLONNE DESIGNATION   --><td>Catalogue Formation (Renseigné)<br>Training catalogue (filled out)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0720-FR-renseigne.xlsx')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ622</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0721</td>
		<!-- COLONNE DESIGNATION   --><td>Catalogue formation par organisme (vierge)<br>Training catalogue per body (Blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0721-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ615</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0722</td>
		<!-- COLONNE DESIGNATION   --><td>Identification des besoins en formation<br>Identification of training needs</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0722-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0722-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ619</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0723</td>
		<!-- COLONNE DESIGNATION   --><td>Demande de formation<br>Training request</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0723-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0723-EN.doc')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('7/D-0723-CH.docx')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('7/D-0723-PH.docx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0723-CA.doc')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0723-CA-en.doc')">1</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">/</td>
		<!-- COLONNE REFERENCE     --><td align="center">/</td>
		<!-- COLONNE DESIGNATION   --><td>Modules de formation (sous extranet rubrique Pyramide SMQ)<br>Training modules (under extranet QMS pyramid)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ628</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0724</td>
		<!-- COLONNE DESIGNATION   --><td>Bilan des formations - Reporting<br>Training summary - Reporting</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0724-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0724-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ605</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0725</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de présence<br>Attendance form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0725-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0725-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ626</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0726</td>
		<!-- COLONNE DESIGNATION   --><td>Attestation de formation<br>Training certificate</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR/EN  --><td align="center" colspan=2><a href="javascript:OuvrirFichier2('https://extranet.aaa-aero.com/Qualite/DQ/6/DQ626-FR.doc')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('7/D-0726/D-0726-DE.doc')">2</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('7/D-0726/D-0726-CH-en.doc')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('7/D-0726/D-0726-PH.doc')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0726/D-0726-MA.doc')">1</a></td>
		<!-- COLONNE CANADA FR/EN  --><td align="center" colspan=2><a href="javascript:OuvrirFichier('7/D734/D734-CA.doc')">X</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ612</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0727</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche d'évaluation formation<br>Training assessment form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0727-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0727-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ604</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0728</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de formation préliminaire<br>Preliminary training form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0728-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0728-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ614</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0729</td>
		<!-- COLONNE DESIGNATION   --><td>Tableau de polyvalence<br>Polyvalence table</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0729-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0729-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ611</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0730</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de qualification<br>Qualification form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0730-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0730-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ624</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0731</td>
		<!-- COLONNE DESIGNATION   --><td>Competency list (sous extranet rubrique RH<br>GPEC)<br>Competency list (under extranet heading HR<br>GPEC)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ625</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0732</td>
		<!-- COLONNE DESIGNATION   --><td>Individual Competency list (sous extranet rubrique RH<br>GPEC)  Individual competency list (under extranet heading HR<br>GPEC)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ738</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0733</td>
		<!-- COLONNE DESIGNATION   --><td>Lettre d’engagement<br>Commitment Letter</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-FR-en.doc')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-DE.doc')">1</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-CH.doc')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-PH.doc')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-MA.doc')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('7/D-0733/D-0733-CA.doc')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">TBD</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ607</td>
		<!-- COLONNE REFERENCE     --><td align="center"><strike>D-0734</strike></td>
		<!-- COLONNE DESIGNATION   --><td><strike>Etat des certifications et re-certifications des agents CND<br>NDT personnels certification and re-certification status</strike></td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2>Cancelled<br>Available through D-0731</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"><strike>S02</strike></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ613</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0735</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de sensibilisation du personnel<br>Staff awareness form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0735-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0735-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ401</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0736</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des documents qualité en vigueur<br>List of applicable quality documents</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2>1</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ406</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0737</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des enregistrements<br>List of records</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0737-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0737-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ413</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0738</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des documents applicables (Vierge)<br>List of applicable documents (blank)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0738-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0738-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ416</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0739</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de prise en compte MAJ documentaire<br>Documentary updating validation form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0739-GRP.xls')">2</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0739-GRP-en.xls')">2</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ417</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0740</td>
		<!-- COLONNE DESIGNATION   --><td>Analyse d'évolution documentaires techniques <br>Analyses of technical document evolution</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('7/D-0740-GRP.xltx')">2</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ407</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0741</td>
		<!-- COLONNE DESIGNATION   --><td>Accusé de réception<br>Acknowledgment of receipt</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0741-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0741-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0742</td>
		<!-- COLONNE DESIGNATION   --><td>Moyens de communication<br>Communication means</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('7/D-0741-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0743</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche Projet M & CP <br>M & PC Project Form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0743/D-0743_D-0738-GRP.xls')">TBD</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0744</td>
		<!-- COLONNE DESIGNATION   --><td>Récapitulatif des Projets M & CP<br>M & PC Projects Summary</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('7/D-0744-GRP.xls')">TBD</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S01</td>
	</tr>

<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_8">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_8')">
				<font style="color:white;">CHAP 8 : Réalisation des activités opérationnelles / Operation</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ742</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0801</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche identification client<br>Customer identification form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0801-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0801-FR-en.doc')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('8/D-0801-CH.docx')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0801-PH.docx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR/EN  --><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0801-CA.xlsx')">1</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0801-US.doc')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ745</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0802</td>
		<!-- COLONNE DESIGNATION   --><td>Analyse de Risques (D-0738)<br>Risks analysis (D-0738)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier2('https://extranet.aaa-aero.com/Qualite/DQ/7/DQ745_DQ413-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ409</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0803</td>
		<!-- COLONNE DESIGNATION   --><td>Gestion de la configuration<br>Configuration management</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0803-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0803-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ744</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0804</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de modification configuration<br>Configuration modification form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0804-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0804-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0804-MA.xls')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ740</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0805</td>
		<!-- COLONNE DESIGNATION   --><td>Avis de diffusion<br>Distribution notice</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0805-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0805-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0805-MA.xls')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ735</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0806</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de devis<br>Quotation form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-CH.xls')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-PH.xls')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-CA.xls')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-CA-en.xls')">1</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0806-US.xlsx')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ743</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0807</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de suivi appel d'offres-devis acceptation par le client<br>Call for tenders-Quotations follow-up - Customer acceptance</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0807-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ705</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0808</td>
		<!-- COLONNE DESIGNATION   --><td>Revue des exigences relatives au produit<br>Review of requirements related to the product</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0808-GRP-fr.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0808-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ747</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0809</td>
		<!-- COLONNE DESIGNATION   --><td>Matrice de conformité aux exigences qualité (D-0738)<br>Quality Requirements Compliance Matrix (D-0738)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0809/D-0809_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03 & S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ703</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0810</td>
		<!-- COLONNE DESIGNATION   --><td>Confirmation commande verbale<br>Verbal order confirmation</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0810-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0810-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ702</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0811</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche d'identification fournisseur<br>Supplier identification form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0811-FR.pdf')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0811-FR-en.doc')">1</a></td>
		<!-- COLONNE GERMANY EN/DE --><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0811-DE.DOC')">1</a></td>
		<!-- COLONNE CHINA EN      --><td align="center">TBD</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0811-PH.docx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0811-CA.doc')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0811-CA-en.doc')">1</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0811-US.doc')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ709</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0812</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des fournisseurs<br>List of suppliers</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0812/D-0812-GRP.xlsx')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0812/D-0812-GRP-en.xlsx')">1</a></td>
		<!-- COLONNE FRANCE FR/EN  --><td align="center" colspan=2>en cours de modif.</td>
		<!-- COLONNE GERMANY EN/DE --><td align="center" colspan=2>TBD</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('8/D-0812/D-0812-CH.xlsx')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0812/D-0812-PH.pdf')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR/EN  --><td align="center" colspan=2>TBD</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0812/D-0812-US.pdf')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ741</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0813</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des couples article ou ébauche<br>fournisseur<br>List of items or drafts<br>supplier couples</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0813-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0813-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ748</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0814</td>
		<!-- COLONNE DESIGNATION   --><td>Analyse de Risques Fournisseurs<br>Suppliers risks analysis</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0814-GRP.xlsx')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0814-GRP-en.xlsx')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ710</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0815</td>
		<!-- COLONNE DESIGNATION   --><td>Spécification technique de sous-traitance<br>Subcontracting technical specification</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0815-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0815-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0816</td>
		<!-- COLONNE DESIGNATION   --><td>Cahier des charges <br>Workspecification</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0816/D-0816_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ706</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0817</td>
		<!-- COLONNE DESIGNATION   --><td>Demande d'achats<br>Purchase request</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0817_D-0738-FR.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('8/D-0817-DE.xls')">1</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('8/D-0817-CH.xls')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0817-PH.xls')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0817-MA.xls')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0817-CA.xls')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0817-US.xls')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ707</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0818</td>
		<!-- COLONNE DESIGNATION   --><td>Bon de commande ou avenant personnel intérimaire<br>Temporary staff purchase order or amendment</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0818-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center"><a href="javascript:OuvrirFichier('8/D-0818-DE.xls')">1</a></td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0818-CA.xlsx')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ708</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0819</td>
		<!-- COLONNE DESIGNATION   --><td>Bon de commande<br>Purchase order</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-DE.xls')">2</a></td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-CH.xlsx')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-PH.xlsx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-CA.xlsm')">2</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-CA-en.xlsm')">2</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0819-USA.xls')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ724</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0820</td>
		<!-- COLONNE DESIGNATION   --><td>Suivi Qualité Fournisseurs<br>Supplier quality follow-up</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0820-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0820-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ725</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0821</td>
		<!-- COLONNE DESIGNATION   --><td>Bilan Semestriel du suivi des Fournisseurs<br>Bi-annual review of supplier follow-up</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0821-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0821-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ726</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0822</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des produits<br>List of products</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0822-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0822-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ730</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0823</td>
		<!-- COLONNE DESIGNATION   --><td>Demande documentaire ou déclaration de manquants<br>Documentary request or missing documents statement</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0823-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0823-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ731</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0824</td>
		<!-- COLONNE DESIGNATION   --><td>Matériel prêté par le client<br>Equipment loaned by the customer</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0824-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0824-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ812</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0825</td>
		<!-- COLONNE DESIGNATION   --><td>Bon sortie pièce<br>Part withdrawal order</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0825-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0825-FR-en.xls')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0825-MA.xls')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ727</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0826</td>
		<!-- COLONNE DESIGNATION   --><td>Mise en œuvre des processus de production<br>Implementation of production processes</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0826-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0826-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ715</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0827</td>
		<!-- COLONNE DESIGNATION   --><td>Mode opératoire<br>Operating mode</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0827-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0827-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ714</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0828</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche d’instructions (GPAO HELIOS)<br>Instructions form (HELIOS)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0828-FR.xls')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0828-MA.xls')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ746</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0829</td>
		<!-- COLONNE DESIGNATION   --><td>Prévention et gestion des corps étrangers (D-0738)<br>Foreign Object Prevention and management (D-0738)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier2('https://extranet.aaa-aero.com/Qualite/DQ/7/DQ746_DQ413-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ749</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0830</td>
		<!-- COLONNE DESIGNATION   --><td>Supplier<br>service provider compliance - Rules for in-situ industrial activities</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0830/D-0830_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ818</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0831</td>
		<!-- COLONNE DESIGNATION   --><td>Gamme de contrôle<br>Control Instruction</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0831-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0831-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ819</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0832</td>
		<!-- COLONNE DESIGNATION   --><td>Mémoire de contrôle<br>Inspection Note</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0832-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0832-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ813</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0833</td>
		<!-- COLONNE DESIGNATION   --><td>Enregistrements des contrôles <br>Records of inspections</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0833-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0833-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ713</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0834</td>
		<!-- COLONNE DESIGNATION   --><td>Affectation marque de contrôle et opérateurs qualifiés<br>Assignment of inspection and qualified operators marks</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0834-GRP-fr.doc')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0834-GRP-en.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ704</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0835</td>
		<!-- COLONNE DESIGNATION   --><td>Enregistrement des marques de contrôle et opérateurs qualifiés<br>Record of inspection and qualified operators marks</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0835-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0835-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ412</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0836</td>
		<!-- COLONNE DESIGNATION   --><td>Dossier avion (enregistrements)<br>Aircraft file (records)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0836-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0836-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ808</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0837</td>
		<!-- COLONNE DESIGNATION   --><td>Bordereau de livraison<br>Déclaration de conformité (BL<br>DC)<br>Delivery note<br>Certificate of Compliance (DN<br>CC)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-FR.xlsx')">2</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-FR-en.xlsx')">2</a></td>
		<!-- COLONNE GERMANY EN/DE --><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0837/D-0837-DE.doc')">1</a></td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-CH.docx')">1</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-PH.doc')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-MA.xls')">1</a></td>
		<!-- COLONNE CANADA FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-CA.docx')">1</a></td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-CA-en.docx')">1</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('8/D-0837/D-0837-US.xlsx')">1</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ811</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0838</td>
		<!-- COLONNE DESIGNATION   --><td>Registre des signataires autorisés<br>Authorized signatories' register</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0838-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0838-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ733</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0839</td>
		<!-- COLONNE DESIGNATION   --><td>Travaux supplémentaires<br>Additional work</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0839-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0839-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ732</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0840</td>
		<!-- COLONNE DESIGNATION   --><td>Clôture de prestation<br>Closing of activity</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0840-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0840-FR-en.doc')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0841</td>
		<!-- COLONNE DESIGNATION   --><td>Répartitions des activités AAA France & Filiales <br>AAA France & Subsidiaries Activities Breakdown</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('8/D-0841-GRP.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0842</td>
		<!-- COLONNE DESIGNATION   --><td>Gestion de projet & Management Opérationnel<br>Project management & Operational Management</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0842-GRP.pdf')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0842-001-GRP.pdf')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">TRAME_PQ<br>QP_TEMPLATE</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0843</td>
		<!-- COLONNE DESIGNATION   --><td>Trame Plan Qualité (accès via pyramide)<br>Quality Plan Template (access via pyramid)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/TRAME_PLAN_QUALITE_AAA_GROUP.docx')">2</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/AAA_GROUP_QUALITY_PLAN_TEMPLATE.docx')">2</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0844</td>
		<!-- COLONNE DESIGNATION   --><td>Conditions Générales d'Achats <br>Purchasing General Conditions</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0844-FR.pdf')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0844-FR-en.pdf')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0845</td>
		<!-- COLONNE DESIGNATION   --><td>Exigences Qualité applicables aux Fournisseurs <br>Quality Requirements applicable to Suppliers</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0845-GRP.pdf')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('8/D-0845-GRP_en.pdf')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0846</td>
		<!-- COLONNE DESIGNATION   --><td>Fiche de déclaration Fournisseur <br>Supplier Statement Form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">TBD</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">TBD</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0847</td>
		<!-- COLONNE DESIGNATION   --><td>Accord de confidentialité <br>Non-Disclosure Agreement (NDA)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0847-FR.doc')">1</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center"><a href="javascript:OuvrirFichier('8/D-0847-FR-en.DOC')">1</a></td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">In progress</td>
	</tr>
	
<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_9">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_9')">
				<font style="color:white;">CHAP 9 : Evaluation des performances / Performance evaluation</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ820</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0901</td>
		<!-- COLONNE DESIGNATION   --><td>Surveillance de poste (D-0738) <br>""On workstation"" monitoring (D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0901/D-0901_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0901/D-0901_D-0738-GRP-en.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ821</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0902</td>
		<!-- COLONNE DESIGNATION   --><td>Surveillance de procédé au poste (D-0738)<br>""On work station"" process monitoring (D-0738)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0902/D-0902_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0902/D-0902_D-0738-GRP-en.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ822</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0903</td>
		<!-- COLONNE DESIGNATION   --><td>Trame SQCDPF<br>SQCDPT Template</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0903 - FR.pptx')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0903 - EN.pptx')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ814</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0904</td>
		<!-- COLONNE DESIGNATION   --><td>Questionnaire Satisfaction client<br>Customer satisfaction questionnaire</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0904-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0904-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('9/D-0904-PH.xlsx')">1</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">R03</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ810</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0905</td>
		<!-- COLONNE DESIGNATION   --><td>Liste des auditeurs internes (renseignée)<br>List of internal auditors (completed)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0905-GRP.pdf')">1</a></td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ803</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0906</td>
		<!-- COLONNE DESIGNATION   --><td>Planning audit interne (Vierge)<br>Internal audit planning (blank)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier2('https://extranet.aaa-aero.com/Qualite/DQ/8/DQ803-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ803</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0906</td>
		<!-- COLONNE DESIGNATION   --><td>Planning audit interne renseigné<br>Completed internal audit planning</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier2('https://extranet.aaa-aero.com/Qualite/DQ/8/DQ803-GRP-RENSEIGNE.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ815</td>
		<!-- COLONNE REFERENCE     --><td align="center"><strike>D-0907</strike></td>
		<!-- COLONNE DESIGNATION   --><td><strike>Guide d’audit interne Internal audit guide</strike></td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2>Cancelled & replaced<br>by D-0912 and D-0913</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"><strike>M02</strike></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ806</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0908</td>
		<!-- COLONNE DESIGNATION   --><td>Rapport d'audit Qualité Interne (D-0738)<br>Internal quality audit report (D-0738)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('9/D904/D904_D413-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ804</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0909</td>
		<!-- COLONNE DESIGNATION   --><td>Rapport d'audit Qualité fournisseur (D-0738)<br>Supplier quality audit report (D-0738)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('9/D903/D903_D413-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">S03</td>
	</tr>
	<?php
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsablePlateforme)))
	{
	?>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ501</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0910</td>
		<!-- COLONNE DESIGNATION   --><td>Revue de Direction (template)<br>Management review</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('9/D-0910-GRP-fr.doc')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<?php
	}
	?>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0910</td>
		<!-- COLONNE DESIGNATION   --><td>Revue de Direction renseigné<br> Completed Management review</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2>X</td>
		<!-- COLONNE FRANCE FR     --><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/FR/D-0910_D-0738-FR.xls')">X</a></td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/DE/D-0910_D-0738-DE.xls')">X</a></td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/CH/D-0910_D-0738-CH.xls')">X</a></td>
		<!-- COLONNE PHILIPPINES EN--><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/PH/D-0910_D-0738-PH.xls')">X</a></td>
		<!-- COLONNE ASM MAROC FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/MA/D-0910_D-0738-MA.xls')">X</a></td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/CA/D-0910_D-0738-CA.xls')">X</a></td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center"><a href="javascript:OuvrirFichier('9/D-0910/US/D-0910_D-0738-US.xls')">X</a></td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M01</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0911</td>
		<!-- COLONNE DESIGNATION   --><td>Planning Audit Fournisseurs (Vierge) (Plannings renseignés disponibles via l'Extranet)<br> Supplier Audit Planning (blank)(Supplier Audit Planning filled-out accesible via Extranet)</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('9/D-0911-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">TBD</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0912</td>
		<!-- COLONNE DESIGNATION   --><td>Programme d'Audit Interne<br> Internal Audit Agenda</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('9/D-0912-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">TBD</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0913</td>
		<!-- COLONNE DESIGNATION   --><td>Programme d'Audit Fournisseur<br> Supplier Audit Agenda</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('9/D-0913-GRP.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">TBD</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0914</td>
		<!-- COLONNE DESIGNATION   --><td>Bilan Qualité Hebdomadaire (BQH) sous Extranet<br> Weekly Quality Report via Extranet</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">TBD</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-0915</td>
		<!-- COLONNE DESIGNATION   --><td>Suivi des Audits Fournisseurs (Vierge)(Suivis renseignés disponibles via l'Extranet)<br> Suppliers Audit Follow-up (Follow-up filled-out accessible via Extranet)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('9/D-0915-GRP.xlsx')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">TBD</td>
	</tr>
	
<!-- ############################################################################################################################################################# -->

	<tr name="CHAPITRE_10">
		<td>&nbsp;</td>
		<td colspan=16 bgcolor="#1f36b4">
			<a href="javascript:onclick=Voir_TR('CHAPITRE_10')">
				<font style="color:white;">CHAP 10 : Amélioration / Improvment</font>
			</a>
		</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ802</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-1001</td>
		<!-- COLONNE DESIGNATION   --><td>Feuille de retouches<br>Touch-up form</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('10/D-1001-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('10/D-1001-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ801</td>
		<!-- COLONNE REFERENCE     --><td align="center"><strike>D-1002</strike></td>
		<!-- COLONNE DESIGNATION   --><td><strike>Fiche de non-conformité<br>Nonconformity form</strike></td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2>Cancelled & replaced<br>by D-1006</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"><strike>M02</strike></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ816</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-1003</td>
		<!-- COLONNE DESIGNATION   --><td>Récapitulatif des PV de Contrôle (FNC)<br>Summary of inspections reports (NCF)</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('10/D-1003-GRP.xls')">1</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('10/D-1003-GRP-en.xls')">1</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">DQ805</td>
		<!-- COLONNE REFERENCE     --><td align="center"><strike>D-1004</strike></td>
		<!-- COLONNE DESIGNATION   --><td><strike>Demande d'action<br>Action request</strike></td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2>Cancelled & replaced<br>by D-1006</td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center"><strike>M02</strike></td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">NA</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-1005</td>
		<!-- COLONNE DESIGNATION   --><td>Trame Flash Qualité<br>Quality Flash Template</td>
		<!-- COLONNE AAA GROUP FR  --><td align="center"><a href="javascript:OuvrirFichier('10/D-1005-GRP.pptx')">2</a></td>
		<!-- COLONNE AAA GROUP EN  --><td align="center"><a href="javascript:OuvrirFichier('10/D-1005-GRP-en.pptx')">2</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>
	<tr>
		<!-- COLONNE REF ANCIEN DQ --><td align="center" bgcolor="#AAAAAA">N/A</td>
		<!-- COLONNE REFERENCE     --><td align="center">D-1006</td>
		<!-- COLONNE DESIGNATION   --><td>Maîtrise des Non-Conformités<br>Non-Conformities Management</td>
		<!-- COLONNE AAA GROUP FREN--><td align="center" colspan=2><a href="javascript:OuvrirFichier('10/D-1006/D-1006_D-0738-GRP.xls')">X</a></td>
		<!-- COLONNE FRANCE FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE FRANCE EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY EN    --><td align="center">&nbsp;</td>
		<!-- COLONNE GERMANY DE    --><td align="center">&nbsp;</td>
		<!-- COLONNE CHINA EN      --><td align="center">&nbsp;</td>
		<!-- COLONNE PHILIPPINES EN--><td align="center">&nbsp;</td>
		<!-- COLONNE ASM MAROC FR  --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA FR     --><td align="center">&nbsp;</td>
		<!-- COLONNE CANADA EN     --><td align="center">&nbsp;</td>
		<!-- COLONNE ETATS-UNIS EN --><td align="center">&nbsp;</td>
		<!-- COLONNE /             --><td align="center">&nbsp;</td>
		<!-- COLONNE PROCESSUS     --><td align="center">M02</td>
	</tr>

	<tr name="BAS_DE_PAGE">
		<td colspan=2 align="center">D-0736 - Edition 1<br>01/09/2017</td>
		<td colspan=14" align="center">DOCUMENT DIRECTION QUALITE AAA GROUP<br>Reproduction interdite sans autorisation écrite de AAA GROUP</td>
		<td align="center">Page 1/1</td>
	</tr>
	</tbody>
</table>
</div>
<script language="javascript">
<!--
Masquer_Tout();
-->
</script>
</body>
<?php
}
?>
</html>