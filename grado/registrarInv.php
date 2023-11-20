<?php
session_start();
ob_start();
header('Content-Type: application/json');

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);

if (!$dbi) {
    echo "Error en la conexión a la base de datos";
    exit;
}

if (isset($_SESSION['id_usuario']) && isset($_SESSION['nombre_usuario']) && isset($_SESSION['apellido_usuario'])) {
    $id_est = $_SESSION['id_usuario'];
    $nombre_adm = $_SESSION['nombre_usuario'];
    $apellido_adm = $_SESSION['apellido_usuario'];
}

$id_relacion = (isset($_POST['opcionRel'])) ? $_POST['opcionRel'] : "";
$documento_inv = (isset($_POST['documento_inv'])) ? $_POST['documento_inv'] : "";

// Consulta para contar las relaciones del usuario
$consulta_relaciones = "SELECT COUNT(*) AS TOTAL_RELACIONES FROM academico.GI_RELACION_TB WHERE DOCUMENTO_GRADUANDO = '$id_est'";
$result_relaciones = $dbi->Execute($consulta_relaciones);
if ($result_relaciones && $result_relaciones->RecordCount() > 0) {
    $row_relaciones = $result_relaciones->FetchRow();
    $totalRelaciones = $row_relaciones['TOTAL_RELACIONES'];
}

$registroPermitido = false; // Variable para controlar si se permite el registro de invitados

$cantPermitida = 0; // Inicializamos con un valor predeterminado

// Verificar la cantidad de invitados registrados en cada relación
$consulta_invitados = "SELECT COUNT(*) AS CANTIDAD_INVITADOS FROM academico.GI_INVITADOS_TB WHERE ID_RELACION = $id_relacion";
$result_invitados = $dbi->Execute($consulta_invitados);

if ($result_invitados && $result_invitados->RecordCount() > 0) {
    $row_invitados = $result_invitados->FetchRow();
    $cantidadInvitados = $row_invitados['CANTIDAD_INVITADOS'];
}

$consulta_cantidad_permitida = "SELECT CANT_INV FROM academico.GI_RELACION_TB WHERE ID_RELACION = $id_relacion";
$result_cantidad_permitida = $dbi->Execute($consulta_cantidad_permitida);

if ($result_cantidad_permitida && $result_cantidad_permitida->RecordCount() > 0) {
    $row_cantidad_permitida = $result_cantidad_permitida->FetchRow();
    $cantPermitida = $row_cantidad_permitida['CANT_INV'];
}

// Verificar si la cantidad de invitados no supera la cantidad permitida en al menos una relación
if ($cantidadInvitados < $cantPermitida) {
    $registroPermitido = true;
}

// Verificar si el invitado ya está registrado en la relación (ceremonia)
$consulta_invitado_existente = "SELECT COUNT(*) AS EXISTE_INVITADO FROM academico.GI_INVITADOS_TB WHERE ID_RELACION = $id_relacion AND DOCUMENTO_INV = '$documento_inv'";
$result_invitado_existente = $dbi->Execute($consulta_invitado_existente);

if ($result_invitado_existente && $result_invitado_existente->RecordCount() > 0) {
    $row_invitado_existente = $result_invitado_existente->FetchRow();
    $existeInvitado = $row_invitado_existente['EXISTE_INVITADO'];

    if ($existeInvitado > 0) {
        $registroPermitido = false; // El invitado ya está registrado en esta relación
        echo json_encode(array(
            "success" => false,
            "message" => "Error. El invitado ya está registrado en la ceremonia seleccionada.",
            "alert" => "El invitado ya está registrado en esta ceremonia."
        ));
    }
}


if ($registroPermitido) {
    $tipo_identificacion = (isset($_POST['tipo_identificacion'])) ? $_POST['tipo_identificacion'] : "";
    $documento_inv = (isset($_POST['documento_inv'])) ? $_POST['documento_inv'] : "";
    $nombres = (isset($_POST['nombres'])) ? $_POST['nombres'] : "";
    $apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "";
    $correo = (isset($_POST['correo'])) ? $_POST['correo'] : "";
    $id_relacion = (isset($_POST['opcionRel'])) ? $_POST['opcionRel'] : "";

    try {
        $sql = "INSERT INTO academico.GI_INVITADOS_TB (DOCUMENTO_INV, TIPO_IDENTIFICACION, NOMBRES, APELLIDOS, CORREO, ID_RELACION)
        VALUES ('$documento_inv', '$tipo_identificacion', UPPER('$nombres'), UPPER('$apellidos'), '$correo', '$id_relacion')";
        $dbi->Execute($sql);
        echo json_encode(array("success" => true, "message" => "El invitado $nombres fue registrado de manera correcta."));
    } catch (Exception $e) {
        if ($e->getCode() == 1062) {
            echo json_encode(array("success" => false, "message" => "No se pudo registrar el invitado porque ya se encuentra en la base de datos."));
        } else {
            echo json_encode(array("success" => false, "message" => 'Error: ' . $e->getMessage()));
        }
    }
} else {
    echo json_encode(array("success" => false, "message" => "La cantidad máxima de invitados se ha superado en la ceremonia seleccionada."));
}

$dbi->Close();
