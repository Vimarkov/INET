<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Action.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" language="Javascript" src="ModifAction.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	
		<!-- Script DATE  -->
	<script>
		var initDatepicker = function() {  
		$('input[type=date]').each(function() {  
			var $input = $(this);  
			$input.datepicker({  
				minDate: $input.attr('min'),  
				maxDate: $input.attr('max'),  
				dateFormat: 'dd/mm/yy'  
				});  
			});  
		};  
		  
		if(!Modernizr.inputtypes.date){  
			$(document).ready(initDatepicker);  
		}; 
	 </script>
</head>
<?php
require("../Connexioni.php");
require("../Fonctions.php");

if ($_GET)
{
	$IdAction = $_GET['Id_Action'];
	
	$req = "SELECT Id_ActionLiee FROM new_action WHERE new_action.Id =".$IdAction.";";
	$resultAction=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($resultAction);
	
	$req = "SELECT Id, DateCreation,Vacation, Id_Prestation, Id_Pole, Id_Acteur, Id_Createur, SituationAvancement, ";
	$req .= "new_action.Probleme, new_action.Action, new_action.Id_Acteur, new_action.Delais, new_action.Avancement, new_action.DateSolde, new_action.Niveau, new_action.ReprisDQ506, ";
	$req .= "(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id = new_action.Id_Prestation) AS Prestation, ";
	$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_action.Id_Pole) AS Pole, new_action.Lettre, new_action.DateSolde, new_action.Commentaire ";
	$req .= "FROM new_action ";
	if($row['Id_ActionLiee'] == 0){
		$req .= "WHERE new_action.Id =".$IdAction." OR new_action.Id_ActionLiee=".$IdAction." ";
	}
	else{
		$req .= "WHERE new_action.Id =".$row['Id_ActionLiee']." OR new_action.Id_ActionLiee=".$row['Id_ActionLiee']." ";		
	}
	$req .= "ORDER BY Id ";
		
	$resultAction=mysqli_query($bdd,$req);		
	$nbAction=mysqli_num_rows($resultAction);
	$row=mysqli_fetch_array($resultAction);
			
	$reqActeur = "SELECT CONCAT(Nom, ' ', Prenom) AS Acteur ";
	$reqActeur .= "FROM new_rh_etatcivil ";
	$reqActeur .= "WHERE  new_rh_etatcivil.Id = ".$row['Id_Acteur']."; ";
			
	$resultActeur=mysqli_query($bdd,$reqActeur);
	$rowActeur=mysqli_fetch_array($resultActeur);
	
	$reqCreateur = "SELECT CONCAT(Nom, ' ', Prenom) AS Createur ";
	$reqCreateur .= "FROM new_rh_etatcivil ";
	$reqCreateur .= "WHERE  new_rh_etatcivil.Id = ".$row['Id_Createur']."; ";
	
	$resultCreateur=mysqli_query($bdd,$reqCreateur);
	$rowCreateur=mysqli_fetch_array($resultCreateur);
}
?>
<form class="test" method="POST" action="ModifAction.php" onSubmit="return VerifChamps();">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">Historique de l'action</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
		if ($nbAction>0){
		?>
			<tr><td height="4"></td></tr>
			<tr><td>
				<table style="font-weight:bold;" width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
					<tr>
						<td width=8%>
							&nbsp; Prestation : 
						</td>
						<td width=50%>
							<?php 
								echo $row['Prestation'];
							?>
						</td>
						<td width=8%>
							&nbsp; Pôle : 
						</td>
						<td width=10%>
							<div id="pole">
							<?php 
								echo $row['Pole'];
							?>
							</div>
						</td>
						<td width=8%>
							&nbsp; Lettre : 
						</td>
						<td width=10%>
							<?php	
							echo $row['Lettre'];
							?>
						</td>
					</tr>
					<tr>
						<td height="4"></td>
					</tr>
					<tr>
					<tr>
						<td width=8%>
							&nbsp; Problème : 
						</td>
						<td colspan="7">
							<?php
								echo $row['Probleme'];
							?>
						</td>
					</tr>
				</table>
			</td></tr>
		<?php
			mysqli_data_seek($resultAction,0);
			while($row=mysqli_fetch_array($resultAction)){
				$prestation = $row['Prestation'];
				$pole = $row['Pole'];
				$uneDate = $row['DateCreation'];
				$vacation = $row['Vacation'];
				$couleur="";
				if($row['Id']==$IdAction){$couleur= "style='font-weight:bold;background-color:#ebec34;'";}
				else{$couleur= "style='font-weight:bold;background-color:#c0f1ff;'";}
		?>
				<tr><td height="4"></td></tr>
				<tr><td>
					<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
						<tr>
							<td height="4"></td>
						</tr>
						<tr>
							<td width=8% <?php echo $couleur; ?>>
								&nbsp; N° : <?php echo $row['Id'];?>
							</td>
							<td width=8% <?php echo $couleur; ?>>
								&nbsp; Niveau : <?php echo $row['Niveau'];?>
							</td>
							<td width=20% <?php echo $couleur; ?>>
								&nbsp; Date : <?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']); ?>
							</td>
							<td width=20% <?php echo $couleur; ?>>
								&nbsp; Vacation : <?php echo $row['Vacation']; ?>
							</td>
							<td width=20% <?php echo $couleur; ?>> 
								&nbsp; Acteur : <?php echo $rowActeur['Acteur']; ?>
							</td>
							<td width=20% <?php echo $couleur; ?>> 
								&nbsp; Createur : <?php echo $rowCreateur['Createur']; ?>
							</td>
						</tr>
						<tr>
							<td height="4"></td>
						</tr>
						<tr>
							<td colspan="7">
							<table>
								<tr>
									<td>
										<table>
											<tr>
												<td valign="top">
													Action : 
												</td>
												<td colspan="3" valign="top">
													<textarea style="text-align:left;" id="action" name="action" readonly="readonly" cols="60" rows="3"><?php echo $row['Action'];?></textarea>
												</td>
												<td valign="top">
													Commentaire : 
												</td>
												<td colspan="3" valign="top">
													<textarea style="text-align:left;" id="commentaire" name="commentaire" readonly="readonly" cols="60" rows="3"><?php echo $row['Commentaire'];?></textarea>
												</td>
											</tr>
											<tr>
												<td valign="top">
													Situation d'avancement : 
												</td>
												<td valign="top">
													<textarea style="text-align:left;" id="situation" name="situation" readonly="readonly" cols="60" rows="2"><?php echo $row['SituationAvancement'];?></textarea>
												</td>
												<td colspan="4">
													<table>
														<tr>
															<td>
																&nbsp; Délais :
															</td>
															<td>
																<?php
																	$valDelais="";
																	if($row['Delais'] > '0001-01-01'){$valDelais=AfficheDateJJ_MM_AAAA($row['Delais']);}
																	echo $valDelais;
																?>
															</td>
															<td>
																&nbsp; Repris par le D-0601 : 
															</td>
															<td>
																<?php
																	$selectOui="";
																	$selectNon="";
																	if ($nbAction>0){
																		if($row['ReprisDQ506']==1){$selectOui="selected";}
																		elseif($row['ReprisDQ506']==2){$selectNon="selected";}
																	}
																?>
																<select name="repris" id="repris" disabled>
																	<option value="1" <?php echo $selectOui;?>>Oui</option>
																	<option value="2" <?php echo $selectNon;?>>Non</option>
																</select>
															</td>
														</tr>
														<tr>
															<td>
																&nbsp; Avancement : 
															</td>
															<td>
																<?php
																	$select0="";
																	$select1="";
																	$select2="";
																	$select3="";
																	$select4="";
																	$select5="";
																	$select6="";
																	if ($nbAction>0){
																		if($row['Avancement']==0){$select0="selected";}
																		elseif($row['Avancement']==1){$select1="selected";}
																		elseif($row['Avancement']==2){$select2="selected";}
																		elseif($row['Avancement']==3){$select3="selected";}
																		elseif($row['Avancement']==4){$select4="selected";}
																		elseif($row['Avancement']==5){$select5="selected";}
																		elseif($row['Avancement']==6){$select6="selected";}
																	}
																?>
																<select id="avancement" name="avancement" onchange="AfficherAvancement();" disabled>
																	<option value="0" <?php echo $select0;?>>Point non pris en compte</option>
																	<option value="1" <?php echo $select1;?>>Point pris en compte</option>
																	<option value="2" <?php echo $select2;?>>Point e/c</option>
																	<option value="3" <?php echo $select3;?>>Solution/Action</option>
																	<option value="4" <?php echo $select4;?>>Action clôturée</option>
																	<option value="5" <?php echo $select5;?>>Poursuivre N+1</option>
																	<option value="6" <?php echo $select6;?>>Poursuivre N-1</option>
																</select>
																<div id="ImgAvancement" style="display:inline;">
																	<?php
																		if($row['Avancement']==0){echo "<img src='../../Images/NonPrisEnCompte.gif' border='0' alt='NonPrisEnCompte' title='Non pris en compte'>";}
																		elseif($row['Avancement']==1){echo "<img src='../../Images/EnCompte.gif' border='0' alt='EnCompte' title='En compte'>";}
																		elseif($row['Avancement']==2){echo "<img src='../../Images/EnCours.gif' border='0' alt='EnCours' title='En cours'>";}
																		elseif($row['Avancement']==3){echo "<img src='../../Images/Solution.gif' border='0' alt='Solution' title='Solution/action'>";}
																		elseif($row['Avancement']>=4){echo "<img src='../../Images/Cloturee.gif' border='0' alt='Cloturee' title='Cloturée'>";}
																	?>
																</div>
															</td>
															<?php
																echo "<td>";
																echo "&nbsp; Date de clôture : ";
																echo "</td>";
																echo "<td id='corpsCloture'>";
																if($row['Delais'] > '0001-01-01'){echo AfficheDateFR($row['DateSolde']);}
																echo "</td>";
															?>
														</tr>
													</table>
												</td>

											</tr>
											<tr>
												<td height="4"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							</td>
						</tr>
					</table>
				</td></tr>
		<?php
			}
		}
		?>
	</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>