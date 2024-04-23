<?php
require("../../Menu.php");
?>

<form class="test" method="POST" action="ReglesInformatique.php">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#64a8f2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "R�gles d'utilisations des moyens informatique AAA";}else{echo "Rules for using AAA IT resources";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td align="center">
			<table style="width:55%; border-spacing:0; align:center;" class="GeneralInfo">
				<?php if($_SESSION['Langue']=="FR"){?>
				<tr>
					<td class="Libelle2" align="center" style="color:#1058c6;font-size:18px;">Les 05 commandements � m�moriser* par chaque utilisateur des moyens informatiques AAA</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">1.	De support informatique tu b�n�ficieras</span></b> : <span style="color:#1058c6;">informatique@aaa-aero.com</span> <br>
						Chaque demande est r�pertori�e et trait�e par le service informatique AAA selon leur priorit�. La prise en main � distance du mat�riel par le service informatique est possible pour intervenir rapidement et efficacement.
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">2.	Pour des activit�s AAA exclusivement, le mat�riel informatique AAA tu utiliseras</span></b> : <br>
						Les utilisateurs s�engagent � utiliser les moyens informatiques mis � leur disposition pour leurs activit�s professionnelles uniquement. Ils doivent prendre des mesures appropri�es pour prot�ger efficacement les renseignements classifi�s et prot�g�s en leur possession. En cas de doute se rapprocher de sa hi�rarchie ou du service informatique
						</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">3.	A l�int�grit� de l�information et du mat�riel tu veilleras.</span></b> <br>
						Toute information, de quelque nature qu�elle soit, install�e dans les ressources informatiques de AAA est la propri�t� unique de AAA. Il est interdit de porter atteinte � l�int�grit� des donn�es des autres utilisateurs des ressources informatiques de AAA.
						</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">4.	La s�curit� et la confidentialit� des donn�es tu respecteras. </span></b> <br>
						La Direction de l�Informatique d�limite les espaces informatiques � acc�s restreint et installe les syst�mes de s�curit� ainsi que le mat�riel n�cessaire selon une �valuation des menaces et des risques. Elle s�assure �galement de mettre en place les mesures n�cessaires pour prot�ger les �quipements et l�information qu�ils contiennent.
						</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">5.	La validation du service informatique pr�alable � tout acquisition/Evolution/Installation de mat�riel ou logiciel tu demanderas :</span></b> <br>
						Toute acquisition de produit mat�riel ou logiciel est approuv�e par la Direction de l�Informatique afin qu�elle s�assure que les nouveaux produits commerciaux sont en ligne avec les orientations strat�giques de AAA, que le d�veloppement de ces produits tiennent compte des exigences de s�curit�, que les pr�requis sont compatibles avec le syst�me d �information AAA en place.
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td class="Libelle">
						<i>*Tout manquement aux r�gles d�utilisation des moyens informatiques AAA peut mener, sans pr�avis, � la suspension des privil�ges d�acc�s aux technologies de l�information de AAA. <br>Le service informatique est � votre service pour vous les pr�ciser : <br><span style="color:#1058c6;">informatique@aaa-aero.com</span></i>
					</td>
				</tr>
				<?php }
				else{
				?>
				<tr>
					<td class="Libelle2" align="center" style="color:#1058c6;font-size:18px;">The 05 commands to memorize * by each user of AAA IT resources</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">1.	From IT support you will benefit </span></b> : <span style="color:#1058c6;">informatique@aaa-aero.com</span> <br> 
						Each request is listed and processed by the AAA IT department according to their priority. The remote control of the equipment by the IT department is possible to intervene quickly and efficiently.
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">2.	For AAA activities exclusively, the AAA computer equipment you will use</span></b> : <br>
						Users undertake to use the IT resources made available to them for their professional activities only. They must take appropriate measures to effectively protect the classified and protected information in their possession. In case of doubt, contact your hierarchy or the IT department
						</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">3.	You will ensure the integrity of information and material</span></b> <br>
						All information of whatever nature installed in AAA's computer resources is the sole property of AAA. It is prohibited to infringe the integrity of the data of other users of AAA's computer resources.
						</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">4.	The security and confidentiality of data you will respect. </span></b> <br>
						The IT Department defines the computer spaces with restricted access and installs the security systems and the necessary equipment according to a threat and risk assessment. It also ensures that the necessary measures are in place to protect the equipment and the information it contains.
						</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td>
						<b><span style="font-size:15px;">5.	The validation of the IT department prior to any acquisition / Evolution / Installation of hardware or software you will ask :</span></b> <br>
						Any acquisition of a hardware or software product is approved by the IT Department to ensure that new commercial products are in line with AAA's strategic orientations, that the development of these products take into account security requirements. , that the prerequisites are compatible with the AAA information system in place.
					</td>
				</tr>
				<tr>
					<td height="5px"></td>
				</tr>
				<tr>
					<td class="Libelle">
						<i>* Any breach of the rules for the use of AAA IT resources may lead, without notice, to the suspension of AAA's information technology access privileges.
						<br>The IT department is at your service to specify them for you
						 <br><span style="color:#1058c6;">informatique@aaa-aero.com</span></i>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="100px"></td>
	</tr>
</table>
</form>

<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>