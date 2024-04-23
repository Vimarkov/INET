function OuvreFenetreAjout_Critere(){
	var w=window.open("Ajout_CritereExtractTE.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=350");
	w.focus();
}
function Suppr_Critere(critere,valeur){
	var w=window.open("Ajout_CritereExtractTE.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
	w.focus();
}
function OuvreFenetreAjout_CriterePointage(){
	var w=window.open("Ajout_CritereExtractPointage.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=1000,height=350");
	w.focus();
}
function OuvreFenetreAjout_CritereQualite(){
	var w=window.open("Ajout_CritereQualite.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=250");
	w.focus();
}
function Suppr_CriterePointage(critere,valeur){
	var w=window.open("Ajout_CritereExtractPointage.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
	w.focus();
}
function Suppr_CritereQualite(critere,valeur){
	var w=window.open("Ajout_CritereQualite.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
	w.focus();
}
function Excel_Tache(){
	var w=window.open("Extract_Tache.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_UO(){
	var w=window.open("Extract_UO.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_Tache2(){
	var w=window.open("Extract_Tache2.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_UO2(){
	var w=window.open("Extract_UO2.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_Client(){
	var w=window.open("Extract_Client.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_Pointage(){
	var w=window.open("Extract_Pointage.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_OTD(){
	var w=window.open("OTD.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_OQD(){
	var w=window.open("OQD.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_Parametrage(){
	var w=window.open("Extract_Parametrage.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_CatalogueUO(){
	var w=window.open("Extract_CatalogueUO.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function ExcelControle(Id_TE,Id,Version){
	var w=window.open("../Production/Extract_CLRemplie.php?Id_TE="+Id_TE+"&Id="+Id+"&Version="+Version,"PageExcel","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_CC_Global(){
	var w=window.open("Extract_CC_Global.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}
function Excel_CC_CL(){
	var w=window.open("Extract_CC_Checklist.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
	w.focus();
}