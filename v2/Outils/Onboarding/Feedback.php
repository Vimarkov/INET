<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery-3.4.0.min.js"></script>	
	<script type="text/javascript">
		function VerifChamps(Mode)
		{
			if(formulaire.noteTemps.value=='0' || formulaire.noteFacilite.value=='0' || formulaire.commentaire.value==''){alert('Vous n\'avez pas renseigné toutes les informations.');return false;}
			return true;
		}

		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		
		$(function(){
			// NOTE 1
			//on détecte la présence de la souris sur une étoile
			$(".tde").mouseover(function(){
				//Grâce à substring(), on récupère le numéro dans l'id de cette étoile et on la stocke dans une variable en ayant supprimé le préfixe "tde_", bonnes pratiques du HTML !
				var nbr = $(this).prop('id').substring(4);
				
				
				//on impose la couleur jaune dans le fond transparent de cette étoile
				$(this).css( "backgroundColor", "#E0E001" );

				//et en même temps, on met toutes les étoiles en-dessous de nbr en jaune.
				 $( ".tde").slice(0, nbr).css( "backgroundColor", "#E0E001" );

				//et toutes celles au-dessus de nbr en gris
				 $( ".tde").slice(nbr).css( "backgroundColor", "#A1A1A1" );
			 })

			//et quand la souris s'en va, on annule le fond jaune sous les étoiles pour garder uniquement celui de #value 
			$("#glob").mouseout(function(){
				$(".tde").css('backgroundColor', "" );
			})
			
			//au clic sur une étoile, on enregistre la note dans value
			$(".tde").click(function(){
				var nbr = $(this).prop('id').substring(4);
				document.getElementById("noteTemps").value=nbr;
				lewidth=nbr*20;
				document.getElementById("value").style.width=lewidth;
			})
			
			//NOTE 2
			//on détecte la présence de la souris sur une étoile
			$(".tdf").mouseover(function(){
				//Grâce à substring(), on récupère le numéro dans l'id de cette étoile et on la stocke dans une variable en ayant supprimé le préfixe "tde_", bonnes pratiques du HTML !
				var nbr = $(this).prop('id').substring(4);
				
				
				//on impose la couleur jaune dans le fond transparent de cette étoile
				$(this).css( "backgroundColor", "#E0E001" );

				//et en même temps, on met toutes les étoiles en-dessous de nbr en jaune.
				 $( ".tdf").slice(0, nbr).css( "backgroundColor", "#E0E001" );

				//et toutes celles au-dessus de nbr en gris
				 $( ".tdf").slice(nbr).css( "backgroundColor", "#A1A1A1" );
			 })

			//et quand la souris s'en va, on annule le fond jaune sous les étoiles pour garder uniquement celui de #value 
			$("#glob2").mouseout(function(){
				$(".tdf").css('backgroundColor', "" );
			})
			
			//au clic sur une étoile, on enregistre la note dans value
			$(".tdf").click(function(){
				var nbr = $(this).prop('id').substring(4);
				document.getElementById("noteFacilite").value=nbr;
				lewidth=nbr*20;
				document.getElementById("value2").style.width=lewidth;
			})
		});
	</script>	
</head>
<body>
<?php
session_start();
require("../Connexioni.php");

function SousTitre($Libelle){
	$couleurTexte="#000000";
	echo "<td colspan='2' style=\"width:33%;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-size:18px;font-weight:bold;\">".$Libelle."</td>\n";
}

$dateDuJour = date("Y-m-d");

if($_POST)
{
	$req="SELECT Id FROM onboarding_feedback WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb==0){
		//INSERT
		$req="INSERT INTO onboarding_feedback (Id_Personne,NoteTemps,NoteFacilite,Commentaire,DateFeedback)
			VALUES (".$_SESSION['Id_Personne'].",".$_POST['noteTemps'].",".$_POST['noteFacilite'].",'".addslashes($_POST['commentaire'])."','".date('Y-m-d')."')";
	}
	else{
		//UPDATE
		$req="UPDATE onboarding_feedback 
			SET
				NoteTemps=".$_POST['noteTemps'].",
				NoteFacilite=".$_POST['noteFacilite'].",
				Commentaire='".addslashes($_POST['commentaire'])."',
				DateFeedback='".date('Y-m-d')."'
			WHERE 
				Id_Personne=".$_SESSION['Id_Personne']."";
	}

	$result=mysqli_query($bdd,$req);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$req="SELECT NoteTemps,NoteFacilite,Commentaire,DateFeedback FROM onboarding_feedback WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if($nb>0){
		$row=mysqli_fetch_array($result);
	}
