<?php
session_start();
require("../ConnexioniSansBody.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");

require_once '../../../dompdf_0-6-0_beta3/lib/html5lib/Parser.php';
require_once '../../../dompdf_0-6-0_beta3/src/Autoloader.php';
Dompdf\Autoloader::register();

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$formulaire='
<html style="background-color:#ffffff;">
	<head>
		<link type="text/css" href="../../CSS/FeuillePDF.css" rel="stylesheet" />
		<!--[if gte mso 9]><xml>
		 <w:WordDocument>
		  <w:SpellingState>Clean</w:SpellingState>
		  <w:GrammarState>Clean</w:GrammarState>
		  <w:TrackMoves>false</w:TrackMoves>
		  <w:TrackFormatting/>
		  <w:HyphenationZone>21</w:HyphenationZone>
		  <w:PunctuationKerning/>
		  <w:DrawingGridHorizontalSpacing>9.05 pt</w:DrawingGridHorizontalSpacing>
		  <w:DrawingGridVerticalSpacing>9.05 pt</w:DrawingGridVerticalSpacing>
		  <w:ValidateAgainstSchemas/>
		  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
		  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
		  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
		  <w:DoNotPromoteQF/>
		  <w:LidThemeOther>FR</w:LidThemeOther>
		  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
		  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
		  <w:DoNotShadeFormData/>
		  <w:Compatibility>
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
		 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="false"
		  DefSemiHidden="false" DefQFormat="false" LatentStyleCount="267">
		  <w:LsdException Locked="false" QFormat="true" Name="Normal"/>
		  <w:LsdException Locked="false" QFormat="true" Name="heading 1"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 2"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 3"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 4"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 5"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 6"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 7"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 8"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="heading 9"/>
		  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
		   QFormat="true" Name="caption"/>
		  <w:LsdException Locked="false" QFormat="true" Name="Title"/>
		  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
		  <w:LsdException Locked="false" QFormat="true" Name="Subtitle"/>
		  <w:LsdException Locked="false" QFormat="true" Name="Strong"/>
		  <w:LsdException Locked="false" QFormat="true" Name="Emphasis"/>
		  <w:LsdException Locked="false" Priority="99" Name="No List"/>
		  <w:LsdException Locked="false" Priority="99" SemiHidden="true"
		   Name="Placeholder Text"/>
		  <w:LsdException Locked="false" Priority="1" QFormat="true" Name="No Spacing"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 1"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 1"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 1"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 1"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 1"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 1"/>
		  <w:LsdException Locked="false" Priority="99" SemiHidden="true" Name="Revision"/>
		  <w:LsdException Locked="false" Priority="34" QFormat="true"
		   Name="List Paragraph"/>
		  <w:LsdException Locked="false" Priority="29" QFormat="true" Name="Quote"/>
		  <w:LsdException Locked="false" Priority="30" QFormat="true"
		   Name="Intense Quote"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 1"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 1"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 1"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 1"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 1"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 1"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 1"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 1"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 2"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 2"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 2"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 2"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 2"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 2"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 2"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 2"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 2"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 2"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 2"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 2"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 2"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 2"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 3"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 3"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 3"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 3"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 3"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 3"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 3"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 3"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 3"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 3"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 3"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 3"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 3"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 3"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 4"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 4"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 4"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 4"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 4"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 4"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 4"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 4"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 4"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 4"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 4"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 4"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 4"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 4"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 5"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 5"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 5"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 5"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 5"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 5"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 5"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 5"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 5"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 5"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 5"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 5"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 5"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 5"/>
		  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 6"/>
		  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 6"/>
		  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 6"/>
		  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 6"/>
		  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 6"/>
		  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 6"/>
		  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 6"/>
		  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 6"/>
		  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 6"/>
		  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 6"/>
		  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 6"/>
		  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 6"/>
		  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 6"/>
		  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 6"/>
		  <w:LsdException Locked="false" Priority="19" QFormat="true"
		   Name="Subtle Emphasis"/>
		  <w:LsdException Locked="false" Priority="21" QFormat="true"
		   Name="Intense Emphasis"/>
		  <w:LsdException Locked="false" Priority="31" QFormat="true"
		   Name="Subtle Reference"/>
		  <w:LsdException Locked="false" Priority="32" QFormat="true"
		   Name="Intense Reference"/>
		  <w:LsdException Locked="false" Priority="33" QFormat="true" Name="Book Title"/>
		  <w:LsdException Locked="false" Priority="37" SemiHidden="true"
		   UnhideWhenUsed="true" Name="Bibliography"/>
		  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
		   UnhideWhenUsed="true" QFormat="true" Name="TOC Heading"/>
		 </w:LatentStyles>
		</xml><![endif]-->
		<style>
			<!--
			 /* Font Definitions */
			 @font-face
				{font-family:Calibri;
				panose-1:2 15 5 2 2 2 4 3 2 4;
				mso-font-charset:0;
				mso-generic-font-family:swiss;
				mso-font-pitch:variable;
				mso-font-signature:-536870145 1073786111 1 0 415 0;}
			@font-face
				{font-family:Tahoma;
				panose-1:2 11 6 4 3 5 4 4 2 4;
				mso-font-charset:0;
				mso-generic-font-family:swiss;
				mso-font-format:other;
				mso-font-pitch:variable;
				mso-font-signature:3 0 0 0 1 0;}
			@font-face
				{font-family:Algerian;
				panose-1:4 2 7 5 4 10 2 6 7 2;
				mso-font-charset:0;
				mso-generic-font-family:decorative;
				mso-font-pitch:variable;
				mso-font-signature:3 0 0 0 1 0;
				src: url(../../../dompdf_0-6-0_beta3/lib/fonts/Algerian.ttf) format("truetype");
				}
			 /* Style Definitions */
			 p.MsoNormal, li.MsoNormal, div.MsoNormal
				{mso-style-unhide:no;
				mso-style-qformat:yes;
				mso-style-parent:"";
				margin:0cm;
				margin-bottom:.0001pt;
				mso-pagination:widow-orphan;
				font-size:12.0pt;
				font-family:"Times New Roman","serif";
				mso-fareast-font-family:"Times New Roman";}
			p.MsoHeader, li.MsoHeader, div.MsoHeader
				{mso-style-unhide:no;
				margin:0cm;
				margin-bottom:.0001pt;
				mso-pagination:widow-orphan;
				tab-stops:center 8.0cm right 16.0cm;
				font-size:12.0pt;
				font-family:"Times New Roman","serif";
				mso-fareast-font-family:"Times New Roman";}
			p.MsoFooter, li.MsoFooter, div.MsoFooter
				{mso-style-unhide:no;
				margin:0cm;
				margin-bottom:.0001pt;
				mso-pagination:widow-orphan;
				tab-stops:center 8.0cm right 16.0cm;
				font-size:12.0pt;
				font-family:"Times New Roman","serif";
				mso-fareast-font-family:"Times New Roman";}
			a:link, span.MsoHyperlink
				{mso-style-unhide:no;
				mso-style-parent:"";
				color:blue;
				text-decoration:underline;
				text-underline:single;}
			a:visited, span.MsoHyperlinkFollowed
				{mso-style-unhide:no;
				color:purple;
				mso-themecolor:followedhyperlink;
				text-decoration:underline;
				text-underline:single;}
			p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
				{mso-style-unhide:no;
				mso-style-link:"Texte de bulles Car";
				margin:0cm;
				margin-bottom:.0001pt;
				mso-pagination:widow-orphan;
				font-size:8.0pt;
				font-family:"Tahoma","sans-serif";
				mso-fareast-font-family:"Times New Roman";
				mso-bidi-font-family:Tahoma;}
			span.MsoPlaceholderText
				{mso-style-noshow:yes;
				mso-style-priority:99;
				mso-style-unhide:no;
				color:gray;}
			span.TextedebullesCar
				{mso-style-name:"Texte de bulles Car";
				mso-style-unhide:no;
				mso-style-locked:yes;
				mso-style-parent:"";
				mso-style-link:"Texte de bulles";
				mso-ansi-font-size:8.0pt;
				mso-bidi-font-size:8.0pt;
				font-family:"Tahoma","sans-serif";
				mso-ascii-font-family:Tahoma;
				mso-hansi-font-family:Tahoma;
				mso-bidi-font-family:Tahoma;}
			span.SpellE
				{mso-style-name:"";
				mso-spl-e:yes;}
			span.GramE
				{mso-style-name:"";
				mso-gram-e:yes;}
			.MsoChpDefault
				{mso-style-type:export-only;
				mso-default-props:yes;
				font-size:10.0pt;
				mso-ansi-font-size:10.0pt;
				mso-bidi-font-size:10.0pt;}
			 /* Page Definitions */
			 @page
				{mso-footnote-separator:url("Template_AttestationsFormations/header.htm") fs;
				mso-footnote-continuation-separator:url("Template_AttestationsFormations/header.htm") fcs;
				mso-endnote-separator:url("Template_AttestationsFormations/header.htm") es;
				mso-endnote-continuation-separator:url("Template_AttestationsFormations/header.htm") ecs;}
			@page WordSection1
				{size:841.9pt 595.3pt;
				mso-page-orientation:landscape;
				margin:1.0cm 31.9pt 1.0cm 36.0pt;
				mso-header-margin:35.45pt;
				mso-footer-margin:25.9pt;
				mso-header:url("Template_AttestationsFormations/header.htm") h1;
				mso-footer:url("Template_AttestationsFormations/header.htm") f1;
				mso-paper-source:0;}
			div.WordSection1
				{page:WordSection1;}
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
				mso-pagination:widow-orphan;
				font-size:10.0pt;
				font-family:"Times New Roman","serif";}
			</style>
			<![endif]--><!--[if gte mso 9]><xml>
			 <o:shapedefaults v:ext="edit" spidmax="2055"/>
			</xml><![endif]--><!--[if gte mso 9]><xml>
			 <o:shapelayout v:ext="edit">
			  <o:idmap v:ext="edit" data="1"/>
			 </o:shapelayout></xml><![endif]-->
	</head>
