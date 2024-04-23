<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link rel="stylesheet" href="../../CSS/Action.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<?php
	require("../Connexioni.php");
?>
<form class="test" method="POST" action="AidePERFOS.php">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">CRITERES LETTRES SQCDPF</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td height="4"></td>
				</tr>
				<?php
				if ($_GET){
					$reqFrequence = "SELECT CritereSRouge,CritereSRouge,CritereQRouge,CritereQRouge,CritereCRouge,CritereCRouge,CritereDVert,CritereDVert,CriterePVert, ";
					$reqFrequence .= "CriterePVert,CritereFVert,CritereFVert, ";
					$reqFrequence .= "(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=new_frequencesqcdpf.Id_Prestation) AS Prestation, ";
					$reqFrequence .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=new_frequenceperfos.Id_Pole) AS Pole ";
					$reqFrequence .= "FROM new_frequencesqcdpf ";
					$reqFrequence .= "WHERE Id_Prestation=".$_GET['Id_Prestation']." AND Id_Pole=".$_GET['Id_Pole'];
					$result=mysqli_query($bdd,$reqFrequence);
					$nb=mysqli_num_rows($result);
					if($nb>0){
						$row=mysqli_fetch_array($result);
				?>
				<tr>
					<td colspan="5" style="font-weight:bold;" align="center">&nbsp; <?php echo $row['Prestation']." ".$row['Pole'];?></td>
				</tr>
				<tr>
					<td colspan="5">
						<table align="center" width="90%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="font-weight:bold;" align="center">S</td>
								<td style="font-weight:bold;" align="center">Q</td>
								<td style="font-weight:bold;" align="center">C</td>
								<td style="font-weight:bold;" align="center">D</td>
								<td style="font-weight:bold;" align="center">P</td>
								<td style="font-weight:bold;" align="center">F</td>
							</tr>
							
									<tr>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertS" name="vertS" rows="7" cols="0"><?php echo $row['CritereSVert'] ;?></textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertQ" name="vertQ" rows="7" cols="0"><?php echo $row['CritereQVert'] ;?></textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertC" name="vertC" rows="7" cols="0"><?php echo $row['CritereCVert'] ;?></textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertD" name="vertD" rows="7" cols="0"><?php echo $row['CritereDVert'] ;?></textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertP" name="vertP" rows="7" cols="0"><?php echo $row['CriterePVert'] ;?></textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertF" name="vertF" rows="7" cols="0"><?php echo $row['CritereFVert'] ;?></textarea></td>
									</tr>
									<tr>
										<td align="left"><textarea style="background:#ffafae;" id="rougeS" name="rougeS" rows="7" cols="0"><?php echo $row['CritereSRouge'] ;?></textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeQ" name="rougeQ" rows="7" cols="0"><?php echo $row['CritereQRouge'] ;?></textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeC" name="rougeC" rows="7" cols="0"><?php echo $row['CritereCRouge'] ;?></textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeD" name="rougeD" rows="7" cols="0"><?php echo $row['CritereDRouge'] ;?></textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeP" name="rougeP" rows="7" cols="0"><?php echo $row['CriterePRouge'] ;?></textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeF" name="rougeF" rows="7" cols="0"><?php echo $row['CritereFRouge'] ;?></textarea></td>
									</tr>
						</table>
					</td>
				</tr>
				<?php
					}
					else{
					?>
					
				<tr>
					<td colspan="5">
						<table align="center" width="90%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="font-weight:bold;" align="center">S</td>
								<td style="font-weight:bold;" align="center">Q</td>
								<td style="font-weight:bold;" align="center">C</td>
								<td style="font-weight:bold;" align="center">D</td>
								<td style="font-weight:bold;" align="center">P</td>
								<td style="font-weight:bold;" align="center">F</td>
							</tr>
							
									<tr>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertS" name="vertS" rows="7" cols="0">Pas de risques potentiel ou d’accident de travail</textarea></td>																				
										<td align="left"><textarea style="background:#e2fdbd;" id="vertQ" name="vertQ" rows="7" cols="0">Critère validation de l’OQD de la prestation respecté</textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertC" name="vertC" rows="7" cols="0">Pas de difficultés de réalisation imputables AAA, ou difficultés de réalisation non imputables AAA mais sans impact critique sur l’atteinte des objectifs</textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertD" name="vertD" rows="7" cols="0">Critère de validation de l’OTD de la prestation respectés</textarea></td>
										<td align="left"><textarea style="background:#e2fdbd;" id="vertP" name="vertP" rows="7" cols="0">Pas d’absence imprévue</textarea></td>										
										<td align="left"><textarea style="background:#e2fdbd;" id="vertF" name="vertF" rows="7" cols="0">Planning formation respecté, pas d’absence en formation</textarea></td>
									</tr>
									<tr>
										<td align="left"><textarea style="background:#ffafae;" id="rougeS" name="rougeS" rows="7" cols="0">Au moins 1 risque potentiel ou accident de travail</textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeQ" name="rougeQ" rows="7" cols="0">Au moins 1 critère de validation de l’OQD non respecté</textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeC" name="rougeC" rows="7" cols="0">Au moins 1 difficulté de réalisation imputable AAA et/ou au moins 1 difficulté non imputable mais avec impact critique sur l’atteinte des objectifs</textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeD" name="rougeD" rows="7" cols="0">Au moins un critère de validation de l’OTD non respecté</textarea></td>
										<td align="left"><textarea style="background:#ffafae;" id="rougeP" name="rougeP" rows="7" cols="0">Au moins 1 absence imprévue</textarea></td>										
										<td align="left"><textarea style="background:#ffafae;" id="rougeF" name="rougeF" rows="7" cols="0">Au moins 1 absence en formation</textarea></td>
									</tr>
						</table>
					</td>
				</tr>
					<?php
					}
				}
				?>
				<tr>
					<td height="4"></td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>