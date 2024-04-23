
<!DOCTYPE html>
<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<script language="javascript">
		function OuvrirFichier(Fic)
			{window.open("../../DQ/4/DQ413/Modules_de_formation/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
		
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
	session_start();
	require_once("../../../v2/Outils/Formation/Globales_Fonctions.php");
	require("../../../v2/Outils/Connexioni.php");

	$QCM=false;
	if(DroitsPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteFormateur,$IdPosteReferentQualiteProcedesSpeciaux))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
	)
	{
		$QCM=true;
	}
?>
<font face="Calibri">
<div id="TABLE_FORMATION">
<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
	<tr>
		<td align="center"><img src="../../../v2/Images/Logos/Logo_Doc_Group.png" border="0"></td>
		<td align="center" bgcolor="#DDDDDD"><font size="6" color="#330099"><b>DOCUMENTS APPLICABLES</b></font></td>
		<td align="center" colspan="2"><b>Toute entité</b></td>
	</tr>
	<tr>
		<td width="170"><b>Mis a jour : 30/11/2023</td>
		<td width="500" align="center"><b>par : Emmanuelle HAUTEM</td>
		<td width="100" align="center"><b>Visa :</b></td>
		<td width="100" align="center"><b>EHM</b></td>
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
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b> FORMATION METIER / JOB VALIDATION</b></td>

<tr name="GENE_1">
		<td align="center"><b>FDG-TORQ / GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1',4)">SERRAGE AU COUPLE / TORQUE TIGHTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>FDG-TORQ (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FDG-TORQ.pptx');">FORMATION SERRAGE AU COUPLE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/12/2022</td>
	</tr>
	<tr>
		<td align="center"><b>FDG-TORQ (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('FDG-TORQ_MCQ.xlsx');\">";}?>QCM SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2022</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('Module GENE_01 ed 07_EN.pdf');">TRAINING TORQUE TIGHTENING</a></td>
		<td align="center">Ed-7</td>
		<td align="center">23/09/2019</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_EN_QCM.xls');\">";}?>MCQ TORQUE TIGHTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/08/2014</td>
	</tr>



	<tr name="GENE_2">
		<td align="center"><b>GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2',4)">METALLISATION / ELECTRICAL BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR.pdf');">FORMATION METALLISATION</a></td>
		<td align="center">Ed-4</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR-QC_QCM.xlsx');\">";}?>QCM METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_EN.pdf');">TRAINING ELECTRICAL BONDING</a></td>
		<td align="center">Ed-4</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_EN_QCM.xls');\">";}?>MCQ ELECTRICAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/10/2014</td>
	</tr>



	<tr name="GENE_3">
		<td align="center"><b>GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3',4)">APPLICATION DES MASTICS ET PREPARATION DE SURFACE / APPLICATION OF SEALANTS AND SURFACE PREPARATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('Module GENE_03 Ed 05.ppt');">FORMATION APPLICATION DES MASTICS ET PREPARATION DE SURFACE</a></td>
		<td align="center">Ed-5</td>
		<td align="center">07/06/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR-QC_QCM.xlsx');\">";}?>QCM APPLICATION DES MASTICS ET PREPARATION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 3 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_EN_Issue 05.ppt');">TRAINING APPLICATION OF SEALANTS AND SURFACE PREPARATION</a></td>
		<td align="center">Ed-5</td>
		<td align="center">08/01/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ GENE 3 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_EN_QCM.xls');\">";}?>MCQ APPLICATION OF SEALANTS AND SURFACE PREPARATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/08/2014</td>
	</tr>



	<tr name="GENE_4">
		<td align="center"><b>GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4',4)">PROTECTION DE SURFACE / SURFACE PROTECTION</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR.pdf');">FORMATION PROTECTION DE SURFACE</a></td>
		<td align="center">Ed-6</td>
		<td align="center">03/12/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR-QC_QCM.xlsx');\">";}?>QCM PROTECTION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_EN.pdf');">TRAINING SURFACE PROTECTION</a></td>
		<td align="center">Ed-6</td>
		<td align="center">03/12/2013</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_EN_QCM.xls');\">";}?>MCQ SURFACE PROTECTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>



	<tr name="GENE_5">
		<td align="center"><b>GENE 5</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_5',4)">RETOUCHES PEINTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_05_FR.pdf');">FORMATION RETOUCHES PEINTURE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/08/2011</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_05_EN.pdf');">TRAINING PAINT TOUCH-UP</a></td>
		<td align="center">Ed-2</td>
		<td align="center">21/06/2016</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_05_FR-QC_QCM.xlsx');\">";}?>QCM RETOUCHES PEINTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>GENE 5 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_05_EN_QCM.xls');\">";}?>MCQ PAINT TOUCH-UP<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/05/2016</td>
	</tr>



<tr name="AJU_1">
		<td align="center"><b>FDA-STRU / AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1',4)">ASSEMBLAGE STRUCTURAL / STRUCTURAL RIVETING</span></td>
	</tr>
	<tr>
		<td align="center"><b>FDA-STRU (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FDA-STRU.pptx');">FORMATION ASSEMBLAGE STRUCTURAL</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/12/2022</td>
	</tr>
	<tr>
		<td align="center"><b>FDA-STRU (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('FDA-STRU_MCQ.xlsx');\">";}?>QCM ASSEMBLAGE STRUCTURAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2022</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJU_01_Ed_6_EN.pdf');">TRAINING STRUCTURAL RIVETING</a></td>
		<td align="center">Ed-6</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJU_01_EN_MCQ_08-11-2019.xls');\">";}?>MCQ STRUCTURAL RIVETING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2019</td>
	</tr>



	<tr name="AJU_2">
		<td align="center"><b>AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2',4)">INSTALLATION OF SPECIAL FASTENERS (REPLACED BY FDA-STRU FOR AAA FRANCE)</span></td>
	</tr>
	<tr>
		<td align="center"><s>AJU 2 (FR)</s></td>
		<td><a href="javascript:OuvrirFichier('');">TRAINING REPLACED BY FDA-STRU FOR AAA FRANCE</a></td>
		<td align="center">NA</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><s>AJU 2 (FR)</s></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('');\">";}?>MCQ REPLACED BY FDA-STRU FOR AAA FRANCE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJU_02_Ed_4_EN.pdf');">TRAINING INSTALLATION OF SPECIAL FASTENERS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">13/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AJU 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJU_02_EN_MCQ-08-11-2019.xls');\">";}?>MCQ INSTALLATION OF SPECIAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2019</td>
	</tr>




<tr name="AJU TEST">
		<td align="center"><b>AJU TEST</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU TEST',4)">EPROUVETTE AJUSTEUR / FITTER SAMPLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AJU TEST (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DQ819-001-Memoire-de-controle-Eprouvette-Ajusteur.xls');">MEMOIRE DE CONTROLE EPROUVETTE AJUSTEUR</a></td>
		<td align="center">Ed-1</td>
		<td align="center">13/03/2015</td>
	</tr>
	<tr>
		<td align="center"><b>AJU TEST (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DQ715-001-MO-eprouvette-Ajusteur.docx');">MODE OPERATOIRE EPROUVETTE AJUSTEUR</a></td>
		<td align="center">Ed-1</td>
		<td align="center">13/03/2015</td>
	</tr>
<tr>
		<td align="center"><b>AJU TEST (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('DQ819-001-Memoire-de-controle-Eprouvette-Ajusteur-EN.xls');">INSPECTION NOTE FITTER SAMPLE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">13/03/2015</td>
	</tr>
	<tr>
		<td align="center"><b>AJU TEST (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('DQ715-001-MO-eprouvette-Ajusteur-EN.docx');">OPERATING MODE FITTER SAMPLE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">13/03/2015</td>
	</tr>
	

<tr name="MECA_1">
		<td align="center"><b>MECA 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_1',4)">MONTAGE SYSTEME HYDRAULIQUE / HYDRAULIC SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_FR.pdf');">FORMATION MONTAGE SYSTEME HYDRAULIQUE</a></td>
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
		<td><a href="javascript:OuvrirFichier('MECA_01_EN.pdf');">TRAINING HYDRAULIC SYSTEM INSTALLATION</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_EN_QCM.xls');\">";}?>MCQ HYDRAULIC SYSTEM INSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>


	<tr name="MECA_2">
		<td align="center"><b>MECA 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_2',4)">MONTAGE SYSTEME CARBURANT / FUEL SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_FR.pdf');">FORMATION MONTAGE SYSTEME CARBURANT</a></td>
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
		<td><a href="javascript:OuvrirFichier('MECA_02_EN.pdf');">TRAINING FUEL SYSTEM INSTALLATION</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_EN_QCM.xls');\">";}?>MCQ FUEL SYSTEM NSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>



	<tr name="MECA_3">
		<td align="center"><b>MECA 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_3',2)">MONTAGE SYSTEME OXYGENE / OXYGEN SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_03_FR.pdf');">FORMATION MONTAGE SYSTEME OXYGENE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_03_FR_QCM.xls');\">";}?>QCM MONTAGE SYSTEME OXYGENE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>



	<tr name="MECA_4">
		<td align="center"><b>MECA 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_4',4)">MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID / INSTALLATION OF AIR CONDITIONING PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_FR.pdf');">FORMATION MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID</a></td>
		<td align="center">Ed-3</td>
		<td align="center">21/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_FR_QCM.XLS');\">";}?>QCM MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">31/08/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_EN.pdf');">TRAINING INSTALLATION OF AIR CONDITIONING PIPES</a></td>
		<td align="center">Ed-3</td>
		<td align="center">21/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MECA 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_EN_QCM.XLS');\">";}?>MCQ INSTALLATION OF AIR CONDITIONING PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/07/2021</td>
	</tr>


<tr name="ELEC_1">
		<td align="center"><b>ELEC 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_1',4)">CHEMINEMENT DES HARNAIS / HARNESSES ROUTING</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_FR.pdf');">FORMATION CHEMINEMENT DES HARNAIS</a></td>
		<td align="center">Ed-8</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_FR-QC_QCM.xlsx');\">";}?>QCM CHEMINEMENT DES HARNAIS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_EN.pdf');">TRAINING HARNESSES ROUTING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_EN_QCM.xls');\">";}?>MCQ HARNESSES ROUTING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/10/2013</td>
	</tr>



	<tr name="ELEC_2">
		<td align="center"><b>ELEC 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_2',4)">CABLAGE / WIRING</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_02_FR.pdf');">FORMATION CABLAGE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_FR-QC_QCM.xlsx');\">";}?>QCM CABLAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_02_EN.pdf');">TRAINING WIRING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_EN_QCM0.xls');\">";}?>MCQ WIRING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_EN_QCM_AIRBUS_Completed.xls');\">";}?>MCQ WIRING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/06/2017</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_EN_QCM.xls');\">";}?>MCQ WIRING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2013</td>
	</tr>



	<tr name="ELEC_7">
		<td align="center"><b>ELEC 7</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_7',4)">SERTISSAGE-INSERTION-EXTRACTION / CRIMPING</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_07_FR.pdf');">FORMATION SERTISSAGE-INSERTION-EXTRACTION</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_07_FR-QC_QCM.xlsx');\">";}?>QCM SERTISSAGE-INSERTION-EXTRACTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 7 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_07_EN.pdf');">TRAINING CRIMPING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/07/2013</td>
	</tr>

	</tr>
		<td align="center"><b>ELEC 7 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_07_EN_QCM.xls');\">";}?>MCQ CRIMPING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2013</td>
	</tr>



	<tr name="ELEC_8">
		<td align="center"><b>ELEC 8</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_8',4)">MONTAGE DES EQUIPEMENTS ELECTRIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 8 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_08_FR.pdf');">FORMATION MONTAGE DES EQUIPEMENTS ELECTRIQUES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">09/04/2014</td>
	</tr>
	<tr>
		<td align="center"><b>ELEC 8 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_08_FR-QC_QCM.xlsx');\">";}?>QCM MONTAGE DES EQUIPEMENTS ELECTRIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/11/2019</td>
	</tr>
<tr>
		<td align="center"><b>ELEC 8 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-ELEC_08_EN.xls');\">";}?>MCQ ELECTRICAL ASSEMBLIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/04/2017</td>
	</tr>


<tr name="CONT_1">
		<td align="center"><b>CONT 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CONT_1',4)">CONTROLEUR AERONAUTIQUE / AERONAUTICAL INSPECTOR</span></td>
	</tr>
	<tr>
		<td align="center"><b>CONT 1(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('CONT_01_FR.pdf');">FORMATION CONTROLEUR AERONAUTIQUE</a></td>
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
		<td><a href="javascript:OuvrirFichier('CONT_01_EN.pdf');">TRAINING AERONAUTICAL INSPECTOR</a></td>
		<td align="center">Ed-2</td>
		<td align="center">26/06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>CONT 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('CONT_01_EN_MCQ.xls');\">";}?>MCQ AERONAUTICAL INSPECTOR<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/10/2014</td>
	</tr>


<tr name="COMPO_1">
		<td align="center"><b>COMPO 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('COMPO_1',2)">GENERALITE COMPOSITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>COMPO 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('COMP_01_FR.pdf');">FORMATION GENERALITE COMPOSITE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">30/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>COMPO 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('COMP_01_FR_QCM.xls');\">";}?>QCM GENERALITE COMPOSITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/06/2015</td>
	</tr>



<tr name="COMPO_2">
		<td align="center"><b>COMPO 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('COMPO_2',2)">GENERALITE USINAGE COMPOSITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>COMPO 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('COMP_02_FR.pdf');">FORMATION GENERALITE USINAGE COMPOSITE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">16/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>COMPO 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('COMP_02_FR_QCM.xls');\">";}?>QCM GENERALITE USINAGE COMPOSITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/09/2013</td>
	</tr>




	<tr name="TTH ALU">
		<td align="center"><b>TTH ALU</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('TTH ALU',2)">MATIERE ET TRAITEMENT THERMIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>TTH ALU</b></td>
		<td><a href="javascript:OuvrirFichier('Matiere_&_TTH.pdf');">FORMATION MATIERE ET TRAITEMENT THERMIQUES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">15/05/2015</td>
	</tr>
	<tr>
		<td align="center"><b>TTH ALU</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('Matiere_&_TTH_QCM.xls');\">";}?>QCM MATIERE ET TRAITEMENT THERMIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/07/2015</td>
	</tr>



	
	
	</tr>
	
	
	
	<!--###################################################-->
	<!--###################################################-->
	<!------------------------ AUTRES COMPETENCES AAA ------------------------>
	<!--###################################################-->
	<!--###################################################-->
	<tr name="COURS_METIER">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AUTRES COMPETENCES AAA / OTHER AAA COMPETENCIES</b></td>
	</tr>


	<tr name="DAN_QUAL_6">
		<td align="center"><b>DAN QUAL 6</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DAN_QUAL_6',4)">FOE-FOD</span></td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DAN_QUAL_06_FR.pps');">FORMATION FOE-FOD</a></td>
		<td align="center">Ed-17</td>
		<td align="center">07/10/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06(FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DAN_QUAL_06_FR_QCM.xlsx');\">";}?>QCM FOE-FOD<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('DAN_QUAL_06_EN.pps');">TRAINING FOE-FOD</a></td>
		<td align="center">Ed-17</td>
		<td align="center">07/10/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DAN_QUAL_06 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DAN_QUAL_06_EN_QCM.xls');\">";}?>MCQ FOE-FOD<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/09/2017</td>
	</tr>




	<tr name="5S">
		<td align="center"><b>5S</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('5S',4)">5S</span></td>
	</tr>
	<tr>
		<td align="center"><b>5S (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('Formation 5S - FR.ppt');">FORMATION 5S</a></td>
		<td align="center">Ed-2</td>
		<td align="center">23/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>5S(FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('5S_FR_QCM.xls');\">";}?>QCM 5S<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/01/2017</td>
	</tr>
	<tr>
		<td align="center"><b>5S (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('Formation 5S - EN.ppt');">TRAINING 5S</a></td>
		<td align="center">Ed-2</td>
		<td align="center">23/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>5S (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('5S_EN_MCQ.xls');\">";}?>MCQ 5S<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/01/2017</td>
	</tr>




<tr name="TUTORAT">
		<td align="center"><b> AAA_TUTO</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('TUTORAT',4)">TUTORAT / TUTORING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AAA_TUTO (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_TUTO.pptx');">FORMATION TUTORAT</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/05/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AAA_TUTO (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCMAAA_TUTO.xls');\">";}?>QCM TUTORAT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>AAA_TUTO (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_TUTO-en.pptx');">TRAINING TUTORING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/05/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AAA_TUTO (EN)</b></td>
				<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQAAA_TUTO-en.xls');\">";}?> MCQ TUTORING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/09/2017</td>
	</tr>

	


  	
<tr name="SENSI_FH">
		<td align="center"><b>FH AAA 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SENSI_FH',2)">FACTEURS HUMAINS <FONT COLOR="#F76234">(NON RECONNUE PART 145)</FONT></A> <a href="javascript:onclick=Voir_TR('SENSI_FH',2)">/HUMAN FACTORS <FONT COLOR="#F76234">(NOT VALID FOR PART 145)</A></span></td>
	</tr>

	<tr>
		<td align="center"><b>FH_AAA_01 (FR) </b></td>
		<td><a href="javascript:OuvrirFichier('FH_AAA_01.pps');">SENSIBILISATION FACTEURS HUMAINS (NON RECONNUE PART 145)</a></td>
		<td align="center">Ed-1</td>
		<td align="center">24/02/2015</td>
	</tr>
	<tr>
		<td align="center"><b>FH_AAA_01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('SENSI_FH_en.pps');">AWARENESS HUMAN FACTORS (NOT VALID FOR PART 145)</a></td>
		<td align="center">Ed-XX</td>
		<td align="center">XX/XX/XXXX</td>
</tr>

  	
<tr name="IC-01">
		<td align="center"><b>IC-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IC-01',3)">FICHE D INTERVENTION / INTERVENTION CARD</span></td>
	</tr>

	<tr>
		<td align="center"><b>DOC IC-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('Module_Fiche_d_intervention_Ed_07.ppt');">FORMATION FICHE D INTERVENTION</a></td>
		<td align="center">Ed-7</td>
		<td align="center">22/06/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IC-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('Intervention_Card_module_Issue_07.ppt');">TRAINING INTERVENTION CARD</a></td>
		<td align="center">Ed-7</td>
		<td align="center">22/06/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IC-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM _Intervention_Card_Issue_08.xlsx');\">";}?>QCM FICHE D INTERVENTION / MCQ INTERVENTION CARD<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/06/2020</td>
</tr>



<tr name="PREL-01">
		<td align="center"><b>PREL-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('PREL-01',3)"> PRELEVEMENT / CANNIBALIZATION</span></td>
	</tr>

	<tr>
		<td align="center"><s>PREL-01 FR</s></td>
		<td><a href="javascript:OuvrirFichier('PREL-01_HS.ppt');">FORMATION PRELEVEMENT-SEE ENGLISH VERSION</a></td>
		<td align="center">Ed-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>PREL-01 EN</b></td>
		<td><a href="javascript:OuvrirFichier('PREL-01_EN.pptx');">TRAINING CANNIBALIZATION</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/03/2023</td>
	</tr>
	<tr>
		<td align="center"><b>PREL-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_PREL-01.xlsx');\">";}?>MCQ CANNIBALIZATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/03/2023</td>
</tr>

<tr name="FONDA-AERO">
		<td align="center"><b>FONDA-AERO</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('FONDA-AERO',4)">FONDAMENTAUX AERONAUTIQUES / AEROSPACE BASICS</span></td>
	</tr>

	<tr>
		<td align="center"><b>FONDA-AERO FR</b></td>
		<td><a href="javascript:OuvrirFichier('FONDA-AERO-fr.pptx');">FORMATION FONDAMENTAUX AERONAUTIQUES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">06/01/2022</td>
	</tr>
	<tr>
		<td align="center"><b>QCM FONDA-AERO FR</b></td>
		<td><a href="javascript:OuvrirFichier('QCM_FONDA_AERO.xlsx');">QCM FONDAMENTAUX AERONAUTIQUES</a></td>
		<td align="center">-</td>
		<td align="center">01/12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>FONDA-AERO EN</b></td>
		<td><a href="javascript:OuvrirFichier('FONDA-AERO-en.pptx');">TRAINING AEROSPACE BASICS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">06/01/2022</td>
	</tr>

	<tr>
		<td align="center"><b>MCQ FONDA-AERO EN</b></td>
		<td><a href="javascript:OuvrirFichier('QCM_FONDA_AERO-en.xlsx');">MCQ AEROSPACE BASICS</a></td>
		<td align="center">-</td>
		<td align="center">01/12/2021</td>
	</tr>
</tr>



<tr name="MNGT-OP-01">
		<td align="center"><b>MNGT-OP-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MNGT-OP-01',3)">MANAGEMENT OPERATIONNEL / OPERATIONAL MANAGEMENT</span></td>
	</tr>

	<tr>
		<td align="center"><b>MNGT-OP-01 (fr)</b></td>
		<td><a href="javascript:OuvrirFichier('Support_formation_MNGT-OP-01.pptx');">FORMATION MANAGEMENT OPERATIONNEL</a></td>
		<td align="center">Ed-01</td>
		<td align="center">22/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MNGT-OP-01 (en)</b></td>
		<td><a href="javascript:OuvrirFichier('MNGT-OP-01-en.pptx');">TRAINING OPERATIONAL MANAGEMENT</a></td>
		<td align="center">Ed-01</td>
		<td align="center">22/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MNGT-OP-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MNGT_OP_01_Ind_01.xls');\">";}?>QCM MANAGEMENT OPERATIONNEL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/01/2018</td>
</tr>


<tr name="CABI-01">
		<td align="center"><b>CABI-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CABI-01',4)">CABINE / CABIN</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC CABI-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('CABI_01.pdf');">FORMATION CABINE</a></td>
		<td align="center">Ed-02</td>
		<td align="center">18/02/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC CABI-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('CABI_01-en.pdf');">TRAINING CABIN</a></td>
		<td align="center">Ed-02</td>
		<td align="center">18/02/2020</td>
	</tr>
	<tr>
		<td align="center"><b>QCM CABI-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_CABI-01.xls');\">";}?>QCM CABINE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/02/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ CABI-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_CABI-01-en.xlsx');\">";}?>MCQ CABIN<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/02/2020</td>
	</tr>

<tr name="AAA_AT">
		<td align="center"><b>AAA_AT</b></td>
                <td colspan="3"><a href="javascript:onclick=Voir_TR('AAA_AT',14)">ACTION TRACKER</span></td>
       </tr>

       <tr>
                <td align="center"><b>AAA_AT_01 (FR)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_01_Generalite.pdf');">GENERALITE</a></td>
                <td align="center">Ed-03</td>
                <td align="center">24/03/2022</td>
       </tr>
	<tr>
                <td align="center"><b>AAA_AT_01 (EN)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_01_Generality.pdf');">GENERALITY</a></td>
                <td align="center">Ed-03</td>
                <td align="center">24/03/2022</td>
       </tr>
       <tr>
                <td align="center"><b>AAA_AT_02 (FR)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_02_Fiche_NC.pdf');">FICHE NC</a></td>
                <td align="center">Ed-03</td>
                <td align="center">24/03/2022</td>
       </tr>
	<tr>
                <td align="center"><b>AAA_AT_02 (EN)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_02_NC.pdf');">NC FORM</a></td>
                <td align="center">Ed-03</td>
                <td align="center">24/03/2022</td>
       </tr>

       <tr>
                <td align="center"><b>AAA_AT_02</b></td>
                <td colspan="3"><a href="javascript:OuvrirFichier('AAA_AT_02_NC_Corresp_AT_templateAAA.pdf');">NC CORRESPONDENCE MATRIX AAA TEMPLATE</a></td>
                
       </tr>

       <tr>
                <td align="center"><b>AAA_AT_03 (FR)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_03_Fiche_8D.pdf');">FICHE 8D</a></td>
                <td align="center">Ed-01</td>
                <td align="center">28/06/2019</td>
      </tr>
       <tr>
                <td align="center"><b>AAA_AT_03 (EN)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_03_8D.pdf');">8D FORM</a></td>
                <td align="center">Ed-01</td>
                <td align="center">28/06/2019</td>
      </tr>

      <tr>
                <td align="center"><b>AAA_AT_03</b></td>
                <td colspan="3"><a href="javascript:OuvrirFichier('AAA_AT_03_8D_Corresp_AT_templateAAA.pdf');">8D CORRESPONDENCE MATRIX AAA TEMPLATE</a></td>
                
      </tr>

      <tr>
                <td align="center"><b>AAA_AT_04 (FR)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_04_Fiche_CR_&_PA.pdf');">FICHES COMPTE RENDU ET PLAN D ACTIONS</a></td>
                <td align="center">Ed-01</td>
                <td align="center">28/06/2019</td>
      </tr>
      <tr>
                <td align="center"><b>AAA_AT_04 (EN)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_04_MoM_&_AP.pdf');">MINUTES OF MEETING AND ACTIONS PLAN FORMS</a></td>
                <td align="center">Ed-01</td>
                <td align="center">28/06/2019</td>
      </tr>

      <tr>
                <td align="center"><b>AAA_AT_05 (FR)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_05_Fiche_AUDIT.pdf');">FICHE AUDIT</a></td>
                <td align="center">Ed-05</td>
                <td align="center">24/03/2022</td>
      </tr>
	<tr>
                <td align="center"><b>AAA_AT_05 (EN)</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_05_AUDIT.pdf');">AUDIT FORM</a></td>
                <td align="center">Ed-05</td>
                <td align="center">24/03/2022</td>
      </tr>

      <tr>
                <td align="center"><b>AAA_AT_05</b></td>
                <td colspan="3"><a href="javascript:OuvrirFichier('AAA_AT_05_AUDIT_Corresp_AT_templateAAA.pdf');">AUDIT CORRESPONDENCE MATRIX AAA TEMPLATE</a></td>
                
      </tr>
	<tr>
                <td align="center"><b>AAA_AT_06</b></td>
                <td><a href="javascript:OuvrirFichier('AAA_AT_06_ADMINISTRATION.pdf');">ADMINISTRATION</a></td>
                <td align="center">Ed-01</td>
                <td align="center">05/02/2021</td>
      </tr>    
                         
	<tr name="OPTIMU">
		<td align="center"><b>OPTIMU</b></td>
                <td colspan="3"><a href="javascript:onclick=Voir_TR('OPTIMU',1)">DELTAMU – FORMATION OPTIMU (SUPPORT CLIENT)</span></td>
       </tr>

       <tr>
                <td align="center"><b>OPTIMU</b></td>
                <td><a href="javascript:OuvrirFichier('OPTIMU.pdf');">FORMATION OPTIMU</a></td>
                <td align="center">-</td>
                <td align="center">14/06/2016</td>
       </tr>

        <!--###################################################-->
	<!--###################################################-->
	<!---------------------- DASSAULT ----------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="DASSAULT ANNEXES / APPENDICES">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>DASSAULT ANNEXES / APPENDICES</b></td>
	</tr>


	<tr name="GENE_1_DASS">
		<td align="center"><b>DA GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1_DASS',2)">DASSAULT SERRAGE AU COUPLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_FR_ANNEXE_DA.pdf');">FORMATION ANNEXE DASSAULT SERRAGE AU COUPLE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="GENE_2_DASS">
		<td align="center"><b>DA GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2_DASS',2)">DASSAULT METALLISATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR_ANNEXE_DA.pdf');">FORMATION ANNEXE DASSAULT METALLISATION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>
	<tr name="GENE_3_DASS">
		<td align="center"><b>DA GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3_DASS',2)">DASSAULT APPLICATION DES MASTICS ET PREPARATION DE SURFACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_DASS.pptx');">FORMATION ANNEXE DASSAULT APPLICATION DES MASTICS ET PREPARATION DE SURFACE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">24/06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR_QCM_DA.xlsx');\">";}?>QCM ANNEXE DASSAULT APPLICATION DES MASTICS ET PREPARATION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2022</td>
	</tr>


	<tr name="GENE_4_DASS">
		<td align="center"><b>DA GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4_DASS',2)">DASSAULT PROTECTION DE SURFACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR_Annexe DASSAULT_ITR_ Ed-06.pptx');">FORMATION ANNEXE DASSAULT PROTECTION DE SURFACE</a></td>
		<td align="center">Ed-6</td>
		<td align="center">09/08/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DA GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR_QCM_DA_Ed-06.xls');\">";}?>QCM ANNEXE DASSAULT PROTECTION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/08/2022</td>
	</tr>


	<tr name="AJU_1_DASS">
		<td align="center"><b>DA AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1_DASS',2)">DASSAULT RIVETAGE STRUCTURAL</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA AJU 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_FR_ANNEXE_DA.pdf');">FORMATION ANNEXE DASSSAULT RIVETAGE STRUCTURAL</a></td>
		<td align="center">Ed-2</td>
		<td align="center">30/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DA AJU 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT RIVETAGE STRUCTURAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2014</td>
	</tr>
	<tr name="AJU_2_DASS">
		<td align="center"><b>DA AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2_DASS',2)">DASSAULT POSE DE FIXATIONS SPECIALES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA AJU 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_FR_ANNEXE_DA.pdf');">FORMATION ANNEXE DASSAULT POSE DE FIXATIONS SPECIALES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">31/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DA AJU 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT POSE DE FIXATIONS SPECIALES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/03/2014</td>
	</tr>

<tr name="FIL_0000_DASS">
		<td align="center"><b>DA FILL 0000</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('FIL_0000_DASS',2)">DASSAULT FILLERALU</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA FILL 0000 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FILL_0000_FR_ANNEXE_DA edt 8.pptx');">FORMATION ANNEXE DASSAULT FILLERALU</a></td>
		<td align="center">Ed-8</td>
		<td align="center">03/01/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DA FILL 0000 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('FILL_0000_FR_QCM_ANNEXE_DA_Ed 8.xlsx');\">";}?>QCM ANNEXE DASSAULT FILLERALU<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/01/2022</td>
	</tr>

<tr name="PEINT_00">
		<td align="center"><b>DA PEINT_00</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('PEINT_00',2)">DASSAULT PREPARATION ET APPLICATION PEINTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA PEINT_00 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('PEINT_00_FR_ANNEXE_DA.ppt');">FORMATION ANNEXE DASSAULT PREPARATION ET APPLICATION PEINTURE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/05/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DA PEINT_00 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM DASSAULT PEINT_00.xls');\">";}?>QCM ANNEXE DASSAULT PREPARATION ET APPLICATION PEINTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/05/2020</td>
	</tr>


<tr name="EMBA_00">
		<td align="center"><b>DA EMBA_00</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('EMBA_00',2)">DASSAULT EMMANCHEMENT DES BAGUES A AZOTE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA EMBA_00 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('EMBA_00_FR_ANNEXE_DA.ppt');">FORMATION ANNEXE DASSAULT EMMANCHEMENT DES BAGUES A AZOTE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">26/04/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DA EMBA_00 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-DGQT-EMMANCHEMENT DES BAGUES A AZOTE.xlsx');\">";}?>QCM ANNEXE DASSAULT EMMANCHEMENT DES BAGUES A AZOTE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/06/2022</td>
	</tr>




<tr name="EMBR_00">
		<td align="center"><b>DA EMBR_00</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('EMBR_00',2)">DASSAULT EMBREVEMENT A FROID</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA EMBR_00 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('EMBR_0000_FR_ANNEXE_DA.ppt');">FORMATION ANNEXE DASSAULT EMBREVEMENT A FROID</a></td>
		<td align="center">Ed-1</td>
		<td align="center">16/03/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DA EMBR_00 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('EMBR_0000_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT EMBREVEMENT A FROID<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/03/2016</td>
	</tr>

<tr name="TTHR_0000">
		<td align="center"><b>DA TTHR_0000</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('TTHR_0000',2)">DASSAULT TRAITEMENT THERMIQUE DES RIVETS"</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA TTHR_0000 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('TTHR_0000_v6.ppt');">FORMATION ANNEXE DASSAULT TRAITEMENT THERMIQUE DES RIVETS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">23/09/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DA TTHR_0000 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_TTHR_FR_DA_ITR_ED_6.xls');\">";}?>QCM ANNEXE DASSAULT TRAITEMENT THERMIQUE DES RIVETS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2022</td>
	</tr>


<tr name="DIV_01_DASS">
		<td align="center"><b>DA DIV 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DIV_01_DASS',2)">DASSAULT CONDUCTIVITE ELECTRIQUE - MESURE AU SYGMATEST</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA DIV 01(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DIV 01_FR_ANNEXE_DA.pps');">FORMATION ANNEXE DASSAULT CONDUCTIVITE ELECTRIQUE - MESURE AU SYGMATEST</a></td>
		<td align="center">Ed-3</td>
		<td align="center">09/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DA DIV 01(FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DIV 01_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT CONDUCTIVITE ELECTRIQUE - MESURE AU SYGMATEST<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/01/2018</td>
	</tr>



<tr name="DA_RES_01">
		<td align="center"><b>DA RES 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DA_RES_01',2)">DASSAULT BORDURAGE EN RESINE DES PORTES ET PANNEAUX KEVLAR SUR RAFALE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DA RES 01(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('bordurage resineV2.pptx');">FORMATION ANNEXE DASSAULT - BORDURAGE EN RESINE DES PORTES ET PANNEAUX KEVLAR SUR RAFALE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">25/08/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DA RES 01(FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('KEVLAR_FR_QCM_DA.xls');\">";}?>QCM ANNEXE DASSAULT BORDURAGE EN RESINE DES PORTES ET PANNEAUX KEVLAR SUR RAFALE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/11/2022</td>
	</tr>



</tr>

	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- DP DASSAULT ----------------------->
	<!--###################################################-->
	<!--###################################################-->

    <tr name="DP DASSAULT">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>DASSAULT DP</b></td>
	</tr>

<tr name="DP BZ 105">
		<td align="center"><b>DP BZ 105</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP BZ 105',2)">MONTAGE TUYAUTERIES RIGIDES GROS DIAMETRES (CARBURANT, PRESSURISATION, CONDITIONNEMENT)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP BZ 105 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP_BZ_105.pdf');">DP MONTAGE TUYAUTERIES RIGIDES GROS DIAMETRES (CARBURANT, PRESSURISATION, CONDITIONNEMENT)</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP BZ 105</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP-105_FR_QCM.xls');\">";}?>QCM MONTAGE TUYAUTERIES RIGIDES GROS DIAMETRES (CARBURANT, PRESSURISATION, CONDITIONNEMENT)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/04/2019</td>
	</tr>


<tr name="DP BZ 106">
		<td align="center"><b>DP BZ 106</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP BZ 106',2)">MONTAGE TUYAUTERIES RIGIDES PETITS DIAMETRES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP BZ 106 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP_BZ_106.pdf');">DP MONTAGE TUYAUTERIES RIGIDES PETITS DIAMETRES</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP BZ 106</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP-106_FR_QCM.xls');\">";}?>QCM MONTAGE TUYAUTERIES RIGIDES PETITS DIAMETRES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/04/2019</td>
	</tr>




<tr name="DP 108">
		<td align="center"><b>DP 108</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP 108',2)">Fabrication Additive FDM ULTEM 9085 AEROSPACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP 108 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP108 bis.pdf');">DP Fabrication Additive FDM ULTEM 9085 AEROSPACE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP 108</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_ULTEM 9085 CG.xlsx');\">";}?>QCM Fabrication Additive FDM ULTEM 9085 AEROSPACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/02/2022</td>
	</tr>
	
	
	
	
<tr name="DP BZ 286">
		<td align="center"><b>DP BZ 286</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP BZ 286',2)">MONTAGE DES ENSEMBLES ELECTRIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP BZ 286 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP_BZ_286.pdf');">DP MONTAGE DES ENSEMBLES ELECTRIQUES</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP BZ 286</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_DP_BZ_286.xlsx');\">";}?>QCM MONTAGE DES ENSEMBLES ELECTRIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/03/2019</td>
	</tr>

<tr name="DP BZ 583">
		<td align="center"><b>DP BZ 583</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP BZ 583',2)">INSTALLATION DE CABLAGES ELECTRIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP BZ 583 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP_BZ_286.pdf');">DP INSTALLATION DE CABLAGES ELECTRIQUES</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP BZ 583</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_DP_BZ_583.xlsx');\">";}?>QCM INSTALLATION DE CABLAGES ELECTRIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/03/2019</td>
	</tr>



<tr name="DP ME 163">
		<td align="center"><b>DP ME 163</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP ME 163',2)">ECLISSAGE VOILURES FALCON PREPARATION & INJECTION PR</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP ME 163 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP-ME163.pdf');">DP ECLISSAGE VOILURES FALCON PREPARATION & INJECTION PR</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP ME 163</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-DP-ME163- Ind E.xlsx');\">";}?>QCM ECLISSAGE VOILURES FALCON PREPARATION & INJECTION PR<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/10/2022</td>
	</tr>

<tr name="DP ME 167">
		<td align="center"><b>DP ME 167</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP ME 167',2)">MONTAGE COMMANDES PAR BIELLES ET DES ELEMENTS MOBILES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP ME 167 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP-ME167.pdf');">DP MONTAGE COMMANDES PAR BIELLES ET DES ELEMENTS MOBILES</a></td>
		<td align="center">F</td>
		<td align="center">11/04/2018</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP ME 167</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP_ME_167_QCM.xlsx');\">";}?>QCM MONTAGE COMMANDES PAR BIELLES ET DES ELEMENTS MOBILES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/06/2023</td>
	</tr>

<tr name="DP ME 168">
		<td align="center"><b>DP ME 168</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP ME 168',2)">MONTAGE / REGLAGLE COMMANDES PAR CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP ME 168 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP-ME168.pdf');">DP MONTAGE / REGLAGLE COMMANDES PAR CABLES</a></td>
		<td align="center">C</td>
		<td align="center">24/10/2016</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP ME 168</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP_ME_168_QCM.xlsx');\">";}?>QCM MONTAGE / REGLAGLE COMMANDES PAR CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/06/2023</td>
	</tr>

<tr name="DP ME 169">
		<td align="center"><b>DP ME 169</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP ME 169',2)">MONTAGE MONTAGE / REGLAGLE COMMANDES SOUPLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP ME 169 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP-ME169.pdf');">DP MONTAGE / REGLAGLE COMMANDES SOUPLES</a></td>
		<td align="center">D</td>
		<td align="center">20/10/2016</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP ME 169</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP_ME_169_QCM.xlsx');\">";}?>QCM MONTAGE / REGLAGLE COMMANDES SOUPLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/06/2023</td>
	</tr>

<tr name="DP ME 219">
		<td align="center"><b>DP ME 219</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP ME 219',2)">TORQUAGE / PENDULAGE DURANT L’ECLISSAGE FALCON</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP ME 219 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP-ME219.pdf');">DP TORQUAGE / PENDULAGE DURANT L’ECLISSAGE FALCON</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP ME 219</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-DP-ME219- Ind C.xlsx');\">";}?>QCM TORQUAGE / PENDULAGE DURANT L’ECLISSAGE FALCON<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/10/2022</td>
	</tr>


<tr name="DP ME 249">
		<td align="center"><b>DP ME 249</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DP ME 249',2)">ESSAIS CIRCUITS ANEMOMETRIQUE / OXYGENE FALCON</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DP ME 249 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('DP-ME249.pdf');">DP ESSAIS CIRCUITS ANEMOMETRIQUE / OXYGENE FALCON</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>QCM DP ME 249</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-DP-ME249- Ind B.xlsx');\">";}?>QCM ESSAIS CIRCUITS ANEMOMETRIQUE / OXYGENE FALCON<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/10/2022</td>
	</tr>
	
	
	
<tr name="TUYGD-01">
		<td align="center"><b>TUYGD-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('TUYGD-01',2)"> DP ME 166 : Montage réglage des tuyauteries Gros Diamètres (via TUYGD01)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC TUYGD-01</b></td>
		<td><a href="javascript:OuvrirFichier('DP_ME_166.pptx');">DP Montage réglage des tuyauteries Gros Diamètres</a></td>
		<td align="center">Ed-2</td>
		<td align="center">11/03/2021</td>
	</tr>
	<tr>
		<td align="center"><b>QCM TUYGD-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP_ME_166_QCM.xls');\">";}?>QCM Montage réglage des tuyauteries Gros Diamètres<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-2</td>
		<td align="center">18/02/2021</td>
	</tr>



<tr name="TUYPD-01">
		<td align="center"><b>TUYPD-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('TUYPD-01',2)"> DP ME 236 : Montage réglage des tuyauteries Petits Diamètres (via TUYPD01)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC TUYPD-01</b></td>
		<td><a href="javascript:OuvrirFichier('DP_ME_236.pptx');">DP Montage réglage des tuyauteries Petits Diamètres</a></td>
		<td align="center">Ed-1</td>
		<td align="center">18/02/2021</td>
	</tr>
	<tr>
		<td align="center"><b>QCM TUYPD-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DP_ME_236_QCM.xlsx');\">";}?>QCM Montage réglage des tuyauteries Petits Diamètres<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-2</td>
		<td align="center">04/10/2023</td>
	</tr>

	<!--###################################################-->
	<!--###################################################-->
	<!----------------------- AIRBUS ------------------------>
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIRBUS BASIC QUALIFICATION">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS BASIC QUALIFICATION</b></td>
	</tr>
	<tr name="GENE_1_AIRB">
		<td align="center"><b>QBG-AI-TORQ / AI GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1_AIRB',4)">AIRBUS SERRAGE AU COUPLE / TORQUE TIGHTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>QBG-AI-TORQ (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('QBG-AI-TORQ_Serrage au couple.pptx');">FORMATION AIRBUS SERRAGE AU COUPLE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">28/04/2023</td>
	</tr>
	<tr>
		<td align="center"><b>QBG-AI-TORQ (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QBG-AI-TORQ_MCQ.xlsx');\">";}?>QCM AIRBUS SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/04/2023</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX TORQUE TIGHTENING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX TORQUE TIGHTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2014</td>
	</tr>
	<tr name="GENE_2_AIRB">
		<td align="center"><b>AI GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2_AIRB',4)">AIRBUS METALLISATION / ELECTRICAL BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS METALLISATION</a></td>
		<td align="center">Ed-5</td>
		<td align="center">11/08/2015</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX ELECTRICAL BONDING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX ELECTRICAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/07/2014</td>
	</tr>
	<tr name="GENE_3_AIRB">
		<td align="center"><b>AI GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3_AIRB',4)">AIRBUS APPLICATION DES MASTICS ET PREPARATION DE SURFACE / APPLICATION OF SEALANTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_FR_ANNEXE.ppt');">FORMATION ANNEXE AIRBUS APPLICATION DES MASTICS ET PREPARATION DE SURFACE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">10/02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS APPLICATION DES MASTICS ET PREPARATION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 3 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX APPLICATION OF SEALANTS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 3 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX APPLICATION OF SEALANTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/07/2014</td>
	</tr>
	<tr name="GENE_4_AIRB">
		<td align="center"><b>AI GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4_AIRB',4)">AIRBUS PROTECTION DE SURFACE / PROTECTION BY ALODINE CHROMATIZATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS PROTECTION DE SURFACE</a></td>
		<td align="center">Ed-7</td>
		<td align="center">21/10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS PROTECTION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX PROTECTION BY ALODINE CHROMATIZATION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">12/07/2011</td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX PROTECTION BY ALODINE CHROMATIZATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr name="GENE_5_AIRB">
		<td align="center"><b>AI GENE 5</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_5_AIRB',1)">AIRBUS APPLICATION PEINTURE / APPLICATION OF PAINTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI GENE 5 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_05_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX APPLICATION OF PAINTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/05/2011</td>
	</tr>

	<tr name="AJU_1_AIRB">
		<td align="center"><b>QBA-AI-STRU / AI AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1_AIRB',4)">AIRBUS ASSEMBLAGE STRUCTURAL (FR) / STRUCTURAL RIVETING (EN)</span></td>
	</tr>
	<tr>
		<td align="center"><b>QBA-AI-STRU (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('QBA-AI-STRU_Assemblage Structural.pptx');">FORMATION AIRBUS ASSEMBLAGE STRUCTURAL</a></td>
		<td align="center">Ed-1</td>
		<td align="center">21/07/2023</td>
	</tr>
	<tr>
		<td align="center"><b>QBA-AI-STRU (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_QBA-AI-STRU.xlsx');\">";}?>QCM AIRBUS ASSEMBLAGE STRUCTURAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/07/2023</td>
	</tr>
	<tr>
		<td align="center"><b>AI AJU 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX STRUCTURAL RIVETING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>AI AJU 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX STRUCTURAL RIVETING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2011</td>
	</tr>
	<tr name="AJU_2_AIRB">
		<td align="center"><b>AI AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2_AIRB',4)">AIRBUS POSE DE FIXATIONS SPECIALES / INSTALLATION OF SPECIAL FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><s>AI AJU 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">TRAINING REPLACED BY QBA-STRU FOR AAA FRANCE</a></td>
		<td align="center">NA</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AI AJU 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_EN_ANNEXE.pdf');">TRANING AIRBUS APPENDIX INSTALLATION OF SPECIAL FASTENERS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/03/2012</td>
	</tr>
	<tr>
		<td align="center"><s>AI AJU 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('');\">";}?>MCQ REPLACED BY QBA-STRU FOR AAA FRANCE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AI AJU 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX INSTALLATION OF SPECIAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2012</td>
	</tr>


	<tr name="MECA_1_AIRB">
		<td align="center"><b>AI MECA 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_1_AIRB',4)">AIRBUS MONTAGE SYSTEME HYDRAULIQUE / HYDRAULIC SYSTEM NSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS MONTAGE SYSTEME HYDRAULIQUE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS MONTAGE SYSTEME HYDRAULIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/03/2019</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_01_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX HYDRAULIC SYSTEM INSTALLATION</a></td>
		<td align="center">Ed-1</td>
		<td align="center">02/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX HYDRAULIC SYSTEM INSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="MECA_2_AIRB">
		<td align="center"><b>AI MECA 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_2_AIRB',4)">AIRBUS MONTAGE SYSTEME CARBURANT / FUEL SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS MONTAGE SYSTEME CARBURANT</a></td>
		<td align="center">Ed-3</td>
		<td align="center">28/03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS MONTAGE SYSTEME CARBURANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 2 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_02_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX FUEL SYSTEM INSTALLATION</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX FUEL SYSTEM INSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="MECA_3_AIRB">
		<td align="center"><b>AI MECA 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_3_AIRB',4)">AIRBUS MONTAGE SYSTEME OXYGENE / OXYGEN SYSTEM INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_03_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS MONTAGE SYSTEME OXYGENE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/09/2014</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_03_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS MONTAGE SYSTEME OXYGENE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 3 (EN)</b></td>
		<td>TRAINING AIRBUS APPENDIX OXYGEN SYSTEM INSTALLATION</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 3 (EN)</b></td>
		<td>MCQ AIRBUS APPENDIX OXYGEN SYSTEM INSTALLATION</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr name="MECA_4_AIRB">
		<td align="center"><b>AI MECA 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_4_AIRB',4)">AIRBUS MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID / INSTALLATION OF AIR CONDITIONING PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID</a></td>
		<td align="center">Ed-3</td>
		<td align="center">17/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS MONTAGE TUYAUTERIE SYSTEME CONDITIONNEMENT AIR FROID<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/01/2015</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 4 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_04_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX INSTALLATION OF AIR CONDITIONING PIPES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI MECA 4 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_04_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX INSTALLATION OF AIR CONDITIONING PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr name="ELEC_1_AIRB">
		<td align="center"><b>AI ELEC 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_1_AIRB',6)">AIRBUS CHEMINEMENT DES HARNAIS / HARNESSES ROUTING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS CHEMINEMENT DES HARNAIS</a></td>
		<td align="center">Ed-7</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS CHEMINEMENT DES HARNAIS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 1 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_EN_ANNEXE.pdf');">TRAINING AIRBUS APPENDIX HARNESSES ROUTING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">09/12/2010</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX HARNESSES ROUTING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_01_FR_ANNEXE_A350.pdf');">FORMATION ANNEXE AIRBUS CHEMINEMENT DES HARNAIS A350</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_01_FR_QCM_AIRBUS_A350.xls');\">";}?>QCM ANNEXE AIRBUS CHEMINEMENT DES HARNAIS A350<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2013</td>
	</tr>
	<tr name="ELEC_2_AIRB">
		<td align="center"><b>AI ELEC 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_2_AIRB',4)">AIRBUS CABLAGE / WIRING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_02_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS CABLAGE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS CABLAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 2 (EN)</b></td>
		<td>TRAINING AIRBUS APPENDIX WIRING</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 2 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_02_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDIX WIRING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2013</td>
	</tr>
	<tr name="ELEC_6_AIRB">
		<td align="center"><b>AI ELEC 6</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_6_AIRB',4)">AIRBUS GRILLE DE CABLAGE / WIRING TABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 6 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_06_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS GRILLE DE CABLAGE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/10/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 6 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_06_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS GRILLE DE CABLAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 6 (EN)</b></td>
		<td>TRAINING AIRBUS APPENDIX WIRING TABLE</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 6 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_06_EN_QCM_AIRBUS.xls');\">";}?>MCQ AIRBUS APPENDX WIRING TABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/05/2013</td>
	</tr>



	<tr name="ELEC_7_AIRB">
		<td align="center"><b>AI ELEC 7</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ELEC_7_AIRB',2)">AIRBUS SERTISSAGE-INSERTION-EXTRACTION / CRIMPING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 7 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('ELEC_07_FR_ANNEXE.pdf');">FORMATION ANNEXE AIRBUS SERTISSAGE-INSERTION-EXTRACTION</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/11/2013</td>
	</tr>
	<tr>
		<td align="center"><b>AI ELEC 7 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ELEC_07_FR_QCM_AIRBUS.xls');\">";}?>QCM ANNEXE AIRBUS SERTISSAGE-INSERTION-EXTRACTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2013</td>
	</tr>



	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIRBUS 80-T -------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIRBUS 80-T">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS 80-T</b></td>
	</tr>
	
<tr>
	<tr name="80-T-30-9910">
		<td align="center"><b>80-T-30-9910</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-30-9910',2)">Drilling, Reaming and Countersinking of Rivet and Screw Holes</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-30-9910 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-30-9910_2022-04.pdf');">Drilling, Reaming and Countersinking of Rivet and Screw Holes</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-30-9910</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-30-9910_Ind_04-2022.xls');\">";}?>MCQ Drilling, Reaming and Countersinking of Rivet and Screw Holes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2022</td>
	</tr>



	<tr name="80-T-31-2916">
		<td align="center"><b>80-T-31-2916</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-31-2916',2)">Reworking of Monolithic and Sandwich Components</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-31-2916 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-31-2916_2016-07_EN.pdf');">Reworking of Monolithic and Sandwich Components</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-31-2916</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80_T-31-2916_Ind_07-2016.xlsx');\">";}?>MCQ Reworking of Monolithic and Sandwich Components<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/07/2019</td>
	</tr>



	<tr name="80-T-32-2611">
		<td align="center"><b>80-T-32-2611</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-32-2611',2)">Cold working of Holes</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-32-2611 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-32-2611_2022-07.pdf');">Cold working of Holes</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-32-2611</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-32-2611_Ind_2022-07.xls');\">";}?>MCQ Cold working of Holes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/11/2022</td>
	</tr>



<tr name="80-T-34-0108">
		<td align="center"><b>80-T-34-0108</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-0108',2)">Roller Swaging of Titanium Tubes into Tube Fittings (3–Grooves)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-0108 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-0108_2000-10.pdf');">Roller Swaging of Titanium Tubes into Tube Fittings (3–Grooves)</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/2000</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-0108</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-0108_Ind_2000-10.xlsx');\">";}?>MCQ Roller Swaging of Titanium Tubes into Tube Fittings (3–Grooves)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>




<tr name="80-T-34-0109">
		<td align="center"><b>80-T-34-0109</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-0109',2)">Installation of Piping and Flexible Hoses</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-0109 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-0109_2006-05_EN.pdf');">Installation of Piping and Flexible Hoses</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-0109</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-0109_Ind_05-2006.xlsx');\">";}?>MCQ Installation of Piping and Flexible Hoses<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/07/2019</td>
	</tr>



	<tr name="80-T-34-3000">
		<td align="center"><b>80-T-34-3000</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-3000',2)">Installation of Anchor Nuts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3000 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3000.pdf');">Installation of Anchor Nuts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/1998</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-3000</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-3000_1998-08.xlsx');\">";}?>MCQ/QCM Installation of Anchor Nuts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/12/2017</td>
	</tr>



	<tr name="80-T-34-3003">
		<td align="center"><b>80-T-34-3003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-3003',2)">Installation of Helical Threaded Inserts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3003_2007-09.pdf');">Installation of Helical Threaded Inserts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-3003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-3003_Ind_2007-09.xlsx');\">";}?>MCQ/QCM Installation of Helical Threaded Inserts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>



	<tr name="80-T-34-3001">
		<td align="center"><b>80-T-34-3001</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-34-3001',2)"> Installation of fasteners and nuts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3001_2018-11_EN.pdf');"> Installation of fasteners and nuts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-3001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-3001_2018-11.xlsx');\">";}?>MCQ/QCM Installation of fasteners and nuts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/02/2019</td>
	</tr>



	<tr name="80-T-34-3016">
		<td align="center"><b>80-T-34-3016</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-34-3016',2)">Self–Locking Nuts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3016 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3016_1992-12_EN.pdf');">Self–Locking Nuts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/1992</td>
	</tr>
	<tr>
		<td align="center"><b>QCM 80-T-34-3016</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_80-T-34-3016_Ind_1992-12_with EN.xlsx');\">";}?>QCM Self–Locking Nuts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>



<tr name="80-T-34-3200">
		<td align="center"><b>80-T-34-3200</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-34-3200',2)">Pressing and Insertion of Bushes and Bearings</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3200 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3200_2018-06.pdf');">Pressing and Insertion of Bushes and Bearings</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>QCM 80-T-34-3200</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-3200_Ind_2018-06.xlsx');\">";}?>QCM Pressing and Insertion of Bushes and Bearings<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/09/2021</td>
	</tr>



<tr name="80-T-34-3050">
		<td align="center"><b>80-T-34-3050</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-3050',4)">Tightening of Screws and Nuts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3050 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3050_2022-02.pdf');">Tightening of Screws and Nuts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-3050</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-3050_2022-02.xlsx');\">";}?>MCQ/QCM Tightening of Screws and Nuts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/07/2022</td>
	</tr>
	<tr>
		<td align="center"><b>FT comparatif serrage 80T et IPDA (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FT comparatif serrage 80T et IPDA.pptx');">Tightening of Screws and Nuts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/07/2015</td>
	</tr>
	<tr>
		<td align="center"><b>FT comparison tightening 80T et IPDA (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('FT Tightening 80T and IPDA EN.pptx');">Tightening of Screws and Nuts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/09/2015</td>
	</tr>



	<tr name="80-T-34-3215">
		<td align="center"><b>80-T-34-3215</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-3215',2)">Installation of Rivetless Nutplates</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3215 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3215.pdf');">Installation of Rivetless Nutplates</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-3215</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ - 80-T-34-3215.xls');\">";}?>MCQ Installation of Rivetless Nutplates<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/11/2015</td>
	</tr>



	<tr name="80-T-34-3700">
		<td align="center"><b>80-T-34-3700</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-3700',2)">Installation of Bearings and Bushes by Heating and/or Cooling</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-3700 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-3700_2011-05.pdf');">Installation of Bearings and Bushes by Heating and/or Cooling</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-3700</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-3700 ind 2011-05.xltx.xlsx');\">";}?>MCQ Installation of Bearings and Bushes by Heating and/or Cooling<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/03/2022</td>
	</tr>



	<tr name="80-T-34-5661">
		<td align="center"><b>80-T-34-5661</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5661',2)">Installation of Spherical Plain Beargins</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5661 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5661.pdf');">Installation of Spherical Plain Beargins</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/1996</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5661</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-34-5661_MCQ.xls');\">";}?>MCQ Installation of Spherical Plain Beargins<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/03/2015</td>
	</tr>



	<tr name="80-T-34-5661">
		<td align="center"><b>80-T-34-5661</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5661',2)">Installation of Spherical Plain Beargins</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5661 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5661.pdf');">Installation of Spherical Plain Beargins</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/1996</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5661</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-34-5661_MCQ.xls');\">";}?>MCQ Installation of Spherical Plain Beargins<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/03/2015</td>
	</tr>



	<tr name="80-T-34-5669">
		<td align="center"><b>80-T-34-5669</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-34-5669',2)">Pipe Connections, “Harrison” System</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5669 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5669_2017-08.pdf');">Pipe Connections, “Harrison” System</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2017</td>
	</tr>
	<tr>
		<td align="center"><b>QCM 80-T-34-5669</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5669_Ind_2017-08.xlsx');\">";}?>QCM Pipe Connections, “Harrison” System<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/09/2021</td>
	</tr>



<tr name="80-T-34-5670">
		<td align="center"><b>80-T-34-5670</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5670',3)">Raccord de Tuyauteries Système Deutsch / Piping Deutsch System</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5670 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5670_1999-03_EN.pdf');">Raccord de Tuyauteries Système Deutsch</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM 80-T-34-5670 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_80-T-34-5670_Ind_03-1999.xlsx');\">";}?>QCM Raccord de Tuyauteries Système Deutsch<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/07/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5670 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_80-T-34-5670_Ind_03-en.xlsx');\">";}?>MCQ Piping Deutsch System <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/07/2019</td>
	</tr>


	<tr name="80-T-34-5803">
		<td align="center"><b>80-T-34-5803</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5803',2)">Wet Installation of Fasteners</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5803 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5803_2022-05.pdf');">Wet Installation of Fasteners</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5803</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-5803_Ind_2022-05.xlsx');\">";}?>MCQ Wet Installation of Fasteners<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/11/2022</td>
	</tr>



	<tr name="80-T-34-5804">
		<td align="center"><b>80-T-34-5804</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5804',2)">Treatment of Solid Aluminum Rivets Prior to Installation</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5804 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5804_2020-12.pdf');">Treatment of Solid Aluminum Rivets Prior to Installation</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5804</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5804 Ind_2020-12.xlsx');\">";}?>MCQ Treatment of Solid Aluminum Rivets Prior to Installation<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/09/2021</td>
	</tr>



	<tr name="80-T-34-5805">
		<td align="center"><b>80-T-34-5805</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5804',2)">Installation of Rivets with an Automatic Riveter</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5805 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5805_1994-01.pdf');">Installation of Rivets with an Automatic Riveter</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/1994</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5805</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5805_Ind_1994-01.xlsx');\">";}?>MCQ Installation of Rivets with an Automatic Riveter<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/09/2021</td>
	</tr>



	<tr name="80-T-34-5807">
		<td align="center"><b>80-T-34-5807</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5807',2)">Installation of Titanium-Niobium Solid Rivets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5807 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5807_2007-04_FR.pdf');">Installation of Titanium-Niobium Solid Rivets</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5807</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-5807_Ind_2007-04.xlsx');\">";}?>MCQ Installation of Titanium-Niobium Solid Rivets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/09/2018</td>
	</tr>



	<tr name="80-T-34-5811">
		<td align="center"><b>80-T-34-5811</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5811',2)">Installation of blind bolts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5811 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5811.pdf');">Installation of blind bolts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5811</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-34-5811_MCQ.xls');\">";}?>MCQ Installation of blind bolts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/10/2015</td>
	</tr>


	<tr name="80-T-34-5812">
		<td align="center"><b>80-T-34-5812</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5812',2)">Installation of Lockbolts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5812 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5812_2019-02.pdf');">Installation of Lockbolts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5812</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5812 2019-02.xlsx');\">";}?>MCQ Installation of Lockbolts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/12/2021</td>
	</tr>



	<tr name="80-T-34-5815">
		<td align="center"><b>80-T-34-5815</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5815',2)">Installation of Close-tolerance Rivets (Hi-Lok, close-tolerance bolts)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5815 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5815_2017-12_EN.pdf');">Installation of Close-tolerance Rivets (Hi-Lok, close-tolerance bolts)</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-5815</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5815_Ind_12-2017.xlsx');\">";}?>MCQ/QCM Installation of Close-tolerance Rivets (Hi-Lok, close-tolerance bolts)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/08/2019</td>
	</tr>



	<tr name="80-T-34-5817">
		<td align="center"><b>80-T-34-5817</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5817',2)">Installation of Close-tolerance Bolts (Hi-Lite, Veri-Lite)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5817 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5817_2022-04.pdf');">Installation of Close-tolerance Bolts (Hi-Lite, Veri-Lite)</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5817</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-5817_Ind_2022-04.xlsx');\">";}?>MCQ Installation of Close-tolerance Bolts (Hi-Lite, Veri-Lite)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/09/2022</td>
	</tr>



	<tr name="80-T-34-5818">
		<td align="center"><b>80-T-34-5818</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5818',2)">Installation of Avdel MBC Blind Rivets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5818 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5818_1998-09.pdf');">Installation of Avdel MBC Blind Rivets</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1998</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5818</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5818 1998-09.xlsx');\">";}?>MCQ Installation of Avdel MBC Blind Rivets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/12/2021</td>
	</tr>



	<tr name="80-T-34-5819">
		<td align="center"><b>80-T-34-5819</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5819',2)">Installation of Composi–Lok Blind Rivets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5819 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5819_1998-09.pdf');">Installation of Composi–Lok Blind Rivets</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1998</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-5819</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5819_Ind_1998-09.xlsx');\">";}?>MCQ Installation of Composi–Lok Blind Rivets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/09/2021</td>
	</tr>




	<tr name="80-T-34-5822">
		<td align="center"><b>80-T-34-5822</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5822',2)">Installation of NAS Blind Rivets and MS–/NASM– and EN Blind Bolts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5822 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5822_2003-07_EN.pdf');">Installation of NAS Blind Rivets and MS–/NASM– and EN Blind Bolts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/2003</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-5822</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5822_Ind_07-2003.xlsx');\">";}?>MCQ/QCM Installation of NAS Blind Rivets and MS–/NASM– and EN Blind Bolts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2019</td>
	</tr>




	<tr name="80-T-34-5827">
		<td align="center"><b>80-T-34-5827</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5827',2)">Installation of Cherrymax Blind Rivets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5827 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5827_1998-09.pdf');">Installation of Cherrymax Blind Rivets</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1998</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-5827</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_80-T-34-5827_Ind_09-1998.xlsx');\">";}?>MCQ/QCM Installation of Cherrymax Blind Rivets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/11/2020</td>
	</tr>
	
	


	<tr name="80-T-34-5829">
		<td align="center"><b>80-T-34-5829</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-5829',2)">Installation of Taper-Loks</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-5829 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-5829_2016-08.pdf');">Installation of Taper-Loks</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-5829</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-5829 2016-08.xlsx');\">";}?>MCQ/QCM Installation of Taper-Loks<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/12/2021</td>
	</tr>



	<tr name="80-T-34-9000">
		<td align="center"><b>80-T-34-9000</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-9000',2)">Bonding of Structures</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9000 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9000_2019-03.pdf');">Bonding of Structures</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-9000</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-9000_Ind_2019-03.xlsx');\">";}?>MCQ Bonding of Structures<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/09/2021</td>
	</tr>



	<tr name="80-T-34-9020">
		<td align="center"><b>80-T-34-9020</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('80-T-34-9020',3)">Installation of Bearings/Bushes by Bonding</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9020 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9020_1998-09_EN.pdf');">Installation of Bearings/Bushes by Bonding</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1998</td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-90220 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9020_1998-09_FR.pdf');">Pose des paliers et des douilles par collage</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1998</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-9020</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-9020_1998-09.xlsx');\">";}?>MCQ/QCM Installation of Bearings/Bushes by Bonding<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/08/2018</td>
	</tr>


	
	<tr name="80-T-34-9032">
		<td align="center"><b>80-T-34-9032</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('80-T-34-9032',3)">Bonding of Non-structural Connections</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9032 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9032_2012-01_EN.pdf');">Bonding of Non-structural Connections</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9032 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9032_2012-01_FR.pdf');">Collage d'Assemblages non Structuraux</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-34-9032</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-34-9032_2012-01.xlsx');\">";}?>MCQ/QCM Bonding of Non-structural Connections<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2018</td>
	</tr>



	<tr name="80-T-34-9033">
		<td align="center"><b>80-T-34-9033</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-9033',2)">Bonding with Adhesives on Silicone Basis</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9033 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9033_2019-09_EN.pdf');">Bonding with Adhesives on Silicone Basis</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-9033</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-9033_Ind_09-2019.xls');\">";}?>MCQ Bonding with Adhesives on Silicone Basis<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/02/2020</td>
	</tr>



	<tr name="80-T-34-9600">
		<td align="center"><b>80-T-34-9600</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('80-T-34-9600',4)">Application of sealing compounds</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9600 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9600_2021-11.pdf');">Application of sealing compounds</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-9600</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-9600_2021-11.xlsx');\">";}?>MCQ Application of sealing compounds<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">31/01/2022/td>
	</tr>
	<tr>
		<td align="center"><b>FICHE SYNTHESE (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FT comparatif MASTIC 80T et IPDA.pptx');">Fiche synth�se application des mastics</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>SYNTHESIS SHEET (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('Fiche comparaison 80T349600.pptx');">Comparison sheet application of sealing compounds</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/10/2015</td>
	</tr>	



<tr name="80-T-34-9604">
		<td align="center"><b>80-T-34-9604</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-34-9604',2)">Application of Silicone-based Sealants</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-34-9604 (EN&FR)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-34-9604.FR.pdf');">Application of Silicone-based Sealants</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/2009</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-34-9604</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-34-9604_Ind_03-2009.xls');\">";}?>MCQ Application of Silicone-based Sealants<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/12/2015</td>
	</tr>	


	<tr name="80-T-35-0060">
		<td align="center"><b>80-T-35-0060</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-0060',2)">Final Cleaning of Tubes and Components of the Oxygen System with Solvents</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-0060 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-0060_2006-08.pdf');">Final Cleaning of Tubes and Components of the Oxygen System with Solvents</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-0060</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-0060_Ind_2006-08.xlsx');\">";}?>MCQ Final Cleaning of Tubes and Components of the Oxygen System with Solvents<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/09/2021</td>
	</tr>



	<tr name="80-T-35-0130">
		<td align="center"><b>80-T-35-0130</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-0130',2)">Preteatment of non-structural joints for bonding</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-0130 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-0130_2007-10_EN.pdf');">Preteatment of non-structural joints for bonding</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-0130</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-0130_Ind_10-2007.xlsx');\">";}?>MCQ Preteatment of non-structural joints for bonding<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/07/2019</td>
	</tr>



	<tr name="80-T-35-1101">
		<td align="center"><b>80-T-35-1101</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-1101',2)">Chromating of Aluminum and Aluminum Alloy</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-1101 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-1101_2015-08.pdf');">Chromating of Aluminum and Aluminum Alloy</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-1101</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80T-35-1101_issue_201508.xlsx');\">";}?>MCQ Chromating of Aluminum and Aluminum Alloy<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/09/2018</td>
	</tr>



	<tr name="80-T-35-5000">
		<td align="center"><b>80-T-35-5000</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5000',2)">Coating with Wash Primer</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5000 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5000_2021-06.pdf');">Coating with Wash Primer</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>


	
	<tr name="80-T-35-5001">
		<td align="center"><b>80-T-35-5001</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5001',2)">Coating with Synthetic Resin Primer, Containing Zinc Chromate</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5001_1999-03.pdf');">Coating with Synthetic Resin Primer, Containing Zinc Chromate</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/1999</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>




	<tr name="80-T-35-5002">
		<td align="center"><b>80-T-35-5002</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5002',2)">Coating with Two-component Primer, EP-based / Coating with Paints and Varnishes</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5002_2021-11.pdf');">Coating with Two-component Primer, EP-based</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>	



	<tr name="80-T-35-5008">
		<td align="center"><b>80-T-35-5008</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5008',2)">Coating with Two-Component Polyurethane Primer</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5008_2003-05.pdf');">Coating with Two-Component Polyurethane Primer</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/2003</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>	




	<tr name="80-T-35-5023">
		<td align="center"><b>80-T-35-5023</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5023',2)">Coating with Two–Component EP–based Primer,Chromate–free</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5023 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5023_1999-01_EN.pdf');">Coating with Two–Component EP–based Primer,Chromate–free</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/1999</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>	



	<tr name="80-T-35-5024">
		<td align="center"><b>80-T-35-5024</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5024',2)">Coating with Two-component - Primer, PURbased,Chromate Free</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5024 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5024_2020-10.pdf');">Coating with Two-component - Primer, PURbased,Chromate Free</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>



	<tr name="80-T-35-5025">
		<td align="center"><b>80-T-35-5025</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5025',2)">Coating with Two–component Primer,Chromate–free</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5025 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5025_2002-01_EN.pdf');">Coating with Two–component Primer,Chromate–free</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2002</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>



	<tr name="80-T-35-5030">
		<td align="center"><b>80-T-35-5030</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5030',2)">Coating with Two-/Three-component Water-based Primer</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5030 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5030_2021-04.pdf');">Coating with Two-/Three-component Water-based Primer</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-5030</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-5030_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Two-/Three-component Water-based Primer<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>



    <tr name="80-T-35-5106">
		<td align="center"><b>80-T-35-5106</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5106',2)">Coating with PUR-based Top Coat</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5106 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5106_2017-07_EN.pdf');">Coating with PUR-based Top Coat</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-5106</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>	



	<tr name="80-T-35-5130">
		<td align="center"><b>80-T-35-5130</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5130',2)">Coating with Two-/Three-component Water-based Top Coat</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5130 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5130_2021-04.pdf');">Coating with Two-/Three-component Water-based Top Coat</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-5130</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-5130_Ed_2021_04.xlsx');\">";}?>MCQ Coating with Two-/Three-component Water-based Top Coat<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>	



	<tr name="80-T-35-5202">
		<td align="center"><b>80-T-35-5202</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5202',2)">Coating with Highly Elastic Paints</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5202 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5202_2017-08_EN.pdf');">Coating with Highly Elastic Paints</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>



	<tr name="80-T-35-5218">
		<td align="center"><b>80-T-35-5218</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-5218',2)">Application of Elastic Protective Coatings</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5218 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5218_2022-01.pdf');">Application of Elastic Protective Coatings</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-5218</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-35-5218_Ind_2022-04.xlsx');\">";}?>MCQ Application of Elastic Protective Coatings<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/04/2022</td>
	</tr>



	<tr name="80-T-35-5254">
		<td align="center"><b>80-T-35-5254</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5254',2)">Coating with PUR–based Varnish, Abrasion–resistant</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5254 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5254_2017-08.pdf');">Coating with PUR–based Varnish, Abrasion–resistant</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>	



	<tr name="80-T-35-5256">
		<td align="center"><b>80-T-35-5256</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-5256',2)">Coating with Conductive Paint</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5256 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5256_2019-08.pdf');">Coating with Conductive Paint</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes, General<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>	




	<tr name="80-T-35-5903">
		<td align="center"><b>80-T-35-5903</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-5903',2)">Assembly with Duroplastic Anti-corrosion Compound, Zinc Chromate Based</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5903 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5903_2016-02.pdf');">Assembly with Duroplastic Anti-corrosion Compound, Zinc Chromate Based</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-5903</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-5903_Ind_02-2016.xlsx');\">";}?>MCQ Assembly with Duroplastic Anti-corrosion Compound, Zinc Chromate Based<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/07/2019</td>
	</tr>	



	<tr name="80-T-35-5904">
		<td align="center"><b>80-T-35-5904</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-5904',2)">Assembly with Duroplastic Anti-corrosion Compound Chromate-free</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5904 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5904_2016-02_EN.pdf');">Assembly with Duroplastic Anti-corrosion Compound Chromate-free</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-5904</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-35-5904_Ind_02-2016.xlsx');\">";}?>MCQ Assembly with Duroplastic Anti-corrosion Compound Chromate-free<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/07/2019</td>
	</tr>




	<tr name="80-T-35-5905">
		<td align="center"><b>80-T-35-5905</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-5905',2)">Wet Assembly of Components with Corrosion Preventives on a Chromate Basis</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-5905 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-5905_2014-12');">Wet Assembly of Components with Corrosion Preventives on a Chromate Basis</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-5905</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-5905_Ind_2014-12.xlsx');\">";}?>MCQ Wet Assembly of Components with Corrosion Preventives on a Chromate Basis<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2021</td>
	</tr>



	
	<tr name="80-T-35-9021">
		<td align="center"><b>80-T-35-9021</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9021',2)">Preservation of Cut Edges</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9021 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9021.pdf');">Preservation of Cut Edges</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/1998</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-9021</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-35-9021 MCQ.xls');\">";}?>MCQ Preservation of Cut Edges<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>	
	
	

	<tr name="80-T-35-9023">
		<td align="center"><b>80-T-35-9023</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9023',2)">Preservation of Rivet Rows</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9023 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9023_2018-02_EN.pdf');">Preservation of Rivet Rows</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9023</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM 80-T-35-9023_Ind_2018-02.xlsx');\">";}?>MCQ/QCM Preservation of Rivet Rows<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/05/2018</td>
	</tr>	



	<tr name="80-T-35-9030">
		<td align="center"><b>80-T-35-9030</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9030',2)">Application of Fillers</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9030 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9030_2020-11.pdf');">Application of Fillers</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9030</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9030_Ind_2020-11.xlsx');\">";}?>MCQ/QCM Application of Fillers<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2021</td>
	</tr>	



	
<tr name="80-T-35-9120">
		<td align="center"><b>80-T-35-9120</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-35-9120',2)">Coating with Two-component Primer, EP-based</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9120 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9120_2021-04.pdf');">Coating with Two-component Primer, EP-based</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9120</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9120_Ed_2021_04.xlsx');\">";}?>MCQ/QCM Coating with Paints and Varnishes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2022</td>
	</tr>



	<tr name="80-T-35-9123">
		<td align="center"><b>80-T-35-9123</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9123',2)">Paint Coating in Interior Furnishing</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9123 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9123_2018-01_EN.pdf');">Paint Coating in Interior Furnishing</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9123</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9123_Ind_012018.xlsx');\">";}?>MCQ/QCM Paint Coating in Interior Furnishing<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/09/2018</td>
	</tr>




	<tr name="80-T-35-9124">
		<td align="center"><b>80-T-35-9124</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9124',2)">Repair of Paint Coatings on Metallic and Non?Metallic Surfaces</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9124 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9124.pdf');">Repair of Paint Coatings on Metallic and Non?Metallic Surfaces</a></td>
		<td align="center">10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-9124</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-35-9124 MCQ.xls');\">";}?>MCQ Repair of Paint Coatings on Metallic and Non?Metallic Surfaces<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/06/2016</td>
	</tr>	




	<tr name="80-T-35-9125">
		<td align="center"><b>80-T-35-9125</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9125',2)">Removal of Organic Coatings</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9125 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9125_2008-04.pdf');">Removal of Organic Coatings</a></td>
		<td align="center">04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-35-9125</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9125_Ind_2008-04.xlsx');\">";}?>MCQ Removal of Organic Coatings<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2021</td>
	</tr>	




	<tr name="80-T-35-9127">
		<td align="center"><b>80-T-35-9127</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-35-9127',2)">Aircraft Exterior Paint System Application</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-35-9127 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-35-9127_2021-12.pdf');">Aircraft Exterior Paint System Application</a></td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-35-9127</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-35-9127_Ind_12-2021.xlsx');\">";}?>MCQ/QCM Aircraft Exterior Paint System Application<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>



	<tr name="80-T-36-1010">
		<td align="center"><b>80-T-36-1010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-36-1010',2)">Heat Treatment of Aluminum</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-36-1010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-36-1010_2010-02.pdf');">Heat Treatment of Aluminum</a></td>
		<td align="center">02/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-36-1010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-36-1010_Ind_2010-02.xlsx');\">";}?>MCQ/QCM Heat Treatment of Aluminum<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/09/2021</td>
	</tr>



	<tr name="80-T-39-0109">
		<td align="center"><b>80-T-39-0109</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0109',2)">Insulation and Heating of Water Pipes</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0109 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0109_2008-03.pdf');">Insulation and Heating of Water Pipes</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-39-0109</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-0109_Ind_2008-03.xlsx');\">";}?>MCQ Insulation and Heating of Water Pipes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/09/2021</td>
	</tr>



	<tr name="80-T-39-0118">
		<td align="center"><b>80-T-39-0118</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0118',2)">Application of gap fillers</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0118 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0118_2016-02.pdf');">Application of gap fillers</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-39-0118</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-0118_Ind_2016-02.xlsx');\">";}?>MCQ Application of gap fillers<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/09/2021</td>
	</tr>



	<tr name="80-T-39-0131">
		<td align="center"><b>80-T-39-0131</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0131',2)">Production and marking of identification plates</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0131 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0131_2019-12.pdf');">Production and marking of identification plates</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-39-0131</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-0131_Ind_2019-12.xlsx');\">";}?>MCQ Production and marking of identification plates<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/09/2021</td>
	</tr>



	<tr name="80-T-39-0141">
		<td align="center"><b>80-T-39-0141</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0141',2)">Application and Protection of Identification and  Plates and Placards</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0141 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0141_2014-02_EN.pdf');">Application and Protection of Identification and  Plates and Placards</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02-2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-39-0141</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-0141_2014-02.xlsx');\">";}?>MCQ/QCM Application and Protection of Identification and  Plates and Placards<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2018</td>
	</tr>



	
	<tr name="80-T-39-0230">
		<td align="center"><b>80-T-39-0230</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0230',2)">Treatment of Damage on  Components Made of Aluminum Alloys </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0230 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0230.pdf');">Treatment of Damage on  Components Made of Aluminum Alloys</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/2009</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-39-0230</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-39-0230_MCQ.xls');\">";}?>MCQ Treatment of Damage on  Components Made of Aluminum Alloys<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/02/2015</td>
	</tr>
		



	<tr name="80-T-39-0235">
		<td align="center"><b>80-T-39-0235</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0235',2)">Cleaning and Protection of Cabin Window Panes </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0235 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0235_2009-08.pdf');">Cleaning and Protection of Cabin Window Panes</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2009</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-39-0235</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-0235_Ind_2009-08.xlsx');\">";}?>MCQ Cleaning and Protection of Cabin Window Panes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/12/2021</td>
	</tr>
			



	<tr name="80-T-39-0331">
		<td align="center"><b>80-T-39-0331</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0331',2)">Production, Installation and Rework of/on Insulation Blankets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0331 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0331_2015-07.pdf');">Production, Installation and Rework of/on Insulation Blankets</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-39-0331</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-39-0331.xls');\">";}?>MCQ Production, Installation and Rework of/on Insulation Blankets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2016</td>
	</tr>



<tr name="80-T-39-0950">
		<td align="center"><b>80-T-39-0950</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-0950',2)">Application of Adhesive Films to the Outer Skin</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-0950 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-0950.pdf');">Application of Adhesive Films to the Outer Skin</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-39-0950</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ - 80T-39-0950.xls');\">";}?>MCQ Application of Adhesive Films to the Outer Skin<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/11/2015</td>
	</tr>



<tr name="80-T-39-1020">
		<td align="center"><b>80-T-39-1020</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-1020',2)">Application of identification markings on pipes</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-1020 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-1020_2010-10.pdf');">Application of identification markings on pipes</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-39-1020</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-1020_Ind_2010-10.xlsx');\">";}?>MCQ Application of identification markings on pipes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/09/2021</td>
	</tr>



<tr name="80-T-39-1022">
		<td align="center"><b>80-T-39-1022</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-1022',2)">Soudage de revètements de sol non textiles en polychlorure de vinyle (PVC)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-1022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-1022_2021-11.pdf');">Hot Welding of Non?textile Floor Coverings made of Polyvinyl Chloride</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-39-1022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-1022_Ind_2021-11.xlsx');\">";}?>MCQ/QCM Soudage de rev?tements de sol non textiles en polychlorure de vinyle (PVC)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/03/2022</td>
	</tr>




	<tr name="80-T-39-1024">
		<td align="center"><b>80-T-39-1024</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-39-1024',2)">Application of Non-textile Floor Coverings</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-39-1024 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-39-1024_2021-11.pdf');">Application of Non-textile Floor Coverings</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-39-1024</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-39-1024_Ind_2021-11.xls');\">";}?>MCQ/QCM Pose de rev?tements de sol non textiles<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/03/2022</td>
	</tr>



	
	<tr name="80-T-40-0102">
		<td align="center"><b>80-T-40-0102</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-40-0102',2)">Installation of Fiber-optic Cables in Aircraft</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-0102 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-0102_2014-03.pdf');">Installation of Fiber-optic Cables in Aircraft</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-0102</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-40-0102_Ind_2014-03.xls');\">";}?>MCQ Installation of Fiber-optic Cables in Aircraft<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/01/2016</td>
	</tr>



	
	<tr name="80-T-40-0107">
		<td align="center"><b>80-T-40-0107</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-40-0107',2)">Installation of Quadrax Components</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-0107 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-0107_2016-08.pdf');">Installation of Quadrax Components</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-0107</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-40-0107 2016-08.xlsx');\">";}?>MCQ Installation of Quadrax Components<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/12/2021</td>
	</tr>




<tr name="80-T-40-0108">
<td align="center"><b>80-T-40-0108</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-0108',2)">Installation of Twinax Components</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-0108 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-0108-EN.pdf');">Installation of Twinax Components</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-0108</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-0108.xls');\">";}?>MCQ Installation of Twinax Components<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/03/2016</td>
	</tr>



<tr name="80-T-40-3202">
<td align="center"><b>80-T-40-3202</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3202',3)">Freinage des prises et des interrupteurs ?lectriques au fil ? freiner</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3202 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3202-FR.pdf');">Freinage des prises et des interrupteurs ?lectriques au fil ? freiner</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/1996</td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3202 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3202-EN.pdf');">Wire Locking of Electrical Connectors and Switches</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/1996</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-40-3202</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ QCM 80-T-40-3202.xls');\">";}?>MCQ/QCM Wire Locking of Electrical Connectors and Switches<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2016</td>
	</tr>



<tr name="80-T-40-3204">
<td align="center"><b>80-T-40-3204</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3204',2)">Special Contacts (Coaxial)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3204 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3204.pdf');">Special Contacts (Coaxial)</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/2009</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3204</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3204.xls');\">";}?>MCQ Special Contacts (Coaxial)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/11/2015</td>
	</tr>




<tr name="80-T-40-3210">
		<td align="center"><b>80-T-40-3210</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3210',2)">Installation of Pressure-tight Grommets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3210 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3210_2021-10.pdf');"> Installation of Pressure-tight Grommets</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3210</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3210 - 2021-10.xls');\">";}?>MCQ Installation of Pressure-tight Grommets<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/02/2022</td>
	</tr>



<tr name="80-T-40-3213">
		<td align="center"><b>80-T-40-3213</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3213',2)"> Assembly of Terminal Blocks</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3213 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3213.pdf');"> Assembly of Terminal Blocks</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-40-3213</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM 80-T-40-3213.xls');\">";}?>MCQ/QCM Assembly of Terminal Blocks<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/01/2016</td>
	</tr>


<tr name="80-T-40-3217">
		<td align="center"><b>80-T-40-3217</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-40-3217',2)">Crimping of Terminals</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3217 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3217_2019-02_EN.pdf');">Crimping of Terminals</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3217</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-40-3217_Ind_2019-02.xls');\">";}?>MCQ Crimping of Terminals<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2019</td>
	</tr>




	<tr name="80-T-40-3218">
		<td align="center"><b>80-T-40-3218</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3218',3)">Stripping of Electrical Cables</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3218 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3218.pdf');">Stripping of Electrical Cables</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3218</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-40-3218 MCQ.xls');\">";}?>MCQ Stripping of Electrical Cables<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>QCM 80-T-40-3218</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('80-T-40-3218 QCM.xls');\">";}?>QCM D?nudage de c?bles ?lectriques<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/11/2014</td>
	</tr>




	<tr name="80-T-40-3219">
		<td align="center"><b>80-T-40-3219</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3219',3)">Crimping of Special Contacts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3219 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3219_2017-02.pdf');">Crimping of Special Contacts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3219 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3219_BBL_08_2017-02.pdf');">BBL 08 Crimping of Special Contacts</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3219</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-80-T-40-3219_Ind_02-2017.xls');\">";}?>MCQ Crimping of Special Contacts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/07/2017</td>
	</tr>



<tr name="80-T-40-3310">
		<td align="center"><b>80-T-40-3310</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3310',2)">Splices and End Caps</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3310 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3310.pdf');">Splices and End Caps</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3310</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3310.xls');\">";}?>MCQ Splices and End Caps<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/11/2015</td>
	</tr>



<tr name="80-T-40-3312">
		<td align="center"><b>80-T-40-3312</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('80-T-40-3312',4)">Electrical Bonding - Métallisation</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3312 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3312_2020-09_EN.pdf');">Electrical Bonding</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM 80-T-40-3312</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_80-T-40-3312_Ind_2020-09.xls');\">";}?>MCQ/QCM Electrical Bonding - M?tallisation<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/11/2020</td>
	</tr>
	<tr>
		<td align="center"><b>FT comparatif serrage 80T et IPDA (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('FT comparaison m?tallisation 80T et IPDA -FR.pptx');">Electrical Bonding</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>FT comparison Electrical bonding 80T et IPDA (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('FT comparison bonding 80T et IPDA -EN.pptx');">Electrical Bonding</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/10/2015</td>
	</tr>




<tr name="80-T-40-3500">
<td align="center"><b>80-T-40-3500</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3500',2)">Cleaning and Preversation of Electronic Assemblies</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3500 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3500.pdf');">Cleaning and Preversation of Electronic Assemblies</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1999</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3500</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3500.xls');\">";}?>MCQ Cleaning and Preversation of Electronic Assemblies<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/11/2015</td>
	</tr>




	<tr name="80-T-40-3702">
		<td align="center"><b>80-T-40-3702</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3702',2)">Installation of cable guides and supports</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3702 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3702.pdf');">Installation of cable guides and supports</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/2009</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3702</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3702.xls');\">";}?>MCQ Installation of cable guides and supports<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/11/2015</td>
	</tr>




	<tr name="80-T-40-3703">
		<td align="center"><b>80-T-40-3703</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3703',2)"> Electrical Installation in Conduits</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3703 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3703.pdf');"> Electrical Installation in Conduits</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1999</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3703</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3703.xls');\">";}?>MCQ  Electrical Installation in Conduits<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/12/2015</td>
	</tr>




	<tr name="80-T-40-3714">
		<td align="center"><b>80-T-40-3714</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3714',2)">Marking of Electrical Cables with UV Laser</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3714 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3714.pdf');"> Marking of Electrical Cables with UV Laser</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/2004</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3714</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3714.xls');\">";}?>MCQ  Marking of Electrical Cables with UV Laser<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/03/2016</td>
	</tr>




<tr name="80-T-40-3735">
<td align="center"><b>80-T-40-3735</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-3735',2)">Handling of Electrostatically Sensitive Electronic Components</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-3735 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-3735.pdf');">Handling of Electrostatically Sensitive Electronic Components</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1999</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-3735</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-3735.xls');\">";}?>MCQ Handling of Electrostatically Sensitive Electronic Components<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/11/2015</td>
	</tr>




<tr name="80-T-40-4001">
<td align="center"><b>80-T-40-4001</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-4001',2)">Crimping of Circular Contacts to Copper Wires</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-4001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-4001_2017-03_EN.pdf');">Crimping of Circular Contacts to Copper Wires</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-4001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_80-T-40-4001_Ind_2017-03.xls');\">";}?>MCQ Crimping of Circular Contacts to Copper Wires<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/08/2017</td>
	</tr>




	<tr name="80-T-40-5001">
		<td align="center"><b>80-T-40-5001</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-5001',2)">Crimping of Circular Contacts to Aluminum Cables </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-5001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-5001.pdf');">Crimping of Circular Contacts to Aluminum Cables </a></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/2009</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-5001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-5001.xls');\">";}?>MCQ Crimping of Circular Contacts to Aluminum Cables <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/11/2015</td>
	</tr>




<tr name="80-T-40-8100">
		<td align="center"><b>80-T-40-8100</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-8100',2)">Soldering of Electrical Connections by Hand</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-8100 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-8100.pdf');">Soldering of Electrical Connections by Hand</a></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/1999</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-8100</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-8100.xls');\">";}?>MCQ Soldering of Electrical Connections by Hand<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/11/2015</td>
	</tr>



<tr name="80-T-40-8103">
		<td align="center"><b>80-T-40-8103</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('80-T-40-8103',2)">Shrinking-on of Soldering Sleeves </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC 80-T-40-8103 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('80-T-40-8103.pdf');">Shrinking-on of Soldering Sleeves </a></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ 80-T-40-8103</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ 80-T-40-8103.xls');\">";}?>MCQ Shrinking-on of Soldering Sleeves <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/11/2015</td>
	</tr>


		
	
	
	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIRBUS IPDA -------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIRBUS IPDA">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS IPDA</b></td>
	</tr>


	<tr name="IPDA_04-02">
		<td align="center"><b>IPDA 04-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_04-02',2)">CLEANING OF WINDOWS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 04-02 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_04-02_2011-03-09_A3.pdf');">CLEANING OF WINDOWS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">09/03/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 04-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_IPDA 04-02_A3.xls');\">";}?>MCQ CLEANING OF WINDOWS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">19/05/2021</td>
	</tr>


	
	<tr name="IPDA_28-01">
		<td align="center"><b>IPDA 28-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_28-01',2)">INSTALLING IDENTIFICATION TAPES ON PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 28-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_28-01_2006-02-14_A1.pdf');">INSTALLING IDENTIFICATION TAPES ON PIPES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">14/02/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 28-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 28-01_Ind_A1.xlsx');\">";}?>MCQ INSTALLING IDENTIFICATION TAPES ON PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">28/10/2021</td>
	</tr>
	
	
	
	
		<tr name="IPDA_28-06">
		<td align="center"><b>IPDA 28-06</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_28-06',2)">PROTECTION OF IDENTIFICATION PLACARDS IN SKYDROL ZONES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 28-06 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_28-06_2005-11-07_A1.pdf');">PROTECTION OF IDENTIFICATION PLACARDS IN SKYDROL ZONES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">07/11/2005</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 28-06</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 28-06_Ind_A1.xlsx');\">";}?>MCQ PROTECTION OF IDENTIFICATION PLACARDS IN SKYDROL ZONES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">28/10/2021</td>
	</tr>
	
	
	
	
	
<tr name="IPDA_37-05">
		<td align="center"><b>IPDA 37-05</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_37-05',2)">PERCAGE AXIAL EMPILAGES CARBONE/METAL</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 37-05 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_37-05.pdf');">PERCAGE AXIAL EMPILAGES CARBONE/METAL</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">08/07/2015</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 37-05</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM - IPDA 37-05 A3.xls');\">";}?>QCM PERCAGE AXIAL EMPILAGES CARBONE/METAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">20/08/2015</td>
	</tr>




<tr name="IPDA_37-06">
		<td align="center"><b>IPDA 37-06</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_37-06',3)">DRILLING, REAMING, COUNTERSINKING OF GLASS, ARAMID AND HYBRID COMPOSITES </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 37-06 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_37-06_2009-10_A1_EN.pdf');">DRILLING, REAMING, COUNTERSINKING OF GLASS, ARAMID AND HYBRID COMPOSITES </a></td>
		<td align="center">Ed-A1</td>
		<td align="center">21/01/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 37-06 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_37-06.pdf');">PERC, ALE, FRAIS COMPO VERRE, ARAM & HYB </a></td>
		<td align="center">Ed-A1</td>
		<td align="center">16/10/2009</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 37-06</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM - IPDA 37-06 A1.xls');\">";}?>QCM PERC, ALE, FRAIS COMPO VERRE, ARAM & HYB<?php if($QCM){echo "</a>";}?></td>
		<td align="center">17/09/2015</td>
	</tr>



<tr name="IPDA_45-01">
		<td align="center"><b>IPDA 45-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_45-01',2)">EXPANSION A FROID ALESAGES ET FRAISURES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 45-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_45-01_2010-07_A3_FR.pdf');">EXPANSION A FROID ALESAGES ET FRAISURES</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">05/07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 45-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_45-01_Ind_A3.xlsx');\">";}?>QCM EXPANSION A FROID ALESAGES ET FRAISURES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">12/12/2019</td>
	</tr>



<tr name="IPDA_45-08">
		<td align="center"><b>IPDA 45-08</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_45-08',3)">INSTALLING BUSHES IN BORES BY BUSHLOC PROCESS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 45-08 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_45-08_2003-09_A1_EN.pdf');">INSTALLING BUSHES IN BORES BY BUSHLOC PROCESS</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">05/01/2004</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 45-08 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_45-08_2003-09_A1_FR.pdf');">INSTALLING BUSHES IN BORES BY BUSHLOC PROCESS</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">26/11/2003</td>
	</tr>
	<tr>
		<td align="center"><b>QCM-MCQ IPDA 45-08</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_45-08_Ind_A1.xlsx');\">";}?>QCM-MCQ INSTALLING BUSHES IN BORES BY BUSHLOC PROCESS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">28/11/2019</td>
	</tr>





<tr name="IPDA_62-30">
		<td align="center"><b>IPDA 62-30</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_62-30',3)">PROTECTION TOUCH-UP BY CHROMATE TREATMENT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 62-30 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_62-30_2008-05-07_A3_EN.pdf');">PROTECTION TOUCH-UP BY CHROMATE TREATMENT</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">16/12/2008</td>
	</tr>
		<tr>
		<td align="center"><b>DOC IPDA 62-30 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_62-30_2008-05-07_A3_FR.pdf');">RETOUCHE PROTECTION PAR CHROMATATION</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">16/12/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 62-30</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_62-30_Ind_A3.xlsx');\">";}?>MCQ PROTECTION TOUCH-UP BY CHROMATE TREATMENT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/09/2021</td>
	</tr>
	
	
	
	
	<tr name="IPDA_64-01">
		<td align="center"><b>IPDA 64-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-01',2)">SURFACE PREP BEFORE FINAL EXT PAINTING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-01_2005-04_A2.pdf');">SURFACE PREP BEFORE FINAL EXT PAINTING</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">04/2005</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 64-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 64-01_Ind_A2.xlsx');\">";}?>MCQ/QCM SURFACE PREP BEFORE FINAL EXT PAINTING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/10/2021</td>
	</tr>
	
	



<tr name="IPDA_64-02">
		<td align="center"><b>IPDA 64-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-02',2)">MANUAL PAINTING OF METAL DETAIL PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-02_PART_FRA_VERS_A6_2021-12_A6.pdf');">MANUAL PAINTING OF METAL DETAIL PARTS</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 64-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IPDA_64-02 A6.xls');\">";}?>MCQ/QCM MANUAL PAINTING OF METAL DETAIL PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/02/2022</td>
	</tr>
	



	<tr name="IPDA_64-04">
		<td align="center"><b>IPDA 64-04</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-04',3)">SURFACE PREPARATION OF COMPOSITES BEFORE PAINTING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-04 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-04.pdf');">SURFACE PREPARATION OF COMPOSITES BEFORE PAINTING</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">06/05/2011</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-04 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-04_A3 FR.pdf');">COMPOSITE SURFACE PREPARATION PRIOR TO PAINT APPLICATION</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">12/01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 64-04</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA_64-04_Ind_A3.xls');\">";}?>MCQ/QCM COMPOSITE SURFACE PREPARATION PRIOR TO PAINT APPLICATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/08/2015</td>
	</tr>




	<tr name="IPDA_64-05">
		<td align="center"><b>IPDA 64-05</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-05',3)">PAINTING OF COMPOSITE DETAIL PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-05 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-05_2011-08_A2_EN.pdf');">PAINTING OF COMPOSITE DETAIL PARTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/12/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-05 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-05_2011-08_A2_FR.pdf');">PAINTING OF COMPOSITE DETAIL PARTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">20/10/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 64-05</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA_64-05_Ind_A2.xlsx');\">";}?>MCQ/QCM PAINTING OF COMPOSITE DETAIL PARTS<td align="center">&nbsp;</td>
		<td align="center">04/12/2019</td>
	</tr>




<tr name="IPDA_64-07">
		<td align="center"><b>IPDA 64-07</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-07',2)">POLYMERISATION ACCELEREE DES PEINTURES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-07 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-07_2011-03_A2.pdf');">POLYMERISATION ACCELEREE DES PEINTURES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">03/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 64-07</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA 64-07 A2.xls');\">";}?>MCQ/QCM POLYMERISATION ACCELEREE DES PEINTURES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/08/2016</td>
	</tr>
	
	
	
	
	<tr name="IPDA_64-14">
		<td align="center"><b>IPDA 64-14</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-14',2)">REVETEMENT BARRIERE D'ETANCHEITE/FUEL VAPOUR BARRIER COATING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-14 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-14.pdf');">REVETEMENT BARRIERE D'ETANCHEITE</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">14/03/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 64-14</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA_64-14.xls');\">";}?>MCQ/QCM REVETEMENT BARRIERE D'ETANCHEITE/FUEL VAPOUR BARRIER COATING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/11/2015</td>
	</tr>



<tr name="IPDA_64-15">
		<td align="center"><b>IPDA 64-15</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_64-15',3)">ANTI-CORROSION PROTECTION OF FASTENER ROWS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 64-15 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-15_2008-09-16_A3_EN.pdf');">ANTI-CORROSION PROTECTION OF FASTENER ROWS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">27/03/2009</td>
	</tr>
		<tr>
		<td align="center"><b>DOC IPDA 64-15 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_64-15_2008-09-16_A3_FR.pdf');">PROTECT° ANTI CORROSION LIGNES DE FIXAT°</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">16/09/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 64-15</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_64-15_Ind_A3.xlsx');\">";}?>MCQ ANTI-CORROSION PROTECTION OF FASTENER ROWS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/12/2021</td>
	</tr>



	<tr name="IPDA_66-02">
		<td align="center"><b>IPDA 66-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_66-02',3)">Repairing defects on aircraft aluminium alloys outer skins</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 66-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_66-02-FR.pdf');">Repairing defects on aircraft aluminium alloys outer skins </a></td>
		<td align="center">Ed-A1</td>
		<td align="center">14/03/2001</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 66-02 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_66-02-EN.pdf');">Repairing defects on aircraft aluminium alloys outer skins</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">17/12/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 66-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA 66-02.xls');\">";}?>MCQ/QCM Repairing defects on aircraft aluminium alloys outer skins<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/10/2015</td>
	</tr>



	<tr name="IPDA_66-06">
		<td align="center"><b>IPDA 66-06</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_66-06',2)">SABLAGE A SEC DES METAUX DURS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 66-06 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_66-06_1999-10_A0_FR.pdf');">SABLAGE A SEC DES METAUX DURS</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">10/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 66-06</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_66-06_Ind_A0.xlsx');\">";}?>QCM SABLAGE A SEC DES METAUX DURS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/02/2019</td>
	</tr>



	<tr name="IPDA_69-02">
		<td align="center"><b>IPDA 69-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_69-02',2)">INTERIOR ANTI-CORROSION PROTECTION TOUCH-UP</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 69-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_69-02_A3.pdf');">INTERIOR ANTI-CORROSION PROTECTION TOUCH-UP</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 69-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_IPDA 69-02_2021_02_Ind_A3.xlsx');\">";}?>MCQ INTERIOR ANTI-CORROSION PROTECTION TOUCH-UP<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/02/2021</td>
	</tr>



<tr name="IPDA_71-03">
		<td align="center"><b>IPDA 71-03</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-03',3)">TIGHTENING TORQUES FOR STRUCTURAL FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-03 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-03_2021-04_A4.pdf');">APPL COUPLE SERRAGE BOULONNERIE STRUCTUR</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-03</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA_71-03_Ind_A4.xlsx');\">";}?>MCQ/QCM TIGHTENING TORQUES FOR STRUCTURAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/11/2021</td>
	</tr>



<tr name="IPDA_71-05">
		<td align="center"><b>IPDA 71-05</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-05',2)">POSE BOULON DE TRACTION ET RONDELLE PLI DANS LES ASSEMBLAGES METALLIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-05 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-05_2018-01_A2_FR.pdf');">POSE BOULON DE TRACTION ET RONDELLE PLI DANS LES ASSEMBLAGES METALLIQUES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">15/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 71-05</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_71-05_Ind_A2.xlsx');\">";}?>MCQ POSE BOULON DE TRACTION ET RONDELLE PLI DANS LES ASSEMBLAGES METALLIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/03/2020</td>
	</tr>


	<tr name="IPDA_71-09">
		<td align="center"><b>IPDA 71-09</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-09',2)">POSE FIXATIONS TAPER HI-LITE ASS. METAL</span></td>
	</tr>

	<tr>
		<td align="center"><b>DOC IPDA 71-09 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-09_2019-08_A8.pdf');">POSE FIXATIONS TAPER HI-LITE ASS. METAL</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">08/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-09</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 71-09_Ind_A8.xlsx');\">";}?>MCQ/QCM POSE FIXATIONS TAPER HI-LITE ASS. METAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/10/2021</td>
	</tr>
	
	
	

<tr name="IPDA_71-10">
		<td align="center"><b>IPDA 71-10</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-10',2)">INSTALLING HI-LITE FASTENERS IN METALLIC ASSEMBLIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-10 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-10_A5.pdf');">POSE FIXAT° HI LITE DS ASSEMB METALLIQUE</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">08/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-10</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 71-10_2021_02_A5.xlsx');\">";}?>MCQ/QCM INSTALLING HI-LITE FASTENERS IN METALLIC ASSEMBLIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/12/2021</td>
	</tr>



<tr name="IPDA_71-11">
		<td align="center"><b>IPDA 71-11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-11',2)">INSTALLING LOCKBOLTS IN METALLIC MATERIALS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-11 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-11_2020-08_A4.pdf');">POSE FIXAT° LOCKBOLTS MATERIAUX METALLIQ</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">08/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA_71-11_2020-08_A4.xlsx');\">";}?>MCQ/QCM INSTALLING LOCKBOLTS IN METALLIC MATERIALS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2022</td>
	</tr>



<tr name="IPDA_71-12">
		<td align="center"><b>IPDA 71-12</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-12',3)">RIVETING OF METAL ASSEMBLIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-12 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-12_2020-08_A5.pdf');">RIVETAGES DES ASSEMBLAGES METALLIQUES</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">08/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 71-12</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_IPDA_71-12_Ind_A5.xlsx');\">";}?>MCQ RIVETING OF METAL ASSEMBLIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/10/2021</td>
	</tr>



	<tr name="IPDA_71-14">
		<td align="center"><b>IPDA 71-14</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-14',3)">INSTALLATION OF BLIND FASTENERS NAS 1919 - 1921</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-14 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-14_2006-10-09_A4_EN.pdf');">INSTALLATION OF BLIND FASTENERS NAS 1919 - 1921</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">24/10/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-14 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-14.pdf');">POSE FIXATIONS AVEUGLES NAS 1919 - 1921</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">24/10/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-14</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA 71-14.xls');\">";}?>MCQ/QCM POSE FIXATIONS AVEUGLES NAS 1919 - 1921<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/10/2015</td>
	</tr>

<tr name="IPDA_71-15">
		<td align="center"><b>IPDA 71-15</b></td>
		<td colspan="2"><a href="javascript:onclick=Voir_TR('IPDA_71-15',2)">POSE BOULON DE TRACTION ET RONDELLE PLI DANS LES ASSEMBLAGES METALLIQUES</span></td>
	</tr>

	<tr>
		<td align="center"><b>DOC IPDA 71-15 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-15.pdf');">POSE BOULON DE TRACTION ET RONDELLE PLI DANS LES ASSEMBLAGES METALLIQUES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">TBD</td>
	</tr>

	<tr name="IPDA_71-16">
		<td align="center"><b>IPDA 71-16</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-16',3)">INSTALLATION OF VISU-LOK AND JO-LOK BLIND FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-16 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-16_2006-06-22_A5_EN.pdf');">INSTALLATION OF VISU-LOK AND JO-LOK BLIND FASTENERS</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">22/06/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-16 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-16_2006-06-22_A5_FR.pdf');">INSTALLATION OF VISU-LOK AND JO-LOK BLIND FASTENERS</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">22/06/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 71-16</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_IPDA_71-16_Ind_A5.xlsx');\">";}?>MCQ INSTALLATION OF VISU-LOK AND JO-LOK BLIND FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/05/2019</td>
	</tr>



	<tr name="IPDA_71-17">
		<td align="center"><b>IPDA 71-17</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-17',3)">INSTALLATION OF BLIND AVDEL-MBC FASTENERS IN METALLIC ASSEMBLIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-17 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-17_1999-10_A0_EN.pdf');">INSTALLATION OF BLIND AVDEL-MBC FASTENERS IN METALLIC ASSEMBLIES</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">17/12/2014</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-17 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-17.pdf');">POSE DES FIXATIONS AVEUGLES AVDEL-MBC DANS DES ASSEMBLAGES METALLIQUES</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">24/11/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 71-17</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_71-17_Ind_A0.xls');\">";}?>QCM POSE DES FIXATIONS AVEUGLES AVDEL-MBC DANS DES ASSEMBLAGES METALLIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/10/2015</td>
	</tr>



	<tr name="IPDA_71-18">
		<td align="center"><b>IPDA 71-18</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-18',2)">POSE FIXAT° HI LITE PULL STEM DS METALIQ</span></td>
	</tr>

	<tr>
		<td align="center"><b>DOC IPDA 71-18 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-18_2013-04_A4_FR.pdf');">POSE FIXAT° HI LITE PULL STEM DS METALIQ</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">04/04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-18</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_71-18_Ind_A4.xlsx');\">";}?>MCQ/QCM POSE FIXAT° HI LITE PULL STEM DS METALIQ<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/03/2019</td>
	</tr>
	
	
	
	
	<tr name="IPDA_71-21">
		<td align="center"><b>IPDA 71-21</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-21',3)">INSTALLING BLIND FASTENERS ASNA0077-0078</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-21 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-21_2001-06_A0_EN.pdf');">INSTALLING BLIND FASTENERS ASNA0077-0078</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/02/2002</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 71-21 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-21_2001-06_A0_FR.pdf');">INSTALLING BLIND FASTENERS ASNA0077-0078</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/02/2002</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 71-21</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_IPDA_71-21_Ind_A0.xlsx');\">";}?>MCQ INSTALLING BLIND FASTENERS ASNA0077-0078<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/05/2019</td>
	</tr>



	<tr name="IPDA_71-23">
		<td align="center"><b>IPDA 71-23</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_71-23',2)">POSE DE VIS DANS ASSEMBLAGE METALLIQUE</span></td>
	</tr>

	<tr>
		<td align="center"><b>DOC IPDA 71-23 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_71-23_2013-04_A0_FR.pdf');">POSE DE VIS DANS ASSEMBLAGE METALLIQUE</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 71-23</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_71-23_Ind_A0.xlsx');\">";}?>MCQ/QCM POSE DE VIS DANS ASSEMBLAGE METALLIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/03/2019</td>
	</tr>




	<tr name="IPDA_72-02">
		<td align="center"><b>IPDA 72-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_72-02',2)">POSE FIX AV MS21140/21141 ASS COMP METAL</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-02.pdf');">POSE FIX AV MS21140/21141 ASS COMP METAL</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">27/09/2012</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 72-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 72-02.xls');\">";}?>QCM POSE FIX AV MS21140/21141 ASS COMP METAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2015</td>
	</tr>



	<tr name="IPDA_72-08">
		<td align="center"><b>IPDA 72-08</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_72-08',3)">INSTALLING STANDARD SCREWS IN COMPOSITE AND MIXED ASSEMBLIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-08 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-08_2012-10_A1_FR.pdf');">INSTALLING STANDARD SCREWS IN COMPOSITE AND MIXED ASSEMBLIES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">23/10/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-08 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-08_2012-10_A1_FR.pdf');">INSTALLING STANDARD SCREWS IN COMPOSITE AND MIXED ASSEMBLIES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">07/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 72-08</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA_72-08_Ind_A1.xlsx');\">";}?>MCQ/QCM INSTALLING STANDARD SCREWS IN COMPOSITE AND MIXED ASSEMBLIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/03/2019</td>
	</tr>


	
	<tr name="IPDA_72-10">
		<td align="center"><b>IPDA 72-10</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_72-10',3)">INSTALLING FASTENERS NAS 1919/1921 IN COMPOSITES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-10 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-10_2013-02_A4_EN.pdf');">INSTALLING FASTENERS NAS 1919/1921 IN COMPOSITES</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">19/01/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-10 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-10.pdf');">INSTALLATION OF NAS 1919/1921 RIVETS IN COMPOSITE MATERIAL</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">19/02/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 72-10</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA 72-10.xls');\">";}?>MCQ/QCM INSTALLATION OF NAS 1919/1921 RIVETS IN COMPOSITE MATERIAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2015</td>
	</tr>




	<tr name="IPDA_72-11">
		<td align="center"><b>IPDA 72-11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_72-11',3)">INSTALLING BLIND FASTENERS ASNA 0341/0342</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-11 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-11_2001-03_A0_EN.pdf');">INSTALLING BLIND FASTENERS ASNA 0341/0342</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">08/07/2002</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-11 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA-72-11.pdf');">INSTALLATION OF BLIND RIVETS ASNA 0341/0342</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">19/05/2001</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 72-11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA_72-11.xls');\">";}?>MCQ/QCM INSTALLATION OF BLIND RIVETS ASNA 0341/0342<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2015</td>
	</tr>




	<tr name="IPDA_72-16">
		<td align="center"><b>IPDA 72-16</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_72-16',3)">POSE BOULON TRACTION DANS ASS MIXTE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-16 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-16_2010-12_A0_EN.pdf');">INSTALLING TENSION BOLTS IN MIXED ASSEMBLY </a></td>
		<td align="center">Ed-A0</td>
		<td align="center">19/01/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 72-16 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_72-16.pdf');">POSE BOULON TRACTION DANS ASS MIXTE</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">14/01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 72-16</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_72-16_Ind_A0.xls');\">";}?>QCM POSE BOULON TRACTION DANS ASS MIXTE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/10/2015</td>
	</tr>



	<tr name="IPDA_74-01">
		<td align="center"><b>IPDA 74-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_74-01',2)">SCELLEMENT A FRD BAGUE-ROULEMENT-ROTULE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 74-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_74-01_2012-09_A3.pdf');">SCELLEMENT A FRD BAGUE-ROULEMENT-ROTULE</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">09/2012</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 74-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA 74-01_2012-09_A3.xlsx');\">";}?>QCM SCELLEMENT A FRD BAGUE-ROULEMENT-ROTULE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/05/2021</td>
	</tr>


<tr name="IPDA_74-09">
		<td align="center"><b>IPDA 74-09</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_74-09',3)">MISE EN OEUVRE DES ADHESIFS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 74-09 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_74-09_1999-10_A0_EN.pdf');">NON-STRUCT BONDING CABIN FURNISHINGS</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">10/1999</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 74-09 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_74-09_1999-10_A0_FR.pdf');">MISE EN OEUVRE DES ADHESIFS</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">10/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 74-09</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_74-09_Ind_A0.xlsx');\">";}?>QCM MISE EN OEUVRE DES ADHESIFS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/11/2015</td>
	</tr>



	<tr name="IPDA_74-11">
		<td align="center"><b>IPDA 74-11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_74-11',2)">MISE EN OEUVRE DES ADHESIFS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 74-11 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA 74-11.pdf');">MISE EN OEUVRE DES ADHESIFS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">10/01/2008</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 74-11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_74-11_Ind_A0_with EN.xls');\">";}?>QCM MISE EN OEUVRE DES ADHESIFS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_75-05">
		<td align="center"><b>IPDA 75-05</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_75-05',3)">INSTALLATION OF TIGHT FIT BUSHINGS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 75-05 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_75-05_2010-09_A3_EN.pdf');">INSTALLATION OF TIGHT FIT BUSHINGS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">13/05/2011</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 75-05 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_75-05.pdf');">INSTALLATION OF TIGHT FIT BUSHINGS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">01/09/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 75-05</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA_75-05.xls');\">";}?>MCQ/QCM INSTALLATION OF TIGHT FIT BUSHINGS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/10/2015</td>
	</tr>




	<tr name="IPDA_77-01">
		<td align="center"><b>IPDA 77-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_77-01',3)">DEFROSTING - PREPARATION - APPLICATION OF SEALANTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 77-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_77-01_2011-12_A6_EN.pdf');">DEFROSTING - PREPARATION - APPLICATION OF SEALANTS</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">26/01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 77-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_77-01-FR.pdf');">DECONGEL-PREPA - APPLICATION DES MASTICS</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">19/11/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 77-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM IPDA_77_01.xls');\">";}?>MCQ/QCM DEFROSTING - PREPARATION - APPLICATION OF SEALANTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2015</td>
	</tr>



	<tr name="IPDA_77-02">
		<td align="center"><b>IPDA 77-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_77-02',2)">INST PLAQUE DE PROTEC DE SEUILS DE PORTE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 77-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_77-02_2001-02_A1_FR.pdf');">INST PLAQUE DE PROTEC DE SEUILS DE PORTE</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">15/03/2001</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 77-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_77-02 A1_with EN.xls');\">";}?>QCM INST PLAQUE DE PROTEC DE SEUILS DE PORTE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>



	<tr name="IPDA_77-06">
		<td align="center"><b>IPDA 77-06</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_77-06',3)">DISTRIBUTION-CONSERVATION OF FROZEN SEALANTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 77-06 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_77-06_2011-12_A4_EN.pdf');">DISTRIBUTION-CONSERVATION OF FROZEN SEALANTS</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">05/12/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 77-06 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_77-06_2011-12_A4_FR.pdf');">DISTRI-CONSERVATION DES MASTICS CONGELES</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">02/12/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 77-06</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_IPDA_77-06_Ind_A4.xlsx');\">";}?>MCQ DISTRIBUTION-CONSERVATION OF FROZEN SEALANTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/03/2019</td>
	</tr>



<tr name="IPDA_79-02">
		<td align="center"><b>IPDA 79-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_79-02',3)">FILLER RESIN - DUAL CARTRIDGE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 79-02 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_79-02_2008-09_A2_EN.pdf');">FILLER RESIN - DUAL CARTRIDGE</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">03/06/2009</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 79-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_79-02_2008-09_A2.pdf');">RESINE D'INTERPOSITION -CARTOUCHE DUAL</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">13/11/2008</td>
	</tr>
	<tr>
		<td align="center"><b>QCM/MCQ IPDA 79-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IPDA_79-02_Ind_A2.xls');\">";}?>QCM/MCQ RESINE D'INTERPOSITION -CARTOUCHE DUAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/07/2019</td>
	</tr>



<tr name="IPDA_79-04">
		<td align="center"><b>IPDA 79-04</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_79-04',2)">USE OF RHODORSIL CAF</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 79-04 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_79-04_2004-05_A1.pdf');">USE OF RHODORSIL CAF</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">05/2004</td>
	</tr>
	<tr>
		<td align="center"><b>QCM/MCQ IPDA 79-04</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 79-04_Ind_A1.xlsx');\">";}?>QCM/MCQ USE OF RHODORSIL CAF<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/10/2021</td>
	</tr>




	<tr name="IPDA_82-03">
		<td align="center"><b>IPDA 82-03</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_82-03',1)">INSTALLING COMPONENTS FOR HYDRAULIC DISTRIBUTION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 82-03 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_82-03_2020-07_A6.pdf');">INSTALLING COMPONENTS FOR HYDRAULIC DISTRIBUTION</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">07/2020</td>
	</tr>




	<tr name="IPDA_82-07">
		<td align="center"><b>IPDA 82-07</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_82-07',2)">SWAGING PERMASWAGE UNIONS WITH DLT TOOL</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 82-07 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_82-07_2003-09_A2.pdf');">SWAGING PERMASWAGE UNIONS WITH DLT TOOL</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">09/2003</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 82-07</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 82-07 Ind_A2.xlsx');\">";}?>MCQ SWAGING PERMASWAGE UNIONS WITH DLT TOOL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/10/2021</td>
	</tr>





	<tr name="IPDA_82-10">
		<td align="center"><b>IPDA 82-10</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_82-10',2)">PIPE PRESSURE TEST & DECONTAMINATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 82-10 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_82-10_2009-12_A3.pdf');">PIPE PRESSURE TEST & DECONTAMINATION</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">12/2009</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 82-10</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 82-10_Ind_A3.xlsx');\">";}?>MCQ PIPE PRESSURE TEST & DECONTAMINATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/10/2021</td>
	</tr>




	<tr name="IPDA_83-01">
		<td align="center"><b>IPDA 83-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-01',3)">MANUFACTURE & INSTALLATION OF ELECTRICAL WIRING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-01_2016-11_A2_EN.pdf');">MANUFACTURE & INSTALLATION OF ELECTRICAL WIRING</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">07/06/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-01_2016-11_A2.pdf');">FABRICATION & INSTALLATION CABLAGES ELEC</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">11/2016</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_83-01 A2_with EN.xls');\">";}?>QCM FABRICATION & INSTALLATION CABLAGES ELEC<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>





	<tr name="IPDA_83-02">
		<td align="center"><b>IPDA 83-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-02',2)"> FRETTAGE CABLE PAR NSA935401 OU NSA8420</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-02 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-02_2017-02_A5.pdf');">FRETTAGE CABLE PAR NSA935401 OU NSA8420</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">27/02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_83-02_Ind_A5_with EN.xls');\">";}?>QCM FRETTAGE CABLE PAR NSA935401 OU NSA8420<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-04">
		<td align="center"><b>IPDA 83-04</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-04',3)">CRIMPING WITH HYDRAULIC, HAND AND POWER TOOLS </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-04 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-04-FR.pdf');">SERTIS. OUTILS HYDRAU. MANUELS ET PAR GENE. DE PRESSION</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">24/02/2004</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-04 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-04-EN.pdf');">CRIMPING WITH HYDRAULIC, HAND AND POWER TOOLS </a></td>
		<td align="center">Ed-A4</td>
		<td align="center">21/04/2004</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-04</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ IPDA 83-04.xls');\">";}?>MCQ/QCM CRIMPING WITH HYDRAULIC, HAND AND POWER TOOLS <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/03/2016</td>
	</tr>




	<tr name="IPDA_83-05">
		<td align="center"><b>IPDA 83-05</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-05',3)">PRINCIPLE FOR USING MANUAL CRIMPING PLIERS: M 22520, MS 3198, MS 3191 </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-05 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-05_1999-10_A0_EN.pdf');">PRINCIPLE FOR USING MANUAL CRIMPING PLIERS: M 22520, MS 3198, MS 3191 </a></td>
		<td align="center">Ed-A0</td>
		<td align="center">26/01/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-05 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-05.pdf');">PRINCIPE D?UTILISATION DES PINCES A SERTIR MANUELLES</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">24/11/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-05</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-05 A0_with EN.xls');\">";}?>QCM PRINCIPE D?UTILISATION DES PINCES A SERTIR MANUELLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-06">
		<td align="center"><b>IPDA 83-06</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-06',3)">TIGHTENING TORQUES FOR ELECTRICAL EQUIPMENT </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-06 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-06_1999-10_A0_EN.pdf');">TIGHTENING TORQUES FOR ELECTRICAL EQUIPMENT </a></td>
		<td align="center">Ed-A0</td>
		<td align="center">16/11/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-06 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-06.pdf');">COUPLES DE SERRAGE SUR MATERIELS ELECTRIQUES</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">24/11/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-06</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-06 A0_with EN.xls');\">";}?>QCM COUPLES DE SERRAGE SUR MATERIELS ELECTRIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-07">
		<td align="center"><b>IPDA 83-07</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-07',3)">STRIPPING OF ELECTRICAL WIRES AND CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-07 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-07_2002-04-03_A1_EN.pdf');">STRIPPING OF ELECTRICAL WIRES AND CABLES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">16/04/2003</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-07 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-07.pdf');">DENUDAGE DES CABLES ELECTRIQUES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">04/06/2002</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-07</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-07 A1_with EN.xls');\">";}?>QCM DENUDAGE DES CABLES ELECTRIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-10">
		<td align="center"><b>IPDA 83-10</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-10',3)">SHIELDING TERMINATIONS ON ELECTRIC CABLES </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-10 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-10-FR.pdf');">ARRET DE BLINDAGE SUR CABLES ELECTRIQUES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/12/2005</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-10 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-10-EN.pdf');">SHIELDING TERMINATIONS ON ELECTRIC CABLES </a></td>
		<td align="center">Ed-A2</td>
		<td align="center">03/01/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-10</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM IPDA 83-10.xls');\">";}?>MCQ/QCM SHIELDING TERMINATIONS ON ELECTRIC CABLES <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2016</td>
	</tr>




	<tr name="IPDA_83-11">
		<td align="center"><b>IPDA 83-11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-11',2)">GROUNDING & ELECTRICAL BONDING METHOD</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-11 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-11_2020-08_A6.pdf');">MISE A LA MASSE & METHODE DE METALLISATION</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">08/2020</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_83-11_A6_with EN.xls');\">";}?>QCM MISE A LA MASSE & METHODE DE METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>
	



	<tr name="IPDA_83-12">
		<td align="center"><b>IPDA 83-12</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-12',3)">GROUNDING MODULE ASNE0425/ABS1599</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-12 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-12_2007-04-04_A2_EN.pdf');">GROUNDING MODULE ASNE0425/ABS1599</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/08/2008</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-12 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-12.pdf');">MODULE DE MISE A LA MASSE</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">30/04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-12</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-12 A2_with EN.xls');\">";}?>QCM MODULE DE MISE A LA MASSE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-13">
		<td align="center"><b>IPDA 83-13</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-13',2)">GAINES TEXTILES POUR PROTECT? CABLE ELEC</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-13 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-13.pdf');">GAINES TEXTILES POUR PROTECT? CABLE ELEC</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">10/02/2015</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-13</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-13 A1_with EN.xls');\">";}?>QCM GAINES TEXTILES POUR PROTECT? CABLE ELEC<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>





	<tr name="IPDA_83-14">
		<td align="center"><b>IPDA 83-14</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-14',3)">ASSEMBLY AND CONNECTION OF MODULAR TERMINAL BLOCKS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-14 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-14_2006-01-04_A4_EN.pdf');">ASSEMBLY AND CONNECTION OF MODULAR TERMINAL BLOCKS</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">26/01/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-14 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-14.pdf');">ASSEMBLAGES ET RACCORDEMENTS DE BARRETTES A MODULES</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">10/01/2006</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-14</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-14 A4_with EN.xls');\">";}?>QCM ASSEMBLAGES ET RACCORDEMENTS DE BARRETTES A MODULES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-16">
		<td align="center"><b>IPDA 83-16</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-16',3)"> REPRISE DE BLINDAGE PAR MANCHON ASNE0160 </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-16 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-16-FR.pdf');">REPRISE DE BLINDAGE PAR MANCHON ASNE0160</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">17/03/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-16 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-16-EN.pdf');">SHIELDING BOND BY SLEEVE ASNE0160</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">27/06/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-16</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM IPDA 83-16.xls');\">";}?>MCQ/QCM REPRISE DE BLINDAGE PAR MANCHON ASNE0160<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2016</td>
	</tr>





	<tr name="IPDA_83-17">
		<td align="center"><b>IPDA 83-17</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-17',3)">DRAINING PLASTIC CONDUITS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-17 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-17_2005-02_A1_EN.pdf');">DRAINING PLASTIC CONDUITS</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">30/06/2005</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-17 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-17.pdf');">DRAINAGE GAINES PLASTIQUES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">29/03/2005</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-17</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-17 A1_with EN.xls');\">";}?>QCM DRAINAGE GAINES PLASTIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-20">
		<td align="center"><b>IPDA 83-20</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-20',2)"> TRAVERSEES ELECTRIQUES COMPOUNDEES NSA 934710</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-20 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-20.pdf');">TRAVERSEES ELECTRIQUES COMPOUNDEES NSA 934710</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">24/11/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-20</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-20 A0_with EN.xls');\">";}?>QCM TRAVERSEES ELECTRIQUES COMPOUNDEES NSA 934710<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-23">
		<td align="center"><b>IPDA 83-23</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-23',3)">TERMINAL EYES NSA936501</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-23 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-23-FR.pdf');">COSSES NSA936501</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">05/02/2007</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-23 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-23-EN.pdf');">TERMINAL EYES NSA936501</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">04/07/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-23</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM IPDA 83-23.xls');\">";}?>MCQ/QCM TERMINAL EYES NSA936501<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/02/2016</td>
	</tr>



	<tr name="IPDA_83-25">
		<td align="center"><b>IPDA 83-25</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-25',3)"> EYE TERMINALS NSA936507  </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-25 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-25-FR.pdf');">COSSES NSA936507</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">30/04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-25 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-25-EN.pdf');">EYE TERMINALS NSA936507 </a></td>
		<td align="center">Ed-A3</td>
		<td align="center">01/08/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-25</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM IPDA 83-25.xls');\">";}?>MCQ/QCM EYE TERMINALS NSA936507<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/02/2016</td>
	</tr>



	<tr name="IPDA_83-29">
		<td align="center"><b>IPDA 83-29</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-29',2)">LUGS ASNE0422 FOR ALUMINIUM CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-29 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-29_2006-04-13_A1.pdf');">LUGS ASNE0422 FOR ALUMINIUM CABLE</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">13/04/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-29</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 83-29_Ind_A1.xlsx');\">";}?>MCQ/QCM LUGS ASNE0422 FOR ALUMINIUM CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/10/2021</td>
	</tr>




	<tr name="IPDA_83-30">
		<td align="center"><b>IPDA 83-30</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-30',2)">ALUMINIUM TERMINALS ASNE0466 FOR ALUMINIUM CABLES </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-30 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-30_2006-07-05_A4.pdf');">ALUMINIUM TERMINALS ASNE0466 FOR ALUMINIUM CABLES </a></td>
		<td align="center">Ed-A4</td>
		<td align="center">05/07/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-30</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 83-30_Ind_A4.xlsx');\">";}?>MCQ/QCM ALUMINIUM TERMINALS ASNE0466 FOR ALUMINIUM CABLES <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/10/2021</td>
	</tr>
	
	
	
	
	<tr name="IPDA_83-36">
		<td align="center"><b>IPDA 83-36</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-36',3)"> CONTACT EN3155-03F/08M/14M/15F/70M/71F</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-36 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-36_2007-03-20_A6_EN.pdf');">CONTACT EN3155-03F/08M/14M/15F/70M/71F</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">14/08/2008</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-36 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-36.pdf');">CONTACT EN3155-03F/08M/14M/15F/70M/71F</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">30/04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-36</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-36 A6_with EN.xls');\">";}?>QCM CONTACT EN3155-03F/08M/14M/15F/70M/71F<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-40">
		<td align="center"><b>IPDA 83-40</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-40',3)">CONNECTORS ASNE0086/0145/0146/0147/0726/0729</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-40 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-40_2007-05-21_A3_EN.pdf');">CONNECTORS ASNE0086/0145/0146/0147/0726/0729</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">14/08/2008</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-40 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-40.pdf');">CONNEXION ASNE 0086/0145/0147/0726/0729</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">30/04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-40</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-40 A3_with EN.xls');\">";}?>QCM CONNEXION ASNE 0086/0145/0147/0726/0729<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-42">
		<td align="center"><b>IPDA 83-42</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-42',3)">CONTACTS EN3155-016M (NSA937910) and ABS1493</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-42 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-42_2006-02-13_A2_EN.pdf');">CONTACTS EN3155-016M (NSA937910) and ABS1493</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/04/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-42 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-42.pdf');">CONT.EN3155-016M (NSA937910) et ABS1493</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/03/2006</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-42</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-42 A2_with EN.xls');\">";}?>QCM CONT.EN3155-016M (NSA937910) et ABS1493<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-43">
		<td align="center"><b>IPDA 83-43</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-43',2)">RACK CONNECTORS ASNE 0161/163/165 ABS 0831</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-43 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-43.pdf');">RACK CONNECTORS ASNE 0161/163/165 ABS 0831</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">17/12/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ IPDA 83-43</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-43 A3_with EN.xls');\">";}?>MCQ RACK CONNECTORS ASNE 0161/163/165 ABS 0831<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>


	
	<tr name="IPDA_83-45">
		<td align="center"><b>IPDA 83-45</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-45',2)">CONNECTEURS COAXIAUX TYPE ?BNC? - NSA 938601</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-45 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-45.pdf');">CONNECTEURS COAXIAUX TYPE ?BNC? - NSA 938601</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">24/11/1999</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-45</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-45 A0_with EN.xls');\">";}?>QCM CONNECTEURS COAXIAUX TYPE ?BNC? - NSA 938601<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	
	<tr name="IPDA_83-62">
		<td align="center"><b>IPDA 83-62</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-62',2)">CNCTR NSA938361 ASNE0362 ABS0713 ABS1145</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-62 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-62_2005-11-15_A2.pdf');">CNCTR NSA938361 ASNE0362 ABS0713 ABS1145</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/12/2005</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-62</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-62 A2.xls');\">";}?>QCM CNCTR NSA938361 ASNE0362 ABS0713 ABS1145<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/02/2022</td>
	</tr>




	<tr name="IPDA_83-64">
		<td align="center"><b>IPDA 83-64</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-64',3)">CLEANING CABLES AND ELECTRICAL COMPONENTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-64 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-64_2004-01-15_A0_EN.pdf');">CLEANING CABLES AND ELECTRICAL COMPONENTS</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">28/04/2004</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-64 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-64.pdf');">NETTOYAGE CABLE ET COMPOSANT ELECTRIQUE </a></td>
		<td align="center">Ed-A0</td>
		<td align="center">05/03/2004</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-64</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_83-64 A0_with EN.xls');\">";}?>QCM NETTOYAGE CABLE ET COMPOSANT ELECTRIQUE <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-90">
		<td align="center"><b>IPDA 83-90</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-90',3)">INSTALLING OPTICAL CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-90 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-90_2017-10_A2_EN.pdf');">INSTALLING OPTICAL CABLES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">10/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-90 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-90_2017-10_A2_FR.pdf');"> INSTALLATION CABLE OPTIQUE </a></td>
		<td align="center">Ed-A2</td>
		<td align="center">10/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-90</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IPDA_83-90 A2_with EN.xls');\">";}?>QCM  INSTALLATION CABLE OPTIQUE <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-92">
		<td align="center"><b>IPDA 83-92</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-92',3)"> MISE EN OEUVRE DES CONTACTS QUADRAX  </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-92 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-92-FR.pdf');"> MISE EN OEUVRE DES CONTACTS QUADRAX  </a></td>
		<td align="center">Ed-A6</td>
		<td align="center">30/04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-92 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-92-EN.pdf');"> INSTALLATION OF QUADRAX CONTACTS</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">14/08/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-92</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ QCM IPDA 83-92.xls');\">";}?>MCQ/QCM INSTALLATION OF QUADRAX CONTACTS <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2016</td>
	</tr>



	<tr name="IPDA_83-94">
		<td align="center"><b>IPDA 83-94</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-94',3)">M.E.O CONTACTS OPTIQUES ABS1379-003MEO OPTICAL CONTACTS ABS1379-003</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-94 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-94_2007-04-04_A0_FR.pdf');">M.E.O CONTACTS OPTIQUES ABS1379-003</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">30/04/2008</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-94 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-94_2007-04-04_A0_EN.pdf');">M.E.O CONTACTS OPTIQUES ABS1379-003MEO OPTICAL CONTACTS ABS1379-003</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/04/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-94</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA_83-94 VersA0.xlsx');\">";}?>MCQ/QCM M.E.O CONTACTS OPTIQUES ABS1379-003MEO OPTICAL CONTACTS ABS1379-003<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/03/2019</td>
	</tr>



	<tr name="IPDA_83-95">
		<td align="center"><b>IPDA 83-95</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-95',2)">USE OF CONTACTS ABS1380/1381 with #24 to 12 Al cable </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-95 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-95_2006-12-12_A3.pdf');">USE OF CONTACTS ABS1380/1381 with #24 to 12 Al cable </a></td>
		<td align="center">Ed-A3</td>
		<td align="center">12/12/2006</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IPDA 83-95</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 83-95_Ind_A3.xlsx');\">";}?>MCQ/QCM USE OF CONTACTS ABS1380/1381 with #24 to 12 Al cable <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/10/2021</td>
	</tr>
	
	
	
	<tr name="IPDA_83-100">
		<td align="center"><b>IPDA 83-100</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-100',3)">ASSEMBLY AND CONNECTION OF TERMINAL BLOCKS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-100 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-100_2006-01-04_A1_EN.pdf');">ASSEMBLY AND CONNECTION OF TERMINAL BLOCKS</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">15/11/2006</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-100 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-100.pdf');"> ASSEMBLAGE ET RACCORDEMENT DES BARRETTES A BORNES </a></td>
		<td align="center">Ed-A1</td>
		<td align="center">01/02/2006</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-100</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-100 A1_with EN.xls');\">";}?>QCM ASSEMBLAGE ET RACCORDEMENT DES BARRETTES A BORNES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-103">
		<td align="center"><b>IPDA 83-103</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-103',2)">INSERTION ET EXTRACTION DES CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-103 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-103.pdf');">INSERTION ET EXTRACTION DES CONTACTS</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">12/03/2004</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-103</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM IPDA 83-103 A0_with EN.xls');\">";}?>QCM INSERTION ET EXTRACTION DES CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/09/2021</td>
	</tr>




	<tr name="IPDA_83-105">
		<td align="center"><b>IPDA 83-105</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_83-105',2)">STRIPPING AD & DR CABLES ABS0949 & EN2267</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 83-105 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_83-105_2006-04-04_A0.pdf');">STRIPPING AD & DR CABLES ABS0949 & EN2267</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/04/2006</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 83-105</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 83-105_Ind_A0.xlsx');\">";}?>MCQ STRIPPING AD & DR CABLES ABS0949 & EN2267<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/10/2021</td>
	</tr>




	<tr name="IPDA_88-01">
		<td align="center"><b>IPDA 88-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IPDA_88-01',3)">MONTAGE TUYAUTERIES POUR CIRCUIT OXYGENE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IPDA 88-01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_88-01_2009-12-22_A4_FR.pdf');">MONTAGE TUYAUTERIES POUR CIRCUIT OXYGENE</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">22/12/2009</td>
	</tr>	
	<tr>
		<td align="center"><b>DOC IPDA 88-01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IPDA_88-01_2009-12-22_A4_EN.pdf');">Installation of oxygen system pipes</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">22/12/2009</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IPDA 88-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IPDA 88-01_Ind_A4.xlsx');\">";}?>QCM MONTAGE TUYAUTERIES POUR CIRCUIT OXYGENE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/10/2021</td>
	</tr>

	
	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIRBUS IDP --------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIRBUS IDP">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS IDP</b></td>
	</tr>
	
<tr name="I+D-F-015">
		<td align="center"><b>I+D-F-015</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-F-015',2)">Huck blind fasteners</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-F-015 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-F-015_1997-02-17_3_huck_blind_fasteners.pdf');">Huck blind fasteners</a></td>
		<td align="center">Ed-D</td>
		<td align="center">15/11/1977</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-F-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM I+D-F-15_mod_3.xls');\">";}?>MCQ/QCM Huck blind fasteners<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/12/2016</td>
	</tr>



<tr name="I+D-P-060">
		<td align="center"><b>I+D-P-060</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-060',2)">Paint</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-060 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-060_2019-12_C7_EN.pdf');">Paint</a></td>
		<td align="center">Ed-C7</td>
		<td align="center">10/12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-060</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_I+D-P-060_Ind_C7.xlsx');\">";}?>MCQ/QCM Paint<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/01/2020</td>
	</tr>



<tr name="I+D-P-067">
		<td align="center"><b>I+D-P-067</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-067',2)">SURFACE PREPARATION FOR NON-STRUCTURAL BONDINGS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-067 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-067_2008-04-11_1_EN.pdf');">SURFACE PREPARATION FOR NON-STRUCTURAL BONDINGS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/04/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-067</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_I+D-P-67_Ind_1.xlsx');\">";}?>MCQ/QCM SURFACE PREPARATION FOR NON-STRUCTURAL BONDINGS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/03/2018</td>
	</tr>



	<tr name="I+D-P-071">
		<td align="center"><b>I+D-P-071</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-071',2)">NON-STRUCTURAL BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-067 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-071_2007-02-12_B_EN.pdf');">NON-STRUCTURAL BONDING</a></td>
		<td align="center">Ed-B</td>
		<td align="center">12/02/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-067</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_I+D-P-071_Ind_B.xlsx');\">";}?>MCQ/QCM NON-STRUCTURAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/08/2018</td>
	</tr>



	<tr name="I+D-P-117">
		<td align="center"><b>I+D-P-117</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-117',2)">Steel and Titanium Hi-Lok Fasteners</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-117 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-117_2007-06-20_7_EN.pdf');">Steel and Titanium Hi-Lok Fasteners</a></td>
		<td align="center">Ed-7</td>
		<td align="center">20/06/2007</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ I+D-P-117</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_I+D-P-117_Ind_06-2007.xlsx');\">";}?>MCQ Steel and Titanium Hi-Lok Fasteners<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/09/2019</td>
	</tr>



	<tr name="I+D-P-188">
		<td align="center"><b>I+D-P-188</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-188',2)">APPLICATION OF FLUORESCENT PAINTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-188 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-188_2016-06-14_A2_EN.pdf');">APPLICATION OF FLUORESCENT PAINTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/06/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-188</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_I+D-P-188_Ind_A2.xlsx');\">";}?>MCQ/QCM APPLICATION OF FLUORESCENT PAINTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/10/2018</td>
	</tr>



	<tr name="I+D-P-202">
		<td align="center"><b>I+D-P-202</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-202',2)">CHEMICAL CONVERSION FILMS ON ALUMINIUM ANO ITS ALLOYS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-202 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-202_2021-01_C2.pdf');">CHEMICAL CONVERSION FILMS ON ALUMINIUM ANO ITS ALLOYS</a></td>
		<td align="center">Ed-C2</td>
		<td align="center">01/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-202</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_I+D-P-202_Ind_C2.xlsx');\">";}?>MCQ/QCM CHEMICAL CONVERSION FILMS ON ALUMINIUM ANO ITS ALLOYS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/03/2021</td>
	</tr>


	<tr name="I+D-P-231">
		<td align="center"><b>I+D-P-231</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-231',2)">Electrical Bonding</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-231 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-231_2018-05-08_B7_EN.pdf');">Electrical Bonding</a></td>
		<td align="center">Ed-B7</td>
		<td align="center">08/05/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ I+D-P-231</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_I+D-P-231_Ind_B.xlsx');\">";}?>MCQ Electrical Bonding<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/08/2019</td>
	</tr>



	<tr name="I+D-P-355">
		<td align="center"><b>I+D-P-355</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-355',2)">BONDING AND GROUNDING INSTALLATION </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-355 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-355_2022-10_C.pdf');">BONDING AND GROUNDING INSTALLATION </a></td>
		<td align="center">Ed-C</td>
		<td align="center">10/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-355</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_I+D-P-355_Ind_C.xlsx');\">";}?>MCQ/QCM BONDING AND GROUNDING INSTALLATION <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/11/2022</td>
	</tr>



	<tr name="I+D-P-387">
		<td align="center"><b>I+D-P-387</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('I+D-P-387',2)">MACHINING COMPOSITES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC I+D-P-387 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('I_D-P-387_2015-12-01_A2_EN.pdf');">MACHINING COMPOSITES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">01/12/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM I+D-P-387</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_I+D-P-387_Ind_A2.xlsx');\">";}?>MCQ/QCM MACHINING COMPOSITES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/08/2018</td>
	</tr>



	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIRBUS ABP --------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIRBUS ABP">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS ABP</b></td>
	</tr>


<tr name="ABP 2-1067">
		<td align="center"><b>ABP 2-1067</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 2-1067',2)">RIVETING WITH BLIND RIVETS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 2-1067 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_2-1067_2014-06_3.pdf');">RIVETING WITH BLIND RIVETS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">NA</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 2-1067</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_2-1067_Issue_3.xlsx');\">";}?>MCQ/QCM RIVETING WITH BLIND RIVETS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/06/2018</td>
	</tr>


<tr name="ABP 2-1069">
		<td align="center"><b>ABP 2-1069</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 2-1069',2)">Riveting with Blind Flush Break Rivets (Avdel MBC)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 2-1069 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_2-1069_2010-03_1-4.pdf');">Riveting with Blind Flush Break Rivets (Avdel MBC)</a></td>
		<td align="center">Ed-1</td>
		<td align="center">NA</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 2-1069</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_2-1069_Issue_1.xlsx');\">";}?>MCQ/QCM Riveting with Blind Flush Break Rivets (Avdel MBC)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/06/2018</td>
	</tr>


<tr name="ABP 2-2075">
		<td align="center"><b>ABP 2-2075</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 2-2075',2)">MANUAL FASTENING WITH SHORT THREAD POINT DRIVE BOLTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 2-2075 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_2-2075_2021-11-19_10.pdf');">MANUAL FASTENING WITH SHORT THREAD POINT DRIVE BOLTS</a></td>
		<td align="center">Ed-10</td>
		<td align="center">19/11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 2-2075</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_2-2075_Ind_10.xlsx');\">";}?>MCQ/QCM MANUAL FASTENING WITH SHORT THREAD POINT DRIVE BOLTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2021</td>
	</tr>



<tr name="ABP 2-2081">
		<td align="center"><b>ABP 2-2081</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 2-2081',2)">MANUAL FASTENING WITH 4 START OR 2 START QUICK RELEASE FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 2-2081 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_2-2075_2017-10-03_7.pdf');">MANUAL FASTENING WITH 4 START OR 2 START QUICK RELEASE FASTENERS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">14/08/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 2-2081</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_2-2081_2017-08-14_3.xlsx');\">";}?>MCQ/QCM MANUAL FASTENING WITH 4 START OR 2 START QUICK RELEASE FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2018</td>
	</tr>



<tr name="ABP 2-2087">
		<td align="center"><b>ABP 2-2087</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 2-2087',2)">FASTENING  OF  JOINTS  CONTAINING SEALANT  BY  TORQUE  TIGHTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 2-2087 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_2-2087_1993-08_1.pdf');">FASTENING  OF  JOINTS  CONTAINING SEALANT  BY  TORQUE  TIGHTENING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/1993</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 2-2087</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_2-2087_Issue_1.xlsx');\">";}?>MCQ/QCM FASTENING  OF  JOINTS  CONTAINING SEALANT  BY  TORQUE  TIGHTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/06/2018</td>
	</tr>



<tr name="ABP 4-2128">
		<td align="center"><b>ABP 4-2128</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 4-2128',2)">APPLICATION OF PAINTS AND VARNISHES FOR OVERCOATING OF SEALANTS AND PART MARKINGS AND FOR IDENTIFICATION PURPOSES </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 4-2128 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_4-2128_2018-07-04_11_EN.pdf');">APPLICATION OF PAINTS AND VARNISHES FOR OVERCOATING OF SEALANTS AND PART MARKINGS AND FOR IDENTIFICATION PURPOSES </a></td>
		<td align="center">Ed-11</td>
		<td align="center">04/07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 4-2128</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_4-2128_Issue_11.xlsx');\">";}?>MCQ/QCM APPLICATION OF PAINTS AND VARNISHES FOR OVERCOATING OF SEALANTS AND PART MARKINGS AND FOR IDENTIFICATION PURPOSES <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/08/2018</td>
	</tr>




<tr name="ABP 2-2336">
		<td align="center"><b>ABP 2-2336</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 2-2336',2)">COMMON REQUIREMENTS FOR INSTALLING BOLTS, STUDS, NUTS AND OTHER FASTENING PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 2-2336 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_2-2336_2021-11-19_9.pdf');">COMMON REQUIREMENTS FOR INSTALLING BOLTS, STUDS, NUTS AND OTHER FASTENING PARTS</a></td>
		<td align="center">Ed-9</td>
		<td align="center">19/11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 2-2336</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_2-2336_Issue_9.xlsx');\">";}?>MCQ/QCM COMMON REQUIREMENTS FOR INSTALLING BOLTS, STUDS, NUTS AND OTHER FASTENING PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2021</td>
	</tr>




<tr name="ABP 4-3329">
		<td align="center"><b>ABP 4-3329</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 4-3329',2)">APPLICATION OF AERODYNAMIC FILLER SEALANT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 4-3329 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_4-3329_2012-06_4.pdf');">APPLICATION OF AERODYNAMIC FILLER SEALANT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">07/06/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 4-3329</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_4-3329_Issue_4.xlsx');\">";}?>MCQ/QCM APPLICATION OF AERODYNAMIC FILLER SEALANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/06/2018</td>
	</tr>


	<tr name="ABP 4-5141">
		<td align="center"><b>ABP 4-5141</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 4-5141',2)">APPLICATION OF SEALANT FOR INTEGRAL FUEL TANKS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 4-5141 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_4-5141_2021-09-28_18.pdf');">APPLICATION OF SEALANT FOR INTEGRAL FUEL TANKS</a></td>
		<td align="center">Ed-18</td>
		<td align="center">28/09/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 4-5141</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_4-5141_Issue_18.xlsx');\">";}?>MCQ/QCM APPLICATION OF SEALANT FOR INTEGRAL FUEL TANKS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/11/2021</td>
	</tr>


	<tr name="ABP 4-5142">
		<td align="center"><b>ABP 4-5142</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 4-5142',2)">APPLICATION OF SEALANT FOR GENERAL AIRCRAFT STRUCTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 4-5142 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_4-5142_2022-01-04_19.pdf');">APPLICATION OF SEALANT FOR GENERAL AIRCRAFT STRUCTURE</a></td>
		<td align="center">Ed-19</td>
		<td align="center">04/01/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 4-5142</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_4-5142_Issue_19.xlsx');\">";}?>MCQ/QCM APPLICATION OF SEALANT FOR GENERAL AIRCRAFT STRUCTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/01/2022</td>
	</tr>


	<tr name="ABP 4-5144">
		<td align="center"><b>ABP 4-5144</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 4-5144',2)">APPLICATION OF NON-HARDENING JOINTING COMPOUNDS AS SEALANT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 4-5144 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_4-5144_2016-01_5_EN.pdf');">APPLICATION OF NON-HARDENING JOINTING COMPOUNDS AS SEALANT</a></td>
		<td align="center">Ed-5</td>
		<td align="center">01/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 4-5144</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_4-5144_Ind_5.xlsx');\">";}?>MCQ/QCM APPLICATION OF NON-HARDENING JOINTING COMPOUNDS AS SEALANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/07/2018</td>
	</tr>


	<tr name="ABP 6-3204">
		<td align="center"><b>ABP 6-3204</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 6-3204',2)">RECTIFICATION OF SCRATCHES ON AN ALUMINIUM CLAD SURFACE DURING FABRICATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 6-3204 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_6-3204_2020-01-23_6_EN.pdf');">RECTIFICATION OF SCRATCHES ON AN ALUMINIUM CLAD SURFACE DURING FABRICATION</a></td>
		<td align="center">Ed-6</td>
		<td align="center">23/01/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ ABP 6-3204</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_ABP_6-3204_5.xlsx');\">";}?>MCQ RECTIFICATION OF SCRATCHES ON AN ALUMINIUM CLAD SURFACE DURING FABRICATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/10/2018</td>
	</tr>


	<tr name="ABP 7-1245">
		<td align="center"><b>ABP 7-1245</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 7-1245',2)">ELECTRICAL BONDING OF METALLIC MATERIAL: ASSEMBLY METHODS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 7-1245 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_7-1245_2018-12-20_9_EN.pdf');">ELECTRICAL BONDING OF METALLIC MATERIAL: ASSEMBLY METHODS</a></td>
		<td align="center">Ed-9</td>
		<td align="center">20/12/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 7-1245</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP-7-1245_Issue_9.xlsx');\">";}?>MCQ/QCM ELECTRICAL BONDING OF METALLIC MATERIAL: ASSEMBLY METHODS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/01/2019</td>
	</tr>


	<tr name="ABP 7-1246">
		<td align="center"><b>ABP 7-1246</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('ABP 7-1246',2)">ELECTRICAL BONDING AND EARTHING FOR AIRBUS REQUIREMENTS DURING ASSEMBLY </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC ABP 7-1246 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ABP_7-1246_2012-05_9.pdf');">ELECTRICAL BONDING AND EARTHING FOR AIRBUS REQUIREMENTS DURING ASSEMBLY </a></td>
		<td align="center">Ed-9</td>
		<td align="center">NA</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM ABP 7-1246</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_ABP_7-1246_Issue_9.xlsx');\">";}?>MCQ/QCM ELECTRICAL BONDING AND EARTHING FOR AIRBUS REQUIREMENTS DURING ASSEMBLY <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/07/2018</td>
	</tr>


	
	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIRBUS AIPI/AIPS --------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="AIRBUS AIPI/AIPS/AITM">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS AIPI / AIPS / AITM</b></td>
	</tr>



	<tr name="AIPI_01-01-004">
		<td align="center"><b>AIPI 01-01-004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-01-004',1)">Installation of Solid Rivets</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-01-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-01-004_2019-08_A5.pdf');">Installation of Solid Rivets</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">28/08/2019</td>
	</tr>


	<tr name="AIPI_01-02-002">
		<td align="center"><b>AIPI 01-02-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-002',3)">Installation of Taper Shank Bolts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-002_2013-09_A0.pdf');">Installation of Taper Shank Bolts</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-002_2013-09_3.pdf');">Installation of Taper Shank Bolts</a></td>
		<td align="center">Ed-3</td>
		<td align="center">09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-002_Ind_A0&3.xlsx');\">";}?>MCQ/QCM Installation of Taper Shank Bolts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>



	<tr name="AIPI_01-02-003">
		<td align="center"><b>AIPI 01-02-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-003',4)">PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-003.pdf');">PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-003 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-003_2016-01_A1_FR.pdf');">Préparation des trous dans les matériaux métalliques pour fixation</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">17/11/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-003_2019-05_8_EN.pdf');">PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING</a></td>
		<td align="center">Ed-8</td>
		<td align="center">05/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_01-02-003_Ind_A1&8.xls');\">";}?>MCQ/QCM PREPARATION OF HOLES IN METALLIC MATERIALS FOR FASTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/05/2019</td>
	</tr>




	<tr name="AIPI_01-02-005">
		<td align="center"><b>AIPI 01-02-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-005',3)">PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-005_2022-05_A5.pdf');">PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">05/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-005_12.pdf');">PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING</a></td>
		<td align="center">Ed-12</td>
		<td align="center">10/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_01-02-005_Ind_A5&12.xls');\">";}?>MCQ/QCM PREPARATION OF HOLES IN FIBRE REINFORCED PLASTIC(FRP) AND MIXED (FRP/Metal) ASSEMBLIES FOR FASTENING (QI)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/05/2022</td>
	</tr>




	<tr name="AIPI_01-02-006">
		<td align="center"><b>AIPI 01-02-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-006',4)">INSTALLATION OF LOCKBOLTS PULL TYPE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-006.pdf');">POSE DE FIXATIONS LOCKBOLTS DE TYPE "PULL" ET "STUMP"</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">03/04/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-006 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-006_2015-01_A2_FR.pdf');">POSE DE FIXATIONS LOCKBOLTS  DE TYPE "PULL" ET "STUMP"</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">03/04/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-006_2021-09_6.pdf');">INSTALLATION OF LOCKBOLTS PULL TYPE</a></td>
		<td align="center">Ed-6</td>
		<td align="center">09/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-006_Ind_A2&6.xlsx');\">";}?>MCQ/QCM INSTALLATION OF LOCKBOLTS PULL TYPE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/10/2021</td>
	</tr>




	<tr name="AIPI_01-02-008">
		<td align="center"><b>AIPI 01-02-008</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-02-008',3)">TORQUE TIGHTENING OF SCREWS, BOLTS, AND NUTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-008_2021-09_A7.pdf');">TORQUE TIGHTENING OF SCREWS, BOLTS, AND NUTS</a></td>
		<td align="center">Ed-A7</td>
		<td align="center">09/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-008_2021-12_18.pdf');">TORQUE TIGHTENING OF SCREWS, BOLTS, AND NUTS</a></td>
		<td align="center">Ed-18</td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-008_Ind_A7&18.xlsx');\">";}?>MCQ/QCM TORQUE TIGHTENING OF SCREWS, BOLTS, AND NUTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2022</td>
	</tr>



	<tr name="AIPI_01-02-013">
		<td align="center"><b>AIPI 01-02-013</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-013',3)">INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-013 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-013_2017-08_A4.pdf');">INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">30/08/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-013 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-013_2017-01_5.pdf');">INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"</a></td>
		<td align="center">Ed-5</td>
		<td align="center">01/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-013</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_AIPI-AIPS_01-02-013_Ind_A4&5.xls');\">";}?>MCQ/QCM INSTALLATION OF BLIND BOLTS AND BLIND RIVETS "PULL-TYPE"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/10/2017</td>
	</tr>



<tr name="AIPI_01-02-014">
		<td align="center"><b>AIPI 01-02-014</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-014',3)">Installation of cold expanded Retainers</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-014 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-014_2010-04_2.pdf');">Installation of cold expanded Retainers</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-014 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-014_2010-04_3.pdf');">Installation of cold expanded Retainers</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-014</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-014_Ind_2&3.xlsx');\">";}?>MCQ/QCM Installation of cold expanded Retainers<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/09/2017</td>
	</tr>




	<tr name="AIPI_01-02-015">
		<td align="center"><b>AIPI 01-02-015</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-015',3)">INSTALLATION OF BLIND BOLTS THREADED TYPE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-015 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-015_2020-01_A5_EN.pdf');">INSTALLATION OF BLIND BOLTS THREADED TYPE</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">23/01/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-015 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-015_2023-02_8.pdf');">INSTALLATION OF BLIND BOLTS THREADED TYPE</a></td>
		<td align="center">Ed-8</td>
		<td align="center">02/2023</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-015_Ind_A5&8.xlsx');\">";}?>MCQ/QCM INSTALLATION OF BLIND BOLTS THREADED TYPE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/11/2023</td>
	</tr>



	<tr name="AIPI_01-02-016">
		<td align="center"><b>AIPI 01-02-016</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-016',4)">INSTALLATION OF RIVETLESS NUTPLATES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-016 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-016.pdf');">INSTALLATION OF RIVETLESS NUTPLATES</a></td>
		<td align="center">A3</td>
		<td align="center">04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-016 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-016_2013-04_A3_FR.pdf');">INSTALLATION D’ECROUS FLOTTANTS SANS RIVET</a></td>
		<td align="center">A3</td>
		<td align="center">04/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-016 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-016_2021-04_6.pdf');">INSTALLATION OF RIVETLESS NUTPLATES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 01-02-016</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_01-02-016_Ind_A3&6.xls');\">";}?>MCQ INSTALLATION OF RIVETLESS NUTPLATES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2021</td>
	</tr>



	<tr name="AIPI_01-02-017">
		<td align="center"><b>AIPI 01-02-017</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-017',3)">GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-017 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-017_2021-07_A4.pdf');">GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">07/2021</td>
	
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-017 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-017_6.pdf');">GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">02/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-017</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_01-02-017_Ind_A4&6.xlsx');\">";}?>MCQ/QCM GENERAL ASSEMBLY AND INSTALLATION OF FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/07/2021</td>
	</tr>




	<tr name="AIPI_01-02-019">
		<td align="center"><b>AIPI 01-02-019</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-02-019',4)"> Installation of Bushes shrink and Press Fit</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-019 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-019_2022-06_A2_FR.pdf');">Montage des bagues, axes, logements de palier et arbres par « différence de température » ou « à la presse »</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-019 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-019_2022-06_A2_EN.pdf');"> Installation of Bushes shrink and Press Fit</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-019 </b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-019_2021-04_5.pdf');"> Installation of Bushes shrink and Press Fit</a></td>
		<td align="center">Ed-5</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI AIPS 01-02-019</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_01-02-019 Ind A2&5.xls');\">";}?>MCQ/QCM  Installation of Bushes shrink and Press Fit<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/07/2022</td>
	</tr>



	<tr name="AIPI_01-02-022">
		<td align="center"><b>AIPI 01-02-022</b></td>
		<td colspan="6"><a href="javascript:onclick=Voir_TR('AIPI_01-02-022',3)">INSTALLING THREADED CYLINDRICAL FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-022_2021-11_A5.pdf');">INSTALLING THREADED CYLINDRICAL FASTENERS</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-022.pdf');">INSTALLING THREADED CYLINDRICAL FASTENERS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-022_Ind_A5&4.xls');\">";}?>MCQ/QCM INSTALLING THREADED CYLINDRICAL FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2021</td>
	</tr>




	<tr name="AIPI_01-02-027">
		<td align="center"><b>AIPI 01-02-027</b></td>
		<td colspan="6"><a href="javascript:onclick=Voir_TR('AIPI_01-02-027',4)">Installation of blind rivet nuts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-027 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-027_2016-05_A2_EN.pdf');">Installation of blind rivet nuts</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-027 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-027_2016-05_A2_FR.pdf');">MONTAGE DES ECROUS RIVETS AVEUGLES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-027 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-027_2014-10_3_EN.pdf');">Installation of blind rivet nuts</a></td>
		<td align="center">Ed-3</td>
		<td align="center">10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AIPI/AIPS 01-02-027</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AIPI-AIPS_01-02-027_Ind_A2&3.xlsx');\">";}?>QCM Installation of blind rivet nuts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/06/2019</td>
	</tr>

	
	
		<tr name="AIPI_01-02-029">
		<td align="center"><b>AIPI 01-02-029</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-02-029',3)">TORQUE, TIGHTENING TORQUE OF SCREWS, BOLTS AND NUTS FOR ASSEMBLY OF BRACKETS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-02-029 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-02-029_2021-04_A2.pdf');">TORQUE, TIGHTENING TORQUE OF SCREWS, BOLTS AND NUTS FOR ASSEMBLY OF BRACKETS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-02-029 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-02-029_2020-12_5.pdf');">TORQUE, TIGHTENING TORQUE OF SCREWS, BOLTS AND NUTS FOR ASSEMBLY OF BRACKETS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">12/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-02-029</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-02-029_Ind_A2&5.xlsx');\">";}?>MCQ/QCM TORQUE, TIGHTENING TORQUE OF SCREWS, BOLTS AND NUTS FOR ASSEMBLY OF BRACKETS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>



		<tr name="AIPI_01-03-001">
		<td align="center"><b>AIPI 01-03-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-03-001',3)">Installation of cold-expanded bushes into non-metallic materials</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-03-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-001_2010-04_2.pdf');">Installation of cold-expanded bushes into non-metallic materials</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-03-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-001_2010-04_3.pdf');">Installation of cold-expanded bushes into non-metallic materials</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-03-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-03-001_Ind_2&3.xlsx');\">";}?>MCQ/QCM Installation of cold-expanded bushes into non-metallic materials<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>





	<tr name="AIPI_01-03-002">
		<td align="center"><b>AIPI 01-03-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-03-002',4)">MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-03-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-002.pdf');">MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-03-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-002_2010-05_1_FR.pdf');">Installation manuelle des fixations à démontage rapide à 2 ou 4 encoches avec ou sans bague Acres </a></td>
		<td align="center">Ed-1</td>
		<td align="center">26/01/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-03-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-002.pdf');">MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">02/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-03-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_01-03-002_Ind_1&6.xlsx');\">";}?>MCQ/QCM MANUEL FASTENING OF 2- OR 4- START QUICK RELEASE FASTENERS WITH OR WITHOUT ACRES SLEEVES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/06/2020</td>
	</tr>




	<tr name="AIPI_01-03-003">
		<td align="center"><b>AIPI 01-03-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_01-03-003',3)">INSTALLATION OF INSERTS IN NON-METALLICS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-03-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-003.pdf');">INSTALLATION OF INSERTS IN NON-METALLICS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-03-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-003.pdf');">INSTALLATION OF INSERTS IN NON-METALLICS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-03-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_AIPS_01-03-003_Ind_2&3.xls');\">";}?>MCQ/QCM INSTALLATION OF INSERTS IN NON-METALLICS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2014</td>
	</tr>



	<tr name="AIPI_01-03-004">
		<td align="center"><b>AIPI 01-03-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-03-004',3)">INSTALLATION OF HELICOIL THREADED INSERT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-03-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-004.pdf');">INSTALLATION OF HELICOIL THREADED INSERT</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-03-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-004.pdf');">INSTALLATION OF HELICOIL THREADED INSERT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">03/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 01-03-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM-AIPI_AIPS_01-03-004.xls');\">";}?>MCQ/QCM INSTALLATION OF HELICOIL THREADED INSERT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2014</td>
	</tr>



	<tr name="AIPI_01-03-005">
		<td align="center"><b>AIPI 01-03-005</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_01-03-005',4)">INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 01-03-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_01-03-005.pdf');">INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)</a></td>
		<td align="center">Ed-02</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 01-03-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_01-03-005.pdf');">INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)</a></td>
		<td align="center">Ed-4</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 01-03-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-005_EN.xls');\">";}?>MCQ INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AIPI/AIPS 01-03-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_01-03-005_FR.xls');\">";}?>QCM INSTALLATION OF METALLIC INSERT (ACRES SLEEVES)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/08/2014</td>
	</tr>




	<tr name="AIPI_02-01-003">
		<td align="center"><b>AIPI 02-01-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_02-01-003',3)">Protection contre la corrosion et traitement avant peinture par anodisation tartrique sulfurique des alliages d’aluminium</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-01-003 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI-02-01-003_2020-11_A4.pdf');">Protection contre la corrosion et traitement avant peinture par anodisation tartrique sulfurique des alliages d’aluminium</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">11/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-01-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-01-003_2020-03_3.pdff');">Tartaric Sulphuric Anodizing (TSA) of aluminum alloys for corrosion protection and paint pre-treatment</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2020</td>
	</tr>
	</tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-01-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM AIPI-AIPS_02-01-003_2020-11_A4_3.xlsx');\">";}?>MCQ/QCM Protection contre la corrosion et traitement avant peinture par anodisation tartrique sulfurique des alliages d’aluminiumg<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/02/2022</td>
	</tr>
	
	
	

<tr name="AIPI_02-01-006">
		<td align="center"><b>AIPI 02-01-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_02-01-006',3)">Phosphoric Sulphuric Anodizing (PSA) of Aluminum Alloys Prior to Structural Bonding</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-01-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-01-006_A3.pdf');">Phosphoric Sulphuric Anodizing (PSA) of Aluminum Alloys Prior to Structural Bonding</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">02/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-01-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-01-006_2019-07_3.pdf');">Phosphoric Sulphuric Anodizing (PSA) of Aluminum Alloys Prior to Structural Bonding</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/2019</td>
	</tr>
	</tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-01-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_02-01-006_Ind_A3&3.xlsx');\">";}?>MCQ/QCM Phosphoric Sulphuric Anodizing (PSA) of Aluminum Alloys Prior to Structural Bonding<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/02/2021</td>
	</tr>


<tr name="AIPI_02-02-002">
		<td align="center"><b>AIPI 02-02-002</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_02-02-002',4)">Dry Blasting</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-002_2010-04_2_FR.pdf');">Sablage à Sec</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-002_2010-04_2_EN.pdf');">Dry Blasting</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-02-002_2010-04_2_EN.pdf');">Dry Blasting</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-02-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_02-02-002_Ind_2&2.xlsx');\">";}?>MCQ/QCM Dry Blasting<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/03/2017</td>
	</tr>



		<tr name="AIPI_02-02-003">
		<td align="center"><b>AIPI 02-02-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_02-02-003',3)">WET BLASTING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-003_2013-03_A1.pdf');">WET BLASTING</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">03/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-02-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-02-003_2021-03_4.pdf');">WET BLASTING</a></td>
		<td align="center">Ed-4</td>
		<td align="center">03/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-02-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_02-02-003_Ind_A1&4.xlsx');\">";}?>MCQ/QCM WET BLASTING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>




<tr name="AIPI_02-02-005">
		<td align="center"><b>AIPI 02-02-005</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_02-02-005',4)">Cold Expansion in Metallic Materials – Split Sleeve Process</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-005 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-005_2010-04_2_FR.pdf');">Expansion à froid dans les matériaux métalliques – Méthode de la bague fendue</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-005_2010-04_2_EN.pdf');">Cold Expansion in Metallic Materials – Split Sleeve Process</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-02-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-02-005_2019-05_4_EN.pdf');">Cold Expansion in Metallic Materials – Split Sleeve Process</a></td>
		<td align="center">Ed-4</td>
		<td align="center">02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-02-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_02-02-005_Ind_2&4.xlsx');\">";}?>MCQ/QCM Cold Expansion in Metallic Materials – Split Sleeve Process<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/05/2019</td>
	</tr>



	<tr name="AIPI_02-02-010">
		<td align="center"><b>AIPI 02-02-010</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_02-02-010',4)"> Installation of Bushings by Cold Expansion Process in Metallic Materials</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-010 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-010-FR.pdf');">Installation de bagues par expansion ? froid dans les mat?riaux m?talliques</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">01/10/2014</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-02-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-02-010-EN.pdf');"> Installation of Bushings by Cold Expansion Process in Metallic Materials</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">01/10/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-02-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-02-010.pdf');"> Installation of Bushings by Cold Expansion Process in Metallic Materials</a></td>
		<td align="center">Ed-3</td>
		<td align="center">08/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-02-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM AIPI_AIPS_02-02-010_A2.xls');\">";}?> MCQ/QCM Installation of Bushings by Cold Expansion Process in Metallic Materials<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/12/2015</td>
	</tr>


	

	<tr name="AIPI_02-05-001">
		<td align="center"><b>AIPI 02-05-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_02-05-001',4)">CHEMICAL CONVERSION COATING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-05-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-05-001_2010-04_2.pdf');">CHEMICAL CONVERSION COATING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">27/04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 02-05-001 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_02-05-001_2010-04_2_FR.pdf');">Conversion chimique par chromatation</a></td>
		<td align="center">Ed-2</td>
		<td align="center">08/02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 02-05-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_02-05-001.pdf');">CHEMICAL CONVERSION COATING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 02-05-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_02-05-001_Ind_2&3.xlsx');\">";}?>MCQ/QCM CHEMICAL CONVERSION COATING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">31/01/2018</td>
	</tr>




	<tr name="AIPI_03-01-010">
		<td align="center"><b>AIPI 03-01-010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-01-010',4)">MANUFACTURING OF PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-01-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-01-010_2022-07_A2.pdf');">MANUFACTURING OF PIPES</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">07/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-01-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-01-010_2021-12_5.pdf');">MANUFACTURING OF PIPES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 03-01-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-01-010_Ind_A2&5.xlsx');\">";}?>MCQ MANUFACTURING OF PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/09/2022</td>
	</tr>
	<tr>
		<td align="center"><b>[Light] MCQ/QCM AIPI/AIPS 03-01-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-01-010_Ind_A2&5-Light.xlsb');\">";}?>MCQ/QCM MANUFACTURING OF PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/09/2022</td>
	</tr>




	<tr name="AIPI_03-01-012">
		<td align="center"><b>AIPI 03-01-012</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-01-012',4)">EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-01-012 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-01-012_2020-09_A8.pdf');">EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">09/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-01-012 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-01-012.pdf');">EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">03/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-01-012</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-01-012_Ind_A8&4.xlsx');\">";}?>MCQ/QCM EXTERNAL SWAGING OF FITTINGS ON 5080 PSI TITANIUM TUBES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>
	


	<tr name="AIPI_03-03-001">
		<td align="center"><b>AIPI 03-03-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-03-001',3)">REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-03-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-001_2010-05_2.pdf');">REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-03-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-03-001.pdf');">REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-03-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-03-001_Ind_2&5.xlsx');\">";}?>MCQ/QCM REWORK OF SCRATCHES IN ALUMINIUM AND ALUMINIUM ALLOYS ON EXTERNAL SURFACES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/10/2021</td>
	</tr>



	<tr name="AIPI_03-03-010">
		<td align="center"><b>AIPI 03-03-010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-03-010',4)">Application of Textile Floor Coverings</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-03-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-010_2010-11_1.pdf');">Application of Textile Floor Coverings</a></td>
		<td align="center">Ed-1</td>
		<td align="center">11/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-03-010 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-010_2010-11_1_FR.pdf');">Pose de Revêtements de Sol Textiles</a></td>
		<td align="center">Ed-1</td>
		<td align="center">15/03/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-03-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-03-010_2010-05_2.pdf');">Application of Textile Floor Coverings</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-03-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-03-010_Ind_1&2.xls');\">";}?>MCQ/QCM Application of Textile Floor Coverings<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center"></td>
	</tr>



	<tr name="AIPI_03-03-012">
		<td align="center"><b>AIPI 03-03-012</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-03-012',3)">Installation of bearings, spherical bearings and bushes by swaging</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-03-012 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-012_2013-09_A2.pdf');">Installation of bearings, spherical bearings and bushes by swaging</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">24/09/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-03-012 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-03-012_2021-03_6.pdf');">Installation of bearings, spherical bearings and bushes by swaging</a></td>
		<td align="center">Ed-6</td>
		<td align="center">03/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-03-012</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_AIPS_03-03-012_Ind_A2&6.xlsx');\">";}?>MCQ/QCM Application of Textile Floor Coverings<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2021</td>
	</tr>




	<tr name="AIPI_03-03-014">
		<td align="center"><b>AIPI 03-03-014</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-03-014',4)">Bonding of bearings and bushes by anaerobic compound</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-03-014 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-014_2016-03_A1.pdf');">Scellement de bagues et de paliers par application de mastic anaérobie</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-03-014 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-03-014_2016-03_A1_FR.pdf');">Scellement de bagues et de paliers par application de mastic anaérobie</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">23/06/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-03-014 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-03-014.pdf');">Bonding of bearings and bushes by anaerobic compound</a></td>
		<td align="center">Ed-5</td>
		<td align="center">02/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-03-014</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-03-03-014 _Ind_A1_&_5.xls');\">";}?>MCQ/QCM Bonding of bearings and bushes by anaerobic compound<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/06/2020</td>
	</tr>




	<tr name="AIPI_03-06-001">
		<td align="center"><b>AIPI 03-06-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-001',3)">SCUFF-PLATE AND ANTI-CHAFING PLATE INSTALLATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI 03-06-001.FR.pdf');">SCUFF-PLATE AND ANTI-CHAFING PLATE INSTALLATION</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">08/2011</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-001.pdf');">SCUFF-PLATE AND ANTI-CHAFING PLATE INSTALLATION</a></td>
		<td align="center">Ed-3</td>
		<td align="center">11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM 03-06-001.xls');\">";}?>MCQ/QCM SCUFF-PLATE AND ANTI-CHAFING PLATE INSTALLATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/11/2015</td>
	</tr>
	




	<tr name="AIPI_03-06-007">
		<td align="center"><b>AIPI 03-06-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-007',3)">EXTERNAL SWAGED TUBE FITTINGS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-007_2021-12_A8.pdf');">EXTERNAL SWAGED TUBE FITTINGS</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-007_2021-06_10.pdf');">EXTERNAL SWAGED TUBE FITTINGS</a></td>
		<td align="center">Ed-10</td>
		<td align="center">06/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-007_Ind_A8&10.xlsx');\">";}?>MCQ/QCM EXTERNAL SWAGED TUBE FITTINGS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2023</td>
	</tr>




	<tr name="AIPI_03-06-008">
		<td align="center"><b>AIPI 03-06-008</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-008',3)">INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-008_2022-03_A5.pdf');">INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">03/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-008_2022-03_10.pdf');">INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES</a></td>
		<td align="center">Ed-10</td>
		<td align="center">03/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-008_Ind_A5&10.xlsx');\">";}?>MCQ/QCM INSTALLATION OF RIGID HYDRAULIC PIPES AND FLEXIBLE HOSES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/04/2022</td>
	</tr>




	<tr name="AIPI_03-06-009">
		<td align="center"><b>AIPI 03-06-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-009',3)">SHIM FOR ASSEMBLY</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-009_2019-12_A5_EN.pdf');">SHIM FOR ASSEMBLY</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">09/12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-009.pdf');">SHIM FOR ASSEMBLY</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-009_Ind_A5&3.xlsx');\">";}?>MCQ/QCM SHIM FOR ASSEMBLY<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/02/2020</td>
	</tr>




	<tr name="AIPI_03-06-010">
		<td align="center"><b>AIPI 03-06-010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-010',4)">INSTALLATION OF OXYGEN PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-010_2021-03_A5.pdf');">INSTALLATION OF OXYGEN PIPES</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">03/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-010_2021-08_6.pdf');">INSTALLATION OF OXYGEN PIPES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">08/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM-AIPI_AIPS_03-06-010_Ind_A5&6.xls');\">";}?>MCQ/QCM INSTALLATION OF OXYGEN PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>FT AIPI 03-06-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('FT AIPI-03_06_010 issue A2.pptx');\">";}?>SYNTHESIS SHEET INSTALLATION OF OXYGEN PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/10/2015</td>
	</tr>



<tr name="AIPI_03-06-011">
		<td align="center"><b>AIPI 03-06-011</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-011',3)">INSTALLATION OF FUEL PIPES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-011 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-011_2020-09_A6_EN.pdf');">INSTALLATION OF FUEL PIPES</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">09/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-011 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-011.pdf');">INSTALLATION OF FUEL PIPES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">02/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-011</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-011_Ind_A6&6.xlsx');\">";}?>MCQ/QCM INSTALLATION OF FUEL PIPES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/09/2020</td>
	</tr>
	


	<tr name="AIPI_03-06-015">
		<td align="center"><b>AIPI 03-06-015</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-06-015',3)">TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-015 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-015_2021-05_A3.pdf');">TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">05/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-015 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-015_7.pdf');">TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS</a></td>
		<td align="center">Ed-7</td>
		<td align="center">10/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-015_Ind_A3&7.xlsx');\">";}?>MCQ/QCM TORQUE TIGHTENING OF A380 AND A350 HYDRAULIC CONNECTIONS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/05/2021</td>
	</tr>



	<tr name="AIPI_03-06-018">
		<td align="center"><b>AIPI 03-06-018</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-06-018',4)">Hot gas welding of Non-textile Floor Coverings made of Polyvinyl Chloride</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-018 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-018_2022-04_A2_FR.pdf');">Soudage de Rev?tements de Sol non-textiles en Polychlorure de Vinyle</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">04/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-018 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-018_2022-04_A2_EN.pdf');">Hot gas welding of Non-textile Floor Coverings made of Polyvinyl Chloride</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">04/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-018 (EN</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-018_2021-08_3.pdf');">Hot gas welding of Non-textile Floor Coverings made of Polyvinyl Chloride</a></td>
		<td align="center">Ed-3</td>
		<td align="center">08/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 03-06-018</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ AIPI_AIPS_03-06-018 ind A2 - 3.xls');\">";}?> MCQ Hot gas welding of Non-textile Floor Coverings made of Polyvinyl Chloride<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2022</td>
	</tr>



	<tr name="AIPI_03-06-019">
		<td align="center"><b>AIPI 03-06-019</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-06-019',3)">Application of Non-Textile Floor coverings (NTF)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-019 (EN) </b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-019_2022-04_A1.pdf');">Application of Non-Textile Floor coverings (NTF)</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">04/2022</td>
	</tr>
		<tr>
		<td align="center"><b>DOC AIPS 03-06-019 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-019_2021-08_3.pdf');">Application of Non-Textile Floor coverings (NTF)</a></td>
		<td align="center">Ed-3</td>
		<td align="center">08/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 03-06-019</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ AIPI_AIPS_03-06-019_Ind_A1&3.xls');\">";}?>MCQ Application of Non-Textile Floor coverings (NTF)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2022</td>
	</tr>



	<tr name="AIPI_03-06-020">
		<td align="center"><b>AIPI 03-06-020</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-06-020',3)">INSTALLATION OF AIR CONDITIONING DUCTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-020 </b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-020_2022-02_A7.pdf');">INSTALLATION OF AIR CONDITIONING DUCTS</a></td>
		<td align="center">Ed-A7</td>
		<td align="center">02/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-020 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-020_2021-12_5.pdf');">INSTALLATION OF AIR CONDITIONING DUCTS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-020</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-020_Ind_A7&5.xlsx');\">";}?>MCQ/QCM INSTALLATION OF AIR CONDITIONING DUCTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">07/02/2022</td>
	</tr>




	<tr name="AIPI_03-06-021">
		<td align="center"><b>AIPI 03-06-021</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-06-021',3)">Installation of cooling/ water/ anemometric/ rain repellent/ extinguishing/ draining circuit pipes</a></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-021 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-021_2022-02_A5.pdf');">Installation of cooling / water / anemometric / rain repellent / extinguishing / draining circuit pipes</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">02/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-021 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-021_2020-07_4.pdf');">Installation of cooling / water / anemometric / rain repellent / extinguishing / draining circuit pipes</a></td>
		<td align="center">Ed-4</td>
		<td align="center">07/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-021</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-06-021_Ind_A5&4.xls');\">";}?>MCQ/QCM Installation of cooling / water / anemometric / rain repellent / extinguishing / draining circuit pipes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/02/2022</td>



<tr name="AIPI_03-06-022">
		<td align="center"><b>AIPI 03-06-022</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-06-022',3)">Installation of bleed air system</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-06-022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-06-022_2018-01_A3_EN.pdf');">Installation of bleed air system</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">15/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-06-022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-06-022_5.pdf');">Installation of bleed air system</a></td>
		<td align="center">Ed-5</td>
		<td align="center">01/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 03-06-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_AIPS_03-06-022_Ind_A3&5.xls');\">";}?>MCQ/QCM Installation of bleed air system<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/02/2021</td>
	</tr>




	<tr name="AIPI_03-07-004">
		<td align="center"><b>AIPI 03-07-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_03-07-004',3)">AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-07-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-07-004_2015-09_A4_EN.pdf');">AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">04/09/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-07-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-07-004.pdf');">AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET</a></td>
		<td align="center">Ed-6</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 03-07-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-AIPI_AIPS_03-07-004_Ind_A4&6.xls');\">";}?>MCQ AIRBUS PROCESS INSTRUCTION PRODUCTION, INSTALLATION AND REWORK OF INSULATION BLANKET<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/10/2015</td>
	</tr>



	<tr name="AIPI_03-08-003">
		<td align="center"><b>AIPI 03-08-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-08-003',5)">REWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-08-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-08-003_2022-07_A8.pdf');">Rework of Structures Manufactured from Composite Materials (Laminates and Sandwich)</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">07/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-08-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-08-003_2022-06_7.pdf');">REWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)</a></td>
		<td align="center">Ed-7</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 03-08-003 COMPLET</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI_03-08-003_Formation_Complete_Ind_A8&7.xls');\">";}?>MCQ REWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/11/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 03-08-003 RETOUCHES COSMETIQUES</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_03-08-003_Ind_A8&7_Light.xlsx');\">";}?>MCQ REWORK OF STRUCTURES MANUFACTURED FROM COMPOSITE MATERIALS (LAMINATES AND SANDWICH)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/07/2022</td>
	</tr>



<tr name="AIPS_03-10-003">
		<td align="center"><b>AIPI 03-10-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPS_03-10-003',3)">Rectification of metallic materials by 3 point bending </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-10-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-10-003_2018-01_A1_EN.pdf');">Rectification of metallic materials by 3 point bending </a></td>
		<td align="center">Ed-A1</td>
		<td align="center">03/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 03-10-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_03-10-003_2010-04_3_EN.pdf');">Rectification of metallic materials by 3 point bending </a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI 03-10-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-AIPI_03-10-003_Ind_A1&3.xls');\">";}?>MCQ Rectification of metallic materials by 3 point bending<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/02/2018</td>
	</tr>



<tr name="AIPI_03-11-003">
		<td align="center"><b>AIPI 03-11-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_03-11-003',1)">DEBURRING AND MANUAL REWORK OF METALLIC COMPONENTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 03-11-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_03-11-003_2010-06_3.pdf');">DEBURRING AND MANUAL REWORK OF METALLIC COMPONENTS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/2010</td>
	</tr>


<tr name="AIPI_05-02-002">
		<td align="center"><b>AIPI 05-02-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-002',4)">APPLICATION OF ANTI-STATIC PAINT ON COMPOSITE PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-002_2010-04_2.pdf');">APPLICATION OF ANTI-STATIC PAINT ON COMPOSITE PARTS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-002_2010-04_2_FR.pdf');">APPLICATION DE PEINTURES ANTI-STATIQUES SUR DES ELEMENTS COMPOSITES</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-002.pdf');">APPLICATION OF ANTI-STATIC PAINT ON COMPOSITE PARTS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 05-02-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-AIPI_05-02-002_Ind_2&2.xls');\">";}?>MCQ APPLICATION OF ANTI-STATIC PAINT ON COMPOSITE PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/12/2017</td>
	</tr>
	


	<tr name="AIPI_05-02-003">
		<td align="center"><b>AIPI 05-02-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-003',4)">APPLICATION OF EXTERNAL PAINT SYSTEMS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-003_2020-05-19_A2.pdf');">APPLICATION OF EXTERNAL PAINT SYSTEMS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-003_2014-07_5.pdf');">APPLICATION OF EXTERNAL PAINT SYSTEMS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">07/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-02-003_Ind_A2&5.xls');\">";}?>MCQ/QCM APPLICATION OF EXTERNAL PAINT SYSTEMS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>




	<tr name="AIPI_05-02-009">
		<td align="center"><b>AIPI 05-02-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-009',3)">APPLICATION OF STRUCUTRAL PAINTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-009_2020-01_A4.pdf');">APPLICATION OF STRUCUTRAL PAINTS</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">01/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-009_2021-10_6.pdf');">APPLICATION OF STRUCUTRAL PAINTS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">10/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-02-009_Ind_A4&6.xlsx');\">";}?>MCQ/QCM APPLICATION OF STRUCUTRAL PAINTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/10/2021</td>
	</tr>



	<tr name="AIPI_05-02-011">
		<td align="center"><b>AIPI 05-02-011</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-011',3)">REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-011 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-011_2022-01_A2.pdf.pdf');">REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">01/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-011 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-011_2022-10_7.pdf');">REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS</a></td>
		<td align="center">Ed-7</td>
		<td align="center">10/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-011</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-02-011_Ind_A2&7.xls');\">";}?>MCQ/QCM REWORK OF PAINTS ON METALLIC AND NON-METALLIC STRUCTURAL PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/11/2022</td>
	</tr>



	<tr name="AIPI_05-02-014">
		<td align="center"><b>AIPI 05-02-014</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-014',5)">Application of fuel vapour barrier coating</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-014 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-014_2016-06_A2_EN.pdf');">Application of fuel vapour barrier coating</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">17/06/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-014 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-014_2016-06_A2_FR.pdf');">Application d’un revêtement barrière d’étanchéité aux vapeurs de carburant (revêtement FVB)</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">16/12/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-014 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-014_2022-03_4.pdf');">Application of fuel vapour barrier coating</a></td>
		<td align="center">Ed-4</td>
		<td align="center">03/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-014</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPS-AIPI_05-02-014_Ind_A2&4.xls');\">";}?>MCQ/QCM Application of fuel vapour barrier coating<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/03/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-014 REWORK</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPS-AIPI_05-02-014_Ind_A2&4_REWORK.xlsx');\">";}?>MCQ/QCM Rework of fuel vapour barrier coating<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/03/2022</td>
	</tr>



		<tr name="AIPI_05-02-017">
		<td align="center"><b>AIPI 05-02-017</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-02-017',3)">Application of corrosion inhibiting coating on external erosion prone (unpainted) Aluminium surfaces</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-017 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-02-017_2012-12_A0.pdf');">Application of corrosion inhibiting coating on external erosion prone (unpainted) Aluminium surfaces</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">12/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-017 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-02-017_2012-11_2.pdf');">Application of corrosion inhibiting coating on external erosion prone (unpainted) Aluminium surfaces</a></td>
		<td align="center">Ed-2</td>
		<td align="center">11/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-017</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-02-017_Ind_A0&2.xlsx');\">";}?>MCQ/QCM Application of corrosion inhibiting coating on external erosion prone (unpainted) Aluminium surfaces<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>




	<tr name="AIPI_05-04-005">
		<td align="center"><b>AIPI 05-04-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-04-005',4)">APPLICATION OF GAP FILLER-EASY TO REMOVE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-04-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-04-005_2020-07_A3.pdf');">APPLICATION OF GAP FILLER-EASY TO REMOVE</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">07/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-04-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-04-005.pdf');">APPLICATION OF GAP FILLER-EASY TO REMOVE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">06/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI 05-04-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_05-04-005_Ind_A3&3.xls');\">";}?>MCQ/QCM APPLICATION OF GAP FILLER-EASY TO REMOVE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>



	<tr name="AIPI_05-04-006">
		<td align="center"><b>AIPI 05-04-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-04-006',3)">Application of Filler</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-04-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-04-006_2019-09_A3_EN.pdf');">Application of Filler</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">24/09/2019</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-04-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-04-006.pdf');">Application of Filler</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI 05-04-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-04-006_Ind_A3&3.xlsx');\">";}?>MCQ/QCM Application of Filler<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/06/2020</td>
	</tr>




	<tr name="AIPI_05-05-001">
		<td align="center"><b>AIPI 05-05-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-001',3)">SEALING OF AIRCRAFT STRUCTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-001_2022-01_A6.pdf');">SEALING OF AIRCRAFT STRUCTURE</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">01/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-001_2022-04_11.pdf');">SEALING OF AIRCRAFT STRUCTURE</a></td>
		<td align="center">Ed-11</td>
		<td align="center">04/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-05-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-05-001_Ind_A6&11.xlsx');\">";}?>MCQ/QCM SEALING OF AIRCRAFT STRUCTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/05/2022</td>
	</tr>



	<tr name="AIPI_05-05-003">
		<td align="center"><b>AIPI 05-05-003</b></td>
		<td colspan="8"><a href="javascript:onclick=Voir_TR('AIPI_05-05-003',6)">SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-003.pdf');">SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">01/04/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-003 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-003_2017-12_A5_FR.pdf');">PROTECTION DE SURFACE DES FIXATIONS ET DES MASTICS PAR APPLICATION DE PEINTURE</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">02/10/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-003.pdf');">SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-05-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-05-003_Ind_A6&3.xlsx');\">";}?>MCQ/QCM SURFACE PROTECTION OF FASTENERS AND SEALANTS BY APPLICATION OF VARNISH<?php if($QCM){echo "</a>";}?></td>
		<td align="center">07/03/2022</td>
	</tr>
	<tr>
		<td align="center"><b>INSPECTION NOTE 05-05-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001_AIPI-AIPS_05-05-003_EN.xls');">INSPECTION NOTE 05-05-003</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/03/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MEMOIRE DE CONTROLE 05-05-003 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001_AIPI-AIPS_05-05-003_FR.xls');">MEMOIRE DE CONTROLE 05-05-003</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/03/2019</td>
	</tr>




		<tr name="AIPI_05-05-004">
		<td align="center"><b>AIPI 05-05-004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-004',3)">WET INSTALLATION OF FASTENERS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-004_2021-07_A5.pdf');">WET INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">07/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-004_2021-06_7.pdf');">WET INSTALLATION OF FASTENERS</a></td>
		<td align="center">Ed-7</td>
		<td align="center">06/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-05-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-05-004_Ind_A5&7.xlsx');\">";}?>MCQ/QCM WET INSTALLATION OF FASTENERS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2022</td>
	</tr>




	<tr name="AIPI_05-05-005">
		<td align="center"><b>AIPI 05-05-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-005',4)">PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-005.pdf');">PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">10/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-005 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-005_2012-10_A2_FR.pdf');">Fabrication de Joints Moulants au moyen de Mastic d’Étanchéité</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">04/12/2014</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-005_2021-11_4.pdf');">PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 05-05-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_05-05-005_EN.xls');\">";}?>MCQ PROCESS FOR THE MANUFACTURE OF FORM-IN-PLACE SEALS USING SEALANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/12/2021</td>
	</tr>




	<tr name="AIPI_05-05-006">
		<td align="center"><b>AIPI 05-05-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-006',4)">APPLICATION OF NON HARDENING JOINTING COMPOUNDS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-006.pdf');">APPLICATION OF NON HARDENING JOINTING COMPOUNDS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">11/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-006 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-006_2013-01_A3_FR.pdf');">APPLICATION DE PATE A JOINT NON DURCISSANTE</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">04/01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-006.pdf');">APPLICATION OF NON HARDENING JOINTING COMPOUNDS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-05-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-05-006_Ind_A3&4.xlsx');\">";}?>MCQ/QCM APPLICATION OF NON HARDENING JOINTING COMPOUNDS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/05/2018</td>
	</tr>



	<tr name="AIPI_05-05-008">
		<td align="center"><b>AIPI 05-05-008</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-008',3)">APPLICATION OF LOW ADHESION SEALANT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-008_2021-03_A2.pdf');">APPLICATION OF LOW ADHESION SEALANT</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">03/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-008_2021-11_3.pdf');">APPLICATION OF LOW ADHESION SEALANT</a></td>
		<td align="center">Ed-3</td>
		<td align="center">11/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-05-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-05-008_Ind_A2&3.xlsx');\">";}?>MCQ/QCM APPLICATION OF LOW ADHESION SEALANT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/11/2021</td>
	</tr>




<tr name="AIPI_05-05-009">
		<td align="center"><b>AIPI 05-05-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-009',3)">Preservation of Cut Edges of Carbon Fibre Composite Parts to prevent Galvanic Corrosion on attached Metal Parts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-05-009_2021-08_A6.pdf');">Preservation of Cut Edges of Carbon Fibre Composite Parts to prevent Galvanic Corrosion on attached Metal Parts</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">08/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-009.pdf');">Preservation of Cut Edges of Carbon Fibre Composite Parts to prevent Galvanic Corrosion on attached Metal Parts</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 05-05-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_05-05-009_Ind_A6&3.xls');\">";}?>MCQ Preservation of Cut Edges of Carbon Fibre Composite Parts to prevent Galvanic Corrosion on attached Metal Parts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/01/2024</td>
	</tr>




	<tr name="AIPI_05-05-010">
		<td align="center"><b>AIPI 05-05-010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-05-010',4)">Use of silicone-based sealants for interior application</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-010 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI 05-05-010-FR.pdf');">Utilisation de mastics d??tanch?it? ? base de silicone pour application</a></td>
		<td align="center">Ed-1</td>
		<td align="center">19/03/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-05-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI 05-05-010-EN.pdf');">Use of silicone-based sealants for interior application</a></td>
		<td align="center">Ed-1</td>
		<td align="center">01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-05-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-05-010.pdf');">Use of silicone-based sealants for interior application</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-05-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM AIPI 05-05-010.xls');\">";}?>MCQ/QCM Use of silicone-based sealants for interior application<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/01/2016</td>
	</tr>



		<tr name="AIPI_05-07-001">
		<td align="center"><b>AIPI 05-07-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_05-07-001',3)">Application of Fire Protective Coatings</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 05-02-017 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_05-07-001_2010-10_1.pdf');">Application of Fire Protective Coatings</a></td>
		<td align="center">Ed-1</td>
		<td align="center">10/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 05-02-017 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_05-07-001_2015-02_3.pdf');">Application of Fire Protective Coatings</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 05-02-017</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_05-07-001_Ind_1&3.xlsx');\">";}?>MCQ/QCM Application of Fire Protective Coatings<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>



	<tr name="AIPI_06-01-004">
		<td align="center"><b>AIPI 06-01-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_06-01-004',3)">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 06-01-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-01-004_2021-04_A3.pdf');">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 06-01-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_06-01-004_2021-02_3.pdf');">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 06-01-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_06-01-004_Ind_A3&3.xls');\">";}?>MCQ/QCM MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2021</td>
	</tr>

<tr name="AIPI_06-01-006">
		<td align="center"><b>AIPI 06-01-006</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_06-01-006',3)">Priming of polyamide adherend prior to adhesive bonding</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 06-01-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-01-006.pdf');">Priming of polyamide adherend prior to adhesive bonding</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">28/02/2017</td>

<tr>
		<td align="center"><b>DOC AIPS 06-01-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_06-01-006.pdf');">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 06-01-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_06-01-006_Ind_A2&2.xls');\">";}?>MCQ/QCM MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/03/2020</td>
	</tr>
	</tr>

<tr name="AIPI_06-01-008">
		<td align="center"><b>AIPI 06-01-008</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_06-01-008',3)">Surface preparation of adherends for silicone based adhesives/sealants</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 06-01-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-01-008_2011-01_1.pdf');">Surface preparation of adherends for silicone based adhesives/sealants</a></td>
		<td align="center">Ed-1</td>
		<td align="center">01/2011</td>
	</tr>
<tr>
		<td align="center"><b>DOC AIPS 06-01-008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_06-01-008_2010-06_1.pdf');">MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 06-01-008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AIPI-AIPS_06-01-008 ind 1-1.xls');\">";}?>MCQ/QCM MECHANICAL SURFACE PREPARATION OF NON-STRUCTURAL ADHEREND PRIOR TO ADHESIVE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/04/2022</td>
	</tr>
	</tr>

<tr name="AIPI_06-02-002">
		<td align="center"><b>AIPI 06-02-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_06-02-002',3)">COLLAGE NON STRUCTURAL </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 06-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-02-002_2018-11_A3_EN.pdf');">COLLAGE NON STRUCTURAL</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">14/11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 06-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_06-02-002_2011-01_4.pdf');">Non-structural adhesive bonding </a></td>
		<td align="center">Ed-4</td>
		<td align="center">01/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 06-02-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_06-02-002_Ind_A3&4.xlsx');\">";}?>MCQ/QCM NON STRUCTURAL ADHESIVE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/02/2020</td>
	</tr>




	<tr name="AIPI_06-02-009">
		<td align="center"><b>AIPI 06-02-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_06-02-009',4)">ADHESIVE TAPE BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 06-02-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-02-009_2015-10_A1_EN.pdf');">ADHESIVE TAPE BONDING</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">26/10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 06-02-009 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_06-02-009-FR.pdf');">COLLAGE DES RUBANS ADH?SIFS</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">12/02/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 06-02-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_06-02-009.pdf');">ADHESIVE TAPE BONDING</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 06-02-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_06-02-009_Ind_A1&2.xls');\">";}?>MCQ ADHESIVE TAPE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/05/2016</td>
	</tr>




	<tr name="AIPI_07-01-001">
		<td align="center"><b>AIPI 07-01-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-001',4)">Manufacturing and installation of cable harnesses</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-001_2021-05_A8_EN.pdf');">Manufacturing and installation of cable harnesses</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">05/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-001 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-001_2021-05_A8_FR.pdf');">Manufacturing and installation of cable harnesses</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">05/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-001_2020-12_12.pdf');">Manufacturing and installation of cable harnesses</a></td>
		<td align="center">Ed-12</td>
		<td align="center">12/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-01-001_Ind_A8&12.xlsx');\">";}?>MCQ/QCM Manufacturing and installation of cable harnesses<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/05/2021</td>
	</tr>




	<tr name="AIPI_07-01-002">
		<td align="center"><b>AIPI 07-01-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-002',4)">Insertion and extraction of removable contacts in electrical connecting systems</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-002_2017-07_A3_EN.pdf');">Insertion and extraction of removable contacts in electrical connecting systems</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">12/07/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-002_2017-07_A3_FR.pdf');">Insertion et extraction des contacts amovibles dans les systèmes de connexion électrique</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">09/11/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-002</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-002.pdf');">General requirements for the insertion and extraction of removable contacts in electrical connecting systems</a></td>
		<td align="center">Ed-2</td>
		<td align="center">10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_AIPS_07-01-002_Ind _A3&2.xls');\">";}?>MCQ/QCM Insert and extract of removable contacts in elec connect systems <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/12/2015</td>
	</tr>




	<tr name="AIPI_07-01-003">
		<td align="center"><b>AIPI 07-01-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-003',3)">Cable tying with cable ties NSA935401 and lacing tapes NSA8420</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-003_2019-09_A8_EN.pdf');">Cable tying with cable ties NSA935401 and lacing tapes NSA8420</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">26/09/2019</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-003.pdf');">Cable tying with cable ties NSA935401 and lacing tapes NSA8420</a></td>
		<td align="center">Ed-6</td>
		<td align="center">03/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-01-003_Ind_A8&6.xlsx');\">";}?>MCQ/QCM Cable tying with cable ties NSA935401 and lacing tapes NSA8420<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/10/2019</td>
	</tr>



	
	<tr name="AIPI_07-01-006">
		<td align="center"><b>AIPI 07-01-006</b></td>
		<td colspan="7"><a href="javascript:onclick=Voir_TR('AIPI_07-01-006',3)">ELECTRICAL BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-006_2021-12_B3.pdf');">ELECTRICAL BONDING</a></td>
		<td align="center">Ed-B3</td>
		<td align="center">12/2021</td>
	</tr>-
	<tr>
		<td align="center"><b>DOC AIPS 07-01-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-006_2021-09_11.pdf');">ELECTRICAL BONDING</a></td>
		<td align="center">Ed-11</td>
		<td align="center">09/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI_AIPS_07-01-006_Ind_B3&11.xlsx');\">";}?>MCQ/QCM ELECTRICAL BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/01/2022</td>
	</tr>




	<tr name="AIPI_07-01-007">
		<td align="center"><b>AIPI 07-01-007</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-01-007',4)">INSTALLATION OF SOLER SLEEVES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-007.pdf');">INSTALLATION OF SOLER SLEEVES</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">03/04/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-007 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-007_2017-10_A5_FR.pdf');">Installation des manchons autosoudeurs</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">09/11/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-007_2017-08_6.pdf');">INSTALLATION OF SOLER SLEEVES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">08/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-01-007_Ind_A6&6.xls');\">";}?>MCQ/QCM INSTALLATION OF SOLER SLEEVES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/06/2020</td>
	</tr>
	


	<tr name="AIPI_07-01-009">
		<td align="center"><b>AIPI 07-01-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-009',3)">Installation of backshells</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-009_2021-12_A8.pdf');">Installation of backshells</a></td>
		<td align="center">Ed-A8</td>
		<td align="center">12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-009_10.pdf');">Installation of backshells</a></td>
		<td align="center">Ed-10</td>
		<td align="center">12/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-01-009_Ind_A8&10.xls');\">";}?>MCQ/QCM Installation of backshells<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2021</td>
	</tr>




<tr name="AIPI_07-01-015">
		<td align="center"><b>AIPI 07-01-015</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-01-015',3)">Modification and repair of an overbraided harness</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-015 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-015_2020-01_A5_EN.pdf');">Modification and repair of an overbraided harness</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">29/01/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-015</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-015_2019-12_7_EN.pdf');">Modification and repair of cable and harness protection components </a></td>
		<td align="center">Ed-7</td>
		<td align="center">12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-01-015</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_07-01-015_Ind_A5&7.xls');\">";}?>MCQ Modification and repair of an overbraided harness<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/02/2020</td>
	</tr>



<tr name="AIPI_07-01-022">
		<td align="center"><b>AIPI 07-01-022</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-01-022',4)">INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-022_2017-05_A6.pdf');">INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">04/05/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-022 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-022_2017-05_A6_FR.pdf');">Installation des jonctions souples et câbles ESN et des raceways</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">04/05/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-022 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-022_2013-07_3.pdf');">INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-022</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-01-022_Ind_A6&3.xlsx');\">";}?>MCQ/QCM INSTALLATION OF ESN FLEXIBLE JUNCTIONS, CABLES AND RACEWAYS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/09/2018</td>
	</tr>
	


	<tr name="AIPI_07-01-023">
		<td align="center"><b>AIPI 07-01-023</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-01-023',4)">Grounding shielded cables</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-023 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-023_FR.pdf');">Mise ? la masse des c?bles blind?s</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">13/10/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-01-023 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-01-023_EN.pdf');">Grounding shielded cables</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">10/06/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-01-023 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-01-023.pdf');">Bonding of individual cable shields</a></td>
		<td align="center">Ed-2</td>
		<td align="center">04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-01-023</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-01-023.xls');\">";}?>MCQ/QCM Grounding shielded cables<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2016</td>
	</tr>



<tr name="AIPI_07-02-001">
		<td align="center"><b>AIPI 07-02-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-02-001',3)">Stripping of electrical cables</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-02-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-02-001_2017-04_A4.pdf');">Stripping of electrical cables</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">04/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-02-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-02-001_2023-05_8.pdf');">Stripping of electrical cables</a></td>
		<td align="center">Ed-8</td>
		<td align="center">05/2023</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-02-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-02-001_A4_&_8.xls');\">";}?>MCQ/QCM Stripping of electrical cables<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/07/2023</td>
	</tr>


	<tr name="AIPI_07-03-001">
		<td align="center"><b>AIPI 07-03-001</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-03-001',3)">General requirements for crimping of electrical contacts</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-03-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-03-001_2018-03_A5_EN.pdf');">General requirements for crimping of electrical contacts</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">20/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-03-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-03-001_2016-03_4_EN.pdf');">General requirements for crimping of electrical contacts</a></td>
		<td align="center">Ed-4</td>
		<td align="center">03/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-03-001</b></td>
	    <td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-03-001_Ind_A5&4.xls');\">";}?>MCQ-QCM General requirements for crimping of electrical contacts<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/05/2019</td>
	</tr>



	<tr name="AIPI_07-03-003">
		<td align="center"><b>AIPS 07-03-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-03-003',2)">PRINCIPLE OF USE OF MANUAL CRIMPING TOOLS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-03-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-03-003.pdf');">PRINCIPLE OF USE OF MANUAL CRIMPING TOOLS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">02/1996</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-03-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-03-003_EN.xls');\">";}?>MCQ PRINCIPLE OF USE OF MANUAL CRIMPING TOOLS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/05/2014</td>
	</tr>



	<tr name="AIPI_07-04-007">
		<td align="center"><b>AIPI 07-04-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-007',4)">Installation of caps NSA936601 and NSA936604</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-007.pdf');">Installation of caps NSA936601 and NSA936604</a></td>
		<td align="center">Ed-1</td>
		<td align="center">10/2009</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-007 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-007_2009-10_1_FR.pdf');">Installation of caps NSA936601 and NSA936604</a></td>
		<td align="center">Ed-1</td>
		<td align="center">10/2009</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-007.pdf');">Installation of caps NSA936601 and NSA936604</a></td>
		<td align="center">Ed-4</td>
		<td align="center">05/2013</td>
	</tr>	
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-04-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM - AIPI_AIPS_07-04-007.xls');\">";}?>MCQ/QCM Installation of caps NSA936601 and NSA936604 <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2016</td>
	</tr>



		<tr name="AIPI_07-04-009">
		<td align="center"><b>AIPI 07-04-009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-009',3)">Installation of copper extremities onto aluminium cable (Copalum crimp)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-009_2018-11_A2.pdf');">Installation of copper extremities onto aluminium cable (Copalum crimp)</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-009 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-009_2012-02_4.pdf');">Installation of copper extremities onto aluminium cable (Copalum crimp)</a></td>
		<td align="center">Ed-4</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-04-009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-04-009_Ind_A2&4.xlsx');\">";}?>MCQ/QCM Installation of copper extremities onto aluminium cable (Copalum crimp)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/09/2021</td>
	</tr>




	<tr name="AIPI_07-04-010">
		<td align="center"><b>AIPI 07-04-010</b></td>
		<td colspan="6"><a href="javascript:onclick=Voir_TR('AIPI_07-04-010',4)">INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-010_2018-11_A7_EN.pdf');">INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE</a></td>
		<td align="center">Ed-A7</td>
		<td align="center">2018-11</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-010_2018-07_7_EN.pdf');">INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE</a></td>
		<td align="center">Ed-7</td>
		<td align="center">07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-04-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI_AIPS_07-04-010_Ind_A7&7.xlsx');\">";}?>MCQ INSTALLATION OF ALUMINIUM TERMINAL LUGS ON ALUMINIUM CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/12/2018</td>
	</tr>
	
	

	<tr name="AIPI_07-04-024">
		<td align="center"><b>AIPI 07-04-024</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-024',3)">General requirements for the crimping of 10 to 04 size aluminium electrical cables ABS0949 AD series onto contacts ABS1380 and ABS1381</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-024 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-024_2015-12_A2.pdf');">General requirements for the crimping of 10 to 04 size aluminium electrical cables ABS0949 AD series onto contacts ABS1380 and ABS1381</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">12/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-024 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-024_2022-06_6.pdf');">General requirements for the crimping of 10 to 04 size aluminium electrical cables ABS0949 AD series onto contacts ABS1380 and ABS1381</a></td>
		<td align="center">Ed-6</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-04-024</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-04-024_Ind_A2&6.xlsx');\">";}?>MCQ/QCM General requirements for the crimping of 10 to 04 size aluminium electrical cables ABS0949 AD series onto contacts ABS1380 and ABS1381<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/07/2022</td>
	</tr>




	<tr name="AIPI_07-04-028">
		<td align="center"><b>AIPI 07-04-028</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-028',4)">INSTALLATION OF TWINAX CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-028 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-028.pdf');">INSTALLATION OF TWINAX CONTACTS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-028 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-028_2012-05_A2_FR.pdf');">Montage des contacts Twinax</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">05/06/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-028 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-028_2018-11_5_EN.pdf');">INSTALLATION OF TWINAX CONTACTS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-04-028</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_07-04-028_Ind_A2&5.xlsx');\">";}?>MCQ INSTALLATION OF TWINAX CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/12/2018</td>
	</tr>




	<tr name="AIPI_07-04-031">
		<td align="center"><b>AIPI 07-04-031</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-031',3)">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-031 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-031_2019-10_A7_EN.pdf');">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS</a></td>
		<td align="center">Ed-A7</td>
		<td align="center">10/2019</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-031 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-031_2019-09_4_EN.pdf');">GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">09/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-04-031</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-04-031_Ind_A7&4.xls');\">";}?>MCQ/QCM GENERAL REQUIREMENTS FOR THE CRIMPING OF 24 TO 12 SIZE ALUMINIUM ELECTRICAL CABLES ABS0949 (AD SERIES) ONTO CONTACTS ABS1380 AND ABS1381 WITH MULTI-GAUGES CRIMPING TOOLS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/10/2019</td>
	</tr>



	<tr name="AIPI_07-04-037">
		<td align="center"><b>AIPI 07-04-037</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-04-037',4)">MANUFACTURING OF TRIAXIAL CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-037 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-037_2017-06_A3.pdf');">MANUFACTURING OF TRIAXIAL CONTACTS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">21/06/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-04-037 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-04-037_2017-06_A3_FR.pdf');">Fabrication de contacts triaxiaux</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">29/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-04-037 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-04-037_2018-11_3_EN.pdf');">MANUFACTURING OF TRIAXIAL CONTACTS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-04-037</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_07-04-037_Ind_A3&3.xlsx');\">";}?>MCQ MANUFACTURING OF TRIAXIAL CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/12/2018</td>
	</tr>



	<tr name="AIPI_07-05-004">
		<td align="center"><b>AIPI 07-05-004</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-004',4)">Installation of grounding modules</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-004 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-004-FR.pdf');">Installation des modules circulaires de masse</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">11/04/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-004-EN.pdf');">Installation of grounding modules/a></td>
		<td align="center">Ed-A1</td>
		<td align="center">03/01/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-004.pdf');">Installation of grounding modules</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2012</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ - AIPI_AIPS_07-05-004.xls');\">";}?>MCQ/QCM Installation of grounding modules<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/12/2015</td>
	</tr>



	<tr name="AIPI_07-05-005">
		<td align="center"><b>AIPI 07-05-005</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-005',3)">Assembly and connection of rail mounted terminal modules</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-005_2020-09_A4.pdf');">Assembly and connection of rail mounted terminal modules</a></td>
		<td align="center">Ed-A4/td>
		<td align="center">09/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-005_7.pdf');">Assembly and connection of rail mounted terminal modules</a></td>
		<td align="center">Ed-7</td>
		<td align="center">11/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-05-005_Ind A4&7.xls');\">";}?>MCQ/QCM Assembly and connection of rail mounted terminal modules<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/11/2020</td>
	</tr>



	<tr name="AIPI_07-05-006">
		<td align="center"><b>AIPI 07-05-006</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-006',3)">Installation of rectangular connectors</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-006_2019-07_A6_EN.pdf');">Installation of rectangular connectors/a></td>
		<td align="center">Ed-A6</td>
		<td align="center">07/2019</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-006_2019-08_7_EN.pdf');">Installation of rectangular connectors</a></td>
		<td align="center">Ed-7</td>
		<td align="center">08/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-006_Ind_A6&7.xlsx');\">";}?>MCQ/QCM Installation of rectangular connectors<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2019</td>
	</tr>




	<tr name="AIPI_07-05-007">
		<td align="center"><b>AIPI 07-05-007</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-007',3)">Installation of compound-filled pressure seals</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-007.pdf');">Installation of compound-filled pressure seals</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">06/03/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-007_2011-02_2.pdf');">Installation of compound-filled pressure seals</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-007_Ind_A3&2.xls');\">";}?>MCQ/QCM Installation of compound-filled pressure seals<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/06/2020</td>
	</tr>




	<tr name="AIPI_07-05-010">
		<td align="center"><b>AIPI 07-05-010</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-010',3)">Installation of connectors EN3646 type</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-010_2019-11_A3_EN.pdf');">Installation of connectors EN3646 type</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">29/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-010 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-010-EN.pdf');">Installation of connectors EN3646 type </a></td>
		<td align="center">Ed-5</td>
		<td align="center">09/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-010</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-05-010_Ind A3&5.xls');\">";}?>MCQ/QCM Installation of connectors EN3646 type <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/12/2019</td>
	</tr>




	<tr name="AIPI_07-05-016">
		<td align="center"><b>AIPI 07-05-016</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-05-016',4)">Installation of coaxial connectors TNC, BNC, N and C types</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-016 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-016-FR.pdf');">Installation de connecteurs coaxiaux, types TNC, BNC, N et C</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">04/05/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-016 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-016-EN.pdf');">Installation of coaxial connectors TNC, BNC, N and C types</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">24/06/2011</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-016 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-016.pdf');">Installation of coaxial connectors TNC, BNC, N and C types</a></td>
		<td align="center">Ed-4</td>
		<td align="center">10/2011</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-016</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-05-016.xls');\">";}?>MCQ/QCM Installation of coaxial connectors TNC, BNC, N and C types<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/02/2016</td>
	</tr>



	<tr name="AIPI_07-05-032">
		<td align="center"><b>AIPI 07-05-032</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-032',3)">Installation of connectors EN3646 type </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-032 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-032_2017-08_A5_EN.pdf');">Installation of connectors EN3646 type</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">13/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-032 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-032_2017-05_6.pdf');">Installation of connectors EN3646 type</a></td>
		<td align="center">Ed-6</td>
		<td align="center">05/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-032</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-032_Ind_A5&6.xls');\">";}?>MCQ/QCM Installation of connectors EN3646 type<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/03/2018</td>
	</tr>



	<tr name="AIPI_07-05-038">
		<td align="center"><b>AIPI 07-05-038</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-038',4)">INSTALLATION OF COAXIAL CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-038 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-038_2018-06_A6_EN.pdf');">INSTALLATION OF COAXIAL CONTACTS</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">05/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-038 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-038_2018-06_A6_FR.pdf');">Installation de contacts coaxiaux</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">06/08/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-038 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-038_2018-11_6_EN.pdf');">INSTALLATION OF COAXIAL CONTACTS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-038</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-038_Ind_A6&6.xlsx');\">";}?>MCQ/QCM INSTALLATION OF COAXIAL CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/12/2018</td>
	</tr>




	<tr name="AIPI_07-05-041">
		<td align="center"><b>AIPI 07-05-041</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-041',4)">Installation of D-Subminiature connectors</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-041 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-041-FR.pdf');">Montage des connecteurs D-subminiature</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">XX/2012</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-041 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-041-EN.pdf');">Installation of D-Subminiature connectors</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">27/06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-041 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-041.pdf');">Installation of D-Subminiature connectors</a></td>
		<td align="center">Ed-4</td>
		<td align="center">05/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-041</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-05-041.xls');\">";}?>MCQ/QCM Installation of D-Subminiature connectors<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/02/2016</td>
	</tr>




	<tr name="AIPI_07-05-042">
		<td align="center"><b>AIPI 07-05-042</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-042',3)">Assembly and connection of terminal blocks</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-042 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-042.pdf');">Installation of cable brackets and supports</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-042 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-042-FR.pdf');">Installation de supports de c?blage</a></td>
		<td align="center">Ed-6</td>
		<td align="center">09/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-042</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-05-042.xls');\">";}?>MCQ/QCM Installation of cable brackets and supports<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/11/2016</td>
	</tr>




	<tr name="AIPI_07-05-043">
		<td align="center"><b>AIPI 07-05-043</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-043',4)">INSTALLATION OF QUADRAX CONTACTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-043 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-043.pdf');">INSTALLATION OF QUADRAX CONTACTS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">18/05/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-043 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-043_2016-05_A3_FR.pdf');">Installation des contacts Quadrax</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">03/08/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-043 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-043_2018-11_4_EN.pdf');">INSTALLATION OF QUADRAX CONTACTS</a></td>
		<td align="center">Ed-4</td>
		<td align="center">11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-043</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-043_Ind_A3&4.xlsx');\">";}?>MCQ/QCM INSTALLATION OF QUADRAX CONTACTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/12/2018</td>
	</tr>
	



	<tr name="AIPI_07-05-047">
		<td align="center"><b>AIPI 07-05-047</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-047',5)">USE OF METALLIC CLAMPING STRIP ASNE0805</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-047 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-047_2022-05_A1.pdf');">USE OF METALLIC CLAMPING STRIP ASNE0805</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">05/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-047 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-047.pdf');">USE OF METALLIC CLAMPING STRIP ASNE0805</a></td>
		<td align="center">Ed-2</td>
		<td align="center">07/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-05-047</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ- AIPI_AIPS_07-05-047_EN.xls');\">";}?>MCQ USE OF METALLIC CLAMPING STRIP ASNE0805<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/05/2022</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AIPI/AIPS 07-05-047</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM- AIPI_AIPS_07-05-047_FR.xls');\">";}?>QCM Utilisation des colliers m?talliques ASNE0805<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/05/2022</td>
	</tr>




	<tr name="AIPI_07-05-050">
		<td align="center"><b>AIPI 07-05-050</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-050',4)">Assembly and connection of terminal blocks</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-050 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-050_2016-11_A2.pdf');">Assembly and connection of terminal blocks</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">14/11/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-050 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-050_2016-11_A2_FR.pdf');">Assemblage et raccordement des barrettes à bornes</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">30/01/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-050 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-050_2016-09_5.pdf');">Assembly and connection of terminal blocks</a></td>
		<td align="center">Ed-5</td>
		<td align="center">09/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-050</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-05-050_Ind_A2_&_5.xls');\">";}?>MCQ/QCM Assembly and connection of terminal blocks<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/02/2017</td>
	</tr>



<tr name="AIPI_07-05-053">
		<td align="center"><b>AIPI 07-05-053</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-053',4)">Installation of quick-release-connector ABS0364, ABS1019 and ABS1152</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-053 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-053_2017-08_A1.pdf');">Installation of quick-release-connector ABS0364, ABS1019 and ABS1152</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">&nbsp;</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-053 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-053_2017-08_A1_FR.pdf');">Installation de connecteurs à démontage rapide ABS0364, ABS1019 et ABS1152</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">09/11/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-053 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-053_2017-07_3.pdf');">Installation of quick-release-connector ABS0364, ABS1019 and ABS1152</a></td>
		<td align="center">Ed-3</td>
		<td align="center">07/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-053</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-053_Ind_A1&3.xls');\">";}?>MCQ/QCM Installation of quick-release-connector ABS0364, ABS1019 and ABS1152<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/08/2017</td>
	</tr>



	<tr name="AIPI_07-05-062">
		<td align="center"><b>AIPI 07-05-062</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-05-062',4)">ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-062 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-062.pdf');">ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">16/06/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-062 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-062_2016-06_A2_FR.pdf');">Procédé d’assemblage des traversées étanches ABS1571</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">10/11/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-062 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-062.pdf');">ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571</a></td>
		<td align="center">Ed-4</td>
		<td align="center">05/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-062</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-062_Ind_A2&4.xlsx');\">";}?>MCQ/QCM ASSEMBLY PROCESS FOR PRESSURE SEALS ABS1571<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/09/2018</td>
	</tr>




	<tr name="AIPI_07-05-076">
		<td align="center"><b>AIPI 07-05-076</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-076',3)">INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-076 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-076_2020-09_A3.pdf');">INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">09/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-076 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-076_2020-06_3.pdf');">INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM</a></td>
		<td align="center">Ed-3</td>
		<td align="center">09/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ-QCM AIPI/AIPS 07-05-076</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-076_Ind_A3&3.xlsx');\">";}?>MCQ-QCM INSTALLATION OF COUPLER MIL BUS 1553 FOR FLIGHT CONTROL SYSTEM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/10/2020</td>
	</tr>



<tr name="AIPI_07-05-078">
		<td align="center"><b>AIPI 07-05-078</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-078',3)">Installation of coaxial connectors with clamp technology (connection without crimping)</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-078 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-078_2018-07_A3_EN.pdf');">Installation of coaxial connectors with clamp technology (connection without crimping)</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">12/07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-078 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-078_2019-02_3_EN.pdf');">Installation of coaxial connectors with clamp technology (connection without crimping)</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-078</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-078_Ind_A3&3.xlsx');\">";}?>MCQ/QCM Installation of coaxial connectors with clamp technology (connection without crimping)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/03/2019</td>
	</tr>




	<tr name="AIPI_07-05-079">
		<td align="center"><b>AIPI 07-05-079</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-05-079',3)">ASSEMBLY OF MODULAR CONNECTORS FAMILY</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-05-079 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-05-079_2017-11_A3.pdf');">ASSEMBLY OF MODULAR CONNECTORS FAMILY</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">29/11/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-05-079 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-05-079_2016-08_2.pdf');">ASSEMBLY OF MODULAR CONNECTORS FAMILY</a></td>
		<td align="center">Ed-2</td>
		<td align="center">08/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-05-079</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-05-079_Ind_A3&2.xls');\">";}?>MCQ/QCM ASSEMBLY OF MODULAR CONNECTORS FAMILY<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/12/2017</td>
	</tr>




<tr name="AIPI_07-06-002">
		<td align="center"><b>AIPI 07-06-002</b></td>
		<td colspan="4"><a href="javascript:onclick=Voir_TR('AIPI_07-06-002',4)">Identification and marking of electrical installations</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-06-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-06-002_2017-05_A5.pdf');">Identification and marking of electrical installations</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">19/05/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-06-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-06-002_2017-05_A5_FR.pdf');">Identification et repérage des installations électriques</a></td>
		<td align="center">Ed-A5</td>
		<td align="center">12/06/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-06-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-06-002_8.pdf');">Identification and marking of electrical installations</a></td>
		<td align="center">Ed-8</td>
		<td align="center">12/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-06-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-06-002_Ind_A5_& 8.xls');\">";}?>MCQ/QCM Identification and marking of electrical installations<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/12/2020</td>
	</tr>
	
<tr name="AIPI_07-06-007">
		<td align="center"><b>AIPI 07-06-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-06-007',1)">Installation and Protection of labels</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-06-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-06-007_2017-02_A2.pdf');">Installation and Protection of labels</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">08/02/2017</td>
	</tr>


	<tr name="AIPI_07-07-002">
		<td align="center"><b>AIPI 07-07-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-07-002',4)">FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-07-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-07-002.pdf');">FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">10/10/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-07-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-07-002_2016-10_A4_FR.pdf');">Installation des gaines textiles flexibles ABS0125, ABS0596, ABS0890, ABS1552, ABS2413, ABS2418, ASNE0559 et EN6049-003 à EN6049-009 pour la protection externe des câbles électriques </a></td>
		<td align="center">Ed-A4</td>
		<td align="center">10/10/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-07-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-07-002.pdf');">FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES</a></td>
		<td align="center">Ed-11</td>
		<td align="center">04/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-07-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-07-002_EN.xls');\">";}?>MCQ FLEXIBLE TEXTILE SHEATHS EN6049-003 TO EN6049-009, ABS0890, ABS1552, ASNE0559 AND ABS0596-003 FOR EXTERNAL PROTECTION OF ELECTRICAL CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/10/2016</td>
	</tr>




	<tr name="AIPI_07-07-005">
		<td align="center"><b>AIPI 07-07-005</b></td>
		<td colspan="5"><a href="javascript:onclick=Voir_TR('AIPI_07-07-005',5)">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-07-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-07-005.pdf');">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">26/11/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-07-005 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-07-005_2015-11_A2_FR.pdf');">Pose de ruban adhésif autour des gaines de protection contre lesinterférences électromagnétiques EN4674-003 et EN4674-004</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">31/03/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-07-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-07-005.pdf');">INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-07-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI_AIPS_07-07-005.xls');\">";}?>MCQ INSTALLATION OF EMI PROTECTION SLEEVES EN4674-003 AND EN4674-004<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/01/2016</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AIPI/AIPS 07-07-005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AIPI_AIPS_07-07-005.xls');\">";}?>QCM Mise en oeuvre des gaines textiles blind?es EN4674-003 et EN4674-004<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/01/2016</td>
	</tr>




<tr name="AIPI_07-08-004">
		<td align="center"><b>AIPI 07-08-004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-08-004',3)">REWORK OF ELECTRICAL AND OPTICAL CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-08-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-08-004_2022-05_B0.pdf');">REWORK OF ELECTRICAL AND OPTICAL CABLES</a></td>
		<td align="center">Ed-B0</td>
		<td align="center">05/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-08-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-08-004_2022-07_12.pdf');">REWORK OF ELECTRICAL AND OPTICAL CABLES</a></td>
		<td align="center">Ed-12</td>
		<td align="center">07/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-08-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS-07-08-004_Ind_B0&12.xls');\">";}?>MCQ REWORK OF ELECTRICAL AND OPTICAL CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/05/2022</td>
	</tr>




<tr name="AIPI_07-09-002">
		<td align="center"><b>AIPI 07-09-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-09-002',4)">Electrical and optical tests of aircraft wiring </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-09-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-09-002.pdf');">Electrical and optical tests of aircraft wiring </a></td>
		<td align="center">Ed-1</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-09-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-09-002_2010-05_1_FR.pdf');">Electrical and optical tests of aircraft wiring </a></td>
		<td align="center">Ed-1</td>
		<td align="center">05/2010</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-09-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-09-002_2016-08_4.pdf');">Electrical and optical tests of aircraft wiring </a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-09-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AIPI_AIPS_07-09-002.xls');\">";}?>MCQ/QCM Electrical and optical tests of aircraft wiring <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">21/05/2021</td>
	</tr>
	



	<tr name="AIPI_07-11-001">
		<td align="center"><b>AIPI 07-11-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-001',3)">MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-001_A6.pdf');">MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES</a></td>
		<td align="center">Ed-A6</td>
		<td align="center">12/2020</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-001.pdf');">MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES</a></td>
		<td align="center">Ed-5</td>
		<td align="center">11/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-11-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_07-11-001_Ind_A6&5.xls');\">";}?>MCQ MANUFACTURING AND INSTALLATION OF OPTICAL FIBRE CABLES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2020</td>
	</tr>



	<tr name="AIPI_07-11-002">
		<td align="center"><b>AIPI 07-11-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-002',3)">Termination of ABS0929-003 and ABS0929-004 single way optical connector onto ABS0963-003 LF optical cable</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-002_2022-11_A2.pdf');">Termination of ABS0929-003 and ABS0929-004 single way optical connector onto ABS0963-003 LF optical cable</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">11/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-002_2021-05_4.pdf');">Termination of ABS0929-003 and ABS0929-004 single way optical connector onto ABS0963-003 LF optical cable</a></td>
		<td align="center">Ed-4</td>
		<td align="center">05/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-11-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-AIPI_AIPS_07-11-002_EN A2 & 4.xls');\">";}?>MCQ/QCM Termination of ABS0929-003 and ABS0929-004 single way optical connector onto ABS0963-003 LF optical cable<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/11/2022</td>
	</tr>



	<tr name="AIPI_07-11-003">
		<td align="center"><b>AIPI 07-11-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-003',3)">Installation of ABS1379-003 optical contact with ABS0963-003 LF and ABS2293 LG fibre optic cables</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-003_2022-06_A4.pdf');">Installation of ABS1379-003 optical contact with ABS0963-003 LF and ABS2293 LG fibre optic cables</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-003_2021-05_6.pdf');">Installation of ABS1379-003 optical contact with ABS0963-003LF and ABS2293LG fibre optic cables</a></td>
		<td align="center">Ed-6</td>
		<td align="center">05/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-11-003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_07-11-003_Ind_A4&6.xls');\">";}?>MCQ/QCM Installation of ABS1379-003 optical contact with ABS0963-003LF and ABS2293LG fibre optic cables<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/06/2022</td>
	</tr>
	



	<tr name="AIPI_07-11-004">
		<td align="center"><b>AIPI 07-11-004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-004',3)">ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-004_2022-06_A2.pdf');">ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-004_2021-05_3.pdf');">ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">05/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 07-11-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM- AIPI_AIPS_07-11-004 Ind A2.xls');\">";}?>MCQ/QCM ASSEMBLY OF ABS1906 OPTICAL CONTACT ON ABS0963 TYPE LF OPTICAL CABLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/06/2022</td>
	</tr>
	



	<tr name="AIPI_07-11-005">
		<td align="center"><b>AIPI 07-11-005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-005',4)">Insertion loss measurement on optical links</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-005.pdf');">Insertion loss measurement on optical links</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/12/2015</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-005 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-005_2015-12_A2_FR.pdf');">Mesure des pertes d’insertion sur les liaisons optiques</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/04/2016</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-005.pdf');">Insertion loss measurement on optical links</a></td>
		<td align="center">Ed-3</td>
		<td align="center">12/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPIAIPS 07-11-005</b></td>/
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AIPI_AIPS_07-11-005_EN.xls');\">";}?>MCQ Insertion loss measurement on optical links<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		




	<tr name="AIPI_07-11-006">
		<td align="center"><b>AIPI 07-11-006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-006',4)">FIBRE OPTIC TECHNOLOGY ? CLEANING METHODS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-006_2017-03_A3 En.pdf');">FIBRE OPTIC TECHNOLOGY ? CLEANING METHODS</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">13/03/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-006 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-006_2017-03_A3_FR.pdf');">Technologies des fibres optiques – Méthodes de nettoyage</a></td>
		<td align="center">Ed-A3</td>
		<td align="center">03/04/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-006.pdf');">FIBRE OPTIC TECHNOLOGY ? CLEANING METHODS</a></td>
		<td align="center">Ed-5</td>
		<td align="center">01/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-11-006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI 07-11-006_Ind_A3_&_5.xls');\">";}?>MCQ FIBRE OPTIC TECHNOLOGY ? CLEANING METHODS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/04/2017</td>
	</tr>




	<tr name="AIPI_07-11-007">
		<td align="center"><b>AIPI 07-11-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_07-11-007',4)">FIBRE OPTIC INSTALLATIONS ? FAULT DIAGNOSIS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-007_2017-02_A2.pdf');">FIBRE OPTIC INSTALLATIONS ? FAULT DIAGNOSIS</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">21/02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 07-11-007 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_07-11-007_2017-02_A2_FR.pdf');">Fibres optiques – Diagnostic de panne</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">31/03/2017</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 07-11-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_07-11-007_2017-02_3.pdf');">FIBRE OPTIC INSTALLATIONS ? FAULT DIAGNOSIS</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 07-11-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-AIPI 07-11-007_Ind_A2_&_3.xls');\">";}?>MCQ FIBRE OPTIC INSTALLATIONS ? FAULT DIAGNOSIS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/04/2017</td>
	</tr>




	<tr name="AIPI_08-02-002">
		<td align="center"><b>AIPI 08-02-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_08-02-002',4)">Installation of identification labels in hydraulic fluid areas</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 08-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_08-02-002_2013-01_A0.pdf');">Installation of identification labels in hydraulic fluid areas</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">01/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 08-02-002 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_08-02-002_2013-01_A0_FR.pdf');">POSE DES ETIQUETTES D’IDENTIFICATION DANS LES ZONES EXPOSEES AU FLUIDE HYDRAULIQUE</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">27/06/2013</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 08-02-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_08-02-002_1996-10_1.pdf');">Installation of identification labels in hydraulic fluid areas</a></td>
		<td align="center">Ed-1</td>
		<td align="center">10/1996</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS 08-02-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_08-02-002_Ind_A0&1.xlsx');\">";}?>MCQ/QCM Installation of identification labels in hydraulic fluid areas<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/05/2018</td>
	</tr>



	<tr name="AIPI_08-02-004">
		<td align="center"><b>AIPI 08-02-004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_08-02-004',3)">Printing and installation of thermal activated identification tapes</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 08-02-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_08-02-004_2021-02_A2.pdf');">Printing and installation of thermal activated identification tapes</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">02/2021</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 08-02-004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_08-02-004_2021-02_3.pdf');">Printing and installation of thermal activated identification tapes</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 08-02-004</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS_08-02-004_Ind_A2&3.xlsx');\">";}?>MCQ Printing and installation of thermal activated identification tapes<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">01/03/2021</td>
	</tr>


<tr name="AIPI_08-03-002">
		<td align="center"><b>AIPI 08-03-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_08-03-002',1)">Permanent marking with ink</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 08-03-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_08-03-002_2017-07_A2.pdf');">Permanent marking with ink</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">18/07/2017</td>
	</tr>

<tr name="AIPI_09-01-002">
		<td align="center"><b>AIPI 09-01-002</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_09-01-002',3)"> Cleaning with liquid non aqueous agents including vapour phase </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 09-01-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_09-01-002_2022-06_A5.pdf');"> Cleaning with liquid non aqueous agents including vapour phase </a></td>
		<td align="center">Ed-A5</td>
		<td align="center">06/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 09-01-002 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_09-01-002_2021-09_5.pdf');"> Cleaning with liquid non aqueous agents including vapour phase </a></td>
		<td align="center">Ed-5</td>
		<td align="center">09/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AIPI/AIPS_09-01-002</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AIPI-AIPS_09-01-002_Ind_A5&5.xls');\">";}?>MCQ/QCM Cleaning with liquid non aqueous agents including vapour phase <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/07/2022</td>
	</tr>

<tr name="AIPI_09-01-003">
		<td align="center"><b>AIPI 09-01-003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_09-01-003',1)">Cleaning With Aqueous Cleaning Agents</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 09-01-003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_09-01-003_2020-09_A4.pdf');">Cleaning With Aqueous Cleaning Agents</a></td>
		<td align="center">Ed-A4</td>
		<td align="center">09/2020</td>
	</tr>



	<tr name="AIPI_09-01-007">
		<td align="center"><b>AIPI 09-01-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_09-01-007',3)">CLEANING OF AIRCRAFT WINDOWS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 09-01-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_09-01-007.pdf');">CLEANING OF AIRCRAFT WINDOWS</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/2011</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 09-01-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_09-01-007.pdf');">CLEANING OF AIRCRAFT WINDOWS</a></td>
		<td align="center">Ed-6</td>
		<td align="center">04/2010</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 09-01-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AIPI-AIPS-09-01-007_Ind_1&6.xls');\">";}?>MCQ CLEANING OF AIRCRAFT WINDOWS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/05/2014</td>
	</tr>
	

	<tr name="AIPI_09-04-001">
		<td align="center"><b>AIPI 09-04-001</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AIPI_09-04-001',4)">SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPI 09-04-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPI_09-04-001_2022-02_A2.pdf');">SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION</a></td>
		<td align="center">Ed-A2</td>
		<td align="center">02/2022</td>
	</tr>
	<tr>
		<td align="center"><b>DOC AIPS 09-04-001 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AIPS_09-04-001_2021-10_3.pdf');">SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION</a></td>
		<td align="center">Ed-3</td>
		<td align="center">10/2021</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AIPI/AIPS 09-04-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM AIPI_AIPS_09-04-001.xls');\">";}?>QCM SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2022</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AIPI/AIPS 09-04-001</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ AIPI_AIPS_09-04-001.xls');\">";}?>MCQ SURFACE PREPARATION PRIOR TO EXTERNAL PAINT APPLICATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2022</td>
	</tr>



<tr name="AITM_2-0039">
		<td align="center"><b>AITM 2-0039</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_2-0039',2)">Electrical bonding connections resistance measurement Determination of electrical resistance of paints using the Coras instrument</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 2-0039 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_2-0039_1996-11_1.pdf');">Electrical bonding connections resistance measurement Determination of electrical resistance of paints using the Coras instrument</a></td>
		<td align="center">Ed-1</td>
		<td align="center">11/1996</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AITM 2-0039 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AITM_2-0039_Ind_1.xls');\">";}?>MCQ/QCM Electrical bonding connections resistance measurement Determination of electrical resistance of paints using the Coras instrument<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/03/2021</td>
	</tr>



<tr name="AITM_3-0007">
		<td align="center"><b>AITM 3-0007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_3-0007',2)">DROP (REACTION) TEST ON ALUMINIUM AND ALUMINIUM ALLOY</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 3-0007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_3-0007_Issue_1_EN.pdf');">DROP (REACTION) TEST ON ALUMINIUM AND ALUMINIUM ALLOY</a></td>
		<td align="center">Ed-1</td>
		<td align="center">11/1993</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AITM 3-0007 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_AITM_3-0007_Ind_1.xlsx');\">";}?>MCQ DROP (REACTION) TEST ON ALUMINIUM AND ALUMINIUM ALLOY<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">15/11/2018</td>
	</tr>



	<tr name="AITM_6-3004">
		<td align="center"><b>AITM 6-3004</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-3004',3)">VISUAL INSPECTION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 6-3004 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-3004_2021-09_9.pdf');">VISUAL INSPECTION</a></td>
		<td align="center">Ed-9</td>
		<td align="center">09/2021</td>
	</tr>
	<tr>
		<td align="center"><b>QCM-MCQ AITM 6-3004 </b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-AITM_6-3004- Ind 9 Visual_inspection.xls');\">";}?>QCM VISUAL INSPECTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/10/2021</td>
	</tr>



	<tr name="AITM_6-3005">
		<td align="center"><b>AITM 6-3005</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-3005',3)">VISUAL INSPECTION OF GLASS FIBRE COMPOSITES BY TRANSMITTED LIGHT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 6-3005 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-3005.pdf');">VISUAL INSPECTION OF GLASS FIBRE COMPOSITES BY TRANSMITTED LIGHT</a></td>
		<td align="center">Ed-2</td>
		<td align="center">09/2008</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ AITM 6-3005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM_6-3005_EN.xls');\">";}?>MCQ VISUAL INSPECTION OF GLASS FIBRE COMPOSITES BY TRANSMITTED LIGHT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/05/2014</td>
	</tr>
	<tr>
		<td align="center"><b>QCM AITM 6-3005</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AITM 6-3005_FR.xls');\">";}?>QCM Inspection visuelle des composites en fibres de verre par transmission de lumi?re<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/05/2014</td>
	</tr>




	<tr name="AITM_6-5003">
		<td align="center"><b>AITM 6-5003</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-5003',2)">TAP TEST</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 6-5003 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-5003_2019-08_6_EN.pdf');">TAP TEST</a></td>
		<td align="center">Ed-6</td>
		<td align="center">08/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AITM 6-5003</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_AITM_6-5003_Ind_6.xlsx');\">";}?>MCQ/QCM TAP TEST<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/02/2020</td>
	</tr>
	



<tr name="AITM_6-9006">
		<td align="center"><b>AITM 6-9006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-9006',2)">Detection of Defects in Insulating Coatings by means of Capacitive Measurement </span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 6-9006 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-9006.pdf');">Detection of Defects in Insulating Coatings by means of Capacitive Measurement</a></td>
		<td align="center">Ed-3</td>
		<td align="center">08/2014</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AITM 6-9006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM - AITM 6-9006.xls');\">";}?>MCQ/QCM Detection of Defects in Insulating Coatings by means of Capacitive Measurement <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/01/2016</td>
	</tr>




<tr name="AITM_6-9007">
		<td align="center"><b>AITM 6-9007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-9007',2)">ESN FLEXIBLE AND MECHANICAL JUNCTIONS MEASUREMENT</span></td>
	</tr>	
	<tr>
		<td align="center"><b>DOC AITM 6-9007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-9007_2016-08_4 EN.pdf');">ESN FLEXIBLE AND MECHANICAL JUNCTIONS MEASUREMENT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/2016</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AITM 6-9007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM-AITM_6-9007_Ind_4.xls');\">";}?>MCQ/QCM ESN FLEXIBLE AND MECHANICAL JUNCTIONS MEASUREMENT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/02/2020</td>
	</tr>




<tr name="AITM_6-9008">
		<td align="center"><b>AITM 6-9008</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AITM_6-9008',2)">ELECTRICAL BONDING CONNECTIONS RESISTANCE MEASUREMENT</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC AITM 6-9008 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AITM_6-9008-EN.pdf');">ELECTRICAL BONDING CONNECTIONS RESISTANCE MEASUREMENT</a></td>
		<td align="center">Ed-4</td>
		<td align="center">12/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM AITM 6-9008</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_QCM_AITM_6-9008_Ind_4_&_AITM_2-0039_Ind_1.xls');\">";}?>MCQ/QCM ELECTRICAL BONDING CONNECTIONS RESISTANCE MEASUREMENT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/12/2017</td>
	</tr>


	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- STELIA AEROSPACE (AEROLIA) ----------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="ANNEXES STELIA AEROSPACE (AEROLIA)">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>STELIA AEROSPACE (EX-AEROLIA) ANNEXES / APPENDICES</b></td>
	</tr>
	<tr name="GENE_1_STELIA AEROSPACE">
		<td align="center"><b>SA GENE 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_1_STELIA AEROSPACE',2)">STELIA AEROSPACE SERRAGE AU COUPLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_01_FR_ANNEXE_SA.ppt');">FORMATION ANNEXE STELIA AEROSPACE SERRAGE AU COUPLE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">02/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_01_FR_QCM_SA.xlsx');\">";}?>QCM ANNEXE STELIA AEROSPACE SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">22/08/2016</td>
	</tr>
	<tr name="GENE_2_STELIA AEROSPACE">
		<td align="center"><b>SA GENE 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_2_STELIA AEROSPACE',2)">STELIA AEROSPACE METALLISATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_02_FR_ANNEXE_SA.ppt');">FORMATION ANNEXE STELIA AEROSPACE METALLISATION</a></td>
		<td align="center">Ed-5</td>
		<td align="center">02/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_02_FR_QCM_SA.xlsx');\">";}?>QCM ANNEXE STELIA AEROSPACE METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/09/2017</td>
	</tr>
	<tr name="GENE_3_STELIA AEROSPACE">
		<td align="center"><b>SA GENE 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_3_STELIA AEROSPACE',2)">STELIA AEROSPACE APPLICATION DES MASTICS ET PREPARATION DE SURFACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_03_FR_ANNEXE_SA.ppt');">FORMATION ANNEXE STELIA AEROSPACE APPLICATION DES MASTICS ET PREPARATION DE SURFACE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">24/07/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_03_FR_QCM_SA.xlsx');\">";}?>QCM ANNEXE STELIA AEROSPACE APPLICATION DES MASTICS ET PREPARATION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2015</td>
	</tr>
	<tr name="GENE_4_STELIA AEROSPACE">
		<td align="center"><b>SA GENE 4</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('GENE_4_STELIA AEROSPACE',2)">STELIA AEROSPACE PROTECTION DE SURFACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 4 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('GENE_04_FR_ANNEXE_SA.ppt');">FORMATION ANNEXE STELIA AEROSPACE PROTECTION DE SURFACE</a></td>
		<td align="center">Ed-7</td>
		<td align="center">02/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA GENE 4 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('GENE_04_FR_QCM_SA.xlsx');\">";}?>QCM ANNEXE STELIA AEROSPACE PROTECTION DE SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/09/2016</td>
	</tr>
	<tr name="AJU_1_STELIA AEROSPACE">
		<td align="center"><b>SA AJU 1</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_1_STELIA AEROSPACE',2)">STELIA AEROSPACE RIVETAGE STRUCTURAL</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA AJU 1 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_01_FR_ANNEXE_SA.ppt');">FORMATION ANNEXE STELIA AEROSPACE RIVETAGE STRUCTURAL</a></td>
		<td align="center">Ed-6</td>
		<td align="center">25/03/2020</td>
	</tr>
	<tr>
		<td align="center"><b>SA AJU 1 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_01_FR_QCM_SA.xlsx');\">";}?>QCM ANNEXE STELIA AEROSPACE RIVETAGE STRUCTURAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/02/2015</td>
	</tr>
	<tr name="AJU_2_STELIA AEROSPACE">
		<td align="center"><b>SA AJU 2</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_2_STELIA AEROSPACE',2)">STELIA AEROSPACE POSE DE FIXATIONS SPECIALES</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA AJU 2 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_02_FR_ANNEXE_SA.ppt');">FORMATION ANNEXE STELIA AEROSPACE POSE DE FIXATIONS SPECIALES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">17/05/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA AJU 2 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_02_FR_QCM_SA.xlsx');\">";}?>QCM ANNEXE STELIA AEROSPACE POSE DE FIXATIONS SPECIALES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/08/2016</td>
</tr>

<tr name="AJU_3_STELIA AEROSPACE">
		<td align="center"><b>SA AJU 3</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AJU_3_STELIA AEROSPACE',2)">STELIA AEROSPACE EMMANCHEMENT DE BAGUES MONTEES SERREES</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA AJU 3 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AJUS_03_FR_SA.pdf');">FORMATION ANNEXE STELIA AEROSPACE EMMANCHEMENT DE BAGUES MONTEES SERREES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">27/07/2015</td>
	</tr>
	<tr>
		<td align="center"><b>AJU 3 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AJUS_03_FR_QCM_SA.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE EMMANCHEMENT DE BAGUES MONTEES SERREES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/07/2015</td>
</tr>

<tr name="MES_01_STELIA AEROSPACE">
		<td align="center"><b>SA MES 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('MES_01_STELIA AEROSPACE',2)">STELIA AEROSPACE MESURE DE CONTINUITE ELECTRIQUE</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA MES 01 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MES_01_SA.pdf');">FORMATION ANNEXE STELIA AEROSPACE MESURE DE CONTINUITE ELECTRIQUE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/06/2017</td>
	</tr>
	<tr>
		<td align="center"><b>SA MES 01 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MES_01_FR_QCM_SA.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE MESURE DE CONTINUITE ELECTRIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/06/2017</td>
	</tr>

</tr>


	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- STELIA AEROSPACE (SOGERMA) ----------------------->
	<!--###################################################-->
	<!--###################################################-->
	<tr name="ANNEXES STELIA AEROSPACE (SOGERMA)">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>STELIA AEROSPACE (EX-SOGERMA) ANNEXES / APPENDICES</b></td>
	</tr>

<tr name="VEL_01_STELIA AEROSPACE">
		<td align="center"><b>SA VEL 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('VEL_01_STELIA AEROSPACE',2)">STELIA AEROSPACE ATTACHMENT OF SELF-ADHESIVE</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA VEL 01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('APPENDIX SEP010.pdf');">TRAINING STELIA AEROSPACE APPENDIX ATTACHMENT OF SELF-ADHESIVE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">23/09/2015</td>
	</tr>
	<tr>
		<td align="center"><b>SA VEL 01 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_ APPENDIX_SEP010_VEL-01.xls');\">";}?>MCQ STELIA AEROSPACE APPENDIX ATTACHMENT OF SELF-ADHESIVE"<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2015</td>
	</tr>


<tr name="DECO_01_STELIA AEROSPACE">
		<td align="center"><b>SA DECO 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DECO_01_STELIA AEROSPACE',3)">STELIA AEROSPACE INSTALLATION OF DECORATIVE FILM</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA DECO 01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ANNEXE SEP011_DECO-01.pdf');">TRAINING STELIA AEROSPACE APPENDIX INSTALLATION OF DECORATIVE FILM</a></td>
		<td align="center">Ed-2</td>
		<td align="center">25/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA DECO 1 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ SEP011 EN.pdf');\">";}?>MCQ STELIA AEROSPACE APPENDIX INSTALLATION OF DECORATIVE FILM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/09/2015</td>
	<tr>
		<td align="center"><b>SA DECO 01 (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM SEP011 FR.pdf');\">";}?>QCM ANNEXE STELIA AEROSPACE INSTALLATION OF DECORATIVE FILM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/09/2015</td>
	</tr>



<tr name="PEIN_01_STELIA AEROSPACE">
		<td align="center"><b>SA PEIN 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('PEIN_01_STELIA AEROSPACE',2)">STELIA AEROSPACE PAINTING OF COMPOSITE & PLASTIC PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>SA PEIN 01 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('ANNEXE SEP012_PEIN-01.pdf');">TRAINING STELIA AEROSPACE APPENDIX PAINTING OF COMPOSITE & PLASTIC PARTS</a></td>
		<td align="center">Ed-2</td>
		<td align="center">25/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>SA PEIN 01 (EN)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ APPENDIX SEP012.pdf');\">";}?>MCQ STELIA AEROSPACE APPENDIX PAINTING OF COMPOSITE & PLASTIC PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/09/2015</td>
	</tr>





	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- STELIA AEROSPACE IP & CPAE------>
	<!--###################################################-->
	<!--###################################################-->
	
    <tr name="STELIA AEROSPACE IP">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>STELIA AEROSPACE IP - SEP - CPAE</b></td>
	</tr>


	<tr name="IP 37-05">
		<td align="center"><b>IP 37-05</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 37-05',1)">PREPARATION DES TROUS POUR POSE DE FIXATIONS - MODE OPERATOIRE</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 37-05</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_37-05_Ind_A5.xls');\">";}?>QCM : PREPARATION DES TROUS POUR POSE DE FIXATIONS - MODE OPERATOIRE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">11/03/2021</td>
	</tr>



<tr name="IP 62-30">
		<td align="center"><b>IP 62-30</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 62-30',1)">RETOUCHE CHROMATATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 62-30</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_62-30_Ind_A6.xlsx');\">";}?>QCM : RETOUCHE CHROMATATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>


<tr name="IP 64-02">
		<td align="center"><b>IP 64-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 64-02',1)">APPLICATION ET POLYMERISATION DES PEINTURES ELEMENTAIRES PIECES METALLIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 64-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_64-02_Ind_A9.xlsx');\">";}?>QCM : APPLICATION ET POLYMERISATION DES PEINTURES ELEMENTAIRES PIECES METALLIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>



<tr name="IP 64-15">
		<td align="center"><b>IP 64-15</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 64-15',1)">PROTECTION DE SURFACE DES FIXATIONS ET MASTICS PAR APPLICATION PEINTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 64-15</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_64-15_Ind_A2.xlsx');\">";}?>QCM : PROTECTION DE SURFACE DES FIXATIONS ET MASTICS PAR APPLICATION PEINTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>


<tr name="IP 69-02">
		<td align="center"><b>IP 69-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 69-02',1)">RETOUCHE DE PEINTURES SUR PIECES STRUCTURALES METALLIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 69-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_69-02_Ind_A9.xlsx');\">";}?>QCM : RETOUCHE DE PEINTURES SUR PIECES STRUCTURALES METALLIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>


<tr name="IP 71-00">
		<td align="center"><b>IP 71-00</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 71-00',1)">ASSEMBLAGE GENERAL - MONTAGE DES FIXATIONS</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 71-00</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IP_71-00_Ind_A2.xlsx');\">";}?>QCM : ASSEMBLAGE GENERAL - MONTAGE DES FIXATIONS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/03/2021</td>
	</tr>



<tr name="IP 71-01">
		<td align="center"><b>IP 71-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 71-01',1)">MONTAGE DES BOULONS CYLINDRIQUES DE CISAILLEMENT</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 71-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_71-01_Ind_A3.xlsx');\">";}?>QCM : MONTAGE DES BOULONS CYLINDRIQUES DE CISAILLEMENT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>



<tr name="IP 71-03">
		<td align="center"><b>IP 71-03</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 71-03',2)">COUPLE DE SERRAGE POUR LES ELEMENTS DE FIXATION STRUCTURALE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IP 71-03 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IP_71-03_Part 000.pdf');">COUPLE DE SERRAGE POUR LES ELEMENTS DE FIXATION STRUCTURALE</a></td>
		<td align="center">Ed-A0</td>
		<td align="center">04/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 71-03</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IP_71-03_Ind_A6.xlsx');\">";}?>QCM : COUPLE DE SERRAGE POUR LES ELEMENTS DE FIXATION STRUCTURALE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>



<tr name="IP 71-12">
		<td align="center"><b>IP 71-12</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 71-12',1)">RIVETAGES STRUCTURAUX</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 71-12</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_71-12_Ind_A5.xlsx');\">";}?>QCM : RIVETAGES STRUCTURAUX<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>



<tr name="IP 71-14">
		<td align="center"><b>IP 71-14</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 71-14',2)">POSE DES FIXATIONS AVEUGLES – GENERALITES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IP 71-14 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IP_71-14_Part 000.pdf');">POSE DES FIXATIONS AVEUGLES – GENERALITES</a></td>
		<td align="center">Ed-A1</td>
		<td align="center">04/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 71-14</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-MCQ_IP_71-14_Ind_A2.xlsx');\">";}?>QCM : POSE DES FIXATIONS AVEUGLES – GENERALITES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">10/03/2021</td>
	</tr>



	<tr name="IP 77-01">
		<td align="center"><b>IP 77-01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 77-01',1)">APPLICATION DES MASTICS</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 77-01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_77-01_Ind_A0.xlsx');\">";}?>QCM : APPLICATION DES MASTICS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/06/2018</td>
	</tr>


	<tr name="IP 83-11">
		<td align="center"><b>IP 83-11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP 83-11',1)">METALLISATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 83-11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_IP_83-11_Ind_A6.xlsx');\">";}?>QCM : METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2021</td>
	</tr>



<tr name="CPAE_37-05 / CPAE_71-50">
		<td align="center"><b>CPAE_37-05 / CPAE_71-50</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CPAE_37-05 / CPAE_71-50',2)">STELIA AEROSPACE PERCAGE EMPILAGE SUR A350</span></td>
	</tr>
	<tr>
		<td align="center"><b>CPAE_37-05 / CPAE_71-50</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE STELIA AEROSPACE PERCAGE EMPILAGE SUR A350 (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">xx-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>CPAE_37-05 / CPAE_71-50</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM CPAE_37-05-CPAE_71-50.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE PERCAGE EMPILAGE SUR A350<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/08/2016</td>
</tr>



<tr name="CPAE 48-40">
		<td align="center"><b>CPAE 48-40</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CPAE 48-40',2)">STELIA AEROSPACE REPARATION COSMETIQUE SUR PE COMPOSITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 48-40</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE STELIA AEROSPACE REPARATION COSMETIQUE SUR PE COMPOSITE (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">xx-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 48-40</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM CPAE 48-40.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE REPARATION COSMETIQUE SUR PE COMPOSITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/12/2016</td>
</tr>




<tr name="CPAE 66-02">
		<td align="center"><b>CPAE 66-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CPAE 66-02',2)">STELIA AEROSPACE REPARATION DES DEFAUTS DE SURFACE SUR ALLIAGES D ALUMINIUM</span></td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 66-02</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE STELIA AEROSPACE REPARATION DES DEFAUTS DE SURFACE SUR ALLIAGES D ALUMINIUM (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">xx-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 66-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM CPAE 66-02.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE REPARATION DES DEFAUTS DE SURFACE SUR ALLIAGES D ALUMINIUM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">26/01/2017</td>
</tr>



<tr name="CPAE 69-02">
		<td align="center"><b>CPAE 69-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CPAE 69-02',2)">STELIA AEROSPACE RETOUCHE PEINTURE</span></td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 69-02</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE STELIA AEROSPACE RETOUCHE PEINTURE (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">xx-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 69-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM CPAE 69-02.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE RETOUCHE PEINTURE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">07/09/2016</td>
</tr>





<tr name="CPAE 74-06">
		<td align="center"><b>CPAE 74-06</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CPAE 74-06',2)">STELIA AEROSPACE COLLAGE DES SUPPORTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 74-06</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE STELIA AEROSPACE COLLAGE DES SUPPORTS (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">xx-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 74-06</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM CPAE 74-06.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE COLLAGE DES SUPPORTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">16/12/2016</td>
</tr>

<tr name="CPAE 79-02">
		<td align="center"><b>CPAE 79-02</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('CPAE 79-02',2)">STELIA AEROSPACE CALAGE PAR INTERPOSITION DE RESINE</span></td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 79-02</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE STELIA AEROSPACE CALAGE PAR INTERPOSITION DE RESINE (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">xx-x</td>
		<td align="center">xx/xx/xxxx</td>
	</tr>
	<tr>
		<td align="center"><b>CPAE 79-02</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM CPAE 79-02.xls');\">";}?>QCM ANNEXE STELIA AEROSPACE CALAGE PAR INTERPOSITION DE RESINE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/08/2016</td>
</tr>


<tr name="IP_01_STELIA AEROSPACE">
		<td align="center"><b>IP 01</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_01_STELIA AEROSPACE',1)">RETOUCHE DE CHROMATATION AU STICK ALODINE® 1132 SUR ALLIAGES D’ALUMINIUM</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IP-001_Ind_F.xlsx');\">";}?>QCM RETOUCHE DE CHROMATATION AU STICK ALODINE® 1132 SUR ALLIAGES D’ALUMINIUM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/07/2018</td>
	</tr>



<tr name="IP_09_STELIA AEROSPACE">
		<td align="center"><b>IP 09</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_09_STELIA AEROSPACE',1)">POSE DE FIXATIONS SUR MASTIC INTERCALAIRE NON POLYMERISE</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 09</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_09_QCM.xls');\">";}?>QCM POSE DE FIXATIONS SUR MASTIC INTERCALAIRE NON POLYMERISE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/12/2017</td>
	</tr>



	<tr name="IP_11_STELIA AEROSPACE">
		<td align="center"><b>IP 11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_11_STELIA AEROSPACE',1)">SERRAGE AU COUPLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_011_QCM.xls');\">";}?>QCM SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/12/2017</td>
	</tr>



	<tr name="IP_17_STELIA AEROSPACE">
		<td align="center"><b>IP 17</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_17_STELIA AEROSPACE',1)">MONTAGE DES BOULONS CYLINDRIQUES DE CISAILLEMENT</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_011_QCM.xls');\">";}?>QCM MONTAGE DES BOULONS CYLINDRIQUES DE CISAILLEMENT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/12/2017</td>
	</tr>



	<tr name="IP_18_STELIA AEROSPACE">
		<td align="center"><b>IP 18</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_18_STELIA AEROSPACE',1)">RIVETAGES STRUCTURAUX</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 18</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_018_QCM.xls');\">";}?>QCM RIVETAGES STRUCTURAUX<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/12/2017</td>
	</tr>



	<tr name="IP_22_STELIA AEROSPACE">
		<td align="center"><b>IP 22</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_22_STELIA AEROSPACE',1)">APPLICATION DES MASTICS</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 22</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_022_QCM.xls');\">";}?>QCM APPLICATION DES MASTICS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/12/2017</td>
	</tr>



	<tr name="IP_23_STELIA AEROSPACE">
		<td align="center"><b>IP 23</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_23_STELIA AEROSPACE',1)">METALLISATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 23</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_023_QCM.xls');\">";}?>QCM METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">13/12/2017</td>
	</tr>



	<tr name="IP_25_STELIA AEROSPACE">
		<td align="center"><b>IP 25</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_25_STELIA AEROSPACE',1)">PERÇAGE, ALÉSAGE, FRAISURAGE, LAMAGE DES COMPOSITES CARBONES OU DES EMPILAGES COMPOSITES CARBONE/MÉTALLIQUE</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 25</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IP-025_Ind_C.xlsx');\">";}?>QCM PERÇAGE, ALÉSAGE, FRAISURAGE, LAMAGE DES COMPOSITES CARBONES OU DES EMPILAGES COMPOSITES CARBONE/MÉTALLIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/07/2018</td>
	</tr>



	<tr name="IP_26_STELIA AEROSPACE">
		<td align="center"><b>IP 26</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_26_STELIA AEROSPACE',1)">FRAISAGE, DÉTOURAGE DES COMPOSITES CARBONES</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 26</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IP-026_Ind_A.xlsx');\">";}?>QCM FRAISAGE, DÉTOURAGE DES COMPOSITES CARBONES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/07/2018</td>
	</tr>




	<tr name="IP_29_STELIA AEROSPACE">
		<td align="center"><b>IP 29</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IP_29_STELIA AEROSPACE',1)">Montage des fixations aveugles</span></td>
	</tr>
	<tr>
		<td align="center"><b>QCM IP 29</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('IP_029_QCM.xls');\">";}?>QCM Montage des fixations aveugles<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">12/12/2017</td>
	</tr>


	<tr name="SEP_006_STELIA AEROSPACE">
		<td align="center"><b>SEP 006</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_006_STELIA AEROSPACE',2)">Densification, bordurage, pose d'inserts et de douilles</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 006</b></td>
		<td><a href="javascript:OuvrirFichier('SEP006-rev_I.pdf');">Densification, bordurage, pose d'inserts et de douilles</a></td>
		<td align="center">Ed-I</td>
		<td align="center">12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ-QCM SEP 006</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_SEP_006_Ind_I.xls');\">";}?>QCM Densification, bordurage, pose d'inserts et de douilles<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">18/08/2020</td>
	</tr>


	
<tr name="SEP_009_STELIA AEROSPACE">
		<td align="center"><b>SEP 009</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_009_STELIA AEROSPACE',2)">SERRAGE DE LA BOULONNERIE</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 009</b></td>
		<td><a href="javascript:OuvrirFichier('SEP009-rev_D.pdf');">SERRAGE DE LA BOULONNERIE</a></td>
		<td align="center">Ed-D</td>
		<td align="center">03/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ SEP 009</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_SEP009-Issue_D.xls');\">";}?>MCQ SERRAGE DE LA BOULONNERIE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/05/2019</td>
	</tr>



<tr name="SEP_010_STELIA AEROSPACE">
		<td align="center"><b>SEP 010</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_010_STELIA AEROSPACE',1)">POSE DE RUBANS AUTO-AGRIPPANTS ADHESIFS</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 010</b></td>
		<td><a href="javascript:OuvrirFichier('SEP010-rev_C.pdf');">POSE DE RUBANS AUTO-AGRIPPANTS ADHESIFS</a></td>
		<td align="center">Ed-C</td>
		<td align="center">06/2020</td>
	</tr>



<tr name="SEP_011_STELIA AEROSPACE">
		<td align="center"><b>SEP 011</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_011_STELIA AEROSPACE',2)">POSE DE FILM DECORATIF</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 011</b></td>
		<td><a href="javascript:OuvrirFichier('SEP011-rev_E.pdf');">POSE DE FILM DECORATIF</a></td>
		<td align="center">Ed-E</td>
		<td align="center">06/2020</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ-QCM SEP 011</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_SEP_011_Ind_E.xls');\">";}?>MCQ POSE DE FILM DECORATIF<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">20/08/2020</td>
	</tr>



<tr name="SEP_012_STELIA AEROSPACE">
		<td align="center"><b>SEP 012</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_012_STELIA AEROSPACE',2)">PEINTURE DES PIECES COMPOSITES ET PLASTIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 012</b></td>
		<td><a href="javascript:OuvrirFichier('SEP012-rev_G.pdf');">PEINTURE DES PIECES COMPOSITES ET PLASTIQUES</a></td>
		<td align="center">Ed-G</td>
		<td align="center">12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM SEP 012</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_SEP_012_Ind_G.xls');\">";}?>QCM PEINTURE DES PIECES COMPOSITES ET PLASTIQUES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/08/2020</td>
	</tr>



<tr name="SEP_013_STELIA AEROSPACE">
		<td align="center"><b>SEP 013</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_013_STELIA AEROSPACE',2)">PAINTING 0F METALLIC PARTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 013</b></td>
		<td><a href="javascript:OuvrirFichier('SEP013-rev_D.pdf');">PAINTING 0F METALLIC PARTS</a></td>
		<td align="center">Ed-D</td>
		<td align="center">04/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM SEP 013</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_MCQ_SEP_013_Ind_D.xls');\">";}?>MCQ/QCM PAINTING 0F METALLIC PARTS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">23/09/2020</td>
	</tr>



<tr name="SEP_027_STELIA AEROSPACE">
		<td align="center"><b>SEP 027</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('SEP_027_STELIA AEROSPACE',2)">STRUCTURAL AND NON-STRUCTURAL ADHESIVE BONDING</span></td>
	</tr>
	<tr>
		<td align="center"><b>SEP 027</b></td>
		<td><a href="javascript:OuvrirFichier('SEP027-rev_G.pdf');">STRUCTURAL AND NON-STRUCTURAL ADHESIVE BONDING</a></td>
		<td align="center">Ed-G</td>
		<td align="center">07/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM SEP 027</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_SEP_027_Ind_G.xlsx');\">";}?>MCQ/QCM STRUCTURAL AND NON-STRUCTURAL ADHESIVE BONDING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/08/2020</td>
	</tr>







	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- AIRBUS HELICOPTERS ----------------------->
	<!--###################################################-->
	<!--###################################################-->


	<tr name="ANNEXES AIRBUS HELICOPTERS">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>AIRBUS HELICOPTERS ANNEXES / APPENDICES</b></td>
	</tr>

<tr name="AH IFMA TORQ">
		<td align="center"><b>AH IFMA TORQ</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA TORQ',2)">AIRBUS HELICOPTERS SERRAGE AU COUPLE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA TORQ (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH SERRAGE AU COUPLE (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA TORQ (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_TORQ.xls');\">";}?>QCM ANNEXE AH SERRAGE AU COUPLE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">17/06/2022</td>
</tr>

	<tr name="AH IFMA FREIN">
		<td align="center"><b>AH IFMA FREIN</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA FREIN',2)">AIRBUS HELICOPTERS FREINAGE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA FREIN (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MODULE_AH_FREIN.ppt');">FORMATION ANNEXE AH FREINAGE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">19/05/2020</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA FREIN (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_FREIN.xls');\">";}?>QCM ANNEXE AH FREINAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2022</td>
	</tr>



<tr name="AH IFMA METAL">
		<td align="center"><b>AH IFMA METAL</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA METAL',2)">AIRBUS HELICOPTERS METALLISATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA METAL (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH METALLISATION (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA METAL (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_METAL.xls');\">";}?>QCM ANNEXE AH METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2023</td>
</tr>

<tr name="AH IFMA REL_METAL">
		<td align="center"><b>AH IFMA REL_METAL</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA REL_METAL',2)">AIRBUS HELICOPTERS RELEVE DE METALLISATION</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA REL_METAL (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MODULE_AH_REL_METAL.ppt');">FORMATION ANNEXE AH RELEVE DE METALLISATION</a></td>
		<td align="center">Ed-1</td>
		<td align="center">09/05/2019</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA REL_METAL (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_REL_METAL.xls');\">";}?>QCM ANNEXE AH RELEVE DE METALLISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">27/01/2022</td>
</tr>
		<tr name="AH IFMA PRSU">
		<td align="center"><b>AH IFMA PRSU</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA PRSU',2)">AIRBUS HELICOPTERS PREPARATION DE SURFACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA PRSU (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH PREPARATION SURFACE (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA PRSU (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_PRSU.xls');\">";}?>QCM ANNEXE AH PREPARATION SURFACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/05/2022</td>
</tr>


<tr name="AH IFMA MASTIC">
		<td align="center"><b>AH IFMA MASTIC</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA MASTIC',2)">AIRBUS HELICOPTERS APPLICATION DES MASTICS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA MASTIC (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH APPLICATION DES MASTICS (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA MASTIC (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_MASTIC.xls');\">";}?>QCM ANNEXE AH APPLICATION DES MASTICS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>


<tr name="AH IFMA ALO">
		<td align="center"><b>AH IFMA ALO</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA ALO',2)">AIRBUS HELICOPTERS CONVERSION CHIMIQUE DES ALLIAGES D'ALUMINIUM</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA ALO(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH CONVERSION CHIMIQUE DES ALLIAGES D'ALUMINIUM (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA ALO (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_ALO.xls');\">";}?>QCM ANNEXE AH CONVERSION CHIMIQUE DES ALLIAGES D'ALUMINIUM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">08/11/2023</td>
</tr>


<tr name="AH IFMA ALES">
		<td align="center"><b>AH IFMA ALES</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA ALES',2)">AIRBUS HELICOPTERS ALESAGE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA ALES (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('MODULE_AH_ALES.ppt');">FORMATION ANNEXE AH ALESAGE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/05/2020</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA ALES (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_ALES.xls');\">";}?>QCM ANNEXE AH ALESAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
	</tr>



<tr name="AH IFMA RIVET">
		<td align="center"><b>AH IFMA RIVET</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA RIVET',2)">AIRBUS HELICOPTERS RIVETAGE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA RIVET (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH RIVETAGE (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA RIVET (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_RIVET.xls');\">";}?>QCM ANNEXE AH RIVETAGE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/05/2022</td>
	</tr>


<tr name="AH IFMA CNS">
		<td align="center"><b>AH IFMA CNS</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA CNS',2)">AIRBUS HELICOPTERS COLLAGE NON STRUCTURAL</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA CNS(FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH COLLAGE NON STRUCTURAL (EN DIRECT SUR DOCS CLIENT) </a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA CNS (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_CNS.xls');\">";}?>QCM ANNEXE AH COLLAGE NON STRUCTURAL<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>


<tr name="AH IFMA COMPO_MONO">
		<td align="center"><b>AH IFMA COMPO_MONO</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA COMPO_MONO',2)">AIRBUS HELICOPTERS COMPOSITE MONOLYTHIQUE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA COMPO_MONO (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH COMPOSITE MONOLITHIQUE (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA COMPO_MONO (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_COMPO_MONO.xls');\">";}?>QCM ANNEXE AH COMPOSITE MONOLITHIQUE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>

<tr name="AH IFMA POLYM">
		<td align="center"><b>AH IFMA POLYM</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA POLYM',2)">AIRBUS HELICOPTERS POLYMERISATION</span></td>
	</tr>

	<tr>
		<td align="center"><b>AH IFMA POLYM (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH POLYMERISATION (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA POLYM (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_POLYM.xls');\">";}?>QCM ANNEXE AH POLYMERISATION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">29/08/2022</td>
</tr>


	
	<tr name="AH IFMA ANITA">
		<td align="center"><b>AH IFMA ANITA</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA ANITA',2)">AIRBUS HELICOPTERS VALISE DE POLYMERISATION (ANITA)</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA ANITA (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH VALISE DE POLYMERISATION (ANITA) (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA ANITA (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_ANITA.xls');\">";}?>QCM ANNEXE AH VALISE DE POLYMERISATION (ANITA)<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
	</tr>




<tr name="AH IFMA REP_COMPO">
		<td align="center"><b>AH IFMA REP_COMPO</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA REP_COMPO',2)">AIRBUS HELICOPTERS REPARATION COMPOSITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA REP_COMPO (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH REPARATION COMPOSITE (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA REP_COMPO (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_REP_COMPO.xls');\">";}?>QCM ANNEXE AH REPARATION COMPOSITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>



	
	<tr name="AH IFMA INSERT">
		<td align="center"><b>AH IFMA INSERT</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA INSERT',2)">AIRBUS HELICOPTERS BORDURAGE ET POSE D'INSERTS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA INSERT (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH BORDURAGE ET POSE D'INSERTS (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA INSERT (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_INSERT.xls');\">";}?>QCM ANNEXE AH Bordurage et pose des inserts sur les panneaux <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">24/05/2022</td>
</tr>




<tr name="AH IFMA TAPPING">
		<td align="center"><b>AH IFMA TAPPING</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA TAPPING',2)">AIRBUS HELICOPTERS TAPPING</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA TAPPING (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH TAPPING (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA TAPPING (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_TAPPING.xls');\">";}?>QCM ANNEXE AH TAPPING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>



<tr name="AH IFMA HARNAIS">
		<td align="center"><b>AH IFMA HARNAIS</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA HARNAIS',2)">AIRBUS HELICOPTERS INSTALLATION ET CHEMINEMENT DES HARNAIS</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA HARNAIS (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH INSTALLATION ET CHEMINEMENT DES HARNAIS (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA HARNAIS (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_HARNAIS.xls');\">";}?>QCM ANNEXE AH INSTALLATION ET CHEMINEMENT DES HARNAIS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>


	<tr name="AH IFMA COMMUN">
		<td align="center"><b>AH IFMA COMMUN</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA COMMUN',2)">AIRBUS HELICOPTERS CABLAGE COMMUN</span></td>
	</tr>
<tr>
		<td align="center"><b>AH IFMA COMMUN (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH CABLAGE COMMUN (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA COMMUN (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_CAB_COM.xls');\">";}?>QCM ANNEXE AH CABLAGE COMMUN<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
	</tr>




<tr name="AH IFMA BAND_IT">
		<td align="center"><b>AH IFMA BAND_IT</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA BAND_IT',2)">AIRBUS HELICOPTERS CABLAGE BAND-IT</span></td>
	</tr>
<tr>
		<td align="center"><b>AH IFMA BAND_IT (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH CABLAGE BAND IT (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA BAND_IT (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_BAND_IT.xls');\">";}?>QCM ANNEXE AH CABLAGE BAND-IT<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
	</tr>



	<tr name="AH IFMA COAX">
		<td align="center"><b>AH IFMA COAX</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA COAX',2)">AIRBUS HELICOPTERS CABLAGE COAX</span></td>
	</tr>
<tr>
		<td align="center"><b>AH IFMA COAX (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH CABLAGE COAX (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA COAX (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_COAX.xls');\">";}?>QCM ANNEXE AH CABLAGE COAX<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
	</tr>





<tr name="AH IFMA TEST_ELEC">
		<td align="center"><b>AH IFMA TEST_ELEC</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA TEST_ELEC',2)">AIRBUS HELICOPTERS TESTS ELECTRIQUES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA TEST_ELEC (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH TESTS ELECTRIQUES (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>

	<tr>
		<td align="center"><b>AH IFMA TEST_ELEC (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_TEST_ELEC.xls');\">";}?>QCM ANNEXE AH TESTS ELECTRIQUES <?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
	</tr>



<tr name="AH IFMA MONT_TUY">
		<td align="center"><b>AH IFMA MONT_TUY</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('AH IFMA MONT_TUY',2)">AIRBUS HELICOPTERS MONTAGE TUYAUTERIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA MONT_TUY (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('');">FORMATION ANNEXE AH MONTAGE TUYAUTERIES (EN DIRECT SUR DOCS CLIENT)</a></td>
		<td align="center">Ed-X</td>
		<td align="center">XX/XX/XXXX</td>
	</tr>
	<tr>
		<td align="center"><b>AH IFMA MONT_TUY (FR)</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_AH_MONT_TUY.xls');\">";}?>QCM ANNEXE AH MONTAGE TUYAUTERIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">19/05/2020</td>
</tr>




	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- LATECOERE IF ------------------->
	<!--###################################################-->
	<!--###################################################-->
	
    <tr name="LATECOERE IF">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>LATECOERE IF</b></td>
	</tr>

<tr name="IF 2.00.14">
		<td align="center"><b>IF 2.00.14</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IF 2.00.14',2)">LATECOERE RETOUCHE PROTECTION</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.00.14 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('2.00.14_Ind_O_FR.pdf');">FORMATION LATECOERE RETOUCHE PROTECTION (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">Ed-O</td>
		<td align="center">04/04/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IF 2.00.14</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IF_2.00.14_Ind_O.xlsx');\">";}?>MCQ/QCM LATECOERE RETOUCHE PROTECTION<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">25/06/2018</td>
	</tr>



<tr name="IF 2.00.16">
		<td align="center"><b>IF 2.00.16</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IF 2.00.16',2)">LATECOERE CONDITIONS APPLICATION PEINTURES SUR P/E, ENSEMBLES ET S/E</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF IF 2.00.16 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('2.00.16_Ind_Q_FR.pdf');">FORMATION LATECOERE CONDITIONS APPLICATION PEINTURES SUR P/E, ENSEMBLES ET S/E (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">Ed-Q</td>
		<td align="center">12/12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IF 2.00.16</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IF_2.00.16_Ind_Q.xlsx');\">";}?>MCQ/QCM LATECOERE CONDITIONS APPLICATION PEINTURES SUR P/E, ENSEMBLES ET S/E<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">05/03/2020</td>
	</tr>



<tr name="IF 2.03.08">
		<td align="center"><b>IF 2.03.08</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IF 2.03.08',2)">LATECOERE UTILISATION MASTICS D ETANCHEITE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.03.08 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IF_2.03.08_Ind_R_FR.pdf');">FORMATION LATECOERE UTILISATION MASTICS D ETANCHEITE (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">Ed-R</td>
		<td align="center">17/03/2017</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IF 2.03.08</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IF_2.03.08_Ind_R.xls');\">";}?>QCM LATECOERE UTILISATION MASTICS D ETANCHEITE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">28/02/2018</td>
	</tr>



<tr name="Annexe 3 IF 2.03.08">
		<td align="center"><b>Annexe 3 IF 2.03.08</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('Annexe 3 IF 2.03.08',2)">LATECOERE MONTAGE HUMIDE DES FIXATIONS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC Annexe 3 IF 2.03.08 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IF_2.03.08_Annexe_3_Rev_A_FR.pdf');">FORMATION LATECOERE MONTAGE HUMIDE DES FIXATIONS (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">Ed-A</td>
		<td align="center">04/11/2015</td>
	</tr>
	<tr>
		<td align="center"><b>QCM Annexe 3 IF 2.03.08</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IF_2.03.08_Annexe_3_Rev_A.xlsx');\">";}?>QCM LATECOERE MONTAGE HUMIDE DES FIXATIONS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">02/08/2018</td>
	</tr>



<tr name="IF 2.03.11">
		<td align="center"><b>IF 2.03.11</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IF 2.03.11',2)">LATECOERE PROCEDES METALLISATION ET DE MISE A LA MASSE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.03.11 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IF_2.03.11_Ind_N_FR.pdf');">FORMATION LATECOERE PROCEDES METALLISATION ET DE MISE A LA MASSE (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">Ed-N</td>
		<td align="center">14/01/2020</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IF 2.03.11</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IF_2.03.11_Indice_N.xls');\">";}?>QCM LATECOERE PROCEDES METALLISATION ET DE MISE A LA MASSE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">09/03/2020</td>
	</tr>



<tr name="IF 2.03.24">
		<td align="center"><b>IF 2.03.24</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IF 2.03.24',3)">LATECOERE REGLES D ASSEMBLAGE / ASSEMBLY RULES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.03.24 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('2.03.24_Ind_D_FR.pdf');">FORMATION LATECOERE REGLES D ASSEMBLAGE (EN DIRECT SUR DOC CLIENT)</a></td>
		<td align="center">Ed-D</td>
		<td align="center">20/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.03.24 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('2.03.24_Ind_D_EN.pdf');">TRAINING LATECOERE ASSEMBLY RULES (DIRECTLY ON CUSTOMER DOC)</a></td>
		<td align="center">Ed-O</td>
		<td align="center">20/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ/QCM IF 2.03.24</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ-QCM_IF_2.03.24_Ind_D.xlsx');\">";}?>MCQ/QCM LATECOERE ASSEMBLY RULES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">06/07/2018</td>
	</tr>



<tr name="IF 2.03.26">
		<td align="center"><b>IF 2.03.26</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('IF 2.03.26',4)">LATECOERE TORQUAGE / TORQUE TIGHTENING</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.03.26 (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('IF_2_03_26 ind E_FR.pdf');">FORMATION LATECOERE TORQUAGE</a></td>
		<td align="center">Ed-E</td>
		<td align="center">29/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>DOC IF 2.03.26 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('IF_2_03_26 ind E_EN.pdf');">TRAINING LATECOERE TORQUE TIGHTENING</a></td>
		<td align="center">Ed-E</td>
		<td align="center">29/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IF 2.03.26 Boeing</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IF_2.03.26_Ind E_Boeing.xlsx');\">";}?>MCQ LATECOERE BOEING TORQUE TIGHTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>QCM IF 2.03.26 Dassault</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM_IF_2.03.26_Ind E_Dassault.xlsx');\">";}?>MCQ LATECOERE DASSAULT TORQUE TIGHTENING<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">14/02/2019</td>
	</tr>






	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- BOMBARDIER BAPS ---------------->
	<!--###################################################-->
	<!--###################################################-->
	
    <tr name="BOMBARDIER BAPS">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>BOMBARDIER BAPS</b></td>
	</tr>

<tr name="BAPS 151-027">
		<td align="center"><b>BAPS 151-027</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('BAPS 151-027',2)">INSTALLATION OF BLIND, SELFLOCKING BIGFOOT FASTENERS FOR ADVANCED COMPOSITE MATERIALS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC BAPS 151-027 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('BAPS 151-027_2002_05_21_EN.pdf');">INSTALLATION OF BLIND, SELFLOCKING BIGFOOT FASTENERS FOR ADVANCED COMPOSITE MATERIALS</a></td>
		<td align="center">Ed-NC</td>
		<td align="center">21/05/2002</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ BAPS 151-027</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_BAPS_151-027_2002-05-21.xlsx');\">";}?>MCQ : INSTALLATION OF BLIND, SELFLOCKING BIGFOOT FASTENERS FOR ADVANCED COMPOSITE MATERIALS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">03/12/2018</td>
	</tr>



<tr name="BAPS 151-028">
		<td align="center"><b>BAPS 151-028</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('BAPS 151-028',2)">INSTALLATION OF BLIND FASTENERS IN COMPOSITE MATERIALS</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC BAPS 151-028 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('BAPS 151-028_2017_04_21_EN.pdf');">INSTALLATION OF BLIND FASTENERS IN COMPOSITE MATERIALS</a></td>
		<td align="center">Ed-A</td>
		<td align="center">21/04/2017</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ BAPS 151-028</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_BAPS_151-028_Rev.A.xlsx');\">";}?>MCQ : INSTALLATION OF BLIND FASTENERS IN COMPOSITE MATERIALS<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">04/12/2018</td>
	</tr>



<tr name="BAPS 188-007">
		<td align="center"><b>BAPS 188-007</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('BAPS 188-007',2)">DRILLING OF COMPOSITES AND COMPOSITE/METALLIC ASSEMBLIES</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC BAPS 188-007 (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('BAPS 188-007_D_EN.pdf');">DRILLING OF COMPOSITES AND COMPOSITE/METALLIC ASSEMBLIES</a></td>
		<td align="center">Ed-D</td>
		<td align="center">20/05/2015</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ BAPS 188-007</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MCQ_BAPS 188-007_ Rev.D.xlsx');\">";}?>MCQ : DRILLING OF COMPOSITES AND COMPOSITE/METALLIC ASSEMBLIES<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">30/11/2018</td>
	</tr>






	<!--###################################################-->
	<!--###################################################-->
	<!---------------------- DASSAULT DGQT   ---------------->
	<!--###################################################-->
	<!--###################################################-->
	
    <tr name="DASSAULT DGQT">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b>DASSAUT DGQT</b></td>
	</tr>


<tr name="DGQT 0.4.2.0310">
		<td align="center"><b>DGQT 0.4.2.0310</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DGQT 0.4.2.0310',2)">Peinture monocouche 4125 de PPG AEROSPACE</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DGQT 0.4.2.0310</b></td>
		<td><a href="javascript:OuvrirFichier('DA_DGQT+0.4.2.0310_C.pdf');">Peinture monocouche 4125 de PPG AEROSPACE</a></td>
		<td align="center">Ed-C</td>
		<td align="center">06/07/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ DGQT 0.4.2.0310</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-DGQT 0.4.2.0310 ind C.xlsx');\">";}?>QCM : Peinture monocouche 4125 de PPG AEROSPACE<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">31/03/2022</td>
	</tr>



<tr name="DGQT 0.4.2.0461">
		<td align="center"><b>DGQT 0.4.2.0461</b></td>
		<td colspan="3"><a href="javascript:onclick=Voir_TR('DGQT 0.4.2.0461',2)">Préparation de surface avant applications et retouches de peinture</span></td>
	</tr>
	<tr>
		<td align="center"><b>DOC DGQT 0.4.2.0461</b></td>
		<td><a href="javascript:OuvrirFichier('DA_DGQT+0.4.2.0461_.pdf');">Préparation de surface avant applications et retouches de peinture</a></td>
		<td align="center"></td>
		<td align="center">03/04/2021</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ DGQT 0.4.2.0461</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('QCM-DGQT 0.4.2.0461 ind 04-2021.xlsx');\">";}?>QCM : Préparation de surface avant applications et retouches de peinture<?php if($QCM){echo "</a>";}?></td>
		<td align="center">&nbsp;</td>
		<td align="center">31/03/2022</td>
	</tr>





<tr name="EPR_TOULOUSE">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>EPROUVETTES AAA TOULOUSE / AAA TOULOUSE SAMPLES</b></td>
</tr>

<tr name="EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015">
	<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015',3)">EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-458-MO_01-02-006&01-02-013&01-02-015.docx');">MO EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-016-MC_Opérateurs_01-02-006&01-02-013&01-02-015_Ind_2.xls');">MC OPERATEUR EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/01/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-019-MC_IQ_01-02-006&01-02-013&01-02-015_Ind_1.xls');">MC IQ EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</a></td>
		<td align="center">Ed-1</td>
		<td align="center">29/03/2018</td>
	</tr>



<tr name="EPROUVETTE AIPI 01-03-004">
	<td align="center"><b>EPROUVETTE AIPI 01-03-004</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 01-03-004',3)">EPROUVETTE AIPI 01-03-004</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-03-004</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-401-MO_AIPI_01-03-004.docx');">MO EPROUVETTE AIPI 01-03-004</a></td>
		<td align="center">Ed-6</td>
		<td align="center">25/01/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-03-004</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-028-MC-Opérateurs_AIPI_01-03-004.xls');">MC EPROUVETTE AIPI 01-03-004</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/01/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-03-004</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-029-MC-IQ_AIPI_01-03-004.xls');">MC IQ EPROUVETTE AIPI 01-03-004</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/01/2019</td>
	</tr>



<tr name="EPROUVETTE ABP MECA-ELEC & IQ">
	<td align="center"><b>EPROUVETTE ABP MECA-ELEC & IQ</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE ABP MECA-ELEC & IQ',3)">EPROUVETTE ABP MECA-ELEC & IQ</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE ABP MECA-ELEC & IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-467-MO_Eprouvette_Méca_Elec_ABP_Ind_3.docx');">MO EPROUVETTE ABP MECA-ELEC & IQ</a></td>
		<td align="center">Ed-3</td>
		<td align="center">15/01/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE ABP MECA-ELEC & IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-028-MC-Opérateurs_AIPI_01-03-004.xls');">MC EPROUVETTE ABP MECA-ELEC & IQ</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/12/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE ABP MECA-ELEC & IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-029-MC-IQ_AIPI_01-03-004.xls');">MC IQ EPROUVETTE ABP MECA-ELEC & IQ</a></td>
		<td align="center">Ed-2</td>
		<td align="center">06/12/2018</td>
	</tr>



<tr name="EPROUVETTE AIPI AJU">
	<td align="center"><b>EPROUVETTE AIPI AJU</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI AJU',4)">EPROUVETTE AIPI AJU</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-205-MO_Eprouvette_Ajusteur_AIPI-AIPS_Ind_4.docx');">MO EPROUVETTE AIPI AJU</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/08/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001-MC_Eprouvette_Ajusteur_AIPI-AIPS_Ind_6.xls');">MC EPROUVETTE AIPI AJU</a></td>
		<td align="center">Ed-6</td>
		<td align="center">05/09/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-017-MC_Test_de_validation_Auto-vérification_Aju_AIPI-AIPS_Ind_2.xls');">MC AUTO VERIFICATION EPROUVETTE AIPI AJU</a></td>
		<td align="center">Ed-2</td>
		<td align="center">05/09/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-023-MC-IQ_Structure_AIPI-AIPS_Ind_1.xls');">MC IQ EPROUVETTE AIPI AJU</a></td>
		<td align="center">Ed-2</td>
		<td align="center">12/09/2018</td>
	</tr>



<tr name="EPROUVETTE 80-T AJU">
	<td align="center"><b>EPROUVETTE 80-T AJU</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE 80-T AJU',3)">EPROUVETTE 80-T AJU</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE 80-T AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-602 MO éprouvette 80-T AJU.DOCX');">MO EPROUVETTE 80-T AJU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">16/12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE 80-T AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-038 MC Eprouvette 80-T Ajusteur Indice 1.xls');">MC EPROUVETTE 80-T AJU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/12/2021</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE 80-T AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-037- MC Eprouvette IQ 80-T Ajusteur Indice 1.xls');">MC IQ EPROUVETTE 80-T AJU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/12/2021</td>
	</tr>




<tr name="EPROUVETTE AIPI MECA-ELEC">
	<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI MECA-ELEC',6)">EPROUVETTE AIPI MECA-ELEC</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-261-MO_Eprouvette_Méca-Elec_AIPI-AIPS_Ind_5.docx');">MO EPROUVETTE AIPI MECA-ELEC</a></td>
		<td align="center">Ed-5</td>
		<td align="center">11/05/2020</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-434-MO_01-02-008&07-01-006&05-05-003_Ind_2.docx');">MO EPROUVETTE 01-02-008 & 07-01-006 & 05-05-003</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-002 MC Eprouvette AIPI Méca_Elec Indice 4.xls');">MC EPROUVETTE AIPI MECA-ELEC</a></td>
		<td align="center">Ed-4</td>
		<td align="center">11/05/2020</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-011-MC-Opérateurs_01-02-008&07-01-006&05-05-003.xls');">MC EPROUVETTE 01-02-008 & 07-01-006 & 05-05-003</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-018-MC-IQ_01-02-008&07-01-006&05-05-003.xls');">MC IQ EPROUVETTE AIPI AJU</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI MECA-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-022-MC_IQ_Eprouvette_AIPI_MECA-ELEC_Ind_1.xls');">MC IQ EPROUVETTE 01-02-008 & 07-01-006 & 05-05-003</a></td>
		<td align="center">Ed-1</td>
		<td align="center">28/08/2018</td>
	</tr>



<tr name="EPROUVETTE AITM">
	<td align="center"><b>EPROUVETTE AITM</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AITM',2)">EPROUVETTE AITM</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AITM</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-519-MO_AITM_3-0007.docx');">MO EPROUVETTE AITM</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/01/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AITM</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-027-MC-IQ_AITM_3.0007.xls');">MC IQ EPROUVETTE AITM</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/01/2019</td>
	</tr>



<tr name="EPROUVETTE COLLAGE NON-STRUCTURAL">
	<td align="center"><b>EPROUVETTE COLLAGE NON-STRUCTURAL</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE COLLAGE NON-STRUCTURAL',2)">EPROUVETTE COLLAGE NON-STRUCTURAL</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COLLAGE NON-STRUCTURAL</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-457-MO_AIPI_06-01-004&06-02-002&05-05-003.docx');">MO EPROUVETTE COLLAGE NON-STRUCTURAL</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COLLAGE NON-STRUCTURAL</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-027-MC-IQ_AITM_3.0007.xls');">MC EPROUVETTE COLLAGE NON-STRUCTURAL</a></td>
		<td align="center">Ed-1</td>
		<td align="center">08/09/2017</td>
	</tr>



<tr name="EPROUVETTE COMPOSITE IQ">
	<td align="center"><b>EPROUVETTE COMPOSITE IQ</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE COMPOSITE IQ',2)">EPROUVETTE COMPOSITE IQ</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COMPOSITE IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-497-MO-IQ_Composite_AIPI-AIPS.docx');">MO IQ EPROUVETTE COMPOSITE IQ</a></td>
		<td align="center">Ed-1</td>
		<td align="center">06/11/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COLLAGE NON-STRUCTURAL</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-024-MC-IQ_Composite_ AIPI-AIPS.xls');">MC IQ EPROUVETTE COLLAGE NON-STRUCTURAL</a></td>
		<td align="center">Ed-1</td>
		<td align="center">05/11/2018</td>
	</tr>



<tr name="EPROUVETTE IPDA IQ">
	<td align="center"><b>EPROUVETTE IPDA IQ</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE IPDA IQ',4)">EPROUVETTE IPDA IQ</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-387-MO_Eprouvette-1-IQ_Annexes_AAA_Issue-4.docx');">MO IQ EPROUVETTE 1 IPDA IQ</a></td>
		<td align="center">Ed-4</td>
		<td align="center">15/01/2021</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-388-MO_Eprouvette-2-IQ_Annexes_AAA.docx');">MO IQ EPROUVETTE 2 IPDA IQ</a></td>
		<td align="center">Ed-3</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-007-MC-IQ_Eprouvette-1_Annexes_AAA_Issue-4.xls');">MC IQ EPROUVETTE 1 IPDA IQ</a></td>
		<td align="center">Ed-4</td>
		<td align="center">15/01/2021</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-008-MC-IQ_Eprouvette-2_Annexes_AAA.xls');">MC IQ EPROUVETTE 2 IPDA IQ</a></td>
		<td align="center">Ed-4</td>
		<td align="center">14/03/2018</td>
	</tr>




<tr name="EPROUVETTE AIPI IQ">
	<td align="center"><b>EPROUVETTE AIPI IQC</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI IQ',4)">EPROUVETTE AIPI IQ</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-270-MO-IQ_Eprouvette-1_AIPS-AIPI_Ind_7.docx');">MO IQ EPROUVETTE 1 AIPI IQ</a></td>
		<td align="center">Ed-7</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-344-MO-IQ_Eprouvette-2_AIPS-AIPI_Ind_2.docx');">MO IQ EPROUVETTE 2 AIPI IQ</a></td>
		<td align="center">Ed-2</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-005-MC-IQ_Eprouvette-1_AIPS-AIPI_Ind_7.xls');">MC IQ EPROUVETTE 1 AIPI IQ</a></td>
		<td align="center">Ed-7</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI IQ</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-006-MC-IQ_Eprouvette-2_AIPS-AIPI_Ind_7.xls');">MC IQ EPROUVETTE 2 AIPI IQ</a></td>
		<td align="center">Ed-7</td>
		<td align="center">14/03/2018</td>
	</tr>



<tr name="EPROUVETTE LEGACY AJU">
	<td align="center"><b>EPROUVETTE LEGACY AJU</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE LEGACY AJU',3)">EPROUVETTE LEGACY AJU</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE LEGACY AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-132-MO_Eprouvette_LEGACY_AJU.docx');">MO EPROUVETTE LEGACY AJU</a></td>
		<td align="center">Ed-3</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE LEGACY AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-003-MC_Eprouvette_Legacy_Ajusteur_Ind_2.xls');">MC EPROUVETTE LEGACY AJU</a></td>
		<td align="center">Ed-2</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE LEGACY AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-025-MC-IQ_Eprouvette_Legacy_Ajusteur_Ind_1.xls');">MC IQ EPROUVETTE LEGACY AJU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">27/09/2018</td>
	</tr>



<tr name="EPROUVETTE LEGACY MECE-ELEC">
	<td align="center"><b>EPROUVETTE LEGACY MECE-ELEC</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE LEGACY MECE-ELEC',3)">EPROUVETTE LEGACY MECE-ELEC</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE LEGACY MECE-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-133-MO_Eprouvette_LEGACY_MECA-ELEC.docx');">MO EPROUVETTE LEGACY MECE-ELEC</a></td>
		<td align="center">Ed-2</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE LEGACY MECE-ELEC</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-004-MC_Eprouvette_LEGACY_Méca_Elec_Ind_2.xls');">MC EPROUVETTE LEGACY MECE-ELEC</a></td>
		<td align="center">Ed-2</td>
		<td align="center">14/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE LEGACY AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-026-MC-IQ_Eprouvette_LEGACY_Méca-Elec_Ind_1.xls');">MC IQ EPROUVETTE LEGACY MECE-ELEC</a></td>
		<td align="center">Ed-1</td>
		<td align="center">27/09/2018</td>
	</tr>



<tr name="EPROUVETTE RESINE - CALAGE LIQUIDE">
	<td align="center"><b>EPROUVETTE RESINE - CALAGE LIQUIDE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE RESINE - CALAGE LIQUIDE',2)">EPROUVETTE RESINE - CALAGE LIQUIDE</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RESINE - CALAGE LIQUIDE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-420-MO_Filler_Resin.docx');">MO EPROUVETTE RESINE - CALAGE LIQUIDE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/12/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RESINE - CALAGE LIQUIDE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-420-MC_Filler_Resin.xls');">MC EPROUVETTE RESINE - CALAGE LIQUIDE</a></td>
		<td align="center">Ed-1</td>
		<td align="center">03/12/2019</td>
	</tr>



<tr name="EPROUVETTE RETOUCHE COMPOSITE">
	<td align="center"><b>EPROUVETTE RETOUCHE COMPOSITE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE RETOUCHE COMPOSITE',3)">EPROUVETTE RETOUCHE COMPOSITE</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RETOUCHE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-454-MO_Eprouvette_Composite_03-08-003&05-05-009&05-02-009&05-02-011_Ind_3.docx');">MO EPROUVETTE RETOUCHE COMPOSITE</a></td>
		<td align="center">Ed-3</td>
		<td align="center">09/04/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RETOUCHE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-012-MC-Opérateurs-Eprouvette_Composite_03-08-003&05-05-009&05-02-009&05-02-011_Ind_3.xls');">MC EPROUVETTE RETOUCHE COMPOSITE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">09/04/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RETOUCHE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-013-MC-IQ-Eprouvette_Composite_03-08-003&05-05-009&05-02-009&05-02-011_Ind_3.xls');">MC IQ EPROUVETTE RETOUCHE COMPOSITE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">19/03/2018</td>
	</tr>



<tr name="EPROUVETTE RETOUCHE RAYURES">
	<td align="center"><b>EPROUVETTE RETOUCHE RAYURES</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE RETOUCHE RAYURES',3)">EPROUVETTE RETOUCHE RAYURES</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RETOUCHE RAYURES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-433-MO_02-05-001&03-03-001&05-02-011&05-02-009.docx');">MO EPROUVETTE RETOUCHE RAYURES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RETOUCHE RAYURES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-009-MC_Opérateurs_02-05-001&03-03-001&05-02-011&05-02-009.xls');">MC EPROUVETTE RETOUCHE RAYURES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/09/2017</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE RETOUCHE RAYURES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-010-MC-IQ_02-05-001&03-03-001&05-02-011&05-02-009.xls');">MC IQ EPROUVETTE RETOUCHE RAYURES</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/09/2017</td>
	</tr>



<tr name="EPR_SAINT_NAZAIRE">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>EPROUVETTES AAA OUEST / AAA WEST SAMPLES</b></td>
</tr>

<tr name="EPROUVETTE AJU">
	<td align="center"><b>EPROUVETTE AJU</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AJU',2)">EPROUVETTE AJU</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-014-MO_Eprouvette_AJU.docx');">MO EPROUVETTE AJU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">11/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001-MC-EPROUVETTE_AJU.xls');">MC EPROUVETTE AJU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">10/11/2017</td>
	</tr>



<tr name="EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011">
	<td align="center"><b>EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011',2)">EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-433-MO_AIPI_02-05-001_03-03-001_05-02-011_05-02-009.docx');">MO EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011</a></td>
		<td align="center">Ed-4</td>
		<td align="center">08/09/2017</td>
	</tr>
		<tr>
		<td align="center"><b>EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011</b></td>
		<td><a href="javascript:OuvrirFichier('MC 19-005 Retouche rayures 02-05-001 03-03-001 05-02-011 05-02-009.xls');">MC EPROUVETTE AIPI 02-05-001 & 03-03-001 & 05-02-009 & 05-02-011</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/09/2019</td>
	</tr>



<tr name="EPR_HAMBOURG">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>EPROUVETTES AAA GmbH / AAA GmbH SAMPLES</b></td>
</tr>

<tr name="EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015">
	<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015',6)">EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-458-MO-1_AIPI_01-02-006&01-02-013&01-02-015.docx');">MO EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</a></td>
		<td align="center">Ed-3</td>
		<td align="center">20/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-013 & 05-05-008 & 05-05-001 & 05-05-004</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-458-MO-2_AIPI_01-02-013&05-05-008&05-05-001&05-05-004.docx');">MO EPROUVETTE AIPI 01-02-013 & 05-05-008 & 05-05-001 & 05-05-004</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-013 & 05-05-008 & 06-02-009</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-458-MO-2_AIPI_01-02-013&05-05-008&06-02-009.docx');">MO EPROUVETTE AIPI 01-02-013 & 05-05-008 & 06-02-009</a></td>
		<td align="center">Ed-TBD</td>
		<td align="center">26/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-013 & 05-05-008</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-458-MO-2_AIPI_01-02-013&05-05-008.docx');">MO EPROUVETTE AIPI 01-02-013 & 05-05-008</a></td>
		<td align="center">Ed-2</td>
		<td align="center">26/06/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-019-MC-1-IQ_AIPI_01-02-006_01-02-013_01-02-015.xls');">MC EPROUVETTE AIPI 01-02-006 & 01-02-013 & 01-02-015</a></td>
		<td align="center">Ed-1</td>
		<td align="center">07/12/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 01-02-013 & 05-05-008</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-415-MC-2-QI_01-02-013_05-05-008.xls');">MC EPROUVETTE AIPI 01-02-013 & 05-05-008</a></td>
		<td align="center">Ed-1</td>
		<td align="center">27/06/2018</td>
	</tr>



<tr name="EPROUVETTE AIPI 03-01-010">
	<td align="center"><b>EPROUVETTE AIPI 03-01-010</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 03-01-010',2)">EPROUVETTE AIPI 03-01-010</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 03-01-010</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-014-MO_Eprouvette_AJU.docx');">MO EPROUVETTE AIPI 03-01-010</a></td>
		<td align="center">Ed-1</td>
		<td align="center">11/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 03-01-010</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001-MC-EPROUVETTE_AJU.xls');">MC EPROUVETTE AIPI 03-01-010</a></td>
		<td align="center">Ed-2</td>
		<td align="center">29/11/2018</td>
	</tr>



<tr name="EPROUVETTE AIPI 06-01-004 & 06-02-002">
	<td align="center"><b>EPROUVETTE AIPI 06-01-004 & 06-02-002</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 06-01-004 & 06-02-002',2)">EPROUVETTE AIPI 06-01-004 & 06-02-002</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 06-01-004 & 06-02-002</b></td>
		<td><a href="javascript:OuvrirFichier('D0827-457-OM_AIPI_06-01-004&06-02-002.docx');">MO EPROUVETTE AIPI 06-01-004 & 06-02-002</a></td>
		<td align="center">Ed-3</td>
		<td align="center">04/07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 06-01-004 & 06-02-002</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-015-MC-IQ_AIPI_06-02-002&06-01-004.xls');">MC IQ EPROUVETTE AIPI 06-01-004 & 06-02-002</a></td>
		<td align="center">Ed-2</td>
		<td align="center">26/06/2018</td>
	</tr>



<tr name="EPROUVETTE AIPI 06-02-009">
	<td align="center"><b>EPROUVETTE AIPI 06-02-009</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 06-02-009',1)">EPROUVETTE AIPI 06-02-009</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 06-02-009</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-DE-414-MO-Airbus_Helico_AIPI06-02-009-Adhesive-tape-bonding_HBG.docx');">MO EPROUVETTE AIPI 06-02-009</a></td>
		<td align="center">Ed-4</td>
		<td align="center">25/06/2018</td>
	</tr>




<tr name="EPROUVETTE AIPI 08-02-004">
	<td align="center"><b>EPROUVETTE AIPI 08-02-004</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AIPI 08-02-004',2)">EPROUVETTE AIPI 08-02-004</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 08-02-004</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-417-MO_AIPI_08-02-004.docx');">MO EPROUVETTE AIPI 08-02-004</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AIPI 08-02-004</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-417-IN_AIPI_ 08-02-004.xls');">MC EPROUVETTE AIPI 08-02-004</a></td>
		<td align="center">Ed-2</td>
		<td align="center">18/02/2019</td>
	</tr>



<tr name="EPROUVETTE COMPOSITE">
	<td align="center"><b>EPROUVETTE COMPOSITE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE COMPOSITE',2)">EPROUVETTE COMPOSITE</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-454-OM_Composite_Sample_03-08-003&05-05-009&05-02-009&05-02-011.docx');">MO EPROUVETTE COMPOSITE</a></td>
		<td align="center">Ed-4</td>
		<td align="center">28/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-013-IN-QI_Composite_Sample_03-08-003&05-05-009&05-02-009&05-02-011.xls');">MC EPROUVETTE COMPOSITE</a></td>
		<td align="center">Ed-2</td>
		<td align="center">30/04/2018</td>
	</tr>



<tr name="EPROUVETTE FITTER">
	<td align="center"><b>EPROUVETTE FITTER</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE FITTER',3)">EPROUVETTE FITTER</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE FITTER</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-132-1-OM_Fitter_Sample.docx');">MO EPROUVETTE FITTER</a></td>
		<td align="center">Ed-8</td>
		<td align="center">19/03/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE FITTER</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-132-2-OM_Sample.docx');">MO EPROUVETTE FITTER</a></td>
		<td align="center">Ed-1</td>
		<td align="center">28/03/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE FITTER</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-003-1-IN_Fitter_Sample.xls');">MC EPROUVETTE FITTER</a></td>
		<td align="center">Ed-6</td>
		<td align="center">25/01/2019</td>
	</tr>



<tr name="EPROUVETTE INSTALLATION OF BUSHES">
	<td align="center"><b>EPROUVETTE INSTALLATION OF BUSHES</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE INSTALLATION OF BUSHES',2)">EPROUVETTE INSTALLATION OF BUSHES</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE INSTALLATION OF BUSHES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-419-OM_Installation_of_bushes.docx');">MO EPROUVETTE INSTALLATION OF BUSHES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/05/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE INSTALLATION OF BUSHES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-419-IN_Installation_of_bushes.xls');">MC EPROUVETTE INSTALLATION OF BUSHES</a></td>
		<td align="center">Ed-1</td>
		<td align="center">21/05/2019</td>
	</tr>



<tr name="EPROUVETTE IPDA 66-02">
	<td align="center"><b>EPROUVETTE IPDA 66-02</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE IPDA 66-02',2)">EPROUVETTE IPDA 66-02</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 66-02</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-001-OM-ABZGM02_Outer_skin_Rework.docx');">MO EPROUVETTE IPDA 66-02</a></td>
		<td align="center">Ed-1</td>
		<td align="center">18/03/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 66-02</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001-IN-ABZGM02_Outer_skin_rework.xls');">MC EPROUVETTE IPDA 66-02</a></td>
		<td align="center">Ed-1</td>
		<td align="center">19/03/2019</td>
	</tr>



<tr name="EPROUVETTE IPDA 69-02">
	<td align="center"><b>EPROUVETTE IPDA 69-02</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE IPDA 69-02',2)">EPROUVETTE IPDA 69-02</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 69-02</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-002-OM-ABZGM02_Int._Anti_corrosion_touch-up.docx');">MO EPROUVETTE IPDA 69-02</a></td>
		<td align="center">Ed-2</td>
		<td align="center">03/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 69-02</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-002-IN-ABZGM02-_Int._Anti_corrosion_touch-up.xls');">MC EPROUVETTE IPDA 69-02</a></td>
		<td align="center">Ed-1</td>
		<td align="center">19/03/2019</td>
	</tr>




<tr name="EPROUVETTE IPDA 71-23 & 64-15">
	<td align="center"><b>EPROUVETTE IPDA 71-23 & 64-15</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE IPDA 71-23 & 64-15',2)">EPROUVETTE IPDA 71-23 & 64-15</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 71-23 & 64-15</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-003-ABZGM02-OM_Screw_Installation.docx');">MO EPROUVETTE IPDA 71-23 & 64-15</a></td>
		<td align="center">Ed-4</td>
		<td align="center">02/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 71-23 & 64-15</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-003-IN_Screw_Installation.xls');">MC EPROUVETTE IPDA 71-23 & 64-15</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/04/2019</td>
	</tr>



<tr name="EPROUVETTE IPDA 72-08">
	<td align="center"><b>EPROUVETTE IPDA 72-08</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE IPDA 72-08',2)">EPROUVETTE IPDA 72-08</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 72-08</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-004-ABZGM02-OM_Composite_Screw_Installation.docx');">MO EPROUVETTE IPDA 72-08</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE IPDA 72-08</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-004-ABZGM02-IN_Composite_Screw_Installation.xls');">MC EPROUVETTE IPDA 72-08</a></td>
		<td align="center">Ed-2</td>
		<td align="center">02/04/2019</td>
	</tr>



<tr name="EPROUVETTE REWORK SCRATCHES">
	<td align="center"><b>EPROUVETTE REWORK SCRATCHES</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE REWORK SCRATCHES',2)">EPROUVETTE REWORK SCRATCHES</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE REWORK SCRATCHES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-433-OM_Aluminium_Rework.docx');">MO EPROUVETTE REWORK SCRATCHES</a></td>
		<td align="center">Ed-10</td>
		<td align="center">02/04/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE REWORK SCRATCHES</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-010-IN_Rework_of_scratches.xls');">MC EPROUVETTE REWORK SCRATCHES</a></td>
		<td align="center">Ed-6</td>
		<td align="center">02/04/2019</td>
	</tr>



<tr name="EPR_MARIGNANE">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>EPROUVETTES AAA SUD EST / AAA SOUTH EAST SAMPLES</b></td>
</tr>

<tr name="EPROUVETTE AJU">
	<td align="center"><b>EPROUVETTE AJU</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AJU',2)">EPROUVETTE AJU</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-003-MO_Eprouvette_AJU_Ind_B.pdf');">MO EPROUVETTE AJU</a></td>
		<td align="center">Ed-B</td>
		<td align="center">19/02/2019</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AJU</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-001-MC_Eprouvette_IFMA_ASN_Ajusteur_final_Ind_B.pdf');">MC EPROUVETTE AJU</a></td>
		<td align="center">Ed-B</td>
		<td align="center">20/02/2019</td>
	</tr>

<tr name="EPROUVETTE AJU COMPOSITE">
	<td align="center"><b>EPROUVETTE AJU COMPOSITE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE AJU COMPOSITE',2)">EPROUVETTE AJU COMPOSITE</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AJU COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-007-MO_Eprouvette_AJU_COMPOSITE_Ind_A.pdf');">MO EPROUVETTE AJU COMPOSITE</a></td>
		<td align="center">Ed-A</td>
		<td align="center">24/01/2020</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE AJU COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-004-MC_Eprouvette_AJU_COMPOSITE_Ind_B.pdf');">MC EPROUVETTE AJU COMPOSITE</a></td>
		<td align="center">Ed-B</td>
		<td align="center">24/01/2020</td>
	</tr>

<tr name="EPROUVETTE MECA">
	<td align="center"><b>EPROUVETTE MECA</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE MECA',2)">EPROUVETTE MECA</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE MECA</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-006_MO_MECA_Ind_A.pdf');">MO EPROUVETTE MECA</a></td>
		<td align="center">Ed-A</td>
		<td align="center">20/07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE MECA</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-003_MC_MECA_Ind_A.pdf');">MC EPROUVETTE MECA</a></td>
		<td align="center">Ed-A</td>
		<td align="center">20/07/2018</td>
	</tr>

<tr name="EPROUVETTE COMPOSITE">
	<td align="center"><b>EPROUVETTE COMPOSITE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('EPROUVETTE COMPOSITE',2)">EPROUVETTE COMPOSITE</span></td>
</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0827-005_MO_COMPOSITE CARBONE_Ind_A.pdf');">MO EPROUVETTE COMPOSITE</a></td>
		<td align="center">Ed-A</td>
		<td align="center">20/07/2018</td>
	</tr>
	<tr>
		<td align="center"><b>EPROUVETTE COMPOSITE</b></td>
		<td><a href="javascript:OuvrirFichier('D-0832-002_MC_COMPOSITE CARBONE_Ind_A.pdf');">MC EPROUVETTE COMPOSITE</a></td>
		<td align="center">Ed-A</td>
		<td align="center">20/07/2018</td>
	</tr>

<tr name="CHINE">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>EPROUVETTES AAA TIANJIN / AAA TIANJIN SAMPLES</b></td>
</tr>

<tr name="STRIPPING - HEAT-SHRINKING">
	<td align="center"><b>STRIPPING - HEAT-SHRINKING</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('STRIPPING - HEAT-SHRINKING',2)">STRIPPING - HEAT-SHRINKING</span></td>
</tr>
	<tr>
		<td align="center"><b>STRIPPING - HEAT-SHRINKING</b></td>
		<td><a href="javascript:OuvrirFichier('DQ715-GRP-001-MO_Stripping_Heat-shrinking.pdf');">MO STRIPPING - HEAT-SHRINKING</a></td>
		<td align="center">Ed-1</td>
		<td align="center">31/05/2016</td>
	</tr>
	<tr>
		<td align="center"><b>STRIPPING - HEAT-SHRINKING</b></td>
		<td><a href="javascript:OuvrirFichier('DQ715-GRP-002-MC_Stripping_Heat-shrinking.pdf');">MC STRIPPING - HEAT-SHRINKINGU</a></td>
		<td align="center">Ed-1</td>
		<td align="center">31/05/2016</td>
	</tr>


<tr name="CANADA">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>FORMATION SPECIFIQUE AAA CANADA / AAA CANADA SPECIFIC TRAINING</b></td>
</tr>

<tr name="CABIN">
	<td align="center"><b>CABIN</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('CABIN',4)">CABIN INSTALLATION / INSTALLATION D INTERIEUR</span></td>
</tr>
	<tr>
		<td align="center"><b>CABIN (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_CA_TR_CABIN_EN.pptx');">TRAINING CABIN INSTALLATION</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>CABIN (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_CA_TR_CABIN_FR.pptx');">FORMATION INSTALLATION D INTERIEUR</a></td>
		<td align="center">Ed-1</td>
		<td align="center">20/11/2019</td>
	</tr>
	<tr>
		<td align="center"><b>MCQ CABIN (EN)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_CA_TR_CABIN_QCM_EN.xlsx');">MCQ CABIN INSTALLATION</a></td>
		<td align="center">-</td>
		<td align="center">10/12/2019</td>

	</tr>
	<tr>
		<td align="center"><b>QCM CABIN (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_CA_TR_CABIN_QCM_FR.xlsx');">QCM INSTALLATION D INTERIEUR</a></td>
		<td align="center">-</td>
		<td align="center">10/12/2019</td>
	</tr>


<tr name="SUD EST">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>FORMATION SPECIFIQUE AAA SUD EST / AAA SOUTH EAST SPECIFIC TRAINING</b></td>
</tr>

<tr name="SE_PLAN">
	<td align="center"><b>SE_PLAN</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('SE_PLAN',1)">LECTURE DE PLAN / DRAWING READING</span></td>
</tr>
	<tr>
		<td align="center"><b>SE_PLAN (FR)</b></td>
		<td><a href="javascript:OuvrirFichier('AAA_SE_PLAN.ppt');">FORMATION LECTURE DE PLAN</a></td>
		<td align="center">Ed-1</td>
		<td align="center">22/01/2020</td>
	</tr>
	

	</tr>
<tr name="MOD_TOULOUSE">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>FORMATION SPECIFIQUE AAA TOULOUSE / AAA TOULOUSE SPECIFIC TRAINING</b></td>
</tr>


<tr name="AERO TLS_01">
	<td align="center"><b>AERO TLS_01</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('AERO TLS_01',1)">STRUCTURE D UN AVION</span></td>
</tr>
	<tr>
		<td align="center"><b>AERO TLS_01</b></td>
		<td><a href="javascript:OuvrirFichier('AERO TLS_01.pdf');">STRUCTURE D UN AVION</a></td>
		<td align="center">01</td>
		<td align="center">06/05/2019</td>
	</tr>

<tr name="AERO TLS_02">
	<td align="center"><b>AERO TLS_02</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('AERO TLS_02',1)">LECTURE DE PLAN et NOMENCLATURE</span></td>
</tr>
	<tr>
		<td align="center"><b>AERO TLS_02</b></td>
		<td><a href="javascript:OuvrirFichier('AERO TLS_02.pdf');">LECTURE DE PLAN et NOMENCLATURE</a></td>
		<td align="center">01</td>
		<td align="center">06/05/2019</td>
	</tr>

<tr name="AERO TLS_03">
	<td align="center"><b>AERO TLS_03</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('AERO TLS_03',1)">COMPREHENSION GAMMES DE TRAVAIL / FI-SOI et REMPLISSAGE FICHES SUIVEUSES</span></td>
</tr>
	<tr>
		<td align="center"><b>AERO TLS_03</b></td>
		<td><a href="javascript:OuvrirFichier('AERO TLS_03.pdf');">COMPREHENSION GAMMES DE TRAVAIL / FI-SOI et REMPLISSAGE FICHES SUIVEUSES</a></td>
		<td align="center">01</td>
		<td align="center">06/05/2019</td>
	</tr>


<tr name="ATEX-EC">
	<td align="center"><b>ATEX_EC</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('ATEX-EC',2)">Atmospheres Explosives (ATEX) et Espaces Confines</span></td>
</tr>

	<tr>
		<td align="center"><b>ATEX_EC</b></td>
		<td><a href="javascript:OuvrirFichier('ATEX-EC-Formation.pptx');">Formation</a></td>
		<td align="center">Ed-4</td>
		<td align="center">14/02/2019</td>
	</tr>

	<tr>
		<td align="center"><b>ATEX_EC</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('ATEX-EC-QCM.xlsx');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-1</td>
		<td align="center">26/09/2018</td>
	</tr>


<tr name="CONT_PISTE">
	<td align="center"><b>CONT_PISTE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('CONT_PISTE',2)">Controleur Piste</span></td>
</tr>

<tr>
		<td align="center"><b>CONT_PISTE</b></td>
		<td><a href="javascript:OuvrirFichier('CONT-PISTE-Formation(v4).pptx');">Formation</a></td>
		<td align="center">Ed-4</td>
		<td align="center">12/07/2021</td>
	</tr>

	<tr>
		<td align="center"><b>CONT_PISTE</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('CONT-PISTE-QCM(V02).xls');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-2</td>
		<td align="center">12/07/2021</td>
	</tr>


<tr name="DAM_01">
	<td align="center"><b>DAM_01</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('DAM_01',2)">FOD - Damage Prevention Awareness </span></td>
</tr>

<tr>
		<td align="center"><b>DAM_01</b></td>
		<td><a href="javascript:OuvrirFichier('DAM_01-Formation-FOD(V03).pptx');">Formation</a></td>
		<td align="center">Ed-3</td>
		<td align="center">03/08/2018</td>
	</tr>

	<tr>
		<td align="center"><b>DAM_01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('DAM_01-QCM.xlsx');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-1</td>
		<td align="center">03/08/2018</td>
	</tr>


<tr name="ACCUEIL BEHAVIOR A350">
	<td align="center"><b>FAL_A350</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('ACCUEIL BEHAVIOR A350',4)">Accueil Behavior A350</span></td>
</tr>

	<tr>
		<td align="center"><b>FAL_A350</b></td>
		<td><a href="javascript:OuvrirFichier('FAL_A350-FILM-23092011.wmv');"> Film presentation FAL A350</a></td>
		<td align="center">-</td>
		<td align="center">23/09/2011</td>
	</tr>
	<tr>
		<td align="center"><b>FAL_A350</b></td>
		<td><a href="javascript:OuvrirFichier('FAL_A350-Accueil.ppt');"> Accueil A350</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>FAL_A350</b></td>
		<td><a href="javascript:OuvrirFichier('FAL_A350-Behavior-to-adopt.ppt');"> behavior to adapt in FAL A350</a></td>
		<td align="center">Ed-1</td>
		<td align="center">25/10/2017</td>
	</tr>
	<tr>
		<td align="center"><b>FAL_A350</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('FAL_A350-QCM.xls');\">";}?>QCM acceuil et behavior en FAL A350<?php if($QCM){echo "</a>";}?></td>
		<td align="center">-</td>
		<td align="center">08/11/2017</td>
	</tr>


<tr name="MATELAS">
	<td align="center"><b>MATELAS</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('MATELAS',2)">Ouverture/Fermeture des matelas d'isolation</span></td>
</tr>

	<tr>
		<td align="center"><b>MATELAS</b></td>
		<td><a href="javascript:OuvrirFichier('MATELAS-Formation(V1).ppt');">Formation</a></td>
		<td align="center">Ed-1</td>
		<td align="center">30/06/2017</td>
	</tr>

	<tr>
		<td align="center"><b>DAM_01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MATELAS-QCM.xlsx');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">-</td>
		<td align="center">30/06/2017</td>
	</tr>


<tr name="MECA_PISTE">
	<td align="center"><b>MECA_PISTE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('MECA_PISTE',2)">Mecanicien Piste</span></td>
</tr>

<tr>
		<td align="center"><b>MECA_PISTE</b></td>
		<td><a href="javascript:OuvrirFichier('MECA_PISTE-Formation(V02).pptx');">Formation</a></td>
		<td align="center">Ed-2</td>
		<td align="center">01/10/2018</td>
	</tr>

	<tr>
		<td align="center"><b>DAM_01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('MECA_PISTE-QCM(V01).xls');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-1</td>
		<td align="center">24/05/2018</td>
	</tr>


<tr name="PREPA_01">
	<td align="center"><b>PREPA_01</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('PREPA_01',2)">Serrage au Couple</span></td>
</tr>

<tr>
		<td align="center"><b>PREPA_01</b></td>
		<td><a href="javascript:OuvrirFichier('PREPA_01-Formation(V03).pptx');">Formation</a></td>
		<td align="center">Ed-3</td>
		<td align="center">19/01/2022</td>
	</tr>

	<tr>
		<td align="center"><b>PREPA_01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('PREPA_01-QCM.xls');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">-</td>
		<td align="center">19/01/2022</td>
	</tr>

<tr name="SMQ_AAA">
	<td align="center"><b>SMQ_AAA</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('SMQ_AAA',2)">SMQ AAA</span></td>
</tr>

<tr>
		<td align="center"><b>SMQ_AAA</b></td>
		<td><a href="javascript:OuvrirFichier('SMQ_AAA-Formation(V11).pptx');">Formation</a></td>
		<td align="center">Ed-11</td>
		<td align="center">19/11/2018</td>
	</tr>

	<tr>
		<td align="center"><b>SMQ_AAA</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('SMQ_AAA-QCM(V9).xls');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-9</td>
		<td align="center">15/02/2018</td>
	</tr>


<tr name="TRACT_01">
	<td align="center"><b>TRACT_01</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('TRACT_01',2)">Tractage Avion</span></td>
</tr>

<tr>
		<td align="center"><b>TRACT_01</b></td>
		<td><a href="javascript:OuvrirFichier('TRACT_01-Formation(V07).ppt');">Formation</a></td>
		<td align="center">Ed-7</td>
		<td align="center">13/07/2021</td>
	</tr>

	<tr>
		<td align="center"><b>TRACT_01</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('TRACT_01-QCM(V07).xls');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">Ed-7</td>
		<td align="center">13/07/2021</td>
	</tr>

<tr name="MOD_MAINTENANCE">
		<td colspan="4" align="center" bgcolor="#DEDEDE"><b>FORMATION SPECIFIQUE AAA MAINTENANCE / AAA MAINTENANCE SPECIFIC TRAINING</b></td>
</tr>


<tr name="PART145">
	<td align="center"><b>AAAMS_PART145</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('PART145',2)">PART145</span></td>
</tr>

	<tr>
		<td align="center"><b>AAAMS_PART145</b></td>
		<td><a href="javascript:OuvrirFichier('AAAMS_PART145_FR ed4.ppt');">Formation</a></td>
		<td align="center">Ed-4</td>
		<td align="center">22/03/2021</td>
	</tr>

	<tr>
		<td align="center"><b>AAAMS_PART145</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AAAMS_PART145_QCM.xlsx');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">-</td>
		<td align="center">22/03/2021</td>
	</tr>

<tr name="AAAMS_FH">
	<td align="center"><b>AAAMS_FH</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('AAAMS_FH',2)">Facteurs Humains</span></td>
</tr>

	<tr>
		<td align="center"><b>AAAMS_FH</b></td>
		<td><a href="javascript:OuvrirFichier('AAAMS_Facteurs Humains_FR ed2.ppt');">Formation</a></td>
		<td align="center">Ed-2</td>
		<td align="center">22/03/2021</td>
	</tr>

	<tr>
		<td align="center"><b>AAAMS_FH</b></td>
		<td><?php if($QCM){echo "<a href=\"javascript:OuvrirFichier('AAAMS_Facteurs Humains_QCM.xlsx');\">";}?>QCM<?php if($QCM){echo "</a>";}?></td>
		<td align="center">-</td>
		<td align="center">22/03/2021</td>
	</tr>

<tr name="AAAMS_EWIS">
	<td align="center"><b>AAAMS_EWIS</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('AAAMS_EWIS',2)">EWIS Groupe 4 (TBD)</span></td>
</tr>

	<tr>
		<td align="center"><b>AAAMS_EWIS</b></td>
		<td><a>Formation (TBD)</a></td>
		<td align="center">-</td>
		<td align="center">-</td>
	</tr>

	<tr>
		<td align="center"><b>AAAMS_EWIS</b></td>
		<td><a>Formation (TBD)</a></td>
		<td align="center">-</td>
		<td align="center">_</td>
	</tr>

<tr name="AAAMS_CDCCL">
	<td align="center"><b>AAAMS_CDCCL</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('AAAMS_CDCCL',2)">CDCCL Niveau 2 (TBD)</span></td>
</tr>

	<tr>
		<td align="center"><b>AAAMS_CDCCL</b></td>
		<td><a>Formation (TBD)</a></td>
		<td align="center">-</td>
		<td align="center">-</td>
	</tr>

	<tr>
		<td align="center"><b>AAAMS_CDCCL</b></td>
		<td><a>QCM (TBD)</a></td>
		<td align="center">-</td>
		<td align="center">_</td>
	</tr>

<tr name="PART_DR145">
	<td align="center"><b>PART_DR145</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('PART_DR145',1)">EASA Vs ISO – Exigences PART145</span></td>
</tr>

	<tr>
		<td align="center"><b>PART_DR145</b></td>
		<td><a href="javascript:OuvrirFichier('PART-DR145-FR Présentation EASA Vs ISO Exigences PART145 Ed2.ppt');">Formation</a></td>
		<td align="center">Ed-2</td>
		<td align="center">13/04/2021</td>
	</tr>


<tr name="PART_MOE">
	<td align="center"><b>PART_MOE</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('PART_MOE',1)">Formation MOE AAA</span></td>
</tr>

	<tr>
		<td align="center"><b>PART_MOE</b></td>
		<td><a href="javascript:OuvrirFichier('PART_MOE_FR ed8.ppt');">Formation au MOE AAA Ed13</a></td>
		<td align="center">Ed-8</td>
		<td align="center">28/11/2021</td>
	</tr>

<tr name="PART_DR21G">
	<td align="center"><b>PART_DR21G</b></td>
	<td colspan="3"><a href="javascript:onclick=Voir_TR('PART_DR21G',1)">EASA Vs ISO – Exigences PART21G</span></td>
</tr>

	<tr>
		<td align="center"><b>PART_DR21G</b></td>
		<td><a href="javascript:OuvrirFichier('PART-DR21G-FR Présentation EASA Vs ISO Exigences PART21G Ed2.ppt');">Formation</a></td>
		<td align="center">2</td>
		<td align="center">11/02/2021</td>
	</tr>

</tr></tr>






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