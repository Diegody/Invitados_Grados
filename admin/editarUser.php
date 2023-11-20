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
        $tipo_doc = (isset($_POST['tipo_identificacionE'])) ? $_POST['tipo_identificacionE'] : "";
        $doc_user = (isset($_POST['documento_userE'])) ? $_POST['documento_userE'] : "";
        $nombre = (isset($_POST['nombresE'])) ? $_POST['nombresE'] : "";
        $apellido = (isset($_POST['apellidosE'])) ? $_POST['apellidosE'] : "";
        $clave = (isset($_POST['claveE'])) ? $_POST['claveE'] : "";
        $perfil = (isset($_POST['tipo_rolE'])) ? $_POST['tipo_rolE'] : "";

        $conn = "UPDATE academico.GI_GRADUANDOS_TB SET TIPO_IDENTIFICACION = '$tipo_doc', NOMBRES = UPPER('$nombre'), APELLIDOS = UPPER('$apellido'), CLAVE = '$clave', PERFIL = '$perfil' WHERE DOCUMENTO_GRADUANDO = '$doc_user'";
        $resultado = $dbi->Execute($conn, array($tipo_doc, $doc_user, $nombre, $apellido, $clave, $perfil));

        // echo "Consulta: ".$resultado;
        if ($resultado ) {
            echo "<script> alert('Usuario $nombre actualizado de manera correcta.'); window.location = 'formU.php' </script>";
        } else {
            $error_info = $dbi->ErrorMsg();
            switch ($error_info) {
                case 'ORA-00001: unique constraint (ACADEMICO.PK_GRADUANDOS) violated':
                    $mensaje = "Ya existe este dato en los registros de usuarios";
                    break;
                case 'ORA-01722: invalid number':
                    $mensaje = "Dato invalido. Intente otra vez";
                    break;
                default:
                    $mensaje = "Error en la actualización en GI_GRADUADOS_TB.";
                    break;
            }
            echo "<script> alert('Error al ejecutar la consulta SQL: $mensaje'); window.location = 'formU.php' </script>";
        }
    } catch (exception $e) {
        echo "<script> alert('Error al actualizar el usuario.'); window.location = 'formU.php' </script>";

        // echo json_encode(array("success" => false, "message" => "Error al actualizar el graduando: " . $e->getMessage()));
    }

    $dbi->Close();
}