';

//Recherche des informations
$req = "
SELECT
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS STAGIAIRE,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS FORMATEUR,
	(SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,
	(SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=form_session.Id_Plateforme) AS Plateforme,
	(SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
	FROM form_formation_langue_infos
	WHERE Id_Formation=form_session.Id_Formation
	AND Id_Langue=
		(SELECT Id_Langue 
		FROM form_formation_plateforme_parametres 
		WHERE Id_Plateforme=form_session.Id_Plateforme
		AND Id_Formation=form_session.Id_Formation
		AND Suppr=0 
		LIMIT 1)
	AND Suppr=0) AS Formation,
	(SELECT IF(form_session.Recyclage=1,DureeRecyclage,Duree)
	FROM form_formation_plateforme_parametres
	WHERE Id_Formation=form_session.Id_Formation
	AND Id_Plateforme=form_session.Id_Plateforme
	AND form_formation_plateforme_parametres.Suppr=0 
	LIMIT 1) AS Duree,
	(SELECT DateSession 
	FROM form_session_date
	WHERE form_session_date.Id_Session=form_session.Id
	AND form_session_date.Suppr=0
	ORDER BY DateSession ASC 
	LIMIT 1) AS DateSession
FROM
	form_session_personne,
	form_session
WHERE
	form_session.Id=form_session_personne.Id_Session
	AND form_session_personne.Id=".$_GET['Id']."";
$ResultSession=mysqli_query($bdd,$req);
$RowSession=mysqli_fetch_array($ResultSession);

$formulaire.='
<body>
	<br>
	<p class=MsoNormal>
		
		<v:shapetype id="_x0000_t75" coordsize="21600,21600" o:spt="75"
		 o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe" filled="f" stroked="f">
		 <v:stroke joinstyle="miter"/>
		 <v:formulas>
		  <v:f eqn="if lineDrawn pixelLineWidth 0"/>
		  <v:f eqn="sum @0 1 0"/>
		  <v:f eqn="sum 0 0 @1"/>
		  <v:f eqn="prod @2 1 2"/>
		  <v:f eqn="prod @3 21600 pixelWidth"/>
		  <v:f eqn="prod @3 21600 pixelHeight"/>
		  <v:f eqn="sum @0 0 1"/>
		  <v:f eqn="prod @6 1 2"/>
		  <v:f eqn="prod @7 21600 pixelWidth"/>
		  <v:f eqn="sum @8 21600 0"/>
		  <v:f eqn="prod @7 21600 pixelHeight"/>
		  <v:f eqn="sum @10 21600 0"/>
		 </v:formulas>
		 <v:path o:extrusionok="f" gradientshapeok="t" o:connecttype="rect"/>
		 
		 <o:lock v:ext="edit" aspectratio="t"/>
		</v:shapetype><v:shape id="Image_x0020_7" o:spid="_x0000_s1026" type="#_x0000_t75"alt="Logo AAA2" style="position:absolute;margin-left:10pt;margin-top:-36.75pt;
		 z-index:251657728;visibility:visible;
		 mso-wrap-style:square;mso-width-percent:0;mso-height-percent:0;
		 mso-wrap-distance-left:9pt;mso-wrap-distance-top:0;mso-wrap-distance-right:9pt;
		 mso-wrap-distance-bottom:0;mso-position-horizontal:absolute;
		 mso-position-horizontal-relative:text;mso-position-vertical:absolute;
		 mso-position-vertical-relative:text;mso-width-percent:0;mso-height-percent:0;
		 mso-width-relative:page;mso-height-relative:page">
		  <img width=1000 height=750 style="opacity:0.3;" src="Template_AttestationsFormations/image002.png">
		 <w:wrap type="square" side="left"/>
		</v:shape></p>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img width=200 height=100 src="Template_AttestationsFormations/image001.png">
		<p class=MsoNormal align=center style="text-align:center"><b style="mso-bidi-font-weight:
		normal"><span style="font-size:10.0pt;mso-bidi-font-size:8.0pt">10, rue Mercœur
		– 75011 PARIS<o:p></o:p></span></b></p>

		<p class=MsoNormal align=center style="text-align:center"><b style="mso-bidi-font-weight:
		normal"><span style="font-size:10.0pt;mso-bidi-font-size:8.0pt">Téléphone&nbsp;:
		+33 (0)1 48 06 85 85<span style="mso-spacerun:yes">  </span>- Télécopie&nbsp;:
		+33 (0)1 48 06 32 19<o:p></o:p></span></b></p>

		<p class=MsoNormal align=center style="text-align:center"><b style="mso-bidi-font-weight:
		normal"><span lang=EN-GB style="font-size:10.0pt;mso-bidi-font-size:8.0pt;
		color:black;mso-ansi-language:EN-GB">Site <span class=GramE>Web&nbsp;:</span> </span></b><a
		href="http://www.aaa-aero.com/" title="blocked::http://www.aaa-aero.com/"><b
		style="mso-bidi-font-weight:normal"><span lang=EN-GB style="font-size:10.0pt;
		mso-bidi-font-size:8.0pt;color:windowtext;mso-ansi-language:EN-GB;text-decoration:
		none;text-underline:none">www.aaa-aero.com</span></b></a><b style="mso-bidi-font-weight:
		normal"><span lang=EN-GB style="font-size:10.0pt;mso-bidi-font-size:8.0pt;
		mso-ansi-language:EN-GB"><o:p></o:p></span></b></p>

		<p class=MsoNormal align=center style="text-align:center"><i style="mso-bidi-font-style:
		normal">
		<span style="font-size:48.0pt;font-family:Algerian;">
		Attestation de formation 
		</span></i><i style="mso-bidi-font-style:normal">
		<span lang=EN-GB
		style="font-size:36.0pt;mso-bidi-font-size:48.0pt;font-family:Algerian;
		mso-bidi-font-family:Arial;mso-effects-shadow-color:black;mso-effects-shadow-alpha:
		40.0%;mso-effects-shadow-dpiradius:4.0pt;mso-effects-shadow-dpidistance:3.0pt;
		mso-effects-shadow-angledirection:2700000;mso-effects-shadow-align:topleft;
		mso-effects-shadow-pctsx:100.0%;mso-effects-shadow-pctsy:100.0%;mso-effects-shadow-anglekx:
		0;mso-effects-shadow-angleky:0;mso-ansi-language:EN-GB">
		<br>TRAINING CERTIFICATE
		</span></i><b
		style="mso-bidi-font-weight:normal"><i style="mso-bidi-font-style:normal"><span
		lang=EN-GB style="font-size:26.0pt;font-family:Algerian;mso-bidi-font-family:
		Arial;mso-effects-shadow-color:black;mso-effects-shadow-alpha:40.0%;mso-effects-shadow-dpiradius:
		4.0pt;mso-effects-shadow-dpidistance:3.0pt;mso-effects-shadow-angledirection:
		2700000;mso-effects-shadow-align:topleft;mso-effects-shadow-pctsx:100.0%;
		mso-effects-shadow-pctsy:100.0%;mso-effects-shadow-anglekx:0;mso-effects-shadow-angleky:
		0;mso-ansi-language:EN-GB"><o:p></o:p></span></i></b></p>
		
		<p class=MsoNormal align=center style="text-align:center"><span lang=EN-GB
		style="font-family:Algerian;mso-bidi-font-family:Arial;mso-ansi-language:EN-GB"><o:p>&nbsp;</o:p></span></p>

		<p class=MsoNormal style="margin-left:14.2pt;text-indent:-14.2pt;line-height:
		200%;tab-stops:14.2pt dotted 326.05pt 758.4pt"><span lang=EN-GB
		style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:EN-GB"><span
		style="mso-tab-count:1">     </span>Je <span class=SpellE>soussigné</span> (e)
		/ <i style="mso-bidi-font-style:normal">I, the undersigned<span class=GramE>, </span></i></span><!--[if supportFields]><i
		style="mso-bidi-font-style:normal"><span lang=EN-GB style="font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		class=GramE><span style="mso-spacerun:yes"> </span>Formateur </span></span></i><![endif]--><!--[if supportFields]><i
		style="mso-bidi-font-style:normal"><span lang=EN-GB style="font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></i><![endif]--><!--[if supportFields]><i
		style="mso-bidi-font-style:normal"><span lang=EN-GB style="font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		class=GramE><span style="mso-spacerun:yes"> </span>Formateur </span></span></i><![endif]--><!--[if supportFields]><i
		style="mso-bidi-font-style:normal"><span lang=EN-GB style="font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></i><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		class=GramE><span style="mso-spacerun:yes"> </span>Formateur </span></span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		class=GramE><span lang=EN-GB style="font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-tab-count:1 dotted">'.$RowSession['FORMATEUR'].'</span><span
		style="mso-spacerun:yes"> </span>,</span></span><span lang=EN-GB
		style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:EN-GB"> <span
		class=SpellE>intervenant</span> de la <span class=SpellE>Société</span> <span
		style="mso-spacerun:yes"> </span>/ <i style="mso-bidi-font-style:normal">trainer
		of the Company, Assistance Aéronautique et Aérospatiale</i></span><!--[if supportFields]><b><span lang=EN-GB
		style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>AAA </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		lang=EN-GB style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:
		EN-GB"><span style="mso-spacerun:yes"> </span>, <span class=SpellE>atteste</span>
		que / <i style="mso-bidi-font-style:normal">certify that, </i><span
		style="mso-spacerun:yes"> </span></span><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>Stagiaire </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		lang=EN-GB style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:
		EN-GB"><span style="mso-tab-count:1 dotted">'.$RowSession['STAGIAIRE'].' </span><span
		style="mso-spacerun:yes"> </span>a <span class=SpellE>suivi</span> la formation
		(<span class=SpellE>intitulé</span> + n° <span class=SpellE>réf</span>.) / <i
		style="mso-bidi-font-style:normal">has attended the training course
		(designation and ref. n°)</i> '.$RowSession['Formation'].'</span><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>Intitule </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		lang=EN-GB style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:
		EN-GB"><span style="mso-spacerun:yes"> </span>qui a <span class=SpellE>eu</span>
		lieu le (s)&nbsp;/ <i style="mso-bidi-font-style:normal">which took place on
		the </i></span><!--[if supportFields]><b><span lang=EN-GB style="font-family:
		"Calibri","sans-serif";mso-bidi-font-family:Calibri;mso-ansi-language:EN-GB"><span
		style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>DateFormation </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		lang=EN-GB style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:
		EN-GB"><span style="mso-tab-count:1 dotted">'.AfficheDateJJ_MM_AAAA($RowSession['DateSession']).' </span><span
		style="mso-spacerun:yes"> </span><span style="mso-spacerun:yes">  </span>à / <i
		style="mso-bidi-font-style:normal">in </i></span>'.$RowSession['Lieu'].'<!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>Lieu </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		lang=EN-GB style="font-family:"Arial","sans-serif";color:black;mso-ansi-language:
		EN-GB"><o:p></o:p></span></p>

		<p class=MsoNormal style="margin-top:6.0pt;margin-right:0cm;margin-bottom:6.0pt;
		margin-left:0cm;tab-stops:14.2pt dotted 326.05pt"><span lang=EN-GB
		style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";color:black;
		mso-ansi-language:EN-GB"><span style="mso-tab-count:1">     </span><span
		class=SpellE>Durée</span> de la formation (<span class=SpellE>en</span> <span
		class=SpellE>heures</span><span class=GramE>) <span
		style="mso-spacerun:yes"> </span>/</span> <i style="mso-bidi-font-style:normal">Duration
		of the training course (in hours)&nbsp;: </i></span><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>Duree </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><i
		style="mso-bidi-font-style:normal"><span lang=EN-GB style="mso-bidi-font-size:
		14.0pt;font-family:"Arial","sans-serif";color:black;mso-ansi-language:EN-GB">'.str_replace(".","h",$RowSession['Duree']).'</span></i><span
		lang=EN-GB style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"> <span style="mso-tab-count:1 dotted">...................... </span><o:p></o:p></span></p>

		<p class=MsoNormal style="tab-stops:2.0cm dotted 205.55pt blank 517.4pt"><span
		lang=EN-GB style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-tab-count:1">                   </span>Fait
		à / <i style="mso-bidi-font-style:normal">Made <span class=GramE>in<span
		style="font-style:normal">&nbsp;:</span></span></i> </span><!--[if supportFields]><b><span
		style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri"><span
		style="mso-element:field-begin"></span></span></b><b><span lang=EN-GB
		style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-spacerun:yes"> </span>Plateforme </span></b><![endif]--><!--[if supportFields]><b><span
		style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri"><span
		style="mso-element:field-end"></span></span></b><![endif]--><span lang=EN-GB
		style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";color:black;
		mso-ansi-language:EN-GB"><span style="mso-tab-count:2 dotted">'.$RowSession['Plateforme'].'............................. </span>Visa
		<span class=SpellE>Formateur</span></span><span lang=EN-GB style="font-family:
		"Arial","sans-serif";color:black;mso-ansi-language:EN-GB">&nbsp;/ <i
		style="mso-bidi-font-style:normal">Trainer’s signature</i> : '.$RowSession['FORMATEUR'].' "signature électronique"'.'<o:p></o:p></span></p>

		<p class=MsoNormal style="margin-top:6.0pt;tab-stops:2.0cm dotted 205.55pt"><span
		lang=EN-GB style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";
		color:black;mso-ansi-language:EN-GB"><span style="mso-tab-count:1">                   </span></span><span
		style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";color:black">Le
		/ <i style="mso-bidi-font-style:normal">Date</i>&nbsp;:</span><b><span
		style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri"> </span></b><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>DateSession </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";color:black"><span
		style="mso-tab-count:1 dotted">'.AfficheDateJJ_MM_AAAA($RowSession['DateSession']).' </span><span
		style="mso-tab-count:1 dotted">. </span><span style="mso-tab-count:9">                                                                                                          </span><span
		style="mso-tab-count:1">                   </span></span><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-begin"></span><span
		style="mso-spacerun:yes"> </span>VISA </span></b><![endif]--><!--[if supportFields]><b><span
		lang=EN-GB style="font-family:"Calibri","sans-serif";mso-bidi-font-family:Calibri;
		mso-ansi-language:EN-GB"><span style="mso-element:field-end"></span></span></b><![endif]--><span
		style="mso-bidi-font-size:14.0pt;font-family:"Arial","sans-serif";color:black"><o:p></o:p></span></p>
	<br><br><br><br>
	<table border=0 cellpadding=0 cellspacing=0 width=700>
	 <tr>
		<td width=60 align=center><img width="56" height="59" src="Template_AttestationsFormations/image004.jpg" ></td>
		  <td colspan=5 class=xl69 width=650 align=center>Siège Social : 10, rue
		  Mercœur - 75011 Paris - Tél. 33 (0)1 48 06 85 85 - Fax. 33 (0)1 48 06 32
		  19<br>
			Société par Actions Simplifiée au capital de 1.600.000 Euros<br>
			RCS Paris B 353 522 204 - N° Siret 353 522 204 00059 - Code NAF 3030 Z -
		  TVA FR52 353 522 204
		  </td>
		 <td width=60 align=center><img width="54" height="59" src="Template_AttestationsFormations/image003.jpg" ></td>
	</tr>
	</table>
</body>
';

$formulaire.="</html>";

$dompdf->set_paper("a4", "landscape" ); 
$dompdf->loadHtml(utf8_encode($formulaire));

// Render the HTML as PDF
$dompdf->render();

$canvas = $dompdf->get_canvas();
$font = 0;                  
$canvas->page_text(300, 560, "AAA  FR QUALITY MANAGEMENT DOCUMENT", $font, 10, array(0,0,0));
$canvas->page_text(290, 570, "Reproduction forbidden without written authorization by AAA FR", $font, 10, array(0,0,0));
$canvas->page_text(45, 560, "D-0726 –FR – Issue 1", $font, 10, array(0,0,0));
$canvas->page_text(45, 570, "01/09/2017", $font, 10, array(0,0,0));

// Output the generated PDF to Browser
$dompdf->stream();
?>