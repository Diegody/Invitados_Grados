<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$invitationCode = json_decode(file_get_contents('php://input'), true)['invitationCode'];

date_default_timezone_set('America/Bogota');
// Obtén la fecha y hora actual
$fecha_actual = date("d-m-Y h:i:s");

// Convierte la fecha y hora actual a un objeto DateTime
$fechaHora = DateTime::createFromFormat('d-m-Y h:i:s', $fecha_actual);

// Suma 4 minutos al objeto DateTime
$fechaHora->add(new DateInterval('PT4M49S'));

// Obtiene la nueva fecha y hora
$nuevaFechaHora = $fechaHora->format('d-m-Y h:i:s');


include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");

$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);
if (!$dbi) {
    echo "<br>Actualmente no hay una conexion de bases de datos<br>";
    exit;
}


$qperiodo = "SELECT * FROM academico.GI_INVITACIONES_TB WHERE EPOCH = $invitationCode AND ESTADO  = 'ACTIVO'";

$eqperiodo = $dbi->Execute($qperiodo);
$fqperiodo = $eqperiodo->RecordCount();


if ($fqperiodo > 0) {
    // Invitación encontrada y habilitada
    $response = array("valid" => true);
    $dbi->beginTrans();

    $qinsd = "UPDATE academico.GI_INVITACIONES_TB SET ESTADO = 'INACTIVO', FECHA = TO_DATE('$nuevaFechaHora', 'DD-MM-YYYY HH24:MI:SS') WHERE EPOCH = $invitationCode";
    error_log("$qinsd", 0);


    if (!$eqinsd = $dbi->Execute($qinsd)) {
        echo "<script>alert('No se pudo inactivar la invitación');</script>";
        $dbi->RollbackTrans();
        die();
    }

    $dbi->CommitTrans();
} else {
    // Invitación no encontrada o deshabilitada
    $response = array("valid" => false);
}

header('Content-Type: application/json');
echo json_encode($response);
