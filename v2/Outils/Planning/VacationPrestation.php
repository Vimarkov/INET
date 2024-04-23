<?php
require("../../Menu.php");
?>

<script language="javascript">
	function OuvreFenetreModif(Mode,Id_Prestation)
		{window.open("Modifier_VacationPrestation.php?Mode="+Mode+"&Id_Prestation="+Id_Prestation,"PageFichier","status=no,menubar=no,scrollbars=1,width=500,height=400");}
	function OuvreFenetreExcelVacationPrestation(Id_Plateformes)
		{window.open("VacationPrestation_Excel.php?Id_Plateformes="+Id_Plateformes,"PageExcel","status=no,menubar=no,width=300,height=300");}
</script>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="TitrePage">Gestion du planning # Vacations par prestation</td>
				</tr>
			</table>
		</td>
		<td>
			<?php
				$Plateformes = "";
				foreach ($_SESSION['Id_Plateformes'] as $value) {
					$Plateformes .= $value.";";
				}
			?>
			<a href="javascript:OuvreFenetreExcelVacationPrestation('<?php echo $Plateformes; ?>');">
			<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
			</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" width="1100">
						<?php
							$req = "SELECT new_competences_prestation.Id, new_competences_prestation.Libelle, new_competences_plateforme.Libelle FROM new_competences_prestation INNER JOIN new_competences_plateforme ON new_competences_prestation.Id_Plateforme = new_competences_plateforme.Id WHERE ";
							foreach ($_SESSION['Id_Plateformes'] as &$value) {
								$req .= " new_competences_prestation.Id_Plateforme=".$value." OR";
							}
							$req =  substr($req, 0, -2)."" ;
							$req .= " ORDER BY new_competences_plateforme.Libelle ASC, new_competences_prestation.libelle ASC;";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td colspan="3" class="EnTeteTableauCompetences">Prestation</td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							$Plateforme=0;
							$idPrestation=0;
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								if($Plateforme!=$row['2']){echo "<tr height='1' bgcolor='#66AACC'><td colspan='30'></td></tr>";}
								$Plateforme=$row['2'];
								$idPrestation=$row['0'];
						?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td width=100><?php echo $Plateforme;?></td>
									<td width=500><?php echo $row['1'];?></td>
									<td width=10>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['0']; ?>');">
											<img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier">
										</a>
									</td>
								</tr>
						<?php
								} //fin boucle for
							}		//Fin boucle
								mysqli_free_result($result);	// Libération des résultats
						?>
						</table>
					</td>
					
				</tr>
			</table>
		</tr>
	</td>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>