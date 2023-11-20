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
$palabraClave = "%" . strtolower($_GET['searchWordc']) . "%";
$sql = "SELECT ID_CEREMONIA, NOMBRE FROM academico.GI_CEREMONIAS_TB WHERE LOWER(NOMBRE) LIKE '%" . $palabraClave . "%'";
$result = $dbi->Execute($sql, array($palabraClave));


if ($result && $result->RecordCount() > 0) {
    echo "<ul>";
    while (!$result->EOF) {
        $codigoCeremonia = $result->fields['ID_CEREMONIA']; 
        echo "<li NOMBRE='$codigoCeremonia'>" . $result->fields['NOMBRE'] . "</li>"; 
        $result->MoveNext();
    }
    echo "</ul>"; 
} else {
    echo "No se encontraron resultados.";
}

$dbi->Close();
