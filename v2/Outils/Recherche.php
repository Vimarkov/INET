<?php
require("../Menu.php");
include("../../PdfToText/PdfToText.phpclass");
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr><td class="TitrePage">Résultats de la recherche</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table class="GeneralInfo" style="border-spacing:0; align:center; width:100%;">
<?php
	$nbresultatstotal=0;
	$file_types=array('.doc','.docx','.xlsx','.xls','.ppt','.pptx','.pdf','.txt','.pps','.ppsx');
	
	//Transformation des mots en tableau de mots
	$MOTS="";
	if(isset($_GET['mots'])){$MOTS=stripslashes($_GET['mots']);}
	elseif(isset($_POST['mots'])){$MOTS=stripslashes($_POST['mots']);}
	preg_match_all('@["][^"]+["]|[\S^"]+@',$MOTS,$TableauMots);
	$TableauMots=str_replace('"','',$TableauMots[0]);
	foreach($TableauMots as $k=>$v){$TableauMots[$k]=addslashes($v);}
	
	
	$Requete_NomDossier="SELECT	Libelle,NomTable FROM dossiers_general";
	$Result_NomDossier=mysqli_query($bdd,$Requete_NomDossier);
	$nbResultaDossier=mysqli_num_rows($Result_NomDossier);
	
	
	$NbResultatsTotal=0;
	if($nbResultaDossier>0){
		while($Row_Dossier=mysqli_fetch_array($Result_NomDossier))
		{
			
			$NbResultats=0;
			$Nom_Dossier=$Row_Dossier['Libelle'];
			$Dossier=$Row_Dossier['NomTable'];
			$TrouveDansDossier=false;
			
			$Requete_Info="SELECT Id, Titre, Contenu, Dossier1, Dossier2, NomFichier, Fichier FROM "."new_".$Dossier." ";
			$Result_Info=mysqli_query($bdd,$Requete_Info);
			$nbResultaInfo=mysqli_num_rows($Result_Info);
			if($nbResultaInfo>0){
				while($Row_Info=mysqli_fetch_array($Result_Info))
				{
					
					if(Droits_PersonneConnectee_PageExtranet($Dossier,$Row_Info['Dossier1'],$Row_Info['Dossier2']) != "Aucun")
					{
						
						$TotaliteInformationALire=$Nom_Dossier."#".$Row_Info['Dossier1']."#".$Row_Info['Dossier2']."#".$Row_Info['Contenu']."#".$Row_Info['Fichier']."#".$Row_Info['NomFichier'];
						
						for($k=0;$k<sizeof($TableauMots);$k++)
						{
							$TableauMots[$k]=strtr($TableauMots[$k], "@àäâöôéèëêîïù'", "aaaaooeeeeiiu_");
							if(stripos($TotaliteInformationALire,$TableauMots[$k]) !== false)
							{
								if(!$TrouveDansDossier){echo "<tr><td class='TitrePage'>".$Nom_Dossier." # ".$Row_Info['Dossier1']." # ".$Row_Info['Dossier2']."</td></tr>";}
								$TrouveDansDossier=true;
								$NbResultats++;
								$NbResultatsTotal++;
								echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<a class='Info' href='../ListeDocs.php?Page=".$Dossier."&Dossier1=".$Row_Info['Dossier1']."&Dossier2=".$Row_Info['Dossier2']."#".$Row_Info['Id']."'>";
								echo ">> ".stripslashes($Row_Info['Titre'])."</a></td></tr>";
							}
						}
					}
				}
			}
			if($NbResultats>0){echo "<tr height='15'><td></td></tr>";}
		}
	}
	
	if($NbResultatsTotal==0){echo "<tr><td>Aucun résultat pour cette recherche.</tr></td>";}
?>
			</table>
		</td>
	</tr>
</table>

<?php
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>