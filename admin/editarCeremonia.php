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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_ceremonia = (isset($_POST['edit-id'])) ? $_POST['edit-id'] : "";
        $nombre = (isset($_POST['edit-nombre'])) ? $_POST['edit-nombre'] : "";
        $fecha = (isset($_POST['edit-fecha'])) ? $_POST['edit-fecha'] : "";
        $hora = (isset($_POST['edit-hora'])) ? $_POST['edit-hora'] : "";
        $descripcion = (isset($_POST['edit-descripcion'])) ? $_POST['edit-descripcion'] : "";
        $cant_inv = (isset($_POST['edit-cant'])) ? $_POST['edit-cant'] : "";


        $sql = "UPDATE academico.GI_CEREMONIAS_TB SET FECHA = '$fecha', HORA = '$hora', NOMBRE = UPPER('$nombre'), DESCRIPCION = UPPER('$descripcion'), CANT_INV = '$cant_inv' WHERE ID_CEREMONIA = '$id_ceremonia'";
        // echo $sql;
        $params = array($id_ceremonia,$nombre, $fecha, $hora, $id_asis, $descripcion, $cant_inv);
        $resultado = $dbi->Execute($sql, $params);
        echo "<script> alert('Datos de la ceremonia {$id_ceremonia} actualizados de manera correcta.'); window.location = 'panel-fec.php' </script>";
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $dbi->Close();
}
