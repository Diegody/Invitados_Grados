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
$sql = "SELECT PLAN_ASIS, NOMBRE FROM ACADEMICO.V_PLANES WHERE MODALIDAD = 'PRE' AND PROGRAMA_ACTIVO  = 'S' AND OFERTA_WEB = 'S' AND LOWER(NOMBRE) LIKE '%" . $palabraClave . "%'";
$result = $dbi->Execute($sql, array($palabraClave));


if ($result && $result->RecordCount() > 0) {
    echo "<ul>";
    while (!$result->EOF) {
        $codigoFacultad = $result->fields['PLAN_ASIS']; 
        echo "<li NOMBRE='$codigoFacultad'>" . $result->fields['NOMBRE'] . "</li>"; // Agrega data-codigo
        $result->MoveNext();
    }
    echo "</ul>"; // Cierra la lista no ordenada
} else {
    echo "No se encontraron resultados.";
}

$dbi->Close();
?>
