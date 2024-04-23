<script language="javascript">
	function OuvreFenetreFiltre(display,valeur){
		var w=window.open("Ajout_Filtre.php?Display="+display+"&Valeur="+valeur,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=400");
		w.focus();
		}
	function reset(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnReset2' name='btnReset2' value='Reset'>";
		document.getElementById('reset').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnReset2").dispatchEvent(evt);
		document.getElementById('reset').innerHTML="";
	}
	function OuvreFenetre(Id,Lien,Id_Aircraft){
		if(Lien=="WO"){
			window.location="TableauDeBord.php?Id_WO="+Id+"&Id_Aircraft="+Id_Aircraft+"&Menu=2";
		}
		else if(Lien=="Position"){
			var w=window.open("Ajout_Position.php?Id="+Id_Aircraft,"PagePosition","status=no,menubar=no,scrollbars=yes,width=300,height=300");
			w.focus();
		}
		else if(Lien=="Plannification"){
			var w=window.open("Ajout_Plannification.php?Id="+Id,"PagePlannification","status=no,menubar=no,scrollbars=yes,width=900,height=400");
			w.focus();
		}
		else if(Lien=="Logistic"){
			var w=window.open("Ajout_Logistic.php?Id="+Id,"PageLogistic","status=no,menubar=no,scrollbars=yes,width=1100,height=600");
			w.focus();
		}
	}
</script>

<?php
	if($_GET){
		$display="";
		$req="SELECT Valeur 
			FROM gpao_parametrage 
			WHERE Suppr=0
			AND Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Type='Display' ";
		$resultDisplay=mysqli_query($bdd,$req);
		$nbDisplay=mysqli_num_rows($resultDisplay);
		
		if($nbDisplay>0){
			$rowDisplay=mysqli_fetch_array($resultDisplay);
			$display=$rowDisplay['Valeur'];
		}
		
		if(isset($_GET['Tri'])){
			$tab = array('Customer','Imputation','AM','OF','NC','QLB','TLB','Concession','Para','PlanDate','LimitDateFOT','Designation','TargetTime','WorkingProgress','FI','MSN','Position','Type','CreationDate','Priority','Comments','ClosureDate','WorkingShift','NewEoW','OTDEoW','EscalationPoint','LastEoW','OTDComment','UpdateDateTandem','FOTDate','EoWDQ1','NewEoWDQ1','CreationDateDQ1','CommentsDQ1','FollowUpConcession','CommentsA_CMS2','NewEoWAvailable','PriorityReason','Skills','LastStatus','LastStatusDate','StatusComment','PartNumber','Quantity','CMS','RefDIV','PartsDeliveryDate','PartsReceivedOn');
			foreach($tab as $tri){
				if($_GET['Tri']==$tri){
					$_SESSION['TriGPAO_'.$display.'_General']= str_replace($tri." ASC,","",$_SESSION['TriGPAO_'.$display.'_General']);
					$_SESSION['TriGPAO_'.$display.'_General']= str_replace($tri." DESC,","",$_SESSION['TriGPAO_'.$display.'_General']);
					$_SESSION['TriGPAO_'.$display.'_General']= str_replace($tri." ASC","",$_SESSION['TriGPAO_'.$display.'_General']);
					$_SESSION['TriGPAO_'.$display.'_General']= str_replace($tri." DESC","",$_SESSION['TriGPAO_'.$display.'_General']);
					if($_SESSION['TriGPAO_'.$display.'_'.$tri]==""){$_SESSION['TriGPAO_'.$display.'_'.$tri]="ASC";$_SESSION['TriGPAO_'.$display.'_General'].= $tri." ".$_SESSION['TriGPAO_'.$display.'_'.$tri].",";}
					elseif($_SESSION['TriGPAO_'.$display.'_'.$tri]=="ASC"){$_SESSION['TriGPAO_'.$display.'_'.$tri]="DESC";$_SESSION['TriGPAO_'.$display.'_General'].= $tri." ".$_SESSION['TriGPAO_'.$display.'_'.$tri].",";}
					else{$_SESSION['TriGPAO_'.$display.'_'.$tri]="";}
				}
			}
		}
	}
	else{
		$display=$_POST['display'];
		
		if(isset($_POST['btnReset2'])){
			$req="SELECT Id_PrestationGPAO,Display,Valeur, Type
				FROM gpao_tableau 
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
				
			$resultTitreDisplay=mysqli_query($bdd,$req);
			$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
			
			$tabDisplay = array();
			if($nbTitreDisplay>0){
				while($rowDisplay=mysqli_fetch_array($resultTitreDisplay))
				{
					$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']]="";
					$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_2"]="";
					$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_Du"]="";
					$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_Au"]="";
					$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_Type"]="";
				}
			}
		}
		elseif(isset($_POST['Btn_Tri'])){
			if($display=="General" 
			|| $display=="Coordination" 
			|| $display=="Production" 
			|| $display=="Quality"
			|| $display=="Concession"
			|| $display=="Archives"){
				$tabChamps=array('Customer','Imputation','AM','OF','NC','QLB','TLB','Concession','Para','PlanDate','LimitDateFOT','Designation','TargetTime','WorkingProgress','FI','MSN','Position','Type','CreationDate','Priority','Comments','ClosureDate','WorkingShift','NewEoW','OTDEoW','EscalationPoint','LastEoW','OTDComment','UpdateDateTandem','FOTDate','EoWDQ1','NewEoWDQ1','CreationDateDQ1','CommentsDQ1','FollowUpConcession','CommentsA_CMS2','NewEoWAvailable','PriorityReason','Skills','LastStatus','LastStatusDate','StatusComment');

				foreach($tabChamps as $champs)
				{
					$_SESSION['TriGPAO_'.$display.'_'.$champs]="";
				}
				$_SESSION['TriGPAO_'.$display.'_General']="";
			}
			elseif($display=="Logistic"){
				$tabChamps=array('Customer','Type','MSN','AM','OF','NC','QLB','Para','TLB','LastStatus','PartNumber','Quantity','CMS','RefDIV','PartsDeliveryDate','PartsReceivedOn','CreationDate');
				foreach($tabChamps as $champs)
				{
					$_SESSION['TriGPAO_'.$display.'_'.$champs]="";
				}
				$_SESSION['TriGPAO_'.$display.'_General']="";
			}
		} 
		
		$req="SELECT Valeur 
			FROM gpao_parametrage 
			WHERE Suppr=0
			AND Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Type='Display' ";
		$resultDisplay=mysqli_query($bdd,$req);
		$nbDisplay=mysqli_num_rows($resultDisplay);
		
		if($nbDisplay>0){
			$req="UPDATE gpao_parametrage 
			SET Valeur='".$_POST['display']."'
			WHERE Suppr=0
			AND Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Type='Display' ";
			$resultDisplay=mysqli_query($bdd,$req);
		}
		else{
			$req="INSERT INTO gpao_parametrage (Id_PrestationGPAO,Id_Personne,Type,Valeur)
			VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['Id_Personne'].",'Display','".$_POST['display']."') ";
			$resultDisplay=mysqli_query($bdd,$req);
		}
	}
	
?>
<br>
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<input type="hidden" name="Menu" id="Menu" value="<?php echo $_SESSION['Menu']; ?>" />

<table class="TableCompetences" align="center" width="100%">
	<tr>
		<td class="Libelle" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Display";}else{echo "Display";} ?></td>
		<td width="95%">
			<select class="display" name="display" onchange="submit()" style="width:150px;">
			<?php
				$tab=array("","General","Archives","Coordination","Production","Quality","Concession","Logistic");

				foreach($tab as $valeur)
				{
					$selected="";
					if($display==$valeur){$selected="selected";}
					echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
				}
			?>
			</select>
		</td>
	</tr>
<?php 
	if($display<>""){
?>
	<tr>
		<td colspan="2">
			<table width="100%">
				<tr>
					<td align="right">
						<a href="javascript:reset()">
							<img id="btnReset" name="btnReset" width="20px" src="../../Images/Gomme.png" alt="submit" style="cursor:pointer;"/> 
						</a><br>
						<div id="reset"></div>
					</td>
				</tr>
				<?php 
				//Liste des filtres 
				$req="SELECT Display,Titre,Valeur, Type
					FROM gpao_tableau 
					WHERE Suppr=0 
					AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
					AND Display='".$display."' 
					ORDER BY Ordre";
					
				$resultTitreDisplay=mysqli_query($bdd,$req);
				$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
				
				$tabDisplay = array();
				if($nbTitreDisplay>0){
					while($rowDisplay=mysqli_fetch_array($resultTitreDisplay))
					{
						if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]<>"" 
						|| $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']<>""
						|| $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']<>""){
							echo "<tr><td class='Libelle'>";
							echo $rowDisplay['Titre']." : ";
							
							$filtres=str_replace(";",",",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']])."-1";
							

							if($rowDisplay['Valeur']=='Customer' || $rowDisplay['Valeur']=='Imputation' || $rowDisplay['Valeur']=='MSN' || $rowDisplay['Valeur']=='Type' 
								|| $rowDisplay['Valeur']=='WorkingShift' || $rowDisplay['Valeur']=='Priority' || $rowDisplay['Valeur']=='LastStatus'){
								if($rowDisplay['Valeur']=='Customer'){
									$req="SELECT Id,Libelle
									FROM gpao_customer
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
									AND Id IN (".$filtres.")
									ORDER BY Libelle";
								}
								elseif($rowDisplay['Valeur']=='Imputation'){
									$req="SELECT Id,Libelle
									FROM gpao_imputation
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
									AND Id IN (".$filtres.")						
									ORDER BY Libelle";
								}
								elseif($rowDisplay['Valeur']=='MSN'){
									$req="SELECT Id,MSN AS Libelle
									FROM gpao_aircraft
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
									AND Id IN (".$filtres.")
									ORDER BY Libelle";
								}
								elseif($rowDisplay['Valeur']=='Type'){
									$req="SELECT Id,Libelle
									FROM gpao_aircrafttype
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
									AND Id IN (".$filtres.")
									ORDER BY Libelle";
								}
								elseif($rowDisplay['Valeur']=='WorkingShift'){
									$req="SELECT Id,Libelle
									FROM gpao_workingshifts
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
									AND Id IN (".$filtres.")
									ORDER BY Libelle";
								}
								elseif($rowDisplay['Valeur']=='Priority'){
									$req="SELECT Id,Libelle
									FROM gpao_priority
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
									AND Id IN (".$filtres.")
									ORDER BY Libelle";
								}
								elseif($rowDisplay['Valeur']=='LastStatus'){
									$req="SELECT Id,Libelle
									FROM gpao_statutlist
									WHERE Suppr=0 
									AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
									AND Id IN (".$filtres.")
									ORDER BY Libelle";
								}
								$resultList=mysqli_query($bdd,$req);
								$nbList=mysqli_num_rows($resultList);
								
								if ($nbList > 0)
								{
									while($rowList=mysqli_fetch_array($resultList))
									{
										echo stripslashes($rowList['Libelle']).";";
									}
								 }
							}
							elseif($rowDisplay['Valeur']=='Position' || $rowDisplay['Valeur']=='AM' || $rowDisplay['Valeur']=='OF' || $rowDisplay['Valeur']=='NC' 
								|| $rowDisplay['Valeur']=='QLB' || $rowDisplay['Valeur']=='TLB' || $rowDisplay['Valeur']=='Concession' || $rowDisplay['Valeur']=='Para'
								|| $rowDisplay['Valeur']=='Designation' || $rowDisplay['Valeur']=='Skills' || $rowDisplay['Valeur']=='OTDComment' || $rowDisplay['Valeur']=='PriorityReason'
								|| $rowDisplay['Valeur']=='EscalationPoint' || $rowDisplay['Valeur']=='OTDEoW' || $rowDisplay['Valeur']=='FollowUpConcession'
								|| $rowDisplay['Valeur']=='FI' || $rowDisplay['Valeur']=='Comments' || $rowDisplay['Valeur']=='CommentsDQ1' 
								|| $rowDisplay['Valeur']=='StatusComment' || $rowDisplay['Valeur']=='CommentsA_CMS2'
								|| $rowDisplay['Valeur']=='PartNumber' || $rowDisplay['Valeur']=='CMS' || $rowDisplay['Valeur']=='RefDIV'){
								echo $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']];
							}
							elseif($rowDisplay['Valeur']=='PlanDate' || $rowDisplay['Valeur']=='LimitDateFOT' || $rowDisplay['Valeur']=='CreationDate' || $rowDisplay['Valeur']=='ClosureDate' || $rowDisplay['Valeur']=='NewEoW' || $rowDisplay['Valeur']=='LastEoW'
							 || $rowDisplay['Valeur']=='UpdateDateTandem' || $rowDisplay['Valeur']=='FOTDate' || $rowDisplay['Valeur']=='EoWDQ1' || $rowDisplay['Valeur']=='NewEoWDQ1' || $rowDisplay['Valeur']=='CreationDateDQ1' 
							 || $rowDisplay['Valeur']=='NewEoWAvailable' || $rowDisplay['Valeur']=='LastStatusDate' || $rowDisplay['Valeur']=='PartsDeliveryDate'
							|| $rowDisplay['Valeur']=='PartsReceivedOn'){
								 if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']<>""){
									if($_SESSION['Langue']=="EN"){echo "From ";}else{echo "Du ";}
									echo AfficheDateJJ_MM_AAAA(TrsfDate_($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']));
								 }
								 if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']<>""){
									if($_SESSION['Langue']=="EN"){echo " to ";}else{echo " au ";}
									echo AfficheDateJJ_MM_AAAA(TrsfDate_($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']));
								 }
							}
							elseif($rowDisplay['Valeur']=='WorkingProgress' || $rowDisplay['Valeur']=='TargetTime' || $rowDisplay['Valeur']=='Quantity'){
								echo $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Type'];
								echo "&nbsp;";
								echo $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']];
							}
							
							echo "</td></tr>";
						}
					}
				}
				?>
				<tr>
					<td align="right">
						<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Tri"  value="<?php if($_SESSION['Langue']=="EN"){echo "Clear sorting";}else{echo "Effacer les tris";}?>">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php 
	}	
?>
<br>
<?php 
	$req="SELECT SUM(Pourcentage) AS Somme
			FROM gpao_tableau 
			WHERE Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
			AND Display='".$display."' 
			AND Suppr=0 ";
			
		$resultTitreDisplay=mysqli_query($bdd,$req);
		$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
		
		$totalTable="";
		if($nbTitreDisplay>0){
			$rowDisplay=mysqli_fetch_array($resultTitreDisplay);
			$totalTable=$rowDisplay['Somme'];
		}
?>
<table class="TableCompetences" width="<?php echo $totalTable;?>px" align="center">
	<tr>
	<?php 
		$req="SELECT Titre, Valeur, Pourcentage,Type,LienInterface
			FROM gpao_tableau 
			WHERE Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
			AND Display='".$display."' 
			AND Suppr=0
			ORDER BY Ordre ";
			
		$resultTitreDisplay=mysqli_query($bdd,$req);
		$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
		
		$tabDisplay = array();
		if($nbTitreDisplay>0){
			while($rowDisplay=mysqli_fetch_array($resultTitreDisplay))
			{
				$tabDisplay[]=array($rowDisplay['Valeur'],$rowDisplay['Type'],$rowDisplay['LienInterface']);
	?>
				<td style="color:#00567c;border-bottom:2px #055981 solid;" width="<?php echo stripslashes($rowDisplay['Pourcentage']);?>px">
				<?php 
					echo '<a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TableauDeBord.php?Menu='.$_SESSION['Menu'].'&Tri='.$rowDisplay['Valeur'].'">';
					echo stripslashes($rowDisplay['Titre']);
					if($_SESSION['TriGPAO_'.$display.'_'.$rowDisplay['Valeur']]=="DESC"){echo "&uarr;";} 
					elseif($_SESSION['TriGPAO_'.$display.'_'.$rowDisplay['Valeur']]=="ASC"){echo "&darr;";}
					echo '</a>';
				?>
					<a href="javascript:OuvreFenetreFiltre('<?php echo $display;?>','<?php echo $rowDisplay['Valeur'];?>')">
					
					<?php 
						if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]<>"" 
						|| $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']<>""
						|| $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']<>""){
					?>
						<img src='../../Images/FiltrePlein.png' border='0' width='10px' alt='<?php if($_SESSION['Langue']=="EN"){echo "Filter";}else{echo "Filtre";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Filter";}else{echo "Filtre";} ?>'>
					<?php 		
						}
						else{
					?>
						<img src='../../Images/filtre.png' border='0' width='10px' alt='<?php if($_SESSION['Langue']=="EN"){echo "Filter";}else{echo "Filtre";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Filter";}else{echo "Filtre";} ?>'>
					<?php 
						}
					?>
					</a>
				</td>
	<?php
			}
		}
	?>
	</tr>
	<?php 
		if($display<>""){
			if($display=="General" 
			|| $display=="Coordination" 
			|| $display=="Production" 
			|| $display=="Quality"
			|| $display=="Concession"
			|| $display=="Archives"){
				$req="SELECT Id,
				(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
				(SELECT Libelle FROM gpao_imputation WHERE Id=Id_Imputation) AS Imputation,
				AM,OF,NC,QLB,TLB,Concession,Para,PlanDate,LimitDateFOT,
				Designation,TargetTime,WorkingProgress,FI,Id_Aircraft,
				(SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
				(SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Position,
				(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
				CreationDate,
				(SELECT Libelle FROM gpao_priority WHERE Id=Id_Priority) AS Priority,
				Comments,ClosureDate,
				(SELECT Libelle FROM gpao_workingshifts WHERE Id=Id_WorkingShift) AS WorkingShift,
				NewEoW,OTDEoW,EscalationPoint,LastEoW,OTDComment,UpdateDateTandem,
				FOTDate,EoWDQ1,NewEoWDQ1,CreationDateDQ1,CommentsDQ1,
				FollowUpConcession,CommentsA_CMS2,NewEoWAvailable,
				PriorityReason,Skills,
				(SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS LastStatus,
				(SELECT DateStatut FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS LastStatusDate,
				(SELECT StatusComments FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS StatusComment
				FROM gpao_wo
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
				if($display=="General"){
					$req.=" AND Invoiced=0 ";
				}
				elseif($display=="Coordination"){
					$req.=" 
					AND Invoiced=0
					AND ((SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) 
					NOT IN ('TERC CLOSED','CANCELLED','CANNIBALIZATION','CLOSED POINT','DELETE','OPEN POINTS','PARA STAMPED SENT','TERC CUSTOMER','TERC PARTNER','TRANSFERRED')
					OR (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) IS NULL
					) ";
				}
				elseif($display=="Production"){
					$req.="
					AND Invoiced=0
					AND ((SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) 
					IN ('BLOCKED ACCESS PROD','DRESS OUT AAA','PROD CUSTOMER','PROD LAUNCHED','PROD SHOPFLOOR','QUALITY INTERMEDIATE CHECK','QUALITY TERA','READY PLANIFICATION','REWORK PRODUCTION','SANDBLASTING')
					) ";
				}
				elseif($display=="Quality"){
					$req.="
					AND Invoiced=0
					AND ((SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) 
					IN ('NDT','PROD LAUNCHED','SANDBLASTING','WAITING FEEBACK CUSTOMER')
					OR (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) LIKE '%CONCESSION%'
					OR (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) LIKE '%REWORK%'
					OR (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) LIKE '%DRESS OUT%'
					OR (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) LIKE '%QUALITY%'
					OR (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) LIKE '%REJECT%'
					) ";
				}
				elseif($display=="Concession"){
					$req.=" AND Invoiced=0 
					AND FollowUpConcession=1 ";
				}
				elseif($display=="Archives"){
					$req.=" AND Invoiced=1 ";
				}
			}
			elseif($display=="Logistic"){
				$req="SELECT gpao_wo.Id,
				(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
				(SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
				(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
				AM,OF,NC,QLB,TLB,Para,Id_Aircraft,CreationDate,
				(SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) AS LastStatus,
				PartNumber,Quantity,CMS,RefDIV,PartsDeliveryDate,PartsReceivedOn
				FROM gpao_logistic
				RIGHT JOIN gpao_wo ON gpao_logistic.Id_WO=gpao_wo.Id
				WHERE gpao_wo.Suppr=0 
				AND (gpao_logistic.Suppr=0 
					OR (SELECT COUNT(Id)FROM gpao_logistic WHERE Suppr=0 AND Id_WO=gpao_wo.Id)=0
				)
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				AND ClosureDate<='0001-01-01' 
				AND (SELECT (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) 
				IN ('PARTS MISSING','FOR LOGISTIC','BLOCKED LOGISTIC')";
			}

			//Filtre
			$reqFiltre="SELECT Display,Titre,Valeur, Type
				FROM gpao_tableau 
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				AND Display='".$display."' 
				ORDER BY Ordre";
				
			$resultTitreDisplay=mysqli_query($bdd,$reqFiltre);
			$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
			
			if($nbTitreDisplay>0){
				while($rowDisplay=mysqli_fetch_array($resultTitreDisplay))
				{
					if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]<>"" 
					|| $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']<>""
					|| $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']<>""){

						$filtres=str_replace(";",",",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']])."-1";
						
						if($rowDisplay['Valeur']=='Customer'){$req.=" AND Id_Customer IN (".$filtres.") ";}
						elseif($rowDisplay['Valeur']=='Imputation'){$req.=" AND Id_Imputation IN (".$filtres.") ";}
						elseif($rowDisplay['Valeur']=='MSN'){$req.=" AND Id_Aircraft IN (".$filtres.") ";}
						elseif($rowDisplay['Valeur']=='Type'){$req.=" AND (SELECT Id_AircraftType FROM gpao_aircraft WHERE Id=Id_Aircraft) IN (".$filtres.") ";}
						elseif($rowDisplay['Valeur']=='WorkingShift'){$req.=" AND Id_WorkingShift IN (".$filtres.") ";}
						elseif($rowDisplay['Valeur']=='Priority'){$req.=" AND Id_Priority IN (".$filtres.") ";}
						elseif($rowDisplay['Valeur']=='LastStatus'){$req.=" AND (SELECT Id_StatutList FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) IN (".$filtres.") ";}
						
						
						
						elseif($rowDisplay['Valeur']=='Position'){
							$reqFiltre2="";
							$tabFiltre=explode(";",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]);
							foreach($tabFiltre as $lefiltre){
								if($lefiltre<>""){
									$reqFiltre2.=' (SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) LIKE "%'.$lefiltre.'%" OR ';
								}
							}
							
							if($reqFiltre2<>""){
								$req.=" AND (".substr($reqFiltre2,0,-3).")";
							}
						}
						elseif($rowDisplay['Valeur']=='EscalationPoint' || $rowDisplay['Valeur']=='OTDEoW' || $rowDisplay['Valeur']=='FollowUpConcession'){
							$filtres=substr(str_replace(";",",",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_2']),0,-1);
							
							$req.=" AND ".$rowDisplay['Valeur']." IN (".$filtres.") ";
						}
						elseif($rowDisplay['Valeur']=='AM' || $rowDisplay['Valeur']=='OF' || $rowDisplay['Valeur']=='NC' 
							|| $rowDisplay['Valeur']=='QLB' || $rowDisplay['Valeur']=='TLB' || $rowDisplay['Valeur']=='Concession' || $rowDisplay['Valeur']=='Para'
							|| $rowDisplay['Valeur']=='Designation' || $rowDisplay['Valeur']=='Skills' || $rowDisplay['Valeur']=='OTDComment' || $rowDisplay['Valeur']=='PriorityReason'
							|| $rowDisplay['Valeur']=='FI' || $rowDisplay['Valeur']=='Comments' || $rowDisplay['Valeur']=='CommentsDQ1' 
							|| $rowDisplay['Valeur']=='CommentsA_CMS2' 
							|| $rowDisplay['Valeur']=='PartNumber' || $rowDisplay['Valeur']=='CMS' || $rowDisplay['Valeur']=='RefDIV'){

							$reqFiltre2="";
							$tabFiltre=explode(";",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]);
							foreach($tabFiltre as $lefiltre){
								if($lefiltre<>""){
									$reqFiltre2.=' '.$rowDisplay['Valeur'].' LIKE "%'.$lefiltre.'%" OR ';
								}
							}
							
							if($reqFiltre2<>""){
								$req.=" AND (".substr($reqFiltre2,0,-3).")";
							}
						}
						elseif($rowDisplay['Valeur']=='StatusComment'){
							$reqFiltre2="";
							$tabFiltre=explode(";",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]);
							foreach($tabFiltre as $lefiltre){
								if($lefiltre<>""){
									$reqFiltre2.=' (SELECT StatusComments FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) LIKE "%'.$lefiltre.'%" OR ';
								}
							}
							
							if($reqFiltre2<>""){
								$req.=" AND (".substr($reqFiltre2,0,-3).")";
							}
						}
						
						elseif($rowDisplay['Valeur']=='WorkingProgress'){$req.=" AND WorkingProgress".$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Type']." ".$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]." ";}
						elseif($rowDisplay['Valeur']=='TargetTime'){$req.=" AND TargetTime".$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Type']." ".$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]." ";}
						elseif($rowDisplay['Valeur']=='Quantity'){$req.=" AND Quantity".$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Type']." ".$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur']]." ";}
						
						elseif($rowDisplay['Valeur']=='PlanDate' || $rowDisplay['Valeur']=='LimitDateFOT' || $rowDisplay['Valeur']=='CreationDate' || $rowDisplay['Valeur']=='ClosureDate' || $rowDisplay['Valeur']=='NewEoW' || $rowDisplay['Valeur']=='LastEoW'
						 || $rowDisplay['Valeur']=='UpdateDateTandem' || $rowDisplay['Valeur']=='FOTDate' || $rowDisplay['Valeur']=='EoWDQ1' || $rowDisplay['Valeur']=='NewEoWDQ1' || $rowDisplay['Valeur']=='CreationDateDQ1' 
						 || $rowDisplay['Valeur']=='NewEoWAvailable' || $rowDisplay['Valeur']=='PartsDeliveryDate'
						 || $rowDisplay['Valeur']=='PartsReceivedOn'){
							 if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']<>""){
								$req.=" AND ".$rowDisplay['Valeur']." >= '".TrsfDate_($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du'])."' ";
							 }
							 if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']<>""){
								$req.=" AND ".$rowDisplay['Valeur']." <= '".TrsfDate_($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au'])."' ";
							 }
						}
						elseif($rowDisplay['Valeur']=='LastStatusDate'){
							if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du']<>""){
								$req.=" AND (SELECT DateStatut FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) >= '".TrsfDate_($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Du'])."' ";
							 }
							 if($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au']<>""){
								$req.=" AND (SELECT DateStatut FROM gpao_statutquality WHERE Suppr=0 AND Id_WO=gpao_wo.Id ORDER BY DateStatut DESC LIMIT 1) <= '".TrsfDate_($_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$rowDisplay['Valeur'].'_Au'])."' ";
							 }
						}
						
					}
				}
			}

			$reqOrder="";
			if($_SESSION['TriGPAO_'.$display.'_General']<>""){
				$reqOrder=" ORDER BY ".substr($_SESSION['TriGPAO_'.$display.'_General'],0,-1);
			}

			$result=mysqli_query($bdd,$req.$reqOrder);
			$nbLigne=mysqli_num_rows($result);
			
			$tabON[0]="";
			$tabON[1]="Yes";
			if($nbLigne>0){
				
				while($rowLigne=mysqli_fetch_array($result))
				{
					$laCouleur="#FFFFFF";
					if($display=="General" || $display=="Coordination" || $display=="Production"
						|| $display=="Quality" || $display=="Archives")
					{
						if($rowLigne['LastStatus']=="TERC CLOSED" || $rowLigne['LastStatus']=="TERC AIRBUS"){
							$laCouleur="bgcolor='#13aa00' ";
						}
						elseif($rowLigne['LastStatus']=="TERC CUSTOMER"){
							$laCouleur="bgcolor='#979797' ";
						}
						elseif($rowLigne['LastStatus']=="TERA" || $rowLigne['LastStatus']=="QUALITY TERA"){
							$laCouleur="bgcolor='#67ffc1' ";
						}
						elseif($rowLigne['LastStatus']=="PROD LAUNCHED" || $rowLigne['LastStatus']=="LAUNCHED" || $rowLigne['LastStatus']=="SANDBLASTING"){
							$laCouleur="bgcolor='#004ee9' ";
						}
						elseif($rowLigne['LastStatus']=="PROD SHOPFLOOR" || $rowLigne['LastStatus']=="PROD READY"){
							$laCouleur="bgcolor='#67c6ff' ";
						}
						elseif($rowLigne['LastStatus']=="ACCESS PRODUCTION" || $rowLigne['LastStatus']=="ACCESS QUALITY"
						|| $rowLigne['LastStatus']=="BLOCKED IS" || $rowLigne['LastStatus']=="PARTS MISSING"
						|| $rowLigne['LastStatus']=="WAITING DESIGN OFFICE (BE)" || $rowLigne['LastStatus']=="BLOCKED ACCESS"
						|| $rowLigne['LastStatus']=="BLOCKED AR"){
							$laCouleur="bgcolor='#ffbad5' ";
						}
					}
				?>
					<tr>
				<?php
					foreach($tabDisplay as $colonne){
						
						$couleurCellule=$laCouleur;
						
						if($display=="General" || $display=="Coordination" || $display=="Production"
						|| $display=="Quality" || $display=="Archives"){
							if($colonne[0]=="PlanDate"){
								if($rowLigne[$colonne[0]]<date('Y-m-d') && $rowLigne[$colonne[0]]>'0001-01-01'){
									$couleurCellule="bgcolor='#ff0303' ";
								}
							}
							elseif($colonne[0]=="EscalationPoint"){
								if($rowLigne[$colonne[0]]==1){
									$couleurCellule="bgcolor='#bd0000' ";
								}
							}
						}
				?>
						<td style='border-bottom:1px dotted black;cursor:pointer;' <?php echo $couleurCellule;?> <?php if($colonne[2]<>""){echo "onclick=\"OuvreFenetre('".$rowLigne['Id']."','".$colonne[2]."','".$rowLigne['Id_Aircraft']."')\"";}?>>
						<?php 
							if($colonne[1]=="Texte"){echo stripslashes($rowLigne[$colonne[0]]);}
							elseif($colonne[1]=="Date"){echo AfficheDateJJ_MM_AAAA($rowLigne[$colonne[0]]);}
							elseif($colonne[1]=="Booleen"){echo $tabON[$rowLigne[$colonne[0]]];}
							else{
								echo $rowLigne[$colonne[0]];
							}
						?>
						</td>
				<?php
					}
				?>
					</tr>
				<?php
				}
				
			}
		}
	?>
</table>