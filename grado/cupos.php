<?php
session_start();
ob_start();
header('Content-Type: application/json');
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$timeout_duration = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: logout.php');
    exit();
}

include("../../bases_datos/adodb/adodb.inc.php");
include("../../bases_datos/usb_defglobales.inc");


$dbi = NewADOConnection("$motor_odb1");
$dbi->Connect($base_db1, $usuario_db1, $contra_db1);

if (!$dbi) {
    echo "Error en la conexión a la base de datos";
    exit;
}

if (isset($_POST['id_relacion'])) {
    $id_relacion = $_POST['id_relacion'];

    // $sql = "SELECT CANT_INV FROM academico.GI_RELACION_TB WHERE ID_RELACION = '$id_relacion'";
      $sql = "select (SELECT cant_inv FROM academico.GI_RELACION_TB where id_relacion = $id_relacion )-(Select count(*) from ACADEMICO.gi_invitados_tb where id_relacion = $id_relacion)CANT_INV from dual";
    $resultado = $dbi->Execute($sql);
    
    if ($resultado && !$resultado->EOF) {
        echo json_encode(['cant_inv' => $resultado->fields['CANT_INV']]);
    } else {
        echo json_encode(['cant_inv' => 0]);
    }
}
