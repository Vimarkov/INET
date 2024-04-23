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
if($_POST){
	$startdate=$_POST['startdate'];
	$enddate=$_POST['enddate'];
	
	
}
else{
	$startdate="";
	$enddate="";
}
?>
<br>
<input type="hidden" name="Menu" id="Menu" value="<?php echo $_SESSION['Menu']; ?>" />
<table class="TableCompetences" align="center" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Start date";} ?> :</td>
		<td width='35%'>
			<input type="date" name="startdate" id="startdate" size="20" value="<?php echo $startdate;?>">
		</td>
		<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "End date";} ?> :</td>
		<td width='35%'>
			<input type="date" name="enddate" id="enddate" size="20" value="<?php echo $enddate;?>">
		</td>
		<td align="left" width='5%'>
			<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Generate";}else{echo "Generate";}?>">
		</td>
		<td align="left" width='5%'>
			<a class="Bouton" style="text-decoration:none;" href="javascript:OuvreFenetre('Extract_SQCDP')"><?php if($_SESSION['Langue']=="EN"){echo "List of";}else{echo "List of";}?></a>
		</td>
	</tr>
	<tr>
		<td height="5" colspan="5">
		<?php 
			if($startdate<>"" && $enddate<>""){
				$req="SELECT gpao_statutquality.Id
					FROM gpao_statutquality 
					LEFT JOIN gpao_wo
					ON gpao_statutquality.Id_WO=gpao_wo.Id
					WHERE gpao_statutquality.Suppr=0 
					AND gpao_wo.Suppr=0 
					AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'REWORK TC'  
					AND DateStatut>='".$startdate."' 
					AND DateStatut<='".$enddate."' ";

				$resultRapport=mysqli_query($bdd,$req);
				$nbRapport=mysqli_num_rows($resultRapport);


				$coordRework=$nbRapport;
				
				$req="SELECT gpao_statutquality.Id
					FROM gpao_statutquality 
					LEFT JOIN gpao_wo
					ON gpao_statutquality.Id_WO=gpao_wo.Id
					WHERE gpao_statutquality.Suppr=0 
					AND gpao_wo.Suppr=0 
					AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'REWORK PRODUCTION'  
					AND DateStatut>='".$startdate."' 
					AND DateStatut<='".$enddate."' ";

				$resultRapport=mysqli_query($bdd,$req);
				$nbRapport=mysqli_num_rows($resultRapport);
				
				$prodRework=$nbRapport;
				
				$req="SELECT gpao_statutquality.Id
					FROM gpao_statutquality 
					LEFT JOIN gpao_wo
					ON gpao_statutquality.Id_WO=gpao_wo.Id
					WHERE gpao_statutquality.Suppr=0 
					AND gpao_wo.Suppr=0 
					AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'REWORK QUALITY'  
					AND DateStatut>='".$startdate."' 
					AND DateStatut<='".$enddate."' ";

				$resultRapport=mysqli_query($bdd,$req);
				$nbRapport=mysqli_num_rows($resultRapport);
				
				$qualityRework=$nbRapport;

				$req="SELECT gpao_wo.Id
					FROM gpao_wo
					WHERE gpao_wo.Suppr=0 
					AND Invoiced=0
					AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND CreationDate>='".$startdate."' 
					AND CreationDate<='".$enddate."' ";

				$resultRapport=mysqli_query($bdd,$req);
				$nbRapport=mysqli_num_rows($resultRapport);

				$totalCreated=$nbRapport;
				
				$req="SELECT gpao_statutquality.Id
					FROM gpao_statutquality 
					LEFT JOIN gpao_wo
					ON gpao_statutquality.Id_WO=gpao_wo.Id
					WHERE gpao_statutquality.Suppr=0 
					AND gpao_wo.Suppr=0 
					AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'QUALITY TERA'  
					AND DateStatut>='".$startdate."' 
					AND DateStatut<='".$enddate."' ";

				$resultRapport=mysqli_query($bdd,$req);
				$nbRapport=mysqli_num_rows($resultRapport);


				$totalTera=$nbRapport;

				$req="SELECT gpao_statutquality.Id
					FROM gpao_statutquality 
					LEFT JOIN gpao_wo
					ON gpao_statutquality.Id_WO=gpao_wo.Id
					WHERE gpao_statutquality.Suppr=0 
					AND gpao_wo.Suppr=0 
					AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND ((SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'TERC CLOSED'  
						OR (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'TERC CUSTOMER'  
						OR (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'PARA STAMPED SENT'  
						OR (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) LIKE 'TERC AIRBUS'  
					)
					AND ClosureDate>='".$startdate."' 
					AND ClosureDate<='".$enddate."' ";

				$resultRapport=mysqli_query($bdd,$req);
				$nbRapport=mysqli_num_rows($resultRapport);

				$totalTerc=$nbRapport;
				
				
				$delivery="";
				

				$coordReworkPourcentage="";
				if($totalCreated>0){
					$coordReworkPourcentage=round(($coordRework/$totalCreated)*100,1);
				}
				$prodReworkPourcentage="";
				if($totalTera>0){
					$prodReworkPourcentage=round(($prodRework/$totalTera)*100,1);
				}
				$qualityReworkPourcentage="";
				if($totalTerc>0){
					$qualityReworkPourcentage=round(($qualityRework/$totalTerc)*100,1);
				}
				
				
		?>
			<table align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="Libelle" width='10%'></td>
					<td class="Libelle" colspan="4"><?php if($_SESSION['Langue']=="EN"){echo "Q";}else{echo "Q";} ?></td>
					<td class="Libelle" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "C";}else{echo "C";} ?></td>
					<td class="Libelle" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "D";}else{echo "D";} ?></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Coord";}else{echo "Coord";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $coordRework; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Coordination Rework %";}else{echo "Coordination Rework %";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $coordReworkPourcentage; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Total Created";}else{echo "Total Created";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $totalCreated; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Delivery";}else{echo "Delivery";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $delivery; ?></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Prod";}else{echo "Prod";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $prodRework; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Production Rework %";}else{echo "Production Rework %";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $prodReworkPourcentage; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Total TERA";}else{echo "Total TERA";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $totalTera; ?></td>
					<td class="Libelle" width='10%'></td>
					<td class="Libelle" width='10%'></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Quality";}else{echo "Quality";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $qualityRework; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Quality Rework %";}else{echo "Quality Rework %";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $qualityReworkPourcentage; ?></td>
					<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Total TERC";}else{echo "Total TERC";} ?> :</td>
					<td class="Libelle" width='10%'><?php echo $totalTerc; ?></td>
					<td class="Libelle" width='10%'></td>
					<td class="Libelle" width='10%'></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
			</table>
		<?php
			}
		?>
		</td>
	</tr>
</table>