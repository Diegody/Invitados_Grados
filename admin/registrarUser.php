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

try {
    $tipo_doc = (isset($_POST['tipo_identificacion'])) ? $_POST['tipo_identificacion'] : "";
    $doc_user = (isset($_POST['documento_user'])) ? $_POST['documento_user'] : "";
    $nombre = (isset($_POST['nombres'])) ? $_POST['nombres'] : "";
    $apellido = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "";
    $pass = (isset($_POST['clave'])) ? $_POST['clave'] : "";
    $perfil = (isset($_POST['tipo_rol'])) ? $_POST['tipo_rol'] : "";

    $consulta = "SELECT COUNT(*) AS total FROM academico.GI_GRADUANDOS_TB WHERE DOCUMENTO_GRADUANDO = '$doc_user'";
    $resultado = $dbi->GetOne($consulta, false, array($doc_user));

    if ($resultado > 0) {
        echo "<script> alert('El usuario {$nombre} ya está registrado.'); window.location= 'formU.php' </script>";
    } else {
        $insercion = "INSERT INTO academico.GI_GRADUANDOS_TB VALUES ('$doc_user', '$tipo_doc', UPPER('$nombre'), UPPER('$apellido'), '$pass', '$perfil', NULL, NULL, NULL, NULL)";
        $resultadoInsercion = $dbi->Execute($insercion);

        $insercionRelacion = "INSERT INTO academico.GI_RELACION_TB(DOCUMENTO_GRADUANDO, ID_CEREMONIA, ID_VPLANES, CANT_INV, ESTADO) VALUES ('$doc_user', NULL, NULL, NULL, 'HABILITADO')";
        $resultadoRelacion = $dbi->Execute($insercionRelacion);
        
        if ($resultadoInsercion && $resultadoRelacion) {
            echo "<script> alert('Registro de usuario {$nombre} registrado de manera correcta.'); window.location= 'formU.php' </script>";
        } else {
            echo "<script> alert('No se pudo registrar el usuario porque ya se encuentra en la base de datos.'); window.location= 'formU.php' </script>";
        }
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
