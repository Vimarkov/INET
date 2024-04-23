<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/calendar/jquery-1.3.2.min.js"></script>
	<script language="javascript">
		function OuvreFenetreAjout(IdVersion,Id_CL,New){
			var w=window.open("Ajout_Checklist.php?Mode=A&Id=0&IdVersion="+IdVersion+"&Id_CL="+Id_CL+"&New="+New,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=760,height=200");
			w.focus();
			}
		function OuvreFenetreModif(Id,IdVersion,Id_CL,New){
			var w=window.open("Ajout_Checklist.php?Mode=M&Id="+Id+"&IdVersion="+IdVersion+"&Id_CL="+Id_CL+"&New="+New,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=760,height=200");
			w.focus();
			}
		function OuvreFenetreSuppr(Id,IdVersion,Id_CL,New){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_Checklist.php?Mode=S&Id="+Id+"&IdVersion="+IdVersion+"&Id_CL="+Id_CL+"&New="+New,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
		function Excel(CL,Version){
			var w=window.open("Extract_CL.php?CL="+CL+"&Version="+Version,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function afficherIMG(img){
			var w=open("",'image','weigth=toolbar=no,scrollbars=no,resizable=yes, width=810, height=310');	
			w.document.write("<HTML><BODY onblur=\"window.close();\"><IMG src='ImagesChecklist/"+img+"'>");
			w.document.write("</BODY></HTML>");
			w.focus();
			w.document.close();
		}
		function Up(Id,IdVersion,Id_CL){
			var tr=document.getElementById('tr_'+Id);
			tbody=document.getElementById('test');
			trs=tbody.getElementsByTagName('tr');
			count=trs.length;
			i=0;
			found=false;
			while(i<count && !found){
				if(trs[i]==tr){
					found=true;
				}else{
					i++;
				}
			}
			
			//Echanger les couleurs
			j=0;
			found=false;
			while(j<count && !found){
				if(j==(i-1)){
					var color=tr.style.backgroundColor;
					tr.style.backgroundColor=trs[j].style.backgroundColor;
					trs[j].style.backgroundColor=color;
					$.ajax({
						url : 'EchangerOrdre.php',
						data : 'Id1='+tr.id.substr(3)+'&Id2='+trs[j].id.substr(3),
					});
					found=true;
				}else{
					j++;
				}
			}
			
			tr2=tbody.insertRow(i-1);
			tbody.replaceChild(tr,tr2);
		}
		function Down(Id,IdVersion,Id_CL){
			var tr=document.getElementById('tr_'+Id);
			tbody=document.getElementById('test');
			trs=tbody.getElementsByTagName('tr');
			count=trs.length;
			i=0;
			found=false;
			while(i<count && !found){
				if(trs[i]==tr){
					found=true;
				}else{
					i++;
				}
			}
			//Echanger les couleurs
			j=0;
			found=false;
			while(j<count && !found){
				if(j==(i+1)){
					var color=tr.style.backgroundColor;
					tr.style.backgroundColor=trs[j].style.backgroundColor;
					trs[j].style.backgroundColor=color;
					$.ajax({
						url : 'EchangerOrdre.php',
						data : 'Id1='+tr.id.substr(3)+'&Id2='+trs[j].id.substr(3),
					});
					found=true;
				}else{
					j++;
				}
			}
			
			tr2=tbody.insertRow(i+2);
			tbody.replaceChild(tr,tr2);
		}
	</script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script type="text/javascript">
		$(function(){
			$(window).scroll(
				function () {//Au scroll dans la fenetre on d?clenche la fonction
					if ($(this).scrollTop() > 1) { //si on a d?fil? de plus de 150px du haut vers le bas
						$('#navigation').addClass("fixNavigation"); //on ajoute la classe "fixNavigation" ? <div id="navigation">
					} else {
						$('#navigation').removeClass("fixNavigation");//sinon on retire la classe "fixNavigation" ? <div id="navigation">
					}
				}
			);			 
		});
	</script>
</head>
<?php
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Production/Checklist.php";

$IdEC=0;
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Checklist.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Check-List";}else{echo "Check-List";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td><input type="hidden" name="IdNewIndice" id="IdNewIndice" value="<?php if(isset($_GET['New'])){echo $_GET['New'];}else{echo 0;}?>" /></td></tr>
	<tr>
		<td align="left">
			<table align="left" class="GeneralInfo" style="width:100%;">
				<tr>
					<td width="8%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Check-list";}else{echo "Check-list";} ?></td>
					<td align="left">
						<select id="checklist" name="checklist" style="width:150px;" onchange="submit()">
						<?php
							
							$numDQ="";
							echo"<option name='0' value='0'></option>";
							$req="SELECT Id, Libelle,NumDQ FROM trame_checklist WHERE Supprime=false AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							$CL=0;
							$new=0;
							if(isset($_GET['New'])){
								$new=$_GET['New'];
							}
							if ($nbResulta>0){
								$i=0;
								while($rowCL=mysqli_fetch_array($result)){
									$selected="";
									if($_POST){
										if($_POST['checklist']==$rowCL['Id']){
											$CL=$rowCL['Id'];
											$numDQ=$rowCL['NumDQ'];
											$selected="selected";
										}
									}
									elseif(isset($_GET['Id'])){
										if($_GET['Id']==$rowCL['Id']){
											$CL=$rowCL['Id'];
											$numDQ=$rowCL['NumDQ'];
											$selected="selected";
										}
									}
									echo "<option value='".$rowCL['Id']."' ".$selected.">".$rowCL['Libelle']."</option>";
									$i+=1;
								}
							}
						?>	
						</select>
						<?php
							$dateCL="";
							$numVersion="";
							$personne="";
							$Id_Version=0;
							if($CL>0){
								$req="SELECT Id, NumVersion,DateCL, ";
								$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=trame_cl_version.Id_Personne) AS Personne ";
								$req.="FROM trame_cl_version WHERE Id_CL=".$CL." AND Valide=1 ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$row=mysqli_fetch_array($result);
									$dateCL=AfficheDateFR($row['DateCL']);
									$numVersion=$row['NumVersion'];
									$personne=$row['Personne'];
									$Id_Version=$row['Id'];
								}
							}
						?>
					</td>
					<td class="Libelle">&nbsp; <?php if($CL>0){ ?><?php if($_SESSION['Langue']=="EN"){echo "Index";}else{echo "Indice";}?> : <?php echo $numVersion; ?><?php } ?></td>
					<td class="Libelle">&nbsp; <?php if($CL>0){ ?><?php if($_SESSION['Langue']=="EN"){echo "Creator";}else{echo "Auteur";}?>  : <?php echo $personne; ?><?php } ?></td>
					<td class="Libelle">&nbsp; <?php if($CL>0){ ?><?php if($_SESSION['Langue']=="EN"){echo "Creation date";}else{echo "Date création";}?>  : <?php echo $dateCL; ?><?php } ?></td>
					<td class="Libelle">&nbsp; <?php if($CL>0){ ?><?php if($_SESSION['Langue']=="EN"){echo "N° D-0833";}else{echo "N° D-0833";}?>  : <?php echo $numDQ; ?><?php } ?> </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<?php
					if($CL>0){
				?>
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout(<?php echo $Id_Version;?>,<?php echo $CL; ?>,<?php echo $new; ?>)'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add control";}else{echo "Ajouter un contrôle";} ?>&nbsp;</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a style="text-decoration:none;" class="Bouton" href="javascript:Excel(<?php echo $CL;?>,<?php echo $Id_Version;?>)">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
				<?php
					}
				?>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<thead>
				<tr bgcolor="#00325F">
					<td class="EnTeteTableauCompetences" width="3%"></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Chapter";}else{echo "Chapitre";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Weighting";}else{echo "Pondération";} ?></td>
					<td class="EnTeteTableauCompetences" width="60%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Control";}else{echo "Contrôle";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Picture";}else{echo "Image";} ?></td>
					<td class="EnTeteTableauCompetences" width="2%"></td>
					<td class="EnTeteTableauCompetences" width="2%"></td>
				</tr>
			</thead>
			<tbody id="test">
			<?php
				$req="SELECT Id,Chapitre,Ponderation,Controle,Photo ";
				$req.="FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Id_Version." ORDER BY Ordre;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				
				if ($nbResulta>0){
					$couleur="#E1E1D7";
					$nb=1;
					while($row=mysqli_fetch_array($result)){
						?>
							<tr id="tr_<?php echo $row['Id']; ?>" style="background-color:<?php echo $couleur; ?>;">
								<td width="3%" align="center">
									<a href="javascript:Up(<?php echo $row['Id']; ?>,<?php echo $Id_Version;?>,<?php echo $CL; ?>)">
									<img id="Haut" src='../../Images/haut_Gris.png' width="13px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Up";}else{echo "Monter";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Up";}else{echo "Monter";} ?>'
									onmouseover="this.src='../../Images/haut.png'" onmouseout="this.src='../../Images/haut_Gris.png'">
									</a></br>
									<a href="javascript:Down(<?php echo $row['Id']; ?>,<?php echo $Id_Version;?>,<?php echo $CL; ?>)">
									<img id="Bas" src='../../Images/bas_Gris.png' width="13px" border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Down";}else{echo "Descendre";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Down";}else{echo "Descendre";} ?>'
									onmouseover="this.src='../../Images/bas.png'" onmouseout="this.src='../../Images/bas_Gris.png'">
									</a>
								</td>
								<td width="15%">&nbsp;<?php echo stripslashes(str_replace("\\","",$row['Chapitre']));?></td>
								<td width="6%"><?php echo $row['Ponderation'];?></td>
								<td width="60%"><?php echo stripslashes(str_replace("\\","",$row['Controle']));?></td>
								<td width="5%" align="center">
								<?php 
									if($row['Photo']<>""){
										echo "<img onclick=\"afficherIMG('".$row['Photo']."')\" src='../../Images/image.png' border='0'>";
									}
								?>
								</td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $Id_Version;?>,<?php echo $CL; ?>,<?php echo $new; ?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
									</a>
								</td>
								<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $Id_Version;?>,<?php echo $CL; ?>,<?php echo $new; ?>)">
								<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
								</td>
							</tr>
						<?php
						$nb++;
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
					}
				}
			?>
			</tbody>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
}
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>