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

$tipo_doc = (isset($_POST['tipo_identificacion'])) ? $_POST['tipo_identificacion'] : "";
$doc_user = (isset($_POST['documento_user'])) ? $_POST['documento_user'] : "";
$nombre = (isset($_POST['nombres'])) ? $_POST['nombres'] : "";
$apellido = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : "";
$pass = (isset($_POST['clave'])) ? $_POST['clave'] : "";
$cantInv = (isset($_POST['cant_inv'])) ? $_POST['cant_inv'] : "";
$emailPer = (isset($_POST['emailPer'])) ? $_POST['emailPer'] : "";
$emailCorp = (isset($_POST['emailCorp'])) ? $_POST['emailCorp'] : "";
$numTel = (isset($_POST['numTel'])) ? $_POST['numTel'] : "";
$perfil = (isset($_POST['tipo_rol'])) ? $_POST['tipo_rol'] : "";
$id_cerem = (isset($_POST['id_cerem'])) ? $_POST['id_cerem'] : "";
$programEst = (isset($_POST['programEst'])) ? $_POST['programEst'] : "";
$cicloCeremonia = (isset($_POST['cicloCeremonia'])) ? $_POST['cicloCeremonia'] : "";

//echo "Documento: " . $tipo_doc . "| Tipo Doc: " . $doc_user . " |Nombre: " . $nombre . " |Apellido: " . $apellido . " |Pass: " . $pass . " |Invitados: " . $cantInv . " |email 1:" . $emailPer . " |Email 2: " . $emailCorp  . " |Telefono: " . $numTel . " |Perfil: " . $perfil . " |ID Ceremonia: " . $id_cerem . " |Programa: " . $programEst  . " ";

$consulta = "SELECT COUNT(*) AS total FROM academico.GI_GRADUANDOS_TB WHERE DOCUMENTO_GRADUANDO = '$doc_user'";
$total = $dbi->GetOne($consulta,false, array($doc_user));

try {
    if ($total > 0) {
        echo "<script> alert('El usuario ya está registrado.'); window.location= '#.php' </script>";
    } else {
        $dbi->BeginTrans();  // Inicio de la transacción

        $insercion = "INSERT INTO academico.GI_GRADUANDOS_TB(DOCUMENTO_GRADUANDO, TIPO_IDENTIFICACION, NOMBRES, APELLIDOS, CLAVE, PERFIL, CORREO_PERSONAL, CORREO_INSTITUCIONAL, TELEFONO, CICLO) 
                      VALUES ('$doc_user', '$tipo_doc', UPPER('$nombre'), UPPER('$apellido'), '$pass', '$perfil', '$emailPer', '$emailCorp', '$numTel', '$cicloCeremonia')";
        $resultadoInsercion = $dbi->Execute($insercion);

        $insercionRelacion = "INSERT INTO academico.GI_RELACION_TB(DOCUMENTO_GRADUANDO, ID_CEREMONIA, ID_VPLANES, CANT_INV, ESTADO)
                              VALUES ('$doc_user', '$id_cerem', '$programEst', '$cantInv', 'HABILITADO')";
        $resultadoRelacion = $dbi->Execute($insercionRelacion);

        //echo "SQL GRAD : " . $resultadoInsercion . "   ";
        if ($resultadoInsercion && $resultadoRelacion) {
            $dbi->CommitTrans();  // Confirmar transacción si ambas inserciones fueron exitosas
            echo "<script> alert('Registro Exitoso'); window.location= 'formG.php' </script>";
        } else {
            $dbi->RollbackTrans();  // Deshacer la transacción si alguna inserción falló
            echo "<script> alert('Error al registrar el graduando.'); window.location= 'formG.php' </script>";
        }
        
        echo "Error: " . $dbi->ErrorMsg();
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
