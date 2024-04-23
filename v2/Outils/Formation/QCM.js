

function Doc_Web(Id)
{
	var w= window.open("Doc_Web.php?Id_Session_Personne_Document="+Id,"PageDocWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
	w.focus();
}

function OuvreFenetreIdentifiants(Id)
{
	var w= window.open("IdentifiantPersonne.php?Id="+Id,"PageIdentifiant","status=no,menubar=no,scrollbars=yes,width=300,height=150");
	w.focus();
}

function OuvrirFermerAcces(Id,checked)
{
	formulaire.Ouverture.value=Id;
	formulaire.BOuverture.value=checked;
	formulaire.submit();
}

function OuvrirFermerAccesGlobal(Id_Session,Id_Qualification,checked)
{
	formulaire.Id_SessionG.value=Id_Session;
	formulaire.Id_QualificationG.value=Id_Qualification;
	formulaire.BOuvertureG.value=checked;
	formulaire.submit();
}

function ModifierQCM(Id)
{
	var w= window.open("ModifierQCMSession.php?Id="+Id,"PageModifQCMSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
	w.focus();
}

function OuvrirFermerAccesDoc(Id,checked)
{
	formulaire.OuvertureDoc.value=Id;
	formulaire.BOuvertureDoc.value=checked;
	formulaire.submit();
}

function OuvrirFermerAccesGlobalDoc(Id_Session,Id_Document,checked)
{
	formulaire.Id_SessionG.value=Id_Session;
	formulaire.Id_DocumentG.value=Id_Document;
	formulaire.BOuvertureGDoc.value=checked;
	formulaire.submit();
}

function ModifierDoc(Id)
{
	var w= window.open("ModifierDocSession.php?Id="+Id,"PageModifDocSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
	w.focus();
}

function OuvreExcel(Id_QCM_Langue){
	var w=window.open("QCM_Excel_v2.php?Id_QCM_Langue="+document.getElementById(Id_QCM_Langue).value,"PageQCM","status=no,menubar=no,width=50,height=50");
	w.focus();
}

function OuvreDocument(NomDocumentPHP,Id_Session_Personne_Document){
	var w=window.open("Document_Modele/"+NomDocumentPHP+"?Id_Session_Personne_Document="+Id_Session_Personne_Document,"PageDocumentExcel","status=no,menubar=no,width=50,height=50");
	w.focus();
}

function OuvreFenetreProfil(Mode,Id)
{
	var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
	w.focus();
}