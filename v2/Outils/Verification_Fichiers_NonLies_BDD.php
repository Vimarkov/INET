<?php
require_once("Connexioni.php");

$chemin   = "../../Upload/Fichiers";
function list_dir($name)
{
	
	global $bdd; 
	
	$DossierPere="";
	$Dossier1="";
	$Dossier2="";
	$camino="";
	$NbASupprimer=0;
	$TailleASupprimer=0;
	if ($dir1 = opendir($name))
	{
		while (false !== ($file = readdir($dir1)))
		{
			$camino=$name."/".$file;
			if(is_dir($camino))
			{
				if(!in_array($file, array(".","..")))
				{
					$DossierPere=$file;
					if ($dir2 = opendir($camino))
					{
						while (false !== ($file = readdir($dir2)))
						{
							if(!in_array($file, array(".","..")))
							{
								$camino=$name."/".$DossierPere."/".$file;
								if(is_dir($camino))
								{
									$Dossier1=$file;
									if ($dir3 = opendir($camino))
									{
										while (false !== ($file = readdir($dir3)))
										{
											if(!in_array($file, array(".","..")))
											{
												$camino=$name."/".$DossierPere."/".$Dossier1."/".$file;
												if(is_dir($camino))
												{
													$Dossier2=$file;
													if ($dir4 = opendir($camino))
													{
														while (false !== ($file = readdir($dir4)))
														{
															if(!in_array($file, array(".","..")))
															{
																$requete="SELECT Id FROM new_".$DossierPere." WHERE Dossier1='".$Dossier1."' AND Dossier2='".$Dossier2."' AND Fichier='".$file."'";
																$result=mysqli_query($bdd,$requete);
																$nbreponse=mysqli_num_rows($result);
																if($nbreponse==0)
																{
																	$NbASupprimer++;
																	//if(filesize($camino."/".$file)){$TailleASupprimer+=filesize($camino."/".$file);}
																	echo "Dossier Père : ".$DossierPere." , Dossier1 : ".$Dossier1." , Dossier2 : ".$Dossier2." , Fichier : ".$file."<BR>";
																}
															}
														}
														closedir($dir4);
													}
												}
												else
												{
													$requete="SELECT Id FROM new_".$DossierPere." WHERE Dossier1='".$Dossier1."' AND Dossier2='' AND Fichier='".$file."'";
													$result=mysqli_query($bdd,$requete);
													$nbreponse=mysqli_num_rows($result);
													if($nbreponse==0)
													{
														$NbASupprimer++;
														//if(filesize($camino."/".$file)){$TailleASupprimer+=filesize($camino."/".$file);}
														echo "Dossier Père : ".$DossierPere." , Dossier1 : ".$Dossier1." , Dossier2 : ".$Dossier2." , Fichier : ".$file."<BR>";
													}
												}
											}
										}
										closedir($dir3);
									}
								}
								else
								{
									$requete="SELECT Id FROM new_".$DossierPere." WHERE Dossier1='' AND Dossier2='' AND Fichier='".$file."'";
									$result=mysqli_query($bdd,$requete);
									$nbreponse=mysqli_num_rows($result);
									if($nbreponse==0)
									{
										$NbASupprimer++;
										//if(filesize($camino."/".$file)){$TailleASupprimer+=filesize($camino."/".$file);}
										echo "Dossier Père : ".$DossierPere." , Dossier1 : ".$Dossier1." , Dossier2 : ".$Dossier2." , Fichier : ".$file."<BR>";
									}
								}
							}
						}
						closedir($dir2);
					}
				}
			}
		}
		closedir($dir1);
	}
	echo "Nombre de fichiers à supprimer : ".$NbASupprimer;
	echo "<BR>Taille des fichiers : ".$TailleASupprimer;
}
list_dir($chemin);
?>