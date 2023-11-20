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
        $doc_inv = (isset($_POST['edit-doc'])) ? $_POST['edit-doc'] : "";
        $nombre = (isset($_POST['edit-nombre'])) ? $_POST['edit-nombre'] : "";
        $apellido = (isset($_POST['edit-apellido'])) ? $_POST['edit-apellido'] : "";
        $email = (isset($_POST['edit-email'])) ? $_POST['edit-email'] : "";
        $registro = (isset($_POST['edit-reg'])) ? $_POST['edit-reg'] : "";

        $conn = "UPDATE academico.GI_INVITADOS_TB SET TIPO_IDENTIFICACION = '$tipo_doc', DOCUMENTO_INV = '$doc_inv', NOMBRES = UPPER('$nombre'), APELLIDOS = UPPER('$apellido'), CORREO = '$email' WHERE ID_REGISTRO = '$registro'";
        $resultadoConn = $dbi->Execute($conn, array($tipo_doc, $doc_inv, $nombre, $apellido, $email, $registro));

        // Puedes verificar si ambos resultados son exitosos antes de enviar la respuesta
        if ($resultadoConn) {
            echo json_encode(array("success" => true, "message" => "Invitado $nombre actualizado de manera correcta."));
        } else {
            $error_info = $dbi->ErrorMsg();
            switch ($error_info) {
                case 'ORA-00001: unique constraint (ACADEMICO.PK_GRADUANDOS) violated':
                    $mensaje = "Ya existe este dato en los registros de graduados";
                    break;
                case 'ORA-01722: invalid number':
                    $mensaje = "Dato invalido. Intente otra vez";
                    break;
                default:
                    $mensaje = "Error en la actualización en GI_INVITADOS_TB: " . $dbi->ErrorMsg();
                    break;
            }
            echo json_encode(array("success" => false, "message" => "Error al ejecutar la consulta SQL: " .  $mensaje));
        }
    } catch (exception $e) {
        echo json_encode(array("success" => false, "message" => "Error al actualizar el graduando: " . $e->getMessage()));
    }

    $dbi->Close();
}
