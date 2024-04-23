<script>
	function Affiche_Masque()
{
	var SourceImage = document.getElementById('Image_PlusMoins').src;
	var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
	
	if(result == "us.gif")
	{
		document.getElementById('Image_PlusMoins').src="../../Images/Moins.gif";
		document.getElementById('Table_ChargeCapa').style.display = "";
		document.getElementById('ChargeCapa_Info').style.display = "";
	}
	else
	{
		document.getElementById('Image_PlusMoins').src="../../Images/Plus.gif";
		document.getElementById('Table_ChargeCapa').style.display = "none";
		document.getElementById('ChargeCapa_Info').style.display = "none";
	}
}
</script>
<?php 
	$formatMonocompetences=$_SESSION['MORIS_VisionMonoCompetence'];
	if($_POST){
		if(isset($_POST['formatMonocompetences'])){
			$formatMonocompetences=$_POST['formatMonocompetences'];
		}
	}
	$_SESSION['MORIS_VisionMonoCompetence']=$formatMonocompetences;
	
	$checkedVolumeActivite="checked";
	$checkedVolumeMonoCompetences="";
	if($formatMonocompetences==1){
		$checkedVolumeActivite="";
		$checkedVolumeMonoCompetences="checked";
	}
	
	$visionOTDLivrable=$_SESSION['MORIS_VisionOTDLivrable'];
	if($_POST){
		if(isset($_POST['visionOTDLivrable'])){
			$visionOTDLivrable=$_POST['visionOTDLivrable'];
		}
	}
	$_SESSION['MORIS_VisionOTDLivrable']=$visionOTDLivrable;
	
	$checkedVisionOTDPourcentage="checked";
	$checkedVisionOTDLivrable="";
	if($visionOTDLivrable==1){
		$checkedVisionOTDPourcentage="";
		$checkedVisionOTDLivrable="checked";
	}
	
	$visionOTD2Livrable=$_SESSION['MORIS_VisionOTD2Livrable'];
	if($_POST){
		if(isset($_POST['visionOTD2Livrable'])){
			$visionOTD2Livrable=$_POST['visionOTD2Livrable'];
		}
	}
	$_SESSION['MORIS_VisionOTD2Livrable']=$visionOTD2Livrable;
	
	$checkedVisionOTD2Pourcentage="checked";
	$checkedVisionOTD2Livrable="";
	if($visionOTD2Livrable==1){
		$checkedVisionOTD2Pourcentage="";
		$checkedVisionOTD2Livrable="checked";
	}
	
	$visionOQDLivrable=$_SESSION['MORIS_VisionOQDLivrable'];
	if($_POST){
		if(isset($_POST['visionOQDLivrable'])){
			$visionOQDLivrable=$_POST['visionOQDLivrable'];
		}
	}
	$_SESSION['MORIS_VisionOQDLivrable']=$visionOQDLivrable;
	
	$checkedVisionOQDPourcentage="checked";
	$checkedVisionOQDLivrable="";
	if($visionOQDLivrable==1){
		$checkedVisionOQDPourcentage="";
		$checkedVisionOQDLivrable="checked";
	}
	
	$visionOQD2Livrable=$_SESSION['MORIS_VisionOQD2Livrable'];
	if($_POST){
		if(isset($_POST['visionOQD2Livrable'])){
			$visionOQD2Livrable=$_POST['visionOQD2Livrable'];
		}
	}
	$_SESSION['MORIS_VisionOQD2Livrable']=$visionOQD2Livrable;
	
	$checkedVisionOQD2Pourcentage="checked";
	$checkedVisionOQD2Livrable="";
	if($visionOQD2Livrable==1){
		$checkedVisionOQD2Pourcentage="";
		$checkedVisionOQD2Livrable="checked";
	}

?>
<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr><td height="5"></td></tr>
	<tr>
		<td colspan="8" align="right" class="Libelle">
			<?php
				$vision=$_SESSION['FiltreRECORD_Vision'];
				if($_POST){$vision=$_POST['vision'];}
				$_SESSION['FiltreRECORD_Vision']=$vision;
			?>
			<input type="radio" value='1' <?php if($vision==1){echo "checked";} ?> onchange="submit()" name='vision'><?php if($_SESSION['Langue']=="EN"){echo "Calendar vision";}else{echo "Vision calendaire";} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" value='2' <?php if($vision==2){echo "checked";} ?> onchange="submit()" name='vision'><?php if($_SESSION['Langue']=="EN"){echo "UER/Department/Subsidiary vision";}else{echo "Vision UER/Dept/Filiale";} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" value='3' <?php if($vision==3){echo "checked";} ?> onchange="submit()" name='vision'><?php if($_SESSION['Langue']=="EN"){echo "Site vision";}else{echo "Vision Prestation";} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="8" align="right" class="Libelle" <?php if($_SESSION['FiltreRECORD_Vision']<>1){echo "style=display:none;";} ?>>
			<?php
				$nbMois=$_SESSION['FiltreRECORD_NbMois'];
				if($_POST){$nbMois=$_POST['nbMois'];}
				$_SESSION['FiltreRECORD_NbMois']=$nbMois;
			?>
			<input type="radio" value='3' <?php if($nbMois==3){echo "checked";} ?> onchange="submit()" name='nbMois'><?php if($_SESSION['Langue']=="EN"){echo "3 month";}else{echo "3 mois";} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" value='6' <?php if($nbMois==6){echo "checked";} ?> onchange="submit()" name='nbMois'><?php if($_SESSION['Langue']=="EN"){echo "6 month";}else{echo "6 mois";} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" value='12' <?php if($nbMois==12){echo "checked";} ?> onchange="submit()" name='nbMois'><?php if($_SESSION['Langue']=="EN"){echo "12 month";}else{echo "12 mois";} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<?php 
		if($_POST && (isset($_POST['btnFiltrer2']) || isset($_POST['btn_actualiserFam']))){
	?>
	<tr>
		<td colspan="8" align="right" class="Libelle">
			<a href="javascript:GraphiquesPDF();"><img src="../../Images/pdf.png" border="0" alt="PDF" width="25"></a>
			<script>
				function savePDF(charge,productivite,management,otd,oqd,competence,prm,securite,nc,pdp){
					Promise.all([
						chart.exporting.pdfmake,
						chart.exporting.getImage("png"),
						chart2.exporting.getImage("png"),
						chart3.exporting.getImage("png"),
						chart4.exporting.getImage("png"),
						chartOTD.exporting.getImage("png"),
						chart5.exporting.getImage("png"),
						chartOQD.exporting.getImage("png"),
						chart6.exporting.getImage("png"),
						chart7.exporting.getImage("png"),
						chart8.exporting.getImage("png"),
						chart9.exporting.getImage("png"),
						chart10.exporting.getImage("png")
					  ]).then(function(res) { 
						
						var pdfMake = res[0];
						
						// pdfmake is ready
						// Create document template
						var doc = {
						  pageSize: "A3",
						  pageOrientation: "portrait",
						  pageMargins: [10, 10, 10, 10],
						  content: []
						};
						
						doc.content.push({
						  table: {
							headerRows: 1,
							widths: [ "25%", "50%", "25%"],
							body: [
							  [
								{ image: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKEAAABGCAIAAAArcmPbAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAJaNJREFUeF7tXQdcVMfWn7udosb0l+QlL8+CoogKUnbZpVnBQu9FRVETkxgTW2LsUrcBgkgvYteoSI0Klqgx1tgLSYypL0VFQan7nTN3F1lAAc170Xzsb7wsy925985/zjn/U2ZkNBoN6Xr9vUcAMO56/b1HgPy9H+9v/XSNmsYGeED4hz808Fttg6axXqO5dO2H6pqapmfvwvjpnQaN+EKA4Qg/oWnuNWqKP//yTMV3tfX1+DF9dWH8tGIMAILIIsqNdawkf/vjL2tyNhftOYDCDR9SKe/C+GkFGDUz4NtQ21hfyz5Dfmn5vGVR+48dp78B+lqAuzB+ijGmKIIEa3749cbClcp5iyOuXr8Ov9Y1gCW+D3AXxp3BWGvdOvOV5udqLWanvt78O/geXmB9GxBCvBsA89PS8vH+YVHKxNpakF34W2N9QwOKOD2hS1d3arg1DXRo8YVcptMNR50d+I7OFdbgAmDsxRob8AW0WQv8sSu/+L+7zMpp/K6SEnpXCHCbj9TFuTqENKCLGpAdbC2Lhd/qO94AGxQ0KoEdE2l2VsDJcJVaOjm0EF76uWp+ctHrlm6uvmEXKq5RgOvq6+t1f2/5RF0YdwhjHG7AqAHH+x4KjJ7B62AXLMTsRKEOz8NeKMWom2s1wKrgqlSmf6y8Ld956k3PpcJedouilLX1aI/hbrChFHfJcYegeNBJiDHAAv/qGutqNHdu19++XVfdmVb5w63r31f+WscqA8T6YSgjqmgetAz5p6q6uM+uDJ69kQwM6Ws1vrS0THejzWdbF8aPhzEbQgLxu9lwc+mB1d7b5vtvX9iJtmvR8KjgHeX50AfLjtqzzFrAbtTWZh+6LFucz3eJJb3d3f3fulZRgY8CM4BOE20M5MEG4O+qq5s9MQhfe8OpZTVNQUEaWWg5JfADFJrtl/YaRtqRKAsSZUWirVoe2Q+bN/gEP7ThhJhsKtykNcrIkHUUTEfiqEUAe8Da7oaq2rpPv6hwXZ7fw38NsZttPHBklDy++m41vTFQKFqMtQzh/x3GaJ+owaPzvCOvFmfh+Om9tJz4dsMd97UfkihropYSlX1nmoNh2KAtxdvYm8LuMD5FI47slEKvCN5gTONWnWbrseuuUUWGwRnEI4mYB9s4eX5Wsltn0em9PdyeN7v5v6kcU3JEeSyMWkO15m6dBvzHuge1Og2Y2LoGDcT071Vpqn+r/qOeKuZmLyQ28OvOq/uMYqREISEqh84AbM+o9DGmvi51dlkPSYtYXUNj8YnvvGOKjIJSiE8aGRtN+o8NCpt1/dp3LKx1DUjFOjJrm875e2KMgkIFr6a+OuHYprDCldOKVk4rfmCbUbRyRmHUtKLoaSVRIZ8uCpfPvnPvTvNxRDwa6m/X3hu77n0SM4SjtGeUj4kx6heUYvR4cT7VNTYWn77uJy/qGbiGuCdzfDOJ+P1XLCekpqTV16J+rgPS1ylsdSf/fTGmT1j+/clnY0aT5UPJCsuHNgsSAc2GrBxG5plZTXS6W9UCY7R+Gy/uNowEdMVchcPjY0w5M6J7u15TfOpasLr4ueAUMn41CVzH8VxFBvqPmBBy9OiXLFL19bUQnW4G8YPNb6t58ORgrDVSrMlBv08XTGo9d9nn0z6lbm5TH1H3C8SDUBPWTy2IIdG2RGXDKKREKXtQY5R2jNKWUco4SjuyZJg0fOy9apba6GIeDZpbDdUuG98nUXCaA6MApGWd0tVE5WgwxZy1xxrweilNgB8lZ771VRV1A7vrlkx8MrmBa5nhi4xMRs5fsOh25R+ILjxXPcqwlplphwN9qw5K9ZOBsZYdUacfKWMHb157GowUfhMCfTqM2RDFkR9Ov6xwZeRirhzQfSg/UgNg0BxInJQssZZNHXu3WivHMFFY/rXr8r7uckeilBK1M0cFs6GznMtJNNV8a9FmetMNlXX1+Se/CY0vei4UNPNq4pfFCVjL80sllpMGO/h+unmn/hB0ckT0v/zEYIz4oDKiVqr2i+++3HK+cPOlgi0XCzdcLt50qXjzpaJmrXDrxeLNF0o3XCpad7Vo2Y7Eb37AkF4TxjDD7zbWh+2KIBHWHLkdVw5i1ymMXe9WV2kVCTXstxruuud9SKKtOSqA1pFo50QneDVwLqMppjvLCyobNJuOfe0ZW/BswBoyYQ3xXccE5PGDc4nrCt6ACZPDZn//9WWqnO8/Tuem/JOpq2nFChq8Guoznr5xeWCCp5HcsZvc4dlYJ2OFczeFc3e5c3c46t50Uww3UI0wUDs/Fz28h4fJiXOntBjrZvyhH8+9rB5LFDaMyg5Ua0flGDyiJdbSKa7VVFdjeIFqxE8rDhpHOxGFmC+XcqG3zmPMUdl3m2o2VZHsHlvUIyABPSK/NI5/JidwncA3lVjNeMNqTEpqBlJ7Oss1EKfsJH9+0FR4IuQYcynUkYABrWus/bBUTVbaEDCNsWKwf2hQY8QkWoxH7Rv4BD6XwJ+4ERJjj74nL3zFGk/Wa4QRmlEQhUKssiNxIG0gfA81n026WodxlRZjTDbdbKwavwF8Yisw6ny5PVchY5TSztpjbpyD4ZShQucZxDuD+GaTgGwSmsENzQHviDvQwy1w+tmv6CPQCBg1tm3FYR5Jop8IjGmkBlkmDOiJ3y+9onYjoGDRP5EyCgkjhwYuKbynDd/bMbF2vBgZ6GGicDD27t8C4/0/f/WichRRWHOVjhyFM1HbPSrGSP52XtlnHGMPPjFf4cgBm62WMqpOY8yJczCYPFg08m1O8FpOQC4TlMsNTCHit18eNCppdVpt3T2qy0CMQaHhbG83adFxuJ8IjNkgD41aNHxUlkhibICygqAwIHyshLWwpsBskfLgCRyFrIeX6Vfnz6AEAD0CPaCpDdu1kkRas1qao3SkAD+cBus4F0j8YmvpVFdWjkGcqhrv+W9YQKKH4bXAJ1ZDA8VACRq8wduDOwEiBlEOe1DIeALGv1pekWI8RDjiHW5AhjAolzMuhmc6wcVn2rHjaGXo9KbpX63D0AnXqF2wnxyM8VZP/n7lX+oJNIr0cDrjCJIE48hR2PPkds94mbJyTCsgNId+PvWK0oXESkDW26Fa969C5xOGrqTMYuDVYI+Rc8Gr8NuDPaOGgzppdUs4/8CPAl8Z3WUkYgAzaxSwnxazCjAWTR5iMGoWzz+diD98xWxEjGpV1T0UX21KuV2sHvWEJwFj8GwhQoesa9YeORhaKnmdwdi7//GLiDEouJrGukn5y0gUOMQybA+n0w/GuKr6NnR4t6F2wqbZJGoYTKaWt0T1P6N0wga4ymE+SRm1hIuOOGDcSo7VUoMpllzbYJ7pWHefsFMnTrLS2wAB1j9TaNuYCE8CxvigoKa/+vXqq6tc0QB3HuPTLOfSaA7/dPr5uDFEKQb1DsqzvbnSdAKVY1TFoKutQI6rqipRiK8eNooGk2/NUbSadqixQTPbkwR7Jt7u5YRRLye5QiCFB1wBMW4Z6eTGORpNHPLGEHFScmpd9S1WeLWJDjRVoII6ljzpvDQ/ERjTauDaD/fGA4vmofC1GwrW19XepqcvoD0GOh1eFEmixFTxdhxgNJ86jO1QV4e53K2CIMhdj41zSaQVD+NfrVULiC/MCRlP7SiaLw7bsMQ+7y3k+cAKVaC6WbN9v3HU9t1D++dsWMtihLFJXQKKpZydSCR1Eua/BmNt3LKZ/3f8P+deSxhPYqmCBYcHbBvyaiooTRLT5OGg8QOaIwNXlR9r18PH9ASV4yM/n3tRPYYTA5qgxSzRUSroAVypGBA1/UkAlhVhdgBR5i6xkYaNqbl7L//bg8ZRDowcAHZCn7jFpAEbHGvHV4qNVjgLpf0Td2wdvfY9cPDw/unNt9LtkHcy27UbawQw7AoMEykWm3b676rrvwZjqpgw38JGLOA4f+8qkBgYTYqrGPDjKBwgvUMJNnBjlBgSByYQw0yozAHjOPjcSRgr6+nV/8T5UzBaMwvUJNKSUYhbqVaWojsyarFBjNPgjIkQPNHnRGhBQbczihHMUmvHeV63aiq9N80jMdYEr+XIVcCl9WEDeY1xFKlshRMHGVuO2fD5aee86STSltoIlsHpn692MAgbtLUI49XwvP8tvdyWiP81GFOWAUYYs+TwOvNHxb+TvJlYW74cVRzECxHdOAAVZAiEFbSfhKe048lZEwgfArpwDgylI08h7elmArr6+M3Lr8ZNADrNkUvAoWoxxJgNjJOKltr2DnP+uHyNsWp4c4xB7mFKwRwCEQS+5rfu46JrR56NHk6Utjp3qHWHEr58JG+FA39Yrx7jFmUfvuCcN60LY+0cQ3xp8UMNSDMmxxvn7kmAvB5PLuGitKHUojRDegAcXIh2xQMbsgWdzI0FjNkQBOhDKU0MQJDS9pkZVuevX36nNIJEDqVct1XCAOeEPS/egefVO0Ih33i53DiWdZq1ogZXRNGPkxC1rShKqjq92XvrR1ido7SDwBZVvPdPplROxlNJjeJGCoPM+NYePfwzsj4/24XxfSXCYoylLlRln71R8WaCOwQmeZAdAlxBJ1NrCpIKAINECqIc+iZ6vbxqLIEIFxpO+NyexIOjIkPJVlibJQXmV+x7TTmKibVqFoK4ryoBda7KXvCJ3YDRFjd+u/np1YOG+hijlgYTAIYgxtIxc/raitLusU6g8yFhxaNpxOa6V4ux0sF4kT3f0pTnpTIMyM49dKYLY31DQWN1NCVaP6c8kUTY0oAUBBPYwJYDaEh0NNX2BjHDu/uZztsYOSQ5GD0itQRVNHVyAGM8Lcbqw3LllKLlTKQdIIHB5DYYtVSglAnGmSSnrYL72Hx5rwCyhM1Fk0apIFwqjJaoTm4K3L4Yiu4gW8WJZYNZqDn0lL/aQah2MnTvL5KEcEPXdfNPzzx01qlLVzcDGfFlScfZ3y6+meAB8WfkVgCtlo6ifwk2mBtvL5xlNTLI5cyPlwYlBVKMQYhBkyNPRtuptPtX3Lg1F3a+rILAFmhvmCgsxmhfMd2E1l3GjZPyPxpq6SL9/cZNuOi2K2VCuZOersZLy0iMlTQrLPdC6XMxoxl0c2m2igYsdaIMNtse5hZX5Wj4sbXA0tzAbzXXM41rNSm1cM/w9TO67HETylgbThl1w5zdcUyEGNFqJnw6BiQVyO17jOqVX5L/fe0ffZN8idyWlXJEEd5ApCnSdmZp7KQCsMRiJhbTFTohpjYbdb4jF2yt2lkwoW/u+nXsHey8vNsQBFTfxPIVUqMIWfzp9aE7l5EICVItvUYjl3BpNBB2IrmzyH2IyHESYz+ve3/X6TPnn62ocMgN78K4iXM11lLP+NzNr/+V6AUJxBbeKppPQCvekf/2UO8w79ramoqqn00SfYlCizE4M+g7KYf9M8Ej81zBq5AnjgXLTV1qLTDAnkA3OIIZ5kOS4H0LJ/9xtTV32SKizVf2C+X6vhNOF8tRmbM2XN39rHpkGwFzBNge3TmVVKh2MPzAhrzxqmiAk4vnWyXFWBJb3VArzQzrwliLMQYAaG3OPEgxRYgxP6gfkYAYCBBaXoxz9+G9yvbtga9drfxRD2MVWG4pJHSnYTFlLAORB62KbhI+DDaBXuWqrIWxkp6jexUW5UMi6cTV3zcfvJJxdq8QPCU9EysRRNrGHdsS9CkIsQVHAVa/ZRCDcm9kfAKlM1/6mpnFsIy12bXVbFJBc7PuLuj5Lox1ckyjXKf/qHgzzhP9Ewwh6YWlAGOeWiaYZB7wVkgNrOhqbKy4pYcxBsKiJW+q3Vef2fKyejwH80stOBHIHPBkGTdeYvSu9RA3j4gtR8ZG7Xg1NM10elrq8WJjcMmafyVG7Jw9PetyQffYURyg0wpILbTEmPIAewg786abjwv2+PmXX9nngXQmqIdbdfeeXIyp1tQuXKZ2svXCERqWofazKYyuz5Lb/40uC9CmvdkI5kflaUyklAPxDeq8olRhCJD1jO2EUXYvOfU+fvo4HcXGisqf+q2iuhoCIyoHvsqOG2n7brHyvVIFmvOWQgy0CEBy5MhlQoVU5GzWY/Qc4pNB3NcQr/Te761POfmZEYYtdWxZKeVF2iWe3DgR6TSbTWpdj4emHXgZL1r2zKi+e8vK8cYgUtdQB/X38LbyycaYFoexyxuhvhHq5uhaZV2+i11NSev4KVCPkh5hl+nBgj9dUd3FP77tkwAcCo0oZa3gJqGnxFE4YQwrwYE/eeBbC96hswpfFZW/9E/0ATMJlAebXNwrYULG+YLXFW6cGDFSYn2KxFFKuHJHHrg3M814FlKRXzrxo+U1flkD3stLOVlioHJCjCGQAgIdYy3Nmpx9sfClaBeYFtg/kvyWNBDmnwAUQ/jA0DnT6KIVvVflk62rwaJgrWpl9b2q2qZNnWiggiLKrtfQwt6+xLZ1BioC1BF4Jfr3xeWpTBTEJtGjZUspqGtL6yjiZaIVdv8YaXL23Gkqw6hXvr79vUmSJ8RAMOoEijpK8nZx1PTSGEgxcVRQR9cqB4D5DAk4uyJ7E+GY+fyQXMY/kwRkNcPYkQBHQ0fcRhAliTu5MWTXMhJpJ4jFOAxNKuiZDwydQsFehPTFkSbHjh3FyaeP8hONMU1/4OKqGzdvJ68ryd5z5uqv1bhGli0cwx8g6DVQF4sFzI+UAGOtAa7OpsmWK7eu9U/yI7G2XJChOHRMMXQFBBgzPBIeGLwAk3kr5mHpDrv7hUbzze3r/VZ7EoWMiz6P+I0Ej+yzO16Pc6MeM6R6Wq1MUdvz4+27TenPtxohCMhggjKYgAw9jCE/gTkPBxJrKcsIy7m8+wX5KAbZH0w7VlHrUwSlnRCYYKDJu3PfhYJruhpKT5KfaIx1i1hR1gr2neg74WPZ3I3zcw/v/PKbyz/evHN/+YUW30dImOBX6I4HMDJQ7rFwXwoTAZFLWkcX58CF8DLWQzlCXImrFBsuFPcZbf4L3aKGfcHXz9/8oV+iN4RKMLUQJQYJfuczBT9CW891P/+oU7CAEyxaMbDtxx+3kBuSw/inkAB9OVZDqJKmOlbaKr/MDUKfGKt0uUop3E/r5CAocNEySa8xgy9fvIhzH6qfnyZdjRvw0RW6kAfSaOLzihjJewaeiS9OXmezIH9G8oHVBaf2n/vx2q/V1ffaXXzRzI43GwKqKnBPMIDrzI1v3ljlg8VWMKAQLFRCvg+MImAMWUUgUzLDwH4R8Yr/3Kn/4uvfN524rth57u3k8rTDRwatCQIzDDml3qrxcee2vBbvwYFks9KBJ4eQCPK1+yZZKRPFORtOMuNbeQgDcziB6UxgJuMPcpxJfDPBHq85WWKoHAkLZIjCQpw5Le9yyQvykSDEVJegJabxEz0bz0twZnx7L4tdhtNVuzfDU2SP9RgW6qGwpYnEaQnxzSPeacQrpZtfSu+pOU4fbw9PKIvYdHzd/orPz/506XrlTzfqqmqhDLITJhqU/qKyNBKJZZc0v9SU0gFpBrdEKlpi3U1mNnbpBssFW/8ZnmEMKwncVpOxcR/tLB6cOonIrYAfvVOqene3HAqw2XItGrtmiRKt3oJiW7mD0TJboUVfQ9dYqFAngekkMIcEZEBBJNcnzfS99YCxkXwEJKwE0TZxJzdNyofSEVzsxOaqdVWVbJbCAVNhcQ78xXZvugz+5ju6gL+tmfxE6+rmELE84j83bovDlpFxsUxgFuOXQYCtAC/1TCEeaQLfrO6hua++nWU+b9PolQVhCeVz0o9EbP5yVeHxzPIzGw5d2v7ltZLTP+85p21lF34pO/9L8amfd31xPf/Q1aM/Xe0L1AnCF21FCgVqZ5FnPyNxINc7lfo5aVhu7pMl8ElbVLB3cFowkQ99LcEt+eyOXgluJIa6sLoiAox4qBz5cijYcBBA8Y2fich6gigIuTQTkMnxzybwPmgtcK7+szaknywyhhxXlFiSOTX7SuGLyrGYtdQPeiAdg4g3RC4hvah25Hv0lifKcaxwkw59U0xH8KnBmE5SnKXHzl/6p9sCxn0NJzALdB1KAHAWQNo/jfinEu904pFCPJOJVyLxXkV8k7kBKcLAFKOg1GdCMp6bmP385KznJ2dCewFaWGbPSenGfkluy7fOKU3lRdtwMY6o55mAFSSJEuEn1sLBw4x8kpiAtUxADicgh/GD6ZUp9E1ftqvMPN2PRA6eWSB/r0SBtdO07JKNY6CHjbQL6kOcBEqJaKmVcNggAzc5E5wDQgz9cPzTubDdQlAmCcw2ez8v/USJkVIsWimLP77Ff9di2BSAh2EvfWeJjYLFSUGOBXOHmbtZ/w5BD6z0byOA8NRh3FCPPo4mb8cBI8cFiGgwsJVsEphFAmGMkLkwIBb+2Yx/FsEG2GcRX3BL4I2uQcDhfksn3qlGvsmRZcf6rwEf14ILzk9Ld9ZBEC8Rur0pcnyLG5rNwAIC9lqBYEQzBH6py/L3Dkzx+keca+qZHW8C+UJz3gwSTC5BCQdU/zgI4iQG7iYGkgC+33qeXy4JoooaeHVIHi84h/FY1cd3SdIXhaJYiV36lNwre55TjiYKqDFqvSCKOlEqGV/tJBj776TsZJThpq1FWlmop0qOgRxB8p6mhebKM7nDF3L8s8DxYPzTqPsB6ObiEVsW4s02PAcmgbYx9AT2iM0na/SKbfMO5MCaMFqS16ouFWoWZw8VDB0m8k1mAmGdSJqWBqMdzeH7pS0u2DtglffEXSvf2qOAurjW1c4MJBXUdlzVSMOF1sKhliIPNWhmni/IcRYJXscDbTReTiymdh/gETxtfvqJQqNIB/mx3OCCFRAm4yihQ8hwtCi/QgMPOUTePCs7v+E3b2EpLt1pjzoij4bxlPbruf6kZWx6JKmNei6WN6LHXF05YaaSjJLzArK5ACSMOMoxdUKwoaiBtdNCq/0QwIYPqaDDyWAF/TONg7IUew+aJ3kRuQ0Uv9GKO5oZxOgHalqR0tZwlIlg+DtC/3Ucf1gOBD3kcAOyUV3754p8MxcWfDYy653VX336b0g2gxDrV8Ii8wJHSG0nUg03mGAqcpokDMpjQnJ4wXmM3xoy/BMyKKDXMJeZ73x0ZP/Rxrq6TReKJGlTsi8UvBTjgsuo6IKaViV2uHYNzArj+vr6LVgwSyOBdeB9QJyALpTGQaLr2nGwKmvbyUng2tQw820l29muwIuEGYNrm/QbXAJqzZt27OkEoX3wqa0x1vnBIM2a+vPfXDf1WsBxU3KCwEaCaGYgojo423yDQg9kDU4OhDeZxCdl/MqCBftyONG2aDXVkPVzAlKNteZYv+jIVTsK37NkzKUcj3jimYHkzgu2O0kh3muweaVw3Fd/vLUw4fiGWaVKznJYT9xyDT+mCuQyboLMYO4wZqCY567i+GRwx6nIsBndBo4b4RaWkpx9veLbpkHYcK5k1dH1IfnLSRR4w6yz1KpUFkLTkGiaPWxUyNiaO9VteA/aAL6WgN2oq2knJxEHGA/asQdrb9vgbFq6Tp3MevBmEfs/S6bbxBgCH7gXSX0jGuaC8sM9R7xPfNJ5AaCu01A0H4pxk+XmBmRy/bINgzLUe48MTvMlShsOzdpi4kEFfi06OXyFA09p3230QIuQuVYf5AyelzpoXobZ3PWD5+QO+XDtoDlrB8xdazY7a+3hE/t+OfIvtSdEx2gZUMssE0wXkcKx25h+Fr6zBs9O7eO3wHx00Mw58/eUFVTW/IamVFNX3XijsvFGTcPdr3+6vu3inhdUY9DcYlkBekctKYLC3jDauYfLvwv2bIcRv91YU91YfZdtDXCsqmq4U91w+27DndqGW9Wa6j9q7zwcY0aNGG/cs7lGUws70dTU19xtuEu70vZ5u7Hyj9obP9z6FTaGoCHczm6n8EBBbhNjurqZjVjTKReZupXjPB9VaFAOktV2MGapGRBj4N5pY5YVzSvP5MDiXcwpDce6WhUwJm2Ey0BlL3xn0Gt+5t4FK30LIuHoVRjhVRjpu2ulb36kT2GUZ2Gk37aV5d9+NX+3kkSCDgDJw2CFfnWVjBMvM5pl+6LnALcdyzzzV7pvm+O3fc60/VFTD8uDy1YElywNLVoWWvyxb+nH84vUN2puBe9azqyAFS5sLqSNlRl8pZPofatX3EwnFi+fWLo8pHhFCBxLl9Ej+2ZZcOmKSUUrAncvDS5ZXv7tUcech9WB8JROxmEDXSIDJ+2ODCpeGlK8PKhoaVDRkqBi2oqWBH+22D1r5oerl7L1yH/WAnPoqc36agqtLjYNP+/V1gbNVZFRyzlBuWiA29PVsDwevBQmILV7QIp89xcD0wJhaYJA4cxgdBpi1AASRCVlDOQQYxxE4lef9xvEXzBEOLu/6INBRh+YieaY8ucMEMwZxJ87kPtB39cWWqV8tb0XrKKQwyIXiHdihqoZxuDFSo1jZMT6H3zX3mTmQDLDlLxtRt4yJ9MGkOkmZFpf2vqR8L5kSm+LKP/t18pfUEP9F1ItiFzSWvwW9liGhSKSZwVj+5C3Tcn0XiTchEzvS2aYkBl9tW+gQ+g/fAB5C3o2TT+0zTnvYfVcXLWTKNyUN+41MgPusA+Z3oeE6zfoedwLXjP9WGWuXUfxZxjkB2FMt4DTzicU5e9/+o9lCARGVLxA0NUIoba1whsINgccKthCzDNzfETJxwezGNhGQ46RLBJnyy4BBV+FC5/EORqGDRb2NTEcZi+0tBdZ2Ius7ITDJKJhUqGlTAg5QUs7wUCL4EUL39u7ihs5jKfEyi9EBeqq2LIeuhQYLLrRtCGmTkN8pwd4TQ9wn+Y3Ybq3+1RfaG7T/KB5hPu5h/u6T/Fzn+yXtitvYv4SCHpDbhGqfeld6SWMMfoR7yCcMcjccbDv9FC3GT4e4T5u4f7Yg37zgg7D/bzCfXynBhQcKXNaC+skbPDGWtdxQp0QPOxkUxs3ideMQM8pvh709uDY1Dyn+Y0P8YhPTWIxbou8PyLgD1on0UaG6eCpi6+Mnct4rgZKRZ1XFuYsEgQsjL5nXabALC4IcWCGsW/KipJD5hifoukH3HyQcmlICsHgyp2EK6UCSW/D8Qv4gWl8v1SeXwbXH44pAp8Unk8az38Nz3fNG0EZqYe+MEnyYrCiHett2TJYXPWLdl3CVUoEEdLXRg44egpTfjQNiolwNinO5ke1Lg9lSUd/PPmSfDTmlyAXgo4cm/Vi5RiLf/kKGdzY88N7fXkaO2QXm93PqDcFMmm3tHPs91ZtlTRzClTsYsk3KircaIBdFMM2uHnDsIFbSrZhn3V019vmXeluUkvIOhMhbhf5jqyFYe8Fs0eJW3aLnD+AsBH1jDPoGxZd6hmzHnMg+DzZxCt71Ir8T/alC1fCkiF2gRruW4bhZdzfCiKODqKQAd2sJhjh13Oxk6A8EryeBG9g4Bi6iR+4njshITzhs/fLE3mRWBKETRviBllBBSuIFfPinEmw6fSPZuqmfzuPHL4DNvuhpb7au9LGqJFd090pIOghDDGbiR12YqRhk2O7rHASa8OJxapeGnqjykbX4M6NJw/cUrq1XUj+9BM6hTEwvsZZMRlk5BJeYB4TtA5CHzx/EL5MDuCta5isDUw38slc8tlRi/QQMKJ0jRrLbmjDVQt2Bius+JYWRq4qfvAmCEJxQtbyg7P5kAR0U5Hhi4nFFNIv4A1J8Jq9O/uhYy1moMoH6+ax0fSflKMWC5Ri3kqHl0YMvHD5PEpAPWxYxjqy7Kao91+YitZoPv/pzHMKFy6siQLvHNZk4LIMutODSgq+HA82C4iXCJZLXx89qOLyRewQXRjUCNSTeeCLyvFtacYUomQnNHJD7TYS7CPDJeLsRVPMthVvoaqhzXvUaZ4/G+T2MWZFGI8Yjdf8evPWqBkrhC4RvJBNTOh6HoSTYPuSYGg52hYKof9c1+ii+Z/n8KOdBeqRPPCGwVnSNaHK2ThOJgzqZ2Djz/NNIuNWEPuPyNC3iVlgzyFe/SCq5Dbp3VkfZKXnnbt0afaeaEgKof1GhxWaPTbMFjjB9iuw9pf49f4katHDh4VV13AI2gH7hEBtggRWK/FUcFfYLSaw0brTXR8gje3be8GKj7QddkCS8T95aGis1Nx1zgQ5FvMUTnxw/enCO7ZztgFRN5w4eGsBYqzVin82lg/qr0MYswwAN1ShtufUlWv9xrxNzIPIsCDYF45YTCYWocQyVHscGiqwmDxxobzP+CHE5jmu5GVG/BIjfrFZg19fIi8YP9vf4Q0bf3M7L/fA8PlLIpJTM8r37f/u+nf3arT7GJYf3N/Hy8ZwTO+eLiY9x/R9bozJs/DGFVv3sf2ece3XY2TvfiPMf7iOG7DRMKMu8dfqcWF/j8x12f9wHdTdpVf38X2fccGusLcxfaE949K321iTni79uo3oPdTF5ocf71cotAsEYtzYuPfgQTMvWY/RvXqO6dfTpQ90+OwYbefsJXqO7vO89Rs7CvNxBFvtnNzuVR7nhPYxpr3TvZW1q2bxJg9/cSI5OTU1dU1qanpqakZqChzT2WNKSnp6ZnZebl7iqqT0pKRUaKtXp66Go7atSUpMTUrJSV9burfs0uWK336DGEVLeYHfq2urT504undv6cHycmgHdMf9+8oP7is7WA6t/MCePRcvoZa+jzELc6vXnarb+w8fLCvb/Tl+d/+B8n1sn7qGHX6+t3z/nr2XL114QB9tjzOL8ZVLFeVl++De9pfvOwCXKIPWvP/yfeW79x8o++3339n6qf/lq4MYt7il/8k9/k8u8vhjraXvj9/Rf62HR8NY65g86K4eQk/YP9H/qug+i3nI07XbFbpHHXt1pKtHAKyD3bL3+Aj9d+zhHnbWI2L8+BduPjSP39tf2MNfAlunnvcvw7hTd9l18uOMQBfGjzN6T8d3uzB+OnB6nLvswvhxRu/p+O7/Af9dDNPZdVN5AAAAAElFTkSuQmCC", width:120,alignment: "center"},
								{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("CONSOLIDATED CHARTS\n".utf8_encode($MoisLettre3[$_SESSION['MORIS_Mois']-1])." ".$_SESSION['MORIS_Annee']);}else{echo json_encode("GRAPHIQUES CONSOLIDES\n".utf8_encode($MoisLettre3[$_SESSION['MORIS_Mois']-1])." ".$_SESSION['MORIS_Annee']);} ?>, bold: true, fontSize: 18,alignment: "center" },
								{ text: "", bold: true, alignment: "center" }
							  ],
							]
						  }
						});
						
						doc.content.push({
						  text: "",
						  fontSize: 12,
						  bold: true,
						  margin: [0, 10, 0, 10]
						});
						
						if(charge==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("CHARGE / CAPACITY");}else{echo json_encode("CHARGE / CAPACITE");} ?>, bold: true, alignment: "center"}
								  ],
								  [
									{ image: res[1],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						if(productivite==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("PRODUCTIVITY");}else{echo json_encode("PRODUCTIVITE");} ?>, bold: true, alignment: "center" }
								  ],
								  [
									{ image: res[2],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						if(productivite==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("MANAGEMENT");}else{echo json_encode("MANAGEMENT");} ?>, bold: true, alignment: "center" }
								  ],
								  [
									{ image: res[3],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						if(otd==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("On Time Delivery (OTD)");}else{echo json_encode("On Time Delivery (OTD)");} ?>, bold: true, alignment: "center"},
									],
								]
							  }
							});
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "50%", "50%"],
								body: [
								  [
									{ image: res[4],width:400, alignment: "center"},
									{ image: res[5],width:400, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						if(oqd==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("On Quality Delivery (OQD) ");}else{echo json_encode("On Quality Delivery (OQD) ");} ?>, bold: true, alignment: "center" },
									],
								]
							  }
							});
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "50%", "50%"],
								body: [
								  [
									{ image: res[6],width:400, alignment: "center"},
									{ image: res[7],width:400, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						if(nc==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("NC & RC NEWS");}else{echo json_encode("NOUVELLES NC & RC");} ?>, bold: true, alignment: "center" },
									],
								  [
									{ image: res[8],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}

						if(prm==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("SATISFACTION CLIENTS");}else{echo json_encode("SATISFACTION CLIENTS");} ?>, bold: true, alignment: "center" },
									],
								  [
									{ image: res[9],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						if(competence==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("COMPETENCES");}else{echo json_encode("COMPETENCES");} ?>, bold: true, alignment: "center"},
									],
								  [
									{ image: res[10],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						if(securite==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("SECURITY");}else{echo json_encode("SECURITE");} ?>, bold: true, alignment: "center"},
									],
								  [
									{ image: res[11],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						if(pdp==1){
							doc.content.push({
							  table: {
								headerRows: 1,
								widths: [ "100%"],
								body: [
								  [
									{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("PREVENTION PLAN");}else{echo json_encode("PLAN DE PREVENTION");} ?>, bold: true, alignment: "center"},
									],
								  [
									{ image: res[12],width:600, alignment: "center"}
								  ],
								]
							  }
							});
						}
						
						pdfMake.createPdf(doc).download("RECORD.pdf");
						
					  });
				}
			</script>
		</td>
	</tr>
	<?php 
		}
		function valeurSinonNull($lavaleur){
			if($lavaleur>0){
				return $lavaleur;
			}
			else{
				return null;
			}
		}
		
		function LienSaisie($Libelle,$Lien){
			echo "<tr>
					<td style='font-size:14px;' colspan='8' >&nbsp;&bull;&nbsp;
						<a style=\"color:black;text-decoration: none;font-weight:bold;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
							".$Libelle."
						</a>
					</td>
				</tr>\n
				<tr>
					<td height=\"5px\">
					
					</td>
				</tr>
				";
		}
	?>
	<tr>
		<td colspan="8">
			<table align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="20%" valign="top">
						<table class="GeneralInfo" style="border-spacing:0; width:100%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
							<tr>
								<td align="right">
									<img id="btnReset" name="btnReset" width="25px" src="../../Images/Gomme.png" alt="submit2" style="cursor:pointer;" onclick="reset2();"/> 
									<div id="reset"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td align="center">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle2.png" alt="submit" style="cursor:pointer;width:40px;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Année";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<select id="annee" name="annee" onchange="submit();">
										<?php
											$annee=$_SESSION['MORIS_Annee'];
											if($_POST){$annee=$_POST['annee'];}
											$_SESSION['MORIS_Annee']=$annee;
										?>
										<option value="<?php echo date('Y')-1; ?>" <?php if($annee==date('Y')-1){echo "selected";} ?>><?php echo date('Y')-1; ?></option>
										<option value="<?php echo date('Y'); ?>" <?php if($annee==date('Y')){echo "selected";} ?>><?php echo date('Y'); ?></option>
										<option value="<?php echo date('Y')+1; ?>" <?php if($annee==date('Y')+1){echo "selected";} ?>><?php echo date('Y')+1; ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<select id="mois" name="mois" onchange="submit();">
										<?php
											if($_SESSION["Langue"]=="EN"){
												$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
												
											}
											else{
												$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
											}
											$mois=$_SESSION['MORIS_Mois'];
											if($_POST){$mois=$_POST['mois'];}
											$_SESSION['MORIS_Mois']=$mois;
											
											for($i=0;$i<=11;$i++){
												$numMois=$i+1;
												if($numMois<10){$numMois="0".$numMois;}
												echo "<option value='".$numMois."'";
												if($mois== ($i+1)){echo " selected ";}
												echo ">".$arrayMois[$i]."</option>\n";
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Division";}else{echo "Division";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllDivision" id="selectAllDivision" onclick="SelectionnerTout('Division')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_Division' style='height:130px;width:200px;overflow:auto;'>
									<table>
								<?php 
									$req="SELECT Id
										FROM new_competences_prestation
										WHERE new_competences_prestation.UtiliseMORIS=1
										AND (SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Prestation=new_competences_prestation.Id 
											AND Id_Poste IN (4)
											)>0";
									$resultPrestation=mysqli_query($bdd,$req);
									$nbRP=mysqli_num_rows($resultPrestation);
									
									$req="SELECT Id
										FROM new_competences_prestation
										WHERE new_competences_prestation.UtiliseMORIS=1
										AND (SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Prestation=new_competences_prestation.Id 
											AND Id_Poste IN (5)
											)>0";
									$resultPrestation=mysqli_query($bdd,$req);
									$nbCQP=mysqli_num_rows($resultPrestation);
									
									$req="SELECT Id
										FROM new_competences_prestation
										WHERE new_competences_prestation.UtiliseMORIS=1
										AND ((SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (6)
											)>0
											OR 
											(SELECT COUNT(Id) 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (6)
											)>0
											)";
									$resultCQS=mysqli_query($bdd,$req);
									$nbCQS=mysqli_num_rows($resultCQS);
									
									$req="SELECT Id
										FROM new_competences_prestation
										WHERE new_competences_prestation.UtiliseMORIS=1
										AND (SELECT COUNT(Id) 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (27)
											)>0";
									$resultCG=mysqli_query($bdd,$req);
									$nbCG=mysqli_num_rows($resultCG);
									
									$req="SELECT Id
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Plateforme=17
											AND Id_Poste IN (9,15,27,41,44)";
									$resultRespSG=mysqli_query($bdd,$req);
									$nbRespSG=mysqli_num_rows($resultRespSG);
		
									if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
										$req="SELECT DISTINCT new_competences_plateforme.Id_Division,
											(SELECT Libelle FROM new_competences_division2 WHERE Id=new_competences_plateforme.Id_Division) AS Division
											FROM new_competences_prestation
											LEFT JOIN new_competences_plateforme
											ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
											WHERE new_competences_prestation.UtiliseMORIS=1
											AND new_competences_plateforme.Id_Division>0
											ORDER BY (SELECT Libelle FROM new_competences_division2 WHERE Id=new_competences_plateforme.Id_Division);";
									}
									elseif($nbRP>0){
										$req="SELECT DISTINCT new_competences_plateforme.Id_Division,
											(SELECT Libelle FROM new_competences_division2 WHERE Id=new_competences_plateforme.Id_Division) AS Division
											FROM new_competences_prestation
											LEFT JOIN new_competences_plateforme
											ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
											WHERE new_competences_prestation.UtiliseMORIS=1
											AND new_competences_plateforme.Id_Division>0
											AND (SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
											AND Id_Poste IN (4)
											)>0
											ORDER BY (SELECT Libelle FROM new_competences_division2 WHERE Id=new_competences_plateforme.Id_Division);";
									}
									elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
										$req="SELECT DISTINCT new_competences_plateforme.Id_Division,
											(SELECT Libelle FROM new_competences_division2 WHERE Id=new_competences_plateforme.Id_Division) AS Division
											FROM new_competences_prestation
											LEFT JOIN new_competences_plateforme
											ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
											WHERE new_competences_prestation.UtiliseMORIS=1
											AND new_competences_plateforme.Id_Division>0
											AND ((SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (5)
											)>0
											OR
											(SELECT COUNT(Id) 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (6,27)
											)>0
											)
											ORDER BY (SELECT Libelle FROM new_competences_division2 WHERE Id=new_competences_plateforme.Id_Division);";
									}
									$resultDiv=mysqli_query($bdd,$req);
									$nbDiv=mysqli_num_rows($resultDiv);
									
									$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['division0'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_Division']<>""){
												$tabDiv=explode(",",$_SESSION['FiltreRECORD_Division']);
												foreach($tabDiv as $div){
													if($div==0){$selected="checked";}
												}
											}
											else{
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkDivision' type='checkbox' onclick=\"Selectionner('UER','0')\" ".$selected." value='0' id='division0' name='division0'>Non renseigné</td></tr>";
										
									if ($nbDiv > 0)
									{
										while($row=mysqli_fetch_array($resultDiv))
										{
											$selected="";
											if($_POST && !isset($_POST['btnReset2'])){
												if(isset($_POST['division'.$row['Id_Division']])){$selected="checked";}
											}
											elseif($_GET){
												if($_SESSION['FiltreRECORD_Division']<>""){
													$tabDiv=explode(",",$_SESSION['FiltreRECORD_Division']);
													foreach($tabDiv as $div){
														if($div==$row['Id_Division']){$selected="checked";}
													}
												}
												else{
													$selected="checked";
												}
											}
											else{
												$selected="checked";
											}
											echo "<tr><td><input class='checkDivision' type='checkbox' onclick=\"Selectionner('UER',".$row['Id_Division'].")\" ".$selected." value='".$row['Id_Division']."' id='division".$row['Id_Division']."' name='division".$row['Id_Division']."'>".stripslashes($row['Division'])."</td></tr>";
										}
									}
									
									$listeDivision="";
									if($_POST && !isset($_POST['btnReset2'])){
										if(isset($_POST['division0'])){
											if($listeDivision<>""){$listeDivision.=",";}
											$listeDivision.=0;
										}
									}
									elseif($_GET){
										$listeDivision=$_SESSION['FiltreRECORD_Division'];
									}
									else{
										if($listeDivision<>""){$listeDivision.=",";}
										$listeDivision.=0;
									}
									
									if ($nbDiv > 0)
									{	
										mysqli_data_seek($resultDiv,0);
										while($row=mysqli_fetch_array($resultDiv))
										{
											if($_POST && !isset($_POST['btnReset2'])){
												if(isset($_POST['division'.$row['Id_Division']])){
													if($listeDivision<>""){$listeDivision.=",";}
													$listeDivision.=$row['Id_Division'];
												}
											}
											elseif(isset($_POST['btnReset2'])){
												if($listeDivision<>""){$listeDivision.=",";}
												$listeDivision.=$row['Id_Division'];
											}
										}
									}
									$_SESSION['FiltreRECORD_Division']=$listeDivision;
								?>
									</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER/Department/Subsidiary";}else{echo "UER/Dept/Filiale";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllUER" id="selectAllUER" onclick="SelectionnerTout('UER')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_Plateforme' style='height:200px;width:200px;overflow:auto;'>
									<table>
								<?php
									if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
										$req="SELECT DISTINCT new_competences_plateforme.Id,
											new_competences_plateforme.Libelle,new_competences_plateforme.Id_Division
											FROM new_competences_prestation
											LEFT JOIN new_competences_plateforme
											ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
											WHERE new_competences_prestation.UtiliseMORIS=1
	
											AND new_competences_prestation.Id_Plateforme>0 ";
										$req.="ORDER BY new_competences_plateforme.Libelle;";
									}
									elseif($nbRP>0){
										$req="SELECT DISTINCT new_competences_plateforme.Id,
											new_competences_plateforme.Libelle,new_competences_plateforme.Id_Division
											FROM new_competences_prestation
											LEFT JOIN new_competences_plateforme
											ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
											WHERE new_competences_prestation.UtiliseMORIS=1
											AND new_competences_prestation.Id_Plateforme>0 
											AND (SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
											AND Id_Poste IN (4)
											)>0
											";
										$req.="ORDER BY new_competences_plateforme.Libelle;";
									}
									elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
										$req="SELECT DISTINCT new_competences_plateforme.Id,
											new_competences_plateforme.Libelle,new_competences_plateforme.Id_Division
											FROM new_competences_prestation
											LEFT JOIN new_competences_plateforme
											ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
											WHERE new_competences_prestation.UtiliseMORIS=1
											AND new_competences_prestation.Id_Plateforme>0 
											AND ((SELECT COUNT(Id) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (5)
											)>0
											OR
											(SELECT COUNT(Id) 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']."
											AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
											AND Id_Poste IN (6,27)
											)>0
											)
											";
										$req.="ORDER BY new_competences_plateforme.Libelle;";
										
									}
									$resultPlate=mysqli_query($bdd,$req);
									$nbPlate=mysqli_num_rows($resultPlate);
									$i=0;
									if ($nbPlate > 0)
									{
										while($row=mysqli_fetch_array($resultPlate))
										{
											$selected="";
											if($_POST && !isset($_POST['btnReset2'])){
												if(isset($_POST['plateforme'.$row['Id']])){$selected="checked";}
											}
											elseif($_GET){
												if($_SESSION['FiltreRECORD_UER']<>""){
													$tab=explode(",",$_SESSION['FiltreRECORD_UER']);
													foreach($tab as $laValeur){
														if($laValeur==$row['Id']){$selected="checked";}
													}
												}
												else{
													$selected="checked";
												}
											}
											else{
												$selected="checked";
											}
											echo "<tr><td><input class='checkUER' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation',".$row['Id'].")\" id='plateforme".$row['Id']."' name='plateforme".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
											echo "<script>tabUER[".$i."]= new Array(".$row['Id'].",".$row['Id_Division'].");</script>";
											$i++;
										}
									}
									 
									$listePlateforme="";
									if($_POST && !isset($_POST['btnReset2'])){
										if ($nbPlate > 0)
										{
											mysqli_data_seek($resultPlate,0);
											while($row=mysqli_fetch_array($resultPlate))
											{
												if(isset($_POST['plateforme'.$row['Id']])){
													if($listePlateforme<>""){$listePlateforme.=",";}
													$listePlateforme.=$row['Id'];
												}
											}
										}
									}
									elseif($_GET){
										$listePlateforme=$_SESSION['FiltreRECORD_UER'];
									}
									$_SESSION['FiltreRECORD_UER']=$listePlateforme;
								?>
									</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllPrestation" id="selectAllPrestation" onclick="SelectionnerTout('Prestation')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_Prestation' style='height:250px;width:200px;overflow:auto;'>
										<table>
									<?php	
								
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT Id,Libelle,Id_Plateforme,Id_Contrat,Id_FamilleR03,Id_Client,Id_EntiteAchat,Id_DivisionClient,
												(SELECT new_competences_personne_poste_prestation.Id_Personne
												FROM new_competences_personne_poste_prestation
												WHERE new_competences_personne_poste_prestation.Id_Personne>0
												AND new_competences_personne_poste_prestation.Id_Poste=4
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												AND new_competences_personne_poste_prestation.Backup=0 LIMIT 1) AS Id_RespProjet,Active
												FROM new_competences_prestation
												WHERE new_competences_prestation.UtiliseMORIS=1 ";
											$req.="ORDER BY Libelle;";
										}
										elseif($nbRP>0){
											$req="SELECT Id,Libelle,Id_Plateforme,Id_Contrat,Id_FamilleR03,Id_Client,Id_EntiteAchat,Id_DivisionClient,
												(SELECT new_competences_personne_poste_prestation.Id_Personne
												FROM new_competences_personne_poste_prestation
												WHERE new_competences_personne_poste_prestation.Id_Personne>0
												AND new_competences_personne_poste_prestation.Id_Poste=4
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												AND new_competences_personne_poste_prestation.Backup=0 LIMIT 1) AS Id_RespProjet,Active
												FROM new_competences_prestation
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND (SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (4)
												)>0
												";
											$req.="ORDER BY Libelle;";
										}
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT Id,Libelle,Id_Plateforme,Id_Contrat,Id_FamilleR03,Id_Client,Id_EntiteAchat,Id_DivisionClient,
												(SELECT new_competences_personne_poste_prestation.Id_Personne
												FROM new_competences_personne_poste_prestation
												WHERE new_competences_personne_poste_prestation.Id_Personne>0
												AND new_competences_personne_poste_prestation.Id_Poste=4
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												AND new_competences_personne_poste_prestation.Backup=0 LIMIT 1) AS Id_RespProjet,Active
												FROM new_competences_prestation
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY Libelle;";
										}
										$resultPrestation=mysqli_query($bdd,$req);
										$nbPrestation=mysqli_num_rows($resultPrestation);
										
										if ($nbPrestation > 0)
										{
											$i=0;
											while($row=mysqli_fetch_array($resultPrestation))
											{
												$active="";
												if($row['Active']<>0){$active=" [INACTIVE]";}
												$presta=substr($row['Libelle'],0,strpos($row['Libelle']," ")).$active;
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['prestation'.$row['Id']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_Prestation']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_Prestation']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkPrestation' type='checkbox' ".$selected." value='".$row['Id']."' id='prestation".$row['Id']."' name='prestation".$row['Id']."'>".$presta."</td></tr>";
												echo "<script>tabPresta[".$i."]= new Array(".$row['Id'].",".$row['Id_Plateforme'].",".$row['Id_Contrat'].",".$row['Id_FamilleR03'].",".$row['Id_Client'].",".$row['Id_DivisionClient'].",".$row['Id_EntiteAchat'].",".$row['Id_RespProjet'].");</script>";
												$i++;
											}
										}
										$listePrestation="";
										if($_POST && !isset($_POST['btnReset2'])){
											if ($nbPrestation > 0)
											{
												mysqli_data_seek($resultPrestation,0);
												while($row=mysqli_fetch_array($resultPrestation))
												{
													if(isset($_POST['prestation'.$row['Id']])){
														if($listePrestation<>""){$listePrestation.=",";}
														$listePrestation.=$row['Id'];
													}
												}
											}
										}
										elseif($_GET){
											$listePrestation=$_SESSION['FiltreRECORD_Prestation'];
										}
										$_SESSION['FiltreRECORD_Prestation']=$listePrestation;
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Contract";}else{echo "Contrat";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllContrat" id="selectAllContrat" onclick="SelectionnerTout('Contrat')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_Contrat' style='height:200px;width:200px;overflow:auto;'>
										<table>
									<?php
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT DISTINCT moris_contrat.Id,
												moris_contrat.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_contrat
												ON new_competences_prestation.Id_Contrat=moris_contrat.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_Contrat>0 ";
											$req.="ORDER BY moris_contrat.Libelle;";
										}
										elseif($nbRP>0){
											$req="SELECT DISTINCT moris_contrat.Id,
												moris_contrat.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_contrat
												ON new_competences_prestation.Id_Contrat=moris_contrat.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_Contrat>0 
												AND (SELECT COUNT(Id) 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION['Id_Personne']."
													AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
													AND Id_Poste IN (4)
													)>0
												";
											$req.="ORDER BY moris_contrat.Libelle;";
										}
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT DISTINCT moris_contrat.Id,
												moris_contrat.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_contrat
												ON new_competences_prestation.Id_Contrat=moris_contrat.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_Contrat>0 
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY moris_contrat.Libelle;";
										}
										
										$resultContrat=mysqli_query($bdd,$req);
										$nbContrat=mysqli_num_rows($resultContrat);
										
										$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['contrat0'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_Contrat']<>""){
												$tab=explode(",",$_SESSION['FiltreRECORD_Contrat']);
												foreach($tab as $laValeur){
													if($laValeur==0){$selected="checked";}
												}
											}
											else{
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkContrat' type='checkbox' ".$selected." value='0' id='contrat0' name='contrat0'>Non renseigné</td></tr>";
										
										if ($nbContrat > 0)
										{
											while($row=mysqli_fetch_array($resultContrat))
											{
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['contrat'.$row['Id']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_Contrat']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_Contrat']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkContrat' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation',".$row['Id'].")\" id='contrat".$row['Id']."' name='contrat".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
											}
										 }
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Family R03";}else{echo "Famille R03";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllR03" id="selectAllR03" onclick="SelectionnerTout('R03')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_R03' style='height:150px;width:200px;overflow:auto;'>
										<table>
									<?php 
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT DISTINCT moris_famille_r03.Id,
												moris_famille_r03.Num
												FROM new_competences_prestation
												LEFT JOIN moris_famille_r03
												ON new_competences_prestation.Id_FamilleR03=moris_famille_r03.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_FamilleR03>0 ";
											$req.="ORDER BY moris_famille_r03.Num;";
										}
										elseif($nbRP>0){
											$req="SELECT DISTINCT moris_famille_r03.Id,
												moris_famille_r03.Num
												FROM new_competences_prestation
												LEFT JOIN moris_famille_r03
												ON new_competences_prestation.Id_FamilleR03=moris_famille_r03.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_FamilleR03>0 
												AND (SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (4)
												)>0
												";
											$req.="ORDER BY moris_famille_r03.Num;";
										}
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT DISTINCT moris_famille_r03.Id,
												moris_famille_r03.Num
												FROM new_competences_prestation
												LEFT JOIN moris_famille_r03
												ON new_competences_prestation.Id_FamilleR03=moris_famille_r03.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_FamilleR03>0 
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY moris_famille_r03.Num;";
										}
										
										$resultR03=mysqli_query($bdd,$req);
										$nbR03=mysqli_num_rows($resultR03);
										
										$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['r030'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_FamilleR03']<>""){
												$tab=explode(",",$_SESSION['FiltreRECORD_FamilleR03']);
												foreach($tab as $laValeur){
													if($laValeur==0){$selected="checked";}
												}
											}
											else{
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkR03' type='checkbox' ".$selected." value='0' id='r030' onclick=\"Selectionner('Prestation',0)\" name='r030'>Non renseigné</td></tr>";
										if ($nbR03 > 0)
										{
											while($row=mysqli_fetch_array($resultR03))
											{
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['r03'.$row['Id']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_FamilleR03']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_FamilleR03']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkR03' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation',".$row['Id'].")\" id='r03".$row['Id']."' name='r03".$row['Id']."'>".stripslashes($row['Num'])."</td></tr>";
											}
										 }
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllClient" id="selectAllClient" onclick="SelectionnerTout('Client')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_R03' style='height:200px;width:200px;overflow:auto;'>
										<table>
									<?php
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT DISTINCT moris_client.Id,
												moris_client.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_client
												ON new_competences_prestation.Id_Client=moris_client.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_Client>0 ";
											$req.="ORDER BY moris_client.Libelle;";
										}
										elseif($nbRP>0){
											$req="SELECT DISTINCT moris_client.Id,
												moris_client.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_client
												ON new_competences_prestation.Id_Client=moris_client.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_Client>0 
												AND (SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (4)
												)>0
												";
											$req.="ORDER BY moris_client.Libelle;";
										}
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT DISTINCT moris_client.Id,
												moris_client.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_client
												ON new_competences_prestation.Id_Client=moris_client.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_Client>0 
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY moris_client.Libelle;";
										}
										
										$resultClient=mysqli_query($bdd,$req);
										$nbClient=mysqli_num_rows($resultClient);
										
										$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['client0'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_Client']<>""){
												$tab=explode(",",$_SESSION['FiltreRECORD_Client']);
												foreach($tab as $laValeur){
													if($laValeur==0){$selected="checked";}
												}
											}
											else{
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkClient' type='checkbox' ".$selected." value='0' id='client0' onclick=\"Selectionner('Prestation',0)\" name='client0'>Non renseigné</td></tr>";
										if ($nbClient > 0)
										{
											while($row=mysqli_fetch_array($resultClient))
											{
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['client'.$row['Id']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_Client']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_Client']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkClient' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation',".$row['Id'].")\" id='client".$row['Id']."' name='client".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
											}
										 }
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Client division";}else{echo "Division Client";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllClientDivision" id="selectAllClientDivision" onclick="SelectionnerTout('ClientDivision')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_R03' style='height:200px;width:200px;overflow:auto;'>
										<table>
									<?php 
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT DISTINCT moris_divisionclient.Id,
												moris_divisionclient.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_divisionclient
												ON new_competences_prestation.Id_DivisionClient=moris_divisionclient.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_DivisionClient>0 ";
											$req.="ORDER BY moris_divisionclient.Libelle;";
										}
										elseif($nbRP>0){
											$req="SELECT DISTINCT moris_divisionclient.Id,
												moris_divisionclient.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_divisionclient
												ON new_competences_prestation.Id_DivisionClient=moris_divisionclient.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_DivisionClient>0 
												AND (SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (4)
												)>0
												";
											$req.="ORDER BY moris_divisionclient.Libelle;";
										}
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT DISTINCT moris_divisionclient.Id,
												moris_divisionclient.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_divisionclient
												ON new_competences_prestation.Id_DivisionClient=moris_divisionclient.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_DivisionClient>0 
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY moris_divisionclient.Libelle;";
										}
										
										$resultDivisionClient=mysqli_query($bdd,$req);
										$nbDivisionClient=mysqli_num_rows($resultDivisionClient);
										
										$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['divisionclient0'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_DivisionClient']<>""){
												$tab=explode(",",$_SESSION['FiltreRECORD_DivisionClient']);
												foreach($tab as $laValeur){
													if($laValeur==0){$selected="checked";}
												}
											}
											else{
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkClientDivision' type='checkbox' ".$selected." value='0' id='divisionclient0' onclick=\"Selectionner('Prestation',0)\" name='divisionclient0'>Non renseigné</td></tr>";
										if ($nbDivisionClient > 0)
										{
											while($row=mysqli_fetch_array($resultDivisionClient))
											{
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['divisionclient'.$row['Id']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_DivisionClient']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_DivisionClient']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkClientDivision' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation',".$row['Id'].")\" id='divisionclient".$row['Id']."' name='divisionclient".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
											}
										 }
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Purchasing entity";}else{echo "Entité achat";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllEntiteAchat" id="selectAllEntiteAchat" onclick="SelectionnerTout('EntiteAchat')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_EntiteAchat' style='height:150px;width:200px;overflow:auto;'>
										<table>
									<?php 
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT DISTINCT moris_entiteachat.Id,
												moris_entiteachat.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_entiteachat
												ON new_competences_prestation.Id_EntiteAchat=moris_entiteachat.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_EntiteAchat>0 ";
											$req.="ORDER BY moris_entiteachat.Libelle;";
										}
										elseif($nbRP>0){
											$req="SELECT DISTINCT moris_entiteachat.Id,
												moris_entiteachat.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_entiteachat
												ON new_competences_prestation.Id_EntiteAchat=moris_entiteachat.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_EntiteAchat>0 
												AND (SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (4)
												)>0
												";
											$req.="ORDER BY moris_entiteachat.Libelle;";
										}
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT DISTINCT moris_entiteachat.Id,
												moris_entiteachat.Libelle
												FROM new_competences_prestation
												LEFT JOIN moris_entiteachat
												ON new_competences_prestation.Id_EntiteAchat=moris_entiteachat.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_prestation.Id_EntiteAchat>0 
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY moris_entiteachat.Libelle;";
										}
										
										$resultEntiteAchat=mysqli_query($bdd,$req);
										$nbEntiteAchat=mysqli_num_rows($resultEntiteAchat);
										
										$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['entiteachat0'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_EntiteAchat']<>""){
												$tab=explode(",",$_SESSION['FiltreRECORD_EntiteAchat']);
												foreach($tab as $laValeur){
													if($laValeur==0){$selected="checked";}
												}
											}
											else{
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkEntiteAchat' type='checkbox' ".$selected." value='0' onclick=\"Selectionner('Prestation',0)\" id='entiteachat0' name='entiteachat0'>Non renseigné</td></tr>";
										if ($nbEntiteAchat > 0)
										{
											while($row=mysqli_fetch_array($resultEntiteAchat))
											{
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['entiteachat'.$row['Id']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_EntiteAchat']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_EntiteAchat']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkEntiteAchat' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation',".$row['Id'].")\" id='entiteachat".$row['Id']."' name='entiteachat".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
											}
										 }
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Project manager";}else{echo "Resp. projet";} ?>&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="selectAllRP" id="selectAllRP" onclick="SelectionnerTout('RP')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
								</td>
							</tr>
							<tr>
								<td>
									<div id='Div_RP' style='height:200px;width:200px;overflow:auto;'>
										<table>
									<?php 
										if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) || $nbRespSG>0){
											$req="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne,
												(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
												FROM new_competences_personne_poste_prestation
												LEFT JOIN new_competences_prestation
												ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_personne_poste_prestation.Id_Personne>0
												AND new_competences_personne_poste_prestation.Id_Poste=4
												AND new_competences_personne_poste_prestation.Backup=0 ";
											$req.="ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne);";
										}
										elseif($nbRP>0){
											$req="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne,
												(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
												FROM new_competences_personne_poste_prestation
												LEFT JOIN new_competences_prestation
												ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_personne_poste_prestation.Id_Personne>0
												AND new_competences_personne_poste_prestation.Id_Poste=4
												AND new_competences_personne_poste_prestation.Backup=0 
												AND (SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (4)
												)>0
												";
											$req.="ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne);";
										}	
										elseif($nbCQS>0 || $nbCQP>0 || $nbCG>0){
											$req="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne,
												(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
												FROM new_competences_personne_poste_prestation
												LEFT JOIN new_competences_prestation
												ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												WHERE new_competences_prestation.UtiliseMORIS=1
												AND new_competences_personne_poste_prestation.Id_Personne>0
												AND new_competences_personne_poste_prestation.Id_Poste=4
												AND new_competences_personne_poste_prestation.Backup=0 
												AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
												OR
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
												)
												";
											$req.="ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne);";
										}
										
										$resultRP=mysqli_query($bdd,$req);
										$nbRP=mysqli_num_rows($resultRP);
										
										$selected="";
										if($_POST && !isset($_POST['btnReset2'])){
											if(isset($_POST['RP0'])){$selected="checked";}
										}
										elseif($_GET){
											if($_SESSION['FiltreRECORD_RespProjet0']==1){
												$selected="checked";
											}
										}
										else{
											$selected="checked";
										}
										echo "<tr><td><input class='checkRP' type='checkbox' ".$selected." value='0' onclick=\"Selectionner('Prestation',0)\" id='RP0' name='RP0'>Non renseigné</td></tr>";
										
										if ($nbRP > 0)
										{
											while($row=mysqli_fetch_array($resultRP))
											{
												$selected="";
												if($_POST && !isset($_POST['btnReset2'])){
													if(isset($_POST['RP'.$row['Id_Personne']])){$selected="checked";}
												}
												elseif($_GET){
													if($_SESSION['FiltreRECORD_RespProjet']<>""){
														$tab=explode(",",$_SESSION['FiltreRECORD_RespProjet']);
														foreach($tab as $laValeur){
															if($laValeur==$row['Id_Personne']){$selected="checked";}
														}
													}
													else{
														$selected="checked";
													}
												}
												else{
													$selected="checked";
												}
												echo "<tr><td><input class='checkRP' type='checkbox' ".$selected." value='".$row['Id_Personne']."' onclick=\"Selectionner('Prestation',".$row['Id_Personne'].")\" id='RP".$row['Id_Personne']."' name='RP".$row['Id_Personne']."'>".stripslashes($row['Personne'])."</td></tr>";
											}
										}
									?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td align="center">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle2.png" alt="submit" style="cursor:pointer;width:40px;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
						</table>
					</td>
					<?php 
					$listeContrat="";
					if($_POST && !isset($_POST['btnReset2'])){
						if(isset($_POST['contrat0'])){
							if($listeContrat<>""){$listeContrat.=",";}
							$listeContrat.=0;
						}
					}
					elseif($_GET){
						$listeContrat=$_SESSION['FiltreRECORD_Contrat'];
					}
					else{
						if($listeContrat<>""){$listeContrat.=",";}
						$listeContrat.=0;
					}
					
					if ($nbContrat > 0)
					{
						
						mysqli_data_seek($resultContrat,0);
						while($row=mysqli_fetch_array($resultContrat))
						{
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['contrat'.$row['Id']])){
									if($listeContrat<>""){$listeContrat.=",";}
									$listeContrat.=$row['Id'];
								}
							}
							elseif(isset($_POST['btnReset2'])){
								if($listeContrat<>""){$listeContrat.=",";}
								$listeContrat.=$row['Id'];
							}
						}
					}
					$_SESSION['FiltreRECORD_Contrat']=$listeContrat;
					
					$listeR03="";
					if($_POST && !isset($_POST['btnReset2'])){
						if(isset($_POST['r030'])){
							if($listeR03<>""){$listeR03.=",";}
							$listeR03.=0;
						}
					}
					elseif($_GET){
						$listeR03=$_SESSION['FiltreRECORD_FamilleR03'];
					}
					else{
						if($listeR03<>""){$listeR03.=",";}
						$listeR03.=0;
					}
					
					if ($nbR03 > 0)
					{	
						mysqli_data_seek($resultR03,0);
						while($row=mysqli_fetch_array($resultR03))
						{
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['r03'.$row['Id']])){
									if($listeR03<>""){$listeR03.=",";}
									$listeR03.=$row['Id'];
								}
							}
							elseif(isset($_POST['btnReset2'])){
								if($listeR03<>""){$listeR03.=",";}
								$listeR03.=$row['Id'];
							}
						}
					}
					$_SESSION['FiltreRECORD_FamilleR03']=$listeR03;
					
					$listeClient="";
					if($_POST && !isset($_POST['btnReset2'])){
						if(isset($_POST['client0'])){
							if($listeClient<>""){$listeClient.=",";}
							$listeClient.=0;
						}
					}
					elseif($_GET){
						$listeClient=$_SESSION['FiltreRECORD_Client'];
					}
					else{
						if($listeClient<>""){$listeClient.=",";}
						$listeClient.=0;
					}
					
					if ($nbClient > 0)
					{	
						mysqli_data_seek($resultClient,0);
						while($row=mysqli_fetch_array($resultClient))
						{
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['client'.$row['Id']])){
									if($listeClient<>""){$listeClient.=",";}
									$listeClient.=$row['Id'];
								}
							}
							elseif(isset($_POST['btnReset2'])){
								if($listeClient<>""){$listeClient.=",";}
								$listeClient.=$row['Id'];
							}
						}
					}
					$_SESSION['FiltreRECORD_Client']=$listeClient;
					
					$listeDivisionClient="";
					if($_POST && !isset($_POST['btnReset2'])){
						if(isset($_POST['divisionclient0'])){
							if($listeDivisionClient<>""){$listeDivisionClient.=",";}
							$listeDivisionClient.=0;
						}
					}
					elseif($_GET){
						$listeDivisionClient=$_SESSION['FiltreRECORD_DivisionClient'];
					}
					else{
						if($listeDivisionClient<>""){$listeDivisionClient.=",";}
						$listeDivisionClient.=0;
					}
					
					if ($nbDivisionClient > 0)
					{	
						mysqli_data_seek($resultDivisionClient,0);
						while($row=mysqli_fetch_array($resultDivisionClient))
						{
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['divisionclient'.$row['Id']])){
									if($listeDivisionClient<>""){$listeDivisionClient.=",";}
									$listeDivisionClient.=$row['Id'];
								}
							}
							elseif(isset($_POST['btnReset2'])){
								if($listeDivisionClient<>""){$listeDivisionClient.=",";}
								$listeDivisionClient.=$row['Id'];
							}
						}
					}
					$_SESSION['FiltreRECORD_DivisionClient']=$listeDivisionClient;
					
					$listeEntiteAchat="";
					if($_POST && !isset($_POST['btnReset2'])){
						if(isset($_POST['entiteachat0'])){
							if($listeEntiteAchat<>""){$listeEntiteAchat.=",";}
							$listeEntiteAchat.=0;
						}
					}
					elseif($_GET){
						$listeEntiteAchat=$_SESSION['FiltreRECORD_EntiteAchat'];
					}
					else{
						if($listeEntiteAchat<>""){$listeEntiteAchat.=",";}
						$listeEntiteAchat.=0;
					}
					
					if ($nbEntiteAchat > 0)
					{	
						mysqli_data_seek($resultEntiteAchat,0);
						while($row=mysqli_fetch_array($resultEntiteAchat))
						{
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['entiteachat'.$row['Id']])){
									if($listeEntiteAchat<>""){$listeEntiteAchat.=",";}
									$listeEntiteAchat.=$row['Id'];
								}
							}
							elseif(isset($_POST['btnReset2'])){
								if($listeEntiteAchat<>""){$listeEntiteAchat.=",";}
								$listeEntiteAchat.=$row['Id'];
							}
						}
					}
					$_SESSION['FiltreRECORD_EntiteAchat']=$listeEntiteAchat;
					
					$listeRP="";
					if ($nbRP > 0)
					{	
						mysqli_data_seek($resultRP,0);
						while($row=mysqli_fetch_array($resultRP))
						{
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['RP'.$row['Id_Personne']])){
									if($listeRP<>""){$listeRP.=",";}
									$listeRP.=$row['Id_Personne'];
								}
							}
							elseif($_GET){
								if($listeRP<>""){$listeRP.=",";}
								$listeRP.=$row['Id_Personne'];
							}
						}
					}
					
					
					if($_POST && !isset($_POST['btnReset2'])){
						if(isset($_POST['RP0'])){
							$_SESSION['FiltreRECORD_RespProjet0']="1";
						}
						else{
							$_SESSION['FiltreRECORD_RespProjet0']="";
						}
					}
					elseif($_GET){
						$listeRP=$_SESSION['FiltreRECORD_RespProjet'];
					}
					else{
						$_SESSION['FiltreRECORD_RespProjet0']="";
					}
					$_SESSION['FiltreRECORD_RespProjet']=$listeRP;

					if($_POST && (isset($_POST['btnFiltrer2']) || isset($_POST['btn_actualiserFam']))){
					//Liste des prestations concernées + récupérer le nombre
					$req="SELECT new_competences_prestation.Id 
							FROM new_competences_prestation
							LEFT JOIN new_competences_plateforme
							ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
							WHERE new_competences_prestation.UtiliseMORIS>0 ";
					if($_SESSION['FiltreRECORD_Prestation']<>""){
						$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
					}
					$resultPrestation2V2=mysqli_query($bdd,$req);
					$nbPrestation2=mysqli_num_rows($resultPrestation2V2);
					
					$listePrestation2="-1";
					
					$resultPrestation2=array();
					
					if ($nbPrestation2 > 0)
					{
						$i=0;
						while($row=mysqli_fetch_array($resultPrestation2V2))
						{
							if($listePrestation2<>""){$listePrestation2.=",";}
							$listePrestation2.=$row['Id'];
							$resultPrestation2[$i]=array("Id" => $row['Id']);
							$i++;
						}
					}

					$moisEC=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");
					if($_SESSION['FiltreRECORD_NbMois']==12){
						$date_11Mois = date("Y-m-d",strtotime($moisEC." -11 month"));
					}
					elseif($_SESSION['FiltreRECORD_NbMois']==6){
						$date_11Mois = date("Y-m-d",strtotime($moisEC." -5 month"));
					}
					elseif($_SESSION['FiltreRECORD_NbMois']==3){
						$date_11Mois = date("Y-m-d",strtotime($moisEC." -2 month"));
					}
					
					//Productivité
					$productivite=array();
					$arrayMois=array();
					
					//Management 
					$arrayMoisLettre=array();
					$arrayManagement=array();
					
					//Compétences 
					$arrayCompetences=array();
					
					//OTD 
					$arrayOTD=array();
					$arrayOTD2=array();
					
					$array2OTD=array();
					$array2OTD2=array();
					
					//OQD 
					$arrayOQD=array();
					$arrayOQD2=array();
					
					$array2OQD=array();
					$array2OQD2=array();
					
					//PDP
					$arrayPDP=array();
					$arrayPDP2=array();
					
					//Securite 
					$arraySecurite=array();
					
					//NC / RC
					$arrayNbNC=array();
					$arrayLegendeNC=array("NC Niv 1","NC Niv 2","NC Niv 3","RC");

					//PRM
					$arrayNewPRM=array();
					$noteAnnee=0;
					$noteSemestre1=0;
					$noteSemestre2=0;
					$pourcentageDemande=0;
					$pourcentageRetour=0;
					
					$i=0;
					$i2=0;
					$ipdp=0;
					$iotd=0;
					$ioqd=0;
					$iprod=0;
					$iotd2=0;
					$ioqd2=0;
					$laDate=$date_11Mois;
					$nbVolumeMonoMax=20;
					
					$anneeDuJour_1=date("Y",strtotime(date('Y-m-1')." -2 month"));
					$moisDuJour_1=date("m",strtotime(date('Y-m-1')." -2 month"));
					
					$anneeDuJour=date("Y",strtotime(date('Y-m-1')." -1 month"));
					$moisDuJour=date("m",strtotime(date('Y-m-1')." -1 month"));
					
					$anneeDuJour1=date("Y",strtotime(date('Y-m-1')." 0 month"));
					$moisDuJour1=date("m",strtotime(date('Y-m-1')." 0 month"));
					$anneeDuJour2=date("Y",strtotime(date('Y-m-1')." 1 month"));
					$moisDuJour2=date("m",strtotime(date('Y-m-1')." 1 month"));
					$anneeDuJour3=date("Y",strtotime(date('Y-m-1')." 2 month"));
					$moisDuJour3=date("m",strtotime(date('Y-m-1')." 2 month"));
					$anneeDuJour4=date("Y",strtotime(date('Y-m-1')." 3 month"));
					$moisDuJour4=date("m",strtotime(date('Y-m-1')." 3 month"));
					$anneeDuJour5=date("Y",strtotime(date('Y-m-1')." 4 month"));
					$moisDuJour5=date("m",strtotime(date('Y-m-1')." 4 month"));
					$anneeDuJour6=date("Y",strtotime(date('Y-m-1')." 5 month"));
					$moisDuJour6=date("m",strtotime(date('Y-m-1')." 5 month"));
					$anneeDuJour7=date("Y",strtotime(date('Y-m-1')." 6 month"));
					$moisDuJour7=date("m",strtotime(date('Y-m-1')." 6 month"));
					
					$bFamilleIndefini=0;
					$listeFamilleIndefini="";
					$listeFamilleIndefini2="";
					if($_POST && !isset($_POST['btnReset2']) && !isset($_POST['btnFiltrer2'])){
						if(isset($_POST['Famille_0'])){$bFamilleIndefini=1;}
					}
					else{
						$bFamilleIndefini=1;
					}
					$req="SELECT DISTINCT Id_Famille
						FROM moris_moisprestation_famille
						LEFT JOIN moris_moisprestation
						ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
						WHERE moris_moisprestation.Suppr=0
						";
					$resultFamille=mysqli_query($bdd,$req);
					$nbResultaFamille=mysqli_num_rows($resultFamille);
					if($nbResultaFamille>0){
						while($rowFamille=mysqli_fetch_array($resultFamille)){
							if($listeFamilleIndefini2<>""){$listeFamilleIndefini2.=",";}
							$listeFamilleIndefini2.=$rowFamille['Id_Famille'];
							
							if($_POST && !isset($_POST['btnReset2']) && !isset($_POST['btnFiltrer2'])){
								if(isset($_POST['Famille_'.$rowFamille['Id_Famille']])){
									if($listeFamilleIndefini<>""){$listeFamilleIndefini.=",";}
									$listeFamilleIndefini.=$rowFamille['Id_Famille'];
								}
							}
							else{
								if($listeFamilleIndefini<>""){$listeFamilleIndefini.=",";}
								$listeFamilleIndefini.=$rowFamille['Id_Famille'];
							}
						}
					}
					
					if($bFamilleIndefini==0 && $listeFamilleIndefini==""){
						$bFamilleIndefini=1;
						$listeFamilleIndefini=$listeFamilleIndefini2;
						if($listeFamilleIndefini==""){$listeFamilleIndefini="0";}
					}
					$_SESSION['MORIS_ListeFamilleIndefini']=$listeFamilleIndefini;
					
					//Besoin staffing 
					$arrayBesoin=array();
					if($_SESSION['FiltreRECORD_Vision']==1){
						
						
						if($_SESSION['FiltreRECORD_NbMois']==12){
							$nbMois9=9;
							$nbMois15=15;
							$nbMois17=17;
						}
						elseif($_SESSION['FiltreRECORD_NbMois']==6){
							$nbMois9=3;
							$nbMois15=8;
							$nbMois17=10;
						}
						elseif($_SESSION['FiltreRECORD_NbMois']==3){
							$nbMois9=0;
							$nbMois15=3;
							$nbMois17=5;
						}
						
						$annee3Mois=date("Y",strtotime($date_11Mois." +".$nbMois9." month"));
						$mois3Mois=date("m",strtotime($date_11Mois." +".$nbMois9." month"));
						
						$annee6Mois=date("Y",strtotime($date_11Mois." +".$nbMois15." month"));
						$mois6Mois=date("m",strtotime($date_11Mois." +".$nbMois15." month"));
						
						$annee8Mois=date("Y",strtotime($date_11Mois." +".$nbMois17." month"));
						$mois8Mois=date("m",strtotime($date_11Mois." +".$nbMois17." month"));

						$reqFamille="SELECT DISTINCT Id_Famille,
						(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Famille
						FROM moris_moisprestation_famille
						LEFT JOIN moris_moisprestation
						ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
						WHERE Id_Famille>0
						AND moris_moisprestation.Suppr=0 ";
						if($annee3Mois.'_'.$mois3Mois>$anneeDuJour_1.'_'.$moisDuJour_1){
							$reqFamille.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJour_1.'_'.$moisDuJour_1."' ";
						}
						else{
							$reqFamille.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
						}
							
						$reqFamille.="
						AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee8Mois.'_'.$mois8Mois."'
						";
						if($listePrestation2<>""){
							$reqFamille.="AND Id_Prestation IN(".$listePrestation2.") ";
						}
						$reqFamille.="ORDER BY Famille";

						$productiviteBrutTotal=0;
						$productiviteCorrigeeTotal=0;
						$tempsPasseTotal=0;
						$tempsAlloueTotal=0;
						$tempsObjectifTotal=0;
						$nbBrut=0;
						$nbCorrige=0;
						
						$ConformeOTDTotal=0;
						$NonConformeOTDTotal=0;
						$ToleranceOTDTotal=0;
						
						$ConformeOTDTotal2=0;
						$NonConformeOTDTotal2=0;
						$ToleranceOTDTotal2=0;
						$nbOTDTotal=0;
						
						$ConformeOQDTotal=0;
						$NonConformeOQDTotal=0;
						$ToleranceOQDTotal=0;
						$nbOQDTotal=0;
						
						$ratioOTDInfTotal=0;
						$ratioOTDEgalTotal=0;
						$ratioOTDSupTotal=0;
						$nbratioOTDTotal=0;
						
						$ratioOQDInfTotal=0;
						$ratioOQDEgalTotal=0;
						$ratioOQDSupTotal=0;
						$nbratioOQDTotal=0;
						
						$nbVertPdp2Total=0;
						$nbOrangePdp2Total=0;
						$nbRougePdp2Total=0;
						$nbNoirPdp2Total=0;
						$nbPdp2Total=0;
						
						if($_SESSION['FiltreRECORD_NbMois']==12){
							$moisDebutChargeCapa=9;
						}
						elseif($_SESSION['FiltreRECORD_NbMois']==6){
							$moisDebutChargeCapa=3;
						}
						else{
							$moisDebutChargeCapa=0;
						}
						
						
						for($nbMois=1;$nbMois<=$_SESSION['FiltreRECORD_NbMois'];$nbMois++){
							$anneeEC=date("Y",strtotime($laDate." +0 month"));
							$moisEC=date("m",strtotime($laDate." +0 month"));
							
							$arrayMois[$i]=$MoisLettre[$moisEC-1]."<br>".date("y",strtotime($laDate." +0 month"));
							$arrayMoisLettre[$i]=$MoisLettre2[$moisEC-1];
							
							$mois_6Mois=date("m",strtotime($laDate." -6 month"));
							$annee_6Mois=date("Y",strtotime($laDate." -6 month"));
							
							$CapaInterne=0;
							$CapaExterne=0;
							$ChargeTotal=0;
							$CapaInternePrev=0;
							$CapaExternePrev=0;
							
							if($nbMois>$moisDebutChargeCapa){
								$laDate2=date("Y-m-d",strtotime($laDate." +0 month"));
								$annee_1=date("Y",strtotime($laDate2." -1 month"));
								$mois_1=date("m",strtotime($laDate2." -1 month"));
								$annee_2=date("Y",strtotime($laDate2." -2 month"));
								$mois_2=date("m",strtotime($laDate2." -2 month"));
								$annee_3=date("Y",strtotime($laDate2." -3 month"));
								$mois_3=date("m",strtotime($laDate2." -3 month"));
								$annee_4=date("Y",strtotime($laDate2." -4 month"));
								$mois_4=date("m",strtotime($laDate2." -4 month"));
								$annee_5=date("Y",strtotime($laDate2." -5 month"));
								$mois_5=date("m",strtotime($laDate2." -5 month"));
								$annee_6=date("Y",strtotime($laDate2." -6 month"));
								$mois_6=date("m",strtotime($laDate2." -6 month"));
								$annee_7=date("Y",strtotime($laDate2." -7 month"));
								$mois_7=date("m",strtotime($laDate2." -7 month"));
								

								foreach($resultPrestation2 as $rowPresta)
								{
									$req="SELECT ";
									if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0) AS InterneCurrent,";
									if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS SubContractorCurrent, ";
									$req.="
									IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
										PermanentCurrent+TemporyCurrent+InterneCurrent,
										COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaInterne,
									IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
										SubContractorCurrent,
										COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaExterne
									FROM moris_moisprestation
									LEFT JOIN new_competences_prestation
									ON moris_moisprestation.Id_Prestation=new_competences_prestation.Id
									WHERE moris_moisprestation.Annee=".$anneeEC." 
									AND moris_moisprestation.Mois=".$moisEC."
									AND moris_moisprestation.Suppr=0 
									AND new_competences_prestation.ChargeADesactive=0
									AND moris_moisprestation.Id_Prestation = ".$rowPresta['Id']." 
									AND ((";
									if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
									OR COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
									)";

									$resultEC2=mysqli_query($bdd,$req);
									$nbResultaMoisPrestaEC2=mysqli_num_rows($resultEC2);
									$nbInternePresta=0;
									$nbSubContractorPresta=0;
									if($nbResultaMoisPrestaEC2>0){
										$LigneMoisPrestationEC2=mysqli_fetch_array($resultEC2);

										$CapaInterne+=$LigneMoisPrestationEC2['CapaInterne'];
										$CapaExterne+=$LigneMoisPrestationEC2['CapaExterne'];
										$ChargeTotal+=$LigneMoisPrestationEC2['InterneCurrent']+$LigneMoisPrestationEC2['SubContractorCurrent'];
									}
									else{
										if($anneeEC."_".$moisEC>=$anneeDuJour."_".$moisDuJour && $anneeEC."_".$moisEC<=$anneeDuJour7."_".$moisDuJour7){
											//Rechercher la prévision sur l'un des mois précédent
											$req="SELECT moris_moisprestation.Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
											FROM moris_moisprestation
											LEFT JOIN new_competences_prestation
											ON moris_moisprestation.Id_Prestation=new_competences_prestation.Id
											WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ";
											if($anneeEC."_".$moisEC==$anneeDuJour."_".$moisDuJour){$req.="('".$annee_1."_".$mois_1."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour1."_".$moisDuJour1){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour2."_".$moisDuJour2){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour3."_".$moisDuJour3){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour4."_".$moisDuJour4){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour5."_".$moisDuJour5){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour6."_".$moisDuJour6){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour7."_".$moisDuJour7){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											$req.="
											AND moris_moisprestation.Suppr=0 
											AND new_competences_prestation.ChargeADesactive=0
											AND moris_moisprestation.Id_Prestation IN (".$rowPresta['Id'].") 
											ORDER BY Annee DESC, Mois DESC ";

											$resultEC3=mysqli_query($bdd,$req);
											$nbResultaMoisPrestaEC3=mysqli_num_rows($resultEC3);
											if($nbResultaMoisPrestaEC3>0){
												$LigneMoisPrestationEC3=mysqli_fetch_array($resultEC3);
												$leMoisCharge="";
												if($LigneMoisPrestationEC3['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
												elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
												elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
												elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
												elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
												elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
												elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_7."_".$mois_7){$leMoisCharge="6";}
												if($leMoisCharge<>""){
													//Rechercher la prévision sur l'un des mois précédent
													$req="SELECT ";
													if($bFamilleIndefini==1){$req.="M".$leMoisCharge."+";}
													$req.="COALESCE((SELECT SUM(M".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS M,";
													if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS MInterne,";			
													if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS MExterne,
													COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaInterne,
													COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaExterne
													FROM moris_moisprestation
													WHERE Id=".$LigneMoisPrestationEC3['Id']." ";
													$resultECF2=mysqli_query($bdd,$req);
													$nbResultaMoisPrestaECF2=mysqli_num_rows($resultECF2);
													if($nbResultaMoisPrestaECF2>0){
														$LigneMoisPrestationECF2=mysqli_fetch_array($resultECF2);
														$ChargeTotal+=$LigneMoisPrestationECF2['M'];
														$CapaInternePrev+=$LigneMoisPrestationECF2['CapaInterne'];
														$CapaExternePrev+=$LigneMoisPrestationECF2['CapaExterne'];
													}
												}
											}
										}
									}
								}
							}
							
							$req="SELECT Id,
							(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							(SELECT ProductiviteADesactive FROM new_competences_prestation WHERE Id=Id_Prestation) AS ProductiviteADesactive,
							(SELECT ToleranceOTDOQD FROM new_competences_prestation WHERE Id=Id_Prestation) AS ToleranceOTDOQD,
							Id_Prestation,TempsAlloue,TempsPasse,TempsObjectif,
							IF(ObjectifClientOTD=0,100,ObjectifClientOTD) AS ObjectifClientOTD,
							NbLivrableOTD,NbRetourClientOTD,OTD,
							ObjectifToleranceOTD,
							IF(ObjectifClientOQD=0,100,ObjectifClientOQD) AS ObjectifClientOQD,
							NbLivrableOQD,NbRetourClientOQD,OQD,ObjectifToleranceOQD,
							IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,PasOTD,
							IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,PasOQD,
							NbXTableauPolyvalence,NbLTableauPolyvalence,NbLivrableToleranceOTD,NbLivrableToleranceOQD,PasActivite,
							TendanceManagement,TauxQualif,NbMonoCompetence,
							EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
							FROM moris_moisprestation
							WHERE Annee=".$anneeEC." 
							AND Mois=".$moisEC."
							AND Suppr=0 											
							";
							if($listePrestation2<>""){
								$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
							}
							
							$resultEC=mysqli_query($bdd,$req);
							$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
							
							
							//Prestations OTD les plus faibles 
							$req="SELECT Id,
							(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							Id_Prestation,
							((IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD))/(IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD)+NbRetourClientOTD+NbLivrableToleranceOTD))*100 AS OTD
							FROM moris_moisprestation
							WHERE Annee=".$anneeEC." 
							AND Mois=".$moisEC."
							AND PasOTD=0
							AND PasActivite=0
							AND Suppr=0 
							AND (IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD)>0 OR NbRetourClientOTD>0 OR NbLivrableToleranceOTD>0) ";
							if($listePrestation2<>""){
								$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY IF(IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD)>0 OR NbRetourClientOTD>0 OR NbLivrableToleranceOTD>0,((IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD))/(IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD)+NbRetourClientOTD+NbLivrableToleranceOTD))*100,100)
							LIMIT 5
							";
							$resultOTD=mysqli_query($bdd,$req);
							$nbOTD=mysqli_num_rows($resultOTD);
							
							$listeOTD="";
							if($nbOTD>0){
								while($LigneOTD=mysqli_fetch_array($resultOTD)){
									$presta=substr($LigneOTD['Prestation'],0,strpos($LigneOTD['Prestation']," "));
									if($listeOTD<>""){$listeOTD.="<br>";}
									$listeOTD.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneOTD['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$presta." : ".round($LigneOTD['OTD'],1)."%</strong>";
								}
							}
							
							
							//Prestations OQD les plus faibles 
							$req="SELECT Id,
							(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							Id_Prestation,
							((IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD))/(IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD)+NbRetourClientOQD+NbLivrableToleranceOQD))*100 AS OQD
							FROM moris_moisprestation
							WHERE Annee=".$anneeEC." 
							AND Mois=".$moisEC."
							AND Suppr=0 
							AND PasOQD=0
							AND PasActivite=0
							AND (IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD)>0 OR NbRetourClientOQD>0 OR NbLivrableToleranceOQD>0) ";
							if($listePrestation2<>""){
								$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY IF(IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD)>0 OR NbRetourClientOQD>0 OR NbLivrableToleranceOQD>0,((IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD))/(IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD)+NbRetourClientOQD+NbLivrableToleranceOQD))*100,100)
							LIMIT 5
							";
							$resultOQD=mysqli_query($bdd,$req);
							$nbOQD=mysqli_num_rows($resultOQD);
							
							$listeOQD="";
							if($nbOQD>0){
								while($LigneOQD=mysqli_fetch_array($resultOQD)){
									$presta=substr($LigneOQD['Prestation'],0,strpos($LigneOQD['Prestation']," "));
									if($listeOQD<>""){$listeOQD.="<br>";}
									$listeOQD.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneOQD['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$presta." : ".round($LigneOQD['OQD'],1)."%</strong>";
								}
							}
							
							
							//Prestations productivité brut les plus faibles 
							$req="SELECT Id,
							(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							Id_Prestation,
							TempsObjectif/TempsPasse AS ProductiviteBrut
							FROM moris_moisprestation
							WHERE Annee=".$anneeEC." 
							AND Mois=".$moisEC."
							AND ProductiviteDesactive=0
							AND PasActivite=0
							AND Suppr=0 
							AND TempsPasse>0
							AND TempsAlloue>0
							AND tempsObjectif>0
							";
							if($listePrestation2<>""){
								$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY ProductiviteBrut
							LIMIT 5
							";
							$resultProd=mysqli_query($bdd,$req);
							$nbProd=mysqli_num_rows($resultProd);

							$listeBrut="";
							if($nbProd>0){
								while($LigneProd=mysqli_fetch_array($resultProd)){
									$presta=substr($LigneProd['Prestation'],0,strpos($LigneProd['Prestation']," "));
									if($listeBrut<>""){$listeBrut.="<br>";}
									$listeBrut.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneProd['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$presta." : ".round($LigneProd['ProductiviteBrut'],2)."</strong>";
								}
							}
							
							//Prestations productivité corrigée les plus faibles 
							$req="SELECT Id,
							(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							Id_Prestation,
							TempsAlloue/TempsPasse AS ProductiviteCorrigee
							FROM moris_moisprestation
							WHERE Annee=".$anneeEC." 
							AND Mois=".$moisEC."
							AND ProductiviteDesactive=0
							AND PasActivite=0
							AND Suppr=0  
							AND TempsPasse>0
							AND TempsAlloue>0
							AND tempsObjectif>0
							";
							if($listePrestation2<>""){
								$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY ProductiviteCorrigee
							LIMIT 5
							";
							$resultProd=mysqli_query($bdd,$req);
							$nbProd=mysqli_num_rows($resultProd);
							
							$listeCorrigee="";
							if($nbProd>0){
								while($LigneProd=mysqli_fetch_array($resultProd)){
									$presta=substr($LigneProd['Prestation'],0,strpos($LigneProd['Prestation']," "));
									if($listeCorrigee<>""){$listeCorrigee.="<br>";}
									$listeCorrigee.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneProd['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$presta." : ".round($LigneProd['ProductiviteCorrigee'],2)."</strong>";
								}
							}
							
							$productiviteBrut=0;
							$productiviteCorrigee=0;
							$tempsPasse=0;
							$tempsAlloue=0;
							$tempsObjectif=0;
							
							
							$ConformeOTD=0;
							$NonConformeOTD=0;
							$ToleranceOTD=0;
							$ConformeOTD2=0;
							$NonConformeOTD2=0;
							$ToleranceOTD2=0;
							
							$ConformeOQD=0;
							$NonConformeOQD=0;
							$ToleranceOQD=0;
							$ConformeOQD2=0;
							$NonConformeOQD2=0;
							$ToleranceOQD2=0;

							$ratioCompetence=0;
							$ratioQualif=0;
							$nbAccidentTrajet=0;
							$nbAvecArret=0;
							$nbSansArret=0;
							$listeAT="";
							$listeTrajet="";
							$listeSansAT="";
							$listeATv2="";
							$listeTrajetv2="";
							$listeSansATv2="";
							$nbNC=0;
							$nbNC2=0;
							$nbNC3=0;
							$nbRC=0;
							$listeNC="";
							$listeNC2="";
							$listeNC3="";
							$listeRC="";
							$listeNCv2="";
							$listeNC2v2="";
							$listeNC3v2="";
							$listeRCv2="";
							$nbVert=0;
							$nbOrange=0;
							$nbRouge=0;
							$nbPresta=0;
							$nbPrestaProd=0;
							$nbPrestaOTD=0;
							$nbPrestaOQD=0;
							$nbPrestaCompetence=0;
							$nbPrestaQualif=0;
							$nbMonoCompetences=0;
							$nbActiviteMonoCompetences=0;
							$nbPrestaPdp=0;
							$listeNoir="";
							$listeRouge="";
							$listeOrange="";
							$nbPrestaOTD2=0;
							
							$ratioOTDInf=0;
							$ratioOTDEgal=0;
							$ratioOTDSup=0;
							$ratioOQDInf=0;
							$ratioOQDEgal=0;
							$ratioOQDSup=0;
							
							$ratioOTDInf2=0;
							$ratioOTDEgal2=0;
							$ratioOTDSup2=0;
							$ratioOQDInf2=0;
							$ratioOQDEgal2=0;
							$ratioOQDSup2=0;
							
							$nbPrestaOQD2=0;
							$listeOTDInf="";
							$listeOQDInf="";
							
							$ObjectifProductivite=null;
							$ObjectifOTD=null;
							$ObjectifOQD=null;
							$ObjectifOTDActivite=null;
							$ObjectifOQDActivite=null;
							$ObjectifPDP=null;
							$ObjectifSatisfactionClient=null;
							$ObjectifTauxQualif=null;
							$ObjectifTauxPolyvalence=null;
							
							//Recherche Objectif E/C 
							$req="SELECT Theme, Pourcentage
							FROM moris_objectifglobal
							WHERE CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT(0,MONTH(DateDebut)),MONTH(DateDebut)))<='".$anneeEC."_".$moisEC."' 
							AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT(0,MONTH(DateFin)),MONTH(DateFin)))>='".$anneeEC."_".$moisEC."' OR DateFin<='0001-01-01') ";
							$resultObj=mysqli_query($bdd,$req);
							$nbObj=mysqli_num_rows($resultObj);
							if($nbObj>0){
								while($rowObj=mysqli_fetch_array($resultObj)){
									switch($rowObj['Theme']){
										case "OTD activité":
											$ObjectifOTDActivite=$rowObj['Pourcentage'];
											break;
										case "OTD livrable":
											$ObjectifOTD=$rowObj['Pourcentage'];
											break;
										case "OQD activité":
											$ObjectifOQDActivite=$rowObj['Pourcentage'];
											break;
										case "OQD livrable":
											$ObjectifOQD=$rowObj['Pourcentage'];
											break;
										case "Productivité corrigée":
											$ObjectifProductivite=$rowObj['Pourcentage'];
											break;
										case "Satisfaction client":
											$ObjectifSatisfactionClient=$rowObj['Pourcentage'];
											break;
										case "Taux de qualification":
											$ObjectifTauxQualif=$rowObj['Pourcentage'];
											break;
										case "Taux de polyvalence":
											$ObjectifTauxPolyvalence=$rowObj['Pourcentage'];
											break;
										case "Plan de prévention":
											$ObjectifPDP=$rowObj['Pourcentage'];
											break;
									}
								}
							}
							$req="SELECT Id_Prestation FROM
							(SELECT *
							FROM 
							(SELECT Id_Prestation,Annee,Mois,RefPdp,DateValidite,(@row_number:=@row_number + 1) AS rnk
							FROM moris_pdp
							WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois))<='".$anneeEC."_".$moisEC."'
							AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
							AND (
								SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
								AND moris_datesuivi.Suppr=0 
								AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
							)>0
							";
							if($listePrestation2<>""){
								$req.="AND Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY Id_Prestation,Annee DESC, Mois DESC) AS TAB
							GROUP BY Id_Prestation) AS TAB2
							WHERE (TAB2.DateValidite>'0001-01-01')
							AND TAB2.DateValidite>='".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +2 month"))."'							
							";
							
							$resultPdp=mysqli_query($bdd,$req);
							$nbVertPdp=mysqli_num_rows($resultPdp);
							
							
							$req="SELECT Id_Prestation,(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation FROM
							(SELECT *
							FROM 
							(SELECT Id_Prestation,Annee,Mois,RefPdp,DateValidite,(@row_number:=@row_number + 1) AS rnk
							FROM moris_pdp
							WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois))<='".$anneeEC."_".$moisEC."'
							AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
							AND (
								SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
								AND moris_datesuivi.Suppr=0 
								AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
							)>0
							";
							if($listePrestation2<>""){
								$req.="AND Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY Id_Prestation,Annee DESC, Mois DESC) AS TAB
							GROUP BY Id_Prestation) AS TAB2
							WHERE (DateValidite>'0001-01-01')	
							AND DateValidite>='".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +1 month"))."'	
							AND DateValidite<'".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +2 month"))."'	
							ORDER BY Prestation
							";
							$resultPdp=mysqli_query($bdd,$req);
							$nbOrangePdp=mysqli_num_rows($resultPdp);
							if($nbOrangePdp>0){
								$nb=1;
								while($rowPdp=mysqli_fetch_array($resultPdp)){
									if($listeOrange<>""){$listeOrange.=", ";}
									if($nb==6){$listeOrange.="<br>";$nb=0;}
									$listeOrange.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$rowPdp['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$rowPdp['Prestation']."</strong>";
									$nb++;
								}
							}
							
							
							$req="SELECT Id_Prestation,(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation FROM
							(SELECT *
							FROM 
							(SELECT Id_Prestation,Annee,Mois,RefPdp,DateValidite,(@row_number:=@row_number + 1) AS rnk
							FROM moris_pdp
							WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois))<='".$anneeEC."_".$moisEC."'
							AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
							AND (
								SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
								AND moris_datesuivi.Suppr=0 
								AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
							)>0
							";
							if($listePrestation2<>""){
								$req.="AND Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="ORDER BY Id_Prestation,Annee DESC, Mois DESC) AS TAB
							GROUP BY Id_Prestation) AS TAB2
							WHERE (DateValidite>'0001-01-01')
							AND DateValidite<'".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +1 month"))."'	
							ORDER BY Prestation
							";
							$resultPdp=mysqli_query($bdd,$req);
							$nbRougePdp=mysqli_num_rows($resultPdp);
							if($nbRougePdp>0){
								$nb=1;
								while($rowPdp=mysqli_fetch_array($resultPdp)){
									if($listeRouge<>""){$listeRouge.=", ";}
									if($nb==6){$listeRouge.="<br>";$nb=0;}
									$listeRouge.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$rowPdp['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$rowPdp['Prestation']."</strong>";
									$nb++;
								}
							}
							
							$req="SELECT Id_Prestation,(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
							FROM
							(SELECT *
							FROM 
							(SELECT Id_Prestation,Annee,Mois,RefPdp,DateValidite,(@row_number:=@row_number + 1) AS rnk
							FROM moris_pdp
							WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois))<='".$anneeEC."_".$moisEC."'
							AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
							AND (
								SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
								AND moris_datesuivi.Suppr=0 
								AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
							)>0
							";
							if($listePrestation2<>""){
								$req.="AND Id_Prestation IN (".$listePrestation2.") ";
							}
							$req.="
							UNION 
							SELECT Id AS Id_Prestation,'' AS Annee,'' AS Mois,'' AS RefPdp,'0001-01-01' AS DateValidite,(@row_number:=@row_number + 1) AS rnk
							FROM new_competences_prestation
							WHERE PlanPreventionADesactivite=0
							AND (
								SELECT COUNT(DateDebut) 
								FROM moris_datesuivi 
								WHERE moris_datesuivi.Id_Prestation=new_competences_prestation.Id
								AND moris_datesuivi.Suppr=0 
								AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
							)>0
							AND (SELECT COUNT(Id_Prestation)
								FROM moris_pdp
								WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois))<='".$anneeEC."_".$moisEC."'
								AND Id_Prestation=new_competences_prestation.Id
								)=0
							";
							if($listePrestation2<>""){
								$req.="AND new_competences_prestation.Id IN (".$listePrestation2.") ";
							}
							$req.="
							ORDER BY Id_Prestation,Annee DESC, Mois DESC
							) AS TAB
							GROUP BY Id_Prestation) AS TAB2
							WHERE (DateValidite<='0001-01-01')
							ORDER BY Prestation
							";

							$resultPdp=mysqli_query($bdd,$req);
							$nbNoirPdp=mysqli_num_rows($resultPdp);
							if($nbNoirPdp>0){
								$nb=1;
								$leNombre=0;
								while($rowPdp=mysqli_fetch_array($resultPdp)){
									if($listeNoir<>""){$listeNoir.=", ";}
									if($nb==6){$listeNoir.="<br>";$nb=0;}
									$listeNoir.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$rowPdp['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$rowPdp['Prestation']."</strong>";
									$nb++;
									$leNombre++;
								}
								$nbNoirPdp=$leNombre;
							}
							
							$nbPrestaPdp=$nbVertPdp+$nbOrangePdp+$nbRougePdp+$nbNoirPdp;
							
							if($nbResultaMoisPrestaEC>0){
								while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC))
								{
									
									if($LigneMoisPrestationEC['ProductiviteADesactive']==0 && $LigneMoisPrestationEC['PasActivite']==0){
										if($LigneMoisPrestationEC['TempsPasse']>0 && $LigneMoisPrestationEC['TempsAlloue']>0 && $LigneMoisPrestationEC['TempsObjectif']>0){
											$tempsPasse+=$LigneMoisPrestationEC['TempsPasse'];
											$tempsAlloue+=$LigneMoisPrestationEC['TempsAlloue'];
											$tempsObjectif+=$LigneMoisPrestationEC['TempsObjectif'];
											
											if($_SESSION['MORIS_Annee']==$anneeEC){
												$tempsPasseTotal+=$LigneMoisPrestationEC['TempsPasse'];
												$tempsAlloueTotal+=$LigneMoisPrestationEC['TempsAlloue'];
												$tempsObjectifTotal+=$LigneMoisPrestationEC['TempsObjectif'];
											}
											$nbPrestaProd++;
										}
									}
									
									if($LigneMoisPrestationEC['PasOTD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
										if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0){
											$ConformeOTD2+=$LigneMoisPrestationEC['NbLivrableConformeOTD'];
											$NonConformeOTD2+=$LigneMoisPrestationEC['NbRetourClientOTD'];
											$ToleranceOTD2+=$LigneMoisPrestationEC['NbLivrableToleranceOTD'];
											$nbPrestaOTD++;
										}
										
										if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0 && $LigneMoisPrestationEC['ObjectifClientOTD']>0){
											$ratio=0;
											if($LigneMoisPrestationEC['NbLivrableConformeOTD']>0){
												$ratio=round(($LigneMoisPrestationEC['NbLivrableConformeOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
											}
											if($ratio>=$LigneMoisPrestationEC['ObjectifClientOTD']){
												$ratioOTDSup++;$ratioOTDSup2++;
											}
											else{
												if($LigneMoisPrestationEC['ToleranceOTDOQD']==0){
													$ratioOTDInf++;$ratioOTDInf2++;
													if($listeOTDInf<>""){$listeOTDInf.="<br>";}
													$listeOTDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOTD']."%</strong>";
												}
												else{
													if($ratio>=$LigneMoisPrestationEC['ObjectifToleranceOTD']){
														$ratioOTDEgal++;$ratioOTDEgal2++;
													}
													else{
														$ratioOTDInf++;$ratioOTDInf2++;
														if($listeOTDInf<>""){$listeOTDInf.="<br>";}
														$listeOTDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOTD']."%</strong>";
													}
												}
											}
											$nbPrestaOTD2++;
										}
									}
	
									if($LigneMoisPrestationEC['PasOQD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
										if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0){
											$ConformeOQD2+=$LigneMoisPrestationEC['NbLivrableConformeOQD'];
											$NonConformeOQD2+=$LigneMoisPrestationEC['NbRetourClientOQD'];
											$ToleranceOQD2+=$LigneMoisPrestationEC['NbLivrableToleranceOQD'];
											$nbPrestaOQD++;
										}
										
										if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0 && $LigneMoisPrestationEC['ObjectifClientOQD']>0){
											$ratio=0;
											if($LigneMoisPrestationEC['NbLivrableConformeOQD']>0){
												$ratio=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
											}
											if($ratio>=$LigneMoisPrestationEC['ObjectifClientOQD']){
												$ratioOQDSup++;
												$ratioOQDSup2++;
											}
											else{
												if($LigneMoisPrestationEC['ToleranceOTDOQD']==0){
													$ratioOQDInf++;
													$ratioOQDInf2++;
													if($listeOQDInf<>""){$listeOQDInf.="<br>";}
													$listeOQDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOQD']."%</strong>";
												}
												else{
													if($ratio>=$LigneMoisPrestationEC['ObjectifToleranceOQD']){
														$ratioOQDEgal++;$ratioOQDEgal2++;
													}
													else{
														$ratioOQDInf++;
														$ratioOQDInf2++;
														if($listeOQDInf<>""){$listeOQDInf.="<br>";}
														$listeOQDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOQD']."%</strong>";
													}
												}
											}
											$nbPrestaOQD2++;
										}
									}

									$nbPresta++;
									if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0){
										$ratioCompetence+= round(($LigneMoisPrestationEC['NbXTableauPolyvalence']/($LigneMoisPrestationEC['NbXTableauPolyvalence']+$LigneMoisPrestationEC['NbLTableauPolyvalence']))*100,2);
										$nbPrestaCompetence++;
									}
									if($LigneMoisPrestationEC['TauxQualif']>0){
										$ratioQualif+=$LigneMoisPrestationEC['TauxQualif'];
										$nbPrestaQualif++;
									}
									
									if($LigneMoisPrestationEC['NbMonoCompetence']>0){
										$nbMonoCompetences+=$LigneMoisPrestationEC['NbMonoCompetence'];
										$nbActiviteMonoCompetences++;
									}
									
									
									$req="SELECT Id FROM moris_moisprestation_securite 
										WHERE Suppr=0 
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
										AND AvecArret=1	
										AND AccidentTrajet=0 
										UNION
										SELECT Id
										FROM rh_personne_at 
										WHERE rh_personne_at.Suppr=0 
										AND rh_personne_at.ArretDeTravail=1
										AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
										AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."' ";
									$resultSecurite=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultSecurite);
									$nbAvecArret+=$nb;
									if($nb>0){
										if($listeAT<>""){$listeAT.=", ";}
										$listeAT.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeATv2<>""){$listeATv2.=", ";}
										$listeATv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									}	
									
									$req="SELECT Id FROM moris_moisprestation_securite 
										WHERE Suppr=0 
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
										AND AvecArret=0
										AND AccidentTrajet=0 
										UNION
										SELECT Id
										FROM rh_personne_at 
										WHERE rh_personne_at.Suppr=0 
										AND rh_personne_at.ArretDeTravail=0
										AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
										AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
									$resultSecurite=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultSecurite);
									$nbSansArret+=$nb;
									if($nb>0){
										if($listeSansAT<>""){$listeSansAT.=", ";}
										$listeSansAT.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeSansATv2<>""){$listeSansATv2.=", ";}
										$listeSansATv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									}
									
									
									$req="SELECT Id FROM moris_moisprestation_securite 
										WHERE Suppr=0 
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
										AND AccidentTrajet=1 
										UNION
										SELECT Id
										FROM rh_personne_at 
										WHERE rh_personne_at.Suppr=0 
										AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)>0
										AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
									$resultSecurite=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultSecurite);
									$nbAccidentTrajet+=$nb;
									if($nb>0){
										if($listeTrajet<>""){$listeTrajet.=", ";}
										$listeTrajet.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeTrajetv2<>""){$listeTrajetv2.=", ";}
										$listeTrajetv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									}
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='NC'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultNC);
									$nbNC+=$nb;
									if($nb>0){
										if($listeNC<>""){$listeNC.=", ";}
										$listeNC.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeNCv2<>""){$listeNCv2.=", ";}
										$listeNCv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									}
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='NC Niv 2'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultNC);
									$nbNC2+=$nb;
									if($nb>0){
										if($listeNC2<>""){$listeNC2.=", ";}
										$listeNC2.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeNC2v2<>""){$listeNC2v2.=", ";}
										$listeNC2v2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									}
	
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='NC Niv 3'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultNC);
									$nbNC3+=$nb;
									if($nb>0){
										if($listeNC3<>""){$listeNC3.=", ";}
										$listeNC3.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeNC3v2<>""){$listeNC3v2.=", ";}
										$listeNC3v2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									
									}
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='RC'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultNC);
									$nbRC+=$nb;
									if($nb>0){
										if($listeRC<>""){$listeRC.=", ";}
										$listeRC.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
										if($listeRCv2<>""){$listeRCv2.=", ";}
										$listeRCv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
									}
								
									if($LigneMoisPrestationEC['PasActivite']==0){
										if($LigneMoisPrestationEC['TendanceManagement']==0){$nbVert++;}
										elseif($LigneMoisPrestationEC['TendanceManagement']==1){$nbOrange++;}
										elseif($LigneMoisPrestationEC['TendanceManagement']==2){$nbRouge++;}
									}

								}
								
							}
							
							
							
							if($nbPrestaCompetence>0){
								$ratioCompetence=round($ratioCompetence/$nbPrestaCompetence,2);
							}
							if($nbPrestaQualif>0){
								$ratioQualif=round($ratioQualif/$nbPrestaQualif,2);
							}
							if($nbPrestaOTD>0){
								$ConformeOTD=round(($ConformeOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
								$NonConformeOTD=round(($NonConformeOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
								$ToleranceOTD=round(($ToleranceOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
							}
							if($nbPrestaOQD>0){
								$ConformeOQD=round(($ConformeOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
								$NonConformeOQD=round(($NonConformeOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
								$ToleranceOQD=round(($ToleranceOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
							}
							
							if($nbPrestaOTD2>0){
								$ratioOTDInf=round(($ratioOTDInf/$nbPrestaOTD2)*100,1);
								$ratioOTDEgal=round(($ratioOTDEgal/$nbPrestaOTD2)*100,1);
								$ratioOTDSup=round(($ratioOTDSup/$nbPrestaOTD2)*100,1);
							}
							if($nbPrestaOQD2>0){
								$ratioOQDInf=round(($ratioOQDInf/$nbPrestaOQD2)*100,1);
								$ratioOQDEgal=round(($ratioOQDEgal/$nbPrestaOQD2)*100,1);
								$ratioOQDSup=round(($ratioOQDSup/$nbPrestaOQD2)*100,1);
							}
							
							$nbVertPdp2=0;
							$nbOrangePdp2=0;
							$nbRougePdp2=0;
							$nbNoirPdp2=0;
							if($nbPrestaPdp>0){
								$nbVertPdp2=round(($nbVertPdp/$nbPrestaPdp)*100,1);
								$nbOrangePdp2=round(($nbOrangePdp/$nbPrestaPdp)*100,1);
								$nbRougePdp2=round(($nbRougePdp/$nbPrestaPdp)*100,1);
								$nbNoirPdp2=round(($nbNoirPdp/$nbPrestaPdp)*100,1);
							}
							
							if($nbMois>$moisDebutChargeCapa){
								$arrayBesoin[$i-$moisDebutChargeCapa]=array("Mois" => $MoisLettre[$moisEC-1]." ".$anneeEC,"Interne" => valeurSinonNull($CapaInterne),"SubContractor" => valeurSinonNull($CapaExterne),"Prevision" => valeurSinonNull($ChargeTotal), "InternePrevi" => valeurSinonNull($CapaInternePrev), "ExternePrevi" => valeurSinonNull($CapaExternePrev));
							}
							
							$productiviteBrut=null;
							if($tempsPasse>0){
								$productiviteBrut=round($tempsObjectif/$tempsPasse,2);
							}
							$productiviteCorrigee=null;
							if($tempsPasse>0){
								$productiviteCorrigee=round($tempsAlloue/$tempsPasse,2);
							}
							
							if($_SESSION['MORIS_VisionMonoCompetence']==0){
								if($nbVolumeMonoMax<$nbActiviteMonoCompetences){
									$nbVolumeMonoMax=$nbActiviteMonoCompetences+50;
								}
							}
							else{
								if($nbVolumeMonoMax<$nbMonoCompetences){
									$nbVolumeMonoMax=$nbMonoCompetences+50;
								}
							}
							$arrayPDP[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbVert" => valeurSinonNull($nbVertPdp2),"NbOrange" => valeurSinonNull($nbOrangePdp2),"NbRouge" => valeurSinonNull($nbRougePdp2),"NbNoir" => valeurSinonNull($nbNoirPdp2),"Objectif" => valeurSinonNull($ObjectifPDP));
							$arrayPDP2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbVert" => valeurSinonNull($nbVertPdp),"NbOrange" =>valeurSinonNull( $nbOrangePdp),"NbRouge" => valeurSinonNull($nbRougePdp),"NbNoir" => valeurSinonNull($nbNoirPdp),"listeOrange" => $listeOrange,"listeRouge" => $listeRouge,"listeNoir" => $listeNoir);
							$arrayOTD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => valeurSinonNull($ConformeOTD),"NbTolerance" => valeurSinonNull($ToleranceOTD),"NbRetour" => valeurSinonNull($NonConformeOTD),"Objectif" => valeurSinonNull($ObjectifOTD),"ValeurNbConforme" => valeurSinonNull($ConformeOTD2),"ValeurNbTolerance" => valeurSinonNull($ToleranceOTD2),"ValeurNbRetour" => valeurSinonNull($NonConformeOTD2));
							$arrayOTD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => valeurSinonNull($ConformeOTD2),"NbTolerance" => valeurSinonNull($ToleranceOTD2),"NbRetour" => valeurSinonNull($NonConformeOTD2),"OTD" => valeurSinonNull($ConformeOTD),"liste" => $listeOTD,"Objectif" => valeurSinonNull($ObjectifOTD));
							
							$array2OTD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"ratioInf" => valeurSinonNull($ratioOTDInf),"ratioEgal" => valeurSinonNull($ratioOTDEgal),"ratioSup" => valeurSinonNull($ratioOTDSup),"ValeurratioInf" => valeurSinonNull($ratioOTDInf2),"ValeurratioEgal" => valeurSinonNull($ratioOTDEgal2),"ValeurratioSup" => valeurSinonNull($ratioOTDSup2),"Objectif" => valeurSinonNull($ObjectifOTDActivite));
							$array2OTD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"ratioInf" => valeurSinonNull($ratioOTDInf2),"ratioEgal" => valeurSinonNull($ratioOTDEgal2),"ratioSup" => valeurSinonNull($ratioOTDSup2),"listeOTDInf" => $listeOTDInf,"Objectif" => valeurSinonNull($ObjectifOTDActivite));
							
							$arrayOQD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => valeurSinonNull($ConformeOQD),"NbTolerance" => valeurSinonNull($ToleranceOQD),"NbRetour" => valeurSinonNull($NonConformeOQD),"Objectif" => valeurSinonNull($ObjectifOQD));
							$arrayOQD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => valeurSinonNull($ConformeOQD2),"NbTolerance" => valeurSinonNull($ToleranceOQD2),"NbRetour" => valeurSinonNull($NonConformeOQD2),"OQD" => valeurSinonNull($ConformeOQD),"liste" => $listeOQD,"Objectif" => valeurSinonNull($ObjectifOQD));
							
							$array2OQD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"ratioInf" => valeurSinonNull($ratioOQDInf),"ratioEgal" => valeurSinonNull($ratioOQDEgal),"ratioSup" => valeurSinonNull($ratioOQDSup),"Objectif" => valeurSinonNull($ObjectifOQDActivite));
							$array2OQD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"ratioInf" => valeurSinonNull($ratioOQDInf2),"ratioEgal" => valeurSinonNull($ratioOQDEgal2),"ratioSup" => valeurSinonNull($ratioOQDSup2),"listeOQDInf" => $listeOQDInf,"Objectif" => valeurSinonNull($ObjectifOQDActivite));
							$productivite[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"ProductiviteBrut" => valeurSinonNull($productiviteBrut),"Objectif" => valeurSinonNull($ObjectifProductivite),"ProductiviteCorrigee" => valeurSinonNull($productiviteCorrigee),"listeBrut" => $listeBrut,"listeCorrigee" => $listeCorrigee);
							$arrayCompetences[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","Competences" => valeurSinonNull($ratioCompetence),"TauxQualif" => valeurSinonNull($ratioQualif),"NbMonoCompetence" => valeurSinonNull($nbMonoCompetences),"NbActiviteMonoCompetences" => valeurSinonNull($nbActiviteMonoCompetences),"ObjectifTauxQualif" => valeurSinonNull($ObjectifTauxQualif),"ObjectifTauxPolyvalence" => valeurSinonNull($ObjectifTauxPolyvalence));
							$arraySecurite[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NbTrajet" => valeurSinonNull($nbAccidentTrajet),"NbNonTrajetAvecArret" => valeurSinonNull($nbAvecArret),"NbNonTrajetSansArret" => valeurSinonNull($nbSansArret),"listeAT" => $listeAT,"listeSansAT" => $listeSansAT, "listeTrajet" => $listeTrajet,"listeATv2" => $listeATv2,"listeSansATv2" => $listeSansATv2, "listeTrajetv2" => $listeTrajetv2);
							$arrayNbNC[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NC1" => valeurSinonNull($nbNC),"NC2" => valeurSinonNull($nbNC2),"NC3" => valeurSinonNull($nbNC3),"RC" => valeurSinonNull($nbRC),"listeNC" => $listeNC,"listeNC2" => $listeNC2,"listeNC3" => $listeNC3,"listeRC" => $listeRC,"listeNCv2" => $listeNCv2,"listeNC2v2" => $listeNC2v2,"listeNC3v2" => $listeNC3v2,"listeRCv2" => $listeRCv2);
							$arrayManagement[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NbVert" => valeurSinonNull($nbVert),"NbOrange" => valeurSinonNull($nbOrange),"NbRouge" => valeurSinonNull($nbRouge));
							
							if($anneeEC==$_SESSION['MORIS_Annee'] && $moisEC<=$_SESSION['MORIS_Mois']){
								if($ConformeOTD2>0 || $NonConformeOTD2>0 || $ToleranceOTD2>0){
									$ConformeOTDTotal+=$ConformeOTD2;
									$NonConformeOTDTotal+=$NonConformeOTD2;
									$ToleranceOTDTotal+=$ToleranceOTD2;
								}
								if($ConformeOQD2>0 || $NonConformeOQD2>0 || $ToleranceOQD2>0){
									$ConformeOQDTotal+=$ConformeOQD2;
									$NonConformeOQDTotal+=$NonConformeOQD2;
									$ToleranceOQDTotal+=$ToleranceOQD2;
								}
								if($ratioOTDInf2>0 || $ratioOTDEgal2>0 || $ratioOTDSup2>0){
									$ratioOTDInfTotal+=$ratioOTDInf2;
									$ratioOTDEgalTotal+=$ratioOTDEgal2;
									$ratioOTDSupTotal+=$ratioOTDSup2;
									$nbratioOTDTotal++;
								}
								if($ratioOQDInf2>0 || $ratioOQDEgal2>0 || $ratioOQDSup2>0){
									$ratioOQDInfTotal+=$ratioOQDInf2;
									$ratioOQDEgalTotal+=$ratioOQDEgal2;
									$ratioOQDSupTotal+=$ratioOQDSup2;
									$nbratioOQDTotal++;
								}
								if($nbVertPdp>0 || $nbOrangePdp>0 || $nbRougePdp>0 || $nbNoirPdp=0){
									$nbVertPdp2Total+=$nbVertPdp;
									$nbOrangePdp2Total+=$nbOrangePdp;
									$nbRougePdp2Total+=$nbRougePdp;
									$nbNoirPdp2Total+=$nbNoirPdp;
									$nbPdp2Total++;
								}
							}
	
							$i++;
							$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
						}
						
						$ConformeOTDTotal2=$ConformeOTDTotal;
						$NonConformeOTDTotal2=$NonConformeOTDTotal;
						$ToleranceOTDTotal2=$ToleranceOTDTotal;

						$ConformeOQDTotal2=$ConformeOQDTotal;
						$NonConformeOQDTotal2=$NonConformeOQDTotal;
						$ToleranceOQDTotal2=$ToleranceOQDTotal;
						
						$ConformeOTDTotal=null;
						$NonConformeOTDTotal=null;
						$ToleranceOTDTotal=null;
						if($ConformeOTDTotal2>0 || $NonConformeOTDTotal2>0 || $ToleranceOTDTotal2>0){
							$ConformeOTDTotal=round(($ConformeOTDTotal2/($ConformeOTDTotal2+$NonConformeOTDTotal2+$ToleranceOTDTotal2))*100,1);
							$NonConformeOTDTotal=round(($NonConformeOTDTotal2/($ConformeOTDTotal2+$NonConformeOTDTotal2+$ToleranceOTDTotal2))*100,1);
							$ToleranceOTDTotal=round(($ToleranceOTDTotal2/($ConformeOTDTotal2+$NonConformeOTDTotal2+$ToleranceOTDTotal2))*100,1);
						}
						
						$ConformeOQDTotal=null;
						$NonConformeOQDTotal=null;
						$ToleranceOQDTotal=null;
						if($ConformeOQDTotal2>0 || $NonConformeOQDTotal2>0 || $ToleranceOQDTotal2>0){
							$ConformeOQDTotal=round(($ConformeOQDTotal2/($ConformeOQDTotal2+$NonConformeOQDTotal2+$ToleranceOQDTotal2))*100,1);
							$NonConformeOQDTotal=round(($NonConformeOQDTotal2/($ConformeOQDTotal2+$NonConformeOQDTotal2+$ToleranceOQDTotal2))*100,1);
							$ToleranceOQDTotal=round(($ToleranceOQDTotal2/($ConformeOQDTotal2+$NonConformeOQDTotal2+$ToleranceOQDTotal2))*100,1);
						}
						
						$ratioOTDInfTotal2=$ratioOTDInfTotal;
						$ratioOTDEgalTotal2=$ratioOTDEgalTotal;
						$ratioOTDSupTotal2=$ratioOTDSupTotal;

						$ratioOQDInfTotal2=$ratioOQDInfTotal;
						$ratioOQDEgalTotal2=$ratioOQDEgalTotal;
						$ratioOQDSupTotal2=$ratioOQDSupTotal;
						
						$ratioOTDInfTotal=null;
						$ratioOTDEgalTotal=null;
						$ratioOTDSupTotal=null;
						if($ratioOTDInfTotal2>0 || $ratioOTDEgalTotal2>0 || $ratioOTDSupTotal2>0){
							$ratioOTDInfTotal=round(($ratioOTDInfTotal2/($ratioOTDInfTotal2+$ratioOTDEgalTotal2+$ratioOTDSupTotal2))*100,1);
							$ratioOTDEgalTotal=round(($ratioOTDEgalTotal2/($ratioOTDInfTotal2+$ratioOTDEgalTotal2+$ratioOTDSupTotal2))*100,1);
							$ratioOTDSupTotal=round(($ratioOTDSupTotal2/($ratioOTDInfTotal2+$ratioOTDEgalTotal2+$ratioOTDSupTotal2))*100,1);
						}
						
						$ratioOQDInfTotal=null;
						$ratioOQDEgalTotal=null;
						$ratioOQDSupTotal=null;
						if($ratioOQDInfTotal2>0 || $ratioOQDEgalTotal2>0 || $ratioOQDSupTotal2>0){
							$ratioOQDInfTotal=round(($ratioOQDInfTotal2/($ratioOQDInfTotal2+$ratioOQDEgalTotal2+$ratioOQDSupTotal2))*100,1);
							$ratioOQDEgalTotal=round(($ratioOQDEgalTotal2/($ratioOQDInfTotal2+$ratioOQDEgalTotal2+$ratioOQDSupTotal2))*100,1);
							$ratioOQDSupTotal=round(($ratioOQDSupTotal2/($ratioOQDInfTotal2+$ratioOQDEgalTotal2+$ratioOQDSupTotal2))*100,1);
						}
						
						$nbVertPdp2Total2=$nbVertPdp2Total;
						$nbOrangePdp2Total2=$nbOrangePdp2Total;
						$nbRougePdp2Total2=$nbRougePdp2Total;
						$nbNoirPdp2Total2=$nbNoirPdp2Total;
						
						$nbVertPdp2Total=null;
						$nbOrangePdp2Total=null;
						$nbRougePdp2Total=null;
						$nbNoirPdp2Total=null;
						if($nbVertPdp2Total2>0 || $nbOrangePdp2Total2>0 || $nbRougePdp2Total2>0 || $nbNoirPdp2Total2>0){
							$nbVertPdp2Total=round(($nbVertPdp2Total2/($nbVertPdp2Total2+$nbOrangePdp2Total2+$nbRougePdp2Total2+$nbNoirPdp2Total2))*100,1);
							$nbOrangePdp2Total=round(($nbOrangePdp2Total2/($nbVertPdp2Total2+$nbOrangePdp2Total2+$nbRougePdp2Total2+$nbNoirPdp2Total2))*100,1);
							$nbRougePdp2Total=round(($nbRougePdp2Total2/($nbVertPdp2Total2+$nbOrangePdp2Total2+$nbRougePdp2Total2+$nbNoirPdp2Total2))*100,1);
							$nbNoirPdp2Total=round(($nbNoirPdp2Total2/($nbVertPdp2Total2+$nbOrangePdp2Total2+$nbRougePdp2Total2+$nbNoirPdp2Total2))*100,1);
						}
						
						$productiviteBrutTotal=null;
						if($tempsPasseTotal>0){
							$productiviteBrutTotal=round($tempsObjectifTotal/$tempsPasseTotal,2);
						}
						$productiviteCorrigeeTotal=null;
						if($tempsPasseTotal>0){
							$productiviteCorrigeeTotal=round($tempsAlloueTotal/$tempsPasseTotal,2);
						}
						$productivite[$i]=array("Mois" => $_SESSION['MORIS_Annee'],"ProductiviteBrut" => valeurSinonNull($productiviteBrutTotal),"Objectif" => valeurSinonNull($ObjectifProductivite),"ProductiviteCorrigee" => valeurSinonNull($productiviteCorrigeeTotal),"listeBrut" => "","listeCorrigee" => "");
						$arrayOTD[$i]=array("Mois" =>  $_SESSION['MORIS_Annee'],"NbConforme" => valeurSinonNull($ConformeOTDTotal),"NbTolerance" => valeurSinonNull($ToleranceOTDTotal),"NbRetour" => valeurSinonNull($NonConformeOTDTotal),"Objectif" => valeurSinonNull($ObjectifOTD));
						$arrayOTD2[$i]=array("Mois" =>  $_SESSION['MORIS_Annee'],"NbConforme" => valeurSinonNull($ConformeOTDTotal2),"NbTolerance" => valeurSinonNull($ToleranceOTDTotal2),"NbRetour" => valeurSinonNull($NonConformeOTDTotal2),"Objectif" => valeurSinonNull($ObjectifOTD));
						$arrayOQD[$i]=array("Mois" =>  $_SESSION['MORIS_Annee'],"NbConforme" => valeurSinonNull($ConformeOQDTotal),"NbTolerance" => valeurSinonNull($ToleranceOQDTotal),"NbRetour" => valeurSinonNull($NonConformeOQDTotal),"Objectif" => valeurSinonNull($ObjectifOQD));
						$arrayOQD2[$i]=array("Mois" =>  $_SESSION['MORIS_Annee'],"NbConforme" => valeurSinonNull($ConformeOQDTotal2),"NbTolerance" => valeurSinonNull($ToleranceOQDTotal2),"NbRetour" => valeurSinonNull($NonConformeOQDTotal2),"Objectif" => valeurSinonNull($ObjectifOQD));
						$array2OTD[$i]=array("Mois" => $_SESSION['MORIS_Annee'],"ratioInf" => valeurSinonNull($ratioOTDInfTotal),"ratioEgal" => valeurSinonNull($ratioOTDEgalTotal),"ratioSup" => valeurSinonNull($ratioOTDSupTotal),"Objectif" => valeurSinonNull($ObjectifOTDActivite));
						$array2OTD2[$i]=array("Mois" => $_SESSION['MORIS_Annee'],"ratioInf" => valeurSinonNull($ratioOTDInfTotal2),"ratioEgal" => valeurSinonNull($ratioOTDEgalTotal2),"ratioSup" => valeurSinonNull($ratioOTDSupTotal2),"Objectif" => valeurSinonNull($ObjectifOTDActivite));
						$array2OQD[$i]=array("Mois" => $_SESSION['MORIS_Annee'],"ratioInf" => valeurSinonNull($ratioOQDInfTotal),"ratioEgal" => valeurSinonNull($ratioOQDEgalTotal),"ratioSup" => valeurSinonNull($ratioOQDSupTotal),"Objectif" => valeurSinonNull($ObjectifOQDActivite));
						$array2OQD2[$i]=array("Mois" => $_SESSION['MORIS_Annee'],"ratioInf" => valeurSinonNull($ratioOQDInfTotal2),"ratioEgal" => valeurSinonNull($ratioOQDEgalTotal2),"ratioSup" => valeurSinonNull($ratioOQDSupTotal2),"Objectif" => valeurSinonNull($ObjectifOQDActivite));
						$arrayPDP[$i]=array("Mois" => $_SESSION['MORIS_Annee'],"NbVert" => valeurSinonNull($nbVertPdp2Total),"NbOrange" => valeurSinonNull($nbOrangePdp2Total),"NbRouge" => valeurSinonNull($nbRougePdp2Total),"NbNoir" => valeurSinonNull($nbNoirPdp2Total),"Objectif" => valeurSinonNull($ObjectifPDP));
						$laDate=date("Y-m-d",strtotime($laDate." +0 month"));
						
						if($_SESSION['FiltreRECORD_NbMois']==12){
							$moisDebutChargeCapaV2=6;
						}
						elseif($_SESSION['FiltreRECORD_NbMois']==6){
							$moisDebutChargeCapaV2=3;
						}
						else{
							$moisDebutChargeCapaV2=0;
						}
						
						for($nbMois=1;$nbMois<=$moisDebutChargeCapaV2;$nbMois++){
							$anneeEC=date("Y",strtotime($laDate." +0 month"));
							$moisEC=date("m",strtotime($laDate." +0 month"));
							
							$laDate2=date("Y-m-d",strtotime($laDate." +0 month"));
							$annee_1=date("Y",strtotime($laDate2." -1 month"));
							$mois_1=date("m",strtotime($laDate2." -1 month"));
							$annee_2=date("Y",strtotime($laDate2." -2 month"));
							$mois_2=date("m",strtotime($laDate2." -2 month"));
							$annee_3=date("Y",strtotime($laDate2." -3 month"));
							$mois_3=date("m",strtotime($laDate2." -3 month"));
							$annee_4=date("Y",strtotime($laDate2." -4 month"));
							$mois_4=date("m",strtotime($laDate2." -4 month"));
							$annee_5=date("Y",strtotime($laDate2." -5 month"));
							$mois_5=date("m",strtotime($laDate2." -5 month"));
							$annee_6=date("Y",strtotime($laDate2." -6 month"));
							$mois_6=date("m",strtotime($laDate2." -6 month"));
							$annee_7=date("Y",strtotime($laDate2." -7 month"));
							$mois_7=date("m",strtotime($laDate2." -7 month"));
							
							$CapaInterne=0;
							$CapaExterne=0;
							$ChargeTotal=0;
							$CapaInternePrev=0;
							$CapaExternePrev=0;;
							
							foreach($resultPrestation2 as $rowPresta)
							{
								$req="SELECT ";
								if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
								$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0) AS InterneCurrent,";
								if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
								$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS SubContractorCurrent,
								IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
									PermanentCurrent+TemporyCurrent+InterneCurrent,
									COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaInterne,
								IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
									SubContractorCurrent,
									COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaExterne ";
								$req.="FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND Suppr=0 
								AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
								AND moris_moisprestation.Id_Prestation =".$rowPresta['Id']." AND (";
								if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
								$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
								OR COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
								)";
								
								$resultEC=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
								
								$nbInternePresta=0;
								$nbSubContractorPresta=0;
								if($nbResultaMoisPrestaEC>0){
									$LigneMoisPrestationEC=mysqli_fetch_array($resultEC);
									$CapaInterne+=$LigneMoisPrestationEC['CapaInterne'];
									$CapaExterne+=$LigneMoisPrestationEC['CapaExterne'];
									$ChargeTotal+=$LigneMoisPrestationEC['InterneCurrent']+$LigneMoisPrestationEC['SubContractorCurrent'];
								}
								else{
									if($anneeEC."_".$moisEC>=$anneeDuJour."_".$moisDuJour && $anneeEC."_".$moisEC<=$anneeDuJour7."_".$moisDuJour7){
										//Rechercher la prévision sur l'un des mois précédent
										$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
										FROM moris_moisprestation
										WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ";
											if($anneeEC."_".$moisEC==$anneeDuJour."_".$moisDuJour){$req.="('".$annee_1."_".$mois_1."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour1."_".$moisDuJour1){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour2."_".$moisDuJour2){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour3."_".$moisDuJour3){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour4."_".$moisDuJour4){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour5."_".$moisDuJour5){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour6."_".$moisDuJour6){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour7."_".$moisDuJour7){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
										$req.="AND Suppr=0 
										AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
										AND moris_moisprestation.Id_Prestation IN (".$rowPresta['Id'].")
										AND ((";
										if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
										$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
											OR 
											COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
											)
										ORDER BY Annee DESC, Mois DESC ";
	
										$resultEC2=mysqli_query($bdd,$req);
										$nbResultaMoisPrestaEC2=mysqli_num_rows($resultEC2);
										if($nbResultaMoisPrestaEC2>0){
											$LigneMoisPrestationEC2=mysqli_fetch_array($resultEC2);
											$leMoisCharge="";
											if($LigneMoisPrestationEC2['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
											elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
											elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
											elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
											elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
											elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
											elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_7."_".$mois_7){$leMoisCharge="6";}
											if($leMoisCharge<>""){
												//Rechercher la prévision sur l'un des mois précédent
												$req="SELECT ";
												if($bFamilleIndefini==1){$req.="M".$leMoisCharge."+";}
												$req.="
												COALESCE((SELECT SUM(M".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS M,
												COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaInterne,
												COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaExterne
												FROM moris_moisprestation
												WHERE Id=".$LigneMoisPrestationEC2['Id']." ";
												
												$resultECF2=mysqli_query($bdd,$req);
												$nbResultaMoisPrestaECF2=mysqli_num_rows($resultECF2);
												if($nbResultaMoisPrestaECF2>0){
													$LigneMoisPrestationECF2=mysqli_fetch_array($resultECF2);
													$ChargeTotal+=$LigneMoisPrestationECF2['M'];
													$CapaInternePrev+=$LigneMoisPrestationECF2['CapaInterne'];
													$CapaExternePrev+=$LigneMoisPrestationECF2['CapaExterne'];
												}
											}
										}
									}
								}
							}
							$arrayBesoin[$i-$moisDebutChargeCapa]=array("Mois" => $MoisLettre[$moisEC-1]." ".$anneeEC,"Interne" => valeurSinonNull($CapaInterne),"SubContractor" => valeurSinonNull($CapaExterne),"Prevision" => valeurSinonNull($ChargeTotal), "InternePrevi" => valeurSinonNull($CapaInternePrev), "ExternePrevi" => valeurSinonNull($CapaExternePrev));
							$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
							$i++;
						}
						
						$req="SELECT Id,Annee,Mois,MONTH(DerniereDateEvaluation) AS leMois,
							EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
						FROM moris_moisprestation
						WHERE Annee=".$annee."
						AND Mois<=".$mois."
						AND Suppr=0
						AND (
							EvaluationQualite>0
							OR EvaluationDelais>0
							OR EvaluationCompetencePersonnel>0
							OR EvaluationAutonomie>0
							OR EvaluationAnticipation>0
							OR EvaluationCommunication>0
							)
						AND (YEAR(DerniereDateEvaluation)=".$annee." AND MONTH(DerniereDateEvaluation)<=".$mois.")
						";
						if($listePrestation2<>""){
							$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
						}

						$result=mysqli_query($bdd,$req);
						$nbResultaMoisPresta2=mysqli_num_rows($result);
						
						$nbAnnee=0;
						$nbSem1=0;
						$nbSem2=0;
						
						$totalTotal=0;
						$nbEvalTotal=0;
						$totalS1=0;
						$totalS2=0;
						if($nbResultaMoisPresta2>0){
							while($rowPRM=mysqli_fetch_array($result)){
								$total=0;
								$nbEval=0;
								if($rowPRM['EvaluationQualite']>-1){
									$total+=$rowPRM['EvaluationQualite'];
									$totalTotal+=$rowPRM['EvaluationQualite'];
									$nbEval++;
									$nbEvalTotal++;
									
									if($rowPRM['leMois']<=6){
										$nbSem1++;
										$totalS1+=$rowPRM['EvaluationQualite'];
									}
									else{
										$nbSem2++;
										$totalS2+=$rowPRM['EvaluationQualite'];
									}
								}
								if($rowPRM['EvaluationDelais']>-1){
									$total+=$rowPRM['EvaluationDelais'];
									$totalTotal+=$rowPRM['EvaluationDelais'];
									$nbEval++;
									$nbEvalTotal++;
									
									if($rowPRM['leMois']<=6){
										$nbSem1++;
										$totalS1+=$rowPRM['EvaluationDelais'];
									}
									else{
										$nbSem2++;
										$totalS2+=$rowPRM['EvaluationDelais'];
									}
								}
								if($rowPRM['EvaluationCompetencePersonnel']>-1){
									$total+=$rowPRM['EvaluationCompetencePersonnel'];
									$totalTotal+=$rowPRM['EvaluationCompetencePersonnel'];
									$nbEval++;
									$nbEvalTotal++;
									
									if($rowPRM['leMois']<=6){
										$nbSem1++;
										$totalS1+=$rowPRM['EvaluationCompetencePersonnel'];
									}
									else{
										$nbSem2++;
										$totalS2+=$rowPRM['EvaluationCompetencePersonnel'];
									}
								}
								if($rowPRM['EvaluationAutonomie']>-1){
									$total+=$rowPRM['EvaluationAutonomie'];
									$totalTotal+=$rowPRM['EvaluationAutonomie'];
									$nbEval++;
									$nbEvalTotal++;
									
									if($rowPRM['leMois']<=6){
										$nbSem1++;
										$totalS1+=$rowPRM['EvaluationAutonomie'];
									}
									else{
										$nbSem2++;
										$totalS2+=$rowPRM['EvaluationAutonomie'];
									}
								}
								if($rowPRM['EvaluationAnticipation']>-1){
									$total+=$rowPRM['EvaluationAnticipation'];
									$totalTotal+=$rowPRM['EvaluationAnticipation'];
									$nbEval++;
									$nbEvalTotal++;
									
									if($rowPRM['leMois']<=6){
										$nbSem1++;
										$totalS1+=$rowPRM['EvaluationAnticipation'];
									}
									else{
										$nbSem2++;
										$totalS2+=$rowPRM['EvaluationAnticipation'];
									}
								}
								if($rowPRM['EvaluationCommunication']>-1){
									$total+=$rowPRM['EvaluationCommunication'];
									$totalTotal+=$rowPRM['EvaluationCommunication'];
									$nbEval++;
									$nbEvalTotal++;
									
									if($rowPRM['leMois']<=6){
										$nbSem1++;
										$totalS1+=$rowPRM['EvaluationCommunication'];
									}
									else{
										$nbSem2++;
										$totalS2+=$rowPRM['EvaluationCommunication'];
									}
								}
							}
						}

						if($nbEvalTotal>0){
							$noteAnnee=round($totalTotal/$nbEvalTotal,2);
						}
						if($nbSem1>0){
							$noteSemestre1=round($totalS1/$nbSem1,2);
						}
						if($nbSem2>0){
							$noteSemestre2=round($totalS2/$nbSem2,2);
						}
						
						$laDate=date("Y-m-d",strtotime(date($annee."-".$mois."-01")." +1 month"));
						
						$laDateS1=date($annee."-01-01");
						$laDateS2=date($annee."-07-01");
						$laDateFinS2=date($annee."-12-31");
						$laDateDujour=date('Y-m-d');
						$lemoisEnCours=date($annee."-".$mois."-01");
						
						$req="
							SELECT DISTINCT Id_Prestation 
							FROM moris_datesuivi 
							WHERE Suppr=0 
							AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
							AND DateDebut<'".$laDateS2."'
							AND (DateFin>='".$laDateS1."' OR DateFin<='0001-01-01')
						";
						if($listePrestation2<>""){
							$req.="AND Id_Prestation IN (".$listePrestation2.") ";
						}
						$result=mysqli_query($bdd,$req);
						$nbPrestaS1=mysqli_num_rows($result);
			
						$req="SELECT DISTINCT(Id_Prestation)
						FROM moris_moisprestation
						WHERE Suppr=0
						AND (
							SELECT COUNT(DateDebut) 
							FROM moris_datesuivi 
							WHERE Id_Prestation=moris_moisprestation.Id_Prestation
							AND Suppr=0 
							AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
							AND DateDebut<'".$laDateS2."'
							AND (DateFin>='".$laDateS1."' OR DateFin<='0001-01-01')
						)>0
						AND (YEAR(DateEnvoiDemandeSatisfaction)=".$annee." AND DateEnvoiDemandeSatisfaction<'".$laDateS2."' AND DateEnvoiDemandeSatisfaction<'".$laDate."')
						";
						if($listePrestation2<>""){
							$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
						}
						
						$result=mysqli_query($bdd,$req);
						$nbPrestaS1Demande=mysqli_num_rows($result);
						$nbPrestaS1Demande2=$nbPrestaS1Demande;
						if($nbPrestaS1>0){
							$nbPrestaS1Demande=round(($nbPrestaS1Demande/$nbPrestaS1)*100,0);
						}
						else{
							$nbPrestaS1Demande=0;
							$nbPrestaS1Demande2=0;
						}
						
						$req="SELECT DISTINCT(Id_Prestation)
						FROM moris_moisprestation
						WHERE Suppr=0
						AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
						AND (
							SELECT COUNT(DateDebut) 
							FROM moris_datesuivi 
							WHERE Id_Prestation=moris_moisprestation.Id_Prestation
							AND Suppr=0 
							AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
							AND DateDebut<'".$laDateS2."'
							AND (DateFin>='".$laDateS1."' OR DateFin<='0001-01-01')
						)>0
						AND (YEAR(DerniereDateEvaluation)=".$annee." AND DerniereDateEvaluation<'".$laDateS2."' AND DerniereDateEvaluation<'".$laDate."')
						";
						if($listePrestation2<>""){
							$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
						}
						$result=mysqli_query($bdd,$req);
						$nbPrestaS1Retour=mysqli_num_rows($result);
						$nbPrestaS1Retour2=$nbPrestaS1Retour;
						
						if($nbPrestaS1>0){
							$nbPrestaS1Retour=round(($nbPrestaS1Retour/$nbPrestaS1)*100,0);
						}
						else{
							$nbPrestaS1Retour=0;
							$nbPrestaS1Retour2=0;
						}
						
						if($laDateS2<=$laDateDujour && $laDateS2<=$lemoisEnCours){
							$req="
								SELECT DISTINCT Id_Prestation 
								FROM moris_datesuivi 
								WHERE Suppr=0 
								AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
								AND DateDebut<='".$laDateFinS2."'
								AND (DateFin>='".$laDateS2."' OR DateFin<='0001-01-01')
							";
							if($listePrestation2<>""){
								$req.="AND Id_Prestation IN (".$listePrestation2.") ";
							}
							
							$result=mysqli_query($bdd,$req);
							$nbPrestaS2=mysqli_num_rows($result);
						}
						else{
							$nbPrestaS2=0;
						}
						
						$req="SELECT DISTINCT(Id_Prestation)
						FROM moris_moisprestation
						WHERE Suppr=0
						AND (
							SELECT COUNT(DateDebut) 
							FROM moris_datesuivi 
							WHERE Id_Prestation=moris_moisprestation.Id_Prestation
							AND Suppr=0 
							AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
							AND DateDebut<='".$laDateFinS2."'
							AND (DateFin>='".$laDateS2."' OR DateFin<='0001-01-01')
						)>0
						AND (YEAR(DateEnvoiDemandeSatisfaction)=".$annee." AND DateEnvoiDemandeSatisfaction>='".$laDateS2."' AND DateEnvoiDemandeSatisfaction<'".$laDate."')
						";
						if($listePrestation2<>""){
							$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
						}
						$result=mysqli_query($bdd,$req);
						$nbPrestaS2Demande=mysqli_num_rows($result);
						$nbPrestaS2Demande2=$nbPrestaS2Demande;
						if($nbPrestaS2>0){
							$nbPrestaS2Demande=round(($nbPrestaS2Demande/$nbPrestaS2)*100,0);
						}
						else{
							$nbPrestaS2Demande=0;
							$nbPrestaS2Demande2=0;
						}
						
						$req="SELECT DISTINCT(Id_Prestation)
						FROM moris_moisprestation
						WHERE Suppr=0
						AND (
							SELECT COUNT(DateDebut) 
							FROM moris_datesuivi 
							WHERE Id_Prestation=moris_moisprestation.Id_Prestation
							AND Suppr=0 
							AND (SELECT PRMADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
							AND DateDebut<='".$laDateFinS2."'
							AND (DateFin>='".$laDateS2."' OR DateFin<='0001-01-01')
						)>0
						AND (YEAR(DerniereDateEvaluation)=".$annee." AND DerniereDateEvaluation>='".$laDateS2."' AND DerniereDateEvaluation<'".$laDate."')
						";
						if($listePrestation2<>""){
							$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
						}
						$result=mysqli_query($bdd,$req);
						$nbPrestaS2Retour=mysqli_num_rows($result);
						
						$nbPrestaS2Retour2=$nbPrestaS2Retour;
						if($nbPrestaS2>0){
							$nbPrestaS2Retour=round(($nbPrestaS2Retour/$nbPrestaS2)*100,0);
						}
						else{
							$nbPrestaS2Retour=0;
							$nbPrestaS2Retour2=0;
						}
						
						$nbPrestaAnnee=$nbPrestaS1+$nbPrestaS2;
						$nbPrestaAnneeDemande=$nbPrestaS1Demande2+$nbPrestaS2Demande2;
						$nbPrestaAnneeRetour= $nbPrestaS1Retour2+$nbPrestaS2Retour2;
						
						if($nbPrestaAnnee>0){
							$nbPrestaAnneeDemande=round(($nbPrestaAnneeDemande/$nbPrestaAnnee)*100,0);
						}
						
						if($nbPrestaAnnee>0){
							$nbPrestaAnneeRetour=round(($nbPrestaAnneeRetour/$nbPrestaAnnee)*100,0);
						}
						
						$tabPRM="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabPRM.= "<tr>";
							if($_SESSION['Langue']=="FR"){
								$tabPRM.= "<td style='border:1px solid black'></td>";
								$tabPRM.= "<td style='border:1px solid black'>Année (%)</td>";
								$tabPRM.= "<td style='border:1px solid black'>Semestre 1</td>";
								$tabPRM.= "<td style='border:1px solid black'>Semestre 2</td>";
							}
							else{
								$tabPRM.= "<td style='border:1px solid black'></td>";
								$tabPRM.= "<td style='border:1px solid black' align='center'>Year (%)</td>";
								$tabPRM.= "<td style='border:1px solid black' align='center'>Semester 1</td>";
								$tabPRM.= "<td style='border:1px solid black' align='center'>Semester 2</td>";
							}
						$tabPRM.= "</tr>";
						$tabPRM.= "<tr>";
							if($_SESSION['Langue']=="FR"){
								$tabPRM.= "<td style='border:1px solid black'>Nb Activités</td>";
							}
							else{
								$tabPRM.= "<td style='border:1px solid black'>Nb sites</td>";
							}
							$tabPRM.= "<td style='border:1px solid black' align='center'></td>";
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaS1."</td>";
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaS2."</td>";
						$tabPRM.= "</tr>";
						$tabPRM.= "<tr>";
							if($_SESSION['Langue']=="FR"){
								$tabPRM.= "<td style='border:1px solid black'>% Demande</td>";
							}
							else{
								$tabPRM.= "<td style='border:1px solid black'>% Request</td>";
							}
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaAnneeDemande." %</td>";
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaS1Demande." %</td>";
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaS2Demande." %</td>";
						$tabPRM.= "</tr>";
						$tabPRM.= "<tr>";
							if($_SESSION['Langue']=="FR"){
								$tabPRM.= "<td style='border:1px solid black'>% Retour</td>";
							}
							else{
								$tabPRM.= "<td style='border:1px solid black'>% Return</td>";
							}
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaAnneeRetour." %</td>";
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaS1Retour." %</td>";
							$tabPRM.= "<td style='border:1px solid black' align='center'>".$nbPrestaS2Retour." %</td>";
						$tabPRM.= "</tr>";
						$tabPRM.="</table>";
						
						if($_SESSION['Langue']=="FR"){
							$arrayNewPRM[0]=array("Abs" => utf8_encode("Année"),"Note" => valeurSinonNull($noteAnnee),"Objectif" => $ObjectifSatisfactionClient);
							$arrayNewPRM[1]=array("Abs" => "","Note" => null,"Objectif" => $ObjectifSatisfactionClient);
							$arrayNewPRM[2]=array("Abs" => "Semestre 1","Note" => valeurSinonNull($noteSemestre1),"Objectif" => $ObjectifSatisfactionClient);
							$arrayNewPRM[3]=array("Abs" => "Semestre 2","Note" => valeurSinonNull($noteSemestre2),"Objectif" => $ObjectifSatisfactionClient);
						}
						else{
							$arrayNewPRM[0]=array("Abs" => utf8_encode("Year"),"Note" => valeurSinonNull($noteAnnee),"Objectif" => $ObjectifSatisfactionClient);
							$arrayNewPRM[1]=array("Abs" => "","Note" => null,"Objectif" => $ObjectifSatisfactionClient);
							$arrayNewPRM[2]=array("Abs" => "Semester 1","Note" => valeurSinonNull($noteSemestre1),"Objectif" => $ObjectifSatisfactionClient);
							$arrayNewPRM[3]=array("Abs" => "Semester 2","Note" => valeurSinonNull($noteSemestre2),"Objectif" => $ObjectifSatisfactionClient);

						}

						$tabOTD="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'></td>";
						foreach($arrayOTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['Mois']."</td>";
							}
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>C</td>";
						foreach($arrayOTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbConforme']."</td>";
							}
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($arrayOTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbTolerance']."</td>";
							}
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>NC</td>";
						foreach($arrayOTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbRetour']."</td>";
							}
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>OTD %</td>";
						foreach($arrayOTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="#00d200";
								if($otd['OTD']<$otd['Objectif']){$couleur="#ff5b5b";}
								if($otd['OTD']>0){
									$leHover="";
									$span="";
									if($otd['liste']<>""){
										$leHover="id='leHover'";
										$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'OTD\',\''.$otd['liste'].'\',\''.$otd['Mois'].'<br>OTD %\')" /><span>'.$otd['liste'].'</span>';
									}
									$tabOTD.= "<td ".$leHover." style='border:1px solid black;background-color:".$couleur.";text-align:center;cursor:pointer;'>".$span." ".$otd['OTD']."%</td>";
								}
								else{
									$tabOTD.= "<td style='border:1px solid black;text-align:center;'></td>";
								}
							}
						}
						$tabOTD.= "</tr>";
						$tabOTD.="</table>";
						
						
						$tabOQD="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'></td>";
						foreach($arrayOQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['Mois']."</td>";
							}
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>C</td>";
						foreach($arrayOQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbConforme']."</td>";
							}
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($arrayOQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbTolerance']."</td>";
							}
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>NC</td>";
						foreach($arrayOQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbRetour']."</td>";
							}
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>OQD %</td>";
						foreach($arrayOQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="#00d200";
								if($oqd['OQD']<$otd['Objectif']){$couleur="#ff5b5b";}
								if($oqd['OQD']>0){
									$leHover="";
									$span="";
									if($oqd['liste']<>""){
										$leHover="id='leHover'";
										$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'OQD\',\''.$oqd['liste'].'\',\''.$oqd['Mois'].'<br>OQD %\')" /><span>'.$oqd['liste'].'</span>';
									}
								$tabOQD.= "<td ".$leHover." style='border:1px solid black;background-color:".$couleur.";text-align:center;cursor:pointer;'>".$span." ".$oqd['OQD']."%</td>";
								}
								else{
									$tabOQD.= "<td style='border:1px solid black;text-align:center;'></td>";
								}
							}
						}
						$tabOQD.= "</tr>";
						$tabOQD.="</table>";
						
						$tabOTD2="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'></td>";
						foreach($array2OTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOTD2.= "<td style='border:1px solid black;text-align:center;'>".$otd['Mois']."</td>";
							}
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'>C</td>";
						foreach($array2OTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="";
								if($otd['ratioSup']>0){
									$couleur="background-color:#00d200";
								}
								$tabOTD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$otd['ratioSup']."</td>";
							}
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($array2OTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="";
								if($otd['ratioEgal']>0){
									$couleur="background-color:#ffd757";
								}
								$tabOTD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$otd['ratioEgal']."</td>";
							}
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'>NC</td>";
						foreach($array2OTD2 as $otd){
							if($otd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="";
								$leHover="";
								$span="";
								if($otd['ratioInf']>0){
									$couleur="background-color:#ff5b5b";
									if($otd['listeOTDInf']<>""){
										$leHover="id='leHover'";
										$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'OTDInf\',\''.$otd['listeOTDInf'].'\',\''.$otd['Mois'].'<br>NC\')" /><span>'.$otd['listeOTDInf'].'</span>';
									}
								}
								$tabOTD2.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$otd['ratioInf']."</td>";
							}
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.="</table>";
						
						$tabOQD2="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'></td>";
						foreach($array2OQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$tabOQD2.= "<td style='border:1px solid black;text-align:center;'>".$oqd['Mois']."</td>";
							}
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'>C</td>";
						foreach($array2OQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="";
								if($oqd['ratioSup']>0){
									$couleur="background-color:#00d200";
								}
								$tabOQD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$oqd['ratioSup']."</td>";
							}
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($array2OQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="";
								if($oqd['ratioEgal']>0){
									$couleur="background-color:#ffd757";
								}
								$tabOQD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$oqd['ratioEgal']."</td>";
							}
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'>NC</td>";
						foreach($array2OQD2 as $oqd){
							if($oqd['Mois']<>$_SESSION['MORIS_Annee']){
								$couleur="";
								$leHover="";
								$span="";
								if($oqd['ratioInf']>0){
									$couleur="background-color:#ff5b5b";
									if($oqd['listeOQDInf']<>""){
										$leHover="id='leHover'";
										$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'OQDInf\',\''.$oqd['listeOQDInf'].'\',\''.$oqd['Mois'].'<br>NC\')" /><span>'.$oqd['listeOQDInf'].'</span>';
									}
								}
								$tabOQD2.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$oqd['ratioInf']."</td>";
							}
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.="</table>";
						
						$tabPDP="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'></td>";
						foreach($arrayPDP2 as $pdp){
							$tabPDP.= "<td style='border:1px solid black;text-align:center;'>".$pdp['Mois']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>> 1 mois</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							if($pdp['NbVert']>0){
								$couleur="background-color:#00d200";
							}
							$tabPDP.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$pdp['NbVert']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>< 1 mois</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							$leHover="";
							$span="";
							if($pdp['NbOrange']>0){
								$couleur="background-color:#ffd757";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'PDP\',\''.$pdp['listeOrange'].'\',\''.$pdp['Mois'].'<br>< 1 mois\')" /><span>'.$pdp['listeOrange'].'</span>';
							}							
							$tabPDP.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$pdp['NbOrange']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>Dépassé</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							$leHover="";
							$span="";
							if($pdp['NbRouge']>0){
								$couleur="background-color:#ff5b5b";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'PDP\',\''.$pdp['listeRouge'].'\',\''.$pdp['Mois'].'<br>Dépassé\')" /><span>'.$pdp['listeRouge'].'</span>';
							}							
							$tabPDP.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$pdp['NbRouge']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>Non renseigné</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							$leHover="";
							$span="";
							if($pdp['NbNoir']>0){
								$couleur="background-color:#bdbdbd";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'PDP\',\''.$pdp['listeNoir'].'\',\''.$pdp['Mois'].'<br>Non renseigné\')" /><span>'.$pdp['listeNoir'].'</span>';
							}							
							$tabPDP.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur.";cursor:pointer;' >".$span." ".$pdp['NbNoir']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>Taux de couverture</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							if($pdp['NbVert']>0){
								$couleur="background-color:#00d200";
							}
							$taux="";
							if(($pdp['NbVert']+$pdp['NbOrange']+$pdp['NbRouge']+$pdp['NbNoir'])>0){
								$taux=round((($pdp['NbVert']+$pdp['NbOrange'])/($pdp['NbVert']+$pdp['NbOrange']+$pdp['NbRouge']+$pdp['NbNoir']))*100,1);
							}
							$tabPDP.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$taux."%</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.="</table>";
						
						$tabProductivite="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>
											<tr>
											<td style='border:1px solid black'></td>";
						foreach($productivite as $prod){
							$tabProductivite.= "<td style='border:1px solid black;text-align:center;'>".$prod['Mois']."</td>";
						}
						$tabProductivite.= "</tr>";

						$tabProductivite.= "<tr>";
							$tabProductivite.= "<td style='border:1px solid black'>Productivité brute</td>";
						foreach($productivite as $prod){
							if($prod['ProductiviteBrut']>0){
								$leHover="";
								$span="";
								if($prod['listeBrut']<>""){
									$leHover="id='leHover'";
									$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'Productivite\',\''.$prod['listeBrut'].'\',\''.$prod['Mois'].'<br>Productivité brute\')" /><span>'.$prod['listeBrut'].'</span>';
								}
								$tabProductivite.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;' >".$span." ".$prod['ProductiviteBrut']."</td>";
							}
							else{
								$tabProductivite.= "<td style='border:1px solid black;text-align:center;'></td>";
							}
						}
						$tabProductivite.= "</tr>";
						
						$tabProductivite.= "<tr>";
							$tabProductivite.= "<td style='border:1px solid black'>Productivité corrigée</td>";
						foreach($productivite as $prod){
							if($prod['ProductiviteCorrigee']>0){
								$leHover="";
								$span="";
								if($prod['listeCorrigee']<>""){
									$leHover="id='leHover'";
									$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'Productivite\',\''.$prod['listeCorrigee'].'\',\''.$prod['Mois'].'<br>Productivité corrigée\')" /><span>'.$prod['listeCorrigee'].'</span>';
								}
								$tabProductivite.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;'>".$span." ".$prod['ProductiviteCorrigee']."</td>";
							}
							else{
								$tabProductivite.= "<td style='border:1px solid black;text-align:center;'></td>";
							}
						}
						$tabProductivite.= "</tr>";
						$tabProductivite.="</table>";
						
						$tabNC="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$ligneNCMois="<tr><td style='border:1px solid black'></td>";
						$ligneNC1="<tr><td style='border:1px solid black'>NC Niv 1</td>";
						$ligneNC2="<tr><td style='border:1px solid black'>NC Niv 2</td>";
						$ligneNC3="<tr><td style='border:1px solid black'>NC Niv 3</td>";	
						$ligneRC="<tr><td style='border:1px solid black'>RC</td>";
						foreach($arrayNbNC as $nc){
							$ligneNCMois.= "<td style='border:1px solid black;text-align:center;'>".$nc['Mois']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['NC1']<>""){
								$couleur="background-color:#3d7ad5;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeNCv2'].'\',\''.$nc['Mois'].'<br>NC Niv 1\')" /><span>'.$nc['listeNC'].'</span>';
							}
							$ligneNC1.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['NC1']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['NC2']<>""){
								$couleur="background-color:#29dae9;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeNC2v2'].'\',\''.$nc['Mois'].'<br>NC Niv 2\')" /><span>'.$nc['listeNC2'].'</span>';
							}
							$ligneNC2.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['NC2']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['NC3']<>""){
								$couleur="background-color:#f8ff6d;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeNC3v2'].'\',\''.$nc['Mois'].'<br>NC Niv 3\')" /><span>'.$nc['listeNC3'].'</span>';
							}
							$ligneNC3.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['NC3']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['listeRC']<>""){
								$couleur="background-color:#f3b479;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeRCv2'].'\',\''.$nc['Mois'].'<br>RC\')" /><span>'.$nc['listeRC'].'</span>';
							}
							$ligneRC.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['RC']."</td>";
						}
						$ligneNCMois.= "</tr>";
						$ligneNC1.= "</tr>";
						$ligneNC2.= "</tr>";
						$ligneNC3.= "</tr>";
						$ligneRC.= "</tr>";
						
						$tabNC.=$ligneNCMois.$ligneNC1.$ligneNC2.$ligneNC3.$ligneRC;
						
						$tabNC.="</table>";
						
						$tabAT="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$ligneATMois="<tr><td style='border:1px solid black'></td>";
						$ligneATrajet="<tr><td style='border:1px solid black'>Nb accident de trajet</td>";
						$ligneSansAT="<tr><td style='border:1px solid black'>Nb accident sans arrêt de travail</td>";
						$ligneAvecAT="<tr><td style='border:1px solid black'>Nb accident avec arrêt de travail</td>";	
						foreach($arraySecurite as $securite){
							$ligneATMois.= "<td style='border:1px solid black;text-align:center;'>".$securite['Mois']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($securite['NbTrajet']<>""){
								$couleur="background-color:#3d7ad5;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'AT\',\''.$securite['listeTrajetv2'].'\',\''.$securite['Mois'].'<br>Nb accident de trajet\')" /><span>'.$securite['listeTrajet'].'</span>';
							}
							$ligneATrajet.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$securite['NbTrajet']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($securite['NbNonTrajetAvecArret']<>""){
								$couleur="background-color:#dbb637;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'AT\',\''.$securite['listeATv2'].'\',\''.$securite['Mois'].'<br>Nb accident avec arrêt de travail\')" /><span>'.$securite['listeAT'].'</span>';
							}
							$ligneAvecAT.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$securite['NbNonTrajetAvecArret']."</td>";
							
							
							$couleur="";
							$leHover="";
							$span="";
							if($securite['NbNonTrajetSansArret']<>""){
								$couleur="background-color:#9fb1c5;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'AT\',\''.$securite['listeSansATv2'].'\',\''.$securite['Mois'].'<br>Nb accident sans arrêt de travail\')" /><span>'.$securite['listeSansAT'].'</span>';
							}
							$ligneSansAT.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$securite['NbNonTrajetSansArret']."</td>";
							
							
						}
						$ligneNCMois.= "</tr>";
						$ligneATrajet.= "</tr>";
						$ligneSansAT.= "</tr>";
						$ligneAvecAT.= "</tr>";
						
						$tabAT.=$ligneATMois.$ligneATrajet.$ligneAvecAT.$ligneSansAT;
						
						$tabAT.="</table>";
						
	
					}
					elseif($_SESSION['FiltreRECORD_Vision']==2){
						
						$reqFamille="SELECT DISTINCT Id_Famille,
						(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Famille
						FROM moris_moisprestation_famille
						LEFT JOIN moris_moisprestation
						ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
						WHERE Id_Famille>0
						AND moris_moisprestation.Suppr=0
						AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))='".$_SESSION['MORIS_Annee'].'_'.$_SESSION['MORIS_Mois']."'
						";
						if($listePrestation2<>""){
							$reqFamille.="AND Id_Prestation IN(".$listePrestation2.") ";
						}
						$reqFamille.="ORDER BY Famille";
						
						//Liste des plateformes sélectionnées 
						$req="SELECT DISTINCT new_competences_plateforme.Id,
								new_competences_plateforme.Libelle
								FROM new_competences_prestation
								LEFT JOIN new_competences_plateforme
								ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
								WHERE new_competences_prestation.UtiliseMORIS>0  
							";
						if($_SESSION['FiltreRECORD_Prestation']<>""){
							$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
						}
						$req.="ORDER BY new_competences_plateforme.Libelle";
						$resultPlateforme=mysqli_query($bdd,$req);
						$nbPlateforme=mysqli_num_rows($resultPlateforme);
						
						$anneeEC=$_SESSION['MORIS_Annee'];
						$moisEC=$_SESSION['MORIS_Mois'];
						
						$laDate1=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");
						$laDate=date("Y-m-d",strtotime($laDate1." +0 month"));

						$laDate2=date("Y-m-d",strtotime($laDate." +0 month"));
						$annee_1=date("Y",strtotime($laDate2." -1 month"));
						$mois_1=date("m",strtotime($laDate2." -1 month"));
						$annee_2=date("Y",strtotime($laDate2." -2 month"));
						$mois_2=date("m",strtotime($laDate2." -2 month"));
						$annee_3=date("Y",strtotime($laDate2." -3 month"));
						$mois_3=date("m",strtotime($laDate2." -3 month"));
						$annee_4=date("Y",strtotime($laDate2." -4 month"));
						$mois_4=date("m",strtotime($laDate2." -4 month"));
						$annee_5=date("Y",strtotime($laDate2." -5 month"));
						$mois_5=date("m",strtotime($laDate2." -5 month"));
						$annee_6=date("Y",strtotime($laDate2." -6 month"));
						$mois_6=date("m",strtotime($laDate2." -6 month"));
						$annee_7=date("Y",strtotime($laDate2." -7 month"));
						$mois_7=date("m",strtotime($laDate2." -7 month"));
						if($nbPlateforme>0){
							while($rowPlateforme=mysqli_fetch_array($resultPlateforme)){
															
								$req="SELECT DISTINCT new_competences_prestation.Id
								FROM new_competences_prestation
								WHERE new_competences_prestation.UtiliseMORIS>0 
								AND Id_Plateforme = ".$rowPlateforme['Id']."
								";
								if($listePrestation2<>""){
									$req.="AND new_competences_prestation.Id IN(".$listePrestation2.") ";
								}
								$resultPrestation2=mysqli_query($bdd,$req);
								
								$CapaInterne=0;
								$CapaExterne=0;
								$ChargeTotal=0;
								$CapaInternePrev=0;
								$CapaExternePrev=0;
								
								foreach($resultPrestation2 as $rowPresta)
								{

									$req="SELECT ";
									if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0) AS InterneCurrent,";
									if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS SubContractorCurrent,
									IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
										PermanentCurrent+TemporyCurrent+InterneCurrent,
										COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaInterne,
									IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
										SubContractorCurrent,
										COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaExterne
									";
									$req.="FROM moris_moisprestation
									WHERE Annee=".$anneeEC." 
									AND Mois=".$moisEC."
									AND Suppr=0 
									AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
									AND moris_moisprestation.Id_Prestation =".$rowPresta['Id']." 
									
									AND ((";
										if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
										$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0 
										OR COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
										)";
									
									$resultEC=mysqli_query($bdd,$req);
									$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
									
									if($nbResultaMoisPrestaEC>0){
										$LigneMoisPrestationEC=mysqli_fetch_array($resultEC);
										$CapaInterne+=$LigneMoisPrestationEC['CapaInterne'];
										$CapaExterne+=$LigneMoisPrestationEC['CapaExterne'];
										$ChargeTotal+=$LigneMoisPrestationEC['InterneCurrent']+$LigneMoisPrestationEC['SubContractorCurrent'];
									}
									//if($CapaInterne==0 && $CapaExterne==0 && $ChargeTotal==0){
									if($nbResultaMoisPrestaEC==0){
										if($anneeEC."_".$moisEC>=$anneeDuJour."_".$moisDuJour && $anneeEC."_".$moisEC<=$anneeDuJour7."_".$moisDuJour7){
											//Rechercher la prévision sur l'un des mois précédent
											$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
											FROM moris_moisprestation
											WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ";
											if($anneeEC."_".$moisEC==$anneeDuJour."_".$moisDuJour){$req.="('".$annee_1."_".$mois_1."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour1."_".$moisDuJour1){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour2."_".$moisDuJour2){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour3."_".$moisDuJour3){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour4."_".$moisDuJour4){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour5."_".$moisDuJour5){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour6."_".$moisDuJour6){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour7."_".$moisDuJour7){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											$req.="AND Suppr=0 
											AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
											AND moris_moisprestation.Id_Prestation IN (".$rowPresta['Id'].")
											AND ((";
											if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
											$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
											OR COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
											)
											ORDER BY Annee DESC, Mois DESC ";
											
											
											/*$req.="AND Suppr=0 
											AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
											AND moris_moisprestation.Id_Prestation IN (".$rowPresta['Id'].")
											ORDER BY Annee DESC, Mois DESC ";*/
											
											$resultEC2=mysqli_query($bdd,$req);
											$nbResultaMoisPrestaEC2=mysqli_num_rows($resultEC2);
											if($nbResultaMoisPrestaEC2>0){
												$LigneMoisPrestationEC2=mysqli_fetch_array($resultEC2);
												$leMoisCharge="";
												if($LigneMoisPrestationEC2['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_7."_".$mois_7){$leMoisCharge="6";}
												if($leMoisCharge<>""){
													//Rechercher la prévision sur l'un des mois précédent
													$req="SELECT ";
													if($bFamilleIndefini==1){$req.="M".$leMoisCharge."+";}
													$req.="COALESCE((SELECT SUM(M".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS M,";
													$req.="COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaInterne,
													COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaExterne
													FROM moris_moisprestation
													WHERE Id=".$LigneMoisPrestationEC2['Id']." ";
													$resultECF2=mysqli_query($bdd,$req);
													$nbResultaMoisPrestaECF2=mysqli_num_rows($resultECF2);
													if($nbResultaMoisPrestaECF2>0){
														$LigneMoisPrestationECF2=mysqli_fetch_array($resultECF2);
														$ChargeTotal+=$LigneMoisPrestationECF2['M'];
														$CapaInternePrev+=$LigneMoisPrestationECF2['CapaInterne'];
														$CapaExternePrev+=$LigneMoisPrestationECF2['CapaExterne'];
													}
												}
											}
										}
									}
								}
								if($CapaInterne>0 || $CapaExterne>0 || $ChargeTotal>0 || $CapaInternePrev>0 || $CapaExternePrev>0){
									$arrayBesoin[$i2]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"Interne" => valeurSinonNull($CapaInterne),"SubContractor" => valeurSinonNull($CapaExterne),"Prevision" => valeurSinonNull($ChargeTotal), "InternePrevi" => valeurSinonNull($CapaInternePrev), "ExternePrevi" => valeurSinonNull($CapaExternePrev));
									$i2++;
								}

								
								$req="SELECT Id,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								Id_Prestation,TempsAlloue,TempsPasse,TempsObjectif,
								(SELECT ProductiviteADesactive FROM new_competences_prestation WHERE Id=Id_Prestation) AS ProductiviteADesactive,
								(SELECT ToleranceOTDOQD FROM new_competences_prestation WHERE Id=Id_Prestation) AS ToleranceOTDOQD,
								IF(ObjectifClientOTD=0,100,ObjectifClientOTD) AS ObjectifClientOTD,
								NbLivrableOTD,NbRetourClientOTD,OTD,ObjectifToleranceOTD,
								IF(ObjectifClientOQD=0,100,ObjectifClientOQD) AS ObjectifClientOQD,
								NbLivrableOQD,NbRetourClientOQD,OQD,ObjectifToleranceOQD,
								IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,PasOTD,
								IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,PasOQD,
								NbXTableauPolyvalence,NbLTableauPolyvalence,NbLivrableToleranceOTD,NbLivrableToleranceOQD,PasActivite,
								TendanceManagement,TauxQualif,NbMonoCompetence,
								EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND Suppr=0 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$resultEC=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
								
								$req="SELECT Id,
								EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND Suppr=0 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND (
									EvaluationQualite>0
									OR EvaluationDelais>0
									OR EvaluationCompetencePersonnel>0
									OR EvaluationAutonomie>0
									OR EvaluationAnticipation>0
									OR EvaluationCommunication>0
									)
								AND YEAR(DerniereDateEvaluation)=".$anneeEC." AND MONTH(DerniereDateEvaluation)=".$moisEC."
								";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$resultEC2=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaEC2=mysqli_num_rows($resultEC2);
								
								//Prestations productivité brut les plus faibles 
								$req="SELECT Id,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								Id_Prestation,
								TempsObjectif/TempsPasse AS ProductiviteBrut
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND ProductiviteDesactive=0
								AND PasActivite=0
								AND Suppr=0 
								AND TempsPasse>0
								AND TempsAlloue>0
								AND tempsObjectif>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								";
								if($listePrestation2<>""){
									$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
								}
								$req.="ORDER BY ProductiviteBrut
								LIMIT 5
								";
								$resultProd=mysqli_query($bdd,$req);
								$nbProd=mysqli_num_rows($resultProd);
								
								$listeBrut="";
								if($nbProd>0){
									while($LigneProd=mysqli_fetch_array($resultProd)){
										$presta=substr($LigneProd['Prestation'],0,strpos($LigneProd['Prestation']," "));
										if($listeBrut<>""){$listeBrut.="<br>";}
										$listeBrut.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneProd['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$presta." : ".round($LigneProd['ProductiviteBrut'],2)."</strong>";
									}
								}
								
								//Prestations productivité corrigée les plus faibles 
								$req="SELECT Id,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								Id_Prestation,
								TempsAlloue/TempsPasse AS ProductiviteCorrigee
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND ProductiviteDesactive=0
								AND PasActivite=0
								AND Suppr=0  
								AND TempsPasse>0
								AND TempsAlloue>0
								AND tempsObjectif>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								";
								if($listePrestation2<>""){
									$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
								}
								$req.="ORDER BY ProductiviteCorrigee
								LIMIT 5
								";
								$resultProd=mysqli_query($bdd,$req);
								$nbProd=mysqli_num_rows($resultProd);
								
								$listeCorrigee="";
								if($nbProd>0){
									while($LigneProd=mysqli_fetch_array($resultProd)){
										$presta=substr($LigneProd['Prestation'],0,strpos($LigneProd['Prestation']," "));
										if($listeCorrigee<>""){$listeCorrigee.="<br>";}
										$listeCorrigee.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneProd['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$presta." : ".round($LigneProd['ProductiviteCorrigee'],2)."</strong>";
									}
								}
								
								$productiviteBrut=0;
								$productiviteCorrigee=0;
								$tempsPasse=0;
								$tempsAlloue=0;
								$tempsObjectif=0;
								$objectif=1;
								$OQD=0;
								$ConformeOTD=0;
								$NonConformeOTD=0;
								$ToleranceOTD=0;
								$ConformeOTD2=0;
								$NonConformeOTD2=0;
								$ToleranceOTD2=0;
								$ConformeOQD=0;
								$NonConformeOQD=0;
								$ToleranceOQD=0;
								$ConformeOQD2=0;
								$NonConformeOQD2=0;
								$ToleranceOQD2=0;
								$ObjectifOTD=0;
								$ObjectifOQD=0;
								$ratioCompetence=0;
								$ratioQualif=0;
								$nbMonoCompetences=0;
								$nbActiviteMonoCompetences=0;
								$nbAccidentTrajet=0;
								$nbAvecArret=0;
								$nbSansArret=0;
								$listeAT="";
								$listeTrajet="";
								$listeSansAT="";
								$listeATv2="";
								$listeTrajetv2="";
								$listeSansATv2="";
								$nbNC=0;
								$nbNC2=0;
								$nbNC3=0;
								$nbRC=0;
								$listeNC="";
								$listeNC2="";
								$listeNC3="";
								$listeRC="";
								$listeNCv2="";
								$listeNC2v2="";
								$listeNC3v2="";
								$listeRCv2="";
								$nbVert=0;
								$nbOrange=0;
								$nbRouge=0;
								$nbPrestaProd=0;
								$nbPrestaOTD=0;
								$nbPrestaOQD=0;
								$nbPrestaCompetence=0;
								$nbPrestaQualif=0;
								$note=0;
								$nbPrestaPdp=0;
								$listeNoir="";
								$listeRouge="";
								$listeOrange="";
								$nbPrestaOTD2=0;
								$ratioOTDInf=0;
								$ratioOTDEgal=0;
								$ratioOTDSup=0;
								$ratioOQDInf=0;
								$ratioOQDEgal=0;
								$ratioOQDSup=0;
								$ratioOTDInf2=0;
								$ratioOTDEgal2=0;
								$ratioOTDSup2=0;
								$ratioOQDInf2=0;
								$ratioOQDEgal2=0;
								$ratioOQDSup2=0;
								$nbPrestaOQD2=0;
								$listeOTDInf="";
								$listeOQDInf="";
								
								$ObjectifProductivite=null;
								$ObjectifOTD=null;
								$ObjectifOQD=null;
								$ObjectifOTDActivite=null;
								$ObjectifOQDActivite=null;
								$ObjectifPDP=null;
								$ObjectifSatisfactionClient=null;
								$ObjectifTauxQualif=null;
								$ObjectifTauxPolyvalence=null;
								
								//Recherche Objectif E/C 
								$req="SELECT Theme, Pourcentage
								FROM moris_objectifglobal
								WHERE CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT(0,MONTH(DateDebut)),MONTH(DateDebut)))<='".$anneeEC."_".$moisEC."' 
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT(0,MONTH(DateFin)),MONTH(DateFin)))>='".$anneeEC."_".$moisEC."' OR DateFin<='0001-01-01') ";
								$resultObj=mysqli_query($bdd,$req);
								$nbObj=mysqli_num_rows($resultObj);
								if($nbObj>0){
									while($rowObj=mysqli_fetch_array($resultObj)){
										switch($rowObj['Theme']){
											case "OTD activité":
												$ObjectifOTDActivite=$rowObj['Pourcentage'];
												break;
											case "OTD livrable":
												$ObjectifOTD=$rowObj['Pourcentage'];
												break;
											case "OQD activité":
												$ObjectifOQDActivite=$rowObj['Pourcentage'];
												break;
											case "OQD livrable":
												$ObjectifOQD=$rowObj['Pourcentage'];
												break;
											case "Productivité corrigée":
												$ObjectifProductivite=$rowObj['Pourcentage'];
												break;
											case "Satisfaction client":
												$ObjectifSatisfactionClient=$rowObj['Pourcentage'];
												break;
											case "Taux de qualification":
												$ObjectifTauxQualif=$rowObj['Pourcentage'];
												break;
											case "Taux de polyvalence":
												$ObjectifTauxPolyvalence=$rowObj['Pourcentage'];
												break;
											case "Plan de prévention":
												$ObjectifPDP=$rowObj['Pourcentage'];
												break;
										}
									}
								}
							
								$req="SELECT Id
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND DateValidite>'0001-01-01'
								AND DateValidite>='".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +2 month"))."'		
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$resultPdp=mysqli_query($bdd,$req);
								$nbVertPdp=mysqli_num_rows($resultPdp);
								
								$req="SELECT Id,Id_Prestation,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND DateValidite>'0001-01-01'
								AND DateValidite>='".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +1 month"))."'	
								AND DateValidite<'".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +2 month"))."'		
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." ";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$req.="ORDER BY Prestation";
								$resultPdp=mysqli_query($bdd,$req);
								$nbOrangePdp=mysqli_num_rows($resultPdp);
								if($nbOrangePdp>0){
									$nb=1;
									while($rowPdp=mysqli_fetch_array($resultPdp)){
										if($listeOrange<>""){$listeOrange.=", ";}
										if($nb==6){$listeOrange.="<br>";$nb=0;}
										$listeOrange.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$rowPdp['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$rowPdp['Prestation']."</strong>";
										$nb++;
									}
								}
								
								$req="SELECT Id,Id_Prestation,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND DateValidite>'0001-01-01'	
								AND DateValidite<'".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +1 month"))."'		
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']."  ";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$req.="ORDER BY Prestation";
								$resultPdp=mysqli_query($bdd,$req);
								$nbRougePdp=mysqli_num_rows($resultPdp);
								if($nbRougePdp>0){
									$nb=1;
									while($rowPdp=mysqli_fetch_array($resultPdp)){
										if($listeRouge<>""){$listeRouge.=", ";}
										if($nb==6){$listeRouge.="<br>";$nb=0;}
										$listeRouge.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$rowPdp['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$rowPdp['Prestation']."</strong>";
										$nb++;
									}
								}
								
								$req="SELECT Id,Id_Prestation,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND (DateValidite<='0001-01-01')	
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." ";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$req.="ORDER BY Prestation";

								$resultPdp=mysqli_query($bdd,$req);
								$nbNoirPdp=mysqli_num_rows($resultPdp);
								if($nbNoirPdp>0){
									$nb=1;
									$leNombre=0;
									while($rowPdp=mysqli_fetch_array($resultPdp)){
										if($listeNoir<>""){$listeNoir.=", ";}
										if($nb==6){$listeNoir.="<br>";$nb=0;}
										$listeNoir.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$rowPdp['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$rowPdp['Prestation']."</strong>";
										$nb++;
										$leNombre++;
									}
									$nbNoirPdp=$leNombre;
								}
								
								$nbPrestaPdp=$nbVertPdp+$nbOrangePdp+$nbRougePdp+$nbNoirPdp;
								
								if($nbResultaMoisPrestaEC>0){
									while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
										if($LigneMoisPrestationEC['TempsPasse']>0 && $LigneMoisPrestationEC['TempsAlloue']>0 && $LigneMoisPrestationEC['TempsObjectif']>0 && $LigneMoisPrestationEC['ProductiviteADesactive']==0 && $LigneMoisPrestationEC['PasActivite']==0){
											$tempsPasse+=$LigneMoisPrestationEC['TempsPasse'];
											$tempsAlloue+=$LigneMoisPrestationEC['TempsAlloue'];
											$tempsObjectif+=$LigneMoisPrestationEC['TempsObjectif'];
											
											$nbPrestaProd++;
										}
										
										if($LigneMoisPrestationEC['PasOTD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
											if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0){
												$ConformeOTD2+=$LigneMoisPrestationEC['NbLivrableConformeOTD'];
												$NonConformeOTD2+=$LigneMoisPrestationEC['NbRetourClientOTD'];
												$ToleranceOTD2+=$LigneMoisPrestationEC['NbLivrableToleranceOTD'];
												$nbPrestaOTD++;
											}
											if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0 && $LigneMoisPrestationEC['ObjectifClientOTD']>0){
												$ratio=0;
												if($LigneMoisPrestationEC['NbLivrableConformeOTD']>0){
													$ratio=round(($LigneMoisPrestationEC['NbLivrableConformeOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
												}
												if($ratio>=$LigneMoisPrestationEC['ObjectifClientOTD']){
													$ratioOTDSup++;$ratioOTDSup2++;
												}
												else{
													if($LigneMoisPrestationEC['ToleranceOTDOQD']==0){
														$ratioOTDInf++;$ratioOTDInf2++;
														if($listeOTDInf<>""){$listeOTDInf.="<br>";}
														$listeOTDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOTD']."%</strong>";
													}
													else{
														if($ratio>=$LigneMoisPrestationEC['ObjectifToleranceOTD']){
															$ratioOTDEgal++;$ratioOTDEgal2++;
														}
														else{
															$ratioOTDInf++;$ratioOTDInf2++;
															if($listeOTDInf<>""){$listeOTDInf.="<br>";}
															$listeOTDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOTD']."%</strong>";
														}
													}
												}
												$nbPrestaOTD2++;
											}
										}
										
										if($LigneMoisPrestationEC['PasOQD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
											if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0){
												$ConformeOQD2+=$LigneMoisPrestationEC['NbLivrableConformeOQD'];
												$NonConformeOQD2+=$LigneMoisPrestationEC['NbRetourClientOQD'];
												$ToleranceOQD2+=$LigneMoisPrestationEC['NbLivrableToleranceOQD'];
												$nbPrestaOQD++;
											}
											
											if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0 && $LigneMoisPrestationEC['ObjectifClientOQD']>0){
												$ratio=0;
												if($LigneMoisPrestationEC['NbLivrableConformeOQD']>0){
													$ratio=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
												}
												if($ratio>=$LigneMoisPrestationEC['ObjectifClientOQD']){
													$ratioOQDSup++;$ratioOQDSup2++;
												}
												else{
													if($LigneMoisPrestationEC['ToleranceOTDOQD']==0){
														$ratioOQDInf++;$ratioOQDInf2++;
														if($listeOQDInf<>""){$listeOQDInf.="<br>";}
														$listeOQDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOQD']."%</strong>";
													}
													else{
														if($ratio>=$LigneMoisPrestationEC['ObjectifToleranceOQD']){
															$ratioOQDEgal++;$ratioOQDEgal2++;
														}
														else{
															$ratioOQDInf++;$ratioOQDInf2++;
															if($listeOQDInf<>""){$listeOQDInf.="<br>";}
															$listeOQDInf.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOQD']."%</strong>";
														}
													}
												}
												$nbPrestaOQD2++;
											}
										}

										
										if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0){
											$ratioCompetence+= round(($LigneMoisPrestationEC['NbXTableauPolyvalence']/($LigneMoisPrestationEC['NbXTableauPolyvalence']+$LigneMoisPrestationEC['NbLTableauPolyvalence']))*100,2);
											$nbPrestaCompetence++;
										}
										if($LigneMoisPrestationEC['TauxQualif']>0){
											$ratioQualif+=$LigneMoisPrestationEC['TauxQualif'];
											$nbPrestaQualif++;
										}
										
										if($LigneMoisPrestationEC['NbMonoCompetence']>0){
											$nbMonoCompetences+=$LigneMoisPrestationEC['NbMonoCompetence'];
											$nbActiviteMonoCompetences++;
										}
										
										$req="SELECT Id FROM moris_moisprestation_securite 
											WHERE Suppr=0 
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
											AND AvecArret=1	
											AND AccidentTrajet=0 
											UNION
											SELECT Id
											FROM rh_personne_at 
											WHERE rh_personne_at.Suppr=0 
											AND rh_personne_at.ArretDeTravail=1
											AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
											AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
											AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
											AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."' ";
										$resultSecurite=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultSecurite);
										$nbAvecArret+=$nb;
										if($nb>0){
											if($listeAT<>""){$listeAT.=", ";}
											$listeAT.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeATv2<>""){$listeATv2.=", ";}
											$listeATv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										$req="SELECT Id FROM moris_moisprestation_securite 
											WHERE Suppr=0 
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
											AND AvecArret=0
											AND AccidentTrajet=0 
											UNION
											SELECT Id
											FROM rh_personne_at 
											WHERE rh_personne_at.Suppr=0 
											AND rh_personne_at.ArretDeTravail=0
											AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
											AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
											AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
											AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
										$resultSecurite=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultSecurite);
										$nbSansArret+=$nb;
										if($nb>0){
											if($listeSansAT<>""){$listeSansAT.=", ";}
											$listeSansAT.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeSansATv2<>""){$listeSansATv2.=", ";}
											$listeSansATv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										$req="SELECT Id FROM moris_moisprestation_securite 
											WHERE Suppr=0 
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
											AND AccidentTrajet=1 
											UNION
											SELECT Id
											FROM rh_personne_at 
											WHERE rh_personne_at.Suppr=0 
											AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)>0
											AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
											AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
											AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
										$resultSecurite=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultSecurite);
										$nbAccidentTrajet+=$nb;
										if($nb>0){
											if($listeTrajet<>""){$listeTrajet.=", ";}
											$listeTrajet.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeTrajetv2<>""){$listeTrajetv2.=", ";}
											$listeTrajetv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										
										$req="SELECT Id FROM moris_moisprestation_ncdac
											WHERE Suppr=0 
											AND NC_DAC='NC'
											AND Progression=0
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
										$resultNC=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultNC);
										$nbNC+=$nb;
										if($nb>0){
											if($listeNC<>""){$listeNC.=", ";}
											$listeNC.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeNCv2<>""){$listeNCv2.=", ";}
											$listeNCv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										$req="SELECT Id FROM moris_moisprestation_ncdac
											WHERE Suppr=0 
											AND NC_DAC='NC Niv 2'
											AND Progression=0
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
										$resultNC=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultNC);
										$nbNC2+=$nb;
										if($nb>0){
											if($listeNC2<>""){$listeNC2.=", ";}
											$listeNC2.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeNC2v2<>""){$listeNC2v2.=", ";}
											$listeNC2v2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										$req="SELECT Id FROM moris_moisprestation_ncdac
											WHERE Suppr=0 
											AND NC_DAC='NC Niv 3'
											AND Progression=0
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
										$resultNC=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultNC);
										$nbNC3+=$nb;
										if($nb>0){
											if($listeNC3<>""){$listeNC3.=", ";}
											$listeNC3.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeNC3v2<>""){$listeNC3v2.=", ";}
											$listeNC3v2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										$req="SELECT Id FROM moris_moisprestation_ncdac
											WHERE Suppr=0 
											AND NC_DAC='RC'
											AND Progression=0
											AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
										$resultNC=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($resultNC);
										$nbRC+=$nb;
										if($nb>0){
											if($listeRC<>""){$listeRC.=", ";}
											$listeRC.=$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "));
											if($listeRCv2<>""){$listeRCv2.=", ";}
											$listeRCv2.="<strong style=\'cursor:pointer;\' onclick=\'OuvreFenetreCockpit(".$LigneMoisPrestationEC['Id_Prestation'].",".$anneeEC.",".$moisEC.")\'>".$nb." - ".substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))."</strong>";
										}
										
										if($LigneMoisPrestationEC['PasActivite']==0){
											if($LigneMoisPrestationEC['TendanceManagement']==0){$nbVert++;}
											elseif($LigneMoisPrestationEC['TendanceManagement']==1){$nbOrange++;}
											elseif($LigneMoisPrestationEC['TendanceManagement']==2){$nbRouge++;}
										}
										
									}
								}
								
								$total=0;
								$nbEval=0;
								$note=0;
								if($nbResultaMoisPrestaEC2>0){
									while($LigneMoisPrestationEC2=mysqli_fetch_array($resultEC2)){
										
										if($LigneMoisPrestationEC2['EvaluationQualite']>-1){
											$total+=$LigneMoisPrestationEC2['EvaluationQualite'];
											$nbEval++;
										}
										if($LigneMoisPrestationEC2['EvaluationDelais']>-1){
											$total+=$LigneMoisPrestationEC2['EvaluationDelais'];
											$nbEval++;
										}
										if($LigneMoisPrestationEC2['EvaluationCompetencePersonnel']>-1){
											$total+=$LigneMoisPrestationEC2['EvaluationCompetencePersonnel'];
											$nbEval++;
										}
										if($LigneMoisPrestationEC2['EvaluationAutonomie']>-1){
											$total+=$LigneMoisPrestationEC2['EvaluationAutonomie'];
											$nbEval++;
										}
										if($LigneMoisPrestationEC2['EvaluationAnticipation']>-1){
											$total+=$LigneMoisPrestationEC2['EvaluationAnticipation'];
											$nbEval++;
										}
										if($LigneMoisPrestationEC2['EvaluationCommunication']>-1){
											$total+=$LigneMoisPrestationEC2['EvaluationCommunication'];
											$nbEval++;
										}
									}
									if($nbEval>0){
										$note=round($total/$nbEval,2);
									}
								}

								if($nbPrestaCompetence>0){
									$ratioCompetence=round($ratioCompetence/$nbPrestaCompetence,2);
								}
								if($nbPrestaQualif>0){
									$ratioQualif=round($ratioQualif/$nbPrestaQualif,2);
								}
								if($nbPrestaOTD>0){
									$ConformeOTD=round(($ConformeOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
									$NonConformeOTD=round(($NonConformeOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
									$ToleranceOTD=round(($ToleranceOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
								}
								if($nbPrestaOQD>0){
									$ConformeOQD=round(($ConformeOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
									$NonConformeOQD=round(($NonConformeOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
									$ToleranceOQD=round(($ToleranceOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
								}
								
								if($nbPrestaOTD2>0){
									$ratioOTDInf=round(($ratioOTDInf/$nbPrestaOTD2)*100,1);
									$ratioOTDEgal=round(($ratioOTDEgal/$nbPrestaOTD2)*100,1);
									$ratioOTDSup=round(($ratioOTDSup/$nbPrestaOTD2)*100,1);
								}
								if($nbPrestaOQD2>0){
									$ratioOQDInf=round(($ratioOQDInf/$nbPrestaOQD2)*100,1);
									$ratioOQDEgal=round(($ratioOQDEgal/$nbPrestaOQD2)*100,1);
									$ratioOQDSup=round(($ratioOQDSup/$nbPrestaOQD2)*100,1);
								}
								
								$req="SELECT DISTINCT(Id_Prestation)
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC."
								AND Mois=".$moisEC."
								AND Suppr=0
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$resultActivite=mysqli_query($bdd,$req);
								$nbActivite=mysqli_num_rows($resultActivite);
								
								$req="SELECT DISTINCT(Id_Prestation)
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC."
								AND Mois=".$moisEC."
								AND Suppr=0
								AND (YEAR(DateEnvoiDemandeSatisfaction)=".$anneeEC." AND MONTH(DateEnvoiDemandeSatisfaction)=".$moisEC.")
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$resultDemande=mysqli_query($bdd,$req);
								$nbDemande=mysqli_num_rows($resultDemande);
								
								if($nbActivite>0){
									$nbDemande=round(($nbDemande/$nbActivite)*100,0);
								}
								
								$req="SELECT DISTINCT(Id_Prestation)
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC."
								AND Mois=".$moisEC."
								AND Suppr=0
								AND (YEAR(DerniereDateEvaluation)=".$anneeEC." AND MONTH(DerniereDateEvaluation)=".$moisEC.")
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$rowPlateforme['Id']." 
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_moisprestation.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								";
								if($listePrestation2<>""){
									$req.="AND Id_Prestation IN(".$listePrestation2.") ";
								}
								$resultRetour=mysqli_query($bdd,$req);
								$nbRetour=mysqli_num_rows($resultRetour);
								
								if($nbActivite>0){
									$nbRetour=round(($nbRetour/$nbActivite)*100,0);
								}
								
								$nbVertPdp2=0;
								$nbOrangePdp2=0;
								$nbRougePdp2=0;
								$nbNoirPdp2=0;
								if($nbPrestaPdp>0){
									$nbVertPdp2=round(($nbVertPdp/$nbPrestaPdp)*100,1);
									$nbOrangePdp2=round(($nbOrangePdp/$nbPrestaPdp)*100,1);
									$nbRougePdp2=round(($nbRougePdp/$nbPrestaPdp)*100,1);
									$nbNoirPdp2=round(($nbNoirPdp/$nbPrestaPdp)*100,1);
								}
								
								if($nbPrestaPdp>0){
									$arrayPDP[$ipdp]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbVert" => valeurSinonNull($nbVertPdp2),"NbOrange" => valeurSinonNull($nbOrangePdp2),"NbRouge" => valeurSinonNull($nbRougePdp2),"NbNoir" => valeurSinonNull($nbNoirPdp2),"Objectif" => valeurSinonNull($ObjectifPDP));
									$arrayPDP2[$ipdp]=array("Mois" => $rowPlateforme['Libelle'],"NbVert" => valeurSinonNull($nbVertPdp),"NbOrange" => valeurSinonNull($nbOrangePdp),"NbRouge" => valeurSinonNull($nbRougePdp),"NbNoir" => valeurSinonNull($nbNoirPdp),"listeOrange" => $listeOrange,"listeRouge" => $listeRouge,"listeNoir" => $listeNoir);
									$ipdp++;
								}
								
								if($nbPrestaOTD2>0){
									$array2OTD[$iotd2]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"ratioInf" => valeurSinonNull($ratioOTDInf),"ratioEgal" => valeurSinonNull($ratioOTDEgal),"ratioSup" => valeurSinonNull($ratioOTDSup),"ValeurratioInf" => valeurSinonNull($ratioOTDInf2),"ValeurratioEgal" => valeurSinonNull($ratioOTDEgal2),"ValeurratioSup" => valeurSinonNull($ratioOTDSup2),"Objectif" => valeurSinonNull($ObjectifOTDActivite));
									$array2OTD2[$iotd2]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"ratioInf" => valeurSinonNull($ratioOTDInf2),"ratioEgal" => valeurSinonNull($ratioOTDEgal2),"ratioSup" => valeurSinonNull($ratioOTDSup2),"listeOTDInf" => $listeOTDInf,"Objectif" => valeurSinonNull($ObjectifOTDActivite));
									$iotd2++;
								}
								if($nbPrestaOQD2>0){
									$array2OQD[$ioqd2]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"ratioInf" => valeurSinonNull($ratioOQDInf),"ratioEgal" => valeurSinonNull($ratioOQDEgal),"ratioSup" => valeurSinonNull($ratioOQDSup),"Objectif" => valeurSinonNull($ObjectifOQDActivite));
									$array2OQD2[$ioqd2]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"ratioInf" => valeurSinonNull($ratioOQDInf2),"ratioEgal" => valeurSinonNull($ratioOQDEgal2),"ratioSup" => valeurSinonNull($ratioOQDSup2),"listeOQDInf" => $listeOQDInf,"Objectif" => valeurSinonNull($ObjectifOQDActivite));
									$ioqd2++;
								}
								
								$productiviteBrut=null;
								if($tempsPasse>0){
									$productiviteBrut=round($tempsObjectif/$tempsPasse,2);
								}
								$productiviteCorrigee=null;
								if($tempsPasse>0){
									$productiviteCorrigee=round($tempsAlloue/$tempsPasse,2);
								}
								if($ConformeOTD>0 || $ToleranceOTD>0 || $NonConformeOTD>0 || $ConformeOQD>0 || $ToleranceOQD>0 || $NonConformeOQD>0 
								|| $productiviteBrut>0 || $productiviteCorrigee>0 
								|| $ratioCompetence>0 || $ratioQualif>0 || $nbMonoCompetences>0 || $nbActiviteMonoCompetences>0
								|| $nbAccidentTrajet>0 || $nbAvecArret>0 || $nbSansArret>0 || $nbNC>0 || $nbNC2>0 || $nbNC3>0 || $nbRC>0 || $nbVert>0 || $nbOrange>0 || $nbRouge>0 || $note>0 || $nbDemande>0 || $nbRetour>0){
									if($nbPrestaOTD>0){
										$arrayOTD[$iotd]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbConforme" => valeurSinonNull($ConformeOTD),"NbTolerance" => valeurSinonNull($ToleranceOTD),"NbRetour" => valeurSinonNull($NonConformeOTD),"Objectif" => valeurSinonNull($ObjectifOTD),"ValeurNbConforme" => valeurSinonNull($ConformeOTD2),"ValeurNbTolerance" => valeurSinonNull($ToleranceOTD2),"ValeurNbRetour" => valeurSinonNull($NonConformeOTD2));
										$arrayOTD2[$iotd]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbConforme" => valeurSinonNull($ConformeOTD2),"NbTolerance" => valeurSinonNull($ToleranceOTD2),"NbRetour" => valeurSinonNull($NonConformeOTD2),"OTD" => valeurSinonNull($ConformeOTD),"Objectif" => valeurSinonNull($ObjectifOTD));
										$iotd++;
									}
									if($nbPrestaOQD>0){
										$arrayOQD[$ioqd]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbConforme" => valeurSinonNull($ConformeOQD),"NbTolerance" => valeurSinonNull($ToleranceOQD),"NbRetour" => valeurSinonNull($NonConformeOQD),"Objectif" => valeurSinonNull($ObjectifOQD));
										$arrayOQD2[$ioqd]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbConforme" => valeurSinonNull($ConformeOQD2),"NbTolerance" => valeurSinonNull($ToleranceOQD2),"NbRetour" => valeurSinonNull($NonConformeOQD2),"OQD" => valeurSinonNull($ConformeOQD),"Objectif" => valeurSinonNull($ObjectifOQD));
										$ioqd++;
									}
									if($nbPrestaProd>0){
										$productivite[$iprod]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"ProductiviteBrut" => valeurSinonNull($productiviteBrut),"Objectif" => valeurSinonNull($ObjectifProductivite),"ProductiviteCorrigee" => valeurSinonNull($productiviteCorrigee),"listeBrut" => $listeBrut,"listeCorrigee" => $listeCorrigee);
										$iprod++;
									}
									
									if($_SESSION['MORIS_VisionMonoCompetence']==0){
										if($nbVolumeMonoMax<$nbActiviteMonoCompetences){
											$nbVolumeMonoMax=$nbActiviteMonoCompetences+50;
										}
									}
									else{
										if($nbVolumeMonoMax<$nbMonoCompetences){
											$nbVolumeMonoMax=$nbMonoCompetences+50;
										}
									}
									
									$arrayCompetences[$i]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"Competences" => valeurSinonNull($ratioCompetence),"TauxQualif" => valeurSinonNull($ratioQualif),"NbMonoCompetence" => valeurSinonNull($nbMonoCompetences),"NbActiviteMonoCompetences" => valeurSinonNull($nbActiviteMonoCompetences),"ObjectifTauxQualif" => valeurSinonNull($ObjectifTauxQualif),"ObjectifTauxPolyvalence" => valeurSinonNull($ObjectifTauxPolyvalence));
									$arraySecurite[$i]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbTrajet" => valeurSinonNull($nbAccidentTrajet),"NbNonTrajetAvecArret" => valeurSinonNull($nbAvecArret),"NbNonTrajetSansArret" => valeurSinonNull($nbSansArret),"listeAT" => $listeAT,"listeSansAT" => $listeSansAT, "listeTrajet" => $listeTrajet,"listeATv2" => $listeATv2,"listeSansATv2" => $listeSansATv2, "listeTrajetv2" => $listeTrajetv2);
									$arrayNbNC[$i]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NC1" => valeurSinonNull($nbNC),"NC2" => valeurSinonNull($nbNC2),"NC3" => valeurSinonNull($nbNC3),"RC" => valeurSinonNull($nbRC),"listeNC" => $listeNC,"listeNC2" => $listeNC2,"listeNC3" => $listeNC3,"listeRC" => $listeRC,"listeNCv2" => $listeNCv2,"listeNC2v2" => $listeNC2v2,"listeNC3v2" => $listeNC3v2,"listeRCv2" => $listeRCv2);
									$arrayManagement[$i]=array("Mois" => utf8_encode($rowPlateforme['Libelle']),"NbVert" => valeurSinonNull($nbVert),"NbOrange" => valeurSinonNull($nbOrange),"NbRouge" => valeurSinonNull($nbRouge));
									$arrayNewPRM[$i]=array("Abs" => utf8_encode($rowPlateforme['Libelle']),"Note" => valeurSinonNull($note),"Objectif" => $ObjectifSatisfactionClient,"NbActivite" => $nbActivite,"NbDemande" => $nbDemande,"NbRetour" => $nbRetour);
									$i++;
								}
							}
						}
						$tabPRM="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabPRM.= "<tr>";
							$tabPRM.= "<td style='border:1px solid black'></td>";
						foreach($arrayNewPRM as $prm){
							$tabPRM.= "<td style='border:1px solid black;text-align:center;'>".utf8_decode($prm['Abs'])."</td>";
						}
						$tabPRM.= "</tr>";
						$tabPRM.= "<tr>";
							$tabPRM.= "<td style='border:1px solid black'>Nb Activité</td>";
						foreach($arrayNewPRM as $prm){
							$tabPRM.= "<td style='border:1px solid black;text-align:center;'>".$prm['NbActivite']."</td>";
						}
						$tabPRM.= "</tr>";
						$tabPRM.= "<tr>";
							$tabPRM.= "<td style='border:1px solid black'>% Demande</td>";
						foreach($arrayNewPRM as $prm){
							$tabPRM.= "<td style='border:1px solid black;text-align:center;'>".$prm['NbDemande']."%</td>";
						}
						$tabPRM.= "</tr>";
						$tabPRM.= "<tr>";
							$tabPRM.= "<td style='border:1px solid black'>% Retour</td>";
						foreach($arrayNewPRM as $prm){
							$tabPRM.= "<td style='border:1px solid black;text-align:center;'>".$prm['NbRetour']."%</td>";
						}
						$tabPRM.= "</tr>";
						$tabPRM.="</table>";

						$tabOTD="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'></td>";
						foreach($arrayOTD2 as $otd){
							$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".utf8_decode($otd['Mois'])."</td>";
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>C</td>";
						foreach($arrayOTD2 as $otd){
							$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbConforme']."</td>";
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($arrayOTD2 as $otd){
							$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbTolerance']."</td>";
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>NC</td>";
						foreach($arrayOTD2 as $otd){
							$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbRetour']."</td>";
						}
						$tabOTD.= "</tr>";
						$tabOTD.= "<tr>";
							$tabOTD.= "<td style='border:1px solid black'>OTD %</td>";
						foreach($arrayOTD2 as $otd){
							$couleur="#00d200";
							if($otd['OTD']<$otd['Objectif']){$couleur="#ff5b5b";}
							if($otd['OTD']>0){
								$leHover="";
								$span="";
								$tabOTD.= "<td ".$leHover." style='border:1px solid black;background-color:".$couleur.";text-align:center;cursor:pointer;'>".$span." ".$otd['OTD']."%</td>";
							}
							else{
								$tabOTD.= "<td style='border:1px solid black;text-align:center;'></td>";
							}
						}
						$tabOTD.= "</tr>";
						$tabOTD.="</table>";
						
						
						$tabOQD="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'></td>";
						foreach($arrayOQD2 as $oqd){
							$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".utf8_decode($oqd['Mois'])."</td>";
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>C</td>";
						foreach($arrayOQD2 as $oqd){
							$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbConforme']."</td>";
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($arrayOQD2 as $oqd){
							$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbTolerance']."</td>";
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>NC</td>";
						foreach($arrayOQD2 as $oqd){
							$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbRetour']."</td>";
						}
						$tabOQD.= "</tr>";
						$tabOQD.= "<tr>";
							$tabOQD.= "<td style='border:1px solid black'>OQD %</td>";
						foreach($arrayOQD2 as $oqd){
							$couleur="#00d200";
							if($oqd['OQD']<$otd['Objectif']){$couleur="#ff5b5b";}
							if($oqd['OQD']>0){
								$leHover="";
								$span="";
							$tabOQD.= "<td ".$leHover." style='border:1px solid black;background-color:".$couleur.";text-align:center;cursor:pointer;'>".$span." ".$oqd['OQD']."%</td>";
							}
							else{
								$tabOQD.= "<td style='border:1px solid black;text-align:center;'></td>";
							}
						}
						$tabOQD.= "</tr>";
						$tabOQD.="</table>";
						
						$tabOTD2="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'></td>";
						foreach($array2OTD2 as $otd){
							$tabOTD2.= "<td style='border:1px solid black;text-align:center;'>".utf8_decode($otd['Mois'])."</td>";
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'>C</td>";
						foreach($array2OTD2 as $otd){
							$couleur="";
							if($otd['ratioSup']>0){
								$couleur="background-color:#00d200";
							}
							$tabOTD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$otd['ratioSup']."</td>";
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($array2OTD2 as $otd){
							$couleur="";
							if($otd['ratioEgal']>0){
								$couleur="background-color:#ffd757";
							}
							$tabOTD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$otd['ratioEgal']."</td>";
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.= "<tr>";
							$tabOTD2.= "<td style='border:1px solid black'>NC</td>";
						foreach($array2OTD2 as $otd){
							$couleur="";
							$leHover="";
							$span="";
							if($otd['ratioInf']>0){
								$couleur="background-color:#ff5b5b";
								if($otd['listeOTDInf']<>""){
									$leHover="id='leHover'";
									$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'OTDInf\',\''.$otd['listeOTDInf'].'\',\''.$otd['Mois'].'<br>NC\')" /><span>'.$otd['listeOTDInf'].'</span>';
								}
							}
							$tabOTD2.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$otd['ratioInf']."</td>";
						}
						$tabOTD2.= "</tr>";
						$tabOTD2.="</table>";
						
						$tabOQD2="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'></td>";
						foreach($array2OQD2 as $oqd){
							$tabOQD2.= "<td style='border:1px solid black;text-align:center;'>".utf8_decode($oqd['Mois'])."</td>";
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'>C</td>";
						foreach($array2OQD2 as $oqd){
							$couleur="";
							if($oqd['ratioSup']>0){
								$couleur="background-color:#00d200";
							}
							$tabOQD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$oqd['ratioSup']."</td>";
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'>Tolerance</td>";
						foreach($array2OQD2 as $oqd){
							$couleur="";
							if($oqd['ratioEgal']>0){
								$couleur="background-color:#ffd757";
							}
							$tabOQD2.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$oqd['ratioEgal']."</td>";
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.= "<tr>";
							$tabOQD2.= "<td style='border:1px solid black'>NC</td>";
						foreach($array2OQD2 as $oqd){
							$couleur="";
							$leHover="";
							$span="";
							if($oqd['ratioInf']>0){
								$couleur="background-color:#ff5b5b";
								if($oqd['listeOQDInf']<>""){
									$leHover="id='leHover'";
									$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'OQDInfo\',\''.$oqd['listeOQDInf'].'\',\''.$oqd['Mois'].'<br>NC\')" /><span>'.$oqd['listeOQDInf'].'</span>';
								}
							}
							$tabOQD2.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$oqd['ratioInf']."</td>";
						}
						$tabOQD2.= "</tr>";
						$tabOQD2.="</table>";
						
						$tabPDP="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'></td>";
						foreach($arrayPDP2 as $pdp){
							$tabPDP.= "<td style='border:1px solid black;text-align:center;'>".$pdp['Mois']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>> 1 mois</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							if($pdp['NbVert']>0){
								$couleur="background-color:#00d200";
							}
							$tabPDP.= "<td style='border:1px solid black;text-align:center;".$couleur."'>".$pdp['NbVert']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>< 1 mois</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							$leHover="";
							$span="";
							if($pdp['NbOrange']>0){
								$couleur="background-color:#ffd757";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'PDP\',\''.$pdp['listeOrange'].'\',\''.$pdp['Mois'].'<br>< 1 mois\')" /><span>'.$pdp['listeOrange'].'</span>';
							}							
							$tabPDP.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$pdp['NbOrange']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>Dépassé</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							$leHover="";
							$span="";
							if($pdp['NbRouge']>0){
								$couleur="background-color:#ff5b5b";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'PDP\',\''.$pdp['listeRouge'].'\',\''.$pdp['Mois'].'<br>Dépassé\')" /><span>'.$pdp['listeRouge'].'</span>';
							}							
							$tabPDP.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$pdp['NbRouge']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.= "<tr>";
							$tabPDP.= "<td style='border:1px solid black'>Non renseigné</td>";
						foreach($arrayPDP2 as $pdp){
							$couleur="";
							$leHover="";
							$span="";
							if($pdp['NbNoir']>0){
								$couleur="background-color:#bdbdbd";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'PDP\',\''.$pdp['listeNoir'].'\',\''.$pdp['Mois'].'<br>Non renseigné\')" /><span>'.$pdp['listeNoir'].'</span>';
							}							
							$tabPDP.= "<td ".$leHover." style='border:1px solid black;text-align:center;".$couleur.";cursor:pointer;'>".$span." ".$pdp['NbNoir']."</td>";
						}
						$tabPDP.= "</tr>";
						$tabPDP.="</table>";
						
						$tabProductivite="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$tabProductivite.= "<tr>";
							$tabProductivite.= "<td style='border:1px solid black'></td>";
						foreach($productivite as $prod){
							$tabProductivite.= "<td style='border:1px solid black;text-align:center;'>".utf8_decode($prod['Mois'])."</td>";
						}
						$tabProductivite.= "</tr>";

						$tabProductivite.= "<tr>";
							$tabProductivite.= "<td style='border:1px solid black'>Productivité brute</td>";
						foreach($productivite as $prod){
							if($prod['ProductiviteBrut']>0){
								$leHover="";
								$span="";
								if($prod['listeBrut']<>""){
									$leHover="id='leHover'";
									$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'Productivite\',\''.$prod['listeBrut'].'\',\''.$prod['Mois'].'<br>Productivité brute\')" /><span>'.$prod['listeBrut'].'</span>';
								
								}
								$tabProductivite.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;'>".$span." ".$prod['ProductiviteBrut']."</td>";
							}
							else{
								$tabProductivite.= "<td style='border:1px solid black;text-align:center;'></td>";
							}
						}
						$tabProductivite.= "</tr>";
						
						$tabProductivite.= "<tr>";
							$tabProductivite.= "<td style='border:1px solid black'>Productivité corrigée</td>";
						foreach($productivite as $prod){
							if($prod['ProductiviteCorrigee']>0){
								$leHover="";
								$span="";
								if($prod['listeCorrigee']<>""){
									$leHover="id='leHover'";
									$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'Productivite\',\''.$prod['listeCorrigee'].'\',\''.$prod['Mois'].'<br>Productivité corrigée\')" /><span>'.$prod['listeCorrigee'].'</span>';
								}
								$tabProductivite.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;'>".$span." ".$prod['ProductiviteCorrigee']."</td>";
							}
							else{
								$tabProductivite.= "<td style='border:1px solid black;text-align:center;'></td>";
							}
						}
						$tabProductivite.= "</tr>";
						$tabProductivite.="</table>";
						
						$tabNC="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$ligneNCMois="<tr><td style='border:1px solid black'></td>";
						$ligneNC1="<tr><td style='border:1px solid black'>NC Niv 1</td>";
						$ligneNC2="<tr><td style='border:1px solid black'>NC Niv 2</td>";
						$ligneNC3="<tr><td style='border:1px solid black'>NC Niv 3</td>";	
						$ligneRC="<tr><td style='border:1px solid black'>RC</td>";
						foreach($arrayNbNC as $nc){
							$ligneNCMois.= "<td style='border:1px solid black;text-align:center;'>".$nc['Mois']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['NC1']<>""){
								$couleur="background-color:#3d7ad5;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeNCv2'].'\',\''.$nc['Mois'].'<br>NC Niv 1\')" /><span>'.$nc['listeNC'].'</span>';
							}
							$ligneNC1.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['NC1']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['NC2']<>""){
								$couleur="background-color:#29dae9;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeNC2v2'].'\',\''.$nc['Mois'].'<br>NC Niv 2\')" /><span>'.$nc['listeNC2'].'</span>';
							}
							$ligneNC2.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['NC2']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['NC3']<>""){
								$couleur="background-color:#f8ff6d;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeNC3v2'].'\',\''.$nc['Mois'].'<br>NC Niv 3\')" /><span>'.$nc['listeNC3'].'</span>';
							}
							$ligneNC3.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['NC3']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($nc['listeRC']<>""){
								$couleur="background-color:#f3b479;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'NC\',\''.$nc['listeRCv2'].'\',\''.$nc['Mois'].'<br>RC\')" /><span>'.$nc['listeRC'].'</span>';
							}
							$ligneRC.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$nc['RC']."</td>";
						}
						$ligneNCMois.= "</tr>";
						$ligneNC1.= "</tr>";
						$ligneNC2.= "</tr>";
						$ligneNC3.= "</tr>";
						$ligneRC.= "</tr>";
						
						$tabNC.=$ligneNCMois.$ligneNC1.$ligneNC2.$ligneNC3.$ligneRC;
						$tabNC.="</table>";
						
						$tabAT="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						$ligneATMois="<tr><td style='border:1px solid black'></td>";
						$ligneATrajet="<tr><td style='border:1px solid black'>Nb accident de trajet</td>";
						$ligneSansAT="<tr><td style='border:1px solid black'>Nb accident sans arrêt de travail</td>";
						$ligneAvecAT="<tr><td style='border:1px solid black'>Nb accident avec arrêt de travail</td>";	
						foreach($arraySecurite as $securite){
							$ligneATMois.= "<td style='border:1px solid black;text-align:center;'>".$securite['Mois']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($securite['NbTrajet']<>""){
								$couleur="background-color:#3d7ad5;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'AT\',\''.$securite['listeTrajetv2'].'\',\''.$securite['Mois'].'<br>Nb accident de trajet\')" /><span>'.$securite['listeTrajet'].'</span>';
							}
							$ligneATrajet.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$securite['NbTrajet']."</td>";
							
							$couleur="";
							$leHover="";
							$span="";
							if($securite['NbNonTrajetAvecArret']<>""){
								$couleur="background-color:#dbb637;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'AT\',\''.$securite['listeATv2'].'\',\''.$securite['Mois'].'<br>Nb accident avec arrêt de travail\')" /><span>'.$securite['listeAT'].'</span>';
							}
							$ligneAvecAT.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$securite['NbNonTrajetAvecArret']."</td>";
							
							
							$couleur="";
							$leHover="";
							$span="";
							if($securite['NbNonTrajetSansArret']<>""){
								$couleur="background-color:#9fb1c5;";
								$leHover="id='leHover'";
								$span='<img src="../../Images/etoile.png" border="0" onclick="AfficherInfoBulle(\'AT\',\''.$securite['listeSansATv2'].'\',\''.$securite['Mois'].'<br>Nb accident sans arrêt de travail\')" /><span>'.$securite['listeSansAT'].'</span>';
							}
							$ligneSansAT.= "<td ".$leHover." style='border:1px solid black;text-align:center;cursor:pointer;".$couleur."'>".$span." ".$securite['NbNonTrajetSansArret']."</td>";
							
							
						}
						$ligneNCMois.= "</tr>";
						$ligneATrajet.= "</tr>";
						$ligneSansAT.= "</tr>";
						$ligneAvecAT.= "</tr>";
						
						$tabAT.=$ligneATMois.$ligneATrajet.$ligneAvecAT.$ligneSansAT;
						
						$tabAT.="</table>";
					}
					elseif($_SESSION['FiltreRECORD_Vision']==3){
						$reqFamille="SELECT DISTINCT Id_Famille,
						(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Famille
						FROM moris_moisprestation_famille
						LEFT JOIN moris_moisprestation
						ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
						WHERE Id_Famille>0
						AND moris_moisprestation.Suppr=0
						AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))='".$_SESSION['MORIS_Annee'].'_'.$_SESSION['MORIS_Mois']."'
						";
						if($listePrestation2<>""){
							$reqFamille.="AND Id_Prestation IN(".$listePrestation2.") ";
						}
						$reqFamille.="ORDER BY Famille";
						
						//Liste des plateformes sélectionnées 
						$req="SELECT DISTINCT new_competences_prestation.Id,
								new_competences_prestation.Libelle,ChargeADesactive,ProductiviteADesactive,ToleranceOTDOQD
								FROM new_competences_prestation
								LEFT JOIN new_competences_plateforme
								ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
								WHERE new_competences_prestation.UtiliseMORIS>0 ";
						if($_SESSION['FiltreRECORD_Prestation']<>""){
							$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
						}
						$req.="ORDER BY new_competences_prestation.Libelle";
						$resultPresta=mysqli_query($bdd,$req);
						$nbPresta=mysqli_num_rows($resultPresta);
						
						$anneeEC=$_SESSION['MORIS_Annee'];
						$moisEC=$_SESSION['MORIS_Mois'];

						$laDate1=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");
						$laDate=date("Y-m-d",strtotime($laDate1." +0 month"));

						$laDate2=date("Y-m-d",strtotime($laDate." +0 month"));
						$annee_1=date("Y",strtotime($laDate2." -1 month"));
						$mois_1=date("m",strtotime($laDate2." -1 month"));
						$annee_2=date("Y",strtotime($laDate2." -2 month"));
						$mois_2=date("m",strtotime($laDate2." -2 month"));
						$annee_3=date("Y",strtotime($laDate2." -3 month"));
						$mois_3=date("m",strtotime($laDate2." -3 month"));
						$annee_4=date("Y",strtotime($laDate2." -4 month"));
						$mois_4=date("m",strtotime($laDate2." -4 month"));
						$annee_5=date("Y",strtotime($laDate2." -5 month"));
						$mois_5=date("m",strtotime($laDate2." -5 month"));
						$annee_6=date("Y",strtotime($laDate2." -6 month"));
						$mois_6=date("m",strtotime($laDate2." -6 month"));
						
						if($nbPresta>0){
							while($rowPresta=mysqli_fetch_array($resultPresta)){
								$presta=substr($rowPresta['Libelle'],0,strpos($rowPresta['Libelle']," "));
								
								$CapaInterne=0;
								$CapaExterne=0;
								$ChargeTotal=0;
								$CapaInternePrev=0;
								$CapaExternePrev=0;
								
								$req="SELECT ";
									if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0) AS InterneCurrent,";
									if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS SubContractorCurrent,
									IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
										PermanentCurrent+TemporyCurrent+InterneCurrent,
										COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaInterne,
									IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
										SubContractorCurrent,
										COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaExterne
									";
									$req.="FROM moris_moisprestation
									WHERE Annee=".$anneeEC." 
									AND Mois=".$moisEC."
									AND Suppr=0 
									AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
									AND moris_moisprestation.Id_Prestation =".$rowPresta['Id']." 
									AND ((";
									if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
									$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
									OR COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
									)";
									
									$resultEC=mysqli_query($bdd,$req);
									$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
									
									if($nbResultaMoisPrestaEC>0){
										$LigneMoisPrestationEC=mysqli_fetch_array($resultEC);
										$CapaInterne+=$LigneMoisPrestationEC['CapaInterne'];
										$CapaExterne+=$LigneMoisPrestationEC['CapaExterne'];
										$ChargeTotal+=$LigneMoisPrestationEC['InterneCurrent']+$LigneMoisPrestationEC['SubContractorCurrent'];
									}
									else{
										if($anneeEC."_".$moisEC>=$anneeDuJour."_".$moisDuJour && $anneeEC."_".$moisEC<=$anneeDuJour7."_".$moisDuJour7){
											//Rechercher la prévision sur l'un des mois précédent
											$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
											FROM moris_moisprestation
											WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ";
											if($anneeEC."_".$moisEC==$anneeDuJour."_".$moisDuJour){$req.="('".$annee_1."_".$mois_1."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour1."_".$moisDuJour1){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour2."_".$moisDuJour2){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour3."_".$moisDuJour3){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour4."_".$moisDuJour4){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour5."_".$moisDuJour5){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour6."_".$moisDuJour6){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											if($anneeEC."_".$moisEC==$anneeDuJour7."_".$moisDuJour7){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
											$req.="AND Suppr=0 
											AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
											AND moris_moisprestation.Id_Prestation IN (".$rowPresta['Id'].")
											AND ((";
											if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
											$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
												OR COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
												)
											ORDER BY Annee DESC, Mois DESC ";
											
											/*$req.="AND Suppr=0 
											AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
											AND moris_moisprestation.Id_Prestation IN (".$rowPresta['Id'].")
											ORDER BY Annee DESC, Mois DESC ";*/
											
											$resultEC2=mysqli_query($bdd,$req);
											$nbResultaMoisPrestaEC2=mysqli_num_rows($resultEC2);
											if($nbResultaMoisPrestaEC2>0){
												$LigneMoisPrestationEC2=mysqli_fetch_array($resultEC2);
												$leMoisCharge="";
												if($LigneMoisPrestationEC2['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
												elseif($LigneMoisPrestationEC2['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
												if($leMoisCharge<>""){
													//Rechercher la prévision sur l'un des mois précédent
													$req="SELECT ";
													if($bFamilleIndefini==1){$req.="M".$leMoisCharge."+";}
													$req.="COALESCE((SELECT SUM(M".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS M,";
													$req.="COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaInterne,
													COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaExterne
													FROM moris_moisprestation
													WHERE Id=".$LigneMoisPrestationEC2['Id']." ";
													$resultECF2=mysqli_query($bdd,$req);
													$nbResultaMoisPrestaECF2=mysqli_num_rows($resultECF2);
													if($nbResultaMoisPrestaECF2>0){
														$LigneMoisPrestationECF2=mysqli_fetch_array($resultECF2);
														$ChargeTotal+=$LigneMoisPrestationECF2['M'];
														$CapaInternePrev+=$LigneMoisPrestationECF2['CapaInterne'];
														$CapaExternePrev+=$LigneMoisPrestationECF2['CapaExterne'];
													}
												}
											}
										}
									}
									
								if($rowPresta['ChargeADesactive']==0){
									if($CapaInterne>0 || $CapaExterne>0 || $ChargeTotal>0 || $CapaInternePrev>0 || $CapaExternePrev>0){
										$arrayBesoin[$i2]=array("Mois" => utf8_encode($presta),"Interne" =>  valeurSinonNull($CapaInterne),"SubContractor" => valeurSinonNull($CapaExterne),"Prevision" => valeurSinonNull($ChargeTotal), "InternePrevi" => valeurSinonNull($CapaInternePrev), "ExternePrevi" => valeurSinonNull($CapaExternePrev));
										$i2++;
									}
								}
								
								$req="SELECT Id,
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								Id_Prestation,TempsAlloue,TempsPasse,TempsObjectif,
								(SELECT ToleranceOTDOQD FROM new_competences_prestation WHERE Id=Id_Prestation) AS ToleranceOTDOQD,
								IF(ObjectifClientOTD=0,100,ObjectifClientOTD) AS ObjectifClientOTD,
								NbLivrableOTD,NbRetourClientOTD,OTD,ObjectifToleranceOTD,
								IF(ObjectifClientOQD=0,100,ObjectifClientOQD) AS ObjectifClientOQD,
								NbLivrableOQD,NbRetourClientOQD,OQD,ObjectifToleranceOQD,
								IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,PasOTD,
								IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,PasOQD,
								NbXTableauPolyvalence,NbLTableauPolyvalence,NbLivrableToleranceOTD,NbLivrableToleranceOQD,PasActivite,
								TendanceManagement,TauxQualif,NbMonoCompetence
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND Suppr=0 
								AND Id_Prestation = ".$rowPresta['Id']." ";
								$resultEC=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
								
								$req="SELECT Id,
								EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication
								FROM moris_moisprestation
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND Suppr=0 
								AND (
									EvaluationQualite>0
									OR EvaluationDelais>0
									OR EvaluationCompetencePersonnel>0
									OR EvaluationAutonomie>0
									OR EvaluationAnticipation>0
									OR EvaluationCommunication>0
									)
								AND YEAR(DerniereDateEvaluation)=".$anneeEC." AND MONTH(DerniereDateEvaluation)=".$moisEC."
								AND Id_Prestation = ".$rowPresta['Id']." ";
								$resultEC2=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaEC2=mysqli_num_rows($resultEC2);
								
								$productiviteBrut=0;
								$productiviteCorrigee=0;
								$tempsPasse=0;
								$tempsAlloue=0;
								$tempsObjectif=0;
								$objectif=1;

								$ConformeOTD=0;
								$NonConformeOTD=0;
								$ToleranceOTD=0;
								$ConformeOTD2=0;
								$NonConformeOTD2=0;
								$ToleranceOTD2=0;
								$ConformeOQD=0;
								$NonConformeOQD=0;
								$ToleranceOQD=0;
								$ConformeOQD2=0;
								$NonConformeOQD2=0;
								$ToleranceOQD2=0;
								$ObjectifOTD=0;
								$ObjectifOQD=0;
								$ratioCompetence=0;
								$ratioQualif=0;
								$nbAccidentTrajet=0;
								$nbAvecArret=0;
								$nbSansArret=0;
								$listeAT="";
								$listeTrajet="";
								$listeSansAT="";
								$listeATv2="";
								$listeTrajetv2="";
								$listeSansATv2="";
								$nbNC=0;
								$nbNC2=0;
								$nbNC3=0;
								$nbRC=0;
								$listeNC="";
								$listeNC2="";
								$listeNC3="";
								$listeRC="";
								$listeNCv2="";
								$listeNC2v2="";
								$listeNC3v2="";
								$listeRCv2="";
								$nbVert=0;
								$nbOrange=0;
								$nbRouge=0;
								$nbPrestaProd=0;
								$nbPrestaOTD=0;
								$nbPrestaOQD=0;
								$nbPrestaCompetence=0;
								$nbPrestaQualif=0;
								$nbMonoCompetences=0;
								$nbActiviteMonoCompetences=0;
								$note=0;
								$nbPrestaPdp=0;
								$listeNoir="";
								$listeRouge="";
								$listeOrange="";
								$nbPrestaOTD2=0;
								$ratioOTDInf=0;
								$ratioOTDEgal=0;
								$ratioOTDSup=0;
								$ratioOQDInf=0;
								$ratioOQDEgal=0;
								$ratioOQDSup=0;
								$ratioOTDInf2=0;
								$ratioOTDEgal2=0;
								$ratioOTDSup2=0;
								$ratioOQDInf2=0;
								$ratioOQDEgal2=0;
								$ratioOQDSup2=0;
								$nbPrestaOQD2=0;
								$listeOTDInf="";
								$listeOQDInf="";
								
								$ObjectifProductivite=null;
								$ObjectifOTD=null;
								$ObjectifOQD=null;
								$ObjectifOTDActivite=null;
								$ObjectifOQDActivite=null;
								$ObjectifPDP=null;
								$ObjectifSatisfactionClient=null;
								$ObjectifTauxQualif=null;
								$ObjectifTauxPolyvalence=null;
								
								//Recherche Objectif E/C 
								$req="SELECT Theme, Pourcentage
								FROM moris_objectifglobal
								WHERE CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT(0,MONTH(DateDebut)),MONTH(DateDebut)))<='".$anneeEC."_".$moisEC."' 
								AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT(0,MONTH(DateFin)),MONTH(DateFin)))>='".$anneeEC."_".$moisEC."' OR DateFin<='0001-01-01') ";
								$resultObj=mysqli_query($bdd,$req);
								$nbObj=mysqli_num_rows($resultObj);
								if($nbObj>0){
									while($rowObj=mysqli_fetch_array($resultObj)){
										switch($rowObj['Theme']){
											case "OTD activité":
												$ObjectifOTDActivite=$rowObj['Pourcentage'];
												break;
											case "OTD livrable":
												$ObjectifOTD=$rowObj['Pourcentage'];
												break;
											case "OQD activité":
												$ObjectifOQDActivite=$rowObj['Pourcentage'];
												break;
											case "OQD livrable":
												$ObjectifOQD=$rowObj['Pourcentage'];
												break;
											case "Productivité corrigée":
												$ObjectifProductivite=$rowObj['Pourcentage'];
												break;
											case "Satisfaction client":
												$ObjectifSatisfactionClient=$rowObj['Pourcentage'];
												break;
											case "Taux de qualification":
												$ObjectifTauxQualif=$rowObj['Pourcentage'];
												break;
											case "Taux de polyvalence":
												$ObjectifTauxPolyvalence=$rowObj['Pourcentage'];
												break;
											case "Plan de prévention":
												$ObjectifPDP=$rowObj['Pourcentage'];
												break;
										}
									}
								}
							
								$req="SELECT Id
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND DateValidite>'0001-01-01'
								AND DateValidite>='".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +2 month"))."'		
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND Id_Prestation = ".$rowPresta['Id']."
								";
								$resultPdp=mysqli_query($bdd,$req);
								$nbVertPdp=mysqli_num_rows($resultPdp);
								
								$req="SELECT Id,Id_Prestation,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND DateValidite>'0001-01-01'
								AND DateValidite>='".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." 1 month"))."'	
								AND DateValidite<'".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +2 month"))."'		
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND Id_Prestation = ".$rowPresta['Id']."
								";
								$resultPdp=mysqli_query($bdd,$req);
								$nbOrangePdp=mysqli_num_rows($resultPdp);
								if($nbOrangePdp>0){
									$nb=1;
									while($rowPdp=mysqli_fetch_array($resultPdp)){
										if($listeOrange<>""){$listeOrange.=", ";}
										if($nb==6){$listeOrange.="<br>";$nb=0;}
										$listeOrange.=$rowPdp['Prestation'];
										$nb++;
									}
								}
								
								$req="SELECT Id,Id_Prestation,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND DateValidite>'0001-01-01'
								AND DateValidite<'".date('Y-m-d',strtotime(date($anneeEC.'-'.$moisEC.'-1')." +1 month"))."'		
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND Id_Prestation = ".$rowPresta['Id']."
								";
								
								$resultPdp=mysqli_query($bdd,$req);
								$nbRougePdp=mysqli_num_rows($resultPdp);
								if($nbRougePdp>0){
									$nb=1;
									while($rowPdp=mysqli_fetch_array($resultPdp)){
										if($listeRouge<>""){$listeRouge.=", ";}
										if($nb==6){$listeRouge.="<br>";$nb=0;}
										$listeRouge.=$rowPdp['Prestation'];
										$nb++;
									}
								}
								
								$req="SELECT Id,Id_Prestation,
								(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
								CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
								FROM moris_pdp
								WHERE Annee=".$anneeEC." 
								AND Mois=".$moisEC."
								AND (DateValidite<='0001-01-01')	
								AND Id_Prestation IN (SELECT Id FROM new_competences_prestation WHERE PlanPreventionADesactivite=0)
								AND (
									SELECT COUNT(DateDebut) 
									FROM moris_datesuivi 
									WHERE moris_datesuivi.Id_Prestation=moris_pdp.Id_Prestation
									AND moris_datesuivi.Suppr=0 
									AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($anneeEC.'_'.$moisEC)."'
									AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($anneeEC.'_'.$moisEC)."' OR DateFin<='0001-01-01')
								)>0
								AND Id_Prestation = ".$rowPresta['Id']."
								";
								
								$resultPdp=mysqli_query($bdd,$req);
								$nbNoirPdp=mysqli_num_rows($resultPdp);
								if($nbNoirPdp>0){
									$nb=1;
									$leNombre=0;
									while($rowPdp=mysqli_fetch_array($resultPdp)){
										if($listeNoir<>""){$listeNoir.=", ";}
										if($nb==6){$listeNoir.="<br>";$nb=0;}
										$listeNoir.=$rowPdp['Prestation'];
										$nb++;
										$leNombre++;
									}
									$nbNoirPdp=$leNombre;
								}
								
								$nbPrestaPdp=$nbVertPdp+$nbOrangePdp+$nbRougePdp+$nbNoirPdp;
								
								if($nbResultaMoisPrestaEC>0){
									$LigneMoisPrestationEC=mysqli_fetch_array($resultEC);
									
									if($rowPresta['ProductiviteADesactive']==0 && $LigneMoisPrestationEC['PasActivite']==0){
										if($LigneMoisPrestationEC['TempsPasse']>0 && $LigneMoisPrestationEC['TempsAlloue']>0 && $LigneMoisPrestationEC['TempsObjectif']>0){
											$productiviteCorrigee+=round($LigneMoisPrestationEC['TempsAlloue']/$LigneMoisPrestationEC['TempsPasse'],2);
											$productiviteBrut+=round($LigneMoisPrestationEC['TempsObjectif']/$LigneMoisPrestationEC['TempsPasse'],2);
											$nbPrestaProd++;
										}
									}
									
									if($LigneMoisPrestationEC['PasOTD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
										if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0){
											$ConformeOTD2+=$LigneMoisPrestationEC['NbLivrableConformeOTD'];
											$NonConformeOTD2+=$LigneMoisPrestationEC['NbRetourClientOTD'];
											$ToleranceOTD2+=$LigneMoisPrestationEC['NbLivrableToleranceOTD'];
											$nbPrestaOTD++;
										}
										if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0 && $LigneMoisPrestationEC['ObjectifClientOTD']>0){
											$ratio=0;
											if($LigneMoisPrestationEC['NbLivrableConformeOTD']>0){
												$ratio=round(($LigneMoisPrestationEC['NbLivrableConformeOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
											}
											if($ratio>=$LigneMoisPrestationEC['ObjectifClientOTD']){
												$ratioOTDSup++;$ratioOTDSup2++;
											}
											else{
												if($LigneMoisPrestationEC['ToleranceOTDOQD']==0){
													$ratioOTDInf++;$ratioOTDInf2++;
													if($listeOTDInf<>""){$listeOTDInf.="<br>";}
													$listeOTDInf.=substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOTD']."%";
												}
												else{
													if($ratio>=$LigneMoisPrestationEC['ObjectifToleranceOTD']){
														$ratioOTDEgal++;$ratioOTDEgal2++;
													}
													else{
														$ratioOTDInf++;$ratioOTDInf2++;
														if($listeOTDInf<>""){$listeOTDInf.="<br>";}
														$listeOTDInf.=substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOTD']."%";
													}
												}
											}
											$nbPrestaOTD2++;
										}
									}
									
									if($LigneMoisPrestationEC['PasOQD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
										if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0){
											$ConformeOQD2+=$LigneMoisPrestationEC['NbLivrableConformeOQD'];
											$NonConformeOQD2+=$LigneMoisPrestationEC['NbRetourClientOQD'];
											$ToleranceOQD2+=$LigneMoisPrestationEC['NbLivrableToleranceOQD'];
											$nbPrestaOQD++;
										}
										
										if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0 && $LigneMoisPrestationEC['ObjectifClientOQD']>0){
											$ratio=0;
											if($LigneMoisPrestationEC['NbLivrableConformeOQD']>0){
												$ratio=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
											}
											if($ratio>=$LigneMoisPrestationEC['ObjectifClientOQD']){
												$ratioOQDSup++;$ratioOQDSup2++;
											}
											else{
												if($LigneMoisPrestationEC['ToleranceOTDOQD']==0){
													$ratioOQDInf++;$ratioOQDInf2++;
													if($listeOQDInf<>""){$listeOQDInf.="<br>";}
													$listeOQDInf.=substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOQD']."%";
												}
												else{
													if($ratio>=$LigneMoisPrestationEC['ObjectifToleranceOQD']){
														$ratioOQDEgal++;$ratioOQDEgal2++;
													}
													else{
														$ratioOQDInf++;$ratioOQDInf2++;
														if($listeOQDInf<>""){$listeOQDInf.="<br>";}
														$listeOQDInf.=substr($LigneMoisPrestationEC['Prestation'],0,strpos($LigneMoisPrestationEC['Prestation']," "))." : ".$ratio."% < ".$LigneMoisPrestationEC['ObjectifClientOQD']."%";
													}
												}
											}
											$nbPrestaOQD2++;
										}
									}
									if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0){
										$ratioCompetence+= round(($LigneMoisPrestationEC['NbXTableauPolyvalence']/($LigneMoisPrestationEC['NbXTableauPolyvalence']+$LigneMoisPrestationEC['NbLTableauPolyvalence']))*100,2);
										$nbPrestaCompetence++;
									}
									if($LigneMoisPrestationEC['TauxQualif']>0){
										$ratioQualif+=$LigneMoisPrestationEC['TauxQualif'];
										$nbPrestaQualif++;
									}
									
									if($LigneMoisPrestationEC['NbMonoCompetence']>0){
										$nbMonoCompetences+=$LigneMoisPrestationEC['NbMonoCompetence'];
										$nbActiviteMonoCompetences++;
									}
									
									$req="SELECT Id FROM moris_moisprestation_securite 
										WHERE Suppr=0 
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
										AND AvecArret=1	
										AND AccidentTrajet=0 
										UNION
										SELECT Id
										FROM rh_personne_at 
										WHERE rh_personne_at.Suppr=0 
										AND rh_personne_at.ArretDeTravail=1
										AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
										AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."' ";
									$resultSecurite=mysqli_query($bdd,$req);
									$nbAvecArret+=mysqli_num_rows($resultSecurite);
									
									$req="SELECT Id FROM moris_moisprestation_securite 
										WHERE Suppr=0 
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
										AND AvecArret=0
										AND AccidentTrajet=0 
										UNION
										SELECT Id
										FROM rh_personne_at 
										WHERE rh_personne_at.Suppr=0 
										AND rh_personne_at.ArretDeTravail=0
										AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
										AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
									$resultSecurite=mysqli_query($bdd,$req);
									$nbSansArret+=mysqli_num_rows($resultSecurite);
									
									$req="SELECT Id FROM moris_moisprestation_securite 
										WHERE Suppr=0 
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
										AND AccidentTrajet=1 
										UNION
										SELECT Id
										FROM rh_personne_at 
										WHERE rh_personne_at.Suppr=0 
										AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)>0
										AND rh_personne_at.Id_Prestation=".$LigneMoisPrestationEC['Id_Prestation']."
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
										AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
									$resultSecurite=mysqli_query($bdd,$req);
									$nbAccidentTrajet+=mysqli_num_rows($resultSecurite);
									
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='NC'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nbNC+=mysqli_num_rows($resultNC);
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='NC Niv 2'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nbNC2+=mysqli_num_rows($resultNC);
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='NC Niv 3'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nbNC3+=mysqli_num_rows($resultNC);
									
									$req="SELECT Id FROM moris_moisprestation_ncdac
										WHERE Suppr=0 
										AND NC_DAC='RC'
										AND Progression=0
										AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
									$resultNC=mysqli_query($bdd,$req);
									$nbRC+=mysqli_num_rows($resultNC);
									
									if($LigneMoisPrestationEC['TendanceManagement']==0){$nbVert++;}
									elseif($LigneMoisPrestationEC['TendanceManagement']==1){$nbOrange++;}
									elseif($LigneMoisPrestationEC['TendanceManagement']==2){$nbRouge++;}
									
								}
								
								if($nbResultaMoisPrestaEC2>0){
									$LigneMoisPrestationEC2=mysqli_fetch_array($resultEC2);
									$total=0;
									$nbEval=0;
									if($LigneMoisPrestationEC2['EvaluationQualite']>-1){
										$total+=$LigneMoisPrestationEC2['EvaluationQualite'];
										$nbEval++;
									}
									if($LigneMoisPrestationEC2['EvaluationDelais']>-1){
										$total+=$LigneMoisPrestationEC2['EvaluationDelais'];
										$nbEval++;
									}
									if($LigneMoisPrestationEC2['EvaluationCompetencePersonnel']>-1){
										$total+=$LigneMoisPrestationEC2['EvaluationCompetencePersonnel'];
										$nbEval++;
									}
									if($LigneMoisPrestationEC2['EvaluationAutonomie']>-1){
										$total+=$LigneMoisPrestationEC2['EvaluationAutonomie'];
										$nbEval++;
									}
									if($LigneMoisPrestationEC2['EvaluationAnticipation']>-1){
										$total+=$LigneMoisPrestationEC2['EvaluationAnticipation'];
										$nbEval++;
									}
									if($LigneMoisPrestationEC2['EvaluationCommunication']>-1){
										$total+=$LigneMoisPrestationEC2['EvaluationCommunication'];
										$nbEval++;
									}
									
									$note=0;
									if($nbEval>0){
										$note+=round($total/$nbEval,1);
									}
									
								}

								if($nbPrestaProd>0){
									$productiviteBrut=round($productiviteBrut/$nbPrestaProd,2);
									$productiviteCorrigee=round($productiviteCorrigee/$nbPrestaProd,2);
								}
								if($nbPrestaCompetence>0){
									$ratioCompetence=round($ratioCompetence/$nbPrestaCompetence,2);
								}
								if($nbPrestaQualif>0){
									$ratioQualif=round($ratioQualif/$nbPrestaQualif,2);
								}
								if($nbPrestaOTD>0){
									$ConformeOTD=round(($ConformeOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
									$NonConformeOTD=round(($NonConformeOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
									$ToleranceOTD=round(($ToleranceOTD2/($ConformeOTD2+$ToleranceOTD2+$NonConformeOTD2))*100,1);
								}
								if($nbPrestaOQD>0){
									$ConformeOQD=round(($ConformeOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
									$NonConformeOQD=round(($NonConformeOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
									$ToleranceOQD=round(($ToleranceOQD2/($ConformeOQD2+$ToleranceOQD2+$NonConformeOQD2))*100,1);
								}
								
								if($nbPrestaOTD2>0){
									$ratioOTDInf=round(($ratioOTDInf/$nbPrestaOTD2)*100,1);
									$ratioOTDEgal=round(($ratioOTDEgal/$nbPrestaOTD2)*100,1);
									$ratioOTDSup=round(($ratioOTDSup/$nbPrestaOTD2)*100,1);
								}
								if($nbPrestaOQD2>0){
									$ratioOQDInf=round(($ratioOQDInf/$nbPrestaOQD2)*100,1);
									$ratioOQDEgal=round(($ratioOQDEgal/$nbPrestaOQD2)*100,1);
									$ratioOQDSup=round(($ratioOQDSup/$nbPrestaOQD2)*100,1);
								}
								
								$nbVertPdp2=0;
								$nbOrangePdp2=0;
								$nbRougePdp2=0;
								$nbNoirPdp2=0;
								if($nbPrestaPdp>0){
									$nbVertPdp2=round(($nbVertPdp/$nbPrestaPdp)*100,1);
									$nbOrangePdp2=round(($nbOrangePdp/$nbPrestaPdp)*100,1);
									$nbRougePdp2=round(($nbRougePdp/$nbPrestaPdp)*100,1);
									$nbNoirPdp2=round(($nbNoirPdp/$nbPrestaPdp)*100,1);
								}
								
								if($nbPrestaPdp>0){
									$arrayPDP[$ipdp]=array("Mois" => utf8_encode($presta),"NbVert" => valeurSinonNull($nbVertPdp2),"NbOrange" => valeurSinonNull($nbOrangePdp2),"NbRouge" => valeurSinonNull($nbRougePdp2),"NbNoir" => valeurSinonNull($nbNoirPdp2),"Objectif" => valeurSinonNull($ObjectifPDP));
									$arrayPDP2[$ipdp]=array("Mois" => $presta,"NbVert" => valeurSinonNull($nbVertPdp),"NbOrange" => valeurSinonNull($nbOrangePdp),"NbRouge" => valeurSinonNull($nbRougePdp),"NbNoir" => valeurSinonNull($nbNoirPdp),"listeOrange" => $listeOrange,"listeRouge" => $listeRouge,"listeNoir" => $listeNoir);
									$ipdp++;
								}
								
								if($nbPrestaOTD2>0){
									$array2OTD[$iotd2]=array("Mois" => utf8_encode($presta),"ratioInf" => valeurSinonNull($ratioOTDInf),"ratioEgal" => valeurSinonNull($ratioOTDEgal),"ratioSup" => valeurSinonNull($ratioOTDSup),"ValeurratioInf" => valeurSinonNull($ratioOTDInf2),"ValeurratioEgal" => valeurSinonNull($ratioOTDEgal2),"ValeurratioSup" => valeurSinonNull($ratioOTDSup2),"Objectif" => valeurSinonNull($ObjectifOTDActivite));
									$array2OTD2[$iotd2]=array("Mois" => utf8_encode($presta),"ratioInf" => valeurSinonNull($ratioOTDInf2),"ratioEgal" => valeurSinonNull($ratioOTDEgal2),"ratioSup" => valeurSinonNull($ratioOTDSup2),"listeOTDInf" => $listeOTDInf,"Objectif" => valeurSinonNull($ObjectifOTDActivite));
									$iotd2++;
								}
								if($nbPrestaOQD2>0){
									$array2OQD[$ioqd2]=array("Mois" => utf8_encode($presta),"ratioInf" => valeurSinonNull($ratioOQDInf),"ratioEgal" => valeurSinonNull($ratioOQDEgal),"ratioSup" => valeurSinonNull($ratioOQDSup),"Objectif" => valeurSinonNull($ObjectifOQDActivite));
									$array2OQD2[$ioqd2]=array("Mois" => utf8_encode($presta),"ratioInf" => valeurSinonNull($ratioOQDInf2),"ratioEgal" => valeurSinonNull($ratioOQDEgal2),"ratioSup" => valeurSinonNull($ratioOQDSup2),"listeOQDInf" => $listeOQDInf,"Objectif" => valeurSinonNull($ObjectifOQDActivite));
									$ioqd2++;
								}
								
								if($ConformeOTD>0 || $ToleranceOTD>0 || $NonConformeOTD>0 || $ConformeOQD>0 || $ToleranceOQD>0 || $NonConformeOQD>0 
								|| $productiviteBrut>0 || $productiviteCorrigee>0 
								|| $ratioCompetence>0 || $ratioQualif>0 || $nbMonoCompetences>0 || $nbActiviteMonoCompetences>0
								|| $nbAccidentTrajet>0 || $nbAvecArret>0 || $nbSansArret>0 || $nbNC>0 || $nbNC2>0 || $nbNC3>0 || $nbRC>0 || $nbVert>0 || $nbOrange>0 || $nbRouge>0 || $note>0){
									if($LigneMoisPrestationEC['PasOTD']==0){
										$arrayOTD[$iotd]=array("Mois" => utf8_encode($presta),"NbConforme" => valeurSinonNull($ConformeOTD),"NbTolerance" => valeurSinonNull($ToleranceOTD),"NbRetour" => valeurSinonNull($NonConformeOTD),"Objectif" => valeurSinonNull($ObjectifOTD),"ValeurNbConforme" => valeurSinonNull($ConformeOTD2),"ValeurNbTolerance" => valeurSinonNull($ToleranceOTD2),"ValeurNbRetour" => valeurSinonNull($NonConformeOTD2));
										$arrayOTD2[$iotd]=array("Mois" => utf8_encode($presta),"NbConforme" => valeurSinonNull($ConformeOTD2),"NbTolerance" => valeurSinonNull($ToleranceOTD2),"NbRetour" => valeurSinonNull($NonConformeOTD2),"OTD" => valeurSinonNull($ConformeOTD),"Objectif" => valeurSinonNull($ObjectifOTD));
										$iotd++;
									}
									if($LigneMoisPrestationEC['PasOQD']==0){
										$arrayOQD[$ioqd]=array("Mois" => utf8_encode($presta),"NbConforme" => valeurSinonNull($ConformeOQD),"NbTolerance" => valeurSinonNull($ToleranceOQD),"NbRetour" => valeurSinonNull($NonConformeOQD),"Objectif" => valeurSinonNull($ObjectifOQD));
										$arrayOQD2[$ioqd]=array("Mois" => utf8_encode($presta),"NbConforme" => valeurSinonNull($ConformeOQD2),"NbTolerance" => valeurSinonNull($ToleranceOQD2),"NbRetour" => valeurSinonNull($NonConformeOQD2),"OQD" => valeurSinonNull($ConformeOQD),"Objectif" => valeurSinonNull($ObjectifOQD));
										$ioqd++;
									}
									if($rowPresta['ProductiviteADesactive']==0){
										$productivite[$iprod]=array("Mois" => utf8_encode($presta),"ProductiviteBrut" => valeurSinonNull($productiviteBrut),"Objectif" => valeurSinonNull($ObjectifProductivite),"ProductiviteCorrigee" => valeurSinonNull($productiviteCorrigee),"listeBrut" => "","listeCorrigee" => "");
										$iprod++;
									}
									
									if($_SESSION['MORIS_VisionMonoCompetence']==0){
										if($nbVolumeMonoMax<$nbActiviteMonoCompetences){
											$nbVolumeMonoMax=$nbActiviteMonoCompetences+50;
										}
									}
									else{
										if($nbVolumeMonoMax<$nbMonoCompetences){
											$nbVolumeMonoMax=$nbMonoCompetences+50;
										}
									}
									
									$arrayCompetences[$i]=array("Mois" => utf8_encode($presta),"Competences" => valeurSinonNull($ratioCompetence),"TauxQualif" => valeurSinonNull($ratioQualif),"NbMonoCompetence" => valeurSinonNull($nbMonoCompetences),"NbActiviteMonoCompetences" => valeurSinonNull($nbActiviteMonoCompetences),"ObjectifTauxQualif" => valeurSinonNull($ObjectifTauxQualif),"ObjectifTauxPolyvalence" => valeurSinonNull($ObjectifTauxPolyvalence));
									$arraySecurite[$i]=array("Mois" => utf8_encode($presta),"NbTrajet" => valeurSinonNull($nbAccidentTrajet),"NbNonTrajetAvecArret" => valeurSinonNull($nbAvecArret),"NbNonTrajetSansArret" => valeurSinonNull($nbSansArret),"listeAT" => $listeAT,"listeSansAT" => $listeSansAT, "listeTrajet" => $listeTrajet,"listeATv2" => $listeATv2,"listeSansATv2" => $listeSansATv2, "listeTrajetv2" => $listeTrajetv2);
									$arrayNbNC[$i]=array("Mois" => utf8_encode($presta),"NC1" => valeurSinonNull($nbNC),"NC2" => valeurSinonNull($nbNC2),"NC3" => valeurSinonNull($nbNC3),"RC" => valeurSinonNull($nbRC),"listeNC" => $listeNC,"listeNC2" => $listeNC2,"listeNC3" => $listeNC3,"listeRC" => $listeRC,"listeNCv2" => $listeNCv2,"listeNC2v2" => $listeNC2v2,"listeNC3v2" => $listeNC3v2,"listeRCv2" => $listeRCv2);
									$arrayManagement[$i]=array("Mois" => utf8_encode($presta),"NbVert" => valeurSinonNull($nbVert),"NbOrange" => valeurSinonNull($nbOrange),"NbRouge" => valeurSinonNull($nbRouge));
									$arrayNewPRM[$i]=array("Abs" => utf8_encode($presta),"Note" => valeurSinonNull($note),"Objectif" => $ObjectifSatisfactionClient);
									
									$i++;
								}
							}
						}
						$tabPRM="";

						$tabOTD="";

						$tabOQD="";
						
						$tabOTD2="";
						
						$tabOQD2="";
						
						$tabPDP="";
						
						$tabProductivite="";
						
						$tabNC="";
						
						$tabAT="";
					}
				}
				
				//Affichage des objectifs 
				$tabTheme = array("OTD activité","OTD livrable","OQD activité","OQD livrable","Plan de prévention","Productivité corrigée","Satisfaction client","Taux de polyvalence","Taux de qualification");
				$tabThemeAffichage = array();
				foreach($tabTheme as $theme){
					$afficher=0;
					$req="SELECT Id FROM moris_objectifaffichage WHERE Theme='".$theme."' AND Afficher=1 ";
					$resultAff=mysqli_query($bdd,$req);
					$nbResultaAff=mysqli_num_rows($resultAff);
					if($nbResultaAff>0){
						$afficher=1;
					}
					$tabThemeAffichage[$theme] = $afficher;
				}
				?>
					<td width="80%">
					<?php 
					if($_POST && (isset($_POST['btnFiltrer2']) || isset($_POST['btn_actualiserFam']))){
						
						
					?>
						<table width="100%">
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table class="TableCompetences" width="99%" style="height:100%;" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="font-size:15px;">
											<input type="checkbox" id="checkChargeCapa" name="checkChargeCapa" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "CHARGE / CAPACITY";}else{echo "CHARGE / CAPACITÉ";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('Charge')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td width="85%" valign="top">
												<div id="chart_Besoin" style="width:100%;height:400px;"></div>
												<script>
													var chart = am4core.create("chart_Besoin", am4charts.XYChart);

													// Add data
													chart.data = <?php echo json_encode($arrayBesoin); ?>;

													// Create axes
													var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 15;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;

													var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.extraMax = 0.2; 
													valueAxis.title.text = "Staffing (nbr)";

													// Create series
													var series1 = chart.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{categoryX}: {valueY.value}";
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "Interne";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa Interne");}else{echo json_encode("Capa Interne");} ?>;
													series1.stacked = true;
													series1.stroke  = "#66b6dc";
													series1.fill  = "#66b6dc";
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chart.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{categoryX}: {valueY.value}";
													series3.dataFields.categoryX = "Mois";
													series3.dataFields.valueY = "SubContractor";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa External");}else{echo json_encode("Capa Externe");} ?>;
													series3.stacked = true;
													series3.stroke  = "#1ab559";
													series3.fill  = "#1ab559";
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY}";
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;
													
													var series2 = chart.series.push(new am4charts.ColumnSeries());
													series2.columns.template.width = am4core.percent(80);
													series2.tooltipText = "{categoryX}: {valueY.value}";
													series2.dataFields.categoryX = "Mois";
													series2.dataFields.valueY = "InternePrevi";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa Interne Forecast");}else{echo json_encode("Capa Interne Previ.");} ?>;
													series2.stacked = true;
													series2.stroke  = "#8a88d7";
													series2.strokeDasharray = "8,4,2,4";
													series2.strokeWidth=3;
													series2.fill  = "#8ec9e5";
													
													var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
													bullet2.label.text = "{valueY}";
													bullet2.locationY = 0.5;
													bullet2.label.fill = am4core.color("#ffffff");
													bullet2.interactionsEnabled = false;
				
													var series5 = chart.series.push(new am4charts.ColumnSeries());
													series5.columns.template.width = am4core.percent(80);
													series5.tooltipText = "{categoryX}: {valueY.value}";
													series5.dataFields.categoryX = "Mois";
													series5.dataFields.valueY = "ExternePrevi";
													series5.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa External Forecast");}else{echo json_encode("Capa Externe Previ.");} ?>;
													series5.stacked = true;
													series5.stroke  = "#36339c";
													series5.strokeDasharray = "8,4,2,4";
													series5.strokeWidth=2;
													series5.fill  = "#86edaf";
													
													var bullet5 = series5.bullets.push(new am4charts.LabelBullet());
													bullet5.label.text = "{valueY}";
													bullet5.locationY = 0.5;
													bullet5.label.fill = am4core.color("#ffffff");
													bullet5.interactionsEnabled = false;
													
													var series4 = chart.series.push(new am4charts.LineSeries());
													series4.dataFields.valueY = "Prevision";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Total load\n(Int + Ext)");}else{echo json_encode("Charge totale\n(Int + Ext)");} ?>;
													series4.dataFields.categoryX = "Mois";
													series4.tooltipText = "{categoryX}: {valueY.value}";
													series4.strokeWidth = 2;
													series4.stroke  = "#f7e802";
													series4.fill  = "#f7e802";
													
													var bullet = series4.bullets.push(new am4charts.CircleBullet());
													bullet.circle.radius = 3;
													bullet.circle.fill = am4core.color("#f7e802");
													bullet.circle.strokeWidth = 1;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY}";
													//bullet4.locationY = -0.18;
													bullet4.label.dy = -20;
													bullet4.label.fill = am4core.color("#e5d713");
													bullet4.interactionsEnabled = false;
				
													
													// Cursor
													chart.cursor = new am4charts.XYCursor();
													chart.cursor.behavior = "panX";
													chart.cursor.lineX.opacity = 0;
													chart.cursor.lineY.opacity = 0;
													
													// Add legend 
													chart.legend = new am4charts.Legend();
													chart.scrollbarX = new am4core.Scrollbar();
													
													chart.exporting.menu = new am4core.ExportMenu();
													chart.scrollbarX.exportable = false;
													chart.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
												</script>
											</td>
											<td width="15%" valign="top" align="right">
												<?php 
												
													$resultFamille=mysqli_query($bdd,$reqFamille);
													$nbResultaFamille=mysqli_num_rows($resultFamille);
													if($nbResultaFamille>0){
														if($_SESSION['Langue']=="EN"){
															echo "<input type='submit' class='Bouton' name='btn_actualiserFam' id='btn_actualiserFam' value='Refresh' /><br>";
														}
														else{
															echo "<input type='submit' class='Bouton' name='btn_actualiserFam' id='btn_actualiserFam' value='Actualiser' /><br>";
														}
														echo "
															<div id='Div_Famille' style='height:250px;overflow:auto;'>
															<table width='99%' cellpadding='0' cellspacing='0'>";
															if($_SESSION['Langue']=="EN"){
																echo "<tr><td class='Libelle'>&nbsp;Families</td></tr>";
															}
															else{
																echo "<tr><td class='Libelle'>&nbsp;Familles</td></tr>";
															}
														
														$checked="checked";
														if($_POST && !isset($_POST['btnReset2']) && !isset($_POST['btnFiltrer2'])){
															if(!isset($_POST['Famille_0'])){$checked="";}
															while($rowFamille=mysqli_fetch_array($resultFamille)){
																	if(!isset($_POST['Famille_'.stripslashes($rowFamille['Id_Famille'])])){$checked="";}
															}
														}
														?>
														<tr>
															<td class="Libelle">
																<input type="checkbox" name="selectAllFamille" id="selectAllFamille" onclick="SelectionnerTout2('Famille')" <?php echo $checked;?> /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
															</td>
														</tr>
														<?php
														$checked="checked";
														if($_POST && !isset($_POST['btnReset2']) && !isset($_POST['btnFiltrer2'])){
															if(isset($_POST['Famille_0'])){$checked="checked";}
															else{$checked="";}
														}
														if($_SESSION['Langue']=="EN"){
															echo "<tr><td class='Libelle'><input type='checkbox' class='checkFamille' name='Famille_0' id='Famille_0' ".$checked." />&nbsp;Indefinite</td></tr>";
														}
														else{
															echo "<tr><td class='Libelle'><input type='checkbox' class='checkFamille' name='Famille_0' id='Famille_0' ".$checked." />&nbsp;Indéfini</td></tr>";
														}
														$resultFamille=mysqli_query($bdd,$reqFamille);
														while($rowFamille=mysqli_fetch_array($resultFamille)){
															$checked="checked";
															if($_POST && !isset($_POST['btnReset2']) && !isset($_POST['btnFiltrer2'])){
																if(isset($_POST['Famille_'.stripslashes($rowFamille['Id_Famille'])])){$checked="checked";}
																else{$checked="";}
															}
															echo "<tr><td class='Libelle'><input type='checkbox' class='checkFamille' name='Famille_".stripslashes($rowFamille['Id_Famille'])."' id='Famille_".stripslashes($rowFamille['Id_Famille'])."' ".$checked." />&nbsp;".stripslashes($rowFamille['Famille'])."</td></tr>";
														}
														
														echo "</table>
														</div>
														";
														
													}
												?>
											</td>
										</tr>
									<?php 
									
									if($_SESSION['FiltreRECORD_Vision']==1){
									if($annee."_".$mois>"2022_09"){?>
									<tr>
										<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "Detail";}else{echo "Détail de la charge / Capa";} ?>
										<?php 
											echo '<img id="Image_PlusMoins" src="../../Images/Plus.gif" width="15px" style="cursor:pointer;" onclick="javascript:Affiche_Masque();">';
										?>
											<a href="javascript:OuvreFenetreExcel('DetailChargeCapa')">
												<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
											</a>
										</td>
									</tr>
									<?php } ?>
									<tr>
										<td height="95%" colspan="2" valign="top" align="center">
											<div id="ChargeCapa_Info" style="display:none;">
												<?php if($_SESSION['Langue']=="EN"){echo "<img src='../../Images/EnCours.png' width='40px' border='0'> On display";}else{echo "<img src='../../Images/EnCours.png' width='40px' border='0'>En cours d'affichage";} ?>
											</div>
											<div id="Table_ChargeCapa" style="display:none;"></div>
										</td>
									</tr>
									<?php }
									
									?>
									<tr><td height="4"></td></tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="font-size:15px;">
											<input type="checkbox" id="checkProductivite" name="checkProductivite" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "PRODUCTIVITY";}else{echo "PRODUCTIVITE";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('Productivite2')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td valign="center" colspan="2">
												<div id="chart_Productivite" style="width:100%;height:400px;"></div>
												<script>
													// Create chart instance
													var chart2 = am4core.create("chart_Productivite", am4charts.XYChart);

													// Add data
													chart2.data = <?php echo json_encode($productivite); ?>;

													// Create axes
													var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 15;
													categoryAxis.renderer.labels.template.horizontalCenter = "middle";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													categoryAxis.renderer.cellStartLocation = 0.1;
													categoryAxis.renderer.cellEndLocation = 0.9;

													var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Productivity");}else{echo json_encode(utf8_encode("Productivité"));} ?>;
													
													// Create series
													var series = chart2.series.push(new am4charts.ColumnSeries());
													series.dataFields.valueY = "ProductiviteBrut";
													series.dataFields.categoryX = "Mois";
													series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Gross Productivity");}else{echo json_encode(utf8_encode("Productivité Brute"));} ?>;
													series.tooltipText = "[{Mois}: bold]{valueY}[/]";
													
													var columnTemplate = series.columns.template;
													columnTemplate.strokeWidth = 1;
													columnTemplate.strokeOpacity = 1;
													columnTemplate.stroke = series.fill;

													var series = chart2.series.push(new am4charts.ColumnSeries());
													series.dataFields.valueY = "ProductiviteCorrigee";
													series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Corrected Productivity");}else{echo json_encode(utf8_encode("Productivité Corrigée"));} ?>;
													series.dataFields.categoryX = "Mois";
													series.tooltipText = "[{Mois}: bold]{valueY}[/]";
													series.strokeWidth = 1;
													series.stroke  = "#947cca";
													series.fill  = "#947cca";
													
													var columnTemplate = series.columns.template;
													columnTemplate.strokeWidth = 1;
													columnTemplate.strokeOpacity = 1;
													columnTemplate.stroke = series.fill;
													
													<?php 
														if($tabThemeAffichage['Productivité corrigée']==1){
													?>
													var series2 = chart2.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objectif");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
													series2.strokeWidth = 2;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													
													<?php 
														}
													?>
													
													// Add legend
													chart2.legend = new am4charts.Legend();
													chart2.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chart2.cursor = new am4charts.XYCursor();
													chart2.cursor.behavior = "panX";
													chart2.cursor.lineX.opacity = 0;
													chart2.cursor.lineY.opacity = 0;
													
													chart2.exporting.menu = new am4core.ExportMenu();
													chart2.scrollbarX.exportable = false;
													chart2.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
												</script>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr >
											<td align="center" width="80%">
											<?php echo $tabProductivite; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_Productivite"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" colspan="2" style="font-size:15px;">
											<input type="checkbox" id="checkManagement" name="checkManagement" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "MANAGEMENT";}else{echo "MANAGEMENT";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('Management2')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr class="Management" style="display:none;"><td height="4"></td></tr>
										<tr class="Management" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="4" height="4"></td></tr>
										<tr class="Management" style="display:none;">
											<td colspan="4" bgcolor="#d5f2ff" style="color:#000000;">
											<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td width="100%" colspan="4">
												<div id="chart_Management" style="width:100%;height:400px"></div>
												<script>
													// Create chart instance
													var chart3 = am4core.create("chart_Management", am4charts.XYChart);

													// Add data
													chart3.data = <?php echo json_encode($arrayManagement); ?>;

													// Create axes
													var categoryAxis = chart3.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 15;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;

													var valueAxis = chart3.yAxes.push(new am4charts.ValueAxis());
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Activities");}else{echo json_encode(utf8_encode("Activités"));} ?>;
													

													// Create series
													var series1 = chart3.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{categoryX}: {valueY.value}";
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "NbVert";
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;

													var series2 = chart3.series.push(new am4charts.ColumnSeries());
													series2.columns.template.width = am4core.percent(80);
													series2.tooltipText = "{categoryX}: {valueY.value}";
													series2.dataFields.categoryX = "Mois";
													series2.dataFields.valueY = "NbOrange";
													series2.stacked = true;
													series2.stroke  = "#ffd757";
													series2.fill  = "#ffd757";
													
													var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
													bullet2.label.text = "{valueY}";
													bullet2.locationY = 0.5;
													bullet2.label.fill = am4core.color("#ffffff");
													bullet2.interactionsEnabled = false;
													
													var series3 = chart3.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{categoryX}: {valueY.value}";
													series3.dataFields.categoryX = "Mois";
													series3.dataFields.valueY = "NbRouge";
													series3.stacked = true;
													series3.stroke  = "#dd0000";
													series3.fill  = "#dd0000";
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY}";
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;
													
													// Cursor
													chart3.cursor = new am4charts.XYCursor();
													chart3.cursor.behavior = "panX";
													chart3.cursor.lineX.opacity = 0;
													chart3.cursor.lineY.opacity = 0;
													chart3.scrollbarX = new am4core.Scrollbar();
													
													chart3.exporting.menu = new am4core.ExportMenu();
													chart3.scrollbarX.exportable = false;
													chart3.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
												</script>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table width="100%" cellpadding="0" cellspacing="0">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="font-size:15px;" colspan="2">
											<input type="checkbox" id="checkOTD" name="checkOTD" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "On Time Delivery (OTD)";}else{echo "On Time Delivery (OTD)";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('OTD')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td colspan="2" valign="top" align="right">
												<table>
													<tr><td><input type="radio" name="visionOTDLivrable" id="visionOTDLivrable" onclick="AfficherGraphOTDOQD('OTD',1)" value="0" <?php echo $checkedVisionOTDPourcentage; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision in %";}else{echo "Vision en %";} ?></td></tr>
													<tr><td><input type="radio" name="visionOTDLivrable" id="visionOTDLivrable" onclick="AfficherGraphOTDOQD('OTD',2)" value="1" <?php echo $checkedVisionOTDLivrable; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision to deliverable";}else{echo "Vision au livrable";} ?></td></tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="4">
												<div id="chart_OTD" style="width:100%;height:400px;<?php if($checkedVisionOTDPourcentage==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chart4 = am4core.create("chart_OTD", am4charts.XYChart);

													// Add data
													chart4.data = <?php echo json_encode($arrayOTD); ?>;
													chart4.numberFormatter.numberFormat = "#'%'";

													// Create axes
													var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.max= 100;
													valueAxis.strictMinMax= true;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Deliverables");}else{echo json_encode(utf8_encode("Livrables"));} ?>;
										

													// Create series
													var series1 = chart4.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Conform");}else{echo json_encode("% Conforme");} ?>;
													series1.dataFields.valueY = "NbConforme";
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chart4.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Tolerance");}else{echo json_encode("% Tolerance");} ?>;
													series3.dataFields.valueY = "NbTolerance";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chart4.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Improper");}else{echo json_encode("% Non-Conforme");} ?>;
													series4.dataFields.valueY = "NbRetour";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['OTD livrable']==1){
													?>
													
													var series2 = chart4.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;
													
													<?php 
													}
													?>
													
													// Add legend
													chart4.legend = new am4charts.Legend();
													chart4.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chart4.cursor = new am4charts.XYCursor();
													chart4.cursor.behavior = "panX";
													chart4.cursor.lineX.opacity = 0;
													chart4.cursor.lineY.opacity = 0;
													
													chart4.exporting.menu = new am4core.ExportMenu();
													chart4.scrollbarX.exportable = false;
													chart4.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="4">
												<div id="chart_OTDV2" style="width:100%;height:400px;<?php if($checkedVisionOTDLivrable==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chartOTD2 = am4core.create("chart_OTDV2", am4charts.XYChart);
													
													chartOTD2.data = <?php echo json_encode($arrayOTD2); ?>;

													// Create axes
													var categoryAxis = chartOTD2.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													<?php 
														if($tabThemeAffichage['OTD livrable']==1){
													?>
													var  valueAxis2 = chartOTD2.yAxes.push(new am4charts.ValueAxis());
													valueAxis2.calculateTotals = true;
													valueAxis2.min = 0;
													valueAxis2.max = 100;
													valueAxis2.strictMinMax = true;
													valueAxis2.tooltip.disabled = true;
													valueAxis2.renderer.axisFills.template.disabled = true;
													valueAxis2.renderer.ticks.template.disabled = true;
													valueAxis2.renderer.labels.template.fontSize = 0;
													
													<?php 
													}
													?>
													
													var  valueAxis = chartOTD2.yAxes.push(new am4charts.ValueAxis());
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Deliverables");}else{echo json_encode(utf8_encode("Livrables"));} ?>;
													valueAxis.calculateTotals = true;
													valueAxis.min = 0;
													valueAxis.max = 100;
													valueAxis.strictMinMax = true;
													valueAxis.renderer.labels.template.adapter.add("text", function(text) {
													  return text + "%";
													});
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;

													// Create series
													var series1 = chartOTD2.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "NbConforme";
													series1.dataFields.valueYShow = "totalPercent";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Conform");}else{echo json_encode("Conforme");} ?>;
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													series1.yAxis = valueAxis;

													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chartOTD2.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Tolerance");}else{echo json_encode("Tolerance");} ?>;
													series3.dataFields.valueY = "NbTolerance";
													series3.dataFields.valueYShow = "totalPercent";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													series3.yAxis = valueAxis;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chartOTD2.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Improper");}else{echo json_encode("Non-Conforme");} ?>;
													series4.dataFields.valueY = "NbRetour";
													series4.dataFields.valueYShow = "totalPercent";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													series4.yAxis = valueAxis;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['OTD livrable']==1){
													?>
													var series2 = chartOTD2.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "{name}: {valueY} %";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;
													series2.yAxis = valueAxis2;
													<?php 
													}
													?>
													
													// Add legend
													chartOTD2.legend = new am4charts.Legend();
													chartOTD2.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chartOTD2.cursor = new am4charts.XYCursor();
													chartOTD2.cursor.behavior = "panX";
													chartOTD2.cursor.lineX.opacity = 0;
													chartOTD2.cursor.lineY.opacity = 0;
													
													chartOTD2.exporting.menu = new am4core.ExportMenu();
													chartOTD2.scrollbarX.exportable = false;
													chartOTD2.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td align="center" width="80%">
												<?php echo $tabOTD; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_OTD"/>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td colspan="2" valign="top" align="right">
												<table>
													<tr><td><input type="radio" name="visionOTD2Livrable" id="visionOTD2Livrable" onclick="AfficherGraphOTDOQD('OTD2',1)" value="0" <?php echo $checkedVisionOTD2Pourcentage; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision to deliverable";}else{echo "Vision en %";} ?></td></tr>
													<tr><td><input type="radio" name="visionOTD2Livrable" id="visionOTD2Livrable" onclick="AfficherGraphOTDOQD('OTD2',2)" value="1" <?php echo $checkedVisionOTD2Livrable; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision to activity";}else{echo "Vision à l'activité";} ?></td></tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="4">
												<div id="chart_OTD2" style="width:100%;height:400px;<?php if($checkedVisionOTD2Pourcentage==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chartOTD = am4core.create("chart_OTD2", am4charts.XYChart);

													// Add data
													chartOTD.data = <?php echo json_encode($array2OTD); ?>;
													chartOTD.numberFormatter.numberFormat = "#'%'";

													// Create axes
													var categoryAxis = chartOTD.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													var valueAxis = chartOTD.yAxes.push(new am4charts.ValueAxis());
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.max= 100;
													valueAxis.strictMinMax= true;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Activities");}else{echo json_encode(utf8_encode("Activités"));} ?>;
										

													// Create series
													var series1 = chartOTD.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Conform");}else{echo json_encode("% Conforme");} ?>;
													series1.dataFields.valueY = "ratioSup";
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chartOTD.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Tolerance");}else{echo json_encode("% Tolerance");} ?>;
													series3.dataFields.valueY = "ratioEgal";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chartOTD.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Improper");}else{echo json_encode("% Non-conforme");} ?>;
													series4.dataFields.valueY = "ratioInf";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['OTD activité']==1){
													?>
													var series2 = chartOTD.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;
													<?php 
														}
													?>
													
													// Add legend 
													chartOTD.legend = new am4charts.Legend();
													chartOTD.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chartOTD.cursor = new am4charts.XYCursor();
													chartOTD.cursor.behavior = "panX";
													chartOTD.cursor.lineX.opacity = 0;
													chartOTD.cursor.lineY.opacity = 0;
													
													chartOTD.exporting.menu = new am4core.ExportMenu();
													chartOTD.scrollbarX.exportable = false;
													chartOTD.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="4">
												<div id="chart_OTD2V2" style="width:100%;height:400px;<?php if($checkedVisionOTD2Livrable==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chartOTD2V2 = am4core.create("chart_OTD2V2", am4charts.XYChart);
													
													chartOTD2V2.data = <?php echo json_encode($array2OTD2); ?>;

													// Create axes
													var categoryAxis = chartOTD2V2.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													<?php 
														if($tabThemeAffichage['OTD activité']==1){
													?>
													var  valueAxis2 = chartOTD2V2.yAxes.push(new am4charts.ValueAxis());
													valueAxis2.calculateTotals = true;
													valueAxis2.min = 0;
													valueAxis2.max = 100;
													valueAxis2.strictMinMax = true;
													valueAxis2.tooltip.disabled = true;
													valueAxis2.renderer.axisFills.template.disabled = true;
													valueAxis2.renderer.ticks.template.disabled = true;
													valueAxis2.renderer.labels.template.fontSize = 0;
													<?php 
														}
													?>
													
													var  valueAxis = chartOTD2V2.yAxes.push(new am4charts.ValueAxis());
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Activities");}else{echo json_encode(utf8_encode("Activités"));} ?>;
													valueAxis.calculateTotals = true;
													valueAxis.min = 0;
													valueAxis.max = 100;
													valueAxis.strictMinMax = true;
													valueAxis.renderer.labels.template.adapter.add("text", function(text) {
													  return text + "%";
													});
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													

													// Create series
													var series1 = chartOTD2V2.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "ratioSup";
													series1.dataFields.valueYShow = "totalPercent";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Conform");}else{echo json_encode("Conforme");} ?>;
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													series1.yAxis = valueAxis;
				
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chartOTD2V2.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Tolerance");}else{echo json_encode("Tolerance");} ?>;
													series3.dataFields.valueY = "ratioEgal";
													series3.dataFields.valueYShow = "totalPercent";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													series3.yAxis = valueAxis;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chartOTD2V2.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Improper");}else{echo json_encode("Non-Conforme");} ?>;
													series4.dataFields.valueY = "ratioInf";
													series4.dataFields.valueYShow = "totalPercent";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													series4.yAxis = valueAxis;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['OTD activité']==1){
													?>
													var series2 = chartOTD2V2.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;
													series2.yAxis = valueAxis2;
													<?php 
														}
													?>
													
													
													// Add legend
													chartOTD2V2.legend = new am4charts.Legend();
													chartOTD2V2.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chartOTD2V2.cursor = new am4charts.XYCursor();
													chartOTD2V2.cursor.behavior = "panX";
													chartOTD2V2.cursor.lineX.opacity = 0;
													chartOTD2V2.cursor.lineY.opacity = 0;
													
													chartOTD2V2.exporting.menu = new am4core.ExportMenu();
													chartOTD2V2.scrollbarX.exportable = false;
													chartOTD2V2.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr colspan="4">
											<td align="center" width="80%">
												<?php echo $tabOTD2; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_OTDInf"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="border-bottom:2px solid #0b6acb;font-size:15px;">
											<input type="checkbox" id="checkOQD" name="checkOQD" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "On Quality Delivery (OQD)";}else{echo "On Quality Delivery (OQD)";} ?>&nbsp;</td>
											<td style="border-bottom:2px solid #0b6acb;cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('OQD')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td colspan="2" valign="top" align="right">
												<table>
													<tr><td><input type="radio" name="visionOQDLivrable" id="visionOQDLivrable" onclick="AfficherGraphOTDOQD('OQD',1)" value="0" <?php echo $checkedVisionOQDPourcentage; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision in %";}else{echo "Vision en %";} ?></td></tr>
													<tr><td><input type="radio" name="visionOQDLivrable" id="visionOQDLivrable" onclick="AfficherGraphOTDOQD('OQD',2)" value="1" <?php echo $checkedVisionOQDLivrable; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision to deliverable";}else{echo "Vision au livrable";} ?></td></tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="2">
												<div id="chart_OQD" style="width:100%;height:400px;<?php if($checkedVisionOQDPourcentage==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chart5 = am4core.create("chart_OQD", am4charts.XYChart);

													// Add data
													chart5.data = <?php echo json_encode($arrayOQD); ?>;
													chart5.numberFormatter.numberFormat = "#'%'";

													// Create axes
													var categoryAxis = chart5.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													var valueAxis = chart5.yAxes.push(new am4charts.ValueAxis());
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.max= 100;
													valueAxis.strictMinMax= true;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Deliverables");}else{echo json_encode(utf8_encode("Livrables"));} ?>;
										

													// Create series
													var series1 = chart5.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Conform");}else{echo json_encode("% Conforme");} ?>;
													series1.dataFields.valueY = "NbConforme";
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.label.fontSize = 10;
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;

													var series3 = chart5.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Tolerance");}else{echo json_encode("% Tolerance");} ?>;
													series3.dataFields.valueY = "NbTolerance";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chart5.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Improper");}else{echo json_encode("% Non-Conforme");} ?>;
													series4.dataFields.valueY = "NbRetour";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;

													<?php 
														if($tabThemeAffichage['OQD livrable']==1){
													?>
													var series2 = chart5.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;	
													<?php 
														}
													?>													
													
													// Add legend
													chart5.legend = new am4charts.Legend();
													chart5.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chart5.cursor = new am4charts.XYCursor();
													chart5.cursor.behavior = "panX";
													chart5.cursor.lineX.opacity = 0;
													chart5.cursor.lineY.opacity = 0;
													
													chart5.exporting.menu = new am4core.ExportMenu();
													chart5.scrollbarX.exportable = false;
													chart5.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="4">
												<div id="chart_OQDV2" style="width:100%;height:400px;<?php if($checkedVisionOQDLivrable==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chartOQDV2 = am4core.create("chart_OQDV2", am4charts.XYChart);
													
													chartOQDV2.data = <?php echo json_encode($arrayOQD2); ?>;

													// Create axes
													var categoryAxis = chartOQDV2.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													<?php 
														if($tabThemeAffichage['OQD livrable']==1){
													?>
													var  valueAxis2 = chartOQDV2.yAxes.push(new am4charts.ValueAxis());
													valueAxis2.calculateTotals = true;
													valueAxis2.min = 0;
													valueAxis2.max = 100;
													valueAxis2.strictMinMax = true;
													valueAxis2.tooltip.disabled = true;
													valueAxis2.renderer.axisFills.template.disabled = true;
													valueAxis2.renderer.ticks.template.disabled = true;
													valueAxis2.renderer.labels.template.fontSize = 0;
													<?php 
														}
													?>

													var  valueAxis = chartOQDV2.yAxes.push(new am4charts.ValueAxis());
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Deliverables");}else{echo json_encode(utf8_encode("Livrables"));} ?>;
													valueAxis.calculateTotals = true;
													valueAxis.min = 0;
													valueAxis.max = 100;
													valueAxis.strictMinMax = true;
													valueAxis.renderer.labels.template.adapter.add("text", function(text) {
													  return text + "%";
													});
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													

													// Create series
													var series1 = chartOQDV2.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "NbConforme";
													series1.dataFields.valueYShow = "totalPercent";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Conform");}else{echo json_encode("Conforme");} ?>;
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													series1.yAxis = valueAxis;

													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chartOQDV2.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Tolerance");}else{echo json_encode("Tolerance");} ?>;
													series3.dataFields.valueY = "NbTolerance";
													series3.dataFields.valueYShow = "totalPercent";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													series3.yAxis = valueAxis;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chartOQDV2.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Improper");}else{echo json_encode("Non-Conforme");} ?>;
													series4.dataFields.valueY = "NbRetour";
													series4.dataFields.valueYShow = "totalPercent";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													series4.yAxis = valueAxis;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;

													<?php 
														if($tabThemeAffichage['OQD livrable']==1){
													?>
													var series2 = chartOQDV2.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;
													series2.yAxis = valueAxis2;
													<?php 
														}
													?>
													
													// Add legend
													chartOQDV2.legend = new am4charts.Legend();
													chartOQDV2.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chartOQDV2.cursor = new am4charts.XYCursor();
													chartOQDV2.cursor.behavior = "panX";
													chartOQDV2.cursor.lineX.opacity = 0;
													chartOQDV2.cursor.lineY.opacity = 0;
													
													chartOQDV2.exporting.menu = new am4core.ExportMenu();
													chartOQDV2.scrollbarX.exportable = false;
													chartOQDV2.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td align="center" width="80%">
												<?php echo $tabOQD; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_OQD"/>
											</td>
											
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td colspan="2" valign="top" align="right">
												<table>
													<tr><td><input type="radio" name="visionOQD2Livrable" id="visionOQD2Livrable" onclick="AfficherGraphOTDOQD('OQD2',1)" value="0" <?php echo $checkedVisionOQD2Pourcentage; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision in %";}else{echo "Vision en %";} ?></td></tr>
													<tr><td><input type="radio" name="visionOQD2Livrable" id="visionOQD2Livrable" onclick="AfficherGraphOTDOQD('OQD2',2)" value="1" <?php echo $checkedVisionOQD2Livrable; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Vision to activity";}else{echo "Vision à l'activité";} ?></td></tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="100%" valign="top" colspan="2">
												<div id="chart_OQD2" style="width:100%;height:400px;<?php if($checkedVisionOQD2Pourcentage==""){echo "display:none;";}?>"></div>
												<script>
													// Create chart instance
													var chartOQD = am4core.create("chart_OQD2", am4charts.XYChart);

													// Add data
													chartOQD.data = <?php echo json_encode($array2OQD); ?>;
													chartOQD.numberFormatter.numberFormat = "#'%'";

													// Create axes
													var categoryAxis = chartOQD.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													var valueAxis = chartOQD.yAxes.push(new am4charts.ValueAxis());
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.max= 100;
													valueAxis.strictMinMax= true;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Activities");}else{echo json_encode(utf8_encode("Activités"));} ?>;
										

													// Create series
													var series1 = chartOQD.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Conform");}else{echo json_encode("% Conforme");} ?>;
													series1.dataFields.valueY = "ratioSup";
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chartOQD.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Tolerance");}else{echo json_encode("% Tolerance");} ?>;
													series3.dataFields.valueY = "ratioEgal";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chartOQD.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Improper");}else{echo json_encode("% Non-conforme");} ?>;
													series4.dataFields.valueY = "ratioInf";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['OQD activité']==1){
													?>
													var series2 = chartOQD.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Mois";
													series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
													series2.strokeWidth = 2;
													series2.minBulletDistance = 10;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													series2.sequencedInterpolation = true;
													<?php 
														}
													?>
													
													// Add legend 
													chartOQD.legend = new am4charts.Legend();
													chartOQD.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chartOQD.cursor = new am4charts.XYCursor();
													chartOQD.cursor.behavior = "panX";
													chartOQD.cursor.lineX.opacity = 0;
													chartOQD.cursor.lineY.opacity = 0;
													
													chartOQD.exporting.menu = new am4core.ExportMenu();
													chartOQD.scrollbarX.exportable = false;
													chartOQD.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
											<tr>
												<td width="100%" valign="top" colspan="4">
													<div id="chart_OQD2V2" style="width:100%;height:400px;<?php if($checkedVisionOQD2Livrable==""){echo "display:none;";}?>"></div>
													<script>
														// Create chart instance
														var chartOQD2V2 = am4core.create("chart_OQD2V2", am4charts.XYChart);
														
														chartOQD2V2.data = <?php echo json_encode($array2OQD2); ?>;

														// Create axes
														var categoryAxis = chartOQD2V2.xAxes.push(new am4charts.CategoryAxis());
														categoryAxis.dataFields.category = "Mois";
														categoryAxis.renderer.grid.template.location = 0;
														categoryAxis.renderer.minGridDistance = 30;
														categoryAxis.renderer.labels.template.horizontalCenter = "right";
														categoryAxis.renderer.labels.template.verticalCenter = "middle";
														categoryAxis.renderer.labels.template.rotation = 270;
														categoryAxis.tooltip.disabled = true;
														categoryAxis.renderer.minHeight = 0;
														
														<?php 
															if($tabThemeAffichage['OQD activité']==1){
														?>
														var  valueAxis2 = chartOQD2V2.yAxes.push(new am4charts.ValueAxis());
														valueAxis2.calculateTotals = true;
														valueAxis2.min = 0;
														valueAxis2.max = 100;
														valueAxis2.strictMinMax = true;
														valueAxis2.tooltip.disabled = true;
														valueAxis2.renderer.axisFills.template.disabled = true;
														valueAxis2.renderer.ticks.template.disabled = true;
														valueAxis2.renderer.labels.template.fontSize = 0;
														<?php 
															}
														?>

														var  valueAxis = chartOQD2V2.yAxes.push(new am4charts.ValueAxis());
														valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Activities");}else{echo json_encode(utf8_encode("Activités"));} ?>;
														valueAxis.calculateTotals = true;
														valueAxis.min = 0;
														valueAxis.max = 100;
														valueAxis.strictMinMax = true;
														valueAxis.renderer.labels.template.adapter.add("text", function(text) {
														  return text + "%";
														});
														valueAxis.tooltip.disabled = true;
														valueAxis.renderer.axisFills.template.disabled = true;
														valueAxis.renderer.ticks.template.disabled = true;
														

														// Create series
														var series1 = chartOQD2V2.series.push(new am4charts.ColumnSeries());
														series1.columns.template.width = am4core.percent(80);
														series1.tooltipText = "{name}: {valueY}";
														series1.dataFields.categoryX = "Mois";
														series1.dataFields.valueY = "ratioSup";
														series1.dataFields.valueYShow = "totalPercent";
														series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Conform");}else{echo json_encode("Conforme");} ?>;
														series1.stacked = true;
														series1.stroke  = "#00d200";
														series1.fill  = "#00d200";
														series1.sequencedInterpolation = true;
														series1.yAxis = valueAxis;

														var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
														bullet1.label.text = "{valueY.value}";
														bullet1.locationY = 0.5;
														bullet1.label.fontSize = 10;
														bullet1.label.fill = am4core.color("#ffffff");
														bullet1.interactionsEnabled = false;
														
														var series3 = chartOQD2V2.series.push(new am4charts.ColumnSeries());
														series3.columns.template.width = am4core.percent(80);
														series3.tooltipText = "{name}: {valueY}";
														series3.dataFields.categoryX = "Mois";
														series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Tolerance");}else{echo json_encode("Tolerance");} ?>;
														series3.dataFields.valueY = "ratioEgal";
														series3.dataFields.valueYShow = "totalPercent";
														series3.stacked = true;
														series3.stroke  = "#ffd757";
														series3.fill  = "#ffd757";
														series3.sequencedInterpolation = true;
														series3.yAxis = valueAxis;
														
														var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
														bullet3.label.text = "{valueY.value}";
														bullet3.label.fontSize = 10;
														bullet3.locationY = 0.5;
														bullet3.label.fill = am4core.color("#ffffff");
														bullet3.interactionsEnabled = false;

														var series4 = chartOQD2V2.series.push(new am4charts.ColumnSeries());
														series4.columns.template.width = am4core.percent(80);
														series4.tooltipText = "{name}: {valueY}";
														series4.dataFields.categoryX = "Mois";
														series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Improper");}else{echo json_encode("Non-Conforme");} ?>;
														series4.dataFields.valueY = "ratioInf";
														series4.dataFields.valueYShow = "totalPercent";
														series4.stacked = true;
														series4.stroke  = "#ff5b5b";
														series4.fill  = "#ff5b5b";
														series4.sequencedInterpolation = true;
														series4.yAxis = valueAxis;
														
														var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
														bullet4.label.text = "{valueY.value}";
														bullet4.label.fontSize = 10;
														bullet4.locationY = 0.5;
														bullet4.label.fill = am4core.color("#ffffff");
														bullet4.interactionsEnabled = false;
														
														<?php 
															if($tabThemeAffichage['OQD activité']==1){
														?>
														var series2 = chartOQD2V2.series.push(new am4charts.LineSeries());
														series2.dataFields.valueY = "Objectif";
														series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
														series2.dataFields.categoryX = "Mois";
														series2.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
														series2.strokeWidth = 2;
														series2.minBulletDistance = 10;
														series2.stroke  = "#d00000";
														series2.fill  = "#d00000";
														series2.sequencedInterpolation = true;
														series2.yAxis = valueAxis2;
														<?php 
															}
														?>
														
														// Add legend
														chartOQD2V2.legend = new am4charts.Legend();
														chartOQD2V2.scrollbarX = new am4core.Scrollbar();
														
														// Cursor
														chartOQD2V2.cursor = new am4charts.XYCursor();
														chartOQD2V2.cursor.behavior = "panX";
														chartOQD2V2.cursor.lineX.opacity = 0;
														chartOQD2V2.cursor.lineY.opacity = 0;
														
														chartOQD2V2.exporting.menu = new am4core.ExportMenu();
														chartOQD2V2.scrollbarX.exportable = false;
														chartOQD2V2.exporting.menu.items =
														[
														  {
															"label": "...",
															"menu": [
															  {
																"label": "Image",
																"menu": [
																  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
																  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
																  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
																]
															  }, {
																"label": "Data",
																"menu": [
																  { "type": "csv", "label": "CSV" },
																  { "type": "xlsx", "label": "XLSX" },
																  { "type": "html", "label": "HTML" }
																]
															  }
															]
														  }
														];
					
													</script>
												</td>
											</tr>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td align="center" width="80%">
												<?php echo $tabOQD2; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_OQDInf"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" height="450px;">
									<table width="100%" cellpadding="0" cellspacing="0">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="font-size:15px;">
											<input type="checkbox" id="checkNC" name="checkNC" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "NC & RC NEWS";}else{echo "NOUVELLES NC & RC";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('NC')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td valign="top" colspan="2">
												<div id="chart_NC" style="width:100%;height:400px"></div>
												<script>
													// Create chart instance
													var chart6 = am4core.create("chart_NC", am4charts.XYChart);

													// Add data
													chart6.data = <?php echo json_encode($arrayNbNC); ?>;

													// Create axes
													var categoryAxis = chart6.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;

													var valueAxis = chart6.yAxes.push(new am4charts.ValueAxis());
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of NC / RC");}else{echo json_encode(utf8_encode("Nombre de NC/RC"));} ?>;
													
													// Create series
													var series1 = chart6.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													
													series1.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "NC1";
													series1.dataFields.valueZ = "listeNC";
													series1.name = <?php echo json_encode($arrayLegendeNC[0]); ?>;
													series1.stacked = true;
													series1.stroke  = "#3d7ad5";
													series1.fill  = "#3d7ad5";
													
													function myFunction(ev) {
													 alert("clicked on ", ev.target);
													}
													series1.columns.template.events.on("hit", myFunction, this);
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													// Create series
													var series2 = chart6.series.push(new am4charts.ColumnSeries());
													series2.columns.template.width = am4core.percent(80);
													series2.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series2.dataFields.categoryX = "Mois";
													series2.dataFields.valueY = "NC2";
													series2.dataFields.valueZ = "listeNC2";
													series2.name = <?php echo json_encode($arrayLegendeNC[1]); ?>;
													series2.stacked = true;
													series2.stroke  = "#29dae9";
													series2.fill  = "#29dae9";
													
													var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series2 = chart6.series.push(new am4charts.ColumnSeries());
													series2.columns.template.width = am4core.percent(80);
													series2.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series2.dataFields.categoryX = "Mois";
													series2.dataFields.valueY = "NC3";
													series2.dataFields.valueZ = "listeNC3";
													series2.name = <?php echo json_encode($arrayLegendeNC[2]); ?>;
													series2.stacked = true;
													series2.stroke  = "#f8ff6d";
													series2.fill  = "#f8ff6d";
													
													var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#000000");
													bullet1.interactionsEnabled = false;
													
													var series2 = chart6.series.push(new am4charts.ColumnSeries());
													series2.columns.template.width = am4core.percent(80);
													series2.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series2.dataFields.categoryX = "Mois";
													series2.dataFields.valueY = "RC";
													series2.dataFields.valueZ = "listeRC";
													series2.name = <?php echo json_encode($arrayLegendeNC[3]); ?>;
													series2.stacked = true;
													series2.stroke  = "#f3b479";
													series2.fill  = "#f3b479";
													
													var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													// Cursor
													chart6.cursor = new am4charts.XYCursor();
													chart6.cursor.behavior = "panX";
													chart6.cursor.lineX.opacity = 0;
													chart6.cursor.lineY.opacity = 0;
													chart6.scrollbarX = new am4core.Scrollbar();
													
													chart6.exporting.menu = new am4core.ExportMenu();
													chart6.scrollbarX.exportable = false;
													chart6.legend = new am4charts.Legend();
													
													chart6.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
													
												</script>
											</td>
										</tr>
										<tr>
											<td align="center" width="80%">
												<?php echo $tabNC; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_NC"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" colspan="2" style="font-size:15px;">
											<input type="checkbox" id="checkPRM" name="checkPRM" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "SATISFACTION CLIENTS";}else{echo "SATISFACTION CLIENTS";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('PRM')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr class="Prm" style="display:none;"><td height="4"></td></tr>
										<tr class="Prm" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
										<tr class="Prm" style="display:none;">
											<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
											<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td width="100%" valign="top">
												<div id="chart_PRM" style="width:100%;height:400px"></div>
												<script>
													// Create chart instance
													var chart7 = am4core.create("chart_PRM", am4charts.XYChart);

													// Add data
													chart7.data = <?php echo json_encode($arrayNewPRM); ?>;

													// Create axes
													var categoryAxis = chart7.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Abs";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "middle";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 0;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.renderer.cellStartLocation = 0.1;
													categoryAxis.renderer.cellEndLocation = 0.9;

													var valueAxis = chart7.yAxes.push(new am4charts.ValueAxis());
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Score");}else{echo json_encode(utf8_encode("Note"));} ?>;

													// Create series
													var series = chart7.series.push(new am4charts.ColumnSeries());
													series.dataFields.valueY = "Note";
													series.dataFields.categoryX = "Abs";
													series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Score");}else{echo json_encode(utf8_encode("Note"));} ?>;
													series.tooltipText = "[{Abs}: bold]{valueY}[/]";
													series.stroke  = "#19ae9f";
													series.fill  = "#00d200";
													
													var columnTemplate = series.columns.template;
													columnTemplate.strokeWidth = 2;
													columnTemplate.strokeOpacity = 1;
													columnTemplate.stroke = series.fill;
													
													var bullet1 = series.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.label.fill = am4core.color("#1c1c1c");
													bullet1.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['Satisfaction client']==1){
													?>
													var series2 = chart7.series.push(new am4charts.LineSeries());
													series2.dataFields.valueY = "Objectif";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objectif");}else{echo json_encode("Objectif");} ?>;
													series2.dataFields.categoryX = "Abs";
													series2.tooltipText = "[{Abs}: bold]{valueY}[/]";
													series2.strokeWidth = 2;
													series2.stroke  = "#d00000";
													series2.fill  = "#d00000";
													<?php 
														}
													?>
													
													// Add legend
													chart7.legend = new am4charts.Legend();
													chart7.scrollbarX = new am4core.Scrollbar();

													// Cursor
													chart7.cursor = new am4charts.XYCursor();
													chart7.cursor.behavior = "panX";
													chart7.cursor.lineX.opacity = 0;
													chart7.cursor.lineY.opacity = 0;
													
													chart7.exporting.menu = new am4core.ExportMenu();
													chart7.scrollbarX.exportable = false;
													chart7.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
												</script>
											</td>
										</tr>
										<tr>
											<td valign="center" align="center">
												<table style="border:1px dotted black" width="50%" align="center">
													<tr>
														<td>&nbsp;1 : <?php if($_SESSION['Langue']=="EN"){echo "Insufficient";}else{echo "Insuffisant";} ?></td>

														<td>&nbsp;2 : <?php if($_SESSION['Langue']=="EN"){echo "Average";}else{echo "Moyen";} ?></td>

														<td>&nbsp;3 : <?php if($_SESSION['Langue']=="EN"){echo "Satisfactory";}else{echo "Satisfaisant";} ?></td>
				
														<td>&nbsp;4 : <?php if($_SESSION['Langue']=="EN"){echo "Very satisfactory";}else{echo "Très satisfaisant";} ?></td>
													</tr>
												</table>
											</tD>
										</tr>
										<tr><td height="4"></td></tr>
										<tr colspan="4">
											<td align="center">
											<?php echo $tabPRM; ?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td align="center">
									<table width="100%" cellpadding="0" cellspacing="0">	
										<tr>
											<td colspan="2" valign="top" height="450px;">

												<table class="TableCompetences" height="100%" width="99%" cellpadding="0" cellspacing="0">
													<tr>
														<td class="Libelle" height="5%" style="font-size:15px;">
														<input type="checkbox" id="checkCompetence" name="checkCompetence" checked>
														<?php if($_SESSION['Langue']=="EN"){echo "SKILLS";}else{echo "COMPETENCES";} ?></td>
														<td style="cursor:pointer;" align="right">
															<a href="javascript:OuvreFenetreExcel('Competence')">
																<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
															</a>
														</td>
													</tr>
													<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
													<tr><td height="4"></td></tr>
													<tr>
														<td colspan="2" valign="top" align="right">
															<table>
																<tr><td><input type="radio" name="formatMonocompetences" id="formatMonocompetences" onclick="AfficherGraphCompetence(1)" value="0" <?php echo $checkedVolumeActivite; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Business volume";}else{echo "Volume activités";} ?></td></tr>
																<tr><td><input type="radio" name="formatMonocompetences" id="formatMonocompetences" onclick="AfficherGraphCompetence(2)" value="1" <?php echo $checkedVolumeMonoCompetences; ?>/><?php if($_SESSION['Langue']=="EN"){echo "Single-skill volume";}else{echo "Volume mono compétences";} ?></td></tr>
															</table>
														</td>
													</tr>
													<tr>
														<td valign="top" colspan="2">
															<div id="chart_Competences" style="width:100%;height:450px;<?php if($checkedVolumeActivite==""){echo "display:none;";}?>"></div>
															<script>
																am4core.useTheme(am4themes_animated);
																var interfaceColors = new am4core.InterfaceColorSet();
																
																// Create chart instance
																var chart8 = am4core.create("chart_Competences", am4charts.XYChart);

																// Add data
																chart8.data = <?php echo json_encode($arrayCompetences); ?>;
																

																// Create axes
																var categoryAxis = chart8.xAxes.push(new am4charts.CategoryAxis());
																categoryAxis.dataFields.category = "Mois";
																categoryAxis.renderer.grid.template.location = 0;
																categoryAxis.renderer.minGridDistance = 30;
																categoryAxis.renderer.labels.template.horizontalCenter = "right";
																categoryAxis.renderer.labels.template.verticalCenter = "middle";
																categoryAxis.renderer.labels.template.rotation = 270;
																categoryAxis.tooltip.disabled = true;
																categoryAxis.renderer.minHeight = 0;
																
																var valueAxis = chart8.yAxes.push(new am4charts.ValueAxis());
																valueAxis.renderer.minWidth = 0;
																valueAxis.extraMin=0.2;
																valueAxis.max=105;
																valueAxis.strictMinMax = true;
																valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Rate (%)");}else{echo json_encode(utf8_encode("Taux (%)"));} ?>;
																
																// uncomment these lines to fill plot area of this axis with some color
																valueAxis.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
																valueAxis.renderer.gridContainer.background.fillOpacity = 0.02;
																
																// Create series
																var series = chart8.series.push(new am4charts.LineSeries());
																series.dataFields.valueY = "Competences";
																series.dataFields.categoryX = "Mois";
																series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Versatility rate (%)");}else{echo json_encode("Taux de polyvalence (%)");} ?>;
																series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
																series.yAxis = valueAxis;
																series.strokeWidth = 2;
																series.minBulletDistance = 10;
																series.stroke  = "#6ab7da";
																series.fill  = "#6ab7da";

																var bullet = series.bullets.push(new am4charts.CircleBullet());
																bullet.circle.radius = 6;
																bullet.circle.fill = am4core.color("#fff");
																bullet.circle.strokeWidth = 3;
																
																var bullet1 = series.bullets.push(new am4charts.LabelBullet());
																bullet1.label.text = "{valueY}";
																//bullet1.locationY = -0.03;
																bullet1.label.dy = -5;
																bullet1.label.fill = am4core.color("#1c1c1c");
																bullet1.interactionsEnabled = false;
																
																// Create series
																var series2 = chart8.series.push(new am4charts.LineSeries());
																series2.dataFields.valueY = "TauxQualif";
																series2.dataFields.categoryX = "Mois";
																series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualification rate");}else{echo json_encode("Taux de qualification");} ?>;
																series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
																series2.stroke  = "#a53bd3";
																series2.fill  = "#a53bd3";
																series2.strokeWidth = 2;
																series2.yAxis = valueAxis;
																series2.minBulletDistance = 10;

																var bullet2 = series2.bullets.push(new am4charts.CircleBullet());
																bullet2.circle.radius = 6;
																bullet2.circle.fill = am4core.color("#fff");
																bullet2.circle.strokeWidth = 3;
																
																var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
																bullet3.label.text = "{valueY}";
																//bullet3.locationY = 0.04;
																bullet3.label.dy = 10;
																bullet3.label.fill = am4core.color("#1c1c1c");
																bullet3.interactionsEnabled = false;
																
																<?php 
																	if($tabThemeAffichage['Taux de qualification']==1){
																?>
																var series5 = chart8.series.push(new am4charts.LineSeries());
																series5.dataFields.valueY = "ObjectifTauxQualif";
																series5.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualified rate target");}else{echo json_encode("Objectif tx qualif");} ?>;
																series5.dataFields.categoryX = "Mois";
																series5.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
																series5.strokeWidth = 2;
																series5.minBulletDistance = 10;
																series5.stroke  = "#cea7eb";
																series5.fill  = "#cea7eb";
																series5.sequencedInterpolation = true;
																series5.yAxis = valueAxis;
																<?php 
																	}
																?>
																
																<?php 
																	if($tabThemeAffichage['Taux de polyvalence']==1){
																?>
																var series6 = chart8.series.push(new am4charts.LineSeries());
																series6.dataFields.valueY = "ObjectifTauxPolyvalence";
																series6.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Versatility rate target");}else{echo json_encode("Objectif tx polyvalence");} ?>;
																series6.dataFields.categoryX = "Mois";
																series6.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
																series6.strokeWidth = 2;
																series6.minBulletDistance = 10;
																series6.stroke  = "#b2ddec";
																series6.fill  = "#b2ddec";
																series6.sequencedInterpolation = true;
																series6.yAxis = valueAxis;
																<?php 
																	}
																?>
																
																var valueAxis2 = chart8.yAxes.push(new am4charts.ValueAxis());
																valueAxis2.renderer.minWidth = 0;
																valueAxis2.min= 0;
																valueAxis2.extraMax=0.2;
																valueAxis2.strictMinMax = true;
																valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Nbr");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
																valueAxis2.marginTop = 30;
																
																// uncomment these lines to fill plot area of this axis with some color
																valueAxis2.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
																valueAxis2.renderer.gridContainer.background.fillOpacity = 0.02;
																
																var series4 = chart8.series.push(new am4charts.ColumnSeries());												
																series4.tooltipText = "{name}: {valueY.value}";
																series4.dataFields.categoryX = "Mois";
																series4.dataFields.valueY = "NbActiviteMonoCompetences";
																series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("No. of activities with a single skills");}else{echo json_encode(utf8_encode("Nb activités avec mono compétences"));} ?>;
																series4.strokeWidth = 1;
																series4.stroke  = "#f3b479";
																series4.fill  = "#f3b479";
																series4.yAxis = valueAxis2;

																var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
																bullet3.label.text = "{valueY}";
																bullet3.locationY = -0.1;
																bullet3.label.fill = am4core.color("#000000");
																bullet3.interactionsEnabled = false;
																bullet3.fontSize = 10;

																chart8.leftAxesContainer.layout = "vertical";

																// Add legend
																chart8.legend = new am4charts.Legend();
																chart8.scrollbarX = new am4core.Scrollbar();

																// Cursor
																chart8.cursor = new am4charts.XYCursor();
																chart8.cursor.behavior = "panX";
																chart8.cursor.lineX.opacity = 0;
																chart8.cursor.lineY.opacity = 0;
																
																chart8.exporting.menu = new am4core.ExportMenu();
																chart8.scrollbarX.exportable = false;
																chart8.exporting.menu.items =
																[
																  {
																	"label": "...",
																	"menu": [
																	  {
																		"label": "Image",
																		"menu": [
																		  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
																		  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
																		  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
																		]
																	  }, {
																		"label": "Data",
																		"menu": [
																		  { "type": "csv", "label": "CSV" },
																		  { "type": "xlsx", "label": "XLSX" },
																		  { "type": "html", "label": "HTML" }
																		]
																	  }
																	]
																  }
																];
															</script>
														</td>
													</tr>
													<tr>
														<td valign="top" colspan="2">
															<div id="chart_Competences2" style="width:100%;height:450px;<?php if($checkedVolumeMonoCompetences==""){echo "display:none;";}?>"></div>
															<script>
																// Create chart instance
																am4core.useTheme(am4themes_animated);
																var interfaceColors = new am4core.InterfaceColorSet();
																
																var chart8 = am4core.create("chart_Competences2", am4charts.XYChart);

																// Add data
																chart8.data = <?php echo json_encode($arrayCompetences); ?>;

																// Create axes
																var categoryAxis = chart8.xAxes.push(new am4charts.CategoryAxis());
																categoryAxis.dataFields.category = "Mois";
																categoryAxis.renderer.grid.template.location = 0;
																categoryAxis.renderer.minGridDistance = 30;
																categoryAxis.renderer.labels.template.horizontalCenter = "right";
																categoryAxis.renderer.labels.template.verticalCenter = "middle";
																categoryAxis.renderer.labels.template.rotation = 270;
																categoryAxis.tooltip.disabled = true;
																categoryAxis.renderer.minHeight = 0;

																var valueAxis = chart8.yAxes.push(new am4charts.ValueAxis());
																valueAxis.renderer.minWidth = 0;
																valueAxis.extraMin=0.3;
																valueAxis.max=105;
																valueAxis.strictMinMax = true;
																valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Rate (%)");}else{echo json_encode(utf8_encode("Taux (%)"));} ?>;
																// uncomment these lines to fill plot area of this axis with some color
																valueAxis.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
																valueAxis.renderer.gridContainer.background.fillOpacity = 0.02;
																
																
																// Create series
																var series = chart8.series.push(new am4charts.LineSeries());
																series.dataFields.valueY = "Competences";
																series.dataFields.categoryX = "Mois";
																series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Versatility rate (%)");}else{echo json_encode("Taux de polyvalence (%)");} ?>;
																series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
																series.yAxis = valueAxis;
																series.strokeWidth = 2;
																series.minBulletDistance = 10;

																var bullet = series.bullets.push(new am4charts.CircleBullet());
																bullet.circle.radius = 6;
																bullet.circle.fill = am4core.color("#fff");
																bullet.circle.strokeWidth = 3;
																
																var bullet1 = series.bullets.push(new am4charts.LabelBullet());
																bullet1.label.text = "{valueY}";
																bullet1.locationY = -0.03;
																bullet1.label.fill = am4core.color("#1c1c1c");
																bullet1.interactionsEnabled = false;
																
																// Create series
																var series2 = chart8.series.push(new am4charts.LineSeries());
																series2.dataFields.valueY = "TauxQualif";
																series2.dataFields.categoryX = "Mois";
																series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualification rate");}else{echo json_encode("Taux de qualification");} ?>;
																series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
																series2.stroke  = "#a53bd3";
																series2.fill  = "#a53bd3";
																series2.strokeWidth = 2;
																series2.yAxis = valueAxis;
																series2.minBulletDistance = 10;

																var bullet2 = series2.bullets.push(new am4charts.CircleBullet());
																bullet2.circle.radius = 6;
																bullet2.circle.fill = am4core.color("#fff");
																bullet2.circle.strokeWidth = 3;
																
																var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
																bullet3.label.text = "{valueY}";
																bullet3.locationY = 0.04;
																bullet3.label.fill = am4core.color("#1c1c1c");
																bullet3.interactionsEnabled = false;
																
																<?php 
																	if($tabThemeAffichage['Taux de qualification']==1){
																?>
																var series5 = chart8.series.push(new am4charts.LineSeries());
																series5.dataFields.valueY = "ObjectifTauxQualif";
																series5.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualified rate target");}else{echo json_encode("Objectif tx qualif");} ?>;
																series5.dataFields.categoryX = "Mois";
																series5.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
																series5.strokeWidth = 2;
																series5.minBulletDistance = 10;
																series5.stroke  = "#cea7eb";
																series5.fill  = "#cea7eb";
																series5.sequencedInterpolation = true;
																series5.yAxis = valueAxis;
																<?php 
																	}
																?>
																<?php 
																	if($tabThemeAffichage['Taux de polyvalence']==1){
																?>
																var series6 = chart8.series.push(new am4charts.LineSeries());
																series6.dataFields.valueY = "ObjectifTauxPolyvalence";
																series6.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Versatility rate target");}else{echo json_encode("Objectif tx polyvalence");} ?>;
																series6.dataFields.categoryX = "Mois";
																series6.tooltipText = "[{categoryX}: bold]{valueY}[/] %";
																series6.strokeWidth = 2;
																series6.minBulletDistance = 10;
																series6.stroke  = "#b2ddec";
																series6.fill  = "#b2ddec";
																series6.sequencedInterpolation = true;
																series6.yAxis = valueAxis;
																<?php 
																	}
																?>
																
																
																var valueAxis2 = chart8.yAxes.push(new am4charts.ValueAxis());
																valueAxis2.renderer.minWidth = 0;
																valueAxis2.min= 0;
																valueAxis2.extraMax=0.2;
																valueAxis2.strictMinMax = true;
																valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Nbr");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
																valueAxis2.marginTop = 30;
																
																// uncomment these lines to fill plot area of this axis with some color
																valueAxis2.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
																valueAxis2.renderer.gridContainer.background.fillOpacity = 0.02;
																
																var series4 = chart8.series.push(new am4charts.ColumnSeries());												
																series4.tooltipText = "{name}: {valueY.value}";
																series4.dataFields.categoryX = "Mois";
	
																series4.dataFields.valueY = "NbMonoCompetence";
																series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Nb single-skilled");}else{echo json_encode(utf8_encode("Nb mono compétences"));} ?>;
	
																series4.strokeWidth = 1;
																series4.stroke  = "#f3b479";
																series4.fill  = "#f3b479";
																series4.yAxis = valueAxis2;

																var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
																bullet3.label.text = "{valueY}";
																bullet3.locationY = -0.1;
																bullet3.label.fill = am4core.color("#000000");
																bullet3.interactionsEnabled = false;
																bullet3.fontSize = 10;
																
																chart8.leftAxesContainer.layout = "vertical";
																
																// Add legend
																chart8.legend = new am4charts.Legend();
																chart8.scrollbarX = new am4core.Scrollbar();

																// Cursor
																chart8.cursor = new am4charts.XYCursor();
																chart8.cursor.behavior = "panX";
																chart8.cursor.lineX.opacity = 0;
																chart8.cursor.lineY.opacity = 0;
																
																chart8.exporting.menu = new am4core.ExportMenu();
																chart8.scrollbarX.exportable = false;
																chart8.exporting.menu.items =
																[
																  {
																	"label": "...",
																	"menu": [
																	  {
																		"label": "Image",
																		"menu": [
																		  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
																		  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
																		  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
																		]
																	  }, {
																		"label": "Data",
																		"menu": [
																		  { "type": "csv", "label": "CSV" },
																		  { "type": "xlsx", "label": "XLSX" },
																		  { "type": "html", "label": "HTML" }
																		]
																	  }
																	]
																  }
																];
															</script>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table class="TableCompetences" height="100%" width="99%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="font-size:15px;">
											<input type="checkbox" id="checkSecurite" name="checkSecurite" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "SECURITY";}else{echo "SECURITE";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('Securite2')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td colspan="2">
												<div id="chart_Securite" style="width:100%;height:400px"></div>
												<script>
													// Create chart instance
													var chart9 = am4core.create("chart_Securite", am4charts.XYChart);

													// Add data
													chart9.data = <?php echo json_encode($arraySecurite); ?>;

													// Create axes
													var categoryAxis = chart9.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;

													var valueAxis = chart9.yAxes.push(new am4charts.ValueAxis());
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of accidents");}else{echo json_encode(utf8_encode("Nombre d'accidents"));} ?>;

													// Create series
													var series1 = chart9.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of commuting accidents");}else{echo json_encode(utf8_encode("Nbr d'accident de trajet"));}?>;
													series1.dataFields.categoryX = "Mois";
													series1.dataFields.valueY = "NbTrajet";
													series1.dataFields.valueZ = "listeTrajet";
													series1.stacked = true;
													series1.stroke  = "#3d7ad5";
													series1.fill  = "#3d7ad5";
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY}";
													bullet1.locationY = 0.5;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;

													var series2 = chart9.series.push(new am4charts.ColumnSeries());
													series2.columns.template.width = am4core.percent(80);
													series2.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of accidents with lost time");}else{echo json_encode(utf8_encode("Nbr d'accident avec arrêt de travail"));}?>;
													series2.dataFields.categoryX = "Mois";
													series2.dataFields.valueY = "NbNonTrajetAvecArret";
													series2.dataFields.valueZ = "listeAT";
													series2.stacked = true;
													series2.stroke  = "#dbb637";
													series2.fill  = "#dbb637";
													
													var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
													bullet2.label.text = "{valueY}";
													bullet2.locationY = 0.5;
													bullet2.label.fill = am4core.color("#ffffff");
													bullet2.interactionsEnabled = false;
													
													var series3 = chart9.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{categoryX}: ({valueY.value}) {valueZ}";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of accidents without work stoppage");}else{echo json_encode(utf8_encode("Nbr d'accident sans arrêt de travail"));}?>;
													series3.dataFields.categoryX = "Mois";
													series3.dataFields.valueY = "NbNonTrajetSansArret";
													series3.dataFields.valueZ = "listeSansAT";
													series3.stacked = true;
													series3.stroke  = "#9fb1c5";
													series3.fill  = "#9fb1c5";
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY}";
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;
													
													chart9.legend = new am4charts.Legend();
													chart9.scrollbarX = new am4core.Scrollbar();
													
													
													// Cursor
													chart9.cursor = new am4charts.XYCursor();
													chart9.cursor.behavior = "panX";
													chart9.cursor.lineX.opacity = 0;
													chart9.cursor.lineY.opacity = 0;
													
													chart9.exporting.menu = new am4core.ExportMenu();
													chart9.scrollbarX.exportable = false;
													chart9.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];

												</script>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td align="center" width="80%">
												<?php echo $tabAT; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_AT"/>
											</td>
											
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top" height="450px;">
									<table width="100%" cellpadding="0" cellspacing="0">
									<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="Libelle" height="5%" style="font-size:15px;" colspan="2">
											<input type="checkbox" id="checkPDP" name="checkPDP" checked>
											<?php if($_SESSION['Langue']=="EN"){echo "PREVENTION PLAN";}else{echo "PLAN DE PREVENTION";} ?></td>
											<td style="cursor:pointer;" align="right">
												<a href="javascript:OuvreFenetreExcel('PDP')">
													<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
												</a>
											</td>
										</tr>
										<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td width="100%" valign="top" colspan="4">
												<div id="chart_PDP" style="width:100%;height:400px"></div>
												<script>
													// Create chart instance
													var chart10 = am4core.create("chart_PDP", am4charts.XYChart);

													// Add data
													chart10.data = <?php echo json_encode($arrayPDP); ?>;
													chart10.numberFormatter.numberFormat = "#'%'";

													// Create axes
													var categoryAxis = chart10.xAxes.push(new am4charts.CategoryAxis());
													categoryAxis.dataFields.category = "Mois";
													categoryAxis.renderer.grid.template.location = 0;
													categoryAxis.renderer.minGridDistance = 30;
													categoryAxis.renderer.labels.template.horizontalCenter = "right";
													categoryAxis.renderer.labels.template.verticalCenter = "middle";
													categoryAxis.renderer.labels.template.rotation = 270;
													categoryAxis.tooltip.disabled = true;
													categoryAxis.renderer.minHeight = 0;
													
													var valueAxis = chart10.yAxes.push(new am4charts.ValueAxis());
													valueAxis.tooltip.disabled = true;
													valueAxis.renderer.axisFills.template.disabled = true;
													valueAxis.renderer.ticks.template.disabled = true;
													valueAxis.renderer.minWidth = 0;
													valueAxis.min= 0;
													valueAxis.max= 100;
													valueAxis.strictMinMax= true;
													valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("PDP %");}else{echo json_encode(utf8_encode("PDP %"));} ?>;
										

													// Create series
													var series1 = chart10.series.push(new am4charts.ColumnSeries());
													series1.columns.template.width = am4core.percent(80);
													series1.tooltipText = "{name}: {valueY}";
													series1.dataFields.categoryX = "Mois";
													series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% >1 month");}else{echo json_encode("% >1 mois");} ?>;
													series1.dataFields.valueY = "NbVert";
													series1.stacked = true;
													series1.stroke  = "#00d200";
													series1.fill  = "#00d200";
													series1.sequencedInterpolation = true;
													
													var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
													bullet1.label.text = "{valueY.value}";
													bullet1.locationY = 0.5;
													bullet1.label.fontSize = 10;
													bullet1.label.fill = am4core.color("#ffffff");
													bullet1.interactionsEnabled = false;
													
													var series3 = chart10.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(80);
													series3.tooltipText = "{name}: {valueY}";
													series3.dataFields.categoryX = "Mois";
													series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% <1 month ");}else{echo json_encode("% <1 mois");} ?>;
													series3.dataFields.valueY = "NbOrange";
													series3.stacked = true;
													series3.stroke  = "#ffd757";
													series3.fill  = "#ffd757";
													series3.sequencedInterpolation = true;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY.value}";
													bullet3.label.fontSize = 10;
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;

													var series4 = chart10.series.push(new am4charts.ColumnSeries());
													series4.columns.template.width = am4core.percent(80);
													series4.tooltipText = "{name}: {valueY}";
													series4.dataFields.categoryX = "Mois";
													series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% outdated");}else{echo json_encode(utf8_encode("% dépassé"));} ?>;
													series4.dataFields.valueY = "NbRouge";
													series4.stacked = true;
													series4.stroke  = "#ff5b5b";
													series4.fill  = "#ff5b5b";
													series4.sequencedInterpolation = true;
													
													var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
													bullet4.label.text = "{valueY.value}";
													bullet4.label.fontSize = 10;
													bullet4.locationY = 0.5;
													bullet4.label.fill = am4core.color("#ffffff");
													bullet4.interactionsEnabled = false;
													
													var series5 = chart10.series.push(new am4charts.ColumnSeries());
													series5.columns.template.width = am4core.percent(80);
													series5.tooltipText = "{name}: {valueY}";
													series5.dataFields.categoryX = "Mois";
													series5.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% not specified");}else{echo json_encode(utf8_encode("% non renseigné"));} ?>;
													series5.dataFields.valueY = "NbNoir";
													series5.stacked = true;
													series5.stroke  = "#4f4f4f";
													series5.fill  = "#4f4f4f";
													series5.sequencedInterpolation = true;
													series5.yAxis = valueAxis;
													
													var bullet5 = series5.bullets.push(new am4charts.LabelBullet());
													bullet5.label.text = "{valueY.value}";
													bullet5.label.fontSize = 10;
													bullet5.locationY = 0.5;
													bullet5.label.fill = am4core.color("#ffffff");
													bullet5.interactionsEnabled = false;
													
													<?php 
														if($tabThemeAffichage['Plan de prévention']==1){
													?>
													var series6 = chart10.series.push(new am4charts.LineSeries());
													series6.dataFields.valueY = "Objectif";
													series6.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objectif");}else{echo json_encode("Objectif");} ?>;
													series6.dataFields.categoryX = "Mois";
													series6.tooltipText = "{name}: {valueY}";
													series6.strokeWidth = 2;
													series6.stroke  = "#d00000";
													series6.fill  = "#d00000";
													<?php 
														}
													?>
													
													// Add legend
													chart10.legend = new am4charts.Legend();
													chart10.scrollbarX = new am4core.Scrollbar();
													
													// Cursor
													chart10.cursor = new am4charts.XYCursor();
													chart10.cursor.behavior = "panX";
													chart10.cursor.lineX.opacity = 0;
													chart10.cursor.lineY.opacity = 0;
													
													chart10.exporting.menu = new am4core.ExportMenu();
													chart10.scrollbarX.exportable = false;
													chart10.exporting.menu.items =
													[
													  {
														"label": "...",
														"menu": [
														  {
															"label": "Image",
															"menu": [
															  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
															  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
															]
														  }, {
															"label": "Data",
															"menu": [
															  { "type": "csv", "label": "CSV" },
															  { "type": "xlsx", "label": "XLSX" },
															  { "type": "html", "label": "HTML" }
															]
														  }
														]
													  }
													];
				
												</script>
											</td>
										</tr>
										<tr><td height="4"></td></tr>
										<tr>
											<td align="center" width="80%">
												<?php echo $tabPDP; ?>
											</td>
											<td align="left" width="20%">
												<div id="div_PDP"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					<?php 
					}
					?>
					</td>
				</tr>
				<tr><td height="80"></td></tr>
			</table>
		</td>
	</tr>
</table>
<?php
	echo "<script>
		var xhr2 = $.ajax({
			url : 'Ajax_TableauChargeCapa.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('Table_ChargeCapa').innerHTML=data;
				document.getElementById('ChargeCapa_Info').innerHTML='';
				}
		});
	</script>
	";
?>