?>
	<form id="formulaire" method="POST" enctype="multipart/form-data" action="Feedback.php" onSubmit="return VerifChamps('<?php echo $_GET['Mode']; ?>');">
	<table style="width:95%; border-spacing:0; align:center;" class="TableCompetences">
		<tr><td height="10"></td></tr>
		<tr>
			<?php if($_SESSION['Langue']=="FR"){echo SousTitre("Comment évaluez-vous cet espace accueil ?");}else{echo SousTitre("How do you rate this home page ?");}?> 
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Temps passé vs. utilité des informations présentées";}else{echo "Time spent vs. usefulness of the information presented";}?> : </td>
			<td>
				<?php 
				$value1=0;
				if($nb>0){
					$value1=$row['NoteTemps']*20;
				}
				?>
				<!--div optionnelle pour contenir le tout-->
				<div style="float:left;width:100px;"> 

				<!--div en arrière-plan qui s'allongera en fonction de la valeur de $value1-->
				  <div id="value">

				<!--div qui contient les étoiles-->
					<div id="glob" >
					  <img id="tde_1" src="../../Images/star.png" class="tde"/>
					  <img id="tde_2" src="../../Images/star.png" class="tde"/>
					  <img id="tde_3" src="../../Images/star.png" class="tde"/>
					  <img id="tde_4" src="../../Images/star.png" class="tde"/>
					  <img id="tde_5" src="../../Images/star.png" class="tde"/>    
					</div>
				  </div>
				</div>
				<input type="hidden" name="noteTemps" id="noteTemps" value="<?php echo $value1/20;?>" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Facilité de navigation dans l’espace accueil";}else{echo "Ease of navigation in the home page";}?> : </td>
			<td>
				<?php 
					$value2=0;
					if($nb>0){
						$value2=$row['NoteFacilite']*20;
					}
				?>
				<!--div optionnelle pour contenir le tout-->
				<div style="float:left;width:100px;"> 

				<!--div en arrière-plan qui s'allongera en fonction de la valeur de $value1-->
				  <div id="value2">

				<!--div qui contient les étoiles-->
					<div id="glob2" >
					  <img id="tdf_1" src="../../Images/star.png" class="tdf"/>
					  <img id="tdf_2" src="../../Images/star.png" class="tdf"/>
					  <img id="tdf_3" src="../../Images/star.png" class="tdf"/>
					  <img id="tdf_4" src="../../Images/star.png" class="tdf"/>
					  <img id="tdf_5" src="../../Images/star.png" class="tdf"/>    
					</div>
				  </div>
				</div>
				<input type="hidden" name="noteFacilite" id="noteFacilite" value="<?php echo $value2/20;?>" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Commentaire ";}else{echo "Comment ";} ?> : </td>
			<td>
				<textarea name="commentaire" cols='50' rows='4' style="resize: none;"><?php if($nb>0){echo stripslashes($row['Commentaire']);}?></textarea>
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		<tr>
			<td colspan="4" align="center">
				<input class="Bouton" type="submit" 
				<?php
					if($_GET['Mode']=="M"){
						if($_SESSION['Langue']=="FR"){echo "value='Modifier'";}
						else{echo "value='Edit'";}
					}
					else{
						if($_SESSION['Langue']=="FR"){echo "value='Envoyer'";}
						else{echo "value='Send'";}
					}
				?>
				>
			</td>
		</tr>
	</table><br>
	</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
<style>
	/*code CSS */
	.tde {height:20px;width:20px;cursor:pointer;}
	.tdf {height:20px;width:20px;cursor:pointer;}
	#value {height:20px; width: <?=$value1;?>px; background:#E0E001;}
	#value2 {height:20px; width: <?=$value2;?>px; background:#E0E001;}
	#glob {display: flex;}
	#glob2 {display: flex;}
</style>
</body>
</html>