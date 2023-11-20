<?php

session_start();
ob_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);
if (!$dbi) {
    echo "<br>error <br>";
    exit;
}
//////////////////////////////
$palabraClave = "%" . strtolower($_GET['searchWord']) . "%";
$sql = "SELECT  STRM, DESCR FROM academico.PS_TERM_TBL WHERE ACAD_CAREER = 'PREG' AND WEEKS_OF_INSTRUCT = 16 AND STRM >= 2366 AND LOWER(DESCR) LIKE '%" . $palabraClave . "%' ORDER BY STRM DESC";

$result = $dbi->Execute($sql, array($palabraClave));

if ($result && $result->RecordCount() > 0) {
    echo "<ul>";
    while (!$result->EOF) {
        $ciclo = $result->fields['STRM']; 
        echo "<li DESCR='$ciclo'>" . $result->fields['DESCR'] . "</li>"; // Agrega data-codigo
        $result->MoveNext();
    }
    echo "</ul>"; // Cierra la lista no ordenada
} else {
    echo "No se encontraron resultados.";
}

$dbi->Close();
?>
