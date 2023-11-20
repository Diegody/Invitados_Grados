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
    $id_cerem = (isset($_POST['id_ceremonia'])) ? $_POST['id_ceremonia'] : "";
    $fecha = (isset($_POST['fecCeremonia'])) ? $_POST['fecCeremonia'] : "";
    $hora = (isset($_POST['horaCeremonia'])) ? $_POST['horaCeremonia'] : "";
    $nombre = (isset($_POST['nombreCeremonia'])) ? $_POST['nombreCeremonia'] : "";
    $descripcion = (isset($_POST['descCeremonia'])) ? $_POST['descCeremonia'] : "";
    $ciclo = (isset($_POST['cicloCeremonia'])) ? $_POST['cicloCeremonia'] : "";
    $estado = (isset($_POST['estCeremonia'])) ? $_POST['estCeremonia'] : "";
    $cant_inv = (isset($_POST['cantInv'])) ? $_POST['cantInv'] : "";

    $consulta = "SELECT COUNT(*) AS total FROM academico.GI_CEREMONIAS_TB WHERE ID_CEREMONIA = '$id_cerem'";
    $resultado = $dbi->GetOne($consulta, false, array($id_cerem));

    if ($resultado > 0) {
        echo "<script> alert('La ceremonia {$id_cerem} ya se encuentra creada.'); window.location= 'panel-fec.php' </script>";
    } else {
        $insercion = "INSERT INTO academico.GI_CEREMONIAS_TB VALUES('$id_cerem', TO_DATE('$fecha', 'YYYY-MM-DD'), '$hora', UPPER('$nombre'), UPPER('$descripcion'), '$estado', '$cant_inv', UPPER('$ciclo'))";
        $resultadoInsercion = $dbi->Execute($insercion);

        if ($resultadoInsercion) {
            echo "<script> alert('La ceremonia {$id_cerem} fue creada de manera correcta.'); window.location= 'panel-fec.php' </script>";
        } else {
            $error_info = $dbi->ErrorMsg();
            switch ($error_info) {
                case 'ORA-00001: unique constraint (ACADEMICO.PK_GRADUANDOS) violated':
                    $mensaje = "Ya existe este dato de graduado con su ceremonia";
                    break;
                case 'ORA-01400: no se puede realizar una inserción NULL en ("ACADEMICO"."GI_CEREMONIAS_TB"."ID_CEREMONIA")':
                    $mensaje = "No puede dejar campos vacios";
                    break;
                default:
                    $mensaje = "Error en la creación en la ceremonia";
                    break;
            }
            echo "<script> alert('No se pudo crear la ceremonia porque ocurrió un error: " . $mensaje . "'); window.location= 'panel-fec.php' </script>";
        }
    }
} catch (exception $e) {
    echo 'Error: ' . $e->getMessage();
}
