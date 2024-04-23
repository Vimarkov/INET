<script language="javascript">
	function OuvreFenetre(Lien){
		if(Lien=='OpenIdleTime'){
			var w=window.open("OpenIdleTime.php","PageOpenIdleTime","status=no,menubar=no,scrollbars=yes,width=1100,height=400");
			w.focus();
		}
		else if(Lien=='Extract_Invoice'){
			if(document.getElementById('customer').value!='0' && document.getElementById('enddate').value!=''){
				var w=window.open(Lien+".php?Customer="+document.getElementById('customer').value+"&EndDate="+document.getElementById('enddate').value,"PageLien","status=no,menubar=no,scrollbars=yes,width=50,height=50");
				w.focus();
			}
		}
	}
</script>

<?php 

?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td valign="top" width="50%">
			<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Customer";} ?> : </td>
					<td width='60%'>
						<select class="customer" id="customer" name="customer" style="width:130px;">
							<option value='0'></option>
						<?php
							$req="SELECT Id,Libelle
							FROM gpao_customer
							WHERE Suppr=0 
							AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
							ORDER BY Libelle";
							$resultList=mysqli_query($bdd,$req);
							$nbList=mysqli_num_rows($resultList);
							
							if ($nbList > 0)
							{
								while($rowList=mysqli_fetch_array($resultList))
								{
									$selected="";
									echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
								}
							 }
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "End date";} ?> :</td>
					<td width='60%'>
						<input type="date" name="enddate" id="enddate" size="20" value="">
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td colspan="2" align="left">
						<a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_Invoice')"><?php if($_SESSION['Langue']=="EN"){echo "INVOICE";}else{echo "INVOICE";}?></a>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" width="50%">
			<table class="TableCompetences"  align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" align="left"><a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('OpenIdleTime')"><?php if($_SESSION['Langue']=="EN"){echo "Open Idle Time";}else{echo "Open Idle Time";}?></a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
</table>