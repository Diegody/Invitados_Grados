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

//////////////////////////////


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['documento'])) {
            $documento_user = $_POST['documento'];

            $consulta = "DELETE FROM academico.GI_RELACION_TB WHERE DOCUMENTO_GRADUANDO = '$documento_user'";
            $resultadoRelacion = $dbi->Execute($consulta, array($documento_user));

            $sql = "DELETE FROM academico.GI_GRADUANDOS_TB WHERE DOCUMENTO_GRADUANDO = '$documento_user'";
            $resultadoUsuario = $dbi->Execute($sql, array($documento_user));

            if ($resultadoRelacion && $resultadoUsuario) {
                echo json_encode(array("success" => true, "message" => "Usuario {$documento_user} eliminado de manera correcta."));
            } else {
                $error_info = $dbi->ErrorMsg();
                switch ($error_info) {
                    case 'ORA-00001: unique constraint (ACADEMICO.PK_GRADUANDOS) violated':
                        $mensaje = "Ya existe este dato en los registros de graduados";
                        break;
                    case 'ORA-02292: integrity constraint (ACADEMICO.FK_RELACION_GRADUANDOS) violated - child record found':
                        $mensaje = "Registro secundario encontrado";
                        break;
                    default:
                        $mensaje = "Error en la eliminación en GI_GRADUADOS_TB";
                        break;
                }
                echo json_encode(array("success" => false, "message" => "Error al ejecutar la consulta SQL: " . $mensaje));
            }
        } else {
            echo json_encode(array("success" => false, "message" => "Documento no proporcionado."));
        }
    } catch (exception $e) {
        echo json_encode(array("success" => false, "message" => "Error al eliminar el graduando: " . $e->getMessage()));
    }

    $dbi->Close();
}
