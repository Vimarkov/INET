<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<?php
	require("../Connexioni.php");
	require_once("../Fonctions.php");
	
	if ($_GET){
		$Idnew_v2perfos = $_GET['Id_perfos'];
		$prestation = "";
		$pole = "";
		$uneDate = "";
		$vacation="";
		$CommentaireS_J_1 = "";
		$CommentaireQ_J_1 = "";
		$CommentaireC_J_1 = "";
		$CommentaireD_J_1 = "";
		$CommentaireP_J_1 = "";
		$CommentaireF_J_1 = "";
		$CommentaireGeneral = "";
		$noteS_J_1 = "0";
		$noteQ_J_1 = "0";
		$noteC_J_1 = "0";
		$noteD_J_1 = "0";
		$noteP_J_1 = "0";
		$noteF_J_1 = "0";
		
		$req = "SELECT new_v2sqcdpf.Id, new_v2sqcdpf.DateSQCDPF, Vacation, new_v2sqcdpf.Id_Prestation, new_v2sqcdpf.Id_Pole, new_v2sqcdpf.Id_Personne1, ";
		$req .= "(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id = new_v2sqcdpf.Id_Prestation) AS Prestation, ";
		$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_v2sqcdpf.Id_Pole) AS Pole, ";
		$req .= "new_v2sqcdpf.S_J, new_v2sqcdpf.Q_J, new_v2sqcdpf.C_J, new_v2sqcdpf.D_J, new_v2sqcdpf.P_J, new_v2sqcdpf.F_J, new_v2sqcdpf.S_J_1, new_v2sqcdpf.Q_J_1, new_v2sqcdpf.C_J_1, ";
		$req .= "new_v2sqcdpf.D_J_1, new_v2sqcdpf.P_J_1, new_v2sqcdpf.F_J_1, new_v2sqcdpf.CommentaireS_J_1, new_v2sqcdpf.CommentaireQ_J_1, new_v2sqcdpf.CommentaireC_J_1, ";
		$req .="new_v2sqcdpf.CommentaireD_J_1, new_v2sqcdpf.CommentaireP_J_1, new_v2sqcdpf.CommentaireF_J_1, new_v2sqcdpf.CommentaireGeneral ";
		$req .= "FROM new_v2sqcdpf ";
		$req .= "WHERE new_v2sqcdpf.Id =".$Idnew_v2perfos.";";
		
		$resultnew_v2perfos=mysqli_query($bdd,$req);
		$nbnew_v2perfos=mysqli_num_rows($resultnew_v2perfos);
		if ($nbnew_v2perfos>0){
			$row=mysqli_fetch_array($resultnew_v2perfos);
			$prestation = $row['Prestation'];
			$pole = $row['Pole'];
			$uneDate = AfficheDateJJ_MM_AAAA($row['DateSQCDPF']);
			$vacation = $row['Vacation'];
			$CommentaireS_J_1 = $row['CommentaireS_J_1'];
			$CommentaireQ_J_1 = $row['CommentaireQ_J_1'];
			$CommentaireC_J_1 = $row['CommentaireC_J_1'];
			$CommentaireD_J_1 = $row['CommentaireD_J_1'];
			$CommentaireP_J_1 = $row['CommentaireP_J_1'];
			$CommentaireF_J_1 = $row['CommentaireF_J_1'];
			$CommentaireGeneral = $row['CommentaireGeneral'];
			$noteS_J_1 = $row['S_J_1'];
			$noteQ_J_1 = $row['Q_J_1'];
			$noteC_J_1 = $row['C_J_1'];
			$noteD_J_1 = $row['D_J_1'];
			$noteP_J_1 = $row['P_J_1'];
			$noteF_J_1 = $row['F_J_1'];
		}
	}
