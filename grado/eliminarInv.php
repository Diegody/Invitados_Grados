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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['documento']) && isset($_POST['registro'])) {
            $documento_inv = $_POST['documento'];
            $registro = $_POST['registro'];

            // Primero, eliminar registros de GI_RELACION_TB
            $conn = "DELETE FROM academico.GI_INVITADOS_TB WHERE DOCUMENTO_INV = '$documento_inv' AND ID_REGISTRO = '$registro'";
            $resultado = $dbi->Execute($conn);

            // Puedes verificar si ambos resultados son exitosos antes de enviar la respuesta
            if ($resultado) {
                echo json_encode(array("success" => true, "message" => "Invitado $documento_inv eliminado de manera correcta."));
            } else {
                echo json_encode(array("success" => false, "message" => "Error al ejecutar la consulta SQL: " . $dbi->ErrorMsg()));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Documento no proporcionado."));
        }
    } catch (exception $e) {
        echo json_encode(array("success" => false, "message" => "Error al eliminar el Invitado: " . $e->getMessage()));
    }

    $dbi->Close();
}
