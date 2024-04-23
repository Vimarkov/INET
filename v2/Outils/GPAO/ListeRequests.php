<script language="javascript">
	function OuvreFenetre(Lien){
		if(Lien=='ProductionHours'){
			var w=window.open("ProductionHours.php","PageProduction","status=no,menubar=no,scrollbars=yes,width=1100,height=400");
			w.focus();
		}
		else if(Lien=='WeeklyWorkerHour'){
			var w=window.open("WeeklyWorkerHours.php","PageWorker","status=no,menubar=no,scrollbars=yes,width=1100,height=400");
			w.focus();
		}
		else if(Lien=="UpdateClosureDate"){
			var w=window.open("Action.php?Act="+Lien,"PageAction","status=no,menubar=no,scrollbars=yes,width=1100,height=600");
			w.focus();
			if(Lien=="UpdateClosureDate"){
				alert("Launch and Closure Date are updated");
			}
		}
		else if(Lien=='WorkTimeWeek'){
			var w=window.open("WorkTimeWeek.php","PageWorkTimeWeek","status=no,menubar=no,scrollbars=yes,width=700,height=400");
			w.focus();
		}
		else if(Lien=='WorkTimeMonth'){
			var w=window.open("WorkTimeMonth.php","PageWorkTimeMonth","status=no,menubar=no,scrollbars=yes,width=700,height=400");
			w.focus();
		}
		else if(Lien=='ControllingTime'){
			var w=window.open("ControllingTime.php","ControllingTime","status=no,menubar=no,scrollbars=yes,width=1100,height=400");
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
					<td width="33%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('UpdateClosureDate')"><?php if($_SESSION['Langue']=="EN"){echo "Update closure date";}else{echo "Update closure date";}?></a></td>
					<td width="33%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('ProductionHours')"><?php if($_SESSION['Langue']=="EN"){echo "Production hours";}else{echo "Production hours";}?></a></td>
					<td width="33%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('WeeklyWorkerHour')"><?php if($_SESSION['Langue']=="EN"){echo "Weekly Worker hours";}else{echo "Weekly Worker hours";}?></a></td>
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
					<td colspan="4" align="center" class="Libelle" style="font-size:16px;"><?php if($_SESSION['Langue']=="EN"){echo "Extracts";}else{echo "Extracts";}?></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_KPI')"><?php if($_SESSION['Langue']=="EN"){echo "Extract workflow KPI";}else{echo "Extract workflow KPI";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_KPI_REWORK')"><?php if($_SESSION['Langue']=="EN"){echo "Extract rework KPI";}else{echo "Extract rework KPI";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_PROD')"><?php if($_SESSION['Langue']=="EN"){echo "Extract prod hours";}else{echo "Extract prod hours";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_InJigDate')"><?php if($_SESSION['Langue']=="EN"){echo "Extract In Jig Date";}else{echo "Extract In Jig Date";}?></a></td>
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
					<td colspan="4" align="center" class="Libelle" style="font-size:16px;"><?php if($_SESSION['Langue']=="EN"){echo "Production Leaders";}else{echo "Production Leaders";}?></td>
				</tr>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('WorkTimeWeek')"><?php if($_SESSION['Langue']=="EN"){echo "Work Time Week";}else{echo "Work Time Week";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('WorkTimeMonth')"><?php if($_SESSION['Langue']=="EN"){echo "Work Time Month";}else{echo "Work Time Month";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('ControllingTime')"><?php if($_SESSION['Langue']=="EN"){echo "Controlling of Time per Worker per Week per Customer";}else{echo "Controlling of Time per Worker per Week per Customer";}?></a></td>
					<td width="23%" align="center"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_ProductionPriosOverview')"><?php if($_SESSION['Langue']=="EN"){echo "Production Prios Overview";}else{echo "Production Prios Overview";}?></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
</table>