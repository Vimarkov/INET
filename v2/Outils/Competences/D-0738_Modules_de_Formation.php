
<!DOCTYPE html>
<html>
<head>
	<title>D-0738 Modules de formation</title><meta name="robots" content="noindex">
	<script language="javascript">
		function OuvrirFichier(Fic)
			{window.open("../../../Qualite/DQ/4/DQ413/Modules_de_formation/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
		
		function Voir_TR(Name,Nb_Lignes)
		{
			table = document.getElementById('TABLE_FORMATION').getElementsByTagName('TR')
			for (l=0;l<table.length+1;l++)
			{
				if(table[l].getAttribute("name")==Name)
				{
					for (m=l+1;m<l+Nb_Lignes+1;m++)
					{
						if(table[m].style.display == ''){table[m].style.display = 'none';}
						else{table[m].style.display = '';}
					}
				}
			}
		}
		
		function Masquer_Tout()
		{
			table = document.getElementById('TABLE_FORMATION').getElementsByTagName('TR')
			for (l=6;l<table.length+1;l++)
			{
				if(table[l].getAttribute("name")==null){table[l].style.display = 'none';}
				else{table[l].style.display = '';}
			}
		}
	</script>
</head>
<?php
	session_start();
	require_once("../Formation/Globales_Fonctions.php");
	require("../Connexioni.php");
	require_once("../Fonctions.php");


if(isset($_SESSION['Id_Personne'])){
	
	$QCM=false;
	if(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteFormateur,$IdPosteReferentQualiteProcedesSpeciaux))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
	)
	{
		$QCM=true;
	}
?>
<font face="Calibri">
<div id="TABLE_FORMATION">
<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" border="1" bordercolor="#000000">
	<tr>
		<td align="center"><img src="../../../v2/Images/Logos/Logo Daher_posi.png" border="0" width="150px" ></td>
		<td align="center" bgcolor="#DDDDDD"><font size="6" color="#330099"><b>DOCUMENTS APPLICABLES</b></font></td>
		<td align="center" colspan="2"><b>Toute entité</b></td>
	</tr>
	<tr>
		<td width="170"><b>Mis a jour : 30/11/2023</td>
		<td width="500" align="center"><b>par : Emmanuelle HAUTEM</td>
		<td width="100" align="center"><b>Visa :</b></td>
		<td width="100" align="center"><b>EHM</b></td>
	</tr>
	<tr>
		<td colspan="4" height="5">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" bgcolor="#DDDDDD"><b>FAMILLE :</b></td>
		<td align="center"><font size="5"><b>MODULES DE FORMATION</b></font></td>
		<td align="center" bgcolor="#DDDDDD" colspan="4"><b>Origine : INTERNE</b></td>
	</tr>
	<tr>
		<td colspan="4" height="5">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" bgcolor="#DDDDDD"><b>REFERENCE</b></td>
		<td align="center" bgcolor="#DDDDDD"><b>INTITULE</b></td>
		<td align="center" bgcolor="#DDDDDD"><b>INDICE</b></td>
		<td align="center" bgcolor="#DDDDDD"><b>DATE</b></td>
	</tr>
	<?php 
	$req="SELECT 
			DISTINCT Id_Categorie, moduleformation_categorie.Libelle AS Categorie 
		FROM moduleformation_formation 
		LEFT JOIN moduleformation_categorie
		ON moduleformation_formation.Id_Categorie=moduleformation_categorie.Id
		WHERE moduleformation_formation.Suppr=0 
		AND moduleformation_formation.Id_Formation=0 
		ORDER BY moduleformation_categorie.Ordre ASC";
	$resultCategorie=mysqli_query($bdd,$req);
	$nbCategorie=mysqli_num_rows($resultCategorie);
	if($nbCategorie>0)
	{
		while($rowCategorie=mysqli_fetch_array($resultCategorie))
		{
	?>
	<tr name="COURS_METIER">
		<td colspan="4" align="center" bgcolor="#DDDDDD"><b><?php echo stripslashes($rowCategorie['Categorie']); ?></b></td>
	</tr>
	<?php
			$resultForm=mysqli_query($bdd,"SELECT Id,Reference,Intitule FROM moduleformation_formation WHERE Suppr=0 AND Id_Formation=0 AND Id_Categorie=".$rowCategorie['Id_Categorie']." ORDER BY Reference");
			$nbForm=mysqli_num_rows($resultForm);
			if($nbForm>0)
			{
				while($rowForm=mysqli_fetch_array($resultForm))
				{
					$reqDoc="SELECT Id, Id_Formation, Reference , Intitule, Indice, DateDocument,Lien,TypeDocument FROM moduleformation_formation WHERE Suppr=0 AND Id_Formation=".$rowForm['Id']." ";
					$resultDoc=mysqli_query($bdd,$reqDoc);
					$nbDocument=mysqli_num_rows($resultDoc);
			?>
					<tr name="<?php echo $rowForm['Id'];?>">
						<td align="center"><b><?php echo stripslashes($rowForm['Reference']);?></b></td>
						<td colspan="3"><a href="javascript:onclick=Voir_TR('<?php echo $rowForm['Id'];?>',<?php echo $nbDocument;?>)"><?php echo stripslashes($rowForm['Intitule']);?></span></td>
					</tr>
			<?php
					if($nbDocument>0){
						while($rowDoc=mysqli_fetch_array($resultDoc))
						{
							?>
							<tr>
								<td align="center"><b><?php echo stripslashes($rowDoc['Reference']);?></b></td>
								<td>
									<?php 
										if($rowDoc['Lien']<>""){
											if($rowDoc['TypeDocument']=="Document" || ($rowDoc['TypeDocument']=="QCM" && $QCM)){
												echo  "<a href=\"javascript:OuvrirFichier('".$rowDoc['Lien']."');\" >";
											}
										}
										echo stripslashes($rowDoc['Intitule']);
										if($rowDoc['Lien']<>""){
											if($rowDoc['TypeDocument']=="Document" || ($rowDoc['TypeDocument']=="QCM" && $QCM)){
												echo  "</a>";
											}
										}
									?>
								</td>
								<td align="center"><?php echo stripslashes($rowDoc['Indice']);?></td>
								<td align="center"><?php echo AfficheDateJJ_MM_AAAA($rowDoc['DateDocument']);?></td>
							</tr>
					<?php			
						}
					}
				}
			}
		}
	}
	?>
</table>

<table>
	<tr height="20"><td colspan="3"></td></tr>
	<tr><td colspan="3">_________________________________________________________________________________________________________________</td></tr>
	<tr>
		<td width="170" align="center">DQ 413 - Edition 1<br>27/06/2012</td>
		<td width="580" align="center">DOCUMENT QUALITE AAA GROUP<br>Reproduction interdite sans autorisation écrite de AAA GROUP</td>
		<td width="200" align="center"><br>Page 1/1</td>
	</tr>
</table>
</font>
</div>
<script language="javascript">
<!--
Masquer_Tout();
-->
</script>
<?php 
}
?>
</body>
</html>