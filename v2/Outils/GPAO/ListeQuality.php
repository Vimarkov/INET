<script language="javascript">
	function OuvreFenetre(Lien){
		if(Lien=='CMTESearch'){
			var w=window.open("CMTESearch.php","PageCMTESearch","status=no,menubar=no,scrollbars=yes,width=1300,height=500");
			w.focus();
			
			var w=window.open("CMTEWarning.php","PageCMTEWarning","status=no,menubar=no,scrollbars=yes,width=800,height=400");
			w.focus();
		}
		else if(Lien=='ChemicalProductSearch'){
			var w=window.open("ChemicalProductSearch.php","PageChemicalProductSearch","status=no,menubar=no,scrollbars=yes,width=1300,height=500");
			w.focus();
			
			var w=window.open("ChemicalProductWarning.php","PageChemicalProductWarning","status=no,menubar=no,scrollbars=yes,width=800,height=400");
			w.focus();
		}
		else if(Lien=='InterventionCardSearch'){
			var w=window.open("InterventionCardSearch.php","PageInterventionCardSearch","status=no,menubar=no,scrollbars=yes,width=1300,height=500");
			w.focus();
		}
		else{
			var w=window.open(Lien+".php","PageLien","status=no,menubar=no,scrollbars=yes,width=50,height=50");
			w.focus();
		}
	}
</script>

<?php 
if($_POST){
	$annee=$_POST['annee'];
	$mois=$_POST['mois'];
}
else{
	$annee="";
	$mois="";
}
?>
<input type="hidden" name="Menu" id="Menu" value="<?php echo $_SESSION['Menu']; ?>" />
<table align="center" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td valign="top">
			<table class="TableCompetences"  align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="center" class="Libelle" style="font-size:16px;"><?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Search";}?></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('CMTESearch')"><?php if($_SESSION['Langue']=="EN"){echo "CMTE";}else{echo "CMTE";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('ChemicalProductSearch')"><?php if($_SESSION['Langue']=="EN"){echo "Chemical Product";}else{echo "Chemical Product";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('InterventionCardSearch')"><?php if($_SESSION['Langue']=="EN"){echo "Intervention Card";}else{echo "Intervention Card";}?></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td valign="top">
			<table class="TableCompetences"  align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="center" class="Libelle" style="font-size:16px;"><?php if($_SESSION['Langue']=="EN"){echo "List";}else{echo "List";}?></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_Reworks')"><?php if($_SESSION['Langue']=="EN"){echo "Reworks";}else{echo "Reworks";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_TERCList')"><?php if($_SESSION['Langue']=="EN"){echo "TERC List";}else{echo "TERC List";}?></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td valign="top">
			<table class="TableCompetences"  align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="center" class="Libelle" style="font-size:16px;"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Process";}else{echo "Manufacturing Process";}?></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('ListManufacturingOpen')"><?php if($_SESSION['Langue']=="EN"){echo "List of Manufacturing processes linked to OPEN";}else{echo "List of Manufacturing processes linked to OPEN";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('ListManufacturingClosed')"><?php if($_SESSION['Langue']=="EN"){echo "List of Manufacturing processes linked to CLOSED";}else{echo "List of Manufacturing processes linked to CLOSED";}?></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
</table>