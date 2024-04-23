<br>
<div class="conteneurTuto">
	<div class="tableMatiereRECORD">
			
		<ul>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Objectif de RECORD";}else{echo "RECORD goal";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Comment acc�der � RECORD ?";}else{echo "How do I access RECORD ?";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Saisie prestation";}else{echo "Service entry";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "G�rer les admnistrateurs";}else{echo "Manage administrators";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "G�rer les acc�s suppl�mentaires";}else{echo "Manage additional access";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Configurer une prestation";}else{echo "Configure a service";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Configurer les aides au remplissage";}else{echo "Configuring filling aids";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Importer les NC";}else{echo "Import NCs";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Suivi des enregistements";}else{echo "Recording follow-up";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Envoi d'alertes";}else{echo "Send alerts";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Cockpit prestation";}else{echo "Prestation du cockpit";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Graphiques consolid�s";}else{echo "Consolidated charts";}?></li>
			<li><?php if($_SESSION["Langue"]=="FR"){echo "Support � l'outil";}else{echo "Tool support";}?></li>
		</ul>

	</div>
	<div class="contenuTutoRECORD">
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Objectif de RECORD";}else{echo "RECORD goal";}?></div>
			<div class="corpsRECORD">
				<?php 
				if($_SESSION["Langue"]=="FR"){
					echo "RECORD est un outil de pilotage et de reporting op�rationnel mis en place pour supporter le processus de r�alisation R03 chez AAA.<br>
					Cet outil permet de mesurer, consolider et suivre les param�tres de performance inh�rents au processus R03 
					mais �galement ceux jug�s comme pertinents et n�cessaires au pilotage des activit�s.<br><br>
					
					Afin que les donn�es soient consolid�es, exploit�es et communiqu�es � partir du 15 du mois, il est demand� :<br>
					-	Une mise � jour des donn�es avant le 10 du mois pour vision du mois �chu<br>
					-	Un verrouillage des donn�es avant le 13 du mois pour vision du mois �chu (a minima au niveau des fonctions projets)<br>
					Les activit�s pr�sentes dans le suivi RECORD sont des activit�s cr��es et pr�sentes dans l�extranet AAA,
					et utilis�es par d�autres outils internes tels que la gestion des comp�tences, SODA�.<br>
					Aucune activit� ne sera cr�� dans l�outil RECORD<br>";
				}
				else{echo "";}
				?>
			</div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Comment acc�der � RECORD ?";}else{echo "How do I access RECORD ?";}?></div>
			<div id="corpsRECORD">
				<?php 
				if($_SESSION["Langue"]=="FR"){
					echo "
					RECORD est un outil de reporting et pilotage op�rationnel centralis� accessible depuis une connexion internet pour l�ensemble des managers et certaines fonctions supports de AAA Fr et GmbH.<br>
					Les acc�s d�pendent de la fonction de l'utilisateur.<br>
					Des acc�s suppl�mentaires peuvent �tre fournis pour aider � la saisie des informations du mois d'une prestation.<br>
					";
				}
				else{echo "";}
				?>
				<table class="GeneralInfo" class="bordure" width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Page";}else{echo "Page";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Bouton";}else{echo "Button";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Administrateur";}else{echo "Administrator";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Acc�s supp. saisie";}else{echo "Additional entry access";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "N+2";}else{echo "N+2";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "N+3";}else{echo "N+3";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Resp. projet";}else{echo "Project manager";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Contr�le de gestion";}else{echo "Controlling";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Resp. unit�";}else{echo "Unit manager";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Resp. qualit�";}else{echo "Quality manager";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "CQP";}else{echo "CQP";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "CQS";}else{echo "CQS";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Dir. op.";}else{echo "Operations management";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "Charg� mission op.";}else{echo "Project operation manager";}?></td>
						<td class="titreTab"><?php if($_SESSION["Langue"]=="FR"){echo "CODIR";}else{echo "CODIR";}?></td>
					</tr>
					<tr bgcolor="#d0edf5" >
						<td class="bordure" rowspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "COCKPIT PRESTATION";}else{echo "COCKPIT PRESTATION";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
					</tr>
					<tr bgcolor="#d0edf5" >
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "PDF";}else{echo "PDF";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
					</tr>
					<tr>
						<td class="bordure" rowspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "SAISIE PRESTATION";}else{echo "PERFORMANCE INPUT";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
					</tr>
					<tr>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"><br><?php if($_SESSION["Langue"]=="FR"){echo "(si mois non verrouill�)";}else{echo "(if month not locked)";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"><br><?php if($_SESSION["Langue"]=="FR"){echo "(si mois non verrouill�)";}else{echo "(if month not locked)";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"><br><?php if($_SESSION["Langue"]=="FR"){echo "(si mois non verrouill�)";}else{echo "(if month not locked)";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"><br><?php if($_SESSION["Langue"]=="FR"){echo "(si mois non verrouill�)";}else{echo "(if month not locked)";}?></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
					</tr>
					<tr>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "Verrouiller";}else{echo "Lock";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
					</tr>
					<tr>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "D�verrouiller";}else{echo "Unlock";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
					</tr>
					<tr bgcolor="#d0edf5" >
						<td class="bordure" rowspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "GRAPHIQUES CONSOLIDES";}else{echo "CONSOLIDATED CHARTS";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
					</tr>
					<tr bgcolor="#d0edf5" >
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "Filtres<br>(vision en fonction du profil)";}else{echo "Filters<br>(view by profile)";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
					</tr>
					<tr>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "SUIVI DES ENREGISTREMENTS";}else{echo "RECORDING FOLLOW-UP";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
					</tr>
					<tr bgcolor="#d0edf5">
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "ADMINISTRATION";}else{echo "ADMINISTRATION";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
					</tr>
					<tr>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "IMPORT DES NC";}else{echo "NC IMPORT";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"><br><?php if($_SESSION["Langue"]=="FR"){echo "Uniquement P. MARTIN";}else{echo "Only P. MARTIN";}?></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
						<td class="bordure"></td>
					</tr>
					<tr bgcolor="#d0edf5" >
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "TUTORIEL";}else{echo "TUTORIEL";}?></td>
						<td class="bordure"><?php if($_SESSION["Langue"]=="FR"){echo "";}else{echo "";}?></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
						<td class="bordure"><img src="../../Images/Valider.png" border="0"></td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Saisie prestation";}else{echo "Service entry";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "G�rer les admnistrateurs";}else{echo "Manage administrators";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "G�rer les acc�s suppl�mentaires";}else{echo "Manage additional access";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Configurer une prestation";}else{echo "Configure a service";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Configurer les aides au remplissage";}else{echo "Configuring filling aids";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Importer les NC";}else{echo "Import NCs";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Suivi des enregistements";}else{echo "Recording follow-up";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Envoi d'alertes";}else{echo "Send alerts";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Cockpit prestation";}else{echo "Prestation du cockpit";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Graphiques consolid�s";}else{echo "Consolidated charts";}?></div>
			<div id="corpsRECORD"></div>
		</div>
		
		<div class="contenuMenuRECORD">
			<div class="titreRECORD"><?php if($_SESSION["Langue"]=="FR"){echo "Support � l'outil";}else{echo "Tool support";}?></div>
			<div class="corpsRECORD">
				<?php 
				if($_SESSION["Langue"]=="FR"){
					echo "En cas de difficult�, vous pouvez vous orienter vers vos relais Excellence Op�rationnelle qui sont vos interlocuteurs locaux privil�gi�s.";
				}
				else{echo "";}
				?>
				
			</div>
			<div id="image">
				<img src="ImageTuto/SupportOP.PNG" border="0">
			</div>
			<div class="corpsRECORD">
				<?php 
				if($_SESSION["Langue"]=="FR"){
					echo "Vous pouvez �galement �crire � l'adresse help-record.aaa@daher.com";
				}
				else{echo "";}
				?>
				
			</div>
		</div>
	</div>
</div>