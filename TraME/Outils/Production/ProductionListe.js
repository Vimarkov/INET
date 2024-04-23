function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CriterePROD.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=250");w.focus();}
function Suppr_Critere(critere,valeur){
	var w=window.open("Ajout_CriterePROD.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");w.focus();}
function OuvreFenetreLecture(Id){
	var w=window.open("Ajout_Production.php?Mode=M&Mode2=L&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=700");w.focus();}
function OuvreFenetreAjout(){
	var w=window.open("Ajout_Production.php?Mode=A&Mode2=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=800");w.focus();}
function OuvreFenetreAjoutR(){
	var w=window.open("Ajout_ProductionRecurrent.php?Mode=A&Mode2=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=800");w.focus();}
function OuvreFenetreModif(Id){
	var w=window.open("Ajout_Production.php?Mode=M&Mode2=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=700");w.focus();}
function OuvreFenetreSuppr(Id){
	if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
	var w=window.open("Ajout_Production.php?Mode=S&Mode2=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
	w.focus();}
}
function OuvreFenetreDupliquer(Id){
	var w=window.open("Ajout_Production.php?Mode=D&Mode2=D&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=700");w.focus();}
function OuvreFenetreC(Id,langue){
	if(langue=="EN"){texte='Are you sure you want to ask for control of this work at the manufacturing engineer ?';}
	else{texte='Etes-vous sûr de vouloir demander le contrôle de ce travail par le préparateur ?';}
	if(window.confirm(texte)){
		var w=window.open("ControleTravail.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");w.focus();}
}
function Excel(){
	var w=window.open("Extract_Production.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");w.focus();}
function OuvreFenetreAC(Id){
	var w=window.open("Ajout_AC.php?Id="+Id,"PageAC","status=no,menubar=no,scrollbars=yes,width=1000,height=700");w.focus();}
function OuvreFenetreControle(Id){
	var w=window.open("Ajout_Controle.php?Mode=A&Id="+Id,"PageAC","status=no,menubar=no,scrollbars=yes,width=1300,height=700");w.focus();}
function OuvreFenetreAfficheControle(Id){
	var w=window.open("Ajout_Controle.php?Mode=A&Id="+Id,"PageAC","status=no,menubar=no,scrollbars=yes,width=1300,height=700");w.focus();}
function OuvreFenetreReControle(Id){
	var w=window.open("Ajout_Controle.php?Mode=M&Id="+Id,"PageAC","status=no,menubar=no,scrollbars=yes,width=1300,height=700");w.focus();}	
function messageAC(message){
	var myRegEx=new RegExp(";","gm");
	var newref=message.replace(myRegEx,"\n");
	alert(newref);
}	
function OuvreFenetreAffichage(){
	var w=window.open("Modif_AffichagePROD.php","PageAffichage","status=no,menubar=no,scrollbars=yes,width=400,height=600");w.focus();}
	function OuvreFenetreAnomalie(Id){
	var w=window.open("Ajout_Anomalie2.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=700");w.focus();}