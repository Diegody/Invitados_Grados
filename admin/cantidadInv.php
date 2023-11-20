<?php
session_start();
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

$consulta  = "SELECT * FROM academico.DDI_PARTICIPANTES_TB P";
$ejecutar_consulta = $dbi->Execute($consulta);
$contar_consulta = $ejecutar_consulta->RecordCount();
//////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $relacion = (isset($_POST['edit-tipo'])) ? $_POST['edit-tipo'] : "";
        $doc_grad = (isset($_POST['edit-id'])) ? $_POST['edit-id'] : "";
        $nombre = (isset($_POST['edit-nombre'])) ? $_POST['edit-nombre'] : "";
        // $apellido = (isset($_POST['edit-apellido'])) ? $_POST['edit-apellido'] : "";
        // $email = (isset($_POST['edit-correo'])) ? $_POST['edit-correo'] : "";
        $cant = (isset($_POST['edit-id_est'])) ? $_POST['edit-id_est'] : "";


        $sql = "UPDATE academico.GI_RELACION_TB SET CANT_INV = '$cant' WHERE DOCUMENTO_GRADUANDO = '$doc_grad' AND ID_RELACION = '$relacion'";
        $params = array($cant, $doc_grad, $relacion);
        $resultado = $dbi->Execute($sql, $params);
        echo "<script> alert('Cantidad del graduando {$nombre} actualizada de manera correcta.'); window.location = 'panel-cant.php' </script>";
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $dbi->Close();
}
