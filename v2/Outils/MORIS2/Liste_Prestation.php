<script language="javascript">
	function OuvreFenetreAjout(){
		var w=window.open("Ajout_Prestation.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1100,height=350");
		w.focus();
		}
	function OuvreFenetreSuppr(Id){
		if(window.confirm('Cette suppression permet de supprimer une prestation qui n\'a jamais utilisé RECORD.\nSa supression permettra de ne pas la prendre en compte dans les indicateurs.\nPour rendre inactive une prestation aller dans Gestion des prestations -> Prestation.\n\nEtes-vous sûr de vouloir supprimer cette prestation de RECORD ?')){
			var w=window.open("Ajout_Prestation.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
	function OuvreFenetreModif(Id)
		{
			var w= window.open("Ajout_Prestation.php?Mode=M&Id="+Id,"PageLieu","status=no,menubar=no,width=1100,height=500");
			w.focus();
		}
</script>
<?php
function SousTitre($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:3px solid #ffffff;font-style:italic;font-size:14px;";$couleurTexte="#ffffff";}
	echo "<td style=\"height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr bgcolor="#6EB4CD">
		<?php
		if($_SESSION["Langue"]=="FR"){SousTitre("ACCES SUPP.","Outils/MORIS2/TableauDeBord.php?Menu=16",false);}
		else{SousTitre("ADDITIONAL ACCESS","Outils/MORIS2/TableauDeBord.php?Menu=16",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("ADMINISTRATEURS","Outils/MORIS2/TableauDeBord.php?Menu=3",false);}
		else{SousTitre("ADMINISTRATOR","Outils/MORIS2/TableauDeBord.php?Menu=3",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("LISTE PRESTATIONS","Outils/MORIS2/TableauDeBord.php?Menu=4",true);}
		else{SousTitre("LIST OF SITE","Outils/MORIS2/TableauDeBord.php?Menu=4",true);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("AIDE","Outils/MORIS2/TableauDeBord.php?Menu=5",false);}
		else{SousTitre("HELP","Outils/MORIS2/TableauDeBord.php?Menu=5",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		else{SousTitre("CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=10",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("CONTRAT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}
		else{SousTitre("CONTRACT","Outils/MORIS2/TableauDeBord.php?Menu=9",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("DIVISION CLIENT","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}
		else{SousTitre("CUSTOMER DIVISION","Outils/MORIS2/TableauDeBord.php?Menu=13",false);}

		if($_SESSION["Langue"]=="FR"){SousTitre("ENTITE ACHAT","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
		else{SousTitre("PURCHASE ENTITY","Outils/MORIS2/TableauDeBord.php?Menu=11",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("PROGRAMME / PRODUIT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
		else{SousTitre("PROGRAM / PRODUCT","Outils/MORIS2/TableauDeBord.php?Menu=12",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("FAMILLE METIER/FONCTION","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
		else{SousTitre("JOB/POSITION FAMILY","Outils/MORIS2/TableauDeBord.php?Menu=15",false);}
		
		if($_SESSION["Langue"]=="FR"){SousTitre("OBJECTIFS","Outils/MORIS2/TableauDeBord.php?Menu=18",false);}
		else{SousTitre("OBJECTIVES","Outils/MORIS2/TableauDeBord.php?Menu=18",false);}
		?>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td width="100%" colspan="11">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
				<tr><td height="4"></td></tr>
				<tr>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Statut : ";}else{echo "Status : ";}?></td>
					<td width="90%">
						<select name="statut" style="width:150px;" onchange="submit();">
							<?php
								$statut=$_SESSION['FiltreRECORD_Statut'];
								if($_POST){$statut=$_POST['statut'];}
								$_SESSION['FiltreRECORD_Statut']=$statut;
							?>
							<option value="0" <?php if($statut=="0"){echo "selected";} ?>></option>
							<option value="1" <?php if($statut=="1"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "En cours de suivi";}else{echo "In progress";}?></option>
							<option value="2" <?php if($statut=="2"){echo "selected";} ?>><?php if($_SESSION['Langue']=="FR"){echo "Plus suivi";}else{echo "No longer being monitored";}?></option>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td align="center" colspan="11">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a site";}else{echo "Ajouter une prestation";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="11">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Follow-up start date";}else{echo "Date de début de suivi";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Follow-up end date";}else{echo "Date de fin de suivi";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Contract";}else{echo "Contrat";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Customer division";}else{echo "Division client";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Family R03";}else{echo "Famille R03";} ?></td>
				<td class="EnTeteTableauCompetences" width="6%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Purchasing entity";}else{echo "Entité achat";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Chargec";}else{echo "Charge";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Productivity";}else{echo "Productivité";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Prevention plan";}else{echo "Plan prévention";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OTD/OQD<br>tolerance";}else{echo "Tolérance<br>OTD/OQD";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Versatility";}else{echo "Polyvalence";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Skill";}else{echo "Compétence";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Management";}else{echo "Management";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Security";}else{echo "Sécurité";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "PRM & Customer Satisfaction";}else{echo "PRM & Satisfaction client";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "NC & RC";}else{echo "NC & RC";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OTD & OQD";}else{echo "OTD & OQD";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%" colspan="2"></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle, RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,Sigle,PlanPreventionADesactivite,PolyvalenceADesactive,
					(SELECT DateDebut FROM moris_datesuivi WHERE Suppr=0 AND Id_Prestation=new_competences_prestation.Id ORDER BY DateDebut DESC LIMIT 1) AS DateDebut,
					(SELECT DateFin FROM moris_datesuivi WHERE Suppr=0 AND Id_Prestation=new_competences_prestation.Id ORDER BY DateDebut DESC LIMIT 1) AS DateFin,
					(SELECT Libelle FROM moris_contrat WHERE Id=Id_Contrat) AS NomContrat,
					(SELECT Libelle FROM moris_client WHERE Id=Id_Client) AS Client,
					(SELECT Libelle FROM moris_entiteachat WHERE Id=Id_EntiteAchat) AS EntiteAchat,
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
					(SELECT Libelle FROM moris_divisionclient WHERE Id=Id_DivisionClient) AS DivisionClient,
					(SELECT Num FROM moris_famille_r03 WHERE Id=Id_FamilleR03) AS FamilleR03,
					ProductiviteADesactive,ChargeADesactive,
					(SELECT Num FROM moris_famille_r03 WHERE Id=Id_FamilleR03) AS FamilleR03,ToleranceOTDOQD,Id_Plateforme,
					OTDOQDADesactive,ManagementADesactive,CompetenceADesactive,SecuriteADesactive,PRMADesactive,NCADesactive
					FROM new_competences_prestation
					WHERE new_competences_prestation.UtiliseMORIS=1
					";
				if($_SESSION['FiltreRECORD_Statut']=="1"){
					$req.="AND (SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE Id_Prestation=new_competences_prestation.Id
								AND Suppr=0 
								AND DateDebut<='".date('Y-m-d')."'
								AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01')
								)>0 ";
				}
				elseif($_SESSION['FiltreRECORD_Statut']=="2"){
					$req.="AND (SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE Id_Prestation=new_competences_prestation.Id
								AND Suppr=0
								AND DateDebut<='".date('Y-m-d')."'
								AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01')
								)=0 ";
				}
				$req.="ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						$tolerance="<img width='15px' src='../../Images/delete.png' border='0' />";
						if($row['ToleranceOTDOQD']==1){$tolerance="<img width='15px' src='../../Images/tick.png' border='0' />";}
						
						$planPrevention="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['PlanPreventionADesactivite']==1){$planPrevention="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$productivite="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['ProductiviteADesactive']==1){$productivite="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$charge="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['ChargeADesactive']==1){$charge="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$polyvalence="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['PolyvalenceADesactive']==1){$polyvalence="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$otdOqd="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['OTDOQDADesactive']==1){$otdOqd="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$management="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['ManagementADesactive']==1){$management="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$competence="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['CompetenceADesactive']==1){$competence="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$securite="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['SecuriteADesactive']==1){$securite="<img width='15px' src='../../Images/delete.png' border='0' />";}
						elseif($row['SecuriteADesactive']==0){$securite="<img width='15px' src='../../Images/tick.png' border='0' />";}
						elseif($row['SecuriteADesactive']==-1){$securite="<b>M</b>";}
						
						$prm="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['PRMADesactive']==1){$prm="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$nc="<img width='15px' src='../../Images/tick.png' border='0' />";
						if($row['NCADesactive']==1){$nc="<img width='15px' src='../../Images/delete.png' border='0' />";}
						
						$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
						if($presta==""){$presta=$row['Libelle'];}
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td>&nbsp;<?php echo $presta; ?></td>
							<td><?php echo stripslashes($row['Plateforme']);?></td>
							<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
							<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
							<td><?php echo stripslashes($row['NomContrat']);?></td>
							<td><?php echo stripslashes($row['Client']);?></td>
							<td><?php echo stripslashes($row['DivisionClient']);?></td>
							<td><?php echo stripslashes($row['FamilleR03']);?></td>
							<td><?php echo stripslashes($row['EntiteAchat']);?></td>
							<td><?php echo $charge;?></td>
							<td><?php echo $productivite;?></td>
							<td><?php echo $planPrevention;?></td>
							<td><?php echo $tolerance;?></td>
							<td><?php echo $polyvalence;?></td>
							<td><?php echo $competence;?></td>
							<td><?php echo $management;?></td>
							<td><?php echo $securite;?></td>
							<td><?php echo $prm;?></td>
							<td><?php echo $nc;?></td>
							<td><?php echo $otdOqd;?></td>
							<td align="center">
								<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Edit";}else{echo "Modif";} ?>'>
								</a>
							</td>
							<td align="center">
								<?php if($_SESSION['Id_Personne']==1351 || $_SESSION['Id_Personne']==2526){ ?>
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
								<?php } ?>
							</td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
</table>