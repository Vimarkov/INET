<?php
require("../Menu.php");
if(isset($_POST['submitValider']))
{
	$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
	$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	$destinataire = "extranet@aaa-aero.com";
	$Signature = "";
	
	$reqPersonne = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =".$_SESSION['Id_Personne']."";
	$resultPersonne=mysqli_query($bdd,$reqPersonne);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	if ($nbPersonne>0){
		$rowPersonne = mysqli_fetch_array($resultPersonne);
		$Signature = $rowPersonne['Nom']." ".$rowPersonne['Prenom'] ;
	}
	$object =$_POST['Titre'];
	
	$message='<html>';
	$message.='<head>';
	$message.='<title></title>';
	$message.='</head><body>';
	$message.= stripslashes($_POST['Contenu']).' ';
	$message.= '<br>';
	$message.= '<br>'.$Signature.' ';
	$message.= '<br>'.$_POST['Email'].' ';
	$message.= '</body></html>';
	
	if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com'))
	{
		echo"<script language=\"javascript\">alert('Le mail a bien été envoyé')</script>";
	}
	else
	{
		echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";
	}	
}
?>

<form class="test" method="POST" action="BoiteIdees.php">
	<table style="width:100%; border-spacing:0; align:center;">
		<tr style="width:10%;">
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing:0;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">
							<?php
								if($_SESSION['Langue']=="FR"){echo "Boîte à idées # Envoyer un message";}
								else{echo "Ideas box # Send a message";}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				<table class="GeneralIdees" style="border-spacing:0; align:center;">
					<tr>
						<td>
							<table class="TitreInfo" style="border-spacing:0; align:center;">
								<tr>
									<td class="TitreInfo" width="4"> &nbsp;
										<?php
											if($_SESSION['Langue']=="FR"){echo "Des idées ? Des questions ? Envoyez-nous un message ! ";}
											else{echo "Ideas ? Questions ? Send us an e-mail";}
										?>
									</td>
								</tr>
							</table>
							<table style="align:center;" class="ContenuInfo">
								<tr>
									<td>
										<table>
    										<tr><td height="4"></td></tr>
    										<tr>
    											<td align="center"> 
    												<label style="font:14px Calibri;">
    													<?php
    														if($_SESSION['Langue']=="FR"){echo "Objet";}
    														else{echo "Object";}
    													?>
    													&nbsp;
    												</label>
    											</td>
    											<td>
    												<input size="100" type="text" style="text-align:left;" name="Titre">
    											</td>
    										</tr>
    										<tr>
    											<td colspan="2">
    												<textarea class="Contenu" name="Contenu" rows="0" cols="0"></textarea>
    											</td>
    										</tr>
    										<tr>
    											<td align="center"> 
    												<label style="font:14px Calibri;">
    													<?php
    														if($_SESSION['Langue']=="FR"){echo "Votre adresse mail";}
    														else{echo "Your email adress";}
    													?>
    													&nbsp;
    												</label>
    											</td>
    											<?php
    												$adresseMail = "";
													if(isset($_SESSION['Id_Personne']))
													{
														$reqPers = "SELECT new_rh_etatcivil.EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =".$_SESSION['Id_Personne']."";
														$resultPers = $bdd->query($reqPers);
														$nbPersonne=$resultPers->num_rows;
														if ($nbPersonne>0)
														{
															$rowPersonne = mysqli_fetch_array($resultPers);
															$adresseMail = $rowPersonne['EmailPro'] ;
														}
													}
    											?>
    											<td>
    												<input size="100" type="text" style="text-align:left;" name="Email" value="<?php echo $adresseMail;?>">
    											</td>
    										</tr>
    										<tr align="center">
    											<td  colspan="2" align="center" style="tex-align:center;">
    												<input class="Bouton" name="submitValider" type="submit" value='<?php if($_SESSION['Langue']=="FR"){echo "Envoyer";}else{echo "Send";}?>'>
    											</td>
    										</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>