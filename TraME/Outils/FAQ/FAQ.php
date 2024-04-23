<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_FAQ.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_FAQ.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
			w.focus();
			}
		function OuvreFenetreVisualiser(Id){
			var w=window.open("Ajout_FAQ.php?Mode=V&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=800,height=250");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer les accès?')){
				var w=window.open("Ajout_FAQ.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
				}
			}
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereFAQ.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=200");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereFAQ.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=200");
			w.focus();
		}
		function Excel(){
			var w=window.open("Extract_FAQ.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");w.focus();}
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

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

$_SESSION['Formulaire']="FAQ/FAQ.php";

if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['CategorieFAQ']="";
		$_SESSION['QuestionFAQ']="";
		$_SESSION['ReponseFAQ']="";
		
		$_SESSION['CategorieFAQ2']="";
		$_SESSION['QuestionFAQ2']="";
		$_SESSION['ReponseFAQ2']="";
		
		$_SESSION['ModeFiltreFAQ']="";
		$_SESSION['PageFAQ']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['ModeFiltreFAQ']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriCategorieFAQ']="";
		$_SESSION['TriQuestionFAQ']="";
		$_SESSION['TriReponseFAQ']="";
		$_SESSION['TriGeneralFAQ']="";
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="FAQ.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Questions";}else{echo "FAQ";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td><b>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Search criteria";}else{echo "Critères de recherche";} ?> : </b></td>
			<td align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter un critère";} ?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter un critère";} ?>">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			if($_SESSION['CategorieFAQ']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Categories : ".$_SESSION['CategorieFAQ']."</td>";
				}
				else{
					echo "<td>&nbsp; Catégories : ".$_SESSION['CategorieFAQ']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['QuestionFAQ']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Questions : ".$_SESSION['QuestionFAQ']."</td>";
				}
				else{
					echo "<td>&nbsp; Questions : ".$_SESSION['QuestionFAQ']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ReponseFAQ']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Answers : ".$_SESSION['ReponseFAQ']."</td>";
				}
				else{
					echo "<td>&nbsp; Réponses : ".$_SESSION['ReponseFAQ']."</td>";
				}
				echo "</tr>";
			}
		?>
		<tr>
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";} ?>">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";} ?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Tri_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Delete sorting";}else{echo "Effacer les tris";} ?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" colspan="6">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a question";}else{echo "Ajouter une question";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		if(isset($_GET['Tri'])){
			if($_GET['Tri']=="Categorie"){
				$_SESSION['TriGeneralFAQ']= str_replace("Categorie ASC,","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Categorie DESC,","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Categorie ASC","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Categorie DESC","",$_SESSION['TriGeneralFAQ']);
				if($_SESSION['TriCategorieFAQ']==""){$_SESSION['TriCategorieFAQ']="ASC";$_SESSION['TriGeneralFAQ'].= "Categorie ".$_SESSION['TriCategorieFAQ'].",";}
				elseif($_SESSION['TriCategorieFAQ']=="ASC"){$_SESSION['TriCategorieFAQ']="DESC";$_SESSION['TriGeneralFAQ'].= "Categorie ".$_SESSION['TriCategorieFAQ'].",";}
				else{$_SESSION['TriCategorieFAQ']="";}
			}
			if($_GET['Tri']=="Question"){
				$_SESSION['TriGeneralFAQ']= str_replace("Question ASC,","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Question DESC,","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Question ASC","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Question DESC","",$_SESSION['TriGeneralFAQ']);
				if($_SESSION['TriQuestionFAQ']==""){$_SESSION['TriQuestionFAQ']="ASC";$_SESSION['TriGeneralFAQ'].= "Question ".$_SESSION['TriQuestionFAQ'].",";}
				elseif($_SESSION['TriQuestionFAQ']=="ASC"){$_SESSION['TriQuestionFAQ']="DESC";$_SESSION['TriGeneralFAQ'].= "Question ".$_SESSION['TriQuestionFAQ'].",";}
				else{$_SESSION['TriQuestionFAQ']="";}
			}
			if($_GET['Tri']=="Reponse"){
				$_SESSION['TriGeneralFAQ']= str_replace("Reponse ASC,","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Reponse DESC,","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Reponse ASC","",$_SESSION['TriGeneralFAQ']);
				$_SESSION['TriGeneralFAQ']= str_replace("Reponse DESC","",$_SESSION['TriGeneralFAQ']);
				if($_SESSION['TriReponseFAQ']==""){$_SESSION['TriReponseFAQ']="ASC";$_SESSION['TriGeneralFAQ'].= "Reponse ".$_SESSION['TriReponseFAQ'].",";}
				elseif($_SESSION['TriReponseFAQ']=="ASC"){$_SESSION['TriReponseFAQ']="DESC";$_SESSION['TriGeneralFAQ'].= "Reponse ".$_SESSION['TriReponseFAQ'].",";}
				else{$_SESSION['TriReponseFAQ']="";}
			}
		}
		if($_SESSION['ModeFiltreFAQ']=="oui"){
			$reqAnalyse="SELECT trame_faq.Id ";
			$req2="SELECT Id,(SELECT Libelle FROM trame_categorie_faq WHERE trame_categorie_faq.Id=trame_faq.Id_Categorie) AS Categorie,";
			$req2.="Question,Reponse ";
			$req="FROM trame_faq WHERE ";
			if($_SESSION['CategorieFAQ2']<>""){
				$tab = explode(";",$_SESSION['CategorieFAQ2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Categorie=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['QuestionFAQ2']<>""){
				$tab = explode(";",$_SESSION['QuestionFAQ2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Question LIKE '%".$valeur."%' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['ReponseFAQ2']<>""){
				$tab = explode(";",$_SESSION['ReponseFAQ2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Reponse LIKE '%".$valeur."%' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
			if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
			
			$result=mysqli_query($bdd,$reqAnalyse.$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($_SESSION['TriGeneralFAQ']<>""){
				$req.="ORDER BY ".substr($_SESSION['TriGeneralFAQ'],0,-1);
			}

			$nombreDePages=ceil($nbResulta/100);
			if(isset($_GET['Page'])){$_SESSION['PageFAQ']=$_GET['Page'];}
			else{$_SESSION['PageFAQ']=0;}
			$req3=" LIMIT ".($_SESSION['PageFAQ']*100).",100";
			
			$result=mysqli_query($bdd,$req2.$req.$req3);
			$nbResulta=mysqli_num_rows($result);
		}
	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				if($_SESSION['ModeFiltreFAQ']=="oui"){
					$nbPage=0;
					if($_SESSION['PageFAQ']>1){echo "<b> <a style='color:#00599f;' href='FAQ.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['PageFAQ']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['PageFAQ']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['PageFAQ']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['PageFAQ']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='FAQ.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['PageFAQ']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='FAQ.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				}
			?>
		</td>
	</tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="10%" ><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="FAQ.php?Tri=Categorie">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Catégorie";} ?><?php if($_SESSION['TriCategorieFAQ']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCategorieFAQ']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="40%" ><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="FAQ.php?Tri=Question">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Question";}else{echo "Question";} ?><?php if($_SESSION['TriQuestionFAQ']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriQuestionFAQ']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="40%" ><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="FAQ.php?Tri=Reponse">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Answer";}else{echo "Réponse";} ?><?php if($_SESSION['TriReponseFAQ']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriReponseFAQ']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<?php if(substr($_SESSION['DroitTR'],5,1)=='1'){
				?>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<?php
					}
				?>
			</tr>
			<?php
				if($_SESSION['ModeFiltreFAQ']=="oui"){
					if ($nbResulta>0){
						$couleur="#ffffff";
						while($row=mysqli_fetch_array($result)){
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="10%">&nbsp;<?php echo $row['Categorie'];?></td>
								<td width="40%">&nbsp;<?php echo nl2br($row['Question']);?></td>
								<td width="40%">&nbsp;<?php echo nl2br($row['Reponse']);?></td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreVisualiser(<?php echo $row['Id']; ?>)">
									<img style="width:19px;height:19px;" src='../../Images/Loupe.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Display";}else{echo "Visualiser";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
									</a>
								</td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
									</a>
								</td>
								<?php if(substr($_SESSION['DroitTR'],5,1)=='1'){
								?>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
										<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
									</a>
								</td>
								<?php
									}
								?>
							</tr>
							<?php
							if($couleur=="#ffffff"){$couleur="#E1E1D7";}
							else{$couleur="#ffffff";}
						}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>