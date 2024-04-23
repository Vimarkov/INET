<?php
// header
function head($rep)
{
    header('filename=' . $rep);
    header("Content-Disposition:attachment;filename=".basename($rep));
    header("Content-type:application/pdf");
    header("Content-Transfer-Encoding:binary");
    header("Content-Length:".filesize($rep));
    readfile($rep);
}
// pdf
head( $_GET['Doc'].'.pdf' );

?>