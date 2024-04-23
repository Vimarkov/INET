<html>
<head>
	<title>Extranet | Daher</title>
	<script language="javascript">
		function OuvrirFichier(Fic)
			{window.open("../Qualite/DQ/4/DQ413/Modules_de_formation/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
		
		function Voir_TR(Name,Nb_Lignes)
		{
			table = document.getElementById('TABLE_FORMATION').getElementsByTagName('TR')
			for (l=0;l<table.length+1;l++)
			{
				if(table[l].getAttribute("name")==Name)
				{
					for (m=l+1;m<l+Nb_Lignes+1;m++)
					{
						if(table[m].style.display == ''){table[m].style.display = 'none';}
						else{table[m].style.display = '';}
					}
				}
			}
		}
		
		function Masquer_Tout()
		{
			table = document.getElementById('TABLE_FORMATION').getElementsByTagName('TR')
			for (l=6;l<table.length+1;l++)
			{
				if(table[l].getAttribute("name")==null){table[l].style.display = 'none';}
				else{table[l].style.display = '';}
			}
		}
	</script>
</head>
<?php
	require("VerifPage.php");
	require("Connexioni.php");
	
	//Vérification des droits de lecture, écriture, administration
	$resultDroits=mysqli_query($bdd,"SELECT * FROM acces WHERE Login='".$_SESSION['Log']."' AND Page='qualite' AND Dossier1='QCM'");
	$rowDroits=mysqli_fetch_array($resultDroits);
	$QCM=false;
	if($rowDroits['Droits']=="Administrateur" || $rowDroits['Droits']=="Ecriture" || $rowDroits['Droits']=="Lecture"){$QCM=true;}
?>
<font face="Arial">
<div id="TABLE_FORMATION">
<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
	<tr>
		<td align="center"><img src="../Images/Logo_Doc_Group.png" border="0"></td>
		<td align="center" bgcolor="#DDDDDD"><font size="6" color="#330099"><b>DOCUMENTS APPLICABLES</b></font></td>
		<td align="center" colspan="2"><b>Toute entité</b></td>
	</tr>
	<tr>
		<td width="170"><b>Mis à jour : 30/10/2014</b></td>
		<td width="500" align="center"><b>par : Olivier AUDOIN</b></td>
		<td width="100" align="center"><b>Visa :</b></td>
		<td width="100" align="center"><b>OAN</b></td>
	</tr>
	<tr>
		<td colspan="4" height="5">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" bgcolor="#DDDDDD"><b>FAMILLE :</b></td>
		<td align="center"><font size="5"><b>MODULES DE FORMATION</b></font></td>
		<td align="center" bgcolor="#DDDDDD" colspan="4"><b>Origine : INTERNE</b></td>
	</tr>
	<tr>
		<td colspan="4" height="5">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" bgcolor="#DDDDDD"><b>REFERENCE</b></td>
		<td align="center" bgcolor="#DDDDDD"><b>INTITULE</b></td>
		<td align="center" bgcolor="#DDDDDD"><b>INDICE</b></td>
		<td align="center" bgcolor="#DDDDDD"><b>DATE</b></td>
	</tr>
	<!--###################################################-->
	<!--###################################################-->
	<!------------------------ COURS ------------------------>
	<!--###################################################-->
	<!--###################################################-->
	<tr name="COURS_METIER">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>COURS "METIER"</b></td>
	</tr>
	<tr name="GENE_1">
		<td align="center"><b>GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1',4)">SERRAGE AU COUPLE / TORQUE TIGHTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_FR.pdf');">SERRAGE AU COUPLE</a></td>
		<td align="center">Ed-6</td>
		<td align="center">05/04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_FR_QCM.xls');\">";}?>QCM SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/08/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_EN.pdf');">TORQUE TIGHTENING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_EN_QCM.xls');\">";}?>MCQ TORQUE TIGHTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="GENE_2">
		<td align="center"><b>GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2',4)">METALLISATION / ELECTRICAL BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR.pdf');">METALLISATION</a></td>
		<td align="center">Ed-4</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR_QCM.xls');\">";}?>QCM METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_EN.pdf');">ELECTRICAL BONDING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_EN_QCM.xls');\">";}?>MCQ ELECTRICAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="GENE_3">
		<td align="center"><b>GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3',4)">APPLICATION DES MASTICS ET PREPARATION DE SURFACE / APPLICATION OF SEALANTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_FR.pdf');">APPLICATION DES MASTICS ET PREPARATION DE SURFACE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">14/12/2012</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR_QCM.xls');\">";}?>QCM APPLICATION DES MASTICS ET PREPARATION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/08/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_EN.pdf');">APPLICATION OF SEALANTS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_EN_QCM.xls');\">";}?>MCQ APPLICATION OF SEALANTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="GENE_4">
		<td align="center"><b>GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4',4)">PROTECTION DE SURFACE / PROTECTION BY ALODINE CHROMATIZATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR.pdf');">PROTECTION DE SURFACE</a></td>
		<td align="center">Ed-6</td>
		<td align="center">03/12/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR_QCM.xls');\">";}?>QCM PROTECTION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_EN.pdf');">PROTECTION BY ALODINE CHROMATIZATION</a></td>
		<td align="center">Ed-3</td>
		<td align="center">12/07/2011</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_EN_QCM.xls');\">";}?>MCQ TOUCH-UP BY ALODINE CHROMATIZATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/07/2011</td>
	</tr>
	<tr name="GENE_5">
		<td align="center"><b>GENE 5</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_5',2)">RETOUCHES PEINTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_05_FR.pdf');">RETOUCHES PEINTURE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/08/2011</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_05_FR_QCM.xls');\">";}?>QCM RETOUCHES PEINTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/08/2011</td>
	</tr>
	<tr name="MECA_1">
		<td align="center"><b>MECA 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_1',4)">MONTAGE SYSTEME HYDRAULIQUE / HYDRAULIC SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_FR.pdf');">MONTAGE SYSTEME HYDRAULIQUE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_FR_QCM.xls');\">";}?>QCM MONTAGE SYSTEME HYDRAULIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_EN.pdf');">HYDRAULIC SYSTEM INSTALLATION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_EN_QCM.xls');\">";}?>MCQ HYDRAULIC SYSTEM INSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="MECA_2">
		<td align="center"><b>MECA 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_2',4)">MONTAGE SYSTEME CARBURANT / FUEL SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_FR.pdf');">MONTAGE SYSTEME CARBURANT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_FR_QCM.xls');\">";}?>QCM MONTAGE SYSTEME CARBURANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_EN.pdf');">FUEL SYSTEM INSTALLATION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_EN_QCM.xls');\">";}?>MCQ FUEL SYSTEM NSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="MECA_3">
		<td align="center"><b>MECA 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_3',4)">MONTAGE SYSTEME OXYGENE / OXYGEN SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_03_FR.pdf');">MONTAGE SYSTEME OXYGENE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_03_FR_QCM.xls');\">";}?>QCM MONTAGE SYSTEME OXYGENE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (EN)</b></td>
		<td>OXYGEN SYSTEM INSTALLATION</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (EN)</b></td>
		<td>OXYGEN SYSTEM INSTALLATION</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="MECA_4">
		<td align="center"><b>MECA 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_4',4)">MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID / INSTALLATION OF AIR CONDITIONING PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_FR.pdf');">MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID</a></td>
		<td align="center">Ed-3</td>
		<td align="center">21/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_FR_QCM.xls');\">";}?>QCM MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_EN.pdf');">INSTALLATION OF AIR CONDITIONING PIPES</a></td>
		<td align="center">Ed-2</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_EN_QCM.xls');\">";}?>MCQ INSTALLATION OF AIR CONDITIONING PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="ELEC_1">
		<td align="center"><b>ELEC 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_1',4)">CHEMINEMENT DES HARNAIS / HARNESSES ROUTING</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_FR.pdf');">CHEMINEMENT DES HARNAIS</a></td>
		<td align="center">Ed-8</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_FR_QCM.xls');\">";}?>QCM CHEMINEMENT DES HARNAIS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_EN.pdf');">HARNESSES ROUTING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_EN_QCM.xls');\">";}?>MCQ HARNESSES ROUTING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="ELEC_2">
		<td align="center"><b>ELEC 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_2',4)">CABLAGE / WIRING</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_02_FR.pdf');">CABLAGE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_FR_QCM.xls');\">";}?>QCM CABLAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td>WIRING</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td>MCQ WIRING</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="ELEC_7">
		<td align="center"><b>ELEC 7</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_7',4)">SERTISSAGE-INSERTION-EXTRACTION / CRIMPING</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_07_FR.pdf');">SERTISSAGE-INSERTION-EXTRACTION</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_07_FR_QCM.xls');\">";}?>QCM SERTISSAGE-INSERTION-EXTRACTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (EN)</b></td>
		<td>CRIMPING</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (EN)</b></td>
		<td>MCQ CRIMPING</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="ELEC_8">
		<td align="center"><b>ELEC 8</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_8',4)">MONTAGE DES EQUIPEMENTS ELECTRIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 8 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_08_FR.pdf');">MONTAGE DES EQUIPEMENTS ELECTRIQUES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">09/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 8 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_08_FR_QCM.xls');\">";}?>QCM MONTAGE DES EQUIPEMENTS ELECTRIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 8 (EN)</b></td>
		<td>///</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 8 (EN)</b></td>
		<td>MCQ ///</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="AJU_1">
		<td align="center"><b>AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1',4)">RIVETAGE STRUCTURAL / STRUCTURAL RIVETING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_FR.pdf');">RIVETAGE STRUCTURAL</a></td>
		<td align="center">Ed-4</td>
		<td align="center">23/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_FR_QCM.xls');\">";}?>QCM RIVETAGE STRUCTURAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_EN.pdf');">STRUCTURAL RIVETING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">12/05/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_EN_QCM.xls');\">";}?>MCQ STRUCTURAL RIVETING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2011</td>
	</tr>
	<tr name="AJU_2">
		<td align="center"><b>AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2',4)">POSE DE FIXATIONS SPECIALES / INSTALLATION OF SPECIAL FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_FR.pdf');">POSE DE FIXATIONS SPECIALES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">24/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_FR_QCM.xls');\">";}?>QCM POSE DE FIXATIONS SPECIALES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_EN.pdf');">INSTALLATION OF SPECIAL FASTENERS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_EN_QCM.xls');\">";}?>MCQ INSTALLATION OF SPECIAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2012</td>
	</tr>
	<tr name="CONT_1">
		<td align="center"><b>CONT 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CONT_1',4)">CONTROLEUR AERONAUTIQUE / AERONAUTICAL INSPECTOR</span></td>
	</tr>
	<tr>
		<td align="center"><b>CONT 1(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('CONT_01_FR.pdf');">CONTROLEUR AERONAUTIQUE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">26/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>CONT 1(FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('CONT_01_FR_QCM.xls');\">";}?>QCM CONTROLEUR AERONAUTIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>CONT 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('CONT_01_EN.pdf');">AERONAUTICAL INSPECTOR</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/08/2010</td>
	</tr>
	<tr>
		<td align="center"><b>CONT 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('CONT_01_EN_QCM.xls');\">";}?>MCQ AERONAUTICAL INSPECTOR<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="DAN_QUAL_6">
		<td align="center"><b>DAN QUAL 6</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DAN_QUAL_6',4)">FORMATION FOE-FOD / FOE-FOD AWARNESS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DAN_QUAL_06_FR.pps');">FORMATION FOE-FOD</a></td>
		<td align="center">Ed-9</td>
		<td align="center">24/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06(FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DAN_QUAL_06_FR_QCM.xls');\">";}?>QCM FORMATION FOE-FOD<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('DAN_QUAL_06_EN.pps');">FOE-FOD AWARNESS</a></td>
		<td align="center">Ed-9</td>
		<td align="center">24/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DAN_QUAL_06_EN_QCM.xls');\">";}?>MCQ FOE-FOD AWARNESS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/10/2013</td>
	</tr>
	<tr name="COMP_1">
		<td align="center"><b>COMP 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('COMP_1',2)">GENERALITE SUR LE COMPOSITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>COMP 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('COMP_01_FR.pdf');">GENERALITE SUR LE COMPOSITE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">30/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>COMP 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('COMP_01_FR_QCM.xls');\">";}?>QCM GENERALITE SUR LE COMPOSITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/09/2013</td>
	</tr>
	<tr name="COMP_2">
		<td align="center"><b>COMP 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('COMP_2',2)">GENERALITE SUR L'USINAGE DU COMPOSITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>COMP 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('COMP_02_FR.pdf');">GENERALITE SUR L'USINAGE DU COMPOSITE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">16/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>COMP 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('COMP_02_FR_QCM.xls');\">";}?>QCM GENERALITE SUR L'USINAGE DU COMPOSITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/09/2013</td>
	</tr>
	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- DASSAULT ----------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="ANNEXES DASSAULT">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>ANNEXES DASSAULT</b></td>
	</tr>
	<tr name="GENE_1_DASS">
		<td align="center"><b>GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1_DASS',2)">ANNEXE SERRAGE AU COUPLE "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_FR_ANNEXE_DA.pdf');">ANNEXE SERRAGE AU COUPLE "DASSAULT"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_FR_QCM_DA.xls');\">";}?>QCM SERRAGE AU COUPLE "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="GENE_2_DASS">
		<td align="center"><b>GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2_DASS',2)">ANNEXE METALLISATION "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR_ANNEXE_DA.pdf');">ANNEXE METALLISATION "DASSAULT"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR_QCM_DA.xls');\">";}?>QCM METALLISATION "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="GENE_3_DASS">
		<td align="center"><b>GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3_DASS',2)">ANNEXE APPLICATION DES MASTICS ET PREPARATION DE SURFACE "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_FR_ANNEXE_DA.pdf');">ANNEXE APPLICATION DES MASTICS ET PREPARATION DE SURFACE "DASSAULT"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">29/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR_QCM_DA.xls');\">";}?>QCM APPLICATION DES MASTICS ET PREPARATION DE SURFACE "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="GENE_4_DASS">
		<td align="center"><b>GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4_DASS',2)">ANNEXE PROTECTION DE SURFACE "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR_ANNEXE_DA.pdf');">ANNEXE PROTECTION DE SURFACE "DASSAULT"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">07/02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR_QCM_DA.xls');\">";}?>QCM PROTECTION DE SURFACE "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/07/2014</td>
	</tr>
	<tr name="AJU_1_DASS">
		<td align="center"><b>AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1_DASS',2)">ANNEXE RIVETAGE STRUCTURAL "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_FR_ANNEXE_DA.pdf');">ANNEXE RIVETAGE STRUCTURAL "DASSAULT"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">30/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_FR_QCM_DA.xls');\">";}?>QCM RIVETAGE STRUCTURAL "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2014</td>
	</tr>
	<tr name="AJU_2_DASS">
		<td align="center"><b>AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2_DASS',2)">ANNEXE POSE DE FIXATIONS SPECIALES "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_FR_ANNEXE_DA.pdf');">ANNEXE POSE DE FIXATIONS SPECIALES "DASSAULT"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">31/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_FR_QCM_DA.xls');\">";}?>QCM POSE DE FIXATIONS SPECIALES "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="FIL_0000_DASS">
		<td align="center"><b>FILL 0000</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('FIL_0000_DASS',2)">FILLERALU "DASSAULT"</span></td>
	</tr>
	<tr>
		<td align="center"><b>FILL 0000 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FILL_0000_FR_ANNEXE_DA.pps');">ANNEXE FILLERALU "DASSAULT"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>FILL 0000 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('FILL_0000_FR_QCM_DA.xls');\">";}?>QCM FILLERALU "DASSAULT"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<!--###################################################-->
	<!--###################################################-->
	<!----------------------- AIRBUS ------------------------>
	<!--###################################################-->
	<!--###################################################-->
	<tr name="ANNEXES AIRBUS (LEGACY PROGRAMS)">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>ANNEXES AIRBUS (LEGACY PROGRAMS)</b></td>
	</tr>
	<tr name="GENE_1_AIRB">
		<td align="center"><b>GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1_AIRB',4)">ANNEXE SERRAGE AU COUPLE "AIRBUS" / APPENDIX TORQUE TIGHTENING "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_FR_ANNEXE.pdf');">ANNEXE SERRAGE AU COUPLE "AIRBUS"</a></td>
		<td align="center">Ed-3</td>
		<td align="center">05/04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_FR_QCM_AIRBUS.xls');\">";}?>QCM SERRAGE AU COUPLE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_EN_ANNEXE.pdf');">APPENDIX TORQUE TIGHTENING "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX TORQUE TIGHTENING  "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="GENE_2_AIRB">
		<td align="center"><b>GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2_AIRB',4)">ANNEXE METALLISATION "AIRBUS" / APPENDIX ELECTRICAL BONDING "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR_ANNEXE.pdf');">ANNEXE METALLISATION "AIRBUS"</a></td>
		<td align="center">Ed-4</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR_QCM_AIRBUS.xls');\">";}?>QCM METALLISATION "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_EN_ANNEXE.pdf');">APPENDIX ELECTRICAL BONDING "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX ELECTRICAL BONDING "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="GENE_3_AIRB">
		<td align="center"><b>GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3_AIRB',4)">ANNEXE APPLICATION DES MASTICS ET PREPARATION DE SURFACE "AIRBUS" / APPENDIX APPLICATION OF SEALANTS "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_FR_ANNEXE.pdf');">ANNEXE APPLICATION DES MASTICS ET PREPARATION DE SURFACE "AIRBUS"</a></td>
		<td align="center">Ed-3</td>
		<td align="center">24/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR_QCM_AIRBUS.xls');\">";}?>QCM APPLICATION DES MASTICS ET PREPARATION DE SURFACE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_EN_ANNEXE.pdf');">APPENDIX APPLICATION OF SEALANTS "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX APPLICATION OF SEALANTS "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="GENE_4_AIRB">
		<td align="center"><b>GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4_AIRB',4)">ANNEXE PROTECTION DE SURFACE "AIRBUS" / APPENDIX PROTECTION BY ALODINE CHROMATIZATION "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR_ANNEXE.pdf');">ANNEXE PROTECTION DE SURFACE "AIRBUS"</a></td>
		<td align="center">Ed-6</td>
		<td align="center">25/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR_QCM_AIRBUS.xls');\">";}?>QCM PROTECTION DE SURFACE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_EN_ANNEXE.pdf');">APPENDIX PROTECTION BY ALODINE CHROMATIZATION "AIRBUS"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">12/07/2011</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX TOUCH-UP BY ALODINE CHROMATIZATION "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/07/2011</td>
	</tr>
	<tr name="GENE_5_AIRB">
		<td align="center"><b>GENE 5</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_5_AIRB',1)">MCQ APPENDIX APPLICATION OF PAINTS "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_05_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX APPLICATION OF PAINTS "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/05/2011</td>
	</tr>
	<tr name="MECA_1_AIRB">
		<td align="center"><b>MECA 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_1_AIRB',4)">ANNEXE MONTAGE SYSTEME HYDRAULIQUE "AIRBUS" / APPENDIX  HYDRAULIC SYSTEM NSTALLATION "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_FR_ANNEXE.pdf');">ANNEXE MONTAGE SYSTEME HYDRAULIQUE "AIRBUS"</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_FR_QCM_AIRBUS.xls');\">";}?>QCM MONTAGE SYSTEME HYDRAULIQUE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_EN_ANNEXE.pdf');">APPENDIX  HYDRAULIC SYSTEM NSTALLATION "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">02/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX  HYDRAULIC SYSTEM NSTALLATION "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="MECA_2_AIRB">
		<td align="center"><b>MECA 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_2_AIRB',4)">ANNEXE MONTAGE SYSTEME CARBURANT "AIRBUS" / APPENDIX FUEL SYSTEM NSTALLATION "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_FR_ANNEXE.pdf');">ANNEXE MONTAGE SYSTEME CARBURANT "AIRBUS"</a></td>
		<td align="center">Ed-3</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_FR_QCM_AIRBUS.xls');\">";}?>QCM MONTAGE SYSTEME CARBURANT "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_EN_ANNEXE.pdf');">APPENDIX FUEL SYSTEM NSTALLATION "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX FUEL SYSTEM NSTALLATION "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="MECA_3_AIRB">
		<td align="center"><b>MECA 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_3_AIRB',4)">ANNEXE MONTAGE SYSTEME OXYGENE "AIRBUS" / APPENDIX OXYGEN SYSTEM INSTALLATION "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_03_FR_ANNEXE.pdf');">ANNEXE MONTAGE SYSTEME OXYGENE "AIRBUS"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_03_FR_QCM_AIRBUS.xls');\">";}?>QCM MONTAGE SYSTEME OXYGENE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (EN)</b></td>
		<td>APPENDIX OXYGEN SYSTEM INSTALLATION "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (EN)</b></td>
		<td>MCQ APPENDIX OXYGEN SYSTEM INSTALLATION "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="MECA_4_AIRB">
		<td align="center"><b>MECA 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_4_AIRB',4)">ANNEXE MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID "AIRBUS" / APPENDIX INSTALLATION OF AIR CONDITIONING PIPES "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_FR_ANNEXE.pdf');">ANNEXE MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID "AIRBUS"</a></td>
		<td align="center">Ed-3</td>
		<td align="center">17/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_FR_QCM_AIRBUS.xls');\">";}?>QCM MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_EN_ANNEXE.pdf');">APPENDIX INSTALLATION OF AIR CONDITIONING PIPES "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX INSTALLATION OF AIR CONDITIONING PIPES "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="ELEC_1_AIRB">
		<td align="center"><b>ELEC 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_1_AIRB',6)">ANNEXE CHEMINEMENT DES HARNAIS "AIRBUS" / APPENDIX HARNESSES ROUTING "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_FR_ANNEXE.pdf');">ANNEXE CHEMINEMENT DES HARNAIS "AIRBUS"</a></td>
		<td align="center">Ed-7</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_FR_QCM_AIRBUS.xls');\">";}?>QCM CHEMINEMENT DES HARNAIS "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_EN_ANNEXE.pdf');">APPENDIX HARNESSES ROUTING "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX HARNESSES ROUTING "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_FR_ANNEXE_A350.pdf');">ANNEXE CHEMINEMENT DES HARNAIS "AIRBUS A350"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_FR_QCM_AIRBUS_A350.xls');\">";}?>QCM CHEMINEMENT DES HARNAIS "AIRBUS A350"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2013</td>
	</tr>
	<tr name="ELEC_2_AIRB">
		<td align="center"><b>ELEC 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_2_AIRB',4)">ANNEXE CABLAGE "AIRBUS" / APPENDIX WIRING "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_02_FR_ANNEXE.pdf');">ANNEXE CABLAGE "AIRBUS"</a></td>
		<td align="center">Ed-3</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_FR_QCM_AIRBUS.xls');\">";}?>QCM CABLAGE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td>APPENDIX WIRING "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td>MCQ APPENDIX WIRING "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="ELEC_6_AIRB">
		<td align="center"><b>ELEC 6</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_6_AIRB',4)">ANNEXE GRILLE DE CABLAGE "AIRBUS" / APPENDIX WIRING TABLE "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 6 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_06_FR_ANNEXE.pdf');">ANNEXE GRILLE DE CABLAGE "AIRBUS"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 6 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_06_FR_QCM_AIRBUS.xls');\">";}?>QCM GRILLE DE CABLAGE "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 6 (EN)</b></td>
		<td>APPENDIX WIRING TABLE "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 6 (EN)</b></td>
		<td>MCQ APPENDIX WIRING TABLE "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="ELEC_7_AIRB">
		<td align="center"><b>ELEC 7</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_7_AIRB',4)">ANNEXE SERTISSAGE-INSERTION-EXTRACTION "AIRBUS" / APPENDIX CRIMPING "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_07_FR_ANNEXE.pdf');">ANNEXE SERTISSAGE-INSERTION-EXTRACTION "AIRBUS"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_07_FR_QCM_AIRBUS.xls');\">";}?>QCM SERTISSAGE-INSERTION-EXTRACTION "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (EN)</b></td>
		<td>APPENDIX CRIMPING "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (EN)</b></td>
		<td>MCQ APPENDIX CRIMPING "AIRBUS"</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="AJU_1_AIRB">
		<td align="center"><b>AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1_AIRB',4)">ANNEXE RIVETAGE STRUCTURAL "AIRBUS" / APPENDIX STRUCTURAL RIVETING "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_FR_ANNEXE.pdf');">ANNEXE RIVETAGE STRUCTURAL "AIRBUS"</a></td>
		<td align="center">Ed-6</td>
		<td align="center">06/12/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_FR_QCM_AIRBUS.xls');\">";}?>QCM RIVETAGE STRUCTURAL "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_EN_ANNEXE.pdf');">APPENDIX STRUCTURAL RIVETING "AIRBUS"</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX STRUCTURAL RIVETING "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2011</td>
	</tr>
	<tr name="AJU_2_AIRB">
		<td align="center"><b>AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2_AIRB',4)">ANNEXE POSE DE FIXATIONS SPECIALES "AIRBUS" / APPENDIX INSTALLATION OF SPECIAL FASTENERS "AIRBUS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_FR_ANNEXE.pdf');">ANNEXE POSE DE FIXATIONS SPECIALES "AIRBUS"</a></td>
		<td align="center">Ed-6</td>
		<td align="center">24/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_EN_ANNEXE.pdf');">APPENDIX INSTALLATION OF SPECIAL FASTENERS "AIRBUS"</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_FR_QCM_AIRBUS.xls');\">";}?>QCM POSE DE FIXATIONS SPECIALES "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ APPENDIX INSTALLATION OF SPECIAL FASTENERS "AIRBUS"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2012</td>
	</tr>
	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- 80-T ---------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="80-T">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>80-T</b></td>
	</tr>
	
	<tr>
	<tr name="80-T-34-3000">
		<td align="center"><b>80-T-34-3000</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-3000',6)">Installation of Anchor Nuts</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-34-3000</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3000.pdf');">Installation of Anchor Nuts</a></td>
		<td align="center">08/1998</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-34-3000</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-34-3000 MCQ.xls');\">";}?>MCQ Installation of Anchor Nuts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/11/2014</td>
	</tr>
	
	
	<tr>
	<tr name="80-T-34-9032">
		<td align="center"><b>80-T-34-9032</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('80-T-34-9032',6)">Bonding of Non-structural Connections</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-34-9032</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9032 EN.pdf');">Bonding of Non-structural Connections</a></td>
		<td align="center">01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-34-9032</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9032 FR.pdf');">Collage d’Assemblages non Structuraux</a></td>
		<td align="center">01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-34-9032</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-34-9032 MCQ.xls');\">";}?>MCQ Bonding of Non-structural Connections<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-34-9032</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-34-9032 QCM.xls');\">";}?>QCM Collage d’Assemblages non Structuraux<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/11/2014</td>
	</tr>
	
	<tr>	
	<tr name="80-T-35-5002&9120">
		<td align="center"><b>80-T-35-5002&9120</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5002&9120',6)">Coating with Two-component Primer, EP-based / Coating with Paints and Varnishes</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-5002</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5002.pdf');">Coating with Two-component Primer, EP-based</a></td>
		<td align="center">05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9120</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9120.pdf');">Coating with Paints and Varnishes</a></td>
		<td align="center">en attente version anglaise 08/2014</td>
	</tr>
		<tr>
		<td align="center"><b>80-T-35-5002&9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-35-5002&9120 MCQ.xls');\">";}?>Coating with Paints and Varnishes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>	
	
	<tr>	
	<tr name="80-T-35-9021">
		<td align="center"><b>80-T-35-9021</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9021',6)">Preservation of Cut Edges</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9021</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9021.pdf');">Preservation of Cut Edges</a></td>
		<td align="center">01/1998</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9021</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-35-9021 MCQ.xls');\">";}?>Preservation of Cut Edges<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>	
	
	
	<tr>	
	<tr name="80-T-35-9023">
		<td align="center"><b>80-T-35-9023</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9023',6)">Preservation of Rivet Rows</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9023</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9023.pdf');">Preservation of Rivet Rows</a></td>
		<td align="center">04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9023</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-35-9023 MCQ.xls');\">";}?>Preservation of Rivet Rows<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>
	
	
	<tr>	
	<tr name="80-T-35-9124">
		<td align="center"><b>80-T-35-9124</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9124',6)">Repair of Paint Coatings on Metallic and Non–Metallic Surfaces</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9124</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9124.pdf');">Repair of Paint Coatings on Metallic and Non–Metallic Surfaces</a></td>
		<td align="center">03/1999</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-35-9124</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-35-9124 MCQ.xls');\">";}?>Repair of Paint Coatings on Metallic and Non–Metallic Surfaces<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/11/2014</td>
	</tr>	
	
	
	<tr>	
	<tr name="80-T-39-0331">
		<td align="center"><b>80-T-39-0331</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0331',6)">Production, Installation and Rework of/on Insulation Blankets</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-39-0331</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0331.pdf');">Production, Installation and Rework of/on Insulation Blankets</a></td>
		<td align="center">08/2011</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-39-0331</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-39-0331 MCQ.xls');\">";}?>Production, Installation and Rework of/on Insulation Blankets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>
	
	
	<tr>
	<tr name="80-T-40-3218">
		<td align="center"><b>80-T-40-3218</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3218',6)">Stripping of Electrical Cables</span></td>
	</tr>
	<tr>
		<td align="center"><b>80-T-40-3218</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3218.pdf');">Stripping of Electrical Cables</a></td>
		<td align="center">08/2007</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-40-3218</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-40-3218 MCQ.xls');\">";}?>MCQ Stripping of Electrical Cables<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>80-T-40-3218</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-40-3218 QCM.xls');\">";}?>QCM Dénudage de câbles électriques<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>
	
	
	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIPI/AIPS ---------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIPI/AIPS/AITM">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIPI / AIPS / AITM</b></td>
	</tr>
	<tr name="AIPI_01-02-003">
		<td align="center"><b>AIPI_01-02-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-003',6)">PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-003.pdf');">PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-003.pdf');">PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING</a></td>
		<td align="center">Ed-7</td>
		<td align="center">06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-003.xls');\">";}?>QCM PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-003_QI</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-003_QI.xls');\">";}?>QCM PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING (QI)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-003_QI</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-003_QI_EN.xls');\">";}?>MCQ PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING (QI)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-003_BC</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-003_BC_EN.xls');\">";}?>MCQ PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING (QI)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="AIPI_01-02-005">
		<td align="center"><b>AIPI_01-02-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-005',6)">PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-005.pdf');">PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-005.pdf');">PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING</a></td>
		<td align="center">Ed-9</td>
		<td align="center">01/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-005_QI</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI-AIPS_ 01-02-005_QI_EN.xls');\">";}?>MCQ PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING (QI)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-005_BC</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI-AIPS_ 01-02-005_BC_EN.xls');\">";}?>MCQ PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING (BC)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/07/2014</td>
	</tr>
	<tr name="AIPI_01-02-006">
		<td align="center"><b>AIPI_01-02-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-006',4)">INSTALLATION OF LOCKBOLTS PULL TYPE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-006.pdf');">INSTALLATION OF LOCKBOLTS PULL TYPE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-006.pdf');">INSTALLATION OF LOCKBOLTS PULL TYPE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-006.xls');\">";}?>QCM INSTALLATION OF LOCKBOLTS PULL TYPE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-006_EN.xls');\">";}?>MCQ INSTALLATION OF LOCKBOLTS PULL TYPE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2014</td>
	</tr>
	<tr name="AIPI_01-02-008">
		<td align="center"><b>AIPI_01-02-008</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-02-008',4)">TIGHTENING TORQUES FOR STRUCTURAL FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-008</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-008.pdf');">TIGHTENING TORQUES FOR STRUCTURAL FASTENERS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-008</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-008.pdf');">TIGHTENING TORQUES FOR STRUCTURAL FASTENERS</a></td>
		<td align="center">Ed-7</td>
		<td align="center">10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-008_EN.xls');\">";}?>MCQ TIGHTENING TORQUES FOR STRUCTURAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/12/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-008_FR.xls');\">";}?>QCM COUPLES DE SERRAGE POUR FIXATIONS STRUCTURALES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/12/2014</td>
	</tr>
	<tr name="AIPI_01-02-013">
		<td align="center"><b>AIPI_01-02-013</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-013',4)">INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-013</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-013.pdf');">INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"</a></td>
		<td align="center">Ed-4</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-013</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-013.pdf');">INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"</a></td>
		<td align="center">Ed-3</td>
		<td align="center">01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-013</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-013.xls');\">";}?>QCM INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-013</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-013_EN.xls');\">";}?>MCQ INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2014</td>
	</tr>
	<tr name="AIPI_01-02-015">
		<td align="center"><b>AIPI_01-02-015</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-015',4)">INSTALLATION OF BLIND BOLTS THREADED TYPE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-015.pdf');">INSTALLATION OF BLIND BOLTS THREADED TYPE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-015.pdf');">INSTALLATION OF BLIND BOLTS THREADED TYPE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-015.xls');\">";}?>QCM INSTALLATION OF BLIND BOLTS THREADED TYPE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-015_EN.xls');\">";}?>MCQ INSTALLATION OF BLIND BOLTS THREADED TYPE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/03/2014</td>
	</tr>
	<tr name="AIPI_01-02-016">
		<td align="center"><b>AIPI_01-02-016</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-016',3)">INSTALLATION OF RIVETLESS NUTPLATES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-016</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-016.pdf');">INSTALLATION OF RIVETLESS NUTPLATES</a></td>
		<td align="center">A3</td>
		<td align="center">04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-016</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-016.pdf');">INSTALLATION OF RIVETLESS NUTPLATES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-016</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-016_EN.xls');\">";}?>QCM INSTALLATION OF RIVETLESS NUTPLATES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/05/2014</td>
	</tr>
	<tr name="AIPI_01-02-017">
		<td align="center"><b>AIPI_01-02-017</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-017',4)">GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-017</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-017.pdf');">GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-017</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-017.pdf');">GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-017</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-017.xls');\">";}?>QCM GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/08/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-017</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-017_EN.xls');\">";}?>MCQ GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/08/2014</td>
	</tr>
	<tr name="AIPI_01-02-022">
		<td align="center"><b>AIPI_01-02-022</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('AIPI_01-02-022',5)">INSTALLING THREADED CYLINDRICAL FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-02-022</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-022.pdf');">INSTALLING THREADED CYLINDRICAL FASTENERS</a></td>
		<td align="center">Ed-A02</td>
		<td align="center">10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-02-022</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-022.pdf');">INSTALLING THREADED CYLINDRICAL FASTENERS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-022_FR');\">";}?>!!OBSOLETE!! QCM INSTALLING THREADED CYLINDRICAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-02-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-02-022_EN.xls');\">";}?>MCQ INSTALLING THREADED CYLINDRICAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/01/2015</td>
	</tr>
	<tr>
		<td align="center"><b>FLASH_METHODE_01-02-022</b></td>
		<td><a href="javascript:OuvrirFichier('FM-004_AIPI-01-02-022_06012015.pptx');">FLASH METHODE N°4</a></td>
		<td align="center"></td>
		<td align="center">01/2015</td>
	</tr>
	<tr>
	<tr name="AIPI_01-03-002">
		<td align="center"><b>AIPI_01-03-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-03-002',3)">MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-03-002</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-002.pdf');">MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-03-002</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-002.pdf');">MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-03-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-002_EN.xls');\">";}?>QCM MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/05/2014</td>
	</tr>
	<tr name="AIPI_01-03-003">
		<td align="center"><b>AIPI_01-03-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-03-003',3)">INSTALLATION OF INSERTS IN NON-METALLICS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-03-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-003.pdf');">INSTALLATION OF INSERTS IN NON-METALLICS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-03-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-003.pdf');">INSTALLATION OF INSERTS IN NON-METALLICS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-03-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-003_EN.xls');\">";}?>QCM INSTALLATION OF INSERTS IN NON-METALLICS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2014</td>
	</tr>
	<tr name="AIPI_01-03-004">
		<td align="center"><b>AIPI_01-03-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-03-004',4)">INSTALLATION OF HELICOIL THREADED INSERT</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-03-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-004.pdf');">INSTALLATION OF HELICOIL THREADED INSERT</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-03-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-004.pdf');">INSTALLATION OF HELICOIL THREADED INSERT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">03/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-03-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-004_EN.xls');\">";}?>MCQ INSTALLATION OF HELICOIL THREADED INSERT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-03-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-004_FR.xls');\">";}?>QCM INSTALLATION D'INSERTS FILETES HELICOIDAUX<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2014</td>
	</tr>
	<tr name="AIPI_01-03-005">
		<td align="center"><b>AIPI_01-03-005</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-03-005',4)">INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_01-03-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-005.pdf');">INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)</a></td>
		<td align="center">Ed-02</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_01-03-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-005.pdf');">INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)</a></td>
		<td align="center">Ed-4</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-03-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-005_EN.xls');\">";}?>MCQ INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_01-03-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-005_FR.xls');\">";}?>QCM INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2014</td>
	</tr>
	<tr name="AIPI_02-05-001">
		<td align="center"><b>AIPI_02-05-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_02-05-001',4)">CHEMICAL CONVERSION COATING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_02-05-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-05-001.pdf');">CHEMICAL CONVERSION COATING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_02-05-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-05-001.pdf');">CHEMICAL CONVERSION COATING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_02-05-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_02-05-001.xls');\">";}?>QCM CHEMICAL CONVERSION COATING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_02-05-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_02-05-001_EN.xls');\">";}?>MCQ CHEMICAL CONVERSION COATING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/02/2014</td>
	</tr>
	<tr name="AIPI_03-01-012">
		<td align="center"><b>AIPI_03-01-012</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-01-012',4)">EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-01-012</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-01-012.pdf');">EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-01-012</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-01-012.pdf');">EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-01-012</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-01-012.xls');\">";}?>QCM EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-01-012</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-01-012_EN.xls');\">";}?>MCQ EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/04/2014</td>
	</tr>
	<tr name="AIPI_03-03-001">
		<td align="center"><b>AIPI_03-03-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-03-001',4)">REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-03-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-001.pdf');">REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-03-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-03-001.pdf');">REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-03-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-03-001.xls');\">";}?>MCQ REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-03-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-03-001FR.xls');\">";}?>QCM REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/10/2014</td>
	</tr>
	<tr name="AIPI_03-06-007">
		<td align="center"><b>AIPI_03-06-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-007',4)">EXTERNAL SWAGED TUBE FITTINGS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-06-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-007.pdf');">EXTERNAL SWAGED TUBE FITTINGS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-06-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-007.pdf');">EXTERNAL SWAGED TUBE FITTINGS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-007.xls');\">";}?>QCM EXTERNAL SWAGED TUBE FITTINGS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-007_EN.xls');\">";}?>MCQ EXTERNAL SWAGED TUBE FITTINGS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/04/2014</td>
	</tr>
	<tr name="AIPI_03-06-008">
		<td align="center"><b>AIPI_03-06-008</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-008',4)">INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-06-008</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-008.pdf');">INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">02/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-06-008</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-008.pdf');">INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">02/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-008.xls');\">";}?>QCM INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-008_EN.xls');\">";}?>MCQ INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/10/2014</td>
	</tr>
	<tr name="AIPI_03-06-009">
		<td align="center"><b>AIPI_03-06-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-009',4)">SHIM FOR ASSEMBLY</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-06-009</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-009.pdf');">SHIM FOR ASSEMBLY</a></td>
		<td align="center">Ed-2</td>
		<td align="center">07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-06-009</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-009.pdf');">SHIM FOR ASSEMBLY</a></td>
		<td align="center">Ed-5</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-009.xls');\">";}?>QCM SHIM FOR ASSEMBLY<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-009_EN.xls');\">";}?>QCM SHIM FOR ASSEMBLY<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/04/2014</td>
	</tr>
	<tr name="AIPI_03-06-010">
		<td align="center"><b>AIPI_03-06-010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-010',10)">INSTALLATION OF OXYGEN PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-06-010</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-010.pdf');">INSTALLATION OF OXYGEN PIPES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-06-010</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-010.pdf');">INSTALLATION OF OXYGEN PIPES</a></td>
		<td align="center">Ed-3</td>
		<td align="center">01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-010.xls');\">";}?>QCM INSTALLATION OF OXYGEN PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-010_EN.xls');\">";}?>QCM INSTALLATION OF OXYGEN PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/10/2014</td>
	</tr>
	<tr name="AIPI_03-06-015">
		<td align="center"><b>AIPI_03-06-015</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-06-015',4)">TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-06-015</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-015.pdf');">TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">06/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-06-015</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-015.pdf');">TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">12/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-015_EN.xls');\">";}?>MCQ TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-06-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-06-015_FR.xls');\">";}?> QCM Serrage au couple pour connexions hydraulique sur A380 et A350<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/02/2014</td>
	</tr>
	<tr name="AIPI_03-07-004">
		<td align="center"><b>AIPI_03-07-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-07-004',4)">AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-07-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-07-004.pdf');">AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-07-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-07-004.pdf');">AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET</a></td>
		<td align="center">Ed-6</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-07-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-07-004.xls');\">";}?>QCM Critères d'acceptation matelas<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-07-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-07-004_EN.xls');\">";}?>MCQ AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/07/2014</td>
	</tr>
	<tr name="AIPI_03-08-003">
		<td align="center"><b>AIPI_03-08-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-08-003',3)">ROWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_03-08-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-08-003.pdf');">ROWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_03-08-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-08-003.pdf');">ROWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_03-08-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_03-08-003_EN.xls');\">";}?>MCQ ROWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/06/2014</td>
	</tr>
	<tr name="AIPI_05-02-003">
		<td align="center"><b>AIPI_05-02-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-003',4)">APPLICATION OF EXTERNAL PAINT SYSTEMS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-02-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-003.pdf');">APPLICATION OF EXTERNAL PAINT SYSTEMS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-02-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-003.pdf');">APPLICATION OF EXTERNAL PAINT SYSTEMS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-02-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-02-003.xls');\">";}?>QCM APPLICATION OF EXTERNAL PAINT SYSTEMS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-02-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-02-003_EN.xls');\">";}?>MCQ APPLICATION OF EXTERNAL PAINT SYSTEMS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/02/2014</td>
	</tr>
	<tr name="AIPI_05-02-009">
		<td align="center"><b>AIPI_05-02-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-009',4)">APPLICATION OF STRUCUTRAL PAINTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-02-009</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-009.pdf');">APPLICATION OF STRUCUTRAL PAINTS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-02-009</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-009.pdf');">APPLICATION OF STRUCUTRAL PAINTS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">12/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-02-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-02-009.xls');\">";}?>QCM APPLICATION OF STRUCUTRAL PAINTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-02-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-02-009_EN.xls');\">";}?>MCQ APPLICATION OF STRUCUTRAL PAINTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/02/2014</td>
	</tr>
	<tr name="AIPI_05-02-011">
		<td align="center"><b>AIPI_05-02-011</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-011',4)">REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-02-011</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-011.pdf');">REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-02-011</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-011.pdf');">REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-02-011</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-02-011.xls');\">";}?>QCM REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-02-011</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-02-011_EN.xls');\">";}?>MCQ REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/02/2014</td>
	</tr>
	<tr name="AIPI_05-04-005">
		<td align="center"><b>AIPI_05-04-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-04-005',4)">APPLICATION OF GAP FILLER-EASY TO REMOVE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-04-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-04-005.pdf');">APPLICATION OF GAP FILLER-EASY TO REMOVE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-04-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-04-005.pdf');">APPLICATION OF GAP FILLER-EASY TO REMOVE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-04-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-04-005_EN.xls');\">";}?>MCQ APPLICATION OF GAP FILLER-EASY TO REMOVE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/08/2014</td>
	</tr>
	<tr name="AIPI_05-05-001">
		<td align="center"><b>AIPI_05-05-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-001',4)">SEALING OF AIRCRAFT STRUCTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-001.pdf');">SEALING OF AIRCRAFT STRUCTURE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-05-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-001.pdf');">SEALING OF AIRCRAFT STRUCTURE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-001.xls');\">";}?>QCM SEALING OF AIRCRAFT STRUCTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-001_EN.xls');\">";}?>MCQ SEALING OF AIRCRAFT STRUCTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/11/2014</td>
	</tr>
	<tr name="AIPI_05-05-003">
		<td align="center"><b>AIPI_05-05-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-003',4)">SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-003.pdf');">SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-05-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-003.pdf');">SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-003.xls');\">";}?>QCM SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-003_EN.xls');\">";}?>MCQ SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2014</td>
	</tr>
	<tr name="AIPI_05-05-004">
		<td align="center"><b>AIPI_05-05-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_05-05-004',4)">WET INSTALLATION OF FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-004.pdf');">WET INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-004_FR</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-004_FR.pdf');">MONTAGE HUMIDE DE FIXATIONS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-05-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-004.pdf');">WET INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-004.xls');\">";}?>MCQ WET INSTALLATION OF FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/08/2014</td>
	</tr>
	<tr name="AIPI_05-05-005">
		<td align="center"><b>AIPI_05-05-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-005',4)">PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-005.pdf');">PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">10/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-05-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-005.pdf');">PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-005_EN.xls');\">";}?>MCQ PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/11/2014</td>
	</tr>
	<tr name="AIPI_05-05-006">
		<td align="center"><b>AIPI_05-05-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-006',3)">APPLICATION OF NON HARDENING JOINTING COMPOUNDS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-006.pdf');">APPLICATION OF NON HARDENING JOINTING COMPOUNDS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">11/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-05-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-006.pdf');">APPLICATION OF NON HARDENING JOINTING COMPOUNDS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-006.xls');\">";}?>QCM APPLICATION OF NON HARDENING JOINTING COMPOUNDS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2014</td>
	</tr>
	<tr name="AIPI_05-05-008">
		<td align="center"><b>AIPI_05-05-008</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-008',4)">APPLICATION OF LOW ADHESION SEALANT</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_05-05-008</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-008.pdf');">APPLICATION OF LOW ADHESION SEALANT</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_05-05-008</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-008.pdf');">APPLICATION OF LOW ADHESION SEALANT</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_05-05-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-008_EN.xls');\">";}?>MCQ APPLICATION OF LOW ADHESION SEALANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="AIPI_06-01-004">
		<td align="center"><b>AIPI_06-01-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_06-01-004',4)">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_06-01-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-01-004.pdf');">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_06-01-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_06-01-004.pdf');">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_06-01-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_06-01-004_EN.xls');\">";}?>MCQ MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_06-01-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_06-01-004_FR.xls');\">";}?>QCM PREPARATION MECANIQUE DES SURFACES NON STRUCTURALES AVANT COLLAGE A L’AIDE D’ADHESIF<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/11/2014</td>
	</tr>
	<tr name="AIPI_07-01-006">
		<td align="center"><b>AIPI_07-01-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-006',4)">ELECTRICAL BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-01-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-006.pdf');">ELECTRICAL BONDING</a></td>
		<td align="center">Ed-5</td>
		<td align="center">08/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-01-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-006.pdf');">ELECTRICAL BONDING</a></td>
		<td align="center">Ed-6</td>
		<td align="center">07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-01-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-006.xls');\">";}?>QCM ELECTRICAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-01-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-006_EN.xls');\">";}?>MCQ ELECTRICAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/02/2014</td>
	</tr>
	<tr name="AIPI_07-01-007">
		<td align="center"><b>AIPI_07-01-007</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-01-007',4)">INSTALLATION OF SOLER SLEEVES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-01-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-007.pdf');">INSTALLATION OF SOLER SLEEVES</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">??/????</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-01-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-007.pdf');">INSTALLATION OF SOLER SLEEVES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">07/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-01-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-007_EN.xls');\">";}?>MCQ INSTALLATION OF SOLER SLEEVES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-01-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-007_FR.xls');\">";}?>QCM Installation des manchons auto-soudeurs<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/06/2014</td>
	</tr>
	<tr name="AIPI_07-01-022">
		<td align="center"><b>AIPI_07-01-022</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-022',4)">INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-01-022</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-022.pdf');">INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">08/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-01-022</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-022.pdf');">INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-01-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-022.xls');\">";}?>QCM INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-01-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-022_EN.xls');\">";}?>MCQ INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/04/2014</td>
	</tr>
	<tr name="AIPI_07-02-001">
		<td align="center"><b>AIPI_07-02-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-02-001',4)">STRIPPING OF ELECTRICAL CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-02-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-02-001.pdf');">STRIPPING OF ELECTRICAL CABLE</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-02-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-02-001.pdf');">STRIPPING OF ELECTRICAL CABLE</a></td>
		<td align="center">Ed-6</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-02-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-02-001.xls');\">";}?>QCM STRIPPING OF ELECTRICAL CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-02-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-02-001_EN.xls');\">";}?>MCQ STRIPPING OF ELECTRICAL CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/04/2014</td>
	</tr>
	<tr name="AIPI_07-03-001">
		<td align="center"><b>AIPI_07-03-001</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-03-001',4)">CRIMPING OF CONTACTS TO COPPER CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-03-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-03-001.pdf');">CRIMPING OF CONTACTS TO COPPER CABLES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-03-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-03-001.pdf');">CRIMPING OF CONTACTS TO COPPER CABLES</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-03-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-03-001_EN.xls');\">";}?>MCQ CRIMPING OF CONTACTS TO COPPER CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-03-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-03-001_FR.xls');\">";}?>QCM Sertissage de contacts sur des câbles en cuivre<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/04/2014</td>
	</tr>
	<tr name="AIPI_07-03-003">
		<td align="center"><b>AIPI_07-03-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-03-003',2)">PRINCIPLE OF USE OF MANUAL CRIMPING TOOLS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-03-003</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-03-003.pdf');">PRINCIPLE OF USE OF MANUAL CRIMPING TOOLS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">02/1996</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-03-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-03-003_EN.xls');\">";}?>MCQ PRINCIPLE OF USE OF MANUAL CRIMPING TOOLS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/05/2014</td>
	</tr>
	<tr name="AIPI_07-04-010">
		<td align="center"><b>AIPI_07-04-010</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-04-010',4)">INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-04-010_FR</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-010_FR.pdf');">INSTALLATION DE COSSES EN ALU SUR DES CABLES EN ALU</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-04-010</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-010.pdf');">INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-04-010</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-010.pdf');">INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-04-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-04-010_EN.xls');\">";}?>MCQ INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/08/2014</td>
	</tr>
	<tr name="AIPI_07-04-023">
		<td align="center"><b>AIPI_07-04-023</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-023',2)">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-04-023</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-023.pdf');">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381</a></td>
		<td align="center">Ed-4</td>
		<td align="center">10/2006</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-04-023</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-04-023_EN.xls');\">";}?>MCQ GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/04/2014</td>
	</tr>
	<tr name="AIPI_07-04-024">
		<td align="center"><b>AIPI_07-04-024</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-024',3)">CRIMPING OF CONTACTS ABS1380 AND ABS1381 ONTO 10 TO 04 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 AD SERIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-04-024</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-024.pdf');">CRIMPING OF CONTACTS ABS1380 AND ABS1381 ONTO 10 TO 04 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 AD SERIES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">09/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-04-024</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-024.pdf');">CRIMPING OF CONTACTS ABS1380 AND ABS1381 ONTO 10 TO 04 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 AD SERIES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">11/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-04-024</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-04-024_EN.xls');\">";}?>MCQ CRIMPING OF CONTACTS ABS1380 AND ABS1381 ONTO 10 TO 04 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 AD SERIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/04/2014</td>
	</tr>
	<tr name="AIPI_07-04-028">
		<td align="center"><b>AIPI_07-04-028</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-028',3)">INSTALLATION OF TWINAX CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-04-028</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-028.pdf');">INSTALLATION OF TWINAX CONTACTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-04-028</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-028.pdf');">INSTALLATION OF TWINAX CONTACTS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">11/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-04-028</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-04-028_EN.xls');\">";}?>MCQ INSTALLATION OF TWINAX CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/04/2014</td>
	</tr>
	<tr name="AIPI_07-04-031">
		<td align="center"><b>AIPI_07-04-031</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-031',3)">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-04-031</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-031.pdf');">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">12/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-04-031</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-031.pdf');">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-04-031</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-04-031_EN.xls');\">";}?>MCQ GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/04/2014</td>
	</tr>
	<tr name="AIPI_07-04-037">
		<td align="center"><b>AIPI_07-04-037</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-037',3)">MANUFACTURING OF TRIAXIAL CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-04-037</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-037.pdf');">MANUFACTURING OF TRIAXIAL CONTACTS</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-04-037</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-037.pdf');">MANUFACTURING OF TRIAXIAL CONTACTS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-04-037</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-04-037_EN.xls');\">";}?>MCQ MANUFACTURING OF TRIAXIAL CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/04/2014</td>
	</tr>
	<tr name="AIPI_07-05-038">
		<td align="center"><b>AIPI_07-05-038</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-038',4)">INSTALLATION OF COAXIAL CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-038</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-038.pdf');">INSTALLATION OF COAXIAL CONTACTS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">??/????</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-038</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-038.pdf');">INSTALLATION OF COAXIAL CONTACTS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-038</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-038_EN.xls');\">";}?>MCQ INSTALLATION OF COAXIAL CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-038</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-038_FR.xls');\">";}?>QCM Installation de contacts coaxiaux<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/04/2014</td>
	</tr>
	<tr name="AIPI_07-05-043">
		<td align="center"><b>AIPI_07-05-043</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-043',4)">INSTALLATION OF QUADRAX CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-043</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-043.pdf');">INSTALLATION OF QUADRAX CONTACTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">09/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-043</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-043.pdf');">INSTALLATION OF QUADRAX CONTACTS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-043</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-043_EN.xls');\">";}?>MCQ INSTALLATION OF QUADRAX CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-043</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-043_FR.xls');\">";}?>QCM Installation des contacts Quadrax<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/04/2014</td>
	</tr>
	<tr name="AIPI_07-05-046">
		<td align="center"><b>AIPI_07-05-046</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-05-046',4)">ASSEMBLY PROCESS OF PRESSURE SEAL ABS1378</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-046</b></td>
		<td><a href="#">ASSEMBLY PROCESS OF PRESSURE SEAL ABS1378</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-046</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-046.pdf');">ASSEMBLY PROCESS OF PRESSURE SEAL ABS1378</a></td>
		<td align="center">Ed-2</td>
		<td align="center">09/2008</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-046</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-046.xls');\">";}?>QCM ASSEMBLY PROCESS OF PRESSURE SEAL ABS1378<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-046</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-046_EN.xls');\">";}?>MCQ ASSEMBLY PROCESS OF PRESSURE SEAL ABS1378<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/04/2014</td>
	</tr>
	<tr name="AIPI_07-05-047">
		<td align="center"><b>AIPI_07-05-047</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-047',4)">USE OF METALLIC CLAMPING STRIP ASNE0805</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-047</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-047.pdf');">USE OF METALLIC CLAMPING STRIP ASNE0805</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-047</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-047.pdf');">USE OF METALLIC CLAMPING STRIP ASNE0805</a></td>
		<td align="center">Ed-2</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-047</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-047_EN.xls');\">";}?>MCQ USE OF METALLIC CLAMPING STRIP ASNE0805<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-047</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-047_FR.xls');\">";}?>QCM Utilisation des colliers métalliques ASNE0805<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/06/2014</td>
	</tr>
	<tr name="AIPI_07-05-062">
		<td align="center"><b>AIPI_07-05-062</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-05-062',3)">ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-062</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-062.pdf');">ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571</a></td>
		<td align="center">Ed-1</td>
		<td align="center">09/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-062</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-062.pdf');">ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571</a></td>
		<td align="center">Ed-3</td>
		<td align="center">12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-062</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-062_EN.xls');\">";}?>MCQ ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/06/2014</td>
	</tr>
	<tr name="AIPI_07-05-076">
		<td align="center"><b>AIPI_07-05-076</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-076',4)">INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-076</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-076.pdf');">INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-076</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-076.pdf');">INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM</a></td>
		<td align="center">Ed-1</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-076</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-076_EN.xls');\">";}?>MCQ INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-076</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-076_FR.xls');\">";}?>QCM Installation de coupleurs Mil Bus 1553 pour systèmes de commande de vol<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/06/2014</td>
	</tr>
	<tr name="AIPI_07-05-079">
		<td align="center"><b>AIPI_07-05-079</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-079',4)">ASSEMBLY OF MODULAR CONNECTORS FAMILY</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-05-079</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-079.pdf');">ASSEMBLY OF MODULAR CONNECTORS FAMILY</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-05-079</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-079.pdf');">ASSEMBLY OF MODULAR CONNECTORS FAMILY</a></td>
		<td align="center">Ed-1</td>
		<td align="center">01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-079</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-079_EN.xls');\">";}?>MCQ ASSEMBLY OF MODULAR CONNECTORS FAMILY<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-05-079</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-05-079_FR.xls');\">";}?>QCM Montage des connecteurs modulaires<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/06/2014</td>
	</tr>
	<tr name="AIPI_07-07-002">
		<td align="center"><b>AIPI_07-07-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-07-002',3)">FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-07-002</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-07-002.pdf');">FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">12/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-07-002</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-07-002.pdf');">FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES</a></td>
		<td align="center">Ed-9</td>
		<td align="center">11/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-07-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-07-002_EN.xls');\">";}?>MCQ FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/06/2014</td>
	</tr>
	<tr name="AIPI_07-07-005">
		<td align="center"><b>AIPI_07-07-005</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('AIPI_07-07-005',5)">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-07-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-07-005.pdf');">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">18/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-07-005</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-07-005.pdf');">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>FLASH_METHODE_N°003_07-07-005</b></td>
		<td><a href="javascript:OuvrirFichier('FM-003_AIPI -07-07-005-03112014.pptx');">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-07-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-07-005_EN.xls');\">";}?>MCQ INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-07-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-07-005_FR.xls');\">";}?>QCM Mise en oeuvre des gaines textiles blindées EN4674-003 et EN4674-004<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/11/2014</td>
	</tr>
	<tr name="AIPI_07-11-001">
		<td align="center"><b>AIPI_07-11-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-001',3)">MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-11-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-001.pdf');">MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-11-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-001.pdf');">MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-11-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-001_EN.xls');\">";}?>MCQ MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/06/2014</td>
	</tr>
	<tr name="AIPI_07-11-002">
		<td align="center"><b>AIPI_07-11-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-002',2)">TERMINATION OF ABS0929-003 AND ABS0929-004 SINGLEWAY OPTICAL CONNECTOR ONTO ABSO963-003LF OPTICAL CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-11-002</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-002.pdf');">TERMINATION OF ABS0929-003 AND ABS0929-004 SINGLEWAY OPTICAL CONNECTOR ONTO ABSO963-003LF OPTICAL CABLE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">02/2005</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-11-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-002_EN.xls');\">";}?>MCQ TERMINATION OF ABS0929-003 AND ABS0929-004 SINGLEWAY OPTICAL CONNECTOR ONTO ABSO963-003LF OPTICAL CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/11/2014</td>
	</tr>
	<tr name="AIPI_07-11-004">
		<td align="center"><b>AIPI_07-11-004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-004',3)">ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-11-004</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-004.pdf');">ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">12/2006</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-11-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-004_EN.xls');\">";}?>MCQ ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-11-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-004_FR.xls');\">";}?>QCM Installation des contacts optiques ABS1906 sur les câbles optiques ABS0963 de type LF<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/04/2014</td>
	</tr>
	<tr name="AIPI_07-11-006">
		<td align="center"><b>AIPI_07-11-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-006',3)">FIBRE OPTIC TECHNOLOGY – CLEANING METHODS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-11-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-006.pdf');">FIBRE OPTIC TECHNOLOGY – CLEANING METHODS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">03/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-11-006</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-006.pdf');">FIBRE OPTIC TECHNOLOGY – CLEANING METHODS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">01/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-11-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-006_EN.xls');\">";}?>MCQ FIBRE OPTIC TECHNOLOGY – CLEANING METHODS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/06/2014</td>
	</tr>
	<tr name="AIPI_07-11-007">
		<td align="center"><b>AIPI_07-11-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-007',3)">FIBRE OPTIC INSTALLATIONS – FAULT DIAGNOSIS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_07-11-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-007.pdf');">FIBRE OPTIC INSTALLATIONS – FAULT DIAGNOSIS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_07-11-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-007.pdf');">FIBRE OPTIC INSTALLATIONS – FAULT DIAGNOSIS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_07-11-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-007_EN.xls');\">";}?>MCQ FIBRE OPTIC INSTALLATIONS – FAULT DIAGNOSIS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/04/2014</td>
	</tr>
	<tr name="AIPI_09-01-007">
		<td align="center"><b>AIPI_09-01-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_09-01-007',3)">CLEANING OF AIRCRAFT WINDOWS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_09-01-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_09-01-007.pdf');">CLEANING OF AIRCRAFT WINDOWS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_09-01-007</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_09-01-007.pdf');">CLEANING OF AIRCRAFT WINDOWS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_09-01-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_09-01-007_EN.xls');\">";}?>MCQ CLEANING OF AIRCRAFT WINDOWS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/05/2014</td>
	</tr>
	<tr name="AIPI_09-04-001">
		<td align="center"><b>AIPI_09-04-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_09-04-001',4)">SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_09-04-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_09-04-001.pdf');">SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPS_09-04-001</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_09-04-001.pdf');">SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_09-04-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_09-04-001.xls');\">";}?>QCM SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AIPI_AIPS_09-04-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_09-04-001_EN.xls');\">";}?>MCQ SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/04/2014</td>
	</tr>
	<tr name="AITM_6-3004">
		<td align="center"><b>AITM_6-3004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-3004',3)">VISUAL INSPECTION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-3004</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-3004.pdf');">VISUAL INSPECTION</a></td>
		<td align="center">Ed-6</td>
		<td align="center">02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-3004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM_6-3004.xls');\">";}?>QCM VISUAL INSPECTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-3004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM_6-3004_EN.xls');\">";}?>MCQ VISUAL INSPECTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/02/2014</td>
	</tr>
	<tr name="AITM_6-3005">
		<td align="center"><b>AITM_6-3005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-3005',3)">VISUAL INSPECTION OF GLASS FIBRE COMPOSITES BY TRANSMITTED LIGHT</span></td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-3005</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-3005.pdf');">VISUAL INSPECTION OF GLASS FIBRE COMPOSITES BY TRANSMITTED LIGHT</a></td>
		<td align="center">Ed-2</td>
		<td align="center">09/2008</td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-3005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM_6-3005_EN.xls');\">";}?>MCQ VISUAL INSPECTION OF GLASS FIBRE COMPOSITES BY TRANSMITTED LIGHT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-3005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM 6-3005_FR.xls');\">";}?>QCM Inspection visuelle des composites en fibres de verre par transmission de lumière<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/05/2014</td>
	</tr>
	<tr name="AITM_6-5003">
		<td align="center"><b>AITM_6-5003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-5003',3)">TAP TEST</span></td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-5003</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-5003.pdf');">TAP TEST</a></td>
		<td align="center">Ed-4</td>
		<td align="center">01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-5003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM_6-5003_EN.xls');\">";}?>MCQ TAP TEST<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AITM_6-5003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM 6-5003_FR.xls');\">";}?>QCM Test sonore<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/05/2014</td>
	</tr>
</table>

<table>
	<tr height="20"><td colspan="3"></td></tr>
	<tr><td colspan="3">_________________________________________________________________________________________________________________</td></tr>
	<tr>
		<td width="170" align="center">DQ 413 - Edition 1<br>27/06/2012</td>
		<td width="580" align="center">DOCUMENT QUALITE AAA GROUP<br>Reproduction interdite sans autorisation écrite de AAA GROUP</td>
		<td width="200" align="center"><br>Page 1/1</td>
	</tr>
</table>
</font>
</div>
<script language="javascript">
<!--
Masquer_Tout();
-->
</script>
</body>
</html>