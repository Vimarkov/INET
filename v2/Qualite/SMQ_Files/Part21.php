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
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Part21/MOP-FR.pdf' target='_blank'>Manuel d\'Organisme de Production (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Part21/MOP-EN.pdf' target='_blank'>Production Organization Manual (EN)</a></li>";
					break;
				case 4:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/4/D-0402-GRP.xls' target='_blank'>Liste des processus en vigueur - D-0402 (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/D/4/D-0402-GRP_EN.xls' target='_blank'>Applicable Processes List - D-0402 (EN)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Processus/PR/TRAME_PR03_SPECIFIQUE_AAA_GROUP.xlsx' target='_blank'>Trame PR03 spécifique AAA Group (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Processus/PR/AAA_GROUP_SPECIFIC_PR03_TEMPLATE.xlsx' target='_blank'>AAA Group Specific PR03 Template (EN)</a></li>";
					break;
				case 5:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Part21/P21_DQ413-FR.xlsx' target='_blank'>Liste des procédures spécifiques PART 21 – D-0738 (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Part21/P21_DQ413-EN.xlsx' target='_blank'>PART 21 specific procedures list – D-0738 (EN)</a></li>";
					break;
				case 6:
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Part21/DQ21_DQ413-FR.xlsx' target='_blank'>Liste des documents spécifiques PART 21 – D-0738 (FR)</a></li>";
					DetailSousMenu=DetailSousMenu + "<li><a href='../../../Qualite/Part21/DQ21_DQ413-EN.xlsx' target='_blank'>PART 21 specific documents list – D-0738 (EN)</a></li>";
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
					<td class="PagePyramide" style="font-size:50px;color:#00325F;">PART 21</td>
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
												<input type="submit" class="EtagePyramide" value="MOP / POM" onclick="AfficherSousMenu(1);">
											</td>
										</tr>
										<tr height="50"><td></td></tr>
										<tr height="70"><td></td></tr>
										<tr height="70">
											<td>
											</td>
										</tr>
										<tr height="70">
											<td>
											</td>
										</tr>
										<tr height="70">
											<td>
												<input type="submit" class="EtagePyramide" value="Documents spécifiques / Specific documents" onclick="AfficherSousMenu(6);">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td style="width:50%;">
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