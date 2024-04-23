<html>
<head>
	<title><?php echo $_GET['Image']; ?></title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function resize()
		{
			imag=document.getElementById('Image');
			largeurImage=imag.width;
			hauteurImage=imag.height;
			if(parseInt(navigator.appVersion)>3){screenW = screen.width;screenH = screen.height;}
			else if(navigator.appName == "Netscape" && parseInt(navigator.appVersion)==3 && navigator.javaEnabled()) 
			{
			 var jToolkit = java.awt.Toolkit.getDefaultToolkit();
			 var jScreenSize = jToolkit.getScreenSize();
			 screenW = jScreenSize.width;
			 screenH = jScreenSize.height;
			}
			
			largeur=8;hauteur=35;
			if(largeurImage+largeur<screenW && hauteurImage+hauteur<screenH){window.resizeTo(largeurImage+largeur,hauteurImage+hauteur);}
			else
			{
				window.resizeTo(screenW-10,screenH-10);
				imag.width=screenW-10;
				imag.height=screenH-10;
			}
			window.moveTo(0,0);
		}
	</script>
</head>
<?php 
	if($_GET['Dossier2']!=""){$CheminImage="../../Upload/Images/".$_GET['Page']."/".$_GET['Dossier1']."/".$_GET['Dossier2']."/".$_GET['Image'];}
	else{$CheminImage="../../Upload/Images/".$_GET['Page']."/".$_GET['Dossier1']."/".$_GET['Image'];}
?>
<body onLoad="resize();" leftmargin="0" topmargin="0">
<img id="Image" src="<?php echo $CheminImage; ?>">
</body>
</html>