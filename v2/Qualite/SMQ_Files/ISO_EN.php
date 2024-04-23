<html>
<head>
	<title>SMQ AAA</title>
	<link href="../../CSS/SMQ.css" rel="stylesheet" type="text/css">
	<script>
		function AfficherSousMenu(NumEtage)
		{
			DetailSousMenu="<ul>";
			switch(NumEtage)
			{
				case 1:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Manuel_Qualite/Manuel.pdf' target='_blank'>Manuel Qualité AAA Group (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Manuel_Qualite/Manual.pdf' target='_blank'>AAA Group Quality Manual (EN)</a></li>";
					break;
				case 2:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/8/D-0843_D-0738-GRP.xls' target='_blank'>Trames PQ AAA Group (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/8/D-0843_D-0738-GRP.xls' target='_blank'>AAA Group QP Templates (EN)</a></li>";
					break;
				case 3:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Manuel_Qualite/Engagement%20de%20la%20direction.pdf' target='_blank'>Engagement de la Direction (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Manuel_Qualite/Politique%20qualite.pdf' target='_blank'>Politique Qualité (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Manuel_Qualite/Management_Commitment.pdf' target='_blank'>Management Commitment (EN)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Manuel_Qualite/Qualite_Policy.pdf' target='_blank'>Quality Policy (EN)</a></li>";
					break;
				case 4:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/4/D-0402-GRP.xls' target='_blank'>Liste des processus en vigueur - D-0402 (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/4/D-0402-GRP_EN.xls' target='_blank'>Applicable Processes List - D-0402 (EN)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Processus/PR/TRAME_PR03_SPECIFIQUE_AAA_GROUP.xlsx' target='_blank'>Trame PR03 spécifique AAA Group (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Processus/PR/AAA_GROUP_SPECIFIC_PR03_TEMPLATE.xlsx' target='_blank'>AAA Group Specific PR03 Template (EN)</a></li>";
					break;
				//case 5:
				//	DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/DQ/4/DQ402-GRP.xls' target='_blank'>Liste des procédures en vigueur – DQ 402 (FR)</a></li>";
				//	DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/DQ/4/DQ402-GRP_EN.xls' target='_blank'>Applicable Procedures List - DQ 402 (EN)</a></li>";
				//	break;
				case 6:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/7/D-0736-GRP.php' target='_blank'>Liste des documents qualité / List of Applicable Quality Documents - D-0736 (FR/EN)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='Autres_Documents.php'>Autres documents (modèles) / Others documents (templates) (FR/EN)</a></li>";
					//DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/7/D-0738_Modules_de_Formation.php' style='color:red;'>Modules de formation (D-0738) / Training modules (D-0738)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../v2/Outils/Competences/D-0738_Modules_de_Formation.php' style='color:red;'>Modules de formation (D-0738) / Training modules (D-0738)</a></li>";
					break;
			}
			DetailSousMenu=DetailSousMenu + "</ul>";
			document.getElementById('SousMenu').innerHTML=DetailSousMenu;
			document.getElementById('SousMenu').style.visibility="visible";
		}
	</script>
</head>
<?php
	require("../../Outils/VerifPage.php");
?>
<body>
<table style="width:100%; height:100%;">
	<tr>
		<td>
			<table style="width:100%; height:100%;">
				<tr>
					<td style="width:15%;font-size:20px;color:#00325F;" valign="top">
						<img width="250px" src="../../Images/Logos/Logo Daher_posi.png" onclick="location.href='Accueil.php'"><br>
						Daher Industrial Services
					</td>
					<td class="PagePyramide" style="font-size:50px;color:#00325F;">SYSTEME MANAGEMENT QUALITE<br>QUALITY MANAGEMENT SYSTEM</td>
					<td width="15%"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%; height:100%;">
				<tr>
					<td width="15%"></td>
					<td>
						<table class="Pyramide" style="width:712; height:500;">
							<tr>
								<td>
									<table style="width:100%; height:95%;">
										<tr height="80"><td></td></tr>
										<tr height="70">
											<td>
												<input type="submit" class="EtagePyramide" value="Quality Manual" onclick="AfficherSousMenu(1);">
											</td>
										</tr>
										<tr height="50">
											<td>
												<input type="submit" class="EtagePyramide" value="Quality Plan" onclick="AfficherSousMenu(2);">
											</td>
										</tr>
										<tr height="70">
											<td>
												<input type="submit" class="EtagePyramide" value="Directives / Directives" onclick="AfficherSousMenu(3);">
											</td>
										</tr>
										<tr height="70">
											<td>
												<input type="submit" class="EtagePyramide" value="Processus / Processes" onclick="AfficherSousMenu(4);">
											</td>
										</tr>
										<tr height="70">
											<td>
												<input type="submit" class="EtagePyramide" value="Procédures / Procedures" onclick="AfficherSousMenu(5);">
											</td>
										</tr>
										<tr height="70">
											<td>
												<input type="submit" class="EtagePyramide" value="Documents / Documents" onclick="AfficherSousMenu(6);">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td style="width:40%;">
						<table>
							<tr valign="middle">
								<td>
									<div id="SousMenu" class="SousMenu"></div>
								</td>
							</tr>
							<tr height="50%"></tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td></td></tr>
</table>
</body>

</html>