<?php
require("../../Menu.php");
?>
<script language="javascript">
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkPresta");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function Excel_ProfilPrestationOptea(){
		var w=window.open("Excel_ProfilPrestationOptea.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_ProfilZSortie(){
		var w=window.open("Excel_ProfilZSortie.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
</script>
<?php
if($_POST){
	if(isset($_POST['Excel_ProfilPrestationOptea'])){
		echo "<script>Excel_ProfilPrestationOptea();</script>";
	}
	elseif(isset($_POST['Excel_ProfilZSortie'])){
		echo "<script>Excel_ProfilZSortie();</script>";
	}
}
?>
<form id="formulaire" action="Liste_ExtractProfil.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#fdca83;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Profil - Extracts";}else{echo "Profile - Extracts)";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td>
	<tr>
		<td align="left" width="60%">
			<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<tr><td height="20px"></td></tr>
				<tr>
					<td><input name="Excel_ProfilPrestationOptea" style="text-decoration:none;border:none;cursor:pointer;text-decoration: none;text-align: left;color:#0000ee;background-color:#ffffff;" type="submit" value="&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "List of people assigned to a service on OPTEA but not on the Extranet profile";}else{echo "Liste des personnes affectées à une prestation sur OPTEA mais pas sur le profil Extranet";}?>&nbsp;" /></td>
				</tr>
				<tr><td height="20px"></td></tr>
				<tr>
					<td><input name="Excel_ProfilZSortie" style="text-decoration:none;border:none;cursor:pointer;text-decoration: none;text-align: left;color:#0000ee;background-color:#ffffff;" type="submit" value="&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "List of people who do not have an OPTEA contract but who are not in Z-SORTIE";}else{echo "Liste des personnes qui n'ont pas de contrat sous OPTEA mais qui ne sont pas en Z-SORTIE";}?>&nbsp;" /></td>
				</tr>
				<tr><td height="20px"></td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td>
	</table>
</form>
</html>
	