?>
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
<form class="test" method="POST" action="AjoutPERFOS.php">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">SQCDPF # Consulter un SQCDPF</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td class="enTete" width=30%>
						&nbsp; Prestation : <?php echo $prestation ?>
					</td>
					<td class="enTete" width=15%>
						&nbsp; Pôle : <?php echo $pole ?>
					</td>
					<td class="enTete" width=20%>
						&nbsp;
						Date : <?php echo $uneDate ?>
					</td>
					<td class="enTete" width=20%>
						&nbsp;
						Vacation : <?php echo $vacation ?>
					</td>
					<td width=5%>

					</td>
				</tr>
			</table>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table  class="TableCompetences" width="100%" cellpadding="0" cellspacing="0" align="center">
				<tr><td height="4"></td></tr>
				<tr>
					<td class="TitreSousPagePERFOS" >Reporting SQCDPF J-1/S-1</td>
				</tr>
				<tr>
					<td ><br/></td>
				</tr>
				<tr><td>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr align="center">
							<?php
								if ($noteS_J_1 == "1"){echo "<td class='lettreVerte'>S<font style=\"font-size:12px;\">écurité</font></td>";}
								elseif ($noteS_J_1 == "2"){echo "<td class='lettreRouge'>S<font style=\"font-size:12px;\">écurité</font></td>";}
								else{echo "<td class='lettre'>S<font style=\"font-size:12px;\">écurité</font></td>";}
								
								if ($noteQ_J_1 == "1"){echo "<td class='lettreVerte'>Q<font style=\"font-size:12px;\">ualité</font></td>";}
								elseif ($noteQ_J_1 == "2"){echo "<td class='lettreRouge'>Q<font style=\"font-size:12px;\">ualité</font></td>";}
								else{echo "<td class='lettre'>Q<font style=\"font-size:12px;\">ualité</font></td>";}
								
								if ($noteC_J_1 == "1"){echo "<td class='lettreVerte'>C<font style=\"font-size:12px;\">oûts</font></td>";}
								elseif ($noteC_J_1 == "2"){echo "<td class='lettreRouge'>C<font style=\"font-size:12px;\">oûts</font></td>";}
								else{echo "<td class='lettre'>C<font style=\"font-size:12px;\">oûts</font></td>";}
								
								if ($noteD_J_1 == "1"){echo "<td class='lettreVerte'>D<font style=\"font-size:12px;\">élais</font></td>";}
								elseif ($noteD_J_1 == "2"){echo "<td class='lettreRouge'>D<font style=\"font-size:12px;\">élais</font></td>";}
								else{echo "<td class='lettre'>D<font style=\"font-size:12px;\">élais</font></td>";}
								
								if ($noteP_J_1 == "1"){echo "<td class='lettreVerte'>P<font style=\"font-size:12px;\">ersonnel</font></td>";}
								elseif ($noteP_J_1 == "2"){echo "<td class='lettreRouge'>P<font style=\"font-size:12px;\">ersonnel</font></td>";}
								else{echo "<td class='lettre'>P<font style=\"font-size:12px;\">ersonnel</font></td>";}
								
								if ($noteF_J_1 == "1"){echo "<td class='lettreVerte'>F<font style=\"font-size:12px;\">ormation</font></td>";}
								elseif ($noteF_J_1 == "2"){echo "<td class='lettreRouge'>F<font style=\"font-size:12px;\">ormation</font></td>";}
								else{echo "<td class='lettre'>F<font style=\"font-size:12px;\">ormation</font></td>";}
							?>
						</tr>
						<tr align="center">
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8">Adéquation charge capacité</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8">Respect OQD</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8">Difficultés réalisation</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8">Niveau de formation du personnel</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8">Respect des objectifs de délais et de «quantité»</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8">Sécurité du personnel</td>
						</tr>
						<tr align="center">
							<td style="border:1px #d9d9d7 solid;"><textarea class="CommentaireLettre" readonly="readonly" name="CommentaireS_J_1" rows="0" cols="0"><?php echo $CommentaireS_J_1; ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;"><textarea class="CommentaireLettre" readonly="readonly" name="CommentaireQ_J_1" rows="0" cols="0"><?php echo $CommentaireQ_J_1; ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;"><textarea class="CommentaireLettre" readonly="readonly" name="CommentaireC_J_1" rows="0" cols="0"><?php echo $CommentaireC_J_1; ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;"><textarea class="CommentaireLettre" readonly="readonly" name="CommentaireD_J_1" rows="0" cols="0"><?php echo $CommentaireD_J_1; ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;"><textarea class="CommentaireLettre" readonly="readonly" name="CommentaireP_J_1" rows="0" cols="0"><?php echo $CommentaireP_J_1; ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;"><textarea class="CommentaireLettre" readonly="readonly" name="CommentaireF_J_1" rows="0" cols="0"><?php echo $CommentaireF_J_1 ;?></textarea></td>
						</tr>
					</table>
				</td></tr>
				<tr><td height="4"/></tr>
				<tr>
					<td class="TitreSousPagePERFOS" >Points chauds J-J/actions SQCDPF</td>
				</tr>
				<tr><td>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr bgcolor="#bacfea">
							<td class="SousenTetePERFOS">Niveau</td>
							<td class="SousenTetePERFOS">Lettre</td>
							<td class="SousenTetePERFOS">Description du problème (Point chaud, Point SQCDPF)</td>
							<td class="SousenTetePERFOS">Commentaire</td>
							<td class="SousenTetePERFOS">Description action</td>
							<td class="SousenTetePERFOS">Acteur</td>
							<td class="SousenTetePERFOS">Délai</td>
						</tr>
						<?php
							$req="SELECT new_action.Lettre, new_action.Probleme, new_action.Action, new_action.Delais, new_action.Niveau, ";
							$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_action.Id_Acteur) AS Responsable, new_action.Commentaire ";
							$req.="FROM new_action WHERE new_action.DateCreation='".$row['DateSQCDPF']."' AND Vacation='".$row['Vacation']."' AND new_action.Id_Prestation=".$row['Id_Prestation']." AND new_action.Id_Pole=".$row['Id_Pole']."";
							$resulAction=mysqli_query($bdd,$req);
							$nbAction=mysqli_num_rows($resulAction);
							if ($nbAction>0){
								$couleur = "#eef3fa";
								while($rowAction=mysqli_fetch_array($resulAction)){
									if ($couleur == "#eef3fa"){$couleur = "#ffffff";}
									else{$couleur = "#eef3fa";}
									echo "<tr bgcolor='".$couleur."'>";
									echo "<td align='center'>".$rowAction['Niveau']."</td>";
									echo "<td align='center'>".$rowAction['Lettre']."</td>";
									echo "<td align='center'>".$rowAction['Probleme']."</td>";
									echo "<td align='center'>".$rowAction['Commentaire']."</td>";
									echo "<td align='center'>".$rowAction['Action']."</td>";
									echo "<td align='center'>".$rowAction['Responsable']."</td>";
									if($rowAction['Delais'] > "0001-01-01"){
										echo "<td align='center'>".AfficheDateJJ_MM_AAAA($rowAction['Delais'])."</td>";
									}
									else{
										echo "<td align='center'></td>";
									}
									echo "</tr>";
								}
							}
						?>
					</table>
				</td></tr>
			</table>
		</td></tr>
	</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>