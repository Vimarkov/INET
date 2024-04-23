<?php
session_start();
require("../../ConnexioniSansBody.php");
require_once("../../Fonctions.php");
require_once("../Globales_Fonctions.php");

require_once '../../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$ReqFormSessionPersonneDoc="
    SELECT
        form_session_personne.Id_Personne,
        form_session_personne_document.DateHeureRepondeur,
		form_session_personne_document.Id_Document,
		form_session_personne_document.Id_LangueDocument,
        form_session_personne.Id_Session,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
		(SELECT Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS NomStagiaire,
		(SELECT Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS PrenomStagiaire,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Stagiaire
    FROM
        form_session_personne_document
    LEFT JOIN form_session_personne
        ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
    WHERE
        form_session_personne_document.Id=".$_GET['Id_Session_Personne_Document'];
$ResultFormSessionPersonneDoc=mysqli_query($bdd,$ReqFormSessionPersonneDoc);
$RowFormSessionPersonneDoc=mysqli_fetch_array($ResultFormSessionPersonneDoc);

$date=substr($RowFormSessionPersonneDoc['DateHeureRepondeur'],0,10);
$formulaire='<html>
<head>
	<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
	<meta name=ProgId content=Word.Document>
	<meta name=Generator content="Microsoft Word 14">
	<meta name=Originator content="Microsoft Word 14">
	<link rel=File-List href="D-0745 CHARTE AERO-converti_fichiers/filelist.xml">
	<link rel=Edit-Time-Data href="D-0745 CHARTE AERO-converti_fichiers/editdata.mso">
	<!--[if !mso]>
	<style>
	v\:* {behavior:url(#default#VML);}
	o\:* {behavior:url(#default#VML);}
	w\:* {behavior:url(#default#VML);}
	.shape {behavior:url(#default#VML);}
	</style>
	<![endif]-->
	<title>TELECOPIEUR – 01 48 06 32 19</title>
	<link rel=themeData href="D-0745 CHARTE AERO-converti_fichiers/themedata.thmx">
	<link rel=colorSchemeMapping href="D-0745 CHARTE AERO-converti_fichiers/colorschememapping.xml">
	<!--[if gte mso 9]><xml>
	 <w:WordDocument>
	  <w:SpellingState>Clean</w:SpellingState>
	  <w:GrammarState>Clean</w:GrammarState>
	  <w:TrackMoves>false</w:TrackMoves>
	  <w:TrackFormatting/>
	  <w:HyphenationZone>21</w:HyphenationZone>
	  <w:PunctuationKerning/>
	  <w:DrawingGridHorizontalSpacing>5.5 pt</w:DrawingGridHorizontalSpacing>
	  <w:DisplayHorizontalDrawingGridEvery>2</w:DisplayHorizontalDrawingGridEvery>
	  <w:ValidateAgainstSchemas/>
	  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
	  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
	  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
	  <w:DoNotPromoteQF/>
	  <w:LidThemeOther>FR</w:LidThemeOther>
	  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
	  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
	  <w:Compatibility>
	   <w:ULTrailSpace/>
	   <w:BreakWrappedTables/>
	   <w:SnapToGridInCell/>
	   <w:WrapTextWithPunct/>
	   <w:UseAsianBreakRules/>
	   <w:DontGrowAutofit/>
	   <w:SplitPgBreakAndParaMark/>
	   <w:EnableOpenTypeKerning/>
	   <w:DontFlipMirrorIndents/>
	   <w:OverrideTableStyleHps/>
	  </w:Compatibility>
	  <w:DoNotOptimizeForBrowser/>
	  <m:mathPr>
	   <m:mathFont m:val="Cambria Math"/>
	   <m:brkBin m:val="before"/>
	   <m:brkBinSub m:val="&#45;-"/>
	   <m:smallFrac m:val="off"/>
	   <m:dispDef/>
	   <m:lMargin m:val="0"/>
	   <m:rMargin m:val="0"/>
	   <m:defJc m:val="centerGroup"/>
	   <m:wrapIndent m:val="1440"/>
	   <m:intLim m:val="subSup"/>
	   <m:naryLim m:val="undOvr"/>
	  </m:mathPr></w:WordDocument>
	</xml><![endif]--><!--[if gte mso 9]><xml>
	 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
	  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
	  LatentStyleCount="267">
	  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
	  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
	  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
	  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
	  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
	  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
	  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
	  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
	  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
	  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
	  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Table Grid"/>
	  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
	  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 1"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
	  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
	  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
	  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
	  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 2"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 3"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 4"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 5"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
	  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
	  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light List Accent 6"/>
	  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
	  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
	  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
	  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
	  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
	  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
	  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
	  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
	   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
	  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
	  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
	  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
	  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
	  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
	   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
	  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
	  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
	 </w:LatentStyles>
	</xml><![endif]-->
	<style>
	<!--
	 /* Font Definitions */
	 @font-face
		{font-family:\"Trebuchet MS\";
		panose-1:2 11 6 3 2 2 2 2 2 4;
		mso-font-alt:\"Trebuchet MS\";
		mso-font-charset:0;
		mso-generic-font-family:swiss;
		mso-font-pitch:variable;
		mso-font-signature:647 0 0 0 159 0;}
	 /* Style Definitions */
	 p.MsoNormal, li.MsoNormal, div.MsoNormal
		{mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		mso-style-parent:"";
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:none;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Arial",\"sans-serif\";
		mso-fareast-font-family:Arial;
		mso-bidi-language:FR;}
	h1
		{mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		margin-top:0cm;
		margin-right:0cm;
		margin-bottom:0cm;
		margin-left:10.6pt;
		margin-bottom:.0001pt;
		text-indent:-18.0pt;
		mso-pagination:none;
		mso-outline-level:1;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Arial",\"sans-serif\";
		mso-fareast-font-family:Arial;
		mso-font-kerning:0pt;
		mso-bidi-language:FR;}
	p.MsoBodyText, li.MsoBodyText, div.MsoBodyText
		{mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		margin:0cm;
		margin-bottom:.0001pt;
		mso-pagination:none;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Arial",\"sans-serif\";
		mso-fareast-font-family:Arial;
		mso-bidi-language:FR;}
	p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
		{mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		margin-top:2.95pt;
		margin-right:0cm;
		margin-bottom:0cm;
		margin-left:46.6pt;
		margin-bottom:.0001pt;
		text-indent:-18.0pt;
		mso-pagination:none;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Arial",\"sans-serif\";
		mso-fareast-font-family:Arial;
		mso-bidi-language:FR;}
	p.TableParagraph, li.TableParagraph, div.TableParagraph
		{mso-style-name:"Table Paragraph";
		mso-style-priority:1;
		mso-style-unhide:no;
		mso-style-qformat:yes;
		margin-top:0cm;
		margin-right:0cm;
		margin-bottom:0cm;
		margin-left:5.35pt;
		margin-bottom:.0001pt;
		mso-pagination:none;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Arial",\"sans-serif\";
		mso-fareast-font-family:Arial;
		mso-bidi-language:FR;}
	span.SpellE
		{mso-style-name:"";
		mso-spl-e:yes;}
	span.GramE
		{mso-style-name:"";
		mso-gram-e:yes;}
	.MsoChpDefault
		{mso-style-type:export-only;
		mso-default-props:yes;
		font-family:"Calibri",\"sans-serif\";
		mso-ascii-font-family:Calibri;
		mso-ascii-theme-font:minor-latin;
		mso-fareast-font-family:Calibri;
		mso-fareast-theme-font:minor-latin;
		mso-hansi-font-family:Calibri;
		mso-hansi-theme-font:minor-latin;
		mso-bidi-font-family:\"Times New Roman\";
		mso-bidi-theme-font:minor-bidi;
		mso-ansi-language:EN-US;
		mso-fareast-language:EN-US;}
	.MsoPapDefault
		{mso-style-type:export-only;
		mso-pagination:none;
		text-autospace:none;}
	 /* Page Definitions */
	 @page
		{mso-footnote-separator:url("D-0745 CHARTE AERO-converti_fichiers/header.html") fs;
		mso-footnote-continuation-separator:url("D-0745 CHARTE AERO-converti_fichiers/header.html") fcs;
		mso-endnote-separator:url("D-0745 CHARTE AERO-converti_fichiers/header.html") es;
		mso-endnote-continuation-separator:url("D-0745 CHARTE AERO-converti_fichiers/header.html") ecs;}
	@page WordSection1
		{size:595.5pt 842.5pt;
		margin:64.0pt 35.0pt 46.0pt 32.0pt;
		mso-header-margin:14.15pt;
		mso-footer-margin:36.6pt;
		mso-page-numbers:1;
		mso-header:url("D-0745 CHARTE AERO-converti_fichiers/header.html") h1;
		mso-footer:url("D-0745 CHARTE AERO-converti_fichiers/header.html") f1;
		mso-paper-source:0;}
	div.WordSection1
		{page:WordSection1;}
	@page WordSection2
		{size:595.5pt 842.5pt;
		margin:64.0pt 35.0pt 46.0pt 32.0pt;
		mso-header-margin:14.15pt;
		mso-footer-margin:36.6pt;
		mso-header:url("D-0745 CHARTE AERO-converti_fichiers/header.html") h1;
		mso-footer:url("D-0745 CHARTE AERO-converti_fichiers/header.html") f1;
		mso-paper-source:0;}
	div.WordSection2
		{page:WordSection2;}
	 /* List Definitions */
	 @list l0
		{mso-list-id:353192245;
		mso-list-type:hybrid;
		mso-list-template-ids:-1943907090 -1464183618 -2066704492 -2116647574 -1611249524 -2098163138 -844698608 -1578495422 -605945528 1998241232;}
	@list l0:level1
		{mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:46.6pt;
		text-indent:-17.4pt;
		mso-ansi-font-size:11.0pt;
		mso-bidi-font-size:11.0pt;
		font-family:\"Trebuchet MS\",\"sans-serif\";
		mso-fareast-font-family:\"Trebuchet MS\";
		mso-bidi-font-family:\"Trebuchet MS\";
		mso-font-width:81%;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;
		mso-ansi-font-weight:bold;
		mso-bidi-font-weight:bold;}
	@list l0:level2
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:\F0B7;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:99.4pt;
		text-indent:-18.0pt;
		mso-ansi-font-size:11.0pt;
		mso-bidi-font-size:11.0pt;
		font-family:Symbol;
		mso-fareast-font-family:Symbol;
		mso-bidi-font-family:Symbol;
		mso-font-width:100%;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level3
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:146.7pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level4
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:194.4pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level5
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:242.1pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level6
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:289.8pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level7
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:337.5pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level8
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:385.2pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l0:level9
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:432.9pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1
		{mso-list-id:702245264;
		mso-list-type:hybrid;
		mso-list-template-ids:789101894 -1459948918 918696022 -1202444848 -186984820 1586265458 -486087456 -10590270 424698564 -2081262014;}
	@list l1:level1
		{mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:46.6pt;
		text-indent:-17.4pt;
		mso-font-width:81%;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;
		mso-ansi-font-weight:bold;
		mso-bidi-font-weight:bold;}
	@list l1:level2
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:\F0B7;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:99.4pt;
		text-indent:-18.0pt;
		mso-ansi-font-size:11.0pt;
		mso-bidi-font-size:11.0pt;
		font-family:Symbol;
		mso-fareast-font-family:Symbol;
		mso-bidi-font-family:Symbol;
		mso-font-width:100%;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level3
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:146.7pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level4
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:194.4pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level5
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:242.1pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level6
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:289.8pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level7
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:337.5pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level8
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:385.2pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	@list l1:level9
		{mso-level-start-at:0;
		mso-level-number-format:bullet;
		mso-level-text:•;
		mso-level-tab-stop:none;
		mso-level-number-position:left;
		margin-left:432.9pt;
		text-indent:-18.0pt;
		mso-ansi-language:FR;
		mso-fareast-language:FR;
		mso-bidi-language:FR;}
	ol
		{margin-bottom:0cm;}
	ul
		{margin-bottom:0cm;}
	-->
	</style>
	<!--[if gte mso 10]>
	<style>
	 /* Style Definitions */
	 table.MsoNormalTable
		{mso-style-name:"Tableau Normal";
		mso-tstyle-rowband-size:0;
		mso-tstyle-colband-size:0;
		mso-style-noshow:yes;
		mso-style-priority:99;
		mso-style-parent:"";
		mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
		mso-para-margin:0cm;
		mso-para-margin-bottom:.0001pt;
		mso-pagination:none;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Calibri",\"sans-serif\";
		mso-ascii-font-family:Calibri;
		mso-ascii-theme-font:minor-latin;
		mso-hansi-font-family:Calibri;
		mso-hansi-theme-font:minor-latin;
		mso-ansi-language:EN-US;
		mso-fareast-language:EN-US;}
	table.TableNormal
		{mso-style-name:"Table Normal";
		mso-tstyle-rowband-size:0;
		mso-tstyle-colband-size:0;
		mso-style-noshow:yes;
		mso-style-priority:2;
		mso-style-qformat:yes;
		mso-style-parent:"";
		mso-padding-alt:0cm 0cm 0cm 0cm;
		mso-para-margin:0cm;
		mso-para-margin-bottom:.0001pt;
		mso-pagination:none;
		text-autospace:none;
		font-size:11.0pt;
		font-family:"Calibri",\"sans-serif\";
		mso-ascii-font-family:Calibri;
		mso-ascii-theme-font:minor-latin;
		mso-hansi-font-family:Calibri;
		mso-hansi-theme-font:minor-latin;
		mso-ansi-language:EN-US;
		mso-fareast-language:EN-US;}
	</style>
	<![endif]--><!--[if gte mso 9]><xml>
	 <o:shapedefaults v:ext="edit" spidmax="2062"/>
	</xml><![endif]--><!--[if gte mso 9]><xml>
	 <o:shapelayout v:ext="edit">
	  <o:idmap v:ext="edit" data="1"/>
	 </o:shapelayout></xml><![endif]-->
</head>';

$formulaire.='<body lang=FR style="tab-interval:30.0pt">

<div class=WordSection1>

<table cellpadding=0 cellspacing=0 align=left>
 <tr>
  <td></td>
  <td width=113 style="border:1px solid black"><img width=113 height=52 src="D-0745 CHARTE AERO-converti_fichiers/image002.jpg" v:shapes="image1.jpeg"></td>
  <td width=420 height=56 align="center" style="border:1px solid black;background-color:#e7e6e6;color:#002060;font-size:20px;"><img src="D-0745 CHARTE AERO-converti_fichiers/FlecheGauche.png"><b>CHARTE INDIVIDUELLE AERONAUTIQUE</b><img src="D-0745 CHARTE AERO-converti_fichiers/FlecheDroite.png"><br>« GOLDEN RULES »</td>
 </tr>
</table>

</span><![endif]><span style="font-size:10.0pt;mso-bidi-font-size:11.0pt;
font-family:\"Times New Roman\",\"serif\";mso-hansi-font-family:Arial;mso-bidi-font-family:
Arial"><o:p>&nbsp;</o:p></span></p>

<p class=MsoBodyText style="margin-top:.1pt"><span style="font-size:11.5pt;
mso-bidi-font-size:11.0pt;font-family:\"Times New Roman\",\"serif\";mso-hansi-font-family:
Arial;mso-bidi-font-family:Arial"><o:p>&nbsp;</o:p></span></p>

<p class=MsoBodyText style="margin-left:4.95pt"><!--[if mso & !supportInlineShapes & supportFields]><span
style="mso-element:field-begin;mso-field-lock:yes"></span><span
style="mso-spacerun:yes"> </span>SHAPE <span
style="mso-spacerun:yes"> </span>\* MERGEFORMAT <span style="mso-element:field-separator"></span><![endif]--><!--[if gte vml 1]><v:shapetype
 id="_x0000_t202" coordsize="21600,21600" o:spt="202" path="m,l,21600r21600,l21600,xe">
 <v:stroke joinstyle="miter"/>
 <v:path gradientshapeok="t" o:connecttype="rect"/>
</v:shapetype><v:shape id="Text_x0020_Box_x0020_3" o:spid="_x0000_s1028"
 type="#_x0000_t202" style="width:517.3pt;height:17.65pt;visibility:visible;
 mso-wrap-style:square;mso-left-percent:-10001;mso-top-percent:-10001;
 mso-position-horizontal:absolute;mso-position-horizontal-relative:char;
 mso-position-vertical:absolute;mso-position-vertical-relative:line;
 mso-left-percent:-10001;mso-top-percent:-10001;v-text-anchor:top" o:gfxdata="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
" fillcolor="#001f5f" strokeweight=".16936mm">
 <v:textbox inset="0,0,0,0">
  <![if !mso]>
  <table cellpadding=0 cellspacing=0 width="100%">
   <tr>
    <td><![endif]>
    <div>
    <p class=MsoNormal style="margin-top:.3pt;margin-right:0cm;margin-bottom:
    0cm;margin-left:158.9pt;margin-bottom:.0001pt"><b style="mso-bidi-font-weight:
    normal"><span style="font-size:14.0pt;mso-bidi-font-size:11.0pt;color:white">REGLES
    DE L’ART </span></b><b style="mso-bidi-font-weight:normal"><span
    style="font-size:14.0pt;mso-bidi-font-size:11.0pt;font-family:\"Trebuchet MS\",\"sans-serif\";
    color:white">/ GOLDEN RULES</span></b><b style="mso-bidi-font-weight:normal"><span
    style="font-size:14.0pt;mso-bidi-font-size:11.0pt;font-family:\"Trebuchet MS\",\"sans-serif\""><o:p></o:p></span></b></p>
    </div>
    <![if !mso]></td>
   </tr>
  </table>
  <![endif]></v:textbox>
 <w:wrap type="none"/>
 <w:anchorlock/>
</v:shape><![endif]--><![if !vml]><img width=696 height=30 src="D-0745 CHARTE AERO-converti_fichiers/image003.gif"
alt="Zone de Texte: REGLES DE L’ART / GOLDEN RULES" v:shapes="Text_x0020_Box_x0020_3"><![endif]><!--[if mso & !supportInlineShapes & supportFields]><v:shape
 id="_x0000_i1026" type="#_x0000_t75" style="width:517.3pt;height:17.65pt">
 <v:imagedata croptop="-65520f" cropbottom="65520f"/>
</v:shape><span style="mso-element:field-end"></span><![endif]--><span
style="font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:\"Times New Roman\",\"serif\";
mso-hansi-font-family:Arial;mso-bidi-font-family:Arial"><o:p></o:p></span></p>

<p class=MsoBodyText style="margin-top:.5pt">
<span style="font-size:5.0pt;mso-bidi-font-size:5.0pt;font-family:\"Times New Roman\",\"serif\";mso-hansi-font-family:Arial;mso-bidi-font-family:Arial"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
1.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">LIRE, COMPRENDRE ET UTILISER SYSTEMATIQUEMENT LA DOCUMENTATION AU DERNIER INDICE EN VIGUEUR</span>
</b>
conformément au travail à exécuter. Ne pas travailler par « habitude ».
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
2.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">UTILISER L\'OUTILLAGE STRICTEMENT NECESSAIRE</span>
</b>
, du matériel et des produits conformes et validés.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
3.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">CONNAITRE LES QUALIFICATIONS ET LES HABILITATIONS NECESSAIRES</span>
</b>
 pour réaliser le travail que je dois exécuter.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
4.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">NE PAS TRAVAILLER SANS MON TUTEUR</span>
</b>
 pour réaliser le travail que je dois exécuter si je suis en cours de parrainage, écolage, formation,...
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
5.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">RESPECTER SANS EXCEPTION LES REGLES RELATIVES A LA SANTE, HYGIENE & SECURITE ET PORTER SYSTEMATIQUEMENT LES E.P.I</span>
</b>
 requis à mon poste de travail.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
6.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">RESPECTER LES REGLES FOD</span>
</b>
 avant, pendant et après la réalisation du travail :<br>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Gravage des outils <br>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Réalisation des inventaires <br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Déclaration de perte <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Clean as you go <br>
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
7.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">LOCALISER CORRECTEMENT LA ZONE DE TRAVAIL ET PROTEGER</span>
</b>
 les zones sensibles avant toute intervention et les éléments d\'aéronefs (montés ou en attente de montage).
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
8.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">REALISER ET VERIFIER LA CONFORMITE DE MON TRAVAIL</span>
</b>
 avant de passer à l\'opération suivante.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
9.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">MAINTENIR PRORE</span>
</b>
 et ordonnée la zone de travail.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
10.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">GARANTIR LA TRACABILITE DE MON TRAVAIL</span>
</b>
 en renseignant la documentation interne et/ou client appropriée (gammes, fiches suiveuses, étiquettes, fiche d\'intervention, Guide et relevés;...):<br>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Traçabilité Opération (Date, Nom + Signature ou Stamp) dans l\'ordre prévu <br>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Traçabilité Produit (Lot Mastic, Vernis, Peinture, etc...), Equipement (Clé à torquer, Go-No Go, Pied à coulisse, etc...), Pièces avionables, etc...
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
11.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">IDENTIFIER LE PRODUIT NON CONFORME ET L\'ISOLER</span>
</b>
 ou demander à l\'isoler dans la zone de quarantaine identifiée.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
12.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">VERIFIER, NETTOYER LA ZONE AVANT DE LA QUITTER ET REMETTRE EN PLACE</span>
</b>
 l\'ensemble des moyens utilisés.
</p>

<p style="page-break-before: always;">
</p>

<table cellpadding=0 cellspacing=0 align=left>
 <tr>
  <td></td>
  <td width=113 style="border:1px solid black"><img width=113 height=52 src="D-0745 CHARTE AERO-converti_fichiers/image002.jpg" v:shapes="image1.jpeg"></td>
  <td width=420 height=56 align="center" style="border:1px solid black;background-color:#e7e6e6;color:#002060;font-size:20px;"><img src="D-0745 CHARTE AERO-converti_fichiers/FlecheGauche.png"><b>CHARTE INDIVIDUELLE AERONAUTIQUE</b><img src="D-0745 CHARTE AERO-converti_fichiers/FlecheDroite.png"><br>« GOLDEN RULES »</td>
 </tr>
</table>

</span><![endif]><span style="font-size:10.0pt;mso-bidi-font-size:11.0pt;
font-family:\"Times New Roman\",\"serif\";mso-hansi-font-family:Arial;mso-bidi-font-family:
Arial"><o:p>&nbsp;</o:p></span></p>

<p class=MsoBodyText style="margin-top:.1pt"><span style="font-size:11.5pt;
mso-bidi-font-size:11.0pt;font-family:\"Times New Roman\",\"serif\";mso-hansi-font-family:
Arial;mso-bidi-font-family:Arial"><o:p>&nbsp;</o:p></span></p>

<p class=MsoBodyText style="margin-left:4.95pt"><!--[if mso & !supportInlineShapes & supportFields]><span
style="mso-element:field-begin;mso-field-lock:yes"></span><span
style="mso-spacerun:yes"> </span>SHAPE <span
style="mso-spacerun:yes"> </span>\* MERGEFORMAT <span style="mso-element:field-separator"></span><![endif]--><!--[if gte vml 1]><v:shapetype
 id="_x0000_t202" coordsize="21600,21600" o:spt="202" path="m,l,21600r21600,l21600,xe">
 <v:stroke joinstyle="miter"/>
 <v:path gradientshapeok="t" o:connecttype="rect"/>
</v:shapetype><v:shape id="Text_x0020_Box_x0020_3" o:spid="_x0000_s1028"
 type="#_x0000_t202" style="width:517.3pt;height:17.65pt;visibility:visible;
 mso-wrap-style:square;mso-left-percent:-10001;mso-top-percent:-10001;
 mso-position-horizontal:absolute;mso-position-horizontal-relative:char;
 mso-position-vertical:absolute;mso-position-vertical-relative:line;
 mso-left-percent:-10001;mso-top-percent:-10001;v-text-anchor:top" o:gfxdata="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
" fillcolor="#001f5f" strokeweight=".16936mm">
 <v:textbox inset="0,0,0,0">
  <![if !mso]>
  <table cellpadding=0 cellspacing=0 width="100%">
   <tr>
    <td><![endif]>
    <div>
    <p class=MsoNormal style="margin-top:.3pt;margin-right:0cm;margin-bottom:
    0cm;margin-left:158.9pt;margin-bottom:.0001pt"><b style="mso-bidi-font-weight:
    normal"><span style="font-size:14.0pt;mso-bidi-font-size:11.0pt;color:white">REGLES
    DE L’ART </span></b><b style="mso-bidi-font-weight:normal"><span
    style="font-size:14.0pt;mso-bidi-font-size:11.0pt;font-family:\"Trebuchet MS\",\"sans-serif\";
    color:white">/ GOLDEN RULES</span></b><b style="mso-bidi-font-weight:normal"><span
    style="font-size:14.0pt;mso-bidi-font-size:11.0pt;font-family:\"Trebuchet MS\",\"sans-serif\""><o:p></o:p></span></b></p>
    </div>
    <![if !mso]></td>
   </tr>
  </table>
  <![endif]></v:textbox>
 <w:wrap type="none"/>
 <w:anchorlock/>
</v:shape><![endif]--><![if !vml]><img width=696 height=30 src="D-0745 CHARTE AERO-converti_fichiers/image005.gif"
alt="Zone de Texte: REGLES DE L’ART / GOLDEN RULES" v:shapes="Text_x0020_Box_x0020_3"><![endif]><!--[if mso & !supportInlineShapes & supportFields]><v:shape
 id="_x0000_i1026" type="#_x0000_t75" style="width:517.3pt;height:17.65pt">
 <v:imagedata croptop="-65520f" cropbottom="65520f"/>
</v:shape><span style="mso-element:field-end"></span><![endif]--><span
style="font-size:10.0pt;mso-bidi-font-size:11.0pt;font-family:\"Times New Roman\",\"serif\";
mso-hansi-font-family:Arial;mso-bidi-font-family:Arial"><o:p></o:p></span></p>

<p class=MsoBodyText style="margin-top:.5pt">
<span style="font-size:5.0pt;mso-bidi-font-size:5.0pt;font-family:\"Times New Roman\",\"serif\";mso-hansi-font-family:Arial;mso-bidi-font-family:Arial"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
1.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">AVOIR CONSCIENCE D\'EVOLUER DANS UN CONTEXTE AERONAUTIQUE</span>
</b>
dans lequel je contribue à la sécurité des personnes et des biens
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
2.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">S\'ENGAGER ENVERS LA QUALITE ET LA SECURITE DES PERSONNES ET DES PRODUITS :</span>
</b>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Signaler immédiatement tous problèmes liés à la sécurité
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp; Connaître ses responsabilités en terme de Qualité tout au long du cycle de vie des produits et ne jamais accepter une quelconque concession en terme de qualité ou de sécurité
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
3.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">S\'ENGAGER A RESPECTER LES REGLES ENVIRONNEMENTALES</span>
</b>
 applicables sur mon lieu de travail
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
4.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">CONNAITRE, APPLIQUER ET FAIRE APPLIQUER LES REGLES,</span>
</b>
 les processus et les procédures applicables à mon environnement.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
5.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">SIGNALER SANS DELAI A MA HIERARCHIE</span>
</b>
 et aux services compétents toute anomalie produite ou constatée (difficultés de réalisation, absence des documents de travail, de formation, malfaçon, pièces suspectées d\'être contrefaites,FOD, problème éthique, etc...).
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
6.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">ETRE RIGOUREUX, PROFESSIONNEL, INTEGRE ET LOYAL</span>
</b>
 sont nos maîtres mots.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
7.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">FAVORISER LE DIALOGUE</span>
</b>
 ouvert et constructif fondé sur la confiance, le respect mutuel et l\'information.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
8.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">VALORISER L\'ESPRIT D\'EQUIPE.</span>
</b>
 Solidarité, disponibilité, échange d\'expérience et de connaissances, transmission de savoir-faire et savoir-être.
</p>

<p class=MsoBodyText style="margin-top:-3pt"><span style="font-size:5.5pt;
mso-bidi-font-size:5.0pt"><o:p>&nbsp;</o:p></span></p>

<p class=MsoListParagraph style="margin-right:7.3pt;line-height:90%;mso-list:l0 level1 lfo2;tab-stops:46.05pt;font-size:14px;">
<b><span style="font-family:\"Trebuchet MS\",\"sans-serif\";mso-fareast-font-family:\"Trebuchet MS\";mso-bidi-font-family:\"Trebuchet MS\";mso-font-width:81%">
9.&nbsp;</span></b>
<b style="mso-bidi-font-weight:normal">
	<span style="font-family:Trebuchet MS,sans-serif;background-color:#a9a9a9;">TRANSMETTRE MES CONNAISSANCES ET COMPETENCES</span>
</b>
 au sein de AAA
</p>

<br>

<h1 style="margin-right:7.2pt;text-align:justify;text-indent:0cm;line-height:
105%"><span style="font-family:"Trebuchet MS","sans-serif"">Le non-respect du code éthique peut avoir des conséquences graves et durables sur la réputation et les relations d’affaire de l’entreprise.
Aucun objectif de performance ne peut être imposé ni accepté si sa réalisation implique de déroger aux principes éthiques de l’entreprise.</h1>

<br>

<table class=TableNormal border=0 cellspacing=0 cellpadding=0 style="margin-left:
 5.15pt;border-collapse:collapse;mso-table-layout-alt:fixed;mso-yfti-tbllook:
 480;mso-padding-alt:0cm 0cm 0cm 0cm">
 <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:5.25pt">
  <td width=118 valign=top style="width:88.8pt;background:#F1F1F1;padding:0cm 0cm 0cm 0cm;
  height:5.25pt">
  <p class=TableParagraph><span style="mso-font-width:95%">Je soussigné :</span></p>
  </td>
  <td width=571 valign=top style="width:428.5pt;background:#F1F1F1;padding:
  0cm 0cm 0cm 0cm;height:5.25pt">
  <p class=TableParagraph style="margin-left:0cm"><span style="font-size:10.0pt;
  mso-bidi-font-size:11.0pt;font-family:"Times New Roman","serif";mso-hansi-font-family:
  Arial;mso-bidi-font-family:Arial"><o:p>&nbsp;</o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:1;height:14.65pt">
  <td width=118 valign=top style="width:88.8pt;background:#F1F1F1;padding:0cm 0cm 0cm 0cm;
  height:14.65pt">
  <p class=TableParagraph style="line-height:5.8pt;mso-line-height-rule:exactly">Nom :</p>
  </td>
  <td width=571 valign=top style="width:428.5pt;background:#F1F1F1;padding:
  0cm 0cm 0cm 0cm;height:10.65pt">
  <p class=TableParagraph style="margin-left:42.5pt;line-height:9.1pt;
  mso-line-height-rule:exactly"><b style="mso-bidi-font-weight:normal"><span
  style="font-size:12.0pt;mso-bidi-font-size:11.0pt;mso-font-width:80%">'.stripslashes($RowFormSessionPersonneDoc['NomStagiaire']).'</span></b><b
  style="mso-bidi-font-weight:normal"><span style="font-size:12.0pt;mso-bidi-font-size:
  11.0pt"><o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:2;height:14.65pt">
  <td width=118 valign=top style="width:88.8pt;background:#F1F1F1;padding:0cm 0cm 0cm 0cm;
  height:14.65pt">
  <p class=TableParagraph style="line-height:11.75pt;mso-line-height-rule:exactly">Prénom
  :</p>
  </td>
  <td width=571 valign=top style="width:428.5pt;background:#F1F1F1;padding:
  0cm 0cm 0cm 0cm;height:14.65pt">
  <p class=TableParagraph style="margin-left:42.5pt;line-height:13.05pt;
  mso-line-height-rule:exactly"><b style="mso-bidi-font-weight:normal"><span
  style="font-size:12.0pt;mso-bidi-font-size:11.0pt;mso-font-width:80%">'.stripslashes($RowFormSessionPersonneDoc['PrenomStagiaire']).'</span></b><b
  style="mso-bidi-font-weight:normal"><span style="font-size:12.0pt;mso-bidi-font-size:
  11.0pt"><o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:3;mso-yfti-lastrow:yes;height:12.55pt">
  <td width=690 colspan=2 valign=top style="width:517.3pt;background:#F1F1F1;
  padding:0cm 0cm 0cm 0cm;height:12.55pt">
  <p class=TableParagraph style="line-height:11.55pt;mso-line-height-rule:exactly">m’engage à mettre en œuvre et respecter les dispositions de cette charte individuelle aéronautique.</p>
  </td>
 </tr>
</table>


<p class=MsoBodyText><b style="mso-bidi-font-weight:normal"><span
style="font-size:10.0pt;mso-bidi-font-size:11.0pt"><o:p>&nbsp;</o:p></span></b></p>

<table class=TableNormal border=1 cellspacing=0 cellpadding=0 style="margin-left:101.3pt;border-collapse:collapse;mso-table-layout-alt:fixed;border:none;
 mso-border-alt:solid black .5pt;mso-yfti-tbllook:480;mso-padding-alt:0cm 0cm 0cm 0cm;mso-border-insideh:.5pt solid black;mso-border-insidev:.5pt solid black">
 <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;
  height:60.65pt">
  <td width=144 valign=top style="width:107.8pt;border:solid black 1.0pt;
  mso-border-alt:solid black .5pt;background:#F1F1F1;padding:0cm 0cm 0cm 0cm;
  height:60.65pt">
  <p class=TableParagraph style="margin-left:5.25pt">Date :</p><br>'.AfficheDateJJ_MM_AAAA($date).'
  </td>
  <td width=295 valign=top style="width:221.1pt;border:solid black 1.0pt;
  border-left:none;mso-border-left-alt:solid black .5pt;mso-border-alt:solid black .5pt;
  background:#F1F1F1;padding:0cm 0cm 0cm 0cm;height:60.65pt">
  <p class=TableParagraph style="margin-left:5.25pt">Signature :</p><br>"Signature électronique" '.stripslashes($RowFormSessionPersonneDoc['Stagiaire']).'
  </td>
 </tr>
</table>
</body>
';


$formulaire.='</html>';

//$dompdf->set_paper("a4", "landscape" ); 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

$canvas = $dompdf->get_canvas();
$font = 0;                  
$canvas->page_text(550, 770, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 10, array(0,0,0));
$canvas->page_text(200, 765, "DOCUMENT DIRECTION QUALITE AAA Group", $font, 10, array(0,0,0));
$canvas->page_text(190, 775, "Reproduction interdite sans autorisation écrite de AAA Group", $font, 10, array(0,0,0));
$canvas->page_text(10, 765, "D-0745 Edition 1", $font, 10, array(0,0,0));
$canvas->page_text(10, 775, "10/07/2019", $font, 10, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();
?>
