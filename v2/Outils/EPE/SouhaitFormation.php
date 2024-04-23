<?php
if($_POST){
	$Id_Plateforme="";
	if(isset($_POST['Id_Plateforme'])){
		if (is_array($_POST['Id_Plateforme'])) {
			foreach($_POST['Id_Plateforme'] as $value){
				if($Id_Plateforme<>''){$Id_Plateforme.=",";}
			  $Id_Plateforme.=$value;
			}
		} else {
			$value = $_POST['Id_Plateforme'];
			$Id_Plateforme = $value;
		}
	}
	
	if($_POST){$annee=$_POST['annee'];}
	if($annee==""){$annee=date("Y");}
	
	$_SESSION['FiltreEPEIndicateurs_Plateforme']=$Id_Plateforme;
	$_SESSION['FiltreEPEIndicateurs_Annee']=$annee;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="80%">
		<?php if($_POST){ 
				if($_POST['annee']<>""){
		?>
		<table class="GeneralInfo" width="50%">
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_BesoinFormation();">
					Besoins en formations
				</a>&nbsp;
				</td>
			</tr>
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_EPEEPP();">
					Bilan global par salarié
				</a>&nbsp;
				</td>
			</tr>
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_EvaluationFroidFormation();">
					Evaluation à froid des formations
				</a>&nbsp;
				</td>
			</tr>
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_TE();">
					Souhaits évolution
				</a>&nbsp;
				</td>
			</tr>
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_SouhaitFormation();">
					Souhaits en formations
				</a>&nbsp;
				</td>
			</tr>
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_M();">
					Souhaits mobilité
				</a>&nbsp;
				</td>
			</tr>
			<tr>
				<td width="3%">
				&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_RPS();">
					RPS
				</a>&nbsp;
				</td>
			</tr>
		</table>
		<?php	
			}
		}
		?>
	</td>
	<td align="right" valign="top" width="20%">
		<table class="GeneralInfo" style="border-spacing:0; width:100%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreEPEIndicateurs_Annee']; ?>" size="5"/></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						
					}
					else{
						$requetePlateforme.="
						AND
						( Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
							)
						)
							";
					}
					$requetePlateforme.="ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					
					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$checked="";
						if($_POST){
							$checkboxes = isset($_POST['Id_Plateforme']) ? $_POST['Id_Plateforme'] : array();
							foreach($checkboxes as $value) {
								if($LigPlateforme['Id']==$value){$checked="checked";}
							}
						}
						else{
							$checked="checked";	
						}
						echo "<tr><td>";
						echo "<input type='checkbox' class='checkPlateforme' name='Id_Plateforme[]' Id='Id_Plateforme[]' value='".$LigPlateforme['Id']."' ".$checked." >".$LigPlateforme['Libelle'];
						echo "</td></tr>";
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
</tr>
<tr><td height="4"></td>
</table>	