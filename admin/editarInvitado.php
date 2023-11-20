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
    die(json_encode(array("success" => false, "message" => "Error de conexión a la base de datos.")));
    exit;
}
//////////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tipo_doc = (isset($_POST['edit-tipo'])) ? $_POST['edit-tipo'] : "";
        $doc_inv = (isset($_POST['edit-id'])) ? $_POST['edit-id'] : "";
        $nombre = (isset($_POST['edit-nombre'])) ? $_POST['edit-nombre'] : "";
        $apellido = (isset($_POST['edit-apellido'])) ? $_POST['edit-apellido'] : "";
        $email = (isset($_POST['edit-correo'])) ? $_POST['edit-correo'] : "";
        $relacion = (isset($_POST['edit-id_rel'])) ? $_POST['edit-id_rel'] : "";


        $sql = "UPDATE academico.GI_INVITADOS_TB SET TIPO_IDENTIFICACION = '$tipo_doc', NOMBRES = '$nombre', APELLIDOS = '$apellido', CORREO = '$email' WHERE DOCUMENTO_INV = '$doc_inv' AND ID_RELACION = '$relacion'";
        // echo $sql;
        $params = array($doc_inv, $tipo_doc, $nombre, $apellido, $email);
        $resultado = $dbi->Execute($sql, $params);
        echo json_encode(array("success" => true, "message" => "Datos del invitado $nombre actualizados de manera correcta"));
    } catch (exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $dbi->Close();
